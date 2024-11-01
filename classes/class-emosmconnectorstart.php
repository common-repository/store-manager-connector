<?php
/**
 *  This file is part of Store Manager Connector.
 *
 *   Store Manager Connector is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Store Manager Connector is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Mobile Assistant Connector. If not, see <http://www.gnu.org/licenses/>.
 *
 *  author    eMagicOne <contact@emagicone.com>
 *  copyright 2024 eMagicOne
 *  license   http://www.gnu.org/licenses   GNU General Public License
 *
 *  @package eMagicOne Store Manager for WooCommerce
 */

/**
 * Class which register plugin
 */
class EmoSMConnectorStart {
	/** Register plugin
	 *
	 * @param string $plugin_basename The plugin basename.
	 */
	public function __construct( $plugin_basename, $plugin_file_path ) {
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_option_styles_and_javascript' ) );
		add_filter( 'query_vars', array( &$this, 'add_query_vars' ) );
		add_action( 'template_redirect', array( &$this, 'the_template' ) );
		add_action( 'plugins_loaded', array( &$this, 'translations' ) );

		register_activation_hook( $plugin_file_path, array( &$this, 'smconnector_activation' ) );
		register_deactivation_hook( $plugin_file_path, array( &$this, 'smconnector_deactivation' ) );

		add_filter( "plugin_action_links_$plugin_basename", array( &$this, 'setting_link' ) );

	}
	/** Create plugins tables */
	private function create_tables() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Create table `smconnector_session_keys`.
		dbDelta(
			'CREATE TABLE IF NOT EXISTS `smconnector_session_keys` (
					`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
					`session_key` VARCHAR(100) NOT NULL,
					`date_added` DATETIME NOT NULL,
					`last_activity` DATETIME NOT NULL,
					PRIMARY KEY (`id`))'
		);

		// Create table `smconnector_failed_login`.
		dbDelta(
			'CREATE TABLE IF NOT EXISTS `smconnector_failed_login` (
					`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
					`ip` VARCHAR(20) NOT NULL,
					`date_added` DATETIME NOT NULL,
					PRIMARY KEY (`id`))'
		);
	}
	/** Add query vars
	 *
	 * @param array $vars The array of query vars.
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'connector';
		$vars[] = 'task';
		$vars[] = 'category';
		$vars[] = 'include_tables';
		$vars[] = 'sql';
		$vars[] = 'filename';
		$vars[] = 'position';
		$vars[] = 'search_path';
		$vars[] = 'include_subdir';
		$vars[] = 'mask';
		$vars[] = 'ignore_dir';
		$vars[] = 'checksum_sm';
		$vars[] = 'checksum_sm';
		$vars[] = 'hash';
		$vars[] = 'entity_type';
		$vars[] = 'image_id';
		$vars[] = 'key';

		return $vars;
	}
	/** Build template
	 *
	 * @param str $template The plugin template.
	 */
	public function the_template( $template ) {
		global $wp_query;

		if ( ! isset( $wp_query->query['connector'] ) ) {
			return $template;
		}

		if ( 'bridge' == $wp_query->query['connector'] ) {
			new EmoStoreManagerConnector();
			exit;
		}

		return $template;
	}
	/** Store Manager Connector plugin deactivation */
	public function smconnector_deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );
	}
	/** Store Manager Connector plugin activation */
	public function smconnector_activation() {
		global $wpdb;

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
		check_admin_referer( "activate-plugin_{$plugin}" );

		if ( ! ( get_option( 'SMCONNECTOR_OPTIONS' ) ) ) {
			$wpdb->replace(
				$wpdb->options,
				array(
					'option_name' => 'SMCONNECTOR_OPTIONS',
					'option_value' => serialize( emo_smc_get_default_smconnector_options() ),
				)
			);
		}

		$this->create_tables();
	}
	/** Load translations */
	public function translations() {
		load_plugin_textdomain( 'store-manager-connector', false, basename( __DIR__ ) . '/lang' );
	}
	/** Register option styles and javascript */
	public function register_option_styles_and_javascript() {
		global $hook_suffix;

		if ( 'toplevel_page_smconnector' === $hook_suffix ) {
			wp_register_style( 'ema_style', plugins_url( '../assets/css/style.css', __FILE__ ), array(), '1.0' );
			wp_enqueue_style( 'ema_style' );

			wp_register_style( 'tb_style', plugins_url( '../assets/css/tb.css', __FILE__ ), array(), '1.0' );
			wp_enqueue_style( 'tb_style' );

			wp_register_script( 'ema_script', plugins_url( '../assets/js/main.js', __FILE__ ), array(), '1.0' );
			wp_enqueue_script( 'ema_script' );
		}
	}
	/**  Add settings link on plugin page
	 *
	 * @param array $links Array of parameters link.
	 */
	public function setting_link( $links ) {
		$settings_link = '<a href="admin.php?page=smconnector">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}
