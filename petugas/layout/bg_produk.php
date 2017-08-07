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
      <div class="atas"><img src="img/cross.png"><span>Data Produk</span>  
        <form action="index.php?page=produk&bar=cari" method="POST"> 
          <input type="text" class="cari" name="cari" placeholder="Cari Produk...">
        </form>
      </div>

<?php
  $file="index.php?page=produk";
  $batas=5;
  $halaman=$_GET['halaman'];
    if(empty($halaman)){
      $posisi=0;
      $halaman=1;
    } else{
      $posisi = ($halaman-1) * $batas;
    }
?> 
      <div class="posbg">   
          <a href="index.php?page=produk#add_form"><button class="btn-tambah">
              <i class="fa fa-plus"></i> Tambah Produk
          </button></a>
      <table id="t01">
        <tr>  
          <th>No</th>
          <th>Nama</th>
          <th>Jenis</th>
          <th>Merek</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Stok 
            <a href="index.php?page=produk&bar=stok">
              <button class="urut" type="submit">
                <i class="fa fa-sort-amount-asc"></i>
              </button>
            </a>  
          </th>
          <th>Deskripsi</th>
          <th>Foto</th>
          <th>Aksi</th>
        </tr>
      
<?php
switch ($bar) {                  
    default:
      $sql = "SELECT produk.id, produk.nama, produk.jenis, produk.id_merek, produk.id_kategori, produk.harga, produk.stok, produk.deskripsi, produk.foto, produk.tgl_add, kategori.nama, merek.nama FROM produk INNER JOIN kategori ON produk.id_kategori = kategori.id_kategori INNER JOIN merek ON produk.id_merek = merek.id_merek order by produk.id ASC limit $posisi,$batas";
      $result =mysql_query($sql);
        $no = 1;
        while ($data=mysql_fetch_array($result)) {
          if ($data[6] < 10) {
            $stok = '<b style="color:red">'.$data[6].'</b>';
          } else {
            $stok = $data[6];
          }

          echo' 
              <tr class="animated flipInX delay-10s">
                <td>'.$no.'</td>
                <td>'.$data[1].'</td>
                <td>'.$data[2].'</td>
                <td>'.$data[11].'</td>
                <td>'.$data[10].'</td>
                <td>Rp. '.rupiah($data[5]).'</td>
                <td>'.$stok.'</td>
                <td width="200px">'.substr($data[7], 0, 80).'...</td>
                <td><img src="data:image/png;base64,' . $data[8] . '" class="imgtbl"></td>
                <td>
                <a href="index.php?page=produk&kd='.$data[0].'#stok_form">
                  <button id="btna-plus"><i class="fa fa-plus"></i></button>
                </a>
                <a href="index.php?page=produk&id='.$data[0].'#edit_form">
                  <button id="btna-edit"><i class="fa fa-pencil"></i></button>
                </a>
                <a href="#" onclick="if
                (confirm(\'Apakah anda ingin menghapus data = '.$data[0],' ? \'))
                location.href=\'proses.php?act=hapus_produk&id='.$data[0].'\';">    
                <button id="btna-hapus"><i class="fa fa-trash"></i></button></a>
                </td>
              </tr>';
           $no++;     
          }
    break;
    
    case 'cari':
      echo"<center>Hasil Pencarian untuk '<b>$cari</b>'</center>";

      $sql = "SELECT produk.id, produk.nama, produk.jenis, produk.id_merek, produk.id_kategori, produk.harga, produk.stok, produk.deskripsi, produk.foto, produk.tgl_add, kategori.nama, merek.nama FROM produk INNER JOIN kategori ON produk.id_kategori = kategori.id_kategori INNER JOIN merek ON produk.id_merek = merek.id_merek Where produk.nama LIKE '%$cari%' or produk.jenis LIKE '%$cari%' or kategori.nama LIKE '%$cari%' or merek.nama LIKE '%$cari%' order by produk.id ASC limit $posisi,$batas";
      $result =mysql_query($sql);
        $no = 1;
        while ($data=mysql_fetch_array($result)) {
   
        $nama = preg_replace("/(" . $cari . ")/i", "<b style='color:#3498DB !important;'>$1</b>", $data[1]);
        $jenis = preg_replace("/(" . $cari . ")/i", "<b style='color:#3498DB !important;'>$1</b>", $data[2]);
        $merek = preg_replace("/(" . $cari . ")/i", "<b style='color:#3498DB !important;'>$1</b>", $data[11]);
        $kategori = preg_replace("/(" . $cari . ")/i", "<b style='color:#3498DB !important;'>$1</b>", $data[10]);

          echo'
              <tr class="animated flipInX delay-10s">
                <td>'.$no.'</td>
                <td>'.$nama.'</td>
                <td>'.$jenis.'</td>
                <td>'.$merek.'</td>
                <td>'.$kategori.'</td>
                <td>Rp. '.rupiah($data[5]).'</td>
                <td>'.$data[6].'</td>
                <td width="200px">'.substr($data[7], 0, 100).'...</td>
                <td><img src="data:image/png;base64,' . $data[8] . '" class="imgtbl"></td>
                <td>
                <a href="index.php?page=produk&bar=cari&kd='.$data[0].'#stok_form">
                  <button id="btna-plus"><i class="fa fa-pencil"></i></button>
                </a>
                <a href="index.php?page=produk&bar=cari&id='.$data[0].'#edit_form">
                  <button id="btna-edit"><i class="fa fa-pencil"></i></button>
                </a>
                <a href="#" onclick="if
                (confirm(\'Apakah anda ingin menghapus data = '.$data[0],' ? \'))
                location.href=\'proses.php?act=hapus_produk&id='.$data[0].'\';">    
                <button id="btna-hapus"><i class="fa fa-trash"></i></button></a>
                </td>
              </tr>';
           $no++;     
          }   
    break;

    case 'stok':
       $sql = "SELECT produk.id, produk.nama, produk.jenis, produk.id_merek, produk.id_kategori, produk.harga, produk.stok, produk.deskripsi, produk.foto, produk.tgl_add, kategori.nama, merek.nama FROM produk INNER JOIN kategori ON produk.id_kategori = kategori.id_kategori INNER JOIN merek ON produk.id_merek = merek.id_merek order by produk.stok ASC limit $posisi,$batas";
      $result =mysql_query($sql);
        $no = 1;
        while ($data=mysql_fetch_array($result)) {
          if ($data[6] < 10) {
            $stok = '<b style="color:red">'.$data[6].'</b>';
          } else {
            $stok = $data[6];
          }

          echo' 
              <tr class="animated flipInX delay-10s">
                <td>'.$no.'</td>
                <td>'.$data[1].'</td>
                <td>'.$data[2].'</td>
                <td>'.$data[11].'</td>
                <td>'.$data[10].'</td>
                <td>Rp. '.rupiah($data[5]).'</td>
                <td>'.$stok.'</td>
                <td width="200px">'.substr($data[7], 0, 80).'...</td>
                <td><img src="data:image/png;base64,' . $data[8] . '" class="imgtbl"></td>
                <td>
                <a href="index.php?page=produk&bar=stok&kd='.$data[0].'#stok_form">
                  <button id="btna-plus"><i class="fa fa-plus"></i></button>
                </a>
                <a href="index.php?page=produk&bar=stok&id='.$data[0].'#edit_form">
                  <button id="btna-edit"><i class="fa fa-pencil"></i></button>
                </a>
                <a href="#" onclick="if
                (confirm(\'Apakah anda ingin menghapus data = '.$data[0],' ? \'))
                location.href=\'proses.php?act=hapus_produk&id='.$data[0].'\';">    
                <button id="btna-hapus"><i class="fa fa-trash"></i></button></a>
                </td>
              </tr>';
           $no++;     
          }
    break;
  }        
?>
      </table>
      <div align="right" class="pagging">
      <?php
        $hasil2=mysql_query("SELECT * from produk");
        $jmldata=mysql_num_rows($hasil2);
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
            ?>
		        </div>
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
