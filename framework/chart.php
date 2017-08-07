<?php



set_time_limit(0);
//error_reporting(0);

include("common/vars.php"); 
include("config.php");

if (!CekLogin() ){	
	echo "Anda belum Login!! Silahkan login terlebih dahulu <br> <a href='index.php'>Login</a>";
}else{
setLastAktif();
// include("viewer/viewerfn.php");

$SPg = cekGET('SPg');
$SDest = isset($HTTP_GET_VARS['SDest'])?$HTTP_GET_VARS['SDest']:"";
// $COrder = isset($HTTP_GET_VARS['COrder'])?$HTTP_GET_VARS['COrder']:"";

$Pg = isset($HTTP_GET_VARS['Pg'])?$HTTP_GET_VARS['Pg']:"";


if ($SPg ==''){$SPg = "03";} //default page
if ($Pg ==''){$Pg = "Chart";} //default page
if ($COrder=='') {$COrder="00";}
$Adv = $_GET['Adv'];


//------- set menu tab aktif ------------
switch($Pg){
	case 'Chart':{ include('chart/view_chart.php'); break; }	

}




		

//-------- tampil page -------------



$cek .= '<br> fmskpd='.$fmSKPD.
	'<br> fmunit='.$fmUNIT.
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
	
//echo $cek;


//echo $addPageParam."<br>";
echo $view->isi;
}
?>