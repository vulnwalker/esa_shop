<?php
include("../../config.php");
$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"";
$objID = isset($HTTP_GET_VARS['objID'])?$HTTP_GET_VARS['objID']:"";
$objNM = isset($HTTP_GET_VARS['objNM'])?$HTTP_GET_VARS['objNM']:"";
 
$Cari1 = isset($HTTP_GET_VARS['Cari1'])?$HTTP_GET_VARS['Cari1']:"";


$akun = $_GET["akun"]; //echo '<br> bidang='.$fmBIDANG;
$kelompok = $_GET["kelompok"];
$jenis = $_GET["jenis"];
$objek = $_GET["objek"];
$rincian = $_GET["rincian"];


$ListAkun = cmbQuery("akun",$akun,
	"select k, nm_rekening from ref_rekening 
	where k<>'0' and l='0' and m='0' and n='00' and o='00'",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);

$ListKelompok = //"select l, nm_rekening from ref_rekening 	where k='$akun' and l<>'0' and m='0' and n='00' and o='00'".
	cmbQuery("kelompok",$kelompok,	"select l, nm_rekening from ref_rekening 
	where k='$akun' and l<>'0' and m='0' and n='00' and o='00'",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListJenis = cmbQuery("jenis",$jenis,
	"select m, nm_rekening from ref_rekening 
	where k='$akun' and l='$kelompok' and m<>'0' and n='00' and o='00'",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListObjek = cmbQuery("objek",$objek,
	"select n, nm_rekening from ref_rekening 
	where k='$akun' and l='$kelompok' and m='$jenis' and n<>'00' and o='00'",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListRincian = cmbQuery("rincian",$rincian,
	"select o, nm_rekening from ref_rekening 
	where k='$akun' and l='$kelompok' and m='$jenis' and n='$objek' and o<>'00'",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);


if ($akun==''){	$akun ='00';}
if ($kelompok==''){	$kelompok ='00';}
if ($jenis==''){	$jenis ='00';}
if ($objek==''){	$objek ='00';}
if (!(($akun == '' ||$akun =='00') && ($kelompok == '' ||$kelompok =='00') 
 	&& ($jenis == '' ||$jenis =='00') && ($objek == '' ||$objek =='00')) ){
	
	if ($akun != 00 ){ $Kondisi .= " and k ='$akun'";}
	if ($kelompok != 00 ){ $Kondisi .= " and l ='$kelompok'";}
	if ($jenis != 00 ){ $Kondisi .= " and m ='$jenis'";}
	if ($objek != 00 ){ $Kondisi .= " and n ='$objek'";}

}

//$Qry = mysql_query("select * from ref_rekening where nm_rekening like '%$Cari1%' and o <> '00' limit 100");
$Qry = mysql_query("select * from ref_rekening where nm_rekening like '%$Cari1%' $Kondisi ");
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$no++;
	$Isi1 = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
	$Isi2 = $isi['nm_rekening'];
	$List.= "
		<tr>
			<td>$no. </td>
			<td>$Isi1</td>
			<td><a href='#' cursor='hand' onClick=\"KlikGunakan('$Isi1','$Isi2')\">$Isi2 </a></td>
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
		tutup();
	}
</script>
</HEAD>
<BODY>
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"searchtext\">Pencarian Nama Rekening</th>
		<td align=\"right\"><input align=right type=submit value=Tutup onClick=\"tutup()\"></td>
	</tr>
</table>
<form method=get action='?Cari=$Cari' class=\"adminform\" id='adminForm' name='adminForm'>

<table width=\"100%\"  class=\"adminform\">
		<tr>
			<td WIDTH='10%'>AKUN BELANJA</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListAkun</td>
		</tr>
		<tr>
			<td WIDTH='10%'>KELOMPOK BELANJA</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListKelompok</td>
		</tr>
		<tr>
			<td WIDTH='10%'>JENIS BELANJA</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListJenis</td>
		</tr>
		<tr>
			<td WIDTH='10%'>OBJEK BELANJA</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListObjek</td>
		</tr>
		<!--<TR>
			<td WIDTH='10%'>RINCIAN BELANJA</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListRincian</td>
		</tr>-->
		<tr>
			<td>NAMA REKENING</td>
			<td>:</td>
			<td>
				<input type=text value='$Cari1' name='Cari1'><input type=submit value='Tampilkan'>
			</td>
		</tr>
</table>
<input type=hidden name='objID' value='$objID'>
<input type=hidden name='objNM' value='$objNM'>
<!--Tuliskan kata kunci pencarian&nbsp;&nbsp;&nbsp;<input type=text value='$Cari1' name='Cari1'><input type=submit value='Cari'> -->
<br>Ditemukan <font color=red>$numRow</font> data yang sesuai dengan kata kunci <b>$Cari1</b>
</form>
<table width=\"100%\" height=\"100%\">
<tr><td vAlign=top>
	<table align=\"center\" class=\"adminlist\">
	<tr>
	<td colspan=3 align=\"center\">
	<!--Maksimal ditampilkan 100 data. Klik pada nama rekening untuk digunakan-->
	</td>
	</tr>
	<tr><th align=\"left\" width='40'>No.</th><th align=\"left\" width='100'>Kode Barang</th><th align=\"left\" >Nama Rekening</th></tr>
	$List
	</table>
</td></tr>
</table>
</BODY>
</HTML>
";
echo $Main->Isi;
?>