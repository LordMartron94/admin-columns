<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\TableScreenFactory;
use AC\Type\ListKey;

class PostFactory extends BaseFactory
{

    private $table_screen_factory;

    public function __construct(TableScreenFactory\Post $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    protected function create_list_screen(ListKey $key): ListScreen
    {
        return new Post(
            $this->table_screen_factory->create($key)
        );
    }

    public function can_create(ListKey $key): bool
    {
        return $this->table_screen_factory->can_create($key);
    }

}