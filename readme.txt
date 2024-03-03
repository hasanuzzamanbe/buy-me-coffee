=== Buy me coffee button & widget - fundraise into own account ===
Contributors: wpminers
Tags: buy me a coffee, donations, payments, stripe payments, fundraise
Requires at least: 4.5
Tested up to: 6.4.1
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
Text Domain: buy-me-coffee
Collect donations from "buy me a coffee" widgets directly into your own Stripe or PayPal account (upcoming).

[![Buy Me a Coffee](https://github.com/hasanuzzamanbe/buy-me-coffee/assets/43160844/11d39611-2195-439e-925b-77139c5f124d)](https://www.youtube.com/watch?v=m3T5LQ1DOEc&ab_channel=WPMiners)
### See Short Video
<a href="https://www.youtube.com/watch?v=m3T5LQ1DOEc" target="_blank">https://www.youtube.com/watch?v=m3T5LQ1DOEc</a>


== Description ==

[User Guide](https://wpminers.com/buymecoffee/docs/getting-started/quick-setup/) | [Demo](https://wpminers.com/buymecoffee-demo)

**Buy me a Coffee** is a free WordPress plugin that allows you to accept donations from your visitors. It is a simple and effective way to monetize your content. You can accept donations via PayPal or Stripe. The plugin is very easy to use and configure. You can add a PayPal donation button, Form, or template anywhere on your website using a shortcode or a widget.

**Buy Me a Coffee** is a perfect solution for content creators, bloggers, musicians, artists, developers, gamers, photographers, and all other types of content creators who want to accept donations (as Buy Coffee for Me) from their visitors.

You can use it for free without any limitations. You can accept donations from your visitors without any commission.
Donations are collected directly into your own PayPal or Stripe account merchant. You can accept donations in any currency supported by PayPal or Stripe.
one-time donations are available now will implement recurring donations in the future.
You can accept donations from your visitors using a Stripe and PayPal donation button, Form, or template. Accept donations from your visitors using a shortcode or a widget.
It Will be available using a popup or a page.

You can accept donations from your visitors using custom amounts.

Features:
Custom number of donations
Accept donations using Stripe and PayPal(upcoming)
Customizable templates
Form shortcode and widget
Buttons Shortcode and widget
Donor profiles
Donation statistics/reports
Quick setup mode
Coffee counter

## Installation
1. Download a release version and Upload the plugin files to the `/wp-content/plugins/buy-me-coffee` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Dashboard -> Quick setup screen to configure basic settings

## Frequently Asked Questions
#### How to use the plugin?
You can use the plugin for free without any limitations. You can accept donations from your visitors without any commission.
Donations are collected directly into your own PayPal or Stripe account merchant. You can accept donations in any currency supported by PayPal or Stripe.

#### How to accept donations?
You can accept coffee donation or custom amount donations by stripe pro, Also PayPal pro or PayPal standard from the next version.

#### Do you have a premium version?
No, we don't have a premium version. You can use the plugin for free without any limitations.

#### Do you have a recurring donation?
No, we don't have a recurring donation. We will implement recurring donations in the future.

#### Do I need coding skills to use the plugin?
No, you don't need coding skills to use the plugin. You can use the plugin without any coding skills.

#### Do you have documentation?
Yes, you can find the documentation on the [Buy me a coffee](https://wpminers.com/buymecoffee/docs/getting-started/quick-setup/) website.
though the plugin is very simple and easy to use so you don't need any documentation.

#### Is it secure?
Yes, it is secure. We don't store any data on our servers. All data is stored on your own PayPal or Stripe account.

## Screenshots
1. Admin Dashboard
2. Global Settings
3. Guided Quick setup
4. Buy me a coffee Preview
5. Stripe Payment settings

## Changelog
= 1.0.0 March 3, 2024=
* Initial release

# Development Docs
#### CDN used for Payments:
* [Stripe SDK](https://js.stripe.com/v3/)
  is used to create a Stripe payment button and collect donations from your visitors. It is required to create a Stripe payment element. There is a clear documentation on Stripe's website link above about how Stripe manage user data.

* [PayPal SDK](https://developer.paypal.com/sdk/js/reference/)
  is used to create a PayPal donation button and collect donations from your visitors. It is required to create a PayPal donation button. There is a clear documentation on PayPal's website link above about how PayPal manage user data.

#### 3rd Party services:
* [Stripe](https://www.stripe.com)
  is used to collect payments from users. where the sdk client library is used. [Stripe SDK](https://js.stripe.com/v3/)
  We use [stripe api] (https://api.stripe.com/v1/) to authenticate and process payment through stripe. We won't store any card info
  or other private data. Only required things are stored like a Stripe Public key, Secret key.
  you may check the full privacy policy from here,
  [Stripe privacy policy] (https://stripe.com/privacy)

* [PayPal](https://www.paypal.com/) (Upcoming feature)
  is used to collect donations from your visitors. It is required to create a PayPal donation button. There is a clear documentation on PayPal's website link above about how PayPal manage user data.
  for PayPal sandbox you may use [PayPal Sandbox](https://developer.paypal.com/docs/api-basics/sandbox/accounts/).
  For PayPal IPN BuyMeCoffee plugin use [PayPal IPN](https://www.sandbox.paypal.com/cgi-bin/webscr/) to verify the payment.
  And for library SDK [PayPal SDK](https://www.paypal.com/sdk/js?client-id=) where it requires your clientId.
#### PHP library used:
* [WP Fluent DB library](https://github.com/hasanuzzamanbe/wp-fluent/)
  This is just a Database library for WordPress. It is not a full-fledged ORM. It is a simple database library that makes working with the database easier. It is inspired by Laravel's Eloquent ORM. It's not collect any data from your site.
#### NPM Package used:
[element-plus/icons-vue](https://www.npmjs.com/package/@element-plus/icons-vue),[element-plus](https://www.npmjs.com/package/element-plus/),[@wordpress/hooks](https://www.npmjs.com/package/@wordpress/hooks),[chart.js](https://www.npmjs.com/package/chart.js),[clipboard](https://www.npmjs.com/package/clipboard),[lodash](https://www.npmjs.com/package/lodash),[moment](https://www.npmjs.com/package/moment),[vue](https://www.npmjs.com/package/vue),[vue-router](https://www.npmjs.com/package/vue-router)

#### Contribution guideline:
Go to [GitHub Repo](https://github.com/hasanuzzamanbe/buy-me-coffee) to see package.json for the full list of scripts and packages used in this project.
Follow the guidelines available in readme.