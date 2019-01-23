<?php
//    MyDMS. Document Management System
//    Copyright (C) 2002-2005  Markus Westphal
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

include("../inc/inc.Settings.php");
include("../inc/inc.LogInit.php");
include("../inc/inc.Language.php");
include("../inc/inc.Utils.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.ClassUI.php");
include("../inc/inc.ClassController.php");
//include("../inc/inc.Authentication.php");

if (isset($_GET["hash"])) { /* {{{ */
	$link = SeedDMS_Core_DownloadLink::getInstance($hash, $dms);
	if(!$link)
		exit;
	$user = $link->getUser();
	$document = $link->getDocument();
	$version = $ling->getVersion();
	
	$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
	$controller = Controller::factory($tmp[1]);
	$accessop = new SeedDMS_AccessOperation($dms, $user, $settings);
	if (!$accessop->check_controller_access($controller, $_POST)) {
		UI::exitError(getMLText("document_title", array("documentname" => "")),getMLText("access_denied"));
	}

	if (!is_object($document)) {
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("invalid_doc_id"));

	}

	if ($document->getAccessMode($user) < M_READ) {
		UI::exitError(getMLText("document_title", array("documentname" => $document->getName())),getMLText("access_denied"));
	}

	$content = $document->getContentByVersion($version);

	if (!is_object($content)) {
		UI::exitError(getMLText("document_title", array("documentname" => $document->getName())),getMLText("invalid_version"));
	}

	$controller->setParam('content', $content);
	$controller->version();

} /* }}} */

add_log_line();
exit();
