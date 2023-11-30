<?php
/**
 * @wordpress-plugin
 * Plugin Name:       NinjaScanner Activ@tor
 * Plugin URI:        https://bit.ly/njsc-act
 * Description:       NinjaScanner Plugin Activ@tor
 * Version:           1.3.0
 * Requires at least: 5.9.0
 * Requires PHP:      7.2
 * Author:            moh@medhk2
 * Author URI:        https://bit.ly/medhk2
 **/

defined( 'ABSPATH' ) || exit;
$PLUGIN_NAME   = 'NinjaScanner Activ@tor';
$PLUGIN_DOMAIN = 'ninjascanner-activ@tor';
extract( require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php' );
if (
	$admin_notice_ignored()
	|| $admin_notice_plugin_install( 'ninjascanner/index.php', 'ninjascanner', 'NinjaScanner', $PLUGIN_NAME, $PLUGIN_DOMAIN )
	|| $admin_notice_plugin_activate( 'ninjascanner/index.php', $PLUGIN_NAME, $PLUGIN_DOMAIN )
) {
	return;
}
$nscan_options = get_option( 'nscan_options' );
if ( $nscan_options ) {
	$nscan_options['exp']   = date( 'Y-m-d', strtotime( '+1000 year' ) );
	$nscan_options  ['key'] = 'free4all-free4all-free4all-free4all-free4all';
	update_option( 'nscan_options', $nscan_options );
}
add_filter( 'pre_http_request', function ( $pre, $parsed_args, $url ) use ( $json_response ) {
	$target = defined( 'NSCAN_SIGNATURES_URL' ) ? NSCAN_SIGNATURES_URL : 'https://ninjascanner.nintechnet.com/index.php';
	switch ( $url ) {
		case $target:
			switch ( $parsed_args['body']['action'] ?? null ) {
				case 'check_license':
					return $json_response( [
						'checked' => true,
						'exp'     => date( 'Y-m-d', strtotime( '+1000 year' ) ),
					] );
			}
			break;
	}

	return $pre;
}, 99, 3 );
