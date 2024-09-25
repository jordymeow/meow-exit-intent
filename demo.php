<?php

// Initialize the Meow_ExitIntent class after plugins are loaded
add_action('plugins_loaded', 'my_custom_meow_exit_intent_init');

function my_custom_meow_exit_intent_init() {
  // Check if the class exists to prevent conflicts
  if (class_exists('Meow_ExitIntent')) {

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

    // Instantiate the Meow_ExitIntent class with options
    new Meow_ExitIntent(array(
        'domain'     => null,     // Set to a specific domain, e.g., 'example.com', or null for all domains
        'logged'     => null,     // Set to true (logged-in users), false (logged-out users), or null for all users
        'admin'      => true,     // Set to true (admins only), false (non-admins), or null for all users
        'aggressive' => true,     // Set to true or false to control the 'aggressive' setting in Ouibounce
        'delay'      => 200,      // Delay in milliseconds before showing the modal
        'content'    => $modal_content,
        'content_css'=> $content_css
    ));
  }
}

?>
