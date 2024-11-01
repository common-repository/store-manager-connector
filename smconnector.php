<?php
/**
 * Plugin Name: eMagicOne Store Manager for WooCommerce
 * Plugin URI: https://emagicone.com/products/store-manager-for-woocommerce/
 * Description: Store Manager for WooCommerce by eMagicOne is a plugin, intended to connect Store Manager desktop software to your WooCommerce store.
 * Author: eMagicOne
 * Author URI: https://emagicone.com/
 * Version: 1.2.3
 * Text Domain: store-manager-connector
 * Domain Path: /i18n
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @package eMagicOne Store Manager for WooCommerce
 */

/*
-----------------------------------------------------------------------------+
| eMagicOne                                                                    |
| Copyright (c) 2024 eMagicOne.com <contact@emagicone.com>		               |
| All rights reserved                                                          |
+------------------------------------------------------------------------------+
|                                                                              |
| eMagicOne Store Manager for WooCommerce	                                   |
|                                                                              |
| Developed by eMagicOne,                                                      |
| Copyright (c) 2024                                            	           |
+------------------------------------------------------------------------------+
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EMO_SMC_CRYPT_KEY', "EMO_smconnector\0\0\0\0\0\0\0\0\0" );
define( 'EMO_SMC_DEFAULT_LOGIN', '1' );
define( 'EMO_SMC_DEFAULT_PASSWORD', '1' );
// define('EMO_SMC_DEFAULT_TMP_DIR', '/wp-content/plugins/store-manager-connector/tmp');.
define( 'EMO_SMC_DEFAULT_ALLOW_COMPRESSION', 1 );
define( 'EMO_SMC_DEFAULT_USE_PLUGIN_TMP_DIR', 0 );
define( 'EMO_SMC_DEFAULT_COMPRESS_LEVEL', 6 );        // 1 - 9.
define( 'EMO_SMC_DEFAULT_LIMIT_QUERY_SIZE', 8192 );   // kB.
define( 'EMO_SMC_DEFAULT_PACKAGE_SIZE', 1024 );       // kB.
define( 'EMO_SMC_DEFAULT_NOTIFICATION_EMAIL', '' );
define( 'EMO_SMC_DEFAULT_ALLOWED_IPS', '' );
define( 'EMO_SMC_MIN_COMPRESS_LEVEL', 1 );
define( 'EMO_SMC_MAX_COMPRESS_LEVEL', 9 );
define( 'EMO_SMC_MIN_LIMIT_QUERY_SIZE', 100 );    // kB.
define( 'EMO_SMC_MAX_LIMIT_QUERY_SIZE', 100000 ); // kB.
define( 'EMO_SMC_MIN_PACKAGE_SIZE', 100 );        // kB.
define( 'EMO_SMC_MAX_PACKAGE_SIZE', 30000 );      // kB.

/** Get tmp path */
function emo_smc_get_tmp_path() {
	$emo_plugin_path = plugin_dir_path( __FILE__ );
	if ( strpos( $emo_plugin_path, ABSPATH ) !== false ) {
		$emo_tmp_dir = substr( $emo_plugin_path, strlen( ABSPATH ) - 1 ) . 'tmp';
	} else {
		$emo_tmp_dir = ABSPATH . 'tmp';
	}

	return $emo_tmp_dir;
}

/** Get plugin tmp path */
function emo_smc_get_plugin_tmp_path() {
	return plugin_dir_path( __FILE__ ) . 'tmp';
}

/** Get encrypted password
 *
 * @param string $data Decrypted password.
 */
function emo_smc_get_encrypted_password( $data ) {
	return call_user_func(
		'base64_encode',
		openssl_encrypt(
			$data,
			'aes-192-ecb',
			EMO_SMC_CRYPT_KEY,
			OPENSSL_RAW_DATA
		)
	);
}

/** Get decrypted password
 *
 * @param string $data Encrypted password.
 */
