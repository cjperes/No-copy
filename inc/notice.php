<?php

function ncjp_admin_notice__success()
{
    if (! PAnD::is_admin_notice_active('disable_ncjp_notice_forever')) {
            return;
    }

    ?>
         <div data-dismissible="disable_ncjp_notice_forever" class="notice notice-success is-dismissible">
            <p><?php _e('Success! Your site is protected against copying content and images, <a href="https://wordpress.org/support/plugin/no-copy-block-text-selection/reviews/#new-post" target="blank">If you like the plugin, consider rating the plugin with 5 stars ⭐⭐⭐⭐⭐, encourages us to add new features in the future!</a>'); ?></p>
        </div>
        <?php
}
    add_action('admin_init', array( 'PAnD', 'init' ));
    add_action('admin_notices', 'ncjp_admin_notice__success'); ?>
