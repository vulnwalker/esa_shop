<?php
/*
2010.11.11
- tambah total 

*/



$tgl = ($fmTahun+1).'-1-1';


//$jmPerHal = cekPOST("jmPerHal");
//$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
$HalDefault = cekPOST("HalDefault",1);
$noawal = $Main->PagePerHal * (($HalDefault*1) - 1)-1;

if ($noawal<=0){
	$noawal=0;
	$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".($Main->PagePerHal-1);
}else{
	$LimitHal = " limit ".((($HalDefault	*1) - 1) * $Main->PagePerHal-1).",".$Main->PagePerHal;
}
//$LimitHal = '';
//$cek .= ' limit'.$LimitHal.'<br>';
/*list($rekap->listtable, $jmlData) =  
	getList_RekapByOPD($SPg, $noawal, $LimitHal, '', FALSE, !empty($cbxDlmRibu));
*/
list($rekap->listtable, $jmlData) =  
	getList_RekapByOPD2($SPg, $noawal, $LimitHal, '', FALSE, !empty($cbxDlmRibu),$tgl);	

if ($SPg==10){
	$hal_colspan=22;
}else{
	$hal_colspan=6	;
}

	
$rekap->listtable .= '
		<!--footer table -->
		<tr class=""><td colspan=11 class="GarisDaftar">&nbsp</td></tr>
		<tr class="">
		<td colspan="'.$hal_colspan.'" class="GarisDaftar" align=center height="50">'.$BarisPerHalaman.'&nbsp;&nbsp'.Halaman($jmlData,$Main->PagePerHal,'HalDefault').'</td>
		</tr>
		'.$cek;	




?>