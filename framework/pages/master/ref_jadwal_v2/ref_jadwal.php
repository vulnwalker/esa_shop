<?php

class ref_jadwalObj  extends DaftarObj2{	
	var $Prefix = 'ref_jadwal';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_jadwal'; //bonus
	var $TblName_Hapus = 'ref_jadwal';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id_tahap');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'REFERENSI JADWAL';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='ref_jadwal.xls';
	var $namaModulCetak='REFERENSI JADWAL';
	var $Cetak_Judul = 'REFERENSI JADWAL';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ref_jadwalForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'REFERENSI JADWAL';
	}

	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru ", 'Baru ')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content='';
	 $json = TRUE;
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];

	 foreach ($_REQUEST as $key => $value) { 
		  $$key = $value; 
	 } 
	
	if(empty($waktu_aktif))$err ="ISI TANGGAL MULAI";
	if(empty($waktu_pasif))$err ="ISI TANGGAL SELESAI";
	
	$waktu_aktif = explode("-",$waktu_aktif);
	$waktu_aktif = $waktu_aktif[2]."-".$waktu_aktif[1]."-".$waktu_aktif[0];
	$waktu_pasif = explode("-",$waktu_pasif);
	$waktu_pasif = $waktu_pasif[2]."-".$waktu_pasif[1]."-".$waktu_pasif[0];
	
	$angkaMulai = str_replace("-","",$waktu_aktif);
	$angkaSelesai = str_replace("-","",$waktu_pasif);
	if($angkaMulai > $angkaSelesai)$err="TANGGAL SALAH";
	if(empty($tahun))$err ="ISI TAHUN";
	if(empty($idModul))$err = "PILIH MODUL";
	if(empty($anggaran))$err = "PILIH ANGGARAN";
	if(empty($tahun))$err = "ISI TAHUN";
	if(empty($jamM) || empty($jamA) || empty($menitM) || empty($menitA))$err = "ISI WAKTU";
	if(empty($jenisForm))$err = "PILIH JENIS TAHAP";
	if(empty($nama_tahap))$err = "ISI NAMA TAHAP";
	$jamMulai = $jamM.":".$menitM;
	$jamSelesai = $jamA.":".$menitA;
	
	
	$user = $_COOKIE['coID'];
	
	
	 if( $err=='' && $nama_tahap =='' ) $err= 'NAMA TAHAP ANGGARAN Belum Di Isi !!';
	 
	if($fmST == 0){
	 $angkaMulaiLengkap = $angkaMulai.str_replace(":","",$jamMulai);
	 $angkaSelesaiLengkap = $angkaSelesai.str_replace(":","",$jamSelesai);
	 $penyocokanTanggal = mysql_num_rows(mysql_query("select * from ref_jadwal where (concat(REPLACE(tanggal_mulai,'-',''), REPLACE(jam_mulai,':','') ) < '$angkaMulaiLengkap' and  concat(REPLACE(tanggal_selesai,'-',''), REPLACE(jam_selesai,':','')) > '$angkaMulaiLengkap' and anggaran ='$anggaran' and tahun = '$tahun' ) or (concat(REPLACE(tanggal_mulai,'-',''), REPLACE(jam_mulai,':','') ) < '$angkaSelesaiLengkap' and  concat(REPLACE(tanggal_selesai,'-',''), REPLACE(jam_selesai,':','')) > '$angkaSelesaiLengkap' and anggaran ='$anggaran' and tahun = '$tahun' )"));
	if($penyocokanTanggal > 0){
		$err ="TANGGAL SALAH";
	}
				if($err==''){
					$statusJadwal= 'NORMAL';
					if($status == 'AKTIF'){
						$nomor_urut = 1;
						$queryNoUrut = "select * from ref_jadwal where status_penyusunan = 'AKTIF'  and anggaran ='$anggaran' and  tahun = '$tahun' order by id_tahap asc";
						$execute = mysql_query($queryNoUrut);
						while ($rows = mysql_fetch_array($execute)){
							$nomor_urut = $rows['no_urut'] + 1;
						}
					}
					
					
					$data = array(
								   'anggaran' => $anggaran,
								   'tahun' => $tahun,
								   'nama_tahap' => $nama_tahap,
								   'id_modul' => $idModul,
								   'no_urut' => $nomor_urut,
								   'jenis_form_modul' =>$jenisForm,
								   'tanggal_mulai' => $waktu_aktif,
								   'tanggal_selesai' => $waktu_pasif,
								   'jam_mulai' => $jamMulai,
								   'jam_selesai' => $jamSelesai,
								   'status_penyusunan' => $status,
								   'status_jadwal' => $statusJadwal,
								   'tgl_update'=> date("Y-m-d"),
								   'user' => $user
								  );
					$query = VulnWalkerInsert("ref_jadwal",$data);
					mysql_query($query);
					$getIDTahap = mysql_fetch_array(mysql_query("select max(id_tahap) as ID_TAHAP from ref_jadwal"));
					
					$dataHistori = array( 'tanggal_mulai' => $waktu_aktif,
										  'tanggal_selesai' => $waktu_pasif,
										  'tanggal_update' => date("Y-m-d"),
										  'user' => $user,
										  'id_tahap' =>  $getIDTahap['ID_TAHAP']
										);
					mysql_query(VulnWalkerInsert('histori_tahap',$dataHistori));
					
					$dataDefault = array("tahun" => $tahun,
								 		 "jenis_anggaran" =>$anggaran,
										 "user" => $user 
										);
					 $executePertamaDefault = mysql_query(VulnWalkerInsert("default_tahap",$dataDefault));
					 if($executePertamaDefault){
					 }else{
					 	$dataDefault = array("tahun" => $tahun,
								 		 "jenis_anggaran" =>$anggaran
										);
						mysql_query(VulnWalkerUpdate("default_tahap",$dataDefault," user='$user'"));
					 }
					 $nomor_urut = 1;
								$execute = mysql_query("select * from ref_jadwal where status_penyusunan ='AKTIF' and anggaran='$anggaran' and  tahun = '$tahun' ORDER BY id_tahap");
								while($rows = mysql_fetch_array($execute)){
									$dataUpdateAll = array("no_urut" => $nomor_urut);
									$currentIdTahap = $rows['id_tahap'];
									mysql_query(VulnWalkerUpdate('ref_jadwal',$dataUpdateAll," id_tahap = '$currentIdTahap'") );
								   $nomor_urut = $nomor_urut + 1;
								}
					

					$content .= $query;
				}
			}else{		
	 $angkaMulaiLengkap = $angkaMulai.str_replace(":","",$jamMulai);
	 $angkaSelesaiLengkap = $angkaSelesai.str_replace(":","",$jamSelesai);
	 $penyocokanTanggal = mysql_num_rows(mysql_query("select * from ref_jadwal where (concat(REPLACE(tanggal_mulai,'-',''), REPLACE(jam_mulai,':','') ) < '$angkaMulaiLengkap' and  concat(REPLACE(tanggal_selesai,'-',''), REPLACE(jam_selesai,':','')) > '$angkaMulaiLengkap' and anggaran ='$anggaran' and tahun = '$tahun' and id_tahap != '$idplh' ) or (concat(REPLACE(tanggal_mulai,'-',''), REPLACE(jam_mulai,':','') ) < '$angkaSelesaiLengkap' and  concat(REPLACE(tanggal_selesai,'-',''), REPLACE(jam_selesai,':','')) > '$angkaSelesaiLengkap' and anggaran ='$anggaran' and tahun = '$tahun' and id_tahap != '$idplh' )"));
	 
	 
	if($penyocokanTanggal > 0){
		$err ="TANGGAL SALAH";
	}
				if($err==''){
								$hitungHistori = mysql_num_rows(mysql_query("select * from histori_tahap where id_tahap = '$idplh'"));
					
				
							if($status =='AKTIF'){
								
						    $data = array(
								   'anggaran' => $anggaran,
								   'tahun' => $tahun,
								   'nama_tahap' => $nama_tahap,
								   'id_modul' => $idModul,
								   'jenis_form_modul' =>$jenisForm,
								   'tanggal_mulai' => $waktu_aktif,
								   'tanggal_selesai' => $waktu_pasif,
								   'jam_mulai' => $jamMulai,
								   'jam_selesai' => $jamSelesai,
								   'status_penyusunan' => $status,
								   'status_jadwal' => 'PERUBAHAN KE '.$hitungHistori,
								   'tgl_update'=> date("Y-m-d"),
								   'user' => $user
								  );
									$query = VulnWalkerUpdate("ref_jadwal",$data," id_tahap = '$idplh'");
									mysql_query($query);

							}else{
								
								  $data = array(
								   'anggaran' => $anggaran,
								   'tahun' => $tahun,
								   'nama_tahap' => $nama_tahap,
								   'id_modul' => $idModul,
								   'jenis_form_modul' =>$jenisForm,
								   'tanggal_mulai' => $waktu_aktif,
								   'tanggal_selesai' => $waktu_pasif,
								   'jam_mulai' => $jamMulai,
								   'no_urut' => '',
								   'jam_selesai' => $jamSelesai,
								   'status_penyusunan' => $status,
								   'status_jadwal' => 'PERUBAHAN KE '.$hitungHistori,
								   'tgl_update'=> date("Y-m-d"),
								   'user' => $user
								  );
									$query = VulnWalkerUpdate("ref_jadwal",$data," id_tahap = '$idplh'");
									mysql_query($query);
								
							}
							
						    	$nomor_urut = 1;
								$execute = mysql_query("select * from ref_jadwal where status_penyusunan ='AKTIF' and anggaran='$anggaran' and  tahun = '$tahun' ORDER BY id_tahap");
								while($rows = mysql_fetch_array($execute)){
									$dataUpdateAll = array("no_urut" => $nomor_urut);
									$currentIdTahap = $rows['id_tahap'];
									mysql_query(VulnWalkerUpdate('ref_jadwal',$dataUpdateAll," id_tahap = '$currentIdTahap'") );
								   $nomor_urut = $nomor_urut + 1;
								}
							$dataHistori = array( 'tanggal_mulai' => $waktu_aktif,
										  'tanggal_selesai' => $waktu_pasif,
										  'tanggal_update' => date("Y-m-d"),
										  'user' => $user,
										  'id_tahap' =>  $idplh
										);
						mysql_query(VulnWalkerInsert('histori_tahap',$dataHistori));
						$dataDefault = array("tahun" => $tahun,
								 		 "jenis_anggaran" =>$anggaran,
										 "user" => $user 
										);
					 $executePertamaDefault = mysql_query(VulnWalkerInsert("default_tahap",$dataDefault));
					 if($executePertamaDefault){
					 }else{
					 	$dataDefault = array("tahun" => $tahun,
								 		 "jenis_anggaran" =>$anggaran
										);
						mysql_query(VulnWalkerUpdate("default_tahap",$dataDefault," user='$user'"));
					 }
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
			"<script type='text/javascript' src='js/master/ref_jadwal/ref_jadwal.js' language='JavaScript' ></script> 
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
			$aqry = "SELECT * FROM  ref_jadwal WHERE id_tahap='".$this->form_idplh."' "; $cek.=$aqry;
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
	 $this->form_height = 400;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$username = $_COOKIE['coID'];
		$cekRows = mysql_num_rows(mysql_query("select * from default_tahap where user = '$username'"));
		if($cekRows > 0){
			$getDefault = mysql_fetch_array(mysql_query("select * from default_tahap where user = '$username'"));
			$tahun = $getDefault['tahun'];
			$anggaran = $getDefault['jenis_anggaran'];
		}
		$status = "PASIF";
		$getMaxNomorAku = mysql_fetch_array(mysql_query("select max(id_tahap) max from ref_jadwal"));
		$maxNomorAku = $getMaxNomorAku['max'];
		$getNomorAku = mysql_fetch_array(mysql_query("select * from ref_jadwal where id_tahap = '$maxNomorAku'"));
		$nomor_aku = $getNomorAku['no_urut'];
		$waktu_aktif = explode("-",$getNomorAku['tanggal_selesai']);
		$waktu_aktif = $waktu_aktif[2]."-".$waktu_aktif[1]."-".$waktu_aktif[0];
		$jamMulai = explode(':',$getNomorAku['jam_selesai']);
		$jamM = $jamMulai[0]; 
		$menitM = $jamMulai[1] + 1;
	  }else{
		$this->form_caption = 'Edit';			
		$nama_tahap = $dt['nama_tahap']	;
		$modul = $dt['modul'];	
		$waktu_aktif = explode("-",$dt['tanggal_mulai']);
		$waktu_aktif = $waktu_aktif[2]."-".$waktu_aktif[1]."-".$waktu_aktif[0];
		$waktu_pasif = explode("-",$dt['tanggal_selesai']);
		$waktu_pasif = $waktu_pasif[2]."-".$waktu_pasif[1]."-".$waktu_pasif[0];
		$status = $dt['status'];	
		$tahun = $dt['tahun'];
		$anggaran = $dt['anggaran'];
		$jenisForm = $dt['jenis_form_modul'];
		$jamMulai = explode(':',$dt['jam_mulai']);
		$jamM = $jamMulai[0]; 
		$menitM = $jamMulai[1];
		$jamSelesai = explode(':',$dt['jam_selesai']);
		$jamA = $jamSelesai[0]; 
		$menitA = $jamSelesai[1]; 
		$status = $dt['status_penyusunan'];
		$idModul = $dt['id_modul'];
		$nomor_aku = $dt['no_urut'] - 1;
		$arrayIdModule = explode(';',$dt['id_modul']);
		$arrayJenisForm = "";
		$namaModul = "";
		 for ($i = 0 ; $i < sizeof($arrayIdModule); $i ++){
		 	$getModul = mysql_fetch_array(mysql_query("select * from ref_modul where id_modul = '$arrayIdModule[$i]' "));
			if($arrayIdModule[$i] == "" || $i == (sizeof($arrayIdModule) - 2) ){
				$pemisah = "";
			}else{
				$pemisah = ", ";
			}
			
			$namaModul .= $getModul['nama_modul'].$pemisah; 		
		 }
		
	  }
	    //ambil data trefditeruskan
	  	$query = "select *from " ;$cek .=$query;
	  	$res = mysql_query($query);
		   $jamMulai = "<input type ='text' class = 'clockpicker'>";
		   $arrayJam = array(array('00','00'),
		   					 array('01','01'),
							 array('02','02'),
							 array('03','03'),
							 array('04','04'),
							 array('05','05'),
							 array('06','06'),
							 array('07','07'),
							 array('08','08'),
							 array('09','09'),
							 array('10','10'),
							 array('11','11'),
							 array('12','12'),
							 array('13','13'),
							 array('14','14'),
							 array('15','15'),
							 array('16','16'),
							 array('17','17'),
							 array('18','18'),
							 array('19','19'),
							 array('20','20'),
							 array('21','21'),
							 array('22','22'),
							 array('23','23'));
			$arrayMenit = array();			 
			for ($i = 0; $i <= 59 ; $i ++){
				if($i < 10){
					$menit = "0".$i;
				}else{
					$menit = $i;
				}
				array_push($arrayMenit, array($menit,$menit));
			}
							 
	$cmbJamMulai = cmbArray('jamM',$jamM,$arrayJam, '-- JAM MULAI --');
	$cmbMenitMulai = cmbArray('menitM',$menitM,$arrayMenit,'-- MENIT MULAI --');
	$cmbJamAkhir = cmbArray('jamA',$jamA,$arrayJam, '-- JAM SELESAI --');
	$cmbMenitAkhir = cmbArray('menitA',$menitA,$arrayMenit,'-- MENIT SELESAI --');
	$jamMulai = $cmbJamMulai." : ".$cmbMenitMulai;
	$jamSelesai = $cmbJamAkhir." : ".$cmbMenitAkhir;
			
	$mulai = "<input type ='text' id='waktu_aktif' name='waktu_aktif' value='$waktu_aktif' class='datepicker'> ";
	$selesai = "<input type ='text' id='waktu_pasif' name='waktu_pasif' value='$waktu_pasif' class='datepicker'> ";
	$tanggal = $mulai." S/D ".$selesai;
   
   $arrayStatus = array(array('AKTIF' , 'AKTIF'),
					  	array('TIDAK AKTIF' , 'TIDAK AKTIF'));
   $cmbStatus = cmbArray('status',$status,$arrayStatus,'-- STATUS --');
   $arrayAnggaran = array(	array('MURNI','MURNI'),
							array("PERUBAHAN","PERUBAHAN")
							) ;
							
	if($namaModul =="RKBMD, "){
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI PENGGUNA' , 'KOREKSI PENGGUNA'),
							 			    array('KOREKSI PENGELOLA' , 'KOREKSI PENGELOLA'),
							                array('READ' , 'READ'));
			}else{
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI' , 'KOREKSI'),
							                array('READ' , 'READ'));
			}
								
	$cmbJenisForm = cmbArray('jenisForm',$jenisForm,$arrayJenisForm,'-- JENIS TAHAP --');
	
  	$cmbAnggaran = cmbArray('anggaran',$anggaran,$arrayAnggaran,'-- ANGGARAN --'); 
    $findModul = "<input type='text' id ='namaModul' name = 'namaModul' value='$namaModul' style='width : 300px;' readonly > <input type='hidden' name='idModul' id='idModul' value='$idModul'>  <button type='button' onclick='ref_jadwal.CariModul()'>CARI </button>";
   
	$maxNoUrut = $nomor_aku ;
	$getDataMax = mysql_fetch_array(mysql_query("select * from ref_jadwal where no_urut = '$maxNoUrut' " ));
	$arrayTanggalMulaiBefore = explode("-",$getDataMax['tanggal_mulai']);
	$mulaiBefore =  $arrayTanggalMulaiBefore[2]."-".$arrayTanggalMulaiBefore[1]."-".$arrayTanggalMulaiBefore[0]. "  JAM : ".$getDataMax['jam_mulai'];
	$arrayTanggalSelesaiBefore = explode("-",$getDataMax['tanggal_selesai']);
	$mulaiAfter =  $arrayTanggalSelesaiBefore[2]."-".$arrayTanggalSelesaiBefore[1]."-".$arrayTanggalSelesaiBefore[0]. "  JAM : ".$getDataMax['jam_selesai'];
	$maxNoUrutSesudahnya = $maxNoUrut + 2;
