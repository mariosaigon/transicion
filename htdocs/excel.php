     <html>
	 <head> 
	 </head>
	 <body>
	
<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
     echo "Hola, prueba de crear un excel y pushearlo al navegador";
	 echo "<br>";
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("IAIP")
							 ->setLastModifiedBy("Oficial de Información X")
							 ->setTitle("Reporte ente obligado x")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 iaip reserva")
							 ->setCategory("Test result file");


$cont=0;

$columnaA="A";
$filaColA=1;
$data="conejo";
$col=0;
$row=1;
for($cont=0;$cont<10;$cont++)
{
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
}
//https://stackoverflow.com/questions/27764204/how-to-do-the-phpexcel-outside-border
$BStyle = array(
  'borders' => array(
    'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:A15')->applyFromArray($BStyle);
//Redirect output to a client’s web browser (Excel2007)
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="pene.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
header('Location: ../out/out.ViewDocument.php?documentid=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;

?>
</body>
 </html>