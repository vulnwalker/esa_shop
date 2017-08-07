<?php

class UserManajemenObj  extends DaftarObj2{	
	var $Prefix = 'UserManajemen';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'admin'; //bonus
	var $TblName_Hapus = 'admin';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('uid');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'USER MANAGEMENT';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usermanajemen.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'DAFTAR HAK AKSES';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'UserManajemenForm';
	var $noModul=15; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'HAK AKSES - Username';
	}
	
	function setMenuEdit(){
		return
		
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
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
			"<input type='hidden' id='skpd_usermanajemenfmBidang' name='skpd_usermanajemenfmBidang' value='".$skpd."'>".
			"<input type='hidden' id='skpd_usermanajemenfmBagian' name='skpd_usermanajemenfmBagian' value='".$unit."'>".
			"<input type='hidden' id='skpd_usermanajemenfmSubBagian' name='skpd_usermanajemenfmSubBagian' value='".$subunit."'>".
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				//"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $uid = $_REQUEST['uid'];
	 $nm_lengkap = $_REQUEST['nm_lengkap'];
	 $pwd = $_REQUEST['password'];
	 $md5_pwd=md5($pwd);
	 $skpd =$_REQUEST['UserManajemenSkpd2fmSKPD'];
	 $unit =$_REQUEST["UserManajemenSkpd2fmUNIT"];
	 $subunit =$_REQUEST["UserManajemenSkpd2fmSUBUNIT"];
	 //$id_pegawai = $_REQUEST['id_pegawai'];
	 //$bidang = $_REQUEST['bidang']==''?'00':$_REQUEST['bidang'];
	 //$bagian = $_REQUEST['bagiann']==''?'00':$_REQUEST['bagiann'];
	 //$subbagian = $_REQUEST['subbagiann']==''?'00':$_REQUEST['subbagiann'];
	 $group = $skpd.'.'.$unit.'.'.$subunit;
	 $level = $_REQUEST['fmLEVELPENGGUNA'];
	 //$group = $_REQUEST['fmGROUPPENGGUNA'];
	 $modul01= $_REQUEST['fmMODUL01'];
	 $modul02= $_REQUEST['fmMODUL02'];
	 $modul03= $_REQUEST['fmMODUL03'];
	 $modul04= $_REQUEST['fmMODUL04'];
	 $modul05= $_REQUEST['fmMODUL05'];
	 $modul06= $_REQUEST['fmMODUL06'];
	 $modul07= $_REQUEST['fmMODUL07'];
	 $modul08= $_REQUEST['fmMODUL08'];
	 $modul09= $_REQUEST['fmMODUL09'];
	 $modul10= $_REQUEST['fmMODUL10'];
	 $modul11= $_REQUEST['fmMODUL11'];
	 $modul12= $_REQUEST['fmMODUL12'];
	 $modul13= $_REQUEST['fmMODUL13'];
	 $status = $_REQUEST['fmStatus'];
	 $fmMODULref=$_REQUEST['fmMODULref'];  	  
	 $fmMODULadm=$_REQUEST['fmMODULadm'];  	  
	 $fmStatus=$_REQUEST['fmStatus'];  	  
	 
	if( $err=='' && $uid =='' ) $err= 'User Name Belum Di Isi !!';
	if( $err=='' && $pwd =='' ) $err= 'Password Belum Di Isi !!';
	// if( $err=='' && $nama =='' ) $err= 'Nama Pegawai Belum Di Isi !!';
	// if( $err=='' && $group =='' ) $err= 'Group Belum Di Isi !!';
	 if( $err=='' && $level =='' ) $err= 'Level Belum Di Pilih !!';
	 $old = mysql_fetch_array(mysql_query("SELECT uid FROM admin WHERE uid='$uid'"));
				
			if($fmST == 0){
				if($err==''){ 
				if($uid == $old['uid']){
				//if($uid==$old['uid']){
				       $get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM admin WHERE uid='$uid' "));
					   if($get['cnt']>0 ) $err='User Name '.$uid.' Sudah Ada !';
					}
					$aqry = "INSERT into admin(
									uid,
									nama,
									password,
									level,
									`group`,
									modul01,
									modul02,
									modul03,
									modul04,
									modul05,
									modul06,
									modul07,
									modul08,
									modul09,
									modul10,
									modul11,
									modul12,
									modul13,
									modulref,
									moduladm,
									status)
							 values(
							 		'$uid',
									'$nm_lengkap',
									'$md5_pwd',
									'$level',
									'$group',
									'$modul01',
									'$modul02',
									'$modul03',
									'$modul04',
									'$modul05',
									'$modul06',
									'$modul07',
									'$modul08',
									'$modul09',
									'$modul10',
									'$modul11',
									'$modul12',
									'$modul13',
									'$fmMODULref',
							 		'$fmMODULadm',
							 		'$fmStatus'
								    )";	$cek.= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{			
				if($err==''){
				$aqry = "UPDATE admin 
				 		 set uid='$uid',
						 	 nama='$nm_lengkap',
							 password='$md5_pwd',
							 level='$level',
							 `group`='$group',
							 modul01='$modul01',
							 modul02='$modul02',
							 modul03='$modul03',
							 modul04='$modul04',
							 modul05='$modul05',
							 modul06='$modul06',
							 modul07='$modul07',
							 modul08='$modul08',
							 modul09='$modul09',
							 modul10='$modul10',
							 modul11='$modul11',
							 modul12='$modul12',
							 modul13='$modul13',
							 modulref='$fmMODULref',
							 moduladm='$fmMODULadm',
							 status='$fmStatus'
						 WHERE uid='".$idplh."'";$cek.= $aqry;
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
		/**
		case 'autocomplete_getdata':{
				$json = FALSE;
				$fm = $this->autocomplete_getdata();
				break;
		}
		**/
		
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
		
		case 'getdata_pegawai':{
			$Id = $_REQUEST['id_pegawai'];
			$cek = $Id;
			$aqry = "SELECT nip, toRoman(gol) as gol, ruang, pangkat, jabatan FROM ref_pegawai WHERE Id=$Id "; $cek .= $aqry;
			$dt=mysql_fetch_array(mysql_query($aqry));
				
			if($dt==FALSE) {
				$err="Pegawai Tidak Ada !";
			}
			else{
			
				$content = array(
					 'nip'=>$dt['nip'],
					 'gol'=>$dt['gol'],
					 'ruang'=>$dt['ruang'],
  					 'pangkat'=>$dt['pangkat'],
					 'jabatan'=>$dt['jabatan'],								 
					 );
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
							document.getElementById('".$this->Prefix."_cont_skpd').innerHTML='<div id=\"skpd_usermanajemen\"></div>';
							skpd_usermanajemen.initial();
						});
					</script>";
		return 
			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script src='js/jquery.js' type='text/javascript'></script>".
			"<script src='js/jquery-ui.js' type='text/javascript'></script>".
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	/**
	function autocomplete_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//$id_bagian = $_REQUEST['id_bagian'];
		
		$bidang = $_REQUEST['bidang'];
		$bagian = $_REQUEST['bagian'];
		$subbagian = $_REQUEST['subbagian'];
		
		if($bidang == '00'){
			$kondisi = '';
		}else{
			if($bagian == '00'){
				$kondisi = "kode like '".$bidang."%' and";
			}elseif($bagian != '00' && $subbagian == '00'){
				$kondisi = "kode like '".$bidang.'.'.$bagian."%' and";
			}else{
				$kondisi = "kode like '".$bidang.'.'.$bagian.'.'.$subbagian."' and";
			}
		
		}
		
		//echo $name_startsWith
		$sql = "select *  from v1_pegawai where $kondisi nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;			
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];
				$a_json_row["label"] =  $row['nama'];
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
	**/
	//cetak ====================================
	
	/*function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		return
			"<table style='width:100%'>
					<tr>
						<td align=center style='font-size:15;'>KEMENTRIAN ENERGI DAN SUMBERDAYA MINERAL REPUBLIK INDONESIA<br><br></td>
					</tr>
					<tr valign=middle>
						<td align=center style='font-size:17;'><h3>B A D A N &nbsp; G E O L O G I</h3></td>
					</tr>
					<tr>
						<td align=center style='font-size:13;'>Jalan Diponegoro No. 57 Bandung, Telp. 022-7206515 Fax: 7218154</td>
					</tr>
					<tr>
						<td align=center style='font-size:13;'>Jalan Jend. Gatot Subroto kav. 49 Jakarta 12950, Telp.021-5228371 Fax: 5228372</td>
					</tr>
					<tr><td><hr size='4' color='black'></td></tr>
				</table><br>".
			//$Main->kopLaporan.
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\" align=center>".strtoupper($this->setCetakTitle())."</td>
			</tr>
			</table><br><br>	";
			
			
	} */

	function setCetak_footer($xls=FALSE){
		return 
				"<br>";
				//$this->PrintTTD($this->Cetak_WIDTH, $xls);
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
		$aqry = "SELECT * FROM  admin WHERE uid='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));

		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	

	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content='';		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 570;
	 
		
	 if ($this->form_fmST==0) {
	  						
		$this->form_caption = 'Baru';
		$username	 ="<input type='text' name='uid' id='uid' value='".$dt['uid']."' size='55' onChange='".$this->Prefix.".cek()'>
					";
						
		//$chkLevel2="checked";	
		$chkModul01_0="checked";	
		$chkModul02_0="checked";	
		$chkModul03_0="checked";	
		$chkModul04_0="checked";	
		$chkModul05_0="checked";	
		$chkModul06_0="checked";	
		$chkModul07_0="checked";	
		$chkModul08_0="checked";	
		$chkModul09_0="checked";	
		$chkModul10_0="checked";	
		$chkModul11_0="checked";	
		$chkModul12_0="checked";	
		$chkModul13_0="checked";	
		$chkModulref_0="checked";	
		$chkModuladm_0="checked";
		$chkStatus_1="checked";
		$readOnly='';
		
	  }else{
		$this->form_caption = 'Edit';
		
		$fmGROUPPENGGUNA = $dt['group'];
		
		$username	 ="<input type='text' name='uid' id='uid' value='".$dt['uid']."' size='55' onChange='".$this->Prefix.".cek()' readonly='1' >
						";
						
		$readOnly='readonly=1';
		$fmLEVELPENGGUNA = $dt['level'];
		$fmMODUL01 = $dt['modul01'];
		$fmMODUL02 = $dt['modul02'];
		$fmMODUL03 = $dt['modul03'];
		$fmMODUL04 = $dt['modul04'];
		$fmMODUL05 = $dt['modul05'];
		$fmMODUL06 = $dt['modul06'];
		$fmMODUL07 = $dt['modul07'];
		$fmMODUL08 = $dt['modul08'];
		$fmMODUL09 = $dt['modul09'];
		$fmMODUL10 = $dt['modul10'];
		$fmMODUL11 = $dt['modul11'];
		$fmMODUL12 = $dt['modul12'];
		$fmMODUL13 = $dt['modul13'];
		$fmMODULref = $dt['modulref'];
		$fmMODULadm = $dt['moduladm'];
		$fmStatus = $dt['status'];
		
		$chkLevel1 = $fmLEVELPENGGUNA == "1" ? "checked":"";$chkLevel2 = $fmLEVELPENGGUNA == "2" ? "checked":"";$chkLevel3 = $fmLEVELPENGGUNA == "3" ? "checked":"";
		//$chkLevel1 = $fmLEVELPENGGUNA == "1" ? "checked":""; $chkLevel2 = $fmLEVELPENGGUNA == "2" ? "checked":""; $chkLevel3 = $fmLEVELPENGGUNA == "3" ? "checked":"";
		$chkStatus_0 = $fmStatus == "0" ? "checked":"";$chkStatus_1 = $fmStatus == "1" ? "checked":"";
		
		$chkModul01_0 = $fmMODUL01== "0" ? "checked":"";$chkModul01_1 = $fmMODUL01== "1" ? "checked":"";$chkModul01_2 = $fmMODUL01== "2" ? "checked":"";
		$chkModul02_0 = $fmMODUL02 == "0" ? "checked":"";$chkModul02_1 = $fmMODUL02 == "1" ? "checked":"";$chkModul02_2 = $fmMODUL02 == "2" ? "checked":"";
		$chkModul03_0 = $fmMODUL03 == "0" ? "checked":"";$chkModul03_1 = $fmMODUL03 == "1" ? "checked":"";$chkModul03_2 = $fmMODUL03 == "2" ? "checked":"";
		$chkModul04_0 = $fmMODUL04 == "0" ? "checked":"";$chkModul04_1 = $fmMODUL04 == "1" ? "checked":"";$chkModul04_2 = $fmMODUL04 == "2" ? "checked":"";
		$chkModul05_0 = $fmMODUL05 == "0" ? "checked":"";$chkModul05_1 = $fmMODUL05 == "1" ? "checked":"";$chkModul05_2 = $fmMODUL05 == "2" ? "checked":"";
		$chkModul06_0 = $fmMODUL06 == "0" ? "checked":"";$chkModul06_1 = $fmMODUL06 == "1" ? "checked":"";$chkModul06_2 = $fmMODUL06 == "2" ? "checked":"";
		$chkModul07_0 = $fmMODUL07 == "0" ? "checked":"";$chkModul07_1 = $fmMODUL07 == "1" ? "checked":"";$chkModul07_2 = $fmMODUL07 == "2" ? "checked":"";
		$chkModul08_0 = $fmMODUL08 == "0" ? "checked":"";$chkModul08_1 = $fmMODUL08 == "1" ? "checked":"";$chkModul08_2 = $fmMODUL08 == "2" ? "checked":"";
		$chkModul09_0 = $fmMODUL09 == "0" ? "checked":"";$chkModul09_1 = $fmMODUL09 == "1" ? "checked":"";$chkModul09_2 = $fmMODUL09 == "2" ? "checked":"";
		$chkModul10_0 = $fmMODUL10 == "0" ? "checked":"";$chkModul10_1 = $fmMODUL10 == "1" ? "checked":"";$chkModul10_2 = $fmMODUL10 == "2" ? "checked":"";
		$chkModul11_0 = $fmMODUL11 == "0" ? "checked":"";$chkModul11_1 = $fmMODUL11 == "1" ? "checked":"";$chkModul11_2 = $fmMODUL11 == "2" ? "checked":"";
		$chkModul12_0 = $fmMODUL12 == "0" ? "checked":"";$chkModul12_1 = $fmMODUL12 == "1" ? "checked":"";$chkModul12_2 = $fmMODUL12 == "2" ? "checked":"";			
		$chkModul13_0 = $fmMODUL13 == "0" ? "checked":"";$chkModul13_1 = $fmMODUL13 == "1" ? "checked":"";$chkModul13_2 = $fmMODUL13 == "2" ? "checked":"";
		$chkModulref_0 = $fmMODULref == "0" ? "checked":"";$chkModulref_1 = $fmMODULref == "1" ? "checked":"";$chkModulref_2 = $fmMODULref == "2" ? "checked":"";
		$chkModuladm_0 = $fmMODULadm == "0" ? "checked":"";$chkModuladm_1 = $fmMODULadm == "1" ? "checked":"";$chkModuladm_2 = $fmMODULadm == "2" ? "checked":"";
		
	}
	 
	 //items ----------------------
	  $this->form_fields = array(
			'uid' => array( 
						'label'=>'ID Pengguna',
						//'labelWidth'=>200, 
						'value'=>$username, 
						'type'=>'',
						'param'=>""
						 ),
			
			'nm_lengkap' => array( 
						'label'=>'Nama Lengkap',
						//'labelWidth'=>150, 			 
						'value'=>"<input type='text' name='nm_lengkap' id='nm_lengkap' value='".$dt['nama']."' size='55'>
								 ",
						),
			'password' => array( 
				'label'=>'Sandi',
				//'labelWidth'=>200, 
				'value'=>
					"<input type='password' name='password' id='password' value='".$dt['password']."' size='47' $readOnly>".
					"&nbsp;&nbsp;<input type='button' value='Ganti' totle='Ganti Password'>"
					, 
			),
			
			
			
						
			'hak_akses' => array( 
						'label'=>'',						
						'value'=>'Group',
						'type'=>'merge'
						 ),
			'hak_akses2' => array( 
						'label'=>'Hak Akses',
						'value'=>WilSKPD_ajx($this->Prefix.'Skpd2', '100%', 94),
						'type'=>'merge'
						 ),
			'level' => array( 
				'label'=>'Level',
				//'labelWidth'=>200, 
				'value'=>
				"<INPUT $chkLevel1 TYPE='RADIO' NAME='fmLEVELPENGGUNA' ID='fmLEVELPENGGUNA' VALUE='1' onClick=\"".$this->Prefix.".LevelAdmin()\">&nbsp;Administrator &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkLevel2 TYPE='RADIO' NAME='fmLEVELPENGGUNA' ID='fmLEVELOPERATOR' VALUE='2' onClick=\"".$this->Prefix.".LevelOperator()\">&nbsp;Operator &nbsp;&nbsp;&nbsp;&nbsp;
				",
				'type'=>'',
			),
			'status' => array( 
						'label'=>'Status User',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkStatus_0 TYPE='RADIO' NAME='fmStatus' VALUE='0' >&nbsp;Disabled / Blocked&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkStatus_1 TYPE='RADIO' NAME='fmStatus' VALUE='1' >&nbsp;Aktif &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			'modul' => array( 
				'label'=>'',						
				'value'=>"<div style='padding: 4 0 4 0;'>Hak Akses</div>",
				'type'=>'merge'
			),	
			'modult' => array( 
				'label'=>'',						
				'value'=>
					"<table width='100%'>".
						"<tr valign='top'><td>1.</td><td>Perencanaan dan Penganggaran</td><td>:</td>".
						"<td>
							<INPUT $chkModul01_0 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_0' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul01_1 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul01_2 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>2.</td><td>Pengadaan</td><td>:</td>".
						"<td>
							<INPUT $chkModul02_0 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul02_1 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul02_2 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_2' VALUE='2' >&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>3.</td><td>Penerimaan dan Pengeluaran</td><td>:</td>".
						"<td>
							<INPUT $chkModul03_0 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul03_1 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul03_2 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>4.</td><td>Penggunaan</td><td>:</td>".
						"<td>
							<INPUT $chkModul04_0 TYPE='RADIO' NAME='fmMODUL04' ID='fmMODUL04_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul04_1 TYPE='RADIO' NAME='fmMODUL04' ID='fmMODUL04_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul04_2 TYPE='RADIO' NAME='fmMODUL04'ID='fmMODUL04_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>5.</td><td>Penatausahaan</td><td>:</td>".
						"<td>
							<INPUT $chkModul05_0 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul05_1 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul05_2 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>6.</td><td>Pemanfaatan</td><td>:</td>".
						"<td>
							<INPUT $chkModul06_0 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul06_1 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul06_2 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>7.</td><td>Pengamanan dan Pemeliharaan</td><td>:</td>".
						"<td>
							<INPUT $chkModul07_0 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul07_1 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul07_2 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"</tr>".
						"<tr valign='top'><td>8.</td><td>Penilaian</td><td>:</td>".
						"<td>
							<INPUT $chkModul08_0 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul08_1 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul08_2 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>9.</td><td>Penghapusan</td><td>:</td>".
						"<td>
							<INPUT $chkModul09_0 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul09_1 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul09_2 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"</tr>".
						"<tr valign='top'><td>10.</td><td>Pemindahtanganan</td><td>:</td>".
						"<td>
							<INPUT $chkModul10_0 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul10_1 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul10_2 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>11.</td><td>Pembiayaan</td><td>:</td>".
						"<td>
							<INPUT $chkModul11_0 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul11_1 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul11_2 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"</tr>".
						"<tr valign='top'><td>12.</td><td>Tuntutan Ganti Rugi</td><td>:</td>".
						"<td>
							<INPUT $chkModul12_0 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul12_1 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul12_2 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>13.</td><td>Pengawasan dan Pengendalian</td><td>:</td>".
						"<td>
							<INPUT $chkModul13_0 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul13_1 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModul13_2 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"<tr valign='top'><td>14.</td><td>Modul Referensi</td><td>:</td>".
						"<td>
							<INPUT $chkModulref_0 TYPE='RADIO' NAME='fmMODULref' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModulref_1 TYPE='RADIO' NAME='fmMODULref' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModulref_2 TYPE='RADIO' NAME='fmMODULref' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						"<tr valign='top'><td>15.</td><td>Modul Administrasi</td><td>:</td>".
						"<td>
							<INPUT $chkModuladm_0 TYPE='RADIO' NAME='fmMODULadm' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModuladm_1 TYPE='RADIO' NAME='fmMODULadm' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
							<INPUT $chkModuladm_2 TYPE='RADIO' NAME='fmMODULadm' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
						</td>".
						"</tr>".
						
					"</table>",
				'type'=>'merge'
			),	
			/*'modul01' => array( 
				'label'=>'1. Perencanaan dan Penganggaran',				
				'value'=>
					"<INPUT $chkModul01_0 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_0' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT $chkModul01_1 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT $chkModul01_2 TYPE='RADIO' NAME='fmMODUL01' ID='fmMODUL01_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
				'type'=>'',
			 ),		 
			'modul02' => array( 
						'label'=>'Modul 02.Pengadaan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul02_0 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul02_1 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul02_2 TYPE='RADIO' NAME='fmMODUL02' ID='fmMODUL02_2' VALUE='2' >&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			'modul03' => array( 
						'label'=>'Modul 03.Penerimaan dan Pengeluaran',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul03_0 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul03_1 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul03_2 TYPE='RADIO' NAME='fmMODUL03' ID='fmMODUL03_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul04' => array( 
						'label'=>'Modul 04.Penggunaan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul04_0 TYPE='RADIO' NAME='fmMODUL04' ID='fmMODUL04_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul04_1 TYPE='RADIO' NAME='fmMODUL04' ID='fmMODUL04_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul04_2 TYPE='RADIO' NAME='fmMODUL04'ID='fmMODUL04_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul05' => array( 
						'label'=>'Modul 05.Penatausahaan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul05_0 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul05_1 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul05_2 TYPE='RADIO' NAME='fmMODUL05' ID='fmMODUL05_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul06' => array( 
						'label'=>'Modul 06.Pemanfaatan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul06_0 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul06_1 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul06_2 TYPE='RADIO' NAME='fmMODUL06' ID='fmMODUL06_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
						 
			'modul07' => array( 
						'label'=>'Modul 07.Pengamanan dan Pemeliharaan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul07_0 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul07_1 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul07_2 TYPE='RADIO' NAME='fmMODUL07' ID='fmMODUL07_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul08' => array( 
						'label'=>'Modul 08. Penilaian',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul08_0 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul08_1 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul08_2 TYPE='RADIO' NAME='fmMODUL08' ID='fmMODUL08_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul09' => array( 
						'label'=>'Modul 09. Penghapusan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul09_0 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul09_1 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul09_2 TYPE='RADIO' NAME='fmMODUL09' ID='fmMODUL09_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul10' => array( 
						'label'=>'Modul 10. Pemindahtanganan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul10_0 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul10_1 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul10_2 TYPE='RADIO' NAME='fmMODUL10' ID='fmMODUL10_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
			
			'modul11' => array( 
						'label'=>'Modul 11. Pembiayaan',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul11_0 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul11_1 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul11_2 TYPE='RADIO' NAME='fmMODUL11' ID='fmMODUL11_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
					 
			'modul12' => array( 
						'label'=>'Modul 12.Tuntutan Ganti Rugi',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul12_0 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul12_1 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul12_2 TYPE='RADIO' NAME='fmMODUL12' ID='fmMODUL12_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
				
			'modul13' => array( 
						'label'=>'Modul 13. Pengawasan dan Pengendalian',
						//'labelWidth'=>200, 
						'value'=>
				"<INPUT $chkModul13_0 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_0' VALUE='0' >&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul13_1 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_1' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul13_2 TYPE='RADIO' NAME='fmMODUL13' ID='fmMODUL13_2' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
						'type'=>'',
						 ),
					
			'modulreferensi' => array( 
						'label'=>'Modul Referensi',
						//'labelWidth'=>200, 
						'value'=>
						"<INPUT $chkModulref_0 TYPE='RADIO' NAME='fmMODULref' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModulref_1 TYPE='RADIO' NAME='fmMODULref' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModulref_2 TYPE='RADIO' NAME='fmMODULref' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
				'type'=>'',
						 ),
				
			'moduladministrasi' => array( 
						'label'=>'Modul Administrasi',
						//'labelWidth'=>200, 
						'value'=>
						"<INPUT $chkModuladm_0 TYPE='RADIO' NAME='fmMODULadm' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModuladm_1 TYPE='RADIO' NAME='fmMODULadm' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModuladm_2 TYPE='RADIO' NAME='fmMODULadm' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;",
				'type'=>'',
						 ),
					*/							 
			
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
	<A href=\"pages.php?Pg=usermanajemen\" title='User Name' style='color:blue' >User Name</a> 
	<!--<A href=\"pages.php?Pg=logsystem\" title='Log System' >Log System</a>-->
	&nbsp&nbsp&nbsp	
	</td></tr></table>";*/
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' align='center'>ID Pengguna</th>
	   <th class='th01' align='center'>Nama Lengkap</th>
	   <th class='th01' align='center'>Level</th>
	   <th class='th01' align='center'>Group</th>
	   <th class='th01' align='center'>Modul 01</th>
	   <th class='th01' align='center'>Modul 02</th>
	   <th class='th01' align='center'>Modul 03</th>
	   <th class='th01' align='center'>Modul 04</th>
	   <th class='th01' align='center'>Modul 05</th>
	   <th class='th01' align='center'>Modul 06</th>
	   <th class='th01' align='center'>Modul 07</th>
	   <th class='th01' align='center'>Modul 08</th>
	   <th class='th01' align='center'>Modul 09</th>
	   <th class='th01' align='center'>Modul 10</th>
	   <th class='th01' align='center'>Modul 11</th>
	   <th class='th01' align='center'>Modul 12</th>
	   <th class='th01' align='center'>Modul 13</th>
	   <th class='th01' align='center'>Modul Referensi</th>
	   <th class='th01' align='center'>Modul Administrasi</th>
	   <th class='th01' align='center'>Modul Status</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 //Level
	 if($isi['level']==1){
	 	$isi['level']='Administrator';
	 }elseif($isi['level']==2){
	 	$isi['level']='Operator';
	 }else{
	 	$isi['level']='-';
	 }
	
	 //Modul 1
	 if($isi['modul01']==0){
	 	$isi['modul01']='Disable';
	 }elseif($isi['modul01']==1){
	 	$isi['modul01']='Write';
	 }else{
	 	$isi['modul01']='Read';
	 }
	 
	 //Modul 2
	 if($isi['modul02']==0){
	 	$isi['modul02']='Disable';
	 }elseif($isi['modul02']==1){
	 	$isi['modul02']='Write';
	 }else{
	 	$isi['modul02']='Read';
	 }
	 
	 //Modul 3
	if($isi['modul03']==0){
	 	$isi['modul03']='Disable';
	 }elseif($isi['modul03']==1){
	 	$isi['modul03']='Write';
	 }else{
	 	$isi['modul03']='Read';
	 }
	 
	 
	 //Modul 4
	 if($isi['modul04']==0){
	 	$isi['modul04']='Disable';
	 }elseif($isi['modul04']==1){
	 	$isi['modul04']='Write';
	 }else{
	 	$isi['modul04']='Read';
	 }
	 
	 //Modul 5
	 if($isi['modul05']==0){
	 	$isi['modul05']='Disable';
	 }elseif($isi['modul05']==1){
	 	$isi['modul05']='Write';
	 }else{
	 	$isi['modul05']='Read';
	 }
	 
	 //Modul 6
	 if($isi['modul06']==0){
	 	$isi['modul06']='Disable';
	 }elseif($isi['modul06']==1){
	 	$isi['modul06']='Write';
	 }else{
	 	$isi['modul06']='Read';
	 }
	 
	 //Modul 7
	 if($isi['modul07']==0){
	 	$isi['modul07']='Disable';
	 }elseif($isi['modul07']==1){
	 	$isi['modul07']='Write';
	 }else{
	 	$isi['modul07']='Read';
	 }
	 
	  //Modul 8
	 if($isi['modul08']==0){
	 	$isi['modul08']='Disable';
	 }elseif($isi['modul08']==1){
	 	$isi['modul08']='Write';
	 }else{
	 	$isi['modul08']='Read';
	 }
	
	  //Modul 9
	 if($isi['modul09']==0){
	 	$isi['modul09']='Disable';
	 }elseif($isi['modul09']==1){
	 	$isi['modul09']='Write';
	 }else{
	 	$isi['modul09']='Read';
	 }
	
	  //Modul 10
	 if($isi['modul10']==0){
	 	$isi['modul10']='Disable';
	 }elseif($isi['modul10']==1){
	 	$isi['modul10']='Write';
	 }else{
	 	$isi['modul10']='Read';
	 }
	 
	  //Modul 11
	 if($isi['modul11']==0){
	 	$isi['modul11']='Disable';
	 }elseif($isi['modul11']==1){
	 	$isi['modul11']='Write';
	 }else{
	 	$isi['modul11']='Read';
	 }
	 
	  //Modul 12
	 if($isi['modul12']==0){
	 	$isi['modul12']='Disable';
	 }elseif($isi['modul12']==1){
	 	$isi['modul12']='Write';
	 }else{
	 	$isi['modul12']='Read';
	 }
	  //Modul 13
	 if($isi['modul13']==0){
	 	$isi['modul13']='Disable';
	 }elseif($isi['modul13']==1){
	 	$isi['modul13']='Write';
	 }else{
	 	$isi['modul13']='Read';
	 }
	 
	 //Modul Ref
	 if($isi['modulref']==0){
	 	$isi['modulref']='Disable';
	 }elseif($isi['modulref']==1){
	 	$isi['modulref']='Write';
	 }else{
	 	$isi['modulref']='Read';
	 }
	 //Modul Adm
	 if($isi['moduladm']==0){
	 	$isi['moduladm']='Disable';
	 }elseif($isi['moduladm']==1){
	 	$isi['moduladm']='Write';
	 }else{
	 	$isi['moduladm']='Read';
	 }
	 
	 //Status
	 if($isi['status']==1){
	 	$isi['status']='Aktif';
	 }else{
	 	$isi['status']='Disable';
	 }
	 
	 //$aqry = "select *, toRoman(gol) as golongan from ref_pegawai where Id = '".$isi['ref_idpegawai']."'";
	// $row = mysql_fetch_array(mysql_query($aqry));
	  
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['uid']);
	 $Koloms[] = array('align="left"',$isi['nama']);	 
	 $Koloms[] = array('align="left"',$isi['level']);
	 $Koloms[] = array('align="center"',$isi['group']);
	 $Koloms[] = array('align="left"',$isi['modul01']);
	 $Koloms[] = array('align="left"',$isi['modul02']);
	 $Koloms[] = array('align="left"',$isi['modul03']);
	 $Koloms[] = array('align="left"',$isi['modul04']);
	 $Koloms[] = array('align="left"',$isi['modul05']);
	 $Koloms[] = array('align="left"',$isi['modul06']);
	 $Koloms[] = array('align="left"',$isi['modul07']);
	 $Koloms[] = array('align="left"',$isi['modul08']);
	 $Koloms[] = array('align="left"',$isi['modul09']);
	 $Koloms[] = array('align="left"',$isi['modul10']);
	 $Koloms[] = array('align="left"',$isi['modul11']);
	 $Koloms[] = array('align="left"',$isi['modul12']);
	 $Koloms[] = array('align="left"',$isi['modul13']);
	 $Koloms[] = array('align="left"',$isi['modulref']);
	 $Koloms[] = array('align="left"',$isi['moduladm']);
	 $Koloms[] = array('align="center"',$isi['status']);
	 
	 return $Koloms;
	 
	}
	
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$arr = array(
				array('pilihNB','Nama Barang'),	
				array('pilihMerk','Merk'),	
				array('pilihSatuan','Satuan'),	
		);
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'),
			array('3','Tahun Anggaran'),
					
		);
		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		
		//tgl bulan dan tahun
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');	
		
		//tahun
		//$t=date('Y');
		/**
		$tahunang = $_REQUEST['tahunang'];
		$thnaw = $tahunang-10;
		$thnak = $tahunang+11;
			
		$opsi = "<option value=''>--Tahun Anggaran--</option>";
		
		for ($a=$thnaw;$a<=$thnak;$a++){
				$sel = $a == $tahunang? "selected='true'" :'';
				$opsi.= "<option value='".$a."' ".$sel.">".$a."</option>";
		}
		
		$cmbth = "<select name='tahunang' id='tahunang'>".
				 		$opsi.
				 "</select>";		
		**/
		
		//$thh = $_REQUEST['thh'];
			
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx($this->Prefix.'Skpd') . 
			"</td>
			</tr>"."
			<tr><td></table>"."	
			<!--		
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>-->   						
				<!--<td align='left' style='padding:1 8 0 8; '>".
			
			//cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Semua --',''). //generate checkbox
			//"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>
			"</td>-->				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			/**"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Tahun Anggaran </div>".
			//createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1).
			//$cmbth.//"<input type='text' name='thh' id='thh' value='$thh'>".			
			"</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			/**
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Pilih--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>Desc."
					),			
				$this->Prefix.".refreshList(true)"
				).**/
			"";
				
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	
			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
				
		$tahunang = $_REQUEST['tahunang'];
			
		$arrKondisi = array();		
		
		//c,d,e
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		
		$query="select*from admin where uid='$UID'";
		$result=mysql_fetch_array(mysql_query($query));
		$level=$result['level'];
		$mygroup=$result['group'];
				
		$grp='';		
		if(!($fmSKPD=='' || $fmSKPD=='00') )$grp.=$fmSKPD ;
		if(!($fmUNIT=='' || $fmUNIT=='00') )$grp.='.'.$fmUNIT ;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') )$grp.='.'.$fmSUBUNIT;
								
		//if($grp !='')$arrKondisi[]= "`group` like'$grp%' ";
		if($grp !='')$arrKondisi[]= "`group` like'$grp%' and `group` <>'$mygroup'";
		
		 				
		$Kondisi= join(' and ',$arrKondisi);
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;		
		/**$Kondisi = $Kondisi =='' ? 
			"Where level=1 and `group` <>'$mygroup'":
			"Where('.$Kondisi.') or('level=1 and `group` <>"$mygroup"')";
		*/	
		if($level==2) $Kondisi="1=2";
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		switch($fmORDER1){
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
			case '3': $arrOrders[] = " tahun_anggaran $Asc1 " ;break;
		}	
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
		//limit --------------------------------------
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
$UserManajemen = new UserManajemenObj();
?>