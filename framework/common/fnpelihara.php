<?php

function Pelihara_ProsesSimpan(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 $sukses = FALSE;	
		$MyModulKU = explode(".",$HTTP_COOKIE_VARS["coModul"]);
					 
	if ($MyModulKU[6]=='1'){	
		$arrParams = array('fmTANGGALPEMELIHARAAN');
		
		//$fmNOREG = $_GET['fmNOREG']; 
		$$arrParams[0] = $_GET[$arrParams[0]]; 
		$fmJENISPEMELIHARAAN = $_GET['fmJENISPEMELIHARAAN'];
		$fmPEMELIHARAINSTANSI = $_GET['fmPEMELIHARAINSTANSI'];
		$fmPEMELIHARAALAMAT = $_GET['fmPEMELIHARAALAMAT'];
		$fmSURATNOMOR = $_GET['fmSURATNOMOR'];
		$fmSURATTANGGAL = $_GET['fmSURATTANGGAL'];
		$fmBIAYA = $_GET['fmBIAYA'];
		$fmBUKTIPEMELIHARAAN = $_GET['fmBUKTIPEMELIHARAAN'];
		$fmKET = $_GET['fmKET'];
		$fmTAMBAHASET = $_GET['fmTAMBAHASET'];
		$fmTAMBAHMasaManfaat = $_GET['fmTAMBAHMasaManfaat'];
		$cara_perolehan = $_GET['cara_perolehan'];
		$fmTANGGALPerolehan = $_GET['fmTANGGALPerolehan'];
		$fmNOMORba = $_GET['fmNOMORba'];
		$fmTANGGALba = $_GET['fmTANGGALba'];
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$idbi = $_GET['idbi'];
		$idplh = $_GET['idplh'];
		$idbi_awal = $_GET['idbi_awal']; 
		$fmst = $_GET['fmst'];


		if ($fmst==0){
			if ($idplh!='')
			{
				
			
					$isix =  mysql_fetch_array( mysql_query (				
				" select idbi_awal  from pemeliharaan where id ='$idplh'"
			));
			$idbi=$isix['idbi_awal'];
			}
			
		}
		
		if ($fmBIAYA == ''){$fmBIAYA = 0;}	
		if (!empty($fmTAMBAHASET)){
			$fmTAMBAHASET = 1;
		}else{
			$fmTAMBAHASET=0;
		}
		if (!empty($fmTAMBAHMasaManfaat)){
			$fmTAMBAHMasaManfaat = 1;
		}else{
			$fmTAMBAHMasaManfaat=0;
		}
		$errmsg ='';//",fmTAMBAHASET=$fmTAMBAHASET";
		/*$errmsg = " fmTANGGALPEMELIHARAAN=$fmTANGGALPEMELIHARAAN, fmJENISPEMELIHARAAN=$fmJENISPEMELIHARAAN,
		fmPEMELIHARAINSTANSI=$fmPEMELIHARAINSTANSI, fmPEMELIHARAALAMAT=$fmPEMELIHARAALAMAT, fmSURATNOMOR=$fmSURATNOMOR,
		fmSURATTANGGAL=$fmSURATTANGGAL, fmBIAYA=$fmBIAYA, fmBUKTIPEMELIHARAAN=$fmBUKTIPEMELIHARAAN, fmKET=$fmKET,
		idbi=$idbi, idplh=$idplh, idbi_awal=$idbi_awal, ActEntry=$ActEntry, fmst=$fmst";*/
		//validasi -----------------------
		if($fmTANGGALPerolehan == '0000-00-00' || $fmTANGGALPerolehan=='' ){ $errmsg = 'Tanggal Perolehan belum diisi!';	}		
		if($fmTANGGALPEMELIHARAAN == '0000-00-00' || $fmTANGGALPEMELIHARAAN=='' ){ $errmsg = 'Tanggal Pemeliharaan belum diisi!';	}		
		if($cara_perolehan=='' ){ $errmsg = 'Cara Perolehan belum diisi!';	}		
		if($errmsg=='' && !cektanggal($fmTANGGALPEMELIHARAAN)){	$errmsg = "Tanggal Pemeliharaan $fmTANGGALPEMELIHARAAN Salah!";	}	
		if($errmsg=='' && !cektanggal($fmTANGGALPerolehan)){	$errmsg = "Tanggal Perolehan $fmTANGGALPerolehan Salah!";	}	
		if ($errmsg =='' && compareTanggal($fmTANGGALPEMELIHARAAN, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Pemeliharaan tidak lebih besar dari Hari ini!';				
		if ($errmsg =='' && compareTanggal($fmTANGGALPerolehan,$fmTANGGALPEMELIHARAAN )==2) $errmsg = 'Tanggal Perolehan tidak lebih besar dari Tanggal Pemeliharaan !';				
		
		$thn_Perolehan = substr($fmTANGGALPerolehan,0,4);
		$get_cd = mysql_fetch_array(mysql_query("select c,d,e,e1,tgl_buku,thn_perolehan from buku_induk where id='$idbi'"));
		$tgl_closing=getTglClosing($get_cd['c'],$get_cd['d'],$get_cd['e'],$get_cd['e1']); 
		
		//get tglakhir susut,koreksi,penilaian,penghapusan_sebagian dgn idbi_awal yg sama
		$tgl_susutAkhir = mysql_fetch_array(mysql_query("select tgl from penyusutan where idbi_awal='$idbi' order by tgl desc limit 1"));
		$tgl_korAkhir = mysql_fetch_array(mysql_query("select tgl,tgl_create from t_koreksi where idbi_awal='$idbi' order by tgl desc limit 1"));
		$tgl_nilaiAkhir = mysql_fetch_array(mysql_query("select tgl_penilaian,tgl_create from penilaian where idbi_awal='$idbi' order by tgl_penilaian desc limit 1"));
		$tgl_hpsAkhir = mysql_fetch_array(mysql_query("select tgl_penghapusan,tgl_create from penghapusan_sebagian where idbi_awal='$idbi' order by tgl_penghapusan desc limit 1"));
		$tgl_asetAkhir = mysql_fetch_array(mysql_query("select tgl,tgl_create from t_history_aset where idbi_awal='$idbi' order by tgl desc limit 1"));
		//$errmsg="select tgl,tgl_create from t_history_aset where idbi_awal='$idbi' order by tgl desc limit 1";
		//-------------------------------------
		
		if ($fmst==1){
			//jika tambah aset =1 atau tambah manfaat = 1:
			//if($fmTAMBAHASET==1 || $fmTAMBAHMasaManfaat==1){
				if($errmsg =='' && compareTanggal($get_cd['tgl_buku'],$fmTANGGALPEMELIHARAAN)==2) $errmsg = 'Tanggal Buku Pemeliharaan tidak kecil dari Tanggal Buku Barang !';				
				if($errmsg =='' && $thn_Perolehan<$get_cd['thn_perolehan']) $errmsg = 'Tahun Perolehan tidak kecil dari Tahun Perolehan Buku Barang !';				
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<=$tgl_closing)$errmsg ='Tanggal sudah Closing !';
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<=$tgl_susutAkhir['tgl'])$errmsg ='Sudah ada penyusutan !';
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<$tgl_korAkhir['tgl'] )$errmsg ='Sudah ada koreksi harga !';
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<$tgl_nilaiAkhir['tgl_penilaian'] )$errmsg ='Sudah ada penilaian !';
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<$tgl_hpsAkhir['tgl_penghapusan'] )$errmsg ='Sudah ada penghapusan sebagian !';
				if($errmsg=='' && $fmTANGGALPEMELIHARAAN<$tgl_asetAkhir['tgl'] )$errmsg ='Sudah ada perubahan status aset !';
			//}
			//$errmsg=$thn_Perolehan;
		//if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']<$tgl_susutAkhir['tgl']){			
		}else{
		
			$old_pemelihara = mysql_fetch_array(mysql_query("select * from pemeliharaan where id='$idplh'"));
			//$errmsg='tgl_='.$old_pemelihara['tgl_pemeliharaan'];
			//cek tgl buku lama <= tgl closing
			if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']<=$tgl_closing){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit,karena sudah closing !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit,karena sudah closing !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit,karena sudah closing !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit,karena sudah closing !';
				if($errmsg=='' && $old_pemelihara['cara_perolehan']!=$cara_perolehan)$errmsg = 'Cara Perolehan tidak bisa di edit,karena sudah closing !';
			}			
			//cek tgl perolehan lama < tgl susut akhir 
			//if($errmsg=='' && ($old_pemelihara['tgl_perolehan']<$tgl_susutAkhir['tgl'] && $old_pemelihara['tgl_pemeliharaan']<=$tgl_susutAkhir['tgl'])){
			if($errmsg=='' && $old_pemelihara['tgl_perolehan']<$tgl_susutAkhir['tgl'] ){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit,sudah ada penyusutan !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit,sudah ada penyusutan !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit,sudah ada penyusutan !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit,sudah ada penyusutan !';
				if($errmsg=='' && $old_pemelihara['tambah_masamanfaat']!=$fmTAMBAHMasaManfaat)$errmsg = 'Manambah Masa Manfaat tidak bisa di edit,karena sudah penyusutan !';
			}
			//cek (tgl buku lama < tgl buku koreksi terakhir) atau (tgl buku lama = tgl buku koreksi terakhir  dan waktu create < waktu create koreksi terakhir)
			if(($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']<$tgl_korAkhir['tgl']) || ($old_pemelihara['tgl_pemeliharaan']==$tgl_korAkhir['tgl'] && $old_pemelihara['tgl_create']<$tgl_korAkhir['tgl_create'])){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit, sudah ada koreksi harga !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit, sudah ada koreksi harga !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit, sudah ada koreksi harga !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit, sudah ada koreksi harga !';
			}
			//cek (tgl buku lama < tgl buku penilaian terakhir) atau (tgl buku lama = tgl buku penilaian terakhir  dan waktu create < waktu create penilaian terakhir)
			if(($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']<$tgl_nilaiAkhir['tgl_penilaian']) || ($old_pemelihara['tgl_pemeliharaan']==$tgl_nilaiAkhir['tgl_penilaian'] && $old_pemelihara['tgl_create']<$tgl_nilaiAkhir['tgl_create'])){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit, sudah ada penilaian !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit, sudah ada penilaian !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit, sudah ada penilaian !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit, sudah ada penilaian !';
			}
			//cek (tgl buku lama < tgl buku hapus sebagian terakhir) atau (tgl buku lama = tgl buku hapus sebagian terakhir  dan waktu create < waktu create hapus sebagian terakhir)
			if(($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']<$tgl_hpsAkhir['tgl_penghapusan']) || ($old_pemelihara['tgl_pemeliharaan']==$tgl_hpsAkhir['tgl_penghapusan'] && $old_pemelihara['tgl_create']<$tgl_hpsAkhir['tgl_create'])){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit, sudah ada penghapusan sebagian !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit, sudah ada penghapusan sebagian !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit, sudah ada penghapusan sebagian !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit, sudah ada penghapusan sebagian !';
			}
			//cek (tgl pelihara < tgl terakhir t_history_aset) atau ( tgl pelihara = tgl terakhir tapi tgl create pelihara < tgl create terakhir ) utk idbiawal yg sama
			if(($errmsg=='' && $fmTANGGALPEMELIHARAAN<$tgl_asetAkhir['tgl']) || ($fmTANGGALPEMELIHARAAN==$tgl_asetAkhir['tgl'] && $old_pemelihara['tgl_create']<$tgl_asetAkhir['tgl_create'])){
				if($errmsg=='' && $old_pemelihara['tgl_pemeliharaan']!=$fmTANGGALPEMELIHARAAN)$errmsg = 'Tanggal Buku Pemeliharaan tidak bisa di edit, sudah ada perubahan status aset !';
				if($errmsg=='' && $old_pemelihara['tgl_perolehan']!=$fmTANGGALPerolehan)$errmsg = 'Tanggal Perolehan tidak bisa di edit, sudah ada perubahan status aset !';
				if($errmsg=='' && $old_pemelihara['biaya_pemeliharaan']!=$fmBIAYA)$errmsg = 'Biaya Pemeliharaan	tidak bisa di edit, sudah ada perubahan status aset !';
				if($errmsg=='' && $old_pemelihara['tambah_aset']!=$fmTAMBAHASET)$errmsg = 'Manambah Aset tidak bisa di edit, sudah ada perubahan status aset !';
				if($errmsg=='' && $old_pemelihara['tambah_masamanfaat']!=$fmTAMBAHMasaManfaat)$errmsg = 'Manambah Masa Manfaat tidak bisa di edit, sudah ada perubahan status aset !';
			}						
		}
				
		if ($errmsg ==''){
			$bi =  mysql_fetch_array( mysql_query (				
				" select tgl_buku,status_barang from buku_induk where id ='$idbi'"
			));
			if ($errmsg =='' && compareTanggal($fmTANGGALPEMELIHARAAN, $bi['tgl_buku'])==0  ) $errmsg = 'Tanggal Pemeliharaan tidak lebih kecil dari Tanggal Buku Barang !';
			if ($errmsg =='' && $bi['status_barang']==5 ) $errmsg = 'Gagal Simpan. Barang untuk Pemeliharaan ini sudah diganti rugi!';
					
		}
		
		if( $errmsg =='' & (!($fmSURATTANGGAL == '0000-00-00' || $fmSURATTANGGAL==''))){
		 	if( !cektanggal($fmSURATTANGGAL)){ 	$errmsg = 'Tanggal Surat Salah!';}
			if ($errmsg =='' && compareTanggal($fmSURATTANGGAL, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Surat tidak lebih besar dari Hari ini!';				
		}
		if ($errmsg=='' && $fmst==1){
		 $errmsg=Pelihara_CekdataCutoff('insert',$idplh,$fmTANGGALPEMELIHARAAN,$idbi);	
		 }
				
		if($errmsg == ''){	
			if ($fmst==1){//simpan baru
				$isibi= table_get_rec("select * from view_buku_induk2 where id = '$idbi'" );
				$idbi_awal = $isibi['idawal'] ==''? $isibi['id']: $isibi['idawal'];				
				$aqry = "insert into pemeliharaan (id_bukuinduk, 				
					tgl_pemeliharaan, jenis_pemeliharaan, pemelihara_instansi, pemelihara_alamat,
					surat_no, surat_tgl, biaya_pemeliharaan,  ket, idbi_awal, tgl_update, uid ,tambah_aset,tambah_masamanfaat, cara_perolehan,tgl_perolehan,no_bast ) 
					values ('$idbi',					
						'$fmTANGGALPEMELIHARAAN', '$fmJENISPEMELIHARAAN', '$fmPEMELIHARAINSTANSI', '$fmPEMELIHARAALAMAT', 
						'$fmSURATNOMOR', '$fmSURATTANGGAL', '$fmBIAYA',  '$fmKET',
						'$idbi_awal', now(), '$UID', '$fmTAMBAHASET' , '$fmTAMBAHMasaManfaat' , '$cara_perolehan' ,'$fmTANGGALPerolehan','$fmNOMORba'			
					) ";//echo "errmsg=$aqry<r>";
				$sukses = mysql_query($aqry);				
				if($sukses && $fmTAMBAHASET==1 ){
					$id = mysql_insert_id();
					if($Main->MODUL_JURNAL)jurnalPemeliharaan($id,$UID,1);
				}
			}else{//smpan edit
				if ($errmsg=='') $errmsg=Pelihara_CekdataCutoff('edit',$idplh,$fmTANGGALPEMELIHARAAN,$idbi_awal);	
				
				if($errmsg ==''){
					//ambil id buku induk
					$old = mysql_fetch_array(mysql_query(
						"select idbi_awal, id_bukuinduk from pemeliharaan where id='$idplh'"
					));		
					//cek status barangnya
					$penatausaha = mysql_fetch_array(mysql_query(
						"select status_barang from buku_induk where id='{$old['id_bukuinduk']}'"
					));
					if ($errmsg =='' && $penatausaha['status_barang']==3 ) $errmsg = "Gagal Edit. Barang untuk Pemeliharaan ini sudah dihapuskan!";
					if ($errmsg =='' && $penatausaha['status_barang']==4 ) $errmsg = 'Gagal Edit. Barang untuk Pemeliharaan ini sudah dipindah tangankan!';
					if ($errmsg =='' && $penatausaha['status_barang']==5 ) $errmsg = 'Gagal Edit. Barang untuk Pemeliharaan ini sudah diganti rugi!';
				}															
				
				if($errmsg ==''){
					$old= mysql_fetch_array(mysql_query("select * from pemeliharaan where id='$idplh'"));
					$aqry = "update pemeliharaan set
						tgl_pemeliharaan = '$fmTANGGALPEMELIHARAAN', 
						jenis_pemeliharaan = '$fmJENISPEMELIHARAAN', 
						pemelihara_instansi = '$fmPEMELIHARAINSTANSI', 
						pemelihara_alamat = '$fmPEMELIHARAALAMAT',
						surat_no = '$fmSURATNOMOR', 
						surat_tgl = '$fmSURATTANGGAL', 
						biaya_pemeliharaan = '$fmBIAYA',
						cara_perolehan = '$cara_perolehan',
						ket = '$fmKET',
						tgl_update = now(),
						uid = '$UID',
						tambah_aset = '$fmTAMBAHASET',					
						tambah_masamanfaat = '$fmTAMBAHMasaManfaat',					
						tgl_perolehan = '$fmTANGGALPerolehan',				
						no_bast = '$fmNOMORba'					
						where id = '$idplh'";//echo "aqry=$aqry<r>";
					$sukses = mysql_query($aqry);	
				//$errmsg='qry='.$aqry;
					//if($fmTAMBAHASET==1 ){						
					if($sukses) {
						if($Main->MODUL_JURNAL){
							if($old['tambah_aset']==$fmTAMBAHASET && $fmTAMBAHASET ==1 ) jurnalPemeliharaan($idplh,$UID, 2);
							if($old['tambah_aset']==0 && $fmTAMBAHASET ==1 ) jurnalPemeliharaan($idplh,$UID, 1); //$errmsg= $plh['cek']; $sukses=FALSE;
							if($old['tambah_aset']==1 && $fmTAMBAHASET ==0 ) jurnalPemeliharaan($idplh,$UID, 3);
						}
					}
					//	$qrupdt = "update jurnal set stbatal=0 where refid='$idplh' and jns_trans2=3 "; 
					//	mysql_query($qrupdt);
					//}else{
						//mysql_query("delete from jurnal where refid='$idplh' and $jns_trans2=3 ");
					//	$qrupdt = "update jurnal set stbatal=1 where refid='$idplh' and jns_trans2=3 "; 
					//	mysql_query($qrupdt);
					//}
				}																		
			}			
			if($sukses){
				//KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
				//$errmsg = 'Data telah di ubah dan simpan';				
			}else{
				if($errmsg ==''){
					$errmsg = 'Data Tidak dapat di ubah atau di simpan';	
				};				
			}
		}
	}else{
		$errmsg = 'Gagal Simpan Data. Anda Tidak Mempunyai Hak Akses!';	
	}
	return $errmsg;
}

function Pelihara_GetData($id){
	global $fmNOREG, $fmTANGGALPEMELIHARAAN, $fmJENISPEMELIHARAAN,
			$fmPEMELIHARAINSTANSI, $fmPEMELIHARAALAMAT, $fmSURATNOMOR, 
			$fmSURATTANGGAL, $fmBIAYA, $fmBUKTIPEMELIHARAAN, $fmKET, $fmTAMBAHASET, $fmTAMBAHMasaManfaat,
			$cara_perolehan,$fmTANGGALPerolehan,$fmNOMORba;
	global $idbi; //idbi nya
	global $idbi_awal; //idbi_awal nya
			
	
	$aqry = "select * from pemeliharaan where id = '$id'";
	$qry = mysql_query($aqry);
	if ($isi = mysql_fetch_array($qry)){
		$fmTANGGALPEMELIHARAAN = $isi['tgl_pemeliharaan']=='0000-00-00'? '': $isi['tgl_pemeliharaan'];
		$fmJENISPEMELIHARAAN = $isi['jenis_pemeliharaan'];//'fmJENISPEMELIHARAAN';
		$fmPEMELIHARAINSTANSI = $isi['pemelihara_instansi'];//'fmPEMELIHARAINSTANSI';
		$fmPEMELIHARAALAMAT = $isi['pemelihara_alamat'];//'fmPEMELIHARAALAMAT';
		$fmSURATNOMOR = $isi['surat_no'];//'fmSURATNOMOR';
		$fmSURATTANGGAL = $isi['surat_tgl'] == '0000-00-00'? '': $isi['surat_tgl'];//'2010-02-15';
		$fmBIAYA = $isi['biaya_pemeliharaan'];//'fmBIAYA';
		$fmBUKTIPEMELIHARAAN = $isi['bukti_pemeliharaan'];//'fmBUKTIPEMELIHARAAN';
		$fmKET = $isi['ket'];//'fmKET';
		$fmTAMBAHASET = $isi['tambah_aset'];
		$fmTAMBAHMasaManfaat = $isi['tambah_masamanfaat'];
		$idbi= $isi['id_bukuinduk'];//'333';
		$idbi_awal= $isi['idbi_awal'];//'333';
		$cara_perolehan = $isi['cara_perolehan'];
		$fmTANGGALPerolehan = $isi['tgl_perolehan'];
		$fmNOMORba = $isi['no_bast'];
	}
	
	
}

function Pelihara_FormEntry(){
	global $fmNOREG, $fmTANGGALPEMELIHARAAN, $fmJENISPEMELIHARAAN,
			$fmPEMELIHARAINSTANSI, $fmPEMELIHARAALAMAT, $fmSURATNOMOR, 
			$fmSURATTANGGAL, $fmBIAYA, $fmBUKTIPEMELIHARAAN, $fmKET, $fmTAMBAHASET,$fmTAMBAHMasaManfaat,
			$cara_perolehan,$fmTANGGALPerolehan,$fmNOMORba;	
	global   $ActEntry, $AmbilData;			//$fmst, $idbi, $idbi_awal, $idplh, 
	global $Main;	
	global $idbi_awal,$idbi;	
	
//echo ",fmTAMBAHASET=$fmTAMBAHASET";
//if ( $fmTAMBAHASET  ) $fmTAMBAHASET = 1; //default
//echo ",fmTAMBAHASET=$fmTAMBAHASET";
	$arrCaraPerolehan = array(
		array('1','Belanja Modal'),
		array('2','Belanja Barang Jasa'),
		array('3','Hibah'),
		array('4','Penggabungan'),
	);
$fmTmbahAset_checked =$fmTAMBAHASET==1?'checked':'';
$fmTmbahMasaManfaat_checked =$fmTAMBAHMasaManfaat==1?'checked':'';
$cara_perolehan =$cara_perolehan==NULL?'2':$cara_perolehan;
$space = formEntryBase2('','','',"","",'','valign="middle" height="8"');
	return "
		<A NAME='FORMENTRY'></A>
		<div><div>$FormEntry_Script
		
	
		$FormEntry_Hidden
		
		<input type='hidden' name='idbi_awal' id='idbi_awal' value='".$idbi_awal."' >
		<input type='hidden' name='idplh' id='idplh' value='".$_GET['idplh']."' >
		<input type='hidden' name='fmst' id='fmst' value='".$_GET['fmst']."' >
		</div></div>
	
	<table width=\"100%\"  height='100%' class=\"adminform\" style='border-width:0'><tr><td valign='top' style='padding:8;'>
	<table width='100%'>

	
			
	".formEntryBase2('Tanggal Buku Pemeliharaan',':',
				createEntryTgl('fmTANGGALPEMELIHARAAN', $fmTANGGALPEMELIHARAAN, false, '','','FormPelihara','2').
				'&nbsp&nbsp<span style="color: red;"></span>'
				,"style='width:170'","style='width:10'",'','valign="middle" height="21"')."
	".formEntryBase2('Tanggal Perolehan',':',
				createEntryTgl('fmTANGGALPerolehan', $fmTANGGALPerolehan, false, '','','FormPelihara').
				'&nbsp&nbsp<span style="color: red;"></span>'
				,"style='width:150'","style='width:10'",'','valign="middle" height="21"')."

	".formEntryBase2('Uraian Pemeliharaan',':',		
		"<textarea name=\"fmJENISPEMELIHARAAN\"  id=\"fmJENISPEMELIHARAAN\" cols='51' >$fmJENISPEMELIHARAAN</textarea>"
		,"style='width:150'","style='width:10'",'','valign="top" height="21"')."
	
			
	".formEntryBase2('Yang Memelihara','','',"style=''","style=''",'','valign="middle" height="21"')."
		
	".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Instansi/CV/PT',':',
		txtField('fmPEMELIHARAINSTANSI',$fmPEMELIHARAINSTANSI,'100','50','text')
		,'','valign="middle" height="21"')."
	
	".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',':',
		txtField('fmPEMELIHARAALAMAT',$fmPEMELIHARAALAMAT,'100','50','text')
		,'','valign="middle" height="21"')."
		
	".formEntryBase2('Surat Perjanjian / Kontrak','','',"style=''","style=''",'','valign="middle" height="21"')."
		
	".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor',':',
		txtField('fmSURATNOMOR',$fmSURATNOMOR,'100','50','text')
		,"style=''","style=''",'','valign="middle" height="21"')."
	".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor Bast',':',
		txtField('fmNOMORba',$fmNOMORba,'100','50','text')
		,"style=''","style=''",'','valign="middle" height="21"')."
	
	".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal',':',
				createEntryTgl('fmSURATTANGGAL', $fmSURATTANGGAL, false, '', '','FormPelihara').
				'&nbsp&nbsp<span style="color: red;"></span>'
				,'','','','valign="middle" height="24"')."		
	
	".formEntryBase2('Biaya Pemeliharaan',':',
			'Rp.'.inputFormatRibuan("fmBIAYA", 
			 	($entryMutasi==FALSE? '':' readonly="" ') ),'','','','valign="top" height="21"')."
	".formEntryBase2('Cara Perolehan',':', 
			cmb2D_v2('cara_perolehan', $cara_perolehan, $arrCaraPerolehan )
			,'','','','valign="top" height="21"')."		
	".formEntryBase2('Manambah Nilai Aset',':',
			"<input id='fmTAMBAHASET' type='checkbox' value='checked' $fmTmbahAset_checked > Ya" 
			,'','','','valign="top" height="21"')."
	".formEntryBase2('Manambah Masa Manfaat',':',
			"<input id='fmTAMBAHMasaManfaat' type='checkbox' value='checked' $fmTmbahMasaManfaat_checked onclick='javascript:checkedMM()'> Ya" 
			,'','','','valign="top" height="21"')."	
	<!--".formEntryBase2('Manambah Aset',':',
			cmb2D('fmTAMBAHASET',$fmTAMBAHASET,$Main->ArYaTidak,'') 
			,'','','','valign="top" height="21"')."-->

	<!--".formEntryBase2('Bukti Pemeliharaan',':',
		txtField('fmBUKTIPEMELIHARAAN',$fmBUKTIPEMELIHARAAN,'50','50','text')
		,"style=''","style=''",'','valign="middle" height="21"')."-->

	
	".formEntryBase2('Keterangan',':',
		"<textarea name=\"fmKET\"  id=\"fmKET\" cols='51' >$fmKET</textarea>"
		,"style=''","style=''",'','valign="top" height="21"')."
	</table></td></tr>
	</table>
	
	";
}

