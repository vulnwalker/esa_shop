<?php
class renja_ins_v3Obj  extends DaftarObj2{	
	var $Prefix = 'renja_ins_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_penerimaan_barang'; //bonus
	var $TblName_Hapus = 't_penerimaan_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'RENJA';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='renja.xls';
	var $namaModulCetak='PERENCANAAN';
	var $Cetak_Judul = 'RENJA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'renja_ins_v3Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $modul = "RENJA";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	
	function setTitle(){

		return 'RENCANA KERJA SKPD TAHUN '.$this->tahun ;
	}
	
	function setMenuEdit(){
		return "";

	}
	
	function setMenuView(){
		return "";
	}
	
	
	
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main,$HTTP_COOKIE_VARS;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 	  
	  switch($tipe){	
			
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		
		case 'hapus':{				
			$content = array("test" => 'test');										
		break;
		}
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'CekKosong':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			$query = "select * from view_renja_v3 where c1 = '$c1' and c ='$c' and d ='$d' and p !='00' and q != '00' and tahun = '$this->tahun'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from renja where c1 = '$c1' and c ='$c' and d ='$d' and tahun = '$this->tahun' ");
			}
			
			
			$query = "select * from view_renja_v3 where c1 = '$c1' and c ='$c' and d !='00' and p !='00' and q != '00' and tahun = '$this->tahun'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from renja where c1 = '$c1' and c ='$c' and d ='00' and tahun = '$this->tahun'");
				
			}
			
			$query = "select * from view_renja_v3 where  c1 = '$c1' and c !='00' and d !='00' and p !='00' and q != '00' and tahun = '$this->tahun'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from renja where c1 = '$c1' and c ='00' and d ='00' and tahun = '$this->tahun' ");
				
			}
			
			
			
														
		break;
		}		
		case 'Simpan':{
		
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			} 
	
			
			
			if(empty($cmbKegiatan)){
				$err = "Pilih Kegiatan";
			}elseif(empty($cmbSubUnitForm)){
				$err = "Pilih Unit Pelaksana";
			}
			elseif(empty($tanggalMulai)){
				$err = "Isi Tanggal Mulai";
			}
			elseif(empty($tanggalSelesai)){
				$err = "Isi Tanggal Selesai";
			}
