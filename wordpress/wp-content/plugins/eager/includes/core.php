<?php
function eager_get_embed_html() {
  echo '<script data-cfasync="false" src="//fast.eager.io/ouwZK88XQ1.js"></script>';
}

add_action('wp_head', 'eager_get_embed_html');
?>
