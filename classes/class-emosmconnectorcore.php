<?php
/**
 *   This file is part of Store Manager Connector.
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
 *   along with Mobile Assistant Connector.
 *   If not, see <http://www.gnu.org/licenses/>.
 *
 *   author    eMagicOne <contact@emagicone.com>
 *   2024 eMagicOne
 *   http://www.gnu.org/licenses   GNU General Public License
 *
 *   @package eMagicOne Store Manager for WooCommerce
 */

/**
 * Class which contains those methods which have be overridden in child class
 */
abstract class EmoSMConnectorCore {
	/** The module configuration parameter
	 *
	 * @var string $module_name The module name parameter.
	 */
	public $module_name;
	/** The module configuration parameter
	 *
	 * @var array $options_name The name of option.
	 */
	protected $options_name;
	/** The sql encode parameter
	 *
	 * @var bool $is_sql_encoded_in_base64 Check if sql encoded in base64.
	 */
	protected $is_sql_encoded_in_base64 = false;
	// public $error_no;   /* error number during sql query execution */.
	/** The error message
	 *
	 * @var string $error_msg The error message during sql query execution.
	 */
	public $error_msg;

	const NUMERIC      = 1;     /* numeric array */
	const ASSOC        = 0;     /* associating array */
	const PRODUCT      = 'p';   /* entity 'product' */
	const CATEGORY     = 'c';   /* entity 'category' */
	const MANUFACTURER = 'm';   /* entity 'manufacturer' */
	const CARRIER      = 's';   /* entity 'carrier' */
	const SUPPLIER     = 'su';  /* entity 'supplier' */
	const ATTRIBUTE    = 'co';  /* entity 'attribute' */

	/*const IMAGE_FILE        = 'image_file';*/
	const IMAGE_URL = 'image_url';
	const CODE_RESPONSE = 'response_code';
	const KEY_MESSAGE = 'message';
	const SUCCESSFUL = 20; /* an operation was executed successfully */
	const ERROR_CODE_COMMON = 19;
	const ERROR_GENERATE_STORE_FILE_ARCHIVE = 27;

