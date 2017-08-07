<?php
// include ('..\config.php');


function selKabKota_txt($selectedValue,$txtValue,$id_propinsi,$idmd='1',$prefix='',$param1='',$param2='') {
    //$selName = 'selKabKota';
    $disKota = $DisAbled;
    $KondisiKota = "";
	
    $PilihKota = "<option value='0'>--- Pilih Kota/Kabupaten ---</option>";

	$fmxKotaKabtxt='';
	if ($idmd=='1')
	{
	$fmxCbxmode="<input type='hidden' value='1' name='".$prefix."cbxmode' id='".$prefix."cbxmode'"." >";
	} else {
	$fmxCbxmode="<input type='hidden' value='0' name='".$prefix."cbxmode' id='".$prefix."cbxmode'"." >";
		
	}    
	$fmxKotaKab = $selectedValue==''?'0':$selectedValue;

	$styleinput=" maxlength='100' size='50'";
	
	if ($fmxKotaKab !== "0") {
//		$KondisiKota = " and kd_kota='$fmxKotaKab'";
		$KondisiKota = " and kd_kota<>'0'";

//		$PilihKota = ""; 
		if ($idmd=='1'){
			$fmxKotaKabtxt="&nbsp;<input type='hidden' value='' name='".$prefix."fmxKotaKabtxt' id='".$prefix."fmxKotaKabtxt'".$styleinput." >";
		} 
		
	}else{
		$KondisiKota = " and kd_kota<>'0'"; //$PilihKota = " and e<>'00' "; 		
		if ($idmd=='1'){
			$fmxKotaKabtxt="&nbsp;<input type='hidden' value='$txtValue' name='".$prefix."fmxKotaKabtxt' id='".$prefix."fmxKotaKabtxt'".$styleinput." >";
		}

	}

	$style = "style='width:214;'";
    
	$fmxProvinsi='28';
 
	//Kota ----------------------------------	
	$aqry = "select * from ref_kotakec where kd_kota<>'' $KondisiKota and kd_kec='0'  order by nm_wilayah"; $cek .= $aqry;
    $Qry = mysql_query($aqry);
    $Ops = "";
    while ($isi = mysql_fetch_array($Qry)) {
        $sel = $fmxKotaKab == $isi['kd_kota'] ? "selected" : "";
        $Ops .= "<option $sel value='{$isi['kd_kota']}'>{$isi['nm_wilayah']}</option>\n";
    }
    $ListKota = 
		"<select $disKota name='".$prefix."fmxKotaKab' id='".$prefix."fmxKotaKab' 	
			onChange=\"".$prefix.".pilihKotaKab()\"	 $style	
		>$PilihKota $Ops</select> $fmxKotaKabtxt $fmxCbxmode";
	



    return $ListKota;
}

function selKecamatan_txt($selectedValue,$txtValue, $id_propinsi,$id_kotakab='0',$idmd='1',$prefix='',$param1='',$param2='') {
	$fmxProvinsi='28'; // = $id_propinsi
	$disKecamatan = $DisAbled;
	$KondisiKecamatan = "";
	$PilihKecamatan = "<option value='0'>--- Pilih Kecamatan ---</option>";
	$fmxKecamatantxt='';
	$fmxKotaKabtxt='';
    
	$fmxKotaKab = $id_kotakab==''?'0':$id_kotakab;
	$fmxKecamatan = $selectedValue==''?'0':$selectedValue;

	$styleinput=" maxlength='100' size='50'";
	$style = "style='width:214;'";

//	$style = $param1==''?"style='width:188;'":$param1;	
	if ($fmxKecamatan !== "0") {
//		$KondisiKecamatan = " and kd_kec='$fmxKecamatan'";
		$KondisiKecamatan = "";

//		$PilihKecamatan = ""; 
		if ($idmd=='1'){
			$fmxKecamatantxt="&nbsp;<input type='hidden' value='' name='".$prefix."fmxKecamatantxt' id='".$prefix."fmxKecamatantxt'".$styleinput." >"; }
	}else{
		$KondisiKecamatan = " and kd_kec<>'0'"; //$PilihKota = " and e<>'00' "; 
		if ($idmd=='1'){
			$fmxKecamatantxt="&nbsp;<input type='hidden' value='$txtValue' name='".$prefix."fmxKecamatantxt' id='".$prefix."fmxKecamatantxt'".$styleinput." >";
		}
				
	}	
		//kecamatan ----------------------------------
	$Ops = "";
	if ($fmxKotaKab<>'0'){
		$aqry = "select * from ref_kotakec where kd_kota='$fmxKotaKab' $KondisiKecamatan order by nm_wilayah"; $cek .= $aqry;
	    $Qry = mysql_query($aqry);

	    while ($isi = mysql_fetch_array($Qry)) {
	        $sel = $fmxKecamatan == $isi['kd_kec'] ? "selected" : "";
	        $Ops .= "<option $sel value='{$isi['kd_kec']}'>{$isi['nm_wilayah']}</option>\n";
	    }
	}

	    $ListKecamatan = 
			"<select $disKecamatan name='".$prefix."fmxKecamatan' id='".$prefix."fmxKecamatan' 	
				onChange=\"".$prefix.".pilihKecamatan()\" $style		
			>$PilihKecamatan $Ops</select> $fmxKecamatantxt";
		  return $ListKecamatan;
}



