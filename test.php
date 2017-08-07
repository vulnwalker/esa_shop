<?php
$baseurl = "http://127.0.0.8/framework";
include('framework/config.php');
echo "<script language='JavaScript' src='$baseurl/lib/js/JSCookMenu_mini.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/lib/js/ThemeOffice/theme.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/lib/js/joomla.javascript.js' type='text/javascript'></script>
	<script src='$baseurl/js/jquery.js' type='text/javascript'></script>	
	<script language='JavaScript' src='$baseurl/js/ajaxc2.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/dialog/dialog.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/js/global.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/js/base.js' type='text/javascript'></script>
	<script language='JavaScript' src='$baseurl/js/encoder.js' type='text/javascript'></script>	
	<script language='JavaScript' src='$baseurl/lib/chatx/chatx.js' type='text/javascript'></script>
	<script src='$baseurl/js/daftarobj.js' type='text/javascript'></script>
	<script src='$baseurl/js/pageobj.js' type='text/javascript'></script>	";
echo "<script type='text/javascript' src='$baseurl/js/aksi/aksi.js'></script>";


echo "<button onclick='aksi.hubla();'> hubla </button>";

$combobox = cmbQuery('cmbBidang',$c,$codeAndNameBidang,'onchange=rka.refreshList(true);','-- BIDANG --');
echo $combobox;
$array = mysql_fetch_array(mysql_query('select * from user'));
print_r($array);

?>