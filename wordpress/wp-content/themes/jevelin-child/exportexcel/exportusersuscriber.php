<?php
define( 'WP_USE_THEMES', false );

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
global $wpdb;

$blogusers = get_users( 'blog_id=12&role=subscriber' );


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
$objPHPExcel->setActiveSheetIndex(0);
// Initialise the Excel row number
$rowCount = 0;
// Sheet cells
$cell_definition = array(
        'A' => 'Nombre de Familia',
		'B' => 'Email de contacto',
        'C' => 'Número de Adultos',
		'D' => 'Número de Niños',
		'E' => 'Número de Mascotas',
		'F' => 'Como nos ha conocido',
		'G' => 'CONSENTIMIENTO'
);
// Build headers
foreach( $cell_definition as $column => $value )
	$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value );
// Build cells

foreach ( $blogusers as $user )
{
		$cell = $rowCount + 2;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, $user->user_login);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cell, $user->user_email);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cell, get_user_meta( $user->ID, 'numadultos', true ));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cell, get_user_meta( $user->ID, 'numchild', true ));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$cell, get_user_meta( $user->ID, 'nummascota', true ));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$cell, get_user_meta( $user->ID, 'comonosconocio', true ));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$cell, get_user_meta( $user->ID, 'acepto', true ));
		$rowCount++;
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="asistentes_evento.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;