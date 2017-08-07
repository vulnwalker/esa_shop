<?php
date_default_timezone_set("Asia/Bangkok");
error_reporting(0);
$Main->Judul = "::ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah)";
$Main->CopyRight = "<br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"hakcipta\">
<tr><td>
	Pemerintah Kota Demo.
	 2015.
</td></tr>
</table>
";
$Main->CopyRight_isi = "Pengembang : PT. PILAR WAHANA ARTHA, Jl.Cikutra Baru XII No.24 Bandung  	
	Telp. (022) 87240297 HP. 081221239899";
$Main->HeadStyleico="<link rel=\"shortcut icon\" href=\"images/logo_web_demo_kota.ico\" />";
$Main->ManualBook="Buku_Petunjuk_ATISISBADA.pdf";
$Main->Image13='index_13_demo_kota.gif';
$Main->Image14='index_14_demo_kota.gif';
$Main->Logocetak='logo_cetak1_demo_kota.jpg';






//---------- DEFAULT -----------------
$USER_TIME_OUT = 15;//menit timeout, otomatis logout jika iddle
$USER_TIME_OUT2 = 1;//menit timeout untuk login 
$Main->DB_Hostname = 'localhost';
$Main->DB_Databasename = 'shop';
$Main->DB_User = 'root';
$Main->DB_Pass = '';
$Main->DB_Port = ':3306';
//Perencanaan



$con = mysql_connect($Main->DB_Hostname, $Main->DB_User, $Main->DB_Pass) or
die("Could not connect: " . mysql_error());
mysql_select_db($Main->DB_Databasename);

$getSettingPerencanaan = mysql_fetch_array(mysql_query("select * from settingperencanaan"));
if($getSettingPerencanaan['bypass_jadwal'] == '1'){
	$Main->bypassJadwal = TRUE;
}else{
	$Main->bypassJadwal = FALSE;
}

if($getSettingPerencanaan['wajib_validasi'] == '1'){
	$Main->wajibValidasi = TRUE;
}else{
	$Main->wajibValidasi = FALSE;
}





//Perencanaan

$Main->PagePerHal 		= 25;
$Main->JmlDataHal 		= 0;
$Main->DEF_KEPEMILIKAN 	= '12';//milik kota
$Main->NM_KEPEMILIKAN = 'Pemerintah Kabupaten/Kota'; //'Propinsi'
//Provinsi
$Main->Provinsi =array("10","CONTOH");
$Main->DEF_PROPINSI		= '10';
$Main->DEF_WILAYAH 		= '03';
$Main->NM_WILAYAH 		= 'Kota Demo';
$Main->NM_WILAYAH2		= 'PEMERINTAH KOTA DEMO';
$Main->NOREG_SIZE = 6;
$Main->NOREG_SAMPLE = '000002';
$Main->NOREG_MAX = 999999;
$Main->BARCODE_ENABLE = TRUE;
$Main->PENATAUSAHA_TOOLBARBAWAH = TRUE;

$Main->MODUL_PEMELIHARAAN = TRUE;
$Main->MODUL_PENGAMANAN = TRUE;
$Main->MODUL_MUTASI = TRUE;
$Main->MODUL_SENSUS = TRUE;
$Main->MODUL_SENSUS_MANUAL = FALSE;


$Main->MODUL_PERENCANAAN = TRUE;
$Main->MODUL_PENGADAAN = TRUE;
$Main->MODUL_PENERIMAAN = TRUE;
$Main->MODUL_PENGGUNAAN = TRUE;
$Main->MODUL_PENATAUSAHAAN = TRUE;
$Main->MODUL_PEMANFAATAN = TRUE;
$Main->MODUL_PENGAMANPELIHARA = TRUE;
$Main->MODUL_PENILAIAN = TRUE;
$Main->MODUL_PENGAPUSAN = TRUE;
$Main->MODUL_PENGAPUSAN_SK = TRUE;
$Main->MODUL_PENGAPUSAN_SEBAGIAN =TRUE;
$Main->MODUL_PEMINDAHTANGAN = TRUE;
$Main->MODUL_PEMBIAYAAN = FALSE;
$Main->MODUL_GANTIRUGI = FALSE;
$Main->MODUL_MONITORING = FALSE;
$Main->MODUL_CHART = TRUE;
$Main->MODUL_PEMBUKUAN = TRUE;
$Main->MODUL_AKUNTANSI= TRUE;
$Main->MODUL_RECLASS= TRUE;
$Main->TAMPIL_BIDANG=FALSE;
$Main->MODUL_PEMUSNAHAN= 1; // 1=tampil

$Main->KEPALA_OPD	= 'KEPALA SKPD';
$Main->CETAK_LOKASI = 'Demo';
$Main->MODUL_ASET_LAINNYA = TRUE; //kib g

