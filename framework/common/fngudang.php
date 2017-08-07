<?php

class GudangObj  extends DaftarObj2{	
	//var $SHOW_CEK = TRUE;
	var $Prefix = 'Gudang';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_gudang'; //daftar
	var $TblName_Edit = 'ref_gudang';
	var $TblName_Hapus = 'ref_gudang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d','e','e1','id_gudang');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='gudang.xls';
	var $Cetak_Judul = 'DAFTAR GUDANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Gudang';
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');

		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$idplh = $_REQUEST[$this->Prefix.'_idplh'];
				$kode = explode(' ',$idplh);
				//$a1 = $Main->DEF_KEPEMILIKAN;
				//$a = $Main->Provinsi[0];
				//$b = '00';
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$id_gudang= $_REQUEST['id_gudang'];
				$nm_gudang= $_REQUEST['nm_gudang'];
				//$jabatan= $_REQUEST['jabatan'];
				
				
				if( $err=='' && $id_gudang =='' ) $err= 'ID belum diisi!';
				if( $err=='' && $nm_gudang =='' ) $err= 'Nama Gudang belum diisi!';
				//if( $err=='' && $jabatan =='' ) $err= 'Jabatan belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from $this->TblName where c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."' and id_gudang='".$id_gudang."'  "
						));
						if($get['cnt']>0 ) $err='Gudang Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into $this->TblName_Edit (c,d,e,e1,id_gudang,nm_gudang)"."values('$c','$d','$e','$e1','$id_gudang','$nm_gudang')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from $this->TblName where c='".$kode[0]."' and d='".$kode[1]."' and e='".$kode[2]."'  and e1='".$kode[3]."'  and id_gudang='".$kode[4]."' "));
					if( $err=='' ){
						if($id_gudang!=$old['id_gudang'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from $this->TblName where c='".$kode[0]."' and d='".$kode[1]."' and e='".$kode[2]."'  and e1='".$kode[3]."' and id_gudang='".$id_gudang."' "
							));
							if($get['cnt']>0 ) $err='Gudang Sudah Ada!';
						}
					}
					if($err==''){						
						$aqry = "update $this->TblName_Edit set ".
							" c='$c',d='$d',e='$e',e1='$e1',
							id_gudang='$id_gudang',nm_gudang='$nm_gudang'".
							"where c='".$kode[0]."' and d='".$kode[1]."' and e='".$kode[2]."' and e1='".$kode[3]."' and id_gudang='".$kode[4]."' ";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
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
			case 'cbxgedung':{
				$c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
				$d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
				$e1= $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				if($c=='' || $c =='00') {
					$kondC='';
				}else{
					$kondC = "and c = '$c'";
				}
				if($d=='' || $d =='00') {
					$kondD='';
				}else{
					$kondD = "and d = '$d'";
				}
				if($e=='' || $e =='00') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				if($e1=='' || $e1 =='00' || $e1 =='000') {
					$kondE1='';
				}else{
					$kondE1 = "and e1 = '$e1'";
				}

				$aqry = "select * from ref_ruang where q='0000' $kondC $kondD $kondE $kondE1";
				$cek .= $aqry;
				$content = genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						$aqry,
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" );
				break;
			}		
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
				$id = $_REQUEST['id'];
				$kd = explode(' ',$id);
				$aqry = "select * from $this->TblName  ".
					" where c='".$kd[0]."' and d='".$kd[1]."' and e='".$kd[2]."'  and e1='".$kd[3]."' and id_gudang='".$kd[4]."'"; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('id_gudang'=>$get['id_gudang'],'nm_gudang'=>$get['nm_gudang']);
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
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from $this->TblName_Edit where c='".$kode[0]."' and d='".$kode[1]."' and e='".$kode[2]."'  and e1='".$kode[3]."' and id_gudang='".$kode[4]."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 500;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];	
		
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'id_gudang' => array( 'label'=>'ID Gudang', 'value'=>$dt['id_gudang'], 'type'=>'text'  ),	
			'nm_gudang' => array( 'label'=>'Nama Gudang', 'value'=>$dt['nm_gudang'], 'type'=>'text', 'param'=>"style='width:340;'"  ),	
			//'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			//'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			//'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
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
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=150>ID GUDANG</th>
				<th class='th01' >NAMA GUDANG </th>
				
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['id_gudang'] );		
		$Koloms[] = array('', $isi['nm_gudang'] );
		//$Koloms[] = array('', $isi['jabatan']);				
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			
			</table>";
			/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();		
		
		
		/*$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.'00';
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		*/
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');	
		$arrKondisi[] = getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);
		
		 	
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	

}
$Gudang = new GudangObj();


class GudangPilihObj  extends GudangObj{
	var $SHOW_CEK = TRUE;
	var $Prefix = 'GudangPilih';
	var $fileNameExcel='gudang.xls';
	var $Cetak_Judul = 'DAFTAR NAMA GUDANG';
	
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_gudang'; //daftar
	var $TblName_Hapus = 'ref_gudang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d','e','e1','id_gudang');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
		
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	
	/*function setPage_TitleDaftar(){
		return 'Daftar Pegawai';
	}*/	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Pilih Nama Gudang';
	}
	
	function setMenuView(){
		return '';
	}		
	//*
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		/*$fmSKPD = $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_COOKIE['cofmSUBUNIT'];*/
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		
		$TampilOpt =			
			"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
			"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT'  value='$fmUNIT'>".
			"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT'  value='$fmSUBUNIT'>".
			"<input type='hidden' id='".$this->Prefix."SkpdfmSEKSI' name='".$this->Prefix."SkpdfmSEKSI'  value='$fmSEKSI'>";

			/*genFilterBar(
				array( 
					'Gedung &nbsp; '.
					"<span id='cbxRuangGedung' >".
					genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						"select * from ref_ruang where q='0000' and c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT'  ",
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" ).
					"</span>"
				)				
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);	*/		
		return array('TampilOpt'=>$TampilOpt);
	}		
	//*/	
	//*
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$cek='';
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();
		//$arrKondisi[] = 'a='.$Main->Provinsi[0];
		//$arrKondisi[] = 'b='.'00';
		
		/*$fmSKPD = $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_COOKIE['cofmSUBUNIT'];*/
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
				
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00' || $fmSEKSI=='000') ) $arrKondisi[] = "e1='$fmSEKSI'";
		
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " c,d,e,id_gudang ";		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;				
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'cek'=>$cek);		
	}
	//*/
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->Prefix.'_Form';
		
		$fmSKPD = $_REQUEST['fmSKPD']; $cek.= '$fmSKPD='.$fmSKPD;
		$fmUNIT = $_REQUEST['fmUNIT']; $cek.= '$fmUNIT='.$fmUNIT;
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT']; $cek.= '$fmSUBUNIT='.$fmSUBUNIT;
		$fmSEKSI = $_REQUEST['fmSEKSI']; $cek.= '$fmSEKSI='.$fmSEKSI;
				
		$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI);
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						400,
						$this->setTitle(),//'Pilih Pegawai',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
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
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other2($tipe){
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
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='',$fmSEKSI=''){
		$this->fmSKPD = $fmSKPD;
		$this->fmUNIT = $fmUNIT;
		$this->fmSUBUNIT = $fmSUBUNIT;
		$this->fmSEKSI = $fmSEKSI;
		
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmSEKSI' name='".$this->Prefix."SkpdfmSEKSI' value='$fmSEKSI'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
}
$GudangPilih = new GudangPilihObj();


?>