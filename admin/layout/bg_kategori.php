<div class="row">
  <div class="grid">
  
    <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=merek'>Kategori</a> 
          </div>
        </div>
      </div> 
    </div>

<div class="col-wd-12">
  <div class="formbg">
    <div class="col pos-left animated fadeIn delay-07s">
      <div class="atas"><img src="img/cross.png"><span>Data Kategori</span></div>
        <div class="posbg">
          </td><td>
          <a href="#add_form"><button class="btn-tambah">
              <i class="fa fa-plus"></i> Tambah Kategori
          </button></a>

      <table id="t01">
        <tr>  
          <th>No</th>
          <th>Id Kategori</th>
          <th>Nama Kategori</th>
          <th>Keterangan</th>
          <th>Aksi</th>
        </tr>
<?php
$sql = query("SELECT * from kategori order by id_kategori ASC");
  $no = 1;
  while ($data=fetch($sql)) {
    echo'
        <tr>
          <td>'.$no.'</td>
          <td>'.$data['id_kategori'].'</td>
          <td>'.$data['nama_kat'].'</td>
          <td>'.$data['keterangan_kat'].'</td>
          <td>
          <a href="index.php?page=kategori&id='.$data['id_kategori'].'#edit_form">
            <button id="btna-edit"><i class="fa fa-pencil"></i></button>
          </a>
          <a href="#" onclick="if
          (confirm(\'Apakah anda ingin menghapus data = '.$data['id_kategori'],' ? \'))
          location.href=\'proses.php?act=hapus_kategori&id='.$data['id_kategori'].'\';">    
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
