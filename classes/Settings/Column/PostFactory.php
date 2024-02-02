<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class PostFactory implements SettingFactory
{

    private $character_limit_factory;

    private $image_factory;

    private $user_factory;

    private $status_factory;

    private $date_factory;

    public function __construct(
        CharacterLimitFactory $character_limit_factory,
        ImageFactory $image_factory,
        UserFactory $user_factory,
        StatusFactory $status_factory,
        DateFactory $date_factory
    ) {
        $this->character_limit_factory = $character_limit_factory;
        $this->image_factory = $image_factory;
        $this->user_factory = $user_factory;
        $this->status_factory = $status_factory;
        $this->date_factory = $date_factory;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new Post(
            $config->get('post') ?: Post::PROPERTY_TITLE,
            new SettingCollection([
                $this->character_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_TITLE)
                ),
                $this->image_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_FEATURED_IMAGE)
                ),
                $this->user_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_AUTHOR)
                ),
                $this->status_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_STATUS)
                ),
                $this->status_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_STATUS)
                ),
                $this->date_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_DATE)
                ),
            ]),
            $specification
        );
    }

}