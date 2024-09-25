# Meow Exit Intent

A simple and customizable exit intent popup for WordPress.

## Description

Meow Exit Intent is a lightweight WordPress plugin that displays a customizable exit intent popup to your website visitors. The popup is triggered when the user's mouse leaves the viewport, indicating they might be about to leave the site.

## Features

- **Easy Customization:** Modify the content and styles of the popup using simple PHP variables.
- **Targeting Options:** Show the popup based on user login status, admin status, or specific domains.
- **Aggressive Mode:** Control how often the popup appears to users.
- **Animation and Delay:** Smooth animations and configurable delay before showing the popup.

## Installation

1. **Upload the Plugin:**
   - Download the plugin files.
   - Upload the `meow-exit-intent` folder to the `/wp-content/plugins/` directory.

2. **Activate the Plugin:**
   - Go to the 'Plugins' menu in WordPress and activate the 'Meow Exit Intent' plugin.

3. **Initialize the Plugin:**
   - Copy the contents of `demo.php` into your theme's `functions.php` file or create a custom plugin.
   - **Important:** Use the `plugins_loaded` action hook as shown in the example code to ensure proper initialization.
   - Customize the `$modal_content` and `$content_css` variables as needed.

## Usage

### Initialization

To use the plugin, you need to create an instance of the `Meow_ExitIntent` class with your desired configuration. It's crucial to initialize the class **after all plugins have been loaded** to ensure that the `Meow_ExitIntent` class is available. This is achieved by using the `plugins_loaded` action hook.

**Example:**

```php
<?php
// Define the content of the modal
$modal_content = '
    <h2>Subscribe to Our Newsletter</h2>
    <p>Get the latest updates and offers.</p>
    <a href="https://example.com/signup" class="meow-button">Sign Up Now</a>
';

// Define the CSS styles for the content
$content_css = '
    .meow-modal-body {
        font-size: 15px;
    }

    .meow-modal-body p, .meow-modal-body h2, .meow-modal-body h3 {
        color: black;
    }
    
    .meow-modal-body h2 {
        margin-top: 0px;
        font-size: 28px;
    }

    .meow-button {
        display: block; /* Make the button a block element */
        width: 100%;    /* Full width */
        background-color: black;
        color: white;
        padding: 15px 0;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
    }

    .meow-button:hover {
        background-color: #333;
    }
';

// Initialize the Meow_ExitIntent class after plugins are loaded
add_action('plugins_loaded', 'my_custom_meow_exit_intent_init');

function my_custom_meow_exit_intent_init() {
    // Check if the class exists to prevent errors
    if (class_exists('Meow_ExitIntent')) {
        // Instantiate the Meow_ExitIntent class with options
        new Meow_ExitIntent(array(
            'domain'     => null,     // Set to a specific domain, e.g., 'example.com', or null for all domains
            'logged'     => null,     // Set to true (logged-in users), false (logged-out users), or null for all users
            'admin'      => null,     // Set to true (admins only), false (non-admins), or null for all users
            'aggressive' => false,    // Set to true or false to control the 'aggressive' setting
            'delay'      => 200,      // Delay in milliseconds before showing the modal
            'content'    => $modal_content,
            'content_css'=> $content_css
        ));
    }
}
?>
