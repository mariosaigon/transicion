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

class SeedDMS_View_EstadisticasDepartamentales extends SeedDMS_Bootstrap_Style 
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

//hago el cálculo del número de reservas por Municipio
    $numActasMunicipios=$actasAhuachapan+$actasCabañas+$actasChalate+$actasCusca+$actasLaLibertad+$actasLaPaz+$actasLaUnion+$actasMorazan+$actasSanMiguel+$actasSanSalvador+$actasSanVicente+$actasSantaAna+$actasSonsonate+$actasUsulutan;


    $numReservasMunicipios=$reservasAhuachapan+$reservasCabañas+$reservasChalate+$reservasCusca+$reservasLaLibertad+$reservasLaPaz+$reservasLaUnion+$reservasMorazan+$reservasSanMiguel+$reservasSanSalvador+$reservasSanVicente+$reservasSantaAna+$reservasSonsonate+$reservasUsulutan;

$numSinRegistroMunicipios=$sinAhuachapan+$sinCabañas+$sinChalate+$sinCusca+$sinLaLibertad+$sinLaPaz+$sinLaUnion+$sinMorazan+$sinSanMiguel+$sinSanSalvadorl+$sinSanVicente+$sinSantaAna+$sinSonsonate+$sinUsulutan;

		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
		$ruta_pagina_salida="../out/out.CaducaranPronto.php";

		$this->htmlStartPage(getMLText("mi_sitio"), "skin-blue sidebar-mini");
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
 <!-- line chart canvas element -->
 <!--
        <canvas id="buyers" width="600" height="400"></canvas>
         pie chart canvas element 
        <canvas id="countries" width="600" height="400"></canvas>
        bar chart canvas element -->
           <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Estadísticas por departamento</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="graficoBarras" style="height:250px"></canvas>
              </div>
              <a href="/out/out.TablaDepartamentos.php">Ver esta gráfica como tabla </a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->















      <div id="js-legend"></div>
      <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Actas de inexistencia, índices de reserva y sin registro contenidos en  ALCALDÍAS</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="noexiste" style="height:250px"></canvas>
              <canvas id="pastel1" style="height:250px"></canvas>
            
                          <div class="box-body no-padding">
              <table class="table table-condensed">
                <tr>
                  <th style="width: 10px">Documento</th>

                  <th style="width: 40px">Número</th>
                </tr>
                <tr>
                  <td>Actas</td>               
                  <td><span class="badge bg-red"><?php echo $numActasMunicipios ?></span></td>
                </tr>
                <tr>
                  <td>Índices</td>               
                  <td><span class="badge bg-yellow"><?php echo $numReservasMunicipios ?></span></td>
                </tr>
                <tr>
                  <td>Número de entes  municipales sin ningún registro</td>                
                  <td><span class="badge bg-green"><?php echo $numSinRegistroMunicipios ?></span></td>
                </tr>
              </table>
            </div>
             
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->






          <div id="js-legend"></div>
      <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Actas de inexistencia, índices de reserva y sin registro contenidos en ENTES NO MUNICIPALES</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
            <canvas id="noexiste" style="height:250px"></canvas>
              <canvas id="pastel2" style="height:250px"></canvas>
                          <div class="box-body no-padding">
              <table class="table table-condensed">
                <tr>
                  <th style="width: 10px">Documento</th>

                  <th style="width: 40px">Número</th>
                </tr>
                <tr>
                  <td>Actas</td>               
                  <td><span class="badge bg-red"><?php echo $acumuladorActas ?></span></td>
                </tr>
                <tr>
                  <td>Índices</td>               
                  <td><span class="badge bg-yellow"><?php echo $acumuladorReservas ?></span></td>
                </tr>
                <tr>
                  <td>Número de entes no municipales sin ningún registro</td>                
                  <td><span class="badge bg-green"><?php echo $acumuladorSin ?></span></td>
                </tr>
              </table>
            </div>
             
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


     <?php  
      //////FIN MI CODIGO               
//$this->contentContainerEnd();
$this->endsBoxPrimary();
     ?>
	     </div>
		</div>
		</div>
