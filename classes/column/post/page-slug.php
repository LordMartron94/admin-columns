<?php
/**
 * CPAC_Column_Post_Page_Slug
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Page_Slug extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 	= 'column-page-slug';
		$this->properties['label']	 	= __( 'Slug', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
	
		return get_post_field( 'post_name', $post_id );
	}
}