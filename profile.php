<?php session_start();
include 'func/fungsi.php';
	if (empty($_SESSION[user]) and $_SESSION[level] != "member") {
      	 echo"<script languange='javascript'>
			document.location='index.php#login_form';
		</script>";
    }  
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
							<div>
								<?php require'resource/content/profile_bar.php';?>
							</div>	

							<div class="col-wd-9">								
								<?php 
								switch ($page) {
										default:
											require'resource/content/profile_detail.php';
											break;

										case 'history':
											require'resource/content/edit_profile.php';
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
</div>
<?php require'resource/content/footer.php';?>
</body>
</html>