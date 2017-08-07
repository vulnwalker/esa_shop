<?php
require "koneksi.php";



foreach ($_POST as $key => $value) { 
	 $$key = $value; 
}

insertRincianTemplate($c1,$c,$d,$e,$username);

	function insertRincianTemplate($c1,$c,$d,$e, $username){
	 	$maxID = "";
		
		$get = mysqli_fetch_array( mysqli_query(koneksi(),"select max(id) as aaa from temp_template where username = '$username' "));
		$maxID = $get['aaa'];
		
		
		$query = "select * from ref_skpd where c1='$c1' and c='$c' and d='$d' and e !='00' and e1 !='000'";
		$execute = mysqli_query(koneksi(),$query);
		while($row = mysqli_fetch_array($execute)){
			$e = $row['e'];
			$e1= $row['e1'];
			$nama_sub_unit = $row['nm_skpd'];	

			$aqry = "INSERT into temp_detail_template (c1,c,d,e,e1,nama_sub_unit,ref_id_template,username) values ('$c1','$c','$d','$e','$e1','$nama_sub_unit','$maxID','$username')";
			 mysqli_query(koneksi(),$aqry);	
			
					
		}
		
		echo $maxID." : ". $c1." : ". $c." : ".$d. " : ".$username." query : ".$aqry;

		
	}

?>