<?php
include ('viewerfn_xls.php');
function list_footer($XXTMPTGL='',$XXJABATAN1='',$XXNAMA1='',$XXNIP1='',
$XXJABATAN2='',$XXNAMA2='',$XXNIP2='')
{
$isix='
<table style="width:30cm" border=0> 
				<tr> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>

				<tr> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td>&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP2.'</B> </td>
				</tr>
				</table>

</body>
</html>
';
return $isix;	
}

$stylexls ='
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
table.cetak tr.row0 {
	background-color: #DBDBDB;
	text-align: left;
}
table.cetak tr.row1 {
	background-color: #FFF;
	text-align: left;
}
table.cetak input {

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
.GCTK1 {
	background-color: white;
	vertical-align: middle;
	border-right: 0;

}
.GCTK2 {
	background-color: white;
	vertical-align: middle;
	border-left: 0;

	
}
.GCTK3 {
	background-color: white;
	vertical-align: middle;
	border-right: 0;
	border-left: 0;
	
}
.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}	
</style>

';

$cbxDlmRibu = '';
$view->title = getTitleKib($SPg);

$all = $_GET['all'];
$jmPerHal = cekPOST("jmPerHal");
$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
$HalDefault = cekPOST("HalDefault",1);
$noawal = $Main->PagePerHal * (($HalDefault*1) - 1)-1;

if ($noawal<=0){
	$noawal=0;
	$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".($Main->PagePerHal-1);
}else{
	$LimitHal = " limit ".((($HalDefault	*1) - 1) * $Main->PagePerHal-1).",".$Main->PagePerHal;
}

$all=1;

if($all==1){
	$LimitHal = '';
}

list($rekap->listtable, $jmlData) =  
	getList_RekapByOPD_xls($SPg, $noawal, $LimitHal, '', TRUE, '');
	
if ($SPg != 10){
$tampilHeaderHarga= 'Total Harga';
$tampilHeaderBarang= 'Total Barang';
	$tabelHeader=" <thead>
<th class='th01' width='30'> No</th>
<th class='th01' width='' colspan=6> Uraian</th>
<th class='th01' width='80'> $tampilHeaderBarang</th>
<th class='th01' width='120'> $tampilHeaderHarga</th>
</thead>";
}else{
	$tampilHeaderHarga= !empty($cbxDlmRibu)? 'BI (Ribuan)': 'BI';
	$tampilHeaderHargaKiba= !empty($cbxDlmRibu)? 'KIB A (Ribuan)': 'KIB A';
	$tampilHeaderHargaKibb= !empty($cbxDlmRibu)? 'KIB B (Ribuan)': 'KIB B';
	$tampilHeaderHargaKibc= !empty($cbxDlmRibu)? 'KIB C (Ribuan)': 'KIB C';
	$tampilHeaderHargaKibd= !empty($cbxDlmRibu)? 'KIB D (Ribuan)': 'KIB D';
	$tampilHeaderHargaKibe= !empty($cbxDlmRibu)? 'KIB E (Ribuan)': 'KIB E';
	$tampilHeaderHargaKibf= !empty($cbxDlmRibu)? 'KIB F (Ribuan)': 'KIB F';
	$tabelHeader = '
		<thead>	
		<th class="th01" width="50" >No.</th>	
		<th class="th01"  colspan=3 >Uraian</th>
		<th class="th01" width="100">'.$tampilHeaderHarga.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKiba.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKibb.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKibc.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKibd.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKibe.'</th>	
		<th class="th01" width="100">'.$tampilHeaderHargaKibf.'</th>			
		</thead>
	';
}

if ($SPg != 10){
	$page_width = '21cm';
}else{
	$page_width = '30cm';
}
PrintTTD( $page_width);

$isi_data_xls ="
<head>
	<title>$Main->Judul</title>".$stylexls."

</head>
<body>
<table class='rangkacetak'> <tr valign=top> <td >

<!--- judul --->
<table border=0 style='width: $page_width;'>
<tr>
<td width='30px'>&nbsp;</td>
<td width='10px'>&nbsp;</td>
<td width='10px'>&nbsp;</td>
<td width='10px'>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td width='100px'>&nbsp;</td>
</tr>
<tr><td colspan=9 class='judulcetak'>
<div align='center'>
	REKAPITULASI BARANG DAERAH BERDASARKAN OPD<br>  
	( $view->title )
</div>
</td></tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
<table class='cetak' border=1 style='width: $page_width;'>
".$tabelHeader."

<tbody>

<!-- list -->
$rekap->listtable
</tbody>
</table>


".list_footer($TITIMANGSA,$JABATANSKPD,$NAMASKPD,$NIPSKPD,$JABATANSKPD1,$NAMASKPD1,$NIPSKPD1)." ";


$sdata = sizeof($isi_data_xls);
    header('Content-length: $sdata');
	header("Content-Type: application/vnd.ms-excel"); 
    header('Content-disposition: attachment; filename="Rekapitulasi Barang Daerah - '.$view->title.'.xls"');
header('Accept-Ranges: bytes');

 /* The three lines below basically make the 
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

echo $isi_data_xls;





?>