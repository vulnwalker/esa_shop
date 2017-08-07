<?php

class RkpbObj extends DaftarObj2{
	var $Prefix = 'rkpb';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'v1_rkpb';//view2_sensus';
	var $TblName_Hapus = 'rkpb';
	var $TblName_Edit = 'rkpb';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_biaya');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 8, 7, 7);
	var $FieldSum_Cp2 = array( 4, 4,4);
	var $FormName = 'rkpbForm';	
	//var $FormName = 'adminForm';	
	var $PageTitle = 'Perencanaan Kebutuhan dan Penganggaran';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';	
	var $fileNameExcel='rkpbmd.xls';
	var $Cetak_Judul = 'DAFTAR RKPBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	//var $row_params= " valign='top'";
	
	
	function setTitle(){
		return 'Rencana Kebutuhan Pemeliharaan Barang Milik Daerah (RKPBMD)';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
		global $Main;
		if ($Main->VERSI_NAME=='JABAR') $persediaan = "| <a href='pages.php?Pg=perencanaanbarang_persediaan' title='Perencanaan Persediaan'>Persediaan</a> ";
	
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=rkb" title="Pengadaan">Pengadaan</a> |				
				<a style="color:blue;" href="pages.php?Pg=rkpb" title="Pemeliharaan">Pemeliharaan</a>  |  
				<a href="pages.php?Pg=rencana_pemanfaatan" title="Pemanfaatan">Pemanfaatan</a>  |
				<a href="pages.php?Pg=rpebmd" title="Pemindahtanganan">Pemindahtanganan</a>  |
				<a href="pages.php?Pg=rphbmd" title="Penghapusan">Penghapusan</a>'.
				$persediaan. 
				 ' &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=rkpb" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKPBMD</a> |	
				<a href="pages.php?Pg=rekaprkpb" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKPBMD</a> |
				<a href="pages.php?Pg=rekaprkpbmd_skpd" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKPBMD (SKPD)</a>  |	
				<a href="pages.php?Pg=rka&jns=1" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKA</a> | 		
				<a href="pages.php?Pg=dkpb" title="Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">DKPBMD</a>  |  
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
				<tr><th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=150>Kode Barang/ Kode Akun</th>
				<th class='th01' width=150>Nama Barang/Nama Akun</th>
				<th class='th01' >Jumlah Barang </th>		
				<th class='th01' >Kuantitas </th>		
				<th class='th01' >Satuan </th>
				<th class='th01' >Harga Satuan</th>				
				<th class='th01' >Jumlah Harga</th>							
				<th class='th01' >Uraian Pemeliharaan</th>
				<th class='th01' >Jenis Pemeliharaan/<br>Kapitalisasi</th>
				<th class='th01' >Keterangan </th>							
				<th class='th01' >Status </th>							
				<!--<th class='th01' >No. Register</th>
				<th class='th01' >Tahun<br>Perolehan</th>
				<th class='th01' >Tahun<br>Anggaran </th>-->			
				</tr>
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS, $Main;
		
		$Koloms = array();
		
		//cari total bi
		$totalbi = '';
		if($isi['stat']==0){
			$status = "";
		}elseif($isi['stat']==1){
			$status = "DKPB";
		}else{
			$status = "RKA";
		}
		
		
		
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
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'.'.$isi['kf']				
		);	
		$Koloms[] = array('', 
			$isi['nm_barang'].'<br>'.$isi['nm_account']
							
		);		
		$Koloms[] = array("align='right'", $isi['jml_barang']  );	
		$Koloms[] = array("align='right'", $isi['kuantitas']  );	
		$Koloms[] = array("align='right'", $isi['satuan']  );	
		$Koloms[] = array("align='right'", number_format( $isi['harga'] ,2,',','.' ));			
		$Koloms[] = array("align='right'", number_format( $isi['jml_biaya'] ,2,',','.') );		
		$Koloms[] = array("", $isi['uraian'] );
		$Koloms[] = array('', $Main->arJnsPelihara[ $isi['jns_pelihara'] -1][1].'<br>'.$Main->ArYaTidak[ $isi['kapitalisasi'] -1][1]  );
		$Koloms[] = array('', $isi['ket']);
		$Koloms[] = array("align='center'",  $status);
		//$Koloms[] = array("align='center'", $isi['noreg']  );		
		//$Koloms[] = array("align='center'", $isi['thn_perolehan']  );

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
			array('2','Nama Barang'),
			//array('3','Kode Rekening'),
			//array('4','Nama Rekening')
		);
		$arrStatus = array(
					array('1','RKPB'),
					array('2','RKA'),
					array('3','DKPB'),
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
					)*/).
					$Main->batas.
					boxFilter('Status : '.
					cmbArray(
					'fmFiltStatus',
					$fmFiltStatus,
					$arrStatus,
					'-- Status --',
					''
					))
					
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
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];		
		switch($_REQUEST['fmFiltStatus']){
			case '1': $fmFiltStatus='0'; break;
			case '2': $fmFiltStatus='2'; break;
			case '3': $fmFiltStatus='1'; break;
		}
		if(!empty($_REQUEST['fmFiltStatus'])) $arrKondisi[]= "stat = '$fmFiltStatus'";
		if(!empty($fmThnAnggaran) )  $arrKondisi[] = "tahun='$fmThnAnggaran'";
		
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
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'RKPB Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit RKPB')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus RKPB')."</td>".
			"<td>".genPanelIcon("javascript:RKA.Baru(1)","new_f2.png","RKA", 'RKA')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".setDKPB()","new_f2.png","DKPB", 'DKPB')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}

	function setMenuView(){
		return 
		"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>				<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>
		";
		}	

	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		$dt['tahun']=$_COOKIE['coTahunAnggaran'];		
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
		
		//echo sizeof($cbid).' '.$cbid[0] ;
		//print_r($cbid);
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']
		);
	}
	
	function setForm($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmNMBARANG, $fmIDREKENING, $Main;
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
		$kode_barang = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;	
		$kode_account = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].'.'.$dt['kf'];
		
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
		$fmTAHUN = $dt['tahun']==''?  $_COOKIE['coThnAnggaran'] : $dt['tahun'] ; //def tahun = 2012
		
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'"
		));
		$fmNMBARANG = $get['nm_barang'];
		
		$vtahun = //$this->form_fmST==1?
			//$fmTAHUN :
			'<input type="text" readonly="TRUE" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)">';
		
		/*$vkdbarang = $this->form_fmST==1?
			$fmIDBARANG.' - '.$dt['nm_barang'] :
			cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled"); 
		*/			
		$vdatabi = //$this->form_fmST==1?
			//$fmIDBARANG.' - '.$dt['nm_barang']  :
			cariBI("adminForm","pages/01/caribi1.php","pages/01/caribi2.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled",'',$dt['idbi'],$dt['c'],$dt['d'],$dt['e']); 
			/*'<iframe id="cariiframeBI" name="cariiframeBI" 
				style=";position:absolute;visibility:hidden" 
				src="pages/01/caribarang2a.php?objID=fmIDBARANG&objNM=fmNMBARANG&kdBrgOld=&Act=">
				
	</iframe>'.
			'<input type="text" name="fmIDBARANG" id="fmIDBARANG" value="02.06.02.01.04" size="20" maxlength="30" readonly="">
		<input type="text" name="fmNMBARANG" id="fmNMBARANG" value="Meja Kayu/Rotan" size="100" maxlength="100" readonly="">'.
			"<input type='button' id='btcaribi' name='btcaribi' value='Cari' onclick=\"TampilkanIFrame(document.all.cariiframeBI)\">"; 
		*/
		
		
		$vjmlbi = $this->form_fmST==1?
			$dt['jml_bi'] :
			'<input type="text"  readonly="true" id="jmlbi" name="jmlbi" value="'.$dt['jml_bi'].'" >'
					."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkpb.cekJmlBrgBI()' title='Cek Jumlah Inventaris (Tahun Sebelumnya) dan Jumlah Standar'>";
		
		$vstandar = $this->form_fmST==1?
			number_format($dt['jml_standar'],0,',','.') :
			'<input type="text"  readonly="true" id="standar" name="standar" value="'.$dt['jml_standar'].'" >';
			
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='text' id='jml_harga' name='jml_harga' readonly='TRUE'  value='".number_format($dt['jml_biaya'],2,',','.')."'>&nbsp".
			"<input type='button' value='Hitung' onclick='".$this->Prefix.".Get_JmlHarga()'>&nbsp&nbsp
			";
		$vnoreg = //$this->form_fmST==1?
			//$dt['noreg'] :
			 "<input type='text' id='noreg' name='noreg' readonly=''  value='".$dt['noreg']."'>";
		$vthnperolehan = //$this->form_fmST==1?
			//$dt['thn_perolehan'] :
			"<input type='text' id='thn_perolehan' name='thn_perolehan' readonly='' value='".$dt['thn_perolehan']."'>";
		/*$arrJnsPelihara = array(
			array('1','Pemeliharaan Ringan'),
			array('2','Pemeliharaan Sedang'), 
			array('3','Pemeliharaan Berat')	//array('4','Nama Rekening')
		);	
		*/
		$title = $this->form_fmST == 1? 'Rencana Kebutuhan Pemeliharaan Barang Milik Daerah - Edit' : 'Rencana Kebutuhan Pemeliharaan Barang Milik Daerah - Baru';
		$this->form_fields = array(	
			'title' => array('label'=>'','value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">'.$title.'</div>', 'type'=>'merge' ),			
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
			'tahun' => array(  'label'=>'Tahun', 
				'value'=> $vtahun
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'nm_barang' => array( 
				'label'=>'Nama Barang',
				'labelWidth'=>150, 
				'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_barang' readonly=''>".
						 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$dt['nm_barang']."' readonly=''>".
						 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
					 ),			
			'kode_account' => array( 
				'label'=>'Nama Akun',
				'labelWidth'=>100, 
				'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
						  <input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>
						  <input type=hidden id='tahun_account' name='tahun_account' value='".$dt['thn_akun']."'>
						  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnal()' title='Cari Jurnal' >" 
					 ),						
			'jml_barang' => array(  'label'=>'Jumlah Barang', 
				'value'=> 
				"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' onkeypress='return isNumberKey(event)'/>",				 
				//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			'kuantitas' => array( 'label'=>'Volume / Kuantitas', 
				'value'=> 
				"<input name=\"kuantitas\" id='kuantitas' type=\"text\" value='".$dt['kuantitas']."' onkeypress='return isNumberKey(event)'/>".
				" Satuan ".
				"<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />", 
				//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),						
			'harga' => array( 'label'=>'Harga Satuan', 
				'value'=>"<input name=\"harga\" id=\"harga\" type=\"text\" value='".$dt['harga']."' onkeypress='return isNumberKey(event)'/>&nbsp", 
				//inputFormatRibuan("harga", '',$dt['harga']).
				//"<input type='button' value='Info' onclick=\"".$this->Prefix.".Info()\">" ,
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=> $vjmlharga ,
				'type'=>'' 
			),				
			'uraian' => array( 'label'=>'Uraian Pemeliharaan', 
				'value'=>"<textarea name=\"uraian\" id='uraian'  cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['uraian']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'jns_pelihara' => array( 'label'=>'Jenis Pemeliharaan', 
				'value'=>cmbArray('jns_pelihara',$dt['jns_pelihara'],$Main->arJnsPelihara,'--- PILIH ---',''),
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'kapitalisasi' => array( 'label'=>'Kapitalisasi', 
				'value'=>cmbArray('kapitalisasi',$dt['kapitalisasi'],$Main->ArYaTidak,'--- PILIH ---',''),
				'type'=>'', 'row_params'=>"valign='top'"
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
	
	function setFormDKPB(){
		global $fmIDBARANG, $fmNMBARANG, $fmIDREKENING, $Main;
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
		$bi = mysql_fetch_array(mysql_query(
			"select * from view_buku_induk where id='".$isi['idbi']."'"
		));
		
		$kode_barang = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;	
		$kode_account = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].'.'.$dt['kf'];
			
		//form -----------------------------			
		$vjmlharga2 = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='text' id='jml_harga' name='jml_harga' readonly='TRUE'  value='".number_format($dt['jml_biaya'],2,',','.')."'>&nbsp".
			"<input type='button' value='Hitung' onclick='".$this->Prefix.".Get_JmlHarga()'>&nbsp&nbsp
			";
		
		
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
							href='javascript:".$this->Prefix.".SimpanDKPB()'> 
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
			
			//RKB -------------------------------------------
			'title' => array( 'label'=>'',
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">RKPBMD</div>', 
				'type'=>'merge' 
			),
			'bidang' => array( 'label'=>'BIDANG', 
				'value'=>$bidang, 
				'type'=>''
			),
			'unit' => array( 'label'=>'SKPD', 
				'value'=>$unit, 
				'type'=>''
			),
			'subunit' => array( 'label'=>'UNIT', 
				'value'=>$subunit, 
				'type'=>''
			),
			'seksi' => array( 'label'=>'SUB UNIT', 
				'value'=>$seksi, 
				'type'=>''
			),
			'tahun' => array(  'label'=>'Tahun Anggaran', 
				'value'=>'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$dt['tahun'].'" size="4" maxlength=4 onkeypress="return isNumberKey(event)" readonly="TRUE">'
					
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
					"<input name=\"jml_barang_rkpb\" id=\"jml_barang_rkpb\" type=\"text\" value='".$dt['jml_barang']."' readonly/>".
					"&nbsp;Satuan ".
					"&nbsp;&nbsp;".
					"<input name=\"satuan_rkpb\" id=\"satuan_rkpb\" type=\"text\" value='".$dt['satuan']."' readonly/>", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			'kuantitas_rkpb' => array( 'label'=>'Volume / Kuantitas', 
				'value'=>"<input name=\"kuantitas_rkpb\" type=\"text\" value='".$dt['kuantitas']."' readonly/>", 
					//inputFormatRibuan("harga", '',$dt['harga']) ,
					//number_format($dt['harga'],2,',','.'),
				'type'=>'' 
			),		
			'harga_rkpb' => array( 'label'=>'Harga Satuan', 
				'value'=>"<input name=\"harga_rkpb\" type=\"text\" value='".number_format($dt['harga'],2,',','.')."' readonly/>", 
					//inputFormatRibuan("harga", '',$dt['harga']) ,
					//number_format($dt['harga'],2,',','.'),
				'type'=>'' 
			),
			'jml_harga_rkpb' => array( 'label'=>'Jumlah Harga', 
				'value'=>"<input name=\"jml_harga_rkpb\" type=\"text\" value='".number_format($dt['jml_biaya'],2,',','.')."' readonly/>",
					//number_format($dt['jml_biaya'],2,',','.'),
				'type'=>'' 
			),
			'uraian_rkb' => array( 'label'=>'Uraian Pemeliharaan', 
				'value'=>
				//"<span style='display:block; white-space: pre-wrap;word-wrap: break-word; ' >".$dt['uraian']."</span>",
				"<textarea readonly name=\"uraian_rkpb\" id='uraian_rkpb'  cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['uraian']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),			
			'jns_pelihara_rkpb' => array( 'label'=>'Jenis Pemeliharaan', 
				'value'=>
					//cmbArray('jns_pelihara',$dt['jns_pelihara'],$Main->arJnsPelihara,'Pilih Jenis Pemeliharaan',''),
					"<input name=\"jns_pelihara_rkpb\" type=\"text\" value='".$Main->arJnsPelihara[$dt['jns_pelihara']-1][1]."' readonly/>",
				'type'=>'', 'row_params'=>"valign='top'"
			),			
			'kapitalisasi_rkpb' => array( 'label'=>'Kapitalisasi', 
				'value'=>
					"<input name=\"kapitalisasi_rkpb\" size=\"4\" type=\"text\" value='".$Main->ArYaTidak[$dt['kapitalisasi']-1][1]."' readonly/>",
				'type'=>'', 'row_params'=>"valign='top'"
			),
			'ket_rkpb' => array( 'label'=>'Keterangan', 
				'value'=>
				//"<span style='display:block; white-space: pre-wrap;word-wrap: break-word; ' >".$dt['ket']."</span>",
				"<textarea readonly name=\"fmKET_rkpb\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
			
			
			//DKPB -----------------------------------------------
			'title2' => array( 'label'=>'',
				'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:5 0 5 0">DKPBMD</div>', 
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
				/*"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".	*/			
				$menu,
				'type'=>'merge'
			)
		);
		$this->form_menubawah = '';
		$this->genForm_nodialog();
		
	}
	
	function simpanDKPB(){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		$idrkpb = $_REQUEST[$this->Prefix.'_idplh'];		
		$jml_barang = $_REQUEST['jml_barang']; 
		$kuantitas = $_REQUEST['kuantitas'];			
		$harga = $_REQUEST['harga'];			
		$jml_harga = $_REQUEST['jml_harga'];
		$uraian=$_REQUEST['uraian'];
		$jns_pelihara = $_REQUEST['jns_pelihara'];
		$kapitalisasi = $_REQUEST['kapitalisasi'];
		$ket = $_REQUEST['fmKET'];
		$tahun = $_REQUEST['fmTAHUN'];
		$tahun_akun = $_REQUEST['tahun_account'];
		$fmTAHUN = $_COOKIE['coThnAnggaran'];
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$nm_barang = $_REQUEST['fmNMBARANG'];
		$fmIDREKENING = $_REQUEST['kode_account'];
		$nm_account = $_REQUEST['nama_account'];
		
		
		//-- validasi				
		$rkpb=mysql_fetch_array(mysql_query(
		"select * from $this->TblName where id='$idrkpb' "
		));
		if($err =='' && $rkpb['id']=='') $err = "RKPB Tidak Ada!";
		if($err =='' && $rkpb['stat']=='1') $err = "DKPB Sudah Ada!";
		if($err =='' && ($jml_barang==''||$jml_barang==0)) $err = "Jumlah DKPBMD Belum Diisi!";
		if($err =='' && $rkpb['jml_barang']<$jml_barang) $err = "Jumlah DKPBMD Tidak Lebih Besar dari RKPB!";
		if($err =='' && $kuantitas=='' ) $err = "Volume / Kuantitas Belum Diisi!";		
		if($err =='' && ($harga==''||$harga==0)) $err = "Harga Satuan Belum Diisi!";
		if($err =='' && ($jml_harga == '' || $jml_harga==0)) $err = "Jumlah Harga belum di hitung!";
		//if($err=='' && $uraian == '')$err = "Uraian belum diisi!";
		//if($err=='' && $jns_pelihara == '')$err = "Jenis Pemeliharaan belum di pilih!";
		//if($err=='' && $kapitalisasi == '')$err = "Kapitalisasi belum di pilih!";
		//if($err=='' && $ket == '')$err = "Keterangan belum diisi!";
		if($err == ''){		
			//$merk_barang = $rkb['merk_barang'];//$harga = $rkb['harga'];
			$satuan = $rkpb['satuan'];
			//$ket = $rkb['ket'];
			//$a = $rkpb['a']; $b = $rkpb['b']; 
			$a1 = $Main->DEF_KEPEMILIKAN;
		 	$a = $Main->DEF_PROPINSI; //$_REQUEST[''];
		 	$b = $Main->DEF_WILAYAH; //$_REQUEST['merk_barang'];
			$c = $rkpb['c']; $d = $rkpb['d']; $e = $rkpb['e'];$e1 = $rkpb['e1'];
			$f = $rkpb['f']; $g = $rkpb['g']; $h = $rkpb['h']; $i = $rkpb['i'];	$j = $rkpb['j'];
			$k = $rkpb['k']; $l = $rkpb['l']; $m = $rkpb['m']; $n = $rkpb['n'];	$o = $rkpb['o']; $kf = $rkpb['kf'];
			$tahun = $rkpb['tahun'];	
			$thn_perolehan = $rkpb['thn_perolehan'];
			$jml_harga = $jml_barang * $kuantitas * $harga  ;
			//$harga = str_replace(".","",$_REQUEST['harga']);		
			$jml_harga = str_replace(".","",$jml_barang*$kuantitas*$harga);	
			//cek dkb sudah ada	//cek jml_dkb+jml_dkb_prev<=jml_rkb			
			/*$aqry = "insert into dkpb (jml_barang,kuantitas,harga,satuan,jml_biaya,ket,uraian,jns_pelihara,kapitalisasi,nm_barang,nm_account,".
				"idrkpb,a1,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,kf,thn_anggaran,uid,tgl_update)".
				" values ".
				"('$jml_barang','$kuantitas','$harga','$satuan','$jml_harga','$ket','$uraian','$jns_pelihara','$kapitalisasi','$nm_barang','$nm_account',".
				"'$idrkpb','$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$kf','$fmTAHUN','$UID',now())"; $cek .= $aqry;
			*/
			$aqry = "insert into dkpb (jml_barang,kuantitas,harga,satuan,jml_biaya,ket,uraian,jns_pelihara,kapitalisasi,nm_barang,nm_account,".
				"idrkpb,a1,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,kf,tahun,thn_perolehan, uid,tgl_update)".
				" values ".
				"('$jml_barang','$kuantitas','$harga','$satuan','$jml_harga','$ket','$uraian','$jns_pelihara','$kapitalisasi','$nm_barang','$nm_account',".
				"'$idrkpb','$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$kf','$fmTAHUN', '$thn_perolehan' ,'$UID',now())"; $cek .= $aqry;
						
			$qry = mysql_query($aqry);
			if($qry){
				$aqry = "update rkpb set stat = 1 where id ='$idrkpb'"; $cek .= $aqry;
				$qry = mysql_query($aqry);
			}else{
				$err = "Gagal Simpan DKPB!";
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function simpan2(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];		
		 $fmIDBARANG=$_REQUEST['fmIDBARANG'];
		 $expfmIDBARANG=explode('.',$fmIDBARANG);
		 $a1 = $Main->DEF_KEPEMILIKAN;
		 $a = $Main->DEF_PROPINSI; //$_REQUEST[''];
		 $b = $Main->DEF_WILAYAH; //$_REQUEST['merk_barang'];
		 $c = $_REQUEST['c'];
		 $d = $_REQUEST['d'];
		 $e = $_REQUEST['e'];
		 $e1 = $_REQUEST['e1'];
		 $f=$expfmIDBARANG['0'];
		 $g=$expfmIDBARANG['1'];
		 $h=$expfmIDBARANG['2'];
		 $i=$expfmIDBARANG['3'];
		 $j=$expfmIDBARANG['4'];
		 $fmIDREKENING=$_REQUEST['kode_account'];
		 $expfmIDREKENING=explode('.',$fmIDREKENING);
		 $ka=$expfmIDREKENING['0'];
		 $kb=$expfmIDREKENING['1'];
		 $kc=$expfmIDREKENING['2'];
		 $kd=$expfmIDREKENING['3'];
		 $ke=$expfmIDREKENING['4'];
		 $kf=$expfmIDREKENING['5'];
		
		$jml_barang = $_REQUEST['jml_barang'];
		$kuantitas = $_REQUEST['kuantitas'];
		$satuan = $_REQUEST['satuan'];
		$harga = $_REQUEST['harga'];		
		$jml_harga = $_REQUEST['jml_harga'];
		$uraian=$_REQUEST['uraian'];
		$jns_pelihara = $_REQUEST['jns_pelihara'];
		$kapitalisasi = $_REQUEST['kapitalisasi'];
		$ket = $_REQUEST['fmKET'];
		$tahun = $_REQUEST['fmTAHUN'];
		$tahun_akun = $_REQUEST['tahun_account'];
		$UID = $HTTP_COOKIE_VARS['coID'];		
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$nm_barang = $_REQUEST['fmNMBARANG'];
		$fmIDREKENING = $_REQUEST['kode_account'];
		$nm_account = $_REQUEST['nama_account'];
		
		/*$thn_perolehan =$_REQUEST['thn_perolehan'];		
		$lokasi = $_REQUEST['lokasi'];
		$idbi = $_REQUEST['idbi'];
		$noreg = $_REQUEST['noreg'];*/
		
		
		//-- validasi
		if($err=='' && ($jml_barang == '' || $jml_barang==0 ))$err = "Jumlah Barang belum diisi!";
		if($err=='' && ($kuantitas == '' || $kuantitas==0)) $err = "Volume / Kuantitas belum diisi!";
		if($err=='' && ($harga == '' || $harga==0)) $err = "Harga Satuan belum diisi!";
		if($err=='' && ($jml_harga == '' || $jml_harga==0)) $err = "Jumlah Harga belum di hitung!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
		//if($err=='' && $uraian == '')$err = "Uraian belum diisi!";
		//if($err=='' && $jns_pelihara == '')$err = "Jenis Pemeliharaan belum di pilih!";
		//if($err=='' && $kapitalisasi == '')$err = "Kapitalisasi belum di pilih!";
		//if($err=='' && $ket == '')$err = "Keterangan belum diisi!";
		$jml_harga = $jml_barang * $kuantitas * $harga  ;
		
		//$harga = str_replace(".","",$_REQUEST['harga']);		
		$jml_harga = str_replace(".","",$jml_barang*$kuantitas*$harga);		
		if($fmST==0){ //baru		
			if($err=='' && $fmIDBARANG == '')$err = "Nama Barang belum diisi!";
			if($err=='' && $fmIDREKENING == '')$err = "Nama Akun belum diisi!";
			if($err=='' && $jns_pelihara == '')$err = "Jenis Pemeliharaan belum di pilih!";
			//if($err=='' && $tahun == '')$err = "Tahun belum diisi!";
			
			/*
			//cek rkb u brg ini sudah ada
			$get = mysql_fetch_array( mysql_query(
				"select count(*) as cnt from rkpb where c='$c' and d='$d' and e='$e' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and tahun='$tahun' and noreg='$noreg'"				
			));
			if($err=='' && $get['cnt']==1) $err="RKPB dengan kode barang ini sudah ada!";
			*/
			
		}else{
			$old = mysql_fetch_array( mysql_query(
				"select * from rkpb where id='$id' "
			));
			
			if($err =='' && $old['stat']=='1') $err = "Gagal Simpan, RKPB sudah DKPB!";
			$cek .= $old['tahun']." == $tahun && ".$old['f']."==$f && ".$old['g']."==$g && ".$old['h']."==$h && ".$old['i']."==$i && ".$old['j']."==$j";
			if(!( $old['thn_perolehan']==$thn_perolehan && $old['noreg']==$noreg && $old['tahun']==$tahun && $old['f']==$f && $old['g']==$g && $old['h']==$h && $old['i']==$i && $old['j']==$j) ){
				/*
				//cek rkb u brg ini sudah ada
				$aqry = "select count(*) as cnt from rkpb where  ".
					" c='$c' and d='$d' and e='$e' ".
					"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ".
					"and tahun='$tahun' and noreg='$noreg' and thn_perolehan='$thn_perolehan'"	; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query(
					$aqry	
				));
				if($err=='' && $get['cnt']>0) $err="RKPB dengan kode barang ini sudah ada!";					
				*/
			}
			
			
		}
		
		
		
		
		if($err==''){
			if($e1=='' || $e1 == NULL) $e1= '001';
			if($fmST == 0){//baru
				
				$aqry = 
					"insert into $this->TblName_Edit ".
					"(jml_barang,kuantitas, harga,jml_biaya,satuan, ".
					"a1,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,kf,tahun,thn_akun, ".
					"uid,tgl_update, uraian, jns_pelihara,kapitalisasi,ket,nm_barang,nm_account) ".
					"values ".
					"('$jml_barang','$kuantitas','$harga','$jml_harga','$satuan',".
					"'$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$ka','$kb','$kc','$kd','$ke','$kf','$tahun','$tahun_akun',".
					"'$UID',now(), '$uraian', '$jns_pelihara','$kapitalisasi','$ket','$nm_barang','$nm_account')";
				
			}else{
				//a='$a',	b='$b',	c='$c', d='$d',	e='$e',
				//f='$f', g='$g', h='$h',	i='$i',	j='$j',
				//tahun='$tahun'
				
				//$get = $this->getJmlBrgBI_($old['c'],$old['d'],$old['e'],$old['f'],$old['g'],$old['h'],$old['i'],$old['j'],$old['tahun']);
		
				$aqry = 
					"update $this->TblName_Edit  ".
					"set ".
					"jml_barang='$jml_barang', ".
					"kuantitas='$kuantitas', ".
					"harga='$harga', ".
					"jml_biaya='$jml_harga', ".
					"satuan ='$satuan', ".
					"f ='$f',g ='$g',h ='$h',i ='$i',j ='$j', ".
					"k ='$ka',l ='$kb',m ='$kc',n ='$kd',o ='$ke',kf ='$kf', ".
					"tahun='$tahun',thn_akun='$tahun_akun',".
					"uraian='$uraian', jns_pelihara='$jns_pelihara',kapitalisasi='$kapitalisasi', ".
					"ket='$ket',nm_barang='$nm_barang',nm_account='$nm_account', ".
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
		//if($err=='' && $old['stat']=='1') $err = 'Data Tidak Bisa Dihapus, RKPB sudah DKB!';
		if($err=='' && $old['stat']!='0') $err = 'Data yang bisa di hapus , hanya yang masih status RKPB !';
		
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
		
		/*$aqry = "select jml_barang from rkpb_standar where 						
			c='".$c."' and d='$d' and e='$e' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' "; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));		
		*/
		
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
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".			*/
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rka.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rkadetail.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
		 	"<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/dkb.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rka.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rkadetail.js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rka.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rkadetail.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
		 	"<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/dkb.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rka.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/rkadetail.js' language='JavaScript' ></script>";
	}	
	
	function getStat(){
		//global $Main;
		$cbid = $_POST[$this->Prefix.'_cb'];
		$id = $cbid[0];		
		$aqry = "select * from $this->TblName where id='$id' ";
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->stat = $isi['stat'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}
	
	function SetFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd']; 	
		
		$this->form_fields = array(	
		/*
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
							),		*/
			'detailcari' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribi' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".Pilih()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closecari()' >";
		
		//$form = //$this->genForm();		
		$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function SetPilihCariBI(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$ids = $_REQUEST['cidBI'];
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
			
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
			$query_brg="select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'";
			$brg = mysql_fetch_array(mysql_query($query_brg)) ;
			$qry="select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'";
			$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
			$tmax = mysql_fetch_array(mysql_query($kueri1));
			$kueri="select * from ref_jurnal 
					where thn_akun = '".$tmax['thn_akun']."' 
					and ka='".$brg['ka']."' and kb='".$brg['kb']."' 
					and kc='".$brg['kc']."' and kd='".$brg['kd']."'
					and ke='".$brg['ke']."' and kf='".$brg['kf']."'"; 
					//echo "$query_brg";
					//echo "$kueri";
			$row=mysql_fetch_array(mysql_query($kueri));
						
			$content->plhkode_account =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
			$content->plhnama_account =$row['nm_account'];			
			$content->plhtahun_account = $row['thn_akun'];
			$content->plhIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->plhNMBARANG = $brg['nm_barang'];		
			
		}
		
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
			case 'formDKPB':{
				$get = $this->setFormDKPB();
				$json = FALSE;
				break;
			}
			case 'simpanDKPB' : {
				$get = $this->simpanDKPB();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'getsat': {
				$get = $this->getStat();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}			
			case 'formCari':{				
				$fm = $this->SetFormCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}			
			case 'pilihcaribi':{				
				$get= $this->SetPilihCariBI();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$rkpb = new RkpbObj();

?>