<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class MapToId implements Formatter
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        $id_value = $this->formatter->format($value);

        if ($id_value->get_value() && is_int($id_value->get_value())) {
            return new Value($id_value);
        }

        return new Value(null);
    }

}