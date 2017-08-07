<?php

class migrasiObj  extends DaftarObj2{	
	var $Prefix = 'migrasi';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_bi_kib_a_tmp'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g');
	var $FieldSum = array('jml_harga','jml_barang');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 5, 4, 4);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);
	var $fieldSum_lokasi = array(6,9);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Administrasi';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'migrasiForm'; 	
			
	function setTitle(){
		return 'Migrasi';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".uploadexcel()","upload_f2.png","Upload Excel",'Upload Excel')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cek()","properties_f2.png","Cek", 'Cek')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".migrasidata()","dbrestore.png","Migrasi", 'Migrasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".hapus()","delete_f2.png","hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".hapussemua()","delete_f2.png","Hapus Semua", 'Hapus Semua').
			"</td>";
	}
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		switch($fmPILKIB){
			case '01':$aqry = "select * from v_bi_kib_a_tmp $Kondisi $Order $Limit ";break;
			case '02':$aqry = "select * from v_bi_kib_b_tmp $Kondisi $Order $Limit ";break;
			case '03':$aqry = "select * from v_bi_kib_c_tmp $Kondisi $Order $Limit ";break;
			case '04':$aqry = "select * from v_bi_kib_d_tmp $Kondisi $Order $Limit ";break;
			case '05':$aqry = "select * from v_bi_kib_e_tmp $Kondisi $Order $Limit ";break;
			case '06':$aqry = "select * from v_bi_kib_f_tmp $Kondisi $Order $Limit ";break;
			case '07':$aqry = "select * from v_bi_kib_g_tmp $Kondisi $Order $Limit ";break;
			default:$aqry = "select * from v_bi_kib_a_tmp $Kondisi $Order $Limit ";break;
		}
		return $aqry;
		//return mysql_query($aqry);
	}
	/*
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
	 $nama_barang = strtoupper($_REQUEST['nama_barang']);
	 $kode_barang = $_REQUEST['kode_barang'];
	 //mendapatkan kode barang
	 $query = "SELECT max(g) AS g FROM ref_barang_persediaan WHERE f = '$kode_barang'"; $cek .= $query;
	 $hasil = mysql_query($query);
	 $data  = mysql_fetch_array($hasil);
	 $g = $data['g']+1;
	 
			if($fmST == 0){ //input ref_barang_persediaan
				if($err==''){ 
					$aqry1 = "INSERT into ref_barang_persediaan (f,g,nama_barang)
					"."values('$kode_barang','$g','$nama_barang')";	$cek .= $aqry1;	
					$qry = mysql_query($aqry1);
					if($qry==FALSE) $err="Gagal simpan barang";							
				}else{
					$err="Gagal menyimpan barang";
				}
			}elseif($fmST == 1){ //edit ref_barang_persediaan						
				if($err==''){
					 $kode_barang = explode(' ',$idplh);
					 $f=$kode_barang[0];	
					 $g=$kode_barang[1];
					 $h=$kode_barang[2];	
					 $i=$kode_barang[3];
					 $j=$kode_barang[4];				 
					$aqry2 = "UPDATE ref_barang_persediaan
		        	 set "." nama_barang = '$nama_barang'".
				 	"WHERE concat(f,g)='".$f.$g."'";	$cek .= $aqry2;
					$qry = mysql_query($aqry2);
					if($qry==FALSE) $err="Gagal Edit barang";							
				}else{
					$err="Gagal menyimpan barang";					
				}
			}else{
			if($err==''){ 

			}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	*/	
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
			 "<script type='text/javascript' src='js/admin/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   /*
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
		//query ambil data barang	
		$aqry = "select * from ref_barang_persediaan where concat(f,g)='".$f.$g."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g;//.'.'.$h.'.'.$i.'.'.$j; 
		$dt['readonly']='readonly';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	*/
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 120;
	 $this->form_caption = 'Upload';
	  				
 	    
		//query golongan
		
       //items ----------------------
		  $this->form_fields = array(									 
									 
			'uploadexcel' => array( 
								'label'=>'Upload File',
								'labelWidth'=>100, 
								'value'=>"<input type='file' name='uploadexcel' id='uploadexcel'>"
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
   	   <th class='th01' width='100'>skpd</th>
	   <th class='th01'width='100'>Kode Barang</th>
	   <th class='th01'>Tahun</th>
	   <th class='th01'>Harga</th>
	   <th class='th01'>Kondisi</th>
	   <th class='th01'>Asal Usul</th>
	   <th class='th01'>Jumlah Barang</th>
	   <th class='th01'>Tanggal Buku</th>	   	   	   
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
		
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;

	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $kode_skpd=$isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$isi['e1'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 	 $Koloms[] = array('align="left" ',$kode_skpd); 
		 $Koloms[] = array('align="left" ',$kode_barang);
		 $Koloms[] = array('align="center" "',$isi['thn_perolehan']);
		 $Koloms[] = array('align="center" "',$isi['jml_harga']);
		 $Koloms[] = array('align="center" "',$isi['kondisi']);
		 $Koloms[] = array('align="center" "',$isi['asal_usul']);
		 $Koloms[] = array('align="center" "',$isi['jml_barang']);
		 $Koloms[] = array('align="center" "',$isi['tgl_buku']); 	 	 	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		//get pilih bidang
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		
		//get selectbox cari data : KIB
		$fmPILKIB = cekPOST('fmPILKIB');
		$thn_perolehan=$_REQUEST['thn_perolehan'];
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//data KIB ----------------------------
		$arrkib = array(
			//array('selectAll','Semua'),
			array('01','KIB A'),
			array('02','KIB B'),
			array('03','KIB C'),
			array('04','KIB D'),
			array('05','KIB E'),
			array('06','KIB F'),
			array('07','KIB G'),
			);
			
 			
		//data order ------------------------------
		$arrOrder = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'),	
			array('3','Tahun Anggaran'),		
		);
		/*
		//get select Order1
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');
		
		//get select Order2
		$fmORDER2 = cekPOST('fmORDER2');
		$fmDESC2 = cekPOST('fmDESC2');
		
		//get select Order3
		$fmORDER3 = cekPOST('fmORDER3');
		$fmDESC3 = cekPOST('fmDESC3');
		*/
		
		//$fmFiltThnAnggaran = $get['maxthn'];
			
		$TampilOpt =
		"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>" . 
				WilSKPD_ajx($this->Prefix.'Skpd')  
			."					
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			"<table> 
				<tr><td><div style='float:left;padding: 2 8 0 0;height:20;'> Tahun Perolehan 
				<input type='text' maxlength='4' size='4' value='$thn_perolehan' id='thn_perolehan' name='thn_perolehan'>
				&nbsp; KIB &nbsp;".
				cmbArray('fmPILKIB',$fmPILKIB,$arrkib,'Cari Data','').
				"</div></td></tr>
				<tr><td><div style='float:left;padding: 2 8 0 0;height:20;'>Tanggal Buku &nbsp; &nbsp;</div>".
				createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','migrasiForm',1)."
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
				</td></tr></table>";
					
		return array('TampilOpt'=>$TampilOpt);
	}		
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		$thn_perolehan=$_REQUEST['thn_perolehan'];	
		$fmGOLONGAN = $_REQUEST['fmGOLONGAN'];
		$fmSUBGOLONGAN = $_REQUEST['fmSUBGOLONGAN'];
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		switch($fmPILKIB){
			case '01': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
								 	
		}
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_buku>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_buku<='$fmFiltTglBtw_tgl2'";
		if(!empty($thn_perolehan)) $arrKondisi[]= " thn_perolehan like '%$thn_perolehan%'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		
		//Order -------------------------------------
		/*$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
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
$migrasi = new migrasiObj();

?>