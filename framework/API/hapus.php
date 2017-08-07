<?php 
 
	$Id    = $_GET["Id"];
	$path="gambar2011/";
 	$err="";
	
 	$query = "select * FROM gambar WHERE Id='$Id' ";	 
	$hasil = mysql_query($query);
	$hapus = mysql_fetch_array($hasil);
	if (mysql_num_rows($hasil) > 0) {
		$response = array();
			
			
			$unlink =  unlink($path.$hapus['nmfile']);
			if ($unlink==FALSE) {
				$response["success"] = "0";
				$err= 'Gagal Hapus File!';	
			}					
			if($err==""){
				$query1 = "DELETE FROM gambar WHERE Id='$Id' ";	 
				$hasil1 = mysql_query($query1);
					
				if($hasil1==FALSE){
					$response["success"] = "0";
					$response["message"] = "Query salah ".mysql_error();
					$err=$response["message"];
				}
			}			
			
		 if ($err==""){
		 	$response["success"] = "1";
		 	$response["message"] = "Data Terhapus"; 
		 }
		 
		echo json_encode($response);
	} else {
	    $response["success"] = "0";
	    $response["message"] = "Tidak ada data ".$query;
	    echo json_encode($response);
	}	

?>