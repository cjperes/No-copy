<?php

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
