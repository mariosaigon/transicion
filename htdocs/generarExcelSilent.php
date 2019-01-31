
<?php
/**
	******************** GENERAR INDICE DE INFORMACION RESERVADA DEL ENTE OBLIGADO **********************************
	V1.0 27/09/17
	@author JOSE MARIO LOPEZ LEIVA
	marioleiva2011@gmail.com
	Script que lee de la aplicación de reserva de información y genera un Excel con el índice.
	
**/
/**
-------------------------------------- Parámetros que recibo a través del método POST desde el formulario ubicado en /views/multisis-lte/class.GenerarIndice.php

   -nombreUsuario: nombre del Oficial de Información
   -nombreEnteObligado: nombre del ente
   -fechaGeneracion: timestamp de la hora de creación del índice
   -fotoEnte: foto, si está definida, del logo del ente obligado
   -nombresDocumentos[]: array, conteniendo los nombres de los documentos que se van a reservar
   -unidadesEnte: array, con las unidades administrativas que generaron los documentos
   -numerosReserva: array, con los números de las reservas 
   -tiposReserva: total/parcial
   -autoridadReserva: autoridad que reserva, ejemplo, Dirección de auditoría interna
   -fundamentoLegal: array de arrays, con varios posibles literales del art. 19 LAIP
   -fechaClasificacion: fecha de clasificación de reservas, array de esto
   -fechaDesclasificacion: array de fechas de caducidad de reservas
   -motivoReserva: motivo de la reserva array.

   ----- para el indice de desclasificación

   
**/
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

//Nombre de la carpeta donde se guardan los excel generados:
$carpetaDestino="Excels";
///////////////////////// OBTENGO PARÁMETROS DESDE EL FORMULARIO
//0 Carpeta destino (si es ente o alcaldia)
$carpetaDestino="Excels";
if (isset($_POST["carpetaDestino"])  && strlen($_POST["carpetaDestino"])>0 ) //se envía a través del método POST porque es un formulario
{
	$carpetaDestino=$_POST["carpetaDestino"];
	//echo "creador excel: ".$creadorExcel;
}	
//1 Nombre del ente obligado
$creadorExcel="IAIP"; //valor por defecto
if (isset($_POST["nombreUsuario"])  && strlen($_POST["nombreUsuario"])>0 ) //se envía a través del método POST porque es un formulario
{
	$creadorExcel=$_POST["nombreUsuario"];
	//echo "creador excel: ".$creadorExcel;
}	

//2) nombre del ente obligado
$nombreEnte="Ente obligado";
if (isset($_POST["nombreEnteObligado"])  && strlen($_POST["nombreEnteObligado"])>0 ) //se envía a través del método POST porque es un formulario
{
	$nombreEnte=$_POST["nombreEnteObligado"];
}	  
//3) fecha de generacións
$fechaGeneracion="20XX-XX-XX";
if (isset($_POST["fechaGeneracion"])  && strlen($_POST["fechaGeneracion"])>0 ) //se envía a través del método POST porque es un formulario
{
	$fechaGeneracion=$_POST["fechaGeneracion"];
}	  
//4) Nombres de documentos
$nombresDocumentos=array();
if (isset($_POST["nombresDocumentos"]) ) //se envía a través del método POST porque es un formulario
{
	$nombresDocumentos=$_POST["nombresDocumentos"];
	//print_r($nombresDocumentos);
}	 

$unidadesEnte=array();
if (isset($_POST["unidadesEnte"]) ) //se envía a través del método POST porque es un formulario
{
	$unidadesEnte=$_POST["unidadesEnte"];

}	

