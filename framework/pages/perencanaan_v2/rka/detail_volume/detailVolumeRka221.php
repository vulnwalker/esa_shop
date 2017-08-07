<?php

class detailVolumeRka221Obj  extends DaftarObj2{	
	var $Prefix = 'detailVolumeRka221';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'temp_detail_volume_rka_2_2_1'; //bonus
	var $TblName_Hapus = 'ref_rincian_template';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'RINCIAN TEMPLATE';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='detailVolumeRka221.xls';
	var $namaModulCetak='RINCIAN TEMPLATE';
	var $Cetak_Judul = 'RINCIAN TEMPLATE';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'detailVolumeRka221Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0

	var $username = "";
	
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
		
		case 'updateTempDetail':{
			foreach ($_REQUEST as $key => $value) { 
					  $$key = $value; 
					}	
			$data = array('jumlah' => $VALUE);
			mysql_query(VulnWalkerUpdate("temp_detail_volume_rka_2_2_1",$data,"id = '$ID'"));
			
			$grabTotalJumlah = mysql_fetch_array(mysql_query("select sum(jumlah) from  temp_detail_volume_rka_2_2_1 where username = '$this->username'"));
			$content = array('total' => $grabTotalJumlah['sum(jumlah)']);
			
			break;
		}
		
		case 'setTempTemplate': {
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
			$username = $_COOKIE['coID'];
			$status = "";
			
			$get = mysql_fetch_array(mysql_query("select * from temp_template where username = '$username'"));
			
			if($get['username'] == $username){
				$status = "644"; 
			}else{
				$status = "777";
			}
			
			if ($status == "644" ){
			
			}else{
				$aqry = "insert into temp_template (username,nama_template,tanggal,nomor_distribusi,c1,c,d)  values('$username','$nama_template','$tanggal','$nomor_distribusi','$cmbUrusanForm','$cmbBidangForm','$cmbSKPDForm')";	$cek .= $aqry;	
				$execute = mysql_query($aqry);	
				if($execute){
					$status = "ADDED";
				}else{
					$status = "FAILURE";
				}
				
				
				$maxID = "";
		
				$get = mysql_fetch_array(mysql_query("select max(id) as aaa from temp_template where username = '$username' "));
				$maxID = $get['aaa'];
				
				
				$query = mysql_query("select * from ref_skpd where c1='$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e !='00' and e1 !='000'");
				while($row = mysql_fetch_array($query)){
					$e = $row['e'];
					$e1= $row['e1'];
					$nama_sub_unit = $row['nm_skpd'];	
		
					$aqry = "INSERT into temp_detail_volume_rka_2_2_1 (c1,c,d,e,e1,nama_sub_unit,ref_id_template,username) values ('$cmbUrusanForm','$cmbBidangForm','$cmbSKPDForm','$e','$e1','$nama_sub_unit','$maxID','$username')";
					mysql_query($aqry);	
					
							
				}
				
				
				
			
			
			}
			
			


			
			$content = array('c1' => $cmbUrusanForm, 'c' =>$cmbBidangForm, 'd' => $cmbSKPDForm, 'e' => $cmbUnitForm, 'nama_template' => $nama_template , 'nomor_distribusi' => $nomor_distribusi, 'tanggal' => $tanggal, 'username' => $username, 'status' => $status );
			break;
		}
		
		
		case 'setCookiesUnit': {
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
			setcookie('TemplateUrusan',$cmbUrusanForm);
			setcookie('TemplateBidang',$cmbBidangForm);
			setcookie('TemplateSkpd',$cmbSKPDForm);	
			setcookie('TemplateUnit',$cmbUnitForm);
			break;
		}
		
		 case 'setValueTemplate':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			
		$username = $_COOKIE['coID'];
		$arrayID = array();
		$query = "select id from temp_detail_volume_rka_2_2_1 where c1='$c1' and c='$c' and d='$d' and e='$e' and username = '$username' ";
		$execute = mysql_query($query);
		while($row = mysql_fetch_array($execute)){
				array_push($arrayID,array('id' => $row['id']));
		}
			$content = json_encode($arrayID) ;		
			$cek = sizeof($arrayID) - 1;

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
			<script type='text/javascript' src='js/perencanaan_v2/rka/detail_volume/detailVolumeRka221.js' language='JavaScript'></script>
			
			".
			
			$scriptload;
	}
	
	function setTopBar(){
	   	return '';
	}
	
	//form ==================================
	
	
	function setPage_HeaderOther(){

	}
		
function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5'>No.</th>
  	   <th class='th01' width='2000'>NAMA SUB UNIT</th>	
	   <th class='th01' width='100'>JUMLAH</th>		
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 $idnya = $isi['id'];
	 foreach ($isi as $key => $value) { 
				  $$key = $value; 
			}
	 $Koloms = array();
	 $getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' "));
	 $Koloms[] = array('align="center"', $no.'.' );
	 $Koloms[] = array('align="left"',$getSubUnit['nm_skpd']); 
	 $Koloms[] = array('align="center"',"<input type='text' name='$idnya' id='$idnya'  align='center' style='text-align: right;  width:50px' value='".$isi['jumlah']."'onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup=$this->Prefix.setValCurrentTextbox(this); >"); 
	 

	 return $Koloms;
	}
	


	function genDaftarOpsi(){

	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');


		
$baris = $_REQUEST['baris'];
	if ($baris == ''){
		$baris = "25";		
	}
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	$baris = $_REQUEST['baris'];
	
	$getTotalInput = mysql_fetch_array(mysql_query("select sum(jumlah) from $this->TblName where username = '$this->username'"));
	$totalInput = $getTotalInput['sum(jumlah)'];
	
			
		$TampilOpt ="<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td  style='width:100%;' align='right'>JUMLAH : <span id='tempatJumlah'>$totalInput </span> </td>
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
		

		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];



		$username = $_COOKIE['coID'];

		$this->pagePerHal=$fmLimit;


		$arrKondisi[]= "username='$username'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " id $Asc1 " ;
			
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
		$cmbUnit = $_REQUEST['cmbUnit'];
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order , 'NoAwal'=>$NoAwal);
		
	}
	

}
$detailVolumeRka221 = new detailVolumeRka221Obj();
$detailVolumeRka221->username = $_COOKIE['coID'];
?>