<?php
set_time_limit(0);
include('listbi_xls.php');

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

/* asalnya
$fmWIL = cekPOST("fmWIL");//b
$fmSKPD = cekPOST("fmSKPD");//c
$fmUNIT = cekPOST("fmUNIT");//d
$fmSUBUNIT = cekPOST("fmSUBUNIT");//e
$Cari = cekPOST("Cari");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
*/


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



$Info = "";

/* asalnya ini
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";
*/

$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";

// copy untuk kode lokasi
//$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,2,2) . "." .$fmSUBUNIT;
$KODELOKASI = $fmKEPEMILIKAN.".".$Main->Provinsi[0].".00.".$fmSKPD . "." .$fmUNIT . "."  .$fmSUBUNIT;
// copy untuk kode lokasi sampai disini

// copy untuk kondisi jumlah total

if(!empty($fmCariComboIsi) && !empty($fmCariComboField)){
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

//$Kondisi.= " and status_barang=1 ";
$Kondisi .=  ' and status_barang <> 3 ';

$KondisiTotal = $Kondisi;
$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from buku_induk where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$jmlTotalHargaDisplay = 0;
$ListData = "";
$no=0;
$cb=0;

$sqry = "select * from view_buku_induk where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,tahun,noreg";
$cek .= '<br> qrylist = '.$sqry;
$Qry = mysql_query($sqry);
$jmlData = mysql_num_rows($Qry);
//echo "select * from view_buku_induk where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHal";
$Qry = mysql_query("select * from view_buku_induk where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,tahun,noreg $LimitHal");

$no=$Main->PagePerHal * (($HalDefault*1) - 1);

PrintSKPD();
PrintTTD();

$ListData =listbi_header($SKPD,$UNIT,$SUBUNIT,$WILAYAH,'JAWA BARAT',$KODELOKASI).
listbi_tableheader($cbxDlmRibu);

if ($jmlData<1) {
$ListData .= list_table();	
}

$maxrow=1000;
$tmpfile=0;

if ($jmlData>$maxrow) {
$tmpfname = tempnam(sys_get_temp_dir(), 'Tux');	
$handle = fopen($tmpfname, "w+");
fwrite($handle, $ListData);

$tmpfile=1;
$bufftmp="";
$listdata = "";

// echo $temp_file;

}

while($isi=mysql_fetch_array($Qry))
{
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$KonKodeBarang = "
		where 
		f = '{$isi['f']}' and 
		g = '{$isi['g']}' and
		h = '{$isi['h']}' and
		i = '{$isi['i']}' and
		j = '{$isi['j']}'
		";
	$KonKelBarang = "
		where 
		f = '{$isi['f']}' and
		g = '{$isi['g']}' and
		h = '00'
	";
	//$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang $KonKodeBarang "));
	//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang $KonKelBarang "));
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_harga'];
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$AsalUsul = $isi['asal_usul'];
	$ISI5 = "";
	$ISI6 = "";
	$ISI7 = "";
	$ISI10 = "";
	$ISI12 = $Main->KondisiBarang[$isi['kondisi']-1][1];
	$ISI15 = "";
	
	$KondisiKIB = "
		
		where 
		a1= '{$isi['a1']}' and 
		a = '{$isi['a']}' and 
		
		c = '{$isi['c']}' and 
		d = '{$isi['d']}' and 
		e = '{$isi['e']}' and 
		f = '{$isi['f']}' and 
		g = '{$isi['g']}' and 
		h = '{$isi['h']}' and 
		i = '{$isi['i']}' and 
		j = '{$isi['j']}' and 
		noreg = '{$isi['noreg']}' and 
		tahun = '{$isi['tahun']}' 
		
		";
	
	
	if($isi['f']=="01")//KIB A;
	{
		
		$QryKIB_A = mysql_query("select * from kib_a  $KondisiKIB limit 0,1");
		while($isiKIB_A = mysql_fetch_array($QryKIB_A))
		{
			$ISI6 = "{$isiKIB_A['sertifikat_no']}";
			$ISI10 = "{$isiKIB_A['luas']}";
			$ISI15 = "{$isiKIB_A['ket']}";
		}
	}
	if($isi['f']=="02")//KIB B;
	{
		

		$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
		while($isiKIB_B = mysql_fetch_array($QryKIB_B))
		{
			$ISI5 = "{$isiKIB_B['merk']}";
			$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
			$ISI7 = "{$isiKIB_B['bahan']}";
			$ISI10 = "{$isiKIB_B['ukuran']}";
			$ISI15 = "{$isiKIB_B['ket']}";
		}

	}
	
	if($isi['f']=="03"){//KIB C;
		$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket from kib_c  $KondisiKIB limit 0,1");
		while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
			$ISI6 = "{$isiKIB_C['dokumen_no']}";
			$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
			$ISI15 = "{$isiKIB_C['ket']}";
		}
	}
	if($isi['f']=="04"){//KIB D;
		$QryKIB_D = mysql_query("select dokumen_no, ket from kib_d  $KondisiKIB limit 0,1");
		while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
			$ISI6 = "{$isiKIB_D['dokumen_no']}";
			$ISI15 = "{$isiKIB_D['ket']}";
		}
	}
	if($isi['f']=="05"){//KIB E;		
		$QryKIB_E = mysql_query("select seni_bahan, ket from kib_e  $KondisiKIB limit 0,1");
		while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
			$ISI7 = "{$isiKIB_E['seni_bahan']}";
			$ISI15 = "{$isiKIB_E['ket']}";
		}
	}
	if($isi['f']=="06"){//KIB F;		
		$QryKIB_F = mysql_query("select dokumen_no, bangunan, ket from kib_f  $KondisiKIB limit 0,1");
		while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
			$ISI6 = "{$isiKIB_F['dokumen_no']}";
			$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
			$ISI15 = "{$isiKIB_F['ket']}";
		}
	}
	
	
	
		$ISI5 = !empty($ISI5)?$ISI5:"-";
		$ISI6 = !empty($ISI6)?$ISI6:"-";
		$ISI7 = !empty($ISI7)?$ISI7:"-";
		$ISI10 = !empty($ISI10)?$ISI10:"-";
		$ISI12 = !empty($ISI12)?$ISI12:"-";
		$ISI15 = !empty($ISI15)?$ISI15:"-";
	$ISI15 	= $ISI15.' /<br>'.TglInd($isi['tgl_buku']);
	$ISI15 .= tampilNmSubUnit($isi);
		
		
	$tampilHarga = !empty($cbxDlmRibu)? $isi['jml_harga']/1000 : $isi['jml_harga'];
	
	if ($tmpfile==1)
	{

	$bufftmp .= listbi_table($no,
	$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'],$isi['noreg'],
	$nmBarang['nm_barang'],	$ISI5,$ISI6,$ISI7,
	$Main->AsalUsul[$AsalUsul-1][1],$isi['thn_perolehan'],$ISI10,$isi['satuan'],$ISI12,
	$isi['jml_barang'],$tampilHarga,$ISI15);
		
	} else {
		
	
	$ListData .= listbi_table($no,
	$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'],$isi['noreg'],
	$nmBarang['nm_barang'],	$ISI5,$ISI6,$ISI7,
	$Main->AsalUsul[$AsalUsul-1][1],$isi['thn_perolehan'],$ISI10,$isi['satuan'],$ISI12,
	$isi['jml_barang'],$tampilHarga,$ISI15);
	}
	$cb++;
	
	if (($cb % $maxrow ==1) & ($cb != 1) & ($tmpfile==1)){
	fwrite($handle, $bufftmp);
	$bufftmp ="";	
	}
	
}

