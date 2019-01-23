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
//tabla seeddms.tblattributedefinitions;
 //generan
if ($user->isGuest()) 
{
	UI::exitError(getMLText("my_documents"),getMLText("access_denied"));
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

    if(isset($GLOBALS['SEEDDMS_HOOKS']['addDocument'])) {
        foreach($GLOBALS['SEEDDMS_HOOKS']['addDocument'] as $hookObj) {
            if (method_exists($hookObj, 'preAddDocument')) {
                $hookObj->preAddDocument(array('name'=>&$name, 'comment'=>&$comment));
            }
        }
    }







//////////////////////////////////////////////////////////////////////////////////////////////////////


$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$view = UI::factory($theme, $tmp[1], array('dms'=>$dms, 'user'=>$user));
//variables con mi contenido
 $arrayNumeros=array();
 $arrayRubros=array();
 $arrayTipos=array();
 $arrayParciales=array();
 $arrayMotivos=array();
 $arrayFundamentos=array();
 $arrayUnidades=array();
 $arrayUnidadesGene=array();
 $arrayAutoridades=array();
 $arrayFechas=array();
 $arrayCaduca=array();
 $archivo=array();

//compruebo y obtengo
if (isset($_POST["arrayNumeros"])) 
{
    $arrayNumeros=$_POST["arrayNumeros"];
    //print_r($arrayNumeros);

}
if (isset($_POST["arrayRubros"])) 
{
    $arrayRubros=$_POST["arrayRubros"];

}
if (isset($_POST["arrayTipos"])) 
{
    $arrayTipos=$_POST["arrayTipos"];

}
if (isset($_POST["arrayParciales"])) 
{
    $arrayParciales=$_POST["arrayParciales"];
}

if (isset($_POST["arrayMotivos"])) 
{
    $arrayMotivos=$_POST["arrayMotivos"];

}
if (isset($_POST["arrayFundamentos"])) //fundamentos en un doble array,
{
    $arrayFundamentos=$_POST["arrayFundamentos"];

}
if (isset($_POST["arrayUnidades"])) 
{
    $arrayUnidades=$_POST["arrayUnidades"];

}
if (isset($_POST["arrayUnidadesGene"])) 
{
    $arrayUnidadesGene=$_POST["arrayUnidadesGene"];

}
if (isset($_POST["arrayAutoridades"])) 
{
    $arrayAutoridades=$_POST["arrayAutoridades"];

}
if (isset($_POST["arrayFechas"])) 
{
    $arrayFechas=$_POST["arrayFechas"];

}
if (isset($_POST["arrayCaduca"])) 
{
    $arrayCaduca=$_POST["arrayCaduca"];

}
$arrayNombresFichero=array();
$arrayTiposFichero=array();
$arrayUbicacionesFichero=array();
if (isset($_FILES["archivo"])) 
{
	//echo "HAY FILE";
    //print_r($_FILES);
    $archivo=$_FILES["archivo"];
    $len = count($_FILES['archivo']['name']);

    for($i = 0; $i < $len; $i++) 
    {
       $arrayNombresFichero[] = $_FILES['archivo']['name'][$i];
       $arrayTiposFichero[]=$_FILES['archivo']['type'][$i];
       $arrayUbicacionesFichero[]=$_FILES['archivo']['tmp_name'][$i];

    }
}
//OJO: ESTAS LAS SAQUE DEL BUCLE PARA OPTIMIZAR, SON LO MISMO SIEMPRE
    $atributoNumRef=$dms->getAttributeDefinitionByName("No. de Declaración de Reserva"); 
    $idNumRef=$atributoNumRef->getID();
    $atributoTipo=$dms->getAttributeDefinitionByName("Tipo de clasificación"); 
    $idTipo=$atributoTipo->getID();
    $atributoDetalle=$dms->getAttributeDefinitionByName("Detalle de la reserva parcial"); 
    $idDetalle=$atributoDetalle->getID();
    $atributoMotivo=$dms->getAttributeDefinitionByName("Motivo de la reserva"); 
    $idMotivo=$atributoMotivo->getID();
    $atributoFundamento=$dms->getAttributeDefinitionByName("Fundamento legal (Art. 19 LAIP)"); 
    $idFundamento=$atributoFundamento->getID();
    $atributoUnidad=$dms->getAttributeDefinitionByName("Unidad Administrativa"); 
    $idUnidad=$atributoUnidad->getID();
    $atributoUnidadGene=$dms->getAttributeDefinitionByName("Unidad Generadora de la Información"); 
    $idUnidadGene=$atributoUnidadGene->getID();
    $atributoAutoridad=$dms->getAttributeDefinitionByName("Autoridad que reserva"); 
    $idAutoridad=$atributoAutoridad->getID();
    $atributoFecha=$dms->getAttributeDefinitionByName("Fecha de clasificación"); 
    $idFecha=$atributoFecha->getID();

    $arrayCategorias=array();
    $catego=$dms->getDocumentCategoryByName("Declaratorias de reserva");
    $idCatego=$catego->getID(); 
    $arrayCategorias[]=$catego;
////////////////// PARTE INTERESANTE: SUBIDA DE LOS ARCHIVOS USANDO LA API ////////////////////////////
$idCarpeta=getDefaultUserFolder($user->getID());
$carpeta=$dms->getFolder($idCarpeta);
$nombreCarpeta=$carpeta->getName();
//echo "Nombre carpeta: ".$nombreCarpeta;
//Cumplimento campos d e subida
$cantidadSubida=count($arrayNumeros);
//bucle principal
for($i=0;$i<$cantidadSubida;$i++)
{
   
    /* Check if name already exists in the folder */

    $numero=$arrayNumeros[$i]; 
    $rubro=$arrayRubros[$i];   
    if(!$settings->_enableDuplicateDocNames) 
    {
        if($folder->hasDocumentByName($rubro)) {
            UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("document_duplicate_name"));
        }
    }
    $tipo=$arrayTipos[$i];    
    $detalleParcial=$arrayParciales[$i];
    $motivo=$arrayMotivos[$i];
    $literales=$arrayFundamentos[$i];//ojo, es un array a su vez.
    $meraletra="";
    foreach ($literales as $lit) 
    {
        $ex=explode(")", $lit);
        //echo "ex:".$ex[0];
        $meraletra=$meraletra.$ex[0].",";
    }
    $unidadAdministrativa=$arrayUnidades[$i];
    $unidadAdministrativaGene=$arrayUnidadesGene[$i];
    $autoridad=$arrayAutoridades[$i];
    $fechaReserva=$arrayFechas[$i];
    $fechaCaduca=$arrayCaduca[$i];
    $caducaUnix=strtotime($fechaCaduca." 00:00:00");
    //echo "TIMESTAP: ".$caducaUnix;
    $ubicacionFichero=$arrayUbicacionesFichero[$i];
    //
    $comment="Documento cuya reserva es válida desde el ".$fechaReserva." sustentado en literales ".$meraletra." del artículo 19 (LAIP)"; 
    $owner=$user; //el dueño será el administrador que tiene id1
    $keywords="";//sin palabras clave
    //categories: en este caso la categoria sera siempre reserva
   
    $rutaFichero=$ubicacionFichero;
    $orgFileName=$rubro; //original file name ES EL RUBRO EL NOMBRE PRIMER CAMPO DEL FORMULARIO
    $extension=explode(".", $arrayNombresFichero[$i]);
    $extension=$extension[1]; //ojo, cambiar y poner filetype como op.AddDocument
     $fileType =".".$extension;
     $mimeType=$arrayTiposFichero[$i];
     $sequence=1; //secuencia de meter al final
     // $reviewers=array();
     // $approvers=array();
     // $administrador=$dms->getUser(1);
     // $approvers[]=$administrador; //que el admin deba aprobar
     $reqversion=1;
    $version_comment="";
     //echo "Ruta de fichero: ".$rutaFichero."; filetype: ".$fileType."<br>"."; mimeType: ".$mimeType;
    $atributos=array(); //FUNCIONAMIENTO: LOS ATRIBUTOS DE SEEDDMS SON UN ARRAY MULTIPLE, CADA ATRIBUTO como array o texto va en en ese array multiple en la posicion indicada por el ID de ese attribute definition. Por ejemplo, si el ID del atributo 'No. de Declaración de Reserva'
    //fuese el 15 (ejemplo), tendria que meter en mi array de atributos en la posicion 15 ese valor: atributos[15]="valor"
    // y al final, al llamar a la API addDocument(), pasar como referencia ese array que creé para atributos, que lleva en los indices correctos
    //mis valores de atributos
    //
                //No. de Declaración de Reserva: a calcular, mejor no meter a mano
                //Tipo de clasificación
                //Detalle de la reserva parcial
                //Motivo de la reserva
                //Fundamento legal (Art. 19 LAIP)
                //Unidad Administrativa
                //Autoridad que reserva
                //Fecha de clasificación
                 //TODAS ESTAS FUERON CALCULADAS FUERA DEL BUCLE, SERAN LAS MISMAS SIEMPRE Y OPTIMIZO EL BUCLE
    $atributos[$idNumRef]=$numero;
    $atributos[$idTipo]=$tipo;
    $atributos[$idDetalle]=$detalleParcial;
    $atributos[$idMotivo]=$motivo;
    $atributos[$idFundamento]=$literales;
    $atributos[$idUnidad]=$unidadAdministrativa;
    $atributos[$idUnidadGene]=$unidadAdministrativaGene;
    $atributos[$idAutoridad]=$autoridad;
    $atributos[$idFecha]=$fechaReserva;
    ////llamada a función que añade. SEGUN LA API:
        /**
        addDocument(string $name, string $comment, integer $expires, object $owner, string $keywords, array $categories, string $tmpFile, 
        string $orgFileName, string $fileType, string $mimeType, float $sequence, array $reviewers, array $approvers, string $reqversion, 
        string $version_comment, array $attributes, array $version_attributes,  $workflow) : \array/boolean
        **/       
        $res=$carpeta->addDocument($rubro,$comment,$caducaUnix,$owner,$keywords,$arrayCategorias,$rutaFichero,$orgFileName,$fileType,$mimeType,$sequence,$reviewers,$approvers,$reqversion,$version_comment,$atributos,NULL,$workflow);
        if (is_bool($res) && !$res) 
        {
            UI::exitError(getMLText("folder_title", "Error al insertar documentos múltiples"));
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
        if($notifier) 
        {
            $fnl = $folder->getNotifyList();
            $dnl = $document->getNotifyList();
            $nl = array(
                'users'=>array_merge($dnl['users'], $fnl['users']),
                'groups'=>array_merge($dnl['groups'], $fnl['groups'])
            );

            $subject = "new_document_email_subject";
            $message = "new_document_email_body";
            $params = array();
            $params['name'] = $rubro;
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

    }


}//fin bucle principal
////////////////////////////////////////////////////////////////////////////////////////////////////////
if($view) 
{
	
	$view->setParam('cachedir', $settings->_cacheDir);
	$view->setParam('previewWidthList', $settings->_previewWidthList);
	$view->setParam('timeout', $settings->_cmdTimeout);
	$view->setParam('arrayNumeros', $arrayNumeros);
	$view->setParam('arrayRubros', $arrayRubros);
	$view->setParam('arrayTipos', $arrayTipos);
	$view->setParam('arrayParciales', $arrayParciales);
	$view->setParam('arrayMotivos', $arrayMotivos);
	$view->setParam('arrayFundamentos', $arrayFundamentos);
	$view->setParam('arrayUnidades', $arrayUnidades);
	$view->setParam('arrayAutoridades', $arrayAutoridades);
	$view->setParam('arrayFechas', $arrayFechas);
	$view->setParam('arrayCaduca', $arrayCaduca);
	$view->setParam('archivo', $archivo);
    $view->setParam('cantidadSubida', $cantidadSubida);//numero de archivos subidos en esta operación

	$view($_GET);
	exit;
}
?>
