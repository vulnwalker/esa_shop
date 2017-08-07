<?php

class FileBackupObj2 extends DaftarObj2{
	var $Prefix = 'FileBackup2'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'ref_barang'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 5,5,5);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 6; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='FileBackup.xls';
	var $Cetak_Judul = 'FileBackup';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'FileBackup';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $jml_data=0;
	var $totBrgAset = 0;
	var $totHrgAset = 0;
	
	function setTitle(){
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		return 'FileBackup ';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>File Backup Manager";
		if ($this->cetak_xls==TRUE)
		{
			$judul="<table width='100%'><tr><td colspan=6>File Backup Manager</td></tr></table>";
		}
		return $judul;
	}
	
	function setMenuEdit(){		
		return '';
	}
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){
	global $Main;	
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];
		//if($Pg == 'sensus'){
		//	$styleMenu = " style='color:blue;' ";	
		//}
		$SPg = $_REQUEST['menu'];
		switch ($SPg){
			case 'Bk01' : $styleMenu1_1 = " style='color:blue;' "; break;
			case 'Bk02': $styleMenu1_2 = " style='color:blue;' "; break;
		}
		
		
		
		$menubar = 	
			"
		
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
			<A href=\"index.php?Pg=Admin&SPg=Bk01\" title='Backup Harian' $styleMenu1_1>Harian</a> |
			<A href=\"index.php?Pg=Admin&SPg=Bk02\" title='Mingguan' $styleMenu1_2>Mingguan</a>&nbsp&nbsp&nbsp
			</td></tr>			
			</table>";
		
		return $menubar;
			
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		$level = $_REQUEST['level'];
		$tglbackup_tgl1 = $_REQUEST['tglbackup_tgl1'];
		$tglbackup_tgl2 = $_REQUEST['tglbackup_tgl2'];
		$tglbackup_tgl1_kosong = $_REQUEST['tglbackup_tgl1_kosong'];
		$tglbackup_tgl2_kosong = $_REQUEST['tglbackup_tgl2_kosong'];	
		$idpengguna = $_REQUEST['idpengguna'];

$vtglbackup = 	
			"<div style='float:left;padding: 0 4 0 4;height:22;'>".
				//createEntryTgl(	 'tgllogin', $tgllogin,	'', '', '', 'adminForm',0, $withBtnClear = TRUE).
				createEntryTglBeetwen('tglbackup',$tglbackup_tgl1, $tglbackup_tgl2,'','','adminForm',1, $tglbackup_tgl1_kosong, $tglbackup_tgl2_kosong);
			"</div>";	
		$TampilOpt =
			//"level= ".$level.
			
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
			<tr valign=\"top\">   		
			<td> ".
				"<table width=100%><tr><td>".
					"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan : </div>".
					$vtglbackup. $Main->batas.	
					"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>".
				"</td></tr></table>".
			"</td></tr></table>".$hiddenOld;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		if($level != '' ) $arrKondisi[] = " level='$level'";	
		//-- tgl login
		$tglbackup_tgl1 = $_REQUEST['tglbackup_tgl1'];
		$tglbackup_tgl2 = $_REQUEST['tglbackup_tgl2'];
		$tglbackup_tgl1_kosong = $_REQUEST['tglbackup_tgl1_kosong']; //$cek.= ' tglkosong1='.$tglbackup_tgl1_kosong;
		$tglbackup_tgl2_kosong = $_REQUEST['tglbackup_tgl2_kosong']; //$cek.= ' tglkosong2='.$tglbackup_tgl2_kosong;
		//-- def tgl	
		if ($tglbackup_tgl1 =='' && $tglbackup_tgl1_kosong!='1') $tglbackup_tgl1 = date("Y-m-d"); 
		if ($tglbackup_tgl2 =='' && $tglbackup_tgl2_kosong!='1') $tglbackup_tgl2 = date("Y-m-d"); 
		//-- set tgl
		if($tglbackup_tgl1!='' ) $arrKondisi[] = " (login>='$tglbackup_tgl1')";
		if($tglbackup_tgl2!='' ) $arrKondisi[] = " lastaktif<=DATE_ADD('$tglbackup_tgl2',INTERVAL 1 DAY) ";



		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						-->".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			
		$headerTable =
			"<tr>
				<th class=\"th02\" width='30' >No.</th>
				<th class=\"th02\" >Nama File</th>
				<th class=\"th02\" width='200' >Tgl. Backup</th>
			</tr>
			<tr>
				<th class=\"th03\" >(1)</th>
				<th class=\"th03\" >(2)</th>
				<th class=\"th03\" >(3)</th>
			</tr>				
			$tambahgaris";
			$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	function setDaftar_After($no=0, $ColStyle=''){
		
/*
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
*/			
			$ListData='';
		
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$Koloms = array();


		
		
		
		

		return $Koloms;
	}
	
	function setKolomData_($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
	
		
		

		return $Koloms;
	}
	
	
	function gen_table_data($Mode=1)
{
global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				

        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">xxxx</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">12-12-2014</td>
        </tr>
		";		
			
		
		
		
	return $ListData;
}

	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/


	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		


		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\" colspan=6>Daftar Backup Database</td>
			</tr>
			</table>";
	}
	
}


$FileBackup2 = new FileBackup2();

?>