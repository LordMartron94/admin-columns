<?php

namespace AC\Settings;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\Component;

class Column
{

    protected $name;

    protected $label;

    protected $description;

    protected $input;

    protected $conditions;

    public function __construct(

        // TODO
        string $name,
        string $label,
        string $description = null,
        Component\Input $input = null,
        Specification $conditions = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->conditions = $conditions ?? new NullSpecification();
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_description(): ?string
    {
        return $this->description;
    }

    public function get_input(): ?Component\Input
    {
        return $this->input;
    }

    public function get_conditions(): Specification
    {
        return $this->conditions;
    }

}