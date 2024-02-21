<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostTerms implements Formatter
{

    private $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function format(Value $value): Value
    {
        $terms = get_the_terms($value->get_id(), $this->taxonomy);

        if ( ! $terms || is_wp_error($terms)) {
            return $value->with_value(false);
        }

        // TODO Probably give back a value collection and let the other formatter each item
        return $value->with_value($terms);
    }

}