<?php

class t_keluargaObj  extends DaftarObj2{	
	var $Prefix = 't_keluarga';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_keluarga'; //daftar
	var $TblName_Hapus = 't_keluarga';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('nama');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'DATA KELUARGA';
	var $PageIcon = 'images/pemindahtanganan_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='DATA KELUARGA.xls';
	var $Cetak_Judul = 'DATA KELUARGA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 't_keluargaForm'; 
	var $pemisahID = ';';	
	var $arrStKeluarga = array( 
	array('1','SUAMI'),
	array('2','ISTRI'),
	array('3','ANAK')
	);	
	var $arrPendidikan = array( 
	array('1','SD'),
	array('2','SMP'),
	array('3','SMA/SMK'),
	array('4','D3'),
	array('5','S1'),
	array('6','S2'),
	array('7','S3'),
	);
	
	function setTitle(){
		return 'DATA KELUARGA';
	}
	
	function setMenuEdit(){		
		return "";
	}
	
	function setMenuView(){
		return "";
	}
	
	function setTopBar(){
		return ""; 
		//genSubTitle($this->setTitle(),$this->genMenu());
	}		
	
	function simpan(){
			
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$nama_lengkap = $_REQUEST['nama_lengkap'];
		$st_keluarga = $_REQUEST['st_keluarga'];
		$tmt_perubahan = $_REQUEST['tmt_perubahan'];
		$jumlah = $_REQUEST['jumlah'];
		$st_tunj = $_REQUEST['st_tunj'];
		if( $err=='' && $nama_lengkap =='' ) $err= 'NAMA LENGKAP Belum Di Isi !';
		if( $err=='' && $st_keluarga =='' ) $err= 'STATUS KELUARGA Belum Di Pilih !';
		if( $err=='' && $tmt_perubahan =='' ) $err= 'TMT PERUBAHAN Belum Di Isi !';
		if( $err=='' && $jumlah =='' ) $err= 'JUMLAH Belum Di Isi !';
		if( $err=='' && $st_tunj =='' ) $err= 'STATUS TUNJANGAN	 Belum Di Pilih !';
			
		if($err=='' && $fmST == 0){
			$aqry = "INSERT into ref_sumber_dana (nama) values('$nama')";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
		}else{				
			$aqry = "UPDATE ref_sumber_dana SET nama='$nama' WHERE nama='".$idplh."'";	$cek .= $aqry;
			$qry = mysql_query($aqry);
		}
		
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
			
			case 'windowshow':{
				$fm = $this->windowShow();
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
	
	function windowShow(){		
			$cek = ''; $err=''; $content=''; 
			$json = TRUE;	//$ErrMsg = 'tes';		
			$form_name = $this->FormName;
			
				$FormContent = $this->genDaftarInitial('');
				$form = centerPage(
						"<form name='$form_name' id='$form_name' method='post' action=''>".
						createDialog(
							$form_name.'_div', 
							$FormContent,
							1000,
							500,
							'DATA KELUARGA',
							'',
							"<input type='button' value='Simpan' onclick ='".$this->Prefix.".windowSave()' >&nbsp;".
							"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
							"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh'>".
							"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
							,//$this->setForm_menubawah_content(),
							$this->form_menu_bawah_height
						).
						"</form>"
				);
				$content = $form;//$content = 'content';
			
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setPage_OtherScript(){
		$scriptload = 
		
		"<script>
		$(document).ready(function(){ 
			".$this->Prefix.".loading();
			});
		
		</script>";
		
		return 
		//"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
		//"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
		//"<script src='js/jquery.js' type='text/javascript'></script>".
		//"<script src='js/jquery-ui.js' type='text/javascript'></script>".
		"<link rel='stylesheet' type='text/css' href='js/master/ref_template/jquery-ui.css'>".
		"<script src='js/master/ref_template/jquery.js' type='text/javascript' language='JavaScript' ></script>".
		"<script src='js/master/ref_template/jquery-ui.min.js' type='text/javascript' language='JavaScript'></script>".
		"<script type='text/javascript' src='js/pegawai/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";	
		$scriptload;
	}
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek = $cbid[0];	
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 0;	
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
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  $this->TblName WHERE nama= '".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
	
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){	
		global $Main;
		$cek = ''; $err=''; $content=''; 		
		$json = TRUE;	//$ErrMsg = 'tes';	 	
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 570;
		$this->form_height = 300;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
		}else{
			$this->form_caption = 'Edit';		
		}
		$query = "" ;$cek .=$query;
		$res = mysql_query($query);
		
		//items ----------------------
		$this->form_fields = array(		
		'nama' => array( 
		'label'=>'NAMA LENGKAP',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nama_lengkap' id='nama_lengkap' value='".$dt['nama_lengkap']."' size='33' >",
		),					 
		'st_keluarga' => array( 
		'label'=>'STATUS KELUARGA', 
		'labelWidth'=>200, 
		'value'=>cmbArray('st_keluarga',$dt['st_keluarga'],$this->arrStKeluarga,'--PILIH--','') 
		),		
		'jk' => array( 
		'label'=>'JENIS KELAMIN',
		'labelWidth'=>200, 
		'value'=>"<input type='radio' name='jk' id='jk' value='1'>&nbsp;LAKI-LAKI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type='radio' name='jk' id='jk' value='2'>&nbsp;PEREMPUAN",
		),
		'ttl' => array( 
		'label'=>'TEMPAT/TANGGAL LAHIR',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmp_lahir' id='tmp_lahir' value='".$dt['tmp_lahir']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tgl_lahir' id='tgl_lahir' size='8' class='datepicker' title='TANGGAL LAHIR'>",
		),
		'pendidikan' => array( 
		'label'=>'PENDIDIKAN', 
		'value'=>cmbArray('pendidikan',$dt['pendidikan'],$this->arrPendidikan,'--PILIH--','')  
		),
		'jurusan' => array( 
		'label'=>'JURUSAN',
		'value'=>"<input type='text' name='jurusan' id='jurusan' value='".$dt['jurusan']."' size=33 >",
		'type'=>'' 
		), 
		'pekerjaan' => array( 
		'label'=>'PEKERJAAN',
		'value'=>"<input type='text' name='pekerjaan' id='pekerjaan' value='".$dt['pekerjaan']."' size=33 >",
		'type'=>'' 
		),
		'tmt_perubahan' => array( 
		'label'=>'TMT PERUBAHAN',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_perubahan' id='tmt_perubahan' size='8' class='datepicker' title='TMT PERUBAHAN'>",
		), 
		'pekerjaan' => array( 
		'label'=>'PEKERJAAN',
		'value'=>"<input type='text' name='pekerjaan' id='pekerjaan' value='".$dt['pekerjaan']."' size=33 >",
		'type'=>'' 
		),	
		'jumlah' => array( 
		'label'=>'JUMLAH',
		'value'=>"<input type='text' name='jumlah' id='jumlah' value='".$dt['jumlah']."' size=33 >",
		'type'=>'' 
		),		
		'st_tunj' => array( 
		'label'=>'STATUS TUNJANGAN',
		'labelWidth'=>200, 
		'value'=>"<input type='radio' name='st_tunj' id='st_tunj' value='1'>&nbsp;PENERIMA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type='radio' name='st_tunj' id='st_tunj' value='2'>&nbsp;BUKAN PENERIMA",
		),	
		'ket' => array( 
		'label'=>'KETERANGAN',
		'value'=>"<input type='text' name='ket' id='ket' value='".$dt['ket']."' size=33 >",
		'type'=>'' 
		),	
		);
		//tombol
		$this->form_menubawah =	
		"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >&nbsp;".
		"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$Menu=
				"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
				<div class='menuBar2' style='' >
				<ul>
				$Menu
				<li><a  href='javascript:".$this->Prefix.".loading()' title='Refresh' class='btrefresh'></a></li>
				<li><a href='javascript:".$this->Prefix.".Hapus()' title='Hapus' class='btdel'></a></li>
				<li><a href='javascript:".$this->Prefix.".Edit()' title='Edit' class='btedit'></a>
					<ul id='bgn_ulEntry'>
						<li style='width:470;top:-4;z-index:99;'></li>
					</ul>
				</li>
				<li><a  href='javascript:".$this->Prefix.".Baru()' title='Tambah' class='btadd'></a>
					<ul id='bgn_ulEntry'>
						<li style='width:470;top:-4;z-index:99;'>	</li>
					</ul>
				</li>
				<!--<li><a style='padding:2;width:55;color:white;font-size:11;' href='javascript:".$this->Prefix.".Loading()' title='Refresh' class=''>[ Refresh ]</a></li>-->
				</ul>	
				<!--<a id='pelihara_jmldata' style='cursor:default;position:relative;left:2;top:2;color:gray;font-size:11;' title='Jumlah'>[ $jmlTampilPLH ]</a>-->			
				</div>
				</td></tr></table>";
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
		"<thead>
		 <tr>
		   <th colspan='11'>$Menu</th>
		  </tr>
		<tr>
		<th class='th01' width='20' >No.</th>
		$Checkbox	
		<th class='th01' align='center'>NAMA LENGKAP</th>
		<th class='th01' align='center'>STATUS KELUARGA</th>
		<th class='th01' align='center'>JENIS KELAMIN</th>
		<th class='th01' align='center'>TEMPAT/TGL LAHIR</th>
		<th class='th01' align='center'>UMUR</th>
		<th class='th01' align='center'>PENDIDIKAN</th>
		<th class='th01' align='center'>JURUSAN</th>
		<th class='th01' align='center'>PEKERJAAN</th>
		<th class='th01' align='center'>JUMLAH(RP)</th>
		
		</tr>
		</thead>";
		
		return $headerTable;
		}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;	
		$Koloms = array();
		$Koloms[] = array('align="center"', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);	
		return $Koloms;
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmNAMA = cekPOST('fmNAMA');
		$arr = array(
		array('selectnama','Nama'),	
		);
		//data order ------------------------------
		$arrOrder = array(
		// array('1','Kode Barang'),
		array('1','Nama'),	
		);	
		
		$TampilOpt = 			
		"";
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmNAMA = cekPOST('fmNAMA');
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			case 'selectNama': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;	
		}
		if(!empty($_POST['fmNAMA'])) $arrKondisi[] = " nama like '%".$_POST['fmNAMA']."%'";	
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
}
$t_keluarga = new t_keluargaObj();
?>