$Main->SUBUNIT_DIGIT = 3; // ditampilkan 3
$Main->LABEL_KODE_WIDTH = 248; //labael kode di kanan atas penatausahaan - edit
$Main->LABEL_KODE_SUBUNIT_WIDTH = 32 ;// width utk kode e1 /seksi

$Main->SUBSUBKEL_DIGIT = 3; // ditampilkan 3
$Main->LABEL_KODE_SUBSUBKEL_WIDTH = 32 ;// width utk kode e1 /seksi

$Main->PATH_IMAGES='gambar2011';
$Main->PATH_DOKUMENS='dokum2011';



$Main->TAHUN_CUTOFF = 0; //<= : tahun tglbuku =  2012
$Main->NILAI_EXTRACOMPATIBLE=0;
$Main->TGL_CUTOFF = '31 Desember '.$Main->TAHUN_CUTOFF;
$Main->VerifikasiEnable=FALSE;

$Main->MODUL_PETA = TRUE;
$Main->SUB_UNIT = TRUE;

$Main->SHOW_CEK = 1;
$Main->KOND_ADA_TIDAK = FALSE;


$Main->BACKUP_DIR = '../../backup_sistem/atisisbada_bogor_kab/mysql/mysql/';

//$Main->WEB_LOCATION = 'http://atisisbada.net/';

if ($Main->KOND_ADA_TIDAK){
	$Main->KondisiBarang = array(
		array("1","Baik"),
		array("2","Kurang Baik"),
		array("3","Rusak Berat"),
		array("4","Tidak Ada")
	);	
}else{
	$Main->KondisiBarang = array(
		array("1","Baik"),
		array("2","Kurang Baik"),
		array("3","Rusak Berat")		
	);	
}

$Main->KondisiBarangLainnya = array(
	array("80","Ada Bukan Rusak Berat"),
	array("81","Ada")
);

$Main->KondisiEkstra=array(
		array("02","00","00","00","00",500000),
		array("05","00","00","00","00",500000)
		
);



$Main->CekIntraEkstra=FALSE;
$Main->thnsensus_default = 2016;//2014;
$Main->periode_sensus = 5;// tahun
$Main->defTglBukuBelumSensus =  '2016-12-31';//'2014-12-31';
/*$Main->GambarPath="/var/www/docs_atsb1/gambar2011";
$Main->DokumenPath="/var/www/docs_atsb1/dokum2011";
$Main->DownloadPath="/var/www/docs_atsb1/downloads2011";
$Main->ProfilePath="/var/www/docs_atsb1/profile2011";
$Main->TempPath="/var/www/tmps2011";
*/


$Main->StatusCek = array(
array("0","Belum di cek"),
array("1","Tidak ada error"),
array("2","Ada Error")
);

$Main->StatusMigrasi = array(
array("0","Belum Migrasi"),
array("1","Sudah Migrasi"),
array("2","Migrasi Ada Error")
);

$Main->jnsTrans = array(
	array('1','BELANJA MODAL'),
	array('2','ATRIBUSI'),	
	array('3','KAPITALISASI'),
	array('4','HIBAH'),
	array('5','PINDAH ANTAR SKPD'),
	array('6','PENILAIAN'),
	array('7','PENGHAPUSAN'),
	array('8','KOREKSI PEMBUKUAN'),
	array('9','REKLASS'),
	
);

$Main->jnsTrans2 = array(
	array('1','Perolehan Pembelian'),
	array('2','Perolehan Hibah'),
	array('3','Perolehan Lainnya'),	//?
	array('4','Perolehan Pindah Antar SKPD'),
	array('5','Perolehan Reklass Barang'),
	array('6','Perolehan Kapitalisasi'),
	array('7','Perolehan Koreksi Pembukuan'),
	array('8','Perolehan Extrakomptable'),//
	
	array('9','Pemeliharaan'),
	array('10','Pengamanan'),
	array('11','Hapus Sebagian'),
	array('12','Koreksi Harga'),
	array('13','Penilaian'), //?
	
	array('14','Penghapusan'),
	array('15','Pindah Antar SKPD'),
	array('16','Reklas Barang'),
	
	array('17','Pemindahtanganan Penjualan'),
	array('18','Tuntutan Ganti Rugi'),
	array('19','Kemitraan dengan Pihak Tiga'),
	array('20','Aset Tidak Berwujud'),
	array('21','Reklas Aset Lain-lain'),//ke/dari aset lain2
	 
	array('22','Kapitalisasi'), //ke/dari intra/extra
	
	array('23','Koreksi Aset Lain-lain'),
	array('24','Koreksi Kapitalisasi'),
		
	array('25','Koreksi Asal Usul'), //?
	array('26','Pemindahtanganan hibah'),
	array('27','Pemindahtanganan lainnya'),
	array('28','Perolehan Aset Lain-lain'),//?
	array('29','Kemitraan dengan Pihak Ketiga berakhir'),
	array('30', 'Penyusutan'),
	array('31', 'Koreksi Penyusutan' ),
    array('32', 'Pemindahtanganan Penyertaan Modal'),
	array('33', 'Pembayaran Ganti Rugi' ),
    array('34', 'Perubahan Kondisi'),
	array('35','Mutasi Balai'),
	array('36','Atribusi'),
	array('37','Penghapusan Karena Penggabungan'),
	array('38','Penghapusan Karena Koreksi'),
	array('39','Pemusnahan'),
    array('40','Reklas Persediaaan'),
	
);

