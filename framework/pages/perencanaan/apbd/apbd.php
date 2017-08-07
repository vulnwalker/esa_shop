<?php

class r_apbdObj  extends DaftarObj2{	
	var $Prefix = 'r_apbd';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'view_r_apbd'; //bonus
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
	var $PageTitle = 'R-APBD';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='r_apbd.xls';
	var $namaModulCetak='r_apbd';
	var $Cetak_Judul = 'r_apbd';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'apbdForm';
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
	var $noUrutTerakhirapbd = "";
	
	//buatview
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'R-APBD '.$this->jenisAnggaran.' TAHUN '. $this->tahun ;
	}
	
	function setMenuEdit(){
	 	 $arrayResult = VulnWalkerTahap("RKA-SKPD");
		 $jenisForm = $arrayResult['jenisForm'];
		 $nomorUrut = $arrayResult['nomorUrut'];
		 $tahun = $arrayResult['tahun'];
		 $jenisAnggaran = $arrayResult['jenisAnggaran'];
		 $query = $arrayResult['query'];
	 	 $listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";

	 
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
	
	
	 if( $err=='' && $jumlah =='' ) $err= 'Jumlah apbd Belum Di Isi !!';
	 
			if($fmST == 0){
				if(empty($cmbUrusanForm))$err ="Pilih Urusan";
				if(empty($cmbBidangForm))$err ="Pilih Bidang";
				if(empty($cmbSKPDForm))$err ="Pilih SKPD";

				if($err==''){
				 
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
						$content .= $query;
						$cek .= "select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='00' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap'";
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

					
					$cekSKPD =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$cmbSKPDForm' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekSKPD > 0 ){
						$err = "apbd SUDAH ADA";
					}else{
						$data = array(
									    'c1' => $cmbUrusanForm,
										'c'  => $cmbBidangForm,
										'd'  => $cmbSKPDForm,
										'e'  => '00',
										'e1' => '000',
										'bk' => '0',
										'ck' => '0',
										'p'  => '0',
										'q'  => '0',
										'user_update' => $_COOKIE['coID'],
										'tgl_update' => date("Y-m-d"),
										'jenis_anggaran' => $this->jenisAnggaran,
										'id_tahap' => $this->idTahap,
										'tahun' => $this->tahun,
										'jumlah' => $jumlah,
										"nama_modul" => $this->modul
										
										);
						$query = VulnWalkerInsert("tabel_anggaran", $data);
						$content .= $query;
						mysql_query($query);					
					}

					
				}
			}else{		
	
				if($err==''){
					$data =           array("jumlah" => $jumlah,
									     'user_update' => $_COOKIE['coID'],
										'tgl_update' => date("Y-m-d"),);
					$query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$idplh'");
					mysql_query($query);
					$content .= $query;
				
				}
			} //end else
					
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
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
			 $selectedBidang = $_REQUEST['fmSKPDBidang'];
			 
			 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";
		
		     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00'and c1 = '$selectedUrusan'  and e1='000'";	
		
		     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1='$selectedUrusan' and d != '00' and  e = '00' and e1='000' ";
			
			
				$bidang =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	
				$skpd = cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --');
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
			$getapbdnya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getapbdnya as $key => $value) { 
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
			$getapbdnya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getapbdnya as $key => $value) { 
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
								"bk" => '0',
								"ck" => '0',
								"p" => '0',
								"q" => '0',
								"jumlah" => $angkaKoreksi,
								"jenis_anggaran" => $this->jenisAnggaran,
								"id_tahap" => $this->idTahap,
								"nama_modul" => $this->modul
 								);			
			
			$cekSKPD =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$d' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekSKPD > 0 ){
						$getID = mysql_fetch_array(mysql_query("select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$d' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					    $idnya = $getID['id_anggaran'];
						mysql_query("update tabel_anggaran set jumlah = '$angkaKoreksi' where id_anggaran='$idnya'");
					}else{
						mysql_query(VulnWalkerInsert("tabel_anggaran", $dataSesuai));	
						$content ="sini";
					}
			
			/*$content = array("query" => $query, "sesuai" => number_format($jumlah,2,',','.'), "QUERY ROWS" => $queryData);*/
			 
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
			 $getSKPD = mysql_fetch_array(mysql_query("select * from tabel_anggaran where id_anggaran = '$r_apbd_idplh'"));
			 $cmbUrusanForm = $getSKPD['c1'];
			 $cmbBidangForm = $getSKPD['c'];
			 
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
										'nama_modul' => $this->modul
										);
						$query = VulnWalkerInsert("tabel_anggaran", $data);
						$content .= $query;
						$cek .= "select * from tabel_anggaran where c1 = '$cmbUrusanForm' and c='00' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap'";
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
										'nama_modul' => $this->modul
										
										);
						$query = VulnWalkerInsert("tabel_anggaran", $data);
						$content .= $query;
						mysql_query($query)	;				
					}
			 
			 
			 
			 


			 $data = array( "status_validasi" => $status_validasi,
			 				'user_validasi' => $_COOKIE['coID'],
			 				'tanggal_validasi' => date("Y-m-d"),
							'id_tahap' => $this->idTahap
			 				);
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$r_apbd_idplh'");
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
		
		case 'Info':{
				$fm = $this->Info();				
				$cek .= $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];										
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
			"";
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
			<script type='text/javascript' src='js/perencanaan/r-apbd/r-apbd.js' language='JavaScript' ></script> 
			<script type='text/javascript' src='js/master/apbd/popupModul.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/apbd/popupHistori.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/refstandarharga/refbarang.js' language='JavaScript' ></script>
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
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 600;
	 $this->form_height = 250;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
		 $selectedBidang = $_REQUEST['fmSKPDBidang'];
	     $selectedskpd = $_REQUEST['fmSKPDskpd'];
		 $tahun = $_REQUEST['tahun'];
		 $idTahap = $_REQUEST['idTahap'];
		 $anggaran = $_REQUEST['anggaran'];
	  }else{
		$this->form_caption = 'Edit';			
		$selectedUrusan = $dt['c1'];
		$selectedBidang = $dt['c'];
     	$selectedskpd =  $dt['d'];
		$tahun = $dt['tahun'];
		$idTahap = $dt['idTahap'];
		$anggaran = $dt['jenis_anggaran'];
		$jumlah = $dt['jumlah'];
		$cmbRo = "disabled";
		
	  }
	     	 $kondisiBidang = "";
	 $codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) as vnama from ref_skpd where d='00' and c ='00' order by c1";


     $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and c!='00' and c1 = '$selectedUrusan'  and e1='000'";	

     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d != '00' and  e = '00' and e1='000' ";
     $cek .= $codeAndNameskpd;

	  	$query = "select * from ref_skpd " ;$cek .=$query;
	  	$res = mysql_query($query);

