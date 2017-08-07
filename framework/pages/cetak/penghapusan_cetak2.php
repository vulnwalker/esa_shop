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
$fmSEKSI = cekPOST("fmSEKSI");
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
	
$SDest = $_REQUEST["SDest"];
if ($SDest=='XLS') { $xls=TRUE; } else { $xls=FALSE; }

$Kondisi = getKondisiSKPD2($fmKEPEMILIKAN,$Main->DEF_PROPINSI,$Main->DEF_WILAYAH,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
$fmFiltThnHapus = cekPOST('fmFiltThnHapus');

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