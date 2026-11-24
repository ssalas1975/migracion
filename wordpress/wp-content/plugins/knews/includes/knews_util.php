<?php
function knews_list_items($element, $all_elements) {
	$reply = '';
	if (!is_array($element)) $element = explode(',', $element);
	foreach ($element as $e) {
		if (isset($all_elements[$e])) {
			if ($reply != '') $reply .= ', ';
			$reply .= $all_elements[$e];
		}
	}
	return $reply;
}

function knews_print_mailinglists($new_way=false) {
	global $Knews_plugin, $wpdb;
	
	$order_by = $Knews_plugin->get_safe('orderby', 'orderlist'); if ($order_by != 'name') $order_by = "orderlist";
	$order = $Knews_plugin->get_safe('order', 'asc'); if ($order != 'asc') $order = "desc";

	$query = "SELECT id, name, auxiliary FROM " . KNEWS_LISTS . " ORDER BY " . $order_by . " " . $order;
	$lists_name = $wpdb->get_results( $query );

	//$col=count($lists_name)+1; 
	$n=0;
	if (count($lists_name) > 8) {
		echo '<table class="widefat"><thead><tr><th class="manage-column column-cb check-column"><input type="checkbox"></th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th><th>&nbsp;</th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th><th>&nbsp;</th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th></tr></thead><tbody><tr class="alt">';
		//$col = ceil($col / 3);
	} else {
		echo '<table class="widefat" style="width:480px"><thead><tr><th class="manage-column column-cb check-column"><input type="checkbox"></th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th></tr></thead><tbody>';
	}
	$alt=false;
	foreach ($lists_name as $ln) {

		$query = "SELECT COUNT(" . KNEWS_USERS . ".id) AS HOW_MANY FROM " . KNEWS_USERS . ", " . KNEWS_USERS_PER_LISTS . " WHERE " . KNEWS_USERS_PER_LISTS . ".id_user=" . KNEWS_USERS . ".id AND " . KNEWS_USERS . ".state='2' AND  " . KNEWS_USERS_PER_LISTS . ".id_list=" . $ln->id;
		$count = $wpdb->get_results( $query );

		$query = "SELECT COUNT(" . KNEWS_USERS . ".id) AS HOW_MANY FROM " . KNEWS_USERS . ", " . KNEWS_USERS_PER_LISTS . " WHERE " . KNEWS_USERS_PER_LISTS . ".id_user=" . KNEWS_USERS . ".id AND " . KNEWS_USERS . ".state<>'2' AND  " . KNEWS_USERS_PER_LISTS . ".id_list=" . $ln->id;
		$count2 = $wpdb->get_results( $query );

		if (count($lists_name) < 9) {
			if ($n != 0) echo '</tr>';
			echo '<tr' . (($alt) ? ' class="alt"' : '') . '>';
			if ($alt) {$alt=false;} else {$alt=true;}
		} elseif ($n%3 == 0 ) {
			if ($n != 0) echo '</tr>';
			echo '<tr' . (($alt) ? ' class="alt"' : '') . '>';
			if ($alt) {$alt=false;} else {$alt=true;}
		}
		$n++;
		
		echo '<th class="check-column" style="padding:9px 0 4px 0;">';
		if ($new_way) {
			echo '<input type="checkbox" value="' . $ln->id . '" name="list[]" id="list_' . $ln->id . '" class="checklist">';
		} else {
			echo '<input type="checkbox" value="1" name="list_' . $ln->id . '" id="list_' . $ln->id . '" class="checklist">';
		}
		echo '</th><td style="padding:7px 60px 0px 7px;">' . (($ln->auxiliary==0) ? '<strong>' : '') . $ln->name . (($ln->auxiliary==0) ? '</strong>' : '') . '</td><td><strong style="color:#25c500">' . $count[0]->HOW_MANY . '</strong> / ' . ($count[0]->HOW_MANY + $count2[0]->HOW_MANY) . '</td>';
	}

	if (count($lists_name) > 8) {
		
		while ($n%3 != 0) {
			echo '<td>&nbsp;</td><td>&nbsp;</td>';
			$n++;
		}
		echo '</tr>';
		echo '<tfoot><tr><th class="manage-column column-cb check-column"><input type="checkbox"></th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th><th>&nbsp;</th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th><th>&nbsp;</th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th></tr></tfoot></table>';
		//$col = ceil($col / 3);
	} else {
		echo '</tr>';
		echo '<tfoot><tr><th class="manage-column column-cb check-column"><input type="checkbox"></th>';
		knews_th_orderable(__('Name list','knews'),'name','asc','padding-right:60px');
		echo '<th>&nbsp;</th></tr></tfoot></table>';
	}
}

