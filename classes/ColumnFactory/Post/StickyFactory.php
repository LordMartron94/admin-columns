<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\IsSticky;

class StickyFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-sticky';
    }

    protected function get_label(): string
    {
        return __('Sticky', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)
                     ->prepend(new IsSticky());
    }

}