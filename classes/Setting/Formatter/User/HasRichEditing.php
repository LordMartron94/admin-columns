<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class HasRichEditing implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value('true' === get_userdata($value->get_id())->rich_editing);
    }

}