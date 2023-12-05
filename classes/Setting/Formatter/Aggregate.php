<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

final class Aggregate implements Formatter
{

    /**
     * @var Formatter[]
     */
    private $data = [];

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    public static function from_settings(SettingCollection $settings): self
    {
        $formatters = [];

        foreach ($settings as $setting) {
            if ($setting instanceof Formatter) {
                $formatters[] = $setting;
            }
        }

        return new self($formatters);
    }

    public function add(Formatter $formatter): void
    {
        $this->data[] = $formatter;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $positioned_formatters = [];

        foreach ($this->data as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $positioned_formatters[$position][] = $formatter;
        }

        ksort($positioned_formatters);

        foreach ($positioned_formatters as $formatters) {
            foreach ($formatters as $formatter) {
                $value = $formatter->format($value, $options);
            }
        }

        return $value;
    }

}