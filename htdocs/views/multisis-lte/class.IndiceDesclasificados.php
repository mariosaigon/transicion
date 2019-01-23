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
 function dameDesclasificados($dms) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
{
	// 4 junio 2018
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

  $miQuery="
CREATE TEMPORARY TABLE IF NOT EXISTS `ttstatid` (PRIMARY KEY (`statusID`), INDEX (`maxLogID`)) 
            SELECT `tblDocumentStatusLog`.`statusID`, 
            MAX(`tblDocumentStatusLog`.`statusLogID`) AS `maxLogID` 
            FROM `tblDocumentStatusLog` 
            GROUP BY `tblDocumentStatusLog`.`statusID` 
            ORDER BY `maxLogID`;                      
                        CREATE TEMPORARY TABLE `ttcontentid` (PRIMARY KEY (`document`), INDEX (`maxVersion`)) 
            SELECT `tblDocumentContent`.`document`, 
            MAX(`tblDocumentContent`.`version`) AS `maxVersion` 
            FROM `tblDocumentContent` 
            GROUP BY `tblDocumentContent`.`document` 
            ORDER BY `tblDocumentContent`.`document`;

  SELECT DISTINCT `tblDocuments`.*, `tblDocumentContent`.`version`, `tblDocumentStatusLog`.`status`, `tblDocumentLocks`.`userID` as `lockUser` FROM `tblDocumentContent` LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentAttributes` ON `tblDocuments`.`id` = `tblDocumentAttributes`.`document` LEFT JOIN `tblDocumentContentAttributes` ON `tblDocumentContent`.`id` = `tblDocumentContentAttributes`.`content` LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.`statusID` LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` LEFT JOIN `ttcontentid` ON `ttcontentid`.`maxVersion` = `tblDocumentStatus`.`version` AND `ttcontentid`.`document` = `tblDocumentStatus`.`documentID` LEFT JOIN `tblDocumentLocks` ON `tblDocuments`.`id`=`tblDocumentLocks`.`document` LEFT JOIN `tblDocumentCategory` ON `tblDocuments`.`id`=`tblDocumentCategory`.`documentID` WHERE `ttstatid`.`maxLogID`=`tblDocumentStatusLog`.`statusLogID` AND `ttcontentid`.`maxVersion` = `tblDocumentContent`.`version` AND `tblDocuments`.`folderList` LIKE '%:1:%' AND `tblDocumentStatusLog`.`status` IN (-2,-3);";
  //echo "mi query: ".$miQuery;
  $mysqli = new mysqli($host, $user, $pass, $base);
  /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/// INICIALIZO TABLA

	echo "<div class=\"box\">";
            echo "<div class=\"box-header with-border\">";
              echo "<h3 class=\"box-title\">Lista de documentos desclasificados</h3>";
            echo "</div>";
            echo "<div class=\"box-body\">";
             echo "<table id=\"tablaDesclasificados2\"class=\"table table-bordered\">";
             print "<thead>";
                echo "<tr>";
                 	echo "<th>Ente obligado</th>";
                  echo "<th>Nombre del documento</th>";
                  echo "<th>Fecha de desclasificación</th>";
                  echo "<th>Causa desclasificación</th>";
                echo "</tr>";
                print "</tr>\n</thead>\n<tbody>\n";

if ($mysqli->multi_query($miQuery)) 
{

	do {

        /* almacenar primer juego de resultados */
        if ($result = $mysqli->store_result()) 
        {
 
            while ($row = $result->fetch_row()) 
            {
        
            	$idDoc=$row[0];
            	$nombreDoc=$row[1];
            	$idOwner=$row[5];
            	$idFolder=$row[6];
            	$status=$row[14];
            	
                //AQUI ENCUENTRA REGISTROS DE LA TABLA COMO TAL
            	 echo "<tr>";
            	 //imprimo nombre del ente obligado
            	 $folderObj=$dms->getFolder($idFolder);
            	 $nomEnte=$folderObj->getName();
            	  print "<td> ".$nomEnte."</td>\n"; 
            	  //////imprimo nombre dle documento
		       print "<td> <a href=\"out.ViewDocument.php?documentid=".$idDoc."&currenttab=revapp\">". utf8_encode($nombreDoc) ."</a></td>\n"; 
		       	/////imprimo fecha de desclasificación Y CAUSA DE UN SOLO////////////
		       	$documento=$dms->getDocument($idDoc);
		         	if($status==-3)//OJO: -3 ES CADUCADO, -2 ES RESERVA REVOCADA (OBSOLETO)
		         	{
							
					$fechaVencimientoReserva=$documento->getExpires();
					$fechaVencimientoReserva=date('d/m/Y', $fechaVencimientoReserva);
					 echo "<td>".$fechaVencimientoReserva."</td>";
					 echo "<td>Vencimiento del plazo de reserva (Art. 20 LAIP)</td>";
		         	}
		         	else //caso que haya sido revocado, busco en la tabla revocacionreservas
		         	{
		  
		         		 $mysqli2 = new mysqli($host, $user, $pass, $base);
					  /* check connection */
					if (mysqli_connect_errno()) {
					    printf("Connect failed: %s\n", mysqli_connect_error());
					    exit();
					}
		         		$queryRevocado="SELECT fechaResolucion FROM  revocacionReservas WHERE idDocumento=$idDoc;";
		         		$result2 = $mysqli2->query($queryRevocado);
		         		if ($result2->num_rows > 0) 
		         		{
					    // output data of each row
		         		$fec=0; //fecha en que fue desclasificado
					    while($row = $result2->fetch_assoc()) 
					    {
					        $fec=$row["fechaResolucion"];
					        break;
					    }
					    $aux=explode("-",$fec);
					    $dia=$ano=$aux[2];
					    $mes=$ano=$aux[1];
					    $ano=$aux[0];
					    $fechaFinal=$dia."/".$mes."/".$ano;
					     echo "<td>".$fechaFinal."</td>";
					      echo "<td>Revocación de la reserva por parte del IAIP (extinción de sus causas: Art. 20 LAIP)</td>";
					} 
					else 
					{
					    echo "0 results";
					}

		         		
		         	
				           
		       //imprimo causa
		         	}
		        } //fin while
					                 
		       echo "</tr>";
            }//fin if
            //$result->free();
        } while ($mysqli->next_result());
    
	
} // fin de multi_query
	
echo "</tbody>\n";

    		echo "</table>";
            echo "</div>";
            echo "</div>";

  //printf("Errormessage: %s\n", $mysqli->error);
$mysqli->close();

}
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
function obtenerFechaRevocacion($documento) //ddodo un id de documento que ha sido revocada la reserva,
//me da la fecha de la resolución del IAIP
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$fecha=0;
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
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("class.IndiceDesclasificados: No se pudo conectar a la BD"));
	}	
	//query de consulta:
	//$miQuery="SELECT fechaResolucion FROM seeddms.revocacionreservas WHERE idDocumento=".$documento;
	$miQuery="SELECT fechaResolucion FROM revocacionReservas WHERE idDocumento=".$documento;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	//echo "fecha= ".$fecha;
    if(!$resultado)
	{
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("Indice desclasificados: Parece ser que hubo un error obteniendo la fecha de revocación de la reserva del documento."));
	}
	$fecha=$resultado[0]['fechaResolucion'];
	//echo "id_folder: ".$id_folder;
	return $fecha;
}

