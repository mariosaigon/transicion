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

class SeedDMS_View_ProcesarMulti extends SeedDMS_Bootstrap_Style 
{
 /**
 Método que muestra los documentos próximos a caducar sólo de 
 **/
	

	function show() 
	{ /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		
		
		$cachedir = $this->params['cachedir'];
		$workflowmode = $this->params['workflowmode'];
		$previewwidth = $this->params['previewWidthList'];
		$timeout = $this->params['timeout'];
		$cantidadSubida = $this->params['cantidadSubida'];
		$arrayNombres = $this->params['cantidadSubida'];
		$arrayRubros = $this->params['arrayRubros'];
		
	

		$this->htmlStartPage("Subida de documentos reservados exitosa", "skin-blue layout-top-nav");
		$this->containerStart();
		$this->mainHeader();
		//$this->mainSideBar();
		//$this->contentContainerStart("hoa");
		$this->contentStart();
          
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary("Reservas subidas exitosamente");
$this->contentContainerStart();
//////INICIO MI CODIGO
?>
 <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php 
              		echo $cantidadSubida;
              		?></h3>

              <p>
              	<?php 
              echo "Reservas subidass exitosamente.";
               echo "<ul>";
               foreach ($arrayRubros as $key) 
               {
               	echo "<li>$key</li>";
               }
               
  
                echo "</ul>";
              ?>
              </p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-copy"></i>
            </div>
            <a href="/out/out.ViewFolder.php?folderid=1" class="small-box-footer">Regresar a la pantalla principal<i class="fa fa-arrow-circle-right"></i></a>
          </div>
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
		$this->htmlEndPage();
	} /* }}} */
}
?>
