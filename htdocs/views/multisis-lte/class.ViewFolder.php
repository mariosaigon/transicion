<?php
/**
 * Implementation of ViewFolder view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
/**
 * Include parent class
 */
require_once("class.Bootstrap.php");
/**
 * Include class to preview documents
 */
require_once("SeedDMS/Preview.php");
/**
 * Class which outputs the html page for ViewFolder view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
function esRaiz($folder)
{
	$ruta=$folder->getPath();
	if(count($ruta)==3)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function vacio($folder,$user)
{
	$totales=$folder->countChildren($user,0);
	if($totales['document_count']==0 && $totales['folder_count']==0)
	{
		return true;
	}
	else 
	{
		return false;
	}
}
//cuenta recursivamente docs en un folder

function dameAvance($numCarpeta,$folder,$user)
{
	$subCarpetas=$folder->getSubFolders("n");
    $totalLlenos=0;
   switch ($numCarpeta) 
   {
   	    case 1: 
   		$carpeta1=$subCarpetas[0];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 2: 
   		$carpeta1=$subCarpetas[1];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 3: 
   		$carpeta1=$subCarpetas[2];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 4: 
   		$carpeta1=$subCarpetas[3];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 5: 
   		$carpeta1=$subCarpetas[4];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 6: 
   		$carpeta1=$subCarpetas[5];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////
   		case 7: 
   		$carpeta1=$subCarpetas[6];//esto es un objeto
   		$ninos=$carpeta1->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		break;
   		/////////////


   	default:
   		# code...
   		break;
   }

   return $totalLlenos;
}
function buscarDocumentosCaducados($baseDatos,$usuario,$days,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria)
	{
		$startts = strtotime("midnight", time());//fecha de la consulta
		$informwatcher=FALSE;
		//echo "Checking for documents expiring between ".date('Y-m-d', $startts)." and ".date('Y-m-d', $startts+($days-1)*86400)."\n";

                //de cada usuario obtengo los grupos a los cuales pertences
		        //echo "Usuario: ".$usuario->getFullname()."\n";
				$groups = $usuario->getGroups();
				$groupids = array();
				foreach($groups as $group)
				{
				   $groupids[] = $group->getID();
				}

				///hacemos la consulta como tal
				//construyo la consulta

				if (!$baseDatos->createTemporaryTable("ttstatid") || !$baseDatos->createTemporaryTable("ttcontentid")) 
				{
	echo getMLText("internal_error_exit")."\n";
	exit;
                }
	//echo "creada tabla temporal";
					/**$queryStr = "SELECT DISTINCT a.*, tblDocumentStatusLog.* FROM `tblDocuments` a ".
		"LEFT JOIN `tblDocumentContent` ON `a`.`id` = `tblDocumentContent`.`document` ".
		"LEFT JOIN `tblNotify` b ON a.id=b.target ".
		"LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` ".
		"LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.     `document` ".
		"LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.  `statusID` ".
		"LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` ".
		"WHERE (a.`owner` = '".$usuario->getID()."' ".
		($informwatcher ? " OR ((b.userID = '".$usuario->getID()."' ".
		($groupids ? "or b.groupID in (".implode(',', $groupids).")" : "").") ".
		"AND b.targetType = 2) " : "").
		") AND a.`expires` < '".($startts + $days*86400)."' ".
		"AND a.`expires` > '".($startts)."' ";
		**/


		$queryStr2 = "SELECT `tblDocuments`.* FROM `tblDocuments`".
		"WHERE `tblDocuments`.`owner` = '".$usuario->getID()."' ".
		"AND `tblDocuments`.`expires` < '".($startts + $days*86400)."' ".
		"AND `tblDocuments`.`expires` > '".($startts)."'";
			
	       //llamo a bd
		//echo "Query q estoy haciendo: ".$queryStr2;
			$resArr = $baseDatos->getResultArray($queryStr2);
			if (is_bool($resArr) && !$resArr) 
			{
				echo getMLText("internal_error_exit")."\n";
				exit;
			}
			return $resArr;
			      
	}
 function imprimirTipos() //imprime los tipos de clasificacion que se usan en el formulario de multisubida
{
	
  $titulos=array("Total","Parcial");
	//echo " <select class=\"form-control select\"  name=\"titulosObtenidos[]\">";
  echo "<option disabled selected value>Seleccione una opción</option>";
    foreach ($titulos as $doc) 
    {
		echo "<option value=\"".$doc."\">".$doc."</option>";
	} //fin del bucle

	//echo "</select>";

}

