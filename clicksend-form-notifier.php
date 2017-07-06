<?php

namespace Grav\Plugin;

use Grav\Common\Config\Config;
use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Uri;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class ClicksendFormNotifyPlugin
 * @package Grav\Plugin
 */
class ClicksendFormNotifierPlugin extends Plugin
{
    private $client;

    /**
     * Constructor.
     *
     * @param string $name
     * @param Grav $grav
     * @param Config $config
     */
    public function __construct($name, Grav $grav, Config $config = null)
    {

        $this->client = new Client([
            'base_uri' => 'http://rest.clicksend.com',
            'timeout' => 5
        ]);

        parent::__construct($name, $grav, $config);
    }

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin() OR !$this->isFormTriggered()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onFormProcessed' => ['onFormProcessed', 0]
        ]);
    }

    public function onFormProcessed()
    {
        $referer = $this->getReferrer($this->grav['uri']);

        $username = $this->config->get('plugins.clicksend-form-notifier.username');
        $apiKey = $this->config->get('plugins.clicksend-form-notifier.api_key');
        $from = $this->config->get('plugins.clicksend-form-notifier.from');
        $to = $this->config->get('plugins.clicksend-form-notifier.to');
        $body = $this->config->get('plugins.clicksend-form-notifier.body');

        $formName = $this->getFormName();

        // Replace placeholders.
        $body = trim(str_replace('{{FORM_NAME}}', $formName, $body));

        // Send SMS notification message.
        try {

            $response = $this->client->post('v3/sms/send', [
                'auth' => [$username, $apiKey],
                'json' => [
                    'messages' => [
                        [
                            'source' => 'grav',
                            'from' => $from,
                            'to' => $to,
                            'body' => $body
                        ]
                    ]
                ]
            ]);

            $result = \GuzzleHttp\json_decode($response->getBody());

            if ($response->getStatusCode() == 200) {

                return $this->grav->redirect($referer . '?' . http_build_query(['submitted' => 'sent']));

            }

            return $this->grav->redirect($referer . '?' . http_build_query(['submitted' => 'not_sent', 'error' => $result->response_code]));

        } catch (ClientException $e) {

            $result = \GuzzleHttp\json_decode($e->getResponse()->getBody());

            return $this->grav->redirect($referer . '?' . http_build_query(['submitted' => 'not_sent', 'error' => $result->response_code]));

        }

    }

    /**
     * Check if this is triggered by a form submit.
     *
     * @return bool
     */
    private function isFormTriggered()
    {
        return isset($_POST['__form-name__']) ? true : false;
    }

    /**
     * Get form name.
     *
     * @return string
     */
    private function getFormName()
    {
        return isset($_POST['__form-name__']) ? trim(strip_tags($_POST['__form-name__'])) : '';
    }

    /**
     * Get base referrer.
     *
     * @param Uri $uri
     * @return array|string
     */
    private function getReferrer(Uri $uri)
    {
        $referer = $uri->referrer('/', null);
        $referer = explode('?', $referer);
        $referer = isset($referer[0]) ? trim($referer[0]) : '/';

        return $referer;
    }
}
