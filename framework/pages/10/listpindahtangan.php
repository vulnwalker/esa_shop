<?php
include('common/daftarobj.php');
include('common/fnpindahtangan.php');

/*$HalBI = cekPOST("HalBI",1);
$HalHPS = cekPOST("HalHPS",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalHPS = " limit ".(($HalHPS*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidHPS = cekPOST("cidHPS");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPENGHAPUSAN = cekPOST("fmTANGGALPENGHAPUSAN");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");
$fmIsMutasi = cekPOST("fmIsMutasi");//echo '<br>fmismutasi = '.$fmIsMutasi;
$fmKondisi = cekPOST("fmKondisi"); //echo "<br>fmKondisi=".$fmKondisi;
$fmNoSK = cekPOST("fmNoSK"); //echo "<br>fmNoSK=".$fmNoSK;
$fmTglSK= cekPOST("fmTglSK"); //echo "<br>fmTglSK=".$fmTglSK;
$fmGambar = cekPOST("fmGambar"); //echo "<br>fmGambar=".$fmGambar;
$fmGambar_old = cekPOST("fmGambar_old"); //echo "<br>fmGambar_old=".$fmGambar_old;
$fmApraisal = cekPOST("fmApraisal");
$fmUID = $HTTP_COOKIE_VARS['coID'];

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$fmBARANGCARI = $_POST['fmBARANGCARI'];
$fmBARANGCARIHPS = $_POST['fmBARANGCARIHPS'];


$SSPg = $_GET['SSPg'];
$Act = cekPOST("Act");
$Penghapusan_Baru = cekPOST("Penghapusan_Baru","1");
$Info = "";

//$PageMode = cekPOST('PageMode',1);
$ViewList = cekPOST('ViewList',1);
$ViewEntry = cekPOST('ViewEntry',0);
$fmFiltThnHapus = cekPOST('fmFiltThnHapus');


if(($Act=="Penghapusan_TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Penghapusan_Edit" || $Act=="Penghapusan_TambahEdit")  &&  count($cidHPS) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Penghapusan_Edit" || $Act=="Penghapusan_TambahEdit")  && count($cidHPS) == 1 ? " disabled ":"";


//PENGHAPUSAN PROSES -------------------------------------------
Penghapusan_Proses(); //hanya utk penghapusan hapus
*/

//echo $_POST['fmFiltThnBuku'];
//$genfilter =  $PindahTangan->genDaftarOpsi();
//$ListFilter = $genfilter['TampilOpt'];
//list -------------------------------------------------------------
//$getopsi = $PindahTangan->getDaftarOpsi(1);
//$ListData = $PindahTangan->genDaftar($getopsi['Kondisi'], $getopsi['Order'], $getopsi['Limit'], $getopsi['NoAwal'], 1 );//Penghapusan_daftar();



global $HTTP_COOKIE_VARS;
$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
$tgl_buku =	$thn_login.'-00-00';


$ErrMsg = ''; $content = '';
if ($_GET['idprs'] != ''){
	$idprs = $_GET['idprs']; //echo ',idprs='.$idprs;
}else{ //json
	$optstring = stripslashes($_GET['opt']);
	$Opt = json_decode($optstring); //$page = json_encode(",cek="+$Opt->idprs);
	$idprs = $Opt->daftarProses[$Opt->idprs];
}

