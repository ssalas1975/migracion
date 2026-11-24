<?php

/**
 * Class1 short summary.
 *
 * Class1 description.
 *
 * @version 1.0
 * @author j.marin
 */
class VocentoInfoMetaBox
{
    //le pasamos al constructor los hooks
    function __construct() {
        
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
            
            add_action( 'save_post',      array( $this, 'save_metabox' ), 10 ,2);
        }
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'VOC_infoperiodica_meta_boxes' ) );
    }

    //añadiendo metabox
    function VOC_infoperiodica_meta_boxes() {
        add_meta_box( 'voc-short-meta-box-info-periodica', 'Tipo información periódica', array( $this, 'meta_box_callback_voc_infoperiodica'), 'post', 'side', 'high' );
     }
	
    //funcion que pinta el metabox
    function meta_box_callback_voc_infoperiodica() {
        global $post;
        $link_type=get_post_meta( $post->ID, 'custom_permalink_type',true);
?>
		<p>Seleccione el tipo de contenido (Solo aplicará a documentos de Información financiera)</p>

		<select id='voccominfo-type' name='voccominfo-type' style="width:100%">
			<option <?php echo (!$link_type)?"selected":"" ?> value="">Ninguno</option>
			<option <?php echo ($link_type=="informe-resultados")?"selected":"" ?> value="informe-resultados">Informe</option>
			<option <?php echo ($link_type=="presentacion-resultados")?"selected":"" ?> value="presentacion-resultados">Presentación</option>
			<option <?php echo ($link_type=="datos-financieros")?"selected":"" ?> value="datos-financieros">Datos</option>
			<option <?php echo ($link_type=="call-resultados")?"selected":"" ?> value="call-resultados">Call</option>
		</select>

        <?php
		
        
    }


    public function save_metabox( $post_id, $post ) {

        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
        
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }


        if(in_category('informacion-periodica',$post_id)){

            $original_link=$this->custom_permalinks_original_post_link($post_id);
            if($_REQUEST['voccominfo-type']) {
                delete_post_meta($post_id,'custom_permalink');
                delete_post_meta($post_id,'custom_permalink_type');
                add_post_meta( $post_id, 'custom_permalink', str_replace('%2F', '/', urlencode(ltrim("/accionistas-inversores/informacion-financiera/".get_the_date( 'Y', $post_id )."/".$_REQUEST['voccominfo-type']."/".$post->post_name."/","/"))));
                add_post_meta( $post_id, 'custom_permalink_type', $_REQUEST['voccominfo-type']);
            }
            else{
                delete_post_meta($post_id,'custom_permalink');
                delete_post_meta($post_id,'custom_permalink_type');
            }
        }
    }

    function custom_permalinks_original_post_link($post_id) {
        remove_filter( 'post_link', 'custom_permalinks_post_link', 'edit_files', 2 ); // original hook
        remove_filter( 'post_type_link', 'custom_permalinks_post_link', 'edit_files', 2 );
        
        require_once ABSPATH . '/wp-admin/includes/post.php';
        list( $permalink, $post_name ) = get_sample_permalink( $post_id );
        $permalink = str_replace(array('%pagename%','%postname%'), $post_name, $permalink);
        $permalink = ltrim(str_replace(home_url(), '', $permalink), '/');
        
        add_filter( 'post_link', 'custom_permalinks_post_link', 'edit_files', 2 ); // original hook
        add_filter( 'post_type_link', 'custom_permalinks_post_link', 'edit_files', 2 );
        
        return $permalink;
    }


}