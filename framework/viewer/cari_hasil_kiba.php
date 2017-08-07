<?php

//-------- get/post parameter cari --------------
//$HalDefault = cekPOST("HalDefault",1);
//$LimitHalKIB_A = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
$HalKIB_A = cekPOST("HalKIB_A",1);
//$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$LimitHalKIB_A = " limit ".(($HalKIB_A*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = $_POST['fmUNIT'];
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];
$selUrut = $_POST["selUrut"];//$selUrut = 'nmopd';

//kibA
$selHakPakai= $_POST['selHakPakai'];
$alamat 	= $_POST['alamat'];
$selKabKota	= $_POST['selKabKota'];
$bersertifikat = $_POST['bersertifikat'];
$noSert 	= $_POST['noSert'];


//-------- default & tes -------------
$fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
$fmWIL 			= $Main->DEF_WILAYAH ; 


//List --------------------------
Viewer_Cari_GetList();


//-------- tampil All ------------
$cari->hasil = 
		'<!--menuhasil-->
		<table width="100%"><tr><td>
		<!--hasilcari-->
		</td></tr></table>
		
			<table border="1" class="koptable">'.
				$cari->header.$cari->listdata.$cari->totalharga.$cari->footer.
			'</table>';
			
		//</td></tr></table>';

?>