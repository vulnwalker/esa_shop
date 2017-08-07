<?php
//selector01.php
//include("navatas.php");
//include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01": include("loginlist.php");break;
		
	//case "barangProses": include("barangProses.php");break;
	case "":
	default:
		include("home.php");
	break;
}
?>