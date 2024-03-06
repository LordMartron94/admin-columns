<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class VisualEditingFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Visual Editor', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-rich_editing';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Formatter\User\HasRichEditing())
                     ->add(new Formatter\YesNoIcon());
    }

}