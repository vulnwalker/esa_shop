<?php
/*
header("Content-type: text/xml; charset=utf-8"); 
//echo "<xml><data><status>0</status><qty>0</qty></data></xml>";
echo "<xml>";
	  	echo "<rec>";         
	  	echo "<bidang>". "b</bidang>";
	  echo "</rec>";         
	  
	//}
	echo "</xml>";
*/
//*
//ob_start("ob_gzhandler");
//include("common/vars.php");
//include("config.php");
$MySQL->HOST = "localhost";
$MySQL->USER = "root";
$MySQL->PWD  = "";
$MySQL->DB   = "db_sislog_innodb_2010";
$KoneksiMySQL = mysql_connect($MySQL->HOST,$MySQL->USER,$MySQL->PWD) or die("Koneksi ke MySQL Server Gagal");
$BukaDataBase = mysql_select_db($MySQL->DB) or die("Database $MySQL->DB, tidak ada");
$Pg = isset($HTTP_GET_VARS["Pg"]) ? $HTTP_GET_VARS["Pg"] : "";
 switch ($Pg) {
 	case 'status': include("pages/barcode/status.php");	break;	
	case 'data_print' : include('pages/barcode/data_print.php'); break;
	case 'update_status' : include('pages/barcode/update_status.php'); break;	
	//case 'tes_status': include("pages/barcode/tes_status.php");	break;
	default: header("Location: http://atisisbada.net/");break;
 }
//ob_flush();
//flush();
//*/
?>