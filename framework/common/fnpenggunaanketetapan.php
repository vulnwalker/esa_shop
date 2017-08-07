<?php

class PenggunaanKetetapanObj extends DaftarObj2{
	var $Prefix = 'PenggunaanKetetapan'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'penggunaan_ketetapan'; //daftar
	var $TblName_Hapus = 'penggunaan_ketetapan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 6, 7,7);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1,1);	
	var $checkbox_rowspan = 2;
	var $totalCol = 7; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='Penggunaan.xls';
	var $Cetak_Judul = 'Penggunaan Ketetapan';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '14cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penggunaan';
	var $PageIcon = 'images/penerimaan_01.gif';
	var $pagePerHal= '25';
	var $FormName = 'PenggunaanKetetapanForm';	
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
			return 'Ketetapan';	
		//}else{
		//	return 'Pembukuan';	
		//}
		
		
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		return " <DIV ALIGN=CENTER>Penyusutan ";
	}
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batal()","delete_f2.png","Batal", 'Batal').
			"</td>";
	}
	
	function setMenuView(){	
		$Op=2;	
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakSK(\"$Op\")","print_f2.png",'Cetak SK',"Cetak SK")."</td>".
			//"<td>".genPanelIcon("javascript:Penggunaan_Det.cetakAll('2')","print_f2.png",'Lampiran',"Cetak Lampiran",'','','','','style="width:75"')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){	
		global $Main;
		global $HTTP_COOKIE_VARS;
		$Pg = $_REQUEST['Pg'];
		
		$Penggunaan_display = "";
		$PenggunaanKetetapan_display = '';
		switch ($Pg){
			case 'Penggunaan': $Penggunaan_display ="style='color:blue;'"; break;
			case 'PenggunaanKetetapan': $PenggunaanKetetapan_display ="style='color:blue;'"; break;
		}
		
			//index.php?Pg=09
			return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=Penggunaan\" title='Usulan Penggunaan' $Penggunaan_display>Usulan </a> |			
			<A href=\"pages.php?Pg=PenggunaanKetetapan\" title='Penggunaan Ketetapan' $PenggunaanKetetapan_display>Ketetapan</a>  
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
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
		
		/*$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']; //tgl buku
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];*/
		$thn_anggaran = $_REQUEST['thn_anggaran'];
		$no_sk = $_REQUEST['no_sk'];		
		$ipk = $_REQUEST['ipk'];
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');

				
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tanggal SK'),
			array('2','Tahun Anggaran')
		);
		
		
		//tampil -------------------------------
		$TampilOpt =
				"<table width=\"100%\" height=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
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
								<input type=\"checkbox\" name=\"AcsDsc1\" value=\"checked\">Desc. &nbsp;&nbsp;".*/ 
								"Tahun Anggaran &nbsp;<input type=\"text\" name=\"thn_anggaran\" id=\"thn_anggaran\" size=\"4\" value=\"$thn_anggaran\">&nbsp;&nbsp;
								No SK &nbsp;<input type=\"text\" name=\"no_sk\" id=\"no_sk\" size=\"15\" value=\"$no_sk\">&nbsp;&nbsp;".
								//"Baris per halaman &nbsp;<input type=\"text\" name=\"jmPerHal\" id=\"jmPerHal\" size=\"4\" value=\"\">&nbsp;&nbsp;".
								cmbArray('fmORDER1',$fmORDER1,$arrOrder,'-- Urutkan --','')."&nbsp;&nbsp;".					
								"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>menurun
								<input type=\"button\" onclick=\"".$this->Prefix.".refreshList(true)\" value=\"Tampilkan\">&nbsp;&nbsp;
							</div>
						</td>
					</tr>
					</tbody>
				</table>".
		
			
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
			
		$thn_anggaran = $_REQUEST['thn_anggaran'];
		$no_sk = $_REQUEST['no_sk'];
		
		//Kondisi				
		$arrKondisi= array();
		$arrKondisi[] = " sttemp='0'";	
		$arrKondisi[] = " stbatal='0'";		
		if(!empty($thn_anggaran)) $arrKondisi[] = "tahun='$thn_anggaran'";	
		if(!empty($no_sk)) $arrKondisi[] = "no_sk LIKE '%$no_sk%'";				
		
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		
		
		//order -------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " tgl_sk $Asc1 " ;break;
			case '2': $arrOrders[] = " tahun $Asc1 " ;break;
		}
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;			
		
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
				<script type='text/javascript' src='js/cetakpenggunaan.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penggunaan.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penggunaan_det.js' language='JavaScript' ></script>	
				<script type='text/javascript' src='js/penggunaan_cari.js' language='JavaScript' ></script>			
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
				<!--<th class=\"th01\" width='70' colspan='". ($cetak? "2": "3") ."'>Nomor</th>-->
				<th class=\"th01\" width='50' rowspan='2'>No.</th>
				$Checkbox	
				<th class=\"th02\" colspan='2'>SK Gubernur</th>				
				<!--<th class=\"th01\" rowspan='2' width='100px'>Tahun Anggaran</th>-->
				<th class=\"th01\" width='100' rowspan='2'>Tahun Anggaran</th>								
				<th class=\"th01\" width='200' rowspan='2'>Jumlah Barang</th>
				<th class=\"th01\" width='200' rowspan='2'>Harga Perolehan</th>".
				//<th class=\"th01\" width='300' rowspan='2'>SKPD</th>
				"<th class=\"th01\" width='300' rowspan='2' style='min-width:100;'>Keterangan</th>
				</tr>
				<tr>
				<th class=\"th01\" width='100'>No.</th>								
				<th class=\"th01\" width='100'>Tgl</th>				
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
		//$cekAsetTetap='';
		//$cek_bawahkap = '';
		$Koloms[] = array('align=right', $no.'.' );	
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);		
		$Koloms[] = array('align=center',$isi['no_sk']);
		$Koloms[] = array('align=center',TglInd($isi['tgl_sk']));
		$Koloms[] = array('align=center', $isi['tahun']);
		$Koloms[] = array('align=right', number_format($isi['jml_barang'],0,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_harga'],2,',','.'));
		//$Koloms[] = array('align=right', '-' );		
		$Koloms[] = array('align=right', $isi['ket'] );						

		return $Koloms;
	}
	
	function cetakSK($xls= FALSE, $Mode=''){
	//global $Main;
		/*$periode= $_REQUEST['periode'];
		$bulan= $_REQUEST['bulan'];
		$tahun= $_REQUEST['tahun'];
		$triwulan= $_REQUEST['triwulan'];
		$ptgs= $_REQUEST['ptgs'];
		$ptgs2= $_REQUEST['ptgs_tahui'];
		$tgl_ind=TglInd($tgl);
		$qry1=mysql_fetch_array(mysql_query("SELECT nama FROM ref_petugas WHERE Id='$ptgs' and jns='1'"));
		$qry2=mysql_fetch_array(mysql_query("SELECT nama FROM ref_petugas WHERE Id='$ptgs2' and jns='1'"));
		$totalhari=cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);*/ 
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
			
			case 'simpan':{
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
		   }
		   
			case 'batal':{
				$fm = $this->batal();
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
	
	function setFormBaru(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];	
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];

		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		$dt['tgl_sk']=date('Y-m-d');
		$dt['tgl_buku']=date('Y-m-d');		
		$dt['tahun']=date('Y');
		$query="INSERT into penggunaan_ketetapan (UID,tgl_update,sttemp)
							"."values('$uid',now(),1)"; $cek.=$query;
		$result=mysql_query($query);					
		$this->form_idplh =mysql_insert_id();
		$dt['ipk'] = $this->form_idplh;
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'].$cek, 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh =$cbid[0];
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$aqry = "select * from penggunaan_ketetapan where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['ipk'] = $this->form_idplh;
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	/*function genSumHal($Kondisi){
		
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
	}*/
	
	function genForm2($withForm=TRUE){	
		$form_name = $this->Prefix.'_form';	
				
		if($withForm){
			$params->tipe=1;
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,
					'',$params
					).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height
				);
			
			
		}
		
		
		/*$form = 
			centerPage(
				$form
			);*/
		return $form;
	}	

	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 900;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Ketetapan Penggunaan';
			$nip	 = '';
		}else{
			$this->form_caption = 'Ketetapan Penggunaan';			
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
			
		$this->form_fields = array(	
			/*'bidang' => array('label'=>'BIDANG', 
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
								 'row_params'=>"style='height:24'"),*/
	  	 	'tgl_pembukuan' => array( 
					 'label'=>'Tanggal pembukuan',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_buku'], 'tgl_pembukuan', false,'tanggal bulan tahun (mis: 1 Januari 1998)')
			 			),												
								 
			'no_sk' => array('label'=>'No SK', 
								 'value'=> $dt['no_sk'],  
								 'type'=>'text' , 
								 'row_params'=>"style='height:24'"),
								 
	  	 	'tgl_sk' => array( 
					 'label'=>'Tanggal SK Gubernur',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_sk'], 'tgl_sk', false,'tanggal bulan tahun (mis: 1 Januari 1998)')
			 			),
														 					
			'tahun' => array(  'label'=>'Tahun Anggaran',
							   'value'=> $dt['tahun'],  
							   'type'=>'text' ,
   							   'param'=> "style='width:50'",
							  ), 
							   
			'ket' => array(  'label'=>'Keterangan',
							   'value'=>"<table border='0' width='100%'>
							   				<tr>
											<td><textarea id='ket' name='ket' rows='2' cols='40'>".$dt['ket']."</textarea></td>
						   					<td align='right'></td>
											</tr>
										</table>	
										</div>",  
							   ),	
							   							   						   
			'daftarpenggunaan' => array( 
								 'label'=>'',
								 'value'=>"<div id='daftarpenggunaan' style='height:5px'></div>", 
								 'type'=>'merge'
					 )			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'>".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'>".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'>".
			"<input type=hidden id='ipk' name='ipk' value='".$dt['ipk']."'>".
			"<input type='button' value='Tambah Usulan' onClick='".$this->Prefix.".CariPenggunaan()' style='width:100px;height:35px;'>&nbsp".
			"<input type='button' value='Hapus Usulan'  onClick ='Penggunaan_Cari.Hapus()' style='width:100px;height:35px;'>&nbsp".			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' style='width:100px;height:35px;'>&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' style='width:100px;height:35px;'>";
		
		
		$form = $this->genForm2();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function batal(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb'];
	 $idplh = $cbid[0];

		if($err==''){ 
			$aqry = "UPDATE penggunaan_ketetapan
	        		 set "." stbatal = '1'".
			 		 "WHERE Id='".$idplh."'";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
			if($qry==TRUE) {
				$aqry2 = "UPDATE penggunaan
		        		 set "." ref_idketetapan = NULL ".
				 		 "WHERE ref_idketetapan='".$idplh."'";	$cek .= $aqry2;	
				$qry2 = mysql_query($aqry2);			
			}else{
				$err="Gagal Batal Penggunaan";	
			}
		}
					
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }		
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $tgl_pembukuan = $_REQUEST['tgl_pembukuan'];
	 $no_sk = $_REQUEST['no_sk'];
	 $tgl_sk = $_REQUEST['tgl_sk'];
	 $tahun_anggaran = $_REQUEST['tahun'];	 
	 $keterangan = $_REQUEST['ket'];	 	 	 
	 //query mendapatkan jml barang & perolehan
     $jml=mysql_fetch_array(mysql_query("select sum(jml_barang) as jml_barang, sum(harga_perolehan) as harga_perolehan from v1_penggunaan where ref_idketetapan='".$idplh."'"));		
	 //cek validasi
	 if( $err=='' && $tgl_pembukuan =='' ) $err= 'Tanggal Pembukuan belum diisi !!';
	 if( $err=='' && $no_sk =='' ) $err= 'No SK belum diisi !!';
	 if( $err=='' && $tgl_sk =='' ) $err= 'Tanggal SK belum diisi !!';
	 if( $err=='' && $tahun_anggaran =='' ) $err= 'Tahun Anggaran SK belum diisi !!';
	 if( $err=='' && $keterangan =='' ) $err= 'Keterangan belum diisi !!';	 	 	 	 
	 
			if($fmST == 0){ //input penggunaan ketetapan
				if($err==''){ 
					$aqry = "UPDATE penggunaan_ketetapan
			        		 set "." tgl_buku = '$tgl_pembukuan',
							 no_sk = '$no_sk',
							 tgl_sk = '$tgl_sk',
							 tahun = '$tahun_anggaran',
							 jml_barang = '".$jml['jml_barang']."',
						 	 jml_harga = '".$jml['harga_perolehan']."',
							 UID = '$uid',
							 tgl_update = now(),
							 ket = '$keterangan',
							 sttemp='0'".
					 		 "WHERE Id='".$idplh."'";	$cek .= $aqry;	
					$qry = mysql_query($aqry);

					$aqry2 = "UPDATE penggunaan
			        		 set "." sttemp_ketetapan = '0'".
					 		 "WHERE ref_idketetapan='".$idplh."'";	$cek .= $aqry2;
					$qry2 = mysql_query($aqry2);	

					if($qry2==TRUE) {
						$aqry3 = "Delete from penggunaan_ketetapan where sttemp=1";	$cek .= $aqry2;	
						$qry3 = mysql_query($aqry3);	
					}else{
						$err="Gagal menyimpan Penggunaan";	
					}
				}	
			}elseif($fmST == 1){						
				if($err==''){
					$aqry = "UPDATE penggunaan_ketetapan
			        		 set "." tgl_buku = '$tgl_pembukuan',
							 no_sk = '$no_sk',
							 tgl_sk = '$tgl_sk',
							 tahun = '$tahun_anggaran',
							 jml_barang = '".$jml['jml_barang']."',
						 	 jml_harga = '".$jml['harga_perolehan']."',
							 UID = '$uid',
							 tgl_update = now(),
							 ket = '$keterangan'".
					 		 "WHERE Id='".$idplh."'";	$cek .= $aqry;	
					$qry = mysql_query($aqry);

					$aqry2 = "UPDATE penggunaan
			        		 set "." sttemp_ketetapan = '0'".
					 		 "WHERE ref_idketetapan='".$idplh."'";	$cek .= $aqry2;
					$qry2 = mysql_query($aqry2);	

					if($qry==FALSE) {
						$err="Gagal Edit Penggunaan Ketetapanb";							
					}else{
						//$err="Gagal menyimpan jurnal";
					}
				}	
			}else{
			if($err==''){ 
						$kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
						$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga)
						"."values('$f','$g','$h','$i','$j','$tahun_anggaran','$harga')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);
						 
				}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }		
	
}
$PenggunaanKetetapan = new PenggunaanKetetapanObj();

?>