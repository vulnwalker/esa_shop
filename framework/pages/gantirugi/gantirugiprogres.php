<?php

class gantirugiprogresObj  extends DaftarObj2{	
	var $Prefix = 'gantirugiprogres';
	//var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_gantirugi'; //daftar
	var $TblName_Hapus = 'gantirugi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('harga_perolehan','harga_sk','jml_bayar','sisa');//array('jml_harga');
	var $SumValue = array();
	var $totalCol = 20; //total kolom daftar
	var $FieldSum_Cp1 = array();//berdasar mode
	var $FieldSum_Cp2 = array();
	var $fieldSum_lokasi = array(10,16,17,18);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='pelaporan ganti rugi.xls';
	var $Cetak_Judul = 'Pelaporan Ganti Rugi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'gantirugiprogresForm'; 	
	var $noModul=12; 
			
	function setTitle(){
		return 'Pelaporan';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".penghapusan()","delete_f2.png","Penghapusan", 'penghapusan','','','','',"style='width:80'")."</td>".
			
			"</td>";
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
		case 'penghapusan':{
					$get= $this->penghapusan();
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
	
	function penghapusan(){//id -> multi id with space delimiter
		global $HTTP_COOKIE_VARS;
		$err = ''; $cek=''; $content='';
		 $uid = $HTTP_COOKIE_VARS['coID'];
		 $tgl=date('Y/m/d h:i:s');
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		$KeyValue = explode(' ',$cbid);
		//$cekdata=mysql_fetch_array(mysql_query("select * from v_gantirugi_bayar where ref_idgantirugi='".$this->form_idplh."'"));
		$cekdata=mysql_fetch_array(mysql_query("select * from v_gantirugi where id='".$this->form_idplh."'"));
		if($cekdata['sisa']!=0 ){
			$err="Tidak bisa dihapus, Pembayaran belum Lunas!";
		}
		$cek.='sisa='.$cekdata['sisa'];
		if($err==''){
		$aqry= "Update gantirugi set stat='2', uid='$uid', tgl_update='$tgl' where id='".$this->form_idplh."'"; $cek.=$aqry;
		$qry = mysql_query($aqry);
		if ($qry==FALSE){
			$err = 'Gagal Hapus Data '.mysql_error();
		}
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
						WilSKPD_ajx($this->Prefix.'CariSkpd') . 
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
		$dt['biaya_pemanfaatan']=0;
		$dt['tgl_pemanfaatan'] = Date('Y-m-d');
		$dt['surat_tgl'] = Date('Y-m-d');
		$dt['tgl_pemanfaatan_akhir'] = Date('Y-m-d');				
		if ($err=='') $fm = $this->setForm($dt);	
			
		return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
   	
	function setFormKetetapan(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 2;
		$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
		$qry=mysql_query($aqry);
		$dt=mysql_fetch_array($qry);
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
	   $kode_skpd = $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;	   
       //items ----------------------
		  if ($this->form_fmST==0 || $this->form_fmST==1){
		  	$this->form_width = 850;
	 		$this->form_height = 280;
			
		  	$this->form_fields = array(	
			'no_usul' => array( 
								'label'=>'No Usulan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='no_usul' id='no_usul' value='".$dt['no_usul']."'>"			 
									 ),
			'tgl_usul' => array( 
								'label'=>'Tanggal Usulan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_usul', $dt['tgl_usul'], false,'','',$this->FormName),			 
									 ),
			'nama_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' value='$kode_skpd' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' value='".$dt['nm_barang']."' readonly=''>".
										 "&nbsp;<input type='button' value='cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
									 ),
			'tahun' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='tahun' id='tahun' value='".$dt['tahun']."' size='15' readonly>"			 
									 ),
			'noreg' => array( 
								'label'=>'No Register',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='noreg' id='noreg' value='".$dt['noreg']."' readonly>"			 
									 ),
			'harga_perolehan' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>100, 
								'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".$dt['harga_perolehan']."' readonly=''>"			 
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
								'value'=>"<input type='text' name='kepada_nama' id='kepada_nama' value='".$dt['kepada_nama']."'>"			 
									 ),	
			'kepada_alamat' => array( 
								'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kepada_alamat' id='kepada_alamat' value='".$dt['kepada_alamat']."'>"			 
									 ),
			'uraian' => array( 
								'label'=>'Uraian',
								'labelWidth'=>100, 
								'value'=>"<textarea name='uraian' id='uraian' cols='83'>{$dt['uraian']}</textarea>"			 
									 ),
			'ket' => array( 
								'label'=>'Keterangan',
								'labelWidth'=>100, 
								'value'=>"<textarea name='ket' id='ket' cols='83'>{$dt['ket']}</textarea>"			 
									 ),				 
			);
		  }else{
		  	$this->form_width = 450;
	 		$this->form_height = 110;
		  	$this->form_fields = array(									 
			'tgl_gantirugi' => array( 
								'label'=>'Tanggal Pembukuan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_gantirugi', $dt['tgl_gantirugi'], TRUE,'','',$this->FormName),			 
									 ),
			'no_sk' => array( 
								'label'=>'No Ketetapan',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='no_sk' id='no_sk' value='".$dt['no_sk']."'>"			 
									 ),
			'tgl_sk' => array( 
								'label'=>'Tanggal Ketetapan',
								'labelWidth'=>100,
								'value'=>createEntryTgl( 'tgl_sk', $dt['tgl_sk'], false,'','',$this->FormName),			 
									 ),
			'harga' => array( 
								'label'=>'Harga Ketetapan',
								'labelWidth'=>100, 
								'value'=>"Rp &nbsp;".inputFormatRibuan("harga", '',$dt['harga']),		 
									 ),
			);
		  }
		  
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' value='".$dt['id_bukuinduk']."' name='idbi' id='idbi'>".
			"<input type='hidden' value='".$dt['c']."' name='c' id='c'>".
			"<input type='hidden' value='".$dt['d']."' name='d' id='d'>".
			"<input type='hidden' value='".$dt['e']."' name='e' id='e'>".
			"<input type='hidden' value='".$dt['e1']."' name='e1' id='e1'>".
			"<input type='hidden' value='".$dt['tgl_gantirugi']."' name='tgl_gantirugi' id='tgl_gantirugi'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan1()' title='Batal kunjungan' >".
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
   	   			<th class='th02' colspan='2'>Usulan</th>
				<th class='th01' rowspan='2'>Kode Barang / Nama Barang/<br>ID Barang</th>
				<th class='th01' rowspan='2'>Tahun / Noreg</th>
				<th class='th02' colspan='2'>Spesifikasi Barang</th>
				<th class='th01' rowspan='2'>Kondisi</th>
				<th class='th01' rowspan='2'>Harga Perolehan</th>
				<th class='th02' colspan='2'>Yang Bertanggung Jawab</th>
				<th class='th01' rowspan='2'>Uraian</th>
				<th class='th02' colspan='3'>Ketetapan</th>
				<th class='th02' colspan='2'>Pembayaran</th>
				<!--<th class='th01' rowspan='2'>Status</th>-->
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
				<th class='th01' >Sudah Bayar</th>
				<th class='th01' >Sisa Bayar</th>
				
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
	  $fjml_bayar=number_format($isi['jml_bayar'], 2, ',' , '.');
	  $fsisa_bayar=number_format($isi['harga_sk']-$isi['jml_bayar'], 2, ',' , '.');
	  $kode_barang=$dtTGR['f'].'.'.$dtTGR['g'].'.'.$dtTGR['h'].'.'.$dtTGR['i'].'.'.$dtTGR['j'] ;
	  $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $CheckBox);
	 $Koloms[] = array('align="left" ',$dtTGR['no_usul']);
	 $Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_usul']));
	 $Koloms[] = array('align="left" ',$kode_barang." / ". $dt2['nm_barang']." /<br> ".$isi['id_bukuinduk']);
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
	 $Koloms[] = array('align="right" ',$fjml_bayar);
	 $Koloms[] = array('align="right" ',$fsisa_bayar);
	 //$Koloms[] = array('align="left" ',$Main->StatusTGR[$dtTGR['stat']][1]);	
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
		$fmid_barang=$_REQUEST['fmid_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
		$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
	if($Main->VERSI_NAME != 'JABAR'){
		$wil_skpd=WilSKPD_ajx3($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
	}else{
		$wil_skpd=WilSKPD_ajx($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
	}			
	$TampilOpt = 
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . $wil_skpd . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					"Kode Barang &nbsp;"
					."<input type='text'  size='15' value='$fmkode_barang' id='fmkode_barang' name='fmkode_barang'>
					Id Barang &nbsp;"
					."<input type='text'  size='12' value='$fmid_barang' id='fmid_barang' name='fmid_barang'>
					&nbsp; Tahun &nbsp;" 
					 ."<input type='text'  size='4' value='$fmtahun' id='fmtahun' name='fmtahun'>
					 &nbsp; Noreg &nbsp;" 
					 ."<input type='text'  size='12' value='$fmnoreg' id='fmnoreg' name='fmnoreg'>
					 &nbsp; No Ketetapan &nbsp;" 
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
		$fmkode_barang=$_REQUEST['fmkode_barang'];
		$fmid_barang=$_REQUEST['fmid_barang'];
		$fmtahun=$_REQUEST['fmtahun'];
		$fmnoreg=$_REQUEST['fmnoreg'];
		$fmnoketetapan=$_REQUEST['fmnoketetapan'];
		$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "(select c from gantirugi where id= v_gantirugi.id)  like '%$fmSKPD%'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "(select d from gantirugi where id= v_gantirugi.id)  like '%$fmUNIT%'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "(select e from gantirugi where id= v_gantirugi.id)  like '%$fmSUBUNIT%'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "(select e1 from gantirugi where id= v_gantirugi.id)  like '%$fmSEKSI%'";
		
		if(!empty($fmkode_barang)) $arrKondisi[]= " (select concat(f,'.',g,'.',h,'.',i,'.',j) from gantirugi where id= v_gantirugi.id)  like '$fmkode_barang%'";
		if(!empty($fmid_barang)) $arrKondisi[]= " (select id_bukuinduk from gantirugi where id= v_gantirugi.id)  like '$fmid_barang%'";
		if(!empty($fmtahun)) $arrKondisi[]= " (select tahun from gantirugi where id= v_gantirugi.id) like '%$fmtahun%'";
		if(!empty($fmnoreg)) $arrKondisi[]= " (select noreg from gantirugi where id= v_gantirugi.id) like '%$fmnoreg%'";
		if(!empty($fmnoketetapan)) $arrKondisi[]= " (select no_sk from gantirugi where id= v_gantirugi.id) like '%$fmnoketetapan%' ";
		if(!empty($fmtahunanggaran)) $arrKondisi[]= " (select year(tgl_sk) from gantirugi where id= v_gantirugi.id) <= '$fmtahunanggaran' ";
				
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		/*switch($fmORDER1){
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
$gantirugiprogres = new gantirugiprogresObj();

?>