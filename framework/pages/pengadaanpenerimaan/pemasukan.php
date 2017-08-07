<?php

include "pages/pencarian/DataPengaturan.php";
$DataOption = $DataPengaturan->DataOption();

class pemasukanObj  extends DaftarObj2{	
	var $Prefix = 'pemasukan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_penerimaan_barang'; //bonus
	var $TblName_Hapus = 't_penerimaan_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'PENGADAAN DAN PENERIMAAN';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan.xls';
	var $namaModulCetak='PENGADAAN DAN PENERIMAAN';
	var $Cetak_Judul = 'Pemasukan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '21.5cm';
	var $Cetak_WIDTH_Landscape = '33cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'pemasukanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $pid = '';
	var $noakhirnya = 1; 
	
	
	var $arr_laporan = array(
			array('1', 'DAFTAR PENERIMAAN BARANG'),
			array('2', 'REKAPITULASI PENERIMAAN BARANG'),
	);
		
	function genForm($form_name = "pemasukan_form"){	
		//$form_name = $this->Prefix.'_form';	
		$form = 
			centerPage(
				"<form name='$form_name' id='$form_name' method='post' action='' >".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height).
				"</form>"
			);
		return $form;
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class='$TblStyle' border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		$qry = mysql_query($aqry);
		$numrows = mysql_num_rows($qry); $cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
					
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				$isi = array_map('utf8_encode', $isi);	
				
				$no++;
				$jmlDataPage++;
				if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
				
