<?php

class RekapRkpbObj extends DaftarObj2{
	var $Prefix = 'rekaprkpb';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'v1_rkpb';//view2_sensus';
	var $TblName_Hapus = 'rkpb';
	var $TblName_Edit = 'rkpb';
	var $KeyFields = array('id');
	var $FieldSum = array(	'jml_barang', 'jml_biaya');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 5, 6,6);
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $totalCol = 6; //total kolom daftar
	var $fieldSum_lokasi = array( 5,6); 
	var $withSumAll = FALSE;
	var $withSumHal = TRUE;
	//var $WITH_HAL = FALSE;
	var $totalhalstr = '<b>Total';
	
	//var $FormName = 'Sensus_form';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
	
	var $PageTitle = 'Perencanaan Kebutuhan dan Penganggaran';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';
	var $cetak_xls = FALSE;
	var $fileNameExcel='rekaprkpbmd.xls';
	var $Cetak_Judul = 'REKAP RKPBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setTitle(){
		return 'Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah ';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=rkb&SPg=01" title="Rencana Kebutuhan Barang Milik Daerah">RKBMD</a> |				
				<a href="pages.php?Pg=dkb" title="Daftar Kebutuhan Barang Milik Daerah">DKBMB</a>  |  
				<a href="pages.php?Pg=rkpb" title="Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">RKPBMD</a>  |
				<a href="pages.php?Pg=dkpb" title="Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">DKPBMD</a>  |
				
				<a href="pages.php?Pg=rekaprkb" title="Rekap Rencana Kebutuhan Barang Milik Daerah">Rekap RKBMD</a> |
				<a href="pages.php?Pg=rekapdkb" title="Rekap Daftar Kebutuhan Barang Milik Daerah">Rekap DKBMD</a>  |  
				<a style="color:blue;" href="pages.php?Pg=rekaprkpb" title="Rekap Rencana Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap RKPBMD</a> |  
				<a href="pages.php?Pg=rekapdkpb" title="Rekap Daftar Kebutuhan Pemeliharaan Barang Milik Daerah">Rekap DKPBMD</a>  
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setMenuEdit(){
		/*$buttonEdits = array(
			array('label'=>'SPPT Baru', 'icon'=>'new_f2.png','fn'=>"javascript:".$this->Prefix.".Baru()" )
		);*/
		return '';
			/*"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";*/
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}
	function setMenuView(){
		return 
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","SPPT",'Cetak SPPT')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Cetak',"Cetak Rekap")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel",'Export ke Excell')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","edit_f2.png","Default",'Setting Default')."</td>";		
	}
	function genRowSum_setTampilValue($i, $value){
		if ($i==0){
			return number_format($value,0, ',', '.');	
		}else{
			return number_format($value,2, ',', '.');		
		}
		//return $value;
	}
	function genRowSum_setColspanTotal($Mode, $colspanarr ){
		$TotalColSpan1 = 4;
		return $TotalColSpan1;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		
		
		//data cari ----------------------------		
		/*$arrCari = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
		*/	
		
		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		if ($fmFiltThnAnggaran!='' && $fmFiltThnAnggaran!=$_COOKIE['coTahunAnggaran']){
			setcookie("coTahunAnggaran",$fmFiltThnAnggaran);
		} else {
			$fmFiltThnAnggaran=$_COOKIE['coTahunAnggaran'];
		}
		
		//data order ------------------------------
		/*$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			array('3','Kode Rekening'),		
		);*/
		
		//thn terakhir
		/*$get = mysql_fetch_array(mysql_query(
			"select max(tahun) as maxthn from $this->TblName "
		));*/
		//$fmFiltThnAnggaran = $get['maxthn'];
			
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
			/*genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').*/
			genFilterBar(
				array(	
					boxFilter( 
						'Tampilkan : '.	
						"<input type='text' id='fmFiltThnAnggaran' name='fmFiltThnAnggaran' value='$fmFiltThnAnggaran' >"
					 	/*genComboBoxQry(
						'fmFiltThnAnggaran',
						$fmFiltThnAnggaran,
						"select tahun from $this->TblName group by tahun desc ",
						'tahun', 
						'tahun',
						'Tahun Anggaran'
						)*/
					)
					/*.
					$Main->batas.
					boxFilter("Kode Rekening &nbsp;&nbsp;<input type='text' value='' id='kode_rekening' name='kode_rekening' >")*/
					
				),$this->Prefix.".refreshList(true)",TRUE
			)/*.
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC1' name='fmDESC1' value='1'>Desc.".
					cmbArray('fmORDER2',$fmORDER2,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC2' name='fmDESC2' value='1'>Desc.".
					cmbArray('fmORDER3',$fmORDER3,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC3' name='fmDESC3' value='1'>Desc."
				),				
				$this->Prefix.".refreshList(true)")*/;
		
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
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');			$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);		//$arrKondisi[] = $tes;
		//$arrKondisi[] = $tes;
		//*/
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){			
			case '1': $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " nm_barang like '%$fmPILCARIVALUE%'"; break;
		}
		$fmFiltThnAnggaran = cekPOST('fmFiltThnAnggaran','');
