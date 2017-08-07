<?php

class SensusProgresObj extends DaftarObj2{
	var $Prefix = 'SensusProgres'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'ref_skpd'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d','e','e1'); //daftar/hapus
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
	var $fileNameExcel='sensusprogres.xls';
	var $Cetak_Judul = 'Sensus Progress';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Sensus Barang';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	//var $pagePerHal= '25';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $totbi = 0;
	
	var $totbaik = 0;
	var $totkb = 0;
	var $totrb = 0;
	var $totada = 0;
	
	var $tottada = 0;
	var $tottelahcek = 0;
	var $tottelahcek_persen = 0;
	
	var $totbelumcek = 0;
	var $totbelumcek_persen = 0;
	var $tottidaktercatat = 0;
	var $tothasilsensus = 0;
	
	function setTitle(){
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		return 'Sensus Progress';
	}
	function setCetakTitle(){
		$tahun_sensus = $_REQUEST['tahun_sensus'];
		return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". $tahun_sensus;
	}
	
	function setMenuEdit(){		
		return '';
	}
	
	function setMenuView(){		
		return 			
			
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

		$menu_pembukuan =
		$Main->MODUL_PEMBUKUAN?
		" <A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Pembukuan' $styleMenu14>PEMBUKUAN</a> ":'';
		$menu_peta = 
		$Main->MODUL_PETA ?
		 " <A href=\"pages.php?Pg=map&SPg=03\" title='Peta' target='_blank'>PETA</a> |" : '';				
		
		
		return 
			//"<tr height='22' valign='top'><td >".
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
			
			<!--
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu12>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu13>KIP</a>  |  
			-->
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |
			$menu_peta
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |
			<A href=\"index.php?Pg=05&SPg=KIR\" $styleMenu1_14 title='Kartu Inventaris Ruangan'>KIR</a> |
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' $styleMenu>SENSUS</a> |
			$menu_pembukuan					  
			  &nbsp&nbsp&nbsp
			</td></tr></table>".
			
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin: 1 0 0 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!-- <A href=\"pages.php?Pg=sensus&menu=kertaskerja\" title='Kertas Kerja' $styleMenu2_5>Kertas Kerja</a> |  -->
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Belum Cek' $styleMenu2_1>Belum Cek</a> |
			<A href=\"pages.php?Pg=SensusScan\" title='Hasil Scan' $styleMenu2_9>Hasil Scan</a> |
			<A href=\"pages.php?Pg=SensusTidakTercatat\" title='Barang Tidak Tercatat' $styleMenu2_8>Tidak Tercatat</a> |
			
			<A href=\"pages.php?Pg=sensus&menu=ada\" title='Ada Barang' $styleMenu2_2>Ada</a>  |  
			<A href=\"pages.php?Pg=sensus&menu=tidakada\" title='Tidak Ada Barang' $styleMenu2_5>Tidak Ada</a>  |  
			<!--<A href=\"pages.php?Pg=sensus&menu=diusulkan\" title='Diusulkan Penghapusan' $styleMenu2_3>Diusulkan</a>  |  -->
			<A href=\"pages.php?Pg=SensusHasil2\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4_>Hasil Sensus</a>   | 			
			<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4>Sensus Progress</a>   | 			
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>"
			;
			
	}
	
