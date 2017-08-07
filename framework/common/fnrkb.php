<?php

class RkbObj extends DaftarObj2{
	var $Prefix = 'rkb';
	//var $SHOW_CEK = TRUE;	
	var $TblName = 'rkb';//view2_sensus';
	var $TblName_Hapus = 'rkb';
	var $TblName_Edit = 'rkb';
	var $KeyFields = array('id');
	var $FieldSum = array('jml_harga');
	var $SumValue = array('jml_harga');
	var $FieldSum_Cp1 = array( 7, 8, 8);
	var $FieldSum_Cp2 = array( 3, 3, 3);	
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Perencanaan Kebutuhan dan Penganggaran';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	
	var $fileNameExcel='rkbmd.xls';
	var $Cetak_Judul = 'DAFTAR RKBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	//var $row_params= " valign='top'";
	
	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/HrgSatPilih.js' language='JavaScript' ></script>".		
				"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	
	function setTitle(){
		return 'Rencana Kebutuhan Barang Milik Daerah (RKBMD)';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
		global $Main;
		if ($Main->VERSI_NAME=='JABAR') $persediaan = "| <a href='pages.php?Pg=perencanaanbarang_persediaan' title='Perencanaan Persediaan'>Persediaan</a> ";
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=rkb" title="Pengadaan">Pengadaan</a> |				
				<a href="pages.php?Pg=rkpb" title="Pemeliharaan">Pemeliharaan</a>  |  
				<a href="pages.php?Pg=rencana_pemanfaatan" title="Pemanfaatan">Pemanfaatan</a>  |
				<a href="pages.php?Pg=rpebmd" title="Pemindahtanganan">Pemindahtanganan</a>  |
				<a href="pages.php?Pg=rphbmd" title="Penghapusan">Penghapusan</a> '.
				$persediaan.
				  '&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=rkb" title="Rencana Kebutuhan Barang Milik Daerah">RKBMD</a> |	
				<a href="pages.php?Pg=rekaprkb" title="Rekap Rencana Kebutuhan Barang Milik Daerah">Rekap RKBMD</a> |
				<a href="pages.php?Pg=rekaprkb_skpd" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKBMD (SKPD)</a>  |	
				<a href="pages.php?Pg=rka" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKA</a> | 		
				<a href="pages.php?Pg=dkb" title="Daftar Kebutuhan Barang Milik Daerah">DKBMD</a>  |  
				<a href="pages.php?Pg=rekapdkb" title="Rekap Daftar Kebutuhan Barang Milik Daerah">Rekap DKBMD</a>  |  
				<a href="pages.php?Pg=rekapdkb_skpd" title="Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap DKBMD (SKPD)</a>
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
				<th class='th01' width=150>Nama Barang / Nama Akun</th>
				<th class='th01' >Merk / Type / Ukuran / Spesifikasi </th>
				<th class='th01' >Jumlah </th>
				<th class='th01' >Satuan</th>
				<th class='th01' >Harga Satuan (Rp)</th>
				<th class='th01' >Jumlah Harga (Rp)</th>				
				<th class='th01' >Informasi Standar</th>				
				<th class='th01' >Keterangan</th>							
				<th class='th01' >Status</th>							
				
				</tr>
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS;
		
		$Koloms = array();
		
		//cari total bi
		$totalbi = '';
		if($isi['stat']==1){
			$status = 'DKB';
		}elseif($isi['stat']==2){
			$status = 'RKA';
		}else{
			$status = '';
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
		
		$info = "<table border=0>
					<tr>
						<td>Harga</td>
						<td>:</td>
						<td>".number_format($isi['harga'], 2, ',', '.')."</td>
					</tr>
					<tr>
						<td>Kebutuhan</td>
						<td>:</td>
						<td>".number_format( $isi['jml_max'] , 0,',','.')."</td>
					</tr>
					<tr>
						<td>Jumlah BI</td>
						<td>:</td>
						<td>".number_format( $isi['jml_bi'] , 0,',','.')."</td>
					</tr>
				</table>";
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'<br>'.
			$isi['nm_barang'].'/<br>'.
			$isi['k'].'.'.$isi['l'].'.'.$isi['m'].'.'.$isi['n'].'.'.$isi['o'].'.'.$isi['kf'].'<br>'.
			$isi['nm_account']		
		);		
		$Koloms[] = array('', $isi['merk_barang'] );
		$Koloms[] = array("align='center'", $isi['jml_barang']);
		$Koloms[] = array("align='center'", $isi['satuan']);
		$Koloms[] = array("align='right'", number_format( $isi['harga'] , 2,',','.') );		
		$Koloms[] = array("align='right'", number_format( $isi['jml_harga'] ,2,',','.') );
		$Koloms[] = array("align='left'", $info);
		$Koloms[] = array('', $isi['ket'].'<br>'.$nmopd);
		$Koloms[] = array("align='center'",  $status);
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
		
		$arrStatus = array(
					array('1','RKB'),
					array('2','RKA'),
					array('3','DKB'),
				);
			

		$fmFiltThnAnggaran=$_COOKIE['coThnAnggaran'];
		$fmFiltStatus=$_REQUEST['fmFiltStatus'];
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			array('3','Nama Barang'),		
		);
			//this name selectbox for kode barang & nama barang
		$fmPILCARI = cekPOST('fmPILCARI'); //get name select box 
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE'); //get value textfield
		
