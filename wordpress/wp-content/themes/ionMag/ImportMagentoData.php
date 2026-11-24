<?php

class ImportMagentoData
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'menuConfigurationImportFileData' ));
        // add_action('admin_enqueue_scripts', array( $this, 'wpdocs_enqueue_custom_admin_style'));
    }

    
    public function menuConfigurationImportFileData()
    {
        add_menu_page(
            'import_data_configuration',
            'Import Data Magento',
            'manage_categories',
            'import_data_configuration',
            array( $this, 'adminPageImportData' ),
            'dashicons-admin-home',
            5
        );
    }

    public function adminPageImportData()
    {
        if(isset($_POST['submitupload'])){
            $json_decode = json_decode(file_get_contents($_FILES['importfile']['tmp_name']), true);
            foreach ($json_decode as $value) {
                ImportMagentoData::uploadDataToPost($value);
            }
         }


        echo '<h1>Importación Datos desde Magento File</h1>';
        echo '
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="file" name="importfile" id="importfile">
                <div class="edit-tag-actions">
                    <input type="submit" name="submitupload" class="button button-primary" value="Upload JSON file"><span class="spinner"></span>
                </div>
            </form>
        ';
    }

    private static function uploadDataToPost($data)
    {
        
         if (!post_exists(wp_strip_all_tags( $data['screen_name'] ))) {
            $my_post = array(
                'post_title'        => wp_strip_all_tags( $data['screen_name'] ),
                'post_name'         => $data["uri"],
                'post_content'      => $data['description'],
                'post_status'       => 'draft',
                'post_author'       => 1,
                'post_category'     => array( 1010 ),
                'meta_input'   => array(
                    "merchant_id" => $data["merchant_id"],
                    "image_1" => $data["image_1"],
                    "image_2" => $data["image_2"],
                    "image_3" => $data["image_3"],
                    "image_4" => $data["image_4"],
                    "image_5" => $data["image_5"],
                    "image_map" => $data["image_map"],
                    "url" => $data["url"],
                    "uri" => $data["uri"],
                    "screen_name" => $data["screen_name"],
                    "description" => $data["description"],
                    "address" => $data["address"],
                    "city" => $data["city"],
                    "media_rel" => $data["media_rel"],
                    "ofp_member_id" => $data["ofp_member_id"],
                    "bookitit_merchant_id" => $data["bookitit_merchant_id"],
                    "source" => $data["source"],
                    "1" => $data["1"],
                    "importedmagento" => 1
                ),
              );
              $post_id = wp_insert_post( $my_post );
              wp_set_post_terms( $post_id, $data["city"]);
        }else{
            echo '<p>'.wp_strip_all_tags( $data['screen_name'] ) . ' <strong>ya existe.</strong></p>'."\n";
        }

       
    }
    

}

if (is_admin()) {
    $ImportMagentoData = new ImportMagentoData();
}