function getUserFromFolder($id_folder) //dado un id de folder, me devuelve el id del usuario (oficial) de esa carpeta
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$id_user=0;
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
	//$miQuery="SELECT homefolder FROM seeddms.tblusers WHERE id=".$id_usuario;
	$miQuery="SELECT id FROM tblUsers WHERE homefolder=$id_folder";
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	if($resultado)
	{
		$id_user=$resultado[0]['id'];
	}
	
	//echo "id_folder: ".$id_folder;
	return $id_user;
}

 function imprimirParametrosShort($tabla,$columna,$id,$db)
{
 $consultar="SELECT id, $columna FROM $tabla WHERE idUsuario=$id";
          //echo "Consultar: ".$consultar;
     $res1 = $db->getResultArray($consultar);
  	$serial=$tabla."_".$id;

// comienzo bucle
  echo "<option disabled selected value>Seleccione un elemento de la lista</option>";

    foreach ($res1 as $doc) 
    {
    	$valor=$doc[$columna];
  	    $idRegistro=$doc['id']; //id único de la entrada en la tabla
    	echo "<option value=\"".$valor."\">".$valor."</option>";
    }


}// fin de imprimir parametros shorti

 function imprimirParametros($tabla,$columna,$id,$db,$inputID,$inputName)
{
 $consultar="SELECT id, $columna FROM $tabla WHERE idUsuario=$id";
          //echo "Consultar: ".$consultar;
     $res1 = $db->getResultArray($consultar);
  	$serial=$tabla."_".$id;
echo " <select class=\"form-control chzn-select\" id=\"$inputID\" name=\"$inputName\">";
// comienzo bucle
  echo "<option disabled selected value>Seleccione un elemento de la lista</option>";

    foreach ($res1 as $doc) 
    {
    	$valor=$doc[$columna];
  	    $idRegistro=$doc['id']; //id único de la entrada en la tabla
    	echo "<option value=\"".$valor."\">".$valor."</option>";
    }


	echo "</select>";
}// fin de imprimir países

