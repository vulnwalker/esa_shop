<?php
// include "viewerfnchart.php";
// require_once ('../lib/jpgraph/jpgraph.php');




//-------- get/post parameter cari --------------
// $HalDefault = cekPOST("HalDefault",1); //$cari->cek .= ' haldef='.$HalDefault;
//$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';


$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");

/*
$fmKEPEMILIKAN ='10';
$fmWIL = '10';
$fmSKPD = '00';
$fmUNIT = '00';
$fmSUBUNIT = '00';
*/

//-------- default & tes -------------

 $fmKEPEMILIKAN 	= $Main->DEF_KEPEMILIKAN; 
 $fmWIL 			= $Main->DEF_WILAYAH ; 

// $fmSKPD 		= '05'; 
// $fmUNIT			= '00';




//---------- header ------------

	

//-------- Kondisi ----------------


//-------- sorting ---------------
///*



//-------- list data ----------------


//-------- tampil All ------------
//$tmpfname = tempnam('tmp', 'chart');
//$tmpfnamex=str_replace('.tmp','.png',$tmpfname);
//			$cari->cek=$tmpfnamex;

$tmpfnamex=BuatChartOPD($fmmodelchart,$fmstylechart);	

$cari->cek .= ' masuk test';
		

$cari->hasil = 
		'<!--menuhasil-->
		<table width="100%"><tr><td><div align="center">
		<img src="'.$tmpfnamex.'"> </img>
		</div></td></tr></table>
		
			'.$cari->cek;
			
		//.'</td></tr></table>';
	


?>