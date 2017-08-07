<?php session_start();
include '../koneksi.php';

foreach ($_POST as $key => $value) {
    $$key = $value;
}

$sukses =   ' <script languange="javascript"> alert("Order berhasil di konfirmasi");
                   history.go(-2);
                  </script>';
$gagal  =   ' <script languange="javascript"> alert("Data gagal disimpan");
                    history.go(-1);
                  </script>'; 

switch ($_GET['act']) {
  case 'konfirmorder':

  $jum = count($id);
  for ($i=0; $i < $jum ; $i++) { 
    $produk= mysql_query("UPDATE produk set stok = stok - $jumlah[$i], terjual = terjual + $jumlah[$i] WHERE id='$id[$i]'");
  }
  $order = mysql_query("UPDATE order_produk set konfirmasi_order='sudah',id_user='$_SESSION[id_user]' WHERE id_order='$id_order'"); 
    
     if ($produk AND $order){
        echo"$sukses";

     }else {
        echo"$gagal";
     }
      
  break;

  }
?>