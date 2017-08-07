<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=merek'>Transaksi</a> 
          </div>
        </div>
      </div> 
    </div>
<?php

$sql = mysql_query("SELECT *FROM order_produk,user where user.id_user = order_produk.id_pembeli AND order_produk.id_order ='$order' order by id_order ASC");
$data = mysql_fetch_array($sql);
echo'
<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Detail Transaksi</span></div>
        <div class="posbg">
          
          <div class="row">
            <div class="detailorder">
              <div class="col-wd-4">
                <div class="col">
                <div class="topdetail"> Data Pembeli</div>
                  <table width="95%">
                    <tr>
                      <td width="25%" id="ddetail">*Nama</td>
                      <td width="70%">'.$data[nama_lengkap].'</td>
                    </tr><tr>  
                      <td id="ddetail">*Email</td>
                      <td>'.$data[email].'</td>
                    </tr><tr>
                      <td id="ddetail">*No Tlp</td>
                      <td>'.$data[tlp].'</td>
                    </tr><tr>
                      <td id="ddetail">*Alamat</td>
                      <td>'.$data[alamat].'</td>
                    </tr>
                  </table>
                </div>
              </div>';?>

              <div class="col-wd-8">
                <div class="col">
                  <div class="topdetail"> Data Order</div>
                     <table id="t01">
                      <tr>  
                        <th>No</th>
                        <th>Nam Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                      </tr>
                      <?php
                      echo'<form action="aksi.php?act=konfirmorder" method="POST">';
                        $sql = "SELECT *FROM order_produk,order_detail,produk where order_detail.id_order = order_produk.id_order AND order_detail.id_produk = produk.id AND order_produk.id_order='$order'";
                        $result =mysql_query($sql);
                          $no = 1;
                          while ($data=mysql_fetch_array($result)) {
                            $subtotal = $data[jumlah] * $data[harga];
                            $total    = $total + $subtotal;  
                            $bank     = $data[bank];
                            $jasa     = $data[jasa_pengiriman];
                            $confirm  = $data[konfirmasi_order];    
                            echo'
                                <input type="hidden" name="id[]" value="'.$data[id].'">
                                <input type="hidden" name="jumlah[]" value="'.$data[jumlah].'">
                                <input type="hidden" name="id_order" value="'.$order.'"> 
                                <tr>
                                  <td>'.$no.'</td>
                                  <td>'.$data[nama].'</td>
                                  <td>'.$data[jumlah].'</td>
                                  <td>Rp. '.rupiah($data[harga]).'</td>
                                  <td>Rp. '.rupiah($subtotal).'</td>
                                </tr>';
                             $no++;     
                            }                         
                            echo'<tr>
                                  <td colspan="3"></td>
                                  <td><b>Total</b></td>
                                  <td style="text-align:center"><b>Rp. '. rupiah($total). ' </b></td>
                                </tr>
                                <tr>
                                  <td colspan="3"></td>
                                  <td><b>Bank Tujuan</b></td>
                                  <td style="text-align:center">'.$bank.'</td>
                                </tr>
                                <tr>
                                  <td colspan="3"></td>
                                  <td><b>Jasa Pengiriman</b></td>
                                  <td style="text-align:center">'.$jasa.'</b></td>
                                </tr>
                      </table>
                     <div align="right">';
                     if ($confirm == "belum") {
                       echo'<button type="submit" class="konfirm">Konfirmasi Order</button>';
                     }
                     echo'
                     </div>
                     </form>';
                    ?>
                </div>
              </div>
            </div>
          </div>

     
		
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