switch ($idprs){
	case 'list':{
		
		$Opsi = $PindahTangan->getDaftarOpsi();	//$content = 'tes'.
		$content=	
			array('list'=>
				$PindahTangan->genDaftar( 
					$Opsi['Kondisi'], $Opsi['Order'], 
					$Opsi['Limit'], $Opsi['NoAwal'], 1 
				),
				'cek' => $Opsi['cek']
			);
		$json= true;								
		
		break;			
	} 		
	case 'sumhal': {		
		$Opsi = $PindahTangan->getDaftarOpsi();		
		$content = $PindahTangan->genSumHal($Opsi['Kondisi']);
		$json= true;							
		
		break;
	}
	case 'cetak_hal': {
		//echo $this->setCetak(2);
		//$this->genCetak(2,$Other,'30cm','DAFTAR NOTA HITUNG');
		$PindahTangan->set_ajxproses_cetak_hal();
		break;
	}
	case 'cetak_all':{
		$PindahTangan->set_ajxproses_cetak_all();					
		//echo $this->setCetak(3);
		//$this->genCetak(3,$Other,'30cm','DAFTAR NOTA HITUNG');
		break;
	}
	case 'formbaru':{		
		$cbid= $_POST['cidBI'];
		//$sqry ="select * from  id ='".$cbid[0]."'"; //$ErrMsg = $sqry;
		//$get = mysql_fetch_array(mysql_query($sqry));
		$PindahTangan->form_width = 720;
		$PindahTangan->form_height = 360;
		$PindahTangan->form_fields = array(
			'tgl_pemindahtanganan' => array( 'label'=>'Tanggal Buku Pemindahtanganan', 'value'=>createEntryTgl('tgl_pemindahtanganan', $tgl_buku, false, '','','FormPemindahtanganan','2'), 'type'=>'','labelWidth'=>200 ),
			'bentuk_pemindahtangan' => array( 
				'label'=>'Bentuk Pemindahtanganan', 				
				'value'=> cmb2D('bentuk_pemindahtanganan','',$Main->BentukPemindahtanganan,''),
				'type'=>'' 
			),

			'surat' => array( 'label'=>'Surat Keputusan', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
			'nosk' => array( 'label'=>'&nbsp;&nbsp;Nomor', 'value'=>'', 'type'=>'text', 'param'=>'size=59' , 'valign'=>'middle'),						
			'tglsk' => array( 'label'=>'&nbsp;&nbsp;Tanggal SK', 'value'=>'', 'type'=>'date','labelWidth'=>200 ),
			
			'kepada' => array( 'label'=>'Kepada', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
			'kepada_nama' => array( 'label'=>'&nbsp;&nbsp;Nama', 'value'=>'', 'type'=>'text', 'param'=>'size=59' , 'valign'=>'middle'),						
			'kepada_alamat' => array( 'label'=>'&nbsp;&nbsp;Alamat', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
			'uraian' => array( 'label'=>'Uraian', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
			'ket' => array( 'label'=>'Keterangan', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
		);		
		$PindahTangan->form_caption = 'Pemindahtanganan - Baru';				
		$PindahTangan->form_idplh = '';
		$PindahTangan->form_fmST = '';
		$PindahTangan->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$PindahTangan->Prefix.".SimpanBaru()' >&nbsp;".
			"<input type='button' value='Batal' onclick ='".$PindahTangan->Prefix.".Close()' >".
			"<input type=hidden value='".$cbid[0]."' name='idbi' id='idbi'>";
		$content= $PindahTangan->genForm();
		//echo 'tes';
		$json=TRUE;	
		break;		
	}
	
	case 'formedit':{
		$cbid= $_POST[$PindahTangan->Prefix.'_cb'];						
		$sqry ="select * from pemindahtanganan where id ='".$cbid[0]."'"; //$ErrMsg = $sqry;
		$get = mysql_fetch_array(mysql_query($sqry));
		$PindahTangan->form_width = 720;
		$PindahTangan->form_height = 360;
		$PindahTangan->form_fields = array(
			'tgl_pemindahtanganan' => array( 'label'=>'Tanggal Buku Pemindahtanganan', 'value'=>createEntryTgl('tgl_pemindahtanganan', $get['tgl_pemindahtanganan'], false, '','','FormPemindahtanganan','2'), 'type'=>'','labelWidth'=>200 ),
			'bentuk_pemindahtangan' => array( 
				'label'=>'Bentuk Pemindahtanganan', 
				'value'=>$Main->BentukPemindahtanganan[$get['bentuk_pemindahtanganan']-1][1]."<input type='hidden' name='bentuk_pemindahtanganan' id='bentuk_pemindahtanganan' value='".$get['bentuk_pemindahtanganan']."'>", 
				//'value'=> cmb2D('bentuk_pemindahtanganan',$get['bentuk_pemindahtanganan'],$Main->BentukPemindahtanganan,''),
				'type'=>'' 
			),
			'surat' => array( 'label'=>'Surat Keputusan', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
			'nosk' => array( 'label'=>'&nbsp;&nbsp;Nomor', 'value'=>$get['nosk'], 'type'=>'text', 'param'=>'size=59' , 'valign'=>'middle'),						
			'tglsk' => array( 'label'=>'&nbsp;&nbsp;Tgl. SK', 'value'=>$get['tglsk'], 'type'=>'date','labelWidth'=>200 ),
			
			'kepada' => array( 'label'=>'Kepada', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
			'kepada_nama' => array( 'label'=>'&nbsp;&nbsp;Nama', 'value'=>$get['kepada_nama'], 'type'=>'text', 'param'=>'size=59' , 'valign'=>'middle' ),						
			'kepada_alamat' => array( 'label'=>'&nbsp;&nbsp;Alamat', 'value'=>$get['kepada_alamat'], 'type'=>'memo','param'=>'cols=60' ),
			'uraian' => array( 'label'=>'Uraian', 'value'=>$get['uraian'], 'type'=>'memo','param'=>'cols=60' ),
			'ket' => array( 'label'=>'Keterangan', 'value'=>$get['ket'], 'type'=>'memo','param'=>'cols=60' ),
		);		
		$PindahTangan->form_caption = 'Pemindahtanganan - Edit';				
		$PindahTangan->form_idplh = $cbid[0];
		$PindahTangan->form_fmST = 1;
		$PindahTangan->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$PindahTangan->Prefix.".Simpan()' >&nbsp;".
			"<input type='button' value='Batal' onclick ='".$PindahTangan->Prefix.".Close()' >".
			"<input type=hidden value='".$get['id_bukuinduk']."' name='idbi' id='idbi'>";
		$content= $PindahTangan->genForm();
		//echo 'tes';
		$json=TRUE;	
		break;		
	}
	case 'simpan':{
		
		//get data lama ---------------------------
		//global $Main//$Pindahtangan;
		$UID =  $HTTP_COOKIE_VARS['coID'];
		$ID = $_POST[$PindahTangan->Prefix.'_idplh'];
		$old = mysql_fetch_array( mysql_query(
			"select * from pemindahtanganan where id = '$ID'"
		));				
		
		//validasi --------------------------------				
		if ($ErrMsg=='' && $_POST['Pindahtangan_fmST']=='1'){
		
		 $ErrMsg=Pindahtangan_CekdataCutoff('edit',$ID,$_POST['tgl_pemindahtanganan'],$idbi);	

		 }	
//		 $ErrMsg.=$ID.' - '.$_POST['tgl_pemindahtanganan'].' - '.$_POST['Pindahtangan_fmST'].' - '.$Pindahtangan->Prefix.'_fmST';
		if($ErrMsg =='' && cektanggal($_POST['tgl_pemindahtanganan'])==FALSE) $ErrMsg = "Tanggal Pemindahtanganan Salah!";	
		if($ErrMsg =='' && compareTanggal($_POST['tgl_pemindahtanganan'], date('Y-m-d'))==2  ) $ErrMsg = 'Tanggal Pemindahtanganan tidak lebih besar dari Hari ini!';				
		
		if ($ErrMsg==''){
			if ($_POST[$PindahTangan->Prefix.'_fmST']==0){//baru
				$idbi = $_POST['idbi'];
				$bi =  mysql_fetch_array( mysql_query(
					"select * from buku_induk where id = '$idbi'"
				));	
				// $Main->TAHUN_CUTOFF."-12-31"
				if($ErrMsg=='' && $bi['thn_perolehan']< $Main->TAHUN_CUTOFF) $ErrMsg = "Tahun Perolehan tidak lebih kecil dari $Main->TAHUN_CUTOFF!";
				if ($bi['status_barang'] == '3') $ErrMsg = 'Barang sudah di Penghapusan!';
				if ($bi['status_barang'] == '4') $ErrMsg = 'Barang sudah di Pemindah Tanganan!';
				if ($bi['status_barang'] == '5') $ErrMsg = 'Barang sudah di Tuntutan Ganti Rugi!';
				
				
			}else{//edit
				$old = mysql_fetch_array(mysql_query("select * from pemindahtanganan where id='{$_POST[$PindahTangan->Prefix.'_idplh']}'" ));
				$idbi = $old['id_bukuinduk'];
				if ($idbi == '') $ErrMsg = "Data dengan id '{$_POST[$PindahTangan->Prefix.'_idplh']}' tidak ada!";
			}			
			$pelihara = mysql_fetch_array( mysql_query ("select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk = '$idbi'"	));
			if ($ErrMsg =='' && (compareTanggal($_POST['tgl_pemindahtanganan'], $pelihara['maxtgl'])==0 || 	compareTanggal($_POST['tgl_pemindahtanganan'], $pelihara['maxtgl'])==1  )  ) 
				$ErrMsg = 'Tanggal Pemindahtangan harus lebih besar dari Tanggal Pemeliharaan!';
			$pengaman = mysql_fetch_array( mysql_query ("select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk = '$idbi'"	));
			if ($ErrMsg =='' && (compareTanggal($_POST['tgl_pemindahtanganan'], $pengaman['maxtgl'])==0 || compareTanggal($_POST['tgl_pemindahtanganan'], $pengaman['maxtgl'])==1 ) ) 
				$ErrMsg = 'Tanggal Pemindahtangan harus lebih besar dari Tanggal Pengamanan!';
			$pemanfaat = mysql_fetch_array( mysql_query ("select max(tgl_pemanfaatan) as maxtgl from pemanfaatan where id_bukuinduk = '$idbi'"	));
			if ($ErrMsg =='' && (compareTanggal($_POST['tgl_pemindahtanganan'], $pemanfaat['maxtgl'])==0 || compareTanggal($_POST['tgl_pemindahtanganan'], $pemanfaat['maxtgl'])==1 )  ) 
				$ErrMsg = 'Tanggal Pemindahtangan harus lebih besar dari Tanggal Pemanfaatan!';						
			$penatausaha = mysql_fetch_array( mysql_query ("select staset,tgl_buku from buku_induk where id = '$idbi'"	));
			if ($ErrMsg=='' &&  compareTanggal($_POST['tgl_pemindahtanganan'], $penatausaha['tgl_buku'] )==0 ) 
				$ErrMsg = 'Tanggal Pemindahtanganan tidak lebih kecil dari Tanggal Buku!'; 		
			$hps = mysql_fetch_array(mysql_query("select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idbi'" ));
			if ($ErrMsg=='' && compareTanggal($hps['maxtgl'] , $_POST['tgl_pemindahtanganan'] )==2  ) $ErrMsg = 'Tanggal Pemindahtanganan tidak lebih kecil dari Tanggal Penghapusan Sebagian!';
			
		}		 

		if ($ErrMsg=='' && $fmst==1){
		 $ErrMsg=Pindahtangan_CekdataCutoff('insert',$idplh,$_POST['tgl_pemindahtanganan'],$idbi);	
		 }			
		 //cek status		
		$bi = mysql_fetch_array( mysql_query (	" select * from buku_induk where id='{$old['id_bukuinduk']}' " ));
		if($ErrMsg=='' &&$_POST[$PindahTangan->Prefix.'_fmST']==1 && $bi['status_barang'] != 1 ) $errmsg= "Hanya Barang Inventaris yang bisa Pemanfaatan!";
		
		if($Main->VERSI_NAME <> 'SERANG'){
			$fmTANGGALpemindahtanganan = $_POST['tgl_pemindahtanganan'];
			$thn_pemindahtanganan = substr($fmTANGGALpemindahtanganan,0,4);
			$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$idbi' and tahun>='$thn_pemindahtanganan'";
			$get_susut = mysql_fetch_array(mysql_query($query_susut));
			$get_cd = mysql_fetch_array(mysql_query("select c,d from buku_induk where id='$idbi'"));
					
			if ($_POST[$PindahTangan->Prefix.'_fmST']==0){
				//cek sudah ada penyusutan / tdk untuk data baru			
				//if($ErrMsg=='' && $get_susut['jml_susut']>0)$ErrMsg='Sudah ada penyusutan, data tidak bisa di masukan !';
				//cek sudah ada Closing untuk data baru
				if($ErrMsg=='' && sudahClosing($fmTANGGALpemindahtanganan,$get_cd['c'],$get_cd['d']))$ErrMsg = 'Tanggal sudah Closing !';
			}else{
				//cek sudah ada penyusutan / tdk untuk data edit	
				$old_pemindahtanganan = mysql_fetch_array(mysql_query("select * from pemindahtanganan where id='$ID'"));
				$oldthn_pemindahtanganan = substr($old_pemindahtanganan['tgl_pemindahtanganan'],0,4);
				/*if($ErrMsg=='' && $get_susut['jml_susut']>0){
					if($ErrMsg=='' && $oldthn_pemindahtanganan!=$thn_pemindahtanganan)$ErrMsg='Sudah ada penyusutan, data tidak bisa dirubah !';
				}*/
				//cek sudah ada Closing untuk data edit
				$get_cd = mysql_fetch_array(mysql_query("select c,d from buku_induk where id='$idbi'"));
				if($ErrMsg=='' && sudahClosing($fmTANGGALpemindahtanganan,$get_cd['c'],$get_cd['d'])){
					if($ErrMsg=='' && $oldthn_pemindahtanganan!=$thn_pemindahtanganan)$ErrMsg = 'Tanggal sudah Closing !';
				}
			}
		}	
		
		//simpan ----------------------------------
		if ($ErrMsg == ''){
			if ($_POST[$PindahTangan->Prefix.'_fmST']==0)
			{
				$fieldstaset=",staset";
				$nilaistaset=",".$penatausaha['staset'];
			} else {
				$fieldstaset="";
				$nilaistaset="";
				
			}
			
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id = '$idbi'"));
			$idbi_awal = $bi['idawal'];
			$nilai_buku = getNilaiBuku($idbi,$_POST['tgl_pemindahtanganan'],0);
			$nilai_susut = getAkumPenyusutan($idbi,$_POST['tgl_pemindahtanganan']);
						
			$get = $PindahTangan->simpan(
				$_POST[$PindahTangan->Prefix.'_fmST'], 'pemindahtanganan', 
				'id', $_POST[$PindahTangan->Prefix.'_idplh'], 
				'id_bukuinduk,tgl_update,uid,tgl_pemindahtanganan,bentuk_pemindahtanganan,nosk,tglsk,kepada_nama,kepada_alamat,uraian,ket,nilai_susut,nilai_buku,idbi_awal'.$fieldstaset,
				$_POST['idbi'].',now(),'.$UID.','.$_POST['tgl_pemindahtanganan'].','.
				$_POST['bentuk_pemindahtanganan'].','.$_POST['nosk'].','.$_POST['tglsk'].','.$_POST['kepada_nama'].','.
				$_POST['kepada_alamat'].','.$_POST['uraian'].','.$_POST['ket'].','.$nilai_susut.','.$nilai_buku.','.$idbi_awal.$nilaistaset  
			);					
			$newid = mysql_insert_id();
			$cek .= 'new id= '.$newid;
			$ErrMsg = $get['err'];
			/*if($ErrMsg ==''){
				if($_POST[$PindahTangan->Prefix.'_fmST']==0) {//baru
				
					//update buku induk set status_barang
					$Simpan = mysql_query("update buku_induk set status_barang = 4,staset='5' where id = '".$_POST['idbi']."'");
					if ($Simpan) {
						//if($_POST[$PindahTangan->Prefix.'_fmST'] == 1){
							if($Main->MODUL_JURNAL) $ins=jurnalPemindahtangan($newid,$UID,1);	
							$cek .= ' insjurnal='.$ins['cek'];
						//}else{
						//	if($Main->MODUL_JURNAL) jurnalPemanfaatan($_POST[$PindahTangan->Prefix.'_idplh'],$UID,2);
						//}
						
						
					}else{
						$ErrMsg = "Gagal update buku induk!";
					}
					
				}else{//edit
					if($Main->MODUL_JURNAL) $ins=jurnalPemindahtangan($ID,$UID,2);	
				}
			}*/
			if ($ErrMsg=='') $content = $get['content'];
		}
		$json=TRUE;	
		break;				
	}	
	case 'hapus':{
		$UID =  $HTTP_COOKIE_VARS['coID'];
		$cbid= $_POST[$PindahTangan->Prefix.'_cb'];	
				for($i = 0; $i<count($cbid); $i++)	{
			$err = $PindahTangan->Hapus_Validasi($cbid[$i]);
			if($err =='')
			{
		 $err=Pindahtangan_CekdataCutoff('hapus',$cbid[$i],$_POST['tgl_pemindahtanganan'],$idbi);	
				
			}
			if($Main->VERSI_NAME <> 'SERANG'){
				//ambil id buku induk
				$pemindahtanganan = mysql_fetch_array(mysql_query(
					"select idbi_awal, tgl_pemindahtanganan, id_bukuinduk from pemindahtanganan where id='".$cbid[$i]."'"
				));
			 	//cek sudah ada penyusutan / tdk
				$fmTANGGALpemindahtanganan = $pemindahtanganan['tgl_pemindahtanganan'];
				$idbi = $pemindahtanganan['id_bukuinduk'];
				$thn_pemindahtanganan = substr($fmTANGGALpemindahtanganan,0,4);
				$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$idbi' and tahun>='$thn_pemindahtanganan'";
				$get_susut = mysql_fetch_array(mysql_query($query_susut));	
				/*if($get_susut['jml_susut']>0){
					$err="Id ".$cbid[$i].", Sudah ada penyusutan !";
				}*/
				//cek sudah ada Closing
				$get_cd = mysql_fetch_array(mysql_query("select c,d from buku_induk where id='$idbi'"));
				if(sudahClosing($fmTANGGALpemindahtanganan,$get_cd['c'],$get_cd['d'])){
					$err = "Id ".$cbid[$i].", Tanggal Sudah Closing !";
				}
			}
				
			if($err ==''){
				$get = $PindahTangan->Hapus_Data($cbid[$i]);
				$err = $get['err'];
				$cek.= $get['cek'];
				if ($errmsg=='') {
					$after = $PindahTangan->Hapus_Data_After($cbid[$i]);					
					$err=$after['err'];
					$cek=$after['cek'];
					if($Main->MODUL_JURNAL) $ins=jurnalPemindahtangan($cbid[$i],$UID,3);
				}
				if ($err != '') break;
				 				
			}else{
				break;
			}
						
		}
		$ErrMsg=$err;
/*		 $ErrMsg=Pindahtangan_CekdataCutoff('hapus',$cbid,$_POST['tgl_pemindahtanganan'],$idbi);	
		 $ErrMsg.=' '.$cbid;
		if ($ErrMsg==''){
		$ErrMsg=//'hapus '.$cbid;
			$PindahTangan->Hapus($cbid);
		}
*/		
		$json=TRUE;	
		break;
	}		
	default:{ //kerangka hal		
		$fmWIL = cekPOST("fmWIL");
		$fmSKPD = cekPOST("fmSKPD");
		$fmUNIT = cekPOST("fmUNIT");
		$fmSUBUNIT = cekPOST("fmSUBUNIT");
		$fmSEKSI = cekPOST("fmSEKSI");
		$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
		//setWilSKPD();
		//toolbar atas ------------------------------------------------------	
		/*
		$script ="
			
			<script>
				function Penghapusan_Edit(){
					var errmsg = '';			
					if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';	}
					if((errmsg=='') && (adminForm.boxchecked.value ==0 )){	errmsg= 'Data belum di pilih!';	}				
					if (errmsg ==''){					
						adminForm.action='?Pg=09&SPg=03';					
						adminForm.Act.value='Penghapusan_Edit';
						adminForm.target='_blank';
						adminForm.submit();
						adminForm.target='';
					}else{
						alert(errmsg);
					}
				}
				function Penghapusan_Hapus(){
					var errmsg = '';			
					if((errmsg=='') && (adminForm.boxchecked.value ==0 )){	errmsg= 'Data belum di pilih!';	}
					if (errmsg ==''){
						if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){
							adminForm.Act.value='Hapus';
							adminForm.action='';
							adminForm.target='';
							adminForm.submit();
						}
					}else{
						alert(errmsg);
					}
				}
				function Penghapusan_CetakAll(){
					adminForm.action='?Pg=PR&SPg=penghapusan_cetak&SSPg=$SSPg&ctk=$jmlDataHPS';
					adminForm.target='_blank';
					adminForm.submit();
					adminForm.target='';		
				}
			</script>
			";*/
			if (empty($disModul09) && empty($ridModul09)){
				$toolbar_atas_edit = 
					"<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>-->
					<td>".PanelIcon1("javascript:Pindahtangan.Edit()","edit_f2.png","Ubah")."</td>
					<td>".PanelIcon1("javascript:Pindahtangan.Hapus()","delete_f2.png","Hapus")."</td>";				
			}	
			$toolbar_atas = "<div style='float:right;'>					
					<table ><tr>
					$toolbar_atas_edit
					<td>".PanelIcon1("javascript:Pindahtangan.cetakAll()","print_f2.png","Cetak")."</td>					
					</tr></table>			
					</div>";
		//title -----------------------------------------------------------------------------
		$subpagetitle = 'Buku Inventaris';
		switch($_GET['SSPg']){
					case '03': break;
					case '04': $subpagetitle ='KIB A'; break;
					case '05': $subpagetitle ='KIB B'; break;
					case '06': $subpagetitle ='KIB C'; break;
					case '07': $subpagetitle ='KIB D'; break;
					case '08': $subpagetitle ='KIB E'; break;
					case '09': $subpagetitle ='KIB F'; break;	
					case '10': $subpagetitle ='ATB'; break;	
		}
		$subpagetitle = '<br>'.$subpagetitle;
		$Page_Title = "<table class=\"adminheading\">
					<tr>
		  			<th height=\"47\" class=\"user\" ><div style='padding:0 0 0 8;'>
						Daftar Pemindahtanganan Barang Milik Daerah $subpagetitle
					</div></th>
		  			<th>$toolbar_atas</th>
					</tr>
					</table>";
		//page --------------------------------------------------------------------------------
		$Page_Hidden = "
			<input type='hidden' name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
			<input type='hidden' name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
			<input type='hidden' name='fmWILSKPD' value='$fmWILSKPD'>
			<input type='hidden' name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
			<input type='hidden' name='fmSSPg' id='fmSSPg' value='".$_GET['SSPg']."'>
			<input type='hidden' name='Act'>
			<input type='hidden' name='Penghapusan_Baru' value='$Penghapusan_Baru'>	
			<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
			<input type=\"hidden\" name=\"option\" value=\"com_users\" />
			<input type=\"hidden\" name=\"task\" value=\"\" />
			<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
			<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
			<!--<input type=text name='PageMode' value='$PageMode' >-->
			<input type=hidden name='ViewList' value='$ViewList' >
			<input type=hidden name='ViewEntry' value='$ViewEntry' >
			";
		$cek ='';
		$Main->Isi .= 
			//$menubarpage.
			"
			<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg\">		
				$script 
				<table width=\"100%\" id='tbljustify_content' >	<tr><td width=\"60%\" valign=\"top\">".
					$Page_Title.			
					"<table class='adminform'><tr><td> ".WilSKPD1()."	</td></tr></table>".
					//$ListBarangHPS_Cari.
					//$ListFilter.
					/*<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1 >	
						$ListData
					</table>*/
					
					$PindahTangan->genDaftarInitial().		
				"</td></tr></table>
				$Page_Hidden	
			</form>".
			$Info.
			$cek;
			
		break;
	}//end default
}//end case


if($json){ //json---	
	$pageArr = array(
				'idprs'=>$Opt->idprs,						 
				'daftarProses'=>$Opt->daftarProses , 
				'ErrNo'=>$ErrNo, 
				'ErrMsg'=>$ErrMsg, 
				'content'=> $content,
				'cek'=>$cek 
			);
	$page = json_encode($pageArr);	
	echo $page;
}



?>