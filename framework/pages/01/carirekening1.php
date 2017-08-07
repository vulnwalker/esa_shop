<?php
error_reporting(0);

include("../../config.php");
include("../../common/vars.php");
include("../../common/fungsi.php");

$Cari2 = cekGET("Cari2","");
$Cari = cekPOST("Cari",$Cari2);
$objID = cekGET("objID","");
$objNM = cekGET("objNM","");
$nm_rekening = cekPOST("nm_rekening","");


$Qry = mysql_query("select * from ref_rekening where concat(k,'.',l,'.',m,'.',n,'.',o) = '$Cari'");
$nm_rekening = "";
if(!mysql_num_rows($Qry))
{
	if(!empty($Cari))
	{
		echo "
			<script>
				//alert('Tidak ada nama rekening dengan kode $Cari');
				parent.document.adminForm.$objID.focus();
				parent.document.adminForm.$objID.select();
		
			</script>
				";
	}
	//echo "<script>alert('Tidak ada nama barang dengan kode $Cari')</script>";
}
else
{

	$isi = mysql_fetch_array($Qry);
	$nm_rekening = $isi['nm_rekening'];
	//echo "<script>alert('aa $Cari bb');</script>";
}
echo "
<body onLoad=\"parent.document.adminForm.$objNM.value = document.all.formI.nm_rekening.value;\">
<form name='formI' method=POST action='?objID=$objID&objNM=$objNM' >
<input type='text' name='Cari' value='$Cari'>
<input type='text' name='nm_rekening' value='$nm_rekening'>
<input type=submit value=cari>
 </form>
 </body>
 ";
 
?>
