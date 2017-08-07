<?php

class popupProgramRKBMD_v3Obj  extends DaftarObj2{	
	var $Prefix = 'popupProgramRKBMD_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_program'; //bonus
	var $TblName_Hapus = 'ref_program';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('bk','ck','dk','p');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'ADMINISTRASI SYSTEM';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'Pemasukan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'popupProgramRKBMD_v3Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'DAFTAR PROGRAM';
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
				
				$kode = explode(".",$id);
				$abk = $kode[0];
				$ack = $kode[1];
				$adk = $kode[2];
				$ap  = $kode[3];
				
				$getDataRenja = mysql_fetch_array(mysql_query("select * from view_renja_v3 where id_anggaran = '$ID_RENJA'"));
				foreach ($getDataRenja as $key => $value) { 
				  $$key = $value; 
				}
				if($jenis_form_modul == "VALIDASI"){
					
				}
				$getView = mysql_query("select * from view_renja_v3 where c1 = '$c1' and c='$c'  and d = '$d' and e ='$e' and e1 ='$e1' and bk ='$abk' and ck = '$ack' and p='$ap' and tahun = '$tahun' and jenis_anggaran = '$jenis_anggaran' and id_tahap = '$id_tahap'");
				$arrayID = array();
				while($rows = mysql_fetch_array($getView)){
					if($rows['jenis_form_modul'] == "VALIDASI" && $rows['status_validasi'] != "1"){
						
					}else{
						if($rows['q'] == '0'){
							
						}else{
							$concat = $rows['bk'].".".$rows['ck'].".".$rows['p'].".".$rows['q'];
							$arrayID[]  = "concat(bk,'.',ck,'.',p,'.',q) = '$concat' " ;
						}
						
					}
					
				}
				$Condition= join(' or ',$arrayID);
				if($kategori == "PENGADAAN"){
					$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$abk' and ck = '$ack' and dk = '$adk' and p = '$ap' and q !='0' and kategori !='2' and $Condition";
				}elseif($kategori == "PEMELIHARAAN"){
					$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$abk' and ck = '$ack' and dk = '$adk' and p = '$ap' and q !='0' and kategori !='1' and $Condition";
				}
				
				$cmbKegiatan = cmbQuery('q', $selecteQ, $codeAndNameKegiatan,' ','-- KEGIATAN --');  
				
				
				$getNamaProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk = '$abk' and ck = '$ack' and dk = '$adk' and p = '$ap' and q = '0'"));
				$program = $getNamaProgram['nama'];
				$content = array("bk" => $abk, "ck" => $ack, "p" => $ap, "cmbKegiatan" => $cmbKegiatan, "program" => $program);
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
			"<script type='text/javascript' src='js/perencanaan/rkbmd/popupProgram.js' language='JavaScript' ></script>".
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
		 setcookie("coUrusanProgram", "", time()-3600);
		 setcookie('coBidangProgram', "", time()-3600);
		 unset($_COOKIE['coProgram']);
   		 
		return
		

		"<html>".
			$this->genHTMLHead().
			"<body >".
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".	
				"<tr height='34'><td>".					
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
					"
					<input type='hidden' name='ID_RENJA' value='".$_REQUEST['ID_RENJA']."' />".
				    "<input type='hidden' name='kategori' value='".$_REQUEST['kategori']."' />".
										
						$this->setPage_Content().

					$form2.
					"</div></div>".
				"</td></tr>".
				"<tr><td height='29' >".	
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>".
			"</body>
		</html>"; 
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
	return "";
			/*"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=bagian\" title='Organisasi' >Organisasi</a> |
	<A href=\"pages.php?Pg=pegawai\" title='Pegawai' >Pegawai</a> |
	<A href=\"pages.php?Pg=barang\" title='Barang'>Barang</a> |
	<A href=\"pages.php?Pg=jenis\" title='Jenis'  >Jenis</a> |
	<A href=\"pages.php?Pg=satuan\" title='Satuan' style='color:blue' >Satuan</a> 
	&nbsp&nbsp&nbsp	
	</td></tr></table>";*/
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5'>No.</th>".
	  	   /*$Checkbox*/"		
		   <th class='th01'>Kode</th>
		   <th class='th01'>Program</th>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $bk = $isi['bk'];
	 if(strlen($bk) == 1)$bk = "0".$bk;
	 
	 $ck = $isi['ck'];
	 if(strlen($ck) == 1)$ck = "0".$ck;
	 
	 $dk = $isi['dk'];
	 if(strlen($dk) == 1)$dk = "0".$dk;
	 
