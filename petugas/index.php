<?php session_start();
$url = 'http://localhost/Online_Shop/';
include'../koneksi.php';
include'../func/fungsi.php';
  if(empty($_SESSION[user])){
      header('location:login.php');
  } elseif ($_SESSION[level] == "member") {
      header('location:../index.php');
  } elseif ($_SESSION[level] == "admin") {
      session_destroy();
      header('location:login.php');
  } else { 
?>

<!DOCTYPE html>

<head>
  <title>Online Shop</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/normalize.css" rel='stylesheet'>
  <link href="css/font-awesome.css" rel='stylesheet'>
  <link href="css/menu.css" rel="stylesheet">
  <link href="css/grid.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/admin_style.css" rel="stylesheet">
  <script src="js/jquery-2.1.3.js"></script>
  <script src="js/checkpass.js"></script>
  <script src=" js/jquery.min.js" type="text/javascript"></script>
  <!-- <script src=" js/highcharts.js" type="text/javascript"></script> -->
  <script src="js/vertical-responsive-menu.min.js"></script>
</head>

<script>  
  $(document).ready(function() {
    $('#tombol').click(function() {
      $('.box').toggle();
    });
  });

</script>
      
<body onLoad="setInterval('displayServerTime()', 1000);">

<?php  
?>

<?php 
  require'content/header.php';
  require'content/menu.php';

echo"
  <div class='wrapper'>
    <section> ";

switch($page){

  default:
   require'layout/bg_dasboard.php';
  break;

  case 'profile':
    require'layout/bg_profile.php';
  break;

  case 'transaksi':
    require'layout/bg_transaksi.php';
  break;

  case 'detail':
    require'layout/bg_detail_transaksi.php';
  break;        
 
// Produk
  case 'kategori':
    require'layout/bg_kategori.php';
    require'content/form_kategori.php';
  break;

  case 'merek':
    require'layout/bg_merek.php';
    require'content/form_merek.php';
  break;

  case 'produk':
    require'layout/bg_produk.php';
    require'content/form_produk.php';
  break;
 
// User
  case 'petugas':
    require'layout/bg_petugas.php';
    require'content/form_petugas.php';
  break;

  case 'member':
    require'layout/bg_member.php';
  break;

// Laporan
  case 'lap_transaksi':
    require'layout/bg_laporan_transaksi.php';
  break;

  case 'lap_barang':
    require'layout/bg_laporan_barang.php';
  break;
}

echo "
    </section>
  </div>";

}
?>


</body>
</html>