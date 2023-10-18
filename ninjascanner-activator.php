<?php

/**
 * @wordpress-plugin
 * Plugin Name:       NinjaScanner Activator
 * Plugin URI:        https://github.com/wp-activators/ninjascanner-activator
 * Description:       NinjaScanner Plugin Activator
 * Version:           1.2.0
 * Requires at least: 5.9.0
 * Requires PHP:      7.2
 * Author:            mohamedhk2
 * Author URI:        https://github.com/mohamedhk2
 **/

defined( 'ABSPATH' ) || exit;
$NINJA_SCANNER_ACTIVATOR_NAME   = 'NinjaScanner Activator';
$NINJA_SCANNER_ACTIVATOR_DOMAIN = 'ninjascanner-activator';
$functions                      = require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
extract( $functions );
if (
	$activator_admin_notice_ignored()
	|| $activator_admin_notice_plugin_install( 'ninjascanner/index.php', 'ninjascanner', 'NinjaScanner', $NINJA_SCANNER_ACTIVATOR_NAME, $NINJA_SCANNER_ACTIVATOR_DOMAIN )
	|| $activator_admin_notice_plugin_activate( 'ninjascanner/index.php', $NINJA_SCANNER_ACTIVATOR_NAME, $NINJA_SCANNER_ACTIVATOR_DOMAIN )
) {
	return;
}
$nscan_options = get_option( 'nscan_options' );
if ( $nscan_options ) {
	$nscan_options['exp']   = date( 'Y-m-d', strtotime( '+1000 year' ) );
	$nscan_options  ['key'] = 'free4all-free4all-free4all-free4all-free4all';
	update_option( 'nscan_options', $nscan_options );
}
add_filter( 'pre_http_request', function ( $pre, $parsed_args, $url ) use ( $activator_json_response ) {
	$target = defined( 'NSCAN_SIGNATURES_URL' ) ? NSCAN_SIGNATURES_URL : 'https://ninjascanner.nintechnet.com/index.php';
	switch ( $url ) {
		case $target:
			switch ( $parsed_args['body']['action'] ?? null ) {
				case 'check_license':
					return $activator_json_response( [
						'checked' => true,
						'exp'     => date( 'Y-m-d', strtotime( '+1000 year' ) ),
					] );
			}
			break;
	}

	return $pre;
}, 99, 3 );
