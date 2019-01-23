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

$UI = new UI();
$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$view = $UI->factory($theme, $tmp[1], array('dms'=>$dms, 'user'=>$user));

if (!$user->isAdmin()) {
	$UI->exitError(getMLText("admin_tools"),getMLText("access_denied"));
}
$orderby='n';
if (isset($_GET["orderby"]) && strlen($_GET["orderby"])==1 ) {
	$orderby=$_GET["orderby"];
}
$showInProcess = false;
if (isset($_GET["inProcess"]) && strlen($_GET["inProcess"])>0 && $_GET["inProcess"]!=0) {
	$showInProcess = true;
}
$rootfolder = $dms->getFolder($settings->_rootFolderID);
$data = $dms->getStatisticalData('behaviourchange');

if($view) {
	$view->setParam('orderby', $orderby);
	$view->setParam('baseServer', $settings->_httpRoot);
	$view->setParam('showinprocess', $showInProcess);
	$view->setParam('workflowmode', $settings->_workflowMode);
	$view->setParam('cachedir', $settings->_cacheDir);
	$view->setParam('previewWidthList', $settings->_previewWidthList);
	$view->setParam('timeout', $settings->_cmdTimeout);
	$view->setParam('rootfolder', $rootfolder);
	$view->setParam('data', $data);
	$view($_GET);
	exit;
}

?>
