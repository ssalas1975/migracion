<?php
function create_destacadoadicionales_shortcode($atts)
{
    wp_enqueue_script('destacadosadicionalespag', '/wp-content/themes/cms-native/addons/js/destacadosadicionalespag.js');
    add_action('wp_ajax_art-pag-destacados-adicionales', 'pag_destacados_adicionales');
    add_action('wp_ajax_nopriv_art-pag-destacados-adicionales', 'pag_destacados_adicionales');
   
    // Attributes
    $atts = shortcode_atts(
        array(
            'selectedtagadicionales' => '',
            'clase_seccion' => '',
        ),
        $atts,
        'destacado_adicionales'
    );
    // Attributes in var
    $tag = $atts['selectedtagadicionales'];
    $class_seccion = $atts['clase_seccion'];

    global $post;

    $html = "";
    $categoria = wp_get_post_categories($post->ID);
    $per_page = 3;

    $related = get_posts(
        array(
            'posts_per_page'  => $per_page,
            'update_post_meta_cache' => false,
            'cache_results'  => false,
            'post_status ' => 'publish',
          'no_found_rows' => true,
          'update_post_term_cache' => false,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $tag,
                ),
            ),
        )
    );

    $count = new WP_Query(
        array(
          'post_type'         => 'post',
          'post_status '      => 'publish',
          'posts_per_page'    => -1,
          'update_post_meta_cache' => false,
          'cache_results'  => false,
          'no_found_rows' => true,
          'update_post_term_cache' => false,
          'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $tag,
            ),
        ),
      )
    );

    $total_page = ceil($count->post_count / $per_page);

    $end_page = ($total_page > 5) ? 5 : $total_page;

    $html .='
    <!-- el div con la clase vacia es el div padre personalizable en el addonn -->
    <div class="'.$class_seccion.'">
        <div class="destacados-adicionales">
            <div class="bloque-a">
    ';
    if ($related) {
        foreach ($related as $related_post) {
            $html .= generate_html_related_adicionales($related_post, $tag, $class_seccion, '');
        }

        $html .='
            </div>
            <ul class="paginacion 11">
    ';
        // if ($end_page >=5) {
        //     for ($i=0; $i < $end_page; $i++) {
        //         if ($i==0) {
        //             $html .= '<li class="active"><a data-page="'.($i + 1).'" data-slug="'.$tag.'" data-postid='.$post->ID.' >'.(1).'</a></li>';
        //         } else {
        //             $html .= '<li ><a data-page="'.($i + 1).'" data-slug="'.$tag.'" data-postid='.$post->ID.'>'.($i + 1).'</a></li>';
        //         }
        //     }
        // }
    }
    if ($total_page > 1) {
        for ($i=0; $i < $end_page; $i++) {
            if ($i==0) {
                $html .= '<li class="active"><a data-page="'.($i + 1).'" data-slug="'.$tag.'" data-postid='.$post->ID.' >'.(1).'</a></li>';
            } else {
                $html .= '<li ><a data-page="'.($i + 1).'" data-slug="'.$tag.'" data-postid='.$post->ID.'>'.($i + 1).'</a></li>';
            }
        }
        $html .='<li><a data-page="2" data-slug="'.$tag.'" data-postid='.$post->ID.'>></a></li>';
    }
    $html .= '
            </ul>
        </div>
    </div>';

    wp_reset_postdata();

    return $html;
}
add_shortcode('destacado_adicionales', 'create_destacadoadicionales_shortcode');



// Create destacado-abc element for Visual Composer
add_action('vc_before_init', 'destacadoadicionales_integrateWithVC');
function destacadoadicionales_integrateWithVC()
{
    $arrayTags = [];
    if(is_admin()){
        $tags = get_tags();
        foreach ($tags as $tag) {
            $arrayTags[$tag->name] = $tag->slug;
        }
    }

    vc_map(array(
        'name' => __('Destacados Adicionales', 'textdomain'),
        'base' => 'destacado_adicionales',
        'show_settings_on_create' => true,
        'category' => __('Content', 'textdomain'),
        'params' => array(
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtagadicionales",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Clase General', 'textdomain'),
                'param_name' => 'clase_seccion',
            ),
        )
    ));
}