function knews_th_orderable ($label, $orderby, $order, $extra_style='') {
	global $Knews_plugin;
	
	if ($extra_style != '') $extra_style = ' style="' . $extra_style . '"';
	
	if ($Knews_plugin->get_safe('orderby') == $orderby) {
		$order = (($Knews_plugin->get_safe('order')=='asc') ? 'desc' : 'asc');
		$sortable = 'sorted';
	} else {
		$sortable = 'sortable';
	}
	$sorted = (($order=='asc') ? 'desc' : 'asc');
	
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	
	$current_url = remove_query_arg( array( 'orderby', 'order' ), $current_url );
	$current_url = add_query_arg( 'orderby', $orderby, $current_url );
	$current_url = add_query_arg( 'order', $order, $current_url );
	
	echo '<th class="manage-column ' . $sortable . ' ' . $sorted . '"' . $extra_style . '><a href="' . esc_url( $current_url ) . '"><span>' . $label . '</span><span class="sorting-indicator"></span></a></th>';
}

function knews_is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

function knews_cut_code($start, $end, $code, $delete) {
	
	$start_pos = strpos($code, $start); if ($start_pos === false) return '';
	
	$end_pos = strpos($code, $end, $start_pos+strlen($start)); if ($end_pos === false) return '';
	
	if ($delete) {
		$start_pos = $start_pos + strlen($start);
	} else {
		$end_pos = $end_pos + strlen($end);
	}
	
	if ($start_pos === false || $end_pos === false) return '';
	return substr($code, $start_pos, $end_pos-$start_pos);	
}

function knews_extract_code($start, $end, $code, $delete) {

	$start_pos = strpos($code, $start); if ($start_pos === false) return $code;
	
	$end_pos = strpos($code, $end, $start_pos+strlen($start)); if ($end_pos === false) return $code;

	if (!$delete) {
		$start_pos = $start_pos + strlen($start);
	} else {
		$end_pos = $end_pos + strlen($end);
	}
	
	if ($start_pos === false || $end_pos === false) return $code;
	return substr($code, 0, $start_pos) . substr($code, $end_pos);	
}

function knews_iterative_extract_code($start, $end, $code, $delete) {
	$pre = $code;
	$post = knews_extract_code($start, $end, $code, $delete);
	while ($pre != $post) {
		$pre=$post;
		$post = knews_extract_code($start, $end, $post, $delete);
	}
	return $post;
}

function knews_deleteTag($tag, $search, $theHtml) {
	$findPos=strpos($theHtml, $search);
	if ($findPos === false) return $theHtml;
	
	$firsthalf = substr($theHtml, 0, $findPos);
	
	$pos = strrpos($firsthalf, '<' . $tag);
	$pos2 = strpos($theHtml, '</' . $tag . '>', $findPos);
	$pos2 = $pos2+strlen($tag)+3;
	
	if ($pos === false || $pos2 === false) return $theHtml;

	return substr($theHtml, 0, $pos) . substr($theHtml, $pos2);	
}
function knews_iterative_deleteTag($tag, $search, $theHtml) {
	$pre = $theHtml;
	$post = knews_deleteTag($tag, $search, $theHtml);
	while ($pre != $post) {
		$pre=$post;
		$post = knews_deleteTag($tag, $search, $post);
	}
	return $post;
}

