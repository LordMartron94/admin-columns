<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Input\Custom;
use AC\Setting\Input\Number;
use AC\Setting\Input\Open;
use AC\Setting\Input\Option;
use AC\Setting\Input\Single;

final class Encoder
{

    private $settings;

    public function __construct(SettingCollection $settings)
    {
        $this->settings = $settings;
    }

    public function encode(): array
    {
        $encoded = [];

        foreach ($this->settings as $setting) {
            $encoded[] = $this->encode_setting($setting);
        }

        return $encoded;
    }

    private function encode_setting(Setting $setting): array
    {
        $encoded = [
            'name'        => $setting->get_name(),
            'label'       => $setting->get_label(),
            'description' => $setting->get_description(),
        ];

        $input = $setting->get_input();

        if ($input) {
            $encoded['input']['type'] = $input->get_type();

            if ($input->has_default()) {
                $encoded['input']['default'] = $input->get_default();
            }

            if ($input instanceof Open) {
                if ($input->has_append()) {
                    $encoded['input']['append'] = $input->get_append();
                }
            }

            if ($input instanceof Custom) {
                $encoded['input']['data'] = $input->get_data();
            }

            if ($input instanceof Option) {
                $encoded['input'] += [
                    'options'  => $this->encode_options($input->get_options()),
                    'multiple' => $input instanceof Option\Multiple,
                ];
            }

            if ($input instanceof Number) {
                if ($input->has_min()) {
                    $encoded['input']['min'] = $input->get_min();
                }

                if ($input->has_max()) {
                    $encoded['input']['max'] = $input->get_max();
                }

                if ($input->has_step()) {
                    $encoded['input']['step'] = $input->get_step();
                }
            }
        }

        if ($setting instanceof Recursive) {
            $encoded['is_parent'] = $setting->is_parent();
            $encoded['children'] = [];

            foreach ($setting->get_children() as $child) {
                $encoded['children'][] = $this->encode_setting($child);
            }
        }

        $conditions = $setting->get_conditions();

        if ($conditions) {
            $encoded['conditions'] = $conditions->get_rules($setting->get_name());
        }

        return $encoded;
    }

    private function encode_options(OptionCollection $options): array
    {
        $encoded = [];

        foreach ($options as $option) {
            $encoded[] = [
                'value' => $option->get_value(),
                'label' => $option->get_label(),
                'group' => $option->get_group(),
            ];
        }

        return $encoded;
    }

}