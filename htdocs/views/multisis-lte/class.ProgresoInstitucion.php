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
 //me dice el total de expedientes que tiene asignado un usuario
 function homeUsuario($idUsuario)
 {
 	$folderID;
 	$settings = new Settings(); //acceder a parámetros de settings.xml con _antes
  	$driver=$settings->_dbDriver;
    $host=$settings->_dbHostname;
    $user=$settings->_dbUser;
    $pass=$settings->_dbPass;
    $base=$settings->_dbDatabase;
	$manejador=new SeedDMS_Core_DatabaseAccess($driver,$host,$user,$pass,$base);
	$estado=$manejador->connect();

	//primero veo viviendo en el hogar, atributo 93 (campo 5)
	$consulta="SELECT homefolder FROM tblusers WHERE id=$idUsuario";
	$resultado=$manejador->getResultArray($consulta);
	$folderID=$resultado[0]['homefolder'];
	return $folderID;
 }
function cuantosExpedientesAsignados($idUsuario,$dms)
{
 $usuario=$dms->getUser($idUsuario);
 $home=homeUsuario($idUsuario);
 //echo "#Home usuario: ".$home;
 $folderUsuario=$dms->getFolder($home);
 $total=$folderUsuario->getSubFolders("n");
 return count($total);
}//fin de ccuantosexpedientes

function imprimirInstituciones($dms)
{
	//imprime a todos los consejeros de FUSALMO SAN SALVADOR , FUSALMO SOYAPANGO, CESAL MEJICANOS Y CESAL APOPA
  //LOS DEPARTAMENTOS LEIDOS DE LA BD
	$grupoCesalApopa=$dms->getFolder(219);
	$grupoCesalMejicanos=$dms->getFolder(218);
	$grupoFusalmoSS=$dms->getFolder(37);
	$grupoFusalmoSoya=$dms->getFolder(36);

	//POR FOLDER ID: 
	//CESAL MEJICANOS: 218
	//CESAL APOPA: 219
	//FUSALMO SOYA: 36
	//FUSALMO SS: 37


  ////////////////////// EL SELECT
  echo '<select class="form-control chzn-select" id="consejero" name="consejero" onchange="myFunction()">';

  echo "<option disabled selected value>Seleccione o busque el nombre de un consejero</option>";
  $usuarioscesalapopa=$grupoCesalApopa->getSubFolders();
  foreach ($usuarioscesalapopa as $apopa) 
  {
  	$valor=$apopa->getID();
  	$nombre=$apopa->getName();
  	echo "<option value=\"".$valor."\">".$nombre."</option>";
  }
  $usuarioscesalmeji=$grupoCesalMejicanos->getSubFolders();
  foreach ($usuarioscesalmeji as $apopa) 
  {
  	$valor=$apopa->getID();
  	$nombre=$apopa->getName();
  	echo "<option value=\"".$valor."\">".$nombre."</option>";
  }
  $usuariosfusalmoss=$grupoFusalmoSS->getSubFolders();
  foreach ($usuariosfusalmoss as $apopa) 
  {
  	$valor=$apopa->getID();
  	$nombre=$apopa->getName();
  	echo "<option value=\"".$valor."\">".$nombre."</option>";
  }
  $usuariosfusalmosoya=$grupoFusalmoSoya->getSubFolders();
  foreach ($usuariosfusalmosoya as $apopa) 
  {
  	$valor=$apopa->getID();
  	$nombre=$apopa->getName();
  	echo "<option value=\"".$valor."\">".$nombre."</option>";
  }

  echo "</select>";
}// fin de imprimir departamentos



///**********************************************************************************
class SeedDMS_View_AvanceConsejero extends SeedDMS_Bootstrap_Style 
{
//recibe: un string indicando parentesco (M,P,Ma,Pa,H,A,T,Pr,O,Na)
//devuelve: cantidad de esos parentescos que hay en la base, viviendo en el hogar y fuera 


