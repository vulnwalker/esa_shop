<?php
 include "pages/pencarian/DataPengaturan.php";
 $DataOption = $DataPengaturan->DataOption();
 
class cariBarangObj  extends DaftarObj2{	
	var $Prefix = 'cariBarang';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_barang'; //bonus
	var $TblName_Hapus = 'ref_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	//var $KeyFields = array('f1','f2','f','g','h','i','j');
	var $KeyFields = array('f','g','h','i','j');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'ADMINISTRASI SYSTEM';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'cariBarang';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'cariBarangForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'DAFTAR BARANG';
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
	 global $Main, $DataOption;
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
		
		case 'getid':{
				$cek = '';
				$err = '';
				$content = '';	
				
				$kodebarangambil = $_REQUEST['kodebarangambil'];
				if($idrekening == '' && $err == '')$err == "Data Belum Dipilih !";
				
				if($err == ''){
					$kode = explode(".",$kodebarangambil);
					
					if($DataOption['kode_barang'] == '1'){
						$where_kode = "concat(f,'.',g,'.',h,'.',i,'.',j)";
						$htng_stringKode = strlen($kode[4]);
						$kodei = $kode[4];
					}else{
						$where_kode = "concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j)";
						$htng_stringKode = strlen($kode[6]);
						$kodei = $kode[6];
					}
					
					$qry = "SELECT * FROM ref_barang WHERE $where_kode = '$kodebarangambil' ";$cek.=$qry;
					$aqry = mysql_query($qry);
					$daqry = mysql_fetch_array($aqry);
						
					
					if($htng_stringKode >= 3){
						if($kodei == '000' || $daqry['nm_barang'] == null){
							$err='Kode Tidak Valid !';
						}else{
							$content['kodebarang'] = $kodebarangambil;
							$content['namabarang'] = $daqry['nm_barang'];
							$content['satuan'] = $daqry['satuan'];
						}
					}else{
						$content['kodebarang'] = '';
						$content['namabarang'] = '';
						$content['satuan'] = '';
					}
					
					
		
				}
				
					
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
			"<script type='text/javascript' src='js/pencarian/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
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
	  	   <th class='th01' width='5'>NO.</th>".
	  	   /*$Checkbox*/"		
		   <th class='th01'>KODE BARANG</th>
		   <th class='th01'>NAMA BARANG</th>
		   <th class='th01'>SATUAN</th>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref,$DataOption;
	 	$F1F2 = '';
	 	if($DataOption['kode_barang'] == '2')$F1F2=$isi['f1'].".".$isi['f2'].".";
	 
	 	$kodebarang = $F1F2.$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	 	//$kodebarang = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  /*if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);*/
	 $Koloms[] = array('align="center" width="150"',"<a href='javascript:".$this->Prefix.".pilBar(`".$kodebarang."`)' >".
	 	$kodebarang."</a>");
	 $Koloms[] = array('align="left"',$isi['nm_barang']);
	 $Koloms[] = array('align="left"',$isi['satuan']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main,$DataOption;
	 
	 if($DataOption == '2'){
	 	$cmbAkun = $_REQUEST['cmbAkun'];
		$cmbKelompok = $_REQUEST['cmbKelompok'];
		
		$dataBarangF1F2 = "<tr>
			<td style='width:150px;'>AKUN</td><td style='width:10px;'>:</td>
			<td>".
			cmbQuery1("cmbAkun",$cmbAkun,"select f1 as valueCmbAkun , nm_barang from ref_barang where f1 != '0' and f2 = '0' and f = '00' and g ='00' and h ='00' and i='00' and j = '000'  ","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
			</tr>
			<tr>
			<td style='width:170px;' >KELOMPOK</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("cmbKelompok",$cmbKelompok,"select f2 as valueCmbKelompok , nm_barang from ref_barang where f1 = '$cmbAkun' and f2 != '0' and f = '00' and g ='00' and h ='00' and i='00' and j = '000' ","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
			</tr>";
		$whereF1F2 = " f1 = '$cmbAkun' and f2 = '$cmbKelompok' and ";
	 }else{
	 	$dataBarangF1F2='';
		$whereF1F2='';
	 }
	
	$cmbJenis = $_REQUEST['cmbJenis'];
	$cmbObyek = $_REQUEST['cmbObyek'];
	$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
	$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
	$cmbSubSubRincianObyek = $_REQUEST['cmbSubSubRincianObyek'];	
	$fmKODE = $_REQUEST['fmKODE'];	
	$fmBARANG = $_REQUEST['fmBARANG'];			
	//$fmPILCARI = $_REQUEST['fmPILCARI'];	
	//$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//$fmORDER1 = cekPOST('fmORDER1');
	//$fmDESC1 = cekPOST('fmDESC1');
	
	
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
	 
	 
				
	$TampilOpt = 
			//"<tr><td>".	
			"<div class='FilterBar'>".
			//<table style='width:100%'><tbody><tr><td align='left'>
			//<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			//<tbody><tr valign='middle'>   						
			//	<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			
			"<table style='width:100%'>
			$dataBarangF1F2
			<tr>
			<td style='width:170px;' >JENIS</td><td>:</td>
			<td>".
			cmbQuery1("cmbJenis",$cmbJenis,"select f as valueCmbJenis, nm_barang from ref_barang where $whereF1F2 f != '00'  and g ='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
			</tr>
			<tr>
			<td style='width:170px;'>OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbObyek",$cmbObyek,"select g as valueCmbObyek, nm_barang from ref_barang where $whereF1F2 f = '$cmbJenis'  and g !='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
			</tr><tr>
			<td style='width:170px;'>RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbRincianObyek",$cmbRincianObyek,"select h as valueCmbRincianObyek, nm_barang from ref_barang where $whereF1F2 f = '$cmbJenis'  and g ='$cmbObyek' and h !='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
				</tr>
			<tr>
			<td style='width:170px;'>SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubRincianObyek",$cmbSubRincianObyek,"select i as valueCmbSubRincianObyek, nm_barang from ref_barang where $whereF1F2 f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i != '00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\" style='width:300px;'",'Pilih','').
			"</td>
				</tr>
			"./*"<tr>
			<td style='width:170px;'>SUB-SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubSubRincianObyek",$cmbSubSubRincianObyek,"select j as valueCmbSubSubRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i='$cmbSubRincianObyek' and j != '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>"*/"
			
			</table>".
			"</div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}				
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS,$DataOption;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		$cmbAkun = $_REQUEST['cmbAkun'];
		$cmbKelompok = $_REQUEST['cmbKelompok'];
		$cmbJenis = $_REQUEST['cmbJenis'];
		$cmbObyek = $_REQUEST['cmbObyek'];
		$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
		$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
		
		$fmKODE = $_REQUEST['fmKODE'];	
		$fmBARANG = $_REQUEST['fmBARANG'];	
		
		$fmKODE = $_REQUEST['fmKODE'];
		$kodebar = explode(".",$fmKODE);
		//Cari 
		/*switch($fmPILCARI){			
			case 'selectSatuan': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	*/
		/*$arrKondisi[] = " k != '0'";
		$arrKondisi[] = " l != '0'";
		$arrKondisi[] = " m != '0'";
		$arrKondisi[] = " n != '00'";
		$arrKondisi[] = " o != '00'";*/
	
		$arrKondisi[] = " f != '0'";
		$arrKondisi[] = " g != '00'";
		$arrKondisi[] = " h != '00'";
		$arrKondisi[] = " i != '00'";
		$arrKondisi[] = " j != '000'";
		
		if($cmbAkun != '')$arrKondisi[] = " f1 = '$cmbAkun'";
		if($cmbKelompok != '')$arrKondisi[] = " f2 = '$cmbKelompok'";
		if($cmbJenis != '')$arrKondisi[] = " f = '$cmbJenis'";
		if($cmbObyek != '')$arrKondisi[] = " g = '$cmbObyek'";
		if($cmbRincianObyek != '')$arrKondisi[] = " h = '$cmbRincianObyek'";
		if($cmbSubRincianObyek != '')$arrKondisi[] = " i = '$cmbSubRincianObyek'";
		
		if($fmBARANG != '')$arrKondisi[] = " nm_barang like '%$fmBARANG%'";
		
		if($DataOption['kode_barang'] == '2'){
			$arrKondisi[] = " f1 != '0'";
			$arrKondisi[] = " f2 != '0'";
			if(isset($kodebar[0]))if($kodebar[0] != '')$arrKondisi[] = " f1 = '".$kodebar[0]."'";
			if(isset($kodebar[1]))if($kodebar[1] != '')$arrKondisi[] = " f2 = '".$kodebar[1]."'";
			if(isset($kodebar[2]))if($kodebar[2] != '')$arrKondisi[] = " f = '".$kodebar[2]."'";
			if(isset($kodebar[3]))if($kodebar[3] != '')$arrKondisi[] = " g = '".$kodebar[3]."'";
			if(isset($kodebar[4]))if($kodebar[4] != '')$arrKondisi[] = " h = '".$kodebar[4]."'";
			if(isset($kodebar[5]))if($kodebar[5] != '')$arrKondisi[] = " i = '".$kodebar[5]."'";
			if(isset($kodebar[6]))if($kodebar[6] != '')$arrKondisi[] = " j = '".$kodebar[6]."'";	
		}else{
			if(isset($kodebar[2]))if($kodebar[2] != '')$arrKondisi[] = " f = '".$kodebar[0]."'";
			if(isset($kodebar[3]))if($kodebar[3] != '')$arrKondisi[] = " g = '".$kodebar[1]."'";
			if(isset($kodebar[4]))if($kodebar[4] != '')$arrKondisi[] = " h = '".$kodebar[2]."'";
			if(isset($kodebar[5]))if($kodebar[5] != '')$arrKondisi[] = " i = '".$kodebar[3]."'";
			if(isset($kodebar[6]))if($kodebar[6] != '')$arrKondisi[] = " j = '".$kodebar[4]."'";
		}
		
		//$arrKondisi[] = " q='00'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		/*if($fmORDER1 == ''){
			$arrOrders[] = " p ";
			$arrOrders[] = " q ";
		}
		switch($fmORDER1){
			case '1': $arrOrders[] = " p $Asc1 " ;break;
		}	*/
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
						'Pilih Barang',
						'',
						/*"
						<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".*/
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' value='' id='idrekeningnya1' >".
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
}
$cariBarang = new cariBarangObj();
?>