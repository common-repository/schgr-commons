<?php
/*
  Plugin Name: Sch.gr commons
  Plugin URI: https://wordpress.org/plugins/schgr-commons/
  Description:  Adds oEmbed support in WordPress posts, pages and custom post types for videos from https://video.sch.gr, school location map form maps.sch.gr sites of Greek Schools Network.
  Tags: sch.gr, video.sch.gr, maps.sch.gr
  Version: 4.0
  Requires at least: WordPress  4.6
  Tested up to: WordPress 6.1
  Author:  NTS on CTI.gr, lenasterg
  Author URI:
  Text Domain: schgr-commons
  Domain Path: /languages
  Last Updated: October 24, 2022
  License: GNU/GPL 3
 */

add_action('plugins_loaded', 'sch_gr_commons_i18n_init');

/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @since 1.0
 */
function sch_gr_commons_i18n_init() {
    load_plugin_textdomain('schgr-commons', false, basename(dirname(__FILE__)) . '/languages/');
}

/**
 * Add mmpress.sch.gr support
 * @since 1.0
 */
wp_embed_register_handler('mmpressch', '/mmpres.sch.gr:4000\/(\w+)/i', 'sch_gr_wp_embed_handler_mmpressch');

function sch_gr_wp_embed_handler_mmpressch($matches, $attr, $url, $rawattr) {
    $args = wp_embed_defaults();

    $width = $args['width'];
    if ($width > 660) {
        $width = 660;
    }
    $height = floor($width * 380 / 660);
    $embed = '<div align="center"><iframe  width="' . $width . '" height="' . $height . '" src="' . $url . '/?autostart=false" '
            . 'scrolling="no" frameborder="0" '
            . 'allowtransparency="true" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>'
            . '	<br/><a href="' . $url . '">' . __('Go to mmpres.sch.gr', 'schgr-commons') . '</a></div>';

    return apply_filters('sch_gr_embed_mmpressch', $embed, $matches, $attr, $url, $rawattr);
}

wp_embed_register_handler('videosch', '/http:\/\/vod-new.sch.gr\/asset\/detail\/((\w+)\/(\w+))/', 'sch_gr_wp_embed_handler_vodnew');
wp_embed_register_handler('videosch2', '/http:\/\/video.sch.gr\/asset\/detail\/((\w+)\/(\w+))/', 'sch_gr_wp_embed_handler_vodnew');
wp_embed_register_handler( 'videosch3', '/https:\/\/video.sch.gr\/asset\/detail\/((\w+)\/(\w+))/', 'sch_gr_wp_embed_handler_vodnew' );

/**
 * Add mmpress.sch.gr support
 * @since 1.0
 */
function sch_gr_wp_embed_handler_vodnew($matches, $attr, $url, $rawattr) {
     $args = wp_embed_defaults();

    $url = str_replace( 'vod-new', 'video', $url );
    $width = $args['width'];
	$height = floor( $width * 380 / 660 );
   $embed = '<div align="center"><iframe src="' . sprintf(
		    'https://video.sch.gr/asset/player/%1$s/%2$s', esc_attr( $matches[1] ), esc_attr( $matches[2] ) ) . '" width="' . $width . 'px" '
	    . 'height="' . $height . 'px" scrolling="no" frameborder="0" '
	    . 'allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>'
	    . '	<br/><a href="' . $url . '">' . __('Go to video.sch.gr', 'schgr-commons') . '</a></div>';
    ;

    return apply_filters('sch_gr_wp_embed_vodnew', $embed, $matches, $attr, $url, $rawattr);
}


wp_embed_register_handler( 'mapssch', '#https?://maps.sch.gr/main.html(.*)#i', 'wp_embed_handler_mapssch' );


/**
 * @author lenasterg <stergatu@cti.gr>
 * @version 1.0 7/12/2020
 *
 */
function wp_embed_handler_mapssch( $matches, $attr, $url, $rawattr ) {
	global $content_width;

    $args = wp_parse_args( $attr, wp_embed_defaults() );

   
	if ($args['width']>'550') {
		$width = '550';
	}
	else {
	$width = $args['width'];	
	}
//    $height = $args['height'];
//    $width = get_option( 'embeddedvideo_width' ) - 10;
    $height = floor( $width * 550 / 450 );
    $embed = '<div align="center"><iframe src="' . sprintf(
		    'https://maps.sch.gr/embed.html%1$s', esc_attr( $matches[1] ))  . '" width="' . $width . 'px" '
	    . 'height="' . $height . 'px" scrolling="no" frameborder="0" '
	    . 'allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>'
	    . '<br/><a href="' . $url . '"> '.__('Go to maps.sch.gr', 'schgr-commons') . '</a></div>';
    ;

    return apply_filters( 'embed_mapssch', $embed, $matches, $attr, $url, $rawattr );
}