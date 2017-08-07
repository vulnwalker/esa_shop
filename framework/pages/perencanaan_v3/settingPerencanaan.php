
<?php

class settingPerencanaan_v3Obj  extends DaftarObj2{	
	var $Prefix = 'settingPerencanaan_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'settingperencanaan_v3'; //bonus
	var $TblName_Hapus = 'settingPerencanaan_v3';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PERENCANAAN';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='settingPerencanaan_v3.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'settingPerencanaan_v3';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'settingPerencanaan_v3Form';
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
		return 'PENGATURAN PERENCANAAN';
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
	 	$bypassJadwal = $_REQUEST['jenisAnggaran'];
		$wajibValidasi = $_REQUEST['wajibValidasi'];
		$tahun = $_REQUEST['tahun'];
		$provinsi = $_REQUEST['provinsi'];
		$kota = $_REQUEST['kota'];
		$pejabat = $_REQUEST['pejabatPengelolaBarang'];
		$pengelola = $_REQUEST['pengelolaBarang'];
		$pengurus = $_REQUEST['pengurusBarangPengelola'];
		
			$qry = "UPDATE settingperencanaan_v3  SET  jenis_anggaran = '$bypassJadwal', wajib_validasi = '$wajibValidasi', tahun = '$tahun', provinsi = '$provinsi' , kota='$kota' , pejabat = '$pejabat' , pengelola ='$pengelola' , pengurus ='$pengurus' ";$cek.=$qry;
			$aqry = mysql_query($qry);
	
	 $getDataTahap = mysql_num_rows(mysql_query("select * from ref_tahap_anggaran_v3 where tahun = '$tahun' and anggaran = '$bypassJadwal'"));
	 if($getDataTahap == 0){
	 	$data1 = array( 'anggaran' => $bypassJadwal,
						'tahun' => $tahun,
						'no_urut' => '1',
						'id_modul' => '2',
						'jenis_form_modul' => 'PENYUSUNAN',
						 );
		mysql_query(VulnWalkerInsert('ref_tahap_anggaran_v3',$data1));
		$data1 = array( 'anggaran' => $bypassJadwal,
						'tahun' => $tahun,
						'no_urut' => '2',
						'id_modul' => '3',
						'jenis_form_modul' => 'PENYUSUNAN',
						 );
		mysql_query(VulnWalkerInsert('ref_tahap_anggaran_v3',$data1));
		$data1 = array( 'anggaran' => $bypassJadwal,
						'tahun' => $tahun,
						'no_urut' => '3',
						'id_modul' => 'KOREKSI PENGGUNA',
						'jenis_form_modul' => 'KOREKSI PENGGUNA',
						 );
		mysql_query(VulnWalkerInsert('ref_tahap_anggaran_v3',$data1));
		$data1 = array( 'anggaran' => $bypassJadwal,
						'tahun' => $tahun,
						'no_urut' => '4',
						'id_modul' => 'KOREKSI PENGELOLA',
						'jenis_form_modul' => 'KOREKSI PENGELOLA',
						 );
		mysql_query(VulnWalkerInsert('ref_tahap_anggaran_v3',$data1));
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
			"<script type='text/javascript' src='js/perencanaan_v3/settingPerencanaan.js' language='JavaScript' ></script>".
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
";
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
	

	
	if($daqry['wajib_validasi'] == '1'){
		$wajibValidasi = "YA";
	}else{
		$wajibValidasi = "TIDAK";
	}
	
	$jenisAnggaran = $daqry['jenis_anggaran'];
	$tahun = $daqry['tahun'];
	$provinsi = $daqry['provinsi'];
	$kota = $daqry['kota'];
	$pejabat = $daqry['pejabat'];
	$pengelola = $daqry['pengelola'];
	$pengurus = $daqry['pengurus'];
	
	$getPengelola = mysql_fetch_array(mysql_query("select * from tandatanganpengelolabarang_v3 where id = '$pengelola'"));
	$pengelolaBarang = $getPengelola['nama'];
	
	$getPejabat = mysql_fetch_array(mysql_query("select * from tandatanganpengelolabarang_v3 where id = '$pejabat'"));
	$pejabatPengelolaBarang = $getPejabat['nama'];
	
	$getPengurus = mysql_fetch_array(mysql_query("select * from tandatanganpengelolabarang_v3 where id = '$pengurus'"));
	$pengurusBarangPengelola = $getPengurus['nama'];
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
								'label'=>'<b>WAJIB VALIDASI</b>',
								'name'=>'wajibValidasi',
								'label-width'=>'400px;',
								'value'=>$wajibValidasi,
							),
							array(
								'label'=>'<b>TAHUN</b>',
								'name'=>'tahun',
								'label-width'=>'230px;',
								'value'=>$tahun,
							),
							array(
								'label'=>'<b>JENIS ANGGARAN</b>',
								'name'=>'tahun',
								'label-width'=>'230px;',
								'value'=>$jenisAnggaran,
							),
							array(
								'label'=>'<b>PROVINSI</b>',
								'name'=>'provinsi',
								'label-width'=>'230px;',
								'value'=>$provinsi,
							),
							array(
								'label'=>'<b>KABUPATEN / KOTA</b>',
								'name'=>'kota',
								'label-width'=>'230px;',
								'value'=>$kota,
							),
							array(
								'label'=>'<b>PENGELOLA BARANG</b>',
								'name'=>'pengelolaBarang',
								'label-width'=>'230px;',
								'value'=>$pengelolaBarang,
							),
							array(
								'label'=>'<b>PEJABAT PENATAUSAHAAN PENGELOLA BARANG</b>',
								'name'=>'pejabatPengelolaBarang',
								'label-width'=>'230px;',
								'value'=>$pejabatPengelolaBarang,
							),
							array(
								'label'=>'<b>PENGURUS BARANG PENGELOLA</b>',
								'name'=>'pengurusBarangPengelola',
								'label-width'=>'230px;',
								'value'=>$pengurusBarangPengelola,
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
			array('1','YA'),	
			array('0','TIDAK'),		
			);
			
