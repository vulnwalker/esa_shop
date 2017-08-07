<?php

class RefBarangObj  extends DaftarObj2{	
	var $Prefix = 'RefBarang';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_barang'; //daftar
	var $TblName_Hapus = 'ref_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f1','f2','f','g','h','i','j');
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
	var $FormName = 'RefBarangForm'; 	
			
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
		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
	}	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
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
	 $kode_barang = $_REQUEST['kode_barang'];
	 $nama_barang = $_REQUEST['nama_barang'];
	 $kode_account_at = $_REQUEST['kaap64'];
	 $kode_account_bm = $_REQUEST['kabmp64'];
	 $kode_account_belanja_modal_pemeliharaan = $_REQUEST['kabpp64j'];
	 $kode_account_ap = $_REQUEST['kaapp64'];
	 $kode_account_beban_penyusutan = $_REQUEST['kabpp64'];
	 $kode_rekening_belanja_modal =  $_REQUEST['krbmp21'];
	 $kode_rekening_belanja_pemeliharaan =  $_REQUEST['krbpp21'];
	 $kodeRekeningSewa = $_REQUEST['kodeRekeningSewa'];
	 
	 
	 $nama_account = $_REQUEST['nama_account'];
	 $masa_manfaat = $_REQUEST['masa_manfaat'];	 
	 $residu = str_replace(",",".",$_REQUEST['residu']);
	 $cb=explode('.',$kode_barang);
	 $jml_data=count($cb);
	
	 //Mendapatkan kondisi untuk update 
	for($i=0;$cb[$i]!='00' && $i<$jml_data;$i++){
	 	switch($i){
			case '0': $kondisi="concat(f,g,h,i,j) Like '%".$cb[0]."%'"; break;
			case '1': $kondisi="concat(f,g,h,i,j) Like '%".$cb[0].$cb[1]."%'"; break;
			case '2': $kondisi="concat(f,g,h,i,j) Like '%".$cb[0].$cb[1].$cb[2]."%'"; break;
			case '3': $kondisi="concat(f,g,h,i,j) Like '%".$cb[0].$cb[1].$cb[2].$cb[3]."%'"; break;
			case '4': $kondisi="concat(f,g,h,i,j) Like '%".$cb[0].$cb[1].$cb[2].$cb[3].$cb[4]."%'"; break;		
		}
	 }
	 	 foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 	 } 

		 	if ($f1 == '' || $f2 == '' || $f == '' || $g == '' || $h == '' || $i == '' || $j == ''){
				$err = "Periksa Kode Barang";
			}elseif($satuan == ''){
				$err = "Isi satuan";
			}
 	 if($err=='' && $nama_barang =='' ) $err= 'Nama Barang belum diisi';	 	 
 	
 						$kode_jurnal_at = explode('.',$kode_account_at);
						 $ka=$kode_jurnal_at[0];	
						 $kb=$kode_jurnal_at[1];
						 $kc=$kode_jurnal_at[2];	
						 $kd=$kode_jurnal_at[3];
						 $ke=$kode_jurnal_at[4];
						 /*$kf=$kode_jurnal_at[5];*/
						
						 $kode_jurnal_bm = explode('.',$kode_account_bm);
						 $m1=$kode_jurnal_bm[0];	
						 $m2=$kode_jurnal_bm[1];
						 $m3=$kode_jurnal_bm[2];	
						 $m4=$kode_jurnal_bm[3];
						 $m5=$kode_jurnal_bm[4];			 	 	  
						 /*$m6=$kode_jurnal_bm[5];*/
						 
						 $kode_jurnal_ap = explode('.',$kode_account_ap);
						 $l1=$kode_jurnal_ap[0];	
						 $l2=$kode_jurnal_ap[1];
						 $l3=$kode_jurnal_ap[2];	
						 $l4=$kode_jurnal_ap[3];
						 $l5=$kode_jurnal_ap[4];			 	 	  
						 /*$l6=$kode_jurnal_ap[5];	*/
						 
						 $kode_jurnal_bp = explode('.',$kode_account_beban_penyusutan);
						 $n1=$kode_jurnal_bp[0];	
						 $n2=$kode_jurnal_bp[1];
						 $n3=$kode_jurnal_bp[2];	
						 $n4=$kode_jurnal_bp[3];
						 $n5=$kode_jurnal_bp[4];			 	 	  
						 /*$n6=$kode_jurnal_bp[5];*/
						 
						 $kode_jurnal_belanja_pemeliharaan = explode('.',$kode_account_belanja_modal_pemeliharaan);
						 $o1=$kode_jurnal_belanja_pemeliharaan[0];	
						 $o2=$kode_jurnal_belanja_pemeliharaan[1];
						 $o3=$kode_jurnal_belanja_pemeliharaan[2];	
						 $o4=$kode_jurnal_belanja_pemeliharaan[3];
						 $o5=$kode_jurnal_belanja_pemeliharaan[4];			 	 	  
						 /*$o6=$kode_jurnal_belanja_pemeliharaan[5];*/
						 
						 $kode_rek_belanja_modal = explode('.',$kode_rekening_belanja_modal);
						 $k11=$kode_rek_belanja_modal[0];	
						 $l11=$kode_rek_belanja_modal[1];
						 $m11=$kode_rek_belanja_modal[2];	
						 $n11=$kode_rek_belanja_modal[3];
						 $o11=$kode_rek_belanja_modal[4];
						 
						 
						 $kode_rek_belanja_pemeliharaan = explode('.',$kode_rekening_belanja_pemeliharaan);
						 $k12=$kode_rek_belanja_pemeliharaan[0];	
						 $l12=$kode_rek_belanja_pemeliharaan[1];
						 $m12=$kode_rek_belanja_pemeliharaan[2];	
						 $n12=$kode_rek_belanja_pemeliharaan[3];
						 $o12=$kode_rek_belanja_pemeliharaan[4];
						 
						 $kodeRekeningSewa = explode('.',$kodeRekeningSewa);
						 $k13=$kodeRekeningSewa[0];	
						 $l13=$kodeRekeningSewa[1];
						 $m13=$kodeRekeningSewa[2];	
						 $n13=$kodeRekeningSewa[3];
						 $o13=$kodeRekeningSewa[4];			 

 	 	 	 	 	 	 
			if($fmST == 0){ 
				if($err==''){ 
						$data = array('f1' => $f1,
									  'f2' => $f2,
									  'f' => $f,
									  'g' => $g,
									  'h' => $h,
									  'i' => $i,
									  'j' => $j,
									  'ka' => $ka,
									  'kb' => $kb,
									  'kc' => $kc,
									  'kd' =>$kd,
									  'ke' => $ke,
									  'kf' => $kf,
									  'l1' => $l1,
									  'l2' => $l2,
									  'l3' =>$l3,
									  'l4'=>$l4,
									  'l5'=>$l5,
									  'l6'=>$l6,
									  'm1'=>$m1,
									  'm2'=>$m2,
									  'm3'=>$m3,
									  'm4'=>$m4,
									  'm5'=>$m5,
									  'm6'=>$m6,
									  'n1'=>$n1,
									  'n2'=>$n2,
									  'n3'=>$n3,
									  'n4'=>$n4,
									  'n5'=>$n5,
									  'n6'=>$n6,
									  'k11'=>$k11,
									  'l11'=>$l11,
									  'm11'=>$m11,
									  'n11'=>$n11,
									  'o11'=>$o11,
									  'k12'=>$k12,
									  'l12'=>$l12,
									  'm12'=>$m12,
									  'n12'=>$n12,
									  'o12'=>$o12,
									  'k13'=>$k13,
									  'l13'=>$l13,
									  'm13'=>$m13,
									  'n13'=>$n13,
									  'o13'=>$o13,
									  'o1'=>$o1,
									  'o2'=>$o2,
									  'o3'=>$o3,
									  'o4'=>$o4,
									  'o5'=>$o5,
									  'o6'=>$o6,
									  'nm_barang'=>$nama_barang,
									  'satuan'=>$satuan
									  );
						$qry =  mysql_query(VulnWalkerInsert("ref_barang",$data));
						if($qry){
							
						}else{
							$err="Gagal menyimpan barang";
						}									
				}else{
					$err="Gagal menyimpan barang";
				}
			}elseif($fmST == 1){
				if($err==''){
						$data = array('f1' => $f1,
									  'f2' => $f2,
									  'f' => $f,
									  'g' => $g,
									  'h' => $h,
									  'i' => $i,
									  'j' => $j,
									  'ka' => $ka,
									  'kb' => $kb,
									  'kc' => $kc,
									  'kd' =>$kd,
									  'ke' => $ke,
									  'kf' => $kf,
									  'l1' => $l1,
									  'l2' => $l2,
									  'l3' =>$l3,
									  'l4'=>$l4,
									  'l5'=>$l5,
									  'l6'=>$l6,
									  'm1'=>$m1,
									  'm2'=>$m2,
									  'm3'=>$m3,
									  'm4'=>$m4,
									  'm5'=>$m5,
									  'm6'=>$m6,
									  'n1'=>$n1,
									  'n2'=>$n2,
									  'n3'=>$n3,
									  'n4'=>$n4,
									  'n5'=>$n5,
									  'n6'=>$n6,
									  'k11'=>$k11,
									  'l11'=>$l11,
									  'm11'=>$m11,
									  'n11'=>$n11,
									  'o11'=>$o11,
									  'k12'=>$k12,
									  'l12'=>$l12,
									  'm12'=>$m12,
									  'n12'=>$n12,
									  'o12'=>$o12,
									  'k13'=>$k13,
									  'l13'=>$l13,
									  'm13'=>$m13,
									  'n13'=>$n13,
									  'o13'=>$o13,
									  'o1'=>$o1,
									  'o2'=>$o2,
									  'o3'=>$o3,
									  'o4'=>$o4,
									  'o5'=>$o5,
									  'o6'=>$o6,
									  'nm_barang'=>$nama_barang,
									  'satuan'=>$satuan
									  );
					    $forWhere = $f1.".".$f2.".".$f.".".$g.".".$h.".".$i.".".$j;
						$qry = mysql_query($aqry2);
						$qry = mysql_query(VulnWalkerUpdate('ref_barang',$data,"concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$forWhere'"));
						$cek = VulnWalkerUpdate('ref_barang',$data,"concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$forWhere'");
						if($qry==FALSE)
						{ 
							$err="Gagal menyimpan barang";
						}else{
							
						}
					}

			}else{
			if($err==''){ 

				}
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
			


			
						
		$content = array('IDBARANG'=>$kode_barang, 'NMBARANG'=>$get['nm_barang'], 'kode_account'=>$kode_account, 'nama_account'=>$row['nm_account'], 'tahun_account'=>$row['thn_akun']  );	
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
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >  &nbsp &nbsp ".
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
			 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>
			 <script type='text/javascript' src='js/master/ref_aset/refrekening.js' language='JavaScript' ></script>
			 <script type='text/javascript' src='js/master/ref_template/jquery.js' language='JavaScript' ></script>".
			
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
		
		

		if($f2 =='0'){
		  $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2!='0' "));
		  if($hasRow > 0){
		  	$errmsg = "Data tidak dapat di hapus";
		  }
		}else{
		   if($f =='0' ){
		   $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2='$f2' and f !='0' and g ='00' and h ='00' and i ='00' and j = '000' "));
			  if($hasRow > 0){
			  	$errmsg = "Data tidak dapat di hapus";
		 	 }
		  }else{
			  	if( $g=='00'){
			  $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2='$f2' and f='$f' and g !='00' and h ='00' and i ='00' and j = '000' "));
			  if($hasRow > 0){
			  	$errmsg = "Data tidak dapat di hapus";
			  }
			}else{
				if( $h=='00'){
		  $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2='$f2' and f='$f' and g ='$g' and h !='00' and i ='00' and j = '000' "));
		  if($hasRow > 0){
		  	$errmsg = "Data tidak dapat di hapus";
		  }
		}else{
			if( $i=='00'){
		  $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2='$f2' and f='$f' and g ='$g' and h='$h' and i!='00'  and j = '000' "));
		  if($hasRow > 0){
		  	$errmsg = "Data tidak dapat di hapus";
		  }
		}else{
			if( $j=='000'){
		  $hasRow = mysql_num_rows(mysql_query("select * from ref_barang where f1 ='$f1' and f2='$f2' and f='$f' and g ='$g' and h='$h'  and i='$i' and j!='000' "));
		  if($hasRow > 0){
		  	$errmsg = "Data tidak dapat di hapus";
		  }
		}
		}
		}
			}
		  }
		}
			
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
		$f1 = '0';
		$f2 = '0';
		$f = $_REQUEST['cmbJenis'];
		$g = $_REQUEST['cmbObyek'];
		$h = $_REQUEST['cmbRincianObyek'];
		$i = $_REQUEST['cmbSubRincianObyek'];
		$j = $_REQUEST['cmbSubSubRincianObyek'];
	  }else{
		$this->form_caption = 'EDIT';
		$chmod644 ="readonly";
		$f1 = '0';
		$f2 = '0';
		$f = $dt['f'];
		$g = $dt['g'];
		$h = $dt['h'];
		$i = $dt['i'];
		$j = $dt['j'];
		 $aqry_at = "select * from ref_jurnal where ka='".$dt['ka']."' and kb='".$dt['kb']."' and kc='".$dt['kc']."' and kd='".$dt['kd']."' and ke='".$dt['ke']."' ";
	 $na_at=mysql_fetch_array(mysql_query($aqry_at));
	 
	 
	 $aqry_bm = "select * from ref_jurnal where ka='".$dt['m1']."' and kb='".$dt['m2']."' and kc='".$dt['m3']."' and kd='".$dt['m4']."' and ke='".$dt['m5']."' ";
	 $na_bm=mysql_fetch_array(mysql_query($aqry_bm));
	 
	 
	 $aqry_ap = "select * from ref_jurnal where ka='".$dt['l1']."' and kb='".$dt['l2']."' and kc='".$dt['l3']."' and kd='".$dt['l4']."' and ke='".$dt['l5']."' ";
	 $na_ap=mysql_fetch_array(mysql_query($aqry_ap));
	 
	 
	 //vw
	 $query_rek_bm = "select * from ref_rekening where k='".$dt['k11']."' and l='".$dt['l11']."' and m='".$dt['m11']."' and n='".$dt['n11']."' and o='".$dt['o11']."' ";
	 $na_rek_bm = mysql_fetch_array(mysql_query($query_rek_bm));
	 $kode_rek_bm=$dt['k11'].'.'.$dt['l11'].'.'.$dt['m11'].'.'.$dt['n11'].'.'.$dt['o11'];
	 
	 $query_rek_bp = "select * from ref_rekening where k='".$dt['k12']."' and l='".$dt['l12']."' and m='".$dt['m12']."' and n='".$dt['n12']."' and o='".$dt['o12']."' ";
	 $na_rek_bp = mysql_fetch_array(mysql_query($query_rek_bp));
	 $kode_rek_bp=$dt['k12'].'.'.$dt['l12'].'.'.$dt['m12'].'.'.$dt['n12'].'.'.$dt['o12'];
	 
	 $query_akun_pemeliharaan = "select * from ref_jurnal where ka='".$dt['o1']."' and kb='".$dt['o2']."' and kc='".$dt['o3']."' and kd='".$dt['o4']."' and ke='".$dt['o5']."'  ";
	 $na_akun_pemeliharaan=mysql_fetch_array(mysql_query($query_akun_pemeliharaan));
	 
	 
	 $query_akun_beban_penyusutan = "select * from ref_jurnal where ka='".$dt['n1']."' and kb='".$dt['n2']."' and kc='".$dt['n3']."' and kd='".$dt['n4']."' and ke='".$dt['n5']."' ";
	 $na_akun_beban_penyusutan=mysql_fetch_array(mysql_query($query_akun_beban_penyusutan));
	 
	 
	 /*if($dt['ke'] < 10)$dt['ke'] = "0".$dt['ke'];
	 if($dt['m5'] < 10)$dt['m5'] = "0".$dt['m5'];
	 if($dt['l5'] < 10)$dt['l5'] = "0".$dt['l5'];
	 if($dt['n5'] < 10)$dt['n5'] = "0".$dt['n5'];
	 if($dt['o5'] < 10)$dt['o5'] = "0".$dt['o5'];
	 if($dt['n13'] < 10)$dt['n13'] = "0".$dt['n13'];
	 if($dt['o13'] < 10)$dt['o13'] = "0".$dt['o13'];*/
	 
	 $kodeRekeningSwa = $dt['k13'].'.'.$dt['l13'].'.'.$dt['m13'].'.'.$dt['n13'].'.'.$dt['o13'];
	 $namaRekeningSewa = mysql_fetch_array(mysql_query("select * from ref_rekening where k='".$dt['k13']."' and l='".$dt['l13']."' and m='".$dt['m13']."' and n='".$dt['n13']."' and o='".$dt['o13']."'"));
	 $kode_account_at=$dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'];	 
	 $kode_account_bm=$dt['m1'].'.'.$dt['m2'].'.'.$dt['m3'].'.'.$dt['m4'].'.'.$dt['m5'];
	 $kode_account_ap=$dt['l1'].'.'.$dt['l2'].'.'.$dt['l3'].'.'.$dt['l4'].'.'.$dt['l5'];
	 $kode_account_pemeliharaan=$dt['o1'].'.'.$dt['o2'].'.'.$dt['o3'].'.'.$dt['o4'].'.'.$dt['o5'];
	 $kode_account_beban_penyusutan=$dt['n1'].'.'.$dt['n2'].'.'.$dt['n3'].'.'.$dt['n4'].'.'.$dt['n5'];
		//$readonly='readonly';
	  }
	  				
 	    $username=$_REQUEST['username'];
		
		$lengthKodeBrg =  12 + $Main->KODEBARANGJ_DIGIT ;
		//$sampleKodeBrg = "*00.00.00.00.000" ;
		
		//query ref_batal
		$queryKB = "SELECT f,nama_barang FROM ref_barang_persediaan where f !='00' and g='00'";
		
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
								'labelWidth'=>200, 
								'value'=>"<input type='text' id='f' name='f' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$f' $chmod644> &nbsp <input type='text' id='g' name='g' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$g' $chmod644> &nbsp <input type='text' id='h' name='h' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$h' $chmod644> &nbsp <input type='text' id='i' name='i' style='width:25px;' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$i' $chmod644> &nbsp <input type='text' id='j' name='j' style='width:30px;' maxlength='3' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='$j' $chmod644> &nbsp  
									<font color=red>$sampleKodeBrg</font>
									<input type='hidden' id='f1' name='f1' maxlength='1' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='0' > &nbsp <input type='hidden' id='f2' name='f2'  maxlength='1' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='0' >
									" 
									 ),	
									 
			'nama_barang' => array( 
								'label'=>'NAMA BARANG',
								'labelWidth'=>250, 
								'value'=>$dt['nm_barang'], 
								'type'=>'text',
								'id'=>'nama_barang',
								'param'=>"style='width:250ppx;' size=50px"
									 ),	
			'satuan' => array( 
								'label'=>'SATUAN',
								'labelWidth'=>250, 
								'value'=>$dt['satuan'], 
								'type'=>'text',
								'id'=>'satuan',
								'param'=>"style='width:250ppx;' size=50px"
									 ),	
									 
			'krbmp21' => array( 
								'label'=>'BELANJA MODAL PERMEN 21',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='krbmp21' value='".$kode_rek_bm."' size='10px' id='krbmp21' readonly>&nbsp
										  <input type='text' name='nbmp21' value='".$na_rek_bm['nm_rekening']."' size='73px' id='nrbmp21' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikrbmp21()'  title='Cari Jurnal Aset Tetap' >" 
									 ),
			'krbpp21' => array( 
								'label'=>'BELANJA PEMELIHARAAN PERMEN 21',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='krbpp21' value='".$kode_rek_bp."' size='10px' id='krbpp21' readonly>&nbsp
										  <input type='text' name='nrbpp21' value='".$na_rek_bp['nm_rekening']."' size='73px' id='nrbpp21' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikrbpp21()'  title='Cari Jurnal Aset Tetap' >" 
									 ),
			'rekeningSewa' => array( 
								'label'=>'BELANJA SEWA PERMEN 21',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kodeRekeningSewa' value='".$kodeRekeningSwa."' size='10px' id='kodeRekeningSewa' readonly>&nbsp
										  <input type='text' name='namaRekeningSewa' value='".$namaRekeningSewa['nm_rekening']."' size='73px' id='namaRekeningSewa' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".cariRekeningSewa()'  title='Cari Rekening Sewa' >" 
									 ),						 
			'kabmp64' => array( 
								'label'=>'BELANJA MODAL PERMEN 64',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kabmp64' value='".$kode_account_bm."' size='10px' id='kabmp64' readonly>&nbsp
										  <input type='text' name='nabmp64' value='".$na_bm['nm_account']."' size='73px' id='nabmp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabmp64()'  title='Cari Jurnal Aset Tetap' >" 
									 ),
			
			'kabpp64j' => array( 
								'label'=>'BELANJA PEMELIHARAAN PERMEN 64 ',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kabpp64j' value='".$kode_account_pemeliharaan."' size='10px' id='kabpp64j' readonly>&nbsp
										  <input type='text' name='nabpp64j' value='".$na_akun_pemeliharaan['nm_account']."' size='73px' id='nabpp64j' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabpp64j()'  title='Cari Jurnal Belanja Modal Pemeliharaan' >" 
									 ),
			'kaap64' => array( 
								'label'=>'ASET PERMEN 64',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kaap64' value='".$kode_account_at."' size='10px' id='kaap64' readonly>&nbsp
										  <input type='text' name='naap64' value='".$na_at['nm_account']."' size='73px' id='naap64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikaap64()'  title='Cari Aset Tetap' >" 
									 ),
			'kaapp64' => array( 
								'label'=>'AKUMULASI PENYUSUTAN PERMEN 64',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kaapp64' value='".$kode_account_ap."' size='10px' id='kaapp64' readonly>&nbsp
										  <input type='text' name='naapp64' value='".$na_ap['nm_account']."' size='73px' id='naapp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikaapp64()'  title='Cari Akun Akumulasi Penyusutan' >" 
									 ),
			'kabpp64' => array( 
								'label'=>'BEBAN PENYUSUTAN PERMEN 64',
								'labelWidth'=>250, 
								'value'=>"<input type='text' name='kabpp64' value='".$kode_account_beban_penyusutan."' size='10px' id='kabpp64' readonly>&nbsp
										  <input type='text' name='nabpp64' value='".$na_akun_beban_penyusutan['nm_account']."' size='73px' id='nabpp64' readonly>&nbsp
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".Carikabpp64()'  title='Cari Jurnal Aset Tetap' >" 
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
	   $Checkbox		
	   <th class='th01' align='left' width='100'>KODE BARANG</th>
	   <th class='th01' align='left' width='800'>NAMA BARANG</th>
	   <th class='th01' align='left' width='100'>SATUAN</th>
	   <th class='th01' align='left' width='200'>KODE REKENING BELANJA MODAL PERMEN 21</th>
	   <th class='th01' align='left' width='200'>KODE REKENING BELANJA PEMELIHARAAN PERMEN 21</th>
	   <th class='th01' align='left' width='200'>KODE REKENING BELANJA SEWA PERMEN 21</th>
	   <th class='th01' align='left' width='200'>KODE AKUN BELANJA MODAL PERMEN 64</th>
	   <th class='th01' align='left' width='200'>KODE AKUN BELANJA PEMELIHARAAN PERMEN 64</th>
	   <th class='th01' align='left' width='200'>KODE AKUN ASET PERMEN 64</th>
	   <th class='th01' align='left' width='200'>KODE AKUN AKUMULASI PENYUSUTAN</th>
	   <th class='th01' align='left' width='200'>KODE AKUN BEBAN PENYUSUTAN</th>

   	  

	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	/*	   <th class='th01' align='left' width='100'>MASA MANFAAT (TAHUN)</th>
	   <th class='th01' align='left' width='100'>RESIDU (%)</th>	 */  
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 
	 $aqry_at = "select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' ";
	 $na_at=mysql_fetch_array(mysql_query($aqry_at));
	 
	 
	 $aqry_bm = "select * from ref_jurnal where ka='".$isi['m1']."' and kb='".$isi['m2']."' and kc='".$isi['m3']."' and kd='".$isi['m4']."' and ke='".$isi['m5']."' ";
	 $na_bm=mysql_fetch_array(mysql_query($aqry_bm));
	 
	 
	 $aqry_ap = "select * from ref_jurnal where ka='".$isi['l1']."' and kb='".$isi['l2']."' and kc='".$isi['l3']."' and kd='".$isi['l4']."' and ke='".$isi['l5']."' ";
	 $na_ap=mysql_fetch_array(mysql_query($aqry_ap));
	 
	 
	 //vw
	 $query_rek_bm = "select * from ref_rekening where k='".$isi['k11']."' and l='".$isi['l11']."' and m='".$isi['m11']."' and n='".$isi['n11']."' and o='".$isi['o11']."' ";
	 $na_rek_bm = mysql_fetch_array(mysql_query($query_rek_bm));
	 $kode_rek_bm=$isi['k11'].'.'.$isi['l11'].'.'.$isi['m11'].'.'.$isi['n11'].'.'.$isi['o11'];
	 
	 $query_rek_bp = "select * from ref_rekening where k='".$isi['k12']."' and l='".$isi['l12']."' and m='".$isi['m12']."' and n='".$isi['n12']."' and o='".$isi['o12']."' ";
	 $na_rek_bp = mysql_fetch_array(mysql_query($query_rek_bp));
	 $kode_rek_bp=$isi['k12'].'.'.$isi['l12'].'.'.$isi['m12'].'.'.$isi['n12'].'.'.$isi['o12'];
	 
	 $query_akun_pemeliharaan = "select * from ref_jurnal where ka='".$isi['o1']."' and kb='".$isi['o2']."' and kc='".$isi['o3']."' and kd='".$isi['o4']."' and ke='".$isi['o5']."'  ";
	 $na_akun_pemeliharaan=mysql_fetch_array(mysql_query($query_akun_pemeliharaan));
	 
	 
	 $query_akun_beban_penyusutan = "select * from ref_jurnal where ka='".$isi['n1']."' and kb='".$isi['n2']."' and kc='".$isi['n3']."' and kd='".$isi['n4']."' and ke='".$isi['n5']."' ";
	 $na_akun_beban_penyusutan=mysql_fetch_array(mysql_query($query_akun_beban_penyusutan));
	 
	 
	 /*if($isi['ke'] < 10)$isi['ke'] = "0".$isi['ke'];
	 if($isi['m5'] < 10)$isi['m5'] = "0".$isi['m5'];
	 if($isi['l5'] < 10)$isi['l5'] = "0".$isi['l5'];
	 if($isi['n5'] < 10)$isi['n5'] = "0".$isi['n5'];
	 if($isi['o5'] < 10)$isi['o5'] = "0".$isi['o5'];
	 if($isi['n13'] < 10)$isi['n13'] = "0".$isi['n13'];
	 if($isi['o13'] < 10)$isi['o13'] = "0".$isi['o13'];*/
	 
	 
	 $kode_account_at=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'];	 
	 $kode_account_bm=$isi['m1'].'.'.$isi['m2'].'.'.$isi['m3'].'.'.$isi['m4'].'.'.$isi['m5'];
	 $kode_account_ap=$isi['l1'].'.'.$isi['l2'].'.'.$isi['l3'].'.'.$isi['l4'].'.'.$isi['l5'];
	 $kode_account_pemeliharaan=$isi['o1'].'.'.$isi['o2'].'.'.$isi['o3'].'.'.$isi['o4'].'.'.$isi['o5'];
	 $kode_account_beban_penyusutan=$isi['n1'].'.'.$isi['n2'].'.'.$isi['n3'].'.'.$isi['n4'].'.'.$isi['n5'];
	 $kodeRekeningSewa = $isi['k13'].'.'.$isi['l13'].'.'.$isi['m13'].'.'.$isi['n13'].'.'.$isi['o13'];
	 $namaRekeningSewa = mysql_fetch_array(mysql_query("select * from ref_rekening where k='".$isi['k13']."' and l='".$isi['l13']."' and m='".$isi['m13']."' and n='".$isi['n13']."' and o='".$isi['o13']."' "));
	 
	 $kode_barang=$isi['f1'].".".$isi['f2'].".".$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 if($isi['j'] == '000'){
	 	$Koloms[] = array('align="left" width="100" style="font-weight : bold;" ',$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']);
	 }else{
	 	$Koloms[] = array('align="left" width="100" style="border-left-style: none;" ',$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']);
	 }
	 
 	 $Koloms[] = array('align="left" width="200"',$isi['nm_barang']);
	 $Koloms[] = array('align="left" width="100"',$isi['satuan']);	 
	 $Koloms[] = array('align="left" width="200"',$kode_rek_bm.'<br>'.$na_rek_bm['nm_rekening']);	
	 $Koloms[] = array('align="left" width="200"',$kode_rek_bp.'<br>'.$na_rek_bp['nm_rekening']);
	 $Koloms[] = array('align="left" width="200"',$kodeRekeningSewa.'<br>'.$namaRekeningSewa['nm_rekening']);		
	 $Koloms[] = array('align="left" width="200"',$kode_account_bm.'<br>'.$na_bm['nm_account']); 
	 $Koloms[] = array('align="left" width="200"',$kode_account_pemeliharaan.'<br>'.$na_akun_pemeliharaan['nm_account']);
	 $Koloms[] = array('align="left" width="200"',$kode_account_at.'<br>'.$na_at['nm_account']);
  	 $Koloms[] = array('align="left" width="200"',$kode_account_ap.'<br>'.$na_ap['nm_account']);	 
	 $Koloms[] = array('align="left" width="200"',$kode_account_beban_penyusutan.'<br>'.$na_akun_beban_penyusutan['nm_account']);	 	 
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
		
		//kondisi -----------------------------------
		
		
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
			$cmbAkun = '0';
		$cmbKelompok = '0';
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
		
			
		
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(f1,f2,f,g,h,i,j) like '".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANG']."%'";	
		$arrKondisi[] = "j !='000' ";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
			$arrOrders[] = " concat(f1,f2,f,g,h,i,j) ASC " ;
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
$RefBarang = new RefBarangObj();

?>