//		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		if ($fmFiltThnAnggaran!='' && $fmFiltThnAnggaran!=$_COOKIE['coTahunAnggaran']){
			setcookie("coTahunAnggaran",$fmFiltThnAnggaran);
		} else {
			$fmFiltThnAnggaran=$_COOKIE['coTahunAnggaran'];
		}
		if(!empty($fmFiltThnAnggaran) ) {
			$arrKondisi[] = "tahun='$fmFiltThnAnggaran'";
		}else{
			//$fmFiltThnAnggaran = '2013';
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
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>".
				//$Checkbox.
				"<th class='th01' width='40'>Golongan</th>
				<th class='th01' width='40'>Kode Bidang Barang</th>
				<th class='th01' width=''>Nama Bidang Barang</th>				
				<th class='th01' width='150'>Jumlah Barang</th>
				<th class='th01' width='150'>Jumlah Harga<br>(Rp)</th>				
				
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
		
		
		/*
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
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
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		*/
		
		if($isi['g']=='00'){
			$nmbarang = "<b>".$isi['nm_barang'];	
			$jmlbarang = "<b>".number_format( $isi['jml_barang'],0,',','.');
			$jmlharga = "<b>".number_format( $isi['jml_harga'],2,',','.');
		}else{
			$nmbarang = "&nbsp;&nbsp;&nbsp;&nbsp;".$isi['nm_barang'];	
			$jmlbarang = number_format( $isi['jml_barang'],0,',','.');
			$jmlharga = number_format( $isi['jml_harga'],2,',','.');
		}
		
		
		$Koloms[] = array('align=right', $no.'.' );
		//if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		if ($this->cetak_xls){
		$Koloms[] = array("align='center'","<div class='nfmt5'>". $isi['f']."</div>" );		
		$Koloms[] = array("align='center'","<div class='nfmt5'>". $isi['g']."</div>" );
			
		} else {
		$Koloms[] = array("align='center'", $isi['f'] );		
		$Koloms[] = array("align='center'", $isi['g'] );
			
		}		
		$Koloms[] = array("align='left'", $nmbarang);		
		$Koloms[] = array("align='right'", $jmlbarang  );		
		$Koloms[] = array("align='right'", $jmlharga  );		
		return $Koloms;
	}
	
	
	function genDaftar($Kondisi_='', $Order='', $Limit='', $noAwal = 0, $Mode=1){		
		$cek =''; $err='';
		$tbl = 'rkpb';
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class=$TblStyle border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		/*$aqry = "select aa.*, bb.jml_barang, bb.jml_harga from ref_barang aa 
left join rkb bb on aa.f=bb.f 
where aa.g='00' ";*/
		$aqry = "select *from ref_barang where g='00'";
		$qry = mysql_query($aqry);
		while ( $isi=mysql_fetch_array($qry)){
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
				
			
			//jmlharga, jmlbarang ----------------------------
			$Kondisi = $Kondisi_==''? " where f='".$isi['f']."'" : $Kondisi_." and f='".$isi['f']."'"; $cek .= 'kondisi2= '.$Kondisi.";";
			$get = mysql_fetch_array(mysql_query(
				"select sum(jml_barang) as jmlbrg, sum(jml_biaya) as jmlhrg from $tbl ".$Kondisi// and g='".$isi['g']."' "
			));
			$isi['jml_barang'] = $get['jmlbrg'];
			$isi['jml_harga'] = $get['jmlhrg'];
			$isi['jml_biaya'] = $get['jmlhrg'];
			
			//sum halaman
			for ($i=0; $i< sizeof($this->FieldSum) ; $i++) {
				$this->SumValue[$i] += $isi[$this->FieldSum[$i]];
			}
			
			//---------------------------
			$rowatr_ = $RowAtr." valign='top' id='$cb' value='".$isi['Id']."'";
			$bef= $this->setDaftar_before_getrow(
					$no,$isi,$Mode, $TampilCheckBox,  
					$rowatr_,
					$ColStyle
					);
			$ListData .= $bef['ListData'];
			$no = $bef['no'];
			//get row
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi);
			
			
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';	//sleep(2); //tes
			}
			
			
			//g --------------------
			$ListData2 = ''; $Koloms2=array();
			
			$aqryg  = "select * from ref_barang where f='".$isi['f']."' and g<>'00' and h='00' ";
			$qryg = mysql_query($aqryg);
			while($isig = mysql_fetch_array($qryg)){
				$no++;
				if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";			
				$rowatr_ = $RowAtr." valign='top' id='$cb' ";
				//jmlharga, jmlbarang
				$Kondisi2 = $Kondisi_==''? "where f='".$isig['f']."' and g='".$isig['g']."' ": 
				$Kondisi_." and f='".$isig['f']."' and g='".$isig['g']."'" ; $cek .= 'kondisi2b= '.$Kondisi2.";";			
				$get = mysql_fetch_array(mysql_query(
					"select sum(jml_barang) as jmlbrg, sum(jml_biaya) as jmlhrg from $tbl ".$Kondisi2
				));
				$isig['jml_barang'] = $get['jmlbrg'];
				$isig['jml_harga'] = $get['jmlhrg'];
			
				$Koloms2 = $this->setKolomData($no,$isig,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
				$list_row = genTableRow($Koloms2, $rowatr_, $ColStyle);		
				$ListData .= $list_row;
				$cb++;			
			}
			$ListData;
			
			
			
		}
		
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}		
		$ContentSum = $this->genRowSum($ColStyle, $Mode, $SumHal['sum']);		
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>";
		if ($Mode==3) {	//flush
			echo $ListData;			
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}
	
	
}
$rekaprkpb = new RekapRkpbObj();

?>