<?php
//    MyDMS. Document Management System
//    Copyright (C) 2002-2005  Markus Westphal
//    Copyright (C) 2006-2008 Malcolm Cowe
//    Copyright (C) 2010 Matteo Lucarelli
//    Copyright (C) 2010-2106 Uwe Steinmann
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
include("../inc/inc.Utils.php");
include("../inc/inc.Language.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.Authentication.php");
include("../inc/inc.ClassUI.php");

/* Check if the form data comes from a trusted request */
if(!checkFormKey('adddocument')) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_request_token"))),getMLText("invalid_request_token"));
}

if (!isset($_POST["folderid"]) || !is_numeric($_POST["folderid"]) || intval($_POST["folderid"])<1) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_folder_id"))),getMLText("invalid_folder_id"));
}

$folderid = $_POST["folderid"];
$folder = $dms->getFolder($folderid);

if (!is_object($folder)) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_folder_id"))),getMLText("invalid_folder_id"));
}

$folderPathHTML = getFolderPathHTML($folder, true);

if ($folder->getAccessMode($user) < M_READWRITE) {
	UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("access_denied"));
}

if($settings->_quota > 0) {
	$remain = checkQuota($user);
	if ($remain < 0) {
		UI::exitError(getMLText("folder_title", array("foldername" => htmlspecialchars($folder->getName()))),getMLText("quota_exceeded", array('bytes'=>SeedDMS_Core_File::format_filesize(abs($remain)))));
	}
}

if($user->isAdmin()) {
	$ownerid = (int) $_POST["ownerid"];
	if($ownerid) {
		if(!($owner = $dms->getUser($ownerid))) {
			UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("error_occured"));
		}
	} else {
		$owner = $user;
	}
} else {
	$owner = $user;
}
//$comment  = trim($_POST["comment"]);
//$comment="Documento subido por un Oficial de Información"; //NO NECESITO COMENTARIO DEL FICHERO
/*$version_comment = trim($_POST["version_comment"]);
if($version_comment == "" && isset($_POST["use_comment"]))
	$version_comment = $comment;
*/
$version_comment="";
//$keywords = trim($_POST["keywords"]);
$keywords=""; //NO NECESITO PALABRAS CLAVE
$categories = isset($_POST["categories"]) ? $_POST["categories"] : null;
// echo "categorias: ".$categories;
if(isset($_POST["attributes"]))
	$attributes = $_POST["attributes"];
else
	$attributes = array();
foreach($attributes as $attrdefid=>$attribute) {
	$attrdef = $dms->getAttributeDefinition($attrdefid);
	if($attribute) {
		if(!$attrdef->validate($attribute)) {
			$errmsg = getAttributeValidationText($attrdef->getValidationError(), $attrdef->getName(), $attribute);
			UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())), $errmsg);
		}
	} elseif($attrdef->getMinValues() > 0) {
		UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("attr_min_values", array("attrname"=>$attrdef->getName())));
	}
}

if(isset($_POST["attributes_version"]))
	$attributes_version = $_POST["attributes_version"];
else
	$attributes_version = array();
foreach($attributes_version as $attrdefid=>$attribute) {
	$attrdef = $dms->getAttributeDefinition($attrdefid);
	if($attribute) {
		if(!$attrdef->validate($attribute)) {
			$errmsg = getAttributeValidationText($attrdef->getValidationError(), $attrdef->getName(), $attribute);
			UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),$errmsg);
		}
	}
}
/*
$reqversion = (int)$_POST["reqversion"];
if ($reqversion<1) $reqversion=1;
*/
$reqversion="1"; //LE PONGO VERSION 1 POR DEFECTO
$sequence = $_POST["sequence"];
$sequence = str_replace(',', '.', $_POST["sequence"]);
if (!is_numeric($sequence)) {
	UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("invalid_sequence"));
}

$expires = false;
if (!isset($_POST['expires']) || $_POST["expires"] != "false") {
	if($_POST["expdate"]) {
		$tmp = explode('-', $_POST["expdate"]);
		$expires = mktime(0,0,0, $tmp[1], $tmp[2], $tmp[0]);
	} else {
		$expires = mktime(0,0,0, $_POST["expmonth"], $_POST["expday"], $_POST["expyear"]);
	}
}

// Get the list of reviewers and approvers for this document.
$reviewers = array();
$approvers = array();
$reviewers["i"] = array();
$reviewers["g"] = array();
$approvers["i"] = array();
$approvers["g"] = array();
$workflow = null;

