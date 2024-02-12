<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\CustomFieldFactory;
use AC\ColumnFactory\Post\AttachmentFactory;
use AC\ColumnFactory\Post\AuthorFactory;
use AC\ColumnFactory\Post\CommentFactory;
use AC\ColumnFactory\Post\ExcerptFactory;
use AC\MetaType;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class PostFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return null;
        }

        $post_type = $table_screen->get_post_type();

        // TODO use ComponentCollectionBuilderFactory? because it will be loaded static now, resulting in duplicate settings (label, width etc.)
        $factories['column-attachment'] = $this->container->get(AttachmentFactory::class);
        $factories['column-author_name'] = $this->container->get(AuthorFactory::class);

        if (post_type_supports($post_type, 'comments')) {
            $factories['column-comment_count'] = $this->container->get(CommentFactory::class);
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories['column-excerpt'] = $this->container->get(ExcerptFactory::class);
        }

        $factories['column-meta'] = $this->container->make(CustomFieldFactory::class, [
            'meta_type' => new MetaType(MetaType::POST),
        ]);
        $factories['column-featured_image'] = $this->container->get(AC\ColumnFactory\Post\FeaturedImageFactory::class);
        $factories['column-post_formats'] = $this->container->get(AC\ColumnFactory\Post\FormatsFactory::class);
        $factories['column-postid'] = $this->container->get(AC\ColumnFactory\Post\IdFactory::class);
        $factories['column-last_modified_author'] = $this->container->get(
            AC\ColumnFactory\Post\LastModifiedAuthorFactory::class
        );
        $factories['column-before_moretag'] = $this->container->get(AC\ColumnFactory\Post\BeforeMoreFactory::class);
        $factories['column-comment_status'] = $this->container->get(AC\ColumnFactory\Post\CommentStatusFactory::class);
        $factories['column-content'] = $this->container->get(AC\ColumnFactory\Post\ContentFactory::class);
        $factories['column-date_published'] = $this->container->get(AC\ColumnFactory\Post\DatePublishFactory::class);
        $factories['column-depth'] = $this->container->get(AC\ColumnFactory\Post\DepthFactory::class);
        $factories['column-estimated_reading_time'] = $this->container->get(
            AC\ColumnFactory\Post\EstimateReadingTimeFactory::class
        );

        return new Collection\ColumnFactories($factories);
    }

}