class SeedDMS_View_ViewFolder extends SeedDMS_Bootstrap_Style {
	function getAccessModeText($defMode) { /* {{{ */
		switch($defMode) {
			case M_NONE:
				return getMLText("access_mode_none");
				break;
			case M_READ:
				return getMLText("access_mode_read");
				break;
			case M_READWRITE:
				return getMLText("access_mode_readwrite");
				break;
			case M_ALL:
				return getMLText("access_mode_all");
				break;
		}
	} /* }}} */
	function printAccessList($obj) { /* {{{ */
		$accessList = $obj->getAccessList();
		if (count($accessList["users"]) == 0 && count($accessList["groups"]) == 0)
			return;
		$content = '';
		for ($i = 0; $i < count($accessList["groups"]); $i++)
		{
			$group = $accessList["groups"][$i]->getGroup();
			$accesstext = $this->getAccessModeText($accessList["groups"][$i]->getMode());
			$content .= $accesstext.": ".htmlspecialchars($group->getName());
			if ($i+1 < count($accessList["groups"]) || count($accessList["users"]) > 0)
				$content .= "<br />";
		}
		for ($i = 0; $i < count($accessList["users"]); $i++)
		{
			$user = $accessList["users"][$i]->getUser();
			$accesstext = $this->getAccessModeText($accessList["users"][$i]->getMode());
			$content .= $accesstext.": ".htmlspecialchars($user->getFullName());
			if ($i+1 < count($accessList["users"]))
				$content .= "<br />";
		}
		if(count($accessList["groups"]) + count($accessList["users"]) > 3) {
			$this->printPopupBox(getMLText('list_access_rights'), $content);
		} else {
			echo $content;
		}
	} /* }}} */
	function js() { /* {{{ */
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$orderby = $this->params['orderby'];
		$expandFolderTree = $this->params['expandFolderTree'];
		$enableDropUpload = $this->params['enableDropUpload'];
		header('Content-Type: application/javascript; charset=UTF-8');
		parent::jsTranslations(array('cancel', 'splash_move_document', 'confirm_move_document', 'move_document', 'splash_move_folder', 'confirm_move_folder', 'move_folder'));
		
?>

function folderSelected(id, name) {
	window.location = '../out/out.ViewFolder.php?folderid=' + id;
}

function checkForm() {
	msg = new Array();
	if (document.form1.name.value == "") msg.push("<?php printMLText("js_no_name");?>");
	if (document.form1.comment.value == "") msg.push("<?php printMLText("js_no_comment");?>");
	if (msg != "") {
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

function checkForm2() {
	msg = new Array();
	if (document.form2.name.value == "") msg.push("<?php printMLText("js_no_name");?>");
	if (document.form2.comment.value == "") msg.push("<?php printMLText("js_no_comment");?>");
	/*if (document.form2.expdate.value == "") msg.push("<?php printMLText("js_no_expdate");?>");*/
	if (document.form2.theuserfile.value == "") msg.push("<?php printMLText("js_no_file");?>");
	if (msg != "") {
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

$(document).ajaxStart(function() { Pace.restart(); });
//  $('.ajax').click(function(){
//    $.ajax({url: '#', success: function(result){
//    $('.ajax-content').html('<hr>Ajax Request Completed !');
//  }});
//});

$(document).ready(function(){
	
	$('body').on('submit', '#form1', function(ev){
		if(!checkForm()) {
			ev.preventDefault();
		} else {
			$("#box-form1").append("<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>");
		}
	});

	$('body').on('submit', '#form2', function(ev){
		if(!checkForm2()){
			ev.preventDefault();
		} else {
			$("#box-form2").append("<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>");
		}
	});

	$("#form1").validate({
		invalidHandler: function(e, validator) {
			noty({
				text:  (validator.numberOfInvalids() == 1) ? "<?php printMLText("js_form_error");?>".replace('#', validator.numberOfInvalids()) : "<?php printMLText("js_form_errors");?>".replace('#', validator.numberOfInvalids()),
				type: 'error',
				dismissQueue: true,
				layout: 'topRight',
				theme: 'defaultTheme',
				timeout: 1500,
			});
		},
		messages: {
			name: "<?php printMLText("js_no_name");?>",
			comment: "<?php printMLText("js_no_comment");?>"
		},
	});

	$("#form2").validate({
		invalidHandler: function(e, validator) {
			noty({
				text:  (validator.numberOfInvalids() == 1) ? "<?php printMLText("js_form_error");?>".replace('#', validator.numberOfInvalids()) : "<?php printMLText("js_form_errors");?>".replace('#', validator.numberOfInvalids()),
				type: 'error',
				dismissQueue: true,
				layout: 'topRight',
				theme: 'defaultTheme',
				timeout: 1500,
			});
		},
		messages: {
			name: "<?php printMLText("js_no_name");?>",
			comment: "<?php printMLText("js_no_comment");?>",
			/*expdate: "<?php printMLText("js_no_expdate");?>",*/
			theuserfile: "<?php printMLText("js_no_file");?>",
		},
	});

	$("#add-folder").on("click", function()
	{
 		  $("#div-add-folder").show('slow');
  });

  $("#cancel-add-folder").on("click", function(){
 		  $("#div-add-folder").hide('slow');
  });

  $("#add-document").on("click", function()
  {
	   
 		  $("#div-add-document").show('slow');
  });
  
  $("#add-acta-inexistencia").on("click", function()
  {
	   
 		  $("#div-add-pene").show('slow');
  });

   $("#add-multiple-document").on("click", function()
  {
	   
 		  $("#div-add-multiple-reservas").show('slow');
  });

  
  
  $(".cancel-add-document").on("click", function(){
 		  $("#div-add-document").hide('slow');
  });

  $(".move-doc-btn").on("click", function(ev){
  	id = $(ev.currentTarget).attr('rel');
 		$("#table-move-document-"+id).show('slow');
  });

  $(".cancel-doc-mv").on("click", function(ev){
  	id = $(ev.currentTarget).attr('rel');
 		$("#table-move-document-"+id).hide('slow');
  });

  $(".move-folder-btn").on("click", function(ev){
  	id = $(ev.currentTarget).attr('rel');
 		$("#table-move-folder-"+id).show('slow');
  });

  $(".cancel-folder-mv").on("click", function(ev){
  	id = $(ev.currentTarget).attr('rel');
 		$("#table-move-folder-"+id).hide('slow');
  });
  
/* ---- Para animar los formularios de subida y sus 3 pestañas al darle a la flechita que apunta hacia la derecha

CAMBIADO: 12/09/17 JOSE MARIO LOPEZ LEIVA---- */
 /* ---- subida de declaratorias de reserva (normal) ---- */
  $("#btn-next-1").on("click", function(){
  	$("#nav-tab-1").removeClass("active");
  	$("#nav-tab-2").addClass("active");
  	$('html, body').animate({scrollTop: 0}, 800);
  });

  $("#btn-next-2").on("click", function(){
  	$("#nav-tab-2").removeClass("active");
  	$("#nav-tab-3").addClass("active");
  	$('html, body').animate({scrollTop: 0}, 800);
  });
  
   /* ---- subida de actas de inexistencia---- */
  $("#btn-next-1_acta").on("click", function(){
  	$("#nav-tab-1_acta").removeClass("active");
  	$("#nav-tab-2_acta").addClass("active");
  	$('html, body').animate({scrollTop: 0}, 800);
  });

  $("#btn-next-2_acta").on("click", function(){
  	$("#nav-tab-2_acta").removeClass("active");
  	$("#nav-tab-3_acta").addClass("active");
  	$('html, body').animate({scrollTop: 0}, 800);
  });
   /* ---- FIN SCROLL CON FLECHA A LA DERECHA---- */
  
  
  

  /* ---- For document previews ---- */

  $(".preview-doc-btn").on("click", function(){
  	$("#div-add-folder").hide();
		$("#div-add-document").hide();
  	$("#folder-content").hide();

  	var docID = $(this).attr("id");
  	var version = $(this).attr("rel");
  	$("#doc-title").text($(this).attr("title"));
  	$("#document-previewer").show('slow');
  	$("#iframe-charger").attr("src","../pdfviewer/web/viewer.html?file=..%2F..%2Fop%2Fop.Download.php%3Fdocumentid%3D"+docID+"%26version%3D"+version);
  });

  $(".close-doc-preview").on("click", function(){
  	$("#document-previewer").hide();
  	$("#iframe-charger").attr("src","");
  	$("#folder-content").show('slow');
  });
  


});
<?php
		if ($enableDropUpload && $folder->getAccessMode($user) >= M_READWRITE) {
			echo "SeedDMSUpload.setUrl('../op/op.Ajax.php');";
			echo "SeedDMSUpload.setAbortBtnLabel('".getMLText("cancel")."');";
			echo "SeedDMSUpload.setEditBtnLabel('".getMLText("edit_document_props")."');";
			echo "SeedDMSUpload.setMaxFileSize(".SeedDMS_Core_File::parse_filesize(ini_get("upload_max_filesize")).");";
			echo "SeedDMSUpload.setMaxFileSizeMsg('".getMLText("uploading_maxsize")."');";
		}
		$this->printDeleteFolderButtonJs();
		$this->printDeleteDocumentButtonJs();
		$this->printKeywordChooserJs("form2");
		$this->printFolderChooserJs("form3");
		$this->printFolderChooserJs("form4");
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$db = $dms->getDB();
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$orderby = $this->params['orderby'];
		$enableFolderTree = $this->params['enableFolderTree'];
		$enableClipboard = $this->params['enableclipboard'];
		$enableDropUpload = $this->params['enableDropUpload'];
		$expandFolderTree = $this->params['expandFolderTree'];
		$showtree = $this->params['showtree'];
		$cachedir = $this->params['cachedir'];
		$workflowmode = $this->params['workflowmode'];
		$enableRecursiveCount = $this->params['enableRecursiveCount'];
		$maxRecursiveCount = $this->params['maxRecursiveCount'];
		$previewwidth = $this->params['previewWidthList'];
		$timeout = $this->params['timeout'];
		$folderid = $folder->getId();

		$baseServer = $this->params['baseServer'];
		$this->htmlAddHeader('<link href="../styles/'.$this->theme.'/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">'."\n", 'css');
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/datatables/jquery.dataTables.min.js"></script>'."\n", 'js');
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/datatables/dataTables.bootstrap.min.js"></script>'."\n", 'js');
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/validate/jquery.validate.js"></script>'."\n", 'js');
		
		echo $this->callHook('startPage');
		if($user->isAdmin())
		{
			$this->htmlStartPage("página de inicio del sistema de gestión de la transición", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("página de inicio institucional".$folder->getName(), "skin-blue layout-top-nav");
		}
		
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar($folder->getID(),0,0);
		}
		
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
		echo $this->callHook('preContent');
		$this->contentStart();	

		echo $this->getFolderPathHTML($folder);
		echo "<div class=\"row\">";
		if($user->isGuest())
		{
			echo ' <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
           <li class="active">Directorio de índices de reserva</li>
        </ol>';
		}
		////////////////////// INICIO MINI DASHBOARD ////////////////
		if(!$user->isAdmin() && !$user->isGuest())
		{
			$usuario_particular=$dms->getUser($user->getID());
		$id_numero_declaratoria=6;
		$resultados30=buscarDocumentosCaducados($db,$usuario_particular,30,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria);
		$conteo30=intval(count($resultados30));
		$array30=array();
		foreach ($resultados30 as $r30) 
		{
			$rubro=$r30['id'];
			$array30[]=$rubro;
		}
		//// 7 meses
		$resultados7=buscarDocumentosCaducados($db,$usuario_particular,213,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria);
		$conteo7=intval(count($resultados7));
		$array7=array();
		foreach ($resultados7 as $r7) 
		{
			$rubro=$r7['id'];
			$array7[]=$rubro;
		}



		echo ' <div class="col-md-3 col-sm-6 col-xs-12"> </div>';	


		echo ' <div class="col-md-3 col-sm-6 col-xs-12">';
        echo ' <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> INDICACIONES!</h4>';
               echo "En esta sección deberá subir un documento que contenga lo siguiente:";
               $rutaFoto=$baseServer."images/formato2.PNG";

           // <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
           //      Launch Default Modal
           //    </button>

           echo "<img src=\"".$rutaFoto."\" alt=\"Contenido del documento\" height=\"150\" width=\"200\" data-toggle=\"modal\" data-target=\"#modal-default\" >";
              echo "</div>";
              
        echo '</div>';	

        echo '<div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-warning"></i></span>
            <div class="info-box-content">';
              echo "<span class=\"info-box-text\">Documentos faltantes para el informe de transición</span>";
              echo "<span class=\"info-box-number\">".$conteo30."</span>";

              echo '<div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">';
                  echo "De un total de 34 documentos";

                    echo "</span>";
            echo '</div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>';
		}

		if($user->isGuest())
		{
			echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Directorio de índices de reserva</h4>
                Indicación: navegue por la lista de entes obligados, del cual podrá ver el índice de reserva actual.
              </div>';
		}	
	
        // FIN MINI DASHBOARD ///////////////7
		//// Add Folder ////
		echo "<div class=\"col-md-12 div-hidden\" id=\"div-add-folder\">";
		echo "<div class=\"box box-success div-green-border\" id=\"box-form1\">";
    echo "<div class=\"box-header with-border\">";
    echo "<h3 class=\"box-title\">".getMLText("add_subfolder")."</h3>";
    echo "<div class=\"box-tools pull-right\">";
    echo "<button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>";
    echo "</div>";
    echo "<!-- /.box-tools -->";
    echo "</div>";
    echo "<!-- /.box-header -->";
    echo "<div class=\"box-body\">";

    ?>
    <form class="form-horizontal" action="../op/op.AddSubFolder.php" id="form1" name="form1" method="post">
			<?php echo createHiddenFieldWithKey('addsubfolder'); ?>
			<input type="hidden" name="folderid" value="<?php print $folder->getId();?>">
			<input type="hidden" name="showtree" value="<?php echo showtree();?>">
			<input type="hidden" name="idUser" id="idUser" value="<?php print $user->getID();?>">
			<div class="box-body">

				<div class="form-group">
					<label class="col-sm-2 control-label"><?php printMLText("name");?>:</label>
					<div class="col-sm-10"><input class="form-control" type="text" name="name" size="60" required></div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><?php printMLText("comment");?>:</label>
					<div class="col-sm-10"><textarea class="form-control" name="comment" rows="4" cols="80"></textarea></div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><?php printMLText("sequence");?>:</label>
					<div class="col-sm-10">
						<?php $this->printSequenceChooser($folder->getSubFolders('s')); if($orderby != 's') echo "<br />".getMLText('order_by_sequence_off');?>
					</div>
				</div>
				<?php
					$attrdefs = $dms->getAllAttributeDefinitions(array(SeedDMS_Core_AttributeDefinition::objtype_folder, SeedDMS_Core_AttributeDefinition::objtype_all));
					if($attrdefs) {
						foreach($attrdefs as $attrdef) {
						?>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo htmlspecialchars($attrdef->getName()); ?>:</label>
							<div class="col-sm-10"><?php $this->printAttributeEditField($attrdef, '') ?></div>
						</div>
						<?php
						}
					}
				?>
				<div class="box-footer">
					<a id="cancel-add-folder" type="button" class="btn btn-default"><?php echo getMLText("cancel"); ?></a type="button">
					<button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> <?php printMLText("save")?></button>
				</div>
		</div>
		</form>

    <?php
    echo "</div>";
    echo "<!-- /.box-body -->";
    echo "</div>";
		echo "</div>";
//PEGATR AQUI 
		
		
	//// Add Document ////
		echo "<div class=\"col-md-12 div-hidden\" id=\"div-add-document\">";
		echo "<div class=\"box box-warning div-bkg-color\" id=\"box-form2\">";
    echo "<div class=\"box-header with-border\">";
    echo "<h3 class=\"box-title\">".getMLText("add_document")."</h3>";
    echo "<div class=\"box-tools pull-right\">";
    echo "<button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>";
    echo "</div>";
    echo "<!-- /.box-tools -->";
    echo "</div>";
    echo "<!-- /.box-header -->";
    echo "<div class=\"box-body\">";
    ?>

   	<form action="../op/op.AddDocument.php" enctype="multipart/form-data" method="post" id="form2" name="form2">
		<?php echo createHiddenFieldWithKey('adddocument'); ?>
		<input type="hidden" name="folderid" value="<?php print $folderid; ?>">
		<input type="hidden" name="showtree" value="<?php echo showtree();?>">
			<div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" id="nav-tab-1"><a href="#tab_1" data-toggle="tab" aria-expanded="true">1 - <?php echo getMLText("document_infos"); ?></a></li>
          <li class="" id="nav-tab-2"><a href="#tab_2" data-toggle="tab" aria-expanded="false">2 - <?php echo getMLText("version_info"); ?></a></li>
          <li class="" id="nav-tab-3"><a href="#tab_3" data-toggle="tab" aria-expanded="false">3 - <?php echo getMLText("add_document_notify"); ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
			    	<div class="form-group">
	            <label><?php echo getMLText("name"); ?>: <span class="is-required">*</span></label>
	            <input type="text" class="form-control" name="name" id="" placeholder="" required>
	          </div>
	          <div class="form-group">
	            <label><?php echo getMLText("comment"); ?>: <span class="is-required">*</span></label>
	            <textarea name="comment" class="form-control" rows="3" placeholder="" required></textarea>
	          </div>
	          <div class="form-group">
	            <label><?php echo getMLText("keywords");?>:</label>
	            <?php $this->printKeywordChooserHtml("form2");?>
	          </div>
	          <div class="form-group">
	            <label><?php printMLText("categories")?>:</label>
	            <select class="form-control chzn-select" name="categories[]" multiple="multiple" data-no_results_text="<?php printMLText('unknown_document_category'); ?>">
							<?php
								$categories = $dms->getDocumentCategories();
								foreach($categories as $category) {
									echo "<option value=\"".$category->getID()."\"";
									echo ">".$category->getName()."</option>";	
								}
							?>
							</select>
	          </div>
	          <div class="form-group">
	            <label><?php printMLText("sequence");?>:</label>
	            <?php $this->printSequenceChooser($folder->getDocuments('s')); if($orderby != 's') echo "<br />".getMLText('order_by_sequence_off'); ?>
	          </div>
	          <div class="form-group">
	          	<?php if($user->isAdmin()) { ?>
							<label><?php printMLText("owner");?>:</label>
							<select class="chzn-select form-control" name="ownerid">
							<?php
							$allUsers = $dms->getAllUsers();
							foreach ($allUsers as $currUser) {
								if ($currUser->isGuest())
									continue;
								print "<option value=\"".$currUser->getID()."\" ".($currUser->getID()==$user->getID() ? 'selected' : '')." data-subtitle=\"".htmlspecialchars($currUser->getFullName())."\"";
								print ">" . htmlspecialchars($currUser->getLogin()) . "</option>\n";
							}
							?>
							</select>
						<?php } ?>
	          </div>

	          <div class="form-group">
	          	<?php
							$attrdefs = $dms->getAllAttributeDefinitions(array(SeedDMS_Core_AttributeDefinition::objtype_document, SeedDMS_Core_AttributeDefinition::objtype_all));
							if($attrdefs) {
								foreach($attrdefs as $attrdef) {
									$arr = $this->callHook('editDocumentAttribute', null, $attrdef);
									if(is_array($arr)) {
										echo "<label>".$arr[0].":</label>";
										echo $arr[1];
									} else {
							?>
							<label><?php echo htmlspecialchars($attrdef->getName()); ?></label>
							<?php $this->printAttributeEditField($attrdef, ''); ?>
							<?php
									}
								}
							}
							?>
						</div>
						<div class="form-group">
							<label><?php printMLText("expires");?>: <span class="is-required">*</span></label>
			        <div class="input-append date span12" id="expirationdate" data-date="" data-date-format="yyyy-mm-dd" data-date-language="<?php echo str_replace('_', '-', $this->params['session']->getLanguage()); ?>" data-checkbox="#expires">
			          <input class="form-control" size="16" name="expdate" type="text" value="">
			          <span class="add-on"></span>
			        </div>
			        <div class="checkbox">
			        	<label>
									<input type="checkbox" id="expires" name="expires" value="false" checked="true"><?php printMLText("does_not_expire");?>
				        </label>
	        		</div>
			    	</div>
			    	<div class="box-footer">
							<a type="button" class="btn btn-default cancel-add-document"><?php echo getMLText("cancel"); ?></a>
							<a id="btn-next-1" href="#tab_2" data-toggle="tab" type="button" class="btn btn-info pull-right"><?php echo getMLText("next"); ?> <i class="fa fa-arrow-right"></i></a>
						</div>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">
          	<div class="form-group">
	            <label><?php printMLText("version");?>:</label>
	            <input type="text" class="form-control" name="reqversion" value="1">
	          </div>
	          <?php $msg = getMLText("max_upload_size").": ".ini_get( "upload_max_filesize"); ?>
   					<?php $this->warningMsg($msg); ?>
	          <div class="form-group">
	            <label><?php printMLText("local_file");?>: <span class="is-required">*</span></label>
	            <?php
								$this->printFileChooser('userfile[]', false);
							?>
	          </div>
	          <div class="form-group">
	          	<label><?php printMLText("comment_for_current_version");?>:</label>
	          	<textarea class="form-control" name="version_comment" rows="3" cols="80"></textarea>
	          	<div class="checkbox">
	          		<label><input type="checkbox" name="use_comment" value="1" /> <?php printMLText("use_comment_of_document"); ?></label>
	          	</div>
	          </div>
						<?php
							$attrdefs = $dms->getAllAttributeDefinitions(array(SeedDMS_Core_AttributeDefinition::objtype_documentcontent, SeedDMS_Core_AttributeDefinition::objtype_all));
								if($attrdefs) {
									foreach($attrdefs as $attrdef) {
										$arr = $this->callHook('editDocumentAttribute', null, $attrdef);
										if(is_array($arr)) {
											echo "<label>".$arr[0].":</label>";
											echo $arr[1];
										} else {
									?>
										<label><?php echo htmlspecialchars($attrdef->getName()); ?></label>
										<?php $this->printAttributeEditField($attrdef, '', 'attributes_version') ?>
										<?php
										}
									}
								}
						if($workflowmode == 'advanced') { ?>
							<div class="form-group">
								<label><?php printMLText("workflow");?>:</label>
								<?php
								$mandatoryworkflows = $user->getMandatoryWorkflows();
								if($mandatoryworkflows) {
									if(count($mandatoryworkflows) == 1) { ?>
										<?php echo htmlspecialchars($mandatoryworkflows[0]->getName()); ?>
										<input type="hidden" name="workflow" value="<?php echo $mandatoryworkflows[0]->getID(); ?>">
									<?php
									} else { ?>
						        <select class="_chzn-select-deselect span9 form-control" name="workflow" data-placeholder="<?php printMLText('select_workflow'); ?>">
										<?php
											foreach ($mandatoryworkflows as $workflow) {
												print "<option value=\"".$workflow->getID()."\"";
												print ">". htmlspecialchars($workflow->getName())."</option>";
											} ?>
						        </select>
										<?php
											}
										} else { ?>
						        <select class="_chzn-select-deselect span9 form-control" name="workflow" data-placeholder="<?php printMLText('select_workflow'); ?>">
										<?php
											$workflows=$dms->getAllWorkflows();
											print "<option value=\"\">"."</option>";
											foreach ($workflows as $workflow) {
												print "<option value=\"".$workflow->getID()."\"";
												print ">". htmlspecialchars($workflow->getName())."</option>";
											} ?>
						        </select>
										<?php } ?>
									<br/>
									<?php $this->infoMsg(getMLText("add_doc_workflow_warning")); ?>
						<?php } else { echo $this->warningMsg("This theme only works with advanced workflows"); }?>
						</div>
						<div class="box-footer">
							<a type="button" class="btn btn-default cancel-add-document"><?php echo getMLText("cancel"); ?></a>
							<a id="btn-next-2" href="#tab_3" data-toggle="tab" aria-expanded="true" type="button" class="btn btn-info pull-right"><?php echo getMLText("next"); ?> <i class="fa fa-arrow-right"></i></a>
						</div>
          </div>
              <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_3">
            <div class="form-group">
            	<label><?php printMLText("individuals");?>:</label>
            	<select class="chzn-select span9 form-control" name="notification_users[]" multiple="multiple"">
								<?php
									$allUsers = $dms->getAllUsers("");
									foreach ($allUsers as $userObj) {
										if (!$userObj->isGuest() && $folder->getAccessMode($userObj) >= M_READ)
											print "<option value=\"".$userObj->getID()."\">" . htmlspecialchars($userObj->getLogin() . " - " . $userObj->getFullName()) . "\n";
									}
								?>
							</select>
            </div>
            <div class="form-group">
							<label><?php printMLText("groups");?>:</label>
							<select class="chzn-select span9" name="notification_groups[]" multiple="multiple">
								<?php
									$allGroups = $dms->getAllGroups();
									foreach ($allGroups as $groupObj) {
										if ($folder->getGroupAccessMode($groupObj) >= M_READ)
											print "<option value=\"".$groupObj->getID()."\">" . htmlspecialchars($groupObj->getName()) . "\n";
									}
								?>
							</select>
						</div>
						<div class="box-footer">
							<a type="button" class="btn btn-default cancel-add-document"><?php echo getMLText("cancel"); ?></a>
							<button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> <?php echo getMLText("save"); ?></button>
						</div>	
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
   	</form>
    <?php
    echo "</div>";
    echo "<!-- /.box-body -->";
    echo "</div>";
		echo "</div>";
		
		
		///// FIN DE AÑADIR ACTA DE INEXISTENCIA
		//// Folder content ////
		$subFolders = $folder->getSubFolders($orderby);
		$subFolders = SeedDMS_Core_DMS::filterAccess($subFolders, $user, M_READ);
		$documents = $folder->getDocuments($orderby);
		$documents = SeedDMS_Core_DMS::filterAccess($documents, $user, M_READ);
		
		if ((count($subFolders) > 0)||(count($documents) > 0))
		{
			echo "<div class=\"col-md-12\" id=\"folder-content\">";
			echo "<div class=\"box box-primary\">";
	    echo "<div class=\"box-body no-padding\">";
			echo "<div class=\"table-responsive\">";
			$txt = $this->callHook('folderListHeader', $folder, $orderby);
			if(is_string($txt))
				echo $txt;
			else {
				//echo "ANTES DE TABLA";

				if(count($documents) > 0)
				{
					$iduser=getUserFromFolder($folder->getID());
				echo "<a href=\"".$baseServer."out/out.GenerarDatoAbierto.php?idUser=$iduser\"<i class=\"fa fa-save\"></i>Descargar este índice de información reservada en formato CSV</a>";
				}
				print "<table id=\"viewfolder-table\" class=\"table table-hover table-striped table-condensed\">";
				print "<thead>\n<tr>\n";
				print "<th></th>\n";	
				print "<th>".getMLText("name")."</th>\n";	
				print "<th>".getMLText("status")."</th>\n";
				print "<th>".getMLText("action")."</th>\n";
				print "</tr>\n</thead>\n<tbody>\n";
			}
		  
		foreach($subFolders as $subFolder) 
		{
			$txt = $this->callHook('folderListItem', $subFolder);
			if(is_string($txt))
				echo $txt;
			else 
			{
				echo $this->folderListRow($subFolder);
			$formFol = "formFol".$subFolder->getID();
			?>
	
				<?php
			}
		}

		foreach($documents as $document) 
		{
			$document->verifyLastestContentExpriry();
			$txt = $this->callHook('documentListItem', $document, $previewer);
			if(is_string($txt))
				echo $txt;
			else 
			{
				  $content=$document->getLatestContent();
						//echo "content: ".$content;
						$status=$content->getStatus();
						//print_r($status);
						$estado=$status['status'];
						if($estado!=S_OBSOLETE && !$document->hasExpired())
						{
							echo $this->documentListRow($document, $previewer);
						}
				
			$formDoc = "formDoc".$document->getID();
				?>

				<?php
			}
		}
		if ((count($subFolders) > 0)||(count($documents) > 0)) {
			$txt = $this->callHook('folderListFooter', $folder);
			if(is_string($txt))
				echo $txt;
			else

				echo "</tbody>\n";
			 echo "<tfoot>";
               echo '<tr>';
                	print "<th></th>\n";	
				print "<th>".getMLText("name")."</th>\n";	
				print "<th>".getMLText("status")."</th>\n";
				print "<th>".getMLText("action")."</th>\n";
                 echo '</tr>';
                 echo '</tfoot>';
			echo "</table>\n";
		}
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>"; 
		} else 
		{
			echo "<div class=\"col-md-12\">";
			$this->infoMsg(getMLText("empty_folder_list"));
			echo "</div>";
		}
		//// Document preview ////
		echo "<div class=\"col-md-12 div-hidden\" id=\"document-previewer\">";
		echo "<div class=\"box box-info\">";
		echo "<div class=\"box-header with-border box-header-doc-preview\">";
    echo "<span id=\"doc-title\" class=\"box-title\"></span>";
    echo "<span class=\"pull-right\">";
    //echo "<a class=\"btn btn-sm btn-primary\"><i class=\"fa fa-chevron-left\"></i></a>";
    //echo "<a class=\"btn btn-sm btn-primary\"><i class=\"fa fa-chevron-right\"></i></a>";
    echo "<a class=\"close-doc-preview btn btn-box-tool\"><i class=\"fa fa-times\"></i></a>";
    echo "</span>";
    echo "</div>";
    echo "<div class=\"box-body\">";
    echo "<iframe id=\"iframe-charger\" src=\"\" width=\"100%\" height=\"700px\"></iframe>";
    echo "</div>";
		echo "</div>";
		echo "</div>"; // End document preview
		?>
<?php
		echo "</div>\n"; // End of row
//////////////////////////77 TABLA DE ESTADO DE SUBIDA DE DOCUMENTOS //////////////
		$esRaizInsti=esRaiz($folder);
		$estaVacio=vacio($folder,$user);
			echo '<div class="row">';
		if($esRaizInsti && !$estaVacio) //mostrar tabla si estoy en la "raiz" de una institución
		{
		$nombreInsti=$folder->getName();
			echo '<div class="col-md-3"></div>'; //col para rellenar huecos	
			echo '<div class="col-md-6">';	
		echo '  <div class="box">
            <div class="box-header with-border">';
             echo "<h3 class=\"box-title\">Estado de subida de documentación de la institución: ".$nombreInsti."</h3>";
            echo  '</div>';
		echo '<div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Contenidos claves del informe institucional de transición</th>
                  <th>Cantidad de documentación subida</th>
                  <th style="width: 40px">Porcentaje</th>
                  <th>Comentario</th>
                </tr>';
                //carpeta1
                 $avanceCarpeta1=dameAvance(1,$folder,$user);
                 $avanceCarpeta2=dameAvance(2,$folder,$user);
                 $avanceCarpeta3=dameAvance(3,$folder,$user);
                 $avanceCarpeta4=dameAvance(4,$folder,$user);
                 $avanceCarpeta5=dameAvance(5,$folder,$user);
                 $avanceCarpeta6=dameAvance(6,$folder,$user);
                 $avanceCarpeta7=dameAvance(7,$folder,$user);

                 $docsfase1=2;
                 $docsfase2=4;
                 $docsfase3=8;
                 $docsfase4=11;
                 $docsfase5=3;
                 $docsfase6=5;
                 $docsfase7=1;


                 //CARPETA 1: son dos docs en total
                 $porcentaje=($avanceCarpeta1*100)/$docsfase1;
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>1.</td>
                  <td>Marco estratégico y normativo institucional</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                  <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta1." de un total de ".$docsfase1." documentos para este apartado del informe</td>
                </tr>";
                ///
                //CARPETA 2: son dos docs en total
                 $porcentaje=floor(($avanceCarpeta2*100)/$docsfase2);
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>2.</td>
                  <td>Cumplimiento de objetivos y metas institucionales</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta2." de un total de ".$docsfase2." documentos para este apartado del informe</td>
                </tr>";
                ///
                //CARPETA 3
                 $porcentaje=($avanceCarpeta3*100)/$docsfase3;
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>3.</td>
                  <td>Presupestos aprobados en el quinquenio y ejecución presupuestaria</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta3." de un total de ".$docsfase3." documentos para este apartado del informe</td>
                </tr>";
                ///
                //CARPETA 4
                 $porcentaje=($avanceCarpeta4*100)/$docsfase4;
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>4.</td>
                  <td>Organización interna</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta4." de un total de ".$docsfase4." documentos para este apartado del informe</td>
                </tr>";
                ///
                //CARPETA 5
                 $porcentaje=floor(($avanceCarpeta5*100)/$docsfase5);
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>5.</td>
                  <td>Auditorías y juicios</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta5." de un total de ".$docsfase5." documentos para este apartado del informe</td>
                </tr>";
                ///
                //CARPETA 6
                 $porcentaje=($avanceCarpeta6*100)/$docsfase6;
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>6.</td>
                  <td>Transparencia y rendición de cuentas</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta6." de un total de ".$docsfase6." documentos para este apartado del informe</td>
                </tr>";
                ///
                 //CARPETA7
                 $porcentaje=($avanceCarpeta7*100)/$docsfase7;
                 $color="";
                 $colorBarra="";
                 if($porcentaje<50)
                 {
                 	$color="red";
                 	$colorBarra="danger";
                 }
                 if($porcentaje>=50 && $porcentaje<100)
                 {
                 	$color="yellow";
                 	$colorBarra="warning";
                 }
                 else if($porcentaje==100)
                 {
                 	$color="green";
                 	$colorBarra="success";
                 }
                echo "<tr>
                  <td>7.</td>
                  <td>Principales procesos estratégicos en marcha</td>
                  <td>
                    <div class=\"progress progress-xs\">";
                      echo"<div class=\"progress-bar progress-bar-".$colorBarra."\" style=\"width:".$porcentaje."%\"></div>";

                   echo "</div>
                  </td>
                   <td><span class=\"badge bg-".$color."\">".$porcentaje."%</span></td>
                  <td>Subidos ".$avanceCarpeta7." de un total de ".$docsfase7." documentos para este apartado del informe</td>
                </tr>";
                ///

          echo ' </table> </div>'; //cierre table y box body
          echo '  <div class="box-footer clearfix">
             
            </div>
          </div></div>';
          echo '<div class="col-md-3"></div>'; //col para rellenar huecos	
		
	} //fin del if de mostrar tabla
	
	if ($estaVacio && $esRaizInsti)
	{
		echo '<div class="callout callout-danger">
                <h4>Aún no se han creado las carpetas</h4>

                <p>Deben crearse las carpetas correspondientes donde los usuarios subirán los documentos del informe de transición</p>
              </div>';
	}
echo '</div>'; //cierre row
		//////////////////////////FIN TABLA DE ESTADO DE SUBIDA DE DOCUMENTOS //////////////
		echo "</div>\n"; // End of container

		echo $this->callHook('postContent');

		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		//echo "<script type='text/javascript' src='../formularioSubida.js'></script>";
		echo "<script type='text/javascript' src='../tablasDinamicas.js'></script>";
		echo '<script src="../styles/multisis-lte/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>';
        echo '<script src="../styles/multisis-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>';
		$this->htmlEndPage();
	} /* }}} */
}

?>