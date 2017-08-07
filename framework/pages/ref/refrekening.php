<?php


$fmBIDANG = cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");

//BIDANG
$ListBidang = cmbQuery("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListKelompok = cmbQuery("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubKelompok = cmbQuery("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubSubKelompok = cmbQuery("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih','');

$Kondisi = "concat(f,g,h,i)='$fmBIDANG$fmKELOMPOK$fmSUBKELOMPOK$fmSUBSUBKELOMPOK'";
$Qry = mysql_query("select * from ref_barang where $Kondisi and j != '000' order by f,g,h,i,j");
$ListDATA = "";
$no=0;
while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$KODEBARANG = "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}";
	$NAMABARANG = $isi["nm_barang"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><div align='left'>$KODEBARANG</div></td>
				<td><div align='left'>$NAMABARANG</div></td>
		</tr>";

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Rekening </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>BIDANG</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListBidang</td>
		</tr>
		<tr>
		<td WIDTH='10%'>KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListKelompok</td>
		</tr>
		<td WIDTH='10%'>SUB KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubKelompok</td>
		</tr>
		<td WIDTH='10%'>SUB SUB KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubSubKelompok</td>
		</tr>
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<th width='10%' class=\"title\"><div align=left>Kode Rekening</div></th>
				<th width='86%' class=\"title\"><div align=left>Nama Rekening</div></th>
			</tr>
			$ListDATA
		</table>
	</td>
</tr>
</table>
<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr><td class=\"menudottedline\">
			<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
			<td align=right  class=\"menudottedline\" >
			".PanelIcon1("?Pg=$Pg","cancel_f2.png","Tutup")."
			</td>
			</tr>
			</table>
	</td></tr>
</table>
</td></tr></table>
</form>



";
?>