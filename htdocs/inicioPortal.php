<?php
ini_set('error_reporting', E_ALL^E_STRICT);

include("./inc/inc.Settings.php");
include("./inc/inc.Utils.php");
include("./inc/inc.Language.php");
include("./inc/inc.Init.php");
include("./inc/inc.Extension.php");
include("./inc/inc.DBInit.php");
include("./inc/inc.ClassUI.php");
include("./inc/inc.ClassAccessOperation.php");

function cuentaReservas() //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
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

  $miQuery="
CREATE TEMPORARY TABLE IF NOT EXISTS `ttstatid` (PRIMARY KEY (`statusID`), INDEX (`maxLogID`)) 
            SELECT `tblDocumentStatusLog`.`statusID`, 
            MAX(`tblDocumentStatusLog`.`statusLogID`) AS `maxLogID` 
            FROM `tblDocumentStatusLog` 
            GROUP BY `tblDocumentStatusLog`.`statusID` 
            ORDER BY `maxLogID`;                      
                        CREATE TEMPORARY TABLE `ttcontentid` (PRIMARY KEY (`document`), INDEX (`maxVersion`)) 
            SELECT `tblDocumentContent`.`document`, 
            MAX(`tblDocumentContent`.`version`) AS `maxVersion` 
            FROM `tblDocumentContent` 
            GROUP BY `tblDocumentContent`.`document` 
            ORDER BY `tblDocumentContent`.`document`;

  SELECT DISTINCT `tblDocuments`.*, `tblDocumentContent`.`version`, `tblDocumentStatusLog`.`status`, `tblDocumentLocks`.`userID` as `lockUser` FROM `tblDocumentContent` LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentAttributes` ON `tblDocuments`.`id` = `tblDocumentAttributes`.`document` LEFT JOIN `tblDocumentContentAttributes` ON `tblDocumentContent`.`id` = `tblDocumentContentAttributes`.`content` LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.`statusID` LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` LEFT JOIN `ttcontentid` ON `ttcontentid`.`maxVersion` = `tblDocumentStatus`.`version` AND `ttcontentid`.`document` = `tblDocumentStatus`.`documentID` LEFT JOIN `tblDocumentLocks` ON `tblDocuments`.`id`=`tblDocumentLocks`.`document` LEFT JOIN `tblDocumentCategory` ON `tblDocuments`.`id`=`tblDocumentCategory`.`documentID` WHERE `ttstatid`.`maxLogID`=`tblDocumentStatusLog`.`statusLogID` AND `ttcontentid`.`maxVersion` = `tblDocumentContent`.`version` AND `tblDocuments`.`folderList` LIKE '%:1:%' AND (`tblDocumentCategory`.`categoryID` in (3)) AND `tblDocumentStatusLog`.`status` IN (3,2);
";
  //echo "mi query: ".$miQuery;
  $mysqli = new mysqli($host, $user, $pass, $base);
  /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$contador=0;
if ($mysqli->multi_query($miQuery)) {
    do {
        /* almacenar primer juego de resultados */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) 
            {
                $contador++;
            }
            $result->free();
        }
        /* mostrar divisor */
        if ($mysqli->more_results()) {
            //printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}
  //printf("Errormessage: %s\n", $mysqli->error);
$mysqli->close();
return $contador;
}

function cuentaDesclasificados() //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
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

  $miQuery="
