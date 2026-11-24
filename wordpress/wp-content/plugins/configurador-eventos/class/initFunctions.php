<?php 

add_action( 'init', 'init_register_post_type_evento' );

function init_register_post_type_evento(){

register_post_type( 'evento', array (
  'plural_name' => 'Eventos',
  'singular_name' => 'Evento',
  'post_type_name' => 'evento',
  'description' => 'Evnetos',
  'hierarchical' => false,
  'public' => true,
  'show_ui' => true,
  'rewrite' => 
  array (
    'slug' => 'evento',
    'with_front' => false,
    'feeds' => true,
    'pages' => true,
  ),
  'show_in_rest' => false,
  'has_archive' => false,
  'exclude_from_search' => false,
  'publicly_queryable' => true,
  'can_export' => true,
  'show_in_nav_menus' => true,
  'show_in_menu' => true,
  'show_under_a_parent' => '',
  'show_under_parent' => '',
  'menu_position' => 5,
  'menu_position_custom' => '',
  'menu_icon' => 'dashicons-admin-post',
  'show_in_admin_bar' => true,
  'auto_generate_labels' => '1',
  'label_add_new' => 'Add New',
  'label_add_new_item' => 'Add New Evento',
  'label_edit_item' => 'Edit Evento',
  'label_new_item' => 'New Evento',
  'label_view_item' => 'View Evento',
  'label_search_items' => 'Search Eventos',
  'label_not_found' => 'No eventos found',
  'label_not_found_in_trash' => 'No eventos found in Trash',
  'label_parent_item_colon' => 'Parent Evento',
  'label_all_items' => 'All Eventos',
  'label_archives' => 'Eventos Archives',
  'label_insert_into_item' => 'Insert into evento',
  'label_uploaded_to_this_item' => 'Uploaded to this evento',
  'label_featured_image' => 'Featured Image',
  'label_set_featured_image' => 'Set featured image',
  'label_remove_featured_image' => 'Remove featured image',
  'label_use_featured_image' => 'Use as featured image',
  'label_menu_name' => 'Eventos',
  'label_filter_items_list' => 'Eventos',
  'label_items_list_navigation' => 'Eventos',
  'label_items_list' => 'Eventos',
  'label_name_admin_bar' => 'Evento',
  'rewrite_with_front' => false,
  'rewrite_slug' => 'evento',
  'rewrite_feeds' => true,
  'rewrite_pages' => true,
  'rest_base' => NULL,
  'rest_controller_class' => NULL,
  'supports' => 
  array (
    0 => 'title',
  ),
  'taxonomies' => 
  array (
    0 => '',
  ),
  'label' => 'Eventos',
  'title_placeholder' => 'Enter evento name',
  'labels' => 
  array (
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Evento',
    'edit_item' => 'Edit Evento',
    'new_item' => 'New Evento',
    'view_item' => 'View Evento',
    'search_items' => 'Search Eventos',
    'not_found' => 'No eventos found',
    'not_found_in_trash' => 'No eventos found in Trash',
    'parent_item_colon' => 'Parent Evento',
    'all_items' => 'All Eventos',
    'archives' => 'Eventos Archives',
    'insert_into_item' => 'Insert into evento',
    'uploaded_to_this_item' => 'Uploaded to this evento',
    'featured_image' => 'Featured Image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image',
    'menu_name' => 'Eventos',
    'filter_items_list' => 'Eventos',
    'items_list_navigation' => 'Eventos',
    'items_list' => 'Eventos',
    'name_admin_bar' => 'Evento',
  ),
  'dashicon_unicode_number' => 109,
));

}

add_action('init', 'eventos_register_meta_fields');

function eventos_register_meta_fields() {
  $args = array(
      'description' => _x('Nombre del evento', 'meta description', 'cyb-textdomain'),
      'single' => true
  );
    register_meta('evento', 'data_event', $args);
}

add_action('add_meta_boxes', 'ce_meta_boxes');

function ce_meta_boxes() {
    add_meta_box('conf-eventos-meta-box', 'Datos del Evento', 'ce_nombre_callback', 'evento');
}

