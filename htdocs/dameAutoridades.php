<?php 
include("./inc/inc.Settings.php");
include("./inc/inc.LogInit.php");
include("./inc/inc.Utils.php");
include("./inc/inc.Language.php");
include("./inc/inc.Init.php");
include("./inc/inc.Extension.php");
include("./inc/inc.DBInit.php");
include("./inc/inc.Authentication.php");
include("./inc/inc.ClassUI.php");

  $idUsuario=$_GET['idUsuario'];

 //echo "mi tabla: ".$tabla;
//borrar con sql el elemento con id $id de la tabla $tabla
 //1 inicializar bd
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
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("dameRubros.php: No se pudo conectar a la BD de la aplicación"));
	}	
	//2-Hacer borrado
	$miQuery="SELECT autoridad from autoridades WHERE idUsuario=$idUsuario";
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResultArray($miQuery);//en este punto está insertado
foreach ($resultado as $a) 
  {
    foreach ($a as $valor) 
    {
       $arrayMunicipios[]=$valor;
	   //echo "Municipio: ".$valor;
    }
  }
echo json_encode($arrayMunicipios);

?>