<?php session_start();
include'func/fungsi.php';
define('_MPDF_PATH','mpdf60/');
include(_MPDF_PATH . "mpdf.php");
$mpdf=new mPDF('utf-8', '15,12'); 
ob_start();
  if ($banyak == 0) {
    $detail = 'Hari ini';
  } elseif ($banyak == 7) {
    $detail = 'Minggu ini';
  } else {
    $detail = 'Bulan ini';
  } 

switch ($page) {
  case 'transaksi':
  $nama_dokumen='Laporan Transaksi '.$detail.' - '.$tglindo.'';
?>
<!DOCTYPE html>
  <html>
    <head>
      <title>Laporan Transaksi <?php echo $detail ?> </title>
    </head>
      <link rel="stylesheet" type="text/css" href="resource/css/laporan.css">
      <body>
          <tb><img src="resource/img/8.png" class="logo"></tb>
          <tb><div class="judul">
              ESA -Shop
              <br>
                <span>Jl Cigugur Tengah No 52 Cimahi</span>
              <br>
                <span>Email esa@shop.com</span>  
              </div></span></tb>
          <div align="center" class="title">Laporan Transaksi <?php echo $detail ?></div>
            <hr>
              <table id="t01">
                <tr>  
                  <th>Id Order</th>
                  <th>Pembeli</th>
                  <th>Alamat Rumah</th>
                  <th>Bank</th>
                  <th>Jasa Pengiriman</th>
                  <th>Tanggal</th>
                  <th>Id Petugas</th>
                </tr>

<?php 
$sql = query("SELECT *FROM user,order_produk where user.id_user = order_produk.id_pembeli AND order_produk.konfirmasi_order='sudah' AND order_produk.tgl_order >= DATE_SUB(curdate(),INTERVAL $banyak day) order by order_produk.tgl_order DESC");
$cek = numrows($sql);
if($cek >= 1){
   while ($data=fetch($sql)) {
    echo'
        <tr>
          <td>'.$data[id_order].'</td>
          <td>'.$data[nama_lengkap].'</td>
          <td>'.$data[alamat_rumah].'</td>
          <td>'.$data[bank].'</td>
          <td>'.$data[jasa_pengiriman].'</td>
          <td>'.$data[tgl_order].'</td>
          <td>'.$data[id_petugas].'</td>
        </tr>';  
    }
} else {
  echo'<script> alert(Maaf Data Laporan Masih Kosong); history.go(-1);</script>';
}
   
echo'
  </table>
    <br><br>
      <div align="right">
        <small>Tanggal '.$tglindo.' </small>
      </div>
  </body>
</html>';  
    break;

  case 'produk':
  $nama_dokumen='Laporan Produk - '.$tglindo.'';
?>

<!DOCTYPE html>
  <html>
    <head>
      <title>Laporan Produk</title>
    </head>
      <link rel="stylesheet" type="text/css" href="resource/css/laporan.css">
      <body>
          <tb><img src="resource/img/8.png" class="logo"></tb>
          <tb><div class="judul">
              ESA -Shop
              <br>
                <span>Jl Cigugur Tengah No 52 Cimahi</span>
              <br>
                <span>Email esa@shop.com</span>  
              </div></span></tb>
          <div align="center" class="title">Laporan Produk</div>
            <hr>
              <table id="t01">
                <tr>  
                  <th>Nama</th>
                  <th>Jenis</th>
                  <th>Merek</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Terjual</th>
                  <th>Stok</th>
                </tr>

<?php 
      $sql = query("SELECT *FROM produk,merek,kategori WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek order by $urut limit $batas");
        while ($data=fetch($sql)) {
          if ($data[stok] < 10) {
            $stok = '<b style="color:red">'.$data[stok].'</b>';
          } else {
            $stok = $data[stok];
          }
          echo' 
              <tr class="animated flipInX delay-10s">
                <td>'.$data['nama_produk]'.'</td>
                <td>'.$data['jenis'].'</td>
                <td>'.$data['nama_merek'].'</td>
                <td>'.$data['nama_kat'].'</td>
                <td>Rp. '.rupiah($data['harga']).'</td>
                <td>'.$data['terjual'].'</td>
                <td>'.$stok.'</td>
              </tr>';  
          }
echo'
  </table>
    <br><br>
      <div align="right">
        <small>Tanggal '.$tglindo.' </small>
      </div>
  </body>
</html>';  
    break;
}

$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;

?>

