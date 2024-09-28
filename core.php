<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

class Meow_ExitIntent {
  public $id = null;             // Unique ID for the popup
  public $domain = null;         // If null, applies to all domains
  public $logged = null;         // null for all users, true for logged-in users, false for logged-out users
  public $admin = null;          // null for all users, true for admins only, false for non-admins
  public $aggressive = false;    // Controls the 'aggressive' setting in NyaoBounce
  public $delay = 0;             // Delay before showing the modal (in milliseconds)
  private $modal_content;        // HTML content inside the modal
  private $content_css;          // CSS styles for the modal content

  private static $script_enqueued = false;

  public function __construct( $args = array() ) {
    // Assign values from $args
    if ( isset( $args['id'] ) ) {
      $this->id = $args['id'];
    }
    if ( isset( $args['domain'] ) ) {
      $this->domain = $args['domain'];
    }
    if ( isset( $args['logged'] ) ) {
      $this->logged = $args['logged'];
    }
    if ( isset( $args['admin'] ) ) {
      $this->admin = $args['admin'];
    }
    if ( isset( $args['aggressive'] ) ) {
      $this->aggressive = $args['aggressive'];
    }
    if ( isset( $args['delay'] ) ) {
      $this->delay = $args['delay'];
    }
    if ( isset( $args['content'] ) ) {
      $this->modal_content = $args['content'];
    } else {
      // Default content if none provided
      $this->modal_content = '
        <h2>Don\'t Miss Out!</h2>
        <p>Subscribe to our newsletter for the latest updates.</p>
        <a href="#" class="meow-button">Subscribe Now</a>
      ';
    }
    if ( isset( $args['content_css'] ) ) {
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
          background-color: #0073aa;
          color: #fff;
          padding: 15px 25px;
          text-decoration: none;
          font-size: 1.2em;
          border-radius: 5px;
        }
        .meow-button:hover {
          background-color: #005177;
        }
      ';
    }

    // Add action to initialize the popup
    add_action( 'wp_footer', array( $this, 'output_html' ), 5 );

    // Enqueue scripts when needed
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

  public function enqueue_scripts() {
    // Check if the popup should be displayed
    if ( ! $this->should_display_popup() ) {
      return;
    }

    // Enqueue nyaobounce.js only once
    if ( ! self::$script_enqueued ) {
      wp_enqueue_script( 'nyaobounce', plugin_dir_url( __FILE__ ) . 'js/nyaobounce.js', array( 'jquery' ), '0.0.2', true );
      self::$script_enqueued = true;
    }

    // Enqueue the main script
    wp_enqueue_script( 'meow-exit-intent', plugin_dir_url( __FILE__ ) . 'js/meow-exit-intent.js', array( 'jquery', 'nyaobounce' ), '0.0.2', true );

    // Localize script data
    wp_localize_script( 'meow-exit-intent', 'MeowExitIntentData', array(
      'ajax_url'   => admin_url( 'admin-ajax.php' ),
      'nonce'      => wp_create_nonce( 'meow_exit_intent_nonce' ),
      'popup_id'   => $this->id,
      'aggressive' => $this->aggressive,
      'delay'      => $this->delay,
    ) );
  }

  public function output_html() {
    // Check if the popup should be displayed based on the rules
    if ( ! $this->should_display_popup() ) {
      return;
    }

    // Decode HTML entities in the content
    $final_html = html_entity_decode( $this->modal_content );

    ?>
    <!-- Meow Exit Intent Popup HTML -->
    <div id="meow-exit-intent-modal-<?php echo esc_attr( $this->id ); ?>" class="meow-exit-intent-modal" style="display: none;">
      <div class="meow-underlay"></div>
      <div class="meow-modal">
        <div class="meow-modal-body">
          <?php echo $final_html; ?>
        </div>
      </div>
    </div>

    <!-- Inline CSS -->
    <style>
      /* Meow Exit Intent CSS */
      .meow-exit-intent-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
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
    <?php
  }

