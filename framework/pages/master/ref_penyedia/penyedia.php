<?php

class penyediaObj  extends DaftarObj2{	
	var $Prefix = 'penyedia';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_penyedia'; //bonus
	var $TblName_Hapus = 'ref_penyedia';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'DAFTAR PENYEDIA';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='penyedia.xls';
	var $namaModulCetak='DAFTAR PENYEDIA';
	var $Cetak_Judul = 'DAFTAR PENYEDIA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'penyediaForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'DAFTAR PENYEDIA';
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

	 foreach ($_REQUEST as $key => $value) { 
		  $$key = $value; 
	 } 

	 $nama_pimpinan_2 =  $_POST['nama_pimpinan'];
	 if( $err=='' && $namapenyedia =='' || $alamatLengkap == '' || $kota == '' || $nama_pimpinan = ''  ) $err= 'Lengkapi !!';
	 
			if($fmST == 0){
				if($err==''){
					if ($_COOKIE['cofmSKPD'] != '00' && $_COOKIE['cofmSKPD'] != '') {
$kueBidang = $_COOKIE['cofmSKPD'];
$kueSKPD = $_COOKIE['cofmUNIT'];
					$aqry = "INSERT into ref_penyedia (c1,c,d,nama_penyedia,alamat,kota,nama_pimpinan,no_npwp,nama_bank,norek_bank,atasnama_bank) values ('$cmbUrusanForm','$kueBidang','$kueSKPD','$namapenyedia','$alamatLengkap','$kota','$nama_pimpinan_2','$nomorNPWP','$nama_bank','$norek_bank','$atasnama_bank')";	$cek .= $aqry;	
					}else{
					$aqry = "INSERT into ref_penyedia (c1,c,d,nama_penyedia,alamat,kota,nama_pimpinan,no_npwp,nama_bank,norek_bank,atasnama_bank) values ('$cmbUrusanForm','$cmbBidangForm','$cmbSKPDForm','$namapenyedia','$alamatLengkap','$kota','$nama_pimpinan_2','$nomorNPWP','$nama_bank','$norek_bank','$atasnama_bank')";	$cek .= $aqry;		
					}

					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){

				$aqry = "UPDATE ref_penyedia set  nama_penyedia ='$namapenyedia', alamat = '$alamatLengkap', kota = '$kota', nama_pimpinan = '$nama_pimpinan_2', no_npwp = '$nomorNPWP', nama_bank = '$nama_bank', norek_bank = '$norek_bank', atasnama_bank = '$atasnama_bank'  WHERE id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());		
					}
			} //end else
					
			return	array ('cek'=>$aqry, 'err'=>$err, 'content'=>$content);	
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
					
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
	   

	case 'BidangAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKELOMPOK = cekPOST('fmKELOMPOK2');
				$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK2');
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content->kelompok = cmbQuery1("fmKELOMPOK2",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBidang' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.KelompokAfter()\"",'Pilih','');
				$content->subkelompok = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
				$content->subsubkelompok = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
			break;
		}
				   			

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
				
