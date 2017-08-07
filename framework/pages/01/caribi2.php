<?php
error_reporting(0);
include("../../config.php");
include("../../common/vars.php");
//include("../../common/fnfile.php");

$Cari = isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"";
$objID = isset($HTTP_GET_VARS['objID'])?$HTTP_GET_VARS['objID']:"";$cek .= '<br> objid='.$objID;
$objNM = isset($HTTP_GET_VARS['objNM'])?$HTTP_GET_VARS['objNM']:"";
$objIDBI = isset($HTTP_GET_VARS['objIDBI'])?$HTTP_GET_VARS['objIDBI']:"";
$objTHN = isset($HTTP_GET_VARS['objTHN'])?$HTTP_GET_VARS['objTHN']:"";
$objNOREG = isset($HTTP_GET_VARS['objNOREG'])?$HTTP_GET_VARS['objNOREG']:"";
$objLOKASI = isset($HTTP_GET_VARS['objLOKASI'])?$HTTP_GET_VARS['objLOKASI']:"";

$Act = $_GET['Act']; $cek .= '<br> act ='.$Act;
$kdBrgOld = $_GET['kdBrgOld']; $cek .= '<br> id barang old ='.$kdBrgOld;
$fmBIDANG = $_GET["fmBIDANG"];$cek .= '<br> bidang='.$fmBIDANG;
$fmKELOMPOK = $_GET["fmKELOMPOK"];
$fmSUBKELOMPOK = $_GET["fmSUBKELOMPOK"];
$fmSUBSUBKELOMPOK = $_GET["fmSUBSUBKELOMPOK"];
$c = isset($HTTP_GET_VARS['c'])?$HTTP_GET_VARS['c']:"";//$_REQUEST['c']; 
$d = isset($HTTP_GET_VARS['d'])?$HTTP_GET_VARS['d']:"";//$_REQUEST['d'];
$e = isset($HTTP_GET_VARS['e'])?$HTTP_GET_VARS['e']:"";//$_REQUEST['e']; 
$e1 = isset($HTTP_GET_VARS['e1'])?$HTTP_GET_VARS['e1']:"";//$_REQUEST['e']; 

$oldf = $_GET['oldf'];
$oldg = $_GET['oldg'];
$oldh = $_GET['oldh'];
$oldi = $_GET['oldi'];

if ($oldf != $fmBIDANG) {
	$fmKELOMPOK ='00'; $fmSUBKELOMPOK = '00'; $fmSUBSUBKELOMPOK = '00';
}
if ($oldg != $fmKELOMPOK) {	
	$fmSUBKELOMPOK = '00'; $fmSUBSUBKELOMPOK = '00';
}
if ($oldh != $fmSUBKELOMPOK) {	
	$fmSUBSUBKELOMPOK = '00';
}

 //kondisi ---------------------------------------
 
$Cari1 = isset($HTTP_GET_VARS['Cari1'])?$HTTP_GET_VARS['Cari1']:"";
$cek .= '<br> cari1='.$Cari1;

$Kondisi = " where nm_barang like '%$Cari1%' and j <> '00' and status_barang<>3 and status_barang<>4 and status_barang<>5 " ;


//BIDANG
if ($Act == 'Edit') {
	$kdbid = substr($kdBrgOld, 0, 2);
	$nmbid = table_get_value("select nm_barang from ref_barang where f='".$kdbid."' and g ='00' and h = '00' and i='00' and (j='00' or j='000')", "nm_barang");
	$cek .= '<br> nm bid = '.$nmbid;
	$fmBIDANG = $kdbid;
	$ListBidang = '
		<input type="hidden" name ="fmBIDANG" value="'.$fmBIDANG.'">
		<input type="text" readonly="" value="'.$nmbid.'" style="width:400">';
}else{
	$ListBidang = cmbQuery("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and (j='00' or j='000')","onChange=\"adminForm.submit()\"",'Pilih','');	
}

