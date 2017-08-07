<?php
require "koneksi.php";


foreach ($_POST as $key => $value) { 
	 $$key = $value; 
}
	$username = $_COOKIE['coID'];
	mysqli_query(koneksi(),"delete from temp_template where username = '$username'");		
	mysqli_query(koneksi(),"delete from temp_detail_template where username = '$username'");	

$c1 = "";
$c = "";
$d = "";
$e = "";
$nama_template = "";
$tanggal = "";
$nomor_distribusi = "";

	$moveParentTemplate  = select("ref_template","id = '$id'");
    foreach ($moveParentTemplate  as $baris) {
		$c1= $baris['c1'];
		$c = $baris['c'];
		$d = $baris['d'];
		$nama_template = $baris['nama'];
		$tanggal = $baris['tgl'];
		$nomor_distribusi = $baris['nomor'];
	}

	$dataInsertTempTemplate = array(
							  'username' => $username,
							  'nama_template' => $nama_template,
							  'tanggal' => $tanggal,
							  'nomor_distribusi' => $nomor_distribusi,
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d
							   );
	insert('temp_template',$dataInsertTempTemplate);
	


    $ambil  = select("ref_rincian_template","refid_template = '$id'");
    foreach ($ambil as $baris) {
		$insert_detail_temp_template = array(
				'ref_id_template' => $id,
                'c1' => $baris['c1'],
                'c' => $baris['c'],
                'd' => $baris['d'],
                'e' => $baris['e'],
				'e1' => $baris['e1'],
				'jumlah' => $baris['jumlah'],
				'nama_sub_unit' => $baris['nama_sub_unit'],
				'username' => $username
            );
            if (insert('temp_detail_template', $insert_detail_temp_template)) {
                echo 'SUKSES';
            } else {
                echo 'GAGAL';
            }
	}
	
	
	echo "username : ".$username;



?>