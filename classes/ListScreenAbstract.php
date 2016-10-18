<?php
defined( 'ABSPATH' ) or die();

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class AC_ListScreenAbstract {

	/**
	 * Identifier for List Screen; Post type etc.
	 *
	 * @since 2.0
	 */
	protected $key;

	/**
	 * @since 2.0
	 */
	protected $label;

	/**
	 * @since 2.3.5
	 */
	protected $singular_label;

	/**
	 * Type of list screen; Post, Media, User or Comments
	 *
	 * @since 2.0
	 */
	protected $type;

	/**
	 * Meta type of list screen; post, user, comment. Mostly used for custom field data.
	 *
	 * @since 3.0
	 */
	protected $meta_type;

	/**
	 * Groups the list screen in the menu.
	 *
	 * @since 2.0
	 */
	protected $menu_type;

	/**
	 * Name of the base PHP file (without extension)
	 *
	 * @since 2.0
	 * @var string
	 */
	protected $base;

	/**
	 * Page menu slug. Applies only when a menu page is used.
	 *
	 * @since 2.4.10
	 * @var string
	 */
	protected $page;

	/**
	 * Class name of the WP_List_Table instance
	 *
	 * @since NEWVERSION
	 * @var string
	 */
	protected $list_table;

	/**
	 * The unique ID of the screen.
	 *
	 * @see get_current_screen()
	 *
	 * @since 2.5
	 * @var string
	 */
	protected $screen;

	/**
	 * @var AC_Settings_Columns $settings
	 */
	private $settings;

	/**
	 * @var AC_ColumnManager $columns
	 */
	private $columns;

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract function set_manage_value_callback();

	// TODO: user getters and setters, make vars protected

	public function __get( $var ) {
		$vars = array(
			'type',
			'key',
		);

		if ( in_array( $var, $vars ) ) {
			return call_user_func( array( $this, 'get_' . $var ) );
		}
	}

	/**
	 * @since 2.4.4
	 */
	public function __construct() {
		$this->menu_type = __( 'Other', 'codepress-admin-columns' );
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_singular_label() {
		return $this->singular_label;
	}

	/**
	 * @param string $key
	 */
	public function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * Return a single object based on it's ID (post, user, comment etc.)
	 *
	 * @since NEWVERSION
	 * @return mixed
	 */
	protected function get_object_by_id( $id ) {
		return null;
	}

	/**
	 * Get a single row from list table
	 *
	 * @param int $object_id Object ID
	 *
	 * @since NEWVERSION
	 *
	 * @return false|string HTML
	 */
	public function get_single_row( $object_id ) {
		return false;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * ID attribute of targeted list table
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_screen_id() {
		return $this->screen;
	}

	/**
	 * @since 2.0.3
	 * @return boolean
	 */
	public function is_current_screen() {
		$screen = get_current_screen();

		return $screen && $screen->id === $this->screen;
	}

	/**
	 * Set menu type
	 *
	 * @since 2.4.1
	 *
	 * @return AC_ListScreenAbstract
	 */
	public function set_menu_type( $menu_type ) {
		$this->menu_type = $menu_type;

		return $this;
	}

	/**
	 * @since 2.5
	 */
	public function get_menu_type() {
		return $this->menu_type;
	}

	/**
	 * Are column set by third party plugin
	 *
	 * @since 2.3.4
	 */
	public function is_using_php_export() {

		/**
		 * @since NEWVERSION
		 */
		return apply_filters( 'ac/list_screen/is_using_php_export', false, $this );
	}

	/**
	 * @since 2.3.4
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->page ), admin_url( $this->base . '.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {

		/**
		 * @since NEWVERSION
		 */
		return apply_filters( 'ac/list_screen/edit_link', add_query_arg( array( 'cpac_key' => $this->key ), AC()->settings()->get_link( 'columns' ) ) );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_default_columns() {
		return array();
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return WP_List_Table|false
	 */
	public function get_list_table( $args = array() ) {
		return class_exists( $this->list_table ) ? new $this->list_table( $args ) : false;
	}

	/**
	 * @return AC_Settings_Columns
	 */
	public function settings() {
		if ( null === $this->settings ) {
			$this->settings = new AC_Settings_Columns( $this->key );
		}

		return $this->settings;
	}

	/**
	 * @return AC_ColumnManager
	 */
	public function columns() {
		if ( null === $this->columns ) {
			$this->columns = new AC_ColumnManager( $this );
		}

		return $this->columns;
	}

}