<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class AttachmentDisplay extends Settings\Column\Recursive
{

    use AC\Setting\RecursiveFormatterTrait;

    public function __construct()
    {
        parent::__construct(
            'attachment_display',
            __('Display', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'attachment_display',
                OptionCollection::from_array([
                    'thumbnail' => __('Thumbnails', 'codepress-admin-columns'),
                    'count'     => __('Count', 'codepress-admin-columns'),
                ]),
                'thumbnail'
            )
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Images(StringComparisonSpecification::equal('thumbnail')),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return 'count' === $options->get($this->name)
            ? $value->with_value(count($value->get_value()))
            : parent::format($value, $options);
    }

}