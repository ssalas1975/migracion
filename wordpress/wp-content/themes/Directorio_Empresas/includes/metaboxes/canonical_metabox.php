<?php
class LDK_CanonicalMetabox
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
		add_action( 'save_post',  array( $this, 'lkd_canonical_save_meta_box_data' ),1 );
		
		add_action( 'add_meta_boxes', function ($hook) {
			
			$screens = array( 'post', 'page');
			
			if($screens && is_array($screens)){
				foreach ( $screens as $screen ) {

					add_meta_box(
						'lkd_canonicalmedios',
						__( 'Canonical', 'lkd_canonicalmedios' ),
						 array( $this, 'lkd_canonical_meta_box_callback' ),
						$screen,
						'side'
					);
				}
			}
		} );
		
	}
	
	
	
	public function lkd_canonical_meta_box_callback( $post ) {

		wp_nonce_field( 'ldk_canonical_save_meta_box_data', 'ldk_canonical_meta_box_nonce' );
		
		$value = get_post_meta( $post->ID, 'ldk_canonical', true );
	
		?>

		<?php foreach(ldk_get_all_medios() as $medio){ 
			$selected=($medio->ID==$value)?"checked":"";
		?>
			
			<label>
				<input  <?php echo $selected ?> type="radio" name="ldk_canonical" value="<?php echo $medio->ID?>" /><?php echo $medio->post_title?>
			</label>
			<br />
			
		<?php } 
		
	}


	
	public function lkd_canonical_save_meta_box_data( $post_id ) {

		

		
		if ( ! isset( $_POST['ldk_canonical_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ldk_canonical_meta_box_nonce'], 'ldk_canonical_save_meta_box_data' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		
		if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
		}
		

			if ( ! isset( $_POST['ldk_canonical'] ) ) {
			return;
		}

		$my_data = $_POST['ldk_canonical'] ;

		
		update_post_meta( $post_id, 'ldk_canonical', $my_data );
	}
	
	
}