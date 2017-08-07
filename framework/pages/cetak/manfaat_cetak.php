<?php
//$HalDPB = cekPOST("HalDPB",1);
//$LimitHalDPB = " limit ".(($HalDPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

//$cidBI = cekPOST("cidBI");
//$cidDPB = cekPOST("cidDPB");

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
$fmTANGGALPEMANFAATAN = cekPOST("fmTANGGALPEMANFAATAN");
$fmBENTUKPEMANFAATAN = cekPOST("fmBENTUKPEMANFAATAN");
$fmKEPADAINSTANSI = cekPOST("fmKEPADAINSTANSI");
$fmKEPADAALAMAT = cekPOST("fmKEPADAALAMAT");
$fmKEPADANAMA = cekPOST("fmKEPADANAMA");
$fmKEPADAJABATAN = cekPOST("fmKEPADAJABATAN");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmJANGKAWAKTU = cekPOST("fmJANGKAWAKTU");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");
$fmBARANGCARIDPB = cekPOST("fmBARANGCARIDPB");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$SSPg = cekGET("SSPg");
$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//Kondisi ---------------------------------------------------
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$Kondisi = getKondisiSKPD();
if(!empty($fmBARANGCARIDPB)){
	$Kondisi .= " and nm_barang like '%$fmBARANGCARIDPB%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pemanfaatan)='$fmPilihThn' ";

//limit -------------------
$ctk = cekGET("ctk");
$HalPMF = cekPOST("HalPMF",1);
$LimitHalPMF = " limit ".(($HalPMF*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPMF = !empty($ctk)?"":$LimitHalPMF;
//list -----------------
$OrderBy = " order by a1,a,b,c,d,e,f,g,h,i,j,thn_perolehan,noreg ";
$ListData = $Pemanfaat->GetList( ' Where '.$Kondisi, $LimitHalPMF, $OrderBy, 2, 'cetak', TRUE, $Main->PagePerHal * (($HalPMF*1) - 1));
//$ListData = Pemanfaatan_GetList(TRUE);

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
			<td class=\"judulcetak\" align='center'>				
				DAFTAR PEMANFAATAN BARANG MILIK DAERAH<br>
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