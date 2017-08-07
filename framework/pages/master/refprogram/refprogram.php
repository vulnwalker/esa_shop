<?php

class refprogramObj  extends DaftarObj2{	
	var $Prefix = 'refprogram';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_program'; //daftar
	var $TblName_Hapus = 'ref_program';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('bk','ck','dk','p','q');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Ref Program.xls';
	var $Cetak_Judul = 'Daftar Program/Kegiatan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'refprogramForm';
	var $kd_urusan=''; 	
			
	function setTitle(){
		return 'Daftar Program/Kegiatan';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	function setMenuView(){
		return "";
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
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	  
	 foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 } 
	  
	  if($fmST == 0){ 
	   if(empty($cmbProgram))$err = "Program Belum Di Pilih";
	   if(empty($nama_kegiatan))$err = "Nama Kegiatan Belum Di Isi";
	   if(empty($fmbk) || $fmbk == "0"){
	   	  $fmck = "0";
		  $fmdk = "0";	
	   }else{
	   	  if(empty($fmdk) || $fmdk == 'NULL'){
		  	$fmdk = "0";
		  }
	   }
	   
	   $data = array("bk" => $fmbk,
	   				 "ck" => $fmck,
					 "dk" => $fmdk,
					 "p" => $cmbProgram,
					 "nama" => $nama_kegiatan,
					 "q" => $kegiatan,
					 "kategori" => $kategori
					 );
	  $query = VulnWalkerInsert("ref_program",$data);
	  mysql_query($query);
	  $content = array("query" => $query);
	   
	  	
			
	  }elseif($fmST == 1){
										

	  }
					
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

		$ref_pilihprogram = $_REQUEST['id'];
		$kode_program = explode(' ',$ref_pilihprogram);
		$bk=$kode_program[0];	
		$ck=$kode_program[1];
		$dk=$kode_program[2];	
		$p=$kode_program[3];
		$q=$kode_program[4];
		if($q==0 && $p<>0) $err='Pilih Kode Kegiatan yang tidak sama dengan 0 !';
		if($err == ''&& $q==0) $err='Kegiatan belum dipilih!';
		if ($err=='' ){
			
		
			//query ambil data ref_program
			$get = mysql_fetch_array( mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='$dk' and p='$p' and q=0"));
			if($Main->VERSI_NAME=='PANDEGLANG'){
				$kode_program=$get['bk'].'.'.$get['ck'].'.'.$get['p'];
			}else{
				$kode_program=$get['bk'].'.'.$get['ck'].'.'.$get['dk'].'.'.$get['p'];	
			}
			$nm_program = $get['nama'];
			
			$get = mysql_fetch_array( mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='$dk' and p='$p' and q='$q'"));
			if($Main->VERSI_NAME=='PANDEGLANG'){
				$kode_kegiatan=$get['bk'].'.'.$get['ck'].'.'.$get['p'].'.'.$get['q'];
			}else{
				$kode_kegiatan=$get['bk'].'.'.$get['ck'].'.'.$get['dk'].'.'.$get['p'].'.'.$get['q'];
			}
			$nm_kegiatan = $get['nama'];
			
			$content = array('kode_program'=>$kode_program, 'nama_program'=>$nm_program, 
				'kode_kegiatan'=>$kode_kegiatan, 'nama_kegiatan'=>$nm_kegiatan,
				'bk'=>$bk, 'ck'=>$ck, 'dk'=>$dk, 'p'=>$p, 'q'=>$q, );	
		}
		break;
	   }
	   
	   case 'programBaru' : {	
				$fm = $this->programBaru($dt);				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];
				break;
	   }
       case 'saveProgram' : {	
		     foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 }
			 if($fmURUSAN == 0){
			 

						$fmDINAS = "0";
						$fmBIDANG = "0";
			 	$getlastProgram = "select max(p) as lastProgram from ref_program where bk = '$fmURUSAN' and ck = '0' and dk ='0' and q = '00'  ";
				$geMaxProgram = mysql_fetch_array(mysql_query($getlastProgram));
				$id = $geMaxProgram['lastProgram'] + 1;
				$data = array("bk" => $fmURUSAN,
							  "ck" => '0',
							  "dk" => '0',
							  "p" => $id,
							  "q" => '00',
							  "nama" => $namaProgram	
								 );
			 }else{
			 	if(empty($fmDINAS)){
					$fmDINAS = "0";
				}			 
			 	$getlastProgram = "select max(p) as lastProgram from ref_program where bk = '$fmURUSAN' and ck = '$fmBIDANG' and dk ='$fmDINAS' and q = '00'  ";
				$geMaxProgram = mysql_fetch_array(mysql_query($getlastProgram));
				$id = $geMaxProgram['lastProgram'] + 1;
				$data = array("bk" => $fmURUSAN,
							  "ck" => $fmBIDANG,
							  "dk" => $fmDINAS,
							  "p" => $id,
							  "q" => '00',
							  "nama" => $namaProgram	
								 );
			 }
			 $query = VulnWalkerInsert("ref_program", $data);
			 	mysql_query($query);
			$codeAndNameProgram = "select p, concat(p, '. ', nama) as vnama from ref_program where bk = '$fmURUSAN' and ck = '$fmBIDANG' and dk ='$fmDINAS' and q='00'";
			$cmbProgram  = cmbQuery('cmbProgram', $selectedProgram, $codeAndNameProgram,''.$cmbRo.'onChange=\''.$this->Prefix.'.comboForm()\'','-- Pilih Program --');
		
			 
			 $content= array("query" => $query , "cmbProgram" => $cmbProgram, "query2" => $codeAndNameProgram) ;
			 
			 
				break;
	   }
	    case 'comboForm': {
			
			 foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 }
			 if($fmURUSAN == 0){
			 	$getLastKegiatan  = "select max(q) as lastKegiatan from ref_program  where bk = '$fmURUSAN' and ck ='0' and dk='0' and p='$p'";
			    $getMaxKegiatan = mysql_fetch_array(mysql_query($getLastKegiatan));
				$id = $getMaxKegiatan['lastKegiatan'] + 1;
			
 			 }else{
				 if(empty($fmDINAS)){
						$fmDINAS = "0";
					}
			 	$getLastKegiatan  = "select max(q) as lastKegiatan from ref_program  where bk = '$fmURUSAN' and ck ='$fmBIDANG' and dk='$fmDINAS' and p='$p'";
			    $getMaxKegiatan = mysql_fetch_array(mysql_query($getLastKegiatan));
				$id = $getMaxKegiatan['lastKegiatan'] + 1;
			 }

