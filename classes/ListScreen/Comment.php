<?php

namespace AC\ListScreen;

use AC;
use AC\Column;
use AC\MetaType;
use AC\Type\QueryAware;
use AC\Type\Url;
use AC\WpListTableFactory;

class Comment extends AC\ListScreen implements ManageValue, ListTable
{

    public function __construct()
    {
        $this->set_label(__('Comments'))
             ->set_singular_label(__('Comment'))
             ->set_meta_type(MetaType::COMMENT)
             ->set_key('wp-comments')
             ->set_screen_id('edit-comments')
             ->set_group('comment');
    }

    public function get_table_url(): QueryAware
    {
        return new Url\ListTable('edit-comments.php', $this->has_id() ? $this->get_id() : null);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Comment((new WpListTableFactory())->create_comment_table($this->get_screen_id()));
    }

    public function manage_value(): AC\Table\ManageValue
    {
        return new AC\Table\ManageValue\Comment(new AC\ColumnRepository($this));
    }

    public function get_table_attr_id(): string
    {
        return '#the-comment-list';
    }

    protected function register_column_types(): void
    {
        $this->register_column_types_from_list([
            AC\Column\CustomField::class,
            AC\Column\Actions::class,
            Column\Comment\Agent::class,
            Column\Comment\Approved::class,
            Column\Comment\Author::class,
            Column\Comment\AuthorAvatar::class,
            Column\Comment\AuthorEmail::class,
            Column\Comment\AuthorIP::class,
            Column\Comment\AuthorName::class,
            Column\Comment\AuthorUrl::class,
            Column\Comment\Comment::class,
            Column\Comment\Date::class,
            Column\Comment\DateGmt::class,
            Column\Comment\Excerpt::class,
            Column\Comment\ID::class,
            Column\Comment\Post::class,
            Column\Comment\ReplyTo::class,
            Column\Comment\Response::class,
            Column\Comment\Status::class,
            Column\Comment\Type::class,
            Column\Comment\User::class,
            Column\Comment\WordCount::class,
        ]);
    }

}