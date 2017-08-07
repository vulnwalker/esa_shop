<?php
$HalDefault = cekPOST("HalDefault",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHal = !empty($ctk)?"":$LimitHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();


$fmTANGGALPERAWAL=cekPOST("fmTANGGALPERAWAL",date("01-01-Y"));
$fmTANGGALPERAKHIR=cekPOST("fmTANGGALPERAKHIR",date("d-m-Y"));

$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmIDBARANGCARI = cekPOST("fmIDBARANGCARI");
$fmNMBARANGCARI = cekPOST("fmNMBARANGCARI");
$fmMEREK = cekPOST("fmMEREK");
$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmNMREKENING = cekPOST("fmNMREKENING");
$fmKET = cekPOST("fmKET");
$fmIDBARANGCARI=cekPOST("fmIDBARANGCARI");
$fmBARANGCARI=cekPOST("fmBARANGCARI");
$fmBARANGCARIDPB=cekPOST("fmBARANGCARIDPB");


$fmUNITGUNAKAN=cekPOST("fmUNITGUNAKAN");
$fmSUBUNITGUNAKAN=cekPOST("fmSUBUNITGUNAKAN");
$fmTANGGALSPK=cekPOST("fmTANGGALSPK");
$fmNOMORSPK=cekPOST("fmNOMORSPK");
$fmPTSPK=cekPOST("fmPTSPK");
$fmTANGGALDPA=cekPOST("fmTANGGALDPA");
$fmNOMORDPA=cekPOST("fmNOMORDPA");


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

$Info = "";

$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmTAHUNANGGARAN,fmTANGGALPERAWAL,fmTANGGALPERAKHIR";

//LIST DPB
$KondisiD = $fmUNIT == "00" ? "":" and pengadaan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengadaan.e='$fmSUBUNIT' ";
$KondisiTahun = " and spk_tgl >= '".TglSQL($fmTANGGALPERAWAL)."' and spk_tgl <= '".TglSQL($fmTANGGALPERAKHIR)."' ";
$Kondisi = "pengadaan.a='{$Main->Provinsi[0]}' and pengadaan.b='$fmWIL' and pengadaan.c='$fmSKPD' $KondisiD $KondisiE and pengadaan.tahun='$fmTAHUNANGGARAN' $KondisiTahun";
if(!empty($fmBARANGCARIDPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPB%' ";
}

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

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from pengadaan where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengadaan where $Kondisi");
$jmlTotalHargaDisplay = mysql_query("select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j) = concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j) inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ");
//echo "select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j) = concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j) inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ";

if($jmlTotalHargaDisplay = mysql_fetch_array($jmlTotalHargaDisplay))
{
	$jmlTotalHargaDisplay = $jmlTotalHargaDisplay[0];
}
else
{$jmlTotalHargaDisplay=0;}

//echo "select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPB = mysql_num_rows($Qry);

$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

$ListBarangDPB = "";
$no=$Main->PagePerHal * (($HalDefault*1) - 1);
$cb=0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$kodeskpd = $isi['c'].$isi['d'].$isi['e'];
	$namaskpd = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where concat(c,d,e)='$kodeskpd'")); 
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPB .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisCetak\" align=left>".$isi['spk_no']."</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['dpa_tgl'])."</td>
			<td class=\"GarisCetak\" align=left>".$isi['dpa_no']."</td>
			<td class=\"GarisCetak\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\">{$namaskpd['nm_skpd']}</td>
			<td class=\"GarisCetak\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPB .= "
	<!-- <tr class='row0'>
		<td class=\"GarisCetak\" colspan=8>Total Harga per Halaman (Rp)</td>
		<td class=\"GarisCetak\" align=right><b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."</td>
		<td class=\"GarisCetak\" colspan=2 align=right>&nbsp;</td>
	</tr> -->
	<tr class='row0'>
		<td class=\"GarisCetak\" colspan=8>Total Harga Seluruhnya (Rp)</td>
		<td class=\"GarisCetak\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
		<td class=\"GarisCetak\" colspan=2>&nbsp;</td>
	</tr>
";
//ENDLIST DPB


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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PENGADAAN BARANG MILIK DAERAH</td>
		</tr>
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DARI TANGGAL $fmTANGGALPERAWAL SAMPAI DENGAN $fmTANGGALPERAKHIR</td>
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
		<tr>
			<th class=\"th01\" rowspan=\"2\" style='width:20'>No.</th>
			<th class=\"th01\" rowspan=\"2\">Barang yang Dibeli</th>
			<th class=\"th02\" colspan=\"2\">SPK/ Perjanjian/<br> Kontrak</th>
			<th class=\"th02\" colspan=\"2\">DPA / SPM /<br> Kwitansi</th>
			<th class=\"th02\" colspan=\"3\">J u m l a h</th>
			<th class=\"th01\" rowspan=\"2\" style='width:180'>Dipergunakan pada Unit</th>
			<th class=\"th01\" rowspan=\"2\" style='width:180'>Keterangan</th>
		</tr>
		<tr>
			<th class=\"th01\" style='width:60'>Tanggal</th>
			<th class=\"th01\">Nomor</th>
			<th class=\"th01\" style='width:60'>Tanggal</th>
			<th class=\"th01\">Nomor</th>
			<th class=\"th01\" style='width:100'>Banyaknya<br> Barang</th>
			<th class=\"th01\" style='width:100'>Harga Satuan<br> (Rp)</th>
			<th class=\"th01\" style='width:100'>Jumlah Harga<br> (Rp)</th>
		</tr>
		</thead>
			$ListBarangDPB
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";
?>