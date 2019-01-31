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

class SeedDMS_View_ProximasCaducidades extends SeedDMS_Bootstrap_Style 
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
    $baseServer = $this->params['baseServer'];
		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
		$ruta_pagina_salida="out/out.CaducaranPronto.php";

		if($user->isAdmin())
		{
			$this->htmlStartPage("reservas que están próximas a caducar", "skin-blue sidebar-mini sidebar-collapse",$baseServer);
		}
		else
		{
			$this->htmlStartPage("reservas que están próximas a caducar", "skin-blue layout-top-nav",$baseServer);
		}
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar();
		}
		//$this->contentContainerStart("hoa");
		$this->contentStart();
          
		?>
		<?php
        if(!$user->isAdmin() && !$user->isGuest())
        {
          echo '<ol class="breadcrumb">
        <li><a href="out/out.ViewFolder.php?folderid=16&showtree=1#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Próximas caducidades</li>
      </ol>';  
        }
        else
        {
            if($user->isGuest())
            {
                echo '<ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Próximas caducidades</li>
      </ol>';
            }
            
        }
      	 
        ?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary(getMLText("mis_reservas"));
$this->contentContainerStart();





   echo "<form action=\"".$ruta_pagina_salida."\" method=\"post\">";
    //echo "Name: <input type=\"text\" name=\"name\"><br>";
   ///////////////
    echo "<div class=\"col-xs-3\">";
    echo  "<label>".getMLText("reserva_caduca_en")."</label>";
echo "<input type=\"number\" class=\"form-control\" name=\"caduca_dentro_de\" placeholder=\"dentro de\">";
 echo "</div>"; 
///////////////
   echo "<div class=\"form-group\">";
echo  "<label>".getMLText("franja_tiempo")."</label>";
   echo "<select class=\"form-control\" name=\"franja\">";
    echo "<option value=\"1\">".getMLText("dias")."</option>";
   echo "<option value=\"2\">".getMLText("months")."</option>";
   echo "<option value=\"3\">".getMLText("years")."</option>";
    echo "</select>";
    echo "</div>";
///////////////

  echo "<div class=\"checkbox\">";
                 echo" <label>";
                    echo "<input type=\"checkbox\" name=\"chequeado\">".getMLText("lista_todos_a_caducar");
                  echo "</label>";
              echo "</div>";

  echo "<div class=\"box-footer\">";
               echo  "<button type=\"submit\" class=\"btn btn-primary\">".getMLText("search")."</button>";
              echo "</div>";
echo "</form>";
   

                  
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
