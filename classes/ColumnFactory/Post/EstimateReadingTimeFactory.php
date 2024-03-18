<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\WordsPerMinute;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;

class EstimateReadingTimeFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        WordsPerMinute $words_per_minute_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($words_per_minute_factory);
    }

    public function get_type(): string
    {
        return 'column-estimated_reading_time';
    }

    protected function get_label(): string
    {
        return __('Read Time', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new Formatter\Post\PostContent());
    }

}