<?php
if( !function_exists('edgtf_quadric_get_blog') ) {
	/**
	 * Function which return holder for all blog lists
	 *
	 * @return holder.php template
	 */
	function edgtf_quadric_get_blog($type) {

		$sidebar = edgtf_quadric_sidebar_layout();

		$params = array(
			"blog_type" => $type,
			"sidebar" => $sidebar
		);
		edgtf_quadric_get_module_template_part('templates/lists/holder', 'blog', '', $params);
	}

}

if( !function_exists('edgtf_quadric_get_blog_type') ) {

	/**
	 * Function which create query for blog lists
	 *
	 * @return blog list template
	 */

	function edgtf_quadric_get_blog_type($type) {
		global $wp_query;

		$id = edgtf_quadric_get_page_id();
		$category = get_post_meta($id, "edgtf_blog_category_meta", true);
		$post_number = esc_attr(get_post_meta($id, "edgtf_show_posts_per_page_meta", true));

		$paged = edgtf_quadric_paged();

		if(!is_archive()) {
			$blog_query = new WP_Query('post_type=post&paged=' . $paged . '&cat=' . $category . '&posts_per_page=' . $post_number);
		}else{
			$blog_query = $wp_query;
		}

		if(edgtf_quadric_options()->getOptionValue('blog_page_range') != ""){
			$blog_page_range = esc_attr(edgtf_quadric_options()->getOptionValue('blog_page_range'));
		} else{
			$blog_page_range = $blog_query->max_num_pages;
		}
		$params = array(
			'blog_query' => $blog_query,
			'paged' => $paged,
			'blog_page_range' => $blog_page_range,
			'blog_type' => $type
		);

		edgtf_quadric_get_module_template_part('templates/lists/' .  $type, 'blog', '', $params);
	}

}



if( !function_exists('edgtf_quadric_get_post_format_html') ) {

	/**
	 * Function which return html for post formats
	 * @param $type
	 * @return post hormat template
	 */

	function edgtf_quadric_get_post_format_html($type = "") {

		$post_format = get_post_format();

		if($post_format === false){
			$post_format = 'standard';
		}elseif($post_format != 'audio' && $post_format != 'video' && $post_format != 'gallery' && $post_format != 'quote' && $post_format != 'link') {
			$post_format = 'standard';
		}

		$slug = '';
		if($type !== ""){
			$slug = $type;
		}

		$params = array();

		$params['print'] = 'yes';
		$params['share'] = 'yes';
		$params['like'] = 'yes';

		if($type == 'standard' || $type == 'standard-whole-post'){
			if(edgtf_quadric_options()->getOptionValue('blog_standard_print') !== ''){
				$params['print'] = edgtf_quadric_options()->getOptionValue('blog_standard_print');
			}
			if(edgtf_quadric_options()->getOptionValue('blog_standard_share') !== ''){
				$params['share'] = edgtf_quadric_options()->getOptionValue('blog_standard_share');
			}
			if(edgtf_quadric_options()->getOptionValue('blog_standard_like') !== ''){
				$params['like'] = edgtf_quadric_options()->getOptionValue('blog_standard_like');
			}

			$params['title_tag'] = 'h2';

		} elseif($type == 'masonry' || $type == 'masonry-full-width') {
			if (edgtf_quadric_options()->getOptionValue('blog_masonry_print') !== '') {
				$params['print'] = edgtf_quadric_options()->getOptionValue('blog_masonry_print');
			}
			if (edgtf_quadric_options()->getOptionValue('blog_masonry_share') !== '') {
				$params['share'] = edgtf_quadric_options()->getOptionValue('blog_masonry_share');
			}
			if (edgtf_quadric_options()->getOptionValue('blog_masonry_like') !== '') {
				$params['like'] = edgtf_quadric_options()->getOptionValue('blog_masonry_like');
			}

			$params['title_tag'] = 'h3';
		}


		$chars_array = edgtf_quadric_blog_lists_number_of_chars();
		if(isset($chars_array[$type])) {
			$params['excerpt_length'] = $chars_array[$type];
		} else {
			$params['excerpt_length'] = '';
		}

		$params['date'] = 'no';
		if(!edgtf_quadric_post_has_title()) {
			$params['date'] = 'yes';
		}
		edgtf_quadric_get_module_template_part('templates/lists/post-formats/' . $post_format, 'blog', $slug, $params);

	}

}

