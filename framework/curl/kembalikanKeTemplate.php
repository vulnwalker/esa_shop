<?php
require "koneksi.php";
foreach ($_POST as $key => $value) { 
	 $$key = $value; 
}

 	
	$jumlahTotal = 0;
	$username = $_COOKIE['coID'];
	$ambilQuerynya = "select sum(jumlah) as total from temp_detail_template where username = '$username'";
	$getJumlahTotal = mysqli_fetch_array(mysqli_query(koneksi(),$ambilQuerynya));
	
	$jumlahTotal = $getJumlahTotal['total'];
 	$idTemplateAsli = $ID;
	
	mysqli_query(koneksi(),"delete from ref_rincian_template where refid_template = '$idTemplateAsli'");
	
    $ambil  = select("temp_detail_template","username = '$username'");
    foreach ($ambil as $baris) {
		$insert_detail_template = array(
				'refid_template' => $idTemplateAsli,
                'c1' => $c1,
                'c' => $c,
                'd' => $d,
                'e' => $baris['e'],
				'e1' => $baris['e1'],
				'jumlah' => $baris['jumlah'],
				'nama_sub_unit' => $baris['nama_sub_unit']
            );
            if (insert('ref_rincian_template', $insert_detail_template)) {
                $insertDetailTemplate = 'SUKSES';
            } else {
                $insertDetailTemplate = 'GAGAL';
            }
	}
	$query = "update ref_template set nama = '$nama', tgl= '$tanggal', nomor='$nomor', jumlah = '$jumlahTotal' where id ='$idTemplateAsli'";
	mysqli_query(koneksi(),$query);
	
	
	echo "id Template Asli : ".$idTemplateAsli." | | Query : ".$query. " | | insertDetail template : ".$insertDetailTemplate." total : ".$jumlahTotal ;
    
	mysqli_query(koneksi(),"delete from temp_template where username='$username'");
	mysqli_query(koneksi(),"delete from temp_detail_template where username='$username'");
?>