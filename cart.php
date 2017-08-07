<?php session_start();
include 'func/fungsi.php';
?>


<!DOCTYPE html>
<html>
<head>
	<title>Online Shop</title>
	<link rel="stylesheet" type="text/css" href="resource/css/grid.css">
	<link rel="stylesheet" type="text/css" href="resource/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="resource/css/style_website.css">
	<link rel="stylesheet" type="text/css" href="resource/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="resource/css/animate.css">
</head>
<body>
	
<script src="resource/js/jquery-2.1.3.js"></script>
<script src="resource/js/textition.js"></script>

<div class="grid">
	<div class="row" id="content">
		<div class="col-wd-12">
			<div class="col" id="layer">
				<div class="grid">
					<div class="row">
						<?php require'resource/content/mainmenu.php';?>
						<div class="header">
							<div class="col-wd-12">
								<div class="col" id="header">
									<?php require'resource/content/header.php';?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="nav">
					<div class="col-wd-12">
						<div class="col animated fadeInDown delay-02s">
							<i class="fa fa-home"></i>
								<a href="index.html"> Beranda </a>
							<i class="fa fa-angle-right"></i>
								<a href="cart.php"> Cart</a>					
						</div>
					</div>
				</div>

<?php
  $session_id = session_id();					
  $query = query("SELECT *FROM produk,kategori,merek,order_session WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek AND produk.id = order_session.id_produk AND order_session.session_id='$session_id' order by order_session.id_order_session DESC");

	$sql = query("SELECT SUM(jumlah) as item FROM order_session where session_id = '$session_id' ");
	$cek=fetch($sql);

	if ($cek['item'] > 0 ) {
	  echo '
		<div class="col-wd-12">
			<div class="col animated fadeInDown delay-05s" id="itemcart">
				<i class="fa fa-shopping-cart"></i> Keranjang ( '.$cek['item'].' Item) 
			</div>
		</div>
  	   ';

  	    if(!empty($_SESSION[tcukup])) {
        echo '<div class="col-wd-12">
				<div class="col animated fadeInDown delay-12s" id="itemcart">
               		<i class="fa fa-warning"> </i> '.$_SESSION[tcukup].'
               </div>
			 </div>'; 
        unset($_SESSION[tcukup]);
  	 	}

    while ($data=fetch($query)) {

    		$subtotal = $data['harga'] * $data['jumlah'];
  			echo'
				<div class="col-wd-12">
					<div class="col-wd-2">
						<div class="col animated flipInX delay-08s" id="cart-col1">
							<img src="data:image/png;base64,' . $data['foto'] . '">
						</div>
					</div>
					<div class="col-wd-3">
						<div class="col animated flipInX delay-09s" id="cart-col2">
							<div class="jdl-cart">
								'.$data['nama_produk'].'
							</div>
							<p>
							<b>Produk id</b> :'.$data['id'].'<br>
							<b>Merek</b> : '.$data['nama_merek'].'
							</p>
							<div class="sub-cart">
								<b>Rp</b>.'.rupiah($data['harga']).'
							</div>
						</div>
					</div>
					<div class="col-wd-2">
						<div class="col animated flipInX delay-10s" id="cart-col2">						
							<span>Jumlah</span> 
							<br><br>					
					  <form action="proses.php?act=update_item" method="post">
							<input type="number" name="quantity" min="1" value="'.$data['jumlah'].'" class="qty qcart" />
											
						</div>
					</div>
					<div class="col-wd-2">
						<div class="col animated flipInX delay-11s" id="cart-col2">
						<span>Sub Total</span><br><br>
							<div class="sub-cart">
								<b> Rp</b>. '.rupiah($subtotal).'
							</div>
						</div>
					</div>

					<div class="col-wd-3">
						<div class="col animated flipInX delay-12s" id="cart-col3">
							<span>Setting</span>
							<br>
								<input type="hidden" value="'.$data['id'].'" name="id">
								<button class="cart-btnset edit"><i class="fa fa-pencil"></i> Update</button>
						</form>
							
							<a href="#" onclick="if
                				(confirm(\'Apakah anda ingin menghapus data = '.$data['nama_produk'],' ? \'))
                				location.href=\'proses.php?act=hapus_item&id='.$data['id'].'\';">
								<button class="cart-btnset hps"><i class="fa fa-trash"></i> Hapus</button>
							</a>
							
						</div>
					</div>
				</div>';
			}

			echo '
				<div class="col-wd-12">
					<div class="col" id="checkout">
					<form action="proses.php?act=checkout" method="post">
						<button class="btn-checkout">Checkout <i class="fa fa-arrow-circle-right"></i></button>
					</form>
					</div>
				</div>
			';
			
		
	} else {
	  echo '
		<div class="col-wd-12">
			<div class="col" id="itemcart">
				<i class="fa fa-shopping-cart"></i> Keranjang anda masih kosong 
			</div>
		</div>
  	   ';
 
		require'resource/content/top_produk.php';			
		require'resource/content/new_produk.php';
	} ?>		
			</div>
		</div>
	</div>
</div>	

<?php require'resource/content/footer.php';?>

</body>
</html>