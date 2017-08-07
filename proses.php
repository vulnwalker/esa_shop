<?php session_start();
include'func/fungsi.php';
                                
switch($act){
   
   case 'logout':
	 
	 session_destroy();
	 query("UPDATE user SET ip ='' WHERE username ='$_SESSION[user]'");
 	 header('location:index.php');
   
   break;

   case 'register':

   $data = query("select * from user where username='$username'");
	$cek=numrows($data);

	if($cek > 0){
		$_SESSION[salah]="Username Telah Terpakai !";
		echo"<script languange='javascript'>
		document.location='index.php#join_form';
		</script>";
	}

	else {
	 $pass = MD5($password);  
   			$daftar=query("INSERT into user values('','$username','$pass','$username','$email','$tlp','$alamat','$defaultimg','member','$tglnow')");
      if ($daftar){
	        $_SESSION[pesan]="Pendaftaran yang anda lakukan berhasil silahkan login!";
			echo"<script languange='javascript'>
			document.location='index.php#login_form';
			</script>";
      } else {
	        $_SESSION[salah]="Pendaftarn yang anda lakukan gagal !";
			echo"<script languange='javascript'>
			document.location='index.php#join_form';
			</script>";
      }
    }   

   break;

   case 'login':
   	$password = MD5($pass);
	$data = query("SELECT * from user where username='$username' AND password='$password'");
	$cek=numrows($data);
	$a=fetch($data);
 
	if($cek==0){
		$_SESSION[pesan]="Username atau Password yang anda masukan salah";
		echo"<script languange='javascript'>
		document.location='index.php#login_form';
		</script>";
	} else{
		if(!empty($a[ip])) {
			echo'<script languange="javascript"> 
					alert("Maaf Akun yang anda masukan sedang dipakai oleh pengguna lain");
                 	history.go(-1);
                </script>';
		} else {
			query("UPDATE user SET ip = '$ipnya' WHERE username ='$username'");
			$_SESSION[user]=$a[username];
			$_SESSION[level]=$a[level];
			if($_SESSION[level] == "member"){echo'<script> history.go(-1);</script>';}
			if($_SESSION[level] == "admin"){header('location:admin/index.php');}
			if($_SESSION[level] == "petugas"){header('location:petugas/index.php');}
		}
	}
   	break;

   	case 'cart_session':
   		$sql = query("SELECT * From produk where id='$id'");
   		$produk=fetch($sql);

   		$query = query("SELECT * From order_session WHERE id_produk='$id' AND session_id ='".session_id()."' ");
   		$cek=fetch($query);

   		if (numrows($query) < 1) {
	   		$save=query("INSERT into  order_session values('','$id','".session_id()."','1','$tglnow')");
		      if ($save){
		       	echo"$cart";

		      } else {
		        echo"$index";
		      }  
   		} else {
   			if ($cek['jumlah'] > $produk['stok']) {
   					$_SESSION['tcukup'] = ' Maaf Stok hanya tersedia '.$produk['stok'].'';
   				echo"$cart";
   			}

   			else {
   				$update = query("UPDATE order_session set jumlah = jumlah+1 WHERE id_produk='$id' AND session_id ='".session_id()."' ");
   				if ($update){
			       	echo"$cart";

			      } else {
			        echo"$index";
			      }  
   			}
   		}
	break;


	case 'cart_session_view':

	if ($quantity <= 0) {
		echo"$noadd";

	} else {

   		$sql = query("SELECT * From produk where id='$id'");
   		$produk=fetch($sql);

   		$query = query("SELECT * From order_session WHERE id_produk='$id' AND session_id ='".session_id()."' ");
   		$cek=fetch($query);
   		$ceks=numrows($query);

   		if (numrows($query) < 1) {

   			$qwt = $cek['jumlah'] + $quantity;
   			if ($qwt > $produk['stok']) {
   				$_SESSION['tcukup'] = ' Maaf Stok hanya tersedia '.$produk['stok'].'';
   				echo"$noupdate";
   			} else { 
	   		$save=query("INSERT into  order_session values('','$id','".session_id()."','$quantity','$tglnow')");
		      if ($save){
		       	echo"$cart";

		      } else {
		        echo"$index";
		      }
		    }    
   		} else {
   			$qwt = $cek['jumlah'] + $quantity; 
   			if ($qwt > $produk['stok']) {
   					$_SESSION['tcukup'] = ' Maaf Stok hanya tersedia '.$produk['stok'].'';
   				echo"$cart";
   			}

   			else {		
   				$update = query("UPDATE order_session set jumlah = jumlah+$quantity WHERE id_produk='$id' AND session_id ='".session_id()."' ");
   				if ($update){
			       	echo"$cart";

			      } else {
			        echo"$index";
			      }  
   			}

   		}
   	 }	

	break;
  	
  	case 'hapus_item':
	    $hapus = query ("DELETE FROM order_session WHERE id_produk='$id' AND session_id ='".session_id()."' ");
	      if ($hapus){
	        echo"$sukses"; 
	      } else {
	        echo"$gagal";
	      }
	break;

	case 'update_item':

   		$sql = query("SELECT * From produk where id='$id'");
   		$produk=fetch($sql);

   		$query = query("SELECT * From order_session WHERE id_produk='$id' AND session_id ='".session_id()."' ");
   		$cek=fetch($query);


   		if ($cek['jumlah'] == $quantity) {
   			echo"$noupdate";
   		}
   		else {
		    if ($quantity > $produk['stok']) {
	   			$_SESSION['tcukup'] = ' Maaf Stok hanya tersedia '.$produk['stok'].'';
	   				echo"$cart";
	   			}

	   			else {		
	   				$update = query("UPDATE order_session set jumlah ='$quantity' WHERE id_produk='$id' AND session_id ='".session_id()."' ");
	   				if ($update){
				       	echo"$cart";

				      } else {
				        echo"$index";
				      }  
	   			}
	   	 }
	   	 		
	  break;

	case 'checkout':
	  	if(empty($_SESSION[user]) || $_SESSION[level] != "member" )  {
	  		$_SESSION[pesan]="Silahkan Login terlebih dahulu";
			echo"<script languange='javascript'>
			document.location='cart.php#login_form';
			</script>";
	 	} else {
	 		echo"<script languange='javascript'>
				document.location='checkout.php';
			</script>";
	 	}		      
           
	 break;    

  }
?>   