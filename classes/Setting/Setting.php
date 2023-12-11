<?php

declare(strict_types=1);

namespace AC\Setting;

use ACP\Expression\Specification;

interface Setting
{

    public function get_name(): string;

    public function get_label(): string;

    public function get_description(): string;

    // TODO David consider if this violates interface segregation for recursive
    public function get_input(): Input;

    public function has_conditions(): bool;

    public function get_conditions(): Specification;

}