$ListKelompok = cmbQuery("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and (j='00' or j='000')","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubKelompok = cmbQuery("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and (j='00' or j='000')","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubSubKelompok = cmbQuery("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and (j='00' or j='000')","onChange=\"adminForm.submit()\"",'Pilih','');

if ($fmBIDANG==''){	$fmBIDANG ='00';}
if ($fmKELOMPOK==''){	$fmKELOMPOK ='00';}
if ($fmSUBKELOMPOK==''){	$fmSUBKELOMPOK ='00';}
if ($fmSUBSUBKELOMPOK==''){	$fmSUBSUBKELOMPOK ='00';}
if (!(($fmBIDANG == '' ||$fmBIDANG =='00') && ($fmKELOMPOK == '' ||$fmKELOMPOK =='00') 
 	&& ($fmSUBKELOMPOK == '' ||$fmSUBKELOMPOK =='00') && ($fmSUBSUBKELOMPOK == '' ||$fmSUBSUBKELOMPOK =='00')) ){
	
	if ($fmBIDANG != 00 ){ $Kondisi .= " and f ='$fmBIDANG'";}
	if ($fmKELOMPOK != 00 ){ $Kondisi .= " and g ='$fmKELOMPOK'";}
	if ($fmSUBKELOMPOK != 00 ){ $Kondisi .= " and h ='$fmSUBKELOMPOK'";}
	if ($fmSUBSUBKELOMPOK != 00 ){ $Kondisi .= " and i ='$fmSUBSUBKELOMPOK'";}

}

if ($c != '') $Kondisi .= " and c='$c'";
if ($d != '') $Kondisi .= " and d='$d'";
if ($e != '') $Kondisi .= " and e='$e'";
if ($e1 != '') $Kondisi .= " and e1='$e1'";
//$Kondisi = '';

//list ---------------------------------------------------------------
switch($fmBIDANG){
	case '01': $tblkib = 'view_kib_a'; break;
	case '03': $tblkib = 'view_kib_c'; break;
	case '04': $tblkib = 'view_kib_d'; break;
	case '06': $tblkib = 'view_kib_f'; break;
}
//$sqry = "select * from ref_barang ".$Kondisi." order by f,g,h,i,j limit 0,200 ";
$sqry = "select * from view_buku_induk ".$Kondisi." order by f,g,h,i,j limit 0,200 "; $cek .= $sqry;
$cek .= '<br> sqry='.$sqry;
$Qry = mysql_query($sqry);
$numRow = mysql_num_rows($Qry);
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$nmF = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='00' and h='00' and i='00' and (j='00' or j='000') "));
	$nmG = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='00' and i='00' and (j='00' or j='000')"));
	$nmH = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='00' (j='00' or j='000')"));
	$nmI = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='{$isi['i']}' and (j='00' or j='000')"));
	$no++;
	$Isi1 = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	$Isi2 = $isi['nm_barang'];
	
	$isilokasi=''; $lokasi = ''; $almt=''; $kel=''; $kec=''; $kota='';	
	//if ($isi['f']=='01' || $isi['f']=='03' || $isi['f']=='04' || $isi['f']=='06' ){
	if ($fmBIDANG=='01' || $fmBIDANG=='03' || $fmBIDANG=='04' || $fmBIDANG=='06' ){
		$alm = mysql_fetch_array(mysql_query(
			"select * from $tblkib where idbi='".$isi['id']."' "
		));
		
		$kel= $alm['alamat_kel'];
		$kec= $alm['alamat_kec1']!=''?$alm['alamat_kec1']:$alm['alamat_kec'];
		$kota= $alm['alamat_kota']!=''?$alm['alamat_kota']:$alm['kota'];
		$isilokasi = $alm['alamat'].'<br>Kel. '.$alm['alamat_kel'].' <br>Kec. '.$kec. '<br>'.$kota;		
		$alamat = $alm['alamat'];
		$lokasi = "<td align='left' >".$isilokasi."</td>";	
		
	}
	
	$List.= "
		<tr>
		<td>$no. </td>
		<td>$Isi1</td>
		<td align='center'>".$isi['noreg']."</td>
		<td><a href='#' cursor='hand' onClick=\"KlikGunakan('".
			$isi['id']."','$Isi1','$Isi2','".$isi['thn_perolehan']."','".$isi['noreg']."','".
			$alamat."','$kel','$kec','$kota')\">$Isi2 </a></td>
		<td align='center'>".$isi['thn_perolehan']."</td>
		<td align='right'>".number_format($isi['jml_harga'],2, ',' , '.' )."</td>
		<!--<td align='center'>".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>-->
		<td align='center'>".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>
		$lokasi
		</tr>";
}


