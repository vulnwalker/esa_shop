<?php
class ManagementSystem2Obj  extends DaftarObj2{	
	var $Prefix = 'ManagementSystem2';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'system'; //bonus
	var $TblName_Hapus = 'system';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id_system');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Management System';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='refmigrasi.xls';
	var $namaModulCetak='REFERENSI DATA';
	var $Cetak_Judul = 'ManagementSystem';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ManagementSystem2Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'Management System';
	}
	
	function setMenuEdit(){
		
		
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
				$Id = $_REQUEST['id'];
				$get = mysql_fetch_array( mysql_query("select * from system where Id_system='$Id'"));
				
				$content = array('id' => $get['Id_system'], 'kode' => $get['kode'], 'nm_system' => $get['nm_system']);
							
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
						setTimeout(function myFunction() {ManagementSystem.nyalakandatepicker()},1000);
					</script>";
		return 	
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			"<link rel='stylesheet' href='css/template_css.css' type='text/css'>".
			"<link rel='stylesheet' href='css/upload_style.css' type='text/css'>".
			"<script src='js/jquery.js' type='text/javascript'></script>".	
			"<script src='js/jquery-ui.js' type='text/javascript'></script>".
			"<script src='js/jquery.min.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/jquery.form.js'></script> ".
			"<script type='text/javascript' src='js/admin/ManagementSystem/ManagementSystem.js' language='JavaScript' ></script>".
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			'.
					
			$scriptload;
	}
	
	//form ==================================
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		$ref_jenis=$_GET['status_filter'];
		$status_filter=1;
		//if($err==''){
			$FormContent = $this->genDaftarInitial(1);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						500,
						'Pilih Management System',
						'',
						
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='CariBarang_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='CariBarang_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
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
	
   
	function setPage_HeaderOther(){
	return "";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   <th class='th01' width='80'>Kode</th>
   	   <th class='th01' width='300'>Nama System</th>
   	   <th class='th01' width='300'>Kelompok System</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;

	
	if($isi['kel_user_system']==1){
	 	$kel='DEVELOPMENT';
	 }elseif($isi['kel_user_system']==2){
	 	$kel='SUPERADMIN';
	 }elseif($isi['kel_user_system']==3){
	 	$kel='ADMINISTRATOR';
	 }elseif($isi['kel_user_system']==4){
	 	$kel='MANAGER';
	 }elseif($isi['kel_user_system']==5){
	 	$kel='OPERATOR';
	 }elseif($isi['kel_user_system']==6){
	 	$kel='USER';
	 }elseif($isi['kel_user_system']==7){
	 	$kel='CUSTOMER';
	 }elseif($isi['kel_user_system']==8){
	 	$kel='PUBLIK';
	 }
	 
	 if($isi['status_system']==1){
	 	$status='AKTIF';
	 }elseif($isi['status_system']==2){
	 	$status='TIDAK AKTIF';
	 }
	$Id_system=$isi['Id_system'];	
 	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 $Koloms[] = array('align="right"',$isi['kode']);
	  $Koloms[] = array('align="left" width=""',"<a style='cursor:pointer;' onclick=ManagementSystem2.windowSave('$Id_system')>".$isi['nm_system']."</a>");
	$Koloms[] = array('align="left"',$kel);
	
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
		
		return array('TampilOpt'=>$TampilOpt);
	}				
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$arrKondisi[]="status_system<>'2'";
			
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		$arrOrders[] = " kode asc ";
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
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
	
	
	function setMenuView(){
		
			}
			
}
$ManagementSystem2 = new ManagementSystem2Obj();
?>