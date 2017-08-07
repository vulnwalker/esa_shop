<?php session_start();
	include 'func/fungsi.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Esa Shop</title>
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
								<?php require'resource/content/cari.php';?>								
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