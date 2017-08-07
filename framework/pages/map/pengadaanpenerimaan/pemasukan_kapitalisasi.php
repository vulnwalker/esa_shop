<?php
	
	include "pages/pengadaanpenerimaan/pemasukan.php";
	$pemasukan_saja = new pemasukanObj();
	
class pemasukan_kapitalisasiObj  extends DaftarObj2{	
	var $Prefix = 'pemasukan_kapitalisasi';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = ''; //bonus
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PENGADAAN DAN PENERIMAAN';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan_kapitalisasi.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'pemasukan_kapitalisasi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'pemasukan_kapitalisasiForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	var $stat_barang = array(
		array("1", "SUDAH"),
		array("2", "BELUM"),
	);
	
	function setTitle(){
		return 'KAPITALISASI BARANG';
	}
	
	function setMenuEdit(){
		return "";
		/*return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Atribusi", 'Atribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Distribusi", 'Distribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Validasi", 'Validasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Posting", 'Posting')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","export_xls.png","Excel", 'Excel')."</td>";*/
	}
	
	function setMenuView(){
		return "";
	}
		
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main,$HTTP_COOKIE_VARS;
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
		
		case 'cekTabelSKPD':{				
			$fm = $this->cekTabelSKPD();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'TabelSKPD':{				
			$fm = $this->TabelDataSKPD();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'SimpanKeDistribusi':{				
			$fm = $this->SimpanKeDistribusi();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'TabelPenerimaDistribusi':{				
			$fm = $this->TabelPenerimaDistribusi();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'SimpanSemua':{				
			$fm = $this->SimpanSemua();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'BatalSemua':{				
			$fm = $this->BatalSemua();				
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
							setTimeout(function myFunction() {".$this->Prefix.".loading()},100);
							
						setTimeout(function myFunction() {".$this->Prefix.".nyalakandatepicker()},1000);
						setTimeout(function myFunction() {".$this->Prefix.".TabelPenerimaDistribusi()},1000);
						});
					</script>";
		return 	
			"<script type='text/javascript' src='js/pengadaanpenerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/pencarian/cariRekening.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pencarian/cariprogram.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pencarian/cariIdPenerima.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pencarian/cariTemplate.js' language='JavaScript' ></script>".
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			'.
			$scriptload;
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
		
		$cbid = $_REQUEST['pemasukan_cb'];
		
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
					"<input type='hidden' name='databaru' id='databaru' value='".$_REQUEST['YN']."' />".
					"<input type='hidden' name='idTerima' id='idTerima' value='".addslashes($_REQUEST['idTerima_nya'])."' />".
					"<input type='hidden' name='idTerima_det' id='idTerima_det' value='".addslashes($_REQUEST['idTerima_det_nya'])."' />".
					
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
			"</table>".
			/*'<script src="assets2/js/bootstrap.min.js"></script>'.
			'<script src="assets2/jquery.min.js"></script>'.*/
			"</body>
		</html>"; 
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
		$a = "SELECT count(*) as cnt, aa.satuan_terbesar, aa.satuan_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_satuan bb ON aa.satuan_terbesar = bb.nama OR aa.satuan_terkecil = bb.nama WHERE bb.nama='".$this->form_idplh."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "Satuan Tidak Bisa Diubah ! Sudah Digunakan Di Ref Barang.";
		if($err == ''){
			$aqry = "SELECT * FROM  ref_satuan WHERE nama='".$this->form_idplh."' "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$fm = $this->setForm($dt);
		}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
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
		$Id = $dt['nama'];			
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
	return 
			"";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='900'>Satuan</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS, $pemasukan_saja, $DataPengaturan, $DataOption;
	 
	 
	 $pemasukan_saja->BersihkanData();
	 
	 
	 /*if(isset($_REQUEST['databaru'])){
	 		$baru = TRUE;
			
			if(addslashes($_REQUEST['databaru']) == '1'){
				$baru = TRUE;			
			}else{
				$baru = FALSE;
			}
				
				
		if($baru == TRUE){
					 
			
			
		}else{
			$id = $_REQUEST['idubah'];
						 
		}
	 }*/
	 
	 $idTerima = $_REQUEST['idTerima']; 
	 $idTerima_det = $_REQUEST['idTerima_det'];
	 
	 $qry_tampil = "SELECT * FROM ".$DataPengaturan->VPenerima_det()." WHERE Id='$idTerima_det' AND refid_terima='$idTerima' ";
	 $aqry_tampil = mysql_query($qry_tampil);
	 $dt = mysql_fetch_array($aqry_tampil);
	 
	 //TAMPIL DISTRIBUSI
	 $qry_dstr = "SELECT nomor, tgl_dok FROM t_distribusi WHERE refid_terima='$idTerima' AND refid_penerimaan_det='$idTerima_det' AND sttemp='0' LIMIT 0,1";
	 $daqry_dstr = mysql_query($qry_dstr);
	 
	 if(mysql_num_rows($daqry_dstr) > 0){
	 	$dt_dstr = mysql_fetch_array($daqry_dstr);
		$tgl_dok = explode("-",$dt_dstr['tgl_dok']);
		$tgl_dok = $tgl_dok[2].'-'.$tgl_dok[1].'-'.$tgl_dok[0];
		
		$nomor_dok = $dt_dstr['nomor'];
		
	 }else{
	 	$tgl_dok = date("d-m-Y");
		$nomor_dok = "";
	 }
		
	 
	 
	$c1 = $dt['c1'];
	$c = $dt['c'];
	$d = $dt['d'];
	$e = $dt['e'];
	$e1 = $dt['e1'];
	
	if($DataOption['skpd'] != '1'){
		$qry4 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='00' AND d='00' AND e='00' AND e1='000'";$cek.=$qry;
		$aqry4 = mysql_query($qry4);
		$data4 = mysql_fetch_array($aqry4);
		$dataC1 = $DataPengaturan->isiform(
					array(
						array(
								'label'=>'URUSAN',
								'name'=>'urusan',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$data4['c1'].'. '.$data4['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
					)
				);
		$WHEREC1 = "c1='$c1' AND";
	}else{
		$dataC1 = '';
		$WHEREC1 = '';
	}
	
	$qry = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='00' AND e='00' AND e1='000'";$cek.=$qry;
	$aqry = mysql_query($qry);
	$data = mysql_fetch_array($aqry);
	
	$qry1 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='00' AND e1='000'";$cek.=$qry1;
	$aqry1 = mysql_query($qry1);
	$data1 = mysql_fetch_array($aqry1);
	
	$qry2 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='$e' AND e1='000'";$cek.=$qry2;
	$aqry2 = mysql_query($qry2);
	$data2 = mysql_fetch_array($aqry2);
	
	$qry3 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='$e' AND e1='$e1'";$cek.=$qry3;
	$aqry3 = mysql_query($qry3);
	$data3 = mysql_fetch_array($aqry3);
	
	$qry_unitkerja = "SELECT e, concat(e,'.',nm_skpd) as nm_skpd FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e!='00' GROUP BY e";
	if($e != '01')$qry_unitkerja.=" AND e='$e'";
	
	$qrysumber_dn = "SELECT nama,nama FROM ref_sumber_dana";$cek.=$qrysumber_dn;
	$qry_dokumen_sumber = "SELECT nama_dokumen,nama_dokumen FROM ref_dokumensumber ";
	
	$TampilOpt =
			$vOrder=
			"<input type='hidden' name='c1nya' value='$c1' id='c1nya' />".
			"<input type='hidden' name='cnya' value='$c' id='cnya' />".
			"<input type='hidden' name='dnya' value='$d' id='dnya' />".
			"<input type='hidden' name='enya' value='$e' id='enya' />".
			"<input type='hidden' name='e1nya' value='$e1' id='e1nya' />".
			genFilterBar(
				array(
					$dataC1.
					$DataPengaturan->isiform(
						array(
							array(
								'label'=>'BIDANG',
								'name'=>'bidang',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c.'. '.$data['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'SKPD',
								'name'=>'skpd',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$d.'. '.$data1['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'UNIT',
								'name'=>'unit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e.'. '.$data2['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'SUB UNIT',
								'name'=>'subunit',
								'label-width'=>'200px',
								'type'=>'text',
								'value'=>$e1.'. '.$data3['nm_skpd'],
								'parrams'=>"style='width:400px;' readonly",
							),
						)
					)
				
				),'','','').
				genFilterBar(
				array(
					"<span id='distribarang' style='color:black;font-size:14px;font-weight:bold;'/>BIAYA KAPITALISASI BARANG</span>",
					
				
				),'','','').
				genFilterBar(
				array(
					$DataPengaturan->isiform(
						array(
							array(
								'label'=>'NAMA BARANG',
								'name'=>'namabarang',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$dt['nm_barang'],
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'JUMLAH',
								'name'=>'jumlah',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>number_format($dt['jml'],0,'.',','),
								'parrams'=>"style='width:80px;text-align:right;' readonly",
							),
							array(
								'label'=>'KUANTITAS',
								'name'=>'kuantitas',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>number_format($dt['kuantitas'],0,'.',','),
								'parrams'=>"style='width:80px;text-align:right;' readonly",
							),
							array(
								'label'=>'VOLUME',
								'name'=>'volume',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>number_format($dt['kuantitas']*$dt['jml'],0,'.',','),
								'parrams'=>"style='width:80px;text-align:right;' readonly",
							),
							array(
								'label'=>'JUMLAH HARGA',
								'name'=>'jml_harga',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>number_format($dt['harga_total'],2,',','.'),
								'parrams'=>"style='width:150px;text-align:right;' readonly",
							),
							array(
								'label'=>'URAIAN',
								'name'=>'merk',
								'label-width'=>'200px;',
								'value'=>"<textarea name='keteranganbarang' style='width:400px;height:50px;' placeholder='URAIAN PEMELIHARAAN' readonly>".$dt['ket_barang']."</textarea>
								",
							),
							array(
								'label'=>'TANGGAL',
								'name'=>'tanggal',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$tgl_dok,
								'parrams'=>"style='width:80px;' class='datepicker'",
							),
							array(
								'label'=>'NOMOR',
								'name'=>'nomor',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$nomor_dok,
								'parrams'=>"style='width:150px;' placeholder='NOMOR'",
							),
							array(
								'label'=>'UNIT KERJA',
								'name'=>'nomor',
								'label-width'=>'200px;',
								'value'=>cmbQuery('unitkerja',$unitkerja,$qry_unitkerja, "style='width:400px;' onchange='".$this->Prefix.".cekTabelSKPD(this.value)'; ","--- UNIT KERJA ---","-"),
							),
						)
					)
				
				),'','','').
				"<div id='tbl_atribusi' style='width:100%;'></div>".
				genFilterBar(
				array(
					"<span id='distribarang2' style='color:black;font-size:14px;font-weight:bold;'/>DAFTAR BARANG INVENTARIS YANG DIKAPITALISASI									
</span>",
					
				
				),'','','').
				"<div id='tbl_penerima_distribusi' style='width:100%;'></div>".
				genFilterBar(
				array(
					
					"<table>
						<tr>							
							<td><span id='selesaisesuai'>".$DataPengaturan->buttonnya($this->Prefix.'.cekTabelSKPD(document.getElementById(`unitkerja`).value,2)','checkin.png','SELESAI','SELESAI','SELESAI')."</span></td>
							<td>".$DataPengaturan->buttonnya($this->Prefix.'.BatalSemua()','cancel_f2.png','BATAL','BATAL','BATAL')."</td>
						</tr>".
					"</table>"
					
				
				),'','','').
				
				genFilterBar(
				array(
					"<input type='hidden' name='".$this->Prefix."_idplh' id='".$this->Prefix."_idplh' value='".$dt['Id']."' />".
					"<input type='hidden' name='refid_terima' id='refid_terima' value='$refid_terimanya' />".		
					"<input type='hidden' name='refid_terima_sebelumnya' id='refid_terima_sebelumnya' value='$refid_terimanya' />"		
				),'','','')
							;
			
		return array('TampilOpt'=>$TampilOpt);
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
		//Cari 
		switch($fmPILCARI){			
			case 'selectSatuan': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
		}	
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
	
	function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		
			$a = "SELECT count(*) as cnt, aa.satuan_terbesar, aa.satuan_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_satuan bb ON aa.satuan_terbesar = bb.nama OR aa.satuan_terkecil = bb.nama WHERE bb.nama='".$ids[$i]."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "Satuan ".$ids[$i]." Tidak Bisa DiHapus ! Sudah Digunakan Di Ref Barang.";
		
			if($err=='' ){
					$qy = "DELETE FROM $this->TblName_Hapus WHERE nama='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
		
	function TabelDataSKPD(){
		global $DataPengaturan, $DataOption;
		$cek = '';
		$err = '';
		$jml_harga=0;
		$datanya='';
		
		$idTerima = addslashes($_REQUEST["idTerima"]);
		$idTerima_det = addslashes($_REQUEST["idTerima_det"]);
		
		$c1nya = addslashes($_REQUEST["c1nya"]);
		$cnya = addslashes($_REQUEST["cnya"]);
		$dnya = addslashes($_REQUEST["dnya"]);
		$unitkerja = addslashes($_REQUEST["unitkerja"]);
		
		
		
		$dt_barang = "SELECT * FROM t_penerimaan_barang_det WHERE Id='$idTerima_det' AND refid_terima='$idTerima' ";$cek.=$dt_barang;
		$qry_dt_barang = mysql_query($dt_barang);
		$aqry_dt_barang = mysql_fetch_array($qry_dt_barang);
		
		$kodebarangnya = "AND f='".$aqry_dt_barang['f']."' AND g='".$aqry_dt_barang['g']."' AND h='".$aqry_dt_barang['h']."' AND i='".$aqry_dt_barang['i']."' AND j='".$aqry_dt_barang['j']."' ";
		$kodebarangnya2 = "AND rs.f='".$aqry_dt_barang['f']."' AND rs.g='".$aqry_dt_barang['g']."' AND rs.h='".$aqry_dt_barang['h']."' AND rs.i='".$aqry_dt_barang['i']."' AND rs.j='".$aqry_dt_barang['j']."' ";
		
		
		$ONF1 = '';
		$SELECTF1 = '';
		if($DataOption['kode_barang'] != '1'){
			$kodebarangnya = "AND f1='".$aqry_dt_barang['f1']."' AND f2='".$aqry_dt_barang['f2']."' $kodebarangnya ";
			$kodebarangnya2 = "AND rs.f1='".$aqry_dt_barang['f1']."' AND rs.f2='".$aqry_dt_barang['f2']."' $kodebarangnya2 ";
			
			$ONF1 = ' AND rs.f1=td.f1 AND rs.f2=td.f2 ';
			$SELECTF1 = ', td.f1, td.f2';
		}
		
		$ONC1 = '';
		$WHEREC1='';
		if($DataOption['skpd'] != '1'){
			$ONC1 = "rs.c1=td.c1 AND ";
			$WHEREC1 = "rs.c1='$c1nya' AND ";
		}
		
		
		
		
		$qry = "SELECT rs.id, rs.noreg, rs.thn_perolehan,rs.jml_harga $SELECTF1 , td.f, td.g, td.h, td.i, td.j, ifnull(td.jumlah,0) as jumlah, td.refid_terima, td.refid_penerimaan_det, td.jns_pemeliharaan FROM buku_induk rs LEFT JOIN (SELECT * FROM t_distribusi WHERE refid_terima='$idTerima' AND refid_penerimaan_det='$idTerima_det' AND status='1' $kodebarangnya) td ON $ONC1 rs.c=td.c AND rs.d=td.d AND rs.e=td.e AND rs.e1=td.e1 AND $ONF1 rs.f=td.f AND rs.g=td.g AND rs.h=td.h AND rs.i=td.i AND rs.j=td.j AND rs.id = td.refid_buku_induk WHERE $WHEREC1 rs.c='$cnya' AND rs.d='$dnya' AND rs.e='$unitkerja' AND rs.status_barang='1' $kodebarangnya2 ";$cek.=$qry;
		$aqry = mysql_query($qry);
		$no=1;
		
		$qry_jns_pemeliharaan = "SELECT jenis, jenis FROM ref_jenis_pemeliharaan";
		while($dt = mysql_fetch_array($aqry)){	
			$kodeskpdnya = $c1nya.'_'.$cnya.'_'.$dnya.'_'.$unitkerja."_".$dt['e1'];
			$datanya.="
				<tr class='row0'>
					<td class='GarisDaftar' align='right'>$no</td>
					<td class='GarisDaftar' align='center'>
							".$dt['noreg']."
							<input type='hidden' name='dataIdBuku[]' value='".$dt['id']."' />
					</td>
					<td class='GarisDaftar' align='center'>".$dt['thn_perolehan']."</td>
					<td class='GarisDaftar' align='left'>".$this->AmbilUraianBarang($dt['id'])."</td>
					<td class='GarisDaftar' align='right'>".number_format($dt['jml_harga'],2,',','.')."</td>
					<td class='GarisDaftar' align='right'>
						<input type='text' name='jumlah_".$dt['id']."' id='jumlah_".$dt['id']."' value='".$dt['jumlah']."' style='text-align:right;width:150px;'onkeypress='return isNumberKey(event)' onkeyup='document.getElementById(`formatjumlah_".$dt['id']."`).innerHTML = pemasukan_kapitalisasi.formatCurrency(this.value);' /><span id='formatjumlah_".$dt['id']."'></span>
					<td class='GarisDaftar'>".cmbQuery('jns_pemeliharaan_'.$dt['id'],$dt['jns_pemeliharaan'],$qry_jns_pemeliharaan, "style='width:100%';", "PILIH")."</td>
					</td>
				</tr>
			";
			$no = $no+1;
		}
		
						
		if(mysql_num_rows($aqry) > 0){
					
			$content['tabel'] =
				genFilterBar(
					array("
						<table class='koptable' style='min-width:1000px;' border='1'>
							<tr>
								<th class='th01'>NO</th>
								<th class='th01' width='50px'>NO REG</th>
								<th class='th01' width='50px'>TAHUN</th>
								<th class='th01'>MERK/TYPE/SPESIFIKASI/LOKASI</th>
								<th class='th01'>HARGA PEROLEHAN</th>
								<th class='th01'>NILAI KAPITALISASI</th>
								<th class='th01'>JENIS PEMELIHARAAN</th>
							</tr>
							$datanya
							
						</table>"
					)
				,'','','')
			;
		}else{
			$content['tabel'] ='';
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function cekTabelSKPD(){
	 global $HTTP_COOKIE_VARS, $DataOption;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $thn_anggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $c1= $_REQUEST['c1nya'];
	 $c= $_REQUEST['cnya'];
	 $d= $_REQUEST['dnya'];
	 $e= $_REQUEST['enya'];
	 $e1= $_REQUEST['e1nya'];
	 
	 $idTerima = addslashes($_REQUEST["idTerima"]);
	 $idTerima_det = addslashes($_REQUEST["idTerima_det"]);
	 
	 if(isset($_REQUEST['dataIdBuku'])){
	 	$dataPilihan = $_REQUEST['dataIdBuku'];
		
		$tmplTerimaDet = "SELECT * FROM t_penerimaan_barang_det WHERE Id='$idTerima_det' AND refid_terima='$idTerima'";$cek.= $tmplTerimaDet;
		$qry_tmplTerimaDet = mysql_query($tmplTerimaDet);
		$daqry_tmDet = mysql_fetch_array($qry_tmplTerimaDet);
		
		//KODE UNTUK QUERY
		
		$kodebarangnya = "AND f='".$daqry_tmDet['f']."' AND g='".$daqry_tmDet['g']."' AND h='".$daqry_tmDet['h']."' AND i='".$daqry_tmDet['i']."' AND j='".$daqry_tmDet['j']."' ";
		if($DataOption['kode_barang'] != '1')$kodebarangnya = "AND f1='".$daqry_tmDet['f1']."' AND f2='".$daqry_tmDet['f2']."' ".$kodebarangnya;
		
		$content['a'] = 1;
		$content['ceklagi'] = 1;
		$content['unit'] = $_REQUEST['unitkerja'];
		for($i=0;$i<=count($dataPilihan);$i++){
			if($err == ''){
					
				//AMBIL SKPD e,e1 di BUKU INDUK
				$qry_BI = "SELECT e,e1 FROM buku_induk WHERE id='".$dataPilihan[$i]."'"; $cek.=" | ".$qry_BI;
				$aqry_BI = mysql_query($qry_BI);
				$dt_BI = mysql_fetch_array($aqry_BI);
							
				$kodeskpd = "c='$c' AND d='$d' AND e='".$dt_BI['e']."' AND e1='".$dt_BI['e1']."' ";
				if($DataOption['kode_barang'] != '')$kodeskpd = "c1='$c1' AND ".$kodeskpd; 						
				$jml_brg = $_REQUEST['jumlah_'.$dataPilihan[$i]];
				$jns_pemeliharaan = $_REQUEST['jns_pemeliharaan_'.$dataPilihan[$i]];		
					
				$qry = "SELECT * FROM t_distribusi WHERE $kodeskpd $kodebarangnya AND status='1' AND refid_penerimaan_det ='$idTerima_det' AND refid_buku_induk='".$dataPilihan[$i]."'  ";  $cek.=" | ".$qry;
				$daqry = mysql_query($qry);
				$dt_daqry = mysql_fetch_array($daqry);
				
				if(mysql_num_rows($daqry) != 0 && $_REQUEST['CekKe'] == 1){
					if($dt_daqry['jumlah'] != $jml_brg && $err =='')$err ='Ada data yang Belum Disimpan ! Mau Disimpan ?';
					if($dt_daqry['jns_pemeliharaan'] != $jns_pemeliharaan && $err =='')$err ='Ada data yang Belum Disimpan ! Mau Disimpan ?';
					$content['ceklagi'] = 0;
				}else{
					if($err == '' && $_REQUEST['CekKe'] == 1 && $jml_brg > 0 )$err ='Ada data yang Belum Disimpan ! Mau Disimpan ?';
					if($err == '' && $_REQUEST['CekKe'] == 2 && $jns_pemeliharaan =='' && $jml_brg > 0){
						$err ='Jenis Pemeliharaan dengan Nilai Kapitalisasi '.number_format($jml_brg,2,',','.').', belum di pilih !';	
						$content['a'] = 0;
						$content['unit'] = $dt_BI['e'];
						}
					
				}					
			}else{
				break;
			}
		}
	 } 
				
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
	
	function TabelPenerimaDistribusi(){
		global $DataOption;
		$cek = '';
		$err = '';
		$jml_harga=0;
		$datanya='';
		
		$idTerima = addslashes($_REQUEST["idTerima"]);
		$idTerima_det = addslashes($_REQUEST["idTerima_det"]);
		
		$c1nya = addslashes($_REQUEST["c1nya"]);
		$cnya = addslashes($_REQUEST["cnya"]);
		$dnya = addslashes($_REQUEST["dnya"]);
		$unitkerja = addslashes($_REQUEST["unitkerja"]);
		
		
		$dt_barang = "SELECT * FROM t_penerimaan_barang_det WHERE Id='$idTerima_det' AND refid_terima='$idTerima' ";$cek.=$dt_barang;
		$qry_dt_barang = mysql_query($dt_barang);
		$aqry_dt_barang = mysql_fetch_array($qry_dt_barang);
		
		$kodebarangnya2 = "AND rs.f='".$aqry_dt_barang['f']."' AND rs.g='".$aqry_dt_barang['g']."' AND rs.h='".$aqry_dt_barang['h']."' AND rs.i='".$aqry_dt_barang['i']."' AND rs.j='".$aqry_dt_barang['j']."' ";
		
		$ONnya = '';
		$ONnya1 = '';
		$WHEREnya = '';
		if($DataOption['skpd'] != '1'){
			$kodebarangnya2 = "AND rs.f1='".$aqry_dt_barang['f1']."' AND rs.f2='".$aqry_dt_barang['f2']."' ".$kodebarangnya2;
			$ONnya = 'rs.c1=td.c1 AND '	;
			$ONnya1 = 'AND rs.f1=td.f1 AND rs.f2=td.f2  '	;
			$WHEREnya = "rs.c1='$c1nya' AND ";
		}
		$qry = "SELECT rs.id,rs.e, rs.noreg, rs.thn_perolehan,rs.jml_harga, td.f1, td.f2, td.f, td.g, td.h, td.i, td.j, ifnull(td.jumlah,0) as jumlah, td.refid_terima, td.refid_penerimaan_det, td.jns_pemeliharaan FROM buku_induk rs RIGHT JOIN t_distribusi td ON $ONnya rs.c=td.c AND rs.d=td.d AND rs.e=td.e AND rs.e1=td.e1 $ONnya1 AND rs.f=td.f AND rs.g=td.g AND rs.h=td.h AND rs.i=td.i AND rs.j=td.j AND rs.id = td.refid_buku_induk WHERE $WHEREnya rs.d='$dnya' AND rs.status_barang='1' AND td.status='1' $kodebarangnya2";$cek.=$qry;
		$aqry = mysql_query($qry);
		$no=1;
		$jumlah_barang = 0;
		while($dt = mysql_fetch_array($aqry)){	
			$kodeskpdnya = $c1nya.'_'.$cnya.'_'.$dnya.'_'.$unitkerja."_".$dt['e1'];
			$datanya.="
				<tr class='row0'>
					<td class='GarisDaftar' align='right' width='5%'>$no</td>
					<td class='GarisDaftar' align='center' width='5%'>".$dt['e']."</td>
					<td class='GarisDaftar' align='center'>".$dt['noreg']."</td>
					<td class='GarisDaftar' align='center'>".$dt['thn_perolehan']."</td>
					<td class='GarisDaftar'>".$this->AmbilUraianBarang($dt['id'])."</td>
					<td class='GarisDaftar' align='right' width='75px'>".number_format($dt['jumlah'],2,',','.')."</td>
					<td class='GarisDaftar' align='left'>".$dt['jns_pemeliharaan']."</td>
				</tr>
			";
			$no = $no+1;
			$jumlah_barang=$jumlah_barang+$dt['jumlah'];
		}
		
		
		//SISA JUMLAHNYA----------------------------------------------------------------------------------------------
		
		
		$sisatotal = $aqry_dt_barang['jml'] - $jumlah_barang;
		
		$content['sisa_jumlah'] = number_format($sisatotal,0,".",",");
		//------------------------------------------------------------------------------------------------------------
						
					
		$content['tabel'] =
			genFilterBar(
				array("
					<table class='koptable' style='min-width:1000px;' border='1'>
						<th class='th01'>NO</th>
						<th class='th01'>UNIT</th>
						<th class='th01' width='50px'>NO REG</th>
						<th class='th01' width='50px'>TAHUN</th>
						<th class='th01'>MERK/TYPE/SPESIFIKASI/LOKASI</th>
						<th class='th01'>NILAI KAPITALISASI</th>
						<th class='th01'>JENIS PEMELIHARAAN</th>
						$datanya
						<tr class='row0'>
							<td class='GarisDaftar' colspan='5' align='center'><b>TOTAL</b></td>
							<td class='GarisDaftar' align='right'><b>".number_format($jumlah_barang,2,',','.')."</b></td>
							<td class='GarisDaftar' align='right'></td>
						</tr>
					</table>"
				)
			,'','','')
		;
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function CEKMASUKAN($jml){
		if($jml > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function SimpanKeDistribusi(){
	 global $HTTP_COOKIE_VARS, $DataOption;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $thn_anggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $c1= $_REQUEST['c1nya'];
	 $c= $_REQUEST['cnya'];
	 $d= $_REQUEST['dnya'];
	 $e= $_REQUEST['enya'];
	 $e1= $_REQUEST['e1nya'];
	 
	 $idTerima = addslashes($_REQUEST["idTerima"]);
	 $idTerima_det = addslashes($_REQUEST["idTerima_det"]);
	 
	 if(isset($_REQUEST['dataIdBuku'])){
	 	$dataPilihan = $_REQUEST['dataIdBuku'];
		$tmplTerimaDet = "SELECT * FROM t_penerimaan_barang_det WHERE Id='$idTerima_det' AND refid_terima='$idTerima'";$cek.= $tmplTerimaDet;
		$qry_tmplTerimaDet = mysql_query($tmplTerimaDet);
		$daqry_tmDet = mysql_fetch_array($qry_tmplTerimaDet);
				
		//KODE UNTUK QUERY
		
		$fld_F1 = '';
		$val_F1 = '';
		$kodebarangnya = "AND f='".$daqry_tmDet['f']."' AND g='".$daqry_tmDet['g']."' AND h='".$daqry_tmDet['h']."' AND i='".$daqry_tmDet['i']."' AND j='".$daqry_tmDet['j']."' ";
		if($DataOption['kode_barang'] != '1'){
			$kodebarangnya="AND f1='".$daqry_tmDet['f1']."' AND f2='".$daqry_tmDet['f2']."' ".$kodebarangnya;
			
			$fld_F1 = "f1,f2,";
			$val_F1 = " '".$daqry_tmDet['f1']."', '".$daqry_tmDet['f2']."', ";
		}
					
					
		for($i=0;$i<=count($dataPilihan);$i++){
			if($err == ''){
								
				$jml_brg = $_REQUEST['jumlah_'.$dataPilihan[$i]];
				$jns_pemeliharaan = $_REQUEST['jns_pemeliharaan_'.$dataPilihan[$i]];
				
				//AMBIL SKPD e,e1 di BUKU INDUK
				$qry_BI = "SELECT e,e1 FROM buku_induk WHERE id='".$dataPilihan[$i]."'";
				$aqry_BI = mysql_query($qry_BI);
				$dt_BI = mysql_fetch_array($aqry_BI);
					
				$kodeskpd = "c='$c' AND d='$d' AND e='".$dt_BI['e']."' AND e1='".$dt_BI['e1']."' ";
				$fld_C1 = "";
				$val_C1 = "";
				if($DataOption['skpd'] != '1'){
					$kodeskpd = "c1='$c1' AND ".$kodeskpd;
					$fld_C1 = "c1,";
					$val_C1 = "'$c1', ";
				}
				$qry = "SELECT * FROM t_distribusi WHERE $kodeskpd $kodebarangnya AND status='1' AND refid_penerimaan_det ='$idTerima_det' AND refid_buku_induk='".$dataPilihan[$i]."' ";
				$daqry = mysql_query($qry);
				$dt_daqry = mysql_fetch_array($daqry);
				
				$masukan = TRUE;
				
				if(mysql_num_rows($daqry) != 0){
					if($dt_daqry['jumlah'] != $jml_brg || $dt_daqry['jns_pemeliharan'] != $jns_pemeliharaan){
						$qryupd= "UPDATE t_distribusi SET status='2' WHERE $kodeskpd $kodebarangnya AND refid_terima='$idTerima' AND refid_penerimaan_det='$idTerima_det' AND refid_buku_induk='".$dataPilihan[$i]."' ";$cek.=$qryupd;
						$aqryupd = mysql_query($qryupd);
						
						$masukan = $this->CEKMASUKAN($jml_brg);
					}else{
						$masukan = FALSE;
					}
				}else{
					$masukan = $this->CEKMASUKAN($jml_brg);
				}
					
				if($masukan == TRUE){
					$qryins = "INSERT INTO t_distribusi ($fld_C1 c,d,e,e1,$fld_F1 f,g,h,i,j,jumlah, refid_terima, tahun, refid_penerimaan_det, uid, status, sttemp, jns_pemeliharaan, refid_buku_induk) values ($val_C1 '$c', '$d', '".$dt_BI['e']."', '".$dt_BI['e1']."', $val_F1 '".$daqry_tmDet['f']."', '".$daqry_tmDet['g']."', '".$daqry_tmDet['h']."', '".$daqry_tmDet['i']."', '".$daqry_tmDet['j']."', '$jml_brg', '$idTerima', '$thn_anggaran', '$idTerima_det', '$uid', '1', '1', '$jns_pemeliharaan', '".$dataPilihan[$i]."')";$cek.= "| ".$qryins;
					$aqryins = mysql_query($qryins);
				}	
					
			}else{			
				break;
			}
		}
	 }
					
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function SimpanSemua(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $thn_anggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $idTerima = addslashes($_REQUEST["idTerima"]);
	 $idTerima_det = addslashes($_REQUEST["idTerima_det"]);
	 
	 $tgl_dok = explode("-",$_REQUEST['tanggal']);
	 $nomor_dok = $_REQUEST['nomor'];
	 $tgl_dok = $tgl_dok[2].'-'.$tgl_dok[1].'-'.$tgl_dok[0];
	 
	 
	 	 
	 //Cek JUMLAH Barang DIPenerimaan_det;
	 $qry_cek_pendet = "SELECT harga_total FROM t_penerimaan_barang_det WHERE Id='$idTerima_det' AND refid_terima='$idTerima' ";$cek.=$qry_cek_pendet;
	 $aqry_cek_pendet = mysql_fetch_array(mysql_query($qry_cek_pendet));
	 
	 //Cek Jumlah Barang Di Distribusi
	 $qry_cek_distri = "SELECT SUM(jumlah) as jumlah FROM t_distribusi WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND status='1' ";$cek.=' | '.$qry_cek_distri;
	 
	 $aqry_cek_distri = mysql_fetch_array(mysql_query($qry_cek_distri));
	 
	 if($err == ''){
	 	if($aqry_cek_pendet['harga_total'] < $aqry_cek_distri['jumlah'])$err = "Total Nilai Kapitalisasi, Tidak Boleh melebihi Jumlah Harga yang di Kapitalisasi !";
	 }
	 //if($err == '')$err.='gfhgfh';
	 
	 if($err == ''){
	 	//HAPUS t_distribusi status = '2'
	 	$qry_del_dstr = "DELETE FROM  t_distribusi WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND status='2' ";
		$aqry_del_dstr = mysql_query($qry_del_dstr);
		//UPDATE t_distribusi
		$qry_upd_dstr = "UPDATE t_distribusi SET sttemp='0', nomor='$nomor_dok', tgl_dok='$tgl_dok' WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND status='1' ";
		$aqry_upd_dstr = mysql_query($qry_upd_dstr);
	 }				
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function BatalSemua(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $thn_anggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $idTerima = addslashes($_REQUEST["idTerima"]);
	 $idTerima_det = addslashes($_REQUEST["idTerima_det"]);
	 
	 if($err == ''){
	 	//HAPUS t_distribusi status = '2'
	 	$qry_del_dstr = "DELETE FROM  t_distribusi WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND sttemp='1' ";
		$aqry_del_dstr = mysql_query($qry_del_dstr);
		//UPDATE t_distribusi
		$qry_upd_dstr = "UPDATE t_distribusi SET status='1' WHERE refid_penerimaan_det='$idTerima_det' AND refid_terima='$idTerima' AND sttemp='0' ";
		$aqry_upd_dstr = mysql_query($qry_upd_dstr);
	 }
	 				
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
}
$pemasukan_kapitalisasi = new pemasukan_kapitalisasiObj();
?>