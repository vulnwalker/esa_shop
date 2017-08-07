<?php

class DaftarImportObj extends DaftarObj2{
	var $Prefix = 'DaftarImport'; //jsname
	//var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'buku_induk'; //daftar
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
	var $fileNameExcel='DaftarImport.xls';
	var $Cetak_Judul = 'Daftar Import';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Daftar Import';
	var $PageIcon = 'images/penerimaan_01.gif';
	var $pagePerHal= '25';
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
		global $Main;
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		//if($Main->MODUL_AKUNTASI){
			return 'Daftar Import';	
		//}else{
		//	return 'Pembukuan';	
		//}
		
		
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		return " <DIV ALIGN=CENTER>Penyusutan ";
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
		
		return
			""
			;
			
	}
	function genSumHal($Kondisi){
		
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$Sum = 0;
		$SumArr=array();
		$vSum = array();
		
		$fsum_ = array();
		$fsum_[] = "count(*) as cnt";
		//$i=0;
		foreach($this->FieldSum as &$value){
			$fsum_[] = "sum($value) as sum_$value";
		}
		$fsum = join(',',$fsum_);
				
		$aqry = $this->setSumHal_query($Kondisi, $fsum); $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = 1;//$isi['cnt'];			
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue(0, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}	
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = '';
		//if( sizeof($vSum)==0) $vsum
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
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
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']; //tgl buku
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
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y-m-d');
		
		/*$barcodeCari = 				
					"<span style='color:red'>BARCODE</span><br>
					<input type='TEXT' value='' id='barcodeCariBarang_input' name='barcodeCariBarang_input' 
						style='font-size:24;width: 369px;' size='28' maxlength='28' ".					
					">".
					"<span id='barcodeCariBarang_msg' name='barcodeCariBarang_msg'>
						<a style='color:red;' href='javascript:barcodeCariBarang.setInputReady()'>Not Ready! (click for ready)</a>".
					"</span>";
			*/		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td style='padding:6'>
			</td>".
			"<td width='375'>".
				
				$barcodeCari.
				
					
				//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>-->
			"</td>
			</tr></table>".
			
			"
			<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
			<tbody><tr valign=\"top\"> 
			<td align=\"Left\"> &nbsp;&nbsp;<div style=\"float:left\"><select name=\"fmCariComboField\" id=\"fmCariComboField\"><option>Cari Data</option><option value=\"nm_barang\">Nama Barang</option><option value=\"thn_perolehan\">Tahun Perolehan</option></select>
			<input type=\"text\" id=\"fmCariComboIsi\" name=\"fmCariComboIsi\" value=\"\">
			<input type=\"button\" value=\"Cari\" onclick=\"Penatausaha.refreshList(true)\"></div><div id=\"penatausaha_pilihan_msg\" style=\"float:right;padding: 4 4 4 8;\"></div></td><td width=\"375\"></td>
			</tr>
			</tbody></table>".
			/*
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
				<tbody><tr valign=\"top\">   		
				<td> <div id=\"\" style=\"float:right; padding: 2 0 0 0\">
					<img id=\"daftaropsi_slide_img\" src=\"images/tumbs/up_2.png\" onclick=\"daftaropsi_click(94)\" style=\"cursor:pointer\">
				</div><div id=\"daftaropsi_div\" style=\"height: 94px; overflow-y: hidden;\"><table width=\"100%\"><tbody><tr><td><div style=\"float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0\"> Tampilkan : </div><div style=\"float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0\"> Tgl. Buku </div><div style=\"float:left;height:22;width:490\">
		<div id=\"fmFiltTglBtw_tgl1_content\">
		<div style=\"float:left;padding: 0 4 0 0\"><select style=\"height:20\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl1')\" name=\"fmFiltTglBtw_tgl1_tgl\" id=\"fmFiltTglBtw_tgl1_tgl\"><option value=\"\">Tgl</option><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option><option value=\"6\">6</option><option value=\"7\">7</option><option value=\"8\">8</option><option value=\"9\">9</option><option value=\"10\">10</option><option value=\"11\">11</option><option value=\"12\">12</option><option value=\"13\">13</option><option value=\"14\">14</option><option value=\"15\">15</option><option value=\"16\">16</option><option value=\"17\">17</option><option value=\"18\">18</option><option value=\"19\">19</option><option value=\"20\">20</option><option value=\"21\">21</option><option value=\"22\">22</option><option value=\"23\">23</option><option value=\"24\">24</option><option value=\"25\">25</option><option value=\"26\">26</option><option value=\"27\">27</option><option value=\"28\">28</option><option value=\"29\">29</option><option value=\"30\">30</option><option value=\"31\">31</option></select></div>		
		<div style=\"float:left;padding: 0 4 0 0\">
			<select style=\"height:20\" name=\"fmFiltTglBtw_tgl1_bln\" id=\"fmFiltTglBtw_tgl1_bln\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl1')\"><option value=\"\">Pilih Bulan</option><option value=\"01\">Januari</option><option value=\"02\">Pebruari</option><option value=\"03\">Maret</option><option value=\"04\">April</option><option value=\"05\">Mei</option><option value=\"06\">Juni</option><option value=\"07\">Juli</option><option value=\"08\">Agustus</option><option value=\"09\">September</option><option value=\"10\">Oktober</option><option value=\"11\">Nopember</option><option value=\"12\">Desember</option></select>
		</div>
		<div style=\"float:left;padding: 0 4 0 0\"><input type=\"text\" name=\"fmFiltTglBtw_tgl1_thn\" id=\"fmFiltTglBtw_tgl1_thn\" value=\"\" size=\"1\" maxlength=\"4\" onkeypress=\"return isNumberKey(event)\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl1')\"></div><div style=\"float:left;padding: 0 4 0 0\">
			<input type=\"button\" value=\"Clear\" name=\"fmFiltTglBtw_tgl1_btClear\" id=\"fmFiltTglBtw_tgl1_btClear\" onclick=\"TglEntry_cleartgl('fmFiltTglBtw_tgl1')\">				
		</div>	<input $dis=\"\" type=\"hidden\" id=\"fmFiltTglBtw_tgl1\" name=\"fmFiltTglBtw_tgl1\" value=\"\">
			<input type=\"hidden\" id=\"fmFiltTglBtw_tgl1_kosong\" name=\"fmFiltTglBtw_tgl1_kosong\" value=\"0\">
		</div>	<div style=\"float:left;padding: 4 8 0 4\">s/d</div>
		<div id=\"fmFiltTglBtw_tgl2_content\">
		<div style=\"float:left;padding: 0 4 0 0\"><select style=\"height:20\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl2')\" name=\"fmFiltTglBtw_tgl2_tgl\" id=\"fmFiltTglBtw_tgl2_tgl\"><option value=\"\">Tgl</option><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option><option value=\"6\">6</option><option value=\"7\">7</option><option value=\"8\">8</option><option value=\"9\">9</option><option value=\"10\">10</option><option value=\"11\">11</option><option value=\"12\">12</option><option value=\"13\">13</option><option value=\"14\">14</option><option value=\"15\">15</option><option value=\"16\">16</option><option value=\"17\">17</option><option value=\"18\">18</option><option value=\"19\">19</option><option value=\"20\">20</option><option value=\"21\">21</option><option value=\"22\">22</option><option value=\"23\">23</option><option value=\"24\">24</option><option value=\"25\">25</option><option value=\"26\">26</option><option value=\"27\">27</option><option value=\"28\">28</option><option value=\"29\">29</option><option value=\"30\">30</option><option value=\"31\">31</option></select></div>		
		<div style=\"float:left;padding: 0 4 0 0\">
			<select style=\"height:20\" name=\"fmFiltTglBtw_tgl2_bln\" id=\"fmFiltTglBtw_tgl2_bln\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl2')\"><option value=\"\">Pilih Bulan</option><option value=\"01\">Januari</option><option value=\"02\">Pebruari</option><option value=\"03\">Maret</option><option value=\"04\">April</option><option value=\"05\">Mei</option><option value=\"06\">Juni</option><option value=\"07\">Juli</option><option value=\"08\">Agustus</option><option value=\"09\">September</option><option value=\"10\">Oktober</option><option value=\"11\">Nopember</option><option value=\"12\">Desember</option></select>
		</div>
		<div style=\"float:left;padding: 0 4 0 0\"><input type=\"text\" name=\"fmFiltTglBtw_tgl2_thn\" id=\"fmFiltTglBtw_tgl2_thn\" value=\"\" size=\"1\" maxlength=\"4\" onkeypress=\"return isNumberKey(event)\" onchange=\"TglEntry_createtgl('fmFiltTglBtw_tgl2')\"></div><div style=\"float:left;padding: 0 4 0 0\">
			<input type=\"button\" value=\"Clear\" name=\"fmFiltTglBtw_tgl2_btClear\" id=\"fmFiltTglBtw_tgl2_btClear\" onclick=\"TglEntry_cleartgl('fmFiltTglBtw_tgl2')\">				
		</div>	<input $dis=\"\" type=\"hidden\" id=\"fmFiltTglBtw_tgl2\" name=\"fmFiltTglBtw_tgl2\" value=\"\">
			<input type=\"hidden\" id=\"fmFiltTglBtw_tgl2_kosong\" name=\"fmFiltTglBtw_tgl2_kosong\" value=\"0\">
		</div>	</div><div style=\"float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22\"></div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><select name=\"fmFiltThnBuku\" id=\"fmFiltThnBuku\"><option value=\"\">Tahun Buku</option><option value=\"2014\">2014</option><option value=\"2013\">2013</option><option value=\"2012\">2012</option><option value=\"2011\">2011</option><option value=\"2010\">2010</option><option value=\"2009\">2009</option><option value=\"0\">0</option></select></div></td></tr></tbody></table><div style=\"border-top: 1px solid #E5E5E5;height:1\"></div><table width=\"100%\"><tbody><tr><td><div style=\"float:left;padding: 0 4 0 0;height:22;\"><select name=\"fmTahunPerolehan\" id=\"fmTahunPerolehan\"><option value=\"\">Dari Tahun</option><option value=\"2014\">2014</option><option value=\"2013\">2013</option><option value=\"2012\">2012</option><option value=\"2011\">2011</option><option value=\"2010\">2010</option><option value=\"2009\">2009</option><option value=\"2008\">2008</option><option value=\"2007\">2007</option><option value=\"2006\">2006</option><option value=\"2005\">2005</option><option value=\"2004\">2004</option><option value=\"2003\">2003</option><option value=\"2002\">2002</option><option value=\"2001\">2001</option><option value=\"2000\">2000</option><option value=\"1999\">1999</option><option value=\"1998\">1998</option><option value=\"1997\">1997</option><option value=\"1996\">1996</option><option value=\"1995\">1995</option><option value=\"1993\">1993</option><option value=\"1992\">1992</option><option value=\"1990\">1990</option><option value=\"1989\">1989</option><option value=\"1979\">1979</option><option value=\"1960\">1960</option><option value=\"1940\">1940</option><option value=\"1920\">1920</option><option value=\"1900\">1900</option><option value=\"0000\">0000</option></select></div><div style=\"float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0\"> s/d </div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><select name=\"fmTahunPerolehan2\" id=\"fmTahunPerolehan2\"><option value=\"\">Tahun</option><option value=\"2014\">2014</option><option value=\"2013\">2013</option><option value=\"2012\">2012</option><option value=\"2011\">2011</option><option value=\"2010\">2010</option><option value=\"2009\">2009</option><option value=\"2008\">2008</option><option value=\"2007\">2007</option><option value=\"2006\">2006</option><option value=\"2005\">2005</option><option value=\"2004\">2004</option><option value=\"2003\">2003</option><option value=\"2002\">2002</option><option value=\"2001\">2001</option><option value=\"2000\">2000</option><option value=\"1999\">1999</option><option value=\"1998\">1998</option><option value=\"1997\">1997</option><option value=\"1996\">1996</option><option value=\"1995\">1995</option><option value=\"1993\">1993</option><option value=\"1992\">1992</option><option value=\"1990\">1990</option><option value=\"1989\">1989</option><option value=\"1979\">1979</option><option value=\"1960\">1960</option><option value=\"1940\">1940</option><option value=\"1920\">1920</option><option value=\"1900\">1900</option><option value=\"0000\">0000</option></select></div><div style=\"float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22\"></div><div style=\"float:left;padding: 0 4 0 4;height:22;\"><select name=\"fmKONDBRG\" id=\"fmKONDBRG\"><option value=\"\">Kondisi Barang</option><option value=\"1\">Baik</option><option value=\"2\">Kurang Baik</option><option value=\"3\">Rusak Berat</option></select></div><div style=\"float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22\"></div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><select name=\"tahun_sensus\" id=\"tahun_sensus\"><option value=\"\">Tahun Sensus</option><option value=\"belum_sensus\">Belum Sensus</option><option value=\"2013\">2013</option></select></div></td></tr></tbody></table><div style=\"border-top: 1px solid #E5E5E5;height:1\"></div><table width=\"100%\"><tbody><tr><td><div style=\"float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 \"> Kode Barang </div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><input id=\"kode_barang\" name=\"kode_barang\" value=\"\" title=\"Cari Kode Barang (ex: 01.02.01.01.01)\"><input type=\"hidden\" id=\"jns\" name=\"jns\" value=\"extra\"><input type=\"hidden\" id=\"fmFiltSdThnBuku\" name=\"fmFiltSdThnBuku\" value=\"2014\"></div><div style=\"float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22\"></div><div style=\"float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 \"> Nama Barang </div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><input id=\"nama_barang\" name=\"nama_barang\" value=\"\" title=\"Cari Nama Barang (ex: Meja Kayu)\"></div><div style=\"float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22\"></div><div style=\"float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0\"> Harga Perolehan Rp </div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><input type=\"text\" name=\"jml_harga1\" id=\"jml_harga1\" value=\"\" onkeydown=\"oldValue=this.value;\" onkeypress=\"return isNumberKey(event); \" onkeyup=\"TampilUang(\" tampilfmjmlharga2',this.value);'=\"\" title=\"Cari Barang dengan harga perolehan lebih dari atau sama dengan (ex: 1000000)\"></div><div style=\"float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0\"> s/d </div><div style=\"float:left;padding: 0 4 0 0;height:22;\"><input type=\"text\" name=\"jml_harga2\" id=\"jml_harga2\" value=\"\" onkeydown=\"oldValue=this.value;\" onkeypress=\"return isNumberKey(event); \" onkeyup=\"TampilUang(\" tampilfmjmlharga2',this.value);'=\"\" title=\"Cari Barang dengan harga perolehan kurang dari atau sama dengan (ex: 1000000)\"></div></td></tr></tbody></table></div></td>
				</tr>
				</tbody></table>".
				*/
				
				"<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
				<tbody><tr valign=\"top\"><td> <div style=\"float:left\"> &nbsp;&nbsp; Urutkan berdasar : 
				<select name=\"odr1\"><option value=\"\">--</option><option value=\"tahun\">Tahun Perolehan</option><option value=\"kondisi\">Keadaan Barang</option><option value=\"year(tgl_buku)\">Tahun Buku</option></select><input type=\"checkbox\" name=\"AcsDsc1\" value=\"checked\">Desc. 
				&nbsp;&nbsp; 
				Baris per halaman <input type=\"text\" name=\"jmPerHal\" id=\"jmPerHal\" size=\"4\" value=\"25\">
			<input type=\"button\" onclick=\"".$this->Prefix.".refreshList(true)\" value=\"Tampilkan\">&nbsp;&nbsp;</div></td></tr></tbody></table>
		".
		
			
			/*genFilterBar(
				array(	
					'Tampilkan : '.
					 cmb2D_v2('jnsrekap',$jnsrekap,array(
						//array("1","Jumlah Barang")
						array("1","Keuangan")
					),'','Fisik',''),
					//'Tanggal Buku s/d '.
					createEntryTgl3($fmFiltThnBuku, 'fmFiltThnBuku', '', '', 
						'Tanggal Buku s/d &nbsp;', 'adminForm', TRUE, FALSE, 1)
					
				),$this->Prefix.".refreshList(true)",TRUE
			)*/
			"";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		//$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		
		//Kondisi				
		$arrKondisi= array();
		//$arrKondisi[] = " h='00'";		
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
		$Limit = ' limit 0,1 '; //tes akuntansi
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
		
			
		$headerTable =
				"<tr>
				
				<th class=\"th01\" width='40px'>No.</th>				
				$Checkbox
				<th class=\"th01\" width='100px'>Tanggal</th>				
				<th class=\"th01\" rowspan='' style='width:600px'>S K P D</th>
				
			
				
				<th class=\"th01\" rowspan='' >U P B</th>
				
				
				<th class=\"th01\" rowspan='' width='100px'>Tgl. Update</th>
				
				</tr>				
				$tambahgaris";
		return $headerTable;
	}
	
	
	function setDaftar_After($no=0, $ColStyle=''){
		
		
				
		$ListData = '';
			/*"<tr class='row1'>
			<td class='$ColStyle' colspan=8 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>141.493.800,00</td>
				
			
			
			
			<td class='$ColStyle' align='right' colspan=3>&nbsp</td>
			";*/
		
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		//$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		//tampil di kolom ---------------------------------------
		$cekAsetTetap='';
		$cek_bawahkap = '';
		$Koloms[] = array('align=right', $no.'.' );	
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);		
		$Koloms[] = array('align=center','02-09-2014 10:03');	
		$Koloms[] = array('align=', 'Sekretariat Daerah');
		
		$Koloms[] = array('align=', '' );//. ' cek='.$KondisiBI2 );
				
		
		$Koloms[] = array('align=center','01-10-2014 09:26' );		
		
		
		//$Koloms[] = array('align=', '' );
		
		

		return $Koloms;
	}
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'formEdit':{				
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 700;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' "));
		$subunit = $get['nm_skpd'];		
		
		
		$vbrg = 
			"<input type=\"text\" name=\"fmIDBARANG\" id=\"fmIDBARANG\" value=\"01.01.11.01.01\" size=\"15\" onblur=\"iframefmIDBARANG.document.all.formI.Cari.value=''+this.value+'';
				iframefmIDBARANG.document.all.formI.submit();\"> 
			<input type=\"text\" name=\"fmNMBARANG\" id=\"fmNMBARANG\" value=\"Tanah Bangunan Rumah Negara Gol I\" size=\"60\" readonly=\"\"> 
			<input type=\"button\" value=\"Cari\" onclick=\"TampilkanIFrame(document.all.cariiframefmIDBARANG)\"> 
			";
		$vmerk = 
			"<textarea name=\"fmMEREK\" cols=\"60\" style=\"margin: 2px;width: 438px; height: 51px;\" >".
			'Jl. Otto Iskandardinata No. 1'.
			'Kel. Babakan Ciamis'.
			'Kec. Bandung Wetan '.
			'Kota Bandung'.
			"</textarea>";
			
		$vharga = 
			"<input type=\"text\"  id=\"standar\" name=\"standar\" value=\"141.493.800,00\">	";
		$vkondisi =
			//"<input type=\"text\"  id=\"standar\" name=\"standar\" value=\"Baik\" >	";
			"<select name=\"fmKONDISIBARANG\" id=\"fmKONDISIBARANG\"><option value=\"\">Pilih</option><option value=\"1\">Baik</option><option value=\"2\">Kurang Baik</option><option value=\"3\">Rusak Berat</option></select>";
		$tahun = 2014;
		
		$vnosk=
			"<input type=\"text\"  id=\"standar\" name=\"standar\" value=\"SK-GUB/2014/0201/001\"  style='width:200px;'>	";
		
		$vket = 
			"<textarea name=\"fmMEREK\" cols=\"60\" style=\"margin: 2px;width: 438px; height: 51px;\">".			
			"</textarea>";
			
		$this->form_fields = array(	
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'', 'row_params'=>"style='height:24'" ),
			'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit,  'type'=>'' , 'row_params'=>"style='height:24'"),
			'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit,  'type'=>'' , 'row_params'=>"style='height:24'"),
			
			'tahun' => array(  'label'=>'Tahun Anggaran', 'value'=> $tahun,  'type'=>'text' ),
			'brg' => array(  'label'=>'Nama Barang', 'value'=> $vbrg,  'type'=>'' ),
			'merk' => array(  'label'=>'Merk/Type/Ukuran/<br>Spesifikasi/Alamat', 'value'=> $vmerk, 'type'=>'', 'row_params'=>"valign='top'" ),
			'kondisi' => array( 'label'=>'Kondisi', 'value'=> $vkondisi, 'type'=>'' ),
			'harga' => array( 'label'=>'Harga Perolehan', 'value'=>$vharga, 'type'=>''  ),	
			'sk' => array( 'label'=>'SK Gubernur', 'value'=>'', 'type'=>'', 'pemisah'=>' '),
			'no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Nomor', 'value'=>$vnosk, 'type'=>''),
			'tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'value'=>'2014-02-01', 'type'=>'date'),
			'ket' => array( 'label'=>'Keterangan', 'value'=>$vket, 'type'=>'', 'row_params'=>"valign='top'"),
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
}
$DaftarImport = new DaftarImportObj();

?>