function pag_destacados_adicionales()
{
    global $wpdb;
    $html = '';
    if (isset($_POST['page'])) {
        $slug = $_POST['slug'];
        $page = sanitize_text_field($_POST['page']);
        $ori_page=intval($page);
        $css_class = sanitize_text_field($_POST['class']);
        $post_id = ($_POST['postid']);
        $page-=1;
        $per_page = 3;

        if (!isset($_POST['id'])) {
            $categoria = intval($_POST['cat']);
        } else {
            $categoria = wp_get_post_categories($_POST['id']);
            $categoria =$categoria[0];
        }
        //Comienzo de los posts
        $start = ($page) * $per_page;

        //Post a mostrar sin incluir el actual
        $all_blog_posts = new WP_Query(
            array(
              'post_type'         => 'post',
              'post_status '      => 'publish',
              'orderby'           => 'post_date',
              'order'             => 'DESC',
              'update_post_meta_cache' => false,
              'cache_results'  => false,
          'no_found_rows' => true,
          'update_post_term_cache' => false,
              'posts_per_page'    => $per_page,
              'offset'            => $start,
              'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $slug,
                ),
            ),
          )
        );

        //Numero de posts
        $count = new WP_Query(
            array(
              'post_type'         => 'post',
              'post_status '      => 'publish',
              'posts_per_page'    => -1,
              'update_post_meta_cache' => false,
              'cache_results'  => false,
                'no_found_rows' => true,
                'update_post_term_cache' => false,
              'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $slug,
                ),
            ),
          )
        );

        //total de paginas
        $total_page = ceil($count->post_count / $per_page);

        //logica de paginacion
        if ($total_page > 5) {
            $start_page = $start - 3;
            if ($start_page) {
                if ($start_page <  0) {
                    $start_page = 0;
                }
            }
            $end_page = $start_page + 5;
            if ($end_page > $total_page) {
                $end_page = $total_page;
            }
        } else {
            $start_page=  0;
            $end_page = $total_page;
        }

        if ($end_page - $start_page <= 5) {
            $start_page = $end_page - 4;
            if ($start_page < 0) {
                $start_page = 1;
            }
        }

        // Pinto todos los posts
        $html .='<div class="bloque-a">';
        foreach ($all_blog_posts->posts as $post) {
            $html .= generate_html_related_adicionales($post, $slug, $css_class, '/contentfactory/post');
        }
        $html .='</div>
        <!-- '.$ori_page .' - '. $start_page .' - '. $end_page .' - '. $total_page .' -->

        <ul class="paginacion 11">';
        //Pinto paginacion
        $html .= generate_html_pagination_destacados_adicionales($page, $ori_page, $start_page, $end_page, $total_page, $categoria, $all_blog_posts, $post_id, $slug);

        $html .= '
                </ul>
            </div>
        </div>';
        echo $html;
    }
}


function generate_html_pagination_destacados_adicionales($page, $ori_page, $start_page, $end_page, $total_page, $categoria, $all_blog_posts, $post_id, $slug)
{
    $html = '';
    $previous_page = ($ori_page - 1 > 0) ? $ori_page - 1 : 0;
    $next_page = ($ori_page + 1 <= $total_page) ? $ori_page + 1 : $total_page;
    $html .= '<ul class="paginacion 11">';

    if ($previous_page!=0) {
        $html .= '<li><a data-page="'.$previous_page.'" data-slug="'.$slug.'" data-cat="'.$categoria.'" data-postid='.$post_id.' data-id='.$all_blog_posts->post->ID.' ><</a></li>';
    }

    if ($end_page==$total_page) {
        $start_page--;
        if($start_page == 0){
            $start_page = 1;
        }
        for ($i = $start_page; $i < $end_page; $i++) {
            $active = ($ori_page==($i+1)) ? " class=active" : "";
            $html .= '<li'.$active.'><a data-page="'.($i + 1).'" data-slug="'.$slug.'" data-cat="'.$categoria.'" data-postid='.$post_id.' data-id='.$all_blog_posts->post->ID.' >'.($i+1).'</a></li>';
        }
    } else {
        if($start_page == 0){
            $start_page = 1;
        }
        for ($i = $start_page; $i <= $end_page; $i++) {
            $active = ($start_page==$i) ? " class=active" : "";
            $html .= '<li'.$active.'><a data-page="'.($i).'" data-slug="'.$slug.'" data-cat="'.$categoria.'" data-postid='.$post_id.' data-id='.$all_blog_posts->post->ID.' >'.($i).'</a></li>';
        }
    }
    if ($ori_page!=$next_page) {
        $html .= ' <li><a data-page="'.$next_page.'" data-slug="'.$slug.'" data-postid='.$post_id.' data-cat="'.$categoria.'">></a></li>';
    }
    $html .= '</ul>';
    return $html;
}


function generate_html_related_adicionales($post, $tag, $class_seccion, $path)
{
    setup_postdata($post);
    $tag = get_term_by('slug', $tag, 'post_tag');
    $idImagenAutor = get_post_meta($post->ID, 'image_url', true);
    $idImagenCabecera = '/contentfactory/post'.get_post_meta($post->ID, '_webdados_fb_open_graph_specific_image', true);
    $autor = get_post_meta($post->ID, 'autor', true);
    $texto = get_post_meta($post->ID, 'texto', true);
    $titulo1 = get_post_meta($post->ID, 'titulo1', true);
    $fecha = get_the_date('j F, Y', $post) .' - '. get_the_date('G:i', $post);
    $permalink = get_permalink($post->ID);
    $urlImg ='';
    if ($idImagenAutor == null || $idImagenAutor == '') {
        $urlImg = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg = $path.wp_get_attachment_image_src(intval($idImagenAutor), 'full')[0];
    }


    $html ='
    <article class="modulo-noticia">
        <figure> <a href="'.$permalink.'">
        <!-- esta imagen la trae del addon cabecero -->
        <img src="'.$idImagenCabecera.'"> </a> </figure>
        <div class="content-a"> <a href="'.$permalink.'">
        <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
        <p class="nombrecategoria">'.$tag->name.'</p>
        <!-- h1 es el titulo del addon cabecero -->
        <h1>'.$titulo1.'</h1>
        <!-- este p es la entradilla del addon cabecero -->
        <p>'.$texto.'</p>           
        <!-- este info reune la imagen del autor, el autor y la fecha del post -->
        <div class="info">
            <address>
            <img class="fffff" src="'.$urlImg.'">'.$autor.'
            </address>
            <time>'.$fecha.'</time>
        </div>
        </a> </div>
    </article>
    ';
    wp_reset_postdata();
    return $html;
}
