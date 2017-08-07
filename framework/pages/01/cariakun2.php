<?php
include("../../config.php");
include("../../common/fnport.php");

$cek = '';
$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"";
$objID = isset($HTTP_GET_VARS['objID'])?$HTTP_GET_VARS['objID']:"";
$objNM = isset($HTTP_GET_VARS['objNM'])?$HTTP_GET_VARS['objNM']:"";
 
$Cari1 = isset($HTTP_GET_VARS['Cari1'])?$HTTP_GET_VARS['Cari1']:"";
$Cari2 = isset($HTTP_GET_VARS['Cari2'])?$HTTP_GET_VARS['Cari2']:"";

$bm=$Main->KODE_BELANJA_MODAL;

$akun = $bm[0]; //echo '<br> bidang='.$fmBIDANG;
$kelompok = $bm[2];
$jenis = $_GET["jenis"];
$objek = $_GET["objek"];
$rincian = $_GET["rincian"];
$subrincian = $_GET["subrincian"];
$cek .= '$Main->KODE_BELANJA_MODAL='.$Main->KODE_BELANJA_MODAL.' $Main->host_port='.$Main->host_port.' $akun='.$akun.' $kelompok='.$kelompok;
if($Main->WITH_THN_ANGGARAN){
	$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
	$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
			thn_akun<='$fmThnAnggaran' ;"; $cek .= $aqry;
			$qry1=mysql_query($aqry1);			
			$qry_jurnal=mysql_fetch_array($qry1);
			$thn_akun=$qry_jurnal['thnMax'];
			//$arrKondisi[] = " thn_akun = '$thn_akun'";														
	$vthnakun = " and thn_akun=$thn_akun ";
}	

$ListAkun = cmbQuery("akun",$akun,
	"select ka, nm_account from ref_jurnal 
	where ka<>'0' and kb='0' and kc='0' and kd='00' and ke='00' and kf='00' $vthnakun",
	"onChange=\"adminForm.submit()\" disabled",'Pilih',''
); $cek .= "select ka, nm_account from ref_jurnal where ka<>'0' and kb='0' and kc='0' and kd='00' and ke='00' and kf='00' $vthnakun ;";

