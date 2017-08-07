<?php
$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"xxxxx";
$Qry = mysql_query("select * from ref_barang where nm_barang like '$Cari%' and j <> '00' and j <> '000' order by f,g,h,i,j limit 0,100 ");
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$nmF = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='00' and h='00' and i='00' and (j='00' or j='000')"));
	$nmG = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='00' and i='00' and (j='00' or j='000')"));
	$nmH = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='00' and (j='00' or j='000')"));
	$nmI = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='{$isi['i']}' and (j='00' or j='000')"));
	$no++;
	$Isi1 = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	$Isi2 = $isi['nm_barang'];
	$List.= "<tr><td>$no. </td>
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
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"css/theme.css\" type=\"text/css\" />
<script>
	MyObjek = window.dialogArguments;
	function KlikGunakan(isi1,isi2)
	{
		MyObjek.IDNYA = isi1;
		MyObjek.NAMANYA = isi2;
		window.close();
	}
</script>
</HEAD>
<BODY>
<table width=\"100%\" height=\"100%\" class=\"adminform\">
<tr><td vAlign=top>
	<table width=100% align=center cellpadding=0 cellspacing=0 border=1 >
	<tr>
	<th colspan=7>
	Hasil Pencarian dengan kata kunci  <b>$Cari</B> terdapat <font color=red>$numRow</font> data<br>ditampilkan 100 data, klik pada nama barang untuk digunakan
	</th>
	</tr>
	<tr>
		<th>No.</th>
		<th>Kode Barang</th><th>Nama Barang</th>
		<th>Bidang</th>
		<th>Kelompok</th>
		<th>Sub Kelompok</th>
		<th>Sub Sub Kelompok</th>
	</tr>
	$List
	</table>
</td></tr>
</table>
</BODY>
</HTML>
";
echo $Main->Isi;
exit;
?>