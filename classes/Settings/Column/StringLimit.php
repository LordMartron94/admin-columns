<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
=======
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\SettingCollection;
use AC\Settings;

class StringLimit extends Recursive
{

    public function __construct(Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = 'string_limit';
        $this->label = __('Text Limit', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
=======
        $input = Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            OptionCollection::from_array(
                [
                    ''                => __('No limit', 'codepress-admin-columns'),
                    'character_limit' => __('Character Limit', 'codepress-admin-columns'),
                    'word_limit'      => __('Word Limit', 'codepress-admin-columns'),
                ]
            ),
            'word_limit'
        );

        parent::__construct(
            'string_limit',
            __('Text Limit', 'codepress-admin-columns'),
            '',
            $input,
            $conditions
        );
    }

    public function get_children(): SettingCollection
    {
        // TODO test formatter
        return new SettingCollection([
            new Settings\Column\CharacterLimit(
                StringComparisonSpecification::equal('character_limit')
            ),
            new Settings\Column\WordLimit(
                20,
                StringComparisonSpecification::equal('word_limit')
            ),
        ]);
    }


    // TODO
    //	/**
    //	 * @var string
    //	 */
    //	private $string_limit;
    //
    //	protected function define_options() {
    //		return [ 'string_limit' => 'word_limit' ];
    //	}
    //
    //	public function create_view() {
    //		$setting = $this->create_element( 'select' )
    //		                ->set_attribute( 'data-refresh', 'column' )
    //		                ->set_options( $this->get_limit_options() );
    //
    //		$view = new View( [
    //			'label'   => __( 'Text Limit', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Limit text to a certain number of characters or words', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	private function get_limit_options() {
    //		$options = [
    //			''                => __( 'No limit', 'codepress-admin-columns' ),
    //			'character_limit' => __( 'Character Limit', 'codepress-admin-columns' ),
    //			'word_limit'      => __( 'Word Limit', 'codepress-admin-columns' ),
    //		];
    //
    //		return $options;
    //	}
    //
    //	public function get_dependent_settings() {
    //		$setting = [];
    //
    //		switch ( $this->get_string_limit() ) {
    //
    //			case 'character_limit' :
    //				$setting[] = new Settings\Column\CharacterLimit( $this->column );
    //
    //				break;
    //			case 'word_limit' :
    //				$setting[] = new Settings\Column\WordLimit( $this->column );
    //
    //				break;
    //		}
    //
    //		return $setting;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_string_limit() {
    //		return $this->string_limit;
    //	}
    //
    //	/**
    //	 * @param string $string_limit
    //	 *
    //	 * @return true
    //	 */
    //	public function set_string_limit( $string_limit ) {
    //		$this->string_limit = $string_limit;
    //
    //		return true;
    //	}

}