<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=merek'>Merek</a> 
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Laporan Transaksi</span></div>
        <div class="posbg">
         <form method="POST" action="index.php?page=lap_transaksi&hal=selected">
          <table>
          <th>
          <select name="banyak" id="boxcari" onChange='this.form.submit()' required>
          <option value="" selected disabled> - Pilih salah satu - </option>
          <option value="0">Laporan Hari Ini</option>
          <option value="7">Laporan Minggu Ini</option>
          <option value="30">Laporan Bulan Ini</option> 
          </select>
          </th>
          </table>
        </form>

      <table id="t01">
        <tr>  
          <th>Id Order</th>
          <th>Pembeli</th>
          <th>Alamat Rumah</th>
          <th>Bank</th>
          <th>Jasa Pengiriman</th>
          <th>Tanggal</th>
          <th>Id Petugas</th>
        </tr>
<?php

switch ($hal) {  
  default:
    $sql = query("SELECT *FROM user,order_produk where user.id_user = order_produk.id_pembeli AND order_produk.konfirmasi_order='sudah' order by order_produk.tgl_order DESC");
    while ($data=fetch($sql)) {
    echo'
        <tr>
          <td>'.$data[id_order].'</td>
          <td>'.$data[nama_lengkap].'</td>
          <td>'.$data[alamat_rumah].'</td>
          <td>'.$data[bank].'</td>
          <td>'.$data[jasa_pengiriman].'</td>
          <td>'.$data[tgl_order].'</td>
          <td>'.$data[id_petugas].'</td>
        </tr>';  
    }

    break;

  case 'selected':
    $sql = query("SELECT *FROM user,order_produk where user.id_user = order_produk.id_pembeli AND order_produk.konfirmasi_order='sudah' AND order_produk.tgl_order >= DATE_SUB(curdate(),INTERVAL $banyak day) order by order_produk.tgl_order DESC");
    $cek = numrows($sql);

    if ($cek > 1) {
        while ($data=fetch($sql)) {
        echo'
            <tr>
              <td>'.$data[id_order].'</td>
              <td>'.$data[nama_lengkap].'</td>
              <td>'.$data[alamat_rumah].'</td>
              <td>'.$data[bank].'</td>
              <td>'.$data[jasa_pengiriman].'</td>
              <td>'.$data[tgl_order].'</td>
              <td>'.$data[id_petugas].'</td>
            </tr>';  
        }
        echo"<a href='../laporan.php?page=transaksi&banyak=$banyak'><button class='btn-tambah1'><i class='fa fa-print'></i> Cetak Laporan</button></a>";  
    } else {
        echo'
          <div align="center" style="color:#E63838;font-style:bold">Maaf Data Laporan Tidak tersedia !</div><br/>
        ';
    }
   
    break;  
}
?>
      </table>
		
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
