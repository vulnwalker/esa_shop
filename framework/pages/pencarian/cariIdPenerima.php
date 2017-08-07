<?php

class cariIdPenerimaObj  extends DaftarObj2{	
	var $Prefix = 'cariIdPenerima';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v1_penerimaan_barang_cari'; //bonus
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('tp_Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PENGADAAN DAN PENERIMAAN';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'cariRekening';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'cariIdPenerimaForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'DAFTAR PROGRAM';
	}
	
	function setMenuEdit(){
		return "";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if( $err=='' && $nama =='' ) $err= 'Satuan Belum Di Isi !!';
	 
			if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama)values('$nama')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_satuan set nama='$nama' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
					}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
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
					
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		
		case 'getid':{
				$cek = '';
				$err = '';
				$content = '';	
				
				$id_penerimaan = $_REQUEST['idpenerimanya'];
				$refidnya = $_REQUEST['refidnya'];
				
				
				if($err == ''){
					$qry = "SELECT * FROM t_penerimaan_barang WHERE Id='$refidnya' ";$cek.=$qry;
					$aqry = mysql_query($qry);
					$daqry = mysql_fetch_array($aqry);
						
					
					$content['idpenerima'] = $id_penerimaan;
					$content['refid'] = $refidnya;
					$content['pekerjaan'] = $daqry['pekerjaan'];
					
					$bknya = $daqry['bk'];
					$cknya = $daqry['ck'];
					$dknya = $daqry['dk'];
					$pnya = $daqry['p'];
					$qnya = $daqry['q'];
					
					$qry = "SELECT * FROM ref_program WHERE bk='$bknya' AND ck='$cknya' AND dk='$dknya' AND p='$pnya' AND q='0' ";
					$aqry = mysql_query($qry);
					$daqry = mysql_fetch_array($aqry);
					
					$kgt = "SELECT q,concat (IF(LENGTH(q)=1,concat('0',q), q),'. ',nama) as nama FROM ref_program WHERE bk='$bknya' AND ck='$cknya' AND dk='$dknya' AND p='$pnya' AND q!='0'";$cek.=$kgt;
					
					$content['kegiatan'] = cmbQuery('kegiatan1',$qnya,$kgt,"style='width:500px;' disabled",'--- PILIH KEGIATAN ---')."<input type='hidden' name='kegiatan' id='kegiatan' value='$qnya' />";
					
					
					 $bk = $daqry['bk'];
					 if(strlen($bk) == 1)$bk = "0".$bk;
					 
					 $ck = $daqry['ck'];
					 if(strlen($ck) == 1)$ck = "0".$ck;
					 
					 $dk = $daqry['dk'];
					 if(strlen($dk) == 1)$dk = "0".$dk;
					 
					 $p = $daqry['p'];
					 if(strlen($p) == 1)$p="0".$p;
	 
					$content['program'] = "$bk.$ck.$dk.$p. ".$daqry['nama'];
					$content['p'] = $daqry['bk'].".".$daqry['ck'].".".$daqry['dk'].".".$daqry['p'];
		
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
					</script>";
		return 	
			"<script type='text/javascript' src='js/pencarian/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
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
	return "";
			/*"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=bagian\" title='Organisasi' >Organisasi</a> |
	<A href=\"pages.php?Pg=pegawai\" title='Pegawai' >Pegawai</a> |
	<A href=\"pages.php?Pg=barang\" title='Barang'>Barang</a> |
	<A href=\"pages.php?Pg=jenis\" title='Jenis'  >Jenis</a> |
	<A href=\"pages.php?Pg=satuan\" title='Satuan' style='color:blue' >Satuan</a> 
	&nbsp&nbsp&nbsp	
	</td></tr></table>";*/
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5'>NO.</th>".
	  	   /*$Checkbox*/"		
		   <th class='th01'>ID PENERIMAAN</th>
		   <th class='th01'>DOKUMEN</th>
		   <th class='th01'>NO DOKUMEN</th>
		   <th class='th01'>TANGGAL DOKUMEN</th>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $tp_tgl_dokumen_sumber = explode("-",$isi['tp_tgl_dokumen_sumber']);
	 $tp_tgl_dokumen_sumber = $tp_tgl_dokumen_sumber[2].'-'.$tp_tgl_dokumen_sumber[1].'-'.$tp_tgl_dokumen_sumber[0];
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  /*if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);*/
	 $Koloms[] = array('align="left" ',"<a href='javascript:".$this->Prefix.".pilPen(`".$isi['tp_id_penerimaan']."`, `".$isi['tp_Id']."`)' >".
	 	$isi['tp_id_penerimaan']."</a>");
	 $Koloms[] = array('align="left"',$isi['tp_dokumen_sumber']);
	 $Koloms[] = array('align="left"',$isi['tpno_dokumen_sumber']);
	 $Koloms[] = array('align="center"',$tp_tgl_dokumen_sumber);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 include('pages/pengadaanpenerimaan/pemasukan_ins.php');
	 
	 
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
	
	$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmBARANG = cekPOST('fmBARANG');
	
	$qrybidang = "SELECT k, concat(k,'. ',nm_rekening) as nama FROM ref_rekening GROUP BY k";
	
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
			$vOrder=''
				/*genFilterBar(
					array(
						$pemasukan_ins->isiform(
							array(
								array(
									'label'=>'BIDANG',
									'label-width'=>'150px;',
									'value'=>cmbQuery("fmBIDANG",$fmBIDANG,"select k,concat(k,'. ',nm_rekening) from ref_rekening where k!='0' and l ='0' and m = '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih',''),
								),
								array(
									'label'=>'KELOMPOK',
									'label-width'=>'150px;',
									'value'=>cmbQuery("fmKELOMPOK",$fmKELOMPOK,"select l,concat(l,'. ',nm_rekening) from ref_rekening where k='$fmBIDANG' and l !='0' and m = '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih',''),
								),
								array(
									'label'=>'SUB KELOMPOK',
									'label-width'=>'150px;',
									'value'=>cmbQuery("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select m,concat(m,'. ',nm_rekening) from ref_rekening where k='$fmBIDANG' and l ='$fmKELOMPOK' and m != '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih',''),
								),
								array(
									'label'=>'SUB SUB KELOMPOK',
									'label-width'=>'150px;',
									'value'=>cmbQuery("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select n,concat(n,'. ',nm_rekening) from ref_rekening where k='$fmBIDANG' and l ='$fmKELOMPOK' and m = '$fmSUBKELOMPOK' and n!='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih',''),
								),
							
						)						
					)
				)
				,'','','').
				genFilterBar(
					array(
						"<table style='width:100%'>
						<tr><td>
							Kode Rekening : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' size=20px>&nbsp
							Nama Rekening : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
							<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
						</td></tr>
						</table>"					
					)
				,'','','')*/
			/*genFilterBar(
				array(							
					WilSKPD_ajx3($this->Prefix.'SKPD'),
					),			
				'','');*/
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
		
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];
		$fmKODE = cekPOST('fmKODE');
		$fmBARANG = cekPOST('fmBARANG');
		
		$c1 = $_REQUEST['c1_cari'];
		$c = $_REQUEST['c_cari'];
		$d = $_REQUEST['d_cari'];
		$e = $_REQUEST['e_cari'];
		$e1 = $_REQUEST['e1_cari'];
		$jns_tra = $_REQUEST['jns_tra'];
		//Cari 
		/*switch($fmPILCARI){			
			case 'selectSatuan': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	*/
		if($fmBIDANG !='')$arrKondisi[] = " k = '$fmBIDANG'";
		if($fmKELOMPOK !='')$arrKondisi[] = " l = '$fmKELOMPOK'";
		if($fmSUBKELOMPOK !='')$arrKondisi[] = " m = '$fmSUBKELOMPOK'";
		if($fmSUBSUBKELOMPOK !='')$arrKondisi[] = " n = '$fmSUBSUBKELOMPOK'";
		$arrKondisi[] = " tp_c1 = '$c1'";
		$arrKondisi[] = " tp_c = '$c'";
		$arrKondisi[] = " tp_d = '$d'";
		$arrKondisi[] = " tp_e = '$e'";
		$arrKondisi[] = " tp_e1 = '$e1'";
		$arrKondisi[] = " tp_jns_trans = '$jns_tra'";
		$arrKondisi[] = " tpbiayaatribusi = '1'";
		$arrKondisi[] = " Id IS NULL";
		
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(k,'.',l,'.',m,'.',n,'.',o) like '".$_POST['fmKODE']."%'";			if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_rekening like '%".$_POST['fmBARANG']."%'";
		
		
		//$arrKondisi[] = " q='00'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		/*if($fmORDER1 == ''){
			$arrOrders[] = " p ";
			$arrOrders[] = " q ";
		}
		switch($fmORDER1){
			case '1': $arrOrders[] = " p $Asc1 " ;break;
		}	*/
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
	
	function setTopBar(){
	   	return '';
	}	
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$c1 = $_REQUEST['c1'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$jns_tra = $_REQUEST['jnsnya'];
				
		$form_name = $this->Prefix."Form";
		//$ref_jenis=$_REQUEST['ref_jenis'];
		//if($err==''){
			$FormContent = $this->genDaftarInitial($ref_jenis);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						900,
						500,
						'CARI ID PENERIMAAN',
						'',
						/*"
						<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".*/
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' value='$c1' id='c1_cari'  name='c1_cari'>".
						"<input type='hidden' value='$c' id='c_cari' name='c_cari' >".
						"<input type='hidden' value='$d' id='d_cari' name='d_cari' >".
						"<input type='hidden' value='$e' id='e_cari' name='e_cari' >".
						"<input type='hidden' value='$e1' id='e1_cari' name='e1_cari' >".
						"<input type='hidden' value='$jns_tra' id='jns_tra' name='jns_tra' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
}
$cariIdPenerima = new cariIdPenerimaObj();
?>