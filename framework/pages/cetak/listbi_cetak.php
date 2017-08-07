<?php
//$HTTP_COOKIE_VARS;
set_time_limit(0);
/*
$fmKONDBRG = $_POST ['fmKONDBRG'];
if($fmKONDBRG == '3'){
	$title = 'ASET LAINNYA';	
}else{
	$title = 'BUKU INVENTARIS BARANG';
}*/
//echo "spg=".$_GET['SPg'];

$fmKEPEMILIKAN =  $Main->DEF_KEPEMILIKAN;
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");

setWilSKPD();
$KODELOKASI = $Main->DEF_KEPEMILIKAN.'.'.$Main->DEF_PROPINSI.'.'.$Main->DEF_WILAYAH.'.'.$HTTP_COOKIE_VARS['cofmSKPD'].'.'.$HTTP_COOKIE_VARS['cofmUNIT'].'.'.$HTTP_COOKIE_VARS['cofmSUBUNIT'].'.'.$HTTP_COOKIE_VARS['cofmSEKSI'];
$tipe = $_REQUEST['tipe'];
$title = $Penatausaha->genTitleCetak($_GET['SPg'], $_POST['fmKONDBRG'],$tipe);
$Opsi = $Penatausaha->getDaftarOpsi();
$width = '32cm';

$xls =$_REQUEST['xls'];

//echo $_GET['SPg'];

if($xls==1){
	switch ($_GET['SPg']) {
		case '04': case 'kib_a_cetak' : 
			//echo ' tipe='.$tipe;
			if($tipe =='kertaskerja'){
				$fname = 'kk_kiba.xls'; 
				$jmlkolom = 16;
				$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
				//$nokib = '01'; //echo ' nokib='.$nokib;
			}else{
				$fname = 'kiba.xls'; 
				$jmlkolom = 14;
				$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;	
			}
			
			break;
		case '05': case 'kib_b_cetak' : 
			if($tipe=='kertaskerja'){
				$fname = 'kk_kibb.xls'; 
				$jmlkolom = 16;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;
				//$nokib = '02';
			}else{
				$fname = 'kibb.xls'; 
				$jmlkolom = 16;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;	
			}
			
			break;
		case '06': case 'kib_c_cetak' : 
			if($tipe=='kertaskerja'){
				$fname = 'kk_kibc.xls'; 
				$jmlkolom = 17;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
				//$nokib = '02';
			}else{
				$fname = 'kibc.xls'; 
				$jmlkolom = 17;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
			}
			break;
		case '07': case 'kib_d_cetak' : 
			if($tipe=='kertaskerja'){
				$fname = 'kk_kibd.xls'; 
				$jmlkolom = 18;
				$cp1=3; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
				//$nokib = '03';
			}else{
				$fname = 'kibd.xls'; 
				$jmlkolom = 18;
				$cp1=3; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
				
			}
			break;
		case '08': case 'kib_e_cetak' : 
			if($tipe=='kertaskerja'){
				$fname = 'kk_kibe.xls'; 
				$jmlkolom = 16;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;
				//$nokib = '04';
			}else{
				$fname = 'kibe.xls'; 
				$jmlkolom = 16;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;
			}
			break;
		case '09': case 'kib_f_cetak' : 
			$fname = 'kibf.xls'; 
			$jmlkolom = 18;
			$cp1=3; $cp2=4; $cp3=4; $cp4=4; $cp5=3;
			break;
		case 'kibg': case 'kib_g_cetak' : 
			if($tipe=='kertaskerja'){
				$fname = 'kk_kibg.xls'; 
				$jmlkolom = 12;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;
				//$nokib = '04';
			}else{
				$fname = 'kibg.xls'; 
				$jmlkolom = 12;
				$cp1=2; $cp2=4; $cp3=4; $cp4=4; $cp5=2;
			}
			break;			
		case 'belumsensus' : 
			$tipe = $_REQUEST['tipe'];
			if($tipe=='kertaskerja'){
				$fname = 'kertaskerja.xls'; 
				$jmlkolom = 14;
				$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;	
			}else{
				$fname = 'belumsensus.xls'; 
				$jmlkolom = 14;
				$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;	
			}
			
			break;
		case 'KIR': 
			$fname = 'kir.xls'; 
			$jmlkolom = 14;
			$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;
			break;
		case 'KIP': 
			$fname = 'kip.xls'; 
			$jmlkolom = 14;
			$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;
			break;
		default: 
			$fname = 'bukuinduk.xls'; 
			$jmlkolom = 14;
			$cp1=1; $cp2=4; $cp3=4; $cp4=4; $cp5=1;
			break;
		
	}
	
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
		}



