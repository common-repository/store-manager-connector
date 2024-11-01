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
 * Class which has common store manager connector functionality
 */
class EmoSMConnectorCommon {
	/** Dump variable
	 *
	 * @var int $dump_file_part_number The number property represents an integer value.
	 */
	private $backup_file_ext = 'sql';
	/** Connector variable
	 *
	 * @var array $br_errors The list of errors.
	 */
	private $br_errors;
	/** Connector variable
	 *
	 * @var array $smconnector_options The options of connection configuration.
	 */
	private $smconnector_options;
	/** Dump variable
	 *
	 * @var bool $sql_compatibility The compability of sql.
	 */
	private $sql_compatibility;
	/** Dump variable
	 *
	 * @var string $sql_delimiter The delimeter of sql parts.
	 */
	private $sql_delimiter       = '/*DELIMITER*/';
	/** Dump variable
	 *
	 * @var int $count_sql_exec_prev The counter of previous sql parts.
	 */
	private $count_sql_exec_prev = 0;
	/*private $test_result;*/
	/** Connector variable
	 *
	 * @var string $request_params The request parameters.
	 */
	private $request_params;
	/** Dump variable
	 *
	 * @var array $db_tables The database tables array.
	 */
	private $db_tables      = array();
	/** Connector variable
	 *
	 * @var bool $log_file_reset The log reset checker.
	 */
	private $log_file_reset = false;
	/** Connector variable
	 *
	 * @var string $db_file_handler The database file handler.
	 */
	private $db_file_handler;
	/** Connector variable
	 *
	 * @var object $shop_cart The shop cart object.
	 */
	private $shop_cart;
	/** Configuration variable
	 *
	 * @var string $tmp_folder_path The tmp folder path.
	 */
	private $tmp_folder_path;
	/** Upload variable
	 *
	 * @var array $searched_files Files list.
	 */
	private $searched_files = array();
	/** Connector variable
	 *
	 * @var string $module_version Connector version.
	 */
	private $module_version;
	/** Connector variable
	 *
	 * @var int $revision Store Manager revision variable.
	 */
	private $revision;
	/** Dump variable
	 *
	 * @var array $post_replace_from_sm Replace symbols in chunk.
	 */
	private $post_replace_from_sm = array(
		'-' => '+',
		'_' => '/',
		',' => '=',
	);
	/** Upload variable
	 *
	 * @var string $image_url The image link.
	 */
	private $image_url;
	/** General variable
	 *
	 * @var string $code_response The code response.
	 */
	private $code_response;
	/** General variable
	 *
	 * @var string $key_message The key message.
	 */
	private $key_message;
	/** General variable
	 *
	 * @var string $successful_code The successful code.
	 */
	private $successful_code;
	/** General variable
	 *
	 * @var string $error_code_common The common error code.
	 */
	private $error_code_common;
	/** Dump variable
	 *
	 * @var string $error_generate_store_file_archive The error generate file archive.
	 */
	private $error_generate_store_file_archive;
	/** Dump variable
	 *
	 * @var string $dump_data_prev The dump data previev.
	 */
	private $dump_data_prev = false;
	/** Configuration variable
	 *
	 * @var string $default_tmp_dir The default tmp folder.
	 */
	private $default_tmp_dir;
	/** Dump variable
	 *
	 * @var string $dump_file_current Current dump file.
	 */
	private $dump_file_current;
	/** Dump variable
	 *
	 * @var int $dump_file_part_number The number of dump part.
	 */
	private $dump_file_part_number = 1;
	/*const SMCONNECTOR_COMMON_VERSION              = 4;*/
	const TEST_POST_STRING = '////AjfiIkllsomsdjUNNLkdsuinmJNFIkmsiidmfmiOKSFKMI/////';
	const TEST_OK          = '<span style="color: #008000;">Ok</span>';
	const TEST_FAIL        = '<span style="color: #ff0000;">Fail</span>';
	const TEST_YES         = '<span style="color: #008000;">Yes</span>';
	const TEST_SKIP        = '<span style="color: #808080;">Test Skipped</span>';
	const TEST_NO          = '<span style="color: #ff0000;">Fail</span>';
	const QOUTE_CHAR       = '"';
	const LOG_FILENAME     = 'smconnector.log';
	/*const MODULE_NAME                        = 'smconnector.log';*/
	const DB_FILE_PREFIX                     = 'm1smconnector_';
	const TMP_FILE_PREFIX                    = 'm1smconnectortmp_';
	const INTERMEDIATE_FILE_NAME             = 'sm_intermediate.txt';
	const DB_FILE_MAIN                       = 'em1_smconnector_db_dump';
	const DB_FILE_COMPRESSION_NO             = 'em1_smconnector_db_dump.sql';
	const DB_FILE_COMPRESSION_YES            = 'em1_smconnector_db_dump.gz';
	const DB_DATA_TMP                        = 'em1_dump_data_tmp.txt';
	const FILE_TMP_GET_SQL                   = 'em1_tmp_get_sql.txt';
	const FILE_TMP_PUT_SQL                   = 'em1_tmp_put_sql.txt';
	const GET_SQL_CANCEL_MESSAGE             = 'Generating database dump is canceled';
	const GET_SQL_CANCEL_PARAM               = 'get_sql_cancel';
	const GET_SQL_TABLE                      = 'get_sql_table';
	const GET_SQL_PERCENTAGE                 = 'get_sql_percentage';
	const GET_SQL_FILE_PART                  = 'get_sql_file_part';
	const GET_SQL_FILE_PART_NAME             = 'get_sql_file_part_name';
	const GET_SQL_FILE_NAME_GENERATING       = 'get_sql_file_name_generating';
	const DB_FILE_EXT_COMPRESSION_NO         = '.sql';
	const DB_FILE_EXT_COMPRESSION_YES        = '.gz';
	const FILE_NAME_PART_NUMBER_COUNT_DIGITS = 3;
	const NUMERIC                            = 1;
	const ASSOC                              = 0;
	const PUT_SQL_ENCODED                    = 'base_64_encoded_';
	/*const UPLOAD_IMAGE_FILE_NAME      = 'image_file';*/
	const UPLOAD_FILE_NAME       = 'file';
	const FILE_READ_SIZE         = 102400; /* B */
	const DELAY_TO_GENERATE_DUMP = 10; /* seconds */

	/* Section of default values which are stored in database */
	const DEFAULT_LOGIN              = '1';
	const DEFAULT_PASSWORD           = '1';
	const DEFAULT_ALLOW_COMPRESSION  = 1;
	const DEFAULT_COMPRESS_LEVEL     = 6;      /* 1 - 9 */
	const DEFAULT_LIMIT_QUERY_SIZE   = 8192;      /* kB */
	const DEFAULT_PACKAGE_SIZE       = 1024;      /* kB */
	const DEFAULT_EXCLUDE_DB_TABLES  = '';
	const DEFAULT_NOTIFICATION_EMAIL = '';
	const DEFAULT_ALLOWED_IPS        = '';
	const MIN_COMPRESS_LEVEL         = 1;
	const MAX_COMPRESS_LEVEL         = 9;
	const MIN_LIMIT_QUERY_SIZE       = 100;    /* kB */
	const MAX_LIMIT_QUERY_SIZE       = 100000;    /* kB */
	const MIN_PACKAGE_SIZE           = 100;    /* kB */
	const MAX_PACKAGE_SIZE           = 30000;    /* kB */
	const MAX_KEY_LIFETIME   = 86400; /* 24 hours */
	const TABLE_SESSION_KEYS = 'smconnector_session_keys';
	const TABLE_FAILED_LOGIN = 'smconnector_failed_login';
	/*
	An operation was executed successfully
	*/
	/*const SUCCESSFUL = 20;*/

	/* chunk checksum from the store manager and chunk checksum from the smconnector file are different */
	const POST_ERROR_CHUNK_CHECKSUM_DIF = 21;

	/*
	Chunk checksums are correct, but some sql code was not executed; has one parameter - an index of sql code
	which was not executed
	*/
	const POST_ERROR_SQL_INDEX = 22;

	const ERROR_CODE_AUTHENTICATION = 25;
	const ERROR_CODE_SESSION_KEY    = 26;
	const ERROR_TEXT_AUTHENTICATION = 'Authentication error';
	const ERROR_TEXT_SESSION_KEY    = 'Session key error';

	/*
	Commented:
	const CODE_RESPONSE = 'response_code';
	const KEY_MESSAGE   = 'message';
	*/

	/* It is used to retry putting sql when server is temporary unavailable */
	const MAX_COUNT_ATTEMPT_POST = 3;   /* maximum count of attempts */
	const DELAY_BETWEEN_POST     = 20;  /* delay between attempts (seconds) */

