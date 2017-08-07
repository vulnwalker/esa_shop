<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=merek'>Produk</a> 
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Laporan Produk</span> 
      </div>

<?php
  $file="index.php?page=lap_barang";
  $batas=10;
  $halaman=$_GET['halaman'];
    if(empty($halaman)){
      $posisi=0;
      $halaman=1;
    } else{
      $posisi = ($halaman-1) * $batas;
    }
?>  <div class="posbg">
         <form method="POST" action="index.php?page=lap_barang&hal=selected">
          <table>
          <th>
          <select name="urut" id="boxcari" required>
            <option value="" selected disabled> - Urutkan Berdasarkan - </option>
            <option value="produk.nama_produk ASC">Nama Produk</option>
            <option value="produk.id_merek ASC">Merek</option>
            <option value="produk.terjual DESC">Produk Terlaris</option>
            <option value="produk.stok ASC">Stok Produk Terkecil</option> 
          </select>
          </th>
          <th>
          <select name="angka" id="batas" onChange='this.form.submit()' required>
            <option value="" selected disabled> - Batas</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option> 
            <option value="30">30</option> 
            <option value="40">40</option>
            <option value="999999">Semua</option> 
          </select>
          </th>

          </table>
        </form>


      <table id="t01">
        <tr>  
          <th>Nama</th>
          <th>Jenis</th>
          <th>Merek</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Terjual</th>
          <th>Stok</th>
        </tr>
      
<?php
switch ($hal) {                  
    default:
      $sql = query("SELECT *From produk, kategori, merek WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek order by produk.id ASC limit $posisi,$batas");
      $cek = numrows($sql);

      if ($cek > 1) {
         while ($data=fetch($sql)) {
          if ($data['stok'] < 10) {
            $stok = '<b style="color:red">'.$data['stok'].'</b>';
          } else {
            $stok = $data['stok'];
          }
          echo' 
              <tr class="animated flipInX delay-10s">
                <td>'.$data['nama_produk'].'</td>
                <td>'.$data['jenis'].'</td>
                <td>'.$data['nama_merek'].'</td>
                <td>'.$data['nama_kat'].'</td>
                <td>Rp. '.rupiah($data['harga']).'</td>
                <td>'.$data['terjual'].'</td>
                <td>'.$stok.'</td>
              </tr>';    
          }
      } else {
        echo'
          <div align="center" style="color:#E63838;font-style:bold">Maaf Data Laporan Tidak tersedia !</div><br/>
        ';
      }     
        ?>
        </table>
          <div align="right" class="pagging">
          <?php
            $hasil2=query("SELECT * from produk");
            $jmldata=numrows($hasil2);
            $jmlhalaman=ceil($jmldata/$batas);

              if($halaman > 1){
                  $previous=$halaman-1;
                    echo "<A HREF=$file&halaman=$previous class='pagenav left'><i class='fa fa-angle-left'></i></A>";
              } else { 
                     echo "<span class='pagenav1 left next'><i class='fa fa-angle-left'></i></span>";
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
                      echo "<span class='pagenav1 right'><i class='fa fa-angle-right'></i></span>";
                  }  
    break; 

    case 'selected':
       $sql = query("SELECT *from produk, kategori, merek WHERE produk.id_kategori = kategori.id_kategori AND produk.id_merek = merek.id_merek order by $urut limit $angka");
       while ($data=fetch($sql)) {
          if ($data['stok'] < 10) {
            $stok = '<b style="color:red">'.$data['stok'].'</b>';
          } else {
            $stok = $data['stok'];
          }
          echo' 
              <tr class="animated flipInX delay-10s">
                <td>'.$data['nama_produk'].'</td>
                <td>'.$data['jenis'].'</td>
                <td>'.$data['nama_merek'].'</td>
                <td>'.$data['nama_kat'].'</td>
                <td>Rp. '.rupiah($data['harga']).'</td>
                <td>'.$data['terjual'].'</td>
                <td>'.$stok.'</td>
              </tr>';    
          }
          echo"<a href='../laporan.php?page=produk&urut=$urut&batas=$angka'><button class='btn-tambah1'><i class='fa fa-print'></i> Cetak Laporan</button></a>";   
          echo'</table>';
    break;
  }        
?>
		        </div>
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
