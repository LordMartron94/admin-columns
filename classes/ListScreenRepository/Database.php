<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepositoryWritable;
use AC\Storage\EncoderFactory;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use DateTime;

class Database implements ListScreenRepositoryWritable
{

    use ListScreenRepositoryTrait;

    private const TABLE = 'admin_columns';

    private $table_screen_factory;

    private $encoder_factory;

    public function __construct(TableScreenFactory $table_screen_factory, EncoderFactory $encoder_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
        $this->encoder_factory = $encoder_factory;
    }

    protected function find_from_source(ListScreenId $id): ?ListScreen
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_id = %s
		',
            (string)$id
        );

        $row = $wpdb->get_row($sql);

        if ( ! $row) {
            return null;
        }

        return $this->create_list_screen($row);
    }

    protected function find_all_from_source(): ListScreenCollection
    {
        global $wpdb;

        $sql = '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
		';

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    protected function find_all_by_key_from_source(ListKey $key): ListScreenCollection
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_key = %s
		',
            (string)$key
        );

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    public function save(ListScreen $list_screen): void
    {
        global $wpdb;

        $list_screen_dto = $this->encoder_factory->create()
                                                 ->set_list_screen($list_screen)
                                                 ->encode()['list_screen'];

        $settings = $this->save_preferences($list_screen_dto);
        $date = DateTime::createFromFormat('U', (string)$list_screen_dto['updated']);

        $args = [
            'list_id'       => $list_screen_dto['id'],
            'list_key'      => $list_screen_dto['type'],
            'title'         => $list_screen_dto['title'],
            'columns'       => $list_screen_dto['columns'] ? serialize($list_screen_dto['columns']) : null,
            'settings'      => $settings ? serialize($settings) : null,
            'date_modified' => $date->format('Y-m-d H:i:s'),
        ];

        $table = $wpdb->prefix . self::TABLE;
        $exists = $this->exists($list_screen->get_id());

        if ($exists) {
            $wpdb->update(
                $table,
                $args,
                [
                    'list_id' => (string)$list_screen->get_id(),
                ],
                array_fill(0, 6, '%s')
            );
        } else {
            $args['date_created'] = $args['date_modified'];

            $wpdb->insert(
                $table,
                $args,
                array_fill(0, 7, '%s')
            );
        }
    }

    public function delete(ListScreen $list_screen): void
    {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . self::TABLE,
            [
                'list_id' => (string)$list_screen->get_id(),
            ],
            [
                '%s',
            ]
        );
    }

    /**
     * Template method to add and remove preferences before save
     */
    protected function save_preferences(array $list_screen_dto): array
    {
        return $list_screen_dto['settings'];
    }

    /**
     * Template method to add and remove preferences before retrieval
     */
    protected function get_preferences(object $data): array
    {
        return $data->settings
            ? unserialize($data->settings, ['allowed_classes' => false])
            : [];
    }

    private function create_list_screen(object $data): ?ListScreen
    {
        $list_key = new ListKey($data->list_key);

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            return null;
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        return new ListScreen(
            new ListScreenId($data->list_id),
            $data->title,
            $table_screen,
            $this->get_columns(new ColumnFactory($table_screen), $data),
            $this->get_preferences($data),
            new DateTime($data->date_modified)
        );
    }

    private function get_columns(ColumnFactory $factory, object $data): ColumnCollection
    {
        $columns = [];

        $columns_data = $data->columns
            ? unserialize($data->columns, ['allowed_classes' => false])
            : [];

        foreach ($columns_data as $name => $column_data) {
            // TODO is this needed?
            if ( ! isset($column_data['name'])) {
                $columns_data['name'] = $name;
            }

            $columns[] = $factory->create($column_data);
        }

        return new ColumnCollection(array_filter($columns));
    }

    private function create_list_screens(array $rows): ListScreenCollection
    {
        $list_screens = array_filter(
            array_map([$this, 'create_list_screen'], $rows)
        );

        return new ListScreenCollection($list_screens);
    }

}