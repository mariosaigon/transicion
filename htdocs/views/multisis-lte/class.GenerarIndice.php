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
	}	
	//query de consulta:
	$miQuery="SELECT homefolder FROM tblUsers WHERE id=".$id_usuario;
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);
	$id_folder=$resultado[0]['homefolder'];
	//echo "id_folder: ".$id_folder;
	return $id_folder;
}
class SeedDMS_View_GenerarIndice extends SeedDMS_Bootstrap_Style 
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
		//$id_numero_declaratoria = $this->params['id_numero_declaratoria'];
		//$id_fecha_clasificacion = $this->params['id_fecha_clasificacion'];
		$idFolderAlcaldias = $this->params['idFolderAlcaldias'];
		$idFolderEntes = $this->params['idFolderEntes'];

		$db = $dms->getDB();
		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);
		$ruta_pagina_salida="../out/out.CaducaranPronto.php";
		//nombres de atributos (para darle variabilidad)
				$nombreVariableFechaClasificacion="Fecha de clasificación";
				$nombreVariableFundamento="Fundamento legal (Art, 19 LAIP)";
				$nombreVariableTipoClasificacion="Tipo de clasificación";
				$nombreVariableUnidades="Unidad Administrativa";
				$nombreVariableNumReserva="No. de Declaración de Reserva";
				$nombreVariablMotivo="Motivo de la reserva";
				$nombreVariableAutoridad="Autoridad que reserva";
		if($user->isAdmin())
		{
			$this->htmlStartPage("generar y consolidar índice de información reservada del sistema", "skin-blue sidebar-mini sidebar-collapse");
		}
		else
		{
			$this->htmlStartPage("generar y consolidar índice de información reservada", "skin-blue layout-top-nav");
		}
		$this->containerStart();
		$this->mainHeader();
		if($user->isAdmin())
		{
			$this->mainSideBar();
		}
		$this->contentStart();         
		?>
		<ol class="breadcrumb">
        <li><a href="/out/out.ViewFolder.php?folderid=16&showtree=1#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Generador de índice</li>
      </ol>
    <div class="gap-10"></div>
    <div class="row">
    <div class="col-md-12">     
    <?php
    //en este bloque php va "mi" código
  $accion="/generarExcel.php";
  $accionAdmin="/generarExcelSilent.php";
 $this->startBoxPrimary(getMLText("generar_mi_indice"));
