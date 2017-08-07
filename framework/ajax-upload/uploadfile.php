<?php
include("Excel/excel_reader2.php");

//type file yang bisa diupload
$file_type  = array('xls');
//direktori upload
$path = '/var/www/atisisbada_garut_kab_v2/tmp2011';
//$path = 'tmp2011';


 
if ($_FILES)
{
    $tmp = $_FILES['isiupload']['tmp_name'];
    $type = $_FILES['isiupload']['type'];
    $size = $_FILES['isiupload']['size'];
    $filename = $_FILES['isiupload']['name'];
	$destination = $path.'/'.$filename;
   
	//cari extensi file dengan menggunakan fungsi explode
	$explode    = explode('.',$filename);
    $extensi    = $explode[count($explode)-1];
	//ganti namafile bila file sudah ada di upload
	if(file_exists($destination)){
		$newfilename=date('d-m-Y Hi ').$filename;
		$destination=$path.'/'.$newfilename;
	}
	//check apakah type file sudah sesuai
    if(!in_array($extensi,$file_type)){
       $status=2;
	   $pesan=1;
    }else{
		 if (move_uploaded_file($tmp, $destination)){
			$status = 1;
	//Memasukan Data ke tabel tmp ----------------------------------------------------------------------------		
			//$datafile= new Spreadsheet_Excel_Reader($_FILES['fileupload']['tmp_name']);
			$datafile= new Spreadsheet_Excel_Reader($path.'/'.$filename);
	//Inisiasi array untuk sheet
			$arrnamakolom=array();
			$arrnamasheet=array();			
			$isheet=0;
			$datasheet=count($datafile->boundsheets);
			while($isheet<=$datasheet){
				$tmpnamasheet=$datafile->boundsheets[$isheet]['name'];
				$tmpnamasheet1=str_replace(' ','',$tmpnamasheet);
				$namasheet=strtolower($tmpnamasheet1);
				$arrnamasheet[$namasheet]=$isheet;
				$isheet++;
			}		
			$databarisKIBA=$datafile->rowcount($sheet_index=$arrnamasheet['kiba']);
			$datakolomKIBA=$datafile->colcount($sheet_index=$arrnamasheet['kiba']);
			$databarisKIBB=$datafile->rowcount($sheet_index=$arrnamasheet['kibb']);
			$datakolomKIBB=$datafile->colcount($sheet_index=$arrnamasheet['kibb']);
			$databarisKIBC=$datafile->rowcount($sheet_index=$arrnamasheet['kibc']);
			$datakolomKIBC=$datafile->colcount($sheet_index=$arrnamasheet['kibc']);
			$databarisKIBD=$datafile->rowcount($sheet_index=$arrnamasheet['kibd']);
			$datakolomKIBD=$datafile->colcount($sheet_index=$arrnamasheet['kibd']);
			$databarisKIBE=$datafile->rowcount($sheet_index=$arrnamasheet['kibe']);
			$datakolomKIBE=$datafile->colcount($sheet_index=$arrnamasheet['kibe']);
			$databarisKIBF=$datafile->rowcount($sheet_index=$arrnamasheet['kibf']);
			$datakolomKIBF=$datafile->colcount($sheet_index=$arrnamasheet['kibf']);
			$databarisKIBG=$datafile->rowcount($sheet_index=$arrnamasheet['kibg']);
			$datakolomKIBG=$datafile->colcount($sheet_index=$arrnamasheet['kibg']);
	
	
		
	//Memasukan data pada sheet 0 atau KIB A
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBA) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kiba']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBA; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kiba']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kiba']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kiba']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kiba']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kiba']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kiba']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kiba']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kiba']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kiba']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kiba']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kiba']);
				$luas =$datafile->val($i,$arrnamakolom['luas'],$arrnamasheet['kiba']);
				$alamat =$datafile->val($i,$arrnamakolom['alamat'],$arrnamasheet['kiba']);
				$kota =$datafile->val($i,$arrnamakolom['kota'],$arrnamasheet['kiba']);
				$status_hak =$datafile->val($i,$arrnamakolom['status_hak'],$arrnamasheet['kiba']);
				$bersertifikat =$datafile->val($i,$arrnamakolom['bersertifikat'],$arrnamasheet['kiba']);
				$sertifikat_tgl =$datafile->val($i,$arrnamakolom['sertifikat_tgl'],$arrnamasheet['kiba']);
				$sertifikat_no =$datafile->val($i,$arrnamakolom['sertifikat_no'],$arrnamasheet['kiba']);
				$penggunaan =$datafile->val($i,$arrnamakolom['penggunaan'],$arrnamasheet['kiba']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kiba']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kiba']);
				if ($staset==''){
					$staset=3;
				}
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_a_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,luas,alamat,kota,status_hak,bersertifikat,sertifikat_tgl,sertifikat_no,penggunaan,ket,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$luas','$alamat','$kota','$status_hak','$bersertifikat','$sertifikat_tgl','$sertifikat_no','$penggunaan','$ket','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
						
	
			
		
	//Memasukan data pada sheet 1 atau KIB B
			$icol=1;
			$arrnamakolom='';
			while($icol<=$datakolomKIBB) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibb']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBB; $i++){
			
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibb']);		
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibb']);
				$merk=$datafile->val($i,$arrnamakolom['merk'],$arrnamasheet['kibb']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibb']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibb']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibb']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibb']);
				$kondisi=$datafile->val($i,$arrnamakolom['kondisi'],$arrnamasheet['kibb']);
				$ukuran=$datafile->val($i,$arrnamakolom['ukuran'],$arrnamasheet['kibb']);
				$bahan=$datafile->val($i,$arrnamakolom['bahan'],$arrnamasheet['kibb']);
				$no_rangka=$datafile->val($i,$arrnamakolom['no_rangka'],$arrnamasheet['kibb']);
				$no_mesin=$datafile->val($i,$arrnamakolom['no_mesin'],$arrnamasheet['kibb']);
				$no_polisi=$datafile->val($i,$arrnamakolom['no_polisi'],$arrnamasheet['kibb']);
				$no_bpkb=$datafile->val($i,$arrnamakolom['no_bpkb'],$arrnamasheet['kibb']);
				$kondisi1=$datafile->val($i,$arrnamakolom['kondisi1'],$arrnamasheet['kibb']);
				$kondisi2=$datafile->val($i,$arrnamakolom['kondisi2'],$arrnamasheet['kibb']);
				$lokasi=$datafile->val($i,$arrnamakolom['lokasi'],$arrnamasheet['kibb']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibb']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibb']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibb']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibb']);
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibb']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibb']);
				$no_pabrik=$datafile->val($i,$arrnamakolom['no_pabrik'],$arrnamasheet['kibb']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibb']);
				if ($staset==''){
					$staset=3;
				}			
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_b_tmp (c,d,e,e1,kode_barang,nama_barang,merk,thn_perolehan,jml_barang,jml_harga,asal_usul,kondisi,ukuran,bahan,no_rangka,no_mesin,no_polisi,no_bpkb,kondisi1,kondisi2,lokasi,ket,noreg,status_barang,tgl_buku,kd_skpd,satuan,no_pabrik,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kode_barang','$nama_barang','$merk','$thn_perolehan','$jml_barang','$jml_harga','$asal_usul','$kondisi','$ukuran','$bahan','$no_rangka','$no_mesin','$no_polisi','$no_bpkb','$kondisi1','$kondisi2','$lokasi','$ket','$noreg','$status_barang','$tgl_buku','$kd_skpd','$satuan','$no_pabrik','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			} 		
			
	
		
		
	//Memasukan data pada sheet 2 atau KIB C
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBC) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibc']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBC; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibc']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibc']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibc']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibc']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibc']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibc']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibc']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibc']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibc']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibc']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibc']);
				$kondisi =$datafile->val($i,$arrnamakolom['kondisi'],$arrnamasheet['kibc']);
				$kondisi_bangunan =$datafile->val($i,$arrnamakolom['kondisi_bangunan'],$arrnamasheet['kibc']);
				$konstruksi_tingkat =$datafile->val($i,$arrnamakolom['konstruksi_tingkat'],$arrnamasheet['kibc']);
				$konstruksi_beton =$datafile->val($i,$arrnamakolom['konstruksi_beton'],$arrnamasheet['kibc']);
				$luas_lantai =$datafile->val($i,$arrnamakolom['luas_lantai'],$arrnamasheet['kibc']);
				$alamat =$datafile->val($i,$arrnamakolom['alamat'],$arrnamasheet['kibc']);
				$luas =$datafile->val($i,$arrnamakolom['luas'],$arrnamasheet['kibc']);
				$status_tanah =$datafile->val($i,$arrnamakolom['status_tanah'],$arrnamasheet['kibc']);
				$kode_tanah=$datafile->val($i,$arrnamakolom['kode_tanah'],$arrnamasheet['kibc']);
				$kode_loktanah=$datafile->val($i,$arrnamakolom['kode_loktanah'],$arrnamasheet['kibc']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibc']);
				$dokumen_tgl =$datafile->val($i,$arrnamakolom['dokumen_tgl'],$arrnamasheet['kibc']);
				$dokumen_no =$datafile->val($i,$arrnamakolom['dokumen_no'],$arrnamasheet['kibc']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibc']);
				if ($staset==''){
					$staset=3;
				}				
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_c_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,kondisi,kondisi_bangunan,konstruksi_tingkat,konstruksi_beton,luas_lantai,alamat,luas,status_tanah,kode_tanah,kode_loktanah,ket,dokumen_tgl,dokumen_no,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$kondisi','$kondisi_bangunan','$konstruksi_tingkat','$konstruksi_beton','$luas_lantai','$alamat','$luas','$status_tanah','$kode_tanah','$kode_loktanah','$ket','$dokumen_tgl','$dokumen_no','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
		
	//Memasukan data pada sheet 3 atau KIB D
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBD) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibd']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBD; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibd']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibd']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibd']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibd']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibd']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibd']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibd']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibd']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibd']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibd']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibd']);
				$konstruksi =$datafile->val($i,$arrnamakolom['konstruksi'],$arrnamasheet['kibd']);
				$panjang =$datafile->val($i,$arrnamakolom['panjang'],$arrnamasheet['kibd']);
				$lebar =$datafile->val($i,$arrnamakolom['lebar'],$arrnamasheet['kibd']);
				$luas =$datafile->val($i,$arrnamakolom['luas'],$arrnamasheet['kibd']);
				$alamat =$datafile->val($i,$arrnamakolom['alamat'],$arrnamasheet['kibd']);
				$kota =$datafile->val($i,$arrnamakolom['kota'],$arrnamasheet['kibd']);
				$status_tanah =$datafile->val($i,$arrnamakolom['status_tanah'],$arrnamasheet['kibd']);
				$kode_tanah=$datafile->val($i,$arrnamakolom['kode_tanah'],$arrnamasheet['kibd']);
				$kondisi =$datafile->val($i,$arrnamakolom['kondisi'],$arrnamasheet['kibd']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibd']);
				$dokumen_tgl =$datafile->val($i,$arrnamakolom['dokumen_tgl'],$arrnamasheet['kibd']);
				$dokumen_no =$datafile->val($i,$arrnamakolom['dokumen_no'],$arrnamasheet['kibd']);				
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibd']);
				if ($staset==''){
					$staset=3;
				}	
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_d_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,konstruksi,kondisi,panjang,lebar,luas,alamat,kota,status_tanah,kode_tanah,ket,dokumen_tgl,dokumen_no,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$konstruksi','$kondisi','$panjang','$lebar','$luas','$alamat','$kota','$status_tanah','$kode_tanah','$ket','$dokumen_tgl','$dokumen_no','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
		
	//Memasukan data pada sheet 4 atau KIB E
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBE) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibe']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBE; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibe']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibe']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibe']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibe']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibe']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibe']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibe']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibe']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibe']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibe']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibe']);
				$buku_judul =$datafile->val($i,$arrnamakolom['buku_judul'],$arrnamasheet['kibe']);
				$buku_spesifikasi =$datafile->val($i,$arrnamakolom['buku_spesifikasi'],$arrnamasheet['kibe']);
				$seni_asal_daerah =$datafile->val($i,$arrnamakolom['seni_asal_daerah'],$arrnamasheet['kibe']);
				$seni_pencipta =$datafile->val($i,$arrnamakolom['seni_pencipta'],$arrnamasheet['kibe']);
				$seni_bahan =$datafile->val($i,$arrnamakolom['seni_bahan'],$arrnamasheet['kibe']);
				$hewan_jenis =$datafile->val($i,$arrnamakolom['hewan_jenis'],$arrnamasheet['kibe']);
				$hewan_ukuran =$datafile->val($i,$arrnamakolom['hewan_ukuran'],$arrnamasheet['kibe']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibe']);
				$kondisi =$datafile->val($i,$arrnamakolom['kondisi'],$arrnamasheet['kibe']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibe']);
				if ($staset==''){
					$staset=3;
				}	
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_e_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,ket,kondisi,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$buku_judul','$buku_spesifikasi','$seni_asal_daerah','$seni_pencipta','$seni_bahan','$hewan_jenis','$hewan_ukuran','$ket','$kondisi','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
		
	//Memasukan data pada sheet 5 atau KIB F
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBF) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibf']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBF; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibf']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibf']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibf']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibf']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibf']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibf']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibf']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibf']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibf']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibf']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibf']);
				$bangunan =$datafile->val($i,$arrnamakolom['bangunan'],$arrnamasheet['kibf']);
				$konstruksi_tingkat =$datafile->val($i,$arrnamakolom['konstruksi_tingkat'],$arrnamasheet['kibf']);
				$konstruksi_beton =$datafile->val($i,$arrnamakolom['konstruksi_beton'],$arrnamasheet['kibf']);
				$luas =$datafile->val($i,$arrnamakolom['luas'],$arrnamasheet['kibf']);
				$alamat =$datafile->val($i,$arrnamakolom['alamat'],$arrnamasheet['kibf']);
				$status_tanah =$datafile->val($i,$arrnamakolom['status_tanah'],$arrnamasheet['kibf']);
				$kode_tanah=$datafile->val($i,$arrnamakolom['kode_tanah'],$arrnamasheet['kibf']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibf']);
				$kota =$datafile->val($i,$arrnamakolom['kota'],$arrnamasheet['kibf']);
				$dokumen_tgl =$datafile->val($i,$arrnamakolom['dokumen_tgl'],$arrnamasheet['kibf']);
				$dokumen_no =$datafile->val($i,$arrnamakolom['dokumen_no'],$arrnamasheet['kibf']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibf']);
				if ($staset==''){
					$staset=3;
				}	
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_f_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,bangunan,konstruksi_tingkat,konstruksi_beton,luas,alamat,status_tanah,kode_tanah,ket,kota,dokumen_tgl,dokumen_no,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$bangunan','$konstruksi_tingkat','$konstruksi_beton','$luas','$alamat','$status_tanah','$kode_tanah','$ket','$kota','$dokumen_tgl','$dokumen_no','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
		
		//Memasukan data pada sheet 6 atau KIB G
			$icol=1;
			$arrnamakolom='';
			while($icol <= $datakolomKIBG) {
			$tmpnamakolom=$datafile->val(1,$icol,$arrnamasheet['kibg']);
			$namakolom=strtolower($tmpnamakolom);
 	 		$arrnamakolom[$namakolom]=$icol;
 	 		$icol++;
			}
			
			for ($i=2; $i<=$databarisKIBG; $i++){
			
				$kd_skpd=$datafile->val($i,$arrnamakolom['kd_skpd'],$arrnamasheet['kibg']);
				$expkdskpd=explode('.',$kd_skpd);
				$c=$expkdskpd['0'];$d=$expkdskpd['1'];$e=$expkdskpd['2'];$e1=$expkdskpd['3'];
				$kode_barang=$datafile->val($i,$arrnamakolom['kode_barang'],$arrnamasheet['kibg']);
				$thn_perolehan=$datafile->val($i,$arrnamakolom['thn_perolehan'],$arrnamasheet['kibg']);
				$noreg =$datafile->val($i,$arrnamakolom['noreg'],$arrnamasheet['kibg']);
				$nama_barang =$datafile->val($i,$arrnamakolom['nama_barang'],$arrnamasheet['kibg']);
				$jml_barang =$datafile->val($i,$arrnamakolom['jml_barang'],$arrnamasheet['kibg']);
				$jml_harga =$datafile->val($i,$arrnamakolom['jml_harga'],$arrnamasheet['kibg']);
				$status_barang =$datafile->val($i,$arrnamakolom['status_barang'],$arrnamasheet['kibg']);
				$satuan =$datafile->val($i,$arrnamakolom['satuan'],$arrnamasheet['kibg']);
				$tgl_buku =$datafile->val($i,$arrnamakolom['tgl_buku'],$arrnamasheet['kibg']);
				$asal_usul =$datafile->val($i,$arrnamakolom['asal_usul'],$arrnamasheet['kibg']);
				$ket =$datafile->val($i,$arrnamakolom['ket'],$arrnamasheet['kibg']);
				$kondisi =$datafile->val($i,$arrnamakolom['kondisi'],$arrnamasheet['kibg']);
				$uraian =$datafile->val($i,$arrnamakolom['uraian'],$arrnamasheet['kibg']);
				$software_nama =$datafile->val($i,$arrnamakolom['software_nama'],$arrnamasheet['kibg']);
				$kajian_nama =$datafile->val($i,$arrnamakolom['kajian_nama'],$arrnamasheet['kibg']);
				$kerjasama_nama =$datafile->val($i,$arrnamakolom['kerjasama_nama'],$arrnamasheet['kibg']);
				$pencipta =$datafile->val($i,$arrnamakolom['pencipta'],$arrnamasheet['kibg']);
				$staset=$datafile->val($i,$arrnamakolom['staset'],$arrnamasheet['kibg']);
				if ($staset==''){
					$staset=8;
				}					
			if($kd_skpd!='' && $kode_barang!=''){
				$sql="INSERT into bi_kib_g_tmp (c,d,e,e1,kd_skpd,kode_barang,thn_perolehan,noreg,nama_barang,jml_barang,jml_harga,status_barang,satuan,tgl_buku,asal_usul,ket,kondisi,uraian,software_nama,kajian_nama,kerjasama_nama,pencipta,staset)"."
			 VALUES ('$c','$d','$e','$e1','$kd_skpd','$kode_barang','$thn_perolehan','$noreg','$nama_barang','$jml_barang','$jml_harga','$status_barang','$satuan','$tgl_buku','$asal_usul','$ket','$kondisi','$uraian','$software_nama','$kajian_nama','$kerjasama_nama','$pencipta','$staset')";
			 $simpan=mysql_query($sql);
			}
			if (!$simpan){
				$err="Gagal Migrasi data".mysql_error();
			}
			}		
		

			
		}else{
			$status = 2;
			$pesan=0;
		}
	}
	
	$tampilpesan=array('File Gagal diupload!','Tipe file yang diupload harus .xls','File sudah ada!');
   	$hasil = array(
        'status' => $status,
        'tampilpesan' => $tampilpesan[$pesan],
    );
    echo json_encode($hasil);
}

?>