<?php
define( 'WP_USE_THEMES', false );


$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
global $wpdb;

$query = "SELECT * FROM $wpdb->posts where post_type = 'evento'";
$error = "No hay registros que exportar.";
$result = $wpdb->get_results( $query, ARRAY_A ) or wp_die( $error );


$objPHPExcel = new PHPExcel(); 


$objPHPExcel->getProperties()
        ->setCreator("user")
        ->setLastModifiedBy("user")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
// Set the active Excel worksheet to sheet 0

$objPHPExcel->setActiveSheetIndex(0)->setTitle('NoBloggers'); 
// Initialise the Excel row number
$rowCount = 0; 
// Sheet cells
$cell_definition = array(
        'A' => 'CLASE',
    'B' => 'Nombre',
        'C' => 'Apellidos',
    'D' => 'Email',
        'E' => 'Como nos ha conocido'
);
// Build headers
foreach( $cell_definition as $column => $value )
    $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
// Build cells
        
foreach ( $result as $res )
{

    //$idpost = $res['ID'];
    //$query2 = "SELECT * FROM $wpdb->usermeta where meta_key = 'idevento' ";
    //$error = "No hay registros que exportar2.";
    //$userResults = $wpdb->get_results( $query2, ARRAY_A ) or wp_die( $error );

    $idpost = $res['ID'];
    $query2 = "select * from wp_usermeta where meta_key = 'url_autor' and meta_value = '' or meta_value is null";
    $error = "No hay registros que exportar2.";
    $userResults = $wpdb->get_results( $query2, ARRAY_A );
    
    foreach ($userResults as $userRes )
    {
    $cell = $rowCount + 2;
    //$idevents = maybe_unserialize( $userRes['meta_value']);
    $idevents = get_user_meta( $userRes['user_id'], 'idevento', true );
    $idevents = maybe_unserialize( $idevents );
        
        $evento = get_post_meta($idpost,'data_event');
        $nombreEvento = $evento[0]['nombre'];
        $nombreEvento = str_replace(" ", "_", $nombreEvento);
        
        $user = get_user_by( 'ID', $userRes['user_id'] );
        $comonosconocio = get_user_meta( $userRes['user_id'], 'comonosconocio', true );


         
        if(in_array($res['ID'], $idevents)){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, $nombreEvento);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$cell, get_user_meta( $userRes['user_id'], 'first_name', true ));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$cell, get_user_meta( $userRes['user_id'], 'last_name', true ));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$cell, $user->data->user_email);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$cell, $comonosconocio[0]);
            $rowCount++; 
        }
    
    }
    
}

$objWorkSheet = $objPHPExcel->createSheet(1)->setTitle('Bloggers');
$rowCount = 0; 
// Sheet cells
$cell_definition = array(
    'A' => 'CLASE',
    'B' => 'Nombre',
    'C' => 'Apellidos',
    'D' => 'Email',
    'E' => 'Como nos ha conocido',
    'F' => 'Url Blog'
);
// Build headers
foreach( $cell_definition as $column => $value )
    $objWorkSheet->setCellValue( "{$column}1", $value ); 
// Build cells


foreach ( $result as $res )
{

    //$idpost = $res['ID'];
    //$query2 = "SELECT * FROM $wpdb->usermeta where meta_key = 'idevento' ";
    //$error = "No hay registros que exportar2.";
    //$userResults = $wpdb->get_results( $query2, ARRAY_A ) or wp_die( $error );

    $idpost = $res['ID'];
    $query2 = "select * from local_wordpress.wp_usermeta where meta_key = 'url_autor' and meta_value not null";
    $error = "No hay registros que exportar2.";
    $userResults = $wpdb->get_results( $query2, ARRAY_A );

    
    foreach ($userResults as $userRes )
    {
    $cell = $rowCount + 2;
    //$idevents = maybe_unserialize( $userRes['meta_value']);
    $idevents = get_user_meta( $userRes['user_id'], 'idevento', true );
    $idevents = maybe_unserialize( $idevents );
        
        $evento = get_post_meta($idpost,'data_event');
        $nombreEvento = $evento[0]['nombre'];
        $nombreEvento = str_replace(" ", "_", $nombreEvento);
        
        $user = get_user_by( 'ID', $userRes['user_id'] );
        $comonosconocio = get_user_meta( $userRes['user_id'], 'comonosconocio', true );


         
        if(in_array($res['ID'], $idevents)){
            $objWorkSheet->setCellValue('A'.$cell, $nombreEvento);
            $objWorkSheet->setCellValue('B'.$cell, get_user_meta( $userRes['user_id'], 'first_name', true ));
            $objWorkSheet->setCellValue('C'.$cell, get_user_meta( $userRes['user_id'], 'last_name', true ));
            $objWorkSheet->setCellValue('D'.$cell, $user->data->user_email);
            $objWorkSheet->setCellValue('E'.$cell, $comonosconocio[0]);
            $objWorkSheet->setCellValue('F'.$cell, get_user_meta( $userRes['user_id'], 'url_autor', true ));
            $rowCount++; 
        }
    
    }
    
}



$objWorkSheet = $objPHPExcel->createSheet(2)->setTitle('UsuariosTotales');
$rowCount = 0; 
$blogusers = get_users( 'blog_id=3&role=subscriber' );
// Sheet cells
$cell_definition = array(
    'A' => 'Nombre',
    'B' => 'Apellidos',
    'C' => 'Email',
    'D' => 'Como nos ha conocido',
);
// Build headers
foreach( $cell_definition as $column => $value )
    $objWorkSheet->setCellValue( "{$column}1", $value ); 
// Build cells


foreach ( $blogusers as $res )
{

    //$idpost = $res['ID'];
    //$query2 = "SELECT * FROM $wpdb->usermeta where meta_key = 'idevento' ";
    //$error = "No hay registros que exportar2.";
    //$userResults = $wpdb->get_results( $query2, ARRAY_A ) or wp_die( $error );

    /*$idpost = $res->ID;
    $query2 = "select * from local_wordpress.wp_usermeta";
    $error = "No hay registros que exportar2.";
    $userResults = $wpdb->get_results( $query2, ARRAY_A );*/

    $cell = $rowCount + 2;
    
    $user = get_user_by( 'ID', $res->ID );
    $comonosconocio = get_user_meta( $res->ID, 'comonosconocio', true );

    $objWorkSheet->setCellValue('A'.$cell, get_user_meta( $res->ID, 'first_name', true ));
    $objWorkSheet->setCellValue('B'.$cell, get_user_meta( $res->ID, 'last_name', true ));
    $objWorkSheet->setCellValue('C'.$cell, $res->user_email);
    $objWorkSheet->setCellValue('D'.$cell, $comonosconocio[0]);
    $rowCount++; 
    
}
        

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="inscripciones.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;