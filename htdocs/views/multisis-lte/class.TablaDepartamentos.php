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

class SeedDMS_View_TablaDepartamentos extends SeedDMS_Bootstrap_Style 
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

    $acumuladorActas = $this->params['acumuladorActas'];
    $acumuladorReservas = $this->params['acumuladorReservas'];
      $acumuladorSin = $this->params['acumuladorSin'];
		 //////////
		$arrayNombres = $this->params['arrayNombres'];
		$reservasAhuachapan = $this->params['reservasAhuachapan'];
		$actasAhuachapan = $this->params['actasAhuachapan'];
		$sinAhuachapan = $this->params['sinAhuachapan'];

		$reservasCabañas = $this->params['reservasCabañas'];
		$actasCabañas = $this->params['actasCabañas'];
		$sinCabañas = $this->params['sinCabañas'];

    $reservasChalate = $this->params['reservasChalate'];
    $actasChalate = $this->params['actasChalates'];
    $sinChalate = $this->params['sinChalate'];

      $reservasCusca = $this->params['reservasCusca'];
    $actasCusca = $this->params['actasCusca'];
    $sinCusca = $this->params['sinCusca'];

     $reservasLaLibertad = $this->params['reservasLaLibertad'];
    $actasLaLibertad = $this->params['actasLaLibertad'];
    $sinLaLibertad = $this->params['sinLaLibertad'];

     $reservasLaPaz = $this->params['reservasLaPaz'];
    $actasLaPaz = $this->params['actasLaPaz'];
    $sinLaPaz = $this->params['sinLaPaz'];

      $reservasLaUnion = $this->params['reservasLaUnion'];
    $actasLaUnion = $this->params['actasLaUnion'];
    $sinLaUnion = $this->params['sinLaUnion'];

    $reservasMorazan = $this->params['reservasMorazan'];
    $actasMorazan = $this->params['actasMorazan'];
    $sinMorazan = $this->params['sinMorazan'];

        $reservasSanMiguel = $this->params['reservasSanMiguel'];
    $actasSanMiguel = $this->params['actasSanMiguel'];
    $sinSanMiguel = $this->params['sinSanMiguel'];

            $reservasSanSalvador = $this->params['reservasSanSalvador'];
    $actasSanSalvador = $this->params['actasSanSalvador'];
    $sinSanSalvadorl = $this->params['sinSanSalvadorl'];

            $reservasSanVicente = $this->params['reservasSanVicente'];
    $actasSanVicente = $this->params['actasSanVicente'];
    $sinSanVicente = $this->params['sinSanVicente'];

            $reservasSantaAna = $this->params['reservasSantaAna'];
    $actasSantaAna = $this->params['actasSantaAna'];
    $sinSantaAna = $this->params['sinSantaAna'];

           $reservasSonsonate = $this->params['reservasSonsonate'];
    $actasSonsonate = $this->params['actasSonsonate'];
    $sinSonsonate = $this->params['sinSonsonate'];

         $reservasUsulutan = $this->params['reservasUsulutan'];
    $actasUsulutan = $this->params['actasUsulutan'];
    $sinUsulutan = $this->params['sinUsulutan'];

		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
		$ruta_pagina_salida="../out/out.CaducaranPronto.php";

		$this->htmlStartPage(getMLText("Estadísticas departamentales (tabla)"), "skin-blue sidebar-mini");
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
  
 $this->startBoxPrimary(getMLText("Estadísticas del índice de reserva"));
//$this->contentContainerStart();

//////INICIO MI CODIGO
?>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Número de actas de inexistencia e índices de reserva por departamento</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
 <div class="box-body table-responsive no-padding">
<table class="table table-hover">
        <tr>
                  <th>Departamento</th>
                  <th>Número de actas de inexistencia</th>
                  <th>Número de índices de reserva</th>
                  <th>Municipios sin registro</th>                
                </tr>
<?php 
               foreach ($arrayNombres as $departamento) 
               {
                echo "<tr>";
                  echo"<td>".$departamento."</td>";
                  switch ($departamento) 
                  {
                    case 'Ahuachapán':
                      echo "<td>".$actasAhuachapan."</td>";
                      echo"<td>".$reservasAhuachapan."</td>";
                      echo"<td>".$sinAhuachapan."</td>";
                      break;
                    
                    case 'Cabañas':
                      echo "<td>".$actasCabañas."</td>";
                      echo"<td>".$reservasCabañas."</td>";
                      echo"<td>".$sinCabañas."</td>";
                      break;

                      case 'Chalatenango':
                      echo "<td>".$actasChalate."</td>";
                      echo"<td>".$reservasChalate."</td>";
                      echo"<td>".$sinChalate."</td>";
                      break;

                       case 'Cuscatlán':
                      echo "<td>".$actasCusca."</td>";
                      echo"<td>".$reservasCusca."</td>";
                      echo"<td>".$sinCusca."</td>";
                      break;

                      case 'La Libertad':
                      echo "<td>".$actasLaLibertad."</td>";
                      echo"<td>".$reservasLaLibertad."</td>";
                      echo"<td>".$sinLaLibertad."</td>";
                      break;

                      case 'La Paz':
                      echo "<td>".$actasLaPaz."</td>";
                      echo"<td>".$reservasLaLibertad."</td>";
                      echo"<td>".$sinLaLibertad."</td>";
                      break;

                        case 'La Unión':
                      echo "<td>".$actasLaUnion."</td>";
                      echo"<td>".$reservasLaUnion."</td>";
                      echo"<td>".$sinLaUnion."</td>";
                      break;

                        case 'Morazán':
                      echo "<td>".$actasMorazan."</td>";
                      echo"<td>".$reservasMorazan."</td>";
                      echo"<td>".$sinMorazan."</td>";
                      break;

                         case 'San Miguel':
                      echo "<td>".$actasSanMiguel."</td>";
                      echo"<td>".$reservasSanMiguel."</td>";
                      echo"<td>".$sinSanMiguel."</td>";
                      break;

                        case 'San Salvador':
                      echo "<td>".$actasSanSalvador."</td>";
                      echo"<td>".$reservasSanSalvador."</td>";
                      echo"<td>".$sinSanSalvadorl."</td>";
                      break;

                      case 'San Vicente':
                      echo "<td>".$actasSanVicente."</td>";
                      echo"<td>".$reservasSanVicente."</td>";
                      echo"<td>".$sinSanVicente."</td>";
                      break;

                       case 'Santa Ana':
                      echo "<td>".$actasSantaAna."</td>";
                      echo"<td>".$reservasSantaAna."</td>";
                      echo"<td>".$sinSantaAna."</td>";
                      break;
                        case 'Sonsonate':
                      echo "<td>".$actasSonsonate."</td>";
                      echo"<td>".$reservasSonsonate."</td>";
                      echo"<td>".$sinSonsonate."</td>";
                      break;

                        case 'Usulután':
                      echo "<td>".$actasUsulutan."</td>";
                      echo"<td>".$reservasUsulutan."</td>";
                      echo"<td>".$sinUsulutan."</td>";
                      break;
                  }
                  
                  
                 // echo "<td>Approved</td>";
                  echo"</tr>";
               } //fin foreach
?>
                </table>
                  </div>
                   </div>
                        </div>
                              </div>
















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


		$this->htmlEndPage();

	} /* }}} */

}


?>  

