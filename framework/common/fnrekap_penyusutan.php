<?php

class RekapPenyusutanOldObj extends DaftarObj2{
	var $Prefix = 'RekapPenyusutanOld'; //jsname
	var $SHOW_CEK = TRUE;
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
	var $FieldSum_Cp1 = array( 5,5,5);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 18; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = FALSE;
	var $withSumHal = FALSE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '';
	var $totalAllStr = '';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='RekapPenyusutanOld.xls';
	var $Cetak_Judul = 'Rekap Penyusutan';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead ='<style>
	.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}</style>';//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penatausahaan';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $jml_data=0;
	var $totBrgAset = 0;
	var $totHrgAset = 0;
	
	//var $table_susut = 't_jurnal_penyusutan';
	
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
		
		
		$ListData .= 
				
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
		return 'Rekap Penyusutan ';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>Rekap Mutasi";
		if ($this->cetak_xls==TRUE){
			$judul="<table width='100%'><tr><td colspan=6>Rekap Mutasi x</td></tr></table>";
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

		$menu = $_REQUEST['menu'];
		
		$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		$menu_penyusutan = $Main->PENYUSUTAN ? 
			" <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenu title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>": '';
		
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
			"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
		$menubar3 = 
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
				"<A href=\"index.php?Pg=05&jns=penyusutan\"  title='Intrakomptabel' >PENYUSUTAN</a> ".
				"| <A href=\"pages.php?Pg=RekapPenyusutan\"  title='Intrakomptabel' $styleMenu >REKAP PENYUSUTAN</a>   ".
				"&nbsp&nbsp&nbsp".
				"</td></tr>";
		
		if($Main->VERSI_NAME=='JABAR'){
			
			$menubar = 			//"<tr height='22' valign='top'><td >".
				"<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
				<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
				<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
				<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
				<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
				<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
				<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
							
				<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			
			$menubar.= " <A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' $styleMenu8>PETA</a> |";
			if($Main->MODUL_MUTASI) 
				$menubar=$menubar."
					<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
					<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
					
			//$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
			
			if($Main->MODUL_SENSUS) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >INVENTARISASI</a> |";
			
			if($Main->MODUL_PEMBUKUAN) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				
			$menubar .= "| <A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> ";
			
			
			$menu_pembukuan1 = ($Main->MODUL_AKUNTANSI )?
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Aset Tetap Tanah'>Tanah</a>  |  
				<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
				<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
				<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
				<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Aset Tetap Lainnya'>ATL</a>  |  
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
				$menu_kibg1
				<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |
				$menu_penyusutan  
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP ASET</a>
				<!--|<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI 2'  >REKAP BI</a>   -->
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI II</a>
				| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' >JURNAL</a>
				  &nbsp&nbsp&nbsp
				</td></tr>":'';	
				
			
			
			$menubar=
				"<!--menubar_page-->		
				<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
					$menubar.
				"&nbsp&nbsp&nbsp".
				"</td></tr>".
				$menu_pembukuan1.
				$menubar3.
				"</table>".
				"";
		}else{
		
			$menu_pembukuan1 = ($Main->MODUL_AKUNTANSI )?
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
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
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a>
				|<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI 2'  >REKAP BI 2</a>   
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
				
			if($Main->MODUL_MUTASI) 
				$menubar=$menubar."
					<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
					<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
					
			$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
			
			if($Main->MODUL_SENSUS) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
			
			if($Main->MODUL_PEMBUKUAN) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			
			$menubar=$menubar."&nbsp&nbsp&nbsp
				</td></tr>".
				$menu_pembukuan1.
				$menubar3.			
				"</table>".
				"";
		}
		return $menubar;
			
	}
	
	function genDaftarOpsi(){
		global $Main,$fmFiltThnBuku;
		
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmSemester = $_REQUEST['fmSemester'];
		$fmTplKosong = cekPOST('fmTplKosong');
		$fmTplDetail = cekPOST('fmTplDetail');
		
		$Semester = "Semester ".cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester1,'','Semester I');
		
		
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
					'Tampilkan : '.$Semester.
					' Tahun Buku '.
					"<input type='text' id='fmFiltThnBuku' name='fmFiltThnBuku' value='$fmFiltThnBuku' 
						size='4' maxlength='4' onkeypress='return isNumberKey(event)' >".
					"<input $fmTplKosong type='checkbox' id='fmTplKosong' name='fmTplKosong' value='checked'>Tampil Kosong &nbsp;&nbsp;&nbsp;".
					"<input $fmTplDetail type='checkbox' id='fmTplDetail' name='fmTplDetail' value='checked'>Detail"
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
						
						-->"
						.'<style>
						.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";
	}
						</style>'.
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		global $Ref;
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$fmTplDetail = $_POST['fmTplDetail'];
		$fmFiltThnBuku = $_POST['fmFiltThnBuku'];
		$smter= $_POST['fmSemester'];
		
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		
		$thnsbl =$fmFiltThnBuku -1;
		if ($smter=='1') 
		{
			$tglAwal="$fmFiltThnBuku-07-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
		} else if ($smter=='2') 
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
		} else
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-06-30";
		}
		
		if($fmTplDetail){
			$rows1 = "rowspan='4'";
			$rows2 = "rowspan='3'";
			$cols1 = "colspan='14'";
			$cols2 = "colspan='12'";
			$cols3 = "colspan='6'";
			$cols4 = "colspan='6'";
			$Detail = "<tr>
				<th class=\"th02\" >Pindah Antar SKPD</th>
				<th class=\"th02\" >Reklas</th>
				<th class=\"th02\" >Penghapusan</th>
				<th class=\"th02\" >Kapitalisasi</th>
				<th class=\"th02\" >Aset Lain-lain</th>
				<th class=\"th02\" >Koreksi Penyusutan</th>
				<th class=\"th02\" >Penyusutan</th>
				<th class=\"th02\" >Koreksi Penyusutan</th>
				<th class=\"th02\" >Pindah Antar SKPD</th>				
				<th class=\"th02\" >Reklas</th>				
				<th class=\"th02\" >Kapitalisasi</th>				
				<th class=\"th02\" >Aset Lain-lain</th>				
			</tr>";
		}else{
			$rows1 = "rowspan='3'";
			$rows2 = "rowspan='2'";
			$cols1 = "colspan='4'";
			$cols2 = "colspan='2'";
			$cols3 = "";
			$cols4 = "";
			$Detail = "";
		}
		

		$headerTglAwal = (substr( $tglAwal, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAwal,5,2)-1)]." ".substr($tglAwal, 0,4);  	
		$headerTglAkhir =  (substr( $tglAkhir, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAkhir,5,2)-1)]." ".substr($tglAkhir, 0,4);  

		$headerTable =
			"<tr>
				<th class=\"th02\" width='30' $rows1 >No. </th>
				<th class=\"th02\" colspan='4' >Kode<br>Barang</th>
				<th class=\"th02\" $rows1 >Nama Barang</th>
				<th class=\"th02\" $cols1 > Akumulasi Penyusutan </th>
			</tr>".
			"<tr>
				<th class=\"th02\" $rows2 >F</th>
				<th class=\"th02\" $rows2 >G</th>
				<th class=\"th02\" $rows2 >H</th>
				<th class=\"th02\" $rows2 >I</th>
				<th class=\"th02\" $rows2 >Keadaan per<br>$headerTglAwal</th>
				<th class=\"th02\" $cols2 >Mutasi Perubahan Selama<br>$headerTglAwal s/d $headerTglAkhir</th>
				<th class=\"th02\" $rows2 >Keadaan per<br>$headerTglAkhir</th>
			</tr>".
			"<tr>
				<th class=\"th02\" $cols3>Berkurang</th>
				<th class=\"th02\" $cols4>Bertambah</th>
			</tr>".
			$Detail.
			"<tr>
				<th class=\"th03\" >1</th>
				<th class=\"th03\" >2</th>
				<th class=\"th03\" >3</th>
				<th class=\"th03\" >4</th>
				<th class=\"th03\" >5</th>
				<th class=\"th03\" >6</th>
				<th class=\"th03\" >7</th>				
				<th class=\"th03\" $cols3>8</th>				
				<th class=\"th03\" $cols4>9</th>				
				<th class=\"th03\" >10</th>				
			</tr>";
		/*$headerTable =
			"<tr>
				<th class=\"th02\" width='30' rowspan='3' >No. </th>
				<th class=\"th02\" colspan='2' >Kode<br>Barang</th>
				<th class=\"th02\"  rowspan='3' >Nama Bidang Barang</th>
				<th class=\"th02\" colspan='2' >Keadaan per<br>$headerTglAwal</th>
				<th class=\"th02\" colspan='4' >Mutasi Perubahan Selama<br>$headerTglAwal s/d $headerTglAkhir</th>
				<th class=\"th02\" colspan='2' >Keadaan per<br>$headerTglAkhir</th>
			</tr>
			<tr>
				<th class=\"th02\" rowspan='2' >Gol.</th>
				<th class=\"th02\" rowspan='2' >Bid.</th>
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" colspan='2' >Berkurang</th>
				<th class=\"th02\" colspan='2' >Bertambah</th>
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Jumlah Harga<br>(Rp.)</th>

			</tr>
			<tr>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
			
			</tr>
			<tr>
				<th class=\"th03\" >1</th>
				<th class=\"th03\" >2</th>
				<th class=\"th03\" >3</th>
				<th class=\"th03\" >4</th>
				<th class=\"th03\" >5</th>
				<th class=\"th03\" >6</th>
				<th class=\"th03\" >7</th>
				<th class=\"th03\" >8</th>
				<th class=\"th03\" >9</th>
				<th class=\"th03\" >10</th>
				<th class=\"th03\" >11</th>
				<th class=\"th03\" >12</th>
				
			</tr>				
			$tambahgaris";*/
			$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	function setDaftar_After($no=0, $ColStyle=''){
	
	/*	
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
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		*/
		$ListData="";
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
	
	
	function gen_table_data($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;
		
			
		$tampilKosong = $_REQUEST['fmTplKosong'] ==''? 0 : 1; //0/kalau checked = 1
		//if($Main->JURNAL_FISIK){
		//	$tblSusut = 't_jurnal_aset';//				
		//}else{
		//	$tblSusut = 't_jurnal_penyusutan';//				
			$tblSusut = 'v_jurnal_penyusutan';//				
		//}

		$idbi = $_REQUEST['idbi'];
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
	
		$smter=empty($_REQUEST['fmSemester'])? '' : $_REQUEST['fmSemester']; 

		if ($smter=='1'){
			$tglAwal="$fmFiltThnBuku-07-01 00:00:00";
			$tglAkhir="$fmFiltThnBuku-12-31 23:59:59";
			
			$tgl1="$fmFiltThnBuku-07-01";
			$tgl2="$fmFiltThnBuku-12-31";
		} else if ($smter=='2'){
			$tglAwal="$fmFiltThnBuku-01-01 00:00:00";
			$tglAkhir="$fmFiltThnBuku-12-31 23:59:59";
			
			$tgl1="$fmFiltThnBuku-01-01";
			$tgl2="$fmFiltThnBuku-12-31";
		} else {
			$tglAwal="$fmFiltThnBuku-01-01 00:00:00";
			$tglAkhir="$fmFiltThnBuku-06-30 23:59:59";
			
			$tgl1="$fmFiltThnBuku-01-01";
			$tgl2="$fmFiltThnBuku-06-30";
		}	
				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;
		if( !empty($idbi )) $KondisiSKPD .= " and idbi='idbi'";

		$Kondisi_ATetap=" and staset<=3 ";
		$KondisiAsal = join(' and ', $arrKond);
		if ($KondisiAsal==''){
			$KondisiAsal=" c <> '' ";
		}
		
		$fmTplDetail = $_REQUEST['fmTplDetail'];
		if($fmTplDetail){
			$debet_harga_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,debet,0)) as debet_harga_brg, ";
			$debet_pindah_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=15 $kondisi,debet,0)) as debet_pindah_brg, ";
			$debet_reklas_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && (jns_trans2=16 or jns_trans2=17 or jns_trans2=18 or jns_trans2=19) $kondisi,debet,0)) as debet_reklas_brg, ";
			$debet_hapus_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=14 $kondisi,debet,0)) as debet_hapus_brg, ";
			$debet_kapitalisasi_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=22 $kondisi,debet,0)) as debet_kapitalisasi_brg, ";
			$debet_asetlain_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=21 $kondisi,debet,0)) as debet_asetlain_brg, ";
			$debet_koreksi_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=31 $kondisi,debet,0)) as debet_koreksi_brg, ";
			
			
			$kredit_harga_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,kredit,0)) as kredit_harga_brg, ";
			$kredit_susut_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=30 $kondisi,kredit,0)) as kredit_susut_brg, ";
			$kredit_koreksi_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=31 $kondisi,kredit,0)) as kredit_koreksi_brg, ";
			$kredit_pindah_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=15 $kondisi,kredit,0)) as kredit_pindah_brg, ";
			$kredit_reklas_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && (jns_trans2=16 or jns_trans2=17 or jns_trans2=18 or jns_trans2=19) $kondisi,kredit,0)) as kredit_reklas_brg, ";
			$kredit_kapitalisasi_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=22 $kondisi,kredit,0)) as kredit_kapitalisasi_brg,  ";
			$kredit_asetlain_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' && jns_trans2=21 $kondisi,kredit,0)) as kredit_asetlain_brg ";
		
			$seleksi_debet = "bb.debet_harga_brg, bb.debet_pindah_brg, bb.debet_reklas_brg, bb.debet_hapus_brg, bb.debet_kapitalisasi_brg, 
			bb.debet_asetlain_brg, bb.debet_koreksi_brg, ";
			$seleksi_kredit = "bb.kredit_harga_brg, bb.kredit_susut_brg, bb.kredit_koreksi_brg, bb.kredit_pindah_brg, bb.kredit_reklas_brg, 
			bb.kredit_kapitalisasi_brg, bb.kredit_asetlain_brg, ";
		}else{
			$debet_harga_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,debet,0)) as debet_harga_brg, ";
			$debet_pindah_brg = "";
			$debet_reklas_brg = "";
			$debet_hapus_brg = "";
			$debet_kapitalisasi_brg = "";
			$debet_asetlain_brg = "";
			$debet_koreksi_brg = "";
			
			$kredit_harga_brg = "sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,kredit,0)) as kredit_harga_brg ";
			$kredit_susut_brg = "";
			$kredit_koreksi_brg = "";
			$kredit_pindah_brg = "";
			$kredit_reklas_brg = "";
			$kredit_kapitalisasi_brg = "";
			$kredit_asetlain_brg = "";
			
			$seleksi_debet = "bb.debet_harga_brg, ";
			$seleksi_kredit = "bb.kredit_harga_brg, ";
		}
		
		
		$JmlHargaAwSKPD = 0;
		
		
	  $no=1;	
	//pesen tempat aset intra , dibawah nilai diisi----------------------------------------------------------------------
	if($fmTplDetail){
		$Intra="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'>
		<b><!--jmlIntraPindah_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'>
		<b><!--jmlIntraReklas_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'>
		<b><!--jmlIntraHapus_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'>
		<b><!--jmlIntraKapital_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'>
		<b><!--jmlIntraAsetLain_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'>
		<b><!--jmlIntraKoreksi_kurang-->
		</a></td>".
		
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'>
		<b><!--jmlIntraSusut_tambah-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'>
		<b><!--jmlIntraKoreksi_tambah-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'>
		<b><!--jmlIntraPindah_tambah-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'>
		<b><!--jmlIntraReklas_tambah-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'>
		<b><!--jmlIntraKapital_tambah-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'>
		<b><!--jmlIntraAsetLain_tambah-->
		</a></td>";
	}else{
		$Intra="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'>
		<b><!--jmlIntra_kurang-->
		</a></td>".
		"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'>
		<b><!--jmlIntra_tambah-->
		</a></td>";
	}
	$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$no.</td>
			<td align=left class='$clGaris' colspan=5 ><b>I. &nbsp;&nbsp; Intrakomptabel</td>".			
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl1' target='blank_'  style='color:black;'>
				<b><!--jmlIntra_aw-->
			</a></td>".
			$Intra.
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=00&kb=00&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl1' target='blank_'  style='color:black;'>
				<b><!--jmlIntra_akhir-->
			</td>".
			
			'';
	
	// aset tetap ka=01 -------------------------------------------------------------------
	//&& jns_trans2
	$kondisi = " && jns_trans=10  ";
	$bqry1="select ".
		"aa.kb,aa.f,aa.g, aa.h, aa.i, aa.nm_barang, ".
		"(bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg) as jmlbrgawal, ".
		"(bb.kredit_saldoawal - bb.debet_saldoawal) as jmlhargaawal, ".
		//"bb.debet_jml_brg,bb.kredit_jml_brg, ".
		$seleksi_debet.
		$seleksi_kredit.
		//"( (bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg) + (bb.kredit_jml_brg - bb.debet_jml_brg ) ) as jmlbrgakhir, ".
		"( (bb.kredit_saldoawal - bb.debet_saldoawal ) + ( bb.kredit_harga_brg - bb.debet_harga_brg ) )  as jmlhargaakhir ".
		//"( (bb.debet_saldoawal - bb.kredit_saldoawal ) + ( bb.debet_harga_brg - bb.kredit_harga_brg ) )  as jmlhargaakhir ".
				
		"from v_ref_kib_keu_penyusutan aa ".
		"left join  ".
		"( select  IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi ,jml_barang_d,0)) as debet_saldoawal_brg,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi ,jml_barang_k,0)) as kredit_saldoawal_brg, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi ,debet,0)) as debet_saldoawal,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi ,kredit,0)) as kredit_saldoawal, ".
		
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_d,0)) as debet_jml_brg, ".
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_k,0)) as kredit_jml_brg, ".
		$debet_harga_brg.
		$debet_pindah_brg.
		$debet_reklas_brg.
		$debet_hapus_brg.
		$debet_kapitalisasi_brg.
		$debet_asetlain_brg.
		$debet_koreksi_brg.
		
		$kredit_harga_brg.
		$kredit_susut_brg.
		$kredit_koreksi_brg.
		$kredit_pindah_brg.
		$kredit_reklas_brg.
		$kredit_kapitalisasi_brg.
		$kredit_asetlain_brg.
		
		"from $tblSusut ".		
		"where c<>'' ".$KondisiSKPD." and kint='01' and ka='01' ".
		"group by f,g,h,i with rollup ".
		") bb on aa.f = bb.f and aa.g = bb.g and aa.h = bb.h and aa.i= bb.i ".
		//"group by f,g,h,i with rollup ".
		//") bb on aa.f = bb.f and aa.g = bb.g  and aa.h = bb.h  and aa.i = bb.i ".
		
		" where  aa.ka='01' ;";
		//and aa.kb<>'00'; ";
		//" where  aa.ka='01' and aa.f<>'01' and aa.f<>'06'; ";