function selKabKota_gps($selectedValue,$txtValue,$id_propinsi,$idmd='1',$prefix='',$param1='',$param2='') {
  global $fmxkeckoorgps,$fmxkeckoorbid;			

    //$selName = 'selKabKota';
    $disKota = $DisAbled;
    $KondisiKota = "";
	
    $PilihKota = "<option value='0'>--- Pilih Kota/Kabupaten ---</option>";

	$fmxKotaKabtxt='';
	if ($idmd=='1')
	{
	$fmxCbxmode="<input type='hidden' value='1' name='".$prefix."cbxmode' id='".$prefix."cbxmode'"." >";
	} else {
	$fmxCbxmode="<input type='hidden' value='0' name='".$prefix."cbxmode' id='".$prefix."cbxmode'"." >";
		
	}    
	$fmxKotaKab = $selectedValue==''?'0':$selectedValue;

	$styleinput=" maxlength='100' size='50'";
	
	if ($fmxKotaKab !== "0") {
//		$KondisiKota = " and kd_kota='$fmxKotaKab'";
		$KondisiKota = " and kd_kota<>'0'";

//		$PilihKota = ""; 
		if ($idmd=='1'){
			$fmxKotaKabtxt="&nbsp;<input type='hidden' value='' name='".$prefix."fmxKotaKabtxt' id='".$prefix."fmxKotaKabtxt'".$styleinput." >";
		} 
		
	}else{
		$KondisiKota = " and kd_kota<>'0'"; //$PilihKota = " and e<>'00' "; 		
		if ($idmd=='1'){
			$fmxKotaKabtxt="&nbsp;<input type='text' value='$txtValue' name='".$prefix."fmxKotaKabtxt' id='".$prefix."fmxKotaKabtxt'".$styleinput." >";
		}

	}

	$style = "style='width:214;'";
    
	$fmxProvinsi='28';
 
	//Kota ----------------------------------	
	$aqry = "select * from ref_kotakec where kd_kota<>'' $KondisiKota and kd_kec='0'  order by nm_wilayah"; $cek .= $aqry;
    $Qry = mysql_query($aqry);
    $Ops = "";
//	$fmxkotakoorgps="";
//	$fmxkotakoorbid="";	
	$fmxkotakoorgps="<input type='hidden' value='' name='".$prefix."fmxkotakoorgps' id='".$prefix."fmxkotakoorgps' >";
			
	$fmxkotakoorbid="<input type='hidden' value='' name='".$prefix."fmxkotakoorbid' id='".$prefix."fmxkotakoorbid' >";

	$fmxkotazoom="<input type='hidden' value='' name='".$prefix."fmxkotazoom' id='".$prefix."fmxkotazoom' >";
				
    while ($isi = mysql_fetch_array($Qry)) {
//        $sel = $fmxKotaKab == $isi['kd_kota'] ? "selected" : "";
		if ($fmxKotaKab == $isi['kd_kota']){
			$sel="selected";

	
			$fmxkotakoorgps="<input type='hidden' value='".$isi['koordinat_gps']."' name='".$prefix."fmxkotakoorgps' id='".$prefix."fmxkotakoorgps'>";
			
			$fmxkotakoorbid="<input type='hidden' value='".$isi['koord_bidang']."' name='".$prefix."fmxkotakoorbid' id='".$prefix."fmxkotakoorbid' >";

	$fmxkotazoom="<input type='hidden' value='".$isi['zoom']."' name='".$prefix."fmxkotazoom' id='".$prefix."fmxkotazoom' >";

		} else {
			$sel="";

		}
        
		$Ops .= "<option $sel value='{$isi['kd_kota']}'>{$isi['nm_wilayah']}</option>\n";
    }
    $ListKota = 
		"<select $disKota name='".$prefix."fmxKotaKab' id='".$prefix."fmxKotaKab' 	
			onChange=\"".$prefix.".pilihKotaKab()\"	 $style	
		>$PilihKota $Ops</select> $fmxKotaKabtxt $fmxCbxmode $fmxkotakoorgps $fmxkotakoorbid $fmxkotazoom";
	



    return $ListKota;
}



