<?php

/**
 * Plugin Name: No copy allowed - Nork Digital
 * Plugin URI: https://nork.com.br
 * Description: Simples plugin para bloquear copias de texto, print screen, "imprimir" em PDF e desabilitar teclas
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.3
 */

define('NCJP_OPTIONS', 'ncjp-options-group');

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.php';
add_action('admin_init', array( 'PAnD', 'init' ));

if (!defined('ABSPATH')) {
    exit; // tela branca caso o plugin for acessado direto
}
