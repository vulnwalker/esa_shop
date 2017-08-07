<?php session_start();
include 'func/fungsi.php';
$base_url   = "http://127.0.0.8/framework";
setcookie('baseurl',$base_url);
$base_dir  = getcwd();
setcookie('basedir',$base_dir);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Huzaini Fashion</title>
	<link rel="icon" type="image/png" href="resource/img/8.png">
	<link rel="stylesheet" type="text/css" href="resource/css/grid.css">
	<link rel="stylesheet" type="text/css" href="resource/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="resource/css/style_website.css">
	<link rel="stylesheet" type="text/css" href="resource/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="resource/css/animate.css">
</head>
<body>
	
<script src="resource/js/jquery-2.1.3.js"></script>
<script src="resource/js/jsp.js"></script>
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

						<div class="col-wd-12">
							<div class="submenu">
								<?php require'resource/content/kategori.php';?>
							</div>	

							<div class="col-wd-9">								
								<?php 
									switch ($page) {									
										
										default:
											require'resource/content/slide.php';
										break;
										
										case 'pencarian':
											require'resource/content/cari.php';
										break;

										case 'kategori':
											require'resource/content/kategori_produk.php';
										break;

										case 'produk':
											require'resource/content/produk.php';
										break;

										case 'tentangkami':
											require'resource/content/profile-ecommers.php';
										break;

										case 'Kontak':
											require'resource/content/kontak.php';
										break;

										case 'cara-belanja':
											require'resource/content/cara_belanja.php';
										break;

									}
								?>								
							</div>
						</div>
						<?php 
							require'resource/content/top_produk.php';			
							require'resource/content/new_produk.php';
						?>					
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<?php require'resource/content/footer.php';?>
<script>
$(function() {
	$('div span').textition({
		autoplay: true,
		// handler: false,
		interval: 3,
		speed: 1,
		map: {x: 50, y: 20, z: 200},
	});	
		
});
</script>

</body>
</html>