				$KeyValue = array();
				for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
					$KeyValue[$i] = $isi[$this->KeyFields[$i]];
				}
				$KeyValueStr = join($this->pemisahID,$KeyValue);
				$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
					
				
				
				//sum halaman
				for ($i=0; $i< sizeof($this->FieldSum) ; $i++) {
					$this->SumValue[$i] += $isi[$this->FieldSum[$i]];
				}
				
				//---------------------------
				$rowatr_ = $RowAtr." valign='top' id='$cb' value='".$isi['Id']."'";
				$bef= $this->setDaftar_before_getrow(
						$no,$isi,$Mode, $TampilCheckBox,  
						$rowatr_,
						$ColStyle
						);
				$ListData .= $bef['ListData'];
				$no = $bef['no'];
				//get row
				$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
				
				if($Koloms != NULL){
					
				
					$list_row = genTableRow($Koloms, 
								$rowatr_,
								$ColStyle);		
					
					
					$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
						$RowAtr, $ColStyle);
					
					$cb++;
					
					if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
						echo $ListData;
						ob_flush();
						flush();
						$ListData='';
						//sleep(2); //tes
					}
				}
			}
		}
		
		}
		
		$ListData .= $this->setDaftar_After($no, $ColStyle);
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		//$SumHal = $this->genSumHal($Kondisi);
		$ContentSum = $this->genRowSum($ColStyle,  $Mode, 
			$SumHal['sums']
		);
		/*$TampilTotalHalRp = number_format($TotalHalRp,2, ',', '.');		
		$TotalColSpan = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
		$ContentTotalHal =
			"<tr>
				<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>
				<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
				<td class='$ColStyle' colspan='4'></td>
			</tr>" ;
			
		$ContentTotal = 
				"<tr>
					<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total</td>
					<td class='$ColStyle' align='right'><b><div  id='cntDaftarTotal'>".$SumHal['sum']."</div></td>
					<td class='$ColStyle' colspan='4'></td>
				</tr>" ;
		
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			$ContentTotalHal='';			
		}
		$ContentSum=$ContentTotalHal.$ContentTotal;
		*/
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>"
			.$vKondisi_old
			;
		if ($Mode==3) {	//flush
			echo $ListData;	
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}
	
	function setTitle(){
		$status_hal = " PENERIMAAN PENGADAAN";
		if($_REQUEST['halmannya'] == 2)$status_hal = 'PENGADAAN PEMELIHARAAN';
		return "DAFTAR $status_hal BARANG";
	}
	
	function setMenuEdit(){
			global $Main;
			$postingkan='';
			//if($_REQUEST['halmannya'] == 1){
				if($Main->PENERIMAAN_P19_POST == 1)$postingkan = "<td>".genPanelIcon("javascript:".$this->Prefix.".Posting()","publishdata.png","Posting", 'Posting')."</td>";
			//}
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".InputBaru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".AtribusiBaru()","atribusi.png","Atribusi", 'Atribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Validasi()","validasi-menu.png","Validasi", 'Validasi')."</td>".
			$postingkan;
	}
	
	function setMenuView(){
			$cetak = "Laporan";
			if($_REQUEST['halmannya'] == 2)$cetak = 'cetakHal';
		return 
			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Laporan',"Laporan")."</td>";				
			"<td>".genPanelIcon("javascript:".$this->Prefix.".$cetak()","print_f2.png",'Laporan',"Laporan")."</td>";				
			
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $statval = "";
	 $tgl_validasi = "";
	 $status_spp = "";
	 if(isset($_REQUEST['validasi']))$statval = "1";
	 
	 if($err == ''){
	 	if($statval == "1"){
			$qry = "UPDATE t_penerimaan_barang SET status_validasi='$statval', uid_validasi='$uid', tgl_validasi=NOW(), status_spp='1' WHERE Id='$idplh' ";
			$content['alert'] = "Data Berhasil di Validasi !";
		}else{
			$qry = "UPDATE t_penerimaan_barang SET status_validasi=NULL, uid_validasi=NULL, tgl_validasi=NULL, status_spp=NULL  WHERE Id='$idplh'";
			$content['alert'] = "Berhasil Membatalkan Validasi !";
		}
		$cek.=' | '.$qry;
		$aqry = mysql_query($qry);	
	 }
	 			
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main, $HTTP_COOKIE_VARS;;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 
	  switch($tipe){	
			
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'BuatLaporan':{				
			$fm = $this->setBuatLaporan();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'PrintLaporan':{				
			$json=FALSE;		
			$this->PrintLaporan();										
		break;
		}
		case 'SetNama':{				
			$fm = $this->SetNama();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'SimpanTTD':{				
			$fm = $this->SimpanTTD();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'LaporanTTD':{				
			$fm = $this->setLaporanTTD();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'HapusPosting':{				
			$fm = $this->HapusPosting();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'PengecekanUbah':{										
			$get= $this->CekAtribusi();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];											
		break;
		}
						
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'hapus':{
			$get= $this->Hapus();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
		}
		
		case 'CetakPermohonan': {
			$json=FALSE;		
			$this->CetakPermohonan();
		break;
		}
		
		case 'Validasi':{
			$dt = array();
			$err='';
			$content='';
			$uid = $HTTP_COOKIE_VARS['coID'];
			
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$idplh = $cbid[0];
			$this->form_idplh = $cbid[0];
			
			$qry = "SELECT * FROM t_penerimaan_barang WHERE Id = '$idplh' ";$cek=$qry;
			$aqry = mysql_query($qry);
			$dt = mysql_fetch_array($aqry);
			
			$namavalid = "SELECT nama FROM admin WHERE uid='".$dt['uid_validasi']."'";
			$aqry_namavalid = mysql_fetch_array(mysql_query($namavalid));
			
			//if($dt['status_validasi'] == '1')if($dt['uid_validasi'] != '' || $dt['uid_validasi'] != null)if($dt['uid_validasi'] != $uid) $err = "Data Sudah di Validasi, Perubahan Hanya Bisa Dilakukan oleh yang Memvalidasi !";
			if($dt['status_validasi'] == '1')if($dt['uid_validasi'] != '' || $dt['uid_validasi'] != null)if($dt['uid_validasi'] != $uid) $err = "Data Sudah di Validasi, Perubahan Hanya Bisa Dilakukan oleh ".$aqry_namavalid['nama']." !";
			
			if($err == ''){
				$fm = $this->Validasi($dt);				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];
			}				
															
		break;
		}
		case 'formPosting':{				
			$fm = $this->setFormPosting();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'PengecekanPosting':{				
			$fm = $this->PengecekanPosting();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'PengecekanKapitalisasi':{				
			$fm = $this->PengecekanKapitalisasi();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'ProsesPostingPemeliharaan':{				
			$fm = $this->ProsesPostingPemeliharaan();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'ProsesPosting':{				
			$fm = $this->ProsesPosting();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
	   
		 default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
		 break;
		 }
		 
	 }//end switch
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
   
   function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>
					<style>
						.tetapdiatas{
							position:fixed;
							top:0px;
							
						}
					</style>
					";
		return 
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pengadaanpenerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pengadaanpenerimaan/pemasukan_ins.js' language='JavaScript' ></script>".
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			'.
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   	
   function setBuatLaporan(){
   		global $DataOption;
   
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		
		$dt['c1'] = '0';
		$dt['c'] = '00';
		$dt['d'] = '00';
		$dt['e'] = '00';
		$dt['e1'] = '000';
			
		if($DataOption['skpd'] == 1){
			$dt['c'] = $_REQUEST['pemasukanSKPD2fmSKPD'];
			$dt['d'] = $_REQUEST['pemasukanSKPD2fmUNIT'];
			$dt['e'] = $_REQUEST['pemasukanSKPD2fmSUBUNIT'];
			$dt['e1'] = $_REQUEST['pemasukanSKPD2fmSEKSI'];
		}else{
			$dt['c1'] = $_REQUEST['pemasukanSKPDfmUrusan'];
			if($dt['c1'] != '00' && $dt['c1'] != '0' && $dt['c1'] != ''){
				$dt['c'] = $_REQUEST['pemasukanSKPDfmSKPD'];
				if($dt['c'] != '00' && $dt['c'] != ''){
					$dt['d'] = $_REQUEST['pemasukanSKPDfmUNIT'];
					if($dt['d'] != '00' && $dt['d'] != ''){
						$dt['e'] = $_REQUEST['pemasukanSKPDfmSUBUNIT'];
						if($dt['e'] != '00' && $dt['e'] != '')$dt['e1'] = $_REQUEST['pemasukanSKPDfmSEKSI'];
					}
				}
			}else{
				$dt['c1'] = '0';	
			}
			
		}
		 
		$fm = $this->setFormBuatLaporan($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBuatLaporan($dt){	
	 global $SensusTmp,$HTTP_COOKIE_VARS ;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';						
	 $this->form_width = 470;
	 $this->form_height = 150;
	 $this->form_caption = 'Laporan';
	 
	 $UID = $_COOKIE['coNama']; 
	 //$namauid = $_COOKIE['coNama'];
	 $tgl = date('d-m-Y');
		
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'nama' => array( 
						'label'=>'NAMA LAPORAN',
						'labelWidth'=>120, 
						'value'=>cmbArray('nama_laporan','1',$this->arr_laporan, '--- PILIH LAPORAN ---', "style='width:280px;'"),
						 ),	
			'tanggal' => array( 
						'label'=>'TANGGAL CETAK',
						'labelWidth'=>120, 
						'value'=>$tgl, 
						'type'=>'text',
						'param'=>"style='width:80px;' readonly"
						 ),	
			'periode' => array( 
						'label'=>'PERIODE',
						'labelWidth'=>120, 
						'value'=>"<input type='text' name='dari' value='01-".date('m-Y')."' style='width:80px;' id='sampai' class='datepicker' /> s.d <input type='text' name='sampai' id='sampai' style='width:80px;' class='datepicker' value='31-".date('m-Y')."' />", 
						
						 ),	
			'user' => array( 
						'label'=>'USER',
						'labelWidth'=>120, 
						'value'=>$UID, 
						'type'=>'text',
						'param'=>"style='width:280px;' readonly"
						 ),				
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='c1nya' value='".$dt['c1']."' />".
			"<input type='hidden' name='cnya' value='".$dt['c']."' />".
			"<input type='hidden' name='dnya' value='".$dt['d']."' />".
			"<input type='hidden' name='enya' value='".$dt['e']."' />".
			"<input type='hidden' name='e1nya' value='".$dt['e1']."' />".
			"<input type='button' value='TTD' onclick ='".$this->Prefix.".LaporanTTD()' title='TTD' > ".
			"<input type='button' value='CETAK' onclick ='".$this->Prefix.".PrintLaporan()' title='CETAK' > ".
			"<input type='button' value='BATAL' title='BATAL' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setLaporanTTD(){
		global $DataOption, $DataPengaturan;
		$cek='';
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$dt['c1'] = '0';
		$dt['c'] = '00';
		$dt['d'] = '00';
		$dt['e'] = '00';
		$dt['e1'] = '000';
			
		if($DataOption['skpd'] == 1){
			$dt['c'] = $_REQUEST['cnya'];
			$dt['d'] = $_REQUEST['dnya'];
			$dt['e'] = $_REQUEST['enya'];
			$dt['e1'] = $_REQUEST['e1nya'];
		}else{
			$dt['c1'] = $_REQUEST['c1nya'];
			if($dt['c1'] != '00' && $dt['c1'] != '0' && $dt['c1'] != ''){
				$dt['c'] = $_REQUEST['cnya'];
				if($dt['c'] != '00' && $dt['c'] != ''){
					$dt['d'] = $_REQUEST['dnya'];
					if($dt['d'] != '00' && $dt['d'] != ''){
						$dt['e'] = $_REQUEST['enya'];
						if($dt['e'] != '00' && $dt['e'] != '')$dt['e1'] = $_REQUEST['e1nya'];
					}
				}
			}else{
				$dt['c1'] = '0';	
			}
			
		}
		
		$wherenya = " WHERE c1='".$dt['c1']."' AND c='".$dt['c']."' AND d='".$dt['d']."' AND e='".$dt['e']."' AND e1='".$dt['e1']."' ";
		$datanya = $DataPengaturan->QyrTmpl1Brs('ref_penerimaan_tandatangan','*', $wherenya);$cek.=$datanya['cek'];
		$data = $datanya['hasil'];
		$dt['Id_penerima'] = $data['refid_penerima'];
		$dt['Id_mengetahui'] = $data['refid_mengetahui'];
		
		$fm = $this->setFormLaporanTTD($dt);
		return	array ('cek'=>$fm['cek'].$cek, 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormLaporanTTD($dt){	
	 global $SensusTmp,$HTTP_COOKIE_VARS ;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form_TTD';				
	 $this->form_width = 420;
	 $this->form_height = 300;
	 $this->form_caption = 'TANDA TANGAN';
	 
	 $UID = $_COOKIE['coID']; 
	 $tgl = date('d-m-Y');
		
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	$qry_ref_ttd = "SELECT Id, nama FROM ref_tandatangan WHERE c1='".$dt['c1']."' AND c='".$dt['c']."' AND d='".$dt['d']."' AND e='".$dt['e']."' AND e1='".$dt['e1']."' ";$cek.=$qry_ref_ttd;
		
	 //items ----------------------""
	  $this->form_fields = array(
			'nama_penerima' => array( 
						'label'=>'NAMA PENERIMA',
						'labelWidth'=>120, 
						'value'=>cmbQuery('nama_penerima', $dt['Id_penerima'], $qry_ref_ttd,"style='width:250px;' onchange='".$this->Prefix.".SetNama(`penerima`);'","--- PILIH PENERIMA ---"),
						 ),	
			'nip_penerima' => array( 
						'label'=>'NIP',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'pangkat_penerima' => array( 
						'label'=>'PANGKAT/GOL',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'eselon_penerima' => array( 
						'label'=>'ESELON',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'jabatan_penerima' => array( 
						'label'=>'JABATAN',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'Enter' => array( 
						'label'=>' ',
						'labelWidth'=>120, 
						'pemisah' =>' ',
						'value'=>'<br><br>', 
						
						 ),	
			'nama_mengetahui' => array( 
						'label'=>'NAMA MENGETAHUI',
						'labelWidth'=>120, 
						'value'=>cmbQuery('nama_mengetahui', $dt['Id_mengetahui'], $qry_ref_ttd,"style='width:250px;' onchange='".$this->Prefix.".SetNama(`mengetahui`);'","--- PILIH PENERIMA ---"),
						 ),	
			'nip_mengetahui' => array( 
						'label'=>'NIP',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'pangkat_mengetahui' => array( 
						'label'=>'PANGKAT/GOL',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'eselon_mengetahui' => array( 
						'label'=>'ESELON',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
			'jabatan_mengetahui' => array( 
						'label'=>'JABATAN',
						'labelWidth'=>120, 
						'value'=>'', 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),	
							
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='c1nya' value='".$dt['c1']."' />".
			"<input type='hidden' name='cnya' value='".$dt['c']."' />".
			"<input type='hidden' name='dnya' value='".$dt['d']."' />".
			"<input type='hidden' name='enya' value='".$dt['e']."' />".
			"<input type='hidden' name='e1nya' value='".$dt['e1']."' />".
			"<input type='button' value='BARU' onclick ='pemasukan_ins.BaruPenerima(`#pemasukan_form`)' title='BARU' > ".
			"<input type='button' value='SIMPAN' onclick ='".$this->Prefix.".SimpanTTD()' title='SIMPAN' > ".
			"<input type='button' value='BATAL' title='BATAL' onclick ='".$this->Prefix.".TutupForm(`".$this->Prefix."_formcover_TTD`)' >";
							
		$form = $this->genForm($form_name);		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_satuan WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 300;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'nama' => array( 
						'label'=>'Satuan',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),			
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setPage_HeaderOther(){
	$setnya = '';
	$setnya1 = '';
	if($_REQUEST['halman'] == '1')$setnya = "style='color:blue' ";
	if($_REQUEST['halman'] == '2')$setnya1 = "style='color:blue' ";
	
	if(!isset($_REQUEST['halman']))$setnya = "style='color:blue' ";
	
	
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=pemasukan&halman=1\" title='PENGADAAN' $setnya >PENGADAAN</a> | 
	<A href=\"pages.php?Pg=pemasukan&halman=2\" title='PEMELIHARAAN' $setnya1 >PEMELIHARAAN</a> 
	&nbsp&nbsp&nbsp	
	</td></tr></table>";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		
		 $cek.= $this->BersihkanData();
	 $NomorColSpan = $Mode==1? 2: 1;
	 
	 $DISTRI = "DISTRI";
	 
	 if($_REQUEST['halmannya'] == '2')$DISTRI = 'KPTLS';
	 $headerTable =
	 // "</table><table id='header-fix' class='koptable' border='1' style='margin:4 0 0 0;width:100%'>"
	  "
	  <thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox		
		   <th class='th01' rowspan='2'>TANGGAL</th>
		   <th class='th01' rowspan='2'>NOMOR</th>
		   <th class='th01' rowspan='2'>NAMA BARANG</th>
		   <th class='th01' rowspan='2'>VOL</th>
		   <th class='th01' rowspan='2'>HARGA REKENING BELANJA</th>
		   <th class='th01' rowspan='2'>HARGA BELI BARANG</th>
		   <th class='th01' rowspan='2'>HARGA ATRIBUSI BARANG</th>
		   <th class='th01' rowspan='2'>HARGA PEROLEHAN BARANG</th>
		   <th class='th02' colspan='2'>STATUS</th>
		   <th class='th01' rowspan='2'>VALID</th>
		   <th class='th01' rowspan='2'>POST</th>
	   </tr>
	   <tr>
	   		<th class='th01'>ATRIB</th>
	   		<th class='th01'>$DISTRI</th>
	   </tr>
	   </thead>"
	   //"</table><table class='koptable' border='1' style='margin:4 0 0 0;width:100%'>"
	   ;
	 
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref, $DataOption, $DataPengaturan;
	 
	 
	 //TGL KONTRAK
	 $tgl_kontrak = explode("-",$isi['tgl_kontrak']);
	 $tgl_kontrak = $tgl_kontrak[2]."-".$tgl_kontrak[1]."-".$tgl_kontrak[0];
	 
	 //TGL DOKUMEN SUMBER 
	 $tgl_dokumen_sumber = explode("-",$isi['tgl_dokumen_sumber']);
	 $tgl_dokumen_sumber = $tgl_dokumen_sumber[2]."-".$tgl_dokumen_sumber[1]."-".$tgl_dokumen_sumber[0];
	
	
	 //TGL BUKU 
	 $tgl_buku = explode("-",$isi['tgl_buku']);
	 $tgl_buku = $tgl_buku[2]."-".$tgl_buku[1]."-".$tgl_buku[0];
				
	 		
	 //VALIDASI
	 $validnya = 'invalid.png';
	 if($isi['status_validasi'] == '1')$validnya = "valid.png";
		
	 $noakhir = $this->noakhirnya;
	 if($noakhir%2 == 0){
	 	$Koloms='<tr class="row1" >';
	 }else{
	 	$Koloms='<tr class="row0" >';
	 }
	 
	 $cssclass = 'GarisCetak';
	 if($Mode == 1)$cssclass = 'GarisDaftar';
	 
	 
	//QRY REKENING
	$qryrekening = "SELECT * FROM v1_penerimaan_barang_rekening WHERE refid_terima='".$isi['Id']."' AND sttemp='0'";
	$aqry_rekening = mysql_query($qryrekening);	
	$hitung_rekeningnya = mysql_num_rows($aqry_rekening);
	$xnya = 0;
	$harga_tot_rek = 0;
	 
	 
	//QRY PENERIMAAN RINCIAN
	$where_kptls = '';
	if($isi['jns_trans'] == 2)$where_kptls = "AND barangdistribusi='1' ";
	
	$qrypenerimaan_det = "SELECT * FROM ".$DataPengaturan->VPenerima_det()." WHERE refid_terima='".$isi['Id']."' AND sttemp='0' $where_kptls";
	$aqrypenerimaan_det = mysql_query($qrypenerimaan_det);
	$hitung_penerimaan_det = mysql_num_rows($aqrypenerimaan_det);
	$nhitung = 0;
	$harga_tot_pendet = 0;
	$tot_bar = 0;
	
	//JUMLAH Total Harga penerimaan_rincian
	$jml_qrypenerimaan_det = "SELECT SUM(harga_total) as total, SUM(jml) as jml_barangnya FROM ".$DataPengaturan->VPenerima_det()." WHERE refid_terima='".$isi['Id']."' AND sttemp='0'";
	$qryjml_penerimaan_det = mysql_query($jml_qrypenerimaan_det);
	$aqryjml_penerimaan_det = mysql_fetch_array($qryjml_penerimaan_det);
	
	//QRY ATRIBUSI
	$qry_atribusi = "SELECT * FROM v1_penerimaan_atribusi WHERE refid_terima='".$isi['Id']."'";
	$aqry_atribusi = mysql_query($qry_atribusi);
	$tot_atr = 0;
	//JUMLAH TOTAL Harga atribusi
	$jml_qry_atribusi = "SELECT SUM(TAR_jumlah) as jumlah FROM v1_penerimaan_atribusi WHERE refid_terima='".$isi['Id']."'";
	$jml_aqry_atribusi = mysql_query($jml_qry_atribusi);
	$aqryjml_aqry_atribusi = mysql_fetch_array($jml_aqry_atribusi);
	
	
	 // TDTR MULAI DISINI ------------------------------------------------------------------------
	 	if($isi['asal_usul'] == '1' || $isi['asal_usul'] == NULL || $isi['asal_usul'] == ''){
			$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
		 	if($Mode == 1)$Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
		}
	 	
		 
	 if($hitung_rekeningnya > 0){
	 	 
		 $Koloms.= "<td class='$cssclass' align='center' width='7%'>$tgl_kontrak</td>";
		 $Koloms.= "<td class='$cssclass' align='left' style='width:70px;'>".$isi['nomor_kontrak']."</td>";
	 }else{
	 	
		
		$noatri = 0;
		while($dt_atri = mysql_fetch_array($aqry_atribusi)){
		
			$tglsp2d_atri = explode("-",$dt_atri['tgl_sp2d']);
			$tglsp2d_atri = $tglsp2d_atri[2]."-".$tglsp2d_atri[1]."-".$tglsp2d_atri[0];
			
			$tgl_dok_sp2d='';
			$tgl_no_sp2d='';
			
			if($noatri != 0){
				$Koloms.= "<td class='$cssclass' align='center'></td>";
		 		if($Mode == 1)$Koloms.= "<td class='$cssclass' align='center'></td>";
			}else{
				$tgl_dok_sp2d=$tglsp2d_atri;
				$tgl_no_sp2d=$dt_atri['no_sp2d'];
			}
			
			$Koloms.= "<td class='$cssclass' align='center' width='7%'>$tgl_dok_sp2d</td>";
		 	$Koloms.= "<td class='$cssclass' align='left' style='width:70px;color:red;'>".$tgl_no_sp2d."</td>";
		 	$Koloms.= "<td class='$cssclass' align='left' width='50%'>".$dt_atri['nm_rekening']."</td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($dt_atri['TAR_jumlah'],2,',','.')."</td>";
			$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
		 	$Koloms.= "<td class='$cssclass' align='left'></td>";
	 		$Koloms.= "</tr>";
			$tot_atr = $tot_atr+ $dt_atri['TAR_jumlah'];
			$noatri++;
		}		
	 }
	
		
	//REKENING -----------------------------------------------------------------------------------	
	if($isi['asal_usul'] == '1'){
		
		 while($dt_rekening = mysql_fetch_array($aqry_rekening)){
		 	if($xnya != 0){
			$noakhir++;
				if($noakhir%2 == 0){
			 		$Koloms.='<tr class="row1" >';
				}else{
					$Koloms.='<tr class="row0" >';
				}
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 if($Mode == 1)$Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			} 
		 	 $Koloms.= "<td class='$cssclass' align='left'  width='50%'>".$dt_rekening['nm_rekening']."</td>";
			 $Koloms.= "<td class='$cssclass' align='left' style='width:50px;'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'>".number_format($dt_rekening['jumlah'],2,',','.')."</td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
		 	$Koloms.= "</tr>";
			$xnya++;
			$harga_tot_rek=$harga_tot_rek+$dt_rekening['jumlah'];
		 }
	}
	
	 
	//PENERIMAAN DETAIL ----------------------------------------------------------------------------------
	 //ATRIBUSI
	 $atribusi = 'TDK';
	 if($isi['biayaatribusi'] == '1')$atribusi='YA';
	 $total_harga_perolehan = 0;
	if($hitung_penerimaan_det > 0){	
		while($dt_penerimaan_det = mysql_fetch_array($aqrypenerimaan_det)){
			//DISTRIBUSI
			 $brgdistribusi = 'TDK';
			 if($dt_penerimaan_det['barangdistribusi'] == '1'){
			 	$qrytot_disti = "SELECT ifnull(SUM(jumlah),0) as jumlah FROM t_distribusi WHERE refid_terima='".$isi['Id']."' AND refid_penerimaan_det='".$dt_penerimaan_det['Id']."' AND sttemp='0' ";
				$aqrytot_disti = mysql_fetch_array(mysql_query($qrytot_disti));
				
				$warna_distri = '';
				if($aqrytot_disti['jumlah'] == $dt_penerimaan_det['jml'])$warna_distri = "style='color:black;' ";
			 	
				$jns_distri = "Distribusikan";
				if($isi['jns_trans'] == 2){
					$jns_distri ="Kapitalisasikan";
					$hrg_perolehan_cek = $DataPengaturan->HargaPerolehanAtribusi($isi['Id'],$dt_penerimaan_det['Id'] );
					//if($aqrytot_disti['jumlah'] == $dt_penerimaan_det['harga_total'])$warna_distri = "style='color:black;' ";
					if($aqrytot_disti['jumlah'] == $hrg_perolehan_cek)$warna_distri = "style='color:black;' ";
					$t=$aqrytot_disti['jumlah']." = ".$hrg_perolehan_cek;
				}
			 	$brgdistribusi='<a href="javascript:'.$this->Prefix.'.'.$jns_distri.'(`'.$isi['Id'].'`, `'.$dt_penerimaan_det['Id'].'`);" '.$warna_distri.'>YA</a>';
			}
							 
			$noakhir++;
			if($noakhir%2 == 0){
			 	$Koloms.='<tr class="row1" >';
			}else{
				$Koloms.='<tr class="row0" >';
			}
			
			 if($hitung_penerimaan_det > 1){
			 	if($nhitung == 0){
			 		$tgllan = $tgl_dokumen_sumber;
					$nomoran = $isi['no_dokumen_sumber'];
				}elseif($nhitung == 1){
					$tgllan = $tgl_buku;
					$nomoran = $isi['id_penerimaan'];
				}else{
					$tgllan = '';
					$nomoran = '';
				}
			 }else{
				$tgllan = $tgl_dokumen_sumber."<br>".$tgl_buku;
				$nomoran=$isi['no_dokumen_sumber']."<br>".$isi['id_penerimaan'];
			 }
			 
			 if($isi['jns_trans'] == '2'){
			 	$jumlah_barang = $dt_penerimaan_det['jml'] * $dt_penerimaan_det['kuantitas'];
			 }else{
			 	$jumlah_barang = $dt_penerimaan_det['jml'];
			 }
			 
			 //DATA POSTING
			 $postnya = 'invalid.png';
			 if($_REQUEST['halmannya'] == '1'){
			 	$qry_toBI = "SELECT COUNT(*) as cnt FROM buku_induk WHERE refid_terima='".$isi['Id']."' ";
				$aqry_toBI = mysql_query($qry_toBI);
				$daqry_toBI = mysql_fetch_array($aqry_toBI);
				
				if($daqry_toBI['cnt'] == $aqryjml_penerimaan_det['jml_barangnya'])$postnya = "valid.png";
				
			 }elseif($_REQUEST['halmannya'] == '2'){
			 	$qry_hit_pemeliharaan = $DataPengaturan->QryHitungData("pemeliharaan", "WHERE refid_terima='".$isi['Id']."' AND refid_terima_det='".$dt_penerimaan_det['Id']."' ");
			 	$qry_hit_t_distribusi = $DataPengaturan->QryHitungData("t_distribusi", "WHERE refid_terima='".$isi['Id']."' AND refid_penerimaan_det='".$dt_penerimaan_det['Id']."' AND sttemp='0' ");
				
				if($qry_hit_pemeliharaan['hasil'] == $qry_hit_t_distribusi['hasil'] && $qry_hit_t_distribusi['hasil'] != 0)$postnya = "valid.png";
			 }
			 
			 //HARGA PEROLEHAN ------------------------------------------------------------------------
			 $harga_perolehan = (intval($dt_penerimaan_det['harga_total'])/intval($aqryjml_penerimaan_det['total']))*(intval($aqryjml_aqry_atribusi['jumlah']))+intval($dt_penerimaan_det['harga_total']);
			 $total_harga_perolehan = $total_harga_perolehan + $harga_perolehan;
			 
			 //Jika Cara Perolehan Bukan Pengadaan
			 $isiNonya = '';
			 $TmplCekBox = '';
			 
			 if($isi['asal_usul'] != '1' && $nhitung == 0){
			 	$isiNonya = $no;
			 	$TmplCekBox = $TampilCheckBox;
			 }
			 
			 //
			 $Koloms.= "<td class='$cssclass' align='center'>$isiNonya</td>";
			 if($Mode == 1)$Koloms.= "<td class='$cssclass' align='center'>$TmplCekBox</td>";
			 $Koloms.= "<td class='$cssclass' align='center' valign='top'>$tgllan</td>";
			 $Koloms.= "<td class='$cssclass' align='left' valign='top'>$nomoran</td>";
			 
		 	 $Koloms.= "<td class='$cssclass' align='left' width='50%'><span style='margin-left:10px;'>".$dt_penerimaan_det['nm_barang']."</span></td>";
			 $Koloms.= "<td class='$cssclass' align='right' style='width:50px;'>".number_format($jumlah_barang,0,'.',',')."</td>";
			 //----- HARGA BARANG
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'>".number_format($dt_penerimaan_det['harga_total'],2,',','.')."</td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' >".number_format(round($harga_perolehan),2,',','.')."</td>";
			 //----- HARGA BARANG
			 $Koloms.= "<td class='$cssclass' align='center'>$atribusi</td>";
			 $Koloms.= "<td class='$cssclass' align='center'>$brgdistribusi</td>";
			 $Koloms.= "<td class='$cssclass' align='center'><img src='images/administrator/images/$validnya' width='20px' heigh='20px' /></td>";
			 $Koloms.= "<td class='$cssclass' align='center'><img src='images/administrator/images/$postnya' width='20px' heigh='20px' /></td>";
		 	$Koloms.= "</tr>";
			$nhitung++;
			$harga_tot_pendet = $harga_tot_pendet+$dt_penerimaan_det['harga_total'];
			$tot_bar=$tot_bar+$jumlah_barang;
			
			if($nhitung == $hitung_penerimaan_det){
				$qrybaru_atribusi = mysql_query($qry_atribusi);
				$no_atr_baru = 0;
				while($dtbaru_atribusi = mysql_fetch_array($qrybaru_atribusi)){
					$tglsp2d_atri_baru = explode("-",$dtbaru_atribusi['tgl_sp2d']);
					$tglsp2d_atri_baru = $tglsp2d_atri_baru[2]."-".$tglsp2d_atri_baru[1]."-".$tglsp2d_atri_baru[0];
					
					$tgl_dok_sp2d='';
					$tgl_no_sp2d='';
					
					if($no_atr_baru == 0){
						$tgl_dok_sp2d=$tglsp2d_atri_baru;
						$tgl_no_sp2d=$dtbaru_atribusi['no_sp2d'];
					}
					
									
					
					$Koloms.= "<td class='$cssclass' align='right'></td>";
				 	if($Mode == 1)$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='center' width='7%'>$tgl_dok_sp2d</td>";
				 	$Koloms.= "<td class='$cssclass' align='left' style='width:70px;'>".$tgl_no_sp2d."</td>";
				 	$Koloms.= "<td class='$cssclass' align='left' width='50%'>".$dtbaru_atribusi['nm_rekening']."</td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($dtbaru_atribusi['TAR_jumlah'],2,',','.')."</td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
				 	$Koloms.= "<td class='$cssclass' align='left'></td>";
			 		$Koloms.= "</tr>";
					$tot_atr = $tot_atr+ $dtbaru_atribusi['TAR_jumlah'];
					$no_atr_baru++;
				}
			}
		}	
	}
	
	//SUB TOTAL ---------------------------------------------------------------------------------------------------------
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	  if($Mode == 1)$Koloms.= "<td class='$cssclass' align='right'></td>";
	  $Koloms.= "<td class='$cssclass' align='right' colspan='3'><b>SUB TOTAL</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($tot_bar,0,'.',',')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($harga_tot_rek,2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($aqryjml_penerimaan_det['total'],2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($aqryjml_aqry_atribusi['jumlah'],2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($total_harga_perolehan,2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	
		 
	 	 /*$Koloms.= "<td class='$cssclass' align='center'><img src='images/administrator/images/$validnya' width='20px' heigh='20px' /></td>";
	 	 $Koloms.= "<td class='$cssclass' align='left'><input type='checkbox' name='posting' value='".$isi['Id']."' /></td>";
	
		$Koloms.= "<td class='$cssclass'></td>";*/
	 	
	 
	 $Koloms = array(
	 	array("Y", $Koloms),
	 );
	 	 
	 
	 /*$this->pid = $isi['Id'];*/
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS, $DataOption;
	 
	 $arr = array(
			//array('selectAll','Semua'),	
			array('selectSatuan','Satuan'),		
			);
		
	 //data order ------------------------------
	 $arrOrder = array(
			     	array('1','Satuan'),
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	
	if($DataOption['skpd'] == 1){
		$DataSKPD = WilSKPD_ajx3($this->Prefix.'SKPD2');
	}else{
		$DataSKPD = WilSKPD_ajxVW($this->Prefix.'SKPD');
	}
		
	
	$TampilOpt ="<input type='hidden' id='ver_skpd' value='".$DataOption['skpd']."' />".
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
				"<table width=\"100%\" class=\"adminform\">	<tr>		
				<td width=\"100%\" valign=\"top\">" . 
					 $DataSKPD. 
				"</td>
				<td valign='top'>" . 		
				"</td></tr>
				<tr><td>
					<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
				</td></tr>		
				</table>"
				;
			
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS, $DataOption;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		if($DataOption['skpd'] == 1){
					
			$cinput = $_COOKIE['cofmSKPD'];
			$dinput = $_COOKIE['cofmUNIT'];
			$einput = $_COOKIE['cofmSUBUNIT'];
			$e1input = $_COOKIE['cofmSEKSI'];
						
		}else{
			$c1input = $_REQUEST['pemasukanSKPDfmUrusan'];
			$cinput = $_REQUEST['pemasukanSKPDfmSKPD'];
			$dinput = $_REQUEST['pemasukanSKPDfmUNIT'];
			$einput = $_REQUEST['pemasukanSKPDfmSUBUNIT'];
			$e1input = $_REQUEST['pemasukanSKPDfmSEKSI'];
		}
		
		
		if($c1input != '' && $c1input != '00')$arrKondisi[] = "c1='$c1input'";
		if($cinput != '' && $cinput != '00')$arrKondisi[] = "c='$cinput'";
		if($dinput != '' && $dinput != '00')$arrKondisi[] = "d='$dinput'";
		if($einput != '' && $einput != '00')$arrKondisi[] = "e='$einput'";
		if($e1input != '' && $e1input != '000')$arrKondisi[] = "e1='$e1input'";
		
		if($_REQUEST['halmannya'] == '2'){
			$arrKondisi[] = " jns_trans ='2' ";
		}else{
			$arrKondisi[] = " jns_trans ='1' ";
		}
			
		
		
		//Cari 
		$arrKondisi[] = " sttemp ='0' ";
		//$arrKondisi[] = " sttemp_det ='0' ";
		
		switch($fmPILCARI){			
			case 'selectSatuan': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
		}	
		
		$arrOrders[] = " Id DESC " ;
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//$Order ="";
		//limit --------------------------------------
		/**$HalDefault=cekPOST($this->Prefix.'_hal',1);	//Cat:Settingan Lama				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		**/
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	function setPage_Header(){	
		global $DataPengaturan;	
		//return createHeaderPage($this->PageIcon, $this->PageTitle);
		return $DataPengaturan->createHeaderPage($this->PageIcon, $this->PageTitle,  
			'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
		);
	}
	
	function pageShow(){
		global $app, $Main; 
		
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		if(!isset($_REQUEST['halman']))$_REQUEST['halman']='1';
		return
		
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".			
		"<html>".
			$this->genHTMLHead().
			"<body >".
			/*"<div id='pageheader'>".$this->setPage_Header()."</div>".
			"<div id='pagecontent'>".$this->setPage_Content()."</div>".
			$Main->CopyRight.*/
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
				//header page -------------------		
				"<tr height='34'><td>".					
					//$this->setPage_Header($IconPage, $TitlePage).
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				//$this->setPage_HeaderOther().
				//Content ------------------------			
				//style='padding:0 8 0 8'
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
//					"<input type='hidden' name='pemasukanSKPDfmUrusan' value='".$_REQUEST['pemasukanSKPDfmUrusan']."' />".
					"<input type='hidden' name='halmannya' id='halmannya' value='".$_REQUEST['halman']."' />".
					
						//Form ------------------
						//$hidden.					
						//genSubTitle($TitleDaftar,$SubTitle_menu).						
						$this->setPage_Content().
						//$OtherInForm.
						
					$form2.//"</form>".
					"</div></div>".
				"</td></tr>".
				//$OtherContentPage.				
				//Footer ------------------------
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>
			
			".
			/*'<script src="assets2/js/bootstrap.min.js"></script>'.
			'<script src="assets2/jquery.min.js"></script>'.*/
			"</body>
		</html>"; 
	}
	
	function BersihkanData(){
		$cek='';
		//Penerimaan Rekening
		$hapusrek = "DELETE FROM t_penerimaan_rekening WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 2 HOURS) AND sttemp='1'"; $cek.="| ".$hapusrek;
		$qry_hapusrek = mysql_query($hapusrek);
		
		$updrek = "UPDATE t_penerimaan_rekening SET status='0' WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND sttemp='0'";$cek.="| ".$updrek;
		$qry_updrek = mysql_query($updrek);		
					
					
		//Penerimaan Detail -----------------------------------------------------------------------------------
		$hapuspenerimaan_det = "DELETE FROM t_penerimaan_barang_det WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 2 HOURS) AND sttemp='1'"; $cek.="| ".$hapuspenerimaan_det;
		$qry_hapuspenerimaan_det	= mysql_query($hapuspenerimaan_det);
		
		$updpenerimaan_det =  "UPDATE t_penerimaan_barang_det SET status='0' WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND sttemp='0'"; $cek.='| '.$updpenerimaan_det;
		
		$qry_updpenerimaan_det = mysql_query($updpenerimaan_det);		
					
		//Penerimaan ------------------------------------------------------------------------------------------
		$hapus_penerimaan = "DELETE FROM t_penerimaan_barang WHERE tgl_create < DATE_SUB(NOW(), INTERVAL 2 DAY) AND sttemp='1'"; $cek.="| ".$hapus_penerimaan;		
		$qry_hapus_penerimaan = mysql_query($hapus_penerimaan);
		
		//ATRIBUSI_RINCIAN
		$hapus_atribusiRincian = "DELETE FROM t_atribusi_rincian WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 2 HOURS) AND sttemp='1'"; $cek.="| ".$hapus_atribusiRincian;
		$qryhapus_atribusiRincian = mysql_query($hapus_atribusiRincian);
		
		$upd_atribusirincian = "UPDATE t_atribusi_rincian SET status='0' WHERE tgl_update < DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND sttemp='0'";$cek.="| ".$upd_atribusirincian;
		$qry_upd_atribusirincian = mysql_query($upd_atribusirincian);
		
		//ATRIBUSI	
		$hapus_atribusi = "DELETE FROM t_atribusi WHERE tgl_create < DATE_SUB(NOW(), INTERVAL 2 DAY) AND sttemp='1'"; $cek.="| ".$hapus_atribusi;		
		$qry_hapus_atribusi = mysql_query($hapus_atribusi);
		
		//DISTRIBUSI
		$hapus_distribusi = "DELETE FROM t_distribusi WHERE tgl_create < DATE_SUB(NOW(), INTERVAL 2 DAY) AND sttemp='1'"; $cek.="| ".$hapus_distribusi;		
		$qry_hapus_distribusi = mysql_query($hapus_distribusi);
		
		return $cek;
	}
	
	function Hapus($ids){ //validasi hapus ref_pegawai
		global $Main, $HTTP_COOKIE_VARS, $DataPengaturan;
		$UID = $_COOKIE['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		for($i = 0; $i<count($ids); $i++){
			$qry = "SELECT * FROM t_penerimaan_barang WHERE Id='".$ids[$i]."' ";
			$aqry = mysql_query($qry);
			$dt = mysql_fetch_array($aqry);
			
			if($err == '' && $dt['status_validasi'] == '1'){
				$err = "Data dengan ID PENERIMAAN `".$dt['id_penerimaan']."` Tidak Bisa Dihapus, Sudah di Validasi !";
				break;
			}
			
			$hit_diBI = $DataPengaturan->QryHitungData("buku_induk", "WHERE refid_terima='".$ids[$i]."'");
			if($err == '' && $hit_diBI['hasil'] > 0)$err = "Data dengan ID PENERIMAAN `".$dt['id_penerimaan']."` Tidak Bisa Dihapus, Sudah di Posting !";
			
			//if($err=='')$err='';
		}
		
		
		if($err == ''){
			for($i = 0; $i<count($ids); $i++){
				//Hapus Rekening
				$hapusrek = "DELETE FROM t_penerimaan_rekening WHERE refid_terima ='".$ids[$i]."'"; $cek.="| ".$hapusrek;
				$qry_hapusrek = mysql_query($hapusrek);
				//Hapus Penerimaan Detail
				$hapuspenerimaan_det = "DELETE FROM t_penerimaan_barang_det WHERE refid_terima ='".$ids[$i]."' "; $cek.="| ".$hapuspenerimaan_det;
				$qry_hapuspenerimaan_det = mysql_query($hapuspenerimaan_det);
				
				
				//Hapus Atribusi Rincian
				$hps_attr_det = "DELETE FROM t_atribusi_rincian WHERE refid_terima ='".$ids[$i]."'"; $cek.=" | ".$hps_attr_det;
				$qry_hps_attr_det = mysql_query($hps_attr_det);
				//Hapus Atribusi
				$hps_attr = "DELETE FROM t_atribusi WHERE refid_terima ='".$ids[$i]."'"; $cek.=" | ".$hps_attr;
				$qry_hps_attr = mysql_query($hps_attr);
				
				//Hapus Distribusi
				$hps_dstr = "DELETE FROM t_distribusi WHERE refid_terima ='".$ids[$i]."'"; $cek.=" | ".$hps_dstr;
				$qry_hps_dstr = mysql_query($hps_dstr);
				
				if(file_exists("Media/PENGADAAN/".$ids[$i].".png"))unlink("Media/PENGADAAN/".$ids[$i].".png");
				
				//Hapus Penerimaan
				$hapus_penerimaan = "DELETE FROM t_penerimaan_barang WHERE Id='".$ids[$i]."'"; $cek.="| ".$hapus_penerimaan;		
				$qry_hapus_penerimaan = mysql_query($hapus_penerimaan);
				
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function CetakPermohonan($xls= FALSE, $Mode=''){
		global $Main, $Ref, $HTTP_COOKIE_VARS, $DataPengaturan, $DataOption;
		$id=$_REQUEST['idnya'];
		$UID = $_COOKIE['coID']; 
		$namauid = $_COOKIE['coNama']; 
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
		
		$qry_pengaturan = "SELECT * FROM t_pengaturan WHERE Id = '1' ";
		$aqry_pengaturan = mysql_query($qry_pengaturan);
		$daqry_pengaturan = mysql_fetch_array($aqry_pengaturan);
		
		$id = addslashes($_REQUEST['idnya']);
		$qry = "SELECT * FROM t_penerimaan_barang WHERE Id='$id'";
		$aqry = mysql_query($qry);$daqry=mysql_fetch_array($aqry);
		
		$qry_det = "SELECT * FROM ".$DataPengaturan->VPenerima_det()." WHERE refid_terima='$id' AND sttemp='0'";
		$aqry_det = mysql_query($qry_det);
		
		
		$qry_rekening = "SELECT * FROM v1_penerimaan_barang_rekening WHERE refid_terima='$id' AND sttemp='0'";
		$aqry_rek = mysql_query($qry_rekening);
		
		//Nama SKPD
		$kodeTambahan='';
		if($DataOption['skpd'] != '1')$kodeTambahan = "c1='".$daqry['c1']."' AND";
		$qry_skpd = "SELECT nm_skpd FROM ref_skpd WHERE $kodeTambahan c='".$daqry['c']."' AND d='".$daqry['d']."'  AND e='".$daqry['e']."' AND e1='".$daqry['e1']."' ";
		$aqry_skpd = mysql_query($qry_skpd);$daqry_skpd = mysql_fetch_array($aqry_skpd);
		
		//$css = $this->cetak_xls	? 
		$gambarnya='';
		//if($aqry['asal_usul'] == '1'){
			$gambarnya = "<img src='Media/PENGADAAN/$id.png' width='100' height='100'/>";
		//}
		
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
					<style>
					.ukurantulisan{
						font-size:17px;
					}
					.ukurantulisan1{
						font-size:20px;
					}
					.ukurantulisanIdPenerimaan{
						font-size:16px;
					}
					</style>
				</head>".
			"<body >
			<form name='adminForm' id='pemasukanForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH;'>
			<table class=\"rangkacetak\" style='width:90%;font-family:Times New Roman;margin-left:2cm;margin-top:4cm;'>
			<tr><td valign=\"top\">
				<div style='text-align:center;'>
				<span style='font-size:18px;font-weight:bold;text-decoration: underline;'>
					SURAT PERMOHONAN VALIDASI INPUT DATA
				</span><br>
				<span class='ukurantulisanIdPenerimaan'>ID DATA : ".$daqry['id_penerimaan']."</span></div><br>
				<div class='ukurantulisan'>
					<table width='100%'>
						<tr>
							<td width='50%' class='ukurantulisan'>
								<b>Kepada Yth ;</b><br>
								".$daqry_pengaturan['nama_bidang']."<br>
								BPKAD<br>
								".$daqry_pengaturan['alamat']."<br>
								".$daqry_pengaturan['kota']."<br><br>
							</td>
							<td align='right'>$gambarnya
							</td>
						</tr>
					</table>
					
					Perihal : Permohonan Validasi Data
					<p style='text-align: justify;'>
					Bersama surat ini kami sampaikan bahwa <span style='text-transform: uppercase;'>".$daqry_skpd['nm_skpd']."</span> telah melakukan input data Pengadaan Barang pada aplikasi aset ".$daqry_pengaturan['nama_aplikasi']." dengan rincian data barang sebagai berikut :</p>
					<table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
						<tr>
							<th class='th01'>No</th>
							<th class='th01'>Nama Barang</th>
							<th class='th01'>Merk/Type/Spek/Judul/Lokasi</th>
							<th class='th01'>Jml</th>
							<th class='th01'>Jumlah Harga (Rp)</th>
						</tr>
				";
				$no = 1;
				$jumtot = 0;
				$jumtothar = 0;
				while($daqry_det=mysql_fetch_array($aqry_det)){
					echo "
						<tr>
							<td align='right' class='GarisCetak'>$no</td>
							<td class='GarisCetak'>".$daqry_det['nm_barang']."</td>
							<td class='GarisCetak'>".$daqry_det['ket_barang']."</td>
							<td align='right' class='GarisCetak'>".number_format($daqry_det['jml'],0,".",",")."</td>
							<td align='right' class='GarisCetak'>".number_format($daqry_det['harga_total'],2,",",".")."</td>
						</tr>
					"; 
					$no++;
					$jumtot=$jumtot+intval($daqry_det['jml']);
					$jumtothar=$jumtothar+$daqry_det['harga_total'];
				}
				
				echo "
						<tr>
							<th colspan='3' class='GarisCetak'>Total</th>
							<th align='right' class='GarisCetak'>".number_format($jumtot,0,".",",")."</th>
							<th align='right' class='GarisCetak'>".number_format($jumtothar,2,",",".")."</th>
						</tr>
					</table>
				<br>
				
				<table width='100%' class='cetak' style='margin:4 0 0 0;width:100%;font-size: 12px;' width='96%'>
					<tr>
						<td colspan='4' style='font-size:13;'>Rekening Belanja :</td>
					</tr>
				";
				$do = 1;
				$totrek=0;
				while($daqry_rek = mysql_fetch_array($aqry_rek)){
					$koderekening = $daqry_rek['k'].".".$daqry_rek['l'].".".$daqry_rek['m'].".".$daqry_rek['n'].".".$daqry_rek['o'];	
					
				echo 
					"<tr>
						<td>$do. </td>
						<td>$koderekening; </td>
						<td>".$daqry_rek['nm_rekening']." </td>
						<td align='right'> &nbsp Rp ".number_format($daqry_rek['jumlah'],2,",",".")." </td>
					";
					$do++;
					$totrek=$totrek+intval($daqry_rek['jumlah']);
				}
				
				//NO KONTRAK
				$tglkontraknya = explode("-",$daqry['tgl_kontrak']);
				$tglkontrak = $tglkontraknya[2].'-'.$tglkontraknya[1].'-'.$tglkontraknya[0];
				
				//DOKUMEN SUMBER
				
				/*switch($daqry['dokumen_sumber']){
					case "1":$dokumensumber = "BAST";break;
					case "2":$dokumensumber = "BAKF";break;
					case "3":$dokumensumber = "BA HIBAH";break;
					case "4":$dokumensumber = "SURAT KEPUTUSAN";break;
				}*/
				
				$tgl_dokumen_sumbernya = explode("-",$daqry['tgl_dokumen_sumber']);
				$tgl_dokumen_sumber = $tgl_dokumen_sumbernya[2].'-'.$tgl_dokumen_sumbernya[1].'-'.$tgl_dokumen_sumbernya[0];
				
				// Tanggal Cetak
				$tgl_cetak = explode("-",date('d-m-Y'));
				$hit_bln = intval($tgl_cetak[1]) - 1;
				$tgl_cetak = $tgl_cetak[0]." ".$Ref->NamaBulan[$hit_bln]." ".$tgl_cetak[2];
				$gmbrcheck = "<img src='datepicker/checkbox.png' width='11px' height='11px' />";
				
				$titik = '';
				for($rw = 0;$rw<100;$rw++){
					$titik.='.';
				}
				echo "
					<tr>
						<th align='right' colspan='3'>Total</th>
						<th align='right'> &nbsp Rp ".number_format($totrek,2,",",".")."</th>
					</tr>
				</table>
				
				Kelengkapan Dokumen :<br>
				<div style='margin-left:30px;'>$gmbrcheck Dokumen Kontrak;  Nomor : ".$daqry['nomor_kontrak']." Tanggal : ".$tglkontrak."</div>
				<div style='margin-left:30px;'>$gmbrcheck ".$daqry['dokumen_sumber']."; Nomor : ".$daqry['no_dokumen_sumber']." Tanggal : $tgl_dokumen_sumber </div>
				<div style='margin-left:30px;'>$gmbrcheck Faktur/Kwitansi Pembelian</div>
				
				<div style='text-align:justify;'>
				Demikian Surat Permohonan ini kami buat dengan sebenarnya, agar dapat digunakan sebagaimana mestinya. </div><br><br>
				
				".$daqry_pengaturan['titimangsa_surat']."; ".$tgl_cetak."<br>
				
					<table width='100%'>
						<tr>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>a/n Penerima Barang;<br><br><br><br><br></span></td>
							<td class='ukurantulisan' valign='top' width='30%' ></td>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>a/n Petugas Validasi Data;</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>$namauid</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>Username</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>$UID</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan'></td>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>............................</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>NIP</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>............................</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				
					<table width='100%'>
						<tr>
							<td class='ukurantulisan'  >
								<table>
									<tr>
										<td class='ukurantulisan'>Tgl</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>Status</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>diterima/ditolak (*)</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>Catatan</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'></td>
									</tr>
									<tr>
										<td colspan='3'>
										$titik
										$titik
										$titik
										$titik
										</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan' valign='top' width='40%' ></td>
						</tr>
					</table>
				
				<p style='font-style: italic; font-size:14px;'>(*) Coret yang tidak sesuai</p>
				</div>
				
				".
				//$this->cetak_xls.		
				//$this->setCetak_HeaderBA($Mode,2).//$this->Cetak_Header.//
				
				"<div id='cntTerimaKondisi'>".
				"</div>
				<div id='cntTerimaDaftar' >";
			
				
		echo	"</div>	".			
				//$this->setcetakfooterformat($xls).
				
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}
	
	function Validasi($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 110;
	 $this->form_caption = 'VALIDASI PENGADAAN';
	  if ($dt['status_validasi'] == '1') {
	  	//2017-03-30 17:12:16
		$tglvalidnya = $dt['tgl_validasi'];
		$thn1 = substr($tglvalidnya,0,4); 
		$bln1 = substr($tglvalidnya,5,2); 
		$tgl1 = substr($tglvalidnya,8,2); 
		$jam1 = substr($tglvalidnya,11,8);
		
		$tglvalid = $tgl1."-".$bln1."-".$thn1." ".$jam1;
		
		$checked = "checked='checked'";			
	  }else{			
		$tglvalid = date("d-m-Y H:i:s");
		$checked = "";	
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'id_penerimaan' => array( 
						'label'=>'ID PENERIMAAN',
						'labelWidth'=>100, 
						'value'=>$dt['id_penerimaan'], 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),
			'tgl_validasi' => array( 
						'label'=>'TANGGAL',
						'labelWidth'=>100, 
						'value'=>$tglvalid, 
						'type'=>'text',
						'param'=>"style='width:125px;' readonly"
						 ),
			'validasi' => array( 
						'label'=>'VALIDASI DATA',
						'labelWidth'=>100, 
						'value'=>"<input type='checkbox' name='validasi' $checked style='margin-left:-1px;' />",
						 ),					
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormPosting(){
		global $DataPengaturan;
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;				
		//get data 
		$Id = $cbid[0];
		
		$qry = "SELECT * FROM t_penerimaan_barang WHERE Id ='$Id'";$cek.=" | ".$qry;
		$aqry = mysql_query($qry);
		$dt = mysql_fetch_array($aqry);
		
		//CEK APAKAH jns_trans = PEMELIHARAAN
		if($dt['jns_trans'] == '2')$err=$this->CekKapitalisasiSesuai($Id);
		if($err == '' && $dt['status_validasi'] !=  '1')$err="Data Harus Terlebih Dahulu di Validasi !";
		
		if($err==''){
			$qry2 = "SELECT Id, jml FROM t_penerimaan_barang_det WHERE refid_terima = '$Id' ";$cek.=" | ".$qry2;
			$aqry2 = mysql_query($qry2);
			
			$dt['barangnya'] = array();
			$nomor = 0;
			
			if($dt['jns_trans'] == '1'){
			//PENGADAAN ----------------------------------------------------------------------------------------
				$dt['jml_brgnya']= 0;
				while($dt2 = mysql_fetch_array($aqry2)){
					$dt['barangnya'][$nomor] = "
						<input type='hidden' name='idbarangnya[]' id='idbarangnya_$nomor' value='".$dt2['Id']."' />
						<input type='hidden' name='jmlbarangnya_".$dt2['Id']."' id='jmlbarangnya_".$dt2['Id']."' value='".$dt2['jml']."' />
					";
					$nomor++;
					$dt['jml_brgnya']=$dt['jml_brgnya']+$dt2['jml'];
				}
				
				$qry_cekBI = "SELECT noreg FROM buku_induk WHERE refid_terima = '$Id'";
				$aqry_cekBI = mysql_query($qry_cekBI);
				$jml_BI = mysql_num_rows($aqry_cekBI);
				
				if($jml_BI > 0){
					$dt['persen'] = floor(($jml_BI / $dt['jml_brgnya']) * 100);
					$dt['ceklisbaru'] = "checked";
				}else{
					$dt['persen'] = 0;
					$dt['ceklisbaru'] = "";
				}
			}else{
			//PEMELIHARAAN ----------------------------------------------------------------------------------------	
			
				$qry_kptls = "SELECT * FROM t_distribusi WHERE refid_terima='$Id' AND sttemp='0'";
				$aqry = mysql_query($qry_kptls);
				
				while($dt2 = mysql_fetch_array($aqry)){
					$selesai = '';
					$cek_pmlhrn = $DataPengaturan->QryHitungData('pemeliharaan', "WHERE refid_terima='$Id' AND refid_terima_det='".$dt2['refid_penerimaan_det']."' AND id_bukuinduk='".$dt2['refid_buku_induk']."'");
					if($cek_pmlhrn['hasil'] > 0)$selesai = "<input type='hidden'  name='status_pmlhrn_$nomor' id='status_pmlhrn_$nomor' value='selesai' />";
					
					$dt['barangnya'][$nomor] = "
						<input type='hidden' name='idKPTLS_$nomor' id='idKPTLS_$nomor' value='".$dt2['Id']."' />
						$selesai
					";
					$nomor++;
				}
				
				$dt['jml_brgnya'] = mysql_num_rows($aqry);
				
				$hit_pemeliharaan = $DataPengaturan->QryHitungData('pemeliharaan', "WHERE refid_terima='$Id'");
				
				if($hit_pemeliharaan['hasil'] > 0){
					$dt['persen'] = floor(($hit_pemeliharaan['hasil'] / $dt['jml_brgnya']) * 100);
					$dt['ceklisbaru'] = "checked";$cek.=$dt['persen'];
				}else{
					$dt['persen'] = 0;
					$dt['ceklisbaru'] = "";
				}
			}
			
			
			if($err == '')$fm = $this->setPosting($dt);
		}
		
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
	
		
	function setPosting($dt){	
	 global $SensusTmp, $DataPengaturan;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 180;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'POSTING DATA';
		//$nip	 = '';
		$disabled = FALSE;
	  }else{
		$this->form_caption = 'Edit';			
		$Id = $dt['id'];
		$disabled= TRUE;			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
		$BARANGNYA = '';
		for($i=0;$i<=count($dt['barangnya']);$i++){
			$BARANGNYA .= $dt['barangnya'][$i];
		}
		
	 //items ----------------------
	  $this->form_fields = array(
			'idpenerimaan' => array(
						'label'=>'',
						'labelWidth'=>1, 
						'pemisah'=>' ', 
						'value'=>
							$DataPengaturan->isiform(
								array(
									array(
										'label'=>'ID PENERIMAAN',
										'name'=>'idpenerimaan',
										'label-width'=>'120px;',
										'type'=>'text',
										'value'=>$dt['id_penerimaan'],
										'align'=>'left',
										'parrams'=>"style='width:200px;' readonly",
									),
									array(
										'label'=>'TANGGAL',
										'name'=>'tgl',
										'label-width'=>'120px;',
										'type'=>'text',
										'value'=>date('d-m-Y'),
										'align'=>'left',
										'parrams'=>"style='width:80px;' readonly",
									),
									array(
										'label'=>'JUMLAH BARANG',
										'name'=>'jml_brg',
										'label-width'=>'120px;',
										'type'=>'text',
										'value'=>number_format($dt['jml_brgnya'],0,'.',','),
										'align'=>'left',
										'parrams'=>"style='width:80px;text-align:right;' readonly",
									),
									array(
										'label'=>'POSTING DATA ?',
										'label-width'=>'120px;',
										'value'=>"<input type='checkbox' name='posting' style='margin-left:-1px;' id='posting' value='postingkan' ".$dt['ceklisbaru']." />
										",
										'align'=>'left',
									),
								), 'style="margin-left:-20px;"'
							)
						 ),
			'progress' => array(
				'label'=>'',
				'labelWidth'=>1, 
				'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox' style='background:#fffbf0;border-radius:5px;border:1px solid;height:10px;margin-left:-20px;'>
						<div id='progressbar'></div >
						<div id='statustxt' style='width:".$dt['persen']."%;background:green;height:10px;text-align:right;color:white;font-size:8px;'>".$dt['persen']."%</div>						
						<div id='output'></div>
					</div>	"
				),
			'peringatan' => array( 
						'label'=>'',
						'labelWidth'=>1, 
						'pemisah'=>' ',
						'value'=>"<div id='pemisah' style='color:red;font-size:11px;'></div>",
				),
			);
		//tombol
		$this->form_menubawah =
			$BARANGNYA.
			"<input type='hidden' name='tot_jmlbarang' id='tot_jmlbarang' value='".$dt['jml_brgnya']."'>".
			"<input type='hidden' name='c1' id='c1' value='".$dt['c1']."'>".
			"<input type='hidden' name='c' id='c' value='".$dt['c']."'>".
			"<input type='hidden' name='d' id='d' value='".$dt['d']."'>".
			"<input type='hidden' name='e' id='e' value='".$dt['e']."'>".
			"<input type='hidden' name='e1' id='e1' value='".$dt['e1']."'>".
			"<input type='hidden' name='tahun' id='tahun' value='".$dt['tahun']."'>".
			"<input type='button' value='Posting' onclick ='".$this->Prefix.".SimpanPosting()' title='Posting' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function PengecekanPosting(){
		global $DataPengaturan;
		$cek ='';$err='';$content=array();
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$Idplh = $_REQUEST['pemasukan_idplh'];
		$posting = $_REQUEST['posting'];
		$jml_brg = $_REQUEST['tot_jmlbarang'];
		
		$cekJnsTrans = $DataPengaturan->QyrTmpl1Brs($this->TblName, "jns_trans", "WHERE Id='$Idplh'");
		$qryJnsTrans = $cekJnsTrans['hasil'];
		
		$content['jns_trans'] = $qryJnsTrans['jns_trans'];
		
		IF($qryJnsTrans['jns_trans'] == '1'){
		// Posting Pengadaan Barang -----------------------------------------------------------------------------------
			$qry_cekBI = "SELECT * FROM buku_induk WHERE refid_terima='$Idplh'";$cek.=$qry_cekBI;
			$aqry_cekBI = mysql_query($qry_cekBI);
			$jml_data_BI = mysql_num_rows($aqry_cekBI);
		
		//CEK KE Apakah ada data yg di Distribusi ---------------------------------------------------------------------	
			if(isset($_REQUEST['posting'])){
				if($DataPengaturan->CekDistribusi == 1){
					//Hitung Di t_penerimaan_barang_det 
					$qry_pendet =$DataPengaturan->QyrTmpl1Brs('t_penerimaan_barang_det', "IFNULL(SUM(jml),0) as jml_brg", "WHERE refid_terima='$Idplh' AND barangdistribusi='1' AND sttemp='0'");
					$aqry_pendet = $qry_pendet['hasil'];
					
					$qry_Distri = $DataPengaturan->QyrTmpl1Brs("t_distribusi", "IFNULL(SUM(jumlah),0) as jml_brg", "WHERE refid_terima='$Idplh' AND sttemp='0'");
					$aqry_Distri = $qry_Distri['hasil'];
					
					if($aqry_Distri['jml_brg'] != $aqry_pendet['jml_brg'] && $err=='')$err='Data Barang Yang Didistribusikan, Belum Selesai di masukan !';
					
				}
				if($err == ''){
					if($posting == "postingkan"){
						if($jml_data_BI > 0){
							if($jml_data_BI >= $jml_brg){
								$content['konfirmasi'] = "Data Sudah Di Posting !";
								$content['NoPosting'] = "1";
							}else{
								$content['konfirmasi'] = "Lanjutkan Posting Data ?";
							}
							
						}else{
							$content['konfirmasi'] = "Data Akan Di Posting ?";
						}
						
						$qry_Penerimaan = "SELECT * FROM t_penerimaan_barang WHERE Id='$Idplh'";
						$aqry_Penerimaan = mysql_query($qry_Penerimaan);
						$daqry_Penerimaan = mysql_fetch_array($aqry_Penerimaan);
						
						if($daqry_Penerimaan['biayaatribusi'] == '1'){
							$qry_attribusi = "SELECT * FROM t_atribusi WHERE refid_terima='$Idplh' AND sttemp='0' ";
							$aqry_attribusi = mysql_query($qry_attribusi);
							if(mysql_num_rows($aqry_attribusi) < 1)$content['err'] = "Biaya Atribusi Belum Di Masukan !";
						}
					
					}
					
				}
			}else{
				if($jml_data_BI > 0){
					$content['konfirmasi'] = "Batalkan Posting Data ?";
				}else{
					$err = "Posting Data, Belum Diceklis !";
				}
			}	
		}else{
			// POSTING PEMELIHARAAN -------------------------------------------------------------------------------------
			$hit_pemeliharaan = $DataPengaturan->QryHitungData("pemeliharaan aa LEFT JOIN buku_induk bi ON aa.id_bukuinduk=bi.id", "WHERE aa.refid_terima='$Idplh'");
			if(isset($_REQUEST['posting'])){
				//Hitung biaya_pemeliharaan di pemeliharaan
				$qry_pemeliharaan = $DataPengaturan->QyrTmpl1Brs("pemeliharaan", "SUM(biaya_pemeliharaan) as tot_biaya", "WHERE refid_terima='$Idplh'");
				$aqry_pemeliharaan = $qry_pemeliharaan['hitung'];
				
				//Hitung Biaya Pemeliharaan Di t_penerimaan_barang_det + t_atribusi
				$qry_pmlhrn_distr = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_barang_det", "SUM(harga_total) as hrg_total", "WHERE refid_terima='$Idplh' and sttemp='0' and barangdistribusi='1' ");
				$aqry_pmlhrn_distr = $qry_pmlhrn_distr['hasil'];
				
				//Hitung Biaya Pemeliharaan Yang sudah dikapitalisasi
				$qry_atribusi = $DataPengaturan->QyrTmpl1Brs("t_atribusi_rincian", "SUM(jumlah) as hrg_atribusi ", "WHERE refid_terima='$Idplh' and sttemp='0'");
				$aqry_atribusi = $qry_atribusi['hasil'];
				
				$total_harga_perolehan = round($aqry_pmlhrn_distr['hrg_total'] + $aqry_atribusi['hrg_atribusi']);
				
				$err = $this->CekKapitalisasiSesuai($Idplh);
				
				if($err == ''){
					if($hit_pemeliharaan['hasil'] > 0){
						if($hit_pemeliharaan['hasil'] >= $total_harga_perolehan){
							$content['konfirmasi'] = "Data Sudah Di Posting !";
							$content['NoPosting'] = "1";
						}else{
							$content['konfirmasi'] = "Lanjutkan Posting Data ?";
						}
								
					}else{
						$content['konfirmasi'] = "Data Akan Di Posting ?";
					}
				}				
				
				//Cek Atribusi -------------------------------------------------------------------------------------
				$content['err'] = $DataPengaturan->CekBiayaAtribusi($Idplh);
			}else{
				if($hit_pemeliharaan['hasil'] > 0){
					$content['konfirmasi'] = "Batalkan Posting Data ?";
				}else{
					$err = "Posting Data, Belum Diceklis !";
				}
			}
			
			
		}		
				
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function HargaRekeningBelanja($WHEREnya){
		//Harga Beli Barang Satuan ---------------------------------------------------------------------------------------
		$qry_rekening = "SELECT IFNULL(SUM(jumlah),0) as hrg_rek FROM t_penerimaan_rekening $WHEREnya ";
		$aqry_rekening = mysql_query($qry_rekening);
		$daqry_rekening = mysql_fetch_array($aqry_rekening);
		
		return $daqry_rekening;
	}
	
	function ProsesPosting(){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		$thn_login = $_COOKIE['coThnAnggaran']; 
		
		
		$cek ='';$err='';$content=array();
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$IdPenDet = addslashes($_REQUEST['IdPenDet']);
		$JMLBRGNY = addslashes($_REQUEST['JMLBRGNY']);
		
		$pemasukan_idplh = $_REQUEST['pemasukan_idplh'];
		
		//Tampil Penerimaan Barang Detail
		$qry_pen_det = "SELECT * FROM t_penerimaan_barang_det WHERE Id='$IdPenDet'";$cek.=' | '.$qry_pen_det;
		$aqry_pen_det = mysql_query($qry_pen_det);
		$daqry_pen_det = mysql_fetch_array($aqry_pen_det);
		
		//Tampil Penerimaan Barang
		$qry_pen = "SELECT tgl_dokumen_sumber, no_dokumen_sumber, asal_usul, tgl_buku, bk,ck,dk,p,q,biayaatribusi, sumber_dana  FROM t_penerimaan_barang WHERE Id='$pemasukan_idplh' ";$cek.=" | ".$qry_pen;
		$aqry_pen = mysql_query($qry_pen);
		$daqry_pen = mysql_fetch_array($aqry_pen);
		
		//SET DATA-DATA
		$Data_a = $Main->DEF_PROPINSI;	
		$Data_a1 = $Main->DEF_KEPEMILIKAN;	
		$Data_b = $Main->DEF_WILAYAH;
		$noregMax = $Main->NOREG_MAX;
		$kodebelanja_Modal = $Main->KODE_BELANJA_MODAL;
		$fd_blnj_modal = $Main->FIELD_KODE_BELANJA_MODAL;
		$thn_BAST = substr($daqry_pen['tgl_dokumen_sumber'],0,4);
		$tgl_dokumen_sumber = $daqry_pen['tgl_dokumen_sumber'];	
		$no_BAST = $daqry_pen['no_dokumen_sumber']; 
		$asal_usul = $daqry_pen['asal_usul']; 
		$tgl_buku_anggaran = $daqry_pen['tgl_buku']; 
		$thn_anggaran = substr($tgl_buku_anggaran,0,4);
		$bk = $daqry_pen['bk']; 
		$ck = $daqry_pen['ck']; 
		$dk = $daqry_pen['dk']; 
		$p = $daqry_pen['p']; 
		$q = $daqry_pen['q'];
		$sumber_dana = $daqry_pen['sumber_dana'];
		
		//Cek Untuk Cara Perolehan
		$cek_kodeBm = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_rekening", "COUNT(*) as hitung", "WHERE refid_terima='$pemasukan_idplh' AND ".$Main->FIELD_KODE_BELANJA_MODAL." = '".$Main->KODE_BELANJA_MODAL."' AND sttemp='0' ");$cek.=$cek_kodeBm['cek'];
		$hsl_cek_Bm = $cek_kodeBm['hasil'];
			
		$cek_kodeBm2 = $DataPengaturan->QyrTmpl1Brs("t_atribusi_rincian", "COUNT(*) as hitung", "WHERE refid_terima='$pemasukan_idplh' AND ".$Main->FIELD_KODE_BELANJA_MODAL." = '".$Main->KODE_BELANJA_MODAL."' AND sttemp='0' ");$cek.=$cek_kodeBm2['cek'];
		$hsl_cek_Bm2 = $cek_kodeBm2['hasil'];
			
		if($hsl_cek_Bm['hitung'] < 1 || $hsl_cek_Bm2['hitung'] < 1){
			$asal_usul = "6";
		}
		
		 
		
		$tgl_buku = date('Y-m-d'); 
		
		if(intval($thn_anggaran) < intval($thn_login))$tgl_buku = $thn_anggaran."-12-31"; 	
		
		//Ambil Harga Total Semua Barang		
		$qry_allhrg = $DataPengaturan->QyrTmpl1Brs($DataPengaturan->VPenerima_det(), "IFNULL(SUM(harga_total),0) as total", " WHERE refid_terima='$pemasukan_idplh' AND sttemp='0'");
		$daqry_allhrg_brg = $qry_allhrg['hasil'];
		
		$harga_total_barang = $daqry_allhrg_brg['total'];
		
		if($daqry_pen['biayaatribusi'] > '0'){
			//Ambil Harga Total Biaya Atribusi			
			$qry_attr = $DataPengaturan->QyrTmpl1Brs("v1_penerimaan_atribusi", "IFNULL(SUM(TAR_jumlah),0) as jumlah", "WHERE refid_terima='$pemasukan_idplh' AND sttemp='0' ");$cek.=$qry_attr['cek'];
			$daqry_attr = $qry_attr['hasil'];
			
			$biayaAtribusi = $daqry_attr['jumlah'];
		}else{
			$biayaAtribusi = 0;
		}
		
		$harga_perolehan = ((($daqry_pen_det['harga_total']/$daqry_allhrg_brg['total']) *  $biayaAtribusi) + $daqry_pen_det['harga_total']);
		$harga_perolehan = round($harga_perolehan);
		
		$harga_perolehan_satuan = $harga_perolehan / $daqry_pen_det['jml'] ;
		$harga_perolehan_satuan = round($harga_perolehan_satuan) ;
		
		$harga_perolehan_satuan_akhir = $harga_perolehan_satuan;
		
		//Untuk Pengecekan
		$harga_baru =$harga_perolehan_satuan * $daqry_pen_det['jml'];
		$harga_baru =round($harga_baru);
		
		
		if($harga_baru > $harga_perolehan){
			$harga_perolehan_satuan_akhir = $harga_baru - $harga_perolehan;
			$harga_perolehan_satuan_akhir = $harga_perolehan_satuan-$harga_perolehan_satuan_akhir;
		}elseif($harga_baru < $harga_perolehan){
			//$harga_perolehan_satuan_akhir = $harga_perolehan - $harga_baru;
			//$harga_perolehan_satuan_akhir = round(($harga_perolehan_satuan+$harga_perolehan_satuan_akhir),2);
			$harga_perolehan_satuan_akhir = $harga_perolehan_satuan * ($daqry_pen_det['jml']-1);
			$harga_perolehan_satuan_akhir = $harga_perolehan - $harga_perolehan_satuan_akhir;
		}
		
		
		$harga_perolehan_satuan_akhir = round($harga_perolehan_satuan_akhir);
		
		// STATUS ASET -----------------------------------------------------------------------------------------------
		$Get_staset = getStatusAset(3,1,$harga_perolehan_satuan,$daqry_pen_det['f'],$daqry_pen_det['g'],$daqry_pen_det['h'],$daqry_pen_det['i'],$daqry_pen_det['j'],$thn_BAST);
		
		
		$cek.="| Harga = ".$harga_perolehan_satuan." ".$harga_perolehan." ".$harga_perolehan_satuan_akhir;
		$cek.="| StAset = ".$Get_staset;
		
		
		
		//Harga Beli Barang Satuan ---------------------------------------------------------------------------------------
		$harga_barang_propos = ($daqry_pen_det['harga_total']/$daqry_allhrg_brg['total']);
		
		$daqry_rekening = $this->HargaRekeningBelanja("WHERE refid_terima='$pemasukan_idplh' AND $fd_blnj_modal = '$kodebelanja_Modal' AND sttemp='0' ");
		
		$hrg_BM_attr = $DataPengaturan->QyrTmpl1Brs('v1_penerimaan_atribusi',"IFNULL(SUM(TAR_jumlah),0) as jumlah","WHERE refid_terima='$pemasukan_idplh' AND $fd_blnj_modal = '$kodebelanja_Modal' AND sttemp='0' ");
		$hrg_BM_attri = $hrg_BM_attr['hasil'];
		
		$hrg_BM = $hrg_BM_attri['jumlah']+$daqry_rekening['hrg_rek'];
		
		$harga_beli_barang = $harga_barang_propos *  $hrg_BM;
		$harga_beli_barang = round($harga_beli_barang);
		$harga_beli_barang = $harga_beli_barang / $daqry_pen_det['jml'] ;
		$harga_beli_barang = round($harga_beli_barang);
		
		
		
		//harga_atribusi ------------------------------------------------------------------------------------------------
		$daqry_rekening2 = $this->HargaRekeningBelanja("WHERE refid_terima='$pemasukan_idplh' AND $fd_blnj_modal != '$kodebelanja_Modal' AND sttemp='0' ");
		
		$hrg_BBM_attr = $DataPengaturan->QyrTmpl1Brs('v1_penerimaan_atribusi',"IFNULL(SUM(TAR_jumlah),0) as jumlah","WHERE refid_terima='$pemasukan_idplh' AND $fd_blnj_modal != '$kodebelanja_Modal' AND sttemp='0' ");
		$hrg_BBM_attri = $hrg_BBM_attr['hasil'];
		
		$hrg_BBM = $hrg_BBM_attri['jumlah']+$daqry_rekening2['hrg_rek'];
		
		$harga_atribusi_barang_rek = $harga_barang_propos *  $hrg_BBM;
		$harga_atribusi_barang = round($harga_atribusi_barang);	
		$harga_atribusi_barang = ($harga_atribusi_barang_rek + $harga_atribusi_barang_attr) / $daqry_pen_det['jml'];
		$harga_atribusi_barang = round($harga_atribusi_barang);	
		
		//HARGATOTAL PEROLEHAN
		$HargaTotalPengadaan = $this->HargaTotalPengadaan($pemasukan_idplh, $daqry_pen['biayaatribusi']);
		$cek.=$HargaTotalPengadaan[1];
		$HargaSemuaPengadaan= $HargaTotalPengadaan[0];
		
		//KE BUKU INDUK --------------------------------------------------------------------------------------------------
		
		$qry_toBI = "SELECT sf_penerimaan_posting('$IdPenDet', '$thn_BAST', '$harga_perolehan_satuan', '$harga_perolehan_satuan_akhir', '$Get_staset', '$Data_a', '$Data_a1', '$Data_b', '$tgl_dokumen_sumber', '$no_BAST', '$asal_usul', '$tgl_buku', '$JMLBRGNY', '0', $harga_beli_barang, $harga_atribusi_barang, '$bk', '$ck', '$dk', '$p', '$q', '$thn_anggaran','$UID', $noregMax, '$sumber_dana', '$HargaSemuaPengadaan') as hasil";$cek.=" | ".$qry_toBI;
		$aqry_toBI = mysql_query($qry_toBI);
		if(!$aqry_toBI && $err=='')$err='Gagal Memposting Data !';
		$daqry_toBI = mysql_fetch_array($aqry_toBI);
		
		$isi_toBI = explode(" ", $daqry_toBI['hasil']);
		if($isi_toBI[0] == "NEXT")$content['Langsung'] = "1";
		$content['BarangSudahProses'] = $isi_toBI[1];$cek.=" | ".$isi_toBI[0];
		
				
		return	array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function HapusPosting(){
		global $DataPengaturan,$HTTP_COOKIE_VARS ;
		$cek='';$err='';$content=array();
		$idplh = $_REQUEST['pemasukan_idplh'];
		
		$uid = $_COOKIE['coID'];
		
		//$qry_cek = "SELECT * FROM (SELECT idbi, COUNT(*) as cnt FROM t_transaksi WHERE idbi IN (SELECT id FROM buku_induk WHERE refid_terima='$idplh') GROUP BY idbi) aa WHERE aa.cnt > 1;";$cek.= $qry_cek ;
		//$qry_cek = "SELECT * FROM t_transaksi WHERE refid_terima='$idplh' ";$cek.= $qry_cek ;
		$cek_admin = $DataPengaturan->QyrTmpl1Brs('admin', 'level, uid', "WHERE uid='$uid'");
		$hsl_cek_admin = $cek_admin['hasil'];
			
		if($err=='' && $hsl_cek_admin['level'] != '1')$err = "Membatalkan Posting Hanya Bisa Dilakukan oleh Admin !";
			
		if($err ==''){
		
			$qry_cek_jns = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_barang", "jns_trans", "WHERE Id='$idplh' ");
			$cek_jns=$qry_cek_jns['hasil'];
		
			if($cek_jns['jns_trans'] == '1'){
			
				$qry_cek = "SELECT * FROM (SELECT COUNT(*) as cnt, tt.*  FROM t_transaksi tt WHERE refid_terima='$idplh' GROUP BY idbi) aa WHERE cnt > 1 ;";$cek.= $qry_cek ;
				$aqry_cek = mysql_query($qry_cek);
				
				if($err == '' && mysql_num_rows($aqry_cek) > 0)$err='Tidak Bisa Membatalkan Posting, Sudah Ada Transaksi Lain Yang Digunakan !';
							
				if($err == ''){
					$qry_del = "DELETE FROM buku_induk WHERE refid_terima = '$idplh' ORDER BY id DESC LIMIT 100";$cek.=$qry_del;
					$aqry_del = mysql_query($qry_del);
					
					$qry = "SELECT COUNT(*) as cnt FROM buku_induk WHERE refid_terima = '$idplh' ";$cek.=$qry;
					$aqry = mysql_query($qry);
										
				}
			}else{
			
				$qry_del = "DELETE FROM pemeliharaan WHERE refid_terima = '$idplh' ORDER BY id DESC LIMIT 100";$cek.=$qry_del;
				$aqry_del = mysql_query($qry_del);
				
				$qry = "SELECT COUNT(*) as cnt FROM pemeliharaan WHERE refid_terima = '$idplh' ";$cek.=$qry;
				$aqry = mysql_query($qry);
			}
			
			$daqry = mysql_fetch_array($aqry);
			$content['JumlahBarang'] = $daqry['cnt'];	
			if($daqry['cnt'] > 0 ){
				$content['Proses'] = "NEXT";
			}else{
				$content['Proses'] = "END";
			}
			
		}
		
		
		
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
	
	function SetNama(){
		global $DataPengaturan;
		$cek='';$err='';$content=array();
		
		$pilihan = $_REQUEST['pilihan'];
		
		$Idplh_nama = $_REQUEST['nama_penerima'];
		if($pilihan == 'mengetahui')$Idplh_nama = $_REQUEST['nama_mengetahui'];
		
		$qry = "SELECT * FROM ref_tandatangan WHERE Id='$Idplh_nama' ";
		$aqry = mysql_query($qry);
		$daqry = mysql_fetch_array($aqry);
		
		$content['nip'] = $daqry['nip'];
		$content['pangkat'] = $daqry['pangkat']."/".$daqry['gol'];
		$content['eselon'] = $DataPengaturan->arrEselon[$daqry['eselon']][1];
		$content['jabatan'] = $daqry['jabatan'];
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function SimpanTTD(){
		global $DataPengaturan;
		$cek='';$err='';$content='';
		
		$c1 = $_REQUEST['c1nya'];
		$c = $_REQUEST['cnya'];
		$d = $_REQUEST['dnya'];
		$e = $_REQUEST['enya'];
		$e1 = $_REQUEST['e1nya'];
		
		$Id_penerima = $_REQUEST['nama_penerima'];
		$Id_mengetahui = $_REQUEST['nama_mengetahui'];
		
		$wherenya = " WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1' ";
		$hitung = $DataPengaturan->QryHitungData('ref_penerimaan_tandatangan', $wherenya);
		
		$cek.=$hitung['cek'];
		
		if(intval($hitung['hasil']) == 0){
			$data = array(
						array('c1',$c1),
						array('c',$c),
						array('d',$d),
						array('e',$e),
						array('e1',$e1),
						array('refid_penerima',$Id_penerima),
						array('refid_mengetahui',$Id_mengetahui),
					);
			$qry = $DataPengaturan->QryInsData('ref_penerimaan_tandatangan',$data);$cek.=$qry['cek'];
		}else{
			$data = array(
						array('refid_penerima',$Id_penerima),
						array('refid_mengetahui',$Id_mengetahui),
					);
			$qry = $DataPengaturan->QryUpdData('ref_penerimaan_tandatangan',$data, $wherenya);$cek.=$qry['cek'];
		}
		
		if($qry['errmsg'] == ''){
			$content='Data Berhasil Disimpan !';
		}else{
			$err=$qry['errmsg'];
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function PrintLaporan(){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS;
		
		$nama_laporan = $_REQUEST['nama_laporan'];
		$tgl_cetak = date('m-d-Y');
		$dari = $_REQUEST['dari'];
		$sampai = $_REQUEST['sampai'];
		$namauid = $_COOKIE['coNama'];
		
		switch($nama_laporan){
			case '1':
				$this->LaporanPenerimaanBarang();
			break;
			case '2':
				$this->RekapitulasiPenerimaanBarang();
			break;
		}
	}
	
	function LaporanPenerimaanBarang($xls =FALSE){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS, $DataOption;
		
		$nama_laporan = $_REQUEST['nama_laporan'];
		$tgl_cetak = date('m-d-Y');
		$dari = $_REQUEST['dari'];
		$dari_tgl = explode("-",$_REQUEST['dari']);
		$sampai = $_REQUEST['sampai'];
		$sampai_tgl = explode("-",$_REQUEST['sampai']);
		
		$tgl_dari = $dari_tgl[2]."-".$dari_tgl[1]."-".$dari_tgl[0];
		$tgl_sampai = $sampai_tgl[2]."-".$sampai_tgl[1]."-".$sampai_tgl[0];
		$namauid = $_COOKIE['coNama'];
		$c1 = $_REQUEST['c1nya'];
		$c = $_REQUEST['cnya'];
		$d = $_REQUEST['dnya'];
		$e = $_REQUEST['enya'];
		$e1 = $_REQUEST['e1nya'];
		
		$whereskpd = "aa.c1='$c1'";
		if($c != '00')$whereskpd.=" AND aa.c='$c'";
		if($d != '00')$whereskpd.=" AND aa.d='$d'";
		if($e != '00')$whereskpd.=" AND aa.e='$e'";
		if($e1 != '00')$whereskpd.=" AND aa.e1='$e1'";
		
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$daqry_pengaturan = $DataOption;
		
		$qry = "SELECT tpb.*, hit.cnt FROM (SELECT aa.Id, aa.tgl_buku, aa.no_dokumen_sumber, aa.tgl_dokumen_sumber, aa.dokumen_sumber, aa.refid_penyedia, aa.nomor_kontrak, aa.tgl_kontrak, aa.id_penerimaan, bb.nm_barang, bb.ket_barang, bb.jml, bb.harga_satuan, bb.harga_total, bb.keterangan FROM ".$this->TblName." aa RIGHT JOIN ".$DataPengaturan->VPenerima_det()." bb ON aa.Id=bb.refid_terima WHERE $whereskpd AND aa.sttemp='0' AND bb.sttemp='0' AND aa.tgl_dokumen_sumber >= '$tgl_dari' AND aa.tgl_dokumen_sumber <= '$tgl_sampai' AND aa.jns_trans='1' GROUP BY bb.Id) tpb
LEFT JOIN (SELECT refid_terima, COUNT(Id) as cnt FROM ".$DataPengaturan->VPenerima_det()." GROUP BY refid_terima) hit ON tpb.Id=hit.refid_terima ";
		$aqry = mysql_query($qry);
		
				
		//MULAI Halaman Laporan ------------------------------------------------------------------------------------------ 
		$css = $xls	? "<style>.nfmt5 {mso-number-format:'\@';}</style>":"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
						.ukurantulisan{
							font-size:17px;
						}
						.ukurantulisan1{
							font-size:20px;
						}
						.ukurantulisanIdPenerimaan{
							font-size:16px;
						}
					</style>
				</head>".
			"<body >
				<div style='width:$this->Cetak_WIDTH_Landscape;'>
					<table class=\"rangkacetak\" style='width:90%;font-family:Times New Roman;margin-left:2cm;margin-top:4cm;'>
						<tr>
							<td valign=\"top\">".
								$this->JudulLaporan($dari, $sampai, 'DAFTAR PENERIMAAN BARANG').
								$this->LaporanTmplSKPD($c1,$c,$d,$e,$e1);
								
		//echo $qry;
		echo "
								<br>
								<table table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
									<tr>
										<th class='th01' rowspan='2'>NO</th>
										<th class='th01' rowspan='2'>TANGGAL</th>
										<th class='th01' rowspan='2'>NO BAST</th>
										<th class='th01' rowspan='2'>NAMA BARANG</th>
										<th class='th01' rowspan='2'>MERK/TYPE/SPESIFIKASI/<br>LOKASI/JUDUL</th>
										<th class='th01' rowspan='2'>JML</th>
										<th class='th01' rowspan='2'>HARGA SATUAN (Rp)</th>
										<th class='th01' rowspan='2'>JUMLAH HARGA (Rp)</th>
										<th class='th01' rowspan='2'>PENYEDIA BARANG</th>
										<th class='th01' colspan='3'>DOKUMEN SUMBER</th>
										<th class='th01' rowspan='2'>ID PENERIMAAN/ KETERANGAN</th>
									</tr>
									<tr>
										<th class='th01'>JENIS</th>
										<th class='th01'>NOMOR</th>
										<th class='th01'>TANGGAL</th>
									</tr>
		";
		
		$pid = '';
		$no_cek = 0;
		$no = 1;
		while($daqry = mysql_fetch_array($aqry)){
			if($pid != $daqry['Id'])$no_cek=0;
			
			$tgl_dokumen_sumber = explode("-",$daqry['tgl_dokumen_sumber']);
			$tgl_kontrak = explode("-",$daqry['tgl_kontrak']);
			if($pid != $daqry['Id']){
				if($daqry['cnt'] == 1){
					$dokumen = "KONTRAK<br>".$daqry['dokumen_sumber'];
					$no_dokumen = $daqry['nomor_kontrak']."<br>".$daqry['no_dokumen_sumber'];
					$tgl_dokumen = $tgl_kontrak[2]."-".$tgl_kontrak[1]."-".$tgl_kontrak[0]."<br>".$tgl_dokumen_sumber[2]."-".$tgl_dokumen_sumber[1]."-".$tgl_dokumen_sumber[0];
					$no_cek = 0;
				}else{
					$dokumen = "KONTRAK";
					$no_dokumen = $daqry['nomor_kontrak'];
					$tgl_dokumen = $tgl_kontrak[2]."-".$tgl_kontrak[1]."-".$tgl_kontrak[0];
				}				
			}else{
				if($no_cek == 1){
					$dokumen = $daqry['dokumen_sumber'];
					$no_dokumen = $daqry['no_dokumen_sumber'];
					$tgl_dokumen = $tgl_dokumen_sumber[2]."-".$tgl_dokumen_sumber[1]."-".$tgl_dokumen_sumber[0];
				}else{
					$dokumen='';
					$no_dokumen='';
					$tgl_dokumen='';
				}
				
			}
			$pid = $daqry['Id'];
			
			// PENYEDIA
			$penyedia = $DataPengaturan->QyrTmpl1Brs('ref_penyedia', 'nama_penyedia', "WHERE id='".$daqry['refid_penyedia']."'");
			$nm_penyedia = $penyedia['hasil'];
			//TGL BUKU & NO BAST
			if($no_cek == 0){
				$tgl_buku_nya = explode("-", $daqry['tgl_buku']);
				$tgl_buku = $tgl_buku_nya[2]."-".$tgl_buku_nya[1]."-".$tgl_buku_nya[0];
				$no_bast = $daqry['no_dokumen_sumber']; 
				$id_penerimaan = $daqry['id_penerimaan']."<br>";
			}else{
				$tgl_buku='';
				$no_bast='';
				$id_penerimaan='';
			}
			
			echo "
								<tr valign='top'>
									<td align='right' class='GarisCetak'>$no</td>
									<td align='center' class='GarisCetak' style='width:65px;'>".$tgl_buku."</td>
									<td class='GarisCetak'>".$no_bast."</td>
									<td class='GarisCetak'>".$daqry['nm_barang']."</td>
									<td class='GarisCetak'>".$daqry['ket_barang']."</td>
									<td align='right' class='GarisCetak'>".number_format($daqry['jml'],0,'.',',')."</td>
									<td align='right' class='GarisCetak'>".number_format($daqry['harga_satuan'],2,',','.')."</td>
									<td align='right' class='GarisCetak'>".number_format($daqry['harga_total'],2,',','.')."</td>
									<td class='GarisCetak'>".$nm_penyedia['nama_penyedia']."</td>
									<td class='GarisCetak'>".$dokumen."</td>
									<td class='GarisCetak'>".$no_dokumen."</td>
									<td align='center' class='GarisCetak' style='width:65px;'>".$tgl_dokumen."</td>
									<td class='GarisCetak'>".$id_penerimaan.$daqry['keterangan']."</td>
								</tr>
			";
			
			$no++;
			$no_cek++;
			
			
		}
		echo 				"</table>";		
		echo 			$this->TandaTanganFooter($c1,$c,$d,$e,$e1).
						"</div>	</td></tr>
					</table>
				</div>	
			</body>	
		</html>";
	}
	
	function TandaTanganFooter($c1,$c,$d,$e,$e1){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS, $DataOption;
		
		$tgl_cetak = date('m-d-Y');	
		$daqry_pengaturan = $DataOption;
		
		$qry_atasnama = $DataPengaturan->QyrTmpl1Brs('ref_penerimaan_tandatangan','*',"WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1' ");
		$atasnama = $qry_atasnama['hasil'];
		
		$qry_penerima = $DataPengaturan->QyrTmpl1Brs('ref_tandatangan','nip, nama',"WHERE Id='".$atasnama['refid_penerima']."' ");
		$penerima = $qry_penerima['hasil'];
		
		$qry_mengetahui = $DataPengaturan->QyrTmpl1Brs('ref_tandatangan','nip, nama',"WHERE Id='".$atasnama['refid_mengetahui']."' ");
		$mengetahui = $qry_mengetahui['hasil'];
		
		
		return "<br><div class='ukurantulisan'>
					<table width='100%'>
						<tr>
							<td class='ukurantulisan' valign='top' ></td>
							<td class='ukurantulisan' valign='top' width='30%' ></td>
							<td class='ukurantulisan' valign='top'><span style='margin-left:5px;'>".$daqry_pengaturan['titimangsa_surat'].", ".$tgl_cetak."</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>Penerima/Pengurus Barang
<br><br><br><br><br></span></td>
							<td class='ukurantulisan' valign='top' width='30%' ></td>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>Mengetahui;
</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>".$penerima['nama']."</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>NIP</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>".$penerima['nip']."</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan'></td>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>".$mengetahui['nama']."</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>NIP</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>".$mengetahui['nip']."</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>";
	}
	
	function LaporanTmplSKPD($c1, $c, $d, $e, $e1){
		global $Main, $DataPengaturan, $DataOption;
		
		$tukdepan = '';
		$urusan = '';
		if($DataOption['skpd'] != '1'){
			$qry1 = $DataPengaturan->QyrTmpl1Brs("ref_skpd","CONCAT (c1,'. ', nm_skpd) as nm_skpd ", "WHERE c1='$c1' AND c='00' AND d='00' AND e='00' AND e1='000'");
			$qry1 = $qry1['hasil'];
			$tukdepan = "c1='$c1' AND ";
			
			$urusan = "
					<tr>
						<td width='10%' valign='top'>URUSAN</td>
						<td width='1%' valign='top'> : </td>
						<td valign='top'>".$qry1['nm_skpd']."</td>
					</tr>
			";
		}
		
			$qry2 = $DataPengaturan->QyrTmpl1Brs("ref_skpd","CONCAT (c,'. ', nm_skpd) as nm_skpd ", "WHERE $tukdepan c='$c' AND d='00' AND e='00' AND e1='000'");
			$qry2 = $qry2['hasil'];
			if($qry2['nm_skpd'] == NULL OR $qry2['nm_skpd'] == '')$qry2['nm_skpd'] = "SEMUA BIDANG";
			
			
			$qry3 = $DataPengaturan->QyrTmpl1Brs("ref_skpd","CONCAT (d,'. ', nm_skpd) as nm_skpd ", "WHERE $tukdepan c='$c' AND d='$d' AND e='00' AND e1='000'");
			$qry3 = $qry3['hasil'];
			if($qry3['nm_skpd'] == NULL OR $qry3['nm_skpd'] == '')$qry3['nm_skpd'] = "SEMUA SKPD";
			
			$qry4 = $DataPengaturan->QyrTmpl1Brs("ref_skpd","CONCAT (e,'. ', nm_skpd) as nm_skpd ", "WHERE $tukdepan c='$c' AND d='$d' AND e='$e' AND e1='000'");
			$qry4 = $qry4['hasil'];
			if($qry4['nm_skpd'] == NULL OR $qry4['nm_skpd'] == '')$qry4['nm_skpd'] = "SEMUA UNIT";
			
			$qry5 = $DataPengaturan->QyrTmpl1Brs("ref_skpd","CONCAT (e1,'. ', nm_skpd) as nm_skpd ", "WHERE $tukdepan c='$c' AND d='$d' AND e='$e' AND e1='$e1'");
			$qry5 = $qry5['hasil'];
			if($qry5['nm_skpd'] == NULL OR $qry5['nm_skpd'] == '')$qry5['nm_skpd'] = "SEMUA SUBUNIT";

		
		
		$data = "
				<table width=\"100%\" border=\"0\" class='subjudulcetak'>
					$urusan
					<tr>
						<td width='10%' valign='top' >BIDANG</td>
						<td width='1%' valign='top'> : </td>
						<td valign='top'>".$qry2['nm_skpd']."</td>
					</tr>
					<tr>
						<td width='10%' valign='top'>SKPD</td>
						<td width='1%' valign='top'> : </td>
						<td valign='top'>".$qry3['nm_skpd']."</td>
					</tr>
					<tr>
						<td width='10%' valign='top'>UNIT</td>
						<td width='1%' valign='top'> : </td>
						<td valign='top'>".$qry4['nm_skpd']."</td>
					</tr>
					<tr>
						<td width='10%' valign='top'>SUBUNIT</td>
						<td width='1%' valign='top'> : </td>
						<td valign='top'>".$qry5['nm_skpd']."</td>
					</tr>
				</table>";
		
		return $data;
	}
	
	function JudulLaporan($dari='', $sampai='',$judul=''){
		return "<div style='text-align:center;'>
				<span style='font-size:18px;font-weight:bold;text-decoration: underline;'>
					$judul
				</span><br>
				<span class='ukurantulisanIdPenerimaan'>PERIODE : $dari S/D $sampai </span></div><br>";
	}
	
	function RekapitulasiPenerimaanBarang($xls =FALSE){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS, $DataOption;
		
		$nama_laporan = $_REQUEST['nama_laporan'];
		$tgl_cetak = date('m-d-Y');
		$dari = $_REQUEST['dari'];
		$dari_tgl = explode("-",$_REQUEST['dari']);
		$sampai = $_REQUEST['sampai'];
		$sampai_tgl = explode("-",$_REQUEST['sampai']);
		
		$tgl_dari = $dari_tgl[2]."-".$dari_tgl[1]."-".$dari_tgl[0];
		$tgl_sampai = $sampai_tgl[2]."-".$sampai_tgl[1]."-".$sampai_tgl[0];
		$namauid = $_COOKIE['coNama'];
		$c1 = $_REQUEST['c1nya'];
		$c = $_REQUEST['cnya'];
		$d = $_REQUEST['dnya'];
		$e = $_REQUEST['enya'];
		$e1 = $_REQUEST['e1nya'];
		
		$whereskpd = "aa.c1='$c1'";
		if($c != '00')$whereskpd.=" AND aa.c='$c'";
		if($d != '00')$whereskpd.=" AND aa.d='$d'";
		if($e != '00')$whereskpd.=" AND aa.e='$e'";
		if($e1 != '00')$whereskpd.=" AND aa.e1='$e1'";
		
		$wherekodbar = '';
		if($DataOption['kode_barang'] == '1')$wherekodbar = "AND f1='0' AND f2='0' ";
		
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$daqry_pengaturan = $DataOption;
		$qry = "SELECT * FROM (SELECT vtpdet.*, tpd.tgl_dokumen_sumber, tpd.jns_trans FROM ".$DataPengaturan->VPenerima_det()." vtpdet LEFT JOIN t_penerimaan_barang tpd ON vtpdet.refid_terima=tpd.Id WHERE tpd.sttemp='0' AND vtpdet.sttemp='0' AND tpd.jns_trans='1') aa WHERE tgl_dokumen_sumber>='$dari' AND tgl_dokumen_sumber<='$sampai' AND $whereskpd $wherekodbar";
		$aqry = mysql_query($qry);
		
		
				
		//MULAI Halaman Laporan ------------------------------------------------------------------------------------------ 
		$css = $xls	? "<style>.nfmt5 {mso-number-format:'\@';}</style>":"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
						.ukurantulisan{
							font-size:17px;
						}
						.ukurantulisan1{
							font-size:20px;
						}
						.ukurantulisanIdPenerimaan{
							font-size:16px;
						}
					</style>
				</head>".
			"<body >
				<div style='width:$this->Cetak_WIDTH;'>
					<table class=\"rangkacetak\" style='width:90%;font-family:Times New Roman;margin-left:2cm;margin-top:4cm;'>
						<tr>
							<td valign=\"top\">".
								$this->JudulLaporan($dari, $sampai, 'REKAPITULASI PENERIMAAN BARANG').
								$this->LaporanTmplSKPD($c1,$c,$d,$e,$e1)."<br>";
								
		echo				"
								<table table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
									<tr>
										<th class='th01'>NO</th>
										<th class='th01'>KODE BARANG</th>
										<th class='th01'>NAMA BARANG</th>
										<th class='th01'>JUMLAH</th>
										<th class='th01'>JUMLAH HARGA (Rp)</th>
									</tr>
							";
		$no=1;	
		$jml_brg = 0;
		$tot_hrg = 0;					
		while($daqry = mysql_fetch_array($aqry)){
			$kodebarang = $daqry['f'].".".$daqry['g'].".".$daqry['h'].".".$daqry['i'].".".$daqry['j'];
			if($DataOption['kode_barang'] != '1')$kodebarang = $daqry['f1'].'.'.$daqry['f2'].$kodebarang;
			echo				"
									<tr>
										<td align='right' class='GarisCetak'>$no</td>
										<td align='center' class='GarisCetak'>$kodebarang</td>
										<td class='GarisCetak'>".$daqry['nm_barang']."</td>
										<td align='right' class='GarisCetak'>".number_format($daqry['jml'],0,'.',',')."</td>
										<td align='right' class='GarisCetak'>".number_format($daqry['harga_total'],2,',','.')."</td>
									</tr>
			";
			$jml_brg = $jml_brg+intval($daqry['jml']);
			$tot_hrg = $tot_hrg+intval($daqry['harga_total']);
			$no++;					
		}
		
		echo 
								"	<tr>
										<td align='center' class='GarisCetak' colspan='3'><b>TOTAL</b></td>
										<td align='right' class='GarisCetak'><b>".number_format($jml_brg,0,'.',',')."</b></td>
										<td align='right' class='GarisCetak'><b>".number_format($tot_hrg,2,',','.')."</b></td>
									</tr>
								</table>".
							$this->TandaTanganFooter($c1,$c,$d,$e,$e1).
						"</div>	</td></tr>
					</table>
				</div>	
			</body>	
		</html>";
	}
	
	function PengecekanKapitalisasi(){
		global $DataPengaturan;
		$cek='';
		$err='';
		$content='';
		
		$Idplh = $_REQUEST['IdTerimanya'];
		
		$qry = $DataPengaturan->QyrTmpl1Brs($this->TblName,"biayaatribusi", "WHERE Id='$Idplh' ");
		$aqry = $qry['hasil'];
		
		if($aqry['biayaatribusi'] == '1'){
			$hit_attr = $DataPengaturan->QryHitungData("t_atribusi","WHERE refid_terima='$Idplh' AND sttemp='0' ");
			if($hit_attr['hasil'] > 0){
				$hit_attr_det = $DataPengaturan->QryHitungData("t_atribusi_rincian","WHERE refid_terima='$Idplh' AND sttemp='0' AND status='0' ");
				if($hit_attr_det['hasil'] < 1)$err = "Ada Harga Atribusi yang Belum Di Masukan !";
			}else{
				$err = "Ada Harga Atribusi yang Belum Di Masukan !";
			}
		}
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function CekKapitalisasiSesuai($idplh){
		global $DataPengaturan;
		
		$err = '';
		
		$qry_pendet = $DataPengaturan->QyrTmpl1Brs('t_penerimaan_barang_det', "IFNULL(SUM(harga_total),0) as harga_total", "WHERE refid_terima='$idplh' AND sttemp='0' AND barangdistribusi='1' ");
		$aqry_pendet = $qry_pendet['hasil'];
		
		$qry_attribusi = $DataPengaturan->QyrTmpl1Brs('t_atribusi_rincian', "IFNULL(SUM(jumlah),0) as jumlah", "WHERE refid_terima='$idplh' AND sttemp='0' ");
		$aqry_attribusi = $qry_attribusi['hasil'];
		
		$tothar = $aqry_pendet['harga_total'] + $aqry_attribusi['jumlah'];
		
		$qry_kptls = $DataPengaturan->QyrTmpl1Brs("t_distribusi", "IFNULL(SUM(jumlah),0) as jumlah", "WHERE refid_terima='$idplh' AND sttemp='0' ");
		$aqry_kptls = $qry_kptls['hasil'];
		
		if(round(intval($aqry_kptls['jumlah']) != intval($tothar)))$err = "Jumlah Biaya Yang Di Kapitalisasi Belum Sesuai !";
		
		return $err;
	}
	
	function ProsesPostingPemeliharaan(){
		global $DataPengaturan, $HTTP_COOKIE_VARS, $Main;
	 	$coThnAnggaran = intval($HTTP_COOKIE_VARS['coThnAnggaran']);
		
		$cek='';
		$err = '';
		$content='';
		
		$IdDistribusi = addslashes($_REQUEST['IdDistribusi']);
		$cek=$IdDistribusi;
		$pemasukan_idplh=$_REQUEST['pemasukan_idplh'];
		
		//Tampil Penerimaan Barang -----------------------------------------------------------------------------
		$aqry_pen = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_barang", "asal_usul", "WHERE Id='$pemasukan_idplh' ");
		$daqry_pen = $aqry_pen['hasil'];
		
		if($daqry_pen['asal_usul'] == '1'){
			$cek_kodeBm = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_rekening", "COUNT(*) as hitung", "WHERE refid_terima='$pemasukan_idplh' AND ".$Main->FIELD_KODE_BELANJA_MODAL." = '".$Main->KODE_BELANJA_MODAL."' AND sttemp='0' ");$cek.=$cek_kodeBm['cek'];
			$hsl_cek_Bm = $cek_kodeBm['hasil'];
			
			$cek_kodeBm2 = $DataPengaturan->QyrTmpl1Brs("t_atribusi_rincian", "COUNT(*) as hitung", "WHERE refid_terima='$pemasukan_idplh' AND ".$Main->FIELD_KODE_BELANJA_MODAL." = '".$Main->KODE_BELANJA_MODAL."' AND sttemp='0' ");$cek.=$cek_kodeBm2['cek'];
			$hsl_cek_Bm2 = $cek_kodeBm2['hasil'];
			
			if($hsl_cek_Bm['hitung'] > 0 || $hsl_cek_Bm2['hitung'] > 0){
				$asal_usul = "1";
			}else{
				$asal_usul = "2";
			}
			
		}else{
			$asal_usul = "3";
		}
		
		$cek.=$aqry_pen['cek'];
		
		if($err == ''){
			$qry = "SELECT sf_penerimaan_posting_pemeliharaan($IdDistribusi, $coThnAnggaran, '$asal_usul') as hasil";$cek.=$qry;
			$aqry = mysql_query($qry);
			if(!$aqry)$err=mysql_error();
			$daqry = mysql_fetch_array($aqry);
					
			$hasil = explode(" ",$daqry['hasil']);
			$selanjutnya = $hasil[0];
			$brg_input = $hasil[1];
			
			$content['lanjut'] = $selanjutnya;
			$content['brg_input'] = $brg_input;		
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function CekAtribusi(){
		global $DataPengaturan;
		$cek = '';
		$err = '';
		$content = '';
			
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$idplh = $cbid[0];
			
		$qry = "SELECT * FROM t_penerimaan_barang WHERE Id='$idplh' ";
		$aqry = mysql_query($qry);
		$dt = mysql_fetch_array($aqry);
			
		if(isset($_REQUEST['atrib']) ){
			if($err == '' && $dt['status_validasi'] == '1')$err = "Data Tidak Bisa Diubah, Data Sudah di Validasi !";
		}elseif($dt['status_validasi'] == '1'){
			$err = "Data Tidak Bisa Diubah, Data Sudah di Validasi !";
		}else{
			if($err == '' && $dt['biayaatribusi'] !='1')$err = "Data Ini Tidak Ada Biaya Atribusi !";
		}
		
		if($err==''){
			if($dt['jns_trans'] == '1'){
				$qry_cek = $DataPengaturan->QryHitungData("buku_induk", "WHERE refid_terima = '$idplh' ");
			}else{
				$qry_cek = $DataPengaturan->QryHitungData("pemeliharaan", "WHERE refid_terima = '$idplh' ");
			}	
			$cek.=$qry_cek;
			
			$daqry_cek = $qry_cek['hasil'];
			if($daqry_cek > 0 )$err = "Data Tidak Bisa Diubah, Data Sudah Di Posting !";
						
		}	
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function HargaTotalPengadaan($Id, $attri){
		global $DataPengaturan;
		$cek.='';
		$hrg_pendet = $DataPengaturan->QyrTmpl1Brs("t_penerimaan_barang_det", "IFNULL(SUM(harga_total),0) as tot", "WHERE refid_terima='$Id' AND sttemp='0' ");$cek.=$hrg_pendet['cek'];
		$harga_pendet = $hrg_pendet['hasil'];
		$harga_total = $harga_pendet['tot'];
		
		if($attri == '1'){
			$hrg_attri = $DataPengaturan->QyrTmpl1Brs("t_atribusi_rincian", "IFNULL(SUM(jumlah),0) as tot", "WHERE refid_terima='$Id' AND sttemp='0' ");$cek.=$hrg_attri['cek'];
			$hrg_attribusi = $hrg_attri['hasil'];
			$harga_total = $harga_total + $hrg_attribusi['tot'];
		}
		
		return array($harga_total,$cek);
		
		
	}
	
}
$pemasukan = new pemasukanObj();
?>