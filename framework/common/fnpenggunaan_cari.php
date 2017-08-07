<?php

class Penggunaan_CariObj extends DaftarObj2{
	var $Prefix = 'Penggunaan_Cari'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'v1_penggunaan'; //daftar
	var $TblName_Hapus = 'penggunaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('harga_perolehan');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 6,7,7);//berdasar mode
	var $FieldSum_Cp2 = array( 2, 2,2);	
	var $checkbox_rowspan = 7;
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
	var $fileNameExcel='Penggunaan.xls';
	var $Cetak_Judul = 'Penggunaan';
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
	var $FormName = 'PenggunaanKetetapan_form';	
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
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakSK(\"$Op\")","print_f2.png",'Cetak SK',"Cetak SK")."</td>".
			"<td>".genPanelIcon("javascript:CetakPenggunaan.cetakAll(\"$Op\")","print_f2.png",'Cetak Lampiran',"Cetak Lampiran",'','','','','style="width:75"')."</td>".
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
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']; //tgl buku
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$ipk = $_REQUEST['ipk'];
				
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

			"<input type=\"hidden\" name=\"ipk\" id=\"ipk\" value=\"".$ipk."\">";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		$ipk = $_REQUEST['ipk'];
		
		//Kondisi				
		$arrKondisi= array();
		$arrKondisi[] = " ref_idketetapan='$ipk'";				
		
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
				<script type='text/javascript' src='js/penggunaan.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penggunaan_det.js' language='JavaScript' ></script>
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
				<th class=\"th02\" colspan='2'>Usulan</th>				
				<!--<th class=\"th01\" rowspan='2' width='100px'>Tahun Anggaran</th>-->
				<th class=\"th01\" width='100' rowspan='2'>Tahun Anggaran</th>								
				<th class=\"th01\" width='200' rowspan='2'>Jumlah Barang</th>
				<th class=\"th01\" width='200' rowspan='2'>Harga Perolehan</th>
				<th class=\"th01\" width='300' rowspan='2'>SKPD</th>
				<th class=\"th01\" width='300' rowspan='2' style='min-width:100;'>Keterangan</th>
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
		 $c=mysql_fetch_array(mysql_query("select * from ref_skpd where c=".$isi['c']." and d=00 and e=00"));
		 $d=mysql_fetch_array(mysql_query("select * from ref_skpd where c=".$isi['c']." and d=".$isi['d']." and e=00"));
		 $e=mysql_fetch_array(mysql_query("select * from ref_skpd where c=".$isi['c']." and d=".$isi['d']." and e=".$isi['e'].""));
		 $skpd=$c['nm_skpd'].'/<br>'.$d['nm_skpd'].'/<br>'.$e['nm_skpd'];