$comboBoxUrusanForm = cmbQuery('cmbUrusanForm', $selectedUrusan, $codeAndNameUrusan,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
	
if($_COOKIE['cofmSKPD']!='00'){

	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
}else{
	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,' '.$cmbRo.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');
}	

	
	
	 //items ----------------------
	  $this->form_fields = array(
	  	  	'kode0' => array(
	  					'label'=>'URUSAN',
						'labelWidth'=>150, 
						'value'=> $comboBoxUrusanForm
						 ),
	  		'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=> $comboBoxBidangForm
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=> cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --')
						 ),
			'jarak' => array( 
						'value'=> "<div style='margin-top: 20px;'></div>"
						 ),
			'tahun' => array( 
						'label'=>'TAHUN ANGGARAN',
						'labelWidth'=>150, 
						'value'=>$this->tahun, 
						'type'=>'text',
						'param'=>"style='width:50px; text-align:center;' readonly"
						 ),	
						 
			'anggaran' => array( 
						'label'=>'ANGGARAN',
						'labelWidth'=>150, 
						'value'=>$this->jenisAnggaran, 
						'type'=>'text',
						'param'=>"style='width:100px;' readonly"
						 ),	
			' ' => array( 
						'label'=>'apbd ANGGARAN (Rp)',
						'labelWidth'=>150, 
						'value'=> "<input type='hidden' name='idTahap' id='idTahap' value='$idTahap'><input type='text' name='jumlah' id='jumlah' value='$jumlah' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='document.getElementById(`bantu`).innerHTML = popupBarang.formatCurrency(this.value);' style='text-align:right' placeholder='Rp.'> <span>Rp. </span><font color='red' id='bantu' name='bantu'></font>"  
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
	
		
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>		
	   <th class='th01' width='100' rowspan='2'>KODE</th>
	   <th class='th01' width='600' rowspan='2'>NAMA URUSAN PEMERINTAHAN</th>
	   <th class='th01' width='300' rowspan='2'>PENDAPATAN</th>
	   <th class='th02' width='300' rowspan='1' colspan='3'>BELANJA</th>
	 
	   </tr>
	   <tr> 
	   <th class='th01' width='300' >TIDAK LANGSUNG</th>
	   <th class='th01' width='300' >LANGSUNG</th>
	   <th class='th01' width='300' >JUMLAH BELANJA</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  			$$key = $value; 
	 }
	 
	if(!empty($this->idTahap)){
		$kondisiFilter = " and id_tahap = '$this->idTahap' ";
		if($this->jenisForm == "VALIDASI"){
			$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
		}
	}else{
		$getIdTahapTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) from tabel_anggaran where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and nama_modul = 'RKA-SKPD'"));
		$idTahapTerakhir = $getIdTahapTerakhir['max(id_tahap)'];
		$kondisiFilter = " and id_tahap = '$idTahapTerakhir' ";
		if($this->jenisFormTerakhir == "VALIDASI"){
			$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
		}
	}
			 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 $getNamaSkpd = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$c1' and c='$c' and d='$d' "));
	 
	 if($d !='00'){
	 	$Koloms[] = array(" align='left'  ",$c1.".".$c.".".$d ); 
		$namaSKPD = "<span style='margin-left:10px;'>".$getNamaSkpd['nm_skpd'];
		$getPendapatan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c' and d='$d' and k='4' $kondisiFilter  "));
	 	$getTidakLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c' and d='$d' and k='5' and l='1' $kondisiFilter  "));
	 	$getLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c' and d='$d' and k='5' and l='2' $kondisiFilter  "));
	 	$pendapatan = number_format($getPendapatan['sum(jumlah_harga)'],2,',','.');
		$tidakLangsung = number_format($getTidakLangsung['sum(jumlah_harga)'],2,',','.');
		$langsung = number_format($getLangsung['sum(jumlah_harga)'],2,',','.');
		$jumlahBelanja = $getTidakLangsung['sum(jumlah_harga)'] + $getLangsung['sum(jumlah_harga)'];
		$jumlahBelanja = number_format($jumlahBelanja,2,',','.');
	 }elseif($c !='00'){
	 	$Koloms[] = array(" align='left'  ",$c1.".".$c ); 
		$namaSKPD = "<span style='font-weight:bold;margin-left:5px;'>". $getNamaSkpd['nm_skpd'];
		$getPendapatan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c'  and k='4' $kondisiFilter  "));
	 	$getTidakLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c'  and k='5' and l='1' $kondisiFilter  "));
	 	$getLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and c='$c'  and k='5' and l='2' $kondisiFilter  "));
	 	$pendapatan = "<b>".number_format($getPendapatan['sum(jumlah_harga)'],2,',','.');
		$tidakLangsung = "<b>".number_format($getTidakLangsung['sum(jumlah_harga)'],2,',','.');
		$langsung = "<b>".number_format($getLangsung['sum(jumlah_harga)'],2,',','.');
		$jumlahBelanja = $getTidakLangsung['sum(jumlah_harga)'] + $getLangsung['sum(jumlah_harga)'];
		$jumlahBelanja = "<b>".number_format($jumlahBelanja,2,',','.');
	 }else{
	 	$Koloms[] = array(" align='left'  ","<b>".$c1);
		$namaSKPD = "<span style='font-weight:bold;'>".$getNamaSkpd['nm_skpd'];
		$getPendapatan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1'  and k='4' $kondisiFilter  "));
	 	$getTidakLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1' and k='5' and l='1' $kondisiFilter  "));
	 	$getLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and rincian_perhitungan !='' and c1='$c1'  and k='5' and l='2' $kondisiFilter  "));
	 	$pendapatan = "<b>".number_format($getPendapatan['sum(jumlah_harga)'],2,',','.');
		$tidakLangsung = "<b>".number_format($getTidakLangsung['sum(jumlah_harga)'],2,',','.');
		$langsung = "<b>".number_format($getLangsung['sum(jumlah_harga)'],2,',','.');
		$jumlahBelanja = $getTidakLangsung['sum(jumlah_harga)'] + $getLangsung['sum(jumlah_harga)'];
		$jumlahBelanja = "<b>".number_format($jumlahBelanja,2,',','.');
	 }
	 
	 
	 $Koloms[] = array('align="left"',  $namaSKPD );
	 $Koloms[] = array('align="right"', $pendapatan );
 	 $Koloms[] = array('align="right"', $tidakLangsung );
	 $Koloms[] = array('align="right"', $langsung );
	 $Koloms[] = array('align="right"', $jumlahBelanja );
	 
	

	 return $Koloms;
	}
	function genRowSum($ColStyle, $Mode, $Total){
		foreach ($_REQUEST as $key => $value) { 
		  	$$key = $value; 
		 } 
		 
		$cmbUrusan = $_REQUEST['fmSKPDUrusan'];
		$cmbBidang = $_REQUEST['fmSKPDBidang'];
		$cmbSKPD  = $_REQUEST['fmSKPDskpd'];
	    if($cmbSKPD != '' && $cmbSKPD != '00'){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
		}elseif($cmbBidang != '' && $cmbBidang != '00'){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
		}elseif($cmbUrusan != '' && $cmbUrusan != '0' ){
			$kondisiSKPD = "and c1='$cmbUrusan'";
		}
		
		if(!empty($this->jenisForm)){
			$idTahap = $this->idTahap;
		}else{
			$getIdTahapRKATerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from tabel_anggaran where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and nama_modul='RKA-SKPD' and o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) "));
		 	$idTahap = $getIdTahapRKATerakhir['max'];
		}

		$getData = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and (k ='5' and (l='1' or l='2')) and jenis_anggaran = '$this->jenisAnggaran'  $kondisiSKPD "));
		$Total = $getData['sum(jumlah_harga)'];
		$getLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and k ='5' and  l='2' and jenis_anggaran = '$this->jenisAnggaran'  $kondisiSKPD "));
		$TotalLangsung = $getLangsung['sum(jumlah_harga)'];
		$getTidakLangsung = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and k ='5' and  l='1' and jenis_anggaran = '$this->jenisAnggaran'  $kondisiSKPD "));
		$TotalTidakLangsung = $getTidakLangsung['sum(jumlah_harga)'];
		$getPendapatan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and k ='4'  and jenis_anggaran = '$this->jenisAnggaran'  $kondisiSKPD "));
		$TotalPendapatan = $getPendapatan['sum(jumlah_harga)'];
		$ContentTotalHal=''; $ContentTotal='';
			$TampilTotalHalRp = number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='3' align='center'><b>Total</td>": '';
				$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalPendapatan,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalTidakLangsung,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalLangsung,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($Total,2,',','.')."</div></td>
				</tr>" ;

			

				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		return $ContentTotalHal.$ContentTotal;
	}

	function Validasi($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 120;
	 $this->form_caption = 'VALIDASI apbd';
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
						'label'=>'KODE apbd',
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
	
	
	function Info(){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 600;
	 $this->form_height = 140;
	 $this->form_caption = 'INFO R-APBD';

	 
	 if($this->jenisFormTerakhir == "VALIDASI"){
	 	$getJumlahSKPDYangMengisiapbd = mysql_num_rows(mysql_query("select * from view_r_apbd where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirapbd' and d!='00' and status_validasi = '1' "));
	 }else{
	 	$getJumlahSKPDYangMengisiapbd = mysql_num_rows(mysql_query("select * from view_r_apbd where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirapbd' and d!='00' "));
	 }
	 
	 
	 	
	 //items ----------------------
	  $this->form_fields = array(
			'1' => array( 
						'label'=>'ANGGARAN',
						'labelWidth'=>200, 
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
						 ),	
			'5' => array( 
						'label'=>'JUMLAH SKPD YANG INPUT',
						'labelWidth'=>150, 
						'value'=> number_format($getJumlahSKPDYangMengisiapbd,0,',','.') ,
						 ),	
						 				
			
			);
		//tombol
		$this->form_menubawah =
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

	

	
	$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			".CmbUrusanBidangSkpd('apbd')."
			</tr>
			</table>".
			"</div>"
			
			;
			
		return array('TampilOpt'=>$TampilOpt);
		
		
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$arrKondisi[] = ' 1 = 1';
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn

		$ref_skpdSkpdfmUrusan = $_REQUEST['fmSKPDUrusan'];
		$ref_skpdSkpdfmSKPD = $_REQUEST['fmSKPDBidang'];
		$ref_skpdSkpdfmUNIT = $_REQUEST['fmSKPDskpd'];
	
		

		if($ref_skpdSkpdfmUrusan!='0' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='00' ){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
		if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";

		if($ref_skpdSkpdfmSKPD!='00'){

		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		     }
		}
		
		$cmbUrusan = $_REQUEST['fmSKPDUrusan'];
		$cmbBidang = $_REQUEST['fmSKPDBidang'];
		$cmbSKPD  = $_REQUEST['fmSKPDskpd'];
		if($cmbSKPD != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
		}elseif($cmbBidang != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
		}elseif($cmbUrusan != ''){
			$kondisiSKPD = "and c1='$cmbUrusan'";
		}
		
		if(!empty($this->idTahap)){
			    $kondisiFilter = " and id_tahap = '$this->idTahap' ";
				if($this->jenisForm == "VALIDASI"){
					$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
				}
			}else{
				 $getIdTahapTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) from tabel_anggaran where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and nama_modul = 'RKA-SKPD'"));
				 $idTahapTerakhir = $getIdTahapTerakhir['max(id_tahap)'];
				 $kondisiFilter = " and id_tahap = '$idTahapTerakhir' ";
					if($this->jenisFormTerakhir == "VALIDASI"){
						$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
					}
			}
		
		
	 
			
			$getAllSkpdFromRka = mysql_query("select * from tabel_anggaran where  o1 !='0' and (rincian_perhitungan !='' or f1 !='0' )  $kondisiFilter");	
			while($rows = mysql_fetch_array($getAllSkpdFromRka)){
				foreach ($rows as $key => $value) { 
				  $$key = $value; 
				}
				$dataUrusan = array(
							  'c1' => $c1,
							  'c' => '00',
							  'd' => '00',
							  'e' => '00',
							  'e1' => '000',
							  'o1' => '0',
							  'f1' => '0',
							  'f2' => '0',
							  'f' => '00',
							  'g' => '00',
							  'h' => '00',
							  'i' => '00',
							  'j' => '000',
							  'nama_modul' => 'R-APBD',
							  'jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun
							  );
				$queryUrusan = VulnWalkerInsert('tabel_anggaran', $dataUrusan);
				if(mysql_num_rows(mysql_query("select * from view_r_apbd where c1 = '$c1' and c='00' and d='00' and e='00' and e1='000' and f1='0' and o1='0' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'")) == 0){
					mysql_query($queryUrusan);
				}
				
				$dataBidang = array(
							  'c1' => $c1,
							  'c' => $c,
							  'd' => '00',
							  'e' => '00',
							  'e1' => '000',
							  'o1' => '0',
							  'f1' => '0',
							  'f2' => '0',
							  'f' => '00',
							  'g' => '00',
							  'h' => '00',
							  'i' => '00',
							  'j' => '000',
							  'nama_modul' => 'R-APBD',
							  'jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun
							  );
					$queryBidang = VulnWalkerInsert('tabel_anggaran', $dataBidang);
					if(mysql_num_rows(mysql_query("select * from view_r_apbd where c1 = '$c1' and c='$c' and d='00' and e='00' and e1='000' and f1='0' and o1='0' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'")) == 0){
						mysql_query($queryBidang);
					}
				
				$dataSKPD = array(
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => '00',
							  'e1' => '000',
							  'o1' => '0',
							  'f1' => '0',
							  'f2' => '0',
							  'f' => '00',
							  'g' => '00',
							  'h' => '00',
							  'i' => '00',
							  'j' => '000',
							  'nama_modul' => 'R-APBD',
							  'jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun
							  );
					$querySKPD = VulnWalkerInsert('tabel_anggaran', $dataSKPD);
					if(mysql_num_rows(mysql_query("select * from view_r_apbd where c1 = '$c1' and c='$c' and d='$d' and e='00' and e1='000' and f1='0' and o1='0' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'")) == 0){
						mysql_query($querySKPD);
					}
								  
				}
					
  		
		
		$grabAll = mysql_query("select * from view_r_apbd where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'");
		while($rows = mysql_fetch_array($grabAll)){
			foreach ($rows as $key => $value) { 
		  		$$key = $value; 
		 	}
			/*$getTotalPerrekening = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where k='$k' and l='$l' and m='$m' and n='$n' and o='$o' $kondisiSKPD $kondisiFilter and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and nama_modul = 'RKA-SKPD'"));
		 	$total = $getTotalPerrekening['sum(jumlah_harga)'];
		 	if($total == 0){
				$arrKondisi[] = "id_anggaran !='$id_anggaran'";
			}	*/
		}
		
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
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
}
$r_apbd = new apbdObj();

