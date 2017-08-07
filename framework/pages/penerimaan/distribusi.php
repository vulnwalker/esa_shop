<?php

class DistribusiObj  extends DaftarObj2{	
	var $Prefix = 'Distribusi';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pengeluaran'; //daftar
	var $TblName_Hapus = 'pengeluaran';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('jml_barang','harga','jml_harga','jml_posting','blm_posting');//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array( 5,6,7,13);
	var $totalCol = 13; //total kolom daftar
	var $FieldSum_Cp1 = array( 6, 4,4);//berdasar mode
	var $FieldSum_Cp2 = array( 6, 7, 7);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penerimaan, Penyimpanan dan Penyaluran';
	var $PageIcon = 'images/penerimaan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='distribusi.xls';
	var $Cetak_Judul = 'DAFTAR DISTRIBUSI BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $FormName = 'DistribusiForm'; 	
			
	function setTitle(){
		return 'Daftar Distribusi Barang Milik Daerah';
	}
	function setMenuEdit(){
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Verifikasi()","new_f2.png","Verifikasi",'Verifikasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Posting()","new_f2.png","Posting", 'Posting')."</td>"
			;
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 //Inisiasi DATA
	 //==================================
	 $idp=$_REQUEST['idp'];
	 $tgl_buku=$_REQUEST['tgl_buku'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
	 //$tahun = year($);
	
	 //data barang pengeluaran
	 $fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
	 $f = $fmIDBARANG[0];
	 $g = $fmIDBARANG[1];
	 $h = $fmIDBARANG[2];
	 $i = $fmIDBARANG[3];
	 $j = $fmIDBARANG[4];
	 $fmNMBARANG = $_REQUEST['fmNMBARANG'];
	 //Akun
	 $fmIDREKENING = explode('.',$_REQUEST['kode_account']);
	 $ka = $fmIDREKENING[0];
	 $kb = $fmIDREKENING[1];
	 $kc = $fmIDREKENING[2];
	 $kd = $fmIDREKENING[3];
	 $ke = $fmIDREKENING[4];
	 $kf = $fmIDREKENING[5];
	 $nama_account = $_REQUEST['nama_account'];
	 $hrg_perolehan = $_REQUEST['harga_satuan'];
	 $cara_perolehan = $_REQUEST['asal_usul'];	
	 $ba_no = $_REQUEST['ba_no'];	
	 $ba_tgl = $_REQUEST['ba_tgl'];
	 $thn = explode('-',$ba_tgl);
	 $thn_perolehan = $thn[0];
	 $jml_barang = $_REQUEST['jml_barang'];
	 
	
		/*
		switch($f){
			case'01': {
				if($_REQUEST['fmLUAS_KIB_A']=="" && $err=="")$err="Luas belum diisi!";
	 			if($_REQUEST['fmLETAK_KIB_A']=="" && $err=="")$err="Letak/Alamat belum diisi!";
	 			if($_REQUEST['alamat_kel']=="" && $err=="")$err="Kelurahan/Desa belum diisi!";
	 			if($_REQUEST['alamat_kec']=="" && $err=="")$err="Kecamatan belum diisi!";
	 			if($_REQUEST['alamat_b']=="" && $err=="")$err="Kota/Kabupaten belum diisi!";
	 			if($_REQUEST['fmHAKPAKAI_KIB_A']=="" && $err=="")$err="Hak belum dipilih!";
	 			if($_REQUEST['bersertifikat']!=""){
					if($_REQUEST['fmTGLSERTIFIKAT_KIB_A']=="" && $err=="")$err="Tanggal Sertifikat belum dipilih!";
	 				if($_REQUEST['fmNOSERTIFIKAT_KIB_A']=="" && $err=="")$err="Nomor Sertifikat belum diisi!";
				};
	 			if($_REQUEST['fmPENGGUNAAN_KIB_A']=="" && $err=="")$err="Pengguna belum diisi!";
	 			if($_REQUEST['fmKET_KIB_A']=="" && $err=="")$err="Keterangan belum diisi!";
				break;
			}
			case'02' : {
				if($_REQUEST['fmMERK_KIB_B']=="" && $err=="")$err="Merk/Type belum diisi!";
	 			if($_REQUEST['fmUKURAN_KIB_B']=="" && $err=="")$err="Ukuran/CC belum diisi!";
	 			if($_REQUEST['fmBAHAN_KIB_B']=="" && $err=="")$err="Bahan belum diisi!";
	 			if($_REQUEST['fmPABRIK_KIB_B']=="" && $err=="")$err="Nomor Pabrik belum diisi!";
	 			if($_REQUEST['fmRANGKA_KIB_B']=="" && $err=="")$err="Nomor Rangka belum diisi!";
	 			if($_REQUEST['fmMESIN_KIB_B']=="" && $err=="")$err="Nomor Mesin belum diisi!";
	 			if($_REQUEST['fmPOLISI_KIB_B']=="" && $err=="")$err="Nomor Polisi belum diisi!";
	 			if($_REQUEST['fmBPKB_KIB_B']=="" && $err=="")$err="Nomor BPKB belum diisi!";
	 			if($_REQUEST['fmKET_KIB_B']=="" && $err=="")$err="Keterangan belum diisi!";
				break;
			}
			case'03' : {
				
				break;
			}
			case'04' : {
				break;
			}
			case'05' : {
				break;
			}
			case'06' : {
				break;
			}
			case'07' : {
				break;
			}
		}*/
		
		switch($f){
			case '01':$tblkib="kib_a";break;
			case '02':$tblkib="kib_b";break;
			case '03':$tblkib="kib_c";break;
			case '04':$tblkib="kib_d";break;
			case '05':$tblkib="kib_e";break;
			case '06':$tblkib="kib_f";break;
			case '07':$tblkib="kib_g";break;
		}
			$jml_posting = 0;
			$loop = 5;
			if($jml_barang<$loop) $loop = $jml_barang;
			while($jml_posting<$loop){
				//cari noreg max
				$max = mysql_fetch_array(mysql_query("select (max(noreg)) as noregmax from buku_induk 
				 									where c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and 
													g='$g' and h='$h' AND i='$i' and j='$j' and thn_perolehan=$thn_perolehan"));
				
				$no= (int)$max['noregmax'];
				$no++;
				$noreg = sprintf("%04s", $no);
				
				if($err==''){
					//insert BI
					$query="insert into buku_induk ".
							"( kondisi,staset,status_barang,".
							" asal_usul,tgl_buku,".
							" a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,".
							" thn_perolehan,harga_beli,".
							" jml_harga,ref_idpengeluaran,".
							" noreg,".
							" uid,tgl_update) ".
							"values ".
							"( '1','3','1',".
							" '2','$tgl_buku',".
							" '$a1','$a','$b','$c','$d','$e','$e1',".
							" '$f','$g','$h','$i','$j',".
							" '$thn_perolehan','$hrg_perolehan',".
							" '$hrg_perolehan','$idp',".
							" '$noreg',".
							" '$uid',now())"; $cek.=" --- Insert BI Baru --- ".$query;	
					$result=mysql_query($query);
					if($result == FALSE) $err='Gagal SQL Insert BI '.mysql_error();					
					$idbi_baru =mysql_insert_id();	
					
					
					$row=mysql_fetch_array(mysql_query("select jml_posting,blm_posting from pengeluaran where id='$idp'"));
					$jml_post = $row['jml_posting']+1;
					$blm_post = $row['blm_posting']-1;
					$aqry = "update pengeluaran  ".
							"set ".
							" jml_posting='$jml_post',". 
							" blm_posting='$blm_post' ".
							"where id='$idp' ";$cek.=" --- Update JJumlah Posting --- ".$aqry;
					$qry=mysql_query($aqry);
					if($qry == FALSE) $err='Gagal SQL Update JJumlah Posting  '.mysql_error();
				}
				
				
				
				if($err==''){
					$fmST_kib=0;
					$idkib="";
				 	switch($f){
						case '01': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_a',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'noreg'=>"'$noreg'",							
									'luas'=>"'".$_REQUEST['fmLUAS_KIB_A']."'",
									'alamat'=>"'".$_REQUEST['fmLETAK_KIB_A']."'",
									'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
									'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
									'alamat_a'=>"'".$a."'",
									'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
									'status_hak'=>"'".$_REQUEST['fmHAKPAKAI_KIB_A']."'",
									'bersertifikat'=>"'".$_REQUEST['bersertifikat']."'",
									'sertifikat_tgl'=>"'".$_REQUEST['fmTGLSERTIFIKAT_KIB_A']."'",
									'sertifikat_no'=>"'".$_REQUEST['fmNOSERTIFIKAT_KIB_A']."'",
									'penggunaan'=>"'".$_REQUEST['fmPENGGUNAAN_KIB_A']."'",
									//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_A']."'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '02': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_b',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'noreg'=>"'$noreg'",								
									'merk'=>"'".$_REQUEST['fmMERK_KIB_B']."'",
									'ukuran'=>"'".$_REQUEST['fmUKURAN_KIB_B']."'",
									'bahan'=>"'".$_REQUEST['fmBAHAN_KIB_B']."'",
									'no_pabrik'=>"'".$_REQUEST['fmPABRIK_KIB_B']."'",
									'no_rangka'=>"'".$_REQUEST['fmRANGKA_KIB_B']."'",
									'no_mesin'=>"'".$_REQUEST['fmMESIN_KIB_B']."'",
									'no_polisi'=>"'".$_REQUEST['fmPOLISI_KIB_B']."'",
									'no_bpkb'=>"'".$_REQUEST['fmBPKB_KIB_B']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_B']."'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '03': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_c',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'noreg'=>"'$noreg'",								
									'kondisi_bangunan'=>"'".$_REQUEST['fmKONDISI_KIB_C']."'",							
									'konstruksi_tingkat'=>"'".$_REQUEST['fmTINGKAT_KIB_C']."'",							
									'konstruksi_beton'=>"'".$_REQUEST['fmBETON_KIB_C']."'",							
									'luas_lantai'=>"'".$_REQUEST['fmLUASLANTAI_KIB_C']."'",						
									'alamat'=>"'".$_REQUEST['fmLETAK_KIB_C']."'",
									'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
									'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
									'alamat_a'=>"'".$a."'",
									'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
									'dokumen_tgl'=>"'".$_REQUEST['fmTGLGUDANG_KIB_C']."'",
									'dokumen_no'=>"'".$_REQUEST['fmNOGUDANG_KIB_C']."'",
									'luas'=>"'".$_REQUEST['fmLUAS_KIB_C']."'",
									'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_C']."'",
									//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_C']."'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '04': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_d',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'noreg'=>"'$noreg'",								
									'konstruksi'=>"'".$_REQUEST['fmKONSTRUKSI_KIB_D']."'",							
									'panjang'=>"'".$_REQUEST['fmPANJANG_KIB_D']."'",							
									'lebar'=>"'".$_REQUEST['fmLEBAR_KIB_D']."'",							
									'luas'=>"'".$_REQUEST['fmLUAS_KIB_D']."'",						
									'alamat'=>"'".$_REQUEST['fmALAMAT_KIB_D']."'",
									'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
									'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
									'alamat_a'=>"'".$a."'",
									'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
									'dokumen_tgl'=>"'".$_REQUEST['fmTGLDOKUMEN_KIB_D']."'",
									'dokumen_no'=>"'".$_REQUEST['fmNODOKUMEN_KIB_D']."'",
									'status_tanah'=>"'".$_REQUEST['fmSTATUSTANAH_KIB_D']."'",
									'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_D']."'",
									//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_D']."'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '05': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_e',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'noreg'=>"'$noreg'",								
									'buku_judul'=>"'".$_REQUEST['fmJUDULBUKU_KIB_E']."'",
									'buku_spesifikasi'=>"'".$_REQUEST['fmSPEKBUKU_KIB_E']."'",
									'seni_asal_daerah'=>"'".$_REQUEST['fmSENIBUDAYA_KIB_E']."'",
									'seni_pencipta'=>"'".$_REQUEST['fmSENIPENCIPTA_KIB_E']."'",
									'seni_bahan'=>"'".$_REQUEST['fmSENIBAHAN_KIB_E']."'",
									'hewan_jenis'=>"'".$_REQUEST['fmJENISHEWAN_KIB_E']."'",
									'hewan_ukuran'=>"'".$_REQUEST['fmUKURANHEWAN_KIB_E']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_E']."'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '06': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_f',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",							
									'bangunan'=>"'".$_REQUEST['fmBANGUNAN_KIB_F']."'",							
									'konstruksi_tingkat'=>"'".$_REQUEST['fmTINGKAT_KIB_F']."'",							
									'konstruksi_beton'=>"'".$_REQUEST['fmBETON_KIB_F']."'",							
									'luas'=>"'".$_REQUEST['fmLUAS_KIB_F']."'",						
									'alamat'=>"'".$_REQUEST['fmLETAK_KIB_F']."'",
									'alamat_kel'=>"'".$_REQUEST['alamat_kel']."'",
									'alamat_kec'=>"'".$_REQUEST['alamat_kec']."'",
									'alamat_a'=>"'".$a."'",
									'alamat_b'=>"'".$_REQUEST['alamat_b']."'",
									'dokumen_tgl'=>"'".$_REQUEST['fmTGLDOKUMEN_KIB_F']."'",
									'dokumen_no'=>"'".$_REQUEST['fmNODOKUMEN_KIB_F']."'",
									'tmt'=>"'".$_REQUEST['fmTGLMULAI_KIB_F']."'",
									'status_tanah'=>"'".$_REQUEST['fmSTATUSTANAH_KIB_F']."'",
									'kode_tanah'=>"'".$_REQUEST['fmNOKODETANAH_KIB_F']."'",
									//'alamat_b'=>"'".$_REQUEST['status_penguasaan']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_F']."'",
									'noreg'=>"'$noreg'",
									'tahun'=>"'$thn_perolehan'"
									
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
						case '07': {
							$get = $this->simpanData(
							$fmST_kib, 'kib_g',
								//array('id'=>"'".$id."'"),
								array('id'=>"'".$idkib."'"),
								array(
									'idbi'=>"'".$idbi_baru."'",
									'a1'=>"'".$a1."'",'a'=>"'".$a."'",'b'=>"'".$b."'",
									'c'=>"'".$c."'",'d'=>"'".$d."'",'e'=>"'".$e."'",'e1'=>"'".$e1."'",
									'f'=>"'".$f."'",'g'=>"'".$g."'",
									'h'=>"'".$h."'",'i'=>"'".$i."'",
									'j'=>"'".$j."'",
									'uraian'=>"'".$_REQUEST['fmURAIAN_KIB_G']."'",							
									'pencipta'=>"'".$_REQUEST['fmPENCIPTA_KIB_G']."'",
									'jenis'=>"'".$_REQUEST['fmJENIS_KIB_G']."'",
									'ket'=>"'".$_REQUEST['fmKET_KIB_G']."'",
									'noreg'=>"'$noreg'",
									'tahun'=>"'$thn_perolehan'"
								)				
							);
							$cek .= $get['cek'];
							$err .= $get['err'];
							break;
						}
					}
				 }
			$jml_posting++;
			}			
			
			$content = array('jml'=>$loop,
							 'jml_post'=>$jml_post,
							 'blm_post'=>$blm_post);		 
			//$content->jml=$loop;
		
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
		
	function simpan2(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 //Inisiasi DATA
	 //==================================
	 $idp=$_REQUEST['idp'];
	 $tgl_buku=$_REQUEST['tgl_buku'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
	 //$tahun = year($);
	
	 //data barang pengeluaran
	 $fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
	 $f = $fmIDBARANG[0];
	 $g = $fmIDBARANG[1];
	 $h = $fmIDBARANG[2];
	 $i = $fmIDBARANG[3];
	 $j = $fmIDBARANG[4];
	 $fmNMBARANG = $_REQUEST['fmNMBARANG'];
	 //Akun
	 $fmIDREKENING = explode('.',$_REQUEST['kode_account']);
	 $ka = $fmIDREKENING[0];
	 $kb = $fmIDREKENING[1];
	 $kc = $fmIDREKENING[2];
	 $kd = $fmIDREKENING[3];
	 $ke = $fmIDREKENING[4];
	 $kf = $fmIDREKENING[5];
	 $nama_account = $_REQUEST['nama_account'];
	 $hrg_perolehan = $_REQUEST['harga_satuan'];
	 $cara_perolehan = $_REQUEST['asal_usul'];	
	 $ba_no = $_REQUEST['ba_no'];	
	 $ba_tgl = $_REQUEST['ba_tgl'];
	 $thn = explode('-',$ba_tgl);
	 $thn_perolehan = $thn[0];
	 $jml_barang = $_REQUEST['jml_barang'];
	 
	
		/*if($tgl_ver=="" && $err=="")$err="Tagal Verifikasi belum diisi!";
	 	if($petugas=="" && $err=="")$err="Petugas belum diisi!";*/
		
		
		switch($f){
			case '01':$tblkib="kib_a";break;
			case '02':$tblkib="kib_b";break;
			case '03':$tblkib="kib_c";break;
			case '04':$tblkib="kib_d";break;
			case '05':$tblkib="kib_e";break;
			case '06':$tblkib="kib_f";break;
			case '07':$tblkib="kib_g";break;
		}
					
			
			
				if($err==''){
				 	switch($f){
						case '01': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" luas='".$_REQUEST['fmLUAS_KIB_A']."',".
									" alamat='".$_REQUEST['fmLETAK_KIB_A']."',".
									" alamat_kel='".$_REQUEST['alamat_kel']."',". 
									" alamat_kec='".$_REQUEST['alamat_kec']."',". 
									" alamat_a='$a',". 
									" alamat_b='".$_REQUEST['alamat_b']."',". 
									" status_hak='".$_REQUEST['fmHAKPAKAI_KIB_A']."',". 
									" bersertifikat='".$_REQUEST['bersertifikat']."',". 
									" sertifikat_tgl='".$_REQUEST['fmTGLSERTIFIKAT_KIB_A']."',". 
									" sertifikat_no='".$_REQUEST['fmNOSERTIFIKAT_KIB_A']."',". 
									" penggunaan='".$_REQUEST['fmPENGGUNAAN_KIB_A']."',". 
									" ket_kib='".$_REQUEST['fmKET_KIB_A']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '02': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" merk='".$_REQUEST['fmMERK_KIB_B']."',".
									" ukuran='".$_REQUEST['fmUKURAN_KIB_B']."',".
									" bahan='".$_REQUEST['fmBAHAN_KIB_B']."',". 
									" no_pabrik='".$_REQUEST['fmPABRIK_KIB_B']."',". 
									" no_rangka='".$_REQUEST['fmRANGKA_KIB_B']."',". 
									" no_mesin='".$_REQUEST['fmMESIN_KIB_B']."',". 
									" no_polisi='".$_REQUEST['fmPOLISI_KIB_B']."',". 
									" no_bpkb='".$_REQUEST['fmBPKB_KIB_B']."',". 
									" ket_kib='".$_REQUEST['fmKET_KIB_B']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '03': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" kondisi_bangunan='".$_REQUEST['fmKONDISI_KIB_C']."',".
									" konstruksi_tingkat='".$_REQUEST['fmTINGKAT_KIB_C']."',".
									" konstruksi_beton='".$_REQUEST['fmBETON_KIB_C']."',". 
									" luas_lantai='".$_REQUEST['fmLUASLANTAI_KIB_C']."',". 
									" alamat='".$_REQUEST['fmLETAK_KIB_C']."',". 
									" alamat_kel='".$_REQUEST['alamat_kel']."',". 
									" alamat_kec='".$_REQUEST['alamat_kec']."',". 
									" alamat_a='$a',". 
									" alamat_b='".$_REQUEST['alamat_b']."',". 
									" dokumen_tgl='".$_REQUEST['fmTGLGUDANG_KIB_C']."',". 
									" dokumen_no='".$_REQUEST['fmNOGUDANG_KIB_C']."',". 
									" luas='".$_REQUEST['fmLUAS_KIB_C']."',". 
									" kode_tanah='".$_REQUEST['fmNOKODETANAH_KIB_C']."',".
									" ket_kib='".$_REQUEST['fmKET_KIB_C']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '04': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" konstruksi='".$_REQUEST['fmKONSTRUKSI_KIB_D']."',".
									" panjang='".$_REQUEST['fmPANJANG_KIB_D']."',".
									" lebar='".$_REQUEST['fmLEBAR_KIB_D']."',". 
									" luas='".$_REQUEST['fmLUAS_KIB_D']."',". 
									" alamat='".$_REQUEST['fmALAMAT_KIB_D']."',". 
									" alamat_kel='".$_REQUEST['alamat_kel']."',". 
									" alamat_kec='".$_REQUEST['alamat_kec']."',". 
									" alamat_a='$a',". 
									" alamat_b='".$_REQUEST['alamat_b']."',". 
									" dokumen_tgl='".$_REQUEST['fmTGLDOKUMEN_KIB_D']."',". 
									" dokumen_no='".$_REQUEST['fmNODOKUMEN_KIB_D']."',". 
									" status_tanah='".$_REQUEST['fmSTATUSTANAH_KIB_D']."',". 
									" kode_tanah='".$_REQUEST['fmNOKODETANAH_KIB_D']."',".
									" ket_kib='".$_REQUEST['fmKET_KIB_D']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '05': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" buku_judul='".$_REQUEST['fmJUDULBUKU_KIB_E']."',".
									" buku_spesifikasi='".$_REQUEST['fmSPEKBUKU_KIB_E']."',".
									" seni_asal_daerah='".$_REQUEST['fmSENIBUDAYA_KIB_E']."',". 
									" seni_pencipta='".$_REQUEST['fmSENIPENCIPTA_KIB_E']."',". 
									" seni_bahan='".$_REQUEST['fmSENIBAHAN_KIB_E']."',". 
									" hewan_jenis='".$_REQUEST['fmJENISHEWAN_KIB_E']."',". 
									" hewan_ukuran='".$_REQUEST['fmUKURANHEWAN_KIB_E']."',". 
									" ket_kib='".$_REQUEST['fmKET_KIB_E']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '06': {
														
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" bangunan='".$_REQUEST['fmBANGUNAN_KIB_F']."',".
									" konstruksi_tingkat='".$_REQUEST['fmTINGKAT_KIB_F']."',".
									" konstruksi_beton='".$_REQUEST['fmBETON_KIB_F']."',". 
									" luas='".$_REQUEST['fmLUAS_KIB_F']."',". 
									" alamat='".$_REQUEST['fmLETAK_KIB_F']."',". 
									" alamat_kel='".$_REQUEST['alamat_kel']."',". 
									" alamat_kec='".$_REQUEST['alamat_kec']."',". 
									" alamat_a='$a',". 
									" alamat_b='".$_REQUEST['alamat_b']."',". 
									" dokumen_tgl='".$_REQUEST['fmTGLDOKUMEN_KIB_F']."',". 
									" dokumen_no='".$_REQUEST['fmNODOKUMEN_KIB_F']."',". 
									" tmt='".$_REQUEST['fmTGLMULAI_KIB_F']."',". 
									" status_tanah='".$_REQUEST['fmSTATUSTANAH_KIB_F']."',".
									" kode_tanah='".$_REQUEST['fmNOKODETANAH_KIB_F']."',".
									" ket_kib='".$_REQUEST['fmKET_KIB_F']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
						case '07': {
							
							$aqry = "update pengeluaran  ".
									"set ".
									" tgl_buku='$tgl_buku',".
									" uraian='".$_REQUEST['fmURAIAN_KIB_G']."',".
									" pencipta='".$_REQUEST['fmPENCIPTA_KIB_G']."',".
									" jenis='".$_REQUEST['fmJENIS_KIB_G']."',". 
									" ket_kib='".$_REQUEST['fmKET_KIB_G']."',". 
									" uid='$uid', tgl_update= now() ".
									"where id='$idp' ";
							
							break;
						}
					}
					
					$cek .= $aqry;
					$qry = mysql_query($aqry);
					if($qry == FALSE) $err='Gagal SQL'.mysql_error();
					
				 }	 
				 
			$content->jml=$loop;
		
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function Hapus(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb'];
	 $idplh = $cbid[0];
	
	 $query = "select * from pemindahtanganan where Id='$idplh'"; $cek .= $query;
	 $ck=mysql_fetch_array(mysql_query($query));
	 if(sudahClosing($ck['tgl_pemindahtanganan'],$ck['c'],$ck['d']))$err="Tanggal Pemindahtanganan Sudah Closing!";

		if($err==''){ 
			$aqry = "DELETE FROM pemindahtanganan WHERE Id='".$idplh."'";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
		}

		return array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function createEntryTgl(	 
	$elName, 
	$Tgl,
	$disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', 
	$fmName = 'adminForm',
	$Mode=0, $withBtnClear = TRUE,
	$tglkosong='0', $param=""
	) 
	{
	    //requirement : javascript TglEntry_cleartgl(), TglEntry_createtgl(), $ref->namabulan
	    global $Ref; //= 'entryTgl';
	    $deftgl = date('Y-m-d'); //'2010-05-05';
	
	    $tgltmp = explode(' ', $Tgl); //explode(' ',$$elName); //hilangkan jam jika ada
	    $stgl = $tgltmp[0];
	    $tgl = explode('-', $stgl);
	    if ($tgl[2] == '00') {
	        $tgl[2] = '';
	    }
	    if ($tgl[1] == '00') {
	        $tgl[1] = '';
	    }
	    if ($tgl[0] == '0000') {
	        $tgl[0] = '';
	    }
	
	
	    $dis = '';
	    if ($disableEntry == '1') {
	        $dis = 'disabled';
	    }
		
		//$Mode = 1;
		switch ($Mode){
			case 1 :{//tahun tanpa combo			
				$entry_thn =
					'<input ' . $dis . ' type="text" 
						
						name="' . $elName . '_thn" id="' . $elName . '_thn" 
						value="' . $tgl[0] . '" size="1" maxlength="4"
						onkeypress="return isNumberKey(event)"
						onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "
					>';
				break;
			}
			default :{ //tahun combo
				if ($tgl[0]==''){
					$thn =(int)date('Y') ;
				}else{
					$thn = $tgl[0];//(int)date('Y') ;
				}
				$thnaw = 1945;
				//$thnak = $thn;
				$thnak = (int)date('Y') ;
				$opsi = "<option value=''>Tahun</option>";
				for ($i=$thnaw; $i<=$thnak; $i++){
					$sel = $i == $tgl[0]? "selected='true'" :'';
					$opsi .= "<option $sel value='$i'>$i</option>";	
				}
				$entry_thn = 
					'<select 					
						id="'. $elName  .'_thn" 
						name="' . $elName . '_thn"'.
						$dis. 
						' onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "
					>'.
						$opsi.
					'</select>';
				break;
			}
			
		}
		
		$ket = $ket == ''? '':
			'<div style="float:left;padding: 0 4 0 0">
				&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>
			</div>';
		$btnclear = $withBtnClear == TRUE ?
			'<div style="float:left;padding: 0 4 0 0">
				<input ' . $dis . '  type="button" value="Clear"
					name="' . $elName . '_btClear" 
					id="' . $elName . '_btClear" 		
					onclick="TglEntry_cleartgl(\'' . $elName . '\')">				
			</div>': '';
	
	    $hsl = '
			<div id="' . $elName . '_content">
			<div  style="float:left;padding: 0 4 0 0">' . 
				$title . 
				/*'<input ' . $dis . ' type="text" name="' . $elName . '_tgl" 
					id="' . $elName . '_tgl" value="' . $tgl[2] . '" size="2" maxlength="2"
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\'' . $elName . '\')">'.*/
				//$tgl[2].
				genCombo_tgl(
					$elName.'_tgl',
					$tgl[2],
					'Tgl', 
					" $dis  style= 'height:20'".'  onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "').
			'</div>		
			<div style="float:left;padding: 0 4 0 0">
				' . cmb2D_v2($elName . '_bln', 
					$tgl[1], 
					$Ref->NamaBulan2, 
					$dis.' style= "height:20" ', 'Pilih Bulan',
	                'onchange="TglEntry_createtgl(\'' . $elName . '\') ; '. $param .' "') . '
			</div>
			<div style="float:left;padding: 0 4 0 0">'.			
				$entry_thn.
			'</div>'.				
			$btnclear.
			$ket.		
			'	<input $dis type="hidden" id=' . $elName . ' name=' . $elName . ' value="' . $Tgl . '" >
				<input type="hidden" id="' . $elName . '_kosong" name="' . $elName . '_kosong" value="'.$tglkosong.'" >
			</div>	';
	    return $hsl;
	}
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
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
				
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formPost':{				
			$fm = $this->setFormPost();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'hapus':{
				$fm = $this->Hapus();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			
		case 'formCari':{				
				$fm = $this->SetFormCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
		case 'pilihcaribi':{				
				$get= $this->SetPilihCariBI();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}			
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'simpan2':{
			$get= $this->simpan2();
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
		
		case 'getdata':{
				$ids = $_REQUEST['cidBI'];//735477
		
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
				$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
								
				//Kode barang
				$kd_barang = str_replace('.','',$kdbrg);
				$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
				
				//Kode Akun
				$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
				$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
				$tmax = mysql_fetch_array(mysql_query($kueri1));
				$kueri="select * from ref_jurnal 
						where thn_akun = '".$tmax['thn_akun']."' 
						and ka='".$br['ka']."' and kb='".$br['kb']."' 
						and kc='".$br['kc']."' and kd='".$br['kd']."'
						and ke='".$br['ke']."' and kf='".$br['kf']."'"; //echo "$kueri";
				$row=mysql_fetch_array(mysql_query($kueri));
				$kdAkun =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
				
				//spesifikasi&alamat
				if($bi['f']=='01'){
					$aqry = "select * from kib_a where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='02'){
					$aqry = "select * from kib_b where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['merk'];
				}
				if($bi['f']=='03'){
					$aqry = "select * from kib_c where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='04'){
					$aqry = "select * from kib_d where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='05'){
					$aqry = "select * from kib_e where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
				}
				if($bi['f']=='06'){
					$aqry = "select * from kib_f where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('".$bi['id']."',now(),'0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				
				$content = array('id_bukuinduk'=>$bi['id'],
								 'idbi_awal'=>$bi['idawal'],
								 'kd_barang'=>$kdbrg,
								 'nm_barang'=>$br['nm_barang'],
								 'kd_akun'=>$kdAkun,
								 'nm_akun'=>$row['nm_account'],
								 'thn_akun'=>$row['thn_akun'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'merk'=>$merk,
								 'kondisi'=>$bi['kondisi'],
								 'staset'=>$bi['staset'],
								 'jml_harga'=>$bi['jml_harga'],
								 'harga_buku'=>$hb);	
		break;
	    }
		
		case 'getbi':{
				$id_bukuinduk = $_REQUEST['id_bukuinduk'];//735477
		
				//cari BI
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='$id_bukuinduk'")) ;

				//spesifikasi&alamat
				if($bi['f']=='01'){
					$aqry = "select * from kib_a where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='02'){
					$aqry = "select * from kib_b where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['merk'];
				}
				if($bi['f']=='03'){
					$aqry = "select * from kib_c where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='04'){
					$aqry = "select * from kib_d where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='05'){
					$aqry = "select * from kib_e where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
				}
				if($bi['f']=='06'){
					$aqry = "select * from kib_f where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('".$bi['id']."',now(),'0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				
				$content = array('idbi_awal'=>$bi['idawal'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'merk'=>$merk,
								 'kondisi'=>$bi['kondisi'],
								 'staset'=>$bi['staset'],
								 'jml_harga'=>$bi['jml_harga'],
								 'harga_buku'=>$hb);	
		break;
	    }
		
	   case 'subtitle':{		
					$content = $this->setTopBar();
					$json=TRUE;
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
		 "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
		 "<script type='text/javascript' src='js/distribusi/distribusi.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/penerimaandetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$dt['tglsk'] = date('Y-m-d');
		$dt['tgl_pemindahtanganan'] = date('Y-m-d');
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
	global $Main;
	global $HTTP_COOKIE_VARS;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		$aqry = "select * from pengeluaran where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$query = "select * from admin where uid='".$uid."'";
		$row = mysql_fetch_array(mysql_query($query));
		
		$dt['tgl_verifikasi'] = date("Y-m-d");
		$dt['petugas'] = $row['nama'];
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormPost(){
		global $Main;
		global $HTTP_COOKIE_VARS;
			$uid = $HTTP_COOKIE_VARS['coID'];
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$cek =$cbid[0];
			$this->form_idplh = $cbid[0];
			$kode = explode(' ',$this->form_idplh);
			$this->form_fmST = 1;
			
			$aqry = "select * from pengeluaran where Id='".$this->form_idplh."'"; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			
			$dt['tgl_buku']=date("Y-m-d");
			$fm = $this->setFormPosting($dt);
			
			return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
		
	function setForm($dt){	
	 global $fmIDBARANG,$fmIDREKENING,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';			
	 $this->form_width = 350;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Verifikasi Data';
	  }else{
		$this->form_caption = 'EDIT';
	  }
	  
	  $arrStatus = array(
					array('1','Ya'),
					array('2','Tidak'),
				);
	 	
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];

		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		  $this->form_fields = array(									 
			
			'tgl_verifikasi' => array(
							'label'=>'Tgl. Verifikasi', 
							'labelWidth'=>70, 
							'value'=> createEntryTgl3($dt['tgl_verifikasi'], 'tgl_verifikasi', false,''), 
							'type'=>''
			),	
			
			'petugas' => array(
							'label'=>'Petugas', 
							'labelWidth'=>70, 
							'value'=>$dt['petugas'], 
							'type'=>'text',
							'param'=>"style='width:230px;'"
			),
			
			
			'status' => array(  
							   'label'=>'Status',
							   'labelWidth'=>70, 
							   'value'=> cmbArray('status',$dt['status'],$arrStatus,'-- Pilih --',''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),	
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='idp' name='idp' value='".$this->form_idplh."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()'  >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal Rencana Pemindahtanganan' >";
							
		$form = $this->genForm();
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormPosting($dt){	
	 global $fmIDBARANG,$fmIDREKENING,$Main;
	 global $HTTP_COOKIE_VARS;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';			
	 $sw=$_REQUEST['sw'];
	 $sh=$_REQUEST['sh'];				
	 $this->form_width = $sw-50;
	 $this->form_height = $sh-100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'POSTING';
	  }else{
		$this->form_caption = 'POSTING';
	  }
	  
	  $arrCaraPerolehan = array(
					array('1','Pembelian'),
					array('2','Hibah'),
					array('3','Lainnya'),
		);
		
		$bb = mysql_fetch_array(mysql_query("select * from penerimaan where Id='".$dt['ref_idterima']."'"));
		$fmIDBARANG = $bb['f'].'.'.$bb['g'].'.'.$bb['h'].'.'.$bb['i'].'.'.$bb['j'];
		$fmNMBARANG = $bb['nm_barang'];
		
		//mapping kd_account
		$brg = mysql_fetch_array(mysql_query("select * from ref_barang where f='".$bb['f']."' and g='".$bb['g']."' and h='".$bb['h']."' and i='".$bb['i']."' and j='".$bb['j']."'"));
		$tmax = mysql_fetch_array(mysql_query("select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '".$HTTP_COOKIE_VARS['coThnAnggaran']."'"));
		$akn=mysql_fetch_array(mysql_query("select * from ref_jurnal 
				where thn_akun = '".$tmax['thn_akun']."' 
				and ka='{$brg[m1]}' and kb='{$brg[m2]}' 
				and kc='{$brg[m3]}' and kd='{$brg[m4]}'
				and ke='{$brg[m5]}' and kf='{$brg[m6]}'"));
		$kode_account = $akn['ka'].'.'.$akn['kb'].'.'.$akn['kc'].'.'.$akn['kd'].'.'.$akn['ke'].'.'.$akn['kf'];
		$nama_account = $akn['nm_account'];
		
		$cc = mysql_fetch_array(mysql_query("select * from penerimaan_ba where id='".$dt['ref_idterimaba']."'"));
		$thn_perolehan = $cc['tahun'];
		$cara_perolehan = $cc['cara_perolehan'];
		$harga=number_format($dt['harga'],2,',','.');
		
		//jml_posting & blm_posting
		$jml_post = $dt['jml_posting'];
		$blm_post = $dt['blm_posting'];
		/*		
		$post = mysql_fetch_array(mysql_query("select count(*) as jml_posting from buku_induk where ref_idpengeluaran='".$this->form_idplh."'"));
		$jml_post = $post['jml_posting'];
		$blm_post = $dt['jml_barang']-$jml_post;
		*/
		
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];
		
		//progress
		$progress = 
			"<div id='progressbox' style='display:none;'>".
			"<div id='progressbck' style='display:block;width:300px;height:4px;background-color:silver; margin: 6 5 0 0;float:left;border-radius: 3px;'>".
			"<div id='progressbar' style='height:2px;margin:1;width:0%;border-radius: 3px;background-color: green;'></div>".
			"</div>".
			"<div id ='progressmsg' name='progressmsg' style='float:left;'></div>".
			"</div>".
			"<input type=hidden id='jmldata' name='jmldata' value='".$dt['jml_barang']."'> ".
			"<input type=hidden id='prog' name='prog' value='0'> ";

		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		  $this->form_fields = array(									 
			
			'tgl_buku' => array('label'=>'Tgl Buku', 
								'labelWidth'=>70, 
								'value'=> createEntryTgl3($dt['tgl_buku'], 'tgl_buku', false,''), 
								'type'=>''
							),
							
			'bidang' => array( 'label'=>'BIDANG', 
								'labelWidth'=>100,
								'value'=>"<input type='text' name='nm_bidang' id='nm_bidang' size='50px' value='$bidang' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'skpd' => array( 'label'=>'SKPD', 
								'value'=>"<input type='text' name='nm_unit' id='nm_unit' size='50px' value='$unit' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),		
							
			'unit' => array( 'label'=>'UNIT', 
								'labelWidth'=>100,
								'value'=>"<input type='text' name='nm_subunit' id='nm_subunit' size='50px' value='$subunit' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'subunit' => array( 'label'=>'SUB UNIT', 
								'value'=>"<input type='text' name='nm_seksi' id='nm_seksi' size='50px' value='$seksi' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),			
			
			'nm_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>70, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$fmIDBARANG' readonly>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$fmNMBARANG."' readonly>"
									 ),
			'kode_account' => array( 
								'label'=>'Kode Akun',
								'labelWidth'=>70, 
								'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
										  <input type='text' name='nama_account' value='$nama_account' size='60px' id='nama_account' readonly>
										  " 
									 ),
			'thn_perolehan' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>70, 
								'value'=>"<input type='text' name='thn_perolehan' id='thn_perolehan' size='4' value='$thn_perolehan' readonly>"
									 ),
									 
			'harga_satuan' => array( 
								'label'=>'Harga Satuan',
								'labelWidth'=>50, 
								'value'=>"<input type='text' name='harga_satuan' id='harga_satuan' size='15' value='".$dt['harga']."' style='text-align:right;' readonly>"
									 ),
			
			'cara_perolehan' => array(  
							   'label'=>'Cara Perolehan',
							   'value'=> cmbArray('cara_perolehan',$cara_perolehan,$arrCaraPerolehan,'-- Pilih --','disabled')."<input type=hidden id='asal_usul' name='asal_usul' value='$cara_perolehan'> ",  
							   'type'=>'' ,
							   'param'=> "",
							 ),	
							 
			'bast' => array(
							'label'=>'', 
							'value'=>'Berita Acara Serah Terima', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'ba_no' => array(
							'label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Nomor', 
							'value'=>$dt['ba_no'], 
							'type'=>'text',
							'param'=>"readonly"
			),
			'ba_tgl' => array(
							'label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Tanggal', 
							'value'=> TglInd($dt['ba_tgl'])."<input type=hidden id='ba_tgl' name='ba_tgl' value='".$dt['ba_tgl']."'> ", 
							'type'=>''
			),	
			
			'jml_barang' => array(
							'label'=>'Jumlah Barang', 
							'labelWidth'=>70, 
							'value'=>"<input type='text' name='jml_barang' id='jml_barang' value='".$dt['jml_barang']."' size=6 readonly>", 
							'type'=>'',
							'param'=>""
							),
			
			'jml_posting' => array(
							'label'=>'Jumlah Posting', 
							'labelWidth'=>70, 
							'value'=>"<input type='text' name='jml_posting' id='jml_posting' value='".$jml_post."' size=6 readonly>
									&nbsp;&nbsp;&nbsp; Belum Posting &nbsp; : &nbsp; 
									<input type='text' name='blm_posting' id='blm_posting' value='".$blm_post."' size=6 readonly>", 
							'type'=>'',
							'param'=>""
							),
			
			'detbrgkib' => array( 
					'label'=>'', 
					'value'=>"<div id=DetailKIB>".$this->getFormEntryKIB($bb['f'],$kib,$progress)."</div>",//Penatausahaan_FormEntry_Kib($dt['f']) ,
				'type'=>'merge' 
			),
			
			/*'progress' => array( 
					'label'=>'', 
					'value'=> $progress, 
					'type'=>'merge' 
			),*/
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='idp' name='idp' value='".$this->form_idplh."'> ".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' id='btposting' value='Posting' onclick ='".$this->Prefix.".Simpan()'  >".
			"<input type='button' id='btbatal' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal Posting' >";
							
		$form = $this->genForm();
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		//$id_penggunaan=$_REQUEST['id_penggunaan'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(	
			/*'skpd' => array( 
				'label'=>'',
				'value'=>
					"<table width=\"200\" class=\"adminform\">	<tr>		
					<td width=\"200\" valign=\"top\">" . 					
						WilSKPD_ajx3($this->Prefix.'CariSkpd') . 
						//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr></table>", 
				'type'=>'merge'
			),	*/		
			'div_detailcaribarang' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribarang' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".PilihBarang()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".CloseCariBarang()' >";
		
		//$form = //$this->genForm();		
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
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox
		   <th class='th01' rowspan='2'>Kode Barang/<br>Kode Akun</th>
		   <th class='th01' rowspan='2'>Nama Barang/<br>Nama Akun</th>
		   <th class='th01' rowspan='2'>Jumlah Barang</th>
		   <th class='th01' rowspan='2'>Harga Satuan</th>
		   <th class='th01' rowspan='2'>Jumlah Harga</th>
		   <th class='th02' colspan='2'>BAST</th>
		   <th class='th01' rowspan='2'>Cara Perolehan</th>
		   <th class='th01' rowspan='2'>Unit Pengguna</th>
		   <th class='th01' rowspan='2'>Verifikasi Data</th>
		   <th class='th01' rowspan='2'>Jumlah Posting</th>
		   <th class='th01' rowspan='2'>Belum Posting</th>
		   <th class='th01' rowspan='2'>Keterangan</th>
	   </tr>
	   <tr>
	       <th class='th01'>Nomor</th>
		   <th class='th01'>Tanggal</th>
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
	
		return 
			"";
	}
	
	function setNavAtas(){
		global $Main;
	
		return
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=penerimaan" title="Daftar Penerimaan Barang Milik Daerah">Penerimaan</a>  |  
				<a style="color:blue;" href="pages.php?Pg=distribusi" title="Daftar Distribusi Barang Milik Daerah">Distribusi</a> '.
				'&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main,$HTTP_COOKIE_VARS;
	 	
	 	//mapping kode&nama barang
		$row = mysql_fetch_array(mysql_query("select * from penerimaan where id='".$isi['ref_idterima']."'"));
		$kd_barang = $row['f'].".".$row['g'].".".$row['h'].".".$row['i'].".".$row['j'];
		$nm_barang = $row['nm_barang'];
		
		//mapping kode&nama akun
		$brg = mysql_fetch_array(mysql_query("select * from ref_barang where f='".$row['f']."' and g='".$row['g']."' and h='".$row['h']."' and i='".$row['i']."' and j='".$row['j']."'"));
		$tmax = mysql_fetch_array(mysql_query("select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '".$HTTP_COOKIE_VARS['coThnAnggaran']."'"));
		$akn=mysql_fetch_array(mysql_query("select * from ref_jurnal 
											where thn_akun = '".$tmax['thn_akun']."' 
											and ka='{$brg[m1]}' and kb='{$brg[m2]}' 
											and kc='{$brg[m3]}' and kd='{$brg[m4]}'
											and ke='{$brg[m5]}' and kf='{$brg[m6]}'"));
		$kode_account = $akn['ka'].'.'.$akn['kb'].'.'.$akn['kc'].'.'.$akn['kd'].'.'.$akn['ke'].'.'.$akn['kf'];
		$nama_account = $akn['nm_account'];
		
		//mapping cara perolehan
		$aa = mysql_fetch_array(mysql_query("select * from penerimaan_ba where id='".$isi['ref_idterimaba']."'"));
		if($aa['cara_perolehan']==1){
			$cara_peroleh = 'Pembelian';
		}elseif($aa['cara_perolehan']==2){
			$cara_peroleh = 'Hibah';
		}elseif($aa['cara_perolehan']==3){
			$cara_peroleh = 'Lainnya';
		}
		
		//unit pengguna
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));
		$seksi = $get['nm_skpd'];
		
		//
		$jml_brg = $isi['jml_barang'];
		$harga=number_format($isi['harga'],2,',','.');
		$jml_harga=number_format($isi['jml_harga'],2,',','.');
		$ba_no = $isi['ba_no'];
		$ba_tgl = TglInd($isi['ba_tgl']);
		$stverifikasi = $isi['status']==1?'Ya' : 'Tidak';
		$jml_post = $isi['jml_posting'];
		$blm_post = $isi['blm_posting'];
		$ket = $isi['ket'];
		
		//jml_posting & blm_posting
		$jml_post = $isi['jml_posting'];
		$blm_post = $isi['blm_posting'];
		/*
		$post = mysql_fetch_array(mysql_query("select count(*) as jml_posting from buku_induk where ref_idpengeluaran='".$isi['id']."'"));
		$jml_post = $post['jml_posting'];
		$blm_post = $jml_brg-$jml_post;
		*/
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) 
		$Koloms[] = array('align=center', $TampilCheckBox);
		$Koloms[] = array('align=left'  , $kd_barang.'/<br>'.$kode_account);			
		$Koloms[] = array('align=left'  , $nm_barang.'/<br>'.$nama_account);
		$Koloms[] = array('align=right' , number_format($jml_brg, 0, ',' ,'.'));
		$Koloms[] = array('align=right' , $harga);
		$Koloms[] = array('align=right' , $jml_harga) ;
		$Koloms[] = array('align=center', $ba_no);
		$Koloms[] = array('align=center', $ba_tgl);
		$Koloms[] = array('align=center', $cara_peroleh);
		$Koloms[] = array('align=left'  , $subunit.'/<br>'.$seksi);
		$Koloms[] = array('align=center', $stverifikasi);
		$Koloms[] = array('align=right', number_format($jml_post, 0, ',' ,'.'));
		$Koloms[] = array('align=right', number_format($blm_post, 0, ',' ,'.'));
		$Koloms[] = array('align=left'  , $ket);
		return $Koloms;
	}
	
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		
		$divHal = "<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
							"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
						"</div>";
		switch($this->daftarMode){						
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr valign=top>
					<td style='width:$this->containWidth;'>".
						"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
						$divHal.
					"</td>".
					"<td>".
						"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >"."</div>".
					$divHal;					
				break;
			}
		}
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmThn1=  $_REQUEST['fmThn1'];
	 $fmThn2=  $_REQUEST['fmThn2'];
	 $fmSemester=  $_REQUEST['fmSemester'];
	 
	  $arrOrder = array(
	  	         array('1','1'),
			     array('2','2'),
					);
	 
	$t=date('Y');
	$thnaw = $t-10;
	$thnak = $t+11;
	$opsi = "<option value=''>--Dari Tahun--</option>";
	$opsi2 = "<option value=''>--Tahun--</option>";
	for ($a=$thnaw;$a<=$thnak;$a++){
		//for ($i=$thnaw; $i<$thnak; $i++){
		$sel = $a == $fmThn1? "selected='true'" :'';
		$sel2 = $a == $fmThn2? "selected='true'" :'';
		$opsi.= "<option value='".$a."' ".$sel.">".$a."</option>";
		$opsi2.= "<option value='".$a."' ".$sel2.">".$a."</option>";
	}
	 	
	$TampilOpt = 
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			 	WilSKPD_ajx3($this->Prefix.'Skpd') .
			"</td>
			</tr></table>".
			
			genFilterBar(
						array(	
							"Tahun : &nbsp;"
							."<select name='fmThn1' id='fmThn1'>".$opsi."</select>".
							"&nbsp; s/d &nbsp;"
							."<select name='fmThn2' id='fmThn2'>".$opsi2."</select>".
							"&nbsp;&nbsp;&nbsp; Semester :&nbsp;"
							.cmbArray('fmSemester',$fmSemester,$arrOrder,'--Semua--','')
						),$this->Prefix.".refreshList(true)",TRUE
					)
					;
			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		global $fmPILCARI;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThn1=  cekPOST('fmThn1');
		$fmThn2=  cekPOST('fmThn2');
		$fmSemester = cekPOST('fmSemester');
		
		$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);
		
		if ($fmThn1 == $fmThn2){
		
			if(!($fmThn1=='')  && !($fmThn2=='')) $arrKondisi[] = " YEAR(tgl_buku) >='$fmThn1' and YEAR(tgl_buku) <='$fmThn2' ";
			switch($fmSemester){			
			case '1': $arrKondisi[] = " tgl_buku>='".$fmThn1."-01-01' and  cast(tgl_buku as DATE)<='".$fmThn2."-06-30' "; break;
			case '2': $arrKondisi[] = " tgl_buku>='".$fmThn1."-07-01' and  cast(tgl_buku as DATE)<='".$fmThn2."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThn1=='') && !($fmThn2=='')) $arrKondisi[] = "YEAR(tgl_buku) >='$fmThn1' and YEAR(tgl_buku) <='$fmThn2' ";
		}
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[]="c,d";
			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//}
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
	
	function windowShow(){	
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		//$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$tahun_anggaran = $_REQUEST['tahun_anggaran'];	
		$tipe='windowshow';	
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		//if($err==''){
			$FormContent = $this->genDaftarInitial($tipe,$fmSKPD,$fmUNIT);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						400,
						'Pilih Rencana Pemanfaatan',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function getFormEntryKIB($f,$dt,$progress){
		global $Main;
			
			$fmKET_KIB_A =$dt['ket'];
			$bersertifikat =$dt['bersertifikat'];
			$status_penguasaan_ =$dt[''];
			$fmLUAS_KIB_A =$dt['luas'];
			$fmLETAK_KIB_A =$dt['alamat'];
			$alamat_kel =$dt['alamat_kel'];
			$alamat_kec =$dt['alamat_kec'];
			$alamat_a =$dt['alamat_a'];
			$alamat_b =$dt['alamat_b'];
			$fmHAKPAKAI_KIB_A =$dt['status_hak'];
			$fmTGLSERTIFIKAT_KIB_A =$dt['sertifikat_tgl'];
			$fmNOSERTIFIKAT_KIB_A =$dt['sertifikat_no'];
			$fmPENGGUNAAN_KIB_A =$dt['penggunaan'];
			
			$fmMERK_KIB_B =$dt['merk'];
			$fmUKURAN_KIB_B =$dt['ukuran'];
			$fmBAHAN_KIB_B =$dt['bahan'];
			$fmPABRIK_KIB_B =$dt['no_pabrik'];
			$fmRANGKA_KIB_B =$dt['no_rangka'];
			$fmMESIN_KIB_B =$dt['no_mesin'];
			$fmPOLISI_KIB_B =$dt['no_polisi'];
			$fmBPKB_KIB_B =$dt['no_bpkb'];
			$fmKET_KIB_B =$dt['ket'];
			
			$fmKONDISI_KIB_C =$dt['kondisi'];
			$fmTINGKAT_KIB_C =$dt['konstruksi_tingkat'];
			$fmBETON_KIB_C =$dt['konstruksi_beton'];
			$fmLUASLANTAI_KIB_C =$dt['luas_lantai'];
			$fmLETAK_KIB_C =$dt['alamat'];
			$fmTGLGUDANG_KIB_C =$dt['dokumen_tgl'];
			$fmNOGUDANG_KIB_C =$dt['dokumen_no'];
			$fmLUAS_KIB_C =$dt['luas'];
			$fmSTATUSTANAH_KIB_C =$dt['status_tanah'];
			$fmNOKODETANAH_KIB_C =$dt['status_tanah'];
			$fmKET_KIB_C =$dt['ket'];
				
			$fmKONSTRUKSI_KIB_D=$dt['konstruksi'];
			$fmPANJANG_KIB_D=$dt['panjang'];
			$fmLEBAR_KIB_D=$dt['lebar'];
			$fmLUAS_KIB_D=$dt['luas'];
			$fmALAMAT_KIB_D=$dt['alamat'];
			$fmTGLDOKUMEN_KIB_D=$dt['dokumen_tgl'];
			$fmNODOKUMEN_KIB_D=$dt['dokumen_no'];
			$fmSTATUSTANAH_KIB_D=$dt['status_tanah'];
			$fmNOKODETANAH_KIB_D=$dt['kode_tanah'];
			$fmKONDISI_KIB_D=$dt['kondisi'];
			$fmKET_KIB_D=$dt['ket'];
		
			$fmJUDULBUKU_KIB_E=$dt['buku_judul'];
			$fmSPEKBUKU_KIB_E=$dt['buku_spesifikasi'];
			$fmSENIBUDAYA_KIB_E=$dt['seni_asal_daerah'];
			$fmSENIPENCIPTA_KIB_E=$dt['seni_pencipta'];
			$fmSENIBAHAN_KIB_E=$dt['seni_bahan'];
			$fmJENISHEWAN_KIB_E=$dt['hewan_jenis'];
			$fmUKURANHEWAN_KIB_E=$dt['hewan_ukuran'];
			$fmKET_KIB_E=$dt['ket'];
					
			$fmBANGUNAN_KIB_F=$dt['bangunan'];
			$fmTINGKAT_KIB_F=$dt['konstruksi_tingkat'];
			$fmBETON_KIB_F=$dt['konstruksi_beton'];
			$fmLUAS_KIB_F=$dt['luas'];
			$fmLETAK_KIB_F=$dt['alamat'];
			$fmTGLDOKUMEN_KIB_F=$dt['dokumen_tgl'];
			$fmNODOKUMEN_KIB_F=$dt['dokumen_no'];
			$fmTGLMULAI_KIB_F=$dt['tmt'];
			$fmSTATUSTANAH_KIB_F=$dt['status_tanah'];
			$fmNOKODETANAH_KIB_F=$dt['kode_tanah'];
			$fmKET_KIB_F=$dt['ket'];
			
			$fmURAIAN_KIB_G=$dt['uraian'];
			$fmPENCIPTA_KIB_G=$dt['pencipta'];
			$fmJENIS_KIB_G=$dt['jenis'];
			$fmKET_KIB_G=$dt['ket'];
		
		
		switch($f){
			case 0:{ //BI
				$hsl="";
			break;}
			case '01':{//KIB A
				$hsl="
				<tr valign=\"top\">   
				<td width='150'>Luas (m<sup>2</sup>)</td>
				<td width='10'>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_A","",$fmLUAS_KIB_A,"")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_A'>$fmLETAK_KIB_A</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','','','valign="top" height="24"')."
				
				
				<tr valign=\"\">   
				<td  colspan=3>Status Tanah :</td>
				</tr>
				<tr valign=\"\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Hak </td>
				<td>:</td>
				<td>".cmb2D('fmHAKPAKAI_KIB_A',$fmHAKPAKAI_KIB_A,$Main->StatusHakPakai,'')."</td>
				</tr>".
				formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;Status Sertifikat ',':', 
					cmb2D_v2('bersertifikat',$bersertifikat,$Main->StatusSertifikat,'','Belum Bersertifikat',"onchange=".$this->Prefix.".sertifikat_onchange()")
					,'','valign="top" height="24"')."
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat  </td><td>:</td>
				<td>".
				createEntryTgl("fmTGLSERTIFIKAT_KIB_A",$fmTGLSERTIFIKAT_KIB_A, $bersertifikat==1?"":"1",
					  'tanggal bulan tahun (mis: 1 Januari 1998)','','adminForm',1
					).//createEntryTgl("fmTGLSERTIFIKAT_KIB_A", $bersertifikat==1?"":"1").
				"</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat  </td><td>:</td><td>".
				txtField('fmNOSERTIFIKAT_KIB_A',$fmNOSERTIFIKAT_KIB_A,'100','20','text', $bersertifikat==1?"":"disabled").
				"</td></tr>
				<tr valign=\"\">   
				<td >Penggunaan</td>
				<td >:</td>
				<td>
				".txtField('fmPENGGUNAAN_KIB_A',$fmPENGGUNAAN_KIB_A,'100','','text',"style='width:346'")."
				</td>				
				</tr>".
				"<tr valign=\"top\">   
				<td >Keterangan</td>
				<td >:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_A'>$fmKET_KIB_A</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";
				break;}		
			case '02':{
				$hsl = "<tr valign=\"top\">   
				<td width='150'>Merk/Type</td>
				<td width='10'>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmMERK_KIB_B'>$fmMERK_KIB_B</textarea>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Ukuran/CC</td>
				<td width=''>:</td>
				<td>
				".txtField('fmUKURAN_KIB_B',$fmUKURAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Bahan</td>
				<td width=''>:</td>
				<td>
				".txtField('fmBAHAN_KIB_B',$fmBAHAN_KIB_B,'100','20','text','')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td  colspan=3>Nomor :</td>
				</tr>
				
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Pabrik </td><td>:</td><td>".txtField('fmPABRIK_KIB_B',$fmPABRIK_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Rangka </td><td>:</td><td>".txtField('fmRANGKA_KIB_B',$fmRANGKA_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Mesin </td><td>:</td><td>".txtField('fmMESIN_KIB_B',$fmMESIN_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Polisi </td><td>:</td><td>".txtField('fmPOLISI_KIB_B',$fmPOLISI_KIB_B,'100','20','text','')."</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;BPKB </td><td>:</td><td>".txtField('fmBPKB_KIB_B',$fmBPKB_KIB_B,'100','20','text','')."</td></tr>
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_B'>$fmKET_KIB_B</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";			
			break;}
			case '03':{//kib c
				$hsl="<tr valign=\"top\">   
				<td width='150'>Kontruksi Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmKONDISI_KIB_C',$fmKONDISI_KIB_C,$Main->Bangunan,'')."</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_C',$fmTINGKAT_KIB_C,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_C',$fmBETON_KIB_C,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Lantai</td>
				<td width=''>:</td>
				<td>
				".txtField('fmLUASLANTAI_KIB_C',$fmLUASLANTAI_KIB_C,'10','10','text','')." &nbsp;M<sup>2</sup>
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_C'>$fmLETAK_KIB_C</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				<tr valign=\"top\">   
				<td colspan=3>Dokumen Gedung :</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td><td>:</td><td>".
					createEntryTgl("fmTGLGUDANG_KIB_C",$fmTGLGUDANG_KIB_C, "").//createEntryTgl("fmTGLGUDANG_KIB_C", "").	//InputKalender("fmTGLGUDANG_KIB_C")
				"</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td><td>:</td><td>
				".txtField('fmNOGUDANG_KIB_C',$fmNOGUDANG_KIB_C,'100','20','text','')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas Total Tanah (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_C","",$fmLUAS_KIB_C,"")."
				
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_C',$fmSTATUSTANAH_KIB_C,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td>Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_C',$fmNOKODETANAH_KIB_C,'100','63','text','')."
				<span style='color: red'> kode_lokasi.kode_barang (mis: 11.10.00.17.01.83.01.01.01.11.01.06.0001)</span>	
				
				</td>
				</tr>".
				/*formEntryBase2('Status Penguasaan',':', 
					cmb2D_v2('status_penguasaan',$status_penguasaan_,$Main->arStatusPenguasaan,'','Pilih','')
					,'','valign="top" height="24"').
				*/
				"<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_C'>$fmKET_KIB_C</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";
				
			break;}
			case '04':{//kib d
				$hsl="<tr valign=\"top\">   
				<td width='150'>Konstruksi</td>
				<td width=''>:</td>
				<td>".txtField('fmKONSTRUKSI_KIB_D',$fmKONSTRUKSI_KIB_D,'50','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >Panjang (Km)</td>
				<td width=''>:</td>
				<td>				
				".inputFormatRibuan("fmPANJANG_KIB_D","",$fmPANJANG_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Lebar (m)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLEBAR_KIB_D","",$fmLEBAR_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_D","",$fmLUAS_KIB_D,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmALAMAT_KIB_D'>$fmALAMAT_KIB_D</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				
				<tr valign=\"top\">   
				<td >Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_D", $fmTGLDOKUMEN_KIB_D, ""). //InputKalender("fmTGLDOKUMEN_KIB_D")
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_D',$fmNODOKUMEN_KIB_D,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td>Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_D',$fmSTATUSTANAH_KIB_D,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>".txtField('fmNOKODETANAH_KIB_D',$fmNOKODETANAH_KIB_D,'100','50','text','')."</td>
				</tr>".
				/*formEntryBase2('Status Penguasaan',':', 
					cmb2D_v2('status_penguasaan',$status_penguasaan_,$Main->arStatusPenguasaan,'','Pilih','')
					,'','valign="top" height="24"').*/
				"<tr valign=\"top\">   
				<td>Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_D'>$fmKET_KIB_D</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";
				
			break;}
			case '05':{//kib e
				$hsl="<tr valign=\"top\" height= '24'>   
				<td  colspan = '3'>Buku Perpustakaan</td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta</td>
				<td width='10'>:</td>
				<td>".txtField('fmJUDULBUKU_KIB_E',$fmJUDULBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi</td>
				<td width=''>:</td>
				<td>".txtField('fmSPEKBUKU_KIB_E',$fmSPEKBUKU_KIB_E,'100','50','text','')."</td>
				</tr>
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3'>Barang bercorak Kesenian/Kebudayaan  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td width='150'>&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBUDAYA_KIB_E',$fmSENIBUDAYA_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Pencipta</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIPENCIPTA_KIB_E',$fmSENIPENCIPTA_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Bahan</td>
				<td width=''>:</td>
				<td>".txtField('fmSENIBAHAN_KIB_E',$fmSENIBAHAN_KIB_E,'100','50','text','')."</td>
				</tr>
				
				
				<tr valign=\"top\" height= '24'>   
				<td colspan = '3' >Hewan Ternak  </td>
				
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Jenis</td>
				<td width=''>:</td>
				<td>".txtField('fmJENISHEWAN_KIB_E',$fmJENISHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td '>&nbsp;&nbsp;&nbsp;&nbsp;Ukuran</td>
				<td width=''>:</td>
				<td>".txtField('fmUKURANHEWAN_KIB_E',$fmUKURANHEWAN_KIB_E,'100','50','text','')."</td>
				</tr>			
				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_E'>$fmKET_KIB_E</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";		
			break;}
			case '06':{//kib f
				$hsl="<tr valign=\"top\">   
				<td width='150'>Bangunan</td>
				<td width='10'>:</td>
				<td>".cmb2D('fmBANGUNAN_KIB_F',$fmBANGUNAN_KIB_F,$Main->Bangunan,'')."</td>
				</tr>
				
				<tr valign=\"top\">   
				<td  colspan=3>Kontruksi Bangunan</td>
				</tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak </td><td>:</td><td>
				".cmb2D('fmTINGKAT_KIB_F',$fmTINGKAT_KIB_F,$Main->Tingkat,'')."
				</td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td>
				".cmb2D('fmBETON_KIB_F',$fmBETON_KIB_F,$Main->Beton,'')."
				</td></tr>
				
				<tr valign=\"top\">   
				<td >Luas (m<sup>2</sup>)</td>
				<td width=''>:</td>
				<td>
				".inputFormatRibuan("fmLUAS_KIB_F","",$fmLUAS_KIB_F,"")."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Letak/Alamat</td>
				<td width=''>:</td>
				<td>
				<textarea cols=60 rows=2 name='fmLETAK_KIB_F'>$fmLETAK_KIB_F</textarea>
				</td>
				</tr>
				
				".formEntryBase2('Kelurahan/Desa',':','<input type="text" name="alamat_kel" value="'.$alamat_kel.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kecamatan',':','<input type="text" name="alamat_kec" value="'.$alamat_kec.'">',
				'','width=""','','valign="top" height="24"')."
				".formEntryBase2('Kota/Kabupaten',':', selKabKota2('alamat_b',$alamat_b,$Main->DEF_PROPINSI),
				'','width=""','','valign="top" height="24"')."
				
				<tr valign=\"top\">   
				<td width='150'>Dokumen :</td>
				</tr>
				
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td >:</td>
				<td>".
					createEntryTgl("fmTGLDOKUMEN_KIB_F", $fmTGLDOKUMEN_KIB_F, ""). //<!--<td>".InputKalender("fmTGLDOKUMEN_KIB_F")."</td>-->
				"</td></tr>
				<tr valign=\"top\">   
				<td >&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width=''>:</td>
				<td>".txtField('fmNODOKUMEN_KIB_F',$fmNODOKUMEN_KIB_F,'100','50','text','')." </td>
				</tr>
				
				<tr valign=\"top\">   
				<td >Tanggal/Bln./Thn. mulai</td>
				<td width=''>:</td>
				<td>".
					createEntryTgl("fmTGLMULAI_KIB_F", $fmTGLMULAI_KIB_F, "").//<!--<td>".InputKalender("fmTGLMULAI_KIB_F")."</td>-->
				"</td>				
				</tr>	
				
				<tr valign=\"top\">   
				<td >Status Tanah</td>
				<td width=''>:</td>
				<td>
				".cmb2D('fmSTATUSTANAH_KIB_F',$fmSTATUSTANAH_KIB_F,$Main->StatusTanah,'')."
				</td>
				</tr>
				<tr valign=\"top\">   
				<td >Nomor Kode Tanah</td>
				<td width=''>:</td>
				<td>
				".txtField('fmNOKODETANAH_KIB_F',$fmNOKODETANAH_KIB_F,'100','50','text','')."
				</td>
				</tr>			<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_F'>$fmKET_KIB_F</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>					";
				
				break;}	
			case '07':{//kib g
				$hsl="
				<tr valign=\"top\">   
				<td width='150'>Uraian</td>
				<td width='10'>:</td>
				<td>".txtField('fmURAIAN_KIB_G',$fmURAIAN_KIB_G,'100','59','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td width='150'>Pencipta</td>
				<td width='10'>:</td>
				<td>".txtField('fmPENCIPTA_KIB_G',$fmPENCIPTA_KIB_G,'100','59','text','')."</td>
				</tr>
				<tr valign=\"top\">   
				<td width='150'>Jenis</td>
				<td width='10'>:</td>
				<td>".txtField('fmJENIS_KIB_G',$fmJENIS_KIB_G,'100','59','text','')."</td>
				</tr>

				<tr valign=\"top\">   
				<td >Keterangan</td>
				<td width=''>:</td>
				<td>
					<table border=0>
					<tr>
						<td>
							<textarea cols=60 rows=2 name='fmKET_KIB_G'>$fmKET_KIB_G</textarea>
						</td>
						<td>
							$progress 
						</td>
					</tr>
					</table>
				</td>
				</tr>";		
			break;}	
		}
		
		return $hsl;
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
			$jmlData = $isi['cnt'];			
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue($value, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}	
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = '';
		//if( sizeof($vSum)==0) $vsum
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}
	
	function genSum_setTampilValue($fieldName, $value){
		switch($fieldName){
			case 'jml_barang': case 'jml_posting':  case 'blm_posting' : return number_format($value, 0, ',' ,'.'); break;
			default : return number_format($value, 2, ',' ,'.'); break;
		}
	}	
	
	function genRowSum_setTampilValue($i, $value){
		switch($i){
			case '0' : case '3' : case '4' : return number_format($value,0, ',', '.'); break;
			default : return number_format($value,2, ',', '.');	break;		
		}		
	}
	
}
$Distribusi = new DistribusiObj();

?>