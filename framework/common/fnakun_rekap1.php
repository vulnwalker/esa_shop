<?php

class PembukuanObj extends DaftarObj2{
	var $Prefix = 'Pembukuan'; //jsname
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
	var $FieldSum_Cp1 = array( 10, 9,9);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 11; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
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
	
	var $totbi = 0;	
	var $totAsetLancar = 0;
	var $totAsetTetap = 0;
	var $totAsetLainMitra = 0;
	var $totAsetLainLain = 0;
	var $totAsetLain = 0;
	var $totIntra = 0;
	var $totBawahKapital = 0;
	var $totAsetHeritage = 0;
	var $totExtra = 0;
	var $tot = 0;
	
	function setTitle(){
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		return 'Pembukuan ';
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		return " <DIV ALIGN=CENTER>Pembukuan ";
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
			if($Main->MODUL_SENSUS) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
			if($Main->MODUL_PETA) $menubar=$menubar."
			<A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' >PETA</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
			<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu>AKUNTANSI</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
			<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris Intrakomptabel '>BI Intra</a> |
			<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris Ekstrakomptabel '>BI Ekstra</a> |
			<A href=\"index.php?Pg=05&SPg=04a\" title='Tanah'>KIB A</a>  |  
			<A href=\"index.php?Pg=05&SPg=05a\" title='Peralatan & Mesin'>KIB B</a>  |  
			<A href=\"index.php?Pg=05&SPg=06a\" title='Gedung & Bangunan'>KIB C</a>  |  
			<A href=\"index.php?Pg=05&SPg=07a\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
			<A href=\"index.php?Pg=05&SPg=08a\" title='Aset Tetap Lainnya'>KIB E</a>  |  
			<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
			<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu>PEMBUKUAN</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>			
			</table>".
			
			/*"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin: 1 0 0 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!-- <A href=\"pages.php?Pg=sensus&menu=kertaskerja\" title='Kertas Kerja' $styleMenu2_5>Kertas Kerja</a> |  -->
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Belum Cek' $styleMenu2_1>Belum Cek</a> |
			<A href=\"pages.php?Pg=SensusScan\" title='Hasil Scan' $styleMenu2_9>Hasil Scan</a> |
			<A href=\"pages.php?Pg=SensusTidakTercatat\" title='Barang Tidak Tercatat' $styleMenu2_8>Tidak Tercatat</a> |
			
			<A href=\"pages.php?Pg=sensus&menu=ada\" title='Ada Barang' $styleMenu2_2>Ada</a>  |  
			<A href=\"pages.php?Pg=sensus&menu=tidakada\" title='Tidak Ada Barang' $styleMenu2_5>Tidak Ada</a>  |  
			<!--<A href=\"pages.php?Pg=sensus&menu=diusulkan\" title='Diusulkan Penghapusan' $styleMenu2_3>Diusulkan</a>  |  -->
			<A href=\"pages.php?Pg=SensusHasil\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>   | 			
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>".*/
			""
			;
		
		return $menubar;
			
	}
	
