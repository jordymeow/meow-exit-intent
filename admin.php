<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

class Meow_ExitIntent_Admin {
  public function __construct() {
    add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
    add_action( 'admin_init', array( $this, 'save_popup' ) );
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
      $edit_index = intval( $_GET['edit'] );
      if ( isset( $popups[ $edit_index ] ) ) {
        $editing_popup = $popups[ $edit_index ];
        $editing_popup['index'] = $edit_index;
      }
    }

    // Handle delete popup
    if ( isset( $_GET['delete'] ) ) {
      $delete_index = intval( $_GET['delete'] );
      if ( isset( $popups[ $delete_index ] ) ) {
        unset( $popups[ $delete_index ] );
        // Reindex the array to prevent gaps
        $popups = array_values( $popups );
        update_option( 'mwpopint_options', $popups );
        echo '<div class="notice notice-success is-dismissible"><p>Popup deleted successfully.</p></div>';
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
        'name'        => sanitize_text_field( $_POST['name'] ),
        'domain'      => sanitize_text_field( $_POST['domain'] ),
        'logged'      => $_POST['logged'],
        'admin'       => $_POST['admin'],
        'aggressive'  => isset( $_POST['aggressive'] ) ? true : false,
        'delay'       => intval( $_POST['delay'] ),
        'content'     => wp_kses_post( $_POST['content'] ),
        'content_css' => wp_strip_all_tags( $_POST['content_css'], false ),
      );

      if ( isset( $_POST['save_as_new'] ) ) {
        // Add as a new popup
        $popups[] = $popup;
        // Get the new index
        $new_index = array_key_last( $popups );
        $redirect_index = $new_index;
      } elseif ( isset( $_POST['index'] ) && $_POST['index'] !== '' ) {
        // Update existing popup
        $index = intval( $_POST['index'] );
        $popups[ $index ] = $popup;
        $redirect_index = $index;
      } else {
        // Add new popup
        $popups[] = $popup;
        // Get the new index
        $new_index = array_key_last( $popups );
        $redirect_index = $new_index;
      }

      // Save data without applying wp_slash()
      update_option( 'mwpopint_options', $popups );

      // Redirect back to the same popup for continued editing
      wp_redirect( admin_url( 'options-general.php?page=meow-exit-intent&edit=' . $redirect_index . '&message=1' ) );
      exit;
    }
  }
}

new Meow_ExitIntent_Admin();