	/** Check if the plugin is enabled */
	abstract public function is_module_enabled();
	/** Get the connector configuration options */
	abstract public function get_sm_connector_options();
	/** Get the root shop directory */
	abstract public function get_shop_root_dir();
	/** Get the database host */
	abstract public function get_db_host();
	/** Get the database name */
	abstract public function get_db_name();
	/** Get the database username */
	abstract public function get_db_username();
	/** Get the database password */
	abstract public function get_db_password();
	/** Get the database prefix */
	abstract public function get_db_prefix();
	/** Get sql results
	 *
	 * @param string $sql The sql string.
	 */
	abstract public function get_sql_results( $sql);
	/** Execude the sql
	 *
	 * @param string $sql The sql to execude.
	 * @param bool   $reconnect The reconnect parameter.
	 */
	abstract public function exec_sql( $sql, $reconnect);
	/** Sanitize sql
	 *
	 * @param string $sql The sql string to sanitize.
	 */
	abstract public function sanitize_sql( $sql);
	/** Check if is set the request parameter
	 *
	 * @param string $param The parameter.
	 */
	abstract public function isset_request_param( $param);
	/** Get parameters from request
	 *
	 * @param string $param The parameter.
	 */
	abstract public function get_request_param( $param);
	/** Get the store link
	 *
	 * @param bool $ssl The ssl parameter.
	 */
	abstract public function get_store_link( $ssl);
	/** Run the indexer */
	abstract public function run_indexer();
	/** Get version of shopping cart */
	abstract public function get_cart_version();
	/** Get the image
	 *
	 * @param string $entity_type The type of image.
	 * @param int    $image_id The image id.
	 */
	abstract public function get_image( $entity_type, $image_id);
	/** Upload the image
	 *
	 * @param string $entity_type The type of entity.
	 * @param int    $image_id The image id.
	 * @param string $image The image.
	 * @param string $type The type of image.
	 */
	abstract public function set_image( $entity_type, $image_id, $image, $type);
	/** Get image directory
	 *
	 * @param string $type The image type.
	 */
	abstract public function get_image_dir( $type);
	/** Delete image
	 *
	 * @param string $entity_type The image typee.
	 * @param string $image_id The image id.
	 */
	abstract public function delete_image( $entity_type, $image_id);
	/** Delete file
	 *
	 * @param string $filepath The filepath to file.
	 */
	abstract public function delete_file( $filepath);
	/** Copy image
	 *
	 * @param string $entity_type The type of image.
	 * @param string $from_image_id The from image id.
	 * @param string $to_image_id The to image id.
	 */
	abstract public function copy_image( $entity_type, $from_image_id, $to_image_id);
	/** Get the file
	 *
	 * @param string $folder The folder of the file.
	 * @param string $filename The filename.
	 */
	abstract public function get_file( $folder, $filename);
	/** Upload the file
	 *
	 * @param string $folder The folder path.
	 * @param string $filename The filename to upload.
	 * @param string $file The the file to upload.
	 */
	abstract public function set_file( $folder, $filename, $file);
	/** Check data changes
	 *
	 * @param array $tables_arr The array of changes.
	 */
	abstract public function check_data_changes( $tables_arr);
	/** Get new orders
	 *
	 * @param int $order_id The last order id.
	 */
	abstract public function get_new_orders( $order_id);
	/** Clear the cache */
	abstract public function clear_cache();
	/** Get the length of string
	 *
	 * @param string $str The string to check.
	 */
	abstract public function str_len( $str);
	/** Get the substring
	 *
	 * @param string $str The full string.
	 * @param int    $start The start position.
	 * @param int    $length The length of substring.
	 */
	abstract public function sub_str( $str, $start, $length = false);
	/** String to lowercase
	 *
	 * @param string $str The string to edit.
	 */
	abstract public function str_to_lower( $str);
	/** String to uppercase
	 *
	 * @param string $str The string to edit.
	 */
	abstract public function str_to_upper( $str);
	/** Stripe slashes
	 *
	 * @param string $str The string to edit.
	 */
	abstract public function strip_slashes( $str);
	/** Get content from the file
	 *
	 * @param string $file The path to the file.
	 */
	abstract public function file_get_contents( $file);
	/** Put content to the file
	 *
	 * @param string $path The path to the file.
	 * @param string $content The content to put.
	 * @param string $mode The mode to interact with the file.
	 */
	abstract public function file_put_contents( $path, $content, $mode = null);
	/** Prepare the sql
	 *
	 * @param string $data The data to sanitize.
	 */
	abstract public function p_sql( $data);
	/** Save the configuration data
	 *
	 * @param string $data The configuration data.
	 */
	abstract public function save_config_data( $data);
	/** Open the file
	 *
	 * @param string $path The path to the file.
	 * @param string $mode The mode of the file opening.
	 */
	abstract public function file_open( $path, $mode);
	/** Close the file
	 *
	 * @param string $resource The path to the file.
	 */
	abstract public function file_close( $resource);
	/** Check if the file is readable
	 *
	 * @param string $path The path to the file.
	 */
	abstract public function is_readable( $path);
	/** Check if the file is writable
	 *
	 * @param string $path The path to the file.
	 */
	abstract public function is_writable( $path);
	/** Check if the directory path is correct
	 *
	 * @param string $path The path to the directory.
	 */
	abstract public function is_directory( $path);
	/** Check if the file is correct
	 *
	 * @param string $path The path to file.
	 */
	abstract public function is_file( $path);
	/** Get data about the file
	 *
	 * @param string $path The path to file.
	 */
	abstract public function stat( $path);
	/** Search for the file
	 *
	 * @param string $path The path to file.
	 * @param string $pattern The search pattern.
	 * @param bool   $only_dir Search only by directories parameter.
	 */
	abstract public function search( $path, $pattern = '*', $only_dir = false);
	/** Read the directory
	 *
	 * @param string $path The path to directory.
	 */
	abstract public function read_directory( $path);
	/** Get the last modification time of a file
	 *
	 * @param string $path The path to file.
	 */
	abstract public function filemtime( $path);
	/** Count the file size
	 *
	 * @param string $path The path to file.
	 */
	abstract public function file_size( $path);
	/** Check if the file exists
	 *
	 * @param string $path The path to file.
	 */
	abstract public function file_exists( $path);
	/** Write to the file
	 *
	 * @param string $resource The path to file.
	 * @param string $data The data to write.
	 */
	abstract public function file_write( $resource, $data);
	/** Read the file
	 *
	 * @param string $resource The path to file.
	 * @param int    $length The length to read.
	 */
	abstract public function file_read( $resource, $length);
	/** Open the gz file
	 *
	 * @param string $path The path to file.
	 * @param string $mode The mode to open file.
	 */
	abstract public function gz_file_open( $path, $mode);
	/** Write the gz file
	 *
	 * @param string $resource The path to gz file.
	 * @param string $data The data to write.
	 */
	abstract public function gz_file_write( $resource, $data);
	/** Close the gz file
	 *
	 * @param string $resource The path to gz file.
	 */
	abstract public function gz_file_close( $resource);
	/** Unlink path
	 *
	 * @param string $path The directory path.
	 */
	abstract public function unlink( $path);
	/** Create directory
	 *
	 * @param string $path The path directory.
	 * @param string $permissions The user permissions.
	 */
	abstract public function create_directory( $path, $permissions);
	/** Get parent directory
	 *
	 * @param string $path The path of parent directory.
	 */
	abstract public function get_parent_directory( $path);
	/** Unserialize
	 *
	 * @param string $data The data to unserialize.
	 */
	abstract public function unserialize( $data);
	/** Get payment and shipping methods for POS */
	abstract public function get_payment_and_shipping_methods();
	/** Get archive instance */
	abstract public function get_zip_archive_instance();
	/** Get archive create value */
	abstract public function get_zip_archive_create_value();

