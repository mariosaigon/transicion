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
 function check_in_range($start_date, $end_date, $date_from_user)
{
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
function consultarTerminacionIndice($id_usuario,$mes,$año) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
{
	//echo "Función getDefaultUserFolder. Se ha pasado con argumento: ".$id_usuario;
	$terminado=FALSE;
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
		echo "class.ListaTerminados.php[consultarTerminacionIndice]Error: no se pudo conectar a la BD";
		exit;
	}	
	//query de consulta:
	$miQuery="SELECT * FROM historialConvocatorias WHERE idUsuario=".$id_usuario ." AND mesConvocatoria ='".$mes."' AND yearConvocatoria= ".$año;
	//echo "mi query: ".$miQuery."</br>";
	$resultado=$manejador->getResultArray($miQuery);
	if($resultado)
	{
		$terminado=TRUE;
	}
	//echo "id_folder: ".$id_folder;
	return $terminado;
}
class SeedDMS_View_listaTerminados extends SeedDMS_Bootstrap_Style 
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
		$ruta_pagina_salida="../out/out.CaducaranPronto.php";

		if($user->isAdmin())
		{
			$this->htmlStartPage("Oficiales que ya terminaron su índice", "skin-blue sidebar-mini sidebar-collapse");
		}

		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar();
		}
		$this->contentStart();
          
		?>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">
      

    <?php
    //en este bloque php va "mi" código
  
 $this->startBoxPrimary(getMLText("Lista de oficiales que terminaron su índice"));
//$this->contentContainerStart();
//////INICIO MI CODIGO

 //////FIN MI CODIGO                 

$listaUsuarios=$dms->getAllUsers();
$fechita=date('d-m-Y');
    $fechaCortada=explode("-", $fechita);
	$mes=intval($fechaCortada[1]);
	$año=intval($fechaCortada[2]);
	$dia=intval($fechaCortada[0]);
	$mesaso="";
	//convocatoria de enero: se cuenta que la hizo en esta convocatoria si lo hace EN diciembre O Enero
	$añoCompensado=$año;
	if($mes==12 || $mes==1)
	{
		$mesaso="enero";
		$añoCompensado=$año+1;
	}
	$fechaInicial=$año."-"."06"."-"."15"; //ejemplo: 2018-06-15
	$fechaFinal=$año."-"."07"."-"."31"; //ejemplo: 2018-06-15
	//convocatoria de julIO. si lo hace entre el 15 de junio y 31 de julio
	if (check_in_range($fechaInicial, $fechaFinal, $fechita)==true)
	{
		$mesaso="julio";
	}
if(strcmp($mesaso, "")!=0) //si estoy en un mes sensible a convocatoria
	{
echo "<div class=\"row\">";
       echo "<div class=\"col-xs-12\">";
          echo "<div class=\"box\">";
           echo "<div class=\"box-header\">";
              echo "<h3 class=\"box-title\">Convocatoria ".$mesaso." ".$añoCompensado."</h3>";

              echo "<div class=\"box-tools\">
                <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
                  <input type=\"text\" name=\"table_search\" class=\"form-control pull-right\" placeholder=\"Search\">
                  
                  <div class=\"input-group-btn\">
                    <button type=\"submit\" class=\"btn btn-default\"><i class=\"fa fa-search\"></i></button>
                  </div>
                </div>
              </div>
            </div>
 <div class=\"box-body table-responsive no-padding\">
<table class=\"table table-hover\">
        <tr>
                  <th>Nombre del oficial</th>
                  <th>correo electrónico</th>
                  <th>¿Ya entregó índice en esta convocatoria?</th>            
                </tr>
";
foreach ($listaUsuarios as $usuario) 
{
	$nombrecito=$usuario->getFullName();
				$correito=$usuario->getEmail();
	echo "<tr>";              
		if(strcmp($mesaso, "enero")==0)
		{
			if($mes==12) //si es diciembre
			{
					$esta=consultarTerminacionIndice($usuario->getID(),"enero",$año+1);
				
				if($esta)
				{
				 echo "<td>".$nombrecito."</td>";
				  echo "<td>".$correito."</td>";	
	            echo "<td><span class=\"label label-success\">SI</span></td>";          
				}
				else
				{
					 echo "<td>".$nombrecito."</td>";
				  echo "<td>".$correito."</td>";	
	            echo "<td><span class=\"label label-danger\">NO</span></td>";
				}
			} //fin si es diciembre
			else //si no es diciembre, sino que es enero, febrero o marzo
			{
					$esta=consultarTerminacionIndice($usuario->getID(),"enero",$año);
				$nombrecito=$usuario->getFullName();
				$correito=$usuario->getEmail();
				if($esta)
				{
				 echo "<td>".$nombrecito."</td>";
				  echo "<td>".$correito."</td>";	
	            echo "<td><span class=\"label label-success\">SI</span></td>";          
				}
				else
				{
					 echo "<td>".$nombrecito."</td>";
				  echo "<td>".$correito."</td>";	
	            echo "<td><span class=\"label label-danger\">NO</span></td>";
				}
			}
		} //FIN SI ES ENERO
		if(strcmp($mesaso, "julio")==0)
		{
				$esta=consultarTerminacionIndice($usuario->getID(),"julio",$año);
			$nombrecito=$usuario->getFullName();
			if($esta)
			{
				echo "<td>".$nombrecito."</td>";
			  echo "<td>".$correito."</td>";	
            echo "<td><span class=\"label label-success\">SI</span></td>";
			}
			else
			{
					 echo "<td>".$nombrecito."</td>";
			  echo "<td>".$correito."</td>";	
            echo "<td><span class=\"label label-danger\">NO</span></td>";
			}
		} //FIN SI ES JULIO
		echo "</tr>";	
	} //FIN FOREACH
    echo "</table>
                  </div>
                   </div>
                        </div>
                              </div>";
} //FIN SI ESTOY EN MES SENSIBLE A CONVOCATORIA
else //estoy en cualquier otro mes
{
	switch($mes)
	{
		case 1: $mes="enero";
		break;
		
		case 2: $mes="febrero";
		break;
		
		case 3: $mes="marzo";
		break;
		
		case 4: $mes="abril";
		break;
		
		case 5: $mes="mayo";
		break;
		case 6: $mes="junio";
		break;
		
		case 7: $mes="julio";
		break;
		
		case 8: $mes="agosto";
		break;
		
		case 9: $mes="septiembre";
		break;
		
		case 10: $mes="octubre";
		break;
		
		case 11: $mes="noviembre";
		break;
		
		case 12: $mes="diciembre";
		break;
	}
			echo "<div class=\"col-lg-3 col-xs-6\">";

          echo "<div class=\"small-box bg-red\">";
            echo "<div class=\"inner\">";
              echo "<h3>".$mes." ".$año."</h3>";

              echo "<p>Aún no hay plazo de remisión de índices abierto. Se mostrarán datos en esta pantalla durante los meses de plazo de remisión (para la convocatoria de enero: meses de diciembre y enero; para la convocatoria de julio desde el 15 de junio al 31 de julio. Cuando el Oficial genere su índice de información con el botón que la herramienta tiene preparado para ello, se considerará que ha entregado su índice</p>";
            echo "</div>";
            echo "<div class=\"icon\">";
              echo "<i class=\"ion ion-pie-graph\"></i>";
            echo "</div>";
            echo "<a href=\"/\" class=\"small-box-footer\">Volver al inicio <i class=\"fa fa-arrow-circle-right\"></i></a>";
         echo " </div>
        </div>
      ";
}
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
