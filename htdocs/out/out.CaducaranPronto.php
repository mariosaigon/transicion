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
$id_numero_declaratoria=6; //id en la base de datos del ATRIBUTO Nº de Reserva de Declaratoria. Se usa en class.CaducaranPronto.php y sirve para obtener el atributo de un documento en las tablas que se
$id_fecha_clasificacion=2;
//tabla seeddms.tblattributedefinitions;
 //generan
// if ($user->isGuest()) {
// 	UI::exitError(getMLText("my_documents"),getMLText("access_denied"));
// }

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
$caduca_en=0; 
$copia="";
// se llama el nombre del input caduca_dentro_de en el archivo class.ProximasCaducidades.php
if (isset($_POST["caduca_dentro_de"])  && strlen($_POST["caduca_dentro_de"])>0 ) //se envía a través del método POST porque es un formulario
{
	$caduca_en=$_POST["caduca_dentro_de"];
	$copia=$caduca_en;
}
$franja="";
if (isset($_POST["franja"])  && strlen($_POST["franja"])>0 ) //se envía a través del método POST porque es un formulario
{
	$franja=$_POST["franja"];
	//1: dias
	//2: meses
	//3: años
}
$formato="";
switch ($franja) {
	case '1':
		$caduca_en=$caduca_en*1;
		$formato="días";
		break;
	case '2':
		$caduca_en=$caduca_en*30;
		$formato="meses";
		break;
	case '3':
		$caduca_en=$caduca_en*365;
		$formato="años";
		break;

}
$fue_chequeado=FALSE;
if (isset($_POST["chequeado"])) //se envía a través del método POST porque es un formulario
{
   $caduca_en=18250;//50 años
   $fue_chequeado=TRUE;
}

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
	$view->setParam('caduca_en', $caduca_en);
	$view->setParam('formato', $formato);
	$view->setParam('exacto', $copia);
	$view->setParam('fue_chequeado', $fue_chequeado);
	$view($_GET);
	exit;
}

?>
