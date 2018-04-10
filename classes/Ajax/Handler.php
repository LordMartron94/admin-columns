<?php

class AC_Ajax_Handler {

	const NONCE_KEY = 'ac-ajax';

	/**
	 * @var array
	 */
	protected $params;

	/**
	 * @var string|array
	 */
	protected $callback;

	public function __construct() {
		$this->set_nonce();
	}

	/**
	 * @throws Exception
	 */
	public function register() {
		if ( ! $this->get_action() ) {
			throw new Exception( 'Action parameter is missing.' );
		}

		if ( ! $this->get_callback() ) {
			throw new Exception( 'A callback is missing.' );
		}

		add_action( $this->get_action(), $this->get_callback() );
	}

	/**
	 * @return string|null
	 */
	public function get_action() {
		return $this->get_param( 'action' );
	}

	/**
	 * @param string $action
	 *
	 * @return $this
	 */
	public function set_action( $action ) {
		$prefix = 'wp_ajax_';

		if ( strpos( $action, $prefix ) !== 0 ) {
			$action = $prefix . $action;
		}

		$this->set_param( 'action', $action );

		return $this;
	}

	/**
	 * @param string|array $callback
	 *
	 * @return $this
	 */
	public function set_callback( $callback ) {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * @return array|string
	 */
	public function get_callback() {
		return $this->callback;
	}

	/**
	 * @return $this
	 */
	protected function set_nonce() {
		$this->set_param( 'nonce', wp_create_nonce( self::NONCE_KEY ) );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function no_nonce() {
		unset( $this->params['nonce'] );

		return $this;
	}

	public function verify_request() {
		check_ajax_referer( self::NONCE_KEY );
	}

	/**
	 * @return array
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	public function get_param( $key ) {
		if ( ! array_key_exists( $key, $this->params ) ) {
			return null;
		}

		return $this->params[ $key ];
	}

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function set_params( array $params ) {
		foreach ( $params as $key => $value ) {
			$this->set_param( $key, $value );
		}

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function set_param( $key, $value ) {
		switch ( $key ) {
			case 'action':
				$this->set_action( $value );

				break;
			default:
				$this->params[ $key ] = $value;
		}

		return $this;
	}

}