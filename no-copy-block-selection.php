<?php

/**
 * Plugin Name: No copy allowed, disable copy - Nork Tecnologia
 * Plugin URI: https://nork.com.br
 * Description: Plugin to block text copies, print screen, "print/save as" PDF and disable many copy keys
 * Author: Caio Peres
 * Author URI: https://cjperes.github.io
 * Version: 1.0.6
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add footer script only if not admin and if block copy is enabled
add_action('wp_footer', 'ncjp_footer_scp');
function ncjp_footer_scp()
{
    // Check if current user is admin and if the option to block copy for admins is disabled
    $block_copy_options = get_option('block_copy_option_name');
    if (current_user_can('administrator') && isset($block_copy_options['ignore_admins']) && $block_copy_options['ignore_admins'] == 'yes') {
        return;
    }
    ?>
<script language="javascript">
document.addEventListener("keyup", function(e) {
  var keyCode = e.keyCode ? e.keyCode : e.which;
  if (keyCode == 44) {
    e.preventDefault();
    NcjpstopPrntScr();
  }
});

function NcjpstopPrntScr() {
  var inpFld = document.createElement("input");
  inpFld.setAttribute("value", ".");
  inpFld.style.height = "0px";
  inpFld.style.width = "0px";
  inpFld.style.border = "0px";
  document.body.appendChild(inpFld);
  inpFld.select();
  document.execCommand("copy");
  inpFld.remove(inpFld);
}

document.onkeydown = function(e) {
  if (e.ctrlKey &&
    (e.keyCode === 67 || // Ctrl + C
      e.keyCode === 85 || // Ctrl + U
      e.keyCode === 80 || // Ctrl + P
      e.keyCode === 83 || // Ctrl + S
      e.keyCode === 86)) { // Ctrl + V
    return false;
  } else {
    return true;
  }
};
</script>

<style type="text/css">
@media print {
  body {
    visibility: hidden !important;
    display: none !important;
  }
}
</style>

<body ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;"></body>
    <?php
}

if (is_admin()) {
    $block_copy = new BlockCopy();
}

class BlockCopy
{
    private $block_copy_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'block_copy_add_plugin_page'));
        add_action('admin_init', array($this, 'block_copy_page_init'));
    }

    public function block_copy_add_plugin_page()
    {
        add_options_page(
            'Block copy',
            'Block copy',
            'manage_options',
            'block-copy',
            array($this, 'block_copy_create_admin_page')
        );
    }

    public function block_copy_create_admin_page()
    {
        $this->block_copy_options = get_option('block_copy_option_name'); ?>
<div class="wrap">
  <h1>Settings - No Copy Allowed</h1>
  <form method="post" action="options.php">
        <?php
              settings_fields('block_copy_option_group');
              do_settings_sections('block-copy-admin');
              submit_button();
        ?>
  </form>

  <h1>Your site is protected!</h1>
  <ul>
    <li>✔ Disable copy of text</li>
    <li>✔ Disable right mouse click</li>
    <li>✔ Disable print screen</li>
    <li>✔ Disable print to PDF</li>
    <li>✔ Disable Ctrl+U (view source)</li>
    <li>✔ Disable Ctrl+P (Print)</li>
    <li>✔ Disable Print with printer</li>
    <li>✔ Disable Ctrl+S (Save page)</li>
    <li>✔ Disable Ctrl+C</li>
  </ul>
  <p><a href="https://wordpress.org/support/plugin/no-copy-block-text-selection/reviews/#new-post" target="blank">If you
      like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the
      future!</a></p>
</div>
    <?php }

    public function block_copy_page_init()
    {
        register_setting(
            'block_copy_option_group', // Option group
            'block_copy_option_name', // Option name
            array($this, 'block_copy_sanitize') // Sanitize
        );

        add_settings_section(
            'block_copy_setting_section', // ID
            'Settings', // Title
            array($this, 'block_copy_section_info'), // Callback
            'block-copy-admin' // Page
        );

        add_settings_field(
            'ignore_admins', // ID
            'Ignore Admins', // Title
            array($this, 'ignore_admins_callback'), // Callback
            'block-copy-admin', // Page
            'block_copy_setting_section' // Section
        );
    }

    public function block_copy_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['ignore_admins'])) {
            $sanitary_values['ignore_admins'] = sanitize_text_field($input['ignore_admins']);
        }
        return $sanitary_values;
    }

    public function block_copy_section_info()
    {
        echo 'Settings for blocking copy functionalities.';
    }

    public function ignore_admins_callback()
    {
        printf(
            '<input type="checkbox" name="block_copy_option_name[ignore_admins]" id="ignore_admins" value="yes" %s> Ignore administrators from copy block functionalities',
            isset($this->block_copy_options['ignore_admins']) && $this->block_copy_options['ignore_admins'] === 'yes' ? 'checked' : ''
        );
    }
}
