<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;
use WP_Term;

class LinkToMenu extends Settings\Column implements Formatter
{

    public function __construct(Specification $conditions = null)
    {
        parent::__construct(
            'link_to_menu',
            __('Link to menu', 'codepress-admin-columns'),
            __('This will make the title link to the menu.', 'codepress-admin-columns'),
            OptionFactory::create_toggle('link_to_menu', null, 'on'),
            $conditions
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $menu_ids = $value->get_value();

        if ( ! $menu_ids) {
            return $value->with_value(false);
        }

        $values = [];

        foreach ($menu_ids as $menu_id) {
            $term = get_term_by('id', $menu_id, 'nav_menu');

            if ( ! $term instanceof WP_Term) {
                continue;
            }

            $label = $term->name;

            if ('on' === $options->get('link_to_menu')) {
                $label = ac_helper()->html->link(
                    add_query_arg(['menu' => $menu_id], admin_url('nav-menus.php')),
                    $label
                );
            }

            $values[] = $label;
        }

        return $value->with_value(wp_sprintf('%l', $values));
    }
}