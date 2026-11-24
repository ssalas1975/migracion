<?php

use function Clue\StreamFilter\append;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class PostWithoutCanonical extends WP_List_Table {

    function __construct(){
        global $status, $page;                
    
        parent::__construct( array(
            'singular'  => 'notification',  
            'plural'    => 'notifications',   
            'ajax'      => false      
        ) );        
    }
    
    protected function get_views() { 
        $status_links = array(
            "all"       => __("<a href='#'>All</a>",'my-plugin-slug'),
            "published" => __("<a href='#'>Published</a>",'my-plugin-slug'),
            "trashed"   => __("<a href='#'>Trashed</a>",'my-plugin-slug')
        );
        return $status_links;
    }
    
    // function column_default($item, $column_name){
    //     switch( $column_name ) { 
    //         case 'booktitle':
    //         case 'ID':
    //         case 'Title':
    //           return $item[ $column_name ];
    //         default:
    //           return print_r( $item, true ) ; // Mostramos todo el arreglo para resolver problemas
    //       }
    // }

    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
          case 'booktitle':
          case 'author':
          case 'isbn':
            return $item[ $column_name ];
          default:
            return print_r( $item, true ) ; // Mostramos todo el arreglo para resolver problemas
        }
      }
    

    // function get_columns(){
    //     $columns = array (
    //         'ID' => 'ID', 
    //         'Title' => 'Title' ); 
    
    //   return $columns; 
    // }

    function get_columns () { 
        $columns = array (
              'booktitle' => 'Titulo', 
              'author' => 'URL', 
              'isbn' => 'ID' ); 
      
        return $columns; 
      }


    public function get_sortable_columns() {
        $sortable_columns = array(
            'ID'    => array('ID',false),
            'Title'  => array('Title',false)
        );
        return $sortable_columns;
    }
    public function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete',
            'email'     => 'Email'
        );
        return $actions;
    }
    
    // public function process_bulk_action() {
    
    //     global $wpdb; 
    //     $notifications_tbl = $wpdb->prefix.'newsletter';
    
    //     if( 'delete'===$this->current_action() ) {
    //         foreach($_POST['notification'] as $single_val){
    //             $wpdb->delete( $notifications_tbl, array( 'id' =>    (int)$single_val ) );             
    //         }
    //         $redirect_url =  get_admin_url( null, 'admin.php?page=subscribers' );
    //         wp_safe_redirect($redirect_url); 
    //         wp_die('Items deleted (or they would be if we had items to delete)!');
    //     } 
    //     if( 'email'===$this->current_action() ) {           
    //             $result_email_ar = implode("-",$_POST['notification']);
    //         $redirect_url =  get_admin_url( null, 'admin.php?page=kvcodes&ids='.$result_email_ar  );
    //         wp_safe_redirect($redirect_url);        
    
    //         wp_die('  ');
    //     }       
    // }
    
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = $per_page * ($current_page-1);

        $database_name = $wpdb->prefix.'posts' ;
        $database_name_postmeta = $wpdb->prefix.'postmeta' ;
        $queryCount = 'select count(*) from '.$database_name.' where ID NOT IN (select post_id from '.$database_name_postmeta.' where meta_key = "canonical-url") AND post_type = "post" AND post_status = "publish"';
        $query = 'select * from '.$database_name.' where ID NOT IN (select post_id from '.$database_name_postmeta.' where meta_key = "canonical-url") AND post_type = "post" AND post_status = "publish" LIMIT '.$per_page.' OFFSET '.$offset;


        $posts =  $wpdb->get_results($query, ARRAY_A );
        $postsCount =  $wpdb->get_results($queryCount, ARRAY_A );
        $total_items = intval($postsCount[0]["count(*)"]);
        // $total_items = count($data);
        
        $data = array();
        foreach($posts as $post){
            $dato = array(
                'booktitle' => $post['post_title'], 
                'author' => 'URL', 
                'isbn' => $post['ID']
            );
            array_push($data, $dato);
        }

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );


            $columns = $this -> get_columns (); 
            $hidden = array(); 
            $sortable = array(); 
            $this->_column_headers = array( $columns ,$hidden , $sortable ); 
            $this->items = $data;

    }



    function column_booktitle( $item ) {
        $actions = array(
                  'edit'      => sprintf('<a href="post.php?post=%s&action=edit">Editar</a>', $item['isbn'] ),
                //   'delete'    => sprintf('<a href="wp-admin/post.php?post=%s&action=edit">Papelera</a>',$item['isbn'] ),
              );
      
        return sprintf('%1$s %2$s', $item['booktitle'], $this->row_actions( $actions ) );
      }



}


    function o_add_menu_items(){
  
        add_menu_page(
            'post_without_canonical',
            'Post sin canonical',
            'manage_options',
            'post_without_canonical',
            'o_render_list_page',
            'dashicons-admin-home',
            5
        );

    } 
    add_action('admin_menu', 'o_add_menu_items');
    
    function o_render_list_page() {
        $postwithoutcanonical = new PostWithoutCanonical();
        $title = __("Post Sin Canonical","my_plugin_slug");
        ?>
        <div class="wrap">
            <h1>
                <?php echo esc_html( $title );?>
                <?php
                ?>
            </h1>
        </div>
        <?php $postwithoutcanonical->views(); ?>
        <form method="post">
        <?php 
    
            $postwithoutcanonical->prepare_items(); 
            $postwithoutcanonical->display();
        ?>
        </form>
    <?php
    }