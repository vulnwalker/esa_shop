<?php

class programKegiatanObj  extends DaftarObj2{	
	var $Prefix = 'programKegiatan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_programkegiatan'; //bonus
	var $TblName_Hapus = 'ref_programkegiatan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('p','q');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'DAFTAR PROGRAM DAN KEGIATAN';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='programKegiatan.xls';
	var $namaModulCetak='DAFTAR PROGRAM DAN KEGIATAN';
	var $Cetak_Judul = 'DAFTAR PROGRAM DAN KEGIATAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'programKegiatanForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'DAFTAR PROGRAM DAN KEGIATAN';
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

	  
	 if( $err=='' && $namaprogramKegiatan =='' || $kode1 =='' || $kode2 == '' ) $err= 'Lengkapi !!';
	 
			if($fmST == 0){
				
				if($err==''){
					$aqry = "INSERT into ref_programkegiatan (p,q,nama_program_kegiatan) values ('$kode1','$kode2','$namaprogramKegiatan')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}else{
					
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_programkegiatan set p = '$kode1' , q ='$kode2', nama_program_kegiatan='$namaprogramKegiatan' WHERE concat(p,' ',q)='".$idplh."'";	$cek .= $aqry;
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
		
	    case 'getdata':{
				$Id = $_REQUEST['id'];
					    	
				$get = mysql_fetch_array( mysql_query("select * from ref_programkegiatan where concat(p,' ',q)='$Id'"));
				$selectedP  = $get['p'];
				$refDataNamaKegiatan= "SELECT q, nama_program_kegiatan  FROM ref_programkegiatan where p = '$selectedP' and q <> '00'";
				$content = array('p' => $get['p'], 'q' => $get['q'], 'nama_program_kegiatan' => $get['nama_program_kegiatan'], 'cmbLucknut' => cmbQuery('cmbKegiatan', $get['p'], $refDataNamaKegiatan,'  onChange=\''.$this->Prefix.'.Bidangsdsdfterform()\'','-- Pilih Semua --') );
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
			"<script type='text/javascript' src='js/master/ref_programKegiatan/".$this->Prefix.".js' language='JavaScript' ></script>".
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

		if($cnt['cnt'] > 0) $err = "programKegiatan Tidak Bisa Diubah ! Sudah Digunakan Di Ref Barang.";
		if($err == ''){
			$aqry = "SELECT * FROM  ref_programKegiatan WHERE concat(p,' ',q)='".$this->form_idplh."' "; $cek.=$aqry;
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
	 $this->form_width = 800;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';	
		$kode = $dt['kode'];		
		$chmod444 = 'readonly';		
		$namaprogramKegiatan = $dt['nama_program_kegiatan']	;		
	  }
	    //ambil data trefditeruskan
	  	$query = "select *from " ;$cek .=$query;
	  	$res = mysql_query($query);

	 //items ----------------------
	  $this->form_fields = array(
			'kode' => array( 
						'label'=>'KODE',
						'labelWidth'=>170, 
						'value'=>
						"<input type='text' name='kode1' id='kode1' size='5' maxlength='2' value='".$dt['p']."' ".$chmod444." >&nbsp
						 <input type='text' name='kode2' id='kode2' size='5' maxlength='2' value='".$dt['q']."' ".$chmod444." >&nbsp
						"
						 ),
/*			'namaprogramKegiatan' => array( 
						'label'=>'NAMA PROGRAM / KEGIATAN',
						'labelWidth'=>170, 
						'value'=>$namaprogramKegiatan, 
						'type'=>'text',
						'param'=>"style='width:500px;'"
						 ),	*/
			'namaprogramKegiatan' => array( 
						'label'=>'NAMA PROGRAM / KEGIATAN',
						'labelWidth'=>150, 
						'value'=>"<textarea name='namaprogramKegiatan' id='namaprogramKegiatan' style='width:500px; height : 50px;'>".$namaprogramKegiatan."</textarea> ", 
						 ),		
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan'> &nbsp ".
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
  	   <th class='th01' width='60'>KODE</th>	
	   <th class='th01' width='900'>NAMA PROGRAM / KEGIATAN</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 	  		if($isi['q'] != '00'){
			$margin = 'style="margin-left:15px;"';
			}else{
			$margin = 'style="margin-left:0px; font-weight:bold;" ';	
			}
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="center"',$isi['p']. ".".$isi['q'] ); 
	 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_program_kegiatan']."</span>");
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 $arr = array(
			//array('selectAll','Semua'),	
			array('program','PROGRAM'),		
			array('kegiatan','KEGIATAN'),		
			);

	$queryCmbProgram = "select p,nama_program_kegiatan from ref_programkegiatan where q='00'";
					
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];	
	$cmbProgram = $_REQUEST['cmbProgram'];	
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
			"<div class='FilterBar' >".	
			"<table style='width:100%'>
			<tr>
			<td style='width:100px'>".cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). 				
					"</td><td> <input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue''>
					<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'> </td>
				</tr>
			
			</table>".
			"</div>".
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>".cmbQuery('cmbProgram', $cmbProgram, $queryCmbProgram,'onchange=programKegiatan.refreshList(true)','-- Pilih Program --')." </td>
			 </tr>
			</table>".
			"</div>".
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:200px;'> Jumlah Data : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'> &nbsp &nbsp &nbsp <input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun  </td><td align='left' style='width:1045;'><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";	
			"";
			
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
		$cmbProgram = $_REQUEST['cmbProgram'];
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'program':
			$get = mysql_fetch_array( mysql_query("select p from ref_programkegiatan where nama_program_kegiatan like '%$fmPILCARIvalue%' and q ='00'"));
			$selectedP  = $get['p'];
			$arrKondisi[] = " p = '$selectedP'"; 
			break;	
			
			case 'kegiatan':
			$arrKondisi[] = " nama_program_kegiatan like  '%$fmPILCARIvalue%' "; 
			break;						 
		}
		
		if(isset($cmbProgram) && $cmbProgram !=''){
			$arrKondisi[] = " p =  '$cmbProgram' "; 
		}
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();

		$arrOrders[] = " concat(p,'.',q) $Asc1 " ;
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
		
		
			if($err=='' ){
					$get = mysql_fetch_array( mysql_query("select * from ref_programkegiatan where concat(p,' ',q)='".$ids[$i]."'"));
					$qsamadengannol  = $get['q'];
					$getP = $get['p'];
					if($qsamadengannol == '00'){
						$qy = "DELETE FROM $this->TblName_Hapus WHERE p = '$getP' ";$cek.=$qy;
						$qry = mysql_query($qy);
					}else{
						$qy = "DELETE FROM $this->TblName_Hapus WHERE concat(p,' ',q)='".$ids[$i]."' ";$cek.=$qy;
						$qry = mysql_query($qy);	
					}
					

						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
}
$programKegiatan = new programKegiatanObj();
?>