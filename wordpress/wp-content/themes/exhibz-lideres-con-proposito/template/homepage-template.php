<?php
/*
 * Template Name: HomePage Template
 *  */
echo '<!-- functions exhibz 222222222 -->';
get_header();
while (have_posts()) :
    the_post();
    the_content();
endwhile;
get_footer();
