<?php

class SensusHasilObj extends DaftarObj2{
	var $Prefix = 'SensusHasil'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'v_ref_kib_keu';//'ref_barang'; //daftar
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
	var $fileNameExcel='hasilsensus.xls';
	var $Cetak_Judul = 'Rekapitulasi Hasil Sensus';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Sensus Barang';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
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
		return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		//return 'Rekapitulasi Hasil Sensus Tahun 2013 ' ;
	}
	function setCetakTitle(){
		return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun 2013 ";
	}
	
	function setMenuEdit(){		
		return '';
	}
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>";
		
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
		" <A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu14>AKUNTANSI</a> ":'';
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
			<A href=\"pages.php?Pg=SensusHasil\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>   | 			
			<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   |
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>"
			;
			
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
		$jnsrekap  =0;
				
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
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3('SensusHasilSkpd') . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					/*'Tampilkan : '.
					 cmb2D_v2('jnsrekap',$jnsrekap,array(
						//array("1","Jumlah Barang")
						array("1","Keuangan")
					)*/
					
					//,'','Fisik','')
					"<input type='hidden' id='jnsrekap' name='jnsrekap' value='$jnsrekap'  >"
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi				
		
		//$arrKondisi[] = " h='00'";
		$arrKondisi[] = " (f<>'07' or (f='07' and g='24'  )or (f='07' and g='00'  ) or (f='07' and g='23'  ) )   ";
		
		
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
		global $Main;
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			$tahun = $Main->thnsensus_default;
		$headerTable =
			"<tr>
				<th class=\"th02\" width='30' rowspan=3>No.</th>
				<th class=\"th02\" width='50' rowspan=3>Golongan</th>
				<th class=\"th02\" width='50' rowspan=3> Kode Bidang Barang</th>				
				<th class=\"th02\" rowspan=3 >Nama Bidang Barang</th>
				<th class=\"th02\" width='40' rowspan=3> Buku Inventaris $tahun</th>
				<th class=\"th02\" colspan=7 >Telah di cek</th>
				<th class=\"th02\" width='40' rowspan=3 colspan=2>Belum Dicek $rp</th>
				<th class=\"th02\" width='40' rowspan=3 >Belum Tercatat $rp</th>
				<th class=\"th02\" width='40' rowspan=3 >Hasil Sensus</th>
				<th class=\"th02\" width='40' rowspan=3 >Keterangan</th>
			</tr>
			<tr>	
				<th class=\"th02\"  colspan=4>Ada</th>
				<th class=\"th02\" width='40' rowspan=2>Tidak Ada $rp</th>
				<th class=\"th02\" rowspan=2 colspan=2>Jumlah</th>
			</tr>
			<tr>	
				<th class=\"th02\" width='40'>Baik $rp</th>
				<th class=\"th02\" width='40'>Kurang Baik $rp</th>
				<th class=\"th02\" width='40'>Rusak Berat $rp</th>
				<th class=\"th02\" width='40' >Jumlah $rp</th>
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
				<th class=\"th03\" >(9)=(6)+(7)+(8)</th>
				<th class=\"th03\" >(10)</th>
				<th class=\"th03\" colspan=2>(11)=(9)+(10)</th>
				<th class=\"th03\" colspan=2>(12)=(5)-(11)</th>
				<th class=\"th03\" >(13)</th>
				<th class=\"th03\" >(14)=(9)+(13)</th>
				<th class=\"th03\" >(15)</th>
			</tr>				
			$tambahgaris";
		return $headerTable;
	}
	
	
	
	function setDaftar_After($no=0, $ColStyle=''){
		
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
				
		$ListData = "<tr class='row1'><td class='$ColStyle' colspan=4 align=center style='height:26'><b>TOTAL</td> 
				<td class='$ColStyle' align='right'><b>$vtotbi</td>
				
				<td class='$ColStyle' align='right'><b>$vtotbaik</td>
				<td class='$ColStyle' align='right'><b>$vtotkb</td>
				<td class='$ColStyle' align='right'><b>$vtotrb</td>
				<td class='$ColStyle' align='right'><b>$vtotada</td>
				
				<td class='$ColStyle' align='right'><b>$vtottada</td>
				
				<td class='$ColStyle' align='right'><b>$vtottelahcek</td>
				<td class='$ColStyle' align='right'><b>$vtottelahcek_persen</td>
				
				<td class='$ColStyle' align='right'><b>$vtotbelumcek</td>
				<td class='$ColStyle' align='right'><b>$vtotbelumcek_persen</td>
				
				<td class='$ColStyle' align='right'><b>$vtottidaktercatat</td>
				<td class='$ColStyle' align='right'><b>$vtothasilsensus</td>
				
				";
		
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS,$Main;
		
		$cek = '';
		$thnsensus = $Main->thnsensus_default;
		$tglSaldoAwal = $Main->defTglBukuBelumSensus ;
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		$vnmbarang = $isi['nm_barang'];
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];	
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
				
		$arrKond = array();
		//kondisi skpd ----------------------------------------------
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;
		//kondisi barang --------------------------------------------		
		//if($isi['f']=='07' && $isi['g']==23){//pemanfaatan
			//$arrKond[]= " status_barang=2 ";
		//}else{
			//$arrKond[]= " status_barang=1 ";
			if ($isi['g']=='00') {
				$arrKond[] = " f='".$isi['f']."' ";
			}else{
				$arrKond[] = " f='".$isi['f']."' and g='".$isi['g']."' "; 
			}	
		//}
		
		$KondisiBrg = join(' and ', $arrKond);
		$KondisiBrg = $KondisiBrg==''? '' : ' and '.$KondisiBrg;
		$Kondisi = join(' and ', $arrKond);
		if($Kondisi !='' ) $Kondisi = ' where '.$Kondisi;
		//$Kondisi = " where c='$c' ";
		//--- cari jml
		if( $Kondisi != ''){
			$KondisiBI = $Kondisi ." and a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH'   and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5  and tgl_buku<='$tglSaldoAwal' ";
		}else{
			$KondisiBI = "where a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH'   and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5 and tgl_buku<='$tglSaldoAwal' ";
		}
		//jml bi ----------------------------------------------				
		if( $Kondisi != ''){
			$KondisiBI2 = $Kondisi ." and a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' and tgl_buku<='$tglSaldoAwal' ";
		}else{
			$KondisiBI2 = "where a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' and tgl_buku<='$tglSaldoAwal' ";
		}
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(harga,0)) as cnt from buku_induk $KondisiBI2";
			$aqryhps = "select sum(ifnull(jml_harga,0)) as cnt from v_penghapusan_bi where tgl_penghapusan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			$aqrypindah = "select sum(ifnull(jml_harga,0)) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			$aqrygr = "select sum(ifnull(jml_harga,0)) as cnt from v1_gantirugi where tgl_gantirugi<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			//$aqrymanfaat = "select sum(ifnull(jml_harga,0)) as cnt from v1_pemanfaatan_bi where tgl_pemanfaatan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
		}else{
			//$aqry = " select count(*) as cnt from buku_induk $KondisiBI2"; 
			$aqry = "select sum(jml_barang_d - jml_barang_k) as cnt from t_jurnal_aset  Where 1=1 $KondisiSKPD ".
				"and g='".$isi['g']."' ".
				"and  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '".$isi['kint'].".".$isi['ka'].".".$isi['kb']."%' ".
				"and  tgl_buku <='$tglSaldoAwal'  and  jns_trans <> 10   "; //$cek=$aqry;
			//$aqryhps = "select count(*) as cnt from v_penghapusan_bi where tgl_penghapusan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			//$aqrypindah = "select count(*) as cnt from v_pindahtangan_bi where tgl_pemindahtanganan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			//$aqrygr = "select count(*) as cnt from v1_gantirugi where tgl_gantirugi<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg ";
			//$aqrymanfaat = "select count(*) as cnt  from v1_pemanfaatan_bi where tgl_pemanfaatan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg "; $cek .= $aqrymanfaat;
			//$aqrymanfaat = "select count(*) as cnt  from v1_pemanfaatan_bi where tgl_pemanfaatan<='$tglSaldoAwal' and id_bukuinduk in (select id from buku_induk where 1=1 $KondisiSKPD and status_barang=2 ) "; $cek .= $aqrymanfaat;
		}
		//$cekbi = $aqry.' ';
		$bi = mysql_fetch_array(mysql_query( $aqry	));
		$hps = mysql_fetch_array(mysql_query( $aqryhps	));
		$pindah = mysql_fetch_array(mysql_query( $aqrypindah ));
		$gr = mysql_fetch_array(mysql_query( $aqrygr ));
		$manfaat = mysql_fetch_array(mysql_query( $aqrymanfaat ));
		$jmlbi = $bi['cnt'] - $hps['cnt'] - $pindah['cnt'] - $gr['cnt'];// - $manfaat['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(biaya_pemeliharaan)as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg and tambah_aset=1 ";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmlbi += $plh['cnt'] ;			
			$aqry = "select sum(biaya_pengamanan)as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' $KondisiSKPD $KondisiBrg and tambah_aset=1 ";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmlbi += $aman['cnt'];
			
			
		}		
		$vjmlbi = number_format($jmlbi, $des, ',', '.');
		$cekbi = '('.$bi['cnt'].'+'.$plh['cnt'].'+'.$aman['cnt'].')'.' - '.$hps['cnt'].' - '.$pindah['cnt'].' - '.$gr['cnt'];//.'-'.$manfaat['cnt'];
		
		//jml tidak ada ----------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(jml_harga) as cnt from view2_sensus ".$Kondisi.
				" and (sesi='' or sesi is null) and (error ='' or error is null) ".
				" and ada=2 and f is not null and tahun_sensus='$thnsensus'";			
		}else{
			$aqry = " select count(*) as cnt from view2_sensus ".$Kondisi.
				" and (sesi='' or sesi is null) and (error ='' or error is null) ".
				"and ada=2 and f is not null and tahun_sensus='$thnsensus'";				
		}		
		$cektidakada = $aqry;
		$tidakada = mysql_fetch_array(mysql_query( $aqry	));
		$jmltidakada = $tidakada['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ".$Kondisi.
				" and f is not null ".
				" and (sesi='' or sesi is null) and (error ='' or error is null) ".
				" and ada=2 and tahun_sensus='$thnsensus'".
				")";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmltidakada += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ".$Kondisi.
				" and f is not null ".
				" and (sesi='' or sesi is null) and (error ='' or error is null) ".
				" and ada=2 and tahun_sensus='$thnsensus'".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmltidakada += $aman['cnt'];
		}	
		$vjmltidakada = number_format($jmltidakada, $des, ',', '.');		
		//jml ada ----------------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(jml_harga,0)) as cnt from view2_sensus ". $Kondisi.
				" and (sesi='' or sesi is null) and (error='' or error is null) ".
				" and f is not null ".
				" and ada=1 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;
		}else{
			$aqry = " select count(*) as cnt from view2_sensus ". $Kondisi.
				" and (sesi='' or sesi is null) and (error='' or error is null) ".
				" and f is not null ".
				" and ada=1 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;	
		}
		$cekada = $aqry;
		$ada = mysql_fetch_array(mysql_query( $aqry	));		
		$jmlada = $ada['cnt'];		
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<='$tglSaldoAwal' and  tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and tahun_sensus='$thnsensus'".
				")";			
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmlada += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and tahun_sensus='$thnsensus'".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmlada += $aman['cnt'];
		}
		$vjmlada = number_format($jmlada, $des, ',', '.');		
		//jml belum cek ----------------------------------------
		$jmlbelumcek = $jmlbi - ($jmltidakada + $jmlada);
		$vjmlbelumcek = number_format($jmlbelumcek, $des, ',', '.');
		//jml baik ---------------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(jml_harga,0)) as cnt from view2_sensus ". $Kondisi.	
				" and (sesi='' or sesi is null) and (error='' or error is null) ".
				" and f is not null ".
				" and ada=1  and kondisi=1 and tahun_sensus='$thnsensus'"; 
		}else{
			$aqry = " select count(*) as cnt from view2_sensus ". $Kondisi.	
				" and (sesi='' or sesi is null) and (error='' or error is null) ".
				" and f is not null ".
				" and ada=1  and kondisi=1 and tahun_sensus='$thnsensus'"; 
		}	
		$cekbaik = $aqry;	
		$baik = mysql_fetch_array(mysql_query( $aqry	));
		$jmlbaik = $baik['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=1 and tahun_sensus='$thnsensus'".
				")";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmlbaik += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=1 and tahun_sensus='$thnsensus'".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmlbaik += $aman['cnt'];
		}
		$vjmlbaik = number_format($jmlbaik, $des, ',', '.');
		//jml kurang baik -------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(jml_harga,0)) as cnt from view2_sensus ". $Kondisi.	" and (sesi='' or sesi is null) and (error='' or error is null) and ada=1  and kondisi=2 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;
		}else{
			$aqry = " select count(*) as cnt from view2_sensus ". $Kondisi.	" and (sesi='' or sesi is null) and (error='' or error is null) and ada=1  and kondisi=2 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;	
		}
		$cekkb = $aqry;
		$kurangbaik = mysql_fetch_array(mysql_query( $aqry	));
		$jmlkurangbaik = $kurangbaik['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where tgl_pemeliharaan<='$tglSaldoAwal' and  tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=2 and tahun_sensus='$thnsensus' ".
				")";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmlkurangbaik += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=2 and tahun_sensus='$thnsensus'".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmlkurangbaik += $aman['cnt'];
		}
		$vjmlkurangbaik = number_format($jmlkurangbaik, $des, ',', '.');		
		//jml rusak berat -------------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(jml_harga,0)) as cnt from view2_sensus ". $Kondisi.	" and (sesi='' or sesi is null) and (error='' or error is null) and ada=1  and kondisi=3 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;
		}else{
			$aqry = " select count(*) as cnt from view2_sensus ". $Kondisi.	" and (sesi='' or sesi is null) and (error='' or error is null) and ada=1  and kondisi=3 and tahun_sensus='$thnsensus'"; //$cek .= $aqry;
		}		
		$cekrb = $aqry;
		$rusakberat = mysql_fetch_array(mysql_query( $aqry	));
		$jmlrusakberat = $rusakberat['cnt'];
		if($jnsrekap==1){
			$aqry = "select sum(ifnull(biaya_pemeliharaan,0))as cnt from v_pemelihara ".
				" where  tgl_pemeliharaan<='$tglSaldoAwal' and   tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=3 and tahun_sensus='$thnsensus'".
				")";
			$plh = mysql_fetch_array(mysql_query($aqry));
			if($plh['cnt']>0) $jmlrusakberat += $plh['cnt'] ;			
			$aqry = "select sum(ifnull(biaya_pengamanan,0))as cnt from v_pengaman ".
				" where tgl_pengamanan<='$tglSaldoAwal' and tambah_aset=1 and id_bukuinduk in (".
				" select idbi as id_bukuinduk from view2_sensus ". $Kondisi." and (sesi='' or sesi is null) and (error='' or error is null) and ada=1 and kondisi=3 and tahun_sensus='$thnsensus'".
				")";
			$aman = mysql_fetch_array(mysql_query($aqry));
			if($aman['cnt']>0) $jmlrusakberat += $aman['cnt'];
		}
		$vjmlrusakberat = number_format($jmlrusakberat, $des, ',', '.');		
		//jml tidak tercatat ----------------------------------
		if($jnsrekap==1){
			$aqry = " select sum(ifnull(jml_harga,0)) as cnt from barang_tidak_tercatat ". $Kondisi." and tahun_sensus='$thnsensus'" ;
		}else{
			$aqry = " select sum(jml_barang) as cnt from barang_tidak_tercatat ". $Kondisi." and tahun_sensus='$thnsensus'";	
		}
		$cektt = $aqry;
		$tidaktercatat = mysql_fetch_array(mysql_query( $aqry	));
		$jmltidaktercatat = $tidaktercatat['cnt'];		
		$vjmltidaktercatat = number_format($jmltidaktercatat, $des, ',', '.');		
		//jml telah cek ---------------------------------------
		$jmltelahcek = $jmlada + $jmltidakada;
		$jmlbelumcek = $jmlbi - $jmltelahcek;
		
		if($jmlbi==0){
			$jmltelahcek_persen = 0;
			$jmlbelumcek_persen = 0;
		}else{
			$jmltelahcek_persen = $jmltelahcek/$jmlbi * 100;	
			$jmlbelumcek_persen = $jmlbelumcek/$jmlbi * 100;
		}		
		
		$jmlhasilsensus = $jmlada + $jmltidaktercatat;
		
		$vjmltelahcek_ = number_format($jmltelahcek, $des, ',', '.');
		$vjmltelahcek_persen_ = number_format($jmltelahcek_persen, 2, ',', '.').' %';
		$vjmlbelumcek = number_format($jmlbelumcek, $des, ',', '.');
		$vjmlbelumcek_persen_ = number_format($jmlbelumcek_persen, 2, ',', '.').' %';	
		$vjmlhasilsensus_ = number_format($jmlhasilsensus, $des, ',', '.'); 
		
		//total
		if ($isi['g']=='00') {
			$this->totbi += $jmlbi;
			
			$this->totbaik += $jmlbaik;
			$this->totkb += $jmlkurangbaik;
			$this->totrb += $jmlrusakberat;
			$this->totada += $jmlada;
			
			$this->tottada += $jmltidakada;
			
			$this->tottelahcek += $jmltelahcek;
			//$this->tottelahcek_persen += $jmltelahcek_persen;
			//if()
			$this->tottelahcek_persen = $this->totbi > 0 ? ($this->tottelahcek/$this->totbi)*100 : 0;
						
			$this->totbelumcek += $jmlbelumcek;
			//$this->totbelumcek_persen += $jmlbelumcek_persen;
			$this->totbelumcek_persen = $this->totbi > 0 ? ($this->totbelumcek/$this->totbi)*100 : 0;
			
			$this->tottidaktercatat += $jmltidaktercatat;
			$this->tothasilsensus += $jmlhasilsensus;
		}
		
		//cari keterangan -----------------------------------------------------------------
		if($no==1){			
			//kib A
			for($i=1;$i<=5;$i++){
				if($jnsrekap==1){
					$aqry = "select sum(jml_harga) as cnt from view2_sensus where 1=1 ". $KondisiSKPD." and f='01' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}else{
					$aqry = "select count(*) as cnt from view2_sensus where 1=1 ". $KondisiSKPD." and f='01' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}
				//$cek .=' qrket='.$aqry;
				$get = mysql_fetch_array(mysql_query($aqry));					
				switch($i){
					case 1: $digunakanA = $get['cnt']; break;
					case 2: $dimanfaatkanA = $get['cnt']; break;
					case 3: $iddleA = $get['cnt']; break;
					case 4: $dikuasaiA = $get['cnt']; break;
					case 5: $sengketaA = $get['cnt']; break;
				}
			}
			//kib C
			for($i=1;$i<=5;$i++){
				if($jnsrekap==1){
					$aqry = "select sum(jml_harga) as cnt from view2_sensus where 1=1  ". $KondisiSKPD." and f='03' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}else{
					$aqry = "select count(*) as cnt from view2_sensus where 1=1 ". $KondisiSKPD." and f='03' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}
				//$cek .=' qrket='.$aqry;
				$get = mysql_fetch_array(mysql_query($aqry));					
				switch($i){
					case 1: $digunakanC = $get['cnt']; break;
					case 2: $dimanfaatkanC = $get['cnt']; break;
					case 3: $iddleC = $get['cnt']; break;
					case 4: $dikuasaiC = $get['cnt']; break;
					case 5: $sengketaC = $get['cnt']; break;
				}
			}			
			//kib D
			for($i=1;$i<=5;$i++){
				if($jnsrekap==1){
					$aqry = "select sum(ifnull(jml_harga,0)) as cnt from view2_sensus where 1=1 ". $KondisiSKPD." and f='04' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}else{
					$aqry = "select count(*) as cnt from view2_sensus where 1=1 ". $KondisiSKPD." and f='04' and (sesi='' or sesi is null) and (error='' or error is null) and status_penguasaan=$i";
				}
				//$cek .=' qrket='.$aqry;
				$get = mysql_fetch_array(mysql_query($aqry));					
				switch($i){
					case 1: $digunakanD = $get['cnt']; break;
					case 2: $dimanfaatkanD = $get['cnt']; break;
					case 3: $iddleD = $get['cnt']; break;
					case 4: $dikuasaiD = $get['cnt']; break;
					case 5: $sengketaD = $get['cnt']; break;
				}	
			}
			$digunakanA = number_format($digunakanA, $des, ',', '.');
			$dimanfaatkanA = number_format($dimanfaatkanA, $des, ',', '.');
			$iddleA = number_format($iddleA, $des, ',', '.');
			$dikuasaiA = number_format($dikuasaiA, $des, ',', '.');
			$sengketaA = number_format($sengketaA, $des, ',', '.');
			
			$digunakanC = number_format($digunakanC, $des, ',', '.');
			$dimanfaatkanC = number_format($dimanfaatkanC, $des, ',', '.');
			$iddleC = number_format($iddleC, $des, ',', '.');
			$dikuasaiC = number_format($dikuasaiC, $des, ',', '.');
			$sengketaC = number_format($sengketaC, $des, ',', '.');
			
			$digunakanD = number_format($digunakanD, $des, ',', '.');
			$dimanfaatkanD = number_format($dimanfaatkanD, $des, ',', '.');
			$iddleD = number_format($iddleD, $des, ',', '.');
			$dikuasaiD = number_format($dikuasaiD, $des, ',', '.');
			$sengketaD = number_format($sengketaD, $des, ',', '.');
			
			$ket_ = "".
				"*) <br>".
				"   - Digunakan : $digunakanA <br>".
				"   - Dimanfaatkan : $dimanfaatkanA <br>".
				"   - Iddle : $iddleA <br>".
				"   - Dikuasai Pihak ke-3 : $dikuasaiA <br>".
				"   - Sengketa : $sengketaA <br>".
				
				"**) <br>".
				"   - Digunakan : $digunakanC <br>".
				"   - Dimanfaatkan : $dimanfaatkanC <br>".
				"   - Iddle : $iddleC <br>".
				"   - Dikuasai Pihak ke-3 : $dikuasaiC <br>".
				"   - Sengketa : $sengketaC <br>".
				
				"***) <br>".
				"   - Digunakan : $digunakanD <br>".
				"   - Dimanfaatkan : $dimanfaatkanD <br>".
				"   - Iddle : $iddleD <br>".
				"   - Dikuasai Pihak ke-3 : $dikuasaiD <br>".
				"   - Sengketa : $sengketaD ".
				"";	
		}
		
				
		//-- format tampil ------------------------------------------------------------------
		if ($isi['g']=='00') {
			$vnmbarang = '<b>'.$vnmbarang;
			$vjmlbi = '<b>'.$vjmlbi;
			
			$vjmlbaik = '<b>'.$vjmlbaik;
			$vjmlkurangbaik = '<b>'.$vjmlkurangbaik;
			$vjmlrusakberat = '<b>'.$vjmlrusakberat;			
			$vjmlada = '<b>'.$vjmlada;
			
			$vjmltidakada = '<b>'.$vjmltidakada;
			
			$vjmltelahcek_ = '<b>'.$vjmltelahcek_;
			$vjmltelahcek_persen_ = '<b>'.$vjmltelahcek_persen_;
			
			$vjmlbelumcek = '<b>'.$vjmlbelumcek;
			$vjmlbelumcek_persen_ = '<b>'.$vjmlbelumcek_persen_;
			
			$vjmltidaktercatat = '<b>'.$vjmltidaktercatat;
			$vjmlhasilsensus_ = '<b>'.$vjmlhasilsensus_;
			
		}else{
			$vnmbarang = '&nbsp;&nbsp;&nbsp;'.$vnmbarang;				
		}
		
		if ($isi['f']=='01' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' *)';
		}
		if ($isi['f']=='03' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' **)';
		}
		if ($isi['f']=='04' && $isi['g']=='00'){
			$vnmbarang = $vnmbarang .' ***)';
		}
		
		
		$cek= $Main->SHOW_CEK? $cek : ''; 
		//$cekbi=''; 
		$cekbaik=''; $cekkb='';
		$cekrb = ''; $cekada=''; $cektidakada='';
		
		$Koloms[] = array('align=right', $no.'.' );
			//if ($Mode == 1) $Koloms[] = array("align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=center',$isi['f']);
		$Koloms[] = array('align=center', $isi['g']);
		$Koloms[] = array('align=left', $cek. $vnmbarang );
		$Koloms[] = array('align=right', "<input type='hidden' value='$cekbi'>". $vjmlbi);
		
		$Koloms[] = array('align=right', $cekbaik.  $vjmlbaik);
		$Koloms[] = array('align=right', $cekkb. $vjmlkurangbaik);
		$Koloms[] = array('align=right', $cekrb.$vjmlrusakberat );
		$Koloms[] = array('align=right', $cekada.$vjmlada);
		
		$Koloms[] = array('align=right', $cektidakada.' '.$vjmltidakada);
		$Koloms[] = array('align=right', $vjmltelahcek_);
		$Koloms[] = array('align=right', $vjmltelahcek_persen_);
		
		$Koloms[] = array('align=right', $vjmlbelumcek);
		$Koloms[] = array('align=right', $vjmlbelumcek_persen_);
		$Koloms[] = array('align=right', $vjmltidaktercatat);
		
		$Koloms[] = array('align=right', $vjmlhasilsensus_);
		
		if($no==1){
			$Koloms[] = array("align='' rowspan=28", $ket_);	
		}else{
			//$Koloms[] = array('align=right', $ket_);
		}
				
		return $Koloms;
	}
	
	
	function setDaftar_before_getrow($no, $isi, $Mode, $TampilCheckBox,
			$RowAtr, $KolomClassStyle)
	{
		$ListData ='';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; 
		
		$RowAtr = "class='row1'";
		//*
		if($isi['f']=='06' && $isi['g']=='00'){
			
			//$Koloms[] = array('align=right', '' );
			//if ($Mode == 1) $Koloms[] = array("align='center'  ", $TampilCheckBox);
			/*$Koloms[] = array("align=center colspan=4", '<b>TOTAL');
			$Koloms[] = array('align=center', '');
			$Koloms[] = array('align=left', '');
			$Koloms[] = array('align=right', '');
			
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '' );
			$Koloms[] = array('align=right', '');
			
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '');
			
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '');
			$Koloms[] = array('align=right', '');
			
			$Koloms[] = array('align=right', '');
		*/
			//$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);			
			//$list_row = genTableRow($Koloms, 
			//				$RowAtr." valign='top' id='$cb' value='".$isi['Id']."'",$KolomClassStyle);					
			//$ListData = $this->setDaftar_after_getrow($list_row, $isi);	
			/*$rowatr_ = $RowAtr." valign='middle' id='$cb' value='".$isi['Id']."' style='height:26'";
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			*/
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
					
		}//*/
		return array ('ListData'=>$ListData, 'no'=>$no);
	}
	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/
	
}
$SensusHasil = new SensusHasilObj();

?>