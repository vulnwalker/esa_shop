<?php

class RefBarangButuhObj  extends DaftarObj2{	
	var $Prefix = 'RefBarangButuh';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_barang'; //daftar
	var $TblName_Hapus = 'ref_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='25';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'RefBarangButuhForm'; 	
			
	function setTitle(){
		return 'Daftar Barang';
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
		
		//$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		//$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		//$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";*/
	}	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
	 $kode_barang = $_REQUEST['kode_barang'];
	 $kode_barang = explode('.',$kode_barang);
	 $f=$kode_barang[0];	
	 $g=($kode_barang[1]=='')?"00":$kode_barang[1];
	 $h=($kode_barang[2]=='')?"00":$kode_barang[2];	
	 $i=($kode_barang[3]=='')?"00":$kode_barang[3];
	 $j=($kode_barang[4]=='')?"00":$kode_barang[4];	 
	 $nama_barang = $_REQUEST['nama_barang']; 
 	 if($err=='' && $nama_barang =='' ) $err= 'Nama Barang belum diisi';	 	 
 	 if($err=='' && $masa_manfaat =='' ) $err= 'Masa Manfaat belum diisi';
 	 if($err=='' && $residu =='' ) $err= 'Residu belum diisi';	
 			if($fmST == 0){ //input ref_barang
				if($err==''){ 
						//memecah kode barang
					 //memecah kode jurnal
						 $kode_jurnal = explode('.',$kode_account);
						 $ka=$kode_jurnal[0];	
						 $kb=$kode_jurnal[1];
						 $kc=$kode_jurnal[2];	
						 $kd=$kode_jurnal[3];
						 $ke=$kode_jurnal[4];			 	 	  
						$aqry1 = "INSERT into ref_barang (f,g,h,i,j,nm_barang,ka,kb,kc,kd,ke,masa_manfaat,residu)
						"."values('$f','$g','$h','$i','$j','$nama_barang','$ka','$kb','$kc','$kd','$ke','$masa_manfaat','$residu')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);						
						if($qry==FALSE)
						{ 
							$err="Gagal menyimpan barang";
						}else{
							$aqry2 = "UPDATE ref_barang
				        			  set "." masa_manfaat ='$masa_manfaat',
									  ka='$ka',
									  kb='$kb',
									  kc='$kc',
									  kd='$kd',
									  ke='$ke',
									  residu='$residu'".
						 			 "WHERE $kondisi";	$cek .= $aqry2;						
							$qry2 = mysql_query($aqry2);
						}
														
				}/*else{else{
					$err="Gagal menyimpan barang";
				}*/
			}elseif($fmST == 1){						
				if($err==''){
						 //memecah kode barang
						 $kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
						 //memecah kode jurnal
						 $kode_jurnal = explode('.',$kode_account);
						 $ka=$kode_jurnal[0];	
						 $kb=$kode_jurnal[1];
						 $kc=$kode_jurnal[2];	
						 $kd=$kode_jurnal[3];
						 $ke=$kode_jurnal[4];						 						 
						$aqry2 = "UPDATE ref_barang
			        			  set "." nm_barang = '$nama_barang',
								  masa_manfaat ='$masa_manfaat',
								  ka='$ka',
								  kb='$kb',
								  kc='$kc',
								  kd='$kd',
								  ke='$ke',
								  residu='$residu'".
					 			 "WHERE concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'";	$cek .= $aqry2;
						$qry = mysql_query($aqry2);
						if($qry==FALSE)
						{ 
							$err="Gagal menyimpan barang";
						}else{
							$aqry2 = "UPDATE ref_barang
				        			  set "." masa_manfaat ='$masa_manfaat',
									  ka='$ka',
									  kb='$kb',
									  kc='$kc',
									  kd='$kd',
									  ke='$ke',
									  residu='$residu'".
						 			 "WHERE $kondisi";	$cek .= $aqry2;						
							$qry2 = mysql_query($aqry2);
						}
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
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
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

		$ref_pilihjurnal = $_REQUEST['id'];
		$kode_barang = explode(' ',$ref_pilihjurnal);
		$f=$kode_barang[0];	
		$g=$kode_barang[1];
		$h=$kode_barang[2];	
		$i=$kode_barang[3];
		$j=$kode_barang[4];
		//query ambil data ref_jurnal
		$get = mysql_fetch_array( mysql_query("select * from ref_barang where f=$f and g=$g and h=$h and i=$i and j=$j"));
		$kode_barang=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j;
		
		$content = array('kode_barang'=>$kode_barang, 'nama_barang'=>$get['nm_barang']);	
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
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script type='text/javascript' src='js/master/refstdbutuh/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/refstdbutuh/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$dt['readonly']='';
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];		
		if(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.'.$fmSUBSUBKELOMPOK.'.';
		}		
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4]; 
		$bulan=date('Y-m-')."1";
		//query ambil data ref_barang
		$aqry = "select * from ref_barang where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$na=mysql_fetch_array(mysql_query("select * from ref_jurnal where ka='".$dt['ka']."' and kb='".$dt['kb']."' and kc='".$dt['kc']."' and kd='".$dt['kd']."' and ke='".$dt['ke']."'"));
		$dt['kode_barang']=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j;
		$dt['kode_account']=$dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke']; 
		$dt['nama_account']=$na['nm_account'];
		$dt['readonly']='readonly';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 175;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';
	  }
	  				
 	    $username=$_REQUEST['username'];
		
		$lengthKodeBrg =  12 + $Main->KODEBARANGJ_DIGIT ;
		$sampleKodeBrg = "*00.00.00.00.".$Main->KODEBARANGJ ;
		
		//query ref_batal
		$queryKB = "SELECT f,nama_barang FROM ref_barang_persediaan where f !=0 and g=0";
		
		$dt['residu'] = $dt['residu'] == '' ?0: $dt['residu'];
		$dt['masa_manfaat'] = $dt['masa_manfaat'] == '' ?0: $dt['masa_manfaat'];
		
		
       //items ----------------------
		  $this->form_fields = array(
			'' => array( 
								'label'=>'Kode barang',
								'labelWidth'=>100, 
								'value'=>'<b>BIDANG/KELOMPOK/SUB KELOMPOK/SUB SUB KELOMPOK/BARANG</b>', 
								'type'=>'merge',
							 ),	

			'kode_barang' => array( 
								'label'=>'Kode barang',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_barang' value='".$dt['kode_barang']."' maxlength='$lengthKodeBrg' size='17px' id='kode_barang' >&nbsp&nbsp  
									<font color=red>$sampleKodeBrg</font>" 
									 ),	
									 
			'nama_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>100, 
								'value'=>$dt['nm_barang'], 
								'type'=>'text',
								'id'=>'nama_barang',
								'param'=>"style='width:250ppx;' size=50px"
									 ),	

			'masa_manfaat' => array( 
								'label'=>'Masa Manfaat',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='masa_manfaat' value='".$dt['masa_manfaat']."' maxlength='4' size='8px' id='masa_manfaat' >&nbsp&nbsp  <font color=red>*tahun</font>" 
									 ),
									 

			'residu' => array( 
								'label'=>'Residu',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='residu' value='".str_replace(".",",",$dt['residu'])."' maxlength='6' size='5px' id='residu' onkeypress='return isNumberKey(event);'>&nbsp&nbsp  <font color=red>*percent (%)</font>" 
									 ),
									 										 										 
			'kode_account' => array( 
								'label'=>'Kode Account',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account' value='".$dt['kode_account']."' size='10px' id='kode_account' readonly>&nbsp
										  <input type='text' name='nama_account' value='".$dt['nama_account']."' size='30px' id='nama_account' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnal()' title='Cari Jurnal' >" 
									 ),	

			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='',$tahun_anggaran='', $height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
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
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
			$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						500,
						'Pilih Barang',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
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
		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $fmBIDANG = $_REQUEST['fmBIDANG'];
	 $fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
	 $fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
	 $fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];				
	if(empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
	{
		$nama_barang="NAMA BIDANG";
	}
	elseif(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK) || empty($fmKELOMPOK))
	{
		$nama_barang="NAMA KELOMPOK";
	}
	elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK) || empty($fmSUBKELOMPOK))
	{
		$nama_barang="NAMA SUB KELOMPOK";	
	}
	elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
	{
		$nama_barang="NAMA SUB SUB KELOMPOK";			
	}
	elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
	{
		$nama_barang="NAMA BARANG";		
	}			 
	 $headerTable =
	 "<thead>
	 <tr>
	   <th class='th01' width='20' >No.</th>
	   $Checkbox		
	   <th class='th01' align='left' width='100'>Kode Barang</th>
	   <th class='th01' align='left' width='800'>Nama Barang</th>
	   <th class='th01' align='left' width='100'>Masa Manfaat (Tahun)</th>
	   <th class='th01' align='left' width='100'>Residu (%)</th>	   
   	   <th class='th01' align='left' width='200'>Kode Account</th>
	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 $aqry = "select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."'";
	 $na=mysql_fetch_array(mysql_query($aqry));
	 $na = array_map('utf8_encode', $na);
	 $kode_account=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'];
	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left" width="100" style="border-left-style: none;" ',$kode_barang);
 	 $Koloms[] = array('align="left" width="200"',$isi['nm_barang']);	 	 	 	 
 	 $Koloms[] = array('align="left" width="200"',$isi['masa_manfaat']);
 	 $Koloms[] = array('align="left" width="200"',str_replace(".",",",$isi['residu']));	 
  	 $Koloms[] = array('align="left" width="200"',$kode_account.'<br>'.$na['nm_account']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	$fmBIDANG = cekPOST('fmBIDANG');
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
			//<table style='width:100%'><tbody><tr><td align='left'>
			//<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			//<tbody><tr valign='middle'>   						
			//	<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='15' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>";
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];				
		$fmMERK = $_REQUEST['fmMERK'];
		$fmTYPE = $_REQUEST['fmTYPE'];		
		
		switch($fmPILCARI){
			case 'selectfg': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectbarang': $arrKondisi[] = " nama_barang like '%".$fmPILCARIvalue."%'"; break;					 	
		}
		
		$arrKondisi[] = " f!='00' and g!='00' and h!='00' and i!='00' and j!='000'";
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(f,g,h,i,j) like '%".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANG']."%'";	

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g,h,i,j) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}

			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//}
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
$RefBarangButuh = new RefBarangButuhObj();

?>