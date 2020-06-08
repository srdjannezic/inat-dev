<?php

/*
Author: Srdjan Nezic
*/

function my_wp_is_mobile() {
    static $is_mobile;

    if ( isset($is_mobile) )
        return $is_mobile;

    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif (
        strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ) {
            $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false) {
            $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false) {
        $is_mobile = false;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

if(wp_get_theme() == 'Storefront' || wp_get_theme() == 'Storefront Child') {

    add_action( 'init', 'jk_remove_storefront_header_search' );
    function jk_remove_storefront_header_search() {
        remove_action( 'storefront_header', 'storefront_product_search',    40 );
    }

    add_action( 'init', 'custom_remove_footer_credit', 10 );

    function custom_remove_footer_credit () {
        remove_action( 'storefront_footer', 'storefront_credit', 20 );
        add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
    } 

    function custom_storefront_credit() {
        ?>
        <div class="site-info">
            &copy; <?php echo get_bloginfo( 'name' ) . ' ' . get_the_date( 'Y' ); ?>
        </div><!-- .site-info -->
        <?php
    }
}