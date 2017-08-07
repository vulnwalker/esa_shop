<?php
$HalBYY = cekPOST("HalBYY",1);
$LimitHalBYY = " limit ".(($HalBYY*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBYY = cekPOST("cidBYY");

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
$fmTANGGALPEMBIAYAAN = cekPOST("fmTANGGALPEMBIAYAAN");
$fmBIAYABARANG = cekPOST("fmBIAYABARANG");
$fmBUKTIBIAYA = cekPOST("fmBUKTIBIAYA");
$fmIDREKENING = cekPOST("fmIDREKENING");
//echo $fmIDREKENING;
$fmKET = cekPOST("fmKET");
$fmBARANGCARIBYY = cekPOST("fmBARANGCARIBYY");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");


//LIST BYY
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIBYY))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIBYY%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and pembiayaan.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataBYY = mysql_num_rows($Qry);
$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBYY");

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
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbBYY$cb\" name=\"cidBYY[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_pembiayaan'])."</td>
			<td class=\"GarisDaftar\" align=right>".number_format(($isi['biaya_barang']/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">{$isi['bukti_pembiayaan']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
	$ListBarangBYY .= "

		<tr class='row0'>
			<td colspan=6 class=\"GarisDaftar\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=3  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format(($jmlTotalHarga/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" colspan=3>&nbsp;</td>
		</tr>
		";
//$ListBarangBYY .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListBYY, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST BYY



$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Pembiayaan Barang Milik Daerah</th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
	".WilSKPD1()."
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PEMBIAYAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIBYY' value='$fmBARANGCARIBYY'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" style='width:20'>No</TD>
		<!-- <TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataBYY,'cbBYY','toggle2');\" /></TD> -->
		<TH class=\"th01\" style='width:80'>Kode Barang</TH>
		<TH class=\"th01\" style='width:60'>Nomor<br>Register</TH>
		<TH class=\"th01\" style='width:180'>Nama Barang</TH>
		<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
		<TH class=\"th01\" style='width:70'>Tanggal<br>Pembiayaan</TH>
		<TH class=\"th01\" style='width:80'>Biaya<br>Barang (Ribuan)</TH>
		<TH class=\"th01\" style='width:100'>Tanda Bukti<br>Pembiayaan</TH>
		<TH class=\"th01\" style='width:70'>Kode Rekening</TH>
		<TH class=\"th01\">Keterangan</TH>
	</TR>
	$ListBarangBYY
	<tr>
	<td colspan=16 align=center>
	".Halaman($jmlDataBYY,$Main->PagePerHal,"HalBYY")."
	</td>
	</tr>
	</table>

<br>
<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pembiayaan_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pembiayaan_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

			</tr></table>
		</td></tr>
	</table>
";
?>