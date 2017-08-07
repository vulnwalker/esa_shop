<?php

class Pembukuan2AjxObj extends DaftarObj2{
	var $Prefix = 'Pembukuan2Ajx'; //jsname
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
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '';
	var $totalAllStr = '';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='RekapNeraca.xls';
	var $Cetak_Judul = 'Rekap Neraca';
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
	var $PageTitle = 'Kertas Kerja';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $jml_data=0;
	var $totBrgAset = 0;
	var $totHrgAset = 0;
	
	/*
	$TampilJmlTotSaldoAwSKPD=number_format($JmlSaldoAwSKPD, 2, ',', '.');
		$TampilJmlTotBelanjaSKPD=number_format($JmlBelanjaSKPD, 2, ',', '.');
		$TampilJmlTotAtribusiSKPD=number_format($JmlAtribusiSKPD, 2, ',', '.');
		$TampilJmlTotKapitalisasiDSKPD=number_format($JmlKapitalisasiDSKPD, 2, ',', '.');
		$TampilJmlTotKapitalisasiKSKPD=number_format($JmlKapitalisasiKSKPD, 2, ',', '.');
		$TampilJmlTotHibahDSKPD=number_format($JmlHibahDSKPD, 2, ',', '.');
		$TampilJmlTotHibahKSKPD=number_format($JmlHibahKSKPD, 2, ',', '.');
		$TampilJmlTotMutasiDSKPD=number_format($JmlMutasiDSKPD, 2, ',', '.');
		$TampilJmlTotMutasiKSKPD=number_format($JmlMutasiKSKPD, 2, ',', '.');
		$TampilJmlTotPenilaianDSKPD=number_format($JmlPenilaianDSKPD, 2, ',', '.');
		$TampilJmlTotPenghapusanKSKPD=number_format($JmlPenghapusanKSKPD, 2, ',', '.');
		$TampilJmlTotPembukuanDSKPD=number_format($JmlPembukuanDSKPD, 2, ',', '.');
		$TampilJmlTotPembukuanKSKPD=number_format($JmlPembukuanKSKPD, 2, ',', '.');
		$TampilJmlTotReklassDSKPD=number_format($JmlReklassDSKPD, 2, ',', '.');
		$TampilJmlTotReklassKSKPD=number_format($JmlReklassKSKPD, 2, ',', '.');
		$TampilJmlTotSaldoAkSKPD=number_format($JmlSaldoAkSKPD, 2, ',', '.');	
	*/
	
	var $JmlSaldoAwSKPD = 0;
	var $JmlBelanjaSKPD = 0;
	var $JmlAtribusiSKPD = 0;
	var $JmlKapitalisasiDSKPD = 0;
	var $JmlKapitalisasiKSKPD = 0;
	var $JmlHibahDSKPD = 0;
	var $JmlHibahKSKPD = 0;
	var $JmlMutasiDSKPD = 0;
	var $JmlMutasiKSKPD = 0;
	var $JmlPenilaianDSKPD = 0;
	var $JmlPenghapusanKSKPD = 0;
	var $JmlPembukuanDSKPD = 0;
	var $JmlPembukuanKSKPD = 0;
	var $JmlReklassDSKPD = 0;
	var $JmlReklassKSKPD = 0;
	var $JmlSaldoAkSKPD  = 0;
	
	/**
	function set_selector_other($tipe){
		$cek = ''; $err=''; $content=''; $json=FALSE;
		switch($tipe){	
			case 'total':{
				$get = $this->changeTahun();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}				
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	**/
	

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
		 /*if($Main->VERSI_NAME == 'KOTA_BANDUNG' ){
		 	$title = "Kertas Kerja";
		 }else{
		 	$title = "Rekap Neraca ";
		 }*/
		return 'Kertas Kerja';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>Rekap Neraca";
		if ($this->cetak_xls==TRUE)
		{
			$judul="<table width='100%'><tr><td colspan=6>Rekap Neraca x</td></tr></table>";
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
			"<A href=\"index.php?Pg=05&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];

		//if($Pg == 'sensus'){
		//	$styleMenu = " style='color:blue;' ";	
		//}
		$menu = $_REQUEST['menu'];
				
		
		if($Main->VERSI_NAME=='JABAR'){
			$styleMenu = " style='color:blue;' ";	
			$styleMenu2_4 = " style='color:blue;' ";
			$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
				" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu >REKAP NERACA</a>": '';
		
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
				<!-- <A href=\"pages.php?Pg=Pembukuan\" title='Rekap Neraca' >REKAP NERACA</a> |
				 <A href=\"index.php?Pg=05&jns=tetap\" title='Rincian Neraca' >RINCIAN NERACA</a> 
				| -->
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap Aset' >REKAP ASET</a>   
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI II</a>
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
			//$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
	
			if($Main->MODUL_SENSUS) $menubar=$menubar."	<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >INVENTARISASI</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				
			$menubar=$menubar." | <A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> 	
				&nbsp&nbsp&nbsp
				</td></tr>$menu_pembukuan1	
						
				</table>".
				"";
			
			
			
			
		}else{
			
			$styleMenu = " style='color:blue;' ";	
			$styleMenu2_4 = " style='color:blue;' ";
			$menu_LRA = $Main->VERSI_NAME == 'SERANG' ? 
						"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
						<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
						<A href=\"pages.php?Pg=perbandingan\" title='Perbandingan LRA - Belanja Modal' >PERBANDNGAN LRA</a> 
						&nbsp&nbsp&nbsp</td></tr>
						</table>" : "";
			//$txtRakapbi = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "REKAP NERACA" : "REKAP BI";
			//$txtRekapneraca = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "KERTAS KERJA" : "REKAP NERACA";
			$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
			$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ? " | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu >KERTAS KERJA</a>": '';
			$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
				"<A href=\"index.php?Pg=05&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
			
			
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
				<!--| <A href=\"pages.php?Pg=Rekap5\" title='Rekap BI'  >REKAP BI 2</a>	-->		
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
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				$menubar=$menubar."&nbsp&nbsp&nbsp
				</td></tr>$menu_pembukuan1			
				</table>".			
				$menu_LRA.
				""	;
		}
		return $menubar;
			
	}
	
	
	
