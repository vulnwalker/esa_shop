<?php
error_reporting(0);
include("../../common/vars.php");
include("../../config.php");

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


$kdSubsubkel0 = genNumber(0, $Main->SUBSUBKEL_DIGIT );

$c = isset($HTTP_GET_VARS['c'])?$HTTP_GET_VARS['c']:"";//$_REQUEST['c']; 
$d = isset($HTTP_GET_VARS['d'])?$HTTP_GET_VARS['d']:"";//$_REQUEST['d'];
$e = isset($HTTP_GET_VARS['e'])?$HTTP_GET_VARS['e']:"";//$_REQUEST['e'];
$e1 = isset($HTTP_GET_VARS['e1'])?$HTTP_GET_VARS['e1']:"";//$_REQUEST['e'];
$thn=  isset($HTTP_GET_VARS['thn'])?$HTTP_GET_VARS['thn']:""; $cek .= "thn= $thn";
//$thn=  $_REQUEST['thn']; $cek .= "thn= $thn";

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

$Kondisi = " where nm_barang like '%$Cari1%' and j <> '$kdSubsubkel0' ";
//and status_barang<>3 and status_barang<>4 and status_barang<>5 " ;


//BIDANG
if ($Act == 'Edit') {
	$kdbid = substr($kdBrgOld, 0, 2);
	$nmbid = table_get_value("select nm_barang from ref_barang where f='".$kdbid."' and g ='00' and h = '00' and i='00' and j='$kdSubsubkel0'", "nm_barang");
	$cek .= '<br> nm bid = '.$nmbid;
	$fmBIDANG = $kdbid;
	$ListBidang = '
		<input type="hidden" name ="fmBIDANG" value="'.$fmBIDANG.'">
		<input type="text" readonly="" value="'.$nmbid.'" style="width:400">';
}else{
	$ListBidang = cmbQuery("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');	
}

$ListKelompok = cmbQuery("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubKelompok = cmbQuery("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubSubKelompok = cmbQuery("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');

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
if ($thn!='') $Kondisi .= " and tahun='$thn'";
//$Kondisi = '';

//list ---------------------------------------------------------------
switch($fmBIDANG){
	case '01': $tblkib = 'view_kib_a'; break;
	case '03': $tblkib = 'view_kib_c'; break;
	case '04': $tblkib = 'view_kib_d'; break;
	case '06': $tblkib = 'view_kib_f'; break;
}

//$sqry = "select * from ref_barang ".$Kondisi." order by f,g,h,i,j limit 0,200 ";
$sqry = "select * from v1_dkpb ".$Kondisi." order by f,g,h,i,j limit 0,200 "; $cek .= $sqry;
$cek .= '<br> sqry='.$sqry;
$Qry = mysql_query($sqry);
$numRow = mysql_num_rows($Qry);
// echo $sqry;
$List = "";
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$nmF = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='00' and h='00' and i='00' and j='$kdSubsubkel0'"));
	$nmG = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='00' and i='00' and j='$kdSubsubkel0'"));
	$nmH = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='00' and j='$kdSubsubkel0'"));
	$nmI = mysql_fetch_array(mysql_query("select nm_barang from ref_barang where f='{$isi['f']}' and g='{$isi['g']}' and h='{$isi['h']}' and i='{$isi['i']}' and j='$kdSubsubkel0'"));
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
		$kec= $alm['alamat_kec'];
		$kota= $alm['alamat_kota'];
		$isilokasi = $alm['alamat'].'<br>Kel. '.$alm['alamat_kel'].' <br>Kec. '.$alm['alamat_kec']. '<br>'.$alm['alamat_kota'];		
		$alamat = $alm['alamat'];
		$lokasi = "<td align='left' >".$isilokasi."</td>";	
		
	}

	$List.= 
		"<tr>
		<td>$no. </td>
		<td>$Isi1</td>		
		<td><a href='#' cursor='hand' onClick=\"KlikGunakan('".
			$isi['id']."','$Isi1','$Isi2','".$isi['merk_barang']."','".$isi['jml_barang']."','".$isi['satuan'].
			"','".$isi['harga']."','".$isi['tahun']."','".$isi['thn_perolehan']."','".$isi['noreg']."' )\">$Isi2 </a></td>".
		"<td align='left'>".$isi['noreg']."</td>".
		"<td align='center'>".$isi['thn_perolehan']."</td>".
		"<td align='center'>".$isi['tahun']."</td>".
		 
		"<td align='left'>".$Main->arJnsPelihara[ $isi['jns_pelihara'] -1][1]."</td>".
		"<td align='left'>".$isi['uraian']."</td>".
		"<td align='right'>".number_format($isi['jml_barang'],0, ',' , '.' )."</td>
		<td align='right'>".number_format($isi['harga'],2, ',' , '.' )."</td>		
		<td align='right'>".number_format($isi['jml_harga'],2, ',' , '.' )."</td>
		<td align='left'>".$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'] .'<br>'.
			$isi['nm_rekening']."</td>
		
		<td align='left'>".$isi['ket']."</td>
		</tr>";
}

/*
$lokasihead = '';
if ($fmBIDANG=='01' || $fmBIDANG=='03' || $fmBIDANG=='04' || $fmBIDANG=='06' ){
	$lokasihead = "<th align='left' width='250'>Alamat</th>";
}
*/

$cek = '';
$Main->Isi = $cek."
<HTML>
<HEAD>
	<link rel=\"stylesheet\" href=\"../../css/template_css.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"../../css/theme.css\" type=\"text/css\" /> 
	<script src='../../js/jquery.js' type='text/javascript'></script>
<script>
	function tutup(){
		parent.document.all.cariiframe$objID.style.visibility='hidden';
		parent.document.body.style.overflow='auto';
	}
	function KlikGunakan(idbi,isi1,isi2, merk, jml, satuan, harga,tahun, thnperolehan, noreg){
		/***************************
		* idbi = id dkpb yg terpilih
		*************************/
		parent.document.adminForm.$objIDBI.value=idbi; 
		parent.document.adminForm.$objID.value=isi1; //id barang
		parent.document.adminForm.$objNM.value=isi2; //nm barang		
		parent.document.getElementById('fmTAHUN').value= tahun;
		parent.document.getElementById('thn_perolehan').value= thnperolehan;
		parent.document.getElementById('noreg').value= noreg;
		
		parent.document.getElementById('satuan').value= satuan;
		parent.document.getElementById('harga').value= harga;
		
		
		var c = parent.document.getElementById('c').value;
		var d = parent.document.getElementById('d').value;
		var e = parent.document.getElementById('e').value;
		var e1 = parent.document.getElementById('e1').value;
		var id = parent.document.getElementById('dppb_idplh').value;
		
		//alert('../../pages.php?Pg=dpb&ajx=1&tipe=hitPengadaanSebelumnya&tahun='+tahun+'&c='+c+'&d='+d+'&e='+e+'&e1='+e1+'&kdbrg='+isi1+'&id='+id);
		$.ajax({
			
			url: '../../pages.php?Pg=dppb&ajx=1&tipe=hitPengadaanSebelumnya&tahun='+tahun+
				'&c='+c+'&d='+d+'&e='+e+'&e1='+e1+'&kdbrg='+isi1+'&id='+id+'&ref_iddkpb='+idbi
				,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');							
				if(resp.err==''){
					//alert('c='+c+'d='+d+'e='+e+ 'jml='+resp.content.jml+' dkpb='+resp.content.jmldkb+ ' jmlada='+resp.content.jmlada+' cek'+resp.cek);
					parent.document.getElementById('jml_ada').value = resp.content.jmlada;	
					
					if( parent.document.getElementById('jml_barang').value =='') parent.document.getElementById('jml_barang').value=0;
					var jml = parent.document.getElementById('jml_barang').value;
					
					parent.document.getElementById('jml_dkb').value= resp.content.jmldkb;
					var jmlsisa = parseInt(resp.content.jmldkb) - (  parseInt(resp.content.jmlada) +  parseInt(jml) );
					
					parent.document.getElementById('jml_sisa').value= jmlsisa;
					
				}else{
					
				}
				
		  	}
		});
		
		tutup();
	}
	