$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($jmlTotalHargaDisplay/1000), 0, ',', '.'):number_format(($jmlTotalHargaDisplay), 0, ',', '.');

$tampilTotal = !empty($cbxDlmRibu) ? $jmlTotalHarga/1000: $jmlTotalHarga;
if ($tmpfile==1){
$bufftmp .= listbi_tablefooter($tampilTotal,$cbxDlmRibu).listbi_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);
fwrite($handle, $bufftmp);
fclose($handle);
$sdata = intval(sprintf("%u", filesize($tmpfname)));

// $sdata = filesize($tmpfname);
header('Content-length: $sdata');
if ($sdata < 2 * 1024*1024){
header("Content-Type: application/vnd.ms-excel"); 	
}
header('Content-disposition: attachment; filename="Buku Inventaris Barang.xls"');


// If it's a large file, readfile might not be able to do it in one go, so:
$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
if ($sdata > $chunksize) {
  $handle = fopen($tmpfname, 'rb');
  $bufftmp = '';
  while (!feof($handle)) {
    $bufftmp = fread($handle, $chunksize);
    echo $bufftmp;
    ob_flush();
    flush();
  }
  fclose($handle);
} 
		
} else {
$ListData .= listbi_tablefooter($tampilTotal,$cbxDlmRibu);	
$ListData .= listbi_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1);
// aray combo pencarian barang 
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

$cek='';

$Main->Isi = $ListData;


$sdata = sizeof($Main->Isi);
    header('Content-length: $sdata');
	header("Content-Type: application/vnd.ms-excel"); 
    header('Content-disposition: attachment; filename="Buku Inventaris Barang.xls"');

}






?>