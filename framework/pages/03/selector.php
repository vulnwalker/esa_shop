<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":if(empty($ridModul03)){include("entrydpb1.php");}else{include("home.php");}break;
	case "02":if(empty($ridModul03)){include("entrydpb2.php");}else{include("home.php");}break;
	case "03":include("listbpb1.php");break;
	case "04":include("listbpb2.php");break;


	case "caribarang":
		include("caribarang.php");
	break;
	case "carirekening":
		include("carirekening.php");
	break;
	default:
		include("home.php");
	break;
}
?>