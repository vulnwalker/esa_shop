<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":if(empty($ridModul11)){include("entrypembiayaan.php");}else{include("home.php");}break;
	case "02":
		include("listpembiayaan.php");
	break;
	case "caribarang":
		include("caribarang.php");
	break;
	case "carirekening":
		include("carirekening.php");
	break;
	case "":
	default:
		include("home.php");
	break;
}
?>