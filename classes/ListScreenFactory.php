<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ListKey;

interface ListScreenFactory
{

    public function can_create(ListKey $key): bool;

    public function create(ListKey $key, array $settings = []): ListScreen;

}