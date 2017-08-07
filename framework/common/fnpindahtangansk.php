<?php

class PindahTanganSKObj  extends DaftarObj2{	
	var $Prefix = 'PindahTanganSK';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pemindahtanganan_sk'; //daftar
	var $TblName_Hapus = 'pemindahtanganan_sk';//'penghapusan_usul_bmd';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array('jmlA','hrgA','jmlB','hrgB','jmlC','hrgC','jmlD','hrgD','jmlE','hrgE','jmlF','hrgF','jml','hrg');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 5, 4, 4);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0, 0);
	var $fieldSum_lokasi = array(7,8,9,10,11,12);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Pemindahtanganan';
	var $PageIcon = 'images/pemindahtanganan_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulan_hapus_bmd.xls';
	var $Cetak_Judul = 'DAFTAR SK PEMINDAHTANGANAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'PindahTanganSK_listForm';
	var $pagePerHal = '';
	
	function genSumHal($Kondisi){		
		global $Main;		
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		$fsum_ = array();
		
		$fsum_[] = "count(*) as cnt";
		
		$fsum = join(',',$fsum_);		
		$aqry = "select count(*) as cnt from $this->TblName_Hapus $Kondisi" ; $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['cnt'];			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue(0, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}			
		for($i=0;$i<6;$i++) {
			$Kondisi2 = $Kondisi != '' ?  $Kondisi . " and f='0".($i+1)."'"  : " where f='0".($i+1)."'" ;
			$aqry = "select count(*) as cnt, sum(jml_harga) as jml_harga from v2_pemindahtanganan_sk $Kondisi2 "; $cek.= $aqry;
			$get = mysql_fetch_array(mysql_query($aqry));
			$vSum[$i*2] = number_format($get['cnt'],0,',','.');
			$vSum[$i*2+1] = number_format($get['jml_harga'],2,',','.');				
		}
		$Kondisi2 = $Kondisi != '' ?  $Kondisi . " and id_bukuinduk !=''"  : " where id_bukuinduk !=''";			
		$aqry = "select count(*) as cnt, sum(jml_harga) as jml_harga from v2_pemindahtanganan_sk $Kondisi2 "; $cek.= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$vSum[12] = number_format($get['cnt'],0,',','.');
		$vSum[13] = number_format($get['jml_harga'],2,',','.');
		
		
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = ''; 			
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}
	
	function genRowSum_setTampilValue($i, $value){
		if($i % 2 ==0){
			return number_format($value,0, ',', '.'); 
		}else{
			return number_format($value,2, ',', '.'); 
		}
	}
	
