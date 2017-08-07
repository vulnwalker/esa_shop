<?php

class Rekapdpb_skpd_rinciObj extends DaftarObj2{
	var $Prefix = 'Rekapdpb_skpd_rinci';
	//var $SHOW_CEK = TRUE;	
	var $TblName = 'dkb';//view2_sensus';
	var $TblName_Hapus = 'dkb';
	var $TblName_Edit = 'dkb';
	var $KeyFields = array('id');
	var $FieldSum = array('rencana','realisasi','selisih');//array('selisih');
	var $SumValue = array('selisih');
	var $FieldSum_Cp1 = array( 4, 3,3);//berdasar mode
	var $FieldSum_Cp2 = array( 2, 0, 0);	
	var $fieldSum_lokasi = array( 5);  //lokasi sumary di kolom ke		
	var $FormName = 'Rekapdpb_skpd_rinciForm';
	var $PageTitle = 'Rincian Rekap DPBMD (SKPD)';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';	
	var $fileNameExcel='rekapdpb_skpd_rinci.xls';
	var $Cetak_Judul = 'RINCIAN REKAP DPBMD (SKPD)';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;

	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".			*/
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pengadaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".

			$scriptload;
	}
	
	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/HrgSatPilih.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/perencanaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	
	function setTitle(){
		return 'RINCIAN REKAP DPBMD (SKPD)';
	}
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		if($Kondisi==''){
			$Kondisi1 = "where ref_iddkb=0";
		}else{
			$Kondisi1 = "and ref_iddkb=0";
		}
		
		$aqry = "(SELECT * 
				FROM (SELECT
				          `aa`.`id` AS `id`, `aa`.`c` AS `c`, `aa`.`d` AS `d`, `aa`.`e` AS `e`,
				          `aa`.`e1` AS `e1`, `aa`.`f` AS `f`, `aa`.`g` AS `g`, `aa`.`h` AS `h`,
				          `aa`.`i` AS `i`, `aa`.`j` AS `j`, `aa`.`nm_barang` AS `nm_barang`,
				          `aa`.`k` AS `k`, `aa`.`l` AS `l`, `aa`.`m` AS `m`, `aa`.`n` AS `n`,
				          `aa`.`o` AS `o`, `aa`.`kf` AS `kf`, `aa`.`nm_account` AS `nm_account`,
				          `aa`.`merk_barang` AS `merk_barang`, `aa`.`jml_harga` AS `rencana`,
				          `bb`.`jml_harga` AS `realisasi`, (`aa`.`jml_harga`-`bb`.`jml_harga`) AS `selisih`,
						  `aa`.`tahun` AS `tahun`, `bb`.`spk_tgl` AS
				          `spk_tgl`, `bb`.`ref_iddkb` AS `ref_iddkb`
				        FROM
				          `dkb` `aa` LEFT JOIN
				          `pengadaan` `bb` ON `aa`.`id` = `bb`.`ref_iddkb` ) `cc`
				$Kondisi)
				UNION
				(SELECT
				  `dd`.`id` AS `id`, `dd`.`c` AS `c`, `dd`.`d` AS `d`, `dd`.`e` AS `e`,
				  `dd`.`e1` AS `e1`, `dd`.`f` AS `f`, `dd`.`g` AS `g`, `dd`.`h` AS `h`,
				  `dd`.`i` AS `i`, `dd`.`j` AS `j`, `dd`.`nm_barang` AS `nm_barang`,
				  `dd`.`k` AS `k`, `dd`.`l` AS `l`, `dd`.`m` AS `m`, `dd`.`n` AS `n`,
				  `dd`.`o` AS `o`, `dd`.`kf` AS `kf`, `dd`.`nm_account` AS `nm_account`,
				  `dd`.`merk_barang` AS `merk_barang`, 0 AS `rencana`, `dd`.`jml_harga` AS
				  `realisasi`, (0 - `dd`.`jml_harga`) AS `selisih`, 
				  `dd`.`tahun` AS `tahun`, `dd`.`spk_tgl` AS `spk_tgl`,
				  `dd`.`ref_iddkb` AS `ref_iddkb`
				FROM
				  `pengadaan` `dd`  
				$Kondisi $Kondisi1 )
				$Order $Limit";	//echo $aqry;
		//return mysql_query($aqry);
		return $aqry;
	}
	
	function setSumHal_query($Kondisi, $fsum){
		if($Kondisi==''){
			$Kondisi1 = "where ref_iddkb=0";
		}else{
			$Kondisi1 = "and ref_iddkb=0";
		}
		
		//return "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		return "select $fsum from (SELECT * 
				FROM (SELECT
				          `aa`.`id` AS `id`, `aa`.`c` AS `c`, `aa`.`d` AS `d`, `aa`.`e` AS `e`,
				          `aa`.`e1` AS `e1`, `aa`.`f` AS `f`, `aa`.`g` AS `g`, `aa`.`h` AS `h`,
				          `aa`.`i` AS `i`, `aa`.`j` AS `j`, `aa`.`nm_barang` AS `nm_barang`,
				          `aa`.`k` AS `k`, `aa`.`l` AS `l`, `aa`.`m` AS `m`, `aa`.`n` AS `n`,
				          `aa`.`o` AS `o`, `aa`.`kf` AS `kf`, `aa`.`nm_account` AS `nm_account`,
				          `aa`.`merk_barang` AS `merk_barang`, `aa`.`jml_harga` AS `rencana`,
				          `bb`.`jml_harga` AS `realisasi`, (`aa`.`jml_harga`-`bb`.`jml_harga`) AS `selisih`, `aa`.`tahun` AS `tahun`, `bb`.`spk_tgl` AS
				          `spk_tgl`, `bb`.`ref_iddkb` AS `ref_iddkb`
				        FROM
				          `dkb` `aa` LEFT JOIN
				          `pengadaan` `bb` ON `aa`.`id` = `bb`.`ref_iddkb` ) `cc`
				$Kondisi 
				UNION
				SELECT
				  `dd`.`id` AS `id`, `dd`.`c` AS `c`, `dd`.`d` AS `d`, `dd`.`e` AS `e`,
				  `dd`.`e1` AS `e1`, `dd`.`f` AS `f`, `dd`.`g` AS `g`, `dd`.`h` AS `h`,
				  `dd`.`i` AS `i`, `dd`.`j` AS `j`, `dd`.`nm_barang` AS `nm_barang`,
				  `dd`.`k` AS `k`, `dd`.`l` AS `l`, `dd`.`m` AS `m`, `dd`.`n` AS `n`,
				  `dd`.`o` AS `o`, `dd`.`kf` AS `kf`, `dd`.`nm_account` AS `nm_account`,
				  `dd`.`merk_barang` AS `merk_barang`, 0 AS `rencana`, `dd`.`jml_harga` AS
				  `realisasi`, (0 - `dd`.`jml_harga`) AS `selisih`, `dd`.`tahun` AS `tahun`, `dd`.`spk_tgl` AS `spk_tgl`, 
				  `dd`.`ref_iddkb` AS `ref_iddkb`
				FROM
				  `pengadaan` `dd`  
				$Kondisi $Kondisi1) `zz` "; //echo $aqry;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='5'>No.</th>
				$Checkbox		
				<th class='th01' >Nama Barang / Nama Akun</th>
				<th class='th01' >Merk / Type / Ukuran / Spesifikasi </th>
				<th class='th01' >Rencana </th>
				<th class='th01' >Realisasi</th>
				<th class='th01' >Selisih</th>
				</tr>
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS;
		
		$rencana = $isi['rencana'] == ''? 0 : number_format( $isi['rencana'] ,2,',','.');
		$realisasi = $isi['realisasi'] == ''? 0 : number_format( $isi['realisasi'] ,2,',','.');
		/*if($isi['rencana']==0){
			$slsh = $isi['realisasi'];
		}else{
			$slsh=$isi['rencana']-$isi['realisasi'];
		}*/
		$slsh=$isi['selisih'];
		$selisih=$slsh == ''? 0 : number_format( $slsh ,2,',','.');
				
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['nm_barang'].'/<br>'.
			$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'.'.$isi['kf'].'<br>'.
			$isi['nm_account']		
		);		
		$Koloms[] = array('', $isi['merk_barang'] );
		$Koloms[] = array("align='right'", $rencana);
		$Koloms[] = array("align='right'", $realisasi);
		$Koloms[] = array("align='right'", $selisih);
		return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
		global $Ref, $Main;
	
		$tahun=$_REQUEST['tahun']==''?$_COOKIE['coThnAnggaran']:$_REQUEST['tahun'];
		$c=$_REQUEST['c'];
		$d=$_REQUEST['d'];
		$sem=$_REQUEST['sem'];
		
		//ambil nama bidang
		$row=mysql_fetch_array(mysql_query("select nm_skpd as nmBidang from ref_skpd where c='$c' and d='00'"));
		$fmBidang=$row['nmBidang']==''?'-':$row['nmBidang'];
		
		if($d!='00'){
			//ambil nama skpd
			$row=mysql_fetch_array(mysql_query("select nm_skpd as nmSkpd from ref_skpd where c='$c' and d='$d'"));
		}$fmSkpd=$row['nmSkpd']==''?'-':$row['nmSkpd'];
		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	
				<tr>		
					<td width='50'>BIDANG</td>
					<td width='10'>:</td>
					<td><input type='text' value='$fmBidang' id='fmBidang' name='fmBidang' size='60' readonly></td>
				</tr>
				<tr>		
					<td>SKPD</td>
					<td>:</td>
					<td><input type='text' value='$fmSkpd' id='$fmSkpd' name='fmSkpd' size='60' readonly></td>
				</tr>
				<tr>		
					<td>TAHUN</td>
					<td>:</td>
					<td>
					<input type='text' value='$tahun' id='tahun' name='tahun' size='5' readonly>&nbsp;
					SEMESTER : <input type='text' value='$sem' id='sem' name='sem' size='5' readonly>
					<input type='hidden' value='$c' id='c' name='c'>
					<input type='hidden' value='$d' id='d' name='d'>
					</td>
				</tr>
			</table>";
			
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		$tahun = $_REQUEST['tahun'];
		$sem = $_REQUEST['sem'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d']=='00'?'':$_REQUEST['d'];
		
		if(!empty($tahun)) $arrKondisi[]= "tahun = '$tahun'";
		if(!empty($c) )  $arrKondisi[] = "c='$c'";
		if(!empty($d) )  $arrKondisi[] = "d='$d'";
		
		switch($sem){			
			case '0': $arrKondisi[] = " spk_tgl>='".$tahun."-01-01' and spk_tgl<='".$tahun."-12-31' "; break;
			case '1': $arrKondisi[] = " spk_tgl>='".$tahun."-01-01' and spk_tgl<='".$tahun."-06-30' "; break;
			case '2': $arrKondisi[] = " spk_tgl>='".$tahun."-07-01' and spk_tgl<='".$tahun."-12-31' "; break;
			default :""; break;
		}
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	function setMenuEdit(){		
		return "";
			
	}
	
	function setMenuView(){
		return //"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHit()","print_f2.png","Cetak", 'Cetak Nota Hitung')."</td>
					"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
					<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>
					<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>";
					
	}
	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru2':{				
				//echo 'tes';
				$this->setFormBaru();				
				//$cek = $fm['cek'];
				//$err = $fm['err'];
				//$content = $fm['content'];
				$json = FALSE;				
				break;
			}
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$TampilOpt = $this->genDaftarOpsi();
		
		return		
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
			"<input type='hidden' id='c' name='c' value='".$_REQUEST['c']."'>".
			"<input type='hidden' id='d' name='d' value='".$_REQUEST['d']."'>".
			"<input type='hidden' id='tahun' name='tahun' value='".$_REQUEST['tahun']."'>".
			"<input type='hidden' id='sem' name='sem' value='".$_REQUEST['sem']."'>".
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
			"</div>";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmBIDANG = cekPOST('fmBidang'); //echo 'fmskpd='.$fmSKPD;
		$fmSKPD = cekPOST('fmSkpd');
		$fmTahun = cekPOST('tahun');

		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">
						<table cellpadding='0' cellspacing='0' border='0' width=\"100%\">
							<tbody>
								<tr valign='top'> 
									<td style='font-weight:bold;font-size:10pt' width='70'>BIDANG</td>
									<td style='width:10;font-weight:bold;font-size:10pt'>:</td>
									<td style='font-weight:bold;font-size:10pt'>$fmBIDANG</td> 
								</tr> 
								<tr valign='top'> 
									<td style='font-weight:bold;font-size:10pt'>SKPD</td>
									<td style='width:10;font-weight:bold;font-size:10pt'>:</td>
									<td style='font-weight:bold;font-size:10pt'>$fmSKPD</td> 
								</tr> 
								<tr valign='top'> 
									<td style='font-weight:bold;font-size:10pt'>TAHUN</td>
									<td style='width:10;font-weight:bold;font-size:10pt'>:</td>
									<td style='font-weight:bold;font-size:10pt'>$fmTahun</td> 
								</tr> 
								
								<!--<tr valign='top'> <td style='font-weight:bold;font-size:10pt'>UNIT</td><td style='width:10;font-weight:bold;font-size:10pt'>:</td><td style='font-weight:bold;font-size:10pt'> </td> </tr> 
								<tr valign='top'> <td style='font-weight:bold;font-size:10pt'>SUB UNIT</td><td style='width:10;font-weight:bold;font-size:10pt'>:</td><td style='font-weight:bold;font-size:10pt'> </td> </tr>--> 
							
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<br>";
	}
	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
		/*
		<style>
		.nfmt1 {mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt2 {mso-number-format:'0\.00_';}
		.nfmt3 {mso-number-format:'00000';}
		.nfmt4 {mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt5 {mso-number-format:'\@';} 
		table {mso-displayed-decimal-separator:'\.';
			mso-displayed-thousand-separator:'\,';}	
		br {mso-data-placement:same-cell;}	
		</style>*/ 	
		//if($this->cetak_xls){
		$this->cetak_xls=$xls;
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}
						
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH'>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
			//echo 'vkondisi='.$$Opsi[vKondisi;
		if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}								
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}
	
}
$Rekapdpb_skpd_rinci = new Rekapdpb_skpd_rinciObj();

?>