if( !function_exists('edgtf_quadric_get_default_blog_list') ) {
	/**
	 * Function which return default blog list for archive post types
	 *
	 * @return post format template
	 */

	function edgtf_quadric_get_default_blog_list() {

		$blog_list = edgtf_quadric_options()->getOptionValue('blog_list_type');
		return $blog_list;

	}

}


if (!function_exists('edgtf_quadric_pagination')) {

	/**
	 * Function which return pagination
	 *
	 * @return blog list pagination html
	 */

	function edgtf_quadric_pagination($pages = '', $range = 4, $paged = 1){

		$showitems = $range+1;

		if($pages == ''){
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages){
				$pages = 1;
			}
		}
		if(1 != $pages){

			echo '<div class="edgtf-pagination">';
				echo '<ul>';
					if($paged > 2 && $paged > $range+1 && $showitems < $pages){
						echo '<li class="edgtf-pagination-first-page"><a href="'.esc_url(get_pagenum_link(1)).'"><span class="arrow_carrot-2left edgtf-pagination-icon"></span></a></li>';
					}
					echo '<li class="edgtf-pagination-prev';
					if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
						echo ' edgtf-pagination prev-first';
					}
					echo '"><a href="'.esc_url(get_pagenum_link($paged - 1)).'"><span class="arrow_carrot-left edgtf-pagination-icon"></span></a></li>';

					for ($i=1; $i <= $pages; $i++){
						if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
							echo ($paged == $i)? "<li class='active'><span>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
						}
					}

					echo '<li class="edgtf-pagination-next';
					if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages){
						echo ' edgtf-pagination-next-last';
					}
					echo '"><a href="';
					if($pages > $paged){
						echo esc_url(get_pagenum_link($paged + 1));
					} else {
						echo esc_url(get_pagenum_link($paged));
					}
					echo '"><span class="arrow_carrot-right edgtf-pagination-icon"></span></a></li>';
					if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages){
						echo '<li class="edgtf-pagination-last-page"><a href="'.esc_url(get_pagenum_link($pages)).'"><span class="arrow_carrot-2right edgtf-pagination-icon"></span></a></li>';
					}
				echo '</ul>';
			echo "</div>";
		}
	}
}

if(!function_exists('edgtf_quadric_post_info')){
	/**
	 * Function that loads parts of blog post info section
	 * Possible options are:
	 * 1. date
	 * 2. category
	 * 3. author
	 * 4. comments
	 * 5. like
	 * 6. share
	 *
	 * @param $config array of sections to load
	 */
	function edgtf_quadric_post_info($config){
		$default_config = array(
			'date' => '',
			'category' => '',
			'author' => '',
			'comments' => '',
			'like' => '',
			'share' => '',
			'print' => ''
		);

		extract(shortcode_atts($default_config, $config));

		if($date == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-date', 'blog');
		}
		if($author == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-author', 'blog');
		}
		if($category == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-category', 'blog');
		}
		if($comments == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-comments', 'blog');
		}
		if($like == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-like', 'blog');
		}
		if($print == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-print', 'blog');
		}
		if($share == 'yes'){
			edgtf_quadric_get_module_template_part('templates/parts/post-info-share', 'blog');
		}
	}
}

