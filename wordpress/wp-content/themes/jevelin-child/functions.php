<?php

/**
 * Theme functions file
 */

/**
 * Enqueue parent theme styles first
 * Replaces previous method using @import
 * <http://codex.wordpress.org/Child_Themes>
 */

add_action('wp_enqueue_scripts', 'jevelin_child_enqueue', 99);

function jevelin_child_enqueue()
{
	wp_enqueue_style('jevelin-child-style', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('jevelin-child-scripts', get_stylesheet_directory_uri() . '/js/scripts.js');
}

/**
 * Add your custom functions below
 */



/**
 * Registar usuario una vez se envia el mail
 */
if (isset($_POST['email_b1629b1'])) {
	switch_to_blog(get_current_blog_id());

	$emailContacto = $_POST['email_b1629b1'];
	$nombreFamilia = $_POST['text_0e367cd'];
	$numAdultos = $_POST['select_448759d'];
	$numChild = $_POST['select_2cb33b5'];
	$numMascota = $_POST['select_9619d83'];
	$conocido = $_POST['checkboxes_1c9d754']; //esto es un array
	$acepto = $_POST['10003'];

	$password = wp_generate_password(12, false);
	$email = $_POST['email_b1629b1'];
	$name = $_POST['text_0e367cd'];
	// Construct a username from the user's name
	$username = strtolower(str_replace(' ', '', $name));
	$name_parts = explode(' ', $name);
	$comonosconocio = implode(" ", $_POST['checkboxes_1c9d754']);


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
			'nickname' => $name,
			'display_name' => $name,
			'first_name' => $name,
			'role' => $cf7frr
		);
		$user_id = wp_insert_user($userdata);

		update_user_meta($user_id, 'comonosconocio', $comonosconocio);
		update_user_meta($user_id, 'numadultos', $numAdultos);
		update_user_meta($user_id, 'numchild', $numChild);
		update_user_meta($user_id, 'nummascota', $numMascota);
		update_user_meta($user_id, 'acepto', $acepto);
	} else {
		switch_to_blog(get_current_blog_id());
		$user_id = get_user_by('email', $email);
		update_user_meta($user_id->ID, 'comonosconocio', $comonosconocio);
		update_user_meta($user_id->ID, 'numadultos', $numAdultos);
		update_user_meta($user_id->ID, 'numchild', $numChild);
		update_user_meta($user_id->ID, 'nummascota', $numMascota);
		update_user_meta($user_id, 'acepto', $acepto);
	}
}


/**
 * ruta clase exportacion
 */
add_action('admin_footer', 'add_club_filter_to_user_listing');
function add_club_filter_to_user_listing()
{

	global $pagenow;

	if ($pagenow == "users.php") {

?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery(".tablenav.top").append("<div class='alignleft actions' style='margin: 1%;'><a href='<?php echo get_stylesheet_directory_uri(); ?>/exportexcel/exportusersuscriber.php' class='exportar'><input type='button' name='exportUsers' id='exportUsers' class='button' value='Listado Familias'></a></div><br class='clear'>");

			});
		</script>
<?php
	}
}


/**
 * Modificar el mail que se envia
 */

add_filter('wp_mail_content_type', 'set_content_type');
function set_content_type($content_type)
{
	return 'text/html';
}

