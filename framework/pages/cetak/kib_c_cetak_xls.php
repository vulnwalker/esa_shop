<?php
set_time_limit(0);
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="Kartu Inventaris Barang (KIB) C - GEDUNG DAN BANGUNAN.xls"');
ob_flush();
flush();
include('kibc_xls.php');
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

$HalKIB_C = cekPOST("HalKIB_C",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalKIB_C = " limit ".(($HalKIB_C	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_C = !empty($ctk)?"":$LimitHalKIB_C;

/*
$HalBI = cekPOST("HalBI",1);
$HalKIB_C = cekPOST("HalKIB_C",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_C = " limit ".(($HalKIB_C*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidKIB_C = cekPOST("cidKIB_C");

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

//LIST KIB_C
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiE1 = $fmSEKSI == "000" ? "":" and e1='$fmSEKSI' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ";

if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
//$Kondisi.= " and status_barang=1 ";
$Kondisi .=  ' and status_barang <> 3 ';
$KondisiTotal = $Kondisi;


$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_c where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,2,2) . "." .$fmSUBUNIT;
$KODELOKASI = $fmKEPEMILIKAN.".".$Main->Provinsi[0].".00.".$fmSKPD . "." .$fmUNIT . "."  .$fmSUBUNIT;

// $Qry = mysql_query("select Id from view_kib_c where $Kondisi ");
// $jmlDataKIB_C = mysql_num_rows($Qry);
$jmlDataKIB_C = 0;
$Qry = mysql_query("select * from view_kib_c2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  $LimitHalKIB_C");

$no=$Main->PagePerHal * (($HalKIB_C*1) - 1);
$cb=0;
$jmlTampilKIB_C = 0;
$JmlTotalHargaListKIB_C = 0;
$ListBarangKIB_C = "";

PrintSKPD();
PrintTTD();

$ListBarangKIB_C =list_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,'JAWA BARAT',$KODELOKASI,$SEKSI).
list_tableheader('');
/*
if ($jmlDataKIB_C<1) {
$ListBarangKIB_C .= list_table();	
}
*/
$maxrow=20;
$bufftmp=$ListBarangKIB_C;

// header("Content-Type: application/vnd.ms-excel");

while ($isi = mysql_fetch_array($Qry))
{

/*
	if (!($isi['alamat_b'] == '' || $isi['alamat_b'] =='00' )){
		$sidBi = 'select nm_wilayah from ref_wilayah where a="'.$isi['alamat_a'].'" and b="'.$isi['alamat_b'].'"'; $cek .= '<br> qrkota='.$sidBi;
		$kota = '<br>'.table_get_value( $sidBi, 'nm_wilayah');
	}
	$Kec = table_get_value('select alamat_kec from kib_c where concat(a1,a,b,c,d,e,f,g,h,i,j,noreg) = "'.
			$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'].'"','alamat_kec');
	$Kel = table_get_value('select alamat_kel from kib_c where concat(a1,a,b,c,d,e,f,g,h,i,j,noreg) = "'.
			$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'].'"','alamat_kel');
	if($Kec != ''){ $Kec='<br>Kec. '.$Kec;}
	if($Kel != ''){ $Kel='<br>Kel. '.$Kel;}
*/
	$kota = $isi['alamat_kota'] == "" ? "":"<br>".$isi['alamat_kota'];
	$Kec = $isi['alamat_kec'] == "" ? "":"<br>Kec ".$isi['alamat_kec'];
	$Kel = $isi['alamat_kel'] == "" ? "":"<br>Kel ".$isi['alamat_kel'];

//	$Kec = '<br>'.$isi['alamat_kec'];
//	$Kel = '<br>'.$isi['alamat_kel'];
		
	$jmlTampilKIB_C++;
	$JmlTotalHargaListKIB_C += $isi['jml_harga'];
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
		
	$ISI15 = ifempty($isi['ket'],'-');
	$ISI15 .= tampilNmSubUnit2($isi);	
	
	$tampilHarga = $isi['jml_harga'];

	$bufftmp .= list_table($no,$isi['id_barang'],$isi['noreg'],$isi['nm_barang'],
	ifempty($Main->KondisiBarang[$isi['kondisi_bi']-1][1],'-'),
	ifempty($Main->Tingkat[$isi['konstruksi_tingkat']-1][1],'-'),
	ifempty($Main->Beton [$isi['konstruksi_beton']-1][1],'-'),
	ifempty($isi['luas_lantai'],'0'),
	ifempty($isi['alamat'],'-').$Kel.$Kec.$kota,
	ifemptyTgl(TglInd($isi['dokumen_tgl']),'-'),
	ifempty($isi['dokumen_no'],'-'),
	ifempty($isi['luas'],'0'),
	ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-'),
	ifempty($isi['kode_tanah'],'-'),
	ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-'),
	ifempty($isi['thn_perolehan'],'-'),
	$tampilHarga,$ISI15);
		
		
	$cb++;
	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}

$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_C/1000), 0, ',', '.'):number_format(($JmlTotalHargaListKIB_C), 0, ',', '.');
$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 0, ',', '.'): number_format(($jmlTotalHarga), 0, ',', '.');

$tampilHarga = number_format(($JmlTotalHargaListKIB_C), 2, '.', '');

$bufftmp  .= list_tablefooter($tampilHarga,'');
$bufftmp .= list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);
echo $bufftmp;
ob_flush();
flush(); 

?>