function knews_rgb2hex($code) {
	for ($pos_char = 0; $pos_char < strlen($code); $pos_char++) {

		if (substr($code, $pos_char, 3)=='rgb') {
			
			$start_pos = strpos($code, '(', $pos_char);
			$end_pos = strpos($code, ')', $pos_char);
						
			if ($start_pos < $end_pos && $pos_char + 6 > $start_pos && $start_pos + 16 > $end_pos) {
				
				$rgb_detected = substr($code, $start_pos +1 , $end_pos-$start_pos-1);

				$rgb_detected = str_replace(' ', '', $rgb_detected);
				$rgb_detected = explode(',', $rgb_detected);
				
				if (is_array($rgb_detected) && sizeof($rgb_detected) == 3) {
					list($r, $g, $b) = $rgb_detected;

					$r = dechex($r<0?0:($r>255?255:$r));
					$g = dechex($g<0?0:($g>255?255:$g));
					$b = dechex($b<0?0:($b>255?255:$b));
					
					$colorhex = (strlen($r) < 2?'0':'').$r;
					$colorhex.= (strlen($g) < 2?'0':'').$g;
					$colorhex.= (strlen($b) < 2?'0':'').$b;

					$colorhex = '#' . strtoupper ($colorhex);
					
					$code = substr($code, 0, $pos_char) . $colorhex . substr($code, $end_pos + 1);
				}
			}
		}
	}
	return $code;
}
function clean_permalinks($code) {
	for ($n=0; $n<21; $n++) {
		$code = str_replace('%the_permalink_' . $n . '%', '#', $code);
	}
	return $code;
}

