<?php

namespace AC\Plugin\SetupFactory;

use AC\Plugin\Install;
use AC\Plugin\InstallCollection;
use AC\Plugin\SetupFactory;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;

final class AdminColumns extends SetupFactory {

	public function __construct( $version_key, Version $version ) {
		parent::__construct(
			$version_key,
			$version,
			new InstallCollection( [
				new Install\Capabilities(),
				new Install\Database(),
			] ),
			new UpdateCollection( [
				new Update\V3005(),
				new Update\V3007(),
				new Update\V3201(),
				new Update\V4000(),
			] )
		);
	}

}