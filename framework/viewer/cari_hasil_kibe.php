<?php

//-------- get/post parameter cari --------------
$HalBI = cekPOST("HalBI",1);
$HalKIB_E = cekPOST("HalKIB_E",1);
//$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$LimitHalKIB_E = " limit ".(($HalKIB_E*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];

//-------- default & tes -------------
$fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
$fmWIL 			= $Main->DEF_WILAYAH ; 

Viewer_Cari_GetList();
//---------- header ------------
			

//-------- Kondisi ----------------


//-------- sorting ---------------


//-------- list data ----------------


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