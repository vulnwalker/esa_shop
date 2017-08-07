<?php
set_time_limit(0);
$Main->PagePerHal = 100;
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");

$cbxDlmRibu = cekPOST("cbxDlmRibu");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
$fmWIL = cekPOST("fmWIL"); 
$fmSKPD = cekPOST("fmSKPD"); $cek .= 'skpd ='.$fmSKPD.'<br>';
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmBIDANG = cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");

$xls = $_REQUEST['xls'];

if($xls==1){
	
	$cp1=1; $cp2=2; $cp3=1; $cp4=2; $cp5 =1;
	$jmlkolom = 7;
	$fname = 'rekapbi.xls';
	header("Content-type: application/msexcel");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");
}


$Info = "";

//$KondisiC = $fmSKPD == "00" ? "":" and bi.c='$fmSKPD' ";
$KondisiD = $fmUNIT == "00" ? "":" and bi.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and bi.e='$fmSUBUNIT' ";
$KondisiE1 = $fmSEKSI == "000" ? "":" and bi.e1='$fmSEKSI' ";
//$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}' and bi.b='$fmWIL' and bi.c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}'  and bi.c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 ";
if($fmSKPD == "00"){
	$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}' $KondisiD $KondisiE $KondisiE1";
}
$Kondisi .=  ' and status_barang <> 3 ';

	

$jmlTotalHargaDisplay = 0;
$ListData = "";

/*
$no=0;
$cb=0;

$QryRefBarang = mysql_query("select ref.f,ref.g,ref.nm_barang from ref_barang as ref where h='00' order by ref.f,ref.g");
$jmlData = mysql_num_rows($QryRefBarang);
$totalBrg = 0;
$TotalHarga = 0;
$no=$Main->PagePerHal * (($HalDefault*1) - 1);
while($isi=mysql_fetch_array($QryRefBarang))
{
	$Kondisi1 = "concat(bi.f,bi.g)= '{$isi['f']}{$isi['g']}'";
	$QryBarang = mysql_query("select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi where $Kondisi1 $Kondisi group by bi.f,bi.g order by bi.f,bi.g");
	$isi1 = mysql_fetch_array($QryBarang);
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$kdBidang = $isi['g'] == "00"?"":$isi['g'];
	$nmBarang = $isi['g'] == "00"?"<b>{$isi['nm_barang']}</b>":"&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
	$QryBarangAtas = mysql_fetch_array(mysql_query("select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi where bi.f='{$isi['f']}' $Kondisi group by f order by f"));
	$jmlBarangAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:$isi1['jml_barang'];
	$jmlBarangAtas = empty($jmlBarangAtas) ? "0" : "".$jmlBarangAtas."";
	$jmlBarangAtas = $isi['g'] == "00" ? "<b>".$jmlBarangAtas."" : "".$jmlBarangAtas."";
	//$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']/1000),0,',', '.')."":"".number_format(($isi1['jml_harga']/1000),0,',', '.')."";
	
	if ( !empty($cbxDlmRibu) ){			
		$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']/1000),0,',', '.')."": "".number_format(($isi1['jml_harga']/1000),0,',', '.')."";
	}else{			
		$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']),0,',', '.')."": "".number_format(($isi1['jml_harga']),0,',', '.')."";			
	}
	
	$totalBrg += $isi1['jml_barang'];
	$TotalHarga +=  $isi1['jml_harga'];
	$ListData .= "
		<tr>
			<!--<td class=\"GarisCetak\" style='border: 1px solid blue;' align=center width=\"50\" height='48'>$no.</td>-->
			<td class=\"GarisCetak\" align=center width=\"50\" >$no.</td>
			<td class=\"GarisCetak\" align=center width=\"50\">{$isi['f']}</td>
			<td class=\"GarisCetak\" align=center width=\"50\">$kdBidang</td>
			<td class=\"GarisCetak\">$nmBarang</div></td>
			<td class=\"GarisCetak\" align=right width=\"120\">$jmlBarangAtas</td>
			<td class=\"GarisCetak\" align=right width=\"120\">$jmlHargaAtas</td>
			<td class=\"GarisCetak\" width=\"220\">&nbsp;</td>
        </tr>
	";
	$cb++;
}

$tampilTotBrg = number_format($totalBrg, 0, ',', '.');
$tampilTotHarga = !empty($cbxDlmRibu) ? number_format(($TotalHarga/1000), 0, ',', '.') : number_format(($TotalHarga), 0, ',', '.'); 
$ListData .= "
		<tr class='row0'>
			<td colspan=4 class=\"GarisCetak\">TOTAL</td>
			<td align=right class=\"GarisCetak\"><b>".$tampilTotBrg."</td>
			<td align=right class=\"GarisCetak\"><b>".$tampilTotHarga."</td>
			<td class=\"GarisCetak\">&nbsp;</td>
		</tr>
		";
*/

