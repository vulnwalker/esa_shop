<?php
$HalPNI = cekPOST("HalPNI",1);
$LimitHalPNI = " limit ".(($HalPNI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidPNI = cekPOST("cidPNI");

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
$fmTANGGALPENILAIAN = cekPOST("fmTANGGALPENILAIAN");
$fmNILAIBARANG = cekPOST("fmNILAIBARANG");
$fmPENILAIINSTANSI = cekPOST("fmPENILAIINSTANSI");
$fmPENILAIALAMAT = cekPOST("fmPENILAIALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmKET = cekPOST("fmKET");
$fmBARANGCARIPNI = cekPOST("fmBARANGCARIPNI");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST PNI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIPNI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIPNI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and penilaian.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataPNI = mysql_num_rows($Qry);
$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalPNI");

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

$jmlTotalHarga = mysql_query("select sum(nilai_barang) as total  from penilaian where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$no=$Main->PagePerHal * (($HalPNI*1) - 1);
$cb=0;
$jmlTampilPNI = 0;
$jmlTotalHargaDisplay = 0;

$ListBarangPNI = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilPNI++;
	$no++;
	$jmlTotalHargaDisplay += $isi['nilai_barang'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangPNI .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbPNI$cb\" name=\"cidPNI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" style='width:150'>{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center style='width:60'>".TglInd($isi['tgl_penilaian'])."</td>
			<td class=\"GarisDaftar\" align=right style='width:60'>".number_format(($isi['nilai_barang']/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" style='width:80'>{$isi['penilai_instansi']}</td>
			<td class=\"GarisDaftar\" style='width:100'>{$isi['penilai_alamat']}</td>
			<td class=\"GarisDaftar\" style='width:75'>{$isi['surat_no']}</td>
			<td class=\"GarisDaftar\" align=center style='width:60'>".TglInd($isi['surat_tgl'])."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}

$ListBarangPNI .= "
		<tr class='row0'>
			<td colspan=6 class=\"GarisDaftar\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=5  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=6 >Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format(($jmlTotalHarga/1000), 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" colspan=5 >&nbsp;</td>
		</tr>
		";
//$ListBarangPNI .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListPNI, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST PNI


$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">

<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"user\">Daftar Penilaian Barang Milik Daerah</th>
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
		<DIV ALIGN=CENTER>DAFTAR PENILAIAN BARANG MILIK DAERAH</DIV>
		</td>
	</tr>
</table>

<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIPNI' value='$fmBARANGCARIPNI'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
</table>

<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2>No</TD>
		<!-- <TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataPNI,'cbPNI','toggle2');\" /></TD> -->
		<TH class=\"th01\" rowspan=2>Kode Barang</TH>
		<TH class=\"th01\" rowspan=2>No.<br>Register</TH>
		<TH class=\"th01\" rowspan=2>Nama Barang</TH>
		<TH class=\"th01\" rowspan=2>Tahun<br>Perolehan</TH>
		<TH class=\"th01\" rowspan=2>Tanggal<br>Penilaian</TH>
		<TH class=\"th01\" rowspan=2>Nilai<br>Barang<br>(Ribuan)</TH>
		<TH class=\"th02\" colspan=2>Pihak Penilai</TH>
		<TH class=\"th02\" colspan=2>Surat Perjanjian / Kontrak</TH>
		<TH class=\"th01\" rowspan=2>Ket</TH>
	</TR>
	<TR>
		<TH class=\"th01\">Instansi</TH>
		<TH class=\"th01\">Alamat</TH>
		<TH class=\"th01\">Nomor</TH>
		<TH class=\"th01\">Tanggal</TH>
	</TR>
	$ListBarangPNI
	<tr>
		<td colspan=16 align=center>
		".Halaman($jmlDataPNI,$Main->PagePerHal,"HalPNI")."
		</td>
	</tr>
</table>
<br>
<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=penilaian_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=penilaian_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

			</tr></table>
		</td></tr>
	</table>

";
?>