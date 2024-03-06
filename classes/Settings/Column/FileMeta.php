<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Settings;

class FileMeta extends Settings\Control
{

    public const NAME = 'media_meta_key';

    protected $meta_key;

    public function __construct(
        string $label,
        array $meta_options,
        string $meta_key,
        Specification $specification = null
    ) {
        parent::__construct(
            OptionFactory::create_select(
                'media_meta_key',
                OptionCollection::from_array($meta_options),
                $meta_key
            ),
            $label,
            null,
            $specification
        );
        $this->meta_key = $meta_key;
    }

}