CREATE TEMPORARY TABLE IF NOT EXISTS `ttstatid` (PRIMARY KEY (`statusID`), INDEX (`maxLogID`)) 
            SELECT `tblDocumentStatusLog`.`statusID`, 
            MAX(`tblDocumentStatusLog`.`statusLogID`) AS `maxLogID` 
            FROM `tblDocumentStatusLog` 
            GROUP BY `tblDocumentStatusLog`.`statusID` 
            ORDER BY `maxLogID`;                      
                        CREATE TEMPORARY TABLE `ttcontentid` (PRIMARY KEY (`document`), INDEX (`maxVersion`)) 
            SELECT `tblDocumentContent`.`document`, 
            MAX(`tblDocumentContent`.`version`) AS `maxVersion` 
            FROM `tblDocumentContent` 
            GROUP BY `tblDocumentContent`.`document` 
            ORDER BY `tblDocumentContent`.`document`;

  SELECT DISTINCT `tblDocuments`.*, `tblDocumentContent`.`version`, `tblDocumentStatusLog`.`status`, `tblDocumentLocks`.`userID` as `lockUser` FROM `tblDocumentContent` LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentAttributes` ON `tblDocuments`.`id` = `tblDocumentAttributes`.`document` LEFT JOIN `tblDocumentContentAttributes` ON `tblDocumentContent`.`id` = `tblDocumentContentAttributes`.`content` LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.`statusID` LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` LEFT JOIN `ttcontentid` ON `ttcontentid`.`maxVersion` = `tblDocumentStatus`.`version` AND `ttcontentid`.`document` = `tblDocumentStatus`.`documentID` LEFT JOIN `tblDocumentLocks` ON `tblDocuments`.`id`=`tblDocumentLocks`.`document` LEFT JOIN `tblDocumentCategory` ON `tblDocuments`.`id`=`tblDocumentCategory`.`documentID` WHERE `ttstatid`.`maxLogID`=`tblDocumentStatusLog`.`statusLogID` AND `ttcontentid`.`maxVersion` = `tblDocumentContent`.`version` AND `tblDocuments`.`folderList` LIKE '%:1:%' AND (`tblDocumentCategory`.`categoryID` in (3)) AND `tblDocumentStatusLog`.`status` IN (-2,-3);
";
  //echo "mi query: ".$miQuery;
  $mysqli = new mysqli($host, $user, $pass, $base);
  /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$contador=0;
if ($mysqli->multi_query($miQuery)) {
    do {
        /* almacenar primer juego de resultados */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) 
            {
                $contador++;
            }
            $result->free();
        }
        /* mostrar divisor */
        if ($mysqli->more_results()) {
            //printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}
  //printf("Errormessage: %s\n", $mysqli->error);
$mysqli->close();
return $contador;
}

function cuentaDeclaratorias($year) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
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

  $miQuery="
CREATE TEMPORARY TABLE IF NOT EXISTS `ttstatid` (PRIMARY KEY (`statusID`), INDEX (`maxLogID`)) 
            SELECT `tblDocumentStatusLog`.`statusID`, 
            MAX(`tblDocumentStatusLog`.`statusLogID`) AS `maxLogID` 
            FROM `tblDocumentStatusLog` 
            GROUP BY `tblDocumentStatusLog`.`statusID` 
            ORDER BY `maxLogID`;                      
                        CREATE TEMPORARY TABLE `ttcontentid` (PRIMARY KEY (`document`), INDEX (`maxVersion`)) 
            SELECT `tblDocumentContent`.`document`, 
            MAX(`tblDocumentContent`.`version`) AS `maxVersion` 
            FROM `tblDocumentContent` 
            GROUP BY `tblDocumentContent`.`document` 
            ORDER BY `tblDocumentContent`.`document`;

  SELECT DISTINCT `tblDocuments`.*, `tblDocumentContent`.`version`, `tblDocumentStatusLog`.`status`, `tblDocumentLocks`.`userID` as `lockUser` FROM `tblDocumentContent` LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentAttributes` ON `tblDocuments`.`id` = `tblDocumentAttributes`.`document` LEFT JOIN `tblDocumentContentAttributes` ON `tblDocumentContent`.`id` = `tblDocumentContentAttributes`.`content` LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.`statusID` LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` LEFT JOIN `ttcontentid` ON `ttcontentid`.`maxVersion` = `tblDocumentStatus`.`version` AND `ttcontentid`.`document` = `tblDocumentStatus`.`documentID` LEFT JOIN `tblDocumentLocks` ON `tblDocuments`.`id`=`tblDocumentLocks`.`document` LEFT JOIN `tblDocumentCategory` ON `tblDocuments`.`id`=`tblDocumentCategory`.`documentID` WHERE `ttstatid`.`maxLogID`=`tblDocumentStatusLog`.`statusLogID` AND `ttcontentid`.`maxVersion` = `tblDocumentContent`.`version` AND `tblDocuments`.`folderList` LIKE '%:1:%' AND (`tblDocumentCategory`.`categoryID` in (3)) AND (EXISTS (SELECT NULL FROM `tblDocumentAttributes` WHERE `tblDocumentAttributes`.`attrdef`= 2 AND (`tblDocumentAttributes`.`value` ) between '$year-01-01' and '$year-12-31' AND `tblDocumentAttributes`.`document` = `tblDocuments`.`id`))
