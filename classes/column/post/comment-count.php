<?php

/**
 * CPAC_Column_Post_Modified
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Comment_Count extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-comment-count';
		$this->properties['label']	 	= __( 'Comment count', CPAC_TEXTDOMAIN );
		
		// define additional options
		$this->options['comment_status'] = '';
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * get_comment_stati
	 * @since 2.0.0
	 */
	function get_comment_stati() {
		
		return array(
			'total_comments'	=> __( 'Total', CPAC_TEXTDOMAIN ),
			'approved'			=> __( 'Approved', CPAC_TEXTDOMAIN ),
			'moderated'			=> __( 'Pending', CPAC_TEXTDOMAIN ),
			'spam'				=> __( 'Spam', CPAC_TEXTDOMAIN ),
			'trash'				=> __( 'Trash', CPAC_TEXTDOMAIN ),
		);
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		
		$value = '';

		$status = $this->options->comment_status;
		$count 	= wp_count_comments( $post_id );

		if ( isset( $count->{$status} ) ) {
			
			$names = $this->get_comment_stati();
			
			$url   = esc_url( add_query_arg( array( 'p' => $post_id, 'comment_status' => $status ), admin_url( 'edit-comments.php' ) ) );
			$value = "<a href='{$url}' class='cp-{$status}' title='" . $names[ $status ] . "'>{$count->approved}</a>";			
		}		
		
		return $value;
	}
	
	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {
		
		return post_type_supports( $this->storage_model->key, 'comments' );
	}
	
	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {
				
		?>
		
		<tr class="column_comment-count">			
			<?php $this->label_view( $this->properties->label, '', 'comment-status' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'comment_status' ); ?>" id="<?php $this->attr_id( 'comment-status' ); ?>">				
				<?php foreach ( $this->get_comment_stati() as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $this->options->comment_status ) ?>><?php echo $label; ?></option>
				<?php endforeach; ?>				
				</select>
			</td>
		</tr>		
		<?php 
	}
}