if(!function_exists('edgtf_quadric_excerpt')) {
	/**
	 * Function that cuts post excerpt to the number of word based on previosly set global
	 * variable $word_count, which is defined in edgt_set_blog_word_count function.
	 *
	 * It current post has read more tag set it will return content of the post, else it will return post excerpt
	 *
	 */
	function edgtf_quadric_excerpt($excerpt_length) {
		global $post;

		if(post_password_required()) {
			echo get_the_password_form();
		}

		//does current post has read more tag set?
		elseif(edgtf_quadric_post_has_read_more()) {
			global $more;

			//override global $more variable so this can be used in blog templates
			$more = 0;
			the_content(true);
		}

		//is word count set to something different that 0?
		elseif($excerpt_length != '0') {
			//if word count is set and different than empty take that value, else that general option from theme options

			$word_count = '45';
			if(isset($excerpt_length) && $excerpt_length != ""){
				$word_count = $excerpt_length;

			} elseif(edgtf_quadric_options()->getOptionValue('number_of_chars') != '') {
				$word_count = esc_attr(edgtf_quadric_options()->getOptionValue('number_of_chars'));
			}

			//if post excerpt field is filled take that as post excerpt, else that content of the post
			$post_excerpt = $post->post_excerpt != "" ? $post->post_excerpt : strip_tags($post->post_content);

			//remove leading dots if those exists
			$clean_excerpt = strlen($post_excerpt) && strpos($post_excerpt, '...') ? strstr($post_excerpt, '...', true) : $post_excerpt;

			//if clean excerpt has text left
			if($clean_excerpt !== '') {
				//explode current excerpt to words
				$excerpt_word_array = explode (' ', $clean_excerpt);

				//cut down that array based on the number of the words option
				$excerpt_word_array = array_slice ($excerpt_word_array, 0, $word_count);

				//add exerpt postfix
				$excert_postfix		= apply_filters('edgtf_quadric_excerpt_postfix', '...');

				//and finally implode words together
				$excerpt 			= implode (' ', $excerpt_word_array).$excert_postfix;

				//is excerpt different than empty string?
				if($excerpt !== '') {
					echo '<p class="edgtf-post-excerpt">'.wp_kses_post($excerpt).'</p>';
				}
			}
		}
	}
}

if(!function_exists('edgtf_quadric_get_blog_single')) {

	/**
	 * Function which return holder for single posts
	 *
	 * @return single holder.php template
	 */

	function edgtf_quadric_get_blog_single() {
		$sidebar = edgtf_quadric_sidebar_layout();

		$params = array(
			"sidebar" => $sidebar
		);

		edgtf_quadric_get_module_template_part('templates/single/holder', 'blog', '', $params);
	}
}

if( !function_exists('edgtf_quadric_get_single_html') ) {

	/**
	 * Function return all parts on single.php page
	 *
	 *
	 * @return single.php html
	 */

	function edgtf_quadric_get_single_html() {

		$post_format = get_post_format();
		if($post_format === false){
			$post_format = 'standard';
		}

		$params = array();
		$params['print'] = 'yes';
		$params['share'] = 'yes';
		$params['like'] = 'yes';

		if(edgtf_quadric_options()->getOptionValue('blog_single_print') !== ''){
			$params['print'] = edgtf_quadric_options()->getOptionValue('blog_single_print');
		}
		if(edgtf_quadric_options()->getOptionValue('blog_single_share') !== ''){
			$params['share'] = edgtf_quadric_options()->getOptionValue('blog_single_share');
		}
		if(edgtf_quadric_options()->getOptionValue('blog_single_like') !== ''){
			$params['like'] = edgtf_quadric_options()->getOptionValue('blog_single_like');
		}





		edgtf_quadric_get_module_template_part('templates/single/post-formats/' . $post_format, 'blog', '', $params);
		edgtf_quadric_get_module_template_part('templates/single/parts/single-navigation', 'blog');
		edgtf_quadric_get_module_template_part('templates/single/parts/author-info', 'blog');
		if(edgtf_quadric_show_comments()){
			comments_template('', true);
		}
	}

}
if( !function_exists('edgtf_quadric_additional_post_items') ) {

	/**
	 * Function which return parts on single.php which are just below content
	 *
	 * @return single.php html
	 */

	function edgtf_quadric_additional_post_items() {

		if(has_tag()){
			edgtf_quadric_get_module_template_part('templates/single/parts/tags', 'blog');
		}


		$args_pages = array(
			'before'           => '<div class="edgtf-single-links-pages"><div class="edgtf-single-links-pages-inner">',
			'after'            => '</div></div>',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'pagelink'         => '%'
		);

		wp_link_pages($args_pages);

	}
	add_action('edgtf_quadric_before_blog_article_closed_tag', 'edgtf_quadric_additional_post_items');
}


