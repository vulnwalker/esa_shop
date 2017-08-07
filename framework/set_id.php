<?php
$status = 0;
$qty = 0;

$uid = $_GET['id'];

$aqry = "select * from barcode_status_print where uid='$uid'";
$qry = mysql_query($aqry);
while ($isi = mysql_fetch_array($qry)){
	$status = $isi['status'];
	$qty = $isi['qty'];
}



header('Content-type: application/xml');
echo "<data>";
echo "<status>".$status."</status>";
//echo "<qty>".$qty."</qty>";
echo "</data>";

?>