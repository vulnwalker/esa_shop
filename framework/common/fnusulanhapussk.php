<?php
/***
	salinan dari fnuseraktivitas.php
	requirement:
	 - daftarobj2 di DaftarObj2.php
	 - global variable di vars.php
	 - library fungsi di fnfile.php
	 - connect db  di config.php
	
	Name 		:Class UsulanHapusskObj 
	Properti	:$Prefix s/d $Cetak_OtherHTMLHead
***/

class UsulanHapusskObj  extends DaftarObj2{	
	var $Prefix = 'UsulanHapussk';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'penghapusan_usulsk'; //daftar
	var $TblName_Hapus = 'penghapusan_usulsk';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penghapusan';
	var $PageIcon = 'images/penghapusan_ico.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'Daftar Usulan SK Bupati';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	function setPage_HeaderOther(){
	global $Main;
	$menuhapussebagian = $Main->MODUL_PENGAPUSAN_SEBAGIAN ?"<A href=\"index.php?Pg=09&SPg=06\" title='Daftar Penghapusan Sebagian'>PENGHAPUSAN SEBAGIAN</a>":"";
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan'>USULAN</a> |
			<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan'>PENGECEKAN</a>  |  
			<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK Kepala Daerah' style='color:blue'>USULAN SK</a> |
			<A href=\"index.php?Pg=09&SPg=01\" title='Daftar Penghapusan'>PENGHAPUSAN</a> | 
			$menuhapussebagian
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}
	
	
	function setTitle(){
		return 'Usulan SK Bupati';
	}
	function setMenuEdit(){
		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Usulan SK Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Usulan SK Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus Usulan SK')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".draftUsulSK()","new_f2.png","Draft SK",'Draft Usulan SK')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".showKepsekda()","new_f2.png","KEPSEKDA",'Keputusan Sekretariat Daerah')."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Barusk()","new_f2.png","SK BUPATI",'Entry No. SK Bupati','','','','',"style='width:80'")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batalsk()","delete_f2.png","Batal SK", 'Batalkan SK')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Batalsk()","delete_f2.png","Batal KEPSEKDA", 'Batalkan KEPSEKDA', '','','','',"style='width:100'")."</td>".
			""
			
			
			;
	}
	
	function getIdBA(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$idusulSK = $_REQUEST['idsk'];
		$aqry = "select * from penghapusan_usulsk_det where Id='$idusulSK'";
		$qry = mysql_query($aqry);
		//get id usulan
		$ids=array();
		while($isi = mysql_fetch_array($qry)){
			$ids[] = $isi['ref_idusulan'];
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$ids);	
	}
	
	function genKepSekda($xls= FALSE, $Mode=''){ 
		global $Main;
		
		
		$id = $_REQUEST['id'];
		$xls = $_REQUEST['xls'];
		if($xls){
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=kepsekda.doc");
			header("Pragma: no-cache");
			header("Expires: 0");
			$menu='';
		}else{
			$menu = 			
				"<div id='divmenuprint' style='background-color: EBEBEB;border-bottom: 1px solid gray;'>
				<table width='100%' style='font-family:arial;'><tbody><tr>
				<td style='font-size: 14;font-weight: bold;'>&nbsp;Keputusan Sekretaris Daerah</td>
				<td align='right'>
				<input type='hidden' id='id' name='id' value='$id'>				
				<input id='btprint' type='button' value='Export Doc' onclick='$this->Prefix.expDocKepSekDa()' style=''>
				<input id='btprint' type='button' value='Cetak' onclick=\"document.getElementById('divmenuprint').style.display='none';window.print();document.getElementById('divmenuprint').style.display='block';\" style=''>
				</td></tr></tbody></table>
				</div>";	
		}
		
		//get data
		$idusul = $_REQUEST['id'];
		$aqry = "select * from penghapusan_usul where id='$idusul'";
		$usul = mysql_fetch_array(mysql_query($aqry));
				
		$biro = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$usul['c']."' and d='".$usul['d']."' and e='".$usul['e']."' and e1='".$usul['e1']."' "));
		$nmbiro = strtoupper( $biro['nm_skpd'] );//"BADAN PERENCANAAN PEMBANGUNAN DAERAH  PROVINSI JAWA BARAT";
		$nousul = $usul['no_usulan'];//'028/2091/KEPEGUM';
		$tglusul = JuyTgl1( $usul['tgl_usul']);//'22 November 2012';
		$noba = $usul['no_ba'];//'028/38-BA/Pbd';
		$tglba = JuyTgl1( $usul['tgl_ba'] );//'22 Februari 2013';
		
		//get jml perolehan
		$aqry = "select sum(harga) as sumjml from v1_penghapusan_usul_det_bi where Id='$idusul' and (sesi='' or sesi is null) and tindak_lanjut<>1  and status<>2 ";
		$getJml = mysql_fetch_array(mysql_query($aqry));
		$jml = number_format($getJml['sumjml'],0,'.',',').',-';//'456.475.300,-';
		$jmlbilang = bilang($getJml['sumjml']);//'empat ratus lima puluh enam juta empat ratus tujuh puluh lima ribu tiga ratus rupiah';
				
		$data[1] = "bahwa ".$Main->NM_WILAYAH2." memiliki/menguasai barang milik  Daerah berupa barang inventaris yang dikelola oleh ";
		$data[2] =  $biro['nm_skpd']." ".$Main->NM_WILAYAH2."," ;
		$data[3] = " dan saat ini kondisinya rusak berat serta tidak efisien untuk digunakan dalam kegiatan kantor, sehingga perlu dilakukan penghapusan barang milik daerah; ";
		$data[4] = "bahwa berdasarkan ketentuan Pasal 54 ayat (3) Peraturan Menteri Dalam Negeri 17 Tahun 2007 tentang Pedoman Teknis Pengelolaan Barang Daerah dan Pasal 41  Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2006 jo. Nomor 8 Tahun 2010 tentang Pengelolaan Barang Milik Daerah, penghapusan barang inventaris oleh Pemerintah Provinsi Jawa Barat dari Daftar Barang Pengguna dan/atau Kuasa Pengguna, dilaksanakan oleh Pengelola ditindaklanjuti  persetujuan Gubernur Jawa Barat;";
		$data[5] = "bahwa untuk kelancaran dan akuntabilitas penghapusan barang milik Daerah sebagaimana dimaksud pada  pertimbangan huruf a dan b, perlu ditetapkan Keputusan Sekretaris Daerah Provinsi Jawa Barat tentang  Penghapusan Barang Milik Daerah dari Daftar Pengguna Badan Perencanaan  Pembangunan Daerah Provinsi Jawa Barat;";
		$data[6] = "Undang-Undang Nomor 11 Tahun 1950 tentang Pembentukan Provinsi Jawa Barat (Berita Negara Republik Indonesia tanggal 4 Juli 1950) jo. Undang-Undang Nomor 20 Tahun 1950 tentang Pemerintahan Jakarta Raya (Lembaran Negara Republik Indonesia Tahun 1950 Nomor 31, Tambahan Lembaran Negara Republik Indonesia Nomor 15) sebagaimana telah diubah beberapa kali, terakhir dengan Undang-Undang Nomor 29 Tahun 2007 tentang Pemerintahan Provinsi Daerah Khusus Ibukota Jakarta Sebagai Ibukota Negara Kesatuan Republik Indonesia (Lembaran Negara Republik Indonesia Tahun 2007 Nomor 93, Tambahan Lembaran Negara Republik Indonesia Nomor 4744) dan Undang-Undang Nomor 23 Tahun 2000 tentang Pembentukan Provinsi Banten (Lembaran Negara Republik Indonesia Tahun 2000 Nomor 182, Tambahan Lembaran Negara Republik Indonesia Nomor 4010);";
		$data[7] = "Undang-Undang Nomor 32 Tahun 2004 tentang Pemerintahan Daerah (Lembaran Negara Republik Indonesia Tahun 2004 Nomor 125, Tambahan Lembaran Negara Republik Indonesia Nomor 4437) sebagaimana telah diubah beberapa kali, terakhir dengan           Undang-Undang Nomor 12 Tahun 2008 tentang Perubahan Kedua atas Undang-Undang Nomor 32 Tahun 2004 tentang Pemerintahan Daerah (Lembaran Negara Republik Indonesia Tahun 2008 Nomor 9, Tambahan Lembaran Negara Republik Indonesia Nomor 4844);";
		$data[8] = "Peraturan Pemerintah Nomor 6 Tahun 2006 tentang Pengelolaan Barang Milik Negara/Daerah (Lembaran Negara Republik Indonesia Tahun 2006 Nomor 20, Tambahan Lembaran Negara Republik Indonesia Nomor 4609) sebagaimana telah diubah dengan Peraturan Pemerintah Nomor 38 Tahun 2008 tentang Perubahan Atas Peraturan Pemerintah Nomor 6 Tahun 2006 tentang Pengelolaan Barang Milik Negara/Daerah (Lembaran Negara Republik Indonesia Tahun 2008 Nomor 78, Tambahan Lembaran Negara Republik Indonesia Nomor 4855);";
		$data[9] = "Peraturan Pemerintah Nomor 38 Tahun 2007 tentang Pembagian Urusan Pemerintahan antara Pemerintah, Pemerintahan Daerah Provinsi dan Pemerintahan Daerah Kabupaten/Kota (Lembaran Negara Republik Indonesia Tahun 2007 Nomor 82, Tambahan Lembaran Negara Republik Indonesia Nomor 4737);";
		$data[10]= "Peraturan Menteri Dalam Negeri Nomor 17 Tahun 2007 tentang Pedoman Teknis Pengelolaan Barang Milik Daerah;";
		$data[11]= "Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Lembaran Daerah Provinsi Jawa Barat Tahun 2008 Nomor 5 Seri E, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 42) sebagaimana telah diubah dengan Peraturan Daerah Provinsi Jawa Barat Nomor 8 Tahun 2010 tentang Perubahan atas Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Lembaran Daerah Provinsi Jawa Barat Tahun 2010 Nomor 8 Seri E, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 74);";
		$data[12]= "Peraturan Daerah Provinsi Jawa Barat Nomor 10 Tahun 2008 tentang Urusan Pemerintahan Provinsi Jawa Barat (Lembaran Daerah Provinsi Jawa Barat Tahun 2008 Nomor 9 Seri D, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 49);";
		$data[13]= "Peraturan Gubernur Jawa Barat Nomor 14 Tahun 2010 tentang Petunjuk Pelaksanaan Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Daerah (Berita Daerah Provinsi Jawa Barat  Tahun 2010 Nomor 14 Seri E) sebagaimana telah diubah dengan Peraturan Gubernur Jawa Barat Nomor 64 Tahun 2011 tentang Perubahan atas Peraturan Gubernur Jawa Barat Nomor 14 Tahun 2010 tentang Petunjuk Pelaksanaan Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Berita Daerah Provinsi Jawa Barat Tahun 2011 Nomor 63 Seri E);";
		$data[14]= "Keputusan Gubernur Jawa Barat Nomor ................... tentang Persetujuan Penghapusan Barang Inventaris Alat Perlengkapan Kantor;";
		
		$data[15]= "Surat Kepala ".$biro['nm_skpd']." Provinsi Jawa Barat Nomor $nousul tanggal $tglusul Hal Permohonan Penghapusan Barang Inventaris.";
		$data[16]= "Berita Acara Hasil Rapat Panitia Penghapusan Barang Inventaris dari Daftar Inventaris Barang Milik Pemerintah Provinsi Jawa Barat Nomor $noba tanggal $tglba.";
		$data[17]= "KEPUTUSAN SEKRETARIS DAERAH TENTANG PENGHAPUSAN BARANG MILIK DAERAH DARI DAFTAR PENGGUNA $nmbiro PROVINSI JAWA BARAT.";
		$data[18]= "Menghapus barang milik Daerah dari Daftar Pengguna ".$biro['nm_skpd']." Provinsi Jawa Barat dengan Nilai Perolehan Rp.$jml (".$jmlbilang." rupiah) sebagaimana tercantum dalam Lampiran, sebagai bagian yang tidak terpisahkan dari Keputusan ini. ";
		$data[19]= "Dengan dilaksanakannya penghapusan barang Milik Daerah dari Daftar Pengguna Badan Perencanaan Pembangunan Daerah Provinsi       Jawa Barat, maka barang Daerah sebagaimana dimaksud pada Diktum KESATU, dikeluarkan dari Buku Inventaris Badan Perencanaan Pembangunan Daerah Provinsi Jawa Barat. ";
		$data[20]= "Keputusan ini mulai berlaku pada tanggal ditetapkan.";
		
		$ttd = "<br><table style='font-family:arial;'>
					<tr><td style='width:4cm'></td><td>
						Ditetapkan di Bandung<br>
						pada tanggal<br>
						
					</td></tr>
					<tr><td></td><td align='center'>
						SEKRETARIS DAERAH PROVINSI<br>
						JAWA BARAT,
						<br><br><br><br><br><br>
						
						Ir. WAWAN RIDWAN, MMA<br>
						Pembina Utama Madya<br>
						NIP. 19561224 198203 1 012
					</td></tr>
				</table>";
		
		$title = 
			"KEPUTUSAN SEKRETARIS DAERAH PROVINSI JAWA BARAT<br>".
			"NOMOR : $nomor <br><br>".			
			"TENTANG<br>".
 			"PENGHAPUSAN BARANG-BARANG MILIK DAERAH DARI DAFTAR PENGGUNA<br>".
			$nmbiro." PROVINSI JAWA BARAT<br><br>".
			"SEKRETARIS DAERAH PROVINSI JAWA BARAT,".
			"<br><br>";
		$isi=
			"<div contenteditable='true' >".
			
			"<table class=\"rangkacetak\" style='width:18.7cm; font-family:arial;margin-top: 0cm;margin-left: 0cm;'>
			<tr><td>".
				"<table style='width:100%'> <tr>
				<td style='padding: 4px 20px 16px;'> <img src=images/logo-jabar_putih.jpg> </td>
				<td align='center' style='font-family:arial;'> 
					<span style='font-size:16pt;'>PEMERINTAH  PROVINSI  JAWA  BARAT<br>
					SEKRETARIAT   DAERAH<br></span>
					Jalan Diponegoro No. 22 Telepon : (022) 4232448 - 4233347 - 4230963<br>
					<span style='font-size:9pt;font-weight:bold'>Faksimili : (022) 4203450, Website : www.jabarprov.go.id email info@jabarprov.go.id</span><br>
					BANDUNG- 40115<br><br>

				</td>
				</tr></table> ".
				"<div style='border-bottom-color: #000000;
				    border-bottom-style: solid;
				    border-bottom-width: 1px;
				    border-top-color: #000000;
				    border-top-style: solid;
				    border-top-width: 3px;
				    height: 2px;'></div><br>".
			"</td></tr>
			<tr valign='top' ><td style='padding: 0 0 0 1cm'>".	
			
						
			"<div style='text-align:center'>				
				<span id='title' name='title' >
				{$title}
				</span>
			</div>".
			"<table style='text-align: justify;font-family:arial;'>".
				"<tr valign='top'><td style='width:3.5cm'>Menimbang</td><td style='width:0.4cm' align='left'>:</td><td style='width:0.7cm' align='left'>a.</td><td > {$data[1]} {$data[2]} {$data[3]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>b.</td><td > {$data[4]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>c.</td><td > {$data[5]}</td></tr>".
				
				"<tr valign='top'><td >Mengingat</td><td align='left'>:</td><td align='left'>1.</td><td > {$data[6]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>2.</td><td > {$data[7]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>3.</td><td > {$data[8]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>4.</td><td > {$data[9]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>5.</td><td > {$data[10]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>6.</td><td > {$data[11]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>7.</td><td > {$data[12]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>8.</td><td > {$data[13]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>9.</td><td > {$data[14]}</td></tr>".
				
				"<tr valign='top'><td >Memperhatikan</td><td align='left'>:</td><td align='left'>1.</td><td > {$data[15]}</td></tr>".
				"<tr valign='top'><td ></td><td align='left'></td><td align='left'>2.</td><td > {$data[16]}</td></tr>".
				
				"<tr valign='' style='height:40'><td ></td><td align='left'>:</td><td colspan=2 align=center> MEMUTUSKAN</td></tr>".
				"<tr valign='top'><td >Menetapkan</td><td align='left'>:</td><td colspan=2> {$data[17]}</td></tr>".
				"<tr valign='top'><td >KESATU</td><td align='left'>:</td><td colspan=2> {$data[18]}</td></tr>".
				"<tr valign='top'><td >KEDUA</td><td align='left'>:</td><td colspan=2> {$data[19]}</td></tr>".
				"<tr valign='top'><td >KETIGA</td><td align='left'>:</td><td colspan=2> {$data[20]}</td></tr>".
				
				"<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='center'></td><td> $ttd</td></tr>".
					
			"</table>".
			"</td></tr>".
			"</table>".
			"</div>".
			"";	
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css".	
					
					"<script type='text/javascript' src='js/ajaxc2.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/global.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='dialog/dialog.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/base.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/daftarobj.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				    "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
				    "<script type='text/javascript' src='js/usulanhapusbacari.js' language='JavaScript' ></script>".		
					"<script type='text/javascript' src='js/usulanhapusskdet.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
					"$this->Cetak_OtherHTMLHead
				</head>".
			"<body style='margin:0' >
			<form name='adminForm' id='adminForm' method='post' action=''>	
			$menu	
			$isi
			</form>
			</body>
			</html>";
			
		
	}
	
	function genKepGub($xls= FALSE, $Mode=''){ 
		global $Main;
		$nomor = '12';
		$data = array();
		$data[26] = 'bahwa  Pemerintah  Provinsi  Jawa Barat memiliki/menguasai   barang inventaris berupa alat perlengkapan kantor, yang dikelola oleh ';
		
		//$data[1] = 'Dinas Pendapatan Provinsi Jawa Barat, Balai Pelabuhan Pantai Subang Muara Ciasem pada Dinas Perikan dan Kelautan Provinsi Jawa Barat dan Balai Pengembangan Benih Ikan Air Tawar Purwakarta pada Dinas Perikanan dan Kelautan Provinsi          Jawa Barat';
		$data[2] = ', yang saat ini kondisinya rusak dan rusak berat, serta tidak efisien untuk digunakan dalam kegiatan kantor, sehingga perlu dilakukan penghapusan barang milik Daerah; ';
		$data[3] = 'bahwa   untuk  tertib  administrasi penghapusan barang inventaris sebagaimana dimaksud pada pertimbangan huruf a, perlu ditetapkan Keputusan Gubernur Jawa Barat tentang Persetujuan Penghapusan Barang Inventaris Alat Perlengkapan Kantor;';
		$data[4] = 'Undang-Undang Nomor 11 Tahun 1950  tentang  Pembentukan Provinsi Jawa Barat (Berita Negara Republik Indonesia tanggal 4 Juli 1950) Jo. Undang-Undang Nomor 20 Tahun 1950  tentang  Pemerintahan Jakarta Raya (Lembaran Negara Republik Indonesia Tahun 1950 Nomor 31, Tambahan Lembaran Negara Republik Indonesia Nomor 15) sebagaimana telah diubah beberapa kali, terakhir dengan Undang-Undang Nomor 29 Tahun 2007  tentang  Pemerintahan Provinsi Daerah Khusus Ibukota Jakarta sebagai Ibukota Negara Kesatuan Republik Indonesia (Lembaran Negara Republik Indonesia Tahun 2007 Nomor 93, Tambahan Lembaran Negara Republik Indonesia Nomor 4744);';
		$data[5] = 'Undang-Undang Nomor 23 Tahun 2000  tentang  Pembentukan Provinsi Banten (Lembaran Negara Republik Indonesia Tahun 2000 Nomor 182, Tambahan Lembaran Negara Republik Indonesia Nomor 4010);';
		$data[6] = 'Undang-Undang Nomor 32 Tahun 2004  tentang  Pemerintahan Daerah (Lembaran Negara Republik Indonesia Tahun 2004 Nomor 125, Tambahan Lembaran Negara Republik Indonesia Nomor 4437) sebagaimana telah diubah beberapa kali, terakhir dengan Undang-Undang Nomor 12 Tahun  2008   tentang   Perubahan    Kedua    atas Undang-Undang Nomor 32 Tahun 2004 tentang  Pemerintahan Daerah  (Lembaran Negara Republik Indonesia Tahun 2008 Nomor 59, Tambahan Lembaran Negara Republik Indonesia Nomor  4844);';
		$data[7] = 'Peraturan Pemerintah Nomor 6 Tahun 2006 tentang  Pengelolaan Barang Milik Negara/Daerah (Lembaran Negara Republik Indonesia Tahun 2006 Nomor 20, Tambahan Lembaran Negara Republik Indonesia Nomor 4609) sebagaimana telah diubah dengan Peraturan Pemerintah Nomor 38 Tahun 2008 tentang Perubahan atas Peraturan Pemerintah Nomor 6 Tahun 2006 tentang  Pengelolaan Barang Milik Negara/Daerah (Lembaran Negara Republik Indonesia Tahun 2008 Nomor 78, Tambahan Lembaran Negara Republik Indonesia Nomor 4855);';
		$data[8] = 'Peraturan Pemerintah Nomor 38 Tahun 2007 tentang Pembagian Urusan Pemerintahan antara Pemerintah, Pemerintahan Daerah Provinsi dan Pemerintahan Daerah Kabupaten/Kota (Lembaran Negara Republik Indonesia Tahun 2007 Nomor 82, Tambahan Lembaran Negara Republik Indonesia Nomor 4737);';
		$data[9] = 'Peraturan Menteri Dalam Negeri Nomor 17 Tahun 2007 tentang Pedoman Teknis Pengelolaan Barang Milik Daerah;  Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Lembaran Daerah Provinsi Jawa Barat Tahun 2008 Nomor 5 Seri E, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 42) sebagaimana telah diubah dengan Peraturan Daerah Provinsi Jawa Barat Nomor 8 Tahun 2010 tentang Perubahan atas Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Lembaran Daerah Provinsi Jawa Barat Tahun 2010 Nomor 8 Seri E, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 74);';
		$data[10]= 'Peraturan Daerah Provinsi Jawa Barat Nomor 10 Tahun 2008 tentang Urusan Pemerintahan Provinsi Jawa Barat (Lembaran Daerah Provinsi Jawa Barat Tahun 2008 Nomor 9 Seri D, Tambahan Lembaran Daerah Provinsi Jawa Barat Nomor 46);';
		$data[11]= 'Peraturan  Daerah Provinsi Jawa Barat Nomor 12 Tahun 2008 tentang Pokok-pokok Pengelolaan Keuangan Daerah (Lembaran Daerah Provinsi Jawa Barat Tahun 2008 Nomor 11 Seri E, Tambahan  Lembaran Daerah Provinsi Jawa Barat Nomor 48).';
		$data[12]= 'Peraturan Gubernur Jawa Barat Nomor 14 Tahun 2010 tentang Petunjuk Pelaksanaan Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Berita Daerah Provinsi Jawa Barat Tahun 2010 Nomor 14 Seri E) sebagaimana telah diubah dengan Peraturan Gubernur Jawa Barat Nomor 64 Tahun 2011 tentang Perubahan atas Peraturan Gubernur Jawa Barat Nomor 14 Tahun 2010 tentang Petunjuk Pelaksanaan Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2008 tentang Pengelolaan Barang Milik Daerah (Berita Daerah Provinsi Jawa Barat Tahun 2011 Nomor 63 Seri E);';
		
		$data[13]='Surat Kepala Badan Pendidikan dan Pelatihan Daerah Provinsi Jawa Barat Nomor 027/2400-Um/Bandiklatda, tanggal 29 Oktober 2012 Hal Permohonan Penghapusan Barang Inventaris Tahun 2012 dan Berita Acara Hasil Rapat Panitia Penghapusan Barang Inventaris dari Daftar Inventaris Barang Milik Pemerintah Provinsi Jawa Barat Nomor 028/38-BA/Pbd tanggal 22 Februari 2013.';
		$data[14]='Surat Kepala Dinas Perikanan dan Kelautan Provinsi Jawa Barat (Balai Pelabuhan Perikanan Pantai) BPPP Subang Ciasem Nomor 028/4609B/Umum Tanggal  Mei 2013 Perihal Permohonan Penghapusan Barang Aset dan Berita Acara Hasil Rapat Panitia Penghapusan Barang Inventaris dari Daftar Inventaris Barang Milik Pemerintah Provinsi Jawa Barat Nomor 028/39-BA/Pbd tanggal 21 Juni  2013.';
		
		$data[15]='KEPUTUSAN  GUBERNUR JAWA BARAT TENTANG PERSETUJUAN PENGHAPUSAN BARANG INVENTARIS ALAT PERLENGKAPAN   KANTOR.';
		$data[16]='Menghapus  barang   inventaris    milik/dikuasai   Pemerintah Provinsi Jawa Barat  berupa alat perlengkapan kantor yang terdapat di lingkungan Organisasi Perangkat Daerah sebagaimana tercantum dalam Lampiran, sebagai bagian yang tidak terpisahkan dari Keputusan ini,  meliputi:';
		$data[17]='Barang    inventaris    sebagaimana     dimaksud     pada    Diktum   KESATU dikeluarkan dari Buku Induk Inventaris Aset Pemerintah Provinsi Jawa Barat dan Buku Induk Inventaris Aset :';
		$daftar1 = 'daftar1';
		
		$data[18]='Penghapusan  barang  inventaris  sebagaimana  dimaksud pada  Diktum KESATU dilaksanakan dengan ketentuan sebagai berikut:';
		$daftar2 = 'daftar2';
		
		$data[19]='barang yang memiliki nilai ekonomis, dijual sesuai ketentuan peraturan perundang-undangan;';
		$data[20]='barang yang masih dapat dimanfaatkan, dihibahkan kepada Yayasan, Pondok Pesantren dan/atau Penyelenggara Pemerintahan, sesuai ketentuan peraturan perundang-undangan; dan';
		$data[21]='barang yang rusak berat/tidak dapat dimanfaatkan dan/atau tidak memenuhi nilai ekonomis, dimusnahkan sesuai ketentuan peraturan perundang-undangan';
		$data[22]='Biro Pengelolaan Barang Daerah Sekretaris Daerah Provinsi Jawa Barat melaporkan pelaksanaan penghapusan sebagaimana dimaksud pada Diktum KESATU kepada Gubernur Jawa Barat melalui Sekretaris Daerah Provinsi Jawa Barat.';
		$data[23]='Keputusan ini mulai berlaku pada tanggal ditetapkan.';
		
		
		$nomor= '';
		$data[28] = "KEPUTUSAN GUBERNUR JAWA BARAT<br>".
					"NOMOR : $nomor <br>".
					"<br><span>TENTANG </span><br>".
					"PERSETUJUAN PENGHAPUSAN BARANG INVENTARIS  <br>".
					"ALAT PERLENGKAPAN KANTOR   <br>".
					"GUBERNUR JAWA BARAT <br>".
					"<br>";
		$ttd = "<table style='font-family:arial;'>
					<tr><td style='width:5cm'></td><td>
						Ditetapkan di Bandung<br>
						pada tanggal<br>
						GUBERNUR  JAWA BARAT,
						<br><br><br><br><br><br>
					</td></tr>
					<tr><Td></td><td align='center'>AHMAD HERYAWAN </td></tr>
				</table>";
		$data[24] = 'Menimbang';
		$data[25] = ':';
		$data[27] = 'b.';
		
		$id= $_REQUEST['id'];
		//ambil data jika ada 
		$aqry = "select * from penghapusan_usulsk where Id='$id'";
		$qry = mysql_query($aqry);
		while ($isi=mysql_fetch_array($qry)){
			if ($isi['draft_isi_surat'] != NULL) $data = json_decode($isi['draft_isi_surat'],TRUE);			
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		//ambil data biro , opd
		$d1='';
		$aqry = "select * from penghapusan_usulsk_det where Id='$id' ";
		$qrdetsk = mysql_query($aqry);
		$daftar1 =''; $daftar2='';
		$jmldata = mysql_num_rows($qrdetsk); $i2=0;		
		while($detsk = mysql_fetch_array($qrdetsk)){
			//ambil usulan hapus
			$aqryusul = "select c,d,e,e1, sum(harga) as jmlharga from v1_penghapusan_usul_det_bi where id = '".$detsk['ref_idusulan']."' and (sesi='' or sesi is null) and tindak_lanjut<>1  and status<>2  group by c,d,e,e1 "; 
			$usul = mysql_fetch_array(mysql_query($aqryusul));
			//get sub unit
			$aqryopd = "select * from ref_skpd where c='{$usul['c']}' and d='{$usul['d']}' and e='{$usul['e']}' and e1='{$usul['e1']}'"; 
			$qropd = mysql_fetch_array(mysql_query($aqryopd));
			$nmseksi = $qropd['nm_skpd'];

			//get opd / unit
			$aqryopd = "select * from ref_skpd where c='{$usul['c']}' and d='{$usul['d']}' and e='{$usul['e']}' and e1='{$kdSubUnit0}'"; 
			// echo  $aqryopd;
			$qropd = mysql_fetch_array(mysql_query($aqryopd));
			$nmbiro = $qropd['nm_skpd'];
			//get biro
			
			$aqryopd = "select * from ref_skpd where c='{$usul['c']}' and d='{$usul['d']}' and e='00'";
			$qropd = mysql_fetch_array(mysql_query($aqryopd));
			$nmopd = $qropd['nm_skpd'];
			if ($i2==0){
				$d1 .=  $nmseksi.' pada '.$nmbiro.'/'.$nmopd;	
			}else{
				$d1 .=  $i2==$jmldata-1? ' dan '.$nmseksi.' pada '.$nmopd : ', '.$nmseksi.' pada '.$nmbiro.'/'.$nmopd;	
			}
			
			
			$jmlharga = number_format( $usul['jmlharga'] , 2, ',','.' ); 
			$bil = bilang($usul['jmlharga']);
			//$daftar1 .= "<tr valign='top'><td></td>"."<td> </td></tr>";
			$daftar1 .= "<tr valign='top'><td></td><td width='' align='center'></td>
				<td width='' align='left'>".'&#'.(97+$i2).".</td>
				<td > $nmbiro pada $nmopd  dengan nilai perolehan sebesar Rp $jmlharga ($bil rupiah); </td></tr>";
			//if 
			$daftar2 .= "<tr valign='top'><td></td><td width='' align='center'></td>
				<td width='' align='left'>".'&#'.(97+$i2).".</td>
				<td > $nmbiro pada $nmopd</td></tr>";
			$i2++;
		}
		$data[1] = $d1;
		
		
		foreach( $data as  $key => $value ){
			$data[$key] =  "<span id='$key' name='$key' >".$data[$key]."</span><input type='hidden' id='d_$key' name='d_$key' value='{$data[$key]}'>";
		}
		$menu = 			
			"<div id='divmenuprint' style='background-color: EBEBEB;border-bottom: 1px solid gray;'>
			<table width='100%' style='font-family:arial;'><tbody><tr>
			<td style='font-size: 14;font-weight: bold;'>&nbsp;Draft Usulan SK</td>
			<td align='right'>
			<input type='hidden' id='id' name='id' value='$id'>
			<input id='btprint' type='button' value='Simpan' onclick='($this->Prefix).simpanDraftUsulSK()' style=''>
			<input id='btprint' type='button' value='Export Doc' onclick='($this->Prefix).expDocDraftUsulSK()' style=''>
			<input id='btprint' type='button' value='Cetak' onclick=\"document.getElementById('divmenuprint').style.display='none';window.print();document.getElementById('divmenuprint').style.display='block';\" style=''>
			</td></tr></tbody></table>
			</div>";
		$xls = $_REQUEST['xls'];
		if($xls){
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=kepgub.doc");
			header("Pragma: no-cache");
			header("Expires: 0");
			$menu='';
		}
/*	
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css".	
					
					"<script type='text/javascript' src='js/ajaxc2.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/global.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='dialog/dialog.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/base.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/daftarobj.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				    "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
				    "<script type='text/javascript' src='js/usulanhapusbacari.js' language='JavaScript' ></script>".		
					"<script type='text/javascript' src='js/usulanhapusskdet.js' language='JavaScript' ></script>".
					"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
					"$this->Cetak_OtherHTMLHead
				</head>".
			"<body style='margin:0' >
			<form name='adminForm' id='adminForm' method='post' action=''>	
			$menu	
			<div contenteditable='true' >
				<table class=\"rangkacetak\" style='width:16.7cm; font-family:arial;margin-top: 1.5cm;margin-left: 1.5cm;'>
				<tr valign='top' ><td>".				
				"<div style='text-align:center'>
					<img src='images/garuda.jpg'	><br><br>
					<span id='title' name='title' >
					{$data[28]}
					</span>
				</div>".
				"<table style='text-align: justify;font-family:arial;' >
					<tr valign='top'><td style='width:3.5cm'>{$data[24]}</td><td style='width:0.4cm' align='left'>{$data[25]}</td><td style='width:0.7cm' align='left'>a.</td><td > {$data[26]} {$data[1]} {$data[2]}</td></tr>
					<tr valign='top'><td></td><td width='' align='left'></td><td width='' align='left'>{$data[27]}</td><td > {$data[3]} </td></tr>
					
					<tr valign='top' style='height:20'><td></td><td width='' align='center'></td><td width='' align='left'></td><td >&nbsp</td></tr>
					<tr valign='top'><td>Mengingat</td><td width='' align='center'>:</td><td width='' align='left'>1.</td><td > {$data[4]}</td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>2.</td><td > {$data[5]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>3.</td><td > {$data[6]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>4.</td><td > {$data[7]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>5.</td><td > {$data[8]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>6.</td><td > {$data[9]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>7.</td><td > {$data[10]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>8.</td><td > {$data[11]} </td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>9.</td><td > {$data[12]} </td></tr>
										
					<tr valign='top' style='height:20'><td></td><td width='' align='center'></td><td width='' align='left'></td><td >&nbsp</td></tr>
					<tr valign='top'><td>Memperhatikan</td><td width='' align='center'>:</td><td width='' align='left'>1.</td><td > {$data[13]}</td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>2.</td><td > {$data[14]} </td></tr>
					
					<tr valign='' style='height:50'><td></td><td width='' align='center'></td><td width='' align='center'></td><td align='center'> Memutuskan </td></tr>
					
					<tr valign='top'><td>Menetapkan</td><td width='' align='center'>:</td><td colspan='2'> {$data[15]}</td></tr>
					
					<tr valign='top'><td>Kesatu</td><td width='' align='center'>:</td><td colspan='2'> {$data[16]}</td></tr>
					$daftar1
					
					<tr valign='top'><td>Kedua</td><td width='' align='center'>:</td><td colspan='2'> {$data[17]}</td></tr>
					<tr valign='top'><td></td><td width='10px' align='center'></td><td colspan='2'> $daftar2</td></tr>
					
					<tr valign='top'><td>Ketiga</td><td width='' align='center'>:</td><td colspan='2'> {$data[18]}</td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>a.</td><td> {$data[19]}</td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>b.</td><td> {$data[20]}</td></tr>
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='left'>c.</td><td> {$data[21]}</td></tr>
					
					
					<tr valign='top'><td>Ketiga</td><td width='' align='center'>:</td><td colspan='2'> {$data[22]}</td></tr>
					<tr valign='top'><td>Keempat</td><td width='' align='center'>:</td><td colspan='2'> {$data[23]}</td></tr>
					
					<tr valign='top' style='height:20'><td></td><td width='' align='center'></td><td width='' align='left'></td><td >&nbsp</td></tr>					
					<tr valign='top'><td></td><td width='' align='center'></td><td width='' align='center'></td><td> $ttd</td></tr>
					
				</table>".
				
				
				"</td></tr>
				</table>
			</div>
			</form>
			</body>";		
*/	
	echo "<CENTER><B>DRAFT SK BUPATI</B></CENTER>";	
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
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
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
			
	 $no_usulan_sk= $_REQUEST['no_usulan_sk'];
		
	 $tgl_usul_sk= $_REQUEST['tgl_usul_sk'];
		
	 $no_sk= $_REQUEST['no_sk'];
				
	 $tgl_sk= $_REQUEST['tgl_sk'];
			
	 $petugas_usulsk= $_REQUEST['ref_idpengadaan'];
				
	 $sesi= $_REQUEST['sesi']; //ambil sesi
				
		if( $err=='' && $no_usulan_sk =='' ) $err= 'No Usulan SK belum diisi!';
	 	if( $err=='' && $tgl_usul_sk =='' ) $err= 'Tgl Usul SK belum diisi!';
								
			if($fmST == 0){
				//cek 
				if( $err=='' ){
					$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM penghapusan_usulsk WHERE no_usulan_sk='$no_usulan_sk' "));
						if($get['cnt']>0 ) $err='No Usulan SK Sudah Ada!';
				}
					
				if($err==''){ //28 maret 2013
					$aqry = "INSERT into penghapusan_usulsk (no_usulan_sk,tgl_usul_sk,tgl_update,uid,no_sk,tgl_sk,
					         ref_idpegawai_usulsk,sesi)"."values('$no_usulan_sk','$tgl_usul_sk',now(),'$uid','$no_sk',
						    '$tgl_sk','$petugas_usulsk','$sesi')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
						
					//cari ID usul berdasarkan sesi tabel master
					$get =mysql_fetch_array(mysql_query("SELECT Id FROM penghapusan_usulsk WHERE sesi ='".$sesi."'"));
					$carID = $get['Id'];
					$cek .='cariID ='.$carID;
						
					//update detail set id = id usul by sesi 
					$upd = mysql_query("UPDATE penghapusan_usulsk_det SET Id='".$carID."' WHERE sesi='".$sesi."'"); 
					
					//hapus sesi
					mysql_query("UPDATE penghapusan_usulsk_det SET sesi=NULL WHERE sesi='".$sesi."'"); 
						
					//hapus sesi master
					mysql_query("UPDATE penghapusan_usulsk SET sesi=NULL WHERE sesi='".$sesi."'"); 
					}
			}else{
			 $old = mysql_fetch_array(mysql_query("SELECT * FROM penghapusan_usulsk WHERE Id='$idplh'"));
				if( $err=='' ){
					if($no_usulan_sk!=$old['no_usulan_sk'] ){
				       $get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM penghapusan_usulsk WHERE no_usulan_sk='$no_usulan_sk' "));
					   if($get['cnt']>0 ) $err='No Usulan SK Sudah Ada!';
					}
				 }
											
				if($err==''){
				$aqry = "UPDATE penghapusan_usulsk 
				         set "." no_usulan_sk='$no_usulan_sk',
				   		 tgl_usul_sk='$tgl_usul_sk',
						 tgl_update =now(),
						 uid ='$uid',
						 no_sk = '$no_sk',
						 tgl_sk = '$tgl_sk',
						 ref_idpegawai_usulsk = '$petugas_usulsk'".
						 "WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
			
	function simpansk(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
				
	 $no_sk= $_REQUEST['no_sk'];
				
	 $tgl_sk= $_REQUEST['tgl_sk'];
				
	 $pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
								
	 if( $err=='' && $no_sk =='' ) $err= 'No SK belum diisi!';
				
	 if( $err=='' && ($tgl_sk =='' || $tgl_sk == '0000-00-00')) $err= 'Tgl SK belum diisi!';
				
		if($fmST == 0){//baru
	       //cek 
		  if( $err=='' ){
		      $get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM penghapusan_usulsk WHERE no_sk='$no_sk' "));
			  if($get['cnt']>0 ) $err='No SK Sudah Ada!';
		  }
					
		  if($err==''){
		   	  $aqry = "INSERT into penghapusan_usulsk (no_sk,tgl_sk,tgl_update,uid,ref_idpegawai_usulsk_pejabat)".
					  "values('$no_sk','$tgl_sk',now(),'$uid','$pejabat_pengadaan')";	$cek .= $aqry;	
			  $qry = mysql_query($aqry);
		  }
		  
	  }else{
		 $old = mysql_fetch_array(mysql_query("SELECT * FROM penghapusan_usulsk WHERE Id='$idplh'"));
			if( $err=='' ){
				if($no_sk!=$old['no_sk'] ){
					$aqry = "SELECT count(*) as cnt FROM penghapusan_usulsk WHERE no_sk='$no_sk' ";
					//$cek .= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry));
					if($get['cnt']>0 ) $err='No SK Sudah Ada!';
				 }
			}
			
			if($err==''){
			   $aqry = "UPDATE penghapusan_usulsk 
				        SET	no_sk='$no_sk',
							tgl_sk='$tgl_sk',
							tgl_update=now(),
							uid='$uid',
							ref_idpegawai_usulsk_pejabat='$pejabat_pengadaan'".
							"WHERE Id='".$idplh."'";	$cek .= $aqry;
			  $qry = mysql_query($aqry);
			}
			
			//===========insert data Ke tabel penghapusan/pemindahtangan=======================================================
		 	//get det usulsk
			if($err==''){
				$aqry = "select * from penghapusan_usulsk_det where Id ='".$idplh."' "; $cek .='usulskdet ='.$aqry;
				$qrusulskdet = mysql_query($aqry);
				
				//get idusulan dari usukskdet
				while( $rowusulskdet = mysql_fetch_array($qrusulskdet) ){
					$idusul = $rowusulskdet['ref_idusulan'];
					//get usuldet dari usulan
					$aqry = "select * from penghapusan_usul_det where Id='$idusul' "; $cek .='usuldet ='.$aqry;
					$qrusuldet = mysql_query($aqry);
					while ($rowusuldet = mysql_fetch_array($qrusuldet)){
						$tindak_lanjut = $rowusuldet['tindak_lanjut'];
						$kondisi = $rowusuldet['kondisi'];
						
						//get id buku induk
						$idbi = $rowusuldet['id_bukuinduk'];
						//get data bi
						$qy = "SELECT id,a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan FROM buku_induk 
									WHERE id = '".$idbi."'";$cek .=$qy;							
						$result = mysql_query($qy);
						$bi = mysql_fetch_array($result);
						
						if($tindak_lanjut==2) {//2 adalah tindak lanjut dimusnahkan
							//cek jika sudah dihapus 
							$aqry = "select count(*) as cnt from penghapusan where id_bukuinduk='{$bi['id']}' "; $cek .=' hapus ada='.$aqry;
							$hps= mysql_fetch_array(mysql_query($aqry));
							
							if($hps['cnt'] == 0 ){
								//======INSERT DATA KE TABEL PENGHAPUSAN============
								$query ="INSERT INTO penghapusan(id,id_bukuinduk,
										a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan,
										tgl_penghapusan,tahun, mutasi, sudahmutasi,
										kondisi_akhir,
										nosk,tglsk,tgl_update)
							    		VALUES('','$bi[id]',
										'$bi[a1]','$bi[a]','$bi[b]','$bi[c]','$bi[d]','$bi[e]','$bi[e1]','$bi[f]','$bi[g]','$bi[h]','$bi[i]','$bi[j]',
										'$bi[noreg]','$bi[thn_perolehan]',
										'$tgl_sk','$bi[thn_perolehan]','0','0',
										'$kondisi',
										'$no_sk','$tgl_sk',now())";$cek .='hapus='.$query;
								
							}  else {
								//======update DATA KE TABEL PENGHAPUSAN============
								$query ="update penghapusan
										set nosk = '$no_sk',
										tglsk = '$tgl_sk',
										tgl_penghapusan = '$tgl_sk',
										tgl_update = now()
										where id_bukuinduk='$bi[id]' ";										
										$cek .='hapus='.$query;
							}
							
							$result = mysql_query($query);
							  
							//============UPDATE DATA status_barang =3 di tabel buku induk sesuai id=========
							$qy = "UPDATE buku_induk
							  		  SET status_barang ='3'
									  WHERE id ='".$bi['id']."'
							  		 ";$cek .=$qy;
							$res = mysql_query($qy);
						}
						
						if($tindak_lanjut==3){ //dipindahtangankan
							$aqry = "select count(*) as cnt from penghapusan where id_bukuinduk='{$bi['id']}' "; $cek .=' hapus ada='.$aqry;
							$hps= mysql_fetch_array(mysql_query($aqry));
							
							if($hps['cnt'] == 0 ){
							//===========INSERT DATA KE TABEL PEMINDAHTANGAN=====
								$qins = "INSERT INTO pemindahtanganan(id,id_bukuinduk,
										a1,a,b,c,d,e,e1,f,g,h,i,j,
										noreg,thn_perolehan,
										tgl_pemindahtanganan,
										nosk,tglsk
										)
										 VALUES('','$bi[id]',
										 '$bi[a1]','$bi[a]','$bi[b]','$bi[c]','$bi[d]','$bi[e]','$bi[e1]','$bi[f]','$bi[g]','$bi[h]','$bi[i]','$bi[j]',
										 '$bi[noreg]','$bi[thn_perolehan]',
										 '$tgl_sk',
										 '$no_sk','$tgl_sk'
										 )";$cek.='pindah='.$qins;									
							}else{
								//===========update DATA KE TABEL PEMINDAHTANGAN=====
								$qins = "update pemindahtanganan
									set tgl_pemindahtanganan = '$tgl_sk',
									nosk ='$no_sk', tglsk = '$tgl_sk'
									where id_bukuinduk = '$bi[id]'";
								$cek.='pindah='.$qins;										
							}
							$resq = mysql_query($qins);
							 //============UPDATE DATA status_barang =3 di tabel buku induk sesuai id=========
							$qy = "UPDATE buku_induk
						  		  SET status_barang ='4'
								  WHERE id ='".$bi['id']."'
						  		 ";$cek .=$qy;
							$res = mysql_query($qy);
						}//end tindak lanjut pemindahtangan	
						
					}
				}
				
				
				
			}
			/*
			if($err == ''){
		     $read = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk_det WHERE Id = '".$idplh."'")); //Tpenghapusan_usulsk
		  	 $getSK = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk WHERE Id = '".$read['Id']."'"));
			 $sql = "SELECT* FROM penghapusan_usul_det WHERE Id = '".$read['ref_idusulan']."' ";$cek .=$sql;
			 $rs = mysql_query($sql);
			  while($row = mysql_fetch_array($rs)){//Tpenghapusan_usul_det
			 	 $tindak_lanjut = $row['tindak_lanjut'];
			     $idBi = $row['id_bukuinduk'];
			    if($tindak_lanjut==2) {//2 adalah tindak lanjut dimusnahkan
			 	  $qy = "SELECT id,a1,a,b,c,d,e,f,g,h,i,j,noreg,thn_perolehan FROM buku_induk WHERE id = '".$idBi."'";$cek .=$qy;
				  $result = mysql_query($qy);
				  $bi = mysql_fetch_array($result);
				  
				  //======INSERT DATA KE TABEL PENGHAPUSAN============
				   $query ="INSERT INTO penghapusan(id,id_bukuinduk,a1,a,b,c,d,e,f,g,h,i,j,noreg,thn_perolehan,tgl_penghapusan,tahun,mutasi,sudahmutasi,kondisi_akhir,nosk,tglsk,tgl_update)
				    		VALUES('','$bi[id]','$bi[a1]','$bi[a]','$bi[b]','$bi[c]','$bi[d]','$bi[e]','$bi[f]','$bi[g]','$bi[h]','$bi[i]','$bi[j]',
							'$bi[noreg]','$bi[thn_perolehan]','$getSK[tgl_sk]','$bi[thn_perolehan]','0','0','$row[kondisi]','$getSK[no_sk]','$getSK[tgl_sk]',now())";$cek .=$query;
				   $result = mysql_query($query);
				  
				  //============UPDATE DATA status_barang =3 di tabel buku induk sesuai id=========
				   $qy = "UPDATE buku_induk
				  		  SET status_barang ='3'
						  WHERE id ='".$bi['id']."'
				  		 ";$cek .=$qy;
				   $res = mysql_query($qy);
			    } //end if
				
				if($tindak_lanjut==3){ //dipindahtangankan
					$sq = "SELECT id,a1,a,b,c,d,e,f,g,h,i,j,noreg,thn_perolehan FROM buku_induk WHERE id = '".$idBi."'";$cek .=$sq;
				    $resq = mysql_query($sq);
				    $bi = mysql_fetch_array($resq);	//untuk pemindahtanganan
					
					//===========INSERT DATA KE TABEL PEMINDAHTANGAN=====
					$qins = "INSERT INTO pemindahtanganan(id,id_bukuinduk,a1,a,b,c,d,e,f,g,h,i,j,noreg,thn_perolehan)
							 VALUES('','$bi[id]','$bi[a1]','$bi[a]','$bi[b]','$bi[c]','$bi[d]','$bi[e]','$bi[f]','$bi[g]','$bi[h]','$bi[i]','$bi[j]','$bi[noreg]','$bi[thn_perolehan]')";$cek.=$qins;	
					$resq = mysql_query($qins);
					
					 //============UPDATE DATA status_barang =3 di tabel buku induk sesuai id=========
				   $qy = "UPDATE buku_induk
				  		  SET status_barang ='4'
						  WHERE id ='".$bi['id']."'
				  		 ";$cek .=$qy;
				   $res = mysql_query($qy);
				}//end if	
				*/
			//} //end while
		  //}
		  								
		} //end else
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
	function pilihbacari(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];/* */
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$a1 = $Main->DEF_KEPEMILIKAN;
					
		$sesi = $_REQUEST['UsulanHapussk_daftarsesi'];//$this->prefix.'_daftarsesi'];	
		
		$Idm = $_REQUEST['UsulanHapussk_daftarid']; //Id master	
		
		$pilih = $_REQUEST['UsulanHapussk_daftarpilih']; //str daftar yg dipilih
		
		$query_values = explode(',', $pilih); //daftar pilih
		 
		$c ='';
		if($Idm==''){ 
			$Idf = '0';
		}else{
			$Idf = $Idm;
			$sesi = '';
		}
		for($i=0;$i< count($query_values) ;$i++){
		    //generate string query value ------------------------------
			$nilaibaru="('".$Idf."','".$query_values[$i]."','".$sesi."',now())"; 
			//idf=Id ; $query_values[$i] = ref_idusulan; $sesi = sesi now() = $tgl_update
			if($c ==''){
			   $c =$c.$nilaibaru;
			}else{
			   $c =$c.','.$nilaibaru;	
		    }
			
		 	//Data Barang pada no ba harus sudah di ada status pengecekan ----------		  	   		  
		   	$ss ="select count(*) AS jum from penghapusan_usul_det where Id='".$query_values[$i]."' and (tindak_lanjut IS NULL || tindak_lanjut='') ";$cek.=$ss;
		   	$ddd = mysql_query($ss);
		   	$gg = mysql_fetch_array($ddd);
			if($gg['jum']>0){
		   	    $err ="Ada Barang  yang Statusnya belum pengecekan";
				break;
		   	}
		   
		   
		 	//cek apakah sudah ada di list usul detail,kalau ada munculkan pesan:data udah ada di list -----
		  	$get = mysql_fetch_array(mysql_query(
		        "SELECT count(*) as cnt  
				FROM penghapusan_usulsk_det 
				WHERE Id='".$Idf."' and sesi ='".$sesi."' and ref_idusulan='".$query_values[$i]."'"));
			if($get['cnt']>0 ){
		  	 	$get = mysql_fetch_array(mysql_query("SELECT*FROM penghapusan_usul WHERE Id='".$query_values[$i]."'"));
			   	$noba = $get['no_ba'];
			   	$err='Nomor BA "'.$noba.'" Sudah Ada!';
				break;
			}
	 		
			//cek apakah NO BA ini sudah digunakan di SURAT USULAN LAIN cat:NO BA harus 1 untuk surat USULAN SK
		  	$read = mysql_fetch_array(mysql_query("SELECT count(*) as hit FROM penghapusan_usulsk_det WHERE ref_idusulan ='".$query_values[$i]."' "));
		   	if($err=='' && $read['hit'] > 0){
				$result = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk_det WHERE ref_idusulan='".$query_values[$i]."' "));
			 	//untuk keperluan Ba
			 	$select = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id='".$query_values[$i]."'"));
			 	$err='Nomor BA '.$select['no_ba'].' Sudah digunakan';
		  	}
			/*
			//cek no ba ini tidak ada yg ditolak
			if ($err=='' ){
				$idusul = $query_values[$i];//
				$aqry = "select count(*) as cnt from penghapusan_usul_det where id='$idusul' and tindak_lanjut=1 ";
				$get = mysql_fetch_array(mysql_query( $aqry ));
				if ($get['cnt'] > 0 ) $err = "Berita Acara ini tidak dapat diusulkan, karena ada barang yang ditolak! ";
			}
			*/
    	}//end for
													
	 if($err==''){	
		//ID kosong
		 if($Idm=='') { //id master 
		    $aqry = "replace into penghapusan_usulsk_det (Id,ref_idusulan,sesi,tgl_update)values $c ";	$cek .= $aqry;	
		    $qry = mysql_query($aqry);
		 }else{ //id master ada
		    $query = "replace into penghapusan_usulsk_det (Id,ref_idusulan,sesi,tgl_update)values $c ";	$cek .= $query;	
		    $result = mysql_query($query);	
		 }
	  }
								
 	  return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	

	function batalcari(){ //01 April 2013
   	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
		
	 $batal = mysql_query("DELETE FROM penghapusan_usulsk_det WHERE Id=0 and (sesi IS not null and sesi !='')");
	 $cek .=$batal; 										
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
	function batalsk(){
		global $HTTP_COOKIE_VARS;
	 	global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$a1 = $Main->DEF_KEPEMILIKAN;
	 	$a = $Main->Provinsi[0];
		$b = $Main->DEF_WILAYAH;;
	 	$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		
		$no_sk= $_REQUEST['no_sk'];
		$tgl_sk= $_REQUEST['tgl_sk'];
		$pejabatsk = $_REQUEST['ref_idpengadaan'];
			
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
						
		if($err==''){
			//update tabel penghapusan_usulsk
			$query = "UPDATE penghapusan_usulsk
					  SET no_sk = NULL,
					  	  tgl_sk = NULL,
						  ref_idpegawai_usulsk_pejabat = NULL,
						  tgl_update=now(),
						  uid='$uid'
					  WHERE Id = '".$this->form_idplh."' ";$cek .=$query;
			$result =mysql_query($query);
			
			//ambil usulan di detail sk ---------------------------
			$aqry = "select * from penghapusan_usulsk_det where id='".$this->form_idplh."'"; $cek.=' detsk = '.$aqry;
			$qrskdet = mysql_query($aqry);
			while($skdet = mysql_fetch_array($qrskdet)){
				$idusulan = $skdet['ref_idusulan'];
				//ambil data usulan detail
				$aqry = "select * from penghapusan_usul_det where Id='$idusulan'";
				$qrusuldet = mysql_query($aqry);
				while( $usuldet = mysql_fetch_array($qrusuldet) ){
					$tindaklanjut = $usuldet['tindak_lanjut'];
					$idbi = $usuldet['id_bukuinduk'];
					if($tindaklanjut==2){
						//TABEL PENGHAPUSAN
						$qy = "DELETE FROM penghapusan 
					   		WHERE id_bukuinduk = '".$idbi."' ";$cek .=$qy;
						$rs = mysql_query($qy);
					}else if($tindaklanjut==3){
						//TABEL pemindahtanganan
						$qye = "DELETE FROM pemindahtanganan 
							   WHERE id_bukuinduk = '".$idbi."' ";$cek .=$qye;
						$rsw = mysql_query($qye);
					}
									
					//UPDATE data status_barang menjadi 1 di tabel buku_induk berdasarkan Id
					$aqry = "UPDATE buku_induk SET status_barang = '1' 
							 WHERE id = '".$idBI."' ";$cek .=$aqry;
					$res = mysql_query($aqry);
				}
				
				
				
				
			}
			
			/*	
			//==DELETE Data berdasarkan id_bukuinduk di tabel penghapusan dan Update Status Barang='1' di BI==
			//$this->form_idplh = Id usulan
			$que = "SELECT* FROM penghapusan_usul_det 
				    WHERE id = '".$this->form_idplh ."' 
					AND   tindak_lanjut='2' ";$cek .=$que;
			$res = mysql_query($que);
			while($row = mysql_fetch_array($res)){
				  $idBI = $row['id_bukuinduk'];
				
				//TABEL PENGHAPUSAN
				$qy = "DELETE FROM penghapusan 
					   WHERE id_bukuinduk = '".$idBI."' ";$cek .=$qy;
				$rs = mysql_query($qy);
				
				//UPDATE data status_barang menjadi 1 di tabel buku_induk berdasarkan Id
				$aqry = "UPDATE buku_induk 
						 SET status_barang = '1' 
						 WHERE id = '".$idBI."' ";$cek .=$aqry;
				$rse = mysql_query($aqry);
			} //end while
			*/
			/*
			//==DELETE Data berdasarkan id_bukuinduk di tabel pemindahtanganan dan Update Status Barang='1' di BI==========
			$quer = "SELECT* FROM penghapusan_usul_det 
				    WHERE id = '".$this->form_idplh ."' 
					AND   tindak_lanjut='3' ";$cek .=$quer;
			$resq = mysql_query($quer);
			while($rowe = mysql_fetch_array($resq)){
				  $idBIe = $rowe['id_bukuinduk'];
				
				//TABEL pemindahtanganan
				$qye = "DELETE FROM pemindahtanganan 
					   WHERE id_bukuinduk = '".$idBIe."' ";$cek .=$qye;
				$rsw = mysql_query($qye);
				
				//UPDATE data status_barang menjadi 1 di tabel buku_induk berdasarkan Id
				$qyr = "UPDATE buku_induk 
						 SET status_barang = '1' 
						 WHERE id = '".$idBIe."' ";$cek .=$qyr;
				$rse = mysql_query($qyr);
			} //end while
			*/
		}//end if
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}
	
	function simpanDraftUsulSK(){
		$cek =''; $err=''; $content='';
		
		$data = array();
		for($i=1;$i<=28;$i++){
			$data[$i] = $_REQUEST['d_'.$i];	
		}
		//$cek = json_encode($data);		
		$id = $_REQUEST['id'];
		$draft_isi_surat = json_encode( $data );
		$aqry = "update penghapusan_usulsk set draft_isi_surat ='$draft_isi_surat' where Id='$id'";
		mysql_query($aqry);
				
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  	case 'getIdBA':{
			$get = $this->getIdBA();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];	
			break;
		}	
	  	case 'genKepSekda':{
			$json=FALSE;
			$this->genKepSekda();
			break;
		}
	  	case 'simpanDraftUsulSK':{
			$get = $this->simpanDraftUsulSK();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];	
			break;
		}
	  	case 'genKepGub':{
			$json=FALSE;
			$this->genKepGub();
			break;
		}
		case 'cbxgedung':{
		 	 $c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
			 $d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
			 $e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
			 $e1= $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				if($c=='' || $c =='00') {
					$kondC='';
				}else{
					$kondC = "and c = '$c'";
				}
				if($d=='' || $d =='00') {
					$kondD='';
				}else{
					$kondD = "and d = '$d'";
				}
				if($e=='' || $e =='00') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				if($e1=='' || $e1 =='00' || $e1 =='000') {
					$kondE1='';
				}else{
					$kondE1 = "and e1 = '$e1'";
				}
				$aqry = "SELECT * FROM ref_ruang where q='0000' $kondC $kondD $kondE $kondE1";
				$cek .= $aqry;
				$content = genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						$aqry,
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" );
				break;
		}
				
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formbaCari':{				
			$fm = $this->setFormbaCari();				
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
			
		case 'formEditsk':{				
			$fm = $this->setFormEditsk();				
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
	
	   case 'simpansk':{
			$get= $this->simpansk();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
		}
	   
	   case 'pilihbacari':{
			$get= $this->pilihbacari();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
	   break;
	   }
	  
	   case 'batalcari':{
	 		$get= $this->batalcari();
	 		$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
	   break;
	   }
	   
	   case 'batalsk':{
			$get= $this->batalsk();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
	   break;
		}
				
	   case 'getdata':{
			$id = $_REQUEST['id'];
			$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
			$get = mysql_fetch_array( mysql_query($aqry));
	 		 if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
				break;
	   }
	
		 default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
		 break;
	 }
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
	 }//end switch
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>**/
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
		    "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
		    "<script type='text/javascript' src='js/usulanhapusbacari.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/usulanhapusskdet.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
		
	//form ==================================
	function setFormBaru(){
		
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl_usul_sk'] = date("Y-m-d"); //set waktu sekarang
		$dt['sesi'] = gen_table_session('penghapusan_usulsk','uid'); //generate no sesi
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
   function setFormbaCari(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		$fm = $this->setFormbaCr($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "SELECT * FROM penghapusan_usulsk WHERE Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		//cek  NO SK sudah ADA
		/**if($dt['no_sk']==''){
			//set form
			$fm = $this->setForm($dt);
		}else{
			$fm['err'] = 'SK Sudah Ada';
		}
		**/
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormEditsk(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "SELECT * FROM penghapusan_usulsk WHERE Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		//$dt['tgl_sk'] =date("Y-m-d"); //set waktu sekarang;
		$fm = $this->setFormsk($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	
	function setFormBA($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 800;
		$this->form_height = 250;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Berita Acara BARU';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		

		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];		
		
		//ambil pegawai
		$got = mysql_fetch_array(mysql_query("SELECT * FROM ref_pegawai WHERE Id = '".$dt['ref_idpegawai_usul']."'"));
			$nama = $got['nama'];
			$nip = $got['nip'];
			$jabatan = $got['jabatan'];

		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'no_usulan' => array( 
						'label'=>'No.Usulan', 'value'=>$dt['no_usulan'], 
						'labelWidth'=>120, 
						'type'=>'' 
			    ),
			'tgl_usul' => array( 
						'label'=>'Tgl Usul',
						 'value'=>TglInd($dt['tgl_usul']), 
						 'type'=>''
						 
						 ),
			'no_ba' => array( 
						'label'=>'No.BA', 'value'=>$dt['no_ba'], 
						'labelWidth'=>120, 
						'type'=>'text' 
			    ),
			'tgl_ba' => array( 
						'label'=>'Tgl BA',
						 'value'=>$dt['tgl_ba'], 
						 'type'=>'date'
						 ),
			
			'pejabat_pengadaan' => array(  
				'label'=>'Pegawai', 
				'value'=> 
					"<input type='text' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai_ba']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$nama."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$nip."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
				,
				'type'=>'' 
			), 	
			'jbt1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			)
		);
						
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBA()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function Hapus_Data_After($id){
	 $err = ''; $content=''; $cek='';
	 //jika Id di penghapusan_usulsk dihapus maka Id di penghapusan_usulsk_det harus terhapus
	 $qy = "DELETE from penghapusan_usulsk_det WHERE Id ='".$id."'";
	 $query = mysql_query($qy);
	 
	return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
   }
	
	function Hapus_Validasi($id){ 
	 //usulan yang sudah jadi SK tidak boleh di hapus
	 $errmsg ='';
	 // ambil data SK di tabel Penghapusan_usulsk
	 $get = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk WHERE Id='".$id."'"));
	  if($get['no_sk']!=''){ //jika no sk ada
   	     $errmsg = 'No USulan sudah jadi SK tidak boleh dihapus !';
	  }
	
	return $errmsg;
    }
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
			
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 800;
	 $this->form_height = 400;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];			
	  }
	 //items ----------------------
	 //ambil pegawai
	 $get = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$dt['ref_idpegawai_usulsk']."'"));
	 $this->form_fields = array(	
			'no_usulan_sk' => array( 
						'label'=>'No. Usulan SK', 
						'value'=>"<input type='text' name='no_usulan_sk' value='".$dt['no_usulan_sk']."' size='43'>", 
						'labelWidth'=>120, 
						'type'=>'' 
			    ),
			'tgl_usul_sk' => array( 
						'label'=>'Tgl. Usulan SK',
						'labelWidth'=>120, 
						 'value'=>$dt['tgl_usul_sk'], 
						 'type'=>'date'
						 ),
			 
			'petugas_usulsk' => array(  
				'label'=>'Pengelola Barang / Pembantu', 
				'labelWidth'=>150,
				'value'=> 
					"<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai_usulsk']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$get['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$get['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
				,
				'type'=>'' 
			), 	
			'daftardetail' => array( 
						'label'=>'',
						 'value'=>"<div id='div_detail'></div>", 
						 'type'=>'merge'
						 )
		);
		/*Update 13 Juni 2013*/
		//Cek  No SK jika ada No sk maka cari,simpan,batalcari di hidden
		$quey = "SELECT Id FROM penghapusan_usulsk_det WHERE ref_idusulan = '".$dt['Id']."' "; $cek .=$quey;
		$res = mysql_fetch_array(mysql_query($quey));	
		
		$qy = "SELECT no_sk FROM penghapusan_usulsk WHERE Id = '".$dt['Id']."' ";
		$rs = mysql_fetch_array(mysql_query($qy));
		
		if($rs['no_sk'] !=''){
			$Hapus = "<a href='javascript:UsulanHapusskdet.Hapus()'><input type='button' value='Hapus' style='visibility:hidden'></a>";
			$Cari = "<input type='button' value='Cari' onclick ='".$this->Prefix.".Cari()' style='visibility:hidden'>";
			$Simpan = "<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' style='visibility:hidden'>";
			$Batal = "<input type='button' value='Close' onclick ='".$this->Prefix.".Batalcari()' style='visibility:visible'>";
		}else{
			$Hapus = "<a href='javascript:UsulanHapusskdet.Hapus()'><input type='button' value='Hapus' style='visibility:visible'></a>";
			$Cari = "<input type='button' value='Cari' onclick ='".$this->Prefix.".Cari()' style='visibility:visible'>";
			$Simpan = "<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' style='visibility:visible'>";
			$Batal = "<input type='button' value='Batal' onclick ='".$this->Prefix.".Batalcari()' style='visibility:visible'>";
		}		
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='sesi' name='sesi' value='".$dt['sesi']."'> ".
			
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			$Hapus.
			//"<a href='javascript:UsulanHapusskdet.Hapus()'><input type='button' value='Hapus'></a>".
			//"<input type='button' value='Cari' onclick ='".$this->Prefix.".Cari()' >".
			$Cari.
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			$Simpan.
			//"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batalcari()' >";
			"$Batal";	
			
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormbaCr($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$form_name = $this->Prefix.'_formbacari';	//nama Form			
		$this->form_width = 800;
		$this->form_height = 400;
		$this->form_caption = 'Cari Berita Acara'; //judul form
		$this->form_fields = array(				
			 'detailbacari' => array( 
						'label'=>'',
						 'value'=>"<div id='div_detailbacari'></div>", 
						 'type'=>'merge'
						 )
		);
		
		//tombol
		$this->form_menubawah =
			"<input type=hidden  value='' id='".$this->Prefix."_daftarid' name='".$this->Prefix."_daftarid' > ".
			"<input type=hidden  value='' id='".$this->Prefix."_daftarsesi' name='".$this->Prefix."_daftarsesi' > ".
			"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>".
			"<input type='button' name='pilih' value='Pilih' onclick ='".$this->Prefix.".Pilihbacari()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closecari()' >";
		
		//$form = //$this->genForm();		
		$form= centerPage("<form name='$form_name' id='$form_name' method='post' action=''>".
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
					$this->form_menu_bawah_height).
				"</form>");
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function setFormsk($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
		
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 700;
	 $this->form_height = 170;
	  if ($this->form_fmST==0) {
	   	 $this->form_caption = 'Baru';
		 $nip	 = '';
	 }else{
		 $this->form_caption = 'Berita Acara SK';			
		 $ref_idpegawai_usulsk = $dt['ref_idpegawai_usulsk_pejabat'];		
	 }
		
	 //items ----------------------
	 //ambil pegawai
	 $got = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$dt['ref_idpegawai_usulsk_pejabat']."'"));
	 $nama = $got['nama'];
	 $nip = $got['nip'];
	 $jabatan = $got['jabatan'];

	 $this->form_fields = array(		
			'no_usulan_sk' => array( 
			'label'=>'No.Usulan SK', 
			'value'=>$dt['no_usulan_sk'], 
			'labelWidth'=>120, 
			'type'=>'' 
		    ),
			
			'tgl_usul_sk' => array( 
			'label'=>'Tgl Usul SK',
		    'value'=>TglInd($dt['tgl_usul_sk']), 
		    'type'=>''
		    ),
		
			'no_sk' => array( 
			'label'=>'No.SK', 
			'value'=>"<input type='text' name='no_sk' value='".$dt['no_sk']."' size='43px'>", 
			'labelWidth'=>120, 
			'type'=>'' 
			),
			
			'tgl_sk' => array( 
			'label'=>'Tgl SK',
			'value'=>$dt['tgl_sk'], 
			'type'=>'date'
			),
			
			'pejabat_pengadaan' => array(  
			'label'=>'Pejabat SK', 
			'value'=> 
			  "<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai_usulsk_pejabat']."'> ".
			  "<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$nama."' style='width:250'> &nbsp; ".
			  "NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$nip."' style='width:150' > ".					
			  "<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
			  ,
			'type'=>'' 
			), 	
			
			'jbt1' => array(  'label'=>'', 
			'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
			'type'=>'' , 'pemisah'=>' '
			)
		);
						
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpansk()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
				
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function cariJml($Id,$kib) {
		//-- jml buku induk
		$query ="select count(*) AS jml , sum(ifnull(jml_harga,0)+ ifnull(tot_pelihara,0)+ ifnull(tot_pengaman,0) ) AS harga 								 
				 from v1_penghapusan_usul_det_bi
				 where Id='".$Id."' and f='".$kib."'";
		$rs = mysql_fetch_array(mysql_query($query));
		$hsl->jml = $rs['jml'];
		$hsl->harga = $rs['harga'];			
		return $hsl;
	}
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	 "<thead>
	  <tr>
	   <th class='th01' width='20' rowspan=2>No.</th>
	 	$Checkbox		
	   <th class='th02' colspan=4>Usulan SK</th>
	   <th class='th02' colspan=3>SK</th>
	  </tr>
	  <tr>
	   <th class='th01' width=100>No. </th>
	   <th class='th01' width=100>Tanggal  </th>
	   <th class='th01' width=150>Petugas Usul</th>
	   <th class='th01' width=150>Jumlah Harga</th>
	   <th class='th01' width=100>No. </th>								
	   <th class='th01' width=100>Tanggal</th>								
	   <th class='th01' width=250>Pejabat SK</th>								
	  </tr>
	  </thead>";
	
		return $headerTable;
	}
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	//get dinas		
	 $dinas = '';
	 $c=''.$isi['c'];
	 $d=''.$isi['d'];
	 $e=''.$isi['e'];
	 $e1=''.$isi['e1'];
	
	 $Id=''.$isi['Id'];
	 
	 $petugas_usul=''.$isi['ref_idpegawai_usulsk'];
	
	 $pejabatsk=''.$isi['ref_idpegawai_usulsk_pejabat'];
	  
		
	 $nmopdarr=array();	
			//if($fmSKPD == '00'){
	 $get = mysql_fetch_array(mysql_query("SELECT* FROM v_bidang WHERE c='".$c."' "));		
	  if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
			//}
			//if($fmUNIT == '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
	 $get = mysql_fetch_array(mysql_query("SELECT* FROM v_opd WHERE c='".$c."' and d='".$d."' "	));		
	  if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
			//}
			//if($fmSUBUNIT == '00'){
	 $get = mysql_fetch_array(mysql_query("SELECT* FROM v_unit WHERE c='".$c."' and d='".$d."' and e='".$e."'"));		
	  if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
			//}
			//if($fmSUBUNIT == '00'){
	 $get = mysql_fetch_array(mysql_query("SELECT* FROM ref_skpd WHERE c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."'"));		
	  if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];


	 $nmopd = join(' - ', $nmopdarr );
			//*/
			//$nmopd = $grp.' '.$c.$d.$e;
		//}
		
	 //get Pegawai untuk petugas USUL
	 $getPET = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE id='".$petugas_usul."'"));
				
	//get Pegawai untuk Pejabat SK
	$getPEJ = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE id='".$pejabatsk."'"));
	
	 $mayadesi =mysql_fetch_array(mysql_query("SELECT ref_idusulan FROM penghapusan_usulsk_det WHERE Id = '".$Id."' "));
	 $ref_idusulan = $mayadesi['ref_idusulan'];
	 
	 /** Jumlah harga yang ditampilkan hanya status tindak lanjut 2 dan 3 **/
	 //kib A 
	 $totalduitkiba=  "SELECT SUM(harga) AS hargakiba FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='01' AND tindak_lanjut!='1' ";
	 $resskiba = mysql_query($totalduitkiba);
	 while($row =mysql_fetch_array($resskiba)) {
				$totetotkiba = $row['hargakiba'];
	 }
	 $totetotkiba =$totetotkiba==0?'0':$totetotkiba;
	 
	 //kib b 
	 $totalduitkibb=  "SELECT SUM(harga) AS hargakibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='02' AND tindak_lanjut!='1' ";
	 $resskibb = mysql_query($totalduitkibb);
	 while($row =mysql_fetch_array($resskibb)) {
				$totetotkibb = $row['hargakibb'];
	 }
	 $totetotkibb =$totetotkibb==0?'0':$totetotkibb;
	
	//kib c 
	 $totalduitkibc=  "SELECT SUM(harga) AS hargakibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='03' AND tindak_lanjut!='1' ";
	 $resskibc = mysql_query($totalduitkibc);
	 while($row =mysql_fetch_array($resskibc)) {
				$totetotkibc = $row['hargakibc'];
	 }
	 $totetotkibc =$totetotkibc==0?'0':$totetotkibc;
	
	//kib d 
	 $totalduitkibd=  "SELECT SUM(harga) AS hargakibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='04' AND tindak_lanjut!='1' ";
	 $resskibd = mysql_query($totalduitkibd);
	 while($row =mysql_fetch_array($resskibd)) {
				$totetotkibd = $row['hargakibd'];
	 }
	 $totetotkibd =$totetotkibd==0?'0':$totetotkibd;
	 
	//kib e
	 $totalduitkibe=  "SELECT SUM(harga) AS hargakibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='05' AND tindak_lanjut!='1' ";
	 $resskibe = mysql_query($totalduitkibe);
	 while($row =mysql_fetch_array($resskibe)) {
				$totetotkibe = $row['hargakibe'];
	 }
	 $totetotkibe =$totetotkibe==0?'0':$totetotkibe;
	
	//kib f
	 $totalduitkibf=  "SELECT SUM(harga) AS hargakibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$ref_idusulan ."' AND f='06' AND tindak_lanjut!='1' ";
	 $resskibf = mysql_query($totalduitkibf);
	 while($row =mysql_fetch_array($resskibf)) {
				$totetotkibf = $row['hargakibf'];
	 }
	 $totetotkibf =$totetotkibf==0?'0':$totetotkibf;
	
	//total kabeh kib A - F
	$totalkabeh = 	$totetotkiba + 	$totetotkibb + $totetotkibc + $totetotkibd + $totetotkibe + $totetotkibf;
	$totalkabeh = $totalkabeh==0?'0':number_format($totalkabeh,2,',','.');
	 /** old 	 		
	//hitung Jumlah harga BA
	$a=array();
	for($i=1;$i<=6;$i++){
		$a[] = $this->cariJml($ref_idusulan,'0'.$i);
	}
	//=================== total Harga KIB A - KIB F =========
	$tothrg =0;
	for($i=0;$i<6;$i++){
			$tothrg=$tothrg+$a[$i]->harga;
	}
	$vtothrg=$tothrg==0?'0':number_format($tothrg,2,',','.');
	**/
	$pet = $getPET['nip']==''? '' : 'NIP.  '.$getPET['nip'].'<br/>'.$getPET['nama'];
	$pej = $getPEJ['nip']==''? '' : 'NIP.  '.$getPEJ['nip'].'<br/>'.$getPEJ['nama'];
	
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('', $isi['no_usulan_sk']);		
	 $Koloms[] = array('align="center"', TglInd($isi['tgl_usul_sk']));
	 $Koloms[] = array('', $pet);	
	 //$Koloms[] = array('', $vtothrg);
	 $Koloms[] = array('align="right"', $totalkabeh);
	 $Koloms[] = array('', $isi['no_sk']);
	 $Koloms[] = array('align="center"', TglInd($isi['tgl_sk']));
	 $Koloms[] = array('',$pej);
			
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
		
		//data order ------------------------------
	 $arrOrder = array(
	  	          array('1','No. SK'),
			      array('2','Tgl. SK'),	
	 );
		
	$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];	
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
				
	$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				//WilSKPD_ajx($this->Prefix.'Skpd') . 
				"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan SK. </div>".
					createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1).
			"</td>
			<td >" . 		
			"</td></tr>".
			"</table>".
			$vOrder=
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Pilih--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>Desc."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
						
		return array('TampilOpt'=>$TampilOpt);
		
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_sk>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_sk<='$fmFiltTglBtw_tgl2'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " no_sk $Asc1 " ;break;
			case '2': $arrOrders[] = " tgl_sk $Asc1 " ;break;
			//case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//$Order ="";
		//limit --------------------------------------
		/**$HalDefault=cekPOST($this->Prefix.'_hal',1);	//Cat:Settingan Lama				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		**/
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$UsulanHapussk = new UsulanHapusskObj();

?>