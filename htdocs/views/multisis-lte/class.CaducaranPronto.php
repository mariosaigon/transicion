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
	$miQuery="SELECT homefolder FROM tblUsers WHERE id=".$id_usuario;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	$id_folder=$resultado[0]['homefolder'];
	//echo "id_folder: ".$id_folder;
	return $id_folder;
}
 function imprimirResultados($resultados,$days,$user,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria,$baseDatos,$id_fecha_clasificacion)
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
		print "<table class=\"table table-hover\">";
								print "\n<thead>\n<tr>\n";
								///////////////////headers de la tabla
								print "<th>".getMLText("numero_declaratoria")."</th>\n";
								print "<th>".getMLText("name")."</th>\n";//Nombre del documento 
								print "<th>".getMLText("fecha_clasificacion")."</th>\n"; //Propietario
								//print "<th>".getMLText("version")."</th>\n";
								//print "<th>".getMLText("last_update")."</th>\n";
								print "<th>".getMLText("expires")."</th>\n";
								//////////////////////////////// records (entradas de la tabla)
								print "</tr>\n</thead>\n<tbody>\n";			
								 
 	foreach ($resultados as $res) //cada resultado es un documento
 	{
 		//obtengo información del documento para hacer un preview de él; id,name,owner, son los nombres de las columnas según la tabla sql tbldocuments. Trato los campos sql directamente porque estoy
 		//trabajando con el array resultante de hacer una consulta sql
 		$id_documento=$res["id"];
 		$nombre_documento=$res["name"];
 		$id_propietario_documento=$res["owner"];

 		$docIdx = array();
 		$document = $dms->getDocument($id_documento);
		//$document->verifyLastestContentExpriry();
		$fechaCaducidad=$document->getExpires(); //me lo da en timestamp unix
		$fechaCaducidad=date('d/m/Y', $fechaCaducidad);
		$id_folder_ente=getDefaultUserFolder($id_propietario_documento);
		$nombreEnte=$dms->getFolder($id_folder_ente)->getName();;
		$nombnePropietario=$dms->getUser($id_propietario_documento)->getFullname();
  		$numDeclaratoria="(no definido)";
  		$fechaClasificacion="(no definida)";
		$query = "SELECT * FROM `tblDocumentAttributes` WHERE document = '".$id_documento."' AND attrdef='".$id_numero_declaratoria."'";
		$queryFechaClasificacion="SELECT * FROM `tblDocumentAttributes` WHERE document = '".$id_documento."' AND attrdef='".$id_fecha_clasificacion."'";
			//echo "query: ".$query;
	       //llamo a bd
		//echo "Query q estoy haciendo: ".$queryStr;
		//////////VER DEL RESULTADO DE LA QUERY NUMERO DE DECLARATORIA
			$resArr = $baseDatos->getResultArray($query);
			//print_r($resArr);
			if (is_bool($resArr) && !$resArr) 
			{
				echo getMLText("internal_error_exit")."\n";
				exit;
			}
			if(count($resArr)>0)
			{
				$numDeclaratoria=$resArr[0]["value"];
			}
			//////////// PARA FECHA DE CLASIFICACION
			$resArr2 = $baseDatos->getResultArray($queryFechaClasificacion);
			//print_r($resArr);
			if (is_bool($resArr2) && !$resArr2) 
			{
				echo getMLText("internal_error_exit")."\n";
				exit;
			}
			if(count($resArr2)>0)
			{
				$fechaClasificacion=$resArr2[0]["value"];
			}

			
		//ver atributos del documento: http://www.seeddms.org/fileadmin/html/classes/SeedDMS_Core_Document.html
								if($nvuelta==1)
								{
									print "Ente obligado: "."<b><i>".$nombreEnte."</i></b>"."\n";
								}
							
				               ;
				               print "<tr>\n";
							$latestContent = $document->getLatestContent();
							//datos (td)
							//$previewer->createPreview($latestContent);
							print "<td>".$numDeclaratoria."</td>\n";
							print "<td> <a href=\"out.ViewDocument.php?documentid=".$id_documento."&currenttab=revapp\">". $nombre_documento ."</a></td>\n"; //Nombre del documento y enlace a él
							print "<td>".$fechaClasificacion."</td>\n"; //propietario
							
							//print "<td>date</td>\n";

							print "<td>".$fechaCaducidad."</td>\n";
							//////fin de datos					
							//print "</tr>\n";
							 print "\n</tr>\n";
							$nvuelta++;

		}
		
			print "\n</tbody>\n</table>\n";
			//print"</div>";
		
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
		//echo "Query q estoy haciendo: ".$queryStr;
			$resArr = $baseDatos->getResultArray($queryStr2);
			if (is_bool($resArr) && !$resArr) 
			{
				echo getMLText("internal_error_exit")."\n";
				exit;
			}
			return $resArr;
			      
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
		$id_numero_declaratoria = $this->params['id_numero_declaratoria'];
		$id_fecha_clasificacion = $this->params['id_fecha_clasificacion'];
		$caduca_en=$this->params['caduca_en'];
		$formato=$this->params['formato'];
		$valor_exacto=$this->params['exacto'];
		$fueChequeado=$this->params['fue_chequeado'];
		//echo "caduca en: ".$caduca_en;
		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);


		if($user->isAdmin())
		{
			$this->htmlStartPage("Reservas que caducarán pronto", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("Reservas que caducarán pronto", "skin-blue layout-top-nav");
		}
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar();
		}
		$this->contentStart();
          
		?>
		<ol class="breadcrumb">
        <li><a href="../index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Buscador de reservas próximas a caducar</li>
        <li class="active">Resultado de reservas próximas a caducar</li>
      </ol>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">

    <?php
    $settings = new Settings(); //acceder a parámetros de settings.xml con _antes
    $idInvitado=$settings->_guestID; //id del guestt user para no mostrarme sus documentos caducados
    //echo "GuestiDF".$idInvitado;
    if($fueChequeado==FALSE)
    {
    	 $this->startBoxPrimary(getMLText("proximos_a_caducar")." dentro de los próximos ".$valor_exacto." ".$formato);
    }
    else
    {
    	$this->startBoxPrimary("Todos los documentos cuya reserva aún está vigente");
    }
   
	//obtengo lista de todos los usuarios
	$miPropioID=$user->getID();
	//echo "miPropioID: ".$miPropioID;
    if($user->isAdmin() || $user->isGuest() )
    {
    	//echo "Es  admin";
    	//$this->contentHeading(getMLText("es usuario administrador"));
    	$todos_usuarios = $dms->getAllUsers();
    	//print_r($todos_usuarios);
    	foreach ($todos_usuarios as $usuario_particular) 
    	{
    		$id_usuario_particular=$usuario_particular->getID();
    		///echo "id usuario aprticular:: ".$id_usuario_particular;
		   //$nombreEnte=$folder_ente->getName();
    		if($id_usuario_particular!=$idInvitado && $miPropioID!=$id_usuario_particular) //hago esto para no mostrar los documentos caducados del guest user (cuyo id es parametrizado en settings.xml)
    		{
    			$resultados=buscarDocumentosCaducados($db,$usuario_particular,$caduca_en,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria);
    			
    			$conteo=intval(count($resultados));
    			//echo "conteo: ".$conteo;
    			if($conteo>0)
    			{

    				//$this->contentHeading($id_usuario_particular);
    				$this->contentContainerStart(); //añade internamente un <div class="well">

    				imprimirResultados($resultados,$caduca_en,$usuario_particular,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria,$db,$id_fecha_clasificacion);
    				$this->contentContainerEnd();
    			}
	    	//$this->contentHeading(getMLText("declaratorias_a_caducar_por_institucion")); //esto es para formatear la tabla que se imprimirá
			    	
    		}
    	
    	}
    	
    }
    /////////////////
	if(!$user->isAdmin() && !$user->isGuest())
	{ 
		//echo "Es usuario normal";
		$id_este_usuario=$user->getID();
    		///echo "id usuario aprticular:: ".$id_usuario_particular;
		   //$nombreEnte=$folder_ente->getName();
    		if($id_este_usuario!=$idInvitado) //hago esto para no mostrar los documentos caducados del guest user (cuyo id es parametrizado en settings.xml)
    		{
    			$resultados=buscarDocumentosCaducados($db,$user,$caduca_en,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria);
    			
    			$conteo=intval(count($resultados));
    			//echo "conteo: ".$conteo;
    			if($conteo>0)
    			{

    				//$this->contentHeading($id_usuario_particular);
    				$this->contentContainerStart(); //añade internamente un <div class="well">

    				imprimirResultados($resultados,$caduca_en,$user,$dms,$cachedir,$previewwidth,$timeout,$id_numero_declaratoria,$db,$id_fecha_clasificacion);
    				$this->contentContainerEnd();
    			}
	    	//$this->contentHeading(getMLText("declaratorias_a_caducar_por_institucion")); //esto es para formatear la tabla que se imprimirá
			    	
    		}

	}
	/* recorro la lista de usuarios, para cada uno, */
	
$this->endsBoxPrimary();
?>
		
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