		$arr_def = array(
			//array('selectAll','Semua'),	
			array('1','YA'),	
			array('0','TIDAK'),		
			);
		
		$arrayJenisAnggaran = array(
			array('MURNI','MURNI'),	
			array('PERUBAHAN','PERUBAHAN'),		
			);
			
		
		$daqry = $this->DataOption();
		$tahun = $daqry['tahun'];
		$provinsi = $daqry['provinsi'];
		$kota = $daqry['kota'];
		$pengelolaBarang = $daqry['pengelola'];
		$pejabatPengelolaBarang = $daqry['pejabat'];
		$pengurusBarangPengelola = $daqry['pengurus'];
		
		$cmbPengelolaBarang = cmbQuery('pengelolaBarang',$pengelolaBarang,"select id, nama from tandatanganpengelolabarang_v3 where kategori = 'PENGELOLA'",'','-- PENGELOLA BARANG --');
		$cmbPejabatPengelolaBarang = cmbQuery('pejabatPengelolaBarang',$pejabatPengelolaBarang,"select id, nama from tandatanganpengelolabarang_v3 where kategori = 'PEJABAT'",'','-- PENGELOLA BARANG --');
		$cmbPengurusBarangPengelola = cmbQuery('pengurusBarangPengelola',$pengurusBarangPengelola,"select id, nama from tandatanganpengelolabarang_v3 where kategori = 'PENGURUS'",'','-- PENGELOLA BARANG --');
			
		$cek='';$err='';
		$content = 
			genFilterBar(
				array(
					
					$this->isiform(
						array(
							array(
								'label'=>'<b>WAJIB VALIDASI</b>',
								'name'=>'wajibValidasi',
								'label-width'=>'400px;',
								'value'=>cmbArray('wajibValidasi',$daqry['wajib_validasi'], $arr_def,'--- PILIH ---',"style='width:300px;'"),
							),
							array(
								'label'=>'<b>TAHUN</b>',
								'name'=>'tahun',
								'label-width'=>'230px;',
								'value'=>"<input type ='text' id='tahun' name='tahun' value='$tahun' style='width:40px;'>",
							),
							array(
								'label'=>'<b>JENIS ANGGARAN</b>',
								'name'=>'jenisAnggaran',
								'label-width'=>'230px;',
								'value'=>cmbArray('jenisAnggaran',$daqry['jenis_anggaran'], $arrayJenisAnggaran,'--- PILIH ---',"style='width:300px;'"),
							),
							array(
								'label'=>'<b>PROVINSI</b>',
								'name'=>'provinsi',
								'label-width'=>'230px;',
								'value'=>"<input type ='text' id='provinsi' name='provinsi' value='$provinsi' style='width:200px;'>",
							),
							array(
								'label'=>'<b>Kabupaten/Kota</b>',
								'name'=>'kota',
								'label-width'=>'230px;',
								'value'=>"<input type ='text' id='kota' name='kota' value='$kota' style='width:200px;'>",
							),
							array(
								'label'=>'<b>PENGELOLA BARANG</b>',
								'name'=>'pengelolaBarang',
								'label-width'=>'230px;',
								'value'=>$cmbPengelolaBarang,
							),
							array(
								'label'=>'<b>PEJABAT PENATAUSAHAAN PENGELOLA BARANG</b>',
								'name'=>'pejabatPengelolaBarang',
								'label-width'=>'230px;',
								'value'=>$cmbPejabatPengelolaBarang,
							),
							array(
								'label'=>'<b>PENGURUS BARANG PENGELOLA</b>',
								'name'=>'pengurusBarangPengelola',
								'label-width'=>'230px;',
								'value'=>$cmbPengurusBarangPengelola,
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
		$qry = "SELECT * FROM $this->TblName ";
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
$settingPerencanaan_v3 = new settingPerencanaan_v3Obj();
?>
