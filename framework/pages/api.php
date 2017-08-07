<?php

ob_start("ob_gzhandler");
/* ganti selector di index */
include("common/vars.php");
include("config.php");
include("common/menu.php");


$Pg = isset($HTTP_GET_VARS["Pg"]) ? $HTTP_GET_VARS["Pg"] : "";

switch ($Pg) {
		case 'daftarBI':{
			include('API/daftarbi.php');
			break;
		}
		case 'upload':{
			include('API/upload.php');
			break;
		}
		default: {
			include('API/login.php');
			break;
		}		
}

?>