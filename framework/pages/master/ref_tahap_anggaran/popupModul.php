<?php

class popupModulObj  extends DaftarObj2{	
	var $Prefix = 'popupModul';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_modul'; //daftar
	var $TblName_Hapus = 'ref_modul';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id_modul');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='25';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'popupModulForm'; 	
			
	function setTitle(){
		return 'MODUL';
	}
	function setMenuEdit(){		
		return
			"";
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
	
	
		
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  

		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			break;
		}

	   	case 'getdata':{
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 }
			/*$id_modul = $_REQUEST['id'];
			$get = mysql_fetch_array( mysql_query("select * from ref_barang where id='$id_modul'"));*/
			$idModul = "";
			$namaModul = "";
			$arrayJenisForm = array();
			
			
			 	
				$get = mysql_fetch_array(mysql_query("select * from ref_modul where id_modul = '$jsonID'"));
				if($namaModul == ""){
					$pemisah = "";
					$pisah = "";
				}else{
					$pemisah = " , "; 
					$pisah = ";";
				}
				
				$idModul = $get['id_modul'];
				$namaModul = $get['nama_modul'];
			
			
			if($namaModul == "RKBMD"){
					$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
							 				array('KOREKSI PENGGUNA' , 'KOREKSI PENGGUNA'),
							 			    array('KOREKSI PENGELOLA' , 'KOREKSI PENGELOLA'));
				}else{
				
				if($namaModul == "R-APBD" || $namaModul == "APBD" || $namaModul == "DPA"){
					$arrayJenisForm = array(array('READ' , 'READ'));
				}else{
					$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
							 				array('KOREKSI' , 'KOREKSI'));
				}
				
					
			}
			$cmbJenisForm = cmbArray('jenisForm',$jenisForm,$arrayJenisForm,'-- JENIS TAHAP --');
			
			$content = array('namaModul' => $namaModul, 'idModul' => $idModul, 'jenisForm' => $cmbJenisForm);	
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
			 "<script type='text/javascript' src='js/master/ref_tahap_anggaran/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	
	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='',$tahun_anggaran='', $height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
			$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						500,
						'Pilih Modul',
						'',
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
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
		
	//daftar =================================	
function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
	   <th class='th01' width='900'>NAMA MODUL</th>
	   <th class='th01' width='200'>STATUS</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  $$key = $value; 
	 } 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 $Koloms[] = array('align="left"',"<a style='cursor:pointer;' onclick=$this->Prefix.windowSave('$id_modul'); >". $isi['nama_modul'] ."</a>");
	 $Koloms[] = array('align="left"',$isi['status']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
		
	 //data order ------------------------------
	 
	  $arr = array(
						array('nama_modul','NAMA MODUL'),		
						array('status','STATUS')			
			);
	 
	 $arrOrder = array(
				     	array('nama_modul','NAMA MODUL'),		
						array('status','STATUS')				
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
$fmORDER1 = $_REQUEST['fmORDER1'];


	$fmDESC1 = cekPOST('fmDESC1');
	$baris = $_REQUEST['baris'];
	if($baris == ''){
		$baris = "25";
	}
	$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:100px;'> ".cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','')."  </td><td><input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>  &nbsp <input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:100px;'> ".cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."  </td>
			<td style='width:200px;' ><input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'> menurun &nbsp Jumlah Data : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'>  </td><td align='left' ><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>"
			
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


		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

			
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_modul': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;						 
			case 'status': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
		}

		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case 'nama_modul': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'status': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;

		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$popupModul = new popupModulObj();

?>