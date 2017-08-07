<?php

class rkaObj  extends DaftarObj2{	
	var $Prefix = 'rka';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'view_rka'; //bonus
	var $TblName_Hapus = 'tabel_anggaran';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id_anggaran');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'RKA-SKPD';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='rka.xls';
	var $namaModulCetak='RKA';
	var $Cetak_Judul = 'RKA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'rkaForm';
	var $modul = "RKA-SKPD";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	var $currentTahap = "";
	var $namaTahapTerakhir = "";
	var $masaTerakhir = "";
	//buatview
	var $urutTerakhir = "";
	var $urutSebelumnya = "";
	var $jenisFormTerakhir = "";
	
	//buatview
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'RKA-SKPD '.$this->jenisAnggaran.' TAHUN '.$this->tahun;
	}
	
	function setMenuEdit(){
	 	 $arrayResult = VulnWalkerTahap("RKA-SKPD");
		 $jenisForm = $arrayResult['jenisForm'];
		 $nomorUrut = $arrayResult['nomorUrut'];
		 $tahun = $arrayResult['tahun'];
		 $jenisAnggaran = $arrayResult['jenisAnggaran'];
		 $query = $arrayResult['query'];
	 if ($jenisForm == "PENYUSUNAN"){
	 	$listMenu = /*"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru ", 'Baru ')."</td>".*/
					"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>
					<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>"
					/*"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>"*/;	
	 }elseif ($jenisForm == "VALIDASI"){
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Validasi()","validasi-menu.png","Validasi", 'Validasi')."</td>
					<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";	
	 }elseif ($jenisForm == "KOREKSI"){
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";
	 }else{
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";
	 }
	 
		return $listMenu ;
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];

	 foreach ($_REQUEST as $key => $value) { 
		  $$key = $value; 
	 } 
	
	
	
	$user = $_COOKIE['coID'];
	$arrayKodeRekening = explode(".",$kodeRekening);
	$k = $arrayKodeRekening[0];
	$l = $arrayKodeRekening[1];
	$m = $arrayKodeRekening[2];
	$n = $arrayKodeRekening[3];
	$o = $arrayKodeRekening[4];
	
	$getJumlahBarang = mysql_fetch_array(mysql_query("select * from tabel_anggaran where id_anggaran = '$rka_idplh'"));
	$jumlahBarang = $getJumlahBarang['volume_barang'];
	$total = $hargaSatuan * $jumlahBarang;
	
	/* $getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja "));
  	 $idTahapRenja = $getIdTahapRenjaTerakhir['max'];
	$getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and id_tahap = '$idTahapRenja' "));*/
	$getPaguYangTelahTerpakai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai from view_rka where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'  and no_urut = '$this->nomorUrut' and id_anggaran!='$rka_idplh' "));
	$sisaPaguIndikatif = $paguIndikatif - $getPaguYangTelahTerpakai['paguYangTerpakai'];
    
	 if(mysql_num_rows(mysql_query("select * from view_rka where c1='0' and f1 = '0' and k = '$k' and l ='$l' and m='$m' and n='$n' and o='$o'  and id_tahap='$this->idTahap' ")) > 0){
				 	
					}else{
						$arrayRekening = array(
											'c1' => '0',
											'c' => '00',
											'd' => '00',
											'e' => '00',
											'e1' => '000',
											'f1' => '0',
							  				'f2' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
											'k' => $k,
											'l' => $l,
											'm' => $m,
											'n' => $n,
											'o' => $o,
											'tahun' => $this->tahun,
											'jenis_anggaran' => $this->jenisAnggaran,
											'id_tahap' => $this->idTahap,
											'nama_modul' => 'RKA-SKPD'
											);
						$queryRekening = VulnWalkerInsert('tabel_anggaran',$arrayRekening);
						mysql_query($queryRekening);
					}
	 	
 	 if(empty($cmbJenisRKAForm) ){
	   	$err= 'Pilih Jenis RKA ';
	 }elseif(empty($kodeRekening)){
	 	$err = 'Pilih Rekening';
	 }elseif(empty($hargaSatuan) || $hargaSatuan == '0'){
	 	$err = 'Isi Harga Satuan';
	 }elseif($total > $sisaPaguIndikatif){
	 	$err = 'Tidak dapat Melebihi Pagu Indikatif';
	 }else{
	 	$data = array(
						'k' => $k,
						'l' => $l,
						'm' => $m,
						'n' => $n,
						'o' => $o,
						'satuan_rek' => $hargaSatuan,
						'jenis_rka' => $cmbJenisRKAForm,
						'jumlah_harga' => $total
							
					   );
		$query = VulnWalkerUpdate('tabel_anggaran',$data,"id_anggaran = '$rka_idplh'");
		mysql_query($query);
	 }
	 
		
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
			foreach ($_REQUEST as $key => $value) { 
			 	 $$key = $value; 
			}
			
			if(empty($cmbUrusan)){
				$err = "Pilih Urusan";
			}elseif(empty($cmbBidang)){
				$err = "Pilih Bidang";
			}elseif(empty($cmbSKPD)){
				$err = "Pilih SKPD";
			}elseif(empty($cmbUnit)){
				$err = "Pilih Unit";
			}elseif(empty($cmbBidang)){
				$err = "Pilih Bidang";
			}elseif(empty($q)){
				$err = "Pilih Kegiatan";
			}elseif(empty($cmbJenisRKA)){
				$err = "Pilih Jenis RKA";
			}else{
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			}
			
														
		break;
		}
		case 'Info':{
				$fm = $this->Info();				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];										
		break;
		}
		case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $cmbUrusan = $_REQUEST['fmSKPDUrusan'];
			 $cmbBidang = $_REQUEST['fmSKPDBidang'];
			 
			 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";
		
		     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00'and c1 = '$cmbUrusan'  and e1='000'";	
		
		     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$cmbBidang' and c1='$cmbUrusan' and d != '00' and  e = '00' and e1='000' ";
			
			
				$bidang =  cmbQuery('cmbBidangForm', $cmbBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	
				$skpd = cmbQuery('cmbSKPDForm', $cmbSKPD, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --');
				$content = array('bidang' => $bidang, 'skpd' =>$skpd ,'queryGetBidang' => $kondisiBidang);
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
		
		case 'sesuai':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			$queryRows = "select * from tabel_anggaran where id_anggaran = '$idAwal'";
			$getrkanya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getrkanya as $key => $value) { 
				  $$key = $value; 
			} 
			$cmbUrusanForm = $c1;
			$cmbBidangForm = $c;
			
			$cekUrusan =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='00' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap'"));
					if($cekUrusan > 0 ){
						
					}else{
						$data = array(
									    'c1' => $cmbUrusanForm,
										'c' => '00',
										'd' => '00',
										'e' => '00',
										'e1' => '000',
										'bk' => '0',
										'ck' => '0',
										'p' => '0',
										'q' => '0',
										'id_tahap' => $this->idTahap,
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										"nama_modul" => $this->modul
										);
						$query = VulnWalkerInsert("tabel_anggaran", $data);
						$content .= "mampir";
						mysql_query($query)	;				
					}
					
					$cekBidang =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekBidang > 0 ){
						
					}else{
						$data = array(
									    'c1' => $cmbUrusanForm,
										'c'  => $cmbBidangForm,
										'd'  => '00',
										'e'  => '00',
										'e1' => '000',
										'bk' => '0',
										'ck' => '0',
										'p' => '0',
										'q' => '0',
										'id_tahap' => $this->idTahap,
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										"nama_modul" => $this->modul
										
										);
						$query = VulnWalkerInsert("tabel_anggaran", $data);
						$content .= $query;
						mysql_query($query)	;				
					}
			
			
			$dataSesuai = array("tahun" => $tahun,
								"c1" => $c1,
								"c" => $c,
								"d" => $d,
								"e" => $e,
								"e1" => $e1,
								"p" => '0',
								"q" => '0',
								"bk" => '0',
								"ck" => '0',
								'jumlah' => $jumlah,
								'id_tahap' => $this->idTahap,
								'jenis_anggaran' => $this->jenisAnggaran,
								'tahun' => $this->tahun,
								"nama_modul" => $this->modul
										
 								);			
			$cekSKPD =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$d' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekSKPD > 0 ){
						$getID = mysql_fetch_array(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$d' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					    $idnya = $getID['id_anggaran'];
						mysql_query("update tabel_anggaran set jumlah = '$jumlah' where id_anggaran='$idnya'");
					}else{
						mysql_query(VulnWalkerInsert("tabel_anggaran", $dataSesuai));	
						$content .=VulnWalkerInsert("tabel_anggaran", $dataSesuai);	
					}
			
			
			/*$content = array("query" => $query, "sesuai" => number_format($jumlah,2,',','.'), "QUERY ROWS" => $queryData);*/
			 
		break;
	    }
		
		case 'koreksi':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			$queryRows = "select * from tabel_anggaran where id_anggaran = '$idAwal'";
			$getrkanya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getrkanya as $key => $value) { 
				  $$key = $value; 
			} 
			 
			 $hasilKali = $koreksiSatuanHarga * $koreksiVolumebarang ;
			 
			 $getNomorUrutRenja = mysql_fetch_array(mysql_query("select max(no_urut) as nomor_urut from view_renja where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) ;
			 $nomorUrutRenja = $getNomorUrutRenja['nomor_urut'];
			 $getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut ='$nomorUrutRenja'"));
			 $paguIndikatif = $getPaguIndikatif['jumlah'];
			 $content .=  "pagu : ". $paguIndikatif;
			 $content .= "HASIL KALI : ".$hasilKali;
			 
			 $filterPagu = $c1.".".$c.".".$d.".".$e.".".$e1.".".$bk.".".$ck.".".$p.".".$q.".".$f1.".".$f2.".".$f.".".$g.".".$h.".".$i.".".$j.".".$id_jenis_pemeliharaan.".".$k.".".$l.".".$m.".".$n.".".$o;
			 $getSisaPagu = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai  from view_rka where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and p='$p' and q='$q' and id_tahap='$this->idTahap' and concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j,'.',id_jenis_pemeliharaan,'.',k,'.',l,'.',m,'.',n,'.',o) != '$filterPagu' "));
			 $sisaPagu = $paguIndikatif - $getSisaPagu['paguYangTerpakai'];
			 $content .= "Sisa PAGU : ".$sisaPagu;
			 $content .= "getSIsaPAGU nya : "."select sum(jumlah_harga) as paguYangTerpakai  from view_rka where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and p='$p' and q='$q' and id_tahap='$this->idTahap' and concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j,'.',id_jenis_pemeliharaan,'.',k,'.',l,'.',m,'.',n,'.',o) != '$filterPagu' ";
			 if($hasilKali > $sisaPagu){
			 	$err = "Tidak dapat melebihi Pagu Indikatif";
			 }
			 if($err == ""){
				 	if(mysql_num_rows(mysql_query("select * from view_rka where f1='0' and c1='0' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and id_tahap='$this->idTahap'  ")) > 0){
				 	
					 }else{
						$data = array(
										"tahun" => $tahun,
										"c1" => '0',
										"c" => '00',
										"d" => '00',
										"e" => '00',
										"e1" => '000',
										"bk" => '0',
										"ck" => '0',
										"dk" => '0',
										"p" => '0',
										"q" => '0',
										"f1" => '0',
										"f2" => '0',
										"f" => '00',
										"g" => '00',
										"h" => '00',
										"i" => '00',
										"j" => '000',
										"k" => $k,
										"l" => $l,
										"m" => $m,
										"n" => $n,
										"o" => $o,
										"jenis_anggaran" => $this->jenisAnggaran,
										"id_tahap" => $this->idTahap,
										"nama_modul" => $this->modul,
										"user_update" => $_COOKIE['coID'],
										"tanggal_update" => date("Y-m-d")
									
									);
							$query = VulnWalkerInsert('tabel_anggaran',$data);
							$content .= $query;
							mysql_query($query);	 	
					 }
					 
					 
					 $dataSesuai = array("tahun" => $tahun,
										 "c1" => $c1,
										 "c" => $c,
										 "d" => $d,
										 "e" => $e,
										 "e1" => $e1,
										 "bk" => $bk,
										 "ck" => $ck,
										 "dk" => '0',
										 "f1" => $f1,
										 "f2" => $f2,
										 "f" => $f,
										 "g" => $g,
										 "h" => $h,
										 "i" => $i,
										 "j" => $j,
										 "id_jenis_pemeliharaan" => $id_jenis_pemeliharaan,
										 "uraian_pemeliharaan" => $uraian_pemeliharaan,
										 "p" => $p,
									   	 "q" => $q,
										 "k" => $k,
										 "l" => $l,
										 "m" => $m,
										 "n" => $n,
										 "o" => $o,
										 "satuan_rek" => $koreksiSatuanHarga,
										 "volume_barang" => $koreksiVolumebarang,
										 "jumlah_harga" => $hasilKali,
										 "jenis_anggaran" => $this->jenisAnggaran,
										 "id_tahap" => $this->idTahap,
										 "nama_modul" => $this->modul,
										 "user_update" => $_COOKIE['coID'],
										 "tanggal_update" => date("Y-m-d")
		 								);			
					
					$cekRKA =  mysql_num_rows(mysql_query("select * from view_rka where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
							if($cekRKA > 0 ){
								$getID = mysql_fetch_array(mysql_query("select * from view_rka where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
							    $idnya = $getID['id_anggaran'];
								mysql_query("update tabel_anggaran set satuan_rek = '$koreksiSatuanHarga', volume_barang ='$koreksiVolumebarang', jumlah_harga = '$hasilKali' where id_anggaran='$idnya'");
							}else{
								mysql_query(VulnWalkerInsert("tabel_anggaran", $dataSesuai));	
								$content.=VulnWalkerInsert("tabel_anggaran", $dataSesuai);
							}
			 }
			
			 			
		break;
	    }

	    case 'SaveValid':{
	    	foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			 if ($validasi == 'on') {
			 	 $status_validasi = "1";
			 }else{
			 	$status_validasi = "0";
			 }
			 


			 $data = array( "status_validasi" => $status_validasi,
			 				'user_validasi' => $_COOKIE['coID'],
			 				'tanggal_validasi' => date("Y-m-d"),
							'id_tahap' => $this->idTahap
			 				);
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$rka_idplh'");
			 mysql_query($query);

			$content .= $query;
		break;
	    }
		
		 case 'SaveCatatan':{
	    	foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			 
			 $data = array( "catatan" => $catatan
			 				);
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$id'");
			 mysql_query($query);

			$content .= $query;
		break;
	    }

	    case 'Validasi':{
			$dt = array();
			$err='';
			$content='';
			$uid = $HTTP_COOKIE_VARS['coID'];
			
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$idplh = $cbid[0];
			$this->form_idplh = $cbid[0];
			
			$qry = "SELECT * FROM tabel_anggaran WHERE id_anggaran = '$idplh' ";$cek=$qry;
			$aqry = mysql_query($qry);
			$dt = mysql_fetch_array($aqry);
			$username = $_COOKIE['coID'];
			$user_validasi = $dt['user_validasi'];

			if ($username != $user_validasi && $dt['status_validasi'] == '1') {
				$getNamaOrang = mysql_fetch_array(mysql_query("select * from admin where uid = '$user_validasi'"));
				$err = "Data Sudah di Validasi, Perubahan Hanya Bisa Dilakukan oleh ".$getNamaOrang['nama']." !";
			}
			
			
			if($err == ''){
				$fm = $this->Validasi($dt);				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];
			}				
															
		break;
		}
		
		
		 case 'Catatan':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			
			$getData = mysql_fetch_array(mysql_query("SELECT * FROM tabel_anggaran WHERE id_anggaran = '$idAwal'"));
			foreach ($getData as $key => $value) { 
				  $$key = $value; 
			}
			$getMaxID = mysql_fetch_array(mysql_query("select max(id_anggaran) as maxID from tabel_anggaran where tahun = '$tahun'  and c1 ='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and p='$p' and q='$q' and jenis_anggaran = '$jenis_anggaran'  ")); 
			$maxID = $getMaxID['maxID'];
			$aqry = "select * from tabel_anggaran where id_anggaran ='$maxID' ";
			$dt = mysql_fetch_array(mysql_query($aqry));
			if($dt['id_tahap'] != $this->idTahap){
				$err = "Data Belum Di Koreksi ";
			}
			if($err == ''){
				$fm = $this->Catatan($dt);				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];
			}				
															
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
    function setPage_HeaderOther(){
   		
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=ref_tahap_anggaran\" title='PLAFON MURNI'  > JADWAL </a> |
	<A href=\"pages.php?Pg=plafon\" title='PLAFON MURNI'  > PLAFON </a> |
	<A href=\"pages.php?Pg=renja\" title='RENJA MURNI'  > RENJA </a> |
	<A href=\"pages.php?Pg=rkbmdPengadaan\" title='RKBMD PENGADAAN MURNI'  > RKBMD PENGADAAN </a> |
	<A href=\"pages.php?Pg=rkbmdPemeliharaan\" title='RKBMD PEMELIHARAAN MURNI' > RKBMD PEMELIHARAAN </a> |
	<A href=\"pages.php?Pg=rka-skpd\" title='RKA-SKPD MURNI' style='color : blue;' > RKA-SKPD </a> |
	<A href=\"pages.php?Pg=rka-skpkd\" title='RKA-SKPKD MURNI' > RKA-SKPKD </a> |
	&nbsp&nbsp&nbsp	
	</td></tr>
	</table>";
	}
   function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>";
		return 	
			"
			<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/perencanaan/rka/popupBarang.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/rka/popupRekening.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/rka/rka.js' language='JavaScript' ></script> 
			  <link rel='stylesheet' href='datepicker/jquery-ui.css'>
			  <script src='datepicker/jquery-1.12.4.js'></script>
			  <script src='datepicker/jquery-ui.js'></script>
			  
			".
			$scriptload;
	}
	
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d");
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		if($err == ''){
			$aqry = "SELECT * FROM  tabel_anggaran WHERE id_anggaran='".$this->form_idplh."' "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$fm = $this->setForm($dt);
		}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
	function Info(){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	 $this->form_caption = 'INFO RKA';

	 
	 if($this->jenisFormTerakhir == "VALIDASI"){
	 	$getJumlahSKPDYangMengisiPlafon = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirPlafon' and d!='00' and status_validasi = '1' "));
	 }else{
	 	$getJumlahSKPDYangMengisiPlafon = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirPlafon' and d!='00' "));
	 }
	 
	 
	 	
	 //items ----------------------
	  $this->form_fields = array(
			'1' => array( 
						'label'=>'ANGGARAN',
						'labelWidth'=>150, 
						'value'=>$this->jenisAnggaran. " TAHUN  ". $this->tahun,
						 ),
			'2' => array( 
						'label'=>'NAMA TAHAP TERAKHIR',
						'labelWidth'=>150, 
						'value'=>$this->namaTahapTerakhir,
						 ),	
			'3' => array( 
						'label'=>'WAKTU',
						'labelWidth'=>150, 
						'value'=>$this->masaTerakhir,
						 ),		
			'4' => array( 
						'label'=>'TAHAP SEKARANG',
						'labelWidth'=>150, 
						'value'=>$this->currentTahap,
						 )
						 				
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 700;
	 $this->form_height = 400;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		 /*$getNamaUrusan = mysql_fetch_array(mysql_query("select concat(c1,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='00' and d='00' and e='00' and e1 = '000'"));
		 $namaUrusan = $getNamaUrusan['nama'];
		 $urusan = "<input type ='hidden' name='c1' id='c1' value = '$c1' > <input type ='text'  value = '$namaUrusan' style='width:400px;' readonly>";
		 
		 $getNamaBidang = mysql_fetch_array(mysql_query("select concat(c,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='00' and e='00' and e1 = '000'"));
		 $namaBidang = $getNamaBidang['nama'];
		 $bidang = "<input type ='hidden' name='c' id='c' value = '$c' > <input type ='text'  value = '$namaBidang' style='width:400px;' readonly>";
		 
		 $getNamaSKPD = mysql_fetch_array(mysql_query("select concat(d,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$d' and e='00' and e1 = '000'"));
		 $namaSKPD = $getNamaSKPD['nama'];
		 $skpd = "<input type ='hidden' name='d' id='d' value = '$d' > <input type ='text'  value = '$namaSKPD' style='width:400px;' readonly>";
		 
		 $getNamaUnit = mysql_fetch_array(mysql_query("select concat(e,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$e' and e='$e' and e1 = '000'"));
		 $namaUnit = $getNamaUnit['nama'];
		 $unit = "<input type ='hidden' name='e' id='e' value = '$e' > <input type ='text'  value = '$namaUnit' style='width:400px;' readonly>";
		 
		 $getNamaSubUnit = mysql_fetch_array(mysql_query("select concat(e1,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$d' and e='$e' and e1 = '$e1'"));
		 $namaSubUnit = $getNamaSubUnit['nama'];
		 $subunit = "<input type ='hidden' name='e1' id='e1' value = '$e1' > <input type ='text'  value = '$namaSubUnit' style='width:400px;' readonly>";
		 
		 $getProgram = mysql_fetch_array(mysql_query("select concat(p,'. ',nama) as nama from ref_program where bk='$bk' and ck='$ck' and dk = '0' and p = '$p' and q= '0'"));
		 $namaProgram = $getProgram['nama'];
		 $program = "<input type ='hidden' name='bk' id='bk' value = '$bk' > <input type ='hidden' name='ck' id='ck' value = '$ck' > <input type ='hidden' name='p' id='p' value = '$p' > <input type ='text'  value = '$namaProgram' style='width:400px;' readonly>";
	   	 
		 $getKegiatan = mysql_fetch_array(mysql_query("select concat(q,'. ',nama) as nama from ref_program where bk='$bk' and ck='$ck' and dk = '0' and p = '$p' and q= '$q'"));
		 $namaKegiatan = $getKegiatan['nama'];
		 $kegiatan = "<input type ='hidden' name='q' id='q' value = '$q' > <input type ='text'  value = '$namaKegiatan' style='width:400px;' readonly>";
	  	 
		 $kodeRENJA = $c1.".".$c.".".$d.".".$e.".".$e1.".".$bk.".".$ck.".".$p.".".$q;*/
			
	  }else{
		$this->form_caption = 'Edit';			
		foreach ($dt as $key => $value) { 
			 	 $$key = $value; 
		 }
		 $getNamaUrusan = mysql_fetch_array(mysql_query("select concat(c1,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='00' and d='00' and e='00' and e1 = '000'"));
		 $namaUrusan = $getNamaUrusan['nama'];
		 $urusan = "<input type ='hidden' name='c1' id='c1' value = '$c1' > <input type ='text'  value = '$namaUrusan' style='width:400px;' readonly>";
		 
		 $getNamaBidang = mysql_fetch_array(mysql_query("select concat(c,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='00' and e='00' and e1 = '000'"));
		 $namaBidang = $getNamaBidang['nama'];
		 $bidang = "<input type ='hidden' name='c' id='c' value = '$c' > <input type ='text'  value = '$namaBidang' style='width:400px;' readonly>";
		 
		 $getNamaSKPD = mysql_fetch_array(mysql_query("select concat(d,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$d' and e='00' and e1 = '000'"));
		 $namaSKPD = $getNamaSKPD['nama'];
		 $skpd = "<input type ='hidden' name='d' id='d' value = '$d' > <input type ='text'  value = '$namaSKPD' style='width:400px;' readonly>";
		 
		 $getNamaUnit = mysql_fetch_array(mysql_query("select concat(e,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$e' and e='$e' and e1 = '000'"));
		 $namaUnit = $getNamaUnit['nama'];
		 $unit = "<input type ='hidden' name='e' id='e' value = '$e' > <input type ='text'  value = '$namaUnit' style='width:400px;' readonly>";
		 
		 $getNamaSubUnit = mysql_fetch_array(mysql_query("select concat(e1,'. ',nm_skpd) as nama from ref_skpd where c1='$c1'  and c='$c' and d='$d' and e='$e' and e1 = '$e1'"));
		 $namaSubUnit = $getNamaSubUnit['nama'];
		 $subunit = "<input type ='hidden' name='e1' id='e1' value = '$e1' > <input type ='text'  value = '$namaSubUnit' style='width:400px;' readonly>";
		 
		 $getProgram = mysql_fetch_array(mysql_query("select concat(p,'. ',nama) as nama from ref_program where bk='$bk' and ck='$ck' and dk = '0' and p = '$p' and q= '0'"));
		 $namaProgram = $getProgram['nama'];
		 $program = "<input type ='hidden' name='bk' id='bk' value = '$bk' > <input type ='hidden' name='ck' id='ck' value = '$ck' > <input type ='hidden' name='p' id='p' value = '$p' > <input type ='text'  value = '$namaProgram' style='width:400px;' readonly>";
	   	 
		 $getKegiatan = mysql_fetch_array(mysql_query("select concat(q,'. ',nama) as nama from ref_program where bk='$bk' and ck='$ck' and dk = '0' and p = '$p' and q= '$q'"));
		 $namaKegiatan = $getKegiatan['nama'];
		 $kegiatan = "<input type ='hidden' name='q' id='q' value = '$q' > <input type ='text'  value = '$namaKegiatan' style='width:400px;' readonly>";
	  	 
		 $kodeRENJA = $c1.".".$c.".".$d.".".$e.".".$e1.".".$bk.".".$ck.".".$p.".".$q;
		 $hargaSatuan = $satuan_rek;
		 $kodeBarang = $f1.".".$f2.".".$f.".".$g.".".$h.".".$i.".".$j ;
		 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
		 $namaBarang = $getNamaBarang['nm_barang'];	
		 $kodeRekening = $k.".".$l.".".$m.".".$n.".".$o ;
		 $getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
		 $namaRekening = $getNamaRekening['nm_rekening'];
		 $arrayJenisRKA = array(
						array("2.2.1","RKA-SKPD 2.2.1"),
						array("2.1","RKA-SKPD 2.1")
						
						);
		 $jenis_rka = $jenis_rka;
		 $cmbJenisRKA = cmbArray('cmbJenisRKAForm',$jenis_rka,$arrayJenisRKA,'-- JENIS RKA --','onchange=rka.unlockFindRekening();');
	 	 if(empty($jenis_rka)){
		 	$tergantungJenis = "disabled";
		 }
		 
		 $getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' "));
		 $idTahapRenja = $getIdTahapRenjaTerakhir['max'];
		 $getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and id_tahap = '$idTahapRenja' "));
		 $angkaPaguIndikatif = number_format($getPaguIndikatif['jumlah'] ,2,',','.');
		 
		 $formPaguIndikatif  = " <input type='hidden' id='paguIndifkatif' name='paguIndikatif' value='".$getPaguIndikatif['jumlah']."' ><input type='text' value='$angkaPaguIndikatif' readonly >";
	  }

	
	
	 //items ----------------------
	  $this->form_fields = array(
	  	  	'kode0' => array(
	  					'label'=>'URUSAN',
						'labelWidth'=>150, 
						'value'=> $urusan
						 ),
	  		'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=> $bidang
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=> $skpd
						 ),
			'kode3' => array( 
						'label'=>'UNIT',
						'labelWidth'=>150, 
						'value'=> $unit
						 ),
			'kode4' => array( 
						'label'=>'SUB UNIT',
						'labelWidth'=>150, 
						'value'=> $subunit
						 ),
			'kode5' => array( 
						'label'=>'PROGRAM',
						'labelWidth'=>150, 
						'value'=> "<input type='hidden' name = 'bk' id = 'bk' value='$bk'> <input type='hidden' name = 'ck' id = 'ck' value='$ck'>".$program
						 ),
			'kode6' => array( 
						'label'=>'KEGIATAN',
						'labelWidth'=>150, 
						'value'=> $kegiatan
						 ),
			'kode12' => array( 
						'label'=>'PAGU INDIKATIF',
						'labelWidth'=>150, 
						'value'=> $formPaguIndikatif 
						 ),		
			'kode11' => array( 
						'label'=>'JENIS RKA',
						'labelWidth'=>150, 
						'value'=> $cmbJenisRKA,
						 ),	
			'kode9' => array( 
						'label'=>'BARANG',
						'labelWidth'=>150,
						'value'=> "<input type='text' id='kodeBarang' name='kodeBarang' style='width:120px;' readonly value = '$kodeBarang'> &nbsp&nbsp
						 <input type='text' id='namaBarang' name='namaBarang' style='width:300px;' readonly value = '$namaBarang'> "
						 ),				 
			'kode7' => array( 
						'label'=>'REKENING',
						'labelWidth'=>150,
						'value'=> "<input type='text' id='kodeRekening' name='kodeRekening' style='width:120px;' readonly value = '$kodeRekening'> &nbsp&nbsp
						 <input type='text' id='namaRekening' name='namaRekening' style='width:300px;' readonly value = '$namaRekening'>
						 <button type='button' id='findRekening' onclick=rka.findRekening('$jenis_rka'); $tergantungJenis> CARI </button> "
						 ),
			'kode8' => array( 
						'label'=>'HARGA SATUAN',
						'labelWidth'=>150, 
						'value'=> "<input type='text' name='hargaSatuan' id='hargaSatuan' value='$hargaSatuan' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='rka.bantu();' > <span id='bantu' style='color:red;'> </span>"
						 ),	
			
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >  &nbsp  ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	

		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	
		
		 $arrayResult = VulnWalkerTahap($this->modul);
		 $jenisForm = $arrayResult['jenisForm'];
		 $nomorUrut = $arrayResult['nomorUrut'];
		 $tahun = $arrayResult['tahun'];
		 $jenisAnggaran = $arrayResult['jenisAnggaran'];
		 $id_tahap = $arrayResult['id_tahap'];
	 if ($jenisForm == "PENYUSUNAN"){
	 	$tergantungJenisForm = "
		";
		$headerTable =
		  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>
	  	   $Checkbox		
		   <th class='th01' width='100'  rowspan='2' >KODE REKENING</th>
		   <th class='th01' width='900'  rowspan='2' >NAMA REKENING</th>
		   <th class='th02' colspan='3'  rowspan='1' width='600' >RINCIAN</th>
		   <th class='th01' width='400'  rowspan='2' >JUMLAH HARGA</th>
		   <th class='th01' width='400'  rowspan='2' >KETERANGAN</th>
		   $tergantungJenisForm 
		 
		   </tr>
		   <tr>
		   <th class='th01' >VOLUME</th>
		   <th class='th01' >SATUAN</th>
		   <th class='th01' >HARGA SATUAN</th>
		   
		   </tr>
		   </thead>";
	 }elseif ($jenisForm == "VALIDASI"){
	 	$tergantungJenisForm = "
		<th class='th01' rowspan='2' width='100' >VALIDASI</th>
		";
		$headerTable =
		  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>
	  	   $Checkbox		
		   <th class='th01' width='100'  rowspan='2' >KODE REKENING</th>
		   <th class='th01' width='900'  rowspan='2' >NAMA REKENING</th>
		   <th class='th02' colspan='3'  rowspan='1' width='600' >RINCIAN</th>
		   <th class='th01' width='400'  rowspan='2' >JUMLAH HARGA</th>
		   <th class='th01' width='400'  rowspan='2' >KETERANGAN</th>
		   $tergantungJenisForm 
		 
		   </tr>
		   <tr>
		   <th class='th01' >VOLUME</th>
		   <th class='th01' >SATUAN</th>
		   <th class='th01' >HARGA SATUAN</th>
		   
		   </tr>
		   </thead>";
	 }elseif ($jenisForm == "KOREKSI"){
	 	$Checkbox = "";
	 	$tergantungJenisForm = "
		<th class='th02' rowspan='1' colspan='2' width='600'>KOREKSI</th>
		<th class='th02' rowspan='1' colspan='2' width='600'>BERTAMBAH/(BERKURANG)</th>
		<th class='th01' rowspan='2' width='200'>AKSI</th>
		";
		$headerTable =
		  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>	
		   <th class='th01' width='100'  rowspan='2' >KODE REKENING</th>
		   <th class='th01' width='900'  rowspan='2' >NAMA REKENING</th>
		   <th class='th02' colspan='3'  rowspan='1' width='600' >RINCIAN</th>
		   <th class='th01' width='200'  rowspan='2' >JUMLAH HARGA</th>
		   <th class='th01' width='400'  rowspan='2' >KETERANGAN</th>
		   $tergantungJenisForm 
		 
		   </tr>
		   <tr>
		   <th class='th01'  >VOLUME</th>
		   <th class='th01'  >SATUAN</th>
		   <th class='th01' width='200' >HARGA SATUAN</th>
		   <th class='th01' width='200' >VOLUME BARANG</th>
		   <th class='th01' width='200' >SATUAN HARGA</th>
		   <th class='th01' width='200' >VOLUME BARANG</th>
		   <th class='th01' width='200' >SATUAN HARGA</th>
		   </tr>
		   </thead>";
	 }else{
	    $Checkbox = "";
		if($this->jenisFormTerakhir == "PENYUSUNAN"){
			$tergantungJenisForm = "";
			$headerTable =
			  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>
	  	   $Checkbox		
		   <th class='th01' width='100'  rowspan='2' >KODE REKENING</th>
		   <th class='th01' width='900'  rowspan='2' >NAMA REKENING</th>
		   <th class='th02' colspan='3'  rowspan='1' width='600' >RINCIAN</th>
		   <th class='th01' width='400'  rowspan='2' >JUMLAH HARGA</th>
		   <th class='th01' width='400'  rowspan='2' >KETERANGAN</th>
		   $tergantungJenisForm 
		 
		   </tr>
		   <tr>
		   <th class='th01' >VOLUME</th>
		   <th class='th01' >SATUAN</th>
		   <th class='th01' >HARGA SATUAN</th>
		   
		   </tr>
		   </thead>";
		}elseif($this->jenisFormTerakhir == "KOREKSI"){
			$tergantungJenisForm = "
			<th class='th01' width='300' >rka KOREKSI (Rp)</th>
			<th class='th01' width='200'>BERTAMBAH/(BERKURANG)</th>";
		}elseif($this->jenisFormTerakhir == "VALIDASI"){
			$tergantungJenisForm = "
			<th class='th01' rowspan='2' width='100' >VALIDASI</th>
			";
			$headerTable =
			  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>	
		   <th class='th01' width='100'  rowspan='2' >KODE REKENING</th>
		   <th class='th01' width='900'  rowspan='2' >NAMA REKENING</th>
		   <th class='th02' colspan='3'  rowspan='1' width='600' >RINCIAN</th>
		   <th class='th01' width='400'  rowspan='2' >JUMLAH HARGA</th>
		   <th class='th01' width='400'  rowspan='2' >KETERANGAN</th>
		   $tergantungJenisForm 
		 
		   </tr>
		   <tr>
		   <th class='th01' >VOLUME</th>
		   <th class='th01' >SATUAN</th>
		   <th class='th01' >HARGA SATUAN</th>
		   
		   </tr>
		   </thead>";
		}
	 	
	 }
	 $NomorColSpan = $Mode==1? 2: 1;
	 
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  			$$key = $value; 
	 }
	 
	 //TAHAP PENYUSUNAN
	 if($this->jenisForm == 'PENYUSUNAN'){
	 			
			 $getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l ='$l' and m='$m' and n='$n' and o='$o'"));
			 $namaRekening = $getNamaRekening['nm_rekening'];
			 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
			 $namaBarang = $getNamaBarang['nm_barang'];
			 $satuanBarang = $getNamaBarang['satuan'];
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($c1 == '0')$TampilCheckBox="";
		 	 $Koloms[] = array(" align='center'  ", $TampilCheckBox);
			 if($c1 == '0'){
			 	$Koloms[] = array(' align="left"', $k.".".$l.".".$m.".".$n.".".$o );
			 }else{
			 	$Koloms[] = array(' align="left"', '' );
			 }
			 
			 if($c1 == '0'){
				$Koloms[] = array('align="left"',"<b>". $namaRekening ."</b>" );
				$getSumJumlahBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
				$jumlahBarang = $getSumJumlahBarang['total'];
				$Koloms[] = array('align="right"', number_format($jumlahBarang ,0,',','.') );
			 }else{
			 	$Koloms[] = array('align="left"',"<span style='margin-left:5px;'>".$namaBarang."</span>" );
				$Koloms[] = array('align="right"', number_format($volume_barang ,0,',','.') );
			 }
			 
			 $Koloms[] = array('align="left"', $satuanBarang );
			 $getSumSatuanRek = mysql_fetch_array(mysql_query("select sum(satuan_rek) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $sumSatuanRek = $getSumSatuanRek['total'];
			 if($c1 == '0'){
			 	$Koloms[] = array('align="right"', number_format($sumSatuanRek ,2,',','.') );
			 }else{
			 	$Koloms[] = array('align="right"', number_format($satuan_rek ,2,',','.') );
			 }
			 
			 if($c1 == '0'){
			 $getTotalJumalhHarga = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $Koloms[] = array('align="right"', number_format($getTotalJumalhHarga['total'] ,2,',','.') );
			 $Koloms[] = array('align="left"', "" );
			 
			 }else{
			 	if($id_jenis_pemeliharaan == '0'){
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$Koloms[] = array('align="left"', "PENGADAAN" );
				}else{
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$getNamaPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id= '$id_jenis_pemeliharaan'"));
					$namaPemeliharaan = $getNamaPemeliharaan['jenis'];
					$Koloms[] = array('align="left"', "PEMELIHARAAN ". $namaPemeliharaan );
				}
			 }
	 
	 
	 //TAHAP PENYUSUNAN
	 }elseif($this->jenisForm=="VALIDASI"){
	 	     $getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l ='$l' and m='$m' and n='$n' and o='$o'"));
			 $namaRekening = $getNamaRekening['nm_rekening'];
			 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
			 $namaBarang = $getNamaBarang['nm_barang'];
			 $satuanBarang = $getNamaBarang['satuan'];
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($c1 == '0')$TampilCheckBox="";
		 	 $Koloms[] = array(" align='center'  ", $TampilCheckBox);
			 if($c1 == '0'){
			 	$Koloms[] = array(' align="left"', $k.".".$l.".".$m.".".$n.".".$o );
			 }else{
			 	$Koloms[] = array(' align="left"', '' );
			 }
			 
			 if($c1 == '0'){
				$Koloms[] = array('align="left"',"<b>". $namaRekening ."</b>" );
				$getSumJumlahBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
				$jumlahBarang = $getSumJumlahBarang['total'];
				$Koloms[] = array('align="right"', number_format($jumlahBarang ,0,',','.') );
			 }else{
			 	$Koloms[] = array('align="left"',"<span style='margin-left:5px;'>".$namaBarang."</span>" );
				$Koloms[] = array('align="right"', number_format($volume_barang ,0,',','.') );
			 }
			 
			 $Koloms[] = array('align="left"', $satuanBarang );
			 $getSumSatuanRek = mysql_fetch_array(mysql_query("select sum(satuan_rek) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $sumSatuanRek = $getSumSatuanRek['total'];
			 if($c1 == '0'){
			 	$Koloms[] = array('align="right"', number_format($sumSatuanRek ,2,',','.') );
			 }else{
			 	$Koloms[] = array('align="right"', number_format($satuan_rek ,2,',','.') );
			 }
			 
			 if($c1 == '0'){
			 $getTotalJumalhHarga = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $Koloms[] = array('align="right"', number_format($getTotalJumalhHarga['total'] ,2,',','.') );
			 $Koloms[] = array('align="left"', "" );
			 $Koloms[] = array('align="left"', "" );
			 }else{
			 	if($id_jenis_pemeliharaan == '0'){
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$Koloms[] = array('align="left"', "PENGADAAN" );
						if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
						 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'> </img>");
				}else{
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$getNamaPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id= '$id_jenis_pemeliharaan'"));
					$namaPemeliharaan = $getNamaPemeliharaan['jenis'];
					$Koloms[] = array('align="left"', "PEMELIHARAAN ". $namaPemeliharaan );
						if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
						 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'> </img>");
				}
			 }
			 
			 
	 }elseif($this->jenisForm=="KOREKSI"){
	 		 
			 $getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l ='$l' and m='$m' and n='$n' and o='$o'"));
			 $namaRekening = $getNamaRekening['nm_rekening'];
			 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
			 $namaBarang = $getNamaBarang['nm_barang'];
			 $satuanBarang = $getNamaBarang['satuan'];
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($c1 == '0'){
			 	$Koloms[] = array(' align="left"', $k.".".$l.".".$m.".".$n.".".$o );
			 }else{
			 	$Koloms[] = array(' align="left"', '' );
			 }
			 
			 if($c1 == '0'){
				$Koloms[] = array('align="left"',"<b>". $namaRekening ."</b>" );
				$getSumJumlahBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
				$jumlahBarang = $getSumJumlahBarang['total'];
				$Koloms[] = array('align="right"', number_format($jumlahBarang ,0,',','.') );
			 }else{
			 	$Koloms[] = array('align="left"',"<span style='margin-left:5px;'>".$namaBarang."</span>" );
				$Koloms[] = array('align="right"', "<input type='hidden' id='valueVolumeBarang$id_anggaran' value='$volume_barang'>  ".number_format($volume_barang ,0,',','.') );
			 }
			 
			 $Koloms[] = array('align="left"', $satuanBarang );
			 $getSumSatuanRek = mysql_fetch_array(mysql_query("select sum(satuan_rek) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $sumSatuanRek = $getSumSatuanRek['total'];
			 if($c1 == '0'){
			 	$Koloms[] = array('align="right"',  number_format($sumSatuanRek ,2,',','.') );
			 }else{
			 	$Koloms[] = array('align="right"', "<input type='hidden' id='valueSatuanHarga$id_anggaran' value='$satuan_rek'>  ".number_format($satuan_rek ,2,',','.') );
			 }
			 
			 if($c1 == '0'){
			 $getTotalJumalhHarga = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $Koloms[] = array('align="right"', number_format($getTotalJumalhHarga['total'] ,2,',','.') );
			 $Koloms[] = array('align="left"', "" );
			 }else{
			 	if($id_jenis_pemeliharaan == '0'){
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$Koloms[] = array('align="left"', "PENGADAAN" );
						
				}else{
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$getNamaPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id= '$id_jenis_pemeliharaan'"));
					$namaPemeliharaan = $getNamaPemeliharaan['jenis'];
					$Koloms[] = array('align="left"', "PEMELIHARAAN ". $namaPemeliharaan );
						
				}
			 }
			 $getAngkaKoreksi = mysql_fetch_array(mysql_query("select * from view_rka where c1='$c1' and c='$c' and d='$d'  and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and id_jenis_pemeliharaan='$id_jenis_pemeliharaan' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o'  and id_tahap='$this->idTahap'"));
			 $koreksiVolumeBarang = number_format($getAngkaKoreksi['volume_barang'] ,0,',','.');
			 $koreksiSatuanHarga = number_format($getAngkaKoreksi['satuan_rek'] ,2,',','.');
			 if($getAngkaKoreksi['volume_barang'] > $volume_barang ){
			 	$bertambahBerkurangVolumeBarang = number_format($getAngkaKoreksi['volume_barang'] - $volume_barang ,0,',','.'); 
			 }elseif($volume_barang > $getAngkaKoreksi['volume_barang']){
			 	$bertambahBerkurangVolumeBarang = "( ". number_format( $volume_barang - $getAngkaKoreksi['volume_barang'],0,',','.') ." )" ; 
			 }else{
			 	$bertambahBerkurangVolumeBarang = "0";
			 }
			 if(empty($getAngkaKoreksi['c1'])){
				$bertambahBerkurangVolumeBarang = "";			 	
			 }
			 
			 
			 $Koloms[] = array('align="right"',"<span id='spanVolumeBarang".$id_anggaran."'> $koreksiVolumeBarang </span>");
			 $Koloms[] = array('align="right"',"<span id='spanSatuanHarga".$id_anggaran."'> $koreksiSatuanHarga </span>");
		     $Koloms[] = array('align="right" id="alignButton'.$id_anggaran.'" ',"<span id='buttonSubmitKoreksi$id_anggaran' >".$bertambahBerkurangVolumeBarang."</span>");
			 $Koloms[] = array('align="right"',$bertambahBerkurangSatuanHarga);
			 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rka.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rka.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rka.catatan('$id_anggaran');></img> ";
			 if ($f1 == '0') {
			 $aksi = "";
			 }

			 $Koloms[] = array('align="center"',$aksi);
	 }else{
	 	if($this->jenisFormTerakhir == "KOREKSI"){
			if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and no_urut = '$this->urutSebelumnya'"));
					$jumlah = $getJumlah['jumlah'];
					$getKoreksi = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1'   and no_urut = '$this->urutTerakhir'  "));
					$angkaKoreksi = number_format($getKoreksi['jumlah'],2,',','.');
					$bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
					 if($bertambahBerkurang < 0){
					 	$bertambahBerkurang = $jumlah - $getKoreksi['jumlah'];
						$bertambahBerkurang = "( ".number_format($bertambahBerkurang,2,',','.')." )";
					 }elseif($bertambahBerkurang > 0){
					 	$bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
						$bertambahBerkurang = number_format($bertambahBerkurang,2,',','.');
					 }else{
					 	$bertambahBerkurang = "0";
					 }
					 $kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and no_urut = '$this->urutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and  no_urut = '$this->urutTerakhir'  "));
					 $angkaKoreksi = number_format($getKoreksi['jumlah'],2,',','.');
					 $bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
					 if($bertambahBerkurang < 0){
					 	$bertambahBerkurang = $jumlah - $getKoreksi['jumlah'];
						$bertambahBerkurang = "( ".number_format($bertambahBerkurang,2,',','.')." )";
					 }elseif($bertambahBerkurang > 0){
					 	$bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
						$bertambahBerkurang = number_format($bertambahBerkurang,2,',','.');
					 }else{
					 	$bertambahBerkurang = "0";
					 }
					 $kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(rka) as jumlah from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutTerakhir'  "));
					 $angkaKoreksi = number_format($getKoreksi['jumlah'],2,',','.');
					 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 
					 $thisTahap = mysql_num_rows(mysql_query("select * from view_rka where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutTerakhir'  "));
					 if($thisTahap == 1){
					 	$tanda = "";
					 }else{
					 	$tanda = " color : red ;";
					 }
					 $nama_skpd = "<span style='margin-left:10px; $tanda'>". $get['nm_skpd'] ."</span>";
					 $bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
					 if($bertambahBerkurang < 0){
					 	$bertambahBerkurang = $jumlah - $getKoreksi['jumlah'];
						$bertambahBerkurang = "( ".number_format($bertambahBerkurang,2,',','.')." )";
					 }elseif($bertambahBerkurang > 0){
					 	$bertambahBerkurang =  $getKoreksi['jumlah'] - $jumlah ;
						$bertambahBerkurang = number_format($bertambahBerkurang,2,',','.');
					 }else{
					 	$bertambahBerkurang = "0";
					 }
					 $kode = $c1.".".$c.".".$d;
				 }
			 
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 $Koloms[] = array(' align="left"', $kode );
			 $Koloms[] = array('align="left"', $nama_skpd );
			 $Koloms[] = array('align="right"', number_format($jumlah ,2,',','.') );
		     $Koloms[] = array('align="right"',"<span id='span".$id_anggaran."'> $angkaKoreksi </span>");
			 $Koloms[] = array('align="right"',$bertambahBerkurang);
		}elseif($this->jenisFormTerakhir == "VALIDASI"){
				$getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l ='$l' and m='$m' and n='$n' and o='$o'"));
			 $namaRekening = $getNamaRekening['nm_rekening'];
			 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
			 $namaBarang = $getNamaBarang['nm_barang'];
			 $satuanBarang = $getNamaBarang['satuan'];
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($c1 == '0'){
			 	$Koloms[] = array(' align="left"', $k.".".$l.".".$m.".".$n.".".$o );
			 }else{
			 	$Koloms[] = array(' align="left"', '' );
			 }
			 
			 if($c1 == '0'){
				$Koloms[] = array('align="left"',"<b>". $namaRekening ."</b>" );
				$getSumJumlahBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
				$jumlahBarang = $getSumJumlahBarang['total'];
				$Koloms[] = array('align="right"', number_format($jumlahBarang ,0,',','.') );
			 }else{
			 	$Koloms[] = array('align="left"',"<span style='margin-left:5px;'>".$namaBarang."</span>" );
				$Koloms[] = array('align="right"', number_format($volume_barang ,0,',','.') );
			 }
			 
			 $Koloms[] = array('align="left"', $satuanBarang );
			 $getSumSatuanRek = mysql_fetch_array(mysql_query("select sum(satuan_rek) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $sumSatuanRek = $getSumSatuanRek['total'];
			 if($c1 == '0'){
			 	$Koloms[] = array('align="right"', number_format($sumSatuanRek ,2,',','.') );
			 }else{
			 	$Koloms[] = array('align="right"', number_format($satuan_rek ,2,',','.') );
			 }
			 
			 if($c1 == '0'){
			 $getTotalJumalhHarga = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $Koloms[] = array('align="right"', number_format($getTotalJumalhHarga['total'] ,2,',','.') );
			 $Koloms[] = array('align="left"', "" );
			 $Koloms[] = array('align="left"', "" );
			 }else{
			 	if($id_jenis_pemeliharaan == '0'){
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$Koloms[] = array('align="left"', "PENGADAAN" );
						if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
						 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'> </img>");
				}else{
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$getNamaPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id= '$id_jenis_pemeliharaan'"));
					$namaPemeliharaan = $getNamaPemeliharaan['jenis'];
					$Koloms[] = array('align="left"', "PEMELIHARAAN ". $namaPemeliharaan );
						if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
						 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'> </img>");
				}
			 }
		}elseif($this->jenisFormTerakhir == "PENYUSUNAN"){
				 $getNamaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l ='$l' and m='$m' and n='$n' and o='$o'"));
			 $namaRekening = $getNamaRekening['nm_rekening'];
			 $getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang where f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j'"));
			 $namaBarang = $getNamaBarang['nm_barang'];
			 $satuanBarang = $getNamaBarang['satuan'];
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($c1 == '0'){
			 	$Koloms[] = array(' align="left"', $k.".".$l.".".$m.".".$n.".".$o );
			 }else{
			 	$Koloms[] = array(' align="left"', '' );
			 }
			 
			 if($c1 == '0'){
				$Koloms[] = array('align="left"',"<b>". $namaRekening ."</b>" );
				$getSumJumlahBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
				$jumlahBarang = $getSumJumlahBarang['total'];
				$Koloms[] = array('align="right"', number_format($jumlahBarang ,0,',','.') );
			 }else{
			 	$Koloms[] = array('align="left"',"<span style='margin-left:5px;'>".$namaBarang."</span>" );
				$Koloms[] = array('align="right"', number_format($volume_barang ,0,',','.') );
			 }
			 
			 $Koloms[] = array('align="left"', $satuanBarang );
			 $getSumSatuanRek = mysql_fetch_array(mysql_query("select sum(satuan_rek) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $sumSatuanRek = $getSumSatuanRek['total'];
			 if($c1 == '0'){
			 	$Koloms[] = array('align="right"', number_format($sumSatuanRek ,2,',','.') );
			 }else{
			 	$Koloms[] = array('align="right"', number_format($satuan_rek ,2,',','.') );
			 }
			 
			 if($c1 == '0'){
			 $getTotalJumalhHarga = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as total from view_rka where k='$k' and l='$l' and m='$m' and n='$n' and o='$o'"));
			 $Koloms[] = array('align="right"', number_format($getTotalJumalhHarga['total'] ,2,',','.') );
			 $Koloms[] = array('align="left"', "" );
			 
			 }else{
			 	if($id_jenis_pemeliharaan == '0'){
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$Koloms[] = array('align="left"', "PENGADAAN" );
				}else{
					$Koloms[] = array('align="right"', number_format($jumlah_harga ,2,',','.') );
					$getNamaPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id= '$id_jenis_pemeliharaan'"));
					$namaPemeliharaan = $getNamaPemeliharaan['jenis'];
					$Koloms[] = array('align="left"', "PEMELIHARAAN ". $namaPemeliharaan );
				}
			 }
		     
		}
	 }

	 return $Koloms;
	}


	function Validasi($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 120;
	 $this->form_caption = 'VALIDASI rka';
	 $kode = $dt['c1'].".".$dt['c'].".".$dt['d'];
	  if ($dt['status_validasi'] == '1') {
	  	//2017-03-30 17:12:16
		// $tglvalidnya = $dt['tgl_validasi'];
		// $thn1 = substr($tglvalidnya,0,4); 
		// $bln1 = substr($tglvalidnya,5,2); 
		// $tgl1 = substr($tglvalidnya,8,2); 
		// $jam1 = substr($tglvalidnya,11,8);
		$arrayTanggalValidasi = explode("-", $dt['tanggal_validasi']);

		$tglvalid = $arrayTanggalValidasi[2]."-".$arrayTanggalValidasi[1]."-".$arrayTanggalValidasi[0];
		$username = $dt['user_validasi'];
		$checked = "checked='checked'";			
	  }else{			
		$tglvalid = date("d-m-Y");
		$checked = "";	
		$username = $_COOKIE['coID'];
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'kode' => array( 
						'label'=>'KODE rka',
						'labelWidth'=>100, 
						'value'=>$kode, 
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),
			'tgl_validasi' => array( 
						'label'=>'TANGGAL',
						'labelWidth'=>100, 
						'value'=>$tglvalid, 
						'type'=>'text',
						'param'=>"style='width:125px;' readonly"
						 ),

			'username' => array( 
						'label'=>'USERNAME',
						'labelWidth'=>100, 
						'value'=>$username ,
						'type'=>'text',
						'param'=>"style='width:250px;' readonly"
						 ),
			'validasi' => array( 
						'label'=>'VALIDASI DATA',
						'labelWidth'=>100, 
						'value'=>"<input type='checkbox' name='validasi' $checked style='margin-left:-1px;' />",
						 ),					
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SaveValid()' title='Simpan' >   ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function Catatan($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 120;
	 $this->form_caption = 'CATATAN';
	 $catatan = $dt['catatan'];
	 $idnya = $dt['id_anggaran'];
	 	
	 //items ----------------------
	  $this->form_fields = array(
			'catatan' => array( 
						'label'=>'CATATAN',
						'labelWidth'=>100, 
						'value'=>"<textarea id='catatan' name='catatan' style='width:100%; height : 100px;'>".$catatan."</textarea>",
						 ),					
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SaveCatatan($idnya)' title='Simpan' >   ".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 
	 $arrOrder = array(
				     	array('nama_tahap','NAMA TAHAP'),		
						array('waktu_aktif','WAKTU AKTIF'),	
						array('waktu_pasif','WAKTU PASIF'),
						array('modul','MODUL'),
						array('status','STATUS')			
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
$fmORDER1 = $_REQUEST['fmORDER1'];


	$fmDESC1 = cekPOST('fmDESC1');
	$baris = $_REQUEST['baris'];
	if($baris == ''){
		$baris = "25";
	}
	
	$selectedC1 = $_REQUEST['cmbUrusan'];
	$selectedC  = $_REQUEST['cmbBidang'];
	$selectedD = $_REQUEST['cmbSKPD'];
	$selectedE = $_REQUEST['cmbUnit'];
	$selectedE1 = $_REQUEST['cmbSubUnit'];
	
	$arrayProgram = explode(".",$_REQUEST['p']);
	
	$selectedBK = $arrayProgram[0];
	$selectedCK = $arrayProgram[1];
	$selectedP = $arrayProgram[2];
	
	$selectedQ = $_REQUEST['q'];
	
	
	foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
	
	
	$codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) from ref_skpd where c='00' and d='00' and e='00' and e1='000' ";
	$urusan = cmbQuery('cmbUrusan',$selectedC1,$codeAndNameUrusan,'onchange=rka.refreshList(true);','-- URUSAN --');
	
	$codeAndNameBidang = "select c, concat(c, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c !='00' and d='00' and e='00' and e1='000' ";
	$bidang = cmbQuery('cmbBidang',$selectedC,$codeAndNameBidang,'onchange=rka.refreshList(true);','-- BIDANG --');
	
	$codeAndNameSKPD = "select d, concat(d, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d!='00' and e='00' and e1='000' ";
	$skpd= cmbQuery('cmbSKPD',$selectedD,$codeAndNameSKPD,'onchange=rka.refreshList(true);','-- SKPD --');
	
	$codeAndNameUnit = "select e, concat(e, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e!='00' and e1='000' ";
	$unit = cmbQuery('cmbUnit',$selectedE,$codeAndNameUnit,'onchange=rka.refreshList(true);','-- UNIT --');
	
	
	$codeAndNameSubUnit = "select e1, concat(e1, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1!='000' ";
	$subunit = cmbQuery('cmbSubUnit',$selectedE1,$codeAndNameSubUnit,'onchange=rka.refreshList(true);','-- SUB UNIT --');
	
	$nomorUrutSebelumnya = $this->nomorUrut - 1;
	
	
	
	
	
	$codeAndNameProgram = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p, tabel_anggaran.q, ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD' and tabel_anggaran.e = '$selectedE' and tabel_anggaran.e1 = '$selectedE1' and tabel_anggaran.q='0'  ");
	$pSama = "";
	$arrayP = array() ;
	while($rows = mysql_fetch_array($codeAndNameProgram)){
		foreach ($rows as $key => $value) { 
				  $$key = $value; 
		}
		
				$concat = $bk.".".$ck.".".$p ;
			if($concat != ".."){
				if($concat == $pSama){		
				}else{
					array_push($arrayP,
				 	  array($concat,$nama  )
					);
				}
			}
		
		
		
		
		
		
		$pSama = $concat;		
	}
	
	$program = "<input type='hidden' id='bk' name='bk' value='$selectedBK'> <input type='hidden' id='ck' name='ck' value='$selectedCK'> <input type='hidden' id='hiddenP' name='hiddenP' value='$selectedP'>".cmbArray('p',$_REQUEST['p'],$arrayP,'-- PROGRAM --','onchange=rka.refreshList(true);');
	
	$codeAndNameKegiatan = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p, tabel_anggaran.q, ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD' and tabel_anggaran.e = '$selectedE' and tabel_anggaran.e1 = '$selectedE1' and tabel_anggaran.q !='0' and tabel_anggaran.bk='$selectedBK' and tabel_anggaran.ck='$selectedCK' and tabel_anggaran.p='$selectedP'  ");
	$qSama = "";
	$arrayQ = array() ;
	while($rows = mysql_fetch_array($codeAndNameKegiatan)){
		foreach ($rows as $key => $value) { 
				  $$key = $value; 
		}
		if($q != 0) {
			if($q == $qSama){		
			}else{
				array_push($arrayQ,
				   array($q,$nama)
				);
			}
		}
		
		
		
		
		$qSama = $q;		
	}
	
	$kegiatan = cmbArray('q',$_REQUEST['q'],$arrayQ,'-- KEGIATAN --','onchange=rka.refreshList(true);');
	
	
	$getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja "));
	$idTahapRenja = $getIdTahapRenjaTerakhir['max'];
	$getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1='$selectedE1' and bk='$selectedBK' and ck='$selectedCK' and p='$selectedP' and q='$selectedQ' and id_tahap = '$idTahapRenja' "));
	$angkaPaguIndikatif = number_format($getPaguIndikatif['jumlah'] ,2,',','.');
	$paguIndikatif = "<input type='text' value='$angkaPaguIndikatif' readonly>";
	$arrayJenisRKA = array(
						array("2.2.1","RKA-SKPD 2.2.1"),
						array("2.1","RKA-SKPD 2.1"),
						array("2.2","RKA-SKPD 2.2")
						
						);
	$cmbJenisRKA = cmbArray('cmbJenisRKA',$cmbJenisRKA,$arrayJenisRKA,'-- JENIS RKA --','onchange=rka.refreshList(true);');
	
	$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>URUSAN</td>
			<td>:</td>
			<td style='width:86%;'>
			".$urusan."
			</td>
			</tr>
			<tr>
			<td>BIDANG</td>
			<td>:</td>
			<td style='width:86%;'>
			".$bidang."
			</td>
			</tr>
			<tr>
			<td>SKPD</td>
			<td>:</td>
			<td style='width:86%;'>
			".$skpd."
			</td>
			</tr>
			<tr>
			<td>UNIT</td>
			<td>:</td>
			<td style='width:86%;'>
			".$unit."
			</td>
			</tr>
			<tr>
			<td>SUB UNIT</td>
			<td>:</td>
			<td style='width:86%;'>
			".$subunit."
			</td>
			</tr>
			
			
			
			
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>JENIS RKA</td>
			<td>:</td>
			<td style='width:86%;'>
			"."<input type='hidden' name='tahun' id='tahun' value='$this->tahun' style='width:40px;' >".$cmbJenisRKA."
			</td>
			</tr>
			<tr>
			<td>PROGRAM</td>
			<td>:</td>
			<td style='width:86%;'>
			".$program."
			</td>
			</tr>
			<tr>
			<td>KEGIATAN</td>
			<td>:</td>
			<td style='width:86%;'>
			".$kegiatan."
			</td>
			</tr>
			<tr>
			<td>PAGU INDIKATIF</td>
			<td>:</td>
			<td style='width:86%;'>
			".$paguIndikatif."
			</td>
			</tr>"
			
			;
			
		return array('TampilOpt'=>$TampilOpt);
		
		
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$nomorUrutSebelumnya = $this->nomorUrut - 1;		
		$arrKondisi = array();		
		$arrKondisi[] = ' 1 = 1';
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn

		$cmbUrusan = $_REQUEST['cmbUrusan'];
		$cmbBidang = $_REQUEST['cmbBidang'];
		$cmbSKPD = $_REQUEST['cmbSKPD'];
		$cmbUnit = $_REQUEST['cmbUnit'];
		$cmbSubUnit = $_REQUEST['cmbSubUnit'];
		$cmbJenisRKA = $_REQUEST['cmbJenisRKA'];
		$bk = $_REQUEST['bk'];
		$ck= $_REQUEST['ck'];
		$hiddenP = $_REQUEST['hiddenP'];
		$q = $_REQUEST['q'];
			if(!empty($cmbUrusan)){
				$arrKondisi[] = " c1 = '$cmbUrusan'  ";
				if(!empty($cmbBidang)){
				$arrKondisi[] = "c = '$cmbBidang'";
					if(!empty($cmbSKPD)){
					$arrKondisi[] = "d = '$cmbSKPD' ";
						if(!empty($cmbUnit)){
							$arrKondisi[] = "e = '$cmbUnit' ";
							if(!empty($cmbSubUnit)){
							$arrKondisi[] = "e1 = '$cmbSubUnit' ";
								if(!empty($bk)){
								$arrKondisi[] = "bk = '$bk' ";
									if(!empty($ck)){
									$arrKondisi[] = "ck = '$ck' ";
										if(!empty($hiddenP)){
										$arrKondisi[] = "p = '$hiddenP' ";
											if(!empty($q)){
											$arrKondisi[] = "q = '$q' ";
											}
										}
										
									}
									
								}
								
							}
						
							
						}
						
					}
					
				}
				
			}
			
			if($cmbJenisRKA == '2.2'){
				
			}else{
				if(!empty($cmbJenisRKA)){
					$arrKondisi[] = "jenis_rka = '$cmbJenisRKA' ";
			   }
			}
			
			

		
		
		
		if($this->jenisForm == 'PENYUSUNAN'){
			$cekCopy = mysql_num_rows(mysql_query("select * from view_rka where jenis_form_modul ='PENYUSUNAN' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'"));
			if($cekCopy == 0){
				$getAllBarang = mysql_query("select tabel_anggaran.c1, tabel_anggaran.c, tabel_anggaran.d, tabel_anggaran.e, tabel_anggaran.e1, tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p, tabel_anggaran.q,tabel_anggaran.f1, tabel_anggaran.f2, tabel_anggaran.f, tabel_anggaran.g, tabel_anggaran.h, tabel_anggaran.i, tabel_anggaran.j, tabel_anggaran.id_jenis_pemeliharaan, tabel_anggaran.uraian_pemeliharaan, tabel_anggaran.catatan, ref_barang.k11, ref_barang.l11, ref_barang.m11, ref_barang.n11, ref_barang.o11, ref_barang.k12, ref_barang.l12, ref_barang.m12, ref_barang.n12, ref_barang.o12, ref_barang.nm_barang, tabel_anggaran.volume_barang, tabel_anggaran.tahun, tabel_anggaran.jenis_anggaran from tabel_anggaran inner join ref_barang on tabel_anggaran.f1 = ref_barang.f1 and tabel_anggaran.f2 = ref_barang.f2 and tabel_anggaran.f = ref_barang.f and tabel_anggaran.g = ref_barang.g and tabel_anggaran.h = ref_barang.h and tabel_anggaran.i = ref_barang.i and tabel_anggaran.j = ref_barang.j INNER JOIN ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.tahun = '$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' order by tabel_anggaran.id_jenis_pemeliharaan ");
				while($rows = mysql_fetch_array($getAllBarang) ){
					foreach ($rows as $key => $value) { 
				 	 	$$key = $value; 
					}
					if($id_jenis_pemeliharaan == 0){
						if(mysql_num_rows(mysql_query("select * from view_rka where c1='0' and f1 = '0' and k = '$k11' and l ='$l11' and m='$m11' and n='$n11' and o='$o11'  and id_tahap='$this->idTahap' ")) > 0){
					 	
						}else{
							$arrayRekening = array(
												'c1' => '0',
												'c' => '00',
												'd' => '00',
												'e' => '00',
												'e1' => '000',
												'f1' => '0',
								  				'f2' => '0',
								  				'f' => '00',
								 			    'g' => '00',
								  			    'h' => '00',
											    'i' => '00',
											    'j' => '000',
												'k' => $k11,
												'l' => $l11,
												'm' => $m11,
												'n' => $n11,
												'o' => $o11,
												'tahun' => $this->tahun,
												'jenis_anggaran' => $this->jenisAnggaran,
												'id_tahap' => $this->idTahap,
												'nama_modul' => 'RKA-SKPD'
												);
							$queryRekening = VulnWalkerInsert('tabel_anggaran',$arrayRekening);
							mysql_query($queryRekening);
						}
						$data = array( 'tahun' => $this->tahun,
								   'jenis_anggaran' => $this->jenisAnggaran,
								   'id_tahap' => $this->idTahap,
								   'c1' => $c1,
								   'c'	=> $c,
								   'd' => $d,
								   'e' => $e,
								   'e1' => $e1,
								   'bk' => $bk,
								   'ck' => $ck,
								   'dk' => '0',
								   'p' => $p,
								   'q' => $q,
								   'f1' => $f1,
								   'f2' => $f2,
								   'f' => $f,
								   'g' => $g,
								   'h' => $h,
								   'i' => $i,
								   'j' => $j,
								   'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
								   'catatan' => $catatan,
								   'k' => $k11,
								   'l' => $l11,
								   'm' => $m11,
								   'n' => $n11,
								   'o' => $o11,
								   'volume_barang' => $volume_barang,
								   'nama_modul' => 'RKA-SKPD'	
								  );
						$query = VulnWalkerInsert('tabel_anggaran', $data);
						if(mysql_num_rows(mysql_query("select * from view_rka where  c1 = '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1 = '$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and k='$k11' and l='$l11' and m='$m11' and n='$n11' and o ='$o11' and id_tahap='$this->idTahap' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan'")) > 0){
							
						}else{
							mysql_query($query);
						}
					}else{
						if(mysql_num_rows(mysql_query("select * from view_rka where c1='0' and f1 = '0' and k = '$k12' and l ='$l12' and m='$m12' and n='$n12' and o='$o12'  and id_tahap='$this->idTahap' ")) > 0){
					 	
						}else{
							$arrayRekening = array(
												'c1' => '0',
												'c' => '00',
												'd' => '00',
												'e' => '00',
												'e1' => '000',
												'f1' => '0',
								  				'f2' => '0',
								  				'f' => '00',
								 			    'g' => '00',
								  			    'h' => '00',
											    'i' => '00',
											    'j' => '000',
												'k' => $k12,
												'l' => $l12,
												'm' => $m12,
												'n' => $n12,
												'o' => $o12,
												'tahun' => $this->tahun,
												'jenis_anggaran' => $this->jenisAnggaran,
												'id_tahap' => $this->idTahap,
												'nama_modul' => 'RKA-SKPD'
												);
							$queryRekening = VulnWalkerInsert('tabel_anggaran',$arrayRekening);
							mysql_query($queryRekening);
						}
						$data = array( 'tahun' => $this->tahun,
								   'jenis_anggaran' => $this->jenisAnggaran,
								   'id_tahap' => $this->idTahap,
								   'c1' => $c1,
								   'c'	=> $c,
								   'd' => $d,
								   'e' => $e,
								   'e1' => $e1,
								   'bk' => $bk,
								   'ck' => $ck,
								   'dk' => '0',
								   'p' => $p,
								   'q' => $q,
								   'f1' => $f1,
								   'f2' => $f2,
								   'f' => $f,
								   'g' => $g,
								   'h' => $h,
								   'i' => $i,
								   'j' => $j,
								   'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
								   'uraian_pemeliharaan' => $uraian_pemeliharaan,
								   'catatan' => $catatan,
								   'k' => $k12,
								   'l' => $l12,
								   'm' => $m12,
								   'n' => $n12,
								   'o' => $o12,
								   'volume_barang' => $volume_barang,
								   'nama_modul' => 'RKA-SKPD'		
								  );
						$query = VulnWalkerInsert('tabel_anggaran', $data);
						if(mysql_num_rows(mysql_query("select * from view_rka where  c1 = '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1 = '$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and k='$k12' and l='$l12' and m='$m12' and n='$n12' and o ='$o12' and id_tahap='$this->idTahap' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' ")) > 0){
							
						}else{
							mysql_query($query);
						}
					}
				
				}
			}
		
			
			$getAllParent = mysql_query("select * from view_rka where id_tahap='$this->idTahap' and f1 ='0' ");
			while($rows = mysql_fetch_array($getAllParent)){
				foreach ($rows as $key => $value) { 
			 	 $$key = $value; 
				}
				$cekRekening = mysql_num_rows(mysql_query("select * from view_rka where id_tahap = '$this->idTahap' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and f1 != '0'   "));
				if($cekRekening == 0){
					$concat = $k.".".$l.".".$m.".".$n.".".$o;
					$arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) != '$concat'";
				}
				
				
			}
			
			$arrKondisi[] = "id_tahap = '$this->idTahap'";
			
		}elseif($this->jenisForm == 'VALIDASI'){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rka where no_urut = '$nomorUrutSebelumnya'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$jenisTahapSebelumnya = $getJenisTahapSebelumnya['jenis_form_modul'];
			$getAllTahapSebelumnya = mysql_query("select * from view_rka where j !='000' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'  ");
			while($rows = mysql_fetch_array($getAllTahapSebelumnya)){
				if( $jenisTahapSebelumnya == "VALIDASI" && $rows['status_validasi'] != '1' ){
				  }else{
				  		 $cmbUrusanForm =$rows['c1'];
						 $cmbBidangForm = $rows['c'];
						 $cmbSKPDForm = $rows['d'];
						 $cmbUnitForm = $rows['e'];
						 $cmbSubUnitForm = $rows['e1'];
						 $bk= $rows['bk'];
						 $ck =$rows['ck'];
						 $p = $rows['p'];
						 $q = $rows['q'];
						 $k = $rows['k'];
						 $l = $rows['l'];
						 $m = $rows['m'];
						 $n = $rows['n'];
						 $o = $rows['o'];
						 $id_jenis_pemeliharaan = $rows['id_jenis_pemeliharaan'];
						 $tempID = $rows['id_anggaran'];
						 
						 if(mysql_num_rows(mysql_query("select * from view_rka where c1='0' and f1 = '0' and k = '$k' and l ='$l' and m='$m' and n='$n' and o='$o'  and id_tahap='$this->idTahap' ")) > 0){
				 	
					}else{
						$arrayRekening = array(
											'c1' => '0',
											'c' => '00',
											'd' => '00',
											'e' => '00',
											'e1' => '000',
											'f1' => '0',
							  				'f2' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
											'k' => $k,
											'l' => $l,
											'm' => $m,
											'n' => $n,
											'o' => $o,
											'tahun' => $this->tahun,
											'jenis_anggaran' => $this->jenisAnggaran,
											'id_tahap' => $this->idTahap,
											'nama_modul' => 'RKA-SKPD'
											);
						$queryRekening = VulnWalkerInsert('tabel_anggaran',$arrayRekening);
						mysql_query($queryRekening);
					}

			
								$data = array( " status_validasi" => $status_validasi,
								 				'user_validasi' => $_COOKIE['coID'],
								 				'tanggal_validasi' => date("Y-m-d"),
												'id_tahap' => $this->idTahap
								 				);
								 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$tempID'");
								 mysql_query($query);
				 	 }
								 
				
				}
									
				$arrKondisi[] =  "no_urut = '$this->nomorUrut' ";
				
		}elseif($this->jenisForm == 'KOREKSI'){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$beforeThis = mysql_fetch_array(mysql_query("select * from view_rka where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$getAllTahapSebelumnya = mysql_query("select * from view_rka where f1 !='0' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
			$arrayID = array();
			while($rows = mysql_fetch_array($getAllTahapSebelumnya)){
				foreach ($rows as $key => $value) { 
				  $$key = $value; 
				 }
				if($beforeThis['jenis_form_modul'] == 'VALIDASI' ){
					if($rows['status_validasi'] !='1' && $f1 != '0' ){
						$arrKondisi[] = " id_anggaran !='$id_anggaran' ";
						$arrayID[] = " id_anggaran !='$id_anggaran' ";
						array_push($arrayID,$id_anggaran);
						$Condition= join(' and ',$arrayID);		
						$Condition = $Condition =='' ? '':' Where '.$Condition;
						$resultBidang = mysql_num_rows(mysql_query("select * from view_rka $Condition and f1 !='0' and k ='$k' and l = '$l' and m = '$m' and n = '$n' and o = '$o' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
						if($resultBidang  == 0){
						    $concat = $k.'.'.$l.'.'.$m.'.'.$n.'.'.$o;
							$arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) != '$concat' ";	
						}else{
						}
					}
				}
			}
			
			$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya' ";
			

		}else{
			if($this->jenisFormTerakhir == "KOREKSI"){
				$nomorUrutSebelumnya = $this->urutTerakhir - 1;
				$beforeThis = mysql_fetch_array(mysql_query("select * from view_rka where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
				$getAllTahapSebelumnya = mysql_query("select * from view_rka where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
				$arrayID = array();
				while($rows = mysql_fetch_array($getAllTahapSebelumnya)){
					$id_anggaran = $rows['id_anggaran'];
					$c1 = $rows['c1'];
					$c = $rows['c'];
					if($beforeThis['jenis_form_modul'] == 'VALIDASI' ){
						if($rows['status_validasi'] !='1' && $d != '00' ){
							$arrKondisi[] = " id_anggaran !='$id_anggaran' ";
							$arrayID[] = " id_anggaran !='$id_anggaran' ";
							array_push($arrayID,$id_anggaran);
							$Condition= join(' and ',$arrayID);		
							$Condition = $Condition =='' ? '':' Where '.$Condition;
							$resultBidang = mysql_num_rows(mysql_query("select * from view_rka $Condition and d !='00' and c1 ='$c1' and c = '$c' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
							if($resultBidang  == 0){
							    $concat = $c1.'.'.$c;
								$arrKondisi[] = "concat(c1,'.',c) != '$concat' ";	
							}else{
								$resultUrusan = mysql_num_rows(mysql_query("select * from view_rka $Condition and d !='00' and c1 ='$c1'  and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
								if($resultUrusan  == 0){
								 	$concat = $c1;
									$arrKondisi[] = "c1 != '$concat' ";	
								}
							}
						}
					}
				}
				
				$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya' ";
			}elseif($this->jenisFormTerakhir == "VALIDASI"){
				
				$getAllParent = mysql_query("select * from view_rka where no_urut ='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and f1 ='0' ");
				while($rows = mysql_fetch_array($getAllParent)){
					foreach ($rows as $key => $value) { 
				 	 $$key = $value; 
					}
					$cekRekening = mysql_num_rows(mysql_query("select * from view_rka where no_urut ='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and f1 != '0'   "));
					if($cekRekening == 0){
						$concat = $k.".".$l.".".$m.".".$n.".".$o;
						$arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) != '$concat'";
					}
					
					
				}
				$arrKondisi[] =  "no_urut = '$this->urutTerakhir'";
			}elseif($this->jenisFormTerakhir == "PENYUSUNAN"){
				$getAllParent = mysql_query("select * from view_rka where no_urut ='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and f1 ='0' ");
				while($rows = mysql_fetch_array($getAllParent)){
					foreach ($rows as $key => $value) { 
				 	 $$key = $value; 
					}
					$cekRekening = mysql_num_rows(mysql_query("select * from view_rka where no_urut ='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and f1 != '0'   "));
					if($cekRekening == 0){
						$concat = $k.".".$l.".".$m.".".$n.".".$o;
						$arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) != '$concat'";
					}
					
					
				}
				$arrKondisi[] = "no_urut = '$this->urutTerakhir'";
			}
		}
		
  

		
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi. "or (c1='0' and f1 ='0' and k!='' )";
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = "urut, id_anggaran  asc";
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal );
		
	}
	
	function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		
		
			$qy = "DELETE FROM $this->TblName_Hapus WHERE id_anggaran='".$ids[$i]."' ";$cek.=$qy;
			$qry = mysql_query($qy);				
				
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
	function pageShow(){
		global $app, $Main; 
		
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		if($this->jenisForm =="PENYUSUNAN" || $this->jenisForm =="VALIDASI" || $this->jenisFormTerakhir == "PENYUSUNAN" || $this->jenisFormTerakhir == "VALIDASI" ){
			$tergantung = "100";
		}else{
			$tergantung = "100";
		}
		return
				
		"<html>".
			$this->genHTMLHead().
			"<body >".
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0'  height='100%' >".	
				"<tr height='34'><td>".						
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.				
						$this->setPage_Content().
						
					$form2.
					"</div></div>".
				"</td></tr>".
				"<tr><td height='29' >".	
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>".
			"</body>
		</html>
		<style>
			#kerangkaHal {
						width:$tergantung%;
			}
			
		</style>
		"; 
	}		
	
	
}
$rka = new rkaObj();

$arrayResult = VulnWalkerTahap($rka->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$rka->jenisForm = $jenisForm;
$rka->nomorUrut = $nomorUrut;
$rka->tahun = $tahun;
$rka->jenisAnggaran = $jenisAnggaran;
$rka->idTahap = $idTahap;


if(empty($rka->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_rka "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_rka where id_anggaran = '$maxAnggaran'"));
	/*$rka->tahun = "select max(id_anggaran) as max from view_rka where nama_modul = 'rka'";*/
	$rka->tahun  = $get2['tahun'];
	$rka->jenisAnggaran = $get2['jenis_anggaran'];
	$rka->urutTerakhir = $get2['no_urut'];
	$rka->jenisFormTerakhir = $get2['jenis_form_modul'];
	$rka->urutSebelumnya = $rka->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$rka->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$rka->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap();
	$rka->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$rka->idTahap'"));
	$rka->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$rka->idTahap'"));
	$rka->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$rka->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$rka->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}


?>