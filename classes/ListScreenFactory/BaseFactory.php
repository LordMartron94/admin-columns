<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ColumnFactory;
use AC\Exception\InvalidListScreenException;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use DateTime;

abstract class BaseFactory implements ListScreenFactory
{

    protected $table_screen_factory;

    public function __construct(TableScreenFactory $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    protected function add_settings(ListScreen $list_screen, ListKey $key, array $settings): ListScreen
    {
        $columns = $settings['columns'] ?? [];
        $preferences = $settings['preferences'] ?? [];
        $group = $settings['group'] ?? '';
        $date = $settings['date'] ?? new DateTime();
        $list_id = $settings['list_id'] ?? null;

        if (is_string($date)) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }

        if (ListScreenId::is_valid_id($list_id)) {
            $list_screen->set_id(new ListScreenId($list_id));
        }

        $list_screen->set_title($settings['title'] ?? '');
        $list_screen->set_preferences($preferences ?: []);
        $list_screen->set_settings($columns ?: []);
        $list_screen->set_updated($date);

        if ($group) {
            $list_screen->set_group($group);
        }

        $list_screen->set_columns(
            $this->get_columns($key, $columns) ?: $this->get_default_columns($key)
        );

        return $list_screen;
    }

    public function create(ListKey $key, array $settings = []): ListScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        return $this->add_settings($this->create_list_screen($key), $key, $settings);
    }

    // TODO create trait or factory
    private function get_columns(ListKey $key, array $settings): array
    {
        if ( ! $this->table_screen_factory->can_create($key)) {
            return [];
        }

        $columns = [];

        $column_factory = new ColumnFactory(
            $this->table_screen_factory->create($key)
        );

        foreach ($settings as $name => $data) {
            $data['name'] = (string)$name;

            $columns[] = $column_factory->create($data);
        }

        return array_filter($columns);
    }

    private function get_default_columns(ListKey $key): array
    {
        if ( ! $this->table_screen_factory->can_create($key)) {
            return [];
        }

        $table_screen = $this->table_screen_factory->create($key);

        $columns = [];

        $column_factory = new ColumnFactory(
            $this->table_screen_factory->create($key)
        );

        // TODO
        foreach ($table_screen->get_columns() as $column) {
            if ($column->is_original()) {
                $columns[] = $column_factory->create([
                    'type'  => $column->get_type(),
                    'label' => $column->get_label(),
                ]);
            }
        }

        return $columns;
    }

    abstract protected function create_list_screen(ListKey $key): ListScreen;

}