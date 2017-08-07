<?php session_start();
include'../koneksi.php';
include'../func/fungsi.php';

switch($act){
  case 'login':
    $password = MD5($pass);
    $data = mysql_query("SELECT * from user where username='$username' AND password='$password' AND level='petugas'");
    $cek=mysql_num_rows($data);
     
    if($cek==0){
      $_SESSION[pesan]="Username atau Password yang anda masukan salah";
      echo"<script languange='javascript'>
      document.location='login.php';
      </script>";
    } else{
      $a=mysql_fetch_array($data);
      $_SESSION[user]=$a[username];
      $_SESSION[level]=$a[level];
      $_SESSION[id_user]=$a[id_user];
      if($_SESSION[level] =="petugas"){header('location:index.php');}
    }
  break;

  case 'logout':
   
     session_destroy();
     header('location:login.php');
   
  break;

  case 'add_merek':
     if (exten($extensi) == 'jpg' OR 'jpeg' OR 'png' OR 'gif') {
        $img = encode($gambar);
          $isi = "'$id_merek','$nama','$keterangan','$img'";
          insert('merek',($isi));
     } else {
            echo "$notformat";
     }         
  break;

  case 'edit_merek':
    if (!empty($extensi)) { 
        if (exten($extensi) == 'jpg' OR 'jpeg' OR 'png' OR 'gif') {
          $img = encode($gambar);
          $edit= query("UPDATE merek set nama='$nama', keterangan='$keterangan' ,logo='$img' WHERE id_merek='$id_merek'");
            alertupdate($edit);  
         } else {
            echo"$notformat";
         }
    } else {
      $edit= query("UPDATE merek set nama='$nama', keterangan='$keterangan' WHERE id_merek='$id_merek'");
        alertupdate($edit); 
    }      
  break;
  
  case 'hapus_merek':
      hapus('merek', 'id_merek="' . $id . '"');
  break; 

  case 'add_kategori':
    $isi = "'$id_kategori','$nama','$keterangan'";
    insert('kategori',($isi));
  break;

  case 'edit_kategori': 
    $edit= query("UPDATE kategori set nama='$nama', keterangan='$keterangan' WHERE id_kategori='$id_kategori'");
      alertupdate($edit);             
  break;

  case 'hapus_kategori':
    hapus('kategori', 'id_kategori="' . $id . '"');
  break;

  case 'add_produk':
  if ($harga < 1000) {
    echo"$cekharga";
  } else {
     if (exten($extensi) == 'jpg' OR 'jpeg' OR 'png' OR 'gif') {
         $img = encode($gambar);
          $isi = "'','$nama','$jenis','$merek','$kategori','$harga','$stok','0','$deskripsi','$img','$tglsekarang'";
          insert('produk',($isi));
     } else {
            echo "$notformat";
     }
   }           
  break;

  case 'edit_produk':
    if (!empty($extensi)) {     
        if (exten($extensi) == 'jpg' OR 'jpeg' OR 'png' OR 'gif') {
          $img  = encode($gambar);
          $edit = query("UPDATE produk set nama='$nama', jenis='$jenis', id_merek='$merek', id_kategori='$kategori', harga='$harga', deskripsi='$deskripsi', foto='$img' WHERE id='$id'");
                alertupdate($edit);    
         } else {
            echo"$notformat";
          }
    } else {
      $edit= query("UPDATE produk set nama='$nama', jenis='$jenis', id_merek='$merek', id_kategori='$kategori', harga='$harga', deskripsi='$deskripsi' WHERE id='$id'");
                alertupdate($edit);    
    }      
  break;

  case 'hapus_produk':
    hapus('produk', 'id="' . $id . '"');
  break;

  case 'tambah_stok': 
    $hasil = $stok_awal + $add;
    $edit = query("UPDATE produk set stok=$hasil WHERE id='$id'");
    $isi = "'','$id','$stok_awal','$add','$hasil','$tglsekarang','$_SESSION[id_user]','$_SESSION[user]'";
    insert('tambah_stok',($isi));        
  break;

  case 'edit_profile':
    if (!empty($extensi)) {     
          if (exten($extensi) == 'jpg' OR 'jpeg' OR 'png' OR 'gif') {
          $img = encode($gambar);
          $edit= query("UPDATE user set nama_lengkap='$nama_lengkap', email='$email', tlp='$tlp', alamat='$alamat', foto='$img' WHERE username ='$_SESSION[user]' ");
                alertupdate($edit);   
         } else {
            echo"$notformat";
          }
    } else {
      $edit= query("UPDATE user set nama_lengkap='$nama_lengkap', email='$email', tlp='$tlp', alamat='$alamat' WHERE username ='$_SESSION[user]' ");
            if ($edit){ echo"$suksesprof"; } else { echo"$gagal"; }    
    }      
  break;

  case 'hapus_order':
    $hapus = query ("DELETE FROM  order_produk WHERE id_order = '$id'");
    $delete = query ("DELETE FROM  order_detail WHERE id_order = '$id'");
      if ($hapus AND $delete){ echo"$sukseshapus"; } else { echo"$gagalhapus"; }
  break;

  case 'hapus_member':
    hapus('user', 'id_user="' . $id . '"');
  break;

  case 'add_petugas':
      $data = mysql_query("SELECT * from user where level='petugas' AND username='$email'");
      $cek=mysql_num_rows($data);

      if($cek >= 1){
        echo"<script>
              alert('Email suadh terpakai silahkan gunakan yang lain');
              history.go(-1);
            </script>";
      } elseif ($pass != $pass1){
        echo"<script>
              alert('Password Dan Retype Password Anda Tidak Sama! ');
              history.go(-1);
             </script>";
      }
      else {
        $pw = md5($pass);
        $isi = "'','$email','$pw','$nama','$email','$tlp','$alamat','$defaultimg','petugas','$tglsekarang'";
        insert('user',($isi));
      }  
  break;

  case 'hapus_petugas':
     hapus('user', 'id_user="' . $id . '"');
  break;

}
?>