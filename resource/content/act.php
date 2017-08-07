<?php session_start();
include '../../func/fungsi.php';

$session_id = session_id();

$gagal = '    <script languange="javascript"> alert("Data gagal");
                history.go(-2);
              </script>';

$index = ' 	  <script languange="javascript">
			 	  document.location="../../index.php#alert_form";
		   	  </script>';


$today = date ("Ymd");
$sql = "SELECT max(id_order) as akhir FROM order_produk WHERE id_order LIKE 'ID-$today%'";
$hasil = query($sql);
$data = fetch($hasil);
$lastID = $data['akhir'];
$lastNoUrut = substr($lastID, 12, 3);
$nextNoUrut = $lastNoUrut + 1;
$id_order = $today.sprintf("%03s",$nextNoUrut);

switch ($act) {
	case 'simpan_order':
		$simpan = query("INSERT into order_produk values('ID-$id_order','$id_user','$prov','$kabkot','$kec','$alamat','$bank','$jasa','$tglnow','belum','')");
	
		$jum = count($id_produk);
		    for ($i=0; $i < $jum; $i++) {
			$detail=query("INSERT into order_detail values('ID-$id_order','$session_id','$id_produk[$i]','$jumlah[$i]')");
			}

		$delete = query("DELETE FROM order_session where session_id = '$session_id' ");	

		if ($simpan AND $detail AND $delete) {
			echo"$index";
		} else {
			echo "$gagal";
		}

	break;
}
?>