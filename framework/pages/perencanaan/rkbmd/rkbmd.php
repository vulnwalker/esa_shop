<?php

class rkbmdObj  extends DaftarObj2{	
	var $Prefix = 'rkbmd';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = "view_rkbmd"; //daftar
	var $TblName_Hapus = 'tabel_anggaran';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id_anggaran');
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 3;
	var $PageTitle = 'RKBMD';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'Daftar Standar Kebutuhan Barang Maksimal';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'rkbmdForm'; 	
	var $kode_skpd = '';
	var $modul = "RKBMD";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	var $namaTahapTerakhir = "";
	var $masaTerakhir = "";
	var $currentTahap = "";
    //untuk view
	var $urutTerakhir = "";
	var $urutSebelumnya = "";
	var $jenisFormTerakhir = "";
	var $tahapTerakhir = "";
	
	//untuk view		
	function setTitle(){
		return 'RKBMD TAHUN '.$this->tahun;
	}
	function setMenuEdit(){
			if ($this->jenisForm == "PENYUSUNAN"){
			 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".InputBaru()","sections.png","Baru ", 'Baru ')."</td>".
							"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
							"<td>".genPanelIcon("javascript:".$this->Prefix.".remove()","delete_f2.png","Hapus", 'Hapus')."</td>";	
			 }elseif ($this->jenisForm == "VALIDASI"){
			 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Validasi()","validasi-menu.png","Validasi", 'Validasi')."</td>";	
			 }elseif ($this->jenisForm == "KOREKSI PENGGUNA"){
		     }elseif ($this->jenisForm == "KOREKSI PENGELOLA"){
		     }else{
		 	 
			 }
		 
		 
		 return $listMenu;
	}
	function setPage_HeaderOther(){
   	
	$executeMurni =  mysql_query("select * from ref_modul where status='AKTIF' ");
	while($getTahapMurni = mysql_fetch_array($executeMurni)){
	$lower = strtolower($getTahapMurni[1]);
		if ($getTahapMurni[0] == $this->idModul ){
			$murni .= "<A href=\"pages.php?Pg=$lower\" title='$getTahapMurni[1] MURNI' style='color : blue;' > $getTahapMurni[1] </a> | ";
		}else{
			$murni .= "<A href=\"pages.php?Pg=$lower\" title='$getTahapMurni[1] MURNI' > $getTahapMurni[1] </a> | ";
		}
		
	}
	
	
   	
	return 
			"<table  class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin-left:8px; width:99%;'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
	$murni
	
	."
	&nbsp&nbsp&nbsp	
	</td></tr>
	</table>";
	}
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		//$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		//$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		//$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";*/
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
	 foreach ($_REQUEST as $key => $value) { 
		  $$key = $value; 
	 } 
	 if($kodeBarang == '')$err='Barang Belum Di Pilih';

	 if(empty($jumlah))$err="Isi Jumlah";
	 $arrayKodeBarang = explode(".",$kodeBarang);
	 $f1 = $arrayKodeBarang[0];
	 $f2 = $arrayKodeBarang[1];
	 $f  = $arrayKodeBarang[2];
	 $g  = $arrayKodeBarang[3];
	 $h  = $arrayKodeBarang[4];
	 $i  = $arrayKodeBarang[5];
	 $j  = $arrayKodeBarang[6];
	 
			if($fmST == 0){ 
				 if(empty($cmbUrusanForm) || empty($cmbBidangForm) || empty($cmbSKPDForm) || empty($cmbUnitForm) || empty($cmbSubUnitForm))$err="Lengkapi SKPD";
				if($err==''){ 
						$data = array ('c1' => $cmbUrusanForm,
									   'c' => $cmbBidangForm,
									   'd' => $cmbSKPDForm,
									   'e' => $cmbUnitForm,
									   'e1' => $cmbSubUnitForm,
									   'f1' => $f1,
									   'f2' => $f2,
									   'f' => $f,
									   'g' => $g,
									   'h' => $h,
									   'i' => $i,
									   'j' => $j,
									   'jumlah' => $jumlah
									   );
						 $cek .= VulnWalkerInsert("ref_std_kebutuhan",$data);
						$input =  mysql_query(VulnWalkerInsert("ref_std_kebutuhan",$data));
						if($input){
							
						}else{
							$err="Gagal Simpan";
						}
							
				}
			}elseif($fmST == 1){		
			 			$data= array('jumlah' => $jumlah);
						$cek .= VulnWalkerUpdate("ref_std_kebutuhan",$data,"concat(c1,' ',c,' ',d,' ',e,' ',e1,' ',f1,' ',f2,' ',f,' ',g,' ',h,' ',i,' ',j) = '$idplh'");	
						mysql_query(VulnWalkerUpdate("ref_std_kebutuhan",$data,"concat(c1,' ',c,' ',d,' ',e,' ',e1,' ',f1,' ',f2,' ',f,' ',g,' ',h,' ',i,' ',j) = '$idplh'"));				

			}else{
			/*if($err==''){ 
						$kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
 						
						$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga)
						"."values('$f','$g','$h','$i','$j','$tahun_anggaran','$harga')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);
						 
				}*/
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
		case 'newTab':{
			 foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			 $nomorUrutSebelumnya = $this->nomorUrut - 1;
			 $cekKeberadaanMangkluk =  mysql_num_rows(mysql_query("select * from view_renja where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1 = '$rkbmdSkpdfmUrusan' and c = '$rkbmdSkpdfmSKPD' and d='$rkbmdSkpdfmUNIT' and e = '$rkbmdSkpdfmSUBUNIT' and e1='$rkbmdSkpdfmSEKSI'  and q!='0' and no_urut ='$nomorUrutSebelumnya' "));		
			 $getDatarenja = mysql_fetch_array(mysql_query("select * from view_renja where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1 = '$rkbmdSkpdfmUrusan' and c = '$rkbmdSkpdfmSKPD' and d='$rkbmdSkpdfmUNIT' and e = '$rkbmdSkpdfmSUBUNIT' and e1='$rkbmdSkpdfmSEKSI' and q!='0' and no_urut = '$nomorUrutSebelumnya'"));	 
			 $lastID = $getDatarenja['id_anggaran'];
			 	if($cekKeberadaanMangkluk != 0){
					if($getDatarenja['jenis_form_modul']  == 'VALIDASI' ){
						$getJumlahRenjaValidasi = mysql_num_rows(mysql_query("select * from view_renja where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1 = '$rkbmdSkpdfmUrusan' and c = '$rkbmdSkpdfmSKPD' and d='$rkbmdSkpdfmUNIT' and e = '$rkbmdSkpdfmSUBUNIT' and e1='$rkbmdSkpdfmSEKSI' and q!='0' and status_validasi = '1' and no_urut = '$nomorUrutSebelumnya'"));
						if($getJumlahRenjaValidasi == 0){
							$err = "Renja Belum Di Validasi";
						}
					}
					
					$getParentRKBMD = mysql_fetch_array(mysql_query("select * from view_renja where id_anggaran = '$lastID'"));
					$parentC1 = $getParentRKBMD['c1'];
					$parentC = $getParentRKBMD['c'];
					$parentD = $getParentRKBMD['d'];
					$parentE = $getParentRKBMD['e'];
					$parentE1= $getParentRKBMD['e1'];
					$getIdRenja = mysql_fetch_array(mysql_query("select * from view_renja where tahun = '$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 = '$parentC1' and c = '$parentC' and d = '$parentD' and e = '$parentD' and e1 = '$parentE1' and p = '0' and q = '0' and no_urut = '$nomorUrutSebelumnya' "));
					$idrenja = $getIdRenja['id_anggaran'];	
					if($cmbJenisRKBMD == 'PENGADAAN'){
						$kemana = 'ins';
					}else{
						$kemana = 'pemeliharaan';
					}
					$username = $_COOKIE['coID'];
					mysql_query("delete from temp_rkbmd_pengadaan where user = '$username'");
					mysql_query("delete from temp_rkbmd_pemeliharaan where user = '$username'");
					mysql_query("delete from rkbmd_pemeliharaan where user = '$username'");
				}else{
					$err  = "Renja Belum ada atau belum di Koreksi";
					
				}
				
				
				$content = array('idrenja' => $idrenja, "kemana" =>$kemana);
			break;
		}		
		case 'editTab':{
			 $id = $_REQUEST['rkbmd_cb'];
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
					$nomorUrutSebelumnya = $this->nomorUrut - 1;
					$getParentRKBMD = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_anggaran = '$id[0]'"));
					foreach ($getParentRKBMD as $key => $value) { 
				 		 $$key = $value; 
					 } 
					$parentC1 = $getParentRKBMD['c1'];
					$parentC = $getParentRKBMD['c'];
					$parentD = $getParentRKBMD['d'];
					$parentE = $getParentRKBMD['e'];
					$parentE1= $getParentRKBMD['e1'];
					$getIdRenja = mysql_fetch_array(mysql_query("select * from view_renja where tahun = '$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 = '$parentC1' and c = '$parentC' and d = '$parentD' and e = '$parentD' and e1 = '$parentE1' and p = '0' and q = '0' and no_urut = '$nomorUrutSebelumnya' "));
					$idrenja = $getIdRenja['id_anggaran'];	
					if($cmbJenisRKBMD == 'PENGADAAN'){
						$kemana = 'ins';
					}else{
						$kemana = 'pemeliharaan';
					}
					$username = $_COOKIE['coID'];
					mysql_query("delete from temp_rkbmd_pengadaan where user = '$username'");
					mysql_query("delete from temp_rkbmd_pemeliharaan where user = '$username'");
					mysql_query("delete from rkbmd_pemeliharaan where user = '$username'");
					
					$execute = mysql_query("select * from view_rkbmd where  c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and id_tahap ='$this->idTahap' and f1 !='' ");
					if($kemana == 'ins'){
							while($rows = mysql_fetch_array($execute)){
							foreach ($rows as $key => $value) { 
					 			 $$key = $value; 
						 	} 
							$data  = array(
							   "c1" => $c1,
							   "c" => $c,
							   "d" => $d,
							   "e" => $e,
							   "e1" => $e1,
							   "bk" => $bk,
							   "ck" => $ck,
							   "dk" => '0',
							   "p" => $p,
							   "q" => $q,
							   "f1" => $f1,
							   "f2" => $f2,
							   "f" => $f,
							   "g" => $g,
							   "h" => $h,
							   "i" => $i,
							   "j" => $j,
							   "jumlah" => $volume_barang,
							   "catatan" => $catatan,
							   "user" => $_COOKIE['coID']
							);
							mysql_query(VulnWalkerInsert('temp_rkbmd_pengadaan',$data));
						}
					}elseif($kemana == 'pemeliharaan' ){
						$username = $_COOKIE['coID'];
							while($rows = mysql_fetch_array($execute)){
							foreach ($rows as $key => $value) { 
					 			 $$key = $value; 
						 	} 
							$data = array(
									'c1' => $c1,
									'c' => $c,
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
									'keterangan' => $catatan,
									'uraian_pemeliharaan' => $uraian_pemeliharaan,
									'volume_barang' => $volume_barang,
									'user' => $username
								  );
							if($id_jenis_pemeliharaan != '0'){
								mysql_query(VulnWalkerInsert('rkbmd_pemeliharaan',$data));
							}	  
							
						}
					}
					
				
				
				$content = array('idrenja' => $idrenja, "kemana" =>$kemana, "qc" => "select * from view_rkbmd where id_anggaran = '$id[0]'");
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
		case 'SaveValid':{
	    	foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			 if ($validasi == 'on') {
			 	 $status_validasi = "1";
			 }else{
			 	$status_validasi = "0";
			 }
			 $getSKPD = mysql_fetch_array(mysql_query("select * from tabel_anggaran where id_anggaran = '$rkbmd_idplh'"));
			 $cmbUrusanForm = $getSKPD['c1'];
			 $cmbBidangForm = $getSKPD['c'];
			 $cmbSKPDForm = $getSKPD['d'];
			 $cmbUnitForm = $getSKPD['e'];
			 $cmbSubUnitForm = $getSKPD['e1'];
			 $bk= $getSKPD['bk'];
			 $ck = $getSKPD['ck'];
			 $p = $getSKPD['p'];
			 $q = $getSKPD['q'];
			 

			 $data = array( "status_validasi" => $status_validasi,
			 				'user_validasi' => $_COOKIE['coID'],
			 				'tanggal_validasi' => date("Y-m-d"),
							'id_tahap' => $this->idTahap
			 				);
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$rkbmd_idplh'");
			 mysql_query($query);

			$content .= $query;
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
	   case 'remove':{				
			$id = $_REQUEST['rkbmd_cb'];
			for($i = 0 ; $i < sizeof($id) ; $i++ ){
				$getData = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_anggaran='$id[$i]'"));
				foreach ($getData as $key => $value) { 
					  $$key = $value; 
				}
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					mysql_query("delete from tabel_anggaran where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and id_tahap ='$this->idTahap' and ((id_jenis_pemeliharaan = '0' and f1 !='') or uraian_pemeliharaan = 'RKBMD PENGADAAN') ");
				}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
					mysql_query("delete from tabel_anggaran where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and id_tahap ='$this->idTahap' and ((id_jenis_pemeliharaan != '0' and f1 !='') or uraian_pemeliharaan = 'RKBMD PEMELIHARAAN') ");
				}
				
				
			}
												
		break;
		}
		case 'koreksi':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			$queryRows = "select * from tabel_anggaran where id_anggaran = '$idAwal'";
			$getrkbmdnya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getrkbmdnya as $key => $value) { 
				  $$key = $value; 
			} 



			
			
			$cekSKPD = mysql_num_rows(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '0' and ck = '0' and p = '0' and q= '0' and id_tahap='$this->idTahap'"));
				if($cekSKPD < 1){
					$data = array('jenis_anggaran' => $this->jenisAnggaran,
								  'tahun' => $this->tahun,
								  'c1' => $c1,
								  'c' => $c,
								  'd' => $d,
								  'e' => $e,
								  'e1' => $e1,
								  'bk' => '0',
								  'ck' => '0',
								  'dk' => '0',
								  'p' => '0',
								  'q' => '0',
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID']
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran',$data));
				}
				$cekProgram = mysql_num_rows(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '0' and id_tahap='$this->idTahap'"));												
				if($cekProgram < 1){
					$data = array('jenis_anggaran' => $this->jenisAnggaran,
								  'tahun' => $this->tahun,
								  'c1' => $c1,
								  'c' => $c,
								  'd' => $d,
								  'e' => $e,
								  'e1' => $e1,
								  'bk' => $bk,
								  'ck' => $ck,
								  'dk' => '0',
								  'p' => $p,
								  'q' => '0',
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID']
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran',$data));
				}
				
				$cekKegiatanPemeliharaan = mysql_num_rows(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '$q' and  f1='' and id_tahap='$this->idTahap' and uraian_pemeliharaan = 'RKBMD PEMELIHARAAN'"));												
				if($cekKegiatanPemeliharaan < 1){
					$data = array('jenis_anggaran' => $this->jenisAnggaran,
								  'tahun' => $this->tahun,
								  'c1' => $c1,
								  'c' => $c,
								  'd' => $d,
								  'e' => $e,
								  'e1' => $e1,
								  'bk' => $bk,
								  'ck' => $ck,
								  'dk' => '0',
								  'p' => $p,
								  'q' => $q,
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID'],
								  'uraian_pemeliharaan' => 'RKBMD PEMELIHARAAN' 
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran',$data));
				}



				$cekKegiatanPengadaan = mysql_num_rows(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '$q' and  f1='' and id_tahap='$this->idTahap' and uraian_pemeliharaan = 'RKBMD PENGADAAN'"));												
				if($cekKegiatanPengadaan < 1){
					$data = array('jenis_anggaran' => $this->jenisAnggaran,
								  'tahun' => $this->tahun,
								  'c1' => $c1,
								  'c' => $c,
								  'd' => $d,
								  'e' => $e,
								  'e1' => $e1,
								  'bk' => $bk,
								  'ck' => $ck,
								  'dk' => '0',
								  'p' => $p,
								  'q' => $q,
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID'],
								  'uraian_pemeliharaan' => 'RKBMD PENGADAAN' 
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran',$data));
				}



			 
			 
			 $dataSesuai = array(           'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $c1,
											'c' => $c,
											'd' => $d,
											'e' => $e,
											'e1' => $e1,
											'bk' => $bk,
											'ck' => $ck,
											'p' => $p,
											'q' => $q,
											'f1' => $f1,
											'f2' => $f2,
											'f' => $f,
											'g' => $g,
											'h' => $h,
											'i' => $i,
											'j' => $j,
											'cara_pemenuhan' => $caraPemenuhan, 
											'volume_barang' => $angkaKoreksi,
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul,
											'uraian_pemeliharaan' => $uraian_pemeliharaan,
											'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
											'user_update' => $_COOKIE['coID'],
											'tanggal_update' => date('Y-m-d')


 								);
								
			  $kodeBarang =$f1.".".$f2.".".$f.".".$g.".".$h.".".$i.".".$j ;
			  $kodeSKPD = $c1.".".$c.".".$d.".".$e.".".$e1;
			  $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
			  $concat = $kodeSKPD.".".$kodeBarang;

			  if($jenisRKBMD == "PENGADAAN"){
				  $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
				  $kebutuhanMax = $getKebutuhanMax['jumlah'];
				  $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
				  $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
				  $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
				  $melebihi = "Kebutuhan Riil";
			  }elseif($jenisRKBMD == "PEMELIHARAAN"){
				  $getDataBukuInduk = mysql_fetch_array(mysql_query("select sum(jml_barang) as totalBarang from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1'"));
				  $kebutuhanRiil = $getDataBukuInduk['totalBarang'];
				  $melebihi = "jumlah barang di buku induk";
			  }
			  	


			  $cekKoreksi =  mysql_num_rows(mysql_query("select * from tabel_anggaran where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q'  and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
			  if($cekKoreksi > 0 ){
			   	 $getID = mysql_fetch_array(mysql_query("select * from tabel_anggaran where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q'  and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					    $idnya = $getID['id_anggaran'];
						if($angkaKoreksi <= $kebutuhanRiil){
							mysql_query("update tabel_anggaran set volume_barang = '$angkaKoreksi' , cara_pemenuhan = '$caraPemenuhan' where id_anggaran='$idnya'");
						}else{
							$err = "Tidak dapat melebihi $melebihi";
						}
						
			}else{
						if($angkaKoreksi <= $kebutuhanRiil){
							mysql_query(VulnWalkerInsert("tabel_anggaran", $dataSesuai));	
						}else{
							$err = "Tidak dapat melebihi $melebihi";
						}
						
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
			$getMaxID = mysql_fetch_array(mysql_query("select * from tabel_anggaran where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q'  and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' ")); 
			$maxID = $getMaxID['id_anggaran'];
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
			"
			
			<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			 "<script type='text/javascript' src='js/perencanaan/rkbmd/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".			
			$scriptload;
	}
	
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
/*		$nomorUrutSebelumnya = $this-> -1;*/
		if($this->jenisForm == "PENYUSUNAN"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
				  	   $Checkbox
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					    <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>	   	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  
					   </tr>
					   </thead>";
				}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
				  	   $Checkbox
					   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
					   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
					   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>	    	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
					      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
						  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
					   </tr>
					   <tr>
					   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
					   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
					   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
					   </tr>
					   
					   </thead>";
				}
			 		
		}
		
		//VALIDASI
		elseif($this->jenisForm == "VALIDASI"){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisFormSebelumnya = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where no_urut = '$nomorUrutSebelumnya' and tahun ='$this->tahun' and anggaran = '$this->jenisAnggaran'"));
			$jenisFormSebelumnya = $getJenisFormSebelumnya['jenis_form_modul'];
				if($jenisFormSebelumnya == "PENYUSUNAN"){
					if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
				  	   $Checkbox
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>VALIDASI</th>	   	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
					  	   $Checkbox
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>VALIDASI</th>		    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
					
				}
				
			 		
		}
		//VALIDASI
		//KOREKSI PENGGUNA
		elseif($this->jenisForm == "KOREKSI PENGGUNA"){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisFormSebelumnya = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where no_urut = '$nomorUrutSebelumnya' and tahun ='$this->tahun' and anggaran = '$this->jenisAnggaran'"));
			$jenisFormSebelumnya = $getJenisFormSebelumnya['jenis_form_modul'];
					if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='300'>AKSI</th>  	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	 
					       <th class='th01' align='center' rowspan='3' colspan='1' width='500'>AKSI</th>  		    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>CARA PEMENUHAN</th>
					      	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
					
				
				
			 		
		}
		
		elseif($this->jenisForm == "KOREKSI PENGELOLA"){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisFormSebelumnya = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where no_urut = '$nomorUrutSebelumnya' and tahun ='$this->tahun' and anggaran = '$this->jenisAnggaran'"));
			$jenisFormSebelumnya = $getJenisFormSebelumnya['jenis_form_modul'];
					if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGELOLA</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='300'>AKSI</th>  	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGELOLA</th>	 
					       <th class='th01' align='center' rowspan='3' colspan='1' width='500'>AKSI</th>  		    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>CARA PEMENUHAN</th>
					      	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>CARA PEMENUHAN</th>
					     	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
					
				
				
			 		
		}
		//KOREKSI PENGGUNA
		//view
		else{
			if($this->jenisFormTerakhir == "PENYUSUNAN"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					    <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>	   	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  
					   </tr>
					   </thead>";
				}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
					   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
					   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>	    	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
					      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
						  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
						  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
					   </tr>
					   <tr>
					   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
					   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
					   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
					   </tr>
					   
					   </thead>";
				}
			}elseif($this->jenisFormTerakhir =="VALIDASI"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>VALIDASI</th>	   	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>VALIDASI</th>		    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
			}
			elseif($this->jenisFormTerakhir == "KOREKSI PENGGUNA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	 	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	 	    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>CARA PEMENUHAN</th>
					      	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
			}
			elseif($this->jenisFormTerakhir =="KOREKSI PENGELOLA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					$headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01'  rowspan='2' colspan='1' width='20' >No.</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='500'>SKPD/PROGRAM/KEGIATAN/OUTPUT</th>		
				   	   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>USULAN BMD</th>
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML RIIL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML MAX</th>	   
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JML OPTIMAL</th>	 
					   <th class='th01' align='center' rowspan='2' colspan='1' width='200'>KETERANGAN</th>
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	
					   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGELOLA</th>	   	   
					   </tr>
					   <tr>
					      <th class='th01' align='center'  width='600'>NAMA BARANG</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
					      <th class='th01' align='center'  width='200'>SATUAN</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
						  <th class='th01' align='center'  width='200'>JUMLAH</th>
						  <th class='th01' align='center'  width='200'>CARA PEMENUHAN</th>
					      <th class='th01' align='center'  width='200'>USER/TGL UPDATE</th>
					   </tr>
					   </thead>";
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
						$headerTable =
						 "<thead>
						 <tr>
					  	   <th class='th01'  rowspan='3' colspan='1' width='20' >No.</th>
						   <th class='th01' align='center' rowspan='3' colspan='1' width='500'>PROGRAM/KEGIATAN/OUTPUT</th>		
					   	   <th class='th02' align='center' rowspan='1' colspan='8' width='200'>BARANG YANG DIPELIHARA</th>
						   <th class='th02' align='center' rowspan='1' colspan='4' width='200'>USULAN KEBUTUHAN PEMELIHARAAN</th>	 
						   <th class='th01' align='center' rowspan='3' colspan='1' width='200'>KETERANGAN</th>
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGGUNA</th>	
						   <th class='th02' align='center' rowspan='1' colspan='3' width='200'>DISETUJUI PENGELOLA</th>	 	    	   	   
						   </tr>
						   <tr>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>KODE BARANG</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='600'>NAMA BARANG</th>
						      <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>STATUS BARANG</th>
							  <th class='th02' align='center' rowspan='1' colspan='3' width='100'>KONDISI</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JENIS PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>URAIAN PEMELIHARAAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>JUMLAH</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='100'>SATUAN</th>
							  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>CARA PEMENUHAN</th>
					      	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  <th class='th01' align='center' rowspan='2' colspan='1'  width='200'>JUMLAH</th>
						 	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>CARA PEMENUHAN</th>
					     	  <th class='th01' align='center' rowspan='2' colspan='1' width='200'>USER/TGL UPDATE</th>
							  
						   </tr>
						   <tr>
						   <th class='th01' align='center' rowspan=1' colspan='1' width='50'>B</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>KB</th>
						   <th class='th01' align='center' rowspan='1' colspan='1' width='50'>RB</th>
						   </tr>
						   
						   </thead>";
					}
				
			}
			
		}
		
		
		
		
	
		return $headerTable;
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
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  $$key = $value; 
	 }
		
	 
	   if($this->jenisForm=="PENYUSUNAN"){
		   	if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
				if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
					 $kebutuhanMax = $getKebutuhanMax['jumlah'];
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
					 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
					 $satuan = $getBarang['satuan'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= "<td class='$cssclass' align='center'></td>";
					 $Koloms.= $tampilHeader;
					 $Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 
				 }
					
					$Koloms = array(
					 	array("Y", $Koloms),
					 );
			}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
				if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= "<td class='$cssclass' align='center'></td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 
					 
					 $Koloms.= "<td class='$cssclass' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 
				 }
					
					$Koloms = array(
					 	array("Y", $Koloms),
					 );
			}
			   	 
	 	 }
	   elseif($this->jenisForm=="VALIDASI"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
						 $Koloms.= "<td class='$cssclass' align='center'>"."<img src='images/administrator/images/$validnya' width='20px' heigh='20px'"."</td>";
						 
					 }
						
						
				}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= "<td class='$cssclass' align='center'></td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= "<td class='$cssclass' align='center'>$TampilCheckBox</td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 
					 
					 $Koloms.= "<td class='$cssclass' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 if($status_validasi == '1'){
						 	$validnya = "valid.png";
						 }else{
						 	$validnya = "invalid.png";
						 }
					 $Koloms.= "<td class='$cssclass' align='center'>"."<img src='images/administrator/images/$validnya' width='20px' heigh='20px'"."</td>";
				 }
						
						
				}
				$Koloms = array(
						 	array("Y", $Koloms),
						 );
			}
	   elseif($this->jenisForm=="KOREKSI PENGGUNA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 
						 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
						 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
						 
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left' ><span style='color:$kondisiWarna;'>$namaBarang</span></td>";
						 $Koloms.= "<td class='$cssclass' align='right'><input type='hidden' id='volumeBarang$id_anggaran' value='$volume_barang'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='$align'><span id='spanJumlah$id_anggaran'>$jumlahKoreksi</span> <span style='color:red;' id='bantuJumlah$id_anggaran'><span> </td>";
						 $Koloms.= "<td class='$cssclass' align='left'><span id='spanCaraPemenuhan$id_anggaran'>$caraPemenuhan</span> </td>";
						 $Koloms.= "<td class='$cssclass' id='updatePengguna$id_anggaran' align='left'><span id='save$id_anggaran'>$keteranganKoreksi</span></td>";
						 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.catatan('$id_anggaran');></img> ";
						 $Koloms.= "<td class='$cssclass' align='center'>$aksi</td>";
						 
					 }
						
						
				}
				elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					 $nomorUrutSebelumnya = $this->nomorUrut -1 ;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
					 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
					 
					 $Koloms.= "<td class='$cssclass' style='color:$kondisiWarna ;' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'><input type='hidden' id='volumeBarang$id_anggaran' value='$volume_barang'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='$align'><span id='spanJumlah$id_anggaran'>$jumlahKoreksi</span> <span style='color:red;' id='bantuJumlah$id_anggaran'><span> </td>";
					 $Koloms.= "<td class='$cssclass' align='left'><span id='spanCaraPemenuhan$id_anggaran'>$caraPemenuhan</span> </td>";
					 $Koloms.= "<td class='$cssclass' id='updatePengguna$id_anggaran' align='left'><span id='save$id_anggaran'>$keteranganKoreksi</span></td>";
					 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.catatan('$id_anggaran');></img> ";
					 $Koloms.= "<td class='$cssclass' align='center'>$aksi</td>";
				 }
						
						
				}
				$Koloms = array(
						 	array("Y", $Koloms),
						 );
			} 
			
	   elseif($this->jenisForm=="KOREKSI PENGELOLA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 
						 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
						 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
						 
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left' ><span style='color:$kondisiWarna;'>$namaBarang</span></td>";
						 
						 
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'><input type='hidden' id='volumeBarang$id_anggaran' value='$volume_barang'>".number_format($volume_barang,0,',','.')." </td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$cara_pemenuhan</td>";
						 $tanggal_update = explode("-",$tanggal_update);
					 	 $tanggal_update = $tanggal_update[2]."-".$tanggal_update[1]."-".$tanggal_update[0];
						 $Koloms.= "<td class='$cssclass' align='left'>$user_update / $tanggal_update</td>";
						 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='right'><span id='spanJumlah$id_anggaran'>$jumlahKoreksi</span> <span style='color:red;' id='bantuJumlah$id_anggaran'><span> </td>";
						 $Koloms.= "<td class='$cssclass' align='left'><span id='spanCaraPemenuhan$id_anggaran'>$caraPemenuhan</span> </td>";
						 $Koloms.= "<td class='$cssclass' id='updatePengguna$id_anggaran' align='left'><span id='save$id_anggaran'>$keteranganKoreksi</span></td>";
						 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.catatan('$id_anggaran');></img> ";
						 $Koloms.= "<td class='$cssclass' align='center'>$aksi</td>";
						 
					 }
						
						
				}
				elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	 $nomorUrutSebelumnya = $this->nomorUrut -1;
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					  $nomorurutkurang2 = $this->nomorUrut - 2;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0' and tahun = '$this->tahun' and jenis_anggaran='$this->jenisAnggaran' and no_urut = '$nomorurutkurang2'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
					 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where id_tahap='$this->idTahap' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
					 
					 $Koloms.= "<td class='$cssclass' style='color:$kondisiWarna ;' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					
					 $getJumlahBarangPertama = mysql_fetch_array(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorurutkurang2'"));
					 $jumlahBarangPertama = $getJumlahBarangPertama['volume_barang'];
					 $tanggal_update = explode("-",$tanggal_update);
					 $tanggal_update = $tanggal_update[2]."-".$tanggal_update[1]."-".$tanggal_update[0];
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahBarangPertama,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'><input type='hidden' id='volumeBarang$id_anggaran' value='$volume_barang'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$cara_pemenuhan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$user_update / $tanggal_update</td>";
					 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='$align'><span id='spanJumlah$id_anggaran'>$jumlahKoreksi</span> <span style='color:red;' id='bantuJumlah$id_anggaran'><span> </td>";
					 $Koloms.= "<td class='$cssclass' align='left'><span id='spanCaraPemenuhan$id_anggaran'>$caraPemenuhan</span> </td>";
					 $Koloms.= "<td class='$cssclass' id='updatePengguna$id_anggaran' align='left'><span id='save$id_anggaran'>$keteranganKoreksi</span></td>";
					 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.catatan('$id_anggaran');></img> ";
					 $Koloms.= "<td class='$cssclass' align='center'>$aksi</td>";
				 }
						
						
				}
				$Koloms = array(
						 	array("Y", $Koloms),
						 );
			} 
	   else{
	   		if($this->jenisFormTerakhir == "PENYUSUNAN"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 
					 }
						
						$Koloms = array(
						 	array("Y", $Koloms),
						 );
				}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
						 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
						 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
						 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
						 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
						 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
						 $satuan = $getBarang['satuan'];
						 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
						 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
						 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
						 $sumVolume = $getSumVolumeBarang['jumlah'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 if($urut == $getMinID['min']){
						 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
						 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
							$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
						 }else{
						 	$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
						 }
						 
						 
						 $Koloms.= "<td class='$cssclass' align='left'>$jenisPemeliharaan</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 
					 }
						
						$Koloms = array(
						 	array("Y", $Koloms),
						 );
				}
			}elseif($this->jenisFormTerakhir == "VALIDASI"){
					if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
						if($f1 == '' && $q =='0')$TampilCheckBox = "";
					   	  if($p =='0'){
						 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
							$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
						 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
							$style = "style='font-weight:bold;'";
							$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
							$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
							$Koloms.= $tampilHeader;
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='right'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
						 }elseif($p!= '0' && $q=='0'){
						 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
							$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
							$header = genNumber($p).". ".$getNamaPrgoram['nama'];
							$style = "style='margin-left:5px;font-weight:bold;'";
							$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
							$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
							$Koloms.= $tampilHeader;
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='right'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
						 }elseif($f1 == '' && $q!='0'){
						 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
							$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
							$header = genNumber($q).". ".$getNamaPrgoram['nama'];
							$style = "style='margin-left:10px;font-weight:bold;'";
							$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
							$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
							$Koloms.= $tampilHeader;
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='right'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
							$Koloms.= "<td class='$cssclass' align='left'></td>";
						 }else{
						 	
							 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
							 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
							 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
							 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
							 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
							 $getBarang = mysql_fetch_array(mysql_query($syntax));
							 $namaBarang = $getBarang['nm_barang'];
							 
							 $concat = $kodeSKPD.".".$kodeBarang;
							 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
							 $kebutuhanMax = $getKebutuhanMax['jumlah'];
							 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
							 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
							 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
							 $satuan = $getBarang['satuan'];
							 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
							 $Koloms.= $tampilHeader;
							 $Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
							 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
							 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
							 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
							 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
							 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
							 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
							 if($status_validasi == '1'){
							 	$validnya = "valid.png";
							 }else{
							 	$validnya = "invalid.png";
							 }
							 $Koloms.= "<td class='$cssclass' align='center'>"."<img src='images/administrator/images/$validnya' width='20px' heigh='20px'"."</td>";
							 
						 }
							
							
					}elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
						if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
						 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
						 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
						 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
						 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
						 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
						 $satuan = $getBarang['satuan'];
						 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
						 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
						 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
						 $sumVolume = $getSumVolumeBarang['jumlah'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 if($urut == $getMinID['min']){
						 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
						 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
							$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
						 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
						 }else{
						 	$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
							$Koloms.= "<td class='$cssclass' align='center'></td>";
						 }
						 
						 
						 $Koloms.= "<td class='$cssclass' align='left'>$jenisPemeliharaan</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 if($status_validasi == '1'){
							 	$validnya = "valid.png";
							 }else{
							 	$validnya = "invalid.png";
							 }
						 $Koloms.= "<td class='$cssclass' align='center'>"."<img src='images/administrator/images/$validnya' width='20px' heigh='20px'"."</td>";
					 }
							
							
					}
					$Koloms = array(
							 	array("Y", $Koloms),
							 );
			}
			elseif($this->jenisFormTerakhir == "KOREKSI PENGGUNA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 
						 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
						 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
						 }
						 
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left' ><span style='color:black;'>$namaBarang</span></td>";
						 $nomorUrutDuaTahapSebelumnya = $this->urutTerakhir - 1;
						 $get2TahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrutDuaTahapSebelumnya' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
						 $volume_barang = $get2TahapSebelumnya['volume_barang'];
						 $catatan = $get2TahapSebelumnya['catatan'];
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='$align'>$jumlahKoreksi </td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$caraPemenuhan </td>";
						 $Koloms.= "<td class='$cssclass' id='updatePengguna$id_anggaran' align='left'>$keteranganKoreksi</td>";
						 
					 }
						
						
				}
				elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					 $nomorUrutSebelumnya = $this->nomorUrut -1 ;
					 $nomorUrut2TahapSebelumnya = $this->urutTerakhir - 1;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as  jumlah  , catatan from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrut2TahapSebelumnya'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $catatan = $getSumVolumeBarang['catatan'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$this->urutTerakhir' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
					 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where no_urut = '$this->urutTerakhir' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
					 
					 $Koloms.= "<td class='$cssclass' style='color:black ;' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 $Koloms.= "<td class='$cssclass'  align='right'>$jumlahKoreksi </td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$caraPemenuhan</td>";
					 $Koloms.= "<td class='$cssclass'  align='left'>$keteranganKoreksi</td>";

				 }
						
						
				}
				$Koloms = array(
						 	array("Y", $Koloms),
						 );
				
			}
			elseif($this->jenisFormTerakhir == "KOREKSI PENGELOLA"){
				if($_REQUEST['cmbJenisRKBMD'] == 'PENGADAAN'){
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
				   	  if($p =='0'){
					 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
					 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
						$style = "style='font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($p!= '0' && $q=='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($p).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:5px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }elseif($f1 == '' && $q!='0'){
					 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
						$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
						$header = genNumber($q).". ".$getNamaPrgoram['nama'];
						$style = "style='margin-left:10px;font-weight:bold;'";
						$tampilHeader = "<td class='$cssclass' align='left' colspan='4'><span $style>".$header."</span></td>";
						$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						$Koloms.= $tampilHeader;
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='right'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
						$Koloms.= "<td class='$cssclass' align='left'></td>";
					 }else{
					 	
						 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
						 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
						 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
						 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
						 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
						 $getBarang = mysql_fetch_array(mysql_query($syntax));
						 $namaBarang = $getBarang['nm_barang'];
						 
						 $concat = $kodeSKPD.".".$kodeBarang;
						 $getKebutuhanMax = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
						 $kebutuhanMax = $getKebutuhanMax['jumlah'];
						 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' "));
						 $jumlahOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];
						 $kebutuhanRiil = $kebutuhanMax - $jumlahOptimal;
						 
						 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut='$this->urutTerakhir' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
						 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where no_urut='$this->urutTerakhir' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
						 }
						 
						 $satuan = $getBarang['satuan'];
						 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
						 $Koloms.= $tampilHeader;
						 $Koloms.= "<td class='$cssclass' align='left' ><span style='color:black;'>$namaBarang</span></td>";
						 
						 $nomorUrut2TahapSebelumnya = $this->urutTerakhir -1;
						 $getDataDuaTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrut2TahapSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan'  "));
						 $volume_barang = $getDataDuaTahapSebelumnya['volume_barang'];
						 $catatan = $getDataDuaTahapSebelumnya['catatan'];
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
						 
						 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanRiil,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($kebutuhanMax,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahOptimal,0,',','.')."</td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')." </td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$cara_pemenuhan</td>";
						 $tanggal_update = explode("-",$tanggal_update);
					 	 $tanggal_update = $tanggal_update[2]."-".$tanggal_update[1]."-".$tanggal_update[0];
						 $Koloms.= "<td class='$cssclass' align='left'>$user_update / $tanggal_update</td>";
						 $Koloms.= "<td class='$cssclass' align='right'>$jumlahKoreksi </td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$caraPemenuhan </td>";
						 $Koloms.= "<td class='$cssclass' align='left'>$keteranganKoreksi</td>";

						 
					 }
						
						
				}
				elseif($_REQUEST['cmbJenisRKBMD'] == 'PEMELIHARAAN'){  
					if($f1 == '' && $q =='0')$TampilCheckBox = "";
			   	  if($p =='0'){
				 	$kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where concat(c1,'.',c,'.',d,'.',e,'.',e1) = '$kodeSKPD'"));
				 	$header = $e1.". ".$getSubUnit['nm_skpd'];	
					$style = "style='font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($p!= '0' && $q=='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".'0';
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($p).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:5px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }elseif($f1 == '' && $q!='0'){
				 	$kodeProgram = $bk.".".$ck.".".'0'.".".$p.".".$q;
					$getNamaPrgoram = mysql_fetch_array(mysql_query("select * from ref_program where concat(bk,'.',ck,'.',dk,'.',p,'.',q) = '$kodeProgram'"));
					$header = genNumber($q).". ".$getNamaPrgoram['nama'];
					$style = "style='margin-left:10px;font-weight:bold;'";
					$tampilHeader = "<td class='$cssclass' align='left' colspan='9'><span $style>".$header."</span></td>";
					$Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					$Koloms.= $tampilHeader;
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='right'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
					$Koloms.= "<td class='$cssclass' align='left'></td>";
				 }else{
				 	 $nomorUrutSebelumnya = $this->nomorUrut -1;
					 $tampilHeader = "<td class='$cssclass' align='left' colspan='1'><span $style>".$header."</span></td>";
					 $kodeBarang =$isi['f1'].".".$isi['f2'].".".$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'] ;
					 $kodeSKPD = $isi['c1'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
					 $kodeKegiatan = $bk.".".$ck.".".$p.".".$q;
					 $syntax = "select * from ref_barang where concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'";
					 $getBarang = mysql_fetch_array(mysql_query($syntax));
					 $namaBarang = $getBarang['nm_barang'];
					 $getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id = '$id_jenis_pemeliharaan'"));
					 $jenisPemeliharaan = $getJenisPemeliharaan['jenis'];
					 $concat = $kodeSKPD.".".$kodeBarang;
					 $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='1' "));
					 $baik = $getKebutuhanOptimal['kebutuhanOptimal'];
					 $getKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='2' "));
					 $kurangBaik = $getKurangBaik['kebutuhanOptimal'];
					 $getRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi ='3' "));
					 $rusakBerat = $getRusakBerat['kebutuhanOptimal'];
					 $satuan = $getBarang['satuan'];
					 $customConcat = $kodeSKPD.".".$bk.".".$ck.".".$p.".".$q.".".$kodeBarang;
					 $getMinID = mysql_fetch_array(mysql_query("select min(urut) as min from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0'"));
					 $nomorUrut3TahapSebelumnya = $this->urutTerakhir -2;
					 $getSumVolumeBarang = mysql_fetch_array(mysql_query("select sum(volume_barang) as jumlah from view_rkbmd where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ='$customConcat' and id_jenis_pemeliharaan != '0' and tahun = '$this->tahun' and jenis_anggaran='$this->jenisAnggaran' and no_urut = '$nomorUrut3TahapSebelumnya'"));
					 $sumVolume = $getSumVolumeBarang['jumlah'];
					 $Koloms.= "<td class='$cssclass' align='center'>$no</td>";
					 $Koloms.= $tampilHeader;
					 if($urut == $getMinID['min']){
					 	$Koloms.= "<td class='$cssclass' align='center'>$kodeBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$namaBarang</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($sumVolume,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 	$Koloms.= "<td class='$cssclass' align='CENTER'>MILIK</td>";
						$Koloms.= "<td class='$cssclass' align='right'>".number_format($baik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($kurangBaik,0,',','.')."</td>";
					 	$Koloms.= "<td class='$cssclass' align='right'>".number_format($rusakBerat,0,',','.')."</td>";
					 }else{
					 	$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
						$Koloms.= "<td class='$cssclass' align='center'></td>";
					 }
					 $getDataKoreksi = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "));
					 $kondisiWarna = "red";
						 
						 if(mysql_num_rows(mysql_query("select * from view_rkbmd where no_urut='$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan ='$id_jenis_pemeliharaan' "))  > 0){
						 	$jumlahKoreksi = number_format($getDataKoreksi['volume_barang'],0,',','.');
						 	$align = "right";
							$tanggalKoreksi = explode("-",$getDataKoreksi['tanggal_update']);
							$tanggalKoreksi = $tanggalKoreksi[2]."-".$tanggalKoreksi[1]."-".$tanggalKoreksi[0];
							$caraPemenuhan = $getDataKoreksi['cara_pemenuhan'];
							$keteranganKoreksi =  $getDataKoreksi['user_update']."/".$tanggalKoreksi;
							$kondisiWarna = "black";
							 
						 }
					 
					 $Koloms.= "<td class='$cssclass' style='color:black ;' align='left'>$jenisPemeliharaan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$uraian_pemeliharaan</td>";
					 $getJumlahBarangPertama = mysql_fetch_array(mysql_query("select * from view_rkbmd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrut3TahapSebelumnya'"));
					 $jumlahBarangPertama = $getJumlahBarangPertama['volume_barang'];
					 $tanggal_update = explode("-",$tanggal_update);
					 $tanggal_update = $tanggal_update[2]."-".$tanggal_update[1]."-".$tanggal_update[0];
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($jumlahBarangPertama,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$satuan</td>";
					 $nomorUrut2TahapSebelumnya = $this->urutTerakhir -1;
					 $getDataTahapPengguna = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrut2TahapSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' "));
					 $catatan = $getDataTahapPengguna['catatan'];
					 $cara_pemenuhan = $getDataTahapPengguna['cara_pemenuhan'];
					 $user_update = $getDataTahapPengguna['user_update'];
					 $tanggal_update = $getDataTahapPengguna['tanggal_update'];
					 $Koloms.= "<td class='$cssclass' align='left'>$catatan</td>";
					 $Koloms.= "<td class='$cssclass' align='right'>".number_format($volume_barang,0,',','.')."</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$cara_pemenuhan</td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$user_update / $tanggal_update</td>";
					 
					 $Koloms.= "<td class='$cssclass' id='alignKoreksi' align='right'>$jumlahKoreksi  </td>";
					 $Koloms.= "<td class='$cssclass' align='left'>$caraPemenuhan </td>";
					 $Koloms.= "<td class='$cssclass'  align='left'>$keteranganKoreksi</td>";
				 }
						
						
				}
				$Koloms = array(
						 	array("Y", $Koloms),
						 );
				
			}
	   }
	 return $Koloms;
	 
	}
	 function Validasi($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 500;
	 $this->form_height = 120;
	 $this->form_caption = 'VALIDASI RKBMD';
	 $kode = $dt['c1'].".".$dt['c'].".".$dt['d'].".".$dt['e'].".".$dt['e1'].".".genNumber($dt['bk']).genNumber($dt['ck']).genNumber($dt['p']).".".$dt['q'].".".$dt['f1'].".".$dt['f2'].".".$dt['f'].".".$dt['g'].".".$dt['h'].".".$dt['i'].".".$dt['j'].".".$dt['id_jenis_pemeliharaan'];
	  if ($dt['status_validasi'] == '1') {
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
						'label'=>'KODE RKBMD',
						'labelWidth'=>100, 
						'value'=>$kode, 
						'type'=>'text',
						'param'=>"style='width:300px;' readonly"
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
						'param'=>"style='width:300px;' readonly"
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
	function genDaftarOpsi(){
	 global $Ref, $Main,  $HTTP_COOKIE_VARS;
	
	
	$fmKODE = $_REQUEST['fmKODE'];
	$fmBARANG = $_REQUEST['fmBARANG'];
	$arr = array(
			//array('selectAll','Semua'),
			array('selectfg','Kode Barang'),
			array('selectbarang','Nama Barang'),	
			);
		
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Barang'),
			     array('2','Nama Barang'),	
	 );	
	 
	$arrayJenisRKBMD = array(
						array("PENGADAAN","PENGADAAN"),
						array("PEMELIHARAAN","PEMELIHARAAN")
						
						);
						
	 $selectedRKBMD = $_REQUEST['cmbJenisRKBMD'];
	 /*if(empty($selectedRKBMD)){
	 	$selectedRKBMD = "PENGADAAN";
	 }*/
	 $cmbJenisRKBMD = cmbArray('cmbJenisRKBMD',$selectedRKBMD,$arrayJenisRKBMD,'-- JENIS RKBMD --','onchange=rkbmd.refreshList(true);');
				
	$TampilOpt = 
			//"<tr><td>".	
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajxVW("rkbmdSkpd") . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				
			</td></tr>			
			</table>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:10%;'> JENIS RKBMD </td>
		    <td style='width:2%;'> : </td>
			<td  style='width:80%;'> ".$cmbJenisRKBMD."</td>
			<td style='color:red;'> </td>
			</tr>
			<tr>
			<td style='width:10%;'> ANGGARAN </td>
		    <td style='width:2%;'> : </td>
			<td '> ".$this->jenisAnggaran." TAHUN ".$this->tahun."  <input type='hidden' name='idTahap' id='idTahap' value='$this->idTahap'><input type='hidden' name='tahun' id='tahun' value='$this->tahun' style='width:50px;' readonly> <input type='hidden' name='anggaran' id='anggaran' value='$this->jenisAnggaran' ></td>
			<td style='color:red;'> </td>
			</tr>
			<tr>
			<td style='width:10%;'> TAHAP TERAKHIR RKBMD </td>
		    <td style='width:2%;'> : </td>
			<td  style='width:80%;'> ".$this->namaTahapTerakhir."</td>
			<td style='color:red;'> </td>
			</tr>
			<tr>
			<td style='width:10%;'> </td>
		    <td style='width:2%;'> : </td>
			<td  style='width:80%;'> ".$this->masaTerakhir."</td>
			<td style='color:red;'> </td>
			</tr>
			<tr>
			<tr>
			<td style='width:10%;'> </td>
		    <td style='width:2%;'> : </td>
			<td  style='width:80%;'> ".$this->jenisFormTerakhir."</td>
			<td style='color:red;'> </td>
			</tr>
			<tr>
			<td style='width:13%;'> TAHAP SEKARANG </td>
		    <td style='width:2%;'> : </td>
			<td  style='width:80%;'> ".$this->currentTahap."</td>
			<td style='color:red;'> </td>
			</tr>
			</table>".
			"</div>"
			
			
			/*.
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp".
				"<input type='hidden' id='filterAkun' name='filterAkun' value='".$filterAkun."'>".
				"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>"*/;		
		return array('TampilOpt'=>$TampilOpt);
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
			$tergantung = "143";
		}
		return
		
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".			
		"<html>".
			$this->genHTMLHead().
			"<body >".
			/*"<div id='pageheader'>".$this->setPage_Header()."</div>".
			"<div id='pagecontent'>".$this->setPage_Content()."</div>".
			$Main->CopyRight.*/
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0'  height='100%' >".
				//header page -------------------		
				"<tr height='34'><td>".						
					//$this->setPage_Header($IconPage, $TitlePage).
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				//$this->setPage_HeaderOther().
				//Content ------------------------			
				//style='padding:0 8 0 8'
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
					
						//Form ------------------
						//$hidden.					
						//genSubTitle($TitleDaftar,$SubTitle_menu).						
						$this->setPage_Content().
						//$OtherInForm.
						
					$form2.//"</form>".
					"</div></div>".
				"</td></tr>".
				//$OtherContentPage.				
				//Footer ------------------------
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
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
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID  = $_COOKIE['coID']; 
		$c1   = $_REQUEST['rkbmdSkpdfmUrusan'];
		$c    = $_REQUEST['rkbmdSkpdfmSKPD'];
		$d    = $_REQUEST['rkbmdSkpdfmUNIT'];
		$e    = $_REQUEST['rkbmdSkpdfmSUBUNIT'];
		$e1   = $_REQUEST['rkbmdSkpdfmSEKSI'];	
		$fmKODE = $_REQUEST['fmKODE'];
		$fmBARANG = $_REQUEST['fmBARANG'];
		$cmbJenisRKBMD = $_REQUEST['cmbJenisRKBMD'];
		$arrKondisi = array();		
		
		if(!empty($c1) && $c1!="0" ){
			$arrKondisi[] = "c1 = $c1";
		}else{
			$c = "";
		}
		if(!empty($c ) && $c!="00"){
			$arrKondisi[] = "c = $c";
		}else{
			$d = "";
		}
		if(!empty($d) && $d!="00"){
			$arrKondisi[] = "d = $d";
		}else{
			$e = "";
		}
		if(!empty($e) && $e!="00"){
			$arrKondisi[] = "e = $e";
		}else{
			$e1 = "";
		}
		if(empty($cmbJenisRKBMD)){
			$arrKondisi[] = " error =;";
		}else{
			if($cmbJenisRKBMD == 'PENGADAAN'){
				$arrKondisi[] = "id_jenis_pemeliharaan = 0  and uraian_pemeliharaan != 'RKBMD PEMELIHARAAN' ";
			}elseif($cmbJenisRKBMD == 'PEMELIHARAAN'){
				$arrKondisi[] = "( id_jenis_pemeliharaan != 0 OR (id_jenis_pemeliharaan = 0 and f1 = '' and uraian_pemeliharaan != 'RKBMD PENGADAAN' ) ) ";
			}
		}
		if($this->jenisForm == "PENYUSUNAN"){
			
			$arrKondisi[] = "id_tahap = '$this->idTahap'";
		}elseif($this->jenisForm =="VALIDASI"){
				//copying data for validasi
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrutSebelumnya'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$jenisTahapSebelumnya = $getJenisTahapSebelumnya['jenis_form_modul'];
			$getAll = mysql_query("select * from view_rkbmd where f1 != '' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya'");
			while($rows = mysql_fetch_array($getAll)){
				foreach ($rows as $key => $value) { 
			 	 $$key = $value; 
				}
				if( $jenisTahapSebelumnya == "VALIDASI" && $status_validasi != '1' ){
				}else{
					$cekSubUnit = "select * from tabel_anggaran  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$c1' and c='$c' and d='$d' and e = '$e' and e1 = '$e1' and bk='0' and ck ='0' and p = '0' and q='0' and id_tahap = '$this->idTahap' ";
					if(mysql_num_rows(mysql_query($cekSubUnit)) == 0) {
						$data = array(
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										'c1' => $c1,
										'c' => $c,
										'd' => $d,
										'e' => $e,
										'e1' => $e1,
										'bk' => '0',
										'ck' => '0',
										'p' => '0',
										'q' => '0',
										'id_tahap' => $this->idTahap,
										'nama_modul' => $this->modul
										);
										 $query = VulnWalkerInsert("tabel_anggaran",$data);
										 mysql_query($query);
						}
					$cekProgram = "select * from tabel_anggaran  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$c1' and c='$c' and d='$d' and e = '$e' and e1 = '$e1' and bk ='$bk' and ck ='$ck' and p = '$p' and q='0' and id_tahap = '$this->idTahap'";	
					if(mysql_num_rows(mysql_query($cekProgram)) == 0){
						$data = array(
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										'c1' => $c1,
										'c' => $c,
										'd' => $d,
										'e' => $e,
										'e1' => $e1,
										'bk' => $bk,
										'ck' => $ck,
										'p' => $p,
										'q' => '0',
										'id_tahap' => $this->idTahap,
										'nama_modul' => $this->modul
										);
										 $query = VulnWalkerInsert("tabel_anggaran",$data);
										 mysql_query($query);
									}
					$cekKegiatanPengadaan = "select * from tabel_anggaran  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$c1' and c='$c' and d='$d' and e = '$e' and e1 = '$e1' and bk ='$bk' and ck ='$ck' and p = '$p' and q='$q' and uraian_pemeliharaan = 'RKBMD PENGADAAN' and id_tahap = '$this->idTahap'";	
					if(mysql_num_rows(mysql_query($cekKegiatanPengadaan)) == 0){
						$data = array(
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										'c1' => $c1,
										'c' => $c,
										'd' => $d,
										'e' => $e,
										'e1' => $e1,
										'bk' => $bk,
										'ck' => $ck,
										'p' => $p,
										'q' => $q,
										'uraian_pemeliharaan' => 'RKBMD PENGADAAN',
										'id_tahap' => $this->idTahap,
										'nama_modul' => $this->modul
										);
										 $query = VulnWalkerInsert("tabel_anggaran",$data);
										 mysql_query($query);
									}
					$cekKegiatanPemeliharaan = "select * from tabel_anggaran  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$c1' and c='$c' and d='$d' and e = '$e' and e1 = '$e1' and bk ='$bk' and ck ='$ck' and p = '$p' and q='$q' and uraian_pemeliharaan = 'RKBMD PEMELIHARAAN' and id_tahap = '$this->idTahap'";	
					if(mysql_num_rows(mysql_query($cekKegiatanPemeliharaan)) == 0){
						$data = array(
										'jenis_anggaran' => $this->jenisAnggaran,
										'tahun' => $this->tahun,
										'c1' => $c1,
										'c' => $c,
										'd' => $d,
										'e' => $e,
										'e1' => $e1,
										'bk' => $bk,
										'ck' => $ck,
										'p' => $p,
										'q' => $q,
										'uraian_pemeliharaan' => 'RKBMD PEMELIHARAAN',
										'id_tahap' => $this->idTahap,
										'nama_modul' => $this->modul
										);
										 $query = VulnWalkerInsert("tabel_anggaran",$data);
										 mysql_query($query);
									}
					$data = array( "status_validasi" => $status_validasi,
							 	   'user_validasi' => $_COOKIE['coID'],
							 		'tanggal_validasi' => date("Y-m-d"),
									'id_tahap' => $this->idTahap
							 	  );
									 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$id_anggaran'");
									 mysql_query($query);
									
									
				}
				
			}
			$arrKondisi[] =  "id_tahap = '$this->idTahap'";
			//copying data for validasi
		}elseif($this->jenisForm == "KOREKSI PENGGUNA"){
			$nomorUrutSebelumnya = $this->nomorUrut -1;
			$getJenisTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrutSebelumnya'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$jenisTahapSebelumnya = $getJenisTahapSebelumnya['jenis_form_modul'];
			$getAll = mysql_query("select * from view_rkbmd where f1 != '' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya'");
			while($rows = mysql_fetch_array($getAll)){
				foreach ($rows as $key => $value) { 
			 	 $$key = $value; 
				}
				if( $jenisTahapSebelumnya == "VALIDASI" && $status_validasi != '1' ){
					$arrKondisi[] = "id_anggaran !='$id_anggaran'";
				}
			
			}
			
			$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya'";
		}elseif($this->jenisForm == "KOREKSI PENGELOLA"){
			$nomorUrutSebelumnya = $this->nomorUrut -1;
			$getJenisTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_rkbmd where no_urut = '$nomorUrutSebelumnya'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$jenisTahapSebelumnya = $getJenisTahapSebelumnya['jenis_form_modul'];
			
			
			$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya'";
		}else{
			
			$arrKondisi[] =  "no_urut = '$this->urutTerakhir'";
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
	if($cmbJenisRKBMD == "PENGADAAN"){
		$arrOrders[] = "urut, concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j) ASC " ;
	}else{
		$arrOrders[] = "c1, c, d, e, e1,bk,ck,p,q ,  concat(f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j), id_jenis_pemeliharaan ASC " ;
	}
		
			

			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$rkbmd = new rkbmdObj();
$arrayResult = VulnWalkerTahap($rkbmd->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$rkbmd->jenisForm = $jenisForm;
$rkbmd->nomorUrut = $nomorUrut;
$rkbmd->tahun = $tahun;
$rkbmd->jenisAnggaran = $jenisAnggaran;
$rkbmd->idTahap = $idTahap;

if(empty($rkbmd->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran) as max from view_rkbmd "));
	$maxID = $get1['max'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_anggaran = '$maxID' "));
	$rkbmd->tahun = $get2['tahun'];
	$rkbmd->jenisAnggaran = $get2['jenis_anggaran'];
	$rkbmd->urutTerakhir = $get2['no_urut'];
	$rkbmd->tahapTerakhir = $get2['id_tahap'];
	$rkbmd->jenisFormTerakhir = $get2['jenis_form_modul'];
	$rkbmd->urutSebelumnya = $rkbmd->urutTerakhir - 1;
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$rkbmd->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$rkbmd->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap();
	$rkbmd->currentTahap = $arrayHasil['currentTahap'];
	
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$rkbmd->idTahap'"));
	$rkbmd->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$rkbmd->idTahap'"));
	$rkbmd->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$rkbmd->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$rkbmd->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}

?>