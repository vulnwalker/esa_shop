<?php
session_start();
$subdir = '';
$_SESSION['base_url'] = "http://".$_SERVER['SERVER_NAME']."/".$subdir;
$_SESSION['base_path'] = $_SERVER['DOCUMENT_ROOT']."/".$subdir;
function anti_injection($data)
{
    $filter = mysqli_real_escape_string(koneksi(), stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}

function GET_FILE($url)
{
    $base = fopen($url, 'rb');
    $file = fread($base, filesize($url));
    fclose($base);
    return $file;
}

function koneksi()
{
    return mysqli_connect("localhost", "root", "12345", "db_atsb_demo_atis_v2");
}

function query($query_simpan)
{
    return mysqli_query(koneksi(), $query_simpan);
}

function fetch($query)
{
    return mysqli_fetch_assoc($query);
}
//select (tabel, where)
function select($table, $where = null, $select = '*', $order = null, $groupby = null)
{
    // ternari operato => seperti if "?"
    $where   = (isset($where) && !empty($where)) ? ' WHERE ' . $where : null;
    $order   = (isset($order) && !empty($order)) ? ' ORDER BY ' . $order : null;
    $groupby = (isset($groupby) && !empty($groupby)) ? ' GROUP BY ' . $groupby : null;
    $q       = query("SELECT $select FROM $table $where $order $groupby");
    if ($q) {
        $row = array();
        while ($tampil = fetch($q)) {
            //array push digunakan untuk mengisi array
            array_push($row, $tampil);
        }
        return $row;
    }
    return false;
}

function select_inner($table, $inner, $select = '*', $order = null, $where = null, $limit = null)
{
    $order   = (isset($order) && !empty($order) && $order != '') ? ' ORDER BY ' . $order : null;
    $where   = (isset($where) && !empty($where) && $where != '') ? ' where ' . $where : null;
    $limit   = (isset($limit) && !empty($limit) && $limit != '') ? ' LIMIT ' . $limit : null;
    $inner   = (isset($inner) && !empty($inner) && $inner != '') ? ' ' . $inner : null;
    $q       = query("SELECT $select FROM $table $inner $where $order desc $limit ");
    if ($q) {
        $row = array();
        while ($tampil = fetch($q)) {
            //array push digunakan untuk mengisi array
            array_push($row, $tampil);
        }
        return $row;
    }
    return false;
}

function insert($table, $data)
{
    if (is_array($data)) {
        // ini buat array
        $key   = array_keys($data);
        $kolom = implode(',', $key);
        $v     = array();
        for ($i = 0; $i < count($data); $i++) {
            array_push($v, "'" . $data[$key[$i]] . "'");
        }
        $values = implode(',', $v);
        $query  = "INSERT INTO $table ($kolom) VALUES ($values)";
    } else {
        $query = "INSERT INTO $table $data";
    }
    
    if (query($query)) {
        return true;
    } else {
        return false;
    }
}

function update($table, $data, $where)
{
    if (is_array($data)) {
        // ini buat array
        $key   = array_keys($data);
        $kolom = implode(',', $key);
        $v     = array();
        for ($i = 0; $i < count($data); $i++) {
            array_push($v, $key[$i] . " = '" . $data[$key[$i]] . "'");
        }
        $values = implode(',', $v);
        $query  = "UPDATE $table SET $values WHERE $where";
    } else {
        $query = "UPDATE $table SET $data WHERE $where";
    }
    
    if (query($query)) {
        return true;
    } else {
        return false;
    }
}

function hapus($table, $where = null)
{
    // ternari operato => seperti if "?"
    $where = (isset($where) && !empty($where)) ? ' WHERE ' . $where : null;
    $query = query("DELETE FROM $table $where");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function karakter_limit($karakter, $panjang)
{
    if (strlen($karakter) <= $panjang) {
        return $karakter;
    } else {
        $string = substr($karakter, 0, $panjang);
        return $string . '...';
    }
}

function cari_posisi($halaman, $batas) {

    if (empty($halaman)) {
        $posisi = 0;
    } else {
        $posisi = ($halaman - 1) * $batas;
    }

    return $posisi;
}

//menambahkan value ke database
function add_array($v, $a) {
    
    $array = explode(',', $a);
    
    //cek apakah value berbentuk array
    if (!in_array($v, $array)) {
        array_push($array, $v);
    }
    return implode(',', $array);
}

//cek value apakah ada di dalam array
function check_in_array($v, $a) {
    
    $array = explode(',', $a);
    
    //cek apakah value berbentuk array
    if (in_array($v, $array)) {
        return 'ada';
    } 
    else {
        return 'kosong';
    }
}
function tanggal($date) { 
    $BulanIndo    = array("Januari", "Februari", "Maret","April", "Mei", "Juni","Juli", "Agustus", "September","Oktober", "November", "Desember");
    $tahun        = substr($date, 0, 4); 
    $bulan        = substr($date, 5, 2); 
    $tgl          = substr($date, 8, 2); 
    $result       = $tgl." ".$BulanIndo[(int)$bulan-1]." ".$tahun;
    return($result);
}

function hari($date) { 
    $NamaHari     = array("Senin", "Selasa", "Rabu","Kamis", "Jumat", "Sabtu","Minggu");
    $result       = $NamaHari[(int)$date-1];
    return($result);
}
function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+','<p>');

    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    
    $s = str_replace($c, '-', $s); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}

function tutup_koneksi()
{
    return mysqli_close(mysqli_connect("localhost", "root", "12345", "db_atsb_demo_atis_v2"));
}
?>
