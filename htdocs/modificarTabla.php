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


$name="";
$pk=""; //me dirá el numero de linea del fichero
$value="";  //nuevo contenido de la línea pk
$tabla="";  //nonbre de la tabla
$nombreColumna="";
if(isset($_POST['pk']))
{
  $pk=$_POST['pk'];
}
if(isset($_POST['name'])) //nombre de la tabla
{
  $name=$_POST['name'];
  $cosa=explode("_", $name);
  $nombreColumna=$cosa[0];
}
if(isset($_POST['value']))
{
  $value=$_POST['value'];
}
if(isset($_POST['tabla']))
{
  $tabla=$_POST['tabla'];
}
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
		UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("modificarTabla.php: No se pudo conectar a la BD de la aplicación"));
	}	
	//2-Hacer borrado
	$miQuery="UPDATE $tabla SET $nombreColumna = '$value' WHERE id=$pk";
	//echo "mi query: ".$miQuery;
	$resultado=$manejador->getResult($miQuery);//en este punto está insertado

?>