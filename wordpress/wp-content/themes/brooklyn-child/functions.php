<?php


function ut_child_theme_enqueue_styles()
{
    $parent_style = 'ut-main-style';

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'ut-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style)
    );
}

add_action('wp_enqueue_scripts', 'ut_child_theme_enqueue_styles');





/******************** GONATURAL *********************************************/
switch_to_blog(get_current_blog_id());

// Only add shortcode if Contact Form 7 is available
if (function_exists('wpcf7_add_shortcode')) {
    add_action('wpcf7_init', 'custom_add_shortcode');
    function custom_add_shortcode()
    {
        wpcf7_add_shortcode('event', 'custom_shortcode_handler');
    }
}
function custom_shortcode_handler($atts)
{
    // Check if array exists to avoid notices
    if (!isset($atts['values']) || !is_array($atts['values']) || empty($atts['values'])) {
        return '';
    }
    $idpost = $atts['values'][0];
    $evento = get_post_meta($idpost, 'data_event');
    if (!is_array($evento) || empty($evento)) {
        return '';
    }
    $users = get_users(array('meta_key' => 'idevento'));
    $count = 0;
    foreach ($users as $user) {
        $data = get_user_meta($user->ID, 'idevento');
        //print_r($data);
        if (is_array($data) && !empty($data[0]) && in_array($idpost, $data[0])) {
            $count++;
        }
    }
    $totalpax = isset($evento[0]['asistentes']) ? (int)$evento[0]['asistentes'] : 0;
    if ($totalpax - $count > 0) {
        return '<p class="checkBox clases">
<font style="color: #a1a1a1; display: block; width: 75%">' . $evento[0]["hora"] . 'H - ' . $evento[0]['nombre'] . ' ' . $evento[0]["docente"] . '</font>
<aviso style="background: #e9e9e9; padding: 5px; float: right; margin-top: -20px">AFORO COMPLETO</aviso>
</p> ';
    }
}


switch_to_blog(get_current_blog_id());

//  add_action("wpcf7_before_send_mail", "wpcf7_do_something_else");
//  function wpcf7_do_something_else( $cf7 ) {
//       $submission = WPCF7_Submission::get_instance();

//     if ( $submission ) {
//        $posted_data = $submission->get_posted_data();
//       var_dump( $posted_data );
//     }
//  }


