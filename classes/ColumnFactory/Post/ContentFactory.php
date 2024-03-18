<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\Post\PostContent;

class ContentFactory extends ColumnFactory
{

    private $string_limit_factory;

    private $before_after_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        StringLimit $string_limit_factory,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->string_limit_factory = $string_limit_factory;
        $this->before_after_factory = $before_after_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->string_limit_factory);
        $this->add_component_factory($this->before_after_factory);
    }

    public function get_type(): string
    {
        return 'column-content';
    }

    protected function get_label(): string
    {
        return __('Content', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components): array
    {
        return array_merge([
            new PostContent(),
        ], parent::get_formatters($components));
    }

}