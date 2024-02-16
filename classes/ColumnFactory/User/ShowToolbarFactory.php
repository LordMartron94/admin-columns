<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class ShowToolbarFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Show Toolbar', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_show_toolbar';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Formatter\User\ShowToolbar())
                     ->add(new Formatter\YesNoIcon());
    }

}