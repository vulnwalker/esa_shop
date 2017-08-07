<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":if(empty($ridModul01)){include("entryrkb.php");}else{include("home.php");}break;
	case "02":if(empty($ridModul01)){include("entrydkb.php");}else{include("home.php");}break;
	case "03":if(empty($ridModul01)){include("entryrkpb.php");}else{include("home.php");}break;
	case "04":if(empty($ridModul01)){include("entrydkpb.php");}else{include("home.php");}break;
	case "05":include("listrkb.php");break;
	case "06":include("listrkpb.php");break;
	case "07":include("listdkb.php");break;
	case "08":include("listdkpb.php");break;
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