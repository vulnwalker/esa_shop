<?php

//-------- get/post parameter cari --------------
$HalDefault = cekPOST("HalDefault",1); //$cari->cek .= ' haldef='.$HalDefault;
//$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';

$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];
$selUrut = $_POST["selUrut"];//$selUrut = 'nmopd';



//-------- default & tes -------------
$fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
$fmWIL 			= $Main->DEF_WILAYAH ; 
//$fmSKPD 		= '05'; //tes
//$fmUNIT			= '00';


Viewer_Cari_GetList();

//---------- header ------------

	

//-------- Kondisi ----------------


//-------- sorting ---------------
///*



//-------- list data ----------------


//-------- tampil All ------------
$cari->hasil = 
		'<!--menuhasil-->
		<table width="100%"><tr><td>
		<!--hasilcari-->
		</td></tr></table>
		
			<table border="1" class="koptable">'.
				$cari->header.$cari->listdata.$cari->totalharga.$cari->footer.
			'</table>
			'.$cari->cek;
		//.'</td></tr></table>';
		

?>