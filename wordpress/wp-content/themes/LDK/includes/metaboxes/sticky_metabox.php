<?php
class LDK_MetaboxSticky
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
		add_action( 'save_post',  array( $this, 'lkd_save_meta_box_data_sticky' ),1 );
		
		add_action( 'add_meta_boxes', function ($hook) {
			
			$screens = array( 'post');
			
			if($screens && is_array($screens)){
				foreach ( $screens as $screen ) {

					add_meta_box(
						'ldk_medios_stick',
						__( 'Sticky post', 'ldk_medios_stick' ),
						 array( $this, 'lkd_meta_box_sticky_callback' ),
						$screen,
						'side'
					);
				}
			}
		} );
		
	}
	
	
	
	public function lkd_meta_box_sticky_callback( $post ) {

		wp_nonce_field( 'lkd_save_meta_box_data_sticky', 'ldk_meta_box_sticky_nonce' );
		
		$value = get_option( 'ldk_sticky' );


		if(!is_array($value))
			$value=array();
	
		?>

		<?php foreach(ldk_get_all_medios() as $medio){ 
				$selected=($value[$medio->ID] && $value[$medio->ID]==$post->ID)?"checked":"";
		?>
			
			<label>
				<input <?php echo $selected ?> type="checkbox" name="ldk_medios_stick[]" value="<?php echo $medio->ID?>" /><?php echo $medio->post_title?>
				
			</label>
			<br />
			
		<?php } 
		
	}

    public function lkd_meta_box_sticky_clean_older($current_values, $post_id,$medios ) {

        //Lo que hacemos es eliminar los valores de este post ID si hubiera.

		if(!is_array($current_values))
			return false;
        
        foreach($medios as $medio){ 
            if($current_values[$medio->ID] && $current_values[$medio->ID]==$post_id)
                $current_values[$medio->ID]=false;
         } 

        return $current_values;
              
	}

	
	public function lkd_save_meta_box_data_sticky( $post_id ) {

		

		
		if ( ! isset( $_POST['ldk_meta_box_sticky_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ldk_meta_box_sticky_nonce'], 'lkd_save_meta_box_data_sticky' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		
		if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
		}
		

        $my_data_array=get_option( 'ldk_sticky' );

        $my_data_array=$this->lkd_meta_box_sticky_clean_older($my_data_array, $post_id,ldk_get_all_medios() );

         if(!is_array($my_data_array))
		        $my_data_array=array();
		
         if($_POST['ldk_medios_stick'] && is_array($_POST['ldk_medios_stick']))
         {
             foreach($_POST['ldk_medios_stick'] as $medioid)
             {
                 $my_data_array[$medioid]=$post_id;

                 
             }

             
             update_option( 'ldk_sticky', $my_data_array);
         }
	}
	
	
}