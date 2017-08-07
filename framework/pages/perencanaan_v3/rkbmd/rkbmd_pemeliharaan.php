<?php
class rkbmd_pemeliharaan_v3Obj  extends DaftarObj2{	
	var $Prefix = 'rkbmd_pemeliharaan_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'rkbmd_pemeliharaan_v3'; //bonus
	var $TblName_Hapus = 't_penerimaan_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('volume_barang');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 11, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'RKBMD';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='rkbmd.xls';
	var $namaModulCetak='PERENCANAAN';
	var $Cetak_Judul = 'RKBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'rkbmd_pemeliharaan_v3Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $modul = "RKBMD";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	
	function setTitle(){
	    $id = $_REQUEST['ID_RENJA'];
	    $getTahun = mysql_fetch_array(mysql_query("select * from tabel_anggaran_v3 where id_anggaran = '$id'"));
		return 'RKBMD PEMELIHARAAN TAHUN '.$getTahun['tahun'] ;
	}
	
	function setMenuEdit(){
		return "";

	}
	
	function setMenuView(){
		return "";
	}
	
	
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		if (sizeof($this->FieldSum)>0){
			$TampilTotalHalRp = number_format($this->SumValue[0],0, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>JUMLAH</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					<td class='$ColStyle' align='right'><b></td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;
			
				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		}
		return $ContentTotalHal.$ContentTotal;
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
		case 'CekAda':{
		
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			} 
			
			$c1 = explode(".",$urusan);
			$c1 = $c1[0];
			$c = explode(".",$bidang);
			$c = $c[0];
			$d = explode(".",$skpd);
			$d = $d[0];
			$e = explode(".",$unit);
			$e = $e[0];
			$e1 = explode(".",$subunit);
			$e1 = $e1[0];
			$username = $_COOKIE['coID'];
			mysql_query("delete from rkbmd_pemeliharaan_v3 where user='$username'");

			$getAll = mysql_query("select * from view_rkbmd_v3 where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and jenis_form_modul ='PENYUSUNAN' and id_tahap='$this->idTahap' and j !='000' and id_jenis_pemeliharaan !='0' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1 ='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and status_validasi !='1'");
			if(mysql_num_rows($getAll) > 0 ){
				$tergantung = 'ada';
				
						while($rows = mysql_fetch_array($getAll)){
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
									'f' => $f,
									'g' => $g,
									'h' => $h,
									'i' => $i,
									'j' => $j,
									'satuan' => $satuan_barang,
									'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
									'keterangan' => $catatan,
									'uraian_pemeliharaan' => $uraian_pemeliharaan,
									'volume_barang' => $volume_barang,
									'user' => $username
								  );
							if($id_jenis_pemeliharaan != '0'){
								mysql_query(VulnWalkerInsert('rkbmd_pemeliharaan_v3',$data));
							}	  
						}
				
			}else{
				$tergantung = 'kosong';
			}
				
			
			
			
		 
			
			
