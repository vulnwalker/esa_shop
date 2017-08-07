<?php
  $kategori = base64_decode($id);
  $file="index.php?page=kategori&id=$id";
  $batas=4;
  $halaman=$_GET['halaman'];
    if(empty($halaman)){
      $posisi=0;
      $halaman=1;
    } else{
      $posisi = ($halaman-1) * $batas;
    }
?> 
	<div class="col-wd-12">
		<div class="col animated fadeInDown" id="hasilcari">
			<i class="fa  fa-circle-o "></i> Kategori Produk <b><?php echo $kategori ?> </b>
		</div>
	</div>
<div class="item">
	<div class="col-wd-12">
		<?php
			$sql = "SELECT *FROM produk,kategori,merek WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek AND kategori.nama_kat ='$kategori' AND produk.stok >= 3 order by produk.id DESC limit $posisi,$batas";
			$result = query($sql);
			$hasil 	=numrows($result);
			if ($hasil > 0) {			 
			$i = 4;
			while ($data = fetch($result)) {

			 $nama = preg_replace("/(" . $cari . ")/i", "<b style='color:#11C0FF !important;'>$1</b>", $data['nama_produk']);
			 $merek = preg_replace("/(" . $cari . ")/i", "<b style='color:#11C0FF !important;'>$1</b>", $data['nama_merek']);	
			    echo'	
					<div class="col-wd-3" id="produkcari">
						<div class="col animated zoomIn delay-0'.$i.'s">
							<a href="index.php?page=produk&id='.$data['id'].'">
								<img src="data:image/png;base64,' . $data['foto'] . '">
							</a>	
							<div class="detail">
								<div class="col-wd-12">
									'.$nama.'
								</div>
								<div class="info">
									<b>Rp. </b> '.rupiah($data['harga']).'
									<div class="merek">
										<b>Merek</b> : '.$merek.'
									</div>	
								</div>
								<form action="proses.php?act=cart_session" method="POST">			
									<input type="hidden" value="'.$data['id'].'" name="id">
									<button id="btn-cart" class="btn-kcl last">
										<i class="fa fa-shopping-cart"></i>
									</button>
								</form>
								<a href="index.php?page=produk&id='.$data['id'].'">	
									<button id="btn-view" class="btn-kcl">
										<i class="fa fa-search"></i>	
								   </button>
								</a>   
								</div>
							</div>
						</div>';
						$i = $i + 2;
						} ?>
					</div>
				</div>					
				<div class="col-wd-12">
					<div class="col" id="pagebar">
						<div align="right">
						<?php
					        $hasil2=query("SELECT *FROM produk,kategori,merek WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek AND kategori.nama_kat ='$kategori' AND produk.stok >= 3 order by produk.id ");
					        $jmldata=numrows($hasil2);
					        $jmlhalaman=ceil($jmldata/$batas);

					          if($halaman > 1){
					              $previous=$halaman-1;
					                echo "<a href=$file&halaman=$previous class='pagenav left'><i class='fa fa-angle-left'></i></a>";
					          } else { 
					                 echo '<a href="#" class="pagenav1"><i class="fa fa-angle-left"></i></a>';
					          }

					          for($i=1;$i<=$jmlhalaman;$i++)
					              if ($i != $halaman){
					                  echo "<a href=$file&halaman=$i class='numpage'>$i</A>";
					              } else{
					                  echo "<b class='numpage in'>$i</b>";
					              }

					          if($halaman < $jmlhalaman){
					                  $next=$halaman+1;
					                  echo "<a href=$file&halaman=$next class='pagenav right'><i class='fa fa-angle-right'></i></a>";
					              } else { 
					                  echo '<a href="#" class="pagenav1"><i class="fa fa-angle-right"></i></a>';
					              }
			            ?>
						</div>
					</div>
				</div>
				<?php
					 } else {
					 	echo'
					 		<div class="col-wd-12">
					 			<div class="col animated fadeInDown delay-06s" 
					 				 style="border:2px solid red; background:#efefef; padding:5px;">
									 Maaf Kategori Produk yang anda pilih masih belum tersedia
								</div>
					 		</div>';
					 }
				?>		
	</div>
</div>


