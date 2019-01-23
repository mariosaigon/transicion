
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
     //echo "Hola, prueba de crear un excel y pushearlo al navegador";
	 //echo "<br>";
	 
	 include("./inc/inc.Settings.php");
include("./inc/inc.LogInit.php");
include("./inc/inc.Utils.php");
include("./inc/inc.Language.php");
include("./inc/inc.Init.php");
include("./inc/inc.Extension.php");
include("./inc/inc.DBInit.php");
include("./inc/inc.Authentication.php");
include("./inc/inc.ClassUI.php");
 function check_in_range($start_date, $end_date, $date_from_user)
{
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
function anadir_a_convocatoria($idUsuario, $mesConvocatoria,$yearConvocatoria) //ddodo un id de documento que ha sido revocada la reserva,
//me da la fecha de la resolución del IAIP
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	 $settings = new Settings(); //acceder a parámetros de settings.xml con _antes
  	$driver=$settings->_dbDriver;
    $host=$settings->_dbHostname;
    $user=$settings->_dbUser;
    $pass=$settings->_dbPass;
    $base=$settings->_dbDatabase;
	$manejador=new SeedDMS_Core_DatabaseAccess($driver,$host,$user,$pass,$base);
	$estado=$manejador->connect();
	//echo "Conectado: ".$estado;
	if($estado!=1)
	{
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("generarExcel: No se pudo conectar a la BD"));
	}	
	//query de consulta:
	$miQuery="INSERT INTO historialConvocatorias (idUsuario,mesConvocatoria,yearConvocatoria) VALUES ('$idUsuario','$mesConvocatoria','$yearConvocatoria')";
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResult($miQuery);
	//echo "fecha= ".$fecha;
    if(!$resultado)
	{
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("Generar excel: Parece ser que hubo un error metiendo a la tabla historial convocatorias Usuario $idUsuario."));
	}
} 
///////////////////////// OBTENGO PARÁMETROS DESDE EL FORMULARIO
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
$urlFoto=array();
if (isset($_POST["urlFoto"]) ) //se envía a través del método POST porque es un formulario
{
	$urlFoto=$_POST["urlFoto"];

}	

