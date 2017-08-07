<?php

class PembukuanObj5 extends DaftarObj2{
	var $Prefix = 'Pembukuan5'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'ref_barang'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 6,6,6);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 7; //total kolom daftar
	var $fieldSum_lokasi = array( 11);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='pembukuan.xls';
	var $Cetak_Judul = 'Pembukuan';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Pembukuan';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $jml_data=0;
	var $totBrgAset = 0;
	var $totHrgAset = 0;
	
	function genRowSum(){
		//hal

		$ContentTotalHal=''; $ContentTotal='';
		return $ContentTotalHal.$ContentTotal;
	}
function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class='$TblStyle' border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		/*
		$qry = mysql_query($aqry);
		$numrows = mysql_num_rows($qry); $cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
					
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				
			
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
				
			
			
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
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
			}
		}
		
		}
		*/
		$ListData .= $this->setDaftar_After($no, $ColStyle);
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		//$SumHal = $this->genSumHal($Kondisi);
		$ContentSum = $this->genRowSum($ColStyle,  $Mode, 
			$SumHal['sums']
		);
		/*$TampilTotalHalRp = number_format($TotalHalRp,2, ',', '.');		
		$TotalColSpan = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
		$ContentTotalHal =
			"<tr>
				<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>
				<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
				<td class='$ColStyle' colspan='4'></td>
			</tr>" ;
			
		$ContentTotal = 
				"<tr>
					<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total</td>
					<td class='$ColStyle' align='right'><b><div  id='cntDaftarTotal'>".$SumHal['sum']."</div></td>
					<td class='$ColStyle' colspan='4'></td>
				</tr>" ;
		
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			$ContentTotalHal='';			
		}
		$ContentSum=$ContentTotalHal.$ContentTotal;
		*/
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>"
			.$vKondisi_old
			;
		if ($Mode==3) {	//flush
			echo $ListData;	
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}

	function genSumHal(){

		return array('sum'=>0, 'hal'=>0, 'sums'=>0, 'jmldata'=>0, 'cek'=>'' );
	}	
	function setTitle(){
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		return 'Pembukuan ';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>Pembukuan";
		if ($this->cetak_xls==TRUE)
		{
			$judul="<table width='100%'><tr><td colspan=6>Pembukuan</td></tr></table>";
		}
		return $judul;
	}
	
	function setMenuEdit(){		
		return '';
	}
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){
	global $Main;	
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];
		//if($Pg == 'sensus'){
		//	$styleMenu = " style='color:blue;' ";	
		//}
		$menu = $_REQUEST['menu'];
		/*switch ($menu){
			case 'belumcek' : $styleMenu2_1 = " style='color:blue;' "; break;
			case 'diusulkan': $styleMenu2_3 = " style='color:blue;' "; break;
			case 'laporan' 	: $styleMenu2_4 = " style='color:blue;' "; break;
			case 'kertaskerja' 	: $styleMenu2_5 = " style='color:blue;' "; break;
			case 'ada' :$styleMenu2_2 = " style='color:blue;' "; break;	
			case 'tidakada' :$styleMenu2_5 = " style='color:blue;' "; break;	
			
			//default: $styleMenu2_2 = " style='color:blue;' "; break;	
		}*/
		//if($tipe='tipe')$styleMenu2_4 = " style='color:blue;' ";
		$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		
		//if ($jns=='penyusutan') $styleMenuPenyusutan = " style='color:blue;'";
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>": '';
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
			"<A href=\"index.php?Pg=05&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
		
			$menu_pembukuan1 =
		($Main->MODUL_AKUNTANSI )?
		"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>



	<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Aset Tetap Tanah'>Tanah</a>  |  
	<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
	<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
	<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
	<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Lainnya'>ATL</a>  |  
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
	$menu_kibg1
	
	<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |  
	$menu_penyusutan
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI'  >REKAP BI</a> |   
	<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI' $styleMenu >REKAP BI 2</a> 
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
	| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' >JURNAL</a> 
	  &nbsp&nbsp&nbsp
	</td></tr>":'';	
		
		
		$menubar = 			//"<tr height='22' valign='top'><td >".
			"<!--menubar_page-->
		
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
			<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
			<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
			<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
			<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
			<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
			<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
			<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			if($Main->MODUL_MUTASI) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
		   $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";

			if($Main->MODUL_SENSUS) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
			<A href=\"pages.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>$menu_pembukuan1			
			</table>".
			
			
			""
			;
		
		return $menubar;
			
	}
	
	function genDaftarOpsi(){
		global $Main;
		
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
				
		//data order ------------------------------
		/*
		$arrOrder = array(
			array('1','Tahun Perolehan'),
			array('2','Keadaan Barang'),
			array('3','Tahun Buku')
		);
		
		*/
		//tampil -------------------------------
		/*
		$menu = $_REQUEST['menu'];
		if($menu=='ada'){
			$filtKondBrg = cmb2D_v2('fmKONDBRG',$fmKONDBRG, $Main->KondisiBarang,'','Kondisi Barang','');
		}
		*/
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					'Tampilkan : '.
					' Tahun Buku '.
					"<input type='text' id='fmFiltThnBuku' name='fmFiltThnBuku' value='$fmFiltThnBuku' 
						size='4' maxlength='4' onkeypress='return isNumberKey(event)' >"
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		//$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		
		//Kondisi				
		$arrKondisi= array();
		$arrKondisi[] = " h='00'";		
		//$arrKondisi[] = " year(tgl_buku)<='$fmFiltThnBuku' ";
		
		
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$fmDESC2 = $_POST['fmDESC2'];
		$AscDsc2 = $fmDESC2 == 1? 'desc' : '';
		$fmORDER2 = $_POST['fmORDER2'];
		$fmDESC3 = $_POST['fmDESC3'];
		$AscDsc3 = $fmDESC3 == 1? 'desc' : '';
		$fmORDER3 = $_POST['fmORDER3'];
		
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc1 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc1 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc1 "; break;			
		}
		switch($fmORDER2){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc2 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc2 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc2 "; break;			
		}
		switch($fmORDER3){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc3 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc3 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc3 "; break;			
		}
			
		
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal;
		$Limit = $Mode == 3 ? '': $Limit;
		$Limit = '';
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						-->".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			
		$headerTable =
			"<thead><tr>
				<th class=\"th02\" width='30' >No.</th>
				<th class=\"th02\" width='50' >f</th>
				<th class=\"th02\" width='50' >g</th>				
				<th class=\"th02\" width='50' >h</th>		
				<th class=\"th02\" width='50' >i</th>		
				<th class=\"th02\" >Uraian</th>
				<th class=\"th02\" width='200' >Jumlah Barang</th>
				<th class=\"th02\" width='200' >Jumlah Harga<br>(Rp.)</th>
			</tr>
			<tr>
				<th class=\"th03\" >(1)</th>
				<th class=\"th03\" >(2)</th>
				<th class=\"th03\" >(3)</th>
				<th class=\"th03\" >(4)</th>
				<th class=\"th03\" >(5)</th>
				<th class=\"th03\" >(6)</th>
				<th class=\"th03\" >(7)</th>
				<th class=\"th03\" >(8)</th>
			</tr>				
			$tambahgaris
			</thead>";
			$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	
	function setDaftar_After($no=0, $ColStyle=''){
		
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];			
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];			
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		
		
		
		$vtotjmlbarang 	= number_format($this->totBrgAset, 0,',','.');
		$vtotjmlharga 		= number_format($this->totHrgAset, 2,',','.');

				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=6 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		
		return $ListData;
	}
	
	function setDaftar_After_($no=0, $ColStyle=''){
		
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];			
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];			
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		
		
		
		$vtotjmlbarang 	= number_format($this->totBrgAset, $des,',','.');
		$vtotjmlharga 		= number_format($this->totHrgAset, $des,',','.');

				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=5 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$Koloms = array();


		
		
		
		

		return $Koloms;
	}
	
	function setKolomData_($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
	
		
		

		return $Koloms;
	}
	
	
	function gen_table_data($Mode=1)
{
global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		$des = $jnsrekap==1? 2:0;

	$tglAwal=$fmFiltThnBuku.'-01-01' ; //'2014-01-01';
	$tglAkhir=$fmFiltThnBuku.'-12-31' ; //'2014-12-31';

				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;

		$Kondisi_ATetap=" and staset<=3 ";
		$KondisiAsal = join(' and ', $arrKond);
		if ($KondisiAsal==''){
			$KondisiAsal=" c <> '' ";
		}

	$Kondisi = $KondisiAsal." and staset<=3 and f<='06' ";
	
{
	

	$sqry=
		"select aa.f,aa.g,aa.h,aa.i,aa.nm_barang ,
(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,
(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
 bb.debet_jml_brg,bb.kredit_jml_brg,bb.debet_harga_brg,bb.kredit_harga_brg,
(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg + bb.debet_jml_brg - bb.kredit_jml_brg) as jmlbrgakhir,
(bb.debet_saldoawal - bb.kredit_saldoawal + bb.debet_harga_brg - bb.kredit_harga_brg) as jmlhargaakhir

from v_ref_kib_keu_h1  aa
 left join 
( select IFNULL(f,'00') as f,IFNULL(g,'00') as g,IFNULL(h,'00') as h, IFNULL(i,'00') as i,
sum(if(tgl_buku<'$tglAwal' $kondisi ,jml_barang_d,0)) as debet_saldoawal_brg, 
sum(if(tgl_buku<'$tglAwal' $kondisi ,jml_barang_k,0)) as kredit_saldoawal_brg,
sum(if(tgl_buku<'$tglAwal' $kondisi ,debet,0)) as debet_saldoawal, 
sum(if(tgl_buku<'$tglAwal' $kondisi ,kredit,0)) as kredit_saldoawal,

sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_jml_brg,
sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_jml_brg,
sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',debet,0)) as debet_harga_brg,
sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',kredit,0)) as kredit_harga_brg
from v_jurnal_aset_tetap_v2 
where c<>'' ".$KondisiSKPD."
group by f,g,h,i with rollup
) bb on aa.f = bb.f and aa.g = bb.g and aa.h = bb.h and aa.i = bb.i
 where aa.ka='01' and aa.kb<>'00'"; //echo $sqry;
}		
		//$ListData .= $sqry;
		
		$Qry = mysql_query($sqry);
		
		//$ListData = "";
		$no=0;
		$tampilKet='';
		$totBrgAsetTetap = 0;
		$totHrgAsetTetap = 0;
		//number_format($this->totAsetLain, $des,',','.');
		while($isi=mysql_fetch_array($Qry)){
		
        	$kdBidang = $isi['g'] != "00" ? $isi['g'] :"" ;
        	$kdKel = $isi['h'] == "00" ? "" : $isi['h'];
        	$nmBarang = $isi['g'] == "00"  && $isi['h'] == "00"  ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
			
        	$no++;
	        if ($cetak == FALSE) {
	            $clRow = $no % 2 == 0 ? "row1" : "row0";
	        } else {
	            $clRow = '';
	        }
						
			if ($no==1)	{
				//siapkan tampil total aset intra ----------------------------------------------------------------------
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
					$tampilKet="";
                    $TampilStyle = 
					"<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b><!--jmlbrg_akhir_aset_intra--></b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b><!--jmlhrg_akhir_aset_intra--></b></td>
					$tampilKet";
		        //$tampil_jmlHrgTambah_curr='';
		        $ListData .= 
					"<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=left colspan=5 ><b>I.&nbsp;&nbsp;&nbsp;&nbsp;Intrakomptabel</b></td>
					$TampilStyle
		        </tr>";
				
				//tampil aset tetap ----------------------------------------------------------------------------------
				$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';	
				$no++;			
		        $ListData .= 
					"<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\"></td>
					<td class=\"$clGaris\" align=left colspan=4 ><b>A.&nbsp;&nbsp;&nbsp;&nbsp;Aset Tetap</b></td>
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b><!--jmlbrg_akhir_aset_tetap1--></b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b><!--jmlHrg_akhir_aset_tetap1--></b></td>
					$tampilKet
		        </tr>
				";
				
				$no++;
				
			}	

			/*$Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
	        $KondisiBi = " status_barang<>3 ";
			$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
			$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";						
	        //data --------------------------------------------------
			//penghapusan
			$jmlBrgHPS_akhir =  $isi['jmlBrgHPS_akhir'];
			$jmlHrgHPS_akhir =  $isi['jmlHrgHPS_akhir'];		
			//buku_induk
			$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
			$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
			//pemelihara
	        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
			//pengaman
			$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
			//hapus sebagian
	        $jmlHrgHSBG_akhir = $isi['jmlHrgHSBG_akhir'];			
			//hapus pelihara
			$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
			//hapus pengaman
			$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
			//hapus hapus sebagian
			$jmlHrgHPS_HSBG_akhir =  $isi['jmlHrgHPS_HSBG_akhir'];   
			*/
			
			//penyusutan
/*			
			if($Main->PENYUSUTAN){								
				if ($isi['f'] == "00" && $isi['g'] == "00" && $isi['h'] == "00"){				
					$susut =  $isi['jmlHrgSusut_akhir'];
					$vTotSusut = "<div style='display:none'>$sqry</div>".'('.number_format( $susut, 2, ',', '.').')';					
				}else{
					$susut = 0;
				}	
			}
*/			
	
			/*$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir ;//- $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
	        $jmlHrg_akhir = 
				($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir 
				+ $jmlHrgHPS_HSBG_akhir) - 
				($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir+$jmlHrgHSBG_akhir )-
				$susut;
			*/
			$jmlBrg_akhir = $isi['jmlbrgakhir'];// $isi['debet_saldoakhir_brg']-$isi['kredit_saldoakhir_brg'];
			$jmlHrg_akhir = $isi['jmlhargaakhir']; //$isi['debet_saldoakhir']-$isi['kredit_saldoakhir'];
			if($isi['i'] != "00"){
				$totBrgAsetTetap += $jmlBrg_akhir;
				$totHrgAsetTetap += $jmlHrg_akhir;
			}
			//hit total --------------------------------------------------------------------------------
	        //awal ----------------------------------------

/*
			$totBrg_awal += $isi['g'] == "00" && $isi['h'] == "00" ? $jmlBrg_awal : 0;
	        $totHrg_awal += $isi['g'] == "00" && $isi['h'] == "00" ? $jmlHrg_awal : 0;
			
			
	
			
			
			//akhir ----------------------------------------
	        $totBrg_akhir += $isi['g'] == "00" && $isi['h'] == "00" ? $jmlBrg_akhir : 0;
	        $totHrg_akhir += $isi['g'] == "00" && $isi['h'] == "00" ? $jmlHrg_akhir : 0;
			 
*/			
			
		
	        //tampil row --------------------------------------------------
	        //dlm ribuan
	        
			$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
			
	
	        $tampil_jmlBrg_akhir = $isi['g'] == "00"  && $isi['h'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
	
	        $tampil_jmlHrg_akhir = $isi['g'] == "00"  && $isi['h'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
			/**
			if ($isi['f'] == "00" && $isi['g'] == "00" )
			{
				// $this->totjmlbarang=$jmlBrg_akhir;
				$totBrgAsetTetap = $jmlBrg_akhir;
				$totHrgAsetTetap = $jmlHrg_akhir;
				$tampil_jmlBrg_akhir='<b><!--jmlbrg_akhir_aset_tetap1--></b>';
				$tampil_jmlHrg_akhir='<b><!--jmlHrg_akhir_aset_tetap1--></b>';		
			}*/		
            $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
			$tampilKet="";
			if ($isi['h']=='00'){
				
			
	            $TampilStyle = "
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</b></td>
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</b></td>
						$tampilKet
					";
	       		 
	//        	$kdBidang = $isi['h'] == "00" ? "" : $isi['g'];
	//        	$kdKel = $isi['h'] == "00" ? "" : $isi['h'];			
	
			        $listRow = "
						<tr class='$clRow'>
						<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
						<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>{$isi['f']}</b></td>
						<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><b>$kdBidang</b></td>
						<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>$kdKel</b></td>					
						<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>$kdKelsub</b></td>					
						<td class=\"$clGaris\" width=\"$kolomwidth[3]\"><b>$nmBarang</b></td>
						$TampilStyle
			        </tr>
					";	
			} else if($isi['i']=='00'){
				$TampilStyle = "
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</b></td>
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</b></td>
						$tampilKet
					";
		        $listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>{$isi['f']}</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><b>{$isi['g']}</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>{$isi['h']}</b></td>					
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"></td>					
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</b></td>
					$TampilStyle
		        </tr>
				";	
				
			} else {
				$TampilStyle = "
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
						<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
						$tampilKet
					";
		        $listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">{$isi['g']}</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['h']}</td>					
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['i']}</td>					
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</td>
					$TampilStyle
		        </tr>
				";	
				
			}
			$ListData .= $listRow;
			
			
			
		}		
		
		
		//penyusutan -----------------------------------------------------------------------------

		
	// aset lainnya baru ------------------------------------------------------------------------------
	  {


	  	
	  
	 $bqry="
	 
		select 
		aa.f,aa.g,aa.h,aa.i,aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
		(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
		bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
		(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,
		(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
		(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
		bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir 
		
		 from v_ref_kib_keu_h1  aa
		 left join 
		( select IFNULL(f,'00') as f, IFNULL(g,'00') as g,IFNULL(h,'00') as h, IFNULL(i,'00') as i,
		sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
		sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,debet,0)) as debet_hp, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,harga_atribusi,0)) as debet_atribusi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,debet,0)) as debet_kapitalisasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,kredit,0)) as kredit_kapitalisasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,debet,0)) as debet_hibah, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,kredit,0)) as kredit_hibah, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,debet,0)) as debet_mutasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,kredit,0)) as kredit_mutasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,kredit,0)) as debet_penilaian,
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,debet,0)) as debet_penghapusan,  
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,kredit,0)) as kredit_penghapusan, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,debet,0)) as debet_koreksi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,kredit,0)) as kredit_koreksi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,debet,0)) as debet_reklass, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,kredit,0)) as kredit_reklass, 
		sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, 
		sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
		sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, 
		sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir
		
		from v_jurnal_aset_lainnya_v2 
		where $KondisiAsal
		group by f,g,h,i with rollup
		) bb on aa.f = bb.f	and aa.g = bb.g and aa.h = bb.h and aa.i = bb.i 
	 
	 
	 
	 where ka='02' 
	 #and kb<>'00' 
	 
	 ";// echo $bqry;
	 }
	 
		// $this->totjmlbarang=$jmlBrg_akhir;
