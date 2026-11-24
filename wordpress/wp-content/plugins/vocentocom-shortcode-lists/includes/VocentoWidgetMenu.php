<?php

/**
 * Class1 short summary.
 *
 * Class1 description.
 *
 * @version 1.0
 * @author j.marin
 */
class VocentoWidgetShortcodeMenu
{
    //le pasamos al constructor los hooks
    function __construct() {
        
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'VOC_shortcode_meta_boxes' ) );
    }

    //añadiendo metabox
    function VOC_shortcode_meta_boxes() {
        add_meta_box( 'voc-short-meta-box-menú', 'Shortcode listas menú', array( $this, 'meta_box_callback_voc_shortcode'), 'post', 'side', 'high', array( 'arg' => 'value') );
        add_meta_box( 'voc-short-meta-box-menú', 'Shortcode listas menú', array( $this, 'meta_box_callback_voc_shortcode'), 'page', 'side', 'high', array( 'arg' => 'value') );
    }
    //funcion que pinta el metabox
    function meta_box_callback_voc_shortcode() {
        echo "<script type='text/javascript'>
                    jQuery(document).ready(function($){
                        $('#inserShortMenu').click(function(e) {                           
	                    var shortcode = $('#vocShrotcodeMenu').val();
                        var body = $('#content_ifr').contents().find('body');
                        body.append(shortcode);
                    });
                });
            </script>";

        echo "<label for='porcentaje'> Shortcode </label><br />
                <input type='text' id='vocShrotcodeMenu' value='[vccom_menu category=\"\" years=\"\" ]' readonly/><br /><label for='porcentaje'> Shortcode para accionistas</label> <input type='text' id='vocShrotcodeMenu' value='[vccom_menu_accionistas category=\"\" years=\"\" ]' readonly/><br /><blockquote>**Recuerde rellenar el atributo \"<b>category</b>\", puede verlas pinchando <a href=\"/wp-admin/edit-tags.php?taxonomy=category\" target=\"_blank\"> AQUI</a> <br /></blockquote>"; 
    }


}