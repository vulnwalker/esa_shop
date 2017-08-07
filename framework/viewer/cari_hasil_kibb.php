<?php
//$stim = time()-$tim; $tim =time(); echo "<br>start list page $stim";
//-------- get/post parameter cari --------------
$HalBI = cekPOST("HalBI",1);
$HalKIB_B = cekPOST("HalKIB_B",1);
//$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$LimitHalKIB_B = " limit ".(($HalKIB_B*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];

$merk 		= $_POST["merk"];
$bahan 		= $_POST["bahan"];
$noPabrik 	= $_POST["noPabrik"];
$noRangka 	= $_POST["noRangka"];
$noMesin	= $_POST["noMesin"];
$noPolisi 	= $_POST["noPolisi"];
$noBPKB 	= $_POST["noBPKB"];


//-------- default & tes -------------
$fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
$fmWIL 			= $Main->DEF_WILAYAH ; 
//$fmSKPD 		= '05'; //tes
//$fmUNIT			= '00';

//echo "add=$addPageParam<br>";
//$stim = time()-$tim; $tim =time(); echo "<br>after getparam $stim";
Viewer_Cari_GetList();
//$stim = time()-$tim; $tim =time(); echo "<br>after getlist $stim";
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