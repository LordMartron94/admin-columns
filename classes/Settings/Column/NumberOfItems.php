<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\Number;
use AC\Settings;

class NumberOfItems extends Settings\Control
{

    public function __construct(int $number_of_items, Specification $specification = null)
    {
        parent::__construct(
            Number::create_single_step('number_of_items', 0, null, $number_of_items),
            __('Number of Items', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of items', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            $specification
        );
    }

}