<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=member'>Member</a> 
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Data Member</span></div>
        <div class="posbg">

      <table id="t01">
        <tr>  
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>No Tlp</th>
          <th>Alamat</th>
          <th>Bergabung</th>
          <th>Aksi</th>
        </tr>
<?php
$sql = "SELECT id_user,nama_lengkap,email,tlp,alamat,dayofmonth(bergabung_tgl) AS tgl, month(bergabung_tgl) AS bln ,year(bergabung_tgl) as thn from user WHERE level ='member' order by id_user ASC";
$result =mysql_query($sql);
  $no = 1;
  while ($data=mysql_fetch_array($result)) {
     $bln= $data[bln];
    echo'
        <tr>
          <td>'.$no.'</td>
          <td>'.$data[nama_lengkap].'</td>
          <td>'.$data[email].'</td>
          <td>'.$data[tlp].'</td>
          <td>'.$data[alamat].'</td>
          <td>'.$data[tgl].' - '.$nama_bulan[$bln].' - '.$data[thn].'</td>
          <td>
          <a href="#" onclick="if
          (confirm(\'Apakah anda ingin menghapus = '.$data[0],' ? \'))
          location.href=\'proses.php?act=hapus_member&id='.$data[0].'\';">    
          <button id="btna-hapus"><i class="fa fa-trash"></i></button></a>
          </td>
        </tr>';
     $no++;     
    }
?>
      </table>
		
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>
