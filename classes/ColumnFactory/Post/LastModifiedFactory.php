<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\ModifiedDate;
use AC\Settings\Column\DateFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class LastModifiedFactory extends ColumnFactory
{

    protected $date_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        DateFactory $date_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->date_factory = $date_factory;
    }

    public function get_type(): string
    {
        return 'column-modified';
    }

    protected function get_label(): string
    {
        return __('Last Modified', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new ModifiedDate());
    }

}