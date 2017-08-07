<?php
class tgr_ketetapanObj extends DaftarObj2{
	var $Prefix = 'tgr_ketetapan';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'gantirugi';//'v1_rkb'
	var $TblName_Hapus = 'gantirugi';
	var $TblName_Edit = 'gantirugi';
	var $KeyFields = array('id');
	var $FieldSum = array('harga_gantirugi');
	var $fieldSum_lokasi = array( 11);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 10, 9, 9);
	var $FieldSum_Cp2 = array( 12, 1, 1);	
	var $totalCol = 12; //total kolom daftar
	var $FormName = 'TGRKetetapanForm';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun	
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $ico_width = '28.8';
	var $ico_height = '28.8';	
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='tgr_ketetapan.xls';
	var $Cetak_Judul = 'KETETAPAN TGR';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	var $arr_CrPengganti = array(
				array('0','Uang Pengganti'),
				array('1','Barang Pengganti'),
				);
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/gantirugi/tgr.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/gantirugi/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".
			$scriptload;
	}
	function setTitle(){
		return 'KETETAPAN TUNTUTAN GANTI RUGI';
	}
	function setNavAtas(){
			return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=tgr" title="Tuntutan Ganti">TGR</a> |	
				<a style="color:blue;" href="pages.php?Pg=tgr_ketetapan" title="Berita Acara Serah Terima">Ketetapan</a> 
				<!--<a href="pages.php?Pg=pembayaran" title="Berita Acara Serah Terima">Pembayaran</a>  |	-->
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setMenuEdit(){		
		return
			//"";
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'Ketetapan Ganti Rugi Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit Ketetapan Ganti Rugi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus Ketetapan Ganti Rugi')."</td>";
			
			
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
					<th class='th01' width='40' rowspan=2 width='40'>No.</th>
					$Checkbox		
					<th class='th01' rowspan=2 width='200'>SKPD/UNIT<br/> Pengguna Barang</th>
					<th class='th01' rowspan=2 width=''>Kode Barang/<br/>Kode Akun</th>
					<th class='th01' rowspan=2 width=''>Nama Barang/<br/>Nama Akun</th>		
					<th class='th01' rowspan=2>No Reg</th>
					<th class='th01' rowspan=2>Spesifikasi/<br/>Alamat</th>
					<th class='th02' colspan=2>Ketetapan TGR</th>
					<th class='th01' rowspan=2 width=''>Jenis Pengganti</th>
					<th class='th01' rowspan=2 width='100'>Harga Pengganti/<br/>Kode Barang</th>
					<th class='th01' rowspan=2 width='100'>Tgl Buku/<br/>Ket</th>
				</tr>
				<tr>
					<th class='th01' width='80'>Nomor </th>				
					<th class='th01' width='60'>Tanggal </th>
				</tr>
				
			</thead>";
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS;
		$isi = array_map('utf8_encode', $isi);
	 $bi_ganti = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['idbi_ganti']."'")) ;
	 $brg = mysql_fetch_array(mysql_query(
				"select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));	
	 $akn = mysql_fetch_array(mysql_query(
				"select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' and kf='".$isi['kf']."'"));	
	 $akn = array_map('utf8_encode', $akn);
	
	 $spesifikasi=$this->getSpesifikasi($isi['id_bukuinduk']);
	 			
		$nmopdarr=array();		
		if($isi['c'] != '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_bidang where c='".$isi['c']."' "
			));		
			if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
		}
		if($isi['d'] != '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
			$get = mysql_fetch_array(mysql_query(
				"select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' "
			));		
			if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
		}
		if($isi['e'] != '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_unit where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."'"
			));		
			if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
		}
		if($isi['e1'] != '00' || $fmSEKSI == '000'){
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
			));		
			if($get['nmseksi']<>'') $nmopdarr[] = $get['nmseksi'];
		}
		$kode_barang_ganti=$bi_ganti['f']==""?"":$bi_ganti['f'].'.'.$bi_ganti['g'].'.'.$bi_ganti['h'].'.'.$bi_ganti['i'].'.'.$bi_ganti['j'];
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array("align='left'",  $nmopd);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."/<br/>".
			$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'].'.'.$isi['kf']
		);
		$Koloms[] = array('', 
			$brg['nm_barang']."/<br/>".
			$isi['nm_account']//$barang['nm_barang']
		);
		$Koloms[] = array("align='center'",  $isi['noreg']."/<br/>".$isi['tahun'] );
		$Koloms[] = array("align='left'", $spesifikasi	);		
		$Koloms[] = array("align=''",  $isi['no_sk']  );
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_sk'] ) );		
		$Koloms[] = array("align=''",  $this->arr_CrPengganti[$isi['jns_ganti']][1]);
		$Koloms[] = array("align='right'", number_format( $isi['harga_gantirugi'] ,2,',','.')."/</br>"
					.$kode_barang_ganti );
		$Koloms[] = array('', TglInd($isi['tgl_buku_sk'])."/</br>".$isi['ket']);
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
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				"<input type='hidden' value='$fmSKPDBidang' id='fmSKPDBidang' name='fmSKPDBidang' >".
				"<input type='hidden' value='$fmSKPDskpd' id='fmSKPDskpd' name='fmSKPDskpd' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		
		//$fmSKPDBidang=cekPOST('fmSKPDBidang');
		// $fmSKPDskpd=cekPOST('fmSKPDskpd');
		 $fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		 $fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranDari');
		 $fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranSampai');
		
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmPILCARI = cekPOST('fmPILCARI'); //get name select box 
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE'); //get value textfield
		
		//data cari ----------------------------
		$arrCari = array(
			array('selectKodeBarang','Kode Barang'),
			array('selectNamaBarang','Nama Barang'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
		
		$arrSemester = array(
			array('1','1'),
			array('2','2'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		 
		
		$TampilOpt =
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			"<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
				$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange='.$this->Prefix.'.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
			"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
				$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
			"</td></tr>".
			"<tr><td width='100'>Tahun Anggaran</td><td width='10'>:</td><td>
				<input type='text'  size='4' value='$fmThnAnggaranDari' id='fmThnAnggaranDari' name='fmThnAnggaranDari' > s/d <input type='text'  size='4' value='$fmThnAnggaranSampai' id='fmThnAnggaranSampai' name='fmThnAnggaranSampai' >
			</td></tr>			
			</table>".
			"</td>
			</tr></table>".
				genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
				genFilterBar(
				array(	
					"<table><tr><td width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILSEMESTER',$fmPILSEMESTER,$arrSemester,'Semua','').
					"</td></tr>			
					</table>"					
				),$this->Prefix.".refreshList(true)",TRUE
			);
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari');
		$fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai');
		$fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIVALUE = $_REQUEST['fmPILCARIVALUE'];	
		 
		$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT
		);
		
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "tahun='$fmThnAnggaran'";
		
		if ($fmThnAnggaranDari == $fmThnAnggaranSampai){
		
			if(!($fmThnAnggaranDari=='')  && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "tahun>='$fmThnAnggaranDari' and tahun<='$fmThnAnggaranSampai' ";
			switch($fmPILSEMESTER){			
			case '1': $arrKondisi[] = " tgl_gantirugi>='".$fmThnAnggaranDari."-01-01' and  cast(tgl_gantirugi as DATE)<='".$fmThnAnggaranSampai."-06-30' "; break;
			case '2': $arrKondisi[] = " tgl_gantirugi>='".$fmThnAnggaranDari."-07-01' and  cast(tgl_gantirugi as DATE)<='".$fmThnAnggaranSampai."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThnAnggaranDari=='') && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "tahun>='$fmThnAnggaranDari' and tahun<='$fmThnAnggaranSampai' ";
		}
				
		switch($fmPILCARI){
				case 'selectKodeBarang': $arrKondisi[] = "concat(f,g,h,i,j) like '".str_replace(".","",$fmPILCARIVALUE)."%'"; break;		 	
				case 'selectNamaBarang': $arrKondisi[] = "concat(f,g,h,i,j) in (select concat(f,g,h,i,j) from ref_barang where nm_barang like '%$fmPILCARIVALUE%') "; break;
			}
				
		$kode_rekening  = cekPOST('kode_rekening');
		if(!empty($kode_rekening) ) $arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) like '%$kode_rekening%'";
		$arrKondisi[] = " no_sk <> ''";
		
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}
		
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
	
	
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$this->form_fmST = 0;
		$dt['tgl_sk'] = date("Y-m-d");
		$dt['tgl_buku_sk'] = date("Y-m-d");
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
		
		$akun = mysql_fetch_array(mysql_query("select * from ref_jurnal where concat(ka,kb,kc,kd,ke,kf)='".$dt['ka'].$dt['kb'].$dt['kc'].$dt['kd'].$dt['ke'].$dt['kf']."'")) ;
		$akun = array_map('utf8_encode', $akun);
		$dt['nm_account']=$akun['nm_account'];
		$dt['spek_alamat']=$this->getSpesifikasi($dt['id_bukuinduk']);
		
		
		//$dt = array_merge($dt,$kib);
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']);
	}
	
	
	
	function setForm($dt){	
		global $fmIDBARANG,$fmNMBARANG,$fmIDREKENING,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';
	 $sw=$_REQUEST['sw'];
	 $sh=$_REQUEST['sh'];				
	 $this->form_width = $sw-50;
	 $this->form_height = $sh-100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';		
		$jenis_pengganti_brg_visible = "style='display:none'"; 
	  }else{
		$this->form_caption = 'EDIT';		
		$jenis_pengganti_brg_visible = $dt['jns_ganti']==0?"style='display:none'":"style='display:block'"; 
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$dt['idbi_ganti']."'")) ;
		switch($bi['f']){
			case '01':$tblkib="kib_a";break;
			case '02':$tblkib="kib_b";break;
			case '03':$tblkib="kib_c";break;
			case '04':$tblkib="kib_d";break;
			case '05':$tblkib="kib_e";break;
			case '06':$tblkib="kib_f";break;
			case '07':$tblkib="kib_g";break;
		}
	    $kode_account2 = $dt['ka_ganti']==''? '' : $dt['ka_ganti'].'.'.$dt['kb_ganti'].'.'.$dt['kc_ganti'].'.'.$dt['kd_ganti'].'.'.$dt['ke_ganti'].'.'.$dt['kf_ganti'];
		
	    $akun = mysql_fetch_array(mysql_query("select * from ref_jurnal where concat(ka,kb,kc,kd,ke,kf)='".$dt['ka_ganti'].$dt['kb_ganti'].$dt['kc_ganti'].$dt['kd_ganti'].$dt['ke_ganti'].$dt['kf_ganti']."'"));
	   $akun = array_map('utf8_encode', $akun);
	   $dt['nm_account_ganti']=$akun['nm_account'];
	  
	   $brg_ganti = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'")) ;
	   $fmIDBARANG = $brg_ganti['f']==''? '':  $brg_ganti['f'].'.'.$brg_ganti['g'].'.'.$brg_ganti['h'].'.'.$brg_ganti['i'].'.'.$brg_ganti['j'] ;
		$fmNMBARANG = $brg_ganti['nm_barang'];
		$kib = mysql_fetch_array(mysql_query("select * from ".$tblkib." where idbi='".$dt['idbi_ganti']."'")) ;
		$kib = array_map('utf8_encode', $kib);
		
	  }
	 	
		//items ----------------------
		$editunit= $dt['e'] != '' ? $dt['c'].".".$dt['d']:'';
		$editsubunit=$dt['e1'] != '' ? $dt['c'].".".$dt['d'].".".$dt['e']:'';
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		
	   	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'")) ;
	   	$kode_brg = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;			
		$kode_account = $dt['ka']==''? '' : $dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'].'.'.$dt['kf'];
				
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<div>
			<input type='text' name='cnt_jmlharga' id='cnt_jmlharga' value='".number_format($dt['jml_harga'],0,',','.')."' readonly=''>
			<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			</div>";
			
			$pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange=tgr_ketetapan.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
			$pilihSubUnit=$this->cmbQuerySubUnit('fmSKPDSubUnit_form',$dt['e1'],'',' '.$disabled1,'--- Pilih Sub Unit ---','000',FALSE,$editsubunit) ;
		//}
		
		//FORM DETAIL JENIS PENGGANTI
		//===========================
		
		switch ( $dt['jns_ganti']){
			case '0': $jenis_pengganti_visible = "style='display:block'";
					  $jenis_pengganti_brg_visible = "style='display:none'"; break;
			case '1': $jenis_pengganti_brg_visible = "style='display:block'";
					  $jenis_pengganti_visible = "style='display:none'"; break;
			default : $jenis_pengganti_brg_visible = "style='display:none'";
					  $jenis_pengganti_visible = "style='display:none'"; break;
			
		}
		$this->form_fields = array(	
			'hargaP' => array( 
					'label'=>'Harga Pengganti',
					'labelWidth'=>147,  
					'value'=>inputFormatRibuan("hrg_pengganti", "",$dt['harga_gantirugi']) ,
					'type'=>'' 
			),
			
		);
		$jenis_pengganti_uangdet = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(	
			'BP' => array(
							'label'=>'', 
							'value'=>'Barang Pengganti', 
							'type'=>'merge',
							'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'nm_barang2' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$fmIDBARANG' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$fmNMBARANG."' readonly=''>".
										 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
									 ),
			'kode_account2' => array( 
								'label'=>'Kode Akun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account2' value='$kode_account2' size='15px' id='kode_account2' readonly>
										  <input type='text' name='nama_account2' value='".$dt['nm_account_ganti']."' size='60px' id='nama_account2' readonly>
										  <input type=hidden id='tahun_account2' name='tahun_account2' value='".$dt['thn_akun_ganti']."'>
										  " 
									 ),
			'thn_perolehan2' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='thn_perolehan2' id='thn_perolehan2' size='4' value='".$bi['tahun']."' onkeypress='return isNumberKey(event)' >"
									 ),
			'no_reg2' => array( 
								'label'=>'No. Reg/Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='no_reg2' id='no_reg2' size='4' value='".$bi['noreg']."' >&nbsp".
								"&nbsp<input type='button' value='Cari No. Akhir' onclick='".$this->Prefix.".getNoRegAkhir()' title='Cari No. Register Terakhir'>&nbsp".
								"<span style='color:red;'> (Ket: Sebelum mencari No. Register Akhir, Isi Nama Barang dan Tahun Perolehan )</span>"
									 ),
			'hrg_perolehan2' => array( 
					'label'=>'Harga Perolehan', 
					'value'=>inputFormatRibuan("hrg_perolehan2", '',$bi['jml_harga']) ,
				'type'=>'' 
			),
			'no_BAST' => array(
							'label'=>'Nomor BAST', 
							'value'=>$bi['no_ba'], 
							'type'=>'text'
			),
			'tgl_BAST' => array(
							'label'=>'&nbsp;&nbsp;Tanggal BAST', 
							'value'=> createEntryTgl('tgl_BAST',$bi['tgl_ba'] ), 
							'type'=>''
			),
			'detbrgkib' => array( 
					'label'=>'', 
					'value'=>'<div id=DetailKIB>'.$this->getFormEntryKIB($bi['f'],$kib).'</div>',//Penatausahaan_FormEntry_Kib($dt['f']) ,
				'type'=>'merge' 
			),
			
		);
		$jenis_pengganti_brgdet = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		 $this->form_fields = array(									 
			'bidang' => array( 'label'=>'BIDANG', 
								'labelWidth'=>150,
								'value'=>$bidang, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'skpd' => array( 'label'=>'SKPD', 
								'value'=>$unit, 
								'type'=>'', 'row_params'=>"height='21'"
							),			
            'thn_anggaran' => array( 
								'label'=>'Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmtahun' id='fmtahun' size='4' value='".$dt['tahun']."' readonly=''>"
									 ),
			'unit_pengguna' => array(
							'label'=>'', 
							'value'=>'Unit Pengguna', 
							'type'=>'merge',
							'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'unit' => array( 
					'label'=>'&nbsp;&nbsp;Unit', 
					'value'=> '<div id=Unit_formdiv>'.$pilihUnit.'</div>' ,
					'type'=>'',
					'row_params'=>"height='21'" 
			),
			'subunit' => array( 
					'label'=>'&nbsp;&nbsp;Sub Unit', 
					'value'=> '<div id=SubUnit_formdiv>'.$pilihSubUnit.'</div>',
					'type'=>'',
					'row_params'=>"height='21'" 
			),
			
			'nm_barang' => array( 
								'label'=>'Kode Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG2' id='fmIDBARANG2' size='15' value='$kode_brg' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG2' id='fmNMBARANG2' size='60' value='".$brg['nm_barang']."' readonly=''>".
										 "<input type=hidden id='idtgr' name='idtgr' value='".$dt['id']."'>".
										 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".CariGantiRugi()\" title='Cari Kode Barang' >",
								),
			'kode_account' => array( 
								'label'=>'Kode Akun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
										  <input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>
										  <input type=hidden id='tahun_account' name='tahun_account' value='".$dt['thn_akun']."'>
										  " 
									 ),
			'no_reg' => array( 
								'label'=>'No. Reg/Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='no_reg' id='no_reg' size='15' value='".$dt['noreg']."' readonly >&nbsp/&nbsp<input type='text' name='thn_perolehan' id='thn_perolehan' size='4' value='".$dt['thn_perolehan']."'  readonly >"
									 ),
			'spek_alamat' => array(  
						'label'=>'Spesifikasi/Alamat', 
						'value'=>"<textarea name=\"spek_alamat\" id=\"spek_alamat\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' readonly >".$dt['spek_alamat']."</textarea>", 
						//'params'=>"valign='top'",
						'type'=>'' , 
						'row_params'=>"valign='top'"
			),
			'hargaPB' => array(  
						'label'=>'Harga Perolehan/Harga Buku', 
						'value'=>"<input name=\"hrg_perolehan\" id='hrg_perolehan' type=\"text\" value='".number_format($dt['harga_perolehan'],2,',','.' )."' onkeypress='return isNumberKey(event)' readonly /> ".
								"<input name=\"hrg_buku\" id='hrg_buku' type=\"text\" value='".number_format($dt['harga_buku'],2,',','.' )."' onkeypress='return isNumberKey(event)' readonly />", 
					'type'=>'' 
			),	
			'ket_tgr' => array(
							'label'=>'', 
							'value'=>'Ketetapan TGR', 
							'type'=>'merge',
							'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'no_tgr' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['no_sk'], 
							'type'=>'text'
			),
			'tgl_tgr' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl('tgl_tgr',$dt['tgl_sk'] ), 
							'type'=>''
			),
			'tgl_buku' => array(
							'label'=>'Tanggal Buku', 
							'value'=> createEntryTgl('tgl_buku',$dt['tgl_buku_sk'] ), 
							'type'=>''
			),			
			'cr_pengganti' => array(
							'label'=>'Jenis Pengganti', 
							'value'=>cmbArray('cr_pengganti',$dt['jns_ganti'],$this->arr_CrPengganti,'--- PILIH ---','onchange='.$this->Prefix.'.formSetDetailJenisEntry()'), 
							'type'=>'',
			),
			'det_jenis_pengganti_uang' => array(
				'label'=> '',
				'labelWidth'=>150,
				'value'=> "<div id='det_jenis_pengganti_uang' name='det_jenis_pengganti_uang' $jenis_pengganti_visible>".$jenis_pengganti_uangdet."</div>",
				'type'=>'merge'
			),
			'det_jenis_pengganti_brg' => array(
				'label'=> '',
				'labelWidth'=>150,
				'value'=> "<div id='det_jenis_pengganti_brg' name='det_jenis_pengganti_brg' $jenis_pengganti_brg_visible>".$jenis_pengganti_brgdet."</div>",
				'type'=>'merge'
			),
		);
		
				
		//tombol
		$this->form_menubawah = 
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='idbi' name='idbi' value='".$dt['id_bukuinduk']."'> ".
			"<input type=hidden id='idbi_ganti' name='idbi_ganti' value='".$dt['idbi_ganti']."'> ".	
			"<input type=hidden id='idbi_awal' name='idbi_awal' value='".$dt['idbi_awal']."'> ".	
			"<input type=hidden id='idkib' name='idkib' value='".$kib['id']."'> ".
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
		
		//Barang Pengganti
		$fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
		$f_baru = $fmIDBARANG[0];
		$g_baru = $fmIDBARANG[1];
		$h_baru = $fmIDBARANG[2];
		$i_baru = $fmIDBARANG[3];
		$j_baru = $fmIDBARANG[4];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		//Akun Barang Ganti
		$fmIDREKENING = explode('.',$_REQUEST['kode_account2']);
		$ka_baru = $fmIDREKENING[0];
		$kb_baru = $fmIDREKENING[1];
		$kc_baru = $fmIDREKENING[2];
		$kd_baru = $fmIDREKENING[3];
		$ke_baru = $fmIDREKENING[4];
		$kf_baru = $fmIDREKENING[5];
		$nama_account_baru = $_REQUEST['nama_account2'];
		$thn_akun_baru = $_REQUEST['tahun_account2'];
		
		//Barang TGR
		$fmIDBARANG2 = explode('.',$_REQUEST['fmIDBARANG2']);
		$f = $fmIDBARANG2[0];
		$g = $fmIDBARANG2[1];
		$h = $fmIDBARANG2[2];
		$i = $fmIDBARANG2[3];
		$j = $fmIDBARANG2[4];
		$fmNMBARANG2 = $_REQUEST['fmNMBARANG2'];
		$idtgr = $_REQUEST['idtgr'];
		//Akun TGR
		$fmIDREKENING2 = explode('.',$_REQUEST['kode_account']);
		$ka = $fmIDREKENING[0];
		$kb = $fmIDREKENING[1];
		$kc = $fmIDREKENING[2];
		$kd = $fmIDREKENING[3];
		$ke = $fmIDREKENING[4];
		$kf = $fmIDREKENING[5];
		$nama_account = $_REQUEST['nama_account'];
		$thn_akun = $_REQUEST['tahun_account'];
		
		$no_reg	= $_REQUEST['no_reg'];
		$thn_perolehan = $_REQUEST['thn_perolehan'];
		$spek_alamat = $_REQUEST['spek_alamat'];
		$hrg_perolehan = $_REQUEST['hrg_perolehan'];
		$hrg_buku = $_REQUEST['hrg_buku'];
		$no_ketetapan = $_REQUEST['no_tgr'];//ketetapan
		$tgl_ketetapan = $_REQUEST['tgl_tgr'];//ketetapan
		$tgl_buku = $_REQUEST['tgl_buku'];
		$cr_pengganti = $_REQUEST['cr_pengganti'];
		$hrg_pengganti = $_REQUEST['hrg_pengganti'];
		$no_reg2 = $_REQUEST['no_reg2'];
		$thn_perolehan2 = $_REQUEST['thn_perolehan2'];
		$hrg_perolehan2 = $_REQUEST['hrg_perolehan2'];
		$no_BAST = $_REQUEST['no_BAST'];
		$tgl_BAST = $_REQUEST['tgl_BAST'];
		$idbi = $_REQUEST['idbi'];
		$idbi_ganti = $_REQUEST['idbi_ganti'];
		$idbi_awal= $_REQUEST['idbi_awal'];
		$fmST_bi=$fmST;//buat status edit bi
		$fmST_kib=$fmST;//buat status edit kib

		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName where id='$id' "		
		));
		
		$old_bi_ganti = mysql_fetch_array( mysql_query(
			"select * from buku_induk where id='$idbi_ganti' "		
		));
		
		switch($old_bi_ganti['f']){
			case '01':$tblkib="kib_a";break;
			case '02':$tblkib="kib_b";break;
			case '03':$tblkib="kib_c";break;
			case '04':$tblkib="kib_d";break;
			case '05':$tblkib="kib_e";break;
			case '06':$tblkib="kib_f";break;
			case '07':$tblkib="kib_g";break;
		}
		
		//validasi
		if($err=='' && sudahClosing($tgl_buku,$c,$d))$err = "Tanggal Sudah Closing!";
		if($err=='' && $cr_pengganti=='')$err = "Jenis Pengganti Belum Dipilih!";
		if($err=='' && $no_ketetapan=='')$err = "No Ketetapan Belum Diisi!";
		
		//edit dan ganti cara pengganti
		if($err=='' && ($fmST == 1 && $old['jns_ganti']!=$cr_pengganti)){
			$cek.=" --- edit dan ganti cara pengganti --- ";
			if($cr_pengganti=='1'){//Edit ganti uang jadi ganti barang
				$fmST_bi=0;
				$fmST_kib=0;
				
			}else{//Edit ganti barang jadi ganti uang
				$aqry = "DELETE FROM buku_induk WHERE id='".$idbi_ganti."'";	$cek .= " --- Hapus BI ".$idbi_ganti." dan ganti cara pengganti uang jadi barang --- ".$aqry;	
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal Hapus '.mysql_error();
				
				$idbi_ganti='';
				$ka_baru='';$kb_baru='';$kc_baru='';
				$kd_baru='';$ke_baru='';$kf_baru='';
				$thn_akun_baru='';
			}
		}
		
	if($cr_pengganti=='1'){
		$hrg_pengganti="";
		if($err=='' && $no_reg2 == '')$err = "Noreg Barang Pengganti belum diisi!";
		if($err=='' && $thn_perolehan2 == '')$err = "Tahun barang pengganti belum diisi!";
		if($err=='' && $hrg_perolehan2 == '')$err = "Harga barang pengganti belum diisi!";
		//Insert BI
		if($err==''){
			$staset=$f_baru=='07'?'8':'3';
			if($fmST_bi == 0){//baru
				$query="insert into buku_induk ".
						"( a1,a,b,c,d,e,e1,".
						" f,g,h,i,j,noreg,thn_perolehan,".
						" jml_barang,satuan,no_ba,tgl_ba,".
						" harga,jml_harga,".
						" kondisi,tahun,".
						" tgl_buku,staset,".
						" status_barang,asal_usul,".
						" uid,tgl_update) ".
						"values ".
						"( '$a1','$a','$b','$c','$d','$e','$e1',".
						" '$f_baru','$g_baru','$h_baru','$i_baru','$j_baru','$no_reg2','$thn_perolehan2',".
						" '1','','$no_BAST','$tgl_BAST',".
						" '$hrg_perolehan2','$hrg_perolehan2',".
						" '1','$thn_perolehan2',".
						" '$tgl_buku','$staset',".
						" '1','11',".
						" '$UID',now())"; $cek.=" --- Insert BI Baru --- ".$query;	
				$result=mysql_query($query);
				if($result == FALSE) $err='Gagal SQL Insert BI '.mysql_error();					
				$idbi_baru =mysql_insert_id();
				$idbi_ganti=	$idbi_baru;			
			}else{
				$query =" update buku_induk  ".
						" set ".
						" a1='$a1',a='$a',b='$b',c='$c',d='$d',e='$e',e1='$e1',".
						" f='$f',g='$g',h='$h',i='$i',j='$j',noreg='$no_reg2',thn_perolehan='$thn_perolehan2',".
						" jml_barang='1',satuan='',no_ba='$no_BAST',tgl_ba='$tgl_BAST',".
						" harga='$hrg_perolehan2',jml_harga='$hrg_perolehan2',".
						" kondisi='1',tahun='$thn_perolehan2',".
						" tgl_buku='$tgl_buku',staset='$staset',".
						" status_barang='1',asal_usul='11',".
						" uid='$UID', tgl_update= now() ".
						"where id='".$idbi_ganti."' "; $cek.=" --- Update BI $idbi_ganti --- ".$query;
				$new_bi_ganti = mysql_fetch_array( mysql_query(
							"select * from buku_induk where id='$idbi_ganti' "		
						));
				$id_kib_ganti = mysql_fetch_array( mysql_query(
							"select * from ".$tblkib." where idbi='$idbi_ganti' "		
						));
				$result=mysql_query($query);
				if($result == FALSE) $err='Gagal SQL Update BI '.mysql_error();					
				$idbi_baru =$idbi_ganti;				
				$idkib =$id_kib_ganti['id'];
			}
			
		}
		
		
		//Insert KIB
		if($err==''){
		 if($fmST==1 && $old_bi_ganti['f']!=$new_bi_ganti['f'] && $old['jns_ganti']==1){
			 	$cek.=" --- Edit Ganti Kib --- ";
		 		
				$aqryh = "DELETE FROM ".$tblkib." WHERE idbi='".$old_bi_ganti['id']."'";	$cek .= " --- Hapus Kib Lama --- ".$aqry;	
				$qryh = mysql_query($aqryh);
				if($qryh == FALSE) $err='Gagal Hapus '.mysql_error();
				$idkib="";
			$fmST_kib=0;
		 }
		 if($err==''){
		 	switch($f_baru){
				case '01': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_a',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'noreg'=>"'".$_REQUEST['no_reg2']."'",							
							'luas'=>"'".$_REQUEST['fmLUAS_KIB_A']."'",
							'alamat'=>"'".$_REQUEST['fmLETAK_KIB_A']."'",
							'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
							'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
							'alamat_a'=>"'".$a."'",
							'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
							'status_hak'=>"'".$_REQUEST['fmHAKPAKAI_KIB_A']."'",
							'bersertifikat'=>"'".$_REQUEST['bersertifikat']."'",
							'sertifikat_tgl'=>"'".$_REQUEST['fmTGLSERTIFIKAT_KIB_A']."'",
							'sertifikat_no'=>"'".$_REQUEST['fmNOSERTIFIKAT_KIB_A']."'",
							'penggunaan'=>"'".$_REQUEST['fmPENGGUNAAN_KIB_A']."'",
							//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_A']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '02': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_b',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'noreg'=>"'".$_REQUEST['no_reg2']."'",								
							'merk'=>"'".$_REQUEST['fmMERK_KIB_B']."'",
							'ukuran'=>"'".$_REQUEST['fmUKURAN_KIB_B']."'",
							'bahan'=>"'".$_REQUEST['fmBAHAN_KIB_B']."'",
							'no_pabrik'=>"'".$_REQUEST['fmPABRIK_KIB_B']."'",
							'no_rangka'=>"'".$_REQUEST['fmRANGKA_KIB_B']."'",
							'no_mesin'=>"'".$_REQUEST['fmMESIN_KIB_B']."'",
							'no_polisi'=>"'".$_REQUEST['fmPOLISI_KIB_B']."'",
							'no_bpkb'=>"'".$_REQUEST['fmBPKB_KIB_B']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_B']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '03': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_c',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'noreg'=>"'".$_REQUEST['no_reg2']."'",								
							'kondisi_bangunan'=>"'".$_REQUEST['fmKONDISI_KIB_C']."'",							
							'konstruksi_tingkat'=>"'".$_REQUEST['fmTINGKAT_KIB_C']."'",							
							'konstruksi_beton'=>"'".$_REQUEST['fmBETON_KIB_C']."'",							
							'luas_lantai'=>"'".$_REQUEST['fmLUASLANTAI_KIB_C']."'",						
							'alamat'=>"'".$_REQUEST['fmLETAK_KIB_C']."'",
							'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
							'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
							'alamat_a'=>"'".$a."'",
							'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
							'dokumen_tgl'=>"'".$_REQUEST['fmTGLGUDANG_KIB_C']."'",
							'dokumen_no'=>"'".$_REQUEST['fmNOGUDANG_KIB_C']."'",
							'luas'=>"'".$_REQUEST['fmLUAS_KIB_C']."'",
							'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_C']."'",
							//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_C']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '04': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_d',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'noreg'=>"'".$_REQUEST['no_reg2']."'",								
							'konstruksi'=>"'".$_REQUEST['fmKONSTRUKSI_KIB_D']."'",							
							'panjang'=>"'".$_REQUEST['fmPANJANG_KIB_D']."'",							
							'lebar'=>"'".$_REQUEST['fmLEBAR_KIB_D']."'",							
							'luas'=>"'".$_REQUEST['fmLUAS_KIB_D']."'",						
							'alamat'=>"'".$_REQUEST['fmALAMAT_KIB_D']."'",
							'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
							'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
							'alamat_a'=>"'".$a."'",
							'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
							'dokumen_tgl'=>"'".$_REQUEST['fmTGLDOKUMEN_KIB_D']."'",
							'dokumen_no'=>"'".$_REQUEST['fmNODOKUMEN_KIB_D']."'",
							'status_tanah'=>"'".$_REQUEST['fmSTATUSTANAH_KIB_D']."'",
							'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_D']."'",
							//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_D']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '05': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_e',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'noreg'=>"'".$_REQUEST['no_reg2']."'",								
							'buku_judul'=>"'".$_REQUEST['fmJUDULBUKU_KIB_E']."'",
							'buku_spesifikasi'=>"'".$_REQUEST['fmSPEKBUKU_KIB_E']."'",
							'seni_asal_daerah'=>"'".$_REQUEST['fmSENIBUDAYA_KIB_E']."'",
							'seni_pencipta'=>"'".$_REQUEST['fmSENIPENCIPTA_KIB_E']."'",
							'seni_bahan'=>"'".$_REQUEST['fmSENIBAHAN_KIB_E']."'",
							'hewan_jenis'=>"'".$_REQUEST['fmJENISHEWAN_KIB_E']."'",
							'hewan_ukuran'=>"'".$_REQUEST['fmUKURANHEWAN_KIB_E']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_E']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '06': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_f',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'bangunan'=>"'".$_REQUEST['fmBANGUNAN_KIB_F']."'",							
							'konstruksi_tingkat'=>"'".$_REQUEST['fmTINGKAT_KIB_F']."'",							
							'konstruksi_beton'=>"'".$_REQUEST['fmBETON_KIB_F']."'",							
							'luas'=>"'".$_REQUEST['fmLUAS_KIB_F']."'",						
							'alamat'=>"'".$_REQUEST['fmLETAK_KIB_F']."'",
							'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
							'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
							'alamat_a'=>"'".$a."'",
							'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
							'dokumen_tgl'=>"'".$_REQUEST['fmTGLDOKUMEN_KIB_F']."'",
							'dokumen_no'=>"'".$_REQUEST['fmNODOKUMEN_KIB_F']."'",
							'tmt'=>"'".$_REQUEST['fmTGLMULAI_KIB_F']."'",
							'status_tanah'=>"'".$_REQUEST['fmSTATUSTANAH_KIB_F']."'",
							'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_F']."'",
							//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
							'ket'=>"'".$_REQUEST['fmKET_KIB_F']."'",
							'noreg'=>"'".$_REQUEST['no_reg2']."'",
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"
							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
				case '07': {
					$get = $this->simpanData(
					$fmST_kib, 'kib_g',
						//array('id'=>"'".$id."'"),
						array('id'=>"'".$idkib."'"),
						array(
							'idbi'=>"'".$idbi_baru."'",
							'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
							'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
							'f'=>"'".$f_baru."'",'g'=>"'".$g_baru."'",
							'h'=>"'".$h_baru."'",'i'=>"'".$i_baru."'",
							'j'=>"'".$j_baru."'",							
							'uraian'=>"'".$_REQUEST['fmURAIAN_KIB_G']."'",							
							'pencipta'=>"'".$_REQUEST['fmPENCIPTA_KIB_G']."'",							
							'jenis'=>"'".$_REQUEST['fmJENIS_KIB_G']."'",							
							'ket'=>"'".$_REQUEST['fmKET_KIB_G']."'",
							'noreg'=>"'".$_REQUEST['no_reg2']."'",						
							'tahun'=>"'".$_REQUEST['thn_perolehan2']."'"							
						)				
					);
					$cek .= $get['cek'];
					$err .= $get['err'];
					break;
				}
			}
		 }
			
			
		}
	}else{
		if($err=='' && $hrg_pengganti == '')$err = "Harga pengganti belum diisi!";
		if($err=='' && $fmIDREKENING2 == '')$err = "Kode akun belum dipilih!";
		$idbi_ganti='';
		$ka_baru='';$kb_baru='';$kc_baru='';
		$kd_baru='';$ke_baru='';$kf_baru='';
		$thn_akun_baru='';
	}
	
		if($err==''){
			if($fmST == 0){//baru
				$ck_tgr = mysql_fetch_array( mysql_query(
				"select * from $this->TblName where id='$idtgr' "		
				));
				if($err=='' && $ck_tgr['no_sk'] != '')$err = "Barang sudah masuk di ketetapan gantirugi!";
				$aqry = "update $this->TblName_Edit  ".
					"set ".
					" no_sk='$no_ketetapan',tgl_sk='$tgl_ketetapan',".
					" harga_gantirugi='$hrg_pengganti',jns_ganti='$cr_pengganti',tgl_buku_sk='$tgl_buku',".
					" idbi_ganti='$idbi_ganti',". 
					" ka_ganti='$ka_baru',kb_ganti='$kb_baru',kc_ganti='$kc_baru',kd_ganti='$kd_baru',ke_ganti='$ke_baru',kf_ganti='$kf_baru',thn_akun_ganti='$thn_akun_baru',".
					" uid='$UID', tgl_update= now() ".
					"where id='".$idtgr."' ";
				
			}else{
				
				$aqry = "update $this->TblName_Edit  ".
					"set ".
					" no_sk='$no_ketetapan',tgl_sk='$tgl_ketetapan',".
					" harga_gantirugi='$hrg_pengganti',jns_ganti='$cr_pengganti',tgl_buku_sk='$tgl_buku',".
					" idbi_ganti='$idbi_ganti',".
					" ka_ganti='$ka_baru',kb_ganti='$kb_baru',kc_ganti='$kc_baru',kd_ganti='$kd_baru',ke_ganti='$ke_baru',kf_ganti='$kf_baru',thn_akun_ganti='$thn_akun_baru',". 
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
	
	function Hapus_Data($id){//id -> multi id with space delimiter
		global $HTTP_COOKIE_VARS;
		$err = ''; $cek='';
		$UID = $HTTP_COOKIE_VARS['coID'];
		$KeyValue = explode(' ',$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		//$Kondisi = 	"Id='".$id."'";
		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName ".$Kondisi		
		));
		if($old['jns_ganti']=='1'){
			$aqry = "DELETE FROM buku_induk WHERE id='".$idbi_ganti."'";	$cek .= $aqry;	
				$qry = mysql_query($aqry);
			if($qry == FALSE) $err='Gagal Hapus BI '.mysql_error();
		}
		
		$aqry = "update $this->TblName_Hapus  ".
					"set ".
					" no_sk='',tgl_sk='',".
					" harga_gantirugi='',jns_ganti='',tgl_buku_sk='',".
					" idbi_ganti='',". 
					" ka_ganti='',kb_ganti='',kc_ganti='',kd_ganti='',ke_ganti='',kf_ganti='',thn_akun_ganti='',".
					" uid='$UID', tgl_update= now() ".$Kondisi; $cek.=$aqry;
		$qry = mysql_query($aqry);
		if ($qry==FALSE){
			$err = 'Gagal Hapus Ketetapan';
		}
		
		return array('err'=>$err,'cek'=>$cek);
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
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter()','--- Pilih SKPD ---','00');
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
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			if($ref_iddkb != ''){
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',TRUE);	
				}else{
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',FALSE);
				}
			
			break;
		    }
				
			case 'PilihBarangBaru':{	
				$IDBarang = $_REQUEST['IDBarang'];
							
				$get = $this->SetPilihBarangBaru($IDBarang);				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];												
				break;
			}
			case 'Filldetailtgr':{	
				$idtgr = $_REQUEST['id_tgr'];
							
				$get = $this->SetPilihTGR($idtgr);				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];												
				break;
			}
			case 'getNoRegAkhir':{				
				$get = $this->getNoRegAkhir();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				$json=TRUE;	
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function SetPilihBarangBaru($IDBarang){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$fmIDBARANGBARU = explode('.',$IDBarang);
		$fBARU = $fmIDBARANGBARU[0];
		$gBARU = $fmIDBARANGBARU[1];
		$hBARU = $fmIDBARANGBARU[2];
		$iBARU = $fmIDBARANGBARU[3];
		$jBARU = $fmIDBARANGBARU[4];
		
		if($err==''){
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$fBARU.$gBARU.$hBARU.$iBARU.$jBARU."'")) ;
			
			$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			
			$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
			$tmax = mysql_fetch_array(mysql_query($kueri1));
			$akn = mysql_fetch_array(mysql_query("select * from ref_jurnal where thn_akun = '".$tmax['thn_akun']."' 
					and ka='".$brg['ka']."' and kb='".$brg['kb']."' 
					and kc='".$brg['kc']."' and kd='".$brg['kd']."' 
					and ke='".$brg['ke']."' and kf='".$brg['kf']."'")) ;
			$akn = array_map('utf8_encode', $akn);
			
			$content->plhkode_account2 = $akn['ka'].'.'.$akn['kb'].'.'.$akn['kc'].'.'.$akn['kd'].'.'.$akn['ke'].'.'.$akn['kf'];
			$content->plhnama_account2 = $akn['nm_account'];
			$content->plhtahun_account2 = $akn['thn_akun'];
			
			$plhdetailKIB=$this->getFormEntryKIB($fBARU);
				
		$this->form_fields = array(				
			'detbrgkib1' => array( 
					'label'=>'', 
					'value'=>$plhdetailKIB,//Penatausahaan_FormEntry_Kib($dt['f']) ,
				'type'=>'merge' 
			),
			
		);
		$content->plhdetailKIB = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";			
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function getFormEntryKIB($f,$dt){
		global $Main;
			
			$fmKET_KIB_A =$dt['ket'];
			$bersertifikat =$dt['bersertifikat'];
			$status_penguasaan_ =$dt[''];
			$fmLUAS_KIB_A =$dt['luas'];
			$fmLETAK_KIB_A =$dt['alamat'];
			$alamat_kel =$dt['alamat_kel'];
			$alamat_kec =$dt['alamat_kec'];
			$alamat_a =$dt['alamat_a'];
			$alamat_b =$dt['alamat_b'];
			$fmHAKPAKAI_KIB_A =$dt['status_hak'];
			$fmTGLSERTIFIKAT_KIB_A =$dt['sertifikat_tgl'];
			$fmNOSERTIFIKAT_KIB_A =$dt['sertifikat_no'];
			$fmPENGGUNAAN_KIB_A =$dt['penggunaan'];
			
			$fmMERK_KIB_B =$dt['merk'];
			$fmUKURAN_KIB_B =$dt['ukuran'];
			$fmBAHAN_KIB_B =$dt['bahan'];
			$fmPABRIK_KIB_B =$dt['no_pabrik'];
			$fmRANGKA_KIB_B =$dt['no_rangka'];
			$fmMESIN_KIB_B =$dt['no_mesin'];
			$fmPOLISI_KIB_B =$dt['no_polisi'];
			$fmBPKB_KIB_B =$dt['no_bpkb'];
			$fmKET_KIB_B =$dt['ket'];
			
			$fmKONDISI_KIB_C =$dt['kondisi'];
			$fmTINGKAT_KIB_C =$dt['konstruksi_tingkat'];
			$fmBETON_KIB_C =$dt['konstruksi_beton'];
			$fmLUASLANTAI_KIB_C =$dt['luas_lantai'];
			$fmLETAK_KIB_C =$dt['alamat'];
			$fmTGLGUDANG_KIB_C =$dt['dokumen_tgl'];
			$fmNOGUDANG_KIB_C =$dt['dokumen_no'];
			$fmLUAS_KIB_C =$dt['luas'];
			$fmSTATUSTANAH_KIB_C =$dt['status_tanah'];
			$fmNOKODETANAH_KIB_C =$dt['status_tanah'];
			$fmKET_KIB_C =$dt['ket'];
				
			$fmKONSTRUKSI_KIB_D=$dt['konstruksi'];
			$fmPANJANG_KIB_D=$dt['panjang'];
			$fmLEBAR_KIB_D=$dt['lebar'];
			$fmLUAS_KIB_D=$dt['luas'];
			$fmALAMAT_KIB_D=$dt['alamat'];
			$fmTGLDOKUMEN_KIB_D=$dt['dokumen_tgl'];
			$fmNODOKUMEN_KIB_D=$dt['dokumen_no'];
			$fmSTATUSTANAH_KIB_D=$dt['status_tanah'];
			$fmNOKODETANAH_KIB_D=$dt['kode_tanah'];
			$fmKONDISI_KIB_D=$dt['kondisi'];
			$fmKET_KIB_D=$dt['ket'];
		
			$fmJUDULBUKU_KIB_E=$dt['buku_judul'];
			$fmSPEKBUKU_KIB_E=$dt['buku_spesifikasi'];
			$fmSENIBUDAYA_KIB_E=$dt['seni_asal_daerah'];
			$fmSENIPENCIPTA_KIB_E=$dt['seni_pencipta'];
			$fmSENIBAHAN_KIB_E=$dt['seni_bahan'];
			$fmJENISHEWAN_KIB_E=$dt['hewan_jenis'];
			$fmUKURANHEWAN_KIB_E=$dt['hewan_ukuran'];
			$fmKET_KIB_E=$dt['ket'];
					
			$fmBANGUNAN_KIB_F=$dt['bangunan'];
			$fmTINGKAT_KIB_F=$dt['konstruksi_tingkat'];
			$fmBETON_KIB_F=$dt['konstruksi_beton'];
			$fmLUAS_KIB_F=$dt['luas'];
			$fmLETAK_KIB_F=$dt['alamat'];
			$fmTGLDOKUMEN_KIB_F=$dt['dokumen_tgl'];
			$fmNODOKUMEN_KIB_F=$dt['dokumen_no'];
			$fmTGLMULAI_KIB_F=$dt['tmt'];
			$fmSTATUSTANAH_KIB_F=$dt['status_tanah'];
			$fmNOKODETANAH_KIB_F=$dt['kode_tanah'];
			$fmKET_KIB_F=$dt['ket'];
			
			$fmURAIAN_KIB_G=$dt['uraian'];
			$fmPENCIPTA_KIB_G=$dt['pencipta'];
			$fmJENIS_KIB_G=$dt['jenis'];
			$fmKET_KIB_G=$dt['ket'];
		
		
		switch($f){
			case 0:{ //BI
				$hsl="";
			break;}
			case '01':{//KIB A
				$hsl="
				<tr valign=\"top\">   
				<td width='150'>Luas (m<sup>2</sup>)</td>
				<td width='10'>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_A","",$fmLUAS_KIB_A,"")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_A'>$fmLETAK_KIB_A</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','','','valign="top" height="24"')."
				
				
				<tr valign=\"\">   
				<td  colspan=3>Status Tanah :</td>
				</tr>
				<tr valign=\"\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Hak </td>
				<td>:</td>
				<td>".cmb2D('fmHAKPAKAI_KIB_A',$fmHAKPAKAI_KIB_A,$Main->StatusHakPakai,'')."</td>
				</tr>".
				formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;Status Sertifikat ',':', 
					cmb2D_v2('bersertifikat',$bersertifikat,$Main->StatusSertifikat,'','Belum Bersertifikat',"onchange=".$this->Prefix.".sertifikat_onchange()")
					,'','valign="top" height="24"')."
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat  </td><td>:</td>
				<td>".
				createEntryTgl("fmTGLSERTIFIKAT_KIB_A",$fmTGLSERTIFIKAT_KIB_A, $bersertifikat==1?"":"1",
					  'tanggal bulan tahun (mis: 1 Januari 1998)','','adminForm',1
					).//createEntryTgl("fmTGLSERTIFIKAT_KIB_A", $bersertifikat==1?"":"1").
				"</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat  </td><td>:</td><td>".
				txtField('fmNOSERTIFIKAT_KIB_A',$fmNOSERTIFIKAT_KIB_A,'100','20','text', $bersertifikat==1?"":"disabled").
				"</td></tr>
				<tr valign=\"\">   
				<td >Penggunaan</td>
				<td >:</td>
				<td>
				".txtField('fmPENGGUNAAN_KIB_A',$fmPENGGUNAAN_KIB_A,'100','','text',"style='width:346'")."
				</td>				
				</tr>".
				"<tr valign=\"top\">   
				<td >Keterangan</td>
				<td >:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_A'>$fmKET_KIB_A</textarea>
				</td>
				</tr>";
				break;}		
			case '02':{
				$hsl = "<tr valign=\"top\">   
				<td width='150'>Merk/Type</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmMERK_KIB_B'>$fmMERK_KIB_B</textarea>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Ukuran/CC</td>
				<td width=''>:</td>
				<td>
				".txtField('fmUKURAN_KIB_B',$fmUKURAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Bahan</td>
				<td width=''>:</td>
				<td>
				".txtField('fmBAHAN_KIB_B',$fmBAHAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td  colspan=3>Nomor :</td>
				</tr>
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Pabrik </td><td>:</td><td>".txtField('fmPABRIK_KIB_B',$fmPABRIK_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Rangka </td><td>:</td><td>".txtField('fmRANGKA_KIB_B',$fmRANGKA_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Mesin </td><td>:</td><td>".txtField('fmMESIN_KIB_B',$fmMESIN_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Polisi </td><td>:</td><td>".txtField('fmPOLISI_KIB_B',$fmPOLISI_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;BPKB </td><td>:</td><td>".txtField('fmBPKB_KIB_B',$fmBPKB_KIB_B,'100','20','text','')."</td></tr>
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_B'>$fmKET_KIB_B</textarea>
				</td>
				</tr>";			
			break;}
			case '03':{//kib c
				$hsl="<tr valign=\"top\">   
				<td width='150'>Kontruksi Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmKONDISI_KIB_C',$fmKONDISI_KIB_C,$Main->Bangunan,'')."</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_C',$fmTINGKAT_KIB_C,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_C',$fmBETON_KIB_C,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Lantai</td>
				<td width=''>:</td>
				<td>
				".txtField('fmLUASLANTAI_KIB_C',$fmLUASLANTAI_KIB_C,'10','10','text','')." &nbsp;M<sup>2</sup>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_C'>$fmLETAK_KIB_C</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				<tr valign=\"top\">   
				<td colspan=3>Dokumen Gedung :</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td><td>:</td><td>".
					createEntryTgl("fmTGLGUDANG_KIB_C",$fmTGLGUDANG_KIB_C, "").//createEntryTgl("fmTGLGUDANG_KIB_C", "").	//InputKalender("fmTGLGUDANG_KIB_C")
				"</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td><td>:</td><td>
				".txtField('fmNOGUDANG_KIB_C',$fmNOGUDANG_KIB_C,'100','20','text','')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Tanah (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_C","",$fmLUAS_KIB_C,"")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_C',$fmSTATUSTANAH_KIB_C,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td>Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','63','text','')."
				<span style='color: red'> kode_lokasi.kode_barang (mis: 11.10.00.17.01.83.01.01.01.11.01.06.0001)</span>	
				
				</td>
				</tr>".
				/*formEntryBase2('Status Penguasaan',':', 
					cmb2D_v2('status_penguasaan',$status_penguasaan_,$Main->arStatusPenguasaan,'','Pilih','')
					,'','valign="top" height="24"').
				*/
				"<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_C'>$fmKET_KIB_C</textarea>
				</td>
				</tr>";
				
			break;}
			case '04':{//kib d
				$hsl="<tr valign=\"top\">   
				<td width='150'>Konstruksi</td>
				<td width=''>:</td>
				<td>".txtField('fmKONSTRUKSI_KIB_D',$fmKONSTRUKSI_KIB_D,'50','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >Panjang (Km)</td>
				<td width=''>:</td>
				<td>				
				".inputFormatRibuan("fmPANJANG_KIB_D","",$fmPANJANG_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Lebar (m)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLEBAR_KIB_D","",$fmLEBAR_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_D","",$fmLUAS_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmALAMAT_KIB_D'>$fmALAMAT_KIB_D</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				
				<tr valign=\"top\">   
				<td >Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_D", $fmTGLDOKUMEN_KIB_D, ""). //InputKalender("fmTGLDOKUMEN_KIB_D")
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_D',$fmNODOKUMEN_KIB_D,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_D',$fmSTATUSTANAH_KIB_D,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>".txtField('fmNOKODETANAH_KIB_D',$fmNOKODETANAH_KIB_D,'100','50','text','')."</td>
				</tr>".
				/*formEntryBase2('Status Penguasaan',':', 
					cmb2D_v2('status_penguasaan',$status_penguasaan_,$Main->arStatusPenguasaan,'','Pilih','')
					,'','valign="top" height="24"').*/
				"<tr valign=\"top\">   
				<td>Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_D'>$fmKET_KIB_D</textarea>
				</td>
				</tr>";
				
			break;}
			case '05':{//kib e
				$hsl="<tr valign=\"top\" height= '24'>   
				<td  colspan = '3'>Buku Perpustakaan</td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta</td>
				<td width='10'>:</td>
				<td>".txtField('fmJUDULBUKU_KIB_E',$fmJUDULBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi</td>
				<td width=''>:</td>
				<td>".txtField('fmSPEKBUKU_KIB_E',$fmSPEKBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3'>Barang bercorak Kesenian/Kebudayaan  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBUDAYA_KIB_E',$fmSENIBUDAYA_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Pencipta</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIPENCIPTA_KIB_E',$fmSENIPENCIPTA_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Bahan</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBAHAN_KIB_E',$fmSENIBAHAN_KIB_E,'100','50','text','')."</td>
				</tr>
				
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3' >Hewan Ternak  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Jenis</td>
				<td width=''>:</td>
				<td>".txtField('fmJENISHEWAN_KIB_E',$fmJENISHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td '>&nbsp;&nbsp;&nbsp;&nbsp;Ukuran</td>
				<td width=''>:</td>
				<td>".txtField('fmUKURANHEWAN_KIB_E',$fmUKURANHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_E'>$fmKET_KIB_E</textarea>
				</td>
				</tr>";		
			break;}
			case '06':{//kib f
				$hsl="<tr valign=\"top\">   
				<td width='150'>Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmBANGUNAN_KIB_F',$fmBANGUNAN_KIB_F,$Main->Bangunan,'')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td  colspan=3>Kontruksi Bangunan</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_F',$fmTINGKAT_KIB_F,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_F',$fmBETON_KIB_F,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_F","",$fmLUAS_KIB_F,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_F'>$fmLETAK_KIB_F</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				<tr valign=\"top\">   
				<td width='150'>Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td >:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_F", $fmTGLDOKUMEN_KIB_F, ""). //<!--<td>".InputKalender("fmTGLDOKUMEN_KIB_F")."</td>-->
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_F',$fmNODOKUMEN_KIB_F,'100','50','text','')." </td>
				</tr>
				
				<tr valign=\"top\">   
				<td >Tanggal/Bln./Thn. mulai</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLMULAI_KIB_F", $fmTGLMULAI_KIB_F, "").//<!--<td>".InputKalender("fmTGLMULAI_KIB_F")."</td>-->
				"</td>				
				</tr>	
				
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_F',$fmSTATUSTANAH_KIB_F,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_F',$fmNOKODETANAH_KIB_F,'100','50','text','')."
				</td>
				</tr>			<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_F'>$fmKET_KIB_F</textarea>
				</td>
				</tr>					";
				
				break;}	
			case '07':{//kib g
				$hsl="
				<tr valign=\"top\">   
				<td width='150'>Uraian</td>
				<td width='10'>:</td>
				<td>".txtField('fmURAIAN_KIB_G',$fmURAIAN_KIB_G,'100','59','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td width='150'>Pencipta</td>
				<td width='10'>:</td>
				<td>".txtField('fmPENCIPTA_KIB_G',$fmPENCIPTA_KIB_G,'100','59','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td width='150'>Jenis</td>
				<td width='10'>:</td>
				<td>".txtField('fmJENIS_KIB_G',$fmJENIS_KIB_G,'100','59','text','')."</td>
				</tr>

				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmKET_KIB_G'>$fmKET_KIB_G</textarea>
				</td>
				</tr>";		
			break;}	
		}
		
		return $hsl;
	}
	
	function SetPilihTGR($idtgr){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$tgr = mysql_fetch_array(mysql_query("select * from gantirugi where id='".$idtgr."'")) ;
		$cek.="select * from gantirugi where id='".$idtgr."'";
		
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$tgr['id_bukuinduk']."'")) ;
			$cek.="select * from buku_induk where id='".$tgr['id_bukuinduk']."'";
			$content->plhidbi=$bi['id'];
			$content->idbi_awal=$bi['idawal'];
			$content->plhnoreg=$tgr['noreg'];
			$content->plhthn_perolehan=$tgr['thn_perolehan'];
			$content->plhspesifikasi=$this->getSpesifikasi($bi['id']);		
			$content->plhharga_perolehan=number_format($tgr['harga_perolehan'],2,',','.' );
			$StSusut=0;
			$harga_buku=getNilaiBuku($bi['id'],$tgr['tgl_gantirugi'],$StSusut);	
			$content->plhharga_buku=number_format($harga_buku,2,',','.' );			
			
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function getSpesifikasi($idbi=""){
		$arrbi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$idbi."'")) ;
		
	 		if ($arrbi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);	

				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];
			}
			
			if ($arrbi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];								
			}
			
			if ($arrbi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);	
					
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];
				
			}
			if ($arrbi['f']=="02"){
				$aqry = "select * from kib_b where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$arrdet['merk']." / ".$arrdet['no_polisi']." / ".$arrdet['no_bpkb'];
				
			}
			if ($arrbi['f']=="05"){
				$aqry = "select * from kib_e where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$arrdet['buku_judul'];
				
			}
			if ($arrbi['f']=="06"){
				$aqry = "select * from kib_f where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];								
			}
			if ($arrbi['f']=="07"){
				$aqry = "select * from kib_g where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$bi['jenis'];
			}
		return $spesifikasi;
	}
	function getNoRegAkhir(){		
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$cek = ''; $err=''; $content=''; 
		
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['fmSKPDUnit_form'];
		$e1 = $_REQUEST['fmSKPDSubUnit_form'];
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$thn_perolehan = $_REQUEST['thn_perolehan2'];
		
		$kond = $c.$d.$e.$e1.$fmIDBARANG.$thn_perolehan;
		$aqry = " select (ifnull(max(noreg),0)+1) as maxno from buku_induk where concat(c,d,e,e1,f,'.',g,'.',h,'.',i,'.',j,thn_perolehan) = '$kond' " ; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query(
			$aqry
		));
		$fmN = ($get['maxno']+10000)."";
		$content->noreg = substr($fmN,1,4);	
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
}
$tgr_ketetapan = new tgr_ketetapanObj();

?>