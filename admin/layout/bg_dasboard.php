<?php 
session_start();
$sql  = query("SELECT * FROM user where username ='$_SESSION[user]'");
$data = fetch($sql);

$jam   = date('H:i:s');
$jam4  = '04:00';
$jam10 = '10:00';
$jam14 = '14:30';
$jam18 = '18:00';

if ($jam >= $jam4 AND $jam <= $jam10) {
    $alert = '<i class="fa fa-soundcloud"></i> Selamat Pagi..';
} elseif ($jam > $jam10 AND $jam <= $jam14) {
    $alert = '<i class="fa fa-sun-o"></i> Selamat Siang..';
} elseif ($jam > $jam14 AND $jam < $jam18) {
    $alert = '<i class="fa fa-sun-o"></i> Selamat Sore..';
} else {
    $alert = '<i class="fa fa-moon-o"></i> Selamat Malam..';
}

$sqlusr = query("SELECT COUNT(*) as usr from user Where level='member'");
$cekusr = fetch($sqlusr);


$sqlpk = query("SELECT COUNT(*) as pk from produk");
$cekpk = fetch($sqlpk);


$sqlop = query("SELECT COUNT(*) as op from order_produk");
$cekop = fetch($sqlop);


echo'
<div class="profatas">
  <div class="row">
    <div class="grid">
      <div class="col-wd-5">
        <img src="data:image/png;base64,' . $data[foto] . '"> 
          <span class="users">'.$alert.'</span>
          <span id="name-user" class="users">'.$data[nama_lengkap].'</span>
      </div>
      <div class="col-wd-2">
        <div class="col" id="kotakinfo">
          <span>'.$cekop[op].'</span><i class="fa fa-shopping-cart"></i>
          <div id="dkbotoom">Order</div>
        </div>
      </div>
      <div class="col-wd-2">
        <div class="col" id="kotakinfo">
          <span>'.$cekpk[pk].'</span><i class="fa fa-product-hunt"></i>
          <div id="dkbotoom">Produk</div>
        </div>
      </div>
      <div class="col-wd-2">
        <div class="col" id="kotakinfo">
          <span>'.$cekusr[usr].'</span><i class="fa fa-users"></i>
          <div id="dkbotoom">Member</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="grid" id="dasg">
      <div class="col-wd-12">
        <div class="col-wd-8">
          <div class="col neworder">
            <div id="titledas">New Order</div>
              <table id="t01" class="beda01">
                <tr>  
                  <th>No</th>
                  <th>Id Order</th>
                  <th>Nama Pembeli</th>
                  <th>Tanggal Order</th>
                  <th>Aksi</th>
                </tr>';
                  $sql = "SELECT *FROM order_produk,user where user.id_user = order_produk.id_pembeli order by konfirmasi_order ASC limit 4";
                  $result = query($sql);
                    $no = 1;
                    while ($data= fetch($result)) {
                      if ($data[konfirmasi_order] == "belum") {
                        echo'
                            <tr>
                              <td>'.$no.'</td>
                              <td>'.$data[id_order].'</td>
                              <td>'.$data[nama_lengkap].'</td>
                              <td>'.$data[tgl_order].'</td>
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
                        }
                       }  
                  echo'         
            </table>
            <a href="index.php?page=transaksi"><div class="ftab f-blue">Lihat Semua Data Order</div></a>
          </div>
        </div>
      <div class="col-wd-4">
        <div class="col newmember">
          <div id="titledas1">Member Baru</div>
              <table id="t01" class="beda02">
                <tr>  
                  <th>Id Member</th>
                  <th>Nama</th>
                  <th>Bergabung</th>
                </tr>';
                  $sqlm = "SELECT id_user,nama_lengkap,dayofmonth(bergabung_tgl) AS tgl, month(bergabung_tgl) AS bln ,year(bergabung_tgl) as thn from user WHERE level ='member' order by id_user DESC";
                  $hasil =query($sqlm);
                    $no = 1;
                    while ($datam= fetch($hasil)) {
                       $bln= $datam[bln];
                      echo'
                          <tr>
                            <td>'.$datam[id_user].'</td>
                            <td>'.$datam[nama_lengkap].'</td>
                            <td>'.$datam[tgl].' - '.$nama_bulan[$bln].' - '.$datam[thn].'</td>
                          </tr>';
                       $no++;     
                      }
                  echo'
               </table>
               <a href="index.php?page=member"><div class="ftab f-green">Lihat Semua Data Member</div></a> 
        </div>
      </div>
    </div>
  </div>
</div>';?>

