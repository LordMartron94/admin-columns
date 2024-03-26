<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class TermLinkFactory implements SettingFactory
{

    private $post_type;

    public function __construct(string $post_type = null)
    {
        $this->post_type = $post_type;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new TermLink(
            $config->get('term_link_to') ?: '',
            $this->post_type,
            $specification
        );
    }

}