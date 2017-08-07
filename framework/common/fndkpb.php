<?php

class DkpbObj extends DaftarObj2{
	var $Prefix = 'dkpb';
	//var $SHOW_CEK = TRUE;	
	var $TblName = dkpb;//'v1_dkpb';//view2_sensus';
	var $TblName_Hapus = 'dkpb';
	var $TblName_Edit = 'dkpb';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_biaya');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 9, 9,9);
	var $FieldSum_Cp2 = array( 3, 3,3);	
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Perencanaan Kebutuhan dan Penganggaran';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $fileNameExcel='dkpbmd.xls';
	var $Cetak_Judul = 'DAFTAR DKPBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setTitle(){
		return 'Daftar Kebutuhan Pemeliharaan Barang Milik Daerah (DKPBMD)';
	}
	function setNavAtas(){
		global $Main;
		if ($Main->VERSI_NAME=='JABAR') $persediaan = "| <a href='pages.php?Pg=perencanaanbarang_persediaan' title='Penghapusan'>Persediaan</a> ";
		
		
		return
		
		'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=rkb" title="Pengadaan">Pengadaan</a> |				
				<a style="color:blue;" href="pages.php?Pg=rkpb" title="Pemeliharaan">Pemeliharaan</a>  |  
				<a href="pages.php?Pg=rencana_pemanfaatan" title="Pemanfaatan">Pemanfaatan</a>  |
				<a href="pages.php?Pg=rpebmd" title="Pemindahtanganan">Pemindahtanganan</a>  |
				<a href="pages.php?Pg=rphbmd" title="Penghapusan">Penghapusan</a> '.
					$persediaan.
				  '&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=rkpb" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKPBMD</a> |	
				<a href="pages.php?Pg=rekaprkpb" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKPBMD</a> |
				<a href="pages.php?Pg=rekaprkpbmd_skpd" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKPBMD (SKPD)</a>  |	
				<a href="pages.php?Pg=rka&jns=1" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKA</a> | 		
				<a style="color:blue;" href="pages.php?Pg=dkpb" title="Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">DKPBMD</a>  |  
				<a href="pages.php?Pg=rekapdkb" title="Rekap Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap DKPBMD</a>  |  
				<a href="pages.php?Pg=rekapdkbmd_skpd" title="Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap DKPBMD (SKPD)</a>
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=150>Kode Barang/ Kode Akun</th>
				<th class='th01' width=150>Nama Barang/Nama Akun</th>
				<th class='th01' >Uraian Pemeliharaan</th>
				<th class='th01' >Jumlah Barang </th>		
				<th class='th01' >Kuantitas </th>		
				<th class='th01' >Satuan </th>
				<th class='th01' >Harga Satuan</th>				
				<th class='th01' >Jumlah Harga</th>							
				<th class='th01' >Jenis Pemeliharaan/<br>Kapitalisasi</th>
				<th class='th01' >Keterangan </th>							
				<!--<th class='th01' >Status </th>							
				<th class='th01' >No. Register</th>
				<th class='th01' >Tahun<br>Perolehan</th>
				<th class='th01' >Tahun<br>Anggaran </th>-->
												
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$Ref,$HTTP_COOKIE_VARS;
		$Koloms = array();
		
		//nama skpd
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
		
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		//cari total bi
		$totalbi = '';
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'.'.$isi['kf']				
		);	
		$Koloms[] = array('', 
			$isi['nm_barang'].'<br>'.$isi['nm_account']
							
		);		
		$Koloms[] = array("", $isi['uraian'] );
		$Koloms[] = array("align='right'", $isi['jml_barang']  );	
		$Koloms[] = array("align='right'", $isi['kuantitas']  );	
		$Koloms[] = array("align='right'", $isi['satuan']  );	
		$Koloms[] = array("align='right'", number_format( $isi['harga'] ,2,',','.' ));			
		$Koloms[] = array("align='right'", number_format( $isi['jml_biaya'] ,2,',','.') );		
		$Koloms[] = array('', $Main->arJnsPelihara[ $isi['jns_pelihara'] -1][1].'<br>'.$Main->ArYaTidak[ $isi['kapitalisasi'] -1][1]  );
		$Koloms[] = array('', $isi['ket']);
		//$Koloms[] = array("align='center'", $status);
		return $Koloms;
	}

	function genDaftarOpsi(){
		global $Ref, $Main;
		
		
		//data cari ----------------------------
		
		$arrCari = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'),
			//array('3','Kode Rekening'),
			//array('4','Nama Rekening')
		);
		$arrStatus = array(
					array('1','RKPB'),
					array('2','DKPB'),
					array('3','RKA'),
				);	
		//get selectbox for cari data:
		$fmPILCARI = cekPost('fmPILCARI');
		$fmPILCARIVALUE = cekPost('fmPILCARIVALUE');
		
		//get selectbox for Tahun Anggaran:2013,2012

		//$kode_rekening = $_REQUEST['kode_rekening'];
		//$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];	$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		//data order ------------------------------
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];	
		$fmFiltStatus=$_REQUEST['fmFiltStatus'];		
		$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			array('3','Nama Barang'),		
		);
		
		//get select Order1
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		
		$TampilOpt =
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
			
			genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
			genFilterBar(
				array(	
					boxFilter( 'Tampilkan : '.	"<input type='text' value='$fmThnAnggaran' id='fmFiltThnAnggaran' name='fmFiltThnAnggaran' readonly>"
					 /*genComboBoxQry(
						'fmFiltThnAnggaran',
						$fmFiltThnAnggaran,
						"select tahun from $this->TblName group by tahun desc ",
						'tahun', 
						'tahun',
						'Tahun Anggaran'
					)*/)
					//$Main->batas.
					/*boxFilter('Status : '.
					cmbArray(
					'fmFiltStatus',
					$fmFiltStatus,
					$arrStatus,
					'-- Status --',
					''
					))*/
					
				),$this->Prefix.".refreshList(true)",FALSE
			).
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' $fmDESC1 id='fmDESC1' name='fmDESC1' value='checked'>Desc."
				),			
				$this->Prefix.".refreshList(true)");	
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		//$arrKondisi[] = 'a='.$Main->Provinsi[0];
		//$arrKondisi[] = 'b='.'00';
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
			$fmSUBUNIT,
			$fmSEKSI
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
		switch($_REQUEST['fmFiltStatus']){
			case '1': $fmFiltStatus='0'; break;
			case '2': $fmFiltStatus='1'; break;
			case '3': $fmFiltStatus='2'; break;
		}
		if(!empty($_REQUEST['fmFiltStatus'])) $arrKondisi[]= "stat = '$fmFiltStatus'";
		if(!empty($fmFiltThnAnggaran) )  $arrKondisi[] = "tahun='$fmFiltThnAnggaran'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " thn_anggaran $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " nm_barang $Asc1 " ;break;
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
	
	function setMenuEdit(){
		return	//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";			
	}

	function setMenuView(){
		return 
		"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>				<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>
		";
		}	

	
	function setFormEdit(){
		global $Main;
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
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];	
		$fmTAHUN = $dt['thn_anggaran']==''?  $_COOKIE['coThnAnggaran'] : $dt['thn_anggaran'] ; //def tahun = 2012
		$kode_barang = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;	
		$kode_account = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].'.'.$dt['kf'];		
		//-- ambil data rkb
		$rkpb = mysql_fetch_array(mysql_query(
			"select * from rkpb where id='".$dt['idrkpb']."'"
		));
		$vjmlharga2 = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='text' id='jml_harga' name='jml_harga' readonly='TRUE'  value='".number_format($dt['jml_biaya'],2,',','.')."'>&nbsp".
			"<input type='button' value='Hitung' onclick='".$this->Prefix.".Get_JmlHarga()'>&nbsp&nbsp
			";
		
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
							href='javascript:".$this->Prefix.".Simpan()'> 
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
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">RKPB</div>', 
				'type'=>'merge' 
			),
			'bidang' => array( 'label'=>'BIDANG', 
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
				'value'=>'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)" readonly="TRUE">'
					
				,'labelWidth'=>170, 'type'=>'' 
			),
			'nm_barang' => array(  'label'=>'Kode & Nama Barang', 
				'value'=> "<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_barang' readonly=''>&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$dt['nm_barang']."' readonly=''>",
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'nm_account' => array(  'label'=>'Kode & Nama Akun', 
				'value'=> "<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>&nbsp;<input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>",
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),								
			'jml_barang_rkpb' => array(  'label'=>'Jumlah RKPBMD', 
				'value'=> 
					"<input name=\"jml_barang_rkpb\" id=\"jml_barang_rkpb\" type=\"text\" value='".$rkpb['jml_barang']."' readonly/>".
					"&nbsp;Satuan ".
					"&nbsp;&nbsp;".
					"<input name=\"satuan_rkpb\" id=\"satuan_rkpb\" type=\"text\" value='".$rkpb['satuan']."' readonly/>", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			'kuantitas_rkpb' => array( 'label'=>'Volume / Kuantitas', 
				'value'=>"<input name=\"kuantitas_rkpb\" type=\"text\" value='".$rkpb['kuantitas']."' readonly/>", 
					//inputFormatRibuan("harga", '',$dt['harga']) ,
					//number_format($dt['harga'],2,',','.'),
				'type'=>'' 
			),		
			'harga_rkpb' => array( 'label'=>'Harga Satuan', 
				'value'=>"<input name=\"harga_rkpb\" type=\"text\" value='".number_format($rkpb['harga'],2,',','.')."' readonly/>", 
					//inputFormatRibuan("harga", '',$dt['harga']) ,
					//number_format($dt['harga'],2,',','.'),
				'type'=>'' 
			),
			'jml_harga_rkpb' => array( 'label'=>'Jumlah Harga', 
				'value'=>"<input name=\"jml_harga_rkpb\" type=\"text\" value='".number_format($rkpb['jml_biaya'],2,',','.')."' readonly/>",
					//number_format($dt['jml_biaya'],2,',','.'),
				'type'=>'' 
			),
			'uraian_rkb' => array( 'label'=>'Uraian Pemeliharaan', 
				'value'=>
				//"<span style='display:block; white-space: pre-wrap;word-wrap: break-word; ' >".$dt['uraian']."</span>",
				"<textarea readonly name=\"uraian_rkpb\" id='uraian_rkpb'  cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$rkpb['uraian']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),			
			'jns_pelihara_rkpb' => array( 'label'=>'Jenis Pemeliharaan', 
				'value'=>
					//cmbArray('jns_pelihara',$dt['jns_pelihara'],$Main->arJnsPelihara,'Pilih Jenis Pemeliharaan',''),
					"<input name=\"jns_pelihara_rkpb\" type=\"text\" value='".$Main->arJnsPelihara[$rkpb['jns_pelihara']-1][1]."' readonly/>",
				'type'=>'', 'row_params'=>"valign='top'"
			),			
			'kapitalisasi_rkpb' => array( 'label'=>'Kapitalisasi', 
				'value'=>
					"<input name=\"kapitalisasi_rkpb\" size=\"4\" type=\"text\" value='".$Main->ArYaTidak[$rkpb['kapitalisasi']-1][1]."' readonly/>",
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'ket_rkpb' => array( 'label'=>'Keterangan', 
				'value'=>
				//"<span style='display:block; white-space: pre-wrap;word-wrap: break-word; ' >".$dt['ket']."</span>",
				"<textarea readonly name=\"fmKET_rkpb\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$rkpb['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			
			
			//DKPB -----------------------------------------------
			'title2' => array( 'label'=>'',
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:5 0 5 0">DKPB</div>', 
				'type'=>'merge' 
			),			
			'jml_barang' => array(  'label'=>'Jumlah DKPBMD', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' onkeypress=\"return isNumberKey(event)\"/>",					
				'type'=>'' 
			),
			'kuantitas' => array( 'label'=>'Volume / Kuantitas', 
				'value'=>"<input name=\"kuantitas\" id=\"kuantitas\" type=\"text\" value='".$dt['kuantitas']."' onkeypress=\"return isNumberKey(event)\"/>", 
				'type'=>'' 
			),
			'harga' => array( 'label'=>'Harga Satuan', 
				'value'=>"<input name=\"harga\" id=\"harga\" type=\"text\" value='".$dt['harga']."' onkeypress=\"return isNumberKey(event)\"/>", 
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=> $vjmlharga2 ,
				'type'=>'' 
			),
			'jns_pelihara' => array( 'label'=>'Jenis Pemeliharaan', 
				'value'=>
					cmbArray('jns_pelihara',$dt['jns_pelihara'],$Main->arJnsPelihara,'--- PILIH ---',''),
					//$Main->arJnsPelihara[$dt['jns_pelihara']-1][1],
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'kapitalisasi' => array( 'label'=>'Kapitalisasi', 
				'value'=>cmbArray('kapitalisasi',$dt['kapitalisasi'],$Main->ArYaTidak,'--- PILIH ---',''),
				'type'=>'', 'row_params'=>"valign='top'"
			),			
			'uraian' => array( 'label'=>'Uraian Pemeliharaan', 
				'value'=>
				//$dt['uraian'],
				"<textarea name=\"uraian\" id='uraian'  cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['uraian']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),	
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			
			
			'menu'=> array( 'label'=>'', 
				'value'=>
				//"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				//"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				//"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				//"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
				$menu,
				'type'=>'merge'
			)
		);
		$this->form_menubawah = '';
		$this->genForm_nodialog();
		
	}
	function Hapus_Validasi($id){
		$err ='';
		//$KeyValue = explode(' ',$id);
		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName where id='$id' "		
		));
		$aqry = "select count(*) as cnt from pengadaan_pemeliharaan where tahun='".$old['tahun']."' ".
			" and c='".$old['c']."' and d='".$old['d']."' and e='".$old['e']."'  and e1='".$old['e1']."'".
			" and f='".$old['f']."' and g='".$old['g']."' and h='".$old['h']."' and i='".$old['i']."' and j='".$old['j']."' ";
		$get = mysql_fetch_array(mysql_query(
			$aqry
		));
		if($err=='' && $get['cnt']>0 ) $err = 'Data Tidak Bisa Dihapus, Sudah ada di Pengadaan!';
		
		return $err;
	}
	
	function simpan(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$jml_barang = $_REQUEST['jml_barang']; 
		$jml_barang_rkpb = $_REQUEST['jml_barang_rkpb']; 
		$kuantitas = $_REQUEST['kuantitas'];			
		$harga = $_REQUEST['harga'];			
		$jml_harga = $_REQUEST['jml_harga'];			
		$jns_pelihara = $_REQUEST['jns_pelihara'];		
		$kapitalisasi = $_REQUEST['kapitalisasi'];		
		$uraian = $_REQUEST['uraian'];
		$ket = $_REQUEST['fmKET'];			
		//$harga = str_replace(".","",$_REQUEST['harga']);		
		$jml_harga = str_replace(".","",$jml_barang*$kuantitas*$harga);
		//-- get old 
		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName where id='$id' "		
		));
			
		//-- validasi	
		if($err=='' && ($jml_barang == '' || $jml_barang==0))$err = "Jumlah DKPBMD belum diisi!";
		if($err =='' && $kuantitas=='' ) $err = "Volume / Kuantitas Belum Diisi!";		
		if($err=='' && ($harga == '' || $harga==0))$err = "Harga Barang belum diisi!";
		if($err =='' && ($jml_harga == '' || $jml_harga==0)) $err = "Jumlah Harga belum di hitung!";
		if($err =='' && $jml_barang_rkpb<$jml_barang) $err = "Jumlah DKPBMD Tidak Lebih Besar dari RKPB!";
		
		
		//-- cek sudah pengadaan
		if($err=='' ){
			$aqry = "select count(*) as cnt from pengadaan_pemeliharaan where tahun='".$old['tahun']."' ".
				" and c='".$old['c']."' and d='".$old['d']."' and e='".$old['e']."'  and e1='".$old['e1']."'".
				" and f='".$old['f']."' and g='".$old['g']."' and h='".$old['h']."' and i='".$old['i']."' and j='".$old['j']."' "; 
			$cek .= $aqry;		
			$pengadaan = mysql_fetch_array(mysql_query( $aqry	));
			if( $pengadaan['cnt'] > 0 ) $err='Gagal Simpan! Data Sudah Ada di Pengadaan!';				
		}
		//-- cek jml kebutuhan < jml rencana
		if($err==''){
			$aqry = "select *  from rkpb where  tahun='".$old['tahun']."' ".
				" and c='".$old['c']."' and d='".$old['d']."' and e='".$old['e']."'  and e1='".$old['e1']."'".
				" and f='".$old['f']."' and g='".$old['g']."' and h='".$old['h']."' and i='".$old['i']."' and j='".$old['j']."' "; 
			$cek .= $aqry;		
			$rkb = mysql_fetch_array(mysql_query($aqry));
			//if( $jml_barang > $rkb['jml_barang']) $err = "Jumlah Barang Kebutuhan Tidak Lebih Besar Dari Rencana!";
		}
		
		
		//-- hit jml harga
		$jml_harga = $jml_barang * $kuantitas * $harga  ;		
		
		if($err==''){
			$aqry = "update $this->TblName_Edit set ".
				" jml_barang='$jml_barang', kuantitas='$kuantitas', harga='$harga', jml_biaya='$jml_harga'".
				", jns_pelihara='$jns_pelihara', kapitalisasi='$kapitalisasi', uraian='$uraian', ket='$ket'  ".
				", uid='$UID', tgl_update=now() where id='$id' "	;$cek .=$aqry;
				
			$qry = mysql_query($aqry);
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){			
			case 'formEdit':{								
				$this->setFormEdit();				
				$json = FALSE;				
				/*$get = $this->setFormEdit(); $cek = $get['cek']; $err = $get['err']; $content=$get['content']; */
				break;
			}
			case 'simpan' : {
				$get = $this->simpan();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
}

$dkpb = new DkpbObj();

?>