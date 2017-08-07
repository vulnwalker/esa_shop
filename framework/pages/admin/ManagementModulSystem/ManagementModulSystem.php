<?php

class ManagementModulSystemObj  extends DaftarObj2{	
	var $Prefix = 'ManagementModulSystem';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'system_modul'; //daftar 
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
	var $PageIcon = 'images/masterdata_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'Management Modul System';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ManagementModulSystemForm'; 
	var $kdbrg = '';	
	
	var $Status = array(
				array('1','AKTIF'), 
				array('2','TIDAK AKTIF'), 
		);	
			
	function setTitle(){
		return 'Management Modul System';
	}
	
	function setMenuView(){
		return "";
	}
	
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			
	}	
	
	
	function simpanEdit(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	
	$c1= $_REQUEST['c1'];
	$c= $_REQUEST['c'];
	$d= $_REQUEST['d'];
	$e= $_REQUEST['e'];
	$e1= $_REQUEST['e1'];
	$r1= $_REQUEST['r1'];
	$r2= $_REQUEST['r2'];
	$nm_r1= $_REQUEST['nm_r1'];
	$nm_r2= $_REQUEST['nm_r2'];
	$nama= $_REQUEST['nama'];
	$nip= $_REQUEST['nip'];

						
	if($err==''){						
		
	$aqry = "UPDATE ref_ruang2 set c1='$c1',c='$c',d='$d',e='$e',e1='$e1',r1='$r1', r2='000',nm_ruang='$nm_r1' where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and r1='$r1' and r2='000'";$cek .= $aqry;
	$aqry2 = "UPDATE ref_ruang2 set c1='$c1',c='$c',d='$d',e='$e',e1='$e1', r2='$r2',nm_ruang='$nm_r2',nm_penanggung_jawab='$nama' ,nip_penanggung_jawab='$nip' where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and r1='$r1' and r2='$r2'";$cek .= $aqry2;
	
	

						$qry = mysql_query($aqry);
						$qry2 = mysql_query($aqry2);
				}
							
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
	 $id_system = $_REQUEST['id'];
     $nourut= $_REQUEST['nourut'];
	 $nm_system= $_REQUEST['nm_system'];
	 $nm_modul= $_REQUEST['nm_modul'];
	 $status= $_REQUEST['status'];
	 $tgl_update = $_REQUEST['tgl_update'];
	 $username = $_REQUEST['username'];
	 
	$tgl_update = explode("-",$tgl_update);
	$tgl_update2 = $tgl_update[2].'-'.$tgl_update[1].'-'.$tgl_update[0];
	 
	 $oldy=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from system_modul where no_urut='$nourut'"
		));
		
	 
	 if( $err=='' && $nourut =='' ) $err= 'No Urut Belum Di Isi !!';
	 if( $err=='' && $nm_system =='' ) $err= 'Data System Belum Di Isi !!';
	 if( $err=='' && $nm_modul =='' ) $err= 'Nama Modul Belum Di Isi !!';
	 if( $err=='' && $status =='' ) $err= 'Status Belum Di Pilih !!';
	/* if( $err=='' && $kode4 =='' ) $err= 'Kode SUB UNIT Belum Di Isi !!';
	 if( $err=='' && $gedung =='' ) $err= 'Kode Gedung Belum Di Pilih !!';
	 if( $err=='' && $ruang =='' ) $err= 'Kode Ruang Belum Di Isi !!';
	 if( $err=='' && $nm_ruang =='' ) $err= 'Nama Ruang Belum Di Isi !!';
	 if( $err=='' && $nm_penanggung =='' ) $err= 'Nama Penanggung Jawab Belum Di Isi !!';
	 if( $err=='' && $nm_penanggung =='' ) $err= 'Nama Penanggung Jawab Belum Di Isi !!';*/
	 
		if($fmST == 0){
		if($err=='' && $oldy['cnt']>0) $err="No Urut '$nourut' Sudah Ada";
			if($err==''){
				
					$aqry = "INSERT into system_modul (Id_system,no_urut,nm_modul,status_modul,tgl_update,uid) values('$id_system','$nourut','$nm_modul','$status','$tgl_update2','$username')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE system_modul SET Id_system='$id_system', no_urut='$nourut', nm_system='$nm_system', nm_modul='$nm_modul',status_modul='$status'  where Id_modul='".$idplh."'";	$cek .= $aqry;
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
				
		case 'hapus':{
			$get= $this->Hapus();
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
		
	
$hpssys=mysql_fetch_array(mysql_query("select COUNT(*) as cnt,system_modul.Id_modul, system_menu.Id_modul from system_modul
left join system_menu on system_menu.Id_modul=system_modul.Id_modul where system_menu.Id_modul='".$ids[$i]."'"));
	
	if($hpssys['cnt'] > 0 )$err ="Data tidak bisa di Hapus karena sudah ada di data MENU SYSTEM  !!";
		if($err=='' ){
					
					$qy = "DELETE FROM system_modul WHERE Id_modul='".$ids[$i]."' ";$cek.=$qy;
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
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script src='js/skpd.js' type='text/javascript'></script>".
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<link rel='stylesheet' href='css/upload_style.css' type='text/css'>".
			"<script src='js/jquery.js' type='text/javascript'></script>".			
			"<script src='js/jquery-ui.js' type='text/javascript'></script>".
			"<script src='js/jquery.min.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/jquery.form.js'></script> ".
			"<script src='js/jquery-ui.custom.js'></script>".
			 "<script type='text/javascript' src='js/admin/ManagementModulSystem/ManagementModulSystem.js' language='JavaScript' ></script>".
			  "<script type='text/javascript' src='js/admin/ManagementSystem/ManagementSystem2.js' language='JavaScript' ></script>
			 ".
			// "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
		
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$cek = $cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
			
	$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$dt['c1'] = $c1; 
		$dt['c'] = $c; 
		$dt['d'] = $d; 
		$dt['e'] = $e; 
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
  	function setFormEdit(){
		
	$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$c1=$kode[0];
		$c=$kode[1];
		$d=$kode[2];
		$e=$kode[3];
		$e1=$kode[4];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  system_modul WHERE Id_modul='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setFormEditdata($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
			
	function setForm($dt){	
	 global $SensusTmp ,$Main;
	 global $Main;
	 global $HTTP_COOKIE_VARS;
	 $uid = $HTTP_COOKIE_VARS['coID'];	
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 530;
	 $this->form_height = 160;
	 $tgl_update = date('d-m-Y');	
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management Modul System - Baru';
	  }else{
		$this->form_caption = 'Management Modul System - Edit';			
		$Id = $dt['id'];			
	 
	 
	 
	  }
	  
		$id = $_REQUEST['Id_system'];
		
		
		
		$queryKF="SELECT max(no_urut)as nourut FROM system_modul" ;
	//	$cek.="SELECT max(no_urut)as nourut FROM system";
		$get=mysql_fetch_array(mysql_query($queryKF));
		$no_urut=$get['nourut'] + 1;
					
		$queryc1="SELECT Id_system, concat(Id_system, '. ', nm_system) as vnama FROM system where status_system=1";
		$query1=mysql_fetch_array(mysql_query("SELECT * FROM system"));
		$querykode=mysql_fetch_array(mysql_query("SELECT kode FROM system where Id_system='".$dt['Id_system']."'"));
		$querynm=mysql_fetch_array(mysql_query("SELECT nm_system FROM system where Id_system='".$dt['Id_system']."'"));
		
		$dataaktif=1;
       //items ----------------------
		  $this->form_fields = array(
		  
		  	'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>120, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='$no_urut' style='width:30px;maxlength='3'>",
						 ),
						 
		  	'nm_system' => array( 
								'label'=>'NAMA SYSTEM',
								'labelWidth'=>100, 
								'value'=>"
								<input type='hidden' name='id' value='".$dt['id']."' placeholder='Kode' size='5px' id='id' readonly>
								<input type='text' name='kode' value='".$querykode['kode']."' placeholder='Kode' size='5px' id='kode' readonly>&nbsp
										  <input type='text' name='nm_system' value='".$querynm['nm_system']."' placeholder='Nama System' style='width:250px' id='nm_system' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Cari()' title='Cari' >" 
									 ),
			
			/*'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>
					
					"<div id='cont_c1'>".cmbQuery('fmc1',$c,$queryc1,'style="width:250px;"onchange="'.$this->Prefix.'.pilihUrusan()"','-- PILIH --')."</div>",
						 ),*/
						 
			'nm_modul' => array( 
						'label'=>'NAMA MODUL',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nm_modul' id='nm_modul' value='".$dt['nm_modul']."' style='width:250px;'>",
						 ),	
						 			 		
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dataaktif,$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),	
			
			'tgl_update' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='$tgl_update' style='width:80px;'readonly />  "
										
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
			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormEditdata($dt){	
	 global $SensusTmp ,$Main;
	 global $Main;
	 global $HTTP_COOKIE_VARS;
	 $uid = $HTTP_COOKIE_VARS['coID'];	
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 530;
	 $this->form_height = 160;
	 $tgl_update = date('d-m-Y');	
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Management Modul System - Baru';
	  }else{
		$this->form_caption = 'Management Modul System - Edit';			
		$Id = $dt['id'];			
	 
	 
	 
	  }
	  
		$id = $_REQUEST['Id_system'];
		
		
		
		/*$queryKF="SELECT max(no_urut)as nourut FROM system_modul" ;
	//	$cek.="SELECT max(no_urut)as nourut FROM system";
		$get=mysql_fetch_array(mysql_query($queryKF));
		$no_urut=$get['nourut'] + 1;*/
					
		$queryc1="SELECT Id_system, concat(Id_system, '. ', nm_system) as vnama FROM system where status_system=1";
		$query1=mysql_fetch_array(mysql_query("SELECT * FROM system"));
		$querykode=mysql_fetch_array(mysql_query("SELECT kode FROM system where Id_system='".$dt['Id_system']."'"));
		$querynm=mysql_fetch_array(mysql_query("SELECT nm_system FROM system where Id_system='".$dt['Id_system']."'"));
		
		$dataaktif=1;
       //items ----------------------
		  $this->form_fields = array(
		  
		  	'no_urut' => array( 
						'label'=>'No URUT',
						'labelWidth'=>120, 
						'value'=>"<input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'name='nourut' id='nourut' maxlength='3' value='".$dt['no_urut']."' style='width:30px;maxlength='3'>",
						 ),
						 
		  	'nm_system' => array( 
								'label'=>'NAMA SYSTEM',
								'labelWidth'=>100, 
								'value'=>"
								<input type='hidden' name='id' value='".$dt['id']."' placeholder='Kode' size='5px' id='id' readonly>
								<input type='text' name='kode' value='".$querykode['kode']."' placeholder='Kode' size='5px' id='kode' readonly>&nbsp
										  <input type='text' name='nm_system' value='".$querynm['nm_system']."' placeholder='Nama System' style='width:250px' id='nm_system' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Cari()' title='Cari' >" 
									 ),
			
			/*'nm_system' => array( 
						'label'=>'NAMA SYSTEM',
						'labelWidth'=>100, 
						'value'=>
					
					"<div id='cont_c1'>".cmbQuery('fmc1',$c,$queryc1,'style="width:250px;"onchange="'.$this->Prefix.'.pilihUrusan()"','-- PILIH --')."</div>",
						 ),*/
						 
			'nm_modul' => array( 
						'label'=>'NAMA MODUL',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='nm_modul' id='nm_modul' value='".$dt['nm_modul']."' style='width:250px;'>",
						 ),	
						 			 		
			'status' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status_modul'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),	
			
			'tgl_update' =>	array(
								'label'=>'TANGGAL UPDATE',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_update' id='tgl_update' class='' value='$tgl_update' style='width:80px;'readonly />  "
										
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
			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
			
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	$NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='80' align='center'>No Urut</th>
	   <th class='th01' width='450' align='center'>Nama System</th>
	   <th class='th01' width='450' align='center'>Nama Modul</th>
	   <th class='th01' width='120' align='center'>Status</th>
	   <th class='th01' width='150' align='center'>Tanggal Update</th>
	   <th class='th01' width='300' align='center'>User Name</th>
	    </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	// $id = $_REQUEST['$id_system'];
	 $isi['Id_system'];
	 if($isi['status_modul']==1){
	 	$status='AKTIF';
	 }elseif($isi['status_modul']==2){
	 	$status='TIDAK AKTIF';
	 }
	 
	 $datsys=mysql_fetch_array(mysql_query("select Id_system from system_modul"));
	 
	 $datmod=mysql_fetch_array(mysql_query("select nm_system from system where Id_system='".$isi['Id_system']."'"));
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="right"',$isi['no_urut']);
	 
	// $Koloms[] = array('align="left"',$isi['nm_system']);
	// $Koloms[] = array('align="left"',$datmod['nm_system']);
	 $Koloms[] = array('align="left"',$datmod['nm_system']);
	 $Koloms[] = array('align="left"',$isi['nm_modul']);
	 $Koloms[] = array('align="left"',$status);
	 $Koloms[] = array('align="left"',TglInd($isi['tgl_update']));
	 $Koloms[] = array('align="left"',$isi['uid']);
	  
	 return $Koloms;
	}
		
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arrOrder = array(
	  	         array('1','Kode SKPD'),
			     array('2','Nama SKPD'),
			     array('3','Nama RUANG'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectKode','Kode SKPD'),	
			array('selectNama','Nama SKPD'),		
			array('selectRuang','Nama Ruang'),		
			);
	/*$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
			//	WilSKPD_ajxVW($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".
			"<tr><td>".
			"<div id='skpd_pegawai' ></div>".
			$vOrder=			
			genFilterBar(
				array(							
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). //generate checkbox					
					"&nbsp&nbsp<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>&nbsp&nbsp"
					//<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>"
					
					.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";*/
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$ref_skpdSkpdfmUrusan = $_REQUEST['ref_skpdSkpdfmUrusan'];
		$ref_skpdSkpdfmSKPD = $_REQUEST['ref_skpdSkpdfmSKPD'];//ref_skpdSkpdfmSKPD
		$ref_skpdSkpdfmUNIT = $_REQUEST['ref_skpdSkpdfmUNIT'];
		$ref_skpdSkpdfmSUBUNIT = $_REQUEST['ref_skpdSkpdfmSUBUNIT'];
		$ref_skpdSkpdfmSEKSI = $_REQUEST['ref_skpdSkpdfmSEKSI'];
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			//case 'selectKode': $arrKondisi[] = " c='".$isivalue[0]."' and d='".$isivalue[1]."' and e='".$isivalue[2]."' and e1='".$isivalue[3]."'"; break;
			case 'selectKode': $arrKondisi[] = " concat(c1,'.',c,'.',d,'.',e,'.',e1) like '$fmPILCARIvalue%'"; break;
			case 'selectNama': $arrKondisi[] = " nm_skpd like '%$fmPILCARIvalue%'"; break;	
			case 'selectRuang': $arrKondisi[] = " nm_ruang like '%$fmPILCARIvalue%'"; break;	
								 	
		}	
		if($ref_skpdSkpdfmUrusan!='00' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='0'){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
			if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !='')$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";
			if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='')$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
			if($ref_skpdSkpdfmSUBUNIT!='00' and $ref_skpdSkpdfmSUBUNIT !='')$arrKondisi[]= "e='$ref_skpdSkpdfmSUBUNIT'";
			if($ref_skpdSkpdfmSEKSI!='00' and $ref_skpdSkpdfmSEKSI !='')$arrKondisi[]= "e1='$ref_skpdSkpdfmSEKSI'";
		}
		
		/*$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//Cari 
		switch($fmPILCARI){			
			case 'selectNama': $arrKondisi[] = " nama_pasien like '%$fmPILCARIvalue%'"; break;
			case 'selectAlamat': $arrKondisi[] = " alamat like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	*/
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " c1,c,d,e,e1 $Asc1 " ;break;
			case '2': $arrOrders[] = " nm_skpd $Asc1 " ;break;
			
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
$ManagementModulSystem = new ManagementModulSystemObj();
?>