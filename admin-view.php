<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Display success message
if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
  echo '<div class="notice notice-success is-dismissible"><p>Popup saved successfully.</p></div>';
}

// Existing popups
$popups = get_option( 'mwpopint_options', array() );

// Default settings for new popups
$default_settings = array(
  'admin'      => 'true',
  'aggressive' => true,
);

// Merge default settings for new popups
if ( ! $editing_popup ) {
  $editing_popup = $default_settings;
}
?>

<div class="wrap">
  <h1>
    Meow Exit Intent
    <!-- "New Popup" Button aligned to the right -->
    <a href="<?php echo admin_url( 'options-general.php?page=meow-exit-intent' ); ?>" class="page-title-action" style="float: right;">New Popup</a>
  </h1>

  <?php if ( $editing_popup && isset( $editing_popup['index'] ) ) : ?>
    <h2><?php echo esc_html( 'Edit Popup: ' . $editing_popup['name'] ); ?></h2>
  <?php else : ?>
    <h2>Add New Popup</h2>
  <?php endif; ?>

  <form method="post" action="">
    <?php wp_nonce_field( 'save_meow_exit_intent', 'meow_exit_intent_nonce' ); ?>

    <?php if ( isset( $editing_popup['index'] ) ) : ?>
      <input type="hidden" name="index" value="<?php echo intval( $editing_popup['index'] ); ?>">
    <?php endif; ?>

    <table class="form-table">
      <tr>
        <th scope="row"><label for="name">Name</label></th>
        <td>
          <input name="name" type="text" id="name" value="<?php echo isset( $editing_popup['name'] ) ? esc_attr( $editing_popup['name'] ) : ''; ?>" class="regular-text" required>
          <p class="description">A unique name to identify this popup.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="domain">Domain</label></th>
        <td>
          <?php $current_domain = $_SERVER['HTTP_HOST']; ?>
          <input name="domain" type="text" id="domain" value="<?php echo isset( $editing_popup['domain'] ) ? esc_attr( $editing_popup['domain'] ) : ''; ?>" class="regular-text">
          <p class="description">Specify a domain to display this popup on (e.g., <?php echo esc_html( $current_domain ); ?>). Leave blank for all domains. Wildcard subdomains are supported (e.g., *.<?php echo esc_html( $current_domain ); ?>).
        </p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="logged">Logged-in Users</label></th>
        <td>
          <select name="logged" id="logged">
            <option value="all" <?php selected( isset( $editing_popup['logged'] ) ? $editing_popup['logged'] : 'all', 'all' ); ?>>All Users</option>
            <option value="true" <?php selected( isset( $editing_popup['logged'] ) ? $editing_popup['logged'] : '', 'true' ); ?>>Logged-in Users Only</option>
            <option value="false" <?php selected( isset( $editing_popup['logged'] ) ? $editing_popup['logged'] : '', 'false' ); ?>>Logged-out Users Only</option>
          </select>
          <p class="description">Select which users should see this popup based on their login status.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="admin">Admin Users</label></th>
        <td>
          <select name="admin" id="admin">
            <option value="all" <?php selected( isset( $editing_popup['admin'] ) ? $editing_popup['admin'] : 'all', 'all' ); ?>>All Users</option>
            <option value="true" <?php selected( isset( $editing_popup['admin'] ) ? $editing_popup['admin'] : 'true', 'true' ); ?>>Admins Only</option>
            <option value="false" <?php selected( isset( $editing_popup['admin'] ) ? $editing_popup['admin'] : '', 'false' ); ?>>Non-Admins Only</option>
          </select>
          <p class="description">Choose whether to display this popup to admin users.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="aggressive">Aggressive Mode</label></th>
        <td>
          <input name="aggressive" type="checkbox" id="aggressive" <?php checked( isset( $editing_popup['aggressive'] ) ? $editing_popup['aggressive'] : true ); ?>>
          <p class="description">If checked, the popup will appear on every page load.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="delay">Delay (ms)</label></th>
        <td>
          <input name="delay" type="number" id="delay" value="<?php echo isset( $editing_popup['delay'] ) ? intval( $editing_popup['delay'] ) : 0; ?>" class="small-text" min="0">
          <p class="description">Delay in milliseconds before showing the popup after exit intent is detected.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="content">HTML Content</label></th>
        <td>
          <?php
          $default_html = '
<h2>Don\'t Miss Out!</h2>
<p>Subscribe to our newsletter for the latest updates.</p>
<a href="#" class="meow-button">Subscribe Now</a>
          ';
          $content = isset( $editing_popup['content'] ) ? $editing_popup['content'] : $default_html;
          wp_editor( $content, 'content', array( 'textarea_name' => 'content' ) );
          ?>
          <p class="description">Enter the HTML content for the popup. This will be displayed inside the popup.</p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="content_css">Custom CSS</label></th>
        <td>
          <?php
          $default_css = '
/* Main container for the popup content */
.meow-modal-body {
  font-size: 15px;
  color: black;
}

/* Styling for headings and paragraphs */
.meow-modal-body p, .meow-modal-body h2, .meow-modal-body h3 {
  color: black;
}

.meow-modal-body h2 {
  margin-top: 0px;
  font-size: 28px;
}

/* Styling for buttons */
.meow-button {
  display: block;
  width: 100%;
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
          $content_css = isset( $editing_popup['content_css'] ) ? $editing_popup['content_css'] : $default_css;
          ?>
          <textarea name="content_css" id="content_css" rows="15" class="large-text"><?php echo esc_textarea( $content_css ); ?></textarea>
          <p class="description">Add custom CSS to style the popup content. The main container class is <code>.meow-modal-body</code>.</p>
        </td>
      </tr>
    </table>

    <?php
    if ( isset( $editing_popup['index'] ) ) {
      echo '<p class="submit" style="display: flex; align-items: center;">';
      submit_button( 'Update Popup', 'primary', 'submit', false );
      echo '<span style="margin-left: 5px;">';
      submit_button( 'Save as New Popup', 'secondary', 'save_as_new', false );
      echo '</span></p>';
    } else {
      submit_button( 'Add Popup' );
    }
    ?>
  </form>

  <h2>Existing Popups</h2>
  <table class="widefat fixed" cellspacing="0">
    <thead>
      <tr>
        <th>Name</th>
        <th>Domain</th>
        <th>Logged-in Users</th>
        <th>Admin Users</th>
        <th>Aggressive</th>
        <th>Delay</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ( ! empty( $popups ) ) : ?>
        <?php foreach ( $popups as $index => $popup ) : ?>
          <tr>
            <td><?php echo esc_html( $popup['name'] ); ?></td>
            <td><?php echo esc_html( $popup['domain'] ); ?></td>
            <td><?php echo esc_html( ucfirst( $popup['logged'] ) ); ?></td>
            <td><?php echo esc_html( ucfirst( $popup['admin'] ) ); ?></td>
            <td><?php echo $popup['aggressive'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo intval( $popup['delay'] ); ?> ms</td>
            <td>
              <a href="<?php echo admin_url( 'options-general.php?page=meow-exit-intent&edit=' . $index ); ?>">Edit</a> |
              <a href="<?php echo admin_url( 'options-general.php?page=meow-exit-intent&delete=' . $index ); ?>" onclick="return confirm('Are you sure you want to delete this popup?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="7">No popups found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