function Pelihara_List($Tbl, $Fields='*', $Kondisi='', $Limit='', $Order='', $Style=1, $TblStyleClass='koptable', 
	$Cetak=FALSE,$NoAwal=0, $ReadOnly=FALSE, $fmKIB='',$xls=FALSE){
	//style(list,header, jml hal, jml tot, hal, menu)
	
	global $Main, $jmlTampilPLH;
	
	$TdStyle=$Cetak ? 'GarisCetak':'GarisDaftar';
	
	$arrCaraPerolehan = array(
		array('1','Belanja Modal'),
		array('2','Belanja Barang Jasa'),
		array('3','Hibah'),
		array('4','Penggabungan'),
	);
	
	//list ------------------------------------
	//if ($Style[0]==1){
	$no=$NoAwal;//$Main->PagePerHal * (($HalPLH*1) - 1);
	$jmlTampilPLH= 0;
	$cb = 0;		
	$aqry="select $Fields from $Tbl $Kondisi $Order $Limit "; //echo " $aqry<br>";
	$Qry = mysql_query($aqry);	
	while ($isi = mysql_fetch_array($Qry)){
	
		$jmlTampilPLH++;
		$no++;
		$jmlTotalHargaDisplay += $isi['biaya_pemeliharaan'];
		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$kdKelBarang = $isi['f'].$isi['g']."00";
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$get_bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'"));
		$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
		$clRow = $no % 2 == 0 ?"row1":"row0";
						
		global $ISI5, $ISI6;										
		$ISI5 = ''; $ISI6 = '';			
		$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and e1 = '{$isi['e1']}' and 
					f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
					tahun = '{$isi['thn_perolehan']}' and noreg = '{$isi['noreg']}'  ";
				//echo "KondisiKIB=$KondisiKIB";
		if($fmKIB==''){
			Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );
		}else{
			Penatausahaan_BIGetKib_hapus($isi['f'], $KondisiKIB );	
		}
		$tampilAlamat = $Style ==1? '':
			"<td class='$TdStyle' align=left>$ISI5 </td>	<td class='$TdStyle' align=left>$ISI6/ <br>{$isi['no_polisi']}</td>	";
		$TampilCheckBox = $Cetak? '' : "<td class=\"$TdStyle\" align='center'><input type=\"checkbox\" id=\"cbPLH$cb\" name=\"cidPLH[]\" value=\"{$isi['id']}\" onClick=\"isChecked2(this.checked,'Pelihara_checkbox');\" />&nbsp;</td>";
		$TampilCheckBoxKol1 = $Style==1? '' : $TampilCheckBox;	
		$TampilCheckBoxKol2 = $Style==1? $TampilCheckBox	: '';	
		
		$List_ = $Style==2?
			"<td class=\"$TdStyle\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}<br>{$isi['id_bukuinduk']}<br>{$isi['idbi_awal']}</td>
			<td class=\"$TdStyle\" align=center>{$isi['noreg']}/<br>{$isi['thn_perolehan']}</td>
			<td class=\"$TdStyle\">{$nmBarang['nm_barang']}/<br>{$get_bi['penggunaan']} </td>
			$tampilAlamat"
			:"";		
		/*$CheckBox = $Style==1?
			"<td class=\"$TdStyle\" align='center'><input type=\"checkbox\" id=\"cbPLH$cb\" name=\"cidPLH[]\" value=\"{$isi['id']}\" onClick=\"isChecked2(this.checked,'Pelihara_checkbox');\" />&nbsp;</td>"
			:"";
		*/
		$vtambah_aset = $isi['tambah_aset']==1? 'Ya':'Tidak';	
		$vtambah_manfaat = $isi['tambah_masamanfaat']==1? 'Ya':'Tidak';	
		if ($xls){
		$List_ = 
			"<td class=\"$TdStyle\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}<br>{$isi['id_bukuinduk']}<br>{$isi['idbi_awal']}</td>
			<td class=\"$TdStyle\" align=center><div class=nfmt3>{$isi['noreg']}</div>/<br>{$isi['thn_perolehan']}</td>
			<td class=\"$TdStyle\">{$nmBarang['nm_barang']} </td>
			$tampilAlamat";
					
		$ListData .= "	
			<tr class='$clRow' height='21'>
			<td class=\"$TdStyle\" align=center>$no</td>
			$TampilCheckBoxKol1
			$List_
			<td class=\"$TdStyle\" align=center>".TglInd($isi['tgl_pemeliharaan'])."</td>
			<td class=\"$TdStyle\">{$isi['jenis_pemeliharaan']}</td>
			<td class=\"$TdStyle\">{$isi['pemelihara_instansi']}</td>
			<td class=\"$TdStyle\">{$isi['pemelihara_alamat']}</td>
			<td class=\"$TdStyle\">{$isi['surat_no']}</td>
			<td class=\"$TdStyle\" align=center>".TglInd($isi['surat_tgl'])."</td>
			<td class=\"$TdStyle\" align=right>".number_format(($isi['biaya_pemeliharaan']), 2, '.', '')."</td>
			<!--<td class=\"$TdStyle\">{$isi['bukti_pemeliharaan']}</td>-->".
			"<td class=\"$TdStyle\">". $arrCaraPerolehan[$isi['cara_perolehan']-1 ][ 1] ."</td>".
			"<td class=\"$TdStyle\">{$isi['ket']}</td>
			$TampilCheckBoxKol2
			</tr>
		";
			
		} else {
		if($isi['surat_tgl']=="0000-00-00"){
			$tgl_surat="<br>";
		}else{
			$tgl_surat="<br>{$isi['surat_tgl']}";
		}
		$ListData .= "	
			<tr class='$clRow' height='21'>
			<td class=\"$TdStyle\" align=center>$no </td>
			$TampilCheckBoxKol1
			$List_
			<td class=\"$TdStyle\" align=center>".TglInd($isi['tgl_perolehan'])."/<br>".TglInd($isi['tgl_pemeliharaan'])."</td>
			<td class=\"$TdStyle\">". $arrCaraPerolehan[$isi['cara_perolehan']-1 ][ 1] ."</td>
			<td class=\"$TdStyle\">{$isi['jenis_pemeliharaan']}</td>			
			<td class=\"$TdStyle\" align=right>".number_format(($isi['biaya_pemeliharaan']), 2, ',', '.')."</td>
			<td class=\"$TdStyle\" align=center>$vtambah_aset</td>
			<td class=\"$TdStyle\" align=center>$vtambah_manfaat</td>".			
			"<td class=\"$TdStyle\">{$isi['ket']}<br>{$isi['surat_no']}$tgl_surat<br>{$isi['pemelihara_instansi']}</td>
			$TampilCheckBoxKol2
			</tr>
		";
			
		}
		$cb++;
	}
		
	//}
	
	//header-----------------------------------	
	if($fmKIB=='01' || $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){
		$tampilMerk = "<th class='th01' style='width:200'>Alamat</th>";
	}else{
		$tampilMerk = "<th class='th01' width='150'>Merk/ Tipe/ Alamat</th>";	
	}
	if($fmKIB=='01' ){
		$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat/ Tanggal/ Hak</th>";
	}else if( $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){
		$tampilSert = "<th class=\"th01\" width='70'>No. Dokumen/ Tanggal </th>";
	}else{
		$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat/ No. Pabrik/ No. Chasis/ No. Mesin/ No.Polisi</th>";	
	}
	$tampilSertMerk	= $tampilMerk .$tampilSert;	
	$TampilCheckBox = $Cetak? '' : "<TH class=\"th01\" rowspan=2 style='width:30'><input type=\"checkbox\" name=\"Pelihara_toggle\" value=\"\" onClick=\"checkAll1b($jmlTampilPLH,'cbPLH','Pelihara_toggle','Pelihara_checkbox');\" /></TH>";
	$TampilCheckBoxKol1 = $Style==1? '' : $TampilCheckBox;	
	$TampilCheckBoxKol2 = $Style==1? $TampilCheckBox	: '';	
	
	$Head1 = $Style==2?
		"<TH class=\"th01\" rowspan=2>Kode Barang/<br>Id Barang/<br>Id Awal</TH>
		<TH class=\"th01\" rowspan=2>No.<br>Register/<br>Thn</TH>
		<TH class=\"th02\" colspan=3>Spesifikasi Barang</TH>"
		:"";
	$Head2 =  $Style==2?
		"<TH class=\"th01\" rowspan=1 style='width:200'>Nama Barang/<br>Penggunaan</TH>
		$tampilSertMerk	"
		: '';		
	/*$CheckBox = $Style==1?
		"<TH class=\"th01\" rowspan=2 style='width:30'><input type=\"checkbox\" name=\"Pelihara_toggle\" value=\"\" onClick=\"checkAll1b($jmlTampilPLH,'cbPLH','Pelihara_toggle','Pelihara_checkbox');\" /></TH>"
		:"";*/
	
	$pelihara_header = 
		"<TR>
			<TH class=\"th01\" rowspan=2 style='width:40'>No</TD>
			$TampilCheckBoxKol1
			$Head1
			<TH class=\"th01\" rowspan=2 style='width:70'>Tanggal Perolehan/ Tanggal Buku</TH>			
			<TH class=\"th01\" rowspan=2 style=''>Cara Perolehan</TH>
			<TH class=\"th01\" rowspan=2 style=''>Uraian<br>Pemeliharaan</TH>			
			<TH class=\"th01\" rowspan=2 style='width:100'>Biaya Pemeliharaan</TH>
			<TH class=\"th02\" colspan=2 style='width:100'>Menambah</TH>
			<TH class=\"th01\" rowspan=2 style='width:130'>Keterangan</TH>
			$TampilCheckBoxKol2
		</TR>
		<TR>
			$Head2	
			<th class='th01' width='50'>Nilai Aset</th>		
			<th class='th01' width='50'>Masa Manfaat</th>		
		</TR>
		";
	//}
	
	//total & Hal ----------------------------------------------------------
	if ($Style==2 && $Cetak==FALSE ){			
		//total ------------
		$totalHal = "
			<tr class='row0'>
			<td colspan=10 class=\"$TdStyle\">Total Harga per Halaman </td>
			<td align=right class=\"$TdStyle\"><b>".number_format(($jmlTotalHargaDisplay), 2, ',', '.')."</td>
			<td colspan=4  class=\"$TdStyle\">&nbsp;</td>
			</tr>";	
		$aqry = "select sum(biaya_pemeliharaan) as total  from $Tbl $Kondisi"; // echo " $aqry<br>";
		$jmlTotalHarga =  table_get_value($aqry,'total');
		if ($xls){
		$totalAll = "
			<tr class='row0'>
			<td class=\"$TdStyle\" colspan=10 >Total Harga Seluruhnya </td>
			<td class=\"$TdStyle\" align=right><b>".number_format(($jmlTotalHarga), 2, '.', '')."</td>
			<td class=\"$TdStyle\" colspan=3 >&nbsp;</td>
			</tr>";
			
		} else {
		$totalAll = "
			<tr class='row0'>
			<td class=\"$TdStyle\" colspan=10 >Total Harga Seluruhnya </td>
			<td class=\"$TdStyle\" align=right><b>".number_format(($jmlTotalHarga), 2, ',', '.')."</td>
			<td class=\"$TdStyle\" colspan=3 >&nbsp;</td>
			</tr>";
			
		}
		//Hal --------------	
		$aqry = "select count(*) as cnt  from $Tbl $Kondisi"; // echo " $aqry";
		$jmlDataPLH =  table_get_value($aqry,'cnt');
		$Hal = "<tr>
				<td colspan=19 align=center>".Halaman($jmlDataPLH,$Main->PagePerHal,"HalPLH")."</td>
			</tr>";
	}
	if ($Cetak){
		$totalHal = "
			<tr class='row0'>
			<td colspan=13 class=\"$TdStyle\">Total </td>
			<td align=right class=\"$TdStyle\"><b>".number_format(($jmlTotalHargaDisplay), 2, ',', '.')."</td>
			<td colspan=2  class=\"$TdStyle\">&nbsp;</td>
			</tr>";	
	}
	
	
	//menu ----------------------------------------------------------
	if ($Style==1){
		$Pelihara_Menu = $ReadOnly? '':
			"<li><a href='javascript:PeliharaHapus.Hapus()' title='Hapus Pemeliharaan' class='btdel'></a></li>
			<li><a href='javascript:PeliharaForm.Edit()' title='Edit Pemeliharaan' class='btedit'></a>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'></li>
				</ul>
			</li>
			<li><a  href='javascript:PeliharaForm.Baru()' title='Tambah Pemeliharaan' class='btadd'></a>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'>	</li>
				</ul>
			</li>	";
		$Pelihara_Menu=
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
			<div class='menuBar2' style='' >
			<ul>
			$Pelihara_Menu
			<li><a  href='javascript:PeliharaRefresh.Refresh()' title='Refresh Pemeliharaan' class='btrefresh'></a></li>
			<!--<li><a style='padding:2;width:55;color:white;font-size:11;' href='javascript:PeliharaRefresh.Refresh()' title='Refresh Pemeliharaan' class=''>[ Refresh ]</a></li>-->
			</ul>	
			<a id='pelihara_jmldata' style='cursor:default;position:relative;left:2;top:2;color:gray;font-size:11;' title='Jumlah Pemeliharaan'>[ $jmlTampilPLH ]</a>
	
			</div>
			</td></tr></table>";
			//echo "jmlTampilPLH = $jmlTampilPLH";
	}
	
		
	return "
			$Pelihara_Menu
			<table class='$TblStyleClass' border='1' width='100%'  >
			$pelihara_header
			$ListData			
			$totalHal
			$totalAll
			$Hal
			</table>
			<input type='hidden' value='' id='Pelihara_checkbox' >
			<input type='hidden' value='$jmlTampilPLH' id='jmlTampilPLH' >
			"
			;
}