	function genDaftarInitial(){
		global $Main;
		
		$vOpsi = $this->genDaftarOpsi();
		
		switch($this->daftarMode){			
			/*case '1' :{ //detail horisontal
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;float:left;width:$this->daftarWidth' >"."</div>".
					"<div id='{$this->Prefix}_cont_daftar_det' style='float:left'></div>";
				break;
			}*/
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr><td style='width:$this->containWidth;'>".
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
					"</td>".
					"<td>".
					"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >".							
					"</div>";					
				break;
			}
		}
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' id='tahun_sensus' name='tahun_sensus' value='$Main->thnsensus_default'>".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
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
		$jnsrekap = 0;
		$tahun_sensus = $_REQUEST['tahun_sensus'];
		if ($tahun_sensus=='') $tahun_sensus= $Main->thnsensus_default;
				
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
		$TampilOpt =
			/*"<table width=\"100%\" class=\"adminform\">	".
			"<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx('SensusProgresSkpd') . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			*/
			//cmbQuery('tahun_sensus','2013', "select tahun_sensus, tahun_sensus where (sesi is null or sesi='' )and(error is null or error='') group by tahun_sensus");
			genFilterBar(
				array(
					//'Tampilkan: '.	
					//cmb2D_v2('jnsrekap',$jnsrekap,array( array("1","Keuangan")	),'','Fisik','').
					"<input type='hidden' id='jnsrekap' name='jnsrekap' value='$jnsrekap'  >".
					' Tahun Sensus '.
					 //cmb2D_v2('jnsrekap',$jnsrekap,array( array("1","Harga Barang")	),'','Jumlah Barang','')
					 cmbQuery('tahun_sensus',$tahun_sensus, "select tahun_sensus, tahun_sensus from sensus where (sesi is null or sesi='' )and(error is null or error='') group by tahun_sensus")
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi -----------------------	
		$fmSKPD = $HTTP_COOKIE_VARS['coSKPD'];
		$fmUNIT = $HTTP_COOKIE_VARS['coUNIT'];
		$fmSUBUNIT = $HTTP_COOKIE_VARS['coSUBUNIT'];
		$fmSEKSI = $HTTP_COOKIE_VARS['coSEKSI'];
		
		if($fmSKPD !='' && $fmSKPD != '00' ) $arrKondisi[] = "c ='$fmSKPD'";			
		if($fmUNIT !='' && $fmUNIT != '00' ) $arrKondisi[] = "d ='$fmUNIT'";			
		if($fmSUBUNIT !='' && $fmSUBUNIT != '00' ) $arrKondisi[] = "e ='$fmSUBUNIT'";			
		if($fmSEKSI !='' && $fmSEKSI != '00'  && $fmSEKSI != '000' ) $arrKondisi[] = "e1 ='$fmSEKSI'";			
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
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>".	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table>".*/
			"<br>";
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
				<th class=\"th02\" width='30' >No.</th>
				<th class=\"th02\" width='50' colspan=4>Uraian</th>
				<th class=\"th02\" width='50' >Buku Inventaris $rp</th>
				<th class=\"th02\" width='50' >Telah Sensus $rp</th>
				<th class=\"th02\" width='50' >Progress (%)</th>								
			</tr>			
			<tr>
				<th class=\"th03\" >(1)</th>
				<th class=\"th03\" colspan=4>(2)</th>
				<th class=\"th03\" >(3)</th>				
				<th class=\"th03\" >(4)</th>				
				<th class=\"th03\" >(5)</th>				
			</tr>				
			$tambahgaris";
		return $headerTable;
	}
	
	
	
	
	function setDaftar_After($no=0, $ColStyle=''){
		
		/*$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		
		$vtotbi = number_format($this->totbi, $des,',','.');
		
		$vtotbaik = number_format($this->totbaik, $des,',','.');
		$vtotkb = number_format($this->totkb, $des,',','.');
		$vtotrb = number_format($this->totrb, $des,',','.');
		
		$vtotada = number_format($this->totada, $des,',','.');
		$vtottada = number_format($this->tottada, $des,',','.');
		
		$vtottelahcek = number_format($this->tottelahcek, $des,',','.');		
		$vtottelahcek_persen = number_format($this->tottelahcek_persen, 2,',','.').' %';
		
		//$vtotbelumcek = number_format($this->totbelumcek, $des,',','.');
		$vtotbelumcek = number_format($this->totbelumcek, $des,',','.');
		$vtotbelumcek_persen = number_format($this->totbelumcek_persen, 2,',','.').' %';
		
		$vtottidaktercatat = number_format($this->tottidaktercatat, $des,',','.');
		$vtothasilsensus = number_format($this->tothasilsensus, $des,',','.');
				
		$ListData = "<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center style='height:26'><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotbi</td>			
			";
		*/
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		$vnmbarang = $isi['nm_barang'];
				
		$c = $isi['c'];//$HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $isi['d'];//$HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $isi['e'];//$HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $isi['e1'];//$HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		
		$thnsensus = $_REQUEST['tahun_sensus'];//'2013';
		if ($thnsensus=='')$thnsensus = '2013';
		$tglthnsensus = $thnsensus.'-01-01';
				
		$arrKond = array();
		//kondisi skpd ----------------------------------------
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;
		
		//$aqry1 = "select count(*) as cnt from view2_sensus where  1=1 $KondisiSKPD and tahun_sensus = '$thnsensus' and (error='' or error is null) and (sesi='' or sesi is null) "; 
		/*
		if($jnsrekap==1){ //harga
			$aqry1 = "select sum(ifnull(harga,0)+ifnull(tot_pelihara,0)+ifnull(tot_pengaman,0)) as cnt from sensus aa ".
				"left join view_buku_induk2_total bb on aa.idbi = bb.id ".
				"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
				"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
				"and bb.f<>'06' ".
				"";	
		}else{//kuantiti
			$aqry1 = "select count(*) as cnt from sensus aa ".
				"left join buku_induk bb on aa.idbi = bb.id ".
				"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
				"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
				"and bb.f<>'06' ".
				"";	
		}*/
		//sensus ----------------------------------------------------------------------------
		if($jnsrekap==1){ //harga
			$aqry1 = "select sum(ifnull(harga,0)) as cnt from sensus aa ".
				"left join buku_induk bb on aa.idbi = bb.id ".
				"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
				"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
				"and bb.f<>'06' ".
				";";	
		}else{//kuantiti
			$aqry1 = "select count(*) as cnt from sensus aa ".
				"left join buku_induk bb on aa.idbi = bb.id ".
				"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
				"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
				"and bb.f<>'06' ".
				";";	
		}
		$cekqrysensus .= $aqry1;
		
		$cek .= $aqry1;
		$get = mysql_fetch_array( mysql_query($aqry1) );
		$cntsensus = $get['cnt'];
		
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' and id_bukuinduk in (".
				" select idbi as id_bukuinduk from sensus where  ".
				" (sesi='' or sesi is null) and (error='' or error is null)  ".
				" tahun_sensus = '$thnsensus' ".
				")";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $cntsensus += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' and id_bukuinduk in (".
				" select idbi as id_bukuinduk from sensus where ".
				" (sesi='' or sesi is null) and (error='' or error is null)".
				" tahun_sensus = '$thnsensus' ".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $cntsensus += $aman['cnt'];
		}
		
		//bi
		/*if($jnsrekap==1){ //harga		
			$aqry = "select sum(ifnull(harga,0)+ifnull(tot_pelihara,0)+ifnull(tot_pengaman,0)) as cnt ".
				"from view_buku_induk2_total where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".
				"and status_barang<>3 and status_barang<>4 and status_barang<>5 ".
				"and f<>'06' ".
				"";
		}else{ //kuantiti
			$aqry = "select count(*) as cnt from buku_induk ".
				"where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".
				"and status_barang<>3 and status_barang <>4 and status_barang<>5 ".
				"and f<>'06' ".
				"";
		}*/
		if($jnsrekap==1){ //harga		
			$aqry = "select sum(ifnull(harga,0))as cnt ".
				"from buku_induk where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".				
				"and f<>'06' ".
				"";
			$aqryhps = "select sum(ifnull(jml_harga,0)) as cnt from v_penghapusan_bi where tgl_penghapusan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
			$aqrypindah = "select sum(ifnull(jml_harga,0)) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
			$aqrygr = "select sum(ifnull(jml_harga,0)) as cnt from v1_gantirugi where tgl_gantirugi<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
		}else{ //kuantiti
			$aqry = "select count(*) as cnt from buku_induk ".
				"where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".				
				"and f<>'06' ".
				"";
			$aqryhps = "select count(*) as cnt from v_penghapusan_bi where tgl_penghapusan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
			$aqrypindah = "select count(*) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
			$aqrygr = "select count(*) as cnt from v1_gantirugi where tgl_gantirugi<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
				
		}
		$cek .= $aqry;
		$get = mysql_fetch_array( mysql_query($aqry) );
		$gethps = mysql_fetch_array( mysql_query($aqryhps) );
		$getpindah = mysql_fetch_array( mysql_query($aqrypindah) );
		$getgr = mysql_fetch_array( mysql_query($aqrygr) );
		
		$cntbi = $get['cnt'] - $gethps['cnt']-$getpindah['cnt']-$getgr['cnt'];
		$cekbi = $get['cnt'].' - '.$gethps['cnt'].' - '.$getpindah['cnt'].' - '.$getgr['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' ".
				"";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $cntbi += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' ".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $cntbi += $aman['cnt'];
		}
		
		
		
		if ($cntbi > 0){
			$progress = $cntsensus / $cntbi * 100;	
		}else{
			$progress = 0;
		}
		$progress = number_format($progress,2, ',', '.');
		$cntsensus = number_format($cntsensus,$des, ',', '.');
		$cntbi = number_format($cntbi,$des, ',', '.');
		
		
		$cekqrysensus = $Mode==2 || $Mode==3 ? '' : "<div id='cekqrysensus' style='display:none'>$cekqrysensus</div>" ;
		$cekqrysensus = $Main->SHOW_CEK ? $cekqrysensus : '';
		
		$Koloms[] = array('align=right', $no.'.' );
			//if ($Mode == 1) $Koloms[] = array("align='center'  ", $TampilCheckBox);
		//$Koloms[] = array('align=left',$uraian);
		
		if($isi['d']=='00' && $isi['e']=='00' && $isi['e1']==$kdSubUnit0 ){
			$Koloms[] = array("align='left' colspan=4", '<b>'.$isi['c'].'. '.$isi['nm_skpd']);
			$Koloms[] = array("align='right' ", "<input type='hidden' id='cek' value='$cekbi'><b>". $cntbi);
			$Koloms[] = array("align='right' ", "<b>". $cntsensus);	
			$Koloms[] = array('align=right', "<input type='hidden' value='".$isi['c'].$isi['d'].$isi['e'].$isi['e1']."' ><b>".$progress);
		}else if($isi['d']!=='00' && $isi['e']=='00'  && $isi['e1']==$kdSubUnit0 ){
			$Koloms[] = array("align='left' width='10' style='border-right-width:0;'", '');
			$Koloms[] = array("align='left' colspan=3 style='border-left-width:0;'", '<b>'.$isi['d'].'. '.$isi['nm_skpd']);
			$Koloms[] = array("align='right' ", "<input type='hidden' id='cek' value='$cekbi'><b>". $cntbi);
			$Koloms[] = array("align='right' ", "<b>". $cntsensus);	
			$Koloms[] = array('align=right', "<input type='hidden' value='".$isi['c'].$isi['d'].$isi['e'].$isi['e1']."' ><b>".$progress);
		}else if($isi['d']!='00' && $isi['e']!=='00' && $isi['e1']==$kdSubUnit0 ){
			$Koloms[] = array("align='left' width='10' style='border-right-width:0;'", '');
			$Koloms[] = array("align='left' width='10' style='border-left-width:0;border-right-width:0;'", '');
			$Koloms[] = array("align='left' colspan=2 style='border-left-width:0;'", '<b>'.$isi['e'].'. '.$isi['nm_skpd']);
			$Koloms[] = array("align='right' ", "<input type='hidden' id='cek' value='$cekbi'><b>". $cntbi);
			$Koloms[] = array("align='right' ", "<b>". $cntsensus);	
			$Koloms[] = array('align=right', "<input type='hidden' value='".$isi['c'].$isi['d'].$isi['e'].$isi['e1']."' ><b>".$progress);
		}else{
			$Koloms[] = array("align='left' width='10' style='border-right-width:0;'", '');
			$Koloms[] = array("align='left' width='10' style='border-left-width:0;border-right-width:0;'", '');
			$Koloms[] = array("align='left' width='10' style='border-left-width:0;border-right-width:0;'", '');
			$Koloms[] = array("align='left' style='border-left-width:0;'", $isi['e1'].'. '.$isi['nm_skpd']);
			$Koloms[] = array("align='right' ", "<input type='hidden' id='cek' value='$cekbi'>".$cntbi);
			$Koloms[] = array("align='right' ", $cntsensus.$cekqrysensus);	
			$Koloms[] = array('align=right', "<input type='hidden' value='".$isi['c'].$isi['d'].$isi['e'].$isi['e1']."' >".$progress);
		}
		
		return $Koloms;
	}
	
