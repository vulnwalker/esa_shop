<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=pegawai'>Data User</a> 
            <i class="fa fa-angle-right"></i>
               Data Pegawai
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left">
      <div class="atas"><img src="img/cross.png"><span>Data Petugas</span></div>
        <div class="posbg">
  
          <a href="#add_form"><button class="btn-tambah">
              <i class="fa fa-plus"></i> Tambah Data Petugas
          </button></a>
  
      <table id="t01">
        <tr>  
          <th>ID User</th>
          <th>Nama</th>
          <th>Email</th>
          <th>No tlp</th>
          <th>Alamat</th>
          <th>Foto</th>
          <th>Bergabung</th>
          <th>Aksi</th>
        </tr>
<?php
$sql = "SELECT *from user where level='petugas'";

$result =mysql_query($sql);
  while ($data=mysql_fetch_array($result)) {
    $bln= $data[6];
    echo'
        <tr>
          <td>'.$data[0].'</td>
          <td>'.$data[3].'</td>
          <td>'.$data[1].'</td>
          <td>'.$data[5].'</td>
          <td>'.$data[6].'</td>
          <td><img src="data:image/png;base64,' . $data[7] . '" class="imgtbl"></td>
          <td>'.$data[9].'</td>
          <td>
          <a href="#" onclick="if
          (confirm(\'Apakah anda ingin menghapus = '.$data[0],' ? \'))
          location.href=\'proses.php?act=hapus_petugas&id='.$data[0].'\';">    
          <button id="btna-hapus"><i class="fa fa-trash"></i></button></a>
          </td>
        </tr>';}
?>
      </table>
		
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>