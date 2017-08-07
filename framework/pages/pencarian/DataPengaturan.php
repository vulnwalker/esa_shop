<?php

class DataPengaturanObj  extends DaftarObj2{	
	var $Prefix = 'DataPengaturan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_pengaturan'; //bonus
	var $TblName_Hapus = 't_pengaturan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PENGADAAN DAN PENERIMAAN';
	var $PageIcon = 'images/administrator/images/InformationSetting.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='DataPengaturan.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'DataPengaturan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'DataPengaturanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $FieldC1 = 0;
	var $arrEselon = array( 
		array('1','ESELON I'),
		array('2','ESELON II'),
		array('3','ESELON III'),
		array('4','ESELON IV'),
		array('5','ESELON V')
		);
		
	var $jns_trans = array(
			//array('selectAll','Semua'),	
			array('1','PENGADAAN BARANG'),	
			array('2','PEMELIHARAAN BARANG'),			
			);
			
	var $arr_pencairan_dana = array(
			array('1', "SPP-LS"),
			array('2', "SPP-UP"),
			array('3', "SPP-GU"),
			array('4', "SPP-TU"),
			);
			
	var $arr_cara_bayar = array(
			//array('selectAll','Semua'),	
			array('1','UANG MUKA'),	
			array('2','TERMIN'),			
			array('3','PELUNASAN'),			
			);
	
	var $CekDistribusi = 1; //1=Ya, 0=Tidak
			
	function setTitle(){
		return 'PENGATURAN PENGADAAN DAN PENERIMAAN';
	}
	
