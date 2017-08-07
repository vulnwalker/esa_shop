<?php

//cek status printer, param = id user

$status = 0;
$qty = 0;

$uid = $_GET['id'];

$aqry = "select * from barcode_status_print where uid='$uid'";
$qry = mysql_query($aqry);
while ($isi = mysql_fetch_array($qry)){
	$status = $isi['status'];
	$qty = $isi['qty'];
}

/*$domtree = new DOMDocument('1.0', 'UTF-8');
$xmlRoot = $domtree->createElement("xml");
$xmlRoot = $domtree->appendChild($xmlRoot);

$recTag = $xmlRoot->appendChild( $domtree->createElement("status", $status));
$recTag = $xmlRoot->appendChild( $domtree->createElement("qty", $qty));


echo $domtree->saveXML();*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Content-type: application/xml');
echo "<data>";
echo "<status>".$status."</status>";
//echo "<qty>".$qty."</qty>";
echo "</data>";

?>