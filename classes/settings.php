<?php

/**
 * CPAC_Settings Class
 *
 * @since 2.0.0
 */
class CPAC_Settings {

	/**
	 * CPAC class
	 */
	private $cpac;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @param object CPAC
	 */
	function __construct( $cpac ) {

		$this->cpac = $cpac;

		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_column_request' ), 1000 );

		// handle addon downloads
		add_action( 'admin_init', array( $this, 'handle_download_request' ) );
	}

	/**
	 * Admin Menu.
	 *
	 * Create the admin menu link for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function settings_menu() {

		// add pages
		$columns_page  = add_menu_page( __( 'Admin Columns Settings', 'cpac' ), __( 'Admin Columns', 'cpac' ), 'manage_admin_columns', 'codepress-admin-columns', array( $this, 'column_settings' ), false, 98 );
		$settings_page = add_submenu_page( 'codepress-admin-columns', __( 'Settings', 'cpac' ), __( 'Settings', 'cpac' ), 'manage_admin_columns', 'cpac-settings',	array( $this, 'general_settings' ) );

		// add help tabs
		add_action( "load-{$columns_page}", array( $this, 'help_tabs' ) );

		// add scripts & styles
		add_action( "admin_print_styles-{$columns_page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$columns_page}", array( $this, 'admin_scripts' ) );
		add_action( "admin_print_styles-{$settings_page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$settings_page}", array( $this, 'admin_scripts' ) );

		// register setting
		register_setting( 'cpac-general-settings', 'cpac_general_options' );

		// add capabilty to administrator to manage admin columns
		// note to devs: you can use this to grant other roles this privilidge as well.
		$role = get_role( 'administrator' );
   		$role->add_cap( 'manage_admin_columns' );

		// add cap to options.php
   		add_filter( 'option_page_capability_cpac-general-settings', array( $this, 'add_capability' ) );
	}

	/**
	 * Add capability
	 *
	 * Allows the capaiblity 'manage_admin_columns' to store data through /wp-admin/options.php
	 *
	 * @since 2.0.0
	 */
	public function add_capability() {

		return 'manage_admin_columns';
	}

	/**
	 * Register admin css
	 *
	 * @since 1.0.0
	 */
	public function admin_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'jquery-ui-lightness', CPAC_URL . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-admin', CPAC_URL . 'assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-multi-select', CPAC_URL . 'assets/css/multi-select.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-multiple-fields-css', CPAC_URL . 'assets/css/multiple-fields.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * Register admin scripts
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts() {

		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui-slider' );

		// columns
		wp_enqueue_script( 'cpac-admin-columns', CPAC_URL . 'assets/js/admin-columns.js', array( 'jquery', 'dashboard', 'jquery-ui-slider', 'jquery-ui-sortable' ), CPAC_VERSION );
		wp_enqueue_script( 'cpac-jquery-multi-select', CPAC_URL . 'assets/js/jquery.multi-select.js', array( 'jquery' ), CPAC_VERSION );
		wp_enqueue_script( 'cpac-multiple-fields-js', CPAC_URL . 'assets/js/multiple-fields.js', array( 'jquery' ), CPAC_VERSION );

		// javascript translations
		wp_localize_script( 'cpac-multiple-fields-js', 'cpac_i18n', array(
			'remove'	=> __( 'Remove', 'cpac' ),
			'clone'		=> __( '%s column is already present and can not be duplicated.', 'cpac' ),
		));
	}

	/**
	 * Handle column requests.
	 *
	 * @since 1.0.0
	 */
	public function handle_column_request() {

		// only handle updates from the admin columns page
		if ( ! ( isset($_GET['page'] ) && in_array( $_GET['page'], array( 'codepress-admin-columns', 'cpac-settings' ) ) && isset( $_REQUEST['cpac_action'] ) ) )
			return false;

		// use $_REQUEST because the values are send both over $_GET and $_POST
		$action = isset( $_REQUEST['cpac_action'] ) ? $_REQUEST['cpac_action'] 	: '';
		$nonce  = isset( $_REQUEST['_cpac_nonce'] ) ? $_REQUEST['_cpac_nonce'] 	: '';
		$key 	= isset( $_REQUEST['cpac_key'] ) 	? $_REQUEST['cpac_key'] 	: '';

		switch ( $action ) :

			case 'update_by_type' :
				if ( wp_verify_nonce( $nonce, 'update-type' ) ) {
					$storage_model = $this->cpac->get_storage_model( $key );
					$storage_model->store();
				}
				break;

			case 'restore_by_type' :
				if ( wp_verify_nonce( $nonce, 'restore-type' ) ) {
					$storage_model = $this->cpac->get_storage_model( $key );
					$storage_model->restore();
				}
				break;

			case 'restore_all' :
				if ( wp_verify_nonce( $nonce, 'restore-all' ) ) {
					$this->restore_all();
				}
				break;

		endswitch;
	}

