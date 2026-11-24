<?php
class LDK_Metabox
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
		add_action( 'save_post',  array( $this, 'lkd_save_meta_box_data' ),1 );
		
		add_action( 'add_meta_boxes', function ($hook) {
			
			$screens = array( 'post', 'page');
			
			if($screens && is_array($screens)){
				foreach ( $screens as $screen ) {

					add_meta_box(
						'lkdmedios',
						__( 'Medios', 'lkdmedios' ),
						 array( $this, 'lkd_meta_box_callback' ),
						$screen,
						'side'
					);
				}
			}
		} );
		
	}
	
	
	
	public function lkd_meta_box_callback( $post ) {

		wp_nonce_field( 'ldk_save_meta_box_data', 'ldk_meta_box_nonce' );
		
		$value = get_post_meta( $post->ID, 'ldk_medio', true );
		
		if(!is_array($value))
			$value=array();
	
		?>

		<?php foreach(ldk_get_all_medios() as $medio){ 
				$selected=(in_array($medio->ID,$value))?"checked":"";
		?>
			
			<label>
				<input <?php echo $selected ?> type="checkbox" name="ldk_medios[]" value="<?php echo $medio->ID?>" /><?php echo $medio->post_title?>
				<!--<?php echo $this->lkd_generate_preview_link($medio,$post->ID); ?>-->
			</label>
			<br />
			
		<?php } 
		
	}


	
	public function lkd_save_meta_box_data( $post_id ) {

		

		
		if ( ! isset( $_POST['ldk_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ldk_meta_box_nonce'], 'ldk_save_meta_box_data' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		
		if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
		}
		
		
		$my_data_array=array();

		if($_POST['ldk_medios'] && is_array($_POST['ldk_medios']))
        {
            foreach($_POST['ldk_medios'] as $medioid)
                array_push($my_data_array,$medioid);

            
            update_post_meta( $post_id, 'ldk_medio', $my_data_array );
        }
	}
	
	public function lkd_generate_preview_link($medio,$post_id){
		
		$url_medio = ldk_get_medio_meta('id_medio',true,$medio);    
        $nonce = wp_create_nonce('post_preview_' . $post_id);
        $customURLPreview =get_permalink($post_id).'?preview=true&preview_id='.$post_id.'&preview_nonce='.$nonce.'&ldkmedioprw='.$url_medio;
		return "<a target='_blank' href='".$customURLPreview."'> Preview</a>";
	}
}