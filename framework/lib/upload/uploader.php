<?php
error_reporting(0);

include("fungsi.php");

$dirPath = '../../gambar';
$tmp_path ='../../tmp';
$idCompOut = $_GET["idCompOut"]; //echo "<br>idCompOut=".$idCompOut;
if (empty($idCompOut)){
	$idCompOut = 'gambar';	
}


$imgWidth = 100;
$imgHeight = 100;
$maxFileSize = 500000;//100 000 000;
$timeOut = 1000;

$idDivOverlay='overlay';

del_file_older_than(2, $tmp_path."/*.*");

/************************************** UPLOAD ***************************/
$errmsg= '';
if ($_FILES['uploadedfile']['name'] != '') {
	$file_size=$_FILES['uploadedfile']['size'];
	if($file_size >= $maxFileSize){
		$errmsg= 'Ukuran File Terlalu besar! Maksimum 500 Kb';
	}
	else {
	
		$target_path= $tmp_path.'/';//$dirPath.'/';
	
		$ext = pathinfo($_FILES['uploadedfile']['name'],PATHINFO_EXTENSION);
	
		$newFile = mt_rand().'.'.$ext;
	
		$target_path = $target_path .$newFile;
	 

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			//echo "The file ".  basename( $_FILES['uploadedfile']['name']).    " has been uploaded";
		} else{
			//echo "There was an error uploading the file, please try again!";
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
}else{
	$nmFile = $newFile;
}

/************************ INTERFACE ************************ */

if ($nmFile != ''){
	try{
		$handle = file($dirPath.'/'.$nmFile);	
	} catch (Exception $e) {
	}
	
	if ($handle==FALSE){	
		$img = '
			<img width='.$imgWidth.' height='.$imgHeight.' src ="'.$tmp_path.'/'.$nmFile.'" > 
			';
		
	}else{
		$img = '
			<img width='.$imgWidth.' height='.$imgHeight.' src ="'.$dirPath.'/'.$nmFile.'" > 
			';
		
	}
}else {
	$img = '';
}

echo "
		<script type='text/javascript'>
		function sendNewName(){
			
			parent.document.getElementById('".$idCompOut."').value= '".$nmFile."';
			//alert('".$nmFile."');
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
		
		".setOverlay($idDivOverlay)."
		
		<form name='fmupload' enctype='multipart/form-data' action='' method='POST'>
		<table  width='100%' style= ''>
				
				<tr height='100'>
				<td width='100' style='
						background-color:#EFEFF1;
								background-image: url(http:blank.jpg);
								background-repeat-x: no-repeat;	background-repeat-y: no-repeat;
								border: 2px solid #EFEFF1'>
					
					".$img."
					
				</td>
				<td>&nbsp</td>				
				</tr>
				<tr>
				<td colspan='2'> 
					<!--<input type='hidden' name='MAX_FILE_SIZE' value='".$maxFileSize."'  /> -->
					<input type='file' id='selFile' name='uploadedfile'  value='Ganti' onchange='uploadFile()' />	</td>
				</tr>
			</table>
			<input type='hidden' name='nmFileGambar'  value='".$nmFile."'> <!--(Max. 500Kb)-->
		</form>
		</body>
	";




?>