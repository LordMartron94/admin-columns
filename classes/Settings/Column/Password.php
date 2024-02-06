<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\Config;
use AC\Setting\Type\Value;
use AC\Settings;

class Password extends Settings\Setting implements Setting\Formatter
{

    public function __construct(string $password = '', Specification $conditions = null)
    {
        parent::__construct(
            Setting\Component\Input\OptionFactory::create_select(
                'password',
                Setting\Component\OptionCollection::from_array([
                    ''     => __('Password', 'codepress-admin-column'),
                    'text' => __('Plain text', 'codepress-admin-column'),
                ]),
                $password
            ),
            __('Display format', 'codepress-admin-columns'),
            null,
            $conditions
        );
    }

    public function format(Value $value): Value
    {
        if ('text' === $options->get('password')) {
            return $value;
        }

        // TODO test

        $password = $value->get_value();

        if ($password) {
            $pwchar = '&#8226;';
            $password = str_pad('', strlen($password) * strlen($pwchar), $pwchar);
        }

        return $value->with_value($password);
    }




    //    private $password;
    //
    //    protected function define_options()
    //    {
    //        return ['password'];
    //    }
    //
    //    public function create_view()
    //    {
    //        $select = $this->create_element('select')
    //                       ->set_options([
    //                           ''     => __('Password', 'codepress-admin-column'), // default
    //                           'text' => __('Plain text', 'codepress-admin-column'),
    //                       ]);
    //
    //        $view = new View([
    //            'label'   => __('Display format', 'codepress-admin-columns'),
    //            'setting' => $select,
    //        ]);
    //
    //        return $view;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_password()
    //    {
    //        return $this->password;
    //    }
    //
    //    /**
    //     * @param string $password
    //     *
    //     * @return true
    //     */
    //    public function set_password($password)
    //    {
    //        $this->password = $password;
    //
    //        return true;
    //    }
    //
    //    public function format($value, $original_value)
    //    {
    //        if ( ! $this->get_password()) {
    //            $pwchar = '&#8226;';
    //            $value = $value ? str_pad('', strlen($value) * strlen($pwchar), $pwchar) : '';
    //        }
    //
    //        return $value;
    //    }

}