<?php
$HalKeluar = cekPOST("HalKeluar",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHal = " limit ".(($HalKeluar	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHal = !empty($ctk)?"":$LimitHal;
/*
$HalKeluar = cekPOST("HalKeluar",1);
$LimitHal = " limit ".(($HalKeluar	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$KondisiD = $fmUNIT == "00" ? "":" and pengeluaran.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengeluaran.e='$fmSUBUNIT' ";
$Kondisi = "pengeluaran.a='{$Main->Provinsi[0]}' and pengeluaran.b='$fmWIL' and pengeluaran.c='$fmSKPD' $KondisiD $KondisiE and pengeluaran.tahun='$fmTAHUNANGGARAN'";

$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang on concat(pengeluaran.f,pengeluaran.g,pengeluaran.h,pengeluaran.i,pengeluaran.j) = concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlData = mysql_num_rows($Qry);

$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang on concat(pengeluaran.f,pengeluaran.g,pengeluaran.h,pengeluaran.i,pengeluaran.j) = concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

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

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengeluaran where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

//$jmlTotalHarga = mysql_query("select sum(pengeluaran.jml_harga) as total from pengeluaran");

$jmlTotalHargaDisplay = 0;
$ListKeluarBarang = "";

$no=$Main->PagePerHal * (($HalKeluar*1) - 1);
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$kdGudang = $isi['c'].$isi['d'].$isi['e'].$isi['id_gudang'];
	$nmGudang = mysql_fetch_array(mysql_query("select * from ref_gudang where concat(c,d,e,id_gudang)='$kdGudang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListKeluarBarang .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['sk_tgl'])."</td>
			<td class=\"GarisCetak\">{$isi['sk_no']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\">{$isi['untuk']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_penyerahan'])."</td>			
			<td class=\"GarisCetak\">{$isi['ket']}</td>
		</tr>
		";
}

$ListKeluarBarang .= "
		<!-- <tr class='row0'>
			<td class=\"GarisCetak\" colspan=6>Total Harga (Ribuan)</td>
			<td class=\"GarisCetak\" align=right>
				<b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."
			</td>
			<td class=\"GarisCetak\" colspan=3 align=right>&nbsp;</td>
		</tr> -->
		<tr class='row0'>
			<td class=\"GarisCetak\" colspan=6 >Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisCetak\" align=right>
				<b>".number_format($jmlTotalHarga, 2, ',', '.')."
			</td>
			<td class=\"GarisCetak\" colspan=3 >&nbsp;</td>
		</tr>
		";

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PENGELUARAN BARANG MILIK DAERAH</td>
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

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\" align=right>GUDANG : {$nmGudang['nm_gudang']}</td>
		</tr>
	</table>
	
<br>

	<table class=\"cetak\">
		<thead>
		<tr>
			<th class=\"th01\" rowspan=\"2\" style='width:20'>No.</th>
			<th class=\"th02\" colspan=\"2\">Surat <br>Permohonan</th>
			<th class=\"th01\" rowspan=\"2\">Nama Barang</th>
			<th class=\"th01\" rowspan=\"2\" style='width:50'>Banyak<br> nya</th>
			<th class=\"th01\" rowspan=\"2\" style='width:90'>Harga<br> Satuan <br>(Rp)</th>
			<th class=\"th01\" rowspan=\"2\" style='width:90'>Jumlah<br> Harga<br> (Rp)</th>
			<th class=\"th01\" rowspan=\"2\" style='width:200'>Untuk</th>
			<th class=\"th01\" rowspan=\"2\" style='width:75'>Tanggal Penyerahan</th>
			<th class=\"th01\" rowspan=\"2\" style='width:200'>Ket.</th>
		</tr>
		<tr>
			<th class=\"th01\" style='width:60'>Tanggal</th>
			<th class=\"th01\">Nomor</th>
		</tr>
		</thead>
			$ListKeluarBarang
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>
";
?>