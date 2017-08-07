<?php

$uid = $_GET['id'];
/*$status= $_GET['status'];
$qty = $_GET['qty'];
if ($status==0) {
	$aqry = "update barcode_status_print set status=0, qty=0 where uid='$uid'";
	$qry= mysql_query($aqry);
	if ($uid !='admin'){
		$aqry = "delete from barcode_data_print where uid='$uid'";
		$qry= mysql_query($aqry);	
	}
	
	
}else {
	$aqry = "update barcode_status_print set status='$status', qty='$qty' where uid='$uid'";
	$qry= mysql_query($aqry);
}
*/

$aqry = "update barcode_status_print set status=0, qty=0 where uid='$uid'";
$qry= mysql_query($aqry);
/*

if ($uid !='admin'){
	$aqry = "delete from barcode_data_print where uid='$uid'";
	$qry= mysql_query($aqry);	
}
*/

if($qry==TRUE){ 
	$status = 1;
}else{
	$status = 0;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Content-type: application/xml');
echo "<data>";
echo "<status>".$status."</status>";
echo "</data>";




?>