<?php

class AC_Settings_View_Field extends AC_Settings_ViewAbstract {

	public function template() {
		foreach ( $this->elements as $element ) {
			echo $element;
		}
	}

}