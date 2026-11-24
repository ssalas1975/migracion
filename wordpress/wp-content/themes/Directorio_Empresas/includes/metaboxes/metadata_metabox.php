<?php
class LDK_Metadata_Metabox
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
		add_action( 'save_post',  array( $this, 'lkd_save_metadata_box_data' ),1 );
		
		add_action( 'add_meta_boxes', function ($hook) {
			
			$screens = array( 'post', 'page');
			
			if($screens && is_array($screens)){
				foreach ( $screens as $screen ) {

					add_meta_box(
						'lkdmetadata',
						__( 'Meta data', 'lkdmetadata' ),
						 array( $this, 'lkd_metadata_box_callback' ),
						$screen
					);
				}
			}
		} );

        add_action('admin_enqueue_scripts', array( $this, 'ldk_enqueue_styles' ));
        
		
	}
	
	
    public function ldk_enqueue_styles($hook) {
		
        if( 'post.php' != $hook && 'post-new.php' != $hook ) return;
		
      
        wp_enqueue_style( 'ldk_meta_styles',  get_stylesheet_directory_uri().'/css/admin-styles.css' );

		
	}
	


	
	public function lkd_metadata_box_callback( $post ) {

		wp_nonce_field( 'lkd_save_metadata_box_data', 'ldk_metadata_box_nonce' );
		
		$value = get_post_meta( $post->ID, 'ldk_metadata', true );
		
		if(!is_array($value))
			$value=array();
	
?>
        <div class="ldk_metadata_box">
            <label>Meta title</label>
				<input type="text" placeholder="<?php echo get_the_title($post->ID)?>" name="ldk_meta_title" value="<?php echo $value['ldk_meta_title']?>" />
			
			
			<br /><br />
            <label> Meta description</label>
				<input type="text" placeholder="<?php echo get_the_excerpt($post->ID)?>" name="ldk_meta_description" value="<?php echo $value['ldk_meta_description']?>" />
			
			
			<br /><br />
            <label>Meta robots</label>
				<input type="text" placeholder="index,follow" name="ldk_meta_robots" value="<?php echo $value['ldk_meta_robots']?>" />
			
			
			<br /><br />

             <label> Meta author</label>
				<input placeholder="<?php echo get_the_author_meta('display_name',$post->post_author)?>" type="text"  name="ldk_meta_author" value="<?php echo $value['ldk_meta_author']?>" />
			
			
			<br /><br />
            </div>
        <?php
		
	}


	
	public function lkd_save_metadata_box_data( $post_id ) {

		

		
		if ( ! isset( $_POST['ldk_metadata_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ldk_metadata_box_nonce'], 'lkd_save_metadata_box_data' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		
		if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
		}
		

        $my_data_array=array();

        if(isset($_POST['ldk_meta_title']))
            $my_data_array['ldk_meta_title']=$_POST['ldk_meta_title'];

        if(isset($_POST['ldk_meta_description']))
            $my_data_array['ldk_meta_description']=$_POST['ldk_meta_description'];

        if(isset($_POST['ldk_meta_robots']))
            $my_data_array['ldk_meta_robots']=$_POST['ldk_meta_robots'];

        if(isset($_POST['ldk_meta_author']))
            $my_data_array['ldk_meta_author']=$_POST['ldk_meta_author'];
	
        
        
        if(count($my_data_array)>0)
		    update_post_meta( $post_id, 'ldk_metadata', $my_data_array );
	}
	
	
}