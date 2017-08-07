<?php
$HalDPSB = cekPOST("HalDPSB",1);
$LimitHalDPSB = " limit ".(($HalDPSB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidDPSB = cekPOST("cidDPSB");

$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$Cari = cekPOST("Cari");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

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
$fmBARANGCARIDPSB=cekPOST("fmBARANGCARIDPSB");

$fmNOSKGUBERNUR=cekPOST("fmNOSKGUBERNUR");
$fmTANGGALSKGUBERNUR=cekPOST("fmTANGGALSKGUBERNUR");
$fmKONDISIBAIK=cekPOST("fmKONDISIBAIK");
$fmKONDISIKURANGBAIK=cekPOST("fmKONDISIKURANGBAIK");
$fmTANGGALBELI=cekPOST("fmTANGGALBELI");
$fmSKGUBNO=cekPOST("fmSKGUBNO","");
$fmSKGUBTGL=cekPOST("fmSKGUBTGL");

//fungsi group by no sk gubernur
function NoPenetapanGub()
{
	global $HTTP_POST_VARS,$HTTP_GET_VARS,$fmSKGUBNO;
	$str = "";
	$Qry = mysql_query("select skgub_no from penetapan group by skgub_no order by skgub_no desc");
	$ops = "<option value=''>Semua No SK</option>";
	while ($isi = mysql_fetch_array($Qry))
	{
		$sel = $fmSKGUBNO == $isi['skgub_no'] ? "selected":""; 
		$ops .= "<option $sel value='{$isi['skgub_no']}'>{$isi['skgub_no']}</option>\n";
	}
	$str = "<select onChange=\"adminForm.target='_self';adminForm.action='?Pg=04&SPg=02';adminForm.submit()\" name='fmSKGUBNO'>$ops</select>";
	return $str;
}

// group by tgl dan sk gubernur

$KondisiD = $fmUNIT == "00" ? "":" and penetapan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and penetapan.e='$fmSUBUNIT' ";
$Kondisi = "penetapan.a='{$Main->Provinsi[0]}' and penetapan.b='$fmWIL' and penetapan.c='$fmSKPD' $KondisiD $KondisiE and penetapan.tahun='$fmTAHUNANGGARAN'";

if(!empty($fmBARANGCARIDPSB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPSB%' ";
}
if(!empty($fmSKGUBNO))
{
	$Kondisi .= " and penetapan.skgub_no = '$fmSKGUBNO'";
}


//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from penetapan where $Kondisi");
$jmlTotalHarga = mysql_query("select sum(penetapan.jml_harga) as total from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi ");


if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";

$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataDPSB = mysql_num_rows($Qry);
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPSB");
//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";

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

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from penetapan where $KondisiTotal ");
$fmSKGUBTGL = mysql_fetch_array(mysql_query("select skgub_tgl from penetapan where $Kondisi "));


if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$JmlTotalHargaListDPSB = 0;
$no=$Main->PagePerHal * (($HalDPSB*1) - 1);
$cb=0;
$jmlTampilDPSB = 0;

$ListBarangDPSB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDPSB++;
	$JmlTotalHargaListDPSB += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPSB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbDPSB$cb\" name=\"cidDPSB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=left>{$isi['skgub_no']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['skgub_tgl'])."</td>
			<!--<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_beli'])."</td>-->
			<td class=\"GarisDaftar\" align=center>{$isi['jml_baik']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['jml_kbaik']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPSB .= "
	<tr class='row0'>
		<td class=\"GarisDaftar\" colspan=5>Total Harga (Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($JmlTotalHargaListDPSB, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=5 align=right>&nbsp;</td>
	</tr>
	<tr class='row0'>
		<td class=\"GarisDaftar\" colspan=5 >Total Harga Seluruhnya (Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=5 >&nbsp;</td>
	</tr>
";
//ENDLIST DPSB

$Main->Isi = "


<A Name=\"ISIAN\"></A>
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">

<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"user\">Daftar Penetapan Status Penggunaan Barang</th>
	</tr>
</table>

<table width=\"100%\">
	<tr>
		<td width=\"60%\" valign=\"top\">
		".WilSKPD()."
<br>
<table width=\"100%\">
	<tr>
		<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR PENETAPAN STATUS PENGGUNAAN BARANG MILIK DAERAH</td>
	</tr>
	<tr>
		<td class=\"contentheading\"><DIV ALIGN=CENTER>TAHUN ANGGARAN $fmTAHUNANGGARAN</td>
	</tr>
</table>

<table width=\"100%\" height=\"50\">
	<tr>
		<td style='font-size:10pt;font-weight:bold;'>Tampilkan Berdasarkan Surat Keputusan Gubernur:</td>
	</tr>
	<tr>
		<td style='font-size:9pt;'>Nomor : <B>".NoPenetapanGub()."&nbsp;&nbsp;&nbsp;</B> Tanggal : <B>".JuyTgl1(date($fmSKGUBTGL[0]))."</B></td>
	</tr>
	<!-- <tr>
		<td style='font-size:9pt;'>Nomor : <B>".NoPenetapanGub()."&nbsp;&nbsp;&nbsp;</B> Tanggal : <B>".TglInd($fmSKGUBTGL[0])."</B></td>
	</tr> -->
</table>

<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2 align=center>No</TD>
		<!-- <TH class=\"th01\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPSB,'cbDPSB','toggle2');\" /></TD> -->
		<TH class=\"th01\" rowspan=2>Nama Barang</TH>
		<TH class=\"th01\" rowspan=2 style='width:120'>Merk/Type/ Ukuran/ Spesifikasi</TH>
		<TH class=\"th01\" rowspan=2>Jumlah</TH>
		<TH class=\"th01\" rowspan=2 style='width:90'>Harga Satuan<br> (Rp)</TH>
		<TH class=\"th01\" rowspan=2 style='width:90'>Jumlah Harga<br> (Rp)</TH>
		<TH class=\"th02\" rowspan=1 colspan=2>SK Gubernur</TH>
		<!--<TH class=\"th01\" rowspan=2 style='width:55'>Tgl Pembelian</TH>-->
		<TH class=\"th02\" colspan=2>J u m l a h</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\" style='width:90' align=center>No.</TH>
		<TH class=\"th01\" style='width:90' align=center>Tanggal</TH>
		<TH class=\"th01\" style='width:35' align=center>Baik</TH>		
		<TH class=\"th01\" style='width:35' align=center>Kurang Baik</TH>		
	</TR>
	$ListBarangDPSB
	<tr>
		<td colspan=12 align=center>
		".Halaman($jmlDataDPSB,$Main->PagePerHal,"HalDPSB")."
		</td>
	</tr>
</table>

<br>
<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=penetapan_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=penetapan_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			</tr></table>
		</td></tr>
	</table>
</form>
";
?>