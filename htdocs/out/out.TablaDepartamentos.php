<?php
//    
//    Copyright (C) José Mario López Leiva. marioleiva2011@gmail.com_addre
//    September 2017. San Salvador (El Salvador)
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

include("../inc/inc.Settings.php");
include("../inc/inc.Language.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.ClassUI.php");
include("../inc/inc.Authentication.php");
$stringDeclaratoriasReserva="Declaratorias de reserva";
$stringActas="Actas de inexistencia"; //string tal como aparece en la tabla tblcategory
//esta es una variable global y acceso a ella desde $GLOBALS
 /*
 FUNCION QUE RECIBE UNA CARPETA, CORRESPONDIENTE A UN DEPARAMENTO, Y CUENTA
 */
 //creado José Mario López Leiva
 /**
DEVUELVE EL ID DE USUARIO DADO UN ID DE FOLDER QUE ES EL HOME FOLDER
 **/


function getUserFromHomeFolder($id_folder) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$idUsuario=0;
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
		echo "out.viewFolder.php[getUserFromHomeFolder]Error: no se pudo conectar a la BD ".$base;
		exit;
	}	
	//query de consulta:
	$miQuery="SELECT id FROM tblUsers WHERE homefolder=".$id_folder;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	if($resultado==FALSE)
	{
		echo "out.viewFolder.php[getUserFromHomeFolder]Error: no se pudo  hacer la consulta ".$miQuery;
		exit;
	}
	//echo "pos cero:; ".print_r($resultado[1])."</br>";
	//$idUsuario=$resultado[0]['id'];
	//echo "id_folder: ".$id_folder;
	//print_r($resultado);
	return $idUsuario;
}
/**
FUNCION QUE DADO UN DIRECTORIO, ESCANEA TODOS SUS SUBFOLDERS Y CUENTA EL NUMERO DE ACTAS DE INEXISTENCIA
O DECLARATORIAS, SEGÚN EL ARGUMENTO tipo_documento
**/
function contarDocumentos($tipo_documento,$folder,$orderby)
{
	$contadorFinal=0;
	
    // un departamento es un folder, cada subfolder será un municipio
    //print "<p> Municipios del departamento de ".$departamento->getName()."</p>";
		$municipios=$folder->getSubFolders();
		foreach ($municipios as $municipio) 
		{
			//echo "municipio ".$municipio->getName()."</br>";				
					$listaDocumentos=$municipio->getDocuments($orderby);

					foreach ($listaDocumentos as $documento)
					 {
							$categoriasDocumento=$documento->getCategories();
				   	    	foreach ($categoriasDocumento as $categoria) 
				   	    	{
				   	    		if(strcmp($tipo_documento, "ACTAS")==0)
				   	    		{
				   	    			//echo "acta </br>";
				   	    			if(strcmp($categoria->getName(),$GLOBALS['stringActas'])==0)
					   	    		{
					   	    			//echo "es una declaratoria de reserva </br>";
					   	    			$contadorFinal++;
					   	    		}

				   	    		}
				   	    		if(strcmp($tipo_documento, "RESERVAS")==0)
				   	    		{
				   	    			//echo "reserva</br>";
				   	    			if(strcmp($categoria->getName(),$GLOBALS['stringDeclaratoriasReserva'])==0)
					   	    		{
					   	    			//echo "es una declaratoria de reserva </br>";
					   	    			$contadorFinal++;
					   	    		}
				   	    		
				   	    		}
				   	    	}
				   	      }
				   	 	
		   }   
   	    ///////////////////////////////
return $contadorFinal;
}
function contarSinRegistro($folder,$orderby)
{
	$contadorFinal=0;
	
    // un departamento es un folder, cada subfolder será un municipio
    //print "<p> Municipios del departamento de ".$departamento->getName()."</p>";
		$municipios=$folder->getSubFolders();
		foreach ($municipios as $municipio) 
		{
			//echo "municipio ".$municipio->getName()."</br>";				
					$listaDocumentos=$municipio->getDocuments($orderby);
					if($listaDocumentos==FALSE)
					{
						//echo "esta carpeta no tiene documentos";
						$contadorFinal++;
					}				   	 	
		   }   
   	    ///////////////////////////////
return $contadorFinal;

}
//////---------------------------------------------------------------------------------------------------------------------------------------------------------
$id_numero_declaratoria=6; //id en la base de datos del ATRIBUTO Nº de Reserva de Declaratoria. Se usa en class.CaducaranPronto.php y sirve para obtener el atributo de un documento en las tablas que se
$id_fecha_clasificacion=2;
//tabla seeddms.tblattributedefinitions;
 //generan
