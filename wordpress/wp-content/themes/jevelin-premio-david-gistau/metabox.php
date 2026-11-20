<?php

function _url_create_metabox()
{
    add_meta_box('_title_metabox', 'Url Link externo', '_url_render_metabox', 'post', 'normal', 'default');
}
add_action('add_meta_boxes', '_url_create_metabox');

function _url_render_metabox()
{
    global $post;
    $url = get_post_meta($post->ID, '_url', true); ?>

	<table id="newmeta">
		<tbody>
			<tr>
				<td id="newmetaleft" class="left">
					
				</td>
				<td style="width: 100%">
					<input
						type="text"
						name="_url_custom_metabox"
						id="_url_custom_metabox"
						value="<?php echo esc_attr($url); ?>"
						style="width: 90%"
					>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
    wp_nonce_field('_url_form_metabox_nonce', '_url_form_metabox_process');
}


function _url_save_revisions($post_id)
{
    update_post_meta($post_id, '_url', $_POST['_url_custom_metabox']);
}
add_action('save_post', '_url_save_revisions');
