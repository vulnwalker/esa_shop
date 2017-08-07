<?php
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();


$Main->Isi = "
<A Name=\"ISIAN\"></A>
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">

	<table class=\"adminheading\">
		<tr>
			<th height=\"47\" class=\"user\">Buku Inventaris Barang</th>
		</tr>
	</table>

	".WilSKPD()."
<BR>

	<table width=\"100%\" height=\"100%\">
		<tr valign=\"top\">
			<td class=\"contentheading\">
				<DIV ALIGN=CENTER>DAFTAR INVENTARIS BARANG</DIV>
			</td>
		</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
		<tr valign=\"top\">    
			<td width=10% >Nama Barang</td>
			<td width=1% >:</td>
			<td>
			<input type=text name='fmBARANGCARI' value='$fmBARANGCARI'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
			</td>
		</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH>No</TD>
		<TH><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlData,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH>Nama Barang</TH>
		<TH>Merk/Type/Ukuran/Spesifikasi</TH>
		<TH>Jumlah</TH>
		<TH>Harga Satuan (Rp)</TH>
		<TH>Jumlah Harga</TH>
		<TH>No/Tgl SPK/Perjanjian/Kontrak</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarang
	</TABLE>

<br>

	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>-->
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Edit';adminForm.submit()","edit_f2.png","Ubah")."</td>
			<td>".PanelIcon1("javascript:if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){adminForm.Act.value='Hapus';adminForm.submit();}","delete_f2.png","Hapus")."</td>
			<td>".PanelIcon1("javascript:adminForm.Act.value='Cetak';adminForm.submit()","print_f2.png","Cetak")."</td>
			</tr></table>
		</td></tr>
	</table>

<!-END DPBT-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

<br>
<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr valign=\"top\">
			<td width=\"184\" height=\"29\">Nama Gudang</td>
			<td width=\"33\">:</td>
			<td width=\"804\">".cmbQuery('fmIDGUDANG',$fmIDGUDANG,"select id_gudang,nm_gudang from ref_gudang where concat(c,d,e)='$fmSKPD$fmUNIT$fmSUBUNIT'",'')."</td>
		</tr>
		<tr valign=\"top\">   
			<td>Nama Barang</td>
			<td>:</td>
			<td>
			".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG")."
			</td>
		</tr>
		<tr valign=\"top\">
			<td width=\"184\" height=\"29\">Merk / Type / Ukuran / Spesifikasi</td>
			<td width=\"33\">:</td>
			<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
		</tr>
		<tr valign=\"top\">
			<td>Jumlah Barang </td>
			<td>:</td>
			<td><input name=\"fmJUMLAH\" type=\"text\" value=\"$fmJUMLAH\" />
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Satuan&nbsp;&nbsp;
			<input name=\"fmSATUAN\" type=\"text\" size=\"10\" value=\"$fmSATUAN\" />
			</td>
		</tr>
		<tr valign=\"top\">
		    <td>Harga Satuan </td>
		    <td>:</td>
		    <td>Rp. 
			<input type=\"text\" name=\"fmHARGASATUAN\" value=\"$fmHARGASATUAN\" /></td>
		</tr>
	<!--

	<tr valign=\"top\">
	  <td>Dipergunakan pada : </td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr valign=\"top\">
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;Unit</td>
	  <td>:</td>
		<td>
		".txtField('fmUNITGUNAKAN',$fmUNITGUNAKAN,'100','20','text','')."
		</td>
	</tr>
	<tr valign=\"top\">
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;Sub Unit</td>
	  <td>:</td>
	  <td>
		".txtField('fmSUBUNITGUNAKAN',$fmSUBUNITGUNAKAN,'100','20','text','')."
		</td>
	</tr>
	-->

	<tr valign=\"top\">
	  <td>Jenis Barang </td>
	  <td>:</td>
	  <td>".cmb2D('fmJENISBARANG',$fmJENISBARANG,$Main->JenisBarang,'')."</td>
	</tr>

	<tr valign=\"top\">   
	<td>Tanggal diterima</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALDITERIMA")."
	</td>
	</tr>

	<tr valign=\"top\">
	  <td>PT/CV</td>
	  <td>:</td>
	  <td>
		".txtField('fmPTCV',$fmPTCV,'100','20','text','')."
		</td>
	</tr>

	<tr valign=\"top\">
	  <td>Dokumen Faktur</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALFAKTUR")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORFAKTUR',$fmNOMORFAKTUR,'100','20','text','')."
	</td>
	</tr>

	<tr valign=\"top\">
	  <td>Berita Acara Pemeriksaan</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALPEMERIKSAAN")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORPEMERIKSAAN',$fmNOMORPEMERIKSAAN,'100','20','text','')."
	</td>
	</tr>


	<tr valign=\"top\">
	  <td>Keterangan</td>
	  <td>:</td>
	  <td><textarea name=\"fmKET\" cols=\"60\" >$fmKET</textarea></td>
	</tr>
	</table>

<br>
	<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"50\"><tr>
			<td>
			".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
			</td>
			<td>
			".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."
			</td>
			</tr></table>
	</td></tr>
	</table>
";
$Tampil = $Main->Isi;
echo $Tampil;
?>