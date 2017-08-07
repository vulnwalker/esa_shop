<?php

//error_reporting(0);
set_time_limit(0);



$HTTP_SERVER_VARS = isset($_SERVER)? $_SERVER : @$HTTP_SERVER_VARS;
$HTTP_POST_VARS = isset($_POST)? $_POST : @$HTTP_POST_VARS;
$HTTP_GET_VARS = isset($_GET)? $_GET : @$HTTP_GET_VARS;
$HTTP_COOKIE_VARS = isset($_COOKIE)? $_COOKIE : @$HTTP_COOKIE_VARS;

$Main->HeadScript ='
	<script language="JavaScript" src="lib/js/JSCookMenu_mini.js" type="text/javascript"></script>
	<script language="JavaScript" src="lib/js/ThemeOffice/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="lib/js/joomla.javascript.js" type="text/javascript"></script>
	
	<script src="js/jquery.js" type="text/javascript"></script>	
	<script language="JavaScript" src="js/ajaxc2.js" type="text/javascript"></script>
	<script language="JavaScript" src="dialog/dialog.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/global.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/base.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/encoder.js" type="text/javascript"></script>	
	<script language="JavaScript" src="lib/chatx/chatx.js" type="text/javascript"></script>
	<script src="js/jquery-ui.custom.js"></script>
	<script src="js/daftarobj.js" type="text/javascript"></script>
	<script src="js/pageobj.js" type="text/javascript"></script>	
	<script src="js/pindahtangan.js" type="text/javascript"></script>
	<script src="js/ruang.js" type="text/javascript"></script>
	<script src="js/pegawai.js" type="text/javascript"></script>	
	<script src="js/sensus.js" type="text/javascript"></script>	
		<script src="js/skpd.js" type="text/javascript"></script>	
		<script src="js/reclass.js" type="text/javascript"></script>	
		<script src="js/asetlainlain.js" type="text/javascript"></script>	
		<script src="js/kapitalisasi.js" type="text/javascript"></script>	
		<script src="js/koreksi.js" type="text/javascript"></script>
		<script src="js/kondisi/kondisi.js" type="text/javascript"></script>	
		<script src="js/master/refstatusbarang/refstatusbarang.js" type="text/javascript"></script>
		<script src="js/master/refprogram/refprogram.js" type="text/javascript"></script>
		
		<!--<script>
			$(document).ready(function(){
					    	$(".toggler").click(function(e){
						        //e.preventDefault();
						        //$("#det_"+$(this).attr("data-prod-cat")).toggle();
								alert("tes");
						    });
						});
		</script>				-->
			
	';
	/*<!--
	  <script type=\"text/javascript\" src=\"js/jscalendar-1.0/calendar.js\"></script>
	  <script type=\"text/javascript\" src=\"js/jscalendar-1.0/lang/calendar-id.js\"></script>
	  <script type=\"text/javascript\" src=\"js/jscalendar-1.0/calendar-setup.js\"></script>	  
	  <script type=\"text/javascript\">
	-->
	  */

$Main->HeadStyle = "$Main->HeadStyleico	
	<link rel='stylesheet' href='css/menu.css' type='text/css' />
	<link rel='stylesheet' href='css/template_css.css' type='text/css' />
	<link rel='stylesheet' href='css/theme.css' type='text/css' />
	<link rel='stylesheet' href='dialog/dialog.css' type='text/css' />
	<link rel='stylesheet' href='lib/chatx/chatx.css' type='text/css' />
	<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>	
	<link rel='stylesheet' href='css/base.css' type='text/css' />
	<!--<link rel='stylesheet' href='css/sislog.css' type='text/css' />-->
	<!--<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"js/jscalendar-1.0/calendar-win2k-cold-1.css\" title=\"win2k-cold-1\" />-->
	
	";

$Main->UserModul = array("Disabled","Write","Read");
$Main->UserLevel = array("Guest","Adminisrator","Operator");

$Main->NavAtas="";
$Main->NavBawah="";
$Main->Isi = "";
$Main->ImageLeft="";