$Main->jnsTrans3 = array(
	array('1','Perolehan'),
	array('2',''),	
	array('3','Pemeliharaan'),
	array('4','Pengamanan'),
	array('5','Hapus Sebagian'),
	array('6','Koreksi'),
	array('7','Penilaian'),
);

/*$Main->StatusAsetView = array( 
	array("1","Intrakomptabel"),
	array("2","Aset Lancar"), 
	array("3","Aset Tetap"), f,g
	array("4","Aset Lainnya"), 
	array("5","Tagihan Penjualan Angsuran"),  f,g = 07.21
	array("6","Tuntutan Ganti Rugi"),  f,g = 07.22
	array("7","Kemitraan dengan Pihak Tiga"), f,g = 07.23
	array("8","Aset Tidak Berwujud"), f,g = 07.24
	array("9","Aset Lain-lain"),  f,g = 07.25
	array("10","Ekstrakomptabel")staset 10
);
*/

//$Main->MODUL_JURNAL = FALSE;
//$Main->MODUL_HISTORY = FALSE;

//$Main->REKON=FALSE;


/* default */
$Main->MODUL_SUBUNIT = TRUE;
$Main->SUBUNIT_DIGIT = 3;		
$Main->KODEBARANGJ = '000';
$Main->KODEBARANGJ_DIGIT = 3; //digit 
$Main->NOREG_SIZE = 4;
$Main->NOREG_SAMPLE = '0002';
$Main->NOREG_MAX = 9999;
$Main->PENYUSUTAN = TRUE;
$Main->TAHUN_MULAI_SUSUT = 0;
$Main->MODUL_AKUNTANSI = TRUE;
$Main->MODUL_ASET_LAINNYA = TRUE;		
$Main->MODUL_SENSUS = TRUE;
$Main->REKAP_NERACA_2 = TRUE;//rekap neraca kertas kerja, rekap neraca 1 = jabar
$Main->MENU_PERBANDINGAN = TRUE;
$Main->MODUL_HISTORY = TRUE;
$Main->PENYUSUTAN = TRUE;
$Main->STASET_OTOMATIS = TRUE;
$Main->MODUL_ASETLAINLAIN = TRUE;

$Main->MODUL_KAPITALISASI = TRUE;
$Main->MODUL_KOREKSI = TRUE;
$Main->MODUL_KONDISI = TRUE;
$Main->MODE_EDIT_SKPD = 1; 
$Main->STATUS_SURVEY = 1;
$Main->TAMPIL_SUSUT = 0; //ket : 1 untuk di tampilkan, 0 untuk tidak ditampilkan

$Main->WITH_THN_ANGGARAN = TRUE;
$Main->DOWNLOAD_MOBILE = TRUE;
$Main->APK_FILE = "ATISISBADA_KabSrg.apk";

$Main->SUSUT_MODE=9;
//0. mode 2 dihitung tahun, nilai susut sem dibagi2, disimpan per sem; mode 1/kosong = dihitung per bulan, bulan berikut sudah penyusutan, disimpan per semester
$Main->JURNAL_FISIK = 1;

$Main->MODUL_JMLGAMBAR = TRUE;
$Main->TAMPIL_BIDANG = TRUE;
$Main->MODUL_BAST = TRUE;
$Main->MODUL_SPK = TRUE;
$Main->MODUL_IDBARANG = TRUE;


//untuk perencanaan -------------------------------------
$Main->KODE_BELANJA_MODAL = '5.2';
$Main->KODE_BELANJA_BJ = '5.1.2';
$Main->KODE_PENDAPATAN_HIBAH = '4.3.1'; // u/ perolehan hibah?
$Main->KODE_BELANJA='5';
$Main->KODE_ASET_TETAP = '1.3';
$Main->KODE_AKUMSUSUT = '1.3.7';

