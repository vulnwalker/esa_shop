<?php

/*
$HalPLH = cekPOST("HalPLH",1);
$LimitHalPLH = " limit ".(($HalPLH*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidPLH = cekPOST("cidPLH");

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
$fmTANGGALPEMELIHARAAN = cekPOST("fmTANGGALPEMELIHARAAN");
$fmJENISPEMELIHARAAN = cekPOST("fmJENISPEMELIHARAAN");
$fmPEMELIHARAINSTANSI = cekPOST("fmPEMELIHARAINSTANSI");
$fmPEMELIHARAALAMAT = cekPOST("fmPEMELIHARAALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmBUKTIPEMELIHARAAN = cekPOST("fmBUKTIPEMELIHARAAN");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$SDest = $_REQUEST["SDest"];
if ($SDest=='XLS') { $xls=TRUE; } else { $xls=FALSE; }

/*
//LIST PLH
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
*/
//kondisi -------------------------------------------------
$Kondisi = getKondisiSKPD2($fmKEPEMILIKAN,$Main->DEF_PROPINSI,$Main->DEF_WILAYAH,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmBARANGCARIPLH = $_POST['fmBARANGCARIPLH'];
if(!empty($fmBARANGCARIPLH)){
	$Kondisi .= " and nm_barang like '%$fmBARANGCARIPLH%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pemeliharaan)='$fmPilihThn' ";

//limit -------------------------
$HalPLH = cekPOST("HalPLH",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalPLH = " limit ".(($HalPLH	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPLH = !empty($ctk)?"":$LimitHalPLH;

$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
$Tbl = 'v_pemelihara';//" pemeliharaan left join ref_barang using(f,g,h,i,j) ";
$Kondisi = ' where ' .$Kondisi; 
$PeliharaList = Pelihara_List($Tbl, '*',$Kondisi, $LimitHalPLH, $OrderBy, 2,'cetak', TRUE,$Main->PagePerHal * (($HalPLH*1) - 1), FALSE,$fmKIB,$xls);


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
header("Content-Type: octet/stream");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="Daftar Pemeliharaan Barang.xls"');
ob_flush();
flush(); 

$Main->Isi = "

<head>
	<style>
	.nfmt3 {
	mso-number-format:\"0000\";
	
	}
	</style>
	<title>$Main->Judul</title>
</head>

<body>
<table class=\"rangkacetak\">
<tr>
<td valign=\"top\">

	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\" ALIGN='CENTER' colspan=16>
				DAFTAR PEMELIHARAAN BARANG MILIK DAERAH<br>
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
	<!--
	<table class=\"cetak\">
		<thead>
		<TR>
			<TH class=\"th01\" rowspan=2 style='width:20'>No</TD>
			<TH class=\"th01\" rowspan=2 style='width:75'>Kode<BR> Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:40'>Nomor<br>Reg.</TH>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" rowspan=2 style='width:65'>Tanggal<br>Pemelihara<br>an</TH>
			<TH class=\"th01\" rowspan=2>Jenis<br>Pemeliharaan</TH>
			<TH class=\"th02\" colspan=2>Pihak Pemelihara</TH>
			<TH class=\"th02\" colspan=2>Surat Perjanjian/<br> Kontrak</TH>
			<TH class=\"th01\" rowspan=2 style='width:80'>Biaya<br>(Ribuan)</TH>
			<TH class=\"th01\" rowspan=2 style='width:90'>Bukti<br> Pemeliharaan</TH>
			<TH class=\"th01\" rowspan=2 style='width:90'>Keterangan</TH>
		</TR>
		<TR>
			<TH class=\"th01\">Instansi</TH>
			<TH class=\"th01\">Alamat</TH>
			<TH class=\"th01\">Nomor</TH>
			<TH class=\"th01\" style='width:60'>Tanggal</TH>
		</TR>
		</thead>
		$ListBarangPLH
		
	</table>-->
	$PeliharaList

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
			<td class=\"judulcetak\" ALIGN='CENTER'>
				DAFTAR PEMELIHARAAN BARANG MILIK DAERAH<br>
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
	<!--
	<table class=\"cetak\">
		<thead>
		<TR>
			<TH class=\"th01\" rowspan=2 style='width:20'>No</TD>
			<TH class=\"th01\" rowspan=2 style='width:75'>Kode<BR> Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:40'>Nomor<br>Reg.</TH>
			<TH class=\"th01\" rowspan=2>Nama Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:60'>Tahun<br>Perolehan</TH>
			<TH class=\"th01\" rowspan=2 style='width:65'>Tanggal<br>Pemelihara<br>an</TH>
			<TH class=\"th01\" rowspan=2>Jenis<br>Pemeliharaan</TH>
			<TH class=\"th02\" colspan=2>Pihak Pemelihara</TH>
			<TH class=\"th02\" colspan=2>Surat Perjanjian/<br> Kontrak</TH>
			<TH class=\"th01\" rowspan=2 style='width:80'>Biaya<br>(Ribuan)</TH>
			<TH class=\"th01\" rowspan=2 style='width:90'>Bukti<br> Pemeliharaan</TH>
			<TH class=\"th01\" rowspan=2 style='width:90'>Keterangan</TH>
		</TR>
		<TR>
			<TH class=\"th01\">Instansi</TH>
			<TH class=\"th01\">Alamat</TH>
			<TH class=\"th01\">Nomor</TH>
			<TH class=\"th01\" style='width:60'>Tanggal</TH>
		</TR>
		</thead>
		$ListBarangPLH
		
	</table>-->
	$PeliharaList
<br>	
".PrintTTD()."
</td>
</tr>
</table>
		

</body>

";
}
?>