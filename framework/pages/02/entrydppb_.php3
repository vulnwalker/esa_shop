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
<form name=\"myForm\" id=\"myForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Data Pengadaan Pemeliharaan Barang</th>
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
	<tr>
		<td colspan='3'><hr></td>
	</tr>
	<tr valign=\"top\">   
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	<i>pencarian barang dari data DKPB</i>
	<input type=text name=CariBarang value='$CariBarang'><input type=button value='Cari' onClick=\"popUpCari('?Pg=01&SPg=caribarang&Cari='+adminForm.CariBarang.value+'',adminForm.fmIDBARANG,adminForm.fmNMBARANG)\"><br>
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
		<td>Kode Lokasi</td>
		<td>:</td>
		<td><input></td>
	</tr>
	<tr>
		<td>Uraian Pemeliharaan</td>
		<td>:</td>
		<td><textarea cols='60'></textarea></td>
	</tr>
	<tr>
			<td>Jumlah Barang</td>
			<td>:</td>
			<td><input> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Satuan : <input></td>
		</tr>
		<tr>
			<td>Harga Satuan (Kontrak)</td>
			<td>:</td>
			<td>Rp. <input></td>
		</tr>
		<tr>
			<td>Jumlah Harga</td>
			<td>:</td>
			<td>Rp. <input></td>
		</tr>
		<tr>
			<td>SPK/Perjanjian/Kontrak</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
			<td>:</td>
			<td>".InputKalender($NAMA="fmTGLSERTIFIKAT_KIB_A")."</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;PT/CV</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>DPA/SPM/Kwitansi</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
			<td>:</td>
			<td><input></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td>:</td>
			<td><textarea cols='60'></textarea></td>
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