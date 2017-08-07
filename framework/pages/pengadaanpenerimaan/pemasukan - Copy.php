<?php

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
	var $Cetak_OtherHTMLHead;
	var $FormName = 'pemasukanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $pid = '';
	var $noakhirnya = 1; 
	
	
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
		return 'DAFTAR PENGADAAN DAN PENERIMAAN BARANG';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".InputBaru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".AtribusiBaru()","new_f2.png","Atribusi", 'Atribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Distribusi", 'Distribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Validasi()","new_f2.png","Validasi", 'Validasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Posting", 'Posting')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","export_xls.png","Excel", 'Excel')."</td>";
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
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'PengecekanUbah':{							
			$cek = '';
			$err = '';
			$content = '';
			
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$idplh = $cbid[0];
			
			$qry = "SELECT * FROM t_penerimaan_barang WHERE Id='$idplh' ";
			$aqry = mysql_query($qry);
			$dt = mysql_fetch_array($aqry);
			
			if($err == '' && $dt['status_validasi'] == '1')$err = "Data Tidak Bisa Diubah, Sudah di Validasi !";
															
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
	return '';
			/*"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=satuan\" title='Satuan' style='color:blue' >PENGADAAN DAN PENERIMAAN</a> 
	&nbsp&nbsp&nbsp	
	</td></tr></table>";*/
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		 $cek.= $this->BersihkanData();
	 $NomorColSpan = $Mode==1? 2: 1;
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
	   		<th class='th01'>DISTRI</th>
	   </tr>
	   </thead>"
	   //"</table><table class='koptable' border='1' style='margin:4 0 0 0;width:100%'>"
	   ;
	 
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 
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
	$hitung_rekeningnya = mysql_num_rows($qryrekening);
	$xnya = 0;
	$harga_tot_rek = 0;
	 
	 
	//QRY PENERIMAAN RINCIAN
	$qrypenerimaan_det = "SELECT * FROM v1_penerimaan_barang_det WHERE refid_terima='".$isi['Id']."' AND sttemp='0'";
	$aqrypenerimaan_det = mysql_query($qrypenerimaan_det);
	$hitung_penerimaan_det = mysql_num_rows($aqrypenerimaan_det);
	$nhitung = 0;
	$harga_tot_pendet = 0;
	$tot_bar = 0;
	
	 // TDTR MULAI DISINI ------------------------------------------------------------------------
		 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
		 if($Mode == 1)$Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
		 
	 if($hitung_rekeningnya > 0){
	 	 
		 $Koloms.= "<td class='$cssclass' align='center' width='7%'>$tgl_kontrak</td>";
		 $Koloms.= "<td class='$cssclass' align='left' style='width:70px;'>".$isi['nomor_kontrak']."</td>";
	 }else{
	 	$qry_atribusi = "SELECT * FROM v1_penerimaan_atribusi WHERE refid_terima='".$isi['Id']."'";
		$aqry_atribusi = mysql_query($qry_atribusi);
		
		$noatri = 0;
		while($dt_atri = mysql_fetch_array($aqry_atribusi)){
		
			$tglsp2d_atri = explode($dt_atri['tgl_sp2d']);
			$tglsp2d_atri = $tglsp2d_atri[2]."-".$tglsp2d_atri[1]."-".$tglsp2d_atri[0];
			
			if($noatri != 0){
				$Koloms.= "<td class='$cssclass' align='center'></td>";
		 		if($Mode == 1)$Koloms.= "<td class='$cssclass' align='center'></td>";
			}
			$Koloms.= "<td class='$cssclass' align='center' width='7%'>$tglsp2d_atri</td>";
		 	$Koloms.= "<td class='$cssclass' align='left' style='width:70px;'>".$dt_atri['no_sp2d']."</td>";
			
			
		}		
	 }
	
	
		
	//REKENING -----------------------------------------------------------------------------------
	
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
	 	 $Koloms.= "<td class='$cssclass' align='left'  width='50%'><b>".$dt_rekening['nm_rekening']."</b></td>";
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
	 
	//PENERIMAAN DETAIL ----------------------------------------------------------------------------------
	
	if($hitung_penerimaan_det > 0){	
		while($dt_penerimaan_det = mysql_fetch_array($aqrypenerimaan_det)){
			//DISTRIBUSI
			 $brgdistribusi = 'TDK';
			 if($dt_penerimaan_det['barangdistribusi'] == '1')$brgdistribusi='YA';
				
			 //ATRIBUSI
			 $atribusi = 'TDK';
			 if($dt_penerimaan_det['biayaatribusi'] == '1')$atribusi='YA';
			 
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
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
			 if($Mode == 1)$Koloms.= "<td class='$cssclass' align='right'></td>";
			 $Koloms.= "<td class='$cssclass' align='center' valign='top'>$tgllan</td>";
			 $Koloms.= "<td class='$cssclass' align='left' valign='top'>$nomoran</td>";
			 
		 	 $Koloms.= "<td class='$cssclass' align='left' width='50%'><span style='margin-left:10px;'>".$dt_penerimaan_det['nm_barang']."</span></td>";
			 $Koloms.= "<td class='$cssclass' align='right' style='width:50px;'>".number_format($dt_penerimaan_det['jml'],0,'.',',')."</td>";
			 //----- HARGA BARANG
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'>".number_format($dt_penerimaan_det['harga_total'],2,',','.')."</td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 $Koloms.= "<td class='$cssclass' align='right' width='10%'></td>";
			 //----- HARGA BARANG
			 $Koloms.= "<td class='$cssclass' align='center'>$atribusi</td>";
			 $Koloms.= "<td class='$cssclass' align='center'>$brgdistribusi</td>";
			 $Koloms.= "<td class='$cssclass' align='center'><img src='images/administrator/images/$validnya' width='20px' heigh='20px' /></td>";
			 $Koloms.= "<td class='$cssclass' align='right'></td>";
		 	$Koloms.= "</tr>";
			$nhitung++;
			$harga_tot_pendet = $harga_tot_pendet+$dt_penerimaan_det['harga_total'];
			$tot_bar=$tot_bar+$dt_penerimaan_det['jml'];
		}	
	}
	
	//SUB TOTAL ---------------------------------------------------------------------------------------------------------
	  $Koloms.= "<td class='$cssclass' align='right'></td>";
	  if($Mode == 1)$Koloms.= "<td class='$cssclass' align='right'></td>";
	  $Koloms.= "<td class='$cssclass' align='right' colspan='3'><b>SUB TOTAL</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($tot_bar,0,'.',',')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($harga_tot_rek,2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format($harga_tot_pendet,2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format(0,2,',','.')."</b></td>";
	  $Koloms.= "<td class='$cssclass' align='right'><b>".number_format(0,2,',','.')."</b></td>";
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
	 global $Ref, $Main, $HTTP_COOKIE_VARS;
	 
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
	
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
			$vOrder=
				"<table width=\"100%\" class=\"adminform\">	<tr>		
				<td width=\"100%\" valign=\"top\">" . 
					WilSKPD_ajxVW($this->Prefix.'SKPD') . 
				"</td>
				<td valign='top'>" . 		
				"</td></tr>
				<!--<tr><td>
					<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
				</td></tr>			-->
				</table>"
				;
			
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		
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
					"<input type='hidden' name='pemasukanSKPDfmUrusan' value='".$_REQUEST['pemasukanSKPDfmUrusan']."' />".
					
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
		
		
		return $cek;
	}
	
	function Hapus($ids){ //validasi hapus ref_pegawai
		global $Main, $HTTP_COOKIE_VARS;
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
		}
		
		
		if($err == ''){
			for($i = 0; $i<count($ids); $i++){
				//Hapus Rekening
				$hapusrek = "DELETE FROM t_penerimaan_rekening WHERE refid_terima ='".$ids[$i]."'"; $cek.="| ".$hapusrek;
				$qry_hapusrek = mysql_query($hapusrek);
				//Hapus Penerimaan Detail
				$hapuspenerimaan_det = "DELETE FROM t_penerimaan_barang_det WHERE refid_terima ='".$ids[$i]."' "; $cek.="| ".$hapuspenerimaan_det;
				$qry_hapuspenerimaan_det = mysql_query($hapuspenerimaan_det);
				
				//Hapus Penerimaan
				$hapus_penerimaan = "DELETE FROM t_penerimaan_barang WHERE Id='".$ids[$i]."'"; $cek.="| ".$hapus_penerimaan;		
				$qry_hapus_penerimaan = mysql_query($hapus_penerimaan);
				
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function CetakPermohonan($xls= FALSE, $Mode=''){
		global $Main, $Ref, $HTTP_COOKIE_VARS;
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
		
		$qry_det = "SELECT * FROM v1_penerimaan_barang_det WHERE refid_terima='$id' AND sttemp='0'";
		$aqry_det = mysql_query($qry_det);
		
		
		$qry_rekening = "SELECT * FROM v1_penerimaan_barang_rekening WHERE refid_terima='$id' AND sttemp='0'";
		$aqry_rek = mysql_query($qry_rekening);
		
		//Nama SKPD
		$qry_skpd = "SELECT nm_skpd FROM ref_skpd WHERE c1='".$daqry['c1']."' AND c='".$daqry['c']."' AND d='".$daqry['d']."'  AND e='".$daqry['e']."' AND e1='".$daqry['e1']."' ";
		$aqry_skpd = mysql_query($qry_skpd);$daqry_skpd = mysql_fetch_array($aqry_skpd);
		
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
							<td align='right'><img src='Media/PENGADAAN/$id.png' width='100' height='100'/>
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
				Rekening Belanja :<br>
				
				<table style='margin-left:30px;' width='96%'>
				";
				$do = 1;
				$totrek=0;
				while($daqry_rek = mysql_fetch_array($aqry_rek)){
					$koderekening = $daqry_rek['k'].".".$daqry_rek['l'].".".$daqry_rek['m'].".".$daqry_rek['n'].".".$daqry_rek['o'];	
					
				echo 
					"<tr>
						<td class='ukurantulisan'>$do. </td>
						<td class='ukurantulisan'>$koderekening; </td>
						<td class='ukurantulisan'>".$daqry_rek['nm_rekening']." </td>
						<td class='ukurantulisan' align='right'> &nbsp Rp ".number_format($daqry_rek['jumlah'],2,",",".")." </td>
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
				echo "
					<tr>
						<td class='ukurantulisan' colspan='3'>Total</td>
						<td class='ukurantulisan' align='right'>&nbsp Rp ".number_format($totrek,2,",",".")."</td>
					</tr>
				</table>
				
				Kelengkapan Dokumen :<br>
				<div style='margin-left:30px;'><b></b> Dokumen Kontrak;  Nomor : ".$daqry['nomor_kontrak']." Tanggal : ".$tglkontrak."</div>
				<div style='margin-left:30px;'><b></b> ".$daqry['dokumen_sumber']."; Nomor : ".$daqry['no_dokumen_sumber']." Tanggal : $tgl_dokumen_sumber </div>
				<div style='margin-left:30px;'><b></b> Faktur/Kwitansi Pembelian</div>
				
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
										…………………………………………………………………………………………
										…………………………………………………………………………………………
										…………………………………………………………………………………………
										…………………………………………………………………………………………
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
		
}
$pemasukan = new pemasukanObj();
?>