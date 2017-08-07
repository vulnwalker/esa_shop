<?php
$HalPTNG = cekPOST("HalPTNG",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalPTNG = " limit ".(($HalPTNG	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPTNG = !empty($ctk)?"":$LimitHalPTNG;
/*
$HalPTNG = cekPOST("HalPTNG",1);
$LimitHalPTNG = " limit ".(($HalPTNG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidPTNG = cekPOST("cidPTNG");

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
$fmTANGGALPEMINDAHTANGANAN = cekPOST("fmTANGGALPEMINDAHTANGANAN");
$fmBENTUKPEMINDAHTANGANAN = cekPOST("fmBENTUKPEMINDAHTANGANAN");
$fmKEPADAALAMAT = cekPOST("fmKEPADAALAMAT");
$fmKEPADANAMA = cekPOST("fmKEPADANAMA");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST PTNG
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIPTNG))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIPTNG%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and pemindahtanganan.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select pemindahtanganan.*,ref_barang.nm_barang from pemindahtanganan inner join ref_barang on concat(pemindahtanganan.f,pemindahtanganan.g,pemindahtanganan.h,pemindahtanganan.i,pemindahtanganan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataPTNG = mysql_num_rows($Qry);
$Qry = mysql_query("select pemindahtanganan.*,ref_barang.nm_barang from pemindahtanganan inner join ref_barang on concat(pemindahtanganan.f,pemindahtanganan.g,pemindahtanganan.h,pemindahtanganan.i,pemindahtanganan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalPTNG");

$no=$Main->PagePerHal * (($HalPTNG*1) - 1);
$cb=0;
$jmlTampilPTNG = 0;

$ListBarangPTNG = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilPTNG++;
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangPTNG .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_pemindahtanganan'])."</td>
			<td class=\"GarisCetak\">".$Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1]."</td>
			<td class=\"GarisCetak\">{$isi['kepada_nama']}</td>
			<td class=\"GarisCetak\">{$isi['kepada_alamat']}</td>
			<td class=\"GarisCetak\" style='width:180'>{$isi['uraian']}</td>
			<td class=\"GarisCetak\" style='width:120'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
//$ListBarangPTNG .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListPTNG, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST PTNG

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PEMINDAHTANGANAN BARANG MILIK DAERAH</td>
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
			<TH class=\"th01\" rowspan=2 style='width:30'>No</TD>
			<TH class=\"th01\" rowspan=2 style='width:75'>Kode<br> Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:50'>Nomor<br>Register</TH>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tanggal<br>Pemindah<br> tanganan</TH>
			<TH class=\"th01\" rowspan=2 style='width:100'>Bentuk<br>Pemindah<br> tanganan</TH>
			<TH class=\"th02\" colspan=2>K e p a d a</TH>
			<TH class=\"th01\" rowspan=2 style='width:180'>Uraian</TH>
			<TH class=\"th01\" rowspan=2 style='width:120'>Keterangan</TH>
		</TR>
		<TR>
			<TH class=\"th01\">Nama</TH>
			<TH class=\"th01\">Alamat</TH>	
		</TR>
		</thead>
		$ListBarangPTNG
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";
?>