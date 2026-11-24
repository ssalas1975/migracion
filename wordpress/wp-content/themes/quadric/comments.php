<div class="edgtf-comment-holder clearfix" id="comments">
	<div class="edgtf-comment-number">
		<div class="edgtf-comment-number-inner">
			<h6><?php comments_number( esc_html__('No Comments','quadric'), '1'.esc_html__(' Comment ','quadric'), '% '.esc_html__(' Comments ','quadric')); ?></h6>
		</div>
	</div>
<div class="edgtf-comments">
<?php if ( post_password_required() ) : ?>
				<p class="edgtf-no-password"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'quadric' ); ?></p>
			</div></div>
<?php
		return;
	endif;
?>
<?php if ( have_comments() ) : ?>

	<ul class="edgtf-comment-list">
		<?php wp_list_comments(array( 'callback' => 'edgtf_quadric_comment')); ?>
	</ul>


<?php // End Comments ?>

 <?php else : // this is displayed if there are no comments so far 

	if ( ! comments_open() ) :
?>
		<!-- If comments are open, but there are no comments. -->

	 
		<!-- If comments are closed. -->
		<p><?php esc_html_e('Sorry, the comment form is closed at this time.', 'quadric'); ?></p>

	<?php endif; ?>
<?php endif; ?>
</div></div>
<?php
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

$args = array(
	'id_form' => 'commentform',
	'id_submit' => 'submit_comment',
	'title_reply'=> esc_html__( 'Post a Comment','quadric' ),
	'title_reply_to' => esc_html__( 'Post a Reply to %s','quadric' ),
	'cancel_reply_link' => esc_html__( 'Cancel Reply','quadric' ),
	'label_submit' => esc_html__( 'Submit','quadric' ),
	'comment_field' => '<textarea id="comment" placeholder="'.esc_html__( 'Write your comment here...','quadric' ).'" name="comment" cols="45" rows="8" aria-required="true"></textarea>',
	'comment_notes_before' => '',
	'comment_notes_after' => '',
	'fields' => apply_filters( 'comment_form_default_fields', array(
		'author' => '<div class="edgtf-three-columns clearfix"><div class="edgtf-three-columns-inner"><div class="edgtf-column"><div class="edgtf-column-inner"><input id="author" name="author" placeholder="'. esc_html__( 'Your full name','quadric' ) .'" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /></div></div>',
		'url' => '<div class="edgtf-column"><div class="edgtf-column-inner"><input id="email" name="email" placeholder="'. esc_html__( 'E-mail address','quadric' ) .'" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' /></div></div>',
		'email' => '<div class="edgtf-column"><div class="edgtf-column-inner"><input id="url" name="url" type="text" placeholder="'. esc_html__( 'Website','quadric' ) .'" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /></div></div></div></div>'
		 ) ) );
 ?>
<?php if(get_comment_pages_count() > 1){
	?>
	<div class="edgtf-comment-pager">
		<p><?php paginate_comments_links(); ?></p>
	</div>
<?php } ?>
 <div class="edgtf-comment-form">
	<?php comment_form($args); ?>
</div>
								
							


