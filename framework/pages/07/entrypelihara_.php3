<?php

$Main->Isi = "


<A Name=\"ISIAN\"></A>
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Data Pemeliharaan Barang</th>
</tr>
</table>

<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">

<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	
	<tr valign=\"top\">   
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	<i>pencarian barang</i>
	<input type=text name=CariBarang value='$CariBarang'><input type=button value='Cari' onClick=\"popUpCari('?Pg=01&SPg=caribarang&Cari='+adminForm.CariBarang.value+'',adminForm.fmIDBARANG,adminForm.fmNMBARANG)\"><br>
	<input type=text name='fmIDBARANG' value='$fmIDBARANG' size='15' readonly>
	<input type=text name='fmNMBARANG' value='$fmNMBARANG' size='60' readonly>
	<input type='button' value='.....'>
	</td>
	</tr>
	
	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Merk / Type / Ukuran </td>
	<td width=\"33\">:</td>
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
	</tr>
	<tr>
		<td>Kode Lokasi</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr><td colspan='3'><hr></td></tr>

	<tr>
		<td>Tanggal Pemeliharaan</td>
		<td>:</td>
		<td><input size='2'>-<input size='2'>-<input size='5'></td>
	</tr>
	<tr>
		<td>Jenis Pemeliharaan</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr>
		<td>Yang Memelihara</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr>
		<td>Biaya Pemeliharaan</td>
		<td>:</td>
		<td>Rp. <input></td>
	</tr>
	<tr>
		<td>Bukti Pemeliharaan</td>
		<td>:</td>
		<td><input></td>
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