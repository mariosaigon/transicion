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

 function add3dots($string, $repl, $limit) 
{
  if(strlen($string) > $limit) 
  {
    return substr($string, 0, $limit) . $repl; 
  }
  else 
  {
    return $string;
  }
}
///////////////////////// OBTENGO PARÁMETROS DESDE EL FORMULARIO
//1 Nombre del ente obligado
$idCarpeta="1"; //valor por defecto
if (isset($_POST["idCarpeta"])) //se envía a través del método POST porque es un formulario
{
	//echo "aqui";
	$idCarpeta=$_POST["idCarpeta"];
	//echo "creador excel: ".$creadorExcel;
}	
//2) nombre del ente obligado
$fichero="";
if (isset($_POST["ficheroExcel"])) //se envía a través del método POST porque es un formulario
{
	$fichero=$_POST["ficheroExcel"];
}	 
//echo "id ingresado: ".$idCarpeta;
//echo "fichero: ".$fichero;


$carpeta=$dms->getFolder($idCarpeta);
$nombreCarpeta=$carpeta->getName();
//echo "Nombre carpeta: ".$nombreCarpeta; 
$row = 1;
if (($handle = fopen($fichero, "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 4096, "|")) !== FALSE) 
	{
			if($row!=1)
			{
				echo "Entrada #: ".$row."<br>";
				echo "Nombre ente".$data[0]."<br>"; $nombreEnte=utf8_encode($data[0]);
				echo "Comentario: ".$data[1]."<br>"; $comentario=utf8_encode($data[1]);		
				echo "--------------------------------<br>";
														
				////////hago la metida como tal
				
				$owner=$dms->getUser(1); //el dueño será el administrador que tiene id1

				////llamada a función que añade. SEGUN LA API:
				/**
				
				addSubFolder(string $name, string $comment, object $owner, integer $sequence, array $attributes) : object
				string	$name	
name of folder

string	$comment	
comment of folder

object	$owner	
owner of folder

integer	$sequence	
position of folder in list of sub folders.

array	$attributes	
list of document attributes. The element key

   must be the id of the attribute definition.
				**/ 
				
				
				$resu=$carpeta->addSubFolder($nombreEnte,$comentario,$owner,0,NULL);
				if(!$resu)
				{
					echo "Error metiendo subcarpeta especificado en la fila_ ".$row." : ".$nombreEnte;
					exit;
				}
			}		
        $row++;      
    }
    fclose($handle);
}
// $handle = fopen($fichero, 'r');
// while (($entrada = fgetcsv($handle,8096)) !== FALSE) 
// {
   // echo "*************************************";
   // echo "</br>";
  
    // print_r($entrada);
   // echo "</br>";
   // echo "------------------------FIN ENTRADA -------------------";
   // echo "</br>";
// }
// fclose($handle);
echo "SCRIPT TERMINADOR;";
exit;
// ?>