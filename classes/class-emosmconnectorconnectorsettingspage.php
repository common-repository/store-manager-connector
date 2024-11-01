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
/**
 * Class which create connector settings page
 */
class EmoSMConnectorConnectorSettingsPage {
	/** The module configuration parameters
	 *
	 * @var array $options The array of configuration options.
	 */
	private $options;

	/** Load Store Manager Connector Plugin Setting page in admin panel */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

		if ( is_multisite() ) {
			$curr_site = get_current_blog_id();
			$sites = get_sites();
			$option = get_blog_option( $curr_site, 'SMCONNECTOR_OPTIONS' );
			foreach ( $sites as $site ) {
                $subsite_id = get_object_vars($site)["blog_id"];
				if ( $curr_site != $subsite_id ) {
					switch_to_blog( $subsite_id );
					update_blog_option( $subsite_id, 'SMCONNECTOR_OPTIONS', $option );
					restore_current_blog();
				}
			}
		}
	}
	/** Add options page */
	public function add_plugin_page() {
		// This page will be under "Settings".
		add_menu_page(
			'Store Manager Connector',
			'Store Manager Connector',
			'manage_options',
			'smconnector',
			array( $this, 'create_admin_page' ),
			plugins_url( '../images/emagicone.png', __FILE__ )
		);
	}
	/** Options page callback */
	public function create_admin_page() {
		global $wpdb;

		$this->options = get_option( 'SMCONNECTOR_OPTIONS' );

		if ( ! $this->options ) {
			$wpdb->replace(
				$wpdb->options,
				array(
					'option_name' => 'SMCONNECTOR_OPTIONS',
					'option_value' => serialize( emo_smc_get_default_smconnector_options() ),
				)
			);
		}
		?>
		<div class="wrap">
			<h2>Store Manager Connector</h2>
            <?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'smconnector_group' ); ?>
				<div class="section_wrap">
					<?php do_settings_sections( 'smconnector-access' ); ?>
				</div>
				<div class="section_wrap">
					<?php do_settings_sections( 'smconnector-smconnectoroptions' ); ?>
				</div>
				<div class="button_toolbar tablenav bottom">
					<?php submit_button( __( 'Save Settings', 'store-manager-connector' ), 'primary', 'submit-form', false ); ?>
				</div>
			</form>
		</div>
		<?php
	}
	/** Register and add settings */
	public function page_init() {
		register_setting(
			'smconnector_group', // Option group.
			'SMCONNECTOR_OPTIONS', // Option name.
			array( $this, 'sanitize' ) // Sanitize.
		);

		// Add Access Settings.
		add_settings_section(
			'setting_section_id', // ID.
			__( 'Access Settings', 'store-manager-connector' ), // Title.
			array( $this, 'print_section_access' ),
			'smconnector-access' // Page.
		);

		// Add Store Manager Connector Options Settings.
		add_settings_section(
			'setting_section_id', // ID.
			__( 'Store Manager Connector Settings', 'store-manager-connector' ), // Title.
			array( $this, 'print_section_smconnector_options' ),
			'smconnector-smconnectoroptions' // Page.
		);

		// Add field 'login'.
		add_settings_field(
			'smconnector_login', // ID.
			__( 'Login', 'store-manager-connector' ), // Title.
			array( $this, 'login_callback' ), // Callback.
			'smconnector-access', // Page.
			'setting_section_id' // Section.
		);

		// Add field 'password'.
		add_settings_field(
			'smconnector_password',
			__( 'Password', 'store-manager-connector' ),
			array( $this, 'password_callback' ),
			'smconnector-access',
			'setting_section_id'
		);

		// Add field 'use_plugin_tmp_dir'.
		add_settings_field(
			'use_plugin_tmp_dir',
			__( 'Use temporary directory in plugin folder', 'store-manager-connector' ),
			array( $this, 'use_plugin_tmp_dir_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'tmp_dir'.
		add_settings_field(
			'smconnector_tmp_dir',
			__( 'Directory for Plugin Operations', 'store-manager-connector' ),
			array( $this, 'tmp_dir_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'allow_compression'.
		add_settings_field(
			'smconnector_allow_compression',
			__( 'Allow Compression', 'store-manager-connector' ),
			array( $this, 'allow_compression_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'compress_level'.
		add_settings_field(
			'smconnector_compress_level',
			__( 'Compress Level', 'store-manager-connector' ),
			array( $this, 'compress_level_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'limit_query_size'.
		add_settings_field(
			'smconnector_limit_query_size',
			__( 'Limit Query Size', 'store-manager-connector' ),
			array( $this, 'limit_query_size_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'package_size'.
		add_settings_field(
			'smconnector_package_size',
			__( 'Package Size', 'store-manager-connector' ),
			array( $this, 'package_size_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'exclude_db_tables'.
		add_settings_field(
			'smconnector_exclude_db_tables',
			__( 'Exclude Tables', 'store-manager-connector' ),
			array( $this, 'exclude_db_tables_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'allowed_ips'.
		add_settings_field(
			'smconnector_allowed_ips',
			__( 'Allowed IPs', 'store-manager-connector' ),
			array( $this, 'allowed_ips_callback' ),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);

		// Add field 'notification_email'.

		/*
		Commented:
		add_settings_field(
			'smconnector_notification_email',
			'Email for Log Reports',
			array($this, 'notificationEmailCallback'),
			'smconnector-smconnectoroptions',
			'setting_section_id'
		);
		*/
	}
	/** Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys.
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_options = array();
		$options_prev = get_option( 'SMCONNECTOR_OPTIONS' );
		$password_prev = isset( $options_prev['password'] )
			? $options_prev['password']
			: emo_smc_get_encrypted_password( EMO_SMC_DEFAULT_PASSWORD );

		$new_options['login'] = isset( $input['smconnector_login'] )
			? sanitize_text_field( $input['smconnector_login'] )
			: EMO_SMC_DEFAULT_LOGIN;
		$new_options['password'] = ! empty( $input['smconnector_password'] ) && $input['smconnector_password'] != $password_prev
			? $input['smconnector_password']
			: EMO_SMC_DEFAULT_PASSWORD;
		$new_options['allow_compression'] = isset( $input['smconnector_allow_compression'] )
			? (int) $input['smconnector_allow_compression']
			: 0;
		$new_options['exclude_db_tables'] = isset( $input['smconnector_exclude_db_tables'] )
			? implode( ';', $input['smconnector_exclude_db_tables'] )
			: implode( ';', emo_smc_get_default_excluded_tables() );
		$new_options['allowed_ips'] = isset( $input['smconnector_allowed_ips'] )
			? (string) $input['smconnector_allowed_ips']
			: EMO_SMC_DEFAULT_ALLOWED_IPS;

		/*
		$new_options['notification_email'] = isset($input['smconnector_notification_email'])
			? (string)$input['smconnector_notification_email']
			: DEFAULT_NOTIFICATION_EMAIL;
		*/

		if ( empty( $input['smconnector_password'] ) ) {
			$new_options['password'] = EMO_SMC_DEFAULT_PASSWORD;
		} elseif ( $input['smconnector_password'] == $password_prev ) {
			$new_options['password'] = emo_smc_get_decrypted_password( $password_prev );
		} else {
			$new_options['password'] = (string) $input['smconnector_password'];
		}

		if ( isset( $input['smconnector_tmp_dir'] ) ) {
			// trim '/', '\', '.' from begin and end.
			$new_options['tmp_dir'] = preg_replace(
				'/^[\/|\\\|\.]*|[\/|\\\|\.]*$/',
				'',
				$input['smconnector_tmp_dir']
			);

			// add '/' to begin.
			$new_options['tmp_dir'] = '/' . $new_options['tmp_dir'];
		} else {
			$new_options['tmp_dir'] = array_key_exists( 'tmp_dir', $options_prev ) ? $options_prev['tmp_dir'] : emo_smc_get_tmp_path();
		}

		$new_options['use_plugin_tmp_dir'] = array_key_exists( 'smconnector_use_plugin_tmp_dir', $input )
			? (int) $input['smconnector_use_plugin_tmp_dir']
			: 0;

		if ( isset( $input['smconnector_compress_level'] ) ) {
			$new_options['compress_level'] = (int) $input['smconnector_compress_level'];

			if ( $new_options['compress_level'] < EMO_SMC_MIN_COMPRESS_LEVEL ) {
				$new_options['compress_level'] = EMO_SMC_MIN_COMPRESS_LEVEL;
			} elseif ( $new_options['compress_level'] > EMO_SMC_MAX_COMPRESS_LEVEL ) {
				$new_options['compress_level'] = EMO_SMC_MAX_COMPRESS_LEVEL;
			}
		} else {
			$new_options['compress_level'] = EMO_SMC_DEFAULT_COMPRESS_LEVEL;
		}

		if ( isset( $input['smconnector_limit_query_size'] ) ) {
			$new_options['limit_query_size'] = (int) $input['smconnector_limit_query_size'];

			if ( $new_options['limit_query_size'] < EMO_SMC_MIN_LIMIT_QUERY_SIZE ) {
				$new_options['limit_query_size'] = EMO_SMC_MIN_LIMIT_QUERY_SIZE;
			} elseif ( $new_options['limit_query_size'] > EMO_SMC_MAX_LIMIT_QUERY_SIZE ) {
				$new_options['limit_query_size'] = EMO_SMC_MAX_LIMIT_QUERY_SIZE;
			}
		} else {
			$new_options['limit_query_size'] = EMO_SMC_DEFAULT_LIMIT_QUERY_SIZE;
		}

		if ( isset( $input['smconnector_package_size'] ) ) {
			$new_options['package_size'] = (int) $input['smconnector_package_size'];

			if ( $new_options['package_size'] < EMO_SMC_MIN_PACKAGE_SIZE ) {
				$new_options['package_size'] = EMO_SMC_MIN_PACKAGE_SIZE;
			} elseif ( $new_options['package_size'] > EMO_SMC_MAX_PACKAGE_SIZE ) {
				$new_options['package_size'] = EMO_SMC_MAX_PACKAGE_SIZE;
			}
		} else {
			$new_options['package_size'] = EMO_SMC_DEFAULT_PACKAGE_SIZE;
		}

		$new_options['smconnector_hash'] = md5( $new_options['login'] . $new_options['password'] );
		$new_options['password'] = emo_smc_get_encrypted_password( $new_options['password'] );

		return $new_options;
	}
	/** Print the Access Section text */
	public function print_section_access() {
		echo esc_html__( 'Enter Store Manager Connector credentials below:', 'store-manager-connector' );
	}
	/** Print the Store Manager Connector Options Section text */
	public function print_section_smconnector_options() {
		echo esc_html__( 'Enter Store Manager Connector settings below:', 'store-manager-connector' );
	}
	/** Generate login field in the plugin configuration */
	public function login_callback() {
		if ( ! isset( $this->options['login'] ) ) {
			$this->options['login'] = EMO_SMC_DEFAULT_LOGIN;
		}

		printf(
			'<input type="text" id="smconnector_login" name="SMCONNECTOR_OPTIONS[smconnector_login]" value="%s" /> %s',
			esc_textarea( $this->options['login'] ),
			EMO_SMC_DEFAULT_LOGIN == $this->options['login']
				? '<span class="warning"><br />' . esc_html__( 'Default login is "1". Change it because of security reasons!', 'store-manager-connector' ) . '</span>'
				: ''
		);
	}
	/** Generate password field in the plugin configuration */
	public function password_callback() {
		$password_changed = false;

		if ( ! isset( $this->options['password'] ) ) {
			$this->options['password'] = emo_smc_get_encrypted_password( EMO_SMC_DEFAULT_PASSWORD );
		}

		if ( emo_smc_get_encrypted_password( EMO_SMC_DEFAULT_PASSWORD ) != $this->options['password'] ) {
			$password_changed = true;
		}

		printf(
			'<input type="password" id="smconnector_password" name="SMCONNECTOR_OPTIONS[smconnector_password]"
            value="%s" />%s',
			esc_textarea( $this->options['password'] ),
			! $password_changed
				? '<span class="warning"><br />' . esc_html__( 'Default password is "1". Change it because of security reasons!', 'store-manager-connector' ) . '</span>'
				: ''
		);
	}
	/** Generate tmp directory field in the plugin configuration */
	public function tmp_dir_callback() {
		$warning = '';
		$disabled = false;

		if ( array_key_exists( 'use_plugin_tmp_dir', $this->options )
			&& 1 === (int) $this->options['use_plugin_tmp_dir']
		) {
			$disabled = true;
		}

		if ( ! $disabled ) {
			if ( ! isset( $this->options['tmp_dir'] ) ) {
				$this->options['tmp_dir'] = emo_smc_get_tmp_path();
			}

			if ( ! is_dir( ABSPATH . $this->options['tmp_dir'] ) ) {
				$warning = __( 'Directory does not exist', 'store-manager-connector' );
			} elseif ( ! is_writable( ABSPATH . $this->options['tmp_dir'] ) ) {
				$warning = __( 'Set writing permissions for temporary directory', 'store-manager-connector' );
			}
		}

		$tmp = '<input type="text" id="smconnector_tmp_dir" name="SMCONNECTOR_OPTIONS[smconnector_tmp_dir]" value="'
			. esc_textarea( $this->options['tmp_dir'] ) . '"'
			. ( $disabled ? disabled( true, true, false ) : '' )
			. '/><span class="field_hint"><br />'
			. __( 'Enter temporary folder path. It should be writable.', 'store-manager-connector' ) . '</span>';

		$html = array(
			'input' => array(
				'type' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
			),
			'span' => array(),
			'br' => array(),
		);
		echo wp_kses( $tmp, $html );

		if ( ! empty( $warning ) ) {
			echo '<span class="warning"><br />' . esc_textarea( $warning ) . '</span>';
		}
	}
	/** Generate tmp directory option field in the plugin configuration */
	public function use_plugin_tmp_dir_callback() {
		$warning = '';
		$checked = false;
		$tmp_path = emo_smc_get_plugin_tmp_path();

		if ( array_key_exists( 'use_plugin_tmp_dir', $this->options )
			&& 1 === (int) $this->options['use_plugin_tmp_dir']
		) {
			$this->options['use_plugin_tmp_dir'] = (int) $this->options['use_plugin_tmp_dir'];
			$checked = true;
		} else {
			$this->options['use_plugin_tmp_dir'] = EMO_SMC_DEFAULT_USE_PLUGIN_TMP_DIR;
		}

		if ( ! is_dir( $tmp_path ) ) {
			if ( ! wp_mkdir_p( $tmp_path ) ) {
				$warning = __( 'Directory does not exist and cannot be created', 'store-manager-connector' );
			}
		}

		if ( '' === $warning && ! is_writable( $tmp_path ) ) {
			$warning = __( 'Set writing permissions for temporary directory in plugin folder', 'store-manager-connector' );
		}

		echo '<input type="checkbox" value="1" id="smconnector_use_plugin_tmp_dir" name="SMCONNECTOR_OPTIONS[smconnector_use_plugin_tmp_dir]" '
			. esc_html( $checked ? checked( true, true, false ) : '' )
			. '/><span class="field_hint"><br />'
			. esc_html__( 'Use temporary folder from plugin directory. May help in case of errors with custom path.', 'store-manager-connector' ) . '</span>';

		if ( '' !== $warning ) {
			echo '<span class="warning"><br />' . esc_textarea( $warning ) . '</span>';
		}
	}
	/** Generate allow compression field in the plugin configuration */
	public function allow_compression_callback() {
		if ( ! isset( $this->options['allow_compression'] ) ) {
			$this->options['allow_compression'] = EMO_SMC_DEFAULT_ALLOW_COMPRESSION;
		} else {
			$this->options['allow_compression'] = (int) $this->options['allow_compression'];
		}

		printf(
			'<input type="checkbox" id="smconnector_allow_compression"
            name="SMCONNECTOR_OPTIONS[smconnector_allow_compression]" value="1" %s/><span class="field_hint"><br />'
			. esc_html__(
				'Compression of generated dump file. It is recommended for save space and faster getting data in Store
            Manager.',
				'store-manager-connector'
			) . '</span>',
			( 1 == $this->options['allow_compression'] ) ? 'checked' : ''
		);
	}
	/** Generate compress level field in the plugin configuration */
	public function compress_level_callback() {
		if ( ! isset( $this->options['compress_level'] ) ) {
			$this->options['compress_level'] = EMO_SMC_DEFAULT_COMPRESS_LEVEL;
		} elseif ( (int) $this->options['compress_level'] < EMO_SMC_MIN_COMPRESS_LEVEL ) {
			$this->options['compress_level'] = EMO_SMC_MIN_COMPRESS_LEVEL;
		} elseif ( (int) $this->options['compress_level'] > EMO_SMC_MAX_COMPRESS_LEVEL ) {
			$this->options['compress_level'] = EMO_SMC_MAX_COMPRESS_LEVEL;
		} else {
			$this->options['compress_level'] = (int) $this->options['compress_level'];
		}

		echo '<input type="text" id="smconnector_compress_level"
            name="SMCONNECTOR_OPTIONS[smconnector_compress_level]" value="' . esc_textarea( $this->options['compress_level'] ) . '" />
            <span class="field_hint"><br />' . esc_html__(
				'Values between 1 and 9 will trade off speed and efficiency.
             The 1 flag means fast but less efficient" compression, and 9 means "slow but most efficient" compression.',
				'store-manager-connector'
			) . '</span>';
	}
	/** Generate limit query size field in the plugin configuration */
	public function limit_query_size_callback() {
		if ( ! isset( $this->options['limit_query_size'] ) ) {
			$this->options['limit_query_size'] = EMO_SMC_DEFAULT_LIMIT_QUERY_SIZE;
		} elseif ( (int) $this->options['limit_query_size'] < EMO_SMC_MIN_LIMIT_QUERY_SIZE ) {
			$this->options['limit_query_size'] = EMO_SMC_MIN_LIMIT_QUERY_SIZE;
		} elseif ( (int) $this->options['limit_query_size'] > EMO_SMC_MAX_LIMIT_QUERY_SIZE ) {
			$this->options['limit_query_size'] = EMO_SMC_MAX_LIMIT_QUERY_SIZE;
		} else {
			$this->options['limit_query_size'] = (int) $this->options['limit_query_size'];
		}

		echo '<input type="text" id="smconnector_limit_query_size"
            name="SMCONNECTOR_OPTIONS[smconnector_limit_query_size]" value="' . esc_textarea( $this->options['limit_query_size'] )
			. '" /><span class="field_hint"><br />' . esc_html__(
				'Restrict capacity of queries per one request (kB).',
				'store-manager-connector'
			) . '</span>';
	}
	/** Generate package size field in the plugin configuration */
	public function package_size_callback() {
		if ( ! isset( $this->options['package_size'] ) ) {
			$this->options['package_size'] = EMO_SMC_DEFAULT_PACKAGE_SIZE;
		} elseif ( (int) $this->options['package_size'] < EMO_SMC_MIN_PACKAGE_SIZE ) {
			$this->options['package_size'] = EMO_SMC_MIN_PACKAGE_SIZE;
		} elseif ( (int) $this->options['package_size'] > EMO_SMC_MAX_PACKAGE_SIZE ) {
			$this->options['package_size'] = EMO_SMC_MAX_PACKAGE_SIZE;
		} else {
			$this->options['package_size'] = (int) $this->options['package_size'];
		}

		echo '<input type="text" id="smconnector_package_size" name="SMCONNECTOR_OPTIONS[smconnector_package_size]"
            value="' . esc_textarea( $this->options['package_size'] ) . '" /><span class="field_hint"><br />'
			. esc_html__( 'Size of parts for getting dump file (kB).', 'store-manager-connector' ) . '</span>';
	}
	/** Generate excluded database tables field in the plugin configuration */
	public function exclude_db_tables_callback() {
		global $wpdb;

		$options = '<div id="exclude_db_tables_list">';
		$tables = $wpdb->get_results( 'SHOW TABLES', ARRAY_N );

		if ( isset( $this->options['exclude_db_tables'] ) ) {
			$excluded_tables = explode( ';', $this->options['exclude_db_tables'] );
		} else {
			$excluded_tables = emo_smc_get_default_excluded_tables();
		}

		foreach ( $tables as $table_name ) {
			$checked = in_array( $table_name[0], $excluded_tables ) ? 'checked' : '';
			$options .= '<label><input type="checkbox" name="SMCONNECTOR_OPTIONS[smconnector_exclude_db_tables][]" value="'
				. $table_name[0] . '" ' . $checked . '>' . $table_name[0] . '</label><br>';
		}

		$html = array(
			'div' => array(
				'id' => array(),
			),
			'label' => array(),
			'input' => array(
				'type' => array(),
				'name' => array(),
				'value' => array(),
				'checked' => array(),
			),
			'br' => array(),
		);

		echo wp_kses( $options, $html );

		echo '</div><span class="field_hint">'
			. esc_html__(
				'Do not get data from tables selected here. Use this to reduce size of the data retrieved from plugin.',
				'store-manager-connector'
			) . '</span>';
	}
	/** Generate allowed IPs field in the plugin configuration */
	public function allowed_ips_callback() {
		if ( ! isset( $this->options['allowed_ips'] ) ) {
			$this->options['allowed_ips'] = EMO_SMC_DEFAULT_ALLOWED_IPS;
		}

		$this->options['allowed_ips'] = (string) $this->options['allowed_ips'];

		echo '<input type="text" id="smconnector_allowed_ips" name="SMCONNECTOR_OPTIONS[smconnector_allowed_ips]"
            value="' . esc_textarea( $this->options['allowed_ips'] ) . '" /><span class="field_hint"><br />'
			. esc_html__(
				'In order to allow plugin using only from specific IP address you should add IP address here
            (for example, 48.78.88.98 - only one IP address; 48.78.88.98, 15.25.35.45 - two IP addresses;
            48.78.x.x - all IP addresses which begin from 48.78.)',
				'store-manager-connector'
			) . '</span>';
	}

	/*
	Commented:
	public function notificationEmailCallback()
	{
		if (!isset($this->options['notification_email'])) {
			$this->options['notification_email'] = DEFAULT_NOTIFICATION_EMAIL;
		}

		$this->options['notification_email'] = (string)$this->options['notification_email'];

		echo '<input type="text" id="smconnector_notification_email" name="SMCONNECTOR_OPTIONS[smconnector_notification_email]" value="' . $this->options['notification_email'] . '" />
			<span class="field_hint"><br />Please enter your email address here to receive notifications.</span>';
	}
	*/
}

if ( is_admin() ) {
	$GLOBALS['SMConnectorSettingsPage'] = new EmoSMConnectorConnectorSettingsPage();
}
