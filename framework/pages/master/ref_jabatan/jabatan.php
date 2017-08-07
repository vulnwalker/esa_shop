<?php

class JabatanObj  extends DaftarObj2{	
	var $Prefix = 'Jabatan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_jabatan'; //bonus
	var $TblName_Hapus = 'ref_jabatan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'MASTER DATA';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='Pangkat.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'Jabatan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'JabatanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	var $stGol = array(
			array('1','I'), 
			array('2','II'),
			array('3','III'),
			array('4','IV'),
		);
		
	var $stJabatan = array(
			array('1','Struktur'), 
			array('2','Fungsional'),
			array('3','Fungsional Umum'),
		);
		
	var $stStatus = array(
			array('1','Aktif'), 
			array('2','Tidak Aktif'),
		);	
		
	var $stRuang = array(
			array('a','a'), 
			array('b','b'),
			array('c','c'),
			array('d','d'),
			array('e','e'),
		);
	function setTitle(){
		return 'Jabatan';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];	 
	 $jns_jbt= $_REQUEST['jns_jbt'];
	 $nm_jbt= $_REQUEST['nm_jbt'];
	 $jumlah = $_REQUEST['jumlah'];
	 $status = $_REQUEST['status'];
	 $c1 = $_REQUEST['cmbUrusanForm']; 
	 $c = $_REQUEST['cmbBidangForm'];
	 $d = $_REQUEST['cmbSKPDForm'];
	 if( $err=='' && $jns_jbt =='' ) $err= 'Jenis Jabatan Di Pilih !!';
	 if( $err=='' && $nm_jbt =='' ) $err= 'Nama Jabatan Belum Di Isi !!';
	 if( $err=='' && $jumlah =='' ) $err= 'jumlah Belum Di Isi !!';
	// if( $err=='' && $status =='' ) $err= 'Status Belum Di Pilih !!';
	 		
				
			if($fmST == 0){
			
				/*$get2=mysql_fetch_array(mysql_query("SELECT toRoman(gol) as gol,ruang FROM ref_pangkat  WHERE gol='$gol' and ruang='$ruang'"));
				$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM ref_pangkat WHERE gol='$gol' and ruang='$ruang'"));
				if($get['cnt']>0 ) $err='Golongan "'.$get2['gol'].'" Ruang "'.$get2['ruang'].'" Sudah Ada !';*/
			
				if($err==''){
					$aqry = "INSERT into ref_jabatan (c1,c,d,jns_jabatan,nama,jumlah,status) values('$c1','$c','$d','$jns_jbt','$nm_jbt','$jumlah','$status')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{
			
				

						if($err==''){
						$aqry = "UPDATE ref_jabatan set c='$c1',c='$c',d='$d',jns_jabatan='$jns_jbt',nama='$ruang',nama='$nm_jbt',jumlah='$jumlah',status='$status' where Id='".$idplh."'";	$cek .= $aqry;
								$qry = mysql_query($aqry) or die(mysql_error());
						}
				
			
									
				
			} //end else
					
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
		
		case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
			 $selectedBidang = $_REQUEST['fmSKPDBidang'];
			 
			 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";
		
		     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00'and c1 = '$selectedUrusan'  and e1='000'";	
		
		     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1='$selectedUrusan' and d != '00' and  e = '00' and e1='000' ";
			
			
				$bidang =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	
				$skpd = cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --');
				$content = array('bidang' => $bidang, 'skpd' =>$skpd ,'queryGetBidang' => $kondisiBidang);
			break;
			}
			
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
		case 'hapus':{ //untuk ref_kota
					$idplh= $_REQUEST['Id'];		
					$get= $this->Hapus();
					$err= $get['err']; 
					$cek = $get['cek'];
					$json=TRUE;	
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
   
   function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
			if($err=='' ){
					$qy = "DELETE FROM ref_pangkat WHERE Id='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>";
		return 	
		"<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/master/ref_jabatan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
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
		$aqry = "SELECT * FROM  ref_jabatan WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	/*function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 120;
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
			'jns_jbt' => array( 
						'label'=>'Jenis Jabatan',
						'labelWidth'=>100, 
						'value'=>cmbArray('jns_jbt',$dt['jns_jbt'],$this->stJabatan,'--PILIH Jabatan--',''), 
						 ),
			
			'nm_jbt' => array( 
						'label'=>'Nama Jabatan',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						 ),		
			'jumlah' => array( 
						'label'=>'Jumlah',
						'labelWidth'=>100, 
						'value'=>$dt['jml'], 
						'type'=>'text',
						 ),	
			'status' => array( 
						'label'=>'Status',
						'labelWidth'=>100, 
						'value'=>cmbArray('status',$dt['status'],$this->stStatus,'--PILIH Status--',''), 
						 ),	 
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}*/
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	s 	
	 $form_name = $this->Prefix.'_form';
	 
				
	 $this->form_width = 650;
	 $this->form_height = 200;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		
	 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
	 $selectedBidang = $_REQUEST['fmSKPDBidang'];
     $selectedskpd = $_REQUEST['fmSKPDskpd'];

		$cmbRo = '';
	  }else{
		$this->form_caption = 'Edit';	
		$kode = $dt['kode'];				
		$namapenyedia = $dt['nama_program_kegiatan'];
		$dt['urusan'] = $dt['c1'];	
		$dt['bidang'] = $dt['c'];	
		$dt['skpd'] = $dt['d'];
		$cmbRo = 'disabled';	
		$selectedUrusan = $dt['c1'];
		$selectedBidang = $dt['c'];
     	$selectedskpd =  $dt['d'];


	  }
	    //ambil data trefditeruskan


