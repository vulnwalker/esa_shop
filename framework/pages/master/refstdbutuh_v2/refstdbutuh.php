<?php

class refstdbutuh_v2Obj  extends DaftarObj2{	
	var $Prefix = 'refstdbutuh_v2';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = "ref_std_kebutuhan inner join ref_barang on concat(ref_std_kebutuhan.f,'.',ref_std_kebutuhan.g,'.',ref_std_kebutuhan.h,'.',ref_std_kebutuhan.i,'.',ref_std_kebutuhan.j) = concat(ref_barang.f,'.',ref_barang.g,'.',ref_barang.h,'.',ref_barang.i,'.',ref_barang.j)"; //daftar
	var $TblName_Hapus = 'ref_std_kebutuhan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c1', 'c', 'd', 'e', 'e1', 'f', 'g', 'h', 'i', 'j');
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
	var $Cetak_Judul = 'Daftar Standar Kebutuhan Barang Maksimal';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'refstdbutuh_v2Form'; 	
	var $kode_skpd = '';
			
	function setTitle(){
		return 'Standar Kebutuhan Barang Maksimal';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	function setMenuView(){
		return "";
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
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
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
	 if($kodeBarang == '')$err='Barang Belum Di Pilih';

	 if(empty($jumlah))$err="Isi Jumlah";
	 $arrayKodeBarang = explode(".",$kodeBarang);
	 $f = $arrayKodeBarang[0];
	 $g = $arrayKodeBarang[1];
	 $h = $arrayKodeBarang[2];
	 $i = $arrayKodeBarang[3];
	 $j = $arrayKodeBarang[4];
	 
			if($fmST == 0){ 
				 if(empty($cmbUrusanForm) || empty($cmbBidangForm) || empty($cmbSKPDForm) || empty($cmbUnitForm) || empty($cmbSubUnitForm))$err="Lengkapi SKPD";
				if($err==''){ 
						$data = array ('c1' => $cmbUrusanForm,
									   'c' => $cmbBidangForm,
									   'd' => $cmbSKPDForm,
									   'e' => $cmbUnitForm,
									   'e1' => $cmbSubUnitForm,
									   'f' => $f,
									   'g' => $g,
									   'h' => $h,
									   'i' => $i,
									   'j' => $j,
									   'jumlah' => $jumlah
									   );
						 $cek .= VulnWalkerInsert("ref_std_kebutuhan",$data);
						$input =  mysql_query(VulnWalkerInsert("ref_std_kebutuhan",$data));
						if($input){
							
						}else{
							$err="Gagal Simpan";
						}
							
				}
			}elseif($fmST == 1){		
			 			$data= array('jumlah' => $jumlah);
						$cek .= VulnWalkerUpdate("ref_std_kebutuhan",$data,"concat(c1,' ',c,' ',d,' ',e,' ',e1' ',f,' ',g,' ',h,' ',i,' ',j) = '$idplh'");	
						mysql_query(VulnWalkerUpdate("ref_std_kebutuhan",$data,"concat(c1,' ',c,' ',d,' ',e,' ',e1,' ',f,' ',g,' ',h,' ',i,' ',j) = '$idplh'"));				

			}else{
			/*if($err==''){ 
						$kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
 						
						$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga)
						"."values('$f','$g','$h','$i','$j','$tahun_anggaran','$harga')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);
						 
				}*/
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
	  
	case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
			 $selectedBidang = $_REQUEST['fmSKPDBidang'];
			 $selectedskpd = $_REQUEST['fmSKPDskpd'];
			 $selectedUnit = $_REQUEST['fmSKPDUnit'];
			 $selectedSubUnit= $_REQUEST['fmSKPDSubUnit'];
			 
			 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";
		
		     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00'and c1 = '$selectedUrusan'  and e1='000'";	
		
		     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1='$selectedUrusan' and d != '00' and  e = '00' and e1='000' ";
			
			 $codeAndNameUnit = "SELECT e, concat(e, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e != '00' and e1='000' ";
     $cek .= $codeAndNameUnit;
	 
	 $codeAndNameSubUnit = "SELECT e1, concat(e1, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e = '$selectedUnit' and e1 !='000' ";
     $cek .= $codeAndNameUnit;
			
				$bidang =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	
				$skpd = cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
				$unit = cmbQuery('cmbUnitForm', $selectedUnit, $codeAndNameUnit,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
				$subUnit = cmbQuery('cmbSubUnitForm', $selectedSubUnit, $codeAndNameSubUnit,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
				$content = array('bidang' => $bidang, 'skpd' =>$skpd, 'unit' =>$unit, 'subUnit' => $subUnit ,'queryGetBidang' => $kondisiBidang);
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
			 "<script type='text/javascript' src='js/master/refstdbutuh_v2/refstdbutuh.js' language='JavaScript' ></script>".		
			 "<script type='text/javascript' src='js/master/refstandarharga_v2/refbarang.js' language='JavaScript' ></script>".		
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$dt=array();
		
		$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['c1'] = $c1; 
		$dt['c'] = $c; 
		$dt['d'] = $d; 
		$dt['e'] = $e; 
		$dt['e1'] = $e1;
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;

		$aqry = "select * from ref_std_kebutuhan where concat(c1,' ',c,' ',d,' ',e,' ',e1,' ',f,' ',g,' ',h,' ',i,' ',j) ='".$this->form_idplh."' "; $cek.=$aqry;
		

		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 900;
	 $this->form_height = 200;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
		$selectedUrusan = $dt['c1'];
	    $selectedBidang = $dt['c'];
        $selectedskpd = $dt['d'];
		$selectedUnit = $dt['e'];
		$selectedSubUnit = $dt['e1'];
		$cmbRo = '';
	  }else{
		$this->form_caption = 'EDIT';
		$selectedUrusan = $dt['c1'];
	    $selectedBidang = $dt['c'];
        $selectedskpd = $dt['d'];
		$selectedUnit = $dt['e'];
		$selectedSubUnit = $dt['e1'];
		
		$kodebarang = $dt['f']." ".$dt['g']." ".$dt['h']." ".$dt['i']." ".$dt['j'];
		$syntax = "select * from ref_barang where concat(f,' ',g,' ',h,' ',i,' ',j) = '$kodebarang' ";
		$getBarang = mysql_fetch_array(mysql_query($syntax));
		$dt['kodeBarang'] = str_replace(" ",".",$kodebarang);
		$dt['namaBarang'] = $getBarang['nm_barang'];
		$dt['satuan'] = $getBarang['satuan'];
		
		$jumlah = $dt['jumlah'];
		$kodeBarang = $dt['kodeBarang'];
		$namaBarang = $dt['namaBarang'];
		$satuan = $dt['satuan'];
		$cmbRo = "disabled";
	  }

	  				
    	$kondisiBidang = "";
	 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";


     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00' and c1 = '$selectedUrusan'  and e1='000'";	

     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d != '00' and  e = '00' and e1='000' ";
     $cek .= $codeAndNameskpd;
	 
	 $codeAndNameUnit = "SELECT e, concat(e, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e != '00' and e1='000' ";
     $cek .= $codeAndNameUnit;
	 
	 $codeAndNameSubUnit = "SELECT e1, concat(e1, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e = '$selectedUnit' and e1 !='000' ";
     $cek .= $codeAndNameUnit;

$comboBoxUrusanForm = cmbQuery('cmbUrusanForm', $selectedUrusan, $codeAndNameUrusan,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
		
$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');

$comboBoxSKPD =  cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');     

$comboBoxUnit =  cmbQuery('cmbUnitForm', $selectedUnit, $codeAndNameUnit,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --'); 

$comboBoxSubUnit =  cmbQuery('cmbSubUnitForm', $selectedSubUnit, $codeAndNameSubUnit,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');          
	
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
						'value'=>$comboBoxSKPD
						 ),
			'kode3' => array( 
						'label'=>'UNIT',
						'labelWidth'=>150, 
						'value'=>$comboBoxUnit						
						 ),
			'kode4' => array( 
						'label'=>'SUB UNIT',
						'labelWidth'=>150, 
						'value'=>$comboBoxSubUnit						
						 ),
			'kodeBarang' => array( 
								'label'=>'KODE DAN NAMA BARANG',
								'labelWidth'=>200, 
								'value'=>"<input type='text' name='kodeBarang' value='".$kodeBarang."' size='15px' id='kodeBarang' readonly>&nbsp
										  <input type='text' name='namaBarang' value='".$namaBarang."' size='70px' id='namaBarang' readonly>&nbsp
										  <input type='button' value='Cari' $cmbRo onclick ='".$this->Prefix.".Cari()' title='Cari Barang' >" 
									 ),

			'satuan' => array(
						'label'=>'SATUAN',
						'labelWidth'=>200, 
						'value'=>$satuan,
						'param'=>"style= 'width:200px;' readonly",
						'type'=>'text'
									 ),
				'jumlah' => array( 
						'label'=>'JUMLAH',
						'labelWidth'=>120, 
						'value'=> "<input type='text' name='jumlah' id='jumlah' value='$jumlah' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='document.getElementById(`bantu`).innerHTML = popupBarang_v2.formatCurrency2(this.value);' style='text-align:right'> <font color='red' id='bantu' name='bantu'></font>"  
							  ),									 	
								 
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >&nbsp &nbsp ".
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
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox
	   <th class='th01' align='center' width='100'>SUB UNIT</th>		
   	   <th class='th01' align='center' width='100'>KODE BARANG</th>
	   <th class='th01' align='center' width='900'>NAMA BARANG</th>	   	   	   
   	   <th class='th01' align='center' width='100'>SATUAN</th>
	   <th class='th01' align='center' width='100'>JUMLAH</th>	
	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	
	
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 
	 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
	 $getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
	 $Koloms[] = array('align="left" width="200"',$getSubUnit['nm_skpd']);	 	
	 $Koloms[] = array('align="center" width="100" ',$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j']);
	 
	 $kodeBarang =$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
	 $syntax = "select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
	 $getBarang = mysql_fetch_array(mysql_query($syntax));
 	 $Koloms[] = array('align="left" width="200"',$getBarang['nm_barang']);	 	 	 	 
	 $Koloms[] = array('align="left" width="100" ',$getBarang['satuan']);
 	 $Koloms[] = array('align="right" width="200"',number_format($isi['jumlah'],0,',','.'));
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main,  $HTTP_COOKIE_VARS;
	
	
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
	 
	 if($Main->WITH_THN_ANGGARAN){
		$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
				thn_akun<=$fmThnAnggaran";
				$qry1=mysql_query($aqry1);			
				$qry_jurnal=mysql_fetch_array($qry1);
				$thn_akun=$qry_jurnal['thnMax'];
				//$arrKondisi[] = " thn_akun = '$thn_akun'";														
		$vthnakun = " and thn_akun=$thn_akun ";
			
	}	
	 
				
	$TampilOpt = 
			//"<tr><td>".	
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajxVW("refstdbutuh_v2Skpd") . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				
			</td></tr>			
			</table>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp".
				"<input type='hidden' id='filterAkun' name='filterAkun' value='".$filterAkun."'>".
				"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>";		
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID  = $_COOKIE['coID']; 
		$c1   = $_REQUEST['refstdbutuh_v2SkpdfmUrusan'];
		$c    = $_REQUEST['refstdbutuh_v2SkpdfmSKPD'];
		$d    = $_REQUEST['refstdbutuh_v2SkpdfmUNIT'];
		$e    = $_REQUEST['refstdbutuh_v2SkpdfmSUBUNIT'];
		$e1   = $_REQUEST['refstdbutuh_v2SkpdfmSEKSI'];	
			$fmKODE = $_REQUEST['fmKODE'];
	$fmBARANG = $_REQUEST['fmBARANG'];
			
		$arrKondisi = array();		
		
		if(!empty($c1) && $c1!="0" ){
			$arrKondisi[] = "c1 = $c1";
		}else{
			$c = "";
		}
		if(!empty($c ) && $c!="00"){
			$arrKondisi[] = "c = $c";
		}else{
			$d = "";
		}
		if(!empty($d) && $d!="00"){
			$arrKondisi[] = "d = $d";
		}else{
			$e = "";
		}
		if(!empty($e) && $e!="00"){
			$arrKondisi[] = "d = $e";
		}else{
			$e1 = "";
		}
		if(!empty($e1) && $e1!="000")$arrKondisi[] = "e1 = $e1";
 		if(empty($fmKODE)){
			
		}else{
			$arrKondisi[]= "concat(ref_std_kebutuhan.f1,'.',ref_std_kebutuhan.f2,'.',ref_std_kebutuhan.f,'.',ref_std_kebutuhan.g,'.',ref_std_kebutuhan.h,'.',ref_std_kebutuhan.i,'.',ref_std_kebutuhan.j) like '$fmKODE%'";
		}
		if(empty($fmBARANG)){
			
		}else{
			/*$query = "select * from ref_barang where nm_barang like '%$fmBARANG%'";
			$execute = mysql_query($query);
			while($row = mysql_fetch_array($execute)){
				$KodeBarang = $row['f1'].".".$row['f2'].".".$row['f'].".".$row['g'].".".$row['h'].".".$row['i'].".".$row['j'];
				$arrKondisiLike[]= "concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = $KodeBarang";
			}*/
			
			$arrKondisi[]="nm_barang like '%$fmBARANG%'";
			
		}
	
		
		$Kondisi= join(' and ',$arrKondisi);	
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();

		$arrOrders[] = " concat(ref_std_kebutuhan.f1,'.',ref_std_kebutuhan.f2,'.',ref_std_kebutuhan.f,'.',ref_std_kebutuhan.g,'.',ref_std_kebutuhan.h,'.',ref_std_kebutuhan.i,'.',ref_std_kebutuhan.j) ASC " ;
			

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
$refstdbutuh_v2 = new refstdbutuh_v2Obj();

?>