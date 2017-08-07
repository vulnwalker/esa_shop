<?php

class templateObj  extends DaftarObj2{	
	var $Prefix = 'template';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_template'; //bonus
	var $TblName_Hapus = 'ref_template';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'TEMPLATE DISTRIBUSI BARANG';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='template.xls';
	var $namaModulCetak='TEMPLATE DISTRIBUSI BARANG';
	var $Cetak_Judul = 'TEMPLATE DISTRIBUSI BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'templateForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $subDir = "atisisbada_demo_v2";
	
	function setTitle(){
		return 'TEMPLATE DISTRIBUSI BARANG';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".buttonEditDiatas()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
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
			$username = $_COOKIE['coID'];
			setcookie('TemplateUnit','');
			mysql_query("delete from temp_template where username = '$username'");		
		    mysql_query("delete from temp_detail_template where username = '$username'");						
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
		case 'buang':{		

			$id = str_replace("[","",$_REQUEST['template_cb']);	
			$id = str_replace("]","",$id);
			$content = $id[0];
														
		break;
		}
					
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
	   
		case 'BidangAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKELOMPOK = cekPOST('fmKELOMPOK2');
				$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK2');
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content->kelompok = cmbQuery1("fmKELOMPOK2",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBidang' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.KelompokAfter()\"",'Pilih','');
				$content->subkelompok = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
				$content->subsubkelompok = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
			break;
		}
				   			

			case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
			 $selectedBidang = $_REQUEST['fmSKPDBidang'];
			 $selectedskpd = $_REQUEST['fmSKPDskpd'];
			 $selectedUnit = $_REQUEST['fmSKPDUnit'];
			 
			 $username = $_COOKIE['coID'];
			 $cmbRo = 'disabled';
			 
			 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";
		     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00'and c1 = '$selectedUrusan'  and e1='000'";	
		
		     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1='$selectedUrusan' and d != '00' and  e = '00' and e1='000' ";
			 
			 
			$codeAndNameUnit = "SELECT e, concat(e, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1='$selectedUrusan' and d = '$selectedskpd' and  e != '00' and e1='000' ";
			
			
			 $bidang =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	
			 $skpd = cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
			 $unit = cmbQuery('cmbUnitForm', $selectedUnit, $codeAndNameUnit,''.$cmbRoEdit.' onchange=detailTemplate.setCookiesUnit();','-- Pilih Unit --');

				$content = array('bidang' => $bidang, 'skpd' =>$skpd ,'unit' => $unit);
			break;
			}
			
			
			
			case 'updateTempDetail':{
				foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 			} 
				$username = $_COOKIE['coID'];
				$query ="update temp_detail_template set jumlah = '$VALUE' where id = '$ID'";
				mysql_query($query);
				
				$get = mysql_fetch_array(mysql_query("select sum(jumlah) as total from temp_detail_template where username = '$username' "));
				$total = $get['total'];
				$content = array('query' => $query , 'idnya' => $ID , 'valuenya' => $VALUE, 'total'=>number_format($total,0,",","."));
				
