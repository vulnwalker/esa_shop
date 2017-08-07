<?php
class dpb_spkObj extends DaftarObj2{
	var $Prefix = 'dpb_spk';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'v_pengadaan';//'v1_rkb'
	var $TblName_Hapus = 'pengadaan_sk';
	var $TblName_Edit = 'pengadaan_sk';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_barang','jml_harga');
	var $fieldSum_lokasi = array(6,7);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 7, 6, 6);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	var $totalCol = 10; //total kolom daftar
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun	
	var $PageTitle = 'Pengadaan';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='dpb_spk.xls';
	var $Cetak_Judul = 'SPK DAFTAR PENGADAAN BARANG';	
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
			 "<script type='text/javascript' src='js/pengadaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".	 
			 "<script type='text/javascript' src='js/perencanaan/dkb.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/pengadaan/dpb_spk_det.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".	
			$scriptload;
	}
	function setTitle(){
		return 'DAFTAR SPK PENGADAAN BARANG';
	}
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a  href="pages.php?Pg=dpb_rencana" title="Daftar Pengadaan Barang Milik Daerah">Rencana</a>  |  
				<a style="color:blue;" href="pages.php?Pg=dpb_spk" title="Daftar Pengadaan Barang Milik Daerah SPK">SPK</a>  |  
				<a href="pages.php?Pg=dpb" title="Daftar Pengadaan Pemeliharaan Barang Milik Daerah">Pengadaan</a>  |
				
				<a href="pages.php?Pg=rekapdpb" title="Rekap Daftar Pengadaan Barang Milik Daerah">Rekap</a>  |  
				<a href="pages.php?Pg=rekapdpb_skpd" title="Rekap Daftar Pengadaan Pemeliharaan Barang Milik Daerah">Rekap (SKPD)</a>  
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'DPBMD Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit DPBMD')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus DPBMD')."</td>";
						
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
					<th class='th01' width='40' rowspan=2 width='40'>No.</th>
					$Checkbox		
	   				<th class='th02' colspan=2 width='180'>SPK/ Kontrak</th>
					<th class='th01' rowspan=2 >Program/ Kegiatan/ pekerjaan</th>
					<th class='th01' rowspan=2 >Jumlah Barang</th>		
					<th class='th01' rowspan=2 >Jumlah Harga</th>
	   				<th class='th01' rowspan=2 >Nama Perusahaan</th>
					<th class='th01' rowspan=2 >Alamat Perusahaan</th>
					<th class='th01' rowspan=2 >Keterangan</th>
				</tr>
				<tr>
					<th class='th01' width='80'>Tanggal </th>
					<th class='th01' width='80'>Nomor </th>				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS;
		$skpd_urusan= mysql_fetch_array(mysql_query("select * from ref_skpd_urusan where c='".$isi['c']."' and d='".$isi['d']."' ")) ;	
		$Program=mysql_fetch_array(mysql_query("select p,nama from ref_program where bk='".$skpd_urusan['bk']."' and ck='".$skpd_urusan['ck']."'  and dk='".$skpd_urusan['dk']."' and p!='0' and q='0' "));
		$Kegiatan=mysql_fetch_array(mysql_query("select q,nama from ref_program where bk='".$skpd_urusan['bk']."' and ck='".$skpd_urusan['ck']."'  and dk='".$skpd_urusan['dk']."'  and p='".$isi['p']."' and q!='0' "));
		$Koloms = array();		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array("align='left'",  TglInd($isi['spk_tgl'] ) );
		$Koloms[] = array("align='left'",  $isi['spk_no']  );
		$Koloms[] = array("align='left'", 
			$Program['nama'].'/ '.$Kegiatan['nama'].'/ '.$isi['nama_pekerjaan']				
		);
	 	$Koloms[] = array("align='right'", number_format($isi['jml_barang'],0,',','.'));		
	 	$Koloms[] = array("align='right'", number_format($isi['jml_harga'],2,',','.'));		
	 	$Koloms[] = array("align='left'", $isi['nama_perusahaan']);
	 	$Koloms[] = array("align='left'", $isi['alamat']);
	 	$Koloms[] = array("align='left'", $isi['ket']);
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
		
		//data cari ----------------------------
		
		$arrCari = array(
			array('1','1'),
			array('2','2'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILCARI = cekPOST('fmPILCARI');
		 
		
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
					"<table><tr><td width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Semua','').
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
		$arrKondisi[] = "sttemp='0'";
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		
		$fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari');
		$fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai');
		$fmPILCARI = cekPOST('fmPILCARI');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		
		if ($fmThnAnggaranDari == $fmThnAnggaranSampai){
		
			if(!($fmThnAnggaranDari=='')  && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "tahun>='$fmThnAnggaranDari' and tahun<='$fmThnAnggaranSampai'";
			switch($fmPILCARI){			
			case '1': $arrKondisi[] = " spk_tgl>='".$fmThnAnggaranDari."-01-01' and  cast(spk_tgl as DATE)<='".$fmThnAnggaranSampai."-06-30' "; break;
			case '2': $arrKondisi[] = " spk_tgl>='".$fmThnAnggaranDari."-07-01' and  cast(spk_tgl as DATE)<='".$fmThnAnggaranSampai."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThnAnggaranDari=='') && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "thn_anggaran>='$fmThnAnggaranDari' and thn_anggaran<='$fmThnAnggaranSampai'";
		}
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');
		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}		
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = ' Order By spk_tgl';// Order By no_terima desc ';
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
	
	function getJmlDKB($tahun, $c, $d, $e,$e1, $f,$g,$h,$i,$j){
		$aqry = "select * from dkb where tahun='$tahun' and c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ";
		$get =  mysql_fetch_array(mysql_query($aqry));
		if($get['jml_barang']== NULL) $get['jml_barang']=0;
		return $get['jml_barang'];
	}
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$ins_spk=mysql_query("Insert into pengadaan_sk (sttemp) value ('1') ");		
		$dt['spk_tgl'] = date("Y-m-d");
		$dt['dpa_tgl'] = date("Y-m-d");
		$dt['ref_idsk'] = mysql_insert_id();
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
		$dt['ref_idsk']=$dt['id'];
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
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		
	   	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'")) ;
	   	$skpd_urusan= mysql_fetch_array(mysql_query("select * from ref_skpd_urusan where c='".$dt['c']."' and d='".$dt['d']."' ")) ;	
		$queryProgram="select p,nama from ref_program where bk='".$skpd_urusan['bk']."' and ck='".$skpd_urusan['ck']."'  and dk='".$skpd_urusan['dk']."' and p!='0' and q='0' ";
		$queryKegiatan="select q,nama from ref_program where bk='".$skpd_urusan['bk']."' and ck='".$skpd_urusan['ck']."'  and dk='".$skpd_urusan['dk']."'  and p='".$dt['p']."' and q!='0' ";
		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		 $this->form_fields = array(									 
			'bidang' => array( 
						'label'=>'BIDANG', 
						'labelWidth'=>150,
						'value'=>$bidang, 
						'type'=>'', 'row_params'=>"height='21'"
						),
			'skpd' => array( 
						'label'=>'SKPD', 
						'value'=>$unit, 
						'type'=>'', 'row_params'=>"height='21'"
						),			
            'thn_anggaran' => array( 
						'label'=>'Tahun',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='fmtahun' id='fmtahun' size='4' value='".$dt['tahun']."' readonly=''>"
						),
			'spk' => array(
						'label'=>'', 
						'value'=>'SPK/Kontrak', 
						'type'=>'merge',
						'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'spk_no' => array(
						'label'=>'&nbsp;&nbsp;Nomor', 
						'value'=>$dt['spk_no'], 
						'type'=>'text'
			),
			'spk_tgl' => array(
						'label'=>'&nbsp;&nbsp;Tanggal', 
						'value'=> createEntryTgl('spk_tgl',$dt['spk_tgl'] ), 
						'type'=>''
			),
			'dpa' => array(
						'label'=>'', 
						'value'=>'SP2D/Kwitansi', 
						'type'=>'merge',
						'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
						),
			'dpa_no' => array(
						'label'=>'&nbsp;&nbsp;Nomor', 
						'value'=>$dt['dpa_no'], 
						'type'=>'text'
			),
			'dpa_tgl' => array(
						'label'=>'&nbsp;&nbsp;Tanggal', 
						'value'=> createEntryTgl('dpa_tgl',$dt['dpa_tgl'] ), 
						'type'=>''
						),	
			'program' => array(  
						'label'=>'Program', 
						'value'=> cmbQuery('p',$dt['p'],$queryProgram,'id=p onChange=\''.$this->Prefix.'.ProgramAfter()\' ','-- Pilih Program --'),   
						'type'=>'' , 
						),		
			'kegiatan' => array(  
						'label'=>'Kegiatan', 
						'value'=> '<div id=div_q>'.cmbQuery('q',$dt['q'],$queryKegiatan,'id=q ','-- Pilih Kegiatan --').'</div>',   
						'type'=>'' , 
						),
			'nama_pekerjaan' => array(
						'label'=>'Pekerjaan', 
						'value'=>$dt['nama_pekerjaan'], 
						'type'=>'text',
						'param'=> "style='width:430px'"
						),		
			'nama_perusahaan' => array(
						'label'=>'Nama Perusahaan', 
						'value'=>$dt['nama_perusahaan'], 
						'type'=>'text',
						'param'=> "style='width:430px'"
						),		
			'alamat' => array( 
						'label'=>'Alamat', 
						'value'=>"<textarea name=\"alamat\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['alamat']."</textarea>", 
						'type'=>'', 'row_params'=>"valign='top'"
						),
			'ket' => array( 
						'label'=>'Keterangan', 
						'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
						'type'=>'', 'row_params'=>"valign='top'"
						),
			'daftarrincian' => array( 
						'label'=>'',
						 'value'=>"<a href=\"javascript:dpb_spk.showDpb_det('divDpb_spkList');\">
						 			<div style=\"border-bottom: 2px solid #02769D;\">
									 <table class=\"barHeader\" style=\"height:25;\" id=\"barDpb_spk_head\">
									  <tbody>
									   <tr>
									     <td style=\"width:20\" class=\"\"><img id=\"barDpb_det_ico\" src=\"images/tumbs/right.png\"></td>
							             <td style=\"padding: 0 8 0 0\" class=\"\" width=\"\">Rincian Barang
									      </td>
										 </tr>
										 </tbody>
										 </table>
										 </a>
										  </div>
										 <div id='divDpb_spkList' style='display:none'></div>
									  ", 
						 'type'=>'merge'
			),
		);
		
		//tombol
		$this->form_menubawah = 
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='idsk' name='idsk' value='".$dt['ref_idsk']."'> ".
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
		
		$p = $_REQUEST['p'];
		$q = $_REQUEST['q'];
		
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$tahun = $_REQUEST['fmtahun'];
		
		$spk_no 	= $_REQUEST['spk_no'];
		$spk_tgl 	= $_REQUEST['spk_tgl'];
		$nama_pekerjaan = $_REQUEST['nama_pekerjaan'];
		$nama_perusahaan = $_REQUEST['nama_perusahaan'];
		
		$alamat = $_REQUEST['alamat'];

		$dpa_no 	= $_REQUEST['dpa_no'];
		$dpa_tgl 	= $_REQUEST['dpa_tgl'];
		
		$idsk 	= $_REQUEST['idsk'];

		$ket = $_REQUEST['fmKET'];

		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName where id='$id' "		
		));
		
		
		//-- validasi
		if($err=='' && $spk_no == '')$err = "Nomor SPK belum diisi!";
		if($err=='' && $spk_tgl == '')$err = "Tanggal SPK belum diisi!";
		if($err=='' && ($p == '' || $p == '0' ) )$err = "Program belum dipilih!";
		if($err=='' && ($q == '' || $q == '0' ) )$err = "Kegiatan belum dipilih!";		
		
		
		if($err==''){
			if($fmST == 0){//baru
				
				$aqry = 
					"update $this->TblName_Edit  ".
					" set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',".
					" spk_no='$spk_no',spk_tgl='$spk_tgl',nama_perusahaan='$nama_perusahaan',nama_pekerjaan='$nama_pekerjaan',".
					" dpa_tgl='$dpa_tgl',dpa_no='$dpa_no',".
					" tahun='$tahun',p='$p',q='$q',".
					" ket='$ket',sttemp='0',alamat='$alamat',". 
					" uid='$UID', tgl_update= now() ".
					"where id='".$idsk."' ";
				
			}else{
				
				$aqry = 
					"update $this->TblName_Edit  ".
					" set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',".
					" spk_no='$spk_no',spk_tgl='$spk_tgl',nama_perusahaan='$nama_perusahaan',nama_pekerjaan='$nama_pekerjaan',".
					" dpa_tgl='$dpa_tgl',dpa_no='$dpa_no',".
					" tahun='$tahun',p='$p',q='$q',".
					" ket='$ket',sttemp='0',alamat='$alamat',". 
					" uid='$UID', tgl_update= now() ".
					"where id='".$old['id']."' ";	
				
				
			}
			$cek .= $aqry;
			$qry = mysql_query($aqry);
			if($qry == FALSE) $err='Gagal SQL'.mysql_error();
		}		
		//$err=$cek;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function Hapus_Validasi($id){
		$err ='';
		//$KeyValue = explode(' ',$id);
		$get = mysql_fetch_array(mysql_query(
			"select count(*) as cnt from penerimaan where id_pengadaan ='$id' "
		));
		if($err=='' && $get['cnt']>0 ) $err = 'Data Tidak Bisa Dihapus, Sudah ada di Penerimaan!';
		
		return $err;
	}
	
	
	function getStat(){
		$cek=''; $err=''; $content='';
		//global $Main;
		$cbid = $_POST[$this->Prefix.'_cb'];
		$id = $cbid[0];		
		$aqry = "select * from $this->TblName where id='$id' ";
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->stat = $isi['stat'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	
	function getPengadaanSebelumnya($tahun, $c, $d, $e ,$e1 , $f, $g, $h, $i, $j, $id=''){
		$idstr = $id==''? '' : " and id<>'$id' ";
		$aqry = "select sum(jml_barang) as tot from pengadaan where ".
				"tahun='$tahun'".
				" and c='$c' and d='$d' and e='$e'  and e1='$e1'  ".
				" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' $idstr "; $cek.=$aqry;
		$get = mysql_fetch_array( mysql_query(
			$aqry
		));	
		if($get['tot']==NULL) $get['tot'] = 0;
		return $get['tot'];
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
			
			case 'ProgramAfter':{
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$p = $_REQUEST['p'];						
				$skpd_urusan= mysql_fetch_array(mysql_query("select * from ref_skpd_urusan where c='".$c."' and d='".$d."' ")) ;	
				$queryKegiatan="select q,nama from ref_program where bk='".$skpd_urusan['bk']."' and ck='".$skpd_urusan['ck']."'  and dk='".$skpd_urusan['dk']."'  and p='".$p."'  and q!='0' ";
		
				$hasilKegiatan = mysql_query($queryKegiatan);
				$opsi_kegiatan = "<option value=''>-- Pilih Kegiatan --</option>";
				while ($rowKegiatan = mysql_fetch_array($hasilKegiatan))
				{
					//$selectedSUBUNIT=$rowKegiatan['e1']==$subunit? 'selected':'';
					$opsi_kegiatan.="<option value='".$rowKegiatan['q']."'>".$rowKegiatan['nama']."</option>";
				}
				$content = "<select name='q' id='q'>".$opsi_kegiatan."</select>";
			break;
		}		
				
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$dpb_spk = new dpb_spkObj();

?>