			case 'SKPDAfter':{
				$fmSKPDUnit = cekPOST('fmSKPDUnit');
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
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
			"<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/master/ref_penyedia/".$this->Prefix.".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$dt['urusan'] = $_REQUEST['fmSKPDUrusan'];
		$dt['bidang'] = $_REQUEST['fmSKPDBidang'];
		$dt['skpd'] = $_REQUEST['fmSKPDskpd'];
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				

		if($cnt['cnt'] > 0) $err = "penyedia Tidak Bisa Diubah ! Sudah Digunakan Di Ref Barang.";
		if($err == ''){
			$aqry = "SELECT * FROM  ref_penyedia WHERE id='".$this->form_idplh."' "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$fm = $this->setForm($dt);
		}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	s 	
	 $form_name = $this->Prefix.'_form';
	 
				
	 $this->form_width = 600;
	 $this->form_height = 400;
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
						'labelWidth'=>150, 
						'value'=> $comboBoxUrusanForm
						 ),
	  		'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=> $comboBoxBidangForm
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=> cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --')
						 ),
			'jarak' => array( 
						'value'=> "<div style='margin-top: 20px;'></div>"
						 ),
			'namapenyedia' => array( 
						'label'=>'NAMA PENYEDIA',
						'labelWidth'=>150, 
						'value'=>$dt['nama_penyedia'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),	
						 
			'alamatLengkap' => array( 
						'label'=>'ALAMAT LENGKAP',
						'labelWidth'=>150, 
						'value'=>"<textarea name='alamatLengkap' id='alamatLengkap' style='width:400px; height : 100px;'>".$dt['alamat']."</textarea> ", 
						 ),
			'kota' => array( 
						'label'=>'KOTA / KABUPATEN',
						'labelWidth'=>150, 
						'value'=>$dt['kota'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),
			'nama_pimpinan' => array( 
						'label'=>'NAMA PIMPINAN',
						'labelWidth'=>150, 
						'value'=>$dt['nama_pimpinan'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),
			'nomorNPWP' => array( 
						'label'=>'NOMOR NPWP',
						'labelWidth'=>150, 
						'value'=>$dt['no_npwp'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),
			'nama_bank' => array( 
						'label'=>'NAMA BANK',
						'labelWidth'=>150, 
						'value'=>$dt['nama_bank'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),
			'norek_bank' => array( 
						'label'=>'NOREK BANK',
						'labelWidth'=>150, 
						'value'=>$dt['norek_bank'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 ),
			'atasnama_bank' => array( 
						'label'=>'ATAS NAMA',
						'labelWidth'=>150, 
						'value'=>$dt['atasnama_bank'], 
						'type'=>'text',
						'param'=>"style='width:400px;'"
						 )						
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >  &nbsp  ".
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
  	   <th class='th01' width='900'>NAMA PENYEDIA</th>	
	   <th class='th01' width='900'>ALAMAT LENGKAP</th>	
	   <th class='th01' width='900'>KOTA/KABUPATEN</th>	
	   <th class='th01' width='900'>NAMA PIMPINAN</th>	
	   <th class='th01' width='900'>NO. NPWP</th>	
	   <th class='th01' width='900'>NAMA BANK</th>	
	   <th class='th01' width='900'>NOMOR REKENING</th>
	   <th class='th01' width='900'>ATAS NAMA BANK</th>
	   <th class='th01' width='900'>SKPD</th>		
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama_penyedia']); 
	 $Koloms[] = array('align="left"',$isi['alamat']); 
	 $Koloms[] = array('align="left"',$isi['kota']); 
	 $Koloms[] = array('align="left"',$isi['nama_pimpinan']); 
	 $Koloms[] = array('align="left"',$isi['no_npwp']); 
	 $Koloms[] = array('align="left"',$isi['nama_bank']);
	 $Koloms[] = array('align="left"',$isi['norek_bank']);
	 $Koloms[] = array('align="left"',$isi['atasnama_bank']);
	 $c1 = $isi['c1'];
	 $c = $isi['c'];
	 $d = $isi['d'];
	 $get = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c ='$c' and d='$d' ")) ;       
	 $Koloms[] = array('align="left"',$get['nm_skpd']);
	 return $Koloms;
	}
	


	function genDaftarOpsi(){

	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd, $fmSKPDUrusan;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');


	 $arr = array(
			//array('selectAll','Semua'),	
			array('nama_penyedia','NAMA PENYEDIA'),		
			array('alamat','ALAMAT'),	
			array('kota','KOTA / KABUPATEN'),
			array('nama_pimpinan','NAMA PIMPINAN'),
			array('no_npwp','NO. NPWP'),			
			);
		
	 //data order ------------------------------
	 $arrOrder = array(
			     	array('1','NAMA PENYEDIA'),		
					array('2','ALAMAT'),	
					array('3','KOTA / KABUPATEN'),
					array('4','NAMA PIMPINAN'),
					array('5','NO. NPWP'),	
					);
	 
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

		$kueBidang = $_COOKIE['cofmSKPD'];
		$kueSKPD =  $_COOKIE['cofmUNIT'];
		$ref_skpdSkpdfmUrusan = $_REQUEST['fmSKPDUrusan'];
		$ref_skpdSkpdfmSKPD = $_REQUEST['fmSKPDBidang'];
		$ref_skpdSkpdfmUNIT = $_REQUEST['fmSKPDskpd'];
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_penyedia': $arrKondisi[] = " nama_penyedia like '%$fmPILCARIvalue%'"; break;						 
			case 'alamat': $arrKondisi[] = " alamat like '%$fmPILCARIvalue%'"; break;	
			case 'kota': $arrKondisi[] = " kota like '%$fmPILCARIvalue%'"; break;	
			case 'nama_pimpinan': $arrKondisi[] = " nama_pimpinan like '%$fmPILCARIvalue%'"; break;	
			case 'no_npwp': $arrKondisi[] = " no_npwp like '%$fmPILCARIvalue%'"; break;
			case 'SKPD': $arrKondisi[] = " c like '%$fmPILCARIvalue%' or d like '%$fmPILCARIvalue%' "; break;				
		}
		

		if($kueBidang!='00' and $kueBidang!='')$arrKondisi[]= "c='$kueBidang'";
		if($kueSKPD!='00' and $kueSKPD!='')$arrKondisi[]= "d='$kueSKPD'";


		if($ref_skpdSkpdfmUrusan!='0' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='00' ){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
		if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";

		if($ref_skpdSkpdfmSKPD!='00'){

		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		}
		}
		

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama_penyedia $Asc1 " ;break;
			case '2': $arrOrders[] = " alamat $Asc1 " ;break;
			case '3': $arrOrders[] = " kota $Asc1 " ;break;
			case '4': $arrOrders[] = " nama_pimpinan $Asc1 " ;break;
			case '5': $arrOrders[] = " no_npwp $Asc1 " ;break;
			case '6': $arrOrders[] = " c $Asc1 " ;break;
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
		
			$a = "SELECT count(*) as cnt, aa.penyedia_terbesar, aa.penyedia_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_penyedia bb ON aa.penyedia_terbesar = bb.nama OR aa.penyedia_terkecil = bb.nama WHERE bb.nama='".$ids[$i]."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "penyedia ".$ids[$i]." Tidak Bisa DiHapus ! Sudah Digunakan Di Ref Barang.";
		
			if($err=='' ){
					$qy = "DELETE FROM $this->TblName_Hapus WHERE id='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
}
$penyedia = new penyediaObj();
?>