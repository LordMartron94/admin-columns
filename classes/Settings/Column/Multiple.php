<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Option;
use AC\Setting\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Settings\Column;

abstract class Multiple extends Column implements Option
{

    use SettingTrait;
    use AC\Setting\OptionTrait;

    public function __construct(AC\Column $column, string $name, OptionCollection $options)
    {
        if (null === $this->input) {
            $this->input = AC\Setting\Input\Multiple::create_select();
        }

        $this->options = $options;
        $this->name = $name;

        parent::__construct($column);
    }

}