add_filter('wp_mail', 'my_wp_mail_filter');
function my_wp_mail_filter($args)
{

	if (isset($_POST['email_b1629b1'])) {
		$emailContacto = $_POST['email_b1629b1'];
		$args['to'] = $emailContacto;
	}
	$args['message'] = '
	<head>
		<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
		<meta name="referrer" content="no-referrer">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>go family</title>

		</head>
		<!--[if (gte mso 9)|(IE)]><table border="0"  cellpadding="0" cellspacing="0" width="600" align="center"><tr><td><![endif]-->
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style=" margin: 0; padding: 0;background-color:#f0f0f0";>
		<table width="100%" style="max-width: 600px; padding:5px 15px; background:#DD3F13;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="right" style="margin-bottom:0;font-size:11px;color:#000;font-family: Arial, Helvetica, sans-serif;">
				¿No puedes verlo bien?
				<a style="text-decoration:none;color:#b90021" href="~PROBE(0)~">pulsa aqui</a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr align="center" border="0" cellpadding="0" cellspacing="0">
				<td style="padding-top:20px">
				<a href="https://www.gofamily.mujerhoy.com"> <img width="250" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/LogoGoFamily.jpg" alt="go-family" /></a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr align="center" border="0" cellpadding="0" cellspacing="0">
				<td style="padding-top:20px">
				<img width="175" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/HolaFamilia.jpg" alt="hola-familia" />
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>

			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="center" style="font-size: 14px; line-height: 14px; color: #000;font-family: Arial, Helvetica, sans-serif; padding-left:13%; padding-right:13%;padding-top:15px;">
				Os damos la bienvenida a
				<span style="color:#DD3F13">#mujerhoyGoFamily</span> <br><br>Un evento educativo, solidario, imaginativo y muy divertido
				<br><strong>¡para disfrutar JUNTOS!</strong>
				</td>
			</tr>

			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr align="center" border="0" cellpadding="0" cellspacing="0">
				<td style="padding-top:20px">
				<a href="img/Invitacion_GoFamily_MUJERHOY.pdf" target="_blank"> <img width="90" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/sobre.jpg" alt="hola-familia" /></a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="center" style="font-size: 18px; line-height: 21px; color: #000;font-family: Arial, Helvetica, sans-serif; padding-left:13%; padding-right:13%;padding-top:15px;">
				<a style="font-size: 16px; line-height: 18px; color: #000;font-family: Arial, Helvetica, sans-serif;" href="' . get_stylesheet_directory_uri() . '/img/Invitacion_GoFamily_MUJERHOY.pdf" target="_blank">
					<strong>Descarga aquí tu
					<br> INVITACIÓN familiar</strong>
				</a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr align="center" border="0" cellpadding="0" cellspacing="0">
				<td style="padding-top:5px">
				<a href="https://www.gofamily.mujerhoy.com"><img width="30" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/flechitas.jpg" alt="hola-familia" /></a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="center" style="font-size: 22px; line-height: 21px; color: #000;font-family: Arial, Helvetica, sans-serif; padding-left:13%; padding-right:13%;padding-top:15px;text-decoration:underline #000">
				<strong>¡Os esperamos!</strong>
				</td>
			</tr>
			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="center" style="font-size: 20px; line-height: 21px; color: #000;font-family: Arial, Helvetica, sans-serif; padding-left:13%; padding-right:13%;padding-top:15px;">
				Sábado, 29 septiembre
				<br> 11:00h-19:00h
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr border="0" cellpadding="0" cellspacing="0">
				<td align="center" style="font-size: 15px; line-height: 21px; color: #000;font-family: Arial, Helvetica, sans-serif; padding-left:13%; padding-right:13%;padding-top:15px;">
				<a style="color: #000;font-family: Arial, Helvetica, sans-serif;text-decoration:none;font-weight: bold;" href="https://www.gofamily.mujerhoy.com">gofamily.mujerhoy.com</a>
				</td>
			</tr>
			</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #fff;" align="center"
		border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr align="center" border="0" cellpadding="0" cellspacing="0">
			<td style="padding-top:20px">
				<a href="https://www.gofamily.mujerhoy.com"><img width="525" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/Hashtags.jpg" alt="hola-familia" /></a>
			</td>
			</tr>
		</tbody>
		</table>
		<table width="100%" style="max-width: 600px;font-family: Arial, Helvetica, sans-serif;background-color: #DD3F13;" align="center" border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr align="right" border="0" cellpadding="0" cellspacing="0">
				<td style="padding-top:20px;padding-bottom:20px;padding-right:7%;">
				<img width="152" style="max-width:600px;" src="' . get_stylesheet_directory_uri() . '/img/LogoMH.png" alt="go-family" />
				</td>
			</tr>
			</tbody>
		</table>
		</body>

		<!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
	';

	$new_wp_mail = array(
		'to'          => $args['to'],
		'subject'     => $args['subject'],
		'message'     => $args['message'],
	);

	return $new_wp_mail;
}

function form_gofamily_enqueue_scripts()
{

	if (is_front_page()) {
		wp_enqueue_script('scriptNewForm', get_stylesheet_directory_uri() . '/js/scriptNewForm.js', '1.0.0', true);
	}
}
add_action('wp_enqueue_scripts', 'form_gofamily_enqueue_scripts');

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
