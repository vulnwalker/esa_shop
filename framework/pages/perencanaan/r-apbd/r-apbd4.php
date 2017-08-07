<?php

class r_apbd4Obj  extends DaftarObj2{	
	var $Prefix = 'r_apbd4';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'view_r_apbd4'; //bonus
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
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='r_apbd4.xls';
	var $namaModulCetak='RKA';
	var $Cetak_Judul = 'RKA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'r_apbd4Form';
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
		return 'R-APBD '.$this->jenisAnggaran.' TAHUN '.$this->tahun;
	}
	function setMenuView(){
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Laporan()","print_f2.png",'Laporan',"Laporan")."</td>";				
			
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
	function genRowSum($ColStyle, $Mode, $Total){
		foreach ($_REQUEST as $key => $value) { 
		  	$$key = $value; 
		 } 
		 if($cmbSubUnit != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit'";
		if(!empty($hiddenP)){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP'";
					if(!empty($q)){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q'";
		}
		}						
		}elseif($cmbUnit != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' ";
		}elseif($cmbSKPD != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
		}elseif($cmbBidang != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
		}elseif($cmbUrusan != ''){
			$kondisiSKPD = "and c1='$cmbUrusan'";
		}
	 	
		if(!empty($this->jenisForm)){
			$idTahap = $this->idTahap;
		}else{
			$getIdTahapRKATerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from tabel_anggaran where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and jenis_rka !='' and o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) "));
		 	$idTahap = $getIdTahapRKATerakhir['max'];
		}

		$getAllKegiatan = mysql_query("select * from tabel_anggaran where id_tahap='$idTahap' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' $kondisiSKPD ");
		while($got = mysql_fetch_array($getAllKegiatan)){
			$gotC1= $got['c1'];
			$gotC= $got['c'];
			$gotD= $got['d'];
			$gotE = $got['e'];
			$gotE1 = $got['e1'];
			$gotQ= $got['q'];
			$getIdAwalRenja = mysql_fetch_array(mysql_query("select min(id_anggaran) as idAwalRenja from view_renja where c1='$gotC1' and c='$gotC' and d='$gotD' and e='$gotE' and e1='$gotE1' and bk='$bk' and ck='$ck' and p='$p' and q='$gotQ' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'  "));
			$idAwalRenja = $getIdAwalRenja['idAwalRenja'];
			$getDetailRenja = mysql_fetch_array(mysql_query("select * from detail_renja where id_anggaran ='$idAwalRenja'"));
			$sumTahunPlus = $sumTahunPlus + $getDetailRenja['plus'];
		}
		$tahunPlus = number_format($sumTahunPlus ,2,',','.');
		$getData = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' $kondisiSKPD $kondisiRekening"));
		$Total = $getData['sum(jumlah_harga)'];
		$getTotalPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='1' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaPegawai = $getTotalPegawai['sum(jumlah_harga)'];
		$getTotalBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='2' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaBarangJasa = $getTotalBarangJasa['sum(jumlah_harga)'];
		$getTotalModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and id_tahap='$idTahap' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='3' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaModal = $getTotalModal['sum(jumlah_harga)'];
		$ContentTotalHal=''; $ContentTotal='';
			$TampilTotalHalRp = number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='3' align='center'><b>Total</td>": '';
				$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaPegawai,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaBarangJasa,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaModal,2,',','.')."</div></td>
					<td class='GarisDaftar' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($Total,2,',','.')."</div></td>
				</tr>" ;

			

				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		return $ContentTotalHal.$ContentTotal;
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
	
	$getJumlahBarang = mysql_fetch_array(mysql_query("select * from tabel_anggaran where id_anggaran = '$r_apbd4_idplh'"));
	$jumlahBarang = $getJumlahBarang['volume_barang'];
	$total = $hargaSatuan * $jumlahBarang;
	
	/* $getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja "));
  	 $idTahapRenja = $getIdTahapRenjaTerakhir['max'];
	$getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and id_tahap = '$idTahapRenja' "));*/
	$getPaguYangTelahTerpakai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai from view_r_apbd4 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran'  and no_urut = '$this->nomorUrut' and id_anggaran!='$r_apbd4_idplh' "));
	$sisaPaguIndikatif = $paguIndikatif - $getPaguYangTelahTerpakai['paguYangTerpakai'];
    
	 if(mysql_num_rows(mysql_query("select * from view_r_apbd4 where c1='0' and f1 = '0' and k = '$k' and l ='$l' and m='$m' and n='$n' and o='$o'  and id_tahap='$this->idTahap' ")) > 0){
				 	
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
		$query = VulnWalkerUpdate('tabel_anggaran',$data,"id_anggaran = '$r_apbd4_idplh'");
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
		case 'Laporan':{	
			$json = FALSE;
			$this->Laporan();										
		break;
		}		
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
		case 'clearTemp':{
				$username =$_COOKIE['coID'];
				mysql_query("delete from temp_rka_221 where user ='$username'");							
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
			$getr_apbd4nya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getr_apbd4nya as $key => $value) { 
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
			$getr_apbd4nya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getr_apbd4nya as $key => $value) { 
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
			 $getSisaPagu = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai  from view_r_apbd4 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and p='$p' and q='$q' and id_tahap='$this->idTahap' and concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j,'.',id_jenis_pemeliharaan,'.',k,'.',l,'.',m,'.',n,'.',o) != '$filterPagu'  and o1 !='0' and rincian_perhitungan !=''"));
			 $sisaPagu = $paguIndikatif - $getSisaPagu['paguYangTerpakai'];
			 $content .= "Sisa PAGU : ".$sisaPagu;
			 $content .= "getSIsaPAGU nya : "."select sum(jumlah_harga) as paguYangTerpakai  from view_r_apbd4 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and p='$p' and q='$q' and id_tahap='$this->idTahap' and concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',bk,'.',ck,'.',p,'.',q,'.',f1,'.',f2,'.',f,'.',g,'.',h,'.',i,'.',j,'.',id_jenis_pemeliharaan,'.',k,'.',l,'.',m,'.',n,'.',o) != '$filterPagu' ";
			 if($hasilKali > $sisaPagu){
			 	$err = "Tidak dapat melebihi Pagu Indikatif";
			 }
			 if($err == ""){
				 	if(mysql_num_rows(mysql_query("select * from view_r_apbd4 where o1='0' and rincian_perhitungan ='' and c1='0' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and id_tahap='$this->idTahap'  ")) > 0){
				 	
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
										"jenis_rka" => '2.2.1',
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
					 if(mysql_num_rows(mysql_query("select * from view_r_apbd4 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and k = '$k' and l ='$l' and m='$m' and n='$n' and o='$o'  and o1='$o1' and f1='0' and rincian_perhitungan ='' and id_tahap='$this->idTahap' ")) > 0){
				 	
						 }else{
							$arrayPekerjaan = array(
												'c1' => $c1,
												'c' => $c,
												'd' => $d,
												'e' => $e,
												'e1' => $e1,
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
												'o1' => $o1,
												'rincian_perhitungan' => '',
												'jenis_rka' => '2.2.1',
												'tahun' => $this->tahun,
												'jenis_anggaran' => $this->jenisAnggaran,
												'id_tahap' => $this->idTahap,
												'nama_modul' => 'RKA-SKPD'
												);
							$queryPekerjaan = VulnWalkerInsert('tabel_anggaran',$arrayPekerjaan);
							mysql_query($queryPekerjaan);
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
										 "o1" => $o1,
										 "rincian_perhitungan" => $rincian_perhitungan,
										 "jumlah" => $koreksiSatuanHarga,
										 "volume_rek" => $koreksiVolumebarang,
										 "jumlah_harga" => $hasilKali,
										 "jenis_anggaran" => $this->jenisAnggaran,
										 "jenis_rka" => '2.2.1',
										 "id_tahap" => $this->idTahap,
										 "nama_modul" => $this->modul,
										 "user_update" => $_COOKIE['coID'],
										 "tanggal_update" => date("Y-m-d")
		 								);			
					
					$cekRKA =  mysql_num_rows(mysql_query("select * from view_r_apbd4 where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' and o1='$o1' and rincian_perhitungan = '$rincian_perhitungan'"));
							if($cekRKA > 0 ){
								$getID = mysql_fetch_array(mysql_query("select * from view_r_apbd4 where c1 = '$c1' and c='$c' and d = '$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p = '$p' and q='$q' and f1='$f1' and f2='$f2' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and id_jenis_pemeliharaan = '$id_jenis_pemeliharaan' and k='$k' and l='$l' and m='$m' and n='$n' and o='$o' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and id_tahap = '$this->idTahap' "));
							    $idnya = $getID['id_anggaran'];
								mysql_query("update tabel_anggaran set jumlah = '$koreksiSatuanHarga', volume_rek ='$koreksiVolumebarang', jumlah_harga = '$hasilKali' where id_anggaran='$idnya'");
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
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$r_apbd4_idplh'");
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
		
		
		case 'editTab':{
			$id = $_REQUEST['r_apbd4_cb'];
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			
			$username = $_COOKIE['coID'];

			$get = mysql_fetch_array(mysql_query("select * from tabel_anggaran where id_anggaran ='$id[0]'"));		
			$kodeRek = $get['k'].".".$get['l'].".".$get['m'].".".$get['n'].".".$get['o'] ;
			
			$getAll = mysql_query("select * from view_r_apbd4 where concat(k,'.',l,'.',m,'.',n,'.',o) ='$kodeRek' and id_tahap='$this->idTahap' and rincian_perhitungan !=''   and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' order by o1, rincian_perhitungan");
			mysql_query("delete from temp_rka_221 where user='$username'");
			mysql_query("delete from temp_rincian_volume where user='$username'");
		    mysql_query("delete from temp_alokasi_rka where user='$username'");
			$noUrutPekerjaan = 0;
			$angkaO2 = 0;
			$lastO1 = 0;
			while($rows = mysql_fetch_array($getAll)){
				foreach ($rows as $key => $value) { 
				  $$key = $value; 
				} 
				$getMaxID = mysql_fetch_array(mysql_query("select  max(id) as gblk from temp_rka_221 where user ='$username'"));
				$maxID = $getMaxID['gblk'];
				$lastO1 = $o1;
				
				$getLastO1 = mysql_fetch_array(mysql_query("select o1 from temp_rka_221 where id='$maxID' "));
				if($getLastO1['o1'] != $lastO1){
					$noUrutPekerjaan = $noUrutPekerjaan + 1;
					if($o1 == '0'){
						$noUrutPekerjaan = 0;
					}
					$angkaO2 = 1;
				}
				
			 	if($o1 != '0'){
					if(mysql_num_rows(mysql_query("select * from temp_rka_221 where  o1 ='$o1' and rincian_perhitungan ='' and user ='$username'")) > 0){
			 	
			 	}else{
			 	$data = array( 'c1' => $c1,
							   'c' => $c,
							   'd' => $d,
							   'e' => $e,
							   'e1' => $e1,
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
							   'o1' => $o1,
							   'o2' => 0,
							   'urut' => $noUrutPekerjaan,
							   'user' => $username
							   
						 );
					mysql_query(VulnWalkerInsert("temp_rka_221",$data)); 
				 }
				}
				$data = array(
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
								'k' => $k,
								'l' => $l,
								'm' => $m,
								'n' => $n,
								'o' => $o,
								'o1' => $o1,
								'o2' => $angkaO2,
								'urut' => $noUrutPekerjaan,
								'volume_rek' => $volume_rek,
								'satuan' => $satuan_rek,
								'user' => $username,
								'rincian_perhitungan' => $rincian_perhitungan,
								'jan' => $alokasi_jan,
								'feb' => $alokasi_feb,
								'mar' => $alokasi_mar,
								'apr' => $alokasi_apr,
								'mei' => $alokasi_mei,
								'jun' => $alokasi_jun,
								'jul' => $alokasi_jul,
								'agu' => $alokasi_agu,
								'sep' => $alokasi_sep,
								'okt' => $alokasi_okt,
								'nop' => $alokasi_nop,
								'des' => $alokasi_des,
								'harga_satuan' => $jumlah,
								'jumlah_harga' => $jumlah_harga,
								'jenis_alokasi_kas' => $jenis_alokasi_kas,
								'jumlah1' => $jumlah1,
								'jumlah2' => $jumlah2,
								'jumlah3' => $jumlah3,
								'jumlah4' => $jumlah4,
								'satuan1' => $satuan1,
								'satuan2' => $satuan2,
								'satuan3' => $satuan3,
								'satuan_total' => $satuan_total,
								'id_awal' => $id_anggaran
								);
				$query = VulnWalkerInsert('temp_rka_221',$data);
				mysql_query($query);
				$angkaO2 = $angkaO2 + 1;
				
			}
				
				$content = array('kodeRek' => $kodeRek);
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
	<A href=\"pages.php?Pg=r-apbd\" title='R-APRBD'  > LAMPIRAN II </a> |
	<A href=\"pages.php?Pg=r-apbd4\" title='R-APRBD' style='color : blue;' > LAMPIRAN IV </a> |
	<A href=\"pages.php?Pg=r-apbd5\" title='R-APRBD' > LAMPIRAN V </a> |
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
			<script type='text/javascript' src='js/perencanaan/r-apbd/r-apbd4.js' language='JavaScript' ></script> 
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
						array("2.2.1","RKA-SKPD 2.2"),
						array("2.1","RKA-SKPD 2.1")
						
						);
		 $jenis_rka = $jenis_rka;
		 $cmbJenisRKA = cmbArray('cmbJenisRKAForm',$jenis_rka,$arrayJenisRKA,'-- JENIS RKA --','onchange=r_apbd4.unlockFindRekening();');
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
						 <button type='button' id='findRekening' onclick=r_apbd4.findRekening('$jenis_rka'); $tergantungJenis> CARI </button> "
						 ),
			'kode8' => array( 
						'label'=>'HARGA SATUAN',
						'labelWidth'=>150, 
						'value'=> "<input type='text' name='hargaSatuan' id='hargaSatuan' value='$hargaSatuan' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='r_apbd4.bantu();' > <span id='bantu' style='color:red;'> </span>"
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
	 
		$headerTable =
		  "<thead>
		   <tr>
	  	   <th class='th01' width='5' rowspan='2'  >No.</th>	
		   <th class='th01' width='100'  rowspan='2'  >KODE</th>
		   <th class='th01' width='500'  rowspan='2' >URAIAN</th>
		   <th class='th02' colspan='3'  rowspan='1' width='500' >JUMLAH BELANJA</th>
		   <th class='th01' rowspan='2'>JUMLAH</th>
		   </tr>

		   <tr>
		   <th class='th01' rowspan='1'>BELANJA PEGAWAI</th>
		   <th class='th01' rowspan='1'>BELANJA BARANG & JASA</th>
		   <th class='th01' rowspan='1'>BELANJA MODAL</th>

		   </tr>
		   </thead>";
	 
	
	 	
	 $NomorColSpan = $Mode==1? 2: 1;
	 
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  			$$key = $value; 
	 }
	 foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 }
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
		
		
		
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 
	 if($q !='0'){
	 	$Koloms[] = array('align="left"', $c1.'.'.$c.'.'.$d.'.'.genNumber($bk).'.'.genNumber($ck).'.'.genNumber($p).'.'.genNumber($q) );	
	 	$getNamaKegiatan = mysql_fetch_array(mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='0' and p='$p' and q='$q'"));
		$namaKegiatan =  $getNamaKegiatan['nama'];
	 	$Koloms[] = array('align="left"', "<span style='margin-left:20px;'>".$namaKegiatan."</span>" );
		$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='1' "));
	   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='2' "));
	 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='3' "));
		$Koloms[] = array('align="right"',"<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>" );
		$Koloms[] = array('align="right"',"<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>" );
		$Koloms[] = array('align="right"',"<span style=''>" . number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>" );
		$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>" );
	 }elseif($q == '0' && $p !='0'){
	 	$Koloms[] = array('align="left"', $c1.'.'.$c.'.'.$d.'.'.genNumber($bk).'.'.genNumber($ck).'.'.genNumber($p) );	
		$getNamaProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='0' and p='$p' and q='0'"));
		$namaProgram =  $getNamaProgram['nama'];
	 	$Koloms[] = array('align="left"', "<span style='font-weight:bold;margin-left:15px;'>".$namaProgram."</span>" );
		$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='1' "));
	   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='2' "));
	 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p' and k='5' and l='2' and m='3' "));
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaModal['belanjaModal'] ,2,',','.') );
		$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
		$Koloms[] = array('align="right"', number_format($jumlahRekening ,2,',','.') );
	 }elseif($p == '0' && $d !='00'){
	 	$Koloms[] = array('align="left"', $c1.'.'.$c.'.'.$d );	
		$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='$c' and d='$d' and e='00' and e1='000'"));
		$Koloms[] = array('align="left"', "<span style='font-weight:bold;margin-left:10px;'>".$getSKPD['nm_skpd']."</span>" );
		$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter   and k='5' and l='2' and m='1' "));
	   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter   and k='5' and l='2' and m='2' "));
	 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' c1='$c1' and c='$c' and d='$d' $kondisiFilter  and k='5' and l='2' and m='3' "));
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaModal['belanjaModal'] ,2,',','.') );
		$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
		$Koloms[] = array('align="right"', number_format($jumlahRekening ,2,',','.') );
	 }elseif($d == '00' && $c !='00'){
	 	$Koloms[] = array('align="left"', $c1.'.'.$c );	
		$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='$c' and d='00' and e='00' and e1='000'"));
		$Koloms[] = array('align="left"', "<span style='font-weight:bold;margin-left:5px;'>".$getSKPD['nm_skpd']."</span>" );
		$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c'  $kondisiFilter   and k='5' and l='2' and m='1' "));
	   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c'  $kondisiFilter   and k='5' and l='2' and m='2' "));
	 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' c1='$c1' and c='$c'  $kondisiFilter  and k='5' and l='2' and m='3' "));
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaModal['belanjaModal'] ,2,',','.') );
		$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
		$Koloms[] = array('align="right"', number_format($jumlahRekening ,2,',','.') );
	 }else{
	 	$Koloms[] = array('align="left"', $c1 );	
		$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='00' and d='00' and e='00' and e1='000'"));
		$Koloms[] = array('align="left"', "<span style='font-weight:bold;'>".$getSKPD['nm_skpd']."</span>" );
		$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1'  $kondisiFilter   and k='5' and l='2' and m='1' "));
	   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1'   $kondisiFilter   and k='5' and l='2' and m='2' "));
	 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' c1='$c1'   $kondisiFilter  and k='5' and l='2' and m='3' "));
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.') );
		$Koloms[] = array('align="right"',"<span style='font-weight:bold;'>" . number_format($getBelanjaModal['belanjaModal'] ,2,',','.') );
		$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
		$Koloms[] = array('align="right"', number_format($jumlahRekening ,2,',','.') );
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
	 $this->form_caption = 'VALIDASI RKA-SKPD 2.2';
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
						'label'=>'KODE r_apbd4',
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
	
	
	
	
	
	foreach ($_COOKIE as $key => $value) { 
				  $$key = $value; 
			}
	
	
	if($_COOKIE['VulnWalkerUrusan'] != '0' || $_COOKIE['VulnWalkerUrusan'] != '00' ){
		$codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) from ref_skpd where c1='$VulnWalkerUrusan' and c='00' and d='00' and e='00' and e1='000' ";
		$urusan = cmbQuery('cmbUrusan',$VulnWalkerUrusan,$codeAndNameUrusan,'onchange=r_apbd4.refreshList(true);','-- URUSAN --');
		
		$codeAndNameBidang = "select c, concat(c, '. ', nm_skpd) from ref_skpd where c1='$VulnWalkerUrusan' and c ='$VulnWalkerBidang' and d='00' and e='00' and e1='000' ";
		$bidang = cmbQuery('cmbBidang',$VulnWalkerBidang,$codeAndNameBidang,'onchange=r_apbd4.refreshList(true);','-- BIDANG --');
		
		$codeAndNameSKPD = "select d, concat(d, '. ', nm_skpd) from ref_skpd where c1='$VulnWalkerUrusan' and c='$VulnWalkerBidang' and d='$VulnWalkerSKPD' and e='00' and e1='000' ";
		$skpd= cmbQuery('cmbSKPD',$VulnWalkerSKPD,$codeAndNameSKPD,'onchange=r_apbd4.refreshList(true);','-- SKPD --');
		
		/*$codeAndNameUnit = "select e, concat(e, '. ', nm_skpd) from ref_skpd where c1='$VulnWalkerUrusan' and c='$VulnWalkerBidang' and d='$VulnWalkerSKPD' and e='$VulnWalkerUnit' and e1='000' ";
		$unit = cmbQuery('cmbUnit',$VulnWalkerUnit,$codeAndNameUnit,'onchange=r_apbd4.refreshList(true);','-- UNIT --');
		
		$codeAndNameSubUnit = "select e1, concat(e1, '. ', nm_skpd) from ref_skpd where c1='$VulnWalkerUrusan' and c='$VulnWalkerBidang' and d='$VulnWalkerSKPD' and e='$VulnWalkerUnit' and e1='$VulnWalkerSubUnit' ";
		$subunit = cmbQuery('cmbSubUnit',$VulnWalkerSubUnit,$codeAndNameSubUnit,'onchange=r_apbd4.refreshList(true);','-- SUB UNIT --');*/
		
		$selectedC1 = $VulnWalkerUrusan;
		$selectedC = $VulnWalkerBidang;
		$selectedD = $VulnWalkerSKPD;
		$selectedE = $VulnWalkerUnit;
		$selectedE1 = $VulnWalkerSubUnit;
	}else{
		$codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) from ref_skpd where c='00' and d='00' and e='00' and e1='000' ";
		$urusan = cmbQuery('cmbUrusan',$selectedC1,$codeAndNameUrusan,'onchange=r_apbd4.refreshList(true);','-- URUSAN --');
		
		$codeAndNameBidang = "select c, concat(c, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c !='00' and d='00' and e='00' and e1='000' ";
		$bidang = cmbQuery('cmbBidang',$selectedC,$codeAndNameBidang,'onchange=r_apbd4.refreshList(true);','-- BIDANG --');
		
		$codeAndNameSKPD = "select d, concat(d, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d!='00' and e='00' and e1='000' ";
		$skpd= cmbQuery('cmbSKPD',$selectedD,$codeAndNameSKPD,'onchange=r_apbd4.refreshList(true);','-- SKPD --');
		
