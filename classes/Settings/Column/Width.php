<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;

final class Width extends Recursive
{

    private $default;

    public function __construct(int $default = null)
    {
        parent::__construct(
            'width',
            __('Width', 'codepress-admin-columns'),
            '',
            new Input\Custom('width')
        );

        $this->default = $default;
    }

    public function with_default(int $default): self
    {
        // TODO obsolete
        return new self($default);
    }

    public function get_children(): SettingCollection
    {
        $settings = [
            new AC\Settings\Column(
                $this->name,
                '',
                '',
                Input\Number::create_single_step(0, null, $this->default)
            ),
            new AC\Settings\Column(
                $this->name . '_unit',
                '',
                '',
                Input\Option\Single::create_radio(
                    OptionCollection::from_array([
                        '%',
                        'px',
                    ], false)
                )
            ),
        ];

        return new SettingCollection($settings);
    }

    // TODO
    //    /**
    //     * @var integer
    //     */
    //    private $width;
    //
    //    /**
    //     * @var string
    //     */
    //    private $width_unit;
    //
    //    protected function define_options()
    //    {
    //        return [
    //            'width',
    //            'width_unit' => '%',
    //        ];
    //    }
    //
    //    private function get_valid_width_units()
    //    {
    //        return [
    //            '%'  => '%',
    //            'px' => 'px',
    //        ];
    //    }
    //
    //    private function is_valid_width_unit($width_unit)
    //    {
    //        return array_key_exists($width_unit, $this->get_valid_width_units());
    //    }
    //
    //    public function create_view()
    //    {
    //        $width = $this->create_element('text')
    //                      ->set_attribute('placeholder', __('Auto', 'codepress-admin-columns'))
    //                      ->set_attribute('data-width-input', '');
    //
    //        $unit = $this->create_element('radio', 'width_unit')
    //                     ->set_attribute('data-unit-input', '')
    //                     ->set_options($this->get_valid_width_units());
    //
    //        $section = new View([
    //            'width' => $width,
    //            'unit'  => $unit,
    //        ]);
    //        $section->set_template('settings/setting-width');
    //
    //        $view = new View([
    //            'label'    => __('Width', 'codepress-admin-columns'),
    //            'sections' => [$section],
    //        ]);
    //
    //        return $view;
    //    }
    //
    //    public function create_header_view()
    //    {
    //        $column_width = $this->get_column_width();
    //
    //        return new View([
    //            'title'   => __('width', 'codepress-admin-columns'),
    //            'content' => $column_width ? $column_width->get_value() . $column_width->get_unit() : '',
    //        ]);
    //    }
    //
    //    /**
    //     * @return ColumnWidth|null
    //     */
    //    public function get_column_width()
    //    {
    //        try {
    //            $column_width = new ColumnWidth(
    //                $this->width_unit,
    //                $this->width
    //            );
    //        } catch (InvalidArgumentException $e) {
    //            return null;
    //        }
    //
    //        return $column_width;
    //    }
    //
    //    /**
    //     * @return int
    //     */
    //    public function get_width()
    //    {
    //        return $this->width;
    //    }
    //
    //    /**
    //     * @param $value
    //     *
    //     * @return bool
    //     */
    //    public function set_width($value)
    //    {
    //        // Backwards compatible for AC 2.9
    //        if ('' === $value) {
    //            $this->width = $value;
    //
    //            return true;
    //        }
    //
    //        $value = absint($value);
    //
    //        if ($value > 0) {
    //            $this->width = $value;
    //
    //            return true;
    //        }
    //
    //        return false;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_width_unit()
    //    {
    //        return $this->width_unit;
    //    }
    //
    //    /**
    //     * @param string $width_unit
    //     *
    //     * @return bool
    //     */
    //    public function set_width_unit($width_unit)
    //    {
    //        if ( ! $this->is_valid_width_unit($width_unit)) {
    //            return false;
    //        }
    //
    //        $this->width_unit = $width_unit;
    //
    //        return true;
    //    }
    //
    //    public function get_config(): ?array
    //    {
    //        return [
    //            'type'    => 'width',
    //            'default' => '',
    //            'label'   => __('Width', 'codepress-admin-columns'),
    //        ];
    //    }

}