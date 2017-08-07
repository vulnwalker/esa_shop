<?php

include("../../config.php");
include("../../common/vars.php");
include("../../common/fungsi.php");

$Cari2 = cekGET("Cari2","");
$Cari = cekPOST("Cari",$Cari2);
$objID = cekGET("objID","");
$objNM = cekGET("objNM","");

$Qry = mysql_query("select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j) = '$Cari'");
$nm_barang = "";
if(!mysql_num_rows($Qry))
{
	if(!empty($Cari))
	{
		echo "<script>
				//alert('Tidak ada nama barang dengan kode $Cari');
				parent.document.adminForm.$objID.focus();
				parent.document.adminForm.$objID.select();
			</script>";
	}
	//echo "<script>alert('Tidak ada nama barang dengan kode $Cari')</script>";
}
else
{
	$isi = mysql_fetch_array($Qry);
	$nm_barang = $isi['nm_barang'];
}
echo "
<body onLoad=\"parent.document.adminForm.$objNM.value=document.all.formI.nmbarang.value\">
<form name='formI' method=POST action='?objID=$objID&objNM=$objNM'>
<input type='text' name='Cari' value='$Cari'>
<input type='text' name='nmbarang' value='$nm_barang'>
<input type=submit value=cari>
 </form>
 </body>
 ";
 
?>