	 function js() { /* {{{ */
		$data = $this->params['data'];
		$type = 'amountpeopleparticipating';

		header('Content-Type: application/javascript');
?>
		$(document).ready( function() {
			dates = [];
		<?php
			if (in_array($type, array('docsperuser', 'genamount'))) {
				echo("type = 'pie';\n");
				echo("options = null;\n");
			}
			if($data) 
			{
				?>
				$('#data').DataTable({
					dom: 'Bfrtip',
					buttons: [
						{ extend: 'copy', text: '<?php echo getMLText('copy'); ?>' },
						{ extend: 'csvHtml5', title: '<?php echo getMLText('chart_'.$type.'_title'); ?>' },
						{ extend: 'excelHtml5', title: '<?php echo getMLText('chart_'.$type.'_title'); ?>' },
						{ extend: 'pdfHtml5', title: '<?php echo getMLText('chart_'.$type.'_title'); ?>' },
						{ extend: 'print', text: '<?php echo getMLText('print'); ?>' },
					],
					'language': {
						'search': '<?php echo getMLText('search'); ?>:',
						'emptyTable': '<?php echo getMLText('empty_table'); ?>',
						'info': '<?php echo getMLText('info'); ?>',
						'infoEmpty': '<?php echo getMLText('info_empty'); ?>',
						'infoFiltered': '<?php echo getMLText('info_filtered'); ?>',
						'lengthMenu': '<?php echo getMLText('length_menu'); ?>',
						'loadingRecords': '<?php echo getMLText('loading_records'); ?>',
						'processing': '<?php echo getMLText('processing'); ?>',
						'zeroRecords': '<?php echo getMLText('zero_records'); ?>',
						'paginate': {
							'first': '<?php echo getMLText('paginate_first'); ?>',
							'last': '<?php echo getMLText('paginate_last'); ?>',
							'next': '<?php echo getMLText('paginate_next'); ?>',
							'previous': '<?php echo getMLText('paginate_previous'); ?>'
						}
					}
				});
			});
<?php
		}
	}//fin de función JS

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
		$data = $this->params['data'];
		$baseServer = $this->params['baseServer'];
		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
				$this->htmlAddHeader('<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">'."\n", 'css');
		$this->htmlAddHeader('<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">'."\n", 'css');
		//añado Header para incluir este gràfico de lineas
		$this->htmlAddHeader('<script type="text/javascript" src="/styles/'.$this->theme.'/highcharts/modules/exporting.js"></script>');
		$this->htmlAddHeader('<script type="text/javascript" src="/styles/'.$this->theme.'/highcharts/modules/export-data.js"></script>');

		$this->htmlAddHeader(
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>'."\n".
			'<script type="text/javascript" src=".$baseServer."/styles/'.$this->theme.'/plugins/tacometros/raphael.js"></script>'."\n".
			'<script type="text/javascript" src="/styles/'.$this->theme.'/plugins/tacometros/justgage.js"></script>'."\n".
			'<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>'."\n");


		$this->htmlStartPage("Avance por usuario", "skin-blue sidebar-mini sidebar-collapse");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		//$this->contentContainerStart("hoa");
		$this->contentStart();
		
          
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">

    <ol class="breadcrumb">
        <li><a href="/out/out.ChartSelector.php"><i class="fa fa-dashboard"></i> Estadísticas</a></li>
        <li>Gráficos relacionados con usuarios</li>
        <li class="active">Avance para cada consejero</li>
      </ol>
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary("Avance por consejero");
$this->contentContainerStart();

 //$a=calculaParentescos("T"); echo "Tios: ".$a;
?>


              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Significado de este gráfico</h4>
        Estos gráficos muestran la cantidad de reuniones totales asignadas por cada fase a los consejeros (el número en el extremo derecho del tacómetro). El número grande al centro del tacómetro indica cuántas reuniones han tenido lugar. Un consejero que ha realizado todas las reuniones correspondientes a una fase se denotan con un tacómetro totalmente coloreado.
              </div>


 <?php 
echo "Seleccione de la siguiente lista el nombre de un consejero:<br>";
 imprimirInstituciones($dms)?>
<p id="demo"></p>
<p id="cantidadExpedientes"></p>
 <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Avance para cada consejero</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">

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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

<?php
 //////FIN MI CODIGO                 
$this->contentContainerEnd();

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
		echo "<script type='text/javascript'  src='/scriptTacometro.js'></script>";
		$this->htmlEndPage();
	} /* }}} */
}
?>
