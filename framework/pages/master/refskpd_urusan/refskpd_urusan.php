<?php

class refskpd_urusanObj  extends DaftarObj2{	
	var $Prefix = 'refskpd_urusan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v1_refskpd_urusan'; //bonus
	var $TblName_Hapus = 'ref_skpd_urusan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='refskpd_urusan.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'refskpd_urusan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'refskpd_urusanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'Daftar Mapping SKPD dan Urusan Pemerintahan';
	}
	
	function setMenuEdit(){
		return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
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
	 $c= $_REQUEST['c']; 
 	 $d= $_REQUEST['d']; 
 	 $bk= $_REQUEST['bk']; 
 	 $ck= $_REQUEST['ck']; 
 	 $dk= $_REQUEST['dk']; 	 
	 //query cek c,d sudah ada
	 $c_d=mysql_fetch_array(mysql_query("select * from ref_skpd_urusan where c='$c' AND d='$d'"));	 
	  
	 if( $err=='' && $bk =='' ) $err= 'Urusan Belum Di Isi !!';
	 if( $err=='' && $ck =='' ) $err= 'Bidang Belum Di Isi !!';
 	 if( $err=='' && $dk =='' ) $err= 'Dinas Belum Di Isi !!';	 		

			if($fmST == 0){
				if($err==''){
					if(empty($c_d['c']) && empty($c_d['d'])){
						$aqry = "INSERT into ref_skpd_urusan (c,d,bk,ck,dk) values('$c','$d','$bk','$ck','$dk')";	$cek .= $aqry;						
					}else{
						$aqry = "UPDATE ref_skpd_urusan
							set "." bk = '$bk',
							ck = '$ck',
							dk = '$dk'".
							"WHERE c='".$c."' AND d='".$d."'";	$cek .= $aqry;
					}
					$qry = mysql_query($aqry);
				}
			}elseif($fmST == 1){				
				if($err==''){
					if(empty($c_d['c']) && empty($c_d['d'])){
						$aqry = "INSERT into ref_skpd_urusan (c,d,bk,ck,dk) values('$c','$d','$bk','$ck','$dk')";	$cek .= $aqry;						
					}else{
						$aqry = "UPDATE ref_skpd_urusan
							set "." bk = '$bk',
							ck = '$ck',
							dk = '$dk'".
							"WHERE c='".$c."' AND d='".$d."'";	$cek .= $aqry;
					}					$qry = mysql_query($aqry);
				}
			}else{
				if($err==''){
					
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
		
	  	case 'fmskpd':{
			$fmBidang = $_REQUEST['fmBidang'];

				$query = "SELECT d,nm_skpd FROM ref_skpd WHERE c='$fmBidang' AND d!='00' AND e='00' AND e1='000'"; $cek .= $query2;
				$hasil = mysql_query($query);
				$fmskpd = "<option value=''>-- PILIH SKPD--</option>";
				while ($dt = mysql_fetch_array($hasil))
				{
					$fmskpd.="<option value='".$dt['d']."'>".$dt['nm_skpd']."</option>";
				}
		$content = "<select name='fmSKPD' id='fmSKPD'>".$fmskpd."</select>";
				
		break;
	   } 	
	   
	  	case 'urusan':{
			$urusan = $_REQUEST['urusan'];

				$query = "SELECT ck,nm_urusan FROM ref_urusan WHERE bk='".$urusan."' AND ck!='0' AND dk='0'"; $cek .= $query;
				$hasil = mysql_query($query);
				$div_bidang = "<option value=''>-- PILIH Bidang--</option>";
				while ($dt = mysql_fetch_array($hasil))
				{
					$div_bidang.="<option value='".$dt['ck']."'>".$dt['nm_urusan']."</option>";
				}
		$content = "<select name='ck' id='ck' onChange='".$this->Prefix.".Bidang()'>".$div_bidang."</select>";
				
		break;
	   }
	   
	  	case 'bidang':{
			$urusan = $_REQUEST['urusan'];
			$bidang = $_REQUEST['bidang'];

				$query = "SELECT dk,nm_urusan FROM ref_urusan WHERE bk='".$urusan."' AND ck='$bidang' AND dk!='0'"; $cek .= $query;
				$hasil = mysql_query($query);
				$div_dinas = "<option value=''>-- PILIH Dinas--</option>";
				while ($dt = mysql_fetch_array($hasil))
				{
					$div_dinas.="<option value='".$dt['dk']."'>".$dt['nm_urusan']."</option>";
				}
		$content = "<select name='dk' id='dk'>".$div_dinas."</select>";
				
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
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/master/refskpd_urusan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
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
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode=explode(" ",$this->form_idplh);
		$this->form_fmST = 1;						
		//get data 
		$aqry = "SELECT * FROM  v1_refskpd_urusan WHERE c='".$kode[0]."' AND d='".$kode[1]."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		//query skpd
		$c=mysql_fetch_array(mysql_query("select * from ref_skpd where c=".$dt['c']." and d=00 and e=00 and e1=00"));
		$d=mysql_fetch_array(mysql_query("select * from ref_skpd where c=".$dt['c']." and d=".$dt['d']." and e=00 and e1=00"));
		$dt['bidang']=$c['nm_skpd'];
		$dt['skpd']=$d['nm_skpd'];

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
	 $this->form_width = 600;
	 $this->form_height = 150;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'SKPD URUSAN - Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'SKPD URUSAN - Edit';			
		//$Id = $dt['Id'];			
	  }
	//query program
	$queryUrusan = "SELECT bk,nm_urusan FROM ref_urusan WHERE bk!='0' AND ck='0' AND dk='0'";
 	//query Kegiatan
	$queryBidang = "SELECT ck,nm_urusan FROM ref_urusan WHERE bk='".$dt['bk']."' AND ck!='0' AND dk='0'";
 	//query Kegiatan
	$queryDinas = "SELECT dk,nm_urusan FROM ref_urusan WHERE bk='".$dt['bk']."' AND ck='".$dt['ck']."' AND dk!='0'"; 
		
	 //items ----------------------
	  $this->form_fields = array(
			'bidang' => array( 
							'label'=>'BIDANG',
							'labelWidth'=>100, 
							'value'=>"<input type='text' name='bidang' value='".$dt['bidang']."' size='30px' id='bidang' readonly>&nbsp&nbsp
									<input type='hidden' name='c' value='".$dt['c']."' id='c' >"
								),	
						 
			'skpd' => array( 
							'label'=>'SKPD',
							'labelWidth'=>100, 
							'value'=>"<input type='text' name='skpd' value='".$dt['skpd']."' size='30px' id='skpd' readonly>&nbsp&nbsp
									<input type='hidden' name='d' value='".$dt['d']."' id='d' >"
								),	
			'Urusan Pemerintah' => array('label'=>'', 
								 'value'=> 'Urusan Pemerintah',  
								 'type'=>'merge' , 
								 //'row_params'=>"style='height:24'"
								 ),

			'urusan_up' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Urusan', 
							  'value'=>"<div id='div_urusan'>". cmbQuery('bk',$dt['bk'],$queryUrusan,'id=bk onChange=\''.$this->Prefix.'.Urusan()\'','-- Pilih Urusan --')."</div>", 
							  //'row_params'=>"style='height:24'" 
							  ),
								
			'bidang_up' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bidang',
							 'value'=>"<div id='div_bidang'>". cmbQuery('ck',$dt['ck'],$queryBidang,'id=ck onChange=\''.$this->Prefix.'.Bidang()\'','-- Pilih Bidang --')."</div>",   
							 //'type'=>'' ,
							 //'row_params'=>"style='height:24'"
							 ),
			
			'dinas_up' => array(  'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dinas', 
								 'value'=>"<div id='div_dinas'>". cmbQuery('dk',$dt['dk'],$queryDinas,'id=dk','-- Pilih Dinas --')."</div>",   
								 'type'=>'' , 
								 //'row_params'=>"style='height:24'"
								 )	
	
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
	   <th class='th01' width='300'>Kode SKPD</th>
	   <th class='th01' width='300'>Nama SKPD</th>
   	   <th class='th01' width='300'>Kode Urusan</th>
   	   <th class='th01' width='300'>Nama Urusan Pemerintah</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;

	 $bk = sprintf("%02s", $isi['bk']);
	 $ck = sprintf("%02s", $isi['ck']);
	 $dk = sprintf("%02s", $isi['dk']);
		
	 $kd_skpd=$isi['c'].'.'.$isi['d'];
	 $kd_urusan=$bk.'.'.$ck.'.'.$dk;
 	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$kd_skpd);
	 $Koloms[] = array('align="left"',$isi['nm_skpd']);
	 $Koloms[] = array('align="left"',$kd_urusan);
	 $Koloms[] = array('align="left"',$isi['nm_urusan']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	/*$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmBARANG = cekPOST('fmBARANG');			
	//$fmPILCARI = $_REQUEST['fmPILCARI'];	
	//$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//$fmORDER1 = cekPOST('fmORDER1');
	//$fmDESC1 = cekPOST('fmDESC1');
	
	
	 $arr = array(
			//array('selectAll','Semua'),
			array('selectfg','Kode Barang'),
			array('selectbarang','Nama Barang'),	
			);
		
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Barang'),
			     array('2','Nama Barang'),	
	 );	
				
	$TampilOpt = 
			//"<tr><td>".	
			"<div class='FilterBar'>".
			//<table style='width:100%'><tbody><tr><td align='left'>
			//<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			//<tbody><tr valign='middle'>   						
			//	<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			
			"<table style='width:100%'>
			<tr>
			<td style='width:120px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>";
			/*"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='14' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";	*/		
	
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arrOrder = array(
	  	          array('1','Kode SKPD'),
			     	array('2','Nama SKPD'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectKode','Kode SKPD'),	
			array('selectNama','Nama SKPD'),		
			);
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
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
		
		$refskpd_urusanSkpdfmSKPD = $_REQUEST['refskpd_urusanSkpdfmSKPD'];//ref_skpdSkpdfmSKPD//refskpd_urusanSkpdfmSKPD
		$refskpd_urusanSkpdfmUNIT = $_REQUEST['refskpd_urusanSkpdfmUNIT'];
		$refskpd_urusanSkpdfmSUBUNIT = $_REQUEST['refskpd_urusanSkpdfmSUBUNIT'];
		$refskpd_urusanSkpdfmSEKSI = $_REQUEST['refskpd_urusanSkpdfmSEKSI'];
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			//case 'selectKode': $arrKondisi[] = " c='".$isivalue[0]."' and d='".$isivalue[1]."' and e='".$isivalue[2]."' and e1='".$isivalue[3]."'"; break;
			case 'selectKode': $arrKondisi[] = " concat(c,'.',d,'.',e,'.',e1) like '$fmPILCARIvalue%'"; break;
			case 'selectNama': $arrKondisi[] = " nm_skpd like '%$fmPILCARIvalue%'"; break;	
								 	
		}	
		if($refskpd_urusanSkpdfmSKPD!='00' and $refskpd_urusanSkpdfmSKPD !='')$arrKondisi[]= "c='$refskpd_urusanSkpdfmSKPD'";
		if($refskpd_urusanSkpdfmUNIT!='00' and $refskpd_urusanSkpdfmUNIT !='')$arrKondisi[]= "d='$refskpd_urusanSkpdfmUNIT'";
		if($refskpd_urusanSkpdfmSUBUNIT!='00' and $refskpd_urusanSkpdfmSUBUNIT !='')$arrKondisi[]= "e='$refskpd_urusanSkpdfmSUBUNIT'";
		if($refskpd_urusanSkpdfmSEKSI!='00' and $refskpd_urusanSkpdfmSEKSI !='')$arrKondisi[]= "e1='$refskpd_urusanSkpdfmSEKSI'";
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
			case '1': $arrOrders[] = " c,d,e,e1 $Asc1 " ;break;
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
$refskpd_urusan = new refskpd_urusanObj();
?>