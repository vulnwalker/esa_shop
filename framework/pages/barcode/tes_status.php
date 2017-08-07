<?php
$uid = $_GET['id'];
$aqry = "update barcode_status_print set status=1, qty=1 where uid='$uid'";
$qry= mysql_query($aqry);

?>