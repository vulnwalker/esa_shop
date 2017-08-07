<?php

class ref_kode_gajiObj  extends DaftarObj2{	
	var $Prefix = 'ref_kode_gaji';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_kodegaji'; //daftar
	var $TblName_Hapus = 'ref_kodegaji';
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
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ref_kode_gajiForm'; 	
			
	function setTitle(){
		return 'Daftar Barang';
	}
	
	var $Status = array(
			array('1',' Aktif'),
			array('2',' Tidak Akif'),
		);
	
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function setMenuView(){
		
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
	/*function Simpan_Validasi($id){//id -> multi id with space delimiter
		$err ='';
		$kode_barang = explode(' ',$id);
		$f=$kode_barang[0];	
		$g=$kode_barang[1];
		$h=$kode_barang[2];	
		$i=$kode_barang[3];
		$j=$kode_barang[4];
				
		$quricoy="select count(*) as cnt from ref_barang where f='$f' and g='$g' and h<>'00' and i<>'00' and j<>'000'";
		$dt3 = mysql_fetch_array(mysql_query($quricoy));
		$korong = $dt3 ['cnt'];
		
		if($korong>0){
		
		$korong;
		$err = "ada kode barang tidak bisa di edit/hapus, karena masih ada rinciannya !";
		}
		
		if($err=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ")
				) >0 )
				
			{ $err = "GAGAL SIMPAN, Kode Barang Sudah Ada Di Buku Induk !!! ";}
			
				//$errmsg = "select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$j' and i='$j' ";
			
		return $err;
		
}*/
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $kode = $_REQUEST['kode'];
	 $kode_rekening = $_REQUEST['kode_rekening'];
	 $nmgaji = $_REQUEST['nmgaji'];
	 $kode_barang = $_REQUEST['kode_barang'];
	 $nama_barang = $_REQUEST['nama_barang'];
	 $kode_account_at = $_REQUEST['kode_account_at'];
	 $kode_account_bm = $_REQUEST['kode_account_bm'];
	 $status=$_REQUEST['status'];
	 
	
	
	 
 	 	 	 	 	 	 
			
			if($fmST == 0){ //input ref_barang
			
	 $oldy=mysql_fetch_array(
	 	mysql_query(
	 		"select count(*) as cnt from ref_kodegaji where kode='$kode'"
		));
		//$cek.="select count(*) as cnt from t_penerimaan where no_ba='$no_ba'";
	 if($err=='' && $oldy['cnt']>0) $err="Kode Sudah Ada";
	if( $err=='' && $kode =='' ) $err= 'Kode Belum Di Isi !!';
	if( $err=='' && $nmgaji =='' ) $err= 'Nama Gaji Belum Di Isi !!';
	if( $err=='' && $kode_rekening =='' ) $err= 'Kode Rekening Belum Di Isi !!';
	if( $err=='' && $kode_account_at =='' ) $err= 'Kode Akun Belanja Pegawai Belum Di Isi !!';
	if( $err=='' && $kode_account_bm =='' ) $err= 'Kode Akun Beban Belanja Pegawai Belum Di Isi !!';
	if( $err=='' && $status =='' ) $err= 'Status Belum di Pilih !!';
						
				if($err==''){ 
						//memecah kode barang
						 $kode_rekening = explode('.',$kode_rekening);
						 $k=$kode_rekening[0];	
						 $l=$kode_rekening[1];
						 $m=$kode_rekening[2];	
						 $n=$kode_rekening[3];
						 $o=$kode_rekening[4];
						 
						 //memecah kode jurnal aset tetap
						 $kode_jurnal_at = explode('.',$kode_account_at);
						 $ka=$kode_jurnal_at[0];	
						 $kb=$kode_jurnal_at[1];
						 $kc=$kode_jurnal_at[2];	
						 $kd=$kode_jurnal_at[3];
						 $ke=$kode_jurnal_at[4];
						 //memecah kode jurnal belanja modal
						 $kode_jurnal_bm = explode('.',$kode_account_bm);
						 $ka2=$kode_jurnal_bm[0];	
						 $kb2=$kode_jurnal_bm[1];
						 $kc2=$kode_jurnal_bm[2];	
						 $kd2=$kode_jurnal_bm[3];
						 $ke2=$kode_jurnal_bm[4];			 	 	  
						 
						 //memecah kode jurnal akum penyusutan			 	 	  
						$aqry1 = "INSERT into ref_kodegaji (kode,nama_gaji,k,l,m,n,o,ka,kb,kc,kd,ke,ka2,kb2,kc2,kd2,ke2,status)values('$kode','$nmgaji','$k','$l','$m','$n','$o','$ka','$kb','$kc','$kd','$ke','$ka2','$kb2','$kc2','$kd2','$ke2','$status')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);						
						if($qry==FALSE)
						{ 
						//	$err="Kode Barang yang sama/Sudah ada tidak bisa di simpan";
						}else{
							
						}
														
				}
			}elseif($fmST == 1){
	if($err=='' && $oldy['cnt']>0) $err="Kode Sudah Ada";
	if( $err=='' && $kode =='' ) $err= 'Kode Belum Di Isi !!';
	if( $err=='' && $nmgaji =='' ) $err= 'Nama Gaji Belum Di Isi !!';
	if( $err=='' && $kode_rekening =='' ) $err= 'Kode Rekening Belum Di Isi !!';
	if( $err=='' && $kode_account_at =='' ) $err= 'Kode Akun Belanja Pegawai Belum Di Isi !!';
	if( $err=='' && $kode_account_bm =='' ) $err= 'Kode Akun Beban Belanja Pegawai Belum Di Isi !!';
	if( $err=='' && $status =='' ) $err= 'Status Belum di Pilih !!';
			
			if($err==''){
			
		$kode_rekening = explode('.',$kode_rekening);
						 $k=$kode_rekening[0];	
						 $l=$kode_rekening[1];
						 $m=$kode_rekening[2];	
						 $n=$kode_rekening[3];
						 $o=$kode_rekening[4];
						 
		$kode_account_at = explode('.',$kode_account_at);
						 $ka=$kode_account_at[0];	
						 $kb=$kode_account_at[1];
						 $kc=$kode_account_at[2];	
						 $kd=$kode_account_at[3];
						 $ke=$kode_account_at[4];		
						 
		$kode_account_bm = explode('.',$kode_account_bm);
						 $ka2=$kode_account_at[0];	
						 $kb2=$kode_account_at[1];
						 $kc2=$kode_account_at[2];	
						 $kd2=$kode_account_at[3];
						 $ke2=$kode_account_at[4];						 		 
	
	$aqry = "UPDATE ref_kodegaji set kode='$kode',nama_gaji='$nmgaji',k='$k',l='$l',m='$m',n='$n',o='$o',ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',ka2='$ka2',kb='$kb2',kc2='$kc2',kd2='$kd2',ke2='$ke2',status='$status' where Id='".$idplh."'";$cek .= $aqry;
						$qry = mysql_query($aqry);
			}//end else
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
		$kode_barang = explode(' ',$ref_pilihbarang);
		$f=$kode_barang[0];	
		$g=$kode_barang[1];
		$h=$kode_barang[2];	
		$i=$kode_barang[3];
		$j=$kode_barang[4];
		
		//query ambil data ref_program
		$get = mysql_fetch_array( mysql_query("select * from ref_barang where f=$f and g=$g and h=$h and i=$i and j=$j"));
		$kode_barang=$get['f'].'.'.$get['g'].'.'.$get['h'].'.'.$get['i'].'.'.$get['j'];
		
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
						
		$content = array('IDBARANG'=>$kode_barang, 'NMBARANG'=>$get['nm_barang'], 'kode_account'=>$kode_account, 'nama_account'=>$row['nm_account'], 'tahun_account'=>$row['thn_akun']);	
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
						800,
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
			 "<script src='js/skpd.js' type='text/javascript'></script>".
			 
			"<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/master/ref_rekening/ref_rekening.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/ref_kode_gaji/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
			 ".
			// "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	
	
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_barang = explode(' ',$id);
		$f=$kode_barang[0];	
		$g=$kode_barang[1];
		$h=$kode_barang[2];	
		$i=$kode_barang[3];
		$j=$kode_barang[4];
		
		
		$quricoy="select count(*) as cnt from ref_barang where f='$f' and g='$g' and h<>'00' and i<>'00' and j<>'000'";
		$dt3 = mysql_fetch_array(mysql_query($quricoy));
		$korong = $dt3 ['cnt'];
		
		if($korong>0){
		
		$korong;
		$errmsg = "ada kode barang tidak bisa di edit/hapus, karena masih ada rinciannya !";
		}
		
		if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ")
				) >0 )
				
