<?php
//V2 -----------------
//error_reporting(0);
include("../../common/vars.php");
include("../../config.php");

$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"";
$objID = isset($HTTP_GET_VARS['objID'])?$HTTP_GET_VARS['objID']:"";
$objNM = isset($HTTP_GET_VARS['objNM'])?$HTTP_GET_VARS['objNM']:"";
 
$Cari1 = isset($HTTP_GET_VARS['Cari1'])?$HTTP_GET_VARS['Cari1']:"";
$Qry = mysql_query("select * from ref_barang where nm_barang like '$Cari1%' and j <> '00' and j <> '000'  order by f,g,h,i,j limit 0,100 ");
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry)){
	$nmF = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='00' and h='00' and i='00' and (j='00' or j='000')"));
	$nmG = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='00' and i='00' and (j='00' or j='000')"));
	$nmH = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='00' and (j='00' or j='000')"));
	$nmI = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='{$isi['i']}' and (j='00' or j='000')"));
	$no++;
	$Isi1 = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	$Isi2 = $isi['nm_barang'];
	$List.= "
		<tr>
		<td>$no. </td>
		<td>$Isi1</td>
		<td><a href='#' cursor='hand' onClick=\"KlikGunakan('$Isi1','$Isi2')\">$Isi2 </a></td>
		<td>{$nmF[0]}</td>
		<td>{$nmG[0]}</td>
		<td>{$nmH[0]}</td>
		<td>{$nmI[0]}</td>
		</tr>";
}

$Main->Isi = "
<HTML>
<HEAD>
	<link rel=\"stylesheet\" href=\"../../css/template_css.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"../../css/theme.css\" type=\"text/css\" /> 
<script>
	function tutup()
	{
		parent.document.all.cariiframe$objID.style.visibility='hidden';
	}
	function KlikGunakan(isi1,isi2)
	{
		parent.document.adminForm.$objID.value=isi1;
		parent.document.adminForm.$objNM.value=isi2;
		parent.document.all.cariiframe$objID.style.visibility='hidden';
		parent.tampilkanKIB();

	}
</script>
</HEAD>
<BODY>
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"searchtext\">Pencarian Nama Barang</th>
		<td align=\"right\"><input align=right type=submit value=Tutup onClick=\"tutup()\"></td>
	</tr>
</table>
<form method=get action='?Cari=$Cari' class=\"adminform\">
<input type=hidden name='objID' value='$objID'>
<input type=hidden name='objNM' value='$objNM'>
Tuliskan kata kunci pencarian&nbsp;&nbsp;&nbsp;<input type=text value='$Cari1' name='Cari1'><input type=submit value='Cari'> <br>Ditemukan <font color=red>$numRow</font> data yang sesuai dengan kata kunci <b>$Cari1</b>
</form>
<table width=\"100%\" height=\"100%\">
	<tr>
		<td vAlign=top>
			<table align=center class=\"adminlist\">	
				<tr>
					<td colspan=7 align=\"center\">
					Maksimal ditampilkan 100 data. Klik pada nama rekening untuk digunakan
					</td>
				</tr>
				<tr>
					<th class=\"title\">No.</th>
					<th class=\"title\">Kode Barang</th><th>Nama Barang</th>
					<th class=\"title\">Bidang</th>
					<th class=\"title\">Kelompok</th>
					<th class=\"title\">Sub Kelompok</th>
					<th class=\"title\">Sub Sub Kelompok</th>
				</tr>
				$List
			</table>
</td></tr>
</table>
</BODY>
</HTML>
";
echo $Main->Isi;
?>
