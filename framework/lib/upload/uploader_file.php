<?php
error_reporting(0);
set_time_limit(0);
include("fungsi.php");

$dirPath = '../../dokum';
$tmp_path ='../../tmp';
$idCompOut_doc = 'dokumen';
$idCompOut_file = 'dokumen_file';
$imgWidth = 100;
$imgHeight = 100;
$maxFileSize = 50000000;
$timeOut = 1000;
$errMaxFileSize = "Ukuran File Terlalu besar! Maksimum 50 Mb";

$idDivOverlay='overlay';

del_file_older_than(2, $tmp_path."/*.*");

/************************************** UPLOAD ***************************/
$errmsg='';
if ($_FILES['uploadedfile']['name'] != '') {
	$file_size=$_FILES['uploadedfile']['size']; //filesize($_FILES['uploadedfile']);
	if($file_size >= $maxFileSize){		
		$errmsg= $errMaxFileSize;
	}
	else {
	
		$target_path= $tmp_path.'/';	
		$newDoc = $_FILES['uploadedfile']['name'];
		$ext = pathinfo($_FILES['uploadedfile']['name'],PATHINFO_EXTENSION);		
		$newFile = mt_rand().'.'.$ext;	
		$target_path = $target_path .$newFile;	 

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			//echo "The file ".  basename( $_FILES['uploadedfile']['name']).    " has been uploaded";
		} else{			
			$errmsg= 'There was an error uploading the file, please try again!';
			
		}	
	}
	
	if ($errmsg !=''){
		$newFile='';
		echo "<script> alert('".$errmsg."')</script>";
	}else{
		
	}
}
if ($newFile == '') {
	$nmFile = $_GET['nmFile'];
	$nmDoc = $_GET['nmDoc'];
}else{
	$nmFile = $newFile;
	$nmDoc = $newDoc;
}

/************************ INTERFACE ************************ */

if ($nmFile != ''){
	try{
		$handle = file($dirPath.'/'.$nmFile);	
	} catch (Exception $e) {
	}
	
	if ($handle==FALSE){	
		//$img = '<img width='.$imgWidth.' height='.$imgHeight.' src ="'.$tmp_path.'/'.$nmFile.'" > ';//$img = '<input type="text" readonly width="300" value="'.$nmFile.'" > ';
		$img='';
		
	}else{
		//$img = '<img width='.$imgWidth.' height='.$imgHeight.' src ="'.$dirPath.'/'.$nmFile.'" > ';		
		$img='';
	}
}else {
	$img = '';
}

$nmDoc = str_replace(' ','_',$nmDoc);
echo "
		<script type='text/javascript'>
		function sendNewName(){
			
			parent.document.getElementById('".$idCompOut_doc."').value= '".$nmDoc."';
			parent.document.getElementById('".$idCompOut_file."').value= '".$nmFile."';
			//alert('".$nmDoc."');
		}
		function uploadFile(){
			//alert(document.getElementById('selFile').value);					
			if (document.getElementById('selFile').value != ''){
				document.getElementById('".$idDivOverlay."').style.visibility= 'visible';				
				setTimeout('document.fmupload.submit()' , $timeOut);
			}
		}
		</script>
		
		
		<body onload='sendNewName()' style='margin:0'>
		
		
		
		
		
		<form name='fmupload' enctype='multipart/form-data' action='' method='POST'>
			
		
			<!--<input type='hidden' name='MAX_FILE_SIZE' value='".$maxFileSize."'  /> -->
			<input type='hidden' name='nmFile'  value='".$nmFile."'> 
			<table><tr><td>
				<input type='file' id='selFile' name='uploadedfile'  value='Ganti' onchange='uploadFile()' />
				<!--(Max. 5Mb)	-->
			</td>
			<td > ".setOverlay2($idDivOverlay)."</td>
			</tr></table>
			
		</form>
		</body>
	";




?>