<?php

if (!defined('ABSPATH')) {
    exit; // tela branca caso o plugin for acessado direto
}


//settings
require_once __DIR__ . '\settings.php';


//get abs
function ncjp_get_abs_path()
{
    return WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) . '/';
}


//get url
function ncjp_get_url_path()
{
    return WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/';
}



//inicia com bloqueio ativo
function NCJP_init_options()
{
    add_option(NCJP_OPTIONS);

    $options['active'] = 1;

    update_option(NCJP_OPTIONS, $options);
}

//update na settingspage
function NCJP_update_options($options)
{

    update_option(NCJP_OPTIONS, $options);
}


//pega options da pagina settings
function NCJP_change_options()
{
    $options = get_option(NCJP_OPTIONS);
    if (!$options) {
        NCJP_init_options();
        $options = get_option(NCJP_OPTIONS);
    }
    return $options;
}

//msg sucesso
function NCJP_sucess_msg($msg)
{
    echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';
}
