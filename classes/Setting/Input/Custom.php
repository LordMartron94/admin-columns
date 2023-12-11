<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

final class Custom extends Input
{

    private $data;

    public function __construct(
        string $type,
        array $data = [],
        $default = null,
        string $placeholder = null,
        string $class = null
    ) {
        parent::__construct($type, $default, $placeholder, $class);

        $this->data = $data;
    }

    public static function create_empty(
        array $data = []
    ): self {
        return new self('empty', $data);
    }

    public function has_data(): bool
    {
        return ! empty($this->data);
    }

    public function get_data(): array
    {
        return $this->data;
    }

}