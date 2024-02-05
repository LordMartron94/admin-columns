<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use InvalidArgumentException;

final class Width extends Column implements AC\Setting\Recursive
{

    private $default;

    private $default_unit;

    public function __construct(int $default = null, string $default_unit = null)
    {
        parent::__construct(
            'width',
            __('Width', 'codepress-admin-columns')
        );

        $this->default = $default;
        $this->default_unit = $default_unit ?? 'px';

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! in_array($this->default_unit, ['%', 'px'], true)) {
            throw new InvalidArgumentException('Invalid width unit');
        }
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): SettingCollection
    {
        $settings = [
            new Column(
                '',
                '',
                AC\Setting\Component\Input\Number::create_single_step(
                    'width',
                    0,
                    null,
                    $this->default
                )
            ),
            new Column(
                '',
                '',
                AC\Setting\Component\Input\OptionFactory::create_radio(
                    'width_unit',
                    OptionCollection::from_array([
                        '%',
                        'px',
                    ], false),
                    $this->default_unit
                )
            ),
        ];

        return new SettingCollection($settings);
    }

}