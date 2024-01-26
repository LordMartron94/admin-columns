<?php

namespace AC\Column\Media;

use AC;
use AC\Column;

class Date extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('date');
    }

    public function register_settings()
    {
        $this->add_setting(new AC\Settings\Column\Width(10, '%'));
    }

}