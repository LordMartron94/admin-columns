<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;
use AC\Preferences;

class ListScreenSource extends ScreenOption {

	private const KEY = 'show_list_screen_source';

	/**
	 * @var Preferences\User
	 */
	private $preference;

	public function __construct( Preference\ScreenOptions $preference ) {
		$this->preference = $preference;
	}

	public function is_active() {
		return 1 === $this->preference->get( self::KEY );
	}

	public function render() {
		ob_start();
		?>

		<label for="ac-list-screen-source" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-list-screen-source" type="checkbox" <?php checked( $this->is_active() ); ?>>
			<?= __( 'Source', 'codepress-admin-columns' ); ?>
		</label>
		<?php
		return ob_get_clean();
	}

}