if ($user->isGuest()) {
	UI::exitError(getMLText("my_documents"),getMLText("access_denied"));
}

// Check to see if the user wants to see only those documents that are still
// in the review / approve stages.
$showInProcess = false;
if (isset($_GET["inProcess"]) && strlen($_GET["inProcess"])>0 && $_GET["inProcess"]!=0) {
	$showInProcess = true;
}

$orderby='n';
if (isset($_GET["orderby"]) && strlen($_GET["orderby"])==1 ) {
	$orderby=$_GET["orderby"];
}
/**
FUNCIONAMIENTO ESTADISTICAS DEPARTAMENTALES
Cada carpeta dentro de la carpeta "entidades municipales" es el nombre de uno de los 14 departamentos.
Para saber las estadísticas de Santa Ana, por ejemplo, debo recorrer esa carpeta, todas sus subcarpetas son sus municipios.
Para cada subcarpeta debo tener una opción de contar las actas de inexistencia, y reservas, y sin registro (nada de nada)
 
**/
$arrayNombres=array();
//////
 $nombreAhuachapan="Ahuachapán"; 
 $arrayNombres[]=$nombreAhuachapan;
 $reservasAhuachapan=0;
 $actasAhuachapan=0;
 $sinAhuachapan=0;

//////
 $nombreCabañas="Cabañas";
 $arrayNombres[]=$nombreCabañas;
  $reservasCabañas=0;
 $actasCabañas=0;
 $sinCabañas=0;

 $nombreChalatenango="Chalatenango";
  $arrayNombres[]=$nombreChalatenango;
   $reservasChalate=0;
 $actasChalates=0;
 $sinChalate=0;

$nombreCuscatlan="Cuscatlán";
 $arrayNombres[]=$nombreCuscatlan;
 $reservasCusca=0;
 $actasCusca=0;
 $sinCusca=0;

$nomnbreLaLibertad="La Libertad";
 $arrayNombres[]=$nomnbreLaLibertad;
  $reservasLaLibertad=0;
 $actasLaLibertad=0;
 $sinLaLibertad=0;


$nombreLaPaz="La Paz";
 $arrayNombres[]=$nombreLaPaz;
   $reservasLaPaz=0;
 $actasLaPaz=0;
 $sinLaPaz=0;

$nombreLaUnion="La Unión";
 $arrayNombres[]=$nombreLaUnion;
    $reservasLaUnion=0;
 $actasLaUnion=0;
 $sinLaUnion=0;

$nombreMorazan="Morazán";
 $arrayNombres[]=$nombreMorazan;
     $reservasMorazan=0;
 $actasMorazan=0;
 $sinMorazan=0;

$nombreSanMiguel="San Miguel";
 $arrayNombres[]=$nombreSanMiguel;
      $reservasSanMiguel=0;
 $actasSanMiguel=0;
 $sinSanMiguel=0;

$nombreSanSalvador="San Salvador";
 $arrayNombres[]=$nombreSanSalvador;
       $reservasSanSalvador=0;
 $actasSanSalvador=0;
 $sinSanSalvadorl=0;


$nombreSanVicente="San Vicente";
 $arrayNombres[]=$nombreSanVicente;
        $reservasSanVicente=0;
 $actasSanVicente=0;
 $sinSanVicente=0;

$nombreSantaAna="Santa Ana";
 $arrayNombres[]=$nombreSantaAna;
        $reservasSantaAna=0;
 $actasSantaAna=0;
 $sinSantaAna=0;

$nombreSonsonate="Sonsonate";
 $arrayNombres[]=$nombreSonsonate;
         $reservasSonsonate=0;
 $actasSonsonate=0;
 $sinSonsonate=0;

