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
 	$idTemplateAsli = "";
	$insert_template = array(
                'nama' => $nama,
                'tgl' => $tanggal,
                'nomor' => $nomor,
                'c1' => $c1,
                'c' => $c,
                'd' => $d,
				'jumlah' => $jumlahTotal
            );
            if (insert('ref_template', $insert_template)) {
                $insertTemplate = 'SUKSES';
            } else {
                $insertTemplate = 'GAGAL';
            }
	
	$huahah = mysqli_fetch_array(mysqli_query(koneksi(),"select max(id) as hubla from ref_template "));
	$idTemplateAsli  = $huahah['hubla'];
	

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
	
	mysqli_query(koneksi(),"delete from temp_template where username='$username'");
	mysqli_query(koneksi(),"delete from temp_detail_template where username='$username'");
	echo "id Template Asli : ".$idTemplateAsli." | | Insert template : ".$insertTemplate. " | | insertDetail template : ".$insertDetailTemplate." total : ".$jumlahTotal." query : ".$ambilQuerynya ;
    

?>