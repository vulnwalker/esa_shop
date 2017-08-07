<?php

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPENGHAPUSAN = cekPOST("fmTANGGALPENGHAPUSAN"); //echo "<br>tglhapus=".$fmTANGGALPENGHAPUSAN;
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");
$fmIsMutasi = cekPOST("fmIsMutasi");//echo '<br>fmismutasi = '.$fmIsMutasi;
$fmKondisi = cekPOST("fmKondisi"); //echo "<br>fmKondisi=".$fmKondisi;
$fmNoSK = cekPOST("fmNoSK");//echo "<br>fmNoSK=".$fmNoSK;
$fmTglSK= cekPOST("fmTglSK"); //echo "<br>fmTglSK=".$fmTglSK;
$fmGambar = cekPOST("fmGambar"); //echo "<br>fmGambar=".$fmGambar;
$fmGambar_old = cekPOST("fmGambar_old"); //echo "<br>fmGambar_old=".$fmGambar_old;
$fmGambar_BI = cekPOST("fmGambar_BI"); //gambar BI bukan gambar pengahapusan!

$fmUID = $HTTP_COOKIE_VARS['coID'];
$fmApraisal = cekPOST("fmApraisal");

$fmID = cekPOST("fmID",0); $cek .= '<br>fmID = '.$fmID;
$cidBI = CekPOST("cidBI"); $cek .= '<br>cidBI = '.$cidBI[0];


//echo 'tes1';

if(empty($ridModul09) && empty($disModul09)){ //cek user read only?
$Info='';

Penghapusan_Proses(); 
$Penghapusan_FormEntry = Penghapusan_GetFormEntry();
$cek .= '<br>act2='.$Act;



$Page_Hidden = "
	<input type=hidden name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
	<input type=hidden name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
	<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
	<input type=hidden name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
	<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
	
	<!--<input type=hidden name='Act'>
	<input type=hidden name='Penghapusan_Baru' value='$Penghapusan_Baru'>	
	<input type=\"hidden\" name=\"option\" value=\"com_users\" />
	<input type=\"hidden\" name=\"task\" value=\"\" />
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />-->
	";

}else{
	if($Act != '' ){$Info = "<script>alert('User tidak diijinkan melakukan Penghapusan Barang!')</script>";}
	//$Main->Entry .= "$Info";
	$Act = '';
	$Baru = '';
}

$cek = '';
$Main->Entry = 
	
	$Penghapusan_FormEntry.
	$Page_Hidden.
	$Info.
	$cek;

?>