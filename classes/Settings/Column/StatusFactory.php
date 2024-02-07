<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings;
use AC\Settings\Component;
use AC\Settings\Setting;

class StatusFactory implements Settings\SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Status(
            '1' === $config->get('use_icon'),
            $specification
        );
    }

}