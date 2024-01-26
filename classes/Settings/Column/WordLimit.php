<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input\Number;
use AC\Setting\Type\Value;
use AC\Settings;

class WordLimit extends Settings\Column implements AC\Setting\Formatter
{

    // TODO Stefan Test -> name was word_limit, option 'excerpt_length'

    public function __construct(int $default = 20, Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = 'excerpt_length';
        $this->label = __('Word Limit', 'codepress-admin-columns');
        $this->description = __('Maximum number of words', 'codepress-admin-columns') . '<em>' . __(
                'Leave empty for no limit',
                'codepress-admin-columns'
            ) . '</em>';
        $this->input = AC\Setting\Component\Input\Number::create_single_step(
=======
        $input = Number::create_single_step(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            0,
            null,
            $default,
            null,
            null,
            __('Words', 'codepress-admin-columns')
        );

        parent::__construct(
            'excerpt_length',
            __('Word Limit', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of words', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            $input,
            $conditions
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return $value->with_value(
            ac_helper()->string->trim_words(
                (string)$value->get_value(),
                $options->get('excerpt_length') ?? 20
            )
        );
    }

    // TODO
    //	/**
    //	 * @var int
    //	 */
    //	private $excerpt_length;
    //
    //	protected function set_name() {
    //		$this->name = 'word_limit';
    //	}
    //
    //	protected function define_options() {
    //		return [
    //			'excerpt_length' => 20,
    //		];
    //	}
    //
    //	public function create_view() {
    //		$setting = $this->create_element( 'number' )
    //		                ->set_attributes( [
    //			                'min'  => 0,
    //			                'step' => 1,
    //		                ] );
    //
    //		$view = new View( [
    //			'label'   => __( 'Word Limit', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_excerpt_length() {
    //		return $this->excerpt_length;
    //	}
    //
    //	/**
    //	 * @param int $excerpt_length
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_excerpt_length( $excerpt_length ) {
    //		$this->excerpt_length = $excerpt_length;
    //
    //		return true;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		$values = [];
    //
    //		foreach ( (array) $value as $_string ) {
    //			$values[] = ac_helper()->string->trim_words( $_string, $this->get_excerpt_length() );
    //		}
    //
    //		return ac_helper()->html->implode( $values );
    //	}

}