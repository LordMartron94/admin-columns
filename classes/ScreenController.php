<?php

namespace AC;

use AC\ColumnRepository\Sort\ManualOrder;
use AC\ListScreen\ManageValue;

class ScreenController implements Registerable
{

    /**
     * @var array
     */
    private $headings = [];

    private $table_screen;

    private $list_screen;

    private $default_column_repository;

    public function __construct(
        DefaultColumnsRepository $default_column_repository,
        TableScreen $table_screen,
        ListScreen $list_screen = null
    ) {
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
        $this->default_column_repository = $default_column_repository;
    }

    public function register(): void
    {
        // Headings
        add_filter($this->table_screen->get_heading_hookname(), [$this, 'add_headings'], 200);

        // Values
        if ($this->table_screen instanceof ManageValue) {
            $this->table_screen->manage_value($this->list_screen)->register();
        }

        do_action('ac/table/list_screen', $this->list_screen, $this->table_screen);
    }

    public function add_headings($columns)
    {
        if (empty($columns)) {
            return $columns;
        }

        if ( ! wp_doing_ajax()) {
            $this->default_column_repository->update($columns);
        }

        // Run once
        if ($this->headings) {
            return $this->headings;
        }

        // Nothing stored. Show default columns on screen.
        if ( ! $this->list_screen) {
            return $columns;
        }

        // Add mandatory checkbox
        if (isset($columns['cb'])) {
            $this->headings['cb'] = $columns['cb'];
        }

        $column_repository = new ColumnRepository($this->list_screen);

        $args = [
            ColumnRepository::ARG_SORT => new ManualOrder($this->list_screen->get_id()),
        ];

        foreach ($column_repository->find_all($args) as $column) {
            $this->headings[$column->get_name()] = $column->get_custom_label();
        }

        return apply_filters('ac/headings', $this->headings, $this->list_screen);
    }

}