";
  //echo "mi query: ".$miQuery;
  $mysqli = new mysqli($host, $user, $pass, $base);
  /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$contador=0;
if ($mysqli->multi_query($miQuery)) {
    do {
        /* almacenar primer juego de resultados */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) 
            {
                $contador++;
            }
            $result->free();
        }
        /* mostrar divisor */
        if ($mysqli->more_results()) {
            //printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}
  //printf("Errormessage: %s\n", $mysqli->error);
$mysqli->close();
return $contador;
}
function cuentaActas($year) //dado un id de usuario, me devuelve el id del folder de inicio de ese usuario
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
  $fechaInicial=strtotime("1 January $year");
  $fechaFinal=strtotime("31 December $year");


  $miQuery="
CREATE TEMPORARY TABLE IF NOT EXISTS `ttstatid` (PRIMARY KEY (`statusID`), INDEX (`maxLogID`)) 
            SELECT `tblDocumentStatusLog`.`statusID`, 
            MAX(`tblDocumentStatusLog`.`statusLogID`) AS `maxLogID` 
            FROM `tblDocumentStatusLog` 
            GROUP BY `tblDocumentStatusLog`.`statusID` 
            ORDER BY `maxLogID`;                      
                        CREATE TEMPORARY TABLE `ttcontentid` (PRIMARY KEY (`document`), INDEX (`maxVersion`)) 
            SELECT `tblDocumentContent`.`document`, 
            MAX(`tblDocumentContent`.`version`) AS `maxVersion` 
            FROM `tblDocumentContent` 
            GROUP BY `tblDocumentContent`.`document` 
            ORDER BY `tblDocumentContent`.`document`;

  SELECT DISTINCT `tblDocuments`.*, `tblDocumentContent`.`version`, `tblDocumentStatusLog`.`status`, `tblDocumentLocks`.`userID` as `lockUser` FROM `tblDocumentContent` LEFT JOIN `tblDocuments` ON `tblDocuments`.`id` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentAttributes` ON `tblDocuments`.`id` = `tblDocumentAttributes`.`document` LEFT JOIN `tblDocumentContentAttributes` ON `tblDocumentContent`.`id` = `tblDocumentContentAttributes`.`content` LEFT JOIN `tblDocumentStatus` ON `tblDocumentStatus`.`documentID` = `tblDocumentContent`.`document` LEFT JOIN `tblDocumentStatusLog` ON `tblDocumentStatusLog`.`statusID` = `tblDocumentStatus`.`statusID` LEFT JOIN `ttstatid` ON `ttstatid`.`maxLogID` = `tblDocumentStatusLog`.`statusLogID` LEFT JOIN `ttcontentid` ON `ttcontentid`.`maxVersion` = `tblDocumentStatus`.`version` AND `ttcontentid`.`document` = `tblDocumentStatus`.`documentID` LEFT JOIN `tblDocumentLocks` ON `tblDocuments`.`id`=`tblDocumentLocks`.`document` LEFT JOIN `tblDocumentCategory` ON `tblDocuments`.`id`=`tblDocumentCategory`.`documentID` WHERE `ttstatid`.`maxLogID`=`tblDocumentStatusLog`.`statusLogID` AND `ttcontentid`.`maxVersion` = `tblDocumentContent`.`version` AND ((`tblDocuments`.`keywords` like '%Acta%' OR `tblDocuments`.`name` like '%Acta%' OR `tblDocuments`.`comment` like '%Acta%' OR `tblDocumentContent`.`comment` like '%Acta%' OR `tblDocumentAttributes`.`value` like '%Acta%' OR `tblDocumentContentAttributes`.`value` like '%Acta%' OR `tblDocuments`.`id` like '%Acta%') AND (`tblDocuments`.`keywords` like '%de%' OR `tblDocuments`.`name` like '%de%' OR `tblDocuments`.`comment` like '%de%' OR `tblDocumentContent`.`comment` like '%de%' OR `tblDocumentAttributes`.`value` like '%de%' OR `tblDocumentContentAttributes`.`value` like '%de%' OR `tblDocuments`.`id` like '%de%') AND (`tblDocuments`.`keywords` like '%inexistencia%' OR `tblDocuments`.`name` like '%inexistencia%' OR `tblDocuments`.`comment` like '%inexistencia%' OR `tblDocumentContent`.`comment` like '%inexistencia%' OR `tblDocumentAttributes`.`value` like '%inexistencia%' OR `tblDocumentContentAttributes`.`value` like '%inexistencia%' OR `tblDocuments`.`id` like '%inexistencia%')) AND `tblDocuments`.`folderList` LIKE '%:1:%' AND (`tblDocuments`.`date` >= $fechaInicial AND `tblDocuments`.`date` <= $fechaFinal)
";
  //echo "mi query: ".$miQuery;
  $mysqli = new mysqli($host, $user, $pass, $base);
  /* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$contador=0;
if ($mysqli->multi_query($miQuery)) {
    do {
        /* almacenar primer juego de resultados */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) 
            {
                $contador++;
            }
            $result->free();
        }
        /* mostrar divisor */
        if ($mysqli->more_results()) {
            //printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}
  //printf("Errormessage: %s\n", $mysqli->error);
$mysqli->close();
return $contador;
}
?>
<?php 
$baseServ=$settings->_httpRoot;
?>
<!DOCTYPE html>
<html>
<head>
<?php 
echo "<base href=\"".$baseServ."\" target=\"_blank\">";
?>

<link rel="icon" 
      type="image/png" 
      href="/images/top-iaip-logo.png">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inicio | Sistema de Gestión de Información Reservada IAIP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="styles/multisis-lte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="styles/multisis-lte/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="styles/multisis-lte/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="styles/multisis-lte/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="styles/multisis-lte/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="#" class="navbar-brand"><img src="/images/top-iaip-logo.png" height="27"></img></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">

            <li><a href="https://www.secretariatecnica.gob.sv/">IAIP</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Otros enlaces IAIP <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="http://aprende.iaip.gob.sv/formacionvirtual/">Formación virtual</a></li>
                <li class="divider"></li>
                <li><a href="https://transparencia.iaip.gob.sv/">Portal de Transparencia</a></li>
               
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" action="/out/out.Search.php" method="get">
            <div class="form-group">
             <input type="text" name="query" class="form-control" placeholder="Buscar...">
      <input type="hidden" name="mode" value="1">
      <input type="hidden" name="ownerid" value="-1">
      <input type="hidden" name="resultmode" value="3">
      <input type="hidden" name="targetid" value="1">
      <input type="hidden" name="targetnameform1" value="">
            </div>
          </form>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">


            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="/images/user.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">Acceso para OI</span>
              </a>
              <ul class="dropdown-menu">

          <li class="user-body">
                  <div class="row">
                    <div class="col-xs-12 text-center">
                      <a href="#">Acceso al sistema para Oficiales de Información</a>
                    </div>
                  </div>
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-center">
                    <a href="out/out.Login.php" class="btn btn-default btn-flat">Acceder</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Sistema de Gestión del Índice de Información Reservada 
          <small>del Insituto de Acceso a la Información Pública</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> ¡Bienvenido al Sistema de Gestión del Índice de Información Reservada del Insituto de Acceso a la Información Pública!</h4>
                Aquí podrá buscar y consultar la información reservada que tiene cada ente obligado, según se ampara en la Ley de Acceso a la Información Pública de El Salvador. 
              </div>
        <!-- Info boxes -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-search"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Buscador de documentos</span>
              <span class="info-box-number"> <a href="out/out.Search.php">Acceder</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-navy"><i class="fa fa-folder"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Directorio de índices de reserva</span>
              <span class="info-box-number"> <a href="out/out.ViewFolder.php?folderid=1&showtree=1">Acceder</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->

      

        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-balance-scale"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Documentos desclasificados</span>
              <span class="info-box-number"> <a href="out/out.IndiceDesclasificados.php">Acceder</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
      </div>
      <!-- /.FIN SEGUNDA ROW CAJAS -->

       <div class="row">

        <div class="col-md-4 col-sm-6 col-xs-12">
        
        </div>


          <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-teal"><i class="fa fa-hourglass"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Reservas próximas a vencer</span>
              <span class="info-box-number"> <a href="out/out.ProximasCaducidades.php">Acceder</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


           <div class="col-md-4 col-sm-6 col-xs-12">
        
        </div>


        </div>


      <div class="row">
        <div class="col-md-12">
          <!-- LINE CHART -->
                 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Actas de inexistencia y Declaratorias de reserva emitidas por año</h3>

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
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

      </div>
      </div> <!-- /.FIN tercera row GRAFICA-->

            <div class="row">

            <?php
            $numReservados=cuentaReservas();
            $numDesclasificados=cuentaDesclasificados();

            ?>

             <div class="col-lg-2 col-xs-6">

        </div>

 <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php
            echo $numReservados;

            ?></h3>

              <p>Cantidad total de documentos reservados</p>
            </div>
            <div class="icon">
              <i class="fa fa-envelope"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php
            echo $numDesclasificados;

            ?></</h3>

              <p>Cantidad total de documentos desclasificados</p>
            </div>
            <div class="icon">
              <i class="fa fa-unlock"></i>
            </div>
        
          </div>
        </div>
        <!-- ./col -->
       

        <!-- ./col -->
      </div> <!-- /.FIN TERCERA row datos-->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <?php  
  $reservas2016=cuentaDeclaratorias(2016); 
  $reservas2017=cuentaDeclaratorias(2017); 
  $reservas2018=cuentaDeclaratorias(2018); 

  $actas2016=cuentaActas(2016);
  $actas2017=cuentaActas(2017);
  $actas2018=cuentaActas(2018);

  ?>
  <input type="hidden" id="reservas2016" value="<?php  echo $reservas2016 ?>" />
  <input type="hidden" id="reservas2017" value="<?php   echo $reservas2017 ?>" />
  <input type="hidden" id="reservas2018" value="<?php   echo $reservas2018 ?>" />

  <input type="hidden" id="actas2016" value="<?php   echo $actas2016 ?>" />
  <input type="hidden" id="actas2017" value="<?php   echo $actas2017 ?>" />
  <input type="hidden" id="actas2018" value="<?php   echo $actas2018 ?>" />

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Versión</b> 1.5
      </div>
      <strong>Copyright &copy; 2018 <a href="https://iaip.gob.sv">IAIP</a>.</strong> 
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="styles/multisis-lte/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="styles/multisis-lte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="styles/multisis-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="styles/multisis-lte/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="styles/multisis-lte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="styles/multisis-lte/dist/js/demo.js"></script>

<script type="text/javascript" src="styles/multisis-lte/bower_components/Chart.js/Chart.js"></script>

<script type='text/javascript'  src='styles/multisis-lte/bower_components/datosPortal.js'></script>

</body>
</html>