			{ $errmsg = "GAGAL HAPUS, Kode Barang Sudah Ada Di Buku Induk !!! ";}
			
				//$errmsg = "select Id from buku_induk where f='$f' and g='$g' and h='$h' and i='$j' and i='$j' ";
			
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
		
		
		$aqry = "select * from ref_kodegaji WHERE Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_rekening']=$dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o']; 
		$dt['kode_account_at']=$dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke']; 
		$dt['kode_account_bm']=$dt['ka2'].'.'.$dt['kb2'].'.'.$dt['kc2'].'.'.$dt['kd2'].'.'.$dt['ke2']; 
		$na_re=mysql_fetch_array(mysql_query("select * from ref_rekening where k='".$dt['k']."' and l='".$dt['l']."' and m='".$dt['m']."' and n='".$dt['n']."' and o='".$dt['o']."'"));
		$na_at=mysql_fetch_array(mysql_query("select * from ref_jurnal where ka='".$dt['ka']."' and kb='".$dt['kb']."' and kc='".$dt['kc']."' and kd='".$dt['kd']."' and ke='".$dt['ke']."'"));
		$na_bm=mysql_fetch_array(mysql_query("select * from ref_jurnal where ka='".$dt['ka2']."' and kb='".$dt['kb2']."' and kc='".$dt['kc2']."' and kd='".$dt['kd2']."' and ke='".$dt['ke2']."'"));
		
