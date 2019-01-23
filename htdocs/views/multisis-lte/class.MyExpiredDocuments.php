<?php
/**
 * Implementation of MyDocuments view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx> DMS with modifications of José Mario López Leiva
 * @copyright  Copyright (C) 2017 José Mario López Leiva
 *             marioleiva2011@gmail.com    
 				San Salvador, El Salvador, Central America

 *             
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
 * Class which outputs the html page for MyDocuments view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
 /**
 Función que muestra los documentos próximos a caducar de todos los usuarios
 mostrarTodosDocumentos(lista_usuarios,dias)
 -dias: documentos que van a caducar dentro de cúantos días
 */
function getDefaultUserFolder($id_usuario) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$id_folder=0;
	$manejador=new SeedDMS_Core_DatabaseAccess("mysql","localhost","root","root","seeddms");
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
 function imprimirResultados($resultados,$days,$user,$dms,$cachedir,$previewwidth,$timeout)
 {
 	$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
 	$tableformat = "%-50s %-14s";
 	$tableformathtml = "<tr><td>%-50s</td><td>%-14s</td></tr>";
 	$body = "";
 	$body .= "==== ";
	$body .= getMLText('docsExpiringInNDays', array('days'=>$days), "", $user->getLanguage())."\n";
	$body .= "==== ";
	$body .= $user->getFullname();
	$body .= "\n\n";
	$nvuelta=1;					
 	foreach ($resultados as $res) //cada resultado es un documento
 	{
 		//obtengo información del documento para hacer un preview de él; id,name,owner, son los nombres de las columnas según la tabla sql tbldocuments. Trato los campos sql directamente porque estoy
 		//trabajando con el array resultante de hacer una consulta sql
 		$id_documento=$res["id"];
 		$nombre_documento=$res["name"];
 		$id_propietario_documento=$res["owner"];

 		$docIdx = array();
 		$document = $dms->getDocument($id_documento);
		$document->verifyLastestContentExpriry();
		$id_folder_ente=getDefaultUserFolder($id_propietario_documento);
		$nombreEnte=$dms->getFolder($id_folder_ente)->getName();;
		
		//ver atributos del documento: http://www.seeddms.org/fileadmin/html/classes/SeedDMS_Core_Document.html
								if($nvuelta==1)
								{
									print "Ente obligado: ".$nombreEnte;
								}
								print "<table class=\"table table-condensed\">";
								print "<thead>\n<tr>\n";
								///////////////////headers de la tabla
								print "<th></th>\n";
								print "<th>".getMLText("name")."</th>\n";//Nombre del documento 
								print "<th>".getMLText("owner")."</th>\n"; //Propietario
								//print "<th>".getMLText("version")."</th>\n";
								print "<th>".getMLText("last_update")."</th>\n";
								print "<th>".getMLText("expires")."</th>\n";
								//////////////////////////////// records (entradas de la tabla)
								print "</tr>\n</thead>\n<tbody>\n";
				                print "<tr>\n";
							$latestContent = $document->getLatestContent();
							//datos (td)
							//$previewer->createPreview($latestContent);
							print "<td> <a href=\"#\">";
							print "<img class=\"mimeicon\" width=\"".$previewwidth."\"src=\"../op/op.Preview.php?documentid=".$document->getID()."&version=".$latestContent->getVersion()."&width=".$previewwidth."\" title=\"titulo\"";  print "</a></td>";

							print "<td><a href=\"out.ViewDocument.php?documentid=".$id_documento."&currenttab=revapp\">". $nombre_documento     ."</td>"; //Nombre del documento y enlace a él
							print "<td>".$id_propietario_documento."</td>"; //propietario
							
							print "<td>date</td>";

							print "<td>dasdas</td>";
							//////fin de datos					
							print "</tr>\n";
							print "</tbody>\n</table>";	
							$nvuelta++;

		}
		
 }

function mostrarTodosDocumentos($baseDatos,$usuario,$days,$dms,$cachedir,$previewwidth,$timeout)
	{
		$startts = strtotime("midnight", time());//fecha de la consulta
		$informwatcher=FALSE;
		//echo "Checking for documents expiring between ".date('Y-m-d', $startts)." and ".date('Y-m-d', $startts+($days-1)*86400)."\n";

                //de cada usuario obtengo los grupos a los cuales pertences
				$groups = $usuario->getGroups();
				$groupids = array();
				foreach($groups as $group)
				{
				   $groupids[] = $group->getID();
				}

				///hacemos la consulta como tal
				//construyo la consulta

				if (!$baseDatos->createTemporaryTable("ttstatid") || !$baseDatos->createTemporaryTable("ttcontentid")) {
	echo getMLText("internal_error_exit")."\n";
	exit;
}
	//echo "creada tabla temporal";
					$queryStr = "SELECT DISTINCT a.*, tblDocumentStatusLog.* FROM `tblDocuments` a ".
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
			
	       //llamo a bd
		//echo "Query q estoy haciendo: ".$queryStr;
			$resArr = $baseDatos->getResultArray($queryStr);
			if (is_bool($resArr) && !$resArr) 
			{
				echo getMLText("internal_error_exit")."\n";
				exit;
			}
			if (count($resArr)>0) 
			{
				imprimirResultados($resArr,$days,$usuario,$dms,$cachedir,$previewwidth,$timeout);
			}
			

        
	}
	function mostrarDocumentosSoloUsuario()
	{

	}
class SeedDMS_View_CaducaranPronto extends SeedDMS_Bootstrap_Style 
{
 /**
 Método que muestra los documentos próximos a caducar sólo de 
 **/
	

	function show() 
	{ /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$orderby = $this->params['orderby'];
		$showInProcess = $this->params['showinprocess'];
		$cachedir = $this->params['cachedir'];
		$workflowmode = $this->params['workflowmode'];
		$previewwidth = $this->params['previewWidthList'];
		$timeout = $this->params['timeout'];

		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);


		$this->htmlStartPage(getMLText("my_documents"), "skin-blue sidebar-mini");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		$this->contentStart();
          
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">

    <?php

    $this->startBoxPrimary(getMLText("proximos_a_caducar"));
	//obtengo lista de todos los usuarios
	
    if($user->isAdmin())
    {
    	echo "Es usuario administrador";
    	$todos_usuarios = $dms->getAllUsers();
    	foreach ($todos_usuarios as $usuario_particular) 
    	{
    	$this->contentHeading(getMLText("declaratorias_a_caducar_por_institucion")); //esto es para formatear la tabla que se imprimirá
		$this->contentContainerStart(); //añade internamente un <div class="well">
    	mostrarTodosDocumentos($db,$usuario_particular,30,$dms,$cachedir,$previewwidth,$timeout);
    	$this->contentContainerEnd();
    	}
    	
    }
	if(!$user->isAdmin() && !$user->isGuest())
	{ 
		echo "Es usuario normal";

	}
	/* recorro la lista de usuarios, para cada uno, */
	

?>
		
		</div>
		</div>
		</div>
		</div>
		</div>


		<?php

		
		
		
		
		
		
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
