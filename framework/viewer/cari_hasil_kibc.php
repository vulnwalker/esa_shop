<?php

//-------- get/post parameter cari --------------
$HalBI = cekPOST("HalBI",1);
$HalKIB_C = cekPOST("HalKIB_C",1);
//$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$LimitHalKIB_C = " limit ".(($HalKIB_C*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];
//kibc
$konsTingkat= $_POST['konsTingkat'];
$konsBeton	= $_POST['konsBeton'];
$dokumen_no	= $_POST['dokumen_no'];
$kode_tanah	= $_POST['kode_tanah'];
$alamat 	= $_POST['alamat'];
$selKabKota	= $_POST['selKabKota'];
$status_tanah= $_POST['status_tanah'];	

//-------- default & tes -------------
$fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
$fmWIL 			= $Main->DEF_WILAYAH ; 

//-------- sorting ---------------
//$cbAscDsc = $_POST['cbAscDsc'];



//---------- header ------------
	

//-------- Kondisi ----------------

Viewer_Cari_GetList();

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