<?php
//selector01.php






include("navatas.php");
include("navbawah.php");

$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
$jns = isset($HTTP_GET_VARS["jns"])?$HTTP_GET_VARS["jns"]:"";

$View2 = $_GET['View2'];

switch($SPg){
	//case "01":if(empty($ridModul05)){include("entryidi_lama.php");}else{include("home.php");}break;
	//case "02":if(empty($ridModul05)){include("entrykir.php");}else{include("home.php");}break;
	case "01":if(empty($ridModul05)){include("entryidi_lama.php");}break;
	case "02":if(empty($ridModul05)){include("entrykir.php");}break;
	case "03":{
		if ($View2 == '1'){
			include("listbi_2.php");
		}else{
			include("listbi.php");	
		}
		break;
	}
	case "04":include("listbi.php");break;	
	case "05":include("listbi.php");break;//include("listkibb.php");break;	
	case "06":include("listbi.php");break;	//include("listkibc.php");break;	
	case "07":include("listbi.php");break;	
	case "08":include("listbi.php");break;	
	case "09":include("listbi.php");break;
	case "kibg":include("listbi.php");break;	
	case "10":include("listkir.php");break;	
	case "11":include("rekap_bi.php");break;	
	case "11a":include("rekap_bi_keu.php");break;	
	case "12":include("listMutasi.php");break;
	case "12a":include("listMutasi_a.php");break;
	case "13":include("rekap_mutasi.php");break;	
	case "14":include("rekap_mutasi2.php");break;
	case "caribarang": include("caribarang.php"); break;
	case "carirekening": include("carirekening.php"); break;
	case "setmutasi":
		if(empty($ridModul05)){include("set_mutasi.php");}else{			
			$Info = "<script>alert('User tidak diijinkan merubah data!')</script>";
			$Main->Isi = "$Info";
		}
		break;
	case "barangProses": include("barangProses.php"); break;
	case "reclass": include("reclass.php"); break;		
	case "sensus": include("sensus.php"); break;
	//case "ajx": include("listbi.php");	break;

	default:
		include("listbi.php");
		break;
}


?>