function Pelihara_Hapus(){
	global $Main;
	$errmsg='';$Del = FALSE;
	$cidPLH= $_GET['cidPLH'];
	for($i = 0; $i<count($cidPLH); $i++)	{
		//$id= $cidPLH[$i]; //$str.=$id.'-';
		if($errmsg ==''){
			//ambil id buku induk
			$pelihara = mysql_fetch_array(mysql_query(
				"select idbi_awal, tgl_pemeliharaan, id_bukuinduk from pemeliharaan where id='{$cidPLH[$i]}'"
			));		
			//cek status barangnya
			$penatausaha = mysql_fetch_array(mysql_query(
				"select status_barang,c,d,e,e1 from buku_induk where id='{$pelihara['id_bukuinduk']}'"
			));
			//cek sudah ada penyusutan / tdk
			$fmTANGGALPEMELIHARAAN = $pelihara['tgl_pemeliharaan'];
			$idbi_awal = $pelihara['idbi_awal'];
			$idbi = $pelihara['id_bukuinduk'];
			$thn_pelihara = substr($fmTANGGALPEMELIHARAAN,0,4);
			$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$idbi' and tahun>='$thn_pelihara'";
			$get_susut = mysql_fetch_array(mysql_query($query_susut));
			if($get_susut['jml_susut']>0){
				//$errmsg="Id ".$cidPLH[$i].", Sudah ada penyusutan !";
				$errmsg="Data tidak bisa di hapus,Sudah ada penyusutan !";
			}
			//cek sudah ada Closing untuk data baru
			if(sudahClosing($fmTANGGALPEMELIHARAAN,$penatausaha['c'],$penatausaha['d'],$penatausaha['e'],$penatausaha['e1'])){
				$errmsg = "Data tidak bisa di hapus,Tanggal Sudah Closing !";
			}			
			//cek ada tgl_koreksi dan tgl_koreksi > tgl_pemeliharaan
			$get_koreksi = mysql_fetch_array(mysql_query("select count(*) as cnt from t_koreksi where idbi_awal='$idbi_awal' and tgl>'$fmTANGGALPEMELIHARAAN'"));
			//$errmsg='old='.$old_pemelihara['biaya_pemeliharaan'];
			if($errmsg =='' && $get_koreksi['cnt']>0)$errmsg = 'Data tidak bisa di hapus,karena sudah ada koreksi harga setelah nya ! !';
			//--------------------------------------
					
			if ($errmsg =='' && $penatausaha['status_barang']==3 ) $errmsg = "Gagal Hapus. Barang untuk Pemeliharaan ini sudah dihapuskan!";
			if ($errmsg =='' && $penatausaha['status_barang']==4 ) $errmsg = 'Gagal Hapus. Barang untuk Pemeliharaan ini sudah dipindah tangankan!';
			if ($errmsg =='' && $penatausaha['status_barang']==5 ) $errmsg = 'Gagal Hapus. Barang untuk Pemeliharaan ini sudah diganti rugi!';
		}
		$xid=$cidPLH[$i];
		if ($errmsg=='') $errmsg=Pelihara_CekdataCutoff('hapus',$xid,'');			
		if($errmsg==''){			
			$Del = mysql_query("delete from pemeliharaan where id='{$cidPLH[$i]}' limit 1");
			
			if (!$Del){
				$errmsg = "Gagal Hapus ID {$cidPLH[$i]} ";
			}else{
				if($Main->MODUL_JURNAL) jurnalPemeliharaan($cidPLH[$i],'',3);
			}
		}
		
		if($errmsg != '') break;
	}
	return $errmsg;
}

