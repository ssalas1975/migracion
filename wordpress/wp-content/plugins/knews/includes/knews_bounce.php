<?php
global $Knews_plugin, $knewsOptions, $wpdb;
if (! $Knews_plugin->initialized) $Knews_plugin->init();

$js=$Knews_plugin->get_safe('js', 0, 'int');
$pend=false;
$left_messages=0;

if ($knewsOptions['bounce_on']=='1') {
	require_once(KNEWS_DIR . "/includes/knews_util.php");
	require_once(KNEWS_DIR . "/includes/bouncehandler/bounce_driver.class.php");
	$bouncehandler = new Bouncehandler();

	if ($inst=knews_pop3_login($knewsOptions['bounce_host'], $knewsOptions['bounce_port'], $knewsOptions['bounce_user'], $knewsOptions['bounce_pass'], "INBOX", $knewsOptions['bounce_ssl'], $knewsOptions['bounce_mode'])) {
		$stat=knews_pop3_stat($inst);
		//print_r($stat);
		//die();
		if($stat['Nmsgs'] > 0){
			$messages = $stat['Nmsgs'];
			if ($messages > 50) {
				$pend=true;
				$left_messages = $messages - 50;
				$messages = 50;
			}
			$list=knews_pop3_list($inst, $messages);
			$count=0;
			foreach($list as $row){
				$count++;
				$b = $bouncehandler->get_the_facts($row);
				$fail_code=0;

				$read_blog_id=0;
				$posid = strpos($row, 'Knews-Blog-Id:');
				if ($posid !== false) {
					$posid = $posid +14;
					$posid2=strpos($row,'_', $posid);
					if ($posid2 !== false) $read_blog_id=substr($row,$posid, $posid2-$posid);
					$read_blog_id = intval(trim($read_blog_id));
				}
				$id_newsletter=0;
				$posid = strpos($row, 'Knews-News-Id:');
				if ($posid !== false) {
					$posid = $posid +14;
					$posid2=strpos($row,'_', $posid);
					if ($posid2 !== false) $id_newsletter=substr($row,$posid, $posid2-$posid);
					$id_newsletter = intval(trim($id_newsletter));
				}
/*
Notice: Undefined index: Original-recipient in /usr/home/carlesrever/www/knews/wp-content/plugins/knews/includes/bouncehandler/bounce_driver.class.php on line 494

Notice: Undefined offset: 1 in /usr/home/carlesrever/www/knews/wp-content/plugins/knews/includes/bouncehandler/bounce_driver.class.php on line 497*/
		
				if (count($b) != 1) {
					$fail_code=1;
				} else {
					$b=$b[0];
					if (!isset($b['recipient']) || $b['recipient']=='' || !isset($b['status']) || strlen($b['status']) != 5) $fail_code=2;
				}
					
				if ($fail_code==0) {
					$case=substr($b['status'],0,1);
					if ($case != '4' && $case !='5') {
						$fail_code=5;
					} else {

						if ($read_blog_id == 0) {
							$table_name_users = KNEWS_USERS;
							$table_name_stats = KNEWS_STATS;
						} elseif ($read_blog_id == $Knews_plugin->KNEWS_MAIN_BLOG_ID) {
							$table_name_users = $wpdb->base_prefix . 'knewsusers';
							$table_name_stats = $wpdb->base_prefix . 'knewstats';
						} else {
							$table_name_users = $wpdb->base_prefix . $read_blog_id . '_' . 'knewsusers';							
							$table_name_stats = $wpdb->base_prefix . $read_blog_id . '_' . 'knewsusers';
						}

						$query = "SELECT id FROM " . $table_name_users . " WHERE email='" . esc_sql(trim($b['recipient'])) . "'";
						$find_user = $wpdb->get_results( $query );

						if (count($find_user) != 1) {
							$fail_code=6;
						} else {
							//if ($id_newsletter!=0) {
								$mysqldate = $Knews_plugin->get_mysql_date();
								$what=5; if ($case=='5') $what=6;
								
								$query = "INSERT INTO " . $table_name_stats . " (what, user_id, submit_id, date, statkey) VALUES (" . $what . ", " . $find_user[0]->id . ", " . $id_newsletter . ", '" . $mysqldate . "', 0)";
								$result=$wpdb->query( $query );
							//}

							if ($case=='5') {
							//Let's bouncing the user
		
								$query = "UPDATE " . $table_name_users . " SET state='4' WHERE id=" . $find_user[0]->id;
								$results = $wpdb->query( $query );
							}
							//if (count($find_user) != 0) die();
						}
					}
				}
				if ($fail_code != 0) {
					//Save the bounced email as txt for revision
					$hour = date('d-m-Y', current_time('timestamp'));
					@$fp = fopen(KNEWS_DIR . '/tmp/bf_' . $hour . '_' . $count . '.log', 'a');
					if ($fp) fwrite($fp, 'Bounced email parsing failed. Code: ' . $fail_code . "\r\n\r\n" . $row);
					if ($fp) fclose($fp);
				}
			}

		} else {
			//echo "no unread messages";  
		}
		
		knews_pop3_close ( $inst );
		//print_r($alerts);
		//print_r($errors);
		//commented out because I havent implemented yet. see IMAP documentation.
		//flag: If set to CL_EXPUNGE, the function will silently expunge the mailbox before closing, removing all messages marked for deletion. You can achieve the same thing by using imap_expunge()
	}
}

if ($js != 0) {
?>
	<html>
	<head><title>JS Bounce</title>
	<script type="text/javascript">
	var start = new Date().getTime();
	var total_time = 1000*30 // 1/2 minutes;
	function jump() {
	<?php 
		if ($pend) {
	?>
			location.href="<?php echo $Knews_plugin->get_main_admin_url() . 'admin-ajax.php?action=knewsBounce&js=' . (intval($js)+1); ?>"
	<?php
		}
	?>
	}
	setTimeout ('jump()',total_time); 

	function counter() {
	<?php 
		if ($pend) {
	?>
		elapsed = new Date().getTime() - start;
		timeleft = total_time - elapsed;
		
		minutes=Math.floor(timeleft/60000);
		seconds=Math.floor((timeleft-minutes*60000)/1000);
		if (seconds<10) seconds= "0" + seconds;
		
		if (minutes>=0) {
			document.getElementById("clock").innerHTML=minutes+":"+seconds;
		} else {
			document.getElementById("clock").innerHTML='reloading...';
		}
		setTimeout ('counter()',1000); // 1 second
	<?php
		}
	?>
	}
	</script>
	</head>
	<body onLoad="counter()">
	<h1><?php echo __('Step','knews') . ' #' . $js; ?></h1>
	<?php
	if ($pend) {
	?>
	<h2 id="clock">2:00</h2>
	<p><?php echo sprintf(__("Bounce-JS in the process, %s remaining emails to process... don't close this window...",'knews'), $left_messages);?></p>
	<?php
	} else {
	?>
	<p><?php _e('Bounce-JS finished, you can close the window','knews');?></p>
	<?php
	}
	?>
	</body>
	</html>
<?php

}

?>