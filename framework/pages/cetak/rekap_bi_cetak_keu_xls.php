<?php
set_time_limit(0);
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="REKAPITULASI BUKU INVENTARIS BARANG.xls"');


include('rekapbi_xls.php');
$Main->PagePerHal = 100;
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");

$cbxDlmRibu = cekPOST("cbxDlmRibu");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
$fmWIL = cekPOST("fmWIL"); 
$fmSKPD = cekPOST("fmSKPD"); $cek .= 'skpd ='.$fmSKPD.'<br>';
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmBIDANG = cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");


$Info = "";


$fmTahun = cekPOST('fmTahun',date('Y'));
$tglAwal = $fmTahun.'-1-1';
$tglAkhir = $fmTahun.'-12-31';
$fmKONDBRG = $_REQUEST['fmKONDBRG'];

list($ListData, $jmlData,$jmlTotBarangA,$jmlTotHargaA) = 
	Mutasi_RekapByBrg_GetList2_keu_xls($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu), FALSE, 3, $fmSEKSI, $fmKONDBRG,0,0,0,0			
			);
list($ListDataB, $jmlData,$jmlTotBarangB,$jmlTotHargaB) = 
	Mutasi_RekapByBrg_GetList2_keu_xls($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu), FALSE, 3, $fmSEKSI, $fmKONDBRG,1,$jmlTotBarangA,$jmlTotHargaA,1
			
			);

$ListData .= $ListDataB;

PrintSKPD();
PrintTTD();

$ListData =list_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,$Main->Provinsi[1],$KODELOKASI,$SEKSI).
list_tableheader($cbxDlmRibu).$ListData;


/*
//$KondisiC = $fmSKPD == "00" ? "":" and bi.c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and bi.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and bi.e='$fmSUBUNIT' ";
//$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}' and bi.b='$fmWIL' and bi.c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}'  and bi.c='$fmSKPD' $KondisiD $KondisiE ";
if($fmSKPD == "00"){
	$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}' $KondisiD $KondisiE ";
}
$Kondisi .=  ' and status_barang <> 3 ';

	

$jmlTotalHargaDisplay = 0;
$ListData = "";

$no=0;
$cb=0;

$QryRefBarang = mysql_query("select ref.f,ref.g,ref.nm_barang from ref_barang as ref where h='00' order by ref.f,ref.g");
$jmlData = mysql_num_rows($QryRefBarang);
$TotalHarga = 0.00;
$TotalBarang = 0;
$no=$Main->PagePerHal * (($HalDefault*1) - 1);


PrintSKPD();
PrintTTD();

$ListData =list_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,'JAWA BARAT',$KODELOKASI).
list_tableheader($cbxDlmRibu);


while($isi=mysql_fetch_array($QryRefBarang))
{
	$Kondisi1 = "concat(bi.f,bi.g)= '{$isi['f']}{$isi['g']}'";
	$QryBarang = mysql_query("select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi where $Kondisi1 $Kondisi group by bi.f,bi.g order by bi.f,bi.g");
	$isi1 = mysql_fetch_array($QryBarang);
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$kdBidang = $isi['g'] == "00"?"":$isi['g'];
	$nmBarang = $isi['g'] == "00"?"<b>{$isi['nm_barang']}</b>":"&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
	$QryBarangAtas = mysql_fetch_array(mysql_query("select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi where bi.f='{$isi['f']}' $Kondisi group by f order by f"));
	$jmlBarangAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:$isi1['jml_barang'];
	$jmlBarangAtas = empty($jmlBarangAtas) ? "0" : "".$jmlBarangAtas."";
	$jmlBarangAtas = $isi['g'] == "00" ? "<b>".$jmlBarangAtas."" : "".$jmlBarangAtas."";
	//$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']/1000),0,',', '.')."":"".number_format(($isi1['jml_harga']/1000),0,',', '.')."";
	
	if ( !empty($cbxDlmRibu) ){			
		$jmlHargaAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_harga']/1000 : $isi1['jml_harga']/1000 ;
	}else{			
		$jmlHargaAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_harga']:$isi1['jml_harga'];			
	}
	
	
	$TotalHarga +=  $isi1['jml_harga'];
	$TotalBarang +=  $isi1['jml_barang'];
	
	$ListData .= list_table($no,$isi['f'],$kdBidang,$nmBarang,$jmlBarangAtas,$jmlHargaAtas,'');
	
	$cb++;
}

$tampilTotHarga = number_format(($TotalHarga), 2, '.', ''); 



$ListData  .= list_tablefooter($TotalBarang,$tampilTotHarga,'');


// aray combo pencarian barang 
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

$ListData .= list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);
*/
echo $ListData;	
ob_flush();
flush(); 

?>