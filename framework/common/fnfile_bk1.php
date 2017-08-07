<?php

include ('fnglobal.php');
include ('fndb.php');
include ('fnuser.php');
include ('fnupload.php');
include ('fnform.php');
include ('fndok.php');
include ('fnimg.php');
include ('fngbr.php');
include ('fnkotakec.php');
include ('fnmap.php');
include ('fnpenghapusan.php');
include ('fnpelihara.php');
include ('fnpengaman.php');
include ('fnpemanfaat.php');
include ('fnhapussebagian.php');
include ('fnpenatausaha.php');


function WilSKPD_ajx2($prefix='', $tblwidth='100%', $kol1_width=100) {
    //global $DisAbled;
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //echo "<br>Group=".login_getGroup();

    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
	$disSEKSI = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
	$KondisiSEKSI = "";
    $PilihSKPD = "<option value='00'>--- PILIH BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- PILIH SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- PILIH UNIT ---</option>";
	$PilihSEKSI = "<option value='00'>--- PILIH SUBUNIT ---</option>";
   
	
	$fmSKPD = $_REQUEST[$prefix.'fmSKPD'];
	$fmUNIT = $_REQUEST[$prefix.'fmUNIT'];
	$fmSUBUNIT = $_REQUEST[$prefix.'fmSUBUNIT'];
	$fmSEKSI = $_REQUEST[$prefix.'fmSEKSI'];
	
	if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";        
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmSKPD'])) {
	       $fmSKPD = $HTTP_COOKIE_VARS['cofmSKPD'];
	    }
	}
	if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";        
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmUNIT'])) {
	       $fmUNIT = $HTTP_COOKIE_VARS['cofmUNIT'];
	    }
	}
	if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])) {
	       $fmSUBUNIT = $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	    }	
	}
	if ($HTTP_COOKIE_VARS["coSEKSI"] !== "000") {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmSEKSI'])) {
	       $fmSEKSI = $HTTP_COOKIE_VARS['cofmSEKSI'];
	    }
	}
	/*
	$style=
	 "style='
	    color: red;
	    background-color: black;
	    font-weight: bold;
	    font-size: 12;
	    font-family: verdana;
	'";
   */
   	//skpd -------------------
	$style = "style='width:318;'";
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c";   
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }    
	$ListSKPD = 
		//$cekskpd . 'pref='.$prefix.
		"<div id='".$prefix."CbxBidang'>
			<select $disSKPD name='".$prefix."fmSKPD' id='".$prefix."fmSKPD' $style
				onChange=\"".$prefix.".pilihBidang()\"
			> $PilihSKPD $Ops</select></div>";
	
	//unit ------------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = 
		"<div id='".$prefix."CbxUnit'>
			<select $disUNIT name='".$prefix."fmUNIT' id='".$prefix."fmUNIT' $style
				onChange=\"".$prefix.".pilihUnit()\">
				$PilihUNIT $Ops
			</select></div>";
			
	//sub unit -------------------------
	$seksi_ = $Main->SUB_UNIT? " and e1='000' ":'';
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $seksi_ $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<div id='".$prefix."CbxSubUnit'>
		<select $disSUBUNIT name='".$prefix."fmSUBUNIT' 
			id='".$prefix."fmSUBUNIT' 
			onChange=\"".$prefix.".pilihSubUnit()\" $style
		>	$PilihSUBUNIT $Ops</select></div>";

	//seksi --------------------------------
	if($Main->SUB_UNIT ){
	    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT' and e1<>'000'  $KondisiSEKSI order by e1");
	    $Ops = "";
	    while ($isi = mysql_fetch_array($Qry)) {
	        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
	        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
	    }
	    $ListSEKSI = "<div id='".$prefix."CbxSeksi'>
			<select $disSEKSI name='".$prefix."fmSEKSI' 
				id='".$prefix."fmSEKSI' 
				onChange=\"".$prefix.".pilihSeksi()\" $style
			>	$PilihSEKSI $Ops</select></div>";
		
	}
	
    $Hsl = "
		<!--<script src='js/skpd.js' type='text/javascript'></script>-->
		<div style='float: left;'>
			<table width=\"100%\"  cellpadding='0' cellspacing='0' border='0' > 
				<tr valign=\"top\"> <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>$ListSUBUNIT</td> </tr> 
				<tr valign=\"top\"> <td>$ListSEKSI</td> </tr> 
			</table> 
		</div>
	";
	

    return $Hsl;
}



function PanelIcon($Link="", $Image="module.png", $Isi="Isinya", $Rid="") {
    global $Pg;
    $RidONLY = "";
    $Ret = " <table border=\"0\" align=\"center\" >
			<tr> <td align=center> 
				<table width=\"84%\" > 
				<tr align=\"center\"> <td width=\"34%\" valign=\"top\" align=center> 
				<div id=\"cpanel\"> <div class=\"icon\"> <a$RidONLY href=\"$Link\"> 
				<img src=\"images/administrator/images/$Image\"  alt=\"$Isi\" width=\"48\" height=\"48\" border=\"0\" align=\"middle\" />
				<span>$Isi</span></a> 
				</div> </div> 
				</td> </tr> 
				</table> 
			</td> </tr> 
			</table> ";
    return $Ret;
}

function PanelIcon1($Link="", $Image="save2.png", $Isi="Isinya", $id="", $ReadOnly="", $DisAbled="", $Rid="", $hint='') {
    global $Pg;
    $RidONLY = "";
    if (!Empty($ReadOnly)) {
        $Link = "#FORMENTRY";
    }
	$shint = $hint ==''? $Isi : $hint;
    $Ret = " <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tr valign=\"middle\" align=\"center\"> 
			<td class='border:none'> 
				<a$RidONLY class=\"toolbar\" id=\"$id\" href=\"$Link\" $DisAbled> 
				<img src=\"images/administrator/images/$Image\"  alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" 
					title=\"$shint\" /> $Isi</a> 
			</td> 
			</tr> 
			</table> ";
    return $Ret;
}

function PanelIcon3($Link="", $Isi="Isinya", $ReadOnly="", $DisAbled="", $Rid="", $lineHeight=1, $width=80) {
    global $Pg;
    $RidONLY = "";
    //if(!Empty($ReadOnly)){$Link="#FORMENTRY";}
    $RidONLY = $DisAbled . $ReadOnly;
    $clToolbar = "toolbar2";
	 
    if (empty($DisAbled) && empty($ReadOnly)) {
        //$Link = "";
        //$clToolbar = "toolbar2disabled";
        $aa = "<a$RidONLY  class=\"toolbar2\" href=\"$Link\" $DisAbled style=\"line-height:$lineHeight;width:$width;\"> <b>$Isi</b></a> ";
    } else {
        //$clToolbar = "toolbar2";
        $aa = "<div style='padding:8;border: 1px solid #AAAAAA;color : #808080;line-height:$lineHeight;width:$width;' ><b>$Isi</b></div> ";
    }
    $Ret = " <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar2\">
			<tr class=''> 
			<td class='' valign=\"middle\" align=\"center\" style=''> 
				$aa
				
			</td> 
			</tr> 
			</table> ";
    return $Ret;
}

function ProsesCekField($Field) {
    $ArField = explode(",", $Field);
    $Status = true;
    for ($i = 0; $i < count($ArField); $i++) {
        global $$ArField[$i]; /* echo $ArField[$i].":".$$ArField[$i]." "; */
        if (empty($$ArField[$i]) && $$ArField[$i] != "0") {
            $Status = false;
        } else {
            
        }
    }
    return $Status;
}

function ProsesCekField2($Field) {
    global $Main;


    $Status = '';
    if ($Field != '') {


        $ArField = explode(",", $Field);
        for ($i = 0; $i < count($ArField); $i++) {
            global $$ArField[$i]; /* echo $ArField[$i].":".$$ArField[$i]." "; */
            if (empty($$ArField[$i]) && $$ArField[$i] != "0") {
                /*
                  $Status = $ArField[$i];
                  switch ($ArField[$i]){
                  case 'fmASALUSUL':
                  $Status = 'Asal usul';
                  break;

                  } */
                $str = $Main->FieldEntryLabel[$ArField[$i]];
                if ($str == '') {
                    $str = $ArField[$i];
                }
                $Status = $str . ' belum diisi!';
            } else {

            }
        }
    }
    return $Status;
}

function KosongkanField($Field) {
    $ArField = explode(",", $Field);
    $Status = true;
    for ($i = 0; $i < count($ArField); $i++) {
        global $$ArField[$i];
        $$ArField[$i] = "";
    }
}

function cmb2D($name='txtField', $value='', $arrList = '', $param='') {
    global $Ref;
    $isi = $value;
    $Input = "<option value=''>Pilih</option>";
    for ($i = 0; $i < count($arrList); $i++) {
        $Sel = $isi == $arrList[$i][0] ? " selected " : "";
        $Input .= "<option $Sel value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
    }
    $Input = "<select $param name='$name'  id='$name'>$Input</select>";
    return $Input;
}

function cmb2D_KIB($name='txtField', $value='', $arrList = '', $param='') {
    global $Ref;
    $isi = $value;
    $Input = "<option value=''>Pilih</option>";
    for ($i = 1; $i <= count($arrList); $i++) {
        $index = $i < 10 ? "0$i" : "$i";
        $Sel = $isi == $arrList[$index][0] ? " selected " : "";
        $Input .= "<option $Sel value='{$arrList[$index][0]}'>{$arrList[$index][1]}</option>";
    }
    $Input = "<select $param name='$name'  id='$name'>$Input</select>";
    return $Input;
}

function cmbQuery($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='') {
    global $Ref;
    $Input = "<option value='$vAtas'>$Atas</option>";
    $Query = mysql_query($query);
    while ($Hasil = mysql_fetch_row($Query)) {
        $Sel = $Hasil[0] == $value ? "selected" : "";
        $Input .= "<option $Sel value='{$Hasil[0]}'>{$Hasil[1]}";
    }
    $Input = "<select $param name='$name' id='$name'>$Input</select>";
    return $Input;
}
function cmbQuery2($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$fvalue='') {
    global $Ref;
    $Input = "<option value='$vAtas'>$Atas</option>";
    $Query = mysql_query($query);
	$x=0;
	$y=0;
    while ($Hasil = mysql_fetch_row($Query)) {
        $Sel = $Hasil[0] == $value ? "selected" : "";
		if ($Sel=="selected")
		{
		$x=1;
		}
		if ($fvalue==$Hasil[0])
		{
		$y=1;	
		}
        $Input .= "<option $Sel value='{$Hasil[0]}'>{$Hasil[1]}";
    }
/*
	$Input .= $x==0?"<option selected value='$fvalue'>$fvalue":"";
	if ($x==1 && $y==0)
	{
	$Input .="<option value='$fvalue'>$fvalue";
		
	}
*/
    $Input = "<select $param name='$name' id='$name'>$Input</select>";
    return $Input;
}
function cmbQuery1($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='') {
    global $Ref;
    $Input = "<option value='$vAtas'>$Atas</option>";
    $Query = mysql_query($query);
    while ($Hasil = mysql_fetch_array($Query)) {
        $Sel = $Hasil[0] == $value ? "selected" : "";
        $Input .= "<option $Sel value='{$Hasil[0]}'>{$Hasil[0]}. {$Hasil[1]}";
    } $Input = "<select $param name='$name' id='$name'>$Input</select>";
    return $Input;
}

function txtField($name='txtField', $value='', $maxlength='', $size='20', $type='text', $param='') {
    $value = stripslashes($value);
    $Input = "<input type=\"$type\" name=\"$name\" id=\"$name\" value=\"$value\" size=\"$size\" maxlength=\"$maxlength\" $param>";
    return $Input;
}

function InputKalender($NAMA="TGL", $Param="") {
    global $$NAMA;
    $VAL = $$NAMA;
    $d = "<div>
			<input type=text name='$NAMA' ID='$NAMA' value='$VAL' size='8' readonly='1' $Param>
			&nbsp;<img src=\"js/jscalendar-1.0/img.gif\" id=\"F$NAMA\" style=\"cursor: pointer; border: 1px solid red;\" title=\"Pilih tanggal dalam kalender\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" /><script type=\"text/javascript\">Calendar.setup({inputField:\"$NAMA\",button:\"F$NAMA\",align:\"HR\"});</script></div>";
    return $d;
}

function cariInfo($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="", $DisAbled="", $Param="") {
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
    $in = "
	<input type=text name='$ID' value='$VALID' size='15' $ReadOnly  
		onblur=\"$NMIFRAME.document.all.formI.Cari.value=''+this.value+'';
				$NMIFRAME.document.all.formI.submit();\"
		
				> 
	<iframe onblur=\"document.all.cari$NMIFRAME.style.visibility='hidden'\" id='$NMIFRAME' 
		style='position:absolute;visibility:hidden' name='$NMIFRAME' 
		src='$URL?objID=$ID&objNM=$NM&Cari2=$VALID'>
	</iframe> 
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objNM=$NM&kdBrgOld=$VALID&Act=$Act'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> (  atau Klik 
		<input type=button value='Cari' onClick=\"TampilkanIFrame(document.all.cari$NMIFRAME)\" > 
		untuk mencari data) ";
    return $in;
}

function cariInfo2($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="", $DisAbled="", $Param="", $KIB="") {
    global $SPg, $Act, $$ID, $$NM, $Baru;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
    $in = "
	<input type=text name='$ID' value='$VALID' size='15' $ReadOnly  
		onblur=\"$NMIFRAME.document.all.formI.Cari.value=''+this.value+'';
				$NMIFRAME.document.all.formI.submit();\"
		
				> 
	<iframe onblur=\"document.all.cari$NMIFRAME.style.visibility='hidden'\" id='$NMIFRAME' 
		style='position:absolute;visibility:hidden' name='$NMIFRAME' 
		src='$URL?objID=$ID&objNM=$NM&Cari2=$VALID'>
	</iframe> 
	<iframe id='cari$NMIFRAME' style='z-index:99;position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objNM=$NM&kdBrgOld=$VALID&Act=$Act&SPg=$SPg&KIB=$KIB&Baru=$Baru'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> (  atau Klik 
		<input type=button value='Cari' onClick=\"document.body.style.overflow='hidden';TampilkanIFrame(document.all.cari$NMIFRAME)\" > 
		untuk mencari data) ";
    return $in;
}

function setWilSKPD() {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main,
    $HTTP_COOKIE_VARS, $HTTP_POST_VARS;
	//$fmSEKSI = $_REQUEST['fmSEKSI'];
    /* $fmKEPEMILIKAN = isset($HTTP_POST_VARS['fmKEPEMILIKAN']) ? $HTTP_POST_VARS['fmKEPEMILIKAN'] : "";
      if(empty($fmKEPEMILIKAN)) { if(isset($HTTP_COOKIE_VARS['cofmKEPEMILIKAN'])) {
      $fmKEPEMILIKAN = $HTTP_COOKIE_VARS['cofmKEPEMILIKAN']; }
      } */
    /* if(empty($fmWIL)) {
      if(isset($HTTP_COOKIE_VARS['cofmWIL'])) {
      $fmWIL = $HTTP_COOKIE_VARS['cofmWIL'];
      }
      } */
    //echo "<br>cofmSKPD=".$HTTP_COOKIE_VARS['cofmSKPD']." fmSKPD=".$fmSKPD;
    if (empty($fmSKPD)) {
        if (isset($HTTP_COOKIE_VARS['cofmSKPD'])) {
            $fmSKPD = $HTTP_COOKIE_VARS['cofmSKPD'];
        }
    }
    //echo "<br>cofmUNIT=".$HTTP_COOKIE_VARS['cofmUNIT']." fmUNIT=".$fmUNIT;
    if (empty($fmUNIT)) {
        if (isset($HTTP_COOKIE_VARS['cofmUNIT'])) {
            $fmUNIT = $HTTP_COOKIE_VARS['cofmUNIT'];
        }
    }
    if (empty($fmSUBUNIT)) {
        if (isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])) {
            $fmSUBUNIT = $HTTP_COOKIE_VARS['cofmSUBUNIT'];
        }
    }
    if (empty($fmTAHUNANGGARAN)) {
        if (isset($HTTP_COOKIE_VARS['cofmTAHUNANGGARAN'])) {
            $fmTAHUNANGGARAN = $HTTP_COOKIE_VARS['cofmTAHUNANGGARAN'];
        }
    }
	if (empty($fmSEKSI)) {
        if (isset($HTTP_COOKIE_VARS['cofmSEKSI'])) {
            $fmSEKSI = $HTTP_COOKIE_VARS['cofmSEKSI'];
        }
    }
    //setcookie("cofmKEPEMILIKAN",$fmKEPEMILIKAN);
    setcookie("cofmWIL", $fmWIL);
    setcookie("cofmSKPD", $fmSKPD);
    setcookie("cofmUNIT", $fmUNIT);
	setcookie("cofmSEKSI", $fmSEKSI);
    setcookie("cofmSUBUNIT", $fmSUBUNIT);
    setcookie("cofmTAHUNANGGARAN", $fmTAHUNANGGARAN);
	//echo 'fmSKPD'.$fmSKPD;
}

function WilSKPD() {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg,$Main;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    $disSKPD = "";
    $disUNIT = "";
    $disSUBUNIT = "";
    $disSEKSI = "";
    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $KondisiSEKSI = "";
    $PilihSKPD = "<option value=''>--- Pilih BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
    $PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";

	if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";
    }
	if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";
    } 
	if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }
	if ($HTTP_COOKIE_VARS["coSEKSI"] !== $kdSubUnit0) {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";
    }
    
	/*$Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
    $Ops = "";    
	while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmWIL == $isi['b'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
    } 
	$ListKab = "<select name='fmWIL'  onChange=\"adminForm.target='_self';adminForm.submit()\"><option value=''>--- Pilih Kabupaten/Kota ---</option>$Ops</select>";
    */
	$get = mysql_fetch_array(mysql_query("select * from ref_wilayah where b='$Main->DEF_WILAYAH' and b<>'00'"));
	if($get['nm_wilayah']==''){	$ListKab = ' - ';	}else{$ListKab = $get['nm_wilayah']; }
	
	$ListKab .= "<input type='hidden' id='fmWIL' name='fmWIL' value='$Main->DEF_WILAYAH'>";
	
	$Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : ""; //$sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    } 
	
	
	
	$ListSKPD = 
		"<select $disSKPD name='fmSKPD' 
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmUNIT.value='00';adminForm.submit()\"
		>$PilihSKPD $Ops</select>";
	/*$ListSKPD = 
		"<select $disSKPD name='fmSKPD' 
			onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmUNIT.value='00';adminForm.fmSUBUNIT.value='00';adminForm.submit()\"
		>$PilihSKPD $Ops</select>";*/
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : ""; 
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    } 
	
	$ListUNIT = "<select $disUNIT name='fmUNIT' 
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmSUBUNIT.value='00';adminForm.submit()\"
		>$PilihUNIT $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSUBUNIT = "<select disSUBUNIT name='fmSUBUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">$PilihSUBUNIT $Ops</select>";
	
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1 <> '$kdSubUnit0' $KondisiSEKSI order by e1");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSEKSI = "<select disSEKSI name='fmSEKSI' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">$PilihSEKSI $Ops</select>";	
	
	
    $Hsl = 
		"<div id='wilskpd1' style=' padding: 4px;border: 1px solid #E5E5E5;'>
		<table width=\"100%\" height=\"100%\" class=\"adminform\" style='border:0'> 
		<tr height='20'> 
			<td width=120>TAHUN ANGGARAN</td> <td width='10'>:</td> 
			<td><b><INPUT type=text name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN' size='6'  onBlur='adminForm.submit();'></b></td> 
		</tr> 
		<tr height='20'> 
			<td>PROVINSI</td> <td>:</td> <td><b>{$Main->Provinsi[1]}</b></td> 
		</tr> 
		<tr height='20'> 
			<td >KABUPATEN / KOTA</td> <td >:</td> 
			<td >$ListKab</td> 
		</tr> 
		<tr height='20'>   
			<td>BIDANG</td>   <td>:</td>   
			<td>$ListSKPD</td> 
		</tr> 
		<tr height='20'>   
			<td>SKPD</td> <td>:</td> 
			<td>$ListUNIT</td> 
		</tr> 
		<tr height='20'>   
			<td>UNIT</td> 
			<td>:</td> <td>$ListSUBUNIT</td> 
		</tr> 
		<tr height='20'>   
			<td>SUB UNIT</td> 
			<td>:</td> <td>$ListSEKSI</td> 
		</tr> 

		</table> </div> ";
    return $Hsl;
}


function WilSKPD_() {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    $disSKPD = "";
    $disUNIT = "";
    $disSUBUNIT = "";
    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $PilihSKPD = "<option value=''>--- Pilih SKPD ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua UNIT ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua SUB UNIT ---</option>";
    if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";
    } if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";
    } if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }
    $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmWIL == $isi['b'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
    } $ListKab = "<select name='fmWIL'  onChange=\"adminForm.target='_self';adminForm.submit()\"><option value=''>--- Pilih Kabupaten/Kota ---</option>$Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSKPD = "<select $disSKPD name='fmSKPD' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmUNIT.value='00';adminForm.submit()\">$PilihSKPD $Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    } $ListUNIT = "<select $disUNIT name='fmUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSUBUNIT = "<select disSUBUNIT name='fmSUBUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">$PilihSUBUNIT $Ops</select>";
    $Hsl = " <table width=\"100%\" height=\"100%\" class=\"adminform\"> <tr> <td width=200>TAHUN ANGGARAN</td> <td>:</td> <td><b><INPUT type=text name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN' size='6'  onBlur='adminForm.submit();'></b></td> </tr> <tr> <td>PROVINSI</td> <td>:</td> <td><b>{$Main->Provinsi[1]}</b></td> </tr> <tr valign=\"top\"> <td width=\"184\">KABUPATEN / KOTA</td> <td width=\"33\">:</td> <td width=\"804\">$ListKab</td> </tr> <tr valign=\"top\">   <td>SKPD</td>   <td>:</td>   <td>$ListSKPD</td> </tr> <tr valign=\"top\">   <td>UNIT</td> <td>:</td> <td>$ListUNIT</td> </tr> <tr valign=\"top\">   <td>SUB UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> </table> ";
    return $Hsl;
}


function WilSKPD1($kol1_width=100) {   
	global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, 
	$fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
   
	
    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
	$disSEKSI = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
	$KondisiSEKSI = "";
	
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
	
    $PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
	$PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";
	
    if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";   //echo "<br>KondisiSKPD=".$KondisiSKPD ;
    }
    if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";   //echo "<br>KondisiUNIT=".$KondisiUNIT ;
    }
    if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";   //echo "<br>KondisiSUBUNIT=".$KondisiSUBUNIT ;
    }
	if ($HTTP_COOKIE_VARS["coSEKSI"] !== $kdSubUnit0) {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";   //echo "<br>KondisiSUBUNIT=".$KondisiSUBUNIT ;
    }
	
	//SKPD ------------------------------------------------
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c";
	$Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSKPD = 
		$cekskpd . 
		"<select $disSKPD name='fmSKPD' id='fmSKPD' 
			onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmUNIT.value='00';adminForm.fmSUBUNIT.value='00';adminForm.fmSEKSI.value='$kdSubUnit0';adminForm.submit()\"
		>
		$PilihSKPD $Ops</select>";
	//UNIT -------------------------------------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00'  $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = "<select $disUNIT name='fmUNIT' id='fmUNIT' onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmSUBUNIT.value='00';adminForm.fmSEKSI.value='$kdSubUnit0';adminForm.submit()\">$PilihUNIT $Ops</select>";
	//SUBUNIT ------------------------------------------------
	$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00'  and e1='$kdSubUnit0' $KondisiSUBUNIT order by e";
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT' id='fmSUBUNIT' onChange=\"adminForm.target='';adminForm.action='';adminForm.fmSEKSI.value='$kdSubUnit0'; adminForm.submit()\">	$PilihSUBUNIT $Ops</select>";
	
	//SEKSI ------------------------------------------------
	$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e='$fmSUBUNIT' and e1<> '$kdSubUnit0' $KondisiSEKSI order by e1"; 
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSEKSI = "<select $disSEKSI name='fmSEKSI' id='fmSEKSI' onChange=\"adminForm.target='';adminForm.action=''; adminForm.submit()\">	$PilihSEKSI $Ops</select>";
	

    $Hsl = "
		<div style='float: left; width: 100%;  padding: 4px;'>
			<table width=\"100%\"   >
 		 				
				<tr valign=\"top\"> <td width='$kol1_width'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>SKPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
				<tr valign=\"top\"> <td>SUBUNIT</td> <td>:</td> <td>$ListSEKSI</td> </tr> 
			</table> 
		</div>
	";

    return $Hsl;
}

function WilSKPD3($kol1_width=100) {   
	global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, 
	$fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
   
	
    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
	$disSEKSI = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
	$KondisiSEKSI = "";
	
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
	
    $PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
	$PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";
    if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";   //echo "<br>KondisiSKPD=".$KondisiSKPD ;
    }
    if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";   //echo "<br>KondisiUNIT=".$KondisiUNIT ;
    }
    if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";   //echo "<br>KondisiSUBUNIT=".$KondisiSUBUNIT ;
    }
	if ($HTTP_COOKIE_VARS["coSEKSI"] !== $kdSubUnit0) {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";   //echo "<br>KondisiSUBUNIT=".$KondisiSUBUNIT ;
    }
	
	//SKPD ------------------------------------------------
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c";
	$Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSKPD = 
		$cekskpd . 
		"<select $disSKPD name='fmSKPD' id='fmSKPD' 
			onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmUNIT.value='00';adminForm.fmSUBUNIT.value='00';adminForm.submit()\"
		>
		$PilihSKPD $Ops</select>";
	//UNIT -------------------------------------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00'  $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = "<select $disUNIT name='fmUNIT' id='fmUNIT' onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";
	//SUBUNIT ------------------------------------------------
	$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00'  and e1='$kdSubUnit0' $KondisiSUBUNIT order by e";
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT' id='fmSUBUNIT' onChange=\"adminForm.target='';adminForm.action=''; adminForm.submit()\">	$PilihSUBUNIT $Ops</select>";
	
	//SEKSI ------------------------------------------------
	$aqry = "select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e='$fmSUBUNIT' and e1<> '$kdSubUnit0' $KondisiSEKSI order by e1"; 
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSEKSI = "<select $disSEKSI name='fmSEKSI' id='fmSEKSI' onChange=\"adminForm.target='';adminForm.action=''; adminForm.submit()\">	$PilihSEKSI $Ops</select>";
	

    $Hsl = "
		<div style='float: left; width: 100%;  padding: 4px;'>
			<table width=\"100%\"   >
<tr> <td width=200>TAHUN ANGGARAN</td> <td>:</td> <td><b><INPUT type=text name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN' size='6'  onBlur='adminForm.submit();'></b></td> </tr>				 				
				<tr valign=\"top\"> <td width='$kol1_width'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>SKPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
				<tr valign=\"top\"> <td>SUBUNIT</td> <td>:</td> <td>$ListSEKSI</td> </tr> 
			</table> 
		</div>
	";

    return $Hsl;
}


function WilSKPD_ajx($prefix='', $tblwidth='100%', $kol1_width=100) {
    //global $DisAbled;
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //echo "<br>Group=".login_getGroup();

    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua ASISTEN / OPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua BIRO / UPTD/B ---</option>";
    /*if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";        
    }
    if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";        
    }
    if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }
	*/
	
	$fmSKPD = $_REQUEST[$prefix.'fmSKPD'];
	$fmUNIT = $_REQUEST[$prefix.'fmUNIT'];
	$fmSUBUNIT = $_REQUEST[$prefix.'fmSUBUNIT'];
	
	if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";        
    }else{
		if(!($fmSKPD == '00' || $fmSKPD=='')){
			$KondisiSKPD = " and c='$fmSKPD'";
			$PilihSKPD = ""; 	
		}
	}
	if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";        
    }else{
		
	
		if(!($fmUNIT == '00' || $fmUNIT=='')){
			$KondisiUNIT = " and d='$fmUNIT'";
    		$PilihUNIT = "";        
		}
	}
	if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }else{
		
	
		if(!($fmSUBUNIT == '00' || $fmSUBUNIT=='')){	
			$KondisiSUBUNIT = " and e='$fmSUBUNIT'";
    		$PilihSUBUNIT = "";
		}
	}
   
   	//skpd -------------------
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c";   
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }    
	$ListSKPD = 
		//$cekskpd . 'pref='.$prefix.
		"<div id='".$prefix."CbxBidang'>
			<select $disSKPD name='".$prefix."fmSKPD' id='".$prefix."fmSKPD' 
				onChange=\"".$prefix.".pilihBidang()\"
			> $PilihSKPD $Ops</select></div>";
	
	//unit ------------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = 
		"<div id='".$prefix."CbxUnit'>
			<select $disUNIT name='".$prefix."fmUNIT' id='".$prefix."fmUNIT' 
				onChange=\"".$prefix.".pilihUnit()\">
				$PilihUNIT $Ops
			</select></div>";
			
	//sub unit -------------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<div id='".$prefix."CbxSubUnit'><select $disSUBUNIT name='".$prefix."fmSUBUNIT' 
		id='".$prefix."fmSUBUNIT' 
			onChange=\"".$prefix.".pilihSubUnit()\"
		>	$PilihSUBUNIT $Ops</select></div>";


    $Hsl = "
		<!--<script src='js/skpd.js' type='text/javascript'></script>-->
		<div style='float: left; width: 90%; height: 80px; padding: 4px;'>
			<table width=\"100%\"   > 
				<tr valign=\"top\"> <td width='$kol1_width'>BIDANG</td> <td width='10'>:</td> <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>ASISTEN / OPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>BIRO / UPTD/B</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
			</table> 
		</div>
	";

    return $Hsl;
}


function WilSKPD_ajx3($prefix='', $tblwidth='100%', $kol1_width=100) {
    //global $DisAbled;
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //echo "<br>Group=".login_getGroup();

    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
    $disSEKSI = $DisAbled;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $KondisiSEKSI = "";

    $PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
    $PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";

    /*if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";        
    }
    if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";        
    }
    if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }
	*/
	
	$fmSKPD = $_REQUEST[$prefix.'fmSKPD'];
	$fmUNIT = $_REQUEST[$prefix.'fmUNIT'];
	$fmSUBUNIT = $_REQUEST[$prefix.'fmSUBUNIT'];
	$fmSEKSI = $_REQUEST[$prefix.'fmSEKSI'];
	
	if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";        
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmSKPD'])) {
	       $fmSKPD = $HTTP_COOKIE_VARS['cofmSKPD'];
	    }
		if(!($fmSKPD == '00' || $fmSKPD=='')){
			//$KondisiSKPD = " and c='$fmSKPD'";//$PilihSKPD = ""; 	
		}	
/*		if(!($fmSKPD == '00' || $fmSKPD=='')){
			$KondisiSKPD = " and c='$fmSKPD'";
	//		$PilihSKPD = ""; 	
		}
*/
	}
	if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";        
    }else{
		if (isset($HTTP_COOKIE_VARS['cofmUNIT'])) {
	       $fmUNIT = $HTTP_COOKIE_VARS['cofmUNIT'];
	    }	
		if(!($fmUNIT == '00' || $fmUNIT=='')){
			//$KondisiUNIT = " and d='$fmUNIT'";//$PilihUNIT = "";        
		}
/*		
	
		if(!($fmUNIT == '00' || $fmUNIT=='')){
			$KondisiUNIT = " and d='$fmUNIT'";
    //		$PilihUNIT = "";        
		}
*/		
	}

	if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    }else{
		
		if (isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])) {
	       $fmSUBUNIT = $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	    }	
		if(!($fmSUBUNIT == '00' || $fmSUBUNIT=='')){	
			//$KondisiSUBUNIT = " and e='$fmSUBUNIT'";//$PilihSUBUNIT = "";
		}	
/*
		if(!($fmSUBUNIT == '00' || $fmSUBUNIT=='')){	
			$KondisiSUBUNIT = " and e='$fmSUBUNIT'";
 //   		$PilihSUBUNIT = "";
		}
*/
	}

	if (($HTTP_COOKIE_VARS["coSEKSI"] !== "00") && ($HTTP_COOKIE_VARS["coSEKSI"] !== "000")) {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";
    }else{
		
		if (isset($HTTP_COOKIE_VARS['cofmSEKSI'])) {
	       $fmSEKSI = $HTTP_COOKIE_VARS['cofmSEKSI'];
	    }	
		if(!($fmSEKSI == '00' || $fmSEKSI=='' || $fmSEKSI=='000')){	
			//$KondisiSUBUNIT = " and e='$fmSUBUNIT'";//$PilihSUBUNIT = "";
		}	
/*
		if(!($fmSEKSI == '00' || $fmSEKSI=='' || $fmSEKSI=='000')){	
			$KondisiSEKSI = " and e1='$fmSEKSI'";
//    		$PilihSEKSI = "";
		}
*/		
	}
   
   	//skpd -------------------



    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $KondisiSEKSI = "";


    $aqry = "select * from ref_skpd where d='00'  order by c";   
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }    
	$ListSKPD = 
		//$cekskpd . 'pref='.$prefix.
		"<div id='".$prefix."CbxBidang'>
			<select $disSKPD name='".$prefix."fmSKPD' id='".$prefix."fmSKPD' 
				onChange=\"".$prefix.".pilihBidang()\"
			> $PilihSKPD $Ops</select></div>";
	
	//unit ------------------------
//		$KondisiUNIT='';
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = 
		"<div id='".$prefix."CbxUnit'>
			<select $disUNIT name='".$prefix."fmUNIT' id='".$prefix."fmUNIT' 
				onChange=\"".$prefix.".pilihUnit()\">
				$PilihUNIT $Ops
			</select></div>";
			
	//sub unit -------------------------
//	$KondisiSUBUNIT='';
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' and e1='$kdSubUnit0' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<div id='".$prefix."CbxSubUnit'><select $disSUBUNIT name='".$prefix."fmSUBUNIT' 
		id='".$prefix."fmSUBUNIT' 
			onChange=\"".$prefix.".pilihSubUnit()\"
		>	$PilihSUBUNIT $Ops</select></div>";

	//seksi -------------------------
//	$KondisiSEKSI='';
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1 <> '00' and e1 <> '000' $KondisiSEKSI order by e1");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSEKSI = "<div id='".$prefix."CbxSeksi'><select $disSEKSI name='".$prefix."fmSEKSI' 
		id='".$prefix."fmSEKSI' 
			onChange=\"".$prefix.".pilihSeksi()\"
		>	$PilihSEKSI $Ops</select></div>";



    $Hsl = "
		<!--<script src='js/skpd.js' type='text/javascript'></script>-->
		<div style='float: left; width: 90%; height: auto; padding: 4px;'>
			<table width=\"100%\"   > 
				<tr valign=\"top\"> <td width='$kol1_width'>BIDANG</td> <td width='10'>:</td> <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>SKPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr>
				<tr valign=\"top\"> <td>SUB UNIT</td> <td>:</td> <td>$ListSEKSI</td> </tr> 
			</table> 
		</div>
	";

    return $Hsl;
}




function WilSKPD1b( $fmSKPD, $fmUNIT, $fmSUBUNIT, $kol1_width=100, $fmSEKSI) {
    //global $DisAbled;
    global  $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //echo "<br>Group=".login_getGroup();

    $disSKPD = $DisAbled;
    $disUNIT = $DisAbled;
    $disSUBUNIT = $DisAbled;
    $disSEKSI = $DisAbled;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );		


    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $KondisiSEKSI = "";
    $PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
    $PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";
    if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";
        //echo "<br>KondisiSKPD=".$KondisiSKPD ;
    }
    if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";
        //echo "<br>KondisiUNIT=".$KondisiUNIT ;
    }
    if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
        //echo "<br>KondisiSUBUNIT=".$KondisiSUBUNIT ;
    }
    if ($HTTP_COOKIE_VARS["coSEKSI"] !== "$kdSubUnit0") {
        $fmSEKSI = $HTTP_COOKIE_VARS["coSEKSI"];
        $HTTP_COOKIE_VARS["cofmSEKSI"] = $fmSEKSI;
        $KondisiSEKSI = " and e1='$fmSEKSI'";
        $PilihSEKSI = "";
        //echo "<br>KondisiSEKSI=".$KondisiSEKSI ;
    }

    /*
      //nm wilayah
      $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
      $Ops = "";
      while($isi=mysql_fetch_array($Qry)) {
      $sel = $fmWIL == $isi['b'] ? "selected":"";
      $Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
      }
      $ListKab = "<select name='fmWIL'  onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\"><option value=''>--- Pilih Kabupaten/Kota ---</option>$Ops</select>";
     */

    //$cekskpd = 'kon='.$KondisiSKPD;
    $aqry = "select * from ref_skpd where d='00' $KondisiSKPD order by c";
    //echo $HTTP_COOKIE_VARS["coSKPD"]." $aqry<br>";
    $Qry = mysql_query($aqry);

    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }
    //$ListSKPD = $cekskpd."<select $disSKPD name='fmSKPD' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmUNIT.value='00';adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihSKPD $Ops</select>";
    $ListSKPD = $cekskpd . "<select $disSKPD name='fmSKPD' id='fmSKPD' onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmUNIT.value='00';adminForm.fmSUBUNIT.value='00';adminForm.fmSEKSI.value='$kdSubUnit0';adminForm.submit()\">$PilihSKPD $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    //$ListUNIT = "<select $disUNIT name='fmUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";
    $ListUNIT = "<select $disUNIT name='fmUNIT' id='fmUNIT' onChange=\"adminForm.target='';adminForm.action=''; adminForm.fmSUBUNIT.value='00';adminForm.fmSEKSI.value='$kdSubUnit0';adminForm.submit()\">$PilihUNIT $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' and e1='$kdSubUnit0' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    //$ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">	$PilihSUBUNIT $Ops</select>";
    $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT' id='fmSUBUNIT' onChange=\"adminForm.target='';adminForm.action='';adminForm.fmSEKSI.value='$kdSubUnit0'; adminForm.submit()\">	$PilihSUBUNIT $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1<>'$kdSEKSI0' $KondisiSEKSI order by e1");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    }
    //$ListSEKSI = "<select $disSEKSI name='fmSEKSI' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">	$PilihSEKSI $Ops</select>";
    $ListSEKSI = "<select $disSEKSI name='fmSEKSI' id='fmSEKSI' onChange=\"adminForm.target='';adminForm.action=''; adminForm.submit()\">	$PilihSEKSI $Ops</select>";


    $Hsl = "
		<div style='float: left; width: 100%; height: 70px; padding: 4px;'>
			<table width=\"100%\"   > 
				<tr valign=\"top\"> <td width='$kol1_width'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>SKPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
				<tr valign=\"top\"> <td>UNIT</td> <td>:</td> <td>$ListSEKSI</td> </tr> 
			</table> 
		</div>
	";

    return $Hsl;
}


function WilSKPD2() {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    $disSKPD = "";
    $disUNIT = "";
    $disSUBUNIT = "";
    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $PilihSKPD = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua UNIT ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua SUB UNIT ---</option>";
    if ($HTTP_COOKIE_VARS["coSKPD"] !== "00") {
        $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"];
        $HTTP_COOKIE_VARS["cofmSKPD"] = $fmSKPD;
        $KondisiSKPD = " and c='$fmSKPD'";
        $PilihSKPD = "";
    } if ($HTTP_COOKIE_VARS["coUNIT"] !== "00") {
        $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"];
        $HTTP_COOKIE_VARS["cofmUNIT"] = $fmUNIT;
        $KondisiUNIT = " and d='$fmUNIT'";
        $PilihUNIT = "";
    } if ($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") {
        $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"];
        $HTTP_COOKIE_VARS["cofmSUBUNIT"] = $fmSUBUNIT;
        $KondisiSUBUNIT = " and e='$fmSUBUNIT'";
        $PilihSUBUNIT = "";
    } $Qry = mysql_query("select * from ref_pemilik order by nm_pemilik");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmKEPEMILIKAN == $isi['a1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['a1']}'>{$isi['nm_pemilik']}</option>\n";
    } $ListKepemilikan = "<select name='fmKEPEMILIKAN'  onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\"><option value=''>--- Pilih Kepemilikan ---</option>$Ops</select>";
    $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmWIL == $isi['b'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
    } $ListKab = "<select name='fmWIL'  onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\"><option value=''>--- Pilih Kabupaten/Kota ---</option>$Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSKPD = "<select $disSKPD name='fmSKPD' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmUNIT.value='00';adminForm.submit()\">$PilihSKPD $Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    } $ListUNIT = "<select $disUNIT name='fmUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";
    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    } $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT' onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">$PilihSUBUNIT $Ops</select>";
    $Hsl = " <table width=\"100%\" height=\"100%\" class=\"adminform\"> <tr> <td width='200'>KEPEMILIKAN BARANG</td> <td width='30'>:</td> <td>$ListKepemilikan</td> </tr> <tr> <!-- <td width=200>TAHUN ANGGARAN</td> <td>:</td> <td><b><INPUT type=text name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN' size='6' onBlur='adminForm.submit();'></b></td> </tr> --> <tr> <td>PROVINSI</td> <td>:</td> <td><b>{$Main->Provinsi[1]}</b></td> </tr> <tr valign=\"top\"> <td width=\"184\">KABUPATEN / KOTA</td> <td width=\"33\">:</td> <td width=\"804\">$ListKab</td> </tr> <tr valign=\"top\">   <td>SKPD</td>   <td>:</td>   <td>$ListSKPD</td> </tr> <tr valign=\"top\">   <td>UNIT</td> <td>:</td> <td>$ListUNIT</td> </tr> <tr valign=\"top\">   <td>SUB UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> </table> ";
    return $Hsl;
}

function WilSKPD2b() {//v2 
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    /* $disSKPD = "";
      $disUNIT = "";
      $disSUBUNIT = ""; $KondisiSKPD = ""; $KondisiUNIT = ""; $KondisiSUBUNIT = "";
      $PilihSKPD = "<option value='00'>--- Semua SKPD ---</option>";
      $PilihUNIT = "<option value='00'>--- Semua UNIT ---</option>";
      $PilihSUBUNIT = "<option value='00'>--- Semua SUB UNIT ---</option>";

      $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
      $Ops = "";
      while($isi=mysql_fetch_array($Qry)) {
      $sel = $fmSKPD == $isi['c'] ? "selected":"";
      $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
      }
      $ListSKPD = "<select $disSKPD name='fmSKPD'
      onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';
      adminForm.fmUNIT.value='00';adminForm.submit()\">$PilihSKPD $Ops</select>";

      $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
      $Ops = "";
      while($isi=mysql_fetch_array($Qry)) {
      $sel = $fmUNIT == $isi['d'] ? "selected":"";
      $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
      }
      $ListUNIT = "<select $disUNIT name='fmUNIT'
      onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';
      adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";

      $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT
      order by e");
      $Ops = "";
      while($isi=mysql_fetch_array($Qry)) {
      $sel = $fmSUBUNIT == $isi['e'] ? "selected":"";
      $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
      }
      $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT'
      onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';
      adminForm.submit()\">$PilihSUBUNIT $Ops</select>";

      $Hsl= " <table width=\"100%\" height=\"100%\" class=\"adminform\">
      <tr><td>
      <table>
      <tr valign=\"top\">   <td width='120'>BIDANG</td>   <td width='20'>:</td>   <td>$ListSKPD</td> </tr>
      <tr valign=\"top\">   <td width='120'>ASISTEN / OPD</td> <td width='20'>:</td> <td>$ListUNIT</td> </tr>
      <tr valign=\"top\">   <td width='120'>BIRO / UPTD/B</td> <td width='20'>:</td> <td>$ListSUBUNIT</td> </tr>
      </table>

      </td></tr>
      </table> ";
     */
    $Hsl = " <table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr><td>
			<table>		
			" . WilSKPDList() . "
			</table>
			
			</td></tr>
			</table> ";
    return $Hsl;
}

function WilSKPDList() {//without table, just list
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    $disSKPD = "";
    $disUNIT = "";
    $disSUBUNIT = "";
	$disSEKSI = "";
    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
	$KondisiSEKSI = "";
    
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
	
	$PilihSKPD = "<option value='00'>--- Semua BIDANG ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua UNIT ---</option>";
	$PilihSEKSI = "<option value='$kdSubUnit0'>--- Semua SUB UNIT ---</option>";
    
    $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSKPD = "<select $disSKPD name='fmSKPD'
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; 
				adminForm.fmUNIT.value='00';adminForm.submit()\">$PilihSKPD $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = "<select $disUNIT name='fmUNIT'
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; 
				adminForm.fmSUBUNIT.value='00';adminForm.submit()\">$PilihUNIT $Ops</select>";
    //subunit
	$Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT and e1='$kdSubUnit0'	order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT'
		onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; 
				adminForm.submit()\">$PilihSUBUNIT $Ops</select>";
	//seksi	
	$Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' $KondisiSEKSI and e1<>'$kdSubUnit0' order by e1");   
	$Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e1']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSEKSI = "<select $disSEKSI name='fmSEKSI'
		onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; 
				adminForm.submit()\">$PilihSEKSI $Ops</select>";


    $Hsl = "
			<tr valign=\"top\">   <td width='100'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 
			<tr valign=\"top\">   <td width='100'>SKPD</td> <td width='10'>:</td> <td>$ListUNIT</td> </tr> 
			<tr valign=\"top\">   <td width='100'>UNIT</td> <td width='10'>:</td> <td>$ListSUBUNIT</td> </tr> 
			<tr valign=\"top\">   <td width='100'>SUB UNIT</td> <td width='10'>:</td> <td>$ListSEKSI</td> </tr>
			 ";

    return $Hsl;
}

function Halaman($Jumlah=0, $PerHal=0, $NameHal="Hal", $Lokasi="", $batas=5, $fnGotoHal='gotoHalaman') {
    global $HTTP_POST_VARS, $Pg, $SPg;
    $Kos = !empty($Lokasi) ? "adminForm.action='$Lokasi';adminForm.target='_self';" : "adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';";
    $Hal = isset($_POST[$NameHal]) ? $_POST[$NameHal] : 1 ;
    /*$JmlHal = ceil($Jumlah / $PerHal);
    $Awal = 1;
    $Akhir = $JmlHal;
    $Sebelum = $Hal > 1 ? $Hal - 1 : 1;
    $Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal;
    $disSebelum = $Hal <= 1 ? " disabled " : "";
    $disSesudah = $Hal >= $JmlHal ? " disabled " : "";

    //generate pilihan hal
    $Str = "";
    $Ops = "";
    $aw = 1;
    $ak = $JmlHal; //default
    $aw = $Hal - $batas >= 1 ? $Hal - $batas : 1;
    $ak = $Hal + $batas <= $JmlHal ? $Hal + $batas : $JmlHal;
    for ($i = $aw; $i <= $ak; $i++) {
        $sel = $i == $Hal ? " selected " : "";
        $Ops .= "<option $sel value='$i'>$i</option>";
    }

    $Ops = "<select onChange=\"$Kos;$fnGotoHal('$NameHal',this.value)\">$Ops</select>";
    $Str = " <input $disSebelum type=button value='Awal' onClick=\"$Kos;$fnGotoHal('$NameHal',$Awal)\">
			<input $disSebelum type=button value='Sebelum' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sebelum)\"> 
			$Ops <input $disSesudah type=button value='Sesudah' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sesudah)\"> 
			<input $disSesudah type=button value='Akhir' onClick=\"$Kos;$fnGotoHal('$NameHal',$Akhir)\"> 
			<input type='hidden' id='$NameHal' name='$NameHal'  value='$Hal'> 
			 ";
	*/
	
	if ($Hal==''){	$Hal = isset($HTTP_POST_VARS[$NameHal])?$HTTP_POST_VARS[$NameHal]:1; }
	$JmlHal = ceil($Jumlah / $PerHal); 
	$Awal = 1; 
	$Akhir = $JmlHal; 
	$Sebelum = $Hal > 1 ?$Hal - 1:1; 
	$Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal; 
	$disSebelum = $Hal <= 1 ? " disabled ":""; 
	$disSesudah = $Hal >= $JmlHal ? " disabled ":""; 
	$disGoTo = $Awal==$Akhir ? " disabled " :"";
	//generate pilihan hal 
	$Str = ""; $Ops = ""; 
	$aw= 1; $ak=$JmlHal; //default	
	$aw = $Hal-$batas>=1? $Hal-$batas: 1;
	$ak = $Hal+$batas <=$JmlHal? $Hal+$batas : $JmlHal;
	$xarray=array(0.25,5,0.75);
	if ($aw>1){
	$maw=1;
	$Ops .= "<option  value='1'>1</option>";

	} else {
		$maw=0;
	}
	
	if ($maw>0){
	for ($i=0;$i<3;$i++){
		$mawx=floor($xarray[$i]*$aw);
		if ($mawx<$aw){
			if ($mawx>$maw){
				$Ops .= "<option  value='$mawx'>$mawx</option>";
				$maw=$mawx;
			}
		}
	}
	}
	for ($i = $aw; $i<=$ak; $i++) { 
		$sel = $i == $Hal ? " selected ":""; $Ops .= "<option $sel value='$i'>$i</option>";
	}
	if ($ak<$Akhir){
	$mak=$Akhir;
	} else {
		$mak=0;
	}	

	if ($mak>0){
	$tmakx=$ak;
	for ($i=0;$i<3;$i++){
		$arg=$Akhir-$ak;

		$makx=floor($xarray[$i]*$arg)+$ak;
		if ($makx>$ak){
			if ($makx>$tmakx && $makx<$Akhir){
				$Ops .= "<option  value='$makx'>$makx</option>";
				$tmakx=$makx;
			}
		}
	}
	$Ops .= "<option value='$Akhir'>$Akhir</option>";
	}

	$Ops = "<select name='cbxhalaman' id='cbxhalaman' onChange=\"$Kos;$fnGotoHal('$NameHal',this.value)\">$Ops</select>"; 
	$Str = " <input $disSebelum type=button value='Awal' onClick=\"$Kos;$fnGotoHal('$NameHal',$Awal)\"> 
			<input $disSebelum type=button value='Sebelum' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sebelum)\"> 
			$Ops <input $disSesudah type=button value='Sesudah' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sesudah)\"> 
			<input $disSesudah type=button value='Akhir' onClick=\"$Kos;$fnGotoHal('$NameHal',$Akhir)\">
			&nbsp;<input $disGoTo type=button value='Lihat Halaman' 
			onClick=\"$Kos;$fnGotoHal('$NameHal',document.getElementById('".$NameHal."_fmHAL').value )\">&nbsp;:&nbsp;
			<input $disGoTo type='text' size='6' maxlength='10' 
				name='".$NameHal."_fmHAL' id='".$NameHal."_fmHAL' value='$Hal'  onkeypress='return isNumberKey(event,1)'> &nbsp;
				<span style='color: red;' >$JmlHal hal &nbsp;/&nbsp; $Jumlah data</span> 
			<input type=hidden id='$NameHal' name='$NameHal' value='$Hal'>
			<script language='javascript'> </script> "; 
	
	
	
    return $Str;
}

function Halaman2($Jumlah=0, $PerHal=0, $NameHal="Hal", $Lokasi="", $Hal="", $batas=5, $fnGotoHal = 'gotoHalaman' ) {
    global $HTTP_POST_VARS, $Pg, $SPg;
    $Kos = !empty($Lokasi) ? "adminForm.Act.value='Tampil'; adminForm.action='$Lokasi';adminForm.target='_self';" : "adminForm.Act.value='Tampil';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';";
    /*$Hal = isset($HTTP_POST_VARS[$NameHal]) ? $HTTP_POST_VARS[$NameHal] : 1;
    $JmlHal = ceil($Jumlah / $PerHal);
    $Awal = 1;
    $Akhir = $JmlHal;
    $Sebelum = $Hal > 1 ? $Hal - 1 : 1;
    $Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal;
    $disSebelum = $Hal <= 1 ? " disabled " : "";
    $disSesudah = $Hal >= $JmlHal ? " disabled " : "";
    $Str = "";
    $Ops = "";
    for ($i = 1; $i <= $JmlHal; $i++) {
        $sel = $i == $Hal ? " selected " : "";
        $Ops .= "<option $sel value='$i'>$i</option>";
    }
    $Ops = "<select onChange=\"$Kos;gotoHalaman('$NameHal',this.value)\">$Ops</select>";
    $Str = " <input $disSebelum type=button value='Awal' onClick=\"$Kos;gotoHalaman('$NameHal',$Awal)\">
			<input $disSebelum type=button value='Sebelum' onClick=\"$Kos;gotoHalaman('$NameHal',$Sebelum)\"> 
			$Ops <input $disSesudah type=button value='Sesudah' onClick=\"$Kos;gotoHalaman('$NameHal',$Sesudah)\"> 
			<input $disSesudah type=button value='Akhir' onClick=\"$Kos;gotoHalaman('$NameHal',$Akhir)\"> 
			<input type=hidden id='$NameHal' name='$NameHal' value='$Hal'> <script language='javascript'> </script> ";
    
	
	
	//$Str = Halaman2b($Jumlah,$PerHal,$NameHal);//, '', 5, 'got');
	*/
	
	if ($Hal==''){	$Hal = isset($HTTP_POST_VARS[$NameHal])?$HTTP_POST_VARS[$NameHal]:1; }
	$JmlHal = ceil($Jumlah / $PerHal); 
	$Awal = 1; 
	$Akhir = $JmlHal; 
	$Sebelum = $Hal > 1 ?$Hal - 1:1; 
	$Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal; 
	$disSebelum = $Hal <= 1 ? " disabled ":""; 
	$disSesudah = $Hal >= $JmlHal ? " disabled ":""; 
	$disGoTo = $Awal==$Akhir ? " disabled " :"";
	//generate pilihan hal 
	$Str = ""; $Ops = ""; 
	$aw= 1; $ak=$JmlHal; //default	
	$aw = $Hal-$batas>=1? $Hal-$batas: 1;
	$ak = $Hal+$batas <=$JmlHal? $Hal+$batas : $JmlHal;
	$xarray=array(0.25,5,0.75);
	if ($aw>1){
	$maw=1;
	$Ops .= "<option  value='1'>1</option>";

	} else {
		$maw=0;
	}
	
	if ($maw>0){
	for ($i=0;$i<3;$i++){
		$mawx=floor($xarray[$i]*$aw);
		if ($mawx<$aw){
			if ($mawx>$maw){
				$Ops .= "<option  value='$mawx'>$mawx</option>";
				$maw=$mawx;
			}
		}
	}
	}
	for ($i = $aw; $i<=$ak; $i++) { 
		$sel = $i == $Hal ? " selected ":""; $Ops .= "<option $sel value='$i'>$i</option>";
	}
	if ($ak<$Akhir){
	$mak=$Akhir;
	} else {
		$mak=0;
	}	

	if ($mak>0){
	$tmakx=$ak;
	for ($i=0;$i<3;$i++){
		$arg=$Akhir-$ak;

		$makx=floor($xarray[$i]*$arg)+$ak;
		if ($makx>$ak){
			if ($makx>$tmakx && $makx<$Akhir){
				$Ops .= "<option  value='$makx'>$makx</option>";
				$tmakx=$makx;
			}
		}
	}
	$Ops .= "<option value='$Akhir'>$Akhir</option>";
	}

	$Ops = "<select name='cbxhalaman' id='cbxhalaman' onChange=\"$Kos;$fnGotoHal('$NameHal',this.value)\">$Ops</select>"; 
	$Str = " <input $disSebelum type=button value='Awal' onClick=\"$Kos;$fnGotoHal('$NameHal',$Awal)\"> 
			<input $disSebelum type=button value='Sebelum' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sebelum)\"> 
			$Ops <input $disSesudah type=button value='Sesudah' onClick=\"$Kos;$fnGotoHal('$NameHal',$Sesudah)\"> 
			<input $disSesudah type=button value='Akhir' onClick=\"$Kos;$fnGotoHal('$NameHal',$Akhir)\">
			&nbsp;<input $disGoTo type=button value='Lihat Halaman' 
			onClick=\"$Kos;$fnGotoHal('$NameHal',document.getElementById('".$NameHal."_fmHAL').value )\">&nbsp;:&nbsp;
			<input $disGoTo type='text' size='6' maxlength='10' 
				name='".$NameHal."_fmHAL' id='".$NameHal."_fmHAL' value='$Hal'  onkeypress='return isNumberKey(event,1)'> &nbsp;
				<span style='color: red;' >$JmlHal hal &nbsp;/&nbsp; $Jumlah data</span> 
			<input type=hidden id='$NameHal' name='$NameHal' value='$Hal'>
			<script language='javascript'> </script> "; 
	
	
	
	
	return $Str;
}

function Halaman2b(
	$Jumlah=0,$PerHal=0,$NameHal="Hal", 
	$Hal="", $batas=5, $fnGotoHal = 'gotoHalaman2()'
	 ) 
{ 
	global $HTTP_POST_VARS, $Pg, $SPg; 
	if ($Hal==''){
		$Hal = isset($HTTP_POST_VARS[$NameHal])?$HTTP_POST_VARS[$NameHal]:1; 
	}
	$JmlHal = ceil($Jumlah / $PerHal); 
	$Awal = 1; 
	$Akhir = $JmlHal; 
	$Sebelum = $Hal > 1 ?$Hal - 1:1; 
	$Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal; 
	$disSebelum = $Hal <= 1 ? " disabled ":""; 
	$disSesudah = $Hal >= $JmlHal ? " disabled ":""; 
	$disGoTo = $Awal==$Akhir ? " disabled " :"";
	//generate pilihan hal 
	$Str = ""; $Ops = ""; 
	$aw= 1; $ak=$JmlHal; //default	
	$aw = $Hal-$batas>=1? $Hal-$batas: 1;
	$ak = $Hal+$batas <=$JmlHal? $Hal+$batas : $JmlHal;
	$xarray=array(0.25,5,0.75);
	if ($aw>1){
	$maw=1;
	$Ops .= "<option  value='1'>1</option>";

	} else {
		$maw=0;
	}
	
	if ($maw>0){
	for ($i=0;$i<3;$i++){
		$mawx=floor($xarray[$i]*$aw);
		if ($mawx<$aw){
			if ($mawx>$maw){
				$Ops .= "<option  value='$mawx'>$mawx</option>";
				$maw=$mawx;
			}
		}
	}
	}
	for ($i = $aw; $i<=$ak; $i++) { 
		$sel = $i == $Hal ? " selected ":""; $Ops .= "<option $sel value='$i'>$i</option>";
	}
	if ($ak<$Akhir){
	$mak=$Akhir;
	} else {
		$mak=0;
	}	

	if ($mak>0){
	$tmakx=$ak;
	for ($i=0;$i<3;$i++){
		$arg=$Akhir-$ak;

		$makx=floor($xarray[$i]*$arg)+$ak;
		if ($makx>$ak){
			if ($makx>$tmakx && $makx<$Akhir){
				$Ops .= "<option  value='$makx'>$makx</option>";
				$tmakx=$makx;
			}
		}
	}
	$Ops .= "<option value='$Akhir'>$Akhir</option>";
	}

	$Ops = "<select name='cbxhalaman' id='cbxhalaman' onChange=\"$fnGotoHal('page',this.value)\">$Ops</select>"; 
	$Str = " <input $disSebelum type=button value='Awal' onClick=\"$fnGotoHal('awal',$Awal)\"> 
			<input $disSebelum type=button value='Sebelum' onClick=\"$fnGotoHal('sebelum',$Sebelum)\"> 
			$Ops <input $disSesudah type=button value='Sesudah' onClick=\"$fnGotoHal('sesudah',$Sesudah)\"> 
			<input $disSesudah type=button value='Akhir' onClick=\"$fnGotoHal('akhir',$Akhir)\">
			&nbsp;<input $disGoTo type=button value='Lihat Halaman' 
			onClick=\"$fnGotoHal('goto',document.getElementById('".$NameHal."_fmHAL').value )\">&nbsp;:&nbsp;
			<input $disGoTo type='text' size='6' maxlength='10' 
				name='".$NameHal."_fmHAL' id='".$NameHal."_fmHAL' value='$Hal'  onkeypress='return isNumberKey(event,1)'> &nbsp;
				<span style='color: red;' >$JmlHal hal &nbsp;/&nbsp; $Jumlah data</span> 
			<input type=hidden id='$NameHal' name='$NameHal' value='$Hal'>
			<script language='javascript'> </script> "; 
	return $Str; 
}

function TahunPerolehan() {
    global $HTTP_POST_VARS, $HTTP_GET_VARS, $fmTahunPerolehan, $Pg, $SPg;
    $str = "";
    $Qry = mysql_query("select thn_perolehan from buku_induk group by thn_perolehan order by thn_perolehan desc");
    $ops = "<option value=''>Semua Tahun</option>";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmTahunPerolehan == $isi['thn_perolehan'] ? "selected" : "";
        $ops .= "<option $sel value='{$isi['thn_perolehan']}'>{$isi['thn_perolehan']}</option>\n";
    }
    $str = "Tahun Perolehan : <select
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';
			adminForm.submit()\" name='fmTahunPerolehan'>$ops</select>";
    return $str;
}

function TahunPerolehan2($param = '') {
    global $HTTP_POST_VARS, $HTTP_GET_VARS, $fmTahunPerolehan, $Pg, $SPg;
    $str = "";
    $Qry = mysql_query("select thn_perolehan from buku_induk group by thn_perolehan order by thn_perolehan desc");
    $ops = "<option value=''>Semua Tahun</option>";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmTahunPerolehan == $isi['thn_perolehan'] ? "selected" : "";
        $ops .= "<option $sel value='{$isi['thn_perolehan']}'>{$isi['thn_perolehan']}</option>\n";
    }
    $str = "<select $param name='fmTahunPerolehan'>$ops</select>";

    
    return $str;
}

function comboTglBuku($param = '') {
    global $HTTP_POST_VARS, $HTTP_GET_VARS, $fmTglBuku, $Pg, $SPg;
    $str = "";
    $Qry = mysql_query("select tgl_buku from buku_induk group by tgl_buku order by tgl_buku desc");
    $ops = "<option value=''>Semua Tgl. Buku</option>";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmTglBuku == $isi['tgl_buku'] ? "selected" : "";
        $ops .= "<option $sel value='{$isi['tgl_buku']}'>" . TglInd($isi['tgl_buku']) . "</option>\n";
    }
    $str = "<select $param name='fmTglBuku'>$ops</select>";
	/*
	$str = genComboBoxQry('fmTahunPerolehan',$fmTahunPerolehan,
					"select tgl_buku from buku_induk group by tgl_buku order by tgl_buku desc",
					'tgl_buku', 'tgl_buku','Semua Tgl. Buku');
  */
    return $str;
}

function comboBySQL($comboName, $sql, $fieldName, $fieldNameTampil, $defStr) {
    //masih salah!!!
    global $HTTP_POST_VARS, $HTTP_GET_VARS, $$comboName;
	//echo "$comboName = ".$$comboName;
//echo $sql;
    $Qry = mysql_query($sql); 
    $ops = "<option value=''>$defStr</option>";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $$comboName == $isi[$fieldName] ? "selected" : "";
        $ops .= "<option $sel value='{$isi[$fieldName]}'>{$isi[$fieldNameTampil]}</option>\n";
    }
    $str = "<select $param name='$comboName' id='$comboName'>$ops</select>";
    return $str;
}
function genComboBoxQry($name='txtField', $value='',$aqry='', $fieldKey='', $fieldValue='', 
	$default='Pilih', $param='') { 
	$qry = mysql_query($aqry);
	$Input = "<option value=''>$default</option>"; 
	while ($Hasil=mysql_fetch_array($qry)) { 
		$Sel = $Hasil[$fieldKey]==$value?"selected":""; 
		$Input .= "<option $Sel value='{$Hasil[$fieldKey]}'>{$Hasil[$fieldValue]}</option>"; 
	} 
	$Input  = "<select $param name='$name' id='$name'>$Input</select>"; return $Input; 
} 
//*
function comboBySQL2($comboName, $comboValue, $sql, $fieldName, $fieldNameTampil, $defStr) {    
    //global $HTTP_POST_VARS, $HTTP_GET_VARS, $$comboName;
    $Qry = mysql_query($sql);
    $ops = "<option value=''>$defStr</option>";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $comboValue == $isi[$fieldName] ? "selected" : "";
        $ops .= "<option $sel value='{$isi[$fieldName]}'>{$isi[$fieldNameTampil]}</option>\n";
    }
    $str = "<select $param name='$comboName' id='$comboName'>$ops</select>";
    return $str;
}



function genComboBoxQry2($name='txtField', $value='',$aqry='', $arrfieldKey='', $fieldValue='', 
	$default='Pilih', $param='') { 
	// keynya bisa banyak
	$qry = mysql_query($aqry);
	$Input = "<option value=''>$default</option>"; 
	while ($Hasil=mysql_fetch_array($qry)) { 
		//$key = $Hasil[$fieldKey];
		$arrkey = array();
		for ($i=0 ; $i<sizeof($arrfieldKey) ; $i++ ) {
			$arrkey[] = $Hasil[$arrfieldKey[$i]];
		}
		$key = join(' ',$arrkey);//.' - '.sizeof($arrfieldKey);
		
		$Sel = $key==$value?"selected":""; 
		$Input .= "<option $Sel value='$key'>{$Hasil[$fieldValue]}</option>"; 
	} 
	$Input  = "<select $param name='$name' id='$name'>$Input</select>"; return $Input; 
}


//*/
function CariCombo($ArField="") {
    global $fmCariComboField, $fmCariComboIsi, $Pg, $SPg;
    $str = "<option></option>";
    for ($x = 0; $x < count($ArField); $x++) {
        $sel = $fmCariComboField == $ArField[$x][0] ? " selected " : "";
        $str .= "<option $sel value='{$ArField[$x][0]}'>{$ArField[$x][1]}</option>";
    }

    $str = "Cari berdasar <select name='fmCariComboField'>$str</select>
			<input type=text name='fmCariComboIsi' value='$fmCariComboIsi'>
			<input type='button' value='Cari' onclick=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">";
    return $str;
}

function CariCombo2($ArField="") {
    global $fmCariComboField, $fmCariComboIsi, $Pg, $SPg;
    $str = "<option>Cari Data</option>";
    for ($x = 0; $x < count($ArField); $x++) {
        $sel = $fmCariComboField == $ArField[$x][0] ? " selected " : "";
        $str .= "<option $sel value='{$ArField[$x][0]}'>{$ArField[$x][1]}</option>";
    }

    $str = "<select name='fmCariComboField' id='fmCariComboField'>$str</select>
			<input type=text id='fmCariComboIsi' name='fmCariComboIsi' value='$fmCariComboIsi'>
			<input type='button' value='Cari' onclick=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">";
    return $str;
}

function CariCombo3($ArField="") {
    global $fmCariComboField, $fmCariComboIsi, $Pg, $SPg;
    $str = "<option>Cari Data</option>";
    for ($x = 0; $x < count($ArField); $x++) {
        $sel = $fmCariComboField == $ArField[$x][0] ? " selected " : "";
        $str .= "<option $sel value='{$ArField[$x][0]}'>{$ArField[$x][1]}</option>";
    }

    $str = "<select name='fmCariComboField'>$str</select>
			<input type=text name='fmCariComboIsi' value='$fmCariComboIsi'>
			<input type='submit' value='Cari' >";
    return $str;
}
function CariCombo4($ArField="",$fmCariComboField , $fmCariComboIsi, 
		$onclick="") {
    global  $Pg, $SPg;
    $str = "<option>Cari Data</option>";
    for ($x = 0; $x < count($ArField); $x++) {
        $sel = $fmCariComboField == $ArField[$x][0] ? " selected " : "";
        $str .= "<option $sel value='{$ArField[$x][0]}'>{$ArField[$x][1]}</option>";
    }

    /*$str = "<select name='fmCariComboField' id='fmCariComboField'>$str</select>
			<input type=text id='fmCariComboIsi' name='fmCariComboIsi' value='$fmCariComboIsi'>
			<input type='button' value='Cari' onclick=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">";
    */
	$str = "<select name='fmCariComboField' id='fmCariComboField'>$str</select>
			<input type=text id='fmCariComboIsi' name='fmCariComboIsi' value='$fmCariComboIsi'>
			<input type='button' value='Cari' onclick=\"$onclick\">";
    
	return $str;
}

function PrintSKPD($xls=''){//$fmSKPD='', $fmUNIT='', $fmSUBUNIT='', $fmSEKSI='') {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, 
			$HTTP_COOKIE_VARS, $SKPD, $UNIT, $SUBUNIT, $fmSEKSI, $SEKSI,$WILAYAH;
    $KEPEMILIKAN = mysql_fetch_array(mysql_query("select nm_pemilik from ref_pemilik where a1='$fmKEPEMILIKAN'"));
    $KEPEMILIKAN = $KEPEMILIKAN[0];
    /* $WILAYAH = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where b='$fmWIL' "));
      $WILAYAH = $WILAYAH[0]; */
	if ($Main->DEF_WILAYAH =='00'){
		$WILAYAH =  '-' ;
	}else{
		$WILAYAH = $Main->NM_WILAYAH ;
	}
//    $xls = ($xls==TRUE) ? '1':$xls;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
    $SKPD = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where d='00' and c='$fmSKPD' "));
    $SKPD = $SKPD[0];
    $UNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d <> '00' and e = '00' and d='$fmUNIT' "));
    $UNIT = $UNIT[0];
    $SUBUNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d = '$fmUNIT' and e <> '00' and e='$fmSUBUNIT' and e1='$kdSubUnit0'"));
    $SUBUNIT = $SUBUNIT[0];
	$SEKSI = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1='$fmSEKSI' and e1<>'00' and e1<>'000' "));
    $SEKSI = $SEKSI[0];
	if ($xls!='1'){
		
	
    $Hsl = "<table cellpadding=0 cellspacing=0 border=0 width='100%'>
			<!-- <tr> <td style='font-weight:bold;font-size:10pt;width:150' >Kepemilikan Barang</td><td style='font-weight:bold;font-size:10pt;width:25' >:</td><td style='font-weight:bold;font-size:10pt' >$KEPEMILIKAN</td> </tr> --> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' width='150'>PROVINSI</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' >". $Main->Provinsi[1]." </td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >KABUPATEN/KOTA</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $WILAYAH</td> </tr> 
			
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >BIDANG</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SKPD</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >SKPD</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $UNIT</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SUBUNIT</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >SUB UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SEKSI</td> </tr> 
			</table>";
	} else {
    $Hsl = "<table cellpadding=0 cellspacing=0 border=0 width='100%'>
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt'>PROVINSI</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' >". $Main->Provinsi[1]." </td> </tr> 
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt' >KABUPATEN/KOTA</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $WILAYAH</td> </tr> 
			
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt' >BIDANG</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td  style='font-weight:bold;font-size:10pt' > $SKPD</td> </tr> 
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt' >SKPD</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td  style='font-weight:bold;font-size:10pt' > $UNIT</td> </tr> 
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt' >UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td  style='font-weight:bold;font-size:10pt' > $SUBUNIT</td> </tr> 
			<tr valign=top> <td colspan='2'  style='font-weight:bold;font-size:10pt' >SUB UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td  style='font-weight:bold;font-size:10pt' > $SEKSI</td> </tr> 
			</table>";		
	}
    return $Hsl;
}


function PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI) {
    //global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, 
	global $Main; 
	//$HTTP_COOKIE_VARS, $SKPD, $UNIT, $SUBUNIT;
	
	$PROVINSI=$Main->Provinsi[1];
    $KEPEMILIKAN = mysql_fetch_array(mysql_query("select nm_pemilik from ref_pemilik where a1='$fmKEPEMILIKAN'"));
    $KEPEMILIKAN = $KEPEMILIKAN[0];
	//$fmSKPD = cekPOST('fmSKPD');
    /* $WILAYAH = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where b='$fmWIL' "));
      $WILAYAH = $WILAYAH[0]; */
    $WILAYAH = $Main->NM_WILAYAH;
    $SKPD = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where d='00' and c='$fmSKPD' "));
    $SKPD = $SKPD[0];
    $UNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d <> '00' and e = '00' and d='$fmUNIT' "));
    $UNIT = $UNIT[0];
    $SUBUNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d = '$fmUNIT' and e <> '00' and e='$fmSUBUNIT' "));
    $SUBUNIT = $SUBUNIT[0];
    $SEKSI = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd  where  c='$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1='$fmSEKSI' and e1<>'00' and e1<>'000' "));
    $SEKSI = $SEKSI[0];

    $Hsl = "<table cellpadding=0 cellspacing=0 border=0 width='100%'>
			<!-- <tr> <td style='font-weight:bold;font-size:10pt;width:150' >Kepemilikan Barang</td><td style='font-weight:bold;font-size:10pt;width:25' >:</td><td style='font-weight:bold;font-size:10pt' >$KEPEMILIKAN</td> </tr> --> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' width='150'>PROVINSI</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' >$PROVINSI</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >KABUPATEN/KOTA</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $WILAYAH</td> </tr> 
			
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >BIDANG</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SKPD</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >SKPD</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $UNIT</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SUBUNIT</td> </tr> 
			<tr valign=top> <td style='font-weight:bold;font-size:10pt' >SUB UNIT</td><td style='width:10;font-weight:bold;font-size:10pt' >:</td><td style='font-weight:bold;font-size:10pt' > $SEKSI</td> </tr> 
			</table>";
    return $Hsl;
}


function PrintTTD($pagewidth = '30cm',$xls=FALSE) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;


    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    $TITIMANGSA = $Main->CETAK_LOKASI.", " . JuyTgl1(date("Y-m-d"));
    if (c == '04') {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd1 = '1' ");
    } else {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '00' and ttd1 = '1' ");
    }
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD1 = $isi['nik'];
        $NAMASKPD1 = $isi['nm_pejabat'];
        $JABATANSKPD1 = $isi['jabatan'];
    }
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
        $JABATANSKPD2 = $isi['jabatan'];
    }
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	if (!$xls)
	{
		
	
    $Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<td width=100>&nbsp;</td> 
				<td align=center width=300><B> <INPUT TYPE=TEXT VALUE='MENGETAHUI' 
						STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 ><BR> 
					<INPUT TYPE=TEXT VALUE='".$Main->KEPALA_OPD."'
						STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >
					<BR><BR><BR><BR><BR><BR>
					<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >
					<br>
					<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50> </td> 
					
					<td width=400>&nbsp;</td> <td align=center width=300>
						<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50><BR> 
						<B><INPUT TYPE=TEXT VALUE='PENGURUS BARANG' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >
						<BR><BR><BR><BR><BR><BR>
						<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >
						<br> 
						<div><INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50> 
					</td> 
						<td width='*'>&nbsp;</td> </tr> </table> ";
	} else {
    $Hsl = "  ";
		
	}
    return $Hsl;
}

function inputFormatRibuan($obj="obj", $params="", $value=0, $ket=' pemisah pecahan dengan titik (mis: 1.5)') {
    global $$obj; //$$obj = round($$obj,0);
    //onKeyPress=\"return isNumberKey(event); \"
	//$str = " <input type=\"text\" name=\"$obj\" id=\"$obj\" value=\"" . $value . "\"
	if ($value == 0){
		$value = $$obj;
	}
    $str = " <input type=\"text\" name=\"$obj\" id=\"$obj\" value=\"" . $value . "\"
			$params
			onKeyDown = \"oldValue=this.value;\" 
			 onKeyPress=\"return isNumberKey(event); \"
			 onkeyup=\"TampilUang('Tampil$obj',this.value);\"
			 
			 /> 
			&nbsp;&nbsp
			<b>
			<span id=\"Tampil$obj\" style='color:red'>&nbsp;</span></b> 
			&nbsp;&nbsp 
				<span style='color:red'>$ket</span>";
    return $str;
}

function inputFormatRibuan2($obj="obj", $value=0, $params="", $ket=' pemisah pecahan dengan titik (mis: 1.5)') {
    //global $$obj;
    $str = " <input type=\"text\" name=\"$obj\" id=\"$obj\" value=\"" . $value . "\"
			$params
			onKeyDown = \"oldValue=this.value;\" 
			onKeyUp=\"
				if(isNaN(this.value)){this.value=oldValue;return;};
				 TampilUang(Tampil$obj,this.value)\" /> 
			&nbsp;&nbsp;
			<b>
			<span id=\"Tampil$obj\" style='color:red'>&nbsp;</span></b> 
			&nbsp;&nbsp 
				<span style='color:red'> $ket</span>";
    return $str;
}

function ComboBarang() {
    global $fmBIDANG, $fmKELOMPOK, $fmSUBKELOMPOK, $fmSUBSUBKELOMPOK,$Main;
	$kdSubsubkel0 = genNumber(0, $Main->SUBSUBKEL_DIGIT );
    $str = "";
    $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i ='$fmSUBSUBKELOMPOK' and j != '$kdSubsubkel0'";
    $NmHEAD = "NAMA BARANG";
    if (!empty($fmBIDANG) and !empty($fmKELOMPOK) and !empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) {
        $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i !='00' and j = '$kdSubsubkel0'";
        $NmHEAD = "NAMA SUB SUB KELOMPOK";
    }
    if (!empty($fmBIDANG) and !empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) {
        $NmHEAD = "NAMA SUB KELOMPOK";
        $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h !='00' and i ='00' and j = '$kdSubsubkel0'";
    }
    if (!empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) {
        $Kondisi = "f = '$fmBIDANG' and g != '00' and h ='00' and i ='00' and j = '$kdSubsubkel0'";
        $NmHEAD = "NAMA KELOMPOK";
    }
    if (empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) {
        $Kondisi = "f != '00' and g = '00' and h ='00' and i ='00' and j = '$kdSubsubkel0'";
        $NmHEAD = "NAMA BIDANG";
    }
    $ListBidang = cmbQuery1("fmBIDANG", $fmBIDANG, "select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$kdSubsubkel0'", "onChange=\"adminForm.submit()\"", 'Pilih', '');
    $ListKelompok = cmbQuery1("fmKELOMPOK", $fmKELOMPOK, "select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$kdSubsubkel0'", "onChange=\"adminForm.submit()\"", 'Pilih', '');
    $ListSubKelompok = cmbQuery1("fmSUBKELOMPOK", $fmSUBKELOMPOK, "select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$kdSubsubkel0'", "onChange=\"adminForm.submit()\"", 'Pilih', '');
    $ListSubSubKelompok = cmbQuery1("fmSUBSUBKELOMPOK", $fmSUBSUBKELOMPOK, "select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$kdSubsubkel0'", "onChange=\"adminForm.submit()\"", 'Pilih', '');
    $str = " <table width=\"100%\" height=\"100%\" class=\"adminform\"> <tr> <td WIDTH='30%'>GOLONGAN</td> <td WIDTH='1%'>:</td> <td WIDTH='89%'>$ListBidang</td> </tr> <tr> <td WIDTH='10%'>BIDANG</td> <td WIDTH='1%'>:</td> <td WIDTH='89%'>$ListKelompok</td> </tr> <td WIDTH='10%'>KELOMPOK</td> <td WIDTH='1%'>:</td> <td WIDTH='89%'>$ListSubKelompok</td> </tr> <td WIDTH='10%'>SUB KELOMPOK</td> <td WIDTH='1%'>:</td> <td WIDTH='89%'>$ListSubSubKelompok</td> </tr> </table>";
    return $str;
}

//v2 -----------------------------------------------------------------------------------------------------------


function selKondisiBrg() {
    $Qry = mysql_query("select * from ref_skpd where c='" . $fmSKPD . "' and d <> '00'  and e = '00' " . $KondisiUNIT . " order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option " . $sel . " value='" . $isi['d'] . "'>" . $isi['nm_skpd'] . "</option>\n";
    }
}

function cmb2D_v2($name='txtField', $valu, $arrList = '', $param='', $def='Pilih', $onchange='') {
    //global $Ref;
    $isi = $valu;
    $Input = "<option value=''>" . $def . "</option>";
    for ($i = 0; $i < count($arrList); $i++) {
        $Sel = $isi == $arrList[$i][0] ? " selected " : "";
        $Input .= "<option " . $Sel . " value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
    }
    $Input = "<select  $param name='$name'  id='$name' " . $onchange . ">$Input</select>";
    //return ' val='.$valu.$Input;
    return $Input;
}

function cmb2D_v3($name='txtField', $valu, $arrList = '', $param='', $def='Pilih', $onchange='') {
    //global $Ref;
    $isi = $valu;
    $Input = "<option value='0'>" . $def . "</option>";
    for ($i = 0; $i < count($arrList); $i++) {
        $Sel = $isi == $arrList[$i][0] ? " selected " : "";
        $Input .= "<option " . $Sel . " value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
    }
    $Input = "<select  $param name='$name'  id='$name' " . $onchange . ">$Input</select>";
    //return ' val='.$valu.$Input;
    return $Input;
}

function WilSKPD1a($doCari) { //create opt cari
    //pilihan bidang,opd,unit tidak tergantung login -> tampil semua
    global $selKondisiBrg, $selStatusBrg;
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTahunPerolehan, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg, $kode_barang, $nm_barang;
    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";

    //$cek.=' optenable = '.$optcarienable;
    /* if ($doCari == 1) {
      $disSKPD = "disabled";
      $disUNIT = "disabled";
      $disSUBUNIT = "disabled";
      $disTAHUN ='disabled';
      $disKondisi = 'disabled';
      }else{ */
    $disSKPD = "";
    $disUNIT = "";
    $disSUBUNIT = "";
    $disTAHUN = '';
    $disKondisi = '';
    //}
    //switch($SPg){		case '04': $disKondisi = 'disabled';}

    if ($doCari != 1) {
        $PilihSKPD = '<option value="">--- Semua Bidang ---</option>';
        $PilihUNIT = "<option value='00'>--- Semua Sub Bidang ---</option>";
        $PilihSUBUNIT = "<option value='00'>--- Semua Sub Unit ---</option>";
        //$disSKPD = 'disabled';
        //Bidang -----------------
        $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
        $Ops = "";
        while ($isi = mysql_fetch_array($Qry)) {
            $sel = $fmSKPD == $isi['c'] ? "selected" : "";
            $Ops .= '<option ' . $sel . ' value="' . $isi['c'] . '">' . $isi['nm_skpd'] . '</option>\n';
        }
        $ListSKPD = '
		<select ' . $disSKPD . ' name="fmSKPD"
			onChange="adminForm.target=\'_self\';adminForm.action=\'?Pg=' . $Pg . '&SPg=' . $SPg . '\';
			adminForm.fmUNIT.value=\'00\';
			adminForm.submit()">' .
                $PilihSKPD . ' ' . $Ops .
                '</select>';

        //OPD -------------
        $Qry = mysql_query("select * from ref_skpd where c='" . $fmSKPD . "' and d <> '00'  and e = '00' " . $KondisiUNIT . " order by d");
        $Ops = "";
        while ($isi = mysql_fetch_array($Qry)) {
            $sel = $fmUNIT == $isi['d'] ? "selected" : "";
            $Ops .= "<option " . $sel . " value='" . $isi['d'] . "'>" . $isi['nm_skpd'] . "</option>\n";
        }
        $ListUNIT =
                "<select " . $disUNIT . " name='fmUNIT'
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=" . $Pg . "&SPg=" . $SPg . "';
			adminForm.fmSUBUNIT.value='00';
			adminForm.submit()\">" .
                $PilihUNIT . " " . $Ops .
                "</select>";

        //Unit --------------------
        $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '" . $fmUNIT . "' and d<>'' and e <> '00' $KondisiSUBUNIT order by e");
        $Ops = "";
        while ($isi = mysql_fetch_array($Qry)) {
            $sel = $fmSUBUNIT == $isi['e'] ? "selected" : "";
            $Ops .= "<option " . $sel . " value='" . $isi['e'] . "'>" . $isi['nm_skpd'] . "</option>\n";
        }
        $ListSUBUNIT = "
		<select $disSUBUNIT name='fmSUBUNIT' 
			onChange=\"adminForm.target='_self';
			adminForm.action='?Pg=" . $Pg . "&SPg=" . $SPg . "';
			adminForm.submit()\">" . $PilihSUBUNIT . " " . $Ops . "</select>";

        $thnPerolehan = TahunPerolehan2($disTAHUN);
        $konBrg = cmb2D_v2('selKondisiBrg', $selKondisiBrg, $Main->KondisiBarang, $disKondisi, 'Semua Kondisi');
        $kdBrg = "<input type='text' name='kode_barang' value='" . $kode_barang . "'>";
        $nmBrg = "<input type='text' name='nm_barang' value='" . $nm_barang . "' style='width:200px;'>";
        $stBrg = cmb2D_v2('selStatusBrg', $selStatusBrg, $Main->StatusBarang, $disStatusBrg, 'Semua Status ');
    } else {
        $ListSKPD = table_get_value("select nm_skpd from ref_skpd where c='" . $fmSKPD . "' and d = '00'  and e = '00' ", "nm_skpd");
        if ($fmSKPD == '00') {
            $ListSKPD = '';
        }
        $ListSKPD = '<b>' . $ListSKPD . ' <input type="hidden" name="fmSKPD" value="' . $fmSKPD . '" > ';

        $ListUNIT = table_get_value("select nm_skpd from ref_skpd where c='" . $fmSKPD . "' and d = '" . $fmUNIT . "'  and e = '00' ", "nm_skpd");
        if ($fmUNIT == '00') {
            $ListUNIT = '';
        }
        $ListUNIT = '<b>' . $ListUNIT . ' <input type="hidden" name="fmUNIT" value="' . $fmUNIT . '" > ';

        $ListSUBUNIT = table_get_value("select nm_skpd from ref_skpd where c='" . $fmSKPD . "' and d = '" . $fmUNIT . "'  and e = '" . $fmSUBUNIT . "' ", "nm_skpd");
        if ($fmSUBUNIT == '00') {
            $ListSUBUNIT = '';
        }
        $ListSUBUNIT = '<b>' . $ListSUBUNIT . ' <input type="hidden" name="fmSUBUNIT" value="' . $fmSUBUNIT . '" > ';

        $thnPerolehan = '<b>' . $fmTahunPerolehan . ' <input type="hidden" name="fmTahunPerolehan" value="' . $fmTahunPerolehan . '" > ';
        $konBrg = '<b>' . $Main->KondisiBarang[$selKondisiBrg - 1][1] . ' <input type="hidden" name="selKondisiBrg" value="' . $selKondisiBrg . '" > ';
        $kdBrg = '<b>' . $kode_barang . ' <input type="hidden" name="kode_barang" value="' . $kode_barang . '" > ';
        $nmBrg = '<b>' . $nm_barang . ' <input type="hidden" name="nm_barang" value="' . $nm_barang . '" > ';
        $stBrg = '<b>' . $Main->StatusBarang[$selStatusBrg - 1][1] . ' <input type="hidden" name="selStatusBrg" value="' . $selStatusBrg . '" > ';
    }


    $Hsl = "
		<input type='hidden' name='doCari' id='doCari' value='" . $doCari . "'>
		<table width=\"100%\" height=\"100%\"  > 
			<tr valign=\"top\"> <td width='100' height='22'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 				
			<tr valign=\"top\"> <td height='24'>ASISTEN / OPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
			<tr valign=\"top\"> <td height='24'>BIRO / UPTD/B</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
					
			<tr> <td height='24'>PROVINSI</td> <td>:</td> <td><b>{$Main->Provinsi[1]}</b></td> </tr> 				
			<tr> <td height='24'>TAHUN</td> <td>:</td> <td><b>" . $thnPerolehan
            . "</b></td> </tr>
			<tr> <td height='24'>KONDISI</td> <td>:</td> <td><b>" .
            $konBrg .
            "</b></td> </tr>
			<tr > <td height='24' >KODE BARANG</td> <td>:</td> <td>" . $kdBrg . " </td> </tr>
			<tr > <td height='24'>NAMA BARANG</td> <td>:</td> <td>" . $nmBrg . "</td> </tr>
			<tr > <td height='24'>STATUS BARANG</td> <td>:</td> 
				<td> " . $stBrg . "  </td> </tr>
			
		</table> 
			
		";

    return $Hsl . $cek;
}

function createOptCariDet($SPg) {
    global $Main, $selHakPakai, $alamat, $kota, $noSert;
    global $merk, $bahan, $noPabrik, $noRangka, $noMesin, $noPolisi, $noBPKB;
    switch ($SPg) {
        case '03': break;
        case '04':
            /* $opt = '<option value="">--- Semua ---</option>';
              for($i= 1; $i<=2; $i++ ){$opt .= '<option value="'.$i.'">'.$arr[$i-1][1].'</option>';} */

            $hsl = '
				<tr><td width="100">Hak Pakai</td><td><td width="10">:</td>  
					<td>' . cmb2D_v2('selHakPakai', $selHakPakai, $Main->StatusHakPakai, '', '--- Semua ---') . '</td>
				</td></tr>
				<tr><td width="100">Alamat</td><td><td width="10">:</td><td><input name="alamat" type="text" value="' . $alamat . '">  </td></tr>
				<tr><td width="100">Kota</td><td><td width="10">:</td><td><input name="kota" type="text" value="' . $kota . '">  </td></tr>
				<tr><td width="100">No. Sertifikat</td><td><td width="10">:</td><td><input name="noSert" type="text" value="' . $noSert . '">  </td></tr>
			';
            break;
        case '05':
            $hsl = '
				<tr><td width="100">Merk</td><td><td width="10">:</td><td> <input name="merk" type="text" value="' . $merk . '"> </td></tr>
				<!--<tr><td width="100">Ukuran</td><td><td width="10">:</td><td><input name="alamat" type="text" value="' . $ukuran . '">  </td></tr>-->
				<tr><td width="100">Bahan</td><td><td width="10">:</td><td><input name="bahan" type="text" value="' . $bahan . '">  </td></tr>
				<tr><td width="100">No. Pabrik</td><td><td width="10">:</td><td><input name="noPabrik" type="text" value="' . $noPabrik . '">  </td></tr>
				<tr><td width="100">No. Rangka</td><td><td width="10">:</td><td><input name="noRangka" type="text" value="' . $noRangka . '">  </td></tr>
				<tr><td width="100">No. Mesin</td><td><td width="10">:</td><td><input name="noMesin" type="text" value="' . $noMesin . '">  </td></tr>
				<tr><td width="100">No. Polisi</td><td><td width="10">:</td><td><input name="noPolisi" type="text" value="' . $noPolisi . '">  </td></tr>
				<tr><td width="100">No. BPKB</td><td><td width="10">:</td><td><input name="noBPKB" type="text" value="' . $noBPKB . '">  </td></tr>
			';
            break;
        case '06':
            $hsl = '
				<tr><td width="100">Konst. Bertingkat</td><td><td width="10">:</td>
					<td>' . cmb2D_v2('konsTingkat', $konsTingkat, $Main->Tingkat, '', '--- Semua ---') . '</td>
				</tr>
				<tr><td width="100">Konst. Beton</td><td><td width="10">:</td>
					<td>' . cmb2D_v2('konsBeton', $konsBeton, $Main->Beton, '', '--- Semua ---') . '</td>
				</tr>
				<tr><td width="100">Alamat</td><td><td width="10">:</td><td><input name="alamat" type="text" value="' . $alamat . '">  </td></tr>
				<tr><td width="100">Kota</td><td><td width="10">:</td><td><input name="kota" type="text" value="' . $kota . '">  </td></tr>
				<tr><td width="100">No. Dokumen</td><td><td width="10">:</td><td><input name="nodokumen" type="text" value="' . $nodokumen . '">  </td></tr>
				<tr><td width="100">No. Kode Tanah</td><td><td width="10">:</td><td><input name="nokodetanah" type="text" value="' . $nokodetanah . '">  </td></tr>
			';
            break;
    }

    $hsl = '<table>' . $hsl . '</table>';

    return $hsl;
}

function create_optcari() {
    $opt->hsl = '
		<table width="100%" class="adminform">
			<tr><td valign="top" style="padding:10px">
				<table width="100%" height="30px"><tr><td align="left">
					<b><!--titlepagecari--></b>
				</td></tr></table>
				<table width="100%"><tr>
					<td width="50%"><!--optcari-->	</td>
					<td> <!--optcaridet--></td>
				</tr></table>
				<table width="100%" >
					<tr><td style="padding:2px;text-align:right;">
						<input type="button" name="btcari" id="btcari" value="Cari Data" 
							onclick="<!--btcari_onclick-->"
							style="height:24px;width:100px; "> 
					</td></tr>
				</table>
			</td></tr>	
		</table>';


    return $opt->cari;
}

function createLookupComboDB($selName, $sqry, $fieldID, $fieldValue, $semua, $selectedValue, $param) {//global
    //$param bisa diisi disabled, onchange, style dll
    $Qry = mysql_query($sqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $selectedValue == $isi[$fieldID] ? "selected" : "";
        $Ops .= "<option $sel value='" . $isi[$fieldID] . "'>" . $isi[$fieldValue] . "</option>\n";
    }
    $selList = "<select $param name='" . $selName . "'><option value=''>" . $semua . "</option>$Ops</select>";
    return $selList;
}

function createLookupCombo($selName, $arrList, $semua, $selectedValue, $param) {//global
    //create combo with array 2 dimensi, kolom 0 = id, kolom 1 = value
    $Ops = "";
    for ($i = 0; $i < count($arrList); $i++) {
        $Sel = $selectedValue == $arrList[$i][0] ? " selected " : "";
        $Input .= "<option " . $Sel . " value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
    }
    $selList = "<select $param name='" . $selName . "'><option value=''>" . $semua . "</option>$Ops</select>";
    return $selList;
}

function formEntryBase($caption, $del, $input, $param1='', $param2='', $param3='') {//global
// buat kerangka entry data, contoh: formEntry('nama',':','<input type="text" name="innama" value="$innama">', 'width=30', 'width=10')
    $hsl = '
		<tr>
		<td ' . $param1 . '>' . $caption . '</td>
		<td ' . $param2 . '>' . $del . '</td>
		<td ' . $param3 . '>' . $input . '</td>
		</tr>
		';
    return $hsl;
}

function formEntryBase2($caption, $del, $input, $param1='', $param2='', $param3='', $paramRow='') {//global
// buat kerangka entry data, contoh: formEntry('nama',':','<input type="text" name="innama" value="$innama">', 'width=30', 'width=10')
    $hsl = '
		<tr ' . $paramRow . '>
		<td ' . $param1 . '>' . $caption . '</td>
		<td ' . $param2 . '>' . $del . '</td>
		<td ' . $param3 . '>' . $input . '</td>
		</tr>
		';
    return $hsl;
}

function formEntryText($name, $caption, $del=':', $value, $param1='', $param2='', $param3='') {//global
    return formEntryBase($caption, $del, '<input type="text" name="' . $name . '" value="' . $value . '" >', $param1, $param2, $param3);
}

function formEntryPass($name, $caption, $del=':', $value, $param1='', $param2='', $param3='') {//global
    return formEntryBase($caption, $del, '<input type="password" name="' . $name . '" value="' . $value . '"  AUTOCOMPLETE="off">', $param1, $param2, $param3);
}

function createDBTableList($elTblName, $tblName, $sField, $sFieldKey, $kondisi, $limit, $sParamField, $paramNo) {
    $paramFields = explode(',', $sParamField);
    $fields = explode(',', $sField);
    $fieldKeys = explode(',', $sFieldKey);
    $fieldCaptions = explode(',', $sFieldCaption);
    $function->onSetValue = '';
    $isWithNo = TRUE;

    //generate list
    $sqry = 'select ' . $sField . ' from ' . $tblName . ' ' . $kondisi . ' ' . $limit;
    $qry = mysql_query($sqry);
    $tblList = '';
    $no = 1;
    while ($rec = mysql_fetch_array($qry)) {
        $clRow = $no % 2 == 0 ? "row1" : "row0";

        $idRec = $elTblName;
        for ($i = 0; $i < sizeof($fieldKeys); $i++) {
            $idRec .= ',' . $rec[$fieldKeys[$i]];
        }

        $tblList .= '<tr id="' . $idRec . '"  class="' . $clRow . '" >';

        if ($isWithNo) {
            $tblList .= '<td ' . $paramNo . '> ' . $no . ' </td>';
        }
        for ($i = 0; $i < sizeof($fields); $i++) {
            $tblList .= '<td  ' . $paramFields[$i] . '> ' . $rec[$fields[$i]] . ' </td>';
        }
        $tblList .= '</tr>';
        $no++;
    }


    return $tblList;
}

function createDBTable($elTblName, $paramsTbl, $sFieldCaption, $sParamHeader, $sParamField, $paramNo, $tblName, $sField, $sFieldKey, $kondisi, $limit) {

    //format idREc = elTblName,id1,..,idn

    $paramHeaders = explode(',', $sParamHeader);
    $fieldCaptions = explode(',', $sFieldCaption);

    //generate header
    $tblHeader = '<tr>';
    for ($i = 0; $i < sizeof($fieldCaptions); $i++) {
        $tblHeader .= '<th ' . $paramHeaders[$i] . '> ' . $fieldCaptions[$i] . ' </th>';
    }
    $tblHeader .= '</tr>';
    $tblList = createDBTableList($elTblName, $tblName, $sField, $sFieldKey, $kondisi, $limit, $sParamField, $paramNo);


    //generate Footer
    $tblFooter = '';

    $hsl = '
		<table id="' . $elTblName . '" name="' . $elTblName . '_head"  ' . $paramsTbl . ' >
		' . $tblHeader . '
		</table>
		
		<table id="' . $elTblName . '" name="' . $elTblName . '_list"  ' . $paramsTbl . ' >
		' . $tblList . '
		' . $tblFooter . '</table>';

    return $hsl;
}

function selKabKota($selName, $selectedValue) {
	global $Main;
    //$selName = 'selKabKota';
    $sqry = "select * from ref_wilayah where a='$Main->DEF_PROPINSI' and b<>'00' order by nm_wilayah";
    $fieldID = 'b';
    $fieldValue = 'nm_wilayah';
    $semua = '--- Kota/Kabupaten ---';
    $param = '';
    $hsl = createLookupComboDB($selName, $sqry, $fieldID, $fieldValue, $semua, $selectedValue, $param);
    return $hsl;
}

function selKabKota2($selName, $selectedValue, $id_propinsi) {
    //$selName = 'selKabKota';
    $sqry = "select * from ref_wilayah where a='" . $id_propinsi . "' and b<>'00' order by nm_wilayah";
    //$cek.= $sqry;

    $fieldID = 'b';
    $fieldValue = 'nm_wilayah';
    $semua = '--- Kota/Kabupaten ---';
    $param = '';
    $hsl = createLookupComboDB($selName, $sqry, $fieldID, $fieldValue, $semua, $selectedValue, $param);
    return $hsl . $cek;
}

function selKabKota3($selName, $selectedValue, $id_propinsi,$param) {
    //$selName = 'selKabKota';
    $sqry = "select * from ref_wilayah where a='" . $id_propinsi . "' and b<>'00' order by nm_wilayah";
    //$cek.= $sqry;

    $fieldID = 'b';
    $fieldValue = 'nm_wilayah';
    $semua = '--- Kota/Kabupaten ---';
    $param = '';
    $hsl = createLookupComboDB($selName, $sqry, $fieldID, $fieldValue, $semua, $selectedValue, $param);
    return $hsl . $cek;
}

function selKecamatan($selName, $selectedValue,$id_kotakab,$param) {
	global $Main;
    //$selName = 'selKecamatan';
    $sqry = "select * from ref_kecamatan where a='$Main->DEF_PROPINSI' and b='$id_kotakab' and kd_kec<>'00' order by nm_kecamatan";
    $fieldID = 'kd_kec';
    $fieldValue = 'nm_kecamatan';
    $semua = '--- Kecamatan ---';
    $param = '';
    $hsl = createLookupComboDB($selName, $sqry, $fieldID, $fieldValue, $semua, $selectedValue, $param);
    return $hsl;
}

/*
function createEntryTgl(
	$elName, 
	$disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', $title='', 
	$fmName = 'adminForm', 
	$Mode=0) 
{
    global $$elName, $Ref; //= 'entryTgl';
    $deftgl = date('Y-m-d'); //'2010-05-05';
    //if ($$elName ==''){	$$elName = $deftgl;	}   //$stgl = $$elName;
    $tgltmp = explode(' ', $$elName); //hilangkan jam jika ada
    $stgl = $tgltmp[0];
    $tgl = explode('-', $stgl);
    if ($tgl[2] == '00') {
        $tgl[2] = '';
    }
    if ($tgl[1] == '00') {
        $tgl[1] = '';
    }
    if ($tgl[0] == '0000') {
        $tgl[0] = '';
    }


    $dis = '';
    if ($disableEntry == '1') {
        $dis = 'disabled';
    }
	
	//$Mode = 1;
	switch ($Mode){
		case 1 :{//tahun tanpa combo
			$entry_thn = 
				'<input ' . $dis . ' type="text" 
					name="' . $elName . '_thn" 
					value="' . $tgl[0] . '" size="4" maxlength="4"
					onkeypress="return isNumberKey(event)"
					onchange="' . $elName . '_createtgl()">';
			break;
		}
		default :{ //tahun combo
			if ($tgl[0]==''){
				$thn =(int)date('Y') ;
			}else{
				$thn = $tgl[0];//(int)date('Y') ;
			}
			$thnaw = $thn-10;
			$thnak = $thn+11;
			$opsi = "<option value=''>Pilih Tahun</option>";
			for ($i=$thnaw; $i<$thnak; $i++){
				$sel = $i == $tgl[0]? "selected='true'" :'';
				$opsi .= "<option $sel value='$i'>$i</option>";	
			}
			$entry_thn = 
				"<select id='" . $elName . "_thn' 
					name='" . $elName . "_thn' 
					onchange='" . $elName . "_createtgl()'
				>
					$opsi
				</select>";
			break;
		}
		
	}
	
	//script js -------------------			
	$script = 
		'<script language="javascript">
			function ' . $elName . '_createtgl(){
				//tgl = adminForm.' . $elName . '_tgl.value;
				tgl = ' . $fmName . '.' . $elName . '_tgl.value;
				if (tgl.length==1){
					tgl ="0"+tgl;
				}
				' . $fmName . '.' . $elName . '.value=
					' . $fmName . '.' . $elName . '_thn.value+"-"+
					' . $fmName . '.' . $elName . '_bln.value+"-"+
					tgl;
			}
			function ' . $elName . '_cleartgl(){
				' . $fmName . '.' . $elName . '_thn.value = "";
				' . $fmName . '.' . $elName . '_bln.value = "";
				' . $fmName . '.' . $elName . '_tgl.value = "";
				' . $fmName . '.' . $elName . '.value = "";
			}			
		</script>	';
	
	//tampil --------------------------
    $hsl = 
		$script.	
		'<div>
		<div style="float:left;padding: 0 4 0 0">' . 
			$title . 
			'<input ' . $dis . ' type="text" name="' . 
				$elName . '_tgl" value="' . $tgl[2] . '" size="2" maxlength="2"
				onkeypress="return isNumberKey(event)"
				onchange="' . $elName . '_createtgl()">
		</div>		
		<div style="float:left;padding: 0 4 0 0">
			' . cmb2D_v2($elName . '_bln', $tgl[1], 
				$Ref->NamaBulan2, 
				$dis, 
				'Pilih Bulan', 
				'onchange="' . $elName . '_createtgl()"') . '
		</div>
		<div style="float:left;padding: 0 4 0 0">'.
			$entry_thn.
		'</div>
		<div style="float:left;padding: 0 4 0 0">
			<input ' . $dis . '  name="' . 
				$elName . '_btClear" type="button" 
				value="Clear" onclick="' . $elName . '_cleartgl()">
				
		</div>
		<div style="float:left;padding: 0 4 0 0">
			&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>
		</div>
		<input $dis type="hidden" name=' . $elName . ' value="' . $$elName . '" >		
		</div>';
    return $hsl;
}
*/
function genNumber($num, $dig=2){
	$tambah = pow(10,$dig);//100000;
	$tmp = ($num + $tambah).'';
	return substr($tmp,1,$dig);
}
function genCombo_tgl($name='cbx_tgl',$value='',$def='Pilih Tanggal', $param=''){
	$tgl_bln_ak = 31;
	$Input = "<option value=''>".$def."</option>"; 
	for($i=1;$i<=$tgl_bln_ak;$i++){
		$Sel = $value==$i?" selected ":""; 
		$Input .= "<option ".$Sel." value='$i'>$i</option>"; 
	}
	$Input  = "<select  $param name='$name'  id='$name' >$Input</select>"; 	
	return $Input; 
} 

function createEntryTgl(	 
	$elName, 
	$Tgl,
	$disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', 
	$fmName = 'adminForm',
	$Mode=0, $withBtnClear = TRUE) 
{
    //requirement : javascript TglEntry_cleartgl(), TglEntry_createtgl(), $ref->namabulan
    global $Ref; //= 'entryTgl';
    $deftgl = date('Y-m-d'); //'2010-05-05';

    $tgltmp = explode(' ', $Tgl); //explode(' ',$$elName); //hilangkan jam jika ada
    $stgl = $tgltmp[0];
    $tgl = explode('-', $stgl);
    if ($tgl[2] == '00') {
        $tgl[2] = '';
    }
    if ($tgl[1] == '00') {
        $tgl[1] = '';
    }
    if ($tgl[0] == '0000') {
        $tgl[0] = '';
    }


    $dis = '';
    if ($disableEntry == '1') {
        $dis = 'disabled';
    }
	
	//$Mode = 1;
	switch ($Mode){
		case 1 :{//tahun tanpa combo			
			$entry_thn =
				'<input ' . $dis . ' type="text" 
					
					name="' . $elName . '_thn" id="' . $elName . '_thn" 
					value="' . $tgl[0] . '" size="1" maxlength="4"
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\'' . $elName . '\')"
				>';
			break;
		}
		default :{ //tahun combo
			if ($tgl[0]==''){
				$thn =(int)date('Y') ;
			}else{
				$thn = $tgl[0];//(int)date('Y') ;
			}
			$thnaw = $thn-10;
			$thnak = $thn+11;
			$opsi = "<option value=''>Tahun</option>";
			for ($i=$thnaw; $i<$thnak; $i++){
				$sel = $i == $tgl[0]? "selected='true'" :'';
				$opsi .= "<option $sel value='$i'>$i</option>";	
			}
			$entry_thn = 
				'<select 					
					id="'. $elName  .'_thn" 
					name="' . $elName . '_thn"'.
					$dis. 
					' onchange="TglEntry_createtgl(\'' . $elName . '\')"
				>'.
					$opsi.
				'</select>';
			break;
		}
		
	}
	
	$ket = $ket == ''? '':
		'<div style="float:left;padding: 0 4 0 0">
			&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>
		</div>';
	$btnclear = $withBtnClear == TRUE ?
		'<div style="float:left;padding: 0 4 0 0">
			<input ' . $dis . '  type="button" value="Clear"
				name="' . $elName . '_btClear" 
				id="' . $elName . '_btClear" 		
				onclick="TglEntry_cleartgl(\'' . $elName . '\')">
				<!--&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>-->
		</div>': '';

    $hsl = '
		<div id="' . $elName . '_content">
		<div  style="float:left;padding: 0 4 0 0">' . 
			$title . 
			/*'<input ' . $dis . ' type="text" name="' . $elName . '_tgl" 
				id="' . $elName . '_tgl" value="' . $tgl[2] . '" size="2" maxlength="2"
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\'' . $elName . '\')">'.*/
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'Tgl', 
				" $dis  style= 'height:20'".'  onchange="TglEntry_createtgl(\'' . $elName . '\')"').
		'</div>		
		<div style="float:left;padding: 0 4 0 0">
			' . cmb2D_v2($elName . '_bln', 
				$tgl[1], 
				$Ref->NamaBulan2, 
				$dis.' style= "height:20" ', 'Pilih Bulan',
                'onchange="TglEntry_createtgl(\'' . $elName . '\')"') . '
		</div>
		<div style="float:left;padding: 0 4 0 0">'.			
			$entry_thn.
		'</div>'.				
		$btnclear.
		$ket.		
		'	<input $dis type="hidden" id=' . $elName . ' name=' . $elName . ' value="' . $Tgl . '" >
		</div>	';
    return $hsl;
}

function createEntryTglBeetwen($elName, $tgl1, $tgl2, $disableEntry='', $ket='', $fmName = 'adminForm', $Mode = 1){

	return
		//"<table><tr><td>".
		"<div style='float:left;height:22;width:490'>".
		createEntryTgl(	 $elName.'_tgl1', $tgl1, $disableEntry, $ket='', '', $fmName , $Mode, TRUE).
		//'</td><td> s/d </td><td>'.
		"<div style='float:left;padding: 4 8 0 4'>s/d</div>".
		createEntryTgl(	 $elName.'_tgl2', $tgl2, $disableEntry, $ket='', '', $fmName , $Mode, TRUE).
		"</div>";
		//"</td></tr></table>";
		
	
}

/*
function createEntryTgl2(
	$elName, 
	$disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', 
	$fmName = 'adminForm') 
{
    //requirement : javascript TglEntry_cleartgl(), TglEntry_createtgl()
	global $$elName, $Ref; //= 'entryTgl';
    $deftgl = date('Y-m-d'); //'2010-05-05';

    $tgltmp = explode(' ', $$elName); //hilangkan jam jika ada
    $stgl = $tgltmp[0];
    $tgl = explode('-', $stgl);
    if ($tgl[2] == '00') {
        $tgl[2] = '';
    }
    if ($tgl[1] == '00') {
        $tgl[1] = '';
    }
    if ($tgl[0] == '0000') {
        $tgl[0] = '';
    }


    $dis = '';
    if ($disableEntry == '1') {
        $dis = 'disabled';
    }


    $hsl = '
		<div id="' . $elName . '_content">
		<div  style="float:left;padding: 0 4 0 0">' . $title . '
			<input ' . $dis . ' type="text" name="' . $elName . '_tgl" id="' . $elName . '_tgl" value="' . $tgl[2] . '" size="2" maxlength="2"
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\'' . $elName . '\')">
		</div>		
		<div style="float:left;padding: 0 4 0 0">
			' . cmb2D_v2($elName . '_bln', $tgl[1], $Ref->NamaBulan2, $dis, 'Pilih Bulan',
                    'onchange="TglEntry_createtgl(\'' . $elName . '\')"') . '
		</div>
		<div style="float:left;padding: 0 4 0 0">
			<input ' . $dis . ' type="text" name="' . $elName . '_thn" id="' . $elName . '_thn" value="' . $tgl[0] . '" size="4" maxlength="4"
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\'' . $elName . '\')">
		</div>
		<div style="float:left;padding: 0 4 0 0">
			<input ' . $dis . '  name="' . $elName . '_btClear" id="' . $elName . '_btClear" type="button" value="Clear"
				onclick="TglEntry_cleartgl(\'' . $elName . '\')">
				&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>
		</div>		
		<input $dis type="hidden" id=' . $elName . ' name=' . $elName . ' value="' . $$elName . '" >
		</div>
		
		';
    return $hsl;
}
*/


/*
  function createEntryTgl2($elName, $disableEntry='', $ket='tanggal bulan tahun (mis: 1 Januari 1998)',
  $title='', $fmName = 'adminForm'){
  global $$elName, $Ref;//= 'entryTgl';
  $deftgl = date( 'Y-m-d' ) ;//'2010-05-05';

  //if ($$elName ==''){	$$elName = $deftgl;	}
  $tgl = explode('-',$$elName);
  $dis='';
  if($disableEntry == '1'){
  $dis = 'disabled';
  }


  $hsl = '
  <script language="javascript">
  function '.$elName.'_createtgl(){
  tgl = '.$fmName.'.'.$elName.'_tgl.value;
  if (tgl.length==1){
  tgl ="0"+tgl;
  }
  '.$fmName.'.'.$elName.'.value=
  '.$fmName.'.'.$elName.'_thn.value+"-"+
  '.$fmName.'.'.$elName.'_bln.value+"-"+
  tgl;


  }
  function '.$elName.'_cleartgl(){
  '.$fmName.'.'.$elName.'_thn.value = "";
  '.$fmName.'.'.$elName.'_bln.value = "";
  '.$fmName.'.'.$elName.'_tgl.value = "";
  '.$fmName.'.'.$elName.'.value = "";
  }

  </script>
  <table>
  <tr>
  <td>'.$title.'<input '.$dis.' type="text" name="'.$elName.'_tgl" value="'.$tgl[2].'" size="2" maxlength="2"
  onkeypress="return isNumberKey(event)"
  onchange="'.$elName.'_createtgl()"
  style="width:20" >

  '.cmb2D_v2($elName.'_bln', $tgl[1], $Ref->NamaBulan2, $dis,'Pilih Bulan','onchange="'.$elName.'_createtgl()"'  ) .'

  <input '.$dis.' type="text" name="'.$elName.'_thn" value="'.$tgl[0].'" size="4" maxlength="4"
  onkeypress="return isNumberKey(event)"
  onchange="'.$elName.'_createtgl()"
  style="width:40"
  >
  <input '.$dis.'  name="'.$elName.'_btClear" type="button" value="Clear" onclick="'.$elName.'_cleartgl()">
  <input type="button" value="Cari">
  &nbsp;&nbsp<span style="color:red;">'.$ket.'</span>
  </td>
  </tr>
  </table>
  <input $dis type="hidden" name='.$elName.' value="'.$$elName.'" >

  ';
  return $hsl;


  }
 */

function createEntryBersertifikat($name='', $nmSertTgl = '', $nmNoSert = '', $formEnt = 'adminForm') {
    global $Main;
    global $$name; //bersertifikat
    global $$nmSertTgl, $$nmNoSert;

    $nmsert_tgl = $nmSertTgl . '_tgl';
    $nmsert_bln = $nmSertTgl . '_bln';
    $nmsert_thn = $nmSertTgl . '_thn';
    $nmsert_btclear = $nmSertTgl . '_btClear';

    //$formEnt = 'adminForm';
    $hsl = 
		"<script language='javascript'>
			function sertifikat_onchange(){
				var thiscbx = document.getElementById('$name');
				var tgl = document.getElementById('$nmsert_tgl');
				var bln = document.getElementById('$nmsert_bln');
				var thn = document.getElementById('$nmsert_thn');
				var clear = document.getElementById('$nmsert_btclear');
				var nosert = document.getElementById('fmNOSERTIFIKAT_KIB_A');
				
				//alert (thiscbx.value);			//var set = thiscbx.value==1? 'true' : 'false';
				if ( thiscbx.value!=1){
					//clear.onclick();
					tgl.setAttribute('disabled','true');
					bln.setAttribute('disabled','true');
					thn.setAttribute('disabled','true');
					clear.setAttribute('disabled','true');	
					nosert.setAttribute('disabled','true');	
				}else{
					tgl.removeAttribute('disabled');
					bln.removeAttribute('disabled');
					thn.removeAttribute('disabled');
					clear.removeAttribute('disabled');
					nosert.removeAttribute('disabled');	
				}
				
				
			}
		</script>
		";
		/*
				//if (' . $formEnt . '.' . $name . '.value == "1"   ){
					//alert(' . $formEnt . '.' . $name . '.value);
					//alert(' . $formEnt . '.' . $nmsert_tgl . '.value);
					' .	$formEnt . '.' . $nmsert_tgl . '.disabled= ' . $formEnt . '.' . $name . '.value != "1" ;
					' . $formEnt . '.' . $nmsert_bln . '.disabled= ' . $formEnt . '.' . $name . '.value != "1" ;
					' . $formEnt . '.' . $nmsert_thn . '.disabled= ' . $formEnt . '.' . $name . '.value != "1" ;
					' . $formEnt . '.' . $nmsert_btclear . '.disabled= ' . $formEnt . '.' . $name . '.value != "1" ;
					
					' . $formEnt . '.' . $nmNoSert . '.disabled= ' . $formEnt . '.' . $name . '.value != "1" ;
				//}
				*/
    $hsl .= formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;Status Sertifikat ', ':',
                    cmb2D_v2($name, $$name, $Main->StatusSertifikat, '', 'Belum Bersertifikat', 'onchange="sertifikat_onchange()"')
                    , '', 'valign="top" height="24"');



    return $hsl;
}

function formEntryKoordinatGPS($nmBrg, $koordinat_gps, $koord_bidang='') {
    //return formEntryBase2('Koordinat GPS',':',




    return '
			<tr valign=top>
				<td style="">Koordinat Lokasi</td><td>:</td>
				
				<td><input style="width:346" type="text" id="koordinat_gps" name="koordinat_gps" value="' . $koordinat_gps . '">
				&nbsp;&nbsp;
				<span style="color: red"> Latitude, Longitude (mis: -6.90207, 107.618332)</span></td>
				
			</tr>
			<tr valign=top>
				<td>Koordinat Bidang Tanah</td><td>:</td>
				<td >
					<textarea cols=60 id="koord_bidang" name="koord_bidang" value="">' . $koord_bidang . '</textarea>
					&nbsp;&nbsp;
					<span style="color: red"> Pemisah Koordinat dengan titik koma (mis: -6.90207,107.618332; -6.90307, 107.628332)</span>
					<br>				
					<table style="width:346">
					<tr><td>
						<div style="float:right">
						<!--<input type="button" value="Google Map" onclick="showMapDlg(\'' . $nmBrg . '\',\'' . $koordinat_gps . '\' )" >-->
						<input type="button" value="Google Map" onclick="showmap2()" >
						</div>
					</td></tr>
					</table>
					<table>
					<tr><td>
						<div id="mapviewlarge"></div>
						<div id="mapContent"> </div>
						' . map_showjs() . '
					</td></tr>
					</table>
				
				</td>
			</tr>
			
			
			
			';
    //		'','width="10"','','valign="top" height="24"');
}

function WilSKPD1_new() {
    //global $DisAbled;
    global $fmSKPD_new, $fmUNIT_new, $fmSUBUNIT_new, $Main, $HTTP_COOKIE_VARS, $Pg, $SPg;
    //$disSKPD = ""; $disUNIT = ""; $disSUBUNIT = "";
    //$disSKPD = $DisAbled; $disUNIT = $DisAbled; $disSUBUNIT = $DisAbled;

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $PilihSKPD = "<option value=''>--- Pilih Bidang ---</option>";
    $PilihUNIT = "<option value='00'>--- Semua Unit Bidang ---</option>";
    $PilihSUBUNIT = "<option value='00'>--- Semua Sub Unit ---</option>";

    //if($HTTP_COOKIE_VARS["coSKPD_new"] !== "00") {
    //	$fmSKPD_new = $HTTP_COOKIE_VARS["coSKPD_new"];
    //	$HTTP_COOKIE_VARS["cofmSKPD_new"]=$fmSKPD_new;
    //	$KondisiSKPD = " and c='$fmSKPD_new'"; $PilihSKPD = "";
    //}
    //if($HTTP_COOKIE_VARS["coUNIT_new"] !== "00") {
    //	$fmUNIT_new = $HTTP_COOKIE_VARS["coUNIT_new"];
    //	$HTTP_COOKIE_VARS["cofmUNIT_new"]=$fmUNIT_new;
    //	$KondisiUNIT = " and d='$fmUNIT_new'"; $PilihUNIT = "";
    //}
    //if($HTTP_COOKIE_VARS["coSUBUNIT_new"] !== "00") {
    //	$fmSUBUNIT_new = $HTTP_COOKIE_VARS["coSUBUNIT_new"];
    //	$HTTP_COOKIE_VARS["cofmSUBUNIT_new"]=$fmSUBUNIT_new;
    //	$KondisiSUBUNIT = " and e='$fmSUBUNIT_new'";
    //	$PilihSUBUNIT = "";
    //}


    $cekskpd .= $KondisiSKPD;
    $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSKPD_new == $isi['c'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSKPD = $cekskpd . "<select $disSKPD name='fmSKPD_new'
		onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmUNIT_new.value='00';adminForm.submit()\">
			$PilihSKPD $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD_new' and d <> '00' and e = '00' $KondisiUNIT order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmUNIT_new == $isi['d'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['d']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListUNIT = "<select $disUNIT name='fmUNIT_new'
		onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.fmSUBUNIT_new.value='00';adminForm.submit()\">
			$PilihUNIT $Ops</select>";

    $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD_new' and d = '$fmUNIT_new' and e <> '00' $KondisiSUBUNIT order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmSUBUNIT_new == $isi['e'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['e']}'>{$isi['nm_skpd']}</option>\n";
    }
    $ListSUBUNIT = "<select $disSUBUNIT name='fmSUBUNIT_new'
		onChange=\"adminForm.target='_self';adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.submit()\">	
			$PilihSUBUNIT $Ops</select>";

    $Hsl = " <table width=\"100%\"   >
				<tr valign=\"top\"> <td width='150'>BIDANG</td><td>:</td>   <td>$ListSKPD</td> </tr> 				
				<tr valign=\"top\"> <td>UNIT BIDANG</td> <td>:</td> <td>$ListUNIT</td> </tr> 
				<tr valign=\"top\"> <td>SUB UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
			</table> 	
			";

    return $Hsl;
}

function ifempty($isi, $emptychr) {
    return!empty($isi) ? $isi : $emptychr;
}

function ifemptyTgl($isi, $emptychr) {
    return!($isi == '00-00-0000') ? $isi : $emptychr;
}

function heading($title, $height) {
    $str = "
	<table class=\"adminheading\">
<tr>
  <th height=\"$height\" class=\"user\">$title </th>
</tr>
</table>";

    return $str;
}

function Penghapusan_daftar_mutasi_OPD() {
    global $fmPenghapusanSKPD, $fmPenghapusanUNIT, $fmPenghapusanSUBUNIT,$fmPenghapusanSEKSI;
    global $Pg, $SPg,$Main;

	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

    $KondisiSKPD = "";
    $KondisiUNIT = "";
    $KondisiSUBUNIT = "";
    $KondisiSEKSI = "";
    $PilihSKPD = '<option value="">--- Semua Bidang ---</option>';
    $PilihUNIT = "<option value=''>--- Semua SKPD ---</option>";
    $PilihSUBUNIT = "<option value=''>--- Semua Unit ---</option>";
    $PilihSEKSI = "<option value=''>--- Semua Sub Unit ---</option>";

//Bidang -----------------	
    $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmPenghapusanSKPD == $isi['c'] ? "selected" : "";
        $Ops .= '<option ' . $sel . ' value="' . $isi['c'] . '">' . $isi['nm_skpd'] . '</option>\n';
    }
    $ListSKPD = '
		<select ' . $disSKPD . ' name="fmPenghapusanSKPD"
			onChange="adminForm.target=\'_self\';adminForm.action=\'?Pg=' . $Pg . '&SPg=' . $SPg . '\';
			adminForm.fmPenghapusanUNIT.value=\'00\';
			adminForm.submit()">' .
            $PilihSKPD . ' ' . $Ops .
            '</select>';

    //OPD -------------
    $Qry = mysql_query("select * from ref_skpd where c='" . $fmPenghapusanSKPD . "' and d <> '00'  and e = '00' " . $KondisiUNIT . " order by d");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmPenghapusanUNIT == $isi['d'] ? "selected" : "";
        $Ops .= "<option " . $sel . " value='" . $isi['d'] . "'>" . $isi['nm_skpd'] . "</option>\n";
    }
    $ListUNIT =
            "<select " . $disUNIT . " name='fmPenghapusanUNIT'
			onChange=\"adminForm.target='_self';adminForm.action='?Pg=" . $Pg . "&SPg=" . $SPg . "';
			adminForm.fmPenghapusanSUBUNIT.value='00';
			adminForm.submit()\">" .
            $PilihUNIT . " " . $Ops .
            "</select>";

    //Unit --------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmPenghapusanSKPD' and d = '" . $fmPenghapusanUNIT . "' and d<>'' and e <> '00' and e1='$kdSubUnit0' $KondisiSUBUNIT  order by e");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmPenghapusanSUBUNIT == $isi['e'] ? "selected" : "";
        $Ops .= "<option " . $sel . " value='" . $isi['e'] . "'>" . $isi['nm_skpd'] . "</option>\n";
    }
    $ListSUBUNIT = "
		<select $disSUBUNIT name='fmPenghapusanSUBUNIT' 
			onChange=\"adminForm.target='_self';
			adminForm.action='?Pg=" . $Pg . "&SPg=" . $SPg . "';
			adminForm.submit()\">" . $PilihSUBUNIT . " " . $Ops . "</select>";


    //Sub Unit --------------------
    $Qry = mysql_query("select * from ref_skpd where c='$fmPenghapusanSKPD' and d = '$fmPenghapusanUNIT' and e='$fmPenghapusanSUBUNIT' and e1 <> '$kdSubUnit0'  $KondisiSEKSI  order by e1");
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmPenghapusanSEKSI == $isi['e1'] ? "selected" : "";
        $Ops .= "<option " . $sel . " value='" . $isi['e1'] . "'>" . $isi['nm_skpd'] . "</option>\n";
    }
    $ListSEKSI = "
		<select $disSEKSI name='fmPenghapusanSEKSI' 
			onChange=\"adminForm.target='_self';
			adminForm.action='?Pg=" . $Pg . "&SPg=" . $SPg . "';
			adminForm.submit()\">" . $PilihSEKSI . " " . $Ops . "</select>";


//set tampil OPD
    return
    "<table width=\"100%\" height=\"100%\"  >
			<tr valign=\"top\"> <td width='100' height='22'>BIDANG</td>   <td width='10'>:</td>   <td>$ListSKPD</td> </tr> 				
			<tr valign=\"top\"> <td height='24'>SKPD</td> <td>:</td> <td>$ListUNIT</td> </tr> 
			<tr valign=\"top\"> <td height='24'>UNIT</td> <td>:</td> <td>$ListSUBUNIT</td> </tr> 
			<tr valign=\"top\"> <td height='24'>SUB UNIT</td> <td>:</td> <td>$ListSEKSI</td> </tr> 

	</table>";
}

function formEntryGambar($elNmGambar="gambar", $elNmGambarOld="gambar_old", $gambarVal, $gambarOldVal, $caption, $del, $param1, $param2, $param3, $paramRow) {
    /* $entry_gambar=
      '<table style= "width:345;border-bottom-color: #CCC;
      border-bottom-style: solid;	border-bottom-width: 1px; border-collapse: separate;
      border-left-color: #CCC; border-left-style: solid; border-left-width: 1px;
      border-right-color: #CCC; border-right-style: solid; border-right-width: 1px;
      border-top-color: #CCC; border-top-style: solid; border-top-width: 1px;"><tr><td>
      <input type="hidden" id="gambar" name="gambar" value="">
      <input type="hidden" id="gambar_old" name="gambar_old" value="'.$gambar_old.'">
      <iframe width="300" height="180" scrolling=auto  id="uploadtarget"
      style="border-width:0;border-style:none"
      name="uploader" src ="lib/upload/uploader.php?nmFile='.$gambar.'" >
      </iframe>
      </td></tr></table>
      '; */
    //echo "<br>gambar val=".$gambarVal;
    $entry_gambar =
            '<table style= "width:345;border-bottom-color: #CCC;
		border-bottom-style: solid;	border-bottom-width: 1px; border-collapse: separate;
		border-left-color: #CCC; border-left-style: solid; border-left-width: 1px;
		border-right-color: #CCC; border-right-style: solid; border-right-width: 1px;
		border-top-color: #CCC; border-top-style: solid; border-top-width: 1px;"><tr><td>
	<input type="hidden" id="' . $elNmGambar . '" name="' . $elNmGambar . '" value="">
	<input type="hidden" id="' . $elNmGambarOld . '" name="' . $elNmGambarOld . '" value="' . $gambarOldVal . '">
	<iframe width="300" height="180" scrolling=auto  id="uploadtarget"  
		style="border-width:0;border-style:none"
		name="uploader" src ="lib/upload/uploader.php?nmFile=' . $gambarVal . '&idCompOut=' . $elNmGambar . '" >
		</iframe>
	</td></tr></table>
	';

    return formEntryBase2('Gambar Barang (Max. 500Kb)', ':', $entry_gambar, '', '', '', 'valign="top" height="24"');
}

function setLastAktif() {
    global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_COOKIE_VARS;

    //cek logn
    if (CekLogin ()) {
        //jika login simpan tgl/jam di last aktif
        mysql_query("update admin set lastaktif= now(), ipaddr='" . $_SERVER['REMOTE_ADDR'] . "' where uid='{$HTTP_COOKIE_VARS['coID']}'");
        //$cek .= '<br> uid='.$HTTP_COOKIE_VARS['coID'];

    }

    //echo '<br> uid='.$HTTP_COOKIE_VARS['coID'];
}

function dokumenlink($nmfile, $nmasli) {

    $str = "<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
			<A style='background: no-repeat url(images/administrator/images/dok_16.png);	
									width:16;height:16;display: inline-block;position:absolute;' 
								title='open " . $nmasli . "'
				href ='download.php?file=" . $nmfile . "&dr=dokum&nm=" . $nmasli . "'>
			</a>
		</div>";
    return $str;
}

function dokumenlink2($nmfile, $nmasli) {

    $str = "
			<A style='color:#3B5998' title='Download " . $nmasli . "'
				href ='download.php?file=" . $nmfile . "&dr=dokum&nm=" . $nmasli . "'>$nmasli
			</a>
		";
    return $str;
}

//rekap BI
function getList_RekapByBrg($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $FiltThn='') {
    global $Main;

    //get kondisi ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
    $Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					and c='$fmSKPD' $KondisiD $KondisiE ";
    if ($fmSKPD == "00") {
        $Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE ";
    }
    $Kondisi .= ' and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5';
    if (!empty($FiltThn)) {
        $Kondisi .= " year(tgl_buku) <= $FiltThn";
    }

    //list --------------------------------------------------------------
    $jmlTotalHargaDisplay = 0;
    $ListData = "";
    $cb = 0;
    $QryRefBarang = mysql_query("select ref.f,ref.g,ref.nm_barang from ref_barang as ref
						where h='00' order by ref.f,ref.g");
    $jmlData = mysql_num_rows($QryRefBarang);
    $TotalHarga = 0;
    $totalBrg = 0;
    $no = $noawal;
    while ($isi = mysql_fetch_array($QryRefBarang)) {

        $Kondisi1 = "concat(f, g)= '{$isi['f']}{$isi['g']}'";
        $sqry = "select sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk
				where $Kondisi1 $Kondisi group by f,g order by f,g";
        $cek .= '<br> qry FG =' . $sqry;
        $QryBarang = mysql_query($sqry);
        $isi1 = mysql_fetch_array($QryBarang);
        $no++;
        $clRow = $no % 2 == 0 ? "row1" : "row0";
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";

        $sqry2 = "select sum(jml_barang) as jml_barang, sum(jml_harga) as jml_harga from buku_induk
				where f='{$isi['f']}' $Kondisi group by f order by f";
        $QryBarangAtas = mysql_fetch_array(mysql_query($sqry2));
        $cek .= '<br> qry F =' . $sqry2;
        $jmlBarangAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_barang'] : $isi1['jml_barang'];
        $jmlBarangAtas = empty($jmlBarangAtas) ? "0" : "" . $jmlBarangAtas . "";

        $jmlBarangAtas = $isi['g'] == "00" ? "<b>" . number_format(($jmlBarangAtas), 0, ',', '.') . "" : "" . number_format(($jmlBarangAtas), 0, ',', '.') . "";

        if ($dlmRibuan) {
            $jmlHargaAtas = $isi['g'] == "00" ? "<b>" . number_format(($QryBarangAtas['jml_harga'] / 1000), 2, ',', '.') . "" : "" . number_format(($isi1['jml_harga'] / 1000), 2, ',', '.') . "";
        } else {
            $jmlHargaAtas = $isi['g'] == "00" ? "<b>" . number_format(($QryBarangAtas['jml_harga']), 2, ',', '.') . "" : "" . number_format(($isi1['jml_harga']), 2, ',', '.') . "";
        }


        $ListData .= "
			<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
			<td class=\"GarisDaftar\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			<td class=\"GarisDaftar\" align=right width=\"$kolomwidth[4]\">$jmlBarangAtas</td>
			<td class=\"GarisDaftar\" align=right width=\"$kolomwidth[5]\">$jmlHargaAtas</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
        </tr>
		";

        $TotalHarga += $isi['g'] == "00" ? $QryBarangAtas['jml_harga'] : 0;
        $totalBrg += $isi['g'] == "00" ? $QryBarangAtas['jml_barang'] : 0; //$isi1['jml_barang'];
        $cb++;
    }

    $tampilTotHarga = $dlmRibuan ? number_format(($TotalHarga / 1000), 2, ',', '.') : number_format(($TotalHarga), 2, ',', '.');
    $ListData .= "
			<tr class='row0'>
			<td colspan=4 class=\"GarisDaftar\">TOTAL</td>
			<td align=right class=\"GarisDaftar\"><b>" . number_format(($totalBrg), 0, ',', '.') . "</td>
			<td align=right class=\"GarisDaftar\"><b>" . $tampilTotHarga . "</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
			</tr>
			";

    //return $ListData;
    //return compact($ListData, $jmlData);
    return array($ListData, $jmlData);
}


function RekapByOPD_GetQuery($tgl, $KondisiKIB) {
global $Main;
$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
	
			
    $sqry = "
		select aa.c as c, aa.d as d, aa.e as e,aa.e1 as e1, aa.nm_skpd,
			bb.jmlBrgHPS, bb.jmlHrgHPS, cc.jmlPLH, cc.jmlHrgPLH, dd.jmlAman, dd.jmlHrgAman, ee.jmlBrgBI, ee.jmlHrgBI,
			ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN,  kk.jmlHrgGR_AMAN, ll.jmlBrgPD, ll.jmlHrgPD	
		from ref_skpd aa 
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(jml_barang) as jmlBrgHPS, sum(jml_harga ) as jmlHrgHPS from v_penghapusan_bi where tgl_penghapusan < '$tgl' $KondisiKIB group by c,d,e,e1 with rollup) bb on aa.c=bb.c and aa.d=bb.d and aa.e=bb.e and aa.e1=bb.e1
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgPD from v_pindahgantirugi_bi where tgl < '$tgl' $KondisiKIB group by c,d,e,e1  with rollup) ll on aa.c=ll.c and aa.d=ll.d and aa.e=ll.e and aa.e1=ll.e1
			
			
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, count(*) as jmlPLH, sum(biaya_pemeliharaan ) as jmlHrgPLH from v_pemelihara where tgl_pemeliharaan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1  with rollup) cc on aa.c=cc.c and aa.d=cc.d and aa.e=cc.e and aa.e1=cc.e1
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, count(*) as jmlAman, sum(biaya_pengamanan ) as jmlHrgAman from v_pengaman where tgl_pengamanan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) dd on aa.c=dd.c and aa.d=dd.d and aa.e=dd.e and aa.e1=dd.e1
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI from buku_induk where tgl_buku < '$tgl' $KondisiKIB group by c,d,e,e1   with rollup) ee on aa.c=ee.c and aa.d=ee.d and aa.e=ee.e and aa.e1=ee.e1			
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH from v2_penghapusan_pelihara where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) ff on aa.c=ff.c and aa.d=ff.d and aa.e=ff.e	 and aa.e1=ff.e1
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(biaya_pengamanan ) as jmlHrgHPS_AMAN from v2_penghapusan_pengaman where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) gg on aa.c=gg.c and aa.d=gg.d and aa.e=gg.e and aa.e1=gg.e1	 
			
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v3_pindah_gantirugi_pelihara where  tgl < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) hh on aa.c=hh.c and aa.d=hh.d and aa.e=hh.e and aa.e1=hh.e1
			
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(biaya_pengamanan ) as jmlHrgPD_AMAN from v2_pindahtangan_pengaman where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) ii on aa.c=ii.c and aa.d=ii.d and aa.e=ii.e	and aa.e1=ii.e1 
			
			left join (select IFNULL(c,'00') as c,IFNULL(d,'00') as d,IFNULL(e,'00') as e,IFNULL(e1,'$kdSubUnit0') as e1, sum(biaya_pengamanan ) as jmlHrgGR_AMAN from v2_gantirugi_pengaman where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e,e1   with rollup) kk on aa.c=kk.c and aa.d=kk.d	and aa.e=kk.e  and aa.e1=kk.e1 
		
		order by aa.c, aa.d, aa.e,aa.e1 ";
 //   echo $sqry;
    return $sqry;
}

function RekapByOPD_GetQuery__($tgl, $KondisiKIB) { //e1
	//left join (select c, d, e, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgGR from v_gantirugi_bi where tgl_gantirugi < '$tgl' $KondisiKIB group by c) mm on aa.c=mm.c			
	//left join (select c, d, e, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v2_pindahtangan_pelihara where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) hh on aa.c=hh.c	 			
	//left join (select c, d, e, sum(biaya_pemeliharaan ) as jmlHrgGR_PLH from v2_gantirugi_pelihara where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c) jj on aa.c=jj.c	 			
			
    $sqry = "
		(select aa.c as c, aa.d as d, aa.e as e, aa.e1, aa.nm_skpd,
			bb.jmlBrgHPS, bb.jmlHrgHPS, cc.jmlPLH, cc.jmlHrgPLH, dd.jmlAman, dd.jmlHrgAman, ee.jmlBrgBI, ee.jmlHrgBI,
			ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN, kk.jmlHrgGR_AMAN, ll.jmlBrgPD, ll.jmlHrgPD 			
		from ref_skpd aa 
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgHPS, sum(jml_harga ) as jmlHrgHPS from v_penghapusan_bi where tgl_penghapusan < '$tgl' $KondisiKIB group by c) bb on aa.c=bb.c			
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgPD from v_pindahgantirugi_bi where tgl < '$tgl' $KondisiKIB group by c) ll on aa.c=ll.c
			
			
			left join (select c, d, e, e1, count(*) as jmlPLH, sum(biaya_pemeliharaan ) as jmlHrgPLH from v_pemelihara where tgl_pemeliharaan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) cc on aa.c=cc.c
			left join (select c, d, e, e1, count(*) as jmlAman, sum(biaya_pengamanan ) as jmlHrgAman from v_pengaman where tgl_pengamanan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) dd on aa.c=dd.c
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI from buku_induk where tgl_buku < '$tgl' 
				
				$KondisiKIB group by c) ee on aa.c=ee.c			
			
			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH from v2_penghapusan_pelihara where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) ff on aa.c=ff.c	 			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgHPS_AMAN from v2_penghapusan_pengaman where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) gg on aa.c=gg.c	 
			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v3_pindah_gantirugi_pelihara where  tgl < '$tgl' and tambah_aset=1 $KondisiKIB group by c) hh on aa.c=hh.c	 			
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgPD_AMAN from v2_pindahtangan_pengaman where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c) ii on aa.c=ii.c	 						
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgGR_AMAN from v2_gantirugi_pengaman where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c) kk on aa.c=kk.c	 
			
			
			
		where aa.d='00' and aa.e='00' 
		)union(
		select aa.c as c, aa.d as d, aa.e as e, aa.e1, aa.nm_skpd,
			bb.jmlBrgHPS, bb.jmlHrgHPS, cc.jmlPLH, cc.jmlHrgPLH, dd.jmlAman, dd.jmlHrgAman, ee.jmlBrgBI, ee.jmlHrgBI,
			ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN, kk.jmlHrgGR_AMAN, ll.jmlBrgPD, ll.jmlHrgPD
		from ref_skpd aa 
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgHPS, sum(jml_harga ) as jmlHrgHPS from v_penghapusan_bi where tgl_penghapusan < '$tgl'  $KondisiKIB group by c,d) bb on aa.c=bb.c and aa.d=bb.d
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgPD from v_pindahgantirugi_bi where tgl < '$tgl' $KondisiKIB group by c,d) ll on aa.c=ll.c and aa.d=ll.d
			
			
			left join (select c, d, e, e1, count(*) as jmlPLH, sum(biaya_pemeliharaan ) as jmlHrgPLH from v_pemelihara where tgl_pemeliharaan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) cc on aa.c=cc.c and aa.d=cc.d
			left join (select c, d, e, e1, count(*) as jmlAman, sum(biaya_pengamanan ) as jmlHrgAman from v_pengaman where tgl_pengamanan < '$tgl' and tambah_aset=1 $KondisiKIB  group by c,d) dd on aa.c=dd.c and aa.d=dd.d
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI from buku_induk where tgl_buku < '$tgl' $KondisiKIB group by c,d) ee on aa.c=ee.c and aa.d=ee.d			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH from v2_penghapusan_pelihara where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) ff on aa.c=ff.c and aa.d=ff.d	 
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgHPS_AMAN from v2_penghapusan_pengaman where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) gg on aa.c=gg.c and aa.d=gg.d	 
			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v3_pindah_gantirugi_pelihara where  tgl < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) hh on aa.c=hh.c and aa.d=hh.d
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgPD_AMAN from v2_pindahtangan_pengaman where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) ii on aa.c=ii.c and aa.d=ii.d	 
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgGR_AMAN from v2_gantirugi_pengaman where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d) kk on aa.c=kk.c	and aa.d=kk.d	  
			
			
		where aa.d <>'00' and aa.e='00' 
		)union(
		select aa.c as c, aa.d as d, aa.e as e, aa.e1, aa.nm_skpd,
			bb.jmlBrgHPS, bb.jmlHrgHPS, cc.jmlPLH, cc.jmlHrgPLH, dd.jmlAman, dd.jmlHrgAman, ee.jmlBrgBI, ee.jmlHrgBI,
			ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN,  kk.jmlHrgGR_AMAN, ll.jmlBrgPD, ll.jmlHrgPD	
		from ref_skpd aa 
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgHPS, sum(jml_harga ) as jmlHrgHPS from v_penghapusan_bi where tgl_penghapusan < '$tgl' $KondisiKIB group by c,d,e) bb on aa.c=bb.c and aa.d=bb.d and aa.e=bb.e
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgPD from v_pindahgantirugi_bi where tgl < '$tgl' $KondisiKIB group by c,d,e) ll on aa.c=ll.c and aa.d=ll.d and aa.e=ll.e
			
			
			left join (select c, d, e, e1, count(*) as jmlPLH, sum(biaya_pemeliharaan ) as jmlHrgPLH from v_pemelihara where tgl_pemeliharaan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) cc on aa.c=cc.c and aa.d=cc.d and aa.e=cc.e
			left join (select c, d, e, e1, count(*) as jmlAman, sum(biaya_pengamanan ) as jmlHrgAman from v_pengaman where tgl_pengamanan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) dd on aa.c=dd.c and aa.d=dd.d and aa.e=dd.e
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI from buku_induk where tgl_buku < '$tgl' $KondisiKIB group by c,d,e) ee on aa.c=ee.c and aa.d=ee.d and aa.e=ee.e			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH from v2_penghapusan_pelihara where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) ff on aa.c=ff.c and aa.d=ff.d and aa.e=ff.e	 
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgHPS_AMAN from v2_penghapusan_pengaman where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) gg on aa.c=gg.c and aa.d=gg.d and aa.e=gg.e	 
			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v3_pindah_gantirugi_pelihara where  tgl < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) hh on aa.c=hh.c and aa.d=hh.d and aa.e=hh.e
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgPD_AMAN from v2_pindahtangan_pengaman where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) ii on aa.c=ii.c and aa.d=ii.d and aa.e=ii.e	 
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgGR_AMAN from v2_gantirugi_pengaman where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) kk on aa.c=kk.c and aa.d=kk.d	and aa.e=kk.e  
			
			
		where aa.c<>'00' and aa.d <>'00' and aa.e<>'00' and aa.e1='000'
		) union(
		select aa.c as c, aa.d as d, aa.e as e, aa.e1, aa.nm_skpd,
			bb.jmlBrgHPS, bb.jmlHrgHPS, cc.jmlPLH, cc.jmlHrgPLH, dd.jmlAman, dd.jmlHrgAman, ee.jmlBrgBI, ee.jmlHrgBI,
			ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN,  kk.jmlHrgGR_AMAN, ll.jmlBrgPD, ll.jmlHrgPD	
		from ref_skpd aa 
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgHPS, sum(jml_harga ) as jmlHrgHPS from v_penghapusan_bi where tgl_penghapusan < '$tgl' $KondisiKIB group by c,d,e) bb on aa.c=bb.c and aa.d=bb.d and aa.e=bb.e
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgPD, sum(jml_harga ) as jmlHrgPD from v_pindahgantirugi_bi where tgl < '$tgl' $KondisiKIB group by c,d,e) ll on aa.c=ll.c and aa.d=ll.d and aa.e=ll.e
			
			
			left join (select c, d, e, e1, count(*) as jmlPLH, sum(biaya_pemeliharaan ) as jmlHrgPLH from v_pemelihara where tgl_pemeliharaan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) cc on aa.c=cc.c and aa.d=cc.d and aa.e=cc.e
			left join (select c, d, e, e1, count(*) as jmlAman, sum(biaya_pengamanan ) as jmlHrgAman from v_pengaman where tgl_pengamanan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) dd on aa.c=dd.c and aa.d=dd.d and aa.e=dd.e
			left join (select c, d, e, e1, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI from buku_induk where tgl_buku < '$tgl' $KondisiKIB group by c,d,e) ee on aa.c=ee.c and aa.d=ee.d and aa.e=ee.e			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH from v2_penghapusan_pelihara where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) ff on aa.c=ff.c and aa.d=ff.d and aa.e=ff.e	 
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgHPS_AMAN from v2_penghapusan_pengaman where  tgl_penghapusan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) gg on aa.c=gg.c and aa.d=gg.d and aa.e=gg.e	 
			
			left join (select c, d, e, e1, sum(biaya_pemeliharaan ) as jmlHrgPD_PLH from v3_pindah_gantirugi_pelihara where  tgl < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) hh on aa.c=hh.c and aa.d=hh.d and aa.e=hh.e
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgPD_AMAN from v2_pindahtangan_pengaman where  tgl_pemindahtanganan < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) ii on aa.c=ii.c and aa.d=ii.d and aa.e=ii.e	 
			
			left join (select c, d, e, e1, sum(biaya_pengamanan ) as jmlHrgGR_AMAN from v2_gantirugi_pengaman where  tgl_gantirugi < '$tgl' and tambah_aset=1 $KondisiKIB group by c,d,e) kk on aa.c=kk.c and aa.d=kk.d	and aa.e=kk.e  
			
			
		where aa.c<>'00' and aa.d <>'00' and aa.e<>'00' and aa.e1<>'000'
		)
		order by c, d, e, e1
		";

    return $sqry;
}



function RekapByOPD_TampilJmldanHarga_($e1, $isRibuan, $cltd, $jmlBrg, $jmlHrg) {
global $Main;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    $sjmlBrg = number_format($jmlBrg, 0, ',', '.');
    $sjmlBrg = $e1 == $kdSubUnit0 ? "<b>$sjmlBrg</b>" : $sjmlBrg;
    $sjmlHrg = $isRibuan ?
            number_format($jmlHrg / 1000, 2, ',', '.') :
            number_format($jmlHrg, 2, ',', '.');
    $sjmlHrg = $e1 == $kdSubUnit0 ? "<b>$sjmlHrg</b>" : $sjmlHrg;
    return "
		<td class='$cltd' align='right' >$sjmlBrg</td>			
		<td class='$cltd' align='right' >$sjmlHrg</td>";
}

function RekapByOPD_TampilJmldanHarga($isi, $isRibuan, $cltd) {
	//ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN, kk.jmlHrgGR_AMAN, 
	//ll.jmlBrgPD, ll.jmlHrgPD 			
	
    $jmlBrg = $isi['jmlBrgBI'] - $isi['jmlBrgHPS']- $isi['jmlBrgPD']; //$jmlHrg = ($isi['jmlHrgBI']) - $isi['jmlHrgHPS'];
    $jmlHrg = ($isi['jmlHrgPLH'] + $isi['jmlHrgAman'] + $isi['jmlHrgBI']) 
		- $isi['jmlHrgHPS'] - $isi['jmlHrgPD']  
		- $isi['jmlHrgPD_PLH'] - $isi['jmlHrgPD_AMAN'] - $isi['jmlHrgGR_AMAN']
		;
    $tampilJmlHarga = RekapByOPD_TampilJmldanHarga_($isi['e1'], $isRibuan, $cltd, $jmlBrg, $jmlHrg);
    /* $sjmlBrg = number_format($jmlBrg, 0, ',', '.') ;
      $sjmlBrg = $isi['e']=='00' ? "<b>$sjmlBrg</b>" : $sjmlBrg;
      $sjmlHrg = $isRibuan?
      number_format($jmlHrg/1000, 2, ',', '.') :
      number_format($jmlHrg, 2, ',', '.') ;
      $sjmlHrg = $isi['e']=='00' ? "<b>$sjmlHrg</b>" : $sjmlHrg;
      $tampilJmlHarga = "
      <td class='$cltd' align='right' >$sjmlBrg</td>
      <td class='$cltd' align='right' >$sjmlHrg</td>"; */
    return array($jmlBrg, $jmlHrg, $tampilJmlHarga);
}


function RekapByOPD_CariTotal($c='00', $d='00', $e='00', $e1='000', $kib='', $tgl){
	$JmlBrg = 0; $JmlHrg = 0;
	 //kondisi kib
	 switch ($kib) {
        //case '': $KondisiKIB = ''; $kib='';       break;
        case '01': $KondisiKIB = ' f="01" '; $kib='01';  break;
        case '02': $KondisiKIB = ' f="02" '; $kib='02'; break;
        case '03': $KondisiKIB = ' f="03" '; $kib='03'; break;
        case '04': $KondisiKIB = ' f="04" '; $kib='04'; break;
        case '05': $KondisiKIB = ' f="05" '; $kib='05'; break;
        case '06': $KondisiKIB = ' f="06" '; $kib='06'; break;
		case '07': $KondisiKIB = ' f="07" '; $kib='07'; break;
        default: $KondisiKIB = ''; $kib=''; break;
    }
	//kondisi skpd
	$arr = array();
	$arr[] = "tgl_buku<='$tgl' ";
	if($c !='00' ) $arr[] =  "c='$c'" ;
	if($d !='00') $arr[] = "d='$d'";
	if($e !='00') $arr[] = "e='$e'" ;
	if($e1 !='000' && $e1 !='00') $arr[] = "e1='$e1'" ;
	if($KondisiKIB !='' ) $arr[] = $KondisiKIB ;
	$Kondisi = join(' and ', $arr);
	
	//penatausaha
	$aqry = "select count(*) as cnt, sum(jml_harga) as jml from buku_induk where $Kondisi "	; //echo $aqry;
	$qry = mysql_fetch_array(mysql_query($aqry));
	$JmlBrgBI = $qry['cnt'];
	$JmlHrgBI = $qry['jml'];
	//pemelihara
	//pengamanan	
	//penghapusan		
	//pindah tangan
	//ganti rugi
	//pengahapusan/ganti rugi/pindhtangan pelihara
	//pengahapusan/ganti rugi/pindhtangan pengaman
	
	
		
	
	$JmlBrg = ($JmlBrgBI )- $JmlBrgHps - $JmlBrgGR - $JmlBrgPT ;
	$JmlHrg = ($JmlHrgBI + $JmlHrgPlh + $JmlHrgAmn) - $JmlHrgHps - $JmlHrgGR - $JmlHrgPT - $JmlHrgHpsPlh - $JmlHrgHpsAmn;
	return array('JmlBrg'=>$JmlBrg, 'JmlHrg'=>$JmlHrg);
}




function getList_RekapByOPD2($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=TRUE, $tgl='2012-1-1' ) {
    global $Main, $cek;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    //global $tglAwal, $tglAkhir;
    //$tgl ='2012-1-1';
    //kondisi kib -----------------------------------
    //$tblName = 'v_rekap_bi2'; //default
    switch ($SPg) {
        case '03': $KondisiKIB = '';
            break;
        case '04': $KondisiKIB = ' and  f="01" ';
            break;
        case '05': $KondisiKIB = ' and f="02" ';
            break;
        case '06': $KondisiKIB = ' and f="03" ';
            break;
        case '07': $KondisiKIB = ' and f="04" ';
            break;
        case '08': $KondisiKIB = ' and f="05" ';
            break;
        case '09': $KondisiKIB = ' and f="06" ';
            break;
        case '10': $KondisiKIB = '';
            break;
    }

    //list ------------------------------------------------------------------------
    //get resource table
    if ($SPg != '10') {
        $sqry = RekapByOPD_GetQuery($tgl, $KondisiKIB);
        $qry = mysql_query($sqry . ' ' . $LimitHal);
    } else {
        $sqry = RekapByOPD_GetQuery($tgl, ' and f="01"'); //kib a
        $qry = mysql_query($sqry . ' ' . $LimitHal);
        $sqry2 = RekapByOPD_GetQuery($tgl, ' and f="02"'); //kib b
        $qry2 = mysql_query($sqry2 . ' ' . $LimitHal);
        $sqry3 = RekapByOPD_GetQuery($tgl, ' and f="03"'); //kib c
        $qry3 = mysql_query($sqry3 . ' ' . $LimitHal);
        $sqry4 = RekapByOPD_GetQuery($tgl, ' and f="04"'); //kib d
        $qry4 = mysql_query($sqry4 . ' ' . $LimitHal);
        $sqry5 = RekapByOPD_GetQuery($tgl, ' and f="05"'); //kib e
        $qry5 = mysql_query($sqry5 . ' ' . $LimitHal);
        $sqry6 = RekapByOPD_GetQuery($tgl, ' and f="06"'); //kib f
        $qry6 = mysql_query($sqry6 . ' ' . $LimitHal);
    }
    $ListData = '';
    $no = $noawal + 1;
    if ($isCetak) {
        $cltd = 'GarisCetak';
    } else {
        $cltd = 'GarisDaftar';
    }
	//echo $sqry;
    while ($isi = mysql_fetch_array($qry)) {
		
		
		
		
        $clRow = $no % 2 == 0 ? "row1" : "row0"; //get css row
        if ($isCetak) {
            $clRow = '';
        }
        $no++;
        $sID = $isi['c'] . '.' . $isi['d'] . '.' . $isi['e']. '.' . $isi['e1'];
        //uraian -----------------
        if ($isi['c'] != '00' and $isi['d'] == '00') {//bid
            $uraian = $isi['c'] . '. ' . strtoupper($isi['nm_skpd']);
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else if ($isi['d'] != '00' and $isi['e'] == '00') {//opd
            $uraian = $isi['d'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:22;' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:22;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else if ($isi['e'] != '00' and $isi['e1'] == $kdSubUnit0) {//opd
            $uraian = $isi['e'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:44;' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:44;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else {
            $uraian = $isi['e1'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:66;' id='$sID'>$uraian</td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:66;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	$uraian</td>";
        }

        //tampil kolom jml dan harga -----------
        list($jmlBrg, $jmlHrg, $tampil_jmlharga) = RekapByOPD_TampilJmldanHarga($isi, $isRibuan, $cltd);
        if ($SPg == '10') {
            $i = $no - 2;
            if (!mysql_data_seek($qry2, $i)) {
                echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry3, $i)) {
                echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry4, $i)) {
                echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry5, $i)) {
                echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry6, $i)) {
                echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            $isi2 = mysql_fetch_assoc($qry2);
            $isi3 = mysql_fetch_assoc($qry3);
            $isi4 = mysql_fetch_assoc($qry4);
            $isi5 = mysql_fetch_assoc($qry5);
            $isi6 = mysql_fetch_assoc($qry6);
            list($jmlBrg2, $jmlHrg2, $tampil_jmlharga2) = RekapByOPD_TampilJmldanHarga($isi2, $isRibuan, $cltd);
            list($jmlBrg3, $jmlHrg3, $tampil_jmlharga3) = RekapByOPD_TampilJmldanHarga($isi3, $isRibuan, $cltd);
            list($jmlBrg4, $jmlHrg4, $tampil_jmlharga4) = RekapByOPD_TampilJmldanHarga($isi4, $isRibuan, $cltd);
            list($jmlBrg5, $jmlHrg5, $tampil_jmlharga5) = RekapByOPD_TampilJmldanHarga($isi5, $isRibuan, $cltd);
            list($jmlBrg6, $jmlHrg6, $tampil_jmlharga6) = RekapByOPD_TampilJmldanHarga($isi6, $isRibuan, $cltd);
            $jmlBrgBI = $jmlBrg + $jmlBrg2 + $jmlBrg3 + $jmlBrg4 + $jmlBrg5 + $jmlBrg6;
            $jmlHrgBI = $jmlHrg + $jmlHrg2 + $jmlHrg3 + $jmlHrg4 + $jmlHrg5 + $jmlHrg6;
            $tampil_jmlhargaBI = RekapByOPD_TampilJmldanHarga_($isi['e1'], $isRibuan, $cltd, $jmlBrgBI, $jmlHrgBI);
        }
        //tampil baris ----------------
        $ListData .=
                "<tr class='$clRow'>
			<td class='$cltd' align='center' >$no.</td>" .
                $uraian .
                $tampil_jmlhargaBI .
                $tampil_jmlharga .
                $tampil_jmlharga2 .
                $tampil_jmlharga3 .
                $tampil_jmlharga4 .
                $tampil_jmlharga5 .
                $tampil_jmlharga6 .
                "</tr>";
    }
    
	//cari total & jml data----------------------------------------------------
    $qry = mysql_query($sqry);
    if ($SPg == '10') {
        $qry2 = mysql_query($sqry2);
        $qry3 = mysql_query($sqry3);
        $qry4 = mysql_query($sqry4);
        $qry5 = mysql_query($sqry5);
        $qry6 = mysql_query($sqry6);
    }
    $jmlData = mysql_num_rows($qry);
    if ($noawal == 0) {
        $i = 0;
        while ($isi = mysql_fetch_array($qry)) {
            if ($isi['c'] != '00' and $isi['d'] == '00') {
                $totBrgHPS += $isi['jmlBrgHPS'] + $isi['jmlBrgPD']; 
                $totHrgHPS += $isi['jmlHrgHPS'] + $isi['jmlHrgPD'];
                $totHrgPLH += $isi['jmlHrgPLH'] + $isi['jmlHrgPD_PLH'];
                $totHrgAman += $isi['jmlHrgAman'] + $isi['jmlHrgPD_AMAN'] + $isi['jmlHrgGR_AMAN'];
                $totBrgBI += $isi['jmlBrgBI'];
                $totHrgBI += $isi['jmlHrgBI'];				
                if ($SPg == '10') {
                    if (!mysql_data_seek($qry2, $i)) {
                        echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry3, $i)) {
                        echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry4, $i)) {
                        echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry5, $i)) {
                        echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry6, $i)) {
                        echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    $isi2 = mysql_fetch_assoc($qry2);
                    $isi3 = mysql_fetch_assoc($qry3);
                    $isi4 = mysql_fetch_assoc($qry4);
                    $isi5 = mysql_fetch_assoc($qry5);
                    $isi6 = mysql_fetch_assoc($qry6);
                    //B
                    $totBrgHPS2 += $isi2['jmlBrgHPS'] + $isi2['jmlBrgPD']; 
                	$totHrgHPS2 += $isi2['jmlHrgHPS'] + $isi2['jmlHrgPD'];
                	$totHrgPLH2 += $isi2['jmlHrgPLH'] + $isi2['jmlHrgPD_PLH'];
                	$totHrgAman2 += $isi2['jmlHrgAman'] + $isi2['jmlHrgPD_AMAN'] + $isi2['jmlHrgGR_AMAN'];
                    $totBrgBI2 += $isi2['jmlBrgBI'];
                    $totHrgBI2 += $isi2['jmlHrgBI'];
                    //C
                    $totBrgHPS3 += $isi3['jmlBrgHPS'] + $isi3['jmlBrgPD']; 
                	$totHrgHPS3 += $isi3['jmlHrgHPS'] + $isi3['jmlHrgPD'];
                	$totHrgPLH3 += $isi3['jmlHrgPLH'] + $isi3['jmlHrgPD_PLH'];
                	$totHrgAman3 += $isi3['jmlHrgAman'] + $isi3['jmlHrgPD_AMAN'] + $isi3['jmlHrgGR_AMAN'];
                    $totBrgBI3 += $isi3['jmlBrgBI'];
                    $totHrgBI3 += $isi3['jmlHrgBI'];
                    //D
                    $totBrgHPS4 += $isi4['jmlBrgHPS'] + $isi4['jmlBrgPD']; 
                	$totHrgHPS4 += $isi4['jmlHrgHPS'] + $isi4['jmlHrgPD'];
                	$totHrgPLH4 += $isi4['jmlHrgPLH'] + $isi4['jmlHrgPD_PLH'];
                	$totHrgAman4 += $isi4['jmlHrgAman'] + $isi4['jmlHrgPD_AMAN'] + $isi4['jmlHrgGR_AMAN'];
                    $totBrgBI4 += $isi4['jmlBrgBI'];
                    $totHrgBI4 += $isi4['jmlHrgBI'];
                    //D
					$totBrgHPS5 += $isi5['jmlBrgHPS'] + $isi5['jmlBrgPD']; 
                	$totHrgHPS5 += $isi5['jmlHrgHPS'] + $isi5['jmlHrgPD'];
                	$totHrgPLH5 += $isi5['jmlHrgPLH'] + $isi5['jmlHrgPD_PLH'];
                	$totHrgAman5 += $isi5['jmlHrgAman'] + $isi5['jmlHrgPD_AMAN'] + $isi5['jmlHrgGR_AMAN'];                    
                    $totBrgBI5 += $isi5['jmlBrgBI'];
                    $totHrgBI5 += $isi5['jmlHrgBI'];
                    //E
                    $totBrgHPS6 += $isi6['jmlBrgHPS'] + $isi6['jmlBrgPD']; 
                	$totHrgHPS6 += $isi6['jmlHrgHPS'] + $isi6['jmlHrgPD'];
                	$totHrgPLH6 += $isi6['jmlHrgPLH'] + $isi6['jmlHrgPD_PLH'];
                	$totHrgAman6 += $isi6['jmlHrgAman'] + $isi6['jmlHrgPD_AMAN'] + $isi6['jmlHrgGR_AMAN'];
                    $totBrgBI6 += $isi6['jmlBrgBI'];
                    $totHrgBI6 += $isi6['jmlHrgBI'];
                }
            }
            $i++;
        }
        $totBrg = $totBrgBI - $totBrgHPS;
        $totHrg = ($totHrgPLH + $totHrgAman + $totHrgBI) - $totHrgHPS;
        if ($SPg == '10') {
            $totBrg2 = $totBrgBI2 - $totBrgHPS2;
            $totHrg2 = ($totHrgPLH2 + $totHrgAman2 + $totHrgBI2) - $totHrgHPS2;
            $totBrg3 = $totBrgBI3 - $totBrgHPS3;
            $totHrg3 = ($totHrgPLH3 + $totHrgAman3 + $totHrgBI3) - $totHrgHPS3;
            $totBrg4 = $totBrgBI4 - $totBrgHPS4;
            $totHrg4 = ($totHrgPLH4 + $totHrgAman4 + $totHrgBI4) - $totHrgHPS4;
            $totBrg5 = $totBrgBI5 - $totBrgHPS5;
            $totHrg5 = ($totHrgPLH5 + $totHrgAman5 + $totHrgBI5) - $totHrgHPS5;
            $totBrg6 = $totBrgBI6 - $totBrgHPS6;
            $totHrg6 = ($totHrgPLH6 + $totHrgAman6 + $totHrgBI6) - $totHrgHPS6;
            $totBrgBI = $totBrg + $totBrg2 + $totBrg3 + $totBrg4 + $totBrg5 + $totBrg6; //$totBrgBI - $totBrgHPSBI;
            $totHrgBI = $totHrg + $totHrg2 + $totHrg3 + $totHrg4 + $totHrg5 + $totHrg6; //($totHrgPLHBI + $totHrgAmanBI + $totHrgBI) - $totHrgHPSBI;
        }
        //tampil tot
        $tampilTot_jmlharga = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg, $totHrg);
        if ($SPg == '10') {
            $tampilTot_jmlharga2 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg2, $totHrg2);
            $tampilTot_jmlharga3 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg3, $totHrg3);
            $tampilTot_jmlharga4 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg4, $totHrg4);
            $tampilTot_jmlharga5 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg5, $totHrg5);
            $tampilTot_jmlharga6 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg6, $totHrg6);
            $tampilTot_jmlhargaBI = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrgBI, $totHrgBI);
        }
        //$no=;
        $sID = $row['c'] . '.' . $row['d'] . '.' . $row['e']. '.' . $row['e1'];
        $uraian = "<td class='$cltd' colspan=3><b>$Main->NM_WILAYAH2<b></td>";
        $ListData =
                "<tr class='$clRow'>
		<td class='$cltd' align='center' >1.</td>		
		$uraian
		$tampilTot_jmlhargaBI
		$tampilTot_jmlharga
		$tampilTot_jmlharga2
		$tampilTot_jmlharga3
		$tampilTot_jmlharga4
		$tampilTot_jmlharga5
		$tampilTot_jmlharga6
		<!--
		<td class='$cltd' align='right' ><b>$stotBrg</b></td>			
		<td class='$cltd' align='right' ><b>$stotHrg</b></td>-->			
		</tr>" . $ListData;
    }



    return array($ListData, $jmlData);
}


function getList_RekapByOPD2_($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=TRUE, $tgl='2012-1-1' ) {
    global $Main, $cek;
    //global $tglAwal, $tglAkhir;
    //$tgl ='2012-1-1';
    //kondisi kib -----------------------------------
    //$tblName = 'v_rekap_bi2'; //default
    switch ($SPg) {
        case '03': $KondisiKIB = '';
            break;
        case '04': $KondisiKIB = ' and  f="01" ';
            break;
        case '05': $KondisiKIB = ' and f="02" ';
            break;
        case '06': $KondisiKIB = ' and f="03" ';
            break;
        case '07': $KondisiKIB = ' and f="04" ';
            break;
        case '08': $KondisiKIB = ' and f="05" ';
            break;
        case '09': $KondisiKIB = ' and f="06" ';
            break;
        case '10': $KondisiKIB = '';
            break;
    }

    //list ------------------------------------------------------------------------
    //get resource table
    if ($SPg != '10') {
        $sqry = RekapByOPD_GetQuery($tgl, $KondisiKIB);
        $qry = mysql_query($sqry . ' ' . $LimitHal);
    } else {
        $sqry = RekapByOPD_GetQuery($tgl, ' and f="01"'); //kib a
        $qry = mysql_query($sqry . ' ' . $LimitHal);
        $sqry2 = RekapByOPD_GetQuery($tgl, ' and f="02"'); //kib b
        $qry2 = mysql_query($sqry2 . ' ' . $LimitHal);
        $sqry3 = RekapByOPD_GetQuery($tgl, ' and f="03"'); //kib c
        $qry3 = mysql_query($sqry3 . ' ' . $LimitHal);
        $sqry4 = RekapByOPD_GetQuery($tgl, ' and f="04"'); //kib d
        $qry4 = mysql_query($sqry4 . ' ' . $LimitHal);
        $sqry5 = RekapByOPD_GetQuery($tgl, ' and f="05"'); //kib e
        $qry5 = mysql_query($sqry5 . ' ' . $LimitHal);
        $sqry6 = RekapByOPD_GetQuery($tgl, ' and f="06"'); //kib f
        $qry6 = mysql_query($sqry6 . ' ' . $LimitHal);
    }
    $ListData = '';
    $no = $noawal + 1;
    if ($isCetak) {
        $cltd = 'GarisCetak';
    } else {
        $cltd = 'GarisDaftar';
    }
	//echo $sqry;
    while ($isi = mysql_fetch_array($qry)) {
		
		
		
		
        $clRow = $no % 2 == 0 ? "row1" : "row0"; //get css row
        if ($isCetak) {
            $clRow = '';
        }
        $no++;
        $sID = $isi['c'] . '.' . $isi['d'] . '.' . $isi['e'];
        //uraian -----------------
        if ($isi['c'] != '00' and $isi['d'] == '00') {//bid
            $uraian = $isi['c'] . '. ' . strtoupper($isi['nm_skpd']);
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else if ($isi['d'] != '00' and $isi['e'] == '00') {//opd
            $uraian = $isi['d'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:22;' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:22;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else {
            $uraian = $isi['e'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:44;' id='$sID'>$uraian</td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:44;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	$uraian</td>";
        }

        //tampil kolom jml dan harga -----------
        list($jmlBrg, $jmlHrg, $tampil_jmlharga) = RekapByOPD_TampilJmldanHarga($isi, $isRibuan, $cltd);
        if ($SPg == '10') {
            $i = $no - 2;
            if (!mysql_data_seek($qry2, $i)) {
                echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry3, $i)) {
                echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry4, $i)) {
                echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry5, $i)) {
                echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry6, $i)) {
                echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            $isi2 = mysql_fetch_assoc($qry2);
            $isi3 = mysql_fetch_assoc($qry3);
            $isi4 = mysql_fetch_assoc($qry4);
            $isi5 = mysql_fetch_assoc($qry5);
            $isi6 = mysql_fetch_assoc($qry6);
            list($jmlBrg2, $jmlHrg2, $tampil_jmlharga2) = RekapByOPD_TampilJmldanHarga($isi2, $isRibuan, $cltd);
            list($jmlBrg3, $jmlHrg3, $tampil_jmlharga3) = RekapByOPD_TampilJmldanHarga($isi3, $isRibuan, $cltd);
            list($jmlBrg4, $jmlHrg4, $tampil_jmlharga4) = RekapByOPD_TampilJmldanHarga($isi4, $isRibuan, $cltd);
            list($jmlBrg5, $jmlHrg5, $tampil_jmlharga5) = RekapByOPD_TampilJmldanHarga($isi5, $isRibuan, $cltd);
            list($jmlBrg6, $jmlHrg6, $tampil_jmlharga6) = RekapByOPD_TampilJmldanHarga($isi6, $isRibuan, $cltd);
            $jmlBrgBI = $jmlBrg + $jmlBrg2 + $jmlBrg3 + $jmlBrg4 + $jmlBrg5 + $jmlBrg6;
            $jmlHrgBI = $jmlHrg + $jmlHrg2 + $jmlHrg3 + $jmlHrg4 + $jmlHrg5 + $jmlHrg6;
            $tampil_jmlhargaBI = RekapByOPD_TampilJmldanHarga_($isi['e'], $isRibuan, $cltd, $jmlBrgBI, $jmlHrgBI);
        }
        //tampil baris ----------------
        $ListData .=
                "<tr class='$clRow'>
			<td class='$cltd' align='center' >$no.</td>" .
                $uraian .
                $tampil_jmlhargaBI .
                $tampil_jmlharga .
                $tampil_jmlharga2 .
                $tampil_jmlharga3 .
                $tampil_jmlharga4 .
                $tampil_jmlharga5 .
                $tampil_jmlharga6 .
                "</tr>";
    }
    //cari total & jml data----------------------------------------------------
    $qry = mysql_query($sqry);
    if ($SPg == '10') {
        $qry2 = mysql_query($sqry2);
        $qry3 = mysql_query($sqry3);
        $qry4 = mysql_query($sqry4);
        $qry5 = mysql_query($sqry5);
        $qry6 = mysql_query($sqry6);
    }
    $jmlData = mysql_num_rows($qry);
    if ($noawal == 0) {
        $i = 0;
        while ($isi = mysql_fetch_array($qry)) {
            if ($isi['c'] != '00' and $isi['d'] == '00') {
                $totBrgHPS += $isi['jmlBrgHPS'];
                $totHrgHPS += $isi['jmlHrgHPS'];
                $totHrgPLH += $isi['jmlHrgPLH'];
                $totHrgAman += $isi['jmlHrgAman'];
                $totBrgBI += $isi['jmlBrgBI'];
                $totHrgBI += $isi['jmlHrgBI'];
                if ($SPg == '10') {
                    if (!mysql_data_seek($qry2, $i)) {
                        echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry3, $i)) {
                        echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry4, $i)) {
                        echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry5, $i)) {
                        echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry6, $i)) {
                        echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    $isi2 = mysql_fetch_assoc($qry2);
                    $isi3 = mysql_fetch_assoc($qry3);
                    $isi4 = mysql_fetch_assoc($qry4);
                    $isi5 = mysql_fetch_assoc($qry5);
                    $isi6 = mysql_fetch_assoc($qry6);
                    //B
                    $totBrgHPS2 += $isi2['jmlBrgHPS'];
                    $totHrgHPS2 += $isi2['jmlHrgHPS'];
                    $totHrgPLH2 += $isi2['jmlHrgPLH'];
                    $totHrgAman2 += $isi2['jmlHrgAman'];
                    $totBrgBI2 += $isi2['jmlBrgBI'];
                    $totHrgBI2 += $isi2['jmlHrgBI'];
                    //C
                    $totBrgHPS3 += $isi3['jmlBrgHPS'];
                    $totHrgHPS3 += $isi3['jmlHrgHPS'];
                    $totHrgPLH3 += $isi3['jmlHrgPLH'];
                    $totHrgAman3 += $isi3['jmlHrgAman'];
                    $totBrgBI3 += $isi3['jmlBrgBI'];
                    $totHrgBI3 += $isi3['jmlHrgBI'];
                    //D
                    $totBrgHPS4 += $isi4['jmlBrgHPS'];
                    $totHrgHPS4 += $isi4['jmlHrgHPS'];
                    $totHrgPLH4 += $isi4['jmlHrgPLH'];
                    $totHrgAman4 += $isi4['jmlHrgAman'];
                    $totBrgBI4 += $isi4['jmlBrgBI'];
                    $totHrgBI4 += $isi4['jmlHrgBI'];
                    //D
                    $totBrgHPS5 += $isi5['jmlBrgHPS'];
                    $totHrgHPS5 += $isi5['jmlHrgHPS'];
                    $totHrgPLH5 += $isi5['jmlHrgPLH'];
                    $totHrgAman5 += $isi5['jmlHrgAman'];
                    $totBrgBI5 += $isi5['jmlBrgBI'];
                    $totHrgBI5 += $isi5['jmlHrgBI'];
                    //E
                    $totBrgHPS6 += $isi6['jmlBrgHPS'];
                    $totHrgHPS6 += $isi6['jmlHrgHPS'];
                    $totHrgPLH6 += $isi6['jmlHrgPLH'];
                    $totHrgAman6 += $isi6['jmlHrgAman'];
                    $totBrgBI6 += $isi6['jmlBrgBI'];
                    $totHrgBI6 += $isi6['jmlHrgBI'];
                }
            }
            $i++;
        }
        $totBrg = $totBrgBI - $totBrgHPS;
        $totHrg = ($totHrgPLH + $totHrgAman + $totHrgBI) - $totHrgHPS;
        if ($SPg == '10') {
            $totBrg2 = $totBrgBI2 - $totBrgHPS2;
            $totHrg2 = ($totHrgPLH2 + $totHrgAman2 + $totHrgBI2) - $totHrgHPS2;
            $totBrg3 = $totBrgBI3 - $totBrgHPS3;
            $totHrg3 = ($totHrgPLH3 + $totHrgAman3 + $totHrgBI3) - $totHrgHPS3;
            $totBrg4 = $totBrgBI4 - $totBrgHPS4;
            $totHrg4 = ($totHrgPLH4 + $totHrgAman4 + $totHrgBI4) - $totHrgHPS4;
            $totBrg5 = $totBrgBI5 - $totBrgHPS5;
            $totHrg5 = ($totHrgPLH5 + $totHrgAman5 + $totHrgBI5) - $totHrgHPS5;
            $totBrg6 = $totBrgBI6 - $totBrgHPS6;
            $totHrg6 = ($totHrgPLH6 + $totHrgAman6 + $totHrgBI6) - $totHrgHPS6;
            $totBrgBI = $totBrg + $totBrg2 + $totBrg3 + $totBrg4 + $totBrg5 + $totBrg6; //$totBrgBI - $totBrgHPSBI;
            $totHrgBI = $totHrg + $totHrg2 + $totHrg3 + $totHrg4 + $totHrg5 + $totHrg6; //($totHrgPLHBI + $totHrgAmanBI + $totHrgBI) - $totHrgHPSBI;
        }
        //tampil tot
        $tampilTot_jmlharga = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg, $totHrg);
        if ($SPg == '10') {
            $tampilTot_jmlharga2 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg2, $totHrg2);
            $tampilTot_jmlharga3 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg3, $totHrg3);
            $tampilTot_jmlharga4 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg4, $totHrg4);
            $tampilTot_jmlharga5 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg5, $totHrg5);
            $tampilTot_jmlharga6 = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrg6, $totHrg6);
            $tampilTot_jmlhargaBI = RekapByOPD_TampilJmldanHarga_('00', $isRibuan, $cltd, $totBrgBI, $totHrgBI);
        }
        //$no=;
        $sID = $row['c'] . '.' . $row['d'] . '.' . $row['e'];
        $uraian = "<td class='$cltd' colspan=3><b>".$Main->NM_WILAYAH2."<b></td>";
        $ListData =
                "<tr class='$clRow'>
		<td class='$cltd' align='center' >1.</td>		
		$uraian
		$tampilTot_jmlhargaBI
		$tampilTot_jmlharga
		$tampilTot_jmlharga2
		$tampilTot_jmlharga3
		$tampilTot_jmlharga4
		$tampilTot_jmlharga5
		$tampilTot_jmlharga6
		<!--
		<td class='$cltd' align='right' ><b>$stotBrg</b></td>			
		<td class='$cltd' align='right' ><b>$stotHrg</b></td>-->			
		</tr>" . $ListData;
    }



    return array($ListData, $jmlData);
}


function getList_RekapByOPD($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=TRUE) {
    global $Main, $cek;

    //pilih buku_nduk atau kib -----------------------------------
    $tblName = 'v_rekap_bi2'; //default
    switch ($SPg) {
        case '03': $tblName = 'v_rekap_bi2';
            break;
        case '04': $tblName = 'v_rekap_kiba2';
            break;
        case '05': $tblName = 'v_rekap_kibb2';
            break;
        case '06': $tblName = 'v_rekap_kibc2';
            break;
        case '07': $tblName = 'v_rekap_kibd2';
            break;
        case '08': $tblName = 'v_rekap_kibe2';
            break;
        case '09': $tblName = 'v_rekap_kibf2';
            break;
        case '10': $tblName = 'v_rekap_all2';
            break;
    }

    //$cek = ' <br> SPg = '.$SPg;
    $sqry = ' select * from ' . $tblName; //$cek .= ' sqry= '.$sqry.' '.$LimitHal.'<br>';
    $qry = mysql_query($sqry);
    $jmlData = mysql_num_rows($qry);

    $qry = mysql_query($sqry . ' ' . $LimitHal);
    $ListData = '';
    $no = $noawal; //$Main->PagePerHal * (($HalDefault*1) - 1);
    //$rekap->totPerHal= array('bi'=>0, 'kiba'=>0, 'kibb'=>0, 'kibc'=>0, 'kibd'=>0, 'kibe'=>0, 'kibf'=>0);

    if ($isCetak) {
        $cltd = 'GarisCetak';
    } else {
        $cltd = 'GarisDaftar';
    }

    //cari total atas ------------------------------------
    if ($no == 0) {
        $no = 1; //$clRow = $no % 2 == 0 ?"row1":"row0";//get css row
        $uraian = '<td class="' . $cltd . '" colspan=3><b>PEMERINTAH PROPINSI JAWA BARAT<b></td>';
        $totbi = table_get_value('select sum(jml) as tot from ' . $tblName, 'tot');
        $stotbi = $isRibuan ? '<b>' . number_format($totbi / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($totbi, 2, ',', '.') . '</b>';
        $totbrg = table_get_value('select sum(jmlbrg) as tot from ' . $tblName, 'tot');
        $stotbrg = '<b>' . number_format($totbrg, 0, ',', '.') . '</b>';
        if ($SPg == 10) {
            $tot = table_get_value('select sum(jmlkiba) as tot from ' . $tblName, 'tot');
            $stotkiba = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            $tot = table_get_value('select sum(jmlkibb) as tot from ' . $tblName, 'tot');
            $stotkibb = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            $tot = table_get_value('select sum(jmlkibc) as tot from ' . $tblName, 'tot');
            $stotkibc = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            $tot = table_get_value('select sum(jmlkibd) as tot from ' . $tblName, 'tot');
            $stotkibd = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            $tot = table_get_value('select sum(jmlkibe) as tot from ' . $tblName, 'tot');
            $stotkibe = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            $tot = table_get_value('select sum(jmlkibf) as tot from ' . $tblName, 'tot');
            $stotkibf = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
        }

        if ($SPg != 10) {
            $ListData =
                    '<tr class="' . $clRow . '">
			<td class="' . $cltd . '" align="center" >' . $no . '.</td>
			' . $uraian . '
			<td class="' . $cltd . '" align="right" >' . $stotbrg . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotbi . '</td>
			</tr>';
        } else {
            $ListData =
                    '<tr class="' . $clRow . '">
			<td class="' . $cltd . '" align="center" >' . $no . '.</td>
			' . $uraian . '
			<td class="' . $cltd . '" align="right" >' . $stotbi . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkiba . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkibb . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkibc . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkibd . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkibe . '</td>
			<td class="' . $cltd . '" align="right" >' . $stotkibf . '</td>
			</tr>';
        }
    } else {
        $no++;
    }

    //create list -------------------------------------
    while ($row = mysql_fetch_array($qry)) {
        $clRow = $no % 2 == 0 ? "row1" : "row0"; //get css row
        if ($isCetak) {
            $clRow = '';
        }

        $no++;
        $sID = $row['c'] . '.' . $row['d'] . '.' . $row['e'];

        if ($row['c'] == '') {//total
            $uraian = '<td class="' . $cltd . '" colspan=3><b>TOTAL<b></td>';
            $totbi = table_get_value('select sum(jml) as tot from ' . $tblName, 'tot');
            $totbrg = table_get_value('select sum(jmlbrg) as tot from ' . $tblName, 'tot');
            $stotbi = $isRibuan ? '<b>' . number_format($totbi / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($totbi, 2, ',', '.') . '</b>';
            $stotbrg = '<b>' . number_format($totbrg, 0, ',', '.') . '</b>';
            if ($SPg == 10) {
                $tot = table_get_value('select sum(jmlkiba) as tot from ' . $tblName, 'tot');
                $stotkiba = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibb) as tot from ' . $tblName, 'tot');
                $stotkibb = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibc) as tot from ' . $tblName, 'tot');
                $stotkibc = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibd) as tot from ' . $tblName, 'tot');
                $stotkibd = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibe) as tot from ' . $tblName, 'tot');
                $stotkibe = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibf) as tot from ' . $tblName, 'tot');
                $stotkibf = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            }
        } elseif ($row['c'] != '00' and $row['d'] == '00') {//tot bidang
            if ($isCetak) {
                $uraian = '<td class="' . $cltd . '" colspan=3 style = ""
						id="' . $sID . '"
					><b>' . $row['c'] . '. ' . strtoupper($row['nm_skpd']) . '</b></td>';
            } else {
                $uraian = '<td class="' . $cltd . '" colspan=3 style = "cursor:pointer"
						id="' . $sID . '"
						onclick="getRkpBrg(event)" 
						title="Klik untuk lihat rekap barang"
					><b>' . $row['c'] . '. ' . strtoupper($row['nm_skpd']) . '</b></td>';
            }
            $totbi = table_get_value('select sum(jml) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
            $totbrg = table_get_value('select sum(jmlbrg) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
            $stotbi = $isRibuan ? '<b>' . number_format($totbi / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($totbi, 2, ',', '.') . '</b>';
            $stotbrg = '<b>' . number_format($totbrg, 0, ',', '.') . '</b>';
            if ($SPg == 10) {
                $tot = table_get_value('select sum(jmlkiba) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkiba = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibb) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkibb = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibc) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkibc = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibd) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkibd = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibe) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkibe = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibf) as tot from ' . $tblName . ' where c="' . $row['c'] . '"', 'tot');
                $stotkibf = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            }
        } elseif ($row['d'] != '00' and $row['e'] == '00') {//tot OPD
            if ($isCetak) {
                $uraian =
                        '<td class="' . $cltd . '" colspan=3 style="padding-left:22;"
						id="' . $sID . '"
					><b>' . $row['d'] . '. ' . ($row['nm_skpd']) . '</b></td>';
            } else {
                $uraian =
                        '<td class="' . $cltd . '" colspan=3 style="padding-left:22;cursor:pointer"
						id="' . $sID . '"
						onclick="getRkpBrg(event)"  
						title="Klik untuk lihat rekap barang"
					><b>' . $row['d'] . '. ' . ($row['nm_skpd']) . '</b></td>';
            }
            $totbi = table_get_value('select sum(jml) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
            $totbrg = table_get_value('select sum(jmlbrg) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
            $stotbi = $isRibuan ? '<b>' . number_format($totbi / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($totbi, 2, ',', '.') . '</b>';
            $stotbrg = '<b>' . number_format($totbrg, 0, ',', '.') . '</b>';
            if ($SPg == 10) {
                $tot = table_get_value('select sum(jmlkiba) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
                $stotkiba = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibb) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
                $stotkibb = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibc) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
                $stotkibc = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibd) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"' . $row['c'] . '"', 'tot');
                $stotkibd = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibe) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
                $stotkibe = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = table_get_value('select sum(jmlkibf) as tot from ' . $tblName . ' where c="' . $row['c'] . '" and d="' . $row['d'] . '"', 'tot');
                $stotkibf = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            }
        } elseif ($row['e'] != '00') {//tot unit
            if ($isCetak) {
                $uraian = '
					<td class="' . $cltd . '" colspan=3 style="padding-left:44;"
						id="' . $sID . '"
					>' . $row['e'] . '. ' . $row['nm_skpd'] . '</td>';
            } else {
                $uraian = '
					<td class="' . $cltd . '" colspan=3 style="padding-left:44;cursor:pointer"
						id="' . $sID . '"
						onclick="getRkpBrg(event)" 
						title="Klik untuk lihat rekap barang"
					>' . $row['e'] . '. ' . $row['nm_skpd'] . '</td>';
            }

            $totbi = $row['jml'];
            $totbrg = $row['jmlbrg'];
            $stotbi = $isRibuan ? number_format($totbi / 1000, 2, ',', '.') : number_format($totbi, 2, ',', '.');
            $stotbrg = number_format($totbrg, 0, ',', '.');
            if ($SPg == 10) {
                $tot = $row['jmlkiba'];
                $stotkiba = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = $row['jmlkibb'];
                $stotkibb = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = $row['jmlkibc'];
                $stotkibc = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = $row['jmlkibd'];
                $stotkibd = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = $row['jmlkibe'];
                $stotkibe = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
                $tot = $row['jmlkibf'];
                $stotkibf = $isRibuan ? '<b>' . number_format($tot / 1000, 2, ',', '.') . '</b>' : '<b>' . number_format($tot, 2, ',', '.') . '</b>';
            }
        } else {

        }


        if ($SPg != 10) {
            $ListData .=
                    '<tr class="' . $clRow . '">
				<td class="' . $cltd . '" align="center" >' . $no . '.</td>
				' . $uraian . '
				<td class="' . $cltd . '" align="right" >' . $stotbrg . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotbi . '</td>
				</tr>';
        } else {
            $ListData .=
                    '<tr class="' . $clRow . '">
				<td class="' . $cltd . '" align="center" >' . $no . '.</td>
				' . $uraian . '
				<td class="' . $cltd . '" align="right" >' . $stotbi . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkiba . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkibb . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkibc . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkibd . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkibe . '</td>
				<td class="' . $cltd . '" align="right" >' . $stotkibf . '</td>
				
			</tr>';
        }
    }


    //------ create html total harga -----------
    /*
      $ListData .= '

      <!--footer table -->
      <tr><td colspan=11 >&nbsp</td></tr>
      <tr>
      <td colspan=15 align=center height="50">'.$BarisPerHalaman.'&nbsp;&nbsp'.Halaman($jmlData,$Main->PagePerHal,'HalDefault').'</td>
      </tr>
      '.$cek; */

    return array($ListData, $jmlData);
}


function number_format_ribuan($val=0, $dlmRibuan=FALSE){
	return $dlmRibuan == TRUE ? number_format(($val / 1000), 2, ',', '.') : number_format($val, 2, ',', '.');
        
}
function addbold($str='', $isbold=FALSE){
	 return  $isbold ? "<b>" . $str . "" : $str;        
}
function Mutasi_RekapByBrg_GetList2($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT,  
	$noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $cetak=FALSE, 
	$Style=1, $fmSEKSI='00', $fmKONDBRG='7') {
    // ------------------------------
    // $Style=1 = total penambahan, 2= pemelihara, pemgaman, peroleh, 3 = saldo akhir sampai dgn tglakhir
    // ------------------------------
    global $Main;
    global $tglAwal, $tglAkhir; //$fmSemester, $fmTahun;

	$cek='';

    $clGaris = $cetak == TRUE ? "GarisCetak" : "GarisDaftar";
    //get kondisi (skpd) ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI =='' || $fmSEKSI == "000" || $fmSEKSI == "00"  ? "" : " and e1='$fmSEKSI' ";
    $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					and c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 ";
    if ($fmSKPD == "00") {
        $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE $KondisiE1";
    }
	
	//kondisi barang
	switch($fmKONDBRG){
		case '1' :case '2': case '3': case '4' :{
			$Kondisi .= $Kondisi==''? " kondisi = '$fmKONDBRG' ": " and kondisi = '$fmKONDBRG' ";
			break;
		}
		/*default : {
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}*/
		case '5' :{
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}
	}

    //list --------------------------------------------------------------
    $ListData = "";
    $cb = 0;
    $no = $noawal;
	
	/*
	#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f and aa.g=jj.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f and aa.g=kk.g 			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f and aa.g=ll.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f and aa.g=mm.g 
			
	*/
    $sqry = "
		
		select aa.f, aa.g,  aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir,
			nn.jmlHSBG_awal, nn.jmlHrgHSBG_awal,
			oo.jmlHSBG_akhir, oo.jmlHrgHSBG_akhir,
			pp.jmlHrgHPS_HSBG_awal,
			qq.jmlHrgHPS_HSBG_akhir
			
			 ".		
		"from ref_barang aa 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and tgl_buku <= '$tglAkhir'  group by f,g with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_awal, sum(harga_hapus) as jmlHrgHSBG_awal from v_hapus_sebagian where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) nn on aa.f=nn.f and aa.g=nn.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_akhir, sum(harga_hapus ) as jmlHrgHSBG_akhir from v_hapus_sebagian where $Kondisi  and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) oo on aa.f=oo.f and aa.g=oo.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus ) as jmlHrgHPS_HSBG_awal from v2_penghapusan_hapussebagian where  tgl_penghapusan < '$tglAwal' $KondisiKIB group by f,g with rollup ) pp on aa.f=pp.f  and aa.g=pp.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus) as jmlHrgHPS_HSBG_akhir from v2_penghapusan_hapussebagian where  tgl_penghapusan <= '$tglAkhir'  $KondisiKIB group by f,g with rollup ) qq on aa.f=qq.f  and aa.g=qq.g  

			".			
		" where aa.h='00'  
		order by aa.f, aa.g		
	"; // echo "$sqry";
	// $cek.=$sqry;
    $QryRefBarang = mysql_query($sqry);    
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;    
	$no=0;
    while ($isi = mysql_fetch_array($QryRefBarang)) {
        //get kondisi1 (barang) ----------------------------------
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
        $KondisiBi = " status_barang<>3 ";
		$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
		$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";

        //data --------------------------------------------------
		//penghapusan
        $jmlBrgHPS_awal = $isi['jmlBrgHPS_awal'];
		$jmlHrgHPS_awal = $isi['jmlHrgHPS_awal'];		
		$jmlBrgHPS_akhir = $isi['jmlBrgHPS_akhir'];
		$jmlHrgHPS_akhir = $isi['jmlHrgHPS_akhir'];		
		$jmlBrgHPS_curr = $jmlBrgHPS_akhir - $jmlBrgHPS_awal;
		$jmlHrgHPS_curr = $jmlHrgHPS_akhir - $jmlHrgHPS_awal;
		//buku_induk
//        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
		$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
		$jmlBrgBI_curr = $jmlBrgBI_akhir - $jmlBrgBI_awal;
		$jmlHrgBI_awal = $isi['jmlHrgBI_awal'];		       
		$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
		$jmlHrgBI_curr = $jmlHrgBI_akhir - $jmlHrgBI_awal;		
		//pemelihara
        $jmlHrgPLH_awal = $isi['jmlHrgPLH_awal'];		
        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
		$jmlHrgPLH_curr = $jmlHrgPLH_akhir - $jmlHrgPLH_awal;		
		//pengaman
        $jmlHrgAman_awal = $isi['jmlHrgAman_awal'];
		$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
        $jmlHrgAman_curr = $jmlHrgAman_akhir - $jmlHrgAman_awal;
		//hapus sebagian
        $jmlHrgHSBG_awal = $isi['jmlHrgHSBG_awal'];		
        $jmlHrgHSBG_akhir = $isi['jmlHrgHSBG_akhir'];
		$jmlHrgHSBG_curr = $jmlHrgHSBG_akhir - $jmlHrgHSBG_awal;		
		
		//hapus pelihara
		$jmlHrgHPS_PLH_awal = $isi['jmlHrgHPS_PLH_awal'];   
		$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
		$jmlHrgHPS_PLH_curr = $jmlHrgHPS_PLH_akhir - $jmlHrgHPS_PLH_awal;
		//hapus pengaman
		$jmlHrgHPS_Aman_awal = $isi['jmlHrgHPS_Aman_awal']; 		   		
		$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
		$jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal;
		//hapus hapus sebagian
		$jmlHrgHPS_HSBG_awal = $isi['jmlHrgHPS_HSBG_awal'];   
		$jmlHrgHPS_HSBG_akhir = $isi['jmlHrgHPS_HSBG_akhir'];   
		$jmlHrgHPS_HSBG_curr = $jmlHrgHPS_HSBG_akhir - $jmlHrgHPS_HSBG_awal;
		
		//mutasi pelihara
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_PLH_akhir = $get['sumbiaya'];
		$jmlHrgMut_PLH_curr = $jmlHrgMut_PLH_akhir - $jmlHrgMut_PLH_awal;
		//mutasi pengaman
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_Aman_awal = $get['sumbiaya'];	
		$get= mysql_fetch_array( mysql_query(			
			"select f, sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		 
		$jmlHrgMut_Aman_akhir = $get['sumbiaya']; 	  
		$jmlHrgMut_Aman_curr = $jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal;	

		//mutasi hapus sebagian
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian
			where $Kondisi and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian 
			where $Kondisi and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_HSBG_akhir = $get['sumbiaya'];
		$jmlHrgMut_HSBG_curr = $jmlHrgMut_HSBG_akhir - $jmlHrgMut_HSBG_awal;
			
		//pindahtangan ----------------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_awal = $get['sumbrg'];
		$jmlHrgPindah_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_akhir = $get['sumbrg'];
		$jmlHrgPindah_akhir = $get['sumbiaya'];		
		$jmlHrgPindah_curr = $jmlHrgPindah_akhir - $jmlHrgPindah_awal;
		//pindahtangan pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_PLH_curr = $jmlHrgPindah_PLH_akhir - $jmlHrgPindah_PLH_awal;	
		//pindahtangan pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_akhir = $get['sumbiaya'];
		$jmlHrgPindah_Aman_curr = $jmlHrgPindah_Aman_akhir - $jmlHrgPindah_Aman_awal;	
		//pindahtangan hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and  tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_HSBG_curr = $jmlHrgPindah_HSBG_akhir - $jmlHrgPindah_HSBG_awal;	
		
		//gantirugi --------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi
			where $Kondisi and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_awal = $get['sumbrg'];
		$jmlHrgGantirugi_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi 
			where $Kondisi and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_akhir = $get['sumbrg'];
		$jmlHrgGantirugi_akhir = $get['sumbiaya'];		
		$jmlHrgGantirugi_curr = $jmlHrgGantirugi_akhir - $jmlHrgGantirugi_awal;
		//Gantirugi pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_PLH_curr = $jmlHrgGantirugi_PLH_akhir - $jmlHrgGantirugi_PLH_awal;	
		//Gantirugi pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_akhir = $get['sumbiaya'];
		$jmlHrgGantirugi_Aman_curr = $jmlHrgGantirugi_Aman_akhir - $jmlHrgGantirugi_Aman_awal;

		//Gantirugi hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_HSBG_curr = $jmlHrgGantirugi_HSBG_akhir - $jmlHrgGantirugi_HSBG_awal;	
			
		
        //hitung row --------------------------------------------------------------------------        
        //saldo awal
		$jmlBrg_awal = $jmlBrgBI_awal - ($jmlBrgHPS_awal + $jmlBrgPindah_awal + $jmlBrgGantirugi_awal);        
		$jmlHrg_awal = 
			($jmlHrgBI_awal + $jmlHrgPLH_awal + $jmlHrgAman_awal +  $jmlHrgMut_PLH_awal+ $jmlHrgMut_Aman_awal) - 
			($jmlHrgHPS_awal + $jmlHrgHPS_PLH_awal + $jmlHrgHPS_Aman_awal + 
			$jmlHrgPindah_awal + $jmlHrgPindah_PLH_awal + $jmlHrgPindah_Aman_awal +
			$jmlHrgGantirugi_awal + $jmlHrgGantirugi_PLH_awal + $jmlHrgGantirugi_Aman_awal 
			); 
        //bertambah
		$jmlBrgTambah_curr = $jmlBrgBI_curr;						
		$jmlHrgTambahBi_curr = $jmlHrgBI_curr;
		$jmlHrgTambahPLH_curr = $jmlHrgPLH_curr + $jmlHrgMut_PLH_curr;
		$jmlHrgTambahAman_curr = $jmlHrgAman_curr + $jmlHrgMut_Aman_curr;
		$jmlHrgTambahHSBG_curr = $jmlHrgHPS_HSBG_curr + $jmlHrgPindah_HSBG_curr + $jmlHrgGantirugi_HSBG_curr;

		$jmlHrgTambah_curr = $jmlHrgTambahPLH_curr + $jmlHrgTambahAman_curr + $jmlHrgTambahBi_curr+$jmlHrgTambahHSBG_curr;
		//berkurang
		$jmlBrgKurang_curr = $jmlBrgHPS_curr + $jmlBrgPindah_curr + $jmlBrgGantirugi_curr;
		$jmlHrgKurangPLH_curr = $jmlHrgHPS_PLH_curr + $jmlHrgPindah_PLH_curr + $jmlHrgGantirugi_PLH_curr;
		$jmlHrgKurangAman_curr = $jmlHrgHPS_Aman_curr + $jmlHrgPindah_Aman_curr + $jmlHrgGantirugi_Aman_curr;
		$jmlHrgKurangBi_curr = $jmlHrgHPS_curr + $jmlHrgPindah_curr + $jmlHrgGantirugi_curr;
		$jmlHrgKurangHSBG_curr = $jmlHrgHSBG_curr + $jmlHrgMut_HSBG_curr;


		$jmlHrgKurang_curr =  $jmlHrgKurangPLH_curr + $jmlHrgKurangAman_curr +  $jmlHrgKurangBi_curr+$jmlHrgKurangHSBG_curr;
		
		//echo "<br> $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr ";
		
		/*echo " $jmlHrgKurang_curr = 
			$jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr +
			$jmlHrgPindah_curr + $jmlHrgPindah_PLH_curr + $jmlHrgPindah_Aman_curr; <br> ";	
        */
        //akhir
		//$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir;
		$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir - $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
        $jmlHrg_akhir = 
			($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir + $jmlHrgMut_PLH_akhir+ $jmlHrgMut_Aman_akhir
			+ $jmlHrgHPS_HSBG_akhir + $jmlHrgPindah_HSBG_akhir+ $jmlHrgGantirugi_HSBG_akhir ) - 
			($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir +
			$jmlHrgPindah_akhir + $jmlHrgPindah_PLH_akhir + $jmlHrgPindah_Aman_akhir +
			$jmlHrgGantirugi_akhir + $jmlHrgGantirugi_PLH_akhir + $jmlHrgGantirugi_Aman_akhir +
			$jmlHrgHSBG_akhir+ $jmlHrgMut_HSBG_akhir
			);
        
		//hit total --------------------------------------------------------------------------------
        //awal ----------------------------------------
		$totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
		
		//kurang barang --------------------------------
        $totBrgHPS_curr += $isi['g'] == "00" ? $jmlBrgKurang_curr : 0;
		//kurang harga
		$totHrgKurang_curr += $isi['g'] == "00" ? $jmlHrgKurang_curr : 0;		
		//kurang pelihara
		$totHrgHPS_PLH_curr += $isi['g'] == "00" ? $jmlHrgKurangPLH_curr : 0;
		//kurang aman
		$totHrgHPS_Aman_curr += $isi['g'] == "00" ? $jmlHrgKurangAman_curr : 0;		
		//kurang perolehan
		$totHrgHPS_curr += $isi['g'] == "00" ? $jmlHrgKurangBi_curr : 0;//?
		
        //tambah barang --------------------------------
        $totBrgTambah_curr += $isi['g'] == "00" ? $jmlBrgTambah_curr : 0;
		//tambah harga
		$totHrgTambah_curr += $isi['g'] == "00" ? $jmlHrgTambah_curr : 0;		
		//tambah pelihara		
		$totHrgPLH_curr += $isi['g'] == "00" ? $jmlHrgTambahPLH_curr : 0;
		//$totHrgMut_PLH_curr += $isi['g'] == "00" ? $jmlHrgMut_PLH_curr : 0;
		//tambah aman
		$totHrgAman_curr += $isi['g'] == "00" ? $jmlHrgTambahAman_curr : 0;
		//$totHrgMut_Aman_curr += $isi['g'] == "00" ? $jmlHrgMut_Aman_curr : 0;		
		//tambah perolehan
        $totHrgBI_curr += $isi['g'] == "00" ? $jmlHrgTambahBi_curr : 0;		
		
		//akhir ----------------------------------------
        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
		
		
		
		
        //tampil row --------------------------------------------------
        //dlm ribuan
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, ',', '.') : number_format($jmlHrg_awal, 2, ',', '.');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambah_curr, 2, ',', '.');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahPLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahPLH_curr, 2, ',', '.');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahAman_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahAman_curr, 2, ',', '.');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahBi_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahBi_curr, 2, ',', '.');
        
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurang_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangBi_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangBi_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangPLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangPLH_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangAman_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangAman_curr, 2, ',', '.');
        
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		
		//$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_PLH_curr, 2, ',', '.');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_Aman_curr, 2, ',', '.');
        
		//bold
        $tampil_jmlBrg_awal = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_awal, 0, ',', '.') . "" : "" . number_format($jmlBrg_awal, 0, ',', '.') . "";
        $tampil_jmlBrgHPS_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "";
        $tampil_jmlBrgTambah_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "";
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_awal = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_awal . "" : $tampil_jmlHrg_awal;
        
		
        $tampil_jmlHrgTambah_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgTambah_curr . "" : $tampil_jmlHrgTambah_curr;
        $tampil_jmlHrgPLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgPLH_curr . "" : $tampil_jmlHrgPLH_curr;
        $tampil_jmlHrgAman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgAman_curr . "" : $tampil_jmlHrgAman_curr;
        
		$tampil_jmlHrgBI_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgBI_curr . "" : $tampil_jmlHrgBI_curr;
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		
		$tampil_jmlHrgKurang_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgKurang_curr . "" : $tampil_jmlHrgKurang_curr;
		$tampil_jmlHrgHPS_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_curr . "" : $tampil_jmlHrgHPS_curr;
		$tampil_jmlHrgHPS_PLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_PLH_curr . "" : $tampil_jmlHrgHPS_PLH_curr;
		$tampil_jmlHrgHPS_Aman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_Aman_curr . "" : $tampil_jmlHrgHPS_Aman_curr;
		$tampil_jmlHrgMut_PLH_curr = addbold( number_format_ribuan($jmlHrgMut_PLH_curr, $dlmRibuan), $isi['g'] == "00" );
		$tampil_jmlHrgMut_Aman_curr = addbold( number_format_ribuan($jmlHrgMut_Aman_curr, $dlmRibuan), $isi['g'] == "00" );
        //with td
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgTambah_curr</td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgPLH_curr</td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgAman_curr</td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgBI_curr</td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgKurang_curr</td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_curr</td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_PLH_curr</td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_Aman_curr</td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_PLH_curr</td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_Aman_curr</td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					
				";
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                    break;
                }
            case 3: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
                    break;
                }
        }
        //$tampil_jmlHrgTambah_curr='';
        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			$TampilStyle
        </tr>
		";
    }
    //tampil total -------------------------------------
	//if($noawal == 0  ){
		
	
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, ',', '.') : number_format($totHrg_awal, 2, ',', '.');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, ',', '.') : number_format($totHrgTambah_curr, 2, ',', '.');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, ',', '.') : number_format($totHrgPLH_curr, 2, ',', '.');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, ',', '.') : number_format($totHrgAman_curr, 2, ',', '.');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, ',', '.') : number_format($totHrgBI_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, ',', '.') : number_format($totHrgKurang_curr, 2, ',', '.');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_PLH_curr, 2, ',', '.');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_Aman_curr, 2, ',', '.');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgTambah_curr . "</td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgPLH_curr . "</td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgAman_curr . "</td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgBI_curr . "</td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgKurang_curr . "</td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_curr . "</td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_PLH_curr . "</td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_Aman_curr . "</td>";
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 3: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				";
                break;
            }
    }
	//}
    $ListData .= "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>TOTAL</td>
			$TampilStyleTot
			</tr>
			";//.'no awal='.$noawal



    //return $ListData;
    //return compact($ListData, $jmlData);
	//$ListData = '';
    return array($ListData, $jmlData);
}


function Mutasi_RekapByBrg_GetList2_($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $cetak=FALSE, $Style=1) {
    // ------------------------------
    // $Style=1 = total penambahan, 2= pemelihara, pemgaman, peroleh, 3 = saldo akhir sampai dgn tglakhir
    // ------------------------------
    global $Main;
    global $tglAwal, $tglAkhir; //$fmSemester, $fmTahun;



    $clGaris = $cetak == TRUE ? "GarisCetak" : "GarisDaftar";
    //get kondisi (skpd) ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
    $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					and c='$fmSKPD' $KondisiD $KondisiE ";
    if ($fmSKPD == "00") {
        $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE ";
    }

    //list --------------------------------------------------------------
    $ListData = "";
    $cb = 0;
    $no = $noawal;
    $sqry = "
		(select aa.f as f, aa.g as g, aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir ".			
		
		"from ref_barang aa 
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) bb on aa.f=bb.f						
			left join (select f , g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f) cc on aa.f=cc.f
			left join (select f , g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f) dd on aa.f=dd.f
			left join (select f , g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where  $Kondisi and (status_barang = 1 or status_barang = 3) and  tgl_buku < '$tglAwal'  group by f) ee on aa.f=ee.f
			
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) ff on aa.f=ff.f
			left join (select f , g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f) gg on aa.f=gg.f
			left join (select f , g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f) hh on aa.f=hh.f
			left join (select f , g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and (status_barang = 1 or status_barang = 3) and tgl_buku <= '$tglAkhir'  group by f) ii on aa.f=ii.f
			
			left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f
			left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f			
			left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f
			left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f
		".			
		" where aa.g='00'  
		)union(
		select aa.f, aa.g,  aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir ".		
		"from ref_barang aa 
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select f , g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select f , g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select f , g, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where $Kondisi and  (status_barang = 1 or status_barang = 3) and  tgl_buku < '$tglAwal'  group by f,g) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select f , g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select f , g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select f , g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and (status_barang = 1 or status_barang = 3) and tgl_buku <= '$tglAkhir'  group by f,g) ii on aa.f=ii.f and aa.g=ii.g 
			
			left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f and aa.g=jj.g 
			left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f and aa.g=kk.g 			
			left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f and aa.g=ll.g 
			left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f and aa.g=mm.g 
			".			
		" where aa.g<>'00' and aa.h='00'  
		)
		order by f, g		
	";
    //echo "$sqry";
    $QryRefBarang = mysql_query($sqry);
    //$QryRefBarang2 = mysql_query($sqry);
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;
    //while($isi=mysql_fetch_array($QryRefBarang), $isi2=mysql_fetch_array($QryRefBarang2)){
    while ($isi = mysql_fetch_array($QryRefBarang)) {
        //get kondisi1 (barang) ----------------------------------
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
        $KondisiBi = " status_barang<>3 ";
		$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
		$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";

        //hitung --------------------------------------------------
		//saldo awal ---------------------------------------------------------------------------------
        $jmlBrgHPS_awal = $isi['jmlBrgHPS_awal'];
        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];
        $jmlHrgHPS_awal = $isi['jmlHrgHPS_awal'];
        $jmlHrgPLH_awal = $isi['jmlHrgPLH_awal'];
        $jmlHrgAman_awal = $isi['jmlHrgAman_awal'];
        $jmlHrgBI_awal = $isi['jmlHrgBI_awal'];		
		$jmlHrgHPS_PLH_awal = $isi['jmlHrgHPS_PLH_awal'];   
		$jmlHrgHPS_Aman_awal = $isi['jmlHrgHPS_Aman_awal']; 		   		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_Aman_awal = $get['sumbiaya'];		  
		//awal pindahtangan 
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtangan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		//awal pindahtangan pelihara
		$jmlBrgPindah_awal = $get['sumbrg'];
		$jmlHrgPindah_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tgl_pemindahtangan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		//awal pindahtangan pengaman
		$jmlHrgPindah_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tgl_pemindahtangan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_awal = $get['sumbiaya'];
		
		//saldo akhir -------------------------------------------------------------------------------
        $jmlBrgHPS_akhir = $isi['jmlBrgHPS_akhir'];
        $jmlBrgBI_akhir = $isi['jmlBrgBI_akhir'];
        $jmlHrgHPS_akhir = $isi['jmlHrgHPS_akhir'];
        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
        $jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
        $jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];
		$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
		$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir']; 
		//akhir mutasi pelihara
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_PLH_akhir = $get['sumbiaya'];		
		//akhir mutasi pengaman
		$get= mysql_fetch_array( mysql_query(			
			"select f, sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		 
		$jmlHrgMut_Aman_akhir = $get['sumbiaya']; 
		//akhir pindahtangan 
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtangan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		//akhir pindahtangan pelihara
		$jmlBrgPindah_akhir = $get['sumbrg'];
		$jmlHrgPindah_akhir = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tgl_pemindahtangan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		//akhir pindahtangan pengaman
		$jmlHrgPindah_PLH_akhir = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tgl_pemindahtangan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_akhir = $get['sumbiaya'];
		
		//echo "jmlHrgMut_Aman_akhir= $jmlHrgMut_Aman_akhir sql= $cek ".$get['sumbiaya']."<br>";
        //curr --------------------------------------------------------------------------------------
        $jmlBrgHPS_curr = $jmlBrgHPS_akhir - $jmlBrgHPS_awal;
        $jmlBrgBI_curr = $jmlBrgBI_akhir - $jmlBrgBI_awal;
        $jmlHrgHPS_curr = $jmlHrgHPS_akhir - $jmlHrgHPS_awal;
        $jmlHrgPLH_curr = ($jmlHrgPLH_akhir - $jmlHrgPLH_awal)+($jmlHrgMut_PLH_akhir-$jmlHrgMut_PLH_awal);
        $jmlHrgAman_curr = ($jmlHrgAman_akhir - $jmlHrgAman_awal) + ($jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal);
        $jmlHrgBI_curr = $jmlHrgBI_akhir - $jmlHrgBI_awal;
		$jmlHrgHPS_PLH_curr = $jmlHrgHPS_PLH_akhir - $jmlHrgHPS_PLH_awal;
		$jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal; //echo "<br> jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal";
        $jmlHrgMut_PLH_curr = $jmlHrgMut_PLH_akhir - $jmlHrgMut_PLH_awal;
		$jmlHrgMut_Aman_curr = $jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal;
		//
        $jmlBrg_awal = $jmlBrgBI_awal - $jmlBrgHPS_awal;
        //$jmlHrg_awal = ($jmlHrgPLH_awal + $jmlHrgAman_awal + $jmlHrgBI_awal) - ($jmlHrgHPS_awal+$jmlHrgHPS_PLH_awal+$jmlHrgHPS_Aman_awal);      //echo "jmlHrg_awal= $jmlHrg_awal <br><br>";
		$jmlHrg_awal = 
			($jmlHrgPLH_awal + $jmlHrgAman_awal + $jmlHrgBI_awal + $jmlHrgMut_PLH_awal+ $jmlHrgMut_Aman_awal) - 
			($jmlHrgHPS_awal+$jmlHrgHPS_PLH_awal+$jmlHrgHPS_Aman_awal); 
        
		$jmlBrgTambah_curr = $jmlBrgBI_curr;		
		$jmlHrgKurang_curr = $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr; //echo "<br> $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr ";
        $jmlHrgTambah_curr = $jmlHrgPLH_curr + $jmlHrgAman_curr + $jmlHrgBI_curr ;
        
		$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir;
        $jmlHrg_akhir = ($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir + $jmlHrgMut_PLH_akhir+ $jmlHrgMut_Aman_akhir) - 
						($jmlHrgHPS_akhir+$jmlHrgHPS_PLH_akhir+$jmlHrgHPS_Aman_akhir);
        //total
        $totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
        $totBrgHPS_curr += $isi['g'] == "00" ? $jmlBrgHPS_curr : 0;
        
        $totBrgTambah_curr += $isi['g'] == "00" ? $jmlBrgTambah_curr : 0;
        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
        $totHrgPLH_curr += $isi['g'] == "00" ? $jmlHrgPLH_curr : 0;
        $totHrgAman_curr += $isi['g'] == "00" ? $jmlHrgAman_curr : 0;
        $totHrgBI_curr += $isi['g'] == "00" ? $jmlHrgBI_curr : 0;
		
        $totHrgTambah_curr += $isi['g'] == "00" ? $jmlHrgTambah_curr : 0;
		
		$totHrgKurang_curr += $isi['g'] == "00" ? $jmlHrgKurang_curr : 0;
		$totHrgHPS_curr += $isi['g'] == "00" ? $jmlHrgHPS_curr : 0;
		$totHrgHPS_PLH_curr += $isi['g'] == "00" ? $jmlHrgHPS_PLH_curr : 0;
		$totHrgHPS_Aman_curr += $isi['g'] == "00" ? $jmlHrgHPS_Aman_curr : 0;
		$totHrgMut_PLH_curr += $isi['g'] == "00" ? $jmlHrgMut_PLH_curr : 0;
		$totHrgMut_Aman_curr += $isi['g'] == "00" ? $jmlHrgMut_Aman_curr : 0;
		
		
		
        //tampil row --------------------------------------------------
        //dlm ribuan
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, ',', '.') : number_format($jmlHrg_awal, 2, ',', '.');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambah_curr, 2, ',', '.');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgPLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgPLH_curr, 2, ',', '.');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgAman_curr / 1000), 2, ',', '.') : number_format($jmlHrgAman_curr, 2, ',', '.');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgBI_curr / 1000), 2, ',', '.') : number_format($jmlHrgBI_curr, 2, ',', '.');
        $tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurang_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgHPS_curr / 1000), 2, ',', '.') : number_format($jmlHrgHPS_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgHPS_PLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgHPS_PLH_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgHPS_Aman_curr / 1000), 2, ',', '.') : number_format($jmlHrgHPS_Aman_curr, 2, ',', '.');
        //$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_PLH_curr, 2, ',', '.');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_Aman_curr, 2, ',', '.');
        
		//bold
        $tampil_jmlBrg_awal = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_awal, 0, ',', '.') . "" : "" . number_format($jmlBrg_awal, 0, ',', '.') . "";
        $tampil_jmlBrgHPS_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "";
        $tampil_jmlBrgTambah_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "";
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_awal = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_awal . "" : $tampil_jmlHrg_awal;
        
		
        $tampil_jmlHrgTambah_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgTambah_curr . "" : $tampil_jmlHrgTambah_curr;
        $tampil_jmlHrgPLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgPLH_curr . "" : $tampil_jmlHrgPLH_curr;
        $tampil_jmlHrgAman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgAman_curr . "" : $tampil_jmlHrgAman_curr;
        
		$tampil_jmlHrgBI_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgBI_curr . "" : $tampil_jmlHrgBI_curr;
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		
		$tampil_jmlHrgKurang_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgKurang_curr . "" : $tampil_jmlHrgKurang_curr;
		$tampil_jmlHrgHPS_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_curr . "" : $tampil_jmlHrgHPS_curr;
		$tampil_jmlHrgHPS_PLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_PLH_curr . "" : $tampil_jmlHrgHPS_PLH_curr;
		$tampil_jmlHrgHPS_Aman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_Aman_curr . "" : $tampil_jmlHrgHPS_Aman_curr;
		$tampil_jmlHrgMut_PLH_curr = addbold( number_format_ribuan($jmlHrgMut_PLH_curr, $dlmRibuan), $isi['g'] == "00" );
		$tampil_jmlHrgMut_Aman_curr = addbold( number_format_ribuan($jmlHrgMut_Aman_curr, $dlmRibuan), $isi['g'] == "00" );
        //with td
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgTambah_curr</td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgPLH_curr</td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgAman_curr</td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgBI_curr</td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgKurang_curr</td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_curr</td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_PLH_curr</td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_Aman_curr</td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_PLH_curr</td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_Aman_curr</td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					
				";
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                    break;
                }
            case 3: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
                    break;
                }
        }
        //$tampil_jmlHrgTambah_curr='';
        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			$TampilStyle
        </tr>
		";
    }
    //tampil total -------------------------------------
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, ',', '.') : number_format($totHrg_awal, 2, ',', '.');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, ',', '.') : number_format($totHrgTambah_curr, 2, ',', '.');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, ',', '.') : number_format($totHrgPLH_curr, 2, ',', '.');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, ',', '.') : number_format($totHrgAman_curr, 2, ',', '.');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, ',', '.') : number_format($totHrgBI_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, ',', '.') : number_format($totHrgKurang_curr, 2, ',', '.');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_PLH_curr, 2, ',', '.');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_Aman_curr, 2, ',', '.');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgTambah_curr . "</td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgPLH_curr . "</td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgAman_curr . "</td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgBI_curr . "</td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgKurang_curr . "</td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_curr . "</td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_PLH_curr . "</td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_Aman_curr . "</td>";
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 3: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				";
                break;
            }
    }
    $ListData .= "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>TOTAL</td>
			$TampilStyleTot <br> $Kondisi
			</tr>
			";



    //return $ListData;
    //return compact($ListData, $jmlData);
    return array($ListData, $jmlData);
}

function getIDByKodeBrg($tblName, $kdBrg) {
    $sqry = "select id from buku_induk where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = '$kdBrg'";
    //$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"';
    return table_get_value($sqry, "id");
}

function getNmWlayah($a, $b) {
    $kota = "";
    if (!($b == '' || $b == '00' )) {
        $sqry = "select nm_wilayah from ref_wilayah where a='" . $a . "' and b='" . $b . "'";
        //echo "<br>sqry"
        $kota = table_get_value($sqry, "nm_wilayah");
    }
    return $kota;
}

function tampilTotal($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='') {
    
	$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
    if ($jmlTotalHarga > 0){
		$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
	}
    $ListTotal = $cetak ?
            "<tr class=\"$clRow\">
				<td class=\"$clGaris\" colspan=" . ($clspn1 - 1) . " >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
				<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
				<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
				</tr>
				" : "
				<tr class=\"$clRow\">
				<td class=\"$clGaris\" colspan=$clspn1 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
				<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
				<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
				</tr>
				<tr class=\"$clRow\">
				<td class=\"$clGaris\" colspan=$clspn1 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
				<td class=\"$clGaris\" align=right><div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
				<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
				</tr>
			";
    return $ListTotal;
}

function tampilNmSubUnit($isi) {
    /*
      tampil nama sub unit d kolom keterangan penatausahaan
      syarat: skpd != '' & unit != '' & suunit =''
     */

    global $fmSKPD, $fmUNIT, $fmSUBUNIT;

    $nmSKPD = '';
    $nmUNIT = '';
    $nmSUBUNIT = '';
    /* if($fmSKPD == '00'  ){
      $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c']."0000'";//echo "<br>astr = ".$astr;
      $nmSKPD = table_get_value($astr, 'nm_skpd');
      }
      else if($fmUNIT =='00'){
      $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c'].$isi['d']."00'";//echo "<br>astr = ".$astr;
      $nmUNIT = table_get_value($astr, 'nm_skpd');
      }
      else if($fmSUBUNIT =='00'){
      $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='".$isi['c'].$isi['d'].$isi['e']."'";//echo "<br>astr = ".$astr;
      $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
      }
      $str = $nmSKPD;
      $str .= $str ==""? $nmUNIT : ' - '. $nmUNIT;
      $str .= $str == ""? $nmSUBUNIT: ' - '.$nmSUBUNIT; */
    if ($fmSKPD == '00') {
        //if($fmSKPD == '00'  ){
        $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . "0000'"; //echo "<br>astr = ".$astr;
        $nmSKPD = table_get_value($astr, 'nm_skpd');
        //}
        if ($isi['d'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . "00'"; //echo "<br>astr = ".$astr;
            $nmUNIT = table_get_value($astr, 'nm_skpd');
        }
        if ($isi['e'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . $isi['e'] . "'"; //echo "<br>astr = ".$astr;
            $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
        }
        $str = $nmSKPD . ' - ' . $nmUNIT . ' - ' . $nmSUBUNIT;
    } else if ($fmSKPD != '00' && $fmUNIT == '00' && $fmSUBUNIT == '00') {

        if ($isi['d'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . "00'"; //echo "<br>astr = ".$astr;
            $nmUNIT = table_get_value($astr, 'nm_skpd');
        }
        if ($isi['e'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . $isi['e'] . "'"; //echo "<br>astr = ".$astr;
            $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
        }
        $str = $nmUNIT . ' - ' . $nmSUBUNIT;
    } elseif ($fmSKPD != '00' && $fmUNIT != '00' && $fmSUBUNIT == '00') { //echo "<br> d =".$isi['d'];
        $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . $isi['e'] . "'"; //echo "<br>astr = ".$astr;
        $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
        $str = $nmSUBUNIT;
    }


    if ($str != '') {
        $str = ' /<br>' . $str;
    }

    return $str;
}

function tampilNmSubUnit2($isi) {
    /*
      tampil nama sub unit d kolom keterangan penatausahaan
      syarat: skpd != '' & unit != '' & suunit =''
     */

    global $fmSKPD, $fmUNIT, $fmSUBUNIT;

    if ($fmSKPD != '00' && $fmUNIT == '00' && $fmSUBUNIT == '00') {
        $nmSUBUNIT = '';
        $nmUNIT = '';
        if ($isi['d'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . "00'"; //echo "<br>astr = ".$astr;
            $nmUNIT = table_get_value($astr, 'nm_skpd');
        }
        if ($isi['e'] != '00') {
            $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . $isi['e'] . "'"; //echo "<br>astr = ".$astr;
            $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
        }
        $str = $nmUNIT . ' - ' . $nmSUBUNIT;
    } elseif ($fmSKPD != '00' && $fmUNIT != '00' && $fmSUBUNIT == '00') { //echo "<br> d =".$isi['d'];
        $astr = "select nm_skpd from ref_skpd where concat(c,d,e)='" . $isi['c'] . $isi['d'] . $isi['e'] . "'"; //echo "<br>astr = ".$astr;
        $nmSUBUNIT = table_get_value($astr, 'nm_skpd');
        $str = $nmSUBUNIT;
    }

    $str = ' / ' . $str;
    return $str;
}

function SemesterToTgl($fmSemester, $fmTahun) {
    //$tglAwal= "2010-01-01";$tglAkhir="2010-06-31";	//echo "<br> semester".$fmSemester;
    switch ($fmSemester) {
        default : {//SEMESTER I
                $tglAwal = $fmTahun . "-01-01";
                $tglAkhir = date('Y-m-j', strtotime($fmTahun . "-07-01 -1 day"));
                break;
            }
        case 1: {
                $tglAwal = $fmTahun . "-07-01";
                $tglAkhir = date('Y-m-j', strtotime(($fmTahun + 1) . "-01-01 -1 day"));
                break;
            }
    }
    return array($tglAwal, $tglAkhir);
}

function Mutasi_GetList($cetak=0) {
    global $Main, $ctk;
    global $jmPerHal, $HalDefault, $jmlData;
    global $fmSemester, $fmTahun, $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmKIB;
    global $ListHeader, $ListData, $ListFooterHal, $ListFooterAll;
    global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;

    $clGaris = $cetak == 1 ? "GarisCetak" : "GarisDaftar";
    //hal
    $Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
    $LimitHal = " limit " . (($HalDefault * 1) - 1) * $Main->PagePerHal . "," . $Main->PagePerHal;
    $LimitHal = !empty($ctk) ? " limit 0, $ctk" : $LimitHal;

    //tglAwal TglAkhir ------------------------------------------
    //$tglAwal= "2010-01-01";$tglAkhir="2010-06-31";	//echo "<br> semester".$fmSemester;
    /* switch ($fmSemester){
      default : {//SEMESTER I
      $tglAwal = $fmTahun."-01-01";
      $tglAkhir = date('Y-m-j',  strtotime (  $fmTahun."-07-01 -1 day" ));
      break;
      }
      case 1: {
      $tglAwal = $fmTahun."-07-01";
      $tglAkhir = date('Y-m-j', strtotime ( ($fmTahun+1)."-01-01 -1 day"  ));
      break;
      }
      } */
    list($tglAwal, $tglAkhir) = SemesterToTgl($fmSemester, $fmTahun);
    //Kondisi ----------------------------------
    $KondisiC = $fmSKPD == "00" ? "" : " and c='$fmSKPD' ";
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
    $KondisiE1 = $fmSEKSI == "00" || $fmSEKSI == "000" ? "" : " and e1='$fmSEKSI' ";
    $Kondisi = " and a1='" . $fmKEPEMILIKAN . "' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ";
    if ($fmKIB != '') {
        $Kondisi .= " and f = '$fmKIB' ";
    }

    //order -------------------------------------
    $OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j, thn_perolehan, noreg ";
    //$LimitHal = "limit 0, 10";
    //saldo awal ---------------------------------------------- 
	{
        $SaldoAwal_BrgKurang = 0;
        $SaldoAwal_HrgKurang = 0;
        $SaldoAwal_BrgTambah = 0;
        $SaldoAwal_HrgTambah = 0;
        //saldo awal kurang penghapusan ----------------------------------------------
        $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v_penghapusan_bi
				where tgl_penghapusan < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;
        $qry = mysql_query($aqry);
        $isi = mysql_fetch_array($qry);
        $SaldoAwal_BrgKurang = $isi['totbarang'];
        $SaldoAwal_HrgKurang = $isi['totharga']; //echo"<br>brg=$SaldoAwal_BrgKurang hrg=$SaldoAwal_HrgKurang";

		//saldo awal kurang pelihara,
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_penghapusan_pelihara
				where tgl_penghapusan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];

		//saldo awal kurang pengaman,
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_penghapusan_pengaman
				where tgl_penghapusan < '$tglAwal' $Kondisi";   //echo "<br> qry awal kurang = ".$aqry;
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		
		//saldo awal kurang pemindahtangan ---------------------------------------
		$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_pindahtangan
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;        
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_BrgKurangPindah = $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
		$SaldoAwal_BrgKurang += $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
        $SaldoAwal_HrgKurangPindah = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang pindah pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_pindahtangan_pelihara
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPindahPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang pindah pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_pindahtangan_pengaman
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPindahPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		
		//saldo awal kurang gantirugi --------------------------------------------
		$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_gantirugi
				where tgl_gantirugi < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;        
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_BrgKurangGantirugi = $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
		$SaldoAwal_BrgKurang += $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
        $SaldoAwal_HrgKurangGantirugi = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang gantirugi pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_gantirugi_pelihara
				where tgl_gantirugi < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangGantirugiPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang gantirugi pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_gantirugi_pengaman
				where tgl_gantirugi < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangGantirugiPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];

        //saldo awal kurang hapus_sebagian ----------------------------------------------
        $aqry = " select sum(harga_hapus)as totharga from v_hapus_sebagian
				where tgl_penghapusan < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;
        $qry = mysql_query($aqry);
        $isi = mysql_fetch_array($qry);
		$SaldoAwal_HrgKurangHapusSebagian = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang mut hapus sebagian
		$aqry = " select sum(harga_hapus)as totharga from v2_mutasi_hapussebagian
		where tgl_buku < '$tglAwal'  $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;	
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgKurang += $isi['totharga'];

			
		
        //saldo awal tambah perolehan ------------------------------------------------
        $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from buku_induk
		where tgl_buku < '$tglAwal'   $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;
        $qry = mysql_query($aqry);
        $isi = mysql_fetch_array($qry);
        $SaldoAwal_BrgTambah = $isi['totbarang'];
        $SaldoAwal_HrgTambah = $isi['totharga']; //echo"<br>brg=$SaldoAwal_BrgTambah hrg=$SaldoAwal_HrgTambah";
        //saldo awal tambah pelihara
        $aqry = " select count(*) as totbarang, sum(biaya_pemeliharaan)as totharga from v_pemelihara
		where tgl_pemeliharaan < '$tglAwal' and tambah_aset=1  $Kondisi ";  //echo "<br> qry awal tambah = ".$aqry;
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_HrgTambah += $isi['totharga'];
        //saldo awal tambah pengaman
        $aqry = " select count(*) as totbarang, sum(biaya_pengamanan)as totharga from v_pengaman
		where tgl_pengamanan < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;		
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];
		//saldo awal tambah mut pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_mutasi_pelihara
		where tgl_buku < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;	
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];
		//saldo awal tambah mut pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_mutasi_pengaman
		where tgl_buku < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;		
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];

		//saldo awal tambah hapus sebagian,
		$aqry = " select sum(harga_hapus)as totharga from v2_penghapusan_hapussebagian
				where tgl_penghapusan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_TambahHapusSebagian = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgTambah +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];	

		//saldo awal tambah pindah hapus sebagian
		$aqry = " select sum(harga_hapus)as totharga from v2_pindahtangan_hapussebagian
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgTambahPindahHapusSebagian = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgTambah +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];			
		
		//saldo awal tambah gantirugi hapus sebagian
		$aqry = " select sum(harga_hapus)as totharga from v2_gantirugi_hapussebagian
				where tgl_gantirugi < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgTambahGantirugiHapusSebagian = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgTambah +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		
		
        $SaldoAwal_Brg = $SaldoAwal_BrgTambah - $SaldoAwal_BrgKurang;// + $SaldoAwal_BrgKurangPindah);
        $SaldoAwal_Hrg = $SaldoAwal_HrgTambah - $SaldoAwal_HrgKurang;// + $SaldoAwal_HrgKurangPindah);//+$SaldoAwal_HrgKurangPelihara+$SaldoAwal_HrgKurangPengaman);
    }
    //perubahan berkrang ------------------------------------------------
    $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v_penghapusan_bi
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang = $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang = $isi['totharga'] == NULL ? 0 : $isi['totharga']; //echo"<br>brg=$BrgKurang hrg=$HrgKurang";
	//perubahan hapus pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_penghapusan_pelihara
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan hapus pengamanan
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_penghapusan_pengaman
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan pindah tangan --------------------------------------
	$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_pindahtangan
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang += $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_pindahtangan_pelihara
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPindahPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_pindahtangan_pengaman
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPindahPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	
	//perubahan kurang gantirugi --------------------------------------
	$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_gantirugi
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang += $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_gantirugi_pelihara
		where tgl_gantirugi>= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangGantirugiPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_gantirugi_pengaman
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangGantirugiPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];

	//perubahan kurang hapus sebagaian
	$aqry = " select sum(harga_hapus)as totharga from v_hapus_sebagian
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangHapusSebagian = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];	
    
    //perubahan bertambah ------------------------------------------------
    $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from buku_induk
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir'  $Kondisi";  //echo"<br>qry tambah=".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgTambah = $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgTambah = $isi['totharga'] == NULL ? 0 : $isi['totharga'];
    //pelihara
    $aqry = " select count(*) as totbarang, sum(biaya_pemeliharaan)as totharga from v_pemelihara
		where tgl_pemeliharaan >= '$tglAwal' and tgl_pemeliharaan <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry)); //echo ($aqry);
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
    //pengaman
    $aqry = " select count(*) as totbarang, sum(biaya_pengamanan)as totharga from v_pengaman
		where tgl_pengamanan >= '$tglAwal' and tgl_pengamanan <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//mutasi pelihara
    $aqry = " select sum(biaya_pemeliharaan)as totharga from v2_mutasi_pelihara
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];    
	//mutasi pengaman
    $aqry = " select sum(biaya_pengamanan)as totharga from v2_mutasi_pengaman
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];

//bertambah hapus sebagian





    //saldo akhir ----------------------------------------------
    $SaldoAkhir_Brg = $SaldoAwal_Brg + $BrgTambah - $BrgKurang ;//+ $BrgKurangPindah);
    $SaldoAkhir_Hrg = $SaldoAwal_Hrg + $HrgTambah - $HrgKurang ;//+ $HrgKurangPindah);//+$HrgKurangPelihara+$HrgKurangPengaman);

    // List -----------------------------------------------------------------
    //aqry 
	{
        $aqry = 
		"(select 
		'1' as grp, '0' as a1, null as a, null as b, null as c, null as d, null as e, null as e1, null as f, null as g, null as h, null as i, null as j, null as thn_perolehan, null as noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan, null as tgl_buku, null as ket_hapus, null as tgl_penghapusan,		
		$SaldoAwal_Brg as awal_barang, $SaldoAwal_Hrg as awal_harga, 
		null as brgkurang, null as hrgkurang, null as brgtambah, null as hrgtambah, null as akhir_barang, null as akhir_harga
		,null as nmbarang		
		)union(".
		
		"select 
		'3' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi, status_barang, asal_usul, satuan , tgl_buku, null as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, jml_barang as brgtambah, jml_harga as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from buku_induk
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".	
		"select 
		'3a' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pemeliharaan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,jenis_pemeliharaan as nmbarang
		from v2_mutasi_pelihara
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		"select 
		'3b' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pengamanan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_mutasi_pengaman
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".	
		"select 
		'5' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pemeliharaan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,jenis_pemeliharaan as nmbarang
		from v_pemelihara
		where tgl_pemeliharaan >='$tglAwal' and tgl_pemeliharaan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		"select 
		'6' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pengamanan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v_pengaman
		where tgl_pengamanan >='$tglAwal' and tgl_pengamanan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		"select 
		'7' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_penghapusan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, harga_hapus as hrgkurang, 
		null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian as nmbarang
		from v_hapus_sebagian
		where tgl_penghapusan >='$tglAwal' and tgl_penghapusan <='$tglAkhir'  
		$Kondisi $OrderBy
		)union(".
		
		
		"select 
		'2' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , tgl_buku, ket_hapus, tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from v_penghapusan_bi 		
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".		
		"select 
		'2a' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, 
		null as hrgtambah,
		null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_penghapusan_pelihara
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		"select 
		'2b' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 
		null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_penghapusan_pengaman
		where tgl_penghapusan >='$tglAwal' and tgl_penghapusan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan ------------------------------------------------------
		"select 
		'2c' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , 
		tgl_pemindahtanganan  as tgl_buku, 
		ket_hapus as ket_hapus, null as tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, 
		null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,nm_barang as nmbarang
		from v1_pindahtangan 		
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan pelihara
		"select 
		'2d' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga, null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, null as hrgtambah,null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_pindahtangan_pelihara
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan pengaman
		"select 
		'2e' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga,  null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 	null as hrgtambah,	null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_pindahtangan_pengaman
		where tgl_pemindahtanganan >='$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".		
		//gantirugi -------------------------------------------------------
		"select 
		'2f' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , 
		tgl_gantirugi  as tgl_buku, 
		ket_hapus as ket_hapus, null as tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, 
		null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from v1_gantirugi 		
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".
		//gantirugi pelihara
		"select 
		'2g' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga, null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, null as hrgtambah,null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_gantirugi_pelihara
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		//gantirugi pengaman
		"select 
		'2h' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_gantirugi as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga,  null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 	null as hrgtambah,	null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_gantirugi_pengaman
		where tgl_gantirugi >='$tglAwal' and tgl_gantirugi <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		
		"select
		'4' as grp, '9999' as a1, null as a, null as b, null as c, null as d, null as e,null as e1, null as f, null as g, null as h, null as i, null as j, null as thn_perolehan, null as noreg,		 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan, null as tgl_buku, null as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga, null as brgkurang, 
		null as hrgkurang, null as brgtambah, null as hrgtambah,
		$SaldoAkhir_Brg as akhir_barang, $SaldoAkhir_Hrg as akhir_harga 
		,null as nmbarang		
		
		)
		
		"; //echo $aqry;
    }

    //str_replace('<LimitHal>', $LimitHal, $aqry )
    //jml data
    $qry = mysql_query($aqry);
    $jmlData = mysql_num_rows($qry);

    $aqry .=" $OrderBy $LimitHal "; //echo"<br>qryunion=".$aqry;
    $qry = mysql_query($aqry);
    $totBrgKurangHal = 0;
    $totHrgKurangHal = 0;
    $totBrgTambahHal = 0;
    $totHrgTambahHal = 0;
    $no = !empty($ctk) ? 0 : $Main->PagePerHal * (($HalDefault * 1) - 1);
    
	while ($isi = mysql_fetch_array($qry)) {

        if ($cetak == 0) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $no++;
        //$jmlTotalHargaDisplay += $isi['jml_harga'];
        //get detail kib
        $kdBarang = "";
        $nmBarang = "";
        $ISI5 = ""; //b=merk;
        $ISI6 = ""; //a=sertifikatno;b=nopabrik,rangka,mesin;c=dokumenno;
        $ISI7 = ""; //b=bahan;
        $ISI10 = ""; //c=kondisibangunan;
        $ISI12 = $Main->KondisiBarang[$isi['kondisi'] - 1][1];
        $ISI15 = ""; //ket;
        //if ($isi['f'] != NULL){
        $kdBarang = $isi['f'] . "." . $isi['g'] . "." . $isi['h'] . "." . $isi['i'] . "." . $isi['j'];
        if ($isi['grp'] == '2' || $isi['grp'] == '3') {

            $nmBarang = table_get_value("select nm_barang from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j)= '$kdBarang'", 'nm_barang');
            $KondisiKIB = " Where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and e1 = '{$isi['e1']}' and
						f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
						tahun = '{$isi['thn_perolehan']}' and noreg = '{$isi['noreg']}'  ";
            Penatausahaan_BIGetKib($isi['f'], $KondisiKIB);
            
       	//} elseif ($isi['grp'] == '5'  ) {
		}else{
			
		
            $nmBarang = $isi['nmbarang'];
        }
		
		//keterangan ---------
        //$ISI15 = $isi['grp'] == '2' ? $isi['ket_hapus'] : $ISI15;
		$ISI15 = $isi['grp'] == '3' ? $ISI15: $isi['ket_hapus'];		
        $ISI15 = !empty($ISI15) ? $ISI15 : "-";
        $ISI15 .= $isi['grp'] == '2' ? ' /<br>' . TglInd($isi['tgl_penghapusan']) : ' /<br>' . TglInd($isi['tgl_buku']);
        $ISI15 .= tampilNmSubUnit($isi);

        $tampilSaldoAwalBrg = $isi['grp'] == '1' ? number_format($SaldoAwal_Brg, 0, ',', '.') : '';
        $tampilSaldoAwalHrg = $isi['grp'] == '1' ? number_format($SaldoAwal_Hrg, 2, ',', '.') : '';
        $tampilSaldoAkhirBrg = $isi['grp'] == '4' ? number_format($SaldoAkhir_Brg, 0, ',', '.') : '';
        $tampilSaldoAkhirHrg = $isi['grp'] == '4' ? number_format($SaldoAkhir_Hrg, 2, ',', '.') : '';

        $totBrgKurangHal += $isi['brgkurang'];
        $totHrgKurangHal += $isi['hrgkurang'];
        $totBrgTambahHal += $isi['brgtambah'];
        $totHrgTambahHal += $isi['hrgtambah'];

        $tampilBrgKurang = $isi['brgkurang'] != NULL ? number_format($isi['brgkurang'], 0, ',', '.') : "";
        $tampilHrgKurang = $isi['hrgkurang'] != NULL ? number_format($isi['hrgkurang'], 2, ',', '.') : "";
        $tampilBrgTambah = $isi['brgtambah'] != NULL ? number_format($isi['brgtambah'], 0, ',', '.') : "";
        $tampilHrgTambah = $isi['hrgtambah'] != NULL ? number_format($isi['hrgtambah'], 2, ',', '.') : "";


        //list ------------
        if ($isi['grp'] == '1') {
            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=".($cetak==1?'1':'2').">$no.</td>
			<td class=\"$clGaris\" align=left colspan=11><b>Jumlah (Awal)</td>					
			
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAwalBrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAwalHrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilBrgKurang . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilHrgKurang . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilBrgTambah . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilHrgTambah . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAkhirBrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAkhirHrg . "</td>
			
			<td class=\"$clGaris\"></td>			
        	</tr>
			";
        } else if ($isi['grp'] == '4') {
            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=".($cetak==1?'1':'2').">$no.</td>
			<td class=\"$clGaris\" align=left colspan=11><b>Jumlah (Akhir)</td>					
			
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAwalBrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAwalHrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilBrgKurang . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilHrgKurang . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilBrgTambah . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilHrgTambah . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAkhirBrg . "</td>
			<td class=\"$clGaris\" align=right><b>" . $tampilSaldoAkhirHrg . "</td>
			
			<td class=\"$clGaris\"></td>			
        	</tr>
			";
        } else {


            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=".($cetak==1?'1':'2').">$no.</td>
			<!--<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>-->
			<td class=\"$clGaris\" align=center>$kdBarang</td>
			<td class=\"$clGaris\" align=center>{$isi['noreg']}</td>
			
			<td class=\"$clGaris\">$nmBarang</td>			
			<td class=\"$clGaris\">$ISI5</td>
			<td class=\"$clGaris\">$ISI6</td>
			<td class=\"$clGaris\">$ISI7</td>		
				
			<!--<td class=\"$clGaris\">" . $Main->AsalUsul[$isi['asal_usul'] - 1][1] . "/<br>" . $Main->StatusBarang[$isi['status_barang'] - 1][1] . "</td>	-->
			<td class=\"$clGaris\">" . $Main->AsalUsul[$isi['asal_usul'] - 1][1] . "</td>
			<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"$clGaris\">$ISI10</td>			
			<td class=\"$clGaris\" align=right>{$isi['satuan']}</td>
			<td class=\"$clGaris\">$ISI12</td>
			
			<td class=\"$clGaris\" align=right>" . $tampilSaldoAwalBrg . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilSaldoAwalHrg . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilBrgKurang . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilHrgKurang . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilBrgTambah . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilHrgTambah . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilSaldoAkhirBrg . "</td>
			<td class=\"$clGaris\" align=right>" . $tampilSaldoAkhirHrg . "</td>
			
			<td class=\"$clGaris\">$ISI15</td>
			<!--<td class=\"$clGaris\">$dok</td>-->
        	</tr>
		";
        }
    }
	


    // tampil ----------------------------------------------------------
    // list header 
	{
        $ListHeader =
                "<tr >
		<th class=\"th02\" colspan='".($cetak==1?'3':'4')."'>Nomor</th>
		<th class=\"th02\" colspan='4'>Spesifikasi Barang</th>	
		<th class=\"th02\" rowspan=3 width=50>Asal / Cara Perolehan Barang</th>
		<th class=\"th02\" rowspan=3>Tahun Peroleh an</th>
		<th class=\"th02\" rowspan=3>Ukuran Barang / Konstruksi (P,SP,D)</th>	
		<th class=\"th02\" rowspan=3 width=30>Satu an</th>
		<th class=\"th02\" rowspan=3>Kondisi (B,RR,RB)</th>
		<th class=\"th02\" colspan=2>Jumlah (Awal)</th>	
		<th class=\"th02\" colspan=4>Mutasi/Perubahan</th>	
		<th class=\"th02\" colspan=2>Jumlah (Akhir)</th>		
		<th class=\"th02\" rowspan=3>Keterangan/<br>Tgl. Buku/<br>Tgl. Hapus</th>	
	</tr>
	<tr>
		<th class=\"th02\" rowspan=2 colspan=".($cetak==1?'1':'2').">No. Urut</th>		
		<th class=\"th02\" rowspan=2 >Kode <br>Barang</th>
		<th class=\"th02\" rowspan=2 >Reg.</th>
		<th class=\"th02\" rowspan=2 width=\"100\">Nama / Jenis Barang</th>
		<th class=\"th02\" rowspan=2 width=\"70\">Merk / Tipe </th>
		<th class=\"th02\" rowspan=2 width=\"70\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
		<th class=\"th02\" rowspan=2 >Bahan</th>
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
		<th class=\"th02\" colspan=2 > Berkurang </th>
		<th class=\"th02\" colspan=2 > Bertambah </th>	
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
	</tr>
	<tr>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
	</tr>
	<tr>
		<th class='th03' colspan=".($cetak==1?'1':'2').">1</th>
		<th class='th03' >2</th><th class='th03' >3</th>
		<th class='th03' >4</th><th class='th03' >5</th><th class='th03' >6</th>
		<th class='th03' >7</th><th class='th03' >8</th><th class='th03' >9</th>
		<th class='th03' >10</th><th class='th03' >11</th><th class='th03' >12</th>
		<th class='th03' >13</th><th class='th03' >14</th><th class='th03' >15</th>
		<th class='th03' >16</th><th class='th03' >17</th><th class='th03' >18</th>
		<th class='th03' >19</th><th class='th03' >20</th><th class='th03' >21</th>	
	</tr>
	";
    }



    //list footer 
	{
        $ListFooterHal = !empty($ctk) ?
                "" :
                "<tr>
			<td class=\"$clGaris\" align=left colspan=13><b>Total per Halaman</td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right><b>" . number_format($totBrgKurangHal, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($totHrgKurangHal, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($totBrgTambahHal, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($totHrgTambahHal, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" ></td>
		</tr>";
        $ListFooterAll = 
                "<tr>
			<td class=\"$clGaris\" align=left colspan=".($cetak==1?'12':'13')."><b>Total Seluruhnya</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($SaldoAwal_Brg, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($SaldoAwal_Hrg, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($BrgKurang, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($HrgKurang, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($BrgTambah, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($HrgTambah, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($SaldoAkhir_Brg, 0, ',', '.') . "</td>
			<td class=\"$clGaris\" align=right><b>" . number_format($SaldoAkhir_Hrg, 2, ',', '.') . "</td>
			<td class=\"$clGaris\" ></td>
		</tr>" ;
    }

}

function getKondisiSKPD() {
    global $Main, $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
    $KondisiC = $fmSKPD == '' || $fmSKPD == '00' ? "" : " and c='$fmSKPD' ";
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
    $KondisiE1 = $fmSEKSI == $kdSubUnit0 ? "" : " and e1='$fmSEKSI' ";

    //$cek .= '<br> fmKEPEMILIKANt = '.$fmKEPEMILIKAN;
    $KondisiSKPD = "a1='" . $fmKEPEMILIKAN . "' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ";
    // echo "<br>KondisiSKPD=".$KondisiSKPD;
    return $KondisiSKPD;
}

function getKondisiSKPD2($fmKEPEMILIKAN, $a, $b, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI='') {
    global $Main;//, $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT;
	
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
	
    $KondisiC = $fmSKPD == '' || $fmSKPD == '00' ? "" : " and c='$fmSKPD' ";
    $KondisiD =  $fmUNIT == '' || $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == '' || $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI == '' || $fmSEKSI == "00" || $fmSEKSI == "000" ? "" : " and e1='$fmSEKSI' ";
    //$cek .= '<br> fmKEPEMILIKANt = '.$fmKEPEMILIKAN;
    $KondisiSKPD = "a1='$fmKEPEMILIKAN' and a='$a' and b='$b' $KondisiC $KondisiD $KondisiE $KondisiE1 ";
    // echo "<br>KondisiSKPD=".$KondisiSKPD;
    return $KondisiSKPD;
}

function getKondisiSKPD3($fmKEPEMILIKAN, $a, $b, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI='') {
    global $Main;//, $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT;
	
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
	
    $KondisiC = $fmSKPD == '' || $fmSKPD == '00' ? "" : " and c='$fmSKPD' ";
    $KondisiD =  $fmUNIT == '' || $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == '' || $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI == '' || $fmSEKSI == "$kdSubUnit0" ? "" : " and e1='$fmSEKSI' ";
    //$cek .= '<br> fmKEPEMILIKANt = '.$fmKEPEMILIKAN;
    $KondisiSKPD = " a='$a' and b='$b' $KondisiC $KondisiD $KondisiE $KondisiE1 ";
    // echo "<br>KondisiSKPD=".$KondisiSKPD;
    return $KondisiSKPD;
}

function createBarPanel($barName, $barTitle, $barHeight, $content, $colapsedFirst=TRUE) {
    if ($colapsedFirst) {
        $barHeightStyleFirst = 'height:' . ($barHeight + 1);
        $imgFirst = 'images/tumbs/right.png';
    } else {
        $barHeightStyleFirst = '';
        $imgFirst = 'images/tumbs/down.png';
    }
    return "
	<script>
		function " . $barName . "_expand(){
			var tab = document.getElementById('$barName');
			var tabhead= document.getElementById('" . $barName . "_head');
			var img = document.getElementById('" . $barName . "_ico');
			if (tab.style.height != '" . ($barHeight + 1) . "px'){
				tab.style.height = '" . ($barHeight + 1) . "px';
				img.src = 'images/tumbs/right.png';
			}else{
				tab.style.removeProperty('height');
				img.src = 'images/tumbs/down.png';
			}
		}
	</script>
	<div style='text-align:left;overflow-y:hidden;margin:0 0 8 0; $barHeightStyleFirst;' id='$barName'>	
	<div style='border-bottom: 1px solid #02769D;'>
	<table class='barHeader' id='" . $barName . "_head'  style='height:$barHeight;'	onclick='" . $barName . "_expand()' >
	
	
	<tr>
		<td style='width:20' class=''>
			<img id='" . $barName . "_ico' src='$imgFirst'>
		</td>
		<td style='padding: 0 8 0 0' class='' width=''>
			$barTitle
		</td>		
	</tr>
	</table>
	</div>
	
	$content
	</div>";
}

function gen_table_session($nmtable,$uid)
{
	$hasil=$nmtable.$uid.microtime();
	$hasil=md5($hasil);
	return $hasil;
}

/*
  function Pemanfaatan_GetList($cetak = FALSE ){
  global $Main, $SSPg;
  global $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT;
  global $fmBARANGCARIDPB, $fmTahunPerolehan, $HalDPB, $jmlDataDPB;

  //kondisi ------------------------------------------------------


  $Kondisi = getKondisiSKPD();
  if(!empty($fmBARANGCARIDPB)){
  $Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPB%' ";
  }
  if(!empty($fmTahunPerolehan)){
  $Kondisi .= " and pemanfaatan.thn_perolehan = '$fmTahunPerolehan' ";
  }
  switch($SSPg){
  case '03': break;
  case '04': $Kondisi .= " and f='01' "; break;
  case '05': $Kondisi .= " and f='02' "; break;
  case '06': $Kondisi .= " and f='03' "; break;
  case '07': $Kondisi .= " and f='04' "; break;
  case '08': $Kondisi .= " and f='05' "; break;
  case '09': $Kondisi .= " and f='06' "; break;
  }
  $KondisiTotalDPB = $Kondisi;

  //limit
  $LimitHalDPB = " limit ".(($HalDPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

  //total jumlah harga
  $jmlTotalHargaDPB = mysql_query("select sum(pemanfaatan.biaya_pemanfaatan) as total  from pemanfaatan inner join ref_barang  using(f,g,h,i,j) where $KondisiTotalDPB ");
  if($jmlTotalHargaDPB = mysql_fetch_array($jmlTotalHargaDPB)){
  $jmlTotalHargaDPB = $jmlTotalHargaDPB[0];
  }else{
  $jmlTotalHargaDPB=0;
  }
  //jumlah barang
  $Qry = mysql_query("select pemanfaatan.*,ref_barang.nm_barang from pemanfaatan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
  $jmlDataDPB = mysql_num_rows($Qry);
  $Qry = mysql_query("select pemanfaatan.*,ref_barang.nm_barang from pemanfaatan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalDPB");

  //list
  $no=$Main->PagePerHal * (($HalDPB*1) - 1);
  $cb=0;
  $jmlTampilDPB = 0;
  $JmlTotalHargaListDPB = 0;
  $ListBarangDPB = "";
  $clGaris = $cetak? "GarisCetak": "GarisDaftar";
  while ($isi = mysql_fetch_array($Qry)){
  $jmlTampilDPB++;
  $no++;
  $JmlTotalHargaListDPB += $isi['biaya_pemanfaatan'];
  $kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
  $kdKelBarang = $isi['f'].$isi['g']."00";
  $nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
  $nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
  $clRow = $no % 2 == 0 ?"row1":"row0";

  $ISI15 = $isi['ket']==""? "-":$isi['ket'];
  $ISI15 .= tampilNmSubUnit($isi);

  $tampilNo = $cetak ?
  "<td class=\"$clGaris\" align=center colspan=2>$no</td>":
  "<td class=\"$clGaris\" align=center>$no</td>
  <td class=\"$clGaris\"><input type=\"checkbox\" id=\"cbDPB$cb\" name=\"cidDPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>";
  $ListBarangDPB .= "
  <tr class='$clRow'>
  $tampilNo
  <td class=\"$clGaris\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
  <td class=\"$clGaris\" align=center>{$isi['noreg']}</td>
  <td class=\"$clGaris\">{$nmBarang['nm_barang']}</td>
  <td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}</td>
  <td class=\"$clGaris\" align=center>".TglInd($isi['tgl_pemanfaatan'])."</td>
  <td class=\"$clGaris\">".$Main->BentukPemanfaatan[$isi['bentuk_pemanfaatan']-1][1]."</td>
  <td class=\"$clGaris\">{$isi['kepada_instansi']}</td>
  <td class=\"$clGaris\">{$isi['kepada_alamat']}</td>
  <td class=\"$clGaris\">{$isi['kepada_nama']}</td>
  <td class=\"$clGaris\">{$isi['kepada_jabatan']}</td>
  <td class=\"$clGaris\">{$isi['surat_no']}</td>
  <td class=\"$clGaris\" align=center>".ifemptyTgl( TglInd($isi['surat_tgl']),'-')."</td>
  <td class=\"$clGaris\" align=right>{$isi['jangkawaktu']}</td>
  <td class=\"$clGaris\" align=right>".number_format(($isi['biaya_pemanfaatan']/1000), 0, ',', '.')."</td>
  <td class=\"$clGaris\">$ISI15</td>
  </tr>

  ";
  $cb++;
  }

  if ($cetak == FALSE){
  $ListTotalHal =
  "<tr>
  <td class=\"$clGaris\"colspan=15>Jumlah Biaya per Halaman (Ribuan)</td>
  <td class=\"$clGaris\" align=right ><b>".number_format(($JmlTotalHargaListDPB/1000), 0, ',', '.')." </td>
  <td class=\"$clGaris\" align=center>&nbsp;</td>
  </tr>";
  }

  $ListTotal = "
  <tr>
  <td class=\"$clGaris\" colspan=15>Jumlah Biaya Seluruhnya (Ribuan)</td>
  <td class=\"$clGaris\" align=right ><b>".number_format(($jmlTotalHargaDPB/1000), 0, ',', '.')." </td>
  <td class=\"$clGaris\" align=center>&nbsp;</td>
  </tr>

  ";

  $tampilNo =  $cetak?
  "<TH class=\"th01\" rowspan=2 colspan=2>No</TD>":
  "<TH class=\"th01\" rowspan=2>No</TD>
  <TH class=\"th01\" rowspan=2><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPB,'cbDPB','toggle2');\" /></TD>";

  $ListHeader = "
  <TR>
  $tampilNo
  <TH class=\"th01\" rowspan=2>Kode Barang</TH>
  <TH class=\"th01\" rowspan=2>Nomor<br>Reg</TH>
  <TH class=\"th01\" rowspan=2>Nama Barang</TH>
  <TH class=\"th01\" rowspan=2>Tahun<br>Peroleh an</TH>
  <TH class=\"th01\" rowspan=2 style='width:60'>Tanggal<br>Pemanfaat an</TH>
  <TH class=\"th01\" rowspan=2>Bentuk<br>Pemanfaat an</TH>
  <TH class=\"th02\" colspan=4>K e p a d a</TH>
  <TH class=\"th02\" colspan=2>Surat Perjanjian / Kontrak</TH>
  <TH class=\"th01\" rowspan=2 style='width:45'>Jangka Waktu (Tahun)</TH>
  <TH class=\"th01\" rowspan=2  style='width:65'>Biaya (Ribuan)</TH>
  <TH class=\"th01\" rowspan=2>Ket.</TH>
  </TR>
  <TR>
  <TH class=\"th01\">Instansi</TH>
  <TH class=\"th01\">Alamat</TH>
  <TH class=\"th01\">Nama</TH>
  <TH class=\"th01\">Jabatan</TH>
  <TH class=\"th01\" style='width:70'>Nomor</TH>
  <TH class=\"th01\" style='width:60'>Tanggal</TH>
  </TR>";

  $ListHal = $cetak?
  "" :
  "<tr><td class=\"$clGaris\" colspan=16 align=center>
  ".Halaman($jmlDataDPB,$Main->PagePerHal,"HalDPB")."
  </td></tr>";

  return
  "
  <!--<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>-->
  $ListHeader
  $ListBarangDPB
  $ListTotalHal
  $ListTotal
  $ListHal
  <!--</table>-->";
  }
 */

/*
  function Pemanfaatan_FormEntry(){
  global $Main, $fmIDBARANG, $fmNMBARANG, $fmNOREG, $fmTANGGALPEMANFAATAN, $fmBENTUKPEMANFAATAN,
  $fmKEPADAINSTANSI, $fmKEPADAALAMAT, $fmKEPADANAMA, $fmKEPADAJABATAN, $fmSURATNOMOR,
  $fmSURATTANGGAL, $fmJANGKAWAKTU, $fmBIAYA, $fmKET;
  global $SPg, $Pg;


  $toolbar = "
  <table width=\"100%\" class=\"menudottedline\">
  <tr><td>
  <table width=\"50\"><tr>
  <td>
  ".PanelIcon1("javascript:adminForm.Act.value='Pemanfaatan_Simpan';adminForm.submit()","save_f2.png","Simpan")."
  </td>
  <td>
  ".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."
  </td>
  </tr></table>
  </td></tr>
  </table>";

  $FormEntry = "
  <A NAME='FORMENTRY'></A>
  <table width=\"100%\" height=\"100%\" class=\"adminform\">
  <TR><td>Nama Barang</td><td>:</td>
  <td>".txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ')."
  ".txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly ')."	</td>
  </tr>
  <TR>
  <td>Nomor Register</td>	<td>:</td> <td>	".txtField('fmNOREG',$fmNOREG,'6','4','text',' readonly ')."</td>
  </tr>
  <tr valign=\"top\">
  <!--<td>Tanggal Pemanfaatan</td><td>:</td><td>".InputKalender("fmTANGGALPEMANFAATAN")."	</td>-->
  ".formEntryBase2('Tanggal Pemanfaatan',':',
  createEntryTgl('fmTANGGALPEMANFAATAN', false)
  ,'','','','valign="top" height="24"')."
  </tr>
  <tr valign=\"top\">
  <td>Bentuk Pemanfaatan</td><td>:</td><td>".cmb2D('fmBENTUKPEMANFAATAN',$fmBENTUKPEMANFAATAN,$Main->BentukPemanfaatan,'')."</td>
  </tr>
  <tr valign=\"top\">
  <td>Kepada</td> <td>:</td><td>&nbsp;</td>
  </tr>
  <tr valign=\"top\">
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Instansi/CV/PT</td><td>:</td>
  <td>".txtField('fmKEPADAINSTANSI',$fmKEPADAINSTANSI,'100','50','text')."</td>
  </tr>
  <tr valign=\"top\">
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td><td>:</td>
  <td>".txtField('fmKEPADAALAMAT',$fmKEPADAALAMAT,'100','50','text')."</td>
  </tr>
  <tr valign=\"top\">
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama</td><td>:</td>
  <td>".txtField('fmKEPADANAMA',$fmKEPADANAMA,'100','50','text')." </td>
  </tr>
  <tr valign=\"top\">
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan</td><td>:</td>
  <td>".txtField('fmKEPADAJABATAN',$fmKEPADAJABATAN,'100','50','text')." </td>
  </tr>
  <tr valign=\"top\">
  <td>Surat Perjanjian / Kontrak</td> <td>:</td> <td>&nbsp;	</td>
  </tr>
  <tr valign=\"top\">
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td> <td>:</td>
  <td>".txtField('fmSURATNOMOR',$fmSURATNOMOR,'100','50','text')."</td>
  </tr>
  <tr valign=\"top\">
  <!--<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td> <td>:</td>
  <td>".InputKalender("fmSURATTANGGAL")."</td>-->
  ".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal',':',
  createEntryTgl('fmSURATTANGGAL', false)
  ,'','','','valign="top" height="24"')."
  </tr>
  <tr valign=\"top\">
  <td>Jangka Waktu</td>  <td>:</td>
  <td>".txtField('fmJANGKAWAKTU',$fmJANGKAWAKTU,'6','4','text')." tahun</td>
  </tr>
  <tr valign=\"top\">
  <td>Biaya Pemanfaatan</td> <td>:</td>  <td>".inputFormatRibuan("fmBIAYA")."</td>
  </tr>

  <tr valign=\"top\">
  <td>Keterangan</td> <td>:</td> <td><textarea name=\"fmKET\" cols=\"60\" >$fmKET</textarea></td></tr>
  </table>

  <br>
  $toolbar
  ";

  return $FormEntry;
  }
 */

/* 	
  function Pemanfaatan_Proses(){
  global $Act, $Baru, $cidBI, $cidDPB, $Info;
  global $fmWILSKPD, $fmIDBARANG, $fmIDBUKUINDUK, $fmNOREG, $fmTAHUNPEROLEHAN,
  $fmTANGGALPEMANFAATAN, $fmBENTUKPEMANFAATAN, $fmKEPADAINSTANSI, $fmKEPADAALAMAT, $fmKEPADANAMA, $fmKEPADAJABATAN,
  $fmSURATNOMOR, $fmSURATTANGGAL, $fmJANGKAWAKTU, $fmBIAYA, $fmKET, $fmTAHUNANGGARAN;
  global $fmID, $fmNMBARANG;//, $fmSTATUSBARANG;

  //ProsesCekField
  //echo "<br>$fmWILSKPD, $fmIDBARANG, $fmIDBUKUINDUK, $fmNOREG, $fmTAHUNPEROLEHAN, $fmTANGGALPEMANFAATAN, $fmBENTUKPEMANFAATAN";
  $MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPEMANFAATAN,fmBENTUKPEMANFAATAN";

  if($Act=="Pemanfaatan_Simpan"){
  $errmsg = ProsesCekField2($MyField);
  if ($errmsg=='' && !cektanggal($fmTANGGALPEMANFAATAN)){ $errmsg = "Tanggal Pemanfaatan $fmTANGGALPEMANFAATAN salah!"; }
  if ($errmsg=='' && !($fmSURATTANGGAL=='' || $fmSURATTANGGAL=='0000-00-00')&& !cektanggal($fmSURATTANGGAL)){ $errmsg = "Tanggal Surat $fmSURATTANGGAL salah!"; }
  //if(ProsesCekField2($MyField)){
  if ($errmsg==''){
  $ArBarang = explode(".",$fmIDBARANG);
  $ArWILSKPD = explode(".",$fmWILSKPD);
  $Simpan = false;
  if($Baru=="1"){
  //Simpan Baru
  $Qry = "insert into pemanfaatan (a1,a,b,c,d,e,f,g,h,i,j,id_bukuinduk,noreg,thn_perolehan,
  tgl_pemanfaatan,bentuk_pemanfaatan,kepada_instansi,kepada_alamat,kepada_nama,
  kepada_jabatan,surat_no,surat_tgl,jangkawaktu,biaya_pemanfaatan,ket,tahun)
  values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
  '$fmIDBUKUINDUK','$fmNOREG','$fmTAHUNPEROLEHAN','".$fmTANGGALPEMANFAATAN."','$fmBENTUKPEMANFAATAN',
  '$fmKEPADAINSTANSI','$fmKEPADAALAMAT','$fmKEPADANAMA',
  '$fmKEPADAJABATAN','$fmSURATNOMOR','".$fmSURATTANGGAL."','$fmJANGKAWAKTU','$fmBIAYA','$fmKET','$fmTAHUNANGGARAN')";
  //echo $Qry;
  $Simpan = mysql_query($Qry);
  $UpdateBI = mysql_query("update buku_induk set status_barang='2' where id='$fmIDBUKUINDUK'");
  $InsertHistory = mysql_query("insert into history_barang
  (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values
  ('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
  '$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".$fmTANGGALPEMANFAATAN."','Entry Pemanfaatan','$fmKONDISIBARANG','$fmSTATUSBARANG')");

  }
  if($Baru=="0"){
  $Kriteria = "id='$fmID'";
  $Qry = "
  update pemanfaatan set
  tgl_pemanfaatan = '".$fmTANGGALPEMANFAATAN."', bentuk_pemanfaatan = '$fmBENTUKPEMANFAATAN',
  kepada_instansi = '$fmKEPADAINSTANSI', kepada_alamat = '$fmKEPADAALAMAT', kepada_nama = '$fmKEPADANAMA',
  kepada_jabatan = '$fmKEPADAJABATAN', surat_no = '$fmSURATNOMOR', surat_tgl = '".$fmSURATTANGGAL."',
  jangkawaktu = '$fmJANGKAWAKTU', biaya_pemanfaatan = '$fmBIAYA', ket = '$fmKET'
  where $Kriteria ";
  $Simpan = mysql_query($Qry);
  $InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,
  kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".$fmTANGGALPEMANFAATAN."','Update Pemanfaatan','$fmKONDISIBARANG','$fmSTATUSBARANG')");
  }

  if($Simpan)	{
  KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
  $Info = "<script>alert('Data telah di ubah dan simpan')</script>";
  $Baru="";
  $Act = '';
  }
  else{
  $Info .= "<script>alert('Data TIDAK dapat di ubah atau di simpan')</script>";
  }

  }else{
  $Info = "<script>alert('Data TIDAK Lengkap. \\n$errmsg')</script>";
  }

  }


  //get data ------------------------------------------------------------------------------
  //echo "<br>act=".$Act;
  $cidNya = $cidBI;
  if($Act == "Edit"){	$cidNya = $cidDPB;}

  if($Act=="Edit"|| $Act == "Pemanfaatan_TambahEdit"){
  if(count($cidNya) != 1)	{
  $Info = "<script>alert('Pilih hanya satu data!')</script>";
  }else{
  if($Act=="Edit"){
  $Qry = mysql_query("select * from pemanfaatan where id='{$cidNya[0]}'");
  }else{
  $Qry = mysql_query("select * from buku_induk where buku_induk.id='{$cidNya[0]}'");
  }
  $isi = mysql_fetch_array($Qry);

  //get ID
  if($Act=="Edit"){
  $fmIDBUKUINDUK=$isi['id_bukuinduk'];
  }else{
  $fmIDBUKUINDUK=$isi['id'];
  }
  $fmID = "{$isi['id']}";

  //get data
  $kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
  $nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
  $fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
  $fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
  $fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
  $fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
  $fmNMBARANG = "{$nmBarang['nm_barang']}";
  $fmTAHUNANGGARAN = "{$isi['tahun']}";
  $fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
  $fmNOREG = $isi['noreg'];

  if($Act == "Pemanfaatan_TambahEdit"){

  if ($isi["status_barang"]==2){ //cek status sudah dimanfaatkan
  //echo "<br>status_barang=".$isi["status_barang"];
  $Act = ''; $Baru = ''; //balik ke daftar
  $Info = "<script>alert('Status Barang sudah Pemanfaatan!')</script>";
  }else{
  $Baru=1;
  }
  }else{
  //get data pemanfaatan
  $fmTANGGALPEMANFAATAN = $isi['tgl_pemanfaatan'];
  $fmBENTUKPEMANFAATAN = $isi['bentuk_pemanfaatan'];
  $fmKEPADAINSTANSI = $isi['kepada_instansi'];
  $fmKEPADAALAMAT = $isi['kepada_alamat'];
  $fmKEPADANAMA = $isi['kepada_nama'];
  $fmKEPADAJABATAN = $isi['kepada_jabatan'];
  $fmSURATNOMOR = $isi['surat_no'];
  $fmSURATTANGGAL = $isi['surat_tgl'];
  $fmJANGKAWAKTU = $isi['jangkawaktu'];
  $fmBIAYA = $isi['biaya_pemanfaatan'];
  $fmKET = $isi['ket'];

  $Baru=0;
  }
  }
  }


  //Proses HAPUS
  $cidDPB = cekPOST("cidDPB");
  if($Act=="Hapus" && count($cidDPB) > 0){
  for($i = 0; $i<count($cidDPB); $i++){
  $cekIdBI = mysql_fetch_array(mysql_query("select id_bukuinduk from pemanfaatan where id='{$cidDPB[$i]}'"));$cekIdBI = $cekIdBI[0];
  $Del = mysql_query("delete from pemanfaatan where id='{$cidDPB[$i]}' limit 1");
  $UpdateBI = mysql_query("update buku_induk set status_barang='1' where id='$cekIdBI'");
  $Info = "<script>alert('Data telah di hapus')</script>";
  }
  }


  }

 */
function boxFilter($content){
	return "<div style='float:left;padding: 0 4 0 0;height:22;'>".
		$content.
		"</div>";			
}

function cariBI($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="", $DisAbled="", $Param="", $idbi='', $c='', $d='', $e='', $e1='') {
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
	$IDBI = 'idbi';
	$THN = 'thn_perolehan';
	$NOREG = 'noreg';
	$LOKASI = 'lokasi';
    $in = "
	<input type=hidden id='$IDBI' name='$IDBI' value='$idbi' readonly=''  >
	<input type=text id='$ID' name='$ID' value='$VALID' size='15' readonly=''  > 
	<!--<input type=text id='$NM' name='$NM' value='$VALID' size='15' readonly=''  > -->
	
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objIDBI=$IDBI&objNM=$NM&kdBrgOld=$VALID&Act=$Act&objTHN=$THN&objNOREG=$NOREG&objLOKASI=$LOKASI&c=$c&d=$d&e=$e&e1=$e1'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> (  atau Klik 
		<input type=button value='Cari' onClick=\"TampilkanIFrame(document.all.cari$NMIFRAME)\" > 
		untuk mencari data) ";
    return $in;
}

function genFilterBarfn($Filters, $onClick, $withButton=TRUE, $TombolCaption='Tampilkan', $Style='FilterBar'){
	$Content=''; $i=0;
	while( $i < count($Filters) ){
		$border	= $i== count($Filters)-1 ? '' : "border-right:1px solid #E5E5E5;";		
		$Content.= "<td  align='left' style='padding:1 8 0 8; $border'>".
						$Filters[$i].
					"</td>";
		$i++;
	}
	//tombol
	if($withButton){
		$Content.= "<td  align='left' style='padding:1 8 0 8;'>
					<input type=button id='btTampil' value='$TombolCaption' 
						onclick=\"$onClick\">
				</td>";		
	}
		
	/*return  "
		<table class='$Style' width='100%' style='margin: 4 0 0 0' cellspacing='0' cellpadding='0' border='0'>
		<tr><td>
			<table cellspacing='0' cellpadding='0' border='0'>
			<tr valign='middle'>   						
				$Content				
			</tr>
			</table>
		</td><td width='*'>&nbsp</td></tr>		
		</table>";	*/
	return  "
		<!--<table class='$Style' width='100%' style='margin: 4 0 0 0' cellspacing='0' cellpadding='0' border='0'>
		<tr><td> -->
		<div class='$Style' >
			<table style='width:100%'><tr><td align=left>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tr valign='middle'>   						
				$Content				
			</tr>
			</table>
			</td></tr></table>
		</div>
		<!--</td><td width='*'>&nbsp</td>
		</tr>		
		</table>-->
		
		";	
}

function bilang($x){
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  //if (x==0) return;   
  //elseif ($x < 1) return ' koma '.$abil[$x].bilang($x / 10);
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return bilang($x - 10) . " belas";
  elseif ($x < 100)
    return bilang($x / 10) . " puluh" . bilang($x % 10);
  elseif ($x < 200)
    return " seratus" . bilang($x - 100);
  elseif ($x < 1000)
    return bilang($x / 100) . " ratus" . bilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . bilang($x - 1000);
  elseif ($x < 1000000)
    return bilang($x / 1000) . " ribu" . bilang($x % 1000);
  elseif ($x < 1000000000)
    return bilang($x / 1000000) . " juta" . bilang($x % 1000000);
  elseif ($x < 1000000000000)
    return bilang($x / 1000000000) . " miliar" . bilang( fmod( $x, 1000000000));	
  elseif ($x < 1000000000000000)
    return bilang($x / 1000000000000) . " triliun" . bilang(fmod($x , 1000000000000));		
}

	
	function Penatausahaan_BIGetKib($f, $KondisiKIB){
		//get data detil kib untuk BI
		global $Main, $sort1;
		global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
		$ISI5=''; $ISI6=''; $ISI7=''; $ISI10=''; $ISI12=''; $ISI15='';
		//echo"<br>f=".$f;
		switch($f){
			case '01':{//KIB A			
				
				$sqryKIBA = "select sertifikat_no, luas, ket from kib_a  $KondisiKIB limit 0,1";
				//$sqryKIBA = "select * from view_kib_a  $KondisiKIB limit 0,1";
				//echo '<br> qrykibA = '.$sqryKIBA;
				$QryKIB_A = mysql_query($sqryKIBA);
				while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
					//$ISI5 = $isiKIB_A['alamat'].'<br>'.$isiKIB_A['alamat_kel'].'<br>'.$isiKIB_A['alamat_kec'].'<br>'.$isiKIB_A['alamat_kota'] ;
					$ISI6 = $isiKIB_A['sertifikat_no'];
					/*$ISI6 = $isiKIB_A['sertifikat_no'].'/<br>'.
					TglInd($isiKIB_A['sertifikat_tgl']).'/<br>'.
					$Main->StatusHakPakai[ $isiKIB_A['status_hak']-1 ][1];
					*/
					$ISI10 = number_format($isiKIB_A['luas'],2,',','.');//$cek .= '<br> luas A = '.$isiKIB_A['luas'];
					$ISI15 = "{$isiKIB_A['ket']}";
				}
				break;
			}
			case '02':{//KIB B;			
				//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
				$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket  from kib_b  $KondisiKIB limit 0,1";
				//echo"<br>qrkbb=".$aqry;
				
				$QryKIB_B = mysql_query($aqry);
				
				//echo "<br>qrkibb=".$aqry;
				while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
					
					$ISI5 = "{$isiKIB_B['merk']}";
					$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
					$ISI7 = "{$isiKIB_B['bahan']}";
					$ISI10 = "{$isiKIB_B['ukuran']}";
					$ISI15 = "{$isiKIB_B['ket']}";
				}
				break;
				}	
			case '03':{//KIB C;
				$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket,kota, alamat_kec, alamat_kel, alamat,alamat_b,alamat_c from kib_c  $KondisiKIB limit 0,1");
				//$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_c  $KondisiKIB limit 0,1");
				while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
					//$ISI5 = $isiKIB_C['alamat'].'<br>'.$isiKIB_C['alamat_kel'].'<br>'.$isiKIB_C['alamat_kec'].'<br>'.$isiKIB_C['alamat_kota'] ;
					$ISI5= getalamat($isiKIB_C['alamat_b'],$isiKIB_C['alamat_c'],$isiKIB_C['alamat'],$isiKIB_C['kota'] ,$isiKIB_C['alamat_kec'],$isiKIB_C['alamat_kel']);
					$ISI6 = "{$isiKIB_C['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
					$ISI15 = "{$isiKIB_C['ket']}";
				}
				break;
			}
			case '04':{//KIB D;
				//$QryKIB_D = mysql_query("select dokumen_no, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_d  $KondisiKIB limit 0,1");
				$QryKIB_D = mysql_query("select dokumen_no, ket  from kib_d  $KondisiKIB limit 0,1");
				while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
					//$ISI5 = $isiKIB_D['alamat'].'<br>'.$isiKIB_D['alamat_kel'].'<br>'.$isiKIB_D['alamat_kec'].'<br>'.$isiKIB_D['alamat_kota'] ;
					$ISI6 = "{$isiKIB_D['dokumen_no']}";
					$ISI15 = "{$isiKIB_D['ket']}";
				}
				break;
			}
			case '05':{//KIB E;		
				$QryKIB_E = mysql_query("select seni_bahan, ket from kib_e  $KondisiKIB limit 0,1");
				while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
					$ISI7 = "{$isiKIB_E['seni_bahan']}";
					$ISI15 = "{$isiKIB_E['ket']}";
				}
				break;
			}
			case '06':{//KIB F;
				//$cek.='<br> F = '.$isi['f'];
				//$sqrykibF = "select dokumen_no, bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat  from view_kib_f  $KondisiKIB limit 0,1";
				$sqrykibF = "select dokumen_no, bangunan, ket from kib_f  $KondisiKIB limit 0,1";
				$QryKIB_F = mysql_query($sqrykibF);
				$cek.='<br> qrykibF = '.$sqrykibF;
				while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
					//$ISI5 = $isiKIB_F['alamat'].'<br>'.$isiKIB_F['alamat_kel'].'<br>'.$isiKIB_F['alamat_kec'].'<br>'.$isiKIB_F['alamat_kota'] ;
					$ISI6 = "{$isiKIB_F['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
					$ISI15 = "{$isiKIB_F['ket']}";
				}
				break;
			}
		}
		
		$ISI5 	= !empty($ISI5)?$ISI5:"-"; 
		$ISI6 	= !empty($ISI6)?$ISI6:"-";
		$ISI7 	= !empty($ISI7)?$ISI7:"-";
		$ISI10 	= !empty($ISI10)?$ISI10:"-";
		//$ISI12 	= !empty($ISI12)?$ISI12:"-";
		$ISI15 	= !empty($ISI15)?$ISI15:"-";
	}
	
	
	function Penatausahaan_BIGetKib_hapus($f, $KondisiKIB){
		//get data detil kib untuk BI
		global $Main, $sort1;
		global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
		$ISI5=''; $ISI6=''; $ISI7=''; $ISI10=''; $ISI12=''; $ISI15='';
		//echo"<br>f=".$f;
		switch($f){
			case '01':{//KIB A			
				
				//$sqryKIBA = "select sertifikat_no, luas, ket from kib_a  $KondisiKIB limit 0,1";
				$sqryKIBA = "select * from view_kib_a  $KondisiKIB limit 0,1";
				//echo '<br> qrykibA = '.$sqryKIBA;
				$QryKIB_A = mysql_query($sqryKIBA);
				while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
					$ISI5 = $isiKIB_A['alamat']==''? '' : $isiKIB_A['alamat'].'<br>';
					$ISI5 .= $isiKIB_A['alamat_kel']==''? '': $isiKIB_A['alamat_kel'].'<br>';
					$ISI5 .= $isiKIB_A['alamat_kec']==''? '': $isiKIB_A['alamat_kec'].'<br>';
					$ISI5 .= $isiKIB_A['alamat_kota']==''? '': $isiKIB_A['alamat_kota'] ;
					$ISI6 = $isiKIB_A['sertifikat_no'].'/<br>'.
					TglInd($isiKIB_A['sertifikat_tgl']).'/<br>'.
					$Main->StatusHakPakai[ $isiKIB_A['status_hak']-1 ][1];
					$ISI10 = number_format($isiKIB_A['luas'],2,',','.');//$cek .= '<br> luas A = '.$isiKIB_A['luas'];
					$ISI15 = "{$isiKIB_A['ket']}";
				}
				break;
			}
			case '02':{//KIB B;			
				//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
				$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket  from kib_b  $KondisiKIB limit 0,1";
				//echo"<br>qrkbb=".$aqry;
				$QryKIB_B = mysql_query($aqry);
				//echo "<br>qrkibb=".$aqry;
				while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
					$ISI5 = "{$isiKIB_B['merk']}";
					$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
					$ISI7 = "{$isiKIB_B['bahan']}";
					$ISI10 = "{$isiKIB_B['ukuran']}";
					$ISI15 = "{$isiKIB_B['ket']}";
				}
				break;
				}	
			case '03':{//KIB C;
				//$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket from kib_c  $KondisiKIB limit 0,1");
				$aqry="select dokumen_no, dokumen_tgl, kondisi_bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_c  $KondisiKIB limit 0,1";
				$QryKIB_C = mysql_query($aqry);
				//echo '<br> aqry = '.$aqry;
				while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
					$ISI5 = $isiKIB_C['alamat']==''? '' : $isiKIB_C['alamat'].'<br>';
					$ISI5 .= $isiKIB_C['alamat_kel']==''? '': $isiKIB_C['alamat_kel'].'<br>';
					$ISI5 .= $isiKIB_C['alamat_kec']==''? '': $isiKIB_C['alamat_kec'].'<br>';
					$ISI5 .= $isiKIB_C['alamat_kota']==''? '': $isiKIB_C['alamat_kota'] ;
					//$ISI5 = $isiKIB_C['alamat'].'<br>'.$isiKIB_C['alamat_kel'].'<br>'.$isiKIB_C['alamat_kec'].'<br>'.$isiKIB_C['alamat_kota'] ;
					//echo "<br>ISI5=$ISI5";
					$ISI6 = $isiKIB_C['dokumen_no'].'/<br>'.TglInd($isiKIB_C['dokumen_tgl']);
					$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
					$ISI15 = "{$isiKIB_C['ket']}";
				}
				
				break;
			}
			case '04':{//KIB D;
				$QryKIB_D = mysql_query("select dokumen_no, dokumen_tgl, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_d  $KondisiKIB limit 0,1");
				while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
					$ISI5 = $isiKIB_D['alamat']==''? '' : $isiKIB_D['alamat'].'<br>';
					$ISI5 .= $isiKIB_D['alamat_kel']==''? '': $isiKIB_D['alamat_kel'].'<br>';
					$ISI5 .= $isiKIB_D['alamat_kec']==''? '': $isiKIB_D['alamat_kec'].'<br>';
					$ISI5 .= $isiKIB_D['alamat_kota']==''? '': $isiKIB_D['alamat_kota'] ;
					//$ISI5 = $isiKIB_D['alamat'].'<br>'.$isiKIB_D['alamat_kel'].'<br>'.$isiKIB_D['alamat_kec'].'<br>'.$isiKIB_D['alamat_kota'] ;
					$ISI6 = $isiKIB_D['dokumen_no'].'/<br>'.TglInd($isiKIB_D['dokumen_tgl']);
					$ISI15 = "{$isiKIB_D['ket']}";
				}
				break;
			}
			case '05':{//KIB E;		
				$QryKIB_E = mysql_query("select seni_bahan, ket from kib_e  $KondisiKIB limit 0,1");
				while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
					$ISI7 = "{$isiKIB_E['seni_bahan']}";
					$ISI15 = "{$isiKIB_E['ket']}";
				}
				break;
			}
			case '06':{//KIB F;
				//$cek.='<br> F = '.$isi['f'];
				$sqrykibF = "select dokumen_no, dokumen_tgl, bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat  from view_kib_f  $KondisiKIB limit 0,1";
				$QryKIB_F = mysql_query($sqrykibF);
				$cek.='<br> qrykibF = '.$sqrykibF;
				while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
					$ISI5 = $isiKIB_F['alamat']==''? '' : $isiKIB_F['alamat'].'<br>';
					$ISI5 .= $isiKIB_F['alamat_kel']==''? '': $isiKIB_F['alamat_kel'].'<br>';
					$ISI5 .= $isiKIB_F['alamat_kec']==''? '': $isiKIB_F['alamat_kec'].'<br>';
					$ISI5 .= $isiKIB_F['alamat_kota']==''? '': $isiKIB_F['alamat_kota'] ;
					//$ISI5 = $isiKIB_F['alamat'].'<br>'.$isiKIB_F['alamat_kel'].'<br>'.$isiKIB_F['alamat_kec'].'<br>'.$isiKIB_F['alamat_kota'] ;
					$ISI6 = $isiKIB_F['dokumen_no'].'/<br>'.TglInd($isiKIB_F['dokumen_tgl']);
					$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
					$ISI15 = "{$isiKIB_F['ket']}";
				}
				break;
			}
		}
		
		$ISI5 	= !empty($ISI5)?$ISI5:"-"; 
		$ISI6 	= !empty($ISI6)?$ISI6:"-";
		$ISI7 	= !empty($ISI7)?$ISI7:"-";
		$ISI10 	= !empty($ISI10)?$ISI10:"-";
		$ISI12 	= !empty($ISI12)?$ISI12:"-";
		$ISI15 	= !empty($ISI15)?$ISI15:"-";
	}
	
	
	function Penatausahaan_FormEntry($currID=0, $btCariBrg=TRUE , $entryMutasi=FALSE){
		global $Main, $btnTampilKib, $titleAct, $DisAbled;
		global $Pg, $SPg, $fmIDBARANG, $fmNMBARANG, $fmTAHUNPEROLEHAN, $fmREGISTER, $fmSATUAN;
		global $lastNoReg, $fmHARGABARANG, $fmJUMLAHBARANG, $jmlBrgReadonly, $fmASALUSUL;
		global $fmKONDISIBARANG, $tgl_buku;
		global $cek;	
		global $dokumen, $dokumen_ket, $dokumen_file, $dokumen_file_old;
		global $gambar, $gambar_old;
		global $InfoIDBARANG;
		global $fmUID, $fmTglUpdate, $tgl_sensus;
		global $cidBI;
		global $Baru, $KIB,$Act;
		global $dok_scriptUpload,$dok_tampilMenu,$dok_tampilList,$dok_hidden; //dokumen
		global $fmIdAwal;
		global $fmSTATUSBARANG;
		global $tahun_sensus, $ref_idpemegang, $ref_idpemegang2, $ref_idpenanggung, $ref_idruang;
		global $status_penguasaan_;
		$err = '';
		
		if($err=='' && $Baru != 0 && $fmSTATUSBARANG != 1 ){	//cek status barang
			$err = 'Hanya Status Barang Inventaris Yang Dapat Di Edit!';
			
		}
		
		$jmlBrgReadonly = $entryMutasi==FALSE? $jmlBrgReadonly :' readonly="" ';
		
		if ($Baru==0){	//edit

		$pemegang = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpemegang'"
		));
		$pemegang2 = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpemegang2'"
		));
		//$cek .= "select * from ref_pegawai where Id='$ref_idpemegang'";
		//$cek .= 'pegang='.$pemegang['nama'];
		$penanggung = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpenanggung'"
		));
		$ruang = mysql_fetch_array(mysql_query(
			"select * from ref_ruang where Id='$ref_idruang'"
		));
		$gedung = mysql_fetch_array(mysql_query(
			"select * from ref_ruang where c='".$ruang['c']."' and d='".$ruang['d']."' and e='".$ruang['e']."' and p='".$ruang['p']."' and q='0000'"
		));
			
			//echo"<br>currID=$currID fmIdAwal=$fmIdAwal";
			$idbidok = $fmIdAwal =='' || $fmIdAwal == 0? $currID : $fmIdAwal;
			Dok_Page($idbidok,1);
			$entry_dok=
				"$dok_scriptUpload 	
				$dok_tampilMenu			
				$dok_tampilList";
			$entry_dok = 			
				"<table style= 'background-color:white;width:345;border-bottom-color: #CCC;
				border-bottom-style: solid; border-bottom-width: 1px; border-collapse: separate;
				border-left-color: #CCC; border-left-style: solid; border-left-width: 1px;
				border-right-color: #CCC;border-right-style: solid;border-right-width: 1px;
				border-top-color: #CCC;border-top-style: solid;border-top-width: 1px;height:120'>		
				<tr valign='top'><td style='padding:2'>				
				<div style='position:relative'>
				$entry_dok
				</div>
				</td>
				</tr>
				</table>";		
			$entryDok = formEntryBase2('File Dokumen (Max. 50Mb)',':',$entry_dok,'','','','valign="top" height="24"');
			
			$idbigambar = $fmIdAwal =='' || $fmIdAwal == 0? $currID : $fmIdAwal; 
			//echo "$currID $fmIdAwal $idbidok $idbigambar <br>";
			Gbr_Proses( 
					$idbigambar, $_POST['FmEntryGbr_fmKET'], $_POST['FmEntryGbr_idgbr'] ,
					$_POST['FmEntryGbr_fmKET2'], "FmEntryGbr", $_POST['FmEntryGbr_LimitAw']
				);	//echo"<br>Act=$Act";
			$FormName1 = 'FmEntryGbr';
			//$FormName2 = 'FmEntryGbr2';
			$entryGambar = 
				formEntryBase2(
					'Gambar Barang (Max. 500Kb)',':', 
					"<a name='FmEntryGbr' style='position:relative;top:-100;'></a>".
					"<script src='js/jquery.js' type='text/javascript'></script>".
					"<input type='hidden' id='FormName' name='FormName' value=''>".					
					createImgEntry($FormName1, '300', '200', '30', 1)					
					,'','','','valign="top" height="24"');
					
			
		}
		
		//echo"<br>KIB=$KIB";
		if ($btCariBrg){
			$entryBrg = 
			formEntryBase2('Nama Barang',':',
			cariInfo2("adminForm","pages/01/caribarang1.php","pages/01/caribarang2.php",
			"fmIDBARANG","fmNMBARANG","readonly","$DisAbled",
			" onselect=\"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit();\" ",$KIB),
			'width="150"',' width="10" ','','valign="top" height="24"');
		}else{
			/*$entryBrg = '<td>'.txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ').
			txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly ').'</td>';*/
			$entryBrg = 
			formEntryBase2("NamaBarang",":",txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ')." ".
			txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly '),
			' width="150" ',' width="10" ','','valign="top" height="24"');
		}

		$get= mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpemegang'"
		));
		$nm_pemegang = $get['nama']; $nip_pemegang=$get['nip'];
		$get= mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpemegang2'"
		));
		$nm_pemegang2 = $get['nama']; $nip_pemegang2=$get['nip'];		
		$get= mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='$ref_idpenanggung'"
		));
		$nm_penanggung = $get['nama']; $nip_penanggung=$get['nip'];
		$get= mysql_fetch_array(mysql_query(
			"select * from ref_ruang where id='$ref_idruang'"
		));
		if($get){
			$nm_ruang = $get['nm_ruang'];
			$gdg = mysql_fetch_array(mysql_query(
				"select * from ref_ruang where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' and e1='".$get['e1']."' and p='".$get['p']."' and q='0000' "
			));
			$nm_gedung = $gdg['nm_ruang'];
			
		}		
		
		
		$Entry_BI ="
		<input type='hidden' id='sesi' name='sesi' value='".gen_table_session('buku_induk',$fmUID)."'>
		<table width=\"100%\" height=\"100%\" class=\"adminform\" >		
		<tr><td colspan='3' height='40'>
		<div style='float:left;display;block;'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>".$titleAct."</span></div>
		<div style='float:right;display;block;color: #6D84B4;padding:4'>( Update terakhir: <b>".
		TglInd($fmTglUpdate)."</b> Jam: <b>".substr($fmTglUpdate,11,8)."</b> Oleh: <b>$fmUID</b> )</div>
		<input type='hidden' id='fmTglUpdate' name='fmTglUpdate' value='$fmTglUpdate'>
		<input type='hidden' id='fmUID' name='fmUID' value='$fmUID'>
		</td></tr>
		
		
		".$entryBrg."
		
		".formEntryBase2('Tahun Perolehan',':',
		'<input type="text" name="fmTAHUNPEROLEHAN" value="'.$fmTAHUNPEROLEHAN.'" size="4" maxlength=4 				
		onkeypress="return isNumberKey(event)"
		'.($entryMutasi==FALSE? '':' readonly="" ').' 
		/>
		<span style="color:red;">4 digit (mis: 1998)</span>',
		'','','','valign="top" height="24"')."		
		
		".formEntryBase2('Nomor Register',':',
		'<INPUT type=text name="fmREGISTER" value="'.$fmREGISTER.'" size="'.$Main->NOREG_SIZE.'" maxlength="'.$Main->NOREG_SIZE.'" 
		onKeyup="infofmREGISTER.value=this.value"
		onkeypress="return isNumberKey(event)"
		>
		<span style="color:red;">'.$Main->NOREG_SIZE.' digit (mis: '.$Main->NOREG_SAMPLE.')</span>
		<input type=button 
		
		onClick="adminForm.action=\'#\';adminForm.submit();" 
		value="Cari No. Akhir" title="Cari No. Register Terakhir">
		<INPUT type=text name="lastNoReg" value="'.$lastNoReg.'" size="4" maxlength="4" readonly><span style="color:red;"> (Ket: Sebelum mencari No. Register Akhir, Isi Nama Barang dan Tahun Perolehan )</span>'
		
		
		,
		'','','','valign="top" height="24"')."
		
		
		".formEntryBase2('Harga Satuan Barang ',':',
		'Rp.'.inputFormatRibuan("fmHARGABARANG", ($entryMutasi==FALSE? '':' readonly="" ') ),'','','','valign="top" height="24"')."		
		".formEntryBase2("Jumlah Barang",":",
		"<input type=\"text\" size='4' maxlength='2' name=\"fmJUMLAHBARANG\" value=\"$fmJUMLAHBARANG\" 
		".$jmlBrgReadonly."
		onkeypress='return isNumberKey(event)'
		/>&nbsp;&nbsp;
		<span style='color: red;'> (max 99)</span> &nbsp&nbsp 
		Satuan : <input type=\"text\" size='6' name=\"fmSATUAN\" value=\"$fmSATUAN\" ".($entryMutasi==FALSE? '':' readonly="" ')." />
		&nbsp&nbsp<span style='color: red;'> untuk tanah atau bangunan diisi 1 bidang/bangunan bukan diisi luas tanah/bangunan</span>", 
		"","","","valign='top' height='24'")."		
		".formEntryBase2("Asal Usul/Cara Perolehan",":",
		cmb2D('fmASALUSUL',$fmASALUSUL,$Main->AsalUsul,''), 
		"","","","valign='top' height='24'")."		
		".formEntryBase2("Kondisi Barang",":",
		cmb2D('fmKONDISIBARANG',$fmKONDISIBARANG,$Main->KondisiBarang,''), 
		"","","","valign='top' height='24'")."		
		
		$entryDok
		<!--
		".formEntryBase2('Keterangan Dokumen', ':',
		'<textarea cols="60" rows="2" name="dokumen_ket" >'.$dokumen_ket.'</textarea>'	,'','','',' valign="top" ')."
		-->
		<!--
		".formEntryGambar('gambar', 'gambar_old',$gambar, $gambar_old,'Gambar Barang (Max. 500Kb)',':','','','','valign="top" height="24"')."
		-->
		$entryGambar
		
		".formEntryBase2('Tanggal Dibukukan',':',
			createEntryTgl(
				'tgl_buku', 
				$tgl_buku, 
				false, 
				'tanggal bulan tahun (mis: 1 Januari 1998). Tanggal pembukuan diisi '.$Main->TGL_CUTOFF.' sebagai cut off data, kecuali pengadaan barang diatas '.$Main->TAHUN_CUTOFF, 
				'','adminForm'). //createEntryTgl('tgl_buku', false, 'tanggal bulan tahun (mis: 1 Januari 1998). Tanggal pembukuan diisi 31 Desember 2009 sebagai cut off data kecuali pengadaan barang diatas 2009', '','adminForm',1).
		'&nbsp&nbsp<span style="color: red;"></span>'
		,'','','','valign="top" height="24"')."
		
		".formEntryBase2('Tanggal Sensus Barang',':',
			createEntryTgl('tgl_sensus', $tgl_sensus, false, 
				'tanggal terakhir pengecekan fisik barang','','adminForm').//createEntryTgl('tgl_sensus', false, 'tanggal terakhir pengecekan fisik barang','','adminForm').
		'&nbsp&nbsp<span style="color: red;"></span>'
		,'','','','valign="top" height="24"').

		"<tr><td>Pengguna/Kuasa Pengguna Barang</td><td>:</td><td>".				
				"<input type='hidden' id='ref_idpenanggung' name='ref_idpenanggung' value='".$ref_idpenanggung."'> ".
				"<input type='text' id='nama2' readonly=true value='".$penanggung['nama']."' style='width:250'> &nbsp; ".
				"NIP  &nbsp;<input type='text' id='nip2' readonly=true value='".$penanggung['nip']."' style='width:150' > ".					
				"<input type='button' value='Pilih' onclick=\"pilihPenanggung()\">".
				"<input type='button' value='Clear' onclick=\"document.getElementById('nama2').value='';document.getElementById('nip2').value='';document.getElementById('jbt2').value='';document.getElementById('ref_idpenanggung').value='';\">".
		"</td></tr>".
		"<tr><td></td><td></td><td>JABATAN  &nbsp;<input type='text' id='jbt2' readonly=true value='".$penanggung['jabatan']."' style='width:380'> </td></tr>".
		"<tr><td>Pengurus Barang/Pembantu</td><td>:</td><td>".
			"<script>
				pilihPemegang = function(){		
					var me = this;		
					PegawaiPilih.fmSKPD = document.getElementById('fmSKPD').value;
					PegawaiPilih.fmUNIT = document.getElementById('fmUNIT').value;
					PegawaiPilih.fmSUBUNIT = document.getElementById('fmSUBUNIT').value;
					PegawaiPilih.fmSEKSI = document.getElementById('fmSEKSI').value;
					PegawaiPilih.el_idpegawai = 'ref_idpemegang';
					PegawaiPilih.el_nip= 'nip1';
					PegawaiPilih.el_nama= 'nama1';
					PegawaiPilih.el_jabat= 'jbt1';
					PegawaiPilih.windowSaveAfter= function(){};
					PegawaiPilih.windowShow();
				}	
				pilihPemegang2 = function(){		
					var me = this;		
					PegawaiPilih.fmSKPD = document.getElementById('fmSKPD').value;
					PegawaiPilih.fmUNIT = document.getElementById('fmUNIT').value;
					PegawaiPilih.fmSUBUNIT = document.getElementById('fmSUBUNIT').value;
					PegawaiPilih.fmSEKSI = document.getElementById('fmSEKSI').value;
					PegawaiPilih.el_idpegawai = 'ref_idpemegang2';
					PegawaiPilih.el_nip= 'nip3';
					PegawaiPilih.el_nama= 'nama3';
					PegawaiPilih.el_jabat= 'jbt3';
					PegawaiPilih.windowSaveAfter= function(){};
					PegawaiPilih.windowShow();
				}	
				pilihPenanggung = function(){		
					var me = this;		
					PegawaiPilih.fmSKPD = document.getElementById('fmSKPD').value;
					PegawaiPilih.fmUNIT = document.getElementById('fmUNIT').value;
					PegawaiPilih.fmSUBUNIT = document.getElementById('fmSUBUNIT').value;
					PegawaiPilih.fmSEKSI = document.getElementById('fmSEKSI').value;
					PegawaiPilih.el_idpegawai = 'ref_idpenanggung';
					PegawaiPilih.el_nip= 'nip2';
					PegawaiPilih.el_nama= 'nama2';
					PegawaiPilih.el_jabat= 'jbt2';
					PegawaiPilih.windowSaveAfter= function(){};
					PegawaiPilih.windowShow();
				}	
			</script>".
			"<input type='hidden' id='ref_idpemegang' name='ref_idpemegang' value='".$ref_idpemegang."'> ".
			"<input type='text' id='nama1' readonly=true value='".$pemegang['nama']."' style='width:250'> &nbsp; ".
			"NIP  &nbsp;<input type='text' id='nip1' readonly=true value='".$pemegang['nip']."' style='width:150' > ".					
			"<input type='button' value='Pilih' onclick=\"pilihPemegang()\">".
			"<input type='button' value='Clear' onclick=\"document.getElementById('nama1').value='';document.getElementById('nip1').value='';document.getElementById('jbt1').value='';document.getElementById('ref_idpemegang').value='';\">".
		"</td></tr>".
		
		
		
		"<tr><td></td><td></td><td>JABATAN  &nbsp;<input type='text' id='jbt1' readonly=true value='".$pemegang['jabatan']."' style='width:380'> </td></tr>".
		"<tr><td>Penanggung Jawab Barang</td><td>:</td><td>".
			"<input type='hidden' id='ref_idpemegang2' name='ref_idpemegang2' value='".$ref_idpemegang2."'> ".
			"<input type='text' id='nama3' readonly=true value='".$pemegang2['nama']."' style='width:250'> &nbsp; ".
			"NIP  &nbsp;<input type='text' id='nip3' readonly=true value='".$pemegang2['nip']."' style='width:150' > ".					
			"<input type='button' value='Pilih' onclick=\"pilihPemegang2()\">".
			"<input type='button' value='Clear' onclick=\"document.getElementById('nama3').value='';document.getElementById('nip3').value='';document.getElementById('jbt3').value='';document.getElementById('ref_idpemegang2').value='';\">".
		"</td></tr>".	
		"<tr><td></td><td></td><td>JABATAN  &nbsp;<input type='text' id='jbt3' readonly=true value='".$pemegang2['jabatan']."' style='width:380'> </td></tr>".
		
		"<tr><td>Gedung/Ruang</td><td>:</td><td>".
			"
			<script>
				pilihRuang = function(){		
					var me = this;		
					RuangPilih.fmSKPD = document.getElementById('fmSKPD').value;
					RuangPilih.fmUNIT = document.getElementById('fmUNIT').value;
					RuangPilih.fmSUBUNIT = document.getElementById('fmSUBUNIT').value;
					RuangPilih.fmSEKSI = document.getElementById('fmSEKSI').value;
					RuangPilih.el_idruang= 'ref_idruang';
					RuangPilih.el_nmgedung= 'nm_gedung';
					RuangPilih.el_nmruang= 'nm_ruang';
					RuangPilih.windowShow();
				}	
			</script>".
			" <input type='text' id='nm_gedung' value='".$gedung['nm_ruang']."' readonly='true' style='width:205'>".
			' &nbsp; / &nbsp; '.
			" <input type='text' id='nm_ruang' value='".$ruang['nm_ruang']."' readonly='true' style='width:205'>".
			" <input type='button' value='Pilih' onclick=\"pilihRuang()\" >".
			" <input type='button' value='Clear' onclick=\"document.getElementById('nm_gedung').value='';document.getElementById('nm_ruang').value='';document.getElementById('ref_idruang').value='';\" >".
			" <input type='hidden' id='ref_idruang' name='ref_idruang' value='".$ref_idruang."'>".
			
		"</td></tr>".		
		"<tr>
		<td colspan='2'></td>
		<td>
		$btnTampilKib
		</td>
		</tr>
		<!--<tr><td colspan=3 >
		<div style='float:right;color: red;padding:4'>( Last Update <b>$fmTglUpdate</b> by <b>$fmUID</b> )</div>
		<input type='hidden' id='fmTglUpdate' name='fmTglUpdate' value='$fmTglUpdate'>
		<input type='hidden' id='fmUID' name='fmUID' value='$fmUID'>				
		</td></tr>-->
		</table>
		
		<table width=\"100%\" height=\"100%\" >
		<tr>
		<td align=right width=90%>
		<input type=hidden name='klikDetil' onClick='adminForm.submit()' value='Detil'>
		</td>
		</tr>
		</table>
		<input type=hidden name='fmIdAwal' value='$fmIdAwal'>
		<input type=hidden name='gambar' value='$gambar'>
		
		";
		
		//tampilkan detil KIB ----------------------------------------------------------------
		$DetilKIB = "";
		$InfoIDBARANG = explode(".",$fmIDBARANG); //echo '<br> InfoIDBARANG[0]= '.$InfoIDBARANG[0];
		if($InfoIDBARANG[0]=="01"){
			
			$InfoKIB = "KIB A";
			/*$DetilKIB = " <table width=\"100%\" height=\"100%\" class=\"adminform\">
			".Penatausahaan_FormEntry_Kib(1)."
			</table>";*/
			$DetilKIB = Penatausahaan_FormEntry_Kib(1);
		}
		if($InfoIDBARANG[0]=="02"){
			$InfoKIB = "KIB B";
			$DetilKIB = Penatausahaan_FormEntry_Kib(2);
		}
		if($InfoIDBARANG[0]=="03"){
			$InfoKIB = "KIB C";
			$DetilKIB = Penatausahaan_FormEntry_Kib(3);
		}
		if($InfoIDBARANG[0]=="04"){	
			$InfoKIB = "KIB D";
			$DetilKIB = Penatausahaan_FormEntry_Kib(4);
		}
		if($InfoIDBARANG[0]=="05"){
			$InfoKIB = "KIB E";
			$DetilKIB = Penatausahaan_FormEntry_Kib(5);
		}
		if($InfoIDBARANG[0]=="06"){
			$InfoKIB = "KIB F";
			$DetilKIB = Penatausahaan_FormEntry_Kib(6);
		}
		if($InfoIDBARANG[0]=="07"){
			$InfoKIB = "KIB G";
			$DetilKIB = Penatausahaan_FormEntry_Kib(7);
		}
		$DetilKIB = "
		<table width=\"100%\" height=\"100%\" >
		<tr>
		<td align=center width=90%>
		<b>Input Data Inventaris ($InfoKIB)
			</td>
		</tr>
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminform\"> 
		$DetilKIB 
		
		</table>" ;
		
		if ($Baru==0){				
			$idbiawal = $fmIdAwal =='' || $fmIdAwal == 0? $currID : $fmIdAwal;
			//echo "=$fmIdAwal =$currID =$idbiawal";
			$idbiHidden="
			<input type='hidden' name='idbi' id='idbi' value='$currID' >	
			<input type='hidden' name='idbi_awal' id='idbi_awal' value='$idbiawal' >	
			";
			global $disModul07, $ridModul07;
			if ($disModul07 == ''){
				global $jmlTampilPLH;
				
				if($Main->MODUL_PEMELIHARAAN){
					//pemeliharaan ----------------------------------------------------------					
					$jmlTampilPLH=0;	
					$Kondisi = " where idbi_awal='$idbiawal'";		
					$Pelihara_list  = Pelihara_List('v_pemelihara', '*',$Kondisi,'','', 1,'koptable2', FALSE, 0, ($ridModul07!=''));
					$Pelihara_Script = Pelihara_createScriptJs();
					$Pemeliharaan_ = "		
					<div id='divPelihara' style='margin:8'>
					$Pelihara_Script
					<div id='divPeliharaList'>".		
					$Pelihara_list.		
					"</div></div>";
					$Pemeliharaan = 
					createBarPanel('barPelihara', 'Pemeliharaan', 25, $Pemeliharaan_);
				}
				
				if($Main->MODUL_PENGAMANAN){
					//pengaman -----------------------------------------------------------
					$Kondisi = " where idbi_awal='$idbiawal'";
					$Pengaman_List = Pengaman_List('v_pengaman', '*', $Kondisi, '', '', 1, 'koptable2', FALSE, 0,($ridModul07!=''));
					$Pengamanan_ =  
					"<div id='divPengaman' style='margin:8'>".		
					Pengaman_createScriptJs().			
					"<div id='divPengamanList'>".		
					$Pengaman_List.
					"</div></div>";
					$Pengaman = createBarPanel('barPengaman', 'Pengamanan', 25, $Pengamanan_);
				}
			}
			if($Main->MODUL_PEMANFAATAN){
				//pemanfaatan -----------------------------------------------------------
				global $disModul06, $ridModul06;
				//echo "<br>=$disModul06 =$ridModul06";
				if ($disModul06 == ''){
					$Kondisi = " where idbi_awal='$idbiawal'";
					global $Pemanfaat; //$Pemanfaat2 = new PemanfaatCls($Main);
					$PemanfaatBar =  
					"<div id='divPemanfaat' style='margin:8'>".		
					$Pemanfaat->createScriptJs().	
					"<div id='divPemanfaatList'>".
					$Pemanfaat->GetList($Kondisi, '', '', 1, 'koptable2', FALSE, 0, ($ridModul06!='')).
					"</div></div>";
					$PemanfaatBar = createBarPanel('barPemanfaat', 'Pemanfaatan', 25, $PemanfaatBar);		
				}
			}
		}
		
		
		
		
		//echo '<br> DetilKIB= '.$DetilKIB;
		return $Entry_BI.$DetilKIB.'<br>'.$idbiHidden.$Pemeliharaan.$Pengaman.$PemanfaatBar;
	}
	
	
	function Penatausahaan_FormEntry_Kib($kd){
		global $Main, $bersertifikat;
		global $fmLUAS_KIB_A, $fmLETAK_KIB_A, $alamat_kel, $alamat_kec, $alamat_a, $alamat_b, $alamat_c,
		$koordinat_gps, $koord_bidang,$alamat_kota, 
		$fmHAKPAKAI_KIB_A, $fmTGLSERTIFIKAT_KIB_A, $fmNOSERTIFIKAT_KIB_A, $fmPENGGUNAAN_KIB_A, $fmKET_KIB_A;	
		global $fmMERK_KIB_B, $fmUKURAN_KIB_B, $fmBAHAN_KIB_B, $fmPABRIK_KIB_B, $fmRANGKA_KIB_B, 
		$fmMESIN_KIB_B, $fmPOLISI_KIB_B, $fmBPKB_KIB_B, $fmKET_KIB_B ;
		global $fmKONDISI_KIB_C, $fmTINGKAT_KIB_C, $fmBETON_KIB_C, $fmLUASLANTAI_KIB_C, $fmLETAK_KIB_C, 
		$fmTGLGUDANG_KIB_C, $fmNOGUDANG_KIB_C, $fmLUAS_KIB_C, $fmSTATUSTANAH_KIB_C, 
		$fmNOKODETANAH_KIB_C,  $fmKET_KIB_C;		
		global $fmKONSTRUKSI_KIB_D, $fmPANJANG_KIB_D, $fmLEBAR_KIB_D, $fmLUAS_KIB_D, $fmALAMAT_KIB_D,
		$fmTGLDOKUMEN_KIB_D, $fmNODOKUMEN_KIB_D, $fmSTATUSTANAH_KIB_D, $fmNOKODETANAH_KIB_D, $fmKONDISI_KIB_D, 
		$fmKET_KIB_D;
		global $fmJUDULBUKU_KIB_E, $fmSPEKBUKU_KIB_E, $fmSENIBUDAYA_KIB_E, $fmSENIPENCIPTA_KIB_E, $fmSENIBAHAN_KIB_E,
		$fmJENISHEWAN_KIB_E, $fmUKURANHEWAN_KIB_E, $fmKET_KIB_E;
		global $fmBANGUNAN_KIB_F, $fmTINGKAT_KIB_F, $fmBETON_KIB_F, $fmLUAS_KIB_F, $fmLETAK_KIB_F,
		$fmTGLDOKUMEN_KIB_F, $fmNODOKUMEN_KIB_F, $fmTGLMULAI_KIB_F, $fmSTATUSTANAH_KIB_F, $fmNOKODETANAH_KIB_F, $fmKET_KIB_F;
		global $fmURAIAN_KIB_G, $fmKET_KIB_G;
		
		
		switch($kd){
			case 0:{ //BI
				$hsl="";
			break;}
			case 1:{//KIB A
				$hsl="
				<tr valign=\"top\">   
				<td width='150'>Luas (m<sup>2</sup>)</td>
				<td width='10'>:</td>
				<td>
				<!--<input type=text name='fmLUAS_KIB_A' value='$fmLUAS_KIB_A' size='15' >&nbsp;&nbsp;M<sup>2</sup> -->
				".inputFormatRibuan("fmLUAS_KIB_A")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_A'>$fmLETAK_KIB_A</textarea>
				</td>
				</tr>
				<script language=\"JavaScript\" src=\"js/wilayah.js\" type=\"text/javascript\"></script>
				".formEntryBase2('Kota/Kabupaten',':',selKabKota_txt_div($alamat_b,$alamat_kota,'',1,'Wilayah'),
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':',selKecamatan_txt_div($alamat_c,$alamat_kec,'',$alamat_b,1,'Wilayah'),
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'" 				style="width:187px">',
				'','','','valign="top" height="24"')."
				".formEntryKoordinatGPS('barang',$koordinat_gps, $koord_bidang)."
				
				<tr valign=\"\">   
				<td  colspan=3>Status Tanah :</td>
				</tr>
				<tr valign=\"\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Hak </td>
				<td>:</td>
				<td>".cmb2D('fmHAKPAKAI_KIB_A',$fmHAKPAKAI_KIB_A,$Main->StatusHakPakai,'')."</td>
				</tr>".
				createEntryBersertifikat(
					'bersertifikat',
					'fmTGLSERTIFIKAT_KIB_A',
					'fmNOSERTIFIKAT_KIB_A').
					"
				<!--
				".
				formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;Status Sertifikat ',':', 
					cmb2D_v2('bersertifikat',$bersertifikat,$Main->StatusSertifikat,'','Belum Bersertifikat','')
					,'','valign="top" height="24"')."-->
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat  </td><td>:</td>
				<td>".
				createEntryTgl("fmTGLSERTIFIKAT_KIB_A",$fmTGLSERTIFIKAT_KIB_A, $bersertifikat==1?"":"1",
					  'tanggal bulan tahun (mis: 1 Januari 1998)','','adminForm',1
					).//createEntryTgl("fmTGLSERTIFIKAT_KIB_A", $bersertifikat==1?"":"1").
				"</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat  </td><td>:</td><td>".
				txtField('fmNOSERTIFIKAT_KIB_A',$fmNOSERTIFIKAT_KIB_A,'100','20','text', $bersertifikat==1?"":"disabled").
				"</td></tr>
				<tr valign=\"\">   
				<td >Penggunaan</td>
				<td >:</td>
				<td>
				".txtField('fmPENGGUNAAN_KIB_A',$fmPENGGUNAAN_KIB_A,'100','','text',"style='width:346' required placeholder=\"belum di isi\" ")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td >:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_A'>$fmKET_KIB_A</textarea>
				</td>
				</tr>";
				//echo "<br>hasilki= $hsl";
				break;}		
			case 2:{
				$hsl = "<tr valign=\"top\">   
				<td width='150'>Merk/Type</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmMERK_KIB_B'>$fmMERK_KIB_B</textarea>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Ukuran/CC</td>
				<td width=''>:</td>
				<td>
				".txtField('fmUKURAN_KIB_B',$fmUKURAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Bahan</td>
				<td width=''>:</td>
				<td>
				".txtField('fmBAHAN_KIB_B',$fmBAHAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td  colspan=3>Nomor :</td>
				</tr>
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Pabrik </td><td>:</td><td>".txtField('fmPABRIK_KIB_B',$fmPABRIK_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Rangka </td><td>:</td><td>".txtField('fmRANGKA_KIB_B',$fmRANGKA_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Mesin </td><td>:</td><td>".txtField('fmMESIN_KIB_B',$fmMESIN_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Polisi </td><td>:</td><td>".txtField('fmPOLISI_KIB_B',$fmPOLISI_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;BPKB </td><td>:</td><td>".txtField('fmBPKB_KIB_B',$fmBPKB_KIB_B,'100','20','text','')."</td></tr>
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_B'>$fmKET_KIB_B</textarea>
				</td>
				</tr>";			
			break;}
			case 3:{//kib c
				$hsl="<tr valign=\"top\">   
				<td width='150'>Kontruksi Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmKONDISI_KIB_C',$fmKONDISI_KIB_C,$Main->Bangunan,'')."</td>
				</tr>
				<!--<tr valign=\"top\">   <td  colspan=3>Kontruksi Bangunan</td></tr>-->
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_C',$fmTINGKAT_KIB_C,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_C',$fmBETON_KIB_C,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Lantai</td>
				<td width=''>:</td>
				<td>
				".txtField('fmLUASLANTAI_KIB_C',$fmLUASLANTAI_KIB_C,'10','10','text','')." &nbsp;M<sup>2</sup>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_C'>$fmLETAK_KIB_C</textarea>
				</td>
				</tr>
				<script language=\"JavaScript\" src=\"js/wilayah.js\" type=\"text/javascript\"></script>
				".formEntryBase2('Kota/Kabupaten',':',selKabKota_txt_div($alamat_b,$alamat_kota,'',1,'Wilayah'),
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':',
				selKecamatan_txt_div($alamat_c,$alamat_kec,'',$alamat_b,1,'Wilayah'),
				'','','','valign="top" height="24"')."
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'" style="width:187px">',
				'','width=""','','valign="top" height="24"')."
				".formEntryKoordinatGPS('barang',$koordinat_gps, $koord_bidang)."
				
				<tr valign=\"top\">   
				<td colspan=3>Dokumen Gedung :</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td><td>:</td><td>".
					createEntryTgl("fmTGLGUDANG_KIB_C",$fmTGLGUDANG_KIB_C, "").//createEntryTgl("fmTGLGUDANG_KIB_C", "").	//InputKalender("fmTGLGUDANG_KIB_C")
				"</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td><td>:</td><td>
				".txtField('fmNOGUDANG_KIB_C',$fmNOGUDANG_KIB_C,'100','20','text','')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Tanah (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_C")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_C',$fmSTATUSTANAH_KIB_C,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td>Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','63','text','')."
				<span style='color: red'> kode_lokasi.kode_barang (mis: 11.10.00.17.01.83.01.01.01.11.01.06.0001)</span>	
				
				</td>
				</tr>
				<!--<tr valign=\"top\">   
				<td>Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','20','text','')."&nbsp;
				".txtField('fmNOKODELOKTANAH_KIB_C',$fmNOKODELOKTANAH_KIB_C,'100','36','text','')."
				</td>
				</tr>-->
				<!--<tr valign=\"top\"><td>Nomor Kode Tanah :</td></tr>
				<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;Kode Lokasi</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','63','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','50','text','')."
				</td>
				</tr>-->
				
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_C'>$fmKET_KIB_C</textarea>
				</td>
				</tr>";
				
			break;}
			case 4:{//kib d
				$hsl="<tr valign=\"top\">   
				<td width='150'>Konstruksi</td>
				<td width=''>:</td>
				<td>".txtField('fmKONSTRUKSI_KIB_D',$fmKONSTRUKSI_KIB_D,'50','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >Panjang (Km)</td>
				<td width=''>:</td>
				<td>				
				".inputFormatRibuan("fmPANJANG_KIB_D")."
				<!-- ".txtField('fmPANJANG_KIB_D',$fmPANJANG_KIB_D,'10','10','text','')." KM -->
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Lebar (m)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLEBAR_KIB_D")."
				<!--".txtField('fmLEBAR_KIB_D',$fmLEBAR_KIB_D,'10','10','text','')." M -->
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_D")."
				<!-- ".txtField('fmLUAS_KIB_D',$fmLUAS_KIB_D,'10','10','text','')." -->
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmALAMAT_KIB_D'>$fmALAMAT_KIB_D</textarea>
				</td>
				</tr>

				<script language=\"JavaScript\" src=\"js/wilayah.js\" type=\"text/javascript\"></script>
				".formEntryBase2('Kota/Kabupaten',':',selKabKota_txt_div($alamat_b,$alamat_kota,'',1,'Wilayah'),
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':',
				selKecamatan_txt_div($alamat_c,$alamat_kec,'',$alamat_b,1,'Wilayah'),
				'','','','valign="top" height="24"')."
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'" style="width:187px">',
				'','width=""','','valign="top" height="24"')."
				
				".formEntryKoordinatGPS('barang',$koordinat_gps, $koord_bidang)."			
				
				<tr valign=\"top\">   
				<td >Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_D", $fmTGLDOKUMEN_KIB_D, ""). //InputKalender("fmTGLDOKUMEN_KIB_D")
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_D',$fmNODOKUMEN_KIB_D,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_D',$fmSTATUSTANAH_KIB_D,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>".txtField('fmNOKODETANAH_KIB_D',$fmNOKODETANAH_KIB_D,'100','50','text','')."</td>
				</tr>
				<!--<tr valign=\"top\">   
				<td >Kondisi</td>
				<td width=''>:</td>
				<td>".cmb2D('fmKONDISI_KIB_D',$fmKONDISI_KIB_D,$Main->KondisiBarang,'')."</td>
				</tr>-->
				<tr valign=\"top\">   
				<td>Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_D'>$fmKET_KIB_D</textarea>
				</td>
				</tr>";
				
			break;}
			case 5:{//kib e
				$hsl="<tr valign=\"top\" height= '24'>   
				<td  colspan = '3'>Buku Perpustakaan</td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta</td>
				<td width='10'>:</td>
				<td>".txtField('fmJUDULBUKU_KIB_E',$fmJUDULBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi</td>
				<td width=''>:</td>
				<td>".txtField('fmSPEKBUKU_KIB_E',$fmSPEKBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3'>Barang bercorak Kesenian/Kebudayaan  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBUDAYA_KIB_E',$fmSENIBUDAYA_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Pencipta</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIPENCIPTA_KIB_E',$fmSENIPENCIPTA_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Bahan</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBAHAN_KIB_E',$fmSENIBAHAN_KIB_E,'100','50','text','')."</td>
				</tr>
				
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3' >Hewan Ternak  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Jenis</td>
				<td width=''>:</td>
				<td>".txtField('fmJENISHEWAN_KIB_E',$fmJENISHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td '>&nbsp;&nbsp;&nbsp;&nbsp;Ukuran</td>
				<td width=''>:</td>
				<td>".txtField('fmUKURANHEWAN_KIB_E',$fmUKURANHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_E'>$fmKET_KIB_E</textarea>
				</td>
				</tr>";		
			break;}
			case 6:{//kib f
				$hsl="<tr valign=\"top\">   
				<td width='150'>Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmBANGUNAN_KIB_F',$fmBANGUNAN_KIB_F,$Main->Bangunan,'')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td  colspan=3>Kontruksi Bangunan</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_F',$fmTINGKAT_KIB_F,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_F',$fmBETON_KIB_F,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_F")."
				<!-- ".txtField('fmLUAS_KIB_F',$fmLUAS_KIB_F,'10','10','text','')."  -->
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_F'>$fmLETAK_KIB_F</textarea>
				</td>
				</tr>
				<script language=\"JavaScript\" src=\"js/wilayah.js\" type=\"text/javascript\"></script>
				".formEntryBase2('Kota/Kabupaten',':',selKabKota_txt_div($alamat_b,$alamat_kota,'',1,'Wilayah') 
//				selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI)
				,
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':',
				selKecamatan_txt_div($alamat_c,$alamat_kec,'',$alamat_b,1,'Wilayah')

//				'<input type="text" name="alamat_kec" value="'.$alamat_kec.'">'
				,
				'','','','valign="top" height="24"')."

				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'" style="width:187px">',
				'','width=""','','valign="top" height="24"')."
				".formEntryKoordinatGPS('barang',$koordinat_gps, $koord_bidang)."	
				
				<tr valign=\"top\">   
				<td width='150'>Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td >:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_F", $fmTGLDOKUMEN_KIB_F, ""). //<!--<td>".InputKalender("fmTGLDOKUMEN_KIB_F")."</td>-->
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_F',$fmNODOKUMEN_KIB_F,'100','50','text','')." </td>
				</tr>
				
				<tr valign=\"top\">   
				<td >Tanggal/Bln./Thn. mulai</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLMULAI_KIB_F", $fmTGLMULAI_KIB_F, "").//<!--<td>".InputKalender("fmTGLMULAI_KIB_F")."</td>-->
				"</td>				
				</tr>	
				
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_F',$fmSTATUSTANAH_KIB_F,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_F',$fmNOKODETANAH_KIB_F,'100','50','text','')."
				</td>
				</tr>			<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_F'>$fmKET_KIB_F</textarea>
				</td>
				</tr>					";
				
				break;}		
			case 7:{//kib g
				$hsl="<tr valign=\"top\" height= '24'>   
								
				<tr valign=\"top\">   
				<td width='150'>Uraian</td>
				<td width='10'>:</td>
				<td>".txtField('fmURAIAN_KIB_G',$fmURAIAN_KIB_G,'100','59','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_G'>$fmKET_KIB_G</textarea>
				</td>
				</tr>";		
			break;}
			
		}
		
		return $hsl;
	}
	
	
	function Penatausahaan_GetData($idbi, $hanyaStatusInv = TRUE ){
		global $cek, $errmsg, $KondisiEditKIB;
		global $kdBarang, $kdRekening, $nmRekening, $nmBarang,		
		$fmIDBARANG, $fmIDBARANG_old, $fmNMBARANG, $fmREGISTER,	$fmREGISTER_old,		
		$fmMEREK, $fmJUMLAHBARANG, $fmJUMLAHHARGA, $fmHARGABARANG, $fmSATUAN, $fmASALUSUL, $fmKONDISIBARANG,
		$fmIDREKENING, $fmNMREKENING,	
		$fmWIL, //$fmBIDANG, 
		$fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmSKPD_old, $fmUNIT_old, $fmSUBUNIT_old,	$fmSEKSI_old,		
		$fmTAHUNPEROLEHAN, $fmTAHUNPEROLEHAN_old, $fmTAHUNANGGARAN_old, $fmKET, $fmID,		
		$dokumen_ket, $dokumen, $dokumen_file, $dokumen_file_old, $gambar, $gambar_old,
		$nilai_appraisal, $tgl_buku;
		global $fmLUAS_KIB_A, $fmLETAK_KIB_A, $fmHAKPAKAI_KIB_A, $fmTGLSERTIFIKAT_KIB_A,
		$fmNOSERTIFIKAT_KIB_A, $fmPENGGUNAAN_KIB_A, $fmKET_KIB_A, 
		$alamat_a, $alamat_b,$alamat_c, $alamat_kec, $alamat_kel, $koordinat_gps, $koord_bidang,
		$bersertifikat,$alamat_kota;
		global $fmMERK_KIB_B, $fmUKURAN_KIB_B, $fmBAHAN_KIB_B, $fmPABRIK_KIB_B,
		$fmRANGKA_KIB_B, $fmMESIN_KIB_B, $fmPOLISI_KIB_B, $fmBPKB_KIB_B, $fmKET_KIB_B;
		global $fmKONDISI_KIB_C, $fmTINGKAT_KIB_C, $fmBETON_KIB_C, $fmLUASLANTAI_KIB_C, 
		$fmLETAK_KIB_C, $fmTGLGUDANG_KIB_C, $fmNOGUDANG_KIB_C, 
		$fmLUAS_KIB_C, $fmSTATUSTANAH_KIB_C, $fmNOKODETANAH_KIB_C, $fmKET_KIB_C;
		//$alamat_a, $alamat_b, $alamat_kec, $alamat_kel, $koordinat_gps;
		global $fmKONSTRUKSI_KIB_D, $fmPANJANG_KIB_D, $fmLEBAR_KIB_D, $fmLUAS_KIB_D, 
		$fmALAMAT_KIB_D, $fmTGLDOKUMEN_KIB_D, $fmNODOKUMEN_KIB_D, $fmSTATUSTANAH_KIB_D, 
		$fmNOKODETANAH_KIB_D, $fmKONDISI_KIB_D, $fmKET_KIB_D;
		//$alamat_a, $alamat_b, $alamat_kec, $alamat_kel, $koordinat_gps; 
		global $fmJUDULBUKU_KIB_E, $fmSPEKBUKU_KIB_E, $fmSENIBUDAYA_KIB_E, $fmSENIPENCIPTA_KIB_E, 
		$fmSENIBAHAN_KIB_E, $fmJENISHEWAN_KIB_E, $fmUKURANHEWAN_KIB_E, $fmKET_KIB_E;
		global $fmBANGUNAN_KIB_F, $fmTINGKAT_KIB_F, $fmBETON_KIB_F, $fmLUAS_KIB_F, 
		$fmLETAK_KIB_F, $fmTGLDOKUMEN_KIB_F, $fmNODOKUMEN_KIB_F, $fmTGLMULAI_KIB_F, 
		$fmSTATUSTANAH_KIB_F, $fmNOKODETANAH_KIB_F, $fmKET_KIB_F;
		global $fmURAIAN_KIB_G, $fmKET_KIB_G;
		//$alamat_a, $alamat_b, $alamat_kec, $alamat_kel, $koordinat_gps; 
		global $fmUID, $fmTglUpdate, $tgl_sensus, $fmIdAwal;
		global $fmSTATUSBARANG;
		global $tahun_sensus, $ref_idpemegang, $ref_idpemegang2, $ref_idpenanggung, $ref_idruang;
		
		
		$sqry = "select * from buku_induk where id='$idbi'"; $cek .= '<br> qry edit= '.$sqry;
		$Qry = mysql_query($sqry);
		$isi = mysql_fetch_array($Qry);
		
		/*if (($hanyaStatusInv ) && ($isi['status_barang'] != 1)){	
			$errmsg = 'Hanya barang dengan status Inventaris yang dapat di Ubah'; 
			}*/
		
		if ($errmsg == ''){	
			$kdBarang 	= $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
			$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
			$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
			$nmBarang	= mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
			
			$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
			$fmIDBARANG_old = $fmIDBARANG;	$cek = 'idbarangold = '.$fmIDBARANG;
			$fmNMBARANG = "{$nmBarang['nm_barang']}";
			$fmREGISTER = "{$isi['noreg']}";
			$fmREGISTER_old = "{$isi['noreg']}";
			
			$fmMEREK 	= "{$isi['merk_barang']}";
			$fmJUMLAHBARANG	= "{$isi['jml_barang']}";
			$fmJUMLAHHARGA 	= $isi['harga']*$isi['jml_barang'];
			$fmHARGABARANG 	= "{$isi['harga']}";
			$fmSATUAN 	= "{$isi['satuan']}";
			$fmASALUSUL = $isi['asal_usul'];
			$fmSTATUSBARANG = $isi['status_barang'];
			$fmKONDISIBARANG= $isi['kondisi'];
			$fmIDREKENING 	= $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
			$fmNMREKENING 	= "{$nmRekening['nm_rekening']}";
			
			$fmWIL = $isi['b']; $cek .= '<br> fmWil after_edit = '.$fmWIL;
			//$fmBIDANG = $isi['c']; //echo  '<br> fmBIDANG after_edit = '.$fmBIDANG;//?bidang
			$fmSKPD  =  $isi['c'];
			$fmUNIT = $isi['d'];//echo '<br> fmUNIT after_edit = '.$fmUNIT;		
			$fmSUBUNIT = $isi['e'];//echo '<br> fmSUBUNIT after_edit = '.$fmSUBUNIT;
			$fmSEKSI = $isi['e1']; //echo '<br> $fmSEKSI after getdata = '.$fmSEKSI;
			
			$fmSKPD_old = $isi['c']; $cek .= '<br> fmSKPD_old after_edit = '.$fmSKPD_old;
			$fmUNIT_old = $isi['d']; $cek .= '<br> fmUNIT_old after_edit = '.$fmUNIT_old;
			$fmSUBUNIT_old = $isi['e']; $cek .= '<br> fmSUBUNIT_old after_edit = '.$fmSUBUNIT_old;
			$fmSEKSI_old = $isi['e1']; 
						
			$fmTAHUNPEROLEHAN = $isi['thn_perolehan'];
			$fmTAHUNPEROLEHAN_old = $isi['thn_perolehan'];
			$fmTAHUNANGGARAN_old = $isi['thn_perolehan'];
			$fmKET = "{$isi['ket']}";
			$fmID = "{$isi['id']}";
			
			$dokumen_ket = $isi['dokumen_ket'];
			$dokumen = $isi['dokumen']; $cek .= '<br> dokumen = '.$dokumen;
			$dokumen_file = $isi['dokumen_file']; $cek .= '<br> dokumen_file = '.$dokumen_file;
			$dokumen_file_old = $dokumen_file;
			$gambar= $isi['gambar']; //echo'<br> gambar= '.$gambar;
			$gambar_old = $isi['gambar'];
			$nilai_appraisal = $isi['nilai_appraisal'];	$cek .= '<br> nil appraisal2= '.$nilai_appraisal;
			
			$tgl_buku= $isi['tgl_buku'];	
			$fmUID=$isi['uid'];
			$fmTglUpdate=$isi['tgl_update'];	
			$tgl_sensus= $isi['tgl_sensus'];
			$fmIdAwal = $isi['idawal']=='' || $isi['idawal']==0 ? $isi['id'] : $isi['idawal']; //echo "<br>fmIdAwal2=$fmIdAwal ={$isi['id']} <br>";
			$tahun_sensus = $isi['tahun_sensus']; 
			$ref_idpemegang = $isi['ref_idpemegang'];
			$ref_idpemegang2 = $isi['ref_idpemegang2'];
			$ref_idpenanggung = $isi['ref_idpenanggung'];

			$ref_idruang = $isi['ref_idruang'];

			$KondisiEditKIB = //" idbi = '$idbi'		";
			"
			a1= '{$isi['a1']}' and 
			a = '{$isi['a']}' and 
			b = '{$isi['b']}' and 
			c = '{$isi['c']}' and 
			d = '{$isi['d']}' and 
			e = '{$isi['e']}' and
			e1 = '{$isi['e1']}' and 
			f = '{$isi['f']}' and 
			g = '{$isi['g']}' and 
			h = '{$isi['h']}' and 
			i = '{$isi['i']}' and 
			j = '{$isi['j']}' and 
			noreg = '{$isi['noreg']}' and 
			tahun = '{$isi['tahun']}' ";
			
			switch( $isi['f'] ){
				case '01': {
					$sqry= "select * from kib_a where $KondisiEditKIB"; //echo '<br> qry a='.$sqry;
					$Qry = mysql_query($sqry);
					$isi = mysql_fetch_array($Qry);
					$fmLUAS_KIB_A = $isi['luas'];
					$fmLETAK_KIB_A = $isi['alamat'];
					$fmHAKPAKAI_KIB_A = $isi['status_hak'];
					$fmTGLSERTIFIKAT_KIB_A = $isi['sertifikat_tgl'];
					$fmNOSERTIFIKAT_KIB_A = $isi['sertifikat_no'];
					$fmPENGGUNAAN_KIB_A = $isi['penggunaan'];
					$fmKET_KIB_A = $isi['ket'];
					$alamat_a = $isi['alamat_a'];
					$alamat_b = $isi['alamat_b'];
					$alamat_c = $isi['alamat_c'];
					$alamat_kec = $isi['alamat_kec'];
					$alamat_kel = $isi['alamat_kel'];
					$alamat_kota = $isi['kota'];

					$koordinat_gps = $isi['koordinat_gps'];
					$koord_bidang = $isi['koord_bidang'];
					$bersertifikat = $isi['bersertifikat'];
					break;
				}
				case '02':{
					$sqryb= "select * from kib_b where $KondisiEditKIB"; $cek .= '<br> qry b='.$sqryb;
					$Qry = mysql_query($sqryb);
					$isi = mysql_fetch_array($Qry);
					$fmMERK_KIB_B = $isi['merk'];
					$fmUKURAN_KIB_B = $isi['ukuran'];
					$fmBAHAN_KIB_B = $isi['bahan'];
					$fmPABRIK_KIB_B = $isi['no_pabrik'];
					$fmRANGKA_KIB_B = $isi['no_rangka'];
					$fmMESIN_KIB_B = $isi['no_mesin'];
					$fmPOLISI_KIB_B = $isi['no_polisi'];
					$fmBPKB_KIB_B = $isi['no_bpkb'];
					$fmKET_KIB_B = $isi['ket'];
					break;
				}
				case '03':{
					$Qry = mysql_query("select * from kib_c where $KondisiEditKIB");
					$isi = mysql_fetch_array($Qry);
					$fmKONDISI_KIB_C=$isi['kondisi'];//$isi['kondisi_bangunan'];
					$fmTINGKAT_KIB_C=$isi['konstruksi_tingkat'];
					$fmBETON_KIB_C=$isi['konstruksi_beton'];
					$fmLUASLANTAI_KIB_C=$isi['luas_lantai'];
					$fmLETAK_KIB_C=$isi['alamat'];
					$fmTGLGUDANG_KIB_C=$isi['dokumen_tgl'];
					$fmNOGUDANG_KIB_C=$isi['dokumen_no'];
					$fmLUAS_KIB_C=$isi['luas'];
					$fmSTATUSTANAH_KIB_C=$isi['status_tanah'];
					$fmNOKODETANAH_KIB_C=$isi['kode_tanah'];				
					$fmKET_KIB_C=$isi['ket'];
					$alamat_a = $isi['alamat_a'];
					$alamat_b = $isi['alamat_b'];
					$alamat_c = $isi['alamat_c'];
					$alamat_kec = $isi['alamat_kec'];
					$alamat_kel = $isi['alamat_kel'];
					$alamat_kota = $isi['kota'];
					
					$koordinat_gps = $isi['koordinat_gps'];
					$koord_bidang = $isi['koord_bidang'];
					break;
				}
				case '04':{
					$Qry = mysql_query("select * from kib_d where $KondisiEditKIB");
					$isi = mysql_fetch_array($Qry);
					$fmKONSTRUKSI_KIB_D=$isi['konstruksi'];
					$fmPANJANG_KIB_D=$isi['panjang'];
					$fmLEBAR_KIB_D=$isi['lebar'];
					$fmLUAS_KIB_D=$isi['luas'];
					$fmALAMAT_KIB_D=$isi['alamat'];
					$fmTGLDOKUMEN_KIB_D=$isi['dokumen_tgl'];
					$fmNODOKUMEN_KIB_D=$isi['dokumen_no'];
					$fmSTATUSTANAH_KIB_D=$isi['status_tanah'];
					$fmNOKODETANAH_KIB_D=$isi['kode_tanah'];
					$fmKONDISI_KIB_D=$isi['kondisi'];
					$fmKET_KIB_D=$isi['ket'];
					$alamat_a = $isi['alamat_a'];
					$alamat_b = $isi['alamat_b'];
					$alamat_c = $isi['alamat_c'];
					$alamat_kec = $isi['alamat_kec'];
					$alamat_kel = $isi['alamat_kel'];
					$alamat_kota = $isi['kota'];
					$koordinat_gps = $isi['koordinat_gps'];
					$koord_bidang = $isi['koord_bidang'];
					break;
				}
				case '05':{
					$Qry = mysql_query("select * from kib_e where $KondisiEditKIB");
					$isi = mysql_fetch_array($Qry);
					$fmJUDULBUKU_KIB_E=$isi['buku_judul'];
					$fmSPEKBUKU_KIB_E=$isi['buku_spesifikasi'];
					$fmSENIBUDAYA_KIB_E=$isi['seni_asal_daerah'];
					$fmSENIPENCIPTA_KIB_E=$isi['seni_pencipta'];
					$fmSENIBAHAN_KIB_E=$isi['seni_bahan'];
					$fmJENISHEWAN_KIB_E=$isi['hewan_jenis'];
					$fmUKURANHEWAN_KIB_E=$isi['hewan_ukuran'];
					$fmKET_KIB_E=$isi['ket'];
					break;
				}
				case '06':{
					$Qry = mysql_query("select * from kib_f where $KondisiEditKIB");
					$isi = mysql_fetch_array($Qry);
					$fmBANGUNAN_KIB_F=$isi['bangunan'];
					$fmTINGKAT_KIB_F=$isi['konstruksi_tingkat'];
					$fmBETON_KIB_F=$isi['konstruksi_beton'];
					$fmLUAS_KIB_F=$isi['luas'];
					$fmLETAK_KIB_F=$isi['alamat'];
					$fmTGLDOKUMEN_KIB_F=$isi['dokumen_tgl'];
					$fmNODOKUMEN_KIB_F=$isi['dokumen_no'];
					$fmTGLMULAI_KIB_F=$isi['tmt'];
					$fmSTATUSTANAH_KIB_F=$isi['status_tanah'];
					$fmNOKODETANAH_KIB_F=$isi['kode_tanah'];
					$fmKET_KIB_F=$isi['ket'];
					$alamat_a = $isi['alamat_a'];
					$alamat_b = $isi['alamat_b'];
					$alamat_c = $isi['alamat_c'];
					$alamat_kec = $isi['alamat_kec'];
					$alamat_kel = $isi['alamat_kel'];
					$alamat_kota = $isi['kota'];
					$koordinat_gps = $isi['koordinat_gps'];
					$koord_bidang = $isi['koord_bidang'];
					break;
				}
				case '07':{
					$Qry = mysql_query("select * from kib_g where $KondisiEditKIB");
					$isi = mysql_fetch_array($Qry);
					$fmURAIAN_KIB_G=$isi['uraian'];
					$fmKET_KIB_G=$isi['ket'];
					break;
				}
			}
			
		}
		
		
	}
	
/*	
	function Penatausahaan_genTableRowKibC($clRow, $clGaris, $tampilCheckbox, $arr){
		return 
		"<tr class='$clRow' valign='top'>
		<td class=\"$clGaris\" align=center>".$arr[0]."</td>
		$tampilCheckbox
		<td class=\"$clGaris\" align=center>".$arr[1]."</td>
		<td class=\"$clGaris\" align=center>".$arr[2]."</td>
		<td class=\"$clGaris\">".$arr[3]."</td>
		<td class=\"$clGaris\" align=center>".$arr[4]."</td>
		<td class=\"$clGaris\">".$arr[5]."</td>
		<td class=\"$clGaris\">".$arr[6]."</td>
		<td class=\"$clGaris\">".$arr[7]."</td>
		<td class=\"$clGaris\" align=center>".$arr[8]."</td>
		<td class=\"$clGaris\" style='width:200'>".$arr[9]."</td>
		<td class=\"$clGaris\" align=center  style=''>".$arr[10]."</td>
		<!--<td class=\"$clGaris\">".$arr[11]."</td>-->
		<td class=\"$clGaris\" align=center>".$arr[11]."</td>
		<td class=\"$clGaris\">".$arr[12]."</td>
		<td class=\"$clGaris\" align=center>".$arr[13]."</td>
		<td class=\"$clGaris\" style='width:100'>".$arr[14]."</td>
		<td class=\"$clGaris\" align=right>".$arr[15]."</td>
		<td class=\"$clGaris\">".$arr[16]."</td>			
		</tr>";
		
	}
*/

	function Penatausahaan_genTableRowKibC($clRow, $clGaris, $tampilCheckbox,  $isi, $no, $tampilCbxKeranjang='', $tipe=''){		
		global $Main;
		
		$tampilKodeTanah = 
			substr($isi['kode_tanah'],0,12)."<BR>".
			substr($isi['kode_tanah'],12,12)."<BR>".
			substr($isi['kode_tanah'],24,12)."<BR>".
			substr($isi['kode_tanah'],36,4);
		$tampilIdBrg =
			substr($isi['id_barang'],0,9)."<BR>".
			substr($isi['id_barang'],9,6);
		
		$vKondisi = ifempty($Main->KondisiBarang[$isi['kondisi']-1][1],'-');
		$vTingkat = ifempty($Main->Tingkat[$isi['konstruksi_tingkat']-1][1],'-');
		$vKonstruksi = ifempty($Main->Beton [$isi['konstruksi_beton']-1][1],'-');
		$vluastanah  = ( empty($isi['luas_lantai']) ? "-": number_format($isi['luas_lantai'],2,',','.') );
		$vdok = ifemptyTgl( TglInd($isi['dokumen_tgl']),'-').'<br>'.ifempty($isi['dokumen_no'],'-');
		
$nm_kota="";
		$nm_kec ="";
		$get = mysql_fetch_array(mysql_query(
				"select * from ref_kotakec where kd_kota='".$isi['alamat_b']."'  and kd_kec='0'"
			));		
			if($get['nm_wilayah']<>'') $nm_kota = $get['nm_wilayah'];
		$nm_kota = $nm_kota!=""?$nm_kota: $isi['kota'];
		
		$get = mysql_fetch_array(mysql_query(
		"select * from ref_kotakec where kd_kota='".$isi['alamat_b']."'  and kd_kec='".$isi['alamat_c']."'"
			));		
			if($get['nm_wilayah']<>'') $nm_kec = $get['nm_wilayah'];
		$nm_kec = $nm_kec!="" ? $nm_kec: $isi['alamat_kec'];
		
		$alm = '';
		$alm .= ifempty($isi['alamat'],'-');		
		$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
		$alm .= $nm_kec != ''? '<br>Kec. '.$nm_kec : '';
		$alm .= $isi['alamat_kota'] != ''? '<br>'.$nm_kota : '';
		
		$vluas = ( empty($isi['luas']) ? "-": number_format($isi['luas'],2, ',', '.' ) );
		$vStatus = ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-');
		$vAsal = ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-');
		
		$ket = ifempty($isi['ket'],'-');					
		$ket .= tampilNmSubUnit($isi);
		
		$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
		
		$status_penguasaan = 
			"<img src='images/checkbox.png'> Digunakan<br>".
			"<img src='images/checkbox.png'> Dimanfaatkan<br>".
			"<img src='images/checkbox.png'> Iddle<br>".
			"<img src='images/checkbox.png'> Dikuasai Pihak Ketiga<br>".
			"<img src='images/checkbox.png'> Sengketa<br>".
			"";
		$kondisi_kk =
			"<img src='images/checkbox.png'> B<br>".
			"<img src='images/checkbox.png'> KB<br>".
			"<img src='images/checkbox.png'> RB<br>".
			"<img src='images/checkbox.png'> TD<br>".							
			"";
						
		
		if($tipe=='kertaskerja'){ 
			return 
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center>".$no."</td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center>".$tampilIdBrg."</td>
				<td class=\"$clGaris\" align=center><div class='nfmt3'>".$isi['noreg']."</div></td>
				<td class=\"$clGaris\">".$isi['nm_barang']."</td>
				<td class=\"$clGaris\" align=center>".$isi['tahun']."</td>
				<td class=\"$clGaris\">".$vKondisi."</td>
				<td class=\"$clGaris\">".$vTingkat."</td>
				<td class=\"$clGaris\">".$vKonstruksi."</td>
				<td class=\"$clGaris\" align=right>".$vluastanah."</td>
				<td class=\"$clGaris\" style='width:200'>".$alm."</td>
				<td class=\"$clGaris\" align=center  style=''>".$vdok."</td>		
				<td class=\"$clGaris\" align=right>".$vluas."</td>
				<td class=\"$clGaris\">".$vStatus."</td>
				<td class=\"$clGaris\" align=center>".$vKodeTanah."</td>
				<td class=\"$clGaris\" style='width:100'>".$vAsal."</td>
				<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
				<td class=\"$clGaris\">".$kondisi_kk."</td>".
				"<td class=\"$clGaris\" style='min-width:120'>".$status_penguasaan."</td>".
				"<td class=\"$clGaris\" style='min-width:50'></td>".
				$tampilCbxKeranjang.			
				$isi['vBidang'].
				"</tr>";
		}else{
			return 
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center>".$no."</td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center>".$tampilIdBrg."</td>
				<td class=\"$clGaris\" align=center><div class='nfmt3'>".$isi['noreg']."</div></td>
				<td class=\"$clGaris\">".$isi['nm_barang']."</td>
				<td class=\"$clGaris\" align=center>".$isi['tahun']."</td>
				<td class=\"$clGaris\">".$vKondisi."</td>
				<td class=\"$clGaris\">".$vTingkat."</td>
				<td class=\"$clGaris\">".$vKonstruksi."</td>
				<td class=\"$clGaris\" align=right>".$vluastanah."</td>
				<td class=\"$clGaris\" style='width:200'>".$alm."</td>
				<td class=\"$clGaris\" align=center  style=''>".$vdok."</td>		
				<td class=\"$clGaris\" align=right>".$vluas."</td>
				<td class=\"$clGaris\">".$vStatus."</td>
				<td class=\"$clGaris\" align=center>".$vKodeTanah."</td>
				<td class=\"$clGaris\" style='min-width:100'>".$vAsal."</td>
				<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
				<td class=\"$clGaris\">".$ket."</td>".
				$tampilCbxKeranjang.			
				$isi['vBidang'].
				"</tr>";
		}
		
	}

	
	function Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, $arr){
		//$colspan = $isTampilCheckbox? 13 :12;
		return 
		"<tr class='$clRow' valign='top'>			
		<td class=\"$clGaris\" colspan=$colspan>".$arr[0]."</td>
		<td class=\"$clGaris\" align=right>".$arr[1]."</td>
		<td class=\"$clGaris\"></td>											
		</tr>";
	}
	function Penatausahaan_genTableRowBIJml($clRow, $clGaris, $tampilCheckbox, $arr){
		return 		
		"<tr class='$clRow' valign='top'>
		<td class=\"$clGaris\" align=center colspan='$colspan'>".$arr[0]."</td>			
		<td class=\"$clGaris\" align=right>".$arr[1]."</td>	
		<td class=\"$clGaris\"></td>		
		</tr>";	
	}
/*	
	function Penatausahaan_genTableRowKibA($clRow, $clGaris, $tampilCheckbox, $arr){
		return 
		"<tr class='$clRow' valign='top'>
		<td class=\"$clGaris\" align=center>".$arr[0]."</td>						
		$tampilCheckbox
		<td class=\"$clGaris\" align=center>".$arr[1]."</td>
		<td class=\"$clGaris\" align=center>".$arr[2]."</td>
		<td class=\"$clGaris\" align=left>".$arr[3]."</td>
		<td class=\"$clGaris\" align=right>".$arr[4]."</td>
		<td class=\"$clGaris\" align=center>".$arr[5]."</td>
		<td class=\"$clGaris\">".$arr[6]."</td>
		<td class=\"$clGaris\">".$arr[7]."</td>
		<td class=\"$clGaris\">".$arr[8]."</td>
		<td class=\"$clGaris\">".$arr[9]."</td>
		<td class=\"$clGaris\">".$arr[10]."</td>
		<td class=\"$clGaris\">".$arr[11]."</td>
		<td class=\"$clGaris\" align=right>".$arr[12]."</td>
		<td class=\"$clGaris\">".$arr[13]."</td>											
		</tr>";
	}
*/

	function Penatausahaan_genTableRowKibA($clRow, $clGaris, $tampilCheckbox,  $isi, $no=0, $tampilCbxKeranjang='',$tipe=''){
		global $Main;
		$nm_kota="";
		$nm_kec ="";
		$get = mysql_fetch_array(mysql_query(
				"select * from ref_kotakec where kd_kota='".$isi['alamat_b']."'  and kd_kec='0'"
			));		
			if($get['nm_wilayah']<>'') $nm_kota = $get['nm_wilayah'];
		$nm_kota = $nm_kota!=""?$nm_kota: $isi['kota'];
		
		$get = mysql_fetch_array(mysql_query(
		"select * from ref_kotakec where kd_kota='".$isi['alamat_b']."'  and kd_kec='".$isi['alamat_c']."'"
			));		
			if($get['nm_wilayah']<>'') $nm_kec = $get['nm_wilayah'];
		$nm_kec = $nm_kec!="" ? $nm_kec: $isi['alamat_kec'];
		
		$alm = '';
		$alm .= ifempty($isi['alamat'],'-');		
		$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
		$alm .= $nm_kec != ''? '<br>Kec. '.$nm_kec : '';
		$alm .= $isi['alamat_kota'] != ''? '<br>'.$nm_kota : '';
		
		$sthakpakai = ifempty( $Main->StatusHakPakai[$isi['status_hak']-1][1], '-');
		$sertifikatTgl = ( empty($isi['sertifikat_tgl'] ) || $isi['sertifikat_tgl'] =='0000-00-00' ? "-" : TglInd($isi['sertifikat_tgl']) );
		$sertifikatNo = ifempty( $isi['sertifikat_no'], '-');
		$penggunaan = ifempty( $isi['penggunaan'], '-');
		$asal = ifempty( $Main->AsalUsul[$isi['asal_usul']-1][1], '-')."<br>/".ifempty( $Main->StatusBarang[$isi['status_barang']-1][1], '-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-');

		
		$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');


					
		$ket 	= ifempty( $isi['ket'], '-');
		
		$status_penguasaan = 
			"<img src='images/checkbox.png'> Digunakan<br>".
			"<img src='images/checkbox.png'> Dimanfaatkan<br>".
			"<img src='images/checkbox.png'> Iddle<br>".
			"<img src='images/checkbox.png'> Dikuasai Pihak Ketiga<br>".
			"<img src='images/checkbox.png'> Sengketa<br>".
			"";
		$dttd = 
			"<img src='images/checkbox.png'> DT<br>".
			"<img src='images/checkbox.png'> TD<br>".
			"";
		
		if($tipe=='kertaskerja'){ 
			return 
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center>".$no."</td>						
				$tampilCheckbox
				<td class=\"$clGaris\" align=center>".$isi['id_barang']."</td>
				<td class=\"$clGaris\" align=center><div class='nfmt3'>".$isi['noreg']."</div></td>
				<td class=\"$clGaris\" align=left>".$isi['nm_barang']."</td>
				<td class=\"$clGaris\" align=right>".number_format($isi['luas'],2,',','.' )."</td>
				
				<td class=\"$clGaris\" align=center>".$isi['thn_perolehan']."</td>
				<td class=\"$clGaris\">".$alm."</td>
				<td class=\"$clGaris\">".$sthakpakai."</td>
				<td class=\"$clGaris\">".$sertifikatTgl."</td>
				<td class=\"$clGaris\">".$sertifikatNo."</td>
				<td class=\"$clGaris\">".$penggunaan."</td>
				<td class=\"$clGaris\">".$asal."</td>
				<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
				<td class=\"$clGaris\"  style='min-width:120'>".$status_penguasaan."</td>".
				"<td class=\"$clGaris\">".$dttd."</td>".
				"<td class=\"$clGaris\">".$keterangan."</td>".
				$isi['vBidang'].	
				$tampilCbxKeranjang.
				"</tr>";
		}else{
			
		
			return 
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center>".$no."</td>						
				$tampilCheckbox
				<td class=\"$clGaris\" align=center>".$isi['id_barang']."</td>
				<td class=\"$clGaris\" align=center><div class='nfmt3'>".$isi['noreg']."</div></td>
				<td class=\"$clGaris\" align=left>".$isi['nm_barang']."</td>
				<td class=\"$clGaris\" align=right>".number_format($isi['luas'],2,',','.' )."</td>
				
				<td class=\"$clGaris\" align=center>".$isi['thn_perolehan']."</td>
				<td class=\"$clGaris\">".$alm."</td>
				<td class=\"$clGaris\">".$sthakpakai."</td>
				<td class=\"$clGaris\">".$sertifikatTgl."</td>
				<td class=\"$clGaris\">".$sertifikatNo."</td>
				<td class=\"$clGaris\">".$penggunaan."</td>
				<td class=\"$clGaris\">".$asal."</td>
				<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
				<td class=\"$clGaris\">".$ket."</td>".
				$isi['vBidang'].	
				$tampilCbxKeranjang.
				"</tr>";
		}
	}
		
	function Penatausahaan_genTableRowDet($fmKIB,$clRow, $clGaris, $tampilCheckbox, $arr){
		switch($fmKIB){
			case '01':{
				return "<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center></td>						
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\" align=left>".$arr[1]."</td>
				
				<td class=\"$clGaris\" align=right></td>
				<td class=\"$clGaris\" align=center>".$arr[2]."</td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=right>".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>											
				</tr>";
				break;
			}
			case '02':{
				return "<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center></td>	
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\" align=left>".$arr[1]."</td>
				<td class=\"$clGaris\" align=left></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=left>".$arr[2]."</td>
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" style='width:100'></td>
				<td class=\"$clGaris\" align=right>".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>					
				</tr>";
				break;
			}
			case '03':{
				return 
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center></td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=center>".$arr[2]."</td>
				<td class=\"$clGaris\" colspan=5>".$arr[1]."</td>			
				<td class=\"$clGaris\" align=center  style='width:75'></td>
				<td class=\"$clGaris\"></td> 
				<!--<td class=\"$clGaris\" align=center></td>-->
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=center ></td>
				<td class=\"$clGaris\" style='width:100'></td>
				<td class=\"$clGaris\" align=right>".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>			
				</tr>";
				break;
			}
			case '04':{
				return 
				"<tr class='$clRow' valign='top'>
				<td class='$clGaris' align=center></td>
				$tampilCheckbox
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' align=center>".$arr[0]."</td>
				<td class='$clGaris' >".$arr[1]."</td>
				<td class='$clGaris' >".$arr[2]."</td>
				<td class='$clGaris' ></td>
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' ></td>
				<td class='$clGaris' align=center ></td>
				<td class='$clGaris'></td>
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' align=center></td>
				<td class='$clGaris' ></td>
				<td class='$clGaris' ></td>
				<td class='$clGaris' align=right >".$arr[3]."</td>
				
				<td class='$clGaris'>".$arr[4]."</td>					
				</tr>";
				break;
			}
			case '5':{
				return
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center></td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\" >".$arr[1]."</td>				
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[2]."</td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=right >".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>				
				</tr>";
			}
			case '06':{
				return
				"<tr class='$clRow' valign='top'>
				<td class=\"$clGaris\" align=center></td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\" >".$arr[1]."</td>				
				<td class=\"$clGaris\" >".$arr[2]."</td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" ></td>
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=right >".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>
				
				</tr>";
			}
			default:{		
				return 
				"<tr class=\"$clRow\" valign='top'>
				<td class=\"$clGaris\" align=center></td>
				$tampilCheckbox
				<td class=\"$clGaris\" align=center></td>
				<td class=\"$clGaris\" align=center>".$arr[0]."</td>
				<td class=\"$clGaris\" colspan='3'>".$arr[1]."</td>						
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=center>".$arr[2]."</td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\"></td>
				<td class=\"$clGaris\" align=right></td>
				<td class=\"$clGaris\" align=right>".$arr[3]."</td>
				<td class=\"$clGaris\">".$arr[4]."</td>						
				</tr>";
				break;
			}
		}
		
		
	}

	function Penatausahaan_GetListDet($idBI, $fmKIB, 
		$hrg_perolehan, $cetak=FALSE, 
		$cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox, $cp2=1, $kondTglAkhir='')
	{
		//global $jmlTotalHargaDisplay;//, $ListData;
		$txls = $_REQUEST['xls']=="1"?"1":"";
		$ListData ='';
		$no2=0; 
		$tot=0;
		//pemeliharaan ---------------
		$k1= $kondTglAkhir==''? '': "and tgl_pemeliharaan <='$kondTglAkhir' ";
		$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";				
		//$aqry2 = "select *, year(tgl_pemeliharaan) as thn_pelihara  from pemeliharaan where id_bukuinduk='$idBI' and tambah_aset=1 order by tgl_pemeliharaan" ;
		$aqry2 = "select *, year(tgl_pemeliharaan) as thn_pelihara  from pemeliharaan where idbi_awal='$idBI' $k1 and tambah_aset=1 order by tgl_pemeliharaan" ;
		$Qry2 = mysql_query($aqry2); $i=0;								
		while($isi2=mysql_fetch_array($Qry2)){					
		if ($i==0){	$clGaris = $cetak? "GarisCetak3": "GarisDaftar3";}else{	$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";			}
			$tot +=$isi2['biaya_pemeliharaan'];
			if ($txls=="1"){
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi2['biaya_pemeliharaan']/1000, 2, '.', '') : number_format($isi2['biaya_pemeliharaan'], 2, '.', '');
				
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi2['biaya_pemeliharaan']/1000, 2, ',', '.') : number_format($isi2['biaya_pemeliharaan'], 2, ',', '.');
				
			}
			$tampilCheckbox = $cetak ? "": "<td class=\"$clGaris\" align=center></td>";
			$no2++;			
			$ListData .= 
				Penatausahaan_genTableRowDet($fmKIB, $clRow, $clGaris, $tampilCheckbox, 
					array($no2.'.',$isi2['jenis_pemeliharaan'],$isi2['thn_pelihara'],$tampilHarga,$isi2['ket'] ));
			
			$i++;				
			}	
		
		//pengamanan ---------------
		$k1= $kondTglAkhir==''? '': "and tgl_pengamanan <='$kondTglAkhir' ";
		
		$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";				
		//$aqry3 = "select *, year(tgl_pengamanan) as thn_pengamanan  from pengamanan where id_bukuinduk='$idBI' and tambah_aset=1 order by tgl_pengamanan" ;
		$aqry3 = "select *, year(tgl_pengamanan) as thn_pengamanan  from pengamanan where idbi_awal='$idBI' $k1 and tambah_aset=1 order by tgl_pengamanan" ;
		$Qry3 = mysql_query($aqry3); $i=0;
		while($isi3=mysql_fetch_array($Qry3)){
		if ($i==0){	$clGaris = $cetak? "GarisCetak3": "GarisDaftar3";}else{	$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";}
			$tot +=$isi3['biaya_pengamanan'];
			if ($txls=="1"){			
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi3['biaya_pengamanan']/1000, 2, '.', '') : number_format($isi3['biaya_pengamanan'], 2, '.', '');
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi3['biaya_pengamanan']/1000, 2, ',', '.') : number_format($isi3['biaya_pengamanan'], 2, ',', '.');
				
			}
			$tampilCheckbox = $cetak ? "": "<td class=\"$clGaris\" align=center></td>";
			$no2++;
			$ListData .= Penatausahaan_genTableRowDet($fmKIB,$clRow, $clGaris, $tampilCheckbox, 
			array($no2.'.',$isi3['uraian_kegiatan'],$isi3['thn_pengamanan'],$tampilHarga,$isi3['ket'] ));
			
			$i++;
		}
		
		//total
		$tot_rehab = $tot;//penagmanan+ pemeihara
		$jmlTotalHargaDisplay+=$tot;
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";
		if (mysql_num_rows($Qry2)>0 || mysql_num_rows($Qry3)>0) {
			$tot += $hrg_perolehan;

		if ($txls=="1"){					
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, '.', '') : number_format($tot, 2, '.', '');			
			$tampilHarga_rehab = !empty($cbxDlmRibu)? number_format($tot_rehab/1000, 2, '.', '') : number_format($tot_rehab, 2, '.', '');
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, ',', '.') : number_format($tot, 2, ',', '.');			
			$tampilHarga_rehab = !empty($cbxDlmRibu)? number_format($tot_rehab/1000, 2, ',', '.') : number_format($tot_rehab, 2, ',', '.');
				
			}
			
			switch ($fmKIB){
				case '01': $colspan = $cetak ? 12: 13; break;
				case '02': $colspan = $cetak ? 14: 15; break;
				case '03': $colspan = $cetak ? 15: 16; break;
				case '04': $colspan = $cetak ? 16: 17; break;
				case '05': $colspan = $cetak ? 14: 15; break;
				case '06': $colspan = $cetak ? 16: 17; break;
				default: $colspan = $cetak ? 12: 13; break;
				}		
			$ListData .= 
			Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, 
				array("<span style='font-style:italic;float:right'>Jumlah Rehabilitasi (Rp)</span> ",
				"<span style='font-style:italic;float:right'>$tampilHarga_rehab</span>" )).
			Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, 
				array("<span style='font-style:italic;float:right'>Jumlah (Rp)</span>",
				"<span style='font-style:italic;float:right'>$tampilHarga</span>" ));
			
		}
		
		
		return array('jmlTotalHargaDisplay'=>$jmlTotalHargaDisplay,'ListData'=>$ListData);
	}

function Penatausahaan_GetListDet2($idBI, $fmKIB, 
		$hrg_perolehan, $cetak=FALSE, 
		$cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox, $cp2=1, $kondTglAkhir='')
	{
		//global $jmlTotalHargaDisplay;//, $ListData;
		$fmFiltTglBtw_tgl2 = cekPOST('fmFiltTglBtw_tgl2');
		$fmFiltThnSensus = cekPOST('$fmFiltThnSensus');
				if ($fmFiltTglBtw_tgl2!='' && $kondTglAkhir=='') $kondTglAkhir=$fmFiltTglBtw_tgl2;
		if ($fmFiltThnSensus!='')
		{
			$tgl_sensus=($fmFiltThnSensus-1).'-12-31';
			if ($fmFiltTglBtw_tgl2=='' || $fmFiltTglBtw_tgl2>$tgl_sensus)
			{
				$kondTglAkhir=$tgl_sensus;
			} 
		} 
		$txls = $_REQUEST['xls']=="1"?"1":"";
		$ListData ='';
		$no2=0; 
		$tot=0;

		//pemeliharaan ---------------
		$k1= $kondTglAkhir==''? '': "and tgl_pemeliharaan <='$kondTglAkhir' ";
		$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";				
		//$aqry2 = "select *, year(tgl_pemeliharaan) as thn_pelihara  from pemeliharaan where id_bukuinduk='$idBI' and tambah_aset=1 order by tgl_pemeliharaan" ;
		$aqry2 = "select *, year(tgl_pemeliharaan) as thn_pelihara  from pemeliharaan where idbi_awal='$idBI' $k1 and tambah_aset=1 order by tgl_pemeliharaan" ;
		$Qry2 = mysql_query($aqry2); $i=0;								
		while($isi2=mysql_fetch_array($Qry2)){					
		if ($i==0){	$clGaris = $cetak? "GarisCetak3": "GarisDaftar3";}else{	$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";			}
			$tot +=$isi2['biaya_pemeliharaan'];
			if ($txls=="1"){
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi2['biaya_pemeliharaan']/1000, 2, '.', '') : number_format($isi2['biaya_pemeliharaan'], 2, '.', '');
				
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi2['biaya_pemeliharaan']/1000, 2, ',', '.') : number_format($isi2['biaya_pemeliharaan'], 2, ',', '.');
				
			}
			$tampilCheckbox = $cetak ? "": "<td class=\"$clGaris\" align=center></td>";
			$no2++;			
			$ListData .= 
				Penatausahaan_genTableRowDet($fmKIB, $clRow, $clGaris, $tampilCheckbox, 
					array($no2.'.',$isi2['jenis_pemeliharaan'],$isi2['thn_pelihara'],$tampilHarga,$isi2['ket'] ));
			
			$i++;				
			}	
		
		//pengamanan ---------------
		$k1= $kondTglAkhir==''? '': "and tgl_pengamanan <='$kondTglAkhir' ";
		
		$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";				
		//$aqry3 = "select *, year(tgl_pengamanan) as thn_pengamanan  from pengamanan where id_bukuinduk='$idBI' and tambah_aset=1 order by tgl_pengamanan" ;
		$aqry3 = "select *, year(tgl_pengamanan) as thn_pengamanan  from pengamanan where idbi_awal='$idBI' $k1 and tambah_aset=1 order by tgl_pengamanan" ;
		$Qry3 = mysql_query($aqry3); $i=0;
		while($isi3=mysql_fetch_array($Qry3)){
		if ($i==0){	$clGaris = $cetak? "GarisCetak3": "GarisDaftar3";}else{	$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";}
			$tot +=$isi3['biaya_pengamanan'];
			if ($txls=="1"){			
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi3['biaya_pengamanan']/1000, 2, '.', '') : number_format($isi3['biaya_pengamanan'], 2, '.', '');
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi3['biaya_pengamanan']/1000, 2, ',', '.') : number_format($isi3['biaya_pengamanan'], 2, ',', '.');
				
			}
			$tampilCheckbox = $cetak ? "": "<td class=\"$clGaris\" align=center></td>";
			$no2++;
			$ListData .= Penatausahaan_genTableRowDet($fmKIB,$clRow, $clGaris, $tampilCheckbox, 
			array($no2.'.',$isi3['uraian_kegiatan'],$isi3['thn_pengamanan'],$tampilHarga,$isi3['ket'] ));
			
			$i++;
		}
		
		//total
		$tot_rehab = $tot;//penagmanan+ pemeihara
		$jmlTotalHargaDisplay+=$tot;
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";
		if (mysql_num_rows($Qry2)>0 || mysql_num_rows($Qry3)>0) {
			$tot += $hrg_perolehan;

		if ($txls=="1"){					
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, '.', '') : number_format($tot, 2, '.', '');			
			$tampilHarga_rehab = !empty($cbxDlmRibu)? number_format($tot_rehab/1000, 2, '.', '') : number_format($tot_rehab, 2, '.', '');
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, ',', '.') : number_format($tot, 2, ',', '.');			
			$tampilHarga_rehab = !empty($cbxDlmRibu)? number_format($tot_rehab/1000, 2, ',', '.') : number_format($tot_rehab, 2, ',', '.');
				
			}
			
			switch ($fmKIB){
				case '01': $colspan = $cetak ? 12: 13; break;
				case '02': $colspan = $cetak ? 14: 15; break;
				case '03': $colspan = $cetak ? 15: 16; break;
				case '04': $colspan = $cetak ? 16: 17; break;
				case '05': $colspan = $cetak ? 14: 15; break;
				case '06': $colspan = $cetak ? 16: 17; break;
				default: $colspan = $cetak ? 12: 13; break;
				}		
			$ListData .= 
			Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, 
				array("<span style='font-style:italic;float:right'>Jumlah Rehabilitasi (Rp)</span>",
				"<span style='font-style:italic;float:right'>$tampilHarga_rehab</span>" ));

}
		$tot=0;$no2=0;
		//penghapusan sebagian ---------------
		$k1= $kondTglAkhir==''? '': "and tgl_penghapusan <='$kondTglAkhir' ";

		$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";				
		//$aqry2 = "select *, year(tgl_pemeliharaan) as thn_pelihara  from pemeliharaan where id_bukuinduk='$idBI' and tambah_aset=1 order by tgl_pemeliharaan" ;
		$aqry2 = "select *, year(tgl_penghapusan) as thn_penghapusan  from penghapusan_sebagian where idbi_awal='$idBI' $k1 order by tgl_penghapusan" ;
		$Qry2 = mysql_query($aqry2); $i=0;								
		while($isi2=mysql_fetch_array($Qry2)){					
		if ($i==0){	$clGaris = $cetak? "GarisCetak3": "GarisDaftar3";}else{	$clGaris = $cetak? "GarisCetak2": "GarisDaftar2";			}
			$tot +=-$isi2['harga_hapus'];
			if ($txls=="1"){
			$tampilHarga = !empty($cbxDlmRibu)? number_format(-$isi2['harga_hapus']/1000, 2, '.', '') : number_format(-$isi2['harga_hapus'], 2, '.', '');
				
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format(-$isi2['harga_hapus']/1000, 2, ',', '.') : number_format(-$isi2['harga_hapus'], 2, ',', '.');
				
			}
			$tampilCheckbox = $cetak ? "": "<td class=\"$clGaris\" align=center></td>";
			$no2++;			
			$ListData .= 
				Penatausahaan_genTableRowDet($fmKIB, $clRow, $clGaris, $tampilCheckbox, 
					array($no2.'.',$isi2['uraian'],$isi2['thn_penghapusan'],$tampilHarga,$isi2['ket'] ));
			
			$i++;				
			}	
		
	
		
		//total
		$tot_susut = $tot;//penagmanan+ pemeihara
		$jmlTotalHargaDisplay+=$tot;
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";
		if (mysql_num_rows($Qry2)>0 ) {
			$tot = $hrg_perolehan+$tot_rehab+$tot_susut;

		if ($txls=="1"){					
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, '.', '') : number_format($tot, 2, '.', '');			
			$tampilHarga_susut = !empty($cbxDlmRibu)? number_format($tot_susut/1000, 2, '.', '') : number_format($tot_susut, 2, '.', '');
			} else {
			$tampilHarga = !empty($cbxDlmRibu)? number_format($tot/1000, 2, ',', '.') : number_format($tot, 2, ',', '.');			
			$tampilHarga_susut = !empty($cbxDlmRibu)? number_format($tot_susut/1000, 2, ',', '.') : number_format($tot_susut, 2, ',', '.');
				
			}
			
			switch ($fmKIB){
				case '01': $colspan = $cetak ? 12: 13; break;
				case '02': $colspan = $cetak ? 14: 15; break;
				case '03': $colspan = $cetak ? 15: 16; break;
				case '04': $colspan = $cetak ? 16: 17; break;
				case '05': $colspan = $cetak ? 14: 15; break;
				case '06': $colspan = $cetak ? 16: 17; break;
				default: $colspan = $cetak ? 12: 13; break;
				}		
			$ListData .= 
			Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, 
				array("<span style='font-style:italic;float:right'>Jumlah Penghapusan Sebagian (Rp)</span>",
				"<span style='font-style:italic;float:right'>$tampilHarga_susut</span>" ));
			
		}
		if ($tot_rehab>0 || $tot_susut>0 ) {
		
			$ListData .=Penatausahaan_genTableRowJml($clRow, $clGaris, $colspan, 
				array("<span style='font-style:italic;float:right'>Jumlah (Rp)</span>",
				"<span style='font-style:italic;float:right'>$tampilHarga</span>" ));
		
		}
		
		return array('jmlTotalHargaDisplay'=>$jmlTotalHargaDisplay,'ListData'=>$ListData);
	}	
	
	function Penatausahaan_Proses($isMutasi=FALSE){
		global $errmsg, $Act, $Baru, $cidBI;
		global $MyField, $MyFieldKIB, $Main, $Sukses, $Info;		
		global $fmKEPEMILIKAN, $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmTAHUNPEROLEHAN, 
		$fmREGISTER, 
		$fmSKPD_old, $fmUNIT_old, $fmSUBUNIT_old, $fmREGISTER_old, $fmTAHUNANGGARAN_old, $fmTAHUNPEROLEHAN_old, $fmREGISTER_old,
		$ArBarang, $ArBarang_old, $fmIDBARANG, $fmIDBARANG_old,
		$fmJUMLAHBARANG, $fmSATUAN,
		$fmHARGABARANG, $fmJUMLAHHARGA, $fmASALUSUL, $fmTGLUPDATE, $tgl_sensus,
		$fmKONDISIBARANG, $nilai_appraisal, $gambar, $dokumen_ket, $dokumen,
	$dokumen_file, $tgl_buku, $UID,  $fmIDLama; //{$idBI,}
		
		global $alamat_kel, $alamat_kec, $alamat_a, $alamat_b, $koordinat_gps, $koord_bidang;
		global $alamat_kota, $alamat_c;
		
		global $fmLUAS_KIB_A, $fmLETAK_KIB_A, 
		$fmHAKPAKAI_KIB_A, $bersertifikat, $fmTGLSERTIFIKAT_KIB_A, $fmNOSERTIFIKAT_KIB_A,		
		$fmPENGGUNAAN_KIB_A, $fmKET_KIB_A;
		global $fmMERK_KIB_B, $fmUKURAN_KIB_B, $fmBAHAN_KIB_B, $fmPABRIK_KIB_B, $fmRANGKA_KIB_B, 
		$fmMESIN_KIB_B, $fmPOLISI_KIB_B, $fmBPKB_KIB_B,	$fmKET_KIB_B;
		global $fmKONDISI_KIB_C, $fmTINGKAT_KIB_C, $fmBETON_KIB_C, $fmLUASLANTAI_KIB_C,
		$fmLETAK_KIB_C, $fmTGLGUDANG_KIB_C, $fmNOGUDANG_KIB_C, $fmLUAS_KIB_C, $fmSTATUSTANAH_KIB_C,
		$fmNOKODETANAH_KIB_C, $fmKET_KIB_C;
		global $fmKONSTRUKSI_KIB_D, $fmPANJANG_KIB_D, $fmLEBAR_KIB_D, $fmLUAS_KIB_D,
		$fmALAMAT_KIB_D, $fmTGLDOKUMEN_KIB_D, $fmNODOKUMEN_KIB_D, $fmSTATUSTANAH_KIB_D,
		$fmNOKODETANAH_KIB_D, $fmKONDISI_KIB_D, $fmKET_KIB_D;
		global $fmJUDULBUKU_KIB_E, $fmSPEKBUKU_KIB_E, $fmSENIBUDAYA_KIB_E, $fmSENIPENCIPTA_KIB_E, 
		$fmSENIBAHAN_KIB_E, $fmJENISHEWAN_KIB_E, $fmUKURANHEWAN_KIB_E, $fmKET_KIB_E;
		global $fmBANGUNAN_KIB_F, $fmTINGKAT_KIB_F, $fmBETON_KIB_F, $fmLUAS_KIB_F,
		$fmLETAK_KIB_F, $fmTGLDOKUMEN_KIB_F, $fmNODOKUMEN_KIB_F, $fmTGLMULAI_KIB_F,
		$fmSTATUSTANAH_KIB_F, $fmNOKODETANAH_KIB_F, $fmKET_KIB_F;
		global $fmURAIAN_KIB_G, $fmKET_KIB_G;
		global $gambar_old, $dokumen_file_old;
		global $tgl_sensus, $fmIdAwal;
		global $HTTP_COOKIE_VARS;
		
		$cek = '';
		$UID = $HTTP_COOKIE_VARS['coID'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		$ref_idpemegang = $_REQUEST['ref_idpemegang'];  
		$ref_idpemegang2 = $_REQUEST['ref_idpemegang2'];  
		$ref_idpenanggung = $_REQUEST['ref_idpenanggung']; 		
		$ref_idruang = $_REQUEST['ref_idruang']; 		
		$fmWIL = $Main->DEF_WILAYAH ;
			
		switch ($Act){		
			case 'Simpan' :{
				//prosedure ini dipakai di penatausahaan dan mutasi!!
				$getdate = mysql_fetch_array(mysql_query("select YEAR(now()) as thn "));
						
				//validasi	
				$errmsg='';	
//				$errmsg=Penatausahaan_CekdataCutoff('edit',$xid,$tgl_buku);
//				$errmsg=' lkjafls'.$tgl_buku.' cek';								
				if ($errmsg=='' && $fmKONDISIBARANG == ''){	$errmsg = 'Kondisi Barang belum dipilih!';	}
				if ($errmsg=='' && strlen($fmREGISTER) != $Main->NOREG_SIZE){ $errmsg = 'No. Register salah!';	}
				if ($errmsg=='' && ( strlen($fmREGISTER) < $Main->NOREG_SIZE ) ){ $errmsg = 'No. Register harus '.$Main->NOREG_SIZE.' digit!';	}
															
				//cek thn perolehan
				if ($errmsg=='' && strlen($fmTAHUNPEROLEHAN) != 4){	$errmsg = 'Tahun Perolehan salah!';	}
				if ($errmsg=='' && $fmTAHUNPEROLEHAN < '1900' ) { $errmsg = 'Tahun Perolehan tidak lebih kecil dari 1900!'; }
				if ($errmsg=='' && $fmTAHUNPEROLEHAN >  $getdate['thn'] ) { $errmsg = 'Tahun Perolehan tidak lebih besar dari tahun sekarang!'; }
				
				//cek tgl buku all
				$tgls = explode('-',$tgl_buku);
				$thnTglBuku = $tgls[0]; //echo $thnTglBuku;
				if ($errmsg=='' && !cektanggal($tgl_buku)){ $errmsg = 'Tanggal Buku salah!'; }
				if ($errmsg =='' && compareTanggal($tgl_buku, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Buku tidak lebih besar dari Hari ini!';
				//if ($errmsg=='' && $thnTglBuku < 2009 ){ $errmsg = 'Tahun Tanggal Buku tidak lebih kecil dari 2009!'; }				
				if ($errmsg=='' && $thnTglBuku < $Main->TAHUN_CUTOFF ){ $errmsg = 'Tahun Tanggal Buku tidak lebih kecil dari '.$Main->TAHUN_CUTOFF.'!'; }				
				 
				if ($errmsg=='' && $thnTglBuku <  $fmTAHUNPEROLEHAN ) $errmsg = 'Tahun Tanggal Buku tidak lebih kecil dari Tahun Perolehan!';
								
				//cek tgl sensus			
				$tgl_sensus= $tgl_sensus=='0000-00-00 00:00:00' ? '': $tgl_sensus;	
				$arr = explode('-',$tgl_sensus); //$errmsg = ($tgl_sensus != '').$tgl_sensus;
				$thnTglSensus = $arr[0];								
				if ($errmsg=='' && $tgl_sensus!='' && !cektanggal($tgl_sensus)) $errmsg = "Tanggal Sensus salah  !"; 
				if ($errmsg =='' && $tgl_sensus!='' && compareTanggal($tgl_sensus, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Sensus tidak lebih besar dari Hari ini!';
				if ($errmsg=='' && $tgl_sensus!='' && $thnTglSensus <  $fmTAHUNPEROLEHAN ) $errmsg = 'Tahun Tanggal Sensus tidak lebih kecil dari Tahun Perolehan!';
				
				
				if($isMutasi == FALSE){
					//cek tgl buku										
					/*if ($errmsg=='' && $fmTAHUNPEROLEHAN<=2009 && $thnTglBuku > 2009 ){ 
						$errmsg = 'Untuk Tahun perolehan lebih kecil dari 2010. Tahun Tanggal Buku tidak lebih besar dari 2009!'; 
					}
					if ($errmsg=='' && $fmTAHUNPEROLEHAN>2009 && $thnTglBuku != $fmTAHUNPEROLEHAN && $Baru!=0){ 
						$errmsg = 'Tahun Tanggal Buku harus sama dengan Tahun Perolehan!'; 
					}*/				
					if ($errmsg=='' && $fmTAHUNPEROLEHAN<=$Main->TAHUN_CUTOFF && $thnTglBuku > $Main->TAHUN_CUTOFF ){ 
						$errmsg = 'Untuk Tahun perolehan lebih kecil dari '.($Main->TAHUN_CUTOFF+1).'. Tahun Tanggal Buku tidak lebih besar dari '.$Main->TAHUN_CUTOFF.'!'; 
					}
					if ($errmsg=='' && $fmTAHUNPEROLEHAN>$Main->TAHUN_CUTOFF && $thnTglBuku != $fmTAHUNPEROLEHAN && $Baru!=0){ 
						$errmsg = 'Tahun Tanggal Buku harus sama dengan Tahun Perolehan!'; 
					}
					if ($errmsg =='' && $tgl_sensus!='' && compareTanggal($tgl_buku, $tgl_sensus )==2 && $Baru!=0) $errmsg = 'Tanggal Sensus tidak lebih kecil dari Tanggal Buku !';

					if ($errmsg=='' && $Baru==0){
						$idbi = $_POST['idbi'];
							$errmsg=Penatausahaan_CekdataCutoff('edit',$idbi,'');
					} elseif ($errmsg=='')
					{
							$errmsg=Penatausahaan_CekdataCutoff('insert',$idbi,$tgl_buku);
						
					}
					
					if ($errmsg=='' && $Baru==0){ //edit						
						$idbi = $_POST['idbi'];
						$old =  mysql_fetch_array( mysql_query (
							"select * from buku_induk where id = '$idbi'"
						));//echo  'id='.$idbi .' idlama='.$old['id_lama']. '<br>';

						// $errmsg.=' halo';
						//cek tgl pemeliharaan, pengamanan						
						$pelihara = mysql_fetch_array( mysql_query (
							"select min(tgl_pemeliharaan) as mintgl from pemeliharaan where id_bukuinduk = '$idbi'"
						));
						if ($errmsg =='' && compareTanggal($tgl_buku, $pelihara['mintgl'])==2 && $pelihara['mintgl']!='' ) $errmsg = 'Tanggal Buku tidak lebih besar dari Tanggal Pemeliharaan!';
						$pengaman = mysql_fetch_array( mysql_query (
							"select min(tgl_pengamanan) as mintgl from pengamanan where id_bukuinduk = '$idbi'"
						));
						if ($errmsg =='' && compareTanggal($tgl_buku, $pengaman['mintgl'])==2  && $pengaman['mintgl']!='' ) $errmsg = 'Tanggal Buku tidak lebih besar dari Tanggal Pengamanan!';//.$idbi.' '. $tgl_buku.' '.$pengaman['mintgl'];
						$pemanfaat = mysql_fetch_array( mysql_query (
							"select min(tgl_pemanfaatan) as mintgl from pemanfaatan where id_bukuinduk = '$idbi'"
						));
						if ($errmsg =='' && compareTanggal($tgl_buku, $pemanfaat['mintgl'])==2 && $pemanfaat['mintgl']!=''  ) $errmsg = 'Tanggal Buku tidak lebih besar dari Tanggal Pemanfaatan!';											
						
						if ($old['status_barang']==3) $errmsg = "Gagal Simpan, Data Sudah di Penghapusan!";
						if ($old['status_barang']==4) $errmsg = "Gagal Simpan, Data Sudah di Pemindah Tanganan!";
						if ($old['status_barang']==5) $errmsg = "Gagal Simpan, Data Sudah di Ganti Rugi!";
						
						if($old['id_lama'] != '' || $old['id_lama'] != NULL){ //hasil mutasi
							$hapus = mysql_fetch_array( mysql_query (
								"select tgl_penghapusan from penghapusan where id_bukuinduk = '{$old['id_lama']}'"
							));
							$bilama = mysql_fetch_array( mysql_query (
								"select * from buku_induk where id = '{$old['id_lama']}'"
							)); 
							if ( compareTanggal($tgl_buku, $hapus['tgl_penghapusan'])==0) 
								$errmsg = 'Tanggal Buku tidak lebih kecil dari Tanggal Penghapusan!';									
						}else{//bukan hasil mutasi							
							if ($errmsg =='' && $tgl_sensus!='' && compareTanggal($tgl_buku, $tgl_sensus )==2 ) $errmsg = 'Tanggal Sensus tidak lebih kecil dari Tanggal Buku !';				
							
						}					
					}else{//baru
						// $errmsg=Penatausahaan_CekdataCutoff('insert',$idbi,$tgl_buku);
						if ($errmsg =='' && $tgl_sensus!='' && compareTanggal($tgl_buku, $tgl_sensus )==2 ) $errmsg = 'Tanggal Sensus tidak lebih kecil dari Tanggal Buku !';																	
					}	
				}else{
					$idbi = $_POST['idbi'];
					$old =  mysql_fetch_array( mysql_query (
						"select * from buku_induk where id = '$idbi'"
					));
					if ($errmsg=='' && $old[''])
					if ($errmsg==''){
						$hapus = mysql_fetch_array( mysql_query (
							"select tgl_penghapusan from penghapusan where id_bukuinduk = '$fmIDLama'"
						));
						if ( compareTanggal($tgl_buku, $hapus['tgl_penghapusan'])==0) 
							$errmsg = 'Tanggal Buku tidak lebih kecil dari Tanggal Penghapusan!';	
					}
				}
					
				if ($errmsg=='' && ($fmSKPD =='' || $fmSKPD =='00') ){ $errmsg='BIDANG belum dipilih';	}	
				if ($errmsg=='' && ($fmUNIT =='' || $fmUNIT =='00') ){ $errmsg='SKPD belum dipilih';	}	
				if ($errmsg=='' && ($fmSUBUNIT =='' || $fmSUBUNIT =='00') ){ $errmsg='UNIT belum dipilih';	}	
				if ($errmsg=='' && ($fmSEKSI =='' || $fmSEKSI =='00' || $fmSEKSI =='000') ){ $errmsg='SUB UNIT belum dipilih';	}	
				if ($errmsg=='' ){ $errmsg= ProsesCekField2($MyField);}
				if ($errmsg=='' ){ $errmsg= ProsesCekField2($MyFieldKIB);}	
				//cek tgl sertifikat
				if ($errmsg=='' && ($bersertifikat =='1' ) ){
					$tgl = $fmTGLSERTIFIKAT_KIB_A;
					$nosert = $fmNOSERTIFIKAT_KIB_A;
					if($tgl == '0000-00-00' || $tgl=='' ){ $errmsg = 'Tanggal Sertifikat belum diisi!';	}
					if(!cektanggal($tgl)){ $errmsg = 'Tanggal Sertifikat Salah!';}
					if ($errmsg =='' && compareTanggal($tgl, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Sertifikat tidak lebih besar dari Hari ini!';
				
					if($nosert =='' ){ $errmsg = 'No. Sertifikat belum diisi!';	} 
				}	
				if ($errmsg=='' && $fmJUMLAHBARANG>99 ){ $errmsg='jumlah Barang lebih dari 99!'; }
				if ($errmsg==''){ //noreg + jumlah <= 9999
					//fmREGISTER, fmJUMLAHBARANG
				if ($fmREGISTER+ $fmJUMLAHBARANG >$Main->NOREG_MAX){ $errmsg='No Register dan jumlah Barang tidak lebih dari '.$Main->NOREG_MAX.'!'; }
				}
				
				$cek .= '<br> errmsg = '.$errmsg;
				if ($errmsg==''){	
					$cek .= '<br> cidBI= '.$cidBI;
					$cek .= '<br> Act= '.$Act;
					$cek .= '<br> Baru= '.$Baru;
					$cek .= '<br>idbarang ='.$fmIDBARANG;
					$ArBarang = explode(".",$fmIDBARANG);	
					$ArBarang_old = explode(".",$fmIDBARANG_old);	$cek .= '<br>idbarang_old ='.$fmIDBARANG_old;
					$fmJUMLAHHARGA = $fmJUMLAHBARANG * $fmHARGABARANG;
					$Sukses = false;
					
					switch($Baru){
						case '0':{//edit ------------------------------------------------------------------				
								$idbi_ = $_REQUEST['idbi'];
								$old= mysql_fetch_array(mysql_query(
									"select * from buku_induk where id='$idbi_'"
								));

								$fmSEKSI_old = $old['e1'];//$_REQUEST['fmSEKSI_old'];
								
								//else if($Baru==0){
								$cek .='<br>$idbi_ = '.$idbi_;
								$cek .='<br>fmSubunit before_simpan = '.$fmSUBUNIT;
								$cek .='<br>fmSubunit_old before_simpan = '.$fmSUBUNIT_old;
								$cek .='<br>fmUNIT_old before_simpan = '.$fmUNIT_old;
								$cek .='<br>fmSKPD_old before_simpan = '.$fmSKPD_old;			
								$cek .='<br>fmSEKSI_old before_simpan = '.$fmSEKSI_old;
								$cek .='<br>fmSEKSI = '.$fmSEKSI;			
								$cek .='<br>fmWIL before_simpan = '.$old['b'];
								$cek .='<br>fmWIL  = '.$fmWIL;
								//$errmsg = $cek;
								if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
									
								//$Kriteria = "concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg)=	'$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD_old$fmUNIT_old$fmSUBUNIT_old$fmSEKSI_old{$ArBarang_old[0]}{$ArBarang_old[1]}{$ArBarang_old[2]}{$ArBarang_old[3]}{$ArBarang_old[4]}$fmTAHUNANGGARAN_old$fmREGISTER_old'";
								$KriteriaKIB =  " idbi= $idbi_";
																
								if ($nilai_appraisal==''){$nilai_appraisal=0;}		//$cek .= '<br> nil appraisal3= '.$nilai_appraisal;
																
								//cek no baru --------------------	
								if( ($fmSKPD_old != $fmSKPD)|| ($fmUNIT_old != $fmUNIT)|| ($fmSUBUNIT_old != $fmSUBUNIT)||
									($fmREGISTER != $fmREGISTER_old) || ($fmTAHUNPEROLEHAN != $fmTAHUNPEROLEHAN_old ) || 
									($fmIDBARANG != $fmIDBARANG_old) || ($fmTAHUNANGGARAN != $fmTAHUNANGGARAN_old) || $fmSEKSI !=$fmSEKSI_old )
								{							
										$CekBaru = mysql_num_rows(mysql_query(
											"select * from buku_induk where concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg)=
											'$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT$fmSEKSI{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNPEROLEHAN$fmREGISTER'")
										);
								}
								//$cek .= '<br> cekregister'.$CekBaru;
								if($errmsg == '' && $CekBaru){
									$errmsg = "Data TIDAK dapat disimpan \\n Nomor Register $fmREGISTER tahun $fmTAHUNPEROLEHAN sudah ada!!!";
								}
								
								//cek sudah dihapus -----------------
								if($errmsg=='' ){
									//$get = mysql_fetch_array( mysql_query("select * from buku_induk where ") );
									if ($old['status_barang'] == 3){
										$errmsg = 'Data Sudah Penghapusan Tidak Bisa Di Edit!';
									}
								}
								//$errmsg = 'tes';
								if($errmsg ==''){
									//UPDATE BI -------------------
									$Qry = 
									"update buku_induk set ".									
									"c ='$fmSKPD',
									d ='$fmUNIT',
									e ='$fmSUBUNIT',
									e1 ='$fmSEKSI',					
									f ='{$ArBarang[0]}',
									g ='{$ArBarang[1]}',
									h ='{$ArBarang[2]}',
									i ='{$ArBarang[3]}',
									j ='{$ArBarang[4]}',
									
									noreg ='$fmREGISTER',
									thn_perolehan='$fmTAHUNPEROLEHAN',
									ref_idpemegang='$ref_idpemegang',  
									ref_idpemegang2='$ref_idpemegang2',  
									ref_idpenanggung='$ref_idpenanggung', 
									ref_idruang='$ref_idruang',
									
									tahun='$fmTAHUNANGGARAN', ".
									"jml_barang=1,
									jml_harga='$fmJUMLAHHARGA',
									kondisi='$fmKONDISIBARANG',
									satuan='$fmSATUAN',
									harga='$fmHARGABARANG',
									asal_usul='$fmASALUSUL',					
									tgl_update=now(),
									nilai_appraisal=".$nilai_appraisal.",							
									tgl_buku='".$tgl_buku."',
									tgl_sensus='".$tgl_sensus."',
									uid ='".$UID."'					
									where id= '$idbi_' ";
									
									$cek .= '<br>qrybi updt='.$Qry;
									//$errmsg = $cek;
									$Sukses = mysql_query($Qry);
									$idbukuinduk = $idbi_;//mysql_fetch_array(mysql_query("select id as id from buku_induk where $Kriteria"));					
									$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,e1,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi)values('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$idbukuinduk[0]}','$fmTAHUNANGGARAN','$fmREGISTER',now(),'Update Inventaris','$fmKONDISIBARANG')");
									
									//UPDATE KIB A			
									if($ArBarang[0]=="01"){
											//sertifikat_tgl='".TglSQL($fmTGLSERTIFIKAT_KIB_A)."', 
										if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
											$Qry = 
											"update kib_a set ".
											"c ='$fmSKPD',d ='$fmUNIT',e ='$fmSUBUNIT',e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',g ='{$ArBarang[1]}',h ='{$ArBarang[2]}',i ='{$ArBarang[3]}',j ='{$ArBarang[4]}',
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',".
											
											"luas='$fmLUAS_KIB_A', 											
											alamat='$fmLETAK_KIB_A', 
											alamat_kel='$alamat_kel', 
											alamat_kec='$alamat_kec', 
											alamat_a='$alamat_a', 
											alamat_b='$alamat_b', 
											alamat_c='$alamat_c', 
											kota='$alamat_kota', 

											koordinat_gps='$koordinat_gps',
											koord_bidang='$koord_bidang', 
											
											status_hak='$fmHAKPAKAI_KIB_A', 
											bersertifikat = '$bersertifikat',					
											sertifikat_tgl='".$fmTGLSERTIFIKAT_KIB_A."', 
											sertifikat_no='$fmNOSERTIFIKAT_KIB_A',
											penggunaan='$fmPENGGUNAAN_KIB_A', 
											ket='$fmKET_KIB_A' where $KriteriaKIB";
											
											$cek.='<br> qry udt kiba='.$Qry; //$errmsg.=$cek;
											$Sukses = mysql_query($Qry);
										}
									//UPDATE KIB B
									else if($ArBarang[0]=="02"){					
											$Qry = "update kib_b set ".
											"c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',".
											"merk = '$fmMERK_KIB_B', ukuran = '$fmUKURAN_KIB_B', bahan = '$fmBAHAN_KIB_B', no_pabrik = '$fmPABRIK_KIB_B', no_rangka = '$fmRANGKA_KIB_B', no_mesin = '$fmMESIN_KIB_B', no_polisi = '$fmPOLISI_KIB_B', no_bpkb = '$fmBPKB_KIB_B', ket = '$fmKET_KIB_B' 
											where $KriteriaKIB";
											//echo $Qry ;
											$Sukses = mysql_query($Qry);
										}
									//UPDATE KIB C
									else if($ArBarang[0]=="03"){
											if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
											$Qry = "update kib_c set ".
											"c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',
											".
											"kondisi = '$fmKONDISI_KIB_C', 
											konstruksi_tingkat = '$fmTINGKAT_KIB_C', 
											konstruksi_beton = '$fmBETON_KIB_C', 
											luas_lantai = '$fmLUASLANTAI_KIB_C', 
											alamat = '$fmLETAK_KIB_C', 
											alamat_kel='$alamat_kel', 
											alamat_kec='$alamat_kec', 
											alamat_a='$alamat_a', 
											alamat_b='$alamat_b',
											alamat_c='$alamat_c', 
											kota='$alamat_kota', 
											 
											koordinat_gps='$koordinat_gps', 
											koord_bidang='$koord_bidang', 
											dokumen_tgl = '".$fmTGLGUDANG_KIB_C."', dokumen_no = '$fmNOGUDANG_KIB_C', 
											luas = '$fmLUAS_KIB_C', status_tanah = '$fmSTATUSTANAH_KIB_C', 
											kode_tanah = '$fmNOKODETANAH_KIB_C', 
											
											ket = '$fmKET_KIB_C' 
											where $KriteriaKIB";
											$Sukses = mysql_query($Qry);
										}
									//UPDATE KIB D
									else if($ArBarang[0]=="04"){
										if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
											$Qry = "update kib_d set 
											c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',
											konstruksi = '$fmKONSTRUKSI_KIB_D', panjang = '$fmPANJANG_KIB_D', lebar = '$fmLEBAR_KIB_D', luas = '$fmLUAS_KIB_D', 
											alamat = '$fmALAMAT_KIB_D', 
											alamat_kel='$alamat_kel', 
											alamat_kec='$alamat_kec', 
											alamat_a='$alamat_a', 
											alamat_b='$alamat_b', 
											alamat_c='$alamat_c', 
											kota='$alamat_kota', 

											koordinat_gps='$koordinat_gps',
											koord_bidang='$koord_bidang',  
											dokumen_tgl = '".$fmTGLDOKUMEN_KIB_D."', dokumen_no = '$fmNODOKUMEN_KIB_D', status_tanah = '$fmSTATUSTANAH_KIB_D', kode_tanah = '$fmNOKODETANAH_KIB_D', kondisi = '$fmKONDISI_KIB_D', ket = '$fmKET_KIB_D' 
											where $KriteriaKIB";
											$Sukses = mysql_query($Qry);
											}
									//UPDATE KIB E
									else if($ArBarang[0]=="05"){
											$Qry = "update kib_e set
											c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',
											buku_judul = '$fmJUDULBUKU_KIB_E', buku_spesifikasi = '$fmSPEKBUKU_KIB_E', seni_asal_daerah = '$fmSENIBUDAYA_KIB_E', seni_pencipta = '$fmSENIPENCIPTA_KIB_E', seni_bahan = '$fmSENIBAHAN_KIB_E', hewan_jenis = '$fmJENISHEWAN_KIB_E', hewan_ukuran = '$fmUKURANHEWAN_KIB_E', ket = '$fmKET_KIB_E' 
											where $KriteriaKIB";
											//echo $Qry;
											$Sukses = mysql_query($Qry);
										}
									//UPDATE KIB F
									else if($ArBarang[0]=="06"){
										if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
											$Qry = "update kib_f set 
											c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN',	noreg ='$fmREGISTER',
											bangunan = '$fmBANGUNAN_KIB_F', konstruksi_tingkat = '$fmTINGKAT_KIB_F', konstruksi_beton = '$fmBETON_KIB_F', luas = '$fmLUAS_KIB_F', 
											alamat = '$fmLETAK_KIB_F', 
											alamat_kel='$alamat_kel', 
											alamat_kec='$alamat_kec', 
											alamat_a='$alamat_a', 
											alamat_b='$alamat_b',
											alamat_c='$alamat_c', 
											kota='$alamat_kota', 
											 
											koordinat_gps='$koordinat_gps', 
											koord_bidang='$koord_bidang', 
											dokumen_tgl = '".$fmTGLDOKUMEN_KIB_F."', dokumen_no = '$fmNODOKUMEN_KIB_F', 
											tmt = '".$fmTGLMULAI_KIB_F."', status_tanah = '$fmSTATUSTANAH_KIB_F', kode_tanah = '$fmNOKODETANAH_KIB_F', ket = '$fmKET_KIB_F' 
											where $KriteriaKIB";
											//
											$Sukses = mysql_query($Qry);
									} else if($ArBarang[0]=="07"){
										$Qry = "update kib_g set
											c ='$fmSKPD',
											d ='$fmUNIT',
											e ='$fmSUBUNIT',
											e1 ='$fmSEKSI',	
											f ='{$ArBarang[0]}',
											g ='{$ArBarang[1]}',
											h ='{$ArBarang[2]}',
											i ='{$ArBarang[3]}',
											j ='{$ArBarang[4]}',
											
											tahun='$fmTAHUNANGGARAN', noreg ='$fmREGISTER',
											uraian = '$fmURAIAN_KIB_G', ket = '$fmKET_KIB_G' 
										where $KriteriaKIB";
											//echo $Qry;
										$Sukses = mysql_query($Qry);
									}
									$cek .= '<br> qrykib Sukses edit='.$Qry;
									
									if (!$Sukses){
										$errmsg = 'Data Gagal Disimpan!';
									}
								}//else{
								//	$Info = "<script>alert('$errmsg')</script>";
								//}
								break;
							}
						case '1':{	//simpan baru ------------------------------------------------------------																	
							$fmWIL = $Main->DEF_WILAYAH;	
							if ($nilai_appraisal==''){$nilai_appraisal=0;}
							$Kriteria = "concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg)='$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT$fmSEKSI{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNPEROLEHAN$fmREGISTER'";
							$str = "select *  from buku_induk where concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg)=
							'$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT$fmSEKSI{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNPEROLEHAN$fmREGISTER'";
							
							$CekBaru = mysql_num_rows(mysql_query( $str ));							
							//echo "<br>str=$str cek=".$CekBaru;							
							//$errmsg = " kriteria= $str";
							if($CekBaru == 0){
								if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
								$Mulai = $fmREGISTER*1;
								$Sampai = $Mulai + ($fmJUMLAHBARANG*1);
								//set idawal & idlama -----------------------------------								
								if($isMutasi == FALSE){
									$fmIdAwal = 'null';
									$fmIDLama = 'null';
								}else{
									$fmIdAwal = "'".$fmIdAwal."'";
									$fmIDLama = "'".$fmIDLama."'";
								}
								for ($noREG = $Mulai;$noREG < $Sampai;$noREG++){
									$fmJUMLAHHARGA = $fmHARGABARANG*1;						
									$fmN = ($noREG+pow(10,$Main->NOREG_SIZE))."";
									$fmREGISTER = substr($fmN,1,$Main->NOREG_SIZE);						
									
									$Qry = "insert into buku_induk 
										(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,thn_perolehan,jml_barang,satuan,harga,
										jml_harga,asal_usul,status_barang,tgl_update,kondisi, 
										nilai_appraisal, gambar, 								
										tgl_buku, tgl_sensus, idawal,ref_idruang,
										ref_idpemegang,ref_idpemegang2,ref_idpenanggung,
										uid, id_lama, jml_barang_tmp)
											values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
										'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
										'$fmTAHUNANGGARAN','$fmREGISTER','$fmTAHUNPEROLEHAN',1,'$fmSATUAN',
										'$fmHARGABARANG','$fmJUMLAHHARGA','$fmASALUSUL','1',now(),
										'$fmKONDISIBARANG','$nilai_appraisal', '$gambar',
										'".$tgl_buku."','".$tgl_sensus."', $fmIdAwal,'$ref_idruang',
										'$ref_idpemegang','$ref_idpemegang2','$ref_idpenanggung',
										'".$UID."', $fmIDLama, '$fmJUMLAHBARANG')";//, '".$POST['sesi']."$noREG') ";					
									//echo '<br> qry simpan baru bi= '.$Qry;
											
									$Sukses = mysql_query($Qry);//echo "sukses simpan=$Sukses";
									if ($Sukses== FALSE){
										$errmsg = "Gagal simpan BI!";
									}else{
										$Kriteria = "concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg)=
											'$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT$fmSEKSI{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNANGGARAN$fmREGISTER'";
										$idBI = table_get_value('select id from buku_induk where '.$Kriteria,'id');																				
										
										//update idawal	------------------------
										mysql_query(
											"update buku_induk set idawal = id 
											where id='$idBI' and (idawal is null or idawal='' or idawal=0 )"
										);
										
										$InsertHistory = mysql_query(
											"insert into history_barang (a,b,c,d,e,e1,f,g,h,i,j,
											id_bukuinduk,
											tahun,noreg,tgl_update,kejadian,kondisi,status_barang)
											values('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
											'$idBI',
											'$fmTAHUNANGGARAN','$fmREGISTER',
											now(),'Entry Inventaris',
											'$fmKONDISIBARANG','1')"
										);
											
																					
										//SIMPAN KIB A
										if($ArBarang[0]=="01"){						
												if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}
													$Qry = 
													"insert into kib_a (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,luas,
													alamat, alamat_kel, alamat_kec, alamat_a, alamat_b, koordinat_gps, koord_bidang, 
													status_hak,bersertifikat, sertifikat_tgl,sertifikat_no,penggunaan,ket,idbi,alamat_c,kota)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',	
													'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
													'$fmTAHUNANGGARAN','$fmREGISTER','$fmLUAS_KIB_A',
													'$fmLETAK_KIB_A', '$alamat_kel','$alamat_kec','$alamat_a','$alamat_b','$koordinat_gps','$koord_bidang', 
													'$fmHAKPAKAI_KIB_A','$bersertifikat','".$fmTGLSERTIFIKAT_KIB_A."','$fmNOSERTIFIKAT_KIB_A','$fmPENGGUNAAN_KIB_A','$fmKET_KIB_A','$idBI','$alamat_c','$alamat_kota')";
													$Sukses = mysql_query($Qry);
												if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB A!";}
												}
										//SIMPAN KIB B
										else if($ArBarang[0]=="02"){						
													$Qry = "insert into kib_b (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,merk,ukuran,bahan,no_pabrik,
													no_rangka,no_mesin,no_polisi,no_bpkb,ket,idbi)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
													'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
													'$fmTAHUNANGGARAN','$fmREGISTER','$fmMERK_KIB_B','$fmUKURAN_KIB_B','$fmBAHAN_KIB_B',
													'$fmPABRIK_KIB_B','$fmRANGKA_KIB_B','$fmMESIN_KIB_B','$fmPOLISI_KIB_B','$fmBPKB_KIB_B',
													'$fmKET_KIB_B','$idBI')";
													$Sukses = mysql_query($Qry);
												}
										//SIMPAN KIB C
										else if($ArBarang[0]=="03"){		
													if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}				
													$Qry = "insert into kib_c 						
													(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,
													kondisi_bangunan,konstruksi_tingkat,konstruksi_beton,luas_lantai,
													alamat, alamat_kel, alamat_kec, alamat_a, alamat_b, koordinat_gps,koord_bidang,
													dokumen_tgl,dokumen_no,luas,status_tanah,kode_tanah,ket,idbi,alamat_c,kota)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
														'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER',
													'$fmKONDISI_KIB_C','$fmTINGKAT_KIB_C','$fmBETON_KIB_C','$fmLUASLANTAI_KIB_C',
													'$fmLETAK_KIB_C', '$alamat_kel','$alamat_kec','$alamat_a','$alamat_b','$koordinat_gps','$koord_bidang',
													'".$fmTGLGUDANG_KIB_C."','$fmNOGUDANG_KIB_C','$fmLUAS_KIB_C','$fmSTATUSTANAH_KIB_C',
													'$fmNOKODETANAH_KIB_C','$fmKET_KIB_C','$idBI','$alamat_c','$alamat_kota')";
													//echo "<br> qry simpan kibc=$Qry";
													$Sukses = mysql_query($Qry); //echo " sukses simpan=$Sukses";
												if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB C!";}
												}
										//SIMPAN KIB D
										else if($ArBarang[0]=="04"){	
													if ($alamat_b !=''){$alamat_a = $Main->DEF_PROPINSI;}					
													$Qry = "insert into kib_d (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,konstruksi,
													panjang,lebar,luas,
													alamat,alamat_kel, alamat_kec, alamat_a, alamat_b, koordinat_gps,koord_bidang,
													dokumen_tgl,dokumen_no,status_tanah,kode_tanah,kondisi,ket,idbi,alamat_c,kota)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
														'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','$fmKONSTRUKSI_KIB_D','$fmPANJANG_KIB_D','$fmLEBAR_KIB_D','$fmLUAS_KIB_D',
													'$fmALAMAT_KIB_D', '$alamat_kel','$alamat_kec','$alamat_a','$alamat_b','$koordinat_gps','$koord_bidang',
													'".$fmTGLDOKUMEN_KIB_D."','$fmNODOKUMEN_KIB_D','$fmSTATUSTANAH_KIB_D',
													'$fmNOKODETANAH_KIB_D','$fmKONDISI_KIB_D','$fmKET_KIB_D','$idBI','$alamat_c','$alamat_kota')";
													//echo $Qry;
													$Sukses = mysql_query($Qry);
												if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB D!";}
												}
										//SIMPAN KIB E
										else if($ArBarang[0]=="05"){						
													$Qry = "insert into kib_e (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,buku_judul,buku_spesifikasi,
													seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,ket,idbi)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
													'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
													'$fmTAHUNANGGARAN','$fmREGISTER','$fmJUDULBUKU_KIB_E','$fmSPEKBUKU_KIB_E','$fmSENIBUDAYA_KIB_E',
													'$fmSENIPENCIPTA_KIB_E','$fmSENIBAHAN_KIB_E','$fmJENISHEWAN_KIB_E','$fmUKURANHEWAN_KIB_E',
													'$fmKET_KIB_E','$idBI')";
													//echo $Qry;
													$Sukses = mysql_query($Qry);
												if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB E!";}
												}
										//SIMPAN KIB F
										else if($ArBarang[0]=="06"){						
													$Qry = "insert into kib_f (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,
													bangunan,konstruksi_tingkat,konstruksi_beton,luas,
													alamat, alamat_kel, alamat_kec, alamat_a, alamat_b, koordinat_gps,koord_bidang,
													dokumen_tgl,dokumen_no,tmt,status_tanah,kode_tanah,ket,idbi,alamat_c,kota)
														values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
													'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
													'$fmTAHUNANGGARAN','$fmREGISTER','$fmBANGUNAN_KIB_F','$fmTINGKAT_KIB_F','$fmBETON_KIB_F','$fmLUAS_KIB_F',
													'$fmLETAK_KIB_F', '$alamat_kel','$alamat_kec','$alamat_a','$alamat_b','$koordinat_gps','$koord_bidang',
													'".$fmTGLDOKUMEN_KIB_F."','$fmNODOKUMEN_KIB_F','".$fmTGLMULAI_KIB_F."','$fmSTATUSTANAH_KIB_F',
													'$fmNOKODETANAH_KIB_F','$fmKET_KIB_F','$idBI','$alamat_c','$alamat_kota')";
													//echo $Qry;
													$Sukses = mysql_query($Qry);
												if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB F!";}
										}
										else if($ArBarang[0]=="07"){						
											$Qry = "insert into kib_g (a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,
												uraian, ket, idbi)
												values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','$fmSEKSI',
												'{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
												'$fmTAHUNANGGARAN','$fmREGISTER',												
												'$fmURAIAN_KIB_G', '$fmKET_KIB_G','$idBI')";
												//echo $Qry;
											$Sukses = mysql_query($Qry);
											if ($Sukses== FALSE){$errmsg = "Gagal simpan KIB G!";}
										}
												
										$cek .= '<br> qrykib simpan baru='.$Qry;
									}
								}
								
							}else{
								//$Info = "<script>alert('Data TIDAK dapat disimpan \\n Nomor Register $fmREGISTER tahun $fmTAHUNPEROLEHAN sudah ada!!!')</script>";
								$errmsg = "Data TIDAK dapat disimpan \\n Nomor Register $fmREGISTER tahun $fmTAHUNPEROLEHAN sudah ada!!!";
							}
							break;
						}	
					}
							//echo "<br>Sukses=$Sukses";
					//if($Sukses){
					if($errmsg==''){
						/*
						//pindahkan gambar --------------------				
						if ($gambar != $gambar_old){
							if (copy('tmp/'.$gambar,'gambar/'.$gambar)){
								unlink('tmp/'.$gambar);
							if($gambar_old!=''){ unlink('gambar/'.$gambar_old);}
								}else{
								echo 'gagal copy file';
							}
							}			
						//pindahkan dokumen --------------------
						if ($dokumen_file != $dokumen_file_old){
							if (copy('tmp/'.$dokumen_file,'dokum/'.$dokumen_file)){
								unlink('tmp/'.$dokumen_file);
							if($dokumen_file_old!=''){ unlink('dokum/'.$dokumen_file_old);}
								}else{
								echo 'gagal copy file';
							}
						}
						*/			
						$Info = "<script>alert('Data telah di simpan');</script>";
						$Baru='';//$Baru="0"; 
						//$cek .= '<br> baru after simpan ='.$Baru;
						$Act='';//'<br> Act after simpan ='.$Act;
						//$fmIDLama = '';
					}else{
						//$Info = "<script>alert('Data TIDAK dapat disimpan \\n Nomor Register $fmREGISTER tahun $fmTAHUNPEROLEHAN sudah ada!!!')</script>";
						$Info = "<script>alert('$errmsg')</script>";
					}	
							
				}else{
					$Info = "<script>alert('".$errmsg."')</script>";
					$Act = "Edit";
				}
				break;
			}	
				
			case 'Hapus':{
				if($Act=="Hapus" && count($cidBI) > 0){
					for($i = 0; $i<count($cidBI); $i++)	{
						$aqry = "select * from buku_induk where id='{$cidBI[$i]}'";
						//echo $aqry.' '.$cidBI[$i] ;
						$Qry = mysql_query($aqry);		
						
							
						//*

											
						if($isi = mysql_fetch_array($Qry))	{
				$xid=$isi['id'];
				if ($errmsg=='') $errmsg=Penatausahaan_CekdataCutoff('hapus',$xid,'');								
							$e1 =$Main->SUB_UNIT ? $isi['e1'].'.' : '';
								$kdbrg= $isi['a1'].'.'.$isi['a'].'.'.$isi['b'].'.'.$isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$e1.
								$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'.'.
								$isi['noreg'].'.'.$isi['tahun'];
							if($isi['status_barang']!=1){$errmsg = 'Hanya barang dengan status Inventaris yang dapat dihapus!';}
								//$idawal = $isi['idawal'] == ''? $isi['id'] : $isi['idawal'];
								//if ($isi['idawal'] == ''){
							if ( $errmsg=='' && table_get_value("select count(*) as cnt from gambar where idbi =".$isi['id'],'cnt')>0 ){
								$errmsg = 'Data Gambar masih ada!';
							}
							if ( $errmsg=='' && table_get_value("select count(*) as cnt from dokum where idbi =".$isi['id'],'cnt')>0 ){
								$errmsg = 'Data Dokumen masih ada!';
							}
									
								
							if ( $errmsg=='' && table_get_value("select count(*) as cnt from pemeliharaan where idbi_awal =".$isi['id'],'cnt')>0 ){
								$errmsg = 'Data Pemeliharaan masih ada!';
							}
							if ( $errmsg=='' && table_get_value("select count(*) as cnt from pengamanan where idbi_awal =".$isi['id'],'cnt')>0 ){
								$errmsg = 'Data Pengamanan masih ada!';
							}
							if ( $errmsg=='' && table_get_value("select count(*) as cnt from pemanfaatan where idbi_awal =".$isi['id'],'cnt')>0 ){
								$errmsg = 'Data Pemanfaatan masih ada!';
							}
							//$errmsg = 'tes';
					
							if($errmsg == ''){
								$e1_2 = $Main->SUB_UNIT ? " e1 = '{$isi['e1']}' and ": ''; 
								$KondisiEditKIB =		"
									a1= '{$isi['a1']}' and 
									a = '{$isi['a']}' and 
									b = '{$isi['b']}' and 
									c = '{$isi['c']}' and 
									d = '{$isi['d']}' and 
									e = '{$isi['e']}' and
									$e1_2 
									f = '{$isi['f']}' and 
									g = '{$isi['g']}' and 
									h = '{$isi['h']}' and 
									i = '{$isi['i']}' and 
									j = '{$isi['j']}' and 
									noreg = '{$isi['noreg']}' and 
									tahun = '{$isi['tahun']}' 
									";			
									//hapus gambar & dokumen ---------------------	
									//if ($isi['gambar'] != ''){	unlink('gambar/'.$isi['gambar']);	}						
								//if ($isi['dokumen_file'] != ''){ unlink('dokum/'.$isi['dokumen_file']);	}
									//Dok_HapusByIdBI($isi['id']);
								//*	
									//hapus detail kib 
								if($isi['f']=="01"){$DelKIB = mysql_query("delete from kib_a where $KondisiEditKIB limit 1");}
								if($isi['f']=="02"){$DelKIB = mysql_query("delete from kib_b where $KondisiEditKIB limit 1");}
								if($isi['f']=="03"){$DelKIB = mysql_query("delete from kib_c where $KondisiEditKIB limit 1");}
								if($isi['f']=="04"){$DelKIB = mysql_query("delete from kib_d where $KondisiEditKIB limit 1");}
								if($isi['f']=="05"){$DelKIB = mysql_query("delete from kib_e where $KondisiEditKIB limit 1");}
								if($isi['f']=="06"){$DelKIB = mysql_query("delete from kib_f where $KondisiEditKIB limit 1");}			
									//hapus bi
								$Del = mysql_query("delete from buku_induk where id='{$cidBI[$i]}' limit 1");
									//set belum mutasi di Penghapusan
								$Del = mysql_query("update penghapusan set mutasi=0 where id_bukuinduk='{$cidBI[$i]}' and mutasi=1");
								$sqry = "insert into history_barang (a1, a,b,c,d,e,e1,f,g,h,i,j,
									id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,uid)
										values('".$isi['a1']."', 
									'".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."',
									'".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."',
									'".$isi['id']."','".$isi['thn_perolehan']."','".$isi['noreg']."',
									now(),'Delete Inventaris','','".$UID."')";
									//echo $sqry;
								$InsertHistory = mysql_query($sqry);
								//*/
								$Info = "<script>alert('Data telah di hapus!'  )</script>";
									//$Info='';
							}else{
									//$Act = '';
									$Info = "<script>alert('Gagal Hapus No. $kdbrg. $errmsg')</script>";
									break;
							}
						}
							//*/
							
					}
					$Act ="";
				}
				break;
			}	
			//*	
			case 'barcode':{
				$err = genBarcode();
				
				break;
			}//*/	
				
		}
		//$Sukses = FALSE;
	}

function cariDKB($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="", 
		$DisAbled="", $Param="", $idbi='', $c='', $d='', $e='', $e1='') 
{
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
	$IDBI = 'idbi';
	$THN = 'thn_perolehan';
	$NOREG = 'noreg';
	$LOKASI = 'lokasi';
    $in = "
	<input type=hidden id='$IDBI' name='$IDBI' value='$idbi' readonly=''  >
	<input type=text id='$ID' name='$ID' value='$VALID' size='15' readonly=''  > 
	<!--<input type=text id='$NM' name='$NM' value='$VALID' size='15' readonly=''  > -->
	
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objIDBI=$IDBI&objNM=$NM&kdBrgOld=$VALID&Act=$Act&objTHN=$THN&objNOREG=$NOREG&objLOKASI=$LOKASI&c=$c&d=$d&e=$e&e1=$e1'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> 
		<input type=button value='Cari' onClick=\"TampilkanIFrameDKB(document.all.cari$NMIFRAME)\" > 
		";
    return $in;
}

function cariDKPB($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="", 
		$DisAbled="", $Param="", $idbi='', $c='', $d='', $e='', $e1='') 
{
	/***********************
	* $idbi = id dkpb
	************************/
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
	$IDBI = 'idbi';
	$THN = 'thn_perolehan';
	$NOREG = 'noreg';
	$LOKASI = 'lokasi';
    $in = "
	<input type=hidden id='$IDBI' name='$IDBI' value='$idbi' readonly=''  >
	<input type=text id='$ID' name='$ID' value='$VALID' size='15' readonly=''  > 
	<!--<input type=text id='$NM' name='$NM' value='$VALID' size='15' readonly=''  > -->
	
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objIDBI=$IDBI&objNM=$NM&kdBrgOld=$VALID&Act=$Act&objTHN=$THN&objNOREG=$NOREG&objLOKASI=$LOKASI&c=$c&d=$d&e=$e&e1=$e1'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> 
		<input type=button value='Cari' onClick=\"TampilkanIFrameDKPB(document.all.cari$NMIFRAME)\" > 
		";
    return $in;
}
function cariDPB($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="",  //penerimaan
		$DisAbled="", $Param="", $idbi='', $c='', $d='', $e='', $e1='') 
{
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
	$IDBI = 'idbi';
	$THN = 'thn_perolehan';
	$NOREG = 'noreg';
	$LOKASI = 'lokasi';
    $in = "
	<input type=hidden id='$IDBI' name='$IDBI' value='$idbi' readonly=''  >
	<input type=text id='$ID' name='$ID' value='$VALID' size='15' readonly=''  > 
	<!--<input type=text id='$NM' name='$NM' value='$VALID' size='15' readonly=''  > -->
	
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objIDBI=$IDBI&objNM=$NM&kdBrgOld=$VALID&Act=$Act&objTHN=$THN&objNOREG=$NOREG&objLOKASI=$LOKASI&c=$c&d=$d&e=$e&e1=$e1'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> 
		<input type=button value='Cari' onClick=\"TampilkanIFrameDPB(document.all.cari$NMIFRAME)\" > 
		";
    return $in;
}

function cariPenerimaan($formNYA, $URL, $URLC, $ID="", $NM="", $ReadOnly="",  //penerimaan
		$DisAbled="", $Param="", $idbi='', $c='', $d='', $e='', $e1='') 
{
    global $Act, $$ID, $$NM;
    $VALID = $$ID;
    $VALNM = $$NM;
    $NMIFRAME = "iframe$ID";
	$IDBI = 'idbi';
	$THN = 'thn_perolehan';
	$NOREG = 'noreg';
	$LOKASI = 'lokasi';
    $in = "
	<input type=hidden id='$IDBI' name='$IDBI' value='$idbi' readonly=''  >
	<input type=text id='$ID' name='$ID' value='$VALID' size='15' readonly=''  > 
	<!--<input type=text id='$NM' name='$NM' value='$VALID' size='15' readonly=''  > -->
	
	<iframe id='cari$NMIFRAME' style=';position:absolute;visibility:hidden' 
		name='cari$NMIFRAME' src='$URLC?objID=$ID&objIDBI=$IDBI&objNM=$NM&kdBrgOld=$VALID&Act=$Act&objTHN=$THN&objNOREG=$NOREG&objLOKASI=$LOKASI&c=$c&d=$d&e=$e&e1=$e1'>
	</iframe> 
	<input $Param  type=text name='$NM' value='$VALNM' size='60' readonly> 
		<input type=button value='Cari' onClick=\"TampilkanIFrameDPB(document.all.cari$NMIFRAME)\" > 
		";
    return $in;
}

function getkota($alamat_b='')
{
		$nm_kota="";
		if ($alamat_b<>''){
			
		
		$get = mysql_fetch_array(mysql_query(
				"select * from ref_kotakec where kd_kota='".$alamat_b."'  and kd_kec='0'"
			));		
			if($get['nm_wilayah']<>'') $nm_kota = $get['nm_wilayah'];
		$nm_kota = $nm_kota!=""?$nm_kota: $isi['kota'];
		}
	return $nm_kota;
	
}

function getkecamatan($alamat_b='',$alamat_c='')
{
		
		$nm_kec ="";
		if ($alamat_b<>'' && $alamat_c<>'')
		{
		$get = mysql_fetch_array(mysql_query(
		"select * from ref_kotakec where kd_kota='".$alamat_b."'  and kd_kec='".$alamat_c."'"
			));		
			if($get['nm_wilayah']<>'') $nm_kec = $get['nm_wilayah'];
		$nm_kec = $nm_kec!="" ? $nm_kec: $isi['alamat_kec'];
		}
	return $nm_kec;	
}

function getalamat($alamat_b,$alamat_c,$alamat='',$alamat_kota='',$alamat_kec='',$alamat_kel='')
{
		$nm_kota="";
		if ($alamat_b<>''){
			
		
		$get = mysql_fetch_array(mysql_query(
				"select * from ref_kotakec where kd_kota='".$alamat_b."'  and kd_kec='0'"
			));		
			if($get['nm_wilayah']<>'') $nm_kota = $get['nm_wilayah'];
		}
		$nm_kec="";
		if ($alamat_b<>'' && $alamat_c<>''){
			
		
		$get = mysql_fetch_array(mysql_query(
				"select * from ref_kotakec where kd_kota='".$alamat_b."'  and kd_kec='".$alamat_c."'"
			));		
			if($get['nm_wilayah']<>'') $nm_kec = $get['nm_wilayah'];
		}

		$alm = '';
//		$alm .= ifempty($alamat,'-');		
		$alm .= $alamat;		
		$alm .= $alamat_kel != ''? '<br>Kel. '.$alamat_kel : '';
		$alm .= $nm_kec != ''? '<br>Kec. '.$nm_kec : '';
		if ($nm_kec!=''){
		$alm .= $alamat_kec != ''? '<br>Kec. '.$alamat_kec : '';
			
		}
		$alm .= $nm_kota != ''? '<br>'.$nm_kota : '';
		if ($nm_kota!=''){
		$alm .= $alamat_kota != ''? '<br>'.$alamat_kota : '';
			
		}

		return $alm;
}
function Penatausahaan_CekdataCutoff($mode='insert',$id='',$tgl='',$idbi=''){
global $Main;

$usrlevel=$Main->UserLevel;
$errmsg='';
if ($usrlevel!='1'){
	

$tglcutoff=$Main->TAHUN_CUTOFF."-12-31";

switch ($mode){
	

case 'insert':{
	if ($tgl<$tglcutoff) $errmsg="Tgl. buku(".$tgl.") lebih kecil dari  tgl. cut off (".$tglcutoff.")";

		 	
	break;
}
case 'edit':{
//	if ($tgl<$tglcutoff) $errmsg="Data dengan tgl. penghapusan lebih kecil dari tgl. ".$tglcutoff; 
			//cek tanggal buku
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from buku_induk where id='$id'"));
			$tglx=$datax['tgl_buku'];	
			
			if ($tglx<=$tglcutoff)
				$errmsg="Data dengan tgl. buku(".$tglx.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat diedit";
	}

	break;
}
case 'hapus':{
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from buku_induk where id='$id'"));
			$tgl=$datax['tgl_buku'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. buku(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat dihapus";
	}
	
	break;
}	
}

}
return $errmsg;

}

function PrintTTD_SensusKK($thnsensus='2013',$pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA, $fmSEKSI;


    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    $vTITIKMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    if (c == '04') {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1 = '$fmSEKSI' and ttd1 = '1' ");
    } else {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e1 = '$kdSubUnit0' and ttd1 = '1' ");
    }
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD1 = $isi['nik'];
        $NAMASKPD1 = $isi['nm_pejabat'];
        $JABATANSKPD1 = $isi['jabatan'];
    }
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and e1 = '$fmSEKSI' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
        $JABATANSKPD2 = $isi['jabatan'];
    }
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='Bandung,  ". JuyTgl1(date("Y-m-d"))."' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='MENGETAHUI' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='KOORDINATOR TU SUBUNIT PELAYANAN'	STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN1B		= "<INPUT TYPE=TEXT VALUE='/UPTD/UPTB/OPD'	STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";		
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='PELAKSANA/PETUGAS SENSUS' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >Bandung,  ". JuyTgl1(date("Y-m-d"))."' </span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KOORDINATOR TU SUBUNIT PELAYANAN</span>";
		$vJABATAN1B		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >/UPTD/UPTB/OPD</span>";		
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PELAKSANA/PETUGAS SENSUS</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR> 
					$vJABATAN1<BR>
					$vJABATAN1B
					<BR><BR><BR><BR><BR>
					$vNAMA1
					<br>
					$vNIP1
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp4'>
					$vTITIKMANGSA<BR> 
					<!--$vMENGETAHUI-->
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}
function getTahunSensus(){
	global $Main;
	$thnskr = date('Y');		
	$tahun_sensus = $Main->thnsensus_default;
	while ( ($tahun_sensus+ $Main->periode_sensus) <= $thnskr  ){
		$tahun_sensus+= $Main->periode_sensus;
	}
	return $tahun_sensus;//$thnskr;
}

function Mutasi_RekapByBrg_GetList2_Keu($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT,  
	$noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $cetak=FALSE, 
	$Style=1, $fmSEKSI='00', $fmKONDBRG='7',$jns_aset=0,$jmlBarangAwal=0,$jmlHargaAwal=0,$tampiltot=0) {
    // ------------------------------
    // $Style=1 = total penambahan, 2= pemelihara, pemgaman, peroleh, 3 = saldo akhir sampai dgn tglakhir
    // ------------------------------
    global $Main;
    global $tglAwal, $tglAkhir; //$fmSemester, $fmTahun;

	$cek='';
	
	if ($jns_aset==1){
		$Kondisi_aset=" and aset_tetap=0 ";
	} else {
		$Kondisi_aset=" and aset_tetap=1 ";		
	}

    $clGaris = $cetak == TRUE ? "GarisCetak" : "GarisDaftar";
    //get kondisi (skpd) ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI =='' || $fmSEKSI == "000" || $fmSEKSI == "00"  ? "" : " and e1='$fmSEKSI' ";
    $Kondisi = " a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					 and c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 $Kondisi_aset ";
    if ($fmSKPD == "00") {
        $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE $KondisiE1 $Kondisi_aset ";
    }
	
	//kondisi barang
/*	
	switch($fmKONDBRG){
		case '1' :case '2': case '3': case '4' :{
			$Kondisi .= $Kondisi==''? " kondisi = '$fmKONDBRG' ": " and kondisi = '$fmKONDBRG' ";
			break;
		}
		case '5' :{
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}
	}
*/
//	$Kondisi .=  $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
    //list --------------------------------------------------------------
    $ListData = "";
    $cb = 0;
    $no = $noawal;
	
	/*
	#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f and aa.g=jj.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f and aa.g=kk.g 			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f and aa.g=ll.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f and aa.g=mm.g 
			
	*/
    $sqry = "
		
		select aa.f, aa.g,  aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir,
			nn.jmlHSBG_awal, nn.jmlHrgHSBG_awal,
			oo.jmlHSBG_akhir, oo.jmlHrgHSBG_akhir,
			pp.jmlHrgHPS_HSBG_awal,
			qq.jmlHrgHPS_HSBG_akhir
			
			 ".		
		"from ref_barang aa 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from view_buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from view_buku_induk  where $Kondisi and  tgl_buku <= '$tglAkhir'  group by f,g with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_awal, sum(harga_hapus) as jmlHrgHSBG_awal from v_hapus_sebagian where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) nn on aa.f=nn.f and aa.g=nn.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_akhir, sum(harga_hapus ) as jmlHrgHSBG_akhir from v_hapus_sebagian where $Kondisi  and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) oo on aa.f=oo.f and aa.g=oo.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus ) as jmlHrgHPS_HSBG_awal from v2_penghapusan_hapussebagian where  tgl_penghapusan < '$tglAwal' $KondisiKIB group by f,g with rollup ) pp on aa.f=pp.f  and aa.g=pp.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus) as jmlHrgHPS_HSBG_akhir from v2_penghapusan_hapussebagian where  tgl_penghapusan <= '$tglAkhir'  $KondisiKIB group by f,g with rollup ) qq on aa.f=qq.f  and aa.g=qq.g  

			".			
		" where aa.h='00'  
		order by aa.f, aa.g		
	"; // echo "$sqry";
	// $cek.=$sqry;
    $QryRefBarang = mysql_query($sqry);    
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;    
	$no=0;
    while ($isi = mysql_fetch_array($QryRefBarang)) {
        //get kondisi1 (barang) ----------------------------------
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
        $KondisiBi = " status_barang<>3 ";
		$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
		$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";

        //data --------------------------------------------------
		//penghapusan
        $jmlBrgHPS_awal = $isi['jmlBrgHPS_awal'];
		$jmlHrgHPS_awal = $isi['jmlHrgHPS_awal'];		
		$jmlBrgHPS_akhir = $isi['jmlBrgHPS_akhir'];
		$jmlHrgHPS_akhir = $isi['jmlHrgHPS_akhir'];		
		$jmlBrgHPS_curr = $jmlBrgHPS_akhir - $jmlBrgHPS_awal;
		$jmlHrgHPS_curr = $jmlHrgHPS_akhir - $jmlHrgHPS_awal;
		//buku_induk
//        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
		$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
		$jmlBrgBI_curr = $jmlBrgBI_akhir - $jmlBrgBI_awal;
		$jmlHrgBI_awal = $isi['jmlHrgBI_awal'];		       
		$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
		$jmlHrgBI_curr = $jmlHrgBI_akhir - $jmlHrgBI_awal;		
		//pemelihara
        $jmlHrgPLH_awal = $isi['jmlHrgPLH_awal'];		
        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
		$jmlHrgPLH_curr = $jmlHrgPLH_akhir - $jmlHrgPLH_awal;		
		//pengaman
        $jmlHrgAman_awal = $isi['jmlHrgAman_awal'];
		$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
        $jmlHrgAman_curr = $jmlHrgAman_akhir - $jmlHrgAman_awal;
		//hapus sebagian
        $jmlHrgHSBG_awal = $isi['jmlHrgHSBG_awal'];		
        $jmlHrgHSBG_akhir = $isi['jmlHrgHSBG_akhir'];
		$jmlHrgHSBG_curr = $jmlHrgHSBG_akhir - $jmlHrgHSBG_awal;		
		
		//hapus pelihara
		$jmlHrgHPS_PLH_awal = $isi['jmlHrgHPS_PLH_awal'];   
		$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
		$jmlHrgHPS_PLH_curr = $jmlHrgHPS_PLH_akhir - $jmlHrgHPS_PLH_awal;
		//hapus pengaman
		$jmlHrgHPS_Aman_awal = $isi['jmlHrgHPS_Aman_awal']; 		   		
		$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
		$jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal;
		//hapus hapus sebagian
		$jmlHrgHPS_HSBG_awal = $isi['jmlHrgHPS_HSBG_awal'];   
		$jmlHrgHPS_HSBG_akhir = $isi['jmlHrgHPS_HSBG_akhir'];   
		$jmlHrgHPS_HSBG_curr = $jmlHrgHPS_HSBG_akhir - $jmlHrgHPS_HSBG_awal;
		
		//mutasi pelihara
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_PLH_akhir = $get['sumbiaya'];
		$jmlHrgMut_PLH_curr = $jmlHrgMut_PLH_akhir - $jmlHrgMut_PLH_awal;
		//mutasi pengaman
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_Aman_awal = $get['sumbiaya'];	
		$get= mysql_fetch_array( mysql_query(			
			"select f, sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		 
		$jmlHrgMut_Aman_akhir = $get['sumbiaya']; 	  
		$jmlHrgMut_Aman_curr = $jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal;	

		//mutasi hapus sebagian
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian
			where $Kondisi and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian 
			where $Kondisi and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_HSBG_akhir = $get['sumbiaya'];
		$jmlHrgMut_HSBG_curr = $jmlHrgMut_HSBG_akhir - $jmlHrgMut_HSBG_awal;
			
		//pindahtangan ----------------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_awal = $get['sumbrg'];
		$jmlHrgPindah_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_akhir = $get['sumbrg'];
		$jmlHrgPindah_akhir = $get['sumbiaya'];		
		$jmlHrgPindah_curr = $jmlHrgPindah_akhir - $jmlHrgPindah_awal;
		//pindahtangan pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_PLH_curr = $jmlHrgPindah_PLH_akhir - $jmlHrgPindah_PLH_awal;	
		//pindahtangan pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_akhir = $get['sumbiaya'];
		$jmlHrgPindah_Aman_curr = $jmlHrgPindah_Aman_akhir - $jmlHrgPindah_Aman_awal;	
		//pindahtangan hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and  tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_HSBG_curr = $jmlHrgPindah_HSBG_akhir - $jmlHrgPindah_HSBG_awal;	
		
		//gantirugi --------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi
			where $Kondisi and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_awal = $get['sumbrg'];
		$jmlHrgGantirugi_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi 
			where $Kondisi and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_akhir = $get['sumbrg'];
		$jmlHrgGantirugi_akhir = $get['sumbiaya'];		
		$jmlHrgGantirugi_curr = $jmlHrgGantirugi_akhir - $jmlHrgGantirugi_awal;
		//Gantirugi pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_PLH_curr = $jmlHrgGantirugi_PLH_akhir - $jmlHrgGantirugi_PLH_awal;	
		//Gantirugi pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_akhir = $get['sumbiaya'];
		$jmlHrgGantirugi_Aman_curr = $jmlHrgGantirugi_Aman_akhir - $jmlHrgGantirugi_Aman_awal;

		//Gantirugi hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_HSBG_curr = $jmlHrgGantirugi_HSBG_akhir - $jmlHrgGantirugi_HSBG_awal;	
			
		
        //hitung row --------------------------------------------------------------------------        
        //saldo awal
		$jmlBrg_awal = $jmlBrgBI_awal - ($jmlBrgHPS_awal + $jmlBrgPindah_awal + $jmlBrgGantirugi_awal);        
		$jmlHrg_awal = 
			($jmlHrgBI_awal + $jmlHrgPLH_awal + $jmlHrgAman_awal +  $jmlHrgMut_PLH_awal+ $jmlHrgMut_Aman_awal) - 
			($jmlHrgHPS_awal + $jmlHrgHPS_PLH_awal + $jmlHrgHPS_Aman_awal + 
			$jmlHrgPindah_awal + $jmlHrgPindah_PLH_awal + $jmlHrgPindah_Aman_awal +
			$jmlHrgGantirugi_awal + $jmlHrgGantirugi_PLH_awal + $jmlHrgGantirugi_Aman_awal 
			); 
        //bertambah
		$jmlBrgTambah_curr = $jmlBrgBI_curr;						
		$jmlHrgTambahBi_curr = $jmlHrgBI_curr;
		$jmlHrgTambahPLH_curr = $jmlHrgPLH_curr + $jmlHrgMut_PLH_curr;
		$jmlHrgTambahAman_curr = $jmlHrgAman_curr + $jmlHrgMut_Aman_curr;
		$jmlHrgTambahHSBG_curr = $jmlHrgHPS_HSBG_curr + $jmlHrgPindah_HSBG_curr + $jmlHrgGantirugi_HSBG_curr;

		$jmlHrgTambah_curr = $jmlHrgTambahPLH_curr + $jmlHrgTambahAman_curr + $jmlHrgTambahBi_curr+$jmlHrgTambahHSBG_curr;
		//berkurang
		$jmlBrgKurang_curr = $jmlBrgHPS_curr + $jmlBrgPindah_curr + $jmlBrgGantirugi_curr;
		$jmlHrgKurangPLH_curr = $jmlHrgHPS_PLH_curr + $jmlHrgPindah_PLH_curr + $jmlHrgGantirugi_PLH_curr;
		$jmlHrgKurangAman_curr = $jmlHrgHPS_Aman_curr + $jmlHrgPindah_Aman_curr + $jmlHrgGantirugi_Aman_curr;
		$jmlHrgKurangBi_curr = $jmlHrgHPS_curr + $jmlHrgPindah_curr + $jmlHrgGantirugi_curr;
		$jmlHrgKurangHSBG_curr = $jmlHrgHSBG_curr + $jmlHrgMut_HSBG_curr;


		$jmlHrgKurang_curr =  $jmlHrgKurangPLH_curr + $jmlHrgKurangAman_curr +  $jmlHrgKurangBi_curr+$jmlHrgKurangHSBG_curr;
		
		//echo "<br> $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr ";
		
		/*echo " $jmlHrgKurang_curr = 
			$jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr +
			$jmlHrgPindah_curr + $jmlHrgPindah_PLH_curr + $jmlHrgPindah_Aman_curr; <br> ";	
        */
        //akhir
		//$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir;
		$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir - $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
        $jmlHrg_akhir = 
			($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir + $jmlHrgMut_PLH_akhir+ $jmlHrgMut_Aman_akhir
			+ $jmlHrgHPS_HSBG_akhir + $jmlHrgPindah_HSBG_akhir+ $jmlHrgGantirugi_HSBG_akhir ) - 
			($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir +
			$jmlHrgPindah_akhir + $jmlHrgPindah_PLH_akhir + $jmlHrgPindah_Aman_akhir +
			$jmlHrgGantirugi_akhir + $jmlHrgGantirugi_PLH_akhir + $jmlHrgGantirugi_Aman_akhir +
			$jmlHrgHSBG_akhir+ $jmlHrgMut_HSBG_akhir
			);
        
		//hit total --------------------------------------------------------------------------------
        //awal ----------------------------------------
		$totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
		
		//kurang barang --------------------------------
        $totBrgHPS_curr += $isi['g'] == "00" ? $jmlBrgKurang_curr : 0;
		//kurang harga
		$totHrgKurang_curr += $isi['g'] == "00" ? $jmlHrgKurang_curr : 0;		
		//kurang pelihara
		$totHrgHPS_PLH_curr += $isi['g'] == "00" ? $jmlHrgKurangPLH_curr : 0;
		//kurang aman
		$totHrgHPS_Aman_curr += $isi['g'] == "00" ? $jmlHrgKurangAman_curr : 0;		
		//kurang perolehan
		$totHrgHPS_curr += $isi['g'] == "00" ? $jmlHrgKurangBi_curr : 0;//?
		
        //tambah barang --------------------------------
        $totBrgTambah_curr += $isi['g'] == "00" ? $jmlBrgTambah_curr : 0;
		//tambah harga
		$totHrgTambah_curr += $isi['g'] == "00" ? $jmlHrgTambah_curr : 0;		
		//tambah pelihara		
		$totHrgPLH_curr += $isi['g'] == "00" ? $jmlHrgTambahPLH_curr : 0;
		//$totHrgMut_PLH_curr += $isi['g'] == "00" ? $jmlHrgMut_PLH_curr : 0;
		//tambah aman
		$totHrgAman_curr += $isi['g'] == "00" ? $jmlHrgTambahAman_curr : 0;
		//$totHrgMut_Aman_curr += $isi['g'] == "00" ? $jmlHrgMut_Aman_curr : 0;		
		//tambah perolehan
        $totHrgBI_curr += $isi['g'] == "00" ? $jmlHrgTambahBi_curr : 0;		
		
		//akhir ----------------------------------------
        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
		
		
		
		
        //tampil row --------------------------------------------------
        //dlm ribuan
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, ',', '.') : number_format($jmlHrg_awal, 2, ',', '.');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambah_curr, 2, ',', '.');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahPLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahPLH_curr, 2, ',', '.');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahAman_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahAman_curr, 2, ',', '.');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahBi_curr / 1000), 2, ',', '.') : number_format($jmlHrgTambahBi_curr, 2, ',', '.');
        
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurang_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangBi_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangBi_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangPLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangPLH_curr, 2, ',', '.');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangAman_curr / 1000), 2, ',', '.') : number_format($jmlHrgKurangAman_curr, 2, ',', '.');
        
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		
		//$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_PLH_curr, 2, ',', '.');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, ',', '.') : number_format($jmlHrgMut_Aman_curr, 2, ',', '.');
        
		//bold
        $tampil_jmlBrg_awal = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_awal, 0, ',', '.') . "" : "" . number_format($jmlBrg_awal, 0, ',', '.') . "";
        $tampil_jmlBrgHPS_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgHPS_curr, 0, ',', '.') . "";
        $tampil_jmlBrgTambah_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "" : "" . number_format($jmlBrgTambah_curr, 0, ',', '.') . "";
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_awal = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_awal . "" : $tampil_jmlHrg_awal;
        
		
        $tampil_jmlHrgTambah_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgTambah_curr . "" : $tampil_jmlHrgTambah_curr;
        $tampil_jmlHrgPLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgPLH_curr . "" : $tampil_jmlHrgPLH_curr;
        $tampil_jmlHrgAman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgAman_curr . "" : $tampil_jmlHrgAman_curr;
        
		$tampil_jmlHrgBI_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgBI_curr . "" : $tampil_jmlHrgBI_curr;
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		
		$tampil_jmlHrgKurang_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgKurang_curr . "" : $tampil_jmlHrgKurang_curr;
		$tampil_jmlHrgHPS_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_curr . "" : $tampil_jmlHrgHPS_curr;
		$tampil_jmlHrgHPS_PLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_PLH_curr . "" : $tampil_jmlHrgHPS_PLH_curr;
		$tampil_jmlHrgHPS_Aman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_Aman_curr . "" : $tampil_jmlHrgHPS_Aman_curr;
		$tampil_jmlHrgMut_PLH_curr = addbold( number_format_ribuan($jmlHrgMut_PLH_curr, $dlmRibuan), $isi['g'] == "00" );
		$tampil_jmlHrgMut_Aman_curr = addbold( number_format_ribuan($jmlHrgMut_Aman_curr, $dlmRibuan), $isi['g'] == "00" );
        //with td
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgTambah_curr</td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgPLH_curr</td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgAman_curr</td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgBI_curr</td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgKurang_curr</td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_curr</td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_PLH_curr</td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_Aman_curr</td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_PLH_curr</td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_Aman_curr</td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					
				";
				
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                    break;
                }
            case 3: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
			
                    break;
                }
        }
        //$tampil_jmlHrgTambah_curr='';
        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			$TampilStyle
        </tr>
		";
    }
    //tampil total -------------------------------------
	//if($noawal == 0  ){
		
	
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, ',', '.') : number_format($totHrg_awal, 2, ',', '.');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, ',', '.') : number_format($totHrgTambah_curr, 2, ',', '.');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, ',', '.') : number_format($totHrgPLH_curr, 2, ',', '.');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, ',', '.') : number_format($totHrgAman_curr, 2, ',', '.');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, ',', '.') : number_format($totHrgBI_curr, 2, ',', '.');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, ',', '.') : number_format($totHrg_akhir, 2, ',', '.');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, ',', '.') : number_format($totHrgKurang_curr, 2, ',', '.');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_PLH_curr, 2, ',', '.');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, ',', '.') : number_format($totHrgHPS_Aman_curr, 2, ',', '.');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgTambah_curr . "</td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgPLH_curr . "</td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgAman_curr . "</td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgBI_curr . "</td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgKurang_curr . "</td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_curr . "</td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_PLH_curr . "</td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_Aman_curr . "</td>";
	
	$totBrg_akhirx=$totBrg_akhir+$jmlBarangAwal;
	$totHrg_akhirx=$totHrg_akhir+$jmlHargaAwal;
	
	$tampil_totHrg_akhirx = $dlmRibuan == TRUE ? number_format(($totHrg_akhirx / 1000), 2, ',', '.') : number_format($totHrg_akhirx, 2, ',', '.');
	
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, ',', '.') . "</td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, ',', '.') . "</td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 3: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				";
                $TampilStyleTotx = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhirx), 0, ',', '.') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhirx . "</td>
				$tampilKet
				";				
                break;
            }
    }
	//}


		if ($jns_aset==1){
    $ListData = "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>B.&nbsp;&nbsp;ASET LAINNYA</td>
			$TampilStyleTot
			</tr>
			";//.'no awal='.$noawal
	} else {
    $ListData = "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>A.&nbsp;&nbsp;ASET TETAP</td>
			$TampilStyleTot
			</tr>
			".$ListData;//.'no awal='.$noawal	
	}
	if ($tampiltot==1)
	{
    $ListData .= "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>TOTAL</td>
			$TampilStyleTotx
			</tr>
			";//.'no awal='.$noawal
		
	}

    //return $ListData;
    //return compact($ListData, $jmlData);
	//$ListData = '';
    return array($ListData, $jmlData,$totBrg_akhir,$totHrg_akhir);
}

?>

