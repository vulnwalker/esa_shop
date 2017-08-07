<?php
class t_pegawaiObj  extends DaftarObj2{	
	var $Prefix = 't_pegawai';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_pegawai'; //daftar
	var $TblName_Hapus = 't_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PEGAWAI';
	var $PageIcon = 'images/pemindahtanganan_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='PEGAWAI.xls';
	var $Cetak_Judul = 'PEGAWAI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 't_pegawaiForm'; 
	var $kdbrg = '';	
	var $pemisahID = ';';
	var $form_menu_bawah_height = 0;
	var $arrStatKep = array( //status kepegawaian
	array('1','CPNS'),
	array('2','PNS'),
	array('3','PENSIUNAN'),
	array('4','PINDAH KELUAR'),
	);
	var $arrAgama = array(
	array('1','ISLAM'),
	array('2','PROTESTAN'),
	array('3','KATOLIK'),
	array('4','HINDU'),
	array('5','BUDHA'),
	);
	var $arrStatPer = array( 
	array('1','BELUM KAWIN'),
	array('2','KAWIN'),
	array('3','JANDA'),
	array('4','DUDA'),
	);		
	var $arrKedPeg = array( //Kedudukan Pegawai
	array('01','AKTIF'),
	array('02','CLTN'),
	array('03','PERPANJANGAN CLTN'),
	array('04','TUGAS BELAJAR'),
	array('05','PEMBERHENTIAN SEMENTARA'),
	array('06','PENERIMA UANG TUNGGU'),
	array('07','WAJIB MILITER'),
	array('08','PNS YANG DINYATAKAN HILANG'),
	array('09','PEJABAT NEGARA'),
	array('10','KEPALA DESA'),
	array('11','KEBERATAN ATAS PENJATUHAN HUKUMAN DISIPLIN SESUAI PP.30/80'),
	);
	var $arrGolDar = array(
	array('1','A'),
	array('2','B'),
	array('3','AB'),
	array('4','O'),
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
	var $arrEselon = array( 
	array('1','ESELON I'),
	array('2','ESELON II'),
	array('3','ESELON III'),
	array('4','ESELON IV'),
	array('5','ESELON V')
	);
	var $arrJnsJabatan = array(
	array('1','STRUKTURAL'),
	array('2','FUNGSIONAL'),
	array('3','FUNGSIONAL UMUM'),
	);
	
	function setTitle(){
		return 'PEGAWAI';
	}
	
	function setMenuEdit(){
		return
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
		"</td>";
	}
	
	function simpan(){		
	global $HTTP_COOKIE_VARS,$Main;
	$uid = $HTTP_COOKIE_VARS['coID'];
	$cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
	$nama = $_REQUEST['nama'];
	
		if($fmST == 0){
		/*$ck1=mysql_fetch_array(mysql_query("Select * from ref_sumber_dana where nama='$nama'"));
		if ($ck1>=1)$err= 'Gagal Simpan'.mysql_error();
		if($err=='')
		{
			$aqry = "INSERT into ref_sumber_dana (nama) values('$nama')";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
		}
		}else{						
		if($err=='')
		{
			$aqry = "UPDATE ref_sumber_dana SET nama='$nama' WHERE nama='".$idplh."'";	$cek .= $aqry;
			$qry = mysql_query($aqry) or die(mysql_error());
		}
		*/
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
		switch($tipe)
		{
			case 'pilihPangkat':{				
				$fm = $this->pilihPangkat();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'autocomplete_getdata':{//cari nama kegiatan 
				$json = FALSE;
				$fm = $this->autocomplete_getdata();
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
	
	function autocomplete_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		$sql = "SELECT * from ref_pegawai WHERE jabatan like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs))
		{
			//$label =;			
			$a_json_row["id"] = $row['Id'];
			$a_json_row["value"] = $row['jabatan'];
			$a_json_row["label"] =  $row['jabatan'];
			array_push($a_json, $a_json_row);
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
		}
	
	function pilihPangkat(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$jns = $_REQUEST['jns'];	 
		if($jns==1)
		{
			$idpangkat = $_REQUEST['pangkatcpns'];
		}
		else{
			$idpangkat = $_REQUEST['pangkatakhir'];
		}
		$query = "select concat(gol,'/',ruang)as nama FROM ref_pangkat WHERE id='$idpangkat'" ;
		$get=mysql_fetch_array(mysql_query($query));//$cek.=$query;
		$content=$get['nama'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setPage_OtherScript(){
		$scriptload = 
		"<script>
			$(document).ready(function(){
				".$this->Prefix.".loading();
			}
		);
		</script>";
		return 
		//"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
		//"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
		//"<script src='js/jquery.js' type='text/javascript'></script>".
		//"<script src='js/jquery-ui.js' type='text/javascript'></script>".
		"<link rel='stylesheet' type='text/css' href='js/master/ref_template/jquery-ui.css'>".
		"<script src='js/master/ref_template/jquery.js' type='text/javascript' language='JavaScript' ></script>".
		"<script src='js/master/ref_template/jquery-ui.min.js' type='text/javascript' language='JavaScript'></script>".
		"<script src='js/skpd.js' type='text/javascript'></script>".
		"<script type='text/javascript' src='js/pegawai/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
		"<script type='text/javascript' src='js/pegawai/t_keluarga.js' language='JavaScript' ></script>".
		$scriptload;
	}
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 0;	
		$dt=array();
		$this->form_fmST = 0;
		$dt['st_pegawai']=2;
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
	
	function setForm($dt)
	{
		$cek = ''; $err=''; $content=''; 		
		$json = TRUE;	//$ErrMsg = 'tes';	 	
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 375;
		$this->form_height = 100;
		if ($this->form_fmST==0)
		{
			$this->form_caption = 'Baru';
			$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
			$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
			$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
			$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
			$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		}
		else{
			$this->form_caption = 'Edit';
		}
		$queryc1= "select UCASE(nm_skpd)as nm_skpd from ref_skpd where c1='$c1' and c ='00' and d='00' and e ='00' and e1 ='000'" ;
		$queryc = "select UCASE(nm_skpd)as nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ;
		$queryd = "select UCASE(nm_skpd)as nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ;
		$querye = "select UCASE(nm_skpd)as nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d = '$d' and e='$e' and e1='000'" ;
		$querye1 = "select UCASE(nm_skpd)as nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1'" ;
		$getc1=mysql_fetch_array(mysql_query($queryc1));
		$getc=mysql_fetch_array(mysql_query($queryc));
		$getd=mysql_fetch_array(mysql_query($queryd));
		$gete=mysql_fetch_array(mysql_query($querye));
		$gete1=mysql_fetch_array(mysql_query($querye1));
		$urusan = $getc1['nm_skpd'];
		$bidang = $getc['nm_skpd'];
		$skpd = $getd['nm_skpd'];
		$unit = $gete['nm_skpd'];
		$subunit = $gete1['nm_skpd'];
		$queryPangkat = "select id,nama from ref_pangkat order by gol,ruang" ;
		//items ----------------------
		$this->form_fields = array(
		'div_skpd' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'>", 
		'type'=>'merge' 
		),
		'c1' => array( 
		'label'=>'URUSAN',
		'labelWidth'=>200, 
		'value'=>$urusan."<input type='hidden' name='c1' value='$c1' size='2'>",
		'type'=>'' 
		),
		'c' => array( 
		'label'=>'BIDANG',
		'labelWidth'=>200, 
		'value'=>$bidang."<input type='hidden' name='c' value='$c' size='2'>",
		'type'=>'' 
		),
		'd' => array( 
		'label'=>'SKPD',
		'labelWidth'=>200, 
		'value'=>$skpd."<input type='hidden' name='d' value='$d' size='2'>",
		'type'=>'' 
		),
		'e' => array( 
		'label'=>'UNIT',
		'labelWidth'=>200, 
		'value'=>$unit."<input type='hidden' name='e' value='$e' size='2'>",
		'type'=>'' 
		),
		'e1' => array( 
		'label'=>'SUB UNIT',
		'labelWidth'=>200, 
		'value'=>$subunit."<input type='hidden' name='e1' value='$e1' size='2'>",
		'type'=>'' 
		),
		'div_skpd_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),
		'wajib' => array( 
		'label'=>"<font color=red>* Wajib di isi !</font>",
		'labelWidth'=>200, 
		'value'=>'', 
		'pemisah'=>' '
		),		
		'div_bio' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>INDENTITAS PEGAWAI</span>", 
		'type'=>'merge' 
		),		
		'div_bio' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>INDENTITAS PEGAWAI</span>", 
		'type'=>'merge' 
		),				 
		'st_pegawai' => array( 
		'label'=>'STATUS PEGAWAI', 
		'labelWidth'=>200, 
		'value'=>cmbArray('st_pegawai',$dt['st_pegawai'],$this->arrStatKep,'--PILIH--','')."&nbsp;&nbsp;<font color=red>*</font>&nbsp;<input type='checkbox' name='pindah_masuk' id='pindah_masuk' value=1>&nbsp;PINDAH MASUK" 
		),
		'kedudukan_pegawai' => array( 
		'label'=>'KEDUDUKAN PEGAWAI', 
		'labelWidth'=>200, 
		'value'=>cmbArray('kedudukan_pegawai',$dt['kedudukan_pegawai'],$this->arrKedPeg,'--PILIH--','')."&nbsp;&nbsp;<font color=red>*</font>" 
		),
		'tmt_status' => array( 
		'label'=>'TMT STATUS PEGAWAI',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_status' id='tmt_status' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'tmt_kedudukan' => array( 
		'label'=>'TMT KEDUDUKAN PEGAWAI',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_kedudukan' id='tmt_kedudukan' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'nip_baru' => array( 
		'label'=>'NIP BARU',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nip_baru' id='nip_baru' value='".$dt['nip_baru']."'  onkeypress='return isNumberKey(event)' size='33' >",
		),
		'nama' => array( 
		'label'=>'NAMA LENGKAP',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nama_lengkap' id='nama_lengkap' value='".$dt['nama_lengkap']."' size='33' >&nbsp;&nbsp;<font color=red>*</font>",
		),
		'ttl' => array( 
		'label'=>'TEMPAT/TANGGAL LAHIR',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmp_lahir' id='tmp_lahir' value='".$dt['tmp_lahir']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tgl_lahir' id='tgl_lahir' size='8' class='datepicker'>",
		),
		'jk' => array( 
		'label'=>'JENIS KELAMIN',
		'labelWidth'=>200, 
		'value'=>"<input type='radio' name='jk' id='jk' value='1'>&nbsp;LAKI-LAKI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type='radio' name='jk' id='jk' value='2'>&nbsp;PEREMPUAN&nbsp;&nbsp;<font color=red>*</font>",
		),
		'agama' => array( 
		'label'=>'AGAMA', 
		'value'=>"".cmbArray('agama',$dt['agama'],$this->arrAgama,'--PILIH--','')."&nbsp;&nbsp;<font color=red>*</font>" 
		),
		'gol_darah' => array( 
		'label'=>'GOLONGAN DARAH', 
		'value'=>"".cmbArray('gol_darah',$dt['gol_darah'],$this->arrGolDar,'--PILIH--','')."&nbsp;&nbsp;<font color=red>*</font>" 
		),
		'pendidikan' => array( 
		'label'=>'PENDIDIKAN TERAKHIR', 
		'value'=>"".cmbArray('pendidikan_terakhir',$dt['pendidikan_terakhir'],$this->arrPendidikan,'--PILIH--','')."&nbsp;&nbsp;<font color=red>*</font>&nbsp;<input type='button' value='RIWAYAT'>"  
		),
		'jurusan' => array( 
		'label'=>'JURUSAN',
		'value'=>"<input type='text' name='jurusan' id='jurusan' value='".$dt['jurusan']."' size=33 >",
		'type'=>'' 
		),
		'gaji_pokok' => array( 
		'label'=>'GAJI POKOK', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("gaji_pokok",'size=33',$dt['gaji_pokok'],'')."<font color=red>*</font>", 
		'type'=>'' 
		),				 			 	
		'st_kawin' => array( 
		'label'=>'STATUS PERKAWINAN', 
		'value'=>cmbArray('st_kawin',$dt['st_kawin'],$this->arrStatPer,'--PILIH--',"onChange='".$this->Prefix.".pilihSTkawin()'")."&nbsp;<input type='button' id='dt_keluarga' value='DATA KELUARGA' onClick='".$this->Prefix.".TampilDTKeluarga()'>" 
		),
		'alamat' => array( 'label'=>'ALAMAT LENGKAP', 
		'value'=>"<textarea style='width:320;' id='alamat' name='alamat'>".$dt['alamat']."</textarea>&nbsp;&nbsp;<font color=red>*</font>", 
		'row_params'=>"valign='top'",
		'labelWidth'=>200, 'type'=>'' 
		),
		'rt' => array( 
		'label'=>'RT/RW',
		'value'=>"<input type='text' name='rt' id='rt' value='".$dt['rt']."' size=2 onkeypress='return isNumberKey(event)'>&nbsp;/&nbsp;
		<input type='text' name='rw' id='rw' value='".$dt['rw']."' size=2 onkeypress='return isNumberKey(event)'>",
		'type'=>'' 
		),
		'kampung' => array( 
		'label'=>'KAMPUNG/KOMPLEK',
		'value'=>"<input type='text' name='kampung' id='kampung' value='".$dt['alamat_kel']."' size=33 >",
		'type'=>'' 
		),
		'alamat_kel' => array( 
		'label'=>'KELURAHAN/DESA',
		'value'=>"<input type='text' name='kelurahan' id='kelurahan' value='".$dt['kelurahan']."' size=33 >",
		'type'=>'' 
		),
		'alamat_kec' => array( 
		'label'=>'KECAMATAN', 
		'value'=>"<input type='text' name='kecamatan' id='kecamatan' value='".$dt['kecamatan']."' size=33 >",
		'type'=>'' 
		),
		'kab_kota' => array( 
		'label'=>'KOTA/KABUPATEN', 
		'value'=>"<input type='text' name='kota' id='kota' value='".$dt['kota']."' size=33>", 
		'type'=>'' 
		),
		'no_hp' => array( 
		'label'=>'NO HP/TELP', 
		'value'=>"<input type='text' name='no_hp' id='no_hp' value='".$dt['no_hp']."' size=33 onkeypress='return isNumberKey(event)'>&nbsp;&nbsp;<font color=red>*</font>", 
		'type'=>'' 
		),
		'email' => array( 
		'label'=>'EMAIL', 
		'value'=>"<input type='text' name='email' id='email' value='".$dt['email']."' size=33>", 
		'type'=>'' 
		),			
		'no_karpeg' => array( 
		'label'=>'NO KARPEG', 
		'value'=>"<input type='text' name='no_karpeg' id='no_karpeg' value='".$dt['no_karpeg']."' size=33 onkeypress='return isNumberKey(event)'>&nbsp;&nbsp;<font color=red>*</font>", 
		'type'=>'' 
		),
		'npwp' => array( 
		'label'=>'NPWP', 
		'value'=>"<input type='text' name='npwp' id='npwp' value='".$dt['npwp']."' size=33 onkeypress='return isNumberKey(event)'>", 
		'type'=>'' 
		),
		'no_bpjs' => array( 
		'label'=>'NO ASKES/BPJS', 
		'value'=>"<input type='text' name='no_bpjs' id='no_bpjs' value='".$dt['no_bpjs']."' size=33 onkeypress='return isNumberKey(event)'>", 
		'type'=>'' 
		),
		'no_bpjs' => array( 
		'label'=>'NO KTP/E-KTP', 
		'value'=>"<input type='text' name='no_ktp' id='no_ktp' value='".$dt['no_ktp']."' size=33 onkeypress='return isNumberKey(event)'>", 
		'type'=>'' 
		),
		'div_bio_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),	
		'div_cpns' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>PENGANGKATAN CPNS</span>", 
		'type'=>'merge' 
		),
		'pej_penetapancpns' => array( 
		'label'=>'PEJABAT YANG MENETAPKAN', 
		'value'=>"<input type='text' name='pejabat_penetapan' id='pejabat_penetapan' value='".$dt['pejabat_penetapan']."' size=33>", 
		'type'=>'' 
		),
		'sk_cpns' => array( 
		'label'=>'NO/TANGGAL SK',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nosk_cpns' id='nosk_cpns' value='".$dt['nosk_cpns']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tglsk_cpns' id='tglsk_cpns' size='8' class='datepicker'>",
		),
		'pangkatcpns' => array( 
		'label'=>'PANGKAT/GOL/RUANG', 
		'labelWidth'=>200, 
		'value'=>cmbQuery('pangkatcpns',$dt['pangkatcpns'],$queryPangkat,"onChange='".$this->Prefix.".pilihPangkat(1)'",'--PILIH--')."&nbsp;/&nbsp;<input type='text' name='golang_cpns' id='golang_cpns' size=1 value='".$dt['golang_cpns']."' readonly>"
		),
		'jabatancpns' => array( 
		'label'=>'JABATAN', 
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='jabatancpns' id='jabatancpns' value='".$dt['jabatancpns']."' size='33' >"
		),
		'tmt_cpns' => array( 
		'label'=>'TMT CPNS',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_cpns' id='tmt_cpns' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'makerlama' => array( 
		'label'=>'MASA KERJA LAMA',
		'value'=>"<input type='text' name='makerlama_thn' id='makerlama_thn' value='".$dt['makerlama_thn']."' placeholder='THN' size=2 onkeypress='return isNumberKey(event)'>&nbsp;/&nbsp;
		<input type='text' name='makerlama_bln' id='makerlama_bln' value='".$dt['makerlama_bln']."' placeholder='BLN' size=2 onkeypress='return isNumberKey(event)'>",
		'type'=>'' 
		),
		'div_cpns_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),
		'div_kgb' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>KENAIKAN GAJI BERKALA (KGB) TERAKHIR</span>", 
		'type'=>'merge' 
		),
		'sk_kgb' => array( 
		'label'=>'NO/TANGGAL KGB',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nosk_kgb' id='nosk_kgb' value='".$dt['nosk_kgb']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tglsk_kgb' id='tglsk_kgb' size='8' class='datepicker'>&nbsp;<input type='button' value='RIWAYAT'>"
		),
		'tmt_kgb' => array( 
		'label'=>'TMT KGB',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_kgb' id='tmt_kgb' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'div_kgb_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),
		'div_pangkat' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>PANGKAT/GOL/RUANG TERAKHIR</span>", 
		'type'=>'merge' 
		),
		'sk_pt' => array( 
		'label'=>'NO/TANGGAL SK',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nosk_pangkatakhir' id='nosk_pangkatakhir' value='".$dt['nosk_pangkatakhir']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tglsk_pangkat' id='tglsk_pangkat' size='8' class='datepicker'>",
		),
		'pangkatakhir' => array( 
		'label'=>'PANGKAT/GOL/RUANG', 
		'labelWidth'=>200, 
		'value'=>cmbQuery('pangkatakhir',$dt['pangkatakhir'],$queryPangkat,"onChange='".$this->Prefix.".pilihPangkat(2)'",'--PILIH--')."&nbsp;/&nbsp;<input type='text' name='golang_akhir' id='golang_akhir' size=1 value='".$dt['golang_akhir']."' readonly>&nbsp;&nbsp;<font color=red>*</font>"
		),
		'tmt_pangkat' => array( 
		'label'=>'TMT PANGKAT',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_pangkatakhir' id='tmt_pangkatakhir' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'makerbaru' => array( 
		'label'=>'MASA KERJA BARU',
		'value'=>"<input type='text' name='makerbaru_thn' id='makerbaru_thn' value='".$dt['makerbaru_thn']."' placeholder='THN' size=2 onkeypress='return isNumberKey(event)'>&nbsp;/&nbsp;
		<input type='text' name='makerbaru_bln' id='makerbaru_bln' value='".$dt['makerbaru_bln']."' placeholder='BLN' size=2 onkeypress='return isNumberKey(event)'>",
		'type'=>'' 
		),
		'div_pangkat_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),
		'div_jabatan' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>JABATAN TERAKHIR</span>", 
		'type'=>'merge' 
		),
		'jns_jabatan' => array( 
		'label'=>'JENIS JABATAN', 
		'labelWidth'=>200, 
		'value'=>cmbArray('jns_jabatan',$dt['jns_jabatan'],$this->arrJnsJabatan,'--PILIH--','') 
		),
		'jabatan_akhir' => array( 
		'label'=>'JABATAN', 
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='jabatanakhir' id='jabatanakhir' value='".$dt['jabatanakhir']."' size='33' >&nbsp;<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"jabatanakhir\").value=\"\";'>&nbsp;&nbsp;<font color=red>*</font>"
		),
		'eselon' => array( 
		'label'=>'ESELON', 
		'value'=>"".cmbArray('eselon',$dt['eselon'],$this->arrEselon,'--PILIH--','')
			),
		'sk_jabatan_akhir' => array( 
		'label'=>'NO/TANGGAL SK',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='nosk_jabatanakhir' id='nosk_jabatanakhir' value='".$dt['nosk_jabatanakhir']."' size='33' >&nbsp;/&nbsp;
		<input type='text' name='tglsk_jabatanakhir' id='tglsk_jabatanakhir' size='8' class='datepicker'>",
		),
		'tmt_jabatanakhir' => array( 
		'label'=>'TMT JABATAN',
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='tmt_jabatanakhir' id='tmt_jabatanakhir' size='8' class='datepicker'>&nbsp;&nbsp;<font color=red>*</font>",
		),
		'div_jabatan_akhir_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),
		'div_rinciangaji' => array( 
		'label'=>'', 
		'value'=>"<table width='100%' class='adminform'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>RINCIAN GAJI TERAKHIR</span>",
		'type'=>'merge' 
		), 
		'tunj_keluarga' => array( 
		'label'=>'TUNJANGAN KELUARGA',
		'labelWidth'=>200,  
		'value'=>inputFormatRibuan("tunj_keluarga",'size=33',$dt['tunj_keluarga'],''), 
		'type'=>'' 
		),
		'tunj_jabatan' => array( 
		'label'=>'TUNJANGAN JABATAN',
		'labelWidth'=>200,  
		'value'=>inputFormatRibuan("tunj_jabatan",'size=33',$dt['tunj_jabatan'],''), 
		'type'=>'' 
		),
		'tunj_fungsional' => array( 
		'label'=>'TUNJANGAN FUNGSIONAL', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("tunj_fungsional",'size=33',$dt['tunj_fungsional'],''), 
		'type'=>'' 
		),
		'tunj_fungsionalumum' => array( 
		'label'=>'TUNJANGAN FUNGSIONAL UMUM', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("tunj_fungsionalumum",'size=33',$dt['tunj_fungsionalumum'],''), 
		'type'=>'' 
		),
		'tunj_beras' => array( 
		'label'=>'TUNJANGAN BERAS',
		'labelWidth'=>200,  
		'value'=>inputFormatRibuan("tunj_beras",'size=33',$dt['tunj_beras'],''), 
		'type'=>'' 
		),
		'tunj_pph' => array( 
		'label'=>'TUNJANGAN PPH', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("tunj_pph",'size=33',$dt['tunj_pph'],''), 
		'type'=>'' 
		),
		'pembulatan_gaji' => array( 
		'label'=>'PEMBULATAN GAJI', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("pembulatan_gaji",'size=33',$dt['pembulatan_gaji'],''), 
		'type'=>'' 
		),
		'iuran_jamkes' => array( 
		'label'=>'IURAN JAMINAN KESEHATAN', 
		'labelWidth'=>200, 
		'value'=>inputFormatRibuan("iuran_jamkes",'size=33',$dt['iuran_jamkes'],''), 
		'type'=>'' 
		),	
		'ket' => array( 
		'label'=>'KETERANGAN', 
		'labelWidth'=>200, 
		'value'=>"<input type='text' name='ket' id='ket' value='".$dt['ket']."' size=33 >", 
		'type'=>'' 
		),
		'div_rinciangaji_' => array( 
		'label'=>'',
		'value'=>"</table class='adminform'>",
		'type'=>'merge'  
		),			
		/*'menu' => array( 
		'label'=>'',
		'value'=>"<table width='100%' class='menudottedline'>
		<tbody>
		<tr>
		<td>
		<table width='50'>
		<tbody>
		<tr>				
		<td>					
		<table cellpadding='0' cellspacing='0' border='0' id='toolbar' >
		<tbody>
		<tr valign='middle' align='center'> 
		<td class='border:none'> 
		<a class='toolbar' id='btsave' 
		href='javascript:".$this->Prefix.".Simpan()'> 
		<img src='images/administrator/images/save_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Simpan'> Simpan</a> 
		</td> 
		</tr> 
		</tbody>
		</table> 
		</td>
		<td>			
		<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
		<tbody>
		<tr valign='middle' align='center'> 
		<td class='border:none'> 
		<a class='toolbar' id='btbatal' href='javascript:".$this->Prefix.".Close()'>
		<img src='images/administrator/images/cancel_f2.png' alt='Batal' name='batal' width='32' height='32' border='0' align='middle' title='Batal'> Batal</a> 
		</td> 
		</tr> 
		</tbody>
		</table> 
		</td>
		<td>			
		<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
		<tbody>
		<tr valign='middle' align='center'> 
		<td class='border:none'> 
		<a class='toolbar' id='btselesai' href='javascript:".$this->Prefix.".Selesai()'>
		<img src='images/administrator/images/checkin.png' alt='Selesai' name='Selesai' width='32' height='32' border='0' align='middle' title='Selesai'> Selesai</a> 
		</td> 
		</tr> 
		</tbody>
		</table> 
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>",
		'type'=>'merge'
		)*/
		);
		//tombol
		$this->form_menubawah =	'';
		/*"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >&nbsp;".
		"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";*/
		$form = $this->genForm2();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
		"<thead>
		<tr>
		<th class='th01' width='20' >No.</th>
		$Checkbox	
		<th class='th01' align='center'>NO KARPEG/<BR>NO ID</th>
		<th class='th01' align='center'>NAMA LENGKAP/<BR>NIP</th>
		<th class='th01' align='center'>ALAMAT LENGKAP</th>
		<th class='th01' align='center'>JENIS KELAMIN/<BR>GOL DARAH</th>
		<th class='th01' align='center'>PENDIDIKAN/<BR>PANGKAT/GOL/RUANG</th>
		<th class='th01' align='center'>JABATAN</th>
		<th class='th01' align='center'>SKPD/UNIT/SUBUNIT</th>
		<th class='th01' align='center'>KEDUDUKAN/<BR>STATUS</th>
		<th class='th01' align='center'>NO HP</th>
		<th class='th01' align='center'>KETERANGAN</th>
		<th class='th01' align='center'>VALID</th>
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
		$Koloms[] = array('align="left"',$isi['nama']);
		$Koloms[] = array('align="left"',$isi['nama']);
		return $Koloms;
	}
	
	function genForm2($withForm=TRUE){
		$form_name = 't_pegawai_form';	
		if($withForm)
		{
			$params->tipe=1;
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
			createDialog(
			$form_name.'_div', 
			$this->setForm_content(),
			$this->form_width,
			$this->form_height,
			$this->form_caption,
			'',
			//$this->form_menubawah.
			"<tr>
			<td colspan='3' height='50'><table width='100%' class='menudottedline'>
			<tbody>
			<tr>
			<td>
			<table width='50'>
			<tbody>
			<tr>				
			<td>					
			<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
			<tbody>
			<tr valign='middle' align='center'> 
			<td class='border:none'> 
			<a class='toolbar' id='btsave' href='javascript:".$this->Prefix.".Simpan()'> 
			<img src='images/administrator/images/save_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Simpan'> Simpan</a> 
			</td> 
			</tr> 
			</tbody>
			</table> 
			</td>
			<td>			
			<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
			<tbody>
			<tr valign='middle' align='center'> 
			<td class='border:none'> 
			<a class='toolbar' id='btbatal' href='javascript:".$this->Prefix.".Close()'>
			<img src='images/administrator/images/cancel_f2.png' alt='Batal' name='batal' width='32' height='32' border='0' align='middle' title='Batal'> Batal</a> 
			</td> 
			</tr> 
			</tbody>
			</table> 
			</td>
			<td>			
			<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
			<tbody>
			<tr valign='middle' align='center'> 
			<td class='border:none'> 
			<a class='toolbar' id='btselesai' href='javascript:".$this->Prefix.".Selesai()'>
			<img src='images/administrator/images/checkin.png' alt='Selesai' name='Selesai' width='32' height='32' border='0' align='middle' title='Selesai'> Selesai</a> 
			</td> 
			</tr> 
			</tbody>
			</table> 
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table></td>
			</tr>
			<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
			<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
			,//$this->setForm_menubawah_content(),
			$this->form_menu_bawah_height,
			'',$params
			).
			"</form>";
		}
		else{
			$form= 
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
			$this->form_menu_bawah_height
			);
		}
		/*$form = 
		centerPage(
		$form
		);*/
		return $form;
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmNAMA = cekPOST('fmNAMA');			
		$arr = array(
		array('selectnama','Nama'),	
		);
		//data order ------------------------------
		$arrOrder = array(
		array('1','Nama'),	
		);	
		$TampilOpt = 		
		"<div class='FilterBar'>".
		"<table style='width:100%'>
		<tr><td>
		".WilSKPD_ajxVW($this->Prefix.'Skpd')."
		</td></tr>
		</table>
		</div>";
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
$t_pegawai = new t_pegawaiObj();
?>