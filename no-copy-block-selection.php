<?php

/**
 * Plugin Name: No copy allowed - Nork Digital
 * Plugin URI: https://nork.digital
 * Description: Simples plugin para bloquear copias de texto, print screen, "imprimir" em PDF e desabilitar teclas
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.2
 */


if (!defined('ABSPATH')) {
    exit; // tela branca caso o plugin for acessado direto
}

// adicionar função ao footer do wordpress
add_action('wp_footer', 'ncjp_footer_scp');
function ncjp_footer_scp()
{
?>
    <script language="javascript">
        function NcjpclearData() {
            window.clipboardData.setData('text', '')
        }

        function cldata() {
            if (clipboardData) {
                clipboardData.NcjpclearData();
            }
        }
        setInterval("cldata();", 1000);


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
            inpFld.setAttribute("width", "0");
            inpFld.style.height = "0px";
            inpFld.style.width = "0px";
            inpFld.style.border = "0px";
            document.body.appendChild(inpFld);
            inpFld.select();
            document.execCommand("copy");
            inpFld.remove(inpFld);
        }

        function NcjpAccessClipboardData() {
            try {
                window.clipboardData.setData('text', "Conteúdo protegido por direitos autorais.");
            } catch (err) {}
        }
        setInterval("NcjpAccessClipboardData()", 300);
    </script>

    <style type="text/css" media="print">
        body {
            visibility: hidden !important;
            display: none !important;
            -moz-appearance: none;
            -webkit-appearance: none;
        }
    </style>

    <script language="javascript">
        //desabilita ctrl + s; ctrl + u; ctrl+v; ctr+c; ctrl+p;
        document.onkeydown = function(e) {
            if (e.ctrlKey &&
                (e.keyCode === 67 ||
                    e.keyCode === 86 ||
                    e.keyCode === 85 ||
                    e.keyCode === 80 ||
                    e.keyCode === 83 ||
                    e.keyCode === 117)) {
                return false;
            } else {
                return true;
            }
        };
    </script>


    <body ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;" onload="NcjpclearData(); " onblur="NcjpclearData(); ">
    <?php
}

function ncjp_admin_notice__success()
{
    ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Success! Your site is protected against copying content and images, <a href="https://wordpress.org/support/plugin/no-copy-block-text-selection/reviews/#new-post" target="blank">If you like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the future!</a>'); ?></p>
        </div>
    <?php
}
add_action('admin_notices', 'ncjp_admin_notice__success');
    ?>

    <?php
    class MySettingsPage
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
        }

        /**
         * Add options page
         */
        public function add_plugin_page()
        {
            // This page will be under "Settings"
            add_options_page(
                'Settings Admin',
                'Copy Protect',
                'manage_options',
                'my-setting-admin',
                array($this, 'create_admin_page')
            );
        }

        /**
         * Options page callback
         */
        public function create_admin_page()
        {
            // Set class property
            $this->options = get_option('my_option_name');
    ?>
            <div class="wrap">
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
                    <li>✔ Disable Ctrl+v</li>
                </ul>
                <p><a href="https://wordpress.org/support/plugin/no-copy-block-text-selection/reviews/#new-post" target="blank">If you like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the future!</a></p>
            </div>
    <?php
        }

        /**
         * Register and add settings
         */
        public function page_init()
        {
            register_setting(
                'my_option_group', // Option group
                'my_option_name', // Option name
                array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                'setting_section_id', // ID
                'Copy protect', // Title
                array($this, 'print_section_info'), // Callback
                'my-setting-admin' // Page
            );

            add_settings_field(
                'id_number', // ID
                'ID Number', // Title 
                array($this, 'id_number_callback'), // Callback
                'my-setting-admin', // Page
                'setting_section_id' // Section           
            );

            add_settings_field(
                'title',
                'Title',
                array($this, 'title_callback'),
                'my-setting-admin',
                'setting_section_id'
            );
        }
    }

    if (is_admin())
        $my_settings_page = new MySettingsPage();