	 $p = $isi['p'];
	 if(strlen($p) == 1)$p="0".$p;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 $Koloms[] = array('align="center" width="15%"',$bk.".".$ck.".".$dk.".".$p);
	 $tak = $isi['bk'].".".$isi['ck'].".".$isi['dk'].".".$isi['p'];
	 $Koloms[] = array('align="left"',"<a style='cursor:pointer;' onclick = popupProgramRKBMD_v3.windowSave('$tak'); >" .$isi['nama']."</a>" );
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 $ID_RENJA = cekPOST('ID_RENJA');	
	 $arr = array(
			//array('selectAll','Semua'),	
			array('selectSatuan','Satuan'),		
			);
		
	 //data order ------------------------------
	 $arrOrder = array(
			     	array('1','Satuan'),
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<tr><td>".
			$vOrder=
			genFilterBar(
				array(							
					"<input type='text' value='".$fmPILCARIvalue."' placeholder='NAMA PROGRAM' name='fmPILCARIvalue' id='fmPILCARIvalue' size='70'>
					<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'> 
					<input type='hidden' id ='ID_RENJA' name ='ID_RENJA' value='$ID_RENJA'>"
					),			
				'','');
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
		$ID_RENJA = $_POST['ID_RENJA'];
		if($fmPILCARIvalue !='')$arrKondisi[] = " nama like '%$fmPILCARIvalue%' ";
		
		$getDataRenja = mysql_fetch_array(mysql_query("select * from tabel_anggaran_v3 where id_anggaran = '$ID_RENJA'"));
		foreach ($getDataRenja as $key => $value) { 
		  $$key = $value; 
		}
		$getView = mysql_query("select * from tabel_anggaran_v3 where  q ='0' and c1 = '$c1' and c= '$c' and d = '$d' and e ='$e' and e1 ='$e1' and tahun = '$tahun' and jenis_anggaran = '$jenis_anggaran' and id_tahap = '$id_tahap'");
		$arrayID = array();
		while($rows = mysql_fetch_array($getView)){
			$concat = $rows['bk'].".".$rows['ck'].".".$rows['p'];
			$abk = $rows['bk'];
			$ack = $rows['ck'];
			$ap = $rows['p'];	
			$arrayKondisiKegiatan = array();
			$getKegiatan = mysql_query("select * from tabel_anggaran_v3  where bk='$abk' and ck='$ack'  and p='$ap' and q !='0' and c1 = '$c1' and c= '$c' and d = '$d' and e ='$e' and e1 ='$e1' and tahun = '$tahun' and jenis_anggaran = '$jenis_anggaran' and id_tahap = '$id_tahap' ");
			while($root = mysql_fetch_array($getKegiatan)){
				if($root['jenis_form_modul'] == "VALIDASI" && $root['status_validasi'] !='1'){
				}else{
					$concat3 = $root['bk'].".".$root['ck'].".".$root['p'].".".$root['q'];
					$arrayKondisiKegiatan[]  = "concat(bk,'.',ck,'.',p,'.',q) = '$concat3' " ;
			
				}
				
			}
			
			$kondisiKegiatan = join(' or ',$arrayKondisiKegiatan);	
			if($_REQUEST['kategori'] == "PENGADAAN"){
					$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$abk' and ck = '$ack' and dk = '0' and p = '$ap' and q !='0' and kategori !='2' and $kondisiKegiatan";
			}elseif($_REQUEST['kategori'] == "PEMELIHARAAN"){
					$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$abk' and ck = '$ack' and dk = '0' and p = '$ap' and q !='0' and kategori !='1' and $kondisiKegiatan";
			}
			$got = mysql_query($codeAndNameKegiatan);
			if(mysql_num_rows($got) > 0){
				$arrayID[]  = "concat(bk,'.',ck,'.',p) = '$concat' " ;	
			}
			//$arrayID[] = "vulnwalker    ".mysql_num_rows($got)."       vulnwalker";
		}
		$Condition= join(' or ',$arrayID);	
		$arrKondisi[] = "($Condition)";
		$arrKondisi[] = " q='0' ";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		if($fmORDER1 == ''){
			$arrOrders[] = " bk ";
			$arrOrders[] = " ck ";
			$arrOrders[] = " dk ";
			$arrOrders[] = " p ";
		}
		switch($fmORDER1){
			case '1': $arrOrders[] = " p $Asc1 " ;break;
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
	
	function setTopBar(){
	   	return '';
	}	
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		$ID_RENJA = $_REQUEST['ID_RENJA'];
		$kategori = $_REQUEST['kategori'];
		$form_name = $this->FormName;
		//$ref_jenis=$_REQUEST['ref_jenis'];
		//if($err==''){
			$FormContent = $this->genDaftarInitial($ref_jenis);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						900,
						500,
						'Pilih Program',
						'',
						/*"
						<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".*/
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='CariBarang_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='CariBarang_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >".
						
						"<input type='hidden' id='kategori' name='kategori' value='$kategori'  >".
						"<input type='hidden' id='ID_RENJA' name='ID_RENJA' value='$ID_RENJA'  >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
}
$popupProgramRKBMD_v3 = new popupProgramRKBMD_v3Obj();
?>