<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

class MediaLinkFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new MediaLink(
            (string)$config->get('link_to'),
            $specification
        );
    }

}