    	 $kondisiBidang = "";
	 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";


     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00' and c1 = '$selectedUrusan'  and e1='000'";	

     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d != '00' and  e = '00' and e1='000' ";
     $cek .= $codeAndNameskpd;

	  	$query = "select * from ref_skpd " ;$cek .=$query;
	  	$res = mysql_query($query);

$comboBoxUrusanForm = cmbQuery('cmbUrusanForm', $selectedUrusan, $codeAndNameUrusan,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
	
if($_COOKIE['cofmSKPD']!='00'){

	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
}else{
	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
}	

	
	
	 //items ----------------------
	  $this->form_fields = array(
	  	  	'kode0' => array(
	  					'label'=>'URUSAN',
						'labelWidth'=>100, 
						'value'=> $comboBoxUrusanForm
						 ),
	  		'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>100, 
						'value'=> $comboBoxBidangForm
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>100, 
						'value'=> cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --')
						 ),
			
			'jns_jbt' => array( 
						'label'=>'Jenis Jabatan',
						'labelWidth'=>100, 
						'value'=>cmbArray('jns_jbt',$dt['jns_jabatan'],$this->stJabatan,'--PILIH Jabatan--',''), 
						 ),
			
			'nm_jbt' => array( 
						'label'=>'Nama Jabatan',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						 ),		
			'jumlah' => array( 
						'label'=>'Jumlah',
						'labelWidth'=>100, 
						'value'=>inputFormatRibuan('jumlah','size=33', $dt['jumlah'])
					//	'value'=>$dt['jml'], 
					//	'type'=>'',
						 ),	
			'status' => array( 
						'label'=>'Status',
						'labelWidth'=>100, 
						'value'=>cmbArray('status',$dt['status'],$this->stStatus,'--PILIH Status--',''), 
						 ),	 					
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >  &nbsp  ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setNavAtas(){
		global $Main;
		if ($Main->VERSI_NAME=='JABAR') $persediaan = "| <a href='pages.php?Pg=perencanaanbarang_persediaan' title='Perencanaan Persediaan'>Persediaan</a> ";
	
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				
			</tr>
			</table>';
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='10' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='900'>Jenis Jabatan</th>
	   <th class='th01' width='900'>Nama Jabatan</th>
	   <th class='th01' width='100'>Jumlah</th>
	   <th class='th01' width='100'>Status</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 if($isi['status']==1){
	 	$sta='Aktif';
	 }elseif($isi['status']==2){
	 	$sta='Tidak Aktif';
	 }
	 
	 if($isi['jns_jabatan']==1){
	 	$jbt='Struktural';
	 }elseif($isi['jns_jabatan']==2){
	 	$jbt='Fungsional';
	 }elseif($isi['jns_jabatan']==3){
	 	$jbt='Fungsional Umum';
	 }
	 
	 $Koloms[] = array('align="left"',$jbt);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 $Koloms[] = array('align="right"',$isi['jumlah']);
	  $Koloms[] = array('align="left"',$sta);
	 return $Koloms;
	}
	
	/*function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	/* $arr = array(
			//array('selectAll','Semua'),	
			array('selectNama','Nama Pasien'),	
			array('selectAlamat','Alamat'),		
			);
		
	 //data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Jenis Jabatan'),
	  	         array('2','Nama Jabatan'),
			     array('3','Status'),
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	/*$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	$TampilOpt = 
	"<div class='FilterBar' style='margin-top:10px;'><table style='width:100%'>".
			CmbUrusanBidangSkpd('penyedia').
"</table></div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:140px;'> ".cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','')."  </td><td><input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>  &nbsp <input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:150px;'> ".cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."  </td>
			<td style='width:200px;' ><input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'> menurun &nbsp Jumlah Data : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'>  </td><td align='left' ><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>";
			
		return array('TampilOpt'=>$TampilOpt);
	}		*/			
	
	function setMenuView(){
		
			}
	
	
	function genDaftarOpsi(){

	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd, $fmSKPDUrusan;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	 $fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	 $fmBIDANG = cekPOST('fmBIDANG');


	 $arrOrder = array(
	  	          	array('1','Jenis Jabatan'),		
					array('2','Nama Jabatan'),	
					array('3','Jumlah'),
					array('4','Status'),	
					);	
	 //data order ------------------------------
	
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	$baris = $_REQUEST['baris'];
	if ($baris == ''){
		$baris = "25";		
	}
	$TampilOpt = 
	"<div class='FilterBar' style='margin-top:10px;'><table style='width:100%'>".
			CmbUrusanBidangSkpd('Jabatan').
"</table></div>"."<div class='FilterBar' style='margin-top:5px;'>".
			$vOrder=
			genFilterBar(
				array(							
					
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			
			"</div>";
			
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
		/*switch($fmPILCARI){		
			case '1': 
			if($fmPILCARIvalue= '1')$arrKondisi[]= "Struktural";
			//$arrKondisi[] = " jns_jabatan like '%$fmPILCARIvalue'"; break;	 	 	
			case '2': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;
			case '3': $arrKondisi[] = " jumlah like '%$fmPILCARIvalue%'"; break;
			case '4': $arrKondisi[] = " status like '%$fmPILCARIvalue%'"; break;					 	
		}*/
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
		case'1': $arrOrders[]= "jns_jabatan $Asc1 " ;break;	
		case'2': $arrOrders[]= "nama $Asc1 " ;break;	
		case'3': $arrOrders[]= "jumlah $Asc1 " ;break;	
		case'4': $arrOrders[]= "status $Asc1 " ;break;		
				
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
}
$Jabatan = new JabatanObj();
?>