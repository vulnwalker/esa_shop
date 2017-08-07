<?php

class MasterMerkObj  extends DaftarObj2{	
	var $Prefix = 'MasterMerk';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_merk_persediaan'; //daftar
	var $TblName_Hapus = 'ref_merk_persediaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('h');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'MASTER DATA';
	var $PageIcon = 'images/administrator/images/payment.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'MERK';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'MasterMerkForm'; 	
			
	function setTitle(){
		return 'MERK BARANG';
	}
	function setMenuEdit(){
		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 
	 $kode_merk = $_REQUEST['kode_merk'];
	 $nama_merk = strtoupper($_REQUEST['nama_merk']);
	 $h=mysql_fetch_array(mysql_query("select * from ref_merk_persediaan where nama_merk = '".$nama_merk."'"));	 	 
	 if($h['nama_merk']!='') $err="Nama Merk sudah ada";
	 
			if($fmST == 0){ //input ref_merk_persediaan
				if($err==''){ 
					 $kode_barang = explode('.',$_REQUEST['kode_barang']);
					 $f=$kode_barang[0];	
					 $g=$kode_barang[1];
					 $h=$kode_barang[2];	
					 $i=$kode_barang[3];
					 $j=$kode_barang[4];	 	  
					$aqry1 = "INSERT into ref_merk_persediaan (h,nama_merk)
							 "."values('$kode_merk','$nama_merk')";	$cek .= $aqry1;	
					$qry = mysql_query($aqry1);
					$content->no=1;							
					$content->kode_merk=mysql_insert_id();
					$content->merk=$nama_merk;							
				}
			}elseif($fmST == 1){						
				if($err==''){

				}
			}else{
			if($err==''){ 

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
			 "<script type='text/javascript' src='js/ref_persediaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/ref_persediaan/masterharga.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/rekammedis/pasien.js' language='JavaScript' ></script>".
			  "<script type='text/javascript' src='js/kasir/tagihan.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		//get data 
		$bulan=date('Y-m-')."1";
		$dt['readonly']='';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4]; 
		$bulan=date('Y-m-')."1";
		$aqry = "select * from ref_merk_persediaan where h='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j; 
		$dt['readonly']='readonly';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'DATA BARU';
	  }else{
		$this->form_caption = 'EDIT';	  	
	  }
	  
	  	$jenis = array(
			array('1','ATK'), 
			array('2','OBAT'),
		);
		
	  	$satuan = array(
			array('1','Rim'), 
			array('2','Pack'),
			array('3','Botol'), 
			array('4','Tablet'),			
		);
				
 	    $username=$_REQUEST['username'];
		//query ref_batal
		$querySatuan = "SELECT Id,nama_satuan FROM ref_satuan_persediaan";
       //items ----------------------
		  $this->form_fields = array(									 
			'nama_merk' => array( 
								'label'=>'Nama Merk',
								'labelWidth'=>100, 
								'value'=>$dt['nama_merk'], 
								'type'=>'text',
								'id'=>'nama_merk',
								'param'=>"style='width:250ppx;text-transform: uppercase;' size=50px"
									 ),	
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >".
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
   	   <th class='th01' width='100'>Kode Merk</th>
	   <th class='th01' width='200'>Nama Merk</th>
	   </tr>
	   </thead>";
	
		return $headerTable;
	}
	
	/*function setPage_HeaderOther(){
		$Pg = $_REQUEST['Pg'];
		
		$barang = '';
		$merk = '';
		$type = '';
		$spec = '';
		$persediaan = '';
		switch ($Pg){
			case 'masterbarang': $barang ="style='color:blue;'"; break;
			case 'mastermerk': $merk ="style='color:blue;'"; break;
			case 'mastertype': $type ="style='color:blue;'"; break;
			case 'masterspec': $spec ="style='color:blue;'"; break;
			case 'persediaan': $persediaan ="style='color:blue;'"; break;
		}
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=masterbarang\" title='Barang' $barang>Barang </a> |
			<A href=\"pages.php?Pg=mastermerk\" title='Persediaan'  $merk>Merk</a> |   	
			<A href=\"pages.php?Pg=mastertype\" title='Persediaan'  $type>Type</a> |   	
			<A href=\"pages.php?Pg=masterspec\" title='Persediaan'  $spec>Spesifikasi</a> |   	
			<A href=\"pages.php?Pg=masterharga\" title='Persediaan'  $persediaan>Harga Barang</a>    												
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}*/
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;

	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left" width="100"',$isi['h']);
 	 $Koloms[] = array('align="left" width="200"',$isi['nama_merk']); 	 	 	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	
	
	 $arr = array(
			//array('selectAll','Semua'),
			array('selecth','Kode Merk'),
			array('selectmerk','Nama Merk'),	
			);
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Merk'),
			     array('2','Nama Merk'),	
	 );	
				
	$TampilOpt = 
			"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			$cari =
			cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','').'&nbsp'. //generate checkbox
			"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>
			<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Periode : </div>".
			//createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1).
			cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."&nbsp".					
			"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>menurun".
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'><br>".						
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		
		switch($fmPILCARI){
			case 'selecth': $arrKondisi[] = " h like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectmerk': $arrKondisi[] = " nama_merk like '%".$fmPILCARIvalue."%'"; break;					 	
		}
		
				
		/*if(empty($fmGOLONGAN) && empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f !=00 and g=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g!=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h!=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && !empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h=$fmMERK and i!=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && !empty($fmMERK) && !empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h=$fmMERK and i=$fmTYPE and j!=00";
		}*/
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			//case '': $arrOrders[] = " tgl_kunjungan DESC " ;break;
			case '1': $arrOrders[] = " h $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_merk $Asc1 " ;break;
		
		}

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
$MasterMerk = new MasterMerkObj();

?>