	function setDaftar_before_getrow($no, $isi, $Mode, $TampilCheckBox,	$RowAtr, $KolomClassStyle){
	global $Main;
	
		if($no ==1){
			$RowAtr = " class='row0' valign='top' id='1' value='00 00 00' onclick='SensusProgres.rowOnClick(this)' ";
			
			$jnsrekap = $_REQUEST['jnsrekap'];
			$des = $jnsrekap==1? 2:0;
		
			$thnsensus = $_REQUEST['tahun_sensus'];//'2013';
			if ($thnsensus=='')$thnsensus = $Main->thnsensus_default;			
			$tglthnsensus = $thnsensus.'-01-01';
			$KondisiSKPD='';
						
			if($jnsrekap==1){ //harga
				$aqry1 = "select sum(ifnull(harga,0)) as cnt from sensus aa ".
					"left join buku_induk bb on aa.idbi = bb.id ".
					"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
					"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
					"and bb.f<>'06' and bb.f is not null and aa.ada>0 ".
					"";	
			}else{//kuantiti
				$aqry1 = "select count(*) as cnt from sensus aa ".
					"left join buku_induk bb on aa.idbi = bb.id ".
					"where 1=1 $KondisiSKPD and aa.tahun_sensus = '$thnsensus' ".
					"and (aa.error='' or aa.error is null) and (aa.sesi='' or aa.sesi is null) ".
					"and bb.f<>'06' and bb.f is not null and aa.ada>0 ".
					"";	
			}
			$get = mysql_fetch_array( mysql_query($aqry1) );
			$cntsensus = $get['cnt'];
			if($jnsrekap==1){
				$aqry = "select sum(biaya_pemeliharaan)as cnt from v_pemelihara ".
					" where tgl_pemeliharaan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' and id_bukuinduk in (".
					" select idbi as id_bukuinduk from sensus where  ".
					" (sesi='' or sesi is null) and (error='' or error is null)  ".
					" tahun_sensus = '$thnsensus' ".
					")";
				$plh = mysql_fetch_array(mysql_query($aqry));
				if($plh['cnt']>0) $cntsensus += $plh['cnt'] ;			
				$aqry = "select sum(biaya_pengamanan)as cnt from v_pengaman ".
					" where tgl_pengamanan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' and id_bukuinduk in (".
					" select idbi as id_bukuinduk from sensus where ".
					" (sesi='' or sesi is null) and (error='' or error is null)".
					" tahun_sensus = '$thnsensus' ".
					")";
				$aman = mysql_fetch_array(mysql_query($aqry));
				if($aman['cnt']>0) $cntsensus += $aman['cnt'];
			}
			//BI ---------------------------------------------------------------
			if($jnsrekap==1){ //harga		
				$aqry = "select sum(ifnull(harga,0))as cnt ".
					"from buku_induk where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".				
					"and f<>'06' ".
					"";
				$aqryhps = "select sum(ifnull(jml_harga,0)) as cnt from v_penghapusan_bi where tgl_penghapusan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
				$aqrypindah = "select sum(ifnull(jml_harga,0)) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
				$aqrygr = "select sum(ifnull(jml_harga,0)) as cnt from v1_gantirugi where tgl_gantirugi<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
			}else{ //kuantiti
				$aqry = "select count(*) as cnt from buku_induk ".
					"where 1=1 $KondisiSKPD and tgl_buku<'$tglthnsensus' ".				
					"and f<>'06' ".
					"";
				$aqryhps = "select count(*) as cnt from v_penghapusan_bi where tgl_penghapusan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
				$aqrypindah = "select count(*) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";
				$aqrygr = "select count(*) as cnt from v1_gantirugi where tgl_gantirugi<'$tglthnsensus' $KondisiSKPD and f<>'06'  ";					
			}
			$cek .= $aqry;
			$get = mysql_fetch_array( mysql_query($aqry) );
			$gethps = mysql_fetch_array( mysql_query($aqryhps) );
			$getpindah = mysql_fetch_array( mysql_query($aqrypindah) );
			$getgr = mysql_fetch_array( mysql_query($aqrygr) );
			
			$cntbi = $get['cnt'] - $gethps['cnt']-$getpindah['cnt']-$getgr['cnt'];
			
			if($jnsrekap==1){
				$aqry = "select sum(biaya_pemeliharaan)as cnt from v_pemelihara ".
					" where tgl_pemeliharaan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' ".
					"";
				$plh = mysql_fetch_array(mysql_query($aqry));
				if($plh['cnt']>0) $cntbi += $plh['cnt'] ;			
				$aqry = "select sum(biaya_pengamanan)as cnt from v_pengaman ".
					" where tgl_pengamanan<'$tglthnsensus' and tambah_aset=1 $KondisiSKPD and f<>'06' ".
					"";
				$aman = mysql_fetch_array(mysql_query($aqry));
				if($aman['cnt']>0) $cntbi += $aman['cnt'];
			}
			$cekbi = //' '.$aqry. 
				'('.$get['cnt'].'+'.$plh['cnt'].'+'.$aman['cnt'].')'.' - '.$gethps['cnt'].' - '.$getpindah['cnt'].' - '.$getgr['cnt'];
			
			
			if ($cntbi > 0){
				$progress = $cntsensus / $cntbi * 100;	
			}else{
				$progress = 0;
			}
			
			$progress = number_format($progress,2, ',', '.');
			$cntsensus = number_format($cntsensus,$des, ',', '.');
			$cntbi = number_format($cntbi,$des, ',', '.');
			$Koloms = array();
			$Koloms[] = array('align=right', '' );
			$Koloms[] = array("align='left' colspan=4", $Main->NM_WILAYAH2);
			$Koloms[] = array("align='right' ", "<input type='hidden' value='$cekbi' ><b>". $cntbi);
			$Koloms[] = array("align='right' ", "<input type='hidden' value='$ceksensus' ><b>". $cntsensus);
			$Koloms[] = array("align='right' ", "<input type='hidden' value='' ><b>". $progress);
						
			$ListData =  $this->genTableRow($Koloms, $RowAtr, $KolomClassStyle);
			//$no++;	
		}else{
			$ListData = '';
		}						
		return array('ListData'=>$ListData, 'no'=>$no);
	}
	/*
	function setDaftar_before_getrow($no, $isi, $Mode, $TampilCheckBox,
			$RowAtr, $KolomClassStyle)
	{
		$ListData ='';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; 
		
		$RowAtr = "class='row1'";
		
		if($isi['f']=='06' && $isi['g']=='00'){
			
			
			$jnsrekap = $_REQUEST['jnsrekap'];
			$des = $jnsrekap==1? 2:0;
			
			$vtotbi = number_format($this->totbi, $des,',','.');
			
			$vtotbaik = number_format($this->totbaik, $des,',','.');
			$vtotkb = number_format($this->totkb, $des,',','.');
			$vtotrb = number_format($this->totrb, $des,',','.');
			
			$vtotada = number_format($this->totada, $des,',','.');
			$vtottada = number_format($this->tottada, $des,',','.');
			
			$vtottelahcek = number_format($this->tottelahcek, $des,',','.');		
			$vtottelahcek_persen = number_format($this->tottelahcek_persen, 2,',','.').' %';
			
			//$vtotbelumcek = number_format($this->totbelumcek, $des,',','.');
			$vtotbelumcek = number_format($this->totbelumcek, $des,',','.');
			$vtotbelumcek_persen = number_format($this->totbelumcek_persen, 2,',','.').' %';
			
			$vtottidaktercatat = number_format($this->tottidaktercatat, $des,',','.');
			$vtothasilsensus = number_format($this->tothasilsensus, $des,',','.');
			
			$ListData = 
				"<tr class='row1'><td class='$KolomClassStyle' colspan=4 align=center style='height:26'><b>JUMLAH</td> 
				<td class='$KolomClassStyle' align='right'><b>$vtotbi</td>
				
				<td class='$KolomClassStyle' align='right'><b>$vtotbaik</td>
				<td class='$KolomClassStyle' align='right'><b>$vtotkb</td>
				<td class='$KolomClassStyle' align='right'><b>$vtotrb</td>
				<td class='$KolomClassStyle' align='right'><b>$vtotada</td>
				
				<td class='$KolomClassStyle' align='right'><b>$vtottada</td>
				
				<td class='$KolomClassStyle' align='right'><b>$vtottelahcek</td>
				<td class='$KolomClassStyle' align='right'><b>$vtottelahcek_persen</td>
				
				<td class='$KolomClassStyle' align='right'><b>$vtotbelumcek</td>
				<td class='$KolomClassStyle' align='right'><b>$vtotbelumcek_persen</td>
				
				<td class='$KolomClassStyle' align='right'><b>$vtottidaktercatat</td>
				<td class='$KolomClassStyle' align='right'><b>$vtothasilsensus</td>				
				";
					
		}
		return array ('ListData'=>$ListData, 'no'=>$no);
	}
	*/
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/
	
}
$SensusProgres = new SensusProgresObj();

?>