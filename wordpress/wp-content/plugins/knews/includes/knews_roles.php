<?php

function knews_get_default_roles() {
	return array(
		'super_admin' => array(true, true, true, true, true),
		'administrator' => array(true, true, true, true, true),
		'editor' => array(true, true, true, true, false),
		'author' => array(true, true, true, false, false),
		'contributor' => array(true, false, false, false, false),
		'subscriber' => array(false, false, false, false, false)
	);
}
function knews_start_caps () {
	
	global $wp_roles, $Knews_plugin;
	$all_roles = $wp_roles->roles;

	$current_user = wp_get_current_user(); //get_currentuserinfo();
	$knews_caps = $Knews_plugin->get_caps();
	$knews_default_roles = knews_get_default_roles();
	
	$admin_or_superadmin=false; $somerole='';
	foreach ($current_user->roles as $role) {
		if ($role=='administrator' || $role=='super_admin') $admin_or_superadmin=true;
		if (isset($knews_default_roles[$role]) && $somerole=='') $somerole=$role;
	}
	
	if (!$admin_or_superadmin) {
		if ($somerole != '') {
			$knews_default_roles[$somerole] = array(true, true, true, true, true);
		} else {
			if (isset($current_user->roles[0])) $knews_default_roles[ $current_user->roles[0] ] = array(true, true, true, true, true);
		}
	}

	foreach ($knews_default_roles as $key=>$kdr) {
		if (isset($all_roles[$key])) {
			$role = get_role($key);
			$n=0;
			foreach ($knews_caps as $kc) {
				if (!isset($all_roles[$key]['capabilities'][$kc]) && $knews_default_roles[$key][$n]) {
					$role->add_cap($kc, true);
				}
				$n++;
			}
		}
	}
}

function knews_admin_caps($knews_nonce_action, $knews_nonce_name) {

	global $wp_roles, $Knews_plugin;
	$all_roles = $wp_roles->roles;

	$knews_caps = $Knews_plugin->get_caps();
	$knews_default_roles = knews_get_default_roles();
	
	$alt=false;
?>
	<form action="admin.php?page=knews_config&tab=pro&subtab=3" method="post">
	<table class="widefat" style="width:auto;">
		<thead><tr>
			<th style="padding-right:20px;"><?php _e('Role name','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can see Knews Stats','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can manage newsletters','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can send newsletters','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can manage users','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can configure Knews','knews'); ?></th>
		</tr></thead>
		<tbody>
<?php
		$alt=false;
		foreach ($all_roles as $key=>$kdr) {
			echo '<tr' . (($alt) ? ' class="alt"' : '') . '>';
			echo '<th>' . $all_roles[$key]['name'] . '</th>';
			foreach ($knews_caps as $kc) {
				echo '<td align="center">';

				if ( ($key=='super_admin' || $key=='administrator') && $kc=='knews_configure') {
					echo '<span style="font-size:30px; display:inline-block; padding-top:5px;">*</span>';
				} else {
					echo '<input type="checkbox" value="1" class="knews_open_close"';
					if (isset($all_roles[$key]['capabilities'][$kc]) && $all_roles[$key]['capabilities'][$kc]==1) {
						echo ' checked="checked"';
					}
					echo ' name="knews_cap_' . $key . '_' . $kc . '" />';
				}
				echo '</td>';
			}
			echo '</tr>';
			$alt=!$alt;
		}
?>
		</tbody>
		<tfoot><tr>
			<th style="padding-right:20px;"><?php _e('Role name','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can see Knews Stats','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can manage newsletters','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can send newsletters','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can manage users','knews'); ?></th>
			<th align="center" style="padding:0 20px;"><?php _e('Can configure Knews','knews'); ?></th>
		</tr></tfoot>
	</table>
	<div class="submit">
		<input type="submit" name="update_KnewsAdminRoles" id="update_KnewsAdminRoles" value="<?php _e('Save','knews');?>" class="button-primary" />
	</div>
	<?php 
	//Security for CSRF attacks
	wp_nonce_field($knews_nonce_action, $knews_nonce_name); 
	?>
	</form>
<?php
}

function knews_admin_save_caps() {

	global $Knews_plugin, $wp_roles;
	$all_roles = $wp_roles->roles;
	$knews_caps = $Knews_plugin->get_caps();

	foreach ($all_roles as $key=>$kdr) {
		$role = get_role($key);
		foreach ($knews_caps as $kc) {
			$val = ($Knews_plugin->post_safe('knews_cap_' . $key . '_' . $kc) == 1);
			if ( ($key=='super_admin' || $key=='administrator') && $kc=='knews_configure') $val=true;
			$role->add_cap($kc, $val);
		}
	}

	echo '<div class="updated"><p><strong>' . __('Roles and capabilities saved.','knews') . '</strong></p></div>';
}
?>