<?php

class plafonObj  extends DaftarObj2{	
	var $Prefix = 'plafon';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'view_plafon'; //bonus
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
	var $PageTitle = 'PLAFON';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='plafon.xls';
	var $namaModulCetak='PLAFON';
	var $Cetak_Judul = 'PLAFON';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'plafonForm';
	var $modul = "PLAFON";
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
	var $noUrutTerakhirPlafon = "";
	
	var $username = "";
	//buatview
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'PLAFON '.$this->jenisAnggaran.' TAHUN '. $this->tahun ;
	}
	
	function setMenuEdit(){
	 	 $arrayResult = VulnWalkerTahap("PLAFON");
		 $jenisForm = $arrayResult['jenisForm'];
		 $nomorUrut = $arrayResult['nomorUrut'];
		 $tahun = $arrayResult['tahun'];
		 $jenisAnggaran = $arrayResult['jenisAnggaran'];
		 $query = $arrayResult['query'];
	 if ($jenisForm == "PENYUSUNAN"){
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru ", 'Baru ')."</td>".
					"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
					"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>
					<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";	
	 }elseif ($jenisForm == "VALIDASI"){
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Validasi()","validasi-menu.png","Validasi", 'Validasi')."</td>
					<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>
					";	
	 }elseif ($jenisForm == "KOREKSI"){
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";
	 }else{
	 	$listMenu = "<td>".genPanelIcon("javascript:".$this->Prefix.".Info()","info.png","Info", 'Info')."</td>";
	 }
	 
		return $listMenu ;
	}
	function setMenuView(){
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Laporan()","print_f2.png",'Laporan',"Laporan")."</td>";				
			
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
	
	
	 if( $err=='' && $jumlah =='' ) $err= 'Jumlah Plafon Belum Di Isi !!';
	 
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
						$err = "PLAFON SUDAH ADA";
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
		
		case 'Report':{				
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
			if(mysql_num_rows(mysql_query("select * from skpd_report_plafon where username= '$this->username'")) == 0){
					$data = array(
								  'username' => $this->username,
								  'c1' => $fmSKPDUrusan,
								  'c' => $fmSKPDBidang,
								  'd' => $fmSKPDskpd
								  
								  );
					$query = VulnWalkerInsert('skpd_report_plafon',$data);
					mysql_query($query);
				}else{
					$data = array(
								   'username' => $this->username,
								  'c1' => $fmSKPDUrusan,
								  'c' => $fmSKPDBidang,
								  'd' => $fmSKPDskpd
								  
								  
								  );
					$query = VulnWalkerUpdate('skpd_report_plafon',$data,"username = '$this->username'");
					mysql_query($query);
				}												
		break;
		}
					
		case 'simpan':{
			if($this->jenisForm !='PENYUSUNAN'){
				$err = "Tahap Penyusunan Telah Habis";
			}else{
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			}
		break;
	    }
		case 'Laporan':{
		foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
		$xls =FALSE;
		$json = FALSE;
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$grabSKPD = mysql_fetch_array(mysql_query("select * from skpd_report_plafon where username='$this->username'"));
		foreach ($grabSKPD as $key => $value) { 
				  $$key = $value; 
			}
		$ref_skpdSkpdfmUrusan = $c1;
		$ref_skpdSkpdfmSKPD = $c;
		$ref_skpdSkpdfmUNIT = $d;
		if($ref_skpdSkpdfmUrusan!='0' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='00' ){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
		if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";

		if($ref_skpdSkpdfmSKPD!='00'){

		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		     }
		}
		
		$daqry_pengaturan = $DataOption;
		
		if($this->jenisForm == 'PENYUSUNAN'){
			$getAll = mysql_query("select * from view_plafon where id_tahap='$idTahap' and d = '00' and c ='00'");
			while($rows = mysql_fetch_array($getAll)){
				$c1 = $rows['c1'];
				if(mysql_num_rows(mysql_query("select * from view_plafon where c1 ='$c1' and d!='00' and id_tahap = '$idTahap'")) == 0){
					$arrKondisi[] = " c1 !='$c1' ";
				}
			}
				
			$arrKondisi[] = " tahun = '$this->tahun'";
			$arrKondisi[] = " jenis_anggaran = '$this->jenisAnggaran'";
		}elseif($this->jenisForm == 'VALIDASI'){					
			$arrKondisi[] =  "no_urut = '$this->nomorUrut' ";
				
		}elseif($this->jenisForm == 'KOREKSI'){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$beforeThis = mysql_fetch_array(mysql_query("select * from view_plafon where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$getAllTahapSebelumnya = mysql_query("select * from view_plafon where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
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
						$resultBidang = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1' and c = '$c' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
						if($resultBidang  == 0){
						    $concat = $c1.'.'.$c;
							$arrKondisi[] = "concat(c1,'.',c) != '$concat' ";	
						}else{
							$resultUrusan = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1'  and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
							if($resultUrusan  == 0){
							 	$concat = $c1;
								$arrKondisi[] = "c1 != '$concat' ";	
							}
						}
					}
				}
			}
			
			$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya' ";
			

		}else{
			if($this->jenisFormTerakhir == "KOREKSI"){
				$nomorUrutSebelumnya = $this->urutTerakhir - 1;
				$beforeThis = mysql_fetch_array(mysql_query("select * from view_plafon where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
				$getAllTahapSebelumnya = mysql_query("select * from view_plafon where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
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
							$resultBidang = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1' and c = '$c' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
							if($resultBidang  == 0){
							    $concat = $c1.'.'.$c;
								$arrKondisi[] = "concat(c1,'.',c) != '$concat' ";	
							}else{
								$resultUrusan = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1'  and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
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
				$arrKondisi[] =  "no_urut = '$this->urutTerakhir'";
			}else{
				$getAll = mysql_query("select * from view_plafon where no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran='$this->jenisAnggaran' and d = '00' and c ='00'");
				while($rows = mysql_fetch_array($getAll)){
					$c1 = $rows['c1'];
					if(mysql_num_rows(mysql_query("select * from view_plafon where c1 ='$c1' and d!='00' and no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran='$this->jenisAnggaran'")) == 0){
						$arrKondisi[] = " c1 !='$c1' ";
					}
				}
				$arrKondisi[] = "no_urut = '$this->urutTerakhir'";
			}
		}
		
  

		//hidden if fucking colomn is empty			
		$queryGetAll = "select * from view_plafon where tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' ";
		$execute = mysql_query($queryGetAll);
		while($rows = mysql_fetch_array($execute)){
			$c1 = $rows['c1'];
			$c = $rows['c'];
			$d = $rows['d'];
			$getUrusan = mysql_num_rows(mysql_query("select * from view_plafon where c1 = '$c1' and c !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			if($getUrusan > 0){
				$queryGetBidang = "select * from tabel_anggaran where c1='$c1' and c = '$c'  and d != '00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'";
				$getBidang = mysql_num_rows(mysql_query($queryGetBidang));
				if($getBidang > 0){
				}else{
					if($c == '00'){
						$concat = "'.'";
					}else{
						$concat = $c1.".".$c;
					}
					$arrKondisi[] = " concat(c1,'.',c) != $concat ";
				}
			}else{
				$arrKondisi[] = " c1 !='$c1' ";
			}
			
		}
		//hidden if fucking colomn is empty	
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		$getIdTahap = mysql_fetch_array(mysql_query("select max(id_tahap) from view_plafon where jenis_anggaran ='$this->jenisAnggaran' and tahun = '$this->tahun'"));
		$idTahap = $getIdTahap['max(id_tahap)'];
		$qry = "select * from view_plafon $Kondisi ";
		$aqry = mysql_query($qry);
		
				
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
					<table class=\"rangkacetak\" style='width:90%;font-family:Times New Roman;margin-left:2cm;margin-top:2cm;'>
						<tr>
							<td valign=\"top\">".
								$this->JudulLaporan($dari, $sampai, 'DAFTAR PENERIMAAN BARANG');
								
		//echo $qry;
		echo "
								<br>
								<table table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
									<tr>
										<th class='th01' style='width:20px;' >NO</th>
										<th class='th01' >KODE</th>
										<th class='th01' >NAMA URUSAN PEMERINTAHAN, BIDANG, SKPD</th>
										<th class='th01' >PLAFON (Rp) </th>
										
									</tr>
									
		";
		
		$pid = '';
		$no_cek = 0;
		$no = 1;
		while($daqry = mysql_fetch_array($aqry)){
			foreach ($daqry as $key => $value) { 
				  $$key = $value; 
			} 
			if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and id_tahap = '$idTahap'"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and id_tahap = '$idTahap'"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style=' margin-left:10px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and id_tahap = '$idTahap'"));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c.".".$d;
				 }
			echo "
								<tr valign='top'>
									<td align='right' class='GarisCetak'>$no</td>
									<td align='left' class='GarisCetak' >".$kode."</td>
									<td align='left' class='GarisCetak' >".$nama_skpd."</td>
									<td align='right' class='GarisCetak'>".number_format($jumlah,0,'.',',')."</td>
								</tr>
			";
			
			$no++;
			$no_cek++;
			
			
		}
		echo 				"</table>";		
		echo 			$this->TandaTanganFooter($c1,$c,$d,$e,$e1).
						"</div>	</td></tr>
					</table>
				</div>	
			</body>	
		</html>";
		 break;
		}
		
		case 'sesuai':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			$queryRows = "select * from tabel_anggaran where id_anggaran = '$idAwal'";
			$getPlafonnya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getPlafonnya as $key => $value) { 
				  $$key = $value; 
			} 
			$cmbUrusanForm = $c1;
			$cmbBidangForm = $c;
			if($this->jenisForm !='KOREKSI'){
				$err = "Tahap Koreksi Telah Habis";
			}else{
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
			}
			
			
			
			/*$content = array("query" => $query, "sesuai" => number_format($jumlah,2,',','.'), "QUERY ROWS" => $queryData);*/
			 
		break;
	    }
		
		case 'koreksi':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			} 
			$queryRows = "select * from tabel_anggaran where id_anggaran = '$idAwal'";
			$getPlafonnya = mysql_fetch_array(mysql_query($queryRows));
			foreach ($getPlafonnya as $key => $value) { 
				  $$key = $value; 
			} 
			 $cmbUrusanForm = $c1;
			 $cmbBidangForm = $c;
			 if($this->jenisForm !='KOREKSI'){
				$err = "Tahap Koreksi Telah Habis";
			 }else{
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
			

			 $data = array( "status_validasi" => $status_validasi,
			 				'user_validasi' => $_COOKIE['coID'],
			 				'tanggal_validasi' => date("Y-m-d"),
							'id_tahap' => $this->idTahap
			 				);
			 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$plafon_idplh'");
			 if($this->jenisForm !='VALIDASI'){
			 	$err = "Tahap Validasi Telah Habis !";
			 }else{
			 	mysql_query($query);
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

	    case 'Validasi':{
			$dt = array();
			$err='';
			$content='';
			$uid = $HTTP_COOKIE_VARS['coID'];
			
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$idplh = $cbid[0];
			$this->form_idplh = $cbid[0];
			
			if($this->jenisForm !='VALIDASI'){
				$err = "Tahap Validasi Telah Habis";
			}else{
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
			}				
															
		break;
		}
		
		
		 case 'Catatan':{
			foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 } 
			if($this->jenisForm  !='KOREKSI'){
				$err = "Tahap Koreksi Telah Habis";
			}else{
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
   $angka = mysql_num_rows(mysql_query("select * from view_plafon where id_tahap='$this->idTahap'"));
   if($this->jenisForm == "KOREKSI"){
   	 $noUrutKoreksi  = $this->nomorUrut - 1;
   	 $angka = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut ='$noUrutKoreksi'"));
   }
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
							function checkAll4( n, fldName ,elHeaderChecked, elJmlCek) {
		
			  if (!fldName) {
			     fldName = 'cb';
			  }
			   if (!elHeaderChecked) {
			     elHeaderChecked = 'toggle';
			  }
				var c = document.getElementById(elHeaderChecked).checked;
				var n2 = 0;
				for (i=0; i < ".$angka."; i++) {	
					cb = document.getElementById(fldName+i);
					if (cb) {
						cb.checked = c;
						n2++;
					}
				}
				if (c) {		
					document.getElementById(elJmlCek).value = n2;
				} else {		
					document.getElementById(elJmlCek).value = 0;
				}
		}
					</script>";
		return 	
			"
			<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/perencanaan/plafon/plafon.js' language='JavaScript' ></script> 
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
						'label'=>'PLAFON ANGGARAN (Rp)',
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
	
		
		 $arrayResult = VulnWalkerTahap($this->modul);
		 $jenisForm = $arrayResult['jenisForm'];
		 $nomorUrut = $arrayResult['nomorUrut'];
		 $tahun = $arrayResult['tahun'];
		 $jenisAnggaran = $arrayResult['jenisAnggaran'];
		 $id_tahap = $arrayResult['id_tahap'];
	 if ($jenisForm == "PENYUSUNAN"){
	 	$tergantungJenisForm = "
		";
	 }elseif ($jenisForm == "VALIDASI"){
	 	$tergantungJenisForm = "
		<th class='th01' width='100' >VALIDASI</th>
		
		";
	 }elseif ($jenisForm == "KOREKSI"){
	 	$Checkbox = "";
	 	$tergantungJenisForm = "
		<th class='th01' width='200'>KOREKSI</th>
		<th class='th01' width='200'>BERTAMBAH/(BERKURANG)</th>
		<th class='th01' width='200'>AKSI</th>
		";
	 }else{
	    $Checkbox = "";
		if($this->jenisFormTerakhir == "PENYUSUNAN"){
			$tergantungJenisForm = "";
		}elseif($this->jenisFormTerakhir == "KOREKSI"){
			$tergantungJenisForm = "
			<th class='th01' width='300' >PLAFON KOREKSI (Rp)</th>
			<th class='th01' width='200'>BERTAMBAH/(BERKURANG)</th>";
		}elseif($this->jenisFormTerakhir == "VALIDASI"){
			$tergantungJenisForm = "
			<th class='th01' width='100' > VALIDASI</th>";
		}
	 	
	 }
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' rowspan='2' colspan='1' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='100'>KODE</th>
	   <th class='th01' width='900' >NAMA URUSAN PEMERINTAHAN, BIDANG, SKPD</th>
	   <th class='th01' width='300' >PLAFON (Rp)</th>
	   $tergantungJenisForm 
	 
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  			$$key = $value; 
	 }
	 
	 //TAHAP PENYUSUNAN
	 if($this->jenisForm == 'PENYUSUNAN'){
	 
				 if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1'"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c'"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style=' margin-left:10px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d'"));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c.".".$d;
				 }
			 
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($d == '00')$TampilCheckBox="";
		 	 $Koloms[] = array(" align='center'  ", $TampilCheckBox); 
			 $Koloms[] = array(' align="left"', $kode );
			 $Koloms[] = array('align="left"', $nama_skpd );
			 $Koloms[] = array('align="right"', number_format($jumlah ,2,',','.') );
	 
	 
	 //TAHAP PENYUSUNAN
	 }elseif($this->jenisForm=="VALIDASI"){
	 	 if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$nomorUrutSebelumnya = $this->nomorUrut - 1;
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and (no_urut = '$nomorUrutSebelumnya' OR no_urut = '$this->nomorUrut')"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $nomorUrutSebelumnya = $this->nomorUrut - 1;
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and (no_urut = '$nomorUrutSebelumnya' OR no_urut = '$this->nomorUrut') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style=' margin-left:10px;'>". $get['nm_skpd'] ."</span>";
					 $nomorUrutSebelumnya = $this->nomorUrut - 1;
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and (no_urut = '$nomorUrutSebelumnya' OR no_urut = '$this->nomorUrut') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c.".".$d;
				 }
			 
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 if($d == '00')$TampilCheckBox="";
		 	 $Koloms[] = array(" align='center'  ", $TampilCheckBox); 
			 $Koloms[] = array(' align="left"', $kode );
			 $Koloms[] = array('align="left"', $nama_skpd );
			 $Koloms[] = array('align="right"', number_format($jumlah ,2,',','.') );
		     if ($status_validasi =='1') {
			 	$validnya = "valid.png";
			 }else{
			 	$validnya = "invalid.png";
			 }
			 if($d == '00'){
			 $Koloms[] = array('align="center"', "");
			 }else{
			 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'");
			 }
	 }elseif($this->jenisForm=="KOREKSI"){
	 		 if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$nomorUrutSebelumnya = $this->nomorUrut - 1;
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and no_urut = '$nomorUrutSebelumnya'"));
					$jumlah = $getJumlah['jumlah'];
					$getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1'   and no_urut = '$this->nomorUrut'  "));
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
					 $nomorUrutSebelumnya = $this->nomorUrut - 1;
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and no_urut = '$nomorUrutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c'  and no_urut = '$this->nomorUrut'  "));
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
				 	 
					 $nomorUrutSebelumnya = $this->nomorUrut - 1;
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$nomorUrutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->nomorUrut'  "));
					 $angkaKoreksi = number_format($getKoreksi['jumlah'],2,',','.');
					 $thisTahap = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->nomorUrut'  "));
					 if($thisTahap == 1){
					 	$tanda = "";
					 }else{
					 	$tanda = " color : red ;";
					 }
					 $nama_skpd = "<span style='margin-left:10px; $tanda'>". $get['nm_skpd'] ."</span>";
					 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
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
			 $aksi  = "<img src='images/administrator/images/valid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=plafon.sesuai('$id_anggaran');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=plafon.koreksi('$id_anggaran');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=plafon.catatan('$id_anggaran');></img> ";
			 if ($d == '00') {
			 $aksi = "";
			 }

			 $Koloms[] = array('align="center"',$aksi);
	 }else{
	 	if($this->jenisFormTerakhir == "KOREKSI"){
			if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and no_urut = '$this->urutSebelumnya'"));
					$jumlah = $getJumlah['jumlah'];
					$getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1'   and no_urut = '$this->urutTerakhir'  "));
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
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and no_urut = '$this->urutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and  no_urut = '$this->urutTerakhir'  "));
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
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutSebelumnya'  "));
					 $jumlah = $getJumlah['jumlah'];
					 $getKoreksi = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutTerakhir'  "));
					 $angkaKoreksi = number_format($getKoreksi['jumlah'],2,',','.');
					 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 
					 $thisTahap = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and no_urut = '$this->urutTerakhir'  "));
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
				if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir')"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style=' margin-left:10px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c.".".$d;
				 }
			 
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 $Koloms[] = array(' align="left"', $kode );
			 $Koloms[] = array('align="left"', $nama_skpd );
			 $Koloms[] = array('align="right"', number_format($jumlah ,2,',','.') );
		     if ($status_validasi =='1') {
			 	$validnya = "valid.png";
			 }else{
			 	$validnya = "invalid.png";
			 }
			 if($d == '00'){
			 $Koloms[] = array('align="center"', "");
			 }else{
			 $Koloms[] = array('align="center"', "<img src='images/administrator/images/$validnya' width='20px' heigh='20px'");
			 }
		}elseif($this->jenisFormTerakhir == "PENYUSUNAN"){
				if($c == '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				    $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='00' and d = '00' and e='00' and e1='000'" ));
					$nama_skpd = "<span style='font-weight:bold;'>". $get['nm_skpd'] ."</span>";
					$getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir')"));
					$jumlah = $getJumlah['jumlah'];
					$kode = $c1;
				 }elseif($c != '00' && $d =='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '00' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style='font-weight:bold; margin-left:5px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c;
				 }elseif($c != '00' && $d !='00' && $e=='00' && $e1=='000' && $bk == '0' && $ck =='0' && $p =='0' && $q=='0'  ){
				 	 $get  = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d = '$d' and e='00' and e1='000'" ));
					 $nama_skpd = "<span style=' margin-left:10px;'>". $get['nm_skpd'] ."</span>";
					 $getJumlah = mysql_fetch_array(mysql_query("select sum(plafon) as jumlah from view_plafon where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and c1 ='$c1' and c='$c' and d='$d' and (no_urut = '$this->urutSebelumnya' OR no_urut = '$this->urutTerakhir') "));
					 $jumlah = $getJumlah['jumlah'];
					 $kode = $c1.".".$c.".".$d;
				 }
			 
			 $Koloms = array();
			 $Koloms[] = array('align="center"', $no.'.' );
			 $Koloms[] = array(' align="left"', $kode );
			 $Koloms[] = array('align="left"', $nama_skpd );
			 $Koloms[] = array('align="right"', number_format($jumlah ,2,',','.') );
		     
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
	 $this->form_caption = 'VALIDASI PLAFON';
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
						'label'=>'KODE PLAFON',
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
	 $this->form_caption = 'INFO PLAFON';

	 
	 if($this->jenisFormTerakhir == "VALIDASI"){
	 	$getJumlahSKPDYangMengisiPlafon = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirPlafon' and d!='00' and status_validasi = '1' "));
	 }else{
	 	$getJumlahSKPDYangMengisiPlafon = mysql_num_rows(mysql_query("select * from view_plafon where tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$this->noUrutTerakhirPlafon' and d!='00' "));
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
						'value'=> number_format($getJumlahSKPDYangMengisiPlafon,0,',','.') ,
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
			".CmbUrusanBidangSkpd('plafon')."
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
	
		if($_COOKIE['VulnWalkerSKPD'] != '000'){
			$ref_skpdSkpdfmUrusan = $_COOKIE['VulnWalkerUrusan'];
			$ref_skpdSkpdfmSKPD = $_COOKIE['VulnWalkerBidang'];
			$ref_skpdSkpdfmUNIT = $_COOKIE['VulnWalkerSKPD'];
		}elseif($_COOKIE['VulnWalkerBidang'] !='00'){
			$ref_skpdSkpdfmUrusan = $_COOKIE['VulnWalkerUrusan'];
			$ref_skpdSkpdfmSKPD = $_COOKIE['VulnWalkerBidang'];
		}elseif($_COOKIE['VulnWalkerUrusan'] !='0'){
			$ref_skpdSkpdfmUrusan = $_COOKIE['VulnWalkerUrusan'];
		}

		if($ref_skpdSkpdfmUrusan!='0' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='00' ){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
		if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";

		if($ref_skpdSkpdfmSKPD!='00'){

		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		     }
		}
		
		
		if($this->jenisForm == 'PENYUSUNAN'){
			$getAll = mysql_query("select * from view_plafon where id_tahap='$this->idTahap' and d = '00' and c ='00'");
			while($rows = mysql_fetch_array($getAll)){
				$c1 = $rows['c1'];
				if(mysql_num_rows(mysql_query("select * from view_plafon where c1 ='$c1' and d!='00' and id_tahap = '$this->idTahap'")) == 0){
					$arrKondisi[] = " c1 !='$c1' ";
				}
			}
				
			$arrKondisi[] = " tahun = '$this->tahun'";
			$arrKondisi[] = " jenis_anggaran = '$this->jenisAnggaran'";
		}elseif($this->jenisForm == 'VALIDASI'){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$getJenisTahapSebelumnya = mysql_fetch_array(mysql_query("select * from view_plafon where no_urut = '$nomorUrutSebelumnya'  and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$jenisTahapSebelumnya = $getJenisTahapSebelumnya['jenis_form_modul'];
			$getAllTahapSebelumnya = mysql_query("select * from view_plafon where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'  ");
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
						 $tempID = $rows['id_anggaran'];
						 
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
								 $query = VulnWalkerUpdate("tabel_anggaran",$data," id_anggaran = '$tempID'");
								 mysql_query($query);
				 	 }
								 
				
				}
									
				$arrKondisi[] =  "no_urut = '$this->nomorUrut' ";
				
		}elseif($this->jenisForm == 'KOREKSI'){
			$nomorUrutSebelumnya = $this->nomorUrut - 1;
			$beforeThis = mysql_fetch_array(mysql_query("select * from view_plafon where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			$getAllTahapSebelumnya = mysql_query("select * from view_plafon where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
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
						$resultBidang = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1' and c = '$c' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
						if($resultBidang  == 0){
						    $concat = $c1.'.'.$c;
							$arrKondisi[] = "concat(c1,'.',c) != '$concat' ";	
						}else{
							$resultUrusan = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1'  and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
							if($resultUrusan  == 0){
							 	$concat = $c1;
								$arrKondisi[] = "c1 != '$concat' ";	
							}
						}
					}
				}
			}
			
			$arrKondisi[] =  "no_urut = '$nomorUrutSebelumnya' ";
			

		}else{
			if($this->jenisFormTerakhir == "KOREKSI"){
				$nomorUrutSebelumnya = $this->urutTerakhir - 1;
				$beforeThis = mysql_fetch_array(mysql_query("select * from view_plafon where no_urut = '$nomorUrutSebelumnya' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
				$getAllTahapSebelumnya = mysql_query("select * from view_plafon where d !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' and no_urut ='$nomorUrutSebelumnya'   ");
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
							$resultBidang = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1' and c = '$c' and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
							if($resultBidang  == 0){
							    $concat = $c1.'.'.$c;
								$arrKondisi[] = "concat(c1,'.',c) != '$concat' ";	
							}else{
								$resultUrusan = mysql_num_rows(mysql_query("select * from view_plafon $Condition and d !='00' and c1 ='$c1'  and tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and no_urut = '$nomorUrutSebelumnya' "));
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
				$arrKondisi[] =  "no_urut = '$this->urutTerakhir'";
			}else{
				$getAll = mysql_query("select * from view_plafon where no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran='$this->jenisAnggaran' and d = '00' and c ='00'");
				while($rows = mysql_fetch_array($getAll)){
					$c1 = $rows['c1'];
					if(mysql_num_rows(mysql_query("select * from view_plafon where c1 ='$c1' and d!='00' and no_urut = '$this->urutTerakhir' and tahun='$this->tahun' and jenis_anggaran='$this->jenisAnggaran'")) == 0){
						$arrKondisi[] = " c1 !='$c1' ";
					}
				}
				$arrKondisi[] = "no_urut = '$this->urutTerakhir'";
			}
		}
		
  

		//hidden if fucking colomn is empty			
		$queryGetAll = "select * from view_plafon where tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' ";
		$execute = mysql_query($queryGetAll);
		while($rows = mysql_fetch_array($execute)){
			$c1 = $rows['c1'];
			$c = $rows['c'];
			$d = $rows['d'];
			$getUrusan = mysql_num_rows(mysql_query("select * from view_plafon where c1 = '$c1' and c !='00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'"));
			if($getUrusan > 0){
				$queryGetBidang = "select * from tabel_anggaran where c1='$c1' and c = '$c'  and d != '00' and tahun = '$this->tahun' and jenis_anggaran = '$this->jenisAnggaran'";
				$getBidang = mysql_num_rows(mysql_query($queryGetBidang));
				if($getBidang > 0){
				}else{
					if($c == '00'){
						$concat = "'.'";
					}else{
						$concat = $c1.".".$c;
					}
					$arrKondisi[] = " concat(c1,'.',c) != $concat ";
				}
			}else{
				$arrKondisi[] = " c1 !='$c1' ";
			}
			
		}
		//hidden if fucking colomn is empty	
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "jenis_anggaran = '$this->jenisAnggaran'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		/*switch($fmORDER1){
			case 'nama_tahap': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'waktu_aktif': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'waktu_pasif': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'modul': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'status': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
		}	
		if(empty($fmPILCARI))$arrOrders[] = " id_tahap $Asc1";*/
		
		/*$arrOrders[] = " concat(tahun,'.',c1,'.',c,'.',d,'.',e,'.',e1) $Asc1 " ;*/
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
		/*$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	*/
		
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
	
	function JudulLaporan($dari='', $sampai='',$judul=''){
		return "<div style='text-align:center;'>
				<span style='font-size:18px;font-weight:bold;text-decoration: underline;'>
					PLAFON $this->jenisAnggaran
				</span><br>
				<span class='ukurantulisanIdPenerimaan'>TAHUN : $this->tahun </span></div><br>";
	}
	function TandaTanganFooter($c1,$c,$d,$e,$e1){
		global $Main, $DataPengaturan,$HTTP_COOKIE_VARS;
		
		
		
		
		return "<br><div class='ukurantulisan'>
					<table width='100%'>
						<tr>
							<td class='ukurantulisan' valign='top' ></td>
							<td class='ukurantulisan' valign='top' width='50%' ></td>
							<td class='ukurantulisan' valign='top'><span style='margin-left:5px;'>Bandung, ".VulnWalkerTitiMangsa(date('Y-m-d'))."</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>Tanda Tangan Satu
<br><br><br><br><br></span></td>
							<td class='ukurantulisan' valign='top' width='50%' ></td>
							<td class='ukurantulisan' valign='top' ><span style='margin-left:5px;'>Tanda Tangan Dua
</span></td>
						</tr>
						<tr>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>Nama Tanda Tangan Satu</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>NIP</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>NIP Tanda Tangan Satu</td>
									</tr>
								</table>
							</td>
							<td class='ukurantulisan'></td>
							<td class='ukurantulisan'>
								<table width='100%'>
									<tr>
										<td class='ukurantulisan' width='75px'>Nama</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>Nama Tanda Tangan Dua</td>
									</tr>
									<tr>
										<td class='ukurantulisan'>NIP</td>
										<td class='ukurantulisan'> : </td>
										<td class='ukurantulisan'>NIP Tanda Tangan Dua</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>";
	}
}
$plafon = new plafonObj();

$arrayResult = VulnWalkerTahap($plafon->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];
$plafon->username = $_COOKIE['coID'];

$plafon->jenisForm = $jenisForm;
$plafon->nomorUrut = $nomorUrut;
$plafon->tahun = $tahun;
$plafon->jenisAnggaran = $jenisAnggaran;
$plafon->idTahap = $idTahap;


if(empty($plafon->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_plafon "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_plafon where id_anggaran = '$maxAnggaran'"));
	/*$plafon->tahun = "select max(id_anggaran) as max from view_plafon where nama_modul = 'PLAFON'";*/
	$plafon->tahun  = $get2['tahun'];
	$plafon->jenisAnggaran = $get2['jenis_anggaran'];
	$plafon->urutTerakhir = $get2['no_urut'];
	$plafon->jenisFormTerakhir = $get2['jenis_form_modul'];
	$plafon->urutSebelumnya = $plafon->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$plafon->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$plafon->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$plafon->noUrutTerakhirPlafon = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$plafon->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap();
	$plafon->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$plafon->idTahap'"));
	$plafon->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$plafon->idTahap'"));
	$plafon->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$plafon->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$plafon->noUrutTerakhirPlafon = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$plafon->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}


?>