function create_user_from_registration($cfdata)
{
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        $formdata = $cfdata->posted_data;
    }
    $password = wp_generate_password(12, false);
    $email = $formdata["your-email"];
    $name = $formdata["your-name"];
    $valordato = $formdata["idevento"];
    // Construct a username from the user's name
    $username = strtolower(str_replace(' ', '', $name));
    $name_parts = explode(' ', $name);
    $last_name = $formdata["your-surname"];
    $comonosconocio = $formdata['checkbox-454'];
    if (!email_exists($email)) {
        // Find an unused username
        $username_tocheck = $username;
        $i = 1;
        while (username_exists($username_tocheck)) {
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
        $user_id = wp_insert_user($userdata);
        if ($valordato != null) {
            add_user_meta($user_id, 'idevento', $valordato);
        }

        update_user_meta($user_id, 'comonosconocio', $comonosconocio);
    } else {
        $user_id = get_user_by('email', $email);
        if ($valordato != null) {
            $data = get_user_meta($user_id->ID, 'idevento', true);
            if (empty($data)) {
                add_user_meta($user_id->ID, 'idevento', $valordato);
            } else {
                foreach ($valordato as $valor) {
                    if (in_array($valor, $data)) {
                        //update_user_meta( $user_id->ID, 'idevento', $data );
                    } else {
                        array_push($data, $valor);
                    }
                }

                update_user_meta($user_id->ID, 'idevento', $data);
                update_user_meta($user_id->ID, 'comonosconocio', $comonosconocio);
            }
        }
    }

    if (get_current_blog_id() == 7) {
        $mailContent = '
        <html>
            <head>
            <meta charset="UTF-8">
            <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
            <style type="text/css">
            body,td,th {
                font-family: Poppins, sans-serif;
                font-size: 16px;
                text-align: center;
                color: #888888;
                font-weight: normal;
            }
            a:link {
                text-decoration: none;
            }
            a:visited {
                text-decoration: none;
            }
            a:hover {
                text-decoration: none;
            }
            a:active {
                text-decoration: none;
            }
            </style>
            </head>
            <body>
            <p align="center"><img src="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2019/05/logo.png" width="400"></p>
            <p align="center">¡Hola!</p>
            <p align="center"> Te damos la bienvenida a <strong style="color: #6FB140">#mujerhoyGONatural</strong>!<br>
            Ven y disfruta de un día lleno de actividades saludables e inspiradoras ¡para todas!
            </p>

            <p align="center" style="margin-top: 50px; margin-bottom: 50px"><a href="https://gonatural.mujerhoy.com/wp-content/uploads/sites/11/2018/05/Invitacion.pdf" style="padding: 10px; background-color: #6FB140; color: white; ">DESCARGA AQUÍ TU INVITACIÓN</a></p>
            ';

        if ($valordato != null || $valordato != "" || isset($formdata["idevento"])) {
            $mailContent .= ' 
            <p align="center">Te confirmamos tu plaza en:</p>
            <div align="center" style="color: #6FB140">
           ';

            foreach ($valordato as $eventos) {
                $evento = get_post_meta($eventos, 'data_event', true);
                //$evento['docente']
                //$evento['hora']
                $mailContent .= cleanString($evento['hora']) . '   ' . cleanString(strtoupper($evento['nombre'])) . '<br>';
            }

            $mailContent .= '
            </div>
            <p align="center">¡Recuerda!</p>
                <p align="center" style="font-size: 10px; line-height: 12px; margin-bottom: 40px">*Ven con ropa deportiva o cómoda<br>*Contarás con el material deportivo necesario para la realización de las clases<br>*Si te surge algo y no puedes venir ¡avísanos! Otra persona podrá ocupar tu plaza. Estamos en: eventosmujerhoy@vocento.com</p>
            ';
        }
        $mailContent .= '
            <p align="center">¡Te esperamos!<br><strong style="color: #000">Equipo mujerhoy</strong></p>

            <p align="center"><strong style="color: #6FB140"><a href="http://gonatural.mujerhoy.com/" style="color: #6FB140">gonatural.mujerhoy.com<br>#mujerhoyGONatural<br>#tesientabien</a></strong></p>
            </body>
        </html>
        ';

        $mail = $cfdata->prop('mail');
        $mail_2 = $cfdata->prop('mail_2');
        $mail['body'] = $mailContent;
        $mail_2['body'] = $mailContent;
        $mail["sender"] = "GO NATURAL 2019";
        $mail_2["sender"] = "GO NATURAL 2019";
        if ($valordato != null || $valordato != "" || isset($formdata["idevento"])) {
            $mail_2['subject'] = 'INVITACIÓN/CONFIRMACIÓN CLASE mujerhoy GO NATURAL - 29 junio 2019';
        } else {
            $mail_2['subject'] = 'INVITACIÓN mujerhoy GO NATURAL - 29 junio 2019';
        }
        // set mail property with changed value(s)
        $cfdata->set_properties(array('mail' => $mail));
        $cfdata->set_properties(array('mail_2' => $mail_2));
    }

    return $cfdata;
}
add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1, 2);


function cleanString($text)
{
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚``´´]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function wp_delete_post_link($post)
{
    $link = wp_nonce_url(get_bloginfo('url') . "/wp-admin/post.php?action=delete&amp;post=" . $post, 'delete-post_' . $post);
    return $link;
}



add_action('init', 'init_register_post_type_evento');

