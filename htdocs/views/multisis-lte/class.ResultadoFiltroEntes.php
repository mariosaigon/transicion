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
 //string tal como aparece en la tabla tblcategory
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
		exit;
	}	
	//query de consulta:
	$miQuery="SELECT homefolder FROM tblUsers WHERE id=".$id_usuario;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	$id_folder=$resultado[0]['homefolder'];
	//echo "id_folder: ".$id_folder;
	return $id_folder;
}

function contarDocumentos($tipo_documento,$folder,$orderby)
{
	$contadorFinal=0;
	 $stringDeclaratoriasReserva="Declaratorias de reserva";
$stringActas="Actas de inexistencia";
    // un departamento es un folder, cada subfolder será un municipio
    //print "<p> Municipios del departamento de ".$departamento->getName()."</p>";
		
					$listaDocumentos=$folder->getDocuments($orderby);

					foreach ($listaDocumentos as $documento)
					 {
							$categoriasDocumento=$documento->getCategories();
				   	    	foreach ($categoriasDocumento as $categoria) 
				   	    	{
				   	    		if(strcmp($tipo_documento, "ACTAS")==0)
				   	    		{
				   	    			//echo "acta </br>";
				   	    			if(strcmp($categoria->getName(),$stringActas)==0)
					   	    		{
					   	    			//echo "es una declaratoria de reserva </br>";
					   	    			$contadorFinal++;
					   	    		}

				   	    		}
				   	    		if(strcmp($tipo_documento, "RESERVAS")==0)
				   	    		{
				   	    			//echo "reserva</br>";
				   	    			if(strcmp($categoria->getName(),$stringDeclaratoriasReserva)==0)
					   	    		{
					   	    			//echo "es una declaratoria de reserva </br>";
					   	    			$contadorFinal++;
					   	    		}
				   	    		
				   	    		}
				   	    	}
				   	      }			   	 	
		      
   	    ///////////////////////////////
return $contadorFinal;
}
class SeedDMS_View_ResultadoFiltroEntes extends SeedDMS_Bootstrap_Style 
{
	function show() 
	{ /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
			$orderby = $this->params['orderby'];
		$idEnte=0;
		if (isset($_POST["enteObligado"])  && strlen($_POST["enteObligado"])>0 ) //se envía a través del método POST porque es un formulario
		{
			$idEnte=$_POST["enteObligado"];
			//echo "creador excel: ".$creadorExcel;
		}	

		$this->htmlStartPage(getMLText("mi_sitio"), "skin-blue sidebar-mini");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		//$this->contentContainerStart("hoa");
		$this->contentStart();

		$fold="";
			$nombreEnte="";
			$idF=getDefaultUserFolder($idEnte);
		
				$fold=$dms->getFolder($idF);
				$nombreEnte=$fold->getName();
				$usuario=$dms->getUser($idEnte);
				$fotoEnte=$usuario->getImage();
				$numeroActas=contarDocumentos("ACTAS",$fold,$orderby);
          		$numeroReservas=contarDocumentos("RESERVAS",$fold,$orderby);
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
 $this->startBoxPrimary(getMLText("Ver estadísticas del ente ".$nombreEnte));
//$this->contentContainerStart();
//////INICIO MI CODIGO

?>
<div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h3 class="widget-user-username"> <?php  echo $nombreEnte;?> </h3>
              <h5 class="widget-user-desc">Ente obligado</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="data:image/png;base64,  <?php  print_r($fotoEnte['image'])?>" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php  echo $numeroActas;?></h5>
                    <span class="description-text">Actas de inexistencia</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php  echo $numeroReservas;?></h5>
                    <span class="description-text">Documentos reservados (incluye desclasificados)</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header"> <?php  echo $numeroReservas+$numeroActas;?></h5>
                    <span class="description-text">Documentos en total</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
        <!-- ./col -->
<?php
 //////FIN MI CODIGO                 
//$this->contentContainerEnd();
$this->endsBoxPrimary();
     ?>
	     </div>
		</div>
		</div>

		<?php	
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		//$this->contentContainerEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