$arrayResult = VulnWalkerTahap($r_apbd->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$r_apbd->jenisForm = $jenisForm;
$r_apbd->nomorUrut = $nomorUrut;
$r_apbd->tahun = $tahun;
$r_apbd->jenisAnggaran = $jenisAnggaran;
$r_apbd->idTahap = $idTahap;


if(empty($r_apbd->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_r_apbd "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_r_apbd where id_anggaran = '$maxAnggaran'"));
	/*$r_apbd->tahun = "select max(id_anggaran) as max from view_r_apbd where nama_modul = 'apbd'";*/
	$r_apbd->tahun  = $get2['tahun'];
	$r_apbd->jenisAnggaran = $get2['jenis_anggaran'];
	$r_apbd->urutTerakhir = $get2['no_urut'];
	$r_apbd->jenisFormTerakhir = $get2['jenis_form_modul'];
	$r_apbd->urutSebelumnya = $r_apbd->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$r_apbd->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$r_apbd->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$r_apbd->noUrutTerakhirapbd = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$r_apbd->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap();
	$r_apbd->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$r_apbd->idTahap'"));
	$r_apbd->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$r_apbd->idTahap'"));
	$r_apbd->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$r_apbd->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$r_apbd->noUrutTerakhirapbd = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$r_apbd->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}


?>