if (!function_exists('edgtf_quadric_comment')) {

	/**
	 * Function which modify default wordpress comments
	 *
	 * @return comments html
	 */

	function edgtf_quadric_comment($comment, $args, $depth) {

		$GLOBALS['comment'] = $comment;

		global $post;

		$is_pingback_comment = $comment->comment_type == 'pingback';
		$is_author_comment  = $post->post_author == $comment->user_id;

		$comment_class = 'edgtf-comment clearfix';

		if($is_author_comment) {
			$comment_class .= ' edgtf-post-author-comment';
		}

		if($is_pingback_comment) {
			$comment_class .= ' edgtf-pingback-comment';
		}

		?>

		<li>
		<div class="<?php echo esc_attr($comment_class); ?>">
			<?php if(!$is_pingback_comment) { ?>
				<div class="edgtf-comment-image"> <?php echo edgtf_quadric_kses_img(get_avatar($comment, 75)); ?> </div>
			<?php } ?>
			<div class="edgtf-comment-text">
				<div class="edgtf-comment-info">
					<h6 class="edgtf-comment-name">
						<?php if($is_pingback_comment) { esc_html_e('Pingback:', 'quadric'); } ?>
						<?php echo wp_kses_post(get_comment_author_link()); ?>
						<?php if($is_author_comment) { ?>
							<i class="fa fa-user post-author-comment-icon"></i>
						<?php } ?>
					</h6>
				<?php
					comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) );
					edit_comment_link();
				?>
			</div>
			<?php if(!$is_pingback_comment) { ?>
				<div class="edgtf-text-holder" id="comment-<?php echo comment_ID(); ?>">
					<?php comment_text(); ?>
				</div>
				<span class="edgtf-comment-date"><?php comment_time(get_option('date_format')); ?> <?php esc_html_e('at', 'quadric'); ?> <?php comment_time(get_option('time_format')); ?></span>
			<?php } ?>
		</div>
		</div>
		<?php //li tag will be closed by WordPress after looping through child elements ?>

		<?php
	}
}

if( !function_exists('edgtf_quadric_blog_archive_pages_classes') ) {

	/**
	 * Function which create classes for container in archive pages
	 *
	 * @return array
	 */

	function edgtf_quadric_blog_archive_pages_classes($blog_type) {

		$classes = array();
		if(in_array($blog_type, edgtf_quadric_blog_full_width_types())){
			$classes['holder'] = 'edgtf-full-width';
			$classes['inner'] = 'edgtf-full-width-inner';
		} elseif(in_array($blog_type, edgtf_quadric_blog_grid_types())){
			$classes['holder'] = 'edgtf-container';
			$classes['inner'] = 'edgtf-container-inner clearfix';
		}

		return $classes;

	}

}

if( !function_exists('edgtf_quadric_blog_full_width_types') ) {

	/**
	 * Function which return all full width blog types
	 *
	 * @return array
	 */

	function edgtf_quadric_blog_full_width_types() {

		$types = array('masonry-full-width');

		return $types;

	}

}

if( !function_exists('edgtf_quadric_blog_grid_types') ) {

	/**
	 * Function which return in grid blog types
	 *
	 * @return array
	 */

	function edgtf_quadric_blog_grid_types() {

		$types = array('standard', 'masonry', 'standard-whole-post');

		return $types;

	}

}

