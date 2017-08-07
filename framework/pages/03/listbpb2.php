<?php
$HalKeluar = cekPOST("HalKeluar",1);
$LimitHal = " limit ".(($HalKeluar	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL", $Main->DEF_WILAYAH);
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$KondisiD = $fmUNIT == "00" ? "":" and pengeluaran.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengeluaran.e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and pengeluaran.c='$fmSKPD' ";
$Kondisi = "pengeluaran.a='{$Main->Provinsi[0]}' and pengeluaran.b='$fmWIL' $KondisiC $KondisiD $KondisiE and pengeluaran.tahun='$fmTAHUNANGGARAN'";

$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlData = mysql_num_rows($Qry);

$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

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
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListKeluarBarang .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['sk_tgl'])."</td>
			<td class=\"GarisDaftar\">{$isi['sk_no']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format(($isi['harga']/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format(($isi['jml_harga']/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">{$isi['untuk']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_penyerahan'])."</td>			
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>
		";
}

$ListKeluarBarang .= "
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=6>Total Harga (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right>
				<b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."
			</td>
			<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=6 >Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right>
				<b>".number_format(($jmlTotalHarga/1000), 2, ',', '.')."
			</td>
			<td class=\"GarisDaftar\" colspan=3 >&nbsp;</td>
		</tr>
		";

$Main->Isi = "
<A Name=\"ISIAN\"></A>
<div align=\"center\" class=\"centermain\">
	<div class=\"main\">
		<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
		<table class=\"adminheading\">
			<tr>
				<th height=\"47\" class=\"user\">Daftar Pengeluaran Barang</th>
			</tr>
		</table>
		<table width=\"100%\" class=\"adminheading\">
			<tr>
				<td colspan=4>
			<br>
			".WilSKPD()."
			</td>
			</tr>
		</table>
		<table width=\"100%\">
			<tr>
				<td class=\"contentheading\"><DIV ALIGN=CENTER>BUKU PENGELUARAN BARANG</td>
			</tr>
			<tr>
				<td class=\"contentheading\"><DIV ALIGN=CENTER>TAHUN ANGGARAN $fmTAHUNANGGARAN</td>
			</tr>
		</table>
		<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
			<tr>
				<th class=\"th01\" rowspan=\"2\">No.</th>
				<th class=\"th02\" colspan=\"2\">Surat Permohonan</th>
				<th class=\"th01\" rowspan=\"2\">Nama Barang</th>
				<th class=\"th01\" rowspan=\"2\" style='width:50'>Banyak nya</th>
				<th class=\"th01\" rowspan=\"2\" style='width:65'>Harga Satuan (Ribuan)</th>
				<th class=\"th01\" rowspan=\"2\" style='width:75'>Jumlah Harga (Ribuan)</th>
				<th class=\"th01\" rowspan=\"2\">Untuk</th>
				<th class=\"th01\" rowspan=\"2\" style='width:65'>Tanggal Penyerahan</th>
				<th class=\"th01\" rowspan=\"2\">Ket.</th>
			</tr>
			<tr>
				<th class=\"th01\" style='width:55'>Tanggal</th>
				<th class=\"th01\" style='width:75'>Nomor</th>
			</tr>
			$ListKeluarBarang
			<tr>
				<td colspan=12 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalKeluar")."</td>
			</tr>
		</table>
<br>
		<table width=\"100%\" class=\"menudottedline\">
			<tr><td>
				<table width=\"50\"><tr>
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengeluaran_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengeluaran_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
				</tr></table>
			</td></tr>
		</table>

	</div>
</div>
";
?>