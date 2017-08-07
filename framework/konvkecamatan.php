<?php
include 'config.php';
echo 'Konversi Alamat kecamatan ke kode kecamatan<br>';
$sqry = "select * from ref_kotakec where kd_kota=14";
$qry=mysql_query($sqry);
$no = 1;
while ($isi = mysql_fetch_array($qry)){


$kondisi=" and (alamat_kec like '%".$isi['nm_wilayah']."%' or alamat like '%kec. ".$isi['nm_wilayah']."%' )";

$uqry = "update kib_a set alamat_c='".$isi['kd_kec']."' where alamat_b=14 $kondisi";

$qry1=mysql_query($uqry);
$uqry = "update kib_c set alamat_c='".$isi['kd_kec']."' where kd_kota=14 $kondisi";
$qry1=mysql_query($uqry);
$uqry = "update kib_d set alamat_c='".$isi['kd_kec']."' where kd_kota=14 $kondisi";
$qry1=mysql_query($uqry);
$uqry = "update kib_f set alamat_c='".$isi['kd_kec']."' where kd_kota=14 $kondisi";
$qry1=mysql_query($uqry);

echo $uqry."<br>";

}
?>