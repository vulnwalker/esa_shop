<?php
// susut mode -----------------------------------
//0 = dihitung bulanan berdasar bulan tgl_buku, pd bulan setelah nya, disimpan semester
//1 = dihitung bulanan berdasar bulan tgl_buku, pd bulan setelah nya, disimpan bulanan (jabar1)
//2 = dihitung tahunan, hrg semester = hrg tahun/2 , disimpan semester (serang)
//3 = jabar(2)
//		- sebelum 2014: dihitung tahunan, dihitung pd tahun nya berdasar tahun perolehan/TAHUN_MULAI_SUSUT, disimpan semester (harga susut tahun/2)
//		- sesudah 2014: dihitung bulanan pada bulannya berdasar bulan tgl_bast, disimpan semester (total harga bulanan) (jabar2)
//4 = Garut
//5 = Serang Kota
//6 = Tasik
//7 = Pandeglang
//8 = Cirebon
//9 = Bandung Barat
//10 = BOgor

class PenyusutanObj extends DaftarObj2{
	var $Prefix = 'Penyusutan'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'penyusutan'; //daftar
	var $TblName_Hapus = 'penyusutan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 10, 9,9);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 9; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = FALSE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='Penyusutan.xls';
	var $Cetak_Judul = 'Penyusutan';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penyusutan';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '1000';
	var $FormName = 'Penyusutan_form';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	//hitung
	var $akum_penyusutan=0;
	var $akum_masamanfaat = 0;
	var $akum_perolehan = 0;
	var $akum_rehab =0;
	
	function Hapus($ids){ //batal susut 1 data
		global $Main;
		$err=''; $cek='';
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		
		for($i = 0; $i<count($ids); $i++){
			//cek data penyusutan terakhir
			$old = mysql_fetch_array(mysql_query( "select year(tgl) tahun_buku, tgl, tahun, idbi, idbi_awal from penyusutan where id='".$ids[$i]."' " ));
			$maxthn = mysql_fetch_array(mysql_query( "select max(tahun) as maxthn from penyusutan where idbi_awal='".$old['idbi_awal']."'" ));
			$jmldt = mysql_fetch_array(mysql_query( "select count(*) as jmldt from penyusutan where idbi_awal='".$old['idbi_awal']."' and tahun ='".$old['tahun']."' and jns_trans2 != 30 " ));
			$bi=mysql_fetch_array(mysql_query("select * from buku_induk where id='".$old['idbi']."' "));
			if($err=='' && $old['tahun'] < $maxthn['maxthn']) $err = " Hanya data tahun penyusutan terakhir yang boleh dihapus!";			
			//cek tahun closing
			//if ($err=='' && $old['tahun_buku'] <= $Main->TAHUN_CLOSING ) 
			if ($err=='' && sudahClosing( $old['tgl'], $bi['c'], $bi['d'], $bi['e'], $bi['e1'] ) ) $err = "Data tidak bisa dihapus, sudah closing!";
		}
			//cek jns_trans2 bukan 30
			if ($err=='' && $jmldt['jmldt'] > 0 ) $err = "Data tidak bisa dihapus, karena sudah ada transaksi bukan penyusutan!";
		
		if($err==''){
			for($i = 0; $i<count($ids); $i++)	{
				$old2 = mysql_fetch_array(mysql_query( 
					"select year(tgl) as tahun_buku, tgl, tahun, idbi, idbi_awal from penyusutan where id='".$ids[$i]."' " 
				));
			
				//$err = $this->Hapus_Validasi($ids[$i]);			
				if($err ==''){
					$get = $this->Hapus_Data($ids[$i]);
					$err .= $get['err'];
					$cek .= $get['cek'];
					if ($err=='') {
						if($Main->SUSUT_MODE==2){//semester lainnya dalam tahun tersebut ikut dihapus
							$del = mysql_fetch_array(mysql_query(
								" delete from penyusutan where tahun ='".$old2['tahun']."' and idbi_awal = '".$old2['idbi_awal']."' "
							)); 
						}
						$aqry = "select max(tahun) as maxthn, min(tahun) as minthn, sum(masa_manfaat)  as totmanfaat  ".
							" from penyusutan where idbi='".$old2['idbi']."' " ;
						$mthn = mysql_fetch_array(mysql_query( $aqry ));
						$masa_manfaat = $mthn['totmanfaat'];
												
						$aqry = "select tahun, bulan from penyusutan where idbi='".$old2['idbi']."' order by tahun,bulan limit 0,1 ";
						$mthn = mysql_fetch_array(mysql_query( $aqry ));
						$minthn = $mthn['tahun']; $minbulan = $mthn['bulan'];
						
						$aqry = "select tahun, bulan from penyusutan where idbi='".$old2['idbi']."' order by tahun desc, bulan desc limit 0,1 ";
						$mthn = mysql_fetch_array(mysql_query( $aqry ));
						$maxthn = $mthn['tahun']; $maxbulan = $mthn['bulan'];
						
						$cek .= $aqry;
						$aqry = "update buku_induk set thn_susut_aw='$minthn', bln_susut_aw='$minbulan', ".
							" thn_susut_ak='$maxthn', bln_susut_ak='$maxbulan', ".
							" masa_manfaat = '$masa_manfaat' ".
							" where id = '".$old2['idbi']."' " ;
						mysql_query($aqry); $cek .= $aqry;
									
						$after = $this->Hapus_Data_After($ids[$i]);
						$err .= $after['err'];
						$cek .= $after['cek'];
					}
					if ($err != '') break;
					 				
				}else{
					break;
				}			
			}
		}
		
		return array('err'=>$err,'cek'=>$cek);
	} 
		
	function setTitle(){
		global $Main;
		//return 'Rekapitulasi Hasil Sensus Tahun '. getTahunSensus() ;
		//if($Main->MODUL_AKUNTASI){
			return 'Penyusutan';	
		//}else{
		//	return 'Pembukuan';	
		//}
		
		
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		return " <DIV ALIGN=CENTER>Penyusutan ";
	}
	
	/*function setMenuEdit(){		
		return '';
	}*/
	
	
	
	
	function batalSusut(){ //batal penyusutan per barang
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$UID = $HTTP_COOKIE_VARS['coID'];		
		$cidBI = $_REQUEST['cidBI'];
		//$thn = $Main->TAHUN_CLOSING==''? 0: $Main->TAHUN_CLOSING;
		
		foreach ($cidBI as $idbi){
			$aqry = "select * from buku_induk where id = '$idbi' ;"; $cek .= $aqry;
			$bi = mysql_fetch_array(mysql_query($aqry));
			
			/*if($Main->TAHUN_CLOSING=='' || $Main->TAHUN_CLOSING==0 ){
				$aqry = "delete from penyusutan where idbi='{$bi['id']}' "; $cek .= $aqry;				
			}else{
				$aqry = "delete from penyusutan where idbi='{$bi['id']}' and year(tgl) > $thn; "; $cek .= $aqry;	
			}*/	
			$aqrySusut="select * from penyusutan where idbi='{$bi['id']}' order by Id DESC";
			while($row=mysql_fetch_array(mysql_query($aqrySusut))){			
				//if($Main->TGL_CLOSING=='' || $Main->TGL_CLOSING==0 ){
				$tglclosing = getTglClosing($bi['c'],$bi['d'],$bi['e'],$bi['e1']);
				if($Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==4){
					$tglclosing='2015-12-31';
				}else{
					$tglclosin=$tglclosing;	
				}
				
				if($row['jns_trans2']!=30) {
					$aqry = "delete from t_koreksi_penyusutan where id='".$row['id_koreksi_tambah']."'"; $cek .= $aqry;	
					$qry = mysql_query($aqry);
					//$aqry = "update penyusutan set where Id='".$row['Id']."' and jns_trans2=30"; $cek .= $aqry;	
					$content->alert = "Data Sukses Dibatalkan sampai Bukan transaksi penyusutan";
					break;
				//}elseif($tglclosing!='' || $tglclosing!=0 ){
				}elseif($tglclosing >= $row['tgl']){
					//$aqry = "delete from penyusutan where Id='".$row['Id']."' and jns_trans2=30"; $cek .= $aqry;	
					$content->alert = "Data Sukses Dibatalkan sampai tanggal ".TglInd($tglclosing)."!";
					break;
				}else{
					$aqry = "delete from penyusutan where Id='".$row['Id']."'"; $cek .= $aqry;	
					$content->alert = "Data Sukses Dibatalkan sampai tanggal awal!";
				}
				$qry = mysql_query($aqry);
				
			}		
		
			$aqry = "select max(ifnull(tahun,0) ) as maxtahun from penyusutan where idbi='{$bi['id']}' ; "; $cek .= $aqry;
			$susut 	= mysql_fetch_array(mysql_query($aqry));
			
			if($susut['maxtahun']==0){
				$blnsusutak = 0;
			}else{
				$blnsusutak = 12;
			}
			
			if($qry){
				if($susut['maxtahun']==0){
					if($Main->VERSI_NAME=='BDG_BARAT'){
						$aqry = "update buku_induk set stop_susut=0, masa_manfaat=0, nilai_sisa=null, ".
						"thn_susut_aw = 0, bln_susut_aw = 0, thn_susut_ak='{$susut['maxtahun']}' , bln_susut_ak='$blnsusutak' ".
						"where id='{$bi['id']}' ; "; $cek .= $aqry;
						
					}else{
						$aqry = "update buku_induk set  masa_manfaat=0, nilai_sisa=null, ".
						"thn_susut_aw = 0, bln_susut_aw = 0, thn_susut_ak='{$susut['maxtahun']}' , bln_susut_ak='$blnsusutak' ".
						"where id='{$bi['id']}' ; "; $cek .= $aqry;	
					}
				}else{
						$aqry = "select tahun, bulan from penyusutan where idbi='".$bi['id']."' order by tahun,bulan limit 0,1 ";
						$mthn = mysql_fetch_array(mysql_query( $aqry ));
						$minthn = $mthn['tahun']; $minbulan = $mthn['bulan'];
						
						$aqry = "select tahun, bulan from penyusutan where idbi='".$bi['id']."' order by tahun desc, bulan desc limit 0,1 ";
						$mthn = mysql_fetch_array(mysql_query( $aqry ));
						$maxthn = $mthn['tahun']; $maxbulan = $mthn['bulan'];
						$aqry = "update buku_induk set thn_susut_ak='{$susut['maxtahun']}' , bln_susut_ak='$maxbulan' ".
						"where id='{$bi['id']}' ; "; $cek .= $aqry;						
				}				
					
				$qry = mysql_query(	$aqry );	
			}		
		}
				
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	/*function susut1barang_serang($idbi, $thnsusutak, $semAkhir, $tglSusut){
	/* susut insert per semester
	** s/d $thnsusutak $blnsusutak
	
	
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//ambil data bi
		$aqry = " select * from buku_induk where id ='$idbi' "; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		$arrtglbuku = explode('-', $bi['tgl_buku'] );
		$thnbuku = $arrtglbuku[0];
		$blnbuku = $arrtglbuku[1];
		$tglSusut = $_REQUEST['tgl'];
					
		//if($bi['status_barang']==1 && ($bi['staset']==3 || $bi['staset']==8) &&  $bi['kondisi']<>3     ){
		//if( ($bi['staset']==3 || $bi['staset']==8 || $bi['staset']==10) &&  $bi['kondisi']<>3     ){
		if( ($bi['staset']==3 || $bi['staset']==8 || $bi['staset']==10)  ){
		
			//ambil masa manfaat, residu -------------------------------------------------------
			$masa_manfaat = 0;
			if($bi['masa_manfaat']>0){
				$masa_manfaat = $bi['masa_manfaat'];
				$persen_residu = $bi['nilai_sisa'];
			}else{
				//ambil dari ref barang
				$aqry = " select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."' ";
				$brg = mysql_fetch_array(mysql_query($aqry)); $cek .= $aqry;
				$masa_manfaat = $brg['masa_manfaat'];
				$persen_residu = $brg['residu']; 
			}
			$cek .= ' masa_manfaat='.$masa_manfaat." persen_residu = $persen_residu";
			
			if($masa_manfaat>0){ 
				//penyusutan sebelumnya --------------------------------------------------------------------
				$aqry = "select * from penyusutan where idbi = '".$bi['id']."' ".
					"and Id = (select max(Id) from penyusutan where idbi = '".$bi['id']."'); "; $cek .= $aqry;
				$prev = mysql_fetch_array(mysql_query(
					$aqry
				));			
				//cari tahun dan bulan mulai hitung susut ---------------------------------------------------
				if( $prev['Id'] == '' ){//penyuustan baru
					//if($bi['asal_usul']==5){
					//	$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $thnbuku ? $thnbuku:  $Main->TAHUN_MULAI_SUSUT;											
					//}else{
						$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $bi['thn_perolehan'] ? $bi['thn_perolehan']:  $Main->TAHUN_MULAI_SUSUT;										
						$thn_susut_aw ++;
					//}
					
					/*$jmlsusut =0;
					$bln_susut_aw = $blnbuku+1;//					
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$aqry = "update buku_induk set thn_susut_aw='".($thn_susut_aw)."' , bln_susut_aw ='".$bln_susut_aw."', ".
						" nilai_sisa='$persen_residu' where id='".$bi['id']."'"; $cek .= $aqry;
					mysql_query($aqry);
					$bi_thnsusutaw = $thn_susut_aw;
					//$bi_blnsusutaw = $bln_susut_aw;
				}else{//melanjutkan
					$aqry = "select count(*) as cnt from penyusutan where idbi = '".$bi['id']."' "; $cek .= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry));
					$jmlsusut = $get['cnt'];
					$thn_susut_aw = $prev['tahun']+1;
					/*$bln_susut_aw = $prev['bulan']+1;
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$bi_thnsusutaw = $bi['thn_susut_aw'];
					//$bi_blnsusutaw = $bi['bln_susut_aw'];
				}			
				
				//ambil hrg rehab/masa manfaat sebelumnya -------------------------------------------------------
				$aqry = " select sum(hrg_rehab) as shrg_rehab, sum(masa_manfaat) as smasa_manfaat from penyusutan where idbi = '".$bi['id']."' ";
				$get = mysql_fetch_array(mysql_query($aqry));																
				$hrg_rehab_prev = $get['shrg_rehab']; $masa_rehab_prev=$get['smasa_manfaat'];
								
				//hitung bulanan
				$blnstop=0; $stop = FALSE;
				//while( $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && 
				//	$thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw ) ){
				$awal = $jmlsusut==0; $hrgSusutBln=0; $akhirsusut=FALSE;
				while($stop==FALSE){
					
					//$cek .= " $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && $thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )  <br>";
					//cek penyusutan ----------------------------------------------
					//if($thn_susut_aw*12+$bln_susut_aw+1 > $thnsusutak*12+$blnsusutak ){						
					if($thn_susut_aw > $thnsusutak){						
						$stop = TRUE; $cek .= "stop karena > thn akan disusutkan";
						//$blnstop=$bln_susut_aw;
					//}else if($thn_susut_aw*12+$bln_susut_aw+1 >= ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )){
					}else if( $thn_susut_aw >=  $bi_thnsusutaw +$masa_manfaat ){
						$stop = TRUE; $cek .= "stop karena masa mafaat telah habis";
						//$blnstop=$bln_susut_aw;
						//$akhirsusut=TRUE;
					}else{
						//cek stop krn penghapusan ------------------------------------
						$blnstop=0;
						$aqry = "select count(*) as cnt from penghapusan ".
							" where id_bukuinduk='".$bi['id'].
							"' and year(tgl_penghapusan)='$thn_susut_aw' ";
							//" and month(tgl_penghapusan)='$bln_susut_aw' ";
						$hps = mysql_fetch_array(mysql_query($aqry));
						if($hps['cnt']>0) {
							$cek .= ' stop penghapusan ';
							//$blnstop=$bln_susut_aw; //break;
							$stop=TRUE;
						}else{
							//cek pindah dari aset tetap/ atb
							/*$aqry = "select count(*) as cnt from t_history_aset ".
								" where idbi='".$bi['id']."' and (staset=3 or staset=8) ".
								" and year(tgl)='$thn_susut_aw' ";//." and month(tgl)='$bln_susut_aw' ";
							$aqry = "select count(*) as cnt from t_history_aset ".
								//" where idbi='".$bi['id']."' and (staset_baru=9 or staset_baru=10) ".
								" where idbi='".$bi['id']."' and staset_baru=9  ".
								" and year(tgl)='$thn_susut_aw' ";
							$all = mysql_fetch_array(mysql_query($aqry));
							if($all['cnt']>0){
								$cek .= ' stop pindah aset ';
								//$blnstop=$bln_susut_aw; //break;
								$stop=TRUE;
							}						
						}	
					}
					
					if ($stop==FALSE){
						
					
						$hrg_perolehan = $bi['jml_harga'];	
						$cek .= "[ thn_susut_aw = $thn_susut_aw - bln_susut_aw= $bln_susut_aw - jmlsusut=$jmlsusut - hrg_rehab_prev=$hrg_rehab_prev - masa_rehab_prev=$masa_rehab_prev]"	;				
						$cek .= ' masa_manfaat='.$masa_manfaat." persen_residu = $persen_residu harga periolehan=$hrg_perolehan ";
												
						if($Main->SUSUT_REHAB){					
						
						//pemeliharaan dihitung tiap tahun mulai pd tahun 1
							$curthn = $thn_susut_aw;
							//$operTahun = $curthn==($bi_thnsusutaw*12)+$bi_blnsusutaw? "<=" : "=" ;
							$operTahun = '<';
							$aqryplh = //pelihara
								"select sum(biaya_pemeliharaan) as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pemeliharaan ".
								"where tambah_aset=1 and idbi_awal='".$bi['idawal']."' ".
								"and YEAR(tgl_pemeliharaan) $operTahun $curthn ; "; //$cek .= $aqryplh;
							$plh= mysql_fetch_array(mysql_query( $aqryplh ));
							$aqryplh = //pengaman
								"select sum(biaya_pengamanan)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pengamanan ".
								"where tambah_aset=1 and idbi_awal='".$bi['idawal']."' ".
								"and YEAR(tgl_pengamanan) $operTahun $curthn ; ";// $cek .= $aqryplh;
							$aman= mysql_fetch_array(mysql_query( $aqryplh ));
							$aqryplh = //hapus sebagian
								"select sum(harga_hapus)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penghapusan_sebagian ".
								" where  idbi_awal='".$bi['idawal']."' ".
								"and YEAR(tgl_penghapusan) $operTahun $curthn ; ";// $cek .= $aqryplh;
							$hps= mysql_fetch_array(mysql_query( $aqryplh ));
							$aqryplh = //koreksi 
								"select sum(harga_baru - harga)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from t_koreksi ".
								"where idbi_awal='".$bi['idawal']."' ".
								"and YEAR(tgl) $operTahun $curthn ; "; // $cek .= $aqryplh;
							$koreksi = mysql_fetch_array(mysql_query( $aqryplh ));								
							$aqryplh = //penilaian 
								"select sum(nilai_barang - nilai_barang_asal)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penilaian ".
								"where idbi_awal='".$bi['idawal']."' ".
								"and (YEAR(tgl_penilaian)*12) + MONTH(tgl_penilaian)  $operTahun $curthn ; ";  //$cek .= $aqryplh;
							$penilaian = mysql_fetch_array(mysql_query( $aqryplh ));
							
							$hrg_rehab = $plh['tot'] + $aman['tot'] - $hps['tot'] + $koreksi['tot']+$penilaian['tot'];						
							$masa_rehab = $plh['totmanfaat'] + $aman['totmanfaat'] - $hps['totmanfaat'] + $koreksi['totmanfaat']+ $penilaian['totmanfaat'];
													
							//update hrg perolehan dan masa manfaat 
							$hrg_perolehan += $hrg_rehab ;//nilai buku = hrg perolehan + hrg rehab s/d thn bulan ini
							$masa_manfaat += $masa_rehab ;
						}
						//$hrgSusut += (($hrg_perolehan-($persen_residu/100*$hrg_perolehan))/$masa_manfaat )/12;
						$hrgSusut = ($hrg_perolehan-($persen_residu/100*$hrg_perolehan))/$masa_manfaat;
						$cek .= "  akhirsusut=$akhirsusut hrg peroleh=$hrg_perolehan persen_residu=$persen_residu masa_manfaat=$masa_manfaat";
						$cek .= " hrgSusut1=$hrgSusut ";
						
						/*if( $thn_susut_aw+1 >=  $bi_thnsusutaw +$masa_manfaat ){
						//if($akhirsusut){
							//kalo akhir sust --> harga susut dibulatkan
							//ambil tot akumulasi
							$get = mysql_fetch_array(mysql_query( "select sum(harga) as tot from penyusutan where idbi_awal = '".$bi['idawal']."' "));
							$totsusutprev = $get['tot']; 
							//if ($totsusutprev + $hrgSusut - ($hrg_perolehan*(1+$persen_residu/100)) > 0 || $persen_residu==0) $hrgSusut = $hrg_perolehan*(1+$persen_residu/100) - $totsusutprev;
							$totsusut_ = ($hrg_perolehan*(1-$persen_residu/100));
							if ($totsusutprev + $hrgSusut - $nilaisisa_ < 0 ) $hrgSusut = $totsusut_ - $totsusutprev;
						}
					
						$cek .= " hrgSusut2=$hrgSusut ";
					
					
						//if($bln_susut_aw==6 || $bln_susut_aw==12 || $stop ){						
						//harga perolehan/nilai buku tanpa penyusutan -------------------------
						$cek .= " hrg_rehab=$hrg_rehab - masa_rehab=$masa_rehab";
						
						//$sem = $bln_susut_aw <=6? 1:2;						
						
						//hit penambahan masa manfaat ---------------------------------------------------
						if($awal){
							$penambahan_manfaat = $masa_manfaat + ($masa_rehab-$masa_rehab_prev) ; 						
							$penambahan_perolehan = $hrg_perolehan + ($hrg_rehab-$hrg_rehab_prev);	
						}else{
							$penambahan_manfaat = $masa_manfaat+$masa_rehab-$masa_rehab_prev ; 
							$penambahan_perolehan = $hrg_rehab-$hrg_rehab_prev;					
						}
						//$bln_susut_aw_ = $sem==2? 12:6;
						
						
						
						$hrgSusutSem = $hrgSusut/2;
						//simpan sem1 -----------------------------------------------------------------------------
						$sem=1 ; $bln_susut_aw_ = 6;
						if((int)$thn_susut_aw<='2014'){
							$tglSusut = '2014-12-31';	
						}else{
							$tglSusut = $thn_susut_aw.'-06-30';	
						}
						
						
						//if($bi_blnsusutaw>6){ //tidak berakhir di semester 1
						if($prev['Id'] == '' ){
							$difRehab = (($hrg_rehab-$hrg_rehab_prev));
							$difManfaat = ($penambahan_manfaat);
							$difPerolehan = ($penambahan_perolehan);
							$difRehab2 = 0;
							$difManfaat2 = 0;
							$difPerolehan2 = 0;
						}else{
							$difRehab = (($hrg_rehab-$hrg_rehab_prev)/2);
							$difManfaat = ($penambahan_manfaat/2);
							$difPerolehan = ($penambahan_perolehan/2);
							$difRehab2 = (($hrg_rehab-$hrg_rehab_prev)/2);
							$difManfaat2 = ($penambahan_manfaat/2);
							$difPerolehan2 = ($penambahan_perolehan/2);
						}
						$aqry2 = "insert into penyusutan (tgl,tahun,sem,bulan,idbi,idbi_awal, harga,".
							" uid,tgl_update, ".						
							" hrg_rehab,masa_manfaat,residu,hrg_perolehan) values ".
							"('$tglSusut','$thn_susut_aw' ,'$sem', '$bln_susut_aw_', '".$bi['id']."',  '".$bi['idawal']."',". 
							"'$hrgSusutSem', '$UID', now(),".							
							"'".$difRehab."','".$difManfaat."','$persen_residu','".$difPerolehan."') ;";						
						$cek .= ' simpan susut: '.$aqry2;
						$qry2 = mysql_query($aqry2);
						//}
						//simpan sem2 -----------------------------------------------------------------------------
						if( $thn_susut_aw+1 >=  $bi_thnsusutaw +$masa_manfaat ){						
							//kalo akhir sust --> harga susut dibulatkan
							//ambil tot akumulasi
							$get = mysql_fetch_array(mysql_query( "select sum(harga) as tot, sum(hrg_perolehan) as nilaibuku  from penyusutan where idbi_awal = '".$bi['idawal']."' "));
							$totsusutprev = $get['tot']; 
							//$totsusut_ = ($hrg_perolehan*(1-$persen_residu/100));
							$nilaibuku_ =$get['nilaibuku'] ;
							$nilaisisa_ = ($persen_residu/100)*$hrg_perolehan;
							//if (round($nilaibuku_,2) - (round($totsusutprev,2) + round($hrgSusutSem,2)) <0  ) {
								$hrgSusutSem = round($nilaibuku_,2) - round($nilaisisa_,2) - round($totsusutprev,2);
								$cek .= "akhir: nilaibuku_=$nilaibuku_, nilaisisa_=$nilaisisa_, totsusutprev=$totsusutprev, hrgSusutSem=$hrgSusutSem ";
							//}
						}
						$sem=2 ; $bln_susut_aw_ = 12; 
						if( (int) $thn_susut_aw<=2014){
							$tglSusut = '2014-12-31';	
						}else{
							$tglSusut = $thn_susut_aw.'-12-31';	
						}
						//$tglSusut = $thnsusutak.'-12-31';
						$aqry2 = "insert into penyusutan (tgl,tahun,sem,bulan,idbi,idbi_awal, harga,".
							" uid,tgl_update, ".
							" hrg_rehab,masa_manfaat,residu,hrg_perolehan) values ".
							"('$tglSusut','$thn_susut_aw' ,'$sem', '$bln_susut_aw_', '".$bi['id']."',  '".$bi['idawal']."',". 
							"'$hrgSusutSem', '$UID', now(),".
							"'".$difRehab2."','".$difManfaat2."','$persen_residu','".$difPerolehan2."') ;";
						$cek .= ' simpan susut: '.$aqry2;
						$qry2 = mysql_query($aqry2);						
						if($qry2){
							$aqry = "update buku_induk set thn_susut_ak='".($thn_susut_aw)."', bln_susut_ak='".($bln_susut_aw_).
								"', masa_manfaat='".($masa_manfaat+$masa_rehab)."'  where id='".$bi['id']."'";
							$qry3 = mysql_query($aqry);
							if($qry3==FALSE) {
								$stop=TRUE;
								$err = 'Gagal simpan data!';
							}//$cek .= $aqry ;
						}else{
							$stop = TRUE;
							$err = 'Gagal simpan data!';
						}												
						$masa_rehab_prev = $masa_manfaat+$masa_rehab;
						$hrg_rehab_prev = $hrg_rehab;
						$hrgSusutBln=0;
						$awal = FALSE;
						
						//simpan sem2 jika belum bln
						
							
							//$cek .=" simpan susut";
						//}
					
					
						$jmlsusut ++;
						//$bln_susut_aw++;
						//if($bln_susut_aw>12) {
							$thn_susut_aw++ ;
						//	$bln_susut_aw= 1;
						//}
						$cek .= " stop=$stop ";
					
					}//end stop
					
				
				}//end while
				
				
				
			}//end if
			else{
				$cek .= 'masa manfaat belum ada!';
			}
		
		}//ifsesuai kondisi/status brang
		else{
			$cek .= ' staset=3/8 and kondisi<>3 ';
		}
	
		return	array('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	
	}*/

	function susut1barang_serang($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			//$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			//hitung ulang sampai penyusutn sebelumnya ------------------------------------
			$susutold = mysql_fetch_array(mysql_query(
				" select max(tgl) as maxtgl from penyusutan where idbi = '$idbi' "
			)) ;
			$tglsusutkoreksi = $susutold['maxtgl']; //ssutkan ulang sampai periode tgl penyusutan sebelumnya
			$aqry = "select  sf_susut_mode2($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode2($idbi, '$tglsusut' , '$UID', 30,0)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						
			}				
		
		}else{
			$aqry = "select  sf_susut_mode2($idbi, '$tglsusut' , '$UID', $jns_trans2,0)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
		/*global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode2($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
			$qry2 = mysql_query($aqry2);
			}						
		}					
		//$isi = mysql_fetch_array( mysql_query("select sf_susut_mode4(9426, '2020-08-01' , 'tes' , 1) as cek;") );
		
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/
	}		
	
	function susut1barang_jabar($idbi, $thnsusutak, $blnsusutak, $tglSusut){
	/* susut insert per bulan, dihitung 1 bulan setelah perolehan, ditampilkan bulanan
	** 
	*/
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//ambil data bi
		$aqry = " select * from buku_induk where id ='$idbi' "; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		$arrtglbuku = explode('-', $bi['tgl_buku'] );
		$thnbuku = $arrtglbuku[0];
		$blnbuku = $arrtglbuku[1];
					
		//if($bi['status_barang']==1 && ($bi['staset']==3 || $bi['staset']==8) &&  $bi['kondisi']<>3     ){
		if( ($bi['staset']==3 || $bi['staset']==8) &&  $bi['kondisi']<>3     ){
		
			//ambil masa manfaat, residu
			$masa_manfaat = 0;
			if($bi['masa_manfaat']>0){
				$masa_manfaat = $bi['masa_manfaat'];
				$persen_residu = $bi['nilai_sisa'];
			}else{
				//ambil dari ref barang
				$aqry = " select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."' ";
				$brg = mysql_fetch_array(mysql_query($aqry)); $cek .= $aqry;
				$masa_manfaat = $brg['masa_manfaat'];
				$persen_residu = $brg['residu']; 
			}
			$cek .= ' masa_manfaat='.$masa_manfaat." persen_residu = $persen_residu";
			if($masa_manfaat>0){ 
				//penyusutan sebelumnya
				$aqry = "select * from penyusutan where idbi = '".$bi['id']."' ".
					"and Id = (select max(Id) from penyusutan where idbi = '".$bi['id']."'); "; $cek .= $aqry;
				$prev = mysql_fetch_array(mysql_query( $aqry ));			
				//cari tahun dan bulan mulai hitung susut
				if( $prev['Id'] == '' ){//penyuustan baru
					//if($bi['asal_usul']==5){
					//	$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $thnbuku ? $thnbuku:  $Main->TAHUN_MULAI_SUSUT;											
					//}else{
						$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $bi['thn_perolehan'] ? $bi['thn_perolehan']:  $Main->TAHUN_MULAI_SUSUT;										
					//}
					
					$bln_susut_aw = $blnbuku+1;//
					$jmlsusut =0;
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$aqry = "update buku_induk set thn_susut_aw='".($thn_susut_aw)."' , bln_susut_aw ='".$bln_susut_aw."', ".
						" nilai_sisa='$persen_residu' where id='".$bi['id']."'"; $cek .= $aqry;
					mysql_query($aqry);
					$bi_thnsusutaw = $thn_susut_aw;
					$bi_blnsusutaw = $bln_susut_aw;
				}else{//melanjutkan
					$aqry = "select count(*) as cnt from penyusutan where idbi = '".$bi['id']."' "; $cek .= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry));
					$jmlsusut = $get['cnt'];
					$thn_susut_aw = $prev['tahun'];
					$bln_susut_aw = $prev['bulan']+1;
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$bi_thnsusutaw = $bi['thn_susut_aw'];
					$bi_blnsusutaw = $bi['bln_susut_aw'];
				}			
				
				//ambil harga / masa manfaat rehab sebelumnya ------------------------------------------	
				$aqry = " select sum(hrg_rehab) as shrg_rehab, sum(masa_manfaat) as smasa_manfaat from penyusutan where idbi = '".$bi['id']."' ";
				$get = mysql_fetch_array(mysql_query($aqry));																
				$hrg_rehab_prev = $get['shrg_rehab']; $masa_rehab_prev=$get['smasa_manfaat'];
				$cek .= " $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && 
					$thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )  ";
				
