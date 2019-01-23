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
 //imprime las unidades administrativas de la institución del oficial con id $id

 function imprimirLista($tabla,$columna,$id,$db)
 {
 	  $consultar="SELECT id, $columna FROM $tabla WHERE idUsuario=$id";
                     //echo "Consultar: ".$consultar;
     $res1 = $db->getResultArray($consultar);
  	
    foreach ($res1 as $doc) 
    {
  	$unidad=$doc[$columna];
  	$idRegistro=$doc['id']; //id único de la entrada en la tabla
  	$idEntrada=$columna."_".$idRegistro;
  	//echo "Identrada: ".$idEntrada;
    	echo '<div class="row">';
    	echo '<div class="col-md-10">';
		echo "<li>";
	  echo "<a href=\"#\" id=\"$idEntrada\" data-type=\"text\"  data-pk=\"$idRegistro\" data-url=\"/modificarTabla.php\" data-title=\"Enter username\">$unidad</a>";
	    echo "</li>";
	    echo '</div>'; //fin primer columna

	    echo '<div class="col-md-1">';
	    $idBorrado="borrar-".$tabla."-".$idRegistro;
	    echo "<button type=\"button\" id=\"$idBorrado\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></button>";
	    echo '</div>'; //fin segunda columna

	    echo '</div>'; //fin row
	   
	} //fin del bucle

 }

class SeedDMS_View_ParametrosInstitucionales extends SeedDMS_Bootstrap_Style 
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
			$this->htmlStartPage("página de inicio del sistema de gestión", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("Gestionar parámetros institucionales", "skin-blue layout-top-nav");
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

<input type="hidden" name="idUser" id="idUser" value="<?php print $user->getID();?>">

 <ol class="breadcrumb">
        <li><a href="/out/out.ViewFolder.php?folderid=16&showtree=1"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Gestor de parámetros de la institución</li>
      </ol>

    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary(getMLText("gestor_parametros"));
//$this->contentContainerStart();
//////INICIO MI CODIGO
?>
 <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Unidades</h3>

              <p>Definir y actualizar las Unidades Administrativas que conforman la estructura organizativa de su institución</p>
            </div>
            <div class="icon">
              <i class="ion ion-clipboard"></i>
            </div>
          
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Rubros</h3>

              <p>Definir y actualizar las rubros temáticos predefinidos para las reservas de información</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Autoridades</h3>

              <p>Definir la lista de puestos o autoridades que reservan la información dentro de la institución</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->

        <!-- ./col -->
      </div>

       <!-- ./FIN PRIMER FILA DE CAJAS DE COLORES E INICIO DE CAJAS DE EDICIÓN -->


      <div class="row">
        <div class="col-md-4">
   			      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Sus Unidades Administrativas</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <ul>
                <?php 
                $idUsuario=$user->getID();
                	imprimirLista("unidades","unidad",$idUsuario,$db);
                ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"> <!-- /.INICIO BOX FOOTER -->
                <a id="btn-add-unidades" type="submit" class="btn btn-warning pull-left">
                <i class="fa fa-plus"></i>Añadir nueva Unidad Administrativa</a>
               
                </div>   <!-- /.FIN DEL BOX FOOTER -->
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-4">
                <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Rubros temáticos de su institución</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <ul>
                <?php 
                	imprimirLista("rubros","rubro",$idUsuario,$db);
                ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"> <!-- /.INICIO BOX FOOTER -->
                <a id="btn-add-rubros" type="submit" class="btn btn-warning pull-left">
                <i class="fa fa-plus"></i>Añadir nuevo rubro temático</a>
               
                </div>   <!-- /.FIN DEL BOX FOOTER -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Autoridades que reservan</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <ul>
                <?php 
                	imprimirLista("autoridades","autoridad",$idUsuario,$db);
                ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"> <!-- /.INICIO BOX FOOTER -->
                <a id="btn-add-autoridades" type="submit" class="btn btn-warning pull-left">
                <i class="fa fa-plus"></i>Añadir nueva autoridad</a>
               
                </div>   <!-- /.FIN DEL BOX FOOTER -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
     
        <!-- /.col -->
      </div> <!-- /.fin primera fila -->
<?
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
		echo '<script type="text/javascript" src="/styles/'.$this->theme.'/jquery-editable/js/jquery-editable-poshytip.min.js"></script>'."\n";
		echo '<script type="text/javascript" src="/styles/'.$this->theme.'/poshytip-1.2/src/jquery.poshytip.min.js"></script>'."\n";
		echo "<script type='text/javascript' src='/anadirParametros.js'></script>";
		$this->htmlEndPage();
	} /* }}} */
}
?>
