<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostFormat implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_format($value->get_value()) ?: null
        );
    }

}