$getDataMaxSetelah = mysql_fetch_array(mysql_query("select * from ref_jadwal where no_urut = '$maxNoUrutSesudahnya' " ));
	$arrayTanggalMulaiSetelah = explode("-",$getDataMaxSetelah['tanggal_mulai']);
	$mulaiSetelahnya =  $arrayTanggalMulaiSetelah[2]."-".$arrayTanggalMulaiSetelah[1]."-".$arrayTanggalMulaiSetelah[0]. "  JAM : ".$getDataMaxSetelah['jam_mulai'];
	$arrayTanggalSelesaiSetelah = explode("-",$getDataMaxSetelah['tanggal_selesai']);
	$selesaiSetelahnya=  $arrayTanggalSelesaiSetelah[2]."-".$arrayTanggalSelesaiSetelah[1]."-".$arrayTanggalSelesaiSetelah[0]. "  JAM : ".$getDataMaxSetelah['jam_selesai'];
	
	
	
	 //items ----------------------
	  $this->form_fields = array(
	  		'tahun' => array( 
						'label'=>'TAHUN',
						'labelWidth'=>100, 
						'value'=>$tahun, 
						'type'=>'text',
						'param'=>"style='width:100px;' placeholder = 'TAHUN'"
						 ),
	  		'anggaran' => array( 
						'label'=>'ANGGARAN',
						'labelWidth'=>100, 
						'value'=>$cmbAnggaran
						 ),
			'modul' => array( 
						'label'=>'MODUL',
						'labelWidth'=>100, 
						'value'=>$findModul
						 ),	
			'jenisForm' => array( 
						'label'=>'JENIS TAHAP',
						'labelWidth'=>100, 
						'value'=>$cmbJenisForm
						 ),		
			'nama_tahap' => array( 
						'label'=>'NAMA TAHAP',
						'labelWidth'=>100, 
						'value'=>$nama_tahap, 
						'type'=>'text',
						'param'=>"style='width:300px;' placeholder = 'NAMA TAHAP'"
						 ),
			'1' => array( 
						'label'=>'WAKTU MULAI',
						'labelWidth'=>100, 
						'value'=>$mulai, 
						 ),
			'1adsadasdass' => array( 
						'label'=>'',
						'labelWidth'=>100, 
						'value'=>$jamMulai, 
						 ),
			'2' => array( 
						'label'=>'WAKTU SELESAI',
						'labelWidth'=>100, 
						'value'=>$selesai, 
						 ),
			'1adsddass' => array( 
						'label'=>'',
						'labelWidth'=>100, 
						'value'=>$jamSelesai, 
						 ),
			'ffd' => array( 
						'label'=>'STATUS',
						'labelWidth'=>100, 
						'value'=>$cmbStatus."</td></tr></table><table><tr><td><br>TAHAP SEBELUMNYA : </td>  <td><br> $mulaiBefore  s/d  $mulaiAfter</td></tr>
						<tr><td><br>TAHAP SETELAHNYA : </td>  <td><br> $mulaiSetelahnya s/d  $selesaiSetelahnya</td></tr>"
						 )
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' > &nbsp ".
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
  	   $Checkbox		
	   <th class='th01' width='100' rowspan='2' colspan='1'>ANGGARAN </th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>TAHUN</th>
	   	   <th class='th01' width='100' rowspan='2' colspan='1'>NO URUT</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>JENIS TAHAP</th>
	   <th class='th01' width='900' rowspan='2' colspan='1'>TAHAP</th>

	   <th class='th01' width='300' rowspan='2' colspan='1'>AKTIFASI MODUL</th>
	   
	   <th class='th02' width='1000' rowspan='1' colspan='5'>REFERENSI JADWAL PELAKSANAAN</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>STATUS REFERENSI JADWAL</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>STATUS TAHAP</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>TANGGAL UPDATE</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>USER</th>
	   </tr>
	   <tr>
	   <th class='th01' width='150' > TANGGAL</th>
	   <th class='th01' width='150'> JAM</th>
	   <th class='th01' width='50'> S/D</th>
	   <th class='th01' width='150'> TANGGAL</th>
	   <th class='th01' width='150'> JAM</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['anggaran']);
	 $Koloms[] = array('align="center"',$isi['tahun']);
	 	 $Koloms[] = array('align="right"',$isi['no_urut']);
	 $Koloms[] = array('align="left"',$isi['jenis_form_modul']);
	 $Koloms[] = array('align="left"',$isi['nama_tahap']);

	 $arrayIdModule = explode(';',$isi['id_modul']);
	 $arrayNamaModule = array();
	 for ($i = 0 ; $i < sizeof($arrayIdModule); $i ++){
	 	$getModul = mysql_fetch_array(mysql_query("select * from ref_modul where id_modul = '$arrayIdModule[$i]' "));
		
		$arrayNamaModule[] = $getModul['nama_modul'];
	 }
	 
	 $namaModul = join($arrayNamaModule,", ");
	 $Koloms[] = array('align="left"',$namaModul);

	 $waktu_aktif = explode('-',$isi['tanggal_mulai']) ;
	 $waktu_aktif = $waktu_aktif[2]."-".$waktu_aktif[1]."-".$waktu_aktif[0];
	 $jam_mulai = $isi['jam_mulai'];
	 $jam_selesai = $isi['jam_selesai'];
	 $waktu_pasif = explode('-',$isi['tanggal_selesai']) ;
	 $waktu_pasif = $waktu_pasif[2]."-".$waktu_pasif[1]."-".$waktu_pasif[0];
	 $Koloms[] = array('align="center"',$waktu_aktif);
	 $Koloms[] = array('align="center"',$jam_mulai);
	 $Koloms[] = array('align="center"','S/D');
	 $Koloms[] = array('align="center"',$waktu_pasif);
	 $Koloms[] = array('align="center"',$jam_selesai);
	 $ID_TAHAP = $isi['id_tahap'];
	 $Koloms[] = array('align="left"',"<a onclick=ref_jadwal.histori('$ID_TAHAP') style='cursor : pointer;'>".$isi['status_jadwal']."</a>");
	 $Koloms[] = array('align="left"',$isi['status_penyusunan']);
	 $tanggalUpdate = explode("-",$isi['tgl_update']);
	 $tanggalUpdate = $tanggalUpdate[2]."-".$tanggalUpdate[1]."-".$tanggalUpdate[0];
	 $Koloms[] = array('align="center"',$tanggalUpdate);
	 $Koloms[] = array('align="center"',$isi['user']);

	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
		
	 //data order ------------------------------
	 
	  $arr = array(
						array('nama_tahap','NAMA TAHAP'),		
						array('waktu_aktif','WAKTU AKTIF'),	
						array('waktu_pasif','WAKTU PASIF'),
						array('modul','MODUL'),
						array('status','STATUS')			
			);
	 
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
			<td style='width:100px;'> ".cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','')."  </td><td><input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>  &nbsp <input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'></td>
			 </tr>
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:100px;'> ".cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."  </td>
			<td style='width:200px;' ><input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'> menurun &nbsp Jumlah Data : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'>  </td><td align='left' ><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
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
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn


		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

			
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_tahap': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;						 
			case 'waktu_aktif': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
			case 'waktu_pasif': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
			case 'modul': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
			case 'status': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;			
		}
		
		
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case 'nama_tahap': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'waktu_aktif': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'waktu_pasif': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'modul': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'status': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
		}	
		if(empty($fmPILCARI))$arrOrders[] = " id_tahap $Asc1";
		/*$arrOrders[] = " no_urut $Asc1 " ;*/
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
	
	function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		
		
			if($err=='' ){
					
					$queryGet = "select * FROM $this->TblName_Hapus WHERE id_tahap='".$ids[$i]."'";
					$getInfo = mysql_fetch_array(mysql_query($queryGet));
					$qy = "DELETE FROM $this->TblName_Hapus WHERE id_tahap='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					$anggaran = $getInfo['anggaran'];
					$tahun = $getInfo['tahun'];
					$nomor_urut = 1;
					$execute = mysql_query("select * from ref_jadwal where status_penyusunan ='AKTIF' and anggaran='$anggaran' and  tahun = '$tahun' ORDER BY id_tahap");
					while($rows = mysql_fetch_array($execute)){
					      $dataUpdateAll = array("no_urut" => $nomor_urut);
						  $currentIdTahap = $rows['id_tahap'];
						  mysql_query(VulnWalkerUpdate('ref_jadwal',$dataUpdateAll," id_tahap = '$currentIdTahap'") );
						  $nomor_urut = $nomor_urut + 1;
					}
					$cek .= $queryGet;	
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
}
$ref_jadwal = new ref_jadwalObj();
?>