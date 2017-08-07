<?php
// include ('..\config.php');
// error_reporting(1);

function getwilayah($prefix='',$kol1_width=100, $pilihstr='Semua') {
 
    global $fmWIL, $fmxNegara, $fmxProvinsi, $fmxKotaKab, $fmxKecamatan, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;

	$cek = '';
    
	$fmxKotaKab = $_REQUEST[$prefix.'fmxKotaKab']?$_REQUEST[$prefix.'fmxKotaKab']:'0';
	$fmxKecamatan = $_REQUEST[$prefix.'fmxKecamatan']?$_REQUEST[$prefix.'fmxKecamatan']:'0';
	$cbxmode = $_REQUEST[$prefix.'cbxmode']?$_REQUEST[$prefix.'cbxmode']:'0';

    return array('dt_kota'=>selKabKota_txt($fmxKotaKab,'','',$cbxmode,$prefix) , 
	'dt_kecamatan'=>selKecamatan_txt($fmxKecamatan,'','',$fmxKotaKab,$cbxmode,$prefix) , 'cek'=>$cek);
	
}

function getwilayahgps($prefix='',$kol1_width=100, $pilihstr='Semua') {
 
    global $fmWIL, $fmxNegara, $fmxProvinsi, $fmxKotaKab, $fmxKecamatan, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
  global $fmxkeckoorgps,$fmxkeckoorbid,$fmxkotakoorgps,$fmxkotakoorbid;			

	$cek = '';
    
	$fmxKotaKab = $_REQUEST[$prefix.'fmxKotaKab']?$_REQUEST[$prefix.'fmxKotaKab']:'0';
	$fmxKecamatan = $_REQUEST[$prefix.'fmxKecamatan']?$_REQUEST[$prefix.'fmxKecamatan']:'0';
	$cbxmode = $_REQUEST[$prefix.'cbxmode']?$_REQUEST[$prefix.'cbxmode']:'0';
	
    return array('dt_kota'=>selKabKota_gps($fmxKotaKab,'','',$cbxmode,$prefix) , 
	'dt_kecamatan'=>selKecamatan_gps($fmxKecamatan,'','',$fmxKotaKab,$cbxmode,$prefix) , 
	'cek'=>$cek);
	
}



$dt_kota=''; $dt_kecamatan = '';
$idprs = $_REQUEST['idprs'];	
$prefix = $_REQUEST['nm'];
$pilihstr = $_REQUEST['pilihstr'];
// $cbxmode = $_REQUEST[$prefix.'cbxmode'];



if ($idprs=='demo')
{
// $get=getwilayah($prefix, 100, $pilihstr);
echo "	<script language=\"JavaScript\" src=\"js/jquery.js\" type=\"text/javascript\"></script>";
echo "	<script language=\"JavaScript\" src=\"js/wilayah.js\" type=\"text/javascript\"></script>";
echo 'Kota :'.$fmxKotaKab.'<br>';

	
echo "<form id=\"adminForm\" name=\"adminForm\" method=\"post\" action=\"#\">";
echo "<table><tr><td>	";
echo selKabKota_gps_div('','','',1,'Wilayah');
echo "</td></tr>	";
echo "<tr><td>	";
echo selKecamatan_gps_div('','','','',1,'Wilayah');
echo "</td></tr>	";
echo "</table>";
echo "</form>";
echo '<br>'.$get['cek'];

} else if ($idprs=='json')
{
$get=getwilayah($prefix, 100, $pilihstr);
$pageArr = array(
	'dt_kota'=>$get['dt_kota'],
	'dt_kecamatan'=>$get['dt_kecamatan'],
	'cek'=>$get['cek'] 
);
$page = json_encode($pageArr);
echo $page;	
} else if ($idprs=='jsongps') {
$get=getwilayahgps($prefix, 100, $pilihstr);
$pageArr = array(
	'dt_kota'=>$get['dt_kota'],
	'dt_kecamatan'=>$get['dt_kecamatan'],
	'cek'=>$get['cek'] 
);
$page = json_encode($pageArr);
echo $page;	

};


	
	




	
?>