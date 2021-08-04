<?php

namespace AC\Admin;

use AC\Admin\Menu\Item;
use AC\Deprecated\Hooks;
use AC\Integration\Filter;
use AC\IntegrationRepository;

class MenuFactory implements MenuFactoryInterface {

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var IntegrationRepository
	 */
	private $integration_repository;

	public function __construct( $url, $integration_repository ) {
		$this->url = $url;
		$this->integration_repository = $integration_repository;
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				RequestHandler::PARAM_PAGE => Admin::NAME,
				RequestHandler::PARAM_TAB  => $slug,
			],
			$this->url
		);
	}

	private function get_recommended_integrations() {
		return $this->integration_repository->find_all( [
			IntegrationRepository::ARG_FILTER => [
				new Filter\IsNotActive( is_multisite(), is_network_admin() ),
				new Filter\IsPluginActive(),
			],
		] );
	}

	public function create( $current ) {
		$menu = new Menu();

		$items = [
			Page\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Page\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Page\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$integrations = $this->get_recommended_integrations();

		if ( $integrations->exists() ) {
			$items[ Page\Addons::NAME ] = sprintf( '%s %s', $items[ Page\Addons::NAME ], '<span class="ac-badge">' . $integrations->count() . '</span>' );
		}

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$items[ Page\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' );
		}

		foreach ( $items as $slug => $label ) {
			$menu->add_item( new Item( $this->create_menu_link( $slug ), $label, $current === $slug ? '-active' : '' ) );
		}

		do_action( 'ac/admin/page/menu', $menu );

		return $menu;
	}

}