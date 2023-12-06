<?php

namespace AC;

use AC\ColumnRepository\Sort\ManualOrder;
use AC\ListScreen\ManageValue;

class ScreenController implements Registerable
{

    private $headings = [];

    private $table_screen;

    private $list_screen;

    private $default_column_repository;

    public function __construct(
        DefaultColumnsRepository $default_column_repository,
        TableScreen $table_screen,
        ListScreen $list_screen
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

    public function add_headings($headings)
    {
        if (empty($headings)) {
            return [];
        }

        if ( ! wp_doing_ajax()) {
            $this->default_column_repository->update($headings);
        }

        // Run once
        if ($this->headings) {
            return $this->headings;
        }

        $columns = $this->list_screen->get_columns(null, new ManualOrder($this->list_screen->get_id()));

        // Nothing stored. Show default columns on screen.
        if ( ! $columns->count()) {
            return $headings;
        }

        // Add mandatory checkbox
        if (isset($headings['cb'])) {
            $this->headings['cb'] = $headings['cb'];
        }

        foreach ($columns as $column) {
            $this->headings[$column->get_name()] = $column->get_custom_label();
        }

        return apply_filters('ac/headings', $this->headings, $this->list_screen);
    }

}