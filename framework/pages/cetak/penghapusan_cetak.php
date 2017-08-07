<?php
$HalHPS = cekPOST("HalHPS",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalHPS = " limit ".(($HalHPS	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalHPS = !empty($ctk)?"":$LimitHalHPS;
/*
$HalHPS = cekPOST("HalHPS",1);
$LimitHalHPS = " limit ".(($HalHPS*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidHPS = cekPOST("cidHPS");

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
$fmTANGGALPENGHAPUSAN = cekPOST("fmTANGGALPENGHAPUSAN");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$SSPg = cekGET("SSPg");
$fmBARANGCARIHPS = cekPOST("fmBARANGCARIHPS");


//LIST HPS
/*
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIHPS))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIHPS%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and penghapusan.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select penghapusan.*,ref_barang.nm_barang from penghapusan inner join ref_barang on concat(penghapusan.f,penghapusan.g,penghapusan.h,penghapusan.i,penghapusan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataHPS = mysql_num_rows($Qry);
$Qry = mysql_query("select penghapusan.*,ref_barang.nm_barang from penghapusan inner join ref_barang on concat(penghapusan.f,penghapusan.g,penghapusan.h,penghapusan.i,penghapusan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalHPS");

$no=$Main->PagePerHal * (($HalHPS*1) - 1);
$cb=0;
$jmlTampilHPS = 0;

$ListBarangHPS = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilHPS++;
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangHPS .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['tgl_penghapusan'])."</td>
			<td class=\"GarisCetak\" style='width:260'>{$isi['uraian']}</td>
			<td class=\"GarisCetak\" style='width:180'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
//$ListBarangHPS .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListHPS, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST HPS
*/

$subpagetitle = 'Buku Inventaris';
switch($SSPg){
	case '03': break;
	case '04': $subpagetitle ='KIB A'; break;
	case '05': $subpagetitle ='KIB B'; break;
	case '06': $subpagetitle ='KIB C'; break;
	case '07': $subpagetitle ='KIB D'; break;
	case '08': $subpagetitle ='KIB E'; break;
	case '09': $subpagetitle ='KIB F'; break;
}
$subpagetitle = '<br>'.$subpagetitle;
$ListData = Penghapusan_daftar(TRUE);

$xls = $_REQUEST['xls'];
if($xls==1){
	$fname='penghapusan.xls';
	header("Content-type: application/msexcel");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");

	$headerHTML  =
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
		</head>";
		
	}else{
		$headerHTML = 		
			
			"<head>
			<title>$Main->Judul</title>
			<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
			</head>";
	}
		
		
		
		
	$Main->Isi = 
		
		"<head>".
			//<title>$Main->Judul</title>
			//<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
			$headerHTML.
		"</head>
		
		<body>
		<table class=\"rangkacetak\">
		<tr>
		<td valign=\"top\">
		
			<table style='width:30cm' border=\"0\">
				<tr>
					<td class=\"judulcetak\"><DIV ALIGN=CENTER>PENGHAPUSAN BARANG MILIK DAERAH $subpagetitle</td>
				</tr>
			</table>
		
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD()."</td>
				</tr>
			</table>
			
		<br>
		
			<table class=\"cetak\">		
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