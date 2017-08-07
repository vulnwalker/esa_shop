<?php
$HalDPSB = cekPOST("HalDPSB",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalDPSB = " limit ".(($HalDPSB	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDPSB = !empty($ctk)?"":$LimitHalDPSB;
/*
$HalDPSB = cekPOST("HalDPSB",1);
$LimitHalDPSB = " limit ".(($HalDPSB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
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
$fmSKGUBNO1=cekPOST("fmSKGUBNO","");
$fmSKGUBTGL1=cekPOST("fmSKGUBTGL");

//fungsi group by no sk gubernur
function NoPenetapanGub1()
{
	global $HTTP_POST_VARS,$HTTP_GET_VARS,$fmSKGUBNO1;
	$str = "";
	$Qry = mysql_query("select skgub_no from penetapan group by skgub_no order by skgub_no desc");
	$ops = "<option value=''>Semua No SK</option>";
	while ($isi = mysql_fetch_array($Qry))
	{
		$sel = $fmSKGUBNO1 == $isi['skgub_no'] ? "selected":""; 
		$ops .= "<option $sel value='{$isi['skgub_no']}'>{$isi['skgub_no']}</option>\n";
	}
	//$str = "<select onChange='adminForm.submit()' name='fmSKGUBNO1'>$ops</select>";
	$str = $fmSKGUBNO1;
	return $str;
}


$KondisiD = $fmUNIT == "00" ? "":" and penetapan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and penetapan.e='$fmSUBUNIT' ";
$Kondisi = "penetapan.a='{$Main->Provinsi[0]}' and penetapan.b='$fmWIL' and penetapan.c='$fmSKPD' $KondisiD $KondisiE and penetapan.tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARIDPSB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPSB%' ";
}
if(!empty($fmSKGUBNO1))
{
	$Kondisi .= " and penetapan.skgub_no = '$fmSKGUBNO1'";
}
//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from penetapan where $Kondisi");
$jmlTotalHarga = mysql_query("select sum(penetapan.jml_harga) as total from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ");


if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataDPSB = mysql_num_rows($Qry);
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalDPSB");
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
$fmSKGUBTGL1 = mysql_fetch_array(mysql_query("select skgub_tgl from penetapan where $Kondisi "));

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
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<!-- <td><input type=\"checkbox\" id=\"cbDPSB$cb\" name=\"cidDPSB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td> -->
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\">{$isi['merk_barang']}</td>
			<td class=\"GarisCetak\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['harga']/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" align=right>".number_format(($isi['jml_harga']/1000), 0, ',', '.')."</td>
			<td class=\"GarisCetak\" align=left>{$isi['skgub_no']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['skgub_tgl'])."</td>
			<!--<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_beli'])."</td>-->
			<td class=\"GarisCetak\" align=center>{$isi['jml_baik']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['jml_kbaik']}</td>
			<td class=\"GarisCetak\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPSB .= "
	<!-- <tr class='row0'>
		<td class=\"GarisCetak\" colspan=5>Total Harga (Ribuan)</td>
		<td class=\"GarisCetak\" align=right><b>".number_format(($JmlTotalHargaListDPSB/1000), 2, ',', '.')."</td>
		<td class=\"GarisCetak\" colspan=5 align=right>&nbsp;</td>
	</tr> -->
	<tr class='row0'>
		<td class=\"GarisCetak\" colspan=5 >Total Harga Seluruhnya (Ribuan)</td>
		<td class=\"GarisCetak\" align=right><b>".number_format(($jmlTotalHarga/1000), 0, ',', '.')."</td>
		<td class=\"GarisCetak\" colspan=5 >&nbsp;</td>
	</tr>
";
//ENDLIST DPSB

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PENETAPAN STATUS PENGGUNAAN BARANG MILIK DAERAH</td>
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
	
	<table width=\"400\" border=\"0\">
		<tr>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;' colspan=3>Keputusan Gubernur</td>
		</tr>
		<tr>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;width:145;'>Nomor</td>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;'>:</td>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;'>".NoPenetapanGub1()."</td>
		</tr>
		<tr>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;width:145;'>Tanggal</td>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;'>:</td>
			<td style='font-family:Arial Narrow;font-size:10pt;font-weight:bold;'>".JuyTgl1(date($fmSKGUBTGL1[0]))."</td>
		</tr>
	</table>
<br>

	<table class=\"cetak\">
		<thead>
		<TR>
			<TH class=\"th01\" rowspan=2 align=center>No</TD>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:120'>Merk/Type/ Ukuran/<br> Spesifikasi</TH>
			<TH class=\"th01\" rowspan=2>Jumlah</TH>
			<TH class=\"th01\" rowspan=2 style='width:75'>Harga Satuan<br> (Ribuan)</TH>
			<TH class=\"th01\" rowspan=2 style='width:75'>Jumlah Harga<br> (Ribuan)</TH>
			<TH class=\"th02\" colspan=2>SK Bupati</TH>
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
		</thead>
		$ListBarangDPSB
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";
?>