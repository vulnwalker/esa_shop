<?php
class ManagementPenggunaObj  extends DaftarObj2{	
	var $Prefix = 'ManagementPengguna';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'managemen_pengguna'; //bonus
	var $TblName_Hapus = 'managemen_pengguna';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id_pengguna');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Management Pengguna';
	var $PageIcon = 'images/masterdata_ico.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='refmigrasi.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'ManagementPengguna';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ManagementPenggunaForm';
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
		return 'Management Pengguna';
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
		$temp1=mysql_fetch_array(mysql_query("select Id from gambar_upload where id_upload = '$idplh' and stat='0'"));
		$temp2=mysql_fetch_array(mysql_query("select Id from gambar_upload where id_upload = '$idplh' and stat='0'"));
		
		$aq = "SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='6'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/pengguna/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='6'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		
		$aq1 = "SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='7'";$cek .=$aq;
		$qry1 = mysql_query($aq1);
		while($del2 = mysql_fetch_array($qry1)){
			unlink("Media/pengguna/".$del2['nmfile']);
		}
		$hapus1 = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat = '2' and jns_upload='7'"; $cek .= ' || '.$hapus;
		$hps1 = mysql_query($hapus1);
		
		$upd = "UPDATE gambar_upload SET stat = '0' , stat2 = '0' , tgl_create = NOW() WHERE jns_upload='6' and id_upload = '$idplh'";//$cek .= ' ||'. $upd;
		$qryupd = mysql_query($upd);
		$upd2 ="UPDATE gambar_upload SET stat = '0' , stat2 = '0' , tgl_create = NOW() WHERE jns_upload='7' and id_upload = '$idplh'";//$cek .= ' ||'. $upd;
		$qryupd2 = mysql_query($upd2);
		$temp1=mysql_fetch_array(mysql_query("select Id from gambar_upload where id_upload = '$idplh' and stat='0' and jns_upload='6'"));
		$temp2=mysql_fetch_array(mysql_query("select Id from gambar_upload where id_upload = '$idplh' and stat='0' and jns_upload='7'"));
		$cek.="select Id from gambar_upload where id_upload = '$idplh' and stat='0' and jns_upload='7'";
		$upd3 = "UPDATE managemen_pengguna SET file_imagesbw ='".$temp1['Id']."',file_imagescolor ='".$temp2['Id']."'  WHERE Id_pengguna = '$idplh'";
		$qryupd3 = mysql_query($upd3);
		$cek.="UPDATE managemen_pengguna SET file_imagesbw ='".$temp1['Id']."',file_imagescolor ='".$temp2['Id']."'  WHERE Id_pengguna = '$idplh'";
		
		
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
	 
	 $a= $_REQUEST['a']; 
	 $b= $_REQUEST['b'];
	 $nm_pengguna= $_REQUEST['nm_pengguna'];					
	 $singkatan= $_REQUEST['singkatan'];
	 $tgl_update= $_REQUEST['tgl_update'];			 
	 $username= $_REQUEST['username'];
	 $lokasi= $_REQUEST['lokasi'];
	 $status= $_REQUEST['status'];
	 
