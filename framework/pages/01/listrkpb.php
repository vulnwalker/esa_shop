<?php
$HalRKPB = cekPOST("HalRKPB",1);
$LimitHalRKPB = " limit ".(($HalRKPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidRKPB = cekPOST("cidRKPB");

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
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");
$fmBARANGCARIRKPB = cekPOST("fmBARANGCARIRKPB");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//LIST RKPB
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIRKPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIRKPB%' ";
}
/*
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and rkpb.thn_perolehan = '$fmTahunPerolehan' ";
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

$jmlTotalHargaAll = mysql_query("select sum(jml_biaya) as total  from rkpb where $KondisiTotal ");

if($jmlTotalHargaAll = mysql_fetch_array($jmlTotalHargaAll))
{
	$jmlTotalHargaAll = $jmlTotalHargaAll[0];
}
else
{$jmlTotalHargaAll=0;}

$jmlTotalBiayaPemeliharaanAll = mysql_query("select sum(biaya_pemeliharaan) as total  from view_bi_pemeliharaan where $KondisiTotal ");

if($jmlTotalBiayaPemeliharaanAll = mysql_fetch_array($jmlTotalBiayaPemeliharaanAll))
{
	$jmlTotalBiayaPemeliharaanAll = $jmlTotalBiayaPemeliharaanAll[0];
}
else
{$jmlTotalBiayaPemeliharaanAll=0;}
// copy untuk kondisi jumlah total sampai sini

$Qry = mysql_query("select rkpb.*,ref_barang.nm_barang from rkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");

$jmlDataRKPB = mysql_num_rows($Qry);

$Qry = mysql_query("select rkpb.*,ref_barang.nm_barang from rkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalRKPB");

$no=$Main->PagePerHal * (($HalRKPB*1) - 1);
$cb=0;
$jmlTampilRKPB = 0;
$jmlTotalHargaDisplay = 0;
$jmlTotalBiayaPemeliharaan = 0;
$ListBarangRKPB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilRKPB++;
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
	$ListBarangRKPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}</td>
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

$ListBarangRKPB .= "
		<tr class='row0'>
			<td colspan=7 class=\"GarisDaftar\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."</td>
			<!-- <td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalBiayaPemeliharaan, 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\" colspan=3>&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=7 >Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaAll, 2, ',', '.')."</td>
			<!-- <td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalBiayaPemeliharaanAll, 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\" colspan=3>&nbsp;</td>
		</tr>
		";
//$ListBarangRKPB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListRKPB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST RKPB


$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
	<table class=\"adminheading\">
		<tr>
			<th height=\"47\" class=\"user\">Daftar Rencana Kebutuhan Pemeliharaan Barang </th>
		</tr>
	</table>
	
	<table width=\"100%\">
		<tr>
			<td width=\"60%\" valign=\"top\">
			".WilSKPD()."
			</td>
		</tr>
	</table>
<BR>
	<table width=\"100%\" height=\"100%\">
		<tr valign=\"top\">
			<td class=\"contentheading\">
			<DIV ALIGN=CENTER>DAFTAR RENCANA KEBUTUHAN PEMELIHARAAN BARANG</DIV>
			</td>
		</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
		<tr valign=\"top\">   
			<td width=10% >Nama Barang</td>
			<td width=1% >:</td>
			<td>
			<input type=text name='fmBARANGCARIRKPB' value='$fmBARANGCARIRKPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">&nbsp;&nbsp;&nbsp;".TahunPerolehan()."
			</td>
		</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
		<TR class='title'>
			<TH class=\"th01\">No</TD>
			<TH class=\"th01\" style='width:70'>Kode Barang</TH>
			<TH class=\"th01\" style='width:60'>Nomor<br>Register</TH>
			<TH class=\"th01\" style='width:200'>Nama Barang</TH>
			<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" style='width:40'>Jumlah</TH>
			<TH class=\"th01\" style='width:80'>Harga<br> Satuan<br>(Rp)</TH>
			<TH class=\"th01\" style='width:80'>Jumlah <br>Harga<br>(Rp)</TH>
			<!-- <TH class=\"th01\" style='width:80'>Total Biaya<br> Pemeliharaan<br>(Rp)</TH> -->
			<TH class=\"th01\" style='width:60'>Kode Rekening</TH>
			<TH class=\"th01\" style='width:100'>Uraian Pemeliharaan</TH>
			<TH class=\"th01\" style='width:100'>Keterangan</TH>
		</TR>
			$ListBarangRKPB
		<tr>
			<td colspan=15 align=center>
				".Halaman($jmlDataRKPB,$Main->PagePerHal,"HalRKPB")."
			</td>
		</tr>
	</table>
<BR>
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rkpb_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rkpb_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			</tr></table>
		</td></tr>
	</table>


</form>

";



?>