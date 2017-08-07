<?php
error_reporting(0);

include("../../config.php");
include("../../common/vars.php");
include("../../common/fungsi.php");

$Cari2 = cekGET("Cari2","");
$Cari = cekPOST("Cari",$Cari2);
$objID = cekGET("objID","");
$objNM = cekGET("objNM","");
$nm_akun = cekPOST("nm_akun","");


$Qry = mysql_query("select * from ref_jurnal where concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) = '$Cari'");
$nm_akun = "";
if(!mysql_num_rows($Qry))
{
	if(!empty($Cari))
	{
		echo "
			<script>
				//alert('Tidak ada nama rekening dengan kode $Cari');
				//parent.document.adminForm.$objID.focus();
				//parent.document.adminForm.$objID.select();
		
			</script>
				";
	}
	//echo "<script>alert('Tidak ada nama barang dengan kode $Cari')</script>";
}
else
{

	$isi = mysql_fetch_array($Qry);
	$nm_akun = $isi['nm_account'];
	//echo "<script>alert('aa $Cari bb');</script>";
}
echo "
<body onLoad=\"parent.document.adminForm.$objNM.value = document.all.formI.nm_akun.value;\">
<form name='formI' method=POST action='?objID=$objID&objNM=$objNM' >
<input type='text' name='Cari' value='$Cari'>
<input type='text' name='nm_akun' value='$nm_akun'>
<input type=submit value=cari>
 </form>
 </body>
 ";
 
?>
