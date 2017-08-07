<?php session_start();
include 'func/fungsi.php';


if(empty($_SESSION[user]) || $_SESSION[level] != "member" )  {
	  		$_SESSION[pesan]="Silahkan Login terlebih dahulu";
			echo"<script languange='javascript'>
			document.location='index.php#login_form';
			</script>";
} else {
	 		
			
?>
<!DOCTYPE html>
<html>
<head>
	<title>Online Shop</title>
	<link rel="stylesheet" type="text/css" href="resource/css/grid.css">
	<link rel="stylesheet" type="text/css" href="resource/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="resource/css/style_website.css">
	<link rel="stylesheet" type="text/css" href="resource/css/font-awesome.css">
</head>
<body>
	
<script src="resource/js/jquery-2.1.3.js"></script>
<script src="resource/js/textition.js"></script>

	<script type="text/javascript">
		var htmlobjek;
		$(document).ready(function(){
		  $("#propinsi").change(function(){
		    var propinsi = $("#propinsi").val();
		    $.ajax({
		        url: "resource/content/ambilkota.php",
		        data: "propinsi="+propinsi,
		        cache: false,
		        success: function(msg){
		            $("#kota").html(msg);
		        }
		    });
		  });
		  $("#kota").change(function(){
		    var kota = $("#kota").val();
		    $.ajax({
		        url: "resource/content/ambilkecamatan.php",
		        data: "kota="+kota,
		        cache: false,
		        success: function(msg){
		            $("#kec").html(msg);
		        }
		    });
		  });
		});
	</script>

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
						<div class="col">
							<i class="fa fa-home"></i>
								<a href="index.php"> Beranda </a>
							<i class="fa fa-angle-right"></i>
								<a href="cart.php"> Checkout</a>					
						</div>
					</div>
				</div>

<?php
$username = $_SESSION[user];
$sql = query("SELECT * FROM user WHERE username='$username' AND level='member' ");
$user=fetch($sql);

$daftarbelanja = "";
$datapengirim = "";
$metode = "";
$dataprov = "";


$session_id = session_id();					
  $query = query("SELECT *FROM produk,kategori,merek,order_session WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek AND produk.id = order_session.id_produk AND order_session.session_id='$session_id' order by order_session.id_order_session DESC");
  $no = 1;	
  while ($data=fetch($query)) {
    $subtotal = $data['harga'] * $data['jumlah'];
	$total = $total + $subtotal;	

$daftarbelanja .='<tr> 
					<input type="hidden" name="id_produk[]" value="'.$data['id'].'">
					<input type="hidden" name="jumlah[]" value="'.$data['jumlah'].'">
		     		<td>'.$no.'</td>
		     		<td>'.$data['nama_produk'].'</td>
		     		<td>'.$data['jumlah'].'</td>
		     		<td>'.rupiah($data['harga']).'</td>
		     		<td>Rp '.rupiah($subtotal).'</td>
		   		 </tr>';
	   		$no++;		   
	}

$propinsi = query("SELECT * FROM prov ORDER BY nama_prov");
		while($p=fetch($propinsi)){
				$dataprov .= '<option value="'.$p['id_prov'].'">'.$p['nama_prov'].'</option>';
		}

$datapengirim .= '			
			<div class="col-wd-4">
				<div class="col" id="cart-col4">
					<div class="pengiriman"> Informasi Pengiriman</div>
					<form action="checkout.php?hal=metode" method="POST">
					  <table>
					  	<tbody>
					  	  <tr>
							  <td>
								<label>Nama</label>
								<input type="text" value="'.$user['nama_lengkap'].'" name="nama" required>
								<input type="hidden" value="'.$user['id_user'].'" name="id_user">
							  </td>
						   </tr><tr>	
							  <td>
								<label>Provinsi</label>
								<select name="prov" id="propinsi" required>
								<option value="" selected disabled> -- Pilih Propinsi -- </option>
									'.$dataprov.'
								</select>
							  </td>
						   </tr><tr>	
							  <td>
								<label>Kabupaten / Kota</label>
								<select id="kota" name="kota" required>
									<option value="" selected disabled>Loading..</option>
								</select>
							  </td>
						   </tr><tr>	
							  <td>
								<label>Kecamatan</label>
								<select name="kec" id="kec" required>
									<option value="" selected disabled>Loading..</option>
								</select>
							  </td>
						   </tr><tr>	
							  <td>
								<label>Alamt Rumah</label>
								<textarea name="alamat" id="alamat" required>'.$user['alamat'].'</textarea>
							  </td>	
						   </tr><tr>	
							  <td>
								<label>No Telepon</label>
								<input type="text" value="'.$user['tlp'].'" name="tlp" required>
							  </td>
						    </tr><tr>
						      <td>
						      	<button type="submit">Lanjutkan</button> 
						      </td>
						    </tr>  	 	  
					  	</tbody>
					  </table>
					 </form>	
				</div>
			</div>';

$metode .= '			
			<div class="col-wd-4">
				<div class="col" id="cart-col4">
					<div class="pengiriman"> Metode Pengiriman</div>					
						<input type="hidden" value="'.$id_user.'" name="id_user">
						<input type="hidden" value="'.$prov.'" name="prov">
						<input type="hidden" value="'.$kota.'" name="kabkot">
						<input type="hidden" value="'.$kec.'" name="kec">
						<input type="hidden" value="'.$alamat.'" name="alamat">
						<input type="hidden" value="'.$tlp.'" name="tlp">
					  <table>
					  	<tbody>
					  	  <tr>	
							  <td>
								<label>Bank Tujuan</label>
								<select name="bank" required>
								<option value="" selected disabled> -- Pilih Bank -- </option>
									<option value="Mandiri"> Bank Mandiri</option>
									<option value="BCA"> Bank BCA</option>
									<option value="BRI"> Bank BRI</option>
									<option value="Niaga"> Bank Niaga</option>
								</select>
							  </td>
						   </tr><tr>
						      <td>
								<label>Paket Pengiriman</label>
								<select name="jasa" required>
								<option value="" selected disabled> -- Pilih Paket Pengiriman -- </option>
									<option value="JNE">JNE</option>
									<option value="TIKI">TIKI</option>
									<option value="POS">PT POS</option>
									<option value="Esl_Expres">ESL Expres</option>
								</select>
							  </td>	
						   </tr><tr>
						   	  <td>
						      	<button type="submit">Kirim Pesanan</button> 
						      </td>
						   </tr>     	 	  
					  	</tbody>
					  </table>
				</div>
			</div>';
				switch ($hal) {
					default:
						  echo '
							<div class="col-wd-12">
								<div class="col" id="itemcart">
									<i class="fa fa-shopping-cart"></i> Daftar Belanja 
								</div>
							</div>

							<div class="col-wd-12">
								<div class="col-wd-8">
									<div class="col" id="cart-col8">

									 <table id="t01">
									   <tr>  
									     <th>No</th>
									     <th>Nama Produk</th>
									     <th>Jumlah</th>
										 <th>Harga</th>
									     <th>Subtotal</th>
									   </tr>
										  '.$daftarbelanja.'				
									    <tr>
											<td colspan="3"></td>
											<td><b>Total</b></td>
											<td style="text-align:center"><b>Rp. '. rupiah($total). ' </b></td>
										</tr>
									 </table>	
									</div>
								</div>
							'.$datapengirim.'';
					break;

					case 'metode':
						echo'
						<form action="resource/content/act.php?act=simpan_order" method="POST">
						<div class="col-wd-12">
							<div class="col" id="itemcart">
								<i class="fa fa-shopping-cart"></i> Daftar Belanja 
							</div>
						</div>

						<div class="col-wd-12">
							<div class="col-wd-8">
								<div class="col" id="cart-col8">

								 <table id="t01">
								   <tr>  
								     <th>No</th>
								     <th>Nama Produk</th>
								     <th>Jumlah</th>
									 <th>Harga</th>
								     <th>Subtotal</th>
								   </tr>
									  '.$daftarbelanja.'				
								    <tr>
										<td colspan="3"></td>
										<td><b>Total</b></td>
										<td style="text-align:center"><b>Rp. '. rupiah($total). ' </b></td>
									</tr>
								 </table>	
								</div>
							</div>
							'.$metode.'
						</form>
						';
					break;
				}

			echo'
				</div>
			</div>
		</div>
	</div>
</div>';	

 
require'resource/content/footer.php';

echo'
    </body>
	</html>';
} 
?>