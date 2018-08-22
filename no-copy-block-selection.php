<?php
/**
 * Plugin Name: No copy block text selection
 * Plugin URI: https://github.com/cjperes/no-copy
 * Description: Simple plugin for turning off copies on a website
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // tela branca caso o plugin for acessado direto
}

// adicionar função ao footer do wordpress
add_action( 'wp_footer', 'my_footer_scripts' );
function my_footer_scripts(){
  ?>
  <script language="javascript">
    function clearData(){
        window.clipboardData.setData('text','') 
    }
    function cldata(){
        if(clipboardData){
            clipboardData.clearData();
        }
    }
    setInterval("cldata();", 1000);
</script>


<body ondragstart="return false;" onselectstart="return false;"  oncontextmenu="return false;" onload="clearData();" onblur="clearData();">
  <?php
}