	/** Main method to run Store Manager Connector
	 *
	 * @param string $shop_cart_overrider Cart overrider.
	 * @param string $module_version The module version.
	 * @param int    $revision The module revision.
	 */
	public function __construct( $shop_cart_overrider, $module_version, $revision ) {
		$this->shop_cart       = $shop_cart_overrider;
		$this->default_tmp_dir = '/modules/' . $this->shop_cart->module_name . '/tmp';
		// $this->image_file = $shop_cart_overrider::IMAGE_FILE;
		$this->image_url                         = $shop_cart_overrider::IMAGE_URL;
		$this->code_response                     = $shop_cart_overrider::CODE_RESPONSE;
		$this->key_message                       = $shop_cart_overrider::KEY_MESSAGE;
		$this->successful_code                   = $shop_cart_overrider::SUCCESSFUL;
		$this->error_code_common                 = $shop_cart_overrider::ERROR_CODE_COMMON;
		$this->error_generate_store_file_archive = $shop_cart_overrider::ERROR_GENERATE_STORE_FILE_ARCHIVE;
		$this->module_version                    = $module_version;
		$this->revision                          = $revision;
		$this->get_errors();
		$this->smconnector_options = $this->shop_cart->get_sm_connector_options();
		$this->check_sm_connector_options();
		if ( 1 === $this->smconnector_options['use_plugin_tmp_dir'] ) {
			$this->tmp_folder_path = emo_smc_get_plugin_tmp_path();
		} else {
			$this->tmp_folder_path = $this->shop_cart->get_shop_root_dir() . $this->smconnector_options['tmp_dir'];
		}
		$timestamp = time();

		if ( ! isset( $this->smconnector_options['last_clear_date'] )
			|| ( $timestamp - (int) $this->smconnector_options['last_clear_date'] ) > self::MAX_KEY_LIFETIME
		) {
			$this->clear_old_data();
			$this->smconnector_options['last_clear_date'] = $timestamp;
			$this->shop_cart->save_config_data( $this->smconnector_options );
		}

		if ( ! $this->shop_cart->isset_request_param( 'task' ) ) {
			$this->run_self_test();
		}

		if ( ! $this->shop_cart->is_module_enabled() ) {
			$this->generate_error( $this->br_errors['module_disabled'] );
		}

		$this->check_auth();

		if ( $this->shop_cart->isset_request_param( 'task' ) ) {
			$this->sm_connector_action();
		} else {
			$this->delete_session_key();
			die(
				json_encode(
					array(
						$this->code_response => $this->error_code_common,
						$this->key_message   => 'Missing parameters',
					)
				)
			);
		}
	}
	/** List of errors */
	private function get_errors() {
		$this->br_errors = array(
			'authentification'                       => "StoreManagerConnector (v.{$this->module_version}): Authentication Error",
			'create_tmp_file'                        => "StoreManagerConnector (v.{$this->module_version}): Can't Create Temporary File",
			'open_tmp_file'                          => "StoreManagerConnector (v.{$this->module_version}): Can't Open Temporary File",
			'not_writeable_dir'                      => "StoreManagerConnector (v.{$this->module_version}): Temporary Directory specified in StoreManagerConnector settings doesn't exist or is not writeable",
			'temporary_file_exist_not'               => "StoreManagerConnector (v.{$this->module_version}): Temporary File doesn't exist",
			'temporary_file_readable_not'            => "StoreManagerConnector (v.{$this->module_version}): Temporary File isn't readable",
			'file_uid_mismatch'                      => "StoreManagerConnector (v.{$this->module_version}): SAFE MODE Restriction in effect.
				The script uid is not allowed to access tmp folder owned by other uid. If you don't understand this error,
				please contact your hosting provider for help",
			'open_basedir'                           => "StoreManagerConnector (v.{$this->module_version}): Please create local Temporary Directory,
			according to plugin settings",
			'checksum_dif'                           => 'Checksums are different',
			'ip_check'                               => "StoreManagerConnector (v.{$this->module_version}): Add your IP to allowed list to access plugin,
			please",
			'timezone_check'                         => 'Unable to retrieve timezone information. Please contact your hosting provider for help.',
			'module_disabled'                        => 'Module is disabled',
			'filename_param_missing'                 => 'Request parameter "filename" is missing',
			'filenames_param_missing'                => 'Request parameter "filenames" is missing',
			'position_param_missing'                 => 'Request parameter "position" is missing',
			'sql_param_missing'                      => 'Request parameter "sql" is missing',
			'category_param_missing'                 => 'Request parameter "category" is missing',
			'searchpath_param_missing'               => 'Request parameter "search_path" is missing',
			'varsmaindir_param_missing'              => 'Request parameter "vars_main_dir" is missing',
			'varsnames_param_missing'                => 'Request parameter "vars_names" is missing',
			'xmlpath_param_missing'                  => 'Request parameter "xml_path" is missing',
			'xmlfields_param_missing'                => 'Request parameter "xml_fields" is missing',
			'xmlitemsnode_param_missing'             => 'Request parameter "xml_items_node" is missing',
			'xmlitemsinfonode_param_missing'         => 'Request parameter "xml_items_info_node" is missing',
			'tablename_param_missing'                => 'Request parameter "table_name" is missing',
			'orderid_param_missing'                  => 'Request parameter "order_id" is missing',
			'entitytype_param_missing'               => 'Request parameter "entity_type" is missing',
			'imageid_param_missing'                  => 'Request parameter "image_id" is missing',
			'toimageid_param_missing'                => 'Request parameter "to_image_id" is missing',
			'path_param_missing'                     => 'Request parameter "path" is missing',
			'searchpath_param_empty'                 => 'Request parameter "search_path" is empty',
			'varsmaindir_param_empty'                => 'Request parameter "vars_main_dir" is empty',
			'varsnames_param_empty'                  => 'Request parameter "vars_names" is empty',
			'xmlpath_param_empty'                    => 'Request parameter "xml_path" is empty',
			'xmlfields_param_empty'                  => 'Request parameter "xml_fields" is empty',
			'xmlitemsnode_param_empty'               => 'Request parameter "xml_items_node" is empty',
			'xmlitemsinfonode_param_empty'           => 'Request parameter "xml_items_info_node" is empty',
			'tablename_param_empty'                  => 'Request parameter "table_name" is empty',
			'entitytype_param_empty'                 => 'Request parameter "entity_type" is empty',
			'imageurl_param_empty'                   => 'Request parameter "image_url" is empty',
			'key_param_empty'                        => 'Request parameter "key" is empty',
			'hash_param_empty'                       => 'Request parameter "hash" is empty',
			'filename_param_empty'                   => 'Request parameter "filename" is empty',
			'path_param_empty'                       => 'Request parameter "path" is empty',
			'category_param_empty'                   => 'Request parameter "category" is empty',
			'orderid_param_incorrect'                => 'Request parameter "order_id" is incorrect',
			'imageid_param_incorrect'                => 'Request parameter "image_id" is incorrect',
			'toimageid_param_incorrect'              => 'Request parameter "to_image_id" is incorrect',
			'upload_file_error'                      => 'Some error occurs uploading file into temporary server folder',
			'delete_file_error'                      => 'No such file',
			'zip_archive_not_supported'              => 'ZipArchive is supported in php version >= 5.2.0',
			'json_data_incorrect'                    => 'json/application data incorrect and can not be decoded',
			'incorrect_body_format_of_files_objects' => 'Sent json data formatted incorrectly',
			'missing_files_object_in_json'           => 'Sent json data is incorrect',
			'zip_not_loaded'                         => 'Zip extension not loaded',
			'cannot_archive_files'                   => 'Cannot archive files',
		);
	}
	/** Check Store Manager Connector Options */
	private function check_sm_connector_options() {
		if ( ! isset( $this->smconnector_options['tmp_dir'] ) ) {
			$this->smconnector_options['tmp_dir'] = $this->default_tmp_dir;
		}

		if ( ! isset( $this->smconnector_options['smconnector_hash'] ) ) {
			$this->smconnector_options['smconnector_hash'] = '';
		}

		if ( ! isset( $this->smconnector_options['allow_compression'] ) ) {
			$this->smconnector_options['allow_compression'] = self::DEFAULT_ALLOW_COMPRESSION;
		} else {
			$this->smconnector_options['allow_compression'] = (int) $this->smconnector_options['allow_compression'];
		}

		if ( ! array_key_exists( 'use_plugin_tmp_dir', $this->smconnector_options ) ) {
			$this->smconnector_options['use_plugin_tmp_dir'] = EMO_SMC_DEFAULT_USE_PLUGIN_TMP_DIR;
		} else {
			$this->smconnector_options['use_plugin_tmp_dir'] = (int) $this->smconnector_options['use_plugin_tmp_dir'];
		}

		if ( ! isset( $this->smconnector_options['limit_query_size'] ) ) {
			$this->smconnector_options['limit_query_size'] = self::DEFAULT_LIMIT_QUERY_SIZE;
		} elseif ( (int) $this->smconnector_options['limit_query_size'] < self::MIN_LIMIT_QUERY_SIZE ) {
			$this->smconnector_options['limit_query_size'] = self::MIN_LIMIT_QUERY_SIZE;
		} elseif ( (int) $this->smconnector_options['limit_query_size'] > self::MAX_LIMIT_QUERY_SIZE ) {
			$this->smconnector_options['limit_query_size'] = self::MAX_LIMIT_QUERY_SIZE;
		} else {
			$this->smconnector_options['limit_query_size'] = (int) $this->smconnector_options['limit_query_size'];
		}

		if ( ! isset( $this->smconnector_options['package_size'] ) ) {
			$this->smconnector_options['package_size'] = self::DEFAULT_PACKAGE_SIZE * 1024; // B.
		} elseif ( (int) $this->smconnector_options['package_size'] < self::MIN_PACKAGE_SIZE ) {
			$this->smconnector_options['package_size'] = self::MIN_PACKAGE_SIZE * 1024;
		} elseif ( (int) $this->smconnector_options['package_size'] > self::MAX_PACKAGE_SIZE ) {
			$this->smconnector_options['package_size'] = self::MAX_PACKAGE_SIZE * 1024;
		} else {
			$this->smconnector_options['package_size'] = (int) $this->smconnector_options['package_size'] * 1024;
		}

		if ( ! isset( $this->smconnector_options['exclude_db_tables'] ) ) {
			$this->smconnector_options['exclude_db_tables'] = self::DEFAULT_EXCLUDE_DB_TABLES;
		}

		if ( ! isset( $this->smconnector_options['notification_email'] ) ) {
			$this->smconnector_options['notification_email'] = self::DEFAULT_NOTIFICATION_EMAIL;
		}

		// Values of $compress_level between 1 and 9 will trade off speed and efficiency, and the default is 6.
		// The 1 flag means "fast but less efficient" compression, and 9 means "slow but most efficient" compression.
		if ( ! isset( $this->smconnector_options['compress_level'] ) ) {
			$this->smconnector_options['compress_level'] = self::DEFAULT_COMPRESS_LEVEL;
		} elseif ( (int) $this->smconnector_options['compress_level'] < self::MIN_COMPRESS_LEVEL ) {
			$this->smconnector_options['compress_level'] = self::MIN_COMPRESS_LEVEL;
		} elseif ( (int) $this->smconnector_options['compress_level'] > self::MAX_COMPRESS_LEVEL ) {
			$this->smconnector_options['compress_level'] = self::MAX_COMPRESS_LEVEL;
		} else {
			$this->smconnector_options['compress_level'] = (int) $this->smconnector_options['compress_level'];
		}

		if ( ! isset( $this->smconnector_options['allowed_ips'] ) ) {
			$this->smconnector_options['allowed_ips'] = self::DEFAULT_ALLOWED_IPS;
		}
	}
	/** Check authorization */
	private function check_auth() {
		if ( $this->shop_cart->isset_request_param( 'key' ) ) {
			$key = (string) $this->shop_cart->get_request_param( 'key' );

			if ( empty( $key ) ) {
				$this->add_failed_attempt();
				$this->generate_error( $this->br_errors['key_param_empty'] );
			}

			if ( ! $this->is_session_key_valid( $key ) ) {
				$this->add_failed_attempt();
				die(
					json_encode(
						array(
							$this->code_response => self::ERROR_CODE_SESSION_KEY,
							$this->key_message   => self::ERROR_TEXT_SESSION_KEY,
						)
					)
				);
			}
		} elseif ( $this->shop_cart->isset_request_param( 'hash' ) ) {
			$hash = (string) $this->shop_cart->get_request_param( 'hash' );

			if ( empty( $hash ) ) {
				$this->add_failed_attempt();
				$this->generate_error( $this->br_errors['hash_param_empty'] );
			}

			if ( ! $this->is_hash_valid( $hash ) ) {
				$this->add_failed_attempt();
				die(
					json_encode(
						array(
							$this->code_response => self::ERROR_CODE_AUTHENTICATION,
							$this->key_message   => self::ERROR_TEXT_AUTHENTICATION,
						)
					)
				);
			}

			$key = $this->generate_session_key( $hash );

			if ( $this->shop_cart->isset_request_param( 'task' ) ) {
				$task = $this->shop_cart->get_request_param( 'task' );

				if ( 'get_version' == $task ) {
					die(
						json_encode(
							array(
								$this->code_response => $this->successful_code,
								'revision'           => $this->revision,
								'module_version'     => $this->module_version,
								'session_key'        => $key,
							)
						)
					);
				}
			}

			die(
				json_encode(
					array(
						$this->code_response => $this->successful_code,
						'session_key'        => $key,
					)
				)
			);
		} else {
			$this->add_failed_attempt();
			die(
				json_encode(
					array(
						$this->code_response => self::ERROR_CODE_AUTHENTICATION,
						$this->key_message   => self::ERROR_TEXT_AUTHENTICATION,
					)
				)
			);
		}
	}
	/** Check if hash is valid
	 *
	 * @param string $hash The hash of connection.
	 */
	private function is_hash_valid( $hash ) {
		if ( $this->smconnector_options['smconnector_hash'] != $hash ) {
			return false;
		}

		return true;
	}
	/** List of SM Connector actions */
	private function sm_connector_action() {
		$this->check_data_before_run();

		$this->request_params = $this->validate_types(
			$_REQUEST,
			array(
				'task'                => 'STR',
				'category'            => 'STR',
				'include_tables'      => 'STR',
				'sql'                 => 'STR',
				'filename'            => 'STR',
				'position'            => 'INT',
				'vars_names'          => 'STR',
				'vars_main_dir'       => 'STR',
				'xml_path'            => 'STR',
				'xml_fields'          => 'STR',
				'xml_items_node'      => 'STR',
				'xml_items_info_node' => 'STR',
				'xml_filters'         => 'STR',
				'search_path'         => 'STR',
				'mask'                => 'STR',
				'ignore_dir'          => 'STR',
				'checksum_sm'         => 'STR',
				'fc'                  => 'STR',
				'module'              => 'STR',
				'controller'          => 'STR',
				'hash'                => 'STR',
				'entity_type'         => 'STR',
				'image_id'            => 'STR',
				'to_image_id'         => 'INT',
			)
		);

		switch ( $this->request_params['task'] ) {
			case 'get_sql':
				$this->get_db_dump();
				break;
			case 'get_sql_file':
				$this->get_db_file();
				break;
			case 'put_sql':
				$this->put_sql();
				break;
			case 'get_version':
				$this->get_module_version();
				break;
			case 'get_config':
				$this->get_config();
				break;
			case 'get_category_tree':
				$this->get_category_tree(); // For store diagnostics.
				break;
			case 'run_indexer':
				$this->run_indexer();
				break;
			case 'get_var_from_script':
				$this->get_vars();
				break;
			case 'get_xml_data':
				$this->get_xml_data();
				break;
			case 'get_ftp_files':
				$this->get_ftp_files();
				break;
			case 'get_cart_version':
				$this->get_cart_version();
				break;
			case 'check_data_changes':
				$this->check_data_changes();
				break;
			case 'get_new_orders':
				$this->get_new_orders();
				break;
			case 'get_sql_cancel':
				$this->create_db_dump_cancel();
				break;
			case 'get_sql_progress':
				$this->create_db_dump_progress();
				break;
			case 'get_sql_file_part_info':
				$this->get_sql_file_part_info();
				break;
			case 'get_image':
				$this->get_image();
				break;
			case 'set_image':
				$this->set_image();
				break;
			case 'delete_image':
				$this->delete_image();
				break;
			case 'delete_file':
				$this->delete_file();
				break;
			case 'copy_image':
				$this->copy_image();
				break;
			case 'get_file':
				$this->get_file();
				break;
			case 'set_file':
				$this->set_file();
				break;
			case 'get_files_details':
				$this->get_files_details();
				break;
			case 'get_cache':
				$this->get_cache();
				break;
			case 'clear_cache':
				$this->clear_cache();
				break;
			case 'get_payment_and_shipping_methods':
				$this->get_payment_and_shipping_methods();
				break;
			case 'get_store_file_archive':
				$this->get_store_file_archive();
				break;
			default:
				$this->delete_session_key();
				break;
		}

		die();
	}
	/** Check data before run */
	private function check_data_before_run() {
		if ( ! $this->check_allowed_ip() ) {
			$this->generate_error( $this->br_errors['ip_check'] );
		}

		/*if ( ! ini_get( 'date.timezone' ) || ini_get( 'date.timezone' ) == '' ) {
			$this->generate_error( $this->br_errors['timezone_check'] );
		}*/

		if ( $this->shop_cart->isset_request_param( 'sql_compatibility' ) ) {
			$this->sql_compatibility = $this->shop_cart->get_request_param( 'sql_compatibility' );
		}

		if ( $this->shop_cart->isset_request_param( 'sql_delimiter' ) ) {
			$this->sql_delimiter = $this->shop_cart->get_request_param( 'sql_delimiter' );
		}

		if ( ! ( function_exists( 'gzopen' )
			&& function_exists( 'gzread' )
			&& function_exists( 'gzwrite' )
			&& function_exists( 'gzclose' ) )
		) {
			$this->smconnector_options['allow_compression'] = 0;
		}

		// Detecting open_basedir - required for temporary file storage.
		if ( ini_get( 'open_basedir' ) && null == $this->smconnector_options['tmp_dir'] ) {
			$this->generate_error( $this->br_errors['open_basedir'] );
		}

		// checking temporary directory.
		if ( ! $this->shop_cart->is_directory( $this->tmp_folder_path )
			|| ! $this->shop_cart->is_writable( $this->tmp_folder_path )
		) {
			$this->generate_error( $this->br_errors['not_writeable_dir'] );
		}

		$tmp_file_stat = $this->shop_cart->stat( $this->tmp_folder_path );
		if ( function_exists('getmyuid') && (ini_get('safe_mode') && getmyuid() != (int)$tmp_file_stat['uid']) ) {
			$this->generate_error( $this->br_errors['file_uid_mismatch'] );
		}

		if ( $this->shop_cart->get_request_param( 'task' ) == 'test_post' ) {
			die( esc_html( self::TEST_POST_STRING ) );
		}
	}
	/** Validate types
	 *
	 * @param array $array The variables array.
	 * @param array $names The names array.
	 */
	private function validate_types( &$array, $names ) {
		foreach ( $names as $name => $type ) {
			if ( isset( $array[ $name ] ) ) {
				switch ( $type ) {
					case 'INT':
						$array[ $name ] = (int) $array[ $name ];
						break;
					case 'FLOAT':
						$array[ $name ] = (float) $array[ $name ];
						break;
					case 'STR':
						$array[ $name ] = str_replace(
							array( "\r", "\n" ),
							' ',
							addslashes( htmlspecialchars( trim( urldecode( $array[ $name ] ) ),
								ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 ) )
						);
						break;
					case 'STR_HTML':
						$array[ $name ] = addslashes( trim( urldecode( $array[ $name ] ) ) );
						break;
					default:
						$array[ $name ] = '';
				}
			} else {
				$array[ $name ] = '';
			}
		}

		$array_keys = array_keys( $array );

		foreach ( $array_keys as $key ) {
			if ( ! $this->shop_cart->isset_request_param( $key ) && 'hash' != $key ) {
				$array[ $key ] = '';
			}
		}

		return $array;
	}
	/** Check allowed ips */
	private function check_allowed_ip() {
		if ( ! empty( $this->smconnector_options['allowed_ips'] ) ) {
			$allowed_ips = explode( ',', $this->smconnector_options['allowed_ips'] );
			$ip_allowed  = false;

			foreach ( $allowed_ips as $ip ) {
				$ip            = trim( $ip );
				$str_without_x = $ip;

				if ( strpos( $ip, 'x' ) !== false ) {
					$str_without_x = $this->shop_cart->sub_str( $ip, 0, strpos( $ip, 'x' ) );
				}

				if ( $this->check_ip( $str_without_x ) === true ) {
					$ip_allowed = true;
					break;
				}
			}

			return $ip_allowed;
		}

		return true;
	}
	/** Check user IP
	 *
	 * @param string $ip The user IP.
	 */
	private function check_ip( $ip ) {

		return ( strpos($_SERVER['REMOTE_ADDR'], $ip ) === 0 );

	}
	/** Generate database dump */
	private function get_db_dump() {
		$this->dump_data_prev = $this->get_dump_data();

		if ( ! $this->dump_data_prev ) {
			$this->set_generating_dump_value(
				array(
					self::GET_SQL_CANCEL_PARAM => 0,
					self::GET_SQL_TABLE        => '',
					self::GET_SQL_PERCENTAGE   => 0,
					self::GET_SQL_FILE_PART    => 0,
				)
			);
		} else {
			if ( $this->is_dump_generating() ) {
				die( 'Dump is being generated. Could not run next attempt' );
			}

			$this->log_file_reset = true;
		}

		$this->put_log( 'Initializing' );

		// Delete old files, create new and open it for putting data.
		$this->open_db_file();

		// Generate database dump.
		$this->create_dump();

		$this->set_generating_dump_value(
			array(
				self::GET_SQL_CANCEL_PARAM => 0,
				self::GET_SQL_TABLE        => '',
				self::GET_SQL_PERCENTAGE   => 0,
			)
		);

		// Output generated database dump information.
		$this->download_dump( $this->dump_file_current, $this->smconnector_options['allow_compression'] );
	}
	/** Get dump data */
	private function get_dump_data() {
		$content = false;
		$file    = $this->tmp_folder_path . '/' . self::DB_DATA_TMP;
		$file_db = $this->tmp_folder_path . '/' . self::DB_FILE_COMPRESSION_NO;

		if ( $this->shop_cart->file_exists( $file ) ) {
			if ( $this->shop_cart->file_exists( $file_db ) && ( time() - $this->shop_cart->filemtime( $file_db ) ) > 600 ) {
				$this->shop_cart->unlink( $file_db );
				return false;
			}

			if ( ! $this->shop_cart->file_exists( $file_db ) ) {
				return false;
			}

			$content = $this->shop_cart->file_get_contents( $file );
			$content = $this->shop_cart->unserialize( $content );
		}

		return $content;
	}
	/** Check if dump generating */
	private function is_dump_generating() {
		$file = $this->tmp_folder_path . '/' . self::LOG_FILENAME;

		if ( $this->shop_cart->file_exists( $file ) ) {
			$checksum_prev = md5_file( $file );
			sleep( self::DELAY_TO_GENERATE_DUMP );

			if ( md5_file( $file ) != $checksum_prev ) {
				return true;
			}
		}

		return false;
	}
	/** Set generating dump value
	 *
	 * @param array $arr The dump array.
	 */
	private function set_generating_dump_value( $arr ) {
		if ( ! is_array( $arr ) ) {
			$arr = array( $arr );
		}

		$file_data = $this->get_generating_dump_value_from_file();

		foreach ( $arr as $key => $value ) {
			$file_data[ $key ] = $value;
		}

		$this->shop_cart->file_put_contents( $this->tmp_folder_path . '/' . self::FILE_TMP_GET_SQL, serialize( $file_data ) );
	}
	/** Get generating dump value
	 *
	 * @param string $name The value name.
	 */
	private function get_generating_dump_value( $name ) {
		$ret    = false;
		$values = $this->get_generating_dump_value_from_file();

		if ( is_array( $name ) ) {
			$ret = array();

			foreach ( $name as $val ) {
				if ( isset( $values[ $val ] ) ) {
					$ret[ $val ] = $values[ $val ];
				} else {
					$ret[ $val ] = '';
				}
			}
		} elseif ( isset( $values[ $name ] ) ) {
			$ret = $values[ $name ];
		}

		return $ret;
	}
	/** Get generating dump value from file */
	private function get_generating_dump_value_from_file() {
		$ret  = array();
		$file = $this->tmp_folder_path . '/' . self::FILE_TMP_GET_SQL;

		if ( $this->shop_cart->file_exists( $file ) ) {
			$content = $this->shop_cart->file_get_contents( $file );
			$ret     = $this->shop_cart->unserialize( $content );
		}

		return $ret;
	}
	/** Get part number
	 *
	 * @param integer $number The part number.
	 */
	private function get_part_number( $number ) {
		return str_pad( $number, self::FILE_NAME_PART_NUMBER_COUNT_DIGITS, '0', STR_PAD_LEFT );
	}
	/** Open db file */
	private function open_db_file() {
		if ( $this->smconnector_options['allow_compression'] ) {
			$this->backup_file_ext = 'gz';
		}

		$this->put_log( 'Creating backup file' );
		$this->dump_file_current = self::DB_FILE_MAIN . $this->get_part_number( $this->dump_file_part_number )
			. self::DB_FILE_EXT_COMPRESSION_NO;
		$this->db_file_handler   = $this->shop_cart->file_open( $this->tmp_folder_path . '/' . $this->dump_file_current, 'ab' );
	}
	/** Create dump */
	private function create_dump() {
		$tabinfo        = array();
		$table_sizes    = array();
		$handled_tables = array();
		$tabsize        = array();
		$tabinfo[0]     = 0;
		$db_size        = 0;
		$t              = 0;
		$continue       = false;

		// Get information about all tables.
		$this->get_tables();

		$result = $this->shop_cart->get_sql_results( 'SHOW TABLE STATUS' );

		if ( ! $result ) {
			$this->generate_error(
				'Error selecting table status. | '// Error: ' . $this->shop_cart->error_no . '; '.
				. $this->shop_cart->error_msg
			);
		}

		foreach ( $result as $item ) {
			if ( in_array( $item['Name'], $this->db_tables ) ) {
				$item['Rows']                         = empty( $item['Rows'] ) ? 0 : $item['Rows'];
				$tabinfo[0]                          += $item['Rows'];
				$tabinfo[ $item['Name'] ]             = $item['Rows'];
				$tabsize[ $item['Name'] ]             = 1
					+ round( $this->smconnector_options['limit_query_size'] * 1024 / ( $item['Avg_row_length'] + 1 ) );
				$table_sizes[ $item['Name'] ]['size'] = $item['Data_length'] + $item['Index_length'];
				$table_sizes[ $item['Name'] ]['rows'] = $item['Rows'];
				$db_size                             += $item['Data_length'] + $item['Index_length'];
			}
		}

		if ( ! $this->dump_data_prev ) {
			$result = $this->shop_cart->get_sql_results(
				"SELECT DEFAULT_CHARACTER_SET_NAME AS charset FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '"
				. $this->shop_cart->get_db_name() . "'"
			);

			if ( ! $result ) {
				$this->generate_error(
					'Error selecting database charset. | '// Error: {$this->shop_cart->error_no};.
					. $this->shop_cart->error_msg
				);
			}

			$row = array_shift( $result );
			$this->db_file_write( "ALTER DATABASE CHARACTER SET '{$row['charset']}';\nSET NAMES 'utf8';\n\n" );
		}

		$this->shop_cart->exec_sql( 'SET SQL_QUOTE_SHOW_CREATE = 1' );

		// Form database dump file.
		foreach ( $this->db_tables as $table ) {
			if ( $this->dump_data_prev ) {
				if ( $this->dump_data_prev['table'] == $table ) {
					$this->put_log( 'Next attempt of generating dump' );
					$continue = true;
				} elseif ( ! $continue ) {
					$handled_tables[] = $table;
					continue;
				}
			}

			if ( ! $this->dump_data_prev || $this->dump_data_prev['table'] != $table ) {
				$this->put_log( "Handling table `{$table}` [" . $this->get_formated_int_number( $tabinfo[ $table ] ) . '].' );
			}

			$table_empty = true;
			$result      = $this->shop_cart->get_sql_results( "SHOW CREATE TABLE `{$table}`", self::NUMERIC );

			if ( false === $result ) {
				$this->generate_error(
					'Error selecting table structure. | ' // Error: ' . $this->shop_cart->error_no . '; '.
					. $this->shop_cart->error_msg
				);
			}

			$tab = array_shift( $result );
			$tab = preg_replace(
				'/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|collate \w+)/i',
				'/*!40101 \\1 */',
				$tab
			);
			$this->db_file_write( "DROP TABLE IF EXISTS `{$table}`;\n{$tab[1]};\n\n" );

			$numeric_column = array();
			$result         = $this->shop_cart->get_sql_results( "SHOW COLUMNS FROM `{$table}`", self::NUMERIC );

			if ( false === $result ) {
				$this->generate_error(
					'Error selecting table columns. | ' // Error: ' . $this->shop_cart->error_no . '; '.
					. $this->shop_cart->error_msg
				);
			}

			$field = 0;

			foreach ( $result as $col ) {
				$numeric_column[ $field ++ ] = preg_match( '/^(\w*int|year)/', $col[1] ) ? 1 : 0;
			}

			if ( $this->dump_data_prev && $this->dump_data_prev['table'] == $table ) {
				$from = $this->dump_data_prev['from'];
			} else {
				$from = 0;
			}

			$fields = $field;
			$limit  = $tabsize[ $table ];
			$i      = 0;
			$query  = "SELECT * FROM `{$table}` LIMIT {$from}, {$limit}";
			$result = $this->shop_cart->get_sql_results( $query, self::NUMERIC );

			if ( false === $result ) {
				$this->generate_error(
					"Error selecting data from table `{$table}`. | " // Error: " . $this->shop_cart->error_no . '; '.
					. $this->shop_cart->error_msg
				);
			}

			$count_result = count( $result );

			if ( $count_result > 0 ) {
				$this->db_file_write( "INSERT INTO `{$table}` VALUES" );
			}

			while ( $result && $count_result > 0 ) {
				$table_empty = false;
				$this->put_log( '-' . $query );

				foreach ( $result as $row ) {
					$i ++;
					$t ++;

					for ( $k = 0; $k < $fields; $k ++ ) {
						if ( $numeric_column[ $k ] ) {
							$row[ $k ] = isset( $row[ $k ] ) ? $row[ $k ] : 'NULL';
						} else {
							if ( isset( $row[ $k ] ) ) {
								$row[ $k ] = ' ' . self::QOUTE_CHAR . $this->shop_cart->sanitize_sql( $row[ $k ] )
									. self::QOUTE_CHAR . ' ';
							} else {
								$row[ $k ] = 'NULL';
							}
						}
					}

					$row_ex = ',';

					if ( 1 == $i ) {
						$row_ex = '';
					}

					if ( 0 == $i % 500 && $i > 0 ) {
						$this->db_file_write( ";\nINSERT INTO `{$table}` VALUES" );
						$row_ex = '';
					}

					$this->db_file_write( $row_ex . "\n(" . implode( ', ', $row ) . ')' );
				}

				// Set data of generating database dump progress.
				$this->set_create_db_dump_progress( $handled_tables, $table_sizes, $table, $from, $db_size );

				$this->put_dump_data( $table, $from + $limit );

				// Check if generating database dump should be canceled.
				if ( $this->get_generating_dump_value( self::GET_SQL_CANCEL_PARAM ) ) {
					$this->put_log( self::GET_SQL_CANCEL_PARAM );
					$path_sm_tmp_get_sql_txt          = $this->tmp_folder_path . '/' . self::FILE_TMP_GET_SQL;
					$path_dump_data_tmp_txt           = $this->tmp_folder_path . '/' . self::DB_DATA_TMP;
					$path_em1_smconnector_db_dump_sql = $this->tmp_folder_path . '/' . self::DB_FILE_COMPRESSION_NO;

					if ( $this->shop_cart->file_exists( $path_sm_tmp_get_sql_txt ) ) {
						$this->shop_cart->unlink( $path_sm_tmp_get_sql_txt );
					}

					if ( $this->shop_cart->file_exists( $path_dump_data_tmp_txt ) ) {
						$this->shop_cart->unlink( $path_dump_data_tmp_txt );
					}

					if ( $this->shop_cart->file_exists( $path_em1_smconnector_db_dump_sql ) ) {
						$this->db_file_close();
						$this->shop_cart->unlink( $path_em1_smconnector_db_dump_sql );
					}

					die( esc_html( self::GET_SQL_CANCEL_MESSAGE ) );
				}

				// If store manager needs to get part of dump.
				if ( $this->get_generating_dump_value( self::GET_SQL_FILE_PART ) ) {
					$this->db_file_close();
					$this->generate_archive();
					$this->set_generating_dump_value(
						array(
							self::GET_SQL_FILE_PART_NAME => $this->dump_file_current,
							self::GET_SQL_FILE_PART      => 0,
						)
					);
					$this->dump_file_part_number++;
					$this->open_db_file();
				}

				if ( $count_result < $limit ) {
					break;
				}

				$from  += $limit;
				$query  = "SELECT * FROM {$table} LIMIT {$from}, {$limit}";
				$result = $this->shop_cart->get_sql_results( $query, self::NUMERIC );

				if ( false === $result ) {
					$this->generate_error(
						"Error selecting data from table `{$table}`. | " // Error: " . $this->shop_cart->error_no . '; '.
						. $this->shop_cart->error_msg
					);
				}

				$count_result = count( $result );
			}

			// Add table to array of processed tables.
			$handled_tables[] = $table;

			if ( ! $table_empty ) {
				$this->db_file_write( ';' );
			}

			$this->db_file_write( "\n\n" );
		}

		// Close database dump file.
		$this->db_file_close();
		$this->generate_archive();
	}
	/** Generate archive */
	private function generate_archive() {
		if ( $this->smconnector_options['allow_compression'] ) {
			$file_gz       = self::DB_FILE_MAIN . $this->get_part_number( $this->dump_file_part_number )
				. self::DB_FILE_EXT_COMPRESSION_YES;
			$fname_gz_path = $this->tmp_folder_path . "/$file_gz";
			$fp_gz         = $this->shop_cart->gz_file_open( $fname_gz_path, "wb{$this->smconnector_options['compress_level']}" );

			$fname_path = $this->tmp_folder_path . "/$this->dump_file_current";
			$fp         = $this->shop_cart->file_open( $fname_path, 'r' );

			if ( $fp_gz && $fp ) {
				while ( ! feof( $fp ) ) {
					$content = $this->shop_cart->file_read( $fp, $this->smconnector_options['package_size'] );
					$this->shop_cart->gz_file_write( $fp_gz, $content );
				}

				$this->shop_cart->file_close( $fp );
				$this->shop_cart->unlink( $fname_path );
				$this->shop_cart->file_close( $fp_gz );
				$this->dump_file_current = $file_gz;
			}
		}
	}
	/** Get db tables
	 *
	 * @param string $table The table for inserting data.
	 * @param string $from From parameter.
	 */
	private function put_dump_data( $table, $from ) {
		$data = array(
			'table'                            => $table,
			'from'                             => $from,
			self::GET_SQL_FILE_NAME_GENERATING => $this->dump_file_current,
		);

		$this->shop_cart->file_put_contents( $this->tmp_folder_path . '/' . self::DB_DATA_TMP, serialize( $data ) );
	}
	/** Get db tables */
	private function get_tables() {
		$this->put_log( 'Selecting tables' );
		$result = $this->shop_cart->get_sql_results(
			'SHOW FULL TABLES FROM `' . $this->shop_cart->get_db_name() . "` WHERE Table_type = 'BASE TABLE'",
			self::NUMERIC
		);

		if ( false === $result ) {
			$this->generate_error(
				'Error selecting tables. | ' // Error: ' . $this->shop_cart->error_no . '; '.
				. $this->shop_cart->error_msg
			);
		}

		$quoted_tbls = array();
		if ( isset( $this->smconnector_options['exclude_db_tables'][0] ) ) {
			$arr_exclude_db_tables = explode( ';', $this->smconnector_options['exclude_db_tables'] );
			$count                 = count( $arr_exclude_db_tables );

			for ( $i = 0; $i < $count; $i++ ) {
				$arr_exclude_db_tables[ $i ] = preg_quote( $arr_exclude_db_tables[ $i ], '/' );
				$arr_exclude_db_tables[ $i ] = str_replace( '\*', '.*', $arr_exclude_db_tables[ $i ] );
				$arr_exclude_db_tables[ $i ] = str_replace( '\?', '?', $arr_exclude_db_tables[ $i ] );
				$quoted_tbls[]               = "^$arr_exclude_db_tables[$i]$";
			}
		}
		$tables_exclude_pattern = implode( '|', $quoted_tbls );

		$quoted_tbls = array();
		if ( isset( $this->request_params['include_tables'][0] ) ) {
			$arr_include_db_tables = explode( ';', $this->request_params['include_tables'] );
			$count                 = count( $arr_include_db_tables );

			for ( $i = 0; $i < $count; $i++ ) {
				$arr_include_db_tables[ $i ] = preg_quote( $arr_include_db_tables[ $i ], '/' );
				$arr_include_db_tables[ $i ] = str_replace( '\*', '.*', $arr_include_db_tables[ $i ] );
				$arr_include_db_tables[ $i ] = str_replace( '\?', '?', $arr_include_db_tables[ $i ] );
				$quoted_tbls[]               = '^' . $this->shop_cart->get_db_prefix() . "$arr_include_db_tables[$i]$";
			}
			$quoted_tbls[] = '^sm_.*$';
		}
		$tables_include_pattern = implode( '|', $quoted_tbls );

		$tables     = array();
		$inc_tables = 0;
		foreach ( $result as $table ) {
			if ( preg_match( "/$tables_include_pattern/", $table[0] ) ) {
				$inc_tables++;
			}

			$tables[] = $table[0];
		}

		$count = count( $tables );
		for ( $i = 0; $i < $count; $i++ ) {
			if ( ! empty( $tables_exclude_pattern ) && preg_match( "/$tables_exclude_pattern/", $tables[ $i ] ) ) {
				continue;
			}

			if ( preg_match( "/$tables_include_pattern/", $tables[ $i ] ) || 0 == $inc_tables ) {
				$this->db_tables[] = $tables[ $i ];
			}
		}
	}

