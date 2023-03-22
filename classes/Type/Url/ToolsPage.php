<?php
declare( strict_types=1 );

namespace AC\Type\Url;

use AC\Admin\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type;
use ACP\Admin\Page\Tools;

class ToolsPage implements Type\QueryAware {

	use Type\QueryAwareTrait;

	public function __construct( bool $network ) {
		$url = $network
			? network_admin_url( 'settings.php' )
			: admin_url( 'options-general.php' );

		$this->set_url( $url );
		$this->add_one( RequestHandlerInterface::PARAM_PAGE, Admin::NAME );
		$this->add_one( RequestHandlerInterface::PARAM_TAB, Tools::NAME );
	}

}