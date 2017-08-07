<?php

function getSKPD($prefix='',$kol1_width=100, $pilihstr='Semua') {
    //global $DisAbled;
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //echo "<br>Group=".login_getGroup();
	$cek = '';
    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
	$disSEKSI = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
	$KondisiSEKSI = "";
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
	if ($pilihstr=='') $pilihstr = 'Semua';
	
    $PilihSKPD = "<option value='00'>--- $pilihstr BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- $pilihstr SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- $pilihstr UNIT ---</option>";
	$PilihSEKSI = "<option value='$kdSubUnit0'>--- $pilihstr SUB UNIT ---</option>";
    
	$fmSKPD = $_REQUEST[$prefix.'fmSKPD'];
	$fmUNIT = $_REQUEST[$prefix.'fmUNIT'];
	$fmSUBUNIT = $_REQUEST[$prefix.'fmSUBUNIT'];
	$fmSEKSI = $_REQUEST[$prefix.'fmSEKSI'];
	
	
	if ($fmSKPD !== "00") {		
		$KondisiSKPD = " and c='$fmSKPD'";
		$PilihSKPD = ""; 
	}else{
		$KondisiSKPD = " and c<>'00'";
	}
	if ($fmUNIT !== "00") { 
		$KondisiUNIT = " and d='$fmUNIT'";	
		$PilihUNIT = ""; 
	} else {
		$KondisiUNIT = " and d<>'00'";	
	}
	if ($fmSUBUNIT !== "00") {
		$KondisiSUBUNIT = " and e='$fmSUBUNIT'";
		$PilihSUBUNIT = ""; 
	}else{
		$KondisiSUBUNIT = " and e<>'00'"; //$PilihSUBUNIT = " and e<>'00' "; 		
	}
	if (($fmSEKSI !== "000") && ($fmSEKSI !== "00")) {
		$KondisiSEKSI = " and e1='$fmSEKSI'";
		$PilihSEKSI = ""; 
	}else{
		$KondisiSEKSI = " and e1<>'000' and e1<>'00' "; //$PilihSUBUNIT = " and e<>'00' "; 		
	}
	
	setcookie('cofmSKPD',$fmSKPD);
	setcookie('cofmUNIT',$fmUNIT);
	setcookie('cofmSUBUNIT',$fmSUBUNIT);
	setcookie('cofmSEKSI',$fmSEKSI);
	
	$style = "style='width:252;'";
    

   //skpd ------------------------------------
    //$cekskpd = 'kon='.$KondisiSKPD;
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c;";   $cek .= $aqry; 
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['c']}. {$isi['nm_skpd']}</option>\n";
    }    
	$ListSKPD = 
		$cekskpd . 
		"<select $disSKPD name='".$prefix."fmSKPD' id='".$prefix."fmSKPD' 
			onChange=\"".$prefix.".pilihBidang()\" $style
		> $PilihSKPD $Ops</select>";
		
	//unit -------------------------------------
	$aqry = "select * from ref_skpd where c='$fmSKPD' and e = '00' $KondisiUNIT order by d;";$cek .= $aqry;
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['d']}. {$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = 
		"<select $disUNIT name='".$prefix."fmUNIT' id='".$prefix."fmUNIT' 
			onChange=\"".$prefix.".pilihUnit()\" $style
		>$PilihUNIT $Ops
		</select>";
		
	//sub unit ----------------------------------	
	$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT'  $KondisiSUBUNIT and e1='$kdSubUnit0' order by e"; $cek .= $aqry;
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['e']}. {$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = 
		"<select $disSUBUNIT name='".$prefix."fmSUBUNIT' id='".$prefix."fmSUBUNIT' 	
			onChange=\"".$prefix.".pilihSubUnit()\"	 $style	
		>$PilihSUBUNIT $Ops</select>";
	
	//seksi ----------------------------------
	
		$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT'  and e='$fmSUBUNIT' $KondisiSEKSI order by nm_skpd"; $cek .= $aqry;
	    $Qry = mysql_query($aqry);
	    $Ops = "";
	    while ($isi = mysql_fetch_array($Qry)) {
	        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
	        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['e1']}. {$isi['nm_skpd']}</option>\n";
	    }
	    $ListSEKSI = 
			"<select $disSEKSI name='".$prefix."fmSEKSI' id='".$prefix."fmSEKSI' 	
				onChange=\"".$prefix.".pilihSeksi()\" $style		
			>$PilihSEKSI $Ops</select>";
	
	
    return array('bidang'=>$ListSKPD, 'unit'=>$ListUNIT, 'subunit'=>$ListSUBUNIT , 'seksi'=>$ListSEKSI , 'cek'=>$cek);
}



$bidang = ''; $unit=''; $subunit=''; $cek = '';
$idprs = $_REQUEST['idprs'];	
$prefix = $_REQUEST['nm'];
$pilihstr = $_REQUEST['pilihstr'];
$get=getSKPD($prefix, 100, $pilihstr);
/*switch($idprs){
	case 'pilihBidang':{
		$content = 
		break;
	}
}
*/
$pageArr = array(
	'bidang'=>$get['bidang'], 
	'unit'=>$get['unit'],
	'subunit'=>$get['subunit'],
	'seksi'=>$get['seksi'],
	'cek'=>$get['cek'], 
);
$page = json_encode($pageArr);	
echo $page;
	
	
?>