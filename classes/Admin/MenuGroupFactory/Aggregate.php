<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;

class Aggregate implements MenuGroupFactory
{

    /**
     * @var MenuGroupFactory[]
     */
    private static $factories = [];

    public static function add(MenuGroupFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen): MenuGroup
    {
        foreach (self::$factories as $factory) {
            $group = $factory->create($table_screen);

            if ($group) {
                return $group;
            }
        }

        return new MenuGroup('other', __('Other'));
    }

}