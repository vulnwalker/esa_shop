<?php
//selector01.php
//include("navatas.php");
//include("navbawah.php");
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg)
{
	//case "01":if(empty($ridModul09)){include("entrypenghapusan.php");}else{include("home.php");}break;
	//case "01":if(empty($ridModul09) ){include("entrypenghapusan.php");}else{include("home.php");}break;
	case "01":include("entrypenghapusan.php");break;
	/*case "02":
		include("listpenghapusan.php");
	break;*/
	case '03': include('penghapusanForm.php');break;
	case "caribarang":
		include("caribarang.php");
	break;
	case "carirekening":
		include("carirekening.php");
	break;
	//case "":
	case '06':include("listhapussebagian.php");break;
	case '07':include("entryhapussebagian.php");break;
	

	default:
		include("entrypenghapusan.php");
	break;
}
?>