
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
 
include("./inc/inc.Settings.php");
include("./inc/inc.LogInit.php");
include("./inc/inc.Utils.php");
include("./inc/inc.Language.php");
include("./inc/inc.Init.php");
include("./inc/inc.Extension.php");
include("./inc/inc.DBInit.php");
//include("./inc/inc.Authentication.php");
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
 // $settings = new Settings(); //acceder a parámetros de settings.xml con _antes
 //  	$driver=$settings->_dbDriver;
 //    $host=$settings->_dbHostname;
 //    $user=$settings->_dbUser;
 //    $pass=$settings->_dbPass;
 //    $base=$settings->_dbDatabase;
	// $manejador=new SeedDMS_Core_DatabaseAccess($driver,$host,$user,$pass,$base);
	// $estado=$manejador->connect();
	// //echo "Conectado: ".$estado;
	// if($estado!=1)
	// {
	// 	UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("generarCSV: No se pudo conectar a la BD"));
	// }	
	// //query de consulta:
	// $miQuery="INSERT INTO historialConvocatorias (idUsuario,mesConvocatoria,yearConvocatoria) VALUES ('$idUsuario','$mesConvocatoria','$yearConvocatoria')";
	// //echo "mi query: ".$miQuery;
	// $resultado=$manejador->getResultArray($miQuery);
	// //echo "fecha= ".$fecha;
 //    if(!$resultado)
	// {
	// 	UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("Generar CSV"));
	// }
$idUsuario="";
if (isset($_POST["idUser"])  && strlen($_POST["idUser"])>0 ) //se envía a través del método POST porque es un formulario
{
	$idUsuario=$_POST["idUser"];
	//echo "creador id:: ".$idUsuario;
}
//echo "idUsuario: ".$idUsuario;
$user=$dms->getUser($idUsuario);
$documentos=$dms->getDocumentsByUser($user);

$atributoNumeroDeclaratoria=$dms->getAttributeDefinitionByName("No. de Declaración de Reserva");
				$atributoTipo=$dms->getAttributeDefinitionByName("Tipo de clasificación");
				$atributoDetalle=$dms->getAttributeDefinitionByName("Detalle de la reserva parcial");
				$atributoMotivo=$dms->getAttributeDefinitionByName("Motivo de la reserva");
				$atributoLaip=$dms->getAttributeDefinitionByName("Fundamento legal (Art. 19 LAIP)");
				$atributoUnidad=$dms->getAttributeDefinitionByName("Unidad Administrativa");
				$atributoUnidadGeneradora=$dms->getAttributeDefinitionByName("Unidad Generadora de la Información");
				$atributoAutoridad=$dms->getAttributeDefinitionByName("Autoridad que reserva");
				$atributoFecha=$dms->getAttributeDefinitionByName("Fecha de clasificación");
				//normativas añadidas
				$constitucional=$dms->getAttributeDefinitionByName("Base constitucional");
				$tratados=$dms->getAttributeDefinitionByName("Tratados internacionales");
				$nacional=$dms->getAttributeDefinitionByName("Normativa nacional");
				$reglamentos=$dms->getAttributeDefinitionByName("Reglamentos o instrumentos administrativos");

				$arrayOrdenado=array();
				$arrayOrdenado[]=$atributoNumeroDeclaratoria;
				$arrayOrdenado[]=$atributoTipo; //numvuel=2
				$arrayOrdenado[]=$atributoDetalle;//numvuel=3
				$arrayOrdenado[]=$atributoMotivo;//numvuel=4
				$arrayOrdenado[]=$atributoLaip;//numvuel=5
				///normativas nuevas mayo 2018
				$arrayOrdenado[]=$constitucional; //numvuel=6
				$arrayOrdenado[]=$tratados; //numvuel=7
				$arrayOrdenado[]=$nacional; //numvuel=8
				$arrayOrdenado[]=$reglamentos; //numvuel=9
				/////
				$arrayOrdenado[]=$atributoUnidad; //numvuel=10
				$arrayOrdenado[]=$atributoUnidadGeneradora; //numvuel=11
				$arrayOrdenado[]=$atributoAutoridad; //numvuel=12
				$arrayOrdenado[]=$atributoFecha; //numvuel=13
$myfile = fopen("indice.csv", "w");
$primera=["No. Declaración", "Rubro temático", "Tipo de clasificación", "Detalle de la reserva parcial","Motivo de la reserva", "Fundamento legal (Art. 19 LAIP)", "Normativa Constitucional", "Tratados internacionales", "Normativa nacional", "Reglamentos administrativos", "Unidad que sugiere la reserva", "Unidad que genera la información a reservar", "Autoridad que reserva", "fecha de clasificación","fecha de caducidad de reserva"];
fputcsv($myfile, $primera); //linea cabecera	

foreach ($documentos as $doc) 
{
	$arrayLine=array();
	$cont=1;	
	foreach($arrayOrdenado as $attrdef) 
	{
		if($cont==2)
		{
			$nombreRubro=$doc->getName();
			$arrayLine[]=$nombreRubro;
		}
		$valor=$doc->getAttributeValue($attrdef);
		
		if(is_array($valor)==TRUE)
		{
			$bueno=implode($valor);
			$arrayLine[]=$bueno;
		}
		else
		{
			$arrayLine[]=$valor;
		}


			$cont++;
	}
	//por ultimo fecha de caducidad
		$caducidad=$doc->getExpires();
		$fc=date('Y-m-d', $caducidad);
		$arrayLine[]=$fc;
	fputcsv($myfile, $arrayLine);
	//echo "/*/*/*/NUEVO DOC*/*/*/:<br>";
}
fclose($myfile);

/////////////////////////////////////////////////////////////////////////////
ob_end_clean();
 header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('indice.csv'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('indice.csv'));
    readfile('indice.csv');
    exit;

?>
Hola