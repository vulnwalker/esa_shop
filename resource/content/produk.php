<?php
  $query = query("SELECT *FROM produk,kategori,merek WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek AND produk.id='$id' AND produk.stok >= 3 ");
  $data = fetch($query);

echo'
<div class="nav">
	<div class="col-wd-12">
		<div class="col animated fadeInDown">
			<i class="fa fa-home"></i>
				<a href="index.php"> Beranda </a>
			<i class="fa fa-angle-right"></i>
				<a href="index.php?page=kategori&id='.$data['nama_kat'].'">'.$data['nama_kat'].'</a>
			<i class="fa fa-angle-right"></i>
				'.$data['nama_produk'].'	
		</div>
	</div>
</div>	

<div class="imgproduk">
	<div class="col-wd-4">
		<div class="col animated flipInY delay-01s">
			<img src="data:image/png;base64,' . $data['foto'] . '">
		</div>
	</div>
</div>
	

<div class="col-wd-8">		
	<div class="col-wd-12">
		<div class="jitem">
			<div class="col animated fadeInDown delay-05s">
				'.$data['nama_produk'].'
				<hr>
				 <ul id="detail">
				 	<li><b>Kategori</b> : <a href="index.php?page=kategori&id='.$data['nama_kat'].'" style="color:#fff;">'.$data['nama_kat'].'</a></li>
				 	<li><b>Merek</b> : '.$data['nama_merek'].'</li>
				 	<li>Pria</li>
				 </ul>
			</div>
		</div>
	</div>

<div class="col-wd-12">
	<div class="deskripsi">
		<div class="col animated zoomIn delay-08s">
			<li><i class="fa fa-dot-circle-o "></i> <b>Add</b> : '.$data['tgl_add'].'</li>
			<li><i class="fa fa-dot-circle-o "></i> <b>Stok</b> : '.$data['stok'].'</li>
			<li><i class="fa fa-dot-circle-o "></i> <b>Produk id</b> : '.$data['id'].'</li>
			<br>
			<div>
			   '.$data['deskripsi'].'';
		
			if(!empty($_SESSION[tcukup])) {
		        echo '<br><br><b><div class="col-wd-12 animated fadeInDown delay-16s" align="center">
		               		<i class="fa fa-warning"> </i> '.$_SESSION[tcukup].'
					 </div></b>'; 
		        unset($_SESSION[tcukup]);
	    	}

		echo'
			</div>	 
		</div>
	</div>	
</div>
	<div class="col-wd-12">
		<div class="col-wd-5">
			<div class="harganya">
				<div class="col animated flipInX delay-12s">
					 <b>Rp</b>. '.rupiah($data['harga']).'
				</div>
			</div>
		</div>

		<div class="col-wd-3">
			<div class="banyak">
				<form action="proses.php?act=cart_session_view" method="POST">
				<input type="hidden" value="'.$data['id'].'" name="id">
				<div class="col animated flipInX delay-12s">		
				    <input type="button" value="-" class="qtyminus" field="quantity" />
					<input type="text" name="quantity" min="1" value="1"  class="qty">
					<input type="button" value="+" class="qtyplus" field="quantity" />
				</div>
			</div>
		</div>

		<div class="col-wd-4">
			<div class="btn-add-cart">
			 <button type="submit">
				<div class="col animated flipInX delay-12s">				
					<i class="fa fa-shopping-cart"></i> Add to Cart
				</div>
			 </button>
			</form>	
			</div>	
		</div>
	</div>
</div>';
?>

					