$this->contentContainerStart(); 
/////////////////////////////// PRIMERA COMPROBACION: SI ES OFICIAL, SE GENERA EL ÍNDICE DE INFORMACION
//SOLO DEL ENTE. Comprobación añadida 11 oct. Por Mario
    if(!$user->isAdmin() && !$user->isGuest()) // si no es admin ni guest, es usuario normal (oficiañ)
    {
echo "<form action=\"".$accion."\" method=\"post\">";
echo "<p> Con esta acción, usted generará el índice de información reservada de su ente obligado, basado en todas las declaratorias de reserva de documentos y actas de inexistencia que hubiese subido a este sistema.</p>";

echo "<p> Esta operación generará un archivo de hoja de cálculo (Excel) editable, para que ud. lo modifique según sus necesidades, o bien lo publique directamente en el portal de transparencia de su institución.</p>";

  echo "<center><img src=\"/images/WinrarIAIP.jpg\" alt=\"Pantalla de descarga\" height=\"375\" width=\"700\"></center>";
   
echo "<p>Al  pulsar el botón \"He finalizado mi índice\"  se descargará ese fichero Excel, para lo cual deberá selccionar la carpeta dentro del disco de su computadora donde desea almacenarlo. </p>";
echo "<p><b> Además, se le notificará al IAIP sobre esta acción y se entenderá que ha finalizado su índice dentro de esta convocatoria, por lo cual, toda futura modificación a alguna declaratoria o acta de inexistencia podrá hacerse, pero se le notificará de nuevo al IAIP sobre esta situación, y dicho cambio deberá requerir la aprobación del Oficial de Información del Instituto. </b> Si tiene cualquier consulta, siempre puede contactar con el IAIP para solventarla.</p>";
 

     echo "<div class=\"box-footer\">";
      echo "<div class=\"checkbox\">";
                 echo" <label>";
                    echo "<input type=\"checkbox\" name=\"chequeado\">".getMLText("El índice no ha sido modificado desde la última convocatoria");
                  echo "</label>";
              echo "</div>";

               //echo  "<button type=\"submit\" class=\"btn btn-warning\">".getMLText("Terminar mi índice de información reservada")."</button>";

               echo ' <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Validar el índice de información reservada
              </button>';


             echo ' <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Los siguientes documentos serán enviados para validación / publicación:</h4>
              </div>
              <div class="modal-body">';


                echo "<p>Al presionar el botón \"Enviar\" los siguientes documentos se considerarán incluidos en su índice de reserva: </p>";
                $todosDocs=$user->getDocuments();
                echo "<ul>";
                foreach ($todosDocs as $doc) 
                {
                	echo "<li>".$doc->getName()."</li>";
                }
                echo "</ul>";

                    echo "<p>Además, se descargará un archivo de Excel con el consolidado del índice para los usos que considera conveniente. </p>";
              echo '</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Enviar para validación / publicación</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>';


              echo "</div>";     


              //// LE MANDO AL SCRIPT DE GENERAR EXCEL DATOS DE MANERA SILENCIOSA (HIDDEN) PARA PARÁMETROS DEL EXCEL  
              //1) Nombre del Oficial de Información
              
              $nombreUsuario=$user->getFullName();
              //echo "nombre oficial: ".$nombreUsuario;
               echo "<input type=\"hidden\" name=\"nombreUsuario\"  value=\"".$nombreUsuario."\">";
			   echo "</input>";   
			   //2) Nombre del ente obligado
			   $idusuario=$user->getID();
			   $id_folder_ente=getDefaultUserFolder($idusuario);
				$nombreEnte=$dms->getFolder($id_folder_ente)->getName();;
				echo "<input type=\"hidden\" name=\"nombreEnteObligado\"  value=\"".$nombreEnte."\">";
			   echo "</input>";      
			   //3) fecha de generación del archivo
			    $date = date('d-m-Y H:i:s');
			 //echo "fecha corta:".$date;
			 echo "<input type=\"hidden\" name=\"fechaGeneracion\"  value=\"".$date."\">";
			   echo "</input>";    
			   //4) nombresDocumentos
			   /**
					Para obtener esos atributos de cada documento, me valdré de la clase Usuario, obtendré todos los documnetos del usuairo, y luego para cada documento, obtendre el atributo deseado

			   **/ 
				//declaración de los array recolectores de la información, que enviaré 'por el formulario'		
				$nombresDocumentos=array();
				$unidadesEnte=array();
				$numerosReserva=array();
				$tiposReserva=array();
				$autoridadReserva=array();
				$fundamentoLegal=array();
				$fechaClasificacion=array();
				$motivoReserva=array();
				$fechaDesclasificacion=array();
				
				///arrays para indice de desclasificación
				$des_FechaDesclasificación=array();
				$des_NombreDocumento=array();
				


				/////
				$listaDocumentosUsuario=$user->getDocuments();
				foreach ($listaDocumentosUsuario as $documento) 
				{
					$nameDoc=$documento->getName();
					$caducaDoc=$documento->getExpires();
					$caducaDoc=date('d/m/Y', $caducaDoc);   
					//echo "Documento analizado: ".$nameDoc;
					if(!$documento->hasExpired()) //no puedo generar índice de documentos caducados
					{
						$listaCategorias=$documento->getCategories();
							foreach ($listaCategorias as $categoria) 
							{
								//echo "Categorías del documento:: ".$categoria->getName();
								if(strcmp($categoria->getName(),"Declaratorias de reserva")==0)
								{
									$nombresDocumentos[] = $nameDoc; //meto en el array el nombre de documento, el cual NO ES UN ATRIBUTO
									$fechaDesclasificacion[]= $caducaDoc;              //tampoco es un atributo
									//obtengo los atributos	
									$atributos=$documento->getAttributes();
									foreach ($atributos as $atributoParticular)  //es de la clase SeedDMS_Core_Attribute
									{
										$idAtributo=$atributoParticular->getID();
										$valorAtributo=$atributoParticular->getValue();
										$definiciónAtributo=$atributoParticular->getAttributeDefinition();// es de la clase SeedDMS_Core_AttributeDefinition.html
										$nombreAtributo=$definiciónAtributo->getName();
										//echo " </br> Id del atributo del documento :".$idAtributo." </br> value: ".$valorAtributo." </br> Nombre Atributo: </br>".$nombreAtributo."--**";
										//Unidad administrativa
										if (strcmp($nombreAtributo, $nombreVariableUnidades)==0)
										{
											$unidadesEnte[]=$valorAtributo;
										}
										//números de reserva
										if (strcmp($nombreAtributo, $nombreVariableNumReserva)==0)
										{
											
											$numerosReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableTipoClasificacion)==0)
										{
											$tiposReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableAutoridad)==0)
										{
											$autoridadReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableFundamento)==0)
										{
											$fundamentoLegal[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableFechaClasificacion)==0)
										{
											$fechaClasificacion[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariablMotivo)==0)
										{
											$motivoReserva[]=$valorAtributo;
										}

										
										
									}




								}
							}
							
					}// fin if ha expirado
					else
					{
						//echo "El documento ".$nameDoc." ya esta expirado";
					}
					
				}

				//"MANDAR AL FORMULARIO" LOS DATOS COMO input
				foreach ($nombresDocumentos as $nd) 
				{
					echo "<input type=\"hidden\" name=\"nombresDocumentos[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				foreach ($fechaDesclasificacion as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fechaDesclasificacion[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 /**
					
				 **/
				 foreach ($unidadesEnte as $nd) 
				{
					echo "<input type=\"hidden\" name=\"unidadesEnte[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($numerosReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"numerosReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($tiposReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"tiposReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($autoridadReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"autoridadReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
			    foreach ($fundamentoLegal as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fundamentoLegal[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				  foreach ($fechaClasificacion as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fechaClasificacion[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				  foreach ($motivoReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"motivoReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}

//LOGO DEL ENTE OBLIGADO
if($user->hasImage())
{
	$rutafoto=$user->getImage();
	//echo "rutafoto: ".print_r($rutafoto);
	echo "<input type=\"hidden\" name=\"urlFoto\" value=\"".$rutafoto['image']."\">"; //lo mando por formulario al darle submit
}

echo "</form>";
	} //fin if primera comprobacion
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////  SEGUNDA COMPROBACION: si es un administrador, debe generar el indice en excel de cada uno,
    //y devolver un zip con todos ellos
    if($user->isAdmin())
    {

echo "<p> Con esta acción, usted generará el consolidado del índice de información reservada de TODOS LOS ENTES OBLGADOS, según el estado en el que se encuentren en este instante en el sistema.</p>";

echo "<p> Esta operación generará un archivo comprimido (zip) conteniendo todos los archivos de índice (Excel) de cada ente obligado.</p>";

  echo "<center><img src=\"/images/WinrarIAIP.jpg\" alt=\"Pantalla de descarga\" height=\"600\" width=\"700\"></center>";
   
echo "<p>Al  pulsar el botón \"Descargar índices\"  se descargará ese comprimido, para lo cual deberá selccionar la carpeta dentro del disco de su computadora donde desea almacenarlo. </p>";
echo "<p><b> Recuerde, que como Oficial del IAIP, únicamente usted puede generar este consolidado. El resto de oficiales únicamente pueden generar un Excel correspondiente a su entidad. </b> Además, figuran entradas de los índices, de aquellas declaratorias de reserva aprobadas, o en proceso de aprobación, pero no las caducadas ni removidas.</p>";
 

     echo "<div class=\"box-footer\">";

               echo  "<button type=\"button\" id=\"myButton\"  onclick=\"genera(); \"class=\"btn btn-warning\">".getMLText("Terminar mi índice de información reservada")."</button>";
              echo "</div>";   
              echo "<p id=\"demo\"></p>";  
              //// LE MANDO AL SCRIPT DE GENERAR EXCEL DATOS DE MANERA SILENCIOSA (HIDDEN) PARA PARÁMETROS DEL EXCEL  
              //1) para cada oficial de informacion, hago la generacion de ese índice
              $todosOficiales=$dms->getAllUsers();
             
              $cont=1;
              foreach ($todosOficiales as $oficial) 
              {
              	//echo "usuario: ".$oficial->getFullName()."</br>";

              	if(!$oficial->isAdmin() && !$oficial->isGuest())//me da todos los usuarios, pero quiero aquellos que son oficiales (ni admin ni guest)
              	{
              		//echo "METIDO EN PARTE BUENA </br>";
              		//echo "valor de cont que tendra form: ".$cont."</br>";
              	echo "<form  name ='$cont' action=\"".$accionAdmin."\" id=\"".$cont."\" method=\"post\">";
              	 $cont=$cont+1;
				$nombreUsuario=$oficial->getFullName();
              //echo "nombre oficial: ".$nombreUsuario;
               echo "<input type=\"hidden\" name=\"nombreUsuario\"  value=\"".$nombreUsuario."\">";
			   echo "</input>";   
			   //2) Nombre del ente obligado
			   $carpetaDestino="";
			   $idusuario=$oficial->getID();
			   $id_folder_ente=getDefaultUserFolder($idusuario);
			   $nombreEnte=$dms->getFolder($id_folder_ente)->getName();;

			   //para saber si es el oficial de un ente municipal o no. Añadido por Mario 12 oct 2017
			   $folderPapa="";
			   if(strcmp($nombreEnte,"Directorio central")!=0)
			   {
				   	$folderPapa=$dms->getFolder($id_folder_ente)->getParent();
				   	//echo "mi papa: ".$folderPapa->getName();
				   $idFolderPapa=$folderPapa->getID();
				   if($idFolderPapa==$idFolderEntes)
				   {
				   	 $carpetaDestino="Entes no municipales";
				   }
				    else 
				    {
				    	$folderAbuelo=$dms->getFolder($id_folder_ente)->getParent()->getParent();
				    	$idFolderAbuelo=$folderAbuelo->getID();
				    	   if($idFolderAbuelo==$idFolderAlcaldias)
						   {
						   	 $carpetaDestino="Alcaldias municipales";
						   }
				    }
			   }
			   //////////////////////////////
				echo "<input type=\"hidden\" name=\"carpetaDestino\"  value=\"".$carpetaDestino."\">";
			   echo "</input>"; 
				echo "<input type=\"hidden\" name=\"nombreEnteObligado\"  value=\"".$nombreEnte."\">";
			   echo "</input>";      
			   //3) fecha de generación del archivo
			    $date = date('d-m-Y H:i:s');
			 //echo "fecha corta:".$date;
			 echo "<input type=\"hidden\" name=\"fechaGeneracion\"  value=\"".$date."\">";
			   echo "</input>";    
			   //4) nombresDocumentos
			   /**
					Para obtener esos atributos de cada documento, me valdré de la clase Usuario, obtendré todos los documnetos del usuairo, y luego para cada documento, obtendre el atributo deseado

			   **/ 
				//declaración de los array recolectores de la información, que enviaré 'por el formulario'		
				$nombresDocumentos=array();
				$unidadesEnte=array();
				$numerosReserva=array();
				$tiposReserva=array();
				$autoridadReserva=array();
				$fundamentoLegal=array();
				$fechaClasificacion=array();
				$motivoReserva=array();
				$fechaDesclasificacion=array();
				
				///arrays para indice de desclasificación
				$des_FechaDesclasificación=array();
				$des_NombreDocumento=array();
				


				/////
				$listaDocumentosUsuario=$oficial->getDocuments();
				foreach ($listaDocumentosUsuario as $documento) 
				{
					$nameDoc=$documento->getName();
					$caducaDoc=$documento->getExpires();
					$caducaDoc=date('d/m/Y', $caducaDoc);   
					//echo "Documento analizado: ".$nameDoc;
					if(!$documento->hasExpired()) //no puedo generar índice de documentos caducados
					{
						$listaCategorias=$documento->getCategories();
							foreach ($listaCategorias as $categoria) 
							{
								//echo "Categorías del documento:: ".$categoria->getName();
								if(strcmp($categoria->getName(),"Declaratorias de reserva")==0)
								{
									$nombresDocumentos[] = $nameDoc; //meto en el array el nombre de documento, el cual NO ES UN ATRIBUTO
									$fechaDesclasificacion[]= $caducaDoc;              //tampoco es un atributo
									//obtengo los atributos	
									$atributos=$documento->getAttributes();
									foreach ($atributos as $atributoParticular)  //es de la clase SeedDMS_Core_Attribute
									{
										$idAtributo=$atributoParticular->getID();
										$valorAtributo=$atributoParticular->getValue();
										$definiciónAtributo=$atributoParticular->getAttributeDefinition();// es de la clase SeedDMS_Core_AttributeDefinition.html
										$nombreAtributo=$definiciónAtributo->getName();
										//echo " </br> Id del atributo del documento :".$idAtributo." </br> value: ".$valorAtributo." </br> Nombre Atributo: </br>".$nombreAtributo."--**";
										//Unidad administrativa
										if (strcmp($nombreAtributo, $nombreVariableUnidades)==0)
										{
											$unidadesEnte[]=$valorAtributo;
										}
										//números de reserva
										if (strcmp($nombreAtributo, $nombreVariableNumReserva)==0)
										{
											
											$numerosReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableTipoClasificacion)==0)
										{
											$tiposReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableAutoridad)==0)
										{
											$autoridadReserva[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableFundamento)==0)
										{
											$fundamentoLegal[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariableFechaClasificacion)==0)
										{
											$fechaClasificacion[]=$valorAtributo;
										}
										if (strcmp($nombreAtributo, $nombreVariablMotivo)==0)
										{
											$motivoReserva[]=$valorAtributo;
										}

										
										
									}




								}
							}
							
					}// fin if ha expirado
					else
					{
						//echo "El documento ".$nameDoc." ya esta expirado";
					}
					
				}

				//"MANDAR AL FORMULARIO" LOS DATOS COMO input
				foreach ($nombresDocumentos as $nd) 
				{
					echo "<input type=\"hidden\" name=\"nombresDocumentos[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				foreach ($fechaDesclasificacion as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fechaDesclasificacion[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 /**
					
				 **/
				 foreach ($unidadesEnte as $nd) 
				{
					echo "<input type=\"hidden\" name=\"unidadesEnte[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($numerosReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"numerosReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($tiposReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"tiposReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				 foreach ($autoridadReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"autoridadReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
			    foreach ($fundamentoLegal as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fundamentoLegal[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				  foreach ($fechaClasificacion as $nd) 
				{
					echo "<input type=\"hidden\" name=\"fechaClasificacion[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}
				  foreach ($motivoReserva as $nd) 
				{
					echo "<input type=\"hidden\" name=\"motivoReserva[]\" value=\"".$nd."\">"; //lo mando por formulario al darle submit
					//echo "</input>"; 
				}

				/* //LOGO DEL ENTE OBLIGADO
				if($oficial->hasImage())
				{
					$rutafoto=$oficial->getImage();
					//echo "rutafoto: ".print_r($rutafoto);
					echo "<input type=\"hidden\" name=\"urlFoto\" value=\"".$rutafoto['image']."\">"; //lo mando por formulario al darle submit
				}
 */

              	}
              	
              	echo "</form>";
              //echo "antes de llamar a multiform, valor de cont: ".$cont;
       

              } //fin foreach de recorrer todos los nombres de oficiales
              //echo "numero total de formularios: ".$cont;
            
               echo "<input type=\"hidden\" id=\"meter\" value=$cont>";

    }//fin if segunda comprobacion

 //// fin de caja, comun a ambas comprobaciones
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
