<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Column\LabelEncoder;
use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;

class ListScreenSave implements RequestAjaxHandler
{

    private $storage;

    private $column_factory;

    private $table_screen_factory;

    public function __construct(
        Storage $storage,
        ColumnFactory $column_factory,
        TableScreenFactory $table_screen_factory
    ) {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new ListKey($request->get('list_key', '') ?? '');
        $data = $request->get('data', '');
        $data = json_decode($data, true);

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            wp_send_json_error(['message' => __('List screen not found', 'codepress-admin-columns')]);
        }

        $id = $data['id'] ?? null;

        if ( ! ListScreenId::is_valid_id($id)) {
            $response->error();

            exit;
        }

        $list_screen = $this->storage->find(new ListScreenId($id));

        if ( ! $list_screen) {
            $list_screen = new ListScreen(
                new ListScreenId($id),
                '',
                $this->table_screen_factory->create($list_key)
            );
        }

        $table_screen = $list_screen->get_table_screen();

        $list_screen->set_title((string)$data['title']);
        $list_screen->set_columns($this->get_columns($table_screen, (array)$data['columns']));
        $list_screen->set_preferences((array)$data['settings']);

        $this->storage->save($list_screen);

        $response
            ->set_message(
                sprintf(
                    '%s %s',
                    sprintf(
                        __('Settings for %s updated successfully.', 'codepress-admin-columns'),
                        sprintf('<strong>%s</strong>', esc_html($list_screen->get_title() ?: $list_screen->get_label()))
                    ),
                    ac_helper()->html->link(
                        (string)$list_screen->get_table_url(),
                        sprintf(__('View %s screen', 'codepress-admin-columns'), $list_screen->get_label())
                    )
                )
            )->success();
    }

    private function get_columns(TableScreen $table_screen, array $columndata): ColumnCollection
    {
        $columns = [];

        foreach ($columndata as $data) {
            if (isset($data['label'])) {
                $data['label'] = (new LabelEncoder())->encode($data['label']);
            }

            $columns[] = $this->column_factory->create($table_screen, $data);
        }

        return new ColumnCollection(array_filter($columns));
    }

}