function knews_examine_template($templateID, $template_path, $template_url, $popup=false, $mode='selection') {
	global $Knews_plugin;
	$xml_info = array (
		'shortname' => $templateID,
		'fullname' => 'Not defined',
		'version' => '1.0',
		'url' => '',
		'date' => 'Unknown',
		'author' => 'Unknown',
		'urlauthor' => '',
		'minver' => '1.0.0',
		'minverpro' => '1.0.0',
		'onlypro' => 'no',
		'description' => 'Not defined',
		'desktop' => 'no',
		'mobile' => 'no',
		'responsive' => 'no'
	);

	if (!$xml = @simplexml_load_file($template_path . '/info.xml')) return false;

	foreach($xml->children() as $child) {
		$xml_info[$child->getName()] = $child;
	}
	
	if (!$popup || ($xml_info['responsive'] == 'yes' || $xml_info['mobile'] == 'yes')) { 
?>
		<div style="padding:10px 10px 0 10px; float:left; width:250px; height:370px;" class="template">
<?php
		$selectable=false;
		if ($Knews_plugin->im_pro()) $xml_info['minver'] = $xml_info['minverpro'];
		
		echo '<div style="text-align:center">';
		
		if (version_compare( KNEWS_VERSION, $xml_info['minver'] ) >= 0 && $mode=='selection') {
			if ($xml_info['onlypro'] != 'yes' || $Knews_plugin->im_pro()==true) {
				$selectable=true;
				
				echo '<a href="#" onclick="'. (($popup) ? 'parent.parent.' : '') . 'jQuery(\'input\', '. (($popup) ? 'parent.parent.' : '') . 'jQuery(this).parent().parent()).attr(\'checked\', true); return false;" title="' . __('Select this template','knews') . '">';
			}
		}
		
		if (is_file($template_path . '/thumbnail.png')) {
?>
		<img src="<?php echo $template_url; ?>/thumbnail.png" style="padding-right:20px;" />
<?php
		} elseif (is_file($template_path . '/thumbnail.jpg')) {
?>
		<img src="<?php echo $template_url; ?>/thumbnail.jpg" style="padding-right:20px;" />
<?php
		} else {
?>
		<img src="<?php echo KNEWS_URL; ?>/images/thumbnail.png" style="padding-right:20px;" />
<?php
		}
		if ($selectable) echo '</a>'; ?></div>
		<div>
			<h1 style="font-size:20px; padding:0 0 10px 0; margin:0">
			<?php
			if ($selectable) echo '<input type="radio" name="template" value="' . $templateID . '" />';

			echo $xml_info['shortname'] . ' <span style="font-weight:normal">v' . $xml_info['version'] . '</span></h1>';
			if (version_compare( KNEWS_VERSION, $xml_info['minver'] ) < 0) {
				echo '<p style="color:#e00; font-weight:bold; margin:0;">';
				echo sprintf(__('This template requires Knews version %s you must update Knews before use this template','knews'), $xml_info['minver'] . (($xml_info['onlypro'] == 'yes') ? ' Pro' : ''));
				echo '</p>';
			} else {
				if ($xml_info['onlypro'] == 'yes' && !$Knews_plugin->im_pro()) {
					echo '<p style="color:#e00; font-weight:bold; margin:0">';
					echo sprintf( __('This template requires the professional version of Knews. You can get it %s here','knews'),'<a href="http://www.knewsplugin.com" target="_blank">');
					echo '</a></p>';
				}
			}
			?>
			<h2 style="font-size:16px; padding:0 0 6px 0; margin:0; line-height:20px;"><?php echo $xml_info['fullname']; ?></h2>
			<p style="font-size:13px; padding:0 0 0 0; margin:0"><strong><?php echo (($xml_info['urlauthor'] != '') ? '<a href="' . $xml_info['urlauthor'] . '" target="_blank">' : '') . $xml_info['author'] . (($xml_info['urlauthor'] != '') ? '</a>' : '') . '</strong> (' . $xml_info['date'] . ')'; ?></p>
			<?php
			if ($xml_info['url'] != '') {
			?>
			<p style="font-size:13px; padding:0 0 0 0; margin:0"><a href="<?php echo $xml_info['url']; ?>" target="_blank"><?php _e('Go to template page','knews'); ?></a></p>
			<?php
			}
			$v=$xml_info['version'];
			$v=substr($v, 0, strpos($v, '.'));
			if ($v=='1') $v='';
			if ($mode=='selection') {
			?>
			<input type="hidden" name="vp_<?php echo $templateID; ?>" id="vp_<?php echo $templateID; ?>" value="<?php echo $v; ?>" />
			<input type="hidden" name="path_<?php echo $templateID; ?>" id="path_<?php echo $templateID; ?>" value="<?php echo $template_path; ?>" />
			<input type="hidden" name="url_<?php echo $templateID; ?>" id="url_<?php echo $templateID; ?>" value="<?php echo $template_url; ?>" />
			<input type="hidden" name="ver_<?php echo $templateID; ?>" id="ver_<?php echo $templateID; ?>" value="<?php echo $xml_info['version']; ?>" />
			<p style="margin:0; padding:0; font-size:11px; color:#333"><?php echo $xml_info['description']; ?></p>
			<?php
			} elseif ($mode=='registration') {
				$registration = get_option('knews_template_' . $templateID);
			?>
			<p style="margin:0.5em 0;">Email: <input type="text" name="registered_email_<?php echo $templateID; ?>" id="registered_email_<?php echo $templateID; ?>" value="<?php if (is_array($registration) && isset($registration['email'])) echo $registration['email'];?>" /></p>
			<p style="margin:0.5em 0;">Serial: <input type="text" name="registered_serial_<?php echo $templateID; ?>" id="registered_email_<?php echo $templateID; ?>" value="<?php if (is_array($registration) && isset($registration['serial'])) echo $registration['serial'];?>" /></p>
			<?php
			}
			?>
		</div>
		<?php
		$skins = apply_filters('knews_skins_' . $templateID, array());
		echo '<div class="skin_opts_' . $templateID . '" style="display:none;">';
		if (count($skins) > 0) {
			echo '<h3>Choose ' . $xml_info['shortname'] . ' skin:</h3>';
			$n=0;
			foreach ($skins as $key=>$val) {
				$n++;
				echo '<p style="margin:0; padding:0 0 1em 0;"><input type="radio" name="skin_' . $templateID . '" value="' . $key . '"' . ($n==1 ? ' checked="checked"' : '') . ' /> ' . $val['label'] . '</p>';
			}
		}
		echo '</div>';
		?>
	</div>
<?php
		return true;
	} else {
		return false;
	}
}

function knews_display_templates($popup=false) {
	
	$anytemplate=false;
	$templates = knews_get_all_templates();

	while ($template = current($templates) ){
		if (knews_examine_template(key($templates), $template['folder'], $template['url'], $popup)) $anytemplate=true;
		next($templates);
	}
	reset ($templates);
		
	if (!$anytemplate && $popup) echo '<p>' . _e('You dont have any mobile template!','knews') . '</p>';
}

