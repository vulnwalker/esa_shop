<?php
//selector01.php
/*include("navatas.php");
include("navbawah.php");*/
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	
	default:
		include("home.php");
	break;
}
?>