</script>
</HEAD>
<BODY>
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"searchtext\">Pencarian Data Kebutuhan Pemeliharaan Barang Milik Daerah </th>
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
		<tr style='height:4'>
			<td colspan=3 ></td>
			
		</tr>		
		<tr style='height:21'>
			<td WIDTH='10%'>TAHUN ANGGARAN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$thn</td>
		</tr>
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
		<tr style='height:4'>
			<td colspan=3 ></td>
			
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
<input type=hidden name='thn' value='$thn'>

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
			<table align=center class=\"adminlist\" border =1>	
				<tr>
					<td colspan=13 align=\"center\">
					Ditemukan <font color=red>$numRow</font> data barang yang sesuai dengan kata kunci <b>$Cari1</b>. Pilih nama barang yang akan diinput.
					</td>
				</tr>
				<tr>
					<th align='center' width='40'>No.</th>
					<th align='center' width='100'>Kode Barang</th>
					<th align='center' width='100'>Nama Barang</th>
					<th align='center' width='40'>No. Register</th>	
					<th align='center' width='40'>Tahun Perolehan</th>
									
					
					<th align='center' width='60'>Tahun Anggaran</th>														
					<th align='center' width='40'>Jenis Pemeliharaan</th>	
					<th align='center' width='40'>Uraian Pemeliharaan</th>	
					<th align='center' >Jumlah Barang</th>
					
					<th align='center' >Harga Satuan (Rp)</th>
					<th align='center' >Jumlah Harga (Rp)</th>
					<th align='center' >Kode Rekening</th>
					<th align='center' >Ket</th>
					
				</tr>
				$List
			</table>
</td></tr>
</table>
</BODY>
</HTML>
";


echo $Main->Isi;
?>
