<?php

namespace AC\Admin\Asset;

use AC;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\ColumnTypesFactory\Aggregate;
use AC\Controller\DefaultColumns;
use AC\Storage\Model\EditorFavorites;
use AC\Storage\Model\EditorMenuStatus;
use AC\Table\TableScreenCollection;
use AC\TableScreen;
use AC\Type\ListScreenId;

class Columns extends Script
{

    private $table_screen;

    private $table_screens;

    private $list_id;

    private $column_types_factory;

    private $menu_items;

    public function __construct(
        string $handle,
        Location $location,
        TableScreen $table_screen,
        Aggregate $column_types_factory,
        TableScreenCollection $table_screens,
        AC\Admin\MenuListItems $menu_items,
        ListScreenId $list_id = null
    ) {
        parent::__construct($handle, $location, [
            // TODO do we still use these scripts
            'jquery',
            'jquery-ui-slider',
            'jquery-ui-sortable',
            'jquery-touch-punch',
        ]);

        $this->table_screen = $table_screen;
        $this->table_screens = $table_screens;
        $this->list_id = $list_id;
        $this->column_types_factory = $column_types_factory;
        $this->menu_items = $menu_items;
    }

    public function register(): void
    {
        parent::register();

        // TODO Remove AC variable and use more specific
        $params = [
            //            '_ajax_nonce'                => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_screen'                => $this->table_screen->get_key(),
            'layout'                     => (string)$this->list_id,
            'original_columns'           => [],
            'uninitialized_list_screens' => [],
            'column_types'               => $this->get_column_types(),
            'column_groups'              => AC\ColumnGroups::get_groups()->get_all(),
            'i18n'                       => [
                'value'  => __('Value', 'codepress-admin-columns'),
                'label'  => __('Label', 'codepress-admin-columns'),
                'clone'  => __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
                'error'  => __('Invalid response.', 'codepress-admin-columns'),
                'errors' => [
                    'save_settings'  => __(
                        'There was an error during saving the column settings.',
                        'codepress-admin-columns'
                    ),
                    'loading_column' => __(
                        'The column could not be loaded because of an unknown error',
                        'codepress-admin-columns'
                    ),
                ],
            ],
        ];

        foreach ($this->table_screens as $table_screen) {
            $params['uninitialized_list_screens'][(string)$table_screen->get_key()] = [
                'screen_link' => (string)$table_screen->get_url()->with_arg(DefaultColumns::QUERY_PARAM, '1'),
            ];
        }

        wp_localize_script('ac-admin-page-columns', 'AC', $params);

        // TODO Needed for UI2 Remove part above
        $this->add_inline_variable('ac_admin_columns', [
            'nonce'                => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_key'             => (string)$this->table_screen->get_key(),
            'list_id'              => (string)$this->list_id,
            'column_types'         => $this->get_column_types(),
            'column_groups'        => AC\ColumnGroups::get_groups()->get_all(),
            'menu_items'           => $this->get_menu_items(),
            // TODO
            'menu_items_favorites' => (new EditorFavorites())->find_all_favorites(),
            'menu_groups_opened'   => (new EditorMenuStatus())->find_all_active_groups(),
        ]);

        $this->localize(
            'ac_admin_columns_i18n',
            new Script\Localize\Translation([
                'global'   => [
                    'search' => __('Search', 'codepress-admin-columns'),
                    'select' => __('Select', 'codepress-admin-columns'),
                ],
                'menu'     => [
                    'favorites' => __('Favorites', 'codepress-admin-columns'),
                ],
                'settings' => [
                    'label' => [
                        'select-icon' => __('Select Icon', 'codepress-admin-columns'),
                    ],
                ],
            ])
        );
    }

    public function get_menu_items(): array
    {
        // TODO
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

    private function get_column_types(): array
    {
        $column_types = [];

        // TODO cache
        $groups = AC\ColumnGroups::get_groups();

        foreach ($this->column_types_factory->create($this->table_screen) as $column) {
            $column_types[] = [
                'label'     => $column->get_label(),
                'value'     => $column->get_type(),
                'group'     => $groups->get($column->get_group())['label'],
                'group_key' => $column->get_group(),
                'original'  => $column->is_original(),
            ];
        }

        return $column_types;
    }

}