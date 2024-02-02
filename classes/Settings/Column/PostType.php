<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Settings;

class PostType extends Settings\Column
{

    private $show_any;

    public function __construct(string $post_type = null, bool $show_any = false, Specification $specification = null)
    {
        $input = Setting\Component\Input\OptionFactory::create_select(
            'post_type',
            $this->create_options(),
            $post_type
        );

        parent::__construct(
            __('Post Type', 'codepress-admin-columns'),
            null,
            $input,
            $specification
        );

        $this->show_any = $show_any;
    }

    public function create_options(): Setting\Component\OptionCollection
    {
        $options = $this->get_post_type_labels();

        if ($this->show_any) {
            $options = ['any' => __('All Post Types', 'codepress-admin-columns')] + $options;
        }

        return Setting\Component\OptionCollection::from_array($options);
    }

    private function add_slug_to_duplicate_post_type_label(array $options): array
    {
        $values = array_values($options);

        // Add slug to duplicate post type labels
        foreach ($options as $k => $label) {
            if (count(array_keys($values, $label)) > 1) {
                $options[$k] .= sprintf(' (%s)', $k);
            }
        }

        return $options;
    }

    private function get_post_type_labels(): array
    {
        $options = [];

        $post_types = get_post_types();

        if ( ! is_array($post_types)) {
            return $options;
        }

        foreach ($post_types as $post_type) {
            $post_type_object = get_post_type_object($post_type);
            $options[$post_type] = $post_type_object->labels->name;
        }

        $options = $this->add_slug_to_duplicate_post_type_label($options);

        natcasesort($options);

        return $options;
    }

}