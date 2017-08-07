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
$sql = query("SELECT *from user where level='petugas'");
  while ($data=fetch($sql)) {
    echo'
        <tr>
          <td>'.$data['id_user'].'</td>
          <td>'.$data['nama_lengkap'].'</td>
          <td>'.$data['email'].'</td>
          <td>'.$data['tlp'].'</td>
          <td>'.$data['alamat'].'</td>
          <td><img src="data:image/png;base64,' . $data['foto'] . '" class="imgtbl"></td>
          <td>'.$data['bergabung_tgl'].'</td>
          <td>
          <a href="#" onclick="if
          (confirm(\'Apakah anda ingin menghapus = '.$data['id_user'],' ? \'))
          location.href=\'proses.php?act=hapus_petugas&id='.$data['id_user'].'\';">    
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