<?php
global $Knews_plugin;

if ($Knews_plugin) {

	if (! $Knews_plugin->initialized) $Knews_plugin->init();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Select Mobile Template</title>
<style type="text/css">
body {
	font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif;
	font-size:12px;
	color:#333;
	line-height:1.4em;
}
a { color:#21759B; }
h2 { font-weight:normal; }
input.button-primary, button.button-primary, a.button-primary {
    background: repeat-x scroll left top #21759B;
    border-color: #298CBA;
    color: #FFFFFF;
    font-weight: bold;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
	
    -moz-box-sizing: content-box;
    border-radius: 11px 11px 11px 11px;
    border-style: solid;
    border-width: 1px;
    cursor: pointer;
    font-size: 12px !important;
    line-height: 13px;
    padding: 3px 8px;
    text-decoration: none;
}
</style>
</head>
<body>
	<h1>Select the template for the mobile version:</h1>
	<form method="post" action="admin.php?page=knews_news" target="_parent">
<?php
		require_once( KNEWS_DIR . '/includes/knews_util.php');

		knews_display_templates(true);
?>
	<p style="clear:both; padding:20px 0 30px 0; text-align:center;"><input type="submit" value="Use this template" class="button-primary" /></p>
	<input type="hidden" name="action" id="action" value="add_news_mobile" />
	<input type="hidden" name="new_news" id="new_news" value="mobile version" />
	<input type="hidden" name="lang" id="lang" value="<?php echo $Knews_plugin->get_safe('news_lang'); ?>" />
	<input type="hidden" name="parent" id="parent" value="<?php echo $Knews_plugin->get_safe('id_news',0,'int'); ?>" />

	</form>
<?php /*
	<div id="knewsshop">
		<?php 
		$look = wp_remote_get( 'http://www.knewsplugin.com/shop/look.php' );
		if (isset($look['body'])) echo $look['body'];
		?>
	</div>
*/ ?>
</body>
</html>
<?php
}
die();
?>
