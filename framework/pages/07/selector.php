<?php
//selector01.php
// include("navatas.php");
// include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";

switch($SPg)
{
	case "01":if(empty($ridModul07)){include("entrypengamanan.php");}else{include("home.php");}break;
	case "02":if(empty($ridModul07)){include("entrypelihara.php");}else{include("home.php");}break;
	
	
	
	case "05":{
		include('pelihara_entry.php');
		break;
	}
	
	case "caribarang":
		include("caribarang.php");
	break;
	case "carirekening":
		include("carirekening.php");
	break;
		
	case "03":
		include("listpengamanan.php");
	break;
	case "04":
		include("listpelihara.php");
	break;
	default:
		include("listpelihara.php");
		//include("home.php");
	break;
}
?>