if( !function_exists('edgtf_quadric_blog_types') ) {

	/**
	 * Function which return all blog types
	 *
	 * @return array
	 */

	function edgtf_quadric_blog_types() {

		$types = array_merge(edgtf_quadric_blog_grid_types(), edgtf_quadric_blog_full_width_types());

		return $types;

	}

}

if( !function_exists('edgtf_quadric_blog_templates') ) {

	/**
	 * Function which return all blog templates names
	 *
	 * @return array
	 */

	function edgtf_quadric_blog_templates() {

		$templates = array();
		$grid_templates = edgtf_quadric_blog_grid_types();
		$full_templates = edgtf_quadric_blog_full_width_types();
		foreach($grid_templates as $grid_template){
			array_push($templates, 'blog-'.$grid_template);
		}
		foreach($full_templates as $full_template){
			array_push($templates, 'blog-'.$full_template);
		}

		return $templates;

	}

}

if( !function_exists('edgtf_quadric_blog_lists_number_of_chars') ) {

	/**
	 * Function that return number of characters for different lists based on options
	 *
	 * @return int
	 */

	function edgtf_quadric_blog_lists_number_of_chars() {

		$number_of_chars = array();
		if(edgtf_quadric_options()->getOptionValue('standard_number_of_chars')) {
			$number_of_chars['standard'] = edgtf_quadric_options()->getOptionValue('standard_number_of_chars');
		}
		if(edgtf_quadric_options()->getOptionValue('masonry_number_of_chars')) {
			$number_of_chars['masonry'] = edgtf_quadric_options()->getOptionValue('masonry_number_of_chars');
		}

		return $number_of_chars;

	}

}

if (!function_exists('edgtf_quadric_excerpt_length')) {
	/**
	 * Function that changes excerpt length based on theme options
	 * @param $length int original value
	 * @return int changed value
	 */
	function edgtf_quadric_excerpt_length( $length ) {

		if(edgtf_quadric_options()->getOptionValue('number_of_chars') !== ''){
			return esc_attr(edgtf_quadric_options()->getOptionValue('number_of_chars'));
		} else {
			return 45;
		}
	}

	add_filter( 'excerpt_length', 'edgtf_quadric_excerpt_length', 999 );
}

if (!function_exists('edgtf_quadric_excerpt_more')) {
	/**
	 * Function that adds three dotes on the end excerpt
	 * @param $more
	 * @return string
	 */
	function edgtf_quadric_excerpt_more( $more ) {
		return '...';
	}
	add_filter('excerpt_more', 'edgtf_quadric_excerpt_more');
}

if(!function_exists('edgtf_quadric_post_has_read_more')) {
	/**
	 * Function that checks if current post has read more tag set
	 * @return int position of read more tag text. It will return false if read more tag isn't set
	 */
	function edgtf_quadric_post_has_read_more() {
		global $post;

		return strpos($post->post_content, '<!--more-->');
	}
}

if(!function_exists('edgtf_quadric_post_has_title')) {
	/**
	 * Function that checks if current post has title or not
	 * @return bool
	 */
	function edgtf_quadric_post_has_title() {
		return get_the_title() !== '';
	}
}

if (!function_exists('edgtf_quadric_modify_read_more_link')) {
	/**
	 * Function that modifies read more link output.
	 * Hooks to the_content_more_link
	 * @return string modified output
	 */
	function edgtf_quadric_modify_read_more_link() {
		$link = '<div class="edgtf-more-link-container">';
		$link .= edgtf_quadric_get_button_html(array(
			'link' => get_permalink().'#more-'.get_the_ID(),
			'text' => esc_html__('Continue reading', 'quadric')
		));

		$link .= '</div>';

		return $link;
	}

	add_filter( 'the_content_more_link', 'edgtf_quadric_modify_read_more_link');
}