function Pelihara_createScriptJs($Style=1){
	switch( $Style){
		case 1:{
			return "
				<div><div><script>
				PeliharaRefresh= new AjxRefreshObj('PeliharaList','Pelihara_cover', 'divPeliharaList', new Array('idbi_awal') );
				PeliharaSimpan= new AjxSimpanObj('PeliharaSimpan','PeliharaSimpan_cover',
					new Array('fmTANGGALPEMELIHARAAN','fmJENISPEMELIHARAAN','fmPEMELIHARAINSTANSI','cara_perolehan',
						'fmPEMELIHARAALAMAT','fmSURATNOMOR','fmSURATTANGGAL','fmBIAYA',
						'fmKET','fmTAMBAHASET','fmTAMBAHMasaManfaat','fmTANGGALPerolehan','fmNOMORba','idbi','idbi_awal','idplh','fmst'),
						'PeliharaForm.Close();PeliharaRefresh.Refresh();' );
				PeliharaForm= new AjxFormObj('PeliharaForm','Pelihara_cover','Pelihara_checkbox','jmlTampilPLH', 
					'cbPLH', new Array('idbi','idbi_awal'), 'document.getElementById(\'fmTANGGALPEMELIHARAAN_tgl\').focus()');
				PeliharaHapus= new AjxHapusObj('PeliharaHapus',  'Pelihara_cover', 'Pelihara_checkbox', 'jmlTampilPLH', 
					'cbPLH', 'cidPLH', 'PeliharaRefresh.Refresh();');					
				function checkedMM(){						
					//alert('TES !');
					if(document.getElementById('fmTAMBAHMasaManfaat').checked==true){
					document.getElementById('fmTAMBAHASET').checked = true;
					}else{
					document.getElementById('fmTAMBAHASET').checked = false;
					}
				}		
				</script></div></div>
			";		
			break;
		}
		case 2:{
			$refresh= '';//document.getElementById(\'btTampil\').click()';
			return "
				<div><div><script>
				//PeliharaRefresh= new AjxRefreshObj('PeliharaList','Pelihara_cover', 'divPeliharaList', new Array('idbi_awal') );
				PeliharaSimpan= new AjxSimpanObj('PeliharaSimpan','PeliharaSimpan_cover',
					new Array('fmTANGGALPEMELIHARAAN','fmJENISPEMELIHARAAN','fmPEMELIHARAINSTANSI','cara_perolehan',
						'fmPEMELIHARAALAMAT','fmSURATNOMOR','fmSURATTANGGAL','fmBIAYA',
						'fmKET','fmTAMBAHASET','fmTAMBAHMasaManfaat','fmTANGGALPerolehan','fmNOMORba','idplh','idbi_awal','fmst'),
						'PeliharaForm.Close();', 'document.getElementById(\'btTampil\').click();');
				PeliharaForm= new AjxFormObj('PeliharaForm','Pelihara_cover','Pelihara_checkbox','jmlTampilPLH', 
					'cbPLH', new Array(), 'document.getElementById(\'fmTANGGALPEMELIHARAAN_tgl\').focus()');
				PeliharaHapus= new AjxHapusObj('PeliharaHapus',  'Pelihara_cover', 'Pelihara_checkbox', 'jmlTampilPLH', 
					'cbPLH', 'cidPLH', '$refresh');
				function checkedMM(){						
					//alert('TES !');
					if(document.getElementById('fmTAMBAHMasaManfaat').checked==true){
					document.getElementById('fmTAMBAHASET').checked = true;
					}else{
					document.getElementById('fmTAMBAHASET').checked = false;
					}
				}					
				</script></div></div>
			";		
			break;
		}
		
	} 
	
}

function Pelihara_CekdataCutoff($mode='insert',$id='',$tgl='',$idbi=''){
global $Main;

$usrlevel=$Main->UserLevel;
$errmsg='';
if ($usrlevel!='1'){
	

$tglcutoff=$Main->TAHUN_CUTOFF."-12-31";

switch ($mode){
	

case 'insert':{
	if ($tgl<$tglcutoff) $errmsg="Tgl. pemeliharaan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.")";

		 	
	break;
}
case 'edit':{
//	if ($tgl<$tglcutoff) $errmsg="Data dengan tgl. penghapusan lebih kecil dari tgl. ".$tglcutoff; 
			//cek tanggal buku
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from pemeliharaan where id='$id'"));
				$tgl=$datax['tgl_pemeliharaan'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. pemeliharaan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat diedit";
	}
	
	break;
}
case 'hapus':{
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from pemeliharaan where id='$id'"));	
				$tgl=$datax['tgl_pemeliharaan'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. pemeliharaan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat dihapus";
	}
	
	break;
}	
}

}
return $errmsg;

}
?>