$Main->StatusBarang = array(
	array("1","Inventaris"),
	array("2","Pemanfaatan"),
	array("3","Penghapusan"),
	array("4","Pemindahtanganan"),
	array("5","Tuntutan Ganti Rugi")
);
$Main->StatusAsetView = array( 
	array("1","Intrakomptabel"),
	array("2","Aset Lancar"),
	array("3","Aset Tetap"),
	array("4","Aset Lainnya"),
	array("5","Tagihan Penjualan Angsuran"),
	array("6","Tuntutan Ganti Rugi"),
	array("7","Kemitraan dengan Pihak Ke Tiga"),
	array("8","Aset Tidak Berwujud"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel"),
	array("11","Pemindahtangan tukar menukar"), //jnstrans=7
	array("12","Pemindahtangan hibah"), //jnstrans =4	
	array("13","Pemindahtangan penyertaan modal"), //jnstrans=7
    array("14","pemusnahan"), //
);
$Main->StatusGambar = array(	
	array("1", "Ada Gambar"),
	array("2", "Tidak Ada"),
);
$Main->StatusAset = array(
	array("3","Intrakomptabel"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel")
);
$Main->StatusAsetTPA = array(
	array("5","Intrakomptabel"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel")
);
$Main->StatusAsetTGR = array( //form edit
	array("6","Intrakomptabel"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel")
);
$Main->StatusAsetMITRA = array(
	array("7","Intrakomptabel"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel")
);
$Main->StatusAsetATB = array(
	array("8","Intrakomptabel"),
	array("9","Aset Lain-lain"),
	array("10","Ekstrakomptabel")
);

$Main->StatusBarangView = array(
	array("1",'Semua Status'),
	array("2","Pemanfaatan"),
	array("3","Penghapusan"),
	array("4","Pemindahtanganan"),
	array("5","Tuntutan Ganti Rugi")
);
$Main->JenisBarang = array(
	array("1","Pakai habis"),
	array("2","Inventaris")
);

$Main->AsalUsul = array(
array("1","Pembelian"), //diperoleh dari belanja modal
array("2","Hadiah/Hibah"), //diperoleh dari hibah
array("3","Lainnya"),
array("4","Mutasi"), //pindah antar skpd , skpd level 2
array("5","Reclass"),
array("6","Kapitalisasi"), //barang di beli bukan dari belanja modal, di rekap neraca muncul di kolom kapitalisasi debet
array("7","Mutasi Balai"), //pindah antar balai skpd level 3/4
//array("7","Koreksi"),//? utk barng thn perolehan lama yg baru di entry skr, di detek dari tahun tgl buku <> thn perolehan 
//array("7","Penggabungan"), //?
);

$Main->KondisiBarang = array(
array("1","Baik"),
array("2","Kurang Baik"),
array("3","Rusak Berat"));

$Main->StatusHakPakai = array(
array("1","Pakai"),
array("2","Pengelolaan"),
array("3","Milik"),
array("4","Guna Bangunan"),
array("5","Guna Usaha"),
array("6","Girik/AJB")
);

$Main->Tingkat = array(
array("1","Bertingkat"),
array("2","Tidak"));

$Main->Beton = array(
array("1","Beton"),
array("2","Tidak"));

$Main->StatusTanah = array(
array("1","Milik PEMDA"),
array("2","Milik Negara"),
array("3","Tanah Ulayat"),
array("4","Tanah Hak"));

$Main->BentukPemanfaatan = array(
array("1","Pinjam Pakai"),
array("2","Sewa"),
array("3","Kerjasama Pemanfaatan"),
array("4","Bangun Guna Serah"),
array("5","Bangun Serah Guna"),
array("6","Kerjasama Penyediaan Infrastruktur")
);

$Main->Bangunan = array(
array("1","Permanen"),
array("2","Semi Permanen"),
array("3","Darurat"));

$Main->JenisPengamanan = array(
array("1","Administratif"),
array("2","Fisik"),
array("3","Tindakan Hukum"));

$Main->BentukPemindahtanganan = array(
array("1","Penjualan"),
array("2","Tukar menukar"),
array("3","Hibah"),
array("4","Penyertaan Modal"));

$Main->Modul = array(
"00"=>array("","",""),
"01"=>array("01","Perencanaan Kebutuhan dan Penganggaran","tempate_01.gif"),
"02"=>array("02","Pengadaan","pengadaan_01.gif"),
"03"=>array("03","Penerimaan, Penyimpanan dan Penyaluran","penerimaan_01.gif"),
"04"=>array("04","Penggunaan","penerimaan_01.gif"),
//"05"=>array("05","Penatausahaan","penatausahaan_01.gif"),
//"06"=>array("06","Pemanfaatan","pemanfaatan_01.gif"),
//"07"=>array("07","Pengamanan dan Pemeliharaan","pengamanan_01.gif"),
//"08"=>array("08","Penilaian","penilaian_01.gif"),
//"09"=>array("09","Penghapusan","penghapusan_01.gif"),
//"10"=>array("10","Pemindahtanganan","pemindahtanganan_01.gif"),
"05"=>array("05","Penatausahaan","penatausahaan_ico.gif"),
"06"=>array("06","Pemanfaatan","pemanfaatan_ico.gif"),
"07"=>array("07","Pengamanan dan Pemeliharaan","pengamanan_ico.gif"),
"08"=>array("08","Penilaian","penilaian_ico.gif"),
"09"=>array("09","Penghapusan","penghapusan_ico.gif"),
"10"=>array("10","Pemindahtanganan","pemindahtanganan_ico.gif"),

"11"=>array("11","Pembiayaan","pembiayaan_01.gif"),
"12"=>array("12","Tuntutan Ganti Rugi","gantirugi_ico.gif"),
"13"=>array("13","Pembinaan Pengawasan dan Pengendalian","pengawasan_01.gif"),
"14"=>array("14","Referensi Data","masterData_01.gif"),
"15"=>array("15","Administrasi","penggunaan_01.gif"),
"16"=>array("16","Laporan-laporan","penggunaan_01.gif"),
"ref"=>array("14","Referensi Data","masterData_01.gif"),
"Admin"=>array("15","Administrasi","administrasi_01.gif"),
"Menu"=>array("16","Chating","cpanel.png")
);

$Main->KIB = array(
"01"=>array("01","KIB A"),
"02"=>array("02","KIB B"),
"03"=>array("03","KIB C"),
"04"=>array("04","KIB D"),
"05"=>array("05","KIB E"),
"06"=>array("06","KIB F")
);

$Ref->NamaBulan  = array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
$Ref->NamaBulan2  = array(
	array("01","Januari"),
	array("02","Pebruari"),
	array("03","Maret"),
	array("04","April"),
	array("05","Mei"),
	array("06","Juni"),
	array("07","Juli"),
	array("08","Agustus"),
	array("09","September"),
	array("10","Oktober"),
	array("11","Nopember"),
	array("12","Desember")
);

$Main->UserModul = array("Disabled","Write","Read");
$Main->UserLevel = array("Guest","Adminisrator","Operator");


$view->listUrut = array(
	array("nmopd","OPD"),
	array("thn_perolehan","Tahun"),
	array("kondisi","Kondisi")			
);


$Main->bersertifikat = array( //?
	array("1","Bersertifikat"),
	array("2","Proses Bersertifikat"),
	array("3","Belum Bersertifikat")
);
$Main->StatusSertifikat = array(
	array("1","Bersertifikat"),
	array("2","Proses Bersertifikat")	
);

$Main->kondisi_bangunan = array(
	array("1","Baik"),
	array("2","Kurang Baik"),
	array("3","Rusak Berat")
);

$Main->ArKriteriaHapus = array(
	array("0","Penghapusan"), //default
	array("1","Mutasi")	
);

$Main->ArSemester = array(
	//array("0","Semester I"), default
	array("1","Semester II")
);

$Main->ArSemester1 = array(
	//array("0","Semester I"), default
	array("1","Semester II"),
	array("2","Pertahun")
);


$Main->ArKIB = array(
	//array("0","Semua"), default
	array("01","KIB A"),
	array("02","KIB B"),
	array("03","KIB C"),
	array("04","KIB D"),
	array("05","KIB E"),
	array("06","KIB F")
);

$Main->ArBentukManfaat = array(
	//array("0","Semua"), default
	array("1","Pinjam Pakai"),
	array("2","Sewa"),
	array("3","Kerjasama Pemanfaatan"),
	array("4","Bangun Guna Serah"),
	array("5","Bangun Serah Guna"),
	array("6","Kerjasama Penyediaan Infrastruktur")
);

$Main->FieldEntryLabel = array(	
	"fmKEPEMILIKAN" => "Kepemilikan",
	"fmSKPD" => "Bidang",
	"fmUNIT" => "Unit Bidang",
	"fmSUBUNIT" => "Sub Unit",
	"fmIDBARANG" => "Kode Barang",
	"fmNMBARANG" => "Nama Barang",
	"fmREGISTER" => "No. Register",
	"fmTAHUNPEROLEHAN" => "Tahun Perolehan",
	"fmJUMLAHBARANG" => "Jumlah Barang",
	"fmSATUAN" => "Satuan",	
	"fmHARGABARANG" => "Harga Perolehan",
	"fmASALUSUL" => "Asal Usul",
	"fmSTATUSBARANG" => "Status Barang",
	"fmTGLUPDATE" => "Tgl. Update",
	"fmTAHUNANGGARAN" => "Tahun Anggaran",	

	"fmLUAS_KIB_A" => "Luas",
	"fmLETAK_KIB_A" => "Letak/Alamat",
	"alamat_kel" => "Kelurahan",
	"alamat_kec" => "Kecamatan",
	"alamat_b" => "Kota/Kabupaten",
	"fmHAKPAKAI_KIB_A" => "Hak Pakai",
	"fmTGLSERTIFIKAT_KIB_A" => "Tanggal Sertifikat",
	"fmNOSERTIFIKAT_KIB_A" => "No. Sertifikat",
	"fmPENGGUNAAN_KIB_A" => "Penggunaan",
	"fmKET_KIB_A" => "Keterangan",
	"fmMERK_KIB_B" => "Merk",
	"fmUKURAN_KIB_B" => "Ukuran",
	"fmBAHAN_KIB_B" => "Bahan",
	"fmPABRIK_KIB_B" => "No. Pabrik",
	"fmRANGKA_KIB_B" => "No. Rangka",
	"fmMESIN_KIB_B" => "No. Mesin",
	"fmPOLISI_KIB_B" => "No. Polisi",
	"fmKET_KIB_B" => "Keterangan",
	"fmBPKB_KIB_B" => "No. BPKB",
	
	"fmKONDISI_KIB_C" => "Kondisi Bangunan",
	"fmTINGKAT_KIB_C" => "Bertingkat/Tidak",
	"fmBETON_KIB_C" => "Beton/Tidak",
	"fmLUASLANTAI_KIB_C" => "Luas Lantai",
	"fmLETAK_KIB_C" => "Letak/Alamat",
	"fmTGLGUDANG_KIB_C" => "Tgl. Gudang",
	"fmNOGUDANG_KIB_C" => "No. Gudang",
	"fmLUAS_KIB_C" => "Luas",
	"fmSTATUSTANAH_KIB_C" => "Status Tanah",
	"fmNOKODETANAH_KIB_C" => "No. Kode Tanah",
	
	"fmKET_KIB_C" => "Keterangan",
	
	"fmKONSTRUKSI_KIB_D" => "Konstruksi",
	"fmPANJANG_KIB_D" => "Panjang",
	"fmLEBAR_KIB_D" => "Lebar",
	"fmLUAS_KIB_D" => "Luas",
	"fmALAMAT_KIB_D" => "Letak/Alamat",
	"fmTGLDOKUMEN_KIB_D" => "Tgl. Dokumen",
	"fmNODOKUMEN_KIB_D" => "No. Dokumen",
	"fmSTATUSTANAH_KIB_D" => "Status Tanah",
	"fmNOKODETANAH_KIB_D" => "No. Kode Tanah",
	"fmKONDISI_KIB_D" => "Kondisi",
	"fmKET_KIB_D" => "Keterangan",
		
	"fmJUDULBUKU_KIB_E" => "Judul/Pencipta Buku",
	"fmSPEKBUKU_KIB_E" => "Spesifikasi Buku",
	"fmSENIBUDAYA_KIB_E" => "Asal Daerah",
	"fmSENIPENCIPTA_KIB_E" => "Pencipta",
	"fmSENIBAHAN_KIB_E" => "Bahan",
	"fmJENISHEWAN_KIB_E" => "Jenis Hewan",
	"fmUKURANHEWAN_KIB_E" => "Ukuran Hewan",
	"fmKET_KIB_E" => "Keterangan",
			
	"fmBANGUNAN_KIB_F" => "Bangunan",
	"fmTINGKAT_KIB_F" => "Bertingkat/Tidak",
	"fmBETON_KIB_F" => "Beton/Tidak",
	"fmLUAS_KIB_F" => "Luas",
	"fmLETAK_KIB_F" => "Letak/Alamat",
	"fmTGLDOKUMEN_KIB_F" => "Tgl. Dokumen",
	"fmNODOKUMEN_KIB_F" => "No. Dokumen",	
	"fmTGLMULAI_KIB_F" => "Tgl. Mulai",	
	"fmSTATUSTANAH_KIB_F" => "Status Tanah",	
	"fmNOKODETANAH_KIB_F" => "No. Kode Tanah",
	"fmKET_KIB_F" => "Keterangan",
	
	"koordinat_gps" => "Koordinat Lokasi",
	"koord_bidang" => "Koordinat Bidang",
	
	"tgl_buku" => "Tanggal Dibukukan",
	"fmKET" => "Keterangan",
	"fmURAIAN" => "Uraian",
	"fmTANGGALPENGHAPUSAN" => "Tanggal Penghapusan",
	"fmTANGGALPEMANFAATAN" => "Tanggal Pemanfaatan",
	"fmBENTUKPEMANFAATAN" => "Bentuk Pemanfaataan"
);

$Main->ArYaTidak = array(	
	array('1','Tidak'),
	array('2','Ya')	
);

$Main->ArrAda = array(
	array("1","Ada"),
	array("2","Tidak Ada"),
);

$Main->arStatusPenguasaan = array(
	array('1','Digunakan'),
	array('2','Dimanfaatkan'), 
	array('3','Iddle'),
	array('4','Dikuasai Pihak Ketiga'),
	array('5','Sengketa'),
);
 


$Main->batas = "<div style='float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22'></div>"; //batas kolom filter
$Main->baris = "<div style='border-top: 1px solid #E5E5E5;height:1'></div>"; //batas baris filter

$Main->arJnsPelihara = array(
	array('1','Pemeliharaan Ringan'),
	array('2','Pemeliharaan Sedang'), 
	array('3','Pemeliharaan Berat')	//array('4','Nama Rekening')
);


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

$Main->thnsensus_default = 2014;
$Main->periode_sensus = 5;// tahun
$Main->defTglBukuBelumSensus = '2013-12-31';


$Main->TAHUN_MULAI_SUSUT = 0;
$Main->PENYUSUTAN = TRUE;
$Main->SUSUT_REHAB = TRUE;
$Main->MIN_INTRA = 500000;
$Main->PENYUSUTAN_BLN = TRUE;

$Main->versi = '2.0.7.0';

$Main->SHOW_CEK = TRUE; //FALSE;
$Main->KODEBARANGJ = '000';
$Main->KODEBARANGJ_DIGIT = 3; //digit 

$Main->NOREG_SIZE = 6;
$Main->NOREG_SAMPLE = '000002';
$Main->NOREG_MAX = 999999;

/*
pemindahtangan 	1.5.1.1
TGR 		1.5.1.2
pemanfaatan 	1.5.2
ATB		1.5.3.1 .. 5
amortisasi	1.5.3.6.1 .. 5
Aset lain-lain 	1.5.4.1
akum susut 	1.5.4.2
*/

$Main->StatusTGR = array(
array("0","Usulan"),
array("1","Proses"),
array("2","Dihapus"));

$Main->JenisHibah = array( //1=Komite, DAK, BANPROV, blockgrand, JKN, BOS APBN, BOS APBD
	array('1','Komite'),
	array('2','DAK'),	
	array('3','BANPROV'),
	array('4','Blockgrand'),
	array('5','JKN'),
	array('6','BOS APBN'),
	array('7','BOS APBD'),
	array('8','Wakaf'),
	array('9','Hibah Provinsi'),
	array('10','Hibah Pusat'),
);

?>