<?php

class gantirugi_cariObj  extends DaftarObj2{	
	var $Prefix = 'gantirugi_cari';
	//var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_gantirugi'; //daftar
	var $TblName_Hapus = 'gantirugi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('harga_perolehan','harga_sk');//array('jml_harga');
	var $SumValue = array();
	var $totalCol = 17; //total kolom daftar
	var $FieldSum_Cp1 = array();//berdasar mode
	var $FieldSum_Cp2 = array();
	var $fieldSum_lokasi = array(10,16);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Tuntutan ganti rugi.xls';
	var $Cetak_Judul = 'Tuntutan Ganti Rugi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'gantirugi_cariForm'; 	
			
	function setTitle(){
		return 'Tuntutan Ganti Rugi';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'UsulanBaru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'EditUsulan')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'HapusUsulan')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Ketetapan()","new_f2.png","SK TGR", 'Ketetapan')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".BatalKetetapan()","delete_f2.png","Batal SK", 'BatalKetetapan')."</td>".
			"</td>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 $tabel='gantirugi';
	 //get data -----------------
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 	$kode_barang=$_REQUEST['fmIDBARANG'];
		$expkdbarang=explode('.',$kode_barang);
		$id_bukuinduk=$_REQUEST['idbi'];
		$c=$_REQUEST['c'];
		$d=$_REQUEST['d'];
		$e=$_REQUEST['e'];
		//$e1=$_REQUEST['e1'];
		$e1='001';
		$no_usul=$_REQUEST['no_usul'];
		$tgl_usul=$_REQUEST['tgl_usul'];
		$f=$expkdbarang['0']; $g=$expkdbarang['1']; $h=$expkdbarang['2']; $i=$expkdbarang['3']; $j=$expkdbarang['4'];
		$tahun=$_REQUEST['tahun'];
		$harga=$_REQUEST['harga'];
		$noreg=$_REQUEST['noreg'];
		$kepada_nama=$_REQUEST['kepada_nama'];
		$kepada_alamat=$_REQUEST['kepada_alamat'];
		$uraian=$_REQUEST['uraian'];
		$ket=$_REQUEST['ket'];
		$tgl_gantirugi=$_REQUEST['tgl_gantirugi'];
		$no_sk=$_REQUEST['no_sk'];
		$tgl_sk=$_REQUEST['tgl_sk'];
		$tgl_update=date('Y/m/d h:i:s');
			
	
		if ($kepada_nama==''){
			$err="Kepada Nama Tidak Boleh Kosong";
		}
		if ($f=='' && $g=='' && $h=='' && $i=='' && $j==''){
			$err="Kode Barang Tidak Boleh Kosong";
		}
		if ($tgl_usul==''){
			$err="Tanggal Usul Tidak Boleh Kosong ";
		}
		if ($no_usul==''){
			$err="No Usul Tidak Boleh Kosong ";
		}
		
		if ($err==''){
		 if($fmST==0){
		 	if (mysql_fetch_array(mysql_query("select id_bukuinduk from gantirugi where id_bukuinduk='$id_bukuinduk'"))){
			$err="Data Sudah Diusulkan ";
			}
			$aqry="insert into $tabel (id_bukuinduk,c,d,e,e1,f,g,h,i,j,no_usul,tgl_usul,tahun,noreg,kepada_nama,
					kepada_alamat,uraian,ket,tgl_gantirugi,stat,uid,tgl_update) values
					('$id_bukuinduk','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$no_usul','$tgl_usul','$tahun','$noreg','$kepada_nama',
					'$kepada_alamat','$uraian','$ket','$tgl_gantirugi','$stat','$uid','$tgl_update')";$cek.=$aqry;
		$qry=mysql_query($aqry);
		if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();
		    $aqry1="Update buku_induk set
			        staset='9',
					stusul='4'
					where id='$id_bukuinduk';					
					";$cek.=$aqry1;
		$qry1=mysql_query($aqry1);
		if($qry1==FALSE) $err="Gagal Simpan data ".mysql_error();
		}elseif($fmST==1){
				$aqry="UPDATE $tabel set
				f='$f',g='$g',h='$h',i='$i',j='$j',id_bukuinduk='$id_bukuinduk',
				no_usul='$no_usul',
				tgl_usul='$tgl_usul',
				tahun='$tahun',
				noreg='$noreg',
				kepada_nama='$kepada_nama',
				kepada_alamat='$kepada_alamat',
				uraian='$uraian',
				ket='$ket',
				uid='$uid',
				tgl_update='$tgl_update',
				tgl_gantirugi='$tgl_gantirugi'
		where id='$idplh'";$cek.=$aqry;
		$qry=mysql_query($aqry);
		if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();
			
		}
	}
	
	
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);		
    }
		
	
	function simpanPilih(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//$coDaftar = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];$cek .=$coDaftar;

		//$ids= explode(',',$coDaftar); //$_POST['cidBI'];	//id bi barang
		$ids = $_REQUEST['cidBI'];
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
			
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'")) ;
			
			$content->fmIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->fmNMBARANG = $brg['nm_barang'];
			$content->noreg = $bi['noreg'];
			$content->tahun = $bi['thn_perolehan'];
			//$content->harga_perolehan = $bi['harga'];
			$content->harga_perolehan =number_format($bi['harga'], 2, ',' , '.');
			$content->tgl_gantirugi = $bi['tgl_buku'];
			$content->idbi = $ids[0];
			$content->c = $bi['c'];
			$content->d = $bi['d'];
			$content->e = $bi['e'];
			$content->e1 = $bi['e1'];			
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function simpanSK(){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 global $HTTP_COOKIE_VARS;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	  $tabel='gantirugi';
	 //get data -----------------
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 	$id_bukuinduk=$_REQUEST['idbi'];
		$harga=$_REQUEST['harga'];
		$no_sk=$_REQUEST['no_sk'];
		$tgl_sk=$_REQUEST['tgl_sk'];
		$tgl_update=date('Y/m/d h:i:s');
		$stat=$_REQUEST['stat'];
		if ($harga==''){
			$err="Harga Ketetapan Tidak Boleh Kosong";
		}
		if ($tgl_sk==''){
			$err="Tanggal Ketetapan Tidak Boleh Kosong";
		}
		if ($no_sk==''){
			$err="Nomor Ketetapan Tidak Boleh Kosong ";
		}
		if ($harga=='' || $harga=='0'){
			$err="Harga Ketetapan Tidak Boleh Nol ";
		}
		if($err==''){
		$aqry="UPDATE $tabel set
				no_sk='$no_sk',
				tgl_sk='$tgl_sk',
				harga='$harga',
				uid='$uid',
				tgl_update='$tgl_update',
				stat='1'
		where id='$idplh'";$cek.=$aqry;
		$qry=mysql_query($aqry);
		if($qry==FALSE) $err="Gagal Batal Ketetapan ".mysql_error();
		
		  $aqry1="Update buku_induk set
		  			status_barang='5',
			        staset='6',
					stusul='0'
					where id='$id_bukuinduk';					
					";$cek.=$aqry1;
		$qry1=mysql_query($aqry1);
		if($qry1==FALSE) $err="Gagal Simpan data ".mysql_error();
		
		}	
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
		
		case 'hapus':{				
			$fm = $this->hapus();				
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
		case 'BatalKetetapan':{
					$get= $this->BatalKetetapan();
					$err= $get['err']; 
					$cek = $get['cek'];
					$content = $get['content'];
					$json=TRUE;	
					break;
				}
		case 'formCari':{				
				$fm = $this->formCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
				
		case 'formKetetapan':{				
			$fm = $this->setFormKetetapan();				
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
	   
	   case 'simpanSK':{
			$get= $this->simpanSK();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'simpanPilih':{				
				$get= $this->simpanPilih();
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
		 	 "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/gantirugi/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			
			$scriptload;
	}
	function Hapus_Data($ids){//id -> multi id with space delimiter
		$err = ''; $cek='';
		$KeyValue = explode(' ',$ids);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
			$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		//$Kondisi = 	"Id='".$id."'";
		
		if($err==''){
			$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
			$qry = mysql_query($aqry);
			if ($qry==FALSE){
			$err = 'Gagal Hapus Data';}
		}
		
		
		return array('err'=>$err,'cek'=>$cek);
	}
	function Hapus_Data_After($ids){
		$err = ''; $content=''; $cek='';
		
		return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
	}
	function Hapus($ids){
		$err=''; $cek='';
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		for($i = 0; $i<count($ids); $i++)	{
			$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
			if($cekdata['no_sk']!=''){
			$err="Tidak bisa Dihapus, Sudah Ada Ketetapan!";
			}
			
			if($err ==''){
				$get = $this->Hapus_Data($ids[$i]);
				$err = $get['err'];
				$cek.= $get['cek'];
				if ($errmsg=='') {
					$after = $this->Hapus_Data_After($ids[$i]);
					$err=$after['err'];
					$cek=$after['cek'];
				}
				if ($err != '') break;
				 				
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	} 
	function BatalKetetapan(){//id -> multi id with space delimiter
		$err = ''; $cek=''; $content='';
		 $uid = $HTTP_COOKIE_VARS['coID'];
		 $tgl=date('Y/m/d h:i:s');
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		 
		$KeyValue = explode(' ',$cbid);
		$cekdata=mysql_fetch_array(mysql_query("select * from v_gantirugi_bayar where ref_idgantirugi='".$this->form_idplh."'"));
		if($cekdata['jml_bayar']!=0){
			$err="Tidak bisa Dibatalkan, Sudah Ada Pembayaran!";
		}
		if($err==''){
			$aqry= "Update gantirugi set 
				no_sk='', 
				tgl_sk='', 
				harga='', 
				uid='$uid',
				tgl_update='$tgl_update',
				stat='0'
				 
				where id='".$this->form_idplh."'"; $cek.=$aqry;
			$qry = mysql_query($aqry);
			if ($qry==FALSE){
				$err = 'Gagal Hapus Data'.mysql_error();}
		}
		
		
		return array('err'=>$err,'cek'=>$cek, 'content'=>$content);
	}
	
	//form ==================================
	function formCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(	
			'skpd' => array( 
				'label'=>'',
				'value'=>
					"<table width=\"100%\" class=\"adminform\">	<tr>		
					<td width=\"100%\" valign=\"top\">" . 					
						WilSKPD_ajx($this->Prefix.'Skpd','100%',100,True,'','','',TRUE,'',1) . 
						//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr></table>", 
				'type'=>'merge'
			),			
			'detailcari' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcari' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".Pilih()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closecari()' >";
		
		//$form = //$this->genForm();		
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
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function setFormBaru(){
		global $Main;
		$err='';
		$dt=array();
		$this->form_fmST = 0;
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
							
		if ($err=='') $fm = $this->setForm($dt);	
			
		return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
   	
	function setFormKetetapan(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 2;
		$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
		if($cekdata['no_sk']!=''){
			$fm['err']="Sudah Ditetapan!";
		}
		if($fm['err']==''){
		$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
		$qry=mysql_query($aqry);
		$dt=mysql_fetch_array($qry);
		$fm = $this->setForm($dt);
		}
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;
		
		
		$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
		if($cekdata['no_sk']!=''){
			$fm['err']="Tidak bisa Diedit, Sudah Ada Ketetapan!";
		}
		if($fm['err']==''){
			$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
			$qry=mysql_query($aqry);
			$dt=mysql_fetch_array($qry);
			$fm = $this->setForm($dt);
		}
		
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 
	  if ($this->form_fmST==0) {
		$this->form_caption = 'USULAN BARU';
	  }elseif($this->form_fmST==1){
		$this->form_caption = 'EDIT USULAN';
	  }else{
	  	$this->form_caption = 'Ketetapan';
	  }
	   $dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$dt['id']."'"));
	   $brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dtTGR['f'].$dtTGR['g'].$dtTGR['h'].$dtTGR['i'].$dtTGR['j']."'")) ;
	   if ($dtTGR['tgl_usul']='0000-00-00'){
	      $dtTGR['tgl_usul']=Date('Y-m-d');
	   }
	   if ($dtTGR['tgl_sk']='0000-00-00'){
	      $dtTGR['tgl_sk']=Date('Y-m-d');
	   }
	   $kode_skpd = $dtTGR['f'].'.'.$dtTGR['g'].'.'.$dtTGR['h'].'.'.$dtTGR['i'].'.'.$dtTGR['j'] ;	   
       //items ----------------------
		  if ($this->form_fmST==0 || $this->form_fmST==1){
		  	$this->form_width = 850;
	 		$this->form_height = 280;
			
		  	$this->form_fields = array(	
			'no_usul' => array( 
								'label'=>'No Usulan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='no_usul' id='no_usul' value='".$dtTGR['no_usul']."'>"			 
									 ),
			'tgl_usul' => array( 
								'label'=>'Tanggal Usulan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_usul', $dtTGR['tgl_usul'], false,'','',$this->FormName),			 
									 ),
			'nama_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' value='$kode_skpd' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' value='".$brg['nm_barang']."' readonly=''>".
										 "&nbsp;<input type='button' value='cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
									 ),
			'tahun' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='tahun' id='tahun' value='".$dtTGR['tahun']."' size='15' readonly>"			 
									 ),
			'noreg' => array( 
								'label'=>'No Register',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='noreg' id='noreg' value='".$dtTGR['noreg']."' readonly>"			 
									 ),
			'harga_perolehan' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>100, 
								'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".number_format($dt['harga_perolehan'], 2, ',' , '.')."' readonly=''>"			 
									 ),
			'kpd' => array( 
								'label'=>'Kepada',
								'labelWidth'=>100, 
								'value'=>"",
								'type'=>'',
								'pemisah'=>' '			 
									 ),
			'kepada_nama' => array( 
								'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kepada_nama' id='kepada_nama' value='".$dtTGR['kepada_nama']."'>"			 
									 ),	
			'kepada_alamat' => array( 
								'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kepada_alamat' id='kepada_alamat' value='".$dtTGR['kepada_alamat']."'>"			 
									 ),
			'uraian' => array( 
								'label'=>'Uraian',
								'labelWidth'=>100, 
								'value'=>"<textarea name='uraian' id='uraian' cols='83'>{$dtTGR['uraian']}</textarea>"			 
									 ),
			'ket' => array( 
								'label'=>'Keterangan',
								'labelWidth'=>100, 
								'value'=>"<textarea name='ket' id='ket' cols='83'>{$dtTGR['ket']}</textarea>"			 
									 ),				 
			);
			$simpan1="<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan1()' >";
		  }else{
		  	$this->form_width = 450;
	 		$this->form_height = 110;
		  	$this->form_fields = array(									 
			'tgl_gantirugi' => array( 
								'label'=>'Tanggal Pembukuan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_gantirugi', $dtTGR['tgl_gantirugi'], TRUE,'','',$this->FormName),			 
									 ),
			'no_sk' => array( 
								'label'=>'No Ketetapan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='no_sk' id='no_sk' value='".$dtTGR['no_sk']."'>"			 
									 ),
			'tgl_sk' => array( 
								'label'=>'Tanggal Ketetapan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_sk', $dtTGR['tgl_sk'], false,'','',$this->FormName),			 
									 ),
			'harga' => array( 
								'label'=>'Harga Ketetapan',
								'labelWidth'=>100, 
								'value'=>"Rp &nbsp;".inputFormatRibuan("harga", '',$dtTGR['harga']),		 
									 ),
			);
			$simpan1="<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan2()' >";
		  }
		  
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' value='".$dtTGR['id_bukuinduk']."' name='idbi' id='idbi'>".
			"<input type='hidden' value='".$dtTGR['c']."' name='c' id='c'>".
			"<input type='hidden' value='".$dtTGR['d']."' name='d' id='d'>".
			"<input type='hidden' value='".$dtTGR['e']."' name='e' id='e'>".
			"<input type='hidden' value='".$dtTGR['e1']."' name='e1' id='e1'>".
			"<input type='hidden' value='".$dtTGR['tgl_gantirugi']."' name='tgl_gantirugi' id='tgl_gantirugi'>".
			$simpan1.
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()'title='Batal kunjungan' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 
	 $headerTable =
			"<tr>
				<th class='th01' width='20' rowspan='2'>No.</th>
  	  			$Checkbox 		
   	   			<th class='th02' colspan='2'>Usulan</th>
				<th class='th01' rowspan='2'>Kode Barang / Nama Barang</th>
				<th class='th01' rowspan='2'>Tahun / Noreg</th>
				<th class='th02' colspan='2'>Spesifikasi Barang</th>
				<th class='th01' rowspan='2'>Kondisi</th>
				<th class='th01' rowspan='2'>Harga Perolehan</th>
				<th class='th02' colspan='2'>Kepada</th>
				<th class='th01' rowspan='2'>Uraian</th>
				<th class='th02' colspan='3'>SK TGR</th>
				<th class='th01' rowspan='2' width='200'>Keterangan</th>
			</tr>
				
			<tr>
				<th class='th01' >No</th>
				<th class='th01' >Tanggal</th>
				<th class='th01' >Merk</th>
				<th class='th01' >No. Pabrik</th>
				<th class='th01' >Nama</th>
				<th class='th01' >Alamat</th>
				<th class='th01' >No</th>
				<th class='th01' >Tanggal</th>
				<th class='th01' >Harga</th>
				
			</tr>
				";
	
	return $headerTable;
	}	

	function setKolomData($no, $isi, $Mode, $CheckBox){
	 global $Ref;
	 global $Main;
	  $dt1=mysql_fetch_array(mysql_query("select * from kib_b where idbi='".$isi['id_bukuinduk']."'"));
	  $dtbi=mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'"));
	  $dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$isi['id']."'"));
	  $dt2=mysql_fetch_array(mysql_query("select * from ref_barang where f='".$dtTGR['f']."' 
		        and g='".$dtTGR['g']."' and h='".$dtTGR['h']."' and i='".$dtTGR['i']."' and j='".$dtTGR['j']."'"));
	  $harga_perolehan = number_format($isi['harga_perolehan'], 2, ',' , '.');
	  $harga_ketetapan = number_format($isi['harga_sk'], 2, ',' , '.');
	  $kode_barang=$dtTGR['f'].'.'.$dtTGR['g'].'.'.$dtTGR['h'].'.'.$dtTGR['i'].'.'.$dtTGR['j'] ;
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $CheckBox);
	 $Koloms[] = array('align="left" ',$dtTGR['no_usul']);
	 $Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_usul']));
	 $Koloms[] = array('align="left" ',$kode_barang." / ". $dt2['nm_barang']);
 	 $Koloms[] = array('align="left" ',$dtTGR['tahun']." / ". $dtTGR['noreg']);
	 $Koloms[] = array('align="left" ',$dt1['merk']);
	 $Koloms[] = array('align="left" ',$dt1['no_pabrik']);
	 $Koloms[] = array('align="left" ',$Main->KondisiBarang[$dtbi['kondisi']-1][1]);
	 $Koloms[] = array('align="right" ',$harga_perolehan);
	 $Koloms[] = array('align="left" ',$dtTGR['kepada_nama']);
	 $Koloms[] = array('align="left" ',$dtTGR['kepada_alamat']);
	 $Koloms[] = array('align="left" ',$dtTGR['uraian']);
	 $Koloms[] = array('align="left" ',$dtTGR['no_sk']);
	 $Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_sk']));
	 $Koloms[] = array('align="right" ',$harga_ketetapan);
	 $Koloms[] = array('align="left" ',$dtTGR['ket']);	 	 	 	 
	 return $Koloms;
	 
	}

	
	function setPage_HeaderOther(){
		$Pg = $_REQUEST['Pg'];
		
		$gantirugi = '';
		$pembayaran = '';
		$progres='';
		switch ($Pg){
			case 'gantirugi': $gantirugi ="style='color:blue;'"; break;
			case 'gantirugibayar': $pembayaran ="style='color:blue;'"; break;
			case 'gantirugiprogres': $progres ="style='color:blue;'"; break;
		}
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=gantirugi\" title='TGR' $gantirugi>Tuntutan Ganti Rugi</a> |
			<A href=\"pages.php?Pg=gantirugibayar\" title='Pembayaran'  $pembayaran>Pembayaran</a> |
			<A href=\"pages.php?Pg=gantirugiprogres\" title='Pelaporan'  $progres>Pelaporan</a>    												
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 //get pilih bidang
		
	//Get data
		$fmkode_barang=$_REQUEST['fmkode_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
	
				
	$TampilOpt = 
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1) . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					"Kode Barang &nbsp;"
					."<input type='text'  size='12' value='$fmkode_barang' id='fmkode_barang' name='fmkode_barang'>
					&nbsp; Tahun &nbsp;" 
					 ."<input type='text'  size='12' value='$fmtahun' id='fmtahun' name='fmtahun'>
					 &nbsp; No. Reg &nbsp;" 
					 ."<input type='text'  size='12' value='$fmnoreg' id='fmnoreg' name='fmnoreg'>
					 &nbsp; No. SK &nbsp;" 
					 ."<input type='text'  size='12' value='$fmnoketetapan' id='fmnoketetapan' name='fmnoketetapan'>"
					 ."<input type='hidden'  size='12' value='$fmstat' id='fmstat' name='fmstat'>"
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
	 //get pilih bidang
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');

	//Get data
		$fmkode_barang=$_REQUEST['fmkode_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
		$fmstat=1;
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "(select c from gantirugi where id= v_gantirugi.id)  like '%$fmSKPD%'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "(select d from gantirugi where id= v_gantirugi.id)  like '%$fmUNIT%'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "(select e from gantirugi where id= v_gantirugi.id)  like '%$fmSUBUNIT%'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "(select e1 from gantirugi where id= v_gantirugi.id)  like '%$fmSEKSI%'";
		if(!empty($fmstat)) $arrKondisi[]= " (select stat from gantirugi where id= v_gantirugi.id)  like '%$fmstat%'";
		if(!empty($fmkode_barang)) $arrKondisi[]= " (select concat(f,'.',g,'.',h,'.',i,'.',j) from gantirugi where id= v_gantirugi.id)  like '$fmkode_barang%'";
		if(!empty($fmtahun)) $arrKondisi[]= " (select tahun from gantirugi where id= v_gantirugi.id) like '%$fmtahun%'";
		if(!empty($fmnoreg)) $arrKondisi[]= " (select noreg from gantirugi where id= v_gantirugi.id) like '%$fmnoreg%'";
		if(!empty($fmnoketetapan)) $arrKondisi[]= " (select no_sk from gantirugi where id= v_gantirugi.id) like '%$fmnoketetapan%' ";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		/*
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}*/
			
			$Order= join(',',$arrOrders);	
			$OrderDefault =' ';// Order By no_terima desc ';
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
$gantirugi_cari = new gantirugi_cariObj();

?>