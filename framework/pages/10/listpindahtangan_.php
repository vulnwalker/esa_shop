<?php
$HalPTNG = cekPOST("HalPTNG",1);
$LimitHalPTNG = " limit ".(($HalPTNG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

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
$fmBARANGCARIPTNG = cekPOST("fmBARANGCARIPTNG");

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

$Qry = mysql_query("select pemindahtanganan.*,ref_barang.nm_barang from pemindahtanganan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataPTNG = mysql_num_rows($Qry);
$Qry = mysql_query("select pemindahtanganan.*,ref_barang.nm_barang from pemindahtanganan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalPTNG");

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
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbPTNG$cb\" name=\"cidPTNG[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_pemindahtanganan'])."</td>
			<td class=\"GarisDaftar\">".$Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1]."</td>
			<td class=\"GarisDaftar\">{$isi['kepada_nama']}</td>
			<td class=\"GarisDaftar\">{$isi['kepada_alamat']}</td>
			<td class=\"GarisDaftar\">{$isi['uraian']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
//$ListBarangPTNG .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListPTNG, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST PTNG

$Main->Isi = "
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Pemindahtanganan Barang Milik Daerah</th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
	".WilSKPD1()."
<br>
<A Name=\"ISIAN\"></A>
<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PEMINDAHTANGANAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIPTNG' value='$fmBARANGCARIPTNG'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2>No</TD>
		<!-- <TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataPTNG,'cbPTNG','toggle2');\" /></TD> -->
		<TH class=\"th01\" rowspan=2>Kode Barang</TH>
		<TH class=\"th01\" rowspan=2>Nomor<br>Register</TH>
		<TH class=\"th01\" rowspan=2>Nama Barang</TH>
		<TH class=\"th01\" rowspan=2>Tahun<br>Perolehan</TH>
		<TH class=\"th01\" rowspan=2>Tanggal<br>Pemindah tanganan</TH>
		<TH class=\"th01\" rowspan=2>Bentuk<br>Pemindah tanganan</TH>
		<TH class=\"th02\" colspan=2>K e p a d a</TH>
		<TH class=\"th01\" rowspan=2>Uraian</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\">Nama</TH>
		<TH class=\"th01\">Alamat</TH>	
	</TR>

	$ListBarangPTNG
	<tr>
	<td colspan=16 align=center>
	".Halaman($jmlDataPTNG,$Main->PagePerHal,"HalPTNG")."
	</td>
	</tr>
	</table>
<br>
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pindahtangan_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pindahtangan_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

			</tr></table>
		</td></tr>
	</table>

";
?>