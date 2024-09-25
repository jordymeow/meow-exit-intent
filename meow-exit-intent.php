<?php
/*
Plugin Name: Meow Exit Intent
Plugin URI: https://github.com/jordymeow/meow-exit-intent
Description: A simple and customizable exit intent popup for WordPress.
Version: 0.0.1
Author: Jordy Meow
Author URI: https://meowapps.com
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: meow-exit-intent
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Meow_ExitIntent {
    public $domain = null;       // If null, applies to all domains
    public $logged = null;       // null for all users, true for logged-in users, false for logged-out users
    public $admin = null;        // null for all users, true for admins only, false for non-admins
    public $aggressive = false;  // Controls the 'aggressive' setting in Ouibounce
    public $delay = 0;           // Delay before showing the modal (in milliseconds)
    private $modal_content;      // HTML content inside the modal
    private $content_css;        // CSS styles for the modal content

    public function __construct($args = array()) {
        // Assign values from $args
        if (isset($args['domain'])) {
            $this->domain = $args['domain'];
        }
        if (isset($args['logged'])) {
            $this->logged = $args['logged'];
        }
        if (isset($args['admin'])) {
            $this->admin = $args['admin'];
        }
        if (isset($args['aggressive'])) {
            $this->aggressive = $args['aggressive'];
        }
        if (isset($args['delay'])) {
            $this->delay = $args['delay'];
        }
        if (isset($args['content'])) {
            $this->modal_content = $args['content'];
        } else {
            // Default content if none provided
            $this->modal_content = '
                <h2>Before You Go...</h2>
                <p>Don\'t miss out on our special offer!</p>
                <a href="#" class="meow-button">Click Here</a>
            ';
        }
        if (isset($args['content_css'])) {
            $this->content_css = $args['content_css'];
        } else {
            // Default content CSS if none provided
            $this->content_css = '
                .meow-modal-body h2 {
                    font-size: 2em;
                    margin-bottom: 20px;
                }
                .meow-modal-body p {
                    font-size: 1.2em;
                    margin-bottom: 20px;
                }
                .meow-button {
                    display: inline-block;
                    background-color: #f60;
                    color: #fff;
                    padding: 15px 25px;
                    text-decoration: none;
                    font-size: 1.2em;
                    border-radius: 5px;
                }
                .meow-button:hover {
                    background-color: #e55a00;
                }
            ';
        }

        // Add action to initialize the popup
        add_action('wp_footer', array($this, 'output_html'));
    }

    public function output_html() {
        // Check if the popup should be displayed based on the rules
        if ($this->logged !== null) {
            if ($this->logged && !is_user_logged_in()) {
                return;
            }
            if (!$this->logged && is_user_logged_in()) {
                return;
            }
        }

        if ($this->admin !== null) {
            if ($this->admin && !current_user_can('administrator')) {
                return;
            }
            if (!$this->admin && current_user_can('administrator')) {
                return;
            }
        }

        if ($this->domain !== null) {
            $current_domain = $_SERVER['HTTP_HOST'];
            if (strpos($current_domain, $this->domain) === false) {
                return;
            }
        }

        ?>
        <!-- Meow Exit Intent Popup HTML -->
        <div id="meow-exit-intent-modal" style="display: none;">
            <div class="meow-underlay"></div>
            <div class="meow-modal">
                <div class="meow-modal-body">
                    <?php echo $this->modal_content; ?>
                </div>
            </div>
        </div>

        <!-- Inline CSS -->
        <style>
            /* Meow Exit Intent CSS */
            #meow-exit-intent-modal {
                font-family: 'Open Sans', sans-serif;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            .meow-underlay {
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                background-color: rgba(0,0,0,0.5);
                cursor: pointer;
                -webkit-animation: fadein 0.5s;
                animation: fadein 0.5s;
            }
            .meow-modal {
                width: 600px;
                max-width: 90%;
                background-color: #fff;
                z-index: 1;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border-radius: 4px;
                -webkit-animation: popin 0.3s;
                animation: popin 0.3s;
                padding: 30px;
                box-sizing: border-box;
                text-align: center;
            }
            /* Content CSS */
            <?php echo $this->content_css; ?>

            /* Animation Keyframes */
            @-webkit-keyframes fadein {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
            @-ms-keyframes fadein {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
            @keyframes fadein {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
            @-webkit-keyframes popin {
                0% {
                    -webkit-transform: translate(-50%, -50%) scale(0);
                    opacity: 0;
                }
                85% {
                    -webkit-transform: translate(-50%, -50%) scale(1.05);
                    opacity: 1;
                }
                100% {
                    -webkit-transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }
            }
            @-ms-keyframes popin {
                0% {
                    -ms-transform: translate(-50%, -50%) scale(0);
                    opacity: 0;
                }
                85% {
                    -ms-transform: translate(-50%, -50%) scale(1.05);
                    opacity: 1;
                }
                100% {
                    -ms-transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }
            }
            @keyframes popin {
                0% {
                    transform: translate(-50%, -50%) scale(0);
                    opacity: 0;
                }
                85% {
                    transform: translate(-50%, -50%) scale(1.05);
                    opacity: 1;
                }
                100% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }
            }
        </style>

        <!-- Inline JavaScript -->
        <script>
            (function(){
                // Ouibounce.js code (full version included inline)
                function ouibounce(el, config) {
                    var config = config || {},
                        aggressive = config.aggressive || false,
                        sensitivity = setDefault(config.sensitivity, 20),
                        timer = config.timer,
                        delay = setDefault(config.delay, 0),
                        callback = config.callback,
                        cookieExpire = setDefaultCookieExpire(config.cookieExpire) || '',
                        cookieDomain = config.cookieDomain ? ';domain=' + config.cookieDomain : '',
                        cookieName = config.cookieName ? config.cookieName : 'viewedOuibounceModal',
                        sitewide = config.sitewide === true ? ';path=/' : '',
                        _delayTimer = null,
                        _html = document.documentElement;

                    function setDefault(_property, _default) {
                        return typeof _property === 'undefined' ? _default : _property;
                    }

                    function setDefaultCookieExpire(days) {
                        // transform days to milliseconds
                        var ms = days*24*60*60*1000;

                        var date = new Date();
                        date.setTime(date.getTime() + ms);

                        return "; expires=" + date.toUTCString();
                    }

                    setTimeout(attachOuiBounce, delay);

                    function attachOuiBounce() {
                        if (isDisabled()) { return; }

                        _html.addEventListener('mouseout', handleMouseout);
                        _html.addEventListener('keydown', handleKeydown);
                    }

                    function handleMouseout(e) {
                        if (e.clientY > sensitivity || isDisabled()) return;

                        // return if the current mouse Y position is greater than the sensitivity
                        // or if the modal is already displayed or cookie exists
                        if (e.relatedTarget && e.relatedTarget.nodeName === 'HTML') return;
                        if (e.toElement && e.toElement.nodeName === 'HTML') return;

                        fire();
                    }

                    var disableKeydown = false;
                    function handleKeydown(e) {
                        if (disableKeydown || isDisabled()) return;
                        else if(!e.metaKey || e.keyCode !== 76) return;

                        disableKeydown = true;
                        fire();
                    }

                    function checkCookieValue(cookieName, value) {
                        // cookies are separated by '; '
                        var cookies = document.cookie.split('; ');
                        for (var i = 0; i < cookies.length; i++) {
                            var cookie = cookies[i].split('=');
                            if (cookie[0] === cookieName) {
                                return cookie[1] === value;
                            }
                        }
                        return false;
                    }

                    function isDisabled() {
                        return checkCookieValue(cookieName, 'true') && !aggressive;
                    }

                    function fire() {
                        if (isDisabled()) return;

                        if (el) el.style.display = 'block';
                        disable();

                        if (typeof callback === 'function') {
                            callback();
                        }
                    }

                    function disable(custom_options) {
                        var options = custom_options || {};

                        // you can pass a specific cookie expiration when using the OuiBounce API
                        var expires = setDefaultCookieExpire(typeof options.cookieExpire !== 'undefined' ? options.cookieExpire : config.cookieExpire);

                        document.cookie = cookieName + '=true' + expires + cookieDomain + sitewide;

                        // remove listeners
                        _html.removeEventListener('mouseout', handleMouseout);
                        _html.removeEventListener('keydown', handleKeydown);
                    }

                    return {
                        fire: fire,
                        disable: disable,
                        isDisabled: isDisabled
                    };
                }

                // Initialize Ouibounce on the popup element
                var ouibounceInstance = ouibounce(document.getElementById('meow-exit-intent-modal'), {
                    aggressive: <?php echo $this->aggressive ? 'true' : 'false'; ?>,
                    timer: 0,
                    delay: <?php echo $this->delay; ?>, // Delay before showing the modal
                    cookieExpire: 7, // Cookie expires in 7 days
                    callback: function() {
                        console.log('Exit intent popup triggered.');
                    }
                });

                // Hide the modal when clicking outside of it
                document.body.addEventListener('click', function() {
                    document.getElementById('meow-exit-intent-modal').style.display = 'none';
                });

                // Prevent closing when clicking inside the modal
                document.querySelector('#meow-exit-intent-modal .meow-modal').addEventListener('click', function(e) {
                    e.stopPropagation();
                });

            })();
        </script>
        <?php
    }
}
