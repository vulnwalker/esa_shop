<?php
error_reporting(0);
include("../../config.php");
include("../../common/vars.php");

$Cari 		= isset($HTTP_GET_VARS['Cari'])?$HTTP_GET_VARS['Cari']:"";
$objID 		= isset($HTTP_GET_VARS['objID'])?$HTTP_GET_VARS['objID']:"";$cek .= '<br> objid='.$objID;
$objNM 		= isset($HTTP_GET_VARS['objNM'])?$HTTP_GET_VARS['objNM']:"";
$objIDBI 	= isset($HTTP_GET_VARS['objIDBI'])?$HTTP_GET_VARS['objIDBI']:"";
$objTHN 	= isset($HTTP_GET_VARS['objTHN'])?$HTTP_GET_VARS['objTHN']:"";
$objNOREG 	= isset($HTTP_GET_VARS['objNOREG'])?$HTTP_GET_VARS['objNOREG']:"";
$objLOKASI 	= isset($HTTP_GET_VARS['objLOKASI'])?$HTTP_GET_VARS['objLOKASI']:"";

$Act 		= $_GET['Act']; $cek .= '<br> act ='.$Act;
$kdBrgOld 	= $_GET['kdBrgOld']; $cek .= '<br> id barang old ='.$kdBrgOld;
$fmBIDANG 	= $_GET["fmBIDANG"];$cek .= '<br> bidang='.$fmBIDANG;
$fmKELOMPOK	= $_GET["fmKELOMPOK"];
$fmSUBKELOMPOK = $_GET["fmSUBKELOMPOK"];
$fmSUBSUBKELOMPOK = $_GET["fmSUBSUBKELOMPOK"];
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

