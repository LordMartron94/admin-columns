<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\Input\Custom;
use AC\Setting\Input;
use AC\Settings\Setting;

abstract class Single extends Setting
{

    public function __construct(
        string $name,
        string $label,
        string $description,
        Custom $input = null
    ) {
        if (null === $input) {
            // TODO
            $input = new Custom($name);
        }

        parent::__construct($name, $label, $description, $input);
    }

}