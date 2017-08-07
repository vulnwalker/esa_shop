<?php
$HalDKPB = cekPOST("HalDKPB",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalDKPB = " limit ".(($HalDKPB	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDKPB = !empty($ctk)?"":$LimitHalDKPB;
/*
$HalDKPB = cekPOST("HalDKPB",1);
$LimitHalDKPB = " limit ".(($HalDKPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidDKPB = cekPOST("cidDKPB");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");


$fmJUMLAH = cekPOST("fmJUMLAH");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmSATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST DKPB
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIDKPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDKPB%' ";
}
/*
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and dkpb.thn_perolehan = '$fmTahunPerolehan' ";
}
*/

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

$jmlTotalHarga = mysql_query("select sum(jml_biaya) as total  from dkpb where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

$jmlTotalBiayaPemeliharaanAll = mysql_query("select sum(biaya_pemeliharaan) as total  from view_bi_pemeliharaan where $KondisiTotal ");

if($jmlTotalBiayaPemeliharaanAll = mysql_fetch_array($jmlTotalBiayaPemeliharaanAll))
{
	$jmlTotalBiayaPemeliharaanAll = $jmlTotalBiayaPemeliharaanAll[0];
}
else
{$jmlTotalBiayaPemeliharaanAll=0;}
// copy untuk kondisi jumlah total sampai sini


$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang on concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataDKPB = mysql_num_rows($Qry);
$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang on concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalDKPB");
$no=$Main->PagePerHal * (($HalDKPB*1) - 1);
$cb=0;
$jmlTampilDKPB = 0;
$jmlTotalHargaDisplay = 0;
$jmlTotalBiayaPemeliharaan = 0;
$ListBarangDKPB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDKPB++;
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_biaya'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdBarang1 = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$TotalBiayaPemeliharaan = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as biaya from pemeliharaan where concat(f,g,h,i,j,noreg)='$kdBarang1' "));
	$jmlTotalBiayaPemeliharaan += $TotalBiayaPemeliharaan[0];	
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDKPB .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['jml_barang']}</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['harga']/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['jml_biaya']/1000), 0, ',', '.')."</td>
			<!-- <td class=\"GarisCetak\" align=right>".number_format(($TotalBiayaPemeliharaan[0]/1000), 0, ',', '.')."</td> -->
			<td class=\"GarisCetak\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisCetak\">{$isi['uraian']}</td>
			<td class=\"GarisCetak\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKPB .= "
		<!-- <tr class='row0'>
			<td colspan=7 class=\"GarisCetak\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalBiayaPemeliharaan/1000), 2, ',', '.')."</td>
			<td class=\"GarisCetak\" colspan=3>&nbsp;</td> -->
		</tr>
		<tr class='row0'>
			<td class=\"GarisCetak\" colspan=7 >Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisCetak\" align=right><b>".number_format(($jmlTotalHarga/1000), 0, ',', '.')."</td>
			<!-- <td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalBiayaPemeliharaanAll/1000), 0, ',', '.')."</td> -->
			<td class=\"GarisCetak\" colspan=3>&nbsp;</td>
		</tr>
		";
//$ListBarangDKPB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListDKPB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST DKPB

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR KEBUTUHAN PEMELIHARAAN BARANG MILIK DAERAH</td>
		</tr>
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>TAHUN ANGGARAN $fmTAHUNANGGARAN</td>
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
			<TH class=\"th01\" style='width:75'>Kode<br> Barang</TH>
			<TH class=\"th01\" style='width:50'>Nomor<br>Register</TH>
			<TH class=\"th01\" style='width:220'>Nama Barang</TH>
			<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" style='width:40'>Jumlah</TH>
			<TH class=\"th01\" style='width:75'>Harga <br>Satuan<br>(Ribuan)</TH>
			<TH class=\"th01\" style='width:75'>Jumlah<br> Harga<br>(Ribuan)</TH>
			<!-- <TH class=\"th01\" style='width:75'>Total Biaya<br> Pemeliharaan<br>(Ribuan)</TH> -->
			<TH class=\"th01\" style='width:60'>Kode<br> Rekening</TH>
			<TH class=\"th01\">Uraian <br>Pemeliharaan</TH>
			<TH class=\"th01\">Keterangan</TH>
		</TR>
		</thead>
		$ListBarangDKPB
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
</body>

";
?>