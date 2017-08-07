<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=transaksi'>Transaksi</a> 
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Data Transaksi</span></div>
        <div class="posbg">
          </td><td>

      <table id="t01">
        <tr>  
          <th>No</th>
          <th>Id Order</th>
          <th>Nama Pembeli</th>
          <th>Tanggal Order</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
<?php
$sql = "SELECT *FROM order_produk,user where user.id_user = order_produk.id_pembeli order by konfirmasi_order ASC";
$result =mysql_query($sql);
  $no = 1;
  while ($data=mysql_fetch_array($result)) {
    if ($data[konfirmasi_order] == "belum") {
      echo'
          <tr>
            <td>'.$no.'</td>
            <td>'.$data[id_order].'</td>
            <td>'.$data[nama_lengkap].'</td>
            <td>'.$data[tgl_order].'</td>
            <td>'.$data[konfirmasi_order].'</td>
            <td>
            <a href="index.php?page=detail&order='.$data[id_order].'"> 
              <button id="btna-plus"><i class="fa fa-check"></i> Konfirmasi</button>
            </a>
            <a href="#" onclick="if
                  (confirm(\'Apakah anda ingin mereject order = '.$data[id_order],' ? \'))
                  location.href=\'proses.php?act=hapus_order&id='.$data[id_order].'\';">   
            <button id="btna-hapus"><i class="fa fa-close"></i> Hapus</button></a>
            </td>
          </tr>';
       $no++;
    } else {   
      echo'
          <tr>
            <td>'.$no.'</td>
            <td>'.$data[id_order].'</td>
            <td>'.$data[nama_lengkap].'</td>
            <td>'.$data[tgl_order].'</td>
            <td>'.$data[konfirmasi_order].'</td>
            <td>
            <a href="index.php?page=detail&order='.$data[id_order].'"> 
              <button id="btna-edit"><i class="fa fa-check"></i> Detail</button>
            </a>
            <a href="#" onclick="if
                  (confirm(\'Apakah anda ingin mereject order = '.$data[id_order],' ? \'))
                  location.href=\'proses.php?act=hapus_order&id='.$data[id_order].'\';">   
            <button id="btna-hapus"><i class="fa fa-close"></i> Hapus</button></a>
            </td>
          </tr>';
       $no++;
       }     
    }
?>
      </table>
		
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
