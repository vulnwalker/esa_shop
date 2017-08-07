<?php

class popupHistoriObj  extends DaftarObj2{	
	var $Prefix = 'popupHistori';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'histori_tahap'; //daftar
	var $TblName_Hapus = 'histori_tahap';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
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
	var $FormName = 'popupHistoriForm'; 	
			
	function setTitle(){
		return 'Histori';
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
				$idHistori = $_REQUEST['idHistori'];
				$fm = $this->windowShow($idHistori);
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			break;
		}

	   	case 'getdata':{
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			 }
			/*$id_Histori = $_REQUEST['id'];
			$get = mysql_fetch_array( mysql_query("select * from ref_barang where id='$id_Histori'"));*/
			$idHistori = "";
			$namaHistori = "";
			for ($i = 0 ; $i <= sizeof($popupHistori_cb); $i ++) {
			 	$jsonID =  $popupHistori_cb[$i];
				$get = mysql_fetch_array(mysql_query("select * from ref_Histori where id_Histori = '$jsonID'"));
				if($namaHistori == ""){
					$pemisah = "";
					$pisah = "";
				}else{
					$pemisah = " , "; 
					$pisah = ";";
				}
				$idHistori = $idHistori.$pisah.$get['id_Histori'];
				$namaHistori = $namaHistori.$pemisah.$get['nama_Histori'];
			}
			
			$content = array('namaHistori' => $namaHistori, 'idHistori' => $idHistori);	
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
	
	function windowShow($idHistori){		
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
						'HISTORI',
						'',
						"<input type='hidden' name='idHistori' id='idHistori' value='$idHistori'>".
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
	   <th class='th01' width='900'>HISTORI</th>
	   <th class='th01' width='200'>TANGGAL MULAI</th>
	   <th class='th01' width='200'>TANGGAL SELESAI</th>
	   <th class='th01' width='200'>TANGGAL UPDATE</th>
	   <th class='th01' width='200'>USER</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 $id_tahap = $isi['id_tahap'];
	 
	 $arrayID = array();
	 $query = "select  * from histori_tahap where id_tahap ='$id_tahap'";
	 $execute = mysql_query($query);
	 while ($rows = mysql_fetch_array($execute)){
			array_push($arrayID,$rows['id']);
	 }
	 $berapa = array_search($isi['id'], $arrayID);
	 if($berapa == '0'){
	 	$histori = "NORMAL";
	 }else{
	 	$histori = "PERUBAHAN KE ".$berapa;
	 }
	 $Koloms[] = array('align="left"',$histori);
	 $tanggal_mulai = explode("-",$isi['tanggal_mulai']);
	 $tanggal_mulai = $tanggal_mulai[2]."-".$tanggal_mulai[1]."-".$tanggal_mulai[0];	 
	 $Koloms[] = array('align="center"',$tanggal_mulai);
	 $tanggal_selesai = explode("-",$isi['tanggal_selesai']);
	 $tanggal_selesai = $tanggal_selesai[2]."-".$tanggal_selesai[1]."-".$tanggal_selesai[0];	 
	 $Koloms[] = array('align="center"',$tanggal_selesai);
	 $tanggal_update = explode("-",$isi['tanggal_update']);
	 $tanggal_update = $tanggal_update[2]."-".$tanggal_update[1]."-".$tanggal_update[0];	 
	 $Koloms[] = array('align="center"',$tanggal_update);
	 $Koloms[] = array('align="left"',$isi['user']);
	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 $idTahap = $_REQUEST['idHistori'];
	 $namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idTahap' "));
	 $namaTahap = $namaTahap['nama_tahap'];
	 
	 $TampilOpt =  "<table style='width:100%;'> <td>".$namaTahap."</td>";
		
	 	
			
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
		$idTahap = $_REQUEST['idHistori'];

		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

			
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_Histori': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;						 
			case 'status': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
		}

		$arrKondisi[] = " id_tahap = '$idTahap'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case 'nama_Histori': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'status': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

		/*$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;

		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	*/
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$popupHistori = new popupHistoriObj();

?>