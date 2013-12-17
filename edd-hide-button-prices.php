<?php
/**
 * Plugin Name:		Easy Digital Downloads - Hide Button Prices
 * Plugin URI:		http://wordpress.org/plugins/easy-digital-downloads-hide-button-prices/
 * Description:		Removes prices from purchase buttons on Easy Digital Downloads
 * Version:			1.0.0
 * Author:			Daniel J Griffiths
 * Author URI:		http://ghost1227.com
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Exit if EDD isn't active
if( !class_exists( 'Easy_Digital_Downloads' ) ) return;

function eddhp_purchase_link_defaults( $defaults ) {
	$defaults['price'] = (bool) false;

	return $defaults;
}
add_filter( 'edd_purchase_link_defaults', 'eddhp_purchase_link_defaults' );
