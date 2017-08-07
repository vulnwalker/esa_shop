<?php

//Kabupaten/Kota
$Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
$Ops = "";
while($isi=mysql_fetch_array($Qry))
{
	$sel = $fmWIL == $isi['b'] ? "selected":"";
	$Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
}
$ListKab = "<select name='fmWIL'  onChange=\"adminForm.submit()\"><option value=''>--- Pilih Kabupaten/Kota ---</option>$Ops</select>";

//SKPD
$Qry = mysql_query("select * from ref_skpd where d='00' order by nm_skpd");
$Ops = "";
while($isi=mysql_fetch_array($Qry))
{
	$sel = $fmSKPD == $isi['c'] ? "selected":"";
	$Ops .= "<option $sel value='{$isi['c']}'>{$isi['nm_skpd']}</option>\n";
}
$ListSKPD = "<select name='fmSKPD' onChange=\"adminForm.submit()\"><option value=''>--- Pilih SKPD ---</option>$Ops</select>";

$Main->Isi = "


<A Name=\"ISIAN\"></A>
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Data Penetapan Status Penggunaan Barang Milik Daerah</th>
</tr>
</table>

<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr>
	<td>PROVINSI</td>
		<td>:</td>
		<td><b>{$Main->Provinsi[1]}</b></td>
	</tr>
	<tr>
		<td>KAB/KOTA</td>
		<td>:</td>
		<td>$ListKab</td>
	</tr>
	<tr>
		<td>SKPD</td>
		<td>:</td>
		<td>$ListSKPD</td>
	</tr>
	<tr><td colspan='3'><hr></td></tr>
	<tr>
		<td>Tahun Anggaran</td>
		<td>:</td>
		<td><input size='4'></td>
	</tr>
	<tr>
		<td>Keputusan Gubernur</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
		<td>:</td>
		<td><input size='2'>-<input size='2'>-<input size='5'></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr valign=\"top\">   
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	<i>pencarian data barang</i>
	<input type=text name=CariBarang value='$CariBarang'>&nbsp;<input type=button value='Referensi' onClick=\"popUpCari('?Pg=01&SPg=caribarang&Cari='+adminForm.CariBarang.value+'',adminForm.fmIDBARANG,adminForm.fmNMBARANG)\">&nbsp;<input type='button' value='Pengeluaran'><br>
	<input type=text name='fmIDBARANG' value='$fmIDBARANG' size='15' readonly>
	<input type=text name='fmNMBARANG' value='$fmNMBARANG' size='60' readonly>
	
	</td>
	</tr>
	
	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Merk / Type / Ukuran </td>
	<td width=\"33\">:</td>
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
	</tr>

		<tr>
			<td>Nomor Seri Pabrik</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>Ukuran</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>Bahan</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>Tahun Perolehan</td>
			<td>:</td>
			<td><input size='6'></td>
		</tr>
		<tr>
			<td>Jumlah Barang</td>
			<td>:</td>
			<td><input size='6'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Satuan : <input></td>
		</tr>
		<tr>
			<td>Keadaan Barang</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Baik</td>
			<td>:</td>
			<td><input size='6'></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Kurang Baik</td>
			<td>:</td>
			<td><input size='6'></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td>:</td>
			<td><textarea cols='60'></textarea></td>
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