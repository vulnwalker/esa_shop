<?php session_start();

date_default_timezone_set('Asia/Jakarta');

function anti_injection($data)
{
    $filter = mysqli_real_escape_string(koneksi(), stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}

function koneksi() {
    return mysqli_connect("localhost", "root", "", "db_online_shop");
}

function query($query) {
    return mysqli_query(koneksi(), $query);
}

function fetch($query)
{
    return mysqli_fetch_assoc($query);
}

function numrows($datanya) {
  return mysqli_num_rows($datanya);
}


function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+','<p>');

    $s = str_replace($d, '', $s); 
    
    $s = str_replace($c, '-', $s); 
    return $s;
}

function rupiah($nilai, $pecahan = 0) {
  return number_format($nilai, $pecahan, ',', '.');
}

function insert($table, $data)
{
    $query = "INSERT INTO $table VALUES($data)";
    if (query($query)) {
        echo'<script languange="javascript"> 
               alert("Data Berhasil disimpan");
               history.go(-2);
            </script>';
    } else {
        echo'<script languange="javascript"> 
               alert("Data gagal disimpan");
               history.go(-1);
            </script>';
    }
}

function hapus($table, $where = null) {
   $where = (isset($where) && !empty($where)) ? ' WHERE ' . $where : null;
   $query = query("DELETE FROM $table $where");
   if($query){
    echo'<script languange="javascript"> 
       alert("Data Berhasil Dihapus");
       history.go(-1);
    </script>';
   }else{
    echo'<script> 
       alert("Data Gagal Dihapus");
       history.go(-1);
    </script>';
  }
}

function encode($img) {
  $data = base64_encode(file_get_contents($img));
  return $data;
} 

function base64($data) {
  return base64_encode($data);
}

function exten($path) {
  $extensi = pathinfo($path, PATHINFO_EXTENSION);
  return $extensi;
} 

function alertsimpan($simpan) {
  if($simpan){
    echo'<script languange="javascript"> 
       alert("Data Berhasil Disimpan");
       history.go(-2);
    </script>';
   }else{
    echo'<script> 
       alert("Data Gagal Di Simpan");
       history.go(-1);
    </script>';
  }
}

function alertupdate($update) {
  if($update){
    echo'<script languange="javascript"> 
       alert("Data Berhasil Di Ubah");
       history.go(-2);
    </script>';
   }else{
    echo'<script> 
       alert("Data Gagal Di Ubah");
       history.go(-1);
    </script>';
  }
}

foreach ($_POST as $key => $value) { 
  $$key = anti_injection($value); 
}

foreach ($_GET as $key => $value) { 
  $$key = $value; 
}


$nama_bulan   = array('','January','February','Maret','April','Mei','Juni','Juli','Agustus','September',' Oktober','November','Desember');

// Variable Global
 //Alert

$sukses = '   <script languange="javascript"> alert("Data berhasil dihapus");
                history.go(-1);
              </script>';

$gagal = '    <script languange="javascript"> alert("Data gagal dihapus");
                history.go(-1);
              </script>';

$noupdate = ' <script languange="javascript">
                history.go(-1);
              </script>';             

$index = '    <script languange="javascript">
          document.location="index.php";
          </script>';

$cart = '     <script languange="javascript">
          document.location="cart.php";
          </script>';

$noadd = '    <script languange="javascript"> alert("Pilih Minimal 1 item");
                history.go(-1);
              </script>';         

//lainnya  
   

$tglnow = date ("Y-m-d"); 
$tglindo = date("d-m-Y");
$tglsekarang  = date ("Y-m-d");

$gambar       = $_FILES['gambar']['tmp_name'];

$extensi      = $_FILES['gambar']['name'];

$notformat    = ' <script languange="javascript"> alert("Format Tidak Didukung!");
                   history.go(-1);
                  </script>';

$suksesprof   = ' <script languange="javascript"> alert("Data profile berhasildi ubah");
                   history.go(-1);
                  </script>';                                           

$cekharga     = ' <script languange="javascript"> alert("Cek deui Wa hargana Sien salah !!");
                    history.go(-1);
                  </script>';

$sukseshapus  = ' <script languange="javascript"> alert("Data Berhasil Di Hapus");
                    history.go(-1);
                  </script>';

$gagalhapus   = ' <script languange="javascript"> alert("Data Gagal di Hapus");
                    history.go(-1);
                  </script>';                 

$defaultimg   = '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';

?>