if($settings->_workflowMode == 'traditional' || $settings->_workflowMode == 'traditional_only_approval') {
	if($settings->_workflowMode == 'traditional') {
		// Retrieve the list of individual reviewers from the form.
		if (isset($_POST["indReviewers"])) {
			foreach ($_POST["indReviewers"] as $ind) {
				$reviewers["i"][] = $ind;
			}
		}
		// Retrieve the list of reviewer groups from the form.
		if (isset($_POST["grpReviewers"])) {
			foreach ($_POST["grpReviewers"] as $grp) {
				$reviewers["g"][] = $grp;
			}
		}
	}

	// Retrieve the list of individual approvers from the form.
	if (isset($_POST["indApprovers"])) {
		foreach ($_POST["indApprovers"] as $ind) {
			$approvers["i"][] = $ind;
		}
	}
	// Retrieve the list of approver groups from the form.
	if (isset($_POST["grpApprovers"])) {
		foreach ($_POST["grpApprovers"] as $grp) {
			$approvers["g"][] = $grp;
		}
	}
	// add mandatory reviewers/approvers
	$docAccess = $folder->getReadAccessList($settings->_enableAdminRevApp, $settings->_enableOwnerRevApp);
	if($settings->_workflowMode == 'traditional') {
		$res=$user->getMandatoryReviewers();
		foreach ($res as $r){

			if ($r['reviewerUserID']!=0){
				foreach ($docAccess["users"] as $usr)
					if ($usr->getID()==$r['reviewerUserID']){
						$reviewers["i"][] = $r['reviewerUserID'];
						break;
					}
			}
			else if ($r['reviewerGroupID']!=0){
				foreach ($docAccess["groups"] as $grp)
					if ($grp->getID()==$r['reviewerGroupID']){
						$reviewers["g"][] = $r['reviewerGroupID'];
						break;
					}
			}
		}
	}
	$res=$user->getMandatoryApprovers();
	foreach ($res as $r){

		if ($r['approverUserID']!=0){
			foreach ($docAccess["users"] as $usr)
				if ($usr->getID()==$r['approverUserID']){
					$approvers["i"][] = $r['approverUserID'];
					break;
				}
		}
		else if ($r['approverGroupID']!=0){
			foreach ($docAccess["groups"] as $grp)
				if ($grp->getID()==$r['approverGroupID']){
					$approvers["g"][] = $r['approverGroupID'];
					break;
				}
		}
	}
} elseif($settings->_workflowMode == 'advanced') {
	if(!$workflows = $user->getMandatoryWorkflows()) {
		if(isset($_POST["workflow"]))
			$workflow = $dms->getWorkflow($_POST["workflow"]);
		else
			$workflow = null;
	} else {
		/* If there is excactly 1 mandatory workflow, then set no matter what has
		 * been posted in 'workflow', otherwise check if the posted workflow is in the
		 * list of mandatory workflows. If not, then take the first one.
		 */
		$workflow = array_shift($workflows);
		foreach($workflows as $mw)
			if($mw->getID() == $_POST['workflow']) {$workflow = $mw; break;}
	}
}

if($settings->_dropFolderDir) {
	if(isset($_POST["dropfolderfileform1"]) && $_POST["dropfolderfileform1"]) {
		$fullfile = $settings->_dropFolderDir.'/'.$user->getLogin().'/'.$_POST["dropfolderfileform1"];
		if(file_exists($fullfile)) {
			/* Check if a local file is uploaded as well */
			if(isset($_FILES["userfile"]['error'][0])) {
				if($_FILES["userfile"]['error'][0] != 0)
					$_FILES["userfile"] = array();
			}
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimetype = finfo_file($finfo, $fullfile);
			$_FILES["userfile"]['tmp_name'][] = $fullfile;
			$_FILES["userfile"]['type'][] = $mimetype;
			$_FILES["userfile"]['name'][] = $_POST["dropfolderfileform1"];
			$_FILES["userfile"]['size'][] = filesize($fullfile);
			$_FILES["userfile"]['error'][] = 0;
		}
	}
}

/* Check files for Errors first */
for ($file_num=0;$file_num<count($_FILES["userfile"]["tmp_name"]);$file_num++){
	if ($_FILES["userfile"]["size"][$file_num]==0) {
		UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("uploading_zerosize"));
	}
	if (is_uploaded_file($_FILES["userfile"]["tmp_name"][$file_num]) && $_FILES['userfile']['error'][$file_num]!=0){
		UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("uploading_failed"));
	}
}

