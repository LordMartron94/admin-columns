<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Settings\Control;

// TODO implement formatter with '<span class="cpac_use_icons"></span>'

class ActionIcons extends Control
{

    public function __construct(bool $use_icons, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_toggle('use_icons', null, $use_icons ? 'on' : 'off'),
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            $specification
        );
    }

}