		$dt['nama_rekening']=$na_re['nm_rekening'];
		$dt['nama_account_at']=$na_at['nm_account'];
		$dt['nama_account_bm']=$na_bm['nm_account'];
		
		$fm = $this->setFormdataedit($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 800;
	 $this->form_height = 170;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
		$readonly='';
	  }else{
		$this->form_caption = 'EDIT';
		//$readonly='readonly';
	  }
	  				
 	   
       //items ----------------------
		  $this->form_fields = array(
			

			'kode' => array( 
						'label'=>'Kode',
						'labelWidth'=>200, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kode' id='kode' value='".$dt['kode']."' style='width:20px;'>
						</div>", 
						 ),		
		  
		  'nmgji' => array( 
						'label'=>'Nama Gaji',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='nmgaji' id='nmgaji' value='".$dt['nama_gaji']."' placeholder='Nama Gaji' style='width:270;'>
						</div>", 
						 ),	
		  
		  
		  	'kdRekening1' => array( 
						'label'=>'Kode Rekening Belanja Pegawai',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kode_rekening' id='kode_rekening' value='".$dt['kode_rekening']."' placeholder='Kode' size='10px'readonly>&nbsp&nbsp
						<input type='text' name='nm_rekening' id='nm_rekening' value='".$dt['nama_rekening']."' placeholder='Nama Sub Unit' size='50px'readonly>&nbsp
						<input type='button' value='Cari' onclick ='ref_kode_gaji.Cari()' title='Cari Rekening' >
						</div>", 
						 ),	
			
			'kode_at' => array( 
								'label'=>'Kode Akun Belanja Pegawai',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account_at' value='".$dt['kode_account_at']."' size='10px' id='kode_account_at' readonly>&nbsp
										  <input type='text' name='nama_account_at' value='".$dt['nama_account_at']."' size='50px' id='nama_account_at' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnalAT()' title='Cari Jurnal Aset Tetap' >" 
									 ),	
			'kode_bm' => array( 
								'label'=>'Kode Akun Beban Belanja Pegawai',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account_bm' value='".$dt['kode_account_bm']."' size='10px' id='kode_account_bm' readonly>&nbsp
										  <input type='text' name='nama_account_bm' value='".$dt['nama_account_bm']."' size='50px' id='nama_account_bm' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnalBM()' title='Cari Jurnal Belanja Modal' >" 
									 ),
									 
				'status' => array( 
						'label'=>'Status',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),			 
									 
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()'>".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormdataedit($dt){	
	 global $SensusTmp, $Main;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 800;
	 $this->form_height = 170;
	  
		$this->form_caption = 'EDIT';
		//$readonly='readonly';
	 
	  				
 	   
       //items ----------------------
		  $this->form_fields = array(
			

			'kode' => array( 
						'label'=>'Kode',
						'labelWidth'=>200, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kode' id='kode' value='".$dt['kode']."' style='width:20px;'readonly>
						</div>", 
						 ),		
		  
		  'nmgji' => array( 
						'label'=>'Nama Gaji',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='nmgaji' id='nmgaji' value='".$dt['nama_gaji']."' placeholder='Nama Gaji' style='width:270;'>
						</div>", 
						 ),	
		  
		  
		  	'kdRekening1' => array( 
						'label'=>'Kode Rekening Belanja Pegawai',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kode_rekening' id='kode_rekening' value='".$dt['kode_rekening']."' placeholder='Kode' size='10px'readonly>&nbsp&nbsp
						<input type='text' name='nm_rekening' id='nm_rekening' value='".$dt['nama_rekening']."' placeholder='Nama Sub Unit' size='50px'readonly>&nbsp
						<input type='button' value='Cari' onclick ='ref_kode_gaji.Cari()' title='Cari Rekening' >
						</div>", 
						 ),	
			
			'kode_at' => array( 
								'label'=>'Kode Akun Belanja Pegawai',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account_at' value='".$dt['kode_account_at']."' size='10px' id='kode_account_at' readonly>&nbsp
										  <input type='text' name='nama_account_at' value='".$dt['nama_account_at']."' size='50px' id='nama_account_at' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnalAT()' title='Cari Jurnal Aset Tetap' >" 
									 ),	
			'kode_bm' => array( 
								'label'=>'Kode Akun Beban Belanja Pegawai',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account_bm' value='".$dt['kode_account_bm']."' size='10px' id='kode_account_bm' readonly>&nbsp
										  <input type='text' name='nama_account_bm' value='".$dt['nama_account_bm']."' size='50px' id='nama_account_bm' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnalBM()' title='Cari Jurnal Belanja Modal' >" 
									 ),
									 
				'status' => array( 
						'label'=>'Status',
						'labelWidth'=>100,
						'value'=>cmbArray('status',$dt['status'],$this->Status,'-- PILIH --','style="width:95px;"'),
						 ),			 
									 
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()'>".
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
	   <th class='th01' width='200' align='center'>Kode</th>
	   <th class='th01' width='450' align='center'>Nama Gaji</th>
	   <th class='th01' width='450' align='center'>Kode Rekening</th>
	   <th class='th01' width='450' align='center'>Nama Rekening Belanja</th>
	   <th class='th01' width='450' align='center'>Kode Akun</th>
	   <th class='th01' width='450' align='center'>Nama Akun Belanja Pegawai</th>
	   <th class='th01' width='450' align='center'>Kode Akun</th>
	   <th class='th01' width='450' align='center'>Nama Akun Beban Belanja Pegawai</th>
	   <th class='th01' width='450' align='center'>Status</th>
	  
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	/*function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	/* $aqry_at = "select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' ";
	 $na_at=mysql_fetch_array(mysql_query($aqry_at));
	 $kode_account_at=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'].'.'.$isi['kf'];	 
	 $aqry_bm = "select * from ref_jurnal where ka='".$isi['m1']."' and kb='".$isi['m2']."' and kc='".$isi['m3']."' and kd='".$isi['m4']."' and ke='".$isi['m5']."' ";
	 $na_bm=mysql_fetch_array(mysql_query($aqry_bm));
	 $kode_account_bm=$isi['m1'].'.'.$isi['m2'].'.'.$isi['m3'].'.'.$isi['m4'].'.'.$isi['m5'].'.'.$isi['m6'];
	 $aqry_ap = "select * from ref_jurnal where ka='".$isi['l1']."' and kb='".$isi['l2']."' and kc='".$isi['l3']."' and kd='".$isi['l4']."' and ke='".$isi['l5']."' ";
	 $na_ap=mysql_fetch_array(mysql_query($aqry_ap));
	 $kode_account_ap=$isi['l1'].'.'.$isi['l2'].'.'.$isi['l3'].'.'.$isi['l4'].'.'.$isi['l5'].'.'.$isi['l6'];
	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left" width="100"',$isi['status']);
 	 /*$Koloms[] = array('align="left" width="200"',$isi['status']); 	 	 
 	 $Koloms[] = array('align="left" width="200"',$isi['status']);
 	 $Koloms[] = array('align="left" width="200"',$isi['status']);	 
  	 $Koloms[] = array('align="left" width="200"',$isi['status']);
  	 $Koloms[] = array('align="left" width="200"',$isi['status']);
  	 $Koloms[] = array('align="left" width="200"',$isi['status']);
  	 $Koloms[] = array('align="left" width="200"',$isi['status']);
	 return $Koloms;
	}*/
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	// $namabarang = mysql_fetch_array(mysql_query("SELECT nama from ref_barang WHERE  f='$f' AND g='$g' AND h='$h' AND i='$i' AND j='$j'")); 
	 $queryrekening=mysql_fetch_array(mysql_query("select k,l,m,n,o,nm_rekening from ref_rekening where k='".$isi['k']."' and l='".$isi['l']."' and m='".$isi['m']."' and n='".$isi['n']."' and o='".$isi['o']."'"));
	 $queryjurnalpegawai=mysql_fetch_array(mysql_query("select ka,kb,kc,kd,ke,kf,nm_account from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."'"));
	 $queryjurnalbeban=mysql_fetch_array(mysql_query("select ka,kb,kc,kd,ke,kf,nm_account from ref_jurnal where ka='".$isi['ka2']."' and kb='".$isi['kb2']."' and kc='".$isi['kc2']."' and kd='".$isi['kd2']."' and ke='".$isi['ke2']."'"));
	  if($isi['status']==1){
	 	$status='Aktif';
	 }elseif($isi['status']==2){
	 	$status='Tidak Aktif';
		}
	// $Koloms[] = array('align="right"',$isi['jml_barang_terbesar']);
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['kode']);
	// $Koloms[] = array('align="center"',genNumber($isi['c1'],1));
	 $Koloms[] = array('align="center"',$isi['nama_gaji']);
	 $Koloms[] = array('align="left"',$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o']);
	 $Koloms[] = array('align="center"',$queryrekening['nm_rekening']);
	 $Koloms[] = array('align="left"',$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke']);
	 $Koloms[] = array('align="left"',$queryjurnalpegawai['nm_account']);
	$Koloms[] = array('align="left"',$isi['ka2'].'.'.$isi['kb2'].'.'.$isi['kc2'].'.'.$isi['kd2'].'.'.$isi['ke2']);
	 $Koloms[] = array('align="left"',$queryjurnalbeban['nm_account']);
	 $Koloms[] = array('align="left"',$status);
	 
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
				
	/*$TampilOpt = 
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
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			*/
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
		
		if(empty($fmBIDANG)) {
			$fmKELOMPOK = '';
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmKELOMPOK)) {
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmSUBKELOMPOK)) {		
			$fmSUBSUBKELOMPOK='';
		}		
		
		if(empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			//$arrKondisi[]= "f !=00 and g=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG"; //$arrKondisi[]= "f =$fmBIDANG and g!=00 and h=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h!=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i!=00 and j=00";				
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i=$fmSUBSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i=$fmSUBSUBKELOMPOK and j!=00";			
		}						
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(f,g,h,i,j) like '".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANG']."%'";	

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
		//	case '': $arrOrders[] = " concat(f,g,h,i,j) ASC " ;break;
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
$ref_kode_gaji = new ref_kode_gajiObj();

?>