	function genRowSum($ColStyle, $Mode, $Total){
		//--- total hal
		$ContentTotalHal=''; $ContentTotal='';		
		$colspanarr=$this->fieldSum_lokasi;
		$ContentTotalHal =	"<tr>";
		$ContentTotal =	"<tr>";		
		
		for ($i=0; $i<sizeof($this->FieldSum);$i++){
			
			if($i==0){
				$TotalColSpan1 = $this->genRowSum_setColspanTotal($Mode, $colspanarr );
				$Kiri1 = $TotalColSpan1 > 0 ? 
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalhalstr</td>": '';
				$ContentTotalHal .=	$Kiri1;
				$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalAllStr</td>":'';
			}else{
				$TotalColSpan1 = $colspanarr[$i] - $colspanarr[$i-1]-1; 				
				$ContentTotalHal .=	$TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
				$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
			}
			
			$TampilTotalHalRp = //number_format($this->SumValue[$i],2, ',', '.');
				$this->genRowSum_setTampilValue($i, $this->SumValue[$i]);
			$vTotal = number_format($Total[$i],2, ',', '.');
			$ContentTotalHal .= //$i==0?				
				"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	;
			$ContentTotal .= $i==0?
				"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[$i]."</div></td>":
				"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum$i'>".$Total[$i]."</div></td>";
		}
		
		//$totrow = $Mode == 1? $this->totalRow : $this->totalRow;
		$TotalColSpan1 = $this->totalCol - $colspanarr[sizeof($this->FieldSum)-1];					
		$ContentTotalHal .=	$TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
		$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
		
		
	
		$ContentTotal = $this->withSumAll? $ContentTotal: '';
		$ContentTotalHal = $this->withSumHal? $ContentTotalHal: '';
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){				
			if ($this->withSumAll) {
				$ContentTotalHal = '';
			}
		}
		return $ContentTotalHal.$ContentTotal;
	}
	
	
	
	function setPage_HeaderOther(){	
		$style2 = "style='color:blue;'"; 
		$menubawah = "<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Usulan' >Usulan</a> |
			<A href=\"pages.php?Pg=PindahTanganNilai\" title='Penilaian Pemindahtanganan'  >Penilaian</a>  |
			<A href=\"pages.php?Pg=PindahTanganSK\" title='Surat Keputusan Pemindahtanganan' $style2 >Surat Keputusan</a>  |
			<A href=\"index.php?Pg=10&bentuk=1\" title='Penjualan' >Penjualan</a>  |  
			<A href=\"index.php?Pg=10&bentuk=2\" title='Tukar Menukar' >Tukar Menukar</a>  |  
			<A href=\"index.php?Pg=10&bentuk=3\" title='Hibah' >Hibah</a>  |  
			<A href=\"index.php?Pg=10&bentuk=4\" title='Penyertaan Modal' >Penyertaan Modal</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>";
					
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>".
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!-- <A href=\"pages.php?Pg=PenghapusanPengguna&PT=1\" title='Penghapusan' $style0>PENGHAPUSAN</a> | -->
			<A href=\"#\" title='Mutasi' >MUTASI</a>  |  
			<!-- <A href=\"pages.php?Pg=PenghapusanBmd&tl=1\" title='Pemusnahan' $style1>PEMUSNAHAN</a>  | -->
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Pemindah Tanganan' $style2>PEMINDAH TANGANAN</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>".
			$menubawah.
			"</table>";
	}
		
	function setTitle(){
		return 'SK Pemindahtanganan';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png",'Baru',"Usulan Pemindahtanganan Baru")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png", 'Edit',"Edit Usulan Pemindahtanganan")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png", 'Batal',"Batalkan Usulan Pemindahtanganan")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Barusk()","new_f2.png",'SK Gub',"SK Gubernur Pemindahtanganan")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".batalSK()","delete_f2.png", 'Batal SK',"Batalkan SK Pemindahtanganan")."</td>".
			"";
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";
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
		$a1 = $Main->DEF_KEPEMILIKAN;
		$a = $Main->Provinsi[0];
		$b = '00';
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		
		$sesi = $_REQUEST['sesi']; //ambil sesi
		
		$no_usulan= $_REQUEST['no_usulan'];
		
		$tgl_usul= $_REQUEST['tgl_usul'];
		
		$tempat = $_REQUEST['tempat'];
		
		$pejabat_pengadaan= $_REQUEST['ref_idpengadaan']; //Pengurus barang
		
		$kuasa_pengguna= $_REQUEST['id_kuasaP']; //kuasa Pengguna Barang
		$bentuk_pemindahtanganan = $_REQUEST['bentuk_pemindahtanganan'];
		
		//Pengurus Barang
		$get = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$pejabat_pengadaan."'"));
				$nama = $get['nama'];
				$nip = $get['nip'];
				$jabatan = $get['jabatan'];
				
		//kuasa Barang
		$candakData = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$kuasa_pengguna."'"));
				$nama = $candakData['nama'];
				$nip = $candakData['nip'];
				$jabatan = $candakData['jabatan'];
		
		if( $err=='' && $no_usulan =='' ) $err= 'No. Surat belum diisi !!';
		
		if( $err=='' && $tgl_usul =='' ) $err= 'Tgl Surat belum diisi !!';
		if( $err=='' && $bentuk_pemindahtanganan=='') $err= 'Bentuk Pemindahtanganan belum diisi!';
				
		if($fmST == 0){ //baru
			//-- cek apakah no suuslan sudah ada
			if( $err=='' ){
				$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM $this->TblName_Hapus WHERE no_usulan='$no_usulan' "));
				if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
			}
			//-- cek barang sudah diusulkan
			if( $err=='' ){
				$aqry = "select * from pemindahtanganan_sk_det where sesi='$sesi'  " ; $cek.=' '.$aqry;
				$det = mysql_query( $aqry);
				while($row_det = mysql_fetch_array($det)){
					$aqry = "select count(*) as cnt from pemindahtanganan_sk_det where id_bukuinduk='".$row_det['id_bukuinduk']."' and (sesi='' or sesi is null) " ; $cek.=' '.$aqry;
					$cnt = mysql_fetch_array(mysql_query($aqry));
					if($cnt['cnt']>0){
						$err="Gagal Simpan! Barang ".getKodeBarang($row_det['id_bukuinduk'],'.') ." Sudah Diusulkan";
						break;
					}
				}
				
			}
			
			if($err==''){
				$aqry = "INSERT into pemindahtanganan_sk(no_usulan,tgl_usul,tgl_update,uid,ref_idpegawai_usul,sesi, bentuk_pemindahtanganan)
						"."values('$no_usulan','$tgl_usul',now(),'$uid','$pejabat_pengadaan','$sesi','$bentuk_pemindahtanganan')";	$cek .= $aqry;	
				$qry = mysql_query($aqry);
				
				//ambil id master
				$aqry = "SELECT* FROM $this->TblName_Hapus WHERE sesi ='$sesi'"; $cek.=$aqry;
				$get = mysql_fetch_array(mysql_query($aqry));
				$idmaster = $get['Id']; $cek .= "idmaster=$idmaster";
				
				//update detail : sesi ganti id master
				$aqry = "UPDATE pemindahtanganan_sk_det 
						 SET sesi = null, Id= '$idmaster' ".
						 //bentuk_pemindahtanganan='$bentuk_pemindahtanganan'
						 " WHERE sesi='$sesi'"; $cek .= $aqry;
				$qry = mysql_query($aqry);
				
				//hapus sesi master
				mysql_query("UPDATE $this->TblName_Hapus SET sesi=NULL WHERE sesi='".$sesi."'"); 
			}
		}else{ //edit
			$old = mysql_fetch_array(mysql_query("SELECT * FROM $this->TblName_Hapus WHERE Id='$idplh'"));
			if( $err=='' ){
				if($no_usulan!=$old['no_usulan'] ){
					$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM $this->TblName_Hapus WHERE no_usulan='$no_usulan' "));
					if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
				}
			}
			
			if($err==''){
				$aqry = "UPDATE $this->TblName_Hapus set ".
					" no_usulan='$no_usulan', tgl_usul='$tgl_usul', tgl_update=now(), ".
					" uid='$uid', ref_idpegawai_usul='$pejabat_pengadaan', ".
					" bentuk_pemindahtanganan='$bentuk_pemindahtanganan' ".
					" WHERE Id='".$idplh."'";	$cek .= $aqry;
				$qry = mysql_query($aqry);
				if (!$qry) $err= 'Gagal Simpan Data!';
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}	
	
	function SimpanTterima(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
		
	 $no_tterima= $_REQUEST['no_tterima'];
	 $tgl_tterima= $_REQUEST['tgl_tterima'];
	 $petugas_tterima= $_REQUEST['id_kuasaT'];
	 
	 if( $err=='' && $no_tterima =='' ) $err= 'No Tanda Terima belum diisi!';
	 if( $err=='' && $tgl_tterima =='' ) $err= 'Tgl Tanda Terima belum diisi!';
	 if($fmST == 0){
		//cek 
		if( $err=='' ){
			$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM $this->TblName_Hapus WHERE no_tterima='$no_tterima' "));
						if($get['cnt']>0 ) $err='No Tanda Terima Sudah Ada!';
		}
		if($err==''){
			$aqry = "INSERT into penghapusan_usul(no_ba,tgl_ba,tgl_update,uid,ref_idpegawai_ba,no_tterima,tgl_tterima,ref_idtterima)
					 "."values('$no_ba','$tgl_ba',now(),'$uid','$pejabat_pengadaan','$no_tterima','$tgl_tterima','$petugas_tterima')";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
		}
	 }else{
		$old = mysql_fetch_array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id='$idplh'"));
			if( $err=='' ){
				if($no_tterima!=$old['no_tterima'] ){
		  		  $get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM $this->TblName_Hapus WHERE no_tterima='$no_tterima' "));
							if($get['cnt']>0 ) $err='No Tanda Terima Sudah Ada!';
				  }
			}
			
			if($err==''){
				$aqry = "UPDATE $this->TblName_Hapus 
				         set	no_tterima  = '$no_tterima',
						 tgl_tterima  = '$tgl_tterima',
						 uid ='$uid',
						 ref_idtterima = '$petugas_tterima'".
						 "WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
			}
	 }//end else
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
  }	
	
	function insertUsulan(){
		global $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; 
		$UID = $HTTP_COOKIE_VARS['coID'];
		$dt['c'] = $_REQUEST['SensusSkpdfmSKPD'];
		$dt['d'] = $_REQUEST['SensusSkpdfmUNIT'];
		$dt['e'] = $_REQUEST['SensusSkpdfmSUBUNIT'];	
		 
		$cbid= $_POST['Sensus_cb'];	
		$this->form_fmST = 0;
		$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); 
		
		//cek --------------------------------------------
		for($i = 0; $i<count($cbid); $i++)	{
			//-- cari id bi dari id sensus
			$sensus = mysql_fetch_array(mysql_query("select * from sensus where id = '".$cbid[$i]."'"));
			//-- cek kondisi harus kb/rb
			if(!($sensus['kondisi'] == 2 || $sensus['kondisi']==3) ) {
				$err = 'Gagal Usulan Hapus! Kondisi barang masih baik';
				break;
			}	
			//-- cek sudah diusulkan/belum
			$sudahusul = mysql_fetch_array(mysql_query(
				"select count(*) as cnt from penghapusan_usul_det where id_bukuinduk = '".$sensus['idbi']."' and (sesi ='' or sesi is null )"
			));
			if( $sudahusul['cnt'] > 0 ) {
				$err =  "Gagal Usulan Hapus! Barang ".getKodeBarang($sensus['idbi'],'.') ." sudah di usulkan";
				break;			
				
			}
			
			
		}
		
		//insert usulan ----------------------------------
		if($err==''){
			//tambahkan ke detail tmp
			for($i = 0; $i<count($cbid); $i++)	{
				//cari id bi dari id sensus
				$sensus = mysql_fetch_array(mysql_query("select * from sensus where id = '".$cbid[$i]."'"));
								
				//simpan ke uslhapusdetail
				$aqry= "insert into penghapusan_usul_det (Id, id_bukuinduk, sesi, uid, tgl_update) ".
					" values ('','".$sensus['idbi']."','".$dt['sesi']."', '$UID', now() ) ";
					//values ".$valuestr; 
				$cek .= $aqry;	//$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
				$qry = mysql_query($aqry);
				if ($qry==FALSE){
					$err = 'Gagal Simpan Data';
				}
			}			
			//set form
			$fm = $this->setForm($dt);		
			$err .= $fm['err'];
			$cek .= $fm['cek'];
			$content = $fm['content'];
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function usulanhapusbarcode(){
		global $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content='';
		$UID = $_COOKIE['coID'];
		$fmSKPD = $_REQUEST[$this->Prefix."SkpdfmSKPD"];// $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_REQUEST[$this->Prefix."SkpdfmUNIT"]; //$_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_REQUEST[$this->Prefix."SkpdfmSUBUNIT"];//$_COOKIE['cofmSUBUNIT'];
		$kode = $_REQUEST['kode'];
		$sesi = $_REQUEST['sesi'];
		//$tgl_sensus = $_REQUEST['tgl_sensus'];
		//$tahun_sensus = $_REQUEST['tahun_sensus'];
				
		//get id bi 
		$aqry = "select * from buku_induk where idall2 = '$kode'"; $cek.=$aqry;
		$bi = mysql_fetch_array (mysql_query( 
			$aqry
		));
		$idbi = $bi['id'];
		if ( $err=='' && ( $idbi == '' || $kode=='')) $err = "Barang Tidak Ada!";
		
		//cek dinas
		$cek .= ' sk= '. $fmSKPD; 
		if( $err=='' && $bi['c']!=$fmSKPD ) $err = " Bidang Tidak Sama!";
		if( $err=='' && $bi['d']!=$fmUNIT ) $err = " Asisten/OPD Tidak Sama!";
		if( $err=='' && $bi['e']!=$fmSUBUNIT ) $err = "BIRO/ UPTD/B Tidak Sama!";
				
		return array('err'=>$err,'cek'=>$cek);	
	}
	
	function setFormEditsk(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "SELECT * FROM pemindahtanganan_sk WHERE Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		//$dt['tgl_sk'] =date("Y-m-d"); //set waktu sekarang;
		$fm = $this->setFormsk($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormsk($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
		
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 700;
	 $this->form_height = 170;
	  if ($this->form_fmST==0) {
	   	 $this->form_caption = 'Baru';
		 $nip	 = '';
	 }else{
		 $this->form_caption = 'SK Gubernur';//'Berita Acara SK';			
		 $ref_idpejabat_sk = $dt['ref_idpejabat_sk'];		
	 }
		
	 //items ----------------------
	 //ambil pegawai
	 $got = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$dt['ref_idpegawai_sk']."'"));
	 $nama = $got['nama'];
	 $nip = $got['nip'];
	 $jabatan = $got['jabatan'];

	 $this->form_fields = array(		
			'no_usulan_sk' => array( 
			'label'=>'No.Usulan SK', 
			'value'=>$dt['no_usulan'], 
			'labelWidth'=>120, 
			'type'=>'' 
		    ),
			
			'tgl_usul_sk' => array( 
			'label'=>'Tgl Usul SK',
		    'value'=>TglInd($dt['tgl_usul']), 
		    'type'=>''
		    ),
		
			'no_sk' => array( 
			'label'=>'No.SK', 
			'value'=>"<input type='text' name='no_sk' value='".$dt['no_sk']."' size='43px'>", 
			'labelWidth'=>120, 
			'type'=>'' 
			),
			
			'tgl_sk' => array( 
			'label'=>'Tgl SK',
			'value'=>$dt['tgl_sk'], 
			'type'=>'date'
			),
			
			'pejabat_pengadaan' => array(  
			'label'=>'Pejabat SK', 
			'value'=> 
			  "<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai_sk']."'> ".
			  "<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$nama."' style='width:250'> &nbsp; ".
			  "NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$nip."' style='width:150' > ".					
			  "<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
			  ,
			'type'=>'' 
			), 	
			
			'jbt1' => array(  'label'=>'', 
			'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
			'type'=>'' , 'pemisah'=>' '
			)
		);
						
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpansk()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
				
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function simpansk(){ //sk gub
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$a1 = $Main->DEF_KEPEMILIKAN;
		$a = $Main->Provinsi[0];
		$b = '00';
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
					
		$no_sk= $_REQUEST['no_sk'];					
		$tgl_sk= $_REQUEST['tgl_sk'];					
		$pejabat_pengadaan= $_REQUEST['ref_idpengadaan']; //pejabat sk									
		if( $err=='' && $no_sk =='' ) $err= 'No SK belum diisi!';					
		if( $err=='' && ($tgl_sk =='' || $tgl_sk == '0000-00-00')) $err= 'Tgl SK belum diisi!';					
		
		//cek no sk sudah ada	
		$old = mysql_fetch_array(mysql_query("SELECT * FROM pemindahtanganan_sk WHERE Id='$idplh'"));
		if( $err=='' ){
			if($no_sk!=$old['no_sk'] ){
				$aqry = "SELECT count(*) as cnt FROM pemindahtanganan_sk WHERE no_sk='$no_sk' ";	$cek .= $aqry;
				$get = mysql_fetch_array(mysql_query($aqry));
				if($get['cnt']>0 ) $err='No SK Sudah Ada!';
			}
		}	
		//simpan nosk, tglsk
		if($err==''){	
						
		   $aqry = "UPDATE pemindahtanganan_sk ".
			       "SET no_sk='$no_sk',".
					"tgl_sk='$tgl_sk',".
					"tgl_update=now(),".
					"uid='$uid',".
					"ref_idpegawai_sk='$pejabat_pengadaan'".
				"WHERE Id='".$idplh."'";	$cek .= $aqry;
			$qry = mysql_query($aqry);							
			
			//tindak lanjut sk pemindahtanganan ====================================================
			$aqry = "select * from v2_pemindahtanganan_sk where Id= '".$idplh."' and (sesi is null or sesi='')"; $cek.= $aqry;
			$qry = mysql_query($aqry);
			while ($rows= mysql_fetch_array($qry)){
				$id_bukuinduk = $rows['id_bukuinduk'];
				$getskhapus = mysql_fetch_array(mysql_query(
					"select * from v2_penghapusan_usul_bmd ".
					"where id_bukuinduk='$id_bukuinduk' and (sesi is null or sesi='') "
				));
				if($getskhapus){
					$old = mysql_fetch_array(mysql_query(
						"select * from pemindahtanganan WHERE id_bukuinduk='".$id_bukuinduk."' "
					));
					// tambahkan ke pemidahtanganan					
					if($old['noskpindah']==''){
						$aqry2 = "insert into pemindahtanganan ".
							"(id_bukuinduk,".
							//"a1,a,b,c,d,e,f,g,h,i,j,noreg,thn_perolehan, ".
							"tgl_pemindahtanganan, bentuk_pemindahtanganan,".						
							"noskpindah,tglskpindah,penilaian,noskhapus,tglskhapus) ".
							" values ".
							"('$id_bukuinduk', '$tgl_sk','{$rows['bentuk_pemindahtanganan']}', ".
							" '$no_sk', '$tgl_sk', '{$rows['penilaian']}',".
							" '{$getskhapus['no_sk']}', '{$getskhapus['tgl_sk']}' ".
							" )"
							; 					
					}else{
						//update
						$aqry2 = "update pemindahtanganan set ".
							" noskpindah='$no_sk', tglskpindah='$tgl_sk',".
							" tgl_pemindahtanganan='$tgl_sk', ".
							" bentuk_pemindahtanganan='{$rows['bentuk_pemindahtanganan']}'".						
							" where id_bukuinduk = '$id_bukuinduk' ".
							"";
					}$cek .=$aqry2;
					$qry2 = mysql_query($aqry2);		
					
					// update status barang di buku induk
					if ($qry2){						
						$aqry3= "update buku_induk set status_barang=4 where id='$id_bukuinduk'";
						$qry3=mysql_query($aqry3);	
					}		
				}
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
	function batalSK(){ 
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];		
		
		
		$aqry = "select * from pemindahtanganan_sk_det where Id='".$cbid[0]."' and (sesi is null or sesi='' )";
		$qry = mysql_query($aqry);
		if($qry){
			while ($isi = mysql_fetch_array($qry)){
				$id_bukuinduk = $isi['id_bukuinduk'];
				//hapus dari pemidanhtanganan
				$aqry2 = "delete from pemindahtanganan where id_bukuinduk='$id_bukuinduk'";
				$qry2 = mysql_query($aqry2);
				
				if($qry2){
					// update buku_induk
					$aqry3 = "update buku_induk set status_barang=1 where id = '$id_bukuinduk'";
					$qry3 = mysql_query($aqry3);
					
				}	
			}	
			
			if($qry3){
				//update pemindahtanganansk	
				$aqry4= "update pemindahtanganan_sk set no_sk=null, tgl_sk=null, ref_idpegawai_sk=null where Id='{$cbid['0']}' and (sesi is null or sesi='' ) ";
				$qry4=mysql_query($aqry4);
			}
			
			
		}
		
		
		if($err=='' && !$qry4) $err = "Gagal Simpan data!";
		
		
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'simpansk':{
				$get= $this->simpansk();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'batalSK':{
				$get= $this->batalSK();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'formEditsk':{				
				$fm = $this->setFormEditsk();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'insertUsulan': {
				$get = $this->insertUsulan()	;
				$content =$get['content'];
				$cek =$get['cek'];
				$err =$get['err'];
				break;
			}
			case 'usulanhapusbarcode':{
				
				$get= $this->usulanhapusbarcode();
				$err= $get['err']; 
				$cek = $get['cek'];
				//$content = 'tets';
				break;
			}
			case 'genCetak_tterima':{
			$json=FALSE;
			$this->genCetak_tterima();
			break;
			}	
			case 'genCetak_usulan':{
			$json=FALSE;
			$this->genCetak_usulan();
			break;
			}	
			case 'cbxgedung':{
				$c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
				$d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
				if($c=='' || $c =='00') {
					$kondC='';
				}else{
					$kondC = "and c = '$c'";
				}
				if($d=='' || $d =='00') {
					$kondD='';
				}else{
					$kondD = "and d = '$d'";
				}
				if($e=='' || $e =='00') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				$aqry = "select * from ref_ruang where q='0000' $kondC $kondD $kondE";
				$cek .= $aqry;
				$content = genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						$aqry,
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" );
				break;
			}		
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'formCari':{				
				$fm = $this->setFormCari();				
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
			case 'formTterima':{				
				$fm = $this->setformTterima();				
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
			case 'simpanBA':{
				
				$get= $this->simpanBA();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'SimpanTterima':{
				
				$get= $this->SimpanTterima();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'batalttr':{
				
				$get= $this->batalttr();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			
			case 'hitungPanitia':{
				
				$get= $this->hitungPanitia();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'selectkat':{
				
				$get= $this->getselectkat();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'getdata':{
				$id = $_REQUEST['id'];
				$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
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
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
		}
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
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>**/
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pindahtanganskdet.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/panitiapemeriksa.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	function Hapus_Validasi($id){
		$errmsg ='';
		// ambil data BA di tabel Penghapusan_usul
		$get = mysql_fetch_array(mysql_query("select* from $this->TblName_Hapus where Id='".$id."'"));
		
		if($get['no_ba'] !=''){
			$errmsg = 'No BA Sudah sudah ada,Tidak Boleh di hapus !';
		}
		
		if($get["no_tterima"] !=''){
			$errmsg = 'No tanda terima sudah ada,Tidak Boleh di hapus !';
		}
		return $errmsg;
	}
	//form ==================================
	function setFormBaru(){
		$dt=array();
		/*$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		*/
		//$dt['p'] = '';
		//$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		
		$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		
		$dt['sesi'] = gen_table_session('pemindahtanganan_sk','uid'); //generate no sesi
		
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormCari(){
		$dt=array();
		
		$this->form_fmST = 0;
		
		$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		
		$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		
		$fm = $this->setFormCr($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		/*$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		*/		
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from $this->TblName_Hapus where Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
					
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setformTterima(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		
		//get data 
		$aqry = "select * from $this->TblName_Hapus where Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//jika sudah ada surat BA dianggap sudah ada surat Tanda terima
		if($dt['no_ba'] !=''){
			$fm['err']='Surat sudah Ada BA';
		} 	
		else{
			$dt['tgl_tterima'] = date("Y-m-d");
			$fm = $this->setFormTt($dt);	
		}
				
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	
	function Hapus_Data_After($id){
		$err = ''; $content=''; $cek='';
		
		//jika Id di penghapusan_usul dihapus maka Id di penghapusan_usul_detail dan di panitia_pemeriksa harus terhapus
		$qy = "DELETE FROM pemindahtanganan_sk_det WHERE Id = '".$id."' ";	   
		$query = mysql_query($qy);
		
		$qy1 = "DELETE FROM panitia_pemeriksa WHERE ref_idusulan = '".$id."' ";	   
		$query1 = mysql_query($qy1);
		
		return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
	}
	
	function Hapus($ids){
		$err=''; $cek=''; //$cid= $POST['cid'];	//$err = ''.$ids;
		
		//cek sudah ada sk
		for($i = 0; $i<count($ids); $i++){
			$aqry = "select * from pemindahtanganan_sk where Id ='{$ids[$i]}' ";
			$get = mysql_fetch_array(mysql_query($aqry));
			if ($get['no_sk']!='') {
				$err = 'Usulan '.$get['no_usulan'].' tidak bisa dibatalkan! Sudah ada SK Pemindahtanganan';
				break;
			}
		}
		if($err==''){
			for($i = 0; $i<count($ids); $i++)	{
				$err = $this->Hapus_Validasi($ids[$i]);			
				if($err ==''){
					$get = $this->Hapus_Data($ids[$i]);
					$err = $get['err'];
					$cek.= $get['cek'];
					if ($errmsg=='') {
						$after = $this->Hapus_Data_After($ids[$i]);
						$err=$after['err'];
						$cek=$after['cek'];
					}
					if ($err != '') break;
					 				
				}else{
					break;
				}			
			}
		}
		return array('err'=>$err,'cek'=>$cek);
	} 
	
	function setForm($dt){	
		global $SensusTmp, $Main;
		
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
				
		$form_name = $this->Prefix.'_form';	
		$this->form_width = 800;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Usulan SK Pemindahtanganan - Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Usulan SK Pemindahtanganan - Edit';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		/*$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' "));
		$subunit = $get['nm_skpd'];		
		*/
		//ambil pegawai usul Barang
		$read = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$dt['ref_idpegawai_usul']."'"));
					
		//ambil pegawai Kuasa Barang
		//$select = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$dt['ref_idpegawai_usul2']."'"));
		
		if($dt['no_sk'] !=''){			
			$vnousul = $dt['no_usulan'];
			$vtglusul = array( 
				'label'=>'Tgl. Usulan SK',
				'value'=>TglInd($dt['tgl_usul']), 
				'type'=>''
			);
			$vpejabat_pengadaan = array(  
				'label'=>'Petugas Usulan SK', 
				'value'=> $read['nama'].'&nbsp;/&nbsp;NIP.&nbsp;'.$read['nip'],
				'type'=>'' 
			); 
			$vbentuk_pemindahtanganan = array (
			 	'label'=>'Bentuk Pemindahtanganan',
				'value'=> $Main->BentukPemindahtanganan[$dt['bentuk_pemindahtanganan']-1][1],
				'type'=>'' 
			 );
			$Edit = '';
			$Hapus ='';
			$Tambah ='';
			$Simpan ='Usulan Pemindahtanganan ini tidak bisa diedit, sudah ada SK Pemindahtanganan. &nbsp&nbsp';//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan Usulan Penghapusan' style='visibility:hidden'>";
			$Batal = "<input type='button' value='Close' onclick ='".$this->Prefix.".Close()' style='visibility:visible' >";
		}
		else{			
			$vnousul = "<input type='text' name='no_usulan' value='".$dt['no_usulan']."' size='43px'>";
			$vtglusul = array( 
				'label'=>'Tgl. Usulan SK',
				'value'=>$dt['tgl_usul'], 
				'type'=>'date'
			);
			$vpejabat_pengadaan = array(  
				'label'=>'Petugas Usulan SK', 
				'value'=> 
					"<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai_usul']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$read['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$read['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
				,
				'type'=>'' 
			);
			$vbentuk_pemindahtanganan = array (
			 	'label'=>'Bentuk Pemindahtanganan',
				'value'=> cmb2D_v2('bentuk_pemindahtanganan',$dt['bentuk_pemindahtanganan'],$Main->BentukPemindahtanganan,'','Pilih'),
				'type'=>'' 
			);
			$Edit ="<input type='button' value='Tindak Lanjut' onclick ='PindahTanganSKDet.Edit()' title='Edit Barang' style='visibility:visible'>";
			$Hapus ="<input type='button' value='Hapus' onclick ='PindahTanganSKDet.Hapus()' title='Hapus Barang' style='visibility:visible'>";
			$Tambah = "<input type='button' value='Tambah' onclick ='".$this->Prefix.".Cari()' title='Tambah Barang' style='visibility:visible'>";
			$Simpan ="<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan Usulan Penghapusan' style='visibility:visible'>";
			$Batal = "<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' style='visibility:visible'>";
		}
			
		$this->form_fields = array(				
			'no_usulan' => array( 
				'label'=>'No. Usulan SK', 
				 'value'=>$vnousul, 				 
				'type'=>'','labelWidth'=>150 
		    ),
			'tgl_usul' => $vtglusul,
			'pejabat_pengadaan' => $vpejabat_pengadaan,
			'bentuk_pemindahtanganan' => $vbentuk_pemindahtanganan,
			'daftardetail' => array( 
				'label'=>'',
				 'value'=>"<div id='div_detail' style='height:5px'></div>", 
				 'type'=>'merge'
			 )
						 
		);
		//Update 12 Juni 2013
		//Jika No tanda terima dan BA ada Maka Tombol Edit,Hapus,Simpan,Ditampilkan
		//$result =mysql_fetch_array(mysql_query("SELECT no_tterima,no_ba FROM $this->TblName_Hapus WHERE Id ='".$dt['Id']."' "));
		//Edit
		
							
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='sesi' name='sesi' value='".$dt['sesi']."'> ".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			//$Edit.
			$Hapus.
			$Tambah.
			$Simpan.
			"$Batal";
		$form = $this->genForm2();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormTt($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 700;
		$this->form_height = 260;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Usulan Penghapusan - Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Tanda Terima Surat Usulan Penghapusan Barang';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' "));
		$subunit = $get['nm_skpd'];		
				
		//ambil petugas penerima surat usulan
		$get = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$dt['ref_idtterima']."'"));
					
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ,'row_params'=>"style='height:21'"),
			'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ,'row_params'=>"style='height:21'" ),
			'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ,'row_params'=>"style='height:21'" ),
			'no_usulan' => array( 
				'label'=>'No. Surat', 'value'=>$dt['no_usulan'], 
				'labelWidth'=>120, 
				'type'=>'' 
			),
			'tgl_usul' => array( 
				'label'=>'Tanggal Surat',
				'value'=>TglInd($dt['tgl_usul']), 
				'type'=>''
			),
			'no_tterima' => array( 
				'label'=>'No. Tanda Terima',
				'value'=>"<input type='text' name='no_tterima' value='".$dt['no_tterima']."' size='43px' >", 
				'type'=>''
			),
			'tgl_tterima' => array( 
				'label'=>'Tgl. Tanda Terima',
				'value'=>$dt['tgl_tterima'], 
				'type'=>'date'
			),
				 
			
			'petugas_tterima' => array(  
				'label'=>'Petugas Tanda Terima', 
				'value'=> 
					
					"<input type='hidden' id='id_kuasaT' name='id_kuasaT' value='".$dt['ref_idtterima']."'> ".
					"<input type='text' id='nama_pejabat_kuasaT' name='nama_pejabat_kuasaT' readonly=true value='".$get['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_kuasaT' name='nip_pejabat_kuasaT' readonly=true value='".$get['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".petugastterima()\">"
				,
				'type'=>'' 
			)
			
		);
						
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='sesi' name='sesi' value='".$dt['sesi']."'> ".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			//"<a href='javascript:".$this->Prefix.".SimpanTterima()'>tes</a>".			
			//"<a id='ates' onclick='".$this->Prefix.".Cetak_tterima()'>tes</a>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanTterima()' title='Simpan Tanda Terima' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			//"<iframe id='fr' name='fr' style='top:0;left:0;width: 100%;height: 100%;position: fixed;display:none' ></iframe>";
		
		
		$form = $this->genForm();	//khusus form kecil
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function genForm2($withForm=TRUE){	
		$form_name = $this->Prefix.'_form';	
				
		if($withForm){
			$params->tipe=1;
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
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
					$this->form_menu_bawah_height,
					'',$params
					).
				"</form>";
				
		}else{
			$form= 
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
					$this->form_menu_bawah_height
				);
			
			
		}
		
		
		/*$form = 
			centerPage(
				$form
			);*/
		return $form;
	}	
	function setFormCr($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(				
			 'detailcari' => array( 
					'label'=>'',
					 'value'=>"<div id='div_detailcari' style='height:5px'></div>", 
					 'type'=>'merge'
				)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".Pilih()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closecari()' >";
		
		//$form = //$this->genForm();		
		$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
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
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40' rowspan=2>No.</th>
				$Checkbox		
				<th class='th02' colspan=4>SURAT USULAN</th>
				
				
				<!--<th class='th01' rowspan=2>Bidang/OPD/UPTD</th>	-->
				<th class='th02' colspan=2>KIB A</th>								
				<th class='th02' colspan=2>KIB B</th>								
				<th class='th02' colspan=2>KIB C</th>								
				<th class='th02' colspan=2>KIB D</th>								
				<th class='th02' colspan=2>KIB E</th>								
				<th class='th02' colspan=2>KIB F</th>								
				<th class='th02' colspan=2>TOTAL</th>								
				<!--<th class='th01' width=50 rowspan=2>Pengguna /<br>Pengurus Barang</th>
				<th class='th02' width=50 colspan=2>Tandaterima</th>	
				<th class='th01' width=50 rowspan=2>No. BA</th>	-->
				
				<th class='th02' colspan=3>SK GUBERNUR</th>
				
				</tr>
				<tr>
				
				 <th  class='th01'>No.</th>
				 <th  class='th01'>Tanggal</th>
				 <th  class='th01'>Petugas</th>
				 <th  class='th01'>Bentuk <br>Pemindahtanganan</th>
				  
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>
				 <th  class='th01'>Jml</th>
				 <th  class='th01'>Harga (Rp)</th>				 
				 <th  class='th01'>No.</th>
				 <th  class='th01'>Tanggal</th>	
				 <th  class='th01'>Pejabat SK</th>	
				</tr>
				
			</thead>";
		return $headerTable;
	}
	
	//***************************************************
	//* cari jumlah barang dan harga untuk kib berdasar usulan
	//* input id usul, kode f
	//* return 
	//********************************************************
	function cariJml($Id,$kib) {
		// id = id master
		//-- jml buku induk
		$query ="select count(*) AS jml , sum(ifnull(jml_harga,0)+ ifnull(tot_pelihara,0)+ ifnull(tot_pengaman,0) ) AS harga 								 
				 from v2_pemindahtanganan_sk
				 where Id='".$Id."' and f='".$kib."'";
		$rs = mysql_fetch_array(mysql_query($query));
		$hsl->jml = $rs['jml'];
		$hsl->harga = $rs['harga'];		
			
		return $hsl;
	}

	function batalttr()	{
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $cbid = $_REQUEST[$this->Prefix.'_cb']; //ambil data checkbox
	 $idplh = $cbid[0]; //ambil data checkbox
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 //pengecekan No ba,Apakah no ba sudah di usulkan sk
	 $getBA = mysql_fetch_array(mysql_query("select no_ba  from $this->TblName_Hapus where Id = '".$idplh."'"));
		if($getBA['no_ba'] !=""){
		   $err = "No Tandaterima  tidak bisa dibatalkan,no ini sudah ada Berita Acara";
		}
		
		if($fmST == 0){
			if($err==''){
			   $aqry = "update $this->TblName_Hapus
						 set	no_tterima  = NULL,
						  		tgl_tterima  = NULL,
						 	    uid ='$uid',
						     	ref_idtterima =NULL".
						 " where Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
			}
		}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref, $Main;
				
		//--- get dinas 
		$dinas = '';	
			
		$c=''.$isi['c'];
		
		$d=''.$isi['d'];
		
		$e=''.$isi['e'];
		
		$nmopdarr=array();
		//=================== ambil Bidang ================================================			
		$get = mysql_fetch_array(mysql_query("select * from v_bidang where c='".$c."' "));		
			if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
	    //=================================================================================
		
		//================= ambil OPD ================================================================
		$get = mysql_fetch_array(mysql_query("select * from v_opd where c='".$c."' and d='".$d."' "));		
			if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];	
		//============================================================================================
		
		//================= gabungkan ================================================================================		
		$get = mysql_fetch_array(mysql_query("select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."' "));		
			if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];		
		$nmopd = join(' <br/> ', $nmopdarr );
		//=============================================================================================================
		
		/*  
		//=============== get Pegawai Kuasa Barang ===========================================================
		$ks_barang = ''.$isi['ref_idpegawai_usul2']; 
		$tampil =	mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE id='".$ks_barang."'"));	
		$nmkuasabarang = $tampil['nama'];
		//====================================================================================================
				
		//=============== get Pegawai Pengurus Barang ========================================================
		$ref_idpegawai_usul=''.$isi['ref_idpegawai_usul'];
		$get = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE id='".$ref_idpegawai_usul."' "));
		$nmpengurus = $get['nama'];
		//====================================================================================================
		*/						
		//===================cari jml barang & harga KIB======================================================
		$a=array();
		for($i=1;$i<=6;$i++){
			$a[] = $this->cariJml($isi['Id'],'0'.$i);
		}
		
	    //--- cari Total  Jumlah KIB A -Kib F
		$totjml=0;
		for($i=0;$i<=6;$i++){
			$totjml=$totjml+$a[$i]->jml;
		}		
		$vtotjml = $totjml==0?'':$totjml;
		
		
		//--- total Harga KIB A - KIB F
		$tothrg =0;
		for($i=0;$i<6;$i++)
		{
			$tothrg=$tothrg+$a[$i]->harga;
		}
		
		$vtothrg=$tothrg==0?'':number_format($tothrg,2,',','.');
		//$tothrg = $a[0]->harga+$a[1]->harga+$a[2]->harga+$a[3]->harga+$a[4]->harga+$a[5]->harga;
		
		//jika jumlah 0 ditampilkan kosong
		$vjmlA = $a[0]->jml==0?'':number_format($a[0]->jml,0,',','.');
		$vjmlB = $a[1]->jml==0?'':number_format($a[1]->jml,0,',','.');
		$vjmlC = $a[2]->jml==0?'':number_format($a[2]->jml,0,',','.');
		$vjmlD = $a[3]->jml==0?'':number_format($a[3]->jml,0,',','.');
		$vjmlE = $a[4]->jml==0?'':number_format($a[4]->jml,0,',','.');
		$vjmlF = $a[5]->jml==0?'':number_format($a[5]->jml,0,',','.');
		
		//jika Harga 0 ditampilkan kosong		
		$vhargaA =$a[0]->harga==0?'': number_format($a[0]->harga,2,',','.');
		$vhargaB =$a[1]->harga==0?'': number_format($a[1]->harga,2,',','.');
		$vhargaC =$a[2]->harga==0?'': number_format($a[2]->harga,2,',','.');
		$vhargaD =$a[3]->harga==0?'': number_format($a[3]->harga,2,',','.');
		$vhargaE =$a[4]->harga==0?'': number_format($a[4]->harga,2,',','.');
		$vhargaF =$a[5]->harga==0?'': number_format($a[5]->harga,2,',','.');
					
		for($i=0;$i<6;$i++) {
			$this->SumValue[$i*2] += $a[$i]->jml;
			$this->SumValue[$i*2+1] += $a[$i]->harga;
		}
		$this->SumValue[12] += $totjml;
		$this->SumValue[13] += $tothrg;
				
		$get = mysql_fetch_array(mysql_query("select * from ref_pegawai where Id='{$isi['ref_idpegawai_usul']}'"));
		$petugasUsul=$get['nip'].' / '. $get['nama'];
				
		$get = mysql_fetch_array(mysql_query("select * from ref_pegawai where Id='{$isi['ref_idpegawai_sk']}'"));
		$pejabatsk=$get['nip'].' / '. $get['nama'];
			
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['no_usulan']);		
		$Koloms[] = array('', TglInd($isi['tgl_usul']));
		$Koloms[] = array('', $petugasUsul);
		$Koloms[] = array('', $Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1]);		
		$Koloms[] = array('align="right"',$vjmlA); //kib A
		$Koloms[] = array('align="right"', $vhargaA);
		$Koloms[] = array('align="right"', $vjmlB); //kib B
		$Koloms[] = array('align="right"', $vhargaB);
		$Koloms[] = array('align="right"', $vjmlC); //kib C
		$Koloms[] = array('align="right"', $vhargaC);
		$Koloms[] = array('align="right"', $vjmlD); //kib D
		$Koloms[] = array('align="right"', $vhargaD);
		$Koloms[] = array('align="right"', $vjmlE); //kib E
		$Koloms[] = array('align="right"', $vhargaE);
		$Koloms[] = array('align="right"', $vjmlF); //kib F
		$Koloms[] = array('align="right"', $vhargaF);
		$Koloms[] = array('align="right"', $vtotjml); //total
		$Koloms[] = array("align='right'", $vtothrg);
		//$Koloms[] = array('', $nmkuasabarang.'/<br>'.$nmpengurus);
		$Koloms[] = array('', $isi['no_sk']);
		$Koloms[] = array('', TglInd($isi['tgl_sk']));	
		$Koloms[] = array('', $pejabatsk);	
		//$Koloms[] = array('', $isi['no_ba']);	
		//$Koloms[] = array('', $ket);	
		return $Koloms;
	}
	
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$arr = array(
			//array('selectAll','Semua'),
			array('selectUsul','No. Usulan'),	
			array('selectSK','No. SK'),		
			);
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','No. Usulan'),
			array('2','No. SK'),	
			array('3','Tgl. Usulan'),	
			//array('3','Kode Rekening'),		
		);
		
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//tgl bulan dan tahun
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');	
		
		$fmSKPD='04'; $fmUNIT='05'; $fmSUBUNIT='01';
		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx($this->Prefix.'Skpd', '100%', 100, TRUE, $fmSKPD, $fmUNIT, $fmSUBUNIT) . 
			"</td>
			</tr>"."
			<tr><td></table>"."			
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			$cari =
			cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Semua --',''). //generate checkbox
			"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			$vtgl ="
			<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tgl. Usulan </div>".
			createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)."
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			$vOrder=
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Pilih--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>Desc."
					),			
				$this->Prefix.".refreshList(true)"
				);
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			;
				/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		/*$fmSKPD='04'; $fmUNIT='05'; $fmSUBUNIT='01';
		$arrKondisi[] = getKondisiSKPD2('', 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);*/
		$fmPILCARI = $_REQUEST['fmPILCARI'];
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//$fmFiltTglBtw_tgl1_thn = $_REQUEST['fmFiltTglBtw_tgl1_thn'];
		switch($fmPILCARI){
			case 'selectSK': $arrKondisi[] = " no_sk like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectUsul': $arrKondisi[] = " no_usulan like '%$fmPILCARIvalue%'"; break;						
		}
		
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_usul>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_usul<='$fmFiltTglBtw_tgl2'";	
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_ba>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_ba<='$fmFiltTglBtw_tgl2'";	
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " no_usulan $Asc1 " ;break;
			case '2': $arrOrders[] = " no_sk $Asc1 " ;break;
			case '3': $arrOrders[] = " tgl_usul $Asc1 " ;break;
			//case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}
			
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//$Order ="";
		/*//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		*/
		//limit --------------------------------------
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
	
	//convert hari english ke indonesia
	
	function genCetak_tterima($xls= FALSE, $Mode=''){
		global $Main;
		global $Ref;
		
		
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		
		$get = mysql_fetch_array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id ='".$this->form_idplh."' "));
		$no_usulan = $get["no_usulan"];	
		$tgl_usulan = JuyTgl1($get["tgl_usul"]);
		$tgl_terima =explode('-',$get["tgl_tterima"]);
		//$max = count($tgl_terima);
		for($i=0;$i<count($tgl_terima);$i++){
			$tgl_terima[0];
			$tgl_terima[1];
			$tgl_terima[2];
		}
			$y = $tgl_terima[0];
			$m = $tgl_terima[1]; 
			$d = $tgl_terima[2]; 
					
			$mk=mktime(0, 0, 0, $m, $d, $y);
			$dob_disp1=strftime('%Y-%m-%d-%w',$mk);
			$hari =strftime('%w',$mk);
			$month = intval($m) - 1;
			$bln =$Ref->NamaBulan[$month];
			$month =strftime('%m',$mk);
			$year =strftime('%Y',$mk);
			$tgl = $d;
		
		//convert Hari english to Indonesia
		switch($hari){
			case '1':{
				$hari = 'Senin';
				break;
			}
			case '2':{
				$hari = 'Selasa';
				break;
			}
			case '3':{
				$hari = 'Rabu';
				break;
			}
			case '4':{
				$hari = 'Kamis';
				break;
			}
			case '5':{
				$hari = 'Jum\'at';
				break;
			}
			case '6':{
				$dob_disp1 = 'Sabtu';
				break;
			}
			case '0':{
				$hari = 'Minggu';
				break;
			}
		}
		//echo '<br>'.$hari.'<br>';
		
		$nmopdarr=array();	
		//================== ambil Bidang ========================================================
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
		//========================================================================================
		
		//================== ambil OPD =================================================================================
		$select = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $select['nmopd'];
		//==============================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
		//====================================================================================================================================
							
		//get table penghapusan_usul
		$get = mysql_fetch_array(mysql_query("SELECT no_tterima,tgl_tterima 
											  FROM $this->TblName_Hapus 
											  WHERE Id ='".$this->form_idplh."' "));
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body onload=\"window.innerWidth = screen.width;window.innerHeight = screen.height;window.screenX = 0;window.screenY = 0;\">".
			//<div><input id='btprint' type=button value='Cetak' onclick=\"document.getElementById('btprint').style.display='none';window.print();document.getElementById('btprint').style.display='block';\"></div>
			"<div id='divmenuprint' style='background-color: EBEBEB;border-bottom: 1px solid gray;'>
			<table width='100%'><tbody><tr>
			<td style='font-size: 14;font-weight: bold;'>&nbsp;Cetak Tanda Terima</td>
			<td align='right'>
			<input id='btprint' type='button' value='Cetak' 
				onclick=\"document.getElementById('divmenuprint').style.display='none';window.print();document.getElementById('divmenuprint').style.display='block';\" style=''>
			</td></tr></tbody></table>
			</div>".
			"<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:21cm'>
			<table class=\"rangkacetak\" style='width:21cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//content
		//echo'<br><br><br><br><br><br><br><br><br><br><br>';
		echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:Times New Roman,Arial,Helvetica,sans-serif;margin-left:0.9cm">
		 	 <tr>
			  <td><img src="images/administrator/images/kopbaru.jpg" width="100%"></td>
			 </tr>
			 </table>
			';
		echo"<br>";
		echo'
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:Times New Roman,Arial,Helvetica,sans-serif">
		 <tbody>
		  <tr>
		   <td>
			<table style="width:100%" border="0">
			<tbody>
			<tr>
				<td class="judulcetak"><div align="CENTER"  style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">TANDA TERIMA</div></td>
		    </tr>
			<tr>
				<td class="judulcetak"><div align="CENTER"  style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">SURAT USULAN PENGHAPUSAN BARANG</div></td>
			</tr>
			</tbody></table>
			';
		
		echo'<table style="width:100%" border="0">
			<tbody>
			<tr>
				<td align="center" style="font-size:22pt;font-weight:none;font-family:Times New Roman,Arial,Helvetica,sans-serif">Nomor : '.$get['no_tterima'].'</td>
			</tr>
			</tbody></table>
			';
		echo"<br><br><br><br>";
		echo'<table style="width:100%;margin-left:0.9cm" border="0">
			<tbody>
			<tr>
				<td>
				<div align="left" style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">
				Pada hari ini,<b> '.$hari.'</b> tanggal <b>'.bilang($tgl+0).'</b>  bulan <b>'.$bln. '</b> tahun <b> '.bilang($year).'</b> kami Biro 
				</div>
				</td>
		    </tr>
			<tr>
				<td>
				<div align="left" style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">
				 Pengelolaan Barang Daerah Provinsi Jawa Barat	telah menerima surat usulan Penghapusan 
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div align="left" style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">
				  barang dari :
				</div>
				</td>
			</tr>
			</tbody></table>
			';
		
		echo'<br><br><br><br>
			 ';
		echo'
			 <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:0.9cm">
			  <tbody>
			   <tr valign="top">
			    <td style="font-weight:none;font-size:22pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif">BIDANG</td>
				<td style="width:10;font-weight:bold;font-size:22pt;font-family:Arial,Helvetica,sans-serif">:</td>
				<td style="font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">&nbsp'.$read['nmbidang'].'</td> 
			   </tr> 
			    <tr valign="top">
			    <td style="font-weight:none;font-size:22pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif">ASISTEN</td>
				<td style="width:10;font-weight:bold;font-size:22pt">:</td>
				<td style="font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">&nbsp'.$select['nmopd'].' </td>
			   </tr> 
			   <tr valign="top">
			    <td style="width:250px;font-weight:none;font-size:22pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif">BIRO / UPTD/Balai</td>
				<td style="width:10;font-weight:bold;font-size:22pt;font-family:Arial,Helvetica,sans-serif">:</td>
				<td style="font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">&nbsp'.$getAll['nmunit'].' </td> 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:22pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif" width="150">No. Surat</td>
				<td style="width:10;font-weight:bold;font-size:22pt">:</td>
				<td style="font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">&nbsp'.$no_usulan.'</td> 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:22pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif">Tgl. Surat</td>
				<td style="width:10;font-weight:bold;font-size:22pt">:</td>
				<td style="font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">&nbsp'.$tgl_usulan.'</td>
			   </tr> 
			  </tbody></table>					
			';
		echo'<br><br><br><br>';
		echo'<table style="width:100%" border="0">
			<tbody>
			<tr>
				<td><div align="left" style="font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;margin-left:0.9cm">Demikian Tanda Terima Surat Usulan Penghapusan Barang kami buat</div></td>
		    </tr>
			</tbody></table>
			';	
		echo'<br><br><br><br>
			';
		
	    echo"</div>	".			
				$this->PrintTTDterima($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo"
			</td>
			</tr>
			 </tbody>
			 </table>
			</div>
			</form>		
			</body>	
			</html>";
	}

function genCetak_usulan($xls= FALSE, $Mode=''){
		global $Main;
		
		global $Ref;
				
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		
		//$nowdate = JuyTgl1(date("Y-m-d")); //ambil tanggal,bulan dan tahun sekarang 
		
		/*================================================================
		  Untuk Tabel ambil TANAH,PERALATAN DAN MESIN.DLL
		=================================================================*/
		$get =mysql_fetch_array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id ='".$this->form_idplh ."' "));
		//echo 'ID usul ini ='.$get['Id'];
		
		$select =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul_det WHERE Id ='".$get['Id'] ."' "));
		//echo '<br/>ID usul detail ini ='.$get['Id'];
		
		$read =mysql_fetch_array(mysql_query("SELECT* FROM buku_induk WHERE id ='".$select['id_bukuinduk'] ."' "));
		//echo '<br/>ID buku INDUK ini = '.$read['id'];
		
		$getA =mysql_fetch_array(mysql_query("SELECT* FROM kib_a WHERE f ='".$read['f'] ."' "));
		//echo "<br>SELECT* FROM kib_a WHERE f ='".$read['f'] ."' "; 			
		//echo '<br/>f ini = '.$getA['f']=$read['f'] ; 			
		
		$nmopdarr=array(); //inisialisasi
		//============================= ambil Bidang ============================================			
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
		//=======================================================================================
		
		//============================== ambil OPD =================================================================
		$opd = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $opd['nmopd'];
		//==========================================================================================================
		
		//======= cari jml barang & harga KIB ================
		$a=array();
		for($i=1;$i<=6;$i++){
			$a[] = $this->cariJml($get['Id'],'0'.$i);
		}
		//===================================================
		
	    //============== cari Total  Jumlah KIB A -Kib F =====
		$totjml=0;
		for($i=0;$i<=6;$i++){
			$totjml=$totjml+$a[$i]->jml;
		}		
		$vtotjml = $totjml==0?'0':$totjml;
		//echo'<br> TOTAL ini = '.$vtotjml;
		//=======================================================
		
		//=================== total Harga KIB A - KIB F =========
		$tothrg =0;
		for($i=0;$i<6;$i++)
		{
			$tothrg=$tothrg+$a[$i]->harga;
		}
		$vtothrg=$tothrg==0?'0':number_format($tothrg,2,',','.');
		//echo $vtothrg; 
		//=======================================================
		
		//jika jumlah 0 ditampilkan kosong
		$vjmlA = $a[0]->jml==0?'0':number_format($a[0]->jml,0,',','.');
		$vjmlB = $a[1]->jml==0?'0':number_format($a[1]->jml,0,',','.');
		$vjmlC = $a[2]->jml==0?'0':number_format($a[2]->jml,0,',','.');
		$vjmlD = $a[3]->jml==0?'0':number_format($a[3]->jml,0,',','.');
		$vjmlE = $a[4]->jml==0?'0':number_format($a[4]->jml,0,',','.');
		$vjmlF = $a[5]->jml==0?'0':number_format($a[5]->jml,0,',','.');
		
		//jika Harga 0 ditampilkan kosong		
		$vhargaA =$a[0]->harga==0?'0': number_format($a[0]->harga,2,',','.');
		$vhargaB =$a[1]->harga==0?'0': number_format($a[1]->harga,2,',','.');
		$vhargaC =$a[2]->harga==0?'0': number_format($a[2]->harga,2,',','.');
		$vhargaD =$a[3]->harga==0?'0': number_format($a[3]->harga,2,',','.');
		$vhargaE =$a[4]->harga==0?'0': number_format($a[4]->harga,2,',','.');
		$vhargaF =$a[5]->harga==0?'0': number_format($a[5]->harga,2,',','.');
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:21cm'>
			<table class=\"rangkacetak\" style='width:21cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//=========== CONTENT =============================================================================================
		//echo'<br><br><br><br><br><br><br><br><br><br><br>';
		/**echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:Arial,Helvetica,sans-serif;margin-left:0.9cm">
		 	 <tr>
			  <td><img src="images/administrator/images/kop_setda.jpg" width="100%"></td>
			 </tr>
			 </table>
			';
		**/
		echo'<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
		echo'
		<table cellpadding="0" cellspacing="0" border="0" width="92%" style="font-family:Times New Roman,Arial,Helvetica,sans-serif;margin-left:1cm">
		 <tbody>
		  <tr>
		   <td>
			 <table cellpadding="0" cellspacing="0" border="0" width="92%" style="Times New Roman,font-family:Arial,Helvetica,sans-serif">
			  <tbody>
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif">Nomor</td>
				<td style="width:10px;font-weight:none;font-size:18pt">:</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">'.$get['no_usulan'].'</td> 
				<td colspan="1" width="129px""></td> 
				<td  colspan ="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbsp'.ucfirst($get['tempat']).', '.JuyTgl1($get['tgl_usul']).'</td>
			   </tr> 
			    <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;height:0.7cm;font-family:Times New Roman,Arial,Helvetica,sans-serif" >Perihal</td>
				<td style="width:10;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">:</td>
				<td style="width:300px;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">Usulan Penghapusan Barang</td>
				<td colspan="1"></td> 
				<td colspan ="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspKepada Yth.</td> 
			   </tr> 
			   <tr valign="top" height="20pt">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">Lampiran</td>
				<td style="width:10;font-weight:none;font-size:18pt">:</td>
				<td style="font-weight:none;font-size:18pt">-</td> 
				<td colspan="1"></td> 
				<td colspan ="2"  style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspSekretariat Daerah</td> 
			   </tr> 
			    <tr valign="top" height="20pt">
			    <td colspan="4" ></td>
				<td colspan ="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspa.n. Sekretaris Daerah Provinsi Jawa Barat</td> 
			   </tr> 
			    <tr valign="top">
			    <td colspan="4"></td>
				<td colspan="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspAsisten Administrasi</td> 
			   </tr> 
			   <tr valign="top">
			    <td colspan="4"></td>
				<td colspan="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspu.b Kepala Biro Pengelolaan Barang Daerah</td> 
			   </tr> 
			   <tr>
			    <td colspan="4"></td>
				<td colspan="2" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspDi</td> 
			   </tr>
			  <tr>
			    <td colspan="5"  height="20pt"></td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif" align="left">&nbsp&nbspBandung</td> 
			   </tr>
			  </tbody></table>					
			';
		echo'<br><br><br>';
		echo'
			 <table cellpadding="0" cellspacing="0" border="0" width="92%" style="margin-left:7px;font-family:Times New Roman,Arial,Helvetica,sans-serif">
			  <tbody>
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;height:17pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:justify">Dengan Hormat,</td>
				<td colspan="5" width="50"></td> 
		   	   </tr> 
			    <tr valign="top">
			    <td colspan="2" style="font-weight:none;font-size:18pt;height:17pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:justify" width="500 cm">Bersama surat ini, kami bermaksud untuk mengusulkan
				Penghapusan Barang Milik Pemerintah Provinsi Jawa Barat  <br>yang terdiri dari :</td>
				</tr> 
			    </tbody></table>					
			';
		echo'<br><br>';
		echo'
			 <table cellpadding="5" cellspacing="0" border="1px solid" width="92%" style="margin-left:7px;font-family:Times New Roman,Arial,Helvetica,sans-serif">
			  <tbody>
			   <tr valign="top">
			    <td width="5px" style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">No.</td>
			    <td width="250px" style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Jenis Barang</td>
			    <td  width="50px" style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Jumlah <br>Barang</td>
			    <td  width="50px" style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Satuan</td>
			    <td  width="150px" style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Jumlah Harga (Rp)</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">1.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle">Tanah</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlA.'</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Bidang</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaA.'</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">2.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle;">Peralatan dan Mesin</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlB.'</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Unit</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaB.'</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">3.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle">Bangunan dan Gedung</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlC.'</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Bangunan</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaC.'</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">4.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle">Jalan,Irigasi,dan Jaringan</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlD.'</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Unit</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaD.'</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">5.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle">Aset tetap Lainnya</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlE.'</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Unit</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaE.'</td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">6.</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle">Konstruksi dalam pengerjaan</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vjmlF.'</td>
				<td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">Unit</td>
			    <td style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vhargaF.'</td>
			   </tr> 
			   <tr>
			    
			    <td colspan="2"  style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle;text-align:center">Total</td>
			    <td style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">'.$vtotjml.'</td>
			    <td style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="center">-</td>
			    <td style="font-weight:bold;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;vertical-align:middle" align="right">'.$vtothrg.'</td>
			    
			    
			   </tr> 
			   ';
		echo'</tbody></table>';
		
		echo'<br><br>';
		echo'
			 <table cellpadding="0" cellspacing="0" border="0" width="92%" style="margin-left:7px;font-family:Times New Roman,Arial,Helvetica,sans-serif">
			  <tbody>
			   <tr valign="top">
			    <td colspan="3" width="3cm" style="font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif">
				Demikian surat usulan ini kami buat untuk selanjutnya di lakukan proses pengecekan barang</td>
			   </tr>
			   </tbody></table>					
			';
		echo'<br><br>';
	   /** echo"</div>	".			
				$this->PrintTTD2($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";**/
		 echo"</div>	".			
				$this->PrintTTD($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo'
			</td>
			</tr>
			 </tbody>
			 </table>
			</div>
			</form>		
			</body>	
			</html>';
	}

function PrintTTDterima($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, 
	$fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, 
	$NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;
	
	$get = mysql_fetch_array(mysql_query("SELECT no_tterima,tgl_tterima 
											  FROM $this->TblName_Hapus 
											  WHERE Id ='".$this->form_idplh."' "));
	
    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    //$TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
    $TITIMANGSA = "Bandung, " . JuyTgl1($get['tgl_tterima']);
	$cek ='';
	
	$cbid = $_REQUEST[$this->Prefix.'_cb'];
	
	$this->form_idplh = $cbid[0];
	
	//Ambil data di tabel Penghapusan_usul
	$get= mysql_fetch_Array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id = '".$this->form_idplh ."' "));
	
	//ambil data di tabel ref_pegawai berdasarkan ref_idtterima di tabel penghapusan_usul
	$read = mysql_fetch_Array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$get['ref_idtterima']."' "));
    $NIPSKPD1 = $read['nip'];
    $NAMASKPD1 = $read['nama'];
    $JABATANSKPD1 = $read['jabatan'];
    
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
       $JABATANSKPD2 = $isi['jabatan'];
    }
	
	$nmopdarr=array();	
		//================== ambil Bidang ========================================================
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang =$read['nmbidang'];
		//========================================================================================
		
		//================== ambil OPD =================================================================================
		$select = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $select['nmopd'];
			$opd = $select['nmopd'];
		//==============================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
			  $biro = $getAll['nmunit'];		
		//====================================================================================================================================
		
	
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:20pt;margin-left:140px;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:20pt;margin-left:140px;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='Mengetahui,' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='Petugas Penerima'	
			STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIDANG		= "<INPUT TYPE=TEXT VALUE='$bidang' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:11pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:left' size=50 >";
		$vASISTEN		= "<INPUT TYPE=TEXT VALUE='$opd' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIRO		= "<INPUT TYPE=TEXT VALUE='$biro' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='Yang Menyerahkan' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA OPD</span>";
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr valign='top'> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2' ".
					//style='font-weight:none;font-size:22pt;font-family:Times New Roman,Arial,Helvetica,sans-serif'
				">
					<!--$vMENGETAHUI<BR><BR>-->
					<br><br>
					$vJABATAN1
					<!--$vASISTEN-->
					<!--$vBIRO-->
					<BR><BR><BR><BR><BR><BR><BR><BR><BR><br>
					$vNAMA1
					<br>
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td>
				<td align=center width=300 colspan='$cp4'>
					$vTITIKMANGSA<BR> 
					
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR><BR><BR><BR><br>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}

function PrintTTD($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;
	
	$get = mysql_fetch_array(mysql_query("SELECT no_tterima,tgl_tterima 
											  FROM $this->TblName_Hapus 
											  WHERE Id ='".$this->form_idplh."' "));
	
    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    //$TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
    $TITIMANGSA = "Bandung, " . JuyTgl1($get['tgl_tterima']);
	$cek ='';
	
	$cbid = $_REQUEST[$this->Prefix.'_cb'];
	
	$this->form_idplh = $cbid[0];
	
	//Ambil data di tabel Penghapusan_usul
	$get= mysql_fetch_Array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id = '".$this->form_idplh ."' "));
	
	//ambil data di tabel ref_pegawai berdasarkan ref_idtterima di tabel penghapusan_usul
	$read = mysql_fetch_Array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$get['ref_idpegawai_usul2']."' "));
    $NIPSKPD1 = $read['nip'];
    $NAMASKPD1 = $read['nama'];
    $JABATANSKPD1 = $read['jabatan'];
    
	//ambil data di tabel ref_pegawai berdasarkan ref_idtterima di tabel penghapusan_usul
	$candak = mysql_fetch_Array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$get['ref_idpegawai_usul']."' "));
    $NIPSKPD2 = $candak['nip'];
    $NAMASKPD2 = $candak['nama'];
    $JABATANSKPD2 = $candak['jabatan'];
        
	/** old $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
       $JABATANSKPD2 = $isi['jabatan'];
    }**/
	
	$nmopdarr=array();	
		//================== ambil Bidang ========================================================
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang =$read['nmbidang'];
		//========================================================================================
		
		//================== ambil OPD =================================================================================
		$select = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $select['nmopd'];
			$opd = $select['nmopd'];
		//==============================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
			  $biro = $getAll['nmunit'];		
		//====================================================================================================================================
		
	
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:14pt' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='Mengetahui,' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='Pengguna Barang / Kuasa'	STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIDANG		= "<INPUT TYPE=TEXT VALUE='$bidang' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:11pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:left' size=50 >";
		$vASISTEN		= "<INPUT TYPE=TEXT VALUE='$opd' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIRO		= "<INPUT TYPE=TEXT VALUE='$biro' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='Pengurus Barang / Pembantu' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Times New Roman,Arial,Helvetica,sans-serif;text-align:center' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA OPD</span>";
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR><BR>
					$vJABATAN1
					$vASISTEN
					$vBIRO
					<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
					$vNAMA1
					<br>
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td>
				<td align=center width=300 colspan='$cp4'>
					<!--$vTITIKMANGSA<BR> -->
					<br>
					<br>
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}

function PrintTTD2($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;

	$cek ='';
	
	$cbid = $_REQUEST[$this->Prefix.'_cb'];
	
	$this->form_idplh = $cbid[0];
		
    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    $TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
   
   	$get= mysql_fetch_Array(mysql_query("SELECT* FROM $this->TblName_Hapus WHERE Id = '".$this->form_idplh ."' "));
	
	$read = mysql_fetch_Array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$get['ref_idpegawai_usul2']."' "));
    $NIPSKPD1 = $read['nip'];
    $NAMASKPD1 = $read['nama'];
    $JABATANSKPD1 = $read['jabatan'];
	
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
        $JABATANSKPD2 = $isi['jabatan'];
    }
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	$nmopdarr=array();	
		//================== ambil Bidang ========================================================
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang =$read['nmbidang'];
		//========================================================================================
		
		//================== ambil OPD =================================================================================
		$select = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $select['nmopd'];
			$opd = $select['nmopd'];
		//==============================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
			  $biro = $getAll['nmunit'];		
		//====================================================================================================================================
		
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt;font-family:Arial,Helvetica,sans-serifs' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt;text-align:center' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='Mengetahui' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:11pt;text-align:center' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='Pengguna Barang / Kuasa'	STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIDANG		= "<INPUT TYPE=TEXT VALUE='$bidang' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:left' size=50 >";
		$vASISTEN		= "<INPUT TYPE=TEXT VALUE='$opd' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIRO		= "<INPUT TYPE=TEXT VALUE='$biro' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		//$vJABATAN2 	= "<B><INPUT TYPE=TEXT VALUE='YANG MENYERAHKAN' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA dOPD</span>";
		//$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<!--<td width=100 colspan='$cp1'>&nbsp;</td>--> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR> 
					$vJABATAN1<br>
					<!--$vBIDANG<br>-->
					$vASISTEN<br>
					$vBIRO<br>
					<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
					$vNAMA1
					
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp4'>
					
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}

	
}
$PindahTanganSK = new PindahTanganSKObj();


?>