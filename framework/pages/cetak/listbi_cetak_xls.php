<?php


	

set_time_limit(0);

// error_reporting(0);
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="Buku Inventaris Barang.xls"');
ob_flush();
flush();

include ('listbi_xls.php');
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

$HalDefault = cekPOST("HalDefault",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHal = !empty($ctk)?"":$LimitHal;

$cbxDlmRibu = cekPOST("cbxDlmRibu");
$fmTglBuku = cekPOST('fmTglBuku');


$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");


$Info = "";

//LIST KIB_A
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiE1 = $fmSEKSI == "00" || $fmSEKSI == "000" ? "":" and e1='$fmSEKSI' ";

$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'  $KondisiC $KondisiD $KondisiE $KondisiE1";
$KODELOKASI = $fmKEPEMILIKAN.".".$Main->Provinsi[0].".00.".$fmSKPD . "." .$fmUNIT . "."  .$fmSUBUNIT.".".$fmSEKSI;




if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$Kondisi .= empty($fmTglBuku)? "": " and tgl_buku ='$fmTglBuku' ";//echo $Kondisi;
$Kondisi .=  ' and status_barang <> 3 ';


$KondisiTotal = $Kondisi;
$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from buku_induk where $KondisiTotal ");
$sqry = "select * from view_buku_induk where $Kondisi order by $Urutkan a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg";

/*
$Qry = mysql_query($sqry);
$jmlData = mysql_num_rows($Qry);
*/
$jmlData=0;

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

PrintSKPD();
PrintTTD();
$ListData =list_header($SKPD,$UNIT,$SUBUNIT,$SEKSI,$WILAYAH,'JAWA BARAT',$KODELOKASI).
list_tableheader('');
/*
if ($jmlData<1) {
$ListData .= list_table();	
}
*/
$no=0;
$cb=0;

$maxrow=100;
$bufftmp=$ListData;




$sqry="select c,d,e,e1 from buku_induk where ".$Kondisi.
" group by c,d,e,e1 ".
" order by c,d,e,e1 ";
$QrySKPD = mysql_query($sqry);
while ($isiSKPD = mysql_fetch_array($QrySKPD))
{
$hitung=0;
$kodeD="00";
$kodeC=$isiSKPD['c'];
if (!empty($isiSKPD['d'])) {
	$kodeD=$isiSKPD['d'];
	if (!empty($isiSKPD['e'])) {
	$kodeE=$isiSKPD['e'];
		if (!empty($isiSKPD['e1'])) {
		$kodeE1=$isiSKPD['e1'];
		$hitung=1;
		}
	}
	
} else {
$hitung=0;	
$kodeE1="00";
}
$KondisiC = " and c='$kodeC' ";
$KondisiD = " and d='$kodeD' ";
$KondisiE = " and e='$kodeE' ";
$KondisiE1 = " and e1='$kodeE1' ";

$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ";

if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$Kondisi .= empty($fmTglBuku)? "": " and tgl_buku ='$fmTglBuku' ";//echo $Kondisi;
$Kondisi .=  ' and status_barang <> 3 ';


$QryKIB_A = mysql_query("select * from view_kib_a where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_A = mysql_num_rows($QryKIB_A);
$QryKIB_A = mysql_query("select * from view_kib_a where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");



$QryKIB_B = mysql_query("select * from view_kib_b where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_B = mysql_num_rows($QryKIB_B);
$QryKIB_B = mysql_query("select * from view_kib_b where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");


$QryKIB_C = mysql_query("select * from view_kib_c where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_C = mysql_num_rows($QryKIB_C);
$QryKIB_C = mysql_query("select * from view_kib_c where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");



$QryKIB_D = mysql_query("select * from view_kib_d where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_D = mysql_num_rows($QryKIB_D);
$QryKIB_D = mysql_query("select * from view_kib_d where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");


$QryKIB_E = mysql_query("select * from view_kib_e where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_E = mysql_num_rows($QryKIB_E);
$QryKIB_E = mysql_query("select * from view_kib_e where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");


$QryKIB_F = mysql_query("select * from view_kib_f where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ");
$jmlDataKIB_F = mysql_num_rows($QryKIB_F);
$QryKIB_F = mysql_query("select * from view_kib_f where $Kondisi order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg  ");



if (($jmlDataKIB_A > 0) and ($hitung==1)){
while ($isi = mysql_fetch_array($QryKIB_A))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	$ISI5='';	// Merk Tipe
	$ISI6=$isi['sertifikat_no'];	// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI7='';	// Bahan
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	if ((!empty($isi['luas'])) and ($isi['luas']!=0)){
	$ISI10=number_format($isi['luas'], 0, ',', '.').' m2';	// ukuran / konstruksi / (p,sp,d)
	} else {$ISI10='';}
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	

	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	


	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
		
}
}

if (($jmlDataKIB_B > 0) and ($hitung==1)){
while ($isi = mysql_fetch_array($QryKIB_B))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	$ISI5=$isi['merk'];	// Merk Tipe
	$ISI6=!empty($isi['no_pabrik'])? $isi['no_pabrik']:'';
	$ISI6 .=!empty($isi['no_rangka'])? ' / '.$isi['no_rangka']:'';
	$ISI6 .=!empty($isi['no_mesin'])? ' / '.$isi['no_mesin']:'';
				// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI7=$isi['bahan'];	// Bahan
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	$ISI10=$isi['ukuran'];	// ukuran / konstruksi / (p,sp,d)
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	
	

	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}
}

if (($jmlDataKIB_C > 0) and ($hitung==1)) {
while ($isi = mysql_fetch_array($QryKIB_C))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	$ISI5='';	// Merk Tipe
	$ISI6=$isi['dokumen_no'];	// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI7='';	// Bahan
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	
	if ((!empty($isi['luas'])) and ($isi['luas']!=0)){
	$ISI10=number_format($isi['luas'], 0, ',', '.').' m2';	// ukuran / konstruksi / (p,sp,d)
	} else {$ISI10='';}
	$ISI10 .= !empty($isi['konstruksi_tingkat'])? ' / '.$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]:'';
	$ISI10 .= !empty($isi['konstruksi_beton'])? ' / '.$Main->Beton [$isi['konstruksi_beton']-1][1]:'';
	$ISI10 .= !empty($isi['kondisi'])? ' / '.$Main->Bangunan[$isi['kondisi']-1][1]:'';
	
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi_bi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	
	
	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}
}

if (($jmlDataKIB_D > 0) and ($hitung==1)){
while ($isi = mysql_fetch_array($QryKIB_D))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	$ISI5='';	// Merk Tipe
	$ISI6=$isi['dokumen_no'];	// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI7='';	// Bahan
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	if ((!empty($isi['luas'])) and ($isi['luas']!=0)){
	$ISI10=number_format($isi['luas'], 0, ',', '.').' m2';	// ukuran / konstruksi / (p,sp,d)
	} else {$ISI10='';}
	$ISI10 .=!empty($isi['kontruksi'])? ' / '.$isi['kontruksi']:'';	
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi_bi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	
	

	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

	
	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}
}

if (($jmlDataKIB_E > 0) and ($hitung==1)){
while ($isi = mysql_fetch_array($QryKIB_E))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	if (!empty($isi['buku_judul'])){
	$ISI5=$isi['buku_judul'];	// Merk Tipe
	$ISI7='Kertas';	// Bahan
	$ISI10='';	// ukuran / konstruksi / (p,sp,d)	
	} elseif (!empty($isi['seni_asal_daerah'])) {
	$ISI5=$isi['seni_asal_daerah'];	// Merk Tipe
	$ISI7=$isi['seni_bahan'];	// Bahan
	$ISI10='';	// ukuran / konstruksi / (p,sp,d)	
	} elseif (!empty($isi['hewan_jenis'])) {
	$ISI5=$isi['hewan_jenis'];	// Merk Tipe
	$ISI7='';	// Bahan
	$ISI10=$isi['hewan_ukuran'];	// ukuran / konstruksi / (p,sp,d)	
	} else {
	$ISI5='';	// Merk Tipe
	$ISI7='';	// Bahan
	$ISI10='';	// ukuran / konstruksi / (p,sp,d)	
		
	}
	$ISI6='';	// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	

	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

	
	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}
}

if (($jmlDataKIB_F > 0) and ($hitung==1)){
while ($isi = mysql_fetch_array($QryKIB_F))
{
	$no++;
	$ISI1=$no;	// no
	$ISI2=$isi['id_barang'];	// kode barang
	$ISI3=$isi['noreg'];	// No reg
	$ISI4=$isi['nm_barang'];	// Nama Barang
	$ISI5='';	// Merk Tipe
	$ISI6=$isi['dokumen_no'];	// No setifikat / pabrik / rangka /mesin / dokumen
	$ISI7='';	// Bahan
	$ISI8=$Main->AsalUsul[$isi['asal_usul']-1][1];	// Asal usul / perolehan
	$ISI9=$isi['thn_perolehan'];	// Tahun perolehan
	if ((!empty($isi['luas'])) and ($isi['luas']!=0)){
	$ISI10=number_format($isi['luas'], 0, ',', '.').' m2';	// ukuran / konstruksi / (p,sp,d)
	} else {$ISI10='';}
	$ISI10 .= !empty($isi['konstruksi_tingkat'])? ' / '.$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]:'';
	$ISI10 .= !empty($isi['konstruksi_beton'])? ' / '.$Main->Beton [$isi['konstruksi_beton']-1][1]:'';
	$ISI10 .= !empty($isi['bangunan'])? ' / '.$Main->Bangunan[$isi['bangunan']-1][1]:'';
	
	$ISI11=$isi['satuan'];	// satuan
	$ISI12=$Main->KondisiBarang[$isi['kondisi']-1][1];	// keadaan barang (B/KB/RB)
	$ISI13=$isi['jml_barang'];	// jml barang
	$ISI14=$isi['jml_harga'];	//	harga barang
	$ISI15=$isi['ket'];	// Keterangan
	$ISI15 .= ' / '.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit2($isi);
	$cb++;	
	
	

	$bufftmp .= list_table($ISI1,$ISI2,$ISI3,$ISI4,$ISI5,$ISI6,$ISI7,$ISI8,$ISI9,
	$ISI10,$ISI11,$ISI12,$ISI13,$ISI14,$ISI15);
	$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

	
	if (($cb % $maxrow ==1) & ($cb != 1) ){
	echo $bufftmp;
	ob_flush();
    flush(); 
	$bufftmp ="";	
	}
}
}
}


$bufftmp .= list_tablefooter($jmlTotalHarga,'').list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);
$bufftmp = str_replace(array("\0", "\r", "\n", "\t","\x0B"), '', $bufftmp);	

echo $bufftmp;
ob_end_flush();

?>