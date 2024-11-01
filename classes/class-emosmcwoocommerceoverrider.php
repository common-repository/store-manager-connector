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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EMO_SMC_MODULE_NAME', 'store-manager-connector' );
define( 'EMO_SMC_OPTIONS_NAME', 'SMCONNECTOR_OPTIONS' );

require_once plugin_dir_path( __FILE__ ) . 'class-emosmconnectorcommon.php';
require_once plugin_dir_path( __FILE__ ) . 'class-emosmconnectorcore.php';
require_once plugin_dir_path( __FILE__ ) . 'class-emostoremanagerconnector.php';

/**
 * Class which runs all needed methods
 */
class EmoSMCWoocommerceOverrider extends EmoSMConnectorCore {
	/** Run the EmoSMCWoocommerceOverrider object
	 *
	 * @param string $module_name The module name.
	 * @param string $options_name The oprions name.
	 */
	public function __construct( $module_name, $options_name ) {
		$this->module_name = $module_name;
		$this->options_name = $options_name;
	}
	/** Get base upload directory */
	private static function get_base_upload_directory() {
		$data = wp_get_upload_dir();

		return $data['basedir'];
	}
	/** Get image sizes array */
	private static function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array(
				'width' => '',
				'height' => '',
				'crop' => false,
			);

			if ( isset( $_wp_additional_image_sizes[ $s ]['width'] ) ) {
				$sizes[ $s ]['width'] = (int) $_wp_additional_image_sizes[ $s ]['width']; // For theme-added sizes.
			} else {
				$sizes[ $s ]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options.
			}

			if ( isset( $_wp_additional_image_sizes[ $s ]['height'] ) ) {
				$sizes[ $s ]['height'] = (int) $_wp_additional_image_sizes[ $s ]['height']; // For theme-added sizes.
			} else {
				$sizes[ $s ]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options.
			}