for ($file_num=0;$file_num<count($_FILES["userfile"]["tmp_name"]);$file_num++)
{
	$userfiletmp = $_FILES["userfile"]["tmp_name"][$file_num];
	$userfiletype = $_FILES["userfile"]["type"][$file_num];
	$userfilename = $_FILES["userfile"]["name"][$file_num];
	
	$fileType = ".".pathinfo($userfilename, PATHINFO_EXTENSION);

	if($settings->_overrideMimeType) 
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$userfiletype = finfo_file($finfo, $userfiletmp);
	}

	if ((count($_FILES["userfile"]["tmp_name"])==1)&&($_POST["name"]!=""))
	{
		$name = trim($_POST["name"]);	
	}
		

	  else 
		{
			$name = basename($userfilename);
		}

	/* Check if name already exists in the folder */
	if(!$settings->_enableDuplicateDocNames) 
	{
		if($folder->hasDocumentByName($name)) 
		{
			UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("document_duplicate_name"));
		}
	}

	$cats = array();
	if($categories) {
		foreach($categories as $catid) {
			$cats[] = $dms->getDocumentCategory($catid);
		}
	}

	if(isset($GLOBALS['SEEDDMS_HOOKS']['addDocument'])) {
		foreach($GLOBALS['SEEDDMS_HOOKS']['addDocument'] as $hookObj) {
			if (method_exists($hookObj, 'preAddDocument')) {
				$hookObj->preAddDocument(array('name'=>&$name, 'comment'=>&$comment));
			}
		}
	}
	// $stringFecha="Fecha de clasificación";
	// $atributoFecha=$dms->getAttributeDefinitionByName($stringFecha);
	// $idAtributo=$atributoFecha->getID();	
 //    $fecha=$attributes[$idAtributo];
 //    ////////////////////////////////
 //    $stringArticulos="Fundamento legal (Art. 19 LAIP)";
 //    $atributoArticulos=$dms->getAttributeDefinitionByName($stringArticulos);
	// $idAtributo2=$atributoArticulos->getID();	
	//echo "id fundamento legal: ".$idAtributo2;
	//print_r($attributes);
    // $articulos=$attributes[$idAtributo2];
    // $acum="";
    // $v=1;
    // foreach ($articulos as $art) 
    // {
    // 	if($v==1)
    // 	{
    // 		$acum=$acum."".$art;
    // 	}
    // 	else
    // 	{
    // 		$acum=$acum.",".$art;
    // 	}
    // 	$v++;
    // }
    //echo "articulos: ".print_r($articulos);
    $comment="Comentario para doc del sistema de transiciòn ";
    //echo "comment: ".$comment;

    //$categoActas=$dms->getDocumentCategory(2)->getName();

    //exit;
	$filesize = SeedDMS_Core_File::fileSize($userfiletmp);
	$res = $folder->addDocument($name, $comment, $expires, $owner, $keywords,
															$cats, $userfiletmp, basename($userfilename),
	                            $fileType, $userfiletype, $sequence,
	                            $reviewers, $approvers, $reqversion,
	                            $version_comment, $attributes, $attributes_version, $workflow);

	if (is_bool($res) && !$res) 
	{
		UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("error_occured"));
	} 
	else 
	{
		$document = $res[0];

		/* Set access as specified in settings. */
		if($settings->_defaultAccessDocs) {
			if($settings->_defaultAccessDocs > 0 && $settings->_defaultAccessDocs < 4) {
				$document->setInheritAccess(0, true);
				$document->setDefaultAccess($settings->_defaultAccessDocs, true);
			}
		}

		if(isset($GLOBALS['SEEDDMS_HOOKS']['addDocument'])) {
			foreach($GLOBALS['SEEDDMS_HOOKS']['addDocument'] as $hookObj) {
				if (method_exists($hookObj, 'postAddDocument')) {
					$hookObj->postAddDocument($document);
				}
			}
		}
		if($settings->_enableFullSearch) {
			$index = $indexconf['Indexer']::open($settings->_luceneDir);
			if($index) {
				$indexconf['Indexer']::init($settings->_stopWordsFile);
				$index->addDocument(new $indexconf['IndexedDocument']($dms, $document, isset($settings->_converters['fulltext']) ? $settings->_converters['fulltext'] : null, !($filesize < $settings->_maxSizeForFullText)));
			}
		}

		/* Add a default notification for the owner of the document */
		if($settings->_enableOwnerNotification) {
			$res = $document->addNotify($user->getID(), true);
		}
		/* Check if additional notification shall be added */
		if(!empty($_POST['notification_users'])) {
			foreach($_POST['notification_users'] as $notuserid) {
				$notuser = $dms->getUser($notuserid);
				if($notuser) {
					if($document->getAccessMode($user) >= M_READ)
						$res = $document->addNotify($notuserid, true);
				}
			}
		}
		if(!empty($_POST['notification_groups'])) {
			foreach($_POST['notification_groups'] as $notgroupid) {
				$notgroup = $dms->getGroup($notgroupid);
				if($notgroup) {
					if($document->getGroupAccessMode($notgroup) >= M_READ)
						$res = $document->addNotify($notgroupid, false);
				}
			}
		}

		// Send notification to subscribers of folder.
		if($notifier) {
			$fnl = $folder->getNotifyList();
			$dnl = $document->getNotifyList();
			$nl = array(
				'users'=>array_merge($dnl['users'], $fnl['users']),
				'groups'=>array_merge($dnl['groups'], $fnl['groups'])
			);

			$subject = "new_document_email_subject";
			$message = "new_document_email_body";
			$params = array();
			$params['name'] = $name;
			$params['folder_name'] = $folder->getName();
			$params['folder_path'] = $folder->getFolderPathPlain();
			$params['username'] = $user->getFullName();
			$params['comment'] = $comment;
			$params['version_comment'] = $version_comment;
			$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewDocument.php?documentid=".$document->getID();
			$params['sitename'] = $settings->_siteName;
			$params['http_root'] = $settings->_httpRoot;
			$notifier->toList($user, $nl["users"], $subject, $message, $params);
			foreach ($nl["groups"] as $grp) {
				$notifier->toGroup($user, $grp, $subject, $message, $params);
			}

			if($workflow && $settings->_enableNotificationWorkflow) {
				$subject = "request_workflow_action_email_subject";
				$message = "request_workflow_action_email_body";
				$params = array();
				$params['name'] = $document->getName();
				$params['version'] = $reqversion;
				$params['workflow'] = $workflow->getName();
				$params['folder_path'] = $folder->getFolderPathPlain();
				$params['current_state'] = $workflow->getInitState()->getName();
				$params['username'] = $user->getFullName();
				$params['sitename'] = $settings->_siteName;
				$params['http_root'] = $settings->_httpRoot;
				$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewDocument.php?documentid=".$document->getID();

				foreach($workflow->getNextTransitions($workflow->getInitState()) as $ntransition) {
					foreach($ntransition->getUsers() as $tuser) {
						$notifier->toIndividual($user, $tuser->getUser(), $subject, $message, $params);
					}
					foreach($ntransition->getGroups() as $tuser) {
						$notifier->toGroup($user, $tuser->getGroup(), $subject, $message, $params);
					}
				}
			}

			if($settings->_enableNotificationAppRev) 
			{
				/* Reviewers and approvers will be informed about the new document */
				if($reviewers['i'] || $reviewers['g']) {
					$subject = "review_request_email_subject";
					$message = "review_request_email_body";
					$params = array();
					$params['name'] = $document->getName();
					$params['folder_path'] = $folder->getFolderPathPlain();
					$params['version'] = $reqversion;
					$params['comment'] = $comment;
					$params['username'] = $user->getFullName();
					$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewDocument.php?documentid=".$document->getID();
					$params['sitename'] = $settings->_siteName;
					$params['http_root'] = $settings->_httpRoot;

					foreach($reviewers['i'] as $reviewerid) {
						$notifier->toIndividual($user, $dms->getUser($reviewerid), $subject, $message, $params);
					}
					foreach($reviewers['g'] as $reviewergrpid) {
						$notifier->toGroup($user, $dms->getGroup($reviewergrpid), $subject, $message, $params);
					}
				}

				elseif($approvers['i'] || $approvers['g']) {
					$subject = "approval_request_email_subject";
					$message = "approval_request_email_body";
					$params = array();
					$params['name'] = $document->getName();
					$params['folder_path'] = $folder->getFolderPathPlain();
					$params['version'] = $reqversion;
					$params['comment'] = $comment;
					$params['username'] = $user->getFullName();
					$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewDocument.php?documentid=".$document->getID();
					$params['sitename'] = $settings->_siteName;
					$params['http_root'] = $settings->_httpRoot;

					foreach($approvers['i'] as $approverid) {
						$notifier->toIndividual($user, $dms->getUser($approverid), $subject, $message, $params);
					}
					foreach($approvers['g'] as $approvergrpid) {
						$notifier->toGroup($user, $dms->getGroup($approvergrpid), $subject, $message, $params);
					}
				}
			}
		}
		if($settings->_removeFromDropFolder) 
		{
			if(file_exists($userfiletmp)) 
			{
				unlink($userfiletmp);
			}
		}
	}
	
	add_log_line("?name=".$name."&folderid=".$folderid);
}

header("Location:../out/out.ViewFolder.php?folderid=".$folderid."&showtree=".$_POST["showtree"]);

?>