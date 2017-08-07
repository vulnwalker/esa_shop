<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":if(empty($ridModul02)){include("entrydpb.php");}else{include("home.php");}break;
	case "02":if(empty($ridModul02)){include("entrydppb.php");}else{include("home.php");}break;
	case "03": include("listdpb.php"); break;
	case "04": include("listdppb.php"); break;
	default:
		include("home.php");
	break;
}
?>