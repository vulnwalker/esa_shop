<?php

$cidBI = cekPOST("cidBI");
$cidPMN = cekPOST("cidPMN");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPENGAMANAN = cekPOST("fmTANGGALPENGAMANAN");
$fmJENISPENGAMANAN = cekPOST("fmJENISPENGAMANAN");
$fmURAIANKEGIATAN = cekPOST("fmURAIANKEGIATAN");
$fmPENGAMANINSTANSI = cekPOST("fmPENGAMANINSTANSI");
$fmPENGAMANALAMAT = cekPOST("fmPENGAMANALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmBUKTIPENGAMANAN = cekPOST("fmBUKTIPENGAMANAN");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$SDest = $_REQUEST["SDest"];

if ($SDest=='XLS') { $xls=TRUE; } else { $xls=FALSE; }




//Kondisi ---------------------------------------------
$Kondisi = getKondisiSKPD2($fmKEPEMILIKAN,$Main->DEF_PROPINSI,$Main->DEF_WILAYAH,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmBARANGCARIPMN = $_POST['fmBARANGCARIPMN'];
if(!empty($fmBARANGCARIPMN)){
	$Kondisi .= " and nm_barang like '%$fmBARANGCARIPMN%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pengamanan)='$fmPilihThn' ";



//limit hal -------------------------------------------
$HalPMN = cekPOST("HalPMN",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalPMN = " limit ".(($HalPMN	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPMN = !empty($ctk)?"":$LimitHalPMN;

$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
$Tbl = 'v_pengaman';//" pengamanan left join ref_barang using(f,g,h,i,j) ";
$Kondisi = ' where ' .$Kondisi; 
$ListBarangPMN = Pengaman_List('v_pengaman', '*',$Kondisi, $LimitHalPMN, $OrderBy, 2,'cetak', TRUE,$Main->PagePerHal * (($HalPMN*1) - 1), FALSE, $fmKIB,$xls);


$subpagetitle = 'Buku Inventaris';
switch($fmKIB){
	//case '03': break;
	case '01': $subpagetitle ='KIB A'; break;
	case '02': $subpagetitle ='KIB B'; break;
	case '03': $subpagetitle ='KIB C'; break;
	case '04': $subpagetitle ='KIB D'; break;
	case '05': $subpagetitle ='KIB E'; break;
	case '06': $subpagetitle ='KIB F'; break;
	
}

if (!empty($fmPilihThn )){
	$tampilTahun = "<br>Tahun $fmPilihThn ";
}

if ($xls){
set_time_limit(0);
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="Daftar Pengamanan Barang.xls"');
ob_flush();
flush();
$Main->Isi = "

<head>
	<title>$Main->Judul</title>

</head>

<body>
<table class=\"rangkacetak\">
<tr>
<td valign=\"top\">

	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\" ALIGN=CENTER colspan=18 >
				DAFTAR PENGAMANAN BARANG MILIK DAERAH <br>
				$subpagetitle
				$tampilTahun
			</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\" colspan=5 >".PrintSKPD()."</td>
		</tr>
	</table>
	
<br>

	
	$ListBarangPMN

</td>
</tr>
</table>
		

</body>

"; 	
} else {
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
			<td class=\"judulcetak\" ALIGN=CENTER>
				DAFTAR PENGAMANAN BARANG MILIK DAERAH <br>
				$subpagetitle
				$tampilTahun
			</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".PrintSKPD()."</td>
		</tr>
	</table>
	
<br>

	
	$ListBarangPMN
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";	
}

?>