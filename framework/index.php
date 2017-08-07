<?php

$tim = time();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

set_time_limit(0);
ob_start("ob_gzhandler");
// include("common/vars.php");
include("config.php");
include('lib/browser/Browser.php');


$browser = new Browser();


/* * ****** get post *********** */
try {
    $cid = cekPOST('cid');
//echo 'tes4';
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
$cidDPB = CekPOST('cidDPB');
$cidDKB = CekPOST('cidDKB');
$cidDKPB = CekPOST('cidDKPB');
$cidDKPPB = CekPOST('cidDKPPB');
$cidDPBT = CekPOST('cidDPBT');
$cidDPBL = CekPOST('cidDPBL');
$cidDPSB = CekPOST('cidDPSB');
$cidRKPB = CekPOST('cidRKPB');
$cidBI = CekPOST('cidBI');
$cidKIR = CekPOST('cidKIR');
$fmIDBUKUINDUK = CekPOST('fmIDBUKUINDUK');
$fmTahunPerolehan = CekPOST('$fmTahunPerolehan');
$fmTAHUNPEROLEHAN = CekPOST('fmTAHUNPEROLEHAN');
$fmSATUAN = CekPOST('fmSATUAN');

//echo $Pg;

/* * ******* get main->base (login/basemain) ******* */
if (!CekLogin()) {
    include("pages/base_login.php");
} else {
    include("pages/base_main.php");

    //------ cek enable modul
    $MyModulKU = explode(".", $HTTP_COOKIE_VARS["coModul"]);
    $disModul01 = $MyModulKU[0] == "0" ? "disabled" : "";
    $disModul02 = $MyModulKU[1] == "0" ? "disabled" : "";
    $disModul03 = $MyModulKU[2] == "0" ? "disabled" : "";
    $disModul04 = $MyModulKU[3] == "0" ? "disabled" : "";
    $disModul05 = $MyModulKU[4] == "0" ? "disabled" : "";
    $disModul06 = $MyModulKU[5] == "0" ? "disabled" : "";
    $disModul07 = $MyModulKU[6] == "0" ? "disabled" : "";
    $disModul08 = $MyModulKU[7] == "0" ? "disabled" : "";
    $disModul09 = $MyModulKU[8] == "0" ? "disabled" : "";
    $disModul10 = $MyModulKU[9] == "0" ? "disabled" : "";
    $disModul11 = $MyModulKU[10] == "0" ? "disabled" : "";
    $disModul12 = $MyModulKU[11] == "0" ? "disabled" : "";
    $disModul13 = $MyModulKU[12] == "0" ? "disabled" : "";
    $disModulref = $MyModulKU[13] == "0" ? "disabled" : "";
    $disModuladm = $MyModulKU[14] == "0" ? "disabled" : "";
}
/* * ******* get main->page (login/base/page) ************** */
$Pg = isset($HTTP_GET_VARS['Pg']) ? $HTTP_GET_VARS['Pg'] : "";
$Pg = empty($Pg) ? "00" : $Pg;
if (CekLogin ()) {
    setLastAktif();
    switch ($Pg) {
        case "perencanaan":
			if($Main->MODUL_PERENCANAAN)
			if (empty($disModul01)) {			
                include("pages/perencanaan/base.php");
                include("pages/perencanaan/perencanaan.php");
            }
		break;
		case "perencanaan_v2":
			if($Main->MODUL_PERENCANAAN)
			if (empty($disModul01)) {			
                include("pages/perencanaan_v2/base.php");
                include("pages/perencanaan_v2/perencanaan.php");
            }
		break;
		case "perencanaan_v3":
			if($Main->MODUL_PERENCANAAN)
			if (empty($disModul01)) {			
                include("pages/perencanaan_v3/base.php");
                include("pages/perencanaan_v3/perencanaan.php");
            }
		break;
        case "02":
			if ($Main->MODUL_PENGADAAN)
			if (empty($disModul02)) {
                include("pages/base.php");
                include("pages/02/selector.php");
            }break;
        case "03":
			if($Main->MODUL_PENERIMAAN)
			if (empty($disModul03)) {
                include("pages/base.php");
                include("pages/03/selector.php");
            }break;
        case "04":
			if($Main->MODUL_PENGGUNAAN)
			if (empty($disModul04)) {
                include("pages/base.php");
                include("pages/04/selector.php");
            }break;
        case "05":
			if($Main->MODUL_PENATAUSAHAAN)
            if (empty($disModul05)) {
                include("pages/base.php"); //get main->base
                include("pages/05/selector.php"); //get
            }
            break;
        case "06":
			if($Main->MODUL_PEMANFAATAN)
			if (empty($disModul06)) {
                include("pages/base.php");
                include("pages/06/selector.php");
            }break;
        case "07":
			if($Main->MODUL_PENGAMANPELIHARA){
			if (empty($disModul07)) {
                include("pages/base.php");
                include("pages/07/selector.php");
            }
			}
			break;
        case "08":
			if($Main->MODUL_PENILAIAN)	
			if (empty($disModul08)) {
                include("pages/base.php");
                include("pages/08/selector.php");
            }break;
        case "09":
			if($Main->MODUL_PENGAPUSAN)			
			if (empty($disModul09)) {
                include("pages/base.php");
                include("pages/09/selector.php");
            }break;
        case "10":
			if($Main->MODUL_PEMINDAHTANGAN)			
			if (empty($disModul10)) {
                include("pages/base.php");
                include("pages/10/selector.php");
            }break;
        case "11":
			if($Main->MODUL_PEMBIAYAAN)			
			if (empty($disModul11)) {
                include("pages/base.php");
                include("pages/11/selector.php");
            }break;
        case "12":
			if($Main->MODUL_GANTIRUGI)			
			if (empty($disModul12)) {
                include("pages/base.php");
                include("pages/12/selector.php");
            }break;
        case "13":
			if($Main->MODUL_MONITORING)			
			if (empty($disModul13)) {
                include("pages/base.php");
                include("pages/13/selector.php");
            }break;
        case "ref":
			if (empty($disModulref)) {
                include("pages/base.php");
                include("pages/ref/selector.php");
            }break;
        /*
          case "15":include("pages/base.php");include("pages/15/selector.php");}break;
          case "16":include("pages/base.php");include("pages/16/selector.php");break;
         */
        case "PR":include("pages/base.php");
            include("pages/cetak/selector.php");
            break;
			
        case "Admin":if (empty($disModuladm)) {
                include("pages/base.php");
                include("pages/admin/selector.php");
            }break;
        case "Menu":include("pages/base.php");
            include("pages/menu/selector.php");
            break;
		
        /* case "brg":{
          include("pages/base.php");
          include("pages/brg/selector.php");break;
          } */
    }
}

/* * *** get Main->NavAtas ******* */
include("pages/navatas.php");



if (!isset($Pg)) {
    $Pg = "";
}
switch ($Pg) {
    case "LogIn":
        $err = UserLogin();
        if ($err == '') {
            header("Location:index.php?");
        } else {
            $Main->Isi = $err; //"Akun dan Sandi Anda salah!!! silahkan Login Ulang";
        }
        break;
    case "LogOut":
        UserLogout();
        header("Location: index.php?Pg=");
        break;
}




/* * ****** create main->show ****** */
$Main->Show = $Main->Base;
$Main->Show = eregi_replace("<!--JUDUL-->", $Main->Judul, $Main->Show);
$Main->Show = eregi_replace("<!--NAVATAS-->", $Main->NavAtas, $Main->Show);
$Main->Show = eregi_replace("<!--NAVBAWAH-->", $Main->NavBawah, $Main->Show);
$Main->Show = eregi_replace("<!--COPYRIGHT-->", $Main->CopyRight, $Main->Show);
$Main->Show = eregi_replace("<!--ISI-->", $Main->Isi, $Main->Show);

echo $Main->Show;
/*
  }else{
  echo "Mohon maaf Browser anda tidak mendukung!. <br>
  Harap menggunakan Browser
  <a href='http://www.mozilla.com/products/download.html?product=firefox-4.0.1&os=win&lang=en-US'>Modzilla FireFox</a> atau
  <a href='http://www.google.com/chrome/eula.html?hl=id&platform=win'>Google Chrome</a>.";
  }
 */
?>
