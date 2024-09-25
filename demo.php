<?php
// Define the content of the modal
$modal_content = '
    <h2>Wait! Before You Go...</h2>
    <p>Subscribe to our newsletter to stay updated.</p>
    <a href="https://example.com/subscribe" class="meow-button">Subscribe Now</a>
';

// Define the CSS styles for the content
$content_css = '
    .meow-modal-body {
        font-size: 16px;
    }

    .meow-modal-body p, .meow-modal-body h2 {
        color: #333;
    }
    
    .meow-modal-body h2 {
        margin-top: 0px;
        font-size: 24px;
    }

    .meow-button {
        display: block;
        width: 100%;
        background-color: #0073aa;
        color: #fff;
        padding: 15px 0;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
    }

    .meow-button:hover {
        background-color: #005177;
    }
';

// Instantiate the Meow_ExitIntent class with options
new Meow_ExitIntent(array(
    'domain'     => null,
    'logged'     => null,
    'admin'      => true,      // Set to true to show to admins only (useful during development)
    'aggressive' => true,      // Set to true for testing; change to false for production
    'delay'      => 200,
    'content'    => $modal_content,
    'content_css'=> $content_css
));
?>
