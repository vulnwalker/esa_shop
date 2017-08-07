<?php
class dpb_spk_detObj extends DaftarObj2{
	var $Prefix = 'dpb_spk_det';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'pengadaan';//'v1_rkb'
	var $TblName_Hapus = 'pengadaan';
	var $TblName_Edit = 'pengadaan';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_barang','harga','jml_harga');
	var $fieldSum_lokasi = array( 6,8,9);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 12, 11, 11);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	var $totalCol = 15; //total kolom daftar
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Pengadaan';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='dpb_spk_det.xls';
	var $Cetak_Judul = 'DAFTAR PENGADAAN BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/pengadaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".			 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/perencanaan/dkb.js' language='JavaScript' ></script>".
			$scriptload;
	}
	function setTitle(){
		return '';
	}
	function setNavAtas(){
		return
			'';
	}
	function setMenuEdit(){		
		return
			"";
	}
	
	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $Pengadaan_Menu=
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
			<div class='menuBar2' style='' >
			<ul>
			$Pengadaan_Menu
			<li><a href='javascript:dpb_spk_det.Hapus()' title='Hapus' class='btdel'></a></li>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'></li>
				</ul>
			</li>
			<!--<li><a  href='javascript:dpb_spk_det.Edit2()' title='Edit' class='btedit'></a>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'>	</li>
				</ul>
			</li>--!>
			<li><a  href='javascript:dpb_spk_det.Baru2()' title='Tambah' class='btadd'></a>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'>	</li>
				</ul>
			</li>
			
			</ul>	
			
			</div>
			</td></tr></table>";
	$TampilCheckBox = $Cetak? '' : "<TH class=\"th01\" style='width:30'>
									<input type=\"checkbox\" name=\"dpb_spk_det_toggle\" id=\"dpb_spk_det_toggle\" value=\"\" onclick=\"checkAll4(25,'dpb_spk_det_cb','dpb_spk_det_toggle','dpb_spk_det_jmlcek');\">";
	
	  $headerTable =
	 "<thead>
	 <tr>
	   <th colspan='15'>$Pengadaan_Menu</th>
	  </tr>
	  		<tr>
				<th class='th01' width='40' width='40'>No.</th>
				$TampilCheckBox		
				<th class='th01' width='' width=''>Kode Barang/ Kode Akun</th>
				<th class='th01' width='' width=''>Nama Barang/ Nama Akun</th>				
				<th class='th01' >Merk/ spesifikasi</th>				
				<th class='th01' >Jumlah Barang</th>
				<th class='th01' width=''>Satuan</th>
				<th class='th01' width=''>Harga Satuan</th>
				<th class='th01' width=''>Jumlah Harga</th>
				<th class='th01' width='50'>Dipergunakan<br> Pada Unit</th>
				<th class='th01' width='100'>Keterangan </th>
			</tr>
			
	   </thead>";
	
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS;
		
		$Koloms = array();
		
		//cari total bi
		$totalbi = '';
		$status = $isi['stat'] == '1' ? 'DKB' : '';
		
		
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		$nmopdarr=array();		
		if($fmSKPD == '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_bidang where c='".$isi['c']."' "
			));		
			if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
		}
		if($fmUNIT == '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
			$get = mysql_fetch_array(mysql_query(
				"select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' "
			));		
			if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
		}
		if($fmSUBUNIT == '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_unit where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."'"
			));		
			if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
		}
		if($fmSEKSI == '00' || $fmSEKSI == '000'){
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
			));		
			if($get['nmseksi']<>'') $nmopdarr[] = $get['nmseksi'];
		}
		$get = mysql_fetch_array(mysql_query(
				"select ref_jurnal as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));	
		
		
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."/<br/>".
			$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'.'.$isi['kf']
		);
		$Koloms[] = array('', 
			$isi['nm_barang']."/<br/>".
			$isi['nm_account']//$barang['nm_barang']
		);			
		$Koloms[] = array("align=''",  $isi['merk_barang']  );
		$Koloms[] = array("align='right'", number_format( $isi['jml_barang'] ,0,',','.') );
		$Koloms[] = array('', $isi['satuan'] );
		$Koloms[] = array("align='right'", number_format( $isi['harga'] ,2,',','.' ));			
		$Koloms[] = array("align='right'", number_format( $isi['jml_harga'] ,2,',','.') );
		$Koloms[] = array(' width=150 ', $nmopd);
		$Koloms[] = array('', $isi['ket']);
		return $Koloms;
	}
	function setCekBox($cb, $KeyValueStr, $isi){
		return "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' stat='".$isi['stat']."'  onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
	}
	
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main,$HTTP_COOKIE_VARS;
			
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 
		$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQueryUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('fmSKPDskpd')!=$vAtas? cekPOST('fmSKPDskpd'): $HTTP_COOKIE_VARS['cofmUNIT'];
	 }else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
	 }
		
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e!='00' and e1='000' GROUP by e";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySubUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('c')!=$vAtas? cekPOST('c'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('d')!=$vAtas? cekPOST('d'): $HTTP_COOKIE_VARS['cofmUNIT'];
		$fmSKPDUnit = cekPOST('fmSKPDUnit_form')!=$vAtas? cekPOST('fmSKPDUnit_form'): $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	}else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
		$fmSKPDUnit=$xplode[2];
	 }
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e='$fmSKPDUnit' and e1!='000' GROUP by e1";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e1'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e1'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e1]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		$divHal = "<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
							"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
						"</div>";
		switch($this->daftarMode){						
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr valign=top>
					<td style='width:$this->containWidth;'>".
						"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
						$divHal.
					"</td>".
					"<td>".
						"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >"."</div>".
					$divHal;					
				break;
			}
		}
		
		return		
			
			$vdaftar.
			//"</div>".
			'';
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		$idsk=$_REQUEST['idsk'];
		$c=$_REQUEST['c'];
		$d=$_REQUEST['d'];
		$TampilOpt =
			"<input type='hidden' name='idsk' id='idsk' value='".$idsk."'>".
			"<input type='hidden' name='c' id='c' value='".$c."'>".
			"<input type='hidden' name='d' id='d' value='".$d."'>";
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('c');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('d');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$idsk=  cekPOST('idsk');
		$fmPILCARI = cekPOST('fmPILCARI');
		 
		$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);
		
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "YEAR(spk_tgl)='$fmThnAnggaran'";
		if(!($idsk=='') ) $arrKondisi[] = "ref_idsk='$idsk'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$fmORDER2 = cekPOST('fmORDER2');
		$fmDESC2 = cekPOST('fmDESC2');				
		$Asc2 = $fmDESC2 ==''? '': 'desc';		
		$fmORDER3 = cekPOST('fmORDER3');
		$fmDESC3 = cekPOST('fmDESC3');				
		$Asc3 = $fmDESC3 ==''? '': 'desc';		
		
		$arrOrders = array();
		
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = ' ';// Order By no_terima desc ';
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
	
	
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['c'];
		$dt['d'] = $_REQUEST['d'];
		$dt['ref_idsk'] = $_REQUEST['idsk'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$dt['ref_iddkb']=0;
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	
	function setFormEdit(){		
		global $Main;
		$cek = ''; $err=''; $content='';// $json=FALSE;
		$form = '';
		
		//$err = $_REQUEST['rkbSkpdfmSKPD'];
		$cbid = $_POST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$akun = mysql_fetch_array(mysql_query("select * from ref_jurnal where concat(ka,kb,kc,kd,ke,kf)='".$dt['k'].$dt['l'].$dt['m'].$dt['n'].$dt['o'].$dt['kf']."'")) ;
		$dt['nm_account']=$akun['nm_account'];
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']);
	}
	
	
	
	function setForm($dt){	
		global $fmIDBARANG,$fmIDREKENING,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';
	 $sw=$_REQUEST['sw'];
	 $sh=$_REQUEST['sh'];				
	 $this->form_width = $sw-50;
	 $this->form_height = $sh-100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';
	  }
	 	
		//items ----------------------
		$editunit= $dt['e'] != '' ? $dt['c'].".".$dt['d']:'';
		$editsubunit=$dt['e1'] != '' ? $dt['c'].".".$dt['d'].".".$dt['e']:'';
		$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'")) ;
	   	$kode_brg = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;			
		$kode_account = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].'.'.$dt['kf'];
		
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<div>
			<input type='text' name='cnt_jmlharga' id='cnt_jmlharga' value='".number_format($dt['jml_harga'],0,',','.')."' readonly=''>
			<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			</div>";
			
		if($dt['ref_iddkb']!='0' ){
			$pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);
			$pilihSubUnit=$this->cmbQuerySubUnit('fmSKPDSubUnit_form',$dt['e1'],'',' '.$disabled1,'--- Pilih Sub Unit ---','000',TRUE,$editsubunit) ;
		}else{
			$pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$dt['c'].".".$dt['d']);
			$pilihSubUnit=$this->cmbQuerySubUnit('fmSKPDSubUnit_form',$dt['e1'],'',' '.$disabled1,'--- Pilih Sub Unit ---','000',FALSE) ;}
		
		
		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		 $this->form_fields = array(	
		 	'unit_pengguna' => array('label'=>'', 
								 'value'=>'Unit Pengguna',  
								 'type'=>'merge' , 
								 'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
								 ),
								 					
			'unit' => array(  'label'=>'&nbsp&nbspUnit', 
								 'value'=> '<div id=Unit_formdiv>'.$pilihUnit.'</div>',   
								 'type'=>'' , 
								 ),
								 
			'subunit' => array(  'label'=>'&nbsp&nbspSub Unit', 
								 'value'=> '<div id=SubUnit_formdiv>'.$pilihSubUnit.'</div>',   
								 ),										 						
				   								 
			
            'nm_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_brg' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$brg['nm_barang']."' readonly=''>".
										 "<input type=hidden id='ref_iddkb' name='ref_iddkb' value='".$dt['ref_iddkb']."'>".
										 "&nbsp;<input type='button' value='Cari 1' onclick=\"".$this->Prefix.".caribarang1()\" title='Cari Kode Barang' >".
										 "&nbsp;<input type='button' value='Cari 2' onclick ='".$this->Prefix.".CariDKB()' title='Cari Perencanaan DKBMD' >"			 
									 ),
			'kode_account' => array( 
								'label'=>'Nama Akun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
										  <input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>
										  <input type=hidden id='tahun_account' name='tahun_account' value='".$dt['thn_akun']."'>
										  " 
									 ),
			'harga' => array( 
					'label'=>'Harga Satuan', 
					'value'=>inputFormatRibuan("harga", '',$dt['harga']) ,
				'type'=>'' 
			),
			'jml_barang' => array(  
						'label'=>'Jumlah Pengadaan Barang', 
						'value'=>"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' onkeypress='return isNumberKey(event)'".
						//onblur=''.$this->Prefix.'.hitungSisa()
						"' />", 
					'type'=>'' 
			),				
			'satuan' => array( 
					'label'=>'Satuan', 
					'value'=> "<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />" ,
					'type'=>'' 
			),				
			'jml_harga' => array( 
					'label'=>'Jumlah Harga', 
					'value'=> $vjmlharga ,
					'type'=>'' 
			),			
			'merk' => array(  
						'label'=>'Merk/Type/Ukuran/Spesifikasi', 
						'value'=> "<textarea name=\"fmMEREK\" id=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 
						//'params'=>"valign='top'",
						'type'=>'' , 
						'row_params'=>"valign='top'"
			),
			
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
		);
		
				
		//tombol
		$this->form_menubawah = 
			"<input type='hidden' name='fmtahun' id='fmtahun' size='4' value='".$dt['tahun']."' readonly=''>".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='ref_idsk' name='ref_idsk' value='".$dt['ref_idsk']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan2()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >"
			;
		
		//$this->genForm_nodialog();
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function simpan2(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$a1 = $Main->DEF_KEPEMILIKAN;
		$a = $Main->DEF_PROPINSI;
		$b = $Main->DEF_WILAYAH;
		
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['fmSKPDUnit_form'];
		$e1 = $_REQUEST['fmSKPDSubUnit_form'];
		$tahun = $_REQUEST['fmtahun'];
		
		
		$fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
		$f = $fmIDBARANG[0];
		$g = $fmIDBARANG[1];
		$h = $fmIDBARANG[2];
		$i = $fmIDBARANG[3];
		$j = $fmIDBARANG[4];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		$ref_iddkb = $_REQUEST['ref_iddkb'];
		
		$fmIDREKENING = explode('.',$_REQUEST['kode_account']);
		$k = $fmIDREKENING[0];
		$l = $fmIDREKENING[1];
		$m = $fmIDREKENING[2];
		$n = $fmIDREKENING[3];
		$o = $fmIDREKENING[4];
		$kf = $fmIDREKENING[5];
		$nama_account = $_REQUEST['nama_account'];
		$thn_akun = $_REQUEST['tahun_account'];
		
		$jml_barang = $_REQUEST['jml_barang'];
		$satuan = $_REQUEST['satuan'];
		$harga = $_REQUEST['harga'];		
		$jml_harga  = $harga * $jml_barang;
		//$jml_harga = str_replace('.','',$_REQUEST['jml_harga']);
		$merk_barang = $_REQUEST['fmMEREK'];
		$ket = $_REQUEST['fmKET'];
		$ref_idsk = $_REQUEST['ref_idsk'];

		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName_Edit where id='$id' "		
		));
		
		
		//-- validasi
		if($err=='' && $fmIDBARANG == '')$err = "Barang belum dipilih!";
		if($err=='' && $fmIDREKENING == '')$err = "Kode akun belum dipilih!";
		if($err=='' && ($jml_barang == '' || $jml_barang==0))$err = "Jumlah pengadaan barang belum diisi!";
		if($err=='' && ($harga == ''|| $harga==0))$err = "Harga satuan belum diisi!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
		if($err=='' && ($e == '' || $e == '00' ) )$err = "Dipergunakan Unit belum dipilih!";
		if($err=='' && ($e1 == '' || $e1 == '000' ) )$err = "Dipergunakan Sub Unit belum dipilih!";		
		
		if($ref_iddkb == '' || $ref_iddkb == '0'){
			
		}else{
			$get = mysql_fetch_array( mysql_query("select * from dkb where id='$ref_iddkb'"));			
			$thn_akun = $get['thn_akun'];
		}
		//if($err=='' && sudahClosing($dpa_tgl,$c,$d))$err = "Tanggal Sudah Closing!";
		
		if($err==''){
			if($fmST == 0){//baru
				
				$aqry = 
					"insert into $this->TblName_Edit ".
					"( a1,a,b,c,d,e,e1,".
					" f,g,h,i,j,ref_iddkb,".
					" k,l,m,n,o,kf,thn_akun,".
					" tahun, ref_idsk,".
					" nm_account,nm_barang,".
					" jml_barang,satuan,harga,jml_harga,merk_barang, ket,".
					" uid,tgl_update) ".
					"values ".
					"( '$a1','$a','$b','$c','$d','$e','$e1',".
					" '$f','$g','$h','$i','$j','$ref_iddkb',".
					" '$k','$l','$m','$n','$o','$kf','$thn_akun',".
					" '$tahun','$ref_idsk',".
					" '$nama_account','$fmNMBARANG',".
					" '$jml_barang','$satuan','$harga','$jml_harga','$merk_barang', '$ket',".
					" '$UID',now())";
				
			}else{
				
				//if($err=='' && sudahClosing($dpa_tgl,$c,$d))$err = "Tanggal Sudah Closing!";
		
				$aqry = 
					"update $this->TblName_Edit  ".
					"set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',e='$e',e1='$e1',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',ref_iddkb='$ref_iddkb',".
					" k='$k',l='$l',m='$m',n='$n',o='$o',kf='$kf',thn_akun='$thn_akun',".
					" tahun='$tahun', ref_idsk='$ref_idsk',".
					" nm_account='$nama_account', nm_barang='$fmNMBARANG',".
					" jml_barang='$jml_barang',satuan='$satuan',harga='$harga',jml_harga='$jml_harga',merk_barang='$merk_barang', ket='$ket',". 
					" uid='$UID', tgl_update= now() ".
					"where id='".$old['id']."' ";
				
			
				
			}
			if($err==''){
				$cek .= $aqry;
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal SQL'.mysql_error();	
				}
			
		}		
		//$err=$cek;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function Hapus_Validasi($id){
		global $Main, $HTTP_COOKIE_VARS;
		$err ='';
		//$KeyValue = explode(' ',$id);
		$get = mysql_fetch_array(mysql_query(
			"select count(*) as cnt from penerimaan where id_pengadaan ='$id' "
		));
		if($err=='' && $get['cnt']>0 ) $err = 'Data Tidak Bisa Dihapus, Sudah ada di Penerimaan!';
		
			$kueri="select * from $this->TblName_Hapus 
					where id = '".$id."' "; //echo "$kueri";
			$data=mysql_fetch_array(mysql_query($kueri));
		//if($err=='' && sudahClosing($data['dpa_tgl'],$data['c'],$data['d']))$err = "Tanggal Sudah Closing!";
		
		return $err;
	}
	
	
	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru2':{				
				//echo 'tes';
				$get=$this->setFormBaru();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];			
				break;
			}
			case 'formEdit2':{								
				$get=$this->setFormEdit();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];				
				break;
			}
			case 'simpan2' : {
				$get = $this->simpan2();
				$cek = $get['cek']; 
				$err = $get['err']; 
				$content=$get['content']; 
				break;
			}
			
			case 'getJmlBrgBI':{
				$get = $this->getJmlBrgBI();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'formDKB':{
				$get = $this->setFormDKB();
				$json = FALSE;
				break;
			}
			case 'simpanDKB' : {
				$get = $this->simpanDKB();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'getsat': {
				$get = $this->getStat();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}	
			case 'hitPengadaanSebelumnya':{
				$get = $this->hitPengadaanSebelumnya();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 				
				break;
			}
			case 'BidangAfter':{
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter()','--- Pilih SKPD ---','00');
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			break;
		    }
			case 'SKPDAfter':{
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
				setcookie('cofmSKPD',$fmSKPDBidang);
				setcookie('cofmUNIT',$fmSKPDskpd);
				setcookie('cofmSUBUNIT','00');
				setcookie('cofmSUBUNIT','000');
			break;
		    }
			case 'UnitRefresh':{
				$fmc = cekPOST('c');
				$fmd = cekPOST('d');
				$fme = cekPOST('fmSKPDUnit_form');
				$ref_iddkb = cekPOST('ref_iddkb');
				
				$editunit= $fmc != '' ? $fmc.".".$fmd:'';
				if($ref_iddkb != ''){
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);	
				}else{
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
				}
				
			break;
		    }
			case 'UnitAfter':{
			//$fmSKPDUnit = cekPOST('fmSKPDUnit_form');
			//setcookie('cofmSUBUNIT',$fmSKPDUnit);
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			if($ref_iddkb != 0){
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',TRUE);	
				}else{
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',FALSE);
				}
			
			break;
		    }	
			/*case 'hitungSisa': {
				$get = $this->hitungSisa();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}*/			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$dpb_spk_det = new dpb_spk_detObj();

?>