class SeedDMS_View_IndiceDesclasificados extends SeedDMS_Bootstrap_Style 
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
			$this->htmlStartPage("índice de desclasificación de todos los entes obligados", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("índice de desclasificación de entes obligados ", "skin-blue layout-top-nav");
		}
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar();
		}
		$this->contentStart();
          
		?>
	<?php
        if(!$user->isAdmin() && !$user->isGuest())
        {
          echo '<ol class="breadcrumb">
        <li><a href="/out/out.ViewFolder.php?folderid=16&showtree=1#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Índice de desclasificación</li>
      </ol>';  
        }
        else
        {
            if($user->isGuest())
            {
                echo '<ol class="breadcrumb">
        <li><a href="../index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Índice de desclasificación</li>
      </ol>';
            }
            
        }
      	 
        ?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">

    <?php
    $this->startBoxPrimary("Índice de desclasificación  entes obligados");
    $listaDocumentosUsuario=$user->getDocuments();
	//obtengo lista de todos los usuarios
	$miPropioID=$user->getID();
	//echo "miPropioID: ".$miPropioID;
    /////////////////
    //comprobación 1: generar la lista de desclasificación, pero solo para el ente si lo ve un oficial
	if(!$user->isAdmin() && !$user->isGuest())
	{ 
    				$this->contentContainerStart(); //añade internamente un <div class="well">
    				echo "<div class=\"box-body table-responsive no-padding\">";
         			  echo "<table id =\"tablaDesclasificados\" class=\"table table-hover table-striped\">";
         			  print "<thead>\n<tr>\n";
                  echo "<th>Nombre del documento</th>";
                  echo "<th>Fecha de desclasificación</th>";
                  echo "<th>Causa desclasificación</th>";
                  print "</tr>\n</thead>\n<tbody>\n";
				foreach ($listaDocumentosUsuario as $documento) //recorro los documentos del usuario, y tomo aquellos documentos que ya expiraron
					{
						$nombre_documento=$documento->getName();
						//echo "nom: ".$nombre_documento;
						if($documento->hasExpired())
						{      
							$id_documento=$documento->getID();
							
							$fechaVencimientoReserva=$documento->getExpires();
							$fechaVencimientoReserva=date('d/m/Y', $fechaVencimientoReserva);
				                echo "<tr>";
				             print "<td> <a href=\"out.ViewDocument.php?documentid=".$id_documento."&currenttab=revapp\">". $nombre_documento ."</a></td>\n"; 
				           
				                  echo "<td>".$fechaVencimientoReserva."</td>";
				                  echo "<td>Vencimiento del plazo de reserva (Art. 20 LAIP)</td>";
				                 
				               echo "</tr>";
            
						}//fin if

						 $content=$documento->getLatestContent();
						//echo "content: ".$content;
						$status=$content->getStatus();
						//print_r($status);
						$estado=$status['status'];
						if($estado==S_OBSOLETE) //revocado por IAIP, O EN lenguaje seeddms, OBSOLETO
						//recordemos que el estado obsoleto le he cambiado la denominación como
						//revocado por IAIP
						{
							$fechaDes=obtenerFechaRevocacion($documento->getID()); //fecha del estado más reciente
							$componentes=explode("-", $fechaDes);
							$año=$componentes[0];
								$mes=$componentes[1];
									$dia=$componentes[2];
							$fechaDesclasificacion=$dia."/".$mes."/".$año;
							echo "<tr>"; //inicia la entrada
				             print "<td> <a href=\"out.ViewDocument.php?documentid=".$id_documento."&currenttab=revapp\">". $nombre_documento ."</a></td>\n"; 
				           
				                  echo "<td>".$fechaDesclasificacion."</td>";
				                  echo "<td><a href=\"/LAIP.pdf\"> Revocación de la reserva por parte del IAIP (extinción de sus causas: Art. 20 LAIP)</a></td>";
				                 
				               echo "</tr>"; //termina la entrada
						}
						
					}//fin foreach

					 echo "</table>";
            echo "</div>";
					
    				$this->contentContainerEnd();

	} //fin comprobación 1
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*COMPROBACION 2:  generar el índice de desclasificados de TODOS LOS ENTES*/
	//29 DE MAYO 2018: añadido si es guest también
	if($user->isAdmin() || $user->isGuest())
	{
		//recorro todos los usuarios (bucle 1), para cada usuario saco sus documentos (bucle 2), cada documento, veo si está caducado o revocado, y lo pongo, para cada ente.
		//$this->contentContainerStart(); //añade internamente un <div class="well">
		echo "<p>Haga click en cada cabecera de la tabla para ordenarla de acuerdo al parámetro corrrespondiente</p>";
		dameDesclasificados($dms);
		//$this->contentContainerEnd();		
	}//fin de comprobar que no tomo invitados ni administrador
			//cierre de la tabla	               

      	
$this->endsBoxPrimary();
?>
		
		</div>
		</div>
		</div>
		<?php
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		echo "<script type='text/javascript' src='../tablasDinamicas.js'></script>";
			echo '<script src="../styles/multisis-lte/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>';
        echo '<script src="../styles/multisis-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>';
		$this->htmlEndPage();
	} /* }}} */
}
?>