$nombreUsulutan="Usulután";
  $arrayNombres[]=$nombreUsulutan;
           $reservasUsulutan=0;
 $actasUsulutan=0;
 $sinUsulutan=0;

 //1) recorro cada carpeta según nombre de folder (nombre de departamento

foreach ($arrayNombres as $departamento) 
{
	$folderDepartamento=$dms->getFolderByName($departamento);
	if($folderDepartamento==FALSE)
	{
		echo "out.EstadisticasDepartamentales: error buscando la carpeta ".$departamento;
		exit;
	}
	    
	if(strcmp($departamento, $nombreAhuachapan)==0)
	{
			$reservasAhuachapan=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasAhuachapan=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinAhuachapan=contarSinRegistro($folderDepartamento,$orderby);
			
	}
	if(strcmp($departamento, $nombreCabañas)==0)
	{
			$reservasCabañas=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasCabañas=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinCabañas=contarSinRegistro($folderDepartamento,$orderby);
			
	}


	if(strcmp($departamento, $nombreChalatenango)==0)
	{
			$reservasChalate=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasChalates=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinChalate=contarSinRegistro($folderDepartamento,$orderby);
			
	}
	if(strcmp($departamento, $nombreCuscatlan)==0)
	{
			$reservasCusca=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasCusca=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinCusca=contarSinRegistro($folderDepartamento,$orderby);
			
	}

	if(strcmp($departamento, $nomnbreLaLibertad)==0)
	{
			$reservasLaLibertad=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasLaLibertad=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinLaLibertad=contarSinRegistro($folderDepartamento,$orderby);
			
	}

		if(strcmp($departamento, $nombreLaPaz)==0)
	{
			$reservasLaPaz=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasLaPaz=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinLaPaz=contarSinRegistro($folderDepartamento,$orderby);
			
	}
		if(strcmp($departamento, $nombreLaUnion)==0)
	{
			$reservasLaUnion=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasLaUnion=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinLaUnion=contarSinRegistro($folderDepartamento,$orderby);
			
	}

	if(strcmp($departamento, $nombreMorazan)==0)
	{
			$reservasMorazan=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasMorazan=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinMorazan=contarSinRegistro($folderDepartamento,$orderby);
			
	}

	if(strcmp($departamento, $nombreSanMiguel)==0)
	{
			$reservasSanMiguel=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasSanMiguel=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinSanMiguel=contarSinRegistro($folderDepartamento,$orderby);
			
	}

	if(strcmp($departamento, $nombreSanSalvador)==0)
	{
			$reservasSanSalvador=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasSanSalvador=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinSanSalvadorl=contarSinRegistro($folderDepartamento,$orderby);
			
	}

		if(strcmp($departamento, $nombreSanVicente)==0)
	{
			$reservasSanVicente=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasSanVicente=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinSanVicente=contarSinRegistro($folderDepartamento,$orderby);
			
	}
		if(strcmp($departamento, $nombreSantaAna)==0)
	{
			$reservasSantaAna=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasSantaAna=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinSantaAna=contarSinRegistro($folderDepartamento,$orderby);
			
	}
		if(strcmp($departamento, $nombreSonsonate)==0)
	{
			$reservasSonsonate=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasSonsonate=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinSonsonate=contarSinRegistro($folderDepartamento,$orderby);
			
	}

		if(strcmp($departamento, $nombreUsulutan)==0)
	{
			$reservasUsulutan=contarDocumentos("RESERVAS",$folderDepartamento,$orderby);
			//echo "fin reservas, numero: ".$reservasAhuachapan."</br>";			
			$actasUsulutan=contarDocumentos("ACTAS",$folderDepartamento,$orderby);
			//echo "fin actas, numero: ".$actasAhuachapan."</br>";	
			$sinUsulutan=contarSinRegistro($folderDepartamento,$orderby);
			
	}
}

//2) CUENTO TIPO DE DOCUMENTOS PARA ENTES NO MUNICIPALES
 $nombreDir="Entes obligados no municipales";
 $folderEntes=$dms->getFolderByName($nombreDir); //me da el folder, pero cada ente son los hijos del 
 if(!$folderEntes)
 {
 	echo "[out.EstadisticasDepartamentales.php]: Error obteniendo directorio $nombreDir: getFolderByName"; 
 	exit;
 }
 	$acumuladorReservas=contarDocumentos("RESERVAS",$folderEntes,$orderby);
 	$acumuladorActas=contarDocumentos("ACTAS",$folderEntes,$orderby);
 	$acumuladorSin=contarSinRegistro($folderEntes,$orderby);






