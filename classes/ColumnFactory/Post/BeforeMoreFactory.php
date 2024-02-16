<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\BeforeMoreContent;
use AC\Settings\Column\WordLimitFactory;

class BeforeMoreFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        WordLimitFactory $word_limit_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($word_limit_factory);
    }

    public function get_type(): string
    {
        return 'column-before_moretag';
    }

    protected function get_label(): string
    {
        return __('More Tag', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new BeforeMoreContent());
    }

}
