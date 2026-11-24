<?php

use ExportacionContenido as GlobalExportacionContenido;
require_once( ABSPATH . 'wp-admin/includes/export.php' );

class ExportacionContenido
{

    static public function exportData ($autor = null, $category = null, $desde = null, $hasta = null, $defaultcategoryfinal = null, $tiponoticia = 'ne')
    {
        $args = [];
        $args['content'] = 'post';
        $args['status'] = 'publish';

        // if($autor != '0'){
        //     $args['author'] = intval($autor);
        // }
        if($category != '0'){
            $args['category'] = intval($category);
        }
        if( $desde != null || $desde != "" || $hasta != null || $hasta != "" ){
            $args['start_date'] = $desde;
            $args['end_date'] = $hasta;
        }

        $defaultcategoryfinal = explode("|", $defaultcategoryfinal);

        $categoriaInicial = get_category(intval($category));
        ExportacionContenido::exportDataFile($args, $categoriaInicial, $defaultcategoryfinal, intval($autor), $tiponoticia);


    }

    static public function getPostsById($ids)
    {
        global $wpdb; 
        $table_prefix = $wpdb->prefix . "posts";   
        // $result = $wpdb->get_results( "SELECT * FROM wp_usermeta WHERE meta_key = 'points' AND user_id = '1'");
        // print_r($result);
    }

    static public function getPostMetaById()
    {
        // global $wpdb;    
        // $result = $wpdb->get_results( "SELECT * FROM wp_usermeta WHERE meta_key = 'points' AND user_id = '1'");
        // print_r($result);
    }

    static public function getPostsIdsByCategoryId ($idcategory)
    {
        $args = array( 'posts_per_page' => -1, 'category' => intval($idcategory) );
        $myposts = get_posts( $args );
        $categoriesId = [];
        foreach($myposts as $post)
        {
            array_push($categoriesId, $post->ID);
        }
        return $categoriesId;
    }

    static public function exportDataFile($args = array(), $categoriaInicial, $defaultcategoryfinal, $idAutor, $tiponoticia)
    {
        ob_start();

        switch_to_blog( get_current_blog_id() );
        export_wp($args);
        $xml = ob_get_clean();
        $xml = iconv('utf-8', 'utf-8//IGNORE', $xml);
        $xml = preg_replace('/[^\\x{0009}\\x{000a}\\x{000d}\\x{0020}-\\x{D7FF}\\x{E000}-\\x{FFFD}]+/u', '', $xml);

        // $xml = str_replace('/wp-content/uploads/sites/4/', 'https://www.abc.es/contentfactory/post/wp-content/uploads/sites/4/', $xml);

        $xml = str_replace('</item>', '<wp:postmeta><wp:meta_key><![CDATA[oldcontent]]></wp:meta_key><wp:meta_value><![CDATA[1]]></wp:meta_value></wp:postmeta></item>', $xml);
        
        $xml_dir = __DIR__.'/sample_data.xml';

        $re = '/image_url="(.*?)"/m';
        preg_match_all($re, $xml, $matches, PREG_SET_ORDER, 0);
        foreach( $matches as $match){
            $replaceString = $match[0];
            $url = wp_get_attachment_url(intval($match[1]));
            $url = str_replace("sites/4/","", $url);
            $xml = str_replace($replaceString, 'image_url="'.$url.'"', $xml);
        }

        restore_current_blog();

        $xml = str_replace('</item>', '<wp:postmeta><wp:meta_key><![CDATA[oldcontent]]></wp:meta_key><wp:meta_value><![CDATA[1]]></wp:meta_value></wp:postmeta></item>', $xml);
        file_put_contents($xml_dir, $xml);
    
    }

    static public function replaceHtmlTags ($xml){
        $xml = str_replace('<h4>','<h4 class="voc-most-readed__content-title">', $xml);
        $xml = str_replace('<ul>','<ul class="voc-most-readed__content-list">', $xml);
        $xml = str_replace('<li>','<li class="voc-most-readed__content-item">', $xml);
        $xml = str_replace('<span style="text-decoration: underline;color: #ff0000">', '<p class="voc-paragraph">', $xml);
        $xml = str_replace('<p>', '<p class="voc-paragraph">', $xml);
        $xml = str_replace('/sites/3/','/sites/5/', $xml);
        $xml = str_replace('https://www.xlsemanal.com', '', $xml);
        return $xml;
    }

    
    static public function changeAuthorID ($idAutor){
        switch($idAutor){
            case 13:
                return 63;
                break;
            case 22:
                return 69;
                break;
            case 42:
                return 64;
                break;
            case 17:
                return 65;
                break;
            case 23:
                return 67;
                break;
            default:
                return 0;
        }
    }

    static public function changeAuthorAntetitle ($idAutor){
        switch($idAutor){
            case 13:
                return "antetitulo]]></wp:meta_key>
                <wp:meta_value><![CDATA[PATENTE DE CORSO]]>";
                break;
            case 22:
                return "antetitulo]]></wp:meta_key>
                <wp:meta_value><![CDATA[REINOS DE HUMO]]>";
                break;
            case 42:
                return "antetitulo]]></wp:meta_key>
                <wp:meta_value><![CDATA[MI HERMOSA LAVANDERÍA]]>";
                break;
            case 17:
                return "antetitulo]]></wp:meta_key>
                <wp:meta_value><![CDATA[ANIMALES DE COMPAÑÍA]]>";
                break;
            case 23:
                return "antetitulo]]></wp:meta_key>
                <wp:meta_value><![CDATA[EL BLOC DEL CARTERO]]>";
                break;
            default:
                return 0;
        }
    }

    static public function exportData22222 ($url) {
        header("Content-type: application/x-msdownload",true,200);
            header("Content-Disposition: attachment; filename=data.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo 'data';
            exit();
    }

    static public function getAllCategories()
    {
        global $wpdb;

        $args = array(
            'orderby' => 'name',
            'parent' => 0
            );
          $categories = get_categories( $args );
          $first = true;
            foreach ($categories as $category) {
              if ( $first )
              {
                  echo '<li class="title" style="border-top: 0px;"><a href="acatalog/abovegroundpools.html">'.$category->cat_name.'</a>';
                  $first = false;
              }
              else
              {
                  echo '<li class="title"><a href="acatalog/abovegroundpools.html">'.$category->cat_name.'</a>';
              }
              $theid = $category->term_id;
              $children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent=$theid" );
                  $no_children = count($children);
              if ($no_children > 0) {
                  echo "<ul>";
                  $args2 = array(
                   'orderby' => 'name',
                   'parent' => 2
                   );
                  $args2["parent"]=$category->term_id;
                  $categories2 = get_categories( $args2 );
                   foreach ($categories2 as $category2) {
          
                      echo '<li><a href="acatalog/Inflatable-Hot-Tubs.html">'.$category2->cat_name.'</a></li>';
          
                  }
                  echo '</ul>';
              } else {
              echo '</li>';
              }
          
            }
    }

}


if (is_admin()) {
    $exportacionContenido = new ExportacionContenido();
}
