<?php
set_time_limit(0);
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="Kartu Inventaris Barang (KIB) F - KONSTRUKSI DALAM PENGERJAAN.xls"');


ob_flush();
flush();
include('kibf_xls.php');
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

$HalKIB_F = cekPOST("HalKIB_F",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalKIB_F = " limit ".(($HalKIB_F	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_F = !empty($ctk)?"":$LimitHalKIB_F;
/*
$HalBI = cekPOST("HalBI",1);
$HalKIB_F = cekPOST("HalKIB_F",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_F = " limit ".(($HalKIB_F*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidKIB_F = cekPOST("cidKIB_F");

$cbxDlmRibu = cekPOST("cbxDlmRibu");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
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

//LIST KIB_F
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE ";
//$KondisiTotal = $Kondisi;
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

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_f where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

// copy untuk kode lokasi
//$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,2,2) . "." .$fmSUBUNIT;
$KODELOKASI = $fmKEPEMILIKAN.".".$Main->Provinsi[0].".00.".$fmSKPD . "." .$fmUNIT . "."  .$fmSUBUNIT;
// copy untuk kode lokasi sampai disini


// $Qry = mysql_query("select * from view_kib_f where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg ");
// $jmlDataKIB_F = mysql_num_rows($Qry);
$jmlDataKIB_F = 0;
$Qry = mysql_query("select * from view_kib_f2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg  $LimitHalKIB_F");

$no=$Main->PagePerHal * (($HalKIB_F*1) - 1);
$cb=0;
$jmlTampilKIB_F = 0;
$JmlTotalHargaListKIB_F = 0;
$ListBarangKIB_F = "";

PrintSKPD();
PrintTTD();

$ListBarangKIB_F =list_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,'JAWA BARAT',$KODELOKASI).
list_tableheader('');
/*
if ($jmlDataKIB_F<1) {
$ListBarangKIB_F .= list_table();	
}
*/
$maxrow=20;
$bufftmp=$ListBarangKIB_F;



while ($isi = mysql_fetch_array($Qry))
{
/*
	$kota='';
	if (!($isi['alamat_b'] == '' || $isi['alamat_b'] =='00' )){
		$sidBi = 'select nm_wilayah from ref_wilayah where a="'.$isi['alamat_a'].'" and b="'.$isi['alamat_b'].'"'; $cek .= '<br> qrkota='.$sidBi;
		$kota = '<br>'.table_get_value( $sidBi, 'nm_wilayah');
	}
	$Kec = table_get_value('select alamat_kec from kib_f where concat(a1,a,b,c,d,e,f,g,h,i,j,noreg) = "'.
			$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'].'"','alamat_kec');
	$Kel = table_get_value('select alamat_kel from kib_f where concat(a1,a,b,c,d,e,f,g,h,i,j,noreg) = "'.
			$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'].'"','alamat_kel');
	if($Kec != ''){ $Kec='<br>Kec. '.$Kec;}
	if($Kel != ''){ $Kel='<br>Kel. '.$Kel;}
*/
	$kota = $isi['alamat_kota'] == "" ? "":"<br>".$isi['alamat_kota'];
	$Kec = $isi['alamat_kec'] == "" ? "":"<br>Kec ".$isi['alamat_kec'];
	$Kel = $isi['alamat_kel'] == "" ? "":"<br>Kel ".$isi['alamat_kel'];
		
	$jmlTampilKIB_F++;
	$JmlTotalHargaListKIB_F += $isi['jml_harga'];
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	
	$ISI15 = ifempty($isi['ket'],'-');
	$ISI15 .= tampilNmSubUnit2($isi);		
	
	$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 0, ',', '.') : number_format($isi['jml_harga'], 0, ',', '.');

	$tampilHarga = $isi['jml_harga'];


	$bufftmp .= list_table($no,$isi['id_barang'],$isi['noreg'],$isi['nm_barang'],
	ifempty($Main->Bangunan[$isi['bangunan']-1][1],'-'),
	ifempty($Main->Tingkat[$isi['konstruksi_tingkat']-1][1],'-'),
	ifempty($Main->Beton[$isi['konstruksi_beton']-1][1],'-'),
	$isi['luas'],ifempty($isi['alamat'],'-').$Kel.$Kec.$kota,
	ifemptyTgl(TglInd($isi['dokumen_tgl']),'-'),ifempty($isi['dokumen_no'],'-'),
	ifemptyTgl(TglInd($isi['tmt']),'-'),
	ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-'),
	ifempty($isi['kode_tanah'],'-'),ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-'),$tampilHarga,
	$ISI15);
		
	
	$cb++;
	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}

$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_F/1000), 0, ',', '.'):number_format(($JmlTotalHargaListKIB_F), 0, ',', '.');
$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 0, ',', '.'): number_format(($jmlTotalHarga), 0, ',', '.');

$tampilHarga = number_format(($JmlTotalHargaListKIB_F), 2, '.', '');
$bufftmp  .= list_tablefooter($tampilTotal,'');
$bufftmp .= list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);

echo $bufftmp;
ob_flush();
flush(); 

?>
