<?php

namespace AC\Settings\Column;

use AC;
use AC\Column\LabelEncoder;
use AC\Sanitize\Kses;
use AC\Setting\SettingTrait;
use AC\Settings;
use AC\View;

class Label extends Settings\Column
{

    public function __construct(AC\Column $column)
    {
        $this->name = 'label';
        $this->label = __('Label', 'codepress-admin-columns');

        parent::__construct($column);
    }

    //    /**
    //     * @var string
    //     */
    //    private $label;
    //
    //    protected function define_options()
    //    {
    //        return [
    //            'label'      => $this->column->get_label(),
    //            'label_type' => 'text',
    //        ];
    //    }
    //
    //    public function create_view()
    //    {
    //        $setting = $this
    //            ->create_element('text')
    //            ->set_attribute('placeholder', $this->column->get_label());
    //
    //        $view = new View([
    //            'label'   => __('Label', 'codepress-admin-columns'),
    //            'tooltip' => __('This is the name which will appear as the column header.', 'codepress-admin-columns'),
    //            'setting' => $setting,
    //        ]);
    //
    //        $view->set_template('settings/setting-label');
    //
    //        return $view;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_label()
    //    {
    //        return (new LabelEncoder())->decode($this->label);
    //    }
    //
    //    /**
    //     * @param string $label
    //     */
    //    public function set_label($label)
    //    {
    //        $sanitize = new Kses();
    //
    //        $this->label = (string)apply_filters('ac/column/label', $sanitize->sanitize((string)$label), $label);
    //    }
    //
    //    public function get_config(): ?array
    //    {
    //        return [
    //            'type'        => 'text',
    //            'key'         => $this->get_name(),
    //            'label'       => __('Label', 'codepress-admin-columns'),
    //            'placeholder' => $this->column->get_label(),
    //            'default'     => $this->get_default(),
    //        ];
    //    }

}