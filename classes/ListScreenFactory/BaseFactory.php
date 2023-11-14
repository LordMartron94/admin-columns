<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\Exception\InvalidListScreenException;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use DateTime;

abstract class BaseFactory implements ListScreenFactory
{

    protected function add_settings(ListScreen $list_screen, array $settings): ListScreen
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

        return $list_screen;
    }

    public function create(ListKey $key, array $settings = []): ListScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        return $this->add_settings($this->create_list_screen($key), $settings);
    }

    abstract protected function create_list_screen(ListKey $key): ListScreen;

}