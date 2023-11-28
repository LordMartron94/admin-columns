<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class ForeignId implements Formatter
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        return new Value(
        //$this->formatter->format(new Value($value->get_value()))->get_value(),
            $value->get_id()
        );
    }

}