if($tipe =='kertaskerja'){
	switch ($_GET['SPg']) {
		case '04': case 'kib_a_cetak' : $nokib='01'; break;
		case '05': case 'kib_b_cetak' : $nokib='02'; break;
		case '06': case 'kib_c_cetak' : $nokib='03'; break;
		case '07': case 'kib_d_cetak' : $nokib='04'; break;
		case '08': case 'kib_e_cetak' : $nokib='05'; break;
	}
}


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
	<title>$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
</head>";




$rangkacetak1 = $xls? '': 
	"<table class=\"rangkacetak\" style='width:$width'> <tr> <td valign=\"top\">";
$rangkacetak2 = $xls? '':
	"</td></tr></table>";

if($SPg == 'KIR'){
	$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
	$fmPILRUANG = $_REQUEST['fmPILRUANG'];
	if ($fmPILGEDUNG != ''){
		$arr = explode(' ',$fmPILGEDUNG);		
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_ruang where c='{$arr[0]}' and d='{$arr[1]}' and e='{$arr[2]}'  and e1='{$arr[3]}' and p='{$arr[4]}' and q='0000'"
		)) ;
		$gedung = $get['nm_ruang'];
	}
	if ($fmPILRUANG != ''){
		$arr = explode(' ',$fmPILRUANG);
		$id_pegawai='';		
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_ruang where c='{$arr[0]}' and d='{$arr[1]}' and e='{$arr[2]}'  and e1='{$arr[3]}' and p='{$arr[4]}' and q='{$arr[5]}'"
		)) ;
		$ruang = $get['nm_ruang'];
		$id_pegawai = $get['ref_idpegawai'];
	}
	if ($id_pegawai!=''){
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where id='{$id_pegawai}' "
		)) ;
		
		$penanggungjawab="
		<tr><td width='149'><b>PENANGGUNG JAWAB</td><td width='10'>:</td><td><b></td></tr>".
		"<tr><td width='149'></td><td width='10'></td><td>".
		"<table cellpadding='0' cellspacing='0' border='0'>".
		"<tr><td width='149'><b>NAMA</td><td width='10'>:</td><td><b>".$get['nama']."</td></tr>".
		"<tr><td width='149'><b>NIP</td><td width='10'>:</td><td><b>".$get['nip']."</td></tr>".
		"<tr><td width='149'><b>JABATAN</td><td width='10'>:</td><td><b>".$get['jabatan']."</td></tr>".
		"</table></td></tr>";

		
	}
	$paramKIR = 
		"<table cellpadding='0' cellspacing='0' border='0'><tr><td width='149'><b>GEDUNG / RUANG</td><td width='10'>:</td><td><b>$gedung / $ruang</td></tr>$penanggungjawab</table>";
}

