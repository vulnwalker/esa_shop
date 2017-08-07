<?php

function del_file_older_than($day, $spatern ){
	$php_todays_date = getdate();
	$php_unix_timestamp = mktime(0,0,0,$php_todays_date['mon'],$php_todays_date['mday'],$php_todays_date['year']);
	$php_todays_date_unix_timestamp = $php_unix_timestamp;
	
	$fileNames = glob($spatern);
	foreach ( $fileNames as $filename){
		if ($php_todays_date_unix_timestamp < (filemtime($filename)+86400*($day-1))) {
			//print "File is Not older than ".$day." days - ".$filename." ".date("M-d-Y",filemtime($filename))."<br>";
		}else{
			//print "File is older than ".$day." days - ".$filename." ".date("M-d-Y",filemtime($filename))."<br>";
			$php_status = unlink($filename);
		}
	}
}

function setOverlay( $idOverlay ){
	/****
		aktifkan dgn perintah: document.getElementById('".$idDivOverlay."').style.visibility= 'visible';
		non aktif :  visibility= hidden;
	*/
	
	$imgSrc 	= 'loading.gif';
	$boxWidth 	= '100px';
	$boxHeight 	= '100%';
	
	$tampil = " 
		<div  id=$idOverlay width=100% height=100% 
			style='
			bottom: 0px;			
			left: 0px;
			position: absolute;
			right: 0px;
			top: 0px;
			width: 100%;	
			height: 100%;	
			z-index: 99;	
			visibility:hidden;	
			vertical-align: middle;'				
		> 
			<table style='width:".$boxWidth."; height:".$boxHeight.";										
					vertical-align: middle;	margin-left:auto;margin-right:auto;'>
				<tr><td></td></tr>
				<tr><td style='height:40px;vertical-align: middle;'>					
					<div style='background-color:white; display:block; width:60; margin:0 0 0 0; padding:10 10 10 10; 
						border-color: rgb(153, 170, 189);border-width:1 2 2 1px;border-style:solid;'>
				
					<img src='".$imgSrc."'
						style='display: block; vertical-align: middle; margin-left: auto;  margin-right: auto'
					> 	
					</div>		
				</td></tr>
				<tr><td>&nbsp</td></tr>
			</table>
		</div>";
	return $tampil;
}


function setOverlay2( $idOverlay ){
	/****
		aktifkan dgn perintah: document.getElementById('".$idDivOverlay."').style.visibility= 'visible';
		non aktif :  visibility= hidden;
	*/
	
	$imgSrc 	= 'loading.gif';
	$boxWidth 	= '100px';
	$boxHeight 	= '24px';
	
	$tampil = " 
		
				
					<img src='".$imgSrc."' id=$idOverlay 
						style='visibility:hidden;display: block; vertical-align: middle; margin-left: auto;  margin-right: auto'
					> 	
					";
	return $tampil;
}

?>