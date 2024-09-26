<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

class Meow_ExitIntent_Admin {
  public function __construct() {
    add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
    add_action( 'admin_init', array( $this, 'handle_actions' ) );
  }

  public function add_settings_page() {
    add_options_page(
      'Exit Intents',
      'Exit Intents',
      'manage_options',
      'meow-exit-intent',
      array( $this, 'render_settings_page' )
    );
  }

  public function handle_actions() {
    // Handle saving the popup
    $this->save_popup();

    // Handle reset metrics
    if ( isset( $_GET['reset_metrics'] ) && check_admin_referer( 'meow_reset_metrics' ) ) {
      $this->reset_metrics( sanitize_text_field( $_GET['reset_metrics'] ) );
    }
  }

  public function render_settings_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
      return;
    }

    // Get existing popups
    $popups = get_option( 'mwpopint_options', array() );

    // Handle edit or new popup
    $editing_popup = null;
    if ( isset( $_GET['edit'] ) ) {
      $edit_id = sanitize_text_field( $_GET['edit'] );
      foreach ( $popups as $popup ) {
        if ( $popup['id'] === $edit_id ) {
          $editing_popup = $popup;
          break;
        }
      }
    }

    // Handle delete popup
    if ( isset( $_GET['delete'] ) && check_admin_referer( 'meow_delete_popup' ) ) {
      $delete_id = sanitize_text_field( $_GET['delete'] );
      foreach ( $popups as $key => $popup ) {
        if ( $popup['id'] === $delete_id ) {
          unset( $popups[ $key ] );
          // Reindex the array
          $popups = array_values( $popups );
          update_option( 'mwpopint_options', $popups );
          echo '<div class="notice notice-success is-dismissible"><p>Popup deleted successfully.</p></div>';
          break;
        }
      }
    }

    // Include the settings page HTML
    include 'admin-view.php';
  }

  public function save_popup() {
    if ( isset( $_POST['meow_exit_intent_nonce'] ) && wp_verify_nonce( $_POST['meow_exit_intent_nonce'], 'save_meow_exit_intent' ) ) {
      // Unslash $_POST data
      $_POST = wp_unslash( $_POST );

      // Sanitize and save the popup data
      $popups = get_option( 'mwpopint_options', array() );

      $popup = array(
        'id'          => isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : uniqid(),
        'name'        => sanitize_text_field( $_POST['name'] ),
        'domain'      => sanitize_text_field( $_POST['domain'] ),
        'logged'      => $_POST['logged'],
        'admin'       => $_POST['admin'],
        'aggressive'  => isset( $_POST['aggressive'] ) ? true : false,
        'delay'       => intval( $_POST['delay'] ),
        'content'     => wp_kses_post( wp_encode_emoji( $_POST['content'] ) ),
        'content_css' => wp_strip_all_tags( $_POST['content_css'], false ),
      );

      if ( isset( $_POST['save_as_new'] ) ) {
        // Add as a new popup
        $popups[] = $popup;
        $redirect_id = $popup['id'];
      } else {
        // Update existing popup
        $found = false;
        foreach ( $popups as &$existing_popup ) {
          if ( $existing_popup['id'] === $popup['id'] ) {
            $existing_popup = $popup;
            $found = true;
            break;
          }
        }
        unset( $existing_popup );

        if ( ! $found ) {
          // Add new popup
          $popups[] = $popup;
        }
        $redirect_id = $popup['id'];
      }

      // Save data without applying wp_slash()
      update_option( 'mwpopint_options', $popups );

      // Redirect back to the same popup for continued editing
      wp_redirect( admin_url( 'options-general.php?page=meow-exit-intent&edit=' . $redirect_id . '&message=1' ) );
      exit;
    }
  }

  public function reset_metrics( $popup_id ) {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
      return;
    }

    // Get existing metrics
    $metrics = get_option( 'mwpopint_metrics', array() );

    // Reset metrics for the specific popup
    if ( isset( $metrics[ $popup_id ] ) ) {
      unset( $metrics[ $popup_id ] );
      update_option( 'mwpopint_metrics', $metrics );
      echo '<div class="notice notice-success is-dismissible"><p>Metrics reset successfully for Popup ID: ' . esc_html( $popup_id ) . '.</p></div>';
    }
  }
}

new Meow_ExitIntent_Admin();
