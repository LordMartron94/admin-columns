<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Comment\LinkableCommentDate;
use AC\Setting\Formatter\Comment\Property;

class DateGmtFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Date GMT', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-date_gmt';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Property('comment_date_gmt'))
                     ->add(new LinkableCommentDate());
    }

}