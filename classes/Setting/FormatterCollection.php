<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;
use InvalidArgumentException;

final class FormatterCollection extends Collection implements Countable
{

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    /**
     * @param Formatter|CollectionFormatter $formatter
     */
    public function add($formatter): void
    {
        if ( ! $formatter instanceof Formatter && ! $formatter instanceof FormatterCollection) {
            throw new InvalidArgumentException();
        }

        $this->data[] = $formatter;
    }

    /**
     * @return Formatter|CollectionFormatter
     */
    public function current()
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }

}