$kdSubsubkel0 = genNumber(0, $Main->SUBSUBKEL_DIGIT );
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
$sqry = "select * from v1_penerimaan ".$Kondisi." order by f,g,h,i,j limit 0,200 "; $cek .= $sqry;
$cek .= '<br> sqry='.$sqry;
$Qry = mysql_query($sqry);
$numRow = mysql_num_rows($Qry);
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
		<td class='GarisDaftar'>$no. </td>
		<td class='GarisDaftar'>$Isi1</td>		
		<td class='GarisDaftar'><a href='#' cursor='hand' onClick=\"KlikGunakan('".
			$isi['id']."','$Isi1','$Isi2','".$isi['merk_barang']."','".$isi['jml_barang']."','".$isi['satuan'].
			"','".$isi['harga']."','".$isi['tahun']."','".$isi['id']."' )\">$Isi2 </a></td>".
		"<td class='GarisDaftar' align='center'>". 	$isi['tahun']."</td>".
		
		
		"<td class='GarisDaftar' align='left'>".$isi['tgl_penerimaan']."</td>
		<td class='GarisDaftar' align='left'>".$isi['supplier']."</td>
		<td class='GarisDaftar' align='left'>".$isi['faktur_tgl']."</td>
		<td class='GarisDaftar' align='left'>".$isi['faktur_no']."</td>
		
		<td class='GarisDaftar' align='right'>".number_format($isi['jml_barang'],0, ',' , '.' )."</td>
		<td class='GarisDaftar' align='right'>".number_format($isi['harga'],2, ',' , '.' )."</td>		
		<td class='GarisDaftar' align='right'>".number_format($isi['jml_harga'],2, ',' , '.' )."</td>
		
		<td class='GarisDaftar' align='left'>".$isi['ba_tgl']."</td>
		<td class='GarisDaftar' align='left'>".$isi['ba_no']."</td>
		
		<!-- <td align='left'>".$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'] ."</td> -->
		<td class='GarisDaftar' align='left'>".$isi['ket']."</td>
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
	function KlikGunakan(idbi,isi1,isi2, merk, jml, satuan, harga,tahun,idpenerimaan)
	{
		parent.document.adminForm.$objIDBI.value=idbi;
		parent.document.adminForm.$objID.value=isi1; //id barang
		parent.document.adminForm.$objNM.value=isi2; //nm barang
		
		
		
		
		var c = parent.document.getElementById('c').value;
		var d = parent.document.getElementById('d').value;
		var e = parent.document.getElementById('e').value;
		var e1 = parent.document.getElementById('e1').value;
		var id = parent.document.getElementById('pengeluaran_idplh').value;
		
		parent.document.getElementById('fmMEREK').innerHTML= merk;
		parent.document.getElementById('fmTAHUN').value= tahun;
		parent.document.getElementById('harga').value= harga;
		parent.document.getElementById('satuan').value= satuan;
		
		//alert('pages.php?Pg=pengeluaran&ajx=1&tipe=hitPengadaanSebelumnya&idpenerimaan='+idpenerimaan);
		$.ajax({
			
			url: '../../pages.php?Pg=pengeluaran&ajx=1&tipe=hitPengeluaranSebelumnya&idpenerimaan='+idpenerimaan
				,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');							
				if(resp.err==''){
					//alert('c='+c+'d='+d+'e='+e+ 'jml='+resp.content.jml+' cek'+resp.cek);
					
					parent.document.getElementById('jml_ada').value = resp.content.jmlada;	
					
					if( parent.document.getElementById('jml_barang').value =='') parent.document.getElementById('jml_barang').value=0;
					var jml = parent.document.getElementById('jml_barang').value;
					
					parent.document.getElementById('jmlterima').value= resp.content.jmlterima;
					var jmlsisa = parseInt(resp.content.jmlterima) - (  parseInt(resp.content.jmlada) +  parseInt(jml) );
					parent.document.getElementById('jml_sisa').value= jmlsisa;
					
					//parent.document.getElementById('div_spk_tgl').innerHTML = resp.content.tampilspktgl;
					//parent.document.getElementById('spk_tgl_dpb').value= resp.content.spk_tgl;
					//parent.document.getElementById('spk_no_dpb').value= resp.content.spk_no;
					//parent.document.getElementById('nama_perusahaan_dpb').value= resp.content.nama_perusahaan;
					parent.document.getElementById('id_penerimaan').value=idpenerimaan;
					//parent.document.getElementById('supplier').value= resp.content.nama_perusahaan;
					
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
		<th height=\"47\" class=\"searchtext\">Pencarian Data Pengadaan Barang Milik Daerah </th>
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
			<!--<table align=center class=\"adminlist\" border =1>	-->
			<table align=center class=\"koptable\" border =1>
				<tr>
					<td class='GarisDaftar' colspan=12 align=\"center\">
					Ditemukan <font color=red>$numRow</font> data barang yang sesuai dengan kata kunci <b>$Cari1</b>. Pilih nama barang yang akan diinput.
					</td>
				</tr>
				
				<!--
				<tr>
					<th class='th01' align='center' width='40' rowspan=2>No.</th>
					<th class='th01' align='center' width='100' rowspan=2>Kode Barang</th>					
					<th class='th01' align='center' width='200' rowspan=2>Nama Barang</th>
					<th class='th01' align='center' width='60' rowspan=2>Tahun Anggaran</th>					
					<th class='th01' align='center' width='150' rowspan=2>Merk / Type / Ukuran / Spesifikasi</th>					
					
					<th class='th02' align='center' width='150' colspan=3>SPK/ Perjanjian/ Kontrak</th>
					
					<th class='th02' align='center' colspan='3'>Jumlah</th>
					
					<th class='th01' align='center' rowspan=2>Ket</th>
					
				</tr>
				<tr>
					<th class='th01'> PT/CV </th>
					<th class='th01'> Tanggal </th>
					<th class='th01'> Nomor </th>
					
					<th class='th01' align='center'>Jumlah</th>
					<th class='th01' align='center' >Harga Satuan (Rp)</th>
					<th class='th01' align='center' >Jumlah Harga (Rp)</th>
				</tr>
				
				-->
				
				<tr>				
				<th class='th01' width='40' rowspan=2 width='40'>No.</th>
				<th class='th01' width='' rowspan=2 >Kode Barang</th>
				<th class='th01' width='' rowspan=2 >Nama Barang</th>				
				<th class='th01'  width='50' rowspan=2>Tahun<br>Anggaran</th>
				
				<th class='th01'  width='50' rowspan=2>Tanggal</th>
				<th class='th01'  width='' rowspan=2>Dari</th>
				<th class='th02'  width='' colspan=2>Dokumen Faktur</th>
				
				<th class='th02'  width='' colspan=3>Jumlah</th>
				
				<th class='th02' width='' colspan=2>B.A Pemeriksaan</th>				
				<th class='th01'  width='100' rowspan=2>Keterangan </th>							
				</tr>
				
				
				<tr>
				<th class='th01' width='60'>Tanggal </th>				
				<th class='th01' width='80'>Nomor </th>				
				
				<th class='th01' width='80'>Banyaknya Barang </th>								
				<th class='th01' width='100'>Harga Satuan<br>(Rp) </th>				
				<th class='th01' width='150'>Jumlah Harga<br>(Rp) </th>
				
				<th class='th01' width='60'>Tanggal </th>				
				<th class='th01' width='80'>Nomor </th>
				
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