if($SPg =='KIP'){
	$arrJnsPegawai = array(				
		array('1','Pemegang Barang'),	
		array('2','Pengurus Barang'),		
		array('3','Pengguna Barang')
	);
	$arrNIPNama = array(				
		array('1','NIP'),	
		array('2','Nama')
	);
	$fmPILNIPNAMA = $_REQUEST['fmPILNIPNAMA'];
	$fmPILJNSPEGAWAI = $_REQUEST['fmPILJNSPEGAWAI'];
	$fmEntryNIPNAMA = $_REQUEST['fmEntryNIPNAMA'];
	
	if($fmPILJNSPEGAWAI != ''){
		$fmPILJNSPEGAWAI = $arrJnsPegawai[$fmPILJNSPEGAWAI-1][1];
	}else{
		$fmPILJNSPEGAWAI = 'Semua';	
	}
	
	$nip_pegawai =''; $nm_pegawai = '';
	switch($fmPILNIPNAMA){
		case '1' : $nip_pegawai = $fmEntryNIPNAMA; break;	
		case '2' : $nm_pegawai = $fmEntryNIPNAMA; break;			
	}
		
	
	
	$paramKIP = 
		"<tr><td class='subjudulcetak'>
			<table cellpadding='0' cellspacing='0' border='0'>
				<tr valign='top' ><td width='149' style='font-weight:bold;font-size:10pt'><b>JENIS PEGAWAI</td><td width='10'>:</td>
					<td style='font-weight:bold;font-size:10pt'>$PILJNSPEGAWAI<t/d></tr>
				<tr valign='top' > <td style='font-weight:bold;font-size:10pt'> <b>NAMA PEGAWAI</td><td>:</td>
					<td style='font-weight:bold;font-size:10pt'>$nm_pegawai</td></tr>
				<tr valign='top' ><td style='font-weight:bold;font-size:10pt'><b>NIP PEGAWAI</td><td>:</td>
					<td style='font-weight:bold;font-size:10pt'>$nip_pegawai</td></tr>
			</table>
		</td></tr>";
}

//--- kode lokasi
//if($tipe =='kertaskerja'){
//	$vKodeLokasi = '';
//}else{
	$vKodeLokasi = "<td class=\"subjudulcetak\" align=right colspan='$jmlkolom'>NO. KODE LOKASI : $KODELOKASI</td>";
//}

//skpd
//if($tipe =='kertaskerja'){
//	$PrintSKPD = PrintSKPD_NoProp($xls);
//}else{
	$PrintSKPD = PrintSKPD($xls);
//}

if($tipe =='kertaskerja'){
	$formatForm = $_GET['SPg']=='belumsensus'?'': "<td><div style='padding: 8 20 8 20;float: right;border: solid 1px #000000;'><b>Format : Form-KIB.$nokib</div></td>";
}

//prnt ttd
//echo "tipe=".$tipe;
$noFooter = $_REQUEST['noFooter'];
if ($noFooter=='1')
{
	
} else {
	
if($tipe =='kertaskerja'){ 
	$printtd = PrintTTD_SensusKK(getTahunSensus(), $width,$xls==1, $cp1, $cp2, $cp3, $cp4, $cp5 );
}else{
	$printtd = PrintTTD($width,$xls==1, $cp1, $cp2, $cp3, $cp4, $cp5 );
}
}


$noTitle = $_REQUEST['noTitle'];


if ($noTitle=='1')
{
$header_title_ctk="";	
}  else {

$header_title_ctk=	"<table width='100%' border=\"0\">
		<tr>
			<td class=\"judulcetak\" colspan='$jmlkolom'><DIV ALIGN=CENTER>$title</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".$PrintSKPD."</td>$formatForm
		</tr>
		$paramKIP
		
	</table>
	
	<table width=\"100%\" border=\"0\">
		<tr>	
			<td>$paramKIR</td>		
			$vKodeLokasi
		</tr>
	</table>
<br>
";



	
}
//$Main->Isi = 
//echo "limit=".$Opsi['Limit'];
echo
"<html>".
$headerHTML.
"
<body>".
$rangkacetak1.
$header_title_ctk
."
	<table class=\"cetak\" style='width:100%' border=1>
		<thead>";

echo $Penatausaha->genHeader($_GET['SPg'],TRUE, $tipe);
echo		"</thead>";
		
		//echo $Opsi['Kondisi'];
			echo $Penatausaha->genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'],TRUE, $_GET['ctk'],'',$tipe);
	echo "</table>
<br>	
".$printtd.
$rangkacetak2.

"</body>
</html>";
?>