if(!function_exists('edgtf_quadric_has_blog_widget')) {
	/**
	 * Function that checks if latest posts widget is added to widget area
	 * @return bool
	 */
	function edgtf_quadric_has_blog_widget() {
		$widgets_array = array(
			'edgt_latest_posts_widget'
		);

		foreach ($widgets_array as $widget) {
			$active_widget = is_active_widget(false, false, $widget);

			if($active_widget) {
				return true;
			}
		}

		return false;
	}
}

if(!function_exists('edgtf_quadric_has_blog_shortcode')) {
	/**
	 * Function that checks if any of blog shortcodes exists on a page
	 * @return bool
	 */
	function edgtf_quadric_has_blog_shortcode() {
		$blog_shortcodes = array(
			'edgtf_blog_list',
			'edgtf_blog_slider',
			'edgtf_blog_carousel'
		);

		$slider_field = get_post_meta(edgtf_quadric_get_page_id(), 'edgtf_page_slider_meta', true); //TODO change

		foreach ($blog_shortcodes as $blog_shortcode) {
			$has_shortcode = edgtf_quadric_has_shortcode($blog_shortcode) || edgtf_quadric_has_shortcode($blog_shortcode, $slider_field);

			if($has_shortcode) {
				return true;
			}
		}

		return false;
	}
}


if(!function_exists('edgtf_quadric_load_blog_assets')) {
	/**
	 * Function that checks if blog assets should be loaded
	 *
	 * @see edgt_is_blog_template()
	 * @see is_home()
	 * @see is_single()
	 * @see edgt_has_blog_shortcode()
	 * @see is_archive()
	 * @see is_search()
	 * @see edgt_has_blog_widget()
	 * @return bool
	 */
	function edgtf_quadric_load_blog_assets() {
		return edgtf_quadric_is_blog_template() || is_home() || is_single() || edgtf_quadric_has_blog_shortcode() || is_archive() || is_search() || edgtf_quadric_has_blog_widget();
	}
}

if(!function_exists('edgtf_quadric_is_blog_template')) {
	/**
	 * Checks if current template page is blog template page.
	 *
	 *@param string current page. Optional parameter.
	 *
	 *@return bool
	 *
	 * @see edgtf_quadric_get_page_template_name()
	 */
	function edgtf_quadric_is_blog_template($current_page = '') {

		if($current_page == '') {
			$current_page = edgtf_quadric_get_page_template_name();
		}

		$blog_templates = edgtf_quadric_blog_templates();

		return in_array($current_page, $blog_templates);
	}
}

if(!function_exists('edgtf_quadric_read_more_button')) {
	/**
	 * Function that outputs read more button html if necessary.
	 * It checks if read more button should be outputted only if option for given template is enabled and post does'nt have read more tag
	 * and if post isn't password protected
	 *
	 * @param string $option name of option to check
	 * @param string $class additional class to add to button
	 *
	 */
	function edgtf_quadric_read_more_button($option = '', $class = '') {
		if($option != '') {
			$show_read_more_button = edgtf_quadric_options()->getOptionValue($option) == 'yes';
		}else {
			$show_read_more_button = 'yes';
		}
		if($show_read_more_button && !edgtf_quadric_post_has_read_more() && !post_password_required()) {
			echo edgtf_quadric_get_button_html(array(
				'size'         => 'small',
				'link'         => get_the_permalink(),
				'text'         => esc_html__('Read More', 'quadric'),
				'custom_class' => $class
			));
		}
	}
}

if ( ! function_exists('edgtf_quadric_comment_form_submit_button')) {
	/**
	 * Override comment form submit button
	 *
	 * @return mixed|string
	 */
	function edgtf_quadric_comment_form_submit_button() {

		$comment_form_button = edgtf_quadric_get_button_html(array(
			'html_type'     => 'input',
			'type'          => 'outline',
			'text'          => 'Submit'
		));

		return $comment_form_button;

	}

	add_filter('comment_form_submit_button', 'edgtf_quadric_comment_form_submit_button');

}

?>