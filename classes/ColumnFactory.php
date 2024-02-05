<?php

declare(strict_types=1);

namespace AC;

use AC\Setting\Config;

// TODO remove
class ColumnFactory
{

    private $column_types_factory;

    public function __construct(ColumnTypesFactory $column_types_factory)
    {
        $this->column_types_factory = $column_types_factory;
    }

    private function get_column_types(TableScreen $table_screen): ColumnTypeCollection
    {
        static $types;

        $key = (string)$table_screen->get_key();

        if ( ! isset($types[$key])) {
            // TODO add `Config`
            $types[$key] = $this->column_types_factory->create($table_screen);
        }

        return $types[$key];
    }

    public function create(TableScreen $table_screen, Config $options): ?ColumnPrototype
    {
        $column_types = $this->get_column_types($table_screen);

        return $this->create_column(
            $column_types,
            $options
        );
    }

    private function create_column(
        ColumnTypeCollection $column_types,
        Config $options
    ): ?ColumnPrototype {
        foreach ($column_types as $column_type) {
            if ($column_type->get_type() !== $options->get('type')) {
                continue;
            }

            return new ColumnPrototype(
                $options,
                $column_type
            );
        }

        return null;
    }

    // TODO
    //    private function create_from_column_type(Column $column_type, array $settings): ColumnPrototype
    //    {
    //        return new ColumnPrototype();
    //
    //        $column = clone $column_type;
    //
    //        $column->set_options($settings);
    //
    //        // TODO move to OrginalsFactory
    //        if ($column->is_original()) {
    //            if ( ! empty($settings['label']) && '' === $column->get_label()) {
    //                $column->set_label($settings['label']);
    //            }
    //
    //            return $column->set_name($column_type->get_type())
    //                          ->set_group('default');
    //        }
    //
    //        return $column->set_name((string)$settings['name'])
    //                      ->set_group('custom');
    //    }

}