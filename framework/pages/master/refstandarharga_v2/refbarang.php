<?php

class popupBarang_v2Obj  extends DaftarObj2{	
	var $Prefix = 'popupBarang_v2';
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
	var $PageTitle = '';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = '';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'popupBarang_v2Form'; 	
			
	function setTitle(){
		return '';
	}
	function setMenuEdit(){		
		return
			"";
	}
	  function setTopBar(){
	   	return '';
	}
	function setMenuView(){
		return "";
	}
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		return
			"";	
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
		case 'Hapus':{				
			$fm = $this->Hapus_data();				
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

		$ref_pilihbarang = $_REQUEST['id'];
		$kode_barang = explode('.',$ref_pilihbarang);	
		$f=$kode_barang[0];	
		$g=$kode_barang[1];
		$h=$kode_barang[2];	
		$i=$kode_barang[3];
		$j=$kode_barang[4];
		
		//query ambil data ref_program
		$get = mysql_fetch_array( mysql_query("select * from ref_barang  where  f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
		$kode_barang= $get['f'].'.'.$get['g'].'.'.$get['h'].'.'.$get['i'].'.'.$get['j'];
		
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
			$tmax = mysql_fetch_array(mysql_query($kueri1));
			$kueri="select * from ref_jurnal 
					where thn_akun = '".$tmax['thn_akun']."' 
					and ka='".$get['m1']."' and kb='".$get['m2']."' 
					and kc='".$get['m3']."' and kd='".$get['m4']."'
					and ke='".$get['m5']."' and kf='".$get['m6']."'"; //echo "$kueri";
			$row=mysql_fetch_array(mysql_query($kueri));
						
			$kode_account =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
			


			
						
		$content = array('kodeBarang'=>$kode_barang, 'namaBarang'=>$get['nm_barang'],'satuanBarang'=>$get['satuan'] , 'kode_account'=>$kode_account, 'nama_account'=>$row['nm_account'], 'tahun_account'=>$row['thn_akun']  );	
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
						1200,
						500,
						'Pilih Barang',
						'',
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
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "
			 <script type='text/javascript' src='js/master/ref_template/jquery.js' language='JavaScript' ></script>
			  <script type='text/javascript' src='js/master/refstandarharga_v2/refbarang.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_barang = explode(' ',$id);
		$f1=$kode_barang[0];	
		$f2=$kode_barang[1];
		$f=$kode_barang[2];		
		$g=$kode_barang[3];
		$h=$kode_barang[4];	
		$i=$kode_barang[5];
		$j=$kode_barang[6];
		
		
		if($j =='000'){
			$errmsg = "Data tidak dapat di hapus";
		}
		
/*		if($korong>0){
		
		$korong;
		$errmsg = "ada kode barang tidak bisa di edit/hapus, karena masih ada rinciannya !";
		}*/
/*		//&& 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ")
				) >0*/
/*		if($errmsg=='' )
				
			{ $errmsg = "GAGAL HAPUS, Kode Barang Sudah Ada Di Buku Induk !!! ";}*/
			

			
		return $errmsg;
		
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
		$f1=$kode[0];
		$f2=$kode[1];
		$f=$kode[2];
		$g=$kode[3]; 
		$h=$kode[4]; 
		$i=$kode[5]; 
		$j=$kode[6]; 
		$bulan=date('Y-m-')."1";
		//query ambil data ref_barang
		$aqry = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j)='".$f1.'.'.$f2.'.'.$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
			
		//$dt['readonly']='readonly';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 900;
	 $this->form_height = 300;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
		$readonly='';
		$chmod644 = "";
		$f1 = $_REQUEST['cmbAkun'];
		$f2 = $_REQUEST['cmbKelompok'];
		$f = $_REQUEST['cmbJenis'];
		$g = $_REQUEST['cmbObyek'];
		$h = $_REQUEST['cmbRincianObyek'];
		$i = $_REQUEST['cmbSubRincianObyek'];
		$j = $_REQUEST['cmbSubSubRincianObyek'];
	  }else{
		$this->form_caption = 'EDIT';
		$chmod644 ="readonly";
		$f1 = $dt['f1'];
		$f2 = $dt['f2'];
		$f = $dt['f'];
		$g = $dt['g'];
		$h = $dt['h'];
		$i = $dt['i'];
		$j = $dt['j'];
		 $aqry_at = "select * from ref_jurnal where ka='".$dt['ka']."' and kb='".$dt['kb']."' and kc='".$dt['kc']."' and kd='".$dt['kd']."' and ke='".$dt['ke']."' and kf='".$dt['kf']."' ";
	 $na_at=mysql_fetch_array(mysql_query($aqry_at));
	 
	 $kode_account_at=$dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'].'.'.$dt['kf'];	 
	 $aqry_bm = "select * from ref_jurnal where ka='".$dt['m1']."' and kb='".$dt['m2']."' and kc='".$dt['m3']."' and kd='".$dt['m4']."' and ke='".$dt['m5']."' and kf='".$dt['m6']."' ";
	 $na_bm=mysql_fetch_array(mysql_query($aqry_bm));
	 $kode_account_bm=$dt['m1'].'.'.$dt['m2'].'.'.$dt['m3'].'.'.$dt['m4'].'.'.$dt['m5'].'.'.$dt['m6'];
	 
	 $aqry_ap = "select * from ref_jurnal where ka='".$dt['l1']."' and kb='".$dt['l2']."' and kc='".$dt['l3']."' and kd='".$dt['l4']."' and ke='".$dt['l5']."' and kf='".$dt['l6']."' ";
	 $na_ap=mysql_fetch_array(mysql_query($aqry_ap));
	 $kode_account_ap=$dt['l1'].'.'.$dt['l2'].'.'.$dt['l3'].'.'.$dt['l4'].'.'.$dt['l5'].'.'.$dt['l6'];
	 
	 //vw
	 $query_rek_bm = "select * from ref_rekening where k='".$dt['k11']."' and l='".$dt['l11']."' and m='".$dt['m11']."' and n='".$dt['n11']."' and o='".$dt['o11']."' ";
	 $na_rek_bm = mysql_fetch_array(mysql_query($query_rek_bm));
	 $kode_rek_bm=$dt['k11'].'.'.$dt['l11'].'.'.$dt['m11'].'.'.$dt['n11'].'.'.$dt['o11'];
	 
	 $query_rek_bp = "select * from ref_rekening where k='".$dt['k12']."' and l='".$dt['l12']."' and m='".$dt['m12']."' and n='".$dt['n12']."' and o='".$dt['o12']."' ";
	 $na_rek_bp = mysql_fetch_array(mysql_query($query_rek_bp));
	 $kode_rek_bp=$dt['k12'].'.'.$dt['l12'].'.'.$dt['m12'].'.'.$dt['n12'].'.'.$dt['o12'];
	 
	 $query_akun_pemeliharaan = "select * from ref_jurnal where ka='".$dt['o1']."' and kb='".$dt['o2']."' and kc='".$dt['o3']."' and kd='".$dt['o4']."' and ke='".$dt['o5']."' and kf='".$dt['o6']."' ";
	 $na_akun_pemeliharaan=mysql_fetch_array(mysql_query($query_akun_pemeliharaan));
	 $kode_account_pemeliharaan=$dt['o1'].'.'.$dt['o2'].'.'.$dt['o3'].'.'.$dt['o4'].'.'.$dt['o5'].'.'.$dt['o6'];
	 
	 $query_akun_beban_penyusutan = "select * from ref_jurnal where ka='".$dt['n1']."' and kb='".$dt['n2']."' and kc='".$dt['n3']."' and kd='".$dt['n4']."' and ke='".$dt['n5']."' and kf='".$dt['n6']."' ";
	 $na_akun_beban_penyusutan=mysql_fetch_array(mysql_query($query_akun_beban_penyusutan));
	 $kode_account_beban_penyusutan=$dt['n1'].'.'.$dt['n2'].'.'.$dt['n3'].'.'.$dt['n4'].'.'.$dt['n5'].'.'.$dt['n6'];
		//$readonly='readonly';
	  }
	  				
 	    $username=$_REQUEST['username'];
		
		$lengthKodeBrg =  12 + $Main->KODEBARANGJ_DIGIT ;
		$sampleKodeBrg = "*1.0.0.00.00.00.000" ;
		
		//query ref_batal
		$queryKB = "SELECT f,nama_barang FROM ref_barang_persediaan where f !=0 and g=0";
		
/*		$dt['residu'] = $dt['residu'] == '' ?0: $dt['residu'];
		$dt['masa_manfaat'] = $dt['masa_manfaat'] == '' ?0: $dt['masa_manfaat'];*/
		
		
       //items ----------------------
		  $this->form_fields = array(
/*			'' => array( 
								'label'=>'Kode barang',
								'labelWidth'=>100, 
								'value'=>'<b>BIDANG/KELOMPOK/SUB KELOMPOK/SUB SUB KELOMPOK/BARANG</b>', 
								'type'=>'merge',
							 ),	*/

			'kode_barang' => array( 
								'label'=>'KODE BARANG',
								'labelWidth'=>350, 
								'value'=>"<input type='text' id='f1' name='f1' style='width:20px;' maxlength='1' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$f1' $chmod644> &nbsp <input type='text' id='f2' name='f2' style='width:20px;' maxlength='1' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$f2' $chmod644> &nbsp <input type='text' id='f' name='f' style='width:25px;' maxlength='1' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$f' $chmod644> &nbsp <input type='text' id='g' name='g' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$g' $chmod644> &nbsp <input type='text' id='h' name='h' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$h' $chmod644> &nbsp <input type='text' id='i' name='i' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$i' $chmod644> &nbsp <input type='text' id='j' name='j' style='width:30px;' maxlength='3' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$j' $chmod644> &nbsp  
									<font color=red>$sampleKodeBrg</font>" 
									 ),	
									 
			'nama_barang' => array( 
								'label'=>'NAMA BARANG',
								'labelWidth'=>350, 
								'value'=>$dt['nm_barang'], 
								'type'=>'text',
								'id'=>'nama_barang',
								'param'=>"style='width:250ppx;' size=50px"
									 ),	
			'satuan' => array( 
								'label'=>'SATUAN',
								'labelWidth'=>350, 
								'value'=>$dt['satuan'], 
								'type'=>'text',
								'id'=>'satuan',
								'param'=>"style='width:250ppx;' size=50px"
									 ),	
									 
			'krbmp21' => array( 
								'label'=>'KODE REKENING BELANJA MODAL PERMEN 21',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='krbmp21' value='".$kode_rek_bm."' size='10px' id='krbmp21' readonly>&nbsp
										  <input type='text' name='nbmp21' value='".$na_rek_bm['nm_rekening']."' size='50px' id='nrbmp21' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikrbmp21()' title='Cari Jurnal Aset Tetap' >" 
									 ),
			'krbpp21' => array( 
								'label'=>'KODE REKENING BELANJA PEMELIHARAAN PERMEN 21',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='krbpp21' value='".$kode_rek_bp."' size='10px' id='krbpp21' readonly>&nbsp
										  <input type='text' name='nrbpp21' value='".$na_rek_bp['nm_rekening']."' size='50px' id='nrbpp21' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikrbpp21()' title='Cari Jurnal Aset Tetap' >" 
									 ),						 
			'kabmp64' => array( 
								'label'=>'<font color=red>KODE AKUN BELANJA MODAL PERMEN 64</font>',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='kabmp64' value='".$kode_account_bm."' size='10px' id='kabmp64' readonly>&nbsp
										  <input type='text' name='nabmp64' value='".$na_bm['nm_account']."' size='50px' id='nabmp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabmp64()' title='Cari Jurnal Aset Tetap' >" 
									 ),
			
			'kabpp64j' => array( 
								'label'=>'KODE AKUN BELANJA PEMELIHARAAN PERMEN 64 ',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='kabpp64j' value='".$kode_account_pemeliharaan."' size='10px' id='kabpp64j' readonly>&nbsp
										  <input type='text' name='nabpp64j' value='".$na_akun_pemeliharaan['nm_account']."' size='50px' id='nabpp64j' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabpp64j()' title='Cari Jurnal Belanja Modal Pemeliharaan' >" 
									 ),
			'kaap64' => array( 
								'label'=>'<font color=red>KODE AKUN ASET PERMEN 64</font>',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='kaap64' value='".$kode_account_at."' size='10px' id='kaap64' readonly>&nbsp
										  <input type='text' name='naap64' value='".$na_at['nm_account']."' size='50px' id='naap64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikaap64()' title='Cari Aset Tetap' >" 
									 ),
			'kaapp64' => array( 
								'label'=>'<font color=red>KODE AKUN AKUMULASI PENYUSUTAN PERMEN 64</font>',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='kaapp64' value='".$kode_account_ap."' size='10px' id='kaapp64' readonly>&nbsp
										  <input type='text' name='naapp64' value='".$na_ap['nm_account']."' size='50px' id='naapp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikaapp64()' title='Cari Akun Akumulasi Penyusutan' >" 
									 ),
			'kabpp64' => array( 
								'label'=>'KODE AKUN BEBAN PENYUSUTAN PERMEN 64',
								'labelWidth'=>350, 
								'value'=>"<input type='text' name='kabpp64' value='".$kode_account_beban_penyusutan."' size='10px' id='kabpp64' readonly>&nbsp
										  <input type='text' name='nabpp64' value='".$na_akun_beban_penyusutan['nm_account']."' size='50px' id='nabpp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabpp64()' title='Cari Jurnal Aset Tetap' >" 
									 ),											 								 										 
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' > &nbsp &nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
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
	   <th class='th01' align='left' width='100'>KODE BARANG</th>
	   <th class='th01' align='left' width='800'>NAMA BARANG</th>
	   <th class='th01' align='left' width='100'>SATUAN</th>

   	  

	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	/*	   <th class='th01' align='left' width='100'>MASA MANFAAT (TAHUN)</th>
	   <th class='th01' align='left' width='100'>RESIDU (%)</th>	 */  
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 $aqry_at = "select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' and kf='".$isi['kf']."' ";
	 $na_at=mysql_fetch_array(mysql_query($aqry_at));
	 
	 $kode_account_at=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'].'.'.$isi['kf'];	 
	 $aqry_bm = "select * from ref_jurnal where ka='".$isi['m1']."' and kb='".$isi['m2']."' and kc='".$isi['m3']."' and kd='".$isi['m4']."' and ke='".$isi['m5']."' and kf='".$isi['m6']."' ";
	 $na_bm=mysql_fetch_array(mysql_query($aqry_bm));
	 $kode_account_bm=$isi['m1'].'.'.$isi['m2'].'.'.$isi['m3'].'.'.$isi['m4'].'.'.$isi['m5'].'.'.$isi['m6'];
	 
	 $aqry_ap = "select * from ref_jurnal where ka='".$isi['l1']."' and kb='".$isi['l2']."' and kc='".$isi['l3']."' and kd='".$isi['l4']."' and ke='".$isi['l5']."' and kf='".$isi['l6']."' ";
	 $na_ap=mysql_fetch_array(mysql_query($aqry_ap));
	 $kode_account_ap=$isi['l1'].'.'.$isi['l2'].'.'.$isi['l3'].'.'.$isi['l4'].'.'.$isi['l5'].'.'.$isi['l6'];
	 
	 //vw
	 $query_rek_bm = "select * from ref_rekening where k='".$isi['k11']."' and l='".$isi['l11']."' and m='".$isi['m11']."' and n='".$isi['n11']."' and o='".$isi['o11']."' ";
	 $na_rek_bm = mysql_fetch_array(mysql_query($query_rek_bm));
	 $kode_rek_bm=$isi['k11'].'.'.$isi['l11'].'.'.$isi['m11'].'.'.$isi['n11'].'.'.$isi['o11'];
	 
	 $query_rek_bp = "select * from ref_rekening where k='".$isi['k12']."' and l='".$isi['l12']."' and m='".$isi['m12']."' and n='".$isi['n12']."' and o='".$isi['o12']."' ";
	 $na_rek_bp = mysql_fetch_array(mysql_query($query_rek_bp));
	 $kode_rek_bp=$isi['k12'].'.'.$isi['l12'].'.'.$isi['m12'].'.'.$isi['n12'].'.'.$isi['o12'];
	 
	 $query_akun_pemeliharaan = "select * from ref_jurnal where ka='".$isi['o1']."' and kb='".$isi['o2']."' and kc='".$isi['o3']."' and kd='".$isi['o4']."' and ke='".$isi['o5']."' and kf='".$isi['o6']."' ";
	 $na_akun_pemeliharaan=mysql_fetch_array(mysql_query($query_akun_pemeliharaan));
	 $kode_account_pemeliharaan=$isi['o1'].'.'.$isi['o2'].'.'.$isi['o3'].'.'.$isi['o4'].'.'.$isi['o5'].'.'.$isi['o6'];
	 
	 $query_akun_beban_penyusutan = "select * from ref_jurnal where ka='".$isi['n1']."' and kb='".$isi['n2']."' and kc='".$isi['n3']."' and kd='".$isi['n4']."' and ke='".$isi['n5']."' and kf='".$isi['n6']."' ";
	 $na_akun_beban_penyusutan=mysql_fetch_array(mysql_query($query_akun_beban_penyusutan));
	 $kode_account_beban_penyusutan=$isi['n1'].'.'.$isi['n2'].'.'.$isi['n3'].'.'.$isi['n4'].'.'.$isi['n5'].'.'.$isi['n6'];
	 
	 
	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if($isi['j'] == '000'){
	 	$Koloms[] = array('align="left" width="100" style="font-weight : bold;" ',$kode_barang);
	 }else{
	 	$Koloms[] = array('align="left" width="100" style="border-left-style: none;"',$kode_barang);
	 }
	 
 	 $Koloms[] = array('align="left" width="200"',"<a style='cursor:pointer;' onclick = popupBarang_v2.windowSave('$kode_barang')>". $isi['nm_barang']. "</a>");
	 $Koloms[] = array('align="left" width="100"',$isi['satuan']);	 
	 	 
/* 	 $Koloms[] = array('align="left" width="200"',$isi['masa_manfaat']);
 	 $Koloms[] = array('align="left" width="200"',str_replace(".",",",$isi['residu']));	 
*/
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	$cmbAkun = '0';
	$cmbKelompok = '0';
	$cmbJenis = $_REQUEST['cmbJenis'];
	$cmbObyek = $_REQUEST['cmbObyek'];
	$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
	$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
	$cmbSubSubRincianObyek = $_REQUEST['cmbSubSubRincianObyek'];	
	$fmKODE = $_REQUEST['fmKODE'];	
	$fmBARANG = $_REQUEST['fmBARANG'];	
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
			"<div class='FilterBar'>".

			
			"<table style='width:100%'>
			<tr>
			<td style='width:170px;' >JENIS</td><td>:</td>
			<td>".
			cmbQuery1("cmbJenis",$cmbJenis,"select f as valueCmbJenis, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f != '00'  and g ='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td style='width:170px;'>OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbObyek",$cmbObyek,"select g as valueCmbObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g !='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td style='width:170px;'>RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbRincianObyek",$cmbRincianObyek,"select h as valueCmbRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h !='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			<tr>
			<td style='width:170px;'>SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubRincianObyek",$cmbSubRincianObyek,"select i as valueCmbSubRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i != '00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			<tr>
			<td style='width:170px;'>SUB-SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubSubRincianObyek",$cmbSubSubRincianObyek,"select j as valueCmbSubSubRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i='$cmbSubRincianObyek' and j != '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		$cmbAkun = $_REQUEST['cmbAkun'];
		$cmbKelompok = $_REQUEST['cmbKelompok'];
		$cmbJenis = $_REQUEST['cmbJenis'];
		$cmbObyek = $_REQUEST['cmbObyek'];
		$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
		$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
		$cmbSubSubRincianObyek = $_REQUEST['cmbSubSubRincianObyek'];	
		$fmMERK = $_REQUEST['fmMERK'];
		$fmTYPE = $_REQUEST['fmTYPE'];		
		
		switch($fmPILCARI){
			case 'selectfg': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectbarang': $arrKondisi[] = " nama_barang like '%".$fmPILCARIvalue."%'"; break;					 	
		}
		
		
		if(empty($cmbJenis)) {
			$cmbObyek='';
			$cmbRincianObyek='';
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "f =$cmbJenis";	
		}
		if(empty($cmbObyek)) {
			$cmbRincianObyek='';
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "g =$cmbObyek";	
		}
		if(empty($cmbRincianObyek)) {
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "h =$cmbRincianObyek";	
		}
		if(empty($cmbSubRincianObyek)) {
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "i =$cmbSubRincianObyek";	
		}
		if(empty($cmbSubSubRincianObyek)) {
		}else{
			$arrKondisi[]= "j =$cmbSubSubRincianObyek";	
		}
		$arrKondisi[]= "j !=000";	
			
		
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(f,g,h,i,j) like '".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANG']."%'";	

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		/*switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g,h,i,j) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}*/
			$arrOrders[] = " concat(f,g,h,i,j) ASC " ;
			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					

		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; 
		$Limit = $Mode == 3 ? '': $Limit;
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$popupBarang_v2 = new popupBarang_v2Obj();

?>