$numerosReserva=array();
if (isset($_POST["numerosReserva"]) ) //se envía a través del método POST porque es un formulario
{
	$numerosReserva=$_POST["numerosReserva"];

}	
$tiposReserva=array();
if (isset($_POST["tiposReserva"]) ) //se envía a través del método POST porque es un formulario
{
	$tiposReserva=$_POST["tiposReserva"];

}	
$autoridadReserva=array();
if (isset($_POST["autoridadReserva"]) ) //se envía a través del método POST porque es un formulario
{
	$autoridadReserva=$_POST["autoridadReserva"];

}	
$fundamentoLegal=array();
if (isset($_POST["fundamentoLegal"]) ) //se envía a través del método POST porque es un formulario
{
	$fundamentoLegal=$_POST["fundamentoLegal"];

}	
$fechaClasificacion=array();
if (isset($_POST["fechaClasificacion"]) ) //se envía a través del método POST porque es un formulario
{
	$fechaClasificacion=$_POST["fechaClasificacion"];

}	
$motivoReserva=array();
if (isset($_POST["motivoReserva"]) ) //se envía a través del método POST porque es un formulario
{
	$motivoReserva=$_POST["motivoReserva"];

}	
$fechaDesclasificacion=array();
if (isset($_POST["fechaDesclasificacion"]) ) //se envía a través del método POST porque es un formulario
{
	$fechaDesclasificacion=$_POST["fechaDesclasificacion"];

}	
/* $urlFoto=array();
if (isset($_POST["urlFoto"]) ) //se envía a través del método POST porque es un formulario
{
	$urlFoto=$_POST["urlFoto"];

}	
 */


	 
//////////// CREO EL EXCEL
$objPHPExcel = new PHPExcel();


// Set document properties
$objPHPExcel->getProperties()->setCreator($creadorExcel)
							 ->setLastModifiedBy($creadorExcel)
							 ->setTitle("Indice de información reservada ".$nombreEnte)
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 iaip reserva ".$nombreEnte)
							 ->setCategory("Indice de información reservada");
//METER CABECERAS
$datosOficial="Oficial de Información: ".$creadorExcel;
$ultimaActualizacion="Última actualización: ".$fechaGeneracion;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ÍNDICE DE INFORMACIÓN RESERVADA')
            ->setCellValue('A2', $nombreEnte)
            ->setCellValue('A3', $datosOficial)
            ->setCellValue('A4', $ultimaActualizacion);							 
$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$style1 = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

 $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($style1);

$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
$style2 = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '#4b42f4'),
        'size'  => 17,
        'name'  => 'Candara'
    )
    );

 $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($style2);

$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
$style3 = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '#0c0104'),
        'size'  => 17,
        'name'  => 'Calibri'
    )
    );

 $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($style3);


$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
$style4 = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

 $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray($style4);

 //cabeceras de columnas
 $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', 'Nº')
             ->setCellValue('B5', 'Nº de Declaratoria de Reserva')
            ->setCellValue('C5', 'Documento a reservar (rubro temático)')
            ->setCellValue('D5', 'Unidad administrativa')
            ->setCellValue('E5', 'Autoridad que reserva')	
            ->setCellValue('F5', 'Motivo de la reserva')
             ->setCellValue('G5', 'Fundamento legal (literales Art. 19 LAIP)')	
              ->setCellValue('H5', 'Tipo de clasificación')	
               ->setCellValue('I5', 'Fecha de clasificación')			
                ->setCellValue('J5', 'Fecha de vencimiento de la reserva');						

for($col = 'A'; $col !== 'N'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}
$style5 = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '#0c0104'),
        'size'  => 15,
        'name'  => 'Corbel'
    ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00c7ff')
        )
    );
 $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->applyFromArray($style5);
////////////////////////////////////// 									empezar a poblar con los datos reales 			/	////////////////////////////////////////////////////////////////////////////////////////
  //FECHA DE DESCLASIFICACION.  A PARTIR DE LA J6
$limite=count($fechaDesclasificacion);
$cont=0;
$col=9; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4 I=8 F=5 J=9
//columna D Unidades 
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	
	$data=$fechaDesclasificacion[$cont];
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	
}
  //MOTIVO RESERVA.  A PARTIR DE LA F6
