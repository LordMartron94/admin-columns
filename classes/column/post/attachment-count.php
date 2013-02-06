<?php
/**
 * CPAC_Column_Post_Attachment_Count
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Attachment_Count extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 = 'column-attachment-count';
		$this->properties['label']	 = __( 'No. of Attachments', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {	
		
		$attachment_ids = get_posts( array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
		
		return count( $attachment_ids );
	}
}