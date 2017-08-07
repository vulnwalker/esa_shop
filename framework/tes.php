<?php
echo 'tes' .'<br>';
echo (2<4) .'<br>';
echo '<br>' ;

$tgl1 = '1900-01-03' ;
$tgl2 = '1800-12-31' ;


$hsl=-1;

if ($tgl1 < $tgl2){
	$hsl = 0; //lebih kecil
}else if ($tgl1 == $tgl2){
	$hsl = 1; //sama dengan
}else {
	$hsl = 2;  //lebih besar
}

echo "$tgl1  $tgl2 = " ;

echo $hsl

?>