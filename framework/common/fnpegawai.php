<?php

class PegawaiObj  extends DaftarObj2{	
	var $Prefix = 'Pegawai';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_pegawai'; //daftar
	var $TblName_Hapus = 'ref_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pegawai.xls';
	var $Cetak_Judul = 'DAFTAR PEGAWAI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Pegawai';
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
				$a1 = $Main->DEF_KEPEMILIKAN;
				$a = $Main->Provinsi[0];
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$nip= $_REQUEST['nip'];
				$nama= $_REQUEST['nama'];
				$jabatan= $_REQUEST['jabatan'];
				
				
				if( $err=='' && $nip =='' ) $err= 'NIP belum diisi!';
				if( $err=='' && $nama =='' ) $err= 'Nama belum diisi!';
				if( $err=='' && $jabatan =='' ) $err= 'Jabatan belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from ref_pegawai where nip='$nip' "
						));
						if($get['cnt']>0 ) $err='NIP Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into ref_pegawai (a,b,c,d,e,e1,nip,nama,jabatan)"."values('$a','$b','$c','$d','$e','$e1','$nip','$nama','$jabatan')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from ref_pegawai where Id='$idplh'"));
					if( $err=='' ){
						if($nip!=$old['nip'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from ref_pegawai where nip='$nip' "
							));
							if($get['cnt']>0 ) $err='NIP Sudah Ada!';
						}
					}
					if($err==''){
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						$aqry = "update ref_pegawai set ".
							" a='$a', b='$b', c='$c',d='$d',e='$e',e1='$e1',
							nip='$nip',nama='$nama', jabatan='$jabatan'".
							"where id='".$idplh."'";	$cek .= $aqry;
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
				$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
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
		$aqry = "select * from ref_pegawai where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 500;
		$this->form_height = 160;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
			$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='$kdSubUnit0'  "));
		$subunit = $get['nm_skpd'];		

		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];		
		
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
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
				<th class='th01' width=150>NIP</th>
				<th class='th01' >NAMA </th>
				<th class='th01' >JABATAN </th>								
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['nip']);		
		$Koloms[] = array('', $isi['nama'] );
		$Koloms[] = array('', $isi['jabatan']);				
		return $Koloms;
	}
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	/*$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmBARANG = cekPOST('fmBARANG');			
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
			<tr>
			<td style='width:120px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>";
			/*"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='14' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";	*/		
	
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arrOrder = array(
	  	          array('1','Nip'),
			     	array('2','Nama'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectNip','Nip'),	
			array('selectNama','Nama'),		
			);
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".
			"<tr><td>".
			"<div id='skpd_pegawai' ></div>".
			$vOrder=			
			genFilterBar(
				array(							
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). //generate checkbox					
					"&nbsp&nbsp<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>&nbsp&nbsp"
					//<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>"
					
					.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		/*$ref_skpdSkpdfmSEKSI = $_REQUEST['ref_skpdSkpdfmSEKSI'];//ref_skpdSkpdfmSKPD
		$ref_skpdSkpdfmSKPD = $_REQUEST['ref_skpdSkpdfmSKPD'];
		$ref_skpdSkpdfmUNIT = $_REQUEST['ref_skpdSkpdfmUNIT'];
		$ref_skpdSkpdfmSUBUNIT = $_REQUEST['ref_skpdSkpdfmSUBUNIT'];*/
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			//case 'selectKode': $arrKondisi[] = " c='".$isivalue[0]."' and d='".$isivalue[1]."' and e='".$isivalue[2]."' and e1='".$isivalue[3]."'"; break;
			case 'selectNip': $arrKondisi[] = " nip like '$fmPILCARIvalue%'"; break;
			case 'selectNama': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;	
								 	
		}	
		/*if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !='')$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";
		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='')$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		if($ref_skpdSkpdfmSUBUNIT!='00' and $ref_skpdSkpdfmSUBUNIT !='')$arrKondisi[]= "e='$ref_skpdSkpdfmSUBUNIT'";
		if($ref_skpdSkpdfmSEKSI!='00' and $ref_skpdSkpdfmSEKSI !='')$arrKondisi[]= "e1='$ref_skpdSkpdfmSEKSI'";*/
		/*$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//Cari 
		switch($fmPILCARI){			
			case 'selectNama': $arrKondisi[] = " nama_pasien like '%$fmPILCARIvalue%'"; break;
			case 'selectAlamat': $arrKondisi[] = " alamat like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	*/
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nip $Asc1 " ;break;
			case '2': $arrOrders[] = " nama $Asc1 " ;break;
			
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
}
$Pegawai = new PegawaiObj();


class PegawaiPilihObj  extends PegawaiObj{
	var $Prefix = 'PegawaiPilih';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_pegawai'; //daftar
	var $TblName_Hapus = 'ref_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pegawai.xls';
	var $Cetak_Judul = 'DAFTAR PEGAWAI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	/*function setPage_TitleDaftar(){
		return 'Daftar Pegawai';
	}*/	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Pilih Pegawai ';
	}
	
	function setMenuView(){
		return '';
	}		
	
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
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];	
	//	$TampilOpt='tes';
		$TampilOpt =			
		/*	"<input type='hidden' id='PegawaiPilihSkpdfmSKPD' name='PegawaiPilihSkpdfmSKPD' value='$fmSKPD'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmUNIT' name='PegawaiPilihSkpdfmUNIT'  value='$fmUNIT'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmSUBUNIT' name='PegawaiPilihSkpdfmSUBUNIT'  value='$fmSUBUNIT'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmSEKSI' name='PegawaiPilihSkpdfmSEKSI'  value='$fmSEKSI'>";
			genFilterBar(
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
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".
		//	"<tr><td>".
			"<div id='skpd_pegawai' ></div>".
			$vOrder=			
			genFilterBar(
				array(							
				//	cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). //generate checkbox					
				//	"&nbsp&nbsp<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>&nbsp&nbsp"
					//<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>"
					
				//	.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
				//	"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
			$this->Prefix.".refreshList(true)");
		//	"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			
		return array('TampilOpt'=>$TampilOpt);
	}		
		
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$cek='';
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();
		$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.$Main->DEF_WILAYAH;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
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
		$arrOrders[] = " a,b,c,d,e,e1,nip ";		
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
	
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->Prefix.'_Form';
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
				
		$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI);
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						400,
						'Pilih Pegawai',
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
	
	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='', $fmSEKSI=''){
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
$PegawaiPilih = new PegawaiPilihObj();


?>