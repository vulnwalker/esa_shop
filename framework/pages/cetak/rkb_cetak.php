<?php
$HalDefault = cekPOST("HalDefault",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHal = !empty($ctk)?"":$LimitHal;
/*
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/

$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$Cari = cekPOST("Cari");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//variable pencarian
$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");
//variable pencarian ending

$Info = "";

$Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
$Ops = "";
while($isi=mysql_fetch_array($Qry))
{
	$sel = $fmWIL == $isi['b'] ? "selected":"";
	$Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
}

//ListData
/*
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$Cari = cekPOST("Cari");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
*/
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";


$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";

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

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from rkb where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$jmlTotalHargaDisplay = 0;

$ListData = "";
$cb=0;
$Qry = mysql_query("select * from view_rkb where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlData = mysql_num_rows($Qry);

$Qry = mysql_query("select * from view_rkb where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

$no=$Main->PagePerHal * (($HalDefault*1) - 1);
while($isi=mysql_fetch_array($Qry))
{
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_harga'];
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListData .= "
		<tr>
			<td class=\"GarisCetak\" align=\"center\">$no.</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
            <td class=\"GarisCetak\">{$isi['merk_barang']}</td>
            <td class=\"GarisCetak\" align=\"right\">{$isi['jml_barang']}&nbsp;{$isi['satuan']}</td>
            <td class=\"GarisCetak\" align=\"right\">".number_format($isi['harga'], 2, ',', '.')."</td>
            <td class=\"GarisCetak\" align=\"right\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
            <td class=\"GarisCetak\" align=\"center\">{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}
			</td>
           	<td class=\"GarisCetak\">{$isi['ket']}</td>
        </tr>
	";
	$cb++;
}
$ListData .= "
		<!-- <tr class='row0'>
			<td colspan=5 class=\"GarisCetak\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisCetak\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=2  class=\"GarisCetak\">&nbsp;</td>
		</tr> -->
		<tr class='row0'>
			<td colspan=5 class=\"GarisCetak\">Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisCetak\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
			<td colspan=2 class=\"GarisCetak\">&nbsp;</td>
		</tr>
		";

// aray combo pencarian barang 
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

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
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>RENCANA KEBUTUHAN BARANG MILIK DAERAH</td>
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
	
<br>

	<table class=\"cetak\">
		<thead>
		<tr>
			<th class=\"th01\" style='width:30'>No.</th>
			<th class=\"th01\" style='width:225'>Nama Barang</th>
			<th class=\"th01\" style='width:225'>Merk / Type / Ukuran</th>
			<th class=\"th01\" style='width:90'>Jumlah<br> Barang</th>
			<th class=\"th01\" style='width:90'>Harga <br>Satuan<br> (Rp)</th>
			<th class=\"th01\" style='width:90'>Jumlah<br> Biaya<br> (Rp)</th>
			<th class=\"th01\" style='width:90'>Kode <br>Rekening</th>
			<th class=\"th01\">Keterangan</th>
		</tr>
		</thead>
		$ListData
	</table>
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		
</body>

";
?>