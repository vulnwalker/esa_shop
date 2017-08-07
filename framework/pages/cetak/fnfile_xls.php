<?php
function tampilNmSubUnitXLS($isi){

	if($isi['c']!= '00' && $isi['d']=='00' && $isi['e']=='00' ){
		$nmSUBUNIT = '';
		$nmUNIT = '';
		if($isi['d']!='00'){
			$astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c'].$isi['d']."00'";//echo "<br>astr = ".$astr;
			$nmUNIT = table_get_value($astr, 'nm_skpd');
		}
		if($isi['e']!='00'){
			$astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c'].$isi['d'].$isi['e']."'";//echo "<br>astr = ".$astr;
			$nmSUBUNIT = table_get_value($astr, 'nm_skpd');
		}
		$str = $nmUNIT.' - '.$nmSUBUNIT;
	} elseif ($isi['c']!= '00' && $isi['d']!='00' && $isi['e'] =='00'){ //echo "<br> d =".$isi['d'];
		$astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c'].$isi['d'].$isi['e']."'";//echo "<br>astr = ".$astr;
		$nmSUBUNIT = table_get_value($astr, 'nm_skpd');
		$str = $nmSUBUNIT;
	}
	
	$str = ' / '.$str;	
	return $str;
}

?>
