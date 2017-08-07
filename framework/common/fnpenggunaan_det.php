<?php

class Penggunaan_DetObj extends DaftarObj2{
	var $Prefix = 'Penggunaan_Det'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'penggunaan_det'; //daftar
	var $TblName_Hapus = 'penggunaan_det';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 10, 11,9);//berdasar mode
	var $FieldSum_Cp2 = array( 3, 3,3);	
	var $checkbox_rowspan = 2;
	//var $totalCol = 11; //total kolom daftar
	//var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
//	var $totalhalstr = '<b>Total per Halaman';
//	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='CetakPenggunaan.xls';
	var $Cetak_Judul = 'CetakPenggunaan';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penggunaan';
	var $PageIcon = 'images/penerimaan_01.gif';
	var $pagePerHal= '25';
	var $FormName = 'Penggunaan_form';	
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
			return 'Penggunaan';	
		//}else{
		//	return 'Pembukuan';	
		//}
		
		
	}

	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>	
			<br>";
	}
	
	function setCetak_footer(){
		return "<br>".	
				PrintTTD($this->Cetak_WIDTH);
	}	
	
function PrintTTD($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;


    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    $TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
    if (c == '04') {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd1 = '1' ");
    } else {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '00' and ttd1 = '1' ");
    }
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD1 = $isi['nik'];
        $NAMASKPD1 = $isi['nm_pejabat'];
        $JABATANSKPD1 = $isi['jabatan'];
    }
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
        $JABATANSKPD2 = $isi['jabatan'];
    }
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='MENGETAHUI' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='KEPALA OPD'	STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='PENGURUS BARANG' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA OPD</span>";
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR> 
					$vJABATAN1
					<BR><BR><BR><BR><BR><BR>
					$vNAMA1
					<br>
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp4'>
					$vTITIKMANGSA<BR> 
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}		
	
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		return " <DIV ALIGN=CENTER>LAMPIRAN KEPUTUSAN GUBERNUR/BUPATI/WALIKOTA ..........<br>
								   NOMOR ..............<br>
								   TANGGAL ..............<br>
								   TENTANG PENETAPAN STATUS PENGGUNAAN BARANG MILIK DAERAH PADA DINAS/BADAN/KANTOR ....... ";
	}
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Usulan()","new_f2.png","Usulan",'Usulan')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batal()","delete_f2.png","Batal", 'Batal').
			"</td>";
	}
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakSK(\"$Op\")","print_f2.png",'Cetak SK',"Cetak SK")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak Lampiran',"Cetak Lampiran",'','','','','style="width:75"')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){	
		global $Main;
		
		//style = terpilih
		
		return
			""
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
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']; //tgl buku
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$id_penggunaan = $_REQUEST['id_penggunaan'];
				
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
			/*"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td style='padding:6'>
			<td width=\"100%\" valign=\"top\">			
				".// . WilSKPD_ajx($this->Prefix.'Skpd') . 
			"</td>".
			"<td width='375'>".
				
				$barcodeCari.
				
					
				//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>-->
			"</td>
			</tr></table>".*/
			
			/*"
			<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
			<tbody><tr valign=\"top\"> 
			<td align=\"Left\"> &nbsp;&nbsp;<div style=\"float:left\"><select name=\"fmCariComboField\" id=\"fmCariComboField\"><option>Cari Data</option><option value=\"nm_barang\">Nama Barang</option><option value=\"thn_perolehan\">Tahun Perolehan</option></select>
			<input type=\"text\" id=\"fmCariComboIsi\" name=\"fmCariComboIsi\" value=\"\">
			<input type=\"button\" value=\"Cari\" onclick=\"Penatausaha.refreshList(true)\"></div><div id=\"penatausaha_pilihan_msg\" style=\"float:right;padding: 4 4 4 8;\"></div></td><td width=\"375\"></td>
			</tr>
			</tbody></table>".*/
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
				
				/*"<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
					<tbody>
					<tr valign=\"top\">
						<td> 
							<div style=\"float:left\">". 
								/*"&nbsp;&nbsp; Urutkan berdasar : 
								<select name=\"odr1\">
									<option value=\"\">--</option>
									<option value=\"tahun\">Tahun Perolehan</option>
									<option value=\"kondisi\">Keadaan Barang</option>
									<option value=\"year(tgl_buku)\">Tahun Buku</option>
								</select>""
								<input type=\"checkbox\" name=\"AcsDsc1\" value=\"checked\">Desc. &nbsp;&nbsp;".
								"Tahun Anggaran &nbsp;<input type=\"text\" name=\"thn_anggaran\" id=\"thn_anggaran\" size=\"4\" value=\"\">&nbsp;&nbsp;
								Baris per halaman &nbsp;<input type=\"text\" name=\"jmPerHal\" id=\"jmPerHal\" size=\"4\" value=\"\">
								<input type=\"button\" onclick=\"".$this->Prefix.".refreshList(true)\" value=\"Tampilkan\">&nbsp;&nbsp;
							</div>
						</td>
					</tr>
					</tbody>
				</table>".*/
		
			
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
			"<input type=\"hidden\" name=\"id_penggunaan\" id=\"id_penggunaan\" value=\"$id_penggunaan\">";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		$id_penggunaan = $_REQUEST['id_penggunaan'];
		
		//Kondisi				
		$arrKondisi= array();
		//$arrKondisi[] = " h='00'";		
		//$arrKondisi[] = " year(tgl_buku)<='$fmFiltThnBuku' ";
		
		if(!empty($id_penggunaan)) $arrKondisi[]= " ref_idpenggunaan='$id_penggunaan'";
		
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
		//$Limit = '';
		//$Limit = ' limit 0,1 '; //tes akuntansi
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
				<!--<th class=\"th01\" width='70' colspan='". ($cetak? "2": "3") ."'>No. Urt</th>-->
				<th class=\"th01\" width='50' rowspan='2'>No. Urt</th>
				$Checkbox	
				<th class=\"th01\" width='125' rowspan='2'>No. Kode Barang</th>
				<th class=\"th01\" width='300' rowspan='2'>Nama Barang/<br>Jenis barang</th>				
				<th class=\"th01\" width='200' rowspan='2'>Merk/Model</th>				
				<th class=\"th01\" width='200' rowspan='2'>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>								
				<th class=\"th01\" width='50' rowspan='2'>Ukuran</th>
				<th class=\"th01\" width='50' rowspan='2'>Bahan</th>
				<th class=\"th01\" width='75' rowspan='2'>Tahun Pembuatan/<br>Pembelian</th>
				<th class=\"th01\" width='100' rowspan='2'>Jumlah Barang/<br>Register</th>
				<th class=\"th01\" width='150' rowspan='2'>Harga Perolehan</th>
				<th class=\"th02\" colspan='2'>Keadaan Barang</th>
				<th class=\"th01\" width='300' rowspan='2' >Keterangan</th>
				</tr>
				<tr>
				<th class=\"th01\" width='100'>Baik<br><B></th>								
				<th class=\"th01\" width='100'>Kurang Baik<br>(KB)</th>		
				</tr>";
				//$tambahgaris";
		return $headerTable;
	}
	
	
	/*function setDaftar_After($no=0, $ColStyle=''){
		
		
				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=8 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>141.493.800,00</td>
				
			
			
			
			<td class='$ColStyle' align='right' colspan=3>&nbsp</td>
			";
		
		return $ListData;
	}*/
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		//$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		//tampil di kolom ---------------------------------------
	 	$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['ref_idbi']."'")) ;
		$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'")) ;
		$kode_barang=$bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
		//--------------------------------Mendapatkan Isi Merk, alamat, no sertifikat-------------------------//
		switch($bi['f']){
			case '01' : { //KIB A
				$kib=mysql_fetch_array(mysql_query("select * from kib_a where idbi=".$bi['idawal'].""));	
				$aw=mysql_fetch_array(mysql_query("select * from ref_wilayah where a='".$kib['alamat_a']."' and b='".$kib['alamat_b']."'"));
				$alamat="";
				$alamat.=ifempty($kib['alamat'],'-');
				$alamat.=$kib['alamat_kel']!=''?'<br>Kel. '.$kib['alamat_kel']:'';
				$alamat.=$kib['alamat_kec']!=''?'<br>Kec. '.$kib['alamat_kec']:'';
				$alamat.=$aw['nm_wilayah']!=''?'<br>'.$aw['nm_wilayah']:'';
				$merk=$alamat;
				$no_sertifikat=ifempty($kib['sertifikat_no'],'-');
				$ukuran=$kib['luas']!=''? number_format($kib['luas'],2,',','.') :'-';
				$bahan=$kib['bahan'];
				$ket=ifempty($kib['ket'],'-');
				break;
			}
			case '02' : { //KIB B
				$kib=mysql_fetch_array(mysql_query("select * from kib_b where idbi=".$bi['idawal'].""));	
				$merk=ifempty($kib['merk'],'-');
				if(!empty($kib['sertifikat_no']) && !empty($kib['no_rangka']) && !empty($kib['no_mesin'])){
					$no_sertifikat=ifempty($kib['sertifikat_no'],'-').' / '.ifempty($bi['no_rangka'],'-').' / '.ifempty($bi['no_mesin'],'-');				
				}else{
					$no_sertifikat='-';
				}
				$ket=ifempty($kib['ket'],'-');
				break;
			}
			case '03' : { //KIB C
				$kib=mysql_fetch_array(mysql_query("select * from kib_c where idbi=".$bi['idawal'].""));	
				$merk="";
				$aw=mysql_fetch_array(mysql_query("select * from ref_wilayah where a='".$kib['alamat_a']."' and b='".$kib['alamat_b']."'"));
				$alamat="";
				$alamat.=ifempty($kib['alamat'],'-');
				$alamat.=$kib['alamat_kel']!=''?'<br>Kel. '.$kib['alamat_kel']:'';
				$alamat.=$kib['alamat_kec']!=''?'<br>Kec. '.$kib['alamat_kec']:'';
				$alamat.=$aw['nm_wilayah']!=''?'<br>'.$aw['nm_wilayah']:'';
				$merk=$alamat;
				$no_sertifikat=ifempty($kib['dokumen_no'],'-');
				$ukuran = $Main->Bangunan[$kib['kondisi_bangunan'] - 1][1];
				$ket=ifempty($kib['ket'],'-');
				break;
			}
			case '04' : { //KIB D
				$kib=mysql_fetch_array(mysql_query("select * from kib_d where idbi=".$bi['idawal'].""));	
				$merk="";
				$aw=mysql_fetch_array(mysql_query("select * from ref_wilayah where a='".$kib['alamat_a']."' and b='".$kib['alamat_b']."'"));
				$alamat="";
				$alamat.=ifempty($kib['alamat'],'-');
				$alamat.=$kib['alamat_kel']!=''?'<br>Kel. '.$kib['alamat_kel']:'';
				$alamat.=$kib['alamat_kec']!=''?'<br>Kec. '.$kib['alamat_kec']:'';
				$alamat.=$aw['nm_wilayah']!=''?'<br>'.$aw['nm_wilayah']:'';
				$merk=$alamat;
				$no_sertifikat=ifempty($kib['dokumen_no'],'-');
				$ket=ifempty($kib['ket'],'-');
				break;
			}
			case '05' : { //KIB E
				$kib=mysql_fetch_array(mysql_query("select * from kib_e where idbi=".$bi['idawal'].""));	
				$merk="";
				$aw=mysql_fetch_array(mysql_query("select * from ref_wilayah where a='".$kib['alamat_a']."' and b='".$kib['alamat_b']."'"));
				$alamat="";
				$alamat.=ifempty($kib['alamat'],'-');
				$alamat.=$kib['alamat_kel']!=''?'<br>Kel. '.$kib['alamat_kel']:'';
				$alamat.=$kib['alamat_kec']!=''?'<br>Kec. '.$kib['alamat_kec']:'';
				$alamat.=$aw['nm_wilayah']!=''?'<br>'.$aw['nm_wilayah']:'';
				$merk=$alamat;
				$no_sertifikat='-';
				$ket=ifempty($kib['ket'],'-');
				$bahan='seni_bahan';
				break;
			}
			case '06' : { //KIB F
				$kib=mysql_fetch_array(mysql_query("select * from kib_f where idbi=".$bi['idawal'].""));	
				$merk="";
				$aw=mysql_fetch_array(mysql_query("select * from ref_wilayah where a='".$kib['alamat_a']."' and b='".$kib['alamat_b']."'"));
				$alamat="";
				$alamat.=ifempty($kib['alamat'],'-');
				$alamat.=$kib['alamat_kel']!=''?'<br>Kel. '.$kib['alamat_kel']:'';
				$alamat.=$kib['alamat_kec']!=''?'<br>Kec. '.$kib['alamat_kec']:'';
				$alamat.=$aw['nm_wilayah']!=''?'<br>'.$aw['nm_wilayah']:'';
				$merk=$alamat;
				$no_sertifikat=ifempty($kib['dokumen_no'],'-');
				$ukuran = $Main->Bangunan[$kib['kondisi_bangunan'] - 1][1];
				$ket=ifempty($kib['ket'],'-');
				break;
			}
		}
		$bahan=!empty($bahan) ? $bahan : '-';
		$ukuran=!empty($ukuran) ? $ukuran : '-';
		//--------------------------------akhir Mendapatkan Isi Merk, alamat, no sertifikat-------------------------//
		
		//cek kondisi
		if($bi['kondisi']==1){
			$ceklist_B='B';
			$ceklist_KB='';
		}else{
			$ceklist_B='';
			$ceklist_KB='KB';			
		}			
		
		$Koloms[] = array('align=right', $no.'.' );	
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);		
		$Koloms[] = array('align=left', $kode_barang);
		$Koloms[] = array('align=left',$brg['nm_barang']);
		$Koloms[] = array('align=left',$merk);
		$Koloms[] = array('align=left',$no_sertifikat);
		$Koloms[] = array('alignleft=',$ukuran);		
		$Koloms[] = array('align=left', $bahan);
		$Koloms[] = array('align=center', $isi['tahun']);
		$Koloms[] = array('align=right', number_format($isi['jml_barang'],0,',','.')." / ".$bi['noreg']  );	
		$Koloms[] = array('align=right', number_format($isi['jml_harga'],2,',','.') );	
		$Koloms[] = array('align=center', $ceklist_B );	
		$Koloms[] = array('align=center', $ceklist_KB );	
		$Koloms[] = array('align=left', $ket );										

		return $Koloms;
	}
	
	function cetakSK($xls= FALSE, $Mode=''){
	//global $Main;
		$periode= $_REQUEST['periode'];
		$bulan= $_REQUEST['bulan'];
		$tahun= $_REQUEST['tahun'];
		$triwulan= $_REQUEST['triwulan'];
		$ptgs= $_REQUEST['ptgs'];
		$ptgs2= $_REQUEST['ptgs_tahui'];
		$tgl_ind=TglInd($tgl);
		$qry1=mysql_fetch_array(mysql_query("SELECT nama FROM ref_petugas WHERE Id='$ptgs' and jns='1'"));
		$qry2=mysql_fetch_array(mysql_query("SELECT nama FROM ref_petugas WHERE Id='$ptgs2' and jns='1'"));
		$totalhari=cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); 
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
		$judul="<DIV ALIGN=CENTER>Keputusan Gubernur/Bupati/Walikota ...............<br>
				Nomor ....................<br><br>
				Tentang<br><br>
				Penetapan penggunaan Barang Milik Daerah<br>
				Provinsi/Kabupaten/Kota ....... Pada ....... SKPD .......<br><br>
				Gubernur/Bupati/Walikota ........";
		echo 
			"<html>".
			"<head>
				<title>Laporan PASIEN MASUK RAWAT INAP BERDASARKAN ASAL PASIEN DATANG</title>
				$css					
				$this->Cetak_OtherHTMLHead
			</head>
			<body>	
				<br><br><br><br><br>
				<form name='adminForm' id='adminForm' method='post' action=''>
				<div style='width:$this->Cetak_WIDTH'>
				<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
				<tr>
					<td valign=\"top\">".
						"<table style='width:100%' border=\"0\">
						<tr>
							<td class=\"judulcetak\">".strtoupper($judul)."</td>
						</tr>
						</table>"."<br>".
					//$this->setCetak_Header($Mode,$periode,$bulan,$tahun,$triwulan).//$this->Cetak_Header.//
						"<div id='cntTerimaDaftar' >
						<table style='width:100%;page-break-after: always;' border=\"0\">
						<tr>
							<td rowspan='2' valign='top' width='25%'>Menimbang</td>
							<td rowspan='2' valign='top' width='8%'>:</td>
							<td valign='top' width='5%'>a.</td>
							<td valign='top' width='62%' align='justify'>bahwa tanah dan/atau bangunan dan barang inventaris lainnya 
								milik pemerintahan daerah Provinsi/Kabupaten/Kota ..........
								yang berada pada SKPD ....... harus digunakan sesuai dengan
								 tugas pokok dan fungsi SKPD bersangkutan;
							</td>	
						</tr>
						<tr>
							<td valign='top' width='5%'>b.</td>
							<td valign='top' width='62%' align='justify'>bahwa status penggunaan barang milik daerah tersebut sesuai 
								peraturan Pemerintah Nomor 6 Tahun 2006 tentang 
								pengelolaan Barang Milik Negara/Daerah harus ditetapkan
								dengan Keputusan Kepala Daerah<br>
							</td>							
						</tr>
						<tr>
							<td rowspan='7' valign='top' width='25%'>Mengingat</td>
							<td rowspan='7' valign='top' width='8%'>:</td>
							<td valign='top' width='5%'>1.</td>
							<td valign='top' width='62%' align='justify'>Undang-undang Nomor 5 Tahun 1960 tentang Peraturan dasar 
								Pokok-pokok Agraria (Lembaga Negara Tahun 1960 Nomor 
								164, Tambahan Lembaran Negara Nomor 2043);
							</td>		
						</tr>
						<tr>
							<td valign='top' width='5%'>2.</td>
							<td valign='top' width='62%' align='justify'>Undang-undang Nomor 32 Tahun 2004 tentang Pemerintahan 
								Daerah (Lembaran Negara RI Tahun 1999 Nomor 125,
								Tambahan Lembaran Negara RI Nomor 4437);
						</tr>
						<tr>
							<td valign='top' width='5%'>3.</td>
							<td valign='top' width='62%' align='justify'>Undang-undang Nomor 33 tahun 2004 tentang Perimbangan 
								keuangan antara Pemerintah Pusat dan Pemerintahan DAerah 
								(Lembaga Negara RI Tahun 1999 Nomor 126, Tambahan 
								Lembaran Negara RI Nomor 4438);
							</td>		
						</tr>	
						<tr>
							<td valign='top' width='5%'>4.</td>
							<td valign='top' width='62%' align='justify'>Undang-undang Nomor 17 Tahun 2003 tentang Keuangan 
								Negara (Lembaga Negara RI Tahun 2003 Nomor 47,
								Tambahan Lembaran Negara RI Nomor 4286);
							</td>		
						</tr>		
						<tr>
							<td valign='top' width='5%'>5.</td>
							<td valign='top' width='62%' align='justify'>Undang-undang Nomor 1 Tahun 2004 entang Perbendaharaan 
								Negara (Lembaga Negara RI Tahun 2004 Nomor 5, Tambahan 
								Lembaga Negara RI Nomor 4355);
							</td>		
						</tr>									
						<tr>
							<td valign='top' width='5%'>6.</td>
							<td valign='top' width='62%' align='justify'>Peraturan Pemerintahan Nomor 6 Tahun 2006 tentang 
							Pengelolaan Barang Milik Negara/Daerah;
							</td>		
						</tr>	
						<tr>
							<td valign='top' width='5%'>7.</td>
							<td valign='top' width='62%' align='justify'>Peraturan Menteri Dalam Negeri omor ...... Tahun ...... 
							tentang Pedoman Teknis Pengelolaan Barang Daerah;
							</td>		
						</tr>									
						</table>
						</div><br><br><br>
						<div id='cntTerimaDaftar' >
						<table style='width:100%' border=\"0\">
						<tr>
							<td class=\"judulcetak\" ALIGN='center' colspan='3'>MEMUTUSKAN</td>
						</tr>
						<tr>
							<td width='25%'>Menetapkan</td>
							<td width='8%'>:</td>
						</tr>
						<tr>
							<td valign='top'>PERTAMA</td>
							<td valign='top'>:</td>
							<td align='justify'>Tanah dan/atau bangunan serta barang inventaris lainnya milik 
								Pemerintah Daerah Provinsi/Kabupaten/Kota   ...   yang 
								dipergunakan pada Dinas/Badan/Kantor ........ sebagaimana 
								tersebut pada Lampiran Keputusan ini.</td>
						</tr>
						<tr>
							<td valign='top'>KEDUA</td>
							<td valign='top'>:</td>
							<td align='justify'>Penggunaan tanah dan/atau bangunan serta barang inventaris 
								lainnya sebagaimana dimaksud pada Diktum PERTAMA harus 
								dipergunakan dalam rangka menunang tugas pokok dan fungsi 
								Dinas/Badan/Kantor</td>
						</tr>
						<tr>
							<td valign='top'>KETIGA</td>
							<td valign='top'>:</td>
							<td align='justify'>Apabila tanah dan/atau bangunan serta barang inventaris lainnya 
								tidak digunakan sesuai tugas pokok dan fungsi 
								Dinas/Badan/Kantor .......... wajib diserahkan kepada Kepala Daerah melalui pengelola;</td>
						</tr>
						<tr>
							<td valign='top'>KEEMPAT</td>
							<td valign='top'>:</td>
							<td align='justify'>Tanah dan/atau bangunan serta barang inventaris lainnya 
								sebagaimana Diktum PERTAMA dijadikan lampiran dalam Berita 
								Acara serah Terima jabatan dari pejabat yang lama kepada 
								pejabat yang baru</td>
						</tr>
						<tr>
							<td valign='top'>KELIMA</td>
							<td valign='top'>:</td>
							<td align='justify'>Keputusan ini mulai berlaku pada tanggal ditetapkan.</td>
						</tr>																														
						</table><br><br><br>
						<table style='width:100%' border=\"0\">
						<tr>
							<td width='60%'></td>
							<td width='40%' align='left'>Ditetapkan di &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<br>
											Pada tanggal  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<br><br>
											GUBERNUR/BUPATI/WALIKOTA<br><br><br><br><br>
											(....................................)</td>
						</tr>
						</table><br><br><br>
						Tembusan :<br>
						1. Yth. Ketua DPRD Provinsi/Kabupaten/Kota ...............;<br>
						2. Yth. Baswada Provinsi/Kabupaten/kota ...............;<br>
						3. Arsip.
						</div>
					</td>
				</tr>
				</table>
				</div>
				</form>	
			</body>	
			</html>";
		//}	
	
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
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

			case 'formUsulan':{				
				$fm = $this->setformUsulan();				
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
			
			case 'cetakSK':{				
				$fm = $this->cetakSK();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				$json=FALSE;											
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	/*function setFormBaru(){
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
	}*/
	
	function setFormUsulan(){
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
	
	/*function setForm($dt){	
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
	}*/
	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 700;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Form Usulan SK Gubernur Penggunaan';
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
			'bidang' => array('label'=>'BIDANG', 
							  'value'=> $bidang, 
							  'labelWidth'=>120, 
							  'type'=>'', 
							  'row_params'=>"style='height:24'" ),
								
			'unit' => array( 'label'=>'ASISTEN / OPD',
							 'value'=> $unit,  
							 'type'=>'' ,
							 'row_params'=>"style='height:24'"),
			
			'subunit' => array(  'label'=>'BIRO / UPTD/B', 
								 'value'=> $subunit,  
								 'type'=>'' , 
								 'row_params'=>"style='height:24'"),
								 
			'no_usulan' => array('label'=>'No Usulan', 
								 'value'=> '',  
								 'type'=>'text' , 
								 'row_params'=>"style='height:24'"),
								 
	  	 	'tgl_usulan' => array( 
					 'label'=>'Tanggal Usulan',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_usulan'], 'tgl_usulan', false,'tanggal bulan tahun (mis: 1 Januari 1998)')
			 			),
						
			'no_sk' => array('label'=>'No SK Gubernur', 
								 'value'=> '',  
								 'type'=>'text' , 
								 'row_params'=>"style='height:24'"),                                               											 								 
								 
		 	'tgl_sk' => array( 
					 'label'=>'Tanggal SK Gubernur',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_sk'], 'tgl_sk', false,'tanggal bulan tahun (mis: 1 Januari 1998)')
			 			),
						
			'tahun' => array(  'label'=>'Tahun Anggaran',
							   'value'=> $tahun,  
							   'type'=>'text' ),
							   
			'table' => array('label'=>'Keterangan',
									'labelWidth'=>100, 
									'value'=>
									"<table class='koptable' border='1' style='margin:4 0 0 0;width:100%'>
										<tbody>
											<tr>
												<th class='th01' width='50'>No.</th>
												<th class='th01' width='400'>Nama Barang</th >
												<th class='th01' width='150'>Jumlah Barang</th>
												<th class='th01' width='150'>Harga Perolehan</th>
											</tr>
											<tr class='row0'>
												<td class='GarisDaftar'>1</th>
												<td class='GarisDaftar'>Test</th>
												<td class='GarisDaftar'>5</th>
												<td class='GarisDaftar'>20000</th>
											</tr>
											<tr class='row0'>
												<td class='GarisDaftar'>1</th>
												<td class='GarisDaftar'>Test</th>
												<td class='GarisDaftar'>5</th>
												<td class='GarisDaftar'>20000</th>
											</tr>
											<tr class='row0'>
												<td class='GarisDaftar'>1</th>
												<td class='GarisDaftar'>Test</th>
												<td class='GarisDaftar'>5</th>
												<td class='GarisDaftar'>20000</th>
											</tr>
										</tbody>	
									</table>",
									'type'=>'merge' , 
							 ),								   
							   
			/*'brg' => array(  'label'=>'Nama Barang', 'value'=> $vbrg,  'type'=>'' ),
			'merk' => array(  'label'=>'Merk/Type/Ukuran/<br>Spesifikasi/Alamat', 'value'=> $vmerk, 'type'=>'', 'row_params'=>"valign='top'" ),
			'kondisi' => array( 'label'=>'Kondisi', 'value'=> $vkondisi, 'type'=>'' ),
			'harga' => array( 'label'=>'Harga Perolehan', 'value'=>$vharga, 'type'=>''  ),	
			'sk' => array( 'label'=>'SK Gubernur', 'value'=>'', 'type'=>'', 'pemisah'=>' '),
			'no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Nomor', 'value'=>$vnosk, 'type'=>''),
			'tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'value'=>'2014-02-01', 'type'=>'date'),
			'ket' => array( 'label'=>'Keterangan', 'value'=>$vket, 'type'=>'', 'row_params'=>"valign='top'"),*/
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
$Penggunaan_Det = new Penggunaan_DetObj();

?>