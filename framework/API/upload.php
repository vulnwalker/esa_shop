<?php
echo 'file upload';
$path="gambar2011/";
if (isset($_FILES['myFile'])) {
    // Upload foto ke server:
	//Ganti Nama File Baru
		$ImageName 		= str_replace(' ','-',strtolower($_FILES['myFile']['name']));
			$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
		  	$ImageExt = str_replace('.','',$ImageExt);
			
			
			//remove extension from filename
			$ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName); 
			
			//Construct a new image name (with random number added) for our new image.
			$NewImageName =  date('Ymd').'_'.	( mt_rand()).'.'.$ImageExt;
			
	if (move_uploaded_file($_FILES['myFile']['tmp_name'],  $path. $NewImageName)){
		echo 'Upload Sukses!';
		
		//save database
		
		//get uid -------------
		$uid= $_GET['uid'];
		$idBI= $_GET['idBI'];
		$nmNewFile= $NewImageName;
		$nmFileAsli=$ImageName;
		$fmKET= $_GET['ket'];
		//insert -------------				
		$sqry = " insert into gambar (idbi, nmfile, nmfile_asli, ket, stat, uid, tgl_update) 
				value('$idBI', '$nmNewFile', '$nmFileAsli','$fmKET','0', '$uid', now())" ;	 
		$Ins = mysql_query($sqry);
	}else{
		echo 'Proses Upload Gagal!';
	}
   	
}else{
	echo 'Error';
}
?>