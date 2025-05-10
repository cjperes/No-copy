<?php
/**
 * Plugin Name: No copy allowed, disable copy - Nork Tecnologia
 * Plugin URI: https://nork.com.br
 * Description: Plugin to block text copies, print screen, "print/save as" PDF and disable many copy keys
 * Author: Caio Peres
 * Author URI: https://cjperes.github.io
 * Version: 1.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add footer script
add_action('wp_footer', 'ncjp_footer_scp');
function ncjp_footer_scp()
{
    if (is_admin()) return;

    $options = get_option('block_copy_option_name');

    // Whitelist check
    if (!empty($options['whitelist_types']) && is_singular($options['whitelist_types'])) {
        return;
    }

    // Check admin
    if (current_user_can('administrator') && !empty($options['ignore_admins']) && $options['ignore_admins'] === 'yes') {
        return;
    }

    ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  <?php if (!empty($options['block_middle_click'])) : ?>
  document.addEventListener("mousedown", function(e) {
    if (e.button === 1) {
      e.preventDefault();
    }
  });
  document.querySelectorAll("img").forEach(function(img) {
    img.setAttribute("draggable", "false");
  });
  <?php endif; ?>

  <?php if (!empty($options['block_image_select'])) : ?>
  var style = document.createElement("style");
  style.innerHTML = `
                img {
                    -webkit-user-drag: none;
                    user-drag: none;
                    -webkit-user-select: none;
                    user-select: none;
                }`;
  document.head.appendChild(style);
  <?php endif; ?>

  <?php if (!empty($options['block_modern_hotkeys'])) : ?>
  document.addEventListener("keydown", function(e) {
    if ((e.metaKey || e.ctrlKey) && (e.key === "c" || e.key === "u" || e.key === "p" || (e.ctrlKey && e
        .shiftKey && e.key === "I"))) {
      e.preventDefault();
    }
  });
  <?php endif; ?>

  <?php if (!empty($options['block_devtools_detection'])) : ?>
  setInterval(function() {
    var widthThreshold = window.outerWidth - window.innerWidth > 160;
    var heightThreshold = window.outerHeight - window.innerHeight > 160;
    if (widthThreshold || heightThreshold) {
      document.body.innerHTML = "<h1>DevTools Detected - Acesso Bloqueado</h1>";
    }
  }, 1000);
  <?php endif; ?>
  <?php if (!empty($options['block_ctrl_c'])) : ?>
  document.addEventListener("copy", function(e) {
    e.preventDefault();
  });
  <?php endif; ?>

  <?php if (!empty($options['block_right_click'])) : ?>
  document.addEventListener("contextmenu", function(e) {
    e.preventDefault();
  });
  <?php endif; ?>

  <?php if (!empty($options['block_print_screen'])) : ?>
  document.addEventListener("keyup", function(e) {
    if (e.key === "PrintScreen") {
      e.preventDefault();
      var inpFld = document.createElement("input");
      inpFld.setAttribute("value", ".");
      inpFld.style.height = "0px";
      inpFld.style.width = "0px";
      inpFld.style.border = "0px";
      document.body.appendChild(inpFld);
      inpFld.select();
      document.execCommand("copy");
      inpFld.remove();
    }
  });
  <?php endif; ?>
});
</script>

<?php if (!empty($options['block_print_pdf'])) : ?>
<style type="text/css">
@media print {
  body {
    visibility: hidden !important;
    display: none !important;
  }
}
</style>
<?php endif;
}

if (is_admin()) {
    new BlockCopy();
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
        add_options_page('Block copy', 'Block copy', 'manage_options', 'block-copy', array($this, 'block_copy_create_admin_page'));
    }

    public function block_copy_create_admin_page()
    {
        $this->block_copy_options = get_option('block_copy_option_name');
        ?>
<div class="wrap">
  <h1>Settings - No Copy Allowed</h1>
  <form method="post" action="options.php">
    <?php
                settings_fields('block_copy_option_group');
                do_settings_sections('block-copy-admin');
                submit_button();
                ?>
  </form>
  <hr>
  <p>Desenvolvido com ❤️ por <a href="https://nork.com.br" target="_blank">Nork Tecnologia</a></p>
  <p>Se você gostou do plugin, deixe sua avaliação 5 estrelas na <a
      href="https://wordpress.org/plugins/no-copy-block-text-selection/" target="_blank">página oficial do plugin no
      WordPress.org</a>. Seu apoio nos ajuda a continuar melhorando!</p>
</div>
<?php
    }

    public function block_copy_page_init()
    {
        register_setting('block_copy_option_group', 'block_copy_option_name', array($this, 'block_copy_sanitize'));

        add_settings_section('block_copy_setting_section', 'Settings', array($this, 'block_copy_section_info'), 'block-copy-admin');

        add_settings_field('ignore_admins', 'Ignore Admins', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'ignore_admins', 'label' => 'Ignore administrators from copy block functionalities']);
        add_settings_field('block_ctrl_c', 'Block Ctrl+C', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_ctrl_c', 'label' => 'Block copying text (Ctrl+C)']);
        add_settings_field('block_right_click', 'Block Right Click', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_right_click', 'label' => 'Disable right mouse button']);
        add_settings_field('block_print_screen', 'Block PrintScreen', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_print_screen', 'label' => 'Disable PrintScreen']);
        add_settings_field('block_print_pdf', 'Block Print to PDF', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_print_pdf', 'label' => 'Disable printing to PDF']);

        add_settings_field('block_middle_click', 'Block Middle Click & Drag Image', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_middle_click', 'label' => 'Disable middle mouse click and image dragging']);
        add_settings_field('block_image_select', 'Disable Image Selection', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_image_select', 'label' => 'Apply CSS to disable image dragging and selection']);
        add_settings_field('block_modern_hotkeys', 'Block Modern Hotkeys (Cmd, DevTools)', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_modern_hotkeys', 'label' => 'Block Cmd+C/U/P and Ctrl+Shift+I']);
        add_settings_field('block_devtools_detection', 'Detect DevTools Open', array($this, 'checkbox_callback'), 'block-copy-admin', 'block_copy_setting_section', ['id' => 'block_devtools_detection', 'label' => 'Detect when browser dev tools are opened']);

        add_settings_field('whitelist_types', 'Whitelist Post Types', array($this, 'whitelist_callback'), 'block-copy-admin', 'block_copy_setting_section');
    }

    public function block_copy_sanitize($input)
    {
        $sanitary = array();
        foreach (['ignore_admins', 'block_ctrl_c', 'block_right_click', 'block_print_screen', 'block_print_pdf', 'block_middle_click', 'block_image_select', 'block_modern_hotkeys', 'block_devtools_detection'] as $key) {
            $sanitary[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : '';
        }
        $sanitary['whitelist_types'] = isset($input['whitelist_types']) ? array_map('sanitize_text_field', $input['whitelist_types']) : [];
        return $sanitary;
    }

    public function block_copy_section_info()
    {
        echo 'Customize the protection settings for your site.';
    }

    public function checkbox_callback($args)
    {
        $id = $args['id'];
        $label = $args['label'];
        printf(
            '<input type="checkbox" name="block_copy_option_name[%1$s]" value="yes" %2$s> %3$s',
            $id,
            isset($this->block_copy_options[$id]) && $this->block_copy_options[$id] === 'yes' ? 'checked' : '',
            esc_html($label)
        );
    }

    public function whitelist_callback()
    {
        $types = get_post_types(['public' => true], 'objects');
        $selected = isset($this->block_copy_options['whitelist_types']) ? (array)$this->block_copy_options['whitelist_types'] : [];
        foreach ($types as $type) {
            printf(
                '<label><input type="checkbox" name="block_copy_option_name[whitelist_types][]" value="%1$s" %2$s> %3$s</label><br>',
                esc_attr($type->name),
                in_array($type->name, $selected) ? 'checked' : '',
                esc_html($type->labels->singular_name)
            );
        }
    }
}