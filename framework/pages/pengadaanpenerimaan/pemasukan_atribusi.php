<?php
	
	include "pages/pengadaanpenerimaan/pemasukan.php";
	$pemasukan_saja = $pemasukan;
 
class pemasukan_atribusiObj  extends DaftarObj2{	
	var $Prefix = 'pemasukan_atribusi';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_satuan'; //bonus
	var $TblName_Hapus = 'ref_satuan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('nama');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'PENGADAAN DAN PENERIMAAN';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pemasukan_atribusi.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'pemasukan_atribusi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'pemasukan_atribusiForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	
	var $stat_barang = array(
		array("1", "SUDAH"),
		array("2", "BELUM"),
	);
	
	function setTitle(){
		return 'BIAYA ATRIBUSI BARANG';
	}
	
	function setMenuEdit(){
		return "";
		/*return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Atribusi", 'Atribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Distribusi", 'Distribusi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Validasi", 'Validasi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png","Posting", 'Posting')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","export_xls.png","Excel", 'Excel')."</td>";*/
	}
	
	function setMenuView(){
		return "";
	}
	
	function SimpanSemua(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $thn_anggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $c1= $_REQUEST['c1nya'];
	 $c= $_REQUEST['cnya'];
	 $d= $_REQUEST['dnya'];
	 $e= $_REQUEST['enya'];
	 $e1= $_REQUEST['e1nya'];
	 
	 $jns_transaksi= $_REQUEST['jns_transaksi'];
	 $pencairan_dana= $_REQUEST['pencairan_dana'];
	 $sumberdana= $_REQUEST['sumberdana'];
	 $cara_bayar= $_REQUEST['cara_bayar'];
	 $stat_barang= $_REQUEST['stat_barang'];
	 $id_penerimaan= $_REQUEST['id_penerimaan'];
	 $prog= $_REQUEST['prog'];
	 $kegiatan= $_REQUEST['kegiatan'];
	 $pekerjaan= $_REQUEST['pekerjaan'];
	 
	 $dokumen_sumber= $_REQUEST['dokumen_sumber'];
	 $tgl_dokumen_bast= explode("-", $_REQUEST['tgl_dokumen_bast']);
	 $tgl_dokumen_bast = $tgl_dokumen_bast[2].$tgl_dokumen_bast[1].$tgl_dokumen_bast[0];
	 $nomor_dokumen_bast= $_REQUEST['nomor_dokumen_bast'];
	 
	 
	 $refid_terima= $_REQUEST['refid_terima'];
	 $pemasukan_atribusi_idplh= $_REQUEST['pemasukan_atribusi_idplh'];
	 
	 if($err == '' && $jns_transaksi =='')$err = "Jenis Transaksi Belum Dipilih !";
	 if($err == '' && $pencairan_dana =='')$err = "Mekanisme Pencairan Dana Belum Dipilih !";
	 if($err == '' && $sumberdana =='')$err = "Sumber Dana Belum Dipilih !";
	 if($err == '' && $cara_bayar =='')$err = "Jenis Pembayaran Belum Dipilih !";
	 if($err == '' && $stat_barang =='')$err = "'Barang Sudah Diterima ?' Belum Dipilih !";
	 if($err == '' && $stat_barang == '1'){
	 	if($err == '' && $prog == '')$err = "Program Belum Diisi !";
	 	if($err == '' && $kegiatan == '')$err = "Kegiatan Belum Diisi !";
	 	if($err == '' && $pekerjaan == '')$err = "Pekerjaan Belum Diisi !";
		if($err == ''){
			$prog = explode(".", $prog);
			$bknya = $prog[0];
			$cknya = $prog[1];
			$dknya = $prog[2];
			$prog = $prog[3];
		}
	 }else{
	 	$prog='';
		$bknya = '';
		$cknya = '';
		$dknya = '';
		$kegiatan='';
		$pekerjaan='';
	 }
	 
	 
	 if($err == '' && $dokumen_sumber =='')$err = "Dokumen Sumber Belum Dipilih !";
	 if($err == '' && $tgl_dokumen_bast =='')$err = "Tanggal Dokumen Belum Isi !";
	 if($err == '' && $nomor_dokumen_bast =='')$err = "Nomor Dokumen Belum Isi !";
	 
	 // CEK ATRIBUSI
	 if($err == ''){
	 	$qry_cekAtr = "SELECT * FROM t_atribusi_rincian WHERE refid_atribusi = '$pemasukan_atribusi_idplh' AND status='0' "; $cek.=$qry_cekAtr;
		$aqry_cekAtr = mysql_query($qry_cekAtr);
		if(mysql_num_rows($aqry_cekAtr) < 1)$err = "Rekening Belanja Atribusi Belum ada !";
	 }	 
								
		if($err==''){
			//UPDATE t_atribusi_rincian
			$qry_upd_rinAtr = "UPDATE t_atribusi_rincian SET sttemp ='0' WHERE status ='0' AND refid_atribusi='$pemasukan_atribusi_idplh' ";$cek.=' | '.$qry_upd_rinAtr;
			$aqry_upd_rinAtr = mysql_query($qry_upd_rinAtr);
			
			//DELETE t_atribusi_rincian
			$qry_del_rinAtr = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='$pemasukan_atribusi_idplh' AND status !='0' ";$cek.=' | '.$qry_del_rinAtr;
			$aqry_del_rinAtr = mysql_query($qry_del_rinAtr);
			
			if($refid_terima == ''){
				$ins_penerimaan = "INSERT INTO t_penerimaan_barang (c1,c,d,e,e1,jns_trans,uid,sttemp, biayaatribusi, bk, ck, dk, p, q) values ('$c1', '$c', '$d', '$e', '$e1', '$jns_transaksi', '$uid','0','1', '$bknya', '$cknya', '$dknya', '$prog', '$kegiatan')";$cek.=" | ".$ins_penerimaan;
				$qry_ins_penerimaan = mysql_query($ins_penerimaan);
				
				$tmpl_penerimaan = "SELECT Id FROM t_penerimaan_barang WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1' AND jns_trans='$jns_transaksi' AND uid='$uid' AND sttemp='0' ORDER BY Id DESC ";$cek.=" | ".$tmpl_penerimaan;
				
				$qry_tmpl_penerimaan = mysql_query($tmpl_penerimaan);
				$daqry_penerimaan = mysql_fetch_array($qry_tmpl_penerimaan);
				$refid_terima=$daqry_penerimaan['Id'];
			}
			
			//UPDATE t_atribusi
			$qry_upd_Atr = "UPDATE t_atribusi SET jns_trans='$jns_transaksi', pencairan_dana='$pencairan_dana', sumber_dana='$sumberdana', cara_bayar='$cara_bayar', status_barang='$stat_barang',bk='$bknya', ck='$cknya', dk='$dknya', p='$prog', q='$kegiatan',  pekerjaan='$pekerjaan', dokumen_sumber='$dokumen_sumber', tgl_sp2d='$tgl_dokumen_bast', no_sp2d='$nomor_dokumen_bast', refid_terima='$refid_terima', uid='$uid', tahun='$thn_anggaran', sttemp='0' WHERE Id='$pemasukan_atribusi_idplh' ";$cek.=' | '.$qry_upd_Atr;
			
			$aqry_upd_Atr = mysql_query($qry_upd_Atr);
			
			//UPDATE t_atribusi_rincian lagi
			$qry_upd_rinAtr1 = "UPDATE t_atribusi_rincian SET refid_terima='$refid_terima' WHERE status ='0' AND refid_atribusi='$pemasukan_atribusi_idplh' AND sttemp='0' ";$cek.=' | '.$qry_upd_rinAtr1;
			$aqry_upd_rinAtr1 = mysql_query($qry_upd_rinAtr1);
			
			//HAPUS DATA t_penerimaan_barang Jika refid_terima berbeda dengan sebelumnya
			$refid_terima_sebelumnya = $_REQUEST['refid_terima_sebelumnya'];
			
			if($refid_terima_sebelumnya != $refid_terima){
				$hapus_penerimaan = "DELETE FROM t_penerimaan_barang WHERE Id='$refid_terima_sebelumnya' ";
				$qry_hapus_penerimaan = mysql_query($hapus_penerimaan);
					
			}
			
		}
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
					
		case 'SimpanSemua':{
			$get= $this->SimpanSemua();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'BatalSemua':{
			$get= $this->BatalSemua();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'tabelRekening':{
			$idplh = $_REQUEST['pemasukan_atribusi_idplh'];
			
			if(addslashes($_REQUEST['HapusData'])==1){	
				$qrydel1 = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='$idplh' AND status='1' ";
				$aqrydel1 = mysql_query($qrydel1);
			}
			$get= $this->tabelRekening();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'InsertRekening':{
			$cek = '';
			$err = '';
			$content = '';
			$uid = $HTTP_COOKIE_VARS['coID'];
			$coThnAnggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
			$idplh = $_REQUEST[$this->Prefix.'_idplh'];
			
			$qrydel = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='$idplh' AND status='1' AND uid='$uid'";
			$aqrydel = mysql_query($qrydel);
			
			if($aqrydel){
				$qry="INSERT INTO t_atribusi_rincian (refid_atribusi, status,uid, sttemp,tahun) values ('$idplh','1','$uid','1','$coThnAnggaran')";$cek.=$qry;
				$aqry = mysql_query($qry);
				if($aqry){
					$content = 1;
				}else{
					$err= 'Gagal !';
				}
			}			
		break;
	    }
		
		case 'updKodeRek':{
			$cek = '';
			$err = '';
			$content = '';
			$uid = $HTTP_COOKIE_VARS['coID'];
			$idplh = $_REQUEST[$this->Prefix.'_idplh'];
			$idrek = $_REQUEST['idrek'];
			$koderek = $_REQUEST['koderek'];
			$jumlahharga = $_REQUEST['jumlahharga'];
			if($jumlahharga < 1 && $err=='')$err='Jumlah Harga Belum Di Isi !';
			
			$qry = "SELECT nm_rekening FROM ref_rekening WHERE concat(k,'.',l,'.',m,'.',n,'.',o) = '$koderek' AND k != '0' AND l != '0' AND m != '0' AND n != '00' AND o != '00'"; $cek.=$qry;
			$aqry = mysql_query($qry);
			
			if(mysql_num_rows($aqry) == 0 && $err=='')$err = "KODE REKENING TIDAK VALID !";
			
			if($err==''){
				$kode = explode(".",$koderek);
				$knya = $kode[0];
				$lnya = $kode[1];
				$mnya = $kode[2];
				$nnya = $kode[3];
				$onya = $kode[4];
				if($_REQUEST['statidrek'] == '1'){
					$qryupd="UPDATE t_atribusi_rincian SET k='$knya',l = '$lnya',m = '$mnya', n= '$nnya',o= '$onya', jumlah='$jumlahharga', status='0' WHERE refid_atribusi='$idplh' AND Id='$idrek'";
				}else{
					$qryupd="INSERT INTO t_atribusi_rincian (k,l,m,n,o,status,refid_atribusi,sttemp,uid,jumlah)values('$knya','$lnya','$mnya','$nnya','$onya','0','$idplh','0','$uid','$jumlahharga')";
					$updq = "UPDATE t_atribusi_rincian SET status = '2' WHERE Id='$idrek'";
					$aupdq = mysql_query($updq); 
				}
				$cek.=" | ".$qryupd;
				$aqryupd = mysql_query($qryupd);
				if($aqryupd){
					$content['koderek'] = "<a href='javascript:pemasukan_ins.jadiinput(`".$idrek."`);' />".$koderek."</a>";
					$content['jumlahnya'] = number_format($jumlahharga,2,",",".");
					$content['idrek'] = $idrek;
					$content['option'] = "
				<a href='javascript:".$this->Prefix.".HapusRekening(`$idrek`)' />
					<img src='datepicker/remove2.png' style='width:20px;height:20px;' />
				</a>";
				}else{
					$err= 'Gagal !';
				}
			}		
		break;
	    }
		
		case 'jadiinput':{
			$cek = '';
			$err = '';
			$content = '';
			$uid = $HTTP_COOKIE_VARS['coID'];
			$idrek = $_REQUEST['idrekeningnya'];
			
			$qry = "SELECT * FROM t_atribusi_rincian WHERE Id='$idrek'";$cek.=$qry;
			$aqry = mysql_query($qry);
			$dt = mysql_fetch_array($aqry);
			
			$content['koderek'] = "
				<input type='text' onkeyup='setTimeout(function myFunction() {".$this->Prefix.".namarekening();},100);' name='koderek' id='koderek' value='".$dt['k'].".".$dt['l'].".".$dt['m'].".".$dt['n'].".".$dt['o']."' style='width:80px;' maxlength='11' />
				"."<input type='hidden' name='idrek' id='idrek' value='".$idrek."' />".
				"<input type='hidden' name='statidrek' id='statidrek' value='".$dt['status']."' />
				<a href='javascript:cariRekening.windowShow(".$dt['Id'].");'> <img src='datepicker/search.png' style='width:20px;height:20px;margin-bottom:-5px;'  /></a>
				";
			
			$content['jumlahnya'] = "<input type='text' name='jumlahharga' id='jumlahharga' value='".intval($dt['jumlah'])."' style='text-align:right;' onkeypress='return isNumberKey(event)' onkeyup='document.getElementById(`formatjumlah`).innerHTML = ".$this->Prefix.".formatCurrency(this.value);' />
							<span id='formatjumlah'></span>";
			$content['idrek'] = $idrek;
			$content['option'] = "
				<a href='javascript:".$this->Prefix.".updKodeRek()' />
					<img src='datepicker/save.png' style='width:20px;height:20px;' />
				</a>";
			$content['atasbutton'] = "<a href='javascript:".$this->Prefix.".tabelRekening()' /><img src='datepicker/cancel.png' style='width:20px;height:20px;' /></a>";
			
				
		break;
	    }
		
		case 'namarekening':{
			$cek = '';
			$err = '';
			$content = '';
			$idrek = $_REQUEST['idrek'];
			$koderek = addslashes($_REQUEST['koderek']);
			
			$qry = "SELECT nm_rekening FROM ref_rekening WHERE concat(k,'.',l,'.',m,'.',n,'.',o) = '$koderek' AND k<>'0' AND l<>'0' AND m<>'0' AND n<>'00' AND o<>'00'"; $cek.=$qry;
			$aqry = mysql_query($qry);
			$daqry = mysql_fetch_array($aqry);
			$content['namarekening'] = $daqry['nm_rekening'];
			$content['idrek'] = $idrek;
			
		break;
	    }
		
		case 'HapusRekening':{
			$cek = '';
			$err = '';
			$content = '';
			$uid = $HTTP_COOKIE_VARS['coID'];
			$idrekei = $_REQUEST['idrekei'];
			$idplh = $_REQUEST[$this->Prefix.'_idplh'];
			
			$qrydel = "UPDATE t_atribusi_rincian SET status='2' WHERE Id='$idrekei'";$cek.=$qrydel;
			$aqrydel = mysql_query($qrydel);
			
			$qrydel1 = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='$idplh' AND status='1' AND uid='$uid'";
			$aqrydel1 = mysql_query($qrydel1);
			
			if(!$aqrydel)$err='Gagal Menghapus Data Rekening Belanja Atribusi';
			if(!$aqrydel1)$err='Gagal Menghapus Data Rekening Belanja Atribusi';
					
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
							setTimeout(function myFunction() {".$this->Prefix.".loading()},100);
							
						setTimeout(function myFunction() {".$this->Prefix.".nyalakandatepicker()},1000);
						setTimeout(function myFunction() {".$this->Prefix.".tabelRekening()},1000);
						});
					</script>";
		return 	
			"<script type='text/javascript' src='js/pengadaanpenerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/pencarian/cariRekening.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pencarian/cariprogram.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pencarian/cariIdPenerima.js' language='JavaScript' ></script>".
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			'.
			$scriptload;
	}
	
	function pageShow(){
		global $app, $Main, $DataOption; 
		
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		$cbid = $_REQUEST['pemasukan_cb'];
		if(addslashes($_REQUEST['YN']) == '1')$cbid[0]='';
		if($DataOption['skpd'] == 1){
			$c1input = '0';
			$cinput = $_REQUEST['pemasukanSKPD2fmSKPD'];
			$dinput = $_REQUEST['pemasukanSKPD2fmUNIT'];
			$einput = $_REQUEST['pemasukanSKPD2fmSUBUNIT'];
			$e1input = $_REQUEST['pemasukanSKPD2fmSEKSI'];
		}else{
			$c1input = $_REQUEST['pemasukanSKPDfmUrusan'];
			$cinput = $_REQUEST['pemasukanSKPDfmSKPD'];
			$dinput = $_REQUEST['pemasukanSKPDfmUNIT'];
			$einput = $_REQUEST['pemasukanSKPDfmSUBUNIT'];
			$e1input = $_REQUEST['pemasukanSKPDfmSEKSI'];
		}
		
		return
		
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".			
		"<html>".
			$this->genHTMLHead().
			"<body >".
			/*"<div id='pageheader'>".$this->setPage_Header()."</div>".
			"<div id='pagecontent'>".$this->setPage_Content()."</div>".
			$Main->CopyRight.*/
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
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
					"<input type='hidden' name='pemasukanSKPDfmUrusan' value='".$c1input."' />".
					"<input type='hidden' name='pemasukanSKPDfmSKPD' value='".$cinput."' />".
					"<input type='hidden' name='pemasukanSKPDfmUNIT' value='".$dinput."' />".
					"<input type='hidden' name='pemasukanSKPDfmSUBUNIT' value='".$einput."' />".
					"<input type='hidden' name='pemasukanSKPDfmSEKSI' value='".$e1input."' />".
					"<input type='hidden' name='databaru' id='databaru' value='".$_REQUEST['YN']."' />".
					"<input type='hidden' name='idubah' id='idubah' value='".$cbid[0]."' />".
					"<input type='hidden' name='pil_jns_trans' id='pil_jns_trans' value='".$_REQUEST['halmannya']."' />".
					
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
			/*'<script src="assets2/js/bootstrap.min.js"></script>'.
			'<script src="assets2/jquery.min.js"></script>'.*/
			"</body>
		</html>"; 
	}	
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				
		//get data 
		$a = "SELECT count(*) as cnt, aa.satuan_terbesar, aa.satuan_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_satuan bb ON aa.satuan_terbesar = bb.nama OR aa.satuan_terkecil = bb.nama WHERE bb.nama='".$this->form_idplh."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "Satuan Tidak Bisa Diubah ! Sudah Digunakan Di Ref Barang.";
		if($err == ''){
			$aqry = "SELECT * FROM  ref_satuan WHERE nama='".$this->form_idplh."' "; $cek.=$aqry;
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
	 $this->form_width = 300;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';			
		$Id = $dt['nama'];			
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'nama' => array( 
						'label'=>'Satuan',
						'labelWidth'=>100, 
						'value'=>$dt['nama'], 
						'type'=>'text',
						'param'=>"style='width:200px;'"
						 ),			
			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
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
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='900'>Satuan</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS, $DataPengaturan,$pemasukan_saja,$DataOption;
	  	 
	 $pemasukan_saja->BersihkanData();
	 
	 
	 if(isset($_REQUEST['databaru'])){
	 		$baru = TRUE;
			
			if(addslashes($_REQUEST['databaru']) == '1' && addslashes($_REQUEST['idubah']) == ''){
				$baru = TRUE;
				$baru_IdTerima = "";
				$baru_IdTerima_Val = "";
				$AND_IdTerima = "";
				
				$c1 = $_REQUEST['pemasukanSKPDfmUrusan'];
			 	$c = $_REQUEST['pemasukanSKPDfmSKPD'];
			 	$d = $_REQUEST['pemasukanSKPDfmUNIT'];
			 	$e = $_REQUEST['pemasukanSKPDfmSUBUNIT'];
			 	$e1 = $_REQUEST['pemasukanSKPDfmSEKSI'];
				
			}else{
				$id_ubahnya = $_REQUEST['idubah'];
				
				$cek_atr = "SELECT * FROM t_atribusi WHERE refid_terima='$id_ubahnya' ";
				$qry_cek_atr = mysql_query($cek_atr);
								
				if(mysql_num_rows($qry_cek_atr) > 0){
					$baru = FALSE;
				}else{
					$baru = FALSE;
					$baru_IdTerima = ", refid_terima, bk,ck,dk,p,q";
					$baru_IdTerima_Val = ", '$id_ubahnya'";
					$AND_IdTerima = " refid_terima='$id_ubahnya' ";
					
					$tmpl_penerimaan = "SELECT * FROM t_penerimaan_barang WHERE Id='$id_ubahnya' ";
					$aqry_tmpl_penerimaan = mysql_query($tmpl_penerimaan);
					$daqry_tmpl_penerimaan = mysql_fetch_array($aqry_tmpl_penerimaan);
					
					$c1 = $daqry_tmpl_penerimaan['c1'];
					$c = $daqry_tmpl_penerimaan['c'];
					$d = $daqry_tmpl_penerimaan['d'];
					$e = $daqry_tmpl_penerimaan['e'];
					$e1 = $daqry_tmpl_penerimaan['e1'];
					
					$qry_InsAt = "INSERT INTO t_atribusi (c1,c,d,e,e1,sttemp,uid, refid_terima) values ('$c1', '$c', '$d', '$e', '$e1', '1', '$uid',$id_ubahnya)"; $cek.=$qry_InsAt;
			 $aqry_InsAt = mysql_query($qry_InsAt);
					
				}
			}
				
				
		if($baru == TRUE){
					 
			 $uid = $HTTP_COOKIE_VARS['coID'];
			 
			 $qry_InsAt = "INSERT INTO t_atribusi (c1,c,d,e,e1,sttemp,uid $baru_IdTerima) values ('$c1', '$c', '$d', '$e', '$e1', '1', '$uid' $baru_IdTerima_Val)"; $cek.=$qry_InsAt;
			 $aqry_InsAt = mysql_query($qry_InsAt);
			 
			
			 $qry_tmpl = "SELECT * FROM t_atribusi WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1' AND uid='$uid' AND sttemp='1' ORDER BY Id DESC";$cek.=" | ".$qry_tmpl;
				
			 $aqry_tmpl = mysql_query($qry_tmpl); 
			 $dt1 = mysql_fetch_array($aqry_tmpl);
			 $dt['Id'] = $dt1['Id'];
			 		
		}
		
		if($_REQUEST['idubah'] != ''){
			$id = $_REQUEST['idubah'];
			$qry_tmpl = "SELECT a.*, b.id_penerimaan FROM t_atribusi a INNER JOIN t_penerimaan_barang b ON a.refid_terima = b.Id WHERE refid_terima='$id'";$cek.=" | ".$qry_tmpl;
			$aqry_tmpl = mysql_query($qry_tmpl);
			
			$dt = mysql_fetch_array($aqry_tmpl);
						
			 $jns_transaksi = $dt['jns_trans'];
			 $pencairan_dana= $dt['pencairan_dana'];
			 $sumberdana = $dt['sumber_dana'];
			 $cara_bayar = $dt['cara_bayar'];
			 $stat_barang = $dt['status_barang'];
			
			//p,q
			$prog = $dt['bk'].".".$dt['ck'].".".$dt['dk'].".".$dt['p'];
			$p= $dt['p'];
			$bknya = $dt['bk'];
			$cknya = $dt['ck'];
			$dknya = $dt['dk'];

			$cariprogmnya = "SELECT *, concat (IF(LENGTH(bk)=1,concat('0',bk), bk),'.', IF(LENGTH(ck)=1,concat('0',ck), ck),'.', IF(LENGTH(dk)=1,concat('0',dk), dk),'.', IF(LENGTH(p)=1,concat('0',p), p),'. ', nama) as v2_nama FROM ref_program WHERE bk='$bknya' AND ck='$cknya' AND dk='$dknya' AND p='$p' AND q='0' ";$cek.=$cariprogmnya;
			$qrycariprogmnya = mysql_fetch_array(mysql_query($cariprogmnya));
			
			$programnya = $qrycariprogmnya['v2_nama'];
						
			$kegiatanDSBL ='';
			$qrykegitan = "SELECT q,concat (IF(LENGTH(q)=1,concat('0',q), q),'. ',nama) as nama FROM ref_program WHERE bk='$bknya' AND ck='$cknya' AND dk='$dknya' AND p='$p' AND q!='0'";
			$kegiatan=$dt['q'];
			
			//PEKERJAAN
			$pekerjaan = $dt['pekerjaan'];	
			
					
			//NO DOKUMEN 			 
			 $dokumen_sumber = $dt['dokumen_sumber'];
			 $tgl_dokumen_bast = explode("-",$dt['tgl_sp2d']);
			 $tgl_dokumen_bast = $tgl_dokumen_bast[2]."-".$tgl_dokumen_bast[1]."-".$tgl_dokumen_bast[0];
			 $nomor_dokumen_bast =  $dt['no_sp2d'];
			 
			 $id_penerimaan = $dt['id_penerimaan'];
			 
			 $disableIpPenerimaan = 'disabled';
			 if($dt['id_penerimaan'] =='')$disableIpPenerimaan='';
			 
			 
			 $refid_terimanya=$id;
			 
			 $bacasaja ='readonly';
			 $disableCariPenerimaan = 'disabled';
			 $disableProgram = 'disabled';
			 $refid_terimanya=$_REQUEST['idubah'];
			 
			 $c1 = $dt['c1'];
			 $c = $dt['c'];
			 $d = $dt['d'];
			 $e = $dt['e'];
			 $e1 = $dt['e1'];
		}else{
			 $id_penerimaan = $dt1['id_penerimaan'];
			 
			 $disableIpPenerimaan = 'disabled';
			 $refid_terimanya=$_REQUEST['idubah'];			
			
			 $jns_transaksi = $_REQUEST['pil_jns_trans'];
			 $pencairan_dana='3';
			 $sumberdana = "APBD";
			 $cara_bayar = '3';
			 $stat_barang = '1';
			 $dokumen_sumber = 'SP2D';
			 $tgl_dokumen_bast = date('d-m-Y');
			 $nomor_dokumen_bast = '';
			 $disableCariPenerimaan = '';
			 
			 $kegiatanDSBL ='disabled';
			 $bacasaja='';
			 $disableProgram = '';
			 $c1 = $_REQUEST['pemasukanSKPDfmUrusan'];
			 $c = $_REQUEST['pemasukanSKPDfmSKPD'];
			 $d = $_REQUEST['pemasukanSKPDfmUNIT'];
			 $e = $_REQUEST['pemasukanSKPDfmSUBUNIT'];
			 $e1 = $_REQUEST['pemasukanSKPDfmSEKSI'];
		}
	 }
	 
		if(!isset($qrykegitan))$qrykegitan = "SELECT q,nama_program_kegiatan FROM ref_programkegiatan WHERE p='00' AND q='00'";
	 
	 
	if($DataOption['skpd'] != '1'){
		$qry4 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='00' AND d='00' AND e='00' AND e1='000'";$cek.=$qry;
		$aqry4 = mysql_query($qry4);
		$data4 = mysql_fetch_array($aqry4);
		$dataC1 = $DataPengaturan->isiform(
					array(
						array(
								'label'=>'URUSAN',
								'name'=>'urusan',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$data4['c1'].'. '.$data4['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
					)
				);
		$WHEREC1 = "c1='$c1' AND";
	}else{
		$dataC1 = '';
		$WHEREC1 = '';
	}
	
	$qry = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='00' AND e='00' AND e1='000'";$cek.=$qry;
	$aqry = mysql_query($qry);
	$data = mysql_fetch_array($aqry);
	
	$qry1 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='00' AND e1='000'";$cek.=$qry1;
	$aqry1 = mysql_query($qry1);
	$data1 = mysql_fetch_array($aqry1);
	
	$qry2 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='$e' AND e1='000'";$cek.=$qry2;
	$aqry2 = mysql_query($qry2);
	$data2 = mysql_fetch_array($aqry2);
	
	$qry3 = "SELECT * FROM ref_skpd WHERE $WHEREC1 c='$c' AND d='$d' AND e='$e' AND e1='$e1'";$cek.=$qry3;
	$aqry3 = mysql_query($qry3);
	$data3 = mysql_fetch_array($aqry3);
	
	
		

	
	$qrysumber_dn = "SELECT nama,nama FROM ref_sumber_dana";$cek.=$qrysumber_dn;
	
	$qrypenyedia = "SELECT id,nama_penyedia FROM ref_penyedia WHERE c1= '$c1' AND c='$c' AND d='$d'";
	
	$qrypenerima = "SELECT Id,nama FROM ref_tandatangan WHERE c1= '$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1'";$cek.=$qrypenerima;
	
	$qry_dokumen_sumber = "SELECT nama_dokumen,nama_dokumen FROM ref_dokumensumber ";
	
		
	$TampilOpt =
			
			
			$vOrder=
			"<input type='hidden' name='c1nya' value='$c1' id='c1nya' />".
			"<input type='hidden' name='cnya' value='$c' id='cnya' />".
			"<input type='hidden' name='dnya' value='$d' id='dnya' />".
			"<input type='hidden' name='enya' value='$e' id='enya' />".
			"<input type='hidden' name='e1nya' value='$e1' id='e1nya' />".
			genFilterBar(
				array(
					$dataC1.
					$DataPengaturan->isiform(
						array(
							array(
								'label'=>'BIDANG',
								'name'=>'bidang',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c.'. '.$data['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'SKPD',
								'name'=>'skpd',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$d.'. '.$data1['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'UNIT',
								'name'=>'unit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e.'. '.$data2['nm_skpd'],
								'align'=>'left',
								'parrams'=>"style='width:400px;' readonly",
							),
							array(
								'label'=>'SUB UNIT',
								'name'=>'subunit',
								'label-width'=>'200px',
								'type'=>'text',
								'value'=>$e1.'. '.$data3['nm_skpd'],
								'parrams'=>"style='width:400px;' readonly",
							),
						)
					)
				
				),'','','').
				genFilterBar(
				array(
					$DataPengaturan->isiform(
						array(
							array(
								'label'=>'TRANSAKSI',
								'name'=>'transaksi',
								'label-width'=>'200px;',
								'value'=>cmbArray('jns_transaksi',$jns_transaksi,$DataPengaturan->jns_trans,"--- PILIH JENIS TRANSAKSI ---", "style='width:300px;'"),
							),
							array(
								'label'=>'MEKANISME PENCAIRAN DANA',
								'name'=>'pencairan_dana',
								'label-width'=>'200px;',
								'value'=>cmbArray('pencairan_dana',$pencairan_dana,$DataPengaturan->arr_pencairan_dana,"--- PILIH MEKANISME PENCAIRAN DANA ---", "style='width:300px;'"),
							),
							array(
								'label'=>'SUMBER DANA',
								'name'=>'sumberdana',
								'label-width'=>'200px;',
								'value'=>cmbQuery('sumberdana',$sumberdana,$qrysumber_dn, "style='width:300px;' ","--- PILIH SUMBER DANA ---"),
							),
							array(
								'label'=>'JENIS PEMBAYARAN',
								'label-width'=>'200px;',
								'value'=>cmbArray('cara_bayar',$cara_bayar,$DataPengaturan->arr_cara_bayar,"--- PILIH JENIS TRANSAKSI ---", "style='width:150px;'"),
							),
							array(
								'label'=>'BARANG SUDAH DITERIMA ?',
								'label-width'=>'200px;',
								'value'=>cmbArray('stat_barang',$stat_barang,$this->stat_barang,"--- PILIH ---", "style='width:150px;' onchange='".$this->Prefix.".konfBarang();'"),
							),
							array(
									'label'=>'ID PENERIMAAN',
									'label-width'=>'200px;',
									'value'=>"<input type='text' name='id_penerimaan' id='id_penerimaan' value='$id_penerimaan' readonly style='width:300px;' /> ".
											"<input type='button' name='cariIdPenerimaan' id='cariIdPenerimaan' $disableCariPenerimaan value='CARI' onclick='".$this->Prefix.".CariIdPenerimaan()' />"
									,
								),
							array(
								'label'=>'PROGRAM',
								'name'=>'program',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='program' value='$programnya' readonly id='program' style='width:500px;' />
									<input type='button' name='progcar' $disableProgram id='progcar' value='CARI' onclick='pemasukan_atribusi.CariProgram()' />
									<input type='hidden' name='prog' value='$prog' id='prog' />
								",
							),
							array(
								'label'=>'KEGIATAN',
								'name'=>'kegiatan',
								'label-width'=>'200px;',
								'value'=>"<div id='dafkeg'>".cmbQuery('kegiatan1',$kegiatan,$qrykegitan,"$kegiatanDSBL style='width:500px;' onchange='document.getElementById(`kegiatan`).value=this.value;' $disableIpPenerimaan",'--- PILIH KEGIATAN ---')."<input type='hidden' name='kegiatan' id='kegiatan' value='$kegiatan' /></div>"
										
								,
							),
							array(
								'label'=>'PEKERJAAN',
								'name'=>'pekerjaan',
								'label-width'=>'140px',
								'type'=>'text',
								'value'=>$pekerjaan,
								'parrams'=>"style='width:500px;' placeholder='PEKERJAAN' $bacasaja",
							),
							array(
								'label'=>'DOKUMEN SUMBER',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>cmbQuery('dokumen_sumber',$dokumen_sumber,$qry_dokumen_sumber," style='width:303px;' ",'--- PILIH DOKUMEN SUMBER ---')
										
								,
							),	
							array(
								'label'=>'TANGGAL DAN NOMOR',
								'name'=>'dokumensumber',
								'label-width'=>'200px;',
								'value'=>"<input type='text' name='tgl_dokumen_bast' id='tgl_dokumen_bast' class='datepicker' value='$tgl_dokumen_bast' style='width:80px;' /> <input type='text' name='nomor_dokumen_bast' id='nomor_dokumen_bast' value='$nomor_dokumen_bast' style='width:255px;' /> "
										
								,						
							),
						)
					)
				
				),'','','').
				genFilterBar(
				array(
					"<span id='inputpenerimaanbarang' style='color:black;font-size:14px;font-weight:bold;'/>INPUT KODE REKENING BELANJA ATRIBUSI</span>",
					
				
				),'','','').
				"<div id='tbl_rekening' style='width:100%;'></div>".
				genFilterBar(
				array(
					
					"<table>
						<tr>							
							<td><span id='selesaisesuai'>".$DataPengaturan->buttonnya($this->Prefix.'.SimpanSemua()','checkin.png','SELESAI','SELESAI','SELESAI')."</span></td>
							<td>".$DataPengaturan->buttonnya($this->Prefix.'.BatalSemua()','cancel_f2.png','TUTUP','TUTUP','TUTUP')."</td>
						</tr>".
					"</table>"
					
				
				),'','','').
				genFilterBar(
				array(
					"<input type='hidden' name='".$this->Prefix."_idplh' id='".$this->Prefix."_idplh' value='".$dt['Id']."' />".
					"<input type='hidden' name='refid_terima' id='refid_terima' value='$refid_terimanya' />".		
					"<input type='hidden' name='refid_terima_sebelumnya' id='refid_terima_sebelumnya' value='$refid_terimanya' />"		
				),'','','')
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
	
	function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		
			$a = "SELECT count(*) as cnt, aa.satuan_terbesar, aa.satuan_terkecil, bb.nama, aa.f, aa.g, aa.h, aa.i, aa.j FROM ref_barang aa INNER JOIN ref_satuan bb ON aa.satuan_terbesar = bb.nama OR aa.satuan_terkecil = bb.nama WHERE bb.nama='".$ids[$i]."' "; $cek .= $a;
		$aq = mysql_query($a);
		$cnt = mysql_fetch_array($aq);
		
		if($cnt['cnt'] > 0) $err = "Satuan ".$ids[$i]." Tidak Bisa DiHapus ! Sudah Digunakan Di Ref Barang.";
		
			if($err=='' ){
					$qy = "DELETE FROM $this->TblName_Hapus WHERE nama='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
						
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
	function tabelRekening(){
		global $DataPengaturan;
			
		$cek = '';
		$err = '';
		$jml_harga=0;
		$datanya='';
				
		$refid_terima = addslashes($_REQUEST[$this->Prefix."_idplh"]);
		$qry = "SELECT a.*,b.nm_rekening FROM t_atribusi_rincian a LEFT JOIN ref_rekening b ON a.k=b.k AND a.l=b.l AND a.m=b.m AND a.n=b.n AND a.o=b.o WHERE a.refid_atribusi = '$refid_terima' AND status != '2' ORDER BY Id DESC";$cek.=$qry;
		$aqry = mysql_query($qry);
		$no=1;
		while($dt = mysql_fetch_array($aqry)){
			if($dt['status'] == '0'){
				$kode = "
					<a href='javascript:".$this->Prefix.".jadiinput(`".$dt['Id']."`,`".$dt['k'].".".$dt['l'].".".$dt['m'].".".$dt['n'].".".$dt['o']."`);' />
						".$dt['k'].".".$dt['l'].".".$dt['m'].".".$dt['n'].".".$dt['o']."
					</a>
					";
				
				$idrek = '';
				
				$jumlahnya = number_format($dt['jumlah'],2,",",".");
				$btn ="
				<a href='javascript:".$this->Prefix.".HapusRekening(`".$dt['Id']."`)' />
					<img src='datepicker/remove2.png' style='width:20px;height:20px;' />
				</a>";
				
				
			}
			
			if($dt['status'] == '1'){
			// DENGAN INPUTAN TEXT
				$kode = "<input type='text' onkeyup='setTimeout(function myFunction() {".$this->Prefix.".namarekening();},100);' name='koderek' id='koderek' value='".$dt['k'].".".$dt['l'].".".$dt['m'].".".$dt['n'].".".$dt['o']."' style='width:80px;' maxlength='11' />"
				."<a href='javascript:cariRekening.windowShow(".$dt['Id'].");'> <img src='datepicker/search.png' style='width:20px;height:20px;margin-bottom:-5px;'  /></a>"
				;
						 
				$idrek = "<input type='hidden' name='idrek' id='idrek' value='".$dt['Id']."' />".
						"<input type='hidden' name='statidrek' id='statidrek' value='".$dt['status']."' />";
				
				$jumlahnya = "
					
							<input type='text' name='jumlahharga' id='jumlahharga' value='".intval($dt['jumlah'])."' style='text-align:right;' onkeypress='return isNumberKey(event)' onkeyup='document.getElementById(`formatjumlah`).innerHTML = ".$this->Prefix.".formatCurrency(this.value);' />
							<span id='formatjumlah'></span>
							
						";
				
				$btn ="
						<a href='javascript:".$this->Prefix.".updKodeRek()' />
							<img src='datepicker/save.png' style='width:20px;height:20px;' />
						</a>
						";
			}
			
			$datanya.="
				<tr class='row0'>
					<td class='GarisDaftar' align='right'>$no</td>
					<td class='GarisDaftar' align='center'>
						<span id='koderekeningnya_".$dt['Id']."' >
							$kode $idrek
						</span>
					</td>
					<td class='GarisDaftar'>
						<span id='namaakun_".$dt['Id']."'>".$dt['nm_rekening']."</span>
					</td>
					<td class='GarisDaftar' align='right'>
						<span id='jumlanya_".$dt['Id']."'>$jumlahnya</span>
					</td>
					<td class='GarisDaftar' align='center'>
						<span id='option_".$dt['Id']."'>$btn</span>
					</td>
				</tr>
			";
			$no = $no+1;
			$jml_harga = $jml_harga+intval($dt['jumlah']);
		}
		
						
					
		$content['tabel'] =
			genFilterBar(
				array("
					<table class='koptable' style='min-width:780px;' border='1'>
						<tr>
							<th class='th01'>NO</th>
							<th class='th01' width='50px'>KODE REKENING</th>
							<th class='th01'>NAMA REKENING BELANJA</th>
							<th class='th01'>JUMLAH (Rp)</th>
							<th class='th01'>
								<span id='atasbutton'>
								<a href='javascript:".$this->Prefix.".BaruRekening()' /><img src='datepicker/add-256.png' style='width:20px;height:20px;' /></a>
								</span>
							</th>
						</tr>
						$datanya
						
					</table>"
				)
			,'','','')
		;
		$content['jumlah'] = 
				$DataPengaturan->isiform(
						array(
								array(
									'label'=>'TOTAL BELANJA',
									'label-width'=>'200px;',
									'name'=>'totalbelanja',
									'value'=>"<input type='text' name='totalbelanja' id='totalbelanja' value='".number_format($jml_harga,2,",",".")."' style='width:150px;text-align:right' readonly /><span id='jumlahsudahsesuai'><input type='checkbox' name='jumlah_sesuai' value='1' id='jumlah_sesuai' style='margin-left:20px;' disabled /><span style='font-weight:bold;color:red;'>TOTAL HARGA SESUAI</span></span>",
									
								),
						)
				);
		$content['atasbutton'] = "<a href='javascript:".$this->Prefix.".tabelRekening()' /><img src='datepicker/cancel.png' style='width:20px;height:20px;' /></a>";
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function BatalSemua(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $coThnAnggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 	 
	 	$qry = "SELECT * FROM t_atribusi WHERE refid_terima='$idplh'";$cek.=$qry;
		$aqry = mysql_query($qry);
		$daqry = mysql_fetch_array($aqry);
		
		if($daqry['sttemp'] == '1'){
								
			//Atribusi Detail -----------------------------------------------------------------------------------
			$hapusatribusi_det = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='".$daqry['Id']."'"; $cek.="| ".$hapusatribusi_det;
			$qry_hapusatribusi_det = mysql_query($hapusatribusi_det);	
						
			//Penerimaan ------------------------------------------------------------------------------------------
			$hapus_atribusi = "DELETE FROM t_atribusi WHERE Id='".$daqry['Id']."'"; $cek.="| ".$hapus_penerimaan;		
			$qry_hapus_penerimaan = mysql_query($hapus_penerimaan);
		}else{
						
			//Atribusi Detail -----------------------------------------------------------------------------------
			$hapuspenerimaan_det = "DELETE FROM t_atribusi_rincian WHERE refid_atribusi='".$daqry['Id']."' AND sttemp='1'"; $cek.="| ".$hapuspenerimaan_det;
			$qry_hapuspenerimaan_det = mysql_query($hapuspenerimaan_det);
			
			$updpenerimaan_det =  "UPDATE t_atribusi_rincian SET status='0' WHERE refid_atribusi='".$daqry['Id']."' AND sttemp='0'"; $cek.='| '.$updpenerimaan_det;
			
			$qry_updpenerimaan_det = mysql_query($updpenerimaan_det);
		}
	  
						
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
}
$pemasukan_atribusi = new pemasukan_atribusiObj();
?>