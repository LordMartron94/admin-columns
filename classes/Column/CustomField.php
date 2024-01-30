<?php

namespace AC\Column;

use AC\Column;
use AC\MetaType;
use AC\Setting\SettingCollection;
use AC\Settings;

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all list screens supporting WordPress' default way of handling meta data.
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 */
class CustomField extends Column implements Value
{

    private $meta_type;

    public function __construct(MetaType $meta_type, SettingCollection $settings)
    {
        parent::__construct(
            'column-meta',
            __('Custom Field', 'codepress-admin-columns'),
            $settings,
            'custom_field'
        );

        $this->meta_type = $meta_type;
    }

    public function renderable(): Renderable
    {
        return new Column\Post\Renderable\CustomField(
            new Renderable\ValueFormatter($this->settings),
            $this->meta_type,
            // TODO
            'text'
        );
    }

//    public function get_meta_key(): string
//    {
//        return (string)$this->get_option('field');
//    }
//
//    private function is_acp_active(): bool
//    {
//        return defined('ACP_FILE');
//    }
//
//    public function register_settings()
//    {
//        $this->add_setting(new Settings\Column\CustomField())
//             ->add_setting(new Settings\Column\BeforeAfter());
//
//        // TODO
//        //        if ( ! $this->is_acp_active()) {
//        //            $this->add_setting(new Settings\Column\Pro\Sorting($this))
//        //                 ->add_setting(new Settings\Column\Pro\InlineEditing($this))
//        //                 ->add_setting(new Settings\Column\Pro\BulkEditing($this))
//        //                 ->add_setting(new Settings\Column\Pro\SmartFiltering($this))
//        //                 ->add_setting(new Settings\Column\Pro\Export($this));
//        //        }
//    }
//
//    /**
//     * @return string e.g. excerpt|color|date|numeric|image|has_content|link|checkmark|library_id|title_by_id|user_by_id|array|count
//     * @see Settings\Column\CustomFieldType
//     */
//    public function get_field_type(): ?string
//    {
//        return (string)$this->get_option('field_type');
//    }
//
//    public function get_field(): string
//    {
//        return $this->get_meta_key();
//    }

}