<?php
global $Knews_plugin;

if ($Knews_plugin) {
	if (! $Knews_plugin->initialized) $Knews_plugin->init();
	require_once(KNEWS_DIR . "/includes/knews_util.php");

	if ($inst=knews_pop3_login(	$Knews_plugin->post_safe('bounce_host_knews'),
						$Knews_plugin->post_safe('bounce_port_knews'),
						$Knews_plugin->post_safe('bounce_user_knews'),
						html_entity_decode(stripslashes($Knews_plugin->post_safe('bounce_pass_knews','','unsafe'))),
						//$Knews_plugin->post_safe('bounce_pass_knews'),
						"INBOX",
						$Knews_plugin->post_safe('bounce_ssl_knews'),
						$Knews_plugin->post_safe('bounce_mode_knews')
					)) {
						//print_r($inst);
		_e('Connection seems OK, looking for messages:','knews');
		echo  '<br>';
		$stat=knews_pop3_stat($inst);

		if (isset($stat['Unread'])) echo __('Unread messages:') . ' ' . $stat['Unread'] . '<br>';
		if (isset($stat['Nmsgs'])) {
			echo __('Total messages:') . ' ' . $stat['Nmsgs'] . '<br>';
			if ($stat['Nmsgs']==0) {
				_e('Send one email to the bounce account to test the email reading', 'knews');
			} else {
				echo __("Let's try to read the first email:", 'knews') . '<br>';
				$testmessage=knews_pop3_list($inst, 1, false);
				if (isset($testmessage[0]) && $testmessage[0] != '') {
					echo sprintf(__('The message was readed <strong>successfully</strong>, if has %s bytes length.','knews'), strlen($testmessage[0]) );
					echo ' <a href="#" onclick="show_code(); return false;">' . __('See email content','knews') . '</a>';
					?>
					<div class="showcode" style="display:none; padding:10px 0;"><?php echo htmlentities($testmessage[0]); ?></div>
					<?php
				} else {
					echo __("The message can't be readed.");
				}
			}
		} else {
			_e("Can't read the total messages number, something isn't working");
		}

		knews_pop3_close ( $inst );
	} else {
		_e('Cant connect or login to POP3 mail server, please, check the configuration values','knews');
	}
	echo '<br><br>';
}
die();
?>