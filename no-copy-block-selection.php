<?php
/**
 * Plugin Name: No copy allowed - Nork Digital
 * Plugin URI: https://github.com/cjperes/no-copy
 * Description: Simples plugin para bloquear copias de texto, print screen e "imprimir" em PDF 
 * Author: Caio Peres
 * Author URI: https://github.com/cjperes
 * Version: 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // tela branca caso o plugin for acessado direto
}

// adicionar função ao footer do wordpress
add_action( 'wp_footer', 'ncjp_footer_scp' );
function ncjp_footer_scp(){
  ?>
  <script language="javascript">

    function NcjpclearData(){
        window.clipboardData.setData('text','')
    }
    function cldata(){
        if(clipboardData){
            clipboardData.NcjpclearData();
        }
    }
    setInterval("cldata();", 1000);


    document.addEventListener("keyup", function (e) {
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
                window.clipboardData.setData('text', "Access   Restricted");
            } catch (err) {
            }
        }
        setInterval("NcjpAccessClipboardData()", 300);

      </script>
      <style type="text/css" media="print">
    body { visibility: hidden; display: none }
</style>


<body ondragstart="return false;" onselectstart="return false;"  oncontextmenu="return false;" onload="NcjpclearData(); " onblur="NcjpclearData(); ">
  <?php
}

