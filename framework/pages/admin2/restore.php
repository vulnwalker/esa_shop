<?php
$Submit=CekPOST('Submit');
$fmSKPD=CekPOST('fmSKPD');

//restore.php
$Main->Isi = "Restor data";

$coSKPD=$_COOKIE['coSKPD'];
if ($coSKPD=='00')
{
	$ListSKPD = cmbQuery1("fmSKPD",$coSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' "," ",'Pilih Semua','00');
}
else
{
	$ListSKPD = cmbQuery1("fmSKPD",$coSKPD,"select c,nm_skpd from ref_skpd where c='$coSKPD' and d ='00' and e = '00' "," ",'','');
}

if ($Submit!="Proses Import/Restore")
{
	
	if($Main->MODUL_AKUNTANSI){
		$pesan = 
		"<div id='isipesan' style='display:none'>
		  <table width=\"100%\"><tbody>".
			"<tr><td width='50px'>File </td><td width='10px'>: </td><td width=''><span id='nmfile'>transfer.dat</span></td></tr>".
			"<tr><td width='50px'>Tanggal </td><td width='10px'>: </td><td width=''>03-09-2014 10:34</td></tr>".
			"<tr><td width='50px'>SKPD </td><td width='10px'>: </td><td width=''>04. SEKRETARIAT DAERAH</td></tr>".
			"<tr><td width='50px'>UPB </td><td width='10px'>: </td><td width=''></td></tr>".
		"</tbody></table></div>";
		$progressbar = 
			"<div id=\"progressbox\" style=\"display: none;background-color: silver;  
				position: relative;  width: 300px;  border-radius: 3px;  text-align: left;\">
				<div id=\"progressbar\" style=\"width: 260;height: 2px;  border-radius: 3px;  
				background-color: green;float: left;margin: 6 4 4 0;    \"></div>
					<div id=\"statustxt\" style=\"color: rgb(0, 0, 0);float: left;\">100%</div>
					<div id=\"output\"></div>	</div>";
			
		$Main->Isi = "
			<center>
			
	
			<form method=post enctype='multipart/form-data'>
					<br><br><B><span style='font-size=14px'>Import Data dari File</span></b>&nbsp;
					<!--<input type=submit name='Submit' value='Import'>  -->
					<input type=\"button\" name=\"Submit\" value=\"Import\" onclick=\"document.getElementById('isipesan').style.display='none';document.getElementById('progressbox').style.display='none';$('#fileinput').click();\">
				<input type='file' id='fileinput' style='display:none' onchange=\"document.getElementById('isipesan').style.display='block';document.getElementById('progressbox').style.display='block';document.getElementById('nmfile').innerHTML=document.getElementById('fileinput').value;\">
			</form>
			<br>
			<b>Pesan</b>
			<div id='pesan' style='border: 1px solid #ddd;width:300px; height:100;margin:6px;padding:4px'>
				$pesan
			</div>
			<table width='300px'>
			<tr><td align='left'><b>Progress</td></tr>
			<tr><td>
			
			$progressbar
			
			</td></tr>
			
			</table>
			";
	}else{
		
		$Main->Isi = "
			<center><br><br><B>Import/Restore Data dari Backup</B><br><br><br><br>
	
			<form method=post enctype='multipart/form-data'>
						Pilih SKPD $ListSKPD
			<br>
			Ambil File Backup <input type=file name='userfile' size=50 ><br><br><br>
			Proses ini akan memakan waktu yang cukup lama, maka Yakinkan tidak ada proses Transaksi Data selama Proses ini.<br><br>
			<i><b>Perhatian!, yakinkan dalam proses ini pemilihan SKPD dan File Backup-nya<br>
			Apabila sudah di proses, data akan tergantikan dengan data Backup yang Anda pilih berdasarkan SKPD dan File yang di pilih</b></i><br><br>
			<input type=submit name='Submit' value='Proses Import/Restore'>
			</form>
			";
	}
	
}
else
{
	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) 
		{
				$TahIeuFilena = $_FILES['userfile']['tmp_name'];
				function gFile($file)
				{	
					$fd = fopen ($file, "r");
					$cIsi = fread ($fd, filesize ($file));
					fclose ($fd);
					return $cIsi;
				}
				$Data = gFile($TahIeuFilena);
				$Data = explode("\n",$Data);
				for ($i=0;$i<count($Data);$i++)
				{
					$ArrData[$i] = explode(":",$Data[$i]);
					$ArrData[$i][0] = eregi_replace("'","",$ArrData[$i][0]);
				}


//Create Tabel Temp;
			$TabelTemp = "
				DROP TABLE IF EXISTS `buku_induk_temp` ;
				CREATE TABLE `buku_induk_temp` (
				  `id` int(9) unsigned,
				  `id_baru` int(9) unsigned NOT NULL AUTO_INCREMENT,
				  `a1` char(2)   NOT NULL,
				  `a` char(2)   NOT NULL,
				  `b` char(2)   NOT NULL,
				  `c` char(2)   NOT NULL,
				  `d` char(2)   NOT NULL,
				  `e` char(2)   NOT NULL,
				  `f` char(2)   NOT NULL,
				  `g` char(2)   NOT NULL,
				  `h` char(2)   NOT NULL,
				  `i` char(2)   NOT NULL,
				  `j` char(2)   NOT NULL,
				  `noreg` char(4)   NOT NULL,
				  `thn_perolehan` char(4) NOT NULL,
				  `jml_barang` int(9) DEFAULT NULL,
				  `satuan` varchar(15)   DEFAULT NULL,
				  `harga` decimal(18,2) DEFAULT NULL,
				  `jml_harga` decimal(18,2) DEFAULT NULL,
				  `asal_usul` char(1)   DEFAULT NULL,
				  `kondisi` char(1)   DEFAULT NULL,
				  `status_barang` char(1)   DEFAULT NULL COMMENT 'inventaris, pemanfaatan, penghapusan, pemindahtanganan, tuntutan ganti rugi',
				  `tgl_update` date DEFAULT NULL,
				  `tahun` char(4)   NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`),
				  KEY `id` (`a1`,`a`,`b`,`c`,`d`,`e`,`noreg`,`f`,`g`,`h`,`i`,`j`,`tahun`)
				);

				DROP TABLE IF EXISTS `dkb_temp`;
				CREATE TABLE `dkb_temp` (
				  `id` int(9) unsigned,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `merk_barang` varchar(200)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `satuan` varchar(15)  NOT NULL,
				  `jml_harga` decimal(18,2) NOT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`tahun`)
				);

				DROP TABLE IF EXISTS `dkpb_temp` ;
				CREATE TABLE `dkpb_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `noreg` char(4)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `satuan` varchar(15)  NOT NULL DEFAULT '',
				  `jml_biaya` decimal(18,2) NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `uraian` varchar(200)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`tahun`)
				);

				DROP TABLE IF EXISTS `gantirugi_temp`;
				CREATE TABLE `gantirugi_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_gantirugi` date DEFAULT NULL,
				  `kepada_nama` varchar(50)  DEFAULT NULL,
				  `kepada_alamat` varchar(50)  DEFAULT NULL,
				  `uraian` varchar(200)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `history_barang_temp`;
				CREATE TABLE `history_barang_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `noreg` char(4)  NOT NULL,
				  `kejadian` text ,
				  `kondisi` char(1)  DEFAULT NULL,
				  `status_barang` char(1)  DEFAULT NULL,
				  `tgl_update` date DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `kib_a_temp` ;
				CREATE TABLE `kib_a_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `luas` int(9) DEFAULT NULL,
				  `alamat` varchar(200)  DEFAULT NULL,
				  `status_hak` char(1)  NOT NULL COMMENT 'hak pakai, hak pengelolaan',
				  `sertifikat_tgl` date NOT NULL,
				  `sertifikat_no` varchar(25)  NOT NULL,
				  `penggunaan` varchar(50)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `kib_b_temp` ;
				CREATE TABLE `kib_b_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `merk` varchar(200)  DEFAULT NULL,
				  `ukuran` varchar(50)  DEFAULT NULL,
				  `bahan` varchar(50)  DEFAULT NULL,
				  `no_pabrik` varchar(50)  DEFAULT NULL,
				  `no_rangka` varchar(50)  DEFAULT NULL,
				  `no_mesin` varchar(50)  DEFAULT NULL,
				  `no_polisi` varchar(50)  DEFAULT NULL,
				  `no_bpkb` varchar(50)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `kib_c_temp` ;
				CREATE TABLE `kib_c_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `kondisi` char(1)  NOT NULL,
				  `kondisi_bangunan` char(1)  NOT NULL,
				  `konstruksi_tingkat` char(1)  NOT NULL,
				  `konstruksi_beton` char(1)  NOT NULL,
				  `luas_lantai` int(9) DEFAULT NULL,
				  `alamat` varchar(200)  DEFAULT NULL,
				  `dokumen_tgl` date DEFAULT NULL,
				  `dokumen_no` varchar(25)  DEFAULT NULL,
				  `luas` int(9) DEFAULT NULL,
				  `status_tanah` char(1)  NOT NULL,
				  `kode_tanah` varchar(25)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `kib_d_temp` ;
				CREATE TABLE `kib_d_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `konstruksi` varchar(50)  DEFAULT NULL,
				  `panjang` int(9) DEFAULT NULL,
				  `lebar` int(9) DEFAULT NULL,
				  `luas` int(9) DEFAULT NULL,
				  `alamat` varchar(200)  DEFAULT NULL,
				  `dokumen_tgl` date DEFAULT NULL,
				  `dokumen_no` varchar(25)  DEFAULT NULL,
				  `status_tanah` char(1)  DEFAULT NULL,
				  `kode_tanah` varchar(25)  DEFAULT NULL,
				  `kondisi` char(1)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `kib_e_temp` ;
				CREATE TABLE `kib_e_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)   NOT NULL,
				  `a` char(2)   NOT NULL,
				  `b` char(2)   NOT NULL,
				  `c` char(2)   NOT NULL,
				  `d` char(2)   NOT NULL,
				  `e` char(2)   NOT NULL,
				  `f` char(2)   NOT NULL,
				  `g` char(2)   NOT NULL,
				  `h` char(2)   NOT NULL,
				  `i` char(2)   NOT NULL,
				  `j` char(2)   NOT NULL,
				  `noreg` char(4)   NOT NULL,
				  `buku_judul` varchar(100)   DEFAULT NULL,
				  `buku_spesifikasi` varchar(200)   DEFAULT NULL,
				  `seni_asal_daerah` varchar(50)   DEFAULT NULL,
				  `seni_pencipta` varchar(50)   DEFAULT NULL,
				  `seni_bahan` varchar(50)   DEFAULT NULL,
				  `hewan_jenis` varchar(50)   DEFAULT NULL,
				  `hewan_ukuran` varchar(50)   DEFAULT NULL,
				  `ket` varchar(200)   DEFAULT NULL,
				  `tahun` char(4)   NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `kib_f_temp` ;
				CREATE TABLE `kib_f_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `bangunan` char(1)  DEFAULT NULL COMMENT 'permanen, setengah permanen, darurat',
				  `konstruksi_tingkat` char(1)  DEFAULT NULL COMMENT 'bertingkat, tidak',
				  `konstruksi_beton` char(1)  DEFAULT NULL COMMENT 'beton, tidak',
				  `luas` int(9) DEFAULT NULL,
				  `alamat` varchar(200)  DEFAULT NULL,
				  `dokumen_tgl` date DEFAULT NULL,
				  `dokumen_no` varchar(25)  DEFAULT NULL,
				  `tmt` date DEFAULT NULL,
				  `status_tanah` varchar(50)  DEFAULT NULL,
				  `kode_tanah` char(1)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);


				DROP TABLE IF EXISTS `kir_temp` ;
				CREATE TABLE `kir_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `p` char(3)  NOT NULL,
				  `q` char(3)  NOT NULL,
				  `thn_perolehan` char(4)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tgl_update` date NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`id_bukuinduk`,`p`,`q`)
				);

				DROP TABLE IF EXISTS `pemanfaatan_temp` ;
				CREATE TABLE `pemanfaatan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_pemanfaatan` date DEFAULT NULL,
				  `bentuk_pemanfaatan` char(1)  DEFAULT NULL,
				  `kepada_instansi` varchar(50)  DEFAULT NULL,
				  `kepada_alamat` varchar(50)  DEFAULT NULL,
				  `kepada_nama` varchar(50)  DEFAULT NULL,
				  `kepada_jabatan` varchar(50)  DEFAULT NULL,
				  `surat_no` varchar(50)  DEFAULT NULL,
				  `surat_tgl` date DEFAULT NULL COMMENT 'inventaris, pemanfaatan, penghapusan, pemindahtanganan, tuntutan ganti rugi',
				  `jangkawaktu` int(4) DEFAULT NULL,
				  `biaya_pemanfaatan` decimal(18,0) DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `pembiayaan_temp` ;
				CREATE TABLE `pembiayaan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_pembiayaan` date DEFAULT NULL,
				  `biaya_barang` decimal(18,0) DEFAULT NULL,
				  `bukti_pembiayaan` varchar(100)  DEFAULT NULL,
				  `k` char(1)  DEFAULT NULL,
				  `l` char(1)  DEFAULT NULL,
				  `m` char(1)  DEFAULT NULL,
				  `n` char(2)  DEFAULT NULL,
				  `o` char(2)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `pemeliharaan_temp` ;
				CREATE TABLE `pemeliharaan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_pemeliharaan` date DEFAULT NULL,
				  `jenis_pemeliharaan` varchar(100)  DEFAULT NULL,
				  `pemelihara_instansi` varchar(50)  DEFAULT NULL,
				  `pemelihara_alamat` varchar(50)  DEFAULT NULL,
				  `surat_no` varchar(50)  DEFAULT NULL,
				  `surat_tgl` date DEFAULT NULL COMMENT 'inventaris, pemanfaatan, penghapusan, pemindahtanganan, tuntutan ganti rugi',
				  `biaya_pemeliharaan` decimal(18,0) DEFAULT NULL,
				  `bukti_pemeliharaan` varchar(50)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `pemindahtanganan_temp` ;
				CREATE TABLE `pemindahtanganan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_pemindahtanganan` date DEFAULT NULL,
				  `bentuk_pemindahtanganan` char(1)  DEFAULT NULL,
				  `kepada_nama` varchar(50)  DEFAULT NULL,
				  `kepada_alamat` varchar(50)  DEFAULT NULL,
				  `uraian` varchar(200)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `penerimaan_temp` ;
				CREATE TABLE `penerimaan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_pengadaan` int(9) unsigned,
				  `id_pengadaan_baru` int(9) unsigned,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `id_gudang` char(4)  NOT NULL,
				  `tgl_penerimaan` date NOT NULL,
				  `penerima` varchar(50)  NOT NULL,
				  `merk_barang` varchar(200)  NOT NULL,
				  `supplier` varchar(100)  NOT NULL,
				  `faktur_tgl` date NOT NULL,
				  `faktur_no` varchar(25)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `satuan` varchar(15)  NOT NULL,
				  `jml_harga` decimal(18,2) NOT NULL,
				  `ba_no` varchar(25)  NOT NULL,
				  `ba_tgl` date NOT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `jenis_barang` char(1)  NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`id_pengadaan`,`a`,`b`,`c`,`d`,`e`,`id_gudang`)
				);

				DROP TABLE IF EXISTS `penetapan_temp` ;
				CREATE TABLE `penetapan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_pengeluaran` int(9),
				  `id_pengeluaran_baru` int(9),
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `skgub_tgl` date NOT NULL,
				  `skgub_no` varchar(25)  NOT NULL,
				  `merk_barang` varchar(200)  DEFAULT NULL,
				  `ukuran` varchar(50)  DEFAULT NULL,
				  `bahan` varchar(50)  DEFAULT NULL,
				  `tgl_beli` date DEFAULT NULL,
				  `jml_barang` int(9) DEFAULT NULL,
				  `satuan` varchar(15)  DEFAULT NULL,
				  `harga` decimal(18,2) DEFAULT NULL,
				  `jml_harga` decimal(18,2) DEFAULT NULL,
				  `jml_baik` int(9) DEFAULT NULL,
				  `jml_kbaik` int(9) DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`id_pengeluaran`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`)
				);

				DROP TABLE IF EXISTS `pengadaan_temp` ;
				CREATE TABLE `pengadaan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `spk_tgl` date NOT NULL COMMENT 'SPK/Perjanjian/Kontrak',
				  `spk_no` varchar(25)  NOT NULL DEFAULT '' COMMENT 'SPK/Perjanjian/Kontrak',
				  `dpa_tgl` date NOT NULL,
				  `dpa_no` varchar(25)  NOT NULL,
				  `pt` varchar(50)  NOT NULL,
				  `merk_barang` varchar(200)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `satuan` varchar(15)  NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `jml_harga` decimal(18,2) NOT NULL,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`k`,`l`,`m`,`n`,`o`)
				) ;

				DROP TABLE IF EXISTS `pengadaan_pemeliharaan_temp` ;
				CREATE TABLE `pengadaan_pemeliharaan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `noreg` char(4)  NOT NULL,
				  `spk_tgl` date NOT NULL COMMENT 'SPK/Perjanjian/Kontrak',
				  `spk_no` varchar(25)  NOT NULL DEFAULT '' COMMENT 'SPK/Perjanjian/Kontrak',
				  `dpa_tgl` date NOT NULL,
				  `dpa_no` varchar(25)  NOT NULL,
				  `pt` varchar(50)  NOT NULL,
				  `merk_barang` varchar(50)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `satuan` varchar(15)  NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `jml_harga` decimal(18,2) NOT NULL,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `uraian` varchar(100)  DEFAULT NULL,
				  `ket` varchar(100)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`k`,`l`,`m`,`n`,`o`)
				) ;

				DROP TABLE IF EXISTS `pengamanan_temp` ;
				CREATE TABLE `pengamanan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_pengamanan` date DEFAULT NULL,
				  `jenis_pengamanan` char(1)  DEFAULT NULL,
				  `uraian_kegiatan` varchar(200)  DEFAULT NULL,
				  `pengaman_instansi` varchar(50)  DEFAULT NULL,
				  `pengaman_alamat` varchar(50)  DEFAULT NULL,
				  `surat_no` varchar(50)  DEFAULT NULL,
				  `surat_tgl` date DEFAULT NULL COMMENT 'inventaris, pemanfaatan, penghapusan, pemindahtanganan, tuntutan ganti rugi',
				  `biaya_pengamanan` decimal(18,0) DEFAULT NULL,
				  `bukti_pengamanan` varchar(50)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);


				DROP TABLE IF EXISTS `pengeluaran_temp` ;
				CREATE TABLE `pengeluaran_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_penerimaan` int(9) unsigned ,
				  `id_penerimaan_baru` int(9) unsigned ,
				  `sk_tgl` date NOT NULL,
				  `sk_no` varchar(25)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `id_gudang` char(4)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `satuan` varchar(15)  NOT NULL DEFAULT '',
				  `harga` decimal(18,2) NOT NULL,
				  `jml_harga` decimal(18,2) NOT NULL,
				  `tgl_penyerahan` date NOT NULL,
				  `merk_barang` varchar(200)  NOT NULL,
				  `seri_pabrik` varchar(25)  NOT NULL DEFAULT '',
				  `ukuran` varchar(9)  NOT NULL,
				  `bahan` varchar(25)  NOT NULL,
				  `untuk` varchar(100)  NOT NULL,
				  `thn_buat` char(4)  NOT NULL,
				  `kondisi` char(1)  NOT NULL,
				  `jenis_barang` char(1)  NOT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`),
				  KEY `secondary` (`id_penerimaan`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`id_gudang`)
				);

				DROP TABLE IF EXISTS `penghapusan_temp` ;
				CREATE TABLE `penghapusan_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_penghapusan` date DEFAULT NULL,
				  `uraian` varchar(200)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				) ;

				DROP TABLE IF EXISTS `penilaian_temp` ;
				CREATE TABLE `penilaian_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `id_bukuinduk` int(9) unsigned ,
				  `id_bukuinduk_baru` int(9) unsigned ,
				  `a1` char(2)  NOT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `noreg` char(4)  NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `tgl_penilaian` date DEFAULT NULL,
				  `nilai_barang` decimal(18,0) DEFAULT NULL,
				  `penilai_instansi` varchar(50)  DEFAULT NULL,
				  `penilai_alamat` varchar(100)  DEFAULT NULL,
				  `surat_no` varchar(50)  DEFAULT NULL,
				  `surat_tgl` date DEFAULT NULL COMMENT 'inventaris, pemanfaatan, penghapusan, pemindahtanganan, tuntutan ganti rugi',
				  `ket` varchar(200)  DEFAULT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a1`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`noreg`,`tahun`)
				);

				DROP TABLE IF EXISTS `rkb_temp` ;
				CREATE TABLE `rkb_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `merk_barang` varchar(200)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `satuan` varchar(15)  NOT NULL DEFAULT '',
				  `jml_harga` decimal(18,2) NOT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`tahun`)
				);

				DROP TABLE IF EXISTS `rkpb_temp` ;
				CREATE TABLE `rkpb_temp` (
				  `id` int(9) unsigned ,
				  `id_baru` int(9) unsigned  NOT NULL AUTO_INCREMENT,
				  `noreg` char(4)  NOT NULL,
				  `jml_barang` int(9) NOT NULL,
				  `harga` decimal(18,2) NOT NULL,
				  `satuan` varchar(15)  NOT NULL DEFAULT '',
				  `jml_biaya` decimal(18,2) NOT NULL,
				  `thn_perolehan` char(4)  NOT NULL,
				  `uraian` varchar(200)  DEFAULT NULL,
				  `ket` varchar(200)  DEFAULT NULL,
				  `a` char(2)  NOT NULL,
				  `b` char(2)  NOT NULL,
				  `c` char(2)  NOT NULL,
				  `d` char(2)  NOT NULL,
				  `e` char(2)  NOT NULL,
				  `f` char(2)  NOT NULL,
				  `g` char(2)  NOT NULL,
				  `h` char(2)  NOT NULL,
				  `i` char(2)  NOT NULL,
				  `j` char(2)  NOT NULL,
				  `k` char(1)  NOT NULL,
				  `l` char(1)  NOT NULL,
				  `m` char(1)  NOT NULL,
				  `n` char(2)  NOT NULL,
				  `o` char(2)  NOT NULL,
				  `tahun` char(4)  NOT NULL,
				  PRIMARY KEY (`id_baru`,`a`,`b`,`c`,`d`,`e`,`f`,`g`,`h`,`i`,`j`,`tahun`)
				);

				";

				$ArTabelTemp = explode(";",$TabelTemp);
				for ($cc = 0; $cc < count($ArTabelTemp); $cc++)
				{
					//JalankanQuery
					MySql_Query($ArTabelTemp[$cc]);
				}

				$SisipKeun = "";
				$SisipKeun1 = "";
				for ($x=0;$x<count($ArrData);$x++)
				{
					$SisipKeun = "";
					
					if (count($ArrData[$x])>1)
					{
						for ($i=0;$i<count($ArrData[$x]);$i++)
						{
							
							if ($i==0)
							{
								$Tabelna = explode(",",$ArrData[$x][0]);
								$FieldNa = "";
								for($stru = 1;$stru < count($Tabelna);$stru++)
								{
									$FieldNa .= ",".$Tabelna[$stru];
								}
								$FieldNa = substr($FieldNa,1,strlen($FieldNa));
								$SisipKeun .= "insert into {$Tabelna[0]}_temp ($FieldNa) values (";
							}
							else
							{
								$SisipKeun .=  $ArrData[$x][$i];
								if ($i==count($ArrData[$x])-1)
								{
									$SisipKeun .=  ");";
								}
								if ($i<count($ArrData[$x])-1)
								{
									$SisipKeun .=  ",";
								}
							}
							
						}
					}
					//echo $SisipKeun1 . "<br>";
					//Laksanakan Isi Tabel Temp
					MySQL_Query($SisipKeun);
					
				}

				//Generate ID Baru
				
				$tables = array("buku_induk","dkb","dkpb","gantirugi","history_barang","kib_a","kib_b","kib_c","kib_d","kib_e","kib_f","kir","pemanfaatan","pembiayaan","pemeliharaan","pemindahtanganan","penerimaan","penetapan","pengadaan","pengadaan_pemeliharaan","pengamanan","pengeluaran","penghapusan","penilaian","rkb","rkpb");
				for ($iTabel = 0; $iTabel < count($tables); $iTabel++)
				{
					$IdAkhir = mysql_fetch_array(mysql_query("select max(id)+1 as akhir from {$tables[$iTabel]}"));$IdAkhir=$IdAkhir[0];
					//echo $IdAkhir;
					$nIdBaru = $IdAkhir;
					$JmRow = mysql_num_rows(mysql_query("select * from {$tables[$iTabel]}"));
					MySQL_Query("update {$tables[$iTabel]}_temp set id_baru=id_baru+$IdAkhir");
					
				}
				
				//Update id_bukuinduk di tabel ketergantungan id_bukuinduk
				$TabelIDBUKUINDUK = "pemanfaatan,pemeliharaan,kir,pengamanan,penilaian,penghapusan,pemindahtanganan,pembiayaan,gantirugi,history_barang";
				$TabelBI = explode(",",$TabelIDBUKUINDUK);
				for ($iBi = 0; $iBi < count($TabelBI); $iBi++)
				{
					$TabelBINya = $TabelBI[$iBi];
					$DataHistori = mysql_query("select a.id as sumber,a.id_baru as sumber_baru, b.id_bukuinduk as target,b.id_bukuinduk_baru as target_baru  from buku_induk_temp as a inner join {$TabelBI[$iBi]}_temp as b on (a.id=b.id_bukuinduk)");
					//echo "select a.id as sumber,a.id_baru as sumber_baru, b.id_bukuinduk as target,b.id_bukuinduk_baru as target_baru  from buku_induk_temp as a inner join {$TabelBI[$iBi]}_temp as b on (a.id=b.id_bukuinduk)<br>";
					while ($res = mysql_fetch_array($DataHistori))
					{
						//Update id_bukuinduk_baru di histori_temp
						$IdCari = $res['sumber'];
						$IdBaru = $res['sumber_baru'];
						MySQL_Query ("update {$TabelBI[$iBi]}_temp set id_bukuinduk_baru = '$IdBaru' where id_bukuinduk='$IdCari'");
					}
					MySQL_Query ("update {$TabelBI[$iBi]}_temp set id_bukuinduk = id_bukuinduk_baru");
				}

				//Update ID_PENGADAAN DI TABEL PENERIMAAN DARI TABEL PENGADAAN
					$DataHistori = mysql_query("select a.id as sumber,a.id_baru as sumber_baru, b.id_pengadaan as target,b.id_pengadaan_baru as target_baru  from pengadaan_temp as a inner join penerimaan_temp as b on (a.id=b.id_pengadaan)");
					while ($res = mysql_fetch_array($DataHistori))
					{
						$IdCari = $res['sumber'];
						$IdBaru = $res['sumber_baru'];
						MySQL_Query ("update penerimaan_temp set id_pengadaan_baru = '$IdBaru' where id_pengadaan='$IdCari'");
					}
					MySQL_Query ("update penerimaan_temp set id_pengadaan = id_pengadaan_baru");

				//Update ID_PENERIMAAN DI TABEL PENGELUARAN DARI TABEL PENERIMAAN
					$DataHistori = mysql_query("select a.id as sumber,a.id_baru as sumber_baru, b.id_penerimaan as target,b.id_penerimaan_baru as target_baru  from penerimaan_temp as a inner join pengeluaran_temp as b on (a.id=b.id_penerimaan)");
					while ($res = mysql_fetch_array($DataHistori))
					{
						$IdCari = $res['sumber'];
						$IdBaru = $res['sumber_baru'];
						MySQL_Query ("update pengeluaran_temp set id_penerimaan_baru = '$IdBaru' where id_penerimaan='$IdCari'");

					}
					MySQL_Query ("update pengeluaran_temp set id_penerimaan = id_penerimaan_baru");

				//Update ID_PENGELUARAN DI TABEL PENETAPAN DARI TABEL PENGELUARAN
					$DataHistori = mysql_query("select a.id as sumber,a.id_baru as sumber_baru, b.id_pengeluaran as target,b.id_pengeluaran_baru as target_baru  from pengeluaran_temp as a inner join penetapan_temp as b on (a.id=b.id_pengeluaran)");
					while ($res = mysql_fetch_array($DataHistori))
					{
						$IdCari = $res['sumber'];
						$IdBaru = $res['sumber_baru'];
						MySQL_Query ("update penetapan_temp set id_pengeluaran_baru = '$IdBaru' where id_pengeluaran='$IdCari'");
					}
					MySQL_Query ("update penetapan_temp set id_pengeluaran = id_pengeluaran_baru");

				//Update ID with ID_BARU
				for ($iTabel = 0; $iTabel < count($tables); $iTabel++)
				{
					MySQL_Query("update {$tables[$iTabel]}_temp set id=id_baru");
				}



				//Laksanakan Delete Then Insert
				//MySQL_Query($SisipKeun1);
				for ($iRet = 0; $iRet < count($tables); $iRet++)
				{
					//Ambil Column
					$ListKolom = "";
					$Qry = mysql_query("desc {$tables[$iRet]}");
					while($isiListKolom = mysql_fetch_array($Qry))
					{
						$ListKolom .= ",".$isiListKolom[0];
					}
					$ListKolom = substr($ListKolom,1,strlen($ListKolom));
					$Delete = "delete from {$tables[$iRet]} where c='$fmSKPD'; ";
					$Insert = "insert into {$tables[$iRet]} select $ListKolom from {$tables[$iRet]}_temp; ";
					MySQl_Query($Delete);
					MySQl_Query($Insert);

				}
				

				//Drop Tabel Temp
				$TabelTemp = "
					DROP TABLE IF EXISTS `buku_induk_temp` ;
					DROP TABLE IF EXISTS `dkb_temp`;
					DROP TABLE IF EXISTS `dkpb_temp` ;
					DROP TABLE IF EXISTS `gantirugi_temp`;
					DROP TABLE IF EXISTS `history_barang_temp`;
					DROP TABLE IF EXISTS `kib_a_temp` ;
					DROP TABLE IF EXISTS `kib_b_temp` ;
					DROP TABLE IF EXISTS `kib_c_temp` ;
					DROP TABLE IF EXISTS `kib_d_temp` ;
					DROP TABLE IF EXISTS `kib_e_temp` ;
					DROP TABLE IF EXISTS `kib_f_temp` ;
					DROP TABLE IF EXISTS `kir_temp` ;
					DROP TABLE IF EXISTS `pemanfaatan_temp` ;
					DROP TABLE IF EXISTS `pembiayaan_temp` ;
					DROP TABLE IF EXISTS `pemeliharaan_temp` ;
					DROP TABLE IF EXISTS `pemindahtanganan_temp` ;
					DROP TABLE IF EXISTS `penerimaan_temp` ;
					DROP TABLE IF EXISTS `penetapan_temp` ;
					DROP TABLE IF EXISTS `pengadaan_temp` ;
					DROP TABLE IF EXISTS `pengadaan_pemeliharaan_temp` ;
					DROP TABLE IF EXISTS `pengamanan_temp` ;
					DROP TABLE IF EXISTS `pengeluaran_temp` ;
					DROP TABLE IF EXISTS `penghapusan_temp` ;
					DROP TABLE IF EXISTS `penilaian_temp` ;
					DROP TABLE IF EXISTS `rkb_temp` ;
					DROP TABLE IF EXISTS `rkpb_temp` ;
					";
					$ArTabelTemp = explode(";",$TabelTemp);
					for ($cc = 0; $cc < count($ArTabelTemp); $cc++)
					{
						//JalankanQuery
						MySql_Query($ArTabelTemp[$cc]);
					}
					$Main->Isi = "<br><br>Restor data selesai...";

		}
}
?>