		 $jml=mysql_fetch_array(mysql_query("select sum(jml_barang) as jml_barang, sum(jml_harga) as harga_perolehan from penggunaan_det where ref_idpenggunaan='".$isi['Id']."'"));		
		//tampil di kolom ---------------------------------------
		//$cekAsetTetap='';
		//$cek_bawahkap = '';
		$Koloms[] = array('align=right', $no.'.' );	
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);		
		$Koloms[] = array('align=left',$isi['no_usul']);
		$Koloms[] = array('align=center',TglInd($isi['tgl_usul']));
		$Koloms[] = array('align=center', $isi['tahun']);
		$Koloms[] = array('align=right', number_format($jml['jml_barang'],0,',','.') );
		$Koloms[] = array('align=right', number_format($jml['harga_perolehan'],2,',','.') );
		$Koloms[] = array('align=left', $skpd );		
		$Koloms[] = array('align=left', $isi['ket'] );						

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
			case 'formCariPenggunaan':{				
				$fm = $this->setFormCariPenggunaan();				
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
			
			case 'simpanPilihBarang':{
				$get= $this->simpanPilihBarang();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
		   }				
			
			case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			
	   	case 'windowsave':{

			$ref_pilihbarang = $_REQUEST['id'];
			$ipk = $_REQUEST['ipk'];
			$jml=mysql_fetch_array(mysql_query("select sum(jml_barang) as jml_barang, sum(jml_harga) as harga_perolehan from penggunaan_det where ref_idpenggunaan='".$ref_pilihbarang."'"));		
			$up=mysql_fetch_array(mysql_query("select * from penggunaan where Id='".$ref_pilihbarang."'"));		
			//update penggunaan
			$aqry = "UPDATE penggunaan_ketetapan
	        		 set "." jml_barang = '".$jml['jml_barang']."',
					 jml_harga = '".$jml['harga_perolehan']."',
					 tahun = '".$up['tahun']."',
					 sttemp='0'".
			 		 "WHERE Id='".$ipk."'";	$cek .= $aqry;
			$qry = mysql_query($aqry);	
			//update penggunaan
			$aqry2 = "UPDATE penggunaan
	        		 set "." sttemp_ketetapan = '1',
					 ref_idketetapan='$ipk'".
			 		 "WHERE Id='".$ref_pilihbarang."'";	$cek .= $aqry2;
			$qry = mysql_query($aqry2);	
			
			if($qry==TRUE){
				$aqry3 = "delete from penggunaan_ketetapan where sttemp=1";	$cek .= $aqry3;
				//$qry2 = mysql_query($aqry3);	
			}

		break;
	   }							
			
			case 'simpan':{
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
	
	function setFormCariPenggunaan(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		$dt['tgl_usulan']=date('Y-m-d');
		$dt['tgl_pembukuan']=date('Y-m-d');
		$dt['tgl_sk']=date('Y-m-d');
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		$dt['tgl_usulan']=date('Y-m-d');
		$dt['tgl_pembukuan']=date('Y-m-d');
		$dt['tgl_sk']=date('Y-m-d');
		//$this->form_idplh ='';
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
	
	/*function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = 'adminForm';				
		$this->form_width = 1000;
		$this->form_height = 300;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru - Form Usulan SK Gubernur Penggunaan';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit - Form Usulan SK Gubernur Penggunaan';			
			$nip = $dt['nip'];			
		}
		
		//items ----------------------
			
		$this->form_fields = array(								   
			'daftarcaripenggunaan' => array( 
						 'label'=>'',
						 'value'=>"<div id='daftarcaripenggunaan' style='height:5px'></div>", 
						 'type'=>'merge'
			 )					   							   
		);
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".PilihPenggunaan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm2();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
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
			);
		return $form;
	}	*/
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $no_usulan = $_REQUEST['no_usulan'];
	 $tgl_usulan = $_REQUEST['tgl_usulan'];
	 $tgl_pembukuan = $_REQUEST['tgl_pembukuan'];
	 $no_sk = $_REQUEST['no_sk'];
	 $tgl_sk = $_REQUEST['tgl_sk'];
	 $tahun_anggaran = $_REQUEST['tahun_anggaran'];	 	 	 	 
	 
			if($fmST == 0){ //input penggunaan
				if($err==''){ 
					$aqry = "INSERT into penggunaan (no_usul,tgl_usul,tgl_buku,no_sk,tgl_sk,tahun,UID,tgl_update)
							 "."values('$no_usulan','$tgl_usulan','$tgl_pembukuan','$no_sk','$tgl_sk','$tahun_anggaran','$uid','now()')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal menyimpan Penggunaan";
							
				}else{
					$err="Gagal menyimpan jurnal";
				}
			}elseif($fmST == 1){						
				if($err==''){
					 $kode_jurnal = explode(' ',$idplh);
					 $ka=$kode_jurnal[0];	
					 $kb=$kode_jurnal[1];
					 $kc=$kode_jurnal[2];	
					 $kd=$kode_jurnal[3];
					 $ke=$kode_jurnal[4];
					$aqry2 = "UPDATE ref_jurnal
			        		 set "." nm_account = '$nama_jurnal'".
					 		 "WHERE concat(ka,kb,kc,kd,ke)='".$ka.$kb.$kc.$kd.$ke."'";	$cek .= $aqry2;
					$qry = mysql_query($aqry2);
					if($qry==FALSE) $err="Gagal Edit jurnal";							
				}else{
					$err="Gagal menyimpan jurnal";
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
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = 'Penggunaan_Cari_Form';
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$ipk = $_REQUEST['ipk'];		
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		if($err==''){
			$FormContent = $this->genDaftarInitial2($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$params->tipe=1;
			
			$form = //centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						500,
						'Pilih Usulan Penggunaan',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='ipk' name='ipk' value='$ipk' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height,
						'',$params
					).
					"</form>";
			//);
			$content = $form;//$content = 'content';	
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
	
	function genDaftarInitial2($fmSKPD='', $fmUNIT='', $fmSUBUNIT='',$tahun_anggaran=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}	
	
}
$Penggunaan_Cari = new Penggunaan_CariObj();

?>