function knews_get_all_templates() {

	//Load the new plugin templates
	$templates = apply_filters('knews_get_templates', array());

	//Load the old custom /knewstemplates folder
	$wp_dirs = wp_upload_dir();
	if (is_dir($wp_dirs['basedir'] . '/knewstemplates')) {
		chdir ($wp_dirs['basedir'] . '/knewstemplates');
		$folders = scandir( '.' );
		foreach ($folders as $folder) {
			if ($folder != '..' && $folder != '.' && is_dir($folder) && is_file($wp_dirs['basedir'] . '/knewstemplates/' . $folder . '/info.xml') && (is_file($wp_dirs['basedir'] . '/knewstemplates/' . $folder . '/template.html') || is_file($wp_dirs['basedir'] . '/knewstemplates/' . $folder . '/template.php')) ) {
				
				if (!isset($templates[$folder])) $templates[$folder] = array('folder' => $wp_dirs['basedir'] . '/knewstemplates/' . $folder, 'url' => $wp_dirs['baseurl'] . '/knewstemplates/' . $folder, 'type' => 'old-template');
			}
		}
	}

	global $knewsOptions;

	if (isset($knewsOptions['hide_templates']) && count($templates) > 0 && $knewsOptions['hide_templates']=='1') 
		return $templates;
	
	//Load the default knews templates
	chdir (KNEWS_DIR . '/templates');
	$folders = scandir( '.' );
	foreach ($folders as $folder) {
		if ($folder != '..' && $folder != '.' && is_dir($folder) && is_file(KNEWS_DIR . '/templates/' . $folder . '/info.xml') && (is_file(KNEWS_DIR . '/templates/' . $folder . '/template.html') || is_file(KNEWS_DIR . '/templates/' . $folder . '/template.php')) ) {

			if (!isset($templates[$folder])) $templates[$folder] = array('folder' => KNEWS_DIR . '/templates/' . $folder, 'url' => KNEWS_URL . '/templates/' . $folder, 'type' => 'builtin-template');

		}
	}

	return $templates;
}

// function knews_get_all_templates() {

// 	//Load the new plugin templates
// 	$templates = apply_filters('knews_get_templates', array());
// 	if (is_dir(get_template_directory() . '/knewstemplates')) {
// 		chdir (get_template_directory() . '/knewstemplates');
// 		$folders = scandir( '.' );
// 		foreach ($folders as $folder) {
			
// 			if ($folder != '..' && $folder != '.' && is_dir($folder) && is_file(get_template_directory(). '/knewstemplates/' . $folder . '/info.xml') && (is_file(get_template_directory() . '/knewstemplates/' . $folder . '/template.html') || is_file(get_template_directory() . '/knewstemplates/' . $folder . '/template.php')) ) {
				
// 				if (!isset($templates[$folder])) $templates[$folder] = array('folder' => get_template_directory() . '/knewstemplates/' . $folder, 'url' => get_template_directory_uri() . '/knewstemplates/' . $folder, 'type' => 'old-template');
// 			}
// 		}
// 	}
	
// 	global $knewsOptions;
	
// 	if (isset($knewsOptions['hide_templates']) && count($templates) > 0 && $knewsOptions['hide_templates']=='1') 
// 		return $templates;
	
// 	//Load the default knews templates
// 	//chdir (KNEWS_DIR . '/templates');
// 	chdir (get_template_directory().'/knewstemplates');
// 	$folders = scandir( '.' );
// 	foreach ($folders as $folder) {
// 		if ($folder != '..' && $folder != '.' && is_dir($folder) && is_file(get_template_directory() . '/knewstemplates/' . $folder . '/info.xml') && (is_file(get_template_directory() . '/knewstemplates/' . $folder . '/template.html') || is_file(get_template_directory() . '/knewstemplates/' . $folder . '/template.php')) ) {

// 			if (!isset($templates[$folder])) $templates[$folder] = array('folder' => get_template_directory() . '/knewstemplates/' . $folder, 'url' => get_template_directory_uri() . '/knewstemplates/' . $folder, 'type' => 'builtin-template');

// 		}
// 	}
	
// 	return $templates;
// }

