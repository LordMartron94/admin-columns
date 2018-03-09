<?php

class AC_Notice_Global extends AC_Notice {

	/**
	 * @var bool
	 */
	protected $dismissible;

	/**
	 * @var array
	 */
	protected $dismissible_callback;

	public function display() {
		$data = array(
			'message'              => $this->message,
			'type'                 => $this->type,
			'dismissible'          => $this->dismissible,
			'dismissible_callback' => $this->dismissible_callback,
		);

		$view = new AC_View( $data );
		$view->set_template( 'message/notice' );

		echo $view;
	}

	/**
	 * @return bool
	 */
	public function is_dismissible() {
		return $this->dismissible;
	}

	/**
	 * @param $dismissible
	 */
	public function set_dismissible( $dismissible ) {
		$this->dismissible = (bool) $dismissible;

		return $this;
	}

	/**
	 * @param string $callback
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function set_dismissible_callback( $callback, array $params = array() ) {
		$this->set_dismissible( true );

		$this->dismissible_callback = array_merge( $params, array(
			'action'      => $callback,
			'_ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		) );

		return $this;
	}

	/**
	 * @param null|string $class
	 *
	 * @return $this
	 */
	protected function set_class( $class = null ) {
		if ( null === $class ) {
			if ( $this->type === self::SUCCESS ) {
				$class = 'updated';
			}

			if ( $this->type === self::ERROR ) {
				$class = 'error';
			}
		}

		parent::set_class( $class );

		return $this;
	}

	/**
	 * Enqueue scripts & styles
	 */

	// TODO: when is this called?
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . 'assets/css/notice.css', array(), AC()->get_version() );

		if ( $this->is_dismissible() ) {
			wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice-dismiss.js', array(), AC()->get_version(), true );
		}
	}

}