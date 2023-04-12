<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class Network implements Filter {

	public const KEYS = [
		'wp-ms_sites',
		'wp-ms_users',
	];

	protected function is_network( string $list_key ): bool {
		return in_array( $list_key, self::KEYS, true );
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		$collection = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->is_network( $list_screen->get_key() ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

}