<!-- MANDO -->
<input type="hidden" id="acumuladorReservas" value="<?php echo $acumuladorReservas ?>" />
<input type="hidden" id="acumuladorActas" value="<?php echo $acumuladorActas ?>" />
<input type="hidden" id="acumuladorSin" value="<?php echo $acumuladorSin ?>" />

<input type="hidden" id="actasAhuachapan" value="<?php echo $actasAhuachapan ?>" />
<input type="hidden" id="actasCabañas" value="<?php echo $actasCabañas ?>" />
<input type="hidden" id="actasChalate" value="<?php echo $actasChalate ?>" />
<input type="hidden" id="actasCusca" value="<?php echo $actasCusca ?>" />
<input type="hidden" id="actasLaLibertad" value="<?php echo $actasLaLibertad ?>" />
<input type="hidden" id="actasLaPaz" value="<?php echo $actasLaPaz ?>" />
<input type="hidden" id="actasLaUnion" value="<?php echo $actasLaUnion ?>" />
<input type="hidden" id="actasMorazan" value="<?php echo $actasMorazan ?>" />
<input type="hidden" id="actasSanMiguel" value="<?php echo $actasSanMiguel ?>" />
<input type="hidden" id="actasSanSalvador" value="<?php echo $actasSanSalvador ?>" />
<input type="hidden" id="actasSanVicente" value="<?php echo $actasSanVicente ?>" />
<input type="hidden" id="actasSantaAna" value="<?php echo $actasSantaAna ?>" />
<input type="hidden" id="actasSonsonate" value="<?php echo $actasSonsonate ?>" />
<input type="hidden" id="actasUsulutan" value="<?php echo $actasUsulutan ?>" />

<input type="hidden" id="reservasAhuachapan" value="<?php echo $reservasAhuachapan ?>" />
<input type="hidden" id="reservasCabañas" value="<?php echo $reservasCabañas ?>" />
<input type="hidden" id="reservasChalate" value="<?php echo $reservasChalate ?>" />
<input type="hidden" id="reservasCusca" value="<?php echo $reservasCusca ?>" />
<input type="hidden" id="reservasLaLibertad" value="<?php echo $reservasLaLibertad ?>" />
<input type="hidden" id="reservasLaPaz" value="<?php echo $reservasLaPaz ?>" />
<input type="hidden" id="reservasLaUnion" value="<?php echo $reservasLaUnion ?>" />
<input type="hidden" id="reservasMorazan" value="<?php echo $reservasMorazan ?>" />
<input type="hidden" id="reservasSanMiguel" value="<?php echo $reservasSanMiguel ?>" />
<input type="hidden" id="reservasSanSalvador" value="<?php echo $reservasSanSalvador ?>" />
<input type="hidden" id="reservasSanVicente" value="<?php echo $reservasSanVicente ?>" />
<input type="hidden" id="reservasSantaAna" value="<?php echo $reservasSantaAna ?>" />
<input type="hidden" id="reservasSonsonate" value="<?php echo $reservasSonsonate ?>" />
<input type="hidden" id="reservasUsulutan" value="<?php echo $reservasUsulutan ?>" />

<input type="hidden" id="numActasMunicipios" value="<?php echo $numActasMunicipios ?>" />
<input type="hidden" id="numReservasMunicipios" value="<?php echo $numReservasMunicipios ?>" />
<input type="hidden" id="numSinRegistroMunicipios" value="<?php echo $numSinRegistroMunicipios ?>" />
		<?php	
		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		//$this->contentContainerEnd();/bower_componentsdatos
  //script que dibuja
   //echo "<script > var actasAhuachapan= $actasAhuachapan; </script>";
    echo '<script type="text/javascript" src="/styles/multisis-lte/bower_components/Chart.js/Chart.js"></script>'."\n"; //agregado 
  // echo "<script type='text/javascript'src='/styles/multisis-lte/bower_components/meterVariables.js'></script>";

  echo "<script type='text/javascript'  src='/styles/multisis-lte/bower_components/datos.js'></script>";

		$this->htmlEndPage();

	} /* }}} */

}


?>  