	/*
	Commented:
		private function wakeServer()
		{
			$curr_time = time();

				Check if it needs to output string
			if ($curr_time - $this->i_curr_time > self::I_TIME_OUT)
			{
				echo self::S_UNIQ_DEL;
				$this->flushBuffers();
				$this->i_curr_time = $curr_time;
				$this->ping_count ++;
			}
		}
	*/

	/*
	Commented:
	private function flushBuffers()
	{
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}
	*/

	/** Write the db file
	 *
	 * @param string $str The file string.
	 */
	private function db_file_write( $str ) {
		if ( $this->smconnector_options['allow_compression'] ) {
			$this->shop_cart->gz_file_write( $this->db_file_handler, $str );
		} else {
			$this->shop_cart->file_write( $this->db_file_handler, $str );
		}
	}
	/** Close the db file */
	private function db_file_close() {
		$this->shop_cart->file_close( $this->db_file_handler );
	}
	/** Set table name and percentage of processed data in session
	 *
	 * @param array  $handled_tables Array of processed tables.
	 * @param array  $table_sizes Information about size of each table.
	 * @param string $handling_table Table name which is being processing at the moment.
	 * @param int    $handled_rows Count of processed rows in table name which is being processing at the moment.
	 * @param int    $db_size Size of all data in database which will be processed.
	 */
	private function set_create_db_dump_progress( $handled_tables, $table_sizes, $handling_table, $handled_rows, $db_size ) {
		$size_handled = 0;

		foreach ( $handled_tables as $table ) {
			$size_handled += $table_sizes[ $table ]['size'];
		}

		if ( $handled_rows >= $table_sizes[ $handling_table ]['rows'] ) {
			$size_handled += $table_sizes[ $handling_table ]['size'];
		} else {
			$size_handled += round(
				$table_sizes[ $handling_table ]['size'] / $table_sizes[ $handling_table ]['rows'] * $handled_rows,
				0
			);
		}

		$percentage = round( $size_handled / $db_size * 100, 0 );
		$this->set_generating_dump_value(
			array(
				self::GET_SQL_TABLE      => $handling_table,
				self::GET_SQL_PERCENTAGE => $percentage,
			)
		);
	}
	/** Form information about database dump file and output it
	 *
	 * @param string $file_name The dump file name.
	 * @param bool   $is_compressed The compression parameter.
	 */
	private function download_dump( $file_name, $is_compressed ) {
		/*
		Commented:
		if ($this->smconnector_options['allow_compression']) {
			$file  = self::DB_FILE_COMPRESSION_YES;
			$fname = $this->tmp_folder_path.'/'.self::DB_FILE_COMPRESSION_YES;
		} else {
			$file  = self::DB_FILE_COMPRESSION_NO;
			$fname = $this->tmp_folder_path.'/'.self::DB_FILE_COMPRESSION_NO;
		}
		*/

		$fname = $this->tmp_folder_path . '/' . $file_name;

		if ( ! $this->shop_cart->file_exists( $fname ) ) {
			$this->put_log( 'File not exists.' );
		}

		if ( ! $this->shop_cart->is_readable( $fname ) ) {
			$this->put_log( 'File is not readable.' );
		}

		$file_size     = $this->shop_cart->file_size( $fname );
		$file_checksum = md5_file( $fname );
		$outpustr      = "0\r\n";

		if ( $is_compressed ) {
			$outpustr .= '1';
		} else {
			$outpustr .= '0';
		}

		$outpustr .= '|';
		$div_last  = $file_size % $this->smconnector_options['package_size'];

		if ( 0 == $div_last ) {
			$outpustr .= ( ( $file_size - $div_last ) / $this->smconnector_options['package_size'] );
		} else {
			$outpustr .= ( ( $file_size - $div_last ) / $this->smconnector_options['package_size'] + 1 );
		}

		$outpustr .= "|$file_size";
		$res       = $this->shop_cart->get_sql_results( 'SELECT @@character_set_database AS charset' );

		if ( ! $res ) {
			$outpustr .= '';
		} else {
			$res       = array_shift( $res );
			$outpustr .= '|' . $res['charset'];
		}

		$outpustr .= "\r\n$file_name\r\n$file_checksum\r\n";

		if ( ! headers_sent() ) {
			header( 'Content-Length: ' . $this->shop_cart->str_len( $outpustr ) );
			header( 'Content-Length-Alternative: ' . $this->shop_cart->str_len( $outpustr ) );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Pragma: no-cache' );
		}

		echo esc_html( $outpustr );
	}
	/** Read database dump file and output its data by parts */
	private function get_db_file() {
		if ( ! $this->shop_cart->isset_request_param( 'filename' ) ) {
			$this->generate_error( $this->br_errors['filename_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'position' ) ) {
			$this->generate_error( $this->br_errors['position_param_missing'] );
		}

		$filename = (string) $this->tmp_folder_path . '/' . $this->shop_cart->get_request_param( 'filename' );
		$position = (int) $this->shop_cart->get_request_param( 'position' );

		if ( ! $this->shop_cart->file_exists( $filename ) ) {
			$this->generate_error( $this->br_errors['temporary_file_exist_not'] );
		}

		if ( ! $this->shop_cart->is_readable( $filename ) ) {
			$this->generate_error( $this->br_errors['temporary_file_readable_not'] );
		}

		$outpustr       = '';
		$package_size   = $this->smconnector_options['package_size'];
		$filesize       = $this->shop_cart->file_size( $filename );
		$filesize       = $filesize - $position * $package_size;
		$delete_db_file = false;

		if ( $filesize > $package_size ) {
			$filesize = $package_size;
		}

		if ( $filesize < 0 ) {
			$filesize = 0;
		}

		if ( $filesize < $package_size ) {
			$delete_db_file = true;
		}

		if ( ! headers_sent() ) {
			header( 'Content-Length: ' . ( $this->shop_cart->str_len( $outpustr ) + $filesize ) );
			header( 'Content-Length-Alternative: ' . ( $this->shop_cart->str_len( $outpustr ) + $filesize ) );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Pragma: no-cache' );
		}

		echo esc_html( $outpustr );

		$fp = $this->shop_cart->file_open( $filename, 'rb' );
		fseek( $fp, $package_size * $position );
		$read_size = (int) self::FILE_READ_SIZE;

		while ( ( $read_size > 0 ) && ( $package_size > 0 ) ) {
			if ( $package_size >= $read_size ) {
				$package_size -= $read_size;
			} else {
				$read_size    = $package_size;
				$package_size = 0;
			}

			if ( 0 == $read_size ) {
				break;
			}

			echo $this->shop_cart->file_read( $fp, (int) $read_size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

		$this->shop_cart->file_close( $fp );

		if ( $delete_db_file ) {
			$this->shop_cart->unlink( $filename );
			$this->shop_cart->unlink( $this->tmp_folder_path . '/' . self::DB_DATA_TMP );
		}
	}
	/** Check data and run SQLs */
	private function put_sql() {
		if ( ! $this->shop_cart->isset_request_param( 'sql' ) ) {
			$this->generate_error( $this->br_errors['sql_param_missing'] );
		}

		$this->put_log( 'Put sql. Start' );
		$sql = $this->shop_cart->get_request_param( 'sql' );

		// Put all SQLs into log file.
		$this->put_log( $sql );

		// Run SQLs.
		$content = $this->put_sql_run( $sql );

		// Output answer.
		if ( $this->shop_cart->str_len( $content ) > 0 ) {
			echo wp_kses( $content, 'entities' );
		} else {
			echo "0\r\n";
		}
	}
	/** Put sql run
	 *
	 * @param string $data The sql data.
	 */
	private function put_sql_run( $data ) {
		$ret                       = '';
		$checksum_prev             = '';
		$this->count_sql_exec_prev = 0;
		$post_replace_to_sm        = array_flip( $this->post_replace_from_sm );
		$sql_delimiter_pv          = $this->shop_cart->get_request_param( 'sql_delimiter' );
		$sql_compatibility_pv      = $this->shop_cart->get_request_param( 'sql_compatibility' );

		// Read checksum and count of processed SQLs from file.
		$checksum_arr = $this->get_checksum_prev();

		// Get encoded string in base64 to check below if data are encoded in base64.
		$encoded_data_begin = strtr( call_user_func( 'base64_encode', self::PUT_SQL_ENCODED ), $post_replace_to_sm );

		if ( $checksum_arr ) {
			$checksum_prev             = $checksum_arr[0];
			$this->count_sql_exec_prev = $checksum_arr[1];
		}

		if ( false !== $sql_delimiter_pv && ! empty( $sql_delimiter_pv ) ) {
			$this->sql_delimiter = (string) $this->shop_cart->get_request_param( 'sql_delimiter' );
		}

		if ( false !== $sql_compatibility_pv && ! empty( $sql_compatibility_pv ) ) {
			$this->shop_cart->exec_sql( "SET SQL_MODE = '" . (string) $sql_compatibility_pv . "'" );
		}

		$this->shop_cart->exec_sql( 'SET SQL_BIG_SELECTS=1;' );

		$checksum_current = str_pad( $this->shop_cart->str_to_upper( dechex( crc32( $data ) ) ), 8, '0', STR_PAD_LEFT );

		// Check if chunk checksum from the store manager and checksum of retrieved data are different.
		if ( $this->shop_cart->isset_request_param( 'checksum' )
			&& $this->shop_cart->get_request_param( 'checksum' ) != $checksum_current
		) {
			return self::POST_ERROR_CHUNK_CHECKSUM_DIF . '|' . $this->br_errors['checksum_dif'];
		}

		if ( $this->shop_cart->isset_request_param( 'checksum' ) ) {
			if ( strpos( $data, $encoded_data_begin ) === 0 ) {
				$this->shop_cart->set_sql_encoded_in_base64( true );
				$data = $this->shop_cart->sub_str( $data, $this->shop_cart->str_len( $encoded_data_begin ) );
				$data = call_user_func( 'base64_decode', strtr( $data, $this->post_replace_from_sm ) );
			}
		}

		$sql_queries = explode( $this->sql_delimiter, $data );

		if ( $this->shop_cart->isset_request_param( 'checksum' ) ) {
			if ( $checksum_current != $checksum_prev ) {
				foreach ( $sql_queries as $query ) {
					$query = trim( $query );

					if ( ! empty( $query ) ) {
						if ( '' == $ret ) {
							$ret .= $this->import_run_query( $query, $checksum_current );
						}
					} else {
						break;
					}
				}
			} else {
				foreach ( $sql_queries as $key => $query ) {
					$query = trim( $query );

					if ( ! empty( $query ) && '' == $ret && $key >= $this->count_sql_exec_prev ) {
						$ret .= $this->import_run_query( $query, $checksum_current );
					}
				}
			}
		} else {
			foreach ( $sql_queries as $query ) {
				$query = trim( $query );

				if ( ! empty( $query ) ) {
					$ret .= $this->import_run_query( $query );
				}
			}
		}

		if ( '' == $ret && $this->shop_cart->isset_request_param( 'checksum' ) ) {
			$ret = $this->successful_code . '|Data were posted successfully';
		}

		return $ret;
	}
	/** Get checksum preview */
	private function get_checksum_prev() {
		$checksum_arr           = false;
		$file_name_intermediate = $this->tmp_folder_path . '/' . self::INTERMEDIATE_FILE_NAME;

		if ( $this->shop_cart->file_exists( $file_name_intermediate ) ) {
			$fp = $this->shop_cart->file_open( $file_name_intermediate, 'r' );

			if ( $fp ) {
				$file_size_intermediate = $this->shop_cart->file_size( $file_name_intermediate );

				if ( $file_size_intermediate > 0 ) {
					$content      = $this->shop_cart->file_read( $fp, $file_size_intermediate );
					$checksum_arr = explode( '|', $content );

					if ( count( $checksum_arr ) == 2 ) {
						$checksum_arr[0] = (string) $checksum_arr[0];
						$checksum_arr[1] = (int) $checksum_arr[1];

						if ( $checksum_arr[1] < 0 ) {
							$checksum_arr[1] = 0;
						}
					} else {
						$checksum_arr = false;
					}
				}

				$this->shop_cart->file_close( $fp );
			}
		}

		return $checksum_arr;
	}
	/** Run one SQL and put data into file
	 *
	 * @param string $query The query to put data.
	 * @param string $checksum The additional parameter.
	 */
	private function import_run_query( $query, $checksum = '' ) {
		$ret = '';
		$this->put_log( $query );
		$result = $this->shop_cart->exec_sql( $query );

		// Error Code: 2006 - MySQL server has gone away; Error Code: 1317 - Query execution was interrupted.
		// WordPress codding standards do not allow use error_no.
		if ( ! $result /*&& ( 2006 == $this->shop_cart->error_no ||  1317 == $this->shop_cart->error_no )*/ ) {
			$result = $this->retry_put_sql( $query );
		}

		if ( $result ) {
			if ( $this->shop_cart->isset_request_param( 'checksum' ) ) {
				$this->shop_cart->file_put_contents(
					$this->tmp_folder_path . '/' . self::INTERMEDIATE_FILE_NAME,
					$checksum . '|' . ( ++$this->count_sql_exec_prev )
				);
			}
		} else {
			$ret .= self::POST_ERROR_SQL_INDEX . '|' . ( $this->count_sql_exec_prev + 1 ) . '|<font color="#000000"><b>'
				/*. $this->shop_cart->error_no . '; '*/ . $this->shop_cart->error_msg
				. '</b></font><br>' . htmlspecialchars( $query, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 ) . '<br>';

			if ( $this->shop_cart->isset_request_param( 'checksum' ) ) {
				$this->shop_cart->file_put_contents(
					$this->tmp_folder_path . '/' . self::INTERMEDIATE_FILE_NAME,
					$checksum . '|' . $this->count_sql_exec_prev
				);
			}
		}

		return $ret;
	}
	/** Retry to put sql
	 *
	 * @param string $query The query to put data.
	 */
	private function retry_put_sql( $query ) {
		$result = false;

		for ( $i = 0; $i < self::MAX_COUNT_ATTEMPT_POST; $i ++ ) {
			sleep( self::DELAY_BETWEEN_POST );
			$result = $this->shop_cart->exec_sql( $query, true );

			if ( $result /*|| ( 2006 != $this->shop_cart->error_no &&  1317 != $this->shop_cart->error_no )*/ ) {
				break;
			}
		}

		return $result;
	}
	/** Get module version */
	private function get_module_version() {
		die(
			json_encode(
				array(
					$this->code_response => $this->successful_code,
					'revision'           => $this->revision,
					'module_version'     => $this->module_version,
				)
			)
		);
	}
	/** Get config */
	private function get_config() {
		echo "0\r\n";
		echo 'database_host=' . esc_html( $this->shop_cart->get_db_host() ) . "<br>\r\n";
		echo 'database_name=' . esc_html( $this->shop_cart->get_db_name() ) . "<br>\r\n";
		echo 'database_username=' . esc_html( $this->shop_cart->get_db_username() ) . "<br>\r\n";
		echo 'database_password=' . esc_html( $this->shop_cart->get_db_password() ) . "<br>\r\n";
		echo 'database_table_prefix=' . esc_html( $this->shop_cart->get_db_prefix() ) . "<br>\r\n";
		echo 'php_version=' . esc_html( phpversion() ) . "<br>\r\n";
		echo 'gzip=' . (int) extension_loaded( 'zlib' ) . "<br>\r\n";

		if ( defined( 'VM_VERSION' ) ) {
			echo 'vm_version=' . esc_html( VM_VERSION ) . "<br>\r\n";
		}
	}
	/** Get category tree */
	private function get_category_tree() {
		if ( ! $this->shop_cart->isset_request_param( 'category' ) ) {
			$this->put_log( 'Error: Category name is missing' );
			$this->generate_error( $this->br_errors['category_param_missing'] );
		}

		$category = $this->shop_cart->get_request_param( 'category' );
		if ( empty( $category ) ) {
			$this->put_log( 'Error: Category name is empty' );
			$this->generate_error( $this->br_errors['category_param_empty'] );
		}

		$dir = (string) $this->shop_cart->get_request_param( 'category' );
		if ( empty( $dir ) ) {
			$this->put_log( 'Error: Category name is empty' );
			$this->generate_error( $this->br_errors['category_param_missing'] );
		}

		$tmp_dir_info = dir( $this->tmp_folder_path );

		while ( false !== ( $entry = $tmp_dir_info->read() ) ) {
			if ( '.' != $entry
				&& '..' != $entry
				&& $this->shop_cart->sub_str(
					$entry,
					0,
					$this->shop_cart->str_len( self::TMP_FILE_PREFIX )
				) == self::TMP_FILE_PREFIX
			) {
				$this->shop_cart->unlink( $this->tmp_folder_path . '/' . $entry );
			}
		}

		$tmpfname = $this->shop_cart->str_to_lower( self::TMP_FILE_PREFIX . gmdate( 'H_i_s-d_M_Y' ) );
		$this->put_log( 'Creating and opening tmp file for get category path' );

		if ( $this->smconnector_options['allow_compression'] ) {
			$tmpfname .= '.txt.gz';
			$tmpfd     = $this->shop_cart->gz_file_open(
				$this->tmp_folder_path . '/' . $tmpfname,
				'wb' . $this->smconnector_options['compress_level']
			);
		} else {
			$tmpfname .= '.txt';
			$tmpfd     = $this->shop_cart->file_open( $this->tmp_folder_path . '/' . $tmpfname, 'wb' );
		}

		if ( ! $tmpfd ) {
			$this->put_log( 'Error creating and opening tmp file' );
			$this->generate_error( $this->br_errors['open_tmp_file'] );
		}

		$files = $this->shop_cart->get_files_recursively(
			$this->shop_cart->get_shop_root_dir() . ltrim( $dir, '/' ),
			$this->shop_cart->get_ignore_dirs(),
			'*',
			true
		);

		foreach ( $files as $file ) {
			$this->smconnector_options['allow_compression']
				? $this->shop_cart->gz_file_write( $tmpfd, "$file\r\n" )
				: $this->shop_cart->file_write( $tmpfd, "$file\r\n" );
		}

		$this->smconnector_options['allow_compression']
			? $this->shop_cart->gz_file_close( $tmpfd )
			: $this->shop_cart->file_close( $tmpfd );

		die(
			esc_html(
				$this->generate_file_data(
					$this->tmp_folder_path . '/' . $tmpfname,
					$this->smconnector_options['allow_compression']
				)
			)
		);
	}
	/** Run indexer */
	private function run_indexer() {
		$this->shop_cart->run_indexer();
		die();
	}
	/** Get values of variables from FTP files */
	private function get_vars() {
		if ( ! $this->shop_cart->isset_request_param( 'vars_main_dir' ) ) {
			$this->generate_error( $this->br_errors['varsmaindir_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'vars_names' ) ) {
			$this->generate_error( $this->br_errors['varsnames_param_missing'] );
		}

		$translations  = array();
		$vars_main_dir = (string) $this->shop_cart->get_request_param( 'vars_main_dir' );
		$vars_main_dir = trim( '\\', trim( '/', $vars_main_dir ) );
		$vars_main_dir = $this->shop_cart->get_shop_root_dir() . '/' . $vars_main_dir;
		$vars_names    = (string) $this->shop_cart->get_request_param( 'vars_names' );

		if ( '' == $vars_main_dir ) {
			$this->generate_error( $this->br_errors['varsmaindir_param_empty'] );
		}

		if ( '' == $vars_names ) {
			$this->generate_error( $this->br_errors['varsnames_param_empty'] );
		}

		$item_handler = opendir( $vars_main_dir );

		while ( ( $item = $this->shop_cart->read_directory( $item_handler ) ) !== false ) {
			if ( $this->shop_cart->sub_str( $item, 0, 1 ) != '.' && ! $this->shop_cart->is_directory( $item ) ) {
				$translations[ (string) $item ] = $this->get_vars_from_script( $vars_main_dir . '/' . $item, $vars_names );
			}
		}

		echo '1|' . json_encode( $translations );
	}
	/** Get values of variables from FTP file
	 *
	 * @param string $path_to_script The path to script.
	 * @param array  $vars_names The array of variables names.
	 */
	private function get_vars_from_script( $path_to_script, $vars_names ) {
		if ( $this->shop_cart->file_exists( './' . $path_to_script )
			&& $this->shop_cart->is_readable( './' . $path_to_script )
			&& $this->shop_cart->file_size( './' . $path_to_script ) > 0
		) {
			$current_translations = array();
			$content              = call_user_func( 'file_get_contents', './' . $path_to_script );

			if ( ! $content ) {
				$this->generate_error( 'Cannot open file: ' . $path_to_script );
			}

			$rows    = explode( "\n", $content );
			$pattern = '/^\$\_\[\'(.*)\']\s*\=\s*(.*)\;/i';

			foreach ( $rows as $data ) {
				preg_match( $pattern, $data, $matches );

				if ( in_array( $matches[1], $vars_names ) && isset( $matches[2] ) && null != $matches[2] ) {
					$current_translations[ $matches[1] ] = $matches[2];
				}
			}

			return $current_translations;
		}

		return '';
	}
	/** Get data from .xml file on FTP server */
	private function get_xml_data() {
		if ( ! $this->shop_cart->isset_request_param( 'xml_path' ) ) {
			$this->generate_error( $this->br_errors['xmlpath_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'xml_fields' ) ) {
			$this->generate_error( $this->br_errors['xmlfields_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'xml_items_node' ) ) {
			$this->generate_error( $this->br_errors['xmlitemsnode_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'xml_items_info_node' ) ) {
			$this->generate_error( $this->br_errors['xmlitemsinfonode_param_missing'] );
		}

		$xml_path            = (string) $this->shop_cart->get_request_param( 'xml_path' );
		$xml_fields          = (string) $this->shop_cart->get_request_param( 'xml_fields' );
		$xml_items_node      = (string) $this->shop_cart->get_request_param( 'xml_items_node' );
		$xml_items_info_node = (string) $this->shop_cart->get_request_param( 'xml_items_info_node' );
		$xml_filters         = array();
		$xml_filters_pv      = $this->shop_cart->get_request_param( 'xml_filters' );

		if ( '' == $xml_path ) {
			$this->generate_error( $this->br_errors['xmlpath_param_empty'] );
		}

		if ( '' == $xml_fields ) {
			$this->generate_error( $this->br_errors['xmlfields_param_empty'] );
		}

		if ( '' == $xml_items_node ) {
			$this->generate_error( $this->br_errors['xmlitemsnode_param_empty'] );
		}

		if ( '' == $xml_items_info_node ) {
			$this->generate_error( $this->br_errors['xmlitemsinfonode_param_empty'] );
		}

		if ( false !== $xml_filters_pv && ! empty( $xml_filters_pv ) ) {
			$xml_filters = explode( ';', (string) $xml_filters_pv );
		}

		$path_xml_file = $this->shop_cart->get_shop_root_dir() . '/' . $xml_path;

		if ( ! $this->shop_cart->file_exists( $path_xml_file ) ) {
			$this->generate_error( "File {$xml_path} not found!" );
		}

		$this->get_items_list( $path_xml_file, $xml_fields, $xml_items_node, $xml_items_info_node, $xml_filters );
	}
	/** Get value of nodes from .xml file
	 *
	 * @param string $path_xml_file The xml file path.
	 * @param array  $fields The array of xml fields.
	 * @param string $items_node Items node.
	 * @param string $items_info_node Items info node.
	 * @param string $filters Filters.
	 */
	private function get_items_list( $path_xml_file, $fields, $items_node, $items_info_node, $filters ) {
		$items_list      = array();
		$filters_matched = array();
		$xml             = simplexml_load_file( $path_xml_file );

		foreach ( $filters as $filter ) {
			preg_match( '/^(.*)\:(.*)$/', $filter, $matches );
			$filters_matched[ $matches[1] ] = $matches[2];
		}

		$count_filters_matched = count( $filters_matched );
		$fields                = explode( ',', $fields );
		$items                 = $xml->xpath( "{$items_node}" );
		$items_keys            = array_keys( $items );

		foreach ( $items_keys as $item_name ) {
			if ( $items_node != $items_info_node ) {
				$items_info = $xml->xpath( "{$items_info_node}/{$item_name}" );
				$items_info = $items_info[0];
			} else {
				$items_info = $item_name;
			}

			if ( $count_filters_matched > 0 ) {
				foreach ( $filters_matched as $filter_name => $filter_value ) {
					if ( (string) $items_info->$filter_name != $filter_value ) {
						continue 2;
					}
				}
			}

			foreach ( $fields as $field ) {
				$items_list[ $item_name ][ $field ] = (string) $items_info->$field;
			}
		}

		echo '1|' . json_encode( $items_list ) . "\r\n";

	}
	/** Get file structure from FTP server by path */
	private function get_ftp_files() {
		if ( ! $this->shop_cart->isset_request_param( 'search_path' ) ) {
			$this->generate_error( $this->br_errors['searchpath_param_missing'] );
		}

		$path = (string) $this->shop_cart->get_request_param( 'search_path' );
		$mask = '*';

		if ( '' == $path ) {
			$this->generate_error( $this->br_errors['searchpath_param_empty'] );
		}

		if ( $this->shop_cart->isset_request_param( 'mask' ) ) {
			$mask = (string) $this->shop_cart->get_request_param( 'mask' );

			if ( empty( $mask ) ) {
				$mask = '*';
			}
		}

		$include_subdir = $this->shop_cart->isset_request_param( 'include_subdir' )
			&& (int) $this->shop_cart->get_request_param( 'include_subdir' ) == 1;

		$files = $this->shop_cart->get_files_recursively(
			$this->shop_cart->get_shop_root_dir() . ltrim( $path, '/' ),
			$this->shop_cart->get_ignore_dirs(),
			$mask,
			$include_subdir
		);

		echo json_encode(
			array(
				$this->shop_cart->get_code_response() => $this->shop_cart->get_code_successful(),
				$this->shop_cart->get_key_message()   => $files,
			)
		);
	}
	/** Get cart version */
	private function get_cart_version() {
		echo esc_textarea( $this->shop_cart->get_cart_version() );
	}
	/** Check data changes */
	private function check_data_changes() {
		if ( ! $this->shop_cart->isset_request_param( 'table_name' ) ) {
			$this->generate_error( $this->br_errors['tablename_param_missing'] );
		}

		$table_name = (string) $this->shop_cart->get_request_param( 'table_name' );

		if ( empty( $table_name ) ) {
			$this->generate_error( $this->br_errors['tablename_param_empty'] );
		}

		echo wp_kses( $this->shop_cart->check_data_changes( explode( ';', call_user_func( 'base64_decode', $table_name ) ) ), 'entities' );
	}
	/** Get new orders */
	private function get_new_orders() {
		if ( ! $this->shop_cart->isset_request_param( 'order_id' ) ) {
			$this->generate_error( $this->br_errors['orderid_param_missing'] );
		}

		$order_id = (int) $this->shop_cart->get_request_param( 'order_id' );

		if ( $order_id < 1 ) {
			$this->generate_error( $this->br_errors['orderid_param_incorrect'] );
		}

		echo wp_kses( $this->shop_cart->get_new_orders( $order_id ), 'entities' );
	}
	/** Set value in session to cancel of generating database dump */
	private function create_db_dump_cancel() {
		$this->set_generating_dump_value( array( self::GET_SQL_CANCEL_PARAM => 1 ) );
	}
	/** Get information about state of generating database dump */
	private function create_db_dump_progress() {
		$ret = array();
		$str = '0|';

		$ret['table']      = $this->get_generating_dump_value( self::GET_SQL_TABLE );
		$ret['percentage'] = $this->get_generating_dump_value( self::GET_SQL_PERCENTAGE );

		if ( false !== $ret['table'] && false !== $ret['percentage'] ) {
			$str = '1|' . json_encode( $ret );
		}

		echo wp_kses( $str, 'entities' );
	}
	/** Get sql file part data */
	private function get_sql_file_part_info() {
		$this->set_generating_dump_value( array( self::GET_SQL_FILE_PART => 1 ) );

		for ( $i = 0; $i < 10; $i++ ) {
			sleep( 10 );
			$file_part = $this->get_generating_dump_value( self::GET_SQL_FILE_PART_NAME );

			if ( $file_part && ! empty( $file_part ) ) {
				$is_compressed = false;

				if ( preg_match( '/.gz$/', $file_part ) ) {
					$is_compressed = true;
				}

				$this->download_dump( $file_part, $is_compressed );
				$this->set_generating_dump_value( array( self::GET_SQL_FILE_PART_NAME => '' ) );
				die();
			}
		}

		die( 'Cannot give a file' );
	}
	/** Get image */
	private function get_image() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'image_id' ) ) {
			$this->generate_error( $this->br_errors['imageid_param_missing'] );
		}

		$entity_type = (string) $this->shop_cart->get_request_param( 'entity_type' );
		$image_id    = (string) $this->shop_cart->get_request_param( 'image_id' );

		if ( ! isset( $entity_type[0] ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		if ( empty( $image_id ) ) {
			$this->generate_error( $this->br_errors['imageid_param_incorrect'] );
		}

		$image_path = $this->shop_cart->get_image( $entity_type, $image_id );

		if ( $image_path && $this->shop_cart->file_exists( $image_path ) ) {
			header( 'Content-Type: image/jpeg' );
			header( 'Content-Length: ' . $this->shop_cart->file_size( $image_path ) );
			readfile( $image_path );
		} else {
			$this->generate_error( 'Image is missing' );
		}
	}
	/** Upload image */
	private function set_image() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'image_id' ) ) {
			$this->generate_error( $this->br_errors['imageid_param_missing'] );
		}

		$entity_type = (string) $this->shop_cart->get_request_param( 'entity_type' );
		$image_id    = (string) $this->shop_cart->get_request_param( 'image_id' );

		if ( ! isset( $entity_type[0] ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		if ( empty( $image_id ) ) {
			$this->generate_error( $this->br_errors['imageid_param_incorrect'] );
		}

		if ( $this->shop_cart->isset_request_param( 'image_url' ) ) {
			$image_url = (string) $this->shop_cart->get_request_param( 'image_url' );

			if ( empty( $image_url ) ) {
				$this->generate_error( $this->br_errors['imageurl_param_empty'] );
			}

			$this->shop_cart->set_image( $entity_type, $image_id, $image_url, $this->image_url );
		} else {
			if ( ! $this->shop_cart->set_image( $entity_type, $image_id, self::UPLOAD_FILE_NAME, self::UPLOAD_FILE_NAME ) ) {
				$this->generate_error( $this->br_errors['upload_file_error'] );
			}
		}
	}
	/** Delete image */
	private function delete_image() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'image_id' ) ) {
			$this->generate_error( $this->br_errors['imageid_param_missing'] );
		}

		$entity_type = (string) $this->shop_cart->get_request_param( 'entity_type' );

		if ( ! isset( $entity_type[0] ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		$this->shop_cart->delete_image( $entity_type, $this->shop_cart->get_request_param( 'image_id' ) );
	}
	/** Delete file */
	private function delete_file() {
		if ( ! $this->shop_cart->isset_request_param( 'path' ) ) {
			$this->generate_error( $this->br_errors['path_param_missing'] );
		}

		$filepath = (string) $this->shop_cart->get_request_param( 'path' );

		if ( empty( $filepath ) ) {
			$this->generate_error( $this->br_errors['path_param_empty'] );
		}

		$filepath = $this->shop_cart->get_shop_root_dir() . '/' . $filepath;

		if ( ! $this->shop_cart->file_exists( $filepath ) ) {
			$this->generate_error( $this->br_errors['delete_file_error'] );
		}

		$this->shop_cart->delete_file( $filepath );
	}
	/** Copy image */
	private function copy_image() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'image_id' ) ) {
			$this->generate_error( $this->br_errors['imageid_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'to_image_id' ) ) {
			$this->generate_error( $this->br_errors['toimageid_param_missing'] );
		}

		$entity_type   = (string) $this->shop_cart->get_request_param( 'entity_type' );
		$from_image_id = (int) $this->shop_cart->get_request_param( 'image_id' );
		$to_image_id   = (int) $this->shop_cart->get_request_param( 'to_image_id' );

		if ( ! isset( $entity_type[0] ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		if ( $from_image_id < 1 ) {
			$this->generate_error( $this->br_errors['imageid_param_incorrect'] );
		}

		if ( $to_image_id < 1 ) {
			$this->generate_error( $this->br_errors['toimageid_param_incorrect'] );
		}

		$this->shop_cart->copy_image( $entity_type, $from_image_id, $to_image_id );
	}
	/** Get file */
	private function get_file() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'filename' ) ) {
			$this->generate_error( $this->br_errors['filename_param_missing'] );
		}

		$entity_type = (string) $this->shop_cart->get_request_param( 'entity_type' );
		$filename    = (string) $this->shop_cart->get_request_param( 'filename' );

		if ( empty( $entity_type ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		if ( empty( $filename ) ) {
			$this->generate_error( $this->br_errors['filename_param_empty'] );
		}

		$file_path = $this->shop_cart->get_file( $entity_type, $filename );

		if ( $file_path && $this->shop_cart->file_exists( $file_path ) ) {
			header( 'Content-Type: image/jpeg' );
			header( 'Content-Length: ' . $this->shop_cart->file_size( $file_path ) );
			readfile( $file_path );
		} else {
			$this->generate_error( 'File is missing' );
		}
	}
	/** Upload file */
	private function set_file() {
		if ( ! $this->shop_cart->isset_request_param( 'entity_type' ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_missing'] );
		}

		if ( ! $this->shop_cart->isset_request_param( 'filename' ) ) {
			$this->generate_error( $this->br_errors['filename_param_missing'] );
		}

		$entity_type = (string) $this->shop_cart->get_request_param( 'entity_type' );
		$filename    = (string) $this->shop_cart->get_request_param( 'filename' );

		if ( empty( $entity_type ) ) {
			$this->generate_error( $this->br_errors['entitytype_param_empty'] );
		}

		if ( empty( $filename ) ) {
			$this->generate_error( $this->br_errors['filename_param_empty'] );
		}

		if ( ! $this->shop_cart->set_file( $entity_type, $filename, self::UPLOAD_FILE_NAME ) ) {
			$this->generate_error( $this->br_errors['upload_file_error'] );
		}
	}
	/** Get files details */
	private function get_files_details() {
		$data = $this->get_request_content();
		if ( empty( $data ) && ! array_key_exists( 'files', $data ) && ! array_key_exists( 'details', $data ) ) {
			$this->generate_error(
				$this->br_errors['missing_files_object_in_json']
			);
		}

		$files_details  = array();
		$files         = $data['files'];
		$details       = $data['details'];
		$filter_counter = 0;
		foreach ( $details as $key => $value ) {
			if ( false === $value ) {
				$filter_counter++;
			}
		}

		if ( count( $details ) == $filter_counter ) {
			self::generate_response( $files_details );
		}

		foreach ( $files as $file ) {
			$file_information = array();
			if ( ! array_key_exists( 'path', $file ) ) {
				$this->generate_error(
					$this->br_errors['incorrect_body_format_of_files_objects']
				);
				break;
			}

			$file_path                = (string) $this->shop_cart->get_file( '', (string) $file['path'] );
			$file_information['path'] = (string) $file['path'];
			$file_exists              = ( $file_path && $this->shop_cart->file_exists( $file_path ) );
			if ( array_key_exists( 'size', $details ) && (bool) $details['size'] && $file_exists ) {
				$file_information['size'] = (int) $this->shop_cart->file_size( $file_path );
			} elseif ( array_key_exists( 'size', $details ) && (bool) $details['size'] ) {
				$file_information['size'] = 0;
			}

			if ( array_key_exists( 'type', $details ) && (bool) $details['type'] && $file_exists ) {
				$file_information['type'] = (string) @filetype( $file_path );
			} elseif ( array_key_exists( 'type', $details ) && (bool) $details['type'] ) {
				$file_information['type'] = '';
			}

			if ( array_key_exists( 'permissions', $details ) && (bool) $details['permissions'] && $file_exists ) {
				$file_information['permissions'] = (int) fileperms( $file_path );
			} elseif ( array_key_exists( 'permissions', $details ) && (bool) $details['permissions'] ) {
				$file_information['permissions'] = 0;
			}

			$files_details[] = $file_information;
		}

		self::generate_response( $files_details );
	}
	/** Get cache */
	private function get_cache() {
		echo wp_kses( $this->shop_cart->get_cache(), 'entities' );
	}
	/** Clear cache */
	private function clear_cache() {
		$this->shop_cart->clear_cache();
	}
	 /** Generate response
	  *
	  * @param   array $data       Prepared data.
	  * @param   int   $code       Response code. If everything is ok self::RESPONSE_CODE_SUCCESS.
	  * @return  void    -           Returns response data.
	  */
	public static function generate_response( $data = array(), $code = 20 ) {
		self::response_handler(
			array_merge(
				array( 'response_code' => (int) $code ),
				array( 'message' => $data )
			)
		);
	}
	/** Generate response handler
	 *
	 * @param string $response The response for Store Manager.
	 */
	private static function response_handler( $response ) {
		header( 'Content-Type: application/json;' );
		echo wp_kses( json_encode( $response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ), 'entities' );
		die();
	}
	/** Get request content */
	private function get_request_content() {
		$json_input = json_decode( $this->shop_cart->file_get_contents( 'php://input' ), true );
		if ( ! is_array( $json_input ) && $this->shop_cart->isset_request_param( 'data' ) ) {
			$json_input = json_decode( (string) $this->shop_cart->get_request_param( 'data' ), true );
		}

		if ( ! is_array( $json_input ) ) {
			$this->generate_error(
				$this->br_errors['json_data_incorrect']
			);
		}

		return (array) $json_input;
	}
	/** Get payment and shipping methods for POS */
	private function get_payment_and_shipping_methods() {
		$this->shop_cart->get_payment_and_shipping_methods();
	}
	/** Get store file archive */
	private function get_store_file_archive() {
		if ( version_compare( phpversion(), '5.2.0', '<' ) ) {
			$this->generate_error(
				$this->br_errors['zip_archive_not_supported'],
				$this->error_generate_store_file_archive
			);
		}

		if ( ! extension_loaded( 'zip' ) ) {
			$this->generate_error( $this->br_errors['zip_not_loaded'] );
		}

		if ( ! $this->shop_cart->is_writable( $this->tmp_folder_path ) ) {
			$this->generate_error( $this->br_errors['not_writeable_dir'] );
		}

		$result         = false;
		$arr_ignore_dir = array();
		$file           = "$this->tmp_folder_path/emagicone_store.zip";

		if ( $this->shop_cart->isset_request_param( 'ignore_dir' ) ) {
			$ignore_dir = $this->shop_cart->get_request_param( 'ignore_dir' );

			if ( ! empty( $ignore_dir ) ) {
				$arr_ignore_dir = explode( ';', $ignore_dir );
			}
		}

		if ( $this->shop_cart->file_exists( $file ) ) {
			$this->shop_cart->unlink( $file );
		}

		$zip_obj = $this->shop_cart->get_zip_archive_instance();

		if ( $zip_obj->open( $file, $this->shop_cart->get_zip_archive_create_value() ) === true ) {
			$store_root_dir = $this->shop_cart->get_shop_root_dir();
			$this->generate_file_archive(
				$zip_obj,
				$store_root_dir,
				$this->shop_cart->str_len( $store_root_dir ),
				$arr_ignore_dir
			);
			$zip_obj->close();
			$result = $this->generate_file_data( $file, true );
		}

		if ( ! $result ) {
			$this->generate_error( $this->br_errors['cannot_archive_files'], $this->error_generate_store_file_archive );
		}

		die( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	/** Generate file archive
	 *
	 * @param object $zip_obj The archive object.
	 * @param bool   $path The path to archive.
	 * @param int    $store_root_dir_length The length of store root dir.
	 * @param array  $arr_ignore_dir The list of dirs to ignore.
	 */
	private function generate_file_archive( $zip_obj, $path, $store_root_dir_length, $arr_ignore_dir ) {
		$skip = array( '.', '..' );
		$fp   = opendir( $path );

		if ( $fp ) {
			while ( false !== ( $value = $this->shop_cart->read_directory( $fp ) ) ) {
				$item = "$path/$value";

				if ( $this->shop_cart->is_file( $item ) ) {
					$zip_obj->addFile( $item, $this->shop_cart->sub_str( $item, $store_root_dir_length ) );
				} elseif ( $this->shop_cart->is_directory( $item )
					&& ! in_array( $value, $skip )
					&& ! in_array( $this->shop_cart->sub_str( $item, $store_root_dir_length ), $arr_ignore_dir )
				) {
					$this->generate_file_archive( $zip_obj, $item, $store_root_dir_length, $arr_ignore_dir );
				}
			}

			closedir( $fp );
		}
	}
	/** Generate file data
	 *
	 * @param string $file The file.
	 * @param bool   $allow_compression The compression status parameter.
	 */
	private function generate_file_data( $file, $allow_compression ) {
		$file_size    = $this->shop_cart->file_size( $file );
		$output       = "0\r\n" . ( $allow_compression ? '1' : '0' ) . '|';
		$div_last     = $file_size % $this->smconnector_options['package_size'];
		0 == $output .= $div_last
			? ( $file_size / $this->smconnector_options['package_size'] )
			: ( ( $file_size - $div_last ) / $this->smconnector_options['package_size'] + 1 );
		$output      .= "|$file_size|\r\n" . basename( $file ) . "\r\n" . md5_file( $file );

		if ( ! headers_sent() ) {
			header( 'Content-Length: ' . $this->shop_cart->str_len( $output ) );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Pragma: no-cache' );
		}

		return $output;
	}
	/** Generate error
	 *
	 * @param string $err_text The error text.
	 * @param string $error_code The error code.
	 * @param bool   $show_link The show link parameter.
	 */
	private function generate_error( $err_text = '1', $error_code = false, $show_link = false ) {
		if ( $show_link ) {
			echo "1\r\n";
			echo esc_textarea( $err_text );
		} else {
			echo json_encode(
				array(
					$this->code_response => $error_code ? $error_code : $this->error_code_common,
					$this->key_message   => $err_text,
				)
			);
		}

		die();
	}
	/** Add connector log
	 *
	 * @param string $data The connector data.
	 */
	private function put_log( $data ) {
		if ( ! $this->log_file_reset ) {
			$log_file_handler     = $this->shop_cart->file_open( $this->tmp_folder_path . '/' . self::LOG_FILENAME, 'w' );
			$this->log_file_reset = true;
		} else {
			$log_file_handler = $this->shop_cart->file_open( $this->tmp_folder_path . '/' . self::LOG_FILENAME, 'a' );
		}

		fputs( $log_file_handler, '[' . gmdate( 'r' ) . ']' . $data . "\r\n" );
		$this->shop_cart->file_close( $log_file_handler );
	}
	/** Run self test */
	private function run_self_test() {
		die(
			json_encode(
				array(
					$this->key_message => 'test ok',
				)
			)
		);
	}
	/** Check if the session key is valid
	 *
	 * @param string $key The session key.
	 */
	private function is_session_key_valid( $key ) {
		$date   = gmdate( 'Y-m-d H:i:s', ( time() - self::MAX_KEY_LIFETIME ) );
		$sql    = 'SELECT `id` FROM `' . self::TABLE_SESSION_KEYS
			. "` WHERE `session_key` = '" . $this->shop_cart->p_sql( $key ) . "' AND `last_activity` > '"
			. $this->shop_cart->p_sql( $date ) . "'";
		$result = $this->shop_cart->get_sql_results( $sql );

		if ( $result && isset( $result[0]['id'] ) ) {
			$sql = 'UPDATE `' . self::TABLE_SESSION_KEYS . "` SET `last_activity` = '"
				. $this->shop_cart->p_sql( gmdate( 'Y-m-d H:i:s' ) ) . "' WHERE `id` = " . (int) $result[0]['id'];
			$this->shop_cart->exec_sql( $sql );

			return true;
		}

		return false;
	}
	/** Add failed attempt to database */
	private function add_failed_attempt() {
		$timestamp      = time();
		$remote_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : null;
		$sql            = 'INSERT INTO `' . self::TABLE_FAILED_LOGIN
			. "` (`ip`, `date_added`) VALUES ('" . $remote_address . "', '"
			. $this->shop_cart->p_sql( gmdate( 'Y-m-d H:i:s', $timestamp ) ) . "')";
		$this->shop_cart->exec_sql( $sql );

		// Get count of failed attempts for last 24 hours and set delay.
		$sql    = 'SELECT COUNT(`id`) AS count_rows FROM `' . self::TABLE_FAILED_LOGIN
			. "` WHERE `ip` = '" . $this->shop_cart->p_sql( $remote_address )
			. "' AND `date_added` > '"
			. $this->shop_cart->p_sql( gmdate( 'Y-m-d H:i:s', ( $timestamp - self::MAX_KEY_LIFETIME ) ) ) . "'";
		$result = $this->shop_cart->get_sql_results( $sql );

		if ( $result ) {
			self::set_delay( (int) $result[0]['count_rows'] );
		}
	}
	/** Generate session key
	 *
	 * @param string $hash The session hash.
	 */
	private function generate_session_key( $hash ) {
		$timestamp = time();
		$date      = gmdate( 'Y-m-d H:i:s', $timestamp );
		$sql       = 'SELECT `session_key` FROM `' . self::TABLE_SESSION_KEYS
			. "` WHERE `last_activity` > '"
			. $this->shop_cart->p_sql( gmdate( 'Y-m-d H:i:s', ( $timestamp - self::MAX_KEY_LIFETIME ) ) )
			. "' ORDER BY `last_activity` DESC LIMIT 1";
		$result    = $this->shop_cart->get_sql_results( $sql );

		if ( $result && isset( $result[0]['session_key'] ) ) {
			$sql = 'UPDATE `' . self::TABLE_SESSION_KEYS . "` SET `last_activity` = '"
				. $this->shop_cart->p_sql( $date ) . "' WHERE `session_key` = '"
				. $this->shop_cart->p_sql( $result[0]['session_key'] ) . "'";
			$this->shop_cart->exec_sql( $sql );

			return $result[0]['session_key'];
		}

		$key = hash( 'sha256', $hash . $timestamp );
		$sql = 'INSERT INTO `' . self::TABLE_SESSION_KEYS
			. "` (`session_key`, `date_added`, `last_activity`) VALUES ('" . $this->shop_cart->p_sql( $key ) . "', '"
			. $date . "', '" . $date . "')";
		$this->shop_cart->exec_sql( $sql );

		return $key;
	}
	/** Delete session key */
	private function delete_session_key() {
		if ( $this->shop_cart->isset_request_param( 'key' ) ) {
			$key = (string) $this->shop_cart->get_request_param( 'key' );

			if ( ! empty( $key ) ) {
				$sql = 'DELETE FROM `' . self::TABLE_SESSION_KEYS
					. "` WHERE `session_key` = '" . $this->shop_cart->p_sql( $key ) . "'";
				$this->shop_cart->exec_sql( $sql );
			}
		}
	}
	/** Clear the old data from the database */
	private function clear_old_data() {
		$timestamp = time();
		$date      = gmdate( 'Y-m-d H:i:s', ( $timestamp - self::MAX_KEY_LIFETIME ) );
		$this->shop_cart->exec_sql(
			'DELETE FROM `' . self::TABLE_SESSION_KEYS . "` WHERE `last_activity` < '"
			. $this->shop_cart->p_sql( $date ) . "'"
		);
		$this->shop_cart->exec_sql(
			'DELETE FROM `' . self::TABLE_FAILED_LOGIN . "` WHERE `date_added` < '"
			. $this->shop_cart->p_sql( $date ) . "'"
		);
	}
	/** Set delay time
	 *
	 * @param int $count_attempts The number of attempts to be used in the operation.
	 */
	private static function set_delay( $count_attempts ) {
		if ( $count_attempts <= 10 ) {
			sleep( 1 );
		} elseif ( $count_attempts <= 20 ) {
			sleep( 2 );
		} elseif ( $count_attempts <= 50 ) {
			sleep( 5 );
		} else {
			sleep( 10 );
		}
	}
	/** Get formatted number
	 *
	 * @param int $num The number to be used in the operation.
	 */
	private function get_formated_int_number( $num ) {
		return number_format( $num, 0, ',', ' ' );
	}
}
