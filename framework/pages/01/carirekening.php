<?php
$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"xxxxx";
$Qry = mysql_query("select * from ref_rekening where nm_rekening like '%$Cari%' and o <> '00' limit 100");
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$no++;
	$Isi1 = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
	$Isi2 = $isi['nm_rekening'];
	$List.= "<tr><td>$no. </td><td>$Isi1</td><td><a href='#' cursor='hand' onClick=\"KlikGunakan('$Isi1','$Isi2')\">$Isi2 </a></td></tr>";
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
	<table width=50% align=center cellpadding=0 cellspacing=0 border=1 >
	<tr>
	<th colspan=3>
	Hasil Pencarian dengan kata kunci <b>$Cari</B> terdapat <font color=red>$numRow</font> data<br>ditampilkan 100 data, klik pada nama rekening untuk digunakan
	</th>
	</tr>
	<tr><th>No.</th><th>Kode Barang</th><th>Nama Rekening</th></tr>
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