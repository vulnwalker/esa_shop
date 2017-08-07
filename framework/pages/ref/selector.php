<?php
//selector01.php
include("navatas.php");
include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	case "01":
		include("inputrefbarang.php");
	break;
	case "02":
		include("inputrefgudang.php");
	break;
	case "03":
		include("inputrefrekening.php");
	break;
	case "04":
		include("inputrefskpd.php");
	break;
	case "09":
		include("inputrefruang.php");
	break;
	case "10":
		include("inputrefpejabat.php");
	break;
	case "05":
		include("refbarang.php");
	break;
	case "07":
		include("refrekening.php");
	break;
	case "08":
		include("inputrefskpd.php");
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