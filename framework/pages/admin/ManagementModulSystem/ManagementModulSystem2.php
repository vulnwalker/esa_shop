<?php
class ManagementModulSystem2Obj  extends DaftarObj2{	
	var $Prefix = 'ManagementModulSystem2';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'system_modul'; //bonus
	var $TblName_Hapus = 'system_modul';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id_modul');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Management Modul System';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='refmigrasi.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'ManagementModulSystem2';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ManagementModulSystem2Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	var $kel = array(
				array('1','DEVELOPMENT'), 
				array('2','SUPERADMIN'), 
				array('3','ADMINISTRATOR'), 
				array('4','MANAGER'), 
				array('5','OPERATOR'), 
				array('6','USER'), 
				array('7','CUSTOMER'), 
				array('8','PUBLIK'),
		);
		
	var $Status = array(
				array('1','AKTIF'), 
				array('2','TIDAK AKTIF'), 
		);	
	
	function setTitle(){
		return 'Modul System';
	}
	
	function setMenuEdit(){
		
		
	}
	
	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];	 
	 
	 $nourut= $_REQUEST['nourut']; 
	 $kode= $_REQUEST['kode'];
	 $nm_system= $_REQUEST['nm_system'];					
	 $kel_user= $_REQUEST['kel_user'];
	 $tgl_update= $_REQUEST['tgl_update'];			 
	 $username= $_REQUEST['username'];
	 $expired_date= $_REQUEST['expired_date'];
	 $status= $_REQUEST['status'];
	 
	 $tgl_update = explode("-",$tgl_update);
	 $tgl_update2 = $tgl_update[2].'-'.$tgl_update[1].'-'.$tgl_update[0];
	 
	 $expired_date = explode("-",$expired_date);
	 $expired_date2 = $expired_date[2].'-'.$expired_date[1].'-'.$expired_date[0];
	  
	  $oldy=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from system where no_urut='$nourut'"
		));
		$oldy2=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from system where no_urut='$nourut' and kode='$kode'"
		));
		$cek.="select count(*) as cnt from system where kode='$kode'";
	 if($err=='' && $oldy['cnt']>0) $err="No Urut '$nourut' Sudah Ada";
	 if($err=='' && $oldy2['cnt']>0) $err="Kode System '$kode' Sudah Ada";
	 if( $err=='' && $nourut =='' ) $err= 'No Urut Belum Di Isi !!';
	 if( $err=='' && $kode =='' ) $err= 'Kode System Belum Di Isi !!';
	 if( $err=='' && $nm_system =='' ) $err= 'Nama System Belum Di Isi !!';
	 if( $err=='' && $kel_user =='' ) $err= 'Kelompok User Belum Di Pilih !!';
	
	 if( $err=='' && $username =='' ) $err= 'User Name Belum Di Isi !!';
	 if( $err=='' && $status =='' ) $err= 'Status Belum Di Pilih !!';
		
			if($fmST == 0){
			 if($err=='' && $oldy['cnt']>0) $err="No Urut '$nourut' Sudah Ada";
				if($err==''){
					$aqry = "INSERT into system (no_urut,kode,nm_system,kel_user_system,status_system,tgl_update,uid,expired_date) values('$nourut','$kode','$nm_system','$kel_user','$status','$tgl_update2','$uid','$expired_date2')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{
						if($err==''){
						$aqry = "UPDATE system set no_urut='$nourut',kode='$kode',nm_system='$nm_system',kel_user_system='$kel_user',status_system='$status',tgl_update='$tgl_update2',expired_date='$expired_date2' where Id_system='".$idplh."'";	$cek .= $aqry;
								$qry = mysql_query($aqry) or die(mysql_error());
						}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	/*function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $cek = '';			
	 $err = '';			
	 $content = '';	
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 
	 if(isset($HTTP_COOKIE_VARS['coThnAnggaran'])){
	 	$coThnAnggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 }else{
	 	$coThnAnggaran = '2016';
	 }
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		
		
		$aq = "SELECT * FROM gambar WHERE ref_id = '$idplh' AND Jns='2' AND stat = '2'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("media/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar WHERE ref_id = '$idplh' AND Jns='2' AND stat = '2'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		$upd = "UPDATE gambar SET stat = '0', stat2='0' WHERE ref_id = '$idplh' AND Jns='2' AND stat = '1'";$cek .= ' ||'. $upd;
		$qryupd = mysql_query($upd);
		
		
				
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	*/
	
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
		
		case 'formUpload':{				
			$fm = $this->setFormUpload();				
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
		
		case 'batal':{
			$get= $this->batal();
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
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		
		case 'getdata':{
				$Id = $_REQUEST['id'];
				/*$k = substr($Id, 0,1);
				$l = substr($Id, 2,1);
				$m = substr($Id, 4,1);
				$n = substr($Id, 6,2);
				$o = substr($Id, 9,2);*/
				//$get = mysql_fetch_array( mysql_query("select * from system_modul where Id_modul='$Id'"));
				$get = mysql_fetch_array( mysql_query("SELECT `system`.`nm_system`, `system_modul`.`nm_modul`, `system_modul`.`Id_system`,`system_modul`.`Id_modul` FROM `system` RIGHT JOIN `system_modul` ON `system`.`Id_system` = `system_modul`.`Id_system`  where system_modul.Id_modul='$Id'"));
			
				
				$content = array('id' => $get['Id_modul'],'id_system' => $get['Id_system'], 'nm_system' => $get['nm_system'], 'nm_modul' => $get['nm_modul']);
					
				
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
						setTimeout(function myFunction() {ManagementSystem.nyalakandatepicker()},1000);
					</script>";
		return 	
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
			
			
		//	"<link rel='stylesheet' href='datepicker/jquery-ui.css' type='text/css'>".
			
			
			//"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<link rel='stylesheet' href='css/upload_style.css' type='text/css'>".
			"<script src='js/jquery.js' type='text/javascript'></script>".	
			
			
			/*"<script src='datepicker/jquery-1.12.4.js'></script>".
			 " <script src='datepicker/jquery-ui.js'></script>".*/
			
					
			"<script src='js/jquery-ui.js' type='text/javascript'></script>".
			"<script src='js/jquery.min.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/jquery.form.js'></script> ".
			"<script type='text/javascript' src='js/admin/ManagementModulSystem/ManagementModulSystem2.js' language='JavaScript' ></script>".
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			'.
			
			//"<script type='text/javascript' src='js/refpegawai/refpegawai.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		$ref_jenis=$_GET['status_filter'];
		$status_filter=1;
		//if($err==''){
			$FormContent = $this->genDaftarInitial(1);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						500,
						'Pilih Modul System',
						'',
						
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='CariBarang_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='CariBarang_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
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
		$dt = '';
		$aqry = "SELECT * FROM  system WHERE Id_system='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		/*if(mysql_num_rows($aqfile) > 0) {
			$dt['isifile'] = mysql_num_rows($aqfile);
			$dt['idfile'] = $qryfile['Id'];
			$dt['nmfile'] = $qryfile['nmfile'];
			$dt['nmfile_asli'] = $qryfile['nmfile_asli'];
		}else{
			$dt['isifile'] = 0;
		}*/
		
		$fm = $this->setFormEditdata($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormUpload(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$dt = '';
		$qry = "SELECT * FROM system WHERE Id_system='$this->form_idplh' LIMIT 0,1";$cek=$qry;
		$aq = mysql_query($qry);
		$dt = mysql_fetch_array($aq);
		
		$file = "SELECT * FROM gambar WHERE ref_id='$this->form_idplh' AND Jns='2' AND stat='0' LIMIT 0,1";$cek=$qry;
		$aqfile = mysql_query($file);
		$qryfile = mysql_fetch_array($aqfile);
		
		if(mysql_num_rows($aqfile) > 0) {
			$dt['isifile'] = mysql_num_rows($aqfile);
			$dt['idfile'] = $qryfile['Id'];
			$dt['nmfile'] = $qryfile['nmfile'];
			$dt['nmfile_asli'] = $qryfile['nmfile_asli'];
		}else{
			$dt['isifile'] = 0;
		}
		
		$fm = $this->setFormUploadData($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormUploadData($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 500;
	 $this->form_height = 180;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Manajement System - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Upload Management System';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
	//	$dt['status_system']
	//	$dt['kel_user_system']
	
	if($dt['kel_user_system']==1){
	 	$kel='DEVELOPMENT';
	 }elseif($dt['kel_user_system']==2){
	 	$kel='SUPERADMIN';
	 }elseif($dt['kel_user_system']==3){
	 	$kel='ADMINISTRATOR';
	 }elseif($dt['kel_user_system']==4){
	 	$kel='MANAGER';
	 }elseif($dt['kel_user_system']==5){
	 	$kel='OPERATOR';
	 }elseif($dt['kel_user_system']==6){
	 	$kel='USER';
	 }elseif($dt['kel_user_system']==7){
	 	$kel='CUSTOMER';
	 }elseif($dt['kel_user_system']==8){
	 	$kel='PUBLIK';
	 }
	
	if($dt['status_system']==1){
	 	$status='AKTIF';
	 }elseif($dt['status_system']==2){
	 	$status='TIDAK AKTIF';
	 }
		
	 //items ----------------------
	  $this->form_fields = array(
	  		'manajemen' => array( 
						'label'=>'',
						'labelWidth'=>150, 
						'value'=>'<b>Manajement System :</b>', 
						'type'=>'merge',
						'param'=>""
						 ),
			'nourut' => array( 
						'label'=>'NO URUT',
						'labelWidth'=>100,
						'value'=>$dt['no_urut'],
						 ),
			'KODE SYSTEM' => array( 
						'label'=>'KODE SYSTEM',
						'labelWidth'=>100,
						'value'=>$dt['kode'],
						 ),
			'KELOMPOK USER' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>$kel,
						 ),
			'TANGGAL UPDATE' => array( 
						'label'=>'TANGGAL UPDATE',
						'labelWidth'=>100,
						'value'=>TglInd($dt['tgl_update']),
						 ),
			'USER NAME' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100,
						'value'=>$dt['username'],
						 ),	
						 
			'EXPIRED DATE' => array( 
						'label'=>'EXPIRED DATE',
						'labelWidth'=>100,
						'value'=>TglInd($dt['expired_date']),
						 ),	
						 			 
			'STATUS' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>$status,
						 ),	
						 
			'scan_foto' => array( 
				'label'=>'Upload Arsip','labelWidth'=>150, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processUploadFile' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='ref_id' name='ref_id' value='".$dt['Id']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='Jns' name='Jns' value='2' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$dt['nmfile_asli']."</span>".
					"</form>".
					
					'', 
			 ),
			'progress' => array(
				'label'=>'','labelWidth'=>150, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			)			
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='jumlahbarangx' id='jumlahbarangx' value='$hitung'>".
			"<input type='hidden' name='c' id='c' value='".$dt['c']."'>".
			"<input type='hidden' name='d' id='d' value='".$dt['d']."'>".
			"<input type='hidden' name='e' id='e' value='".$dt['e']."'>".
			"<input type='hidden' name='e1' id='e1' value='".$dt['e1']."'>".
			"<input type='hidden' name='tahun' id='tahun' value='".$dt['tahun']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $cek = ''; $err=''; $content=''; 
	 $uid = $HTTP_COOKIE_VARS['coID'];		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	$this->form_width = 420;
	 $this->form_height = 220;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management System - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management System - Edit';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
	$tgl_dokumen_bast = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>150, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='".$dt['no_urut']."' style='width:30px;maxlength='3'>",
						 ),
						 
			'kode' => array( 
						'label'=>'KODE SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='kode' id='kode' maxlength='3' value='".$dt['kode']."' style='width:30px; '>",
						 ),				 		
			
			'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nm_system' id='nm_system' value='".$dt['nm_system']."' style='width:250px;'>",
						 ),	
			
			'kel_user' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>cmbArray('kel_user',$dt['kel_user_system'],$this->kel,'-- PILIH --','style="width:130px;"'),
						 ),	
						 
			/*'scan_foto' => array( 
				'label'=>'Upload Arsip','labelWidth'=>150, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processUploadFile' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='ref_id' name='ref_id' value='".$dt['Id']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='Jns' name='Jns' value='2' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$dt['nmfile_asli']."</span>".
					"</form>".
					
					'', 
			 ),
			'progress' => array(
				'label'=>'','labelWidth'=>150, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			),*/
			'tgl_update' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='$tgl_dokumen_bast' style='width:80px;'readonly />  "
										
								,						
								),				 
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='username' id='username' value='$uid' style='width:250px;'readonly>",
						 ),	
						 
			'expired_date' =>	array(
								'label'=>'EXPIRED DATE',
								'name'=>'expired_date',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='expired_date' id='expired_date' class='datepicker' value='$tgl_dokumen_bast' style='width:80px;' />  "
										
								,						
								),	
						 			 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status_system'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),				
			);
		//tombol
		$this->form_menubawah =
			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormEditdata($dt){	
	 global $SensusTmp;
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $cek = ''; $err=''; $content=''; 
	 $uid = $HTTP_COOKIE_VARS['coID'];		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	$this->form_width = 420;
	 $this->form_height = 220;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management System - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management System - Edit';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
	$tgl_dokumen_bast = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>150, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='".$dt['no_urut']."' style='width:30px;maxlength='3'>",
						 ),
						 
			'kode' => array( 
						'label'=>'KODE SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='kode' id='kode' maxlength='3' value='".$dt['kode']."' style='width:30px; '>",
						 ),				 		
			
			'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nm_system' id='nm_system' value='".$dt['nm_system']."' style='width:250px;'>",
						 ),	
			
			'kel_user' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>cmbArray('kel_user',$dt['kel_user_system'],$this->kel,'-- PILIH --','style="width:130px;"'),
						 ),	
						 
			/*'scan_foto' => array( 
				'label'=>'Upload Arsip','labelWidth'=>150, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processUploadFile' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='ref_id' name='ref_id' value='".$dt['Id']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='Jns' name='Jns' value='2' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$dt['nmfile_asli']."</span>".
					"</form>".
					
					'', 
			 ),
			'progress' => array(
				'label'=>'','labelWidth'=>150, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			),*/
			'tgl_update' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='".TglInd($dt['tgl_update'])."' style='width:80px;'readonly />  "
										
								,						
								),				 
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='username' id='username' value='$uid' style='width:250px;'readonly>",
						 ),	
						 
			'expired_date' =>	array(
								'label'=>'EXPIRED DATE',
								'name'=>'expired_date',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='expired_date' id='expired_date' class='datepicker' value='$tgl_dokumen_bast' style='width:80px;' />  "
										
								,						
								),	
						 			 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status_system'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),				
			);
		//tombol
		$this->form_menubawah =
			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	/*function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	$this->form_width = 600;
	 $this->form_height = 220;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Form Pengeluaran - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Form Upload Pengeluaran';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
	$tgl_dokumen_bast = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>150, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='".$dt['nourut']."' style='width:30px;maxlength='3'>",
						 ),
						 
			'kode' => array( 
						'label'=>'KODE',
						'labelWidth'=>100, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='kode' id='kode' maxlength='3' value='".$dt['kode']."' style='width:30px; '>",
						 ),				 		
			
			'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nourut' id='nourut' value='".$dt['nourut']."' style='width:250px;'>",
						 ),	
			
			'kel_user' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status'],$this->kel,'-- PILIH --','style="width:95px;"'),
						 ),	
						 
						 
						 
			
			
			'scan_foto' => array( 
				'label'=>'Upload Arsip','labelWidth'=>150, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processUploadFile' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='ref_id' name='ref_id' value='".$dt['Id']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='Jns' name='Jns' value='2' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$dt['nmfile_asli']."</span>".
					"</form>".
					
					'', 
			 ),
			'progress' => array(
				'label'=>'','labelWidth'=>150, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			),
			'status1' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_dokumen_bast' id='tgl_dokumen_bast' class='datepicker' value='$tgl_dokumen_bast' style='width:80px;' />  "
										
								,						
								),				 
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nourut' id='nourut' value='".$dt['nourut']."' style='width:250px;'>",
						 ),				 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),				
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='jumlahbarangx' id='jumlahbarangx' value='$hitung'>".
			"<input type='hidden' name='c' id='c' value='".$dt['c']."'>".
			"<input type='hidden' name='d' id='d' value='".$dt['d']."'>".
			"<input type='hidden' name='e' id='e' value='".$dt['e']."'>".
			"<input type='hidden' name='e1' id='e1' value='".$dt['e1']."'>".
			"<input type='hidden' name='tahun' id='tahun' value='".$dt['tahun']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}*/
	
	function setPage_HeaderOther(){
	return "";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	$NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	    <th class='th01' width='5' >No.</th>
	   
	   <th class='th01' width='450' align='center'>Nama System</th>
	   <th class='th01' width='450' align='center'>Nama Modul</th>
	   
	   
	    </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 if($isi['status_modul']==1){
	 	$status='AKTIF';
	 }elseif($isi['status_modul']==2){
	 	$status='TIDAK AKTIF';
	 }
	
	 $datmod=mysql_fetch_array(mysql_query("select nm_system from system where Id_system='".$isi['Id_system']."'"));
	 $Koloms = array();
	
	 
	$Id_modul=$isi['Id_modul'];	
	  $Koloms[] = array('align="center"', $no.'.' );
	 $Koloms[] = array('align="left"',$datmod['nm_system']);
	 $Koloms[] = array('align="left" width=""',"<a style='cursor:pointer;' onclick=ManagementModulSystem2.windowSave('$Id_modul')>".$isi['nm_modul']."</a>");
	// $Koloms[] = array('align="left"',$isi['nm_modul']);
	
	  
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 $arr = array(
			//array('selectAll','Semua'),	
			array('selectBarang','Barang'),		
			);
		
	 //data order ------------------------------
	 $arrOrder = array(
			     	array('1','Barang'),
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	$fmBidang = cekPOST('fmBidang'); 
	$fmSKPD = cekPOST('fmSKPD'); 
	//query Bidang
	$queryBidang = "SELECT c,nm_skpd FROM ref_skpd WHERE c!='00' AND d='00' AND e='00' AND e1='000'";
	//query SKPD
	$querySKPD = "SELECT d,nm_skpd FROM ref_skpd WHERE c='$fmBidang' AND d!='00' AND e='00' AND e1='000'";
	//query program
	 //combo box 
	 $BIDANG=cmbQuery('fmBidang',$fmBidang,$queryBidang,'onChange=\''.$this->Prefix.'.fmSKPD()\'','-- PILIH BIDANG --');	 
	 $SKPD=cmbQuery('fmSKPD',$fmSKPD,$querySKPD,'','-- PILIH SKPD --');	
	
	/*$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			
			$vOrder=
		genFilterBar(
				array(							
					//'Urutkan : '.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<table>
						<tr>
							<td width='75'>BIDANG</td>
							<td width='25'> : </td>
							<td><div id='div_fmbidang'>$BIDANG</div></td>
						</tr>
						<tr>
							<td>SKPD</td>
							<td> : </td>
							<td><div id='div_fmskpd'>$SKPD</div></td>
						</tr>
					</table>"
					),				
				$this->Prefix.".refreshList(true)",FALSE).
		genFilterBar(
				array(							
					//'Urutkan : '.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>"
					),				
				$this->Prefix.".refreshList(true)",FALSE);		
			//"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			*/
		return array('TampilOpt'=>$TampilOpt);
	}				
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$arrKondisi[]="status_modul<>'2'";
		//$fmPILCARI = $_REQUEST['fmPILCARI'];	
		//$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmBidang = $_REQUEST['fmBidang']; 
		$fmSKPD = $_REQUEST['fmSKPD']; 
		if(!($fmBidang=='' || $fmBidang=='00') ) $arrKondisi[] = "c='$fmBidang'";
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "d='$fmSKPD'";		
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
	
	
	function setMenuView(){
		
			}
			
	function batal(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
		
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	  
	 $aq = "SELECT * FROM gambar WHERE ref_id = '$idplh' AND Jns='2' AND stat2='1'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("media/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar WHERE ref_id = '$idplh' AND Jns='2' AND stat2 = '1'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		$upd = "UPDATE gambar SET stat = '0' WHERE ref_id = '$idplh' AND Jns='2' AND stat2 = '0'";$cek .= ' ||'. $upd;
		$qryupd = mysql_query($upd);
		
	
					
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    
	}
}
$ManagementModulSystem2 = new ManagementModulSystem2Obj();
?>