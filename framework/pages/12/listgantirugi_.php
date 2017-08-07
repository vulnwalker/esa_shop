<?php
$HalTGR = cekPOST("HalTGR",1);
$LimitHalTGR = " limit ".(($HalTGR*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

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
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALTUNTUTANGANTIRUGI = cekPOST("fmTANGGALTUNTUTANGANTIRUGI");
$fmKEPADAALAMAT = cekPOST("fmKEPADAALAMAT");
$fmKEPADANAMA = cekPOST("fmKEPADANAMA");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");
$fmBARANGCARITGR = cekPOST("fmBARANGCARITGR");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST TGR
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARITGR))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARITGR%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and gantirugi.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select gantirugi.*,ref_barang.nm_barang from gantirugi inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataTGR = mysql_num_rows($Qry);
$Qry = mysql_query("select gantirugi.*,ref_barang.nm_barang from gantirugi inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalTGR");

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
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbTGR$cb\" name=\"cidTGR[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_gantirugi'])."</td>
			<td class=\"GarisDaftar\">{$isi['kepada_nama']}</td>
			<td class=\"GarisDaftar\">{$isi['kepada_alamat']}</td>
			<td class=\"GarisDaftar\">{$isi['uraian']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
//$ListBarangTGR .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListTGR, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST TGR

$Main->Isi = "

<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">

<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"user\">Daftar Tuntutan Ganti Rugi Barang Milik Daerah</th>
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
	<DIV ALIGN=CENTER>DAFTAR TUNTUTAN GANTI RUGI BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARITGR' value='$fmBARANGCARITGR'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" style='width:20' rowspan=2>No</TD>
		<!-- <TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataTGR,'cbTGR','toggle2');\" /></TD> -->
		<TH class=\"th01\" style='width:70' rowspan=2>Kode Barang</TH>
		<TH class=\"th01\" style='width:50' rowspan=2>Nomor<br>Register</TH>
		<TH class=\"th01\" style='width:180' rowspan=2>Nama Barang</TH>
		<TH class=\"th01\" style='width:60' rowspan=2>Tahun<br>Perolehan</TH>
		<TH class=\"th01\" style='width:70' rowspan=2>Tanggal<br>Tuntutan Ganti Rugi</TH>
		<TH class=\"th02\" colspan=2>K e p a d a</TH>
		<TH class=\"th01\" style='width:160' rowspan=2>Uraian</TH>
		<TH class=\"th01\" style='width:160' rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\" style='width:80'>Nama</TH>
		<TH class=\"th01\" style='width:100'>Alamat</TH>
	</TR>
	$ListBarangTGR
	<tr>
	<td colspan=16 align=center>
	".Halaman($jmlDataTGR,$Main->PagePerHal,"HalTGR")."
	</td>
	</tr>
	</table>

<br>
<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=gantirugi_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=gantirugi_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

			</tr></table>
		</td></tr>
	</table>
";
?>