			  $content = array("query" => $getLastKegiatan , "IDKEGIATAN" => $id);
			break;
		}
					
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   case 'saveEdit':{
		     foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 }
			 $data = array("nama" => $nama,
			 			   "kategori" => $kategori);
			 $query = VulnWalkerUpdate("ref_program",$data," concat(bk,' ',ck,' ',dk,' ',p,' ',q) = '$refprogram_cb[0]' ");
			 mysql_query($query);
			 $content = $query;
			 
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
			 "<script type='text/javascript' src='js/master/refprogram/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".		
			$scriptload;
	}
	
	function programBaru($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	
	 $form_name = $this->Prefix.'_formProgram';				
	 $this->form_width = 400;
	 $this->form_height = 60;
	 $this->form_caption = 'PROGRAM BARU';
	 
	 	
	 //items ----------------------
	  $this->form_fields = array(
			'namaProgram' => array( 
						'label'=>'NAMA PROGRAM',
						'labelWidth'=>100, 
						'value'=>"",
						'type' => 'text'
						 ),					
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SaveProgram()' title='Simpan' >   ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseProgram()' >";
							
		$form = $this->genForm();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_validasi = explode(' ',$id);
		$bk=$kode_validasi[0];	
		$ck=$kode_validasi[1];
		$dk=$kode_validasi[2];	
		$p=$kode_validasi[3];
		$q=$kode_validasi[4];
		
		
		$quricoy="select count(*) as cnt from ref_program where bk='$bk' and ck='$ck' and dk<>'00' and p<>'00' and q<>'00'";
		$dt3 = mysql_fetch_array(mysql_query($quricoy));
		$korong = $dt3 ['cnt'];
		
		if($korong>0){
		
		$korong;
		$errmsg = "ada kode barang tidak bisa di edit/hapus, karena masih ada rinciannya !";
		}
		
		if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where bk='$bk' and ck='$ck' and dk='$dk' and p='$p' and q='$q' ")
				) >0 )
				
			{ $errmsg = "GAGAL HAPUS, Kode Barang Sudah Ada Di Buku Induk !!! ";}
			
				//$errmsg = "select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$j' and i='$j' ";
			
		return $errmsg;
		
}
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$dt['readonly']='';
		$fmURUSAN = $_REQUEST['fmURUSAN'];
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmDINAS = $_REQUEST['fmDINAS'];
		$dt['bk']=$fmURUSAN;
		$dt['ck']=$fmBIDANG;
		$dt['dk']=$fmDINAS==''?'NULL':$fmDINAS;;
		$dt['p']='1';
		$dt['q']='0';
		
		$fm = $this->setForm($dt);		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$bk=$kode[0];
		$ck=$kode[1]; 
		$dk=$kode[2];
		$p=$kode[3]; 
		$q=$kode[4];
		 
		//query ambil data ref_program
		$aqry = "select * from ref_program where concat(bk,' ',ck,' ',dk,' ',p,' ',q)='$this->form_idplh'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$fm = $this->formEdit($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 1200;
	 $this->form_height = 400;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';
	  }
	  	foreach ($dt as $key => $value) { 
			  $$key = $value; 
		} 
		$get1=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='0' "));
		$urusan = $get1['nm_urusan'];
		$get2=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='0' "));
		$bidang = $get2['nm_urusan'];
		$get3=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='".$dt['dk']."' "));
		$dinas = $get3['dk']==0?'-':$get3['nm_urusan'];
		
		$codeAndNameProgram = "select p, concat(p, '. ', nama) as vnama from ref_program where bk = '$bk' and ck = '$ck' and dk ='$dk' and q='00'";
		$cmbProgram  = cmbQuery('cmbProgram', $selectedProgram, $codeAndNameProgram,''.$cmbRo.'onChange=\''.$this->Prefix.'.comboForm()\'','-- Pilih Program --');
		
		$arrayKategori = array(
							

							array('1','PENGADAAN'),
							array('2','PEMELIHARAAN')
						 
						 );
						 
		$cmbKategori = 	cmbArray('kategori',$kategori,$arrayKategori,'PENGADAAN DAN PEMELIHARAAN','');
		
		if ($dt['stpakai']==1)	$readonly="readonly=''";
		
       //items ----------------------
		  $this->form_fields = array(
			'urusan' => array( 'label'=>'Urusan', 
								'labelWidth'=>150,
								'value'=>$urusan, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'bidang' => array( 'label'=>'Bidang', 
								'value'=>$bidang, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'dinas' => array( 'label'=>'SKPD', 
								'value'=>$dinas, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'kode_program' => array( 
								'label'=>'Kode Program',
								'labelWidth'=>100, 
								'value'=>$cmbProgram." <button type='button' onclick='refprogram.programBaru()' > Baru </button>"
									 ),
			'kode_kegiatan' => array( 
								'label'=>'Kode Kegiatan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' id ='kegiatan' name='kegiatan' style='width:40px;' readonly>"
									 ),
			'nama_kegiatan' => array( 
								'label'=>'Nama',
								'labelWidth'=>100,
								'value'=>$dt['nama'], 
								'type'=>'text',
								'id'=>'nama_program',
								'param'=>"style='width:250ppx;text-transform: uppercase;' size=50ppx"
									 ),
			'kategori' => array( 
								'label'=>'Kategori',
								'labelWidth'=>100,
								'value'=>$cmbKategori, 
									 ),	
								 
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='fmbk' name='fmbk' value='".$dt['bk']."'> ".
			"<input type=hidden id='fmck' name='fmck' value='".$dt['ck']."'> ".
			"<input type=hidden id='fmdk' name='fmdk' value='".$dt['dk']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function formEdit($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_formEdit';				
	 $this->form_width = 1200;
	 $this->form_height = 200;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';
	  }
	  	foreach ($dt as $key => $value) { 
			  $$key = $value; 
		}
		$ROU = "readonly";
		$ROB = "readonly"; 
		$ROD = "readonly";
		$ROP = "readonly";
		$ROK = "readonly";
		$get1=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='0' "));
		$urusan = $get1['nm_urusan'];
		$get2=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='0' "));
		$bidang = $get2['ck']==0?'-':$get2['nm_urusan'];
		$get3=mysql_fetch_array(mysql_query("select * from ref_urusan where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='".$dt['dk']."' "));
		$dinas = $get3['dk']==0?'-':$get3['nm_urusan'];
		$get4=mysql_fetch_array(mysql_query("select * from ref_program where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='".$dt['dk']."' and p='".$dt['p']."' and q='00'"));
		$program = $get4['nama'];
		if($q == '0'){
			
		}else{
			$get5=mysql_fetch_array(mysql_query("select * from ref_program where bk='".$dt['bk']."' and ck='".$dt['ck']."' and dk='".$dt['dk']."' and p='".$dt['p']."' and  q='".$dt['q']."'"));
			$kegiatan = $get5['nama'];
		}
		
		
		$codeAndNameProgram = "select p, concat(p, '. ', nama) as vnama from ref_program where bk = '$bk' and ck = '$ck' and dk ='$dk' and q='00'";
		$cmbProgram  = cmbQuery('cmbProgram', $selectedProgram, $codeAndNameProgram,''.$cmbRo.'onChange=\''.$this->Prefix.'.comboForm()\'','-- Pilih Program --');
		if($q != '0'){
			$ROK = "";
		}else{
			$ROP = "";
		}			
		$arrayKategori = array(
							

							array('1','PENGADAAN'),
							array('2','PEMELIHARAAN')
						 
						 );
		
	
		$kategori = $dt['kategori'];				 
		$cmbKategori = 	cmbArray('kategori',$kategori,$arrayKategori,'PENGADAAN DAN PEMELIHARAAN','');
		if ($dt['stpakai']==1)	$readonly="readonly=''";
		
       //items ----------------------
		  $this->form_fields = array(
			'urusan' => array( 'label'=>'Urusan', 
								'labelWidth'=>150,
								'value'=>$urusan, 
								'type'=>'text',
								'param' => "style='width:900px;' $ROU"
							),
			'bidang' => array( 'label'=>'Bidang', 
								'value'=>$bidang, 
								'type'=>'text',
								'param' => "style='width:900px;' $ROB"
							),
			'dinas' => array( 'label'=>'Dinas', 
								'type'=>'text',
								'value'=> $dinas,
								'param' => "style='width:900px;' $ROD"
							),
			'program' => array( 'label'=>'Program', 
								'value'=>$program, 
								'type'=>'text',
								'param' => "style='width:900px;' $ROP"
							),
			'kegiatan' => array( 'label'=>'Kegiatan', 
								'value'=>$kegiatan, 
								'type'=>'text',
								'param' => "style='width:900px;' $ROK"
							),
			'kategori' => array( 
								'label'=>'Kategori',
								'labelWidth'=>100,
								'value'=>$cmbKategori, 
									 ),	
								 
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='fmbk' name='fmbk' value='".$dt['bk']."'> ".
			"<input type=hidden id='fmck' name='fmck' value='".$dt['ck']."'> ".
			"<input type=hidden id='fmdk' name='fmdk' value='".$dt['dk']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SaveEdit()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseEdit()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		
		
			$FormContent = $this->genDaftarInitial();
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1570,
						695,
						'Pilih Program',
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
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function genDaftarInitial( $height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
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
		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 			 
	 $headerTable =
	 "<thead>
	  <tr>
  	   <th class='th01' width='20' rowspan='2'>No.</th>
  	   $Checkbox		
       <th class='th01' align='center' width='100'>KODE</th>
	   <th class='th01' align='center' width='900'>NAMA</th>   
	   <th class='th01' align='center' width='300'>KATEGORI</th> 
	   <th class='th01' align='center' width='100'>STATUS</th> 
	 
	   </tr>
	  </thead>";
	
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 foreach ($isi as $key => $value) { 
		  $$key = $value; 
	 }
	 $kode = $bk.".".genNumber($ck).".".genNumber($dk).".".genNUmber($p).".".genNumber($q);
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"  ',$kode);
 	 $Koloms[] = array('align="left" ',$nama);
	 if($kategori == 0 ){
	 	$kategori = "PENGADAAN DAN PEMELIHARAAN";
	 }elseif($kategori == 1){
	 	$kategori = "PENGADAAN";
	 }elseif($kategori == 2){
	 	$kategori = "PEMELIHARAAN";
	 }
	 $Koloms[] = array('align="left" ',$kategori);
	 if($stpakai =='1'){
	 	$stpakai = "TIDAK AKTIF";
	 }else{
	 	$stpakai = "AKTIF";
	 }
	 $Koloms[] = array('align="center" ',$stpakai);
	 /*$Koloms[] = array('align="left" ',$pagu);	*/ 	 	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;	 
	
	$fmURUSAN = cekPOST('fmURUSAN');
	$fmBIDANG = cekPOST('fmBIDANG');
	$fmDINAS = cekPOST('fmDINAS');
	$fmPROGRAM = cekPOST('fmPROGRAM');
	$fmKEGIATAN = cekPOST('fmKEGIATAN');
	$fmKODE = cekPOST('fmKODE');
	$fmNMURUSAN = cekPOST('fmNMURUSAN');		
		
		
	$TampilOpt = 
			//"<tr><td>".	
			"<div class='FilterBar'>".			
			"<table style='width:100%'>
			<tr>
			<td style='width:120px'>URUSAN</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmURUSAN",$fmURUSAN,"select bk,nm_urusan from ref_urusan where  ck='0' ","onChange=\"$this->Prefix.Refesh()\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>BIDANG</td><td>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select ck,nm_urusan from ref_urusan where bk='$fmURUSAN' and ck<>'0' and dk='0' ","onChange=\"$this->Prefix.Refesh()\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SKPD</td><td>:</td>
			<td>".
			cmbQuery1("fmDINAS",$fmDINAS,"select dk,nm_urusan from ref_urusan where bk='$fmURUSAN' and ck ='$fmBIDANG' and dk <> '0'","onChange=\"$this->Prefix.Refesh()\"",'Pilih','').
			"</td>
			</tr>
			
			<tr>
			<td style='width:120px'>PROGRAM</td><td style='width:10px'>:</td>
			<td colspan='2'>".
			cmbQuery1("fmPROGRAM",$fmPROGRAM,"select p,nama from ref_program where bk='$fmURUSAN' and ck ='$fmBIDANG' and dk='$fmDINAS' and p<>'0' and q='0'","onChange=\"$this->Prefix.Refesh()\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KEGIATAN</td><td>:</td>
			<td>".
			cmbQuery1("fmKEGIATAN",$fmKEGIATAN,"select q,nama from ref_program where bk='$fmURUSAN' and ck ='$fmBIDANG' and dk='$fmDINAS' and p='$fmPROGRAM' and q<>'0' ","onChange=\"$this->Prefix.Refesh()\"",'Pilih','').
			"</td><td>
			</td>
			</tr>
			</table>".
			"</div>";		
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmURUSAN = cekPOST('fmURUSAN');
		$fmBIDANG = cekPOST('fmBIDANG');
		$fmDINAS = cekPOST('fmDINAS');
		$fmPROGRAM = cekPOST('fmPROGRAM');
		$fmKEGIATAN = cekPOST('fmKEGIATAN');
		$fmKODE = cekPOST('fmKODE');
		$fmNMURUSAN = cekPOST('fmNMURUSAN');
		
		if(empty($fmURUSAN)) {
			$fmBIDANG = '0';
			$fmDINAS='0';
		}
		if(empty($fmBIDANG)) {
			$fmDINAS='0';
		}
		
		if(empty($fmURUSAN) && empty($fmBIDANG) && empty($fmDINAS))
		{
			
		}
		elseif(!empty($fmURUSAN) && empty($fmBIDANG) && empty($fmDINAS))
		{
			$arrKondisi[]= "bk =$fmURUSAN";
		}
		elseif(!empty($fmURUSAN) && !empty($fmBIDANG) && empty($fmDINAS))
		{
			$arrKondisi[]= "bk =$fmURUSAN and ck=$fmBIDANG";
		}
		elseif(!empty($fmURUSAN) && !empty($fmBIDANG) && !empty($fmDINAS))
		{
			$arrKondisi[]= "bk =$fmURUSAN and ck=$fmBIDANG and dk=$fmDINAS";		
		}
		
		if(empty($fmPROGRAM) && empty($fmKEGIATAN))
		{
			
		}
		elseif(!empty($fmPROGRAM) && empty($fmKEGIATAN))
		{
			$arrKondisi[]= "p =$fmPROGRAM";
		}
		elseif(!empty($fmPROGRAM) && !empty($fmKEGIATAN))
		{
			$arrKondisi[]= "p =$fmPROGRAM and q=$fmKEGIATAN";
		}
		
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(bk,ck,dk) like '%".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmNMURUSAN'])) $arrKondisi[] = " nm_urusan like '%".$_POST['fmNMURUSAN']."%'";

 			
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		

			$Order= join(',',$arrOrders);	
			$OrderDefault = ' Order By bk,ck,dk,p,q ';// Order By no_terima desc ';
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
$refprogram = new refprogramObj();

?>