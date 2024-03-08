<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class CommentDisplay implements ComponentFactory
{

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_AUTHOR_EMAIL = 'author_email';

    private $string_limit;

    private $comment_link;

    public function __construct(
        StringLimit $string_limit,
        CommentLink $comment_link
    ) {
        $this->string_limit = $string_limit;
        $this->comment_link = $comment_link;
    }

    // Todo implement formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'comment',
                    OptionCollection::from_array([
                        self::PROPERTY_COMMENT      => __('Comment'),
                        self::PROPERTY_ID           => __('ID'),
                        self::PROPERTY_AUTHOR       => __('Author'),
                        self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                        self::PROPERTY_DATE         => __('Date'),
                    ]),
                    (string)$config->get('comment') ?: self::PROPERTY_COMMENT
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->string_limit->create(
                            $config,
                            StringComparisonSpecification::equal(self::PROPERTY_COMMENT)
                        ),
                        $this->comment_link->create(
                            $config,
                            StringComparisonSpecification::equal(self::PROPERTY_COMMENT)
                        ),
                    ])
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}