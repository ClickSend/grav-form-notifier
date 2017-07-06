# Clicksend Form Notifier Plugin

A simple Grav plugin that allows you to send SMS notification every time someone submits your Grav form.
The **Clicksend Form Notifier** Plugin is for [Grav CMS](http://github.com/getgrav/grav). 

## Installation

Installing the Clicksend Form Notifier plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install clicksend-form-notifier

This will install the Clicksend Form Notifier plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/clicksend-form-notifier`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `clicksend-form-notifier`. You can find these files on [GitHub](https://github.com/omar-usman/grav-plugin-clicksend-form-notifier) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/clicksend-form-notifier
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/clicksend-form-notifier/clicksend-form-notifier.yaml` to `user/config/plugins/clicksend-form-notifier.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
username: your-clicksend-username
api_key: your-clicksend-password
from: 'From'
to: '09171234567'
body: 'You have new form submission on your {{FORM_NAME}} form.'
```

## Usage

* Create your Grav form. See: [Forms](https://learn.getgrav.org/forms)
* That's it. You should now be able to receive SMS notification automatically.