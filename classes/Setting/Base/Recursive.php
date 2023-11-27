<?php

declare(strict_types=1);

namespace AC\Setting\Base;

use AC;
use AC\Setting\RecursiveTrait;
use AC\Setting\SettingCollection;
use ACP\Expression\Specification;

class Recursive extends Setting implements AC\Setting\Recursive
{

    use RecursiveTrait;

    private $settings;

    private $parent;

    public function __construct(
        string $name,
        SettingCollection $settings,
        string $label = '',
        string $description = '',
        Specification $conditions = null,
        bool $parent = false
    ) {
        parent::__construct(
            $name,
            $label,
            $description,
            null,
            $conditions
        );

        $this->settings = $settings;
        $this->parent = $parent;
    }

    public function get_children(): SettingCollection
    {
        return $this->settings;
    }

    public function is_parent(): bool
    {
        return $this->parent;
    }

}