/*
			$tampil_jmlBrg_akhir='<b><!--jmlbrg_akhir_aset_lainnya--></b>';
			$tampil_jmlHrg_akhir='<b><!--jmlHrg_akhir_aset_lainnya--></b>';		


		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet="";
        $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";

	        $ListData .= "
				<tr class='$clRow'>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">&nbsp;</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">&nbsp;</td>
				<td class=\"$clGaris\" colspan=2 ><b>B.</b>&nbsp;&nbsp;&nbsp;&nbsp;Aset Lainnya $bqry</td>
				$TampilStyle
	        </tr>";	 
*/			
			$tampil_jmlBrg_akhir='';
			$tampil_jmlHrg_akhir='';		

	 
	//$aQry = mysql_query($bqry);
	
	$tampilKet='';
	$totBrgAsetLainnya=0;
	$totHrgAsetLainnya=0;

	$Qry = mysql_query($bqry);
	while($isi=mysql_fetch_array($Qry)){
		
//		$kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        	$kdBidang = $isi['g'] != "00" ? $isi['g'] :"" ;
        	$kdKel = $isi['h'] == "00" ? "" : $isi['h'];
					
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }

		$jmlBrg_akhir=0;
		$jmlHrg_akhir =0;		

			$jmlBrg_akhir = $isi['jmlbrgakhir'];// $isi['debet_saldoakhir_brg']-$isi['kredit_saldoakhir_brg'];
			$jmlHrg_akhir = $isi['jmlhargaakhir']; //$isi['debet_saldoakhir']-$isi['kredit_saldoakhir'];
			if($isi['i'] != "00"){
				$totBrgAsetLainnya += $jmlBrg_akhir;
				$totHrgAsetLainnya += $jmlHrg_akhir;
			}

		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00"  && $isi['h'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" && $isi['h'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet="";
			
		if ($isi['g']=='00') {
            $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</b></td>
					$tampilKet
			";
		    $listRow = "
				<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"></td>
					<td class=\"$clGaris\" colspan=4 ><b>B.</b>&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</td>
					$TampilStyle
		        </tr>
				";	
		} else if ($isi['h']=='00'){
            $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</b></td>
					$tampilKet
				";
			$listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>{$isi['f']}</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><b>$kdBidang</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>$kdKel</b></td>					
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"></td>					
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\"><b>$nmBarang</b></td>
					$TampilStyle
		        </tr>
				";	
		} else if ($isi['i']=='00'){
            $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</b></td>
					$tampilKet
				";
			$listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>{$isi['f']}</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><b>$kdBidang</b></td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><b>$kdKel</b></td>					
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"></td>					
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</b></td>
					$TampilStyle
		        </tr>
				";	
		
		} else {
			$TampilStyle = "
				<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
				<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
				$tampilKet
				";       		 
			$listRow = 
				"<tr class='$clRow'>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">{$isi['g']}</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['h']}</td>					
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['i']}</td>					
				<td class=\"$clGaris\" width=\"$kolomwidth[3]\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</td>
				$TampilStyle
		        </tr>
				";	
		
		}


		$ListData .= $listRow;
		
	}
	
	//tsmpil tot intra
		$jmlBrg_akhir_intra =$totBrgAsetTetap+ $totBrgAsetLainnya;
		$jmlHrg_akhir_intra = $totHrgAsetTetap+ $totHrgAsetLainnya;
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir_intra / 1000), 2, ',', '.') : number_format($jmlHrg_akhir_intra, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$ListData = eregi_replace("<!--jmlbrg_akhir_aset_intra-->", $tampil_jmlBrg_akhir,$ListData);
		$ListData = eregi_replace("<!--jmlhrg_akhir_aset_intra-->", $tampil_jmlHrg_akhir,$ListData);	
		
		// Get ekstrakomptabel ----------------------------------------------------------------------------------------
		
		$no++;
		/*$get= mysql_fetch_array( mysql_query("select sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk 
		where  $Kondisi and staset=10  and tgl_buku <= '$tglAkhir'  "));  
		
		$jmlBrg_akhir=$get['jmlBrgBI_akhir'];
		$jmlHrg_akhir=$get['jmlHrgBI_akhir'];
		$jmlHrg_akhir_extra=$jmlHrg_akhir;		
		$jmlBrg_akhir_extra=$jmlBrg_akhir;	
		
		
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		$tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet="";
        $TampilStyle = "
			<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</td>
			<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</td>
			$tampilKet
			";
				
		$ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\" colspan=3><b>II.&nbsp;&nbsp;&nbsp;Ekstrakomptabel<b></td>
			$TampilStyle
        </tr>
		";
		$this->totBrgAset=$jmlBrg_akhir_intra+$jmlBrg_akhir_extra;
		$this->totHrgAset=$jmlHrg_akhir_intra+$jmlHrg_akhir_extra;
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir_intra / 1000), 2, ',', '.') : number_format($jmlHrg_akhir_intra, 2, ',', '.');
		

        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "";

        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		*/
		
		{
		$bqry=
			"select 
			aa.f,aa.g, aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
			(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
			bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
			(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
			(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
			bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir 
			
			 from v_ref_kib_keu  aa
			 left join 
			( select 
			sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
			sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,debet,0)) as debet_hp, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,harga_atribusi,0)) as debet_atribusi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,debet,0)) as debet_kapitalisasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,kredit,0)) as kredit_kapitalisasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,debet,0)) as debet_hibah, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,kredit,0)) as kredit_hibah, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,debet,0)) as debet_mutasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,kredit,0)) as kredit_mutasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,debet,0)) as debet_penilaian, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,debet,0)) as debet_penghapusan,  
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,kredit,0)) as kredit_penghapusan, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,debet,0)) as debet_koreksi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,kredit,0)) as kredit_koreksi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,debet,0)) as debet_reklass, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,kredit,0)) as kredit_reklass, 
			sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
			sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir
			
			from v_jurnal_aset_ekstra 
			where $KondisiAsal
			
			) bb on aa.kint = '02'
				 
		 	where kint='02' ";
		
		}
		$tampilKet='';
		$jmlBrg_akhir_tot=0;
		$jmlHrg_akhir_tot =0;
		$Qry = mysql_query($bqry); //$cek .= $bqry;
		while($isi=mysql_fetch_array($Qry)){
			$jmlBrg_akhir= $isi['jmlbrgakhir'];
			$jmlHrg_akhir= $isi['jmlhargaakhir'];
		}
		  
		$jmlHrg_akhir_extra=$jmlHrg_akhir;		
		$jmlBrg_akhir_extra=$jmlBrg_akhir;	
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		$tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet ="";
        $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
				
		$ListData .= " $cek
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\" colspan=5><b>II.&nbsp;&nbsp;&nbsp;Ekstrakomptabel<b></td>
			$TampilStyle
        </tr>
		";
		$this->totBrgAset=$jmlBrg_akhir_intra+$jmlBrg_akhir_extra;
		$this->totHrgAset=$jmlHrg_akhir_intra+$jmlHrg_akhir_extra;
		
		
		

		//tampil tot aset tetap
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrgAsetTetap / 1000), 2, ',', '.') : number_format($totHrgAsetTetap, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($totBrgAsetTetap, 0, ',', '.') . "" : "" . number_format($totBrgAsetTetap, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$ListData = eregi_replace("<!--jmlbrg_akhir_aset_tetap1-->", $tampil_jmlBrg_akhir,$ListData);
		$ListData = eregi_replace("<!--jmlhrg_akhir_aset_tetap1-->", $tampil_jmlHrg_akhir,$ListData);


		 
		
		//$ListData = eregi_replace("<!--jmlbrg_akhir_aset_intra-->", $tampil_jmlBrg_akhir,$ListData);
		//$ListData = eregi_replace("<!--jmlhrg_akhir_aset_intra-->", $tampil_jmlHrg_akhir,$ListData);		
		
	return $ListData;
}

	
	
	
	
	function gen_table_data_($Mode=1)
{
global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		$des = $jnsrekap==1? 2:0;

	$tglAwal=$fmFiltThnBuku.'-01-01' ; //'2014-01-01';
	$tglAkhir=$fmFiltThnBuku.'-12-31' ; //'2014-12-31';

				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;

		$Kondisi_ATetap=" and staset<=3 ";
		$KondisiAsal = join(' and ', $arrKond);
		if ($KondisiAsal==''){
			$KondisiAsal=" c <> '' ";
		}

	$Kondisi = $KondisiAsal." and staset<=3 and f<='06' ";
	

	$sqry="select aa.f, aa.g,aa.h,  aa.nm_barang,".
			"bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			".
			
			"ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir,
			nn.jmlHSBG_awal, nn.jmlHrgHSBG_awal,
			oo.jmlHSBG_akhir, oo.jmlHrgHSBG_akhir,
			pp.jmlHrgHPS_HSBG_awal,
			qq.jmlHrgHPS_HSBG_akhir,
			rr.jmlHrgSusut_awal, ss.jmlHrgSusut_akhir
			
			 ".		
		"from v_ref_kib_keu_h aa 
			
			".
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g,h with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g,h with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g,h with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from view_buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g,h with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			".
			
			"
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g,h with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g,h with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g,h with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from view_buku_induk  where $Kondisi and  tgl_buku <= '$tglAkhir'  group by f,g,h with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g,h with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g,h with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g,h with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g,h with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlHSBG_awal, sum(harga_hapus) as jmlHrgHSBG_awal from v_hapus_sebagian where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g,h with rollup ) nn on aa.f=nn.f and aa.g=nn.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, count(*) as jmlHSBG_akhir, sum(harga_hapus ) as jmlHrgHSBG_akhir from v_hapus_sebagian where $Kondisi  and tgl_penghapusan <= '$tglAkhir' group by f,g,h with rollup ) oo on aa.f=oo.f and aa.g=oo.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(harga_hapus ) as jmlHrgHPS_HSBG_awal from v2_penghapusan_hapussebagian where  tgl_penghapusan < '$tglAwal' $KondisiKIB group by f,g,h with rollup ) pp on aa.f=pp.f  and aa.g=pp.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(harga_hapus) as jmlHrgHPS_HSBG_akhir from v2_penghapusan_hapussebagian where  tgl_penghapusan <= '$tglAkhir'  $KondisiKIB group by f,g,h with rollup ) qq on aa.f=qq.f  and aa.g=qq.g  
			".
			
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g,IFNULL(h,'00') as h, sum(harga) as jmlHrgSusut_awal from v1_penyusutan where $Kondisi and tahun < '$fmFiltThnBuku'   group by f,g,h with rollup ) rr on aa.f=rr.f  and aa.g=rr.g  ".
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga) as jmlHrgSusut_akhir from v1_penyusutan where $Kondisi and tahun <= '$fmFiltThnBuku'   group by f,g,h with rollup ) ss on aa.f=ss.f  and aa.g=ss.g ".			
			
		" where aa.ka='01' ; "; 
		
		//$ListData .= $sqry;
		
		$Qry = mysql_query($sqry);
		
		//$ListData = "";
		$no=0;
		$tampilKet='';
		//number_format($this->totAsetLain, $des,',','.');
		while($isi=mysql_fetch_array($Qry)){
		
        	$kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        	$nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        	$no++;
	        if ($cetak == FALSE) {
	            $clRow = $no % 2 == 0 ? "row1" : "row0";
	        } else {
	            $clRow = '';
	        }
		
			if ($no==1)
			{
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
					$tampilKet="";
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b><!--jmlbrg_akhir_aset_intra--></b></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b><!--jmlhrg_akhir_aset_intra--></b></td>
					$tampilKet
				";
		        //$tampil_jmlHrgTambah_curr='';
		        $ListData .= "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=left colspan=3 ><b>I.&nbsp;&nbsp;&nbsp;&nbsp;Intrakomptabel</b></td>
					$TampilStyle
		        </tr>
				";
				$no++;
			}	

			$Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
	        $KondisiBi = " status_barang<>3 ";
			$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
			$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";
	
	        //data --------------------------------------------------
			//penghapusan
			$jmlBrgHPS_akhir =  $isi['jmlBrgHPS_akhir'];
			$jmlHrgHPS_akhir =  $isi['jmlHrgHPS_akhir'];		
			//buku_induk
			$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
			$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
			//pemelihara
	        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
			//pengaman
			$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
			//hapus sebagian
	        $jmlHrgHSBG_akhir = $isi['jmlHrgHSBG_akhir'];
			
			//hapus pelihara
			$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
			//hapus pengaman
			$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
			//hapus hapus sebagian
			$jmlHrgHPS_HSBG_akhir =  $isi['jmlHrgHPS_HSBG_akhir'];   
			
			//penyusutan
			
			if($Main->PENYUSUTAN){								
				if ($isi['f'] == "00" && $isi['g'] == "00" ){				
					$susut =  $isi['jmlHrgSusut_akhir'];
					$vTotSusut = "<div style='display:none'>$sqry</div>".'('.number_format( $susut, 2, ',', '.').')';					
				}else{
					$susut = 0;
				}	
			}
			
	
			$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir ;//- $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
	        $jmlHrg_akhir = 
				($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir 
				+ $jmlHrgHPS_HSBG_akhir) - 
				($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir+$jmlHrgHSBG_akhir )-
				$susut;
			//hit total --------------------------------------------------------------------------------
	        //awal ----------------------------------------
			$totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
	        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
			
			
	
			
			
			//akhir ----------------------------------------
	        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
	        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
			
			
			
		
	        //tampil row --------------------------------------------------
	        //dlm ribuan
	        
			$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
			
	
	        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
	
	        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
	
			if ($isi['f'] == "00" && $isi['g'] == "00" )
			{
				// $this->totjmlbarang=$jmlBrg_akhir;
				$totBrgAsetTetap = $jmlBrg_akhir;
				$totHrgAsetTetap = $jmlHrg_akhir;
				$tampil_jmlBrg_akhir='<b><!--jmlbrg_akhir_aset_tetap1--></b>';
				$tampil_jmlHrg_akhir='<b><!--jmlHrg_akhir_aset_tetap1--></b>';		
			}		
            $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
			$tampilKet="";
            $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
       		 
			
			if ($isi['f'] == "00" && $isi['g'] == "00" )
			{
		        $listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">&nbsp</td>
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\" colspan=2 ><b>A.</b>&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</td>
					$TampilStyle
		        </tr>
				";		
			} else 
			{
		        $listRow = "
					<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</td>
					$TampilStyle
		        </tr>
				";					
			}
			$ListData .= $listRow;
			
			
			
		}		
		
		
		//penyusutan -----------------------------------------------------------------------------
		if($Main->PENYUSUTAN){
			$no ++;
			
			$ListData .= "
				<tr class='$clRow'>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">&nbsp</td>
					<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">&nbsp</td>
					<td class=\"$clGaris\" width=\"$kolomwidth[3]\" ><b>Akumulasi Penyusutan</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>".$vTotSusut."</td>
		        </tr>
				";		
		}
		
		
	// aset lainnya baru ------------------------------------------------------------------------------
	  {
	  	
	  
	 $bqry="
	 
		select 
		aa.f,aa.g,aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
		(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
		bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
		(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
		(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
		bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir 
		
		 from v_ref_kib_keu  aa
		 left join 
		( select IFNULL(f,'00') as f, IFNULL(g,'00') as g,
		sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
		sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,debet,0)) as debet_hp, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,harga_atribusi,0)) as debet_atribusi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,debet,0)) as debet_kapitalisasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,kredit,0)) as kredit_kapitalisasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,debet,0)) as debet_hibah, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,kredit,0)) as kredit_hibah, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,debet,0)) as debet_mutasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,kredit,0)) as kredit_mutasi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,kredit,0)) as debet_penilaian,
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,debet,0)) as debet_penghapusan,  
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,kredit,0)) as kredit_penghapusan, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,debet,0)) as debet_koreksi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,kredit,0)) as kredit_koreksi, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,debet,0)) as debet_reklass, 
		sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,kredit,0)) as kredit_reklass, 
		sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, 
		sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
		sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, 
		sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir
		
		from v_jurnal_aset_lainnya 
		where $KondisiAsal
		group by f,g
		) bb on aa.f = bb.f	and aa.g = bb.g 
	 
	 
	 
	 where ka='02' 
	 #and kb<>'00' 
	 
	 ";// echo $bqry;
	 }
	//$aQry = mysql_query($bqry);
	
	$tampilKet='';
	$jmlBrg_akhir_tot=0;
	$jmlHrg_akhir_tot =0;
	$Qry = mysql_query($bqry);
	while($isi=mysql_fetch_array($Qry)){
		
		$kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
		
		$jmlBrg_akhir=0;
		$jmlHrg_akhir =0;		
					

		//if ($isi['g'] == "21"){
			//$get= mysql_fetch_array( mysql_query("select sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk 
			//where  $Kondisi and staset=5  and tgl_buku <= '$tglAkhir'  "));  
			$jmlBrg_akhir= $isi['jmlbrgakhir'];
			$jmlHrg_akhir= $isi['jmlhargaakhir'];
			$jmlHrg_akhir_tot=$jmlHrg_akhir_tot+$jmlHrg_akhir;		
			$jmlBrg_akhir_tot=$jmlBrg_akhir_tot+$jmlBrg_akhir;		
		//}	

		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;


		if ($isi['g'] == "00" )
		{
			// $this->totjmlbarang=$jmlBrg_akhir;
			$tampil_jmlBrg_akhir='<b><!--jmlbrg_akhir_aset_lainnya--></b>';
			$tampil_jmlHrg_akhir='<b><!--jmlHrg_akhir_aset_lainnya--></b>';		
		}

		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet="";
        $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
			
        //$tampil_jmlHrgTambah_curr='';
		if ($isi['f'] == "07" && $isi['g'] == "00" )
		{
	        $ListData .= "
				<tr class='$clRow'>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">&nbsp;</td>
				<td class=\"$clGaris\" colspan=2 ><b>B.</b>&nbsp;&nbsp;&nbsp;&nbsp;$nmBarang</td>
				$TampilStyle
	        </tr>
			";		
		
		} else {
	        $ListData .= "
				<tr class='$clRow'>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
				<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
				<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</td>
				$TampilStyle
	        </tr>
			";	
		}	
		
	}
	  
		
		//tampil tot aset lainnya
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir_tot / 1000), 2, ',', '.') : number_format($jmlHrg_akhir_tot, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir_tot, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir_tot, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$ListData = eregi_replace("<!--jmlbrg_akhir_aset_lainnya-->", $tampil_jmlBrg_akhir,$ListData);
		$ListData = eregi_replace("<!--jmlhrg_akhir_aset_lainnya-->", $tampil_jmlHrg_akhir,$ListData);
		
		//tampil tot aset tetap
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrgAsetTetap / 1000), 2, ',', '.') : number_format($totHrgAsetTetap, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($totBrgAsetTetap, 0, ',', '.') . "" : "" . number_format($totBrgAsetTetap, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$ListData = eregi_replace("<!--jmlbrg_akhir_aset_tetap1-->", $tampil_jmlBrg_akhir,$ListData);
		$ListData = eregi_replace("<!--jmlhrg_akhir_aset_tetap1-->", $tampil_jmlHrg_akhir,$ListData);
		
		
		//tsmpil tot intra
		$jmlBrg_akhir_intra =$totBrgAsetTetap+ $jmlBrg_akhir_tot;
		$jmlHrg_akhir_intra =$totHrgAsetTetap+ $jmlHrg_akhir_tot;
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir_intra / 1000), 2, ',', '.') : number_format($jmlHrg_akhir_intra, 2, ',', '.');
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		$ListData = eregi_replace("<!--jmlbrg_akhir_aset_intra-->", $tampil_jmlBrg_akhir,$ListData);
		$ListData = eregi_replace("<!--jmlhrg_akhir_aset_intra-->", $tampil_jmlHrg_akhir,$ListData);
				
		// Get ekstrakomptabel ----------------------------------------------------------------------------------------
		
		$no++;
		/*$get= mysql_fetch_array( mysql_query("select sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk 
		where  $Kondisi and staset=10  and tgl_buku <= '$tglAkhir'  "));  
		
		$jmlBrg_akhir=$get['jmlBrgBI_akhir'];
		$jmlHrg_akhir=$get['jmlHrgBI_akhir'];
		$jmlHrg_akhir_extra=$jmlHrg_akhir;		
		$jmlBrg_akhir_extra=$jmlBrg_akhir;	
		
		
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		$tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet="";
        $TampilStyle = "
			<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</td>
			<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</td>
			$tampilKet
			";
				
		$ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\" colspan=3><b>II.&nbsp;&nbsp;&nbsp;Ekstrakomptabel<b></td>
			$TampilStyle
        </tr>
		";
		$this->totBrgAset=$jmlBrg_akhir_intra+$jmlBrg_akhir_extra;
		$this->totHrgAset=$jmlHrg_akhir_intra+$jmlHrg_akhir_extra;
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir_intra / 1000), 2, ',', '.') : number_format($jmlHrg_akhir_intra, 2, ',', '.');
		

        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir_intra, 0, ',', '.') . "";

        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;

		*/
		{
		$bqry=
			"select 
			aa.f,aa.g,aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
			(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
			bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
			(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
			(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
			bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir 
			
			 from v_ref_kib_keu  aa
			 left join 
			( select 
			sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
			sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,debet,0)) as debet_hp, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,harga_atribusi,0)) as debet_atribusi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,debet,0)) as debet_kapitalisasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,kredit,0)) as kredit_kapitalisasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,debet,0)) as debet_hibah, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,kredit,0)) as kredit_hibah, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,debet,0)) as debet_mutasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,kredit,0)) as kredit_mutasi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,debet,0)) as debet_penilaian, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,debet,0)) as debet_penghapusan,  
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,kredit,0)) as kredit_penghapusan, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,debet,0)) as debet_koreksi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,kredit,0)) as kredit_koreksi, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,debet,0)) as debet_reklass, 
			sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,kredit,0)) as kredit_reklass, 
			sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
			sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir
			
			from v_jurnal_aset_ekstra 
			where $KondisiAsal
			
			) bb on aa.kint = '02'
				 
		 	where kint='02' ";
		
		}
		$tampilKet='';
		$jmlBrg_akhir_tot=0;
		$jmlHrg_akhir_tot =0;
		$Qry = mysql_query($bqry); //$cek .= $bqry;
		while($isi=mysql_fetch_array($Qry)){
			$jmlBrg_akhir= $isi['jmlbrgakhir'];
			$jmlHrg_akhir= $isi['jmlhargaakhir'];
		}
		  
		$jmlHrg_akhir_extra=$jmlHrg_akhir;		
		$jmlBrg_akhir_extra=$jmlBrg_akhir;	
		
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, ',', '.') : number_format($jmlHrg_akhir, 2, ',', '.');
		$tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, ',', '.') . "" : "" . number_format($jmlBrg_akhir, 0, ',', '.') . "";
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		$tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
		$tampilKet ="";
        $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><b>$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><b>$tampil_jmlHrg_akhir</td>
					$tampilKet
				";
				
		$ListData .= " $cek
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\" colspan=3><b>II.&nbsp;&nbsp;&nbsp;Ekstrakomptabel<b></td>
			$TampilStyle
        </tr>
		";
		$this->totBrgAset=$jmlBrg_akhir_intra+$jmlBrg_akhir_extra;
		$this->totHrgAset=$jmlHrg_akhir_intra+$jmlHrg_akhir_extra;
		
		
		
		 
		
		//$ListData = eregi_replace("<!--jmlbrg_akhir_aset_intra-->", $tampil_jmlBrg_akhir,$ListData);
		//$ListData = eregi_replace("<!--jmlhrg_akhir_aset_intra-->", $tampil_jmlHrg_akhir,$ListData);		
		
	return $ListData;
}

	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/


	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');

		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\" colspan=6>Pembukuan</td>
			</tr>
			</table>"	
			.PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,$this->cetak_xls)."<br>";
	}
	
}


$Pembukuan5 = new PembukuanObj5();

?>