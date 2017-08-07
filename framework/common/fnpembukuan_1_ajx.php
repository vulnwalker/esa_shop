<?php

class Pembukuan1AjxObj2 extends DaftarObj2{
	var $Prefix = 'Pembukuan1Ajx'; //jsname
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
	var $totalCol = 6; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='pembukuan.xls';
	var $Cetak_Judul = 'Rekap Neraca';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Rekap Neraca';
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
		//$title = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "Rekap Neraca" : "Pembukuan";
		return 'Rekap Neraca';
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
		//$txtRakapbi = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "REKAP NERACA" : "REKAP BI";
		//$txtRekapneraca = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "KERTAS KERJA" : "REKAP NERACA";
		//if ($jns=='penyusutan') $styleMenuPenyusutan = " style='color:blue;'";
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >KERTAS KERJA</a>": '';
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
				"<A href=\"index.php?Pg=05&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
			$menu_pembukuan1 =
		($Main->MODUL_AKUNTANSI )?
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
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' $styleMenu >REKAP NERACA</a> 
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
			<A href=\"pages.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>$menu_pembukuan1			
			</table>".
			
			
			""
			;
		
		return $menubar;
			
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'rekapNeraca':{
				$fm = $this->rekapNeraca();// $this->total();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				
				
				$content = $fm['content'];	
				
				//$content = 'tes';
				//echo 'tess';
				break;
			}			
			default:{
				$err = 'content tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function rekapNeraca(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//&c=00&d=00&e=00&e1=00&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=1
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$idel= $_REQUEST['idel'];
		$bold = $_REQUEST['bold'];
		$tanpasusut = $_REQUEST['tanpasusut'];
		$isjmlbrg = $_REQUEST['isjmlbrg'];
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmSemester = $_REQUEST['fmSemester'];
		$fmJenisHibah = $_REQUEST['fmJenisHibah'];
		$cbxDlmRibu = $_REQUEST["cbxDlmRibu"];
				
		//$tgl1 = $_REQUEST['tgl1'];
		//$tgl2 = $_REQUEST['tgl2'];		
		//$jns_trans = $_REQUEST['jns_trans'];
				
		//kondisi
		$arrKondisi = array();	//$arrKondisi[] = " idawal = '740330' ";				
		
		if(!($c=='' || $c=='00') ) $arrKondisi[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisi[] = "d='$d'";
		if(!($e=='' || $e=='00') ) $arrKondisi[] = "e='$e'";
		if(!($e1=='' || $e1=='00') ) $arrKondisi[] = "e1='$e1'";
				
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		
		//$kd_akun = $_REQUEST['kd_akun'];
		$kint = $_REQUEST['kint'];
		$ka = $_REQUEST['ka'];
		$kb = $_REQUEST['kb'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h']==''?'00':$_REQUEST['h'];
		$i = $_REQUEST['i']==''?'00':$_REQUEST['i'];
		$debet = $_REQUEST['debet'];
		
		$cek .= "kint=$kint ka=$ka kb=$kb";
		$kdakun = $kint; //$cek .= " kdakun1=$kdakun ";
		$kdakun .= $ka!='' && $ka!='00' && $ka != NULL ?  '.'.$ka :''; //$cek .= " kdakun2=$kdakun ";
		$kdakun .= $kb!='' && $kb!='00' && $kb != NULL ?  '.'.$kb :''; //$cek .= " kdakun3=$kdakun ";	
		if($kdakun!='') {
			//
			
			//if($kint = '01' && $ka = '01') {
			//	$arrKondisi[] = " f='$ka' and g='$kb'";	
			//}else{
				$arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
			//}
		}
		
		
		
		/*		
		$tgl1 = $_REQUEST['tgl1'];
		if($tgl1!='') $arrKondisi[] = " tgl_buku >='$tgl1' ";
		$tgl2 = $_REQUEST['tgl2'];
		if($tgl2!='') $arrKondisi[] = " tgl_buku <='$tgl2' ";
		$jns_trans = $_REQUEST['jns_trans'];
		if($jns_trans!='') $arrKondisi[] = " jns_trans ='$jns_trans' ";
		$jns_trans2 = $_REQUEST['jns_trans2'];
		if($jns_trans2!='') $arrKondisi[] = " jns_trans2 ='$jns_trans2' ";
		$debet = $_REQUEST['debet'];
		*/
		
		//if($tanpasusut==1) $arrKondisi[] = " jns_trans <> 10 ";
		
		/*if($debet!='') {
			switch ($debet){
				case '1': $arrKondisi[] = " debet >0 "; break;
				case '2': $arrKondisi[] = " kredit >0 "; break;				
			}			
		}else{
			
		}*/
		
		
		if($g!='' && $g != '00') $arrKondisi[] = " g='$g' ";
		if($h!='' && $h != '00') $arrKondisi[] = " h='$h' ";
		if($i!='' && $i != '00') $arrKondisi[] = " i='$i' ";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//$jml = $debet ==2 ? "kredit" : "debet";
		
		
		
		if($isjmlbrg==1){
			if($jns_trans==10){
				$jml = " jml_barang_k-jml_barang_d "; //penyusutan
			}else{
				$jml = " jml_barang_d-jml_barang_k ";	
			}
			switch($debet){
				case '1' : $jml = "jml_barang_d"; break;
				case '2' : $jml = "jml_barang_k"; break;
				//else $jml = " debet-kredit "; break;
			}
		}else{
			if($jns_trans==10){
				$jml = " kredit-debet "; //penyusutan
			}else{
				$jml = " debet-kredit ";	
			}
			switch($debet){
				case '1' : $jml = "debet"; break;
				case '2' : $jml = "kredit"; break;
				//else $jml = " debet-kredit "; break;
			}
		}
			
		
		if($Main->JURNAL_FISIK){
			$tbl = 't_jurnal_aset';//				
		}else{
			$tbl = 'v_jurnal';//				
		}
		
		/*switch ($kint){
			case '01' : 
				switch ($ka){
					case '01': $tbl = ' t_jurnal_aset_tetap ';	break;
					case '02': $tbl = ' t_jurnal_aset_lainnya ';	break;
				}				
			break;
			case '02' :
				$tbl = ' t_jurnal_aset_ekstra ';
			break;			
		}
		*/
		if($kint=='01' && $ka==''){ //intrakomptabel
			$staset = "and staset<>10";
		}elseif($kint=='01' && $ka=='01'){ //Aset Tetap
			if($kb==''){
				$staset = "and staset=3";
			}else{
				if($g=='00'){
					$staset = "and staset=3 and f='$kb'";
				}elseif($g<>'00' && $h=='00'){
					$staset = "and staset=3 and f='$kb' and g='$g'";
				}elseif($g<>'00' && $h<>'00' && $i=='00'){
					$staset = "and staset=3 and f='$kb' and g='$g' and h='$h'";
				}elseif($g<>'00' && $h<>'00' && $i<>'00'){
					$staset = "and staset=3 and f='$kb' and g='$g' and h='$h' and i='$i'";
				}
			}
		}elseif($kint=='01' && $ka=='02'){
			switch($kb){
				case '' : $staset = "and staset<>3 and staset<>10"; break; //Aset Lainnya
				case '01' : $staset = "and staset=5"; break; //Tagihan Penjualan Angsuran
				case '02' : $staset = "and staset=6"; break; //Tuntutan Ganti Rugi
				case '03' : $staset = "and staset=7"; break; //Kemitraan dengan Pihak Ke Tiga
				case '04' : $staset = "and staset=8"; break; //Aset Tidak Berwujud
				case '05' : $staset = "and staset=9"; break; //Aset Lain-lain
				case '06' : $staset = "and staset=11 and staset=12 and staset=13"; break; //Pemindahtanganan
				case '07' : $staset = "and staset=14"; break; //Pemusnahan
			}
		}elseif($kint=='02'){ //Ekstrakomptabel
			$staset = "and staset=10";
		}
		
		/*switch($fmSemester){
			case 1 : $tgl1 = $fmFiltThnBuku.'-07-01'; $tgl2 = $fmFiltThnBuku.'-12-31'; break;
			case 2 : $tgl1 = $fmFiltThnBuku.'-01-01'; $tgl2 = $fmFiltThnBuku.'-12-31'; break; 
			default : $tgl1 = $fmFiltThnBuku.'-01-01'; $tgl2 = $fmFiltThnBuku.'-06-31'; break;
		}*/
		$tgl1 = $fmFiltThnBuku.'-01-01'; $tgl2 = $fmFiltThnBuku.'-12-31';
		
		$sqry = "select count(kondisi) as cntKondisi from buku_induk_thn where kondisi=0 and tgl_periode = '$tgl2' $staset group by kondisi";$cek.=$sqry;
		$sqry2 = "select count(kondisi) as cntKondisi from buku_induk_thn where kondisi=1 and tgl_periode = '$tgl2' $staset group by kondisi";$cek.=$sqry2;
		$sqry3 = "select count(kondisi) as cntKondisi from buku_induk_thn where kondisi=2 and tgl_periode = '$tgl2' $staset group by kondisi";$cek.=$sqry3;
		$aqry = " select  ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total1, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, debet-kredit ,0) ) as total2, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans=10, kredit-debet ,0) ) as total3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_k,0) ) as total4, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, kredit ,0) ) as total5, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total6, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d,0) ) as total7, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, debet ,0) ) as total8, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit ,0) ) as total9, ".
			
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total10, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, debet-kredit ,0) ) as total11, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans=10, kredit-debet ,0) ) as total12, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, debet,0) ) as total1_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, jml_barang_d,0) ) as total1_3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, harga_atribusi ,0) ) as total2_1, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, debet  ,0) ) as total3_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, kredit  ,0) ) as total3_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_d  ,0) ) as total3_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_k  ,0) ) as total3_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, debet  ,0) ) as total4_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, kredit  ,0) ) as total4_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_d  ,0) ) as total4_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_k  ,0) ) as total4_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, debet  ,0) ) as total5_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, kredit  ,0) ) as total5_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_d  ,0) ) as total5_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_k  ,0) ) as total5_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, debet  ,0) ) as total6_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, kredit  ,0) ) as total6_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_d  ,0) ) as total6_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_k  ,0) ) as total6_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, debet  ,0) ) as total7_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, kredit  ,0) ) as total7_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_d  ,0) ) as total7_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_k  ,0) ) as total7_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, debet  ,0) ) as total8_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, kredit  ,0) ) as total8_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_d  ,0) ) as total8_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_k  ,0) ) as total8_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, debet  ,0) ) as total9_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, kredit  ,0) ) as total9_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_d  ,0) ) as total9_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_k  ,0) ) as total9_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total10_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit  ,0) ) as total10_2 ".
			
						
			" from $tbl ".
			" $Kondisi "; $cek.=$aqry;
		
		//select  sum( jml_barang_d-jml_barang_k ) as total from  t_jurnal_aset   Where  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.01.01%' and  tgl_buku <='2008-12-31'  and  jns_trans <> 10
		//rekap mutasi
		//$aqry = "select sum( if( , jml_barang_d - jml_barang_k,0  ) ".
		//	"from $tbl ".
		//	$Kondisi;
		
		$hsl = mysql_fetch_array( mysql_query($aqry) );
		$kndB=mysql_fetch_array(mysql_query($sqry));
		$kndKB=mysql_fetch_array(mysql_query($sqry2));
		$kndRB=mysql_fetch_array(mysql_query($sqry3));
		/*while(){
			$kond .= $knd['cntKondisi']."."; 
		}*/
		$ArKond = explode(".",$kond);	 //$cek .= $kond." ".$ArKond[0].$ArKond[1].$ArKond[2];
		
		$des = 2;//$isjmlbrg ==1? 0: 2;
		
		$content->idel = $idel;
		$content->total =  is_null($hsl['total'])? 0 : $hsl['total'] ;
		$content->total1 = is_null($hsl['total1'])? 0 :$hsl['total1'];//saldo aw
		$content->total2 = is_null($hsl['total2'])? 0 :$hsl['total2'];//saldo aw
		$content->total3 = is_null($hsl['total3'])? 0 :$hsl['total3'];//saldo aw
		$content->total4 = is_null($hsl['total4'])? 0 :$hsl['total4'];
		$content->total5 = is_null($hsl['total5'])? 0 :$hsl['total5'];
		$content->total6 = is_null($hsl['total6'])? 0 :$hsl['total6'];
		$content->total7 = is_null($hsl['total7'])? 0 :$hsl['total7'];
		$content->total8 = is_null($hsl['total8'])? 0 :$hsl['total8'];
		$content->total9 = is_null($hsl['total9'])? 0 :$hsl['total9'];
		$content->total10 = is_null($hsl['total10'])? 0 :$hsl['total10'];//saldo ak brg
		$content->total11 = is_null($hsl['total11'])? 0 :$hsl['total11'];//saldo ak hrg 
		$content->total12 = is_null($hsl['total12'])? 0 :$hsl['total12'];//saldo ak susut
		
		
		
		
		$content->vtotal =  $bold ? '<b>'. number_format($content->total,$des,',' , '.' ) .'</b>':  number_format($content->total,$des,',' , '.' );
		$content->vtotal1 =  $bold ? '<b>'. number_format($content->total1,$des,',' , '.' ) .'</b>':  number_format($content->total1,$des,',' , '.' );
		$content->vtotal2 =  $bold ? '<b>'. number_format($content->total2,$des,',' , '.' ) .'</b>':  number_format($content->total2,$des,',' , '.' );
		$content->vtotal3 =  $bold ? '<b>'. number_format($content->total3,$des,',' , '.' ) .'</b>':  number_format($content->total3,$des,',' , '.' );
		$content->vtotal4 =  $bold ? '<b>'. number_format($content->total4,$des,',' , '.' ) .'</b>':  number_format($content->total4,$des,',' , '.' );
		$content->vtotal5 =  $bold ? '<b>'. number_format($content->total5,$des,',' , '.' ) .'</b>':  number_format($content->total5,$des,',' , '.' );
		$content->vtotal6 =  $bold ? '<b>'. number_format($content->total6,$des,',' , '.' ) .'</b>':  number_format($content->total6,$des,',' , '.' );
		$content->vtotal7 =  $bold ? '<b>'. number_format($content->total7,$des,',' , '.' ) .'</b>':  number_format($content->total7,$des,',' , '.' );
		$content->vtotal8 =  $bold ? '<b>'. number_format($content->total8,$des,',' , '.' ) .'</b>':  number_format($content->total8,$des,',' , '.' );
		$content->vtotal9 =  $bold ? '<b>'. number_format($content->total9,$des,',' , '.' ) .'</b>':  number_format($content->total9,$des,',' , '.' );
		$content->vtotal10 =  $bold ? '<b>'. number_format($content->total10,$des,',' , '.' ) .'</b>':  number_format($content->total10,$des,',' , '.' );
		$content->vtotal11 =  $bold ? '<b>'. number_format($content->total11,$des,',' , '.' ) .'</b>':  number_format($content->total11,$des,',' , '.' );
		$content->vtotal12 =  $bold ? '<b>'. number_format($content->total12,$des,',' , '.' ) .'</b>':  number_format($content->total12,$des,',' , '.' );
	
		$arrTotN = array();
		$arrVTotN = array();
		for ($i=0;$i<10;$i++){
			for ($j=0;$j<4;$j++){
				$des= $j<2 ? 2 : 0;
				$arrTotN[$i][$j] = is_null($hsl['total'.($i+1).'_'.($j+1)])? 0 :$hsl['total'.($i+1).'_'.($j+1)];
				$arrVTotN[$i][$j] =  $bold ? '<b>'. number_format($arrTotN[$i][$j],$des,',' , '.' ) .'</b>':  number_format($arrTotN[$i][$j],$des,',' , '.' );
			}
		}
		$content->totalN = $arrTotN;
		$content->vtotalN = $arrVTotN;
		
		$saldoAk = !empty($cbxDlmRibu)? $hsl['total11']/1000 : $hsl['total11']; //jika cbxDlmRibu aktif
		$content->saldoAk = is_null($saldoAk)? 0 : $saldoAk;
		$content->saldoAkBrg = is_null($hsl['total10'])? 0 : $hsl['total10'];
		$content->susutAk = is_null($hsl['total12'])? 0 :$hsl['total12'];
		$content->nilaibukuAk = $content->saldoAk  - $content->susutAk ;
		
		$content->vSaldoAk =$bold ? '<b>'. number_format($content->saldoAk ,2,',' , '.' ) .'</b>':  number_format($content->saldoAk ,2,',' , '.' );
		$content->vSaldoAkBrg =$bold ? '<b>'. number_format($content->saldoAkBrg ,0,',' , '.' ) .'</b>':  number_format($content->saldoAkBrg ,0,',' , '.' );
		$content->vSusutAk =$bold ? '<b>'. number_format($content->susutAk,2,',' , '.' ) .'</b>':  number_format($content->susutAk,2,',' , '.' );
		$content->vNilaibukuAk =$bold ? '<b>'. number_format($content->nilaibukuAk,2,',' , '.' ) .'</b>':  number_format($content->nilaibukuAk,2,',' , '.' );
		

		$content->KondBaik = $kndB['cntKondisi'] == '' ? 0 : $kndB['cntKondisi'];
		$content->KondRusak = $kndKB['cntKondisi'] == '' ? 0 : $kndKB['cntKondisi'];
		$content->KondRusakBrt = $kndRB['cntKondisi'] == '' ? 0 : $kndRB['cntKondisi'];
		
		$content->vKondBaik = $bold ? '<b>'. number_format($content->KondBaik ,0,',' , '.' ) .'</b>':  number_format($content->KondBaik ,0,',' , '.' );
		$content->vKondRusak = $bold ? '<b>'. number_format($content->KondRusak ,0,',' , '.' ) .'</b>':  number_format($content->KondRusak ,0,',' , '.' );
		$content->vKondRusakBrt = $bold ? '<b>'. number_format($content->KondRusakBrt ,0,',' , '.' ) .'</b>':  number_format($content->KondRusakBrt ,0,',' , '.' );
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genDaftarOpsi(){
		global $Main;
		
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']==''?date('Y') : $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$jenis_hibah = $_REQUEST['fmJenisHibah'];
		$fmLevelBarang = $_REQUEST['fmLevelBarang'];
		$cbxDlmRibu = $_POST["cbxDlmRibu"];	
		//$cbxRincian = $_POST["cbxRincian"];	
		$cbxKondisi = $_POST["cbxKondisi"];	
		$batas = $Main->batas;
		$arrLevelBarang = array(
			array('1','Level 2'),
			array('2','Level 3'),
			array('3','Level 4')
		);		
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
		$vjenis_hibah =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>".
			//cmb2D_v2('jenis_hibah', $jenis_hibah, $Main->JenisHibah, '','Sumber Dana').
			genComboBoxQry('fmJenisHibah',$jenis_hibah,
					"select nama from ref_sumber_dana",
					'nama', 'nama','Sumber Dana').
			"</div>".			
			'';
		if($Main->REKAP_KONDISI){
			$vcbxKondisi = "$batas
							<div style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>
								<input $cbxKondisi id='cbxKondisi' type='checkbox' value='checked' name='cbxKondisi'> Kondisi 
							</div>";	
		}else{
			$vcbxKondisi = "
							<div style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>
								<input $cbxKondisi id='cbxKondisi' type='hidden' value='' name='cbxKondisi'>  
							</div>";
		}
		
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
			"<div class='FilterBar'>
				<table style='width:100%'><tbody><tr><td align='left'>
				<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
				<tbody>
				<tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>
					<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan : </div>
					<div style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>
						<input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> Dalam Ribuan 
					</div>
					".$batas."
					<div style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>
					Tahun Buku <input type='text' id='fmFiltThnBuku' name='fmFiltThnBuku' value='$fmFiltThnBuku' size='4' maxlength='4' onkeypress='return isNumberKey(event)'>
					<input type='hidden' id='daftarcetak' name='daftarcetak' value='' >
					</div>
					".$batas."
					<div style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>
						<!--<input $cbxRincian id='cbxRincian' type='checkbox' value='checked' name='cbxRincian'> Rincian--> 
						$cmbLevelBarang
					</div>
					
					$vcbxKondisi
					<!--".$batas."
					$vjenis_hibah-->
				</td>
				<td align='left' style='padding:1 8 0 8;'>
					<input type='button' id='btTampil' value='Tampilkan' onclick='Pembukuan1Ajx.refreshList(true)'>
				</td>				
				</tr>
				</tbody></table>
				</td></tr></tbody></table>
			</div>";
			/*genFilterBar(
				array(	
					'Tampilkan : '.
					" <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu' > Dalam Ribuan ".
					' Tahun Buku '.
					"<input type='text' id='fmFiltThnBuku' name='fmFiltThnBuku' value='$fmFiltThnBuku' 
						size='4' maxlength='4' onkeypress='return isNumberKey(event)' >"*/
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/
				/*),$this->Prefix.".refreshList(true)",TRUE
			)
			;*/
		
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
						-->".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$cbxRincian = $_POST['cbxRincian'];
		$fmLvlBrg = $_POST['fmLevelBarang'];
		$cbxKondisi = $_POST['cbxKondisi'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Jumlah Harga dalam Ribuan': 'Jumlah Harga';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			/*if(!empty($cbxRincian)){
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
			}*/
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
			
			if(!empty($cbxKondisi)){
				$tampilKondisi = "<th class=\"th02\" colspan=3>Kondisi</th>";
				$tampilKondisiRinci = "<th class=\"th01\" >Baik</th>
										<th class=\"th01\" >Kurang Baik</th>
										<th class=\"th01\" >Rusak Berat</th>";
				
			}else{
				
			}
			
			
			
		$headerTable =
			"<thead><tr>
				<th class=\"th01\" width='30' rowspan=2>No.</th>
				<th class=\"th02\" width='50' $cols>Kode Barang</th>			
				<th class=\"th01\" rowspan=2>Uraian</th>
				$tampilKondisi
				<th class=\"th01\" width='200' rowspan=2>Jumlah Barang</th>
				<th class=\"th01\" width='200' rowspan=2>$tampilHeaderHarga<br>(Rp)</th>
			</tr>
			<tr>
				$tampilKodeBarang
				$tampilKondisiRinci
			</tr>
			<!--<tr>
				<th class=\"th03\" >(1)</th>
				<th class=\"th03\" >(2)</th>
				<th class=\"th03\" >(3)</th>
				<th class=\"th03\" >(4)</th>
				<th class=\"th03\" >(5)</th>
				<th class=\"th03\" >(6)</th>
				<th class=\"th03\" >(7)</th>
				<th class=\"th03\" >(8)</th>
				<th class=\"th03\" >(9)</th>
				$tampilHeaderAngka
			</tr>	-->			
			$tambahgaris
			</thead>";
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
		
		
		
		$vtotjmlbarang 	= number_format($this->totBrgAset, 0,',','.');
		$vtotjmlharga 		= number_format($this->totHrgAset, 2,',','.');

				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b></td>
			<td class='$ColStyle' align='right'><b></td>
			<td class='$ColStyle' align='right'><b></td>
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		*/
		$ListData = "";
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
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
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
	
	
	function gen_table_data($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;

		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$cbxRincian = $_POST['cbxRincian'];
		$fmLvlBrg = $_POST['fmLevelBarang'];
		$cbxKondisi = $_POST['cbxKondisi'];
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

		/**if ($smter=='1') 
		{
			$tglAwal="$fmFiltThnBuku-07-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku).'-06-30';
		} else if ($smter=='2') 
		{*/
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
			$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
		/**} else
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-06-30";
			$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
		}	*/

				
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
		
			/*$ListData .="<tr class='row1'>
				
				<td class='GarisDaftar' align=right>$cskpd.</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' ><b>{$isi['nmopd']}</b> <div style='display:none'>$cek</div></td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar'  align=right>&nbsp;</td><td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>	<td class='GarisDaftar' align=right>&nbsp;</td>
				
				<td class='GarisDaftar' align=right>&nbsp;</td>	
				<td class='GarisDaftar' align=right>&nbsp;</td>	
				<td class='GarisDaftar' align=right>&nbsp;</td>	
				<td class='GarisDaftar' align=right>&nbsp;</td>
				<td class='GarisDaftar' align=right>&nbsp;</td>	
				<td class='GarisDaftar' align=right>&nbsp;</td>	
				
				</tr>"	;
			*/
			$JmlSaldoAwSKPD=0; $JmlBelanjaSKPD=0; $JmlAtribusiSKPD=0; $JmlKapitalisasiDSKPD=0; $JmlKapitalisasiKSKPD=0;
			$JmlHibahDSKPD=0; $JmlHibahKSKPD=0;	$JmlMutasiDSKPD=0; $JmlMutasiKSKPD=0; $JmlPenilaianDSKPD=0;	$JmlPenghapusanKSKPD=0;
			$JmlPembukuanDSKPD=0; $JmlPembukuanKSKPD=0; $JmlReklassDSKPD=0;	$JmlReklassKSKPD=0;	$JmlSaldoAkSKPD=0;
	
			$JmlSaldoAwTot=0; $JmlBelanjaTot=0;	$JmlAtribusiTot=0;	$JmlKapitalisasiDTot=0;	$JmlKapitalisasiKTot=0;
			$JmlHibahDTot=0; $JmlHibahKTot=0; $JmlMutasiDTot=0; $JmlMutasiKTot=0; $JmlPenilaianDTot=0; $JmlPenghapusanKTot=0;
			$JmlPembukuanDTot=0; $JmlPembukuanKTot=0; $JmlReklassDTot=0; $JmlReklassKTot=0;	$JmlSaldoAkTot=0;			
			
			//Intrakomptabel
			//$cols = !empty($cbxRincian)? "colspan = 5" : "colspan=3";
			if($fmLvlBrg=='2'){
				$cols = "colspan=4";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=5";
			}else{
				$cols = "colspan=3";
			}
			$paramKdAkun = "&kint=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno=1;
			$tampilDaftarKondisi = !empty($cbxKondisi)? 
			"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_' style='color:black' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_' style='color:black' id='vrekap_$rowno"."_3' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>"
			:"";
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>$rowno</td>".
				"<td class='GarisDaftar' $cols><b>I.&nbsp;&nbsp;&nbsp;&nbsp;Intrakomptabel</b></td>".
				$tampilDaftarKondisi.
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".
								 
				"</tr>"	;
			
			//tampil total aset tetap -------------------------------------------------------------------------------------------------
			//$cols = !empty($cbxRincian)? "colspan = 4" : "colspan=2";
			if($fmLvlBrg=='2'){
				$cols = "colspan=3";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=4";
			}else{
				$cols = "colspan=2";
			}
			$paramKdAkun = "&kint=01&ka=01&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno=2;
			$tampilDaftarKondisi = !empty($cbxKondisi)? 
			"<td class='GarisDaftar'  align=right><a href_='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_1' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar'  align=right><a href_='' target='blank_' style='color:black' id='vrekap_$rowno"."_2' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar'  align=right><a href_='' target='blank_' style='color:black' id='vrekap_$rowno"."_3' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>"
			:"";
			$ListData .="<tr class='row1'>
			<td class='GarisDaftar'  align=right>$rowno</td>
			<td class='GarisDaftar'  align=right>&nbsp;</td>
			<td class='GarisDaftar' $cols><b>A.&nbsp;&nbsp;&nbsp;&nbsp; Aset Tetap</b></td>
			$tampilDaftarKondisi
		
			<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>					
								
			</tr>"	;
			
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
					
			$aQry = mysql_query($bqry);		
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
				/*$daftarKodeBarang = !empty($cbxRincian)? 
				"<td class='GarisDaftar' align=right>{$isix['f']}</td>
				<td class='GarisDaftar' align=right>$kdBidang</td>
				<td class='GarisDaftar' align=right>$h</td>
				<td class='GarisDaftar' align=right>$i</td>" 
				: 
				"<td class='GarisDaftar' align=right>{$isix['f']}</td>
				<td class='GarisDaftar' align=right>$kdBidang</td>
				";*/
				
				
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
				
				//kondisi
				$tampilDaftarKondisi = !empty($cbxKondisi)? 
				"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>"
				:"";
				$ListData .="<tr class='row1'>
				<td class='GarisDaftar' align=right>$rowno</td>
				<td class='GarisDaftar' align=right>$vf</td>
				<td class='GarisDaftar' align=right>$vg</td>
				$daftarKodeBarang
				<td class='GarisDaftar' >$vnmbarang</td>
				$tampilDaftarKondisi
							
				<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				
				
				</tr>"	;
		  
		  	}																																																									
			
				//tampil total aset lainnnya ----------------------------------------------------------------
			//$cols = !empty($cbxRincian)? "colspan = 4" : "colspan=2";
			if($fmLvlBrg=='2'){
				$cols = "colspan=3";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=4";
			}else{
				$cols = "colspan=2";
			}
			$paramKdAkun = "&kint=01&ka=02&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$tampilDaftarKondisi = !empty($cbxKondisi)? 
			"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>"
			:"";
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>$rowno</td>".
				"<td class='GarisDaftar' align=right></td>".
				"<td class='GarisDaftar' $cols><b>B.&nbsp;&nbsp;&nbsp;&nbsp;Aset Lainnya</b></td>".
				$tampilDaftarKondisi.
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".
							
				"</tr>"	;	 
			
			
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
			
			//query aset lainnya ----------------------------------------------------------------------------------------------- 
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
				
			
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
				$rowno++;
				/*$daftarKodeBarang = !empty($cbxRincian)? 
				"<td class='GarisDaftar' align=right>{$isix['f']}</td>
				<td class='GarisDaftar' align=right>{$isix['g']}</td>
				<td class='GarisDaftar' align=right>{$isix['h']}</td>
				<td class='GarisDaftar' align=right>{$isix['i']}</td>" 
				: 
				"<td class='GarisDaftar' align=right>{$isix['f']}</td>
				<td class='GarisDaftar' align=right>{$isix['g']}</td>
				";*/
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
				$tampilDaftarKondisi = !empty($cbxKondisi)? 
				"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
				<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>"
				:"";
				if($fmLvlBrg == '2' || $fmLvlBrg=='3'){
					if($isix['g']<>'24'){
						if( ($fmLvlBrg=='2' && $isix['h']=='00') || ($fmLvlBrg=='3' && $isix['h']=='00' && $isix['i']=='00') ){
							$ListData .=
							"<tr class='row1'>".
							"<td class='GarisDaftar' align=right>$rowno</td>
							<td class='GarisDaftar' align=right>$vf</td>
							<td class='GarisDaftar' align=right>$vg</td>".
							$daftarKodeBarang.
							"<td class='GarisDaftar' >$vnmbarang</td>".
							$tampilDaftarKondisi.
							
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".	
					
							"</tr>"	;
						}
						
					}elseif($isix['g']=='24'){
						if(($fmLvlBrg=='2') || ($fmLvlBrg=='3' && $isix['i']=='00') || ($fmLvlBrg=='3' && $isix['h']=='05' && $isix['i']<>'00')){
							$ListData .=
							"<tr class='row1'>".
							"<td class='GarisDaftar' align=right>$rowno</td>
							<td class='GarisDaftar' align=right>$vf</td>
							<td class='GarisDaftar' align=right>$vg</td>".
							$daftarKodeBarang.
							"<td class='GarisDaftar' >$vnmbarang</td>".
							$tampilDaftarKondisi.
							
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".	
					
							"</tr>"	;
						}
						
					}
				}else{
					$ListData .=
							"<tr class='row1'>".
							"<td class='GarisDaftar' align=right>$rowno</td>
							<td class='GarisDaftar' align=right>$vf</td>
							<td class='GarisDaftar' align=right>$vg</td>".
							$daftarKodeBarang.
							"<td class='GarisDaftar' >$vnmbarang</td>".
							$tampilDaftarKondisi.
							
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
							"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".	
					
							"</tr>"	;
				}
				
				
				
	  		}																																																												 
		  
		  
		   
		
		
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
			
			
				
			/*
			list jumlah aset
			*/
	
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
			//$cols = !empty($cbxRincian)? "colspan = 5" : "colspan=3";
			if($fmLvlBrg=='2'){
				$cols = "colspan=4";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=5";
			}else{
				$cols = "colspan=3";
			}
			$paramKdAkun = "&kint=02&ka=00&kb=00&bold=1";
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";	
			
			$rowno++;
			$tampilDaftarKondisi = !empty($cbxKondisi)? 
			"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>"
			:""; 	
			$ListData .="<tr class='row1'>".
				"<td class='GarisDaftar' align=right>$rowno</td>".
				"<td class='GarisDaftar' $cols><b>II.&nbsp;&nbsp;&nbsp;&nbsp;Ekstrakomptabel</b></td>".
				$tampilDaftarKondisi.
				
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".
				
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
			//$cols = !empty($cbxRincian)? "colspan = 6" : "colspan=4";
			if($fmLvlBrg=='2'){
				$cols = "colspan=5";
			}elseif($fmLvlBrg=='3'){
				$cols = "colspan=6";
			}else{
				$cols = "colspan=4";
			}
			$paramKdAkun = "&bold=1";
			//$tglAwal2 = ($fmFiltThnBuku-1).'-12-31';
			$hrefAw = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAwal2";
			//$hrefAw = "<a href='$hrefAw' target='blank_'  style='color:black;'>";
			$hrefAk = "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl2=$tglAkhir";
			//$hrefAk = "<a href='$hrefAk' target='blank_'  style='color:black;'>";
			$href 	= "pages.php?Pg=Jurnal$paramSKPD$paramKdAkun&tgl1=$tglAwal&tgl2=$tglAkhir";
			$rowno++;
			$tampilDaftarKondisi = !empty($cbxKondisi)? 
			"<td class='GarisDaftar' align=right><a href_='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>
			<td class='GarisDaftar' align=right><a href_='' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>"
			:"";
			$ListData .="<tr  class='row1'>".
				"<td class='GarisDaftar' $cols align=center><b>TOTAL</b></td>".
				$tampilDaftarKondisi.
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_' style='color:black' id='vrekap_$rowno"."_4' name='vrekap' >$TampilJmlTotSaldoAkSKPD</a></td> ".
				"<td class='GarisDaftar' align=right><a href='$hrefAk&tanpasusut=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td> ".
								 
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
	

	$sqry="select aa.f, aa.g,  aa.nm_barang,".
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
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from view_buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			".
			
			"
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from view_buku_induk  where $Kondisi and  tgl_buku <= '$tglAkhir'  group by f,g with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_awal, sum(harga_hapus) as jmlHrgHSBG_awal from v_hapus_sebagian where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) nn on aa.f=nn.f and aa.g=nn.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_akhir, sum(harga_hapus ) as jmlHrgHSBG_akhir from v_hapus_sebagian where $Kondisi  and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) oo on aa.f=oo.f and aa.g=oo.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus ) as jmlHrgHPS_HSBG_awal from v2_penghapusan_hapussebagian where  tgl_penghapusan < '$tglAwal' $KondisiKIB group by f,g with rollup ) pp on aa.f=pp.f  and aa.g=pp.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus) as jmlHrgHPS_HSBG_akhir from v2_penghapusan_hapussebagian where  tgl_penghapusan <= '$tglAkhir'  $KondisiKIB group by f,g with rollup ) qq on aa.f=qq.f  and aa.g=qq.g  
			".
			
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga) as jmlHrgSusut_awal from v1_penyusutan where $Kondisi and tahun < '$fmFiltThnBuku'   group by f,g with rollup ) rr on aa.f=rr.f  and aa.g=rr.g  ".
			"left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga) as jmlHrgSusut_akhir from v1_penyusutan where $Kondisi and tahun <= '$fmFiltThnBuku'   group by f,g with rollup ) ss on aa.f=ss.f  and aa.g=ss.g ".			
			
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
				<td class=\"judulcetak\" colspan=6>REKAP NERACA</td>
			</tr>
			</table>"	
			.PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,$this->cetak_xls)."<br>";
	}
	
}


$Pembukuan1Ajx = new Pembukuan1AjxObj2();

?>