		  //Order untuk Order 1
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
					boxFilter( 'Tampilkan : '.	"<input type='text' value='$fmFiltThnAnggaran' id='fmFiltThnAnggaran' name='fmFiltThnAnggaran' readonly>"
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
			case '2': $fmFiltStatus='2'; break;
			case '3': $fmFiltStatus='1'; break;
		}
		if(!empty($_REQUEST['fmFiltStatus'])) $arrKondisi[]= "stat = '$fmFiltStatus'";
		if(!empty($fmFiltThnAnggaran) )  $arrKondisi[] = "tahun='$fmFiltThnAnggaran'";
		
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
	
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$fmFiltThnAnggaran=$_COOKIE['coThnAnggaran'];
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='fmFiltThnAnggaran' name='fmFiltThnAnggaran' value='$fmFiltThnAnggaran'>".
				//"<input type='hidden' id='fmNMBARANG' name='fmNMBARANG' value='$fmNMBARANG'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
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
			"<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/dkb.js' language='JavaScript' ></script>".
			$scriptload;
	}	
	
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'RKB Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit RKB')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus RKB')."</td>".
			"<td>".genPanelIcon("javascript:RKA.Baru(0)","new_f2.png","RKA", 'RKA')."</td>".
			"<td>".genPanelIcon("javascript:dkb.Baru()","new_f2.png","DKB", 'DKB')."</td>";
			
	}
	
	function setMenuView(){
		return //"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHit()","print_f2.png","Cetak", 'Cetak Nota Hitung')."</td>
					"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
					<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>					<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>";
					
	}	
	
	function setFormBaru(){
		global $Main;
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		$dt['tahun']=$_COOKIE['coTahunAnggaran'];
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
		
		$aqry = "select * from $this->TblName where id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//echo sizeof($cbid).' '.$cbid[0] ;
		//print_r($cbid);
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']
		);
	}
	
	function setForm($dt){	
		//global $SensusTmp;
		global $fmIDBARANG, $fmIDREKENING,$Main;
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
			
			$thn = $dt['tahun']-1;
			$aqry = "select sum(jml_barang) as jml_brg_sblm from rkb where tahun=$thn and c='{$dt[c]}' 
					and d='{$dt[d]}' and e='{$dt[e]}' and e1='{$dt[e1]}' and f='{$dt[f]}' and g='{$dt[g]}' 
					and h='{$dt[h]}' and i='{$dt[i]}' and j='{$dt[j]}'";
			$row = mysql_fetch_array(mysql_query($aqry));
			$dt['jml_brg_sblm'] = $row['jml_brg_sblm']==''?'0':$row['jml_brg_sblm'];
				
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
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
		$fmNMBARANG = $dt['nm_barang'];
		$fmIDREKENING = $dt['k']==''? '' : $dt['k'].'.'.$dt['l'].'.'.$dt['m'].'.'.$dt['n'].'.'.$dt['o'].'.'.$dt['kf'];//'5.1.1.01.05';
		$fmNMREKENING = $dt['nm_account'];
		$fmTAHUN = $dt['tahun']==''?  $_COOKIE['coThnAnggaran'] : $dt['tahun'] ; //def tahun = 2012
		
		
		$vtahun = //$this->form_fmST==1? $fmTAHUN :
			'<input type="text" id="fmTAHUN" name="fmTAHUN" value="'.$fmTAHUN.'" size="4" maxlength=4 onkeypress="return isNumberKey(event)" readonly>';
		
		$vkdbarang = //$this->form_fmST==1?	$fmIDBARANG.' - '.$dt['nm_barang'] :
			cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"readonly","$DisAbled");
					
		$vkdrekening = //$this->form_fmST==1?	$fmIDREKENING.' - '.$dt['nm_account'] :
			cariInfo("adminForm","pages/01/cariakun1.php","pages/01/cariakun2.php","fmIDREKENING","fmNMREKENING",'readonly'); 
		
		$vjmlbi = //$this->form_fmST==1?	$dt['jml_bi'] :
			'<input type="text"  readonly="true" id="jmlbi" name="jmlbi" value="'.$dt['jml_bi'].'" >&nbsp;'
			."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkb.cekJmlBrgBI()' title='Cek Jumlah Inventaris (Tahun Sebelumnya) dan Jumlah Standar'>"
			;
		
		$vstandar = /*$this->form_fmST==1?$dt['jml_standar'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Info Maks Rencana : '.$dt['jml_max'] :*/
			'<input type="text"  readonly="true" id="standar" name="standar" value="'.$dt['jml_standar'].'" >&nbsp;'
			."<input type='button' id='btcek_jmlstandar' name='btcek_jmlstandar' value='Cek' onclick='rkb.cekJmlBrgBI()' title='Cek Jumlah Standar Kebutuhan'>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Info Maks Rencana : ".
			"<input name=\"jml_max\" id='jml_max' type=\"text\" value='".$dt['jml_max']."' readonly/>"
			;
			
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<input type='text'  readonly='true' id='cnt_jmlharga' name='cnt_jmlharga' value='".number_format($dt['jml_harga'],2,',','.')."' style='text-align:right;'>
			<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			<!--<span id='cnt_jmlharga'>".number_format($dt['jml_harga'],0,',','.')."</span>-->";
				 
			
		
		$title = $this->form_fmST == 1? 'Rencana Kebutuhan Barang Milik Daerah - Edit' : 'Rencana Kebutuhan Barang Milik Daerah - Baru';
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
			'nm_barang' => array(  'label'=>'Nama Barang', 
				'value'=> $vkdbarang,
				'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'nm_akun' => array( 'label'=>'Nama Akun', 
				'value'=>$vkdrekening,
				'type'=>''  
			),
			'jmlbi' => array(  'label'=>'Jumlah Inventaris', 
				'value'=> $vjmlbi				
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			'divJmlBrgBi' => array(  'label'=>'', 
				'value'=> "<div id='divJmlBrgBi' name='divJmlBrgBi'></div>"				
				, 'type'=>'' , 'pemisah'=>' '
			),
			
			'standar' => array(  'label'=>'Jumlah Standar Kebutuhan', 
				'value'=> $vstandar
					
					//."<input type='button' id='btcek_jmlbi' name='btcek_jmlbi' value='Cek' onclick='rkb.getStandar()' title='Cek Jumlah Standar'>"
				,'labelWidth'=>170, 'row_params'=>"height='21'", 'type'=>'' 
			),
			
			'jml_brg_sblm' => array(  'label'=>'Jumlah Rencana Tahun Sebelum', 
				'value'=> 
					"<input name=\"jml_brg_sblm\" id='jml_brg_sblm' type=\"text\" value='".$dt['jml_brg_sblm']."' readonly/>", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
			
			'jml_barang' => array(  'label'=>'Jumlah Rencana Barang', 
				'value'=> 
					"<input name=\"jml_barang\" id='jml_barang' type=\"text\" value='".$dt['jml_barang']."' />".
					"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Satuan Barang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ".
					"<input name=\"satuan\" id='satuan' type=\"text\" value='".$dt['satuan']."' />", 
					//inputFormatRibuan("jml_barang", '',$dt['jml_barang']) ,
				'type'=>'' 
			),
					
			'harga' => array( 'label'=>'Harga Satuan Barang', 
				'value'=>"<input name=\"harga\" id=\"harga\" type=\"text\" value='".$dt['harga']."' onkeypress='return isNumberKey(event)' style='text-align:right;'/>&nbsp;".
				//inputFormatRibuan("harga", '',$dt['harga']).
				"<input type='button' value='Info' onclick=\"".$this->Prefix.".Info()\">" ,
				'type'=>'' 
			),
			'jml_harga' => array( 'label'=>'Jumlah Harga', 
				'value'=> $vjmlharga ,
				'type'=>'' 
			),
			'merk' => array(  'label'=>'Merk / Type / Ukuran / Spesifikasi', 
				'value'=> "<textarea name=\"fmMEREK\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;'>".$dt['merk_barang']."</textarea>", 
				//'params'=>"valign='top'",
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
	
	function setFormDKB(){
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
		
		//-- validasi
		if($err =='' && ($jml_barang==''||$jml_barang==0)) $err = "Jumlah Barang Belum Diisi!";
		if($err =='' && ($harga==''||$harga==0)) $err = "Harga Satuan Belum Diisi!";
		if($err =='' && $rkb['id']=='') $err = "RKB Tidak Ada!";
		if($err =='' && $rkb['stat']=='1') $err = "DKB Sudah Ada!";
		if($err =='' && $rkb['jml_barang']<$jml_barang) $err = "Jumlah Barang Tidak Lebih Besar dari RKB!";
		
		if($err == ''){
			//$merk_barang = $rkb['merk_barang'];
			//$harga = $rkb['harga'];
			$satuan = $rkb['satuan'];
			//$ket = $rkb['ket'];
			$a = $rkb['a']; $b = $rkb['b'];	$c = $rkb['c']; $d = $rkb['d'];	$e = $rkb['e'];$e1 = $rkb['e1'];
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
		
		
		$a = $Main->DEF_PROPINSI; //$_REQUEST[''];
		$b = $Main->DEF_WILAYAH; //$_REQUEST['merk_barang'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		
		$tahun = $_REQUEST['fmTAHUN'];
		
		$nm_barang = $_REQUEST['fmNMBARANG'];
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$f = $fmIDBARANG['0'].$fmIDBARANG['1'];
		$g = $fmIDBARANG['3'].$fmIDBARANG['4'];
		$h = $fmIDBARANG['6'].$fmIDBARANG['7'];
		$i = $fmIDBARANG['9'].$fmIDBARANG['10'];
		$j = $fmIDBARANG['12'].$fmIDBARANG['13'].$fmIDBARANG['14'];
		
		$nm_account = $_REQUEST['fmNMREKENING'];
		$fmIDREKENING = explode(".", $_REQUEST['fmIDREKENING']);		
		$k = $fmIDREKENING['0'];//.$fmIDREKENING['1'];
		$l = $fmIDREKENING['1'];//.$fmIDREKENING['3'];
		$m = $fmIDREKENING['2'];//.$fmIDREKENING['5'];
		$n = $fmIDREKENING['3'];
		$o = $fmIDREKENING['4'];
		$kf = $fmIDREKENING['5'];

		$jml_bi = $_REQUEST['jmlbi'];
		$jml_standar = $_REQUEST['standar'];
		$jml_max = $_REQUEST['jml_max'];
		$jml_barang = $_REQUEST['jml_barang'];
		$satuan = $_REQUEST['satuan'];
		$harga = $_REQUEST['harga'];		
		$jml_harga = $_REQUEST['jml_harga'];
		$merk_barang = $_REQUEST['fmMEREK'];
		$ket = $_REQUEST['fmKET'];
		
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		$tot_harga  = $harga * $jml_barang;
		
		//-- validasi		
		if($err=='' && ($jml_barang == '' || $jml_barang==0 ))$err = "Jumlah Barang belum diisi!";
		if($err=='' && $harga == '')$err = "Harga Satuan belum diisi!";
		if($err=='' && $satuan == '')$err = "Satuan belum diisi!";
		
		if($fmST==0){ //baru
		
			if($err=='' && $fmIDBARANG == '')$err = "Kode Barang belum diisi!";
			if($err=='' && $fmIDREKENING == '')$err = "Kode Rekening belum diisi!";
			//if($err=='' && $tahun == '')$err = "Tahun belum diisi!";
			/*
			//cek rkb u brg ini sudah ada
			$get = mysql_fetch_array( mysql_query(
				"select count(*) as cnt from rkb where c='$c' and d='$d' and e='$e' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and tahun='$tahun'"				
			));
			if($err=='' && $get['cnt']>1) $err="RKB dengan kode barang ini sudah ada!";
			*/
			/*
			//get jml bi
			$get = mysql_fetch_array( mysql_query(
				"select count(*) as cnt from buku_induk where c='$c' and d='$d' and e='$e' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<'$tahun'"				
			));
			$jmlbi = $get['cnt'];
			//get jml standar
			$get = mysql_fetch_array( mysql_query(
				"select jml_barang from rkb_standar where c='$c' and d='$d' and e='$e' ".
				"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'  "				
			));
			$jml_standar=$get['jml_barang'];
			//cek jml bi standar
			if($err=='' && $jml_standar>0 && $jml_standar < $jmlbi+$jml_barang ) 
				$err = "Jumlah RKB dan Jumlah BI tidak lebih besar dari Jumlah Standar!";
			*/
			$getJmlBrgBI = $this->getJmlBrgBI_($c,$d,$e,$e1,$f,$g,$h,$i,$j,$tahun);
			if($err=='' && $get->content->jmlstandar>0 && $get->content->jmlstandar < $get->content->jmlKondBaik+$jml_barang ) 
				$err = "Jumlah RKB dan Jumlah Kondisi Baik tidak lebih besar dari Jumlah Standar!";
				
		}else{
			$old = mysql_fetch_array( mysql_query(
				"select * from rkb where id='$id' "
			));
			
			if($err =='' && $old['stat']=='1') $err = "Gagal Simpan, RKB sudah DKB!";
			/*
			$cek .= $old['tahun']." == $tahun && ".$old['f']."==$f && ".$old['g']."==$g && ".$old['h']."==$h && ".$old['i']."==$i && ".$old['j']."==$j";
			if(!($old['tahun'] == $tahun && $old['f']==$f && $old['g']==$g && $old['h']==$h && $old['i']==$i && $old['j']==$j) ){
				//cek rkb u brg ini sudah ada
				$aqry = "select count(*) as cnt from rkb where  ".
					" c='$c' and d='$d' and e='$e' ".
					"and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and tahun<'$tahun'"	; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query(
					$aqry	
				));
				if($err=='' && $get['cnt']>0) $err="RKB dengan kode barang ini sudah ada!";					
				
			}
			*/
			
			//cek jml bi standar
			$get = $this->getJmlBrgBI_($old['c'],$old['d'],$old['e'],$old['e1'],$old['f'],$old['g'],$old['h'],$old['i'],$old['j'],$old['tahun']);
		
			if($err=='' && $get->content->jmlstandar>0 && $get->content->jmlstandar < $get->content->jmlKondBaik+$jml_barang ) 
				$err = "Jumlah RKB dan Jumlah Kondisi Baik tidak lebih besar dari Jumlah Standar!";
			//$cek .= "jmlstandar=".$old['jml_standar'].", jmlbi=".$old['jmlbi'].", jml brg=".$jml_barang;
			
			
		}
		
		
		
		
		if($err==''){
			if($fmST == 0){//baru
				$aqry = 
					"insert into $this->TblName 
					(merk_barang,jml_barang,jml_bi,jml_standar, harga,satuan,jml_harga,ket,a,b,c,d,e,e1,f,g,h,i,j,k,l,m,n,o,kf,tahun,
					uid,tgl_update,nm_barang,nm_account,jml_max) 
					values 
					('$merk_barang','$jml_barang', '$jml_bi', '$jml_standar','$harga','$satuan','$tot_harga','$ket',
					'$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$kf','$tahun',
					'$UID',now(),'$nm_barang','$nm_account','$jml_max')";
				
			}else{
				//a='$a',	b='$b',	c='$c', d='$d',	e='$e',
				//f='$f', g='$g', h='$h',	i='$i',	j='$j',
				//tahun='$tahun'
				
				
				$aqry = 
					"update $this->TblName_Edit  ".
					"set merk_barang='$merk_barang', ".
					"jml_barang='$jml_barang', ".
					"harga='$harga', ".
					"jml_harga='$tot_harga', ".
					"satuan ='$satuan', ".
					"jml_bi='$jml_bi', ".
					"jml_standar='$jml_standar', ".
					"ket='$ket', ".
					"tahun='$tahun',".
					"f='$f', g='$g',	h='$h',	i='$i',	j='$j', ".
					"k='$k', l='$l',	m='$m',	n='$n',	o='$o', kf='$kf', ".
					"uid='$UID', tgl_update= now(),
					nm_barang='$nm_barang', nm_account='$nm_account', jml_max='$jml_max' ".
					"where id='$id' ";
				
			}
			$cek .= $aqry;
			$qry = mysql_query($aqry);
		}		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	function Hapus($ids){
		$err=''; $cek='';
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$err = $this->Hapus_Validasi($ids[$i]);
			
			if($err ==''){
				$get = $this->Hapus_Data($ids[$i]);
				$err = $get['err'];
				$cek.= $get['cek'];
				if ($errmsg=='') {
					$after = $this->Hapus_Data_After($ids[$i]);
					$err=$after['err'];
					$cek=$after['cek'];
				}
				if ($err != '') break;
				 				
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	} 
	
	function Hapus_Data($id){//id -> multi id with space delimiter
		$err = ''; $cek='';
		$KeyValue = explode(' ',$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		//$Kondisi = 	"Id='".$id."'";
		
		$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
		$qry = mysql_query($aqry);
		if ($qry==FALSE){
			$err = 'Gagal Hapus Data';
		}
		
		return array('err'=>$err,'cek'=>$cek);
	}
	function Hapus_Data_After($id){
		$err = ''; $content=''; $cek='';
		
		return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
	}
	
	function Hapus_Validasi($id){
		$err ='';
		//$KeyValue = explode(' ',$id);
		$old = mysql_fetch_array(mysql_query(
			"select * from $this->TblName where id ='$id' "
		));
		if($err=='' && $old['stat']=='1') $err = 'Data Tidak Bisa Dihapus karena sudah DKB';
		if($err=='' && $old['stat']=='2') $err = 'Data Tidak Bisa Dihapus karena sudah RKA';
		
		return $err;
	}
	
	function getJmlBrgBI_($c,$d,$e,$e1,$f,$g,$h,$i,$j,$tahun){ //& standar
		global $Main;
		$cek = ''; $err=''; $content=''; 
		$jj="0".$j;
		//jml bi		
		/*$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' and thn_perolehan<'$tahun'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlbi = $isi['cnt'];*/
		
		$aqry = "select count(jml_barang) as jml_bi from buku_induk where 
			c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and year(tgl_buku)<='$tahun'
			"; $cek .="jml_bi=".$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlbi = $isi['jml_bi'];
		
		$aqry = "select jml_barang as jml_max from ref_std_kebutuhan where 
				 c='".$c."' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"; $cek .="jmlstandar=".$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jmlstandar = $isi['jml_max']==''?'0':$isi['jml_max'];
		
		$jmlbi=$content->jmlbi;
		$jmlstandar=$content->jmlstandar;
		$content->info_max=0;
		if($jmlbi>$jmlstandar){
		//	$content->info_max = $jmlbi-$jmlstandar;
		}elseif($jmlbi<$jmlstandar){
			$content->info_max = $jmlstandar-$jmlbi;
		}
		
		 		
		$thn = $tahun-1;
		$aqry = "select sum(jml_barang) as jml_brg_sblm from rkb where tahun='$thn' and c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'";
		$cek .="jml_brg_sblm=".$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->jml_brg_sblm = $isi['jml_brg_sblm']==''?'0':$isi['jml_brg_sblm'];
		
		//jml kond baik
		/*$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' 
			and thn_perolehan<'$tahun' and kondisi='1'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		//$content->jmlKondBaik = $isi['cnt'];
		
		//jml kond kurang baik
		$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' 
			and thn_perolehan<'$tahun' and kondisi='2'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		//$content->jmlKondKB = $isi['cnt'];
		
		//jml kond kurang baik
		$aqry = "select count(*) as cnt from buku_induk where 
			(status_barang<>3 and status_barang<>4 and status_barang<>5) 
			and c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' 
			and thn_perolehan<'$tahun' and kondisi='3'"; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));
		//$content->jmlKondRB = $isi['cnt'];
		
		//standar
		$aqry = "select jml_barang from rkb_standar where 						
			c='".$c."' and d='$d' and e='$e' and e1='$e1' 
			and f='".$f."' and g='$g' and h='$h' and i='$i' and j='$j' "; $cek .=$aqry;
		$isi = mysql_fetch_array(mysql_query($aqry));				
		//$content->jmlstandar = $isi['jml_barang'];
		
		*/
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
			
			case 'formInfo':{								
				$this->setFormInfo();				
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
			case 'getJmlBrgStandar':{
				$get = $this->getJmlBrgStandar();
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
			
			case 'Hitung':{
				$cek=''; $err=''; $content='';
			
				$jml_barang=$_REQUEST['jml_barang'];
				$harga=str_replace(".","",$_REQUEST['harga']);
				
				$jmlharga=$jml_barang*$harga;
				$cnt_jmlharga=number_format($jmlharga,0,',','.');

				$cek = $cek; $err = $err; $content=$cnt_jmlharga; 
				break;
			}
			
			case 'hapus':{
				$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->Hapus($cbid);
				$err= $get['err']; 
				$cek = $get['cek'];
				$json=TRUE;	
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	
}
$rkb = new RkbObj();

?>