function emo_smc_get_decrypted_password( $data ) {
	return trim(
		preg_replace(
			'/(^\s+)|(\s+$)/us',
			'',
			openssl_decrypt(
				call_user_func(
					'base64_decode',
					$data
				),
				'aes-192-ecb',
				EMO_SMC_CRYPT_KEY,
				OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
			)
		),
		"\x00..\x1F"
	);
}

/** Get default excluded tables */
function emo_smc_get_default_excluded_tables() {
	$table_excluded = array();
	return $table_excluded;
}

/** Get default Store Manager Connector options */
function emo_smc_get_default_smconnector_options() {
	$option_values = array(
		'login'              => EMO_SMC_DEFAULT_LOGIN,
		'password'           => emo_smc_get_encrypted_password( EMO_SMC_DEFAULT_PASSWORD ),
		'smconnector_hash'   => md5( EMO_SMC_DEFAULT_LOGIN . EMO_SMC_DEFAULT_PASSWORD ),
		'tmp_dir'            => emo_smc_get_tmp_path(),
		'use_plugin_tmp_dir' => EMO_SMC_DEFAULT_USE_PLUGIN_TMP_DIR,
		'allow_compression'  => EMO_SMC_DEFAULT_ALLOW_COMPRESSION,
		'compress_level'     => EMO_SMC_DEFAULT_COMPRESS_LEVEL,
		'limit_query_size'   => EMO_SMC_DEFAULT_LIMIT_QUERY_SIZE,
		'package_size'       => EMO_SMC_DEFAULT_PACKAGE_SIZE,
		'exclude_db_tables'  => implode( ';', emo_smc_get_default_excluded_tables() ),
		'notification_email' => EMO_SMC_DEFAULT_NOTIFICATION_EMAIL,
		'allowed_ips'        => EMO_SMC_DEFAULT_ALLOWED_IPS,
	);

	return $option_values;
}

/** Check if WooCommerce is activated */
function emo_smc_check_is_woocommerce_activated() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	$plugin_status = false;
	$separator = substr( __DIR__, -24, 1 );
	$path = str_replace( $separator . 'store-manager-connector', '', __DIR__ );
	$results = scandir( $path, SCANDIR_SORT_ASCENDING );
	foreach ( $results as $result ) {
		if ( '.' === $result || '..' === $result ) {
			continue;
		}

		$woocommerce_plugin_path = "$path/$result";
		if ( is_dir( $woocommerce_plugin_path )
			&& (bool) preg_match( '/^woocommerce((\-\d+)?(\.\d+)?(\.\d+)?)$/', $result ) !== false
			&& file_exists( "$woocommerce_plugin_path/woocommerce.php" )
			&& ( is_plugin_active( "$result/woocommerce.php" )
				|| in_array(
					"$result/woocommerce.php",
					apply_filters(
						'active_plugins',
						get_option( 'active_plugins' )
					),
					true
				)
				)
		) {
			$plugin_status = true;
			break;
		}
	}

	return $plugin_status;
}

/**
 * Check if WooCommerce is active
 */
if ( emo_smc_check_is_woocommerce_activated() ) {
	if ( ! class_exists( 'EmoSMConnectorStart' ) ) {
		include_once plugin_dir_path( __FILE__ ) . 'classes/class-emosmconnectorstart.php';
		include_once plugin_dir_path( __FILE__ ) . 'classes/class-emosmconnectorconnectorsettingspage.php';
		include_once plugin_dir_path( __FILE__ ) . 'classes/class-emosmcwoocommerceoverrider.php';
		$plugin_basename = plugin_basename( __FILE__ );
        $plugin_file_path = __FILE__;
		$GLOBALS['EmoSMConnectorStart'] = new EmoSMConnectorStart( $plugin_basename, $plugin_file_path );
	}
} else {
	add_action( 'admin_notices', 'emo_smc_admin_notices' );
}

/** Store Manager Connector Plugin notice during the plugin activation*/
function emo_smc_admin_notices() {
	echo '<div id="notice" class="error"><p>';
	echo '<b> eMagicOne Store Manager for WooCommerce </b> add-on requires <a href="https://woocommerce.com/"> WooCommerce </a> plugin. Please install and activate it.';
	echo '</p></div>', "\n";
}