	function setMenuEdit(){
		return "";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if( $err=='' && $nama =='' ) $err= 'Satuan Belum Di Isi !!';
	 
			if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama)values('$nama')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_satuan set nama='$nama' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
					}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function SimpanUbah(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	
		$Idplh = addslashes($_REQUEST['IDNYA']);		
	 	$nama_bidang = addslashes($_REQUEST['nama_bidang']);
	 	$nama_skpd = addslashes($_REQUEST['nama_skpd']);
	 	$alamat = addslashes($_REQUEST['alamat']);
	 	$kota = addslashes($_REQUEST['kota']);
		
	 	$titimangsa_surat = addslashes($_REQUEST['titimangsa_surat']);
	 	$nama_aplikasi = addslashes($_REQUEST['nama_aplikasi']);
	 	$ver_skpd = addslashes($_REQUEST['ver_skpd']);
	 	$ver_kodebarang = addslashes($_REQUEST['ver_kodebarang']);
	 	$def_atrib = addslashes($_REQUEST['def_atrib']);
		
		if($err == '' && $nama_bidang == '')$err="Nama Bidang Belum di Isi !";
		if($err == '' && $nama_skpd == '')$err="Nama SKPD Belum di Isi !";
		if($err == '' && $alamat == '')$err="Alamat Belum di Isi !";
		if($err == '' && $kota == '')$err="Kota Belum di Isi !";
		
		if($err == '' && $titimangsa_surat == '')$err="Titimangsa Surat Belum di Isi !";
		if($err == '' && $nama_aplikasi == '')$err="Nama Aplikasi Belum di Isi !";
		if($err == '' && $ver_skpd == '')$err="Versi SKPD Belum di Pilih !";
		if($err == '' && $ver_kodebarang == '')$err="Versi Kode Barang Belum di Pilih !";
		if($err == '' && $def_atrib == '')$err="Setelan Otomatis Biaya Atribusi Belum di Pilih !";
		
		if($err == ''){
			$qry = "UPDATE ".$this->TblName." SET nama_bidang='$nama_bidang', nama_skpd='$nama_skpd', alamat='$alamat', kota='$kota', titimangsa_surat='$titimangsa_surat', nama_aplikasi='$nama_aplikasi', skpd='$ver_skpd', kode_barang='$ver_kodebarang', harga_atribusi='$def_atrib' WHERE Id='$Idplh'";$cek.=$qry;
			$aqry = mysql_query($qry);
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
		
		case 'UbahPengaturan':{				
			$fm = $this->UbahData();				
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
		
		case 'SimpanUbah':{
			$get= $this->SimpanUbah();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		
		case 'pilihan':{
				$fm = $this->setTemplate();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
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
			"<script type='text/javascript' src='js/pencarian/".$this->Prefix.".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pengadaanpenerimaan/pemasukan_ins.js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_satuan WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 300;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'nama' => array( 
						'label'=>'Satuan',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),			
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setPage_HeaderOther(){
	return "
	<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=pemasukan&halman=1\" title='PENGADAAN' >PENGADAAN</a> | 
			<A href=\"pages.php?Pg=pemasukan&halman=2\" title='PEMELIHARAAN' >PEMELIHARAAN</a> 
			&nbsp&nbsp&nbsp	
			</td>
		</tr>
	</table>";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5'>No.</th>".
	  	   /*$Checkbox*/"		
		   <th class='th01'>NAMA</th>
		   <th class='th01'>JUMLAH</th>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  /*if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);*/
	 $Koloms[] = array('align="left" width="15%"',"<a href='javascript:".$this->Prefix.".pilihan(`".$isi['id']."`)' >".$isi['nama']."</a>");
	 $Koloms[] = array('align="right"',number_format($isi['jumlah'],0,'.',','));
	 return $Koloms;
	}
	
	function setMenuView(){
		return 
			
			"";				
			
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	
	$daqry =$this->DataOption();
	
	//KODE SKPD
	$VerSKPD = '';
	if($daqry['skpd'] == '1')$VerSKPD = "PERMENDAGRI NO.17 TAHUN 2007";
	if($daqry['skpd'] == '2')$VerSKPD = "PERMENDAGRI NO.19 TAHUN 2016";
	
	//KODE BARANG
	$VerKodeBarang = '';
	if($daqry['kode_barang'] == '1')$VerKodeBarang = "PERMENDAGRI NO.17 TAHUN 2007";
	if($daqry['kode_barang'] == '2')$VerKodeBarang = "PERMENDAGRI NO.19 TAHUN 2016";
	
	//
	$harga_atribusi = 'TIDAK';
	if($daqry['harga_atribusi'] == 1)$harga_atribusi = 'YA';
	
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
			$vOrder=
			"<div id='DaftarPengaturan'>".
			genFilterBar(
				array(
					$this->isiform(
						array(
							array(
								'label'=>'<b>KEPADA YTH</b>',
								'pemisah'=>'',
							),
						)
					).						
					$this->isiform(
						array(
							array(
								'label'=>'Nama Bidang/bagian',
								'name'=>'nama_bidang',
								'label-width'=>'210px;',
								'value'=>$daqry['nama_bidang'],
							),
							array(
								'label'=>'Nama SKPD',
								'name'=>'nama_skpd',
								'label-width'=>'210px;',
								'value'=>$daqry['nama_skpd'],
							),
							array(
								'label'=>'Alamat',
								'name'=>'alamat',
								'label-width'=>'210px;',
								'value'=>$daqry['alamat'],
							),
							array(
								'label'=>'Kota/Kab',
								'name'=>'kota',
								'label-width'=>'210px;',
								'value'=>$daqry['kota'],
							),
						), "style='margin-left:20px;'"
					).
					$this->isiform(
						array(
							array(
								'label'=>'<b>TITIMANGSA SURAT</b>',
								'name'=>'titimangsa_surat',
								'label-width'=>'230px;',
								'value'=>$daqry['titimangsa_surat'],
							),
							array(
								'label'=>'<b>NAMA APLIKASI</b>',
								'name'=>'nama_aplikasi',
								'label-width'=>'230px;',
								'value'=>$daqry['nama_aplikasi'],
							),
							array(
								'label'=>'<b>VERSI SKPD</b>',
								'name'=>'ver_skpd',
								'label-width'=>'230px;',
								'value'=>$VerSKPD,
							),
							array(
								'label'=>'<b>VERSI KODE BARANG</b>',
								'name'=>'ver_kodebarang',
								'label-width'=>'230px;',
								'value'=>$VerKodeBarang,
							),
							array(
								'label'=>'<b>SETELAN OTOMATIS BIAYA ATRIBUSI</b>',
								'name'=>'harga_atribusi',
								'label-width'=>'230px;',
								'value'=>$harga_atribusi,
							),
						)
					).
					"<table>
						<tr>
							<td>".$this->buttonnya($this->Prefix.'.UbahData()','edit_f2.png','UBAH','UBAH','UBAH')."</td>
							
						</tr>".
					"</table>"
				),			
				'','').
				"</div>"
				;
			
			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function UbahData(){
		global $Ref, $Main;
		
		$arr_ver = array(
			//array('selectAll','Semua'),	
			array('1','PERMENDAGRI NO.17 TAHUN 2007'),	
			array('2','PERMENDAGRI NO.19 TAHUN 2016'),		
			);
			
		$arr_def = array(
			//array('selectAll','Semua'),	
			array('1','YA'),	
			array('0','TIDAK'),		
			);
			
		
		$daqry = $this->DataOption();
		
		//
		$harga_atribusi = 'TIDAK';
		if($daqry['harga_atribusi'] == 1)$harga_atribusi = 'YA';
			
		$cek='';$err='';
		$content = 
			genFilterBar(
				array(
					$this->isiform(
						array(
							array(
								'label'=>'<b>KEPADA YTH</b>',
								'pemisah'=>'',
							),
						)
					).						
					$this->isiform(
						array(
							array(
								'label'=>'Nama Bidang/bagian',
								'name'=>'nama_bidang',
								'label-width'=>'210px;',
								'type'=>'text',
								'value'=>$daqry['nama_bidang'],
								'parrams'=>"style='width:300px;'",
							),
							array(
								'label'=>'Nama SKPD',
								'name'=>'nama_skpd',
								'label-width'=>'210px;',
								'type'=>'text',
								'value'=>$daqry['nama_skpd'],
								'parrams'=>"style='width:300px;'",
							),
							array(
								'label'=>'Alamat',
								'name'=>'alamat',
								'label-width'=>'210px;',
								'value'=>"<textarea name='alamat' style='width:300px;height:50px;'>".$daqry['alamat']."</textarea>",
							),
							array(
								'label'=>'Kota/Kab',
								'name'=>'kota',
								'label-width'=>'210px;',
								'type'=>'text',
								'value'=>$daqry['kota'],
								'parrams'=>"style='width:300px;'",
							),
						), "style='margin-left:20px;'"
					).
					$this->isiform(
						array(
							array(
								'label'=>'<b>TITIMANGSA SURAT</b>',
								'name'=>'titimangsa_surat',
								'label-width'=>'230px;',
								'value'=>$daqry['titimangsa_surat'],
								'type'=>'text',
								'parrams'=>"style='width:300px;'",
							),
							array(
								'label'=>'<b>NAMA APLIKASI</b>',
								'name'=>'nama_aplikasi',
								'label-width'=>'230px;',
								'value'=>$daqry['nama_aplikasi'],
								'type'=>'text',
								'parrams'=>"style='width:300px;'",
							),
							array(
								'label'=>'<b>VERSI SKPD</b>',
								'name'=>'ver_skpd',
								'label-width'=>'230px;',
								'value'=>cmbArray('ver_skpd',$daqry['skpd'], $arr_ver,'--- PILIH VERSI ---',"style='width:300px;'"),
							),
							array(
								'label'=>'<b>VERSI KODE BARANG</b>',
								'name'=>'ver_kodebarang',
								'label-width'=>'230px;',
								'value'=>cmbArray('ver_kodebarang',$daqry['kode_barang'], $arr_ver,'--- PILIH VERSI ---',"style='width:300px;'"),
							),
							array(
								'label'=>'<b>SETELAN OTOMATIS BIAYA ATRIBUSI</b>',
								'name'=>'harga_atribusi',
								'label-width'=>'230px;',
								'value'=>cmbArray('def_atrib',$daqry['harga_atribusi'], $arr_def,'--- PILIH ---',"style='width:300px;'"),
							),
						)
					).
					"
					<input type='hidden' name='IDNYA' id='IDNYA' value='".$daqry['Id']."'/>
					<table>
						<tr>
							<td>".$this->buttonnya($this->Prefix.'.SimpanUbahData()','save_f2.png','SIMPAN','SIMPAN','SIMPAN')."</td>
							<td>".$this->buttonnya($this->Prefix.'.loading()','cancel_f2.png','BATAL','BATAL','BATAL')."</td>
							
						</tr>".
					"</table>"
				),			
				'','')
				;
				
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}		
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		
		if($fmPILCARIvalue !='')$arrKondisi[] = " nama like '%$fmPILCARIvalue%' ";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		/*if($fmORDER1 == ''){
			$arrOrders[] = " bk ";
			$arrOrders[] = " ck ";
			$arrOrders[] = " dk ";
			$arrOrders[] = " p ";
		}
		switch($fmORDER1){
			case '1': $arrOrders[] = " p $Asc1 " ;break;
		}	*/
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
	
	/*function setTopBar(){
	   	return '';
	}	*/
	
	function DataOption(){
		global $Main, $HTTP_COOKIE_VARS;
		$qry = "SELECT * FROM $this->TblName WHERE Id='1' ";
		$aqry = mysql_query($qry);
		
		$data = mysql_fetch_array($aqry);
		
		return $data;
	}
	
	function VPenerima_det(){
		$Data = $this->DataOption();
		if($Data['skpd'] == '1'){
			$tbl = 'v_penerimaan_barang_det';
		}else{
			$tbl = 'v1_penerimaan_barang_det';
		}
		
		return $tbl;
	}	
		
	function isiform($value, $parram=''){
		$isinya = '';
		$tbl ='<table width="100%" '.$parram.'>';
		for($i=0;$i<count($value);$i++){
			if(!isset($value[$i]['align']))$value[$i]['align'] = "left";
			if(!isset($value[$i]['valign']))$value[$i]['valign'] = "top";
			
			if(isset($value[$i]['type'])){
				switch ($value[$i]['type']){
					case "text" :
						$isinya = "<input type='text' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "hidden" :
						$isinya = "<input type='hidden' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "password" :
						$isinya = "<input type='password' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					default:
						$isinya = $value[$i]['value'];
					break;					
				}
			}else{
				$isinya = $value[$i]['value'];
			}
			
			$pemisah = ':';
			if(isset($value[$i]['pemisah']))$pemisah = $value[$i]['pemisah'];
			
			$tbl .= "
				<tr>
					<td width='".$value[$i]['label-width']."' valign='top'>".$value[$i]['label']."</td>
					<td width='10px' valign='top'>$pemisah<br></td>
					<td align='".$value[$i]['align']."' valign='".$value[$i]['valign']."'> $isinya</td>
				</tr>
			";		
		}
		$tbl .= '</table>';
		
		return $tbl;
	}
	
	function buttonnya($js,$img,$name,$alt,$judul){
		return "<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:$js'> 
						<img src='images/administrator/images/$img' alt='$alt' name='$name' width='32' height='32' border='0' align='middle' title='$judul'> $judul</a> 
					</td> 
					</tr> 
					</tbody></table> ";
	}
	
	function pageShow(){
		global $app, $Main; 
		
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
			
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		if(!isset($_REQUEST['halman']))$_REQUEST['halman']='1';
		return
		
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".			
		"<html>".
			$this->genHTMLHead().
			"<body >".
			/*"<div id='pageheader'>".$this->setPage_Header()."</div>".
			"<div id='pagecontent'>".$this->setPage_Content()."</div>".
			$Main->CopyRight.*/
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
				//header page -------------------		
				"<tr height='34'><td>".					
					//$this->setPage_Header($IconPage, $TitlePage).
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				//$this->setPage_HeaderOther().
				//Content ------------------------			
				//style='padding:0 8 0 8'
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
//
					
						//Form ------------------
						//$hidden.					
						//genSubTitle($TitleDaftar,$SubTitle_menu).						
						$this->setPage_Content().
						//$OtherInForm.
						
					$form2.//"</form>".
					"</div></div>".
				"</td></tr>".
				//$OtherContentPage.				
				//Footer ------------------------
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>
			
			".
			/*'<script src="assets2/js/bootstrap.min.js"></script>'.
			'<script src="assets2/jquery.min.js"></script>'.*/
			"</body>
		</html>"; 
	}
	
	function setPage_Header(){		
		//return createHeaderPage($this->PageIcon, $this->PageTitle);
		return $this->createHeaderPage($this->PageIcon, $this->PageTitle,  
			'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
		);
	}
	
	function createHeaderPage($headerIco, $headerTitle,  $otherMenu='', $headerFixed= FALSE, 
	$headerClass='pageheader', 
	$ico_width=20, $ico_height=30 )
{
	global $Main;
	//$headerIco = 'images/icon/daftar32.png'; $headerTitle = 'Pendaftaran & Pendataan';
	$headerMenu = $Main->MenuHeader;
	$TampilPosFix = $headerFixed==TRUE? "position:fixed;top:0;":'';	
	/*return 
		"<table id='head' cellspacing='0' cellpadding='0' border='0' class='$headerClass' style='$TampilPosFix'>
			<tr class=''>
			<td width='36'><img src='$headerIco' ></td>
			<td>$headerTitle</td>
			<td>$otherMenu $headerMenu</td>			
		</tr>	
	</table>
	";
	*/
	
	return 
	"<table width='100%' class='menubar' cellpadding='0' cellspacing='0' border='0'>
		<tbody><tr>
		<td background='images/bg.gif'>
		
			<div id='pagetitle'>					
					<table width='100%'> <tbody><tr>
					<td width='30'>						
						<img src='$headerIco' height='$ico_height' width='$ico_width'>
					</td>
					<td>$headerTitle</td>
					<td align='right'>
						<!--menubar_kanatas-->
						<table><tbody><tr><td>
						
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/home_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='index.php?Pg=' title='Main Menu'> 											
						</a>
						</div>
												
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/logout_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='index.php?Pg=LogOut' title='Logout'> 											
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/search_24.png);	
									width:24;height:24;display: inline-block;position:absolute' target='_blank' href='viewer.php' title='Pencarian Data'> 				
							
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/help_f2_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='pages.php?Pg=userprofil' title='User Profile'> 											
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a id='chat_alert' style='background-image: url(images/administrator/images/message_24_off.png); background-attachment: scroll; background-color: transparent; width: 24px; height: 24px; 
							display: inline-block; position: absolute; background-position: 0px 0px; background-repeat: no-repeat no-repeat; ' 
							target='_blank' href='index.php?Pg=Menu&amp;SPg=01' title='Chat'></a>
	</div>
	
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/Setting2_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='pages.php?Pg=DataPengaturan' title='Pengaturan Penerimaan' > 											
						</a>
						</div>
						
						
						</td></tr></tbody></table>
						
					</td>
					</tr>
					</tbody></table>
										
					
					
			</div>
					
					
					
		</td></tr>
		</tbody></table>";
	}
	
	function QyrTmpl1Brs($tablenya, $field='*',$where = '') {
		$qry = "SELECT $field FROM $tablenya $where";
		$aqry = mysql_query($qry);
		$hasil = mysql_fetch_array($aqry);
				
		return array('hasil'=>$hasil, 'cek'=>$qry);
	}
	
	function QryHitungData($tablenya, $where = ''){
		$qry = "SELECT * FROM $tablenya $where";
		$aqry = mysql_query($qry);
		$hasil = mysql_num_rows($aqry);
				
		return array('hasil'=>$hasil, 'cek'=>$qry);
	}
	
	function QryInsData($tbl, $values){
		$cek='';
		$field = '';
		$isifield = '';
		$errmsg = '';
		for($i=0;$i<count($values);$i++){
			if($i != 0){
				$field .= ',';
				$isifield .= ', ';
			}
			$field .= $values[$i][0];
			$isifield .= "'".$values[$i][1]."'";	
		}		
		
		$qry = "INSERT INTO $tbl ($field) values ($isifield)";$cek.=$qry;
		$aqry = mysql_query($qry);
		
		if(!$aqry)$errmsg = mysql_error();
		
		return array('hasil'=>$aqry, 'cek'=>$qry, 'errmsg'=>$errmsg);
	}
	
	function QryUpdData($tbl, $values, $where){
		$cek='';
		$isifield = '';
		for($i=0;$i<count($values);$i++){
			if($i != 0){
				$isifield .= ',';
			}
			$isifield .= $values[$i][0]."='".$values[$i][1]."'";	
		}
		
		$qry = "UPDATE $tbl SET $isifield $where ";$cek.=$qry;
		$aqry = mysql_query($qry);
		
		if(!$aqry)$errmsg = mysql_error();
		
		return array('hasil'=>$aqry, 'cek'=>$qry, 'errmsg'=>$errmsg);
	}
	
	function HargaPerolehanAtribusi($idTerima,$idTerima_det){
		$qry_cek_pendet = $this->QyrTmpl1Brs("t_penerimaan_barang_det", "harga_total", "WHERE Id='$idTerima_det' AND refid_terima='$idTerima' AND barangdistribusi='1' " );
		$aqry_cek_pendet = $qry_cek_pendet['hasil'];
		
		$qry_cek_distri = $this->QyrTmpl1Brs("t_distribusi", "SUM(jumlah) as jumlah", "WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND status='1' ");
		$aqry_cek_distri = $qry_cek_distri['hasil'];
	 
	 
	  	//Harga Perolehan Barang
	 	$tot_pmlhrn = $this->QyrTmpl1Brs("t_penerimaan_barang_det", "IFNULL(SUM(harga_total),0) as harga_total", "WHERE refid_terima='".$idTerima."' AND sttemp='0' AND barangdistribusi='1' ");
	 	$qry_pmlhrn = $tot_pmlhrn['hasil'];//$hsl = $tot_pmlhrn['cek'];
	 
	 	$tot_attribusi = $this->QyrTmpl1Brs("t_atribusi_rincian", "IFNULL(SUM(jumlah),0) as tot_atr", "WHERE refid_terima='".$idTerima."' AND sttemp='0' ");
		 $qry_tot_attr = $tot_attribusi['hasil'];$hsl = $tot_attribusi['cek'];
	 
	 //Hitung Harga Perolehan ------------------------------------------------------------------------------------------- 
	 //$hrg_perolehan = (($dt['harga_total']/$qry_pmlhrn['harga_total'])*$qry_tot_attr['tot_atr'])+$dt['harga_total'];
	 	$hrg1brg = round(intval($aqry_cek_pendet['harga_total']));
	 	$hrg_tot_bar = round(intval($qry_pmlhrn['harga_total']));
	 
	 	$hrg = @($hrg1brg/$hrg_tot_bar);
	 	$hrg_perolehan = round(($hrg*$qry_tot_attr['tot_atr'])+$aqry_cek_pendet['harga_total']);
		
		return $hrg_perolehan;
	}
	
	function CekBiayaAtribusi($Idplh){	
		$err = '';	
		$qry_Penerimaan = $this->QyrTmpl1Brs("t_penerimaan_barang", "*", " WHERE Id='$Idplh'" );
		$daqry_Penerimaan = $qry_Penerimaan['hasil'];
						
		if($daqry_Penerimaan['biayaatribusi'] == '1'){			
			$aqry_attribusi = $this->QryHitungData("t_atribusi", "WHERE refid_terima='$Idplh' AND sttemp='0'");
			
			if($aqry_attribusi['hasil'] < 1)$err = "Biaya Atribusi Belum Di Masukan !";
		}
		
		return $err;
		
		
	}
	
	function AmbilUraianBarang($IdBI){
		
		$qry = "SELECT * FROM buku_induk WHERE id='$IdBI'";
		$daqry = mysql_query($qry);
		$dt=mysql_fetch_array($daqry);
		
		$wherenya = "WHERE idbi='$IdBI' ";
		$content = $qry;
		
		$ada_alm = '';
		$ada_alm .= ($dt['rt'] && $dt['rw']) == ''? '' : '<br>RT/RW. '.$dt['rt'].'/'.$dt['rw'];		
		$ada_alm .= $dt['kampung'] == ''? '' : '<br>Kp/Komp. '.$dt['kampung'];	
		
		switch($dt['f']){
			case "01":
				$data_kib = "SELECT * FROM view_kib_a $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = '';
				$alm .= ifempty($qry_data_kib['alamat'],'-');
				$alm .= $ada_alm; 	
				$alm .= $qry_data_kib['alamat_kel'] != ''? '<br>Kel/Desa. '.$qry_data_kib['alamat_kel'] : '';
				$alm .= $qry_data_kib['alamat_kec'] != ''? '<br>Kec. '.$qry_data_kib['alamat_kec'] : '';
				$alm .= $qry_data_kib['alamat_kota'] != ''? '<br>'.$qry_data_kib['alamat_kota'] : '';
					
			break;
			case "02":
				$data_kib = "SELECT * FROM view_kib_b $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$qry_data_kib = array_map('utf8_encode', $qry_data_kib);
				$alm = $qry_data_kib['merk'];
				$alm .= $alm == ''?$qry_data_kib['ket'] : '';
							
			break;
			case "03":
				$data_kib = "SELECT * FROM view_kib_c $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = '';
				$alm .= ifempty($qry_data_kib['alamat'],'-');		
				$alm .= $ada_alm; 
				$alm .= $qry_data_kib['alamat_kel'] != ''? '<br>Kel/Desa. '.$qry_data_kib['alamat_kel'] : '';
				$alm .= $qry_data_kib['alamat_kec'] != ''? '<br>Kec. '.$qry_data_kib['alamat_kec'] : '';
				$alm .= $qry_data_kib['alamat_kota'] != ''? '<br>'.$qry_data_kib['alamat_kota'] : '';
				
			break;
			case "04":
				$data_kib = "SELECT * FROM view_kib_d $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = '';
				$alm .= ifempty($qry_data_kib['alamat'],'-');
				$alm .= $ada_alm; 		
				$alm .= $qry_data_kib['alamat_kel'] != ''? '<br>Kel/Desa. '.$qry_data_kib['alamat_kel'] : '';
				$alm .= $qry_data_kib['alamat_kec'] != ''? '<br>Kec. '.$qry_data_kib['alamat_kec'] : '';
				$alm .= $qry_data_kib['alamat_kota'] != ''? '<br>'.$qry_data_kib['alamat_kota'] : '';
			break;
			case "05":
				$data_kib = "SELECT * FROM view_kib_e $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = $qry_data_kib['ket'] != ''? $qry_data_kib['ket'] : '-';
				
				
			break;
			case "06":
				$data_kib = "SELECT * FROM view_kib_f $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = '';
				$alm .= ifempty($qry_data_kib['alamat'],'-');
				$alm .= $ada_alm; 		
				$alm .= $qry_data_kib['alamat_kel'] != ''? '<br>Kel/Desa. '.$qry_data_kib['alamat_kel'] : '';
				$alm .= $qry_data_kib['alamat_kec'] != ''? '<br>Kec. '.$qry_data_kib['alamat_kec'] : '';
				$alm .= $qry_data_kib['alamat_kota'] != ''? '<br>'.$qry_data_kib['alamat_kota'] : '';
				
			break;
			case "07":
				$data_kib = "SELECT * FROM view_kib_g $wherenya ";
				$qry_data_kib = mysql_fetch_array(mysql_query($data_kib));
				
				$alm = $qry_data_kib['ket'] != ''? $qry_data_kib['ket'] : '-';
			break;
		}
		
		
		return $alm;	
		
	}
	
	
}
$DataPengaturan = new DataPengaturanObj();
?>