/*			elseif(empty($paguIndikatif)){
				$err = "Isi Pagu Indikatif";
			}*/
			elseif(empty($cmbSumberDana)){
				$err = "Pilih Sumber Dana ";
			}
			elseif(empty($sasaranKegiatan) ){
				$err = "Isi Sasaran Kelompok Kegiatan";
			}
			
			elseif(empty($bk) && $bk != '0' ){
				$err = "Pilih Urusan Program";
			}
			$tanggalMulai = explode("-",$tanggalMulai);
			$tanggalMulai = $tanggalMulai[2]."-".$tanggalMulai[1]."-".$tanggalMulai[0];
			$tanggalSelesai = explode("-",$tanggalSelesai);
			$tanggalSelesai = $tanggalSelesai[2]."-".$tanggalSelesai[1]."-".$tanggalSelesai[0];
			if(str_replace("-","",$tanggalMulai) >  str_replace("-","",$tanggalSelesai)  ){
				$err = "TANGGAL SALAH";
			}
			
		if(empty($CPTU) || empty($CPTK) || empty($MTU) || empty($MTK) || empty($KTU) || empty($KTK) || empty($HTU) || empty($HTK) ){
				$err = "Lengkapi Keterangan";				
			}
		if($this->jenisForm != "PENYUSUNAN"){
			$err = "TAHAP PENYUSUNAN TELAH HABIS";
			
		}
			if($err == ''){
						if($tahunJamak =='on'){
							$tahunJamak = '1';
						}else{
							$tahunJamak = '0';
						}
						
						$urusan = explode('.',$urusan);
						$cmbUrusanForm = $urusan[0];
						$bidang = explode('.',$bidang);
						$cmbBidangForm = $bidang[0];
						$skpd = explode('.',$skpd);
						$cmbSKPDForm = $skpd[0];
						
						
					$cekUrusan =  mysql_num_rows(mysql_query("select * from tabel_anggaran_v3 where c1 = '$cmbUrusanForm' and c='00' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap'"));
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
						$query = VulnWalkerInsert("tabel_anggaran_v3", $data);
						$content .= $query;
						$cek .= "select * from tabel_anggaran_v3 where c1 = '$cmbUrusanForm' and c='00' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap'";
						mysql_query($query)	;				
					}
					
					$cekBidang =  mysql_num_rows(mysql_query("select * from tabel_anggaran_v3 where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '00' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'   and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
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
						$query = VulnWalkerInsert("tabel_anggaran_v3", $data);
						$content .= $query;
						mysql_query($query)	;				
					}

					
					$cekSKPD =  mysql_num_rows(mysql_query("select * from tabel_anggaran_v3 where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$cmbSKPDForm' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekSKPD > 0 ){
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
										'jenis_anggaran' => $this->jenisAnggaran,
										'id_tahap' => $this->idTahap,
										'tahun' => $this->tahun,
										'nama_modul' => $this->modul
										
										);
						$query = VulnWalkerInsert("tabel_anggaran_v3", $data);
						$content .= $query;
						mysql_query($query);					
					}
						
						
						
						$cekUnit = "select * from tabel_anggaran_v3 where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '000' and bk='0' and ck ='0' and p = '0' and q='0' and id_tahap = '$this->idTahap'  ";
						if(mysql_num_rows(mysql_query($cekUnit))  == 0) {
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => '000',
											'bk' => '0',
											'ck' => '0',
											'p' => '0',
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
						$cekSubUnit = "select * from tabel_anggaran_v3  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '$cmbSubUnitForm' and bk='0' and ck ='0' and p = '0' and q='0' and id_tahap = '$this->idTahap' ";
						if(mysql_num_rows(mysql_query($cekSubUnit))  == 0) {
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => $cmbSubUnitForm,
											'bk' => '0',
											'ck' => '0',
											'p' => '0',
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
						
						$cekProgram = "select * from tabel_anggaran_v3  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '$cmbSubUnitForm' and bk ='$bk' and ck ='$ck' and p = '$p' and q='0' and id_tahap = '$this->idTahap'";	
						if(mysql_num_rows(mysql_query($cekProgram)) == 0){
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => $cmbSubUnitForm,
											'bk' => $bk,
											'ck' => $ck,
											'p' => $p,
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
						
						$getTotalPagu = mysql_fetch_array(mysql_query("select sum(jumlah) as paguTotal from view_renja_v3 where tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and id_tahap = '$this->idTahap'"));
						$totaPagu = $getTotalPagu['paguTotal'];
						
						$nomorUrutSebelumnya = $this->nomorUrut - 1;
						$getPlafon  =  mysql_fetch_array(mysql_query("select plafon from view_plafon_v3 where tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and no_urut = '$nomorUrutSebelumnya'"));
						$plafon = $getPlafon['plafon'];
						$sisanya = $plafon - $totaPagu;
						$angkaSekarang = $plus + $minus + $paguIndikatif;
						if( $angkaSekarang > $sisanya){
							$err = "Pagu tidak dapat melebihi Plafon !!! ";
						}else{
								$cekKegiatan = "select * from tabel_anggaran_v3  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '$cmbSubUnitForm' and bk ='$bk' and ck ='$ck' and p = '$p' and q='$cmbKegiatan' and id_tahap = '$this->idTahap'";	
								if(mysql_num_rows(mysql_query($cekKegiatan)) == 0){
								$data = array(
												'jenis_anggaran' => $this->jenisAnggaran,
												'tahun' => $this->tahun,
												'c1' => $cmbUrusanForm,
												'c' => $cmbBidangForm,
												'd' => $cmbSKPDForm,
												'e' => $cmbUnitForm,
												'e1' => $cmbSubUnitForm,
												'bk' => $bk,
												'ck' => $ck,
												'p' => $p,
												'q' => $cmbKegiatan,
												'sumber_dana' => $cmbSumberDana,
												'tanggal_update' => date('y-m-d'),
												'user_update' => $_COOKIE['coID'],
												'jumlah' => $plus + $minus + $paguIndikatif,
												'id_tahap' => $this->idTahap,
												'nama_modul' => $this->modul
											  );
								 $queryK = VulnWalkerInsert("tabel_anggaran_v3",$data);
								 mysql_query($queryK);
								 $getIDAnggaran = mysql_fetch_array(mysql_query("select max(id_anggaran) as maxIDAnggaran from tabel_anggaran_v3  " ));
								 $dataDetailRenja = array('lokasi_kegiatan' => $lokasiKegiatan,
								 						  'jenis_kegiatan' => $jenisKegiatan,
														  'pagu_indikatif' => $paguIndikatif,
														  'plus'		   => $plus,
														  'min'			   => $minus,
														  'tanggal_mulai' => $tanggalMulai,
														  'tanggal_selesai' => $tanggalSelesai,
														  'tahun_jamak' => $tahunJamak,
														  'capaian_program_tk' => $CPTK,
														  'capaian_program_tuk' => $CPTU,
														  'masuk_tuk' => $MTU,
														  'masuk_tk' => $MTK,
														  'keluaran_tuk' => $KTU,
														  'keluaran_tk' => $KTK,
														  'hasil_tk' => $HTK,
														  'hasil_tuk' => $HTU,
														  'kelompok_sasaran_kegiatan' => $sasaranKegiatan,
														  'id_tahap' => $this->idTahap,
														  'id_anggaran' => $getIDAnggaran['maxIDAnggaran']
													);
								 $queryDetail = VulnWalkerInsert('detail_renja_v3',$dataDetailRenja);
								 mysql_query($queryDetail);
								 
							}else{
								$err = "DATA SUDAH ADA !";
							}
						}
						
							
						
						
						
						
						
						
						
			}else{
				
			}
			
			
			
			
			$content = array("1" => '1', "gblk" => $cekUrusanProgram , "ucing" =>  $cekSubUnit , "cek_unit" => $cekUnit, "insertK" => $queryK);
		
		
		break;
	    }

		case 'rincianpenerimaanDET':{
			$get= $this->rincianpenerimaanDET();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		case 'EDIT' : {
		foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
						if($tahunJamak =='on'){
							$tahunJamak = '1';
						}else{
							$tahunJamak = '0';
						}
						$tanggalMulai = explode("-",$tanggalMulai);
						$tanggalMulai = $tanggalMulai[2]."-".$tanggalMulai[1]."-".$tanggalMulai[0];
						$tanggalSelesai = explode("-",$tanggalSelesai);
						$tanggalSelesai = $tanggalSelesai[2]."-".$tanggalSelesai[1]."-".$tanggalSelesai[0];
		
		$getSKPD = mysql_fetch_array(mysql_query("select * from tabel_anggaran_v3 where id_anggaran = '$ID_RENJA'"));
		$sTahun = $getSKPD['tahun'];
		$sJenisAnggaran = $getSKPD['jenis_anggaran'];
		$cmbUrusanForm = $getSKPD['c1'];
		$cmbBidangForm = $getSKPD['c'];
		$cmbSKPDForm = $getSKPD['d'];
		$paguSebelumnya = $getSKPD['jumlah'];
		$getTotalPagu = mysql_fetch_array(mysql_query("select sum(jumlah) as paguTotal from view_renja_v3 where tahun = '$sTahun' and jenis_anggaran = '$sJenisAnggaran' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and id_tahap = '$this->idTahap'"));
		$totaPagu = $getTotalPagu['paguTotal'] - $paguSebelumnya;
		$nomorUrutSebelumnya = $this->nomorUrut - 1;
		$getPlafon  =  mysql_fetch_array(mysql_query("select plafon from view_plafon_v3 where tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and no_urut = '$nomorUrutSebelumnya'"));
		$plafon = $getPlafon['plafon'];
		$sisanya = $plafon - $totaPagu;
		$angkaSekarang = $plus + $minus + $paguIndikatif;
		if( $angkaSekarang > $sisanya){
			$err = "Pagu tidak dapat melebihi Plafon !!! ";
		}elseif($this->jenisForm != "PENYUSUNAN"){
			$err = "TAHAP PENYUSUNAN TELAH HABIS";
			
		}
		else{
		
			if($err == ""){
				$cekSKPD =  mysql_num_rows(mysql_query("select * from tabel_anggaran_v3 where c1 = '$cmbUrusanForm' and c='$cmbBidangForm' and d = '$cmbSKPDForm' and e='00' and e1='000' and bk='0' and ck='0' and p = '0' and q='0' and p = '00' and q='00'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
					if($cekSKPD > 0 ){
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
										'jenis_anggaran' => $this->jenisAnggaran,
										'id_tahap' => $this->idTahap,
										'tahun' => $this->tahun,
										'nama_modul' => $this->modul
										
										);
						$query = VulnWalkerInsert("tabel_anggaran_v3", $data);
						$content .= $query;
						mysql_query($query);					
					}
						
						
						
						$cekUnit = "select * from tabel_anggaran_v3 where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '000' and bk='0' and ck ='0' and p = '0' and q='0' and id_tahap = '$this->idTahap'  ";
						if(mysql_num_rows(mysql_query($cekUnit))  == 0) {
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => '000',
											'bk' => '0',
											'ck' => '0',
											'p' => '0',
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
						$cekSubUnit = "select * from tabel_anggaran_v3  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '$cmbSubUnitForm' and bk='0' and ck ='0' and p = '0' and q='0' and id_tahap = '$this->idTahap' ";
						if(mysql_num_rows(mysql_query($cekSubUnit))  == 0) {
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => $cmbSubUnitForm,
											'bk' => '0',
											'ck' => '0',
											'p' => '0',
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
						
						$cekProgram = "select * from tabel_anggaran_v3  where jenis_anggaran = '$this->jenisAnggaran' and tahun = '$this->tahun' and c1= '$cmbUrusanForm' and c='$cmbBidangForm' and d='$cmbSKPDForm' and e = '$cmbUnitForm' and e1 = '$cmbSubUnitForm' and bk ='$bk' and ck ='$ck' and p = '$p' and q='0' and id_tahap = '$this->idTahap'";	
						if(mysql_num_rows(mysql_query($cekProgram)) == 0){
							$data = array(
											'jenis_anggaran' => $this->jenisAnggaran,
											'tahun' => $this->tahun,
											'c1' => $cmbUrusanForm,
											'c' => $cmbBidangForm,
											'd' => $cmbSKPDForm,
											'e' => $cmbUnitForm,
											'e1' => $cmbSubUnitForm,
											'bk' => $bk,
											'ck' => $ck,
											'p' => $p,
											'q' => '0',
											'id_tahap' => $this->idTahap,
											'nama_modul' => $this->modul
										  );
							 $query = VulnWalkerInsert("tabel_anggaran_v3",$data);
							 mysql_query($query);
						}
		
					
					
					$data = array( "jenis_kegiatan" => $jenisKegiatan,
									   "plus" => $plus,
									   "min" => $minus,
									   "tanggal_mulai" => $tanggalMulai,
									   "tanggal_selesai" => $tanggalSelesai,
									   "pagu_indikatif" => $paguIndikatif,
									   'tahun_jamak' => $tahunJamak,
									   'capaian_program_tk' => $CPTK,
									   'capaian_program_tuk' => $CPTU,
									   "lokasi_kegiatan" => $lokasiKegiatan,
									   'masuk_tk' => $MTK,
									   'masuk_tuk' =>$MTU,
									   'keluaran_tk' => $KTK,
									   'keluaran_tuk' =>$KTU,
									   'hasil_tk' => $HTK,
									   'hasil_tuk' =>$HTU,
									   'kelompok_sasaran_kegiatan' => $sasaranKegiatan,
									   'id_tahap' => $this->idTahap
									   
										);
							$query = VulnWalkerUpdate('detail_renja_v3',$data,"id_anggaran='$ID_RENJA'");			
							mysql_query($query);
						 $dataAnggaran = array("jumlah" => $paguIndikatif + $plus + $minus,
						 					   'sumber_dana' => $cmbSumberDana,
											 'tanggal_update' => date('y-m-d'),
												'user_update' => $_COOKIE['coID'],
												'p' => $p,
									   'q' => $cmbKegiatan,
									   'bk' => $bk,
									   'ck' => $ck,
									   'e' => $cmbUnitForm,
									   'e1' => $cmbSubUnitForm
												);
												
												
				mysql_query(VulnWalkerUpdate("tabel_anggaran_v3",$dataAnggaran," id_anggaran = '$ID_RENJA' "));	
			}		
		}
			 
		
						

		break;	
		}
		
		case 'BidangAfterForm':{
			 $kondisiBidang = "";
			 $selectedUrusan = $_REQUEST['fmSKPDUrusan'];
			 $selectedBidang = $_REQUEST['fmSKPDBidang'];
			 $selectedskpd = $_REQUEST['fmSKPDskpd'];
			 $selectedUnit = $_REQUEST['fmSKPDUnit'];
			 $selectedSubUnit = $_REQUEST['fmSKPDSubUnit'];
			 			
			 $codeAndNameSubUnit = "SELECT e1, concat(e1, '. ', nm_skpd) as vnama FROM ref_skpd  where c1='$selectedUrusan'  and  c='$selectedBidang'  and d = '$selectedskpd' and  e = '$selectedUnit' and e1!='000' ";
				$SubUnit = cmbQuery('cmbSubUnitForm', $selectedSubUnit, $codeAndNameSubUnit,''.$cmbRo.'onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Sub Unit --');
				$content = array('subunit' =>$SubUnit ,'queryGetSubUnit' => $codeAndNameSubUnit);
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
		 
	 }
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
   
  
   
   function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						setTimeout(function myFunction() {".$this->Prefix.".rincianpenerimaan()},1000);
						
					</script>";
		return

			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/perencanaan_v3/renja/renja_ins.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan/renja/popupProgram.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/popupUrusanBidangProgram.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/popupUrusan.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/renja/renja.js' language='JavaScript' ></script>
			".
			
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			  <script>
			  $( function() {
			    $( "#tgl_dok" ).datepicker({ dateFormat: "dd-mm-yy" });
				
				$( "#datepicker2" ).datepicker({ dateFormat: "dd-mm-yy" });
			  } );
			  </script>
			'.
			$scriptload;
	}
	
	//form ==================================
 
	function setPage_HeaderOther(){
	return 
	"";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox		
		   <th class='th01' rowspan='2'>TANGGAL BAST/ BUKU</th>
		   <th class='th01' rowspan='2'>NO BAST/ID PENERIMAAN</th>
		   <th class='th02' colspan='2'>DOKUMEN SUMBER</th>
		   <th class='th01' rowspan='2'>SUMBER DANA/ KODE AKUN / PENYEDIA BARANG</th>
		   <th class='th01' rowspan='2'>NAMA BARANG</th>
		   <th class='th01' rowspan='2'>MERK / TYPE/ SPESIFIKASI/ LOKASI</th>
		   <th class='th01' rowspan='2'>JUMLAH</th>
		   <th class='th01' rowspan='2'>HARGA SATUAN</th>
		   <th class='th01' rowspan='2'>JUMLAH HARGA</th>
		   <th class='th01' rowspan='2'>HARGA ATRIBUSI</th>
		   <th class='th01' rowspan='2'>HARGA PEROLEHAN</th>
		   <th class='th01' rowspan='2'>ADA ATRIBUSI</th>
		   <th class='th02' colspan='2'>DISTRIBUSI</th>
		   <th class='th01' rowspan='2'>VALIDASI</th>
		   <th class='th01' rowspan='2'>POSTING</th>
		   <th class='th01' rowspan='2'>KET.</th>
	   </tr>
	   <tr>
	   		<th class='th01'>DOKUMEN</th>
	   		<th class='th01'>TANGGAL DAN NOMOR</th>
	   		<th class='th01'>Y/T</th>
	   		<th class='th01'>SESUAI</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['tgl_buku']."<br>".$isi['tgl_bast']);
	 $Koloms[] = array('align="left"',$isi['no_bast']."/".$isi['id_penerimaan']);
	 $Koloms[] = array('align="left"',$isi['id_dok_sumber']);
	 $Koloms[] = array('align="left"',$isi['nomor_dok']);
	 $Koloms[] = array('align="left"',$isi['sumber_dana']."<br>".$isi['ka'].".".$isi['kb'].".".$isi['kc'].".".$isi['kd'].".".$isi['ke'].".".$isi['kf']."<br>".$isi['id_penyedia']);
	 $Koloms[] = array('align="left"',"Nama Barang");
	 $Koloms[] = array('align="left"',"Keterangan Barang");
	 $Koloms[] = array('align="left"',"Jumlah");
	 $Koloms[] = array('align="left"',"Harga Satuan");
	 $Koloms[] = array('align="left"',"Jumlah Harga");
	 $Koloms[] = array('align="left"',"Harga Atribusi");
	 $Koloms[] = array('align="left"',"Harga Perolehan");
	 $Koloms[] = array('align="left"',"ada atribusi");
	 $Koloms[] = array('align="left"',"");
	 $Koloms[] = array('align="left"',"");
	 $Koloms[] = array('align="left"',"");
	 $Koloms[] = array('align="left"',"");
	 $Koloms[] = array('align="left"',"");
	 return $Koloms;
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
		
		$cbid = $_REQUEST['pemasukan_cb'];
		 setcookie("coUrusanProgram", "", time()-3600);
		 setcookie('coBidangProgram', "", time()-3600);
		 unset($_COOKIE['coProgram']);
   		 
		return
		

		"<html>".
			$this->genHTMLHead().
			"<body >".
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".	
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
					"
					<input type='hidden' name='ID_PLAFON' value='".$_REQUEST['id']."' />
					<input type='hidden' name='ID_RENJA' value='".$_REQUEST['ID_RENJA']."' />".
					"<input type='hidden' name='databaru' id='databaru' value='".$_REQUEST['YN']."' />".
					"<input type='hidden' name='idubah' id='idubah' value='".$cbid[0]."' />".
										
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
		</html>"; 
	}	
	
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS;

	$ID_PLAFON = $_REQUEST['ID_PLAFON'];
	$ID_RENJA = $_REQUEST['ID_RENJA'];
	$Syntax_ambil_yang_udah_ada_plafon = "select * from skpd_renja_v3 where id='$ID_PLAFON'";
	$ambilSKPD = mysql_fetch_array(mysql_query($Syntax_ambil_yang_udah_ada_plafon));
	$c1 = $ambilSKPD['c1'];
	$c  = $ambilSKPD['c'];
	$d  = $ambilSKPD['d'];
	$tanggalSekarang = str_replace("-","",date("Y-m-d"));
	$jenisTransaksi = $this->jenisAnggaran;
	$plafon = $ambilSKPD['jumlah']; 
	if($plafon == ''){
		$disablePlafon = "";
	}else{
		$disablePlafon = "readonly";
	}
	$selectedUrusan = $c1;
	$selectedBidang = $c;
	$selectedskpd = $d;

	$readOnlyJenisKegiatanPlus = "readonly";
	$readOnlyJenisKegiatanMin = "readonly";
	$disabledCariProgram = "";
	$arrayNameUrusan = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='00' and d='00' and e='00' and e1='000'"));
	$namaUrusan = $arrayNameUrusan['nm_skpd'];
	
	$arrayNameBidang = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='00' and e='00' and e1='000'"));
	$namaBidang = $arrayNameBidang['nm_skpd'];
	
	$arrayNameSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='00' and e1='000'"));
	$namaSKPD = $arrayNameSKPD['nm_skpd'];
	
	$getPengurangan = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from view_renja_v3 where q != 0 and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d'"));
	$sisaPlafon = $plafon - $getPengurangan['jumlah'] ;
	$sisaDariDB = $getPengurangan['jumlah'] ;
	
	$tujuan = "Simpan()";
	$codeAndNameKegiatan = "select q, concat(q, '. ', nama_program_kegiatan) as vnama from ref_programkegiatan where p='$selectedProgram' and q!='00'";
	if($ID_RENJA != '' && $ID_RENJA > 0){
	    $getRenja = mysql_fetch_array(mysql_query("select * from tabel_anggaran_v3 where id_anggaran = '$ID_RENJA'"));
		$selectedUnit = $getRenja['e'];
		$selectedSubUnit = $getRenja['e1'];
		$selectedProgram = $getRenja['p'];
		$selectedBK = $getRenja['bk'];
		$selectedCK = $getRenja['ck'];
		$selectedProgram = $getRenja['p'];
		$selectedP = $getRenja['p'];
		
		$getNamaRefUrusan = mysql_fetch_array(mysql_query("select * from ref_urusan where bk = '$bk' and ck ='0' and dk='0'"));
		$urusanProgram = $getNamaRefUrusan['nm_urusan'];
		$getNamaRefBidang = mysql_fetch_array(mysql_query("select * from ref_urusan where bk = '$bk' and ck ='$ck' and dk='0'"));
		$bidangProgram = $getNamaRefBidang['nm_urusan'];
		$getNamaProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk = '$selectedBK' and ck ='$selectedCK' and p='$selectedProgram' and q='0'"));
		$program = $getNamaProgram ['nama'];
		$getDetail = mysql_fetch_array(mysql_query("select * from detail_renja_v3 where id_anggaran = '$ID_RENJA'"));
		$sisaDariDB = $getPengurangan['jumlah']  - $getDetail['pagu_indikatif'] ;
	    $paguIndikatif = $getDetail['pagu_indikatif'];
		$tanggalMulai = explode('-',$getDetail['tanggal_mulai']) ;
		$tanggalMulai = $tanggalMulai[2] ."-".$tanggalMulai[1]."-".$tanggalMulai[0];
		$tanggalSelesai = explode('-',$getDetail['tanggal_selesai']) ;
		$tanggalSelesai = $tanggalSelesai[2] ."-".$tanggalSelesai[1]."-".$tanggalSelesai[0];
		
		$selectedSumberDana = $getRenja['sumber_dana'];
		$kelompokSasaran = $getDetail['kelompok_sasaran_kegiatan'];
		$selectedKegiatan = $getRenja['q'];
		$codeAndNameKegiatan = "select q, concat(q, '. ', nama) as vnama from ref_program where bk='$selectedBK' and ck = '$selectedCK' and p='$selectedProgram' and q='$selectedKegiatan'";
		if($getDetail['tahun_jamak'] == '1'){
			$tahunJamakChecked = "checked";
		}else{
			$tahunJamakChecked = "";
		}
		if($getDetail['jenis_kegiatan'] == 'lanjutan'){
			$selectedJenisKegiatan = "lanjutan";
			$readOnlyJenisKegiatanPlus = "style='width:200px; text-align:right;' onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57' onkeyup='document.getElementById(`keyPP`).textContent = `Rp. ` + popupProgramRenja.formatCurrency(this.value);'";
			$readOnlyJenisKegiatanMin = "style='width:200px; text-align:right;' onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57' onkeyup='document.getElementById(`keyMM`).textContent = `Rp. ` + popupProgramRenja.formatCurrency(this.value);'";
			$plusValue = $getDetail['plus'];
			$minValue = $getDetail['min'];
			$sisaDariDB = $sisaDariDB - $getDetail['plus'] - $getDetail['min'];
		}
		/*$disabledCariProgram = "disabled";*/
		
		$tujuan = "EDIT()";
	}	
	
	


	
$comboBoxKegiatan = cmbQuery('cmbKegiatan', $selectedKegiatan, $codeAndNameKegiatan,' '.$disabledCariProgram.'','-- KEGIATAN --');  
			
$comboBoxJenisKegiatan = cmbArray('jenisKegiatan',$jenisKegiatan,$arrayComboJenisKegiatan,'-- JENIS KEGIATAN --','onchange = renja.jenisChanged();');			

$codeAndNameSumberDana = "select nama, nama from ref_sumber_dana";
	 $codeAndNameUnit = "SELECT e, concat(e, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e != '00' and e1='000' ";
     $cek .= $codeAndNameUnit;
	 
	 $codeAndNameSubUnit = "SELECT e1, concat(e1, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and c1 = '$selectedUrusan'  and d = '$selectedskpd' and  e = '$selectedUnit' and e1 !='000' ";
     $cek .= $codeAndNameUnit;
	 
$arrayComboJenisKegiatan = array(	
			array('baru','BARU'),		
			array('lanjutan','LANJUTAN')			
			);	 



if(empty($selectedSumberDana)){
	$selectedSumberDana = "APBD";
}
$comboBoxSumberDana = cmbQuery('cmbSumberDana', $selectedSumberDana, $codeAndNameSumberDana,' '.$cmbRo.'','-- SUMBER DANA --');   
$comboBoxJenisKegiatan = cmbArray('jenisKegiatan',$selectedJenisKegiatan,$arrayComboJenisKegiatan,'','onchange = renja.jenisChanged();');		 
$comboBoxUnit =  cmbQuery('cmbUnitForm', $selectedUnit, $codeAndNameUnit,' '.$disabledCariProgram.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Unit --'); 
$comboBoxSubUnit =  cmbQuery('cmbSubUnitForm', $selectedSubUnit, $codeAndNameSubUnit,' '.$disabledCariProgram.' onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Sub Unit --');       
$mulai = "<input type ='text' id='tanggalMulai' name='tanggalMulai' value='$tanggalMulai' class='datepicker'> ";
$selesai = "<input type ='text' id='tanggalSelesai' name='tanggalSelesai' value='$tanggalSelesai' class='datepicker'> ";
$tahunJamak = "<input type='checkbox' name='tahunJamak' id='tahunJamak' $tahunJamakChecked> TAHUN JAMAK";
$waktuPelaksanaan = $mulai."&nbsp&nbsp s/d &nbsp&nbsp ".$selesai." &nbsp&nbsp ".$tahunJamak;





	$TampilOpt =
			
			
	$vOrder=
			genFilterBar(
				array(
					$this->isiform(
						array(
							array(
								'label'=>'URUSAN',
								'name'=>'urusan',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c1.". ".$namaUrusan,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'BIDANG',
								'name'=>'bidang',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c.'. '.$namaBidang,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'SKPD',
								'name'=>'skpd',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$d.'. '.$namaSKPD,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							)
						)
					)
				
				),'','','').
				
				genFilterBar(
				array(
					$this->isiform(
						array(
							array(
								'label'=>'TAHUN',
								'name'=>'tahunAnggaran',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$this->tahun,
								'parrams'=>"style='width:100px;' readonly",
							),
							array(
								'label'=>'TRANSAKSI',
								'name'=>'transaksi',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$jenisTransaksi,
								'parrams'=>"style='width:200px;' readonly",
							),
							 array( 
								'label'=>'PROGRAM',
								'labelWidth'=>250, 
								'value'=>"<input type='hidden' name='bk' id='bk' value='$selectedBK'><input type='hidden' name='ck' id='ck' value='$selectedCK'><input type='hidden' name='p' id='p' value='$selectedP'><input type='text' name='program' value='".$program."' style='width:600px;' id='program' readonly>&nbsp
								<input type='button' value='Cari' id='findProgram' onclick ='renja_ins_v3.CariProgramKegiatan()'  title='Cari Program dan Kegiatan' $disabledCariProgram >" 
							  ),
							  array( 
								'label'=>'KEGIATAN',
								'labelWidth'=>150, 
								'value'=>$comboBoxKegiatan				
								 ),
							  array( 'label' => "UNIT KERJA PELAKSANA",
								 	 'value' => "<div style='margin-top: 20px;'></div>"
								),
								 array( 
								'label'=>'&nbsp&nbspUNIT',
								'labelWidth'=>150, 
								'value'=>$comboBoxUnit						
								 ),
							array( 
								'label'=>'&nbsp&nbspSUB UNIT',
								'labelWidth'=>150, 
								'value'=>$comboBoxSubUnit						
								),
							 array( 
								'label'=>'LOKASI KEGIATAN',
								'name' => 'lokasiKegiatan',
								'labelWidth'=>250, 
								'value'=> $getDetail['lokasi_kegiatan'], 
								'type'=>'text',
								'parrams'=>"style='width:600px;'"
							 ),	
							array( 
								'label'=>'JENIS KEGIATAN',
								'labelWidth'=>150, 
								'value'=>$comboBoxJenisKegiatan		                                                                                                                                   			
								 ),	
							array( 
								'label'=>'WAKTU PELAKSANAAN',
								'labelWidth'=>150, 
								'value'=>$waktuPelaksanaan				
							),
							
							array( 
								'label'=>'SUMBER DANA',
								'labelWidth'=>150, 
								'value'=>$comboBoxSumberDana
							),
							array( 
								'label'=>'KELOMPOK SASARAN KEGIATAN',
								'name' => 'sasaranKegiatan', 
								'labelWidth'=>150, 
								'type' => 'text',
								'value'=>$kelompokSasaran,
								'parrams' => "style='width:200px;' placeholder='KELOMPOK SASARAN KEGIATAN'"
								
							),						
						)
					).
					'<span id="pilCaraPerolehan"></span>'
				
				),'','','').
				
				"<div id='rinciandatabarangnya'></div>".
				genFilterBar(
					array(
					"
						<input type='hidden' name='".$this->Prefix."_idplh' id='".$this->Prefix."_idplh' value='$idplhnya' />
					<input type='hidden' name='refid_terimanya' id='refid_terimanya' value='".$dt['Id']."' />
					<input type='hidden' name='FMST_penerimaan_det' id='FMST_penerimaan_det' value='".$dt['FMST_penerimaan_det']."' />
					<table>
						<tr>
							<td>".$this->buttonnya($this->Prefix.'.'.$tujuan,'save_f2.png','simpan','simpan','SIMPAN')."</td>
							<td>".$this->buttonnya($this->Prefix.'.closeTab()','cancel_f2.png','batal','batal','BATAL')."</td>
							
						</tr>".
					"</table>"
				
					
					
				),'','','')				
			;
			
			
		return array('TampilOpt'=>$TampilOpt);
	}
	
	
	
	function isiform($value){
		$isinya = '';
		$tbl ='<table width="100%">';
		for($i=0;$i<count($value);$i++){
			if(!isset($value[$i]['align']))$value[$i]['align'] = "left";
			if(!isset($value[$i]['valign']))$value[$i]['valign'] = "top";
			
			if(isset($value[$i]['type'])){
				switch ($value[$i]['type']){
					case "text" :
						$isinya = "<input type='text' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "hidden" :
						$isinya = "<input type='hidden' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "password" :
						$isinya = "<input type='password' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					default:
						$isinya = $value[$i]['value'];
					break;					
				}
			}else{
				$isinya = $value[$i]['value'];
			}
			
			$tbl .= "
				<tr>
					<td width='".$value[$i]['label-width']."' valign='top'>".$value[$i]['label']."</td>
					<td width='10px' valign='top'>:<br></td>
					<td align='".$value[$i]['align']."' valign='".$value[$i]['valign']."'> $isinya</td>
				</tr>
			";		
		}
		$tbl .= '</table>';
		
		return $tbl;
	}
	
	
	
	function tampilarrnya($value, $width='12'){
		$gabung = '<div class="col-lg-'.$width.' form-horizontal well bs-component"><fieldset>';
		for($i=0;$i<count($value);$i++){
			
			if(isset($value[$i]['type'])){
				switch ($value[$i]['type']){
					case "text" :
						$isinya = "<input type='text' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "hidden" :
						$isinya = "<input type='hidden' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "password" :
						$isinya = "<input type='password' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					default:
						$isinya = $value[$i]['value'];
					break;					
				}
			}else{
				$isinya = $value[$i]['value'];
			}
			
			$gabung .='<div class="form-group">
				<label for="'.$value[$i]['name'].'" class="col-lg-'.$value[$i]['label-width'].' control-label" style="text-align:left;font-size:15px;font-weight: bold;">'.$value[$i]['label'].' </label>
				<label class="col-lg-1 control-label" style="text-align:center;">:</label>
      			<div class="col-lg-'.$value[$i]['isi-width'].'">
					'.$isinya.'
				</div>
    		</div>';
		}
		$gabung .= '</fieldset></div>';
		return $gabung;
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//Cari 
		switch($fmPILCARI){			
			case 'selectSatuan': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
		}	
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
	

	function rincianpenerimaanDET(){
		$cek = '';$err='';
		
		$idplh = addslashes($_REQUEST['renja_ins_v3_idplh']);
		$ID_RENJA = $_REQUEST['ID_RENJA'];
		$getKeterangan = mysql_fetch_array(mysql_query("select * from detail_renja_v3 where id_anggaran= '$ID_RENJA'"));
		$CPTU = $getKeterangan['capaian_program_tuk'];
		$CPTK = $getKeterangan['capaian_program_tk'];
		$MTU = $getKeterangan['masuk_tuk'];
		$MTK = $getKeterangan['masuk_tk'];
		$KTU = $getKeterangan['keluaran_tuk'];
		$KTK = $getKeterangan['keluaran_tk'];
		$HTU = $getKeterangan['hasil_tuk'];
		$HTK = $getKeterangan['hasil_tuk'];
		//$datanya = ";

			$datanya = "
				<tr class='row0'>
					<td>CAPAIAN PROGRAM</td>
					<td><textarea name='CPTU' id='CPTU' style='width:100%; '>$CPTU</textarea></td>
					<td><textarea name='CPTK' id='CPTK' style='width:100%; '>$CPTK</textarea></td>
			   </tr>
			   
			   <tr class='row0'>
					<td>MASUKAN</td>
					<td><textarea name='MTU' id='MTU' style='width:100%;'>$MTU</textarea></td>
					<td><textarea name='MTK' id='MTK' style='width:100%;'>$MTK</textarea></td>
			   </tr>
			   <tr class='row0'>
			  		<td>KELUARAN</td>
					<td><textarea name='KTU' id='KTU' style='width:100%; '>$KTU</textarea></td>
					<td><textarea name='KTK' id='KTK' style='width:100%;'>$KTK</textarea></td>
			   </tr>
			   <tr class='row0'>
			   		<td>HASIL</td>
					<td><textarea name='HTU' id='HTU' style='width:100%;'>$HTU</textarea></td>
					<td><textarea name='HTK' id='HTK' style='width:100%;'>$HTK</textarea></td>
			   </tr>	
			";
			
		
		$content = 
					"
					<div class='FilterBar' style='padding:10px; text-align : left;'>
					<table class='koptable' style='width:60%;' border='1'>
						<tr>
								<th class='th01'>INDIKATOR</th>
								<th class='th01'>TOLAK UKUR KINERJA</th>
								<th class='th01'>TARGET KINERJA</th>	
							
						</tr>
						$datanya
					</table>
					</div>"
				
				;
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function buttonnya($js,$img,$name,$alt,$judul){
		return "<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:$js'> 
						<img src='images/administrator/images/$img' alt='$alt' name='$name' width='32' height='32' border='0' align='middle' title='$judul'> $judul</a> 
					</td> 
					</tr> 
					</tbody></table> ";
	}
	
	
	
	
	
}
$renja_ins_v3 = new renja_ins_v3Obj();

$arrayResult = tahapPerencanaanV3($renja_ins_v3->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$renja_ins_v3->jenisForm = $jenisForm;
$renja_ins_v3->nomorUrut = $nomorUrut;
$renja_ins_v3->tahun = $tahun;
$renja_ins_v3->jenisAnggaran = $jenisAnggaran;
$renja_ins_v3->idTahap = $idTahap;
$renja_ins_v3->username = $_COOKIE['coID'];
$renja_ins_v3->wajibValidasi = $Main->wajibValidasi;

?>