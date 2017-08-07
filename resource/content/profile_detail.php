<?php session_start();
$file="profile.php";
$batas=5;
$halaman=$halaman;
if(empty($halaman)){
      $posisi=0;
      $halaman=1;
    } else{
      $posisi = ($halaman-1) * $batas;
    }

echo'
<div class="nav">
	<div class="col-wd-12">
		<div class="col animated fadeInDown">
			<i class="fa fa-home"></i>
				<a href="index.php"> Beranda </a>
			<i class="fa fa-angle-right"></i>
				<a href="#">Profile Member</a>	
		</div>
	</div>
</div>
<div class="col-wd-12">
	<div class="deskripsi">
		<div class="col animated zoomIn delay-08s">
			<div class="jhistori">Histori Pembelian</div>
			<table id="t01">
			   <tr>  
			     <th>No</th>
			     <th>Id Order</th>
			     <th>Alamat</th>
				 <th>Bank Tujuan</th>
			     <th>Jasa Pengiriman</th>
			     <th>Tanggal Pemesanan</th>
			     <th>Status</th>
			   </tr>';
			   $sql = query("SELECT *FROM order_produk,user where user.id_user = order_produk.id_pembeli AND user.username='$_SESSION[user]' order by order_produk.konfirmasi_order limit $posisi,$batas");
			    $no = 1;	
				while($data=fetch($sql)){
					if ($data[konfirmasi_order] == "belum") {
						$status ='<div style="color:#444"><i class="fa fa-circle-o-notch fa-spin"></i> Belum</div>';
					} else {
						$status = '<div style="color:green"><i class="fa fa-check"></i> Sudah</div>';
					}
					echo'		
						<tr>	
							<td>'.$no.'</td>
				     		<td>'.$data[id_order].'</td>
				     		<td>'.$data[alamat].'</td>
				     		<td>'.$data[bank].'</td>
				     		<td>'.$data[jasa_pengiriman].'</td>
				     		<td>'.$data[tgl_order].'</td>
				     		<td>'.$status.'</td>
				   		 </tr>';
				   		 $no++;		
						}
			echo'	   	
			 </table>  
		</div>
	</div>
	<br>	
</div>';
			$hasil2=query("SELECT *FROM order_produk,user where user.id_user = order_produk.id_pembeli AND user.username='$_SESSION[user]' order by order_produk.konfirmasi_order");
			$jmldata=numrows($hasil2);
		        $jmlhalaman=ceil($jmldata/$batas);

		          if($halaman > 1){
		              $previous=$halaman-1;
		                echo "<a href=$file?halaman=$previous class='pagenav left'><i class='fa fa-angle-left'></i></a>";
		          } else { 
		                 echo '<a href="#" class="pagenav1"><i class="fa fa-angle-left"></i></a>';
		          }

		          for($i=1;$i<=$jmlhalaman;$i++)
		              if ($i != $halaman){
		                  echo "<a href=$file?halaman=$i class='numpage'>$i</A>";
		              } else{
		                  echo "<b class='numpage in'>$i</b>";
		              }

		          if($halaman < $jmlhalaman){
		                  $next=$halaman+1;
		                  echo "<a href=$file?halaman=$next class='pagenav right'><i class='fa fa-angle-right'></i></a>";
		              } else { 
		                  echo '<a href="#" class="pagenav1"><i class="fa fa-angle-right"></i></a>';
		              }	

?>