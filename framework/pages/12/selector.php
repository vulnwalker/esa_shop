<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":if(empty($ridModul12)){include("entrygantirugi.php");}else{include("home.php");}break;
	case "02":	include("listgantirugi_.php"); break;
	case "caribarang":	include("caribarang.php");	break;
	case "carirekening":	include("carirekening.php");break;
	case '03': include("listgantirugi.php"); break;
	
	default: include("listgantirugi.php"); break;
}
?>