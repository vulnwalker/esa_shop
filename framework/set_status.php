<?php

$uid = $_GET['id'];
$status= $_GET['status'];
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

if ($qry==TRUE){
	
}


?>