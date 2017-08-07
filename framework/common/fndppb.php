<?php

class DppbObj extends DaftarObj2{
	var $Prefix = 'dppb';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'v1_pengadaan_pemeliharaan';//'v1_rkb'
	var $TblName_Hapus = 'pengadaan_pemeliharaan';
	var $TblName_Edit = 'pengadaan_pemeliharaan';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 13, 12, 12);
	var $FieldSum_Cp2 = array( 3, 3, 3);	
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Pengadaan';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='dppb.xls';
	var $Cetak_Judul = 'DAFTAR PENGADAAN PEMELIHARAAN BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '20cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	//var $row_params= " valign='top'";
	
	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	function setTitle(){
		return 'Daftar Pengadaan Pemeliharaan Barang Milik Daerah (DPPBMD)';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=dpb" title="Daftar Pengadaan Barang Milik Daerah">DPBMD</a>  |  
				<a href="pages.php?Pg=dppb" title="Daftar Pengadaan Pemeliharaan Barang Milik Daerah">DPPBMD</a>  |
				
				<a href="pages.php?Pg=rekapdpb" title="Rekap Daftar Pengadaan Barang Milik Daerah">Rekap DPBMD</a>  |  
				<a href="pages.php?Pg=rekapdppb" title="Rekap Daftar Pengadaan Pemeliharaan Barang Milik Daerah">Rekap DPPBMD</a>  
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'DPPBMD Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit DPPBMD')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus DPPBMD')."</td>";
			
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40' rowspan=2 width='40'>No.</th>
				$Checkbox		
				<th class='th01' width='' rowspan=2 width=''>Nama Barang</th>
				<th class='th01' rowspan=2 width='50'>No. Register</th>
				<th class='th01' rowspan=2 width='50'>Tahun<br>Perolehan</th>
				<th class='th01' rowspan=2 width='50'>Tahun<br>Anggaran</th>
				<th class='th02' colspan=3>SPK/ Perjanjian/ Kontrak </th>
				<th class='th02' colspan=2>SP2D/ Kwitansi</th>				
				<th class='th02' colspan=3>Jumlah</th>
				<th class='th01' rowspan=2 width='150'>Dipergunakan Pada Unit</th>
				<th class='th01' rowspan=2 width='150'>Pejabat Pengadaan Barang/<br>Pejabat Pembuat Komitmen</th>
				
				<th class='th01' rowspan=2 width='100'>Keterangan </th>							
				</tr>
				<tr>
				<th class='th01' width='100'>PT/CV </th>				
				<th class='th01' width='60'>Tanggal </th>				
				<th class='th01' width='80'>Nomor </th>				
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
		
		$vpejabat = 
			$isi['nip_pejabat_pengadaan']." - ".$isi['nama_pejabat_pengadaan']." /<br>" .
			$isi['nip_pembuat_komitmen']." - ".$isi['nama_pembuat_komitmen'];
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['nm_barang']//$barang['nm_barang']
		);		
		//$Koloms[] = array('', $isi['merk_barang'] );
		$Koloms[] = array("align='center'", $isi['noreg']);
		$Koloms[] = array("align='center'", $isi['thn_perolehan']);
		$Koloms[] = array("align='center'", $isi['tahun']);
		$Koloms[] = array("align='left'", $isi['pt']);
		$Koloms[] = array("align='center'",  TglInd($isi['spk_tgl'] ) );		
		$Koloms[] = array("align=''",  $isi['spk_no']  );
		$Koloms[] = array("align='center'",  TglInd($isi['dpa_tgl'] ) );		
		$Koloms[] = array("align=''",  $isi['dpa_no']  );
		
		$Koloms[] = array("align='right'", number_format( $isi['jml_barang'] ,0,',','.') );
		$Koloms[] = array("align='right'", number_format( $isi['harga'] ,2,',','.' ));			
		$Koloms[] = array("align='right'", number_format( $isi['jml_harga'] ,2,',','.') );
		/*$Koloms[] = array('', $isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'<br>'.
			$isi['nm_rekening']
		);*/
		$Koloms[] = array(' width=150 ', $nmopd);
		$Koloms[] = array(' width= ', $vpejabat);
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
		
		 //get select cari data: kode barang,nama barang
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');	
		
		 //get select Tahun Anggaran
		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		if ($fmFiltThnAnggaran!='' && $fmFiltThnAnggaran!=$_COOKIE['coTahunAnggaran']){
			setcookie("coTahunAnggaran",$fmFiltThnAnggaran);
		} else {
			$fmFiltThnAnggaran=$_COOKIE['coTahunAnggaran'];
		}
		$kode_rekening = $_REQUEST['kode_rekening'];
		//$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];	$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			array('3','Kode Rekening'),		
		);
		
		  //get Select Order1
		  $fmORDER1 = cekPOST('fmORDER1');
		  $fmDESC1 = cekPOST('fmDESC1');
		  
		  //get Select Order2
		  $fmORDER2 = cekPOST('fmORDER2');
		  $fmDESC2 = cekPOST('fmDESC2');
		  
		  //get Select Order3
		  $fmORDER3 = cekPOST('fmORDER3');
		  $fmDESC3 = cekPOST('fmDESC3');
		  
		//thn terakhir
		$get = mysql_fetch_array(mysql_query(
			"select max(tahun) as maxthn from $this->TblName "
		));
		//$fmFiltThnAnggaran = $get['maxthn'];
			
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
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
					$Main->batas.
					boxFilter("Kode Rekening &nbsp;&nbsp;<input type='text' value='$kode_rekening' id='kode_rekening' name='kode_rekening' >")
					
				),$this->Prefix.".refreshList(true)",FALSE
			).
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC1 id='fmDESC1' name='fmDESC1' value='checked'>Desc.".
					cmbArray('fmORDER2',$fmORDER2,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC2 id='fmDESC2' name='fmDESC2' value='checked'>Desc.".
					cmbArray('fmORDER3',$fmORDER3,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC3 id='fmDESC3' name='fmDESC3' value='checked'>Desc."
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
	
	function getJmlDKPB($tahun, $c, $d, $e,$e1, $f,$g,$h,$i,$j){
		$aqry = "select * from dkpb where tahun='$tahun' and c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ";
		$qry =  mysql_query($aqry);
		$get =  mysql_fetch_array($qry);
		$hsl=0;
		if ($qry) $hsl = $get['jml_barang'];
		return $hsl;
	}
	function getPengadaanSebelumnya($tahun, $c, $d, $e ,$e1 , $f, $g, $h, $i, $j, $id=''){
		$idstr = $id==''? '' : " and id<>'$id' ";
		$aqry = "select sum(jml_barang) as tot from pengadaan_pemeliharaan where ".
				"tahun='$tahun'".
				" and c='$c' and d='$d' and e='$e'  and e1='$e1' ".
				" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' $idstr "; $cek.=$aqry;
		$qry = mysql_query(	$aqry);
		$get = mysql_fetch_array( $qry);	
		$hsl = 0;
		if($qry) $hsl = $get['tot'];
		//if($hsl=='')$hsl = 0;
		return $hsl;
	}
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		
		$dt['jmldkb']=0;
		$dt['jmlada'] =0;
		$dt['jml_barang'] =0;
		$dt['jmlsisa'] =0;
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
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$dt['jmldkb'] = $this->getJmlDKPB($dt['tahun'], $dt['c'], $dt['d'], $dt['e'],$dt['e1'], $dt['f'], $dt['g'], $dt['h'], $dt['i'], $dt['j']);
		$dt['jmlada'] = $this->getPengadaanSebelumnya($dt['tahun'], $dt['c'], $dt['d'], $dt['e'],$dt['e1'], $dt['f'], $dt['g'], $dt['h'], $dt['i'], $dt['j'], $this->form_idplh);
		$dt['jmlsisa'] = $dt['jmldkb'] - ( $dt['jml_barang'] + $dt['jmlada']);
		//echo sizeof($cbid).' '.$cbid[0] ;
		//print_r($cbid);
		
		//-- get data pegawai
		$pgw = mysql_fetch_array(mysql_query("select * from ref_pegawai where nip ='".$dt['nip_pembuat_komitmen']."'"));
		$dt['nip_pembuat_komitmen'] = $pgw['nip'];
		$dt['nama_pembuat_komitmen'] = $pgw['nama'];
		$dt['jbt_pembuat_komitmen'] = $pgw['jabatan'];
		$pgw = mysql_fetch_array(mysql_query("select * from ref_pegawai where nip ='".$dt['nip_pejabat_pengadaan']."'"));
		$dt['nip_pejabat_pengadaan'] = $pgw['nip'];
		$dt['nama_pejabat_pengadaan'] = $pgw['nama'];
		$dt['jbt_pejabat_pengadaan'] = $pgw['jabatan'];
		
		
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']);
	}
	
	
	
	function setForm($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmNMBARANG,$fmIDREKENING,$Main;
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
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );		
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
			'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)" >';
		
		$vkdbarang = //$this->form_fmST==1?	$fmIDBARANG.' - '.$dt['nm_barang'] :			
			cariDKPB("adminForm","pages/01/caridkpb1.php","pages/01/caridkpb2.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled",'',$dt['idbi'],$dt['c'],$dt['d'],$dt['e'],$dt['e1']);  
		
		
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			<span id='cnt_jmlharga'>".number_format($dt['jml_harga'],0,',','.')."</span>";
				 
			
		
		$title = $this->form_fmST == 1? 'Daftar Pengadaan Pemeliharaan Barang Milik Daerah - Edit' : 'Daftar Pengadaan Pemeliharaan Barang Milik Daerah - Baru';
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
			'thn_perolehan' => array(  'label'=>'Tahun Perolehan', 
				'value'=> //$dt['thn_perolehan']
					"<input name='thn_perolehan' id='thn_perolehan' type='text' value='".$dt['thn_perolehan']."' readonly='true' />"					
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),	
			'noreg' => array(  'label'=>'No. Register', 
				'value'=> //$dt['noreg']
					"<input name='noreg' id='noreg' type='text' value='".$dt['noreg']."' readonly='true' />"					
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),	
			
			/*
			'merk' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> "<textarea name=\"fmMEREK\" id=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 
				
				'type'=>'' , 'row_params'=>"valign='top'"
			),*/
			'jml_dkb' => array(  'label'=>'Jumlah Kebutuhan', 
				'value'=> 
					"<input name=\"jml_dkb\" id='jml_dkb' type=\"text\" value='".$dt['jmldkb']."' readonly='true' />"					
				,'type'=>'' 
			),	
			'jml_ada' => array(  'label'=>'Pengadaan Sebelumnya', 
				'value'=> 
					"<input name=\"jml_ada\" id='jml_ada' type=\"text\" value='".$dt['jmlada']."' readonly='true' />"					
				,'type'=>'' 
			),				
			'jml_barang' => array(  'label'=>'Jumlah Pengadaan Barang', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' onblur='dppb.hitungSisa()' />".
					" Satuan ".
					"<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			'jml_sisa' => array(  'label'=>'Sisa Kebutuhan', 
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
			
			'spk' => array('label'=>'', 'value'=>'SPK/Perjanjian/Kontrak', 'type'=>'merge','row_params'=>" height='21'"),
			'spk_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('spk_tgl',$dt['spk_tgl'] ), 'type'=>''
			),
			'spk_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$dt['spk_no'], 'type'=>'text'
			),
			'pt' => array('label'=>'&nbsp;&nbsp;PT/CV', 
				'value'=>$dt['pt'], 'type'=>'text', 'param'=> "style='width:430px'"
			),
			'dpa' => array('label'=>'', 'value'=>'SP2D/Kwitansi', 'type'=>'merge','row_params'=>" height='21'"),
			'dpa_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('dpa_tgl',$dt['dpa_tgl'] ), 'type'=>''
			),
			'dpa_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$dt['dpa_no'], 'type'=>'text'
			),
			
			'pejabat_pengadaan' => array(  
				'label'=>'Pejabat Pengadaan', 
				'value'=> 
					"<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpengadaan']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$dt['nama_pejabat_pengadaan']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$dt['nip_pejabat_pengadaan']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
				,
				'type'=>'' 
			), 	
			'jbt1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$dt['jbt_pejabat_pengadaan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'pembuat_komitmen' => array(  
				'label'=>'Pembuat Komitment', 
				'value'=> 
					"<input type='hidden' id='ref_idkomitmen' name='ref_idkomitmen' value='".$dt['ref_idkomitmen']."'> ".
					"<input type='text' id='nama_pembuat_komitmen' name='nama_pembuat_komitmen' readonly=true value='".$dt['nama_pembuat_komitmen']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pembuat_komitmen' name='nip_pembuat_komitmen' readonly=true value='".$dt['nip_pembuat_komitmen']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPembuatKomitmen()\">"
				,
				'type'=>'' 
			),
			'jbt2' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pembuat_komitmen' name='jbt_pembuat_komitmen' readonly=true value='".$dt['jbt_pembuat_komitmen']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),	 	
			/*'pejabat_pengadaan' => array('label'=>'Pejabat Pengadaan', 
				'value'=>
					"NIP <input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' value='".$dt['nip_pejabat_pengadaan']."'> ".
					"&nbsp;Nama <input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' value='".$dt['nama_pejabat_pengadaan']."' style='width:248'> "
				, 'type'=>''
			),
			'pembuat_komitmen' => array('label'=>'Pembuat Komitment', 
				'value'=>
					"NIP <input type='text' id='nip_pembuat_komitmen' name='nip_pembuat_komitmen' value='".$dt['nip_pembuat_komitmen']."'> ".
					"&nbsp;Nama <input type='text' id='nama_pembuat_komitmen' name='nama_pembuat_komitmen' value='".$dt['nama_pembuat_komitmen']."' style='width:248'> "
				, 'type'=>''
			),
			*/
			
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'menu'=> array( 'label'=>'', 
				'value'=>
				"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
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
	
	
	function setFormBaru_(){
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
	
	function setFormEdit_(){		
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
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//echo sizeof($cbid).' '.$cbid[0] ;
		//print_r($cbid);
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']
		);
	}
	
	
	
	function setForm_($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmIDREKENING,$Main;
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
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];			
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
			cariDKPB("adminForm","pages/01/caridkpb1.php","pages/01/caridkpb2.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled",'',$dt['idbi'],$dt['c'],$dt['d'],$dt['e'],$dt['e1']);  
		
		/*$vjmlbi = //$this->form_fmST==1?	$dt['jml_bi'] :
			'<input type="text"  readonly="true" id="jmlbi" name="jmlbi" value="'.$dt['jml_bi'].'" >'
					."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkb.cekJmlBrgBI()' title='Cek Jumlah Inventaris (Tahun Sebelumnya) dan Jumlah Standar'>";
		
		$vstandar = //$this->form_fmST==1?	number_format($dt['jml_standar'],0,',','.') :
			'<input type="text"  readonly="true" id="standar" name="standar" value="'.$dt['jml_standar'].'" >';
		*/	
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
				'value'=> $vkdbarang,
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			/*'jmlbi' => array(  'label'=>'Jumlah Inventaris', 
				'value'=> $vjmlbi				
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'standar' => array(  'label'=>'Jumlah Standar', 
				'value'=> $vstandar
					//."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkb.getStandar()' title='Cek Jumlah Standar'>"
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),*/
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
			
			/*'kode_rekening' => array( 'label'=>'Kode Rekening', 
				'value'=>
					cariInfo("adminForm","pages/01/carirekening1.php","pages/01/carirekening2.php","fmIDREKENING","fmNMREKENING"),
				'type'=>''  
			),*/
			'spk' => array('label'=>'', 'value'=>'SPK/Perjanjian/Kontrak', 'type'=>'merge','row_params'=>" height='21'"),
			'spk_tgl' => array('label'=>'&nbsp;&nbsp;Tanggal', 
				'value'=> createEntryTgl('spk_tgl',$isi['spk_tgl'] ), 'type'=>''
			),
			'spk_no' => array('label'=>'&nbsp;&nbsp;Nomor', 
				'value'=>$isi['spk_no'], 'type'=>'text'
			),
			'dpa' => array('label'=>'', 'value'=>'SP2D/Kwitansi', 'type'=>'merge','row_params'=>" height='21'"),
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
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
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
	
	function setFormDKB_(){
		global $Main;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );	
		//data -----------------------------
		$cbid = $_POST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];				
		//form -----------------------------
		//$title = 'DKB';
		$menu =
			"<table width='100%' class='menudottedline'>
			<tbody><tr><td>
				<table width='50'><tbody><tr>				
					<td>					
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:".$this->Prefix.".SimpanDKB()'> 
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
		$this->form_fields = array(	
			'title' => array( 'label'=>'',
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">RKB</div>', 
				'type'=>'merge' 
			),
			'bidang' => array( 'label'=>'Bidang', 
				'value'=>$bidang, 
				'type'=>''
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
				'value'=> //'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$dt['tahun'].'" size="4" maxlength=4 onkeypress="return isNumberKey(event)">'
					$dt['tahun']
				,'labelWidth'=>170, 'type'=>'' 
			),
			'nm_barang' => array(  'label'=>'Nama Barang', 
				'value'=> $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'].' - '.$dt['nm_barang'], 
				'labelWidth'=>170, 'type'=>'' 
			),
			
			'merk_rkb' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> $dt['merk_barang'],
				'type'=>'' , 'row_params'=>"valign='top'"
			),
			'jmlbi' => array(  'label'=>'Jumlah Inventaris', 
				'value'=> $dt['jml_bi']			
				,'labelWidth'=>170,  'type'=>'' 
			),
			'standar' => array(  'label'=>'Jumlah Standar', 
				'value'=> $dt['jml_standar']
					//."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkb.getStandar()' title='Cek Jumlah Standar'>"
				,'labelWidth'=>170,  'type'=>'' 
			),
			'jml_barang' => array(  'label'=>'Jumlah RKB', 
				'value'=> $dt['jml_barang'].' '.$dt['satuan'], 					
				'type'=>'' 
			),
			'harga_rkb' => array( 'label'=>'Harga Satuan', 
				'value'=> number_format( $dt['harga'],2,',','.' ) ,
				'type'=>'' 
			),
			'jml_harga' => array(  'label'=>'Jumlah Harga', 
				'value'=> number_format( $dt['jml_harga'], 2,',','.'), 					
				'type'=>'' 
			),
			'kode_rekening' => array( 'label'=>'Kode Rekening', 
				'value'=> $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].' - '.$dt['nm_rekening'], 
				'type'=>''  
			),
			'ket_rkb' => array( 'label'=>'Keterangan', 
				'value'=>$dt['ket'],
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'title2' => array( 'label'=>'',
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:5 0 5 0">DKB</div>', 
				'type'=>'merge' 
			),
			/*'tgl_rkb' => array(  'label'=>'Tanggal', 
				'value'=> '',
				'type'=>'' 
			),*/
			'jml_barang2' => array(  'label'=>'Jumlah DKB', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' />".
					' '.$dt['satuan'],					
				'type'=>'' 
			),
			'harga' => array( 'label'=>'Harga Satuan', 
				'value'=>	inputFormatRibuan("harga", '',$dt['harga']) ,
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=>						
					"<input type='button' value='Hitung' onclick=\"
						document.getElementById('cnt_jmlharga').innerHTML = 
							Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
					<span id='cnt_jmlharga'>".number_format($dt['jml_harga'],0,',','.')."</span>" ,
				'type'=>'' 
			),
			'merk' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> "<textarea name=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 				
				'type'=>'' , 'row_params'=>"valign='top'"
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
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
				$menu,
				'type'=>'merge'
			)
		);
		$this->form_menubawah = '';
		$this->genForm_nodialog();
		
	}
	
	function simpanDKB(){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$idrkb = $_REQUEST[$this->Prefix.'_idplh'];
		$jml_barang = $_REQUEST['jml_barang']; //jml_barang dkb
		$harga = $_REQUEST['harga'];	
		$merk_barang = $_REQUEST['fmMEREK'];		
		$ket = $_REQUEST['fmKET'];	
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$rkb=mysql_fetch_array(mysql_query(
			"select * from $this->TblName where id='$idrkb' "
		));
		
		if($err =='' && $rkb['id']=='') $err = "RKB Tidak Ada!";
		if($err =='' && $rkb['stat']=='1') $err = "DKB Sudah Ada!";
		//if($err =='' && $rkb['jml_barang']<$jml_barang) $err = "Jumlah Barang Tidak Lebih Besar dari RKB!";
		
		if($err == ''){
			//$merk_barang = $rkb['merk_barang'];
			//$harga = $rkb['harga'];
			$satuan = $rkb['satuan'];
			//$ket = $rkb['ket'];
			$a = $rkb['a']; $b = $rkb['b'];	$c = $rkb['c']; $d = $rkb['d'];	$e = $rkb['e'];	$e1 = $rkb['e1'];
			$f = $rkb['f']; $g = $rkb['g'];	$h = $rkb['h'];	$i = $rkb['i'];	$j = $rkb['j'];
			$k = $rkb['k'];	$l = $rkb['l'];	$m = $rkb['m'];	$n = $rkb['n'];	$o = $rkb['o'];
			$tahun = $rkb['tahun'];
				
			$jml_harga = $harga * $jml_barang;
			
			//cek dkb sudah ada	//cek jml_dkb+jml_dkb_prev<=jml_rkb			
			$aqry = "insert into dkb (merk_barang,jml_barang,harga,satuan,jml_harga,ket,".
				"a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,tahun,idrkb,uid,tgl_update)".
				" values ".
				"('$merk_barang','$jml_barang','$harga','$satuan','$jml_harga','$ket',".
				"'$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$tahun','$idrkb','$UID',now())"; $cek .= $aqry;
			
			$qry = mysql_query($aqry);
			if($qry){
				$aqry = "update rkb set stat = 1 where id ='$idrkb'"; $cek .= $aqry;
				$qry = mysql_query($aqry);
			}else{
				$err = "Gagal Simpan DKB!";
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function simpan2(){
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
		$thn_perolehan = $_REQUEST['thn_perolehan'];
		$noreg = $_REQUEST['noreg'];
		$jml_harga  = $harga * $jml_barang;
		//satuan='$satuan',
		
		$spk_tgl 	= $_REQUEST['spk_tgl'];
		$spk_no 	= $_REQUEST['spk_no'];
		$dpa_tgl 	= $_REQUEST['dpa_tgl'];
		$dpa_no 	= $_REQUEST['dpa_no'];
		$pt			= $_REQUEST['pt'];
		
		$nip_pejabat_pengadaan	=$_REQUEST['nip_pejabat_pengadaan']; 
		$nama_pejabat_pengadaan	=$_REQUEST['nama_pejabat_pengadaan'];
		$nip_pembuat_komitmen	=$_REQUEST['nip_pembuat_komitmen'];
		$nama_pembuat_komitmen	=$_REQUEST['nama_pembuat_komitmen'];
		
		//-- validasi
		if($err=='' && ($jml_barang == '' || $jml_barang==0))$err = "Jumlah Barang belum diisi!";
		if($err=='' && ($harga == '' || $harga==0))$err = "Harga Satuan belum diisi!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
				
		//-- get jumlah daftar kebutuhan pemeliharaan
		$get = mysql_fetch_array( mysql_query(
			"select count(*)as cnt, sum(jml_barang) as tot from dkpb where ".
			"tahun='$tahun'".
			" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"
		));	
		$jmldkb = $get['tot']; 
		
		//cek kebutuhan ada
		if( $err=='' && ($get['cnt'] == NULL || $get['cnt']==0 )  ) $err = "Kebutuhan Barang $fmIDBARANG untuk Tahun Anggaran $tahun Tidak Ada!";
		
		
		
		if($fmST==0){ //baru
		
			if($err=='' && $fmIDBARANG == '')$err = "Kode Barang belum diisi!";			
			if($err=='' && $tahun == '')$err = "Tahun belum diisi!";
			
			//cek sisa --------------------
			if($err==''){
				
			
				$aqry = "select sum(jml_barang) as tot from pengadaan_pemeliharaan where ".
					"tahun='$tahun'".
					" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'";
				$get = mysql_fetch_array( mysql_query(
					$aqry
				));	
				$jmlada = $get['tot'];			
				$cek .= " $jmlada + $jml_barang > $jmldkb ";
				if (  $jmlada + $jml_barang > $jmldkb ){
					$err = "Jumlah Pengadaan Pemeliharaan Tidak Boleh Lebih dari Jumlah Kebutuhan Pemeliharaan Barang! Pengadaan Sebelumnya ".$jmlada.", Kebutuhan Barang ".$jmldkb;
				}
			
			}
			
		}else{
			$old = mysql_fetch_array( mysql_query(
				"select * from rkb where id='$id' "
			));
			
			//cek sisa ---------------------
			if($err==''){
				
			
				$aqry = "select sum(jml_barang) as tot from pengadaan_pemeliharaan where ".
					"tahun='$tahun'".
					" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id<>'$id'"; $cek.=$aqry;
				$get = mysql_fetch_array( mysql_query(
					$aqry
				));	
				$jmlada = $get['tot'];
				$cek .= " $jmlada + $jml_barang > $jmldkb ";	
				if ( $jmlada + $jml_barang > $jmldkb ){
					$err = "Jumlah Pengadaan Pemeliharaan Tidak Boleh Lebih dari Jumlah Kebutuhan Pemeliharaan Barang! Pengadaan Sebelumnya ".$jmlada.", Kebutuhan Barang ".$jmldkb;
				}
			}
		
			
		}
		
		
		
		
		if($err==''){
			if($fmST == 0){//baru
				$aqry = 
					"insert into $this->TblName_Edit ".
					"(merk_barang,jml_barang, harga,jml_harga,satuan,ket,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,tahun,".
					"thn_perolehan, noreg , ".
					"nip_pejabat_pengadaan,nama_pejabat_pengadaan,nip_pembuat_komitmen,nama_pembuat_komitmen,".
					"spk_tgl,spk_no,dpa_tgl,dpa_no,pt,".
					"uid,tgl_update) ".
					"values ".
					"('$merk_barang','$jml_barang',  '$harga','$jml_harga','$satuan','$ket',".
					"'$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$tahun',".
					"'$thn_perolehan','$noreg',".
					"'$nip_pejabat_pengadaan','$nama_pejabat_pengadaan','$nip_pembuat_komitmen','$nama_pembuat_komitmen',".					
					"'$spk_tgl','$spk_no','$dpa_tgl','$dpa_no','$pt',".
					"'$UID',now())";
				
			}else{
				
				
				$get = $this->getJmlBrgBI_($old['c'],$old['d'],$old['e'],$old['e1'],$old['f'],$old['g'],$old['h'],$old['i'],$old['j'],$old['tahun']);
		
				$aqry = 
					"update $this->TblName_Edit  ".
					"set merk_barang='$merk_barang', ".
					"jml_barang='$jml_barang', ".
					"harga='$harga', ".
					"jml_harga='$jml_harga', ".
					"satuan ='$satuan', ".
					//"jml_bi='".$get->content->jmlbi."', ".
					//"jml_standar='".$get->content->jmlstandar."', ".
					"ket='$ket', ".
					"tahun='$tahun',".
					"thn_perolehan='$thn_perolehan',".
					" noreg='$noreg' ,".
					"nip_pejabat_pengadaan='$nip_pejabat_pengadaan',".
					"nama_pejabat_pengadaan='$nama_pejabat_pengadaan',".
					"nip_pembuat_komitmen='$nip_pembuat_komitmen',".
					"nama_pembuat_komitmen='$nama_pembuat_komitmen',".
					"spk_tgl='$spk_tgl',".
					"spk_no='$spk_no',".
					"dpa_tgl='$dpa_tgl',".
					"dpa_no='$dpa_no',".
					"pt='$pt',".
					"f='$f',g='$g',h='$h',i='$i',j='$j',".
					"k='$k',l='$l',m='$m',n='$n',o='$o',".
					"uid='$UID', tgl_update= now() ".
					"where id='$id' ";
				
			}
			$cek .= $aqry;
			$qry = mysql_query($aqry);
			if($qry==FALSE) $err= 'Gagal Simpan Data!';
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
			and c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<'$tahun'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlbi = $isi['cnt'];
		
		$aqry = "select jml_barang from rkb_standar where 						
			c='".$c."' and d='$d' and e='$e' and e1='$e1' 
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
		
		$get = $this->getJmlBrgBI_($c,$d,$e,$f,$g,$h,$i,$j,$tahun);
		
		
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
	function hitPengadaanSebelumnya(){
		$cek=''; $err=''; $content='';
		$tahun = $_REQUEST['tahun'];
		$c = $_REQUEST['c'];		
		$d = $_REQUEST['d'];		
		$e = $_REQUEST['e'];	
		$e1 = $_REQUEST['e1'];	
		$fmIDBARANG = $_REQUEST['kdbrg'];		
		$f = $fmIDBARANG['0'].$fmIDBARANG['1'];
		$g = $fmIDBARANG['3'].$fmIDBARANG['4'];
		$h = $fmIDBARANG['6'].$fmIDBARANG['7'];
		$i = $fmIDBARANG['9'].$fmIDBARANG['10'];
		$j = $fmIDBARANG['12'].$fmIDBARANG['13'].$fmIDBARANG['14'];	
		$id = $_REQUEST['id'];
		
		
		$content->jmlada = $this->getPengadaanSebelumnya( $tahun , $c, $d, $e ,$e1 , $f, $g, $h, $i, $j, $id);		
		$content->jmldkb = $this->getJmlDKPB($tahun , $c, $d, $e ,$e1 , $f, $g, $h, $i, $j); 
		if($content->jmlada == NULL) $content->jmlada=0;
		if($content->jmldkb == NULL) $content->jmldkb =0;
		
		$content->jml = $content->jmldkb - $content->jmlada;
		$content->jmlsisa = 0;
		
		
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
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$dppb = new DppbObj();

?>