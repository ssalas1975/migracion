<?php 

function cmsnative_load_scripts($hook) {
 
   wp_enqueue_script("jquery");
   wp_enqueue_script( 'ace_cms_editor', plugins_url( 'assets/codemirror/codemirror.js', __FILE__ ) );
   wp_enqueue_style( 'ace_cms_editor_css', plugins_url( 'assets/codemirror/codemirror.css', __FILE__ ) );
}

function cmsnative_e_load_style(){
	return file_get_contents(WP_CONTENT_DIR."/css/style.css");
}

function cmsnative_e_save_style($content){
	
	
	file_put_contents(WP_CONTENT_DIR."/css/style.css",stripslashes($content));
	cmsnative_writelog();
}


function cmsnative_writelog(){
	
	$cuser=wp_get_current_user();
	$date = date('Y-m-d H:i:s');
	$content=$date." | ".$cuser->user_login;
	
	if(!file_exists(WP_CONTENT_DIR."/uploads/cms-native/"))
		mkdir(WP_CONTENT_DIR."/uploads/cms-native/");
	
	file_put_contents(WP_CONTENT_DIR."/uploads/cms-native/log".date('Ymd').".txt",$content."\n",FILE_APPEND);
	
}



?>