	function setPage_HeaderOther_(){
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
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu >REKAP NERACA</a>": '';
		
			$menu_pembukuan1 =
		($Main->MODUL_AKUNTANSI )?
		"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>



	<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Tanah'>KIB A</a>  |  
	<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Peralatan & Mesin'>KIB B</a>  |  
	<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Gedung & Bangunan'>KIB C</a>  |  
	<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
	<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Lainnya'>KIB E</a>  |  
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Konstruksi Dalam Pengerjaan'>KIB F</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
	$menu_kibg1
	<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |  
	$menu_penyusutan
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a> 
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
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
			<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>$menu_pembukuan1			
			</table>".
			
			
			""
			;
		
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
		$arrLevelBarang = array(
			array('1','Level 2'),
			array('2','Level 3'),
			array('3','Level 4')
		);
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
		//$cbxTotal = $_REQUEST['cbxTotal'];
		//$vcbxtotal = $cbxTotal ?  " checked='true' " : '';
		$Semester = "Semester ".cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester1,'','Semester I');
		$cmbLevelBarang = "  Level Barang ".cmb2D_v2('fmLevelBarang',$fmLevelBarang,$arrLevelBarang,'','Level 1');
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
					"<input type='hidden' id='daftarcetak' name='daftarcetak' value='' >".
					$cmbLevelBarang
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/,
					//"<input type='checkbox' name='cbxTotal' id='cbxTotal' value='1' $vcbxtotal  >Total"
				),$this->Prefix.".refreshList(true,'$Main->VERSI_NAME')",TRUE
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
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$fmFiltThnBuku = $_POST['fmFiltThnBuku'];
		$fmSemester = $_POST['fmSemester'];
		$fmLvlBrg = $_POST['fmLevelBarang'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		
		$thnsbl =$fmFiltThnBuku -1;
		
		//default semester 1
		$thnsbl = '1 Jan '.$fmFiltThnBuku;
		$thnakhir= '30 Jun '.$fmFiltThnBuku;
		switch($fmSemester){
			case '1' :  $thnsbl = '1 Jul '.$fmFiltThnBuku; $thnakhir= '31 Des '. $fmFiltThnBuku; break; //semester 2
			case '2' :  $thnsbl = '1 Jan '.$fmFiltThnBuku ; $thnakhir= '31 Des '.$fmFiltThnBuku; break; //tahun			
		}
		if($fmLvlBrg=='2'){
			$cols = "colspan=3";
			$tampilKodeBarang = "<th class=\"th01\" width='10'>f</th>
								<th class=\"th01\" width='10'>g</th>
								<th class=\"th01\" width='10'>h</th>";
			$tampilHeaderAngka = "<th class=\"th01\" >(10)</th>";
		}elseif($fmLvlBrg=='3'){
			$cols = "colspan=4";
			$tampilKodeBarang = "<th class=\"th01\" width='10'>f</th>
								<th class=\"th01\" width='10'>g</th>
								<th class=\"th01\" width='10'>h</th>
								<th class=\"th01\" width='10'>i</th>";
			$tampilHeaderAngka = "<th class=\"th01\" >(10)</th>
								<th class=\"th01\" >(11)</th>";
		}else{
			$cols = "colspan=2";
			$tampilKodeBarang = "<th class=\"th01\" width='50'>f</th>
								<th class=\"th01\" width='50'>g</th>
								";
			$tampilHeaderAngka = "";
		}
			
		$headerTable =
			"<tr>
				<th class=\"th01\" width='30' rowspan='2' >No. </th>
				<th class=\"th02\" width='50' $cols>Kode Barang</th>
				<th class=\"th01\" width='300'  rowspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SKPD&nbsp;/&nbsp;JENIS&nbsp;ASET&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th class=\"th02\" colspan=2 >SALDO AWAL $thnsbl</th>				
				<th class=\"th02\" >BELANJA MODAL</th>
				<th class=\"th02\" >ATRIBUSI</th>
				<th class=\"th02\" colspan=2 >KAPITALISASI</th>
				<th class=\"th02\" colspan=2 >HIBAH</th>
				<th class=\"th02\" colspan=2 >PINDAH ANTAR SKPD</th>
				<th class=\"th02\" >PENILAIAN</th>
				<th class=\"th02\" >PENGHAPUSAN</th>
				<th class=\"th02\" colspan=2 >KOREKSI PEMBUKUAN </th>
				<th class=\"th02\" colspan=2 >REKLASS </th>
				
				<th class=\"th02\" colspan=2 >AKUM. PENYUSUTAN </th>
				<th class=\"th02\" colspan='3' >SALDO AKHIR $thnakhir</th>
			</tr>
			<tr>
				$tampilKodeBarang
				<th class=\"th03\" width='100' >PEROLEHAN</th>
				<th class=\"th03\" width='100' >AKUM. PENYUSUTAN</th>
				<th class=\"th03\" >DEBET</th>				
				<th class=\"th03\" >DEBET</th>
				<th class=\"th03\" >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				
				<th class=\"th03\" width='100' >PEROLEHAN</th>
				<th class=\"th03\" width='100' >AKUM. PENYUSUTAN</th>
				<th class=\"th03\" width='100' >NILAI BUKU</th>
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
	
	
	function gen_table_data($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;

		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$fmLvlBrg = $_POST['fmLevelBarang'];
		$cbxTotal = 1;//$_REQUEST['cbxTotal'];
			
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
		
		//$tglAwal="$fmFiltThnBuku-01-01";
		//$tglAkhir="$fmFiltThnBuku-12-31";
			
		$smter=empty($_REQUEST['fmSemester'])? '' : $_REQUEST['fmSemester']; 
	
	// $smter=$_POST['$fmSemester'];

		if ($smter=='1') 
		{
			$tglAwal="$fmFiltThnBuku-07-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku).'-06-30';
		} else if ($smter=='2') 
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
		} else
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-06-30";
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


		$JmlSaldoAwTot=0; $JmlBelanjaTot=0;	$JmlAtribusiTot=0; $JmlKapitalisasiDTot=0; $JmlKapitalisasiKTot=0;
		$JmlHibahDTot=0; $JmlHibahKTot=0; $JmlMutasiDTot=0; $JmlMutasiKTot=0; $JmlPenilaianDTot=0; $JmlPenghapusanKTot=0;
		$JmlPembukuanDTot=0; $JmlPembukuanKTot=0; $JmlPembukuanKTot=0; $JmlReklassDTot=0; $JmlReklassKTot=0; $JmlSaldoAkTot=0;	

		$JmlSaldoAw=0; $JmlBelanja=0; $JmlAtribusi=0; $JmlKapitalisasiD=0; $JmlKapitalisasiK=0; $JmlHibahD=0; $JmlHibahK=0;
		$JmlMutasiD=0; $JmlMutasiK=0; $JmlPenilaianD=0; $JmlPenghapusanK=0; $JmlPembukuanD=0; $JmlPembukuanK=0;	$JmlReklassD=0;
		$JmlReklassK=0; $JmlSaldoAk=0;
		
		$JmlSaldoAwTotx0; $JmlBelanjaTotx0;	$JmlAtribusiTotx0; $JmlKapitalisasiDTotx0; $JmlKapitalisasiKTotx0; $JmlHibahDTotx0;
		$JmlHibahKTotx0; $JmlMutasiDTotx0; $JmlMutasiKTotx0; $JmlPenilaianDTotx0; $JmlPenghapusanKTotx0; $JmlPembukuanDTotx0;
		$JmlPembukuanKTotx0; $JmlReklassDTotx0; $JmlReklassKTotx0; $JmlSaldoAkTotx0;						

		$Kondisi = $KondisiAsal." and staset<=4 and f<='06' ";
		
		if ( $cbxTotal ){
			$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,
				'T O T A L' as nm_barcode  ";
		}else{
			if ($e1=='00' || $e1=='000' ){
				$sqry=" select * from v_opd  where c<>'' $KondisiSKPD ";
			} else {
				$sqry=" select *,nm_skpd as nmopd from ref_skpd  where c<>'' $KondisiSKPD ";
			}	
			$sqry2= $sqry;
			
			//if($c<> '00' ) 
		}
		//$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,				'T O T A L' as nm_barcode  ";
		//$sqry=" select *,nm_skpd as nmopd from ref_skpd where  c='04' and d<>'00' and e='00' ";
		$cek .=$sqry;
		$cskpd=0;
		$Qry = mysql_query($sqry);
		while($isi=mysql_fetch_array($Qry)){
			$cskpd++;
			if ( $cbxTotal ==TRUE){
				$KondisiSKPDx = "1=1";
				if($c!='00' && $c !='') $KondisiSKPDx .= " and c= '$c' ";
				if($d!='00' && $d !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " d= '$d' " : " and d= '$d' ";
				$paramSKPD = "&c=$c&d=$d&e=$e&e1=$e1";		$cek.= ' no=1 ';		
			}else{
				if ($e1=='00' || $e1=='000' ){
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' ";							
				} else {
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."' ";					
				}
				$paramSKPD = "&c=".$isi['c']."&d=".$isi['d']."&e=".$isi['e']."&e1=".$isi['e1'];
				$cek.= ' no=2 ';
			}
		
		if($fmLvlBrg=='2'){
			$cols = "colspan=4";
		}elseif($fmLvlBrg=='3'){
			$cols = "colspan=5";
		}else{
			$cols = "colspan=3";
		}
		
		$cek = $Main->SHOW_CEK  ? $cek : '';
			$ListData .="<tr class='row1'>
				<td class='GarisDaftar' align=right>$cskpd.</td>
				<td class='GarisDaftar' $cols><b>{$isi['nmopd']}</b> <div id='div_cek' style='display:none'>$cek</div></td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar'  align=right>&nbsp;</td><td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td> <td class='GarisDaftar' align=right>&nbsp;</td> <td class='GarisDaftar' align=right>&nbsp;</td> <td class='GarisDaftar' align=right>&nbsp;</td> <td class='GarisDaftar' align=right>&nbsp;</td>
				</tr>"	;
			
			$JmlSaldoAwSKPD=0; $JmlBelanjaSKPD=0; $JmlAtribusiSKPD=0; $JmlKapitalisasiDSKPD=0; $JmlKapitalisasiKSKPD=0;
			$JmlHibahDSKPD=0; $JmlHibahKSKPD=0;	$JmlMutasiDSKPD=0; $JmlMutasiKSKPD=0; $JmlPenilaianDSKPD=0;	$JmlPenghapusanKSKPD=0;
			$JmlPembukuanDSKPD=0; $JmlPembukuanKSKPD=0; $JmlReklassDSKPD=0;	$JmlReklassKSKPD=0;	$JmlSaldoAkSKPD=0;
	
			$JmlSaldoAwTot=0; $JmlBelanjaTot=0;	$JmlAtribusiTot=0;	$JmlKapitalisasiDTot=0;	$JmlKapitalisasiKTot=0;
			$JmlHibahDTot=0; $JmlHibahKTot=0; $JmlMutasiDTot=0; $JmlMutasiKTot=0; $JmlPenilaianDTot=0; $JmlPenghapusanKTot=0;
			$JmlPembukuanDTot=0; $JmlPembukuanKTot=0; $JmlReklassDTot=0; $JmlReklassKTot=0;	$JmlSaldoAkTot=0;			
			
			//qry aset tetap ---------------------------------------------------------------------------------------				
			switch($fmLvlBrg){
				case '1': $get_table="v_ref_kib_keu"; $sel="f,g"; $konLvl1=""; break;
				case '2': $get_table="v_ref_kib_keu_h_lv3"; $sel="f,g,h"; $konLvl1=""; break;
				case '3': $get_table="v_ref_kib_keu_h1"; $sel="f,g,h,i"; $konLvl1=""; break;
				default : $get_table="v_ref_kib_keu"; $sel="f,g"; $konLvl1="and g='00'"; break;
			}
			$bqry =
				"select kint,ka,kb, $sel,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from $get_table  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='01' and kb<>'00' $konLvl1";
			
			/*$bqry =
				"select kint,ka,kb, f,g,h,i,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu_h1  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='01' and kb<>'00' ";
			*/	
			$aQry = mysql_query($bqry);		$rowno=0;
			while($isix=mysql_fetch_array($aQry)){
		  		$kdBidang = $isix['g'] == "00" ? "" : $isix['g'];
		  		$h = $isix['h'] == "00" ? "" : $isix['h'];
		  		$i = $isix['i'] == "00" ? "" : $isix['i'];
				
				if($isix['g']=='00') $JmlSaldoAwSKPD=$JmlSaldoAwSKPD+$isix['jmlhargaawal'];	
				if($isix['g']=='00')$JmlBelanjaSKPD=$JmlBelanjaSKPD+$isix['debet_bmd'];
				if($isix['g']=='00')$JmlAtribusiSKPD=$JmlAtribusiSKPD+$isix['debet_atribusi'];
				if($isix['g']=='00')$JmlKapitalisasiDSKPD=$JmlKapitalisasiDSKPD+$isix['debet_kapitalisasi'];	
				if($isix['g']=='00')$JmlKapitalisasiKSKPD=$JmlKapitalisasiKSKPD+$isix['kredit_kapitalisasi'];
				if($isix['g']=='00')$JmlHibahDSKPD=$JmlHibahDSKPD+$isix['debet_hibah'];
				if($isix['g']=='00')$JmlHibahKSKPD=$JmlHibahKSKPD+$isix['kredit_hibah'];
				if($isix['g']=='00')$JmlMutasiDSKPD=$JmlMutasiDSKPD+$isix['debet_mutasi'];
				if($isix['g']=='00')$JmlMutasiKSKPD=$JmlMutasiKSKPD+$isix['kredit_mutasi'];
				if($isix['g']=='00')$JmlPenilaianDSKPD=$JmlPenilaianDSKPD+$isix['debet_penilaian'];
				if($isix['g']=='00')$JmlPenghapusanKSKPD=$JmlPenghapusanKSKPD+$isix['kredit_penghapusan'];
				if($isix['g']=='00')$JmlPembukuanDSKPD=$JmlPembukuanDSKPD+$isix['debet_koreksi'];
				if($isix['g']=='00')$JmlPembukuanKSKPD=$JmlPembukuanKSKPD+$isix['kredit_koreksi'];
				if($isix['g']=='00')$JmlReklassDSKPD=$JmlReklassDSKPD+$isix['debet_reklass'];
				if($isix['g']=='00')$JmlReklassKSKPD=$JmlReklassKSKPD+$isix['kredit_reklass'];
				if($isix['g']=='00')$JmlSaldoAkSKPD=$JmlSaldoAkSKPD+$isix['jmlhitakhir'];
				
				if($isix['g']=='00')$JmlSusutAk+=$isix['susutakhir'];
				if($isix['g']=='00')$JmlSusutAw+=$isix['susutawal'];
				if($isix['g']=='00')$Jmldebet_susut+=$isix['debet_susut'];
				if($isix['g']=='00')$Jmlkredit_susut+=$isix['kredit_susut'];
				if($isix['g']=='00')$JmlNilaiBuku+=$isix['nilai_buku'];
				
		
				
				
				//tampil aset tetap -------------------------------------------------------------------
				$rowno ++;
				if($fmLvlBrg=='1' || $fmLvlBrg=='2' || $fmLvlBrg=='3'){
					$bold = ($isix['g']=='00') || ($isix['g']<>'00' && $isix['h']=='00') || ($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00') ? 1 : 0;
				}else{
					$bold = 0;
				}
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";
				
				$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
				$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
				$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
				//$vnmbarang = $isix['g']=='00' ?  "<b>&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
				//bold
				if($fmLvlBrg=='2' || $fmLvlBrg=='3'){
					if($isix['g']=='00'){
						$vnmbarang = "<b>{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>$kdBidang</b>";
						$vh = "<b>$h</b>";
						$vi = "<b>$i</b>";
					}elseif($isix['g']<>'00' && $isix['h']=='00'){
						$vnmbarang = "<b>&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>$kdBidang</b>";
						$vh = "<b>$h</b>";
						$vi = "<b>$i</b>";
					}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00'){
						$vnmbarang = "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>$kdBidang</b>";
						$vh = "<b>$h</b>";
						$vi = "<b>$i</b>";
					}else{
						$vnmbarang = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
						$vf = "{$isix['f']}";
						$vg = "$kdBidang";
						$vh = "$h";
						$vi = "$i";
					}
				}else{
					if($fmLvlBrg=='1'){
						$vnmbarang = $isix['g']=='00' ?  "<b>&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
						$vf = $isix['g']=='00' ? "<b>{$isix['f']}</b>" : "{$isix['f']}";
						$vg = $isix['g']=='00' ? "<b>$kdBidang</b>" : "$kdBidang";
						$vh = $isix['g']=='00' ? "<b>$h</b>" : "$h";
						$vi = $isix['g']=='00' ? "<b>$i</b>" : "$i";
					}else{
						$vnmbarang = "{$isix['nm_barang']}";
							$vf = "{$isix['f']}";
							$vg = "$kdBidang";
							$vh = "$h";
							$vi = "$i";
					}
				}
				
				//kode barang f,g,h,i
				if($fmLvlBrg=='2'){
					$daftarKodeBarang = "<td class='GarisDaftar' align=right>$vh</td>";
				}elseif($fmLvlBrg=='3'){
					$daftarKodeBarang = "<td class='GarisDaftar' align=right>$vh</td>
										<td class='GarisDaftar' align=right>$vi</td>";
				}else{
					$daftarKodeBarang = "";
				}
				
				$ListData .="<tr class='row1'>
				<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>$vf</td>
				<td class='GarisDaftar' align=right>$vg</td>
				$daftarKodeBarang
				<td class='GarisDaftar' >$vnmbarang</td>
				<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>
				
				<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>
				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>
				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>
				<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>
				
				<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>
				<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>
				</tr>"	;
		  
		  	}																																																									
			
			
			//tampil total aset tetap --------------------------------------------------------------
			if($fmLvlBrg=='2'){
				$cols = "colspan=4";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=5";
			}else{
				$cols = "colspan=3";
			}
			$paramKdAkun = "&kint=01&ka=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr class='row1'>
			<td class='GarisDaftar'  align=right>&nbsp;</td>
			<td class='GarisDaftar' $cols>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset Tetap</b></td>
			<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlTotSaldoAwSKPD</a></td>
			<td class='GarisDaftar'  align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlSusutAw</a></td>
			
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_3' name='vrekap' >$TampilJmlTotBelanjaSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotAtribusiSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_5' name='vrekap' >$TampilJmlTotKapitalisasiDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_6' name='vrekap' >$TampilJmlTotKapitalisasiKSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_7' name='vrekap' >$TampilJmlTotHibahDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_8' name='vrekap' >$TampilJmlTotHibahKSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_9' name='vrekap' >$TampilJmlTotMutasiDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_10' name='vrekap' >$TampilJmlTotMutasiKSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_' style='color:black' id='vrekap_$rowno"."_11' name='vrekap' >$TampilJmlTotPenilaianDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_12' name='vrekap' >$TampilJmlTotPenghapusanKSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_13' name='vrekap' >$TampilJmlTotPembukuanDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_14' name='vrekap' >$TampilJmlTotPembukuanKSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_15' name='vrekap' >$TampilJmlTotReklassDSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_16' name='vrekap' >$TampilJmlTotReklassKSKPD</a></td>
			
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap' >$TampilJmldebet_susut</a></td>
			<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap' >$TampilJmlkredit_susut</a></td>
			
			<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_19' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap' >$TampilJmlSusutAk</a></td>
			<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap' >$TampilJmlNilaiBuku</a></td>
			</tr>"	;
			
			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwSKPD;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaSKPD;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiSKPD;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDSKPD;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKSKPD;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDSKPD;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKSKPD;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDSKPD;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKSKPD;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDSKPD;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKSKPD;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDSKPD;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKSKPD;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDSKPD;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKSKPD;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkSKPD;		
			
			$JmlSusutAkTot+=$JmlSusutAk;
			$JmlSusutAwTot+=$JmlSusutAw;
			$Jmldebet_susutTot+=$Jmldebet_susut;
			$Jmlkredit_susutTot+=$Jmlkredit_susut;
			$JmlNilaiBukuTot+=$JmlNilaiBuku;
			
			
			$JmlSaldoAwSKPD=0;	
			$JmlBelanjaSKPD=0;
			$JmlAtribusiSKPD=0;
			$JmlKapitalisasiDSKPD=0;	
			$JmlKapitalisasiKSKPD=0;
			$JmlHibahDSKPD=0;
			$JmlHibahKSKPD=0;
			$JmlMutasiDSKPD=0;
			$JmlMutasiKSKPD=0;
			$JmlPenilaianDSKPD=0;
			$JmlPenghapusanKSKPD=0;
			$JmlPembukuanDSKPD=0;
			$JmlPembukuanKSKPD=0;
			$JmlReklassDSKPD=0;
			$JmlReklassKSKPD=0;
			$JmlSaldoAkSKPD=0;
			$JmlSusutAk =0;
			$JmlSusutAw=0;
			$Jmldebet_susut=0;
			$Jmlkredit_susut=0;
			$JmlNilaiBuku =0;
			
			//query aset lainnya --------------------------------------------- 
			$bqry =
				"select kint,ka,kb, $sel,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from $get_table  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='02' and kb<>'00'  ";
				
			/*$bqry =
				"select kint,ka,kb, f,g,h,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu_h1  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='02' and kb<>'00'  ";
			*/
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
				$rowno++;
				/*$JmlSaldoAwSKPD=$JmlSaldoAwSKPD+$isix['jmlhargaawal'];	
				$JmlBelanjaSKPD=$JmlBelanjaSKPD+$isix['debet_bmd'];
				$JmlAtribusiSKPD=$JmlAtribusiSKPD+$isix['debet_atribusi'];
				$JmlKapitalisasiDSKPD=$JmlKapitalisasiDSKPD+$isix['debet_kapitalisasi'];	
				$JmlKapitalisasiKSKPD=$JmlKapitalisasiKSKPD+$isix['kredit_kapitalisasi'];
				$JmlHibahDSKPD=$JmlHibahDSKPD+$isix['debet_hibah'];
				$JmlHibahKSKPD=$JmlHibahKSKPD+$isix['kredit_hibah'];
				$JmlMutasiDSKPD=$JmlMutasiDSKPD+$isix['debet_mutasi'];
				$JmlMutasiKSKPD=$JmlMutasiKSKPD+$isix['kredit_mutasi'];
				$JmlPenilaianDSKPD=$JmlPenilaianDSKPD+$isix['debet_penilaian'];
				$JmlPenghapusanKSKPD=$JmlPenghapusanKSKPD+$isix['kredit_penghapusan'];
				$JmlPembukuanDSKPD=$JmlPembukuanDSKPD+$isix['debet_koreksi'];
				$JmlPembukuanKSKPD=$JmlPembukuanKSKPD+$isix['kredit_koreksi'];
				$JmlReklassDSKPD=$JmlReklassDSKPD+$isix['debet_reklass'];
				$JmlReklassKSKPD=$JmlReklassKSKPD+$isix['kredit_reklass'];
				$JmlSaldoAkSKPD=$JmlSaldoAkSKPD+$isix['jmlhitakhir'];	 
				
				$JmlSusutAk+=$isix['susutakhir'];
				$JmlSusutAw+=$isix['susutawal'];
				$Jmldebet_susut+=$isix['debet_susut'];
				$Jmlkredit_susut+=$isix['kredit_susut']; 
				$JmlNilaiBuku+=$isix['nilai_buku']; 
				*/
						
				//tampil aset lainnya ---------------------------------------------------------------------
				//bold
				if($fmLvlBrg=='2' || $fmLvlBrg=='3'){
					if($isix['g']=='00'){
						$vnmbarang = "<b>{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>{$isix['g']}</b>";
						$vh = "<b>{$isix['h']}</b>";
						$vi = "<b>{$isix['i']}</b>";
					}elseif($isix['g']<>'00' && $isix['h']=='00'){
						$vnmbarang = "<b>{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>{$isix['g']}</b>";
						$vh = "<b>{$isix['h']}</b>";
						$vi = "<b>{$isix['i']}</b>";
					}elseif($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00'){
						$vnmbarang = "&nbsp;&nbsp;&nbsp;<b>{$isix['nm_barang']}</b>";
						$vf = "<b>{$isix['f']}</b>";
						$vg = "<b>{$isix['g']}</b>";
						$vh = "<b>{$isix['h']}</b>";
						$vi = "<b>{$isix['i']}</b>";
					}else{
						$vnmbarang = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
						$vf = "{$isix['f']}";
						$vg = "{$isix['g']}";
						$vh = "{$isix['h']}";
						$vi = "{$isix['i']}";
					}
				}else{
					if($fmLvlBrg=='1'){
						$vnmbarang = $isix['g']=='00' ?  "<b>&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
						$vf = $isix['g']=='00' ? "<b>{$isix['f']}</b>" : "{$isix['f']}";
						$vg = $isix['g']=='00' ? "<b>{$isix['g']}</b>" : "{$isix['g']}";
						$vh = $isix['g']=='00' ? "<b>{$isix['h']}</b>" : "{$isix['h']}$h";
						$vi = $isix['g']=='00' ? "<b>{$isix['i']}</b>" : "{$isix['i']}";
					}else{
						$vnmbarang = "{$isix['nm_barang']}";
							$vf = "{$isix['f']}";
							$vg = "{$isix['g']}";
							$vh = "{$isix['h']}";
							$vi = "{$isix['i']}";
					}
				}
				
				//kode barang f,g,h,i
				if($fmLvlBrg=='2'){
					$daftarKodeBarang = "<td class='GarisDaftar' align=right>$vh</td>";
				}elseif($fmLvlBrg=='3'){
					$daftarKodeBarang = "<td class='GarisDaftar' align=right>$vh</td>
										<td class='GarisDaftar' align=right>$vi</td>";
				}else{
					$daftarKodeBarang = "";
				}		
				//tampil aset lainnya ---------------------------------------------------------------------
				if($fmLvlBrg=='1' || $fmLvlBrg=='2' || $fmLvlBrg=='3'){
					$bold = ($isix['g']=='00') || ($isix['g']<>'00' && $isix['h']=='00') || ($isix['g']<>'00' && $isix['h']<>'00' && $isix['i']=='00') ? 1 : 0;
				}else{
					$bold = 0;
				}
				//$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}";
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&h={$isix['h']}&i={$isix['i']}&bold=$bold";
				//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
				$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
				//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>";
				$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
				//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
				$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
				
				if($fmLvlBrg == '2' || $fmLvlBrg=='3'){
					if($isix['g']<>'24'){
						if( ($fmLvlBrg=='2' && $isix['h']=='00') || ($fmLvlBrg=='3' && $isix['h']=='00' && $isix['i']=='00') ){
							$ListData .=
							"<tr class='row1'>".
							"<td class='GarisDaftar' align=right>&nbsp;</td>".
							"<td class='GarisDaftar' align=right>$vf</td>
							<td class='GarisDaftar' align=right>$vg</td>".
							$daftarKodeBarang.
							"<td class='GarisDaftar' >$vnmbarang</td>".
							"<td class='GarisDaftar' align=right ><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>".
							"</tr>"	;
						}
					}elseif($isix['g']=='24'){
						if(($fmLvlBrg=='2') || ($fmLvlBrg=='3' && $isix['i']=='00') || ($fmLvlBrg=='3' && $isix['h']=='05' && $isix['i']<>'00')){
							$ListData .=
							"<tr class='row1'>".
							"<td class='GarisDaftar' align=right>&nbsp;</td>".
							"<td class='GarisDaftar' align=right>$vf</td>
							<td class='GarisDaftar' align=right>$vg</td>".
							$daftarKodeBarang.
							"<td class='GarisDaftar' >$vnmbarang</td>".
							"<td class='GarisDaftar' align=right ><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>".
							"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>".
							
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>".
							"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>".
							"</tr>"	;
						}
					}
				}else{
					$ListData .=
					"<tr class='row1'>".
					"<td class='GarisDaftar' align=right>&nbsp;</td>".
					"<td class='GarisDaftar' align=right>$vf</td>
					<td class='GarisDaftar' align=right>$vg</td>".
					$daftarKodeBarang.
					"<td class='GarisDaftar' >$vnmbarang</td>".
					"<td class='GarisDaftar' align=right ><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>".
					
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>".
					
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>".
					"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>".
					
					"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>".
					"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>".
					"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>".
					"</tr>"	;
				}
				
				
				
	  		}																																																												 
		  
		  
		  	//tampil total aset lainnnya ---------------------------------------------------	
			if($fmLvlBrg=='2'){
				$cols = "colspan=4";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=5";
			}else{
				$cols = "colspan=3";
			}
					
			$paramKdAkun = "&kint=01&ka=02&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>&nbsp;</td>".
				"<td class='GarisDaftar' $cols>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset Lainnya</b></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlTotSaldoAwSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlTotBelanjaSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlTotAtribusiSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlTotKapitalisasiDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlTotKapitalisasiKSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlTotHibahDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlTotHibahKSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlTotMutasiDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlTotMutasiKSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlTotPenilaianDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlTotPenghapusanKSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlTotPembukuanDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlTotPembukuanKSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlTotReklassDSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlTotReklassKSKPD</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>".
					
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlTotSaldoAkSKPD</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>".
				"</tr>"	;	  
		
		
		//tampil total aset (aset tetap + aset lainnya ) --------------------------------------------
			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwSKPD;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaSKPD;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiSKPD;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDSKPD;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKSKPD;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDSKPD;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKSKPD;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDSKPD;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKSKPD;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDSKPD;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKSKPD;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDSKPD;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKSKPD;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDSKPD;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKSKPD;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkSKPD;
			
			$JmlSusutAkTot+=$JmlSusutAk;
			$JmlSusutAwTot+=$JmlSusutAw;
			$Jmldebet_susutTot+=$Jmldebet_susut;
			$Jmlkredit_susutTot+=$Jmlkredit_susut;
			$JmlNilaiBukuTot+=$JmlNilaiBuku;
			
			$JmlSaldoAwTotx=$JmlSaldoAwTotx+$JmlSaldoAwTot;	
			$JmlBelanjaTotx=$JmlBelanjaTotx+$JmlBelanjaTot;
			$JmlAtribusiTotx=$JmlAtribusiTotx+$JmlAtribusiTot;
			$JmlKapitalisasiDTotx=$JmlKapitalisasiDTotx+$JmlKapitalisasiDTot;	
			$JmlKapitalisasiKTotx=$JmlKapitalisasiKTotx+$JmlKapitalisasiKTot;
			$JmlHibahDTotx=$JmlHibahDTotx+$JmlHibahDTot;
			$JmlHibahKTotx=$JmlHibahKTotx+$JmlHibahKTot;
			$JmlMutasiDTotx=$JmlMutasiDTotx+$JmlMutasiDTot;
			$JmlMutasiKTotx=$JmlMutasiKTotx+$JmlMutasiKTot;
			$JmlPenilaianDTotx=$JmlPenilaianDTotx+$JmlPenilaianDTot;
			$JmlPenghapusanKTotx=$JmlPenghapusanKTotx+$JmlPenghapusanKTot;
			$JmlPembukuanDTotx=$JmlPembukuanDTotx+$JmlPembukuanDTot;
			$JmlPembukuanKTotx=$JmlPembukuanKTotx+$JmlPembukuanKTot;
			$JmlReklassDTotx=$JmlReklassDTotx+$JmlReklassDTot;
			$JmlReklassKTotx=$JmlReklassKTotx+$JmlReklassKTot;
			$JmlSaldoAkTotx=$JmlSaldoAkTotx+$JmlSaldoAkTot;	
			
			$JmlSusutAkTotx+=$JmlSusutAk;
			$JmlSusutAwTotx+=$JmlSusutAw;
			$Jmldebet_susutTotx+=$Jmldebet_susut;
			$Jmlkredit_susutTotx+=$Jmlkredit_susut;			
			$JmlNilaiBukuTotx+=$JmlNilaiBuku;			
			
			
				
			$paramKdAkun = "&kint=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>&nbsp;</td>".
				"<td class='GarisDaftar' $cols>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset</b></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAwTot</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_' style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKTot</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susutTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susutTot</a></td>".
								
				"<td class='GarisDaftar'  align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAkTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBukuTot</a></td>".
				"</tr>"	;
	
			$JmlSaldoAwEkstra=0; $JmlBelanjaEkstra=0; $JmlAtribusiEkstra=0;	$JmlKapitalisasiDEkstra=0; $JmlKapitalisasiKEkstra=0;
			$JmlHibahDEkstra=0; $JmlHibahKEkstra=0;	$JmlMutasiDEkstra=0; $JmlMutasiKEkstra=0; $JmlPenilaianDEkstra=0;
			$JmlPenghapusanKEkstra=0; $JmlPembukuanDEkstra=0; $JmlPembukuanKEkstra=0; $JmlReklassDEkstra=0;	$JmlReklassKEkstra=0;
			$JmlSaldoAkEkstra=0; 
			$JmlSusutAk=0; $JmlSusutAw = 0; $Jmldebet_susut=0; $Jmlkredit_susut=0;
			
							 
		  	//query aset extrakomptable ------------------------------------------------------
				
			$bqry =
				"select kint,ka,kb, f,g,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu  
               	
				#$KondisiSKPDx
				
				where kint='02' ";
		 	
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
				
				$JmlSaldoAwEkstra=$JmlSaldoAwEkstra+$isix['jmlhargaawal'];	
				$JmlBelanjaEkstra=$JmlBelanjaEkstra+$isix['debet_bmd'];
				$JmlAtribusiEkstra=$JmlAtribusiEkstra+$isix['debet_atribusi'];
				$JmlKapitalisasiDEkstra=$JmlKapitalisasiDEkstra+$isix['debet_kapitalisasi'];	
				$JmlKapitalisasiKEkstra=$JmlKapitalisasiKEkstra+$isix['kredit_kapitalisasi'];
				$JmlHibahDEkstra=$JmlHibahDEkstra+$isix['debet_hibah'];
				$JmlHibahKEkstra=$JmlHibahKEkstra+$isix['kredit_hibah'];
				$JmlMutasiDEkstra=$JmlMutasiDEkstra+$isix['debet_mutasi'];
				$JmlMutasiKEkstra=$JmlMutasiKEkstra+$isix['kredit_mutasi'];
				$JmlPenilaianDEkstra=$JmlPenilaianDEkstra+$isix['debet_penilaian'];
				$JmlPenghapusanKEkstra=$JmlPenghapusanKEkstra+$isix['kredit_penghapusan'];
				$JmlPembukuanDEkstra=$JmlPembukuanDEkstra+$isix['debet_koreksi'];
				$JmlPembukuanKEkstra=$JmlPembukuanKEkstra+$isix['kredit_koreksi'];
				$JmlReklassDEkstra=$JmlReklassDEkstra+$isix['debet_reklass'];
				$JmlReklassKEkstra=$JmlReklassKEkstra+$isix['kredit_reklass'];
				$JmlSaldoAkEkstra=$JmlSaldoAkEkstra+$isix['jmlhitakhir'];	 
				
				$JmlSusutAkEkstra+=$isix['susutakhir'];  
				$JmlSusutAwEksra +=$isix['susutawal']; 
				$Jmldebet_susutEkstra+=$isix['debet_susut']; 
				$Jmlkredit_susutEkstra+=$isix['kredit_susut']; 
				$JmlNilaiBukuEkstra+=$isix['nilai_buku']; 
				
				
			}	 
		  
			
			//tampil extra ---------------------------------------------------------------------------
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$paramKdAkun = "&kint=02&ka=00&kb=00&bold=1";
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";	
			
			$rowno++;
			// 	
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>&nbsp;</td>".
				"<td class='GarisDaftar' $cols>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Ekstrakomptabel</b></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlTotSaldoAwEkstra</b></a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</b></a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlTotBelanjaEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlTotAtribusiEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlTotKapitalisasiDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlTotKapitalisasiKEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlTotHibahDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlTotHibahKEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlTotMutasiDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlTotMutasiKEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlTotPenilaianDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlTotPenghapusanKEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlTotPembukuanDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlTotPembukuanKEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlTotReklassDEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlTotReklassKEkstra</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap' >$TampilJmldebet_susutEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susutEkstra</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlTotSaldoAkEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAkEkstra</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBukuEkstra</a></td>".
				"</tr>"	;		
	

			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwEkstra;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaEkstra;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiEkstra;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDEkstra;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKEkstra;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDEkstra;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKEkstra;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDEkstra;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKEkstra;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDEkstra;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKEkstra;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDEkstra;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKEkstra;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDEkstra;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKEkstra;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkEkstra;
			
			$JmlSusutAkTot+=$JmlSaldoAkEkstra;
			$JmlSusutAwTot+=$JmlSaldoAwEkstra;
			$Jmldebet_susutEkstraTot+=$Jmldebet_susutEkstra;
			$Jmlkredit_susutEkstraTot+=$Jmlkredit_susutEkstra;
			$JmlNilaiBukuEkstraTot+=$JmlNilaiBukuEkstra;
			
			$JmlSaldoAwTotx=$JmlSaldoAwTotx+$JmlSaldoAwTot;	
			$JmlBelanjaTotx=$JmlBelanjaTotx+$JmlBelanjaTot;
			$JmlAtribusiTotx=$JmlAtribusiTotx+$JmlAtribusiTot;
			$JmlKapitalisasiDTotx=$JmlKapitalisasiDTotx+$JmlKapitalisasiDTot;	
			$JmlKapitalisasiKTotx=$JmlKapitalisasiKTotx+$JmlKapitalisasiKTot;
			$JmlHibahDTotx=$JmlHibahDTotx+$JmlHibahDTot;
			$JmlHibahKTotx=$JmlHibahKTotx+$JmlHibahKTot;
			$JmlMutasiDTotx=$JmlMutasiDTotx+$JmlMutasiDTot;
			$JmlMutasiKTotx=$JmlMutasiKTotx+$JmlMutasiKTot;
			$JmlPenilaianDTotx=$JmlPenilaianDTotx+$JmlPenilaianDTot;
			$JmlPenghapusanKTotx=$JmlPenghapusanKTotx+$JmlPenghapusanKTot;
			$JmlPembukuanDTotx=$JmlPembukuanDTotx+$JmlPembukuanDTot;
			$JmlPembukuanKTotx=$JmlPembukuanKTotx+$JmlPembukuanKTot;
			$JmlReklassDTotx=$JmlReklassDTotx+$JmlReklassDTot;
			$JmlReklassKTotx=$JmlReklassKTotx+$JmlReklassKTot;
			$JmlSaldoAkTotx=$JmlSaldoAkTotx+$JmlSaldoAkTot;	
	
			$JmlSusutAkTotx+=$JmlSaldoAkEkstra;
			$JmlSusutAwTotx+=$JmlSaldoAwEkstra;
			$Jmldebet_susutEkstraTotx+=$Jmldebet_susutEkstra;
			$Jmlkredit_susutEkstraTotx+=$Jmlkredit_susutEkstra;
			$JmlNilaiBukuEkstraTotx+=$JmlNilaiBukuEkstra;
			
			
			//tampil total aset + extra -----------------------------------------------------
			$paramKdAkun = "&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr  class='row1'>".
				"<td class='GarisDaftar' align=right>&nbsp;</td>".
				"<td class='GarisDaftar' $cols><b>Jumlah Aset + Ekstrakomptabel</b></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAwTot</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=6' target='blank_' style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=7&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKTot</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$vJmldebet_susutTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$vJmlkredit_susutTot</a></td>".
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAkTot</a></td>".
				"<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$vJmlNilaiBukuTot</a></td>".
				"</tr>"	;		
		
				

			$this->JmlSaldoAwTot += $JmlSaldoAwTot;	
			$this->JmlBelanjaTot += $JmlBelanjaTot;
			$this->JmlAtribusiTot += $JmlAtribusiTot ;
			$this->JmlKapitalisasiDTot += $JmlKapitalisasiDTot;	
			$this->JmlKapitalisasiKTot += $JmlKapitalisasiKTot;
			$this->JmlHibahDTot += $JmlHibahDTot;
			$this->JmlHibahKTot += $JmlHibahKTot;
			$this->JmlMutasiDTot += $JmlMutasiDTot;
			$this->JmlMutasiKTot += $JmlMutasiKTot;
			$this->JmlPenilaianDTot += $JmlPenilaianDTot;
			$this->JmlPenghapusanKTot += $JmlPenghapusanKTot;
			$this->JmlPembukuanDTot += $JmlPembukuanDTot;
			$this->JmlPembukuanKTot += $JmlPembukuanKTot;
			$this->JmlReklassDTot += $JmlReklassDTot;
			$this->JmlReklassKTot += $JmlReklassKTot;
			$this->JmlSaldoAkTot += $JmlSaldoAkTot;
			
			$this->JmlSusutAkTot += $JmlSusutAkTot;
			$this->JmlSusutAwTot += $JmlSusutAkTot;
			$this->Jmldebet_susutTot += $Jmldebet_susutTot;
			$this->debet_susutTot += $jmlkredit_susutTot;
			$this->NilaiBukuTot += $jmlNilaiBukuTot;
	
		}

		return $ListData;
	}

	
	
	function gen_table_data_($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;

		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$cbxTotal = $_REQUEST['cbxTotal'];
			
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

		//$tglAwal="$fmFiltThnBuku-01-01";
		//$tglAkhir="$fmFiltThnBuku-12-31";
			
		$smter=empty($_REQUEST['fmSemester'])? '' : $_REQUEST['fmSemester']; 
	
	// $smter=$_POST['$fmSemester'];

		if ($smter=='1') 
		{
			$tglAwal="$fmFiltThnBuku-07-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku).'-06-30';
		} else if ($smter=='2') 
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
		} else
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-06-30";
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


		$JmlSaldoAwTot=0; $JmlBelanjaTot=0;	$JmlAtribusiTot=0; $JmlKapitalisasiDTot=0; $JmlKapitalisasiKTot=0;
		$JmlHibahDTot=0; $JmlHibahKTot=0; $JmlMutasiDTot=0; $JmlMutasiKTot=0; $JmlPenilaianDTot=0; $JmlPenghapusanKTot=0;
		$JmlPembukuanDTot=0; $JmlPembukuanKTot=0; $JmlPembukuanKTot=0; $JmlReklassDTot=0; $JmlReklassKTot=0; $JmlSaldoAkTot=0;	

		$JmlSaldoAw=0; $JmlBelanja=0; $JmlAtribusi=0; $JmlKapitalisasiD=0; $JmlKapitalisasiK=0; $JmlHibahD=0; $JmlHibahK=0;
		$JmlMutasiD=0; $JmlMutasiK=0; $JmlPenilaianD=0; $JmlPenghapusanK=0; $JmlPembukuanD=0; $JmlPembukuanK=0;	$JmlReklassD=0;
		$JmlReklassK=0; $JmlSaldoAk=0;
		
		$JmlSaldoAwTotx0; $JmlBelanjaTotx0;	$JmlAtribusiTotx0; $JmlKapitalisasiDTotx0; $JmlKapitalisasiKTotx0; $JmlHibahDTotx0;
		$JmlHibahKTotx0; $JmlMutasiDTotx0; $JmlMutasiKTotx0; $JmlPenilaianDTotx0; $JmlPenghapusanKTotx0; $JmlPembukuanDTotx0;
		$JmlPembukuanKTotx0; $JmlReklassDTotx0; $JmlReklassKTotx0; $JmlSaldoAkTotx0;						

		$Kondisi = $KondisiAsal." and staset<=4 and f<='06' ";
		
		if ( $cbxTotal ){
			$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,
				'T O T A L' as nm_barcode  ";
		}else{
			if ($e1=='00' || $e1=='000' ){
				$sqry=" select * from v_opd  where c<>'' $KondisiSKPD ";
			} else {
				$sqry=" select *,nm_skpd as nmopd from ref_skpd  where c<>'' $KondisiSKPD ";
			}	
			$sqry2= $sqry;
			
			//if($c<> '00' ) 
		}
		//$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,				'T O T A L' as nm_barcode  ";
		$sqry=" select *,nm_skpd as nmopd from ref_skpd where  c='04' and d<>'00' and e='00' ";
		$cskpd=0;
		$Qry = mysql_query($sqry);
		while($isi=mysql_fetch_array($Qry)){
			$cskpd++;
			if ( $cbxTotal ==TRUE){
				$KondisiSKPDx = "1=1";
				if($c!='00' && $c !='') $KondisiSKPDx .= " and c= '$c' ";
				if($d!='00' && $d !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " d= '$d' " : " and d= '$d' ";
				$paramSKPD = "&c=$c&d=$d&e=$e&e1=$e1";				
			}else{
				if ($e1=='00' || $e1=='000' ){
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' ";							
				} else {
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."' ";					
				}
				$paramSKPD = "&c=".$isi['c']."&d=".$isi['d']."&e=".$isi['e']."&e1=".$isi['e1'];
			}
		
			$ListData .="<tr>
				<td align=right>$sqry2 $cskpd.</td>
				<td ><b>{$isi['nmopd']}</b></td>
				<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>
				<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>
				<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>
				<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>	<td align=right>&nbsp;</td>
				</tr>"	;
			
			$JmlSaldoAwSKPD=0; $JmlBelanjaSKPD=0; $JmlAtribusiSKPD=0; $JmlKapitalisasiDSKPD=0; $JmlKapitalisasiKSKPD=0;
			$JmlHibahDSKPD=0; $JmlHibahKSKPD=0;	$JmlMutasiDSKPD=0; $JmlMutasiKSKPD=0; $JmlPenilaianDSKPD=0;	$JmlPenghapusanKSKPD=0;
			$JmlPembukuanDSKPD=0; $JmlPembukuanKSKPD=0; $JmlReklassDSKPD=0;	$JmlReklassKSKPD=0;	$JmlSaldoAkSKPD=0;
	
			$JmlSaldoAwTot=0; $JmlBelanjaTot=0;	$JmlAtribusiTot=0;	$JmlKapitalisasiDTot=0;	$JmlKapitalisasiKTot=0;
			$JmlHibahDTot=0; $JmlHibahKTot=0; $JmlMutasiDTot=0; $JmlMutasiKTot=0; $JmlPenilaianDTot=0; $JmlPenghapusanKTot=0;
			$JmlPembukuanDTot=0; $JmlPembukuanKTot=0; $JmlReklassDTot=0; $JmlReklassKTot=0;	$JmlSaldoAkTot=0;			
			
			//qry aset tetap ---------------------------------------------------------------------------------------	
			$bqry=
				"select aa.kint,aa.ka,aa.kb, aa.f,aa.g,aa.nm_barang ,
				(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,
				(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
				(bb.debet_hp-bb.debet_atribusi) as debet_bmd,
				bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
				bb.debet_penilaian,
				(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,
				bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
				(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,
				(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
				
				(bb.debet_saldoawal - bb.kredit_saldoawal+	bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
				bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir, 
				
				(bb.kredit_susutawal - bb.debet_susutawal) as susutawal,
				bb.debet_susut, bb.kredit_susut,
				(bb.kredit_susutawal - bb.debet_susutawal + bb.kredit_susut - bb.debet_susut)as susutakhir,
				
				( (bb.debet_saldoawal-bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass)
				- (bb.kredit_susutawal-bb.debet_susutawal+bb.kredit_susut-bb.debet_susut) ) as nilai_buku
					
				
				 from v_ref_kib_keu  aa
				 left join 
				( select  IFNULL(f,'00') as f, IFNULL(g,'00') as g,
				sum(if(tgl_buku<'$tglAwal' && jns_trans<>10,jml_barang_d,0)) as debet_saldoawal_brg, 
				sum(if(tgl_buku<'$tglAwal' && jns_trans<>10,jml_barang_k,0)) as kredit_saldoawal_brg,
				sum(if(tgl_buku<'$tglAwal' && jns_trans<>10,debet,0)) as debet_saldoawal, 
				sum(if(tgl_buku<'$tglAwal' && jns_trans<>10,kredit,0)) as kredit_saldoawal,
				
				sum(if(tgl_buku<'$tglAwal' && jns_trans=10,debet,0)) as debet_susutawal, 
				sum(if(tgl_buku<'$tglAwal' && jns_trans=10,kredit,0)) as kredit_susutawal,
				
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
				
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,debet,0)) as debet_susut, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,kredit,0)) as kredit_susut, 
				
				sum(if(tgl_buku<='$tglAkhir' && jns_trans<>10,jml_barang_d,0)) as debet_saldoakhir_brg, 
				sum(if(tgl_buku<='$tglAkhir' && jns_trans<>10,jml_barang_k,0)) as kredit_saldoakhir_brg,
				sum(if(tgl_buku<='$tglAkhir' && jns_trans<>10,debet,0)) as debet_saldoakhir, 
				sum(if(tgl_buku<='$tglAkhir' && jns_trans<>10,kredit,0)) as kredit_saldoakhir,
				
				sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,debet,0)) as debet_susutakhir, 
				sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,kredit,0)) as kredit_susutakhir
				
				from v_jurnal_aset_tetap 
				where $KondisiSKPDx
				group by f,g with rollup
				) bb on aa.f = bb.f and aa.g=bb.g
				 where aa.ka='01' and aa.kb<>'00' "; //echo $bqry;
			
			$bqry =
				"select kint,ka,kb, f,g,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='01' and kb<>'00' ";
			
			$aQry = mysql_query($bqry);		$rowno=0;
			while($isix=mysql_fetch_array($aQry)){
		  		$rowno ++;
				
				if($isix['g']=='00') $JmlSaldoAwSKPD=$JmlSaldoAwSKPD+$isix['jmlhargaawal'];	
				if($isix['g']=='00')$JmlBelanjaSKPD=$JmlBelanjaSKPD+$isix['debet_bmd'];
				if($isix['g']=='00')$JmlAtribusiSKPD=$JmlAtribusiSKPD+$isix['debet_atribusi'];
				if($isix['g']=='00')$JmlKapitalisasiDSKPD=$JmlKapitalisasiDSKPD+$isix['debet_kapitalisasi'];	
				if($isix['g']=='00')$JmlKapitalisasiKSKPD=$JmlKapitalisasiKSKPD+$isix['kredit_kapitalisasi'];
				if($isix['g']=='00')$JmlHibahDSKPD=$JmlHibahDSKPD+$isix['debet_hibah'];
				if($isix['g']=='00')$JmlHibahKSKPD=$JmlHibahKSKPD+$isix['kredit_hibah'];
				if($isix['g']=='00')$JmlMutasiDSKPD=$JmlMutasiDSKPD+$isix['debet_mutasi'];
				if($isix['g']=='00')$JmlMutasiKSKPD=$JmlMutasiKSKPD+$isix['kredit_mutasi'];
				if($isix['g']=='00')$JmlPenilaianDSKPD=$JmlPenilaianDSKPD+$isix['debet_penilaian'];
				if($isix['g']=='00')$JmlPenghapusanKSKPD=$JmlPenghapusanKSKPD+$isix['kredit_penghapusan'];
				if($isix['g']=='00')$JmlPembukuanDSKPD=$JmlPembukuanDSKPD+$isix['debet_koreksi'];
				if($isix['g']=='00')$JmlPembukuanKSKPD=$JmlPembukuanKSKPD+$isix['kredit_koreksi'];
				if($isix['g']=='00')$JmlReklassDSKPD=$JmlReklassDSKPD+$isix['debet_reklass'];
				if($isix['g']=='00')$JmlReklassKSKPD=$JmlReklassKSKPD+$isix['kredit_reklass'];
				if($isix['g']=='00')$JmlSaldoAkSKPD=$JmlSaldoAkSKPD+$isix['jmlhitakhir'];
				
				if($isix['g']=='00')$JmlSusutAk+=$isix['susutakhir'];
				if($isix['g']=='00')$JmlSusutAw+=$isix['susutawal'];
				if($isix['g']=='00')$Jmldebet_susut+=$isix['debet_susut'];
				if($isix['g']=='00')$Jmlkredit_susut+=$isix['kredit_susut'];
				if($isix['g']=='00')$JmlNilaiBuku+=$isix['nilai_buku'];
				
		
				$bold = $isix['g']=='00' ? 1 : 0;
				
				//tampil aset tetap -------------------------------------------------------------------
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}&bold=$bold";
				
				$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
				$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
				$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
				$vnmbarang = $isix['g']=='00' ?  "<b>&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
				$ListData .="<tr>
				<td align=right>&nbsp;$bqry</td>
				<td >$vnmbarang</td>
				<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>
				
				<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>
				
				<td align=right><a href='$href&jns_trans=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>
				<td align=right><a href='$href&jns_trans=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>
				<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>
				<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>
				<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>
				<td align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>
				<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>
				<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>
				<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>
				
				<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>
				<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>
				
				<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>
				<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>
				</tr>"	;
		  
		  	}																																																									
			
			//tampil total aset tetap --------------------------------------------------------------
			$paramKdAkun = "&kint=01&ka=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr>
			<td align=right>&nbsp;</td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset Tetap</b></td>
			<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_1' name='vrekap'><b>$TampilJmlTotSaldoAwSKPD</b></a></td>
			<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap' ><b>$TampilJmlSusutAw</b></a></td>
			
			<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_3' name='vrekap' ><b>$TampilJmlTotBelanjaSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' ><b>$TampilJmlTotAtribusiSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_5' name='vrekap' ><b>$TampilJmlTotKapitalisasiDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_6' name='vrekap' ><b>$TampilJmlTotKapitalisasiKSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_7' name='vrekap' ><b>$TampilJmlTotHibahDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_8' name='vrekap' ><b>$TampilJmlTotHibahKSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_9' name='vrekap' ><b>$TampilJmlTotMutasiDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_10' name='vrekap' ><b>$TampilJmlTotMutasiKSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=6' target='blank_' style='color:black' id='vrekap_$rowno"."_11' name='vrekap' ><b>$TampilJmlTotPenilaianDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=7&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_12' name='vrekap' ><b>$TampilJmlTotPenghapusanKSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_13' name='vrekap' ><b>$TampilJmlTotPembukuanDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_14' name='vrekap' ><b>$TampilJmlTotPembukuanKSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black' id='vrekap_$rowno"."_15' name='vrekap' ><b>$TampilJmlTotReklassDSKPD</b></a></td>
			<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black' id='vrekap_$rowno"."_16' name='vrekap' ><b>$TampilJmlTotReklassKSKPD</b></a></td>
			
			<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap' ><b>$TampilJmldebet_susut</b></a></td>
			<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap' ><b>$TampilJmlkredit_susut</b></a></td>
			
			<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_19' name='vrekap' ><b>$TampilJmlTotSaldoAkSKPD</b></a></td>
			<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap' ><b>$TampilJmlSusutAk</b></a></td>
			<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap' ><b>$TampilJmlNilaiBuku</b></a></td>
			</tr>"	;
			
			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwSKPD;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaSKPD;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiSKPD;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDSKPD;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKSKPD;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDSKPD;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKSKPD;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDSKPD;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKSKPD;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDSKPD;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKSKPD;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDSKPD;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKSKPD;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDSKPD;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKSKPD;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkSKPD;		
			
			$JmlSusutAkTot+=$JmlSusutAk;
			$JmlSusutAwTot+=$JmlSusutAw;
			$Jmldebet_susutTot+=$Jmldebet_susut;
			$Jmlkredit_susutTot+=$Jmlkredit_susut;
			$JmlNilaiBukuTot+=$JmlNilaiBuku;
			
			
			$JmlSaldoAwSKPD=0;	
			$JmlBelanjaSKPD=0;
			$JmlAtribusiSKPD=0;
			$JmlKapitalisasiDSKPD=0;	
			$JmlKapitalisasiKSKPD=0;
			$JmlHibahDSKPD=0;
			$JmlHibahKSKPD=0;
			$JmlMutasiDSKPD=0;
			$JmlMutasiKSKPD=0;
			$JmlPenilaianDSKPD=0;
			$JmlPenghapusanKSKPD=0;
			$JmlPembukuanDSKPD=0;
			$JmlPembukuanKSKPD=0;
			$JmlReklassDSKPD=0;
			$JmlReklassKSKPD=0;
			$JmlSaldoAkSKPD=0;
			$JmlSusutAk =0;
			$JmlSusutAw=0;
			$Jmldebet_susut=0;
			$Jmlkredit_susut=0;
			$JmlNilaiBuku =0;
			  
			//query aset lainnya --------------------------------------------- 
			$bqry="
				select aa.kint,aa.ka,aa.kb,
				aa.f,aa.g,aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,
				(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
				(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,
				bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
				bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
				(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,				
				(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
				
				(bb.debet_saldoawal-bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
				bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir, 
				
				(bb.kredit_susutawal - bb.debet_susutawal) as susutawal,
				bb.debet_susut, bb.kredit_susut,
				(bb.kredit_susutawal - bb.debet_susutawal + bb.kredit_susut - bb.debet_susut)as susutakhir,
				
				( (bb.debet_saldoawal-bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+
				bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+bb.debet_penilaian-bb.kredit_penghapusan+
				bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass)
				- (bb.kredit_susutawal-bb.debet_susutawal+bb.kredit_susut-bb.debet_susut) ) as nilai_buku
				
				
				 from v_ref_kib_keu  aa
				 left join 
				( select IFNULL(f,'00') as f, IFNULL(g,'00') as g,
				sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
				sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, 
				sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
				sum(if(tgl_buku<'$tglAwal' && jns_trans=10,debet,0)) as debet_susutawal, 
				sum(if(tgl_buku<'$tglAwal' && jns_trans=10,kredit,0)) as kredit_susutawal,
				
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,debet,0)) as debet_hp, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1 ,harga_atribusi,0)) as debet_atribusi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,debet,0)) as debet_kapitalisasi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=3 ,kredit,0)) as kredit_kapitalisasi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,debet,0)) as debet_hibah, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=4 ,kredit,0)) as kredit_hibah, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,debet,0)) as debet_mutasi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=5 ,kredit,0)) as kredit_mutasi, 
				#sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,kredit,0)) as debet_penilaian,
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=6 ,debet,0)) as debet_penilaian,
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,debet,0)) as debet_penghapusan,  
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=7 ,kredit,0)) as kredit_penghapusan, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,debet,0)) as debet_koreksi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=8 ,kredit,0)) as kredit_koreksi, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,debet,0)) as debet_reklass, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=9 ,kredit,0)) as kredit_reklass, 
				sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
				sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir,
				
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,debet,0)) as debet_susut, 
				sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,kredit,0)) as kredit_susut, 
				
				sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,debet,0)) as debet_susutakhir, 
				sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,kredit,0)) as kredit_susutakhir
								
				from v_jurnal_aset_lainnya 
				where $KondisiSKPDx
				group by f,g
				) bb on aa.f = bb.f	and aa.g = bb.g 
		 		where ka='02' and kb<>'00' ";
		 	/*
			$bqry =
				"select kint,ka,kb, f,g,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu  
               	
				#$KondisiSKPDx
				
				where kint='01' and ka='02' and kb<>'00'  ";
			*/
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
				$rowno++;
				$JmlSaldoAwSKPD=$JmlSaldoAwSKPD+$isix['jmlhargaawal'];	
				$JmlBelanjaSKPD=$JmlBelanjaSKPD+$isix['debet_bmd'];
				$JmlAtribusiSKPD=$JmlAtribusiSKPD+$isix['debet_atribusi'];
				$JmlKapitalisasiDSKPD=$JmlKapitalisasiDSKPD+$isix['debet_kapitalisasi'];	
				$JmlKapitalisasiKSKPD=$JmlKapitalisasiKSKPD+$isix['kredit_kapitalisasi'];
				$JmlHibahDSKPD=$JmlHibahDSKPD+$isix['debet_hibah'];
				$JmlHibahKSKPD=$JmlHibahKSKPD+$isix['kredit_hibah'];
				$JmlMutasiDSKPD=$JmlMutasiDSKPD+$isix['debet_mutasi'];
				$JmlMutasiKSKPD=$JmlMutasiKSKPD+$isix['kredit_mutasi'];
				$JmlPenilaianDSKPD=$JmlPenilaianDSKPD+$isix['debet_penilaian'];
				$JmlPenghapusanKSKPD=$JmlPenghapusanKSKPD+$isix['kredit_penghapusan'];
				$JmlPembukuanDSKPD=$JmlPembukuanDSKPD+$isix['debet_koreksi'];
				$JmlPembukuanKSKPD=$JmlPembukuanKSKPD+$isix['kredit_koreksi'];
				$JmlReklassDSKPD=$JmlReklassDSKPD+$isix['debet_reklass'];
				$JmlReklassKSKPD=$JmlReklassKSKPD+$isix['kredit_reklass'];
				$JmlSaldoAkSKPD=$JmlSaldoAkSKPD+$isix['jmlhitakhir'];	 
				
				$JmlSusutAk+=$isix['susutakhir'];
				$JmlSusutAw+=$isix['susutawal'];
				$Jmldebet_susut+=$isix['debet_susut'];
				$Jmlkredit_susut+=$isix['kredit_susut']; 
				$JmlNilaiBuku+=$isix['nilai_buku']; 
		
		
				
				//tampil aset lainnya ---------------------------------------------------------------------
				$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&g={$isix['g']}";
				//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
				$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
				//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>";
				$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
				//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
				$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
				$ListData .=
					"<tr>
					<td align=right>&nbsp;</td>
					<td >&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</td>
					<td align=right ><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAwSKPD</a></td>					
					<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSusutAw</a></td>
					
					<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlBelanjaSKPD</a></td>
					<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlAtribusiSKPD</a></td>
					<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlKapitalisasiDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlKapitalisasiKSKPD</a></td>
					<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlHibahDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlHibahKSKPD</a></td>
					<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlMutasiDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlMutasiKSKPD</a></td>
					<td align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlPenilaianDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlPenghapusanKSKPD</a></td>
					<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlPembukuanDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlPembukuanKSKPD</a></td>
					<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlReklassDSKPD</a></td>
					<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlReklassKSKPD</a></td>
					
					<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'>$TampilJmldebet_susut</a></td>
					<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'>$TampilJmlkredit_susut</a></td>
					
					<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
					<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'>$TampilJmlSusutAk</a></td>
					<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'>$TampilJmlNilaiBuku</a></td>
					</tr>"	;
	  		}																																																												 
		  
		  	//tampil total aset lainnnya ---------------------------------------------------	
			
					
			$paramKdAkun = "&kint=01&ka=02&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr>
				<td align=right>&nbsp;</td>
				<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset Lainnya</b></td>
				<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'><b>$TampilJmlTotSaldoAwSKPD</b></a></td>
				<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'><b>$TampilJmlSusutAw</b></a></td>
				
				<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'><b>$TampilJmlTotBelanjaSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'><b>$TampilJmlTotAtribusiSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'><b>$TampilJmlTotKapitalisasiDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'><b>$TampilJmlTotKapitalisasiKSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'><b>$TampilJmlTotHibahDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'><b>$TampilJmlTotHibahKSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'><b>$TampilJmlTotMutasiDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'><b>$TampilJmlTotMutasiKSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'><b>$TampilJmlTotPenilaianDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=7&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'><b>$TampilJmlTotPenghapusanKSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'><b>$TampilJmlTotPembukuanDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'><b>$TampilJmlTotPembukuanKSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'><b>$TampilJmlTotReklassDSKPD</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'><b>$TampilJmlTotReklassKSKPD</b></a></td>
				
				<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'><b>$TampilJmldebet_susut</b></a></td>
				<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'><b>$TampilJmlkredit_susut</b></a></td>
					
				
				<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'><b>$TampilJmlTotSaldoAkSKPD</b></a></td>
				<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'><b>$TampilJmlSusutAk</b></a></td>
				<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'><b>$TampilJmlNilaiBuku</b></a></td>
				</tr>"	;	  
		
		
		//tampil total aset (aset tetap + aset lainnya ) --------------------------------------------
			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwSKPD;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaSKPD;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiSKPD;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDSKPD;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKSKPD;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDSKPD;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKSKPD;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDSKPD;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKSKPD;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDSKPD;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKSKPD;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDSKPD;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKSKPD;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDSKPD;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKSKPD;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkSKPD;
			
			$JmlSusutAkTot+=$JmlSusutAk;
			$JmlSusutAwTot+=$JmlSusutAw;
			$Jmldebet_susutTot+=$Jmldebet_susut;
			$Jmlkredit_susutTot+=$Jmlkredit_susut;
			$JmlNilaiBukuTot+=$JmlNilaiBuku;
			
			$JmlSaldoAwTotx=$JmlSaldoAwTotx+$JmlSaldoAwTot;	
			$JmlBelanjaTotx=$JmlBelanjaTotx+$JmlBelanjaTot;
			$JmlAtribusiTotx=$JmlAtribusiTotx+$JmlAtribusiTot;
			$JmlKapitalisasiDTotx=$JmlKapitalisasiDTotx+$JmlKapitalisasiDTot;	
			$JmlKapitalisasiKTotx=$JmlKapitalisasiKTotx+$JmlKapitalisasiKTot;
			$JmlHibahDTotx=$JmlHibahDTotx+$JmlHibahDTot;
			$JmlHibahKTotx=$JmlHibahKTotx+$JmlHibahKTot;
			$JmlMutasiDTotx=$JmlMutasiDTotx+$JmlMutasiDTot;
			$JmlMutasiKTotx=$JmlMutasiKTotx+$JmlMutasiKTot;
			$JmlPenilaianDTotx=$JmlPenilaianDTotx+$JmlPenilaianDTot;
			$JmlPenghapusanKTotx=$JmlPenghapusanKTotx+$JmlPenghapusanKTot;
			$JmlPembukuanDTotx=$JmlPembukuanDTotx+$JmlPembukuanDTot;
			$JmlPembukuanKTotx=$JmlPembukuanKTotx+$JmlPembukuanKTot;
			$JmlReklassDTotx=$JmlReklassDTotx+$JmlReklassDTot;
			$JmlReklassKTotx=$JmlReklassKTotx+$JmlReklassKTot;
			$JmlSaldoAkTotx=$JmlSaldoAkTotx+$JmlSaldoAkTot;	
			
			$JmlSusutAkTotx+=$JmlSusutAk;
			$JmlSusutAwTotx+=$JmlSusutAw;
			$Jmldebet_susutTotx+=$Jmldebet_susut;
			$Jmlkredit_susutTotx+=$Jmlkredit_susut;			
			$JmlNilaiBukuTotx+=$JmlNilaiBuku;			
			
			
				
			$paramKdAkun = "&kint=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr>
				<td align=right>&nbsp;</td>
				<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Aset</b></td>
				<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'><b>$TampilJmlSaldoAwTot</b></a></td>
				<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'><b>$TampilJmlSusutAwTot</b></a></td>
				
				<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'><b>$TampilJmlBelanjaTot</b></a></td>
				<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'><b>$TampilJmlAtribusiTot</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'><b>$TampilJmlKapitalisasiDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'><b>$TampilJmlKapitalisasiKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'><b>$TampilJmlHibahDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'><b>$TampilJmlHibahKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'><b>$TampilJmlMutasiDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'><b>$TampilJmlMutasiKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=6' target='blank_' style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'><b>$TampilJmlPenilaianDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=7&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'><b>$TampilJmlPenghapusanKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'><b>$TampilJmlPembukuanDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'><b>$TampilJmlPembukuanKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'><b>$TampilJmlReklassDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'><b>$TampilJmlReklassKTot</b></a></td>
				
				<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'><b>$TampilJmldebet_susutTot</b></a></td>
				<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'><b>$TampilJmlkredit_susutTot</b></a></td>
								
				<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'><b>$TampilJmlSaldoAkTot</b></a></td>
				<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'><b>$TampilJmlSusutAkTot</b></a></td>
				<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'><b>$TampilJmlNilaiBukuTot</b></a></td>
				</tr>"	;
	
			$JmlSaldoAwEkstra=0; $JmlBelanjaEkstra=0; $JmlAtribusiEkstra=0;	$JmlKapitalisasiDEkstra=0; $JmlKapitalisasiKEkstra=0;
			$JmlHibahDEkstra=0; $JmlHibahKEkstra=0;	$JmlMutasiDEkstra=0; $JmlMutasiKEkstra=0; $JmlPenilaianDEkstra=0;
			$JmlPenghapusanKEkstra=0; $JmlPembukuanDEkstra=0; $JmlPembukuanKEkstra=0; $JmlReklassDEkstra=0;	$JmlReklassKEkstra=0;
			$JmlSaldoAkEkstra=0; 
			$JmlSusutAk=0; $JmlSusutAw = 0; $Jmldebet_susut=0; $Jmlkredit_susut=0;
								 
		  	//query aset extrakomptable ------------------------------------------------------
			$bqry="
				select 
				aa.f,aa.g,aa.nm_barang ,(bb.debet_saldoawal_brg - bb.kredit_saldoawal_brg) as jmlbrgawal,(bb.debet_saldoawal - bb.kredit_saldoawal) as jmlhargaawal,
				(bb.debet_hp-bb.debet_atribusi) as debet_bmd,bb.debet_atribusi,bb.debet_kapitalisasi,bb.kredit_kapitalisasi,bb.debet_hibah,bb.kredit_hibah,bb.debet_mutasi,bb.kredit_mutasi,
				bb.debet_penilaian,(bb.kredit_penghapusan-bb.debet_penghapusan) as kredit_penghapusan,bb.debet_koreksi,bb.kredit_koreksi,bb.debet_reklass,bb.kredit_reklass,
				(bb.debet_saldoakhir_brg - bb.kredit_saldoakhir_brg) as jmlbrgakhir,(bb.debet_saldoakhir - bb.kredit_saldoakhir) as jmlhargaakhir,
				(bb.debet_saldoawal - bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+
				bb.debet_penilaian-bb.kredit_penghapusan+bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass) as jmlhitakhir,
				
				(bb.kredit_susutawal - bb.debet_susutawal) as susutawal,
				bb.debet_susut, bb.kredit_susut,
				(bb.kredit_susutawal - bb.debet_susutawal + bb.kredit_susut - bb.debet_susut)as susutakhir,
				
				( (bb.debet_saldoawal-bb.kredit_saldoawal+bb.debet_hp+bb.debet_kapitalisasi-bb.kredit_kapitalisasi+
				bb.debet_hibah-bb.kredit_hibah+bb.debet_mutasi-bb.kredit_mutasi+bb.debet_penilaian-bb.kredit_penghapusan+
				bb.debet_koreksi-bb.kredit_koreksi+bb.debet_reklass-bb.kredit_reklass)
				- (bb.kredit_susutawal-bb.debet_susutawal+bb.kredit_susut-bb.debet_susut) ) as nilai_buku
				
				from v_ref_kib_keu  aa
				left join 
				( select 
					sum(if(tgl_buku<'$tglAwal',jml_barang_d,0)) as debet_saldoawal_brg, sum(if(tgl_buku<'$tglAwal',jml_barang_k,0)) as kredit_saldoawal_brg,
					sum(if(tgl_buku<'$tglAwal',debet,0)) as debet_saldoawal, sum(if(tgl_buku<'$tglAwal',kredit,0)) as kredit_saldoawal,
					
					sum(if(tgl_buku<'$tglAwal' && jns_trans=10,debet,0)) as debet_susutawal, 
					sum(if(tgl_buku<'$tglAwal' && jns_trans=10,kredit,0)) as kredit_susutawal,
									
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
					sum(if(tgl_buku<='$tglAkhir',jml_barang_d,0)) as debet_saldoakhir_brg, 
					sum(if(tgl_buku<='$tglAkhir',jml_barang_k,0)) as kredit_saldoakhir_brg,
					sum(if(tgl_buku<='$tglAkhir',debet,0)) as debet_saldoakhir, 
					sum(if(tgl_buku<='$tglAkhir',kredit,0)) as kredit_saldoakhir,
					
					sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,debet,0)) as debet_susut, 
					sum(if(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=10 ,kredit,0)) as kredit_susut, 
				
					sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,debet,0)) as debet_susutakhir, 
					sum(if(tgl_buku<='$tglAkhir' && jns_trans=10,kredit,0)) as kredit_susutakhir
													
					from v_jurnal_aset_ekstra 
					#where $KondisiSKPDx			
				) bb on aa.kint = '02'
			 
			 	where kint='02' "; //echo $bqry;
				
			/*$bqry =
				"select kint,ka,kb, f,g,nm_barang ,
				null as jmlbrgawal, null as jmlhargaawal, null as debet_bmd,
				null as debet_atribusi, null as debet_kapitalisasi, null as kredit_kapitalisasi, null as debet_hibah,
                null as kredit_hibah,null as debet_mutasi,null as kredit_mutasi,
				null as debet_penilaian, null as kredit_penghapusan,null as debet_koreksi,
                null as kredit_koreksi, null as debet_reklass, null as kredit_reklass,
				null as jmlbrgakhir, null as jmlhargaakhir, null as jmlhitakhir, 
				
				null as susutawal,	null as debet_susut, null as kredit_susut,
				null as susutakhir, null as nilai_buku
				from v_ref_kib_keu  
               	
				#$KondisiSKPDx
				
				where kint='02' ";
		 	*/
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
				
				$JmlSaldoAwEkstra=$JmlSaldoAwEkstra+$isix['jmlhargaawal'];	
				$JmlBelanjaEkstra=$JmlBelanjaEkstra+$isix['debet_bmd'];
				$JmlAtribusiEkstra=$JmlAtribusiEkstra+$isix['debet_atribusi'];
				$JmlKapitalisasiDEkstra=$JmlKapitalisasiDEkstra+$isix['debet_kapitalisasi'];	
				$JmlKapitalisasiKEkstra=$JmlKapitalisasiKEkstra+$isix['kredit_kapitalisasi'];
				$JmlHibahDEkstra=$JmlHibahDEkstra+$isix['debet_hibah'];
				$JmlHibahKEkstra=$JmlHibahKEkstra+$isix['kredit_hibah'];
				$JmlMutasiDEkstra=$JmlMutasiDEkstra+$isix['debet_mutasi'];
				$JmlMutasiKEkstra=$JmlMutasiKEkstra+$isix['kredit_mutasi'];
				$JmlPenilaianDEkstra=$JmlPenilaianDEkstra+$isix['debet_penilaian'];
				$JmlPenghapusanKEkstra=$JmlPenghapusanKEkstra+$isix['kredit_penghapusan'];
				$JmlPembukuanDEkstra=$JmlPembukuanDEkstra+$isix['debet_koreksi'];
				$JmlPembukuanKEkstra=$JmlPembukuanKEkstra+$isix['kredit_koreksi'];
				$JmlReklassDEkstra=$JmlReklassDEkstra+$isix['debet_reklass'];
				$JmlReklassKEkstra=$JmlReklassKEkstra+$isix['kredit_reklass'];
				$JmlSaldoAkEkstra=$JmlSaldoAkEkstra+$isix['jmlhitakhir'];	 
				
				$JmlSusutAkEkstra+=$isix['susutakhir'];  
				$JmlSusutAwEksra +=$isix['susutawal']; 
				$Jmldebet_susutEkstra+=$isix['debet_susut']; 
				$Jmlkredit_susutEkstra+=$isix['kredit_susut']; 
				$JmlNilaiBukuEkstra+=$isix['nilai_buku']; 
				
				
			}	 
		  
			
			//tampil extra ---------------------------------------------------------------------------
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$paramKdAkun = "&kint=02&ka=00&kb=00&bold=1";
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";	
			
			$rowno++;
			// 	
			$ListData .="<tr>
				<td align=right>&nbsp;</td>
				<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Jumlah Ekstrakomptabel</b></td>
				<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'><b>$TampilJmlTotSaldoAwEkstra</b></a></td>
				<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_2' name='vrekap'><b>$TampilJmlSusutAw</b></a></td>
				
				<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'><b>$TampilJmlTotBelanjaEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'><b>$TampilJmlTotAtribusiEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'><b>$TampilJmlTotKapitalisasiDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'><b>$TampilJmlTotKapitalisasiKEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'><b>$TampilJmlTotHibahDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'><b>$TampilJmlTotHibahKEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'><b>$TampilJmlTotMutasiDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'><b>$TampilJmlTotMutasiKEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=6' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'><b>$TampilJmlTotPenilaianDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=7' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'><b>$TampilJmlTotPenghapusanKEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'><b>$TampilJmlTotPembukuanDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'><b>$TampilJmlTotPembukuanKEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'><b>$TampilJmlTotReklassDEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'><b>$TampilJmlTotReklassKEkstra</b></a></td>
				
				<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap' ><b>$TampilJmldebet_susutEkstra</b></a></td>
				<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'><b>$TampilJmlkredit_susutEkstra</b></a></td>
				
				<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'><b>$TampilJmlTotSaldoAkEkstra</b></a></td>
				<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'><b>$TampilJmlSusutAkEkstra</b></a></td>
				<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'><b>$TampilJmlNilaiBukuEkstra</b></a></td>
				</tr>"	;		
	

			$JmlSaldoAwTot=$JmlSaldoAwTot+$JmlSaldoAwEkstra;	
			$JmlBelanjaTot=$JmlBelanjaTot+$JmlBelanjaEkstra;
			$JmlAtribusiTot=$JmlAtribusiTot+$JmlAtribusiEkstra;
			$JmlKapitalisasiDTot=$JmlKapitalisasiDTot+$JmlKapitalisasiDEkstra;	
			$JmlKapitalisasiKTot=$JmlKapitalisasiKTot+$JmlKapitalisasiKEkstra;
			$JmlHibahDTot=$JmlHibahDTot+$JmlHibahDEkstra;
			$JmlHibahKTot=$JmlHibahKTot+$JmlHibahKEkstra;
			$JmlMutasiDTot=$JmlMutasiDTot+$JmlMutasiDEkstra;
			$JmlMutasiKTot=$JmlMutasiKTot+$JmlMutasiKEkstra;
			$JmlPenilaianDTot=$JmlPenilaianDTot+$JmlPenilaianDEkstra;
			$JmlPenghapusanKTot=$JmlPenghapusanKTot+$JmlPenghapusanKEkstra;
			$JmlPembukuanDTot=$JmlPembukuanDTot+$JmlPembukuanDEkstra;
			$JmlPembukuanKTot=$JmlPembukuanKTot+$JmlPembukuanKEkstra;
			$JmlReklassDTot=$JmlReklassDTot+$JmlReklassDEkstra;
			$JmlReklassKTot=$JmlReklassKTot+$JmlReklassKEkstra;
			$JmlSaldoAkTot=$JmlSaldoAkTot+$JmlSaldoAkEkstra;
			
			$JmlSusutAkTot+=$JmlSaldoAkEkstra;
			$JmlSusutAwTot+=$JmlSaldoAwEkstra;
			$Jmldebet_susutEkstraTot+=$Jmldebet_susutEkstra;
			$Jmlkredit_susutEkstraTot+=$Jmlkredit_susutEkstra;
			$JmlNilaiBukuEkstraTot+=$JmlNilaiBukuEkstra;
			
			$JmlSaldoAwTotx=$JmlSaldoAwTotx+$JmlSaldoAwTot;	
			$JmlBelanjaTotx=$JmlBelanjaTotx+$JmlBelanjaTot;
			$JmlAtribusiTotx=$JmlAtribusiTotx+$JmlAtribusiTot;
			$JmlKapitalisasiDTotx=$JmlKapitalisasiDTotx+$JmlKapitalisasiDTot;	
			$JmlKapitalisasiKTotx=$JmlKapitalisasiKTotx+$JmlKapitalisasiKTot;
			$JmlHibahDTotx=$JmlHibahDTotx+$JmlHibahDTot;
			$JmlHibahKTotx=$JmlHibahKTotx+$JmlHibahKTot;
			$JmlMutasiDTotx=$JmlMutasiDTotx+$JmlMutasiDTot;
			$JmlMutasiKTotx=$JmlMutasiKTotx+$JmlMutasiKTot;
			$JmlPenilaianDTotx=$JmlPenilaianDTotx+$JmlPenilaianDTot;
			$JmlPenghapusanKTotx=$JmlPenghapusanKTotx+$JmlPenghapusanKTot;
			$JmlPembukuanDTotx=$JmlPembukuanDTotx+$JmlPembukuanDTot;
			$JmlPembukuanKTotx=$JmlPembukuanKTotx+$JmlPembukuanKTot;
			$JmlReklassDTotx=$JmlReklassDTotx+$JmlReklassDTot;
			$JmlReklassKTotx=$JmlReklassKTotx+$JmlReklassKTot;
			$JmlSaldoAkTotx=$JmlSaldoAkTotx+$JmlSaldoAkTot;	
	
			$JmlSusutAkTotx+=$JmlSaldoAkEkstra;
			$JmlSusutAwTotx+=$JmlSaldoAwEkstra;
			$Jmldebet_susutEkstraTotx+=$Jmldebet_susutEkstra;
			$Jmlkredit_susutEkstraTotx+=$Jmlkredit_susutEkstra;
			$JmlNilaiBukuEkstraTotx+=$JmlNilaiBukuEkstra;
			
			
			//tampil total aset + extra -----------------------------------------------------
			$paramKdAkun = "&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$ListData .="<tr>
				<td align=right>&nbsp;</td>
				<td ><b>Jumlah Aset + Ekstrakomptabel</b></td>
				<td align=right><a href='$hrefAw&tanpasusut=1' target='blank_'  style='color:black;'  id='vrekap_$rowno"."_1' name='vrekap'><b>$TampilJmlSaldoAwTot</b></a></td>
				<td align=right><a href='$hrefAw&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'><b>$TampilJmlSusutAwTot</b></a></td>
				
				<td align=right><a href='$href&jns_trans=1&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'><b>$TampilJmlBelanjaTot</b></a></td>
				<td align=right><a href='$href&jns_trans=2&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'><b>$TampilJmlAtribusiTot</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'><b>$TampilJmlKapitalisasiDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=3&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'><b>$TampilJmlKapitalisasiKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'><b>$TampilJmlHibahDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=4&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'><b>$TampilJmlHibahKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'><b>$TampilJmlMutasiDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=5&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'><b>$TampilJmlMutasiKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=6' target='blank_' style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'><b>$TampilJmlPenilaianDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=7' target='blank_' style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'><b>$TampilJmlPenghapusanKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'><b>$TampilJmlPembukuanDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=8&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'><b>$TampilJmlPembukuanKTot</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=1' target='blank_' style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'><b>$TampilJmlReklassDTot</b></a></td>
				<td align=right><a href='$href&jns_trans=9&debet=2' target='blank_' style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'><b>$TampilJmlReklassKTot</b></a></td>
				
				<td align=right><a href='$href&jns_trans=10&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_17' name='vrekap'><b>$vJmldebet_susutTot</b></a></td>
				<td align=right><a href='$href&jns_trans=10&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_18' name='vrekap'><b>$vJmlkredit_susutTot</b></a></td>
				
				<td align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_19' name='vrekap'><b>$TampilJmlSaldoAkTot</b></a></td>
				<td align=right><a href='$hrefAk&jns_trans=10' target='blank_'  style='color:black;' id='vrekap_$rowno"."_20' name='vrekap'><b>$TampilJmlSusutAkTot</b></a></td>
				<td align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_21' name='vrekap'><b>$vJmlNilaiBukuTot</b></a></td>
				</tr>"	;		
		
				

			$this->JmlSaldoAwTot += $JmlSaldoAwTot;	
			$this->JmlBelanjaTot += $JmlBelanjaTot;
			$this->JmlAtribusiTot += $JmlAtribusiTot ;
			$this->JmlKapitalisasiDTot += $JmlKapitalisasiDTot;	
			$this->JmlKapitalisasiKTot += $JmlKapitalisasiKTot;
			$this->JmlHibahDTot += $JmlHibahDTot;
			$this->JmlHibahKTot += $JmlHibahKTot;
			$this->JmlMutasiDTot += $JmlMutasiDTot;
			$this->JmlMutasiKTot += $JmlMutasiKTot;
			$this->JmlPenilaianDTot += $JmlPenilaianDTot;
			$this->JmlPenghapusanKTot += $JmlPenghapusanKTot;
			$this->JmlPembukuanDTot += $JmlPembukuanDTot;
			$this->JmlPembukuanKTot += $JmlPembukuanKTot;
			$this->JmlReklassDTot += $JmlReklassDTot;
			$this->JmlReklassKTot += $JmlReklassKTot;
			$this->JmlSaldoAkTot += $JmlSaldoAkTot;
			
			$this->JmlSusutAkTot += $JmlSusutAkTot;
			$this->JmlSusutAwTot += $JmlSusutAkTot;
			$this->Jmldebet_susutTot += $Jmldebet_susutTot;
			$this->debet_susutTot += $jmlkredit_susutTot;
			$this->NilaiBukuTot += $jmlNilaiBukuTot;
	
		}

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


$Pembukuan2Ajx= new Pembukuan2AjxObj();



?>
