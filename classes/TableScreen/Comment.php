<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ColumnRepository;
use AC\MetaType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Comment extends TableScreen implements AC\ListScreen\ListTable
{

    public function __construct(ListKey $key, string $screen_id)
    {
        parent::__construct($key, $screen_id, false);
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Comment($column_repository);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Comment((new WpListTableFactory())->create_comment_table($this->screen_id));
    }

    public function get_group(): string
    {
        return 'comment';
    }

    public function get_query_type(): string
    {
        return 'comment';
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::COMMENT);
    }

    public function get_attr_id(): string
    {
        return '#the-comment-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable('edit-comments.php');
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Comments'),
            __('Comment')
        );
    }

}