	function genDaftarOpsi(){
		global $Main;
		
		//data cari ----------------------------
		switch($_GET['SPg']){			
			case'04': case'06': case'07': case'09' :{
				$arrCari = array(
					array('1','Nama Barang'),
					array('2','Tahun Perolehan'),					
					array('3','Letak/Alamat'),
					array('4','Keterangan'),			
				);
				break;
			};
			default:{
				$arrCari = array(
					array('1','Nama Barang'),
					array('2','Tahun Perolehan')
					//array('3','Keterangan'),			
				);
				break;
			}
		}
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
				
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Perolehan'),
			array('2','Keadaan Barang'),
			array('3','Tahun Buku')
		);
		
		
		//tampil -------------------------------
		$menu = $_REQUEST['menu'];
		if($menu=='ada'){
			$filtKondBrg = cmb2D_v2('fmKONDBRG',$fmKONDBRG, $Main->KondisiBarang,'','Kondisi Barang','');
		}
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3('PembukuanSkpd') . 
			"</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					'Tampilkan : '.
					 cmb2D_v2('jnsrekap',$jnsrekap,array(
						//array("1","Jumlah Barang")
						array("1","Keuangan")
					),'','Fisik','').
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
			"<tr>
				<th class=\"th02\" width='30' rowspan=3>No.</th>
				<th class=\"th02\" width='50' rowspan=3>Golongan</th>
				<th class=\"th02\" width='50' rowspan=3> Kode Bidang Barang</th>				
				<th class=\"th02\" rowspan=3 >Nama Bidang Barang</th>
				<th class=\"th02\" width='40' rowspan=3> Buku Inventaris</th>
				<th class=\"th02\" colspan=6 >INTRACOMPTABLE</th>
				<th class=\"th02\" width='40' colspan=3 >EXTRACOMPTABLE</th>
				<th class=\"th02\" width='40' rowspan=3 >JUMLAH INTRACOMPTABLE DAN EXTRACOMPTABLE</th>				
				<th class=\"th02\" width='40' rowspan=3 >KETERANGAN</th>
			</tr>
			<tr>	
				<th class=\"th02\" > ASET LANCAR </th>
				<th class=\"th02\" width='40' rowspan=2>ASET TETAP</th>
				<th class=\"th02\" colspan=3>ASET LAINNYA</th>
				<th class=\"th02\" width='40' rowspan=2>JUMLAH</th>
				<th class=\"th02\" width='40' rowspan=2>Dibawah Kapitalisasi</th>
				<!--<th class=\"th02\" width='40' rowspan=2>Aset Heritage</th>-->
				<th class=\"th02\" width='40' rowspan=2>Barang yang dikenakan TGR</th>
				<th class=\"th02\" width='40' rowspan=2>JUMLAH</th>
			</tr>
			<tr>	
				<th class=\"th02\" width='40'>Persediaan</th>
				<th class=\"th02\" width='40'>Kemitraan Dengan Pihak Ketiga</th>
				<th class=\"th02\" width='40'>Aset Lain-lain</th>
				<th class=\"th02\" width='40' >Jumlah Aset Lainnya</th>
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
				<th class=\"th03\" >(9)</th>
				<th class=\"th03\" >(10)=(8)+(9)</th>
				<th class=\"th03\" >(11)=(6)+(7)+(10)</th>
				<th class=\"th03\" >(12)</th>
				<th class=\"th03\" >(13)</th>
				<th class=\"th03\" >(14)=(12)+(13)</th>
				<th class=\"th03\" >(15)=(11)+(14)</th>
				<th class=\"th03\" >(16)</th>
			</tr>				
			$tambahgaris";
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
		
		
		$vtotbi = number_format($this->totbi, $des,',','.');
		
		$vtotAsetLancar 	= number_format($this->totAsetLancar, $des,',','.');
		$vtotAsetTetap 		= number_format($this->totAsetTetap, $des,',','.');
		$vtotAsetLainMitra 	= number_format($this->totAsetLainMitra, $des,',','.');		
		$vtotAsetLainLain 	= number_format($this->totAsetLainLain, $des,',','.');
		$vtotAsetLain 		= number_format($this->totAsetLain, $des,',','.');
		$vtotIntra 			= number_format($this->totIntra, $des,',','.');
		
		$vtotBawahKapital 	= number_format($this->totBawahKapital, $des,',','.');
		$vtotAsetHeritage 	= number_format($this->totAsetHeritage, $des,',','.');
		$vtotExtra 			= number_format($this->totExtra, $des,',','.');
		
		$vtot = number_format($this->tot, $des,',','.');
		
		$url_ = "index.php?Pg=05&SPg=03&c=$c&d=$d&e=$e&e1=$e1&skpdro=1";
		$vtotAsetLancar = "<a href='$url_&jns=lancar&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetLancar."</a>";
		$vtotAsetTetap = "<a href='$url_&jns=tetap&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetTetap."</a>";
		$vtotAsetLainMitra = "<a href='$url_&jns=lainmitra&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetLainMitra."</a>";
		
		$vtotAsetLainLain = "<a href='$url_&jns=lainlain&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetLainLain."</a>";
		$vtotAsetLain = "<a href='$url_&jns=lain&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetLain."</a>";
		$vtotIntra = "<a href='$url_&jns=intra&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotIntra."</a>";
		$vtotBawahKapital = "<a href='$url_&jns=bawahkap&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotBawahKapital."</a>";
		//$vtotAsetHeritage = "<a href='$url_&jns=heritage' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotAsetHeritage."</a>";
		$vtotExtra = "<a href='$url_&jns=extra&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtotExtra."</a>";
		$vtot = "<a href='$url_&jns=tot&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vtot."</a>";
		
				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotbi</td>
				
