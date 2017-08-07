<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg){
	case "01":include("listpindahtangan.php");break;
	case "02":include("listpindahtangan_.php");break;
	case "03":include("entrypindahtangan.php");break;
	default: include("listpindahtangan.php");
	break;
}
/*switch($SPg){
	case "01":if(empty($ridModul10)){include("entrypindahtangan.php");}else{include("home.php");}break;
	case "02":
		include("listpindahtangan.php");
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
*/
?>