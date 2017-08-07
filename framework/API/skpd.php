<?php 
 
	$fnbidang=$_GET["c"];
	$fnskpd=$_GET["d"];
	$fnunit=$_GET["e"];
	$fnsubunit=$_GET["e1"];
	$fngroup=$_GET["group"];
	$expgroup=explode('.',$fngroup);
	$err="";
	$tgl_update=date('Y/m/d h:i:s');
	$response = array();
	
	$kondisiBidang=" WHERE c!='00' and d='00' ";
	$kondisiSKPD=" WHERE c='$fnbidang' and d!='00' and e='00'  ";
	$kondisiUnit=" WHERE c='$fnbidang' and d='$fnskpd' and e!='00' and e1='000' ";
	$kondisiSubUnit=" WHERE c='$fnbidang' and d='$fnskpd' and e='$fnunit' and e1!='000' ";
	
	if($fngroup="00.00.00.000"){
	$kondisiBidang1=" WHERE c='".$expgroup['0']."' and d='00' ";
	$kondisiSKPD1=" WHERE c='".$expgroup['0']."' and d='".$expgroup['1']."' and e='00'  ";
	$kondisiUnit1=" WHERE c='".$expgroup['0']."' and d='".$expgroup['1']."' and e='".$expgroup['2']."' and e1='000' ";
	$kondisiSubUnit1=" WHERE c='".$expgroup['0']."' and d='".$expgroup['1']."' and e='".$expgroup['2']."' and e1='".$expgroup['3']."' ";		
		}
	
	
//Data Bidang dari tabel ref_skpd
 	if($fngroup="00.00.00.000"){
		$aqryc = "select * FROM ref_skpd ".$kondisiBidang;			
		}else{
		$aqryc = "select * FROM ref_skpd ".$kondisiBidang1;	
		}
	
	$qryc = mysql_query($aqryc);	
	
	if(mysql_num_rows($qryc)>0){
		$response["bidang"] = array();
		while ($data = mysql_fetch_assoc($qryc)){
			$c['c']	=$data['c'];		
			$c['nm_skpd'] = $data['nm_skpd'];
			 array_push($response["bidang"], $c);
		}
	}else{
		$response["success"] = "0";
	    $response["message"] = "Tidak ada data Bidang ".$aqryc." ". mysql_error();
	    echo json_encode($response);
	}
	
//Data SKPD dari tabel ref_skpd
	if ($fnbidang!="00" && $fnbidang!=""){
		if($fngroup="00.00.00.000"){
		$aqryd = "select * FROM ref_skpd ".$kondisiSKPD;			
		}else{
		$aqryd = "select * FROM ref_skpd ".$kondisiSKPD1;
		}
			 
		$qryd = mysql_query($aqryd);
		
		if(mysql_num_rows($qryd)>0){
			$response["skpd"] = array();
			while ($data = mysql_fetch_assoc($qryd)){
				$d['d']	=$data['d'];		
				$d['nm_skpd'] = $data['nm_skpd'];
				 array_push($response["skpd"], $d);
			}
		}else{
			$response["success"] = "0";
		    $response["message"] = "Tidak ada data SKPD ".$aqryd." ". mysql_error();
		    echo json_encode($response);
		}
	}
 	
	
//Data Unit dari tabel ref_skpd
	if ($fnskpd!="00" && $fnskpd!=""){
	 	if($fngroup="00.00.00.000"){
		$aqrye = "select * FROM ref_skpd ".$kondisiUnit;	 	
		}else{
		$aqrye = "select * FROM ref_skpd ".$kondisiUnit1;	 		
		}
		$qrye = mysql_query($aqrye);
		
		if(mysql_num_rows($qrye)>0){
			$response["unit"] = array();
			while ($data = mysql_fetch_assoc($qrye)){
				$e['e']	=$data['e'];		
				$e['nm_skpd'] = $data['nm_skpd'];
				 array_push($response["unit"], $e);
			}
		}else{
			$response["success"] = "0";
		    $response["message"] = "Tidak ada data Unit ".$aqrye." ". mysql_error();
		    echo json_encode($response);
		}
	}
	
//Data Sub Unit dari tabel ref_skpd
 if ($fnunit!="00" && $fnunit!=""){
 		if($fngroup="00.00.00.000"){
 		$aqrye1 = "select * FROM ref_skpd ".$kondisiSubUnit;	 
		}else{
		$aqrye1 = "select * FROM ref_skpd ".$kondisiSubUnit1;	
		}
	$qrye1 = mysql_query($aqrye1);
	
	if(mysql_num_rows($qrye1)>0){
		$response["subunit"] = array();
		while ($data = mysql_fetch_assoc($qrye1)){
			$e1['e1']	=$data['e1'];		
			$e1['nm_skpd'] = $data['nm_skpd'];
			 array_push($response["subunit"], $e1);
		}
	}else{
		$response["success"] = "0";
	    $response["message"] = "Tidak ada data Sub Unit ".$aqrye1." ". mysql_error();
	    echo json_encode($response);
	}	
	}
		
	if($Main->SHOW_CEK==FALSE) $response["cek"] =  '';
	echo json_encode($response);
?>