function knews_pagination($paged, $maxPage, $items='', $link_params='') {
	if ($link_params != '') {
		$link_params .= '&paged=';
	} else {
		$link_params = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$link_params = remove_query_arg('paged', $link_params) . '&paged=';
	}
?>
		<div class="tablenav-pages">
<?php
	if ($items != '') echo '<span class="displaying-num">' . $items . '  items</span>';

	if ($maxPage > 1) {
		if ($paged > 1) { ?>
			<a href="<?php echo $link_params; ?>1" title="<?php _e('Go to first page','knews'); ?>" class="first-page">&laquo;</a>
			<a href="<?php echo $link_params . ($paged-1); ?>" title="<?php _e('Go to previous page','knews'); ?>" class="prev-page">&lsaquo;</a>
		<?php } else { ?>
			<a href="#" title="<?php _e('Go to first page','knews'); ?>" class="first-page disabled">&laquo;</a>
			<a href="#" title="<?php _e('Go to previous page','knews'); ?>" class="prev-page disabled">&lsaquo;</a>
		<?php } ?>
			<span class="paging-input"><?php echo $paged; ?> <?php _e('of','knews'); ?> <span class="total-pages"><?php echo $maxPage; ?></span></span>
		<?php if ($maxPage > $paged) { ?>
			<a href="<?php echo $link_params . ($paged+1); ?>" title="<?php _e('Go to next page','knews'); ?>" class="next-page">&rsaquo;</a>
			<a href="<?php echo $link_params . $maxPage; ?>" title="<?php _e('Go to last page','knews'); ?>" class="last-page">&raquo;</a>
		<?php } else { ?>
			<a href="#" title="<?php _e('Go to next page','knews'); ?>" class="next-page disabled">&rsaquo;</a>
			<a href="#" title="<?php _e('Go to last page','knews'); ?>" class="last-page disabled">&raquo;</a>					
<?php 
		}
	}
?>
		</div>
	<br class="clear">
<?php
}

function knews_insert_unique_key($type, $submit_id, $link) {
	global  $wpdb, $Knews_plugin;

	//if ($link != '%cant_read_href%' && $link != '%unsubscribe_href%' && $link != '#') {
	if (
		strpos($link,'.') !== false &&
		strpos($link,'<') === false &&
		strpos($link,'>') === false &&
		strpos($link,' ') === false &&
		strpos($link,'\'') === false &&
		strpos($link,'"') === false &&
		strpos($link,'{') === false &&
		strpos($link,'}') === false &&
		strpos($link,'[') === false &&
		strpos($link,']') === false &&
		strpos($link,'%') === false &&
		strpos($link,'#blog_name#') === false &&
		strpos($link,'#url_confirm#') === false &&
		strpos($link,'mailto:') === false 
	) {
		$link_key = substr(md5(uniqid('',true)),-16); $param_href='';

		//pro
		if ( $Knews_plugin->post_safe('knews_utm_source') && $Knews_plugin->post_safe('knews_utm_medium') && $Knews_plugin->post_safe('knews_utm_campaign') && $type==1 ) {
			$valid_ext=array('','.phtml', '.php', '.php3', '.php4', '.php5', '.php6', '.phps', '.cgi', '.exe', '.pl', '.asp', '.aspx', '.shtml', '.shtm', '.fcgi', '.fpl', '.jsp', '.htm', '.html', '.xhtml', '.wml');
			$ext=$link;
			$ext=substr($ext, strpos($ext,'.'));
			if (strpos($ext,'/') === false) $ext='';
			if (strpos($ext,'/') !== false) $ext=substr($ext, strpos($ext,'/'));
			if (strpos($ext,'#') !== false) $ext=substr($ext, 0, strpos($ext,'#'));
			if (strpos($ext,'?') !== false) $ext=substr($ext, 0, strpos($ext,'?'));
			if (strrpos($ext,'.') !== false) $ext=substr($ext, strrpos($ext,'.'));
			if (substr($ext, -1) == '/') $ext = substr($ext, 0, strlen($ext) -1);
			if (strpos($ext,'.') === false) $ext='';
			$ext=strtolower($ext);
			if (in_array($ext, $valid_ext)) {
				$extra_params = 'utm_source=' . $Knews_plugin->post_safe('knews_utm_source') . '&utm_medium=' . $Knews_plugin->post_safe('knews_utm_medium');
				$extra_params .= '&utm_campaign=' . $Knews_plugin->post_safe('knews_utm_campaign') . html_entity_decode($Knews_plugin->post_safe('knews_extra_param'));
	
				$param_href = $Knews_plugin->add_extra_params($link, $extra_params);
			}
		}
		//fi pro
		
		$query = 'SELECT * FROM ' . KNEWS_KEYS . ' WHERE type=' . $type . ' AND submit_id=' . $submit_id . ' AND href=\'' . $link . '\'';
		$result = $wpdb->get_row( $query );
		if (!isset($result->id)) {
			$query = 'INSERT INTO ' . KNEWS_KEYS . ' (keyy, type, submit_id, href, param_href) VALUES (\'' . $link_key . '\', ' . $type . ', ' . $submit_id . ', \'' . $link . '\', \'' . $param_href . '\')';
			$results = $wpdb->query( $query );
		}
		return true;
	} else {
		return false;
	}
}