function selKecamatan_gps($selectedValue,$txtValue, $id_propinsi,$id_kotakab='0',$idmd='0',$prefix='',$param1='',$param2='') {
  global $fmxkeckoorgps,$fmxkeckoorbid;			

	$fmxProvinsi='28'; // = $id_propinsi
	$disKecamatan = $DisAbled;
	$KondisiKecamatan = "";
	$PilihKecamatan = "<option value='0'>--- Pilih Kecamatan ---</option>";
	$fmxKecamatantxt='';
	$fmxKotaKabtxt='';
    
	$fmxKotaKab = $id_kotakab==''?'0':$id_kotakab;
	$fmxKecamatan = $selectedValue==''?'0':$selectedValue;

	$styleinput=" maxlength='100' size='50'";
	$style = "style='width:214;'";

//	$style = $param1==''?"style='width:188;'":$param1;	
	if ($fmxKecamatan !== "0") {
//		$KondisiKecamatan = " and kd_kec='$fmxKecamatan'";
		$KondisiKecamatan = "";

//		$PilihKecamatan = ""; 
		if ($idmd=='1'){
			$fmxKecamatantxt="&nbsp;<input type='hidden' value='' name='".$prefix."fmxKecamatantxt' id='".$prefix."fmxKecamatantxt'".$styleinput." >"; }
	}else{
		$KondisiKecamatan = " and kd_kec<>'0'"; //$PilihKota = " and e<>'00' "; 
		if ($idmd=='1'){
			$fmxKecamatantxt="&nbsp;<input type=text' value='$txtValue' name='".$prefix."fmxKecamatantxt' id='".$prefix."fmxKecamatantxt'".$styleinput." >";
		}
				
	}	
		//kecamatan ----------------------------------
	$Ops = "";
//	$fmxkeckoorgps='';
//	$fmxkeckoorbid='';			
			$fmxkeckoorgps="<input type='hidden' value='' name='".$prefix."fmxkeckoorgps' id='".$prefix."fmxkeckoorgps' >";
			
			$fmxkeckoorbid="<input type='hidden' value='' name='".$prefix."fmxkeckoorbid' id='".$prefix."fmxkeckoorbid' >";

	$fmxkeczoom="<input type='hidden' value='' name='".$prefix."fmxkeczoom' id='".$prefix."fmxkeczoom' >";

	if ($fmxKotaKab<>'0'){
		$aqry = "select * from ref_kotakec where kd_kota='$fmxKotaKab' $KondisiKecamatan order by nm_wilayah"; $cek .= $aqry;
	    $Qry = mysql_query($aqry);

	    while ($isi = mysql_fetch_array($Qry)) {
//	        $sel = $fmxKecamatan == $isi['kd_kec'] ? "selected" : "";
		if ($fmxKecamatan == $isi['kd_kec']){
			$sel="selected";
			$fmxkeckoorgps="<input type='hidden' value='".$isi['koordinat_gps']."' name='".$prefix."fmxkeckoorgps' id='".$prefix."fmxkeckoorgps'".$styleinput." >";
			
			$fmxkeckoorbid="<input type='hidden' value='".$isi['koord_bidang']."' name='".$prefix."fmxkeckoorbid' id='".$prefix."fmxkeckoorbid'".$styleinput." >";

	$fmxkeczoom="<input type='hidden' value='".$isi['zoom']."' name='".$prefix."fmxkeczoom' id='".$prefix."fmxkeczoom' >";
		} else {
			$sel="";

		}

	        $Ops .= "<option $sel value='{$isi['kd_kec']}'>{$isi['nm_wilayah']}</option>\n";
	    }
	}

	    $ListKecamatan = 
			"<select $disKecamatan name='".$prefix."fmxKecamatan' id='".$prefix."fmxKecamatan' 	
				onChange=\"".$prefix.".pilihKecamatan()\" $style		
			>$PilihKecamatan $Ops</select> $fmxKecamatantxt $fmxkeckoorgps $fmxkeckoorbid $fmxkeczoom";
		  return $ListKecamatan;
}





function selKabKota_txt_div($selectedValue,$txtValue,$id_propinsi,$idmd='1',$prefix='',$param1='',$param2=''){
return '<div style="float:left;" id="'.$prefix.'CbxKotaKab">'.selKabKota_txt($selectedValue,$txtValue,$id_propinsi,$idmd,$prefix,$param1,$param2).'</div>';	
}

function selKecamatan_txt_div($selectedValue,$txtValue, $id_propinsi,$id_kotakab='0',$idmd='1',$prefix='',$param1='',$param2='') {
return '<div style="float:left;" id="'.$prefix.'CbxKecamatan">'.selKecamatan_txt($selectedValue,$txtValue, $id_propinsi,$id_kotakab,$idmd,$prefix,$param1,$param2).'</div>'; 	
}


function selKabKota_gps_div($selectedValue,$txtValue,$id_propinsi,$idmd='0',$prefix='',$param1='',$param2=''){
return '<div style="float:left;" id="'.$prefix.'CbxKotaKab">'.selKabKota_gps($selectedValue,$txtValue,$id_propinsi,$idmd,$prefix,$param1,$param2).'</div>';	
}

function selKecamatan_gps_div($selectedValue,$txtValue, $id_propinsi,$id_kotakab='0',$idmd='0',$prefix='',$param1='',$param2='') {
return '<div style="float:left;" id="'.$prefix.'CbxKecamatan">'.selKecamatan_gps($selectedValue,$txtValue, $id_propinsi,$id_kotakab,$idmd,$prefix,$param1,$param2).'</div>'; 	
}


?>