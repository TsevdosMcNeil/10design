# Mailchimp


## Plugin

**[Statamic Mailchimp](https://statamic.com/addons/rad-pack/mailchimp)**

### Installation

You can install this package via composer using:

```bash
composer require statamic-rad-pack/mailchimp
```

The package will automatically register itself.

### Configuration

Set your Mailchimp API Key in your .env file. 
You can get it from: https://admin.mailchimp.com/account/api-key-popup/ and create a new key.

```bash
MAILCHIMP_API_KEY=your-key-here
```

Configure Mailchimp in the Control Panel

https://10sb.localhost/cp/mailchimp/config#users


### Usage

Create your Statamic forms as usual. When editing the form you'll see a *"Mailchimp Integration"* section where you can configure if and how that form integrates with your Mailchimp account.

Don't forget to add the consent field to your blueprint.

You can also manage if new users are added a list using the dedicated settings view in the control panel.