$lokasihead = '';
if ($fmBIDANG=='01' || $fmBIDANG=='03' || $fmBIDANG=='04' || $fmBIDANG=='06' ){
	$lokasihead = "<th align='left' width='250'>Alamat</th>";
}

$cek = '';
$Main->Isi = $cek."
<HTML>
<HEAD>
	<link rel=\"stylesheet\" href=\"../../css/template_css.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"../../css/theme.css\" type=\"text/css\" /> 
<script>
	function tutup()
	{
		parent.document.all.cariiframe$objID.style.visibility='hidden';
	}
	function KlikGunakan(idbi,isi1,isi2, thn, noreg, alm,kel,kec,kota)
	{
		parent.document.adminForm.$objIDBI.value=idbi;
		parent.document.adminForm.$objID.value=isi1;
		parent.document.adminForm.$objNM.value=isi2;
		parent.document.adminForm.$objTHN.value=thn;
		parent.document.adminForm.$objNOREG.value=noreg;
		if (alm!=''){
			parent.document.adminForm.$objLOKASI.innerHTML= alm+'&#13;&#10;Kel. '+kel+'&#13;&#10;Kec. '+kec+'&#13;&#10;'+kota;
		}else{
			parent.document.adminForm.$objLOKASI.innerHTML= '';
		}
		tutup();
	}
</script>
</HEAD>
<BODY>
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"searchtext\">Pencarian Buku Inventaris Barang </th>
		<td align=\"right\"><input align=right type=submit value=Tutup onClick=\"tutup()\"></td>
	</tr>
</table>

<form name=\"adminForm\" id=\"adminForm\"  method=get action='?Cari=$Cari&fmBIDANG=$fmBIDANG&fmKELOMPOK=$fmKELOMPOK&fmSUBKELOMPOK=$fmSUBKELOMPOK&fmSUBSUBKELOMPOK=$fmSUBSUBKELOMPOK' class=\"adminform\">
<!--<form name=\"adminForm\" id=\"adminForm\"  method=\"post\" action='?Cari=$Cari' class=\"adminform\">-->
<!--Kel barang-->

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


<input type=hidden name='kdBrgOld' value='$kdBrgOld'>
<input type=hidden name='Act' value='$Act'>
<input type=hidden name='objID' value='$objID'>
<input type=hidden name='objNM' value='$objNM'>
<input type=hidden name='objIDBI' value='$objIDBI'>
<input type=hidden name='objNOREG' value='$objNOREG'>
<input type=hidden name='objTHN' value='$objTHN'>
<input type=hidden name='objLOKASI' value='$objLOKASI'>

<input type=hidden name='c' value='$c'>
<input type=hidden name='d' value='$d'>
<input type=hidden name='e' value='$e'>
<input type=hidden name='e1' value='$e1'>

<input type=hidden name='oldf' value='$fmBIDANG'>
<input type=hidden name='oldg' value='$fmKELOMPOK'>
<input type=hidden name='oldh' value='$fmSUBKELOMPOK'>
<input type=hidden name='oldi' value='$fmSUBSUBKELOMPOK'>

Ketik nama barang /jenis barang yang akan dicari&nbsp;&nbsp;&nbsp;<input type=text value='$Cari1' name='Cari1' style='width:200px'><input type=submit value='Cari'> 
<br>Maksimal ditampilkan 200 data.
</form>
<table width=\"100%\" height=\"100%\">
	<tr>
		<td vAlign=top>
			<table align=center class=\"adminlist\">	
				<tr>
					<td colspan=9 align=\"center\">
					Ditemukan <font color=red>$numRow</font> data barang yang sesuai dengan kata kunci <b>$Cari1</b>. Pilih nama barang yang akan diinput.
					</td>
				</tr>
				<tr>
					<th align='center' width='40'>No.</th>
					<th align='center' width='100'>Kode Barang</th>
					<th align='center' width='60'>No. Register</th>
					<th align='left' width='200'>Nama Barang</th>
					<th align='center' width='40'>Tahun Perolehan</th>
					<th align='center' width='150'>Harga Perolehan</th>
					<!--<th align='center' >Asal Usul</th>-->
					<th align='center' >Kondisi</th>
					$lokasihead
				</tr>
				$List
			</table>
</td></tr>
</table>
</BODY>
</HTML>
";

//$cek = '';
echo $Main->Isi;
?>