$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$view = UI::factory($theme, $tmp[1], array('dms'=>$dms, 'user'=>$user));
if($view) {
	$view->setParam('orderby', $orderby);
	$view->setParam('showinprocess', $showInProcess);
	$view->setParam('workflowmode', $settings->_workflowMode);
	$view->setParam('cachedir', $settings->_cacheDir);
	$view->setParam('previewWidthList', $settings->_previewWidthList);
	$view->setParam('timeout', $settings->_cmdTimeout);
	$view->setParam('id_numero_declaratoria', $id_numero_declaratoria);
	$view->setParam('id_fecha_clasificacion', $id_fecha_clasificacion);

		$view->setParam('acumuladorActas', $acumuladorActas);
			$view->setParam('acumuladorReservas', $acumuladorReservas);
			$view->setParam('acumuladorSin', $acumuladorSin);
	// $view->setParam('caduca_en', $caduca_en);
	// $view->setParam('formato', $formato);
	// $view->setParam('exacto', $copia);
	//$view->setParam('fue_chequeado', $fue_chequeado);
	$view->setParam('arrayNombres', $arrayNombres);
	//
	$view->setParam('reservasAhuachapan', $reservasAhuachapan);
	$view->setParam('actasAhuachapan', $actasAhuachapan);
	$view->setParam('sinAhuachapan', $sinAhuachapan);

		$view->setParam('reservasCabañas', $reservasCabañas);
	$view->setParam('actasCabañas', $actasCabañas);
	$view->setParam('sinCabañas', $sinCabañas);


		$view->setParam('reservasChalate', $reservasChalate);
	$view->setParam('actasChalates', $actasChalates);
	$view->setParam('sinChalate', $sinChalate);
	
			$view->setParam('reservasCusca', $reservasCusca);
	$view->setParam('actasCusca', $actasCusca);
	$view->setParam('sinCusca', $sinCusca);

		$view->setParam('reservasLaLibertad', $reservasLaLibertad);
	$view->setParam('actasLaLibertad', $actasLaLibertad);
	$view->setParam('sinLaLibertad', $sinLaLibertad);

		$view->setParam('reservasLaPaz', $reservasLaPaz);
	$view->setParam('actasLaPaz', $actasLaPaz);
	$view->setParam('sinLaPaz', $sinLaPaz);



		$view->setParam('reservasLaUnion', $reservasLaUnion);
	$view->setParam('actasLaUnion', $actasLaUnion);
	$view->setParam('sinLaUnion', $sinLaUnion);

	$view->setParam('reservasMorazan', $reservasMorazan);
	$view->setParam('actasMorazan', $actasMorazan);
	$view->setParam('sinMorazan', $sinMorazan);


	$view->setParam('reservasSanMiguel', $reservasSanMiguel);
	$view->setParam('actasSanMiguel', $actasSanMiguel);
	$view->setParam('sinSanMiguel', $sinSanMiguel);

$view->setParam('reservasSanSalvador', $reservasSanSalvador);
	$view->setParam('actasSanSalvador', $actasSanSalvador);
	$view->setParam('sinSanSalvadorl', $sinSanSalvadorl);

$view->setParam('reservasSanVicente', $reservasSanVicente);
	$view->setParam('actasSanVicente', $actasSanVicente);
	$view->setParam('sinSanVicente', $sinSanVicente);

$view->setParam('reservasSantaAna', $reservasSantaAna);
	$view->setParam('actasSantaAna', $actasSantaAna);
	$view->setParam('sinSantaAna', $sinSantaAna);

	$view->setParam('reservasSonsonate', $reservasSonsonate);
	$view->setParam('actasSonsonate', $actasSonsonate);
	$view->setParam('sinSonsonate', $sinSonsonate);

	$view->setParam('reservasUsulutan', $reservasUsulutan);
	$view->setParam('actasUsulutan', $actasUsulutan);
	$view->setParam('sinUsulutan', $sinUsulutan);


	$view($_GET);
	exit;
}

?>
