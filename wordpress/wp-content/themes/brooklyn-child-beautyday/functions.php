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

/*
 * Modificación de dominio a dominio+path
 */
function beauty_replace_domain($content)
{
    $protocol = getHTTPProtocol();

    $content = str_replace("https://beautyday2017-mujerhoy.srv.vocento.in", $protocol . "beautyday.mujerhoy.com/2017", $content);
    $content = str_replace("beautyday2017-mujerhoy.srv.vocento.in", "beautyday.mujerhoy.com/2017", $content);

    return $content;
}

function beauty_replace_content($content)
{
    $protocol = getHTTPProtocol();

    $content = preg_replace("/https:\/\/beautyday2017\-mujerhoy\.srv\.vocento\.in/", $protocol . "beautyday.mujerhoy.com/2017", $content);
    $content = preg_replace("/http:\/\/beautyday2017\-mujerhoy\.srv\.vocento\.in/", $protocol . "beautyday.mujerhoy.com/2017", $content);

    $content = preg_replace("/https:\/\/beautyday\-mujerhoy\.srv\.vocento\.in\/summer/", $protocol . "beautyday.mujerhoy.com", $content);
    $content = preg_replace("/beautyday\-mujerhoy\.srv\.vocento\.in\/summer/", "beautyday.mujerhoy.com", $content);

    $content = preg_replace("/beautyday\.mujerhoy\.com\/wp\-content/", $protocol . "beautyday.mujerhoy.com/2017/wp-content", $content);

    $content = preg_replace("/\"\/wp-content\/uploads\//", "\"/2017/wp-content/uploads/", $content);

    return $content;
}

/*if($_SERVER['HTTP_HOST']=="beautyday.mujerhoy.com"){ //
    add_filter('script_uri', 'beauty_replace_content');
    add_filter('stylesheet_uri', 'beauty_replace_content');
    add_filter('bloginfo_url', 'beauty_replace_content');
    add_filter('bloginfo', 'beauty_replace_content');
    add_filter('post_link', 'beauty_replace_content');
    add_filter('category_link', 'beauty_replace_content');
    add_filter('category_feed_link', 'beauty_replace_content');
    add_filter('wp_get_attachment_url', 'beauty_replace_content');
    add_filter('wp_get_attachment_thumb_url', 'beauty_replace_content');
    add_filter('post_comments_feed_link', 'beauty_replace_content');
    add_filter('author_link', 'beauty_replace_content');
    add_filter('author_feed_link', 'beauty_replace_content');
    add_filter('plugins_url', 'beauty_replace_content');
    add_filter('bloginfo_rss', 'beauty_replace_content');
    add_filter('home_url', 'beauty_replace_content');
    add_filter('script_loader_src', 'beauty_replace_content');
    add_filter('style_loader_src', 'beauty_replace_content');
    add_filter('stylesheet_directory_uri', 'beauty_replace_content');
    add_filter('site_url', 'beauty_replace_content');
    add_filter('esc_url', 'beauty_replace_content');
}*/

add_filter('the_content', 'beauty_replace_content');

function getHTTPProtocol()
{
    return ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
}


