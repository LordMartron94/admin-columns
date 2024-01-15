<?php

namespace AC\Admin\Section\Partial;

use AC\Admin\MenuListItems;

// TODO move out of section dir
class Menu
{

    private $menu_items;

    public function __construct(MenuListItems $menu_items)
    {
        $this->menu_items = $menu_items;
    }

    // TODO remove
    //    public function render(string $current, string $url, bool $is_hidden = false): string
    //    {
    //        $menu = new View([
    //            'items'       => $this->get_menu_items(),
    //            'current'     => $current,
    //            'screen_link' => $url,
    //            'class'       => $is_hidden ? 'hidden' : '',
    //        ]);
    //
    //        return $menu->set_template('admin/edit-menu')
    //                    ->render();
    //    }

    public function get_menu_items(): array
    {
        $options = [];

        foreach ($this->menu_items->all() as $item) {
            $group = $item->get_group();
            $group_name = $group->get_name();

            if ( ! isset($options[$group_name])) {
                $options[$group_name] = [
                    'title'   => $group->get_label(),
                    'icon'    => $group->has_icon() ? $group->get_icon() : '',
                    'options' => [],
                ];
            }

            $options[$group_name]['options'][$item->get_key()] = $item->get_label();
        }

        return $options;
    }

}