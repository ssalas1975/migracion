<?php
if(!function_exists('any_edge_map_blog_meta_fields')) {

    function any_edge_map_blog_meta_fields() {

        $edgt_blog_categories = array();
        $categories = get_categories();
        foreach($categories as $category) {
            $edgt_blog_categories[$category->term_id] = $category->name;
        }

        $blog_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('page'),
                'title' => esc_html__('Blog', 'any'),
                'name' => 'blog_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_blog_category_meta',
                    'type'        => 'selectblank',
                    'label'       => esc_html__('Blog Category', 'any'),
                    'description' => esc_html__('Choose category of posts to display (leave empty to display all categories)', 'any'),
                    'parent'      => $blog_meta_box,
                    'options'     => $edgt_blog_categories
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_show_posts_per_page_meta',
                    'type'        => 'text',
                    'label'       => esc_html__('Number of Posts', 'any'),
                    'description' => esc_html__('Enter the number of posts to display', 'any'),
                    'parent'      => $blog_meta_box,
                    'options'     => $edgt_blog_categories,
                    'args'        => array("col_width" => 3)
                )
            );
        }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_blog_meta_fields');
}