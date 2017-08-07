<?php
$HalBYY = cekPOST("HalBYY",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalBYY = " limit ".(($HalBYY	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalBYY = !empty($ctk)?"":$LimitHalBYY;
/*
$HalBYY = cekPOST("HalBYY",1);
$LimitHalBYY = " limit ".(($HalBYY*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBYY = cekPOST("cidBYY");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPEMBIAYAAN = cekPOST("fmTANGGALPEMBIAYAAN");
$fmBIAYABARANG = cekPOST("fmBIAYABARANG");
$fmBUKTIBIAYA = cekPOST("fmBUKTIBIAYA");
$fmIDREKENING = cekPOST("fmIDREKENING");
//echo $fmIDREKENING;
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");


//LIST BYY
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIBYY))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIBYY%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and pembiayaan.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang on concat(pembiayaan.f,pembiayaan.g,pembiayaan.h,pembiayaan.i,pembiayaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataBYY = mysql_num_rows($Qry);
$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang on concat(pembiayaan.f,pembiayaan.g,pembiayaan.h,pembiayaan.i,pembiayaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBYY");

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

$jmlTotalHarga = mysql_query("select sum(biaya_barang) as total  from pembiayaan where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$no=$Main->PagePerHal * (($HalBYY*1) - 1);
$cb=0;
$jmlTampilBYY = 0;
$jmlTotalHargaDisplay = 0;

$ListBarangBYY = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilBYY++;
	$no++;
	$jmlTotalHargaDisplay += $isi['biaya_barang'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangBYY .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_pembiayaan'])."</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['biaya_barang']/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" style='width:180'>{$isi['bukti_pembiayaan']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisCetak\" style='width:180'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
	$ListBarangBYY .= "

		<!-- <tr class='row0'>
			<td colspan=6 class=\"GarisCetak\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=3  class=\"GarisCetak\">&nbsp;</td>
		</tr> -->
		<tr class='row0'>
			<td class=\"GarisCetak\" colspan=6>Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisCetak\" align=right><b>".number_format(($jmlTotalHarga/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" colspan=3>&nbsp;</td>
		</tr>
		";
//$ListBarangBYY .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListBYY, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST BYY



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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PEMBIAYAAN BARANG MILIK DAERAH</td>
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
			<TH class=\"th01\" style='width:25'>No</TD>
			<TH class=\"th01\" style='width:80'>Kode Barang</TH>
			<TH class=\"th01\" style='width:60'>Nomor<br>Register</TH>
			<TH class=\"th01\">Nama Barang</TH>
			<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" style='width:70'>Tanggal<br>Pembiayaan</TH>
			<TH class=\"th01\" style='width:80'>Biaya<br>Barang<BR> (Ribuan)</TH>
			<TH class=\"th01\" style='width:180'>Tanda Bukti<br>Pembiayaan</TH>
			<TH class=\"th01\" style='width:80'>Kode <BR>Rekening</TH>
			<TH class=\"th01\" style='width:180'>Keterangan</TH>
		</TR>
		</thead>
		$ListBarangBYY
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>
";
?>