  private function should_display_popup() {
    // Check logged-in status
    if ( $this->logged !== null ) {
      if ( $this->logged && ! is_user_logged_in() ) {
        return false;
      }
      if ( ! $this->logged && is_user_logged_in() ) {
        return false;
      }
    }

    // Check admin status
    if ( $this->admin !== null ) {
      if ( $this->admin && ! current_user_can( 'administrator' ) ) {
        return false;
      }
      if ( ! $this->admin && current_user_can( 'administrator' ) ) {
        return false;
      }
    }

    // Check domain
    if ( ! empty( $this->domain ) ) {
      $current_domain = $_SERVER['HTTP_HOST'];

      // Check for wildcard subdomain match
      if ( strpos( $this->domain, '*.' ) === 0 ) {
        $domain_without_wildcard = substr( $this->domain, 2 ); // Remove '*.' from the beginning
        if ( substr( $current_domain, -strlen( $domain_without_wildcard ) ) !== $domain_without_wildcard ) {
          return false;
        }
      } else {
        // Strict equality check
        if ( $current_domain !== $this->domain ) {
          return false;
        }
      }
    }

    return true;
  }
}

// Initialize the popups based on saved options
add_action( 'plugins_loaded', 'meow_exit_intent_init' );

function meow_exit_intent_init() {
  // Get saved popups from the database
  $popups = get_option( 'mwpopint_options', array() );

  // Generate IDs for popups that don't have one
  $popups_updated = false;
  foreach ( $popups as &$popup ) {
    if ( ! isset( $popup['id'] ) ) {
      $popup['id'] = uniqid();
      $popups_updated = true;
    }
  }
  unset( $popup );

  if ( $popups_updated ) {
    update_option( 'mwpopint_options', $popups );
  }

  if ( ! empty( $popups ) ) {
    foreach ( $popups as $popup ) {
      // Ensure necessary fields are set
      if ( isset( $popup['content'] ) && isset( $popup['content_css'] ) ) {
        // Convert 'logged' and 'admin' parameters
        $logged = $popup['logged'] === 'all' ? null : ( $popup['logged'] === 'true' ? true : false );
        $admin  = $popup['admin'] === 'all' ? null : ( $popup['admin'] === 'true' ? true : false );

        new Meow_ExitIntent( array(
          'id'          => $popup['id'],
          'domain'      => $popup['domain'],
          'logged'      => $logged,
          'admin'       => $admin,
          'aggressive'  => $popup['aggressive'],
          'delay'       => $popup['delay'],
          'content'     => $popup['content'],
          'content_css' => $popup['content_css'],
        ) );
      }
    }
  }
}

// AJAX handler for tracking views
function meow_exit_intent_track_view() {
  check_ajax_referer( 'meow_exit_intent_nonce', 'security' );

  $popup_id = sanitize_text_field( $_POST['popup_id'] );

  // Get existing metrics
  $metrics = get_option( 'mwpopint_metrics', array() );

  if ( ! isset( $metrics[ $popup_id ] ) ) {
    $metrics[ $popup_id ] = array( 'views' => 0, 'clicks' => 0 );
  }

  $metrics[ $popup_id ]['views']++;

  update_option( 'mwpopint_metrics', $metrics );

  wp_send_json_success();
}
add_action( 'wp_ajax_nopriv_meow_exit_intent_track_view', 'meow_exit_intent_track_view' );
add_action( 'wp_ajax_meow_exit_intent_track_view', 'meow_exit_intent_track_view' );

// AJAX handler for tracking clicks
function meow_exit_intent_track_click() {
  check_ajax_referer( 'meow_exit_intent_nonce', 'security' );

  $popup_id = sanitize_text_field( $_POST['popup_id'] );

  // Get existing metrics
  $metrics = get_option( 'mwpopint_metrics', array() );

  if ( ! isset( $metrics[ $popup_id ] ) ) {
    $metrics[ $popup_id ] = array( 'views' => 0, 'clicks' => 0 );
  }

  $metrics[ $popup_id ]['clicks']++;

  update_option( 'mwpopint_metrics', $metrics );

  wp_send_json_success();
}
add_action( 'wp_ajax_nopriv_meow_exit_intent_track_click', 'meow_exit_intent_track_click' );
add_action( 'wp_ajax_meow_exit_intent_track_click', 'meow_exit_intent_track_click' );
