<?php

include("./inc/inc.Settings.php");
include("./inc/inc.Utils.php");
include("./inc/inc.Init.php");
include("./inc/inc.DBInit.php");
$name="";
$pk=""; //me dirá el numero de linea del fichero
$value="";  //nuevo contenido de la línea pk

if(isset($_POST['pk']))
{
  $pk=$_POST['pk'];
}
if(isset($_POST['name']))
{
  $name=$_POST['name'];
}
if(isset($_POST['value']))
{
  $value=$_POST['value'];
  //$value = iconv("UTF-8","windows-1250",$value);
}
if(isset($_POST['value']))
{
  $value=$_POST['value'];
  //$value = iconv("UTF-8","windows-1250",$value);
}
 // echo "value: ".$value;
 // echo "Posición dentro de fila pk: ".$pk;
 //  echo "Name: ".$name;
//$file = file("./fechasEntrega.txt");
$fichero="./fechasEntrega.txt";
$lineas = file($fichero);//file in to an array
  $linea=0;
  if($pk<=4)
  {
    $linea=0; //julio
  }
  else
  {
    $linea=1;
  }
$entrada= $lineas[$linea];
//echo "Entrada: ".$entrada;

$arrayEntrada=explode(",", $entrada);
// echo "valores ANTES de cambiar:";
// foreach ($arrayEntrada as $key) 
// {
//   echo $key."<br>";

// }
//ahora la magia: hago el cambio en el array
$arrayEntrada[$pk]=$value;
$lineaCambiada=implode(",", $arrayEntrada);
// echo "valores DEPUES de cambiar:";
// foreach ($arrayEntrada as $key2) 
// {
//   echo $key2."<br>";

// }
/////////////
$file = file($fichero);
$lines = array_map(function ($value) { return rtrim($value, PHP_EOL); }, $file);

$lines[$linea] = $lineaCambiada."\r\n";
$lines = array_values($lines);
$content = implode(PHP_EOL, $lines);
file_put_contents($fichero, $content);
?>