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
function obtenerValorFecha($mes,$posicion) 
{
	$fichero="../fechasEntrega.txt";
	$lineas = file($fichero);//file in to an array
	$linea=0;
	if(strcmp($mes, 'julio')==0)
	{
		$linea=0;
	}
	if(strcmp($mes, 'enero')==0)
	{
		$linea=1;
	}
	$entrada= $lineas[$linea];
	//echo "Linea: ".$entrada;
	$vals=explode(",", $entrada);
	$salida=$vals[$posicion];
	//echo $salida."<br>";
	return $salida;
	//ya me posicioné en la línea correspondiente, ahora devuelvo lo indicado
}

class SeedDMS_View_GestorPlazosEntrega extends SeedDMS_Bootstrap_Style 
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
		$fechita=date('d-m-Y');
	    $fechaCortada=explode("-", $fechita);
		$mes=intval($fechaCortada[1]);
		$anio=intval($fechaCortada[2]);

		$this->htmlStartPage("Gestor de fecha de plazos de entrega del índice", "skin-blue sidebar-mini sidebar-collapse");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		//$this->contentContainerStart("hoa");
		$this->contentStart();
          
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary("Defina las fechas de inicio y fin del plazo para entrega del índice de información reservada");
//$this->contentContainerStart();
//////INICIO MI CODIGO

echo "Convocatoria de julio <br>";
$julioInicioMes=obtenerValorFecha('julio',0); // 0 significa: obtener mes inicio de Julio
$julioInicioDia=obtenerValorFecha('julio',1); // 0 significa: obtener inicio de Julio
$julioFinMes=obtenerValorFecha('julio',2); // 0 significa: obtener inicio de Julio
$julioFinDia=obtenerValorFecha('julio',3); // 0 significa: obtener inicio de Julio
 //echo "<li>";
 echo "Mes de inicio de la Convocatoria de julio <br>";
	  echo "<a href=\"#\" id=\"mesInicioJulio\" data-type=\"select\" data-pk=\"0\" data-url=\"../modificarFechaPlazo.php\" data-source=\"{'junio': 'junio','julio': 'julio'}\" >$julioInicioMes</a><br>";
 //echo "</li>";
 echo "Día de inicio de la Convocatoria de julio <br>";

   echo "<a href=\"#\" id=\"diaInicioJulio\" data-type=\"select\" data-pk=\"1\" data-url=\"../modificarFechaPlazo.php\" data-source=\"{'1': '1','2': '2','3': '3','4': '4','5': '5','6': '6','7': '7','8': '8','9': '9','10': '10','11': '11','12': '12','13': '13','14': '14','15': '15','16': '16','17': '17','18': '18','19': '19','20': '20','21': '21','22': '22','23': '23','24': '24','25': '25','26': '26','27': '27','28': '28','29': '29','30': '30','31': '31'}\" >$julioInicioDia</a><br>";

//////////////////////////////////////////////////////////////////////////////////////////////
   echo "Mes de finalización de la Convocatoria de julio <br>";
	  echo "<a href=\"#\" id=\"mesFinJulio\" data-type=\"select\" data-pk=\"2\" data-url=\"../modificarFechaPlazo.php\" data-source=\"{'junio': 'junio','julio': 'julio'}\" >$julioFinMes</a><br>";
 //echo "</li>";
 echo "Día de finalización de la Convocatoria de julio <br>";

   echo "<a href=\"#\" id=\"diaFinJulio\" data-type=\"select\" data-pk=\"3\" data-url=\"../modificarFechaPlazo.php\" data-source=\"{'1': '1','2': '2','3': '3','4': '4','5': '5','6': '6','7': '7','8': '8','9': '9','10': '10','11': '11','12': '12','13': '13','14': '14','15': '15','16': '16','17': '17','18': '18','19': '19','20': '20','21': '21','22': '22','23': '23','24': '24','25': '25','26': '26','27': '27','28': '28','29': '29','30': '30','31': '31'}\" >$julioFinDia</a>";


 //////FIN MI CODIGO                 
$this->endsBoxPrimary();
     ?>
	     </div>
		</div>
		</div>


		<?php	
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		echo '<script type="text/javascript" src="/styles/'.$this->theme.'/jquery-editable/js/jquery-editable-poshytip.min.js"></script>'."\n";
		echo '<script type="text/javascript" src="/styles/'.$this->theme.'/poshytip-1.2/src/jquery.poshytip.min.js"></script>'."\n";
		echo "<script type='text/javascript' src='/modificarPlazo.js'></script>";
		$this->htmlEndPage();
	} /* }}} */
}
?>
