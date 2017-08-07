<?php
include '../../func/fungsi.php';
$propinsi = $_GET['propinsi'];
$kota = query("SELECT *FROM kabkot WHERE id_prov='$propinsi' order by nama_kabkot");
echo '<option value="" selected disabled> -- Pilih Kabupaten / Kota -- </option>';
while($k = mysql_fetch_array($kota)){
     echo "<option value=\"".$k['id_kabkot']."\">".$k['nama_kabkot']."</option>\n";
} 
?>
