<?php
$HalPNI = cekPOST("HalPNI",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalPNI = " limit ".(($HalPNI	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPNI = !empty($ctk)?"":$LimitHalPNI;
/*
$HalPNI = cekPOST("HalPNI",1);
$LimitHalPNI = " limit ".(($HalPNI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidPNI = cekPOST("cidPNI");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPENILAIAN = cekPOST("fmTANGGALPENILAIAN");
$fmNILAIBARANG = cekPOST("fmNILAIBARANG");
$fmPENILAIINSTANSI = cekPOST("fmPENILAIINSTANSI");
$fmPENILAIALAMAT = cekPOST("fmPENILAIALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST PNI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIPNI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIPNI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and penilaian.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang on concat(penilaian.f,penilaian.g,penilaian.h,penilaian.i,penilaian.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataPNI = mysql_num_rows($Qry);
$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang on concat(penilaian.f,penilaian.g,penilaian.h,penilaian.i,penilaian.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalPNI");

// copy untuk kondisi jumlah total
$KondisiTotal = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(nilai_barang) as total  from penilaian where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$no=$Main->PagePerHal * (($HalPNI*1) - 1);
$cb=0;
$jmlTampilPNI = 0;
$jmlTotalHargaDisplay = 0;

$ListBarangPNI = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilPNI++;
	$no++;
	$jmlTotalHargaDisplay += $isi['nilai_barang'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangPNI .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_penilaian'])."</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['nilai_barang']/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\">{$isi['penilai_instansi']}</td>
			<td class=\"GarisCetak\">{$isi['penilai_alamat']}</td>
			<td class=\"GarisCetak\">{$isi['surat_no']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['surat_tgl'])."</td>
			<td class=\"GarisCetak\" style='width:150'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}

$ListBarangPNI .= "
		<!-- <tr class='row0'>
			<td colspan=6 class=\"GarisCetak\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=5  class=\"GarisCetak\">&nbsp;</td>
		</tr> -->
		<tr class='row0'>
			<td class=\"GarisCetak\" colspan=6 >Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisCetak\" align=right><b>".number_format(($jmlTotalHarga/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" colspan=5 >&nbsp;</td>
		</tr>
		";
//$ListBarangPNI .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListPNI, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST PNI


$Main->Isi = "

<head>
	<title>$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
</head>

<body>
<table class=\"rangkacetak\">
<tr>
<td valign=\"top\">

	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PENILAIAN BARANG MILIK DAERAH</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".PrintSKPD()."</td>
		</tr>
	</table>
<br>

	<table class=\"cetak\">
		<thead>
		<TR>
			<TH class=\"th01\" rowspan=2 style='width:20'>No</TD>
			<TH class=\"th01\" rowspan=2 style='width:75'>Kode<br> Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:35'>Nomor<br>Reg.</TH>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tanggal<br>Penilaian</TH>
			<TH class=\"th01\" rowspan=2 style='width:70'>Nilai<br>Barang<br>(Ribuan)</TH>
			<TH class=\"th02\" colspan=2>Pihak Penilai</TH>
			<TH class=\"th02\" colspan=2>Surat Perjanjian/<br> Kontrak</TH>
			<TH class=\"th01\" rowspan=2 style='width:150'>Keterangan</TH>
		</TR>
		<TR>
			<TH class=\"th01\">Instansi</TH>
			<TH class=\"th01\">Alamat</TH>
			<TH class=\"th01\">Nomor</TH>
			<TH class=\"th01\" style='width:60'>Tanggal</TH>
		</TR>
		</thead>
		$ListBarangPNI
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>

</body>


";
?>