			<td class='$ColStyle' align='right'><b>$vtotAsetLancar</td>
			<td class='$ColStyle' align='right'><b>$vtotAsetTetap</td>
			<td class='$ColStyle' align='right'><b>$vtotAsetLainMitra</td>
			<td class='$ColStyle' align='right'><b>$vtotAsetLainLain</td>
			<td class='$ColStyle' align='right'><b>$vtotAsetLain</td>
			<td class='$ColStyle' align='right'><b>$vtotIntra</td>
			
			<td class='$ColStyle' align='right'><b>$vtotBawahKapital</td>
			<td class='$ColStyle' align='right'><b>$vtotAsetHeritage</td>
			<td class='$ColStyle' align='right'><b>$vtotExtra</td>
			<td class='$ColStyle' align='right'><b>$vtot</td>
			
			<td class='$ColStyle' align='right'></td>
			";
		
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		$vnmbarang = $isi['nm_barang'];
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		$des = $jnsrekap==1? 2:0;
				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;
				
		if ($isi['g']=='00') {
			$arrKond[] = " f='".$isi['f']."' ";
		}else{
			$arrKond[] = " f='".$isi['f']."' and g='".$isi['g']."' "; 
		}
		$Kondisi = join(' and ', $arrKond);
		if($Kondisi !='' ) $Kondisi = ' where '.$Kondisi;
		//$Kondisi = " where c='$c' ";
		//--- cari jml
		if( $Kondisi != ''){
			$KondisiBI = $Kondisi ." and a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH'  ".
				" and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}else{
			$KondisiBI = "where a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' ".
				" and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}
		//jml bi ----------------------------------------------
		if($jnsrekap==1){
			//$aqry = " select sum(ifnull(jml_harga,0)+ifnull(tot_pelihara,0)+ifnull(tot_pengaman,0)) as cnt from view_buku_induk2_total $KondisiBI";			
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".				
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI ";	
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI";
		}//$cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query( $aqry	));
		$jmlbi = $bi['cnt'];	
		$vjmlbi = number_format($jmlbi, $des, ',', '.');
		
		//intracomtable bi >=$Main->NILAI_EXTRACOMPATIBLE utk thn>=2011 ----------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and ((thn_perolehan<2011) or ( thn_perolehan>=2011 and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f<>'02')) ) )";						
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f<>'02' and thn_perolehan<2011)  )";
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI ".
				"and ((thn_perolehan<2011) or ( thn_perolehan>=2011 and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02')) ) ) ";
				//"and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and thn_perolehan<2011) )";
		}
		///$cek .= $aqry;
		$intra = mysql_fetch_array(mysql_query( $aqry	));
		$jmlIntra = $intra['cnt'];			
		$vjmlIntra= number_format($jmlIntra, $des, ',', '.');
		
		//aset lainnya lain2 ---> rusak berat ---------------------------------
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0))as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.kondisi=3 ) ".
				//" $KondisiBI and ((aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE  ) or( aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011 )) and aa.kondisi=3 ".
				" $KondisiBI and ( (thn_perolehan>=2011 and aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE  ) or( thn_perolehan<2011 ) ) and aa.kondisi=3 ".
				"";
		}else{
			$aqry = "select count(*) as cnt from buku_induk $KondisiBI ".
				//"and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and kondisi=3 ".
				//"and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011)) and kondisi=3 ".
				"and ((thn_perolehan>=2011 and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE )or ( thn_perolehan<2011) ) and kondisi=3 ".
				"";
		}
		//$cek .= $aqry;
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLainLain = $get['cnt'];
		$vjmlAsetLainLain = number_format($jmlAsetLainLain, $des, ',', '.');
		
		//aset lainnya kemitraan dgn pihak ke-3 stpenguasaa=manfaat? ---------
		if($jnsrekap==1){
			$aqry ="select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0))as cnt ".
				" from buku_induk aa".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2) ".
				//" $KondisiBI and ((aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2) or(aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2 and thn_perolehan<2011)) ".
				" $KondisiBI and ( (aa.thn_perolehan>=2011 and aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE  ) or( aa.thn_perolehan<2011 ) ) ".
				" and aa.kondisi<>3 and aa.status_penguasaan=2 ".
				"";			
		}else{
			$aqry = "select count(*) as cnt from buku_induk $KondisiBI ".
				//"and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and status_penguasaan=2 ".
				//"and ((jml_harga>=$Main->NILAI_EXTRACOMPATIBLE ) or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE  and thn_perolehan<2011)) and status_penguasaan=2 ".
				" and ( (thn_perolehan>=2011 and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE  ) or( thn_perolehan<2011 ) ) and kondisi<>3 ".
				" and status_penguasaan=2 ".
				"";
		}
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLainMitra = $get['cnt'];
		$vjmlAsetLainMitra = number_format($jmlAsetLainMitra, $des, ',', '.');
		
		
		//Aset lancar ------------------------------------------			
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and aa.f='05'  ".
				//" ( ((aa.g='17' or aa.g='18') and aa.harga<$Main->NILAI_EXTRACOMPATIBLE ) or (aa.harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.g='19') )".
				" and ((aa.thn_perolehan>=2011 and aa.harga>=$Main->NILAI_EXTRACOMPATIBLE) or (aa.thn_perolehan<2011)  ) ".
				" and aa.g='19'  ".
				" and aa.kondisi<>3 and aa.status_penguasaan<>2".
				" and year(aa.tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}else{
			$aqry = "select count(*) as cnt from buku_induk ".
				" $KondisiBI and f='05'  ".
				//" ( ( (g='17' or g='18') and harga<$Main->NILAI_EXTRACOMPATIBLE ) or (g='19') ) ".
				" and ( (thn_perolehan>=2011 and harga>=$Main->NILAI_EXTRACOMPATIBLE) or (thn_perolehan<2011) ) ".
				" and  g='19'  ".
				" and kondisi<>3 and status_penguasaan<>2 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}
		//$cek = $aqry;
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLancar = $get['cnt'];
		$vjmlAsetLancar = number_format($jmlAsetLancar, $des, ',', '.');
		
		
		
		
		//jml aset lainnya -------------------------------------------------
		$jmlAsetLain = $jmlAsetLainMitra + $jmlAsetLainLain;
		$vjmlAsetLain = number_format($jmlAsetLain, $des, ',', '.');
			
		
						
		//Aset tetap -----------------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI ".
				" and concat(f,g)<>'0519' ".
				" and aa.kondisi<>3 and aa.status_penguasaan<>2  ".
				//" (harga>=$Main->NILAI_EXTRACOMPATIBLE or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05') ) ".
				" and ( (aa.thn_perolehan>=2011 and aa.harga>=$Main->NILAI_EXTRACOMPATIBLE) or (aa.thn_perolehan<2011) ) ".				
				"";
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI ".
				" and kondisi<>3 and status_penguasaan<>2  ".
				" and concat(f,g)<>'0519' ".
				//" (harga>=$Main->NILAI_EXTRACOMPATIBLE or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05')  )  ".	
				" and ( (thn_perolehan>=2011 and harga>=$Main->NILAI_EXTRACOMPATIBLE) or (thn_perolehan<2011) ) ".							
				"";
		}
		$tetap = mysql_fetch_array(mysql_query( $aqry ));
		$jmlAsetTetap = $tetap['cnt'];
		//$jmlAsetTetap = $jmlIntra - $jmlAsetLain - $jmlAsetLancar;
		$vjmlAsetTetap= number_format($jmlAsetTetap, $des, ',', '.');
		
		//aset dibawah kapital -------------------------------------
		if($jnsrekap==1){			
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f='02' and aa.thn_perolehan>=2011";			
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI ".
				" and jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f='02' and thn_perolehan>=2011";
		}
		$bk = mysql_fetch_array(mysql_query( $aqry	));
		$jmlBawahKapital = $bk['cnt'];			
		$vjmlBawahKapital= number_format($jmlBawahKapital, $des, ',', '.');
				
		//aset heritage --------------------------------------------
		$jmlAsetHeritage = 0;
		$vjmlAsetHeritage= number_format($jmlAsetHeritage, $des, ',', '.');
		
		//aset extra = dibawah kapital + heritage ------------------
		$jmlExtra = $jmlBawahKapital + $jmlAsetHeritage ;
		$vjmlExtra = number_format($jmlExtra, $des, ',', '.');
		
		//jml total baris ------------------------------------------
		$jmlTot = $jmlIntra + $jmlExtra;
		$vjmlTot = number_format($jmlTot, $des, ',', '.');
				
		//total kolom ----------------------------------------------
		if ($isi['g']=='00') {
			$this->totbi += $jmlbi;
			$this->totAsetLancar += $jmlAsetLancar;
			$this->totAsetTetap += $jmlAsetTetap;
			$this->totAsetLainMitra += $jmlAsetLainMitra;
			$this->totAsetLainLain += $jmlAsetLainLain;
			$this->totAsetLain += $jmlAsetLain;
			$this->totIntra += $jmlIntra;
			$this->totBawahKapital += $jmlBawahKapital;
			$this->totAsetHeritage += $jmlAsetHeritage;
			$this->totExtra += $jmlExtra;
			$this->tot += $jmlTot;
		}
		
		//cari keterangan
		
				
		//-- format tampil ----------------------------------------
		if ($isi['g']=='00') {
			$vnmbarang = '<b>'.$vnmbarang;
			$vjmlbi = '<b>'.$vjmlbi;			
			$vjmlAsetLancar = '<b>'.$vjmlAsetLancar;
			$vjmlAsetTetap = '<b>'.$vjmlAsetTetap;
			$vjmlAsetLainMitra = '<b>'.$vjmlAsetLainMitra;
			$vjmlAsetLainLain = '<b>'.$vjmlAsetLainLain;
			$vjmlAsetLain = '<b>'.$vjmlAsetLain;
			$vjmlIntra = '<b>'.$vjmlIntra;
			$vjmlBawahKapital = '<b>'.$vjmlBawahKapital;
			$vjmlAsetHeritage = '<b>'.$vjmlAsetHeritage;
			$vjmlExtra = '<b>'.$vjmlExtra;
			$vjmlTot = '<b>'.$vjmlTot;
			$ket='<b>'.$ket;
			
		}else{
			$vnmbarang = '&nbsp;&nbsp;&nbsp;'.$vnmbarang;				
		}
		/*
		if ($isi['f']=='01' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' *)';
		}
		if ($isi['f']=='03' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' **)';
		}
		if ($isi['f']=='04' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' ***)';
		}
		*/
		
		//tambah url --------------------------------------------
		switch($isi['f']){
			case '01' : $spg_ = '04'; break;
			case '02' : $spg_ = '05'; break;
			case '03' : $spg_ = '06'; break;
			case '04' : $spg_ = '07'; break;
			case '05' : $spg_ = '08'; break;
			case '06' : $spg_ = '09'; break;
		}		
		//$fmFiltThnBuku = 		
		$url_ = "index.php?Pg=05&SPg=$spg_&c=$c&d=$d&e=$e&f={$isi['f']}&g={$isi['g']}&skpdro=1";
		$unikid = uniqid();
		$vjmlAsetLancar = "<a href='$url_&jns=lancar&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLancar."</a>";
		$unikid = uniqid();
		$vjmlAsetTetap = "<a href='$url_&jns=tetap&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetTetap."</a>";
		$unikid = uniqid();
		$vjmlAsetLainMitra = "<a href='$url_&jns=lainmitra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLainMitra."</a>";
		$unikid = uniqid();
		$vjmlAsetLainLain = "<a href='$url_&jns=lainlain&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLainLain."</a>";
		$unikid = uniqid();
		$vjmlAsetLain = "<a href='$url_&jns=lain&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLain."</a>";
		$unikid = uniqid();
		$vjmlIntra = "<a href='$url_&jns=intra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlIntra."</a>";
		$unikid = uniqid();
		$vjmlBawahKapital = "<a href='$url_&jns=bawahkap&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlBawahKapital."</a>";
		//$vjmlAsetHeritage = "<a href='$url_&jns=heritage' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetHeritage."</a>";
		$unikid = uniqid();
		$vjmlExtra = "<a href='$url_&jns=extra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlExtra."</a>";
		$unikid = uniqid();
		$vjmlTot = "<a href='$url_&jns=tot&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlTot."</a>";
		
		
		//tampil di kolom ---------------------------------------
		$Koloms[] = array('align=right', $no.'.' );			
		$Koloms[] = array('align=center',$isi['f']);
		$Koloms[] = array('align=center', $isi['g']);
		$Koloms[] = array('align=left', $cek. $vnmbarang);
		$Koloms[] = array('align=right', $cekbi. $vjmlbi);
		
		$Koloms[] = array('align=right', $vjmlAsetLancar);
		$Koloms[] = array('align=right', $vjmlAsetTetap );
		$Koloms[] = array('align=right', $vjmlAsetLainMitra );
		$Koloms[] = array('align=right', $vjmlAsetLainLain );		
		$Koloms[] = array('align=right', $vjmlAsetLain );
		$Koloms[] = array('align=right', $vjmlIntra );
		
		$Koloms[] = array('align=right', $vjmlBawahKapital );		
		$Koloms[] = array('align=right', $vjmlAsetHeritage );
		
		$Koloms[] = array('align=right', $vjmlExtra );
		$Koloms[] = array('align=right', $vjmlTot );
		
		//if($no==1){
		$Koloms[] = array("align=''", $ket);	
		//}else{
			//$Koloms[] = array('align=right', $ket_);
		//}
		
		
		
		
		
		
		
		
		
		
		

		return $Koloms;
	}
	
	function setKolomData_($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		$vnmbarang = $isi['nm_barang'];
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		$des = $jnsrekap==1? 2:0;
				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;
				
		if ($isi['g']=='00') {
			$arrKond[] = " f='".$isi['f']."' ";
		}else{
			$arrKond[] = " f='".$isi['f']."' and g='".$isi['g']."' "; 
		}
		$Kondisi = join(' and ', $arrKond);
		if($Kondisi !='' ) $Kondisi = ' where '.$Kondisi;
		//$Kondisi = " where c='$c' ";
		//--- cari jml
		if( $Kondisi != ''){
			$KondisiBI = $Kondisi ." and a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH'   and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}else{
			$KondisiBI = "where a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}
		//jml bi ----------------------------------------------
		if($jnsrekap==1){
			//$aqry = " select sum(ifnull(jml_harga,0)+ifnull(tot_pelihara,0)+ifnull(tot_pengaman,0)) as cnt from view_buku_induk2_total $KondisiBI";			
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".				
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI ";	
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI";
		}//$cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query( $aqry	));
		$jmlbi = $bi['cnt'];	
		$vjmlbi = number_format($jmlbi, $des, ',', '.');
		
		//Aset lancar ------------------------------------------			
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and aa.f='05' and ".
				" ( ((aa.g='17' or aa.g='18') and aa.harga<$Main->NILAI_EXTRACOMPATIBLE ) or (aa.harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.g='19') )".
				" and aa.kondisi<>3 ".
				" and year(aa.tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}else{
			$aqry = "select count(*) as cnt from buku_induk ".
				" $KondisiBI and f='05' and ".
				" ( ( (g='17' or g='18') and harga<$Main->NILAI_EXTRACOMPATIBLE ) or (g='19') ) ".
				" and kondisi<>3 ".
				" and year(tgl_buku)<='$fmFiltThnBuku' ".
				"";
		}
		//$cek = $aqry;
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLancar = $get['cnt'];
		$vjmlAsetLancar = number_format($jmlAsetLancar, $des, ',', '.');
		
		//aset lainnya kemitraan dgn pihak ke-3 stpenguasaa=manfaat? ---------
		if($jnsrekap==1){
			$aqry ="select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0))as cnt ".
				" from buku_induk aa".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2) ".
				" $KondisiBI and ((aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2) or(aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.status_penguasaan=2 and thn_perolehan<2011)) ".
				"";			
		}else{
			$aqry = "select count(*) as cnt from buku_induk $KondisiBI ".
				//"and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and status_penguasaan=2 ".
				"and ((jml_harga>=$Main->NILAI_EXTRACOMPATIBLE ) or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE  and thn_perolehan<2011)) and status_penguasaan=2 ".
				"";
		}
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLainMitra = $get['cnt'];
		$vjmlAsetLainMitra = number_format($jmlAsetLainMitra, $des, ',', '.');
		
		//aset lainnya lain2 ---> rusak berat ---------------------------------
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0))as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and aa.kondisi=3 ) ".
				" $KondisiBI and ((aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE  ) or( aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011 )) and aa.kondisi=3 ".
				"";
		}else{
			$aqry = "select count(*) as cnt from buku_induk $KondisiBI ".
				//"and jml_harga>=$Main->NILAI_EXTRACOMPATIBLE and kondisi=3 ".
				"and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011)) and kondisi=3 ".
				"";
		}
		//$cek .= $aqry;
		$get = mysql_fetch_array(mysql_query( $aqry	));
		$jmlAsetLainLain = $get['cnt'];
		$vjmlAsetLainLain = number_format($jmlAsetLainLain, $des, ',', '.');
		
		//jml aset lainnya -------------------------------------------------
		$jmlAsetLain = $jmlAsetLainMitra + $jmlAsetLainLain;
		$vjmlAsetLain = number_format($jmlAsetLain, $des, ',', '.');
			
		//intracomtable bi >=$Main->NILAI_EXTRACOMPATIBLE ----------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f<>'02') or (harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011)  )";						
				//" $KondisiBI and (aa.jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f<>'02' and thn_perolehan<2011)  )";
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI ".
				"and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02') or (harga<$Main->NILAI_EXTRACOMPATIBLE and thn_perolehan<2011) )";
				//"and (jml_harga>=$Main->NILAI_EXTRACOMPATIBLE or (jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and thn_perolehan<2011) )";
		}
		///$cek .= $aqry;
		$intra = mysql_fetch_array(mysql_query( $aqry	));
		$jmlIntra = $intra['cnt'];			
		$vjmlIntra= number_format($jmlIntra, $des, ',', '.');
						
		//Aset tetap -----------------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI ".
				" and aa.kondisi<>3 and aa.status_penguasaan<>2 and ".
				//" (harga>=$Main->NILAI_EXTRACOMPATIBLE or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05') or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05' and  thn_perolehan<2011) )  ".
				" (harga>=$Main->NILAI_EXTRACOMPATIBLE or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05') ) ".
				"";
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI ".
				" and kondisi<>3 and status_penguasaan<>2 and ".
				" (harga>=$Main->NILAI_EXTRACOMPATIBLE or (harga<$Main->NILAI_EXTRACOMPATIBLE and f<>'02' and f<>'05')  )  ".				
				"";
		}
		$tetap = mysql_fetch_array(mysql_query( $aqry ));
		$jmlAsetTetap = $tetap['cnt'];
		//$jmlAsetTetap = $jmlIntra - $jmlAsetLain - $jmlAsetLancar;
		$vjmlAsetTetap= number_format($jmlAsetTetap, $des, ',', '.');
		
		//aset dibawah kapital -------------------------------------
		if($jnsrekap==1){
			//$aqry = " select sum(ifnull(jml_harga,0)+ifnull(tot_pelihara,0)+ifnull(tot_pengaman,0)) as cnt from view_buku_induk2_total $KondisiBI and jml_harga<$Main->NILAI_EXTRACOMPATIBLE";			
			/*$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f='02'";			
			*/
			$aqry = " select sum(ifnull(aa.jml_harga,0)+ifnull(bb.tot_pelihara,0)+ifnull(cc.tot_pengaman,0)) as cnt ".
				" from buku_induk aa ".
				" left join v_pemelihara_tot bb on aa.idawal=bb.idbi_awal  ".
				" left join v_pengaman_tot cc on aa.idawal=cc.idbi_awal ".
				" $KondisiBI and aa.jml_harga<$Main->NILAI_EXTRACOMPATIBLE and aa.f='02' and aa.thn_perolehan>=2011";			
		}else{
			$aqry = " select count(*) as cnt from buku_induk $KondisiBI and jml_harga<$Main->NILAI_EXTRACOMPATIBLE and f='02' and thn_perolehan>=2011";
		}
		$bk = mysql_fetch_array(mysql_query( $aqry	));
		$jmlBawahKapital = $bk['cnt'];			
		$vjmlBawahKapital= number_format($jmlBawahKapital, $des, ',', '.');
				
		//aset heritage --------------------------------------------
		$jmlAsetHeritage = 0;
		$vjmlAsetHeritage= number_format($jmlAsetHeritage, $des, ',', '.');
		
		//aset extra = dibawah kapital + heritage ------------------
		$jmlExtra = $jmlBawahKapital + $jmlAsetHeritage ;
		$vjmlExtra = number_format($jmlExtra, $des, ',', '.');
		
		//jml total baris ------------------------------------------
		$jmlTot = $jmlIntra + $jmlExtra;
		$vjmlTot = number_format($jmlTot, $des, ',', '.');
				
		//total kolom ----------------------------------------------
		if ($isi['g']=='00') {
			$this->totbi += $jmlbi;
			$this->totAsetLancar += $jmlAsetLancar;
			$this->totAsetTetap += $jmlAsetTetap;
			$this->totAsetLainMitra += $jmlAsetLainMitra;
			$this->totAsetLainLain += $jmlAsetLainLain;
			$this->totAsetLain += $jmlAsetLain;
			$this->totIntra += $jmlIntra;
			$this->totBawahKapital += $jmlBawahKapital;
			$this->totAsetHeritage += $jmlAsetHeritage;
			$this->totExtra += $jmlExtra;
			$this->tot += $jmlTot;
		}
		
		//cari keterangan
		
				
		//-- format tampil ----------------------------------------
		if ($isi['g']=='00') {
			$vnmbarang = '<b>'.$vnmbarang;
			$vjmlbi = '<b>'.$vjmlbi;			
			$vjmlAsetLancar = '<b>'.$vjmlAsetLancar;
			$vjmlAsetTetap = '<b>'.$vjmlAsetTetap;
			$vjmlAsetLainMitra = '<b>'.$vjmlAsetLainMitra;
			$vjmlAsetLainLain = '<b>'.$vjmlAsetLainLain;
			$vjmlAsetLain = '<b>'.$vjmlAsetLain;
			$vjmlIntra = '<b>'.$vjmlIntra;
			$vjmlBawahKapital = '<b>'.$vjmlBawahKapital;
			$vjmlAsetHeritage = '<b>'.$vjmlAsetHeritage;
			$vjmlExtra = '<b>'.$vjmlExtra;
			$vjmlTot = '<b>'.$vjmlTot;
			$ket='<b>'.$ket;
			
		}else{
			$vnmbarang = '&nbsp;&nbsp;&nbsp;'.$vnmbarang;				
		}
		/*
		if ($isi['f']=='01' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' *)';
		}
		if ($isi['f']=='03' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' **)';
		}
		if ($isi['f']=='04' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' ***)';
		}
		*/
		
		//tambah url --------------------------------------------
		switch($isi['f']){
			case '01' : $spg_ = '04'; break;
			case '02' : $spg_ = '05'; break;
			case '03' : $spg_ = '06'; break;
			case '04' : $spg_ = '07'; break;
			case '05' : $spg_ = '08'; break;
			case '06' : $spg_ = '09'; break;
		}		
		//$fmFiltThnBuku = 		
		$url_ = "index.php?Pg=05&SPg=$spg_&c=$c&d=$d&e=$e&e1=$e1&f={$isi['f']}&g={$isi['g']}&skpdro=1";
		$unikid = uniqid();
		$vjmlAsetLancar = "<a href='$url_&jns=lancar&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLancar."</a>";
		$unikid = uniqid();
		$vjmlAsetTetap = "<a href='$url_&jns=tetap&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetTetap."</a>";
		$unikid = uniqid();
		$vjmlAsetLainMitra = "<a href='$url_&jns=lainmitra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLainMitra."</a>";
		$unikid = uniqid();
		$vjmlAsetLainLain = "<a href='$url_&jns=lainlain&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLainLain."</a>";
		$unikid = uniqid();
		$vjmlAsetLain = "<a href='$url_&jns=lain&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetLain."</a>";
		$unikid = uniqid();
		$vjmlIntra = "<a href='$url_&jns=intra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlIntra."</a>";
		$unikid = uniqid();
		$vjmlBawahKapital = "<a href='$url_&jns=bawahkap&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlBawahKapital."</a>";
		//$vjmlAsetHeritage = "<a href='$url_&jns=heritage' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlAsetHeritage."</a>";
		$unikid = uniqid();
		$vjmlExtra = "<a href='$url_&jns=extra&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlExtra."</a>";
		$unikid = uniqid();
		$vjmlTot = "<a href='$url_&jns=tot&xd=$unikid&fmFiltSdThnBuku=$fmFiltThnBuku' target='blank_' style='color:rgb(51, 51, 51);'>".$vjmlTot."</a>";
		
		
		//tampil di kolom ---------------------------------------
		$Koloms[] = array('align=right', $no.'.' );			
		$Koloms[] = array('align=center',$isi['f']);
		$Koloms[] = array('align=center', $isi['g']);
		$Koloms[] = array('align=left', $cek. $vnmbarang);
		$Koloms[] = array('align=right', $cekbi. $vjmlbi);
		
		$Koloms[] = array('align=right', $vjmlAsetLancar);
		$Koloms[] = array('align=right', $vjmlAsetTetap );
		$Koloms[] = array('align=right', $vjmlAsetLainMitra );
		$Koloms[] = array('align=right', $vjmlAsetLainLain );		
		$Koloms[] = array('align=right', $vjmlAsetLain );
		$Koloms[] = array('align=right', $vjmlIntra );
		
		$Koloms[] = array('align=right', $vjmlBawahKapital );		
		$Koloms[] = array('align=right', $vjmlAsetHeritage );
		
		$Koloms[] = array('align=right', $vjmlExtra );
		$Koloms[] = array('align=right', $vjmlTot );
		
		//if($no==1){
		$Koloms[] = array("align=''", $ket);	
		//}else{
			//$Koloms[] = array('align=right', $ket_);
		//}
		
		
		
		
		
		
		
		
		
		
		

		return $Koloms;
	}
	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/
	
}
$Pembukuan = new PembukuanObj();

?>