function init_register_post_type_evento()
{
    register_post_type('evento', array(
        'plural_name' => 'Eventos',
        'singular_name' => 'Evento',
        'post_type_name' => 'evento',
        'description' => 'Evnetos',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'rewrite' =>
        array(
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
        'rest_base' => null,
        'rest_controller_class' => null,
        'supports' =>
        array(
            0 => 'title',
        ),
        'taxonomies' =>
        array(
            0 => '',
        ),
        'label' => 'Eventos',
        'title_placeholder' => 'Enter evento name',
        'labels' =>
        array(
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

add_action('init', 'gonatural_register_meta_fields');

function gonatural_register_meta_fields()
{
    register_meta('evento', 'data_event', [
        'description' => _x('Nombre del evento', 'meta description', 'cyb-textdomain'),
        'single' => true
    ]);
}

add_action('add_meta_boxes', 'gn_meta_boxes');

function gn_meta_boxes()
{
    add_meta_box('gonatural-meta-box', 'Datos del Evento', 'gn_nombre_callback', 'evento');
}

function gn_nombre_callback($post)
{
    $current_value = get_post_meta($post->ID, 'data_event', true);
    wp_nonce_field('gn_meta_box', 'gn_meta_box_noncename'); ?>
    <p>
        <label class="label" for="gn_name"><?php _e('Nombre del Evento', 'gn_textdomain'); ?></label>
        <input name="gn_name" id="gn_name" type="text" value="<?php echo $current_value['nombre']; ?>">
    </p>

    <p>
        <label class="label" for="gn_docente"><?php _e('Docente', 'gn_textdomain'); ?></label>
        <input name="gn_docente" id="gn_docente" type="text" value="<?php echo $current_value["docente"]; ?>">
    </p>

    <p>
        <label class="label" for="gn_hora"><?php _e('Introduce tu nombre', 'gn_textdomain'); ?></label>
        <input name="gn_hora" id="gn_hora" type="text" value="<?php echo $current_value["hora"]; ?>">
    </p>

    <p>
        <label class="label" for="gn_asistentes"><?php _e('Introduce tu nombre', 'gn_textdomain'); ?></label>
        <input name="gn_asistentes" id="gn_asistentes" type="text" value="<?php echo $current_value["asistentes"]; ?>">
    </p>
    <?php
}

add_action('save_post', 'gn_save_custom_fields', 10, 2);
function gn_save_custom_fields($post_id, $post)
{
    if (!isset($_POST['gn_meta_box_noncename']) || !wp_verify_nonce($_POST['gn_meta_box_noncename'], 'gn_meta_box')) {
        return;
    }
    if (isset($_POST['gn_name']) && $_POST['gn_name'] != "") {
        $temp = array();
        $temp['docente'] = $_POST['gn_docente'];
        $temp['nombre'] = $_POST['gn_name'];
        $temp['hora'] = $_POST['gn_hora'];
        $temp['asistentes'] = $_POST['gn_asistentes'];
        update_post_meta($post_id, 'data_event', $temp);
    }
}

add_filter('wpcf7_validate_configuration', '__return_false');

/*
* Añade boton a administración de usuarios justo debajo del menú superior
*/
add_action('admin_footer', 'add_club_filter_to_user_listing');
function add_club_filter_to_user_listing()
{
    global $pagenow;

    if ($pagenow == "users.php") {
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".tablenav.top").append("<div class='alignleft actions' style='margin: 1%;'><a href='<?php echo plugin_dir_url(); ?>gonatural-events/exportusersuscriber.php' class='exportar'><input type='button' name='exportUsers' id='exportUsers' class='button' value='Exportar Usuarios'></a></div><br class='clear'>");

                /*jQuery("#exportUsers").live("click", function(){
								   alert("Exportando usuarios...");
								   //si quieres redirigir..
								   //window.location = "/wp-admin/users.php?club="+club_id_value;
				   });*/
            });
        </script>
<?php
    }
}

/*
* Permite a los usuarios administradores editar los usuarios del site
*/
function mc_admin_users_caps($caps, $cap, $user_id, $args)
{
    foreach ($caps as $key => $capability) {
        if ($capability != 'do_not_allow') {
            continue;
        }

        switch ($cap) {
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
if (get_current_blog_id() == 3) {
    add_filter('map_meta_cap', 'mc_admin_users_caps', 10, 4);

    remove_all_filters('enable_edit_any_user_configuration');
    add_filter('enable_edit_any_user_configuration', '__return_true');

    /**
     * Checks that both the editing user and the user being edited are
     * members of the blog and prevents the super admin being edited.
     */
    function mc_edit_permission_check()
    {
        global $current_user, $profileuser;

        $screen = get_current_screen();

        get_currentuserinfo();

        if (!is_super_admin($current_user->ID) && in_array($screen->base, array('user-edit', 'user-edit-network'))) { // editing a user profile
            if (is_super_admin($profileuser->ID)) { // trying to edit a superadmin while less than a superadmin
                wp_die(__('You do not have permission to edit this user.'));
            } elseif (!(is_user_member_of_blog($profileuser->ID, get_current_blog_id()) && is_user_member_of_blog($current_user->ID, get_current_blog_id()))) { // editing user and edited user aren't members of the same blog
                wp_die(__('You do not have permission to edit this user.'));
            }
        }
    }
    add_filter('admin_head', 'mc_edit_permission_check', 1, 4);
}
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});