	/**
	 * Get export multiselect options
	 *
	 * Gets multi select options to use in a HTML select element
	 *
	 * @since 2.0.0
	 * @return array Multiselect options
	 */
	public function get_export_multiselect_options() {
		$options = array();

		foreach ( $this->cpac->storage_models as $storage_model ) {

			if ( ! $storage_model->get_stored_columns() )
				continue;

			// General group
			if ( in_array( $storage_model->key, array( 'wp-comments', 'wp-links', 'wp-users', 'wp-media' ) ) ) {
				$options['general'][] = $storage_model;
			}

			// Post(types) group
			else {
				$options['posts'][] = $storage_model;
			}
		}

		return $options;
	}

	/**
	 * Restore defaults
	 *
	 * @since 1.0.0
	 */
	private function restore_all() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cpac_options_%'" );

		cpac_admin_message( __( 'Default settings succesfully restored.',  'cpac' ), 'updated' );
	}

	/**
	 * Add help tabs
	 *
	 * @since 1.3.0
	 */
	public function help_tabs() {

		$screen = get_current_screen();

		if ( ! method_exists( $screen,'add_help_tab' ) )
			return;

		// add help content
		$tabs = array(
			array(
				'title'		=> __( "Overview", 'cpac' ),
				'content'	=>
					"<h5>Codepress Admin Columns</h5>
					<p>". __( "This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.", 'cpac' ) . "</p>"
			),
			array(
				'title'		=> __( "Basics", 'cpac' ),
				'content'	=>
					"<h5>". __( "Show / Hide", 'cpac' ) . "</h5>
					<p>". __( "You can switch columns on or off by clicking on the checkbox. This will show or hide each column heading.", 'cpac' ) . "</p>
					<h5>". __( "Change order", 'cpac' ) . "</h5>
					<p>". __( "By dragging the columns you can change the order which they will appear in.", 'cpac' ) . "</p>
					<h5>". __( "Change label", 'cpac' ) . "</h5>
					<p>". __( "By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.", 'cpac' ) . "</p>
					<h5>". __( "Change column width", 'cpac' ) . "</h5>
					<p>". __( "By clicking on the triangle you will see the column options. By using the draggable slider yo can set the width of the columns in percentages.", 'cpac' ) . "</p>"
			),
			array(
				'title'		=> __( "Custom Field", 'cpac' ),
				'content'	=>
					"<h5>". __( "'Custom Field' column", 'cpac' ) . "</h5>
					<p>". __( "The custom field colum uses the custom fields from posts and users. There are 10 types which you can set.", 'cpac' ) . "</p>
					<ul>
						<li><strong>". __( "Default", 'cpac' ) . "</strong><br/>". __( "Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.", 'cpac' ) . "</li>
						<li><strong>". __( "Image", 'cpac' ) . "</strong><br/>". __( "Value: should contain an image URL or Attachment IDs ( seperated by a ',' comma ).", 'cpac' ) . "</li>
						<li><strong>". __( "Excerpt", 'cpac' ) . "</strong><br/>". __( "Value: This will show the first 20 words of the Post content.", 'cpac' ) . "</li>
						<li><strong>". __( "Multiple Values", 'cpac' ) . "</strong><br/>". __( "Value: should be an array. This will flatten any ( multi dimensional ) array.", 'cpac' ) . "</li>
						<li><strong>". __( "Numeric", 'cpac' ) . "</strong><br/>". __( "Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.", 'cpac' ) . "</li>
						<li><strong>". __( "Date", 'cpac' ) . "</strong><br/>". sprintf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", 'cpac' ), 'http://codex.wordpress.org/Formatting_Date_and_Time', get_admin_url() . 'options-general.php' ) . "</li>
						<li><strong>". __( "Post Titles", 'cpac' ) . "</strong><br/>". __( "Value: can be one or more Post ID's (seperated by ',').", 'cpac' ) . "</li>
						<li><strong>". __( "Usernames", 'cpac' ) . "</strong><br/>". __( "Value: can be one or more User ID's (seperated by ',').", 'cpac' ) . "</li>
						<li><strong>". __( "Checkmark", 'cpac' ) . "</strong><br/>". __( "Value: should be a 1 (one) or 0 (zero).", 'cpac' ) . "</li>
						<li><strong>". __( "Color", 'cpac' ) . "</strong><br/>". __( "Value: hex value color, such as #808080.", 'cpac' ) . "</li>
					</ul>
				"
			)
		);

		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab( array(
				'id'		=> 'cpac-tab-'.$k,
				'title'		=> $tab['title'],
				'content'	=> $tab['content'],
			));
		}
	}

	/**
	 * External Urls
	 *
	 * @since 1.0.0
	 *
	 * @param string $storage_model URL type.
	 * @return string Url.
	 */
	function get_url( $type ) {
		$urls = array(
			'codepress'		=> 'http://www.codepresshq.com/',
			'plugins'		=> 'http://wordpress.org/extend/plugins/codepress-admin-columns/',
			'support'		=> 'http://wordpress.org/tags/codepress-admin-columns/',
			'admincolumns'	=> 'http://www.codepresshq.com/wordpress-plugins/admin-columns/',
			'addons'		=> 'http://www.codepresshq.com/wordpress-plugins/admin-columns/#addons',
			'documentation'	=> 'http://www.codepresshq.com/wordpress-plugins/admin-columns/',
			'feedback'		=> 'http://www.codepresshq.com/',
		);

		if ( ! isset( $urls[ $type ] ) )
			return false;

		return $urls[ $type ];
	}

	/**
	 * Has Custom Field
	 *
	 * @since 2.0.0
	 */
	public function uses_custom_fields() {

		$old_columns = get_option('cpac_options');

		if ( empty( $old_columns['columns'] ) ) return false;

		foreach ( $old_columns['columns'] as $columns ) {
			foreach ( $columns as $id => $values ) {
				if ( strpos( $id, 'column-meta-' ) !== false )
					return true;
			}
		}

		return false;
	}

	/**
	 * Handle Addon Download
	 *
	 * @since 2.0.0
	 */
	function handle_download_request() {

		// API domain
		$store_url = 'http://codepresshq.com';

		if ( ! isset( $_REQUEST['cpac_product_id'] ) ) return false;

		$args = array(
			'codepress-wc-api' 	=> 'plugin_updater',
			'secret_key'		=> '',
			'product_id'		=> $_REQUEST['cpac_product_id'],
			'licence_key'		=> isset( $_REQUEST['cpac_license_key'] ) ? $_REQUEST['cpac_license_key'] : '',
			'slug'				=> '',
		);

		$result = wp_remote_get( add_query_arg( $args, $store_url ), array( 'timeout' => 15, 'sslverify' => false ) );

		// Call the custom API.
		$response = json_decode( wp_remote_retrieve_body( $result ) );

		if ( ! $response  )  {
			cpac_admin_message( 'Could not connect to API.', 'error' );
			return;
		}
		if( isset( $response->error ) ) {
			cpac_admin_message( $response->error, 'error' );
			return;
		}
		if( empty( $response->package ) ) {
			cpac_admin_message( 'No File found.', 'error' );
			return;
		}

		// no @ signs allowed/
		// source: http://wordpress.stackexchange.com/questions/64818/wp-sanitize-redirect-strips-out-signs-even-from-parameters-why
		$redirect_url = str_replace( '@', urlencode('@'), $response->package );

		wp_redirect( $redirect_url );
		exit;
	}

	/**
	 * Welcome screen
	 *
	 * @since 2.0.0
	 */
	public function welcome_screen() {

		// Should only be set after upgrade
		$show_welcome = false !== get_transient('cpac_show_welcome');

		// Should only be set manual
		if ( isset( $_GET['info'] ) )
			$show_welcome = true;

		if ( ! $show_welcome )
			return false;

		// Set check that welcome should not be displayed.
		delete_transient('cpac_show_welcome');

		// Get tab
		$tab = !empty( $_GET['info'] ) ? $_GET['info'] : 'whats-new';

		// check if old site used custom field columns
		$uses_customfields 	= $this->uses_custom_fields();
		$uses_sortorder 	= get_option( "cpac_sortable_ac" ) ? true : false;

		$installed_customfields = false;
		$installed_sortorder 	= false;

		?>
		<div id="cpac-welcome" class="wrap about-wrap">

			<h1><?php _e( "Welcome to Admin Columns",'cpac'); ?> <?php echo CPAC_VERSION; ?></h1>
			<div class="about-text">
				<?php _e( "Thank you for updating to the latest version!", 'cpac' ); ?>
				<?php _e( "Admin Columns is more polished and enjoyable than ever before. We hope you like it.", 'cpac' ); ?>
			</div>

			<div class="cpac-content-body">
				<h2 class="nav-tab-wrapper">
					<a class="cpac-tab-toggle nav-tab <?php if( $tab == 'whats-new' ){ echo 'nav-tab-active'; } ?>" href="<?php echo admin_url('admin.php?page=codepress-admin-columns&info=whats-new'); ?>"><?php _e( "What’s New", 'cpac' ); ?></a>
					<a class="cpac-tab-toggle nav-tab <?php if( $tab == 'changelog' ){ echo 'nav-tab-active'; } ?>" href="<?php echo admin_url('admin.php?page=codepress-admin-columns&info=changelog'); ?>"><?php _e( "Changelog", 'cpac' ); ?></a>
					<?php if( $tab == 'download-add-ons' ): ?>
					<a class="cpac-tab-toggle nav-tab nav-tab-active" href="<?php echo admin_url('admin.php?page=codepress-admin-columns&info=download-add-ons'); ?>"><?php _e( "Download Addons", 'cpac' ); ?></a>
					<?php endif; ?>
				</h2>

			<?php if ( 'whats-new' === $tab ) : ?>

				<h3><?php _e( "Addons", 'cpac' ); ?></h3>
				<p>
					<?php _e( "Addons are now activated by downloading and installing individual plugins. Although these plugins will not be hosted on the wordpress.org repository, each Add-on will continue to receive updates in the usual way.",'cpac'); ?>
				</p>
				<?php
				$titles = array();
				if ( $uses_customfields ) 	$titles[] = __('the Custom Fields columns');
				if ( $uses_sortorder ) 		$titles[] = __('the Sortorder Addon');

				if ( $titles ) : ?>
				<h4>This website uses <?php echo implode( ' ' . __('and','cpac') .' ', $titles); ?>. These addons need to be downloaded.</h4>
				<?php endif; ?>

				<div class="cpac-alert cpac-alert-success">
					<p>
						<strong></strong>
						<?php _e( "Addons are sperate plugins which need to be downloaded.", 'cpac' ); ?> <a href="<?php echo admin_url('admin.php?page=codepress-admin-columns&info=download-add-ons'); ?>" class="button-primary" style="display: inline-block;"><?php _e( "Download your Addons", 'cpac'); ?></a>
					</p>
				</div>

				<hr />

				<h3><?php _e( "Important", 'cpac' ); ?></h3>

				<h4><?php _e( "Database Changes", 'cpac' ); ?></h4>
				<p><?php _e("The database has been changed between versions 1 and 2. But we made sure you can still roll back to version 1x without any issues.",'cpac'); ?></p>

			<?php if ( get_option( 'cpac_version', false ) < CPAC_UPGRADE_VERSION ) : ?>
				<p><?php _e("Make sure you backup your database and then click",'cpac'); ?> <a href="<?php echo admin_url('admin.php?page=cpac-upgrade'); ?>" class="button-primary"><?php _e( "Upgrade Database", 'cpac' );?></a></p>
			<?php endif; ?>

				<h4><?php _e( "Potential Issues", 'cpac' ); ?></h4>
				<p><?php _e( "Do to the sizable refactoring the code, surounding Addons and action/filters, your website may not operate correctly. It is important that you read the full", 'cpac' ); ?> <a href="<?php echo $this->get_url('admincolumns'); ?>/migrating-from-v1-to-v2" target="_blank"><?php _e( "Migrating from v1 to v2", 'cpac' ); ?></a> <?php _e( "guide to view the full list of changes.", 'cpac' ); ?> <?php printf( __( 'When you have found a bug please <a href="%s">report them to us</a> so we can fix it in the next release.', 'cpac'), 'mailto:info@codepress.nl' ); ?></p>

				<div class="cpac-alert cpac-alert-error">
					<p><strong><?php _e( "Important!", 'cpac' ); ?></strong> <?php _e( "If you updated the Admin Columns plugin without prior knowledge of such changes, Please roll back to the latest", 'cpac' ); ?> <a href="http://downloads.wordpress.org/plugin/codepress-admin-columns.1.4.9.zip"> <?php _e( "version 1", 'cpac' ); ?></a> <?php _e( "of this plugin.", 'cpac' ); ?></p>
				</div>

			<?php endif; ?>
			<?php if ( 'changelog' === $tab ) : ?>

				<h3><?php _e("Changelog for", 'cpac'); ?> <?php echo CPAC_VERSION; ?></h3>
				<?php

				$items = file_get_contents( CPAC_DIR . 'readme.txt' );

				$items = end( explode('= ' . CPAC_VERSION . ' =', $items) );
				$items = current( explode("\n\n", $items) );
				$items = current( explode("= ", $items) );
				$items = array_filter( array_map('trim', explode("*", $items)) );

				?>
				<ul class="cpac-changelog">
				<?php foreach( $items as $item ) :
					$item = explode('http', $item);
				?>
					<li><?php echo $item[0]; ?><?php if( isset($item[1]) ): ?><a href="http<?php echo $item[1]; ?>" target="_blank"><?php _e("Learn more", 'cpac'); ?></a><?php endif; ?></li>
				<?php endforeach; ?>
				</ul>

			<?php endif; ?>
			<?php if ( 'download-add-ons' === $tab ) : ?>

				<h3><?php _e("Overview",'cpac'); ?></h3>

				<p><?php _e( "New to v2, all Addons act as separate plugins which need to be individually downloaded, installed and updated.", 'cpac' ); ?></p>
				<p><?php _e( "This page will assist you in downloading and installing each available Addon.", 'cpac' ); ?></p>
				<h3><?php _e( "Available Addons", 'cpac' ); ?></h3>

				<table class="widefat" id="cpac-download-add-ons-table">
					<thead>
					<tr>
						<th><?php _e("Name",'cpac'); ?></th>
						<th><?php _e("Download",'cpac'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php if ( $uses_sortorder ): ?>
					<tr>
						<th class="td-name"><?php _e("Sortorder Addon",'cpac'); ?></th>
						<td class="td-download">
							<a class="button" href="<?php echo admin_url('admin.php'); ?>?page=codepress-admin-columns&amp;info=download-add-ons&amp;cpac_product_id=cac-sortable&amp;cpac_license_key=<?php echo get_option( "cpac_sortable_ac" ); ?>"><?php _e("Download",'cpac'); ?></a>
						</td>
					</tr>
					<?php endif; ?>

					<tr>
						<th class="td-name"><?php _e("Custom Fields Addon",'cpac'); ?><?php if ( $uses_customfields ) echo '<p class="description">' . __( 'This website is currently using custom field columns.', 'cpac' ) . '</p>'; ?></th>
						<td class="td-download">
							<a class="button" href="<?php echo admin_url('admin.php'); ?>?page=codepress-admin-columns&amp;info=download-add-ons&amp;cpac_product_id=cac-custom-fields"><?php _e("Download",'cpac'); ?></a>
						</td>
					</tr>

					</tbody>
				</table>

				<h3><?php _e("Installation",'cpac'); ?></h3>

				<p><?php _e("For each Add-on available, please perform the following:",'cpac'); ?></p>
				<ol>
					<li><?php _e("Download the Addon plugin (.zip file) to your desktop",'cpac'); ?></li>
					<li><?php _e("Navigate to",'cpac'); ?> <a target="_blank" href="<?php echo admin_url('plugin-install.php?tab=upload'); ?>"><?php _e("Plugins > Add New > Upload",'cpac'); ?></a></li>
					<li><?php _e("Use the uploader to browse, select and install your Add-on (.zip file)",'cpac'); ?></li>
					<li><?php _e("Once the plugin has been uploaded and installed, click the 'Activate Plugin' link",'cpac'); ?></li>
					<li><?php _e("The Add-on is now installed and activated!",'cpac'); ?></li>
					<li><?php printf( __("For automatic updates make sure to <a href='%s'>enter your licence key</a>.",'cpac'), admin_url('admin.php?page=cpac-settings') ); ?></li>
				</ol>

			<?php endif; ?>

				<hr/>

			</div><!--.cpac-content-body-->

			<div class="cpac-content-footer">
				<a class="button-primary button-large" href="<?php echo admin_url('admin.php?page=codepress-admin-columns'); ?>"><?php _e("Start using Admin Columns",'cpac'); ?></a>
			</div><!--.cpac-content-footer-->

		</div>
		<?php

		return true;
	}

	/**
	 * Column Settings.
	 *
	 * @since 1.0.0
	 */
	public function column_settings() {

		// Load Welcome screen
		if ( $this->welcome_screen() ) return;

		// get first element from post-types
		$first = array_shift( array_values( $this->cpac->get_post_types() ) );
	?>
		<div id="cpac" class="wrap">

			<?php screen_icon( 'codepress-admin-columns' ); ?>
			<h2><?php _e( 'Admin Columns', 'cpac' ); ?></h2>

			<div class="cpac-menu">
				<ul class="subsubsub">
					<?php $count = 0; ?>
					<?php foreach ( $this->cpac->storage_models as $storage_model ) : ?>
					<li><?php echo $count++ != 0 ? ' | ' : ''; ?><a href="#cpac-box-<?php echo $storage_model->key; ?>" <?php echo $storage_model->is_menu_type_current( $first ) ? ' class="current"' : '';?> ><?php echo $storage_model->label; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php foreach ( $this->cpac->storage_models as $storage_model ) : ?>

			<div class="columns-container" data-type="<?php echo $storage_model->key ?>"<?php echo $storage_model->is_menu_type_current( $first ) ? '' : ' style="display:none"'; ?>>
				<form method="post" action="">

				<?php wp_nonce_field( 'update-type', '_cpac_nonce'); ?>
				<input type="hidden" name="cpac_key" value="<?php echo $storage_model->key; ?>" />

				<div class="columns-left">
					<div id="titlediv">
						<h2>
							<?php echo $storage_model->label; ?>
							<?php $storage_model->screen_link(); ?>
						</h2>
					</div>
				</div>

				<div class="columns-right">
					<div class="columns-right-inside">
						<div class="sidebox" id="form-actions">
							<h3>
								<?php _e( 'Store settings', 'cpac' ) ?>
							</h3>
							<?php $has_been_stored = $storage_model->get_stored_columns() ? true : false; ?>
							<div class="form-update">
								<input type="hidden" name="cpac_action" value="update_by_type" />
								<input type="submit" class="button-primary submit-update" value="<?php echo $has_been_stored ? __( 'Update' ) : __('Publish'); ?>" accesskey="u" >
							</div>
							<?php if ( $has_been_stored ) : ?>
							<div class="form-reset">
								<a href="<?php echo add_query_arg( array( 'page' => 'codepress-admin-columns', '_cpac_nonce' => wp_create_nonce('restore-type'), 'cpac_key' => $storage_model->key, 'cpac_action' => 'restore_by_type' ), admin_url("admin.php") ); ?>" class="reset-column-type" onclick="return confirm('<?php printf( __( "Warning! The %s columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", 'cpac' ), $storage_model->label ); ?>');">
									<?php _e( 'Restore', 'cpac' ); ?> <?php echo $storage_model->label; ?> <?php _e( 'columns', 'cpac' ); ?>
								</a>
							</div>
							<?php endif; ?>
						</div><!--form-actions-->

						<div class="sidebox" id="addon-state">
							<h3><?php _e( 'Addons', 'cpac' ) ?></h3>
							<div class="inside">

								<?php if ( $addons = apply_filters( 'cac/addon_list', array() ) ) : ?>
								<ul>
								<?php foreach ( $addons as $label ) : ?>
									<li><?php echo $label; ?></li>
									<?php endforeach; ?>
								</ul>
								<a href="<?php echo add_query_arg( array('page' => 'cpac-addons'), admin_url('admin.php') );?>" class="find-more-addons"><?php _e( 'find more addons', 'cpac' ); ?></a>
								<?php else : ?>
									<p>
										<?php printf( __( 'Check the <a href="%s">Add-on section</a> for more details.', 'cpac' ), add_query_arg( array('page' => 'cpac-addons'), admin_url('admin.php') ) ); ?>
									</p>
								<?php endif; ?>
							</div>
						</div>

						<div class="sidebox" id="plugin-support">
							<h3><?php _e( 'Support', 'cpac' ); ?></h3>
							<div class="inside">
								<?php if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) : ?>
									<p><?php _e( 'Check the <strong>Help</strong> section in the top-right screen.', 'cpac' ); ?></p>
								<?php endif; ?>
								<p><?php printf( __("For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", 'cpac' ), $this->get_url('documentation') ); ?></p>
							</div>
						</div><!--.form-actions-->

					</div><!--.columns-right-inside-->
				</div><!--.columns-right-->

				<div class="columns-left">
					<div class="cpac-boxes">
						<div class="cpac-columns">

							<?php
							foreach ( $storage_model->get_columns() as $column ) {
								$column->display();
							}
							?>

						</div><!--.cpac-columns-->

						<div class="column-footer">
							<div class="order-message"><?php _e( 'Drag and drop to reorder', 'cpac' ); ?></div>

							<div class="button-container">
								<a href="javascript:;" class="add_column button button-primary">+ <?php _e( 'Add Column', 'cpac' );?></a><br/>
							</div>

						</div><!--.cpac-column-footer-->
					</div><!--.cpac-boxes-->
				</div><!--.columns-left-->
				<div class="clear"></div>

				</form>

				<div class="for-cloning-only" style="display:none">
					<?php
					foreach ( $storage_model->get_registered_columns() as $column )
						$column->display();
					?>
				</div>

			</div><!--.columns-container-->

			<?php endforeach; // storage_models ?>

			<div class="clear"></div>

		</div><!--.wrap-->
	<?php
	}

	/**
	 * General Settings.
	 *
	 * @since 1.0.0
	 */
	public function general_settings() {

	?>
	<div id="cpac" class="wrap">

		<?php screen_icon( 'codepress-admin-columns' ); ?>
		<h2><?php _e( 'Admin Columns Settings', 'cpac' ); ?></h2>

		<?php do_action( 'cpac_messages' ); ?>

		<table class="form-table cpac-form-table">
			<tbody>

				<?php if ( has_action( 'cac/settings/general' ) ): ?>
				<tr class="general">
					<th scope="row">
						<h3><?php _e( 'General Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'Customize your Admin Columns settings.', 'cpac' ); ?></p>
					</th>
					<td class="padding-22">
						<div class="cpac_general">
							<form method="post" action="options.php">
								<?php settings_fields( 'cpac-general-settings' ); ?>

								<?php do_action( 'cac/settings/general', get_option( 'cpac_general_options' ) ); ?>

								<p>
									<input type="submit" class="button" value="<?php _e( 'Save' ); ?>" />
								</p>
							</form>
						</div>
					</td>
				</tr><!--.general-->
				<?php endif; ?>

				<tr class="export">
					<th scope="row">
						<h3><?php _e( 'Export Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'Pick the types for export from the left column. Click export to download your column settings.', 'cpac' ); ?></p>
						<p><a href="javascript:;" class="cpac-pointer" rel="cpac-export-instructions-html" data-pos="right"><?php _e( 'Instructions', 'cpac' ); ?></a></p>
						<div id="cpac-export-instructions-html" style="display:none;">
							<h3><?php _e( 'Export Columns Types', 'cpac' ); ?></h3>
							<p><?php _e( 'Instructions', 'cpac' ); ?></p>
							<ol>
								<li><?php _e( 'Select one or more Column Types from the left section by clicking them.', 'cpac' ); ?></li>
								<li><?php _e( 'Click export.', 'cpac' ); ?></li>
								<li><?php _e( 'Save the export file when prompted.', 'cpac' ); ?></li>
								<li><?php _e( 'Upload and import your settings file through Import Settings.', 'cpac' ); ?></li>
							</ol>
						</div>
					</th>
					<td>
						<div class="cpac_export">
							<?php if ( $groups = $this->get_export_multiselect_options() ) : ?>
							<form method="post" action="">
								<?php wp_nonce_field( 'download-export', '_cpac_nonce' ); ?>
								<select name="export_types[]" multiple="multiple" class="select" id="cpac_export_types">
									<?php
									$labels = array(
										'general'	=> __( 'General', 'cpac' ),
										'posts'		=> __( 'Posts', 'cpac' )
									);
									?>
									<?php foreach ( $groups as $group_key => $group ) : ?>
									<optgroup label="<?php echo $labels[$group_key];?>">
										<?php foreach ( $group as $storage_model ) : ?>
										<option value="<?php echo $storage_model->key; ?>"><?php echo $storage_model->label; ?></option>
										<?php endforeach; ?>
									</optgroup>
									<?php endforeach; ?>
								</select>
								<a id="export-select-all" class="export-select" href="javascript:;"><?php _e( 'select all', 'cpac' ); ?></a>
								<input type="submit" id="cpac_export_submit" class="button-primary alignright" value="<?php _e( 'Export', 'cpac' ); ?>">
							</form>
							<?php else : ?>
								<p><?php _e( 'No stored column settings are found.', 'cpac' ); ?></p>
							<?php endif; ?>
						</div>
					</td>
				</tr><!--.export-->

				<tr class="import">
					<th scope="row">
						<h3><?php _e( 'Import Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'Copy and paste your import settings here.', 'cpac' ); ?></p>
						<p><a href="javascript:;" class="cpac-pointer" rel="cpac-import-instructions-html" data-pos="right"><?php _e( 'Instructions', 'cpac' ); ?></a></p>
						<div id="cpac-import-instructions-html" style="display:none;">
							<h3><?php _e( 'Import Columns Types', 'cpac' ); ?></h3>
							<p><?php _e( 'Instructions', 'cpac' ); ?></p>
							<ol>
								<li><?php _e( 'Choose a Admin Columns Export file to upload.', 'cpac' ); ?></li>
								<li><?php _e( 'Click upload file and import.', 'cpac' ); ?></li>
								<li><?php _e( "That's it! You imported settings are now active.", 'cpac' ); ?></li>
							</ol>
						</div>
					</th>
					<td class="padding-22">
						<div id="cpac_import_input">
							<form method="post" action="" enctype="multipart/form-data">
								<input type="file" size="25" name="import" id="upload">
								<?php wp_nonce_field( 'file-import', '_cpac_nonce' ); ?>
								<input type="submit" value="<?php _e( 'Upload file and import', 'cpac' ); ?>" class="button" id="import-submit" name="file-submit">
							</form>
						</div>
					</td>
				</tr><!--.import-->

				<tr class="restore">
					<th scope="row">
						<h3><?php _e( 'Restore Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'This will delete all column settings and restore the default settings.', 'cpac' ); ?></p>
					</th>
					<td class="padding-22">
						<form method="post" action="">
							<?php wp_nonce_field( 'restore-all','_cpac_nonce'); ?>
							<input type="hidden" name="cpac_action" value="restore_all" />
							<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e( 'Restore default settings', 'cpac' ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", 'cpac' ); ?>');" />
						</form>
					</td>
				</tr><!--.restore-->

				<?php

				// Allow plugins to add their own custom settings to the settings page.

				if ( $groups = apply_filters( 'cac/settings/groups', array() ) ) {
					foreach ( $groups as $id => $group ) {

						$title 			= isset( $group['title'] ) ? $group['title'] : '';
						$description 	= isset( $group['description'] ) ? $group['description'] : '';

						?>
				<tr>
					<th scope="row">
						<h3><?php echo $title; ?></h3>
						<p><?php echo $description; ?></p>
					</th>
					<td class="padding-22">
						<?php

						/** Use this Hook to add additonal fields to the group */
						do_action( "cac/settings/groups/row={$id}" );

						?>
					</td>
				</tr>
						<?php
					}
				}

				?>

			</tbody>
		</table>

	</div>

	<?php
	}
}