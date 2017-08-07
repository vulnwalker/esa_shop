<?php session_start();
include'../koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="css/grid.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	<link href="css/font-awesome.css" rel="stylesheet">
</head>
<body>

<div class="grid" id="glogin">
	<div class="row">
		<div class="col-wd-12">
			<div class="wlogin">
				<div class="col">
					<div class="tlogin">Login Form</div>
					<form action="proses.php?act=login" method="POST">
					 	<?php
			                if(!empty($_SESSION[pesan])) 
			                  {echo "<p style='color:#D24525' align='center'>
			                         <i class='fa fa-warning'> </i> $_SESSION[pesan]</p>"; 
			                  unset($_SESSION[pesan]);}
		                ?>		               
						<input type="text" name="username" class="user"placeholder="Username" required>
						<br>
						<input type="password" name="pass" class="key" placeholder="Password" required>
						<br>
						<button type="submit" >LOGIN</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>