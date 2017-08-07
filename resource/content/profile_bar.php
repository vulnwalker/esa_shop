<?php session_start();
// include 'func/fungsi.php';
$sql = query("SELECT * FROM user where username ='$_SESSION[user]'");
$data = fetch($sql);
echo'
      <div class="col-wd-3">
        <div class="col animated fadeInLeft delay-02s" id="profkotak">
          <img src="data:image/png;base64,' . $data[foto] . '">
            <div id="nml">'.$data[nama_lengkap].' 
                <a href="#"><i class="fa fa-pencil" title="Edit Profile"></i></a>
             </div>
              <small><b>Bergabung Pada</b> '.$data[bergabung_tgl].' </small>
          <div class="fkotak">
            <i class="fa fa-star"></i> Level : '.$data[level].' 
            <a href="proses.php?act=logout">
              <i class="fa fa-sign-out" title="Logout" id="signout"></i>
            </a> 
          </div>
        </div>
        <div class="col animated fadeInUp delay-07s" id="inkotak">
            <ul>
              <li><i class="fa fa-rss"></i>'.$data[email].'</li>
              <li><i class="fa fa-phone"></i>'.$data[tlp].'</li>
              <li><i class="fa fa-globe"></i>'.$data[alamat].'</li>
            </ul>
        </div>';
      ?>