//pro
function knews_get_image_path($postid=0) {
	global $post;
	if ($postid != 0) {
		$id = get_post_thumbnail_id($postid);
	} else {
		$id = get_post_thumbnail_id();
	}
	if(stripos($id,'ngg-') !== false && class_exists('nggdb')){
		$nggImage = nggdb::find_image(str_replace('ngg-','',$id));
		$thumbnail = array(
		$nggImage->imageURL
		);
	} else {
		$thumbnail = wp_get_attachment_image_src($id,'full', true);
	}
	$theimage = $thumbnail[0];
	return $theimage;
}
function knews_pop3_login($host,$port,$user,$pass,$folder="INBOX",$ssl='no', $mode=0) { 

	$ssl_conn='';

	if ($ssl=='ssl' || $ssl=='no') $ssl_conn='/pop3'; //backward compatibility
	if ($ssl=='') $ssl_conn='/pop3/novalidate-cert'; //backward compatibility
	if ($ssl=='sp') $ssl_conn='/pop3/ssl';
	if ($ssl=='spc') $ssl_conn='/pop3/ssl/novalidate-cert';

	if ($ssl=='ni') $ssl_conn='/imap';
	if ($ssl=='nic') $ssl_conn='/imap/novalidate-cert';
	if ($ssl=='si') $ssl_conn='/imap/ssl';
	if ($ssl=='sic') $ssl_conn='/imap/ssl/novalidate-cert';

	if ($ssl=='nn') $ssl_conn='/nntp';
	if ($ssl=='nnc') $ssl_conn='/nntp/novalidate-cert';
	if ($ssl=='sn') $ssl_conn='/nntp/ssl';
	if ($ssl=='snc') $ssl_conn='/nntp/ssl/novalidate-cert';

	if ($mode == 0) {
			
/*
// Para conectarse a un servidor IMAP que se ejecuta en el puerto 142 de la m�quina local,
// haga lo siguiente:
$mbox = imap_open("{localhost:143}INBOX", "user_id", "password");

// Para conectarse a un servidor POP3 en el puerto 110 del servidor local, use:
$mbox = imap_open ("{localhost:110/pop3}INBOX", "user_id", "password");

// Para conectarse a un servidor SSL, IMAP o POP3, a�ada /ssl despu�s de la especificaci�n
// del protocolo:
$mbox = imap_open ("{localhost:993/imap/ssl}INBOX", "user_id", "password");

// Para conectarse a un servidor SSL, IMAP o POP3, con un certificado autofirmado,
// a�ada /ssl/novalidate-cert despu�s de la especificaci�n del protocolo:
$mbox = imap_open ("{localhost:995/pop3/ssl/novalidate-cert}", "user_id", "password");

// Para conectarse a un servidor NNTP en el puerto 119 en el servidor local, use:
$nntp = imap_open ("{localhost:119/nntp}comp.test", "", "");
// Para conectarse a un servidor remoto reemplace "localhost" con el nombre o la
// direcci�n IP del servidor al que se quiere conectar.
*/

		
		$reply = @imap_open("{"."$host:$port$ssl_conn"."}$folder",$user,$pass);
		if (!$reply || $reply=='0') return false;
		return array($reply, 0, "{"."$host:$port$ssl_conn"."}$folder"); 

	} else {

		$ssl_prefix='';
		if ($ssl=='sp' || $ssl=='spc' || $ssl=='si'|| $ssl=='sic' || $ssl=='sn' || $ssl=='snc') $ssl_prefix='ssl://';

		$fp=fsockopen($ssl_prefix . $host, $port, $errno, $errstr, 30);
		
		if($fp){
			//echo 'Connected!<br>';
			
			$username="USER " . $user . "\r\n";
			$password="PASS " . $pass . "\r\n";
		
			$ur = knews_fsock_communicate($fp, $username);
			//echo 'Username sent, server response: '.$ur.'<br>';
	
			$pr = knews_fsock_communicate($fp, $password);
			//echo 'Password sent, server response: '.$pr.'<br>';
	
			if (knews_fsock_check_response($pr)) return array($fp, $mode, "{"."$host:$port$ssl_conn"."}$folder");
			
			$res=fgets($fp);
			if (knews_fsock_check_response($res)) return array($fp, $mode, "{"."$host:$port$ssl_conn"."}$folder");
			//echo '*' . $res;
	
			return false;
			
		} else {
			return false;
		}
	}
} 
function knews_pop3_stat($fp) { 
	$connection = $fp[0];
	$mode = $fp[1];
	$mailbox = $fp[2];
	
	if ($mode == 0) {
		//$check = imap_mailboxmsginfo($connection); 
		$status = imap_status ($connection, $mailbox, SA_ALL);
		$reply = array();
		if (isset($status->unseen)) $reply['Unread'] = $status->unseen;
		if (isset($status->messages)) $reply['Nmsgs'] = $status->messages;

		return ($reply); 

	} else {

		$get = knews_fsock_communicate($connection, "LIST\r\n");
		
		$this_line = ""; $stat=array();
		while(true) {
			$this_line = fgets($connection, 128);
			if ($this_line == ".\r\n" || $this_line==''  || !$this_line) break;
			if (substr($this_line, 0, 1) != '+' && substr($this_line, 0, 1) != '-') {
				$stat['Nmsgs']++;
				//echo "< " . $this_line;
			}
		}
		//echo 'sacabo';
		return $stat;
	}
} 
function knews_pop3_list($fp, $messages=50, $delete=true) { 
	$connection = $fp[0];
	$mode = $fp[1];

	$result=array();

	if ($mode == 0) {
		//$MC = imap_check($connection); 
		for ($n=1; $n <= $messages; $n++) {
			$result[] = imap_fetchheader($connection, $n).imap_body($connection, $n); //entire message
			if ($delete) imap_delete ($connection, $n );
		}
		if ($delete) imap_expunge($connection);
	
	} else {

		stream_set_timeout($connection, 1);
			
		for ($nm=1; $nm <= $messages; $nm++) {
			$retr = knews_fsock_communicate($connection, "RETR " . $nm . "\n") . '**';
			
			$nn=0; $message='';
			while (($buffer = fgets($connection)) !== false) {
				$nn++;
				if ($nn==30000) break;
				$message .= $buffer;
			}
	
			$result[] = $message;
		}
		for ($n=1; $n <= $messages; $n++) {
			if ($delete) $del = knews_fsock_communicate($connection, "DELE " . $n . "\n");
			//echo $del;
		}
	}
	
	return $result; 
} 
/*function pop3_retr($fp, $message) { 
	$connection = $fp[0];
	$mode = $fp[1];
    return(imap_fetchheader($connection,$message,FT_PREFETCHTEXT)); 
} */
function knews_pop3_close($fp) {
	$connection = $fp[0];
	$mode = $fp[1];

	if ($mode == 0) {

		$alerts = imap_alerts();
		$errors = imap_errors();
		imap_close ( $connection );

	} else {

		$quit = knews_fsock_communicate($connection, "quit\r\n");
		fclose($connection);
	}
}

function knews_fsock_check_response($response) {
	$r = Array();
	$r = explode(" ", $response);
	return ($r[0] == "+OK") ? true : false;
}

function knews_fsock_communicate($fp, $send, $end = "\n") {
	$response = "";
	//echo "> " . $send;
	fwrite($fp, $send, strlen($send));
	while(substr($response, (strlen($response)-strlen($end)), strlen($end)) != $end) {
		$this_line = fgets($fp, 32000);
		$response .= $this_line;
	}
	return $response;
}
//fi pro
?>