/*		$codeAndNameUnit = "select e, concat(e, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e!='00' and e1='000' ";
		$unit = cmbQuery('cmbUnit',$selectedE,$codeAndNameUnit,'onchange=r_apbd4.refreshList(true);','-- UNIT --');
		
		
		$codeAndNameSubUnit = "select e1, concat(e1, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1!='000' ";
		$subunit = cmbQuery('cmbSubUnit',$selectedE1,$codeAndNameSubUnit,'onchange=r_apbd4.refreshList(true);','-- SUB UNIT --');*/
	}
	
	

	
	
	if($this->jenisForm == "KOREKSI" || $this->jenisForm == "PENYUSUNAN" || $this->jenisForm == "VALIDASI"){
		$getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' "));
		$idTahapRenja = $getIdTahapRenjaTerakhir['max'];
		$getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1='$selectedE1' and bk='$selectedBK' and ck='$selectedCK' and p='$selectedP' and q='$selectedQ' and id_tahap = '$idTahapRenja' "));
		$angkaPaguIndikatif = number_format($getPaguIndikatif['jumlah'] ,2,',','.');
		
		$getPaguYangTerpakai =  mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai from view_r_apbd4 where c1= '$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1='$selectedE1' and bk='$selectedBK' and ck='$selectedCK' and p='$selectedP' and q='$selectedQ' and id_tahap = '$this->idTahap'  "));
		$sisaPagu = $getPaguIndikatif['jumlah'] - $getPaguYangTerpakai['paguYangTerpakai'];
		$sisaPagu =  number_format($sisaPagu ,2,',','.');
		$paguIndikatif = "<input type='text' value='$angkaPaguIndikatif' readonly> &nbsp &nbsp &nbsp SISA PAGU  :  <input type='text' value='$sisaPagu' readonly>";
		
	}else{
		$getIdTahapRenjaTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) as max from view_renja where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' "));
		$idTahapRenja = $getIdTahapRenjaTerakhir['max'];
		$getPaguIndikatif = mysql_fetch_array(mysql_query("select * from view_renja where c1= '$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1='$selectedE1' and bk='$selectedBK' and ck='$selectedCK' and p='$selectedP' and q='$selectedQ' and id_tahap = '$idTahapRenja' "));
		$angkaPaguIndikatif = number_format($getPaguIndikatif['jumlah'] ,2,',','.');
		
		$getPaguYangTerpakai =  mysql_fetch_array(mysql_query("select sum(jumlah_harga) as paguYangTerpakai from view_r_apbd4 where c1= '$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1='$selectedE1' and bk='$selectedBK' and ck='$selectedCK' and p='$selectedP' and q='$selectedQ' and no_urut = '$this->urutTerakhir' and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'  "));
		$sisaPagu = $getPaguIndikatif['jumlah'] - $getPaguYangTerpakai['paguYangTerpakai'];
		$sisaPagu =  number_format($sisaPagu ,2,',','.');
		$paguIndikatif = "<input type='text' value='$angkaPaguIndikatif' readonly> &nbsp &nbsp &nbsp SISA PAGU  :  <input type='text' value='$sisaPagu' readonly>";
		
	}
	
	
	
	$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>URUSAN </td>
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
			
			
			
			
			</table>"
			
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

	
		$cmbJenisRKA = $_REQUEST['cmbJenisRKA'];

		foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 }
			
		
			if($cmbSubUnit != ''){
				$arrKondisi[] = "c1 = '$cmbUrusan'";
				$arrKondisi[] = "c = '$cmbBidang'";
				$arrKondisi[] = "d = '$cmbSKPD'";
				$arrKondisi[] = "e = '$cmbUnit'";
				$arrKondisi[] = "e1 = '$cmbSubUnit'";
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit'";
			}elseif($cmbUnit != ''){
				$arrKondisi[] = "c1 = '$cmbUrusan'";
				$arrKondisi[] = "c = '$cmbBidang'";
				$arrKondisi[] = "d = '$cmbSKPD'";
				$arrKondisi[] = "e = '$cmbUnit'";
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' ";
			}elseif($cmbSKPD != ''){
				$arrKondisi[] = "c1 = '$cmbUrusan'";
				$arrKondisi[] = "c = '$cmbBidang'";
				$arrKondisi[] = "d = '$cmbSKPD'";
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
			}elseif($cmbBidang != ''){
				$arrKondisi[] = "c1 = '$cmbUrusan'";
				$arrKondisi[] = "c = '$cmbBidang'";
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
			}elseif($cmbUrusan != ''){
				$arrKondisi[] = "c1 = '$cmbUrusan'";
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

			$getAllFromRka221 = mysql_query("select * from view_rka_2_2_1 where 1=1 $kondisiFilter and o1!='0'  and (rincian_perhitungan !='' or f1 !='0')");
			while($rows = mysql_fetch_array($getAllFromRka221)){
				foreach ($rows as $key => $value) { 
				  $$key = $value; 
				}	
				
				if(mysql_fetch_array(mysql_query("select * from view_r_apbd4 where c1 = '$c1' and c='00' and d='00' and e='00' and e1='000' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) > 0){
				}else{
					$data = array(
									'c1' => $c1,
									'c' => '00',
									'd' => '00',
									'e' => '00',
									'e1' => '000',
									'bk' => '0',
									'ck' => '0',
									'p' => '0',
									'q' => '0',
									'tahun' => $this->tahun,
									'jenis_anggaran' => $this->jenisAnggaran,
									'nama_modul' => 'R-APBD4'
								  
								  );
					 mysql_query(VulnWalkerInsert("tabel_anggaran",$data));
				}
				
				if(mysql_fetch_array(mysql_query("select * from view_r_apbd4 where c1 = '$c1' and c='$c' and d='00' and e='00' and e1='000' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) > 0){
				}else{
					$data = array(
									'c1' => $c1,
									'c' => $c,
									'd' => '00',
									'e' => '00',
									'e1' => '000',
									'bk' => '0',
									'ck' => '0',
									'p' => '0',
									'q' => '0',
									'tahun' => $this->tahun,
									'jenis_anggaran' => $this->jenisAnggaran,
									'nama_modul' => 'R-APBD4'
								  
								  );
					 mysql_query(VulnWalkerInsert("tabel_anggaran",$data));
				}
				if(mysql_fetch_array(mysql_query("select * from view_r_apbd4 where c1 = '$c1' and c='$c' and d='$d' and e='00' and e1='000' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) > 0){
				}else{
					$data = array(
									'c1' => $c1,
									'c' => $c,
									'd' => $d,
									'e' => '00',
									'e1' => '000',
									'bk' => '0',
									'ck' => '0',
									'p' => '0',
									'q' => '0',
									'tahun' => $this->tahun,
									'jenis_anggaran' => $this->jenisAnggaran,
									'nama_modul' => 'R-APBD4'
								  
								  );
					 mysql_query(VulnWalkerInsert("tabel_anggaran",$data));
				}
				
				
				if(mysql_fetch_array(mysql_query("select * from view_r_apbd4 where  c1='$c1' and c='$c' and d='$d' and e='00' and e1='000' and bk='$bk' and ck='$ck' and p='$p' and q='0' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) > 0){
				}else{
					$data = array(
									'c1' => $c1,
									'c' => $c,
									'd' => $d,
									'e' => '00',
									'e1' => '000',
									'bk' => $bk,
									'ck' => $ck,
									'p' => $p,
									'q' => '0',
									'tahun' => $this->tahun,
									'jenis_anggaran' => $this->jenisAnggaran,
									'nama_modul' => 'R-APBD4'
								  
								  );
					 mysql_query(VulnWalkerInsert("tabel_anggaran",$data));
				}
				if(mysql_fetch_array(mysql_query("select * from view_r_apbd4 where c1='$c1' and c='$c' and d='$d' and e='00' and e1='000' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' ")) > 0){
				}else{
					$data = array(
									'c1' => $c1,
									'c' => $c,
									'd' => $d,
									'e' => '00',
									'e1' => '000',
									'bk' => $bk,
									'ck' => $ck,
									'p' => $p,
									'q' => $q,
									'tahun' => $this->tahun,
									'jenis_anggaran' => $this->jenisAnggaran,
									'nama_modul' => 'R-APBD4'
								  
								  );
					 mysql_query(VulnWalkerInsert("tabel_anggaran",$data));
				}
				
			}		
		
		
		
		/*$getAllProgram = mysql_query("select * from view_r_apbd4 where q != '0'");
		$arrayProgram = array();
		while($rows = mysql_fetch_array($getAllProgram)){
			foreach ($rows as $key => $value) { 
				  $$key = $value; 
			}
			if(mysql_num_rows(mysql_query("select * from view_r_apbd4 where  o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and bk='$bk' and ck='$ck' and p='$p' and q='$q'  $kondisiFilter $kondisiSKPD")) == 0){
				$arrKondisi[] = "id_anggaran !='$id_anggaran'";	
				if(mysql_num_rows(mysql_query("select * from view_r_apbd4 where  o1 !='0' and (rincian_perhitungan !='' or f1 !='0' ) and bk='$bk' and ck='$ck' and p='$p'   $kondisiFilter $kondisiSKPD")) == 0){
					$concat = $bk.".".$ck.".".$p;
					$arrKondisi[] = "concat(bk,'.',ck,'.',p) !='$concat'";
				}
			}else{
					$getIDProgram =  mysql_fetch_array(mysql_query("select * from view_r_apbd4 where bk='$bk' and ck='$ck' and p='$p' and  q = '0'"));
					$idProgram = $getIDProgram['id_anggaran'];
					$arrayProgram[] = " id_anggaran = '$idProgram' ";
					$kondisiProgram = join(' or ',$arrayProgram);	
					$arrKondisi[] = " 1=1 or $kondisiProgram   ";
			}	
			
		}*/
		
		
			
		
  

		
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi ;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = "c1,c,d, bk,ck,p,q  asc";
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
function Laporan($xls =FALSE){
		global $Main;
		
	
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		
		$arrKondisi = array();

		

		
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

					
  		
		
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		
		$Kondisi= join(' and ',$arrKondisi);
		$qry ="select * from view_r_apbd4 where $Kondisi  ";
		$aqry = mysql_query($qry);
		
		//
				
		//MULAI Halaman Laporan ------------------------------------------------------------------------------------------ 
		$css = $xls	? "<style>.nfmt5 {mso-number-format:'\@';}</style>":"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
						.ukurantulisan{
							font-size:17px;
						}
						.ukurantulisan1{
							font-size:20px;
						}
						.ukurantulisanIdPenerimaan{
							font-size:16px;
						}
					</style>
				</head>".
			"<body >
				<div style='width:$this->Cetak_WIDTH_Landscape;'>
					<table class=\"rangkacetak\" style='width:80%;font-family:Times New Roman;margin-left:2cm;margin-top:2cm;'>
						<tr>
							<td valign=\"top\"> <div style='text-align:center;'>
				<span style='font-size:18px;font-weight:bold;text-decoration: '>
					PROVINSI/KABUPATEN/KOTA ....<br>
					REKAPITULASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, DAN KEGIATAN	
				</span><br>
				<span style='font-size:14px;font-weight:text-decoration: '>
					Tahun Anggaran $this->tahun 
				</span><br>
				
				
				<br>
				
				
				";
		echo "
				<span style='font-size:16px;font-weight:bold;text-decoration: '>
					Ringkasan Anggaran Pendapatan, Belanja dan Pembiayaan Satuan Kerja Perangkat Daerah
				</span><br>
								<table table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
									<tr>
									   <th class='th01' width='100'  rowspan='2'  >KODE</th>
									   <th class='th01' width='500'  rowspan='2' >URAIAN</th>
									   <th class='th02' colspan='3'  rowspan='1' width='500' >JUMLAH BELANJA</th>
									   <th class='th01' rowspan='2'>JUMLAH</th>
									 </tr>
							
									 <tr>
									   <th class='th01' rowspan='1'>BELANJA PEGAWAI</th>
									   <th class='th01' rowspan='1'>BELANJA BARANG & JASA</th>
									   <th class='th01' rowspan='1'>BELANJA MODAL</th>
							
									 </tr>
								
								
									
		";
		
		$no = 1;
		while($daqry = mysql_fetch_array($aqry)){
			foreach ($daqry as $key => $value) { 
				  $$key = $value; 
			} 
			echo "<tr valign='top'>";
			 
	 		 if($q !='0'){
				echo "<td align='left' class='GarisCetak' >".$c1.'.'.$c.'.'.$d.'.'.genNumber($bk).'.'.genNumber($ck).'.'.genNumber($p).'.'.genNumber($q)."</td>";
				$getNamaKegiatan = mysql_fetch_array(mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='0' and p='$p' and q='$q'"));
				$namaKegiatan =  $getNamaKegiatan['nama'];
				echo "<td align='left' class='GarisCetak' >"."<span style='margin-left:20px;'>".$namaKegiatan."</span>" ."</td>";
				$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='1' "));
			   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='2' "));
			 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='3' "));
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>"."</td>";	
				$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
				echo "<td align='right' class='GarisCetak' >"."<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>"."</td>";	
			 }elseif($q == '0' && $p !='0'){
			 	echo "<td align='left' class='GarisCetak' >".$c1.'.'.$c.'.'.$d.'.'.genNumber($bk).'.'.genNumber($ck).'.'.genNumber($p)."</td>";
				$getNamaProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk='$bk' and ck='$ck' and dk='0' and p='$p' and q='0'"));
				$namaProgram =  $getNamaProgram['nama'];
				echo "<td align='left' class='GarisCetak' >"."<span style='font-weight:bold;margin-left:15px;'>".$namaProgram."</span>"."</td>";
				$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='1' "));
			   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p'  and k='5' and l='2' and m='2' "));
			 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter and bk='$bk' and ck='$ck' and p='$p' and k='5' and l='2' and m='3' "));
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>"." </td>";	
				$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
				echo "<td align='right' class='GarisCetak' >"."<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>"."</td>";	
			 }elseif($p == '0' && $d !='00'){
			 	echo "<td align='left' class='GarisCetak' >".$c1.'.'.$c.'.'.$d."</td>";	
				$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='$c' and d='$d' and e='00' and e1='000'"));
				echo "<td align='left' class='GarisCetak' >"."<span style='font-weight:bold;margin-left:10px;'>".$getSKPD['nm_skpd']."</span>"."</td>";	
				$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter   and k='5' and l='2' and m='1' "));
			   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter   and k='5' and l='2' and m='2' "));
			 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c' and d='$d' $kondisiFilter  and k='5' and l='2' and m='3' "));
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>"."</td>";	
				$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
				echo "<td align='right' class='GarisCetak' >"."<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>"."</td>";	
			 }elseif($d == '00' && $c !='00'){
			 	echo "<td align='left' class='GarisCetak' >".$c1.'.'.$c."</td>";	
				$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='$c' and d='00' and e='00' and e1='000'"));
				echo "<td align='left' class='GarisCetak' >"."<span style='font-weight:bold;margin-left:5px;'>".$getSKPD['nm_skpd']."</span>"."</td>";
				$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c'  $kondisiFilter   and k='5' and l='2' and m='1' "));
			   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c'  $kondisiFilter   and k='5' and l='2' and m='2' "));
			 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1' and c='$c'  $kondisiFilter  and k='5' and l='2' and m='3' "));
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>"."</td>";	
				$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
				echo "<td align='right' class='GarisCetak' >"."<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>"."</td>";	
			 }else{
			 	echo "<td align='left' class='GarisCetak' >".$c1."</td>";		
				$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1= '$c1' and c='00' and d='00' and e='00' and e1='000'"));
				echo "<td align='left' class='GarisCetak' >". "<span style='font-weight:bold;'>".$getSKPD['nm_skpd']."</span>"."</td>";	
				$getBelanjaPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaPegawai from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1'  $kondisiFilter   and k='5' and l='2' and m='1' "));
			   	$getBelanjaBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaBarangJasa from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1'   $kondisiFilter   and k='5' and l='2' and m='2' "));
			 	$getBelanjaModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) as belanjaModal from tabel_anggaran where nama_modul = 'RKA-SKPD' and c1='$c1'   $kondisiFilter  and k='5' and l='2' and m='3' "));
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaPegawai['belanjaPegawai'] ,2,',','.') ."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaBarangJasa['belanjaBarangJasa'] ,2,',','.')."</span>"."</td>";
				echo "<td align='right' class='GarisCetak' >"."<span style=''>" .number_format($getBelanjaModal['belanjaModal'] ,2,',','.')."</span>"."</td>";	
				$jumlahRekening = $getBelanjaPegawai['belanjaPegawai'] + $getBelanjaBarangJasa['belanjaBarangJasa'] + $getBelanjaModal['belanjaModal'];
				echo "<td align='right' class='GarisCetak' >"."<span style='font-weight:bold;'>" . number_format($jumlahRekening ,2,',','.')."</span>"."</td>";	
			 }
	 

									
									
										
				
			$no++;
			
			
		echo "</tr>";	
			
		}
		$getData = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f ='00' ) $kondisiFilter and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' $kondisiSKPD $kondisiRekening"));
		$Total = $getData['sum(jumlah_harga)'];
		$getTotalPegawai = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f ='00' ) $kondisiFilter and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='1' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaPegawai = $getTotalPegawai['sum(jumlah_harga)'];
		$getTotalBarangJasa = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f ='00' ) $kondisiFilter and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='2' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaBarangJasa = $getTotalBarangJasa['sum(jumlah_harga)'];
		$getTotalModal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where o1 !='0' and (rincian_perhitungan !='' or f ='00' ) $kondisiFilter and tahun ='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and jenis_rka = '2.2.1' and k = '5' and l ='2' and m='3' $kondisiSKPD $kondisiRekening"));
		$TotalBelanjaModal = $getTotalModal['sum(jumlah_harga)'];
		echo 				"<tr valign='top'>
									<td align='right' colspan='2' class='GarisCetak'>Jumlah</td>
									<td class='GarisCetak' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaPegawai,2,',','.')."</div></td>
					<td class='GarisCetak' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaBarangJasa,2,',','.')."</div></td>
					<td class='GarisCetak' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($TotalBelanjaModal,2,',','.')."</div></td>
					<td class='GarisCetak' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".number_format($Total,2,',','.')."</div></td>
									
								</tr>
								
							 </table> 

		";
		echo 			
						"<br><div class='ukurantulisan'>
					<table width='100%'>
						<tr>
							<td class='ukurantulisan' valign='top' ></td>
							<td class='ukurantulisan' valign='top' width='70%' ></td>
							<td class='ukurantulisan' valign='top'><span style='margin-left:5px;'>Bandung, ".VulnWalkerTitiMangsa(date('Y-m-d'))."</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>&nbsp
<br><br><br><br><br></span></td>
							<td class='ukurantulisan' valign='top' width='50%' ></td>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>Gubernur/Bupati/Walikota
</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>&nbsp</td>
										<td class='ukurantulisan'>&nbsp</td>
										<td class='ukurantulisan'>&nbsp</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>&nbsp</td>
										<td class='ukurantulisan'> &nbsp </td>
										<td class='ukurantulisan'>&nbsp</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan'></td>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan'><u>Nama Lengkap</u></td>
										<td class='ukurantulisan'></td>
										<td class='ukurantulisan'></td>
									</tr>
									
								</table>
							</td>
						</tr>
						
						
					</table>
					
					
					<table width='100%'>
						<tr>
							<td class='ukurantulisan' valign='top' ></td>
							<td class='ukurantulisan' valign='top' width='70%' ></td>
							<td class='ukurantulisan' valign='top'><span style='margin-left:5px;'></span></td>
						</tr>
						<tr>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>&nbsp
<br><br><br><br><br></span></td>
							<td class='ukurantulisan' valign='top' width='50%' ></td>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>MENTRERI DALAM NEGRI
</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>&nbsp</td>
										<td class='ukurantulisan'>&nbsp</td>
										<td class='ukurantulisan'>&nbsp</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>&nbsp</td>
										<td class='ukurantulisan'> &nbsp </td>
										<td class='ukurantulisan'>&nbsp</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan'></td>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan'><u>Nama Lengkap</u></td>
										<td class='ukurantulisan'></td>
										<td class='ukurantulisan'></td>
									</tr>
									
								</table>
							</td>
						</tr>
						
						
					</table>
				</div></div>	</td></tr>
					</table>
				</div>	
			</body>	
		</html>";
	}	
	
}
$r_apbd4 = new r_apbd4Obj();

$arrayResult = VulnWalkerTahap($r_apbd4->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$r_apbd4->jenisForm = $jenisForm;
$r_apbd4->nomorUrut = $nomorUrut;
$r_apbd4->tahun = $tahun;
$r_apbd4->jenisAnggaran = $jenisAnggaran;
$r_apbd4->idTahap = $idTahap;


if(empty($r_apbd4->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_r_apbd4 "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_r_apbd4 where id_anggaran = '$maxAnggaran'"));
	/*$r_apbd4->tahun = "select max(id_anggaran) as max from view_r_apbd4 where nama_modul = 'r_apbd4'";*/
	$r_apbd4->tahun  = $get2['tahun'];
	$r_apbd4->jenisAnggaran = $get2['jenis_anggaran'];
	$r_apbd4->urutTerakhir = $get2['no_urut'];
	$r_apbd4->jenisFormTerakhir = $get2['jenis_form_modul'];
	$r_apbd4->urutSebelumnya = $r_apbd4->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$r_apbd4->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$r_apbd4->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap();
	$r_apbd4->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$r_apbd4->idTahap'"));
	$r_apbd4->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$r_apbd4->idTahap'"));
	$r_apbd4->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$r_apbd4->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$r_apbd4->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}


?>