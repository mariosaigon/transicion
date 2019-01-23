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

class SeedDMS_View_GenerarDatosAbiertos extends SeedDMS_Bootstrap_Style 
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
		//$ruta_pagina_salida="../out/out.CaducaranPronto.php";

		//$this->htmlStartPage("Gestor parámetros institucionales", "skin-blue sidebar-mini");
		if($user->isAdmin())
		{
			$this->htmlStartPage("Generar datos en CSV", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("Generar datos abiertos CSV", "skin-blue layout-top-nav");
		}
		
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar($folder->getID(),0,0);
		}
		//$this->contentContainerStart("hoa");
		$this->contentStart();
          
		?>
		 
       <?php
        if(!$user->isAdmin() && !$user->isGuest())
        {
          echo '<ol class="breadcrumb">
        <li><a href="/out/out.ViewFolder.php?folderid=16&showtree=1#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Generador de datos abiertos en CSV</li>
      </ol>';  
        }
        else
        {
            if($user->isGuest())
            {
                echo '<ol class="breadcrumb">
        <li><a href="../index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Generador de datos abiertos en CSV</li>
      </ol>';
            }
            
        }
      	 
        ?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary("Presione el siguiente botón para generar un archivo CSV con todas sus reservas:");
 $accion="/generarCSV.php";
echo "<form action=\"".$accion."\" method=\"post\">";
//$this->contentContainerStart();
//////INICIO MI CODIGO

  ?>
  <input type="hidden" name="idUser" id="idUser" value="<?php print $user->getID();?>">
 <button type="submit" id="generaCSV" class="btn btn-default btn-block">Generar CSV</button>
              <?php
 //////FIN MI CODIGO                 
//$this->contentContainerEnd();

echo "</form>";
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
