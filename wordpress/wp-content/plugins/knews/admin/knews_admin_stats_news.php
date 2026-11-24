<?php
$news = $Knews_plugin->post_safe('news', $Knews_plugin->get_safe('news') );
$show_auto = $Knews_plugin->post_safe('show_auto', 0 );
?>
	<div class="stats_filter">
		<form method="post" action="admin.php?page=knews_stats">
			<?php 
			echo '<p><span style="display:inline-block; vertical-align:top; padding-top:5px;">' . __('Filter by Newsletter:', 'knews') . '</span> <span style="display:inline-block;"> <select name="news">';
			$query='SELECT id, name, mobile, id_mobile FROM ' . KNEWS_NEWSLETTERS . " WHERE mobile=0 AND (newstype='unknown' OR newstype='manual' " . ($show_auto==1 ? "OR newstype='automated'" : "") . ") ORDER BY id DESC";
			$results=$wpdb->get_results($query);
			foreach ($results as $r) {
				if ($news=='') $news=$r->id;
				echo '<option value="' . $r->id . '"' . (($news==$r->id) ? ' selected="selected"' : '') . '>' . $r->name . '</option>';
				
				if ($news==$r->id) {
					$news2= $r->id_mobile;
					$news_name = $r->name;
				}
			}
			echo '</select><input type="submit" value="' . __('Filter','knews') . '" class="button-secondary" /><br>';
			echo ' <input type="checkbox" ' . ($show_auto==1 ? 'checked="checked"' : '') . ' name="show_auto" value="1" onClick="this.form.submit();" /> ' . __('Show auto-created newsletters','knews');
			echo '</p>';
			echo '<input type="hidden" name="tab" value="news" />';
			//Security for CSRF attacks
			wp_nonce_field($knews_nonce_action, $knews_nonce_name); 
			?>
		</form>
	</div>
	<?php
	$query='SELECT * FROM ' . KNEWS_NEWSLETTERS_SUBMITS . " WHERE blog_id=" . get_current_blog_id() . '  AND newsletter=' . $news;
	$submits=$wpdb->get_results($query);

	if ($news2 == 0) {
		$filtersubmits = 'newsletter=' . $news;
	} else {
		$filtersubmits = '(newsletter=' . $news . ' OR newsletter=' . $news2 . ')';
	}
	$filterstats='';

	foreach ($submits as $s) {
		if ($filterstats=='') {
			if (count($submits) > 1) $filterstats = '(';
		} else {
			$filterstats .= ' OR ';
		}
		$filterstats .= 'submit_id=' . $s->id;
	}
	if (count($submits) > 1) $filterstats .= ')';

	if ($filterstats != '') {

		// Min submit
		$query='SELECT MIN(start_time) AS min_submision FROM ' . KNEWS_NEWSLETTERS_SUBMITS . " WHERE blog_id=" . get_current_blog_id() . '  AND ' . $filtersubmits;
		$results=$wpdb->get_results($query);
		$selected_min_date_chart=$Knews_plugin->sql2time($results[0]->min_submision)-(1*24*60*60);

		// Max submit
		$query='SELECT MAX(start_time) AS max_submision FROM ' . KNEWS_NEWSLETTERS_SUBMITS . " WHERE blog_id=" . get_current_blog_id() . '  AND ' . $filtersubmits;
		$results=$wpdb->get_results($query);
		$selected_max_date_chart=$Knews_plugin->sql2time($results[0]->max_submision);

		/* Min stats
		$query='SELECT MIN(date) AS min_stat FROM ' . KNEWS_STATS . ' WHERE ' . $filterstats;
		$results=$wpdb->get_results($query);
		$min_stats=$Knews_plugin->sql2time($results[0]->min_stat);
		if ($min_stats < $selected_min_date_chart) $selected_min_date_chart = $min_stats;*/

		// Max stats
		$query='SELECT MAX(date) AS max_stat FROM ' . KNEWS_STATS . ' WHERE ' . $filterstats;
		$results=$wpdb->get_results($query);
		$max_stats=$Knews_plugin->sql2time($results[0]->max_stat);
		if ($max_stats > $selected_max_date_chart) $selected_max_date_chart = $max_stats;

		$cols=20;
		$days = (($selected_max_date_chart + (60 * 60 * 24)) - $selected_min_date_chart) / (60 * 60 * 24);
			
		if ($days < 1) {
			
			echo '<div class="error"><p>' . __('There is not stats for this newsletter','knews') . '</p></div>';
	
		} else {
				 
			if ($days < $cols) $cols=$days;
			
			$interval = intval(($days / $cols) * (60 * 60 * 24));
			
			 ?>
			<script type="text/javascript">
				function view_graph(n_custom, n_lang) {
					jQuery('div.pestanyes_'+n_custom+' a').removeClass('on');
					jQuery('a.link_'+n_custom+'_'+n_lang).addClass('on');
				
					target='div.pregunta_'+n_custom+' > div.on';
					jQuery('div.pregunta_'+n_custom+' > div').css('display','none').removeClass('on').addClass('off');
					jQuery('div.custom_lang_'+n_custom+'_'+n_lang).css('display','block').addClass('on').removeClass('off');
				}
			</script>
			<?php
	
	/* ------------------------------------------------------------------------------------------ */
	
			$enviaments_ok=0;
			$enviaments_error=0;
			$cant_read=0;
			$blocks=0;
			$clicks=0;
			$mobile=0;
			$opens=0;
			$sbounced=0;
			$hbounced=0;
			//$e_serie1=array(0);$e_serie2=array(0);$e_serie3=array(0);$e_serie4=array(0);$e_serie5=array(0);$e_serie6=array(0);$e_serie7=array(0);$e_serie8=array(0); $date_caption=array('');
	
			for ($i=0; $i<$cols; $i++) {
				$date1 = $Knews_plugin->get_mysql_date($selected_min_date_chart + $i * $interval);
				$date2_num = ($selected_min_date_chart + ($i + 1) * $interval)-1;
				$date2 = $Knews_plugin->get_mysql_date($date2_num);
				
				//if ($i%2 == 0) {
					$date_caption[] =  date("d/m/y", $selected_min_date_chart + ($i + 0.5) * $interval);
				/*} else {
					$date_caption[] = '';
				}*/
	
				// Enviaments OK i Error
				$query='SELECT users_ok, users_error FROM ' . KNEWS_NEWSLETTERS_SUBMITS . " WHERE blog_id=" . get_current_blog_id() . " AND (start_time BETWEEN '" . $date1 . "' AND '" . $date2 . "') AND " . $filtersubmits;
				$results=$wpdb->get_results($query);
				$e_ok=0;
				$e_err=0;
				foreach ($results as $r) {
					$e_ok += $r->users_ok;
					$e_err += $r->users_error;
				}
				$enviaments_ok += $e_ok;
				$enviaments_error += $e_err;
				$e_serie1[] = $enviaments_ok;
				$e_serie2[] = $enviaments_error;
	
				// Cant read
				$query='SELECT COUNT(*) AS cant_read FROM ' . KNEWS_STATS . " WHERE what = 2 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$cant_read += $results[0]->cant_read;
				$e_serie3[] = $cant_read;
	
				// Block
				$query='SELECT COUNT(*) AS blocks FROM ' . KNEWS_STATS . " WHERE what = 3 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$blocks += $results[0]->blocks;
				$e_serie4[] = $blocks;
	
				// Click
				$query='SELECT COUNT(*) AS clicks FROM ' . KNEWS_STATS . " WHERE what = 1 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$clicks += $results[0]->clicks;
				$e_serie5[] = $clicks;
	
				// Opened
				if ($date2_num< $min_tracking) {
					$query='SELECT COUNT(*) AS opens FROM ' . KNEWS_STATS . " WHERE what = 1 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				} else {
					$query='SELECT COUNT(*) AS opens FROM ' . KNEWS_STATS . " WHERE what = 7 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				}
				$results=$wpdb->get_results($query);
				$opens += $results[0]->opens;
				$e_serie6[] = $opens;
	
				// Soft bounce
				$query='SELECT COUNT(*) AS clicks FROM ' . KNEWS_STATS . " WHERE what = 5 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$sbounced += $results[0]->clicks;
				$e_serie7[] = $sbounced;
	
				// Hard bounce
				$query='SELECT COUNT(*) AS clicks FROM ' . KNEWS_STATS . " WHERE what = 6 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$hbounced += $results[0]->clicks;
				$e_serie8[] = $hbounced;
	
				// Mobile
				$query='SELECT COUNT(*) AS clicks FROM ' . KNEWS_STATS . " WHERE what = 4 AND date BETWEEN '" . $date1 . "' AND '" . $date2 . "' AND " . $filterstats;
				$results=$wpdb->get_results($query);
				$mobile += $results[0]->clicks;
				$e_serie9[] = $mobile;
			}
			?>
			<h3><span><?php _e('Delivery issues & user unsubscription stats for: ','knews'); echo $news_name; ?></span></h3>

			<?php
			$total_users = $enviaments_ok + $enviaments_error;
			$realment_ok = $enviaments_ok - ($hbounced + $sbounced + $blocks);
		}
		if ($total_users != 0) {

			//knews_drawPie(array($realment_ok , $enviaments_error, $blocks, $sbounced, $hbounced), array("", "", "", "", ""), 'chart1.png', 'news.txt', false, false);
			
		?>
			<script type="text/javascript">
				
				// Set a callback to run when the Google Visualization API is loaded.
				google.setOnLoadCallback(drawChart);
				
				// Callback that creates and populates a data table, 
				// instantiates the pie chart, passes in the data and
				// draws it.
				function drawChart() {
				
				// Create the data table.
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Topping');
				data.addColumn('number', 'Slices');
				data.addRows([
					['<?php _e('Sendings OK','knews'); ?>', <?php echo $realment_ok; ?>],
					['<?php _e('Sendings Error','knews'); ?>', <?php echo $enviaments_error; ?>],
					['<?php _e('Unsubscriptions','knews'); ?>', <?php echo $blocks; ?>],
					['<?php _e('Soft bounced','knews'); ?>', <?php echo $sbounced; ?>],
					['<?php _e('Hard bounced','knews'); ?>', <?php echo $hbounced; ?>],
				]);
				
				// Set chart options
				var options = { 'title':'', 
								'width':300, 
								'height':300, 
								pieHole: 0.4,
								legend: {position: 'none'},
								chartArea: { left:100, right:100, top:20, bottom:0 },
								backgroundColor: 'transparent',
								colors: ['#2175a8', '#fc8300', '#ff0000', '#525252', '#000000'],
							};
				
				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById('delivery_issues'));
				chart.draw(data, options);
				}
			</script>
			<div class="table_float">
				<table border="0" cellpadding="0" cellspacing="0" width="350">
					<tr class="alt">
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_blue.gif" width="13" height="13" alt="1" /></td>
						<td><?php _e('Sendings OK','knews'); ?></td><td align="right"><?php echo $realment_ok; ?></td><td align="right"><?php echo knews_safe_percent($realment_ok, $total_users); ?>%</td>
					</tr>
					<tr>
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_orange.gif" width="13" height="13" alt="2" /></td>
						<td><?php _e('Sendings Error','knews'); ?></td><td align="right"><?php echo $enviaments_error; ?></td><td align="right"><?php echo knews_safe_percent($enviaments_error, $total_users); ?>%</td>
					</tr>
					<tr>
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_red.gif" width="13" height="13" alt="3" /></td>
						<td><?php _e('Unsubscriptions','knews'); ?></td><td align="right"><?php echo $blocks; ?></td><td align="right"><?php echo knews_safe_percent($blocks, $total_users); ?>%</td>
					</tr>
					<tr class="alt">
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_gray.gif" width="13" height="13" alt="2" /></td>
						<td><?php _e('Soft bounced','knews'); ?></td><td align="right"><?php echo $sbounced; ?></td><td align="right"><?php echo knews_safe_percent($sbounced, $total_users); ?>%</td>
					</tr>
					<tr>
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_black.gif" width="13" height="13" alt="3" /></td>
						<td><?php _e('Hard bounced','knews'); ?></td><td align="right"><?php echo $hbounced; ?></td><td align="right"><?php echo knews_safe_percent($hbounced, $total_users); ?>%</td>
					</tr>
					<tr class="alt">
						<td><img src="<?php echo KNEWS_URL; ?>/images/legend_plus.gif" width="13" height="13" alt="3" /></td>
						<td><?php _e('Total','knews'); ?></td><td align="right"><?php echo $total_users; ?></td><td>&nbsp;</td>
					</tr>
				</table>
			</div>
			<div class="pie_float" id="delivery_issues"></div>
			<div class="clear"></div>
			<h3><span><?php _e('Detailed stats for the newsletter: ','knews'); echo $news_name; ?></span></h3>
	<script type="text/javascript">
		google.setOnLoadCallback(drawChart3);
			
		function drawChart3() {
			var data5 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Sendings OK','knews')); ?>', '<?php knews_safe_js(__('Opened','knews')); ?>', '<?php knews_safe_js(__('Sendings Error','knews')); ?>', '<?php knews_safe_js(__('Link clicks','knews')); ?>', '<?php knews_safe_js(__('Cant read','knews')); ?>', '<?php knews_safe_js(__('Unsubscriptions','knews')); ?>', '<?php knews_safe_js(__('Soft bounced','knews')); ?>', '<?php knews_safe_js(__('Hard bounced','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie1[$x]; ?>, <?php echo $e_serie6[$x]; ?>, <?php echo $e_serie2[$x]; ?>, <?php echo $e_serie5[$x]; ?>, <?php echo $e_serie3[$x]; ?>, <?php echo $e_serie4[$x]; ?>, <?php echo $e_serie7[$x]; ?>, <?php echo $e_serie8[$x]; ?>],
			<?php } ?>
			]);

			var options = {
				//title: 'Company Performance',
				//curveType: 'function',
				legend: { position: 'right' },
				chartArea: { left:70, right:30, top:30, bottom:10, backgroundColor: '#fff' },
				backgroundColor: '#f9f9f9',
				width: 800,
				height: 400,
				pointSize:6,
				colors: ['#2175a8', '#24c11c', '#fc8300', '#ff00fd', '#a0cee9', '#ff0000', '#525252', '#000000'],
				vAxis: { 
					viewWindowMode:'explicit',
					viewWindow:{
						min:0
					}
				}
			};
			
			var chart5 = new google.visualization.LineChart(document.getElementById('chart_5'));
			chart5.draw(data5, options);
		
			var data6 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Sendings OK','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie1[$x]; ?>],
			<?php } ?>
			]);
			var chart6 = new google.visualization.LineChart(document.getElementById('chart_6'));
			chart6.draw(data6, options);

			options.colors = new Array('#24c11c');
			var data7 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Opened','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie6[$x]; ?>],
			<?php } ?>
			]);
			var chart7 = new google.visualization.LineChart(document.getElementById('chart_7'));
			chart7.draw(data7, options);

			options.colors = new Array('#fc8300');
			var data8 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Sendings Error','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie2[$x]; ?>],
			<?php } ?>
			]);
			var chart8 = new google.visualization.LineChart(document.getElementById('chart_8'));
			chart8.draw(data8, options);

			options.colors = new Array('#ff00fd');
			var data9 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Link clicks','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie5[$x]; ?>],
			<?php } ?>
			]);
			var chart9 = new google.visualization.LineChart(document.getElementById('chart_9'));
			chart9.draw(data9, options);

			options.colors = new Array('#a0cee9');
			var data10 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Cant read','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie3[$x]; ?>],
			<?php } ?>
			]);
			var chart10 = new google.visualization.LineChart(document.getElementById('chart_10'));
			chart10.draw(data10, options);

			options.colors = new Array('#ff0000');
			var data11 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Unsubscriptions','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie4[$x]; ?>],
			<?php } ?>
			]);
			var chart11 = new google.visualization.LineChart(document.getElementById('chart_11'));
			chart11.draw(data11, options);

			options.colors = new Array('#525252');
			var data12 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Soft bounced','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie7[$x]; ?>],
			<?php } ?>
			]);
			var chart12 = new google.visualization.LineChart(document.getElementById('chart_12'));
			chart12.draw(data12, options);

			options.colors = new Array('#000000');
			var data13 = new google.visualization.arrayToDataTable([
			['Date', '<?php knews_safe_js(__('Hard bounced','knews')); ?>'],
			<?php for ($x=0; $x<count($e_serie1); $x++) { ?>
			['<?php echo $date_caption[$x]; ?>',  <?php echo $e_serie8[$x]; ?>],
			<?php } ?>
			]);
			var chart13 = new google.visualization.LineChart(document.getElementById('chart_13'));
			chart13.draw(data13, options);
		}
	</script>
			<?php
			/*
			if ($Knews_plugin->get_custom_text('text_direction', get_bloginfo('language')) == 'ltr') {
				knews_drawLine ( array($e_serie1, $e_serie6, $e_serie2, $e_serie5, $e_serie3, $e_serie4, $e_serie7, $e_serie8, $date_caption), array (__('Sendings','knews'), __('Sendings OK','knews'), __('Opened','knews'), __('Sendings Error','knews'), __('Link clicks','knews'), __('Cant read','knews'), __('Unsubscriptions','knews'), __('Soft bounced','knews'),  __('Hard bounced','knews'), ''), 'all.png');
				knews_drawLine ( array($e_serie1, $date_caption), array (__('Sendings OK','knews'), '', ''), 'sendingsok.png', 'blue.txt', false);
				knews_drawLine ( array($e_serie2, $date_caption), array (__('Sendings Error','knews'), '', ''), 'sendingserr.png', 'orange.txt', false);
				knews_drawLine ( array($e_serie3, $date_caption), array (__('Cant read','knews'), '', ''), 'cantread.png', 'cian.txt', false);
				knews_drawLine ( array($e_serie4, $date_caption), array (__('Unsubscriptions','knews'), '', ''), 'unsubscriptions.png', 'red.txt', false);
				knews_drawLine ( array($e_serie5, $date_caption), array (__('Total clicks','knews'), '', ''), 'clicks.png', 'magenta.txt', false);
				knews_drawLine ( array($e_serie6, $date_caption), array (__('Opened','knews'), '', ''), 'opened.png', 'green.txt', false);
				knews_drawLine ( array($e_serie7, $date_caption), array (__('Soft bounced','knews'), '', ''), 'sbounced.png', 'gray.txt', false);
				knews_drawLine ( array($e_serie8, $date_caption), array (__('Hard bounced','knews'), '', ''), 'hbounced.png', 'black.txt', false);
			} else {
				knews_drawLine ( array($e_serie1, $e_serie6, $e_serie2, $e_serie5, $e_serie3, $e_serie4, $e_serie7, $e_serie8, $date_caption), array ('Sendings', 'Sendings OK', 'Opened', 'Sendings Error', 'Link clicks', 'Cant read', 'Unsubscriptions', 'Soft bounced', 'Hard bounced', ''), 'all.png');
				knews_drawLine ( array($e_serie1, $date_caption), array ('Sendings OK', '', ''), 'sendingsok.png', 'blue.txt', false);
				knews_drawLine ( array($e_serie2, $date_caption), array ('Sendings Error', '', ''), 'sendingserr.png', 'orange.txt', false);
				knews_drawLine ( array($e_serie3, $date_caption), array ('Cant read', '', ''), 'cantread.png', 'cian.txt', false);
				knews_drawLine ( array($e_serie4, $date_caption), array ('Unsubscriptions', '', ''), 'unsubscriptions.png', 'red.txt', false);
				knews_drawLine ( array($e_serie5, $date_caption), array ('Total clicks', '', ''), 'clicks.png', 'magenta.txt', false);			
				knews_drawLine ( array($e_serie6, $date_caption), array ('Opened', '', ''), 'opened.png', 'green.txt', false);
				knews_drawLine ( array($e_serie7, $date_caption), array ('Soft bounced', '', ''), 'sbounced.png', 'gray.txt', false);
				knews_drawLine ( array($e_serie8, $date_caption), array ('Hard bounced', '', ''), 'hbounced.png', 'black.txt', false);
			}
			*/
		?>
			<div class="pestanyes pestanyes_2">
					<a onclick="view_graph(2,1); return false;" class="link_2_1 on" href="#"><?php _e('All','knews'); ?></a>
				<a onclick="view_graph(2,2); return false;" class="link_2_2" href="#" title="<?php _e('Sendings OK','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_blue.gif" width="13" height="13" alt="1" /> <?php echo knews_hardCut(__('Sendings OK','knews')); ?></a>
				<a onclick="view_graph(2,3); return false;" class="link_2_3" href="#" title="<?php _e('Opened','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_green.gif" width="13" height="13" alt="1" /> <?php echo knews_hardCut(__('Opened','knews')); ?></a>
				<a onclick="view_graph(2,4); return false;" class="link_2_4" href="#" title="<?php _e('Sendings Error','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_orange.gif" width="13" height="13" alt="2" /> <?php echo knews_hardCut(__('Sendings Error','knews')); ?></a>
				<a onclick="view_graph(2,5); return false;" class="link_2_5" href="#" title="<?php _e('Link clicks','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_magenta.gif" width="13" height="13" alt="5" /> <?php echo knews_hardCut(__('Link clicks','knews')); ?></a>
				<a onclick="view_graph(2,6); return false;" class="link_2_6" href="#" title="<?php _e('Cant read','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_cian.gif" width="13" height="13" alt="3" /> <?php echo knews_hardCut(__('Cant read','knews')); ?></a>
				<a onclick="view_graph(2,7); return false;" class="link_2_7" href="#" title="<?php _e('Unsubscriptions','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_red.gif" width="13" height="13" alt="4" /> <?php echo knews_hardCut(__('Unsubscriptions','knews')); ?></a>
				<a onclick="view_graph(2,8); return false;" class="link_2_8" href="#" title="<?php _e('Soft Bounced','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_gray.gif" width="13" height="13" alt="4" /> <?php echo knews_hardCut(__('Soft bounced','knews')); ?></a>
				<a onclick="view_graph(2,9); return false;" class="link_2_9" href="#" title="<?php _e('Hard Bounced','knews'); ?>"><img src="<?php echo KNEWS_URL; ?>/images/legend_black.gif" width="13" height="13" alt="4" /> <?php echo knews_hardCut(__('Hard bounced','knews')); ?></a>
			</div>
			<div class="pregunta pregunta_2">
				<div id="chart_5" class="custom_lang_2_1 on"></div>
				<div id="chart_6" class="custom_lang_2_2 off"></div>
				<div id="chart_7" class="custom_lang_2_3 off"></div>
				<div id="chart_8" class="custom_lang_2_4 off"></div>
				<div id="chart_9" class="custom_lang_2_5 off"></div>
				<div id="chart_10" class="custom_lang_2_6 off"></div>
				<div id="chart_11" class="custom_lang_2_7 off"></div>
				<div id="chart_12" class="custom_lang_2_8 off"></div>
				<div id="chart_13" class="custom_lang_2_9 off"></div>
			</div>
					<div style="padding:20px 0;">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr class="alt">
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_blue.gif" width="13" height="13" alt="1" /> <?php _e('Sendings OK','knews'); ?>:</td><td align="right"><?php echo $enviaments_ok; ?></td><td align="right"><?php echo knews_safe_percent($enviaments_ok, $enviaments_ok + $enviaments_error); ?>%</td>
								<td width="20">&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_green.gif" width="13" height="13" alt="2" /> <?php _e('Opened','knews'); ?>:</td><td align="right"><?php echo $opens; ?></td><td align="right"><?php echo knews_safe_percent($opens, $enviaments_ok + $enviaments_error); ?>%</td>
								<td width="20">&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_red.gif" width="13" height="13" alt="5" /> <?php _e('Unsubscriptions','knews'); ?>:</td><td align="right"><?php echo $blocks; ?></td><td align="right"><?php echo knews_safe_percent($blocks, $enviaments_ok + $enviaments_error); ?>%</td>
							</tr>
							<tr>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_orange.gif" width="13" height="13" alt="3" /> <?php _e('Sendings Error','knews'); ?>:</td><td align="right"><?php echo $enviaments_error; ?></td><td align="right"><?php echo knews_safe_percent($enviaments_error, $enviaments_ok + $enviaments_error); ?>%</td>
								<td>&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_magenta.gif" width="13" height="13" alt="6" /> <?php _e('Link clicks','knews'); ?>:</td><td align="right"><?php echo $clicks; ?></td><td align="right"><?php echo knews_safe_percent($clicks, $enviaments_ok + $enviaments_error); ?>%</td>
								<td width="20">&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_gray.gif" width="13" height="13" alt="5" /> <?php _e('Soft Bounced','knews'); ?>:</td><td align="right"><?php echo $sbounced; ?></td><td align="right"><?php echo knews_safe_percent($sbounced, $enviaments_ok + $enviaments_error); ?>%</td>
							</tr>
							<tr class="alt">
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_plus.gif" width="13" height="13" alt="4" /> <?php _e('Total submits','knews'); ?>:</td><td align="right"><?php echo ($enviaments_ok + $enviaments_error); ?></td><td align="right">&nbsp;</td>
								<td>&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_cian.gif" width="13" height="13" alt="4" /> <?php _e('Cant read','knews'); ?>:</td><td align="right"><?php echo $cant_read; ?></td>
		<?php //_e('Total clicks','knews'); ?></td><td align="right"><?php echo knews_safe_percent($cant_read, $enviaments_ok + $enviaments_error); ?>%<?php //echo $clicks; ?></td>
								<td width="20">&nbsp;</td>
							<td><img src="<?php echo KNEWS_URL; ?>/images/legend_black.gif" width="13" height="13" alt="5" /> <?php _e('Hard Bounced','knews'); ?>:</td><td align="right"><?php echo $hbounced; ?></td><td align="right"><?php echo knews_safe_percent($hbounced, $enviaments_ok + $enviaments_error); ?>%</td>
						</tr>
						</table>
					</div>
	<?php
			echo '<p>&nbsp;</p><h3><span>' . __('Detailed clicks for the newsletter: ','knews') . $news_name . '</span></h3>';
			echo '<table border="0" cellpadding="0" cellspacing="0">';
	
			$total=0;
			for ($x=0; $x<count($submits); $x++) {
				$query='SELECT DISTINCT(statkey) FROM ' . KNEWS_STATS . " WHERE what = 1 AND submit_id=" . $submits[$x]->id;
				$results=$wpdb->get_results($query);
				if (count($results)>0) echo '<tr class="alt"><td colspan="2"><strong>' . __('Submission in: ','knews') . $Knews_plugin->humanize_dates($submits[$x]->start_time, 'mysql') . '</strong></td></tr>';
	
				$clicks_href=array();
				foreach ($results as $r) {
					$query='SELECT COUNT(*) AS clicks FROM ' . KNEWS_STATS . " WHERE statkey=" . $r->statkey . " AND what=1 AND submit_id=" . $submits[$x]->id;
					$results2=$wpdb->get_row($query);
					if ($r->statkey==0) {
						$link='0';
					} else {
						$query='SELECT href FROM ' . KNEWS_KEYS . " WHERE id=" . $r->statkey;
						$results3=$wpdb->get_row($query);
						$link=$results3->href;
					}
					if (!isset($clicks_href[$link])) $clicks_href[$link]=0;
					$clicks_href[$link] += $results2->clicks;
				}
				while ($c = current($clicks_href)) {
					echo '<tr><td class="link">' . ((key($clicks_href)=='0') ? 'Sorry, old versions of Knews doesnt catch the exact link' : '<a href="' . key($clicks_href) . '" target="_blank">' . key($clicks_href) . '</a>' ) . '</td><td>' . $c . '</td></tr>';
					$total += $c;
					next($clicks_href);
				}
			}
			echo '<tr class="alt"><td>Total:</td><td>' . $total . '</td></tr>';
			echo '</table>';


		} else {
			echo '<div class="updated"><p>' . __('Currently have no stats for this newsletter.','knews') . '</p></div>';
		} 
	} else {
		echo '<div class="updated"><p>' . __('Currently have no stats for this newsletter.','knews') . '</p></div>';
	}

?>
		