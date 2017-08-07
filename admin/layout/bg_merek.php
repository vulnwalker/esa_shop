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
      <div class="atas"><img src="img/cross.png"><span>Data Merek</span></div>
        <div class="posbg">
          </td><td>
          <a href="#add_form"><button class="btn-tambah">
              <i class="fa fa-plus"></i> Tambah Merek
          </button></a>

      <table id="t01">
        <tr>  
          <th>No</th>
          <th>Id jenis biaya</th>
          <th>Nama merek</th>
          <th>Keterangan</th>
          <th>Foto</th>
          <th>Aksi</th>
        </tr>
<?php
$sql = query("SELECT * from merek order by id_merek ASC");
  $no = 1;
  while ($data=fetch($sql)) {
    echo'
        <tr>
          <td>'.$no.'</td>
          <td>'.$data['id_merek'].'</td>
          <td>'.$data['nama_merek'].'</td>
          <td>'.$data['keterangan_merek'].'</td>
          <td><img src="data:image/png;base64,' . $data['logo'] . '" class="imgtbl"></td>
          <td>
          <a href="index.php?page=merek&id='.$data['id_merek'].'#edit_form">
            <button id="btna-edit"><i class="fa fa-pencil"></i></button>
          </a>
          <a href="#" onclick="if
          (confirm(\'Apakah anda ingin menghapus = '.$data['id_merek'],' ? \'))
          location.href=\'proses.php?act=hapus_merek&id='.$data['id_merek'].'\';">    
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
