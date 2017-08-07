<?php

$session_id = session_id();
$sql = query("SELECT SUM(jumlah) as item FROM order_session where session_id = '$session_id' ");
$cek=fetch($sql);

if ($cek['item'] > 0 ) {
		$cek = $cek['item'];
} else {
		$cek = 0;
}
	
echo'	
<div class="grid">
	<di class="row">
		<div class="atas">
			<div class="col-wd-4">
				<div class="col" id="logo">
					<i class="fa fa-opencart"></i>
					<div class="judul">
						<b>Huzaini</b>Fashion<br>
						<span>Online Shop</span>
					</div>
				</div>
			</div>
			<div class="col-wd-7">
				<div class="col" id="pencarian">
					<div align="right">
					<form action="pencarian.php" method="GET">	
						<input type="text" class="pencarian" name="key" placeholder="Cari Produk ...">
					</form>
					</div>
				</div>
			</div>
			<div class="col-wd-1">
				<div class="cart">
				<a href="cart.php">
					<div class="col">
						<i class="fa fa-shopping-cart"></i><br>'.$cek.'
					</div>
				</a>	
				</div>
			</div>
		</div>	
	</di>
</div>';
?>
