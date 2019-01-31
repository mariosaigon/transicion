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
include("../Classes/PHPExcel.php");
function comprimirDirectorio($directorio)
{
$rootPath = realpath($directorio);

// Initialize archive object
$zip = new ZipArchive();
$zip->open('indiceReserva.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}
// Zip archive will be created only after closing object
$zip->close();
//rename('indiceReserva.zip', realpath(dirname(__FILE__) . '/..'));
}
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
///mando a descargar como Excel, quitando antes dos excel indeseables
$directorio='../Excels';
comprimirDirectorio($directorio);
$nombreZip="indiceReserva_".date('d-m-Y H:i:s').".zip";
ob_clean();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Content-Type: application/zip");
header('Content-Disposition: attachment; filename="' . stripslashes($nombreZip) . '"');
readfile("indiceReserva.zip");
exit;
?>