// aray combo pencarian barang 
/*$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);
*/
$fmTahun = cekPOST('fmTahun',date('Y'));
$tglAwal = $fmTahun.'-1-1';
$tglAkhir = $fmTahun.'-12-31';
list($ListData, $jmlData) = 
	Mutasi_RekapByBrg_GetList2($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu), TRUE, 3, $fmSEKSI, 7 ,$xls
			);

$cek = '';

$tampilHeaderHarga =  !empty($cbxDlmRibu) ? "Jumlah Harga <br>dalam Ribuan <br>(Rp)" : " Jumlah Harga <br>(Rp) ";


$headerHTML = $xls==1?
"<head>
	<title>$Main->Judul</title>	
	<style>
	table.rangkacetak {
	background-color: #FFFFFF;
	margin: 0cm;
	padding: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	font-family : Arial,  sans-serif;
}
table.cetak {
	background-color: #FFFFFF;
	font-family : Arial,  sans-serif;
	margin: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	color: #000000;
	font-size : 9pt;
}
table.cetak th.th01 {

	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak th.th02 {

	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
/*table.cetak tr.row0 {
	background-color: #DBDBDB;
	text-align: left;
}
table.cetak tr.row1 {
	background-color: #FFF;
	text-align: left;
}*/
table.cetak input {
	font-family: Arial Narrow;
	font-size: 9pt;
}
/* untuk repeat header */
thead { 
	display: table-header-group; 
}
/* untuk repeat footer */
tfoot { 
	display: table-footer-group; 
}
.judulcetak {
	width: 30cm;
	font-size: 16px;

	font-weight: bold;
}
.subjudulcetak {
	font-size: 12px;

	font-weight: bold;
}
.GCTK {

	background-color: white;
	vertical-align: middle;
}
.nfmt1 {
	mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';
	
}
.nfmt2 {
	mso-number-format:'0\.00_';
	
}
.nfmt3 {
	mso-number-format:'0000';
	
}
.nfmt4 {
	mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';
}	
table{
	mso-displayed-decimal-separator:'\.';
	mso-displayed-thousand-separator:'\,';
}

   br {mso-data-placement:same-cell;}
	
			
			.nfmt5 {mso-number-format:'\@';}			
			</style>	
</head>"
:
"<head>
	<title >$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
</head>";

$rangkacetak1 = $xls? '': 	
	"<table class=\"rangkacetak\" style='width:30cm'  ><tr><td valign=\"top\">";
$rangkacetak2 = $xls? '':
	"</td></tr></table>";

//$Main->Isi = 
echo $cek."
<html>
$headerHTML
<body>
$rangkacetak1


	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\" ALIGN='center'>
			<!--<DIV ALIGN=CENTER>-->
				REKAPITULASI BUKU INVENTARIS BARANG <BR>
				TAHUN ANGGARAN $fmTahun
			<!--</DIV>-->
			</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".PrintSKPD()."</td>
		</tr>
	</table>

<table border=\"1\" class=\"cetak\">
	<tr>
	<thead>
		<th class=\"th01\"  width=\"50\">Nomor</th>
		<th class=\"th01\"  width=\"50\">Golongan</th>
		<th class=\"th01\"   width=\"50\">Kode<br>Bidang<br>Barang</th>
		<th class=\"th01\" >Nama Bidang Barang</th>
		<th class=\"th01\" width=\"120\">Jumlah Barang</th>
		<th class=\"th01\" width=\"120\">$tampilHeaderHarga</th>
		<th class=\"th01\" width=\"220\">Keterangan</th>
	</tr>
	</thead>
		$ListData
</table>
<br>	
".PrintTTD($width, $xls==1, $cp1, $cp2, $cp3, $cp4, $cp5)."
$rangkacetak2
		

</body>
</html>
";



?>