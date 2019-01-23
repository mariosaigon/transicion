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


 $tabla=$_GET['tabla'];
  $idUsuario=$_GET['idUsuario'];
 $valor=$_GET['valor'];
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
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("anadirElemento.php: No se pudo conectar a la BD de la aplicación"));
	}	
	//2-Hacer borrado
	$miQuery="INSERT into  $tabla VALUES (NULL,$idUsuario,'$valor')";
	echo "mi query: ".$miQuery;
	$resultado=$manejador->getResult($miQuery);//en este punto está insertado

?>