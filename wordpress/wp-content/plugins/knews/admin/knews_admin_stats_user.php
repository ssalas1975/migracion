<?php
	$languages = $Knews_plugin->getLangs(true);
	$extra_fields = $Knews_plugin->get_extra_fields();
	$query = "SELECT id, name FROM " . KNEWS_LISTS . " ORDER BY orderlist";
	$lists_name = $wpdb->get_results( $query );

	$user = $Knews_plugin->get_safe('user',0,'int');

	$query = "SELECT id, email, state, lang FROM " . KNEWS_USERS . ' WHERE id=' . $user;
	$users = $wpdb->get_results( $query );

	if (count($users) != 0) {
		?>
		<p>&nbsp;</p>
		<div style="float:left; width:auto; margin-right:30px;">
		<h3><span style="width:auto">User Data</span></h3>
		<table cellpadding="0" cellspacing="0">
			<tr class="alt"><td><?php _e('Field','knews');?></td><td><?php _e('Value','knews');?></td></tr>
			<tr><td>E-mail:</td><td><?php echo $users[0]->email; ?></td>
			<?php
			$lang_listed = false;
			foreach ($languages as $l) {
				if ($l['language_code'] == $users[0]->lang) $lang_listed = true;
			}
			
			if (!$lang_listed) $languages[$users[0]->lang] = array ('translated_name'=>  __('Inactive language','knews') . ' (' . $users[0]->lang . ')', 'language_code'=>$users[0]->lang);
			
			if (count($languages) > 1) {
				
				echo '<tr><td>' . __('Language','knews') . ':</td><td>';
				foreach($languages as $l){
					if ($users[0]->lang==$l['language_code']) echo $l['translated_name'];
				}
				echo '</td></tr>';

			} else if (count($languages) != 1) {
				echo "<p>" . __('Error','knews') . ": " . __('Language not detected!','knews') . "</p>";
			}
			?>
			<tr><td><?php _e('State','knews');?>:</td><td><?php
				if ($users[0]->state==1) echo '<img src="' . KNEWS_URL . '/images/yellow_led.gif" width="20" height="20" title="' . __('Unconfirmed','knews') . '" style="vertical-align:middle" /> ' . __('Unconfirmed','knews') . '</td>';
				if ($users[0]->state==2) echo '<img src="' . KNEWS_URL . '/images/green_led.gif" width="20" height="20" title="' . __('Confirmed','knews') . '" style="vertical-align:middle" /> ' . __('Confirmed','knews') . '</td>';
				if ($users[0]->state==3) echo '<img src="' . KNEWS_URL . '/images/red_led.gif" width="20" height="20" title="' . __('Blocked','knews') . '" style="vertical-align:middle" /> ' . __('Blocked','knews') . '</td>';
				if ($users[0]->state==4) echo '<img src="' . KNEWS_URL . '/images/gray_led.gif" width="20" height="20" title="' . __('Bounced','knews') . '" style="vertical-align:middle" /> ' . __('Bounced','knews') . '</td>';
			echo '</td></tr>';

			foreach ($extra_fields as $ef) {
				echo '<tr><td>' . $ef->name . ':</td><td>' . $Knews_plugin->get_user_field($user, $ef->id);
			}
			
			$query = "SELECT id_list FROM " . KNEWS_USERS_PER_LISTS . " WHERE id_user=" . $user;
			$lists = $wpdb->get_results( $query );
			if (count($lists_name) > 0) {
				?><tr class="alt"><td colspan="2"><?php _e('Current subscriptions:','knews');?></td></tr><?php
			}

			foreach ($lists_name as $ln) {
				$active=false;
				foreach ($lists as $list_user) {
					if ($list_user->id_list==$ln->id) {
						echo '<tr><td>&nbsp;</td><td>' . $ln->name . '</td></tr>';
					}
				}
			}
			?>
			<tr class="alt"><td><?php _e('Field','knews');?></td><td><?php _e('Value','knews');?></td></tr>
		</table>
		</div>
		<div style="float:left; width:auto;">
		<h3><span style="width:auto">Submissions</span></h3>
		<table cellpadding="0" cellspacing="0" >
			<tr class="alt"><td>Date</td><td>Newsletter name</td><td>Delivered</td></tr>
			<?php
			$query = 'SELECT * FROM ' . KNEWS_NEWSLETTERS_SUBMITS_DETAILS . ' WHERE user=' . $user;
			$asd = $wpdb->get_results( $query );
			
			foreach ($asd as $sd) {
				$query = 'SELECT kns.id, kns.start_time, kns.end_time, kn.name FROM ' . KNEWS_NEWSLETTERS_SUBMITS . ' kns, ' . KNEWS_NEWSLETTERS . ' kn WHERE kns.newsletter=kn.id AND kns.blog_id=' . get_current_blog_id() . ' AND kns.id=' . $sd->submit;
				$submit = $wpdb->get_row( $query );
				if (isset($submit->id)) {
					$start=substr($submit->start_time,0,10);
					$end=substr($submit->end_time,0,10);
					if ($start != $end) $start .= ' / ' . $end;
					$status=(($sd->status==1) ? 'Yes' : 'No');
					echo '<tr><td>' . $start . ' (#' . $sd->submit . ')</td><td>' . $submit->name . '</td><td>' . $status . '</td></tr>';

					$query = 'SELECT * FROM ' . KNEWS_STATS . ' WHERE user_id=' . $user . ' AND submit_id=' . $sd->submit;
					$stats = $wpdb->get_results( $query );
					
					foreach ($stats as $s) {
						if ($s->what==1) $w='Link click';
						if ($s->what==2) $w='Click on cant read';
						if ($s->what==3) $w='Click on unsubscribe';
						if ($s->what==4) $w='Click on mobile link';
						if ($s->what==5) $w='Soft bounced';
						if ($s->what==6) $w='Hard bounced';
						if ($s->what==7) $w='Newsletter opened';
						echo '<tr class="sub"><td>' . $s->date . '</td><td>' . $w . '</td><td></td></tr>';
					}
				}
			}
			?>
			<tr class="alt"><td>Date</td><td>Newsletter name</td><td>Delivered</td></tr>
		</table>
		</div>
		<div class="clear"></div>
		<?php 
	} else {
		echo '<p>' . __('User not found','knews') . '</p>';
	}
?>		
</div>
