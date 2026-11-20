<?php 

//funcion para generar Banners

add_shortcode('mey_rss_search', 'mey_generats_rss');

function mey_generats_rss($attr) {
	$html = '';
	$array_to_publish = [];
	if (isset($attr['id_posts_sindicacion'])) {

        $id_single = explode(";", $attr['id_posts_sindicacion']);

        // Get RSS Feed(s)
        include_once(ABSPATH . WPINC . '/feed.php');
        $date_now = strtotime(date("Y-m-d H:i:s"));

        foreach ($id_single as $id_post) {

            $validaPostSingle = get_post($id_post);

            if ($validaPostSingle == null || $validaPostSingle=='' || !isset($validaPostSingle)){
                $html = "One id not found";
                return do_shortcode(htmlspecialchars_decode($html));
            }

            $url_rss = get_post_meta($id_post, 'url_rss', true);
            $max_rss = get_post_meta($id_post, 'numero_post', true);

            if ($url_rss == false){
                $html = "One id not found";
                return do_shortcode(htmlspecialchars_decode($html));
            }

            // Get a SimplePie feed object from the specified feed source.
            $rss = fetch_feed($url_rss);

            if (!is_wp_error($rss)) { // Checks that the object is created correctly

                // Figure out how many total items there are, but limit it to $max_rss.
                $maxitems = $rss->get_item_quantity((int)$max_rss);

                // Build an array of all the items, starting with element 0 (first element).
                $rss_items = $rss->get_items(0, $maxitems);

            }
            $number = 1;

            // try to do array push when the date it is less than today (date and hour)
            foreach ($rss_items as $item) {
                $date_to_public = $item->data['date']['parsed'];
                if ($date_to_public <= $date_now){
                    $item->data['postTitle'] = get_the_title($id_post);
                    array_push($array_to_publish, $item);
                }
            }

        }

        // Function to order the array by Date and Hour
        function date_sort($a, $b) {
            $a = $a->data['date']['parsed'];
            $b = $b->data['date']['parsed'];
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        }
        usort($array_to_publish, "date_sort");

        $select_array_publish = array_slice($array_to_publish, 0, 15);

        foreach ($select_array_publish as $orderItem) {
            $date_format = date("Y-m-d H:i", $orderItem->data['date']['parsed']);
            $html .= '<div class="rss-ads content-rss"><p>';
            $html .= '<strong>' . $orderItem->data['postTitle'] . '.</strong> ';
            $html .= '<a href="'.$orderItem->get_link().'" target="_blank">' . $orderItem->get_title() . '</a> ';
            $html .= ' '.date("Y-m-d H:i",strtotime(''.$date_format.' + 2 hours')).'';
            $html .= '</p></div>';
        }


    }else{
	    $html = "id_posts_sindicacion not found";
    }

	return do_shortcode(htmlspecialchars_decode($html));
}

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5bab910b408c9',
        'title' => 'sindicacion',
        'fields' => array(
            array(
                'key' => 'field_5bab9113359ca',
                'label' => 'url rss',
                'name' => 'url_rss',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5bab913b359cb',
                'label' => 'numero post',
                'name' => 'numero_post',
                'type' => 'number',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'sindicacion',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

endif;

?>