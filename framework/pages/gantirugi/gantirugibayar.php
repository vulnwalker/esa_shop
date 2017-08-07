<?php

class gantirugibayarObj  extends DaftarObj2{	
	var $Prefix = 'gantirugibayar';
	//var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_gantirugi_bayar'; //daftar
	var $TblName_Hapus = 'gantirugi_bayar';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	//var $FieldSum = array('harga_sk','harga_perolehan','jml_bayar');//array('jml_harga');
	var $FieldSum = array('harga_ketetapan','jml_harga','harga');//array('jml_harga');
	var $SumValue = array();
	var $totalCol = 16; //total kolom daftar
	var $FieldSum_Cp1 = array(11, 10,10);//berdasar mode
	var $FieldSum_Cp2 = array( 3, 3, 3);
	var $fieldSum_lokasi = array(5,10,12);	
	//var $fieldSum_lokasi = array(12);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Pembayaran Ganti Rugi.xls';
	var $Cetak_Judul = 'Pembayaran Ganti Rugi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'gantirugibayarForm'; 	
	var $noModul=12; 
			
	function setTitle(){
		return 'Pembayaran';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 $tabel='gantirugi_bayar';
	 //get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$kode_barang=$_REQUEST['fmIDBARANG'];
		$expkdbarang=explode('.',$kode_barang);
		$f=$expkdbarang['0']; $g=$expkdbarang['1']; $h=$expkdbarang['2']; $i=$expkdbarang['3']; $j=$expkdbarang['4'];
		$ref_idgantirugi=$_REQUEST['ref_idgantirugi'];
		$tgl_sk=$_REQUEST['tgl_sk'];
		$dari_nama=$_REQUEST['dari_nama'];
		//$dari_alamat=$_REQUEST['dari_alamat'];
		//$penerima=$_REQUEST['penerima'];
		$harga=$_REQUEST['harga'];
		$bayar=$_REQUEST['bayar'];
		$ket=$_REQUEST['ket'];
		//$tgl=date('Y/m/d h:i:s');
		$tgl=$_REQUEST['tgl_bayar'];
		$tgl_pembukuan=$_REQUEST['tgl_pembukuan'];
		$id_bukuinduk=$_REQUEST['idbi'];
		$kd_skpd=$_REQUEST['kd_skpd'];
		$expkdskpd=explode('.',$kd_skpd);
		$c=$expkdskpd['0']; $d=$expkdskpd['1']; $e=$expkdskpd['2']; $e1=$expkdskpd['3'];
		
			/*if ($penerima==''){
				$err="Penerima Tidak Boleh Kosong";
			}*/
			if ($tgl_pembukuan=='' && $err=='')$err="Tanggal Pembukuan belum di isi !";
			if ($dari_nama=='' && $err==''){
				$err="Bukti Pembayaran Tidak Boleh Kosong!";
			}
			if ($bayar=='' && $err==''){
				$err="Jumlah Bayar Tidak Boleh Kosong!";
			}
			if ($f=='' && $g=='' && $h=='' && $i=='' && $j=='' && $err==''){
				$err="Kode Barang Tidak Boleh Kosong!";
				}
		//$dtTGR=mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$ref_idgantirugi."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select harga,tgl_sk from gantirugi where id='".$ref_idgantirugi."'"));
			$harga_SK=$dtTGR['harga'];
			if (compareTanggal($tgl,$dtTGR['tgl_sk']) == 0){
				$err="Tanggal bayar harus lebih besar dari tanggal sk !";
			}
			if (compareTanggal($tgl_pembukuan,$dtTGR['tgl_sk']) == 0){
				$err="Tanggal pembukuan harus lebih besar dari tanggal sk !";
			}	
		if($err=='' && $fmST == 0){
			$bayar=$_REQUEST['bayar'];
			$dtTGR=mysql_fetch_array(mysql_query("select sum(harga)as sudah_bayar from gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'"));
			$sudah_bayar=$dtTGR['sudah_bayar'];
			
			$sisa=$harga_SK-($sudah_bayar+$bayar);
			if($sisa<0){
				$err="Harga Bayar Melebihi Sisa !";
			}
			if ($err==''){
			$aqry="insert into $tabel (ref_idgantirugi,id_bukuinduk,a1,a,b,c,d,e,e1,tgl,dari_nama,harga,tgl_pembukuan,ket,uid,tgl_update) values
						('$ref_idgantirugi','$id_bukuinduk','".$Main->DEF_KEPEMILIKAN."','".$Main->DEF_PROPINSI."','".$Main->DEF_WILAYAH."','$c','$d','$e','$e1','$tgl','$dari_nama','$bayar','$tgl_pembukuan','$ket','$uid','$tgl')";$cek.=$aqry;
			$qry=mysql_query($aqry);
			if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();
			/* $aqry1="Update buku_induk set
			        staset='6',
					stusul='0',
					status_barang='5'
					where id='$id_bukuinduk';					
					";$cek.=$aqry1;
		$qry1=mysql_query($aqry1);
		if($qry1==FALSE) $err="Gagal Simpan data ".mysql_error();*/
			}
		}else{
			$bayar=$_REQUEST['bayar'];
			
			//$dtTGR=mysql_fetch_array(mysql_query("select * from v_gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select harga from gantirugi where id='".$ref_idgantirugi."'"));
			$harga_SK=$dtTGR['harga'];
			
			//$dtTGRB=mysql_fetch_array(mysql_query("select * from gantirugi_bayar where id='".$dtTGR['id']."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select sum(harga)as bayar from gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'  and  id<>'".$idplh."'"));
			
			$sudah_bayar=$dtTGR['jml_bayar'];
			$sisa=$harga_SK-($sudah_bayar+$bayar);
			if($sisa<0){
				$err="Harga Bayar Melebihi Sisa !";
			}
			if ($err==''){
			$aqry="UPDATE $tabel set
				dari_nama='$dari_nama',
				dari_alamat='$dari_alamat',
				harga='$bayar',
				penerima='$penerima',
				ket='$ket',
				uid='$uid',
				tgl_pembukuan='$tgl_pembukuan',
				tgl_update='$tgl'
			where id='$idplh'";$cek.=$aqry;
		$qry=mysql_query($aqry);
		if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();}
	}
			
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	function cekbayar(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	    $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	    $ref_idgantirugi=$_REQUEST['ref_idgantirugi'];
		$idpilih = $_REQUEST['gantirugibayar_idplh'];
		
		if($fmST == 0){//baru
			$bayar=$_REQUEST['bayar'];
			
			//$dtTGR=mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$ref_idgantirugi."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select harga from gantirugi where id='".$ref_idgantirugi."'"));
			$harga_SK=$dtTGR['harga'];
			
			
			$dtTGR=mysql_fetch_array(mysql_query("select sum(harga)as sudah_bayar from gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'"));
			$sudah_bayar=$dtTGR['sudah_bayar'];
			
			$sisa=$harga_SK-($sudah_bayar+$bayar);
			
			if($sisa<0){
			$err="Harga Bayar Melebihi Sisa !";
			}
		}else{
			//$dtTGR=mysql_fetch_array(mysql_query("select * from v_gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$ref_idgantirugi."'"));
			$harga_SK=$dtTGR['harga'];
			//$dtTGR=mysql_fetch_array(mysql_query("select sum(harga)as sudah_bayar from gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'  and  id<>'".$idpilih."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select sum(harga)as sudah_bayar from gantirugi_bayar where ref_idgantirugi='".$ref_idgantirugi."'"));
			$bayar=$_REQUEST['bayar'];
			//$dtTGRB=mysql_fetch_array(mysql_query("select * from gantirugi_bayar where id='".$dtTGR['id']."'"));
			$sudah_bayar=$dtTGR['sudah_bayar'];
			$sisa=$harga_SK-($sudah_bayar+$bayar);
			if($sisa<0){
			$err="Harga Bayar Melebihi Sisa !";
			}
			
		}	
		$cek .= 'bayar ='.$bayar.' sudah_bayar='.$sudah_bayar.' harga_SK='.$harga_SK.' sisa='.$sisa;
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }
	function simpanPilih(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//$coDaftar = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];$cek .=$coDaftar;

		//$ids= explode(',',$coDaftar); //$_POST['cidBI'];	//id bi barang
		$ids = $_REQUEST['gantirugi_cari_cb'];
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
		if($err==''){
			$tgr = mysql_fetch_array(mysql_query("select stat from gantirugi where id='".$ids[0]."'")) ;
			$tgr1=mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$ids[0]."'")) ;
			$sisa=$tgr1['harga_sk']-$tgr1['jml_bayar'];
			if ($sisa==0 && $tgr['stat']==1){
				$err = 'Barang yang dipilih Sudah Lunas! ';
			}
			if ($tgr['stat']==0 && $err==''){
				$err = 'Barang yang dipilih masih dalam usulan!';
			}
			if ($tgr['stat']==2){
				$err = 'Tuntutan Ganti Rugi Barang yang dipilih Sudah Dihapus!';
			}
		}	
		if($err==''){
			$tgr = mysql_fetch_array(mysql_query("select stat from gantirugi where id='".$ids[0]."'")) ;
			$tgr1=mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$ids[0]."'")) ;
			$sisa=$tgr1['harga_sk']-$tgr1['jml_bayar'];
			/*if ($sisa==0 && $tgr['stat']==1){
				$err = 'Barang yang dipilih Sudah Lunas! ';
			}
			if ($tgr['stat']==0 && $err==''){
				$err = 'Barang yang dipilih masih dalam usulan!';
			}
			if ($tgr['stat']==2){
				$err = 'Tuntutan Ganti Rugi Barang yang dipilih Sudah Dihapus!';
			}*/
		}	
		if($err==''){
			$tgr = mysql_fetch_array(mysql_query("select * from gantirugi where id='".$ids[0]."'")) ;
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$tgr['f'].$tgr['g'].$tgr['h'].$tgr['i'].$tgr['j']."'")) ;
			$tgr1= mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$ids[0]."'")) ;
			$tgl_sk=explode('-',$tgr['tgl_sk']);
			$tgl_sk_thn=$tgl_sk[0];
			$tgl_sk_tgl=$tgl_sk[2];
			$sisa=$tgr1['sisa'];			
			$content->fmIDBARANG = $tgr['f'].'.'.$tgr['g'].'.'.$tgr['h'].'.'.$tgr['i'].'.'.$tgr['j'] ;// '1';
			$content->fmNMBARANG = $brg['nm_barang'];
			$content->no_sk = $tgr['no_sk'];
			if(substr($tgl_sk_tgl,0,1)=='0'){
				$tgl_sk_tgl=substr($tgl_sk_tgl,1);
			}
			$tgl_sk_bln=$tgl_sk[1];
			$content->tgl_sk_thn = $tgl_sk_thn;
			$content->tgl_sk_tgl = $tgl_sk_tgl;
			$content->tgl_sk_bln = $tgl_sk_bln;
			//$content->tgl_sk = $tgr['tgl_sk'];
			$content->noreg = $tgr['noreg'];
			$content->tahun = $tgr['tahun'];
			$content->harga = number_format($tgr['harga'],'2',',','.');
			$content->harga_perolehan = number_format($tgr1['harga_perolehan'],'2',',','.');
			$content->jml_bayar = number_format($tgr1['jml_bayar'],'2',',','.');
			if($sisa==''){
				$sisa=$tgr1['harga_sk']-$tgr1['jml_bayar'];
			}
			$content->sisa_bayar = number_format($sisa,'2',',','.');
			$content->ref_idgantirugi = $ids[0];
			$content->idbi = $tgr['id_bukuinduk'];
			$content->kd_skpd = $tgr['c'].'.'.$tgr['d'].'.'.$tgr['e'].'.'.$tgr['e1'];			
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
		
		case 'hapus':{				
			$fm = $this->hapus();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}	
		
		case 'formCari':{				
				$fm = $this->formCari();				
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
	   
	   case 'cekbayar':{
			$get= $this->cekbayar();
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
			 "<script type='text/javascript' src='js/gantirugi/gantirugi_cari.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/gantirugi/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			
			$scriptload;
	}
	
	//form ==================================
	function formCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
						
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'gantirugiCariForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Tuntutan Ganti Rugi'; //judul form
		$this->form_fields = array(	
			'skpd' => array( 
				'label'=>'',
				'value'=>
					"<div style=\"float: left; width: 90%; height: auto; padding: 4px;\">
					<table width=\"100%\" class=\"gantirugiCariForm\">	<tr>		
					<td width=\"100%\" valign=\"top\">" . 					
						WilSKPD_ajx3('gantirugiCariSkpd','100%',100,FALSE,'','','',TRUE,'',1) . 
						//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr>
					<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='gantirugi_cari.refreshList(true)'>
			</td></table></div>", 
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
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 0;
		$dt['readonly']='';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;
		$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
		$qry=mysql_query($aqry);
		$dt=mysql_fetch_array($qry);
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 global $HTTP_COOKIE_VARS;
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $tgl_buku =	$thn_login.'-00-00';
	 $form_name = $this->Prefix.'_form';				
	 
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
	  	$this->form_caption = 'EDIT';
		$aqry1 = "select * from gantirugi where id='".$dt['ref_idgantirugi']."'"; $cek.=$aqry1;
		$qry1=mysql_query($aqry1);
		$dt1=mysql_fetch_array($qry1);
		
		$dtpembayaran=mysql_fetch_array(mysql_query("select * from gantirugi_bayar where id='".$dt['id']."'"));
		  $kode_skpd = $dt1['f'].'.'.$dt1['g'].'.'.$dt1['h'].'.'.$dt1['i'].'.'.$dt1['j'] ;
		  $qrbrg = "select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j)='". $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j']."'";
		  $brg = mysql_fetch_array(mysql_query( $qrbrg  ));
		  $get = mysql_fetch_array(mysql_query(
		     //"select sum(harga) as sudahbayar from gantirugi_bayar where id<>'".$dt['id']."' "
		     "select sum(harga) as sudahbayar from gantirugi_bayar where ref_idgantirugi='".$dt['ref_idgantirugi']."' "
		  ));
		  $sudahbayar = $get['sudahbayar'];
		  $sisabayar = $dt1['harga'] - $sudahbayar;
		
	  }
	  if ($this->form_fmST==0) {
		$dtpembayaran['tgl_pembukuan']=$tgl_buku;
	  }else{
	  	$dtpembayaran['tgl_pembukuan']=$dtpembayaran['tgl_pembukuan'];
	  }
	  if ($dtpembayaran['tgl']='0000-00-00'){
	      $dtpembayaran['tgl']=Date('Y-m-d');
	      
	   } 	   
       //items ----------------------
		  	$this->form_width = 720;
	 		$this->form_height = 350;
			
		  	$this->form_fields = array(									 
			'no_sk' => array( 
								'label'=>'No Ketetapan',
								'labelWidth'=>150, 
								'value'=>"<div style='float:left'><input type='text' name='no_sk' id='no_sk' value='".$dt1['no_sk']."' readonly=''>&nbsp; / &nbsp; </div>". 
										  createEntryTgl('tgl_sk', $dt1['tgl_sk'], TRUE,'','',$this->FormName)."&nbsp;
										  <input type='button' value='cari' onclick=\"".$this->Prefix.".caritgr()\" >"			 
									 ),
			'nama_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' value='".$kode_skpd."' readonly=''> &nbsp;
										  <input type='text' name='fmNMBARANG' id='fmNMBARANG' value='".$brg['nm_barang']."' readonly='' size='60'>"			 
									 ),
			'tahun' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='tahun' id='tahun' value='".$dt1['tahun']."' readonly=''>
											&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Noreg &nbsp; &nbsp; &nbsp; : &nbsp;
											<input type='text' name='noreg' id='noreg' value='".$dt1['noreg']."' readonly>"			 
									 ),
			/*'harga_perolehan' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>100, 
								'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".number_format($dt['harga_perolehan'], 2, ',' , '.')."' readonly>"			 
									 ),*/
			'harga' => array( 
								'label'=>'Jumlah Ketetapan',
								'labelWidth'=>150, 
								'value'=>"Rp &nbsp; <input type='text' name='harga' id='harga' value='".number_format($dt1['harga'], 2, ',' , '.')."' readonly=''>"			 
									 ),
			'jml_bayar' => array( 
								'label'=>'Sudah Bayar',
								'labelWidth'=>150, 
								'value'=>"Rp &nbsp; <input type='text' name='jml_bayar' id='jml_bayar' value='".number_format($sudahbayar, 2, ',' , '.')."' readonly>
											&nbsp; &nbsp; &nbsp; Sisa &nbsp; &nbsp; &nbsp; : &nbsp;
										  Rp &nbsp; <input type='text' name='sisa_bayar' id='sisa_bayar' value='".number_format($sisabayar, 2, ',' , '.')."' readonly>"														 
									 ),
			'tgl_pembukuan' => array( 
								'label'=>'Tanggal Pembukuan',
								'labelWidth'=>150, 
								'value'=>createEntryTgl('tgl_pembukuan', $dtpembayaran['tgl_pembukuan'], FALSE,'','',$this->FormName,'2'),			 
									 ),	
			'tgl_bayar' => array( 
								'label'=>'Tanggal Bayar',
								'labelWidth'=>150, 
								'value'=>createEntryTgl('tgl_bayar', $dtpembayaran['tgl'], FALSE,'','',$this->FormName),			 
									 ),						 
			'bayar' => array( 
								'label'=>'Jumlah Bayar',
								'labelWidth'=>150, 
								'value'=>"Rp &nbsp; ".inputFormatRibuan("bayar", "onchange=\"".$this->Prefix.".cekbayar()\"",$dtpembayaran['harga']),			 
									 ),
			'dari_nama' => array( 
								'label'=>'Bukti Pembayaran',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='dari_nama' id='dari_nama' value='".$dtpembayaran['dari_nama']."'>"			 
									 ),
			
			/*'diterima' => array( 
								'label'=>'Diterima Dari',
								'labelWidth'=>100, 
								'value'=>"",
								'type'=>'',
								'pemisah'=>' '			 
									 ),*/
				
			/*'dari_alamat' => array( 
								'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',
								'labelWidth'=>100, 
								'value'=>"<textarea name='dari_alamat' id='dari_alamat' cols='90'>{$dtpembayaran['dari_alamat']}</textarea>"			 
									 ),
			'penerima' => array( 
								'label'=>'penerima',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='penerima' id='penerima' value='".$dtpembayaran['penerima']."'>"			 
									 ),*/
			'ket' => array( 
								'label'=>'Keterangan',
								'labelWidth'=>150, 
								'value'=>"<textarea name='ket' id='ket' cols='90'>{$dtpembayaran['ket']}</textarea>"			 
									 ),				 
			);
		 		  
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' name='ref_idgantirugi' id='ref_idgantirugi' value='".$dtpembayaran['ref_idgantirugi']."' readonly=''>".
			"<input type='hidden' value='".$dtpembayaran['id_bukuinduk']."' name='idbi' id='idbi'>".
			"<input type='hidden' value='".$dtpembayaran['kd_skpd']."' name='kd_skpd' id='kd_skpd'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >&nbsp;".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
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
				<th class='th02' colspan='3'>SK TGR</th>
				<th class='th02' colspan='5'>Barang</th>
				<th class='th01' rowspan='2'>Tanggal Bayar</th>
				<th class='th01' rowspan='2'>Jumlah Bayar</th>
				<th class='th01' rowspan='2'>Bukti Pembayaran</th> 		
   	   			<th class='th01' rowspan='2'>Tanggal Pembukuan</th>
				<th class='th01' rowspan='2' width='200'>Keterangan</th>
			</tr>
				
			<tr>
				<th class='th01' >No</th>
				<th class='th01' >Tanggal</th>
				<th class='th01' >Harga</th>
				<th class='th01' >Kode Barang/<br>ID Barang</th>
				<th class='th01' >Nama Barang</th>
				<th class='th01' >Tahun</th>
				<th class='th01' >Noreg</th>
				<th class='th01' >Harga Perolehan</th>
				
			</tr>
				";
	
	return $headerTable;
	}	

	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	   	$dt1=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$isi['ref_idgantirugi']."'"));
		$dtpembayaran=mysql_fetch_array(mysql_query("select * from gantirugi_bayar where id='".$isi['id']."'"));
		$dt2=mysql_fetch_array(mysql_query("select * from ref_barang where f='".$dt1['f']."' 
		        and g='".$dt1['g']."' and h='".$dt1['h']."' and i='".$dt1['i']."' and j='".$dt1['j']."'"));
	if($Main->VERSI_NAME=='JABAR'){
	 	 $harga_perolehan = number_format($isi['harga_perolehan'], 2, ',' , '.');
	}else{
		$harga_perolehan = number_format($isi['jml_harga'], 2, ',' , '.');
	}
	  $harga_ketetapan = number_format($dt1['harga'], 2, ',' , '.');
	  $jml_pembayaran = number_format($dtpembayaran['harga'], 2, ',' , '.');
	  $kode_barang=$dt1['f'].'.'.$dt1['g'].'.'.$dt1['h'].'.'.$dt1['i'].'.'.$dt1['j'] ;
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left" ',$dt1['no_sk']);
	 $Koloms[] = array('align="left" ',$dt1['tgl_sk']);
	 $Koloms[] = array('align="right" ',$harga_ketetapan);
	 $Koloms[] = array('align="left" ',$kode_barang." / ".$isi['id_bukuinduk']);
	 $Koloms[] = array('align="left" ',$dt2['nm_barang']);
	 $Koloms[] = array('align="left" ',$dt1['tahun']);
	 $Koloms[] = array('align="left" ',$dt1['noreg']);
 	 $Koloms[] = array('align="right" ',$harga_perolehan);
	 $Koloms[] = array('align="left" ',$dtpembayaran['tgl']);
	 $Koloms[] = array('align="right" ',$jml_pembayaran);
	 $Koloms[] = array('align="left" ',$dtpembayaran['dari_nama']);
	 $Koloms[] = array('align="left" ',$dtpembayaran['tgl_pembukuan']);
	 $Koloms[] = array('align="left" ',$dtpembayaran['ket']);	 	 	 	 
	 return $Koloms;
	 
	}

	
	function setNavAtas(){
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
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
		$fmid_barang=$_REQUEST['fmid_barang'];
		$fmkode_barang=$_REQUEST['fmkode_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
		if($Main->VERSI_NAME != 'JABAR'){
			$wil_skpd=WilSKPD_ajx3($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
		}else{
			$wil_skpd=WilSKPD_ajx($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
		}		
	$TampilOpt = 
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" .$wil_skpd . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					"Kode Barang &nbsp;"
					."<input type='text'  size='15' value='$fmkode_barang' id='fmkode_barang' name='fmkode_barang'>
					Id Barang &nbsp;"
					."<input type='text'  size='10' value='$fmid_barang' id='fmid_barang' name='fmid_barang'>
					&nbsp; Tahun &nbsp;" 
					."<input type='text'  size='4' value='$fmtahun' id='fmtahun' name='fmtahun'>
					&nbsp; No. Reg &nbsp;" 
					."<input type='text'  size='12' value='$fmnoreg' id='fmnoreg' name='fmnoreg'>
					&nbsp; No. SK &nbsp;" 
					."<input type='text'  size='12' value='$fmnoketetapan' id='fmnoketetapan' name='fmnoketetapan'>
					&nbsp; Tahun Anggaran &nbsp;
					<input type='text'  size='4' value='$fmtahunanggaran' id='fmtahunanggaran' name='fmtahunanggaran'> &nbsp;"
					 
					
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
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
		$fmkode_barang=$_REQUEST['fmkode_barang'];
		$fmid_barang=$_REQUEST['fmid_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "(select c from gantirugi_bayar where id= v_gantirugi_bayar.id)  like '%$fmSKPD%'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "(select d from gantirugi_bayar where id= v_gantirugi_bayar.id)  like '%$fmUNIT%'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "(select e from gantirugi_bayar where id= v_gantirugi_bayar.id)  like '%$fmSUBUNIT%'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "(select e1 from gantirugi_bayar where id= v_gantirugi_bayar.id)  like '%$fmSEKSI%'";
		if(!empty($fmkode_barang)) $arrKondisi[]= " (select concat(f,'.',g,'.',h,'.',i,'.',j) from gantirugi where id= v_gantirugi_bayar.ref_idgantirugi)  like '$fmkode_barang%'";
		if(!empty($fmid_barang)) $arrKondisi[]= " (select id_bukuinduk from gantirugi where id= v_gantirugi_bayar.ref_idgantirugi)  like '$fmkode_barang%'";
		if(!empty($fmtahun)) $arrKondisi[]= " (select tahun from gantirugi where id= v_gantirugi_bayar.ref_idgantirugi) like '%$fmtahun%'";
		if(!empty($fmnoreg)) $arrKondisi[]= " (select noreg from gantirugi where id= v_gantirugi_bayar.ref_idgantirugi) like '%$fmnoreg%'";
		if(!empty($fmnoketetapan)) $arrKondisi[]= " (select no_sk from gantirugi where id= v_gantirugi_bayar.ref_idgantirugi) like '%$fmnoketetapan%' ";
		if(!empty($fmtahunanggaran)) $arrKondisi[]= " year(tgl_pembukuan) <= '$fmtahunanggaran'";
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
$gantirugibayar = new gantirugibayarObj();

?>