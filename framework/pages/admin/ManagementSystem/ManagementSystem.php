<?php
class ManagementSystemObj  extends DaftarObj2{	
	var $Prefix = 'ManagementSystem';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'system'; //bonus
	var $TblName_Hapus = 'system';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id_system');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Management System';
	var $PageIcon = 'images/masterdata_ico.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='refmigrasi.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'ManagementSystem';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ManagementSystemForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	var $kel = array(
				array('1','ADMIN'), 
				array('2','USER'), 
				array('3','CUSTOMER'), 
				array('4','PUBLIK'), 
				
		);
		
	var $Status = array(
				array('1','AKTIF'), 
				array('2','TIDAK AKTIF'), 
		);	
		
	function setTitle(){
		return 'Management System';
	}
	
	function setMenuEdit(){
		return
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Upload()","upload_f2.png","Upload", 'Upload')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
		;
	}
	
	function setMenuView(){
		
			}
	
	function simpanUpload(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $cek = '';			
	 $err = '';			
	 $content = '';	
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 
	 
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		
		
	//	$aq = "SELECT * FROM gambar_upload WHERE sys_id = '$idplh' AND stat = '2'";$cek .=$aq;
		
		$aq = "SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='1'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/system/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='1'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		
		$upd = "UPDATE gambar_upload SET stat = '0' ,stat2 = '0' , tgl_create = NOW() WHERE jns_upload='1' and id_upload = '$idplh'";$cek .= ' ||'. $upd;
		
		$qryupd = mysql_query($upd);
		
		$temp1=mysql_fetch_array(mysql_query("select Id from gambar_upload where id_upload = '$idplh' and stat='0' and jns_upload='1'"));
		
		$upd3 = "UPDATE system SET nm_fileimages ='".$temp1['Id']."'  WHERE Id_system = '$idplh'";
		$qryupd3 = mysql_query($upd3);	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
	 		"select count(*) as cnt from system where kode='$kode'"
		));
		
		$oldy3=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from system where kode='$idplh'"
		));
		
		$cek.="select count(*) as cnt from system where kode='".$dt['kode']."'";
	/* if($err=='' && $oldy['cnt']>0) $err="No Urut '$nourut' Sudah Ada";
	 if($err=='' && $oldy2['cnt']>0) $err="Kode System '$kode' Sudah Ada";*/
	 if( $err=='' && $nourut =='' ) $err= 'No Urut Belum Di Isi !!';
	 if( $err=='' && $kode =='' ) $err= 'Kode System Belum Di Isi !!';
	 if( $err=='' && $nm_system =='' ) $err= 'Nama System Belum Di Isi !!';
	 if( $err=='' && $kel_user =='' ) $err= 'Kelompok User Belum Di Pilih !!';
	
	 if( $err=='' && $username =='' ) $err= 'User Name Belum Di Isi !!';
	 if( $err=='' && $status =='' ) $err= 'Status Belum Di Pilih !!';
		
			if($fmST == 0){
			
			if($err=='' && $oldy['cnt']>0) $err="No Urut '$nourut' Sudah Ada";
	 		if($err=='' && $oldy2['cnt']>0) $err="Kode System '$kode' Sudah Ada";
				if($err==''){
					$aqry = "INSERT into system (no_urut,kode,nm_system,kel_user_system,status_system,tgl_update,uid,expired_date) values('$nourut','$kode','$nm_system','$kel_user','$status','$tgl_update2','$uid','$expired_date2')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{
						if($err==''){
						if($err=='' && $oldy3['cnt']>0) $err="Kode System '$kode' Sudah Ada";
						$aqry = "UPDATE system set no_urut='$nourut',kode='$kode',nm_system='$nm_system',kel_user_system='$kel_user',status_system='$status',tgl_update='$tgl_update2',expired_date='$expired_date2' where Id_system='".$idplh."'";	$cek .= $aqry;
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
		case 'hapus':{
			$get= $this->Hapus();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
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
		
		case 'simpanUpload':{
			$get= $this->simpanUpload();
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
 
	function Hapus($ids){ //validasi hapus tbl_sppd
		 $err=''; $cek='';
		 $cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		
		if ($err ==''){
			
		for($i = 0; $i<count($ids); $i++){
		$idplh1 = explode(" ",$ids[$i]);
		
	
$hpssys=mysql_fetch_array(mysql_query("select COUNT(*) as cnt,system.Id_system, system_modul.Id_system from system
left join system_modul on system_modul.Id_system=system.Id_system where system_modul.Id_system='".$ids[$i]."'"));

$hpsmnu=mysql_fetch_array(mysql_query("select COUNT(*) as cnt,system.Id_system, system_menu.Id_system from system
left join system_menu on system_menu.Id_system=system.Id_system where system_menu.Id_system='".$ids[$i]."'"));
	
	
	if($hpssys['cnt'] > 0 )$err ="Data tidak bisa di Hapus karena sudah ada di data MODUL SYSTEM  !!";
	if($hpsmnu['cnt'] > 0 )$err ="Data tidak bisa di Hapus karena sudah ada di data MENU SYSTEM  !!";
	
	if($hpssys['cnt'] > 0  && $hpsmnu['cnt'] > 0 )$err ="Data tidak bisa di Hapus karena sudah ada di data MODUL SYSTEM  dan MENU SYSTEM !!";
		if($err=='' ){
					
					$aq1 = "SELECT * FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='1'";$cek .=$aq;
					$qry1 = mysql_query($aq1);
					while($del2 = mysql_fetch_array($qry1)){
					unlink("Media/system/".$del2['nmfile']);
						}
					$hapus1 = "DELETE FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='1'"; $cek .= ' || '.$hapus;
					$hps1 = mysql_query($hapus1);
					
					$qy = "DELETE FROM system WHERE Id_system='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					
			}else{
				break;
			}			
		}
		}
		return array('err'=>$err,'cek'=>$cek);
	}	  
	
	function setPage_OtherScript(){
		$scriptload = 
		"<script>
		$(document).ready(function()
		{
			".$this->Prefix.".loading();
			setTimeout(function myFunction()
			{
				".$this->Prefix.".AftFilterRender()},1000);
		}
		);
		</script>";
		return 	
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			
			
			"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<link rel='stylesheet' href='css/upload_style.css' type='text/css'>".
			"<script src='js/jquery.js' type='text/javascript'></script>".			
			"<script src='js/jquery-ui.js' type='text/javascript'></script>".
			"<script src='js/jquery.min.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/jquery.form.js'></script> ".
			"<script src='js/jquery-ui.custom.js'></script>".
			
			"<script type='text/javascript' src='js/admin/ManagementSystem/ManagementSystem.js' language='JavaScript' ></script>".
			
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
   
   function setFormUpload(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  system WHERE Id_system='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$file = "SELECT * FROM gambar_upload WHERE id_upload='$this->form_idplh'LIMIT 0,1";$cek=$qry;
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
		
		$fm = $this->setUpload($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  system WHERE Id_system='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setFormEditdata($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
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
	 $this->form_height = 250;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management System - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management System - Edit';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
		$queryKF="SELECT max(no_urut)as nourut FROM system" ;
	//	$cek.="SELECT max(no_urut)as nourut FROM system";
		$get=mysql_fetch_array(mysql_query($queryKF));
		$no_urut=$get['nourut'] + 1;
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		$datauser=1;
		$dataaktif=1;
	$tgl_dokumen_bast = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'no_urut' => array( 
						'label'=>'NO.URUT',
						'labelWidth'=>150, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='$no_urut' style='width:30px;maxlength='3'>",
						 ),
						 
			'kode' => array( 
						'label'=>'KODE SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='kode' id='kode' maxlength='3' value='".$dt['kode']."' style='width:30px; '> ' 3 Digit (001)'",
						 ),				 		
			
			'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nm_system' id='nm_system' value='".$dt['nm_system']."' style='width:250px;'>",
						 ),	
			
			'kel_user' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>cmbArray('kel_user',$datauser,$this->kel,'-- PILIH --','style="width:130px;"'),
						 ),	
						 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dataaktif,$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),	
						 						 
			'expired_date' =>array(
							'label'=>'EXPIRED DATE',
							'name'=>'expired_date',
							'label-width'=>'200px;',
							'value'=>"<input type='text' name='expired_date' id='expired_date' class='datepicker' value='' style='width:80px;' />",						
								),
												 
			'tgl_update' =>	array(
							'label'=>'TANGGAL UPDATE',
							'name'=>'dokumensumber',
							'label-width'=>'200px;',
							'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='$tgl_dokumen_bast' style='width:80px;'readonly />",						
								),				 
						 			 
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='username' id='username' value='$uid' style='width:250px;'readonly>",
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
	 $this->form_height = 250;
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
		//$tgl_dokumen_bast=$dt['expired_date'];
		$tgl_dokumen_bast=TglInd($dt['expired_date']);
		$nmupload=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`nmfile_asli` FROM `gambar_upload` LEFT JOIN `system` ON `system`.`Id_system` = `gambar_upload`.`id_upload` where Id_system='".$dt['Id_system']."' and jns_upload='1'"));
		
	//$tgl_dokumen_bast = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>150, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='".$dt['no_urut']."' style='width:30px;maxlength='3' readonly>",
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
			'scan_foto' => array( 
						'label'=>'FILE IMAGES',
						'labelWidth'=>100,
						
						'value'=>$nmupload['nmfile_asli']
						
						),			 
			
			 
			'progress' => array(
				'label'=>'','labelWidth'=>150, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			),
					 
			
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status_system'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),	
						 
			'tgl_update' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='".TglInd($dt['tgl_update'])."' style='width:80px;'readonly />  "
										
								,						
								),					 
						 	
			'expired_date' =>	array(
								'label'=>'EXPIRED DATE',
								'name'=>'expired_date',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='expired_date' id='expired_date' class='datepicker' value='$tgl_dokumen_bast' style='width:80px;' />  "
										
								,						
								),
									
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='username' id='username' value='$uid' style='width:250px;'readonly>",
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
	
	
	function setUpload($dt){	
	 global $SensusTmp;
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $cek = ''; $err=''; $content=''; 	
	// $uid = $HTTP_COOKIE_VARS['coID'];			
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 600;
	 $this->form_height = 200;
	  if ($this->form_fmST==0) {
		
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management System - Upload';			
	//	$Id = $dt['id'];		
	  }
	    //ambil data trefditeruskan
	  	
		$tgl_expired=TglInd($dt['expired_date']);
		$tgl_update=TglInd($dt['tgl_update']);
		
	 if($dt['kel_user_system']==1){
	 	$kel='ADMIN';
	 }elseif($dt['kel_user_system']==2){
	 	$kel='USER';
	 }elseif($dt['kel_user_system']==3){
	 	$kel='CUSTOMER';
	 }elseif($dt['kel_user_system']==4){
	 	$kel='PUBLIK';
	 }
	 
	 if($dt['status_system']==1){
	 	$status='AKTIF';
	 }elseif($dt['status_system']==2){
	 	$status='TIDAK AKTIF';
	 }
	 //items ----------------------
	  $this->form_fields = array(
	  		
			'no_urut' => array( 
						'label'=>'NO.URUT',
						'labelWidth'=>150, 
						'value'=>$dt['no_urut'],
						 ),			 
			'kode' => array( 
						'label'=>'KODE SYSTEM',
						'labelWidth'=>100, 
						'value'=>$dt['kode'],
						 ),				 		
			
			'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>$dt['nm_system'],
						 ),	
			
			'kel_user' => array( 
						'label'=>'KELOMPOK USER',
						'labelWidth'=>100,
						'value'=>$kel,
						 ),	
						 			 
			'scan_foto' => array( 
				'label'=>'FILE IMAGES','labelWidth'=>100, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processUploadFile' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='sys_id' name='sys_id' value='".$dt['Id_system']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='jns_upload' name='jns_upload' value='1' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$dt['nmfile_asli']."</span>".
					"</form>".
					'', 
			 ),
			 
			'progress' => array(
				'label'=>'','labelWidth'=>100, 'pemisah'=>' ',
				'value'=>				
					"<div id='progressbox'><div id='progressbar'></div >
					<div id='statustxt'>0%</div >
					<div id='output'></div>	"
			),
			
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>$status,
						 ),
						 							 
			'expired_date' =>array(
							'label'=>'EXPIRED DATE',
							'name'=>'expired_date',
							'label-width'=>170,
							'value'=>$tgl_expired,						
								),
									 
			'tgl_update' =>	array(
							'label'=>'TANGGAL UPDATE',
							'name'=>'tgl_update',
							'label-width'=>170,
							'value'=>$tgl_update,						
							),				 
								 			 
			'username' => array( 
						'label'=>'USER NAME',
						'labelWidth'=>100, 
						'value'=>$dt['uid'],
						 ),	
			);
						
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanUpload()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";				
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
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
  	   $Checkbox		
	   <th class='th01' width='80'>No Urut</th> 
	   <th class='th01' width='80'>Kode</th>
   	   <th class='th01' width='300'>Nama System</th>
   	   <th class='th01' width='200'>Kelompok System</th>
   	   <th class='th01' width='120'>Status</th>
   	   <th class='th01' width='200'>Nama File Images</th>
   	   <th class='th01' width='200'>Alamat Direktory</th>
	   <th class='th01' width='150'>Expired Date</th>
   	   <th class='th01' width='150'>Tgl Update</th>
	   <th class='th01' width='200'>User Name</th> 
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;

	
	 if($isi['kel_user_system']==1){
	 	$kel='ADMIN';
	 }elseif($isi['kel_user_system']==2){
	 	$kel='USER';
	 }elseif($isi['kel_user_system']==3){
	 	$kel='CUSTOMER';
	 }elseif($isi['kel_user_system']==4){
	 	$kel='PUBLIK';
	 }
	 
	 if($isi['status_system']==1){
	 	$status='AKTIF';
	 }elseif($isi['status_system']==2){
	 	$status='TIDAK AKTIF';
	 }
	
	$img=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`direktori`,`gambar_upload`.`nmfile_asli`, `gambar_upload`.`nmfile`, `gambar_upload`.`stat` FROM `system` RIGHT JOIN `gambar_upload` ON `system`.`Id_system` = `gambar_upload`.`id_upload` WHERE `gambar_upload`.`stat` = 0 and Id_system='".$isi['Id_system']."' and `gambar_upload`.`jns_upload` ='1'"));
	
	 if($img != ''){
	 	$file = "<a download='".$img['nmfile_asli']."' href='Media/system/".$img['nmfile']."' title='".$img['nmfile_asli']."'><img width='23px' height='23px' src='images/administrator/images/download_f2.png' /> </a>";
	 }else{
	 	$file='';
	 }
	 
	//$direk=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`nmfile_asli`, `gambar_upload`.`nmfile`, `gambar_upload`.`stat` FROM `system` RIGHT JOIN `gambar_upload` ON `system`.`Id_system` = `gambar_upload`.`sys_id` WHERE `gambar_upload`.`stat` = 0 and Id_system='".$isi['Id_system']."'"));
		
 	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="right"',$isi['no_urut']);
	 $Koloms[] = array('align="right"',$isi['kode']);
	 $Koloms[] = array('align="left"',$isi['nm_system']);
	 $Koloms[] = array('align="left"',$kel);
	 $Koloms[] = array('align="left"',$status);
	 $Koloms[] = array('align="center" width="60px"',$file);
	 $Koloms[] = array('align="left"',$img['direktori'].''.$img['nmfile']);
	 $Koloms[] = array('align="left"',TglInd($isi['expired_date']));
	 $Koloms[] = array('align="left"',TglInd($isi['tgl_update']));
	 $Koloms[] = array('align="left"',$isi['uid']);
	 return $Koloms;
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$TampilOpt = $this->genDaftarOpsi();
		
		if($HTTP_COOKIE_VARS['coSKPD']=='00'){
			$skpd=$HTTP_COOKIE_VARS['cofmSKPD'];
			$unit=$HTTP_COOKIE_VARS['cofmUNIT'];
			$subunit=$HTTP_COOKIE_VARS['cofmSUBUNIT'];
		}
		else{
			$skpd=$HTTP_COOKIE_VARS['coSKPD'];
			$unit=$HTTP_COOKIE_VARS['coUNIT'];
			$subunit=$HTTP_COOKIE_VARS['coSUBUNIT'];
		}
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_skpd' style='position:relative'></div>".
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
			//$vOpsi['TampilOpt'].
			"<input type='hidden' id='thn' name='thn' value='".date('Y')."'>".
			"<input type='hidden' id='bln' name='bln' value='".date('m')."'>".
			"<input type='hidden' id='skpd_penerimaanfmBidang' name='skpd_penerimaanfmBidang' value='".$skpd."'>".
			"<input type='hidden' id='skpd_penerimaanfmBagian' name='skpd_penerimaanfmBagian' value='".$unit."'>".
			"<input type='hidden' id='skpd_penerimaanfmSubBagian' name='skpd_penerimaanfmSubBagian' value='".$subunit."'>".
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				//"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
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
	//$queryBidang = "SELECT c,nm_skpd FROM ref_skpd WHERE c!='00' AND d='00' AND e='00' AND e1='000'";
	//query SKPD
	//$querySKPD = "SELECT d,nm_skpd FROM ref_skpd WHERE c='$fmBidang' AND d!='00' AND e='00' AND e1='000'";
	//query program
	 //combo box 
	 //$BIDANG=cmbQuery('fmBidang',$fmBidang,$queryBidang,'onChange=\''.$this->Prefix.'.fmSKPD()\'','-- PILIH BIDANG --');	 
	 //$SKPD=cmbQuery('fmSKPD',$fmSKPD,$querySKPD,'','-- PILIH SKPD --');	
	
	
		return array('TampilOpt'=>$TampilOpt);
	}				
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
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
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
		}*/	
		$arrOrders[] = " no_urut asc ";
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
	
	function batal(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
		
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	  
	 	$aq = "SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='1'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/system/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='1'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		$upd = "UPDATE gambar_upload SET stat = '0' WHERE id_upload = '$idplh' AND stat2 = '0' and jns_upload='1'";$cek .= ' ||'. $upd;
		$qryupd = mysql_query($upd);
		
	
					
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    
	}
	
}
$ManagementSystem = new ManagementSystemObj();
?>