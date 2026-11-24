<?php
//Security for CSRF attacks
$knews_nonce_action='kn-support-page';
$knews_nonce_name='_support';
if (!empty($_POST)) $w=check_admin_referer($knews_nonce_action, $knews_nonce_name);
//End Security for CSRF attacks

global $knewsOptions, $Knews_plugin;

if (isset($_POST['SubmitKnewsQuestion'])) {
	$message = 'Question from Knews Pro Priority Support' . "\r\n" . "\r\n";
	$message .= 'Name: ' . $Knews_plugin->post_safe('knews_support_name') . "\r\n";
	$message .= 'Email: ' . $Knews_plugin->post_safe('knews_support_email') . "\r\n" . "\r\n";
	$message .= str_replace('\r\n', "\r\n", $Knews_plugin->post_safe('knews_support_comment')) . "\r\n";
	$message .= $knewsOptions['registration_email'] . ' ' . $knewsOptions['registration_serial'] . "\r\n";

	$headers = 'From: ' . $Knews_plugin->post_safe('knews_support_name') . ' <' . $Knews_plugin->post_safe('knews_support_email') . '>' . "\r\n";
	
	if (wp_mail('support@knewsplugin.com', 'Knews Priority Support', $message, $headers)) {
		
		echo '<div style="background-color:#FFFBCC; border:#E6DB55 1px solid; color:#555555; border-radius:3px; padding:5px 10px; margin:20px 15px 10px 0; text-align:left"><p>';
		_e('Your question has been sent successfully. In a few minutes you should recieve an automated reply to confirm that your email has been delivered. We will reply you soon. Thank you very much.','knews');
		echo '</p></div>';

	} else {
		echo '<div style="background-color:#FFEBE8; border:#CC0000 1px solid; color:#555555; border-radius:3px; padding:5px 10px; margin:20px 15px 10px 0; text-align:left">';
		_e('We are sorry, your question cant be sent. Your WordPress cant send emails for any reason. Please, contact us through: support@knewsplugin.com.','knews');
		echo '</p></div>';
	}
}
?>
<div class=wrap>
	<form method="post" action="admin.php?page=knews_support">
		<div style="position:absolute; margin-left:420px; padding-top:10px;" id="look_registration">
		<?php 
		$look = wp_remote_get( 'http://www.knewsplugin.com/look_registration.php?email=' . urlencode($knewsOptions['registration_email']) . '&serial=' . $knewsOptions['registration_serial'] );

		if (is_wp_error($look))
			$look = wp_remote_get( 'https://knewsplugin.com/look_registration.php?email=' . urlencode($knewsOptions['registration_email']) . '&serial=' . $knewsOptions['registration_serial'] );

		if (!is_wp_error($look)) {
			if (isset($look['body'])) echo $look['body'];
		} else {
		?>
			<script type="text/javascript">
				if ('https:' == document.location.protocol) {
					starturl = 'https://';
				} else {
					starturl = 'http://www' + '.';
				}
				var knewsscript = document.createElement('script'); knewsscript.type = 'text/javascript'; knewsscript.async = true;
				knewsscript.src = starturl + 'knewsplugin.com/look_registration.php?email=<?php echo urlencode($knewsOptions['registration_email']);?>&serial=<?php echo $knewsOptions['registration_serial'];?>&js=1&x=3';
				var knewsscript_s = document.getElementsByTagName('script')[0]; knewsscript_s.parentNode.insertBefore(knewsscript, knewsscript_s);
			</script>
		<?php
		}
		?>
		</div>
		<?php
		$current_user = wp_get_current_user(); //get_currentuserinfo();
		?>
		<div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div>
		<h2><?php _e('Priority Support for Knews Pro','knews'); ?></h2>
		<p><?php _e('Name:','knews'); ?><input type="text" name="knews_support_name" class="regular-text" value="<?php echo $current_user->display_name; ?>" /></p>
		<p><?php _e('E-mail:','knews'); ?><input type="text" name="knews_support_email" class="regular-text" value="<?php echo $current_user->user_email; ?>" /></p>
		<p><?php _e('Please, explain your questions in English, Spanish or Catalan:','knews'); ?></p>
		<textarea name="knews_support_comment" rows="20" cols="60" style="width:95%; height:400px;"><?php
		if (isset($_POST['knews_support_comment'])) {
			echo $Knews_plugin->post_safe('knews_support_comment');
		} else {
			if ( !function_exists( 'get_plugins' ) ) { require_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); }
			echo "\n\n\n\n" . __('My General Info:','knews') . "\n" . '===================================' . "\n";
			echo 'Wordpress Version: ' . get_bloginfo( 'version' ) . "\n";
			echo 'Knews Version: ' . KNEWS_VERSION . "\n";
			echo 'Site URL: ' . get_bloginfo('url') . "\n";
			$theme = wp_get_theme();
			echo 'Theme: ' . $theme->Name . ' ' . $theme->Version . ' ' . $theme->ThemeURI . "\n";
			global $wpdb;
			echo 'DB Prefix: ' . $wpdb->prefix . "\n";
			echo 'MULTISITE: ' . ((is_multisite()) ? '1' : '0');
			echo 'NETWORKED: ' . (($Knews_plugin->im_networked()) ? ' 1' : ' 0') . "\n";
			echo 'WP_DEBUG: ' . ((defined('WP_DEBUG') && WP_DEBUG) ? '1' : '0') . "\n";
			echo "\n\n" . __('My installed plugins:','knews') . "\n" . '===================================' . "\n";
	
			$plugins = apply_filters( 'all_plugins', get_plugins() );
			if( is_multisite() ) {
				$getnetwork_plugins = $wpdb->get_row("SELECT meta_value FROM {$wpdb->base_prefix}sitemeta WHERE meta_key = 'active_sitewide_plugins'");
				$network_plugins = array_flip((array)unserialize($getnetwork_plugins->meta_value));
				foreach ($network_plugins as $np) {
					echo '(Network)' . ' - ' . $plugins[$np]['Name'] . ' - ' . $plugins[$np]['Version'] . ' - ' . $plugins[$np]['PluginURI'] . "\n";
				}
			}
			$local_plugins = get_option('active_plugins');
			foreach ($local_plugins as $lp) {
				echo $plugins[$lp]['Name'] . ' - ' . $plugins[$lp]['Version'] . ' - ' . $plugins[$lp]['PluginURI'] . "\n";
			}
		}
		?>
		</textarea>
		<p>This form uses the wp_mail() function. If it doesn't works, please, <a href="http://www.knewsplugin.com/professional-contact/" target="_blank">click here</a></p>
		<p><input type="submit" value="<?php _e('Submit','knews');?>" name="SubmitKnewsQuestion" class="button-primary" /></p>
		<?php 
		//Security for CSRF attacks
		wp_nonce_field($knews_nonce_action, $knews_nonce_name); 
		?>
	</form>
</div>
