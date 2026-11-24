<?php
/**
 * Plugin Name:       Pidecita Prescripcion Reservas
 * Plugin URI:        https://pidecita.com
 * Description:       Módulo prescripción reservas para pidecita
 * Version:           0.1.0
 * Author:            Vocento
 * Domain Path:       /languages
 * Text Domain:       pidecita_prescripcion_reserva
 */

if(is_admin()) {
    require 'admin/post.php';
} else {
    add_filter( 'get_post_metadata', 'add_dynamic_post_meta',10,4);
}

function add_dynamic_post_meta( $value, $post_id, $meta_key, $single )
{
    add_action('get_footer', 'include_in_footer');
}

function include_in_footer() {
    $post = get_post(get_the_ID());
    if ($post) {
        if (get_post_meta( get_the_ID(), 'pidecita-prescripcion-reserva_enabled', true ) == 1) {
			wp_enqueue_style( 'pidecita-prescripcion-reserva-css', plugins_url('public/css/style.css', __FILE__));
			wp_enqueue_script( 'pidecita-prescripcion-reserva-js', plugins_url( 'public/js/script.js' , __FILE__ ), array('jquery'));
			wp_enqueue_style('slick-css', plugins_url( 'public/libs/slick/slick.css' , __FILE__ ));
			wp_enqueue_style( 'slick-theme-css', plugins_url( 'public/libs/slick/slick-theme.css', __FILE__ ));
			wp_enqueue_script('slick-js', plugins_url( 'public/libs/slick/slick.min.js' , __FILE__ ), array('jquery'));
			$categoryPost = get_post_meta( get_the_ID(), 'pidecita-prescripcion-reserva_category', true );
            $category = (!empty($categoryPost)) && ($categoryPost !='Categoría') ? $categoryPost : 'all';
			$subCategoryPost = get_post_meta( get_the_ID(), 'pidecita-prescripcion-reserva_subcategory', true );
			if ($categoryPost === $subCategoryPost) {
				$subCategoryPost = '0';
			}
            $subCategory = (!empty($subCategoryPost)) && ($subCategoryPost !='Subcategoría') ? $subCategoryPost : 'all';
            $cityPost = get_post_meta( get_the_ID(), 'pidecita-prescripcion-reserva_city', true );
            $city =  (!empty($cityPost)) && ($cityPost !='Ciudad') ? $cityPost : 'all';
            $hash ='#vso=pidecitablog&vca=merchant-list-blog&vli='.$post->post_name.'&vmc=';
            $viewMore = 'https://www.pidecita.com';
            if ($category !== 'all') {
                $viewMore .= '/tipo-' . $category;
            }
            if ($subCategory !== 'all') {
                $viewMore .= '/para-' . $subCategory;
            }
            if ($city !== 'all') {
                $viewMore .= '/en-' . $city;
            }
            $viewMore.=$hash.'show-more';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://www.pidecita.com/wrapper/api/pwa?callback=merchants/' . $category . '/' .  $subCategory . '/' . $city . '/all/all/all/all/all/1');
            $result = curl_exec($ch);
            curl_close($ch);
            $obj = json_decode($result);
            $merchants = [];
            $title = pidecitaPrescripcionGetTitle( get_the_ID());
			$viewMoreMobile = false;
            if ($obj && $obj->response) {
                foreach ($obj->response as $key => $merchant) {
                    if ($key < 4) {
                        $merchants[] = [
                            'url' => $merchant->url_merchant . $hash . $merchant->merchant_title,
                            'title' => $merchant->title,
                            'image' => $merchant->image,
                            'address' => implode(' ', array_filter([$merchant->address, $merchant->city, $merchant->zip, $merchant->province]))
                        ];
                    }
                    if ($key > 4) {
                    	$viewMoreMobile = true;
					}
                }
            }
            include_once 'public/index.php';
        }
    }
}

function pidecitaPrescripcionGetTitle($post_id) {
    $title = get_post_meta( $post_id, 'pidecita-prescripcion-reserva_title', true );

    if (empty($title)) {
        $baseTitulo = 'No te pierdas los mejores tratamientos';
        $baseCategoria = '';
        $baseCiudad = ' cerca de ti';
		$subCatName = get_post_meta( $post_id, 'pidecita-prescripcion-reserva_subcategory_name', true );
		$cityName = get_post_meta( $post_id, 'pidecita-prescripcion-reserva_city_name', true );
		$subCatName = explode(',', $subCatName);
        
        if (get_post_meta( $post_id, 'pidecita-prescripcion-reserva_city_name', true ) !== '') {
            $baseCiudad = ' en ' . $cityName;
        }

        if (get_post_meta( $post_id, 'pidecita-prescripcion-reserva_subcategory_name', true ) !== '') {
            $baseCategoria = ' de ' . (isset($subCatName[1]) ? $subCatName[1] : $subCatName[0]);
        }
        $title = $baseTitulo . $baseCategoria . $baseCiudad;
    }

    return $title;
}
