<div class="row">
  <div class="grid">
     <div class="col-wd-12">
      <div class="navigasi">
        <div class="col animated fadeInDown">
          <div class="nav-beranda right">
          <a href='index.php'><i class="fa fa-home"></i> Dasboard</a></div>
          <div class="sub-nav">
              <a href='index.php?page=merek'>Profile</a> 
          </div>
        </div>
      </div> 
    </div>
<?php 
session_start();
$sql = query("SELECT * FROM user where username ='$_SESSION[user]'");
$data = fetch($sql);
echo'
    <div class="col-wd-12">
      <div class="col-wd-3">
        <div class="col animated fadeInLeft delay-02s" id="profkotak">
          <img src="data:image/png;base64,' . $data[foto] . '">
            <div id="nml">'.$data[nama_lengkap].'</div>

          <div class="fkotak">
            <i class="fa fa-star"></i> Level : '.$data[level].'
          </div>
        </div>
        <div class="col animated fadeInUp delay-07s" id="inkotak">
            <ul>
              <li><i class="fa fa-rss"></i>'.$data[email].'</li>
              <li><i class="fa fa-phone"></i>'.$data[tlp].'</li>
              <li><i class="fa fa-globe"></i>'.$data[alamat].'</li>
            </ul>
        </div>
      </div>
      <div class="col-wd-7">
        <div class="col animated fadeInRight delay-12s" id="setprof">
        <div class="hprof"> <i class="fa fa-cogs"></i> Setting Profile</div>
        <form method="post" enctype="multipart/form-data" action="proses.php?act=edit_profile">
            <div id="posprof">
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="'.$data[nama_lengkap].'"></div>
            <div id="posprof">
            <input type="email" name="email" placeholder="No telepon" value="'.$data[email].'"></div>
            <div id="posprof">
            <input type="tel" name="tlp" placeholder="No telepon" value="'.$data[tlp].'"></div>
            <div id="posprof">
            <textarea name="alamat" placeholder="Alamat">'.$data[alamat].'</textarea></div>
            <div id="posprof">
            <input type="file" name="gambar"></div>
            <div id="posprof">
            <button type="submit">Update profile</button>
            <button type="reset">Reset</button></div>
        </form>    
        </div>
      </div>
    </div>  
  </div>
</div>';
?>