	 $tgl_update = explode("-",$tgl_update);
	 $tgl_update2 = $tgl_update[2].'-'.$tgl_update[1].'-'.$tgl_update[0];
	 
	 
	  $oldy=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from managemen_pengguna where b='$b'"
		));
		$oldy3=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from managemen_pengguna where Id_pengguna='$idplh'"
		));
	 
	
		
			if($fmST == 0){
			if( $err=='' && $a =='' ) $err= 'Kode Pertama Belum Di Isi !!';
	 		if( $err=='' && $b =='' ) $err= 'Kode Kedua Belum Di Isi !!';
			if( $err=='' && $nm_pengguna =='' ) $err= 'Nama Pengguna Belum Di Isi !!';
	 		if( $err=='' && $singkatan =='' ) $err= 'Nama Singkatan Belum Di Pilih !!';
			if( $err=='' && $lokasi =='' ) $err= 'Lokasi Belum Di Isi !!';
			
			if($err=='' && $oldy['cnt']>0) $err="KODE '$a' '$b' Sudah Ada";
	 	
				if($err==''){
					$aqry = "INSERT into managemen_pengguna (a,b,nm_pengguna,nm_singkatan,lokasi,status,tgl_update,uid) values('$a','$b','$nm_pengguna','$singkatan','$lokasi','$status',NOW(),'$uid')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{
						if($err==''){
						if( $err=='' && $nm_pengguna =='' ) $err= 'Nama Pengguna Belum Di Isi !!';
	 					if( $err=='' && $singkatan =='' ) $err= 'Nama Singkatan Belum Di Pilih !!';
						if( $err=='' && $lokasi =='' ) $err= 'Lokasi Belum Di Isi !!';
					//	if($err=='' && $oldy3['cnt']>0) $err="Kode System '$a' '$b'  Sudah Ada";
						$aqry = "UPDATE managemen_pengguna set a='$a',b='$b',nm_pengguna='$nm_pengguna',nm_singkatan='$singkatan',lokasi='$lokasi',status='$status',tgl_update=NOW() where Id_pengguna='".$idplh."'";	$cek .= $aqry;
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
			
		if($err=='' ){
		
		$aq = "SELECT * FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='6'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/pengguna/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='6'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		
		$aq1 = "SELECT * FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='7'";$cek .=$aq;
		$qry1 = mysql_query($aq1);
		while($del2 = mysql_fetch_array($qry1)){
			unlink("Media/pengguna/".$del2['nmfile']);
		}
		$hapus1 = "DELETE FROM gambar_upload WHERE id_upload = '".$ids[$i]."' and jns_upload='7'"; $cek .= ' || '.$hapus;
		$hps1 = mysql_query($hapus1);
					
					$qy = "DELETE FROM managemen_pengguna WHERE Id_pengguna='".$ids[$i]."' ";$cek.=$qy;
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
			
			"<script type='text/javascript' src='js/admin/ManagementPengguna/ManagementPengguna.js' language='JavaScript' ></script>".
			
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
		$aqry = "SELECT * FROM  managemen_pengguna WHERE Id_pengguna='".$this->form_idplh."' "; $cek.=$aqry;
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
		$aqry = "SELECT * FROM  managemen_pengguna WHERE Id_pengguna='".$this->form_idplh."' "; $cek.=$aqry;
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
	$this->form_width = 500;
	 $this->form_height = 150;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management Pengguna - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management Pengguna - Edit';			
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
	$tgl_update = date('d-m-Y');		
	 //items ----------------------
	  $this->form_fields = array(
	  		'kode' => array( 
						'label'=>'KODE',
						'labelWidth'=>120, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='a' id='a' maxlength='2' value='' style='width:30px;maxlength='2'>
						<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='b' id='b' maxlength='2' value='' style='width:30px;maxlength='2'> *01 02",
						 ),
						 
			'nm_pengguna' => array( 
						'label'=>'NAMA PENGGUNA',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='nm_pengguna' id='nm_pengguna' value='".$dt['nm_pengguna']."' style='width:250px;'>",
						 ),				 		
			
			'singkatan' => array( 
						'label'=>'SINGKATAN PENGGUNA',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='singkatan' id='singkatan' value='".$dt['singkatan']."' style='width:250px;'>",
						 ),	
						 
			'lokasi' => array( 
						'label'=>'LOKASI',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='lokasi' id='lokasi' value='".$dt['lokasi']."' style='width:250px;'>",
						 ),				 
			
			
						 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dataaktif,$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),	
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='tgl_update' id='tgl_update' class='' value='' style='width:80px;'readonly />".
			"<input type='hidden' name='username' id='username' class='' value='$uid' style='width:80px;'readonly />".
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
					
	$this->form_width = 450;
	 $this->form_height = 190;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management Pengguna - Baru';
		//$nip	 = '';
	  }else{
		$this->form_caption = 'Management Pengguna - Edit';			
		$Id = $dt['id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	
	$kode=mysql_fetch_array(mysql_query("select a,b from managemen_pengguna where a='".$dt['a']."' and b='".$dt['b']."'"));	
	$kode2=$kode['a'].'.'.$kode['b'];
	
	$nmupload1=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`nmfile_asli` FROM `gambar_upload` LEFT JOIN `managemen_pengguna` ON `managemen_pengguna`.`Id_pengguna` = `gambar_upload`.`id_upload` where Id_pengguna='".$dt['Id_pengguna']."' and jns_upload='6'"));
	$nmupload2=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`nmfile_asli` FROM `gambar_upload` LEFT JOIN `managemen_pengguna` ON `managemen_pengguna`.`Id_pengguna` = `gambar_upload`.`id_upload` where Id_pengguna='".$dt['Id_pengguna']."' and jns_upload='7'"));
			
	 //items ----------------------
	  $this->form_fields = array(
	  		'kode' => array( 
						'label'=>'KODE',
						'labelWidth'=>120, 
						//'value'=>"<input type='text' name='a' id='a' maxlength='2' value='$kode2' style='width:40px;maxlength='2' readonly>
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='a' id='a' maxlength='2' value='".$dt['a']."' style='width:30px;maxlength='2'>
						<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='b' id='b' maxlength='2' value='".$dt['b']."' style='width:30px;maxlength='2'> 
						",
						 ),
						 
			'nm_pengguna' => array( 
						'label'=>'NAMA PENGGUNA',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='nm_pengguna' id='nm_pengguna' value='".$dt['nm_pengguna']."' style='width:250px;'>",
						 ),				 		
			
			'singkatan' => array( 
						'label'=>'SINGKATAN PENGGUNA',
						'labelWidth'=>120, 
						'value'=>"<input type='text' name='singkatan' id='singkatan' value='".$dt['nm_singkatan']."' style='width:250px;'>",
						 ),	
						 
			'lokasi' => array( 
						'label'=>'LOKASI',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='lokasi' id='lokasi' value='".$dt['lokasi']."' style='width:250px;'>",
						 ),				 
			
			'scan_foto1' => array( 
						'label'=>'FILE IMAGES BW',
						'labelWidth'=>100,
						
						'value'=>$nmupload1['nmfile_asli']
						
						),		
						
			'scan_foto2' => array( 
						'label'=>'FILE IMAGES COLOR',
						'labelWidth'=>100,
						
						'value'=>$nmupload2['nmfile_asli']
						
						),					
						 
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status'],$this->Status,'-- PILIH --','style="width:95px;"'),
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
		$this->form_caption = 'Management Pengguna - Upload';			
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
	 
	 if($dt['status']==1){
	 	$status='AKTIF';
	 }elseif($dt['status']==2){
	 	$status='TIDAK AKTIF';
	 }
	$kode=mysql_fetch_array(mysql_query("select a,b from managemen_pengguna where a='".$dt['a']."' and b='".$dt['b']."'"));	
	$kode2=$kode['a'].'.'.$kode['b'];
	 
	 //items ----------------------
	  $this->form_fields = array(
	  		
			'kode' => array( 
						'label'=>'KODE',
						'labelWidth'=>150, 
						'value'=>$kode2,
						 ),			 
			'pengguna' => array( 
						'label'=>'NAMA PENGGUNA',
						'labelWidth'=>100, 
						'value'=>$dt['nm_pengguna'],
						 ),				 		
			
			'singkat' => array( 
						'label'=>'NAMA SINGKATAN',
						'labelWidth'=>100, 
						'value'=>$dt['nm_singkatan'],
						 ),	
			
			'lokasi' => array( 
						'label'=>'LOKASI',
						'labelWidth'=>100,
						'value'=>$dt['lokasi'],
						 ),	
						 			 
			'BW' => array( 
				'label'=>'FILE IMAGES BW','labelWidth'=>100, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processuploadfilepengguna1' method='post' enctype='multipart/form-data' id='UploadForm'>".
					"<input type='hidden' id='peng_id' name='peng_id' value='".$dt['Id_pengguna']."' >".
					"<input id='ImageFile' name='ImageFile' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					 <input type='hidden' id='ref_idupload' name='ref_idupload' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					 <input type='hidden' id='jns_upload' name='jns_upload' value='6' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile' style='margin-left:6px;'>".$datakt['nmfile_asli']."</span>".
					"</form>".
					'', 
			 ),
			 
			 'COLOR' => array( 
				'label'=>'FILE IMAGES COLOR','labelWidth'=>100, 
				'value'=>						
					"<form></form>".					
					"<form action='pages.php?Pg=processuploadfilepengguna2' method='post' enctype='multipart/form-data' id='UploadForm2'>".
					"<input type='hidden' id='peng_id' name='peng_id' value='".$dt['Id_pengguna']."' >".
					"<input id='ImageFile2' name='ImageFile2' type='file'  style='visibility:hidden;width:0px;height:0px' onchange=\"".$this->Prefix.".btfile2_onchange()\" />".
					"<input type='button' onclick=\"$('#ImageFile2').click();\" value='Pilih File'>
					 <input type='hidden' id='isifile' name='isifile' value='".$dt['isifile']."' >
					 <input type='hidden' id='ref_idupload1' name='ref_idupload1' value='".$dt['id']."' >
					 <input type='hidden' id='idfile' name='idfile' value='".$dt['idfile']."' >
					<input type='hidden' id='jns_upload' name='jns_upload' value='7' >
					 <input type='hidden' id='nmfile' name='nmfile' value='".$dt['nmfile']."' >".
					"<input type='hidden' id='nmfile_asli' name='nmfile_asli' value='".$dt['nmfile_asli']."' >".
					"<span id='content_newfile2' style='margin-left:6px;'>".$datpsf['nmfile_asli']."</span>".
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
	   <th class='th01' width='80'>Kode</th>
   	   <th class='th01' width='300'>Nama Pengguna</th>
   	   <th class='th01' width='200'>Singkatan</th>
   	   <th class='th01' width='120'>Lokasi</th>
   	   <th class='th01' width='100'>Logo Images BW</th>
   	   <th class='th01' width='100'>Logo Images Color</th>
   	   <th class='th01' width='80'>Status</th>
   	   <th class='th01' width='100'>Tanggal Create</th>
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
	 
	 if($isi['status']==1){
	 	$status='AKTIF';
	 }elseif($isi['status']==2){
	 	$status='TIDAK AKTIF';
	 }
	
	$img=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`direktori`,`gambar_upload`.`nmfile_asli`, `gambar_upload`.`nmfile`, `gambar_upload`.`stat` FROM `managemen_pengguna` RIGHT JOIN `gambar_upload` ON `managemen_pengguna`.`Id_pengguna` = `gambar_upload`.`id_upload` WHERE `gambar_upload`.`stat` = 0 and Id_pengguna='".$isi['Id_pengguna']."' and `gambar_upload`.`jns_upload` ='6'"));
	
	 if($img != ''){
	 	$file = "<a download='".$img['nmfile_asli']."' href='Media/pengguna/".$img['nmfile']."' title='".$img['nmfile_asli']."'><img width='23px' height='23px' src='images/administrator/images/download_f2.png' /> </a>";
	 }else{
	 	$file='';
	 }
	 
	  $img2=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`jns_upload`,`gambar_upload`.`nmfile_asli`, `gambar_upload`.`nmfile`, `gambar_upload`.`stat` FROM `managemen_pengguna` RIGHT JOIN `gambar_upload` ON `managemen_pengguna`.`Id_pengguna` = `gambar_upload`.`id_upload` WHERE `gambar_upload`.`stat` = 0 and `gambar_upload`.`jns_upload` ='7' and Id_pengguna='".$isi['Id_pengguna']."'"));
	
	 if($img2 != ''){
	 	$file2 = "<a download='".$img2['nmfile_asli']."' href='Media/pengguna/".$img2['nmfile']."' title='".$img2['nmfile_asli']."'><img width='23px' height='23px' src='images/administrator/images/download_f2.png' /> </a>";
	 }else{
	 	$file2='';
	 }
	 
	//$direk=mysql_fetch_array(mysql_query("SELECT `gambar_upload`.`nmfile_asli`, `gambar_upload`.`nmfile`, `gambar_upload`.`stat` FROM `system` RIGHT JOIN `gambar_upload` ON `system`.`Id_system` = `gambar_upload`.`sys_id` WHERE `gambar_upload`.`stat` = 0 and Id_system='".$isi['Id_system']."'"));
		
 	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['a'].'.'.$isi['b']);
	 $Koloms[] = array('align="left"',$isi['nm_pengguna']);
	 $Koloms[] = array('align="left"',$isi['nm_singkatan']);
	 $Koloms[] = array('align="left"',$isi['lokasi']);
	 $Koloms[] = array('align="center" width="60px"',$file);
	$Koloms[] = array('align="center" width="60px"',$file2);
	 $Koloms[] = array('align="left"',$status);
	 $Koloms[] = array('align="left"',TglInd($isi['tgl_update']));
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
		$arrOrders[] = " Id_pengguna asc ";
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
	  
	 	$aq = "SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='6'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/pengguna/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='6'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		$upd ="UPDATE gambar_upload SET stat = '0' WHERE id_upload = '$idplh' AND stat2 = '0' and jns_upload='6'";$cek .= ' ||'. $upd;
		$qryupd = mysql_query($upd);
		
		//------------------color----------------------------------
		$aq ="SELECT * FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='7'";$cek .=$aq;
		$qry = mysql_query($aq);
		while($del = mysql_fetch_array($qry)){
			unlink("Media/pengguna/".$del['nmfile']);
		}
		$hapus = "DELETE FROM gambar_upload WHERE id_upload = '$idplh' AND stat2='1' and jns_upload='7'"; $cek .= ' || '.$hapus;
		$hps = mysql_query($hapus);
		
		$upd2 = "UPDATE gambar_upload SET stat = '0' WHERE id_upload = '$idplh' AND stat2 = '0' and jns_upload='7'";$cek .= ' ||'. $upd2;
		$qryupd = mysql_query($upd2);
	
					
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    
	}
	
}
$ManagementPengguna = new ManagementPenggunaObj();
?>