//$j = 0;
	  $aQry = mysql_query($bqry1); //$cek. = 
	 // echo $aQry;
	  
	while($isix=mysql_fetch_array($aQry)){
		$idbi = $isix['idbi'];
		$idawal = $isix['idawal'];
	  	
		if ($isix['g']=='00' &&  $isix['f']!='00'){
			//$j++;			
			//$JmlHargaAwSKPDprev = $JmlHargaAwSKPD;
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];
				
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['kredit_jml_brg'];
	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['debet_jml_brg'];
	
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
			
			if($fmTplDetail){
				$JmlSusutTambahSKPD=$JmlSusutTambahSKPD+$isix['kredit_susut_brg'];
				$JmlKoreksiTambahSKPD=$JmlKoreksiTambahSKPD+$isix['kredit_koreksi_brg'];
				$JmlPindahTambahSKPD=$JmlPindahTambahSKPD+$isix['kredit_pindah_brg'];
				$JmlReklasTambahSKPD=$JmlReklasTambahSKPD+$isix['kredit_reklas_brg'];
				$JmlKapitalTambahSKPD=$JmlKapitalTambahSKPD+$isix['kredit_kapitalisasi_brg'];
				$JmlAsetLainTambahSKPD=$JmlAsetLainTambahSKPD+$isix['kredit_asetlain_brg'];
				
				$JmlKoreksiKurangSKPD=$JmlKoreksiKurangSKPD+$isix['debet_koreksi_brg'];
				$JmlPindahKurangSKPD=$JmlPindahKurangSKPD+$isix['debet_pindah_brg'];
				$JmlReklasKurangSKPD=$JmlReklasKurangSKPD+$isix['debet_reklas_brg'];
				$JmlHapusKurangSKPD=$JmlHapusKurangSKPD+$isix['debet_hapus_brg'];
				$JmlKapitalKurangSKPD=$JmlKapitalKurangSKPD+$isix['debet_kapitalisasi_brg'];
				$JmlAsetLainKurangSKPD=$JmlAsetLainKurangSKPD+$isix['debet_asetlain_brg'];
			}else{
				$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['kredit_harga_brg'];
				$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['debet_harga_brg'];
			}
		}
		
		if($isix['g'] == 00 && $isix['h'] == 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] == 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] != 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}

			
		$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 2, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['kredit_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['debet_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 2, ',', '.');	
		


		if ( $isix['f']=='00' ){//total aset tetap
			$no++;
			$jmlAsetTetap_aw = $isix['jmlhargaawal'];
			$jmlAsetTetap_akhir = $isix['jmlhargaakhir'];
				if($fmTplDetail){
					$jmlAsetTetapSusut_tambah = $isix['kredit_susut_brg'];
					$jmlAsetTetapKoreksi_tambah = $isix['kredit_koreksi_brg'];
					$jmlAsetTetapPindah_tambah = $isix['kredit_pindah_brg'];
					$jmlAsetTetapReklas_tambah = $isix['kredit_reklas_brg'];
					$jmlAsetTetapKapital_tambah = $isix['kredit_kapitalisasi_brg'];
					$jmlAsetTetapAsetLain_tambah = $isix['kredit_asetlain_brg'];
					
					$jmlAsetTetapKoreksi_kurang = $isix['debet_koreksi_brg'];
					$jmlAsetTetapPindah_kurang = $isix['debet_pindah_brg'];
					$jmlAsetTetapReklas_kurang = $isix['debet_reklas_brg'];
					$jmlAsetTetapHapus_kurang = $isix['debet_hapus_brg'];
					$jmlAsetTetapKapital_kurang = $isix['debet_kapitalisasi_brg'];
					$jmlAsetTetapAsetLain_kurang = $isix['debet_asetlain_brg'];
					
					$AsetTetapTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapSusut_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKoreksi_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapPindah_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapReklas_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKapital_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapAsetLain_tambah, 2, ',', '.').
									"</a></td>";
					$AsetTetapKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapPindah_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapReklas_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapHapus_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKapital_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapAsetLain_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKoreksi_kurang, 2, ',', '.').
									"</a></td>";
				}else{
					$jmlAsetTetap_tambah = $isix['kredit_harga_brg'];
					$jmlAsetTetap_kurang = $isix['debet_harga_brg'];
					
					$AsetTetapTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetap_tambah, 2, ',', '.').
									"</a></td>";
					$AsetTetapKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetap_kurang, 2, ',', '.').
									"</a></td>";
				}
			
			$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$no.</td>
			<td align=center class='$clGaris'>&nbsp;</td>".
			"<td align=left class='$clGaris' colspan=4 ><b>A. &nbsp;&nbsp;   Aset Tetap</td>".
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl1' target='blank_'  style='color:black;'><b>".
				number_format($jmlAsetTetap_aw, 2, ',', '.').
			//$isix['g']." ". $j." $JmlHargaAwSKPD $JmlHargaAwSKPDprev {$isix['jmlhargaawal']}".
			"</a></td>".
			$AsetTetapKurang.
			$AsetTetapTambah.			
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=01&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl2' target='blank_'  style='color:black;'><b>".
				number_format($jmlAsetTetap_akhir, 2, ',', '.').
			"</a></td>".
			'';
			
		}else{ //rincian aset tetap
			if($fmTplDetail){
				$AsetTetapRinci_kurang="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_pindah_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_reklas_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_hapus_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_kapitalisasi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_asetlain_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_koreksi_brg'], 2, ',', '.').
										"</a></td>";
				$AsetTetapRinci_tambah="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_susut_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_koreksi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_pindah_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_reklas_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_kapitalisasi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_asetlain_brg'], 2, ',', '.').
										"</a></td>";
			}else{
				$AsetTetapRinci_kurang="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaKurangSKPD
										</a></td>";
				$AsetTetapRinci_tambah="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaTambahSKPD
										</a></td>";
			}
			
		  	if($isix['jmlhargaawal'] + $isix['debet_harga_brg'] +  $isix['kredit_harga_brg'] + $isix['jmlhargaakhir'] <>0 || $tampilKosong==1){
				$no++;
				$space = '';
				$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				<td align=center class='$clGaris'>".		( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}".
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). 
				"</td>
				<td class='$clGaris' >".
				( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang'].
				"</td>
				
				<td align=right class='$clGaris'><a href='$href&tgl2=$tgl1' target='blank_'  style='color:black;'>".
					( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAwSKPD ".
					//$isix['g']." ". $j." $JmlHargaAwSKPD $JmlHargaAwSKPDprev {$isix['jmlhargaawal']}".
				"</a></td>".
				$AsetTetapRinci_kurang.
				$AsetTetapRinci_tambah.		
				"<td align=right class='$clGaris'><a href='$href&tgl2=$tgl2' target='blank_'  style='color:black;'>".
					( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAkhirSKPD".
					( $isix['i'] != '00' ? '':'</b>').
				"</a></td>
				</tr>"	;
			}
		}
		
  	}

	// lainnya ka=02 -------------------------------------------------------------------	
	$bqry2="select  ".
		"aa.kb,aa.f,aa.g,aa.h,aa.i, aa.nm_barang , ".		
		"(bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg) as jmlbrgawal, ".
		"(bb.kredit_saldoawal - bb.debet_saldoawal) as jmlhargaawal, ".
		"bb.debet_jml_brg,bb.kredit_jml_brg, ".
		$seleksi_debet.
		$seleksi_kredit.
		"(bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg + bb.kredit_jml_brg - bb.debet_jml_brg) as jmlbrgakhir, ".
		"(bb.kredit_saldoawal - bb.debet_saldoawal+bb.kredit_harga_brg-bb.debet_harga_brg) as jmlhargaakhir ".
				
		"from v_ref_kib_keu_penyusutan  aa ".
		"left join  ".
		"( select IFNULL(f,'00') as f,IFNULL(g,'00') as g,  ".
		//"( select IFNULL(f,'00') as f,IFNULL(g,'00') as g, '00' as h, '00' as i, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,jml_barang_d,0)) as debet_saldoawal_brg,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,jml_barang_k,0)) as kredit_saldoawal_brg, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,debet,0)) as debet_saldoawal,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,kredit,0)) as kredit_saldoawal, ".
		
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_d,0)) as debet_jml_brg, ".
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_k,0)) as kredit_jml_brg, ".
		$debet_harga_brg.
		$debet_pindah_brg.
		$debet_reklas_brg.
		$debet_hapus_brg.
		$debet_kapitalisasi_brg.
		$debet_asetlain_brg.
		$debet_koreksi_brg.
		
		$kredit_harga_brg.
		$kredit_susut_brg.
		$kredit_koreksi_brg.
		$kredit_pindah_brg.
		$kredit_reklas_brg.
		$kredit_kapitalisasi_brg.
		$kredit_asetlain_brg.
		
		"from $tblSusut ".
		"where c<>'' ".$KondisiSKPD."  and kint='01' and ka='02' ".
		"group by f,g with rollup ".
		//"group by f,g,h,i with rollup ".
		//"group by f,g with rollup ".
		//") bb on aa.f = bb.f and aa.g = bb.g and aa.h= bb.h and aa.i= bb.i ".
        ") bb on aa.f = bb.f and aa.g = bb.g  ".
		"where kint='01' and ka='02' and (kb='05' or kb='00')";


	  $aQry = mysql_query($bqry2);


	  while($isix=mysql_fetch_array($aQry)){
	  	
		if ($isix['g']=='00'){//buat total
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];
				
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['kredit_jml_brg'];
	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['debet_jml_brg'];
	
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
			
			if($fmTplDetail){
				$JmlSusutTambahSKPD=$JmlSusutTambahSKPD+$isix['kredit_susut_brg'];
				$JmlKoreksiTambahSKPD=$JmlKoreksiTambahSKPD+$isix['kredit_koreksi_brg'];
				$JmlPindahTambahSKPD=$JmlPindahTambahSKPD+$isix['kredit_pindah_brg'];
				$JmlReklasTambahSKPD=$JmlReklasTambahSKPD+$isix['kredit_reklas_brg'];
				$JmlKapitalTambahSKPD=$JmlKapitalTambahSKPD+$isix['kredit_kapitalisasi_brg'];
				$JmlAsetLainTambahSKPD=$JmlAsetLainTambahSKPD+$isix['kredit_asetlain_brg'];
				
				$JmlKoreksiKurangSKPD=$JmlKoreksiKurangSKPD+$isix['debet_koreksi_brg'];
				$JmlPindahKurangSKPD=$JmlPindahKurangSKPD+$isix['debet_pindah_brg'];
				$JmlReklasKurangSKPD=$JmlReklasKurangSKPD+$isix['debet_koreksi_brg'];
				$JmlHapusKurangSKPD=$JmlHapusKurangSKPD+$isix['debet_hapus_brg'];
				$JmlKapitalKurangSKPD=$JmlKapitalKurangSKPD+$isix['debet_kapitalisasi_brg'];
				$JmlAsetLainKurangSKPD=$JmlAsetLainKurangSKPD+$isix['debet_asetlain_brg'];
			}else{
				$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['kredit_harga_brg'];
				$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['debet_harga_brg'];
			}
		}
		
		if($isix['g'] == 00 && $isix['h'] == 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] == 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] == 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}elseif($isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] != 00){
			$href = "pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&f={$isix[f]}&g={$isix[g]}&h={$isix[h]}&i={$isix[i]}";
		}

			
		$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 2, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['kredit_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['debet_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 2, ',', '.');	


		if ($isix['g']=='00'){//total aset lainnya
			$no++;
			$jmlAsetTetap_aw = $isix['jmlhargaawal'];
			$jmlAsetTetap_akhir = $isix['jmlhargaakhir'];
			if($fmTplDetail){
				$jmlAsetTetapSusut_tambah = $isix['kredit_susut_brg'];
				$jmlAsetTetapKoreksi_tambah = $isix['kredit_koreksi_brg'];
				$jmlAsetTetapPindah_tambah = $isix['kredit_pindah_brg'];
				$jmlAsetTetapReklas_tambah = $isix['kredit_reklas_brg'];
				$jmlAsetTetapKapital_tambah = $isix['kredit_kapitalisasi_brg'];
				$jmlAsetTetapAsetLain_tambah = $isix['kredit_asetlain_brg'];
				
				$jmlAsetTetapKoreksi_kurang = $isix['debet_koreksi_brg'];
				$jmlAsetTetapPindah_kurang = $isix['debet_pindah_brg'];
				$jmlAsetTetapReklas_kurang = $isix['debet_reklas_brg'];
				$jmlAsetTetapHapus_kurang = $isix['debet_hapus_brg'];
				$jmlAsetTetapKapital_kurang = $isix['debet_kapitalisasi_brg'];
				$jmlAsetTetapAsetLain_kurang = $isix['debet_asetlain_brg'];
				
				$AsetLainnyaTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapSusut_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKoreksi_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapPindah_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapReklas_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKapital_tambah, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapAsetLain_tambah, 2, ',', '.').
									"</a></td>";
				$AsetLainnyaKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapPindah_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapReklas_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapHapus_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKapital_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapAsetLain_kurang, 2, ',', '.').
									"</a></td>".
									"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetapKoreksi_kurang, 2, ',', '.').
									"</a></td>";
			}else{
				$jmlAsetTetap_tambah = $isix['kredit_harga_brg'];
				$jmlAsetTetap_kurang = $isix['debet_harga_brg'];
				
				$AsetLainnyaTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetap_tambah, 2, ',', '.').
									"</a></td>";
				$AsetLainnyaKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'><b>".
										number_format($jmlAsetTetap_kurang, 2, ',', '.').
									"</a></td>";
			}
			$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$no.</td>
			<td align=center class='$clGaris'>&nbsp;</td>".
			"<td align=left class='$clGaris' colspan=4 ><b>B. &nbsp;&nbsp;   Aset Lainnya</td>".
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl1' target='blank_'  style='color:black;'><b>".
				number_format($jmlAsetTetap_aw, 2, ',', '.').
			"</td>".
			$AsetLainnyaKurang.
			$AsetLainnyaTambah.			
			"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=01&ka=02&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl2=$tgl2' target='blank_'  style='color:black;'><b>".
				number_format($jmlAsetTetap_akhir, 2, ',', '.').
			"</a></td>".
			'';
			
		}
		else{ //rincian aset LAINnya
			if($fmTplDetail){
				$AsetLainnyaRinciTambah="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_susut_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_koreksi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_pindah_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_reklas_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_kapitalisasi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['kredit_asetlain_brg'], 2, ',', '.').
										"</a></td>";
				$AsetLainnyaRinciKurang="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_pindah_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_reklas_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_hapus_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_kapitalisasi_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_asetlain_brg'], 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>').number_format($isix['debet_koreksi_brg'], 2, ',', '.').
										"</a></td>";
			}else{
				$AsetLainnyaRinciTambah="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaTambahSKPD
										</a></td>";
				$AsetLainnyaRinciKurang="<td align=right class='$clGaris'><a href='$href&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'>".
											( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaKurangSKPD
										</a></td>";
			}
			if($isix['jmlhargaawal'] + $isix['debet_harga_brg'] +  $isix['kredit_harga_brg'] + $isix['jmlhargaakhir'] <>0 || $tampilKosong==1){
				$no++;
				$space = '';
				$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}".
					//( $isix['i'] != '00'? '': '</b>').
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). 
					//( $isix['i'] != '00'? '': '</b>').	
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). 
					//( $isix['i'] != '00'? '': '</b>').	
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). 
					//( $isix['i'] != '00'? '': '</b>').	
				"</td>
			
				<td class='$clGaris' >".
				( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang'].
				//( $isix['i'] != '00'? '': '</b>').
				"</td>
				
				<td align=right class='$clGaris'><a href='$href&tgl2=$tgl1' target='blank_'  style='color:black;'>".
					( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAwSKPD 
				</a></td>".
				$AsetLainnyaRinciKurang.
				$AsetLainnyaRinciTambah.			
				"<td align=right class='$clGaris'><a href='$href&tgl2=$tgl2' target='blank_'  style='color:black;'>".
					( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAkhirSKPD".
					( $isix['i'] != '00' ? '':'</b>').
				"</a></td>
				</tr>"	;
			}
		}


 	}
	
	//tampil total intra -----------------------------------------------------------------	
	if($fmTplDetail){
		$ListData = str_replace( '<!--jmlIntra_aw-->',  number_format($JmlHargaAwSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraSusut_tambah-->',  number_format($JmlSusutTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraKoreksi_tambah-->',  number_format($JmlKoreksiTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraPindah_tambah-->',  number_format($JmlPindahTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraReklas_tambah-->',  number_format($JmlReklasTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraKapital_tambah-->',  number_format($JmlKapitalTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraAsetLain_tambah-->',  number_format($JmlAsetLainTambahSKPD, 2, ',', '.'), $ListData);
		
		$ListData = str_replace( '<!--jmlIntraKoreksi_kurang-->',  number_format($JmlKoreksiKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraPindah_kurang-->',  number_format($JmlPindahKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraReklas_kurang-->',  number_format($JmlReklasKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraHapus_kurang-->',  number_format($JmlHapusKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraKapital_kurang-->',  number_format($JmlKapitalKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntraAsetLain_kurang-->',  number_format($JmlAsetLainKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntra_akhir-->',  number_format($JmlHargaAkhirKPD, 2, ',', '.'), $ListData);
	}else{
		$ListData = str_replace( '<!--jmlIntra_aw-->',  number_format($JmlHargaAwSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntra_tambah-->',  number_format($JmlHargaTambahSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntra_kurang-->',  number_format($JmlHargaKurangSKPD, 2, ',', '.'), $ListData);
		$ListData = str_replace( '<!--jmlIntra_akhir-->',  number_format($JmlHargaAkhirKPD, 2, ',', '.'), $ListData);
	}
	
	
	//ekstra -----------------------------------------------------------------------
	$bqry3="select  ".
		"aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i, aa.nm_barang , ".
		"(bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg) as jmlbrgawal, ".
		"(bb.kredit_saldoawal - bb.debet_saldoawal) as jmlhargaawal, ".
		"bb.debet_jml_brg,bb.kredit_jml_brg, ".
		$seleksi_debet.
		$seleksi_kredit.
		"(bb.kredit_saldoawal_brg - bb.debet_saldoawal_brg + bb.kredit_jml_brg - bb.debet_jml_brg) as jmlbrgakhir, ".
		"(bb.kredit_saldoawal - bb.debet_saldoawal+bb.kredit_harga_brg-bb.debet_harga_brg) as jmlhargaakhir ".
				
		"from v_ref_kib_keu_penyusutan  aa ".
		"left join  ".
		"( select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,jml_barang_d,0)) as debet_saldoawal_brg,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,jml_barang_k,0)) as kredit_saldoawal_brg, ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,debet,0)) as debet_saldoawal,  ".
		"sum(if(tgl_buku<'$tglAwal' $kondisi,kredit,0)) as kredit_saldoawal, ".
		
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_d,0)) as debet_jml_brg, ".
		"sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir' $kondisi,jml_barang_k,0)) as kredit_jml_brg, ".
		$debet_harga_brg.
		$debet_pindah_brg.
		$debet_reklas_brg.
		$debet_hapus_brg.
		$debet_kapitalisasi_brg.
		$debet_asetlain_brg.
		$debet_koreksi_brg.
		
		$kredit_harga_brg.
		$kredit_susut_brg.
		$kredit_koreksi_brg.
		$kredit_pindah_brg.
		$kredit_reklas_brg.
		$kredit_kapitalisasi_brg.
		$kredit_asetlain_brg.
		
		"from $tblSusut ".
		"where c<>'' ".$KondisiSKPD."  and kint='02'".
		"group by f,g,h,i with rollup ".
		") bb on aa.f = bb.f and aa.g = bb.g and aa.h= bb.h and aa.i= bb.i ".
		"where aa.kint='02'  ";
		
	  $aQry = mysql_query($bqry3);
	  while($isix=mysql_fetch_array($aQry)){
	  	$no++;
		if ($isix['g']=='00'){
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];
				
			$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['kredit_harga_brg'];	
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['kredit_jml_brg'];
	
			$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['debet_harga_brg'];	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['debet_jml_brg'];
	
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
		}

			
		$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 2, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['kredit_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['debet_jml_brg'], 2, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 2, ',', '.');	


		if ($isix['g']=='00'){//total ekstra
			//if($isix['jmlhargaawal'] || $isix['kredit_harga_brg'] ||  $isix['debet_harga_brg'] || $isix['jmlhargaakhir'] ){
			
				$jmlAsetTetap_aw = $isix['jmlhargaawal'];
				$jmlAsetTetap_akhir = $isix['jmlhargaakhir'];
				if($fmTplDetail){
					$jmlAsetTetap_tambah = $isix['kredit_harga_brg'];
					$jmlAsetTetapSusut_tambah = $isix['kredit_susut_brg'];
					$jmlAsetTetapKoreksi_tambah = $isix['kredit_koreksi_brg'];
					$jmlAsetTetapPindah_tambah = $isix['kredit_pindah_brg'];
					$jmlAsetTetapReklas_tambah = $isix['kredit_reklas_brg'];
					$jmlAsetTetapKapital_tambah = $isix['kredit_kapitalisasi_brg'];
					$jmlAsetTetapAsetLain_tambah = $isix['kredit_asetlain_brg'];
					
					$jmlAsetTetapKoreksi_kurang = $isix['debet_koreksi_brg'];
					$jmlAsetTetap_kurang = $isix['debet_harga_brg'];
					$jmlAsetTetapPindah_kurang = $isix['debet_pindah_brg'];
					$jmlAsetTetapReklas_kurang = $isix['debet_reklas_brg'];
					$jmlAsetTetapHapus_kurang = $isix['debet_hapus_brg'];
					$jmlAsetTetapKapital_kurang = $isix['debet_kapitalisasi_brg'];
					$jmlAsetTetapAsetLain_kurang = $isix['debet_asetlain_brg'];
					
					$AsetLainnyaTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=30' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapSusut_tambah, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=31' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapKoreksi_tambah, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=15' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapPindah_tambah, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=16' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapReklas_tambah, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=22' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapKapital_tambah, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2&jns_trans2=21' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapAsetLain_tambah, 2, ',', '.').
										"</a></td>";
					$AsetLainnyaKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=15' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapPindah_kurang, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=16' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapReklas_kurang, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=14' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapHapus_kurang, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=22' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapKapital_kurang, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=21' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapAsetLain_kurang, 2, ',', '.').
										"</a></td>".
										"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1&jns_trans2=31' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetapKoreksi_tambah, 2, ',', '.').
										"</a></td>";
				}else{
					$jmlAsetTetap_tambah = $isix['kredit_harga_brg'];
					$jmlAsetTetap_kurang = $isix['debet_harga_brg'];
					
					$AsetLainnyaTambah="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=2' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetap_tambah, 2, ',', '.').
										"</a></td>";
					$AsetLainnyaKurang="<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&ka={$isix[ka]}&kb={$isix[kb]}&c=$c&d=$d&e=$e&e1=$e1&tgl1=$tgl1&tgl2=$tgl2&debet=1' target='blank_'  style='color:black;'><b>".
											number_format($jmlAsetTetap_kurang, 2, ',', '.').
										"</a></td>";
				}
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>".
				"<td align=left class='$clGaris' colspan=5 ><b>II. &nbsp;&nbsp;   Ekstrakomptabel</td>".
				"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&tgl2=$tgl1' target='blank_'  style='color:black;'><b>".
					number_format($jmlAsetTetap_aw, 2, ',', '.').
				"</a></td>".
				$AsetLainnyaKurang.
				$AsetLainnyaTambah.			
				"<td align=right class='$clGaris'><a href='pages.php?Pg=JurnalPenyusutan2&kint=02&tgl2=$tgl2' target='blank_'  style='color:black;'><b>".
					number_format($jmlAsetTetap_akhir, 2, ',', '.').
				"</a></td>".
				'';
			
			//}
			
		}
		else{ //rincian ekstra
			$space = '';
			$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			
			$ListData .=
			"<tr class='row0'>
			<td align=center class='$clGaris'>$no.</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}". "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). "</td>		
			<td class='$clGaris' >".( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang']."</td>
			
			<td align=right class='$clGaris'>".( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAwSKPD</td>
			<td align=right class='$clGaris'>".( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaKurangSKPD</td>
			<td align=right class='$clGaris'>".( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaTambahSKPD</td>			
			<td align=right class='$clGaris'>".( $isix['i'] != '00' ? '':'<b>')."$TampilJmlHargaAkhirSKPD".
				( $isix['i'] != '00' ? '':'</b>').
			"</td>
			</tr>"	;
		}


 	}
	
	
	
	//total ekstra + intra -----------------------------------------------------------
		$TampilJmlHargaAwSKPD=number_format($JmlHargaAwSKPD, 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($JmlBrgAwSKPD, 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($JmlBrgTambahSKPD, 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($JmlBrgKurangSKPD, 2, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($JmlHargaAkhirKPD, 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($JmlBrgAkhirKPD, 2, ',', '.');	
		
		if($fmTplDetail){
			$TampilJmlSusutTambahSKPD=number_format($JmlSusutTambahSKPD, 2, ',', '.');
			$TampilJmlKoreksiTambahSKPD=number_format($JmlKoreksiTambahSKPD, 2, ',', '.');
			$TampilJmlPindahTambahSKPD=number_format($JmlPindahTambahSKPD, 2, ',', '.');
			$TampilJmlReklasTambahSKPD=number_format($JmlReklasTambahSKPD, 2, ',', '.');
			$TampilJmlKapitalTambahSKPD=number_format($JmlKapitalTambahSKPD, 2, ',', '.');
			$TampilJmlAsetLainTambahSKPD=number_format($JmlAsetLainTambahSKPD, 2, ',', '.');
			
			$TampilJmlKoreksiKurangSKPD=number_format($JmlKoreksiKurangSKPD, 2, ',', '.');
			$TampilJmlPindahKurangSKPD=number_format($JmlPindahKurangSKPD, 2, ',', '.');
			$TampilJmlReklasKurangSKPD=number_format($JmlReklasKurangSKPD, 2, ',', '.');
			$TampilJmlHapusKurangSKPD=number_format($JmlHapusKurangSKPD, 2, ',', '.');
			$TampilJmlKapitalKurangSKPD=number_format($JmlKapitalKurangSKPD, 2, ',', '.');
			$TampilJmlAsetLainKurangSKPD=number_format($JmlAsetLainKurangSKPD, 2, ',', '.');
			$ListData .="<tr class='row0'>
						<td class='$clGaris' align=center colspan=6 ><b>Total</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaAwSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlPindahKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlReklasKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHapusKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlKapitalKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlAsetLainKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlKoreksiKurangSKPD</b></td>
						
						<td align=right class='$clGaris'><b>$TampilJmlSusutTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlKoreksiTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlPindahTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlReklasTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlKapitalTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlAsetLainTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaAkhirSKPD</b></td>
						</tr>"	;
		}else{
			$TampilJmlHargaTambahSKPD=number_format($JmlHargaTambahSKPD, 2, ',', '.');
			$TampilJmlHargaKurangSKPD=number_format($JmlHargaKurangSKPD, 2, ',', '.');
			$ListData .="<tr class='row0'>
						<td class='$clGaris' align=center colspan=6 ><b>Total</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaAwSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaKurangSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaTambahSKPD</b></td>
						<td align=right class='$clGaris'><b>$TampilJmlHargaAkhirSKPD</b></td>
						</tr>"	;
		}
	 
	 	if($fmTplDetail){
			$ListData .="<tr><td colspan=19></td></tr>"	;
		}else{
			$ListData .="<tr><td colspan=12></td></tr>"	;
		}
		
		
	$cek.= $this->SHOW_CEK ? $bqry1.'; '.$bqry2.'; '.$bqry3 : '';
	return $ListData."<div id='cek' style='display:none'>$cek</div>";
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
				<td class=\"judulcetak\" colspan=6>REKAP NERACA</td>
			</tr>
			</table>"	
			.PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,$this->cetak_xls)."<br>";
	}
	
}

$RekapPenyusutanOld = new RekapPenyusutanOldObj();

?>