/* permitir subir archivos con extensión svg*/
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes($existing_mimes = array())
{
    // Add the file extension to the array
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['svgz'] = 'image/svg+xml';
    return $existing_mimes;
}



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

    $bogId = get_current_blog_id();

    $args = array(
        'blog_id' => $GLOBALS['blog_id'],
        'role'    => 'subscriber',
        'orderby' => 'user_nicename',
        'order'   => 'ASC'
    );
    $users = get_users($args);

    if (count($users) >= 3000) {
        $cfdata->set_properties(array('additional_settings' => "on_sent_ok: \"location = '/2019/evento-completo/'\""));
        $mail = $cfdata->prop('mail');
        $mail_2 = $cfdata->prop('mail_2');
        $mail['body'] = "";
        $mail_2['body'] = "";
        $mail["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
        $mail_2["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
        $mail_2['subject'] = 'Aquí tienes tu INVITACIÓN BEAUTY Night mujerhoy - 26 noviembre';
        $mail['recipient'] = "";
        $mail_2['recipient'] = "";
        // set mail property with changed value(s)
        $cfdata->set_properties(array('mail' => $mail));
        $cfdata->set_properties(array('mail_2' => $mail_2));
        return $cfdata;
    } else {
        $password = wp_generate_password(12, false);
        $email = $formdata["your-email"];
        $name = $formdata["your-name"];
        $username = strtolower(str_replace(' ', '', $name));
        $name_parts = explode(' ', $name);
        $last_name = $formdata["your-surname"];
        $comonosconocio = $formdata['checkbox-454'];
        $result = '';

        $emailUsers = getEmailUsers($users);

        if (in_array($email, $emailUsers)) {
            // $cfdata->skip_mail = true;
            $user = get_user_by('email', $email);
            $userid = $user->ID;
            $blogid = $GLOBALS['blog_id'];
            $role = 'subscriber';
            add_user_to_blog($blogid, $userid, $role);

            /**
             * llamada api para generar qr
             */

            $payload = json_encode(
                array(
                    'apikey' => 'a7fb09aa3de5f8',
                    'id' => $user->ID,
                    'nombre' => $user->first_name,
                    'apellidos' => $user->last_name,
                    'email' => $user->user_email
                )
            );

            // Prepare new cURL resource
            $ch = curl_init('https://services.xeria.es/custom/BNaddTicket');
            //attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            //set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

            //return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute the POST request
            $result = curl_exec($ch);
            $data = json_decode($result, true);
            // Close cURL session handle
            curl_close($ch);
            $mailContent = '
                <html>
                <head>
                <meta charset="UTF-8">
                <link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
                <style type="text/css">
                body,td,th {
                                font-family: "Raleway", sans-serif;
                                font-size: 14px;
                                color: grey;
                }

                strong {
                    color: grey;
                }
                </style>
                </head>
                <body>

                <p align="center">¡Hola!</p>
                <p align="center" style="margin-bottom: 0px">Te damos la bienvenida a<br></p>
                <img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2019/10/email1-1.png" align="center" style="display: block; margin: auto; width: 450px;">
                <p align="center" style="margin-top: 0px; margin-bottom: 30px">Aquí tienes tu <strong><a href="' . $data['url'] . '" style="color: grey">INVITACIÓN</a></strong> </p>
                <p align="center" style="color: #ba007c"><strong style="color: #ba007c">26 noviembre 2019<br>
                17:00h - 22:00h</strong><br>
                LA RIVIERA<br>
                Paseo Bajo de la Virgen del Puerto s/n - MADRID</p>

                <p align="center" style="font-size: 20px"><strong>Sí, este año ¡acabaremos bailando!</strong></p>
                <p align="center">¡Te esperamos!</p>
                <p align="center"><img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2017/10/MujerHoy_logo_black-2.png" style="width: 150px; margin-top: 20px; margin-bottom:  20px"></p>
                <p align="center"><strong>beautyday.mujerhoy.com</strong><br>#ANewBEAUTY&nbsp;&nbsp;&nbsp;#mujerhoyBEAUTYRiviera&nbsp;&nbsp;&nbsp;#mujerhoy20</p>

                </body>
                </html>
            ';

            $mail = $cfdata->prop('mail');
            $mail_2 = $cfdata->prop('mail_2');
            $mail['body'] = $mailContent;
            $mail_2['body'] = $mailContent;
            $mail["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
            $mail_2["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
            $mail_2['subject'] = 'Aquí tienes tu INVITACIÓN BEAUTY Night mujerhoy - 26 noviembre';
            $mail_2['recipient'] = $user->user_email;
            // set mail property with changed value(s)
            $cfdata->set_properties(array('mail' => $mail));
            $cfdata->set_properties(array('mail_2' => $mail_2));
            $cfdata->set_properties(array('additional_settings' => "on_sent_ok: \"location = '/2019/registro-error/'\""));
            return $cfdata;
        } else {
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
                update_user_meta($user_id, 'comonosconocio', $comonosconocio);
                /**
                 * llamada api para generar qr
                 */

                $payload = json_encode(
                    array(
                        'apikey' => 'a7fb09aa3de5f8',
                        'id' => $user_id,
                        'nombre' => $username,
                        'apellidos' => $last_name,
                        'email' => $email
                    )
                );

                // Prepare new cURL resource
                $ch = curl_init('https://services.xeria.es/custom/BNaddTicket');
                //attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                //set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

                //return response instead of outputting
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //execute the POST request
                $result = curl_exec($ch);
                $data = json_decode($result, true);
                // Close cURL session handle
                curl_close($ch);
                $mailContent = '
                    <html>
                    <head>
                    <meta charset="UTF-8">
                    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
                    <style type="text/css">
                    body,td,th {
                                    font-family: "Raleway", sans-serif;
                                    font-size: 14px;
                                    color: grey;
                    }

                    strong {
                        color: grey;
                    }
                    </style>
                    </head>
                    <body>

                    <p align="center">¡Hola!</p>
                    <p align="center" style="margin-bottom: 0px">Te damos la bienvenida a<br></p>
                    <img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2019/10/email1-1.png" align="center" style="display: block; margin: auto; width: 450px;">
                    <p align="center" style="margin-top: 0px; margin-bottom: 30px">Aquí tienes tu <strong><a href="' . $data['url'] . '" style="color: grey">INVITACIÓN</a></strong> </p>
                    <p align="center" style="color: #ba007c"><strong style="color: #ba007c">26 noviembre 2019<br>
                    17:00h - 22:00h</strong><br>
                    LA RIVIERA<br>
                    Paseo Bajo de la Virgen del Puerto s/n - MADRID</p>

                    <p align="center" style="font-size: 20px"><strong>Sí, este año ¡acabaremos bailando!</strong></p>
                    <p align="center">¡Te esperamos!</p>
                    <p align="center"><img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2017/10/MujerHoy_logo_black-2.png" style="width: 150px; margin-top: 20px; margin-bottom:  20px"></p>
                    <p align="center"><strong>beautyday.mujerhoy.com</strong><br>#ANewBEAUTY&nbsp;&nbsp;&nbsp;#mujerhoyBEAUTYRiviera&nbsp;&nbsp;&nbsp;#mujerhoy20</p>

                    </body>
                    </html>
                ';

                $mail = $cfdata->prop('mail');
                $mail_2 = $cfdata->prop('mail_2');
                $mail['body'] = $mailContent;
                $mail_2['body'] = $mailContent;
                $mail["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
                $mail_2["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
                $mail_2['subject'] = 'Aquí tienes tu INVITACIÓN BEAUTY Night mujerhoy - 26 noviembre';
                $mail_2['recipient'] = $email;
                // set mail property with changed value(s)
                $cfdata->set_properties(array('mail' => $mail));
                $cfdata->set_properties(array('mail_2' => $mail_2));
                return $cfdata;
            } else {
                $user = get_user_by('email', $email);
                $userid = $user->ID;
                $blogid = $GLOBALS['blog_id'];
                $role = 'subscriber';
                add_user_to_blog($blogid, $userid, $role);

                /**
                 * llamada api para generar qr
                 */

                $payload = json_encode(
                    array(
                        'apikey' => 'a7fb09aa3de5f8',
                        'id' => $user->ID,
                        'nombre' => $user->first_name,
                        'apellidos' => $user->last_name,
                        'email' => $user->user_email
                    )
                );

                // Prepare new cURL resource
                $ch = curl_init('https://services.xeria.es/custom/BNaddTicket');
                //attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                //set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

                //return response instead of outputting
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //execute the POST request
                $result = curl_exec($ch);
                $data = json_decode($result, true);
                // Close cURL session handle
                curl_close($ch);
                $mailContent = '
                    <html>
                    <head>
                    <meta charset="UTF-8">
                    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
                    <style type="text/css">
                    body,td,th {
                                    font-family: "Raleway", sans-serif;
                                    font-size: 14px;
                                    color: grey;
                    }

                    strong {
                        color: grey;
                    }
                    </style>
                    </head>
                    <body>

                    <p align="center">¡Hola!</p>
                    <p align="center" style="margin-bottom: 0px">Te damos la bienvenida a<br></p>
                    <img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2019/10/email1-1.png" align="center" style="display: block; margin: auto; width: 450px;">
                    <p align="center" style="margin-top: 0px; margin-bottom: 30px">Aquí tienes tu <strong><a href="' . $data['url'] . '" style="color: grey">INVITACIÓN</a></strong> </p>
                    <p align="center" style="color: #ba007c"><strong style="color: #ba007c">26 noviembre 2019<br>
                    17:00h - 22:00h</strong><br>
                    LA RIVIERA<br>
                    Paseo Bajo de la Virgen del Puerto s/n - MADRID</p>

                    <p align="center" style="font-size: 20px"><strong>Sí, este año ¡acabaremos bailando!</strong></p>
                    <p align="center">¡Te esperamos!</p>
                    <p align="center"><img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2017/10/MujerHoy_logo_black-2.png" style="width: 150px; margin-top: 20px; margin-bottom:  20px"></p>
                    <p align="center"><strong>beautyday.mujerhoy.com</strong><br>#ANewBEAUTY&nbsp;&nbsp;&nbsp;#mujerhoyBEAUTYRiviera&nbsp;&nbsp;&nbsp;#mujerhoy20</p>

                    </body>
                    </html>
                ';

                $mail = $cfdata->prop('mail');
                $mail_2 = $cfdata->prop('mail_2');
                $mail['body'] = $mailContent;
                $mail_2['body'] = $mailContent;
                $mail["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
                $mail_2["sender"] = "BEAUTY Night mujerhoy <eventosmujerhoy@vocento.com>";
                $mail_2['subject'] = 'Aquí tienes tu INVITACIÓN BEAUTY Night mujerhoy - 26 noviembre';
                $mail_2['recipient'] = $user->user_email;
                // set mail property with changed value(s)
                $cfdata->set_properties(array('mail' => $mail));
                $cfdata->set_properties(array('mail_2' => $mail_2));
                return $cfdata;
            }
        }


        // if ( !email_exists( $email ) )
        // {
        //     // Find an unused username
        //     $username_tocheck = $username;
        //     $i = 1;
        //     while ( username_exists( $username_tocheck ) ) {
        //         $username_tocheck = $username . $i++;
        //     }
        //     $username = $username_tocheck;
        //     // Create the user
        //     $userdata = array(
        //         'user_login' => $username,
        //         'user_pass' => $password,
        //         'user_email' => $email,
        //         'nickname' => reset($name_parts),
        //         'display_name' => $name,
        //         'first_name' => reset($name_parts),
        //         'last_name' => $last_name,
        //         'role' => $cf7frr
        //     );
        //     $user_id = wp_insert_user( $userdata );
        //     update_user_meta( $user_id, 'comonosconocio', $comonosconocio );

        //     /**
        //      * llamada api para generar qr
        //      */

        //     $payload = json_encode(
        //         array(
        //             'apikey' => 'a7fb09aa3de5f8',
        //             'id' => $user_id,
        //             'nombre' => $username,
        //             'apellidos' => $last_name,
        //             'email' => $email
        //         )
        //     );

        //     // Prepare new cURL resource
        //     $ch = curl_init('https://services.xeria.es/custom/BNaddTicket');
        //     //attach encoded JSON string to the POST fields
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //     //set the content type to application/json
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //     //return response instead of outputting
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //     //execute the POST request
        //     $result = curl_exec($ch);
        //     $data = json_decode($result, true);
        //     // Close cURL session handle
        //     curl_close($ch);
        //     $mailContent = '
        //         <html>
        //         <head>
        //         <meta charset="UTF-8">
        //         <link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
        //         <style type="text/css">
        //         body,td,th {
        //                         font-family: "Raleway", sans-serif;
        //                         font-size: 14px;
        //                         color: grey;
        //         }

        //         strong {
        //             color: grey;
        //         }
        //         </style>
        //         </head>
        //         <body>

        //         <p align="center">¡Hola!</p>
        //         <p align="center" style="margin-bottom: 0px">Te damos la bienvenida a<br></p>
        //         <img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2019/10/email1-1.png" align="center" style="display: block; margin: auto; width: 450px;">
        //         <p align="center" style="margin-top: 0px; margin-bottom: 30px">Aquí tienes tu <strong><a href="'.$data['url'].'" style="color: grey">INVITACIÓN</a></strong> </p>
        //         <p align="center" style="color: #ba007c"><strong style="color: #ba007c">26 noviembre 2019<br>
        //         17:00h - 22:00h</strong><br>
        //         LA RIVIERA<br>
        //         Paseo Bajo de la Virgen del Puerto s/n - MADRID</p>

        //         <p align="center" style="font-size: 20px"><strong>Sí, este año ¡acabaremos bailando!</strong></p>
        //         <p align="center">¡Te esperamos!</p>
        //         <p align="center"><img src="http://beautyday.mujerhoy.com/2019/wp-content/uploads/sites/13/2017/10/MujerHoy_logo_black-2.png" style="width: 150px; margin-top: 20px; margin-bottom:  20px"></p>
        //         <p align="center"><strong>beautyday.mujerhoy.com</strong><br>#ANewBEAUTY&nbsp;&nbsp;&nbsp;#mujerhoyBEAUTYRiviera&nbsp;&nbsp;&nbsp;#mujerhoy20</p>

        //         </body>
        //         </html>
        //     ';

        //     $mail = $cfdata->prop( 'mail' );
        //     $mail_2 = $cfdata->prop( 'mail_2' );
        //     $mail['body'] = $mailContent;
        //     $mail_2['body'] = $mailContent;
        //     $mail["sender"] = "BEAUTY NIGHT <eventosmujerhoy@vocento.com>";
        //     $mail_2["sender"] = "BEAUTY NIGHT <eventosmujerhoy@vocento.com>";
        //     $mail_2['subject'] = 'Aquí tienes tu INVITACION BEAUTYNight mujerhoy';
        //     // set mail property with changed value(s)
        //     $cfdata->set_properties( array( 'mail' => $mail ) );
        //     $cfdata->set_properties( array( 'mail_2' => $mail_2 ) );

        //     return $cfdata;

        // }else{
        //     // $cfdata->skip_mail = true;
        //     $cfdata->set_properties( array( 'additional_settings' => "on_sent_ok: \"location = '/beautyday2019/?page_id=2'\"" ) );
        //     return $cfdata;
        // }
    }
}

add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1, 2);

function getEmailUsers($users)
{
    $arrayEmailUsers = [];
    foreach ($users as $user) {
        array_push($arrayEmailUsers, $user->data->user_email);
    }
    return $arrayEmailUsers;
}

add_filter('script_uri', 'BD_replace_domain');
add_filter('stylesheet_uri', 'BD_replace_domain');
add_filter('bloginfo_url', 'BD_replace_domain');
add_filter('bloginfo', 'BD_replace_domain');
add_filter('post_link', 'BD_replace_domain');
add_filter('category_link', 'BD_replace_domain');
add_filter('category_feed_link', 'BD_replace_domain');
add_filter('wp_get_attachment_url', 'BD_replace_domain_img');
add_filter('wp_get_attachment_thumb_url', 'BD_replace_domain_img');
add_filter('post_comments_feed_link', 'BD_replace_domain');
add_filter('author_link', 'BD_replace_domain');
add_filter('author_feed_link', 'BD_replace_domain');
//add_filter('plugins_url', 'BD_replace_domain');
add_filter('bloginfo_rss', 'BD_replace_domain');
add_filter('home_url', 'BD_replace_domain');
add_filter('script_loader_src', 'BD_replace_domain');
add_filter('style_loader_src', 'BD_replace_domain');
add_filter('stylesheet_directory_uri', 'BD_replace_domain');
add_filter('site_url', 'BD_replace_domain');
add_filter('template_directory_uri', 'BD_replace_domain');
add_filter('esc_url', 'BD_replace_domain');
// add_filter('vcbc_video_host', 'cambiaURLBrightcove');

function BD_replace_domain($content)
{
    if (is_single() || is_page() || is_home() || is_404() && !is_admin()) {
        $urlpars = parse_url($content);
        if (strpos($content, 'nuestrosbeautydays.mujerhoy.com') != false) {
            if ($urlpars["path"]) {
                return "/2019" . $urlpars["path"];
            }
        } else {
            return $content;
        }
    } else {
        return $content;
    }
}

function BD_replace_domain_img($content)
{
    if (!is_admin()) {
        $urlpars = parse_url($content);
        return "/2019" . $urlpars["path"];
    } else {
        return $content;
    }
}

add_filter('wpcf7_form_action_url', 'wpcf7_custom_form_action_url');
function wpcf7_custom_form_action_url()
{
    return '/2019';
}


/* Add a paragraph only to Pages. */
function my_added_page_content($content)
{
    $content = str_replace('beautyday.mujerhoy.com', 'nuestrosbeautydays.mujerhoy.com', $content);

    return $content;
}
add_filter('the_content', 'my_added_page_content');
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
