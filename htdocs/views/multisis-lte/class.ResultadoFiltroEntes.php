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

function contarDocumentos($folder,$user)
{
    $totalLlenos=0; 
   		$ninos=$folder->countChildren($user,0);
   		$totalLlenos=$ninos['document_count'];
   		//echo "total llenos para folder: ".$folder->getID()." --".$totalLlenos;
   		return $totalLlenos;
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
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/tacometros/raphael.js"></script>'."\n", 'js');

	    $this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/tacometros/justgage.js"></script>'."\n", 'js');
		$this->htmlStartPage("Avance de institución", "skin-blue sidebar-mini sidebar-collapse");
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

		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
 $this->startBoxPrimary("Ver avance en subida del informe de transición de ".$nombreEnte);
//$this->contentContainerStart();
//////INICIO MI CODIGO
 $subfases=$fold->getSubFolders(""); //ordenados por secuencia
 //echo "Nombre del folder 1 sonL ".$subfases[0]->getName();
$totalf1=contarDocumentos($subfases[0],$user);
$totalf2=contarDocumentos($subfases[1],$user);
$totalf3=contarDocumentos($subfases[2],$user);
$totalf4=contarDocumentos($subfases[3],$user);
$totalf5=contarDocumentos($subfases[4],$user);
$totalf6=contarDocumentos($subfases[5],$user);
$totalf7=contarDocumentos($subfases[6],$user);
?>
<div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h3 class="widget-user-username"> <?php  echo $nombreEnte;?> </h3>
              <h5 class="widget-user-desc">Nombre de la institución</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="data:image/png;base64,  <?php  print_r($fotoEnte['image'])?>" alt="User Avatar">
            </div>
            <div class="box-footer">
        		<div class="row">
            		<div class="col-md-4">
            				<div id="gaugef1" class="200x160px"></div>
            				<p id="f1"></p>
            		</div>
            		<div class="col-md-4">
            			<div id="gaugef2" class="200x160px"></div>
            			<p id="f2"></p>
            		</div>
            		<div class="col-md-4">
            			<div id="gaugef3" class="200x160px"></div>
            			<p id="f3"></p>
            		</div>

            	</div>

            	<div class="row">
            		<div class="col-md-4">
            			<div id="gaugef4" class="200x160px"></div>
            			<p id="f4"></p>
            		</div>
            		<div class="col-md-4">
            			<div id="gaugef5" class="200x160px"></div>
            			<p id="f5"></p>
            		</div>
            		<div class="col-md-4">
            			<div id="gaugef6" class="200x160px"></div>
            			<p id="f6"></p>
            		</div>

            	</div>

            	<div class="row">
            		<div class="col-md-4">

            		</div>
            		<div class="col-md-4">
            				<div id="gaugef7" class="200x160px"></div>
            		<p id="f7"></p>
            		</div>
            		<div class="col-md-4">

            		</div>
            		
            	</div>

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
		<input type="hidden" id="totalf1" value="<?php echo $totalf1?>" />
		<input type="hidden" id="totalf2" value="<?php echo $totalf2?>" />
		<input type="hidden" id="totalf3" value="<?php echo $totalf3?>" />
		<input type="hidden" id="totalf4" value="<?php echo $totalf4?>" />
		<input type="hidden" id="totalf5" value="<?php echo $totalf5?>" />
		<input type="hidden" id="totalf6" value="<?php echo $totalf6?>" />
		<input type="hidden" id="totalf7" value="<?php echo $totalf7?>" />
		<?php	
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		//$this->contentContainerEnd();
		echo "<script type='text/javascript'  src='../scriptTacometro.js'></script>";
		$this->htmlEndPage();
	} /* }}} */
}
?>
