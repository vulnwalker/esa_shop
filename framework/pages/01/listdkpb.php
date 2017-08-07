<?php
$HalDKPB = cekPOST("HalDKPB",1);
$LimitHalDKPB = " limit ".(($HalDKPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

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
$fmBARANGCARIDKPB = cekPOST("fmBARANGCARIDKPB");


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


$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataDKPB = mysql_num_rows($Qry);
$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKPB");
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
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['jml_barang']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_biaya'], 2, ',', '.')."</td>
			<!-- <td class=\"GarisDaftar\" align=right>".number_format($TotalBiayaPemeliharaan[0], 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['uraian']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKPB .= "
		<tr class='row0'>
			<td colspan=7 class=\"GarisDaftar\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."</td>
			<!-- <td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalBiayaPemeliharaan, 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\" colspan=4>&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=7 >Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
			<!-- <td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalBiayaPemeliharaanAll, 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\" colspan=4>&nbsp;</td>
		</tr>
		";
//$ListBarangDKPB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListDKPB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST DKPB

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
	<table class=\"adminheading\">
		<tr>
			<th height=\"47\" class=\"user\">Daftar Kebutuhan Pemeliharaan Barang </th>
		</tr>
	</table>
	
	<table width=\"100%\">
		<tr>
			<td width=\"60%\" valign=\"top\">
			".WilSKPD()."
		</tr>
	</table>

<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR KEBUTUHAN PEMELIHARAAN BARANG</DIV>
	</td>
	</tr>
	</table>
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIDKPB' value='$fmBARANGCARIDKPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">&nbsp;&nbsp;&nbsp;".TahunPerolehan()."
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
		<TR>
			<TH class=\"th01\">No</TD>
			<TH class=\"th01\" style='width:70'>Kode Barang</TH>
			<TH class=\"th01\" style='width:60'>Nomor<br>Register</TH>
			<TH class=\"th01\" style='width:200'>Nama Barang</TH>
			<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" style='width:40'>Jumlah</TH>
			<TH class=\"th01\" style='width:85'>Harga Satuan<br>(Rp)</TH>
			<TH class=\"th01\" style='width:85'>Jumlah Harga<br>(Rp)</TH>
			<!-- <TH class=\"th01\" style='width:85'>Total Biaya <br>Pemeliharaan<br>(Rp)</TH> -->
			<TH class=\"th01\" style='width:60'>Kode Rekening</TH>
			<TH class=\"th01\" style='width:100'>Uraian Pemeliharaan</TH>
			<TH class=\"th01\" style='width:100'>Keterangan</TH>
		</TR>
		$ListBarangDKPB
		<tr>
			<td colspan=11 align=center>
			".Halaman($jmlDataDKPB,$Main->PagePerHal,"HalDKPB")."
			</td>
		</tr>
	</table>
<br>
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dkpb_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dkpb_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			</tr></table>
		</td></tr>
	</table>

</form>


";



?>