//PP27 -----------------------------------------------
//$Main->PP27_MAINMENU = TRUE;//
$Main->PP27_PERENCANAAN = TRUE;
$Main->PP27_PENGADAAN = TRUE;
//$Main->PP27_PENERIMAAN = TRUE;
//$Main->PP27_PENGGUNAAN = TRUE;
//$Main->PP27_PEMANFAATAN = TRUE;
//$Main->PP27_PEMELIHARA = TRUE;
//$Main->PP27_PENILAIAN = TRUE;
//$Main->PP27_PENGHAPUSAN = TRUE;
//$Main->PP27_PEMINDAHTANGAN = TRUE;
$Main->PP27_GANTIRUGI = TRUE;

$Main->MODUL_PENGGUNAAN = TRUE;
$Main->MODUL_BAST = TRUE;
$Main->MODUL_SPK = TRUE;
$Main->MODUL_IDBARANG = TRUE;

$Main->REF_URUSAN = 0;
$Main->REFSKPD_URUSAN = 0;

$Main->CLOSING_SKPD=1; //0 clsoing perskpd

$Main->MODUL_PEMANFAATAN_BERAKHIR =1;
$Main->MENU_VERSI =0; //0=defaulkt, 2 pp27, 3= mantap

//$Main->VERSI_NAME = 'TASIKMALAYA_KAB'; //GARUT, SERANG, JABAR, BOGOR, BDG_BARAT, TASIKMALAYA_KAB
$Main->VERSI_NAME = 'BDG_BARAT';
//$Main->VERSI_NAME = 'PANDEGLANG';
switch ($Main->VERSI_NAME){
	case 'BOGOR':{
		$Main->MODUL_SUBUNIT = TRUE;
		$Main->SUBUNIT_DIGIT = 3;		
		$Main->KODEBARANGJ = '000';
		$Main->KODEBARANGJ_DIGIT = 3; //digit 
		$Main->NOREG_SIZE = 4;
		$Main->NOREG_SAMPLE = '0002';
		$Main->NOREG_MAX = 9999;
		$Main->PENYUSUTAN = TRUE;
		$Main->TAHUN_MULAI_SUSUT = 0;
		$Main->MODUL_AKUNTANSI = TRUE;
		$Main->MODUL_ASET_LAINNYA = TRUE;		
		$Main->MODUL_SENSUS = FALSE;
		$Main->MENU_PERBANDINGAN = FALSE;
		$Main->MODUL_HISTORY = FALSE;
		$Main->STASET_OTOMATIS = TRUE;
		break;
	}
	case 'SERANG' :{
		$Main->MODUL_SUBUNIT = TRUE; //seksi
		$Main->SUBUNIT_DIGIT = 3;		
		$Main->KODEBARANGJ = '000';
		$Main->KODEBARANGJ_DIGIT = 3; //digit 
		$Main->NOREG_SIZE = 4;
		$Main->NOREG_SAMPLE = '0002';
		$Main->NOREG_MAX = 9999;
		$Main->PENYUSUTAN = TRUE;
		$Main->TAHUN_MULAI_SUSUT = 0;
		$Main->MODUL_AKUNTANSI = TRUE;
		$Main->MODUL_ASET_LAINNYA = TRUE;		
		$Main->MODUL_SENSUS = TRUE;
		$Main->REKAP_NERACA_2 = TRUE;
		$Main->MENU_PERBANDINGAN = TRUE;
		$Main->MODUL_HISTORY = TRUE;
		$Main->SUSUT_MODE=2;// dihitung tahun, utk sem dibagi2
		
		break;
	}
	case 'BDG_BARAT':{
		$Main->MODUL_SUBUNIT = TRUE;	
		$Main->SUBUNIT_DIGIT = 3;		
		$Main->KODEBARANGJ = '000';
		$Main->KODEBARANGJ_DIGIT = 3; //digit 
		$Main->NOREG_SIZE = 4;
		$Main->NOREG_SAMPLE = '0002';
		$Main->NOREG_MAX = 9999;
		$Main->PENYUSUTAN = TRUE;
		$Main->TAHUN_MULAI_SUSUT = 0;
		$Main->MODUL_AKUNTANSI = TRUE;
		$Main->MODUL_ASET_LAINNYA = TRUE;		
		$Main->MODUL_SENSUS = TRUE;
		$Main->REKAP_NERACA_2 = TRUE;
		$Main->MENU_PERBANDINGAN = TRUE;
		$Main->MODUL_HISTORY = TRUE;
		$Main->STASET_OTOMATIS = TRUE;	
		break;
	}
	case 'GARUT':{
		$Main->MODUL_SUBUNIT = TRUE;
		$Main->TAHUN_MULAI_SUSUT = 0;		
		break;
	}
	default:{//all
		
		break;
	}
	

	
	
	
}
$Main->REKAP_KONDISI = 0;
$Main->MODUL_PROGRAM = 0;

?>