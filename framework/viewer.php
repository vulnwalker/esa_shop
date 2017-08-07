<?php
ob_start("ob_gzhandler");
$tim = time();

set_time_limit(0);
//error_reporting(0);

include("common/vars.php"); 
include("config.php");

if (!CekLogin() ){	
	echo "Anda belum Login!! Silahkan login terlebih dahulu <br> <a href='index.php'>Login</a>";
}else{
setLastAktif();
include("viewer/viewerfn.php");

$SPg = cekGET('SPg');
$Pg = isset($HTTP_GET_VARS['Pg'])?$HTTP_GET_VARS['Pg']:"";
$SDest = isset($HTTP_GET_VARS['SDest'])?$HTTP_GET_VARS['SDest']:"";
$COrder = isset($HTTP_GET_VARS['COrder'])?$HTTP_GET_VARS['COrder']:"";

//---- default page --------
// echo "<br>Pg=$Pg";echo "<br>SPg=$SPg";
$addprm =""; 
if ($Pg ==''){
	//$Pg = "cari";
	// $addprm .= "?Pg=cari";
	$Pg = "rekap";
	$addprm .= "?Pg=rekap";
} 
if ($SPg ==''){
	$SPg = "03";
	$addprm .= "&SPg=03";
}
$addprm .= "&Adv=1";
if (empty($COrder)){
	$COrder = "OPD";
}
		
		
		
		

$Adv = $_GET['Adv'];

if ($Adv == 1){
	

//http://localhost/atisislog/viewer.php?Pg=cari&SPg=04&OrderByKota=1&ViewStatus=1&FilterKotaKosong=1
$OrderByKota = $_POST['OrderByKota']; //$_GET['OrderByKota']; //order by kota,, kecamatan, kelurahan -> hanya untuk kib yg ada alamat
$ViewStatus = $_POST['ViewStatus'];  // view status gambar, bersertifikat, koordinat
$FilterKotaKosong = $_POST['FilterKotaKosong'];  //filter kota yg belum diisi
//echo "orderbykota=$OrderByKota<br>";
//echo "ViewStatus=$ViewStatus<br>";
//echo "FilterKotaKosong=$FilterKotaKosong<br>";


/*
$param1 =  empty($OrderByKota) ? "": "OrderByKota=$OrderByKota";
$param2 =  empty($ViewStatus) ? "": "ViewStatus=$ViewStatus";
$param3 =  empty($FilterKotaKosong ) ? "": "FilterKotaKosong=$FilterKotaKosong";
if( !empty($param1) ){	$addPageParam = $param1; }
if (!empty($param2)){
	$addPageParam .= empty($addPageParam)? $param2: "&$param2";	
}
if (!empty($param3)){
	$addPageParam .= empty($addPageParam)? $param3: "&$param3";	
}
$addPageParam= empty($addPageParam)? "": "&$addPageParam";
//$addPageParam = $param1; 
//$addPageParam .= empty($addPageParam)? $param2: empty($param2)? "": "&$param2";
//$addPageParam .= empty($addPageParam)? $param3: empty($param3)? "": "&$param3";
*/
$addPageParam= "&Adv=1";


}

//------- set menu tab aktif ------------
switch($Pg){
	case 'cari':{ include('viewer/view_cari.php');	break; }
	case 'rekap':{ include('viewer/view_rekap.php'); break; }
	case 'chart':{ include('viewer/view_chart.php'); break; }
	case 'banding':{ include('common/daftarobj.php'); include('viewer/view_banding.php'); $banding->selector(); break; }
	
	case 'cetak':{//cetak rekap
		if ($SDest=='XLS'){
			include('viewer/view_cetak_xls.php'); 
		}else {
			include('viewer/view_cetak.php'); 
		}
		break;
	}
	case 'cetakbrg':{//cetak barang
		if ($SDest=='XLS'){
			include('viewer/view_cetakbrg_xls.php'); 
		}else {	
			$cbxDlmRibu = $_GET['cbxDlmRibu'];
		
			include('viewer/view_cetakbrg.php'); 
		}	
		//include('pages/cetak/rekap_bi_cetak.php');
		//$view->isi =  $Main->Isi ;
		
		break; 
	}
	case 'cetakCari':{
		if ($SDest=='XLS'){
			include('viewer/view_cetakcari_xls.php'); 
		}else {
			include('viewer/view_cetakcari.php'); 
		}
		break;
	}
}




		

//-------- tampil page -------------



$cek .= '<br> fmskpd='.$fmSKPD.
	'<br> fmunit='.$fmUNIT.
	'<br> fmseksi='.$fmSEKSI.
	'<br> SPg='.$SPg.
	'<br> thn ='.$fmTahunPerolehan.
	'<br> kond ='.$selKondisiBrg.
	'<br> docari ='.$doCari.
	'<br> urut ='.$selUrut.
	'<br> menu ='.$view->menu_bar.
	'<br> kabkota ='.$selKabKota.	
	'<br> Bersertifikat ='.$selBersertifikat.
	'<br> alamat ='.$alamat.
	'<br> ld baramg ='.$kode_barang.
	'<br> nm baramg ='.$nm_barang.
	'<br> jmlbrs ='.$Main->PagePerHal.' - '.$jmlPerHal;
	
// echo $cek;


//echo $addPageParam."<br>";
echo $view->isi;

ob_flush();
	flush();
}
?>