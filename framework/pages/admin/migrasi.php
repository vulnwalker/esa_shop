<?php

class migrasiObj  extends DaftarObj2{	
	var $Prefix = 'migrasi';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_bi_kib_a_tmp'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('jml_harga','jml_barang');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array();//berdasar mode
	var $FieldSum_Cp2 = array();
	var $fieldSum_lokasi = array(6,9);
	var $totalCol = 13;	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Administrasi';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='migrasi.xls';
	var $Cetak_Judul = 'MIGRASI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'migrasiForm'; 	
			
	function setTitle(){
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		switch($fmPILKIB){
			case '01':$settitle="Migrasi KIB A";break;
			case '02':$settitle="Migrasi KIB B";break;
			case '03':$settitle="Migrasi KIB C";break;
			case '04':$settitle="Migrasi KIB D";break;
			case '05':$settitle="Migrasi KIB E";break;
			case '06':$settitle="Migrasi KIB F";break;
			case '07':$settitle="Migrasi KIB G";break;
			default:$settitle="Migrasi KIB A";break;
		}
		return $settitle;
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".formuploadexcel()","upload_f2.png","Upload Excel",'Upload Excel')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cekdata()","properties_f2.png","Cek", 'Cek')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".migrasidata()","dbrestore.png","Migrasi", 'Migrasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".formedit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".hapusini()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".hapussemua()","delete_f2.png","Hapus Semua", 'Hapus Semua').
			"<td>".genPanelIcon("javascript:".$this->Prefix.".rekapdata()","rekap24.png","Rekap", 'Rekap').
			"</td>";
	}
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		switch($fmPILKIB){
			case '01':$aqry = "select * from v_bi_kib_a_tmp $Kondisi $Order $Limit ";break;
			case '02':$aqry = "select * from v_bi_kib_b_tmp $Kondisi $Order $Limit ";break;
			case '03':$aqry = "select * from v_bi_kib_c_tmp $Kondisi $Order $Limit ";break;
			case '04':$aqry = "select * from v_bi_kib_d_tmp $Kondisi $Order $Limit ";break;
			case '05':$aqry = "select * from v_bi_kib_e_tmp $Kondisi $Order $Limit ";break;
			case '06':$aqry = "select * from v_bi_kib_f_tmp $Kondisi $Order $Limit ";break;
			case '07':$aqry = "select * from v_bi_kib_g_tmp $Kondisi $Order $Limit ";break;
			default:$aqry = "select * from v_bi_kib_a_tmp $Kondisi $Order $Limit ";break;
		}
		return $aqry;
	}
	
	function setSumHal_query($Kondisi, $fsum){
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		switch($fmPILKIB){
			case '01':$aqrysum = "select $fsum from v_bi_kib_a_tmp $Kondisi";break;
			case '02':$aqrysum = "select $fsum from v_bi_kib_b_tmp $Kondisi";break;
			case '03':$aqrysum = "select $fsum from v_bi_kib_c_tmp $Kondisi";break;
			case '04':$aqrysum = "select $fsum from v_bi_kib_d_tmp $Kondisi";break;
			case '05':$aqrysum = "select $fsum from v_bi_kib_e_tmp $Kondisi";break;
			case '06':$aqrysum = "select $fsum from v_bi_kib_f_tmp $Kondisi";break;
			case '07':$aqrysum = "select $fsum from v_bi_kib_g_tmp $Kondisi";break;
			default:$aqrysum = "select $fsum from v_bi_kib_a_tmp $Kondisi";break;
		}
			return $aqrysum;
	}
	
	//Hapus data -----------------------------------------------------------
  	function hapussemua(){
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$get = $this->getDaftarOpsi();
		$kondisi=$get['Kondisi'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$PILKIB=$_REQUEST['fmPILKIB'];
		
		
		if ($PILKIB!='' && $PILKIB!='00'){
			switch($PILKIB){
				case '01':$qry1 = "delete from bi_kib_a_tmp $kondisi";$cek.=$qry1;break;
				case '02':$qry1 = "delete from bi_kib_b_tmp $kondisi";$cek.=$qry1;break;
				case '03':$qry1 = "delete from bi_kib_c_tmp $kondisi";$cek.=$qry1;break;
				case '04':$qry1 = "delete from bi_kib_d_tmp $kondisi";$cek.=$qry1;break;
				case '05':$qry1 = "delete from bi_kib_e_tmp $kondisi";$cek.=$qry1;break;
				case '06':$qry1 = "delete from bi_kib_f_tmp $kondisi";$cek.=$qry1;break;
				case '07':$qry1 = "delete from bi_kib_g_tmp $kondisi";$cek.=$qry1;break;
				}
			$qry = mysql_query($qry1);
			if($qry==FALSE) $err="Gagal hapus data".mysql_error();	
		}
		
		if ($PILKIB=='00'){
			$x1=1;
			$x2=7;
			for ($x=$x1;$x<=$x2;$x++){
				if($x==1){
					$qry1 = "delete from bi_kib_a_tmp $kondisi";$cek.=$qry1;	
				}elseif($x==2){
					$qry1 = "delete from bi_kib_b_tmp $kondisi";$cek.=$qry1;
				}elseif($x==3){
					$qry1 = "delete from bi_kib_c_tmp $kondisi";$cek.=$qry1;
				}elseif($x==4){
					$qry1 = "delete from bi_kib_d_tmp $kondisi";$cek.=$qry1;
				}elseif($x==5){
					$qry1 = "delete from bi_kib_e_tmp $kondisi";$cek.=$qry1;
				}elseif($x==6){
					$qry1 = "delete from bi_kib_f_tmp $kondisi";$cek.=$qry1;
				}else if($x==7){
					$qry1 = "delete from bi_kib_g_tmp $kondisi";$cek.=$qry1;
				}
			
				$qry = mysql_query($qry1);
				if($qry==FALSE) $err="Gagal hapus data".mysql_error();		
			}
		}
				
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
//Tombol Hapus-------------------------------------------------------------------------
	function Hapus_Validasi($ids){//id -> multi field with space delimiter
		$errmsg =''; 
		
		return $errmsg;
	}
	function Hapus_Data($ids,$fmPILKIB){//id -> multi field with space delimiter
		$err = ''; $cek='';
		$KeyValue = explode(' ',$ids);
		$arrKondisi = array();
			
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !=''){
			$Kondisi = ' Where '.$Kondisi;
			
		switch($fmPILKIB){
			case '01':$tblName_hapus = "bi_kib_a_tmp";break;
			case '02':$tblName_hapus = "bi_kib_b_tmp";break;
			case '03':$tblName_hapus = "bi_kib_c_tmp";break;
			case '04':$tblName_hapus = "bi_kib_d_tmp";break;
			case '05':$tblName_hapus = "bi_kib_e_tmp";break;
			case '06':$tblName_hapus = "bi_kib_f_tmp";break;
			case '07':$tblName_hapus = "bi_kib_g_tmp";break;
			default:$tblName_hapus = "bi_kib_a_tmp";break;
		}	
			
		
			$aqry= "delete from ".$tblName_hapus.' '.$Kondisi; $cek.=$aqry;
			$qry = mysql_query($aqry);
			if ($qry==FALSE){
			$err = 'Gagal Hapus Data';
			}
		
		}
		return array('err'=>$err,'cek'=>$cek);
		}
		
		
	function Hapus($ids){
		$err=''; $cek='';
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$err = $this->Hapus_Validasi($ids[$i]);
			
			if($err ==''){
				$get = $this->Hapus_Data($ids[$i],$fmPILKIB);
				$err = $get['err'];
				$cek.= $get['cek'];
				//if ($errmsg=='') $errmsg = $this->Hapus_Data_After($ids[$i]);
				if ($err != '') break;
				 				
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
//Cek data -----------------------------------------------------------
	function cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1){
		$panjangc='2'; $panjangd='2'; $panjange='2'; $panjange1='3';
		$panjangthn='4'; $panjangf='2'; $panjangg='2'; $panjangh='2'; $panjangi='2'; $panjangj='3';
		
		$f=$isi['f']; $g=$isi['g']; $h=$isi['h']; $i=$isi['i']; $j=$isi['j'];
		$c=$isi['c']; $d=$isi['d']; $e=$isi['e']; $e1=$isi['e1'];
		$thn_perolehan=$isi['thn_perolehan']; $jml_harga=$isi['jml_harga'];
		$jml_barang=$isi['jml_barang']; $tgl_buku=$isi['tgl_buku']; $status_hak=$isi['status_hak'];
		$ket_cek='';
		
		$simbolohm='Ω'; $simboldiameter='φ';$simbolpetik1='&#8216';$simbolpetik2='"';$simbolpetik='`';
				
		if ($PILKIB=='01'){
			if ($f<>'01'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' 											
												";
			if ($status_hak=='' || strlen($status_hak)>1){
				$ket_cek.='- Status hak tidak sesuai';
			}
		}elseif ($PILKIB=='02'){
			if ($f<>'02'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													 id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' or
													 id='".$id."' and merk like '%".$simboldiameter."%' or id='".$id."' and merk like '%".$simbolohm."%' or id='".$id."' and merk like '%".$simbolpetik1."%' or id='".$id."' and merk like '%".$simbolpetik2."%' or id='".$id."' and merk like '%".$simbolpetik."%'											
												";
		}elseif ($PILKIB=='03'){
			if ($f<>'03'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' 											
												";
		}elseif ($PILKIB=='04'){
			if ($f<>'04'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' or 											
												";
		}elseif ($PILKIB=='05'){
			if ($f<>'05'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' or
													id='".$id."' and buku_spesifikasi like '%".$simboldiameter."%' or id='".$id."' and buku_spesifikasi like '%".$simbolohm."%' or id='".$id."' and buku_spesifikasi like '%".$simbolpetik1."%' or id='".$id."' and buku_spesifikasi like '%".$simbolpetik2."%' or id='".$id."' and buku_spesifikasi like '%".$simbolpetik."%'	or										
												";
		}elseif ($PILKIB=='06'){
			if ($f<>'06'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' or											
												";
		}else{
			if ($f<>'07'){
				$ket_cek.='- kode barang tidak sesuai';
			}
			$aqrycek="SELECT * from ".$tabel." where id='".$id."' and nama_barang like '%".$simboldiameter."%' or id='".$id."' and nama_barang like '%".$simbolohm."%' or id='".$id."' and nama_barang like '%".$simbolpetik1."%' or id='".$id."' and nama_barang like '%".$simbolpetik2."%' or id='".$id."' and nama_barang like '%".$simbolpetik."%' or
													id='".$id."' and ket like '%".$simboldiameter."%' or id='".$id."' and ket like '%".$simbolohm."%' or id='".$id."' and ket like '%".$simbolpetik1."%' or id='".$id."' and ket like '%".$simbolpetik2."%' or id='".$id."' and ket like '%".$simbolpetik."%' 											
												";
		}
		//cek kode c skpd	
			if ($xlock_c<>'00' && $xlock_c<>$c){
				$ket_cek.='- kode c skpd tidak sesuai';
			}elseif($panjangc<>strlen($c)){
				$ket_cek.='- kode c skpd tidak sesuai';
			}
		//cek kode d skpd
			if ($xlock_d<>'00' && $xlock_d<>$d){
				$ket_cek.='- kode d skpd tidak sesuai';
			}elseif($panjangd<>strlen($d)){
				$ket_cek.='- kode d skpd tidak sesuai';
			}
		//cek kode e skpd	
			if ($xlock_e<>'00' && $xlock_e<>$e){
				$ket_cek.='- kode e skpd tidak sesuai';
			}elseif($panjange<>strlen($e)){
				$ket_cek.='- kode e skpd tidak sesuai';
			}
		//cek kode e1 skpd
			if ($xlock_e1<>'00' && $xlock_e1<>$e1){
				$ket_cek.='- kode e1 skpd tidak sesuai';
			}elseif($panjange1<>strlen($e1)){
				$ket_cek.='- kode e1 skpd tidak sesuai';
			}
		//cek kode f skpd
			if($panjangf<>strlen($f)){
				$ket_cek.='- kode f Barang tidak sesuai';
			}
		//cek kode g skpd
			if($panjangg<>strlen($g)){
				$ket_cek.='- kode g Barang tidak sesuai';
			}
		//cek kode h skpd
			if($panjangh<>strlen($h)){
				$ket_cek.='- kode h Barang tidak sesuai';
			}
		//cek kode i skpd
			if($panjangi<>strlen($i)){
				$ket_cek.='- kode i Barang tidak sesuai';
			}
		//cek kode j skpd
			if($panjangj<>strlen($j)){
				$ket_cek.='- kode j Barang tidak sesuai';
			}
		//cek tahun perolehan
			if ($thn_perolehan=='' || $panjangthn<>strlen($thn_perolehan)){
				$ket_cek.='- tahun perolehan tidak sesuai';
			}
		//cek jumlah harga
			if ($jml_harga==''){
				$ket_cek.='- Jumlah harga tidak sesuai';
			}
		//cek jumlah barang
			if ($jml_barang==''){
				$ket_cek.='- Jumlah Barang tidak sesuai';
			}
		//cek Simbol di pada sebuah baris
			$ceksimbol=mysql_query($aqrycek);
			$cekjmlsimbol=mysql_num_rows($ceksimbol);
			if ($cekjmlsimbol>=1){
				$ket_cek.='- Terdapat simbol di data ini';
			}
			//if ($tgl_buku==''){
			//	$ket_cek='- tanggal buku tidak sesuai';
			//}
			return $ket_cek;
	}
	
	
	function cekdata1($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1){
		if ($PILKIB=='01'){
			
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_a_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_a_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
		}
		elseif ($PILKIB=='02'){
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_b_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_b_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
			
		}elseif ($PILKIB=='03'){
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_c_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_c_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
			
		}elseif ($PILKIB=='04'){
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_d_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_d_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
		}elseif ($PILKIB=='05'){
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_e_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_e_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
			
		}elseif ($PILKIB=='06'){
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_f_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_f_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
		}else{
			$ket_cek= $this->cekdata_validasi($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			$sql2='';
			if ($ket_cek==''){
				$sql2="update bi_kib_g_tmp set stcheck=1, ket_cek='' where id='".$id."'";
				$qry=mysql_query($sql2);
			}else{
				$sql2="update bi_kib_g_tmp set stcheck=2, ket_cek='".$ket_cek."' where id='".$id."'";
				$qry=mysql_query($sql2);
			}
		}
		
	}
	
	
	function cekdata(){
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$PILKIB=$_REQUEST['fmPILKIB'];
		$xlock_c=cekPOST($this->Prefix.'SkpdfmSKPD');
		$xlock_d=cekPOST($this->Prefix.'SkpdfmUNIT');
		$xlock_e=cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$xlock_e1=cekPOST($this->Prefix.'SkpdfmSEKSI');
		if($PILKIB!='00'){
			switch($PILKIB){
				case '01':$aqry = "select * from v_bi_kib_a_tmp";$tabel="bi_kib_a_tmp";break;
				case '02':$aqry = "select * from v_bi_kib_b_tmp";$tabel="bi_kib_b_tmp";break;
				case '03':$aqry = "select * from v_bi_kib_c_tmp";$tabel="bi_kib_c_tmp";break;
				case '04':$aqry = "select * from v_bi_kib_d_tmp";$tabel="bi_kib_d_tmp";break;
				case '05':$aqry = "select * from v_bi_kib_e_tmp";$tabel="bi_kib_e_tmp";break;
				case '06':$aqry = "select * from v_bi_kib_f_tmp";$tabel="bi_kib_f_tmp";break;
				case '07':$aqry = "select * from v_bi_kib_g_tmp";$tabel="bi_kib_g_tmp";break;
			}
			
			$qry=mysql_query($aqry);
			$jml_record=mysql_num_rows($qry);
			if ($jml_record>=1){
			while ($isi=mysql_fetch_array($qry)){
			$id=$isi['id'];
				$this->cekdata1($isi,$id,$tabel,$PILKIB,$xlock_f,$xlock_c,$xlock_d,$xlock_e,$xlock_e1);
			}
		}
		if($qry==FALSE) $err="Gagal isi data".mysql_error();
		}else{
			$err="KIB belum dipilih!";
		}
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
//MIgrasi Data======================================================================================================
	function insert_bi_kib($isi,$newisi,$wnoreg=FALSE,$xlock_f){
		
		
		if ($xlock_f=='01'){
		$ins_kib="insert into kib_a (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		luas,alamat,status_hak,sertifikat_tgl,sertifikat_no,ket,tahun )
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['luas'].
		"','".$newisi['alamat']."','".$newisi['status_hak']."','".$newisi['sertifikat_tgl']."','".$newisi['sertifikat_no'].
		"','".$newisi['ket']."','".$newisi['thn_perolehan']."')";
		} else if ($xlock_f=='02'){
		$ins_kib="insert into kib_b (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,merk,
		ukuran,bahan,no_pabrik,no_rangka,
		no_mesin,no_polisi,no_bpkb,ket,tahun)
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['merk'].
		"','".$newisi['ukuran']."','".$newisi['bahan']."','".$newisi['no_pabrik']."','".$newisi['no_rangka'].
		"','".$newisi['no_mesin']."','".$newisi['no_polisi']."','".$newisi['no_bpkb']."','".$newisi['ket']."','".$newisi['thn_perolehan']."')";
		} else if ($xlock_f=='03'){
		$ins_kib="insert into kib_c (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		kondisi_bangunan,konstruksi_tingkat,konstruksi_beton,luas_lantai,alamat,luas,status_tanah,ket,tahun )
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['kondisi_bangunan'].
		"','".$newisi['konstruksi_tingkat']."','".$newisi['konstruksi_beton']."','".$newisi['luas_lantai']."','".$newisi['alamat'].
		"','".$newisi['luas']."','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['thn_perolehan']."')";	
		} else if ($xlock_f=='04'){
		$ins_kib="insert into kib_d (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		konstruksi,panjang,lebar,luas,alamat,status_tanah,ket,tahun)
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['konstruksi'].
		"','".$newisi['panjang']."','".$newisi['lebar']."','".$newisi['luas']."','".$newisi['alamat'].
		"','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['thn_perolehan']."')";	
		} else if ($xlock_f=='05'){
		$ins_kib="insert into kib_e (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,ket,tahun)
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['buku_judul'].
		"','".$newisi['buku_spesifikasi']."','".$newisi['seni_asal_daerah']."','".$newisi['seni_pencipta']."','".$newisi['seni_bahan'].
		"','".$newisi['hewan_jenis']."','".$newisi['hewan_ukuran']."','".$newisi['ket']."','".$newisi['thn_perolehan']."')";
		} else if ($xlock_f=='06'){
		$ins_kib="insert into kib_f (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		bangunan,konstruksi_tingkat,konstruksi_beton,luas,alamat,status_tanah,ket,tahun)
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['bangunan'].
		"','".$newisi['konstruksi_tingkat']."','".$newisi['konstruksi_beton']."','".$newisi['luas']."','".$newisi['alamat'].
		"','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['tahun']."')";
		} else if ($xlock_f=='07'){
		$ins_kib="insert into kib_g (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
		tahun,uraian,software_nama,kajian_nama,kerjasama_nama,ket)
		values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['thn_perolehan'].
		"','".$newisi['uraian']."','".$newisi['software_nama']."','".$newisi['kajian_nama']."','".$newisi['kerjasama_nama'].
		"','".$newisi['ket']."')";
		}
		

		$ins_bi="insert into buku_induk (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan,
		jml_barang,satuan,harga,jml_harga,asal_usul,kondisi,status_barang,tahun,tgl_buku,verifikasi)
		values('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
		"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
		"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['thn_perolehan'].
		"','".$newisi['jml_barang']."','".$newisi['satuan']."','".$newisi['harga']."','".$newisi['jml_harga'].
		"','".$newisi['asal_usul']."','".$newisi['kondisi']."','".$newisi['status_barang'].
		"','".$newisi['thn_perolehan']."','".$newisi['tgl_buku']."','99')";
		
		$qry1=mysql_query($ins_bi);
		$qry2=mysql_query($ins_kib);
				
	}
	
	function update_idbi_idlama($xlock_f){
		$upd_bi="update buku_induk set id_lama=id,idawal=id where id_lama is null";
		$qry1=mysql_query($upd_bi);
		if ($xlock_f=='01')
		{
		$upd_kib="UPDATE
  		`kib_a` `a` INNER JOIN
  		`buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    		`a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    		`a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    		`a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
   		 	`a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    		`a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		} else if ($xlock_f=='02')
		{
		$upd_kib="UPDATE
		  `kib_b` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		} else if ($xlock_f=='03')
		{
		$upd_kib="UPDATE
		  `kib_c` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		} else if ($xlock_f=='04')
		{
		$upd_kib="UPDATE
		  `kib_d` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		} else if ($xlock_f=='05')
		{
		$upd_kib="UPDATE
		  `kib_e` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		} else if ($xlock_f=='06')
		{
		$upd_kib="UPDATE
		  `kib_f` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
		}else if ($xlock_f=='07'){
		$upd_kib="UPDATE
		  `kib_g` `a` INNER JOIN
		  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
		    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
		    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
		    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
		    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
		    `a`.`noreg` = `b`.`noreg`
		SET `a`.`idbi` = `b`.`id`
		where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";	
		}
		
		$qry1=mysql_query($upd_kib);
		
	}

	function insert_bi_kib_newnoreg($isi,$newisi,$wnoreg=FALSE,$xlock_f){

	$kondisix=" a1='".$newisi['a1']."' ".
	" and a='".$newisi['a']."' ".
	" and b='".$newisi['b']."' ".
	" and c='".$newisi['c']."' ".
	" and d='".$newisi['d']."' ".
	" and e='".$newisi['e']."' ".
	" and e1='".$newisi['e1']."' ".
	" and f='".$newisi['f']."' ".
	" and g='".$newisi['g']."' ".
	" and h='".$newisi['h']."' ".
	" and i='".$newisi['i']."' ".
	" and j='".$newisi['j']."' ".
	" and thn_perolehan='".$newisi['thn_perolehan']."' ";


		$sql1="select max(noreg) as maxnoreg from buku_induk where $kondisix ";
		$qry = mysql_fetch_array(mysql_query($sql1));
		$x=$qry['maxnoreg']+1;
		$xnew=$x+10000;
		$xxnew=substr($xnew, -4);
		$newisi['noreg']=$xxnew;
		$this->insert_bi_kib($isi,$newisi,TRUE,$xlock_f);
		
}

	function cek_bi_kib_new($newisi){
	
	$kondisi=" a1='".$newisi['a1']."' ".
	" and a='".$newisi['a']."' ".
	" and b='".$newisi['b']."' ".
	" and c='".$newisi['c']."' ".
	" and d='".$newisi['d']."' ".
	" and e='".$newisi['e']."' ".
	" and e1='".$newisi['e1']."' ".
	" and f='".$newisi['f']."' ".
	" and g='".$newisi['g']."' ".
	" and h='".$newisi['h']."' ".
	" and i='".$newisi['i']."' ".
	" and j='".$newisi['j']."' ".
	" and thn_perolehan='".$newisi['thn_perolehan']."' ".
	" and noreg='".$newisi['noreg']."' ";

	$kondisix=" a1='".$newisi['a1']."' ".
	" and a='".$newisi['a']."' ".
	" and b='".$newisi['b']."' ".
	" and c='".$newisi['c']."' ".
	" and d='".$newisi['d']."' ".
	" and e='".$newisi['e']."' ".
	" and e1='".$newisi['e1']."' ".
	" and f='".$newisi['f']."' ".
	" and g='".$newisi['g']."' ".
	" and h='".$newisi['h']."' ".
	" and i='".$newisi['i']."' ".
	" and j='".$newisi['j']."' ".
	" and thn_perolehan='".$newisi['thn_perolehan']."' ";

	$sql="select * from buku_induk where $kondisi ";
	
	$jmlData= mysql_num_rows(mysql_query($sql));
	
	return $jmlData;
	}
	
	function update_stmigrasi_kib_tmp($isi,$xlock_f){
		if	($xlock_f==1){
			$upd_tmp="update bi_kib_a_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}elseif($xlock_f==2){
			$upd_tmp="update bi_kib_b_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}elseif($xlock_f==3){
			$upd_tmp="update bi_kib_c_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}elseif($xlock_f==4){
			$upd_tmp="update bi_kib_d_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}elseif($xlock_f==5){
			$upd_tmp="update bi_kib_e_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}elseif($xlock_f==6){
			$upd_tmp="update bi_kib_f_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}else if($xlock_f==7){
			$upd_tmp="update bi_kib_g_tmp set stmigrasi='1' where id='".$isi['id']."'";
		}
	$qry1=mysql_query($upd_tmp);
	
	}		
	
	function migrasidata(){
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$lock_c=cekPOST($this->Prefix.'SkpdfmSKPD');
		$lock_d=cekPOST($this->Prefix.'SkpdfmUNIT');
		$lock_e=cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$lock_e1=cekPOST($this->Prefix.'SkpdfmSEKSI');
		$PILKIB=$_REQUEST['fmPILKIB'];
	 	$x1=1;
		$x2=7;
		for ($x=$x1;$x<=$x2;$x++){
			if ($PILKIB=='00'){
			if ($x==1){
 				$idprs='insertkiba';
 			}elseif ($x==2){
 				$idprs='insertkibb';
			}elseif ($x==3){
			 	$idprs='insertkibc';
			}elseif ($x==4){
			 	$idprs='insertkibd';
			}elseif ($x==5){
 				$idprs='insertkibe';
 			}elseif ($x==6){
			 	$idprs='insertkibf';
 			}elseif ($x==7){
			 	$idprs='insertkibg';
 			}	
			}else{
				switch($PILKIB){
				case '01':$idprs='insertkiba';break;
				case '02':$idprs='insertkibb';break;
				case '03':$idprs='insertkibc';break;
				case '04':$idprs='insertkibd';break;
				case '05':$idprs='insertkibe';break;
				case '06':$idprs='insertkibf';break;
				case '07':$idprs='insertkibg';break;
				}
			}
			
			if ($idprs=='insertkiba'){
				$tablename='v_bi_kib_a_tmp';
				$xlock_f='01';				
			}elseif ($idprs=='insertkibb'){
				$tablename='v_bi_kib_b_tmp';
				$xlock_f='02';
			}elseif ($idprs=='insertkibc'){
				$tablename='v_bi_kib_c_tmp';
				$xlock_f='03';
			}elseif ($idprs=='insertkibd'){
				$tablename='v_bi_kib_d_tmp';
				$xlock_f='04';
			}elseif ($idprs=='insertkibe'){
				$tablename='v_bi_kib_e_tmp';
				$xlock_f='05';
			}elseif ($idprs=='insertkibf'){
				$tablename='v_bi_kib_f_tmp';
				$xlock_f='06';
			}elseif ($idprs=='insertkibg'){
				$tablename='v_bi_kib_g_tmp';
				$xlock_f='07';
			}
			
			if ($tablename!='' && $xlock_f!=''){
				$no = 0;
				$sqry="select * from ".$tablename." where stcheck=1 and stmigrasi=0 order by id";$cek=$sqry;
				$qry=mysql_query($sqry);
				if($qry==FALSE) $err="Gagal isi data".mysql_error();
				while($isi=mysql_fetch_array($qry)){
					$no++;
					$jmlharga=$isi['jml_harga'];
					$jmlbarang=$isi['jml_barang'];
					$cnt=1;
					if($jmlbarang>0){
						$harga=$jmlharga/$jmlbarang;
					}else{
						$harga=$jmlharga;
					}
					if ($isi['c']!='' && $isi['e1']!='' && $isi['f']!='' && $isi['j']!='' && $xlock_f!=''){
						while($cnt<=$jmlbarang){
							$newisi=$isi;
							$newisi['jml_barang']='1';
							$newisi['jml_harga']=$harga;
							$newisi['harga']=$harga;
						//untuk noreg yang kosong
							if ($newisi['noreg']==''){
								$newisi['noreg']='0001';
							}
						//untuk noreg yang jumlah karakternya kuran
							if(strlen($newisi['noreg'])<4){
								$x=$newisi['noreg'];
								$xnew=$x+10000;
								$xxnew=substr($xnew, -4);
								$newisi['noreg']=$xxnew;
							}					
							$jmlData=$this->cek_bi_kib_new($newisi);
							if($jmlData==0){
								$this->insert_bi_kib($isi,$newisi,$wnoreg=FALSE,$xlock_f);								
							}else{
								$this->insert_bi_kib_newnoreg($isi,$newisi,true,$xlock_f);								
							}
							$cnt++;
						}//while($cnt<=$jmlbarang)
						
					}//if ($isi['c']!='' && $isi['e1']!='' && $isi['f']!='' && $isi['j']!='' && $xlock_f!='')
					$this->update_stmigrasi_kib_tmp($isi,$xlock_f);
				}//while($isi=mysql_fetch_array($qry)){
				
			if ($no>0){
					$this->update_idbi_idlama($xlock_f);	
				}
			}//if ($tablename!='' && $lock_c!='' && $lock_f!=''){
		}//for ($x=$x1;$x<=$x2;$x++){
							
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
//Rekap Data ===================================================================================================
	function datarekap(){
		global $Main;
		$kondisicek="where stcheck='2'";
		$kondisimigrasi="where stmigrasi='1'";
		$sum="sum(jml_barang) as total_data";
		
		//Kib A
		$rekapkiba=mysql_fetch_array(mysql_query("select $sum from bi_kib_a_tmp "));
		$rekapkibacek=mysql_fetch_array(mysql_query("select $sum from bi_kib_a_tmp $kondisicek"));
		$rekapkibamigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_a_tmp $kondisimigrasi"));
	//Kib B	
		$rekapkibb=mysql_fetch_array(mysql_query("select $sum from bi_kib_b_tmp "));
		$rekapkibbcek=mysql_fetch_array(mysql_query("select $sum from bi_kib_b_tmp $kondisicek"));
		$rekapkibbmigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_b_tmp $kondisimigrasi"));
	//Kib C	
		$rekapkibc=mysql_fetch_array(mysql_query("select $sum from bi_kib_c_tmp "));
		$rekapkibccek=mysql_fetch_array(mysql_query("select $sum from bi_kib_c_tmp $kondisicek"));
		$rekapkibcmigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_c_tmp $kondisimigrasi"));
	//Kib D	
		$rekapkibd=mysql_fetch_array(mysql_query("select $sum from bi_kib_d_tmp "));
		$rekapkibdcek=mysql_fetch_array(mysql_query("select $sum from bi_kib_d_tmp $kondisicek"));
		$rekapkibdmigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_d_tmp $kondisimigrasi"));
	//Kib E	
		$rekapkibe=mysql_fetch_array(mysql_query("select $sum from bi_kib_e_tmp "));
		$rekapkibecek=mysql_fetch_array(mysql_query("select $sum from bi_kib_e_tmp $kondisicek"));
		$rekapkibemigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_e_tmp $kondisimigrasi"));
	//Kib F	
		$rekapkibf=mysql_fetch_array(mysql_query("select $sum from bi_kib_f_tmp "));
		$rekapkibfcek=mysql_fetch_array(mysql_query("select $sum from bi_kib_f_tmp $kondisicek"));
		$rekapkibfmigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_f_tmp $kondisimigrasi"));
	//Kib G	
		$rekapkibg=mysql_fetch_array(mysql_query("select $sum from bi_kib_g_tmp "));
		$rekapkibgcek=mysql_fetch_array(mysql_query("select $sum from bi_kib_g_tmp $kondisicek"));
		$rekapkibgmigrasi=mysql_fetch_array(mysql_query("select $sum from bi_kib_g_tmp $kondisimigrasi"));
		
	//total 
		$totdata=$rekapkiba['total_data']+$rekapkibb['total_data']+$rekapkibc['total_data']+$rekapkibd['total_data']+$rekapkibe['total_data']+$rekapkibf['total_data']+$rekapkibg['total_data'];
		$toterror=$rekapkibacek['total_data']+$rekapkibbcek['total_data']+$rekapkibccek['total_data']+$rekapkibdcek['total_data']+$rekapkibecek['total_data']+$rekapkibfcek['total_data']+$rekapkibgcek['total_data'];
		$totmigrasi=$rekapkibamigrasi['total_data']+$rekapkibbmigrasi['total_data']+$rekapkibcmigrasi['total_data']+$rekapkibdmigrasi['total_data']+$rekapkibemigrasi['total_data']+$rekapkibfmigrasi['total_data']+$rekapkibgmigrasi['total_data'];
		
	 echo 
			"<html>
			<head>
					<title>$Main->Judul</title>
					
				</head>
				<body >
						
		<table border='1'>
		<th>Keterangan</th><th>Jumlah Data</th><th>Data Error</th><th>Data Migrasi</th>
		<tr><td>KIB A</td><td>".$rekapkiba['total_data']."</td><td>".$rekapkibacek['total_data']."</td><td>".$rekapkibamigrasi['total_data']."</td></tr>
		<tr><td>KIB B</td><td>".$rekapkibb['total_data']."</td><td>".$rekapkibbcek['total_data']."</td><td>".$rekapkibbmigrasi['total_data']."</td></tr>
		<tr><td>KIB C</td><td>".$rekapkibc['total_data']."</td><td>".$rekapkibccek['total_data']."</td><td>".$rekapkibcmigrasi['total_data']."</td></tr>
		<tr><td>KIB D</td><td>".$rekapkibd['total_data']."</td><td>".$rekapkibdcek['total_data']."</td><td>".$rekapkibdmigrasi['total_data']."</td></tr>
		<tr><td>KIB E</td><td>".$rekapkibe['total_data']."</td><td>".$rekapkibecek['total_data']."</td><td>".$rekapkibemigrasi['total_data']."</td></tr>
		<tr><td>KIB F</td><td>".$rekapkibf['total_data']."</td><td>".$rekapkibfcek['total_data']."</td><td>".$rekapkibfmigrasi['total_data']."</td></tr>
		<tr><td>KIB G</td><td>".$rekapkibg['total_data']."</td><td>".$rekapkibgcek['total_data']."</td><td>".$rekapkibgmigrasi['total_data']."</td></tr>
		<tr><td>Total</td><td>".$totdata."</td><td>".$toterror."</td><td>".$totmigrasi."</td></tr>
		</table>
			
			</body>	
			</html>";
	}
	
//Simpan Edit ==================================================================================================
	function simpan(){
		global $Main;
		global $HTTP_COOKIE_VARS;
		$uid = $HTTP_COOKIE_VARS['coID'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$lockedit=$_REQUEST['editf'];
		$ekd_skpd=$_REQUEST['ekd_skpd'];
		$expkdskpd=explode('.',$ekd_skpd);
		$ec=$expkdskpd['0'];$ed=$expkdskpd['1'];$ee=$expkdskpd['2'];$ee1=$expkdskpd['3'];
		$ekode_barang=$_REQUEST['ekode_barang'];
		$enama_barang=$_REQUEST['enama_barang'];
		$ethn_perolehan=$_REQUEST['ethn_perolehan'];
		$ejml_barang=$_REQUEST['ejml_barang'];
		$esatuan=$_REQUEST['esatuan'];
		$ejml_harga=$_REQUEST['ejml_harga'];
		$estatus_barang=$_REQUEST['estatus_barang'];
		$easal_usul=$_REQUEST['easal_usul'];
		$eket=$_REQUEST['eket'];
		$eluas=$_REQUEST['eluas'];
		$ealamat=$_REQUEST['ealamat'];
		$esertifikat_tgl=$_REQUEST['esertifikat_tgl'];
		$esertifikat_no=$_REQUEST['esertifikat_no'];
		$epenggunaan=$_REQUEST['epenggunaan'];
		$ekota=$_REQUEST['ekota'];
		$estatus_hak=$_REQUEST['estatus_hak'];
		$etgl_buku=$_REQUEST['etgl_buku'];
		$emerk=$_REQUEST['emerk'];
		$eukuran=$_REQUEST['eukuran'];
		$eno_rangka=$_REQUEST['eno_rangka'];
		$eno_mesin=$_REQUEST['eno_mesin'];
		$eno_polisi=$_REQUEST['eno_polisi'];
		$eno_bpkb=$_REQUEST['eno_bpkb'];
		$ebahan=$_REQUEST['ebahan'];
		$ekondisi=$_REQUEST['ekondisi'];
		$ekondisi_bangunan=$_REQUEST['ekondisi_bangunan'];
		$ekonstruksi_tingkat=$_REQUEST['ekonstruksi_tingkat'];
		$ekonstruksi_beton=$_REQUEST['ekonstruksi_beton'];
		$eluas_lantai=$_REQUEST['eluas_lantai'];
		$estatus_tanah=$_REQUEST['estatus_tanah'];
		$ekonstruksi=$_REQUEST['ekonstruksi'];
		$epanjang=$_REQUEST['epanjang'];
		$elebar=$_REQUEST['elebar'];
		$ekode_tanah=$_REQUEST['ekode_tanah'];
		$ebuku_judul=$_REQUEST['ebuku_judul'];
		$ebuku_spesifikasi=$_REQUEST['ebuku_spesifikasi'];
		$eseni_asal_daerah=$_REQUEST['eseni_asal_daerah'];
		$eseni_pencipta=$_REQUEST['eseni_pencipta'];
		$eseni_bahan=$_REQUEST['eseni_bahan'];
		$ehewan_jenis=$_REQUEST['ehewan_jenis'];
		$ehewan_ukuran=$_REQUEST['ehewan_ukuran'];
		$ebangunan=$_REQUEST['ebangunan'];
		$euraian=$_REQUEST['euraian'];
		$esoftware_nama=$_REQUEST['esoftware_nama'];
		$ekajian_nama=$_REQUEST['ekajian_nama'];
		$epencipta=$_REQUEST['epencipta'];
		$ekerjasama_nama=$_REQUEST['ekerjasama_nama'];
		
		//proses Edit
		if($lockedit=='01'){
			$aqry = "
					update bi_kib_a_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					ket='$eket',
					tgl_buku='$etgl_buku',
					luas='$eluas',
					alamat='$ealamat',
					sertifikat_tgl='$esertifikat_tgl',
					sertifikat_no='$esertifikat_no',
					penggunaan='$epenggunaan',
					kota = '$ekota', 
					status_hak = '$estatus_hak'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB A ".mysql_error();
		}elseif($lockedit=='02'){
			$aqry = "
					update bi_kib_b_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					merk='$emerk',
					ukuran='$eukuran',
					no_rangka='$eno_rangka',
					no_mesin='$eno_mesin',
					no_polisi='$eno_polisi',
					no_bpkb = '$eno_bpkb', 
					bahan = '$ebahan'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB B ".mysql_error();
		}elseif($lockedit=='03'){
			$aqry = "
					update bi_kib_c_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					kondisi_bangunan='$ekondisi_bangunan',
					konstruksi_tingkat='$ekonstruksi_tingkat',
					konstruksi_beton='$ekonstruksi_beton',
					luas_lantai='$eluas_lantai',
					alamat='$ealamat',
					luas = '$eluas', 
					status_tanah = '$estatus_tanah'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB C ".mysql_error();
		}elseif($lockedit=='04'){
			$aqry = "
					update bi_kib_d_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					konstruksi='$ekonstruksi',
					panjang='$epanjang',
					lebar='$elebar',
					kota='$ekota',
					alamat='$ealamat',
					luas = '$eluas', 
					status_tanah = '$estatus_tanah',
					kode_tanah = '$ekode_tanah'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB D ".mysql_error();
		}elseif($lockedit=='05'){
			$aqry = "
					update bi_kib_e_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					buku_judul='$ebuku_judul',
					buku_spesifikasi='$ebuku_spesifikasi',
					seni_asal_daerah='$eseni_asal_daerah',
					seni_pencipta='$eseni_pencipta',
					seni_bahan='$eseni_bahan',
					hewan_jenis = '$ehewan_jenis', 
					hewan_ukuran = '$ehewan_ukuran'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB E ".mysql_error();
		}elseif($lockedit=='06'){
			$aqry = "
					update bi_kib_f_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					bangunan='$ebangunan',
					konstruksi_tingkat='$ekonstruksi_tingkat',
					konstruksi_beton='$ekonstruksi_beton',
					alamat='$ealamat',
					luas = '$eluas', 
					kota = '$ekota'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB F ".mysql_error();
		}elseif($lockedit=='07'){
			$aqry = "
					update bi_kib_g_tmp set 
					kd_skpd='$ekd_skpd',
					c='$ec',d='$ed',e='$ee',e1='$ee1',
					kode_barang='$ekode_barang',
					nama_barang='$enama_barang',
					thn_perolehan='$ethn_perolehan',
					jml_barang='$ejml_barang',
					satuan='$esatuan',
					jml_harga='$ejml_harga',
					status_barang='$estatus_barang',
					asal_usul='$easal_usul',
					kondisi='$ekondisi',
					ket='$eket',
					tgl_buku='$etgl_buku',
					uraian='$euraian',
					software_nama='$esoftware_nama',
					kajian_nama='$ekajian_nama',
					pencipta='$epencipta',
					kerjasama_nama = '$ekerjasama_nama'
					where id = '$idplh'";	$cek.= "$aqry; ";
				$qry = mysql_query($aqry);
				if($qry==FALSE) $err="Gagal edit data KIB G ".mysql_error();
		}
		if($qry==FALSE) $err="Gagal edit data ".mysql_error();
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}


//SET Selector Other ===========================================================================================	
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
			$fm = $this->setFormupload();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'formedit':{				
			$fm = $this->setformedit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'rekapdata':{
					$this->datarekap();							
					break;
		}
	 	case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   case 'settitle':{
			$get= $this->setTitle();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   } 
	   case 'hapussemua':{
			$get= $this->hapussemua();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   } 
	   case 'cekdata':{
			$get= $this->cekdata();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   } 
	    case 'migrasidata':{
			$get= $this->migrasidata();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   } 
	   case 'hapus':{
			$get= $this->Hapus();
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
			 "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/admin/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='ajax-upload/js/jquery.js' ></script>".
			 "<script type='text/javascript' src='ajax-upload/js/ajaxfileupload.js'></script>".			
			$scriptload;
	}
	
	
//form ==================================
	function setFormupload(){
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
    function setformedit(){
		global $SensusTmp , $Main;
		$cek = ''; $err=''; $content='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$fmPILKIB=$_REQUEST['fmPILKIB'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		//get data
		
		//query ambil data barang
		switch($fmPILKIB){
			case '01':$aqry = "select * from bi_kib_a_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='01';break;
			case '02':$aqry = "select * from bi_kib_b_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='02';break;
			case '03':$aqry = "select * from bi_kib_c_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='03';break;
			case '04':$aqry = "select * from bi_kib_d_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
			   		  $lockedit='04';break;
			case '05':$aqry = "select * from bi_kib_e_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='05';break;
			case '06':$aqry = "select * from bi_kib_f_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='06';break;
			case '07':$aqry = "select * from bi_kib_g_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;
					  $lockedit='07';break;
			default:$aqry = "select * from bi_kib_a_tmp where Id='".$this->form_idplh."'"; $cek.=$aqry;$lockedit='01';break;
		}	
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm1($dt,$lockedit);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
   function setForm1($dt,$lockedit){
   	 global $SensusTmp, $Main;
   	 $cek = ''; $err=''; $content='';
	 $json=TRUE;
	 $form_name = $this->Prefix.'_formsimpan';				
	 $this->form_width = 550;
	 $this->form_height = 450;
	 $this->form_caption = 'Edit';
	// $kode_barang=$dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
	 //$kode_skpd=$dt['c'].'.'.$dt['d'].'.'.$dt['e'].'.'.$dt['e1'];
	 //$editf=$dt['f'];
	  //items ----------------------
	  	$vkdbarang = //$this->form_fmST==1?	$fmIDBARANG.' - '.$dt['nm_barang'] :
			cariInfo("$this->Prefix.'_formsimpan'","pages/01/caribarang1.php","pages/01/caribarang2a.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled");
	  	$fkd_skpd=array( 
					'label'=>'Kode SKPD',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='ekd_skpd' id='ekd_skpd' value='".$dt['kd_skpd']."'>"
					 );
	  	$fkd_barang=array( 
					'label'=>'Kode Barang',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='ekode_barang' id='ekode_barang' value='".$dt['kode_barang']."'>"
					 );
		$fnama_barang=array( 
					'label'=>'Nama Barang',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='enama_barang' id='enama_barang' value='".$dt['nama_barang']."'>"
					 );			
	  	$fthn_perolehan=array( 
					'label'=>'Tahun Perolehan',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='ethn_perolehan' id='ethn_perolehan' value='".$dt['thn_perolehan']."' size='4' maxlength=4 onkeypress='return isNumberKey(event)'>"
					 );
	  	$fjml_barang=array( 
					'label'=>'Jumlah Barang / Satuan',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='ejml_barang' id='ejml_barang' value='".$dt['jml_barang']."'> ".
							"<input type='text' name='esatuan' id='esatuan' value='".$dt['satuan']."'>"
					 );
	  	$fjml_harga=array( 
					'label'=>'Jumlah Harga',
					'labelWidth'=>100, 
					'value'=>"<input type='text' name='ejml_harga' id='ejml_harga' value='".$dt['jml_harga']."'>"
					 );
	   $fstatus_barang=array( 
					'label'=>'Status Barang',
					'labelWidth'=>100, 
					'value'=> cmb2D('estatus_barang',$dt['status_barang'],$Main->StatusBarang,''),
					 );
	   $fasal_usul=array( 
					'label'=>'Asal Usul',
					'labelWidth'=>100, 
					'value'=> cmb2D('easal_usul',$dt['asal_usul'],$Main->AsalUsul,''),
					 );
		$fketerangan=array(
					'label'=>'Keterangan',
					'value'=>  "<textarea name='eket' id='eket' cols='60'>{$dt['ket']}</textarea>",
					'row_params' => "valign='top'"			
					);
		$fluas=array(
					'label'=>'Luas',
					'value'=> "<input type='text' name='eluas' id='eluas' value='".$dt['luas']."'>"			
					);
		$falamat=array(
					'label'=>'Alamat',
					'value'=>  "<input type='text' name='ealamat' id='ealamat' value='".$dt['alamat']."'>"		
					);
		$fsertifikat_tgl=array(
					'label'=>'Tanggal Sertifikat',
					'value'=> createEntryTgl( 'esertifikat_tgl', $dt['sertifikat_tgl'], false,'','',$this->FormName),			
					);
		$fsertifikat_no=array(
					'label'=>'Nomor Sertifikat',
					'value'=>  "<input type='text' name='esertifikat_no' id='esertifikat_no' value='".$dt['sertifikat_no']."'>"		
					);
		$fpenggunaan=array(
					'label'=>'Penggunaan',
					'value'=>  "<input type='text' name='epenggunaan' id='epenggunaan' value='".$dt['penggunaan']."'>"		
					);
		$fkota=array(
					'label'=>'Kota',
					'value'=>  "<input type='text' name='ekota' id='ekota' value='".$dt['kota']."'>"		
					);
		$fstatus_hak=array( 
					'label'=>'Status Hak Pakai',
					'labelWidth'=>100, 
					'value'=> cmb2D('estatus_hak',$dt['status_hak'],$Main->StatusHakPakai,''),
					 );
		$ftgl_buku=array(
					'label'=>'Tanggal Buku',
					'value'=> createEntryTgl( 'etgl_buku', $dt['tgl_buku'], false,'','',$this->FormName),			
					);
		$fmerk=array(
					'label'=>'Merk',
					'value'=>  "<input type='text' name='emerk' id='emerk' value='".$dt['merk']."'>"		
					);
		$fukuran=array(
					'label'=>'Ukuran',
					'value'=>  "<input type='text' name='eukuran' id='eukuran' value='".$dt['ukuran']."'>"		
					);
		$fno_rangka=array(
					'label'=>'Nomor Rangka',
					'value'=>  "<input type='text' name='eno_rangka' id='eno_rangka' value='".$dt['no_rangka']."'>"		
					);
		$fno_mesin=array(
					'label'=>'Nomor Mesin',
					'value'=>  "<input type='text' name='eno_mesin' id='eno_mesin' value='".$dt['no_mesin']."'>"		
					);
		$fno_polisi=array(
					'label'=>'Nomor Polisi',
					'value'=>  "<input type='text' name='eno_polisi' id='eno_polisi' value='".$dt['no_polisi']."'>"		
					);
		$fno_bpkb=array(
					'label'=>'Nomor BPKB',
					'value'=>  "<input type='text' name='eno_bpkb' id='eno_bpkb' value='".$dt['no_bpkb']."'>"		
					);
		$fbahan=array(
					'label'=>'Bahan',
					'value'=>  "<input type='text' name='ebahan' id='ebahan' value='".$dt['bahan']."'>"		
					);
		$fkondisi=array(
					'label'=>'Kondisi',
					'value'=> cmb2D('ekondisi',$dt['kondisi'],$Main->KondisiBarang,''),		
					);
		$fkondisi_bangunan=array(
					'label'=>'Kondisi Bangunan',
					'value'=> cmb2D('ekondisi_bangunan',$dt['kondisi_bangunan'],$Main->kondisi_bangunan,''),		
					);
		$fkonstruksi_tingkat=array(
					'label'=>'Konstruksi Tingkat',
					'value'=> cmb2D('ekonstruksi_tingkat',$dt['konstruksi_tingkat'],$Main->Tingkat,''),		
					);
		$fkonstruksi_beton=array(
					'label'=>'Konstruksi Beton',
					'value'=> cmb2D('ekonstruksi_beton',$dt['konstruksi_beton'],$Main->Beton,''),		
					);
		$fluas_lantai=array(
					'label'=>'Luas',
					'value'=> "<input type='text' name='eluas_lantai' id='eluas_lantai' value='".$dt['luas_lantai']."'>"			
					);
		$fstatus_tanah=array(
					'label'=>'Status Tanah',
					'value'=> cmb2D('estatus_tanah',$dt['status_tanah'],$Main->StatusTanah,''),		
					);
		$fkonstruksi=array(
					'label'=>'Konstruksi',
					'value'=> "<input type='text' name='ekonstruksi' id='ekonstruksi' value='".$dt['konstruksi']."'>"		
					);
		$fpanjang=array(
					'label'=>'Panjang',
					'value'=> "<input type='text' name='epanjang' id='epanjang' value='".$dt['panjang']."'>"		
					);
		$flebar=array(
					'label'=>'Lebar',
					'value'=> "<input type='text' name='elebar' id='elebar' value='".$dt['lebar']."'>"		
					);
		$fkode_tanah=array(
					'label'=>'Kode Tanah',
					'value'=> "<input type='text' name='ekode_tanah' id='ekode_tanah' value='".$dt['kode_tanah']."'>"		
					);
		$fbuku_judul=array(
					'label'=>'Judul Buku',
					'value'=> "<input type='text' name='ebuku_judul' id='ebuku_judul' value='".$dt['buku_judul']."'>"		
					);			
		$fbuku_spesifikasi=array(
					'label'=>'Spesifikasi Buku',
					'value'=> "<input type='text' name='ebuku_spesifikasi' id='ebuku_spesifikasi' value='".$dt['buku_spesifikasi']."'>"		
					);
		$fseni_asal_daerah=array(
					'label'=>'Asal Daerah',
					'value'=> "<input type='text' name='eseni_asal_daerah' id='eseni_asal_daerah' value='".$dt['seni_asal_daerah']."'>"		
					);
		$fseni_pencipta=array(
					'label'=>'Pencipta',
					'value'=> "<input type='text' name='eseni_pencipta' id='eseni_pencipta' value='".$dt['seni_pencipta']."'>"		
					);
		$fseni_bahan=array(
					'label'=>'Bahan',
					'value'=> "<input type='text' name='eseni_bahan' id='eseni_bahan' value='".$dt['seni_bahan']."'>"		
					);
		$fhewan_jenis=array(
					'label'=>'Jenis Hewan',
					'value'=> "<input type='text' name='ehewan_jenis' id='ehewan_jenis' value='".$dt['hewan_jenis']."'>"		
					);
		$fhewan_ukuran=array(
					'label'=>'Ukuran Hewan',
					'value'=> "<input type='text' name='ehewan_ukuran' id='ehewan_ukuran' value='".$dt['hewan_ukuran']."'>"		
					);
		$fbangunan=array(
					'label'=>'Bangunan',
					'value'=> cmb2D('ebangunan',$dt['bangunan'],$Main->Bangunan,''),		
					);
		$furaian=array(
					'label'=>'Uraian',
					'value'=> "<input type='text' name='euraian' id='euraian' value='".$dt['uraian']."'>"		
					);
		$fsoftware_nama=array(
					'label'=>'Nama Software',
					'value'=> "<input type='text' name='esoftware_nama' id='esoftware_nama' value='".$dt['software_nama']."'>"		
					);
		$fkajian_nama=array(
					'label'=>'Nama Kajian',
					'value'=> "<input type='text' name='ekajian_nama' id='ekajian_nama' value='".$dt['kajian_nama']."'>"		
					);
		$fpencipta=array(
					'label'=>'Pencipta',
					'value'=> "<input type='text' name='epencipta' id='epencipta' value='".$dt['pencipta']."'>"		
					);
		$fkerjasama_nama=array(
					'label'=>'Nama Kajian',
					'value'=> "<input type='text' name='ekerjasama_nama' id='ekerjasama_nama' value='".$dt['kerjasama_nama']."'>"		
					);			
		switch($lockedit){
			case '01':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$fluas,$falamat,$fsertifikat_tgl,$fsertifikat_no,$fpenggunaan,$fkota,$fstatus_hak,$fketerangan);break;
			
			case '02':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fmerk,$fjml_barang,$fukuran,$fno_rangka,$fno_mesin,$fno_polisi
							,$fno_bpkb,$fasal_usul,$fjml_harga,$fstatus_barang,$fbahan,$fkondisi,$fketerangan);break;
			
			case '03':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$fkondisi,$fkondisi_bangunan,$fkonstruksi_tingkat,$fkonstruksi_beton,$fluas_lantai,$falamat,$fluas,$fstatus_tanah,$fketerangan);break;
			
			case '04':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$fkonstruksi,$fpanjang,$flebar,$fluas,$falamat,$fkota,$fstatus_tanah,$fkode_tanah,$fkondisi,$fketerangan);break;
			
			case '05':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$fbuku_judul,$fbuku_spesifikasi,$fseni_asal_daerah,$fseni_pencipta,$fseni_bahan,$fhewan_jenis,$fhewan_ukuran,$fkondisi,$fketerangan);break;
			
			case '06':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$fbangunan,$fkonstruksi_tingkat,$fkonstruksi_beton,$fluas,$falamat,$fkota,$fketerangan);break;
							
			case '07':$this->form_fields = array($fkd_skpd,$fkd_barang,$fnama_barang,$fthn_perolehan,$ftgl_buku,$fjml_barang,$fjml_harga,$fstatus_barang,$fasal_usul
							,$furaian,$fsoftware_nama,$fkajian_nama,$fpencipta,$fkerjasama_nama,$fkondisi,$fketerangan);break;
		
		}
	 	
	  //Tombol
	  $this->form_menubawah =	
			"<input type='hidden' id='editf' name='editf' value='$lockedit' >".
			"<input type='button' value='Simpan' onclick='".$this->Prefix.".simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal kunjungan'>";
	   
	 	$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
   }
   
	function setForm(){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 120;
	 $this->form_caption = 'Upload';
			
				
 	    
		//query golongan
		
       //items ----------------------
		  $this->form_fields = array(									 
				'isiupload' => array( 
								'label'=>'Pilih File',
								'labelWidth'=>100, 
								'value'=>"<input type='file' name='isiupload' id='isiupload'>"
									 ),
		);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='upload' onclick='".$this->Prefix.".processuploadfile()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal kunjungan'>";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function genForm(){	
		$form_name = $this->Prefix.'_form';	
		$form = 
			centerPage(
				"<form name='$form_name' id='$form_name' method='POST' enctype='multipart/form-data'>".
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
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height).
					
				"</form>"
			);
		return $form;
	}	
		

		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	 "<thead>
	 <tr>
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox		
   	   <th class='th01' width='100'>skpd</th>
	   <th class='th01'width='100'>Kode Barang</th>
	   <th class='th01'>Tahun</th>
	   <th class='th01'>Harga</th>
	   <th class='th01'>Kondisi</th>
	   <th class='th01'>Asal Usul</th>
	   <th class='th01'>Jumlah Barang</th>
	   <th class='th01'>Tanggal Buku</th>
	   <th class='th01'>Status Cek</th>
	   <th class='th01'>Status Migrasi</th>
	   <th class='th01'>Keterangan</th>				   	   	   
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
		
	}
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;

	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $kode_skpd=$isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$isi['e1'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 	 $Koloms[] = array('align="left" ',$kode_skpd); 
		 $Koloms[] = array('align="left" ',$kode_barang);
		 $Koloms[] = array('align="center" "',$isi['thn_perolehan']);
		 $Koloms[] = array('align="center" "',$isi['jml_harga']);
		 $Koloms[] = array('align="center" "',$Main->KondisiBarang[$isi['kondisi']-1][1]);
		 $Koloms[] = array('align="center" "',$Main->AsalUsul[$isi['asal_usul']-1][1]);
		 $Koloms[] = array('align="center" "',$isi['jml_barang']);
		 $Koloms[] = array('align="center" "',$isi['tgl_buku']);
		 $Koloms[] = array('align="center" "',$Main->StatusCek[$isi['stcheck']][1]);
		 $Koloms[] = array('align="center" "',$Main->StatusMigrasi[$isi['stmigrasi']][1]);
		 $Koloms[] = array('align="center" "',$isi['ket_cek']."/".$isi['ket_migrasi']); 	 	 	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		//get pilih bidang
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		//get selectbox cari data : KIB
		$fmPILKIB=$_REQUEST['fmPILKIB'];
		$thn_perolehan=$_REQUEST['thn_perolehan'];
		$fmSTASET=cekPOST('fmSTASET');
		$fmSTCEK=cekPOST('fmSTCEK');
		$fmSTMIGRASI=cekPOST('fmSTMIGRASI');
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//data KIB ----------------------------
		$kibselect='';
		$kibaselect='';
		$kibbselect='';
		$kibcselect='';
		$kibdselect='';
		$kibeselect='';
		$kibfselect='';
		$kibgselect='';
		
		switch($fmPILKIB){
			case'00':$kibselect ="selected='selected'";break;
	    	case'01':$kibaselect ="selected='selected'";break;
			case'02':$kibbselect ="selected='selected'";break;
			case'03':$kibcselect ="selected='selected'";break;
			case'04':$kibdselect ="selected='selected'";break;
			case'05':$kibeselect ="selected='selected'";break;
			case'06':$kibfselect ="selected='selected'";break;
			case'07':$kibgselect ="selected='selected'";break;
		}
		//data Status aset ----------------------------
 			$arrstaset=array(array('3','Intra komptabel'),
			array('9','Aset Lain-lain'),
			array('10','Ekstra Komptabel'),
				);
			$arrstaset1=array(
			array('8','KIB G'),
			);
			if($fmPILKIB=='07'){
				$cmbstaset=cmbArray('fmSTASET',$fmSTASET,$arrstaset1,'Cari Data','');
			}else{
				$cmbstaset=cmbArray('fmSTASET',$fmSTASET,$arrstaset,'Cari Data','');
			}	
			
		//data status migrasi
			$arrstmigrasi=array(array('0','Belum Migrasi'),
			array('1','Sudah Migrasi'),
			array('2','Migrasi ada Error'),
			);
		//data status cek
			$arrstcheck=array(array('0','Belum di cek'),
			array('1','Tidak ada error'),
			array('2','Ada error'),
			);
		//data order ------------------------------
		$arrOrder = array(
			array('1','Kode Barang'),
			array('2','Nama Barang'),	
			array('3','Tahun Anggaran'),		
		);
		
			
		$TampilOpt =
		"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>" . 
				WilSKPD_ajx3($this->Prefix.'Skpd')  
			."					
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			"<table> 
				<tr><td><div style='float:left;padding: 2 8 0 0;height:20;'> Tahun Perolehan 
				<input type='text' maxlength='4' size='4' value='$thn_perolehan' id='thn_perolehan' name='thn_perolehan'>
				&nbsp; KIB  <select onchange='".$this->Prefix.".refreshList(true)' id='fmPILKIB' name='fmPILKIB'>
				<option Value='00' $kibselect>Cari Data</option>
				<option Value='01' $kibaselect>KIB A</option>
				<option Value='02' $kibbselect>KIB B</option>
				<option Value='03' $kibcselect>KIB C</option>
				<option Value='04' $kibdselect>KIB D</option>
				<option Value='05' $kibeselect>KIB E</option>
				<option Value='06' $kibfselect>KIB F</option>
				<option Value='07' $kibgselect>KIB G</option></select>
				&nbsp Status Aset &nbsp;".
				$cmbstaset.
				"&nbsp; Status Cek &nbsp;".
				cmbArray('fmSTCEK',$fmSTCEK,$arrstcheck,'Cari Data','').
				"&nbsp; Status Migrasi &nbsp;".
				cmbArray('fmSTMIGRASI',$fmSTMIGRASI,$arrstmigrasi,'Cari Data','').
				"</div></td></tr>
				<tr><td><div style='float:left;padding: 2 8 0 0;height:20;'>Tanggal Buku &nbsp; &nbsp;</div>".
				createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','migrasiForm',1)."
				<td><input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
				</td></tr></table>";
					
		return array('TampilOpt'=>$TampilOpt);
	}		


	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILKIB = $_REQUEST['fmPILKIB'];
		$thn_perolehan=$_REQUEST['thn_perolehan'];
		$fmSTASET=cekPOST('fmSTASET');
		$fmSTCEK=cekPOST('fmSTCEK');
		$fmSTMIGRASI=cekPOST('fmSTMIGRASI');	
		$fmGOLONGAN = $_REQUEST['fmGOLONGAN'];
		$fmSUBGOLONGAN = $_REQUEST['fmSUBGOLONGAN'];
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		switch($fmSTASET){
			case '3': $arrKondisi[] = " staset like '%$fmSTASET%'"; break;		 	
			case '8': $arrKondisi[] = " staset like '%".$fmSTASET."%'"; break;
			case '9': $arrKondisi[] = " staset like '%".$fmSTASET."%'"; break;
			case '10': $arrKondisi[] = " staset like '%".$fmSTASET."%'"; break;				 	
		}
		
		switch($fmSTMIGRASI){
			case '0': $arrKondisi[] = " stmigrasi like '%$fmSTMIGRASI%'"; break;		 	
			case '1': $arrKondisi[] = " stmigrasi like '%".$fmSTMIGRASI."%'"; break;
			case '2': $arrKondisi[] = " stmigrasi like '%".$fmSTMIGRASI."%'"; break;
		}
		
		switch($fmSTCEK){
			case '0': $arrKondisi[] = " stcheck like '%$fmSTCEK%'"; break;		 	
			case '1': $arrKondisi[] = " stcheck like '%".$fmSTCEK."%'"; break;
			case '2': $arrKondisi[] = " stcheck like '%".$fmSTCEK."%'"; break;
		}
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_buku>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " cast(tgl_buku as DATE)<='$fmFiltTglBtw_tgl2'";
		if(!empty($thn_perolehan)) $arrKondisi[]= " thn_perolehan like '%$thn_perolehan%'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		
		//Order -------------------------------------
		/*$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}

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
		
}
$migrasi = new migrasiObj();

?>