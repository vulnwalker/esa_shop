<?php

class BandingObj extends DaftarObj2{
	var $Prefix = 'banding';
	var $FormName = 'banding_form';
	var $pagePerHal = 50;
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $Cetak_Mode = 1;
	var $fileNameExcel = 'perbandingan.xls';
	
	function selector(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		$tipe = $_REQUEST['tipe'];
		//echo $tipe;
		if ($tipe==''){
			//$ModulAkses = isPageEnable('00','sum');
			//if ( $ModulAkses>0){
			//echo 'tes2';
			echo $this->pageShow();
					//echo $pbbPenetapan->ajxproses();
			//	}
		}else{
			switch($tipe){			
				case 'subtitle':{											
					break;
				}
				case 'filter': {
					//$opsi = $this->genDaftarOpsi();
					$content ='';// $opsi['TampilOpt'];
					$json=TRUE;		
					break;
				}
				case 'daftar': {
					$Opsi = $this->getDaftarOpsi(); 		
					$daftar = $this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'],  $Opsi['NoAwal'], 1, $Opsi['vKondisi_old']);					
					//$daftar = $this->genDaftar('','',$Limit,$noAwal,1);
					$content=$daftar->content	;
					$err = $daftar->err;
					$cek .= $daftar->cek;
					/*$cek .= 'kondisi='.$Opsi['Kondisi'].'order='.$Opsi['Order'].
						' limit='.$Opsi['Limit'].
						' noawal='.$Opsi['NoAwal'] .
						' vkondisi='.$Opsi['vKondisi_old'];					*/
					$json = TRUE;
					break;
				}
				case 'sumhal':{
					$Opsi = $this->getDaftarOpsi();
					$content = $this->genSumHal($Opsi['Kondisi']);
					$cek .= 'kondisi='.$Opsi['Kondisi'].'order='.$Opsi['Order'].'limit='.$Opsi['Limit'].'noawal='.$Opsi['NoAwal'];
					
					$json= true;
					break;
				}
				case 'cetak_hal': {		
					$this->cetak_hal();
					break;
				}
				case 'cetak_all':{
					$this->cetak_all();							
					break;
				}
				case 'exportXls':{
					$this->exportExcel();
					break;	
				}
				
				
				case 'hapus':{
					$cbid= $_POST[$this->Prefix.'_cb'];				
					$get= $this->Hapus($cbid);
					$err= $get['err']; 
					$cek = $get['cek'];
					$json=TRUE;	
					break;
				}
				
				default: {//other type
					//include('penetapan_list.php'); 
					$other = $this->set_selector_other($tipe);
					$cek = $other['cek'];
					$err = $other['err'];
					$content=$other['content'];
					$json=$other['json'];
					
					break;
				}
			}
			if($Main->SHOW_CEK==FALSE) $cek = '';
			if($json){
				$pageArr = array(
					'cek'=>$cek, 'err'=>$err, 'content'=>$content		
				);
				$page = json_encode($pageArr);	
				echo $page;		
			}
			
		}
		
		
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$fmSTATUS = cekPOST('fmSTATUS');
		//$tgl = explode('-',$fmTGLTERIMA);		
		$arrKondisi = array();	
		//default ----------------
		//$arrKondisi[] = " uid='$UID'";
			 
/*
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');

		switch($fmPILCARI){
			case '1': $arrKondisi[] = " concat(nop_prop,'.',nop_kota,'.',nop_kec,'.',nop_kel,'.',nop_blok,'.',nop_urut,'.',nop_kode) like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " lpad(no_sppt,5,'0') like '%$fmPILCARIVALUE%'"; break;			
			case '3': $arrKondisi[] = " nama_wp like '%$fmPILCARIVALUE%'"; break;
			
		}
		
		switch($fmSTATUS){
			case '1': $arrKondisi[] = " status_batal <> 3 and status_batal <> 4 "; break;
			case '2': $arrKondisi[] = " status_batal = 3 "; break;			
			case '3': $arrKondisi[] = " status_batal = 4 "; break;			
		}		
		
		$fmPILKAS = cekPOST('fmPILKAS');
		if( !empty($fmPILKAS) ) $arrKondisi[] = " ref_admin_group='$fmPILKAS'";
*/		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
/*
		switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}		
*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		$vKondisi2_old = '';
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'vKondisi2_old'=>$vKondisi2_old);
		
	}
	
	function setSumHal_query($Kondisi, $fsum){
		//return "select $fsum from bpk  $Kondisi group by no,c,d,e  "; //echo $aqry;
		return "select count(*) as cnt from (select * from bpk  group by c,d,e,e1) aa";
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		
		//switch($this->Cetak_Mode){
		$tipe= $_REQUEST['tipe'];
		switch($tipe){
			case 'cetak_hal': {
				//$lst = getRekapBanding( TRUE, array(30,'',200,200,200,100), FALSE, $noAwal, $Limit);
				$lst = getRekapBanding2( TRUE, array(30,'',200,200,200,100), FALSE, $noAwal, $Limit,  $this->pagePerHal);				
				break;
			}
			case 'exportXls': {
				//$lst = getRekapBanding( TRUE, array(30,'',200,200,200,100), TRUE, $noAwal, $Limit);
				$lst = getRekapBanding2( TRUE, array(30,'',200,200,200,100), TRUE, $noAwal, $Limit, $this->pagePerHal);
				break;
			}
			default: {
				//$lst = getRekapBanding( FALSE, array(30,'',200,200,200,100), FALSE, $noAwal, $Limit	);
				$lst = getRekapBanding2( FALSE, array(30,'',200,200,200,100), FALSE, $noAwal, $Limit, $this->pagePerHal	);
				break;
			}
		}
		
		return $lst;
	}
	
	
	
	function pageShow(){
		$content = '';
		$cbxDlmRibu = cekPOST("cbxDlmRibu");
		$view->isi = file_get_contents('viewer/viewer.html');
		
		//--- menu tab
		$menuTab = 
			'<script language="JavaScript" src="js/banding.js" type="text/javascript"></script>'.
			'<script language="JavaScript" src="js/jquery.js" type="text/javascript"></script>'.
			"<script>
				$(document).ready(function(){". 
					$this->Prefix.".loading();
				});
			</script>".
			'<table style="width:100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
			<div id="menu">
			<ul>
			<li>
				<div id="<!--menu1-->">		
			   	<a  href="viewer.php?Pg=rekap' . $addPageParam . '" target="_self" title="Rekapitulasi Aset">
					<span> Rekapitulasi Aset</span>
				</a>
				</div >
			</li>
			<li>
				<div id="<!--menu3-->">		
			   	<a  href="viewer.php?Pg=banding' . $addPageParam . '" target="_self" title="Perbandingan">
					<span> Perbandingan</span>
				</a>
				</div >
			</li>
		   	</ul>
			</div>
			</td></tr></table>
			';
		$view->isi = str_replace('<!--menuTab-->', $menuTab, $view->isi);
		$view->isi = str_replace('<!--menu3-->', 'menuaktif', $view->isi);
		
		//--- content  
		$tgl = strtoupper( JuyTgl1(date("Y-m-d")) );
		$content .= $view->isi;
		$content .=
			"<div align='center'>".
			"<div class='main' style='width:98%'>".
			"<table width='100%' style='margin:0 0 10 0'><tr><td align='center' style='font-size:18' >
			<b>PERBANDINGAN ANTARA DATA LAPORAN KEUANGAN HASIL AUDIT 2013 DENGAN ATISISBADA PER 31 DESEMBER 2013<BR>
			POSISI TANGGAL $tgl
		</td></tr></table>".
		"<form name='$this->FormName' id='$this->FormName' method='post'>".
		"<div id='".$this->Prefix."_cont_opsi' name='".$this->Prefix."_cont_opsi' style='position:relative;'></div>".
		"<div id='".$this->Prefix."_cont_daftar' name='".$this->Prefix."_cont_daftar' style='position:relative;'>";
		//ob_flush(); flush();
		
		//-- list
		//$Opsi = $this->getDaftarOpsi();
		//$lst = $this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'],  $Opsi['NoAwal'], 1, $Opsi['vKondisi_old']);
		//getRekapBanding( FALSE, array(30,'',200,200,200,100), FALSE, 0, $this->pagePerHal);
		$content .= //$lst->content.
			"</div>";
		
		//-- cari total
		//$jmlData = 47;
		
		//--- tampil hal		
		$content .= 
			"<div id='".$this->Prefix."_cont_hal' name='".$this->Prefix."_cont_hal' style='position:relative;'>".
			//$this->setDaftar_hal($jmlData).
			"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
					/*"<table class='koptable' border='1' width='100%' style='margin:4 0 4 0'>
					<tr><td align=center style='padding:4'>".
						Halaman2b(	$jmlData, $pagePerHal, $elhal, $hal, 5,
						 					
							'banding.gotoHalaman').
							
							
					"</td></tr></table>";*/
		
		//--- menu
			$content .= 
			"<table width=\"100%\" class=\"menudottedline\" style='margin:4 0 10 0'>
			<tr><td>
				<table width=\"50\"><tr>				
				<td>".PanelIcon1("javascript:banding.cetakHal()","print_f2.png","Cetak")."</td>	
				<td>".PanelIcon1("javascript:banding.exportXls()","export_xls.png","Excel")."</td>			
				<!--<td>".PanelIcon1("javascript:adminForm.action='index.php?Pg=PR&SPg=rekap_banding_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Cetak")."</td>			
				<td>".PanelIcon1("javascript:adminForm.action='index.php?Pg=PR&SPg=rekap_banding_cetak&xls=1';adminForm.target='';adminForm.submit();","export_xls.png","Excel")."</td>
				-->
				
				</tr></table>".			
			"</td></tr>
			</table>".
			
			
			"</form>".
			"</div>".
			"</div>";
		//ob_flush();	flush();
		
		echo $content;
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
		
		$tgl = strtoupper( JuyTgl1(date("Y-m-d")) );
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
				
				"<table width='100%' style='margin:0 0 10 0'><tr><td align='center' style='font-size:12' colspan=6>
	<b>PERBANDINGAN ANTARA DATA LAPORAN KEUANGAN SEBELUM AUDIT DENGAN ATISISBADA PER 31 DESEMBER 2012<BR>
	POSISI TANGGAL $tgl
</td></tr></table>".

				"<div id='cntTerimaKondisi'>".
				
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi(); 		
		$daftar = $this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'],  $Opsi['NoAwal'], 1, $Opsi['vKondisi_old']);					
		echo $daftar->content;	
		
		/*$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);			
		if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}*/								
		echo	"</div>	".			
				//$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}

	
}

$banding = new BandingObj();




?>