=== Meow Exit Intent ===
Contributors: jordymeow
Tags: exit intent, popup, modal, wordpress
Requires at least: 4.0
Tested up to: 6.3
Stable tag: 0.0.1
License: MIT
License URI: https://opensource.org/licenses/MIT

A simple and customizable exit intent popup for WordPress.

== Description ==

Meow Exit Intent is a lightweight WordPress plugin that displays a customizable exit intent popup to your website visitors. The popup is triggered when the user's mouse leaves the viewport, indicating they might be about to leave the site.

**Features:**

- Easy customization of popup content and styles.
- Target specific users based on login and admin status.
- Control the frequency of the popup display.
- Smooth animations and configurable delay.

== Installation ==

1. Upload the `meow-exit-intent` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Initialize the plugin by creating an instance of the `Meow_ExitIntent` class.

== Frequently Asked Questions ==

= How do I customize the popup content? =

You can pass your custom HTML content and CSS styles through the `content` and `content_css` parameters when instantiating the `Meow_ExitIntent` class.

= Can I control who sees the popup? =

Yes, you can use the `logged` and `admin` parameters to target specific user groups.

== Screenshots ==

1. Example of the exit intent popup.

== Changelog ==

= 0.0.1 =
* Initial release.

== Upgrade Notice ==

= 0.0.1 =
Initial release.

== License ==

This plugin is licensed under the MIT License.

== Author ==

Developed by [Jordy Meow](https://meowapps.com).
