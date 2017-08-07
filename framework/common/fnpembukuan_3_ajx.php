<?php

class PembukuanObj3Ajx extends DaftarObj2{
	var $Prefix = 'Pembukuan3_2Ajx'; //jsname
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
	var $fileNameExcel='RekapMutasi.xls';
	var $Cetak_Judul = 'Rekap Mutasi';
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
	var $PageTitle = 'Rekap Mutasi';
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
/*
	function genDaftar()
	{
	
		return array('cek'=>'','content'=>'', 'err'=>'');
	}
*/
	function genSumHal(){

		return array('sum'=>0, 'hal'=>0, 'sums'=>0, 'jmldata'=>0, 'cek'=>'' );
	}	
	function setTitle(){
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		return 'Rekap Mutasi ';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>Rekap Mutasi";
		if ($this->cetak_xls==TRUE)
		{
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
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
			"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
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
		
		
		if($Main->VERSI_NAME=='JABAR'){
			$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA II</a>": '';
		
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
	 <A href=\"pages.php?Pg=Pembukuan\" title='Rekap Neraca' >REKAP NERACA</a> |
	 <A href=\"index.php?Pg=05&jns=tetap\" title='Rincian Neraca' >RINCIAN NERACA</a> 
	| <A href=\"pages.php?Pg=Rekap1\" title='Rekap Aset' >REKAP ASET</a>   
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  $styleMenu>REKAP MUTASI II</a>
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
			<A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' $styleMenu8>PETA</a> |
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
		  // $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";

			if($Main->MODUL_SENSUS) $menubar=$menubar."	<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >INVENTARISASI</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			
		$menubar=$menubar." | <A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> 	
			&nbsp&nbsp&nbsp
			</td></tr>$menu_pembukuan1	
					
			</table>".
			"";
			
			
			
			""
			;
		
		}else{
			
		
			$styleMenu = " style='color:blue;' ";	
			$styleMenu2_4 = " style='color:blue;' ";
			$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
			$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ? " | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca'  >KERTAS KERJA</a>": '';
			$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
				"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
			
			
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
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP NERACA</a> 
				<!--| <A href=\"pages.php?Pg=Rekap5\" title='Rekap BI' >REKAP BI 2</a> -->
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  $styleMenu>REKAP MUTASI</a>
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
				<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi' >REKAP MUTASI</a> |";
			   $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
	
				if($Main->MODUL_SENSUS) $menubar=$menubar."
				<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
				if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				$menubar=$menubar."&nbsp&nbsp&nbsp
				</td></tr>$menu_pembukuan1			
				</table>".			
				
				""	;
		
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
		$fmLevelBarang = $_REQUEST['fmLevelBarang'];
		/*
		$xls =$_REQUEST['tipe'];
		if ($xls=='exportXls')
		{
			$this->cetak_xls==TRUE;
		} else {
			$this->cetak_xls==FALSE;
		}		
		*/		
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
		$arrLevelBarang = array(
			//array('1','Level 2'),
			array('1','Level 3'),
			array('2','Level 4')
		);
		$Semester = "Semester ".cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester1,'','Semester I');
		$cmbLevelBarang = "  Level Barang ".cmb2D_v2('fmLevelBarang',$fmLevelBarang,$arrLevelBarang,'','Level 2');
		//$cekLvl2 = " <input $cbxLvl2 id='cbxLvl2' type='checkbox' value='checked' name='cbxLvl2' > 2 ";
		//$cekLvl3 = " <input $cbxLvl3 id='cbxLvl3' type='checkbox' value='checked' name='cbxLvl3' > 3 ";
		//$cekLvl4 = " <input $cbxLvl4 id='cbxLvl4' type='checkbox' value='checked' name='cbxLvl4' > 4 ";
		
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
						size='4' maxlength='4' onkeypress='return isNumberKey(event)' >
						<input type='hidden' id='daftarcetak' name='daftarcetak' value='' >".
					$cmbLevelBarang
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
		
		$daftarCetak=$_REQUEST['daftarcetak'];
		
		$this->cetak_xls = $xls;
		/*
		<style>
		.nfmt1 {mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt2 {mso-number-format:'0\.00_';}
		.nfmt3 {mso-number-format:'00000';}
		.nfmt4 {mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt5 {mso-number-format:'\@';} 
		table {mso-displayed-decimal-separator:'\.';
			mso-displayed-thousand-separator:'\,';}	
		br {mso-data-placement:same-cell;}	
		</style>*/ 	
		//if($this->cetak_xls){
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}						
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH'>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
			//echo 'vkondisi='.$$Opsi[vKondisi;
		/*if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}*/
		echo $daftarCetak;
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
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
		$fmFiltThnBuku = $_POST['fmFiltThnBuku'];
		$smter= $_POST['fmSemester'];
		$fmLevelBarang= $_POST['fmLevelBarang'];
		
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
		

		$headerTglAwal = (substr( $tglAwal, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAwal,5,2)-1)]." ".substr($tglAwal, 0,4);  	
		$headerTglAkhir =  (substr( $tglAkhir, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAkhir,5,2)-1)]." ".substr($tglAkhir, 0,4);  
		
		if($fmLevelBarang=='1'){
			$cols = "colspan='3'";
			$tampilLevelBarang = "<th class=\"th02\" rowspan='2' width='10'>f</th>
								<th class=\"th02\" rowspan='2' width='10'>g</th>
								<th class=\"th02\" rowspan='2' width='10'>h</th>";
			$tampilAngka = "<th class=\"th03\" >17</th>";
		}elseif($fmLevelBarang=='2'){
			$cols = "colspan='4'";
			$tampilLevelBarang = "<th class=\"th02\" rowspan='2' width='10'>f</th>
								<th class=\"th02\" rowspan='2' width='10'>g</th>
								<th class=\"th02\" rowspan='2' width='10'>h</th>
								<th class=\"th02\" rowspan='2' width='10'>i</th>";
			$tampilAngka = "<th class=\"th03\" >17</th>
							<th class=\"th03\" >18</th>";
		}else{
			$cols = "colspan='2'";
			$tampilLevelBarang = "<th class=\"th02\" rowspan='2' width='10'>f</th>
								<th class=\"th02\" rowspan='2' width='10'>g</th>";
			$tampilAngka = "";
		}
			
		$headerTable =
			"<tr>
				<th class=\"th02\" width='30' rowspan='3' >No. </th>
				<th class=\"th02\" $cols width='50'>Kode<br>Barang</th>
				<th class=\"th02\"  rowspan='3' >Nama Bidang Barang</th>
				<th class=\"th02\" colspan='3' >Keadaan per<br>$headerTglAwal</th>
				<th class=\"th02\" colspan='6' >Mutasi Perubahan Selama<br>$headerTglAwal s/d $headerTglAkhir</th>
				<th class=\"th02\" colspan='3' >Keadaan per<br>$headerTglAkhir</th>
			</tr>
			<tr>
				$tampilLevelBarang
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Harga Perolehan<br>(Rp.)</th>
				<th class=\"th02\" rowspan='2' >Akum Penyusutan<br>(Rp.)</th>
				
				<th class=\"th02\" colspan='3' >Berkurang</th>
				<th class=\"th02\" colspan='3' >Bertambah</th>
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" rowspan='2'>Akum Penyusutan<br>(Rp.)</th>

			</tr>
			<tr>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" >Akum Penyusutan<br>(Rp.)</th>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" >Akum Penyusutan<br>(Rp.)</th>
				
			
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
				<th class=\"th03\" >13</th>
				<th class=\"th03\" >14</th>
				<th class=\"th03\" >15</th>
				<th class=\"th03\" >16</th>
				$tampilAngka
			</tr>
			$tambahgaris";
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
	
	function gen_table_data($Mode=1)
{
global $Main,$HTTP_COOKIE_VARS;

		
	$cek = '';
	$cetak = $Mode==2 || $Mode==3 ;
			
	$cbxDlmRibu = $_POST['cbxDlmRibu'];
	$fmLvlBrg = $_REQUEST['fmLevelBarang'];
		
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

	//$tglAwal="$fmFiltThnBuku-01-01";//$tglAkhir="$fmFiltThnBuku-12-31";
		
	$smter=empty($_REQUEST['fmSemester'])? '' : $_REQUEST['fmSemester']; 
	
	// $smter=$_POST['$fmSemester'];	
	if ($smter=='1'){
		$tglAwal="$fmFiltThnBuku-07-01 00:00:00";
		$tglAkhir="$fmFiltThnBuku-12-31 23:59:59";
		$tglAwal2 = ($fmFiltThnBuku).'-06-30';
	} else if ($smter=='2') {
		$tglAwal="$fmFiltThnBuku-01-01 00:00:00";
		$tglAkhir="$fmFiltThnBuku-12-31 23:59:59";
		$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
	} else	{
		$tglAwal="$fmFiltThnBuku-01-01 00:00:00";
		$tglAkhir="$fmFiltThnBuku-06-30 23:59:59";
		$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
	}	
		
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
	
	$KondisiSKPDx = "1=1";
	if($c!='00' && $c !='') $KondisiSKPDx .= " and c= '$c' ";
	if($d!='00' && $d !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " d= '$d' " : " and d= '$d' ";
	if($e!='00' && $e !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " e= '$e' " : " and e= '$e' ";
	if($e1!='00' && $e1 !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " e= '$e1' " : " and e= '$e1' ";
	$paramSKPD = "&c=$c&d=$d&e=$e&e1=$e1";	
		
	//$Main->VERSI_NAME = 'SERANG'; //GARUT, SERANG, JABAR, BOGOR, BDG_BARAT
	//447444,447445,447446,447447,447448,447449
	
	//khusus
	//if($Main->VERSI_NAME == 'BDG_BARAT'  ){
	//	if ($fmFiltThnBuku=='2014')	$kondisi = "  and idbi not in (447444,447445,447446,447447,447448,447449) ";
	//}
	
	if($fmLvlBrg=='1'){ //level barang 3
		$get_table = "v_ref_kib_keu_h_lv3";
		$selectedField = "kint,ka,kb, f,g,h,nm_barang ,";
	}elseif($fmLvlBrg=='2'){ //level barang 4
		$get_table = "v_ref_kib_keu_h1";
		$selectedField = "kint,ka,kb, f,g,h,i,nm_barang ,";
	}else{ //level barang 2 (default)
		$get_table = "v_ref_kib_keu_h";
		$selectedField = "kint,ka,kb, f,g,nm_barang ,";
	}
	
	//ASET TETAP -------------------------------------------------------------------------------------------------	
	
	$bqry =
		"select $selectedField
		
		null as jmlbrgawal,
		null as jmlhargaawal,
				
		null as debet_saldoawal,
		null as kredit_saldoawal,
		
		null as debet_jml_brg,
		null as kredit_jml_brg,
		null as debet_harga_brg,
		null as kredit_harga_brg,
		
		null as jmlbrgakhir,
		null as jmlhargaakhir,
		
		null as susutawal,
		null as debet_susut, 
		null as kredit_susut,
		null as susutakhir,
		
		null as nilai_buku
		
		
		
		from $get_table  
       	
		
		
		where kint='01' and ka='01' and kb<>'00' ";
			

	$aQry = mysql_query($bqry);
	$no=0; $rowno=0;
	while($isix=mysql_fetch_array($aQry)){
	  	$no++;
		if ($isix['g']=='00'){
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];
			$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['debet_harga_brg'];	
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['debet_jml_brg'];
			
			$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['kredit_harga_brg'];	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['kredit_jml_brg'];
			
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
			
			$jmlSusutAw = $jmlSusutAw + $isix['susutawal'];
			$jmlSusutDebet = $jmlSusutDebet + $isix['debet_susut'];	
			$jmlSusutKredit = $jmlSusutKredit + $isix['kredit_susut'];	
			$jmlSusutAk = $jmlSusutAk + $isix['susutakhir'];
			$jmlNilaiBuku += $isix['nilai_buku'];
		}
		
				
		/*$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 0, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['debet_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['kredit_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 0, ',', '.');			
		$vSusutAwal = number_format($isix['susutawal'], 2, ',', '.');			
		$vSusutDebet = number_format($isix['debet_susut'], 2, ',', '.');	
		$vSusutKredit = number_format($isix['kredit_susut'], 2, ',', '.');	
		$vSusutAkhir = number_format($isix['susutakhir'], 2, ',', '.');	
		$vNilaiBuku = number_format($isix['nilai_buku'], 2, ',', '.');	
		*/
		
		
		if($fmLvlBrg=='1'){ //level barang 3
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&bold=$bold";	
		}elseif($fmLvlBrg=='2'){ //level barang 3
			if($isix['g']=='00' ){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['h']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['i']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";	
		}else{ //level barang 2 (default)
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}else{
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
		}
		
		$rowno ++;					
		$bold = ($isix['g']=='00') || ($isix['g']<>'00' && $isix['h']=='00') || ($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00') ? 1 : 0;
		$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";
		$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
		$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
		$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
		
		
		if ($isix['g']=='00'){
			$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				
				$daftarLevelBarang
			
				<td align=right class='$clGaris'><b><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></b></td>
				
				<td align=right class='$clGaris'><b><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></b></td>
				
				<td align=right class='$clGaris'><b><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></b></td>
				
				<td align=right class='$clGaris'><b><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></b></td>
				<td align=right class='$clGaris'><b><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></b></td>".
				//<td align=right class='$clGaris'><b>$vNilaiBuku</b></td>
				"</tr>"	;
		}  else {
			$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				
				$daftarLevelBarang
				
				<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>
				<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>
				<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>
				
				<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>
				<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>
				<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>
				
				<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>
				<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>
				<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>
				
				<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>
				<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>
				<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
				//<td align=right class='$clGaris'><b>$vNilaiBuku</b></td>
				"</tr>"	;
				
				
			
		}
	}

	//ASET LAINNYA ------------------------------------------------------------------------------------------------------------
	$bqry="select 
		aa.f,aa.g,aa.nm_barang ,
		(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,
		(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
		 bb.debet_jml_brg,bb.kredit_jml_brg,bb.debet_harga_brg,bb.kredit_harga_brg,
		(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg + bb.debet_jml_brg - bb.kredit_jml_brg) as jmlbrgakhir,
		(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_harga_brg-bb.kredit_harga_brg) as jmlhargaakhir
		 from v_ref_kib_keu_h  aa
		 left join 
		( select IFNULL(f,'00') as f,IFNULL(g,'00') as g,
		sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, 
		sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
		sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, 
		sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
		
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_jml_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_jml_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',debet,0)) as debet_harga_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',kredit,0)) as kredit_harga_brg
		
		from v_jurnal_aset_lainnya 
		where c<>'' ".$KondisiSKPD."
		group by f,g with rollup
		) bb on aa.f = bb.f and aa.g = bb.g
		 where aa.ka='02'  ";
		 
	$bqry =
		"select $selectedField	
		null as jmlbrgawal,
		null as jmlhargaawal,				
		null as debet_saldoawal,
		null as kredit_saldoawal,		
		null as debet_jml_brg,
		null as kredit_jml_brg,
		null as debet_harga_brg,
		null as kredit_harga_brg,		
		null as jmlbrgakhir,
		null as jmlhargaakhir,		
		null as susutawal,
		null as debet_susut, 
		null as kredit_susut,
		null as susutakhir,		
		null as nilai_buku
		from $get_table  
       	
		#$KondisiSKPDx
		
		where kint='01' and ka='02'   ";
		
	$aQry = mysql_query($bqry);
	while($isix=mysql_fetch_array($aQry)){
	  	$no++;
		if ($isix['g']=='00'){
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];				
			$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['debet_harga_brg'];	
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['debet_jml_brg'];	
			$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['kredit_harga_brg'];	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['kredit_jml_brg'];	
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
			
			$jmlSusutAw = $jmlSusutAw + $isix['susutawal'];
			$jmlSusutDebet = $jmlSusutDebet + $isix['debet_susut'];	
			$jmlSusutKredit = $jmlSusutKredit + $isix['kredit_susut'];	
			$jmlSusutAk = $jmlSusutAk + $isix['susutakhir'];
			$jmlNilaiBuku += $isix['nilai_buku'];
			
		}
		/*$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 0, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['debet_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['kredit_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 0, ',', '.');	
		
		$vSusutAwal = number_format($isix['susutawal'], 2, ',', '.');			
		$vSusutDebet = number_format($isix['debet_susut'], 2, ',', '.');	
		$vSusutKredit = number_format($isix['kredit_susut'], 2, ',', '.');	
		$vSusutAkhir = number_format($isix['susutakhir'], 2, ',', '.');	
		*/
		if($fmLvlBrg=='1'){ //level barang 3
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&bold=$bold";	
		}elseif($fmLvlBrg=='2'){ //level barang 3
			if($isix['g']=='00' ){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['h']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['i']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";	
		}else{ //level barang 2 (default)
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}else{
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td class='$clGaris' >&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>";
			}
		}
		
		if ($isix['g']=='00'){
			/*
				*/
			$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				$daftarLevelBarang
				
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>				
				<td align=right class='$clGaris'><b></b></td>
				
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				<td align=right class='$clGaris'><b></b></td>
				</tr>"	;
			/*
			$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				<td align=center class='$clGaris'><div class='nfmt5'><b>{$isix['f']}</b></td>
				<td align=center class='$clGaris'>&nbsp;</td>
				
				
				<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>
				
				<td align=right class='$clGaris'><b>$TampilJmlBrgAwSKPD</b></td>
				<td align=right class='$clGaris'><b>$TampilJmlHargaAwSKPD</b></td>
				<td align=right class='$clGaris'><b>$vSusutAwal</b></td>
				
				<td align=right class='$clGaris'><b>$TampilJmlBrgTambahSKPD</b></td>
				<td align=right class='$clGaris'><b>$TampilJmlHargaTambahSKPD</b></td>
				<td align=right class='$clGaris'><b>$vSusutKredit</b></td>
				
				<td align=right class='$clGaris'><b>$TampilJmlBrgKurangSKPD</b></td>
				<td align=right class='$clGaris'><b>$TampilJmlHargaKurangSKPD</b></td>				
				<td align=right class='$clGaris'><b>$vSusutDebet</b></td>
				
				<td align=right class='$clGaris'><b>$TampilJmlBrgAkhirSKPD</b></td>
				<td align=right class='$clGaris'><b>$TampilJmlHargaAkhirSKPD</b></td>
				<td align=right class='$clGaris'><b>$vSusutAkhir</b></td>
				</tr>"	;*/
		}  else {
		
			$rowno ++;		
			//$bold = $isix['g']=='00' ? 1 : 0;
			$bold = ($isix['g']=='00') || ($isix['g']<>'00' && $isix['h']=='00') || ($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00') ? 1 : 0;
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&bold=$bold";				
			if($fmLvlBrg=='1' || $fmLvlBrg=='2'){
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";				
			}else{
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=$bold";				
			}
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";

			$ListData .="<tr class='row0'>".
				"<td align=center class='$clGaris'>$no.</td>".
				$daftarLevelBarang.
				
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>".
				
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
								
				"</tr>"	;
			
		}
	}
 	
	//TOTAL INTRA ----------------------------------------------------------------------------------------------------------------
	/*$TampilJmlHargaAwSKPD=number_format($JmlHargaAwSKPD, 2, ',', '.');	
	$TampilJmlBrgAwSKPD=number_format($JmlBrgAwSKPD, 0, ',', '.');	
	$TampilJmlHargaTambahSKPD=number_format($JmlHargaTambahSKPD, 2, ',', '.');	
	$TampilJmlBrgTambahSKPD=number_format($JmlBrgTambahSKPD, 0, ',', '.');	
	$TampilJmlHargaKurangSKPD=number_format($JmlHargaKurangSKPD, 2, ',', '.');	
	$TampilJmlBrgKurangSKPD=number_format($JmlBrgKurangSKPD, 0, ',', '.');	
	$TampilJmlHargaAkhirSKPD=number_format($JmlHargaAkhirKPD, 2, ',', '.');	
	$TampilJmlBrgAkhirSKPD=number_format($JmlBrgAkhirKPD, 0, ',', '.');	
	
	$vjmlSusutAw  = number_format($jmlSusutAw, 2, ',', '.');	
	$vjmlSusutAk  = number_format($jmlSusutAk, 2, ',', '.');	
	$vjmlSusutDebet = number_format($jmlSusutDebet, 2, ',', '.');	
	$vjmlSusutKredit = number_format($jmlSusutKredit, 2, ',', '.');	*/
	
	if($fmLvlBrg=='1'){$cols = "colspan=5";}
	elseif($fmLvlBrg=='2'){	$cols = "colspan=6";}
	else{$cols = "colspan=4";}
	
	$rowno ++;		
	$bold = 1;//$isix['g']=='00' ? 1 : 0;
	$paramKdAkun = "&kint=01";				
	$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
	$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
	$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
	$ListData .=
		"<tr class='row0'>".
		
			"<td class='$clGaris' align=center $cols ><b>TOTAL INTRAKOMPTABLE</b></td>".
		
			"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>".
			
			"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>".
			
			"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>".
			
			"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>".
			"<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
				
		
		"</tr>"	;
 
	$ListData .="<tr><td colspan=12></td></tr>"	;
	
	
	//EXTRA ------------------------------------------------------------------------------------------------------------
	$bqry="select 
		aa.f,aa.g,aa.nm_barang ,
		(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,
		(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
		 bb.debet_jml_brg,bb.kredit_jml_brg,bb.debet_harga_brg,bb.kredit_harga_brg,
		(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg + bb.debet_jml_brg - bb.kredit_jml_brg) as jmlbrgakhir,
		(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_harga_brg-bb.kredit_harga_brg) as jmlhargaakhir
		 from v_ref_kib_keu_h  aa
		 left join 
		( select IFNULL(f,'00') as f,IFNULL(g,'00') as g,
		sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
		sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
		
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_jml_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_jml_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',debet,0)) as debet_harga_brg,
		sum(if(tgl_buku>='$tglAwal' && tgl_buku<='$tglAkhir',kredit,0)) as kredit_harga_brg
		from v_jurnal_aset_ekstra 
		where c<>'' ".$KondisiSKPD."
		#group by f,g with rollup
		) bb #on aa.f = bb.f and aa.g = bb.g
		on aa.kint = '02'
		 where aa.kint='02'  ";
	$bqry =
		"select $selectedField		
		null as jmlbrgawal,
		null as jmlhargaawal,				
		null as debet_saldoawal,
		null as kredit_saldoawal,		
		null as debet_jml_brg,
		null as kredit_jml_brg,
		null as debet_harga_brg,
		null as kredit_harga_brg,		
		null as jmlbrgakhir,
		null as jmlhargaakhir,		
		null as susutawal,
		null as debet_susut, 
		null as kredit_susut,
		null as susutakhir,		
		null as nilai_buku
		from $get_table  
       	
		#$KondisiSKPDx
		
		where kint='02'   ";
	$aQry = mysql_query($bqry);
	while($isix=mysql_fetch_array($aQry)){
	  	$no++;
		if ($isix['g']=='00'){
			$JmlHargaAwSKPD=$JmlHargaAwSKPD+$isix['jmlhargaawal'];	
			$JmlBrgAwSKPD=$JmlBrgAwSKPD+$isix['jmlbrgawal'];				
			$JmlHargaTambahSKPD=$JmlHargaTambahSKPD+$isix['debet_harga_brg'];	
			$JmlBrgTambahSKPD=$JmlBrgTambahSKPD+$isix['debet_jml_brg'];	
			$JmlHargaKurangSKPD=$JmlHargaKurangSKPD+$isix['kredit_harga_brg'];	
			$JmlBrgKurangSKPD=$JmlBrgKurangSKPD+$isix['kredit_jml_brg'];	
			$JmlHargaAkhirKPD=$JmlHargaAkhirKPD+$isix['jmlhargaakhir'];	
			$JmlBrgAkhirKPD=$JmlBrgAkhirKPD+$isix['jmlbrgakhir'];
			
			$jmlSusutAw = $jmlSusutAw + $isix['susutawal'];
			$jmlSusutDebet = $jmlSusutDebet + $isix['debet_susut'];	
			$jmlSusutKredit = $jmlSusutKredit + $isix['kredit_susut'];	
			$jmlSusutAk = $jmlSusutAk + $isix['susutakhir'];
			
		}
		/*$TampilJmlHargaAwSKPD=number_format($isix['jmlhargaawal'], 2, ',', '.');	
		$TampilJmlBrgAwSKPD=number_format($isix['jmlbrgawal'], 0, ',', '.');	
		$TampilJmlHargaTambahSKPD=number_format($isix['debet_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgTambahSKPD=number_format($isix['debet_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaKurangSKPD=number_format($isix['kredit_harga_brg'], 2, ',', '.');	
		$TampilJmlBrgKurangSKPD=number_format($isix['kredit_jml_brg'], 0, ',', '.');	
		$TampilJmlHargaAkhirSKPD=number_format($isix['jmlhargaakhir'], 2, ',', '.');	
		$TampilJmlBrgAkhirSKPD=number_format($isix['jmlbrgakhir'], 0, ',', '.');	
			
		$vSusutAwal = number_format($isix['susutawal'], 2, ',', '.');			
		$vSusutDebet = number_format($isix['debet_susut'], 2, ',', '.');	
		$vSusutKredit = number_format($isix['kredit_susut'], 2, ',', '.');	
		$vSusutAkhir = number_format($isix['susutakhir'], 2, ',', '.');	*/
		if($fmLvlBrg=='1'){ //level barang 3
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'><b></b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>EXTRAKOMPTABLE</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td class='$clGaris' >{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&bold=$bold";	
		}elseif($fmLvlBrg=='2'){ //level barang 3
			if($isix['g']=='00' ){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'><b></b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>EXTRAKOMPTABLE</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['f']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['g']}</b></td>
									<td align=center class='$clGaris'><b><div class='nfmt5'>{$isix['h']}</b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>{$isix['nm_barang']}</b></td>";
			}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']<>'00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['h']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['i']}</td>
									<td class='$clGaris' >{$isix['nm_barang']}</td>";
			}
			//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";	
		}else{ //level barang 2 (default)
			if($isix['g']=='00'){
				$daftarLevelBarang = "<td align=center class='$clGaris'><b><div class='nfmt5'></b></td>
									<td align=center class='$clGaris'>&nbsp;</td>
									<td class='$clGaris' ><b>EXTRAKOMPTABLE</b></td>";
			}else{
				$daftarLevelBarang = "<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>
									<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>
									<td class='$clGaris' >{$isix['nm_barang']}</td>";
			}
		}
		
		$rowno ++;		
		$bold = 1;//$isix['g']=='00' ? 1 : 0;
		//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&bold=$bold";				
		$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=$bold";				
		$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
		$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
		$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
		if ($isix['g']=='00'){
			$ListData .="<tr class='row0'>".
				"<td align=center class='$clGaris'>$no.</td>".
				/*"<td align=center class='$clGaris'><div class='nfmt5'><b></b></td>".
				"<td align=center class='$clGaris'>&nbsp;</td>".
			
				"<td class='$clGaris' ><b>EXTRAKOMPTABLE</b></td>".*/
				$daftarLevelBarang.
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>".
				
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
				
				
			
				"</tr>"	;
		}  else {
			$ListData .="<tr class='row0'>".
				"<td align=center class='$clGaris'>$no.</td>".
				"<td align=center class='$clGaris'><div class='nfmt5'>{$isix['f']}</td>".
				"<td align=center class='$clGaris'><div class='nfmt5'>{$isix['g']}</td>".
			
				"<td  class='$clGaris'>{$isix['nm_barang']}</td>".
				
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>".
				
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
				
				
				"</tr>"	;
			
		}
	}
	
	//TOTAL INTRA + EXTRA ----------------------------------------------------------------------------------------------------------------
	/*$TampilJmlHargaAwSKPD=number_format($JmlHargaAwSKPD, 2, ',', '.');	
	$TampilJmlBrgAwSKPD=number_format($JmlBrgAwSKPD, 0, ',', '.');	
	$TampilJmlHargaTambahSKPD=number_format($JmlHargaTambahSKPD, 2, ',', '.');	
	$TampilJmlBrgTambahSKPD=number_format($JmlBrgTambahSKPD, 0, ',', '.');	
	$TampilJmlHargaKurangSKPD=number_format($JmlHargaKurangSKPD, 2, ',', '.');	
	$TampilJmlBrgKurangSKPD=number_format($JmlBrgKurangSKPD, 0, ',', '.');	
	$TampilJmlHargaAkhirSKPD=number_format($JmlHargaAkhirKPD, 2, ',', '.');	
	$TampilJmlBrgAkhirSKPD=number_format($JmlBrgAkhirKPD, 0, ',', '.');	
	
	
	$vjmlSusutAwal = number_format($jmlSusutAw, 2, ',', '.');			
	$vjmlSusutDebet = number_format($jmlSusutDebet, 2, ',', '.');	
	$vjmlSusutKredit = number_format($jmlSusutKredit, 2, ',', '.');	
	$vjmlSusutAkhir = number_format($jmlSusutAk, 2, ',', '.');*/	
	
	$rowno ++;		
	$bold = 1;// $isix['g']=='00' ? 1 : 0;
	$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&bold=$bold";				
	$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
	$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
	$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";	
	
	$ListData .="<tr class='row0'>".
		
		"<td class='$clGaris' align=center $cols ><b>TOTAL INTRAKOMPTABLE + EXTRAKOMPTABLE</b></td>".
		
		"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlBrgAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlHargaAwSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap' >$vSusutAwal</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlBrgTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlHargaTambahSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap' >$vSusutKredit</a></td>".
				
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&isjmlbrg=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlBrgKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&tanpasusut=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlHargaKurangSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap' >$vSusutDebet</a></td>".
				
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1&isjmlbrg=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlBrgAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlHargaAkhirSKPD</a></td>".
				"<td align=right class='$clGaris'><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap' >$vSusutAkhir</a></td>".
				
		
		"</tr>"	;
 
	$ListData .="<tr><td colspan=12></td></tr>"	;
	
	
		
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
				<td class=\"judulcetak\" colspan=6>REKAP NERACA</td>
			</tr>
			</table>"	
			.PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,$this->cetak_xls)."<br>";
	}
	
}


$Pembukuan3_2Ajx = new PembukuanObj3Ajx();

?>