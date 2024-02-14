<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\StringLimitFactory;

class CommentFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        StringLimitFactory $string_limit_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($string_limit_factory);
    }

    public function get_type(): string
    {
        return 'column-comment_count';
    }

    protected function get_label(): string
    {
        return __('Comment Count', 'codepress-admin-columns');
    }

}