			break;
		    }
			
			case 'kembalikanKeTemplate':{
				foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 			} 
				
			$jumlahTotal = 0;
			$username = $_COOKIE['coID'];
			$ambilQuerynya = "select sum(jumlah) as total from temp_detail_template where username = '$username'";
			$getJumlahTotal = mysql_fetch_array(mysql_query($ambilQuerynya));
			
			$jumlahTotal = $getJumlahTotal['total'];
		 	$idTemplateAsli = $ID;
			
			mysql_query("delete from ref_rincian_template where refid_template = '$idTemplateAsli'");
			
		    $ambil  = mysql_query("select * from temp_detail_template where username = '$username'"); 
		    while ( $baris = mysql_fetch_array($ambil)) {
				$insert_detail_template = array(
						'refid_template' => $idTemplateAsli,
		                'c1' => $c1,
		                'c' => $c,
		                'd' => $d,
		                'e' => $baris['e'],
						'e1' => $baris['e1'],
						'jumlah' => $baris['jumlah'],
						'nama_sub_unit' => $baris['nama_sub_unit']
		            );
		            if (mysql_query(VulnWalkerInsert('ref_rincian_template', $insert_detail_template))) {
		                $insertDetailTemplate = 'SUKSES';
		            } else {
		                $insertDetailTemplate = 'GAGAL';
		            }
			}
			$query = "update ref_template set nama = '$nama', tgl= '$tanggal', nomor='$nomor', jumlah = '$jumlahTotal' where id ='$idTemplateAsli'";
			mysql_query($query);
			mysql_query("delete from temp_template where username='$username'");
			mysql_query("delete from temp_detail_template where username='$username'");
			break;
		    }
			
			case 'moveTemplateToTemp':{
				foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 			} 
				$username = $_COOKIE['coID'];
				mysql_query("delete from temp_template where username = '$username'");		
				mysql_query("delete from temp_detail_template where username = '$username'");	
				
				$c1 = "";
				$c = "";
				$d = "";
				$e = "";
				$nama_template = "";
				$tanggal = "";
				$nomor_distribusi = "";
				
					$moveParentTemplate  = mysql_query("select * from ref_template where id = '$id'"); 
				    while ($baris = mysql_fetch_array($moveParentTemplate)) {
						$c1= $baris['c1'];
						$c = $baris['c'];
						$d = $baris['d'];
						$nama_template = $baris['nama'];
						$tanggal = $baris['tgl'];
						$nomor_distribusi = $baris['nomor'];
					}
				
					$dataInsertTempTemplate = array(
											  'username' => $username,
											  'nama_template' => $nama_template,
											  'tanggal' => $tanggal,
											  'nomor_distribusi' => $nomor_distribusi,
											  'c1' => $c1,
											  'c' => $c,
											  'd' => $d
											   );
					mysql_query(VulnWalkerInsert('temp_template',$dataInsertTempTemplate));
					
				
				
				    $ambil  = mysql_query("select * from ref_rincian_template where refid_template = '$id'"); 
				    while( $baris = mysql_fetch_array($ambil)  ) {
						$insert_detail_temp_template = array(
								'ref_id_template' => $id,
				                'c1' => $baris['c1'],
				                'c' => $baris['c'],
				                'd' => $baris['d'],
				                'e' => $baris['e'],
								'e1' => $baris['e1'],
								'jumlah' => $baris['jumlah'],
								'nama_sub_unit' => $baris['nama_sub_unit'],
								'username' => $username
				            );
				            if (mysql_query(VulnWalkerInsert('temp_detail_template', $insert_detail_temp_template))) {
				                echo 'SUKSES';
				            } else {
				                echo 'GAGAL';
				            }
					}
				
			break;
		    }
			
			
			case 'updateTempRincianTemplate':{
				foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 			} 
				
				
				$jumlahTotal = 0;
				$username = $_COOKIE['coID'];
				$ambilQuerynya = "select sum(jumlah) as total from temp_detail_template where username = '$username'";
				$getJumlahTotal = mysql_fetch_array(mysql_query($ambilQuerynya));
				
				$jumlahTotal = $getJumlahTotal['total'];
			 	$idTemplateAsli = "";
				$insert_template = array(
			                'nama' => $nama,
			                'tgl' => $tanggal,
			                'nomor' => $nomor,
			                'c1' => $c1,
			                'c' => $c,
			                'd' => $d,
							'jumlah' => $jumlahTotal
			            );
			            if (mysql_query(VulnWalkerInsert('ref_template', $insert_template))) {
			                $insertTemplate = 'SUKSES';
			            } else {
			                $insertTemplate = 'GAGAL';
			            }
				
				$huahah = mysql_fetch_array(mysql_query("select max(id) as hubla from ref_template "));
				$idTemplateAsli  = $huahah['hubla'];
				
			
			    $ambil  =  mysql_query("select * from temp_detail_template where username = '$username'"); 
			    while ($baris = mysql_fetch_array($ambil)) {
					$insert_detail_template = array(
							'refid_template' => $idTemplateAsli,
			                'c1' => $c1,
			                'c' => $c,
			                'd' => $d,
			                'e' => $baris['e'],
							'e1' => $baris['e1'],
							'jumlah' => $baris['jumlah'],
							'nama_sub_unit' => $baris['nama_sub_unit']
			            );
			            if (mysql_query(VulnWalkerInsert('ref_rincian_template', $insert_detail_template))) {
			                $insertDetailTemplate = 'SUKSES';
			            } else {
			                $insertDetailTemplate = 'GAGAL';
			            }
				}
				
				mysql_query("delete from temp_template where username='$username'");
				mysql_query("delete from temp_detail_template where username='$username'");
				
				
			break;
		    }
				
			case 'destroyCookies':{
			
			setcookie('TemplateUrusan','0');
			setcookie('TemplateBidang','00');
			setcookie('TemplateSkpd','00');	
			setcookie('TemplateUnit','00');
			setcookie('TemplateUnit','00');

				
				
			break;
		    }	
				
			case 'SKPDAfter':{
				$fmSKPDUnit = cekPOST('fmSKPDUnit');
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
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
			"<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/master/ref_template/ref_template.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/ref_template/detailTemplate.js' language='JavaScript'></script>
			<script type='text/javascript' src='js/master/ref_template/detailTemplateEdit.js' language='JavaScript'></script>
			<script type='text/javascript' src='js/master/ref_template/VulnWalkerFrameWork.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/ref_template/jquery.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/ref_template/jquery-ui.min.js' language='JavaScript'></script>
			<link rel='stylesheet' type='text/css' href='js/master/ref_template/jquery-ui.css'>
			".
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		
		$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['tgl'] = date("Y-m-d"); 
		$dt['urusan'] = $_REQUEST['fmSKPDUrusan'];
		$dt['bidang'] = $_REQUEST['fmSKPDBidang'];
		$dt['skpd'] = $_REQUEST['fmSKPDskpd'];
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
	
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid;
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		
		if($err == ''){
			$aqry = "SELECT * FROM  ref_template WHERE id='".$this->form_idplh."' "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$c1 = $dt['c1'];
			$c = $dt['c'];
			$d = $dt['d'];
			$fm = $this->setForm($dt);
		}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	s 	
	 $form_name = $this->Prefix.'_form';
	 $cmbRo = '';
				
	 $this->form_width = 550;
	 $this->form_height = 350;
	 if ($this->form_fmST==0) {
		$this->form_caption = 'INPUT TEMPLATE DISTRIBUSI BARANG';
		
	 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
	 $selectedBidang = $_REQUEST['fmSKPDBidang'];
     $selectedskpd = $_REQUEST['fmSKPDskpd'];
	 $kemanaCoba = $this->Prefix.".Simpan()";

	  }else{
		$this->form_caption = 'EDIT TEMPLATE DISTRIBUSI BARANG';	
		$kode = $dt['kode'];				
		$dt['nama_template'] = $dt['nama'];
		$dt['urusan'] = $dt['c1'];	
		$dt['bidang'] = $dt['c'];	
		$dt['skpd'] = $dt['d'];
		$idDD = $dt['id'];
		
		$asdasd =explode("-",$dt['tgl']);
		$tgl = $asdasd[2]."-".$asdasd[1]."-".$asdasd[0];
		$cmbRo = 'disabled';	
		$selectedUrusan = $dt['c1'];
		$selectedBidang = $dt['c'];
     	$selectedskpd =  $dt['d'];
		$selectedUnit =  $dt['e'];
		$valEditTanggal = $dt['tgl'];
		
			setcookie('TemplateUrusan',$selectedUrusan);
			setcookie('TemplateBidang',$selectedBidang);
			setcookie('TemplateSkpd',$selectedskpd);	
			setcookie('TemplateUnit',$selectedUnit);
		 $kemanaCoba = $this->Prefix.".SubmitEdit($idDD)";

	  }
	 $kondisiBidang = "";
	 $cmbRo = 'disabled';
	 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";


     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00' and c1 = '$selectedUrusan'  and e1='000'";	

     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d != '00' and  e = '00' and e1='000' ";
     $cek .= $codeAndNameskpd;

	  	$query = "select * from ref_skpd " ;$cek .=$query;
	  	$res = mysql_query($query);

$comboBoxUrusanForm = cmbQuery('cmbUrusanForm', $selectedUrusan, $codeAndNameUrusan,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
	

	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');

	$comboBoxSkpdForm  = cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
	
	
	 //items ----------------------
	  $this->form_fields = array(
	  	  	'kode0' => array(
	  					'label'=>'URUSAN',
						'labelWidth'=>150, 
						'value'=> $comboBoxUrusanForm
						 ),
	  		'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=> $comboBoxBidangForm
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=> $comboBoxSkpdForm
						 ),
			'nama_template' => array( 
						'label'=>'NAMA TEMPLATE',
						'labelWidth'=>150, 
						'value'=>$dt['nama_template'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),	
			 'tgl' => array( 
						'label'=>'TANGGAL',
						'labelWidth'=>100, 
						'value'=>"		
							<input type='hidden' name='hTanggal' id ='hTanggal'>
							<input type='text'  id='tanggal' value='$tgl' >
							"
						 ),	
			'nomor' => array( 
						'label'=>'NOMOR DISTRIBUSI',
						'labelWidth'=>150, 
						'value'=>$dt['nomor'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),
			'detailTemplate' => array( 
						'label'=>'',
						'value'=>"
						
						<div id='detailTemplate' style='height:5px'></div>", 
						
						'type'=>'merge'
					 ),
			'detailTemplateEdit' => array( 
						'label'=>'',
						'value'=>"
						
						<div id='detailTemplateEdit' style='height:5px'></div>", 
						
						'type'=>'merge'
					 ),	
		
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='$kemanaCoba' title='Simpan' > &nbsp ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
							
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
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox	
  	   <th class='th01' width='1000'>NAMA TEMPLATE</th>	
	   <th class='th01' width='200'>JUMLAH</th>	
	   <th class='th01' width='250'>TANGGAL</th>	
	   <th class='th01' width='400'>NOMOR</th>	
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function genForm2($withForm=TRUE){	
		$form_name = $this->Prefix.'_form';	
				
		if($withForm){
			$params->tipe=1;
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
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
					$this->form_menu_bawah_height,
					'',$params
					).
				"</form>";
				
		}else{
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
		return $form;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama']); 
	 $Koloms[] = array('align="right"',number_format($isi['jumlah'],0,",","."));
	 $getTanggal = $isi['tgl'];
	 $pecahTanggal = explode("-",$getTanggal);
	 $Koloms[] = array('align="center"',$pecahTanggal[2]."-".$pecahTanggal[1]."-".$pecahTanggal[0]);
	 $Koloms[] = array('align="left"',$isi['nomor']); 
	 return $Koloms;
	}
	


	function genDaftarOpsi(){

	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd, $fmSKPDUrusan;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');
		$baris = $_REQUEST['baris'];
	if ($baris == ''){
		$baris = "25";		
	}

	 $arr = array(	
			array('nama','NAMA TEMPLATE'),		
			array('jumlah','JUMLAH'),	
			array('tgl','TANGGAL'),	
			array('nomor','NOMOR')		
			);
		
	 $arrOrder = array(
			     	array('1','NAMA TEMPLATE'),
					array('2','JUMLAH'),		
					array('3','TANGGAL'),	
					array('4','NOMOR')			
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
$TampilOpt = 
	"<div class='FilterBar' style='margin-top:10px;'><table style='width:100%'>".
			CmbUrusanBidangSkpd('template').
"</table></div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:140px;'> ".cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','')."  </td><td><input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>  &nbsp <input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:150px;'> ".cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."  </td>
			<td style='width:200px;' ><input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'> menurun &nbsp Jumlah Data : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'>  </td><td align='left' ><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>";
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 

				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];

		$kueBidang = $_COOKIE['cofmSKPD'];
		$kueSKPD =  $_COOKIE['cofmUNIT'];
		$ref_skpdSkpdfmUrusan = $_REQUEST['fmSKPDUrusan'];
		$ref_skpdSkpdfmSKPD = $_REQUEST['fmSKPDBidang'];
		$ref_skpdSkpdfmUNIT = $_REQUEST['fmSKPDskpd'];

		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		switch($fmPILCARI){			
			case 'nama': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;	
			case 'jumlah': $arrKondisi[] = " jumlah like '%$fmPILCARIvalue%'"; break;	
			case 'tgl': $arrKondisi[] = " tgl like '%$fmPILCARIvalue%'"; break;	
			case 'nomor': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 			
		}
		

		if($kueBidang!='00' and $kueBidang!='')$arrKondisi[]= "c='$kueBidang'";
		if($kueSKPD!='00' and $kueSKPD!='')$arrKondisi[]= "d='$kueSKPD'";
		
		
		if($ref_skpdSkpdfmUrusan!='0' and $ref_skpdSkpdfmUrusan !=''  )$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
		
		if($ref_skpdSkpdfmUrusan!='0' && $ref_skpdSkpdfmUrusan!='0'){
			if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";
		}
		if($ref_skpdSkpdfmSKPD!='00'){
		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		}
		
		


		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
			case '2': $arrOrders[] = " jumlah $Asc1 " ;break;
			case '3': $arrOrders[] = " tgl $Asc1 " ;break;
			case '4': $arrOrders[] = " nomor $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';

		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; 
		$Limit = $Mode == 3 ? '': $Limit;
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	

	function Hapus($ids){
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		
			$a = "SELECT count(*) as cnt, aa.template_terbesar, aa.template_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_template bb ON aa.template_terbesar = bb.nama OR aa.template_terkecil = bb.nama WHERE bb.nama='".$ids[$i]."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "template ".$ids[$i]." Tidak Bisa DiHapus ! Sudah Digunakan Di Ref Barang.";
		
			if($err=='' ){
					$qy = "DELETE FROM $this->TblName_Hapus WHERE id='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					$qy = "DELETE FROM ref_rincian_template WHERE refid_template='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
}
$template = new templateObj();
?>