			if ( isset( $_wp_additional_image_sizes[ $s ]['crop'] ) ) {
				$sizes[ $s ]['crop'] = $_wp_additional_image_sizes[ $s ]['crop']; // For theme-added sizes.
			} else {
				$sizes[ $s ]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options.
			}
		}

		return $sizes;
	}
	/** Check if the plugin is enabled */
	public function is_module_enabled() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'store-manager-connector/smconnector.php' )
			|| in_array(
				'store-manager-connector/smconnector.php',
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
				true
			)
		) {
			$plugin_status = true;
		}

		return $plugin_status;
	}
	/** Get the connector configuration options */
	public function get_sm_connector_options() {
		return get_option( $this->options_name );
	}
	/** Get the root shop directory */
	public function get_shop_root_dir() {
		return $this->sub_str( ABSPATH, -1 ) == '/' ? ABSPATH : ABSPATH . '/';
	}
	/** Get the database host */
	public function get_db_host() {
		global $wpdb;

		return $wpdb->dbhost;
	}
	/** Get the database name */
	public function get_db_name() {
		global $wpdb;

		return $wpdb->dbname;
	}
	/** Get the database username */
	public function get_db_username() {
		global $wpdb;

		return $wpdb->dbuser;
	}
	/** Get the database password */
	public function get_db_password() {
		global $wpdb;

		return $wpdb->dbpassword;
	}
	/** Get the database prefix */
	public function get_db_prefix() {
		global $wpdb;

		return $wpdb->prefix;
	}
	/** Get sql results
	 *
	 * @param string $sql The sql string.
	 * @param int    $type The assoc type.
	 * @throws Exception If get sql result is invalid.
	 */
	public function get_sql_results( $sql, $type = self::ASSOC ) {
		global $wpdb;

		try {
			if ( self::ASSOC == $type ) {
				$result = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			} else {
				$result = $wpdb->get_results( $sql, ARRAY_N ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

			if ( $wpdb->last_error ) {
				throw new Exception( 'Error' );
			}
		} catch ( Exception $e ) {
			// $this->error_no = @mysqli_errno($wpdb->dbh);
			$this->error_msg = $wpdb->last_error;

			return false;
		}

		return $result;
	}
	/** Execude the sql
	 *
	 * @param string $sql The sql to execude.
	 * @param bool   $reconnect The reconnect parameter.
	 * @throws Exception If execute is invalid.
	 */
	public function exec_sql( $sql, $reconnect = false ) {
		global $wpdb;

		$result = true;

		if ( ! $this->is_sql_encoded_in_base64 ) {
			$sql = $this->strip_slashes( $this->strip_slashes( $sql ) );
		}

		if ( $reconnect ) {
			$wpdb->db_connect();
		}

		try {
			if ( false === $wpdb->query( $sql ) ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				throw new Exception( 'Error' );
			}
		} catch ( Exception $e ) {
			// $this->error_no = @mysqli_errno($wpdb->dbh);
			$this->error_msg = $wpdb->last_error;
			$result = false;
		}

		return $result;
	}
	/** Sanitize sql
	 *
	 * @param string $sql The sql string to sanitize.
	 */
	public function sanitize_sql( $sql ) {
		global $wpdb;

		$sql = $wpdb->_real_escape( $sql );

		if ( method_exists( $wpdb, 'remove_placeholder_escape' ) ) {
			$sql = $wpdb->remove_placeholder_escape( $sql );
		}

		return $sql;
	}
	/** Check if is set the request parameter
	 *
	 * @param string $param The parameter.
	 */
	public function isset_request_param( $param ) {
		return isset( $_REQUEST[ $param ] );
	}
	/** Get parameters from request
	 *
	 * @param string $param The parameter.
	 */
	public function get_request_param( $param ) {
		if ( isset( $_REQUEST[ $param ] ) ) {
			if ( 'image_url' === $param ) {
				return wp_kses( wp_unslash( $_REQUEST[ $param ] ), 'entities' );
			}
			return wp_kses( wp_unslash( $_REQUEST[ $param ] ), 'entities' );
		}

		return '';
	}
	/** Get the store link
	 *
	 * @param bool $ssl The ssl parameter.
	 */
	public function get_store_link( $ssl = false ) {
		return get_site_url();
	}
	/** Run the indexer */
	public function run_indexer() {
		return false;
	}
	/** Get version of shopping cart */
	public function get_cart_version() {
		global $woocommerce;

		return json_encode( array( 'cart_version' => $woocommerce->version ) );
	}
	/** Get the image
	 *
	 * @param string $entity_type The type of image.
	 * @param int    $image_id The image id.
	 */
	public function get_image( $entity_type, $image_id ) {
		$upload_dir = wp_upload_dir();
		$image_path = preg_replace( '#/+#', '/', $upload_dir['basedir'] . '/' . $image_id );
		return $image_path;
	}
	/** Upload the image
	 *
	 * @param string $entity_type The type of entity.
	 * @param int    $image_id The image id.
	 * @param string $img The image.
	 * @param string $type The type of image.
	 */
	public function set_image( $entity_type, $image_id, $img, $type ) {
		// Make thumbnails and other intermediate sizes.
		global $_wp_additional_image_sizes;

		$image_id = ltrim( $image_id, '\\/' );
		$img_file = self::get_base_upload_directory() . "/$image_id";
		$dir_name = dirname( $img_file );

		if ( ! is_dir( $dir_name ) && ! mkdir( $dir_name, 0777, true ) ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'Could not create directory',
					)
				)
			);
		}

		if ( self::IMAGE_URL == $type ) {
			$is_content_written = file_put_contents( $img_file, $this->file_get_contents( $img ) );
			if ( ! $is_content_written ) {
				$context = stream_context_create(
					array(
						'http' => array(
							'header' => 'User-Agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.6 (KHTML, like Gecko) Chrome/16.0.897.0 Safari/535.6',
						),
					)
				);
				$is_content_written = file_put_contents( $img_file, file_get_contents( $img, false, $context ) );
			}
		} else {
			if (isset( $_FILES[ $img ]['tmp_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$tmp_name = sanitize_textarea_field( $_FILES[ $img ]['tmp_name'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$is_content_written = move_uploaded_file( $tmp_name, $img_file );
			}
		}

		if ( ! $is_content_written ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'Could not create file',
					)
				)
			);
		}

		$res                = false;
		$metadata           = array();
		$imagesize          = getimagesize( $img_file );
		$metadata['width']  = $imagesize[0];
		$metadata['height'] = $imagesize[1];

		// Make the file path relative to the upload dir.
		$metadata['file'] = _wp_relative_upload_path( $img_file );

		$sizes = apply_filters( 'intermediate_image_sizes_advanced', self::get_image_sizes(), $metadata );

		if ( $sizes ) {
			$editor = wp_get_image_editor( $img_file );

			if ( ! is_wp_error( $editor ) ) {
				$res = $editor->multi_resize( $sizes );
			}
		}

		if ( ! $res ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'Unable to resize one or more of your pictures',
					)
				)
			);
		}

		die(
			json_encode(
				array(
					self::CODE_RESPONSE => self::SUCCESSFUL,
					self::KEY_MESSAGE   => 'Upload and resize of images has been executed successfully',
				)
			)
		);
	}
	/** Get image directory
	 *
	 * @param string $type The image type.
	 */
	public function get_image_dir( $type ) {
		return self::get_base_upload_directory();
	}
	/** Delete image
	 *
	 * @param string $entity_type The image typee.
	 * @param string $image_id The image id.
	 */
	public function delete_image( $entity_type, $image_id ) {
		$image_id = ltrim( $image_id, '\\/' );
		$this->delete_file( self::get_base_upload_directory() . "/$image_id" );
	}
	/** Delete file
	 *
	 * @param string $filepath The filepath to file.
	 */
	public function delete_file( $filepath ) {
		if ( ! file_exists( $filepath ) ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'File is missing on server',
					)
				)
			);
		}

		if ( unlink( $filepath ) ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::SUCCESSFUL ),
						esc_html( self::KEY_MESSAGE )   => 'File was deleted from FTP Server successfully',
					)
				)
			);
		}

		die(
			json_encode(
				array(
					self::CODE_RESPONSE => self::ERROR_CODE_COMMON,
					self::KEY_MESSAGE => 'File was not deleted from FTP Server',
				)
			)
		);
	}
	/** Copy image
	 *
	 * @param string $entity_type The type of image.
	 * @param string $from_image_id The from image id.
	 * @param string $to_image_id The to image id.
	 */
	public function copy_image( $entity_type, $from_image_id, $to_image_id ) {
		return false;
	}
	/** Get the file
	 *
	 * @param string $folder The folder of the file.
	 * @param string $filename The filename.
	 */
	public function get_file( $folder, $filename ) {
		$folder   = trim( $folder, '/' );
		$filename = ltrim( $filename, '/' );
		if ( empty( $folder ) ) {
			return $this->get_shop_root_dir() . $filename;
		}

		return $this->get_shop_root_dir() . "$folder/$filename";
	}
	/** Upload the file
	 *
	 * @param string $folder The folder path.
	 * @param string $filename The filename to upload.
	 * @param string $file The the file to upload.
	 */
	public function set_file( $folder, $filename, $file ) {
		$folder           = trim( $folder, '\\/' );
		$filename         = ltrim( $filename, '\\/' );
		$destination_path = $this->get_shop_root_dir() . "$folder/$filename";
		$dir_name         = dirname( $destination_path );

		if ( ! is_dir( $dir_name ) && ! mkdir( $dir_name, 0777, true ) ) {
			die(
				json_encode(
					array(
						self::CODE_RESPONSE => self::ERROR_CODE_COMMON,
						self::KEY_MESSAGE   => 'Could not create directory',
					)
				)
			);
		}

		if ( isset( $_FILES[ $file ]['tmp_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$tmp_name = sanitize_text_field( $_FILES[ $file ]['tmp_name'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$result = move_uploaded_file( $tmp_name, $destination_path );
		}

		if ( $result ) {
			die(
				json_encode(
					array(
						self::CODE_RESPONSE => self::SUCCESSFUL,
						self::KEY_MESSAGE   => 'File was successfully uploaded',
					)
				)
			);
		} else {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'File was not uploaded',
					)
				)
			);
		}
	}
	/** Check data changes
	 *
	 * @param array $tables_arr The array of changes.
	 */
	public function check_data_changes( $tables_arr = array() ) {
		if ( ! $tables_arr ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'Parameter tables is empty',
					)
				)
			);
		}

		$arr_result = array();

		foreach ( $tables_arr as $table ) {
			$table = trim( $table );

			if ( empty( $table ) ) {
				continue;
			}

			try {
				$query = "SELECT `AUTO_INCREMENT` AS 'auto_increment'
					FROM INFORMATION_SCHEMA.TABLES
					WHERE TABLE_SCHEMA = '" . $this->get_db_name() . "'
					AND TABLE_NAME = '$table'";
				$result = $this->get_sql_results( $query );

				if ( false === $result ) {
					continue;
				}

				if ( null !== $result[0]['auto_increment'] ) {
					$arr_result[ $table ] = $result[0]['auto_increment'] - 1;
				} else {
					$query = "SELECT `COLUMN_NAME` AS 'primary_key' INTO @primary_key_field
						FROM `information_schema`.`COLUMNS`
						WHERE (`TABLE_SCHEMA` = '" . $this->get_db_name() . "') AND (`TABLE_NAME` = '$table')
						AND (`COLUMN_KEY` = 'PRI')";
					$this->exec_sql( $query );
					$query = "SET @s = CONCAT('SELECT MAX(', @primary_key_field, ') AS max_id FROM $table')";
					$this->exec_sql( $query );
					$query = 'PREPARE stmt FROM @s';
					$this->exec_sql( $query );
					$query = 'EXECUTE stmt;';
					$result = $this->exec_sql( $query );
					$arr_result[ $table ] = $result[0]['max_id'];
				}
			} catch ( Exception $e ) {
				die(
					json_encode(
						array(
							esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
							esc_html( self::KEY_MESSAGE )   => $e->getMessage(),
						)
					)
				);
			}
		}

		die(
			json_encode(
				array(
					self::CODE_RESPONSE => self::SUCCESSFUL,
					self::KEY_MESSAGE   => $arr_result,
				)
			)
		);
	}
	/** Get new orders
	 *
	 * @param int $order_id The last order id.
	 */
	public function get_new_orders( $order_id = '' ) {
		$order_id = (int) $order_id;

		if ( $order_id < 1 ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => 'Order ID is incorrect',
					)
				)
			);
		}

		$orders = array();

		try {
			// Select new order count.
			$query = 'SELECT COUNT(ID) AS CountNewOrder FROM ' . $this->get_db_prefix()
				. "posts WHERE ID > $order_id AND post_type = 'shop_order'";
			$result = $this->get_sql_results( $query );
			if ( ! $result ) {
				die(
					json_encode(
						array(
							esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
							esc_html( self::KEY_MESSAGE )   => 'Cannot get count of new orders',
						)
					)
				);
			}
			$count_new_orders = $result[0]['CountNewOrder'];

			// Select maximum order id.
			$query = 'SELECT  MAX(ID) AS MaxOrderId FROM ' . $this->get_db_prefix()
				. "posts WHERE post_type = 'shop_order'";
			$result = $this->get_sql_results( $query );
			if ( ! $result ) {
				die(
					json_encode(
						array(
							esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
							esc_html( self::KEY_MESSAGE )   => 'Cannot get max order id',
						)
					)
				);
			}
			$max_order_id = $result[0]['MaxOrderId'];

			// Select new orders.
			$query = 'SELECT posts.ID,
					cusomer_user_id.meta_value AS customer_id,
					order_total.meta_value AS order_total,
					order_currency.meta_value AS order_currency_code,
					first_name.meta_value AS firstname,
					last_name.meta_value AS lastname
				FROM ' . $this->get_db_prefix() . 'posts AS posts
				LEFT JOIN ' . $this->get_db_prefix() . "postmeta AS cusomer_user_id ON posts.ID = cusomer_user_id.post_id
					AND cusomer_user_id.meta_key = '_customer_user'
				LEFT JOIN " . $this->get_db_prefix() . "postmeta AS order_total ON posts.ID = order_total.post_id
					AND order_total.meta_key = '_order_total'
				LEFT JOIN " . $this->get_db_prefix() . "postmeta AS order_currency ON posts.ID = order_currency.post_id
					AND order_currency.meta_key = '_order_currency'
				LEFT JOIN " . $this->get_db_prefix() . "usermeta AS first_name ON cusomer_user_id.meta_value = first_name.user_id
					AND first_name.meta_key = 'first_name'
				LEFT JOIN " . $this->get_db_prefix() . "usermeta AS last_name ON cusomer_user_id.meta_value = last_name.user_id
					AND last_name.meta_key = 'last_name'
				WHERE posts.post_type = 'shop_order'";
			$result = $this->get_sql_results( $query );
			if ( ! $result ) {
				die(
					json_encode(
						array(
							esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
							esc_html( self::KEY_MESSAGE )   => 'Cannot get new orders',
						)
					)
				);
			}

			foreach ( $result as $order ) {
				$order['firstname'] = (string) $order['firstname'];
				$order['lastname']  = (string) $order['lastname'];
				$orders[]           = $order;
			}
		} catch ( Exception $e ) {
			die(
				json_encode(
					array(
						esc_html( self::CODE_RESPONSE ) => esc_html( self::ERROR_CODE_COMMON ),
						esc_html( self::KEY_MESSAGE )   => $e->getMessage(),
					)
				)
			);
		}

		die(
			json_encode(
				array(
					self::CODE_RESPONSE => self::SUCCESSFUL,
					self::KEY_MESSAGE   => array(
						'CountNewOrder' => $count_new_orders,
						'MaxOrderId'    => $max_order_id,
						'OrderInfo'     => $orders,
					),
				)
			)
		);
	}
	/** Clear the cache */
	public function clear_cache() {
		return false;
	}
	/** Get the length of string
	 *
	 * @param string $str The string to check.
	 */
	public function str_len( $str ) {
		return strlen( $str );
	}
	/** Get the substring
	 *
	 * @param string $str The full string.
	 * @param int    $start The start position.
	 * @param int    $length The length of substring.
	 */
	public function sub_str( $str, $start, $length = false ) {
		return $length ? substr( $str, $start, $length ) : substr( $str, $start );
	}
	/** String to lowercase
	 *
	 * @param string $str The string to edit.
	 */
	public function str_to_lower( $str ) {
		return strtolower( $str );
	}
	/** String to uppercase
	 *
	 * @param string $str The string to edit.
	 */
	public function str_to_upper( $str ) {
		return strtoupper( $str );
	}
	/** Stripe slashes
	 *
	 * @param string $str The string to edit.
	 */
	public function strip_slashes( $str ) {
		return stripslashes( $str );
	}
	/** Get content from the file
	 *
	 * @param string $file The path to the file.
	 */
	public function file_get_contents( $file ) {
		return file_get_contents( $file );
	}
	/** Put content to the file
	 *
	 * @param string $path The path to the file.
	 * @param string $content The content to put.
	 * @param string $mode The mode to interact with the file.
	 */
	public function file_put_contents( $path, $content, $mode = 0 ) {
		return file_put_contents( $path, $content, $mode );
	}
	/** Prepare the sql
	 *
	 * @param string $data The data to sanitize.
	 */
	public function p_sql( $data ) {
		return $data;
	}
	/** Save the configuration data
	 *
	 * @param string $data The configuration data.
	 */
	public function save_config_data( $data ) {
		add_option( 'SMCONNECTOR_OPTIONS', serialize( $data ) );
	}
	/** Open the file
	 *
	 * @param string $path The path to the file.
	 * @param string $mode The mode of the file opening.
	 */
	public function file_open( $path, $mode ) {
		return fopen( $path, $mode );
	}
	/** Close the file
	 *
	 * @param string $resource The path to the file.
	 */
	public function file_close( $resource ) {
		return fclose( $resource );
	}
	/** Check if the file is readable
	 *
	 * @param string $path The path to the file.
	 */
	public function is_readable( $path ) {
		return is_readable( $path );
	}
	/** Check if the file is writable
	 *
	 * @param string $path The path to the file.
	 */
	public function is_writable( $path ) {
		return is_writable( $path );
	}
	/** Check if the directory path is correct
	 *
	 * @param string $path The path to the directory.
	 */
	public function is_directory( $path ) {
		return is_dir( $path );
	}
	/** Check if the file is correct
	 *
	 * @param string $path The path to file.
	 */
	public function is_file( $path ) {
		return is_file( $path );
	}
	/** Get data about the file
	 *
	 * @param string $path The path to file.
	 */
	public function stat( $path ) {
		return stat( $path );
	}
	/** Search for the file
	 *
	 * @param string $path The path to file.
	 * @param string $pattern The search pattern.
	 * @param bool   $only_dir Search only by directories parameter.
	 */
	public function search( $path, $pattern = '*', $only_dir = false ) {
		$data = glob( "$path/$pattern" );

		if ( $only_dir ) {
			$dirs = array();

			foreach ( $data as $item ) {
				if ( $this->is_directory( $item ) ) {
					$dirs[] = $item;
				}
			}

			return $dirs;
		}

		return $data;
	}
	/** Read the directory
	 *
	 * @param string $path The path to directory.
	 */
	public function read_directory( $path ) {
		return readdir( $path );
	}
	/** Get the last modification time of a file
	 *
	 * @param string $path The path to file.
	 */
	public function filemtime( $path ) {
		return filemtime( $path );
	}
	/** Count the file size
	 *
	 * @param string $path The path to file.
	 */
	public function file_size( $path ) {
		return filesize( $path );
	}
	/** Check if the file exists
	 *
	 * @param string $path The path to file.
	 */
	public function file_exists( $path ) {
		return file_exists( $path );
	}
	/** Write to the file
	 *
	 * @param string $resource The path to file.
	 * @param string $data The data to write.
	 */
	public function file_write( $resource, $data ) {
		return fwrite( $resource, $data );
	}
	/** Read the file
	 *
	 * @param string $resource The path to file.
	 * @param int    $length The length to read.
	 */
	public function file_read( $resource, $length ) {
		return fread( $resource, $length );
	}
	/** Open the gz file
	 *
	 * @param string $path The path to file.
	 * @param string $mode The mode to open file.
	 */
	public function gz_file_open( $path, $mode ) {
		return gzopen( $path, $mode );
	}
	/** Write the gz file
	 *
	 * @param string $resource The path to gz file.
	 * @param string $data The data to write.
	 */
	public function gz_file_write( $resource, $data ) {
		gzwrite( $resource, $data );
	}
	/** Close the gz file
	 *
	 * @param string $resource The path to gz file.
	 */
	public function gz_file_close( $resource ) {
		gzclose( $resource );
	}
	/** Unlink path
	 *
	 * @param string $path The directory path.
	 */
	public function unlink( $path ) {
		return unlink( $path );
	}
	/** Create directory
	 *
	 * @param string $path The path directory.
	 * @param string $permissions The user permissions.
	 */
	public function create_directory( $path, $permissions ) {
		return mkdir( $path, $permissions );
	}
	/** Get parent directory
	 *
	 * @param string $path The path of parent directory.
	 */
	public function get_parent_directory( $path ) {
		return dirname( $path );
	}
	/** Get parent directory
	 *
	 * @param string $data The path of parent directory.
	 */
	public function unserialize( $data ) {
		return unserialize( $data );
	}
	/** Get payment and shipping methods for POS */
	public function get_payment_and_shipping_methods() {
		$shipping_methods = array();
		$payment_methods  = array();

		// Get shipping methods.
		$wc_shiping = WC_Shipping::instance();
		$methods   = $wc_shiping->load_shipping_methods();
		foreach ( $methods as $method ) {
			$shipping_method = array( 'id' => $method->id );

			if ( ! empty( $method->title ) ) {
				$shipping_method['title'] = $method->title;
			}

			if ( ! empty( $method->method_title ) ) {
				$shipping_method['method_title'] = $method->method_title;
			}

			$shipping_methods[] = $shipping_method;
		}

		// Get payment methods.
		$wc_payment = WC_Payment_Gateways::instance();
		$methods   = $wc_payment->get_available_payment_gateways();
		foreach ( $methods as $method ) {
			$payment_method = array( 'id' => $method->id );

			if ( ! empty( $method->title ) ) {
				$payment_method['title'] = $method->title;
			}

			if ( ! empty( $method->method_title ) ) {
				$payment_method['method_title'] = $method->method_title;
			}

			$payment_methods[] = $payment_method;
		}

		die(
			json_encode(
				array(
					self::CODE_RESPONSE => self::SUCCESSFUL,
					self::KEY_MESSAGE => array(
						'shipping_methods' => $shipping_methods,
						'payment_methods' => $payment_methods,
					),
				)
			)
		);
	}
	/** Get archive instance */
	public function get_zip_archive_instance() {
		return new ZipArchive();
	}
	/** Get archive create value */
	public function get_zip_archive_create_value() {
		return ZipArchive::CREATE;
	}
}