				//hitung bulanan
				while( $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && 
					$thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw ) ){
					
					//cek penghapusan ------------------------------------
					$aqry = " select count(*) as cnt from penghapusan ".
						" where id_bukuinduk='".$bi['id'].
						"' and year(tgl_penghapusan)='$thn_susut_aw' ".
						" and month(tgl_penghapusan)='$bln_susut_aw' ";
					$hps = mysql_fetch_array(mysql_query($aqry));
					if($hps['cnt']>0) {
						$cek .= ' stop penghapusan ';
						break;
					}else{
						//cek pindah aset
						$aqry = "select count(*) as cnt from t_history_aset ".
							" where idbi='".$bi['id']."' and (staset=3 or staset=8) ".
							" and year(tgl)='$thn_susut_aw' "." and month(tgl)='$bln_susut_aw' ";
						$all = mysql_fetch_array(mysql_query($aqry));
						if($all['cnt']>0){
							$cek .= ' stop pindah aset ';
							break;
						}						
					}
											
					//harga perolehan -------------------------------------------------------
					$hrg_perolehan = $bi['jml_harga'];		
					
					//hitung rehab penyusutan -----------------------------------------------						
					if($Main->SUSUT_REHAB){					
						//$operTahun = $prev['Id']==''? "<=" : "=" ;						
						$curthn = $thn_susut_aw*12 + $bln_susut_aw;
						//$operTahun = $curthn==($bi_thnsusutaw*12)+$bi_blnsusutaw? "<=" : "=" ;
						$operTahun = '<';
						$aqryplh = //pelihara
							"select sum(biaya_pemeliharaan) as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pemeliharaan ".
							"where tambah_aset=1 and idbi_awal='".$bi['idawal']."' ".
							"and (YEAR(tgl_pemeliharaan)*12) + MONTH(tgl_pemeliharaan) $operTahun $curthn ; "; //$cek .= $aqryplh;
						$plh= mysql_fetch_array(mysql_query( $aqryplh ));
						$aqryplh = //pengaman
							"select sum(biaya_pengamanan)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pengamanan ".
							"where tambah_aset=1 and idbi_awal='".$bi['idawal']."' ".
							"and (YEAR(tgl_pengamanan)*12) + MONTH(tgl_pengamanan) $operTahun $curthn ; "; //$cek .= $aqryplh;
						$aman= mysql_fetch_array(mysql_query( $aqryplh ));
						$aqryplh = //hapus sebagian
							"select sum(harga_hapus)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penghapusan_sebagian ".
							" where  idbi_awal='".$bi['idawal']."' ".
							"and (YEAR(tgl_penghapusan)*12) + MONTH(tgl_penghapusan) $operTahun $curthn ; "; //$cek .= $aqryplh;
						$hps= mysql_fetch_array(mysql_query( $aqryplh ));
						$aqryplh = //koreksi 
							"select sum(harga_baru - harga)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from t_koreksi ".
							"where idbi_awal='".$bi['idawal']."' ".
							"and (YEAR(tgl)*12) + MONTH(tgl)  $operTahun $curthn ; ";  //$cek .= $aqryplh;
						$koreksi = mysql_fetch_array(mysql_query( $aqryplh ));
						$aqryplh = //penilaian 
							"select sum(nilai_barang - nilai_barang_asal)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penilaian ".
							"where idbi_awal='".$bi['idawal']."' ".
							"and (YEAR(tgl_penilaian)*12) + MONTH(tgl_penilaian)  $operTahun $curthn ; ";  //$cek .= $aqryplh;
						$penilaian = mysql_fetch_array(mysql_query( $aqryplh ));
						
						$hrg_rehab = $plh['tot'] + $aman['tot'] - $hps['tot'] + $koreksi['tot']+$penilaian['tot'];						
						$masa_rehab = $plh['totmanfaat'] + $aman['totmanfaat'] - $hps['totmanfaat'] + $koreksi['totmanfaat']+ $penilaian['totmanfaat'];
						
						//update hrg perolehan dan masa manfaat 
						$hrg_perolehan += $hrg_rehab ;
						$masa_manfaat += $masa_rehab ;
					}
					
					//hitung susut bulanan ---------------------------------------------------------------------------
					$hrgSusutBln = (($hrg_perolehan-($persen_residu/100*$hrg_perolehan))/$masa_manfaat )/12;
					//$cek .= " hrgSusutBln = (($hrg_perolehan-($persen_residu/100*$hrg_perolehan))/$masa_manfaat )/12 ";					
					//if( $thn_susut_aw+1 >=  $bi_thnsusutaw +$masa_manfaat ){			
					//$cek .= " cek akhir:  ".($thn_susut_aw*12+$bln_susut_aw+1)." > ".((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw);
					if($thn_susut_aw*12+$bln_susut_aw+1 >= ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw)){					
						//kalo akhir sust --> harga susut dibulatkan
						//ambil tot akumulasi
						$get = mysql_fetch_array(mysql_query( "select sum(harga) as tot, sum(hrg_perolehan) as nilaibuku  from penyusutan where idbi_awal = '".$bi['idawal']."' "));
						$totsusutprev = $get['tot']; 
						//$totsusut_ = ($hrg_perolehan*(1-$persen_residu/100));
						$nilaibuku_ =$get['nilaibuku'] ;
						$nilaisisa_ = ($persen_residu/100)*$hrg_perolehan;
						//if (round($nilaibuku_,2) - (round($totsusutprev,2) + round($hrgSusutSem,2)) <0  ) {
							$hrgSusutBln = round($nilaibuku_,2) - round($nilaisisa_,2) - round($totsusutprev,2);
							$cek .= "akhir: nilaibuku_=$nilaibuku_, nilaisisa_=$nilaisisa_, totsusutprev=$totsusutprev, hrgSusutBln=$hrgSusutBln ";
						//}
					}
					
					
					//hit penambahan masa manfaat ---------------------------------------------------
					if($jmlsusut==0){//awal
						$penambahan_manfaat = $masa_manfaat;// + ($masa_rehab-$masa_rehab_prev) ; 						
						$penambahan_perolehan = $hrg_perolehan;// + ($hrg_rehab-$hrg_rehab_prev);	
					}else{//melanjutkan
						$penambahan_manfaat = $masa_manfaat+$masa_rehab-$masa_rehab_prev ; 
						$penambahan_perolehan = $hrg_rehab-$hrg_rehab_prev;					
					}
						
					//simpan
					$aqry2 = "insert into penyusutan (tgl,tahun,bulan,idbi,idbi_awal, harga,".
						" uid,tgl_update, ".
						" hrg_rehab,masa_manfaat,residu,hrg_perolehan) values ".
						"('$tglSusut','$thn_susut_aw' ,'$bln_susut_aw', '".$bi['id']."',  '".$bi['idawal']."',". 
						"'$hrgSusutBln', '$UID', now(),".
						"'".($hrg_rehab-$hrg_rehab_prev)."','".$penambahan_manfaat."','$persen_residu','".$penambahan_perolehan."') ;";
					$cek .= ' '.$aqry2;
					$qry2 = mysql_query($aqry2);
					if($qry2){
						$aqry = "update buku_induk set thn_susut_ak='".($thn_susut_aw)."', bln_susut_ak='".($bln_susut_aw)."', masa_manfaat='".($masa_manfaat+$masa_rehab)."'  where id='".$bi['id']."'";
						mysql_query($aqry);
						$cek .= $aqry ;
					}
					//$masa_rehab_prev = $masa_rehab;
					$masa_rehab_prev += $penambahan_manfaat;
					$hrg_rehab_prev = $hrg_rehab;
					$jmlsusut ++;
					$bln_susut_aw++;
					if($bln_susut_aw>12) {
						$thn_susut_aw++ ;
						$bln_susut_aw= 1;
					}
				}
				
				
				
					
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function susut1barang_mode5($idbi, $thnsusut, $semsusut){
		//serang kota, diismpan tahunan, disusutkan tahun depan
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//$idbi = 9426;
		//if ($semsusut == 1) {
		//	$tglsusut = $thnsusut.'-06-30';	
		//}else{
			$tglsusut = $thnsusut.'-12-31';
		//}
		$aqry = "select  sf_susut_mode5($idbi, '$tglsusut' , '$UID' )  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		//$isi = mysql_fetch_array( mysql_query("select sf_susut_mode4(9426, '2020-08-01' , 'tes' , 1) as cek;") );
		
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
		
	function susut_koreksi($idbi, $tglsusut,$susut_koreksi=FALSE){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $jns_trans2=0; $idk=0; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		$thn_tgl_susut_=substr($tglsusut,0,4);
		
		//query buku_induk
		$query_buku_induk = "select ifnull(masa_manfaat,0) as masa_manfaat_, thn_perolehan, thn_susut_ak, jml_harga, nilai_sisa , tgl_buku, a1, a, b, c, d, e, e1, f, g, h, i, j, if(bln_susut_ak > 6, 2,1) , idawal, staset from buku_induk where id = '$idbi'"; $cek.= $query_buku_induk.' |<br>';
		$isi =mysql_fetch_array(mysql_query($query_buku_induk));
		//query penyusutan sebelumnya
		$query_penyusutan = "select (tahun+1) as thn_susut_ak_,akum_nilai_buku,nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke+1,sisa_masa_manfaat, id_koreksi from penyusutan where idbi = '$idbi' order by id Desc limit 0,1"; $cek.= $query_penyusutan.' |<br>';
		$isi_qp =mysql_fetch_array(mysql_query($query_penyusutan));
		
		//set tahun susut periode
		$thn_susut_ak_ = $isi_qp['thn_susut_ak_'];
		
		//query cek penghapusan
		$query_penghapusan = "select count(*) as jml_data_penghapusan from penghapusan where year(tgl_penghapusan)='$thn_susut_ak_' and id_bukuinduk = '$idbi' ;"; $cek.= $query_penghapusan.' |<br>';
		$isi_penghapusan =mysql_fetch_array(mysql_query($query_penghapusan));
		//query cek histori aset
		$query_pindah_aset = "select count(*) as jml_data_pindah_aset from t_history_aset where year(tgl)='$thn_susut_ak_' and (staset_baru=9 or staset_baru=10) and idbi = '$idbi'"; $cek.= $query_pindah_aset.' |<br>';
		$isi_historiaset =mysql_fetch_array(mysql_query($query_pindah_aset));
		//query pemindahtanganan
		$query_pemindahtanganan = "select count(*) as jml_data_pemindahtanganan from pemindahtanganan where year(tgl_pemindahtanganan)='$thn_susut_ak_' and id_bukuinduk = '$idbi' "; $cek.= $query_pemindahtanganan.' |<br>';
		$isi_pemindahtanganan =mysql_fetch_array(mysql_query($query_pemindahtanganan));
		//query gantirugi
		$query_gantirugi = "select count(*) as jml_data_ganti_rugi from gantirugi where year(tgl_gantirugi)='$thn_susut_ak_' and id_bukuinduk = '$idbi'"; $cek.= $query_gantirugi.' |<br>';
		$isi_gantirugi =mysql_fetch_array(mysql_query($query_gantirugi));
		
		//query pemeliharaan
		$query_pemeliharaan = "select * from pemeliharaan where idbi_awal = '".$isi['idawal']."' and year(tgl_pemeliharaan)='$thn_susut_ak_' and year(tgl_perolehan) <> year(tgl_pemeliharaan) and tambah_aset=1"; $cek.= $query_pemeliharaan.' |<br>';
		$cek_pemeliharaan =mysql_fetch_array(mysql_query($query_pemeliharaan));
		//query pengamanan
		$query_pengamanan = "select * from pengamanan where idbi_awal = '".$isi['idawal']."' and year(tgl_pengamanan)='$thn_susut_ak_' and year(tgl_perolehan) <> year(tgl_pengamanan) and tambah_aset=1"; $cek.= $query_pengamanan.' |<br>';
		$cek_pengamanan =mysql_fetch_array(mysql_query($query_pengamanan));
		//query penghapusan sebagian
		$query_penghapusan_sebagian = "select * from penghapusan_sebagian where idbi_awal = ".$isi['idawal']." and year(tgl_penghapusan)='$thn_susut_ak_' and year(tgl_perolehan) <> year(tgl_penghapusan)"; $cek.= $query_penghapusan_sebagian.' |<br>';
		$cek_penghapusan_sebagian =mysql_fetch_array(mysql_query($query_penghapusan_sebagian));	
		//query koreksi
		$query_koreksi = "select * from t_koreksi where idbi_awal = '".$isi['idawal']."' and year(tgl)='$thn_susut_ak_' and year(tgl_perolehan) <> year(tgl)"; $cek.= $query_koreksi.' |<br>';
		$cek_koreksi =mysql_fetch_array(mysql_query($query_koreksi));				
		//query penilaian
		$query_penilaian = "select * from penilaian where idbi_awal = '".$isi['idawal']."' and year(tgl_penilaian)='$thn_susut_ak_'"; $cek.= $query_penilaian.' |<br>';
		$cek_penilaian =mysql_fetch_array(mysql_query($query_penilaian));	

		if($isi['masa_manfaat_']<=0){
			$stop=1;
			$cek.= "stop karena masa manfaat blm di set |";
		}elseif($thn_tgl_susut_ < $thn_susut_ak_){
			$stop=1;
			$cek.= "stop karena penyusutan sampai tahun periode |";
		}elseif($isi_penghapusan['jml_data_penghapusan']>0){
			$stop=1;
			$cek.= "stop karena ada penghapusan koreksi |";
		}elseif($isi_historiaset['jml_data_pindah_aset']>0){
			$stop=1;
			$cek.= "stop karena pindah aset koreksi |";
		}elseif($isi_pemindahtanganan['jml_data_pemindahtanganan']>0){
			$stop=1;
			$cek.= "stop karena pindah tangan koreksi |";
		}elseif($isi_gantirugi['jml_data_ganti_rugi']>0){
			$stop=1;
			$cek.= "stop karena ganti rugi koreksi |";			
		}else{		
			if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE || $susut_koreksi == TRUE){
			//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE ){
				if($cek_pemeliharaan == TRUE){
					$tgl_koreksi=$cek_pemeliharaan['tgl_pemeliharaan'];
					$jns_koreksi=1;
					$refid_koreksi=$cek_pemeliharaan['id'];
				}elseif($cek_pengamanan == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl_pengamanan'];
					$jns_koreksi=2;
					$refid_koreksi=$cek_koreksi['id'];				
				}elseif($cek_penghapusan_sebagian == TRUE){
					$tgl_koreksi=$cek_penghapusan_sebagian['tgl_penghapusan'];
					$jns_koreksi=3;
					$refid_koreksi=$cek_penghapusan_sebagian['id'];
				}elseif($cek_koreksi == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl'];
					$jns_koreksi=4;
					$refid_koreksi=$cek_koreksi['id'];
				}elseif($cek_penilaian == TRUE){
					$tgl_koreksi=$cek_penilaian['tgl_penilaian'];
					$jns_koreksi=5;
					$refid_koreksi=$cek_penilaian['id'];
				}elseif($susut_koreksi == TRUE){
					$tgl_koreksi=$tglsusut;
					$jns_koreksi=6;
					$refid_koreksi=$idbi;
				}
				$cek.= "disusutkan ulang";	
				//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE){
				
					//insert koreksi Penyusutan
					$query_kp = "insert into t_koreksi_penyusutan (tgl,jns,refid,idbi,idbi_awal) value ('$tgl_koreksi','$jns_koreksi','$refid_koreksi','$idbi','".$isi['idawal']."')"; $cek.= $query_kp;
					$qry_kp = mysql_query($query_kp);
					$idk = mysql_insert_id();
					
					//insert transaksi
					$query_t = "insert into t_transaksi (kint, ka, kb, jns_trans2, refid, idbi, idawal, a1,a,b,c,d,e,e1,f,g,h,i,j, tgl_buku, staset, jns_trans, tgl_update,uid, debet, kredit) ".
					"value ('01','01','".$isi['f']."',31,'$idk','$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."','$tgl_koreksi',0,10,now(),'$UID','0','0')"; $cek.= $query_t;
					$qry_t = mysql_query($query_t);
					
					//update penyusutan lama status dikoreksi
					$akum_susut=0;
					$query_p="select * from penyusutan where idbi='$idbi' and id_koreksi=0"; $cek.= $query_p;
					$qry_p = mysql_query($query_p);
					while($data_p=mysql_fetch_array($qry_p)){
						$aqry = "UPDATE penyusutan set ".
								"id_koreksi='$idk' WHERE Id='".$data_p['Id']."'"; $cek.= $aqry;
						$qry = mysql_query($aqry);				
						$akum_susut=$akum_susut+$data_p['harga'];
					}		
					
					//insert jurnal
					$query_j = "insert into t_jurnal_aset (q,kint,ka,kb,jns_trans2,jns_trans3,refid,refid2,idbi,idawal,a1,a,b,c,d,e,e1,f,g,h,i,j,jml_barang_d,jml_barang_k,debet,kredit,tgl_buku,asal_usul,status_barang,staset,jns_trans,uid, tgl_update) value (31,'01','01','".$isi['f']."',31,31,$idk,0,'$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."',0,0,'$akum_susut',0,'$tgl_koreksi',0,0,0,10,'$UID',now())"; $cek.= $query_j;
					$qry_j =  mysql_query($query_j);
					
					//reset buku induk
					$query_bi = "update buku_induk set masa_manfaat=NULL, thn_susut_aw=NULL, 
								thn_susut_ak=NULL, bln_susut_aw=NULL, bln_susut_ak=NULL  
								where id = '$idbi'"; $cek.= $query_bi;
					$qry_bi =  mysql_query($query_bi);			
					
					$stop=0;
					$jns_trans2=31;
			}else{
				$stop=0;
				$jns_trans2=30;	
				$cek.= "tanpa disusutkan ulang";				
			}				
		}		
		
		return array ('stop'=>$stop, 'jns_trans2'=>$jns_trans2, 'idk'=>$idk, 'cek'=>$cek);
	}

	function susut_koreksi_bulan($idbi, $tglsusut, $susut_koreksi = FALSE){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $jns_trans2=0; $idk=0; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		$thn_tgl_susut_=substr($tglsusut,0,4);
		$bln_tgl_susut_=substr($tglsusut,5,2);
		
		//query buku_induk
		$query_buku_induk = "select ifnull(masa_manfaat,0) as masa_manfaat_, thn_perolehan, thn_susut_ak, jml_harga, nilai_sisa , tgl_buku, a1, a, b, c, d, e, e1, f, g, h, i, j, if(bln_susut_ak > 6, 2,1) , idawal, staset from buku_induk where id = '$idbi'"; $cek.= $query_buku_induk.' |<br>';
		$isi =mysql_fetch_array(mysql_query($query_buku_induk));
		//query penyusutan sebelumnya
		$query_penyusutan = "select (tahun+1) as thn_susut_ak_, (bulan+1) as bln_susut_ak_, akum_nilai_buku,nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke+1,sisa_masa_manfaat from penyusutan where idbi = '$idbi' order by id Desc limit 0,1"; $cek.= $query_penyusutan.' |<br>';
		$isi_qp =mysql_fetch_array(mysql_query($query_penyusutan));
		
		//set tahun susut periode
		$thn_susut_ak_ = $isi_qp['thn_susut_ak_'];
		
		//query cek penghapusan
		$query_penghapusan = "select count(*) as jml_data_penghapusan from penghapusan where year(tgl_penghapusan)='$thn_susut_ak_' and month(tgl_penghapusan)='$bln_tgl_susut_' and id_bukuinduk = '$idbi' ;"; $cek.= $query_penghapusan.' |<br>';
		$isi_penghapusan =mysql_fetch_array(mysql_query($query_penghapusan));
		//query cek histori aset
		$query_pindah_aset = "select count(*) as jml_data_pindah_aset from t_history_aset where year(tgl)='$thn_susut_ak_' and month(tgl)='$bln_tgl_susut_' and (staset_baru=9 or staset_baru=10) and idbi = '$idbi'"; $cek.= $query_pindah_aset.' |<br>';
		$isi_historiaset =mysql_fetch_array(mysql_query($query_pindah_aset));
		//query pemindahtanganan
		$query_pemindahtanganan = "select count(*) as jml_data_pemindahtanganan from pemindahtanganan where year(tgl_pemindahtanganan)='$thn_susut_ak_' and month(tgl_pemindahtanganan)='$bln_tgl_susut_' and id_bukuinduk = '$idbi' "; $cek.= $query_pemindahtanganan.' |<br>';
		$isi_pemindahtanganan =mysql_fetch_array(mysql_query($query_pemindahtanganan));
		//query gantirugi
		$query_gantirugi = "select count(*) as jml_data_ganti_rugi from gantirugi where year(tgl_gantirugi)='$thn_susut_ak_' and month(tgl_gantirugi)='$bln_tgl_susut_' and id_bukuinduk = '$idbi'"; $cek.= $query_gantirugi.' |<br>';
		$isi_gantirugi =mysql_fetch_array(mysql_query($query_gantirugi));
		
		//query pemeliharaan
		$query_pemeliharaan = "select * from pemeliharaan where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and month(tgl_perolehan)=$bln_tgl_susut_ and month(tgl_perolehan) <> month(tgl_pemeliharaan) and tambah_aset=1"; $cek.= $query_pemeliharaan.' |<br>';
		$cek_pemeliharaan =mysql_fetch_array(mysql_query($query_pemeliharaan));
		//query pengamanan
		$query_pengamanan = "select * from pengamanan where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and month(tgl_perolehan)=$bln_tgl_susut_ and month(tgl_perolehan) <> month(tgl_pengamanan) and tambah_aset=1"; $cek.= $query_pengamanan.' |<br>';
		$cek_pengamanan =mysql_fetch_array(mysql_query($query_pengamanan));
		//query penghapusan sebagian
		$query_penghapusan_sebagian = "select * from penghapusan_sebagian where idbi_awal = ".$isi['idawal']." and year(tgl_perolehan)='$thn_susut_ak_' and month(tgl_perolehan)='$bln_tgl_susut_' and month(tgl_perolehan) <> month(tgl_penghapusan)"; $cek.= $query_penghapusan_sebagian.' |<br>';
		$cek_penghapusan_sebagian =mysql_fetch_array(mysql_query($query_penghapusan_sebagian));	
		//query koreksi
		$query_koreksi = "select * from t_koreksi where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and month(tgl_perolehan)='$bln_tgl_susut_'"; $cek.= $query_koreksi.' |<br>';
		$cek_koreksi =mysql_fetch_array(mysql_query($query_koreksi));				
		//query penilaian
		$query_penilaian = "select * from penilaian where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and month(tgl_perolehan)='$bln_tgl_susut_'"; $cek.= $query_penilaian.' |<br>';
		$cek_penilaian =mysql_fetch_array(mysql_query($query_penilaian));	

		if($isi['masa_manfaat_']<=0){
			$stop=1;
			$cek.= "stop karena masa manfaat blm di set |";
		}elseif($thn_tgl_susut_ < $thn_susut_ak_){
			$stop=1;
			$cek.= "stop karena penyusutan sampai tahun periode |";
		}elseif($isi_penghapusan['jml_data_penghapusan']>0){
			$stop=1;
			$cek.= "stop karena ada penghapusan koreksi |";
		}elseif($isi_historiaset['jml_data_pindah_aset']>0){
			$stop=1;
			$cek.= "stop karena pindah aset koreksi |";
		}elseif($isi_pemindahtanganan['jml_data_pemindahtanganan']>0){
			$stop=1;
			$cek.= "stop karena pindah tangan koreksi |";
		}elseif($isi_gantirugi['jml_data_ganti_rugi']>0){
			$stop=1;
			$cek.= "stop karena ganti rugi koreksi |";			
		}else{		
			if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE || $susut_koreksi == TRUE){
			//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE ){
				if($cek_pemeliharaan == TRUE){
					$tgl_koreksi=$cek_pemeliharaan['tgl_pemeliharaan'];
					$jns_koreksi=1;
					$refid_koreksi=$cek_pemeliharaan['id'];
				}elseif($cek_pengamanan == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl_pengamanan'];
					$jns_koreksi=2;
					$refid_koreksi=$cek_koreksi['id'];				
				}elseif($cek_penghapusan_sebagian == TRUE){
					$tgl_koreksi=$cek_penghapusan_sebagian['tgl_penghapusan'];
					$jns_koreksi=3;
					$refid_koreksi=$cek_penghapusan_sebagian['id'];
				}elseif($cek_koreksi == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl'];
					$jns_koreksi=4;
					$refid_koreksi=$cek_koreksi['id'];
				}elseif($cek_penilaian == TRUE){
					$tgl_koreksi=$cek_penilaian['tgl_penilaian'];
					$jns_koreksi=5;
					$refid_koreksi=$cek_penilaian['id'];
				}elseif($susut_koreksi == TRUE){
					$tgl_koreksi=$tglsusut;
					$jns_koreksi=6;
					$refid_koreksi=$idbi;
				}
				$cek.= "disusutkan ulang";	
				//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE){
				
					//insert koreksi Penyusutan
					$query_kp = "insert into t_koreksi_penyusutan (tgl,jns,refid,idbi,idbi_awal) value ('$tgl_koreksi','$jns_koreksi','$refid_koreksi','".$isi['id']."','".$isi['idawal']."')"; $cek.= $query_kp;
					$qry_kp = mysql_query($query_kp);
					$idk = mysql_insert_id();
					
					//insert transaksi
					$query_t = "insert into t_transaksi (kint, ka, kb, jns_trans2, refid, idbi, idawal, a1,a,b,c,d,e,e1,f,g,h,i,j, tgl_buku, staset, jns_trans, tgl_update,uid, debet, kredit) ".
					"value ('01','01','".$isi['f']."',31,'$idk','$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."','$tgl_koreksi',0,10,now(),'$UID','0','0')"; $cek.= $query_t;
					$qry_t = mysql_query($query_t);
					
					//update penyusutan lama status dikoreksi
					$akum_susut=0;
					$query_p="select * from penyusutan where idbi='$idbi'"; $cek.= $query_p;
					$qry_p = mysql_query($query_p);
					while($data_p=mysql_fetch_array($qry_p)){
						$aqry = "UPDATE penyusutan set ".
								"id_koreksi='$idk' WHERE Id='".$data_p['Id']."'"; $cek.= $aqry;
						$qry = mysql_query($aqry);				
						$akum_susut=$akum_susut+$data_p['harga'];
					}		
					
					//insert jurnal
					$query_j = "insert into t_jurnal_aset (q,kint,ka,kb,jns_trans2,jns_trans3,refid,refid2,idbi,idawal,a1,a,b,c,d,e,e1,f,g,h,i,j,jml_barang_d,jml_barang_k,debet,kredit,tgl_buku,asal_usul,status_barang,staset,jns_trans,uid, tgl_update) value (31,'01','01','".$isi['f']."',31,31,$idk,0,'$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."',0,0,'$akum_susut',0,'$tgl_koreksi',0,0,0,10,'$UID',now())"; $cek.= $query_j;
					$qry_j =  mysql_query($query_j);
					
					//reset buku induk
					$query_bi = "update buku_induk set masa_manfaat=NULL, thn_susut_aw=NULL, 
								thn_susut_ak=NULL, bln_susut_aw=NULL, bln_susut_ak=NULL  
								where id = '$idbi'"; $cek.= $query_bi;
					$qry_bi =  mysql_query($query_bi);			
					
					$stop=0;
					$jns_trans2=31;
			}else{
				$stop=0;
				$jns_trans2=30;	
				$cek.= "tanpa disusutkan ulang";				
			}				
		}		
		
		return array ('stop'=>$stop, 'jns_trans2'=>$jns_trans2, 'idk'=>$idk, 'cek'=>$cek);
	}

	/*function susut_koreksi_semester($idbi, $tglsusut, $susut_koreksi = FALSE){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $jns_trans2=0; $idk=0; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		$thn_tgl_susut_=substr($tglsusut,0,4);
		$bln_tgl_susut_=substr($tglsusut,5,2);
		$sem_tgl_susut=$bln_tgl_susut_>='01' && $bln_tgl_susut_<='06'? 1: 2;		
		//query buku_induk
		$query_buku_induk = "select ifnull(masa_manfaat,0) as masa_manfaat_, thn_perolehan, thn_susut_ak, jml_harga, nilai_sisa , tgl_buku, a1, a, b, c, d, e, e1, f, g, h, i, j, if(bln_susut_ak > 6, 2,1) , idawal, staset from buku_induk where id = '$idbi'"; $cek.= $query_buku_induk.' |<br>';
		$isi =mysql_fetch_array(mysql_query($query_buku_induk));
		//query penyusutan sebelumnya
		$query_penyusutan = "select (tahun+1) as thn_susut_ak_,akum_nilai_buku,nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke+1,sisa_masa_manfaat from penyusutan where idbi = '$idbi' order by id Desc limit 0,1"; $cek.= $query_penyusutan.' |<br>';
		$isi_qp =mysql_fetch_array(mysql_query($query_penyusutan));
		
		//set tahun susut periode
		$thn_susut_ak_ = $isi_qp['thn_susut_ak_'];
		
		//query cek penghapusan
		$query_penghapusan = "select count(*) as jml_data_penghapusan from penghapusan where year(tgl_penghapusan)='$thn_susut_ak_' and if(month(tgl_penghapusan)>=1 and month(tgl_penghapusan)<=6,1,2)='$sem_tgl_susut' and id_bukuinduk = '$idbi' ;"; $cek.= $query_penghapusan.' |<br>';
		$isi_penghapusan =mysql_fetch_array(mysql_query($query_penghapusan));
		//query cek histori aset
		$query_pindah_aset = "select count(*) as jml_data_pindah_aset from t_history_aset where year(tgl)='$thn_susut_ak_' and if(month(tgl)>=1 and month(tgl)<=6,1,2)='$sem_tgl_susut' and (staset_baru=9 or staset_baru=10) and idbi = '$idbi'"; $cek.= $query_pindah_aset.' |<br>';
		$isi_historiaset =mysql_fetch_array(mysql_query($query_pindah_aset));
		//query pemindahtanganan
		$query_pemindahtanganan = "select count(*) as jml_data_pemindahtanganan from pemindahtanganan where year(tgl_pemindahtanganan)='$thn_susut_ak_' and if(month(tgl_pemindahtanganan)>=1 and month(tgl_pemindahtanganan)<=6,1,2)='$sem_tgl_susut' and id_bukuinduk = '$idbi' "; $cek.= $query_pemindahtanganan.' |<br>';
		$isi_pemindahtanganan =mysql_fetch_array(mysql_query($query_pemindahtanganan));
		//query gantirugi
		$query_gantirugi = "select count(*) as jml_data_ganti_rugi from gantirugi where year(tgl_gantirugi)='$thn_susut_ak_' and if(month(tgl_gantirugi)>=1 and month(tgl_gantirugi)<=6,1,2)='$sem_tgl_susut' and id_bukuinduk = '$idbi'"; $cek.= $query_gantirugi.' |<br>';
		$isi_gantirugi =mysql_fetch_array(mysql_query($query_gantirugi));
		
		//query pemeliharaan
		$query_pemeliharaan = "select * from pemeliharaan where 
		idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and 
		if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2)='$sem_tgl_susut' and 
		if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_pemeliharaan)>=1 and month(tgl_pemeliharaan)<=6,1,2) and tambah_aset=1"; $cek.= $query_pemeliharaan.' |<br>';
		$cek_pemeliharaan =mysql_fetch_array(mysql_query($query_pemeliharaan));
		//query pengamanan
		$query_pengamanan = "select * from pengamanan where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2)='$sem_tgl_susut' and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_pengamanan)>=1 and month(tgl_pengamanan)<=6,1,2) and tambah_aset=1"; $cek.= $query_pengamanan.' |<br>';
		$cek_pengamanan =mysql_fetch_array(mysql_query($query_pengamanan));
		//query penghapusan sebagian
		$query_penghapusan_sebagian = "select * from penghapusan_sebagian where idbi_awal = ".$isi['idawal']." and year(tgl_perolehan)='$thn_susut_ak_' and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2)='$sem_tgl_susut' and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_penghapusan)>=1 and month(tgl_penghapusan)<=6,1,2)"; $cek.= $query_penghapusan_sebagian.' |<br>';
		$cek_penghapusan_sebagian =mysql_fetch_array(mysql_query($query_penghapusan_sebagian));	
		//query koreksi
		$query_koreksi = "select * from t_koreksi where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2)='$sem_tgl_susut'"; $cek.= $query_koreksi.' |<br>';
		$cek_koreksi =mysql_fetch_array(mysql_query($query_koreksi));				
		//query penilaian
		$query_penilaian = "select * from penilaian where idbi_awal = '".$isi['idawal']."' and year(tgl_perolehan)='$thn_susut_ak_' and if(month(tgl_perolehan)>1 and month(tgl_perolehan)<=6,1,2)='$sem_tgl_susut'"; $cek.= $query_penilaian.' |<br>';
		$cek_penilaian =mysql_fetch_array(mysql_query($query_penilaian));	

		if($isi['masa_manfaat_']<=0){
			$stop=1;
			$cek.= "stop karena masa manfaat blm di set |";
		}elseif($thn_tgl_susut_ < $thn_susut_ak_){
			$stop=1;
			$cek.= "stop karena penyusutan sampai tahun periode |";
		}elseif($isi_penghapusan['jml_data_penghapusan']>0){
			$stop=1;
			$cek.= "stop karena ada penghapusan koreksi |";
		}elseif($isi_historiaset['jml_data_pindah_aset']>0){
			$stop=1;
			$cek.= "stop karena pindah aset koreksi |";
		}elseif($isi_pemindahtanganan['jml_data_pemindahtanganan']>0){
			$stop=1;
			$cek.= "stop karena pindah tangan koreksi |";
		}elseif($isi_gantirugi['jml_data_ganti_rugi']>0){
			$stop=1;
			$cek.= "stop karena ganti rugi koreksi |";			
		}else{		
			if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE || $susut_koreksi == TRUE){
			//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE ){
				if($cek_pemeliharaan == TRUE){
					$tgl_koreksi=$cek_pemeliharaan['tgl_pemeliharaan'];
					$jns_koreksi=1;
					$refid_koreksi=$cek_pemeliharaan['id'];
				}elseif($cek_pengamanan == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl_pengamanan'];
					$jns_koreksi=2;
					$refid_koreksi=$cek_koreksi['id'];				
				}elseif($cek_penghapusan_sebagian == TRUE){
					$tgl_koreksi=$cek_penghapusan_sebagian['tgl_penghapusan'];
					$jns_koreksi=3;
					$refid_koreksi=$cek_penghapusan_sebagian['id'];
				}elseif($cek_koreksi == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl'];
					$jns_koreksi=4;
					$refid_koreksi=$cek_koreksi['id'];
				}elseif($cek_penilaian == TRUE){
					$tgl_koreksi=$cek_penilaian['tgl_penilaian'];
					$jns_koreksi=5;
					$refid_koreksi=$cek_penilaian['id'];
				}elseif($susut_koreksi == TRUE){
					$tgl_koreksi=$tglsusut;
					$jns_koreksi=6;
					$refid_koreksi=$idbi;
				}
				$cek.= "disusutkan ulang";	
				//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE){
				
					//insert koreksi Penyusutan
					$query_kp = "insert into t_koreksi_penyusutan (tgl,jns,refid,idbi,idbi_awal) value ('$tgl_koreksi','$jns_koreksi','$refid_koreksi','".$isi['id']."','".$isi['idawal']."')"; $cek.= $query_kp;
					$qry_kp = mysql_query($query_kp);
					$idk = mysql_insert_id();
					
					//insert transaksi
					$query_t = "insert into t_transaksi (kint, ka, kb, jns_trans2, refid, idbi, idawal, a1,a,b,c,d,e,e1,f,g,h,i,j, tgl_buku, staset, jns_trans, tgl_update,uid, debet, kredit) ".
					"value ('01','01','".$isi['f']."',31,'$idk','$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."','$tgl_koreksi',0,10,now(),'$UID','0','0')"; $cek.= $query_t;
					$qry_t = mysql_query($query_t);
					
					//update penyusutan lama status dikoreksi
					$akum_susut=0;
					$query_p="select * from penyusutan where idbi='$idbi'"; $cek.= $query_p;
					$qry_p = mysql_query($query_p);
					while($data_p=mysql_fetch_array($qry_p)){
						$aqry = "UPDATE penyusutan set ".
								"id_koreksi='$idk' WHERE Id='".$data_p['Id']."'"; $cek.= $aqry;
						$qry = mysql_query($aqry);				
						$akum_susut=$akum_susut+$data_p['harga'];
					}		
					
					//insert jurnal
					$query_j = "insert into t_jurnal_aset (q,kint,ka,kb,jns_trans2,jns_trans3,refid,refid2,idbi,idawal,a1,a,b,c,d,e,e1,f,g,h,i,j,jml_barang_d,jml_barang_k,debet,kredit,tgl_buku,asal_usul,status_barang,staset,jns_trans,uid, tgl_update) value (31,'01','01','".$isi['f']."',31,31,$idk,0,'$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."',0,0,'$akum_susut',0,'$tgl_koreksi',0,0,0,10,'$UID',now())"; $cek.= $query_j;
					$qry_j =  mysql_query($query_j);
					
					//reset buku induk
					$query_bi = "update buku_induk set masa_manfaat=NULL, thn_susut_aw=NULL, 
								thn_susut_ak=NULL, bln_susut_aw=NULL, bln_susut_ak=NULL  
								where id = '$idbi'"; $cek.= $query_bi;
					$qry_bi =  mysql_query($query_bi);			
					
					$stop=0;
					$jns_trans2=31;
			}else{
				$stop=0;
				$jns_trans2=30;	
				$cek.= "tanpa disusutkan ulang";				
			}				
		}		
		
		return array ('stop'=>$stop, 'jns_trans2'=>$jns_trans2, 'idk'=>$idk, 'cek'=>$cek);
	}*/
	
	function susut_koreksi_semester($idbi, $tglsusut, $susut_koreksi = FALSE){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $jns_trans2=0; $idk=0; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		$thn_tgl_susut_=substr($tglsusut,0,4);
		$bln_tgl_susut_=substr($tglsusut,5,2);
		$sem_tgl_susut=$bln_tgl_susut_>='01' && $bln_tgl_susut_<='06'? 1: 2;		
		//query buku_induk
		$query_buku_induk = "select ifnull(masa_manfaat,0) as masa_manfaat_, thn_perolehan, thn_susut_ak, jml_harga, nilai_sisa , tgl_buku, a1, a, b, c, d, e, e1, f, g, h, i, j, if(bln_susut_ak > 6, 2,1) , idawal, staset from buku_induk where id = '$idbi'"; $cek.= $query_buku_induk.' |<br>';
		$isi =mysql_fetch_array(mysql_query($query_buku_induk));
		//query penyusutan sebelumnya
		$query_penyusutan = "select (tahun) as thn_susut_ak_,(sem+1) as sem_susut_ak_,akum_nilai_buku,nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke+1,sisa_masa_manfaat from penyusutan where idbi = '$idbi' order by id Desc limit 0,1"; $cek.= $query_penyusutan.' |<br>';
		$isi_qp =mysql_fetch_array(mysql_query($query_penyusutan));
		
		//set tahun susut periode
		$thn_susut_ak_ = $isi_qp['thn_susut_ak_'];
		$sem_susut_ak_ = $isi_qp['sem_susut_ak_'];
		
		if($sem_susut_ak_>2){
			$thn_susut_ak_=$thn_susut_ak_+1;
			$sem_susut_ak_=1;
		}else{
			$thn_susut_ak_=$thn_susut_ak_;
			$sem_susut_ak_=$sem_susut_ak_;			
		}
		//query cek penghapusan
		$query_penghapusan = "select count(*) as jml_data_penghapusan from penghapusan where year(tgl_penghapusan)='$thn_susut_ak_' and if(month(tgl_penghapusan)>=1 and month(tgl_penghapusan)<=6,1,2)='$sem_susut_ak_' and id_bukuinduk = '$idbi' ;"; $cek.= $query_penghapusan.' |<br>';
		$isi_penghapusan =mysql_fetch_array(mysql_query($query_penghapusan));
		//query cek histori aset
		$query_pindah_aset = "select count(*) as jml_data_pindah_aset from t_history_aset where year(tgl)='$thn_susut_ak_' and if(month(tgl)>=1 and month(tgl)<=6,1,2)='$sem_susut_ak_' and (staset_baru=9 or staset_baru=10) and idbi = '$idbi'"; $cek.= $query_pindah_aset.' |<br>';
		$isi_historiaset =mysql_fetch_array(mysql_query($query_pindah_aset));
		//query pemindahtanganan
		$query_pemindahtanganan = "select count(*) as jml_data_pemindahtanganan from pemindahtanganan where year(tgl_pemindahtanganan)='$thn_susut_ak_' and if(month(tgl_pemindahtanganan)>=1 and month(tgl_pemindahtanganan)<=6,1,2)='$sem_susut_ak_' and id_bukuinduk = '$idbi' "; $cek.= $query_pemindahtanganan.' |<br>';
		$isi_pemindahtanganan =mysql_fetch_array(mysql_query($query_pemindahtanganan));
		//query gantirugi
		$query_gantirugi = "select count(*) as jml_data_ganti_rugi from gantirugi where year(tgl_gantirugi)='$thn_susut_ak_' and if(month(tgl_gantirugi)>=1 and month(tgl_gantirugi)<=6,1,2)='$sem_susut_ak_' and id_bukuinduk = '$idbi'"; $cek.= $query_gantirugi.' |<br>';
		$isi_gantirugi =mysql_fetch_array(mysql_query($query_gantirugi));
		
		//query pemeliharaan
		$query_pemeliharaan = "select * from pemeliharaan where idbi_awal = '".$isi['idawal']."' 
		and year(tgl_pemeliharaan)='$thn_susut_ak_' and	if(month(tgl_pemeliharaan)>=1 and month(tgl_pemeliharaan)<=6,1,2)='$sem_susut_ak_'   
		and (year(tgl_perolehan) <> year(tgl_pemeliharaan) 
		or (year(tgl_perolehan) = year(tgl_pemeliharaan) 
		and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_pemeliharaan)>=1 and month(tgl_pemeliharaan)<=6,1,2))) 
		and tambah_aset=1"; $cek.= $query_pemeliharaan.' |<br>';
		$cek_pemeliharaan =mysql_fetch_array(mysql_query($query_pemeliharaan));
		//query pengamanan
		$query_pengamanan = "select * from pengamanan where idbi_awal = '".$isi['idawal']."'  
		and year(tgl_pengamanan)='$thn_susut_ak_' and if(month(tgl_pengamanan)>=1 and month(tgl_pengamanan)<=6,1,2)='$sem_susut_ak_'   
		and (year(tgl_perolehan) <> year(tgl_pengamanan) 
		or (year(tgl_perolehan) = year(tgl_pengamanan) 
		and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_pengamanan)>=1 and month(tgl_pengamanan)<=6,1,2))) 
			and tambah_aset=1"; $cek.= $query_pengamanan.' |<br>';
		$cek_pengamanan =mysql_fetch_array(mysql_query($query_pengamanan));
		//query penghapusan sebagian
		$query_penghapusan_sebagian = "select * from penghapusan_sebagian where idbi_awal = ".$isi['idawal']." 
		and year(tgl_penghapusan)='$thn_susut_ak_' and if(month(tgl_penghapusan)>=1 and month(tgl_penghapusan)<=6,1,2)='$sem_susut_ak_' 
		and (year(tgl_perolehan) <> year(tgl_penghapusan) 
		or (year(tgl_perolehan) = year(tgl_penghapusan) 
		and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl_penghapusan)>=1 and month(tgl_penghapusan)<=6,1,2)))"; $cek.= $query_penghapusan_sebagian.' |<br>';
		$cek_penghapusan_sebagian =mysql_fetch_array(mysql_query($query_penghapusan_sebagian));	
		//query koreksi
		$query_koreksi = "select * from t_koreksi where idbi_awal = '".$isi['idawal']."' 
		and year(tgl)='$thn_susut_ak_' and if(month(tgl)>=1 and month(tgl)<=6,1,2)='$sem_susut_ak_' 
		and (year(tgl_perolehan) <> year(tgl) 
		or (year(tgl_perolehan) = year(tgl) 
		and if(month(tgl_perolehan)>=1 and month(tgl_perolehan)<=6,1,2) <> if(month(tgl)>=1 and month(tgl)<=6,1,2)))"; $cek.= $query_koreksi.' |<br>';
		$cek_koreksi =mysql_fetch_array(mysql_query($query_koreksi));				
		//query penilaian
		$query_penilaian = "select * from penilaian where idbi_awal = '".$isi['idawal']."' and year(tgl_penilaian)='$thn_susut_ak_' and if(month(tgl_penilaian)>1 and month(tgl_penilaian)<=6,1,2)='$sem_susut_ak_'"; $cek.= $query_penilaian.' |<br>';
		$cek_penilaian =mysql_fetch_array(mysql_query($query_penilaian));	

		if($isi['masa_manfaat_']<=0){
			$stop=1;
			$cek.= "stop karena masa manfaat blm di set |";
		}elseif($thn_tgl_susut_ < $thn_susut_ak_){
			$stop=1;
			$cek.= "stop karena penyusutan sampai tahun periode |";
		}elseif($isi_penghapusan['jml_data_penghapusan']>0){
			$stop=1;
			$cek.= "stop karena ada penghapusan koreksi |";
		}elseif($isi_historiaset['jml_data_pindah_aset']>0){
			$stop=1;
			$cek.= "stop karena pindah aset koreksi |";
		}elseif($isi_pemindahtanganan['jml_data_pemindahtanganan']>0){
			$stop=1;
			$cek.= "stop karena pindah tangan koreksi |";
		}elseif($isi_gantirugi['jml_data_ganti_rugi']>0){
			$stop=1;
			$cek.= "stop karena ganti rugi koreksi |";			
		}else{		
			if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE || $susut_koreksi == TRUE){
			//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE ){
				if($cek_pemeliharaan == TRUE){
					$tgl_koreksi=$cek_pemeliharaan['tgl_pemeliharaan'];
					$jns_koreksi=1;
					$refid_koreksi=$cek_pemeliharaan['id'];
				}elseif($cek_pengamanan == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl_pengamanan'];
					$jns_koreksi=2;
					$refid_koreksi=$cek_koreksi['id'];				
				}elseif($cek_penghapusan_sebagian == TRUE){
					$tgl_koreksi=$cek_penghapusan_sebagian['tgl_penghapusan'];
					$jns_koreksi=3;
					$refid_koreksi=$cek_penghapusan_sebagian['id'];
				}elseif($cek_koreksi == TRUE){
					$tgl_koreksi=$cek_koreksi['tgl'];
					$jns_koreksi=4;
					$refid_koreksi=$cek_koreksi['id'];
				}elseif($cek_penilaian == TRUE){
					$tgl_koreksi=$cek_penilaian['tgl_penilaian'];
					$jns_koreksi=5;
					$refid_koreksi=$cek_penilaian['id'];
				}elseif($susut_koreksi == TRUE){
					$tgl_koreksi=$tglsusut;
					$jns_koreksi=6;
					$refid_koreksi=$idbi;
				}
				$cek.= "disusutkan ulang";	
				//if($cek_pemeliharaan == TRUE || $cek_pengamanan == TRUE || $cek_penghapusan_sebagian == TRUE || $cek_koreksi == TRUE || $cek_penilaian == TRUE){
				
					//insert koreksi Penyusutan
					$query_kp = "insert into t_koreksi_penyusutan (tgl,jns,refid,idbi,idbi_awal) value ('$tgl_koreksi','$jns_koreksi','$refid_koreksi','".$isi['id']."','".$isi['idawal']."')"; $cek.= $query_kp;
					$qry_kp = mysql_query($query_kp);
					$idk = mysql_insert_id();
					
					//insert transaksi
					$query_t = "insert into t_transaksi (kint, ka, kb, jns_trans2, refid, idbi, idawal, a1,a,b,c,d,e,e1,f,g,h,i,j, tgl_buku, staset, jns_trans, tgl_update,uid, debet, kredit) ".
					"value ('01','01','".$isi['f']."',31,'$idk','$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."','$tgl_koreksi',0,10,now(),'$UID','0','0')"; $cek.= $query_t;
					$qry_t = mysql_query($query_t);
					
					//update penyusutan lama status dikoreksi
					$akum_susut=0;
					$query_p="select * from penyusutan where idbi='$idbi' and id_koreksi=0"; $cek.= $query_p;
					$qry_p = mysql_query($query_p);
					while($data_p=mysql_fetch_array($qry_p)){
						$aqry = "UPDATE penyusutan set ".
								"id_koreksi='$idk' WHERE Id='".$data_p['Id']."'"; $cek.= $aqry;
						$qry = mysql_query($aqry);				
						$akum_susut=$akum_susut+$data_p['harga'];
					}		
					
					//insert jurnal
					$query_j = "insert into t_jurnal_aset (q,kint,ka,kb,jns_trans2,jns_trans3,refid,refid2,idbi,idawal,a1,a,b,c,d,e,e1,f,g,h,i,j,jml_barang_d,jml_barang_k,debet,kredit,tgl_buku,asal_usul,status_barang,staset,jns_trans,uid, tgl_update) value (31,'01','01','".$isi['f']."',31,31,$idk,0,'$idbi','".$isi['idawal']."','".$isi['a1']."','".$isi['a']."','".$isi['b']."','".$isi['c']."','".$isi['d']."','".$isi['e']."','".$isi['e1']."','".$isi['f']."','".$isi['g']."','".$isi['h']."','".$isi['i']."','".$isi['j']."',0,0,'$akum_susut',0,'$tgl_koreksi',0,0,0,10,'$UID',now())"; $cek.= $query_j;
					$qry_j =  mysql_query($query_j);
					
					//reset buku induk
					$query_bi = "update buku_induk set masa_manfaat=NULL, thn_susut_aw=NULL, 
								thn_susut_ak=NULL, bln_susut_aw=NULL, bln_susut_ak=NULL  
								where id = '$idbi'"; $cek.= $query_bi;
					$qry_bi =  mysql_query($query_bi);
					
					$stop=0;
					$jns_trans2=31;
			}else{
				$stop=0;
				$jns_trans2=30;	
				$cek.= "tanpa disusutkan ulang";				
			}				
		}		
		
		return array ('stop'=>$stop, 'jns_trans2'=>$jns_trans2, 'idk'=>$idk, 'cek'=>$cek);
	}


	/*function susut1barang_garut($idbi, $thnsusut, $semsusut, $jns_trans2){
		//garut kab, disimpan semesteran disusutkan tahun ini/semester ini
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//$idbi = 9426;
		if ($semsusut == 1) {
			$tglsusut = $thnsusut.'-06-30';	
		}else{
			$tglsusut = $thnsusut.'-12-31';
		}
		$aqry = "select  sf_susut_mode4($idbi, '$tglsusut' , '$UID' , 1)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		//$isi = mysql_fetch_array( mysql_query("select sf_susut_mode4(9426, '2020-08-01' , 'tes' , 1) as cek;") );
		
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}*/
	function susut1barang($idbi, $thnsusutak, $blnsusutak, $tglSusut){
	/* susut insert per semester, tampil di generate bulanan
	** s/d $thnsusutak $blnsusutak
	*/
	
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//ambil data bi
		$aqry = " select * from buku_induk where id ='$idbi' "; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		$aqry = " select * from buku_induk where id ='".$bi['idawal']."' "; $cek .= $aqry;
		$biawal = mysql_fetch_array(mysql_query($aqry));
		
		$arrtglbuku = explode('-', $biawal['tgl_buku'] );
		$thnbuku = $arrtglbuku[0];
		$blnbuku = $arrtglbuku[1];
		 $stop = FALSE; $stophapus=FALSE;
		 			
		//if($bi['status_barang']==1 && ($bi['staset']==3 || $bi['staset']==8) &&  $bi['kondisi']<>3     ){
		//if( ($bi['staset']==3 || $bi['staset']==8) &&  $bi['kondisi']<>3     ){
		if( ($bi['staset']==3 || $bi['staset']==8)   ){
		
			//ambil masa manfaat, residu
			$masa_manfaat = 0;
			if($bi['masa_manfaat']>0){
				$masa_manfaat = $bi['masa_manfaat'];
				$persen_residu = $bi['nilai_sisa'];
			}else{
				//ambil dari ref barang
				$aqry = " select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."' ";
				$brg = mysql_fetch_array(mysql_query($aqry)); $cek .= $aqry;
				$masa_manfaat = $brg['masa_manfaat'];
				$persen_residu = $brg['residu']; 
			}
			$cek .= ' masa_manfaat='.$masa_manfaat." persen_residu = $persen_residu";
			if($masa_manfaat>0){ 
				//penyusutan sebelumnya
				$aqry = "select * from penyusutan where idbi = '".$bi['id']."' ".
					"and Id = (select max(Id) from penyusutan where idbi = '".$bi['id']."'); "; $cek .= $aqry;
				$prev = mysql_fetch_array(mysql_query( $aqry ));			
				//cari tahun dan bulan mulai hitung susut
				if( $prev['Id'] == '' ){//penyuustan baru
					//if($bi['asal_usul']==5){
					//	$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $thnbuku ? $thnbuku:  $Main->TAHUN_MULAI_SUSUT;											
					//}else{
						$thn_susut_aw = $Main->TAHUN_MULAI_SUSUT < $bi['thn_perolehan'] ? $bi['thn_perolehan']:  $Main->TAHUN_MULAI_SUSUT;										
					//}
					
					$bln_susut_aw = $blnbuku+1;//
					$jmlsusut =0;
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$aqry = " update buku_induk set thn_susut_aw='".($thn_susut_aw)."' , bln_susut_aw ='".$bln_susut_aw."', ".
						" nilai_sisa='$persen_residu' where id='".$bi['id']."'"; $cek .= $aqry;
					mysql_query($aqry);
					$bi_thnsusutaw = $thn_susut_aw;
					$bi_blnsusutaw = $bln_susut_aw;
				}else{//melanjutkan
					$aqry = "select count(*) as cnt from penyusutan where idbi = '".$bi['id']."' "; $cek .= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry));
					$jmlsusut = $get['cnt'];
					$thn_susut_aw = $prev['tahun'];
					$bln_susut_aw = $prev['bulan']+1;
					if($bln_susut_aw>12){
						$thn_susut_aw ++;
						$bln_susut_aw = 1;
					}
					$bi_thnsusutaw = $bi['thn_susut_aw'];
					$bi_blnsusutaw = $bi['bln_susut_aw'];
				}			
				
				//ambil hrg rehab/masa manfaat sebelumnya
				$aqry = " select sum(hrg_rehab) as shrg_rehab, sum(masa_manfaat) as smasa_manfaat from penyusutan where idbi = '".$bi['id']."' ";
				$get = mysql_fetch_array(mysql_query($aqry));																
				$hrg_rehab_prev = $get['shrg_rehab']; $masa_rehab_prev=$get['smasa_manfaat'];
								
				//hitung bulanan
				$blnstop=0; $stop = FALSE; $stophapus=FALSE;
				//while( $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && 
				//	$thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw ) ){
				$awal = $jmlsusut==0; $hrgSusutBln=0; $akhirsusut=FALSE;
				while($stop==FALSE){
					$cek .= "\n [ thn_susut_aw = $thn_susut_aw - bln_susut_aw= $bln_susut_aw - jmlsusut=$jmlsusut - hrg_rehab_prev=$hrg_rehab_prev - masa_rehab_prev=$masa_rehab_prev]"	;				
					$cek .= ' masa_manfaat='.$masa_manfaat." persen_residu = $persen_residu";
					
					//$cek .= " $thn_susut_aw*12+$bln_susut_aw <= $thnsusutak*12+$blnsusutak && $thn_susut_aw*12+$bln_susut_aw < ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )  <br>";
					//cek penyusutan ----------------------------------------------
					if($thn_susut_aw*12+$bln_susut_aw+1 >= ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )){
						$stop = TRUE; $cek .= "stop karena > thn akhir disusutkan";
						$blnstop=$bln_susut_aw;
						$akhirsusut=TRUE;
					}
					if($thn_susut_aw*12+$bln_susut_aw+1 > $thnsusutak*12+$blnsusutak ){						
						$stop = TRUE; $cek .= "stop karena > thn akan disusutkan";
						$blnstop=$bln_susut_aw;
					//}else if($thn_susut_aw*12+$bln_susut_aw+1 >= ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )){
					}
					//else{
						
						//}else{
					//cek pindah aset ---------------------------
					/*$aqry = "select count(*) as cnt from t_history_aset ".
						" where idbi='".$bi['id']."' and (staset=3 or staset=8) ".
						" and year(tgl)='$thn_susut_aw' "." and month(tgl)='$bln_susut_aw' ";*/
					$aqry = "select count(*) as cnt from t_history_aset ".
						" where idbi='".$bi['id']."' and (staset_baru=9 or staset_baru=10) ".
						" and year(tgl)='$thn_susut_aw' ";
					$all = mysql_fetch_array(mysql_query($aqry));
					if($all['cnt']>0){
						$cek .= ' stop pindah aset ';
						$blnstop=$bln_susut_aw; //break;
						$stop=TRUE;
					}						
						//}	
					//}
					
					//cek stop krn penghapusan ------------------------------------
					$blnstop=0;
					$aqry = "select  count(*) as cnt from penghapusan ".
						" where id_bukuinduk='".$bi['id'].
						//"' and year(tgl_penghapusan)='$thn_susut_aw'  ".
						//" and month(tgl_penghapusan)='$bln_susut_aw' ";
						"' and '$thn_susut_aw' *2+ if('$bln_susut_aw' > 6,2,1)  >= year(tgl_penghapusan)*2+ if(month(tgl_penghapusan)>6,2,1)  ";
					$hps = mysql_fetch_array(mysql_query($aqry));
					if($hps['cnt']>0) {
						$cek .= ' ** stop penghapusan , qry='.$aqry ;
						$blnstop=$bln_susut_aw; //break;
						$stop=TRUE;
						$stophapus=TRUE;
					}
					
					$cek .= ' akhir manfaat = '."$thn_susut_aw*12+$bln_susut_aw+1 >= ((( $bi_thnsusutaw +$masa_manfaat)*12)+$bi_blnsusutaw )";
					$hrg_perolehan = $bi['jml_harga'];								
					
					// return concat(hrg_susut_,';',tot_rehab_,';',masa_rehab_,';',masa_manfaat_,';',hrg_perolehan_,';',nilai_buku_);
					$aqry = "select sf_get_susut( ".$idbi.", ".$thn_susut_aw.", ".$bln_susut_aw.", 0) as hsl ; ";	$cek.= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry) )	;
					$hsl= explode(';',$get['hsl']);
					$hrgSusut = $hsl[0];
					$hrg_rehab = $hsl[1];
					$masa_rehab = $hsl[2];
					$masa_manfaat = $hsl[3];
					$hrg_perolehan = $hsl[4];
					
					
					
					$cek .= '$hrg_rehab='.$hrg_rehab.' $masa_rehab='.$masa_rehab;
					
					//$hrgSusut = (($hrg_perolehan-($persen_residu/100*$hrg_perolehan))/$masa_manfaat )/12; $cek.= ' $hrgSusut='.$hrgSusut;
					
					$hrgSusutBln += $hrgSusut;
					
					if($akhirsusut){
						//kalo akhir sust --> harga susut dibulatkan
						
						$get = mysql_fetch_array(mysql_query( "select sum(harga) as tot, sum(hrg_perolehan) as nilaibuku  from penyusutan where idbi_awal = '".$bi['idawal']."' "));
						$totsusutprev = $get['tot']; 						
						$nilaibuku_ =$get['nilaibuku'] ;
						$nilaisisa_ = ($persen_residu/100)*$hrg_perolehan;
						//$hrgSusutSem = round($nilaibuku_,2) - round($nilaisisa_,2) - round($totsusutprev,2);
						$hrgSusutBln = round($nilaibuku_,2) - round($nilaisisa_,2) - round($totsusutprev,2);
						$cek .= "akhir: nilaibuku_=$nilaibuku_, nilaisisa_=$nilaisisa_, totsusutprev=$totsusutprev, hrgSusutSem=$hrgSusutSem ";
						
					}
					
					$cek .= " akum hrgSusutBln=$hrgSusutBln ";
					
					if( ($bln_susut_aw==6 || $bln_susut_aw==12 || $stop) && $stophapus==FALSE  ){						
						//harga perolehan/nilai buku tanpa penyusutan -------------------------
						$cek .= " hrg_rehab=$hrg_rehab - masa_rehab=$masa_rehab";
						
						$sem = $bln_susut_aw <=6? 1:2;						
						
						//hit penambahan masa manfaat ---------------------------------------------------
						if($awal){
							$penambahan_manfaat = $masa_manfaat + ($masa_rehab-$masa_rehab_prev) ; 						
							$penambahan_perolehan = $hrg_perolehan + ($hrg_rehab-$hrg_rehab_prev);	
						}else{
							$penambahan_manfaat = $masa_manfaat+$masa_rehab-$masa_rehab_prev ; 
							$penambahan_perolehan = $hrg_rehab-$hrg_rehab_prev;					
						}
						$bln_susut_aw_ = $sem==2? 12:6;
						
						if( (int) $thn_susut_aw<=2014){
							$tglSusut = '2014-12-31';
						}else{
							
							if($sem == 1){
								$tglSusut= $thn_susut_aw .'-06-30';
							}else{
								$tglSusut= $thn_susut_aw .'-12-31';	
							}
						}
						
						
						
						$cek .= ' tglSusut = '.$tglSusut;
						
						//simpan -----------------------------------------------------------------------------
						$aqry2 = "insert into penyusutan (tgl,tahun,sem,bulan,idbi,idbi_awal, harga,".
							" uid,tgl_update, ".
							" hrg_rehab,masa_manfaat,residu,hrg_perolehan) values ".
							"('$tglSusut','$thn_susut_aw' ,'$sem', '$bln_susut_aw_', '".$bi['id']."',  '".$bi['idawal']."',". 
							"'$hrgSusutBln', '$UID', now(),".
							"'".($hrg_rehab-$hrg_rehab_prev)."','".$penambahan_manfaat."','$persen_residu','".$penambahan_perolehan."') ;";
						$cek .= ' simpan susut: '.$aqry2;
						$qry2 = mysql_query($aqry2);
						
						if($qry2){
							$aqry = "update buku_induk set thn_susut_ak='".($thn_susut_aw)."', bln_susut_ak='".($bln_susut_aw_).
								"', masa_manfaat='".($masa_manfaat+$masa_rehab)."'  where id='".$bi['id']."'";
							$qry3 = mysql_query($aqry);
							if($qry3==FALSE) {
								$stop=TRUE;
								$err = 'Gagal simpan data!';
							}
							//$cek .= $aqry ;
						}else{
							$stop = TRUE;
							$err = 'Gagal simpan data!';
						}
						$masa_rehab_prev = $masa_manfaat+$masa_rehab;
						$hrg_rehab_prev = $hrg_rehab;
						$hrgSusutBln=0;
						$awal = FALSE;
						//$cek .=" simpan susut";
					}
					
					
					$jmlsusut ++;
					$bln_susut_aw++;
					if($bln_susut_aw>12) {
						$thn_susut_aw++ ;
						$bln_susut_aw= 1;
					}
				}
				
				
				
					
			}
		}
		
		if ($stop) {
			if($Main->VERSI_NAME=='BDG_BARAT'){
				$stop_susut = 1;
				if($stophapus) $stop_susut = 2;
				$qry = mysql_query("update buku_induk set stop_susut=$stop_susut where id='$idbi'");	
			}
			
		}
		$cek = $Main->SHOW_CEK ? $cek : '';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
		
	function susut1barang_jabar2($idbi, $tglsusut,  $jns_trans2, $susut_koreksi=FALSE){
		//mode =3
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$bi = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id='$idbi'"
		));
		if ($bi['thn_perolehan'] <=2014){
			$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
			$idk = $sk['idk']==0?0:$sk['idk'];
			$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
			$aqry = "select  sf_susut_mode3_thn($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){ 
					$aqry2 = "call sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}						
			}
		}else{
			$sk=$this->susut_koreksi_bulan($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
			$idk = $sk['idk']==0?0:$sk['idk'];
			$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
			$aqry = "select  sf_susut_mode3_bln($idbi, '$tglsusut' ,  '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}						
			}

	
		}
		
		$isi =mysql_fetch_array( mysql_query($aqry));
		
		
		//$cek .= 'cek='.$isi['cek'];
		$cek .= 'akum_susut = '.$qry_p['akum_susut'].' || tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}

	function susut1barang_garut($idbi, $tglsusut, $jns_trans2,$susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi_semester($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutbulan=substr($tglsusut,5,2);
			if($tglsusutbulan=12){
				$tglsusuttahun=substr($tglsusut,0,4);
				$tglsusutbulan=6;
				$tglsusuttgl=30;							
			}else{
				$tglsusuttahun=substr($tglsusut,0,4)-1;
				$tglsusutbulan=12;
				$tglsusuttgl=31;				
			}
			$tglsusutkoreksi=$tglsusuttahun.'-'.$tglsusutbulan.'-'.$tglsusuttgl;//date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select  sf_susut_mode4($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){					
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode4($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode4($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);

		/*global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//$tglsusut = $thnsusut.'-12-31';
		$sk=$this->susut_koreksi_semester($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode4($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
			$qry2 = mysql_query($aqry2);
			}						
		}				
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/
	}

	function susut1barang_tasik($idbi, $tglsusut, $jns_trans2,$susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			//$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			//hitung ulang sampai penyusutn sebelumnya -------------------------------------
			$susutold = mysql_fetch_array(mysql_query(
				" select max(tgl) as maxtgl from penyusutan where idbi = '$idbi' "
			)) ;
			$tglsusutkoreksi = $susutold['maxtgl']; //ssutkan ulang sampai periode tgl penyusutan sebelumnya
			$aqry = "select  sf_susut_mode6($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode6($idbi, '$tglsusut' , '$UID', 30,0)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode6($idbi, '$tglsusut' , '$UID', $jns_trans2,0)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		/*global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//$tglsusut = $thnsusut.'-12-31';
		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode6($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
			$qry2 = mysql_query($aqry2);
			}						
		}				
		
		$cek .= 'akum_susut = '.$qry_p['akum_susut'].' || tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/
	}
	
	function susut1barang_pandeglang($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			//$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			//hitung ulang sampai penyusutn sebelumnya -------------------------------------
			$susutold = mysql_fetch_array(mysql_query(
				" select max(tgl) as maxtgl from penyusutan where idbi = '$idbi' "
			)) ;
			$tglsusutkoreksi = $susutold['maxtgl']; //ssutkan ulang sampai periode tgl penyusutan sebelumnya
			$aqry = "select  sf_susut_mode7($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					//$aqry3 = "select sf_susut_mode7($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$aqry3 = "select sf_susut_mode7($idbi, '$tglsusut' , '$UID', 30,0)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			//$aqry = "select  sf_susut_mode7($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$aqry = "select  sf_susut_mode7($idbi, '$tglsusut' , '$UID', $jns_trans2,0 )  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
	}		

	/*function susut1barang_pandeglang($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select  sf_susut_mode7($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode7($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode7($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
		/*global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode7($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
			$qry2 = mysql_query($aqry2);
			}						
		}					
		//$isi = mysql_fetch_array( mysql_query("select sf_susut_mode4(9426, '2020-08-01' , 'tes' , 1) as cek;") );
		
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	*/

	function susut1barang_cirebon($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){
		//mode =3
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi_semester($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutbulan=substr($tglsusut,5,2);
			if($tglsusutbulan=12){
				$tglsusuttahun=substr($tglsusut,0,4);
				$tglsusutbulan=6;
				$tglsusuttgl=30;							
			}else{
				$tglsusuttahun=substr($tglsusut,0,4)-1;
				$tglsusutbulan=12;
				$tglsusuttgl=31;				
			}
			$tglsusutkoreksi=$tglsusuttahun.'-'.$tglsusutbulan.'-'.$tglsusuttgl;//date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select sf_susut_mode8($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){					
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode8($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode8($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);

		
		/*$sk=$this->susut_koreksi_semester($idbi,$tglSusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode8($idbi, '$tglSusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;			$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
				$qry2 = mysql_query($aqry2);
			}						
		}

	
		
		
		$isi =mysql_fetch_array( mysql_query($aqry));
		
		
		$cek .= 'akum_susut = '.$qry_p['akum_susut'].' || tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/

	}

	function susut1barang_bdgbrt($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			//$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			//hitung ulang sampai penyusutn sebelumnya ------------------------------------
			$susutold = mysql_fetch_array(mysql_query(
				" select max(tgl) as maxtgl from penyusutan where idbi = '$idbi' "
			)) ;
			$tglsusutkoreksi = $susutold['maxtgl']; //ssutkan ulang sampai periode tgl penyusutan sebelumnya
			$aqry = "select  sf_susut_mode9($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode9($idbi, '$tglsusut' , '$UID', 30,0)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						
			}				
		
		}else{
			$aqry = "select  sf_susut_mode9($idbi, '$tglsusut' , '$UID', $jns_trans2,0)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
	}		
	/*function susut1barang_bdgbrt($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select  sf_susut_mode9($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode9($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode9($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}*/
	
	function susut1barang_bogor($idbi, $tglsusut, $jns_trans2, $susut_koreksi=FALSE){//garut
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutkoreksi=date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select  sf_susut_mode10($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){				
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode10($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode10($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		/*(global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$sk=$this->susut_koreksi($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode10($idbi, '$tglsusut' , '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
			$qry2 = mysql_query($aqry2);
			}						
		}		
				
		$cek .= 'tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/
	}
	
	function susut1barang_karawang($idbi, $tglsusut,  $jns_trans2, $susut_koreksi=FALSE){
		//mode =3
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$sk=$this->susut_koreksi_semester($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']>0?$sk['idk']:0;
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		if($jns_trans2==31){
			$tglsusutbulan=substr($tglsusut,5,2);
			if($tglsusutbulan=12){
				$tglsusuttahun=substr($tglsusut,0,4);
				$tglsusutbulan=6;
				$tglsusuttgl=30;							
			}else{
				$tglsusuttahun=substr($tglsusut,0,4)-1;
				$tglsusutbulan=12;
				$tglsusuttgl=31;				
			}
			$tglsusutkoreksi=$tglsusuttahun.'-'.$tglsusutbulan.'-'.$tglsusuttgl;//date('Y-m-d', strtotime('-1 year', strtotime($tglsusut)));
			$aqry = "select  sf_susut_mode11($idbi, '$tglsusutkoreksi' , '$UID', $jns_trans2, $idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));
			if($isi){					
				if($jns_trans2==31){
					$aqry2 = "call  sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
					$qry2 = mysql_query($aqry2);
				}
				if($susut_koreksi==FALSE){					
					$aqry3 = "select sf_susut_mode11($idbi, '$tglsusut' , '$UID', 30,$idk)  as cek;"; $cek .= $aqry3;
					$isi2 =mysql_fetch_array( mysql_query($aqry3));
				}						

			}				
		
		}else{
			$aqry = "select  sf_susut_mode11($idbi, '$tglsusut' , '$UID', $jns_trans2,$idk)  as cek;"; $cek .= $aqry;
			$isi =mysql_fetch_array( mysql_query($aqry));			
		}
		
		$cek .= 'tes='.$isi['cek'].$isi2['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
		/*global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];

		$bi = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id='$idbi'"
		));

		$sk=$this->susut_koreksi_bulan($idbi,$tglsusut,$susut_koreksi); $cek .= 'stop = '.$sk['stop'].' | jns_trans2 = '.$sk['jns_trans2'].' | '.$sk['cek'];
		$idk = $sk['idk']==0?0:$sk['idk'];
		$jns_trans2 = $sk['jns_trans2']==0?$jns_trans2:$sk['jns_trans2'];
		$aqry = "select  sf_susut_mode11($idbi, '$tglsusut' ,  '$UID', $jns_trans2)  as cek;"; $cek .= $aqry;			$isi =mysql_fetch_array( mysql_query($aqry));
		if($isi){				
			if($jns_trans2==31){
				$aqry2 = "call sp_jurnal_susut_koreksi($idbi,$idk)"; $cek .= $aqry2;
				$qry2 = mysql_query($aqry2);
			}						
		}
	
		$isi =mysql_fetch_array( mysql_query($aqry));
		
		
		//$cek .= 'cek='.$isi['cek'];
		$cek .= 'akum_susut = '.$qry_p['akum_susut'].' || tes='.$isi['cek'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);*/
	}
					

	function getRehab($idbi_awal, $tgl, $oper='<='){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$hrg_rehab = 0;
		$masa_rehab =0;
		
		
		
		$aqryplh = //pelihara
			"select sum(biaya_pemeliharaan) as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pemeliharaan ".
			"where tambah_aset=1 and idbi_awal='$idbi_awal' ".
			"and tgl_pemeliharaan $oper $tgl ; "; //$cek .= $aqryplh;
		$plh= mysql_fetch_array(mysql_query( $aqryplh ));
		$aqryplh = //pengaman
			"select sum(biaya_pengamanan)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from pengamanan ".
			"where tambah_aset=1 and idbi_awal='$idbi_awal' ".
			"and tgl_pengamanan $oper $tgl ; ";// $cek .= $aqryplh;
		$aman= mysql_fetch_array(mysql_query( $aqryplh ));
		$aqryplh = //hapus sebagian
			"select sum(harga_hapus)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penghapusan_sebagian ".
			" where  idbi_awal='".$bi['idawal']."' ".
			"and tgl_penghapusan $oper $tgl ; ";// $cek .= $aqryplh;
		$hps= mysql_fetch_array(mysql_query( $aqryplh ));
		$aqryplh = //koreksi 
			"select sum(harga_baru - harga)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from t_koreksi ".
			"where idbi_awal='$idbi_awal' ".
			"and tgl  $oper $tgl ; "; // $cek .= $aqryplh;
		$koreksi = mysql_fetch_array(mysql_query( $aqryplh ));
		$aqryplh = //penilaian 
			"select sum(nilai_barang - nilai_barang_asal)as tot, sum(ifnull(masa_manfaat,0)) as totmanfaat from penilaian ".
			"where idbi_awal='$idbi_awal' ".
			"and tgl_penilaian  $oper $tgl; ";  //$cek .= $aqryplh;
		$penilaian = mysql_fetch_array(mysql_query( $aqryplh ));
		
		$hrg_rehab = $plh['tot'] + $aman['tot'] - $hps['tot'] + $koreksi['tot']+$penilaian['tot'];						
		$masa_rehab = $plh['totmanfaat'] + $aman['totmanfaat'] - $hps['totmanfaat'] + $koreksi['totmanfaat']+$penilaian['totmanfaat'];
	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json, 'hrg_rehab'=>$hrg_rehab, 'masa_rehab'=>$masa_rehab);					
	} 
	
/*function susut1barang_jabar2($idbi, $thnsusutak, $blnsusutak, $tglSusut){
		//mode =3
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		//$idbi = 9426;
		if ($semsusut == 1) {
			$tglsusut = $thnsusutak.'-06-30';	
		}else{
			$tglsusut = $thnsusutak.'-12-31';
		}
		if($Main->VERSI_NAME=='PANDEGLANG'){
			$tglsusut = '2014-12-31';
		}
		$aqry = "select  sf_susut_mode3_thn($idbi, '$tglsusut' , '$UID')  as cek;"; $cek .= $aqry;
		$isi =mysql_fetch_array( mysql_query($aqry));
		
		
		$cek .= 'cek='.$isi['cek'];
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}*/
	
	
	function genQuery($mode = 1, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut, $blnsusut=6, $limit=50  ){
		/* mode=1:susut, 2=count */
		global $Main;
		$cek = ''; $err=''; $content='';
		$prog = $_REQUEST['prog'];
		$limit = 2;
		//kondisi ----------------------------
		/*$aqry = 
			"select * from buku_induk aa 
			join ref_barang bb on aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i and aa.j=bb.j
			where 
			( (aa.asal_usul=1 and aa.id=aa.idawal and (aa.thn_perolehan*12+month(aa.tgl_buku))<=$thnblnsusut ) 
			  or (aa.asal_usul=5 and (year(aa.tgl_buku)*12+MONTH(aa.tgl_buku))<=$thnblnsusut)
			)
			and ($thnblnsusut<=((aa.thn_susut_aw+aa.masa_manfaat)*12)+MONTH(aa.tgl_buku) or aa.thn_susut_aw=0) 
			and aa.kondisi<>3 
			and (aa.thn_susut_ak*12)+MONTH(aa.tgl_buku)<$thnblnsusut ";*/
			
		$arrKondisi = array();
		$Kondisi = '';
		$thnblnsusut = $thnsusut*12+$blnsusut;
		
		//skpd		
		$kdseksikos = genNumber(0, $Main->SUBUNIT_DIGIT); //$cek .='Main->SUBUNIT_DIGIT='.$Main->SUBUNIT_DIGIT.'-'.$kdseksikos;
		if($fmSKPD != '' && $fmSKPD != NULL && $fmSKPD !='00') $arrKondisi[] = " c='$fmSKPD' ";
		if($fmUNIT !='' && $fmUNIT != NULL && $fmUNIT !='00') $arrKondisi[] = " d='$fmUNIT' ";
		if($fmSUBUNIT !='' && $fmSUBUNIT != NULL && $fmSUBUNIT !='00') $arrKondisi[] = " e='$fmSUBUNIT' ";		
		if($fmSEKSI !='' && $fmSEKSI != NULL && $fmSEKSI !=$kdseksikos) $arrKondisi[] = " e1='$fmSEKSI' ";	
				
		if( $Main->TAHUN_MULAI_SUSUT =='')  $Main->TAHUN_MULAI_SUSUT=0;
		
		//$arrKondisi[] = 
			//"( (aa.asal_usul<>5 and aa.id=aa.idawal and (aa.thn_perolehan*12+month(aa.tgl_buku))<=$thnblnsusut ) ".			
			//" or (aa.asal_usul=5 and (year(aa.tgl_buku)*12+MONTH(aa.tgl_buku))<$thnblnsusut) )";						
		//$arrKondisi[] = 
		//	"( ( aa.asal_usul<>5 and aa.id=aa.idawal ) or (aa.asal_usul=5 )) and (aa.thn_perolehan*12+month(aa.tgl_buku))<=$thnblnsusut  ";						
		//$arrKondisi[] = " (aa.thn_perolehan*12+month(aa.tgl_buku))+1<=$thnblnsusut  ";	//sudah harus disusutkan
		//mode4 : thn2015 sem1 = and 4031 ( if(aa.thn_perolehan > 0 , aa.thn_perolehan, 0 )*2+ if(month(aa.tgl_buku)>6,2,1)  )<= 4031 					
		
		if($Main->VERSI_NAME =='BOGOR'){
			$arrKondisi[] = " aa.f in('02','03','04','07') ";		
		}else{
			$arrKondisi[] = " aa.f in('02','03','04','05','07') ";			
		}
		
		
		$arrKondisi[] = " bb.masa_manfaat >0  "; //sudah ada masa manfaat		
		#if($Main->VERSI_NAME == 'BDG_BARAT'){
		#	$arrKondisi[] = " (aa.status_barang = 1 or aa.status_barang=2 or status_barang=3) "; //utk awal saja /pemanfaatan
		#}else{
			$arrKondisi[] = " (aa.status_barang = 1 or aa.status_barang=2) "; //utk awal saja /pemanfaatan	
		#}
		
		
		if($Main->VERSI_NAME == 'SERANG'){
			$arrKondisi[] = " (aa.staset=3 or aa.staset=8 or staset=10)"; //aset tetap dan atb
		//}if($Main->VERSI_NAME ==''){
			
			//$arrKondisi[] = " aa.kondisi<>3  and (aa.staset=3 or aa.staset=8)"; //aset tetap dan atb	
		}else{
			$arrKondisi[] = " aa.kondisi<>3  and (aa.staset=3 or aa.staset=8)"; //aset tetap dan atb	
		}
		
		if($Main->VERSI_NAME == 'BDG_BARAT'){
			$arrKondisi[] = " aa.stop_susut=0 and aa.c<>'08' ";
		}
		
		if ($Main->SUSUT_MODE ==2){ //serang
			$arrKondisi[] = " aa.thn_susut_ak<$thnsusut "; //belum selesai disusutkan
			$arrKondisi[] = " if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<$thnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = "  ((aa.thn_susut_ak)< aa.thn_susut_aw+aa.masa_manfaat-1 or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " year(aa.tgl_buku) <=  $thnsusut ";	// periode tgl susut harus >= periode tgl buku
			$thnblnsusut = $thnsusut*2 +( $blnsusut>6? 2 :1);
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*2+if(month(aa.tgl_buku)>6,2,1)  )<=$thnblnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = " ( (aa.thn_susut_ak*2)+if(ifnull(aa.bln_susut_ak,0) > 6, 2,1)<((aa.thn_susut_aw+aa.masa_manfaat-1)*2)+if(ifnull(aa.bln_susut_aw,0)>6,2,1) or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak*2)+ if(ifnull(aa.bln_susut_ak,0)>6,2,1) < $thnblnsusut "; //belum disusutkan pd tgl susut ini
			$arrKondisi[] = " year(aa.tgl_buku)*2+if(month(aa.tgl_buku)>6,2,1 ) <=  $thnblnsusut ";	// periode tgl susut harus >= periode tgl buku, tambahkan ke mode yg lain
		}elseif($Main->SUSUT_MODE==3){
			//sudah harus disusutkan							
			$arrKondisi[] = 
				" ( ".
				" ( aa.thn_perolehan<=2014 and  if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  ) or ".
				" ( aa.thn_perolehan>2014 and ".
				" ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*12+month( if(aa.tgl_ba is not null and aa.tgl_ba <> '0000-00-00', aa.tgl_ba, aa.tgl_buku) ))<=$thnblnsusut  ) ".
				" )";	
			
			//masih dalam masa manfaat
			$arrKondisi[] = 
				" ( ".
				" ( aa.thn_perolehan <=2014 and aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1 ) or ".
				" ( aa.thn_perolehan>2014 and (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0)<((aa.thn_susut_aw+aa.masa_manfaat)*12)+ifnull(aa.bln_susut_aw,0) ) or ".
				" aa.thn_susut_aw=0 )"; 
			//belum disusutkan pd tgl susut ini		
			$arrKondisi[] = 
				" ( (aa.thn_perolehan<=2014 and aa.thn_susut_ak < $thnsusut ) or ".
				" ( aa.thn_perolehan>2014 and (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0)<$thnblnsusut ) )"; 
			
		}
		/*elseif($Main->SUSUT_MODE==3){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini		
		}elseif($Main->SUSUT_MODE==3 && ($thnsusut<=2014 ) ){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini		
		}*/elseif($Main->SUSUT_MODE==6){// && $Main->VERSI_NAME=='TASIKMALAYA_KAB'){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_perolehan + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini
			$arrKondisi[] = " year(aa.tgl_buku) <=  $thnsusut "; // periode tgl susut harus >= periode tgl buku		
		}elseif($Main->SUSUT_MODE==7 && $Main->VERSI_NAME=='PANDEGLANG'){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini	
			$arrKondisi[] = " year(aa.tgl_buku) <=  $thnsusut "; // periode tgl susut harus >= periode tgl buku	
		}elseif($Main->SUSUT_MODE==8 && $Main->VERSI_NAME=='CIREBON'){
			$thnblnsusut = $thnsusut*2 +( $blnsusut>6? 2 :1);
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*2+if(month(aa.tgl_buku)>6,2,1)  )<=$thnblnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = " ( (aa.thn_susut_ak*2)+if(ifnull(aa.bln_susut_ak,0) > 6, 2,1)<((aa.thn_susut_aw+aa.masa_manfaat-1)*2)+if(ifnull(aa.bln_susut_aw,0)>6,2,1) or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak*2)+ if(ifnull(aa.bln_susut_ak,0)>6,2,1) < $thnblnsusut "; //belum disusutkan pd tgl susut ini		
		}elseif($Main->SUSUT_MODE==9 && $Main->VERSI_NAME=='BDG_BARAT'){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini 
			$arrKondisi[] = " year(aa.tgl_buku) <=  $thnsusut "; // periode tgl susut harus >= periode tgl buku		
		}elseif($Main->SUSUT_MODE==10 && $Main->VERSI_NAME=='BOGOR'){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )<=$thnsusut  )";	//sudah harus disusutkan					
			$arrKondisi[] = " ( aa.thn_susut_ak < aa.thn_susut_aw + aa.masa_manfaat-1  or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak < $thnsusut )"; //belum disusutkan pd tgl susut ini		
		}/*elseif($Main->SUSUT_MODE==11 && $Main->VERSI_NAME=='KARAWANG'){
			$thnblnsusut = $thnsusut*2 +( $blnsusut>6? 2 :1);
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*2+if(month(aa.tgl_buku)>6,2,1)  )<=$thnblnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = " ( (aa.thn_susut_ak*2)+if(ifnull(aa.bln_susut_ak,0) > 6, 2,1)<((aa.thn_susut_aw+aa.masa_manfaat-1)*2)+if(ifnull(aa.bln_susut_aw,0)>6,2,1) or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat
			$arrKondisi[] = " (aa.thn_susut_ak*2)+ if(ifnull(aa.bln_susut_ak,0)>6,2,1)< $thnblnsusut "; //belum disusutkan pd tgl susut ini		
		}*/elseif($Main->SUSUT_MODE==11 && $Main->VERSI_NAME=='KARAWANG'){
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*12+month(aa.tgl_buku)) <= $thnblnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = " ( (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0) < (((aa.thn_susut_aw+aa.masa_manfaat)*12)-1)+ifnull(aa.bln_susut_aw,0) or aa.thn_susut_aw=0 ) "; //masih dalam masa manfaat	
			$arrKondisi[] = " (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0) < $thnblnsusut "; //belum selesai disusutkan
		}else{
			$arrKondisi[] = " ( if(aa.thn_perolehan > $Main->TAHUN_MULAI_SUSUT , aa.thn_perolehan, $Main->TAHUN_MULAI_SUSUT )*12+month(aa.tgl_buku))+1<=$thnblnsusut  ";	//sudah harus disusutkan					
			$arrKondisi[] = " ( (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0)+1<((aa.thn_susut_aw+aa.masa_manfaat)*12)+ifnull(aa.bln_susut_aw,0) or aa.thn_susut_aw=0 )"; //masih dalam masa manfaat	
			$arrKondisi[] = " (aa.thn_susut_ak*12)+ifnull(aa.bln_susut_ak,0)<$thnblnsusut "; //belum selesai disusutkan
			
		}
		
		
		
		//$arrKondisi[] = " aa.thn_susut_ak < $thnsusut  "; //tahun sust terakhir < dari tahun susut
		//$arrKondisi[] = " ( ( aa.thn_susut_aw + aa.masa_manfaat -1 > aa.thn_susut_ak) or (aa.thn_susut_aw=0) )  "; //belum sama sekali atau belum habis masa manfaat
		//$arrKondisi[] = " aa.status_barang=1 and aa.staset=3 and aa.kondisi<>3 "; //brg yg disusutka brg inventaris intra
		//$arrKondisi[] = " aa.thn_perolehan < $thnsusut "; //brg dgn thn perolehan < thn susut
				
		$Kondisi = join(' and ',$arrKondisi); //$cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		
		//mode ------------------------------
		switch($mode){
			case 1:
				$vselect = " select * ";
				$vLimit = " limit 0,$limit;"; //" limit $prog,$limit;";
			break;
			case 2:
				$vselect = " select count(*) as cnt ";
			break;
		}
		
		//ambil dari buku induk dimana belum disusutkan s/d tahun sekarang		
		$aqry = $vselect. 
			" from buku_induk aa join ref_barang bb on aa.f= bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i and aa.j = bb.j ".
			$Kondisi.
			$vLimit;
			$cek .= ' '.$aqry;
				
		return	array ('cek'=>htmlspecialchars($cek), 'err'=>$err, 'content'=>$aqry); 
	}
		
	function susutSemester($thnsusut=2014,$blnsusut=12,$tglsusut='2014-12-31',$semsusut=2,$jns_trans2=30){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		
		$fmSKPD = $_REQUEST['c'];
		$fmUNIT = $_REQUEST['d'];
		$fmSUBUNIT = $_REQUEST['e'];		
		$fmSEKSI = $_REQUEST['e1'];
		
		$get = $this->genQuery(1, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut, $blnsusut);
		$cek .= $get['cek'];
		$qry = mysql_query($get['content']);
		$jml = mysql_num_rows($qry); //$cek->jml = 'tes';		
		$cek .= ' susut mode='.$Main->SUSUT_MODE;
		while($isi=mysql_fetch_array($qry)){
			$cek .= ' id= '.$isi['id'];
			if($Main->SUSUT_MODE==5){
				//$sem_ = $fmSemester ==1?2 : 1;
				$susut = $this->susut1barang_mode5($isi['id'], $thnsusut, $sem_ );
			}else if($Main->SUSUT_MODE==4){
				
				$sem_ = $fmSemester ==1?2 : 1;
				$susut = $this->susut1barang_garut($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==3){//
				$susut = $this->susut1barang_jabar2($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==2){//'SERANG' -> dihitung tahun disimpan semester dibagi 2, tgl susut otomatis
				$susut = $this->susut1barang_serang($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==1){//JABAR -> dihitung bulan, disimpan bulan, tgl susut otomatis
				$susut = $this->susut1barang_jabar($isi['id'], $thnsusut, $blnsusut, $tglsusut);	
			}else if($Main->SUSUT_MODE==6){//TASIK -> dihitung tahun, disimpan tahun, tgl susut sesuai neraca awal
				$susut = $this->susut1barang_tasik($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==7){//PANDEGLANG -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_pandeglang($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==8){//CIREBON -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_cirebon($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==9){//BANDUNG BARAT -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_bdgbrt($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==10){//BOGOR -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_bogor($isi['id'], $tglsusut, $jns_trans2);
			}else if($Main->SUSUT_MODE==11){//BOGOR -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_karawang($isi['id'], $tglsusut, $jns_trans2);
			}else{ //disimpan semester, tampil digenerate bulanan
				$susut = $this->susut1barang($isi['id'], $thnsusut, $blnsusut, $tglsusut);	
			}
			
			$cek .= $susut['cek'];
		}
		$content->jml = $jml;		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function susutSatu($idbi=0,$thnsusut=2014,$blnsusut=12,$tglsusut='2014-12-31',$semsusut=2,$jns_trans2=30){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$cek .= '$Main->SUSUT_MODE='.$Main->SUSUT_MODE;
		if($Main->SUSUT_MODE==5){
			#$sem_ = $fmSemester ==1?2 : 1;
			$susut = $this->susut1barang_mode5($idbi, $thnsusut, $semsusut );
		}
		else if($Main->SUSUT_MODE==4){//'GARUT' -> dhitung semester, disimpan semesteran
			$sem_ = $fmSemester ==1?2 : 1;
			$susut = $this->susut1barang_garut($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==3){//
			$susut = $this->susut1barang_jabar2($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==2){//'SERANG' -> dihitung tahun disimpan semester dibagi 2, tgl susut otomatis
			$susut = $this->susut1barang_serang($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==1){//JABAR -> dihitung bulan, disimpan bulan, tgl susut otomatis
			$susut = $this->susut1barang_jabar($idbi, $thnsusut, $blnsusut, $tglsusut);	
		}
		else if($Main->SUSUT_MODE==6){//TASIK -> dihitung tahun, disimpan tahun, tgl susut sesuai neraca awal
			$susut = $this->susut1barang_tasik($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==7){//PANDEGLANG -> dihitung tahun, disimpan tahun, tgl susut otomatis
			$susut = $this->susut1barang_pandeglang($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==8){//CIREBON -> dihitung semester, disimpan semester, tgl susut otomatis
			$susut = $this->susut1barang_cirebon($idbi, $tglsusut, $jns_trans2);
		}else if($Main->SUSUT_MODE==9){//Bandung Barat -> dihitung semester, disimpan semester, tgl susut otomatis
			$susut = $this->susut1barang_bdgbrt($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==10){//Bogor -> dihitung tahun, disimpan tahuan, tgl susut otomatis
			$susut = $this->susut1barang_bogor($idbi, $tglsusut, $jns_trans2);
		}
		else if($Main->SUSUT_MODE==11){//Bogor -> dihitung tahun, disimpan tahuan, tgl susut otomatis
			$susut = $this->susut1barang_karawang($idbi, $tglsusut, $jns_trans2);
		}		
		else{ //disimpan semester, tampil digenerate bulanan
			$susut = $this->susut1barang($idbi, $thnsusut, $blnsusut, $tglsusut);	
		}		
			$cek .= $tglsusut;
			$cek .= $susut['cek'];
		//}
		
		$content->jml = $jml;		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function susutKoreksi($idbi=0,$thnsusut=2014,$blnsusut=12,$tglsusut='2014-12-31',$semsusut=2,$jns_trans2=30){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		
		$fmSKPD = $_REQUEST['c'];
		$fmUNIT = $_REQUEST['d'];
		$fmSUBUNIT = $_REQUEST['e'];		
		$fmSEKSI = $_REQUEST['e1'];
		
		$tglclosing = getTglClosing($fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI); $cek .= $tglclosing;
		if($tglsusut<$tglclosing){
			$err="Tanggal Koreksi harus lebih besar dari Tanggal closing!";
		}else{		
			$cek .= '$Main->SUSUT_MODE='.$Main->SUSUT_MODE;
			if($Main->SUSUT_MODE==5){
				#$sem_ = $fmSemester ==1?2 : 1;
				$susut = $this->susut1barang_mode5($idbi, $thnsusut, $semsusut );
			}
			else if($Main->SUSUT_MODE==4){
				$sem_ = $fmSemester ==1?2 : 1;
				$susut = $this->susut1barang_garut($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==3){//
				$susut = $this->susut1barang_jabar2($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==2){//'SERANG' -> dihitung tahun disimpan semester dibagi 2, tgl susut otomatis
				$susut = $this->susut1barang_serang($idbi, $thnsusut, $blnsusut, $tglsusut, TRUE);
			}
			else if($Main->SUSUT_MODE==1){//JABAR -> dihitung bulan, disimpan bulan, tgl susut otomatis
				$susut = $this->susut1barang_jabar($idbi, $thnsusut, $blnsusut, $tglsusut, TRUE);	
			}
			else if($Main->SUSUT_MODE==6){//TASIK -> dihitung tahun, disimpan tahun, tgl susut sesuai neraca awal
				$susut = $this->susut1barang_tasik($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==7){//PANDEGLANG -> dihitung tahun, disimpan tahun, tgl susut otomatis
				$susut = $this->susut1barang_pandeglang($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==8){//CIREBON -> dihitung semester, disimpan semester, tgl susut otomatis
				$susut = $this->susut1barang_cirebon($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==9){//Bandung Barat -> dihitung semester, disimpan semester, tgl susut otomatis
				$susut = $this->susut1barang_bdgbrt($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==10){//Bogor -> dihitung tahun, disimpan tahuan, tgl susut otomatis
				$susut = $this->susut1barang_bogor($idbi, $tglsusut, $jns_trans2, TRUE);
			}
			else if($Main->SUSUT_MODE==11){//Bogor -> dihitung tahun, disimpan tahuan, tgl susut otomatis
				$susut = $this->susut1barang_karawang($idbi, $tglsusut, $jns_trans2, TRUE);
			}	else{ //disimpan semester, tampil digenerate bulanan
					$susut = $this->susut1barang($idbi, $thnsusut, $blnsusut, $tglsusut, TRUE);	
				}		
		}
			//}
			
			$cek .= $susut['cek'];
		//}
		
		$content->jml = $jml;		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	
	function genFormSusut(){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json = TRUE;	
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 500;
		$this->form_height = 180;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Penyusutan Baru';
			$nip = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
					
		$arrKondisi = array();
		$Kondisi = '';
		$thnskr=$HTTP_COOKIE_VARS['coThnAnggaran'];//date('Y');//2016
		//$fmSemester = '2014';
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		$arrthn = array(
			array($thnskr-3, $thnskr-3),
			array($thnskr-2, $thnskr-2),
			array($thnskr-1, $thnskr-1),
			//array($thnskr, $thnskr)
		);

		$tglclosing = getTglClosing($fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI); $cek .= 'susut mode='.$Main->SUSUT_MODE.$Main->VERSI_NAME;
		if($tglclosing=='' || $tglclosing==0 || $tglclosing==NULL || $Main->SUSUT_MODE==3 || $Main->SUSUT_MODE==10 || $Main->SUSUT_MODE==11 || $Main->SUSUT_MODE==8){ //sementara
			//$thnsusut = $thnskr-1;				
			$vthnsusut = cmbArray('thnsusut', $thnsusut, $arrthn, 'PILIH'," onchange='".$this->Prefix.".changeTahun()' "  );
		}else{
			if($Main->SUSUT_MODE==6){
				$thnsusut = $thnskr-1;//substr($tglclosing,0,4)+1; $cek .= $thnsusut;				
			}else{
				$thnsusut = substr($tglclosing,0,4)+1; $cek .= $thnsusut;			
			}
			$vthnsusut = "<input type='text' id='thnsusut' name='thnsusut' value='$thnsusut' readonly>";
		}
		//$blnsusut=6;		
		$Semester = cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester,'','Semester I'," onchange='".$this->Prefix.".changeTahun()' " );

		$cek .= " thnsusut=$thnsusut blnsusut=$blnsusut ";
					
		//if($Main->VERSI_NAME == 'SERANG') {
			$aqry = $this->genQuery(2, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut,$blnsusut);
		//}else{
		//	$aqry = $this->genQuery(2, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut);
		//}
		
		$cek .= $aqry['content'];
		$get = mysql_fetch_array(mysql_query($aqry['content']));
		$jmldata = $get['cnt'];
		
		//bidang		
		if($fmSKPD != '' && $fmSKPD != NULL && $fmSKPD !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='00' "));
			$bidang = $get['nm_skpd'];	
		}
		if($fmUNIT !='' && $fmUNIT != NULL && $fmUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='00' "));
			$unit = $get['nm_skpd'];
		}
		if($fmSUBUNIT !='' && $fmSUBUNIT != NULL && $fmSUBUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='000' "));
			$subunit = $get['nm_skpd'];
		}
		if($fmSEKSI !='' && $fmSEKSI != NULL && $fmSEKSI !='000') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='$fmSEKSI' "));
			$seksi = $get['nm_skpd'];
		}
			
		//progress
		$progress = 
			"<div id='progressbox' style='display:none;'>".
			"<div id='progressbck' style='display:block;width:300px;height:4px;background-color:silver; margin: 6 5 0 0;float:left;border-radius: 3px;'>".
			"<div id='progressbar' style='height:2px;margin:1;width:0%;border-radius: 3px;background-color: green;'></div>".
			"</div>".
			"<div id ='progressmsg' name='progressmsg' style='float:left;'></div>".
			"</div>".
			"<input type=hidden id='jmldata' name='jmldata' value='".$jmldata."'> ".
			"<input type=hidden id='prog' name='prog' value='0'> ";
		
		if($Main->SUSUT_MODE==2 || $Main->SUSUT_MODE==5 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || $Main->SUSUT_MODE==9 || $Main->SUSUT_MODE==10){
			$vtahun_ =  $vthnsusut."<input type='hidden' id='fmSemester' name='fmSemester' value='1'>";
		}/*elseif($Main->SUSUT_MODE == 3 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7){		
			$vtahun_ = "<input type='text' id='thnsusut' name='thnsusut' value='$thnsusut' readonly>";
		}*/else{
			$vtahun_ =  $Semester.' '.$vthnsusut;	
		}
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUBUNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			//'tgl' => array(  'label'=>'Tanggal Penyusutan', 'value'=> Date('d-m-Y'), 'type'=>'' ),	
			'tahun' => array(  'label'=>'Tahun Laporan s/d ', 'value'=> $vtahun_, 'type'=>'' ),			
			'jml_data' => array( 'label'=>'Belum Penyusutan', 'value'=> "<span id='vjmldata' >". number_format( $jmldata ,0,',','.') ."</span> data", 'type'=>'' ),			
			'progress' => array( 'label'=>'', 'value'=> $progress, 'type'=>'merge' ),			
			//'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			//'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			//'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
			
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$fmSKPD."'> ".
			"<input type=hidden id='d' name='d' value='".$fmUNIT."'> ".
			"<input type=hidden id='e' name='e' value='".$fmSUBUNIT."'> ".
			"<input type=hidden id='e' name='e1' value='".$fmSEKSI."'> ".
			
			
			"<input type='button' id='btproses' value='Proses' onclick ='".$this->Prefix.".susut()' >&nbsp;".
			"<input type='button' id='btbatal' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genFormSusutSatu(){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json = TRUE;	
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 400;
		$this->form_height = 100;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Penyusutan Baru';
			$nip = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
					
		$arrKondisi = array();
		$Kondisi = '';
		$thnskr=$HTTP_COOKIE_VARS['coThnAnggaran'];//date('Y');//2016
		$cidbi = $_REQUEST['cidBI'];
		
		$arrthn = array(
			array($thnskr-3, $thnskr-3),		
			array($thnskr-2, $thnskr-2),
			array($thnskr-1, $thnskr-1),
			//array($thnskr, $thnskr),
			//array($thnskr+1, $thnskr+1)
		);	

		$bi = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id='$idbi'"
		));

		//get tgl closing
		$aqry = "select * from buku_induk where id = '".$cidbi[0]."' ;"; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));			
		$tglclosing = getTglClosing($bi['c'],$bi['d'],$bi['e'],$bi['e1']); $cek .= $tglclosing;
		if($tglclosing=='' || $tglclosing==0 || $tglclosing==NULL || $Main->SUSUT_MODE==3 || $Main->SUSUT_MODE==10 || $Main->SUSUT_MODE==11 || $Main->SUSUT_MODE==8){ //sementara
			$thnsusut = $thnskr-1;				
			$vthnsusut = cmbArray('thnsusut', $thnsusut, $arrthn, 'PILIH',"  "  );
		}else{
			if($Main->SUSUT_MODE==6){
				$thnsusut = $thnskr-1;//substr($tglclosing,0,4)+1; $cek .= $thnsusut;				
			}else{
				$thnsusut = substr($tglclosing,0,4)+1; $cek .= $thnsusut;			
			}
			$vthnsusut = "<input type='text' id='thnsusut' name='thnsusut' value='$thnsusut' readonly>";
		}
		$Semester = cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester,'','Semester I',"  " );		
		
		//if($Main->SUSUT_MODE==2 || ($Main->SUSUT_MODE==3 && $bi['thn_perolehan'] <=2014) ||$Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || ($Main->SUSUT_MODE==8 && $bi['thn_perolehan'] <=2014)){
		if($Main->SUSUT_MODE==2 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || $Main->SUSUT_MODE==9 || $Main->SUSUT_MODE==10){
			//if ($Main->VERSI_NAME == 'SERANG' || $Main->VERSI_NAME == 'PANDEGLANG') {
			//	$thnsusut = '2014';
			//	$vthnsusut_ = $thnsusut."<input type='hidden' id='thnsusut' name='thnsusut' value='$thnsusut'>" ;
			//}else{
				$vthnsusut_ = $vthnsusut."<input type='hidden' id='fmSemester' name='fmSemester' value='1'>";
			//}
		}else{
			$vthnsusut_ = $Semester.'&nbsp;&nbsp;'.$vthnsusut;
		}
		
		
				
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		
		
		
		//$aqry = $this->genQuery(2, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut);
		//$cek .= $aqry['cek'];
		//$get = mysql_fetch_array(mysql_query($aqry['content']));
		$jmldata = 1;// $get['cnt'];
		
		//bidang		
		if($fmSKPD != '' && $fmSKPD != NULL && $fmSKPD !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='00' "));
			$bidang = $get['nm_skpd'];	
		}
		if($fmUNIT !='' && $fmUNIT != NULL && $fmUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='00' "));
			$unit = $get['nm_skpd'];
		}
		if($fmSUBUNIT !='' && $fmSUBUNIT != NULL && $fmSUBUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='000' "));
			$subunit = $get['nm_skpd'];
		}
		if($fmSEKSI !='' && $fmSEKSI != NULL && $fmSEKSI !='000') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='$fmSEKSI' "));
			$seksi = $get['nm_skpd'];
		}
			
		//progress
		$progress = 
			"<div id='progressbox' style='display:none;'>".
			"<div id='progressbck' style='display:block;width:300px;height:4px;background-color:silver; margin: 6 5 0 0;float:left;border-radius: 3px;'>".
			"<div id='progressbar' style='height:2px;margin:1;width:0%;border-radius: 3px;background-color: green;'></div>".
			"</div>".
			"<div id ='progressmsg' name='progressmsg' style='float:left;'></div>".
			"</div>".
			"<input type=hidden id='jmldata' name='jmldata' value='".$jmldata."'> ".
			"<input type=hidden id='idbi' name='idbi' value='".$cidbi[0]."'> ".
			"<input type=hidden id='prog' name='prog' value='0'> ";
		
		
		$this->form_fields = array(				
			//'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			//'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			//'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			//'seksi' => array(  'label'=>'SUBUNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			//'tgl' => array(  'label'=>'Tanggal Penyusutan', 'value'=> Date('Y-m-d'), 'type'=>'date' ),	
			'tahun' => array(  'label'=>'Tahun Laporan s/d ', 'value'=>$vthnsusut_, 'type'=>'' ),			
			'jml_data' => array( 'label'=>'Belum Penyusutan', 'value'=> "<span id='vjmldata' >". number_format( $jmldata ,0,',','.') ."</span> data", 'type'=>'' ),			
			'progress' => array( 'label'=>'', 'value'=> $progress, 'type'=>'merge' ),			
			//'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			//'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			//'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$fmSKPD."'> ".
			"<input type=hidden id='d' name='d' value='".$fmUNIT."'> ".
			"<input type=hidden id='e' name='e' value='".$fmSUBUNIT."'> ".
			"<input type=hidden id='e' name='e1' value='".$fmSEKSI."'> ".
			
			
			"<input type='button' id='btproses' value='Proses' onclick ='".$this->Prefix.".susutSatu()' >&nbsp;".
			"<input type='button' id='btbatal' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genFormKoreksi(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json = TRUE;	
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 400;
		$this->form_height = 100;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Penyusutan Koreksi';
			$nip = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
					
		$arrKondisi = array();
		$Kondisi = '';
		$thnskr=date('Y');//2016
		$cidbi = $_REQUEST['cidBI'];
		$tgl_koreksi=date('Y-m-d');
		$arrthn = array(
			array($thnskr-2, $thnskr-2),
			array($thnskr-1, $thnskr-1),
			array($thnskr, $thnskr)
		);	

		$bi = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id='$idbi'"
		));

		//get tgl closing
		$aqry = "select * from buku_induk where id = '".$cidbi[0]."' ;"; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));			
		$tglclosing = getTglClosing($bi['c'],$bi['d'],$bi['e'],$bi['e1']); $cek .= $tglclosing;
		if($tglclosing=='' || $tglclosing==0 || $tglclosing==NULL || $Main->SUSUT_MODE==3){
			$thnsusut = $thnskr-1;				
			$vthnsusut = cmbArray('thnsusut', $thnsusut, $arrthn, 'PILIH',"  "  );
		}else{
			$thnsusut = substr($tglclosing,0,4)+1; $cek .= $thnsusut;
			$vthnsusut = "<input type='text' id='thnsusut' name='thnsusut' value='$thnsusut' readonly>";
		}
		$Semester = cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester,'','Semester I',"  " );		
		
		if($Main->SUSUT_MODE==2 || ($Main->SUSUT_MODE==3 && $bi['thn_perolehan'] <=2014) ||$Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || ($Main->SUSUT_MODE==8 && $bi['thn_perolehan'] <=2014)){
			//if ($Main->VERSI_NAME == 'SERANG' || $Main->VERSI_NAME == 'PANDEGLANG') {
			//	$thnsusut = '2014';
			//	$vthnsusut_ = $thnsusut."<input type='hidden' id='thnsusut' name='thnsusut' value='$thnsusut'>" ;
			//}else{
				$vthnsusut_ = $vthnsusut."<input type='hidden' id='fmSemester' name='fmSemester' value='1'>";
			//}
		}/*elseif($Main->SUSUT_MODE==3 ||$Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7){
			$aqry = "select * from buku_induk where id = '".$cidbi[0]."' ;"; $cek .= $aqry;
			$bi = mysql_fetch_array(mysql_query($aqry));			
			$tglclosing = getTglClosing($bi['c'],$bi['d']);
			$thnsusut = substr($tglclosing,0,4)+1; $cek .= $thnsusut;
			$vthnsusut_ = "<input type='text' id='thnsusut' name='thnsusut' value='$thnsusut' readonly>";
		}*/else{
			$vthnsusut_ = $Semester.'&nbsp;&nbsp;'.$vthnsusut;
		}
		
		
				
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		
		
		
		//$aqry = $this->genQuery(2, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut);
		//$cek .= $aqry['cek'];
		//$get = mysql_fetch_array(mysql_query($aqry['content']));
		$jmldata = 1;// $get['cnt'];
		
		//bidang		
		if($fmSKPD != '' && $fmSKPD != NULL && $fmSKPD !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='00' "));
			$bidang = $get['nm_skpd'];	
		}
		if($fmUNIT !='' && $fmUNIT != NULL && $fmUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='00' "));
			$unit = $get['nm_skpd'];
		}
		if($fmSUBUNIT !='' && $fmSUBUNIT != NULL && $fmSUBUNIT !='00') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='000' "));
			$subunit = $get['nm_skpd'];
		}
		if($fmSEKSI !='' && $fmSEKSI != NULL && $fmSEKSI !='000') {
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='$fmSEKSI' "));
			$seksi = $get['nm_skpd'];
		}
			
		//progress
		$progress = 
			"<div id='progressbox' style='display:none;'>".
			"<div id='progressbck' style='display:block;width:300px;height:4px;background-color:silver; margin: 6 5 0 0;float:left;border-radius: 3px;'>".
			"<div id='progressbar' style='height:2px;margin:1;width:0%;border-radius: 3px;background-color: green;'></div>".
			"</div>".
			"<div id ='progressmsg' name='progressmsg' style='float:left;'></div>".
			"</div>".
			"<input type=hidden id='jmldata' name='jmldata' value='".$jmldata."'> ".
			"<input type=hidden id='idbi' name='idbi' value='".$cidbi[0]."'> ".
			"<input type=hidden id='prog' name='prog' value='0'> ";
		
		
		$this->form_fields = array(				
			//'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			//'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			//'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			//'seksi' => array(  'label'=>'SUBUNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			//'tgl' => array(  'label'=>'Tanggal Penyusutan', 'value'=> Date('Y-m-d'), 'type'=>'date' ),	
			'tgl' => array(  'label'=>'Tanggal Koreksi ', 'value'=>createEntryTgl3($tgl_koreksi,'tgl_koreksi','',''), 'type'=>'' ),			
			//'jml_data' => array( 'label'=>'Belum Penyusutan', 'value'=> "<span id='vjmldata' >". number_format( $jmldata ,0,',','.') ."</span> data", 'type'=>'' ),			
			'progress' => array( 'label'=>'', 'value'=> $progress, 'type'=>'merge' ),			
			//'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			//'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			//'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$fmSKPD."'> ".
			"<input type=hidden id='d' name='d' value='".$fmUNIT."'> ".
			"<input type=hidden id='e' name='e' value='".$fmSUBUNIT."'> ".
			"<input type=hidden id='e' name='e1' value='".$fmSEKSI."'> ".
			
			
			"<input type='button' id='btproses' value='Proses' onclick ='".$this->Prefix.".susutKoreksi()' >&nbsp;".
			"<input type='button' id='btbatal' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}

	
	function formRincian(){
		$cek = ''; $err=''; $content=''; $json = TRUE;	
		
		$cidBI= $_REQUEST['cidBI'];
		$this->form_idplh = $cidBI[0];
		
		//$form_name = $this->Prefix.'_form';				
		$this->form_width = 400;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Rincian Penyusutan Barang';
			$nip	 = '';
		}else{
			$this->form_caption = '';			
			$nip = $dt['nip'];			
		}		
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id ='$this->form_idplh' "));		
		$id_lama = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$bi['id_lama']."' "));
		$bi_awal = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$bi['idawal']."' "));
		$penyusutan = mysql_fetch_array(mysql_query("select count(*) into cek_idlama_ from penyusutan where idbi ='".$bi['id_lama']." "));
		
		if($bi['id'] <> $bi['idawal']){
			if($bi['id_lama'] != NULL){
				if($penyusutan['cek_idlama_']>0){
					$tgl_buku= TglInd($id_lama['tgl_buku']);	
				}else{
					if($id_lama['f']==06 && ($bi['f']== '02' || $bi['f']== '03' || $bi['f'] == '04' || $bi['f'] == '05')){
						$tgl_buku= TglInd($bi['tgl_buku']);
					}else{
						$tgl_buku= TglInd($id_lama['tgl_buku']);
					}
				}
			}else{
				$tgl_buku= TglInd($bi_awal['tgl_buku']);				
			}
		}else{
			$tgl_buku=TglInd($bi['tgl_buku']);
		}
		//$tgl_buku=$bi['id'] <> $bi['idawal'] ? TglInd($bi_awal['tgl_buku']) : TglInd($bi['tgl_buku']);
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$bi['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];	
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$bi['c']."' and d='".$bi['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$bi['c']."' and d='".$bi['d']."' and e='".$bi['e']."' and e1='000' "));
		$subunit = $get['nm_skpd']; 
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$bi['c']."' and d='".$bi['d']."' and e='".$bi['e']."' and e1='".$bi['e1']."' "));
		$seksi = $get['nm_skpd']; 
		$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];		
		$get= mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'"));
		$nmbrg = $get['nm_barang'];
		
		//hrg rehab
		$hrgrehab = 0;
		$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where tambah_aset=1 and idbi_awal='".$bi['idawal']."' "));
		$hrgrehab += $get['tot'];
		$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where tambah_aset=1 and idbi_awal='".$bi['idawal']."' "));
		$hrgrehab += $get['tot'];
		$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where  idbi_awal='".$bi['idawal']."' "));
		$hrgrehab -= $get['tot'];
		$get = mysql_fetch_array(mysql_query("select sum(harga_baru-harga) as tot from t_koreksi where  idbi_awal='".$bi['idawal']."' "));
		$hrgrehab += $get['tot'];
		$get = mysql_fetch_array(mysql_query("select sum(nilai_barang-nilai_barang_asal) as tot from penilaian where  idbi_awal='".$bi['idawal']."' "));
		$hrgrehab += $get['tot'];
		
		$hrg_perolehan = $bi['harga'];// + $hrgrehab;
		$this->form_fields = array(				
			'bidang' 	=> array( 'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>150, 'type'=>'' ),
			'unit' 		=> array( 'label'=>'SKPD', 'value'=> $unit,  'type'=>'' ),
			'subunit' 	=> array( 'label'=>'UNIT', 'value'=> $subunit,  'type'=>'' ),
			'seksi' 	=> array( 'label'=>'SUBUNIT', 'value'=> $seksi,  'type'=>'' ),			
			'kdbarang' 	=> array( 'label'=>'Kode / Nama Barang', 'value'=> $kdbrg.' / '.$nmbrg, 'type'=>'' ),						
			'noreg' 	=> array( 'label'=>'No. Reg/ Tahun/ Tanggal Buku', 'value'=> $bi['noreg'].' / '.$bi['thn_perolehan'].' / '.$tgl_buku, 'type'=>'' ),												
			'hrg'		=> array( 'label'=>'Harga Perolehan Rp.', 'value'=> number_format( $bi['harga'], 2, ',', '.' ), 'type'=>'' ),		
			'hrgrehab' 	=> array( 'label'=>'Harga Rehabilitasi Rp.', 'value'=> number_format( $hrgrehab, 2, ',', '.' ), 'type'=>'' ),
			'det'		=> array( 
				'label'	=>'Harga Rehabilitasi Rp.', 
				'value'	=>
					"<div id='div_detail' style='height:5px'>". 
					"<input type='hidden' id='susut_mode' value='$Main->SUSUT_MODE' >".
					"<input type='hidden' id='hrg_perolehan' name='hrg_perolehan' value='$hrg_perolehan' >".
					"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>".
						"<input type='hidden' id='idbi' name='idbi' value='".$bi['id']."'></div>".
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' ></div>".
					"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
						"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
					"</div>".
					"</div>", 
				'type'	=>'merge' 
			),		
		);
		
		$this->form_menubawah =
			"<input type=hidden id='susutmode' name='susutmode' value='".$Main->SUSUT_MODE."'> ".
			"<input type=hidden id='c' name='c' value='".$fmSKPD."'> ".
			"<input type=hidden id='d' name='d' value='".$fmUNIT."'> ".
			"<input type=hidden id='e' name='e' value='".$fmSUBUNIT."'> ".
			"<input type=hidden id='e' name='e1' value='".$fmSEKSI."'> ".
			//"<input type='button' id='btproses' value='Proses' onclick ='".$this->Prefix.".susut()' >".
			"<input type='button' id='btbatal' value='Tutup' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm(TRUE, '', FALSE, TRUE);		
				
		$content = $form;//$content = 'content';
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function changeTahun(){
		$cek = ''; $err=''; $content=''; $json = TRUE;	
		//$content = 10;
		$arrKondisi = array();
		$Kondisi = '';
		
		$thnsusut =  $_REQUEST['thnsusut'];	
		
		
		$fmSKPD = $_REQUEST['c'];
		$fmUNIT = $_REQUEST['d'];
		$fmSUBUNIT = $_REQUEST['e'];
		$fmSEKSI = $_REQUEST['e1'];
		$fmSemester = $_REQUEST['fmSemester'];
		
		
		if($fmSemester == 1){
			$blnsusut = 12;
		}else{
			$blnsusut = 6;
		}

		$cek .= " thnsusut=$thnsusut blnsusut=$blnsusut ";
		if ($thnsusut>0){
			//$aqry = $this->genQuery(2, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $thnsusut,$blnsusut);
			$aqry = $this->genQuery(2,$fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI,$thnsusut, $blnsusut);	 $cek .= $aqry['content'];	
			$get = mysql_fetch_array(mysql_query( $aqry['content'] ));
			$content->vjml = number_format( $get['cnt'] , 0 ,',','.');
			$content->jml = $get['cnt'];	
		}else{
			$content->vjml = number_format( 0, 0 ,',','.');
			$content->jml = 0;	
		}
		
		return	array ('cek'=>htmlspecialchars($cek), 'err'=>$err, 'content'=>$content, 'json'=>htmlspecialchars($json));
	}
	
	function set_selector_other($tipe){
		$cek = ''; $err=''; $content=''; $json=FALSE;
		switch($tipe){			
			case 'susut1barang':{
				$idbi = $_REQUEST['idbi'];				
				$thnsusutak = 2014;//$_REQUEST['thnsusutak'];				
				$blnsusutak = 12;//$_REQUEST['blnsusutak'];	
				$tglSusut = '2014-12-31';//$_REQUEST['tglSusut'];			
				$get = $this->susut1barang($idbi, $thnsusutak, $blnsusutak, $tglSusut);
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			case 'changeTahun':{
				$get = $this->changeTahun();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}	
			case 'formRincian':{
				$get = $this->formRincian();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}	
			case 'formSusut':{
				$get = $this->genFormSusut();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}	
			case 'formSusutSatu':{
				$get = $this->genFormSusutSatu();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			case 'formKoreksi':{
				$get = $this->genFormKoreksi();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}			
			case 'susut':{
				//$content='tes';				
				$idbi = $_REQUEST['idbi'];		
				$thnsusut = $_REQUEST['thnsusut'];//date('Y');//2016
				$fmSemester = $_REQUEST['fmSemester']; $cek .= "sem= $fmSemester ";
				if( $fmSemester ==1 ){ 
					$blnsusut=12;
					$tgls = 31;
				} else {
					$blnsusut=6;
					$tgls = 30;
				}
				//$blnsusut=12;
				//$tgls = 31;				
				$get['cek'] .= "bln= $blnsusut ";
				$tglsusut = $thnsusut.'-'.$blnsusut.'-'.$tgls;			

				$get = $this->susutSemester($thnsusut,$blnsusut,$tglsusut,$fmSemester,30);
				//$get = $this->susut();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			case 'susutSatu':{
				//$content='tes';		
				$idbi = $_REQUEST['idbi'];		
				$thnsusut = $_REQUEST['thnsusut'];//date('Y');//2016
				$fmSemester = $_REQUEST['fmSemester']; $cek .= "sem= $fmSemester ";
				if( $fmSemester ==1 ){ 
					$blnsusut=12;
					$tgls = 31;
				} else {
					$blnsusut=6;
					$tgls = 30;
				}
				//$blnsusut=12;
				//$tgls = 31;		
				$get['cek'] .= "bln= $blnsusut ";
				$tglsusut = $thnsusut.'-'.$blnsusut.'-'.$tgls;			

				$get = $this->susutSatu($idbi,$thnsusut,$blnsusut,$tglsusut,$fmSemester,30);
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			case 'susutKoreksi':{
				//$content='tes';		
				$idbi = $_REQUEST['idbi'];		
				//$thnsusut = $_REQUEST['thnsusut'];//date('Y');//2016
				$tgl_koreksi=$_REQUEST['tgl_koreksi'];
				//$fmSemester = $_REQUEST['fmSemester']; $cek .= "sem= $fmSemester ";
				/*if( $fmSemester ==1 ){ 
					$blnsusut=12;
					$tgls = 31;
				} else {
					$blnsusut=6;
					$tgls = 30;
				}*/
				
				$thnsusut=substr($tgl_koreksi,0,4);
				$blnsusut=substr($blnsusut,5,2);
				$fmSemester=$blnsusut>6 ? 2 : 1;
				//$blnsusut=12;
				//$tgls = 31;		
				$get['cek'] .= "bln= $blnsusut ";
				$tglsusut = $tgl_koreksi;//$thnsusut.'-'.$blnsusut.'-'.$tgls;			

				$get = $this->susutKoreksi($idbi,$thnsusut,$blnsusut,$tglsusut,$fmSemester,30);
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}			
			case 'susutAll':{
				//$content='tes';
				$get = $this->susutAll();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			case 'batalSusut':{
				//$content='tes';
				$get = $this->batalSusut();
				$err = $get['err'];
				$cek = $get['cek'];
				$content = $get['content'];				
				$json=TRUE;
				break;
			}
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	/*
	function genSumHal($Kondisi){
		
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$Sum = 0;
		$SumArr=array();
		$vSum = array();
		
		$fsum_ = array();
		$fsum_[] = "count(*) as cnt";
		//$i=0;
		foreach($this->FieldSum as &$value){
			$fsum_[] = "sum($value) as sum_$value";
		}
		$fsum = join(',',$fsum_);
				
		$aqry = $this->setSumHal_query($Kondisi, $fsum); $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = 1;//$isi['cnt'];			
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue(0, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}	
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = '';
		//if( sizeof($vSum)==0) $vsum
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}
	*/
	function setPage_HeaderOther(){	
		global $Main;
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];
		//if($Pg == 'sensus'){
		//	$styleMenu = " style='color:blue;' ";	
		//}
		$menu = $_REQUEST['menu'];
		/*switch ($menu){
			case 'belumcek' : $styleMenu2_1 = " style='color:blue;' "; break;
			case 'diusulkan': $styleMenu2_3 = " style='color:blue;' "; break;
			case 'laporan' 	: $styleMenu2_4 = " style='color:blue;' "; break;
			case 'kertaskerja' 	: $styleMenu2_5 = " style='color:blue;' "; break;
			case 'ada' :$styleMenu2_2 = " style='color:blue;' "; break;	
			case 'tidakada' :$styleMenu2_5 = " style='color:blue;' "; break;
			//default: $styleMenu2_2 = " style='color:blue;' "; break;	
		}*/
		//if($tipe='tipe')$styleMenu2_4 = " style='color:blue;' ";
		$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		
		if( $Main->MODUL_AKUNTANSI ){
			$pembukuan = "<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu>AKUNTANSI</a> |";
			$menu_akuntansi =
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>						
					<a href=\"index.php?Pg=05&jns=intra\" title=\"BI Intracomptable\" $styleMenuA0>BI INTRA</a> |
					<a href=\"index.php?Pg=05&jns=extra\" title=\"BI Extracomptable\" $styleMenuA1>BI EXTRA</a>  |  
					<a href=\"index.php?Pg=05&jns=penyusutan\" title=\"Penyusutan\" style='color:blue;'>PENYUSUTAN</a>  |  
					<a href=\"pages.php?Pg=Pembukuan\" title=\"Rekap Neraca\"  >REKAP NERACA</a>  |  
					<a href=\"index.php?Pg=05&jns=tetap\" title=\"Rincian Neraca\" $styleMenuA2>RINCIAN NERACA</a>  |  
					<a href=\"index.php?Pg=05&SPg=04&jns=tetap\" title=\"Aset Tetap Tanah\" $styleMenuA3>KIB A</a>  |  
					<a href=\"index.php?Pg=05&SPg=05&jns=tetap\" title=\"Aset Tetap Peralatan &amp; Mesin\" $styleMenuA4>KIB B</a>  |  
					<a href=\"index.php?Pg=05&SPg=06&jns=tetap\" title=\"Aset Tetap Gedung &amp; Bangunan\" $styleMenuA5>KIB C</a>  |  
					<a href=\"index.php?Pg=05&SPg=07&jns=tetap\" title=\"Aset Tetap Jalan, Irigasi &amp; Jaringan\" $styleMenuA6>KIB D</a>  |  
					<a href=\"index.php?Pg=05&SPg=08&jns=tetap\" title=\"Aset Tetap Lainnya\" $styleMenuA7>KIB E</a>  |  
					<a href=\"index.php?Pg=05&SPg=09&jns=tetap\" title=\"Aset Tetap Konstruksi Dalam Pengerjaan\" $styleMenuA8>KIB F</a>  |   							
					<a href=\"index.php?Pg=05&SPg=03&jns=lain\" title=\"Aset Lainnya\" $styleMenuA9>ASET LAINNYA</a> |
					<a href=\"pages.php?Pg=RekapAset\" title=\"Rekap Aset Tetap\" $styleMenuA10>REKAP ASET</a>							
					&nbsp;&nbsp;&nbsp;
				</td></tr></table>";
			/*$menu_akuntansi = 
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			
					<a href=\"index.php?Pg=05&SPg=03&c=&d=&e=&skpdro=1&jns=intra&fmFiltSdThnBuku=2014\" title=\"BI Intracomptable\" style=\"\">BI INTRA</a> |
					<a href=\"index.php?Pg=05&SPg=03&c=&d=&e=&skpdro=1&jns=extra&fmFiltSdThnBuku=2014\" title=\"BI Extracomptable\">BI EXTRA</a>  |  
					<a href=\"pages.php?Pg=Penyusutan\" title=\"Peralatan &amp; Mesin\" style='color:blue;'>PENYUSUTAN</a>  |  
					<a href=\"pages.php?Pg=Pembukuan\" title=\"Gedung &amp; Bangunan\"  >REKAP NERACA</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=03&c=&d=&e=&skpdro=1&jns=tetap&fmFiltSdThnBuku=2014
					\" title=\"Jalan, Irigasi &amp; Jaringan\">RINCIAN NERACA</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=04&c=00&d=00&e=00&f=01&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Tanah\">KIB A</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=05&c=00&d=00&e=00&f=02&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Peralatan &amp; Mesin\">KIB B</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=06&c=00&d=00&e=00&f=03&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Gedung &amp; Bangunan\">KIB C</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=07&c=00&d=00&e=00&f=04&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Jalan, Irigasi &amp; Jaringan\">KIB D</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=08&c=00&d=00&e=00&f=05&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Aset Tetap Lainnya\">KIB E</a>  |  
					<a href=\"
					index.php?Pg=05&SPg=09&c=00&d=00&e=00&f=06&g=00&skpdro=1&jns=tetap&xd=541fd1046ad05&fmFiltSdThnBuku=2014-09-22
					\" title=\"Konstruksi Dalam Pengerjaan\">KIB F</a>  |   
						
					<a href=\"
					index.php?Pg=05&SPg=03&c=&d=&e=&skpdro=1&jns=lain&fmFiltSdThnBuku=2014
					\" title=\"Rekap BI\">ASET LAINNYA</a> |
					<a target=\"blank\" href=\"
					pages.php?Pg=RekapAset
					\" title=\"Peta Sebaran\">REKAP BI</a> 
						
					&nbsp;&nbsp;&nbsp;
				</td></tr></table>";*/
		}else{
			$pembukuan = "<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu>PEMBUKUAN</a> |";
		}
		
		return 
			//"<tr height='22' valign='top'><td >".
			"<!--menubar_page-->
		
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
			<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
			<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
			<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
			<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
			<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
			<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
			<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |
			<A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' >PETA</a> |
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |
			$pembukuan
			<A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> 	
			  &nbsp&nbsp&nbsp
			</td></tr></table>".
			
			$menu_akuntansi.
			""
			;
			
	}
	function genDaftarOpsi(){
		global $Main;
		
		//data cari ----------------------------
		$idbi = $_REQUEST['idbi']; //tgl buku
				
		//data order ------------------------------
		
		//tampil -------------------------------
		$TampilOpt = genFilterBar(
			array( "<input type='hidden' id='idbi' name='idbi' value='".$idbi."'>", 
				'<input type="button" id="btHapus" value="Hapus" onclick="Penyusutan.Hapus()">'
				//'<input type="button" id="btCetak" value="Cetak" onclick="Penyusutan.cetakAll()">'
			 ),
			$this->Prefix.".refreshList(true)",
			TRUE
		);
		
		//$TampilOpt;
		
		return array('TampilOpt'=>$TampilOpt);	
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		//$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		
		//Kondisi				
		$arrKondisi= array();
		$idbi = $_REQUEST['idbi'];
		$bi = mysql_fetch_array(mysql_query( "select * from buku_induk where id = '$idbi' "));		
		//if(!empty($idbi) ) $arrKondisi[] = " idbi_awal = '".$bi['idawal']."' ";
		
		$arrKondisi[] = " idbi = '$idbi' ";
		//if($Main->SUSUT_MODE==9){
			#$arrKondisi[] = " ";		
			
		//}else{
		/*if($Main->SUSUT_MODE==2 || $Main->SUSUT_MODE==3 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==8 || $Main->SUSUT_MODE==11){
			 $arrKondisi[] = " sisa_masa_manfaat != 0 ";		
		}*/
		//}
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		
		
		//order -------------------------
		$OrderArr = array();
		$OrderArr[] = " Id ASC ";
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal;
		$Limit = $Mode == 3 ? '': $Limit;
		//$Limit = '';
		//$Limit = ' limit 0,1 '; //tes akuntansi
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						-->".
						$scriptload;
	}
	
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		global $Main;

		//Id,tgl,tahun,idbi,harga,uid,tgl_update,thn_perolehan,hrg_perolehan,hrg_rehab,hrg_rehab_tambah_,masa_manfaat,residu,masa_manfaat_tambah_
		$cetak = $Mode==2 || $Mode==3 ;
		$idbi = $_REQUEST['Penyusutan_idplh'];
		$get = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id ='$idbi' "
		));		
		if(($Main->SUSUT_MODE==3 && $get['thn_perolehan'] > 2014) || $Main->SUSUT_MODE==11){
			$vbln = "Bulan";				
			$vbln_mode = "<th class=\"th01\" rowspan='2' width='60px'>Bulan Awal</th>".
						 "<th class=\"th01\" rowspan='2' width='60px'>Bulan Akhir</th>";
		}elseif($Main->SUSUT_MODE==4){
			$vbln = "Tahun";				
			$vbln_mode = "<th class=\"th01\" rowspan='2' width='60px'>Bulan Awal</th>".
						 "<th class=\"th01\" rowspan='2' width='60px'>Bulan Akhir</th>";
		}elseif($Main->SUSUT_MODE==8){
			$vbln = "Semester";				
			$vbln_mode = "<th class=\"th01\" rowspan='2' width='60px'>Bulan Awal</th>".
						 "<th class=\"th01\" rowspan='2' width='60px'>Bulan Akhir</th>";
		}else{
			$vbln = "Tahun";	
			$vbln_mode = "<th class=\"th01\" rowspan='2' width='60px'>Bulan</th>";
		}		

		$headerTable =
				"<tr>
				<th class=\"th02\" colspan='". ($cetak? "1": "2") ."' width='100px'>Nomor</th>				
				<th class=\"th01\" rowspan='3' width='100px'>Tgl. Penyusutan</th>
				<th class=\"th01\" rowspan='2' width='60px'>Tahun</th>
				<th class=\"th01\" rowspan='2' width='60px'>Semester</th>
				$vbln_mode
				<th class=\"th01\" rowspan='2' width='100px'>Persen Residu (%)</th>
				<th class=\"th01\" rowspan='2' width='100px'>Akumulasi<br>Masa Manfaat ($vbln)</th>
				<th class=\"th01\" rowspan='2' width='100px'>Penambahan <br>Harga Perolehan</th>
				<th class=\"th01\" rowspan='2' width='100px'>Penambahan <br>Masa Manfaat ($vbln)</th>
				<th class=\"th01\" rowspan='2' width='100px'>Sisa <br>Masa Manfaat ($vbln)</th>													
				<!--<th class=\"th01\" rowspan='2' width='200px'>Akumulasi<br>Harga Rehabilitasi (Rp)</th>-->
				<th class=\"th01\" rowspan='2' width='250px'>Penyusutan (Rp)</th>	
				<th class=\"th01\" rowspan='2' width='300px'>Akumulasi Penyusutan (Rp)</th>
				<th class=\"th01\" rowspan='2' width='300px'>Nilai Buku (Rp)</th>
				<th class=\"th01\" rowspan='2' width='300px'>Keterangan</th>
				</tr>
				<tr>
				<th class=\"th01\" width='50px'>No.</th>			
				$Checkbox
				</tr>
				$tambahgaris";
		return $headerTable;
	}
	
	
	/*function genMenu(){
		global $HTTP_COOKIE_VARS;
		$MyModulKU = explode(".", $HTTP_COOKIE_VARS["coModul"]);
		
		
		//$this->noModul = 1;
		$menuedit = '';
		if( $MyModulKU[$this->noModul-1 ] == 1 ) {
			$menuedit = $this->setMenuEdit();
		}
		
		
		$menu = 
			"<table width=\"125\"><tbody><tr><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:barcode.cetak()\"> 
				<img src=\"images/administrator/images/barcode.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Barcode\"> Barcode</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:Reclass.reClass()\"> 
				<img src=\"images/administrator/images/mutasi.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Reclass Barang\"> Reclass</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\"> 
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:MutasiUsulan.usulanbi()\" title=\"Usulan Mutasi\" style=\"width:80\"> 					
					<img src=\"images/administrator/images/mutasi.png\" alt=\"button\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\"> 
					<br>Usulan Mutasi
				</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\"> 
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:MutasiUsulan.beritaAcaraBi()\" title=\"Mutasi Balai\" style=\"width:80\"> 					
					<img src=\"images/administrator/images/mutasi.png\" alt=\"button\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\"> 
					<br>Mutasi Balai
				</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:prosesBaru()\"> 
				<img src=\"images/administrator/images/new_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Baru\"> Baru</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:prosesEdit()\"> 
				<img src=\"images/administrator/images/edit_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Ubah\"> Ubah</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:prosesHapus()\"> 
				<img src=\"images/administrator/images/delete_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Delete\"> Delete</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:cetakBrg()\"> 
				<img src=\"images/administrator/images/print_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Barang\"> Barang</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:Penatausahaan_CetakHal()\"> 
				<img src=\"images/administrator/images/print_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Halaman\"> Halaman</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:Penatausahaan_CetakAll()\"> 
				<img src=\"images/administrator/images/print_f2.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Semua\"> Semua</a> 
			</td> 
			</tr> 
			</tbody></table> </td><td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"toolbar\">
			<tbody><tr valign=\"middle\" align=\"center\"> 
			<td class=\"border:none\"> 
				<a class=\"toolbar\" id=\"\" href=\"javascript:Penatausahaab_exportXls()\"> 
				<img src=\"images/administrator/images/export_xls.png\" alt=\"Save\" name=\"save\" width=\"32\" height=\"32\" border=\"0\" align=\"middle\" title=\"Excel\"> Excel</a> 
			</td> 
			</tr> 
			</tbody></table> </td></tr></tbody></table>";
		
		$SubTitle_menu = 
			"<div style='float:right;'>					
			<div><div></div><div>$scriptMenu</div></div>".
			$menu.
			//<table ><tr>".
			//$menuedit.//$this->SetPage_ToolbarAtasEdit().
			//$this->setMenuView().
			//"</tr>
			//</table>		
			"</div>";
		return $SubTitle_menu;
	}*/
	
	function setDaftar_After($no=0, $ColStyle=''){
		
		
				
		/*$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=11 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>3.718.000,00</td>
				
			<td class='$ColStyle' align='right'><b>0,00</td>
			<td class='$ColStyle' align='right'><b>0,00</td>
			<td class='$ColStyle' align='right'><b>0,00</td>
			<td class='$ColStyle' align='right'><b>3.718.000,00</td>
			
			
			<td class='$ColStyle' align='right'></td>
			";*/
		
		return $ListData;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		//Id,tgl,tahun,idbi,harga,uid,tgl_update,thn_perolehan,hrg_perolehan,hrg_rehab,hrg_rehab_tambah_,masa_manfaat,residu,masa_manfaat_tambah_
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
		$this->akum_penyusutan += $isi['harga'];
		$this->akum_masamanfaat += $isi['masa_manfaat'];
		
		$this->akum_rehab +=$isi['hrg_rehab'];
		$this->akum_perolehan += $isi['hrg_perolehan'];
		//$hrgperolehan = $_REQUEST['hrg_perolehan'];
		//$nilaibuku = ($hrgperolehan + $isi['hrg_rehab']) - $this->akum_penyusutan;		
		if($Main->SUSUT_MODE==2){
			$nilaibuku = $this->akum_perolehan - $this->akum_penyusutan;
			
		}else{
			if($isi['sem']==1){
				$tglcurr =  $isi['tahun'].'-06-30';	
			}else{
				$tglcurr =  $isi['tahun'].'-12-31';	
			}	
			$nilaibuku = getNilaiBuku($isi['idbi'],$tglcurr,0) - $this->akum_penyusutan;
		}
		
		
		//$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		if($Main->SUSUT_MODE ==1 ){
			$vbulan = $isi['bulan'];	
		}else{
			$vbulan ='';
		}
		
		//tampil di kolom ---------------------------------------
		switch($Main->SUSUT_MODE){
			case '1' : case '2' : case '6' : case '7' : case '8' : case '9': case '10': case '11' : $b ='';break;
			default: $b = '<b>'; break;			
		}
		
		$vtambahprolehan = $isi['hrg_perolehan'] > 0 ?
			number_format( $isi['hrg_perolehan'],2,',','.' ):
			'';
		$vtambahmanfaat = $isi['masa_manfaat'] > 0 ?
			number_format( $isi['masa_manfaat'],0,',','.' ):
			'';
		$vsisamanfaat = $isi['sisa_masa_manfaat'] > 0 ?
			number_format( $isi['sisa_masa_manfaat'],0,',','.' ):
			'';

		$vtambahprolehan = number_format( $isi['hrg_perolehan'],2,',','.' );
		$vtambahmanfaat = number_format( $isi['masa_manfaat'],0,',','.' );
		$vsisamanfaat = number_format( $isi['sisa_masa_manfaat'],0,',','.' );

		
		$idbi = $_REQUEST['Penyusutan_idplh'];
		$get = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id ='$idbi' "
		));
		
		$ket = $isi['id_koreksi']!=0?"koreksi #".$isi['id_koreksi']:$isi['ket'];

		$cekAsetTetap='';
		$cek_bawahkap = '';
		$Koloms[] = array('align=right',$b. $no.'.' );	
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);		
		$Koloms[] = array("align=center  ",$b.TglInd($isi['tgl']));
		$Koloms[] = array("align=center ",$b.$isi['tahun']);
		$Koloms[] = array("align=center ",$b.$isi['sem']);
		
		if(($Main->SUSUT_MODE==3 && $get['thn_perolehan'] > 2014) || $Main->SUSUT_MODE==4 || $Main->SUSUT_MODE==8 || $Main->SUSUT_MODE==11){
			$Koloms[] = array("align=center ",$b.$isi['bulan_awl']);
			$Koloms[] = array("align=center ",$b.$isi['bulan']);
		}else{
			$Koloms[] = array("align=center ",$b.$isi['bulan']);
		}
		
		$Koloms[] = array("align=right ", $b.number_format( $isi['residu'],2,',','.' ) );
		
		if($Main->SUSUT_MODE==2 || $Main->SUSUT_MODE==3 || $Main->SUSUT_MODE==4 || $Main->SUSUT_MODE==5 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || $Main->SUSUT_MODE==8 || $Main->SUSUT_MODE==9 || $Main->SUSUT_MODE==10 || $Main->SUSUT_MODE==11){
		  $Koloms[] = array("align=center ", $b.number_format( $isi['akum_masa_manfaat'],0,',','.' ));		
		}else{
			$Koloms[] = array("align=center ", $b.$this->akum_masamanfaat);		
		}
		$Koloms[] = array("align=right ", $b.  $vtambahprolehan );		
		$Koloms[] = array("align=center ", $b.  $vtambahmanfaat );		
		$Koloms[] = array("align=center ", $b.  $vsisamanfaat );
		//$Koloms[] = array("align=right ", $b.number_format( $this->akum_rehab,5,',','.' ) );	
		$Koloms[] = array("align=right ", $b.number_format( $isi['harga'],2,',','.' ) );
		
		if($Main->SUSUT_MODE==2 || $Main->SUSUT_MODE==3 || $Main->SUSUT_MODE==4 || $Main->SUSUT_MODE==5 || $Main->SUSUT_MODE==6 || $Main->SUSUT_MODE==7 || $Main->SUSUT_MODE==8 || $Main->SUSUT_MODE==9 || $Main->SUSUT_MODE==10 || $Main->SUSUT_MODE==11){
			$Koloms[] = array("align=right ", $b.number_format( $isi['akum_susut'],2,',','.' ) );
			$Koloms[] = array("align=right ", $b.number_format( $isi['nilai_buku_stlh_susut'],2,',','.' ) );
		}else{
				$Koloms[] = array("align=right ", $b.number_format( $this->akum_penyusutan,2,',','.' ) );
				$Koloms[] = array("align=right ", $b.number_format( $nilaibuku,2,',','.' ) );
		}
		$Koloms[] = array("align=left ", $b.$ket );		
		return $Koloms;
	}
	
	
	function setDaftar_after_getrow($list_row, $isi, $no='', $Mode='', $TampilCheckBox='', $RowAtr='', $KolomClassStyle=''){
		global $Main;
		//$nilaibuku = $this->akum_perolehan - $this->akum_penyusutan;
		//$this->akum_penyusutan += $isi['harga'];
		/*$this->akum_penyusutan += $isi['harga'];
		$this->akum_masamanfaat += $isi['masa_manfaat'];
		
		$this->akum_rehab +=$isi['hrg_rehab'];
		$this->akum_perolehan += $isi['hrg_perolehan'];*/
		
		$akumsusut_ = $this->akum_penyusutan - $isi['harga'];
		//$nilaibuku_ = ( $this->akum_perolehan-$isi['hrg_perolehan']) - ($this->akum_penyusutan -  $isi['harga']) ;
		$nilaibuku_ = $isi['hrg_perolehan'];
		
		if($Main->SUSUT_MODE==5){//serang kota
		
		}else if($Main->SUSUT_MODE==6){//  && $Main->VERSI_NAME=='TASIKMALAYA_KAB'){//Tasikmalaya
		
		}else if($Main->SUSUT_MODE==7  && $Main->VERSI_NAME=='PANDEGLANG'){//Pandeglang
		
		}
		else if($Main->SUSUT_MODE==8){//  && $Main->VERSI_NAME=='CIREBON'){//CIREBON
		
		}
		else if($Main->SUSUT_MODE==9){//  && $Main->VERSI_NAME=='Bandung Barat'){//Bandung Bara
		
		}else if($Main->SUSUT_MODE==10){//  && $Main->VERSI_NAME=='BOGOR'){//BOGOR
		
		}else if($Main->SUSUT_MODE==11){//  && $Main->VERSI_NAME=='KARAWANG'){//KARAWANG
		
		}else if($Main->SUSUT_MODE==4){//garut
		
		
			
		}else if($Main->SUSUT_MODE==1){//jabar
		
		}else if($Main->SUSUT_MODE==2){//serang
		
		}
		elseif($Main->SUSUT_MODE==3){//pandeglang jabar
			
		}
		elseif($Main->SUSUT_MODE==3 && $isi['tahun'] <= '2014'){//pandeglang jabar
			
		}
		else if($Main->SUSUT_MODE==3 && $isi['tahun'] > '2014'){//jabar 2			
			$j = $isi['sem'] == 1 ? 1 : 7;			
			for ($i=0; $i<6; $i++){
				$bln = $j+$i;
				//return concat(hrg_susut_,';',tot_rehab_,';',masa_rehab_,';',masa_manfaat_,';',hrg_perolehan_,';',nilai_buku_);
				$aqry = "select sf_get_susut( ".$isi['idbi'].", ".$isi['tahun'].", ".$bln.",0) as hsl ; ";	
				$get = mysql_fetch_array(mysql_query($aqry) )	;
				$hsl= explode(';',$get['hsl']);
				$hrgsusut = number_format( $hsl[0], 2,',','.');
				$totrehab = number_format( $hsl[1], 2,',','.');
				$masa_manfaat = number_format( $hsl[3], 0,',','.');
				
				$akumsusut_ += $hsl[0];	
				$bln_ = $bln+1;
				$thn_ = $isi['tahun'];
				if ($bln_ > 12 ) {
					$thn_ ++; 
					$bln_ --;
				}
				
				$tglcur = date('Y-m-j',  strtotime ( '-1 day' , strtotime (  "$thn_-$bln_-1") )) ;				 
				$nilaibuku_ = getNilaiBuku($isi['idbi'],$tglcur,0 ) - $akumsusut_;
				$akumsusut = number_format( $akumsusut_, 2,',','.');
				$nilaibuku = number_format( $nilaibuku_, 2,',','.');
				$list_row .= "<tr $RowAtr valign='top'>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar' align='center'>".$bln."</td>".
					"<td class='GarisDaftar'></td>".
					"<td class='GarisDaftar' align='center'>$masa_manfaat</td>".
					"<td class='GarisDaftar' align='right'>$totrehab</td>".
					"<td class='GarisDaftar' align='right'>$hrgsusut</td>".
					"<td class='GarisDaftar' align='right'>$akumsusut</td>".
					"<td class='GarisDaftar' align='right'>$nilaibuku <input type='hidden' value='$tglcur $thn_-$bln_-1'></td>".			
					"</tr>";		
		
			}
		}
		else{
			//$j = $isi['sem'] == 1 ? 2 : 8;
			$j = $isi['sem'] == 1 ? 1 : 7;
			$SUSUT_MODE= $Main->SUSUT_MODE== ''? 0: $Main->SUSUT_MODE;
			$bi = mysql_fetch_array(mysql_query("select *, month(tgl_buku) as blntglbuku from buku_induk where id='".$isi['idbi']."'"));
			$thnak = $bi['thn_perolehan'] > $Main->TAHUN_MULAI_SUSUT ? $bi['thn_perolehan'] : $Main->TAHUN_MULAI_SUSUT ;
			$thnak = $thnak+$bi['masa_manfaat'];
			$blnak= $bi['blntglbuku'] + 1;
			if($blnak>12){
				$thnak ++;
				$blnak = 1;
			}
			$cek .= '$isi[tahun]='.$isi['tahun'].' $thnak='. $thnak;
			for ($i=0; $i<6; $i++){
				$bln = $j+$i;
				//if( $isi['tahun']<=$thnak && $bln < $blnak ){
				if( $isi['tahun']*12 + $bln <$thnak *12+$blnak ){	
				
					//return concat(hrg_susut_,';',tot_rehab_,';',masa_rehab_,';',masa_manfaat_,';',hrg_perolehan_,';',nilai_buku_);
					$aqry = "select sf_get_susut( ".$isi['idbi'].", ".$isi['tahun'].", ".$bln.", $SUSUT_MODE ) as hsl ; ";	$cek .= $aqry;
					$get = mysql_fetch_array(mysql_query($aqry) )	;
					$hsl= explode(';',$get['hsl']);
					$hrgsusut = number_format( $hsl[0], 2,',','.');
					$totrehab = number_format( $hsl[1], 2,',','.');
					$masa_manfaat = number_format( $hsl[3], 0,',','.');
					$akumsusut_prev= $akumsusut_;
					$akumsusut_ += $hsl[0];	
					//$nilaibuku_ += $hsl[1]- $hsl[0]; 
					$nilaibuku_ = $hsl[4] - $akumsusut_;
					
					//setDate($isi['tahun'], $bln, $data[2]);
					//$bln_ = $bln;
					$bln_ = $bln;
					$thn_ = $isi['tahun'];
					if ($bln_ > 12 ) {
						$thn_ ++; 
						$bln_ --;
					}
					
					//cek minus
					if ($isi['tahun']*12 + $bln +1 == $thnak *12+$blnak  ){
						$cek .= ' akhir';
						$hrgsusut = round( $hsl[4],2) - round($akumsusut_prev,2);
						$akumsusut_ = round($akumsusut_prev,2) + round($hrgsusut,2);
						$nilaibuku_ = round($hsl[4],2) - round($akumsusut_,2);
						$hrgsusut = number_format( $hrgsusut, 2,',','.');
					}
					
					$akumsusut = number_format( $akumsusut_, 2,',','.');
					$nilaibuku = number_format( $nilaibuku_, 2,',','.');
					$list_row .= "<tr $RowAtr valign='top'>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar' align='center'>".$bln."</td>".
						"<td class='GarisDaftar'></td>".
						"<td class='GarisDaftar' align='center'>$masa_manfaat</td>".
						"<td class='GarisDaftar' align='right'>$totrehab</td>".
						"<td class='GarisDaftar' align='right'>$hrgsusut</td>".
						"<td class='GarisDaftar' align='right'>$akumsusut</td>".
						"<td class='GarisDaftar' align='right'>$nilaibuku ".
						"<div style='display:none;'>$tglcur $thn_-$bln_-1; $curthn  $cek</div></td>".			
						"</tr>";	
				}else{
					break;
				}
			}

		}
				
		
		return $list_row;
	}
	
	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/
	
}
$Penyusutan = new PenyusutanObj();

?>