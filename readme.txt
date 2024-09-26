=== Meow Exit Intent ===
Contributors: Jordy Meow
Tags: exit intent, popup, modal, marketing, subscription, admin interface
Requires at least: 5.0
Tested up to: 6.3
Stable tag: trunk
License: MIT
License URI: https://opensource.org/licenses/MIT

A simple and customizable exit intent popup plugin for WordPress, now with an admin interface for easy popup management.

== Description ==

**Meow Exit Intent** is a lightweight and customizable WordPress plugin that allows you to display exit intent popups on your website. Capture your visitors' attention just before they leave and encourage them to take action, such as subscribing to your newsletter or checking out a special offer.

**Features:**

- **Admin Interface:** Easily create, edit, and delete popups directly from the WordPress admin dashboard.
- **Multiple Popups:** Manage multiple popups with different configurations.
- **User Targeting:** Display popups based on user login status (logged-in, logged-out, or all users) and user roles (admins or non-admins).
- **Domain Targeting:** Choose to display popups on specific domains or subdomains.
- **Customization:** Customize the HTML content and CSS styling of your popups.
- **Aggressive Mode:** Option to display the popup on every page load until the user interacts with it.
- **Delay Settings:** Set a delay before the popup appears after exit intent is detected.

**Why Use Meow Exit Intent?**

- **Increase Engagement:** Encourage visitors to stay longer, subscribe, or make a purchase.
- **Easy to Use:** No coding required. Set up and customize your popups through a user-friendly interface.
- **Lightweight:** Minimal impact on site performance.

== Installation ==

1. **Upload the Plugin:**
   - Upload the `meow-exit-intent` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.

2. **Activate the Plugin:**
   - Activate the plugin through the 'Plugins' screen in WordPress.

3. **Configure Popups:**
   - Navigate to **Settings > Meow Exit Intent** in your WordPress admin dashboard.
   - Create a new popup by filling out the form and customizing the content and settings to your liking.

== Frequently Asked Questions ==

= How do I create a new popup? =

- Go to **Settings > Meow Exit Intent** in your WordPress admin dashboard.
- Click on the "New Popup" button.
- Fill out the form with your desired settings, HTML content, and custom CSS.
- Click "Add Popup" to save your new popup.

= Can I have multiple popups on my site? =

Yes, you can create and manage multiple popups with different configurations from the admin interface.

= How do I target specific users with a popup? =

When creating or editing a popup, you can select options under "Logged-in Users" and "Admin Users" to target specific user groups:

- **Logged-in Users:** Choose to display the popup to all users, only logged-in users, or only logged-out users.
- **Admin Users:** Choose to display the popup to all users, only admin users, or only non-admin users.

= Can I display a popup only on certain domains or subdomains? =

Yes, you can specify a domain when creating or editing a popup. Enter the domain (e.g., `example.com` or `subdomain.example.com`) in the "Domain" field. Leave it blank to display the popup on all domains.

= What is Aggressive Mode? =

Aggressive Mode, when enabled, will display the popup on every page load until the user interacts with it. This can be useful for important announcements or promotions.

= How do I customize the popup's appearance? =

You can customize the HTML content and CSS styling of each popup:

- **HTML Content:** Use the built-in WordPress editor to design the content of your popup. You can include text, images, links, and formatting.
- **Custom CSS:** Add your own CSS in the "Custom CSS" field to style the popup content. The main container class is `.meow-modal-body`.

= Is the plugin compatible with my theme? =

Meow Exit Intent is designed to be compatible with most WordPress themes. Since you can fully customize the HTML and CSS of the popup, you can adjust the styling to match your theme.

= Will this plugin affect my site's performance? =

The plugin is lightweight and optimized to have minimal impact on your site's performance.

== Screenshots ==

1. **Admin Interface:** Easily manage your popups from the WordPress admin dashboard.
2. **Popup Example:** A sample exit intent popup displayed to the user.
3. **Customization:** Edit the HTML content and CSS styling of your popup.

== Changelog ==

= 0.0.1 =
* Initial release with admin interface for managing popups.

== License ==

This plugin is licensed under the MIT License.

== Additional Information ==

Contributions are welcome! You can contribute to the development of this plugin by visiting its [GitHub repository](https://github.com/jordymeow/meow-exit-intent).