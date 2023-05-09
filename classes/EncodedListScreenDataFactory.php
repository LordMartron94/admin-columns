<?php

namespace AC;

final class EncodedListScreenDataFactory {

	private static $instance;

	public static function create(): EncodedListScreenData {
		if ( self::$instance === null ) {
			self::$instance = new EncodedListScreenData();
		}

		return self::$instance;
	}
}