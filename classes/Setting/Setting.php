<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Setting\Component\Input;

interface Setting extends Component
{

    public function get_input(): Input;

    public function get_conditions(): Specification;

}