	/** SM Connector Core
	 *
	 * @param string     $path The path to files.
	 * @param array      $arr_ignore_dir The array of ignored dirs.
	 * @param string     $mask The mask of ignored dirs.
	 * @param bool|false $include_subdir The includes of subdirectory .
	 * @param array      $skip The skip parameter.
	 * @param array      $files The files array.
	 * @return array
	 */
	public function get_files_recursively(
		$path,
		$arr_ignore_dir = array(),
		$mask = '*',
		$include_subdir = false,
		$skip = array( '.', '..' ),
		$files = array()
	) {
		if ( $this->is_readable( $path ) ) {
			$root_dir = $this->get_shop_root_dir();

			foreach ( $this->search( $path, $mask ) as $file ) {
				if ( $this->is_file( $file ) ) {
					$files[] = $this->sub_str( $file, $this->str_len( $root_dir ) - 1 );
				}
			}

			if ( $include_subdir ) {
				foreach ( $this->search( $path, '*', true ) as $directory ) {
					if ( in_array( $directory, $arr_ignore_dir ) ) {
						continue;
					}

					$files = $this->get_files_recursively(
						$directory,
						$arr_ignore_dir,
						$mask,
						$include_subdir,
						$skip,
						$files
					);
				}
			}
		}

		return $files;
	}
	/** Get ignored dirs */
	public function get_ignore_dirs() {
		if ( ! $this->isset_request_param( 'ignore_dir' ) ) {
			return array();
		}

		$ignore_dir = (string) $this->get_request_param( 'ignore_dir' );

		if ( empty( $ignore_dir ) ) {
			return array();
		}

		$arr_ignore_dir = explode( ';', $ignore_dir );
		$root_dir = $this->get_shop_root_dir();

		// Set full directory path.
		$count = count( $arr_ignore_dir );
		for ( $i = 0; $i < $count; $i++ ) {
			$arr_ignore_dir[ $i ] = $root_dir . ltrim( $arr_ignore_dir[ $i ], '/' );
		}

		return $arr_ignore_dir;
	}
	/** Get code response */
	public function get_code_response() {
		return self::CODE_RESPONSE;
	}
	/** Get code successful */
	public function get_code_successful() {
		return self::SUCCESSFUL;
	}
	/** Get key message */
	public function get_key_message() {
		return self::KEY_MESSAGE;
	}
	/** Set sql encoded in base64
	 *
	 * @param bool $is_encoded The encoded parameter.
	 */
	public function set_sql_encoded_in_base64( $is_encoded ) {
		$this->is_sql_encoded_in_base64 = $is_encoded;
	}
}
