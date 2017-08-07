<?php

include("common/vars.php");
include ("common/fnport.php");
include('common/floodprotection.php');

$MySQL->HOST = $Main->DB_Hostname;
$MySQL->USER = $Main->DB_User;
$MySQL->PWD  = $Main->DB_Pass;
$MySQL->DB   = $Main->DB_Databasename;
$MySQL->PORT   = $Main->DB_Port;
$KoneksiMySQL = mysql_connect($MySQL->HOST.$MySQL->PORT,$MySQL->USER,$MySQL->PWD) or die("Koneksi ke MySQL Server Gagal");

$DirBase	=	"";

$BukaDataBase = mysql_select_db($MySQL->DB) or die("Database $MySQL->DB, tidak ada");

include ("common/fnfile.php");
?>