			$content = array("status" => $tergantung, "query" => "select * from view_rkbmd_v3 where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and jenis_form_modul ='PENYUSUNAN' and id_tahap='$this->idTahap' and j !='000' and id_jenis_pemeliharaan ='0' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1 ='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q'");
		
		
		break;
	    }
		case 'tabelPemeliharaan':{
			$get= $this->tabelPemeliharaan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		
		case 'subEdit':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			if($jumlah > $jumlahKebutuhanRiil){
				$err = "jumlah tidak dapat melebihi kebutuhan ril";
			}
			$data = array('jumlah' => $jumlah ,
						  'catatan' => $keterangan
						);
			mysql_query(VulnWalkerUpdate('temp_rkbmd_pengadaan',$data,"id = '$id'"));
			$content = VulnWalkerUpdate('temp_rkbmd_pengadaan',$data,"id = '$id'");
			
		break;
		}
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'finish':{		
			$username = $_COOKIE['coID'];		
			$execute = mysql_query("select * from rkbmd_pemeliharaan_v3 where user='$username'");
			$get = mysql_fetch_array($execute);
			foreach ($get as $key => $value) { 
				  $$key = $value; 
			}
			if(mysql_num_rows(mysql_query("select * from rkbmd_pemeliharaan_v3 where user='$username'")) == 0){
				$err = "Data Kosong";
			}elseif($this->jenisForm !='PENYUSUNAN'){
				$err = "Tahap Penyusunan Telah Habis";
				
			}
			else{
			    mysql_query("delete from tabel_anggaran_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and id_tahap ='$this->idTahap' and ((id_jenis_pemeliharaan != '0' and f !='00') or uraian_pemeliharaan = 'RKBMD PEMELIHARAAN') and status_validasi !='1' ");
				$cekSKPD = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '0' and ck = '0' and p = '0' and q= '0' and id_tahap='$this->idTahap'"));
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
								  'f1' => '0',
							  				'f2' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID']
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
				}
				$cekProgram = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '0' and id_tahap='$this->idTahap'"));												
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
								  'f1' => '0',
							  				'f2' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID']
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
				}
				
				$cekKegiatan = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '$q' and  f='00' and id_tahap='$this->idTahap' and uraian_pemeliharaan = 'RKBMD PEMELIHARAAN'"));												
				if($cekKegiatan < 1){
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
								  'f1' => '0',
							  				'f2' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID'],
								  'uraian_pemeliharaan' => 'RKBMD PEMELIHARAAN' 
									);
						mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
				}
				
				$execute2 = mysql_query("select * from rkbmd_pemeliharaan_v3 where user='$username'");
				while($rows = mysql_fetch_array($execute2)){
					foreach ($rows as $key => $value) { 
					  $$key = $value; 
					}
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
								  'f' => $f,
								  'g' => $g,
								  'h' => $h,
								  'i' => $i,
								  'j' => $j,
								  'volume_barang'=> $volume_barang,
								  'catatan' => $keterangan,
								  'satuan_barang' => $satuan,
								  'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
								  'uraian_pemeliharaan' => $uraian_pemeliharaan,
								  'id_tahap' => $this->idTahap,
								  'nama_modul' => "RKBMD",
								  'tanggal_update' => date('Y-m-d'),
								  'user_update' => $_COOKIE['coID'],
								  );
						mysql_query(VulnWalkerInsert("tabel_anggaran_v3",$data));
						$content = VulnWalkerInsert("tabel_anggaran_v3",$data);
						mysql_query("delete from rkbmd_pemeliharaan_v3 where id = '$id'");
				}	
			}
			
		break;
		}
		case 'newRowPemeliharaan':{
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			if(empty($q)){
				$err = "KEGIATAN BELUM DI PILIH";
			}else if(empty($kodeBarang)){
				$err = "KODE BARANG BELUM DI PILIH";
			}
			$kodeBarang = explode(".",$kodeBarang);
			$f = $kodeBarang[0];
			$g = $kodeBarang[1];
			$h = $kodeBarang[2];
			$i = $kodeBarang[3];
			$j = $kodeBarang[4];
			$data  = array("c1" => $c1,
						   "c" => $c,
						   "d" => $d,
						   "e" => $e,
						   "e1" => $e1,
						   "bk" => $bk,
						   "ck" => $ck,
						   "dk" => $dk,
						   "p" => $p,
						   "q" => $q,
						   "f" => $f,
						   "g" => $g,
						   "h" => $h,
						   "i" => $i,
						   "j" => $j,
						   "satuan" => $satuan,
						   "jumlah" => $jumlah,
						   "id_jenis_pemeliharaan" => '0',
						   "uraian_pemeliharaan" => '',
						   "keterangan" => $keterangan,
						   "user" => $_COOKIE['coID']
			);
			$query = (VulnWalkerInsert('temp_rkbmd_pemeliharaan_v3',$data));
			mysql_query($query);
			$username = $_COOKIE['coID'];
			$getIdAkhir = mysql_fetch_array(mysql_query("select max(id) as idAkhir from temp_rkbmd_pemeliharaan_v3 where user = '$username'"));
			$idAkhir = $getIdAkhir['idAkhir'];
			$content = array('id' => $idAkhir);
			
			
		break;
	    }
		case 'CekKosong':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			$query = "select * from view_rkbmd_v3 where c1 = '$c1' and c ='$c' and d ='$d' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='$c' and d ='$d' and tahun = '$tahunAnggaran' ");
			}
			
			
			$query = "select * from view_rkbmd_v3 where c1 = '$c1' and c ='$c' and d !='00' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='$c' and d ='00' and tahun = '$tahunAnggaran'");
				
			}
			
			$query = "select * from view_rkbmd_v3 where  c1 = '$c1' and c !='00' and d !='00' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='00' and d ='00' and tahun = '$tahunAnggaran' ");
				
			}
			
			
			
														
		break;
		}		
		case 'Simpan':{
		
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			} 
			
			$c1 = explode(".",$urusan);
			$c1 = $c1[0];
			$c = explode(".",$bidang);
			$c = $c[0];
			$d = explode(".",$skpd);
			$d = $d[0];
			$e = explode(".",$unit);
			$e = $e[0];
			$e1 = explode(".",$subunit);
			$e1 = $e1[0];
			
			$kodeBarang1 = explode(".",$kodeBarang);
			$f = $kodeBarang1[0];
			$g = $kodeBarang1[1];
			$h = $kodeBarang1[2];
			$i = $kodeBarang1[3];
			$j = $kodeBarang1[4];
			
			$concat = $f.".".$g.".".$h.".".$i.".".$j;
			$username = $_COOKIE['coID'];			
			$cekSame = mysql_num_rows(mysql_query("select * from rkbmd_pemeliharaan_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat'  and user = '$username'"));
			
			if(empty($q)){
				$err = "Pilih Kegiatan";
			}elseif(empty($kodeBarang)){
				$err = "Pilih Barang";
			}elseif($cekSame > 0){
				$err = "Barang Sudah ada";
			}else{
				$data  = array("c1" => $c1,
						   "c" => $c,
						   "d" => $d,
						   "e" => $e,
						   "e1" => $e1,
						   "bk" => $bk,
						   "ck" => $ck,
						   "dk" => $dk,
						   "p" => $p,
						   "q" => $q,
						   "f" => $f,
						   "g" => $g,
						   "h" => $h,
						   "i" => $i,
						   "j" => $j,
						   "jumlah" => $jumlah,
						   "id_jenis_pemeliharaan" => '',
						   "uraian_pemeliharaan" => '',
						   "keterangan" => $keterangan,
						   "user" => $_COOKIE['coID']
			);
			/*
			$query = VulnWalkerInsert("temp_rkbmd_pemeliharaan_v3",$data);
			
			mysql_query($query);*/
			}
			
			
			$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$bk' and ck = '$ck'  and p = '$p' and q ='$q' ";
	$cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,' disabled','-- KEGIATAN --');  
			
			
			$content = array("q" => $cmbKegiatan, 'query' => $query);
		
		
		break;
	    }
		
		case 'subSubDelete':{				
			$id = $_REQUEST['id'];
			mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where id='$id'");				
		break;
		}
		case 'subHapus':{				
			$id = $_REQUEST['id'];
			$get = mysql_fetch_array(mysql_query("select * from rkbmd_pemeliharaan_v3 where id ='$id'"));
			foreach ($get as $key => $value) { 
			  $$key = $value; 
			}
			$concat = $f.".".$g.".".$h.".".$i.".".$j;
			$username = $_COOKIE['coID'];	 
			$execute = mysql_query("select * from rkbmd_pemeliharaan_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat'  and user = '$username'");	
			while($rows = mysql_fetch_array($execute)){
				foreach ($rows as $key => $value) { 
					  $$key = $value; 
				}
				mysql_query("delete from rkbmd_pemeliharaan_v3 where id='$id'");
			}
			
			$hitung = mysql_num_rows(mysql_query("select * from rkbm_pemeliharaan where user='$username'"));
			if($hitung > 0){
				$status	 = "refresh";
			}else{
				$status = "reload";
			}
			$content = $status;
					
		break;
		}
		
		case 'subSubSave':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			$kodeBarang = explode(".",$kodeBarang);
			$f = $kodeBarang[0];
			$g = $kodeBarang[1];
			$h = $kodeBarang[2];
			$i = $kodeBarang[3];
			$j = $kodeBarang[4];
			$data = array(
							'c1' => $c1,
							'c'  => $c,
							'd'	 => $d,
							'e'  => $e,
							'e1' => $e1,
							'bk' => $bk,
							'ck' => $ck,
							'dk' => '0',
							'p' => $p,
							'q' => $q,
							'f' => $f,
							'g'	=> $g,
							'h' => $h,
							'i' => $i,
							'j' => $j,
							'jumlah' => $volumeBarang,
							'id_jenis_pemeliharaan' => $idJenisPemeliharaan,
							'uraian_pemeliharaan' => $uraianPemeliharaan,
							'keterangan' => $keterangan,
							'user' => $_COOKIE['coID']
				
						 );
				$query=VulnWalkerUpdate('temp_rkbmd_pemeliharaan_v3',$data,"id = '$id'");
				
				$concat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$bk.".".$ck.".".$dk.".".$p.".".$q.".".$f.".".$g.".".$h.".".$i.".".$j;
				$cekSame = mysql_num_rows(mysql_query("select * from temp_rkbmd_pemeliharaan_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',dk,'.',p,'.',q,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and id_jenis_pemeliharaan ='$idJenisPemeliharaan' and id !='$id'"));
				
				if(empty($idJenisPemeliharaan)){
					$err = "Pilih Jenis Pemeliharaan";
				}elseif(empty($volumeBarang)){
					$err = "ISI Jumlah";
				}elseif($volumeBarang > $limit){
					$err = "Jumlah pemeliharaan tidak dapat melebihi total";
				}elseif($cekSame == 1){
					$err = "Jenis Pemeliharaan untuk kegiatan dan barang yang sama sudah ada ";
				}else{
					mysql_query($query);
				}
				
				$content = "select * from temp_rkbmd_pemeliharaan_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',dk,'.',p,'.',q,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and id_jenis_pemeliharaan ='$idJenisPemeliharaan' and id !='$id'";
						 
				
		break;
		}
		
		case 'subSubCancel':{	
		    $username = $_COOKIE['coID'];			
			mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where user = '$username' and (id_jenis_pemeliharaan = '' OR id_jenis_pemeliharaan = '0' )");				
		break;
		}
		case 'clear':{	
			 $username = $_COOKIE['coID'];
		  	 mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where user='$username'");
			 mysql_query("delete from rkbmd_pemeliharaan_v3 where user='$username'");
		break;
		}
		
		case 'subCancel':{	
		    $kodeBarang = $_REQUEST['kodeBarang'];	
			$username = $_COOKIE['coID'];	
			mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where user = '$username' and concat(f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'");				
		break;
		}
		
		case 'moveBack':{	
		    foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			$concat = $kodeBarang;
			$username =$_COOKIE['coID'];
			$execute = mysql_query("select * from temp_rkbmd_pemeliharaan_v3 where user = '$username' and (id_jenis_pemeliharaan != '0' or  id_jenis_pemeliharaan !='')");
			if(mysql_num_rows($execute) == 0){
				$err = "Data kosong !";
			}else{	
			mysql_query("delete from rkbmd_pemeliharaan_v3 where user = '$username' and concat(f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'");
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
									'dk' => $dk,
									'p' => $p,
									'q' => $q,
									'f' => $f,
									'g' => $g,
									'h' => $h,
									'i' => $i,
									'j' => $j,
									'satuan' => $satuan,
									'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
									'keterangan' => $keterangan,
									'uraian_pemeliharaan' => $uraian_pemeliharaan,
									'volume_barang' => $jumlah,
									'user' => $username
								  );
					 $query = VulnWalkerInsert('rkbmd_pemeliharaan_v3',$data);
					 mysql_query($query);
					 mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where id = '$id'");
					 $codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$bk' and ck = '$ck'  and p = '$p' and q ='$q' ";
					 $cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,' disabled','-- KEGIATAN --');  
				}
			}
			
			
			
		break;
		}
		
		case 'moveList':{	
		    $id = $_REQUEST['id'];	
			$username = $_COOKIE['coID'];	
			$get = mysql_fetch_array(mysql_query("select * from rkbmd_pemeliharaan_v3 where id = '$id'"));
			foreach ($get as $key => $value) { 
			  $$key = $value; 
			}
			$concat = $f.".".$g.".".$h.".".$i.".".$j;
			$execute = mysql_query("select * from rkbmd_pemeliharaan_v3 where user = '$username' and concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat'");
			mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where user = '$username'");
			while($rows = mysql_fetch_array($execute)){
				foreach ($rows as $key => $value) { 
			  		$$key = $value; 
				}
				$data = array('c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => $e,
							  'e1' => $e1,
							  'bk' => $bk,
							  'ck' => $ck,
							  'dk' => '0',
							  'p' => $p,
							  'q' => $q,
							  'f' => $f,
							  'g' => $g,
							  'h' => $h,
							  'i' => $i,
							  'j' => $j,
							  'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
							  'uraian_pemeliharaan' => $uraian_pemeliharaan,
							  'jumlah' => $volume_barang,
							  'keterangan' => $keterangan,
							  'user' => $user
							);
					$query = VulnWalkerInsert('temp_rkbmd_pemeliharaan_v3',$data);
					mysql_query($query);
					
			}
			
			$getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
			$namaBarang = $getNamaBarang['nm_barang'];
			$satuan = $satuan;
			$concat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$f.".".$g.".".$h.".".$i.".".$j;
			$getJumlah = mysql_fetch_array(mysql_query("select sum(jml_barang) as jumlah from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and (kondisi = '1' or  kondisi = '2' or  kondisi = '3')"));
			$jumlah = $getJumlah['jumlah'];
			$getBaik = $getJumlah = mysql_fetch_array(mysql_query("select sum(jml_barang) as jumlah from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '1' "));
			$baik = $getBaik['jumlah'];
			$getKurangBaik = $getJumlah = mysql_fetch_array(mysql_query("select sum(jml_barang) as jumlah from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '2' "));
			$kurangBaik = $getKurangBaik['jumlah'];
			$getRusakBerat = $getJumlah = mysql_fetch_array(mysql_query("select sum(jml_barang) as jumlah from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '3' "));
			$rusakBerat = $getRusakBerat['jumlah'];
			$content = array("kodeBarang" => $f.".".$g.".".$h.".".$i.".".$j , "namaBarang" => $namaBarang, "satuan" => $satuan, "jumlah" => $jumlah, "baik" => $baik, "kurangBaik" => $kurangBaik, "rusakBerat" =>$rusakBerat);
		break;
		}
		
		
		case 'subSimpan':{	
		    foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			  $$key = $value; 
			}	
			$username = $_COOKIE['coID'];
			$execute = mysql_query("select * from temp_rkbmd_pemeliharaan_v3 where user = '$username' and id_jenis_pemeliharaan != '0' and  id_jenis_pemeliharaan !=''");
			if(mysql_num_rows($execute) == 0){
				$err = "Data kosong !";
			}else{
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
									'dk' => $dk,
									'p' => $p,
									'q' => $q,
									'f' => $f,
									'g' => $g,
									'h' => $h,
									'i' => $i,
									'j' => $j,
									'satuan' => $satuan,
									'id_jenis_pemeliharaan' => $id_jenis_pemeliharaan,
									'keterangan' => $keterangan,
									'uraian_pemeliharaan' => $uraian_pemeliharaan,
									'volume_barang' => $jumlah,
									'user' => $username
								  );
					 $query = VulnWalkerInsert('rkbmd_pemeliharaan_v3',$data);
					 mysql_query($query);
					 mysql_query("delete from temp_rkbmd_pemeliharaan_v3 where id = '$id'");
					 $codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$bk' and ck = '$ck'  and p = '$p' and q ='$q' ";
					 $cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,' disabled','-- KEGIATAN --');  
				}
			}
			
			
			
			
			$content =array("q" => $cmbKegiatan);
						
		break;
		}

		
		case 'subShowEdit':{
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			$get = mysql_fetch_array(mysql_query("select * from temp_rkbmd_pengadaan where id = '$id'"));
			foreach ($get as $key => $value) { 
			  $$key = $value; 
			}
		    $concat2 = $f.".".$g.".".$h.".".$i.".".$j;
			$concat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$f.".".$g.".".$h.".".$i.".".$j ; 
       	    $getKebutuhanMaksimal = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
		 
		 
		   $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and (kondisi = '1' or kondisi = '2') "));	
		   $kebutuhanOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];	
		   $getBarang = mysql_fetch_array( mysql_query("select * from ref_barang_v3 where   f=$f and g=$g and h=$h and i=$i and j=$j"));
		   $content = array('kodeBarang' => $concat2,'jumlah' => $jumlah, 'keterangan' => $catatan, 'jumlahKebutuhanOptimal' => $kebutuhanOptimal, 'jumlahKebutuhanMaksimal' => $getKebutuhanMaksimal['jumlah'], 'namaBarang' => $getBarang['nm_barang'], 'satuan' => $getBarang['satuan']);
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
			"<script type='text/javascript' src='js/perencanaan_v3/rkbmd/rkbmd_pemeliharaan.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan_v3/rkbmd/popupProgram.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/rkbmd/rkbmd.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan_v3/rkbmd/popupBarangPemeliharaan.js' language='JavaScript' ></script>
			
			
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
  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>		
	   <th class='th01' width='100' rowspan='2' colspan='1'>KODE BARANG</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>NAMA BARANG</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>JUMLAH</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>SATUAN</th>
	   <th class='th02' width='1000' rowspan='1' colspan='3'>KONDISI</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>STATUS BARANG</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>JENIS PEMELIHARAAN</th>
	   <th class='th01' width='200' rowspan='2' colspan='1'>NAMA PEMELIHARAAN</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>JUMLAH</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>SATUAN</th>
	   <th class='th01' width='100' rowspan='2' colspan='1'>AKSI</th>
	   
	   
	   </tr>
	   <tr>
	   <th class='th01' width='150' >B</th>
	   <th class='th01' width='150'> KB</th>
	   <th class='th01' width='150'> RB</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	foreach ($isi as $key => $value) { 
			  $$key = $value; 
			}
    $username = $_COOKIE['coID'];
	$Koloms = array();
	$concat2 = $f.".".$g.".".$h.".".$i.".".$j;
	$getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat2'"));
	$concat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$f.".".$g.".".$h.".".$i.".".$j ; 
	$getKondisiBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as baik from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '1'" ));		
	$baik = $getKondisiBaik['baik'];
	$getKondisiKurangBaik = mysql_fetch_array(mysql_query("select sum(jml_barang) as kurangBaik from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '2'" ));		
	$kurangBaik = $getKondisiKurangBaik['kurangBaik'];
	$getKondisiRusakBerat = mysql_fetch_array(mysql_query("select sum(jml_barang) as rusakBerat from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and kondisi = '3'" ));		
	$rusakBerat = $getKondisiRusakBerat['rusakBerat'];
	$getJenisPemeliharaan = mysql_fetch_array(mysql_query("select * from ref_jenis_pemeliharaan where Id='$id_jenis_pemeliharaan'"));
	
	
	$hitungData = mysql_num_rows(mysql_query("select * from rkbmd_pemeliharaan_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat2' and user = '$username'"));
	if($hitungData > 1){
		$getMin = mysql_fetch_array(mysql_query("select min(concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f,'.',g,'.',h,'.',i,'.',j,'.',id_jenis_pemeliharaan)) as min from rkbmd_pemeliharaan_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat2' and user = '$username'"));
		$min = $getMin['min'];
		$realConcat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$bk.".".$ck.".".$p.".".$q.".".$f.".".$g.".".$h.".".$i.".".$j.".".$id_jenis_pemeliharaan; 
		if($realConcat == $min){
			$getTotal = mysql_fetch_array(mysql_query("select sum(volume_barang) as total from rkbmd_pemeliharaan_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat2' and user='$username'"));
			$Koloms[] = array('align="center" width="20"', $no.'.' );
   			$Koloms[] = array('align="center"',$f.".".$g.".".$h.".".$i.".".$j );
			$Koloms[] = array('align="left"', $getNamaBarang['nm_barang'] );
			$Koloms[] = array('align="right"', number_format($getTotal['total'],0,',','.') );
			$Koloms[] = array('align="left"', $satuan );
			$Koloms[] = array('align="right"', number_format($baik,0,',','.') );
			$Koloms[] = array('align="right"', number_format($kurangBaik,0,',','.') );
			$Koloms[] = array('align="right"', number_format($rusakBerat,0,',','.') );
			$Koloms[] = array('align="center"', "MILIK" );
			$aksi  = "<img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.subHapus('$id');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.moveList('$id');></img> ";
		}else{
			$Koloms[] = array('align="center" width="20"', '' );
   			$Koloms[] = array('align="center"', '' );
			$Koloms[] = array('align="left"', '' );
			$Koloms[] = array('align="right"','' );
			$Koloms[] = array('align="left"','' );
			$Koloms[] = array('align="right"', '' );
			$Koloms[] = array('align="right"', '' );
			$Koloms[] = array('align="right"', '' );
			$Koloms[] = array('align="center"', '' );
		}
	}else{
		$Koloms[] = array('align="center" width="20"', $no.'.' );
   		$Koloms[] = array('align="center"', $f.".".$g.".".$h.".".$i.".".$j );
		$Koloms[] = array('align="left"', $getNamaBarang['nm_barang'] );
		$Koloms[] = array('align="right"', number_format($volume_barang,0,',','.') );
		$Koloms[] = array('align="left"', $satuan );
		$Koloms[] = array('align="right"', number_format($baik,0,',','.') );
		$Koloms[] = array('align="right"', number_format($kurangBaik,0,',','.') );
		$Koloms[] = array('align="right"', number_format($rusakBerat,0,',','.') );
		$Koloms[] = array('align="center"', "MILIK" );
		$aksi  = "<img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.subHapus('$id');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.moveList('$id');></img> ";
	}
	
	
	
	$Koloms[] = array('align="center"', $getJenisPemeliharaan['jenis'] );
	$Koloms[] = array('align="left"', $uraian_pemeliharaan );
	$Koloms[] = array('align="right"', number_format($volume_barang,0,',','.') );
	$Koloms[] = array('align="left"', $satuan );
	$Koloms[] = array('align="center"', $aksi );
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
		
		$cbid = $_REQUEST['rkbmd_cb'];
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
					<input type='hidden' name='ID_RENJA' value='".$_REQUEST['id']."' />
					<input type='hidden' name='ID_rkbmd' value='".$_REQUEST['ID_rkbmd']."' />".
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
	function tabelPemeliharaan(){
		$cek = '';
		$err = '';
		$jml_harga=0;
		$datanya='';
		$username = $_COOKIE['coID'];
		$refid_terima = addslashes($_REQUEST[$this->Prefix."_idplh"]);
		$qry = "select * from temp_rkbmd_pemeliharaan_v3 where id_jenis_pemeliharaan != '' and user = '$username'";$cek.=$qry;
		$aqry = mysql_query($qry);
		$no=1;
		$status =$_REQUEST['status'];
		if($status == 1){
			$tujuan = "newPemeliharaan()";
			$gambar = "datepicker/add-256.png";
		}else{
			$tujuan = "subSubCancel()";
			$gambar = "datepicker/cancel.png";
		}
		
		while($dt = mysql_fetch_array($aqry)){
		    $id = $dt['id'];
			$codeAndNameJenisPemeliharaan = "select Id, jenis from ref_jenis_pemeliharaan";
			
			if($dt['id_jenis_pemeliharaan'] == '' || $dt['id_jenis_pemeliharaan'] == '0'){
				$action = "<img id='action$id' src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.subSubSave('$id');>";
				$cmbJenisPemeliharaan = cmbQuery('jenisPemeliharaan'.$id, $dt['id_jenis_pemeliharaan'], $codeAndNameJenisPemeliharaan,' ','-- JENIS PEMELIHARAAN --');  
				$uraianPemeliharaan = "<input type = 'text' id='uraianPemeliharaan$id' name='uraianPemeliharaan$id' style='width:100%;'>";
				$jumlah = "<input type='text' id='jumlah$id' name='jumlah$id' onkeypress='return event.charCode >= 48 && event.charCode <= 57' style='text-align:right;'>";
				$keterangan2 = "<input type = 'text' id='keterangan$id' name='keterangan$id' style='width:100%;'>";
			}else{
				foreach ($dt as $key => $value) { 
				  $$key = $value; 
				}
				$action = "<img id='action$id' src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan_v3.subSubDel('$id');>";
				$cmbJenisPemeliharaan = cmbQuery('jenisPemeliharaan'.$id, $dt['id_jenis_pemeliharaan'], $codeAndNameJenisPemeliharaan,' disabled ','-- JENIS PEMELIHARAAN --');  
				$uraianPemeliharaan = "<input type = 'text' id='uraianPemeliharaan$id' name='uraianPemeliharaan$id' value='$uraian_pemeliharaan' readonly style='width:100%;'>";
				$jumlah = "<input type='text' id='jumlah$id' name='jumlah$id' value='$jumlah' onkeypress='return event.charCode >= 48 && event.charCode <= 57' readonly style='text-align:right;'> ";
				$keterangan2 = "<input type = 'text' id='keterangan$id' name='keterangan$id' value='$keterangan' readonly style='width:100%;'>";
			}
			$datanya.="
			
				<tr class='row0'>
					<td class='GarisDaftar' align='right'><a onclick=rkbmd_pemeliharaan_v3.subSubEdit($id) style='cursor:pointer;'>$no</a></td>
					<td class='GarisDaftar' align='center'>
						$cmbJenisPemeliharaan
					</td>
					<td class='GarisDaftar'>
						$uraianPemeliharaan
					</td>
					<td class='GarisDaftar' align='center'>
						$jumlah
					</td>
					<td class='GarisDaftar' align='center'>
						$keterangan2
					</td>
					<td class='GarisDaftar' align='center'>
						$action
					</td>
				</tr>
			";
			$no = $no+1;
		}
		
						
					
		$content['tabel'] =
			genFilterBar(
				array("
					<table class='koptable' style='min-width:900px;' border='1'>
						<tr>
							<th class='th01'>NO</th>
							<th class='th01'>JENIS PEMELIHARAAN</th>
							<th class='th01'>URAIAN PEMELIHARAAN</th>
							<th class='th01'>JUMLAH  </th>
							<th class='th01'>KETERANGAN  </th>
							<th class='th01'>
								<span id='atasbutton'>
								<a href='javascript:rkbmd_pemeliharaan_v3.$tujuan' id='linkAtasButton' /><img id='gambarAtasButton' src='$gambar' style='width:20px;height:20px;' /></a>
								</span>
							</th>
						</tr>
						$datanya
						
					</table>"
				)
			,'','','')
		;
		$content['jumlah'] = 
				$this->isiform(
						array(
								array(
									'label'=>'TOTAL BELANJA',
									'label-width'=>'200px;',
									'name'=>'totalbelanja',
									'value'=>"<input type='text' name='totalbelanja' id='totalbelanja' value='".number_format($jml_harga,2,",",".")."' style='width:150px;text-align:right' readonly /><span id='jumlahsudahsesuai'><input type='checkbox' name='jumlah_sesuai' value='1' id='jumlah_sesuai' style='margin-left:20px;' disabled /><span style='font-weight:bold;color:red;'>TOTAL HARGA SESUAI</span></span>",
									
								),
						)
				);
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS;

	$ID_RENJA = $_REQUEST['ID_RENJA'];
	$ID_rkbmd = $_REQUEST['ID_rkbmd'];
	$Syntax_ambil_yang_udah_ada_plafon = "select * from tabel_anggaran_v3 where id_anggaran='$ID_RENJA'";
	$ambilSKPD = mysql_fetch_array(mysql_query($Syntax_ambil_yang_udah_ada_plafon));
	$c1  = $ambilSKPD['c1'];
	$c   = $ambilSKPD['c'];
	$d   = $ambilSKPD['d'];
	$e   = $ambilSKPD['e'];
	$e1  = $ambilSKPD['e1'];
	$tahunAnggaran = $ambilSKPD['tahun'];
	$tanggalSekarang = str_replace("-","",date("Y-m-d"));
	$jenisTransaksi = $ambilSKPD['jenis_anggaran'];
	$plafon = $ambilSKPD['jumlah']; 
	$tujuan = "Simpan()";
	if($plafon == ''){
		$disablePlafon = "";
	}else{
		$disablePlafon = "readonly";
		
	}

	$readOnlyJenisKegiatanPlus = "readonly";
	$readOnlyJenisKegiatanMin = "readonly";
	$disabledCariProgram = "";
	$arrayNameUrusan = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='00' and d='00' and e='00' and e1='000'"));
	$namaUrusan = $arrayNameUrusan['nm_skpd'];
	
	$arrayNameBidang = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='00' and e='00' and e1='000'"));
	$namaBidang = $arrayNameBidang['nm_skpd'];
	
	$arrayNameSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='00' and e1='000'"));
	$namaSKPD = $arrayNameSKPD['nm_skpd'];
	
	$arrayNameUNIT = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='$e' and e1='000'"));
	$namaUnit = $arrayNameSKPD['nm_skpd'];
	
	$arrayNameSUBUNIT = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1'"));
	$namaSubUnit = $arrayNameSKPD['nm_skpd'];
	
 

	$selectedBK = $_REQUEST['bk'];
	$selectedCK = $_REQUEST['ck'];
	$selectedP = $_REQUEST['p'];
	$program = $_REQUEST['program'];
	$q = $_REQUEST['q'];
	$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$selectedBK' and ck = '$selectedCK '  and p = '$selectedP' and q !='0' ";
	$cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,'onchange=rkbmd_pemeliharaan_v3.CekAda(); ','-- KEGIATAN --'); 
	$username = $_COOKIE['coID'];
	$matiButton = "";
	$syntax = mysql_query("select * from rkbmd_pemeliharaan_v3 where user = '$username'");
	$cekRow = mysql_num_rows($syntax);
	if($cekRow > 0){
		$getRKBMD = mysql_fetch_array($syntax);
		foreach ($getRKBMD as $key => $value) { 
		  $$key = $value; 
	 	}
		$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$bk' and ck = '$ck'  and p = '$p' and q ='$q' ";
		$cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,' disabled','-- KEGIATAN --'); 
		$selectedBK = $bk;
		$selectedCK = $ck;
		$selectedP = $p;
		$matiButton = "disabled";
		$getProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk =$bk and ck = $ck and dk=0 and p = $p and q =0 "));
		$program = $getProgram['nama'];
	}
	
	  
	$filterSKPD = $c1.".".$c.".".$d.".".$e.".".$e1;
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
							),
							array(
								'label'=>'UNIT',
								'name'=>'unit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e.'. '.$namaUnit,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'SUB UNIT',
								'name'=>'subunit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e1.'. '.$namaSubUnit,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							
						)
					)
				
				),'','','').
				genFilterBar(
				array(
					$this->isiform(
						array(
							
							array( 
								'label'=>'PROGRAM',
								'label-width'=>'200px;',
								'value'=>"<input type='hidden' name='tahunAnggaran' id='tahunAnggaran' value='$tahunAnggaran'>
										  <input type='hidden' name='bk' id='bk' value='$selectedBK'>
										  <input type='hidden' name='ck' id='ck' value='$selectedCK'>
										  <input type='hidden' name='dk' id='dk' value='0'>
										  <input type='hidden' name='p' id='p' value='$selectedP'>
								<input type='text' name='program' value='$program' style='width:600px;' id='program' readonly>&nbsp
								<input type='button' value='Cari' id='findProgram' onclick ='rkbmd_pemeliharaan_v3.CariProgram($ID_RENJA)'  title='Cari Program dan Kegiatan' $matiButton >"				
							),
							array( 
								'label'=>'KEGIATAN',
								'label-width'=>'200px;',
								'value'=>$cmbKegiatan			
							),
						)
					)
				
				),'','','').
			genFilterBar(
				array(
					"<span id='inputpenerimaanbarang' style='color:black;font-size:14px;font-weight:bold;'/>INPUT NAMA BARANG</span>",
					
				
				),'','','').	
				genFilterBar(
				array(
					$this->isiform(
						array(
							
							array(
									'label'=>'KODE BARANG',
									'label-width'=>'200px;',
									'value'=>"<input type='text' name='kodeBarang' onkeyup='cariBarang.pilBar2(this.value)' id='kodeBarang' placeholder='KODE BARANG' style='width:150px;' value='".$dt['kodebarangnya']."' readonly /> 
										<input type='text' name='namaBarang' id='namaBarang' placeholder='NAMA BARANG' style='width:445px;' readonly value='".$dt['nm_barang']."' />
										<button type='button' id='findBarang' onclick=rkbmd_pemeliharaan_v3.CariBarang('$filterSKPD'); > Cari </button>
									",
								),
							array(
									'label'=>'JUMLAH',
									'label-width'=>'200px;',
									'value'=>"<input type='text' name='jumlah' id='jumlah' readonly style='width:50px;'  /> 
										 <input type='text' name='satuanBarang' id='satuanBarang' placeholder='SATUAN' style='width:250px;'   />
										
									",
								),
							array(  'name' => "hubla",
									'label'=>'KONDISI BARANG',
									'label-width'=>'200px;',
								    'value' => "<span> BAIK </span>&nbsp&nbsp<input type='text' id='baik' style='width:40px;' readonly> &nbsp&nbsp <span> KURANG BAIK </span>&nbsp&nbsp<input type='text' id='kurangBaik' style='width:40px;' readonly>&nbsp&nbsp <span> RUSAK BERAT </span>&nbsp&nbsp<input type='text' id='rusakBerat' style='width:40px;' readonly> &nbsp&nbsp "
									
								),
						)
					)
				
				),'','','').
				"<div id='tbl_pemeliharaan' style='width:100%;'></div>".
				genFilterBar(
					array(
					"
						<input type='hidden' name='".$this->Prefix."_idplh' id='".$this->Prefix."_idplh' value='$idplhnya' />
					<input type='hidden' name='refid_terimanya' id='refid_terimanya' value='".$dt['Id']."' />
					<input type='hidden' name='FMST_penerimaan_det' id='FMST_penerimaan_det' value='".$dt['FMST_penerimaan_det']."' />
					<table>
						<tr>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' href='javascript:rkbmd_pemeliharaan_v3.$tujuan'> 
						<img src='images/administrator/images/save_f2.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> SIMPAN</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btcancel' href='javascript:rkbmd_pemeliharaan_v3.closeTab()'> 
						<img src='images/administrator/images/cancel_f2.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> BATAL</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='finish' href='javascript:rkbmd_pemeliharaan_v3.finish()'> 
						<img src='images/administrator/images/checkin.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> SELESAI</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							
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
		$username = $_COOKIE['coID'];		
		$arrKondisi = array();		
		
		
		$arrKondisi[] = "user  = '$username'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " c1,c,d,e,e1,bk,ck,p,q,f,g,h,i,j,id_jenis_pemeliharaan $Asc1 " ;

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
$rkbmd_pemeliharaan_v3 = new rkbmd_pemeliharaan_v3Obj();

$arrayResult = tahapPerencanaanV3($rkbmd_pemeliharaan_v3->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$rkbmd_pemeliharaan_v3->jenisForm = $jenisForm;
$rkbmd_pemeliharaan_v3->nomorUrut = $nomorUrut;
$rkbmd_pemeliharaan_v3->urutTerakhir = $nomorUrut;
$rkbmd_pemeliharaan_v3->tahun = $tahun;
$rkbmd_pemeliharaan_v3->jenisAnggaran = $jenisAnggaran;
$rkbmd_pemeliharaan_v3->idTahap = $idTahap;
$rkbmd_pemeliharaan_v3->username = $_COOKIE['coID'];
$rkbmd_pemeliharaan_v3->wajibValidasi = $Main->wajibValidasi;
if($rkbmd_pemeliharaan_v3->wajibValidasi == TRUE){
	$rkbmd_pemeliharaan_v3->sqlValidasi = " and status_validasi ='1' ";
}else{
	$rkbmd_pemeliharaan_v3->sqlValidasi = " ";
}

?>