$limite=count($motivoReserva);
$cont=0;
$col=5; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4 I=8 F=5
//columna D Unidades 
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	
	$data=$motivoReserva[$cont];
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	
}
 //FECHA CLASIFICACION.  A PARTIR DE LA I6
$limite=count($fechaClasificacion);
$cont=0;
$col=8; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4 I=8
//columna D Unidades 
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	
	$data=$fechaClasificacion[$cont];
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	
}
  //FUNDAMENTO LEGAL.  A PARTIR DE LA G6
$limite=count($fundamentoLegal);
$cont=0;
$col=6; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4
//columna D Unidades
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$fundamentoLegal[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
 //AUTORIDADD QUE RESERVA.  A PARTIR DE LA e6
$limite=count($autoridadReserva);
$cont=0;
$col=4; //columnas desde cero: 0=A, 1=B, etc H=7 E=4
//columna D Unidades
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$autoridadReserva[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
  //TIPOS DE CLASIFICACION.  A PARTIR DE LA H6
$limite=count($tiposReserva);
$cont=0;
$col=7; //columnas desde cero: 0=A, 1=B, etc H=7
//columna D Unidades
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$tiposReserva[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
 //NUMEROS RESERVA.  A PARTIR DE LA B6
$limite=count($numerosReserva);
$cont=0;
$col=1; //columnas desde cero: 0=A, 1=B, etc
//columna D Unidades
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$numerosReserva[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
 //UNIDADES ADMINISTRATIVAS.  A PARTIR DE LA D6
$limite=count($unidadesEnte);
$cont=0;
$col=3; //columnas desde cero: 0=A, 1=B, etc
//columna D Unidades
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	$data=$unidadesEnte[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
 //NOMBRES DE DOCUMENTO RESERVADOS.  A PARTIR DE LA B6
$limite=count($nombresDocumentos);
$cont=0;
$col=2; //columnas desde cero: 0=A, 1=B, etc
//columna C nombre de documento
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	$data=$nombresDocumentos[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
//escribo números correlativos sencillos. Correlativo columna A
$correlativo=1;
$col=0;
$cont=0;
$row=6; //SIEMPRE RESETEAR ROW para emepzar desde fila 6
for($cont=0;$cont<$limite;$cont++)
{
	//echo "correlativo: ".$correlativo;
	//echo "escrito en celda ".$col.",".$row;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $correlativo);
	$correlativo++;
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
$numeroDocumentosIndice=$limite;
$ultimaFila=5+$limite;
$borderLandA="A5:A".$ultimaFila;
$borderLandB="B5:B".$ultimaFila;
$borderLandC="C5:C".$ultimaFila;
$borderLandD="D5:D".$ultimaFila;
$borderLandE="E5:E".$ultimaFila;
$borderLandF="F5:F".$ultimaFila;
$borderLandG="G5:G".$ultimaFila;
$borderLandH="H5:H".$ultimaFila;
$borderLandI="I5:I".$ultimaFila;
$borderLandJ="J5:J".$ultimaFila;
$objPHPExcel->getActiveSheet()->getStyle($borderLandA)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandB)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandC)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandD)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandE)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandF)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandG)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandH)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandI)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle($borderLandJ)->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->setTitle("Indice de reserva");

/* $urlFoto=base64_decode($urlFoto);
$gdImage = imagecreatefromstring ($urlFoto);
// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Sample image');$objDrawing->setDescription('Sample image');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(150);
$objDrawing->setWidth(225);    
$objDrawing->setCoordinates('D1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
 */

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//GUARDO EL FICHERO EXCEL EN CARPETA "EXCELS"


$nombreFicheroFinal=$nombreEnte.".xlsx";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//echo "Hola";
chdir("Excels");
chdir($carpetaDestino);
$objWriter->save($nombreFicheroFinal);

//echo "fini----------------";
//exit;

?>