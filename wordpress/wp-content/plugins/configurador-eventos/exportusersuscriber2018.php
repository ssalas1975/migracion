<?php
define( 'WP_USE_THEMES', false );


$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
global $wpdb;

$args = array(
	'role' => 'subscriber',
);
$users = get_users( $args );


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
    'A' => 'Nombre',
    'B' => 'Apellidos',
    'C' => 'Email',
    'D' => 'Como nos ha conocido'
);
// Build headers
foreach( $cell_definition as $column => $value )
    $objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
// Build cells
        
foreach ($users as $user )
{
	$cell = $rowCount + 2;
	$blogger = get_user_meta( $user->ID, 'url_autor', true);
	if( $blogger == null || $blogger == "" )
	{
		$como = get_user_meta($user->ID, 'comonosconocio', true);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, get_user_meta($user->ID, 'first_name', true));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell, get_user_meta($user->ID, 'last_name', true));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell, $user->user_email);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell, $como[0]);
	}
	$rowCount++; 
}


$objWorkSheet = $objPHPExcel->createSheet(1)->setTitle('Bloggers');
$rowCount = 0; 
// Sheet cells
$cell_definition = array(
    'A' => 'Nombre',
    'B' => 'Apellidos',
    'C' => 'Email',
    'D' => 'Como nos ha conocido',
    'E' => 'Url Blog'
);
// Build headers
foreach( $cell_definition as $column => $value )
    $objWorkSheet->setCellValue( "{$column}1", $value ); 
// Build cells


foreach ( $users as $user )
{
	$cell = $rowCount + 2; 
	$blogger = get_user_meta( $user->ID, 'url_autor', true);
	if( $blogger != null || $blogger != "" )
	{
		$como = get_user_meta($user->ID, 'comonosconocio', true);
		$objWorkSheet->setCellValue('A'.$cell, get_user_meta($user->ID, 'first_name', true));
		$objWorkSheet->setCellValue('B'.$cell, get_user_meta($user->ID, 'last_name', true));
		$objWorkSheet->setCellValue('C'.$cell, $user->user_email);
		$objWorkSheet->setCellValue('D'.$cell, $como[0]);
		$objWorkSheet->setCellValue('E'.$cell, get_user_meta($user->ID, 'url_autor', true));
		$rowCount++; 
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
	
foreach ( $users as $user )
{
	$como = get_user_meta($user->ID, 'comonosconocio', true);
    $cell = $rowCount + 2;
    $objWorkSheet->setCellValue('A'.$cell, get_user_meta($user->ID, 'first_name', true));
    $objWorkSheet->setCellValue('B'.$cell, get_user_meta($user->ID, 'last_name', true));
    $objWorkSheet->setCellValue('C'.$cell, $user->user_email);
    $objWorkSheet->setCellValue('D'.$cell, $como[0]);
    $rowCount++; 
    
}
        

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="inscripciones.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;