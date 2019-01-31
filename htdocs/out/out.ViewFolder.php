<?php
//    MyDMS. Document Management System
//    Copyright (C) 2002-2005 Markus Westphal
//    Copyright (C) 2006-2008 Malcolm Cowe
//    Copyright (C) 2010 Matteo Lucarelli
//    Copyright (C) 2010-2016 Uwe Steinmann
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

/**
    Enhanced by José Mario López Leiva. September 2017
	San Salvador, El Salvador
	marioleiva2011@gmail.com
**/

include("../inc/inc.Settings.php");
include("../inc/inc.Utils.php");
include("../inc/inc.Language.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.Authentication.php");
include("../inc/inc.ClassUI.php");
function getDefaultUserFolder($id_usuario) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$id_folder=0;
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
		echo "out.viewFolder.php[getDefaultUserFolder]Error: no se pudo conectar a la BD";
	}	
	//query de consulta:
	$miQuery="SELECT homefolder FROM tblUsers WHERE id=".$id_usuario;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	$id_folder=$resultado[0]['homefolder'];
	//echo "id_folder: ".$id_folder;
	return $id_folder;
}

$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$view = UI::factory($theme, $tmp[1], array('dms'=>$dms, 'user'=>$user));

//algoritmo:
/**
Tengo dos posibles id de folder: el que me pasan por petición al hacer click en un folder, y el id del folder inicial del usuario (que se define en la interfaz de creación/modificación
de usuario). Tengo que ver cada id, obtener el folder correspondiente a cada id; y ver el folder padre de ese. Si el folder padre es el id 1 (Directorio Central) significa que 
estoy en la "pantalla de inicio" del usuario, si no, estoy en un subfolder del usuario y debo poder acceder a ellos.
resumen:
-Obtener dos id: el que me pasan (se obtiene con $_GET["folderid"]) y el id del folder inicial (con getDefaultUserFolder), obtener el folder de cada id, de eso que obtenga

**/

if (!isset($_GET["folderid"]) || !is_numeric($_GET["folderid"]) || intval($_GET["folderid"])<1) 
{
	//echo "b1";
	$folderid = $settings->_rootFolderID;
}
else 
{
	//echo "b2";
	$folderid = intval($_GET["folderid"]); //obtiene el valor entero intval 
	//echo "folderid despues de set;".$folderid;
}
//echo "Folder_id: ".$folderid;
//Obtengo el id del folder asociado como "homefolder" (carpeta de inicio) asociado al usuario
	$esadmin=$user->isAdmin();
	$esinvitado=$user->isGuest();
	$miid=$user->getID();
	$id_carpeta_usuario=getDefaultUserFolder($miid);
	//echo "Usuario es admin? ".$esadmin;
   // echo "id de usuario: ".$miid;
	if($esinvitado==TRUE)
		{
		//$folderid = $settings->_rootFolderID;
		$folderid = intval($_GET["folderid"]);
		}
	else if($esadmin==FALSE && $folderid!=$id_carpeta_usuario) //si es un usuario que no es el administrador y el id del folder que quiero ver es el directorio central, no puedo y se me pone
	//el id de su carpeta de inicio, de esa forma la carpeta de inicio que verá todo usuario será la suya y no el directorio central (id1)
	// 28 sept modificación: crear 
	{
				$folderAux=$dms->getFolder($id_carpeta_usuario); //digo: solo puedo entrar a mi carpeta inicial o a las carpetas que sean hijas de esta caerpeta
				$folderAux2=$dms->getFolder($folderid);
				if($folderAux->isSubFolder($folderAux2))
				{
					$folderid = intval($_GET["folderid"]);//obtiene el valor entero intval 
				}
				else
				{
					$folderid = intval($id_carpeta_usuario);
				}
		
		
	}
	
$folder = $dms->getFolder($folderid);


if (!is_object($folder)) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_folder_id"))),getMLText("invalid_folder_id"));
}

//comprobar el modo de acceso del folder
/**
      Problema: al crear un folder en el directorio principal, por defecto como el admin lo crea, los usuarios (oficiales de información) no pueden crear nada en su folder
	  porque solo tiene permisos el admin. Solución: si el usuario no es ni admin ni invitado (oficial de información) cambio el permiso para lectura_escritura.
	  Permisos:
	  2 lectura
	  3 lectura_escritura

	  28 SEPT 2017: TAMBIÉN DEBO AUTOMATIZAR QUE NO HEREDE ACCESO, ES DECITR, setInheritAccess( $inheritAccess) CON INHERIT ACCESS 0
**/
if(!$user->isAdmin() && !$user->isGuest())
{
	//echo "Soy un OIP";
	$permiso_actual=$folder->getDefaultAccess();
	//echo "permiso actual: ".$permiso_actual;
	if($permiso_actual==2)//si solo tengo permiso de lectura lo cambio a lecto escritura
	{
		//echo "aqui";
		$folder->setDefaultAccess(3);
		$folder->setInheritAccess(0); //linea añadida 28 sept 2017 
 
	}
	//echo "nuevo permiso actual: ".$folder->getDefaultAccess();
}

if (isset($_GET["orderby"]) && strlen($_GET["orderby"])==1 ) {
	$orderby=$_GET["orderby"];
} else $orderby=$settings->_sortFoldersDefault;

if ($folder->getAccessMode($user) < M_READ) {
	UI::exitError(getMLText("folder_title", array("foldername" => htmlspecialchars($folder->getName()))),getMLText("access_denied"));
}

if($view) {
	$view->setParam('folder', $folder);
	$view->setParam('orderby', $orderby);
	$view->setParam('enableFolderTree', $settings->_enableFolderTree);
	$view->setParam('enableDropUpload', $settings->_enableDropUpload);
	$view->setParam('expandFolderTree', $settings->_expandFolderTree);
	$view->setParam('showtree', showtree());
	$view->setParam('settings', $settings);
	$view->setParam('cachedir', $settings->_cacheDir);
	$view->setParam('workflowmode', $settings->_workflowMode);
	$view->setParam('enableRecursiveCount', $settings->_enableRecursiveCount);
	$view->setParam('maxRecursiveCount', $settings->_maxRecursiveCount);
	$view->setParam('baseServer', $settings->_httpRoot);
	$view->setParam('previewWidthList', $settings->_previewWidthList);
	$view->setParam('timeout', $settings->_cmdTimeout);
	$view($_GET);
	exit;
}

?>
