<?php

set_time_limit(0);
// ob_start();
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header('Content-disposition: attachment; filename="Kartu Inventaris Barang (KIB) B - PERALATAN DAN MESIN.xls"');
header("Content-Transfer-Encoding: Binary");
ob_flush();
flush(); 
include('kibb_xls.php');
$AcsDsc1 = cekPOST("AcsDsc1");
$AcsDsc2 = cekPOST("AcsDsc2");
$Asc1 = !empty($AcsDsc1)? " desc ":"";
$Asc2 = !empty($AcsDsc2)? " desc ":"";
$jmPerHal = cekPOST("jmPerHal");
$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
$odr1 = cekPOST("odr1");
$odr2 = cekPOST("odr2");
$Urutkan = "";
if(!empty($odr1) && empty($odr2))
{$Urutkan = " $odr1 $Asc1, ";}
if(!empty($odr2) && empty($odr1))
{$Urutkan = " $odr2 $Asc2, ";}
if(!empty($odr2) && !empty($odr1))
{$Urutkan = " $odr1 $Asc1, $odr2 $Asc2, ";}

$HalKIB_B = cekPOST("HalKIB_B",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalKIB_B = " limit ".(($HalKIB_B	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_B = !empty($ctk)?"":$LimitHalKIB_B;
/*

$HalBI = cekPOST("HalBI",1);
$HalKIB_B = cekPOST("HalKIB_B",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_B = " limit ".(($HalKIB_B*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidKIB_B = cekPOST("cidKIB_B");

$cbxDlmRibu = cekPOST("cbxDlmRibu");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//LIST KIB_B
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE ";

//$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,2,2) . "." .$fmSUBUNIT;
$KODELOKASI = $fmKEPEMILIKAN.".".$Main->Provinsi[0].".00.".$fmSKPD . "." .$fmUNIT . "."  .$fmSUBUNIT;


if(!empty($fmCariComboIsi) && !empty($fmCariComboField)){
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

//$Kondisi.= " and status_barang=1 ";
$Kondisi .=  ' and status_barang <> 3 ';
$KondisiTotal = $Kondisi;


$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_b where $KondisiTotal ");
if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
	$jmlTotalHarga = $jmlTotalHarga[0];
}else{$jmlTotalHarga=0;}


// $Qry = mysql_query("select * from view_kib_b where $Kondisi");
$jmlDataKIB_B = 0;
// $jmlDataKIB_B = mysql_num_rows($Qry);
$Qry = mysql_query("select * from view_kib_b2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg  $LimitHalKIB_B");

$no=$Main->PagePerHal * (($HalKIB_B*1) - 1);
$cb=0;
$jmlTampilKIB_B = 0;
$JmlTotalHargaListKIB_B = 0;
$ListBarangKIB_B = "";

$qryx="select * from view_kib_b where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg  $LimitHalKIB_B";
PrintSKPD();
PrintTTD();

$ListBarangKIB_B =list_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,'JAWA BARAT',$KODELOKASI).
list_tableheader('');
/*
if ($jmlDataKIB_B<1) {
$ListBarangKIB_B .= list_table();	
}
*/

$maxrow=20;
$bufftmp=$ListBarangKIB_B;




while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilKIB_B++;
	$JmlTotalHargaListKIB_B += $isi['jml_harga'];
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$tampilHarga = $isi['jml_harga'];

	$ISI15 = ifempty($isi['ket'],'-');
	$ISI15 .= tampilNmSubUnit2($isi);	
		

	$bufftmp .= list_table($no,$isi['id_barang'],$isi['noreg'],$isi['nm_barang'],
	ifempty($isi['merk'],'-'),ifempty($isi['ukuran'],'-'),ifempty($isi['bahan'],'-'),
	ifempty($isi['thn_perolehan'],'-'),ifempty($isi['no_pabrik'],'-'),ifempty($isi['no_rangka'],'-'),
	ifempty($isi['no_mesin'],'-'),ifempty($isi['no_polisi'],'-'),ifempty($isi['no_bpkb'],'-'),
	$Main->AsalUsul[$isi['asal_usul']-1][1],$tampilHarga,$ISI15);
		


	$cb++;

	if (($cb % $maxrow ==1) & ($cb != 1) ){
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}

$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_B/1000), 0, ',', '.'):number_format(($JmlTotalHargaListKIB_B), 0, ',', '.');

$tampilHarga = number_format(($JmlTotalHargaListKIB_B), 2, '.', '');


$bufftmp  .= list_tablefooter($tampilHarga,'');
$bufftmp .= list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);


echo $bufftmp;
ob_flush();
flush(); 

?>

