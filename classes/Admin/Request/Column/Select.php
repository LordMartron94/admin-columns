<?php

namespace AC\Admin\Request\Column;

use AC;
use AC\Admin\Request\Column;

class Select extends Column {

	public function __construct( AC\ListScreenRepository\Aggregate $repo ) {
		parent::__construct( $repo, 'select' );
	}

	public function get_column( AC\Request $request, AC\ListScreen $list_screen ) {
		return $list_screen->get_column_by_type( $request->get( 'type' ) );
	}

}