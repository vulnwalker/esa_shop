<?php

class JnsBarangObj  extends DaftarObj2{	
	var $Prefix = 'JnsBarang';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_statusbarang2'; //bonus
	var $TblName_Hapus = 'ref_statusbarang2';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='barang.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'Ref Status Barang';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'RefStatusBarangForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'Daftar Status Barang';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru(1)","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	function setMenuView(){
		$RefStatusBarangMode=$_REQUEST['RefStatusBarangMode'];
		if($RefStatusBarangMode==1){
			return 
				"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
				"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";					
		}else{
			return 
				"";	
		}	
	}	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $stbarang= $_REQUEST['status_barang']; 
 	 $idbi= $_REQUEST['idbi']; 
	  
	 //if( $err=='' && $stbarang =='' ) $err= 'Status Barang Belum Di Isi !!';
	 		
			/*if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_statusbarang2 (nama) values('$stbarang')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}elseif($fmST == 1){				
				if($err==''){
					$aqry = "UPDATE ref_statusbarang2 set nama='$stbarang' WHERE Id='".$idplh."'";	$cek .= $aqry;
					$qry = mysql_query($aqry) or die(mysql_error());
				}
			}else{*/
			if($err==''){
				$bi=explode('.',$idbi);
				for($i=0;$i<count($bi);$i++){
					$aqry= "UPDATE buku_induk set jns_ekstra='$stbarang' WHERE id='".$bi[$i]."'";						
					$cek .= $aqry;
					$qry = mysql_query($aqry) or die(mysql_error());
				}	
			}
			//} //end else									
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpanJnsLain(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$stbarang= $_REQUEST['status_barang']; 
		$idbi= $_REQUEST['idbi']; 
	 
		if($err==''){
			$bi=explode('.',$idbi);
			for($i=0;$i<count($bi);$i++){
				$aqry= "UPDATE buku_induk set jns_lain='$stbarang' WHERE id='".$bi[$i]."'";						
				$cek .= $aqry;
				$qry = mysql_query($aqry) or die(mysql_error());
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function autocomplete_stsurvey_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		$sql = "select Id,nama from ref_statusbarang2 where nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;		
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
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
	
	function autocomplete_stsurvey2_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		$sql = "select Id,nama from ref_statusbarang2 where nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;		
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
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
		
		case 'formStSurvey':{				
			$fm = $this->setFormStSurvey();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}		
		case 'formJnsEkstra':{				
			$fm = $this->setFormJnsEkstra();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
			break;
		}	
		case 'formJnsLain':{				
			$fm = $this->setFormJnsLain();				
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
		
		case 'simpanJnsLain':{
			$get= $this->simpanJnsLain();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
			break;
	    }
		
		case 'test':{
			$cek = "cek";
			$err = "test";
			$json = FALSE;
			
			echo"<html>
				<head>
				<link type='text/css' href='css/menu.css' rel='stylesheet'></link>
				<link type='text/css' href='css/template_css.css' rel='stylesheet'></link>
				<link type='text/css' href='css/theme.css' rel='stylesheet'></link>
				<link type='text/css' href='dialog/dialog.css' rel='stylesheet'></link>
				<link type='text/css' href='lib/chatx/chatx.css' rel='stylesheet'></link>
				<link type='text/css' href='css/base.css' rel='stylesheet'></link>
				<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>			
				<script type='text/javascript' src='lib/js/JSCookMenu_mini.js' language='JavaScript'></script>	
				<script type='text/javascript' src='lib/js/ThemeOffice/theme.js' language='JavaScript'></script>	
				<script type='text/javascript' src='lib/js/joomla.javascript.js' language='JavaScript'>	</script>		
				<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>	
				<script type='text/javascript' src='js/ajaxc2.js' language='JavaScript' ></script>
				<script type='text/javascript' src='dialog/dialog.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/global.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/base.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/encoder.js' language='JavaScript'></script>
				<script type='text/javascript' src='lib/chatx/chatx.js' language='JavaScript'>	</script>				
				<script type='text/javascript' src='js/daftarobj.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/pageobj.js'></script>		
				<script type='text/javascript' src='js/master/refstatusbarang/refstatusbarang.js' language='JavaScript' ></script>
			<script src='js/jquery-ui.custom.js'></script>
			<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>	
				<script>
			 	$(document).ready(function(){
					RefStatusBarang.autocomplete_initial();
				});
				</script>
				</head>
				<body>
				<form action='' method='post' id='ref_status_barang_form' name='ref_status_barang_form'>				
				<div id='tampil_status_barang'>
				<input type='text' name='status_barang' id='status_barang' value='".$dt['nama']."' style='width:200px'>
				<input type='hidden' id='id_status_barang' name='id_status_barang' value='".$dt['Id']."' title='penyedia_barang'>
				<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"status_barang\").value=\"\";document.getElementById(\"id_status_barang\").value=\"\";'>
				<input type='button' value='Cari' id='cari' onclick ='".$this->Prefix.".Cari()' title='Cari' >
				</div>
				</form>
				</body>
				</html>";
			
			break;
    	}
		
	   case 'autocomplete_stsurvey_getdata':{
				$json = FALSE;
				$fm = $this->autocomplete_stsurvey_getdata();
				break;
		}
		
	   case 'autocomplete_stsurvey2_getdata':{
				$json = FALSE;
				$fm = $this->autocomplete_stsurvey2_getdata();
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
				$ref_pilih = $_REQUEST['id'];
				//$kode=explode(' ',$ref_pilihkodeaset);
				//$kode_aset=$kode[0].$kode[1].$kode[2].$kode[3].$kode[4].$kode[5];
				//query ambil data ref_aset
				$get = mysql_fetch_array( mysql_query("select * from ref_statusbarang2 where Id='$ref_pilih'"));
				//$ns=mysql_fetch_array(mysql_query("select * from ref_satuan where Id='".$get['ref_idsatuan']."'"));
				$content = array('el_id_status_brg_temp'=>$get['Id'],
								 'el_nm_status_brg_temp'=>$get['nama']);
								// 'el_nm_barang_temp'=>$get['nama'],
								 //'el_id_satuan_temp'=>$get['ref_idsatuan'],
								 //'el_nm_satuan_temp'=>$ns['nama']);	
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
		
		//if($err==''){
			$FormContent = $this->genDaftarInitial2();
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						900,
						500,
						'Pilih Status Barang',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
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
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>";
		return 	
			"<script type='text/javascript' src='js/master/refstatusbarang/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['mode'] = $_REQUEST['mode'];
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	
	function setFormJnsEkstra(){
		$dt=array();
		$cbid = $_REQUEST['cidBI'];
		//$this->form_idplh = $cbid[0];
		$this->form_fmST = 2;
		$dt['jml_data']=count($cbid);//$this->form_idplh;
		for($i=0;$i<count($cbid);$i++){
			if(($i+1)==count($cbid)){
				$dt['idbi'].=$cbid[$i];			
			}else{
				$dt['idbi'].=$cbid[$i].".";				
			}
		}
		//$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm3($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormJnsLain(){
		$dt=array();
		$cbid = $_REQUEST['cidBI'];
		//$this->form_idplh = $cbid[0];
		$this->form_fmST = 2;
		$dt['jml_data']=count($cbid);//$this->form_idplh;
		for($i=0;$i<count($cbid);$i++){
			if(($i+1)==count($cbid)){
				$dt['idbi'].=$cbid[$i];			
			}else{
				$dt['idbi'].=$cbid[$i].".";				
			}
		}
		//$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm4($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm3($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSurvey';				
	 $this->form_width = 450;
	 $this->form_height = 75;
		$this->form_caption = 'Jenis Ekstrakomptabel';
		
	

	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'pilih_barang' => array( 
								'label'=>'Data Terpilih',
								'labelWidth'=>100, 
								'value'=>"<div style='font-size:12px;font-weight:bold;'>".$dt['jml_data']."&nbspData Terpilih</div>",
								'type'=>'merge',
								'row_params'=>"valign='top'",
								'param'=>""
									 ),	
			'status_barang' => array( 
								'label'=>'Jenis Ekstra',
								'labelWidth'=>100, 
								'value'=> cmb2D_v2('status_barang',$dt['nama'],$Main->arrJnsEkstra ) ,
								'type'=>'',
								'row_params'=>"valign='top'",
								'param'=> ""
									 ),						 
	
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' id='idbi' name='idbi' value='".$dt['idbi']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan(2)' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseCari()' >";
							
		$form = $this->genForm(2);		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
	
	function setForm4($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSurvey';				
	 $this->form_width = 450;
	 $this->form_height = 75;
		$this->form_caption = 'Jenis Ekstrakomptabel';
		
	

	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'pilih_barang' => array( 
								'label'=>'Data Terpilih',
								'labelWidth'=>100, 
								'value'=>"<div style='font-size:12px;font-weight:bold;'>".$dt['jml_data']."&nbspData Terpilih</div>",
								'type'=>'merge',
								'row_params'=>"valign='top'",
								'param'=>""
									 ),	
			'status_barang' => array( 
								'label'=>'Jenis Ekstra',
								'labelWidth'=>100, 
								'value'=> cmb2D_v2('status_barang',$dt['nama'],$Main->arrJnsLain ) ,
								'type'=>'',
								'row_params'=>"valign='top'",
								'param'=> ""
									 ),						 
	
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' id='idbi' name='idbi' value='".$dt['idbi']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanJnsLain()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseCari()' >";
							
		$form = $this->genForm(2);		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
	
	
	function setFormStSurvey(){
		$dt=array();
		$cbid = $_REQUEST['cidBI'];
		//$this->form_idplh = $cbid[0];
		$this->form_fmST = 2;
		$dt['jml_data']=count($cbid);//$this->form_idplh;
		for($i=0;$i<count($cbid);$i++){
			if(($i+1)==count($cbid)){
				$dt['idbi'].=$cbid[$i];			
			}else{
				$dt['idbi'].=$cbid[$i].".";				
			}
		}
		//$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm2($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;						
		//get data 
		$aqry = "SELECT * FROM  ref_statusbarang2 WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function genForm($mode){	
		$form_name = $mode==1 ? $this->Prefix.'_form': $this->Prefix.'Survey_form';	
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
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Status Barang - Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Status Barang - Edit';			
		$Id = $dt['Id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'id_status_barang' => array( 
						'label'=>'Status Barang',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),		
	
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' id='mode' name='mode' value='".$dt['mode']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan(1)' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm(1);		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setForm2($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSurvey';				
	 $this->form_width = 450;
	 $this->form_height = 75;
		$this->form_caption = 'Status Recon';

	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'pilih_barang' => array( 
								'label'=>'Data Terpilih',
								'labelWidth'=>100, 
								'value'=>"<div style='font-size:12px;font-weight:bold;'>".$dt['jml_data']."&nbspData Terpilih</div>",
								'type'=>'merge',
								'row_params'=>"valign='top'",
								'param'=>""
									 ),	
									 
			'status_barang' => array( 
								'label'=>'Status Barang',
								'labelWidth'=>100, 
								'value'=>"
								<input type='text' name='status_barang' id='status_barang' value='".$dt['nama']."' style='width:200px'>
								<input type='hidden' id='id_status_barang' name='id_status_barang' value='".$dt['Id']."'>
								<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"status_barang\").value=\"\";document.getElementById(\"id_status_barang\").value=\"\";'>
								<input type='button' value='Cari' id='cari' onclick ='".$this->Prefix.".Cari()' title='Cari' >",
								'type'=>'',
								'row_params'=>"valign='top'",
								'param'=> ""
									 ),		
	
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' id='idbi' name='idbi' value='".$dt['idbi']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan(2)' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseCari()' >";
							
		$form = $this->genForm(2);		
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
	   <th class='th01' width='300'>Status Barang</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $kd_akun=$isi['k1'].'.'.$isi['k2'].'.'.$isi['k3'].'.'.$isi['k4'].'.'.$isi['k5'].'.'.$isi['k6'];
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 return $Koloms;
	}
	
	function genDaftarInitial($height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='RefStatusBarangMode' name='RefStatusBarangMode' value='1'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function genDaftarInitial2($height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
			"<input type='hidden' id='RefStatusBarangMode' name='RefStatusBarangMode' value='2'>".		
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
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
	$fmStBarang = $_REQUEST['fmStBarang'];
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$RefStatusBarangMode = $_REQUEST['RefStatusBarangMode'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
			$vOrder=
			genFilterBar(
				array(							
					"Status Barang &nbsp"."<input type='text' value='".$fmStBarang."' name='fmStBarang' id='fmStBarang'>".
					"<input type='hidden' id='RefStatusBarangMode' name='RefStatusBarangMode' value='$RefStatusBarangMode'>"				
					//cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					//"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmStBarang = $_REQUEST['fmStBarang'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//Cari 
		switch($fmPILCARI){			
			case 'selectBarang': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmStBarang)) $arrKondisi[]= " nama like '%$fmStBarang%'";
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
$JnsBarang = new JnsBarangObj();
?>