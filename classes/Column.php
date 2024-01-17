<?php

declare(strict_types=1);

namespace AC;

use AC\Column\LabelEncoder;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class Column
{

    /**
     * @var string Unique type
     */
    private $type;

    /**
     * @var string|null Label which describes this column
     */
    private $label;

    /**
     * @var string Unique Name
     */
    private $name = '';

    /**
     * @var bool An original column will use the already defined column value and label.
     */
    private $original = false;

    /**
     * @var Settings\Column[]
     */
    private $settings;

    /**
     * @var Settings\FormatValue[]|Settings\FormatCollection[]
     */
    private $formatters;

    private $group = 'custom';

    protected $options = [];

    protected $meta_type = '';

    protected $post_type = '';

    protected $taxonomy = '';

    /**
     * @deprecated NEWVERSION
     * @var ListScreen
     */
    protected $list_screen;

    public function get_name(): string
    {
        return $this->name;
    }

    public function set_name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function set_type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function get_meta_type(): string
    {
        return $this->meta_type;
    }

    public function set_meta_type(string $meta_type): self
    {
        $this->meta_type = $meta_type;

        return $this;
    }

    public function get_list_singular_label(): string
    {
        return (string)$this->label;
    }

    public function get_label(): string
    {
        return (string)$this->label;
    }

    public function set_label(string $label = null): self
    {
        $this->label = $label;

        return $this;
    }

    public function get_group(): string
    {
        return $this->group;
    }

    public function set_group(string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function get_post_type(): string
    {
        return $this->post_type;
    }

    public function set_taxonomy(string $taxonomy): self
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    public function get_taxonomy()
    {
        return $this->taxonomy;
    }

    public function set_post_type(string $post_type): self
    {
        $this->post_type = $post_type;

        return $this;
    }

    /**
     * Return true when a default column has been replaced by a custom column.
     * An original column will then use the original label and value.
     */
    public function is_original(): bool
    {
        return $this->original;
    }

    public function set_original(bool $boolean): self
    {
        $this->original = $boolean;

        return $this;
    }

    public function add_setting(Settings\Column $setting): self
    {
        // TODO David Check
        //$setting->set_values($this->options);

        $this->settings[$setting->get_name()] = $setting;

        // TODO David check
        //        foreach ((array)$setting->get_dependent_settings() as $dependent_setting) {
        //            $this->add_setting($dependent_setting);
        //        }

        return $this;
    }

    /**
     * @param string $id Settings ID
     */
    public function remove_setting($id)
    {
        if (isset($this->settings[$id])) {
            unset($this->settings[$id]);
        }
    }

    public function get_setting($name): ?Settings\Column
    {
        return $this->settings[$name] ?? null;

        // TODO David, reimplement
        return null;

        return $this->get_settings()->get($id);
    }

    public function get_formatters(): array
    {
        if (null === $this->formatters) {
            $this->formatters = [];

            foreach ($this->get_settings() as $setting) {
                if ($setting instanceof Settings\FormatValue || $setting instanceof Settings\FormatCollection) {
                    $this->formatters[] = $setting;
                }
            }
        }

        return $this->formatters;
    }

    public function get_custom_label(): string
    {
        $label = $this->get_option('label') ?: $this->get_type();
        $label = (new LabelEncoder())->decode($label);

        return (string)apply_filters('ac/headings/label', $label, $this);
    }

    /**
     * @return SettingCollection
     */
    public function get_settings()
    {
        if (null === $this->settings) {
            $settings = [

                // TODO remove
                new Settings\Column\Type(),

                new Settings\Column\Label(),
                new Settings\Column\Width(),
            ];

            // TODO Stefan test settings
            //$settings[] = new Settings\Column\ActionIcons($this);
            //$settings[] = new Settings\Column\AttachmentDisplay($this);
            //$settings[] = new Settings\Column\Comment($this);
            //$settings[] = new Settings\Column\CustomFieldType($this);

            foreach ($settings as $setting) {
                $this->add_setting($setting);
            }

            // TODO David check

            //$this->register_settings_temp();
            $this->register_settings();

            // TODO
            do_action('ac/column/settings', $this);
        }

        return new SettingCollection($this->settings);
    }

    /**
     * Register settings
     */
    protected function register_settings()
    {
        // Overwrite in child class
    }

    // TODO Stefan Only for test
    protected function register_settings_temp()
    {
        // Overwrite in child class
    }

    /**
     * @param string $key
     *
     * @return null|string|bool
     */
    public function get_option($key)
    {
        return $this->options[$key] ?? null;
    }

    public function set_options(array $options): void
    {
        $this->options = $options;
    }

    public function get_options(): array
    {
        return $this->options;
    }

    public function set_option(string $key, $value): void
    {
        $this->options[$key] = $value;
    }

    /**
     * Enqueue CSS + JavaScript on the admin listings screen!
     * This action is called in the admin_head action on the listings screen where your column values are displayed.
     * Use this action to add CSS + JavaScript
     * @since 2.3.4
     */
    public function scripts(): void
    {
        // Overwrite in child class
    }


    /**
     * Apply available formatters (recursive) on the value
     *
     * @param mixed $value
     * @param mixed $original_value
     * @param int   $current Current index of self::$formatters
     *
     * @return mixed
     */

    // TODO
    //public function get_formatted_value($value, $original_value = null, $current = 0)
    //    public function get_formatted_value($value, int $id = null) : string
    //    {
    //        $formatters = $this->get_formatters();
    //        $available = count($formatters);
    //
    //        if (null === $original_value) {
    //            $original_value = $value;
    //        }
    //
    //        if ($available > $current) {
    //            $is_collection = $value instanceof Collection;
    //            $is_value_formatter = $formatters[$current] instanceof Settings\FormatValue;
    //
    //            if ($is_collection && $is_value_formatter) {
    //                foreach ($value as $k => $v) {
    //                    $value->put($k, $this->get_formatted_value($v, null, $current));
    //                }
    //
    //                while ($available > $current) {
    //                    if ($formatters[$current] instanceof Settings\FormatCollection) {
    //                        return $this->get_formatted_value($value, $original_value, $current);
    //                    }
    //
    //                    ++$current;
    //                }
    //            } elseif (($is_collection && ! $is_value_formatter) || $is_value_formatter) {
    //                $value = $formatters[$current]->format($value, $original_value);
    //
    //                return $this->get_formatted_value($value, $original_value, ++$current);
    //            }
    //        }
    //
    //        return $value;
    //    }

    // TODO David check if $id cannot be null
    // TODO David can tis method be protected/private, even just by comment if need be
    public function get_formatted_value($value, int $id = null): string
    {
        $formatter = Formatter\Aggregate::from_settings($this->get_settings());

        if ($value instanceof ValueCollection) {
            $formatted_values = [];
            foreach ($value as $single_value) {
                $formatted_values[] = (string)$formatter->format(
                    new Value($single_value->get_id(), $single_value->get_value()),
                    new ArrayImmutable($this->get_options())
                );
            }

            return implode($this->get_separator(), $formatted_values);
        }

        $formatter = Formatter\Aggregate::from_settings($this->get_settings());

        return (string)$formatter->format(
            new Value($id, $value),
            new ArrayImmutable($this->get_options())
        );

        //        foreach( $this->options as $option ) {
        //
        //        }
        //
        //        foreach( $this->settings as $setting ) {
        //
        //        }
        //
        //        $setting = new User( $this, StringComparisonSpecification::equal('') );
        //
        //
        //        $value = $formatter->format( $value );
    }

    /**
     * Get the raw, underlying value for the column
     * Not suitable for direct display, use get_value() for that
     *
     * @param int $id
     *
     * @return string|array
     */
    public function get_raw_value($id)
    {
        return null;
    }

    /**
     * Display value
     *
     * @param int $id
     *
     * @return string
     */
    public function get_value($id)
    {
        $value = $this->get_formatted_value($this->get_raw_value($id), (int)$id);

        if ($value instanceof Collection) {
            $value = $value->filter()->implode($this->get_separator());
        }

        if ( ! $this->is_original() && ac_helper()->string->is_empty($value)) {
            $value = $this->get_empty_char();
        }

        return (string)$value;
    }

    public function get_separator(): string
    {
        $default_separator = $this->get_option('separator') ?: ', ';
        switch ($default_separator) {
            case 'comma' :
                return ', ';
            case 'newline' :
                return "<br/>";
            case 'none' :
                return '';
            case 'white_space' :
                return '&nbsp;';
            case 'horizontal_rule' :
                return '<hr>';
            default :
                return (new ApplyFilter\ColumnSeparator($this))->apply_filters(', ');
        }
    }

    public function get_empty_char(): string
    {
        return '&ndash;';
    }

    public function toArray(): array
    {
        return $this->options;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_list_screen(): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');
    }

    /**
     * @deprecated NEWVERSION
     */
    public function set_list_screen(ListScreen $list_screen): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');
    }

    /**
     * Overwrite this function in child class.
     * Determine whether this column type should be available
     * @return bool Whether the column type should be available
     * @since      2.2
     * @deprecated NEWVERSION
     */
    public function is_valid(): bool
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return true;
    }

}