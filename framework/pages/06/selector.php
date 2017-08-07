<?php
//selector01.php
//include("navatas.php");
//include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg){
	case "01":
		if(empty($ridModul06)){include("entrydpb.php");}else{include("home.php");}
		//include("entrydpb.php");
	break;
	case "02":
		include("listdpb.php");
	break;
	case "caribarang":
		include("caribarang.php");
	break;
	case "carirekening":
		include("carirekening.php");
	break;
	case "":
	default:		
		include("listdpb.php");		
	break;
}
?>