$noCambio=FALSE;
if (isset($_POST["chequeado"]) ) //se envía a través del método POST porque es un formulario
{
	$noCambio=$_POST["chequeado"];

}	





	 
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
	//echo "Bucle meter numeros de reserva";
	$data=$fechaDesclasificacion[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
  //MOTIVO RESERVA.  A PARTIR DE LA F6
$limite=count($motivoReserva);
$cont=0;
$col=5; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4 I=8 F=5
//columna D Unidades 
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$motivoReserva[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
}
 //FECHA CLASIFICACION.  A PARTIR DE LA I6
$limite=count($fechaClasificacion);
$cont=0;
$col=8; //columnas desde cero: 0=A, 1=B, etc  G=6 H=7 E=4 I=8
//columna D Unidades 
$row=6; //filas empiezan desde 1 (numeración arábiga) Y DESDE EL 6 PARA MI TABLA, SIEMPRE
for($cont=0;$cont<$limite;$cont++)
{
	//echo "Bucle meter numeros de reserva";
	$data=$fechaClasificacion[$cont];
	//echo "data: ".$data;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data);
	$row++;
	//echo "hola";
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

$urlFoto=base64_decode($urlFoto);
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
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
//Redirect output to a client’s web browser (Excel2007)
/**

ENVIO CORREO AL ADMISTRADOR
**/
$fechita=date('d-m-Y');
    $fechaCortada=explode("-", $fechita);
	$mes=intval($fechaCortada[1]);
	$anio=intval($fechaCortada[2]);
	error_log( "mes: ".$mes);
	$mesito="";
	switch($mes)
	{
		case 1: $mesito="enero";
		break;
		
		case 2: $mesito="febrero";
		break;
		
		case 3: $mesito="marzo";
		break;
		
		case 4: $mesito="abril";
		break;
		
		case 5: $mesito="mayo";
		break;
		case 6: $mesito="junio";
		break;
		
		case 7: $mesito="julio";
		break;
		
		case 8: $mesito="agosto";
		break;
		
		case 9: $mesito="septiembre";
		break;
		
		case 10: $mesito="octubre";
		break;
		
		case 11: $mesito="noviembre";
		break;
		
		case 12: $mesito="diciembre";
		break;
	}
	$anoEjercicio=0;
	$convocatoria=$mesito." ".$fechaCortada[2];
	$mesConvo="";
	if($mes==12 || $mes==1)//si esto se hace entre  diciembre  y enero, entenderemos que ese hace para la convocatoria de enero
	{
		
		$mesConvo="enero";
		if($mes==12)
		{
			$convocatoria="enero ".$fechaCortada[2]+1;
			$anoEjercicio=$fechaCortada[2]+1;
		}
		else
		{
			$convocatoria="enero ".$fechaCortada[2];
			$anoEjercicio=$fechaCortada[2];
		}
	}
	// para la convocatoria de julio desde el 15 de junio al 31 de julio
	$fechaInicial=$anio."-"."06"."-"."15"; //ejemplo: 2018-06-15
	$fechaFinal=$anio."-"."07"."-"."31"; //ejemplo: 2018-06-15
	if (check_in_range($fechaInicial, $fechaFinal, $fechita)==true)
	{
		$convocatoria="julio ".$anio;
		$mesConvo="julio";
		$anoEjercicio=$anio;
	}
if($noCambio==TRUE)
{ 
	// y que si se genera el índice en un mes diferente? no se envía correo
	if(strcmp($mesConvo,"")!=0)
	{
			$subject = "Oficial de información del ente ".$nombreEnte." ha acabado su índice de reserva, y asegura que no hay cambios desde el último plazo";
				$message = "El oficial ".$creadorExcel." del ente ". $nombreEnte." ha indicado que ha finalizado su índice de reserva, y éste no se ha actualizado.\n Convocatoria: ".$convocatoria;
				//"\n  <a href=\"http://www.iaip.gob.sv/\">http://www.iaip.gob.sv/</a> \n <img src=\" https://pbs.twimg.com/profile_images/552108240582877184/Z6nbuX_l.jpeg\"></img> ";
				$params = array();
				//$params['name'] = "documentos reservados del ente ".$nombreEnte;
				$administrador=$dms->getUser(1); //usuario con id 1 
					$notifier->toIndividual($user, $administrador, $subject, $message, $params);
					$idUsuario=$user->getID();
					anadir_a_convocatoria($idUsuario,$mesConvo,$anoEjercicio);
						$subject2="Usted ha entregado correctamente su índice de reserva para la convocatoria ".$convocatoria;
					$message2="Usted ha generado y consolidado correctamente el índice de información reservada o acta de inexistencia de su ente obligado para la convocatoria $convocatoria. Este mensaje automático del sistema  es un justificante de ello. Por favor, no responde a este correo.";
					$params2 = array();
					$notifier->toIndividual($administrador, $user, $subject2, $message2, $params2);
	}	
}
else //si sí hubo cambio
{
	if(strcmp($mesConvo,"")!=0)
	{
			$subject = "Oficial de información del ente ".$nombreEnte." ha acabado su índice de reserva, y han habido cambios en el mismo desde el último plazo";
				$message = "El oficial ".$creadorExcel." del ente ". $nombreEnte." ha indicado que ha finalizado su índice de reserva, y éste tiene nuevos elementos.\n Convocatoria: ".$convocatoria;
				//"\n  <a href=\"http://www.iaip.gob.sv/\">http://www.iaip.gob.sv/</a> \n <img src=\" https://pbs.twimg.com/profile_images/552108240582877184/Z6nbuX_l.jpeg\"></img> ";
				$params = array();
				//$params['name'] = "documentos reservados del ente ".$nombreEnte;
				$administrador=$dms->getUser(1); //usuario con id 1 
					$notifier->toIndividual($user, $administrador, $subject, $message, $params);
					$idUsuario=$user->getID();
					anadir_a_convocatoria($idUsuario,$mesConvo,$anoEjercicio);
					//CORREO DE JUSTIFICANTE PARA OFICIALES
					$subject2="Usted ha entregado correctamente su índice de reserva para la convocatoria ".$convocatoria;
					$message2="Usted ha generado y consolidado correctamente el índice de información reservada o acta de inexistencia de su ente obligado para la convocatoria $convocatoria. Este mensaje automático del sistema  es un justificante de ello. Por favor, no responde a este correo.";
					$params2 = array();
					$notifier->toIndividual($administrador, $user, $subject2, $message2, $params2);
	}	
}
/////////////////////////////////////////////////////////////////////////////
ob_end_clean();
$nombreFicheroFinal="indice_".$nombreEnte."_".$fechaGeneracion.".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"".$nombreFicheroFinal."\"");
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//header('Location: /generarExcel.php');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;

?>
Hola