$ListKelompok = //"select l, nm_rekening from ref_rekening 	where k='$akun' and l<>'0' and m='0' and n='00' and o='00'".
	cmbQuery("kelompok",$kelompok,	"select kb, nm_account from ref_jurnal 
	where ka='$akun' and kb<>'0' and kc='0' and kd='00' and ke='00' and kf='00' $vthnakun",
	"onChange=\"adminForm.submit()\" disabled",'Pilih',''
);
$ListJenis = cmbQuery("jenis",$jenis,
	"select kc, nm_account from ref_jurnal 
	where ka='$akun' and kb='$kelompok' and kc<>'0' and kd='00' and ke='00' and kf='00' $vthnakun",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListObjek = cmbQuery("objek",$objek,
	"select kd, nm_account from ref_jurnal 
	where ka='$akun' and kb='$kelompok' and kc='$jenis' and kd<>'00' and ke='00' and kf='00' $vthnakun",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListRincian = cmbQuery("rincian",$rincian,
	"select ke, nm_account from ref_jurnal 
	where ka='$akun' and kb='$kelompok' and kc='$jenis' and kd='$objek' and ke<>'00' and kf='00' $vthnakun",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);
$ListSubRincian = cmbQuery("subrincian",$subrincian,
	"select kf, nm_account from ref_jurnal 
	where ka='$akun' and kb='$kelompok' and kc='$jenis' and kd='$objek' and ke='$rincian' and kf<>'00' $vthnakun",
	"onChange=\"adminForm.submit()\"",'Pilih',''
);


if ($akun==''){	$akun ='00';}
if ($kelompok==''){	$kelompok ='00';}
if ($jenis==''){	$jenis ='00';}
if ($objek==''){	$objek ='00';}
if ($rincian==''){	$rincian ='00';}
if ($subrincian==''){	$subrincian ='00';}
if (!(($akun == '' ||$akun =='00') && ($kelompok == '' ||$kelompok =='00') 
 	&& ($jenis == '' ||$jenis =='00') && ($objek == '' ||$objek =='00')
 	&& ($rincian == '' ||$rincian =='00') && ($subrincian == '' ||$subrincian =='00')) ){
	
	if ($akun != 00 ){ $Kondisi .= " and ka ='$akun'";}
	if ($kelompok != 00 ){ $Kondisi .= " and kb ='$kelompok'";}
	if ($jenis != 00 ){ $Kondisi .= " and kc ='$jenis'";}
	if ($objek != 00 ){ $Kondisi .= " and kd ='$objek'";}
	if ($rincian != 00 ){ $Kondisi .= " and ke ='$rincian'";}
	if ($subrincian != 00 ){ $Kondisi .= " and kf ='$subrincian'";}

}

//$Qry = mysql_query("select * from ref_rekening where nm_rekening like '%$Cari1%' and o <> '00' limit 100");
$Qry = mysql_query("select * from ref_jurnal where concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) like '%$Cari2%' and nm_account like '%$Cari1%' $Kondisi $vthnakun and ke != '00' limit 0,200");
//echo"select * from ref_jurnal where concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) like '%$Cari2%' and nm_account like '%$Cari1%' $Kondisi and ke != '00' limit 0,200";
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$no++;
	$Isi1 = $isi['ka'].".".$isi['kb'].".".$isi['kc'].".".$isi['kd'].".".$isi['ke'].".".$isi['kf'];
	$Isi2 = $isi['nm_account'];
	
	$row=mysql_fetch_array(mysql_query("select * from ref_barang where 
										m1='".$isi['ka']."' and m2='".$isi['kb']."' and 
										m3='".$isi['kc']."' and m4='".$isi['kd']."' and 
										m5='".$isi['ke']."' and m6='".$isi['kf']."'"));
	$Isi3 = $row['f'].".".$row['g'].".".$row['h'].".".$row['i'].".".$row['j'];
	$Isi4 = $row['nm_barang'];
	
	$List.= "
		<tr>
			<td>$no. </td>
			<td>$Isi1</td>
			<td><a href='#' cursor='hand' onClick=\"KlikGunakan('$Isi1','$Isi2','$Isi3','$Isi4')\">$Isi2 </a></td>
		</tr>";
}

if($Main->SHOW_CEK==FALSE) $cek = '';

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
	function KlikGunakan(isi1,isi2,isi3,isi4)
	{
		parent.document.adminForm.$objID.value=isi1;
		parent.document.adminForm.$objNM.value=isi2;
		if(isi3 != '' && isi3 != '....'){
			parent.document.adminForm.fmIDBARANG.value=isi3;
			parent.document.adminForm.fmNMBARANG.value=isi4;
		}
		tutup();
	}
</script>
</HEAD>
<BODY>
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"searchtext\">Pencarian Nama Akun</th>
		<td align=\"right\"><input align=right type=submit value=Tutup onClick=\"tutup()\"></td>
	</tr>
</table>
<form method=get action='?Cari=$Cari' class=\"adminform\" id='adminForm' name='adminForm'>
<div style='display:none'> $cek</div>
<table width=\"100%\"  class=\"adminform\">
		<tr>
			<td WIDTH='10%'>AKUN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListAkun</td>
		</tr>
		<tr>
			<td WIDTH='10%'>KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListKelompok</td>
		</tr>
		<tr>
			<td WIDTH='10%'>JENIS</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListJenis</td>
		</tr>
		<tr>
			<td WIDTH='10%'>OBJEK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListObjek</td>
		</tr>
		<tr>
			<td WIDTH='10%'>RINCIAN OBJEK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListRincian</td>
		</tr>
		<tr>
			<td WIDTH='10%'>SUB RINCIAN OBJEK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubRincian</td>
		</tr>
		
</table>
<table width=\"100%\"  class=\"adminform\" border=0>
		<tr>
			<td WIDTH='10%'>KODE AKUN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='20%'>
				<input type=text value='$Cari2' name='Cari2'><input type=submit value='Cari'>
				
			</td >
			<td WIDTH='10%'>NAMA AKUN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='58%'>
				<input type=text value='$Cari1' name='Cari1'><input type=submit value='Cari'>
				
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
	<tr><th align=\"left\" width='40'>No.</th><th align=\"left\" width='100'>Kode Akun</th><th align=\"left\" >Nama Akun</th></tr>
	$List
	</table>
</td></tr>
</table>
</BODY>
</HTML>
";
echo $Main->Isi;
?>