function ce_nombre_callback($post) {
    $current_value = get_post_meta($post->ID, 'data_event', true);
    wp_nonce_field( 'ce_meta_box', 'ce_meta_box_noncename' );
    ?>
    <p>
        <label class="label" for="ce_name"><?php _e('Nombre del Evento', 'ce_textdomain'); ?></label>
        <input  name="ce_name" id="ce_name" type="text" value="<?php echo $current_value['nombre']; ?>">
    </p>

    <p>
        <label class="label" for="ce_docente"><?php _e('Docente', 'ce_textdomain'); ?></label>
        <input  name="ce_docente" id="ce_docente" type="text" value="<?php echo $current_value["docente"]; ?>">
    </p>

    <p>
        <label class="label" for="ce_hora"><?php _e('Introduce tu nombre', 'ce_textdomain'); ?></label>
        <input  name="ce_hora" id="ce_hora" type="text" value="<?php echo $current_value["hora"]; ?>">
    </p>

    <p>
        <label class="label" for="ce_asistentes"><?php _e('Introduce tu nombre', 'ce_textdomain'); ?></label>
        <input  name="ce_asistentes" id="ce_asistentes" type="text" value="<?php echo $current_value["asistentes"]; ?>">
    </p>
    <?php
}

add_action( 'save_post', 'ce_save_custom_fields', 10, 2);
function ce_save_custom_fields( $post_id, $post )
{
    if ( ! isset( $_POST['ce_meta_box_noncename'] ) || ! wp_verify_nonce( $_POST['ce_meta_box_noncename'], 'ce_meta_box' ) ) {
        return;
    }
    if( isset( $_POST['ce_name'] ) && $_POST['ce_name'] != "" ) {
        $temp = array();
        $temp['docente'] = $_POST['ce_docente'];
        $temp['nombre'] = $_POST['ce_name'];
        $temp['hora'] = $_POST['ce_hora'];
        $temp['asistentes'] = $_POST['ce_asistentes'];
        update_post_meta( $post_id, 'data_event', $temp );
    }
}



/*
* Añade boton a administración de usuarios justo debajo del menú superior
*/
add_action('admin_footer','add_club_filter_to_user_listing');
function add_club_filter_to_user_listing() {
                
    global $pagenow;

    if($pagenow == "users.php"){

       ?>
       <script type="text/javascript">
       jQuery(document).ready(function(){
       jQuery(".tablenav.top").append("<div class='alignleft actions' style='margin: 1%;'><a href='<?php echo plugin_dir_url(); ?>configurador-eventos/exportusersuscriber2018.php' class='exportar'><input type='button' name='exportUsers' id='exportUsers' class='button' value='Exportar Usuarios'></a></div><br class='clear'>");

       });
       </script>
       <?php
    }
}

/*
* Permite a los usuarios administradores editar los usuarios del site
*/ 
function mc_admin_users_caps( $caps, $cap, $user_id, $args ){             
 
    foreach( $caps as $key => $capability ){

        if( $capability != 'do_not_allow' )
            continue;

        switch( $cap ) {
            case 'edit_user':
            case 'edit_users':
                $caps[$key] = 'edit_users';
                break;
            case 'delete_user':
            case 'delete_users':
                $caps[$key] = 'delete_users';
                break;
            case 'create_users':
                $caps[$key] = $cap;
                break;
        }
    }
    return $caps;
                
}
//indicar el ID del blog para permitar la edicion de usuarios a administradores
if(get_current_blog_id() == 1){

add_filter( 'map_meta_cap', 'mc_admin_users_caps', 10, 4 );

remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');

/**
* Checks that both the editing user and the user being edited are
* members of the blog and prevents the super admin being edited.
*/
function mc_edit_permission_check() {
    global $current_user, $profileuser;

    $screen = get_current_screen();

    get_currentuserinfo();

    if( ! is_super_admin( $current_user->ID ) && in_array( $screen->base, array( 'user-edit', 'user-edit-network' ) ) ) { // editing a user profile
        if ( is_super_admin( $profileuser->ID ) ) { // trying to edit a superadmin while less than a superadmin
            wp_die( __( 'You do not have permission to edit this user.' ) );
        } elseif ( ! ( is_user_member_of_blog( $profileuser->ID, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
            wp_die( __( 'You do not have permission to edit this user.' ) );
        }
    }

}
add_filter( 'admin_head', 'mc_edit_permission_check', 1, 4 );

}


function wp_delete_post_link($post)
{
  $link = wp_nonce_url( get_bloginfo('url') . "/wp-admin/post.php?action=delete&amp;post=" . $post, 'delete-post_' . $post);
  return $link;
}


remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');





function check_some_other_plugin() {
  include_once(ABSPATH .'wp-admin/includes/plugin.php');

  if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
  add_action( 'wpcf7_init', 'custom_add_shortcode' );
    function custom_add_shortcode() {
      wpcf7_add_shortcode( 'event', 'custom_shortcode_handler' );
    }
  }
}

add_action( 'init', 'check_some_other_plugin' );
add_filter( 'wpcf7_validate_configuration', '__return_false' );

function custom_shortcode_handler( $atts ) {
    $idpost = $atts[values][0];
    $evento = get_post_meta($idpost,'data_event');
    $users = get_users(array('meta_key' => 'idevento'));
    $count = 0;
    foreach ($users as $user)
    {
        $data = get_user_meta( $user->ID, 'idevento' );
        //print_r($data);
        if (in_array($idpost, $data[0])) {
            $count ++;
        }
    }
    $totalpax = $evento[0]['asistentes'];
    if( $totalpax - $count > 0 )
    {
        return '<p class="checkBox clases">
            <font class="verde">'.$evento[0]["hora"].'H</font> - '.$evento[0]['nombre'].' '.$evento[0]["docente"].'
            <span class="wpcf7-form-control-wrap checkbox-455"><span class="wpcf7-form-control wpcf7-checkbox">
            <span class="wpcf7-list-item first last"><label><span class="wpcf7-list-item-label"></span>&nbsp;
            <input type="checkbox" name="idevento[]"  value="'.$atts[values][0].'"></label></span></span></span></p>';
    }else{
        return '<p class="checkBox clases">
<font style="color: #a1a1a1; display: block; width: 75%">'.$evento[0]["hora"].'H - '.$evento[0]['nombre'].' '.$evento[0]["docente"].'</font>
<aviso style="background: #e9e9e9; padding: 5px; float: right; margin-top: -20px">AFORO COMPLETO</aviso>
</p> ';
    }
    
    
}
 
 
 
function create_user_from_registration($cfdata) {


        if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $formdata = $submission->get_posted_data();
            }
        } elseif (isset($cfdata->posted_data)) {
            $formdata = $cfdata->posted_data;
        }


        $password = wp_generate_password( 12, false );
        $url_autor = $formdata["your-url"];
        $email = $formdata["your-email"];
        $name = $formdata["your-name"];
        $valordato = $formdata["idevento"];
        // Construct a username from the user's name
        $username = strtolower(str_replace(' ', '', $name));
        $name_parts = explode(' ',$name);
        $last_name = $formdata["your-surname"];
        $comonosconocio = $formdata['checkbox-454'];
        if ( !email_exists( $email ) )
        {
            // Find an unused username
            $username_tocheck = $username;
            $i = 1;
            while ( username_exists( $username_tocheck ) ) {
                $username_tocheck = $username . $i++;
            }
            $username = $username_tocheck;
            // Create the user
            $userdata = array(
                'user_login' => $username,
                'user_pass' => $password,
                'user_email' => $email,
                'nickname' => reset($name_parts),
                'display_name' => $name,
                'first_name' => reset($name_parts),
                'last_name' => $last_name,
                'role' => $cf7frr
            );
            $user_id = wp_insert_user( $userdata );
            if($valordato != null)
            {
                add_user_meta( $user_id, 'idevento', $valordato );
            }

            update_user_meta( $user_id, 'url_autor', $url_autor );
                
            update_user_meta( $user_id, 'comonosconocio', $comonosconocio );
            
        }else{
            $user_id = get_user_by( 'email' , $email );
            if($valordato != null)
            {
                $data = get_user_meta( $user_id->ID, 'idevento' , true );
                if( empty($data) )
                {
                    update_user_meta( $user_id->ID, 'idevento', $valordato );
                }else{
                    
                    foreach( $valordato as $valor )
                    {
                        if (in_array($valor, $data)) {
                            //update_user_meta( $user_id->ID, 'idevento', $data );
                        }else{
                            array_push( $data, $valor );
                        }
                        
                    }
                    
                    update_user_meta( $user_id->ID, 'idevento', $data );
                    update_user_meta( $user_id, 'url_autor', $url_autor );
                    update_user_meta( $user_id->ID, 'comonosconocio', $comonosconocio );
                }
            }
        }
            //die();
    return $cfdata;
}
add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1, 2);



?>