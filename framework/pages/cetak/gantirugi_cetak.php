<?php
$HalTGR = cekPOST("HalTGR",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalTGR = " limit ".(($HalTGR	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalTGR = !empty($ctk)?"":$LimitHalTGR;
/*
$HalTGR = cekPOST("HalTGR",1);
$LimitHalTGR = " limit ".(($HalTGR*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidTGR = cekPOST("cidTGR");

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
$fmTANGGALTUNTUTANGANTIRUGI = cekPOST("fmTANGGALTUNTUTANGANTIRUGI");
$fmKEPADAALAMAT = cekPOST("fmKEPADAALAMAT");
$fmKEPADANAMA = cekPOST("fmKEPADANAMA");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST TGR
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARITGR))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARITGR%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and gantirugi.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select gantirugi.*,ref_barang.nm_barang from gantirugi inner join ref_barang on concat(gantirugi.f,gantirugi.g,gantirugi.h,gantirugi.i,gantirugi.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataTGR = mysql_num_rows($Qry);
$Qry = mysql_query("select gantirugi.*,ref_barang.nm_barang from gantirugi inner join ref_barang on concat(gantirugi.f,gantirugi.g,gantirugi.h,gantirugi.i,gantirugi.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalTGR");

$no=$Main->PagePerHal * (($HalTGR*1) - 1);
$cb=0;
$jmlTampilTGR = 0;

$ListBarangTGR = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilTGR++;
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangTGR .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_gantirugi'])."</td>
			<td class=\"GarisCetak\">{$isi['kepada_nama']}</td>
			<td class=\"GarisCetak\">{$isi['kepada_alamat']}</td>
			<td class=\"GarisCetak\" style='width:200'>{$isi['uraian']}</td>
			<td class=\"GarisCetak\" style='width:140'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
//$ListBarangTGR .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListTGR, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST TGR

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR TUNTUTAN GANTI RUGI BARANG MILIK DAERAH</td>
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
			<TH class=\"th01\" style='width:25' rowspan=2>No</TD>
			<TH class=\"th01\" style='width:80' rowspan=2>Kode Barang</TH>
			<TH class=\"th01\" style='width:50' rowspan=2>Nomor<br>Register</TH>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" style='width:60' rowspan=2>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" style='width:70' rowspan=2>Tanggal<br>Tuntutan Ganti Rugi</TH>
			<TH class=\"th02\" colspan=2>K e p a d a</TH>
			<TH class=\"th01\" style='width:200' rowspan=2>Uraian</TH>
			<TH class=\"th01\" style='width:140' rowspan=2>Keterangan</TH>
		</TR>
		<TR>
			<TH class=\"th01\">Nama</TH>
			<TH class=\"th01\">Alamat</TH>
		</TR>
		</thead>
		$ListBarangTGR
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";
?>