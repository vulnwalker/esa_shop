<?php

class PengeluaranObj extends DaftarObj2{
	var $Prefix = 'pengeluaran';
	//var $SHOW_CEK = TRUE;	
	var $TblName = 'v1_pengeluaran';//'v1_rkb'
	var $TblName_Hapus = 'pengeluaran';
	var $TblName_Edit = 'pengeluaran';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 9, 8, 8);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Penerimaan, Penyimpanan dan Penyaluran';
	var $PageIcon = 'images/penerimaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='pengeluaran.xls';
	var $Cetak_Judul = 'DAFTAR PENGELUARAN BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	//var $row_params= " valign='top'";
	var $noModul=3;  
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 			
			//"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/gudang.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".			
			$scriptload;
	}
	function setPage_OtherScript_nodialog(){
		return //"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
					"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
					"<script type='text/javascript' src='js/gudang.js' language='JavaScript' ></script>".			
						"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	function setTitle(){
		return 'Daftar Pengeluaran Barang Milik Daerah';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
	global $Main;
	
		if ( $Main->VERSI_NAME == 'JABAR') {
			$navatas='<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
			<a href="pages.php?Pg=penerimaan" style="color:blue;" title="Rencana Kebutuhan Aset Barang Milik Daerah">ASET TETAP</a>  |  
			<a href="pages.php?Pg=penerimaanbarang_persediaan" title="Rencana Kebutuhan Persediaan Barang Milik Daerah">PERSEDIAAN</a>
			&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
			<a href="pages.php?Pg=penerimaan" title="Daftar Penerimaan Barang Milik Daerah">Penerimaan</a>  |  
			<a href="pages.php?Pg=pengeluaran" style="color:blue;" title="Daftar Pengeluaran Barang Milik Daerah">Pengeluaran</a>  |
			
			<a href="pages.php?Pg=rekappenerimaan" title="Rekap Daftar Penerimaan Barang Milik Daerah">Rekap Penerimaan</a>  |  
			<a href="pages.php?Pg=rekappengeluaran" title="Rekap Daftar Pengeluaran Barang Milik Daerah">Rekap Pengeluaran</a>  
			&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';	
		}else{
			$navatas='<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
			<a href="pages.php?Pg=penerimaan" title="Daftar Penerimaan Barang Milik Daerah">Penerimaan</a>  |  
			<a href="pages.php?Pg=pengeluaran" style="color:blue;" title="Daftar Pengeluaran Barang Milik Daerah">Pengeluaran</a>  |
			
			<a href="pages.php?Pg=rekappenerimaan" title="Rekap Daftar Penerimaan Barang Milik Daerah">Rekap Penerimaan</a>  |  
			<a href="pages.php?Pg=rekappengeluaran" title="Rekap Daftar Pengeluaran Barang Milik Daerah">Rekap Pengeluaran</a>  
			&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';  	
		}
	
	
		return
			$navatas;
	}
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:pengeluaran.Baru2()","new_f2.png","Baru",'Penerimaan Baru')."</td>".
			"<td>".genPanelIcon("javascript:pengeluaran.Edit2()","edit_f2.png","Edit", 'Edit Penerimaan')."</td>".
			"<td>".genPanelIcon("javascript:pengeluaran.Hapus()","delete_f2.png","Hapus", 'Hapus Penerimaan')."</td>";
			/*
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'DPBMD Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit DPBMD')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus DPBMD')."</td>";
			*/
	}
	function setMenuView(){
		return //"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHit()","print_f2.png","Cetak", 'Cetak Nota Hitung')."</td>
					"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
					<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>					<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>";
					
	}	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40' rowspan=2 width='40'>No.</th>
				$Checkbox		
				<th class='th01' width='' rowspan=2 >Nama Barang</th>				
				<th class='th01'  width='50' rowspan=2>Tahun<br>Anggaran</th>
				
				
				
				<th class='th02'  width='' colspan=2>Surat Permohonan</th>
				<th class='th01'  width='50' rowspan=2>Tanggal Penyerahan</th>
				
				<th class='th02'  width='' colspan=3>Jumlah</th>
				<th class='th01'  width='' rowspan=2>Untuk</th>
				<th class='th01'  width='120' rowspan=2>Unit Kerja</th>
				<th class='th01'  width='100' rowspan=2>Keterangan </th>							
				</tr>
				
				
				<tr>
				<th class='th01' width='60'>Tanggal </th>				
				<th class='th01' width='80'>Nomor </th>				
				
				<th class='th01' width='80'>Banyaknya Barang </th>								
				<th class='th01' width='100'>Harga Satuan<br>(Rp) </th>				
				<th class='th01' width='150'>Jumlah Harga<br>(Rp) </th>
				
				
				
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
		/*$barang = mysql_fetch_array(mysql_query(
			"select * from ref_barang where concat(f,g,h,i,j)='".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."' "
		));
		*/
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['nm_barang']//$barang['nm_barang']
		);		
		
		$Koloms[] = array("align='center'", $isi['tahun']);
		
		//$Koloms[] = array("align=''", $isi['supplier']);
		
		$Koloms[] = array("align='center'",  TglInd($isi['sk_tgl'] ) );		
		$Koloms[] = array("align=''",  $isi['sk_no']  );
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_penyerahan']) );
		
		$Koloms[] = array("align='right'", number_format( $isi['jml_barang'] ,0,',','.') );
		$Koloms[] = array("align='right'", number_format( $isi['harga'] ,2,',','.' ));			
		$Koloms[] = array("align='right'", number_format( $isi['jml_harga'] ,2,',','.') );
		
		$Koloms[] = array("align=''",  $isi['untuk']  );
		$Koloms[] = array("align='' width='120'",  $nmopd  );
		$Koloms[] = array('', $isi['ket']);
		//$Koloms[] = array("align='center'",  $status);
		return $Koloms;
	}
	function setCekBox($cb, $KeyValueStr, $isi){
		return "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' stat='".$isi['stat']."'  onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		
		
		//data cari ----------------------------
		
		$arrCari = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
		
		 //get selectbox cari data
		 $fmPILCARI = cekPOST('fmPILCARI');
		 $fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		
		//get selectbox tahun anggaran
		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		//$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];	$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			//array('3','Kode Rekening'),		
		);
		
		  //get select Order1
		  $fmORDER1 = cekPOST('fmORDER1');	
		  $fmDESC1 = cekPOST('fmDESC1');
		  
		 //get select Order2
		  $fmORDER2 = cekPOST('fmORDER2');
		  $fmDESC2 = cekPOST('fmDESC2');
		
		//thn terakhir
		$get = mysql_fetch_array(mysql_query(
			"select max(tahun) as maxthn from $this->TblName "
		));
		//$fmFiltThnAnggaran = $get['maxthn'];
		if ( $Main->VERSI_NAME == 'JABAR') {
			$wilskpd=WilSKPD_ajx($this->Prefix.'Skpd');
		}else{
			$wilskpd=WilSKPD_ajx3($this->Prefix.'Skpd');
		}	
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">".
				//WilSKPD_ajx($this->Prefix) . 
				$wilskpd			 
			."</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".
			genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
			genFilterBar(
				array(	
					boxFilter( 'Tampilkan : '.	
					 genComboBoxQry(
						'fmFiltThnAnggaran',
						$fmFiltThnAnggaran,
						"select tahun from $this->TblName group by tahun desc ",
						'tahun', 
						'tahun',
						'Tahun Anggaran'
					)).
					//$Main->batas.
					boxFilter("<!--Kode Rekening &nbsp;&nbsp;<input type='text' value='' id='kode_rekening' name='kode_rekening'>-->")
					
				),$this->Prefix.".refreshList(true)",FALSE
			).
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC1 id='fmDESC1' name='fmDESC1' value='checked'>Desc.".
					cmbArray('fmORDER2',$fmORDER2,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC2 id='fmDESC2' name='fmDESC2' value='checked'>Desc.".
					//cmbArray('fmORDER3',$fmORDER3,$arrOrder,'--','').
					"<!--<input type='checkbox' id='fmDESC3' name='fmDESC3' value='checked'>Desc.-->"
				),				
				$this->Prefix.".refreshList(true)");
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		/*$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.'00';
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		*/
		//*
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
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
		//$arrKondisi[] = $tes;
		//*/
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){			
			case '1': $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " nm_barang like '%$fmPILCARIVALUE%'"; break;
		}
		$fmFiltThnAnggaran = cekPOST('fmFiltThnAnggaran');
		if(!empty($fmFiltThnAnggaran) ) {
			$arrKondisi[] = "tahun='$fmFiltThnAnggaran'";
		}else{
			//thn terakhir
		//$get = mysql_fetch_array(mysql_query(
		//	"select max(tahun) as maxthn from $this->TblName "
		//));
		//$fmFiltThnAnggaran = $get['maxthn'];
			
		}
		
		$kode_rekening  = cekPOST('kode_rekening');
		if(!empty($kode_rekening) ) $arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) like '%$kode_rekening%'";
		
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
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}
		switch($fmORDER2){
			case '1': $arrOrders[] = " tahun $Asc2 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc2 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc2 " ;break;
		}
		switch($fmORDER3){
			case '1': $arrOrders[] = " tahun $Asc3 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc3 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc3 " ;break;
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
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$dt['p'] = '';
		$dt['q'] = '';
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		//$this->form_idplh ='';
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
		
		/*setcookie('cofmSKPD', $old['c']);
		setcookie('cofmUNIT', $old['d']);
		setcookie('cofmSUBUNIT', $old['e']);
		*/
		
		//ambil penerimaan --------------------------------------
		$aqry = "select * from $this->TblName where Id='$this->form_idplh';"; $cek .= $aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//ambil data pengadaan ---------------------------------
		$aqry = "select * from penerimaan where id='".$dt['id_penerimaan']."' ; "; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query(
			$aqry
		));
		/*$dt['nama_perusahaan_dpb'] = $get['nama_perusahaan'];
		$dt['spk_tgl_dpb'] = $get['spk_tgl'];
		$dt['spk_no_dpb'] = $get['spk_no'];
		*/
		$dt['jmlterima'] = $get['jml_barang']==NULL || $get['jml_barang']==''?0 : $get['jml_barang'];
		
		//ambil penerimaan sebelumnya ----------------------------
		$aqry = "select sum(jml_barang) as tot from $this->TblName where id_penerimaan='".$dt['id_penerimaan']."' and id<>'".$this->form_idplh."' "; $cek .= $aqry;
		$get =  mysql_fetch_array( mysql_query(
			$aqry
		));
		
		$dt['jmlada'] = $get['tot'] == NULL || $get['tot'] == ''? 0: $get['tot'];
		
		//hit sisa ----------------------------------------------
		$dt['jmlsisa'] = $dt['jmlterima'] - $dt['jmlada'] - $dt['jml_barang'];			
		$dt['cek'].=$aqry;
				
		//echo sizeof($cbid).' '.$cbid[0] ;
		//print_r($cbid);
		//echo $cek;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']
		);
	}
	
	function setForm($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmNMBARANG,$fmIDREKENING,$Main;
		$cek = ''; $err=''; $content=''; 
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );			
		$json = TRUE;	//$ErrMsg = 'tes';
		
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
		$bidang = $get['nm_skpd'];//." <input tipe='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='".$dt['c']."'>";
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];//."<input tipe='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='".$dt['d']."'>";
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];
		$dkb = mysql_fetch_array(mysql_query("select * from dkb where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' and f='".$dt['f']."' and g='".$dt['g']."' and h='".$dt['h']."' and i='".$dt['i']."' and j='".$dt['j']."' and tahun='".$dt['tahun']."' "));
		
		$menu =
			"<table width='100%' class='menudottedline'>
			<tbody><tr><td>
				<table width='50'><tbody><tr>				
					<td>					
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:".$this->Prefix.".Simpan2()'> 
						<img src='images/administrator/images/save_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Simpan'> Simpan</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					<td>			
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='' href='javascript:window.close();'> 
						<img src='images/administrator/images/cancel_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Batal'> Batal</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					</tr>
				</tbody></table>
			</td></tr>
			</tbody></table>";
		
		$fmIDBARANG = $dt['f']==''? '':  $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;//'01.01.01.02.01';
		$fmIDREKENING = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'];//'5.1.1.01.05';
		//$fmTAHUN = $dt['tahun']==''?  '2013' : $dt['tahun'] ; //def tahun = 2013
		$fmTAHUN = $dt['tahun'] ;
		$fmNMBARANG = $dt['nm_barang'];
		
		$vtahun = //$this->form_fmST==1? $fmTAHUN :
			'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)" readonly>';
		
		$vkdbarang = //$this->form_fmST==1?	$fmIDBARANG.' - '.$dt['nm_barang'] :			
			cariPenerimaan("adminForm","pages/01/caripenerimaan1.php","pages/01/caripenerimaan2.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled",'',$dt['idbi'],$dt['c'],$dt['d'],$dt['e'],$dt['e1']);  
		
		
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			<span id='cnt_jmlharga'>".number_format($dt['jml_harga'],0,',','.')."</span>";
		
		$vGudang = 
			"<input type='text' id='nm_gudang' name='nm_gudang' value='".$dt['nm_gudang']."' style='width:430' readonly=''>".			
			"&nbsp&nbsp<input type='button' value='Pilih' onclick=\"penerimaan.pilihGudang()\">".
			"<input type='hidden' id='id_gudang' name='id_gudang' value='".$dt['id_gudang']."'>";		 
			
		
		$title = $this->form_fmST == 1? 'Pengeluaran Barang Milik Daerah - Edit' : 'Pengeluaran Barang Milik Daerah - Baru';
		$this->form_fields = array(	
			'title' => array('label'=>'','value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">'.$title.'</div>', 'type'=>'merge' ),			
			'bidang' => array( 'label'=>'Bidang', 
				'value'=>$bidang, 
				'type'=>'', 'row_params'=>"height='21'"
			),
			'unit' => array( 'label'=>'SKPD', 
				'value'=>$unit, 
				'type'=>'', 'row_params'=>"height='21'"
			),
			'subunit' => array( 'label'=>'UNIT', 
				'value'=>$subunit, 
				'type'=>'', 'row_params'=>"height='21'"
			),			
			'seksi' => array( 'label'=>'SUB UNIT', 
				'value'=>$seksi, 
				'type'=>'', 'row_params'=>"height='21'"
			),			
			'tahun' => array(  'label'=>'Tahun Anggaran', 
				'value'=> $vtahun
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),			
			
			'nm_barang' => array(  'label'=>'Nama Barang', 
				'value'=> $vkdbarang,//."<input type='hidden' id='iddkb' name='iddkb' value='".$isi['iddkb']."'>",
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
						
			'merk' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> "<textarea name=\"fmMEREK\" id=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 
				//'params'=>"valign='top'",
				'type'=>'' , 'row_params'=>"valign='top'"
			),
			
			/*
			'spk_dpb' => array('label'=>'', 'value'=>'SPK/Perjanjian/Kontrak', 'type'=>'merge','row_params'=>" height='21'"),
			'nama_perusahaan_dpb' => array('label'=>'&nbsp;&nbsp;PT/CV', 
				'value'=>$dt['nama_perusahaan_dpb'], 					
				'type'=>'text', 'param'=> "style='width:430px' readonly=''"
			),
			'spk_tgl_dpb' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> //createEntryTgl('spk_tgl',$dt['spk_tgl'] )
					"<div id='div_spk_tgl' name='div_spk_tgl'>".$vspk_tgl_dpb."</div>".
					"<input type='hidden' id='spk_tgl_dpb' name='spk_tgl' value='".$dt['spk_tgl_dpb']."'>".
					"<input type='hidden' id='id_pengadaan' name='id_pengadaan' value='".$dt['id_pengadaan']."'>"
				, 'type'=>''
			),
			'spk_no_dpb' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$dt['spk_no_dpb'], 'type'=>'text', 'param'=> " readonly=''"
			),
			*/
			'jmlterima' => array(  'label'=>'Jumlah Penerimaan', 
				'value'=> 
					"<input name=\"jmlterima\" id='jmlterima' type=\"text\" value='".$dt['jmlterima']."' readonly='true' />".
					"<input type='hidden' id='id_penerimaan' name='id_penerimaan' value='".$dt['id_penerimaan']."'>"
				,'type'=>'' 
			),	
			'jml_ada' => array(  'label'=>'Pengeluaran Sebelumnya', 
				'value'=> 
					"<input name=\"jml_ada\" id='jml_ada' type=\"text\" value='".$dt['jmlada']."' readonly='true' />"					
				,'type'=>'' 
			),
			'jml_barang' => array(  'label'=>'Jumlah Pengeluaran Barang', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' onblur='pengeluaran.hitungSisa()' />".
					" Satuan ".
					"<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			'jml_sisa' => array(  'label'=>'Sisa', 
				'value'=> 
					"<input name=\"jml_sisa\" id='jml_sisa' type=\"text\" value='".$dt['jmlsisa']."' readonly='true' />"					
				,'type'=>'' 
			),	
			
					
			'harga' => array( 'label'=>'Harga Satuan', 
				'value'=> 
				inputFormatRibuan("harga", '',$dt['harga']) ,
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=> $vjmlharga ,
				'type'=>'' 
			),			
			
			
			'tgl_penyerahan' => array('label'=>'Tanggal Pengeluaran', 
				'value'=> createEntryTgl('tgl_penyerahan',$dt['tgl_penyerahan'] )					
				, 'type'=>''
			),
			'untuk' => array('label'=>'Untuk', 
				'value'=>$dt['untuk']
				, 'type'=>'text'
			),
			/*'penerima' => array('label'=>'Petugas Penerima', 
				'value'=>
					"NIP <input type='text' id='nip_penerima' name='nip_penerima' value='".$dt['nip_penerima']."'> ".
					"&nbsp;Nama <input type='text' id='penerima' name='penerima' value='".$dt['penerima']."' style='width:248'> "
				, 'type'=>''
			),*/
			
			/*'id_gudang' => array('label'=>'Nama Gudang', 
				'value'=>$vGudang
				, 'type'=>''
			),
			'penyimpan' => array('label'=>'Petugas Penyimpan', 
				'value'=>
					"NIP <input type='text' id='nip_penyimpan' name='nip_penyimpan' value='".$dt['nip_penyimpan']."'> ".
					"&nbsp;Nama <input type='text' id='penyimpan' name='penyimpan' value='".$dt['penyimpan']."' style='width:248'> "
				, 'type'=>''
			),
			*/
			/*			
			'faktur' => array('label'=>'', 'value'=>'Dokumen Faktur', 'type'=>'merge','row_params'=>" height='21'"),
			'supplier' => array('label'=>'&nbsp;&nbsp;PT/CV', 
				'value'=>$dt['supplier'], 					
				'type'=>'text', 'param'=> "style='width:430px'"
			),
			'faktur_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('faktur_tgl',$dt['faktur_tgl'] )					
				, 'type'=>''
			),
			'faktur_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$dt['faktur_no'], 'type'=>'text', 'param'=> ""
			),
			
			*/
			'sk' => array('label'=>'', 'value'=>'Surat Perintah Pengeluaran Barang', 'type'=>'merge','row_params'=>" height='21'"),
			'sk_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('sk_tgl',$dt['sk_tgl'] ), 'type'=>''
			),
			'sk_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$dt['sk_no'], 'type'=>'text'
			),
			/*'pemeriksa' => array('label'=>'Petugas Pemeriksa', 
				'value'=>
					"NIP <input type='text' id='nip_pemeriksa' name='nip_pemeriksa' value='".$dt['nip_pemeriksa']."'> ".
					"&nbsp;Nama <input type='text' id='pemeriksa' name='pemeriksa' value='".$dt['pemeriksa']."' style='width:248'> "
				, 'type'=>''
			),
			*/
			
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>				
				"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'menu'=> array( 'label'=>'', 
				'value'=>
				"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
				//"<input type=hidden id='cek' name='cek' value='".$dt['cek']."'> ".
				$menu,
				'type'=>'merge'
			)
		);
		
				
		//tombol
		$this->form_menubawah = ''
			/*"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> "
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			//"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >"
			*/
			;
		
		$this->genForm_nodialog();
		//$form = $this->genForm(FALSE);		
				
		//$content = $form;//$content = 'content';
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	/*
	function setForm($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmIDREKENING;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
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
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' "));
		$subunit = $get['nm_skpd'];		
		
		$menu =
			"<table width='100%' class='menudottedline'>
			<tbody><tr><td>
				<table width='50'><tbody><tr>				
					<td>					
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:".$this->Prefix.".Simpan2()'> 
						<img src='images/administrator/images/save_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Simpan'> Simpan</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					<td>			
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='' href='javascript:window.close();'> 
						<img src='images/administrator/images/cancel_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Batal'> Batal</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					</tr>
				</tbody></table>
			</td></tr>
			</tbody></table>";
		
		$fmIDBARANG = $dt['f']==''? '':  $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;//'01.01.01.02.01';
		$fmIDREKENING = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'];//'5.1.1.01.05';
		$fmTAHUN = $dt['tahun']==''?  '2013' : $dt['tahun'] ; //def tahun = 2012
		
		
		$vtahun = //$this->form_fmST==1? $fmTAHUN :
			'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)">';
		
		$vkdbarang = //$this->form_fmST==1?	$fmIDBARANG.' - '.$dt['nm_barang'] :			
			cariDKB("adminForm","pages/01/caridkb1.php","pages/01/caridkb2.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled",'',$dt['idbi'],$dt['c'],$dt['d'],$dt['e']);  
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			<span id='cnt_jmlharga'>".number_format($dt['jml_harga'],0,',','.')."</span>";
		
		$title = $this->form_fmST == 1? 'Daftar Pengadaan Barang Milik Daerah - Edit' : 'Daftar Pengadaan Barang Milik Daerah - Baru';
		$this->form_fields = array(	
			'title' => array('label'=>'','value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">'.$title.'</div>', 'type'=>'merge' ),			
			'bidang' => array( 'label'=>'Bidang', 
				'value'=>$bidang, 
				'type'=>'', 'row_params'=>"height='21'"
			),
			'unit' => array( 'label'=>'ASISTEN / OPD', 
				'value'=>$unit, 
				'type'=>'', 'row_params'=>"height='21'"
			),
			'subunit' => array( 'label'=>'BIRO / UPTD/B', 
				'value'=>$subunit, 
				'type'=>'', 'row_params'=>"height='21'"
			),			
						
			'tahun' => array(  'label'=>'Tahun Anggaran', 
				'value'=> $vtahun
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'nm_barang' => array(  'label'=>'Nama Barang', 
				'value'=> $vkdbarang,
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),			
			'merk' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> "<textarea name=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 
				//'params'=>"valign='top'",
				'type'=>'' , 'row_params'=>"valign='top'"
			),	
			'jml_barang' => array(  'label'=>'Jumlah Barang', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' />".
					" Satuan ".
					"<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
					
			'harga' => array( 'label'=>'Harga Satuan', 
				'value'=>//"<input name=\"fmJUMLAH\" type=\"text\" value='".$dt['harga']."' />", 
				inputFormatRibuan("harga", '',$dt['harga']) ,
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=> $vjmlharga ,
				'type'=>'' 
			),
			'spk' => array('label'=>'', 'value'=>'SPK/Perjanjian/Kontrak', 'type'=>'merge','row_params'=>" height='21'"),
			'spk_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('spk_tgl',$isi['spk_tgl'] ), 'type'=>''
			),
			'spk_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$isi['spk_no'], 'type'=>'text'
			),
			'dpa' => array('label'=>'', 'value'=>'DPA/SPM/Kwitansi', 'type'=>'merge','row_params'=>" height='21'"),
			'dpa_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('dpa_tgl',$isi['dpa_tgl'] ), 'type'=>''
			),
			'dpa_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$isi['dpa_no'], 'type'=>'text'
			),
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'menu'=> array( 'label'=>'', 
				'value'=>
				"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				$menu,
				'type'=>'merge'
			)
		);
		
				
		//tombol
		$this->form_menubawah = ''
			
			;
		
		$this->genForm_nodialog();
	}
	*/
	
	
	function simpan2(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$jml_barang = $_REQUEST['jml_barang'];
		$harga = $_REQUEST['harga'];
		$satuan = $_REQUEST['satuan'];
		
		$a = $Main->DEF_PROPINSI; //$_REQUEST[''];
		$b = $Main->DEF_WILAYAH; //$_REQUEST['merk_barang'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];		
		$f = $fmIDBARANG['0'].$fmIDBARANG['1'];
		$g = $fmIDBARANG['3'].$fmIDBARANG['4'];
		$h = $fmIDBARANG['6'].$fmIDBARANG['7'];
		$i = $fmIDBARANG['9'].$fmIDBARANG['10'];
		$j = $fmIDBARANG['12'].$fmIDBARANG['13'].$fmIDBARANG['14'];
		
		$tahun = $_REQUEST['fmTAHUN'];
		$id_penerimaan = $_REQUEST['id_penerimaan'];
		
		//id_pengadaan,a,b,c,d,e,id_gudang,tgl_penerimaan,penerima,merk_barang,supplier,faktur_tgl,faktur_no,f,g,h,i,j,jml_barang,harga,satuan,jml_harga,ba_no,ba_tgl,ket,jenis_barang,tahun
		
		if($err=='' && ($jml_barang == '' || $jml_barang==0))$err = "Jumlah Barang belum diisi!";
		if($err=='' && ($harga == '' || $harga==0))$err = "Harga Satuan belum diisi!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
		
		//-- get jumlah daftar pengadaan
		$get = mysql_fetch_array( mysql_query(
			"select count(*)as cnt, sum(jml_barang) as tot from penerimaan where id='$id_penerimaan'"
		));	
		$jmldpb = $get['tot']; 
				
		
		//-- cek kebutuhan ada
		if( $err=='' && ($get['cnt'] == NULL || $get['cnt']=='' )  ) 
			$err = "Penerimaan Barang $fmIDBARANG untuk Tahun Anggaran $tahun Tidak Ada!";
		
		//-- get penerimaan sblumnya utk idpengadaan, selain ini
		$get =  mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as tot from $this->TblName where id_penerimaan='".$id_penerimaan."' and id<>'$id' "
		));
		$jmlada = $get['tot'];
		
		//-- cek sisa
		if($err==''){
			$jmlsisa = $jmldpb - $jmlada - $jml_barang;			
			if($jmlsisa<0) $err="Gagal Simpan, Jumlah Pengeluaran lebih besar dari Penerimaan!";
		}
		
		
		//$jenis_barang = 2 ;//inventaris , 1= habis pakai
		$jml_harga = $_REQUEST['jml_barang'] * $_REQUEST['harga'];
		
		if($err==''){
			$get = $this->simpanData(
				$fmST, $this->TblName_Edit,
				array('id'=>"'".$id."'"),
				array(
					'id_penerimaan'=>"'".$_REQUEST['id_penerimaan']."'",
					'a'=>"'".$a."'",
					'b'=>"'".$b."'",
					'c'=>"'".$c."'",
					'd'=>"'".$d."'",
					'e'=>"'".$e."'",
					'e1'=>"'".$e1."'",
					//'id_gudang'=>"'".$_REQUEST['id_gudang']."'",
					'tgl_penyerahan'=>"'".$_REQUEST['tgl_penyerahan']."'",
					//'nip_penerima'=>"'".$_REQUEST['nip_penerima']."'",'penerima'=>"'".$_REQUEST['penerima']."'",
					
					//'nip_pemeriksa'=>"'".$_REQUEST['nip_pemeriksa']."'",'pemeriksa'=>"'".$_REQUEST['pemeriksa']."'",
					
					//'nip_penyimpan'=>"'".$_REQUEST['nip_penyimpan']."'",'penyimpan'=>"'".$_REQUEST['penyimpan']."'",
					
					'merk_barang'=>"'".$_REQUEST['fmMEREK']."'",
					'untuk'=>"'".$_REQUEST['untuk']."'",
					'sk_tgl'=>"'".$_REQUEST['sk_tgl']."'",
					'sk_no'=>"'".$_REQUEST['sk_no']."'",
					'f'=>"'".$f."'",
					'g'=>"'".$g."'",
					'h'=>"'".$h."'",
					'i'=>"'".$i."'",
					'j'=>"'".$j."'",
					'jml_barang'=>"'".$_REQUEST['jml_barang']."'",
					'harga'=>"'".$_REQUEST['harga']."'",
					'satuan'=>"'".$_REQUEST['satuan']."'",
					'jml_harga'=>"'".$jml_harga."'",
					//'sk_no'=>"'".$_REQUEST['sk_no']."'",	'sk_tgl'=>"'".$_REQUEST['sk_tgl']."'",
					'ket'=>"'".$_REQUEST['fmKET']."'",
					//'jenis_barang'=>"'".$jenis_barang."'",
					'tahun'=>"'".$_REQUEST['fmTAHUN']."'",
					'tgl_update'=>"now()",
					'uid'=>"'".$UID."'"
					
				)				
			);
			$cek .= $get['cek'];
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}
	
	function simpan2_(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$merk_barang = $_REQUEST['fmMEREK'];
		$jml_barang = $_REQUEST['jml_barang'];
		$harga = $_REQUEST['harga'];		
		$jml_harga = $_REQUEST['jml_harga'];
		$satuan = $_REQUEST['satuan'];
		$ket = $_REQUEST['fmKET'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$a = $Main->DEF_PROPINSI; //$_REQUEST[''];
		$b = $Main->DEF_WILAYAH; //$_REQUEST['merk_barang'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$fmIDREKENING = $_REQUEST['fmIDREKENING'];
		$f = $fmIDBARANG['0'].$fmIDBARANG['1'];
		$g = $fmIDBARANG['3'].$fmIDBARANG['4'];
		$h = $fmIDBARANG['6'].$fmIDBARANG['7'];
		$i = $fmIDBARANG['9'].$fmIDBARANG['10'];
		$j = $fmIDBARANG['12'].$fmIDBARANG['13'].$fmIDBARANG['14'];
		
		$k = $fmIDREKENING['0'];//.$fmIDREKENING['1'];
		$l = $fmIDREKENING['2'];//.$fmIDREKENING['3'];
		$m = $fmIDREKENING['4'];//.$fmIDREKENING['5'];
		$n = $fmIDREKENING['6'].$fmIDREKENING['7'];
		$o = $fmIDREKENING['9'].$fmIDREKENING['10'];
		
		$tahun = $_REQUEST['fmTAHUN'];
		$jml_harga  = $harga * $jml_barang;
		//satuan='$satuan',
		
		
		if($err=='' && $jml_barang == '')$err = "Jumlah Barang belum diisi!";
		if($err=='' && $harga == '')$err = "Harga Satuan belum diisi!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
		
		
		
		if($fmST==0){ //baru
		
			if($err=='' && $fmIDBARANG == '')$err = "Kode Barang belum diisi!";
			if($err=='' && $fmIDREKENING == '')$err = "Kode Rekening belum diisi!";
			if($err=='' && $tahun == '')$err = "Tahun belum diisi!";
			
			//cek rkb u brg ini sudah ada
			$get = mysql_fetch_array( mysql_query(
				"select count(*) as cnt from rkb where c='$c' and d='$d' and e='$e'  and e1='$e1' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and tahun='$tahun'"				
			));
			if($err=='' && $get['cnt']>1) $err="RKB dengan kode barang ini sudah ada!";
			
			//get jml bi
			$get = mysql_fetch_array( mysql_query(
				"select count(*) as cnt from buku_induk where c='$c' and d='$d' and e='$e'  and e1='$e1' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<='$tahun'"				
			));
			$jmlbi = $get['cnt'];
			//get jml standar
			$get = mysql_fetch_array( mysql_query(
				"select jml_barang from rkb_standar where c='$c' and d='$d' and e='$e'  and e1='$e1' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' "				
			));
			$jml_standar=$get['jml_barang'];
			//cek jml bi standar
			//if($err=='' && $jml_standar < $jmlbi+$jml_barang ) $err = "Jumlah RKB dan Jumlah BI tidak lebih besar dari Jumlah Standar!";
			
		}else{
			$old = mysql_fetch_array( mysql_query(
				"select * from rkb where id='$id' "
			));
			
			if($err =='' && $old['stat']=='1') $err = "Gagal Simpan, RKB sudah DKB!";
			//*
			$cek .= $old['tahun']." == $tahun && ".$old['f']."==$f && ".$old['g']."==$g && ".$old['h']."==$h && ".$old['i']."==$i && ".$old['j']."==$j";
			if(!($old['tahun'] == $tahun && $old['f']==$f && $old['g']==$g && $old['h']==$h && $old['i']==$i && $old['j']==$j) ){
				//cek rkb u brg ini sudah ada
				$aqry = "select count(*) as cnt from rkb where  ".
					" c='$c' and d='$d' and e='$e'  and e1='$e1' ".
					"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and tahun='$tahun'"	; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query(
					$aqry	
				));
				if($err=='' && $get['cnt']>0) $err="RKB dengan kode barang ini sudah ada!";					
			}
			//*/
			//cek jml bi standar
			//if($err=='' && $old['jml_standar'] < $old['jml_bi']+$jml_barang ) $err = "Jumlah RKB dan Jumlah BI tidak lebih besar dari Jumlah Standar!";
			//$cek .= "jmlstandar=".$old['jml_standar'].", jmlbi=".$old['jmlbi'].", jml brg=".$jml_barang;
			
			
		}
		
		
		
		
		if($err==''){
			if($fmST == 0){//baru
				$aqry = 
					"insert into $this->TblName_Edit 
					(merk_barang,jml_barang,jml_bi,jml_standar, harga,jml_harga,satuan,ket,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,tahun,
					uid,tgl_update) 
					values 
					('$merk_barang','$jml_barang', '$jmlbi', '$jml_standar' , '$harga','$jml_harga','$satuan','$ket',
					'$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$tahun',
					'$UID',now())";
				
			}else{
				//a='$a',	b='$b',	c='$c', d='$d',	e='$e',
				//f='$f', g='$g', h='$h',	i='$i',	j='$j',
				//tahun='$tahun'
				
				$get = $this->getJmlBrgBI_($old['c'],$old['d'],$old['e'],$old['e1'],$old['f'],$old['g'],$old['h'],$old['i'],$old['j'],$old['tahun']);
		
				$aqry = 
					"update $this->TblName_Edit  ".
					"set merk_barang='$merk_barang', ".
					"jml_barang='$jml_barang', ".
					"harga='$harga', ".
					"jml_harga='$jml_harga', ".
					"satuan ='$satuan', ".
					"jml_bi='".$get->content->jmlbi."', ".
					"jml_standar='".$get->content->jmlstandar."', ".
					"ket='$ket', ".
					"tahun='$tahun',".
					"f='$f', g='$g',	h='$h',	i='$i',	j='$j', ".
					"k='$k', l='$l',	m='$m',	n='$n',	o='$o', ".
					"uid='$UID', tgl_update= now() ".
					"where id='$id' ";
				
			}
			$cek .= $aqry;
			$qry = mysql_query($aqry);
		}		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function Hapus_Validasi($id){
		$err ='';
		//$KeyValue = explode(' ',$id);
		$old = mysql_fetch_array(mysql_query(
			"select * from $this->TblName where id ='$id' "
		));
		if($err=='' && $old['stat']=='1') $err = 'Data Tidak Bisa Dihapus, RKB sudah DKB!';
		
		return $err;
	}
	
	function getJmlBrgBI_($c,$d,$e,$e1,$f,$g,$h,$i,$j,$tahun){ //& standar
		global $Main;
		$cek = ''; $err=''; $content=''; 
						
		$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e'  and e1='$e1'
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<'$tahun'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlbi = $isi['cnt'];
		
		$aqry = "select jml_barang from rkb_standar where 						
			c='".$c."' and d='$d' and e='$e'  and e1='$e1'
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' "; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));		
		
		$content->jmlstandar = $isi['jml_barang'];
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
		$ret->cek = $cek; 
		$ret->err = $err;
		$ret->content = $content;
		return $ret;
	}	
	
	
	function getJmlBrgBI(){ //& standar
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$kdbrg = $_REQUEST['fmIDBARANG'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$tahun = $_REQUEST['fmTAHUN'];
		$kd = explode('.',$kdbrg);
		$f = $kd[0];
		$g = $kd[1];
		$h = $kd[2];
		$i = $kd[3];
		$j = $kd[4];
				
		/*$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<'$tahun'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlbi = $isi['cnt'];
		
		$aqry = "select jml_barang from rkb_standar where 						
			c='".$c."' and d='$d' and e='$e' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' "; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));		
		$content->jmlstandar = $isi['jml_barang'];
		*/
		
		$get = $this->getJmlBrgBI_($c,$d,$e,$e1,$f,$g,$h,$i,$j,$tahun);
		
		
		return	array ('cek'=>$get->cek, 'err'=>$get->err, 'content'=>$get->content);					
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
	
	function getJmlPenerimaan($idpenerimaan){
		$aqry = "select * from penerimaan where id='$idpenerimaan'";
		$get =  mysql_fetch_array(mysql_query($aqry));
		return $get['jml_barang'];
	}
	function getPenerimaan($idpenerimaan){
		$aqry = "select * from penerimaan where id='$idpenerimaan'";
		$get =  mysql_fetch_array(mysql_query($aqry));
		return $get;
	}
	function getPengeluaranSebelumnya($idpenerimaan){
		$idstr = $id==''? '' : " and id<>'$id' ";
		$aqry = "select sum(jml_barang) as tot from pengeluaran where ".
				" id_penerimaan='$idpenerimaan' "; $cek.=$aqry;
		$get = mysql_fetch_array( mysql_query(
			$aqry
		));	
		return $get['tot'];
	}	
	function hitPengeluaranSebelumnya(){
		$cek=''; $err=''; $content='';
		
		$idpenerimaan = $_REQUEST['idpenerimaan'];
				
		$content->jmlada = $this->getPengeluaranSebelumnya( $idpenerimaan );		
		$content->jmlterima = $this->getJmlPenerimaan( $idpenerimaan ); 
		if($content->jmlada == NULL) $content->jmlada=0;
		if($content->jmlterima == NULL) $content->jmlterima =0;
		
		$content->jml = $content->jmlterima - $content->jmlada;
		$content->jmlsisa = 0;
		
		//ambil data penerimaan
		$get = $this->getPenerimaan($idpenerimaan);
		
		
		$content->tampilspktgl = TglInd($get['spk_tgl']);
		$content->spk_tgl = $get['spk_tgl'];
		$content->spk_no = $get['spk_no'];
		//$content->nama_perusahaan = $get['nama_perusahaan'] ;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}
			
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru2':{				
				//echo 'tes';
				$this->setFormBaru();				
				//$cek = $fm['cek'];
				//$err = $fm['err'];
				//$content = $fm['content'];
				$json = FALSE;				
				break;
			}
			case 'formEdit2':{								
				$this->setFormEdit();				
				$json = FALSE;				
				break;
			}
			case 'simpan2' : {
				$get = $this->simpan2();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'getJmlBrgBI':{
				$get = $this->getJmlBrgBI();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			
			case 'hitPengeluaranSebelumnya': {
				$get = $this->hitPengeluaranSebelumnya();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}		
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$pengeluaran = new PengeluaranObj();

?>