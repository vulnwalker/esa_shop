<?php

$Main->Isi = "


<A Name=\"ISIAN\"></A>
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Data Tuntutan Ganti Rugi</th>
</tr>
</table>

<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">

<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr>
		<td>Cari Data Inventaris</td>
		<td>:</td>
		<td><input>&nbsp;&nbsp;&nbsp;<input type='button' value='cari'></td>
	</tr>
	<tr>
		<td>Kode Lokasi</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr>
		<td>Kode Barang</td>
		<td>:</td>
		<td><input></td>
	</tr>

	<tr><td colspan='3'><hr></td></tr>

	<tr>
		<td>Tahun</td>
		<td>:</td>
		<td><input size='6'></td>
	</tr>
	<tr>
		<td>Keterangan</td>
		<td>:</td>
		<td><textarea cols='40'></textarea></td>
	</tr>
	
</table>

</td>
</tr>
</table>


<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr><td class=\"menudottedline\">
			<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
			<td class=\"menudottedline\" height=\"52\" align=right>
			".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."
			</td>
			<td class=\"menudottedline\" height=\"52\">
			".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
			</td>
			<td align=right  class=\"menudottedline\" >
			".PanelIcon1("?Pg=$Pg","cancel_f2.png","Tutup")."
			</td>
			</tr>
			</table>
	</td></tr>
</table>
";
?>