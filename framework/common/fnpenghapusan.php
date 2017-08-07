<?php

function Penghapusan_Proses(){
	global $Act, $fmIDBARANG, $fmWILSKPD, $fmIDBUKUINDUK, $fmNOREG, $fmTAHUNPEROLEHAN,
			$fmTANGGALPENGHAPUSAN, $fmURAIAN, $fmKET, $fmTAHUNANGGARAN, $fmIsMutasi;
	global $MyField, $fmID, $Penghapusan_Baru, $cidBI, $cidHPS, $fmNMBARANG, $cek;	
	global $fmKondisi, $fmNoSK, $fmTglSK, $fmGambar, $fmGambar_old, $fmUID, $fmApraisal;
	global $fmGambar_BI;
	global $fmTglBuku;
	global $Info, $Main;
	
	if (empty($fmTglBuku)){
		$fmTglBuku = $_POST['fmTglBuku'];
	}
	
	//echo "<br>tglsk=".$fmTglSK;
	$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPENGHAPUSAN,fmTAHUNANGGARAN";	
	$cek .= '<br> act1='. $Act;
	$cek .= '<br> Penghapusan_Baru='. $Penghapusan_Baru;	
	if($Act=="Penghapusan_Simpan"){
		if ($fmApraisal=='' ) {	$fmApraisal = 0;	}
		if(empty($fmIsMutasi)){	$fmIsMutasi = 0;}
		$errmsg = ProsesCekField2($MyField);
		
		//cek tgl hapus				
		if ($errmsg=='' && !cektanggal($fmTANGGALPENGHAPUSAN)){ $errmsg = 'Tanggal Penghapusan salah!'; }		
		if ($errmsg =='' && compareTanggal($fmTANGGALPENGHAPUSAN, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Penghapusan tidak lebih besar dari Hari ini!';				
		if ($errmsg=='' &&  compareTanggal($fmTANGGALPENGHAPUSAN, $fmTglBuku)==0 ){ $errmsg = 'Tanggal Penghapusan tidak lebih kecil dari Tanggal Buku!'; }		
		if ($errmsg=='' && sudahClosing($fmTANGGALPENGHAPUSAN) ) $errmsg = 'Tanggal Pengahapusan harus lebih besar dari Tanggal Closing !';
		
		//cek tgl BA
		if($errmsg==''){
			$idbi = $fmIDBUKUINDUK;
			$cekba = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='$idbi'"
			));
			if($cekba['tgl_ba'] == '') $errmsg = 'Tanggal Berita Acara Perolehan Barang belum diisi! Isi di Penatausahaan - Edit';
		}
		
		if($errmsg == ''){
			$ceksusut = mysql_fetch_array(mysql_query("select tgl as tgl_penyusutan from penyusutan where idbi=$fmIDBUKUINDUK order by Id desc limit 0,1"));
			if($fmTANGGALPENGHAPUSAN <= $ceksusut['tgl_penyusutan']) $errmsg = 'Gagal penghapusan, sudah penyusutan !';
		}
		
		if ($errmsg==''){
			if ($Penghapusan_Baru=='1'){ //baru
				$idbi = $fmIDBUKUINDUK;
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='$idbi'"
				));
				if ($bi['status_barang'] <> 1) $errmsg = 'Gagal penghapusan , status barang bukan inventaris!';
				if ($bi['status_barang'] == 3) $errmsg = 'Barang sudah di Penghapusan!';
				if ($bi['status_barang'] == 4) $errmsg = 'Barang sudah di Pemindah Tanganan!';
				if ($bi['status_barang'] == 5) $errmsg = 'Barang sudah di Tuntutan Ganti Rugi!';
				if ($bi['thn_perolehan'] < 1945 ) $errmsg = 'Tahun Perolehan tidak lebih kecil dari 1945!';
				
			}else{ //edit
				$old = mysql_fetch_array(mysql_query(
					"select * from penghapusan where id='$fmID'"
				));
				$idbi = $old['id_bukuinduk'];
				//$errmsg = 'sudah_mutasi='.$old['sudahmutasi'];
				//cek sudah dimutasi
				if ($errmsg =='' && $old['sudahmutasi']==1) $errmsg = "Gagal Simpan, Barang sudah di mutasi!";
				
			}
			if ($errmsg ==''){
				$pelihara = mysql_fetch_array( mysql_query (
					"select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk = '$idbi'"
				));
				if ($errmsg =='' && (compareTanggal($fmTANGGALPENGHAPUSAN, $pelihara['maxtgl'])==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $pelihara['maxtgl'])==1)  ) $errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pemeliharaan!';
				$pengaman = mysql_fetch_array( mysql_query (
					"select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk = '$idbi'"
				));
				if ($errmsg =='' && (compareTanggal($fmTANGGALPENGHAPUSAN, $pengaman['maxtgl'])==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $pengaman['maxtgl'])==1 ) ) 
					$errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pengamanan!';
				$pemanfaat = mysql_fetch_array( mysql_query (
					"select max(tgl_pemanfaatan) as maxtgl from pemanfaatan where id_bukuinduk = '$idbi'"
				));				
				if ($errmsg =='' && (compareTanggal($fmTANGGALPENGHAPUSAN, $pemanfaat['maxtgl'])==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $pemanfaat['maxtgl'])==1 )  ) 
					$errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pemanfaatan!';						
				
				$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idbi'" ));
				if ($errmsg=='' && (compareTanggal( $fmTANGGALPENGHAPUSAN, $hps['maxtgl']   )==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $hps['maxtgl'])==1) ) $errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Penghapusan Sebagian!';
				
				$hps = mysql_fetch_array(mysql_query(  "select max(tgl) as maxtgl from t_koreksi where idbi = '$idbi'" ));
				if ($errmsg=='' && (compareTanggal( $fmTANGGALPENGHAPUSAN , $hps['maxtgl']  )==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $hps['maxtgl'])==1) ) $errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Koreksi!';
				
				$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penilaian) as maxtgl from penilaian where id_bukuinduk = '$idbi'" ));
				if ($errmsg=='' && (compareTanggal( $fmTANGGALPENGHAPUSAN , $hps['maxtgl']  )==0 || compareTanggal($fmTANGGALPENGHAPUSAN, $hps['maxtgl'])==1) ) $errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Penilaian!';
				
			}
		}
		
		
		if ($errmsg=='' && !($fmTglSK=='' || $fmTglSK=='0000-00-00')&& !cektanggal($fmTglSK)){ $errmsg = "Tanggal SK $fmTglSK salah!"; }		
		if ($errmsg=='' && !($fmTglSK=='' || $fmTglSK=='0000-00-00')&& compareTanggal($fmTglSK, date('Y-m-d'))==2 ){ $errmsg = 'Tanggal SK tidak lebih besar dari Hari ini!'; }		
		
		//if ($errmsg=='' && $fmApraisal=='' ){ $errmsg = 'Nilai Appraisal belum diisi!'; }
		//echo " $fmTANGGALPENGHAPUSAN $fmTglBuku ";
		$cek .= '<br>myfield='.$fmWILSKPD.' - '.$fmIDBARANG.' - '.$fmIDBUKUINDUK.' - '.$fmNOREG.' - '.$fmTAHUNPEROLEHAN.
						' - '.$fmTANGGALPENGHAPUSAN.' - '.$fmKET.' - '.$fmTAHUNANGGARAN;
		
		
		
		if($errmsg==''){
			if($Penghapusan_Baru=="1"){
			// simpan baru
				 $errmsg=Penghapusan_CekdataCutoff('insert',$idplh,$fmTANGGALPENGHAPUSAN,$idbi);	
			}
			if($Penghapusan_Baru=="0"){
			// simpan edit
				$errmsg=Penghapusan_CekdataCutoff('edit',$fmID,$fmTANGGALPENGHAPUSAN,$idbi);
			}
		
		} 
				
		if($errmsg==''){
			$ArBarang 	= explode(".",$fmIDBARANG);
			$ArWILSKPD 	= explode(".",$fmWILSKPD);
			$Penghapusan_Simpan = false;						
			if($Penghapusan_Baru=="1"){ //penghapusan baru
				//Simpan Baru
				$Qry = "insert into penghapusan (a1,a,b,c,d,e,e1,f,g,h,i,j,id_bukuinduk,noreg,thn_perolehan,
					tgl_penghapusan,uraian,ket,tahun, mutasi, kondisi_akhir, nosk, tglsk, gambar, apraisal, tgl_update, uid,staset,idbi_awal)
					values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArWILSKPD[6]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}',
							'$fmIDBUKUINDUK','$fmNOREG','$fmTAHUNPEROLEHAN',
							'".$fmTANGGALPENGHAPUSAN."','$fmURAIAN','$fmKET','$fmTAHUNANGGARAN',$fmIsMutasi,
							'$fmKondisi','$fmNoSK','".$fmTglSK."', '$fmGambar', $fmApraisal, '".TglJamSQL(date("d-m-Y H:i:s"))."', '".$fmUID."','".$bi['staset']."','".$bi['idawal']."'   )";
				//echo "<br>qry=".$Qry;
				$Penghapusan_Simpan = mysql_query($Qry);
				$idhps = mysql_insert_id();
				if ($Penghapusan_Simpan){				
					//simpan akum susut
					$nilai_buku = getNilaiBuku($fmIDBUKUINDUK,$fmTANGGALPENGHAPUSAN,0);
					$nilai_susut = getAkumPenyusutan($fmIDBUKUINDUK,$fmTANGGALPENGHAPUSAN);
					$updatehps = mysql_query("update penghapusan set nilai_buku='$nilai_buku', nilai_susut='$nilai_susut' where Id='$idhps'");
					//jurnal
					if($Main->MODUL_JURNAL) jurnalPenghapusan($idhps,$fmUID, 1);	
					//mutasi
					//if($fmIsMutasi==1) $aqry = mysql_query("insert into mutasi (tgl_usul, c,d,e,e1, tgl_update,uid) value('$fmTANGGALPENGHAPUSAN', '{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArWILSKPD[6]}', now(), $fmUID )");
					
					$UpdateBI = mysql_query("update buku_induk set status_barang='3' where id='$fmIDBUKUINDUK'");
					$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,e1,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArWILSKPD[6]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPENGHAPUSAN)."','Entry Penghapusan','$fmKONDISIBARANG','3')");	
				}
			}
			if($Penghapusan_Baru=="0"){ //penghapusan edit
				$Kriteria = "id='$fmID'";
				$Qry = "
				update penghapusan set 
					tgl_penghapusan = '".$fmTANGGALPENGHAPUSAN."', 
					uraian = '$fmURAIAN', 
					ket = '$fmKET', 
					mutasi='$fmIsMutasi',
					kondisi_akhir = '$fmKondisi',
					nosk = '$fmNoSK',
					tglsk = '".$fmTglSK."', 
					gambar = '$fmGambar',
					apraisal = $fmApraisal,
					tgl_update = '".TglJamSQL(date("d-m-Y H:i:s"))."', 
					uid = '".$fmUID."'   					
					where $Kriteria ";
				//echo "<br>qry".$Qry;
				$Penghapusan_Simpan = mysql_query($Qry);
				if($Penghapusan_Simpan){
					if($Main->MODUL_JURNAL) jurnalPenghapusan($fmID,$fmUID,2);
					//if($fmIsMutasi==1) $aqry = mysql_query("update mutasi 
					//	set (tgl_usul, c,d,e,e1, tgl_update,uid) value('$fmTANGGALPENGHAPUSAN', '{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArWILSKPD[6]}', now(), $fmUID )");
					
					$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,e1,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)
						values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArWILSKPD[6]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPENGHAPUSAN)."','Update Penghapusan','$fmKONDISIBARANG','3')");
				}
			}
			if($Penghapusan_Simpan)	{
				//KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
				//pindahkan gambar--------------------
				/*
				if ($fmGambar != $fmGambar_old && $fmGambar_old != $fmGambar_BI ){
					if (copy('tmp/'.$fmGambar,'gambar/'.$fmGambar)){
						unlink('tmp/'.$fmGambar);
						if($fmGambar_old!=''){ unlink('gambar/'.$fmGambar_old);}
					}else{
						echo 'gagal copy file';
					}
				}*/
				//$Info = "<script>alert('Data telah di ubah dan simpan')</script>";
				$Info = '';
				$Penghapusan_Baru="";
				$Act = '';
			}else{
				$Info .= "<script>alert('Data GAGAL di simpan!')</script>";
			}
		}else{
			//$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan')</script>";
			$Info = "<script>alert('Gagal Simpan Data!. \\n".$errmsg."')</script>";
		}
	
	}

	


	//Proses HAPUS
	//$cidHPS = cekPOST("cidHPS");
	if($Act=="Hapus" && count($cidHPS) > 0){
		//cek sudah mutasi --------------
		$errmsg = '';
		for($i = 0; $i<count($cidHPS); $i++)	{
			$idbi = table_get_value(" select id_bukuinduk from penghapusan where id='{$cidHPS[$i]}'",'id_bukuinduk');
			//echo "idbi = $idbi";
			if( table_get_value(" select sudahmutasi from penghapusan where id='{$cidHPS[$i]}'",'sudahmutasi') == 1 
				&& table_get_value(" select count(id) as cnt from buku_induk where id_lama=$idbi",'cnt') >= 1
			){
				$errmsg = 'Data sudah mutasi tidak dapat dihapus!';
				break;
			}
			
			//cek sudah closing
			$row = mysql_fetch_array(mysql_query(" select c,d,e,e1,tgl_penghapusan from penghapusan where id='{$cidHPS[$i]}'"));
			if($errmsg=='' && sudahClosing($row['tgl_penghapusan'], $row['c'], $row['d'], $row['e'], $row['e1']) ) $errmsg = 'Tanggal Penghapusan harus lebih besar dari Tanggal Closing !';
		}
		
		
		
		//hapus ---------------------
			
		if ($errmsg==''){
		
		for($i = 0; $i<count($cidHPS); $i++)	{
			/*$cekIdBI = mysql_fetch_array(mysql_query("select id_bukuinduk from penghapusan where id='{$cidHPS[$i]}'"));
			$cekIdBI = $cekIdBI[0];
			$Del = mysql_query("delete from penghapusan where id='{$cidHPS[$i]}' limit 1");
			$UpdateBI = mysql_query("update buku_induk set status_barang='1' where id='$cekIdBI'");*/
			$xid=$cidHPS[$i];
			if ($errmsg=='') $errmsg=Penghapusan_CekdataCutoff('hapus',$xid,'');
			if ($errmsg==''){
				$aqry = "select a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg,staset from penghapusan where id='{$cidHPS[$i]}'";	//echo "<br>aqry=".$aqry;
				$kdbrg = mysql_fetch_array(mysql_query($aqry));
				$aqry="delete from penghapusan where id='{$cidHPS[$i]}' limit 1"; //echo "<br>aqry=".$aqry;
				$Del = mysql_query($aqry);
				$Kondisibrg = 		"
					a1= '{$kdbrg['a1']}' and 
					a = '{$kdbrg['a']}' and 
					b = '{$kdbrg['b']}' and 
					c = '{$kdbrg['c']}' and 
					d = '{$kdbrg['d']}' and 
					e = '{$kdbrg['e']}' and 
					e1 = '{$kdbrg['e1']}' and 
					f = '{$kdbrg['f']}' and 
					g = '{$kdbrg['g']}' and 
					h = '{$kdbrg['h']}' and 
					i = '{$kdbrg['i']}' and 
					j = '{$kdbrg['j']}' and 
					tahun = '{$kdbrg['thn_perolehan']}' and
					noreg = '{$kdbrg['noreg']}'  
					 ";
				$staset=$kdbrg['staset'];
				$aqry = "update buku_induk set status_barang='1',staset='$staset' where $Kondisibrg "; //echo "<br>aqry=".$aqry;
				$UpdateBI = mysql_query($aqry);
				if($UpdateBI){
					if($Main->MODUL_JURNAL) jurnalPenghapusan($cidHPS[$i],'',3);
				}
				$Info = "<script>alert('Data telah di hapus')</script>";
			} else {
				$Info = "<script>alert('$errmsg')</script>";
			}
		}
		}else{
			$Info = "<script>alert('$errmsg')</script>";
		}
	}
	
	$cek ="";

}

function Penghapusan_TampilForm(){
	global $Act, $cidNya, $Main;
	global $fmIDBARANG, $fmNMBARANG, $fmNOREG, $fmTANGGALPENGHAPUSAN, $fmIsMutasi, $fmKondisi, $fmApraisal,
			$fmNoSK, $fmTglSK, $fmURAIAN, $fmKET;
	global $fmIDBUKUINDUK, $fmIdAwal;
	global $dok_scriptUpload, $dok_tampilMenu, $dok_tampilList;
	global $fmTglBuku;
	//global $FmEntryGbr, $FormEntry;
	$kriteria = $_REQUEST['kriteria'];
	//echo "fmIDBUKUINDUK=$fmIDBUKUINDUK , fmIdAwal=$fmIdAwal";
	
	$fmTglBuku = $fmTglBuku==''? $_POST['fmTglBuku']: $fmTglBuku;
	//echo "fmKondisi = $fmKondisi ";
	//dokum --------------------------
	
	$idbidok = $fmIdAwal =='' || $fmIdAwal=='0'? $fmIDBUKUINDUK : $fmIdAwal; 
	//echo "fmIDBUKUINDUK=$fmIDBUKUINDUK , fmIdAwal=$fmIdAwal , idbidok=$idbidok";
		Dok_Page($idbidok,1);
		$entry_dok="
			$dok_scriptUpload 	
			$dok_tampilMenu			
			$dok_tampilList	
			";
		$entry_dok = "
			<table style= 'background-color:white;width:345;border-bottom-color: #CCC;
				border-bottom-style: solid; border-bottom-width: 1px; border-collapse: separate;
				border-left-color: #CCC; border-left-style: solid; border-left-width: 1px;
				border-right-color: #CCC;border-right-style: solid;border-right-width: 1px;
				border-top-color: #CCC;border-top-style: solid;border-top-width: 1px;height:120'>		
			<tr valign='top'><td style='padding:2'>				
				<div style='position:relative'>
				$entry_dok
				</div>
			</td>
			</tr>
			</table>
			
			";		
		$entryDok = formEntryBase2('File Dokumen (Max. 50Mb)',':',$entry_dok,'','','','valign="top" height="24"');
		
	
	
	//gambar -------------------------
	//$idbigambar = $fmIDBUKUINDUK;//$fmIdAwal	==''? $fmIDBUKUINDUK: $fmIdAwal;
	/*$idbigambar = $fmIdAwal =='' || $fmIdAwal=='0'? $fmIDBUKUINDUK : $fmIdAwal;
	Gbr_Proses( $idbigambar, $_POST['FmEntryGbr_fmKET'], $_POST['FmEntryGbr_idgbr'] ,
					$_POST['FmEntryGbr_fmKET2'], "FmEntryGbr", $_POST['FmEntryGbr_LimitAw']);	//echo"<br>Act=$Act";
					
	$LastGbr = $Act == 'FmEntryGbr1';		
	$entryGambar = 
		formEntryBase2('Gambar Barang (Max. 500Kb)',':', 
					"<table style='position: border-collapse: separate; border-color: #CCC; border-style: solid; border-width: 1px;
						background-color:white;padding:0;'><tr><td style='padding:2'>
						".Gbr_GetList( '', "", "FmEntryGbr" , $LastGbr, 
								300, 200, 30, TRUE,	300, 200, 
								36, $idbigambar, $_POST['FmEntryGbr_LimitAw'] )."
						</td></tr></table>"
		
						,'','','','valign="top" height="24"');*/
	$idbigambar = $fmIdAwal =='' || $fmIdAwal=='0'? $fmIDBUKUINDUK : $fmIdAwal;
		//echo "$currID $fmIdAwal $idbidok $idbigambar <br>";
		Gbr_Proses( 
				$idbigambar, $_POST['FmEntryGbr_fmKET'], $_POST['FmEntryGbr_idgbr'] ,
				$_POST['FmEntryGbr_fmKET2'], "FmEntryGbr", $_POST['FmEntryGbr_LimitAw']
			);	//echo"<br>Act=$Act";
	$FormName1 = 'FmEntryGbr';
	$entryGambar =		
		formEntryBase2(
					'Gambar Barang (Max. 500Kb)',':', 
					"<a name='FmEntryGbr' style='position:relative;top:-100;'></a>".
					"<script src='js/jquery.js' type='text/javascript'></script>".
					"<input type='hidden' id='FormName' name='FormName' value=''>".
					"<input type='hidden' id='idbi' name='idbi' value='$idbigambar'>".
					createImgEntry($FormName1, '300', '200', '30', 1)					
					,'','','','valign="top" height="24"');
	//if($Act == "Penghapusan_Edit"){	
	$vfmIsMutasi = $Act == "Penghapusan_Edit" ? 
		$Main->ArKriteriaHapus[$fmIsMutasi][1]  :
		cmb2D_v2('fmIsMutasi',$fmIsMutasi,$Main->ArKriteriaHapus,'',"Pilih");
	switch($kriteria){
		case '1': $titleAct = 'Penghapusan Karena Mutasi'; break;
		case '4': $titleAct = 'Penggabungan'; break;
		case '5': $titleAct = 'Koreksi Double Catat'; break;
		default : $titleAct = "Penghapusan"; break;
	}
	if($kriteria <>''){
		$entryKriteria = "";
		$hideKriteria = "<input type=hidden name='fmIsMutasi' id='fmIsMutasi' value='$kriteria'>";
		$entryHrgAppraisal = "";
		$titleSK = "Dasar Penghapusan";
	}else{
		$entryKriteria = "<tr valign=\"top\" height='24'>
	  		<td>Kriteria Penghapusan</td>
	  		<td>:</td>
	  		<td>			
			".$vfmIsMutasi."			
			</td>
			</tr>";
		$hideKriteria = "";
		$entryHrgAppraisal = formEntryBase2('Harga Appraisal Barang ',':',
							'Rp.'.inputFormatRibuan("fmApraisal"),'','','','valign="top" height="24"');
		$titleSK = "Dasar SK Penghapusan";
	}
	
	return  "

		<br>
		<A NAME='FORMENTRY'></A>
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr><td colspan=3 height='40'><div style='float:left;display;block;'><span style='font-size: 18px;font-weight: bold;color: #C64934;'>".$titleAct."</span></div></td></tr>
			<tr><td width='150'></td><td width='10'></td><td></td></tr>
			
			<TR valign=\"top\">
			<td>Nama Barang</td>
			<td>:</td>
			<td>
			".txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ')."
			".txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly ')."
			</td>
			</tr>
			
			<TR valign=\"top\">
			<td>Nomor Register</td>
			<td>:</td>
			<td>".txtField('fmNOREG',$fmNOREG,'6','4','text',' readonly ')."
			$hideKriteria
			</td>
			</tr>".
		
			
			
			formEntryBase2('Tanggal Penghapusan',':',
			createEntryTgl('fmTANGGALPENGHAPUSAN',$fmTANGGALPENGHAPUSAN, false)//createEntryTgl('fmTANGGALPENGHAPUSAN', false)
			,'','','','valign="top" height="24"').
			
			
			$entryKriteria.
			
			"<tr valign=\"top\">
	  		<td>Kondisi Akhir Barang</td>
	  		<td>:</td>
	  		<td>			
			".cmb2D_v2('fmKondisi',$fmKondisi,$Main->KondisiBarang,'',"Pilih Kondisi")."			
			</td>
			</tr>
			
			".$entryHrgAppraisal.	
			
			
			"$entryDok
			
			<!--
			<input type=hidden name='fmGambar_BI' value='$fmGambar_BI'>
			".formEntryGambar('fmGambar', 'fmGambar_old',$fmGambar, $fmGambar_old,'Gambar Barang (Max. 500Kb)',':','','','','valign="top" height="24"')."
			-->
			
			$entryGambar ".
			
			
			"<tr valign=\"top\" height=24>
			<td colspan=3>$titleSK</td>
			</tr>
			
			".formEntryBase2('&nbsp;&nbsp;&nbsp; No ',':',
			txtField('fmNoSK',$fmNoSK,'30','20','text','')
			,'','','','valign="top" height="24"')."
			
			".formEntryBase2('&nbsp;&nbsp;&nbsp; Tanggal',':',
			createEntryTgl('fmTglSK', $fmTglSK, false) //createEntryTgl('fmTglSK', false)			
			,'','','','valign="top" height="24"').
			
			
			
			"
			<!--
			<tr valign=\"top\">
	  		<td>Uraian Penghapusan Barang</td>
	  		<td>:</td>
	  		<td><textarea name=\"fmURAIAN\" cols=\"60\" >$fmURAIAN</textarea></td>
			</tr>-->
			
			<tr valign=\"top\">
	  		<td>Keterangan</td>
	  		<td>:</td>
	  		<td><textarea name=\"fmKET\" cols=\"60\" >$fmKET</textarea></td>
			</tr>
		</table>
		
		<br>
		<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<script>
				function Penghapusan_Simpan(){
					document.body.style.overflow='hidden';
					addCoverPage('coverpage',100);
					adminForm.ViewEntry.value=0;
					adminForm.ViewList.value=1;
					adminForm.Act.value='Penghapusan_Simpan';
					adminForm.submit();
				}
			</script>
			<table width=\"50\"><tr>
				<td>
				".PanelIcon1("javascript:Penghapusan_Simpan()","save_f2.png","Simpan")."
				</td>
				<td>
				".PanelIcon1("javascript:window.close()","cancel_f2.png","Batal")."
				<!--".PanelIcon1("javascript:adminForm.ViewEntry.value=0;adminForm.ViewList.value=1;adminForm.submit()","cancel_f2.png","Batal")."-->
				</td>
			</tr></table>
		</td></tr>
		</table>
		<!--<input type=text name='idbi' id='idbi' value = '".$cidNya[0]."'>-->
		<input type=hidden name='fmTglBuku' id='fmTglBuku' value='$fmTglBuku' >
	";
}

function Penghapusan_GetData($idHps){
	global $Act, $errmsg;//, $cidNya;
	global $fmIDBARANG, $fmWILSKPD, $fmIDBUKUINDUK, $fmNOREG, $fmTAHUNPEROLEHAN,
			$fmTANGGALPENGHAPUSAN, $fmURAIAN, $fmKET, $fmTAHUNANGGARAN, $fmIsMutasi;
	global $MyField, $fmID, $Info, $Penghapusan_Baru, $cidBI, $cidHPS, $fmNMBARANG, $cek;	
	global $fmKondisi, $fmNoSK, $fmTglSK,  $fmUID, $fmApraisal;
	global $fmGambar, $fmGambar_old;
	global $fmIdAwal, $fmTglBuku;
	
	
	//echo $Act;
	//if($Act=="Penghapusan_Edit"){
	//if( ){
		
	$Qry = mysql_query("select * from v_penghapusan_bi where id=$idHps"); //$Qry = mysql_query("select * from penghapusan where id=$idHps");
	//}else{
	//			$Qry = mysql_query("select * from buku_induk where buku_induk.id='{$cidNya[0]}'");				
	//}
			$isi = mysql_fetch_array($Qry);
			
			$errmsg='';
			//if ($Act!="Penghapusan_Edit" && $isi['status_barang']=='3' ){	$errmsg='Pilih Barang dengan Status = Inventaris';	}
			
			if ($errmsg == ''){				
			//echo 'tes3';
			
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
				$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
				$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j']; 
				$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
				$fmNMBARANG = "{$nmBarang['nm_barang']}";
				$fmTAHUNANGGARAN = "{$isi['tahun']}";
				$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
				$fmNOREG = $isi['noreg'];				
				$fmKondisi = $isi['kondisi_akhir'];
				
				//if($Act=="Penghapusan_Edit"){//edit
					$fmIDBUKUINDUK=$isi['id_bukuinduk'];
					$fmIsMutasi = $isi['mutasi']; 
					$fmNoSK = $isi['nosk']; 
					$fmTglSK = $isi['tglsk'];
					$fmIdAwal = table_get_value('select idawal from buku_induk where id='.$isi['id_bukuinduk'],'idawal');
					$fmTglBuku = $isi['tgl_buku'];
				//}else{//baru
				//	$fmIDBUKUINDUK=$isi['id'];
				//	$fmIsMutasi = 0;
				//}
				$fmID = "{$isi['id']}";

				//if($Act == "Penghapusan_TambahEdit"){//baru
				//	$Penghapusan_Baru=1;
				//}else{//edit
				
					$fmTANGGALPENGHAPUSAN = $isi['tgl_penghapusan'];
					$fmURAIAN = $isi['uraian'];
					$fmKET = $isi['ket'];
					$fmApraisal = $isi['apraisal'];
					$Penghapusan_Baru=0;
				//}
			}
			/*else{
				$Act='';
				$Info = "<script>alert('".$errmsg."')</script>";
			}*/
}

function Penghapusan_GetDataFromBI($idBi){
	global $errmsg;
	global $fmWILSKPD, $fmIDBARANG, $fmNMBARANG, $fmTAHUNANGGARAN, $fmTAHUNPEROLEHAN, 
		$fmNOREG, $fmKondisi, $fmIDBUKUINDUK, $fmIsMutasi, $fmID, $fmIdAwal;
	global $fmTglBuku;//, $gambar;
	
	
	$Qry = mysql_query("select * from buku_induk where id=$idBi");	
	$isi = mysql_fetch_array($Qry);
	
	if ($isi['status_barang']=='3' ){	$errmsg='Pilih Barang dengan Status = Inventaris';	}
	
	if ($errmsg == ''){
		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'];
		$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j']; 
		$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmTAHUNANGGARAN = "{$isi['tahun']}";
		$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
		$fmNOREG = $isi['noreg'];				
		$fmKondisi = $isi['kondisi'];
		
		$fmIDBUKUINDUK= $isi['id'];
		$fmIdAwal = $isi['idawal'];
		$fmIsMutasi = 0;
		$fmTglBuku = $isi['tgl_buku'];
		//$gambar = $isi['gambar']; // get data gambar default utk mutasi
		
		//$fmID = "{$isi['id']}";
		
	}
	/*else{
		$Act='';
		$Info = "<script>alert('".$errmsg."')</script>";				
	}*/
	
}

function Penghapusan_GetFormEntry(){
	global $fmIDBARANG, $fmNMBARANG, $fmNOREG, $fmTANGGALPENGHAPUSAN, $fmURAIAN, $fmKET, $fmIsMutasi;
	global $fmKondisi, $fmNoSK, $fmTglSK, $fmGambar, $fmGambar_old, $fmApraisal;
	global $Act, $ViewEntry, $ViewList, $Act2, $Info;
	global $Penghapusan_Baru, $cidBI, $cidHPS, $fmID, $fmTAHUNANGGARAN, $fmTAHUNPEROLEHAN, $fmIDBUKUINDUK, $fmWILSKPD;
	global $Pg, $SPg, $Info,$cek, $Main;
	global $fmGambar_BI;
	global $cidNya, $errmsg;
	global $fmIdAwal, $fmTglBuku;
	//echo "<br>fmismutasi=".$fmIsMutasi;
	
	
	//echo 'tes2';	echo $Act;
	//get ID: if Edit -> ID = ID hp, jika TambahEdit (baru) -> id = id Bi	
	if($Act == "Penghapusan_Edit"){	
		$cidNya = $cidHPS;
	}else{
		if (empty($cidBI)){
			$cidNya[0] = $_POST['idbi'];		
		}else{
			$cidNya = $cidBI;		
		}
	}	
	//$cek .= '<br> count cid nya= '. count($cidNya);
	//echo $cidNya[0];
	if(count($cidNya) != 1 && $Act=="Penghapusan_Edit"){
		$Act='';
		$errmsg= 'Pilih hanya satu ID yang dapat di Ubah!';
	}elseif( table_get_value("select sudahmutasi from penghapusan where id='{$cidNya[0]}' ", "sudahmutasi") == 1 && $Act=="Penghapusan_Edit"){
		$errmsg = 'Data sudah mutasi tidak dapat diedit!';		
	}
	//ambil data --------------------------------------------------------------------
	if($Act=="Penghapusan_Edit"|| $Act == "Penghapusan_TambahEdit" ){
		
		if ($errmsg == ''){
			if($Act=="Penghapusan_Edit"){
				Penghapusan_GetData($cidNya[0]);
			}else{
				Penghapusan_GetDataFromBI($cidNya[0]);
			}			
			
			//echo "fmidbg = $fmIDBARANG";
			//echo "fmKondisi = $fmKondisi ";
		}
	}
	
	
	//tampil form --------------------------------------------------------------
	if ($errmsg == ''){
		
	
	//if($Act=="Penghapusan_Simpan" || $Act=="Penghapusan_Baru" || $Act=="Tambah" || $Act=="Penghapusan_TambahEdit"|| 
	//	$Act=="Add"|| ($Act=="Penghapusan_Edit" && !empty($fmID)) || $Act2=='Penghapusan'){
	//echo 'tes4';
	
		$FormEntry = Penghapusan_TampilForm();
		//gambar -------------------------
	
	};
	
	if ($errmsg != ''){
		$Act='';
		//$PageMode=1;//list
		$ViewList=1;
		$ViewEntry= 0;
		$Info = "<script>alert('".$errmsg."'); window.close()</script>";
	}
	
	$cek = '';
	return $FormEntry.$cek;
}

function Penghapusan_daftar($cetak= FALSE ){
	global $Main, $cek, $Pg, $SPg, $cidHPS ;
	global $HalHPS,$fmKEPEMILIKAN;	
	global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
	global $fmUNIT,$fmSKPD, $fmSUBUNIT,$fmSEKSI;
	global $fmBARANGCARIHPS, $fmTahunPerolehan, $SSPg, $jmlDataHPS;
	global $fmFiltThnHapus;
	global $odr1, $AcsDsc1;
	global $fmFiltThnBuku;
	

	//kondisi -----------------------------------------------------------	
	$Kondisi = getKondisiSKPD();	

	$mutasi = $_REQUEST['mutasi']==""?0:$_REQUEST['mutasi'];
	$Kondisi .= " and mutasi = '$mutasi' ";
	
	if(!empty($fmBARANGCARIHPS)){
		$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIHPS%' ";
	}	
	
	$kode_barang = $_REQUEST['kode_barang'];
	if(!empty($kode_barang)){
		$Kondisi .= " and concat(f,'.',g,'.',h,'.',i,'.',j,'.') like '$kode_barang%' ";
	}

	$kriteria = $_REQUEST['kriteria'];
	if(!empty($kriteria)) {
		switch($kriteria){
			//case '1' : $Kondisi .= " and mutasi='0'  "; break;
			//case '2' : $Kondisi .= " and mutasi='1' "; break;
			case '3' : $Kondisi .= " and sudahmutasi=0 "; break;//$Kondisi .= " and mutasi='1' and sudahmutasi=0 "; break;
			case '4' : $Kondisi .= " and sudahmutasi=1 "; break;//$Kondisi .= " and mutasi='1' and sudahmutasi=1 "; break;
			//case '5' : $Kondisi .= " and mutasi='2'  "; break;
		}
		
	}
	
	
	
	//if(!empty($fmTahunPerolehan)){
	//	$Kondisi .= " and penghapusan.thn_perolehan = '$fmTahunPerolehan' ";
	//}
	switch($SSPg){
		case '03': break;
		case '04': $Kondisi .= " and f='01' "; break;
		case '05': $Kondisi .= " and f='02' "; break;
		case '06': $Kondisi .= " and f='03' "; break;
		case '07': $Kondisi .= " and f='04' "; break;
		case '08': $Kondisi .= " and f='05' "; break;
		case '09': $Kondisi .= " and f='06' "; break;	
		case '10': $Kondisi .= " and f='07' "; break;	
	}
	if(!empty($fmFiltThnHapus)){ $Kondisi .= " and year(tgl_penghapusan) =  $fmFiltThnHapus"; }
	if(!empty($fmFiltThnBuku)){ $Kondisi .= " and year(tgl_buku) =  $fmFiltThnBuku"; }
	$id_tujuan = $_REQUEST['id_tujuan'];
	if(!empty($id_tujuan)){
		$idl = mysql_fetch_array(mysql_query("select id_lama from buku_induk where Id = $id_tujuan"));
		$id_lama = $idl['id_lama'];
		$Kondisi .= " and id_bukuinduk = '$id_lama' ";
	}
	$id_barang = $_REQUEST['id_barang'];
	if(!empty($id_barang)){ $Kondisi .= " and id_bukuinduk = '$id_barang'"; }
	
	//urutkan ------------------------------------------------------------
	$Asc1 = !empty($AcsDsc1)? " desc ":"";
	if(!empty($odr1)){$Urutkan .= " $odr1 $Asc1, ";}
	$Orderby = " order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";

	
	//jml data  & limit hal ------------------------------------------
	$aqry = "select v_penghapusan_bi.*, ref_barang.nm_barang as nm_barang 
		from v_penghapusan_bi  left join ref_barang using(f,g,h,i,j)	
		where $Kondisi $Orderby "; //echo "$aqry <br>";


	$Qry = mysql_query($aqry);
	$jmlDataHPS = mysql_num_rows($Qry);//echo "$jmlDataHPS";
	if($cetak==false ){
		$LimitHalHPS = " limit ".(($HalHPS*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;	
	}

//list -----------------------------------------------------------
$aqry .= " $LimitHalHPS "; //echo "aqry=".$aqry;

$Qry = mysql_query($aqry);
$no=$Main->PagePerHal * (($HalHPS*1) - 1);
$cb=0;
$jmlTampilHPS = 0;
$tothal= 0;
$tothalNilaiPeroleh= 0;
$clGaris = $cetak ? "gariscetak" : "garisdaftar";
$ListBarangHPS = "";
while ($isi = mysql_fetch_array($Qry)){
	$jmlTampilHPS++;
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";	
	
	//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	
	$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and e1 = '{$isi['e1']}' and 
				f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
				tahun = '{$isi['tahun']}' and noreg = '{$isi['noreg']}'  ";
	
	if($SSPg=='03'){
		Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );
	}else{
		Penatausahaan_BIGetKib_hapus($isi['f'], $KondisiKIB );	
	}
	
	
	$ISI15 = $isi['ket_hapus']==''? '-': $isi['ket_hapus'] ;
	$ISI15 .= " /<br>".TglInd($isi['tgl_penghapusan']);
	
	$ISI15 .= tampilNmSubUnit3($isi);
	
	//echo "i=".$isi['sudahmutasi'];
	//$KriteriaHapus = $isi['sudahmutasi']== 0 ? $Main->ArKriteriaHapus[$isi['mutasi']][1]: " Sudah Mutasi ";	
	switch ($isi['mutasi']){
		case '0': $KriteriaHapus='Penghapusan'; break;	
		case '1': $KriteriaHapus= $isi['sudahmutasi']== 0? 'Mutasi': 'Sudah Mutasi'; break;	
		case '2': $KriteriaHapus= $isi['sudahmutasi']== 0? 'Reclass': 'Sudah Reclass'; break;	
	}
	$tampilNo = $cetak?
		"<td class=\"$clGaris\" align=center colspan=2>$no</td>	"
		: "<td class=\"$clGaris\" align=center>$no</td>
			<td class=\"$clGaris\" align=center><input type=\"checkbox\" id=\"cbHPS$cb\" name=\"cidHPS[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>";
	$skdbrg = "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}";
	$tampilKdbrg = "<td class=\"$clGaris\" align=center>
				<a href='index.php?Pg=PR&SPg=brg_cetak&byget=1&cid={$isi['id_bukuinduk']}' target='_blank'  >$skdbrg</a>
			</td>";
	/*if($SSPg=='04' or $SSPg=='06' or $SSPg=='07' or $SSPg=='09' ){
		$tampilMerk = 'Alamat';
	}else{
		$tampilMerk = $c;	
	}*/		
	
	
	/*$plh =  mysql_fetch_array(mysql_query(
		"select * from v2_penghapusan_pelihara where idhapus=".$isi['id']
	));
	$pgm =  mysql_fetch_array(mysql_query(
		"select * from v2_penghapusan_pengaman where idhapus=".$isi['id']
	));
	$biayalain = $plh['biaya_pemeliharaan']+$pgm['biaya_pengamanan'];
	*/
	
	$query = "select Id,c,d,e,e1,f,g,h,i,j,tahun,noreg from buku_induk where id_lama = {$isi['id_bukuinduk']}";
	$que = mysql_query($query); 
	$i=0;
	$idbrgtujuan ='';
	$skpdtujuan ='';
	$nmskpdtujuan ='';
	$kdbrgtujuan ='';
	$tahuntujuan ='';
	$noregtujuan ='';
	while($isit = mysql_fetch_array($que)){
		$br = $i == 0?'':'<br>';
		$idbrgtujuan .= $br.$isit['Id'];
		$skpdtujuan .= $br."{$isit['c']}.{$isit['d']}.{$isit['e']}.{$isit['e1']}";
		$kdbrgtujuan .= $br."{$isit['f']}.{$isit['g']}.{$isit['h']}.{$isit['i']}.{$isit['j']}";
		$tahuntujuan .= $br.$isit['tahun'];
		$noregtujuan .= $br.$isit['noreg'];
		
		
		$st=mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isit['c']."' and d='".$isit['d']."' and e='00' and e1='000'"));
		$nmskpdtujuan .= $i == 0? $br.$st['nm_skpd'] : '/'.$br.$st['nm_skpd'];
		
		$i++;
	}
	
	
	
	$biayaplh = $isi['biaya_pemeliharaan'] > 0 ? number_format( $isi['biaya_pemeliharaan'] , 2, ',', '.'): '';
	$biayapgm = $isi['biaya_pengamanan'] > 0 ? number_format( $isi['biaya_pengamanan'] , 2, ',', '.'): '';
	
	$biayaplh2 = $isi['biaya_pemeliharaan'] > 0 ?  $isi['biaya_pemeliharaan'] :0;
	$biayapgm2 = $isi['biaya_pengamanan'] > 0 ? $isi['biaya_pengamanan'] : 0;
	
	$tothalNilaiPeroleh += $isi['nilai_buku'];
		if($mutasi<>0 && $mutasi<>""){
			$isiKriteria = "<td class=\"$clGaris\">".$KriteriaHapus."</td>";
			$isiMutasiReklas = "<td class=\"$clGaris\">$idbrgtujuan</td>
								<td class=\"$clGaris\">$skpdtujuan</td>
								<td class=\"$clGaris\">$nmskpdtujuan</td>
								<td class=\"$clGaris\">$kdbrgtujuan</td>
								<td class=\"$clGaris\">$tahuntujuan</td>
								<td class=\"$clGaris\">$noregtujuan</td>";
			if($mutasi=="1" || $mutasi=="2"){
				$tothal += 
					$biayaplh2 + 
					$biayapgm2;
				$isiRinciHarga = "<td class=\"$clGaris\" align=right>".
							$biayaplh.'<br>'.$biayapgm.				
							"</td>";
			}else{
				$tothal += 
					$isi['jml_harga'] + 
					$biayaplh2 + 
					$biayapgm2;
				$isiRinciHarga = "<td class=\"$clGaris\" align=right>".
							number_format( $isi['jml_harga'] , 2, ',', '.').'<br>'.
							$biayaplh.'<br>'.$biayapgm.				
							"</td>";
			}
			
		}else{
			$tothal += 
					$isi['jml_harga'] + 
					$biayaplh2 + 
					$biayapgm2;
			$isiKriteria = "";
			$isiMutasiReklas = "";
			$isiRinciHarga = "<td class=\"$clGaris\" align=right>".
							number_format( $isi['jml_harga'] , 2, ',', '.').'<br>'.
							$biayaplh.'<br>'.$biayapgm.				
							"</td>";
		}
		$ListBarangHPS .= "	
		<tr class='$clRow' valign='top'>
			$tampilNo						
			$tampilKdbrg
			<td class=\"$clGaris\" align=center>{$isi['id_bukuinduk']} / {$isi['idbi_awal']}</td>			
			<td class=\"$clGaris\" align=center>{$isi['noreg']}</td>			
			<td class=\"$clGaris\">{$isi['nm_barang']}</td>			
			<td class=\"$clGaris\">$ISI5</td>
			<td class=\"$clGaris\">$ISI6</td>						
			<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"$clGaris\" align=center>".$Main->KondisiBarang[$isi['kondisi_akhir']-1][1]."</td>			
			$isiKriteria		
			<!--$isiRinciHarga-->
			<!--<td class=\"$clGaris\" align=right>".number_format( $isi['apraisal'] , 2, ',', '.')."</td> -->
			<td class=\"$clGaris\">".$isi['nosk']."</td>
			<td class=\"$clGaris\" width='70'>".ifemptyTgl( TglInd($isi['tglsk']),'-')."</td>	
			<td class=\"$clGaris\" align=right>".number_format( $isi['nilai_buku'] , 2, ',', '.')."</td>
			<td class=\"$clGaris\" align=right>".number_format( $isi['nilai_susut'] , 2, ',', '.')."</td>
			$isiMutasiReklas
			<td class=\"$clGaris\">".$ISI15."</td>
		</tr>
	
		";
	
		
	$cb++;
}

if($mutasi<>0 && $mutasi<>""){
	$cols = "13";
	if($mutasi=="1" || $mutasi=="2"){
		/*$get = mysql_fetch_array(mysql_query(
			"select sum(ifnull(aa.biaya_pemeliharaan,0)+ifnull(aa.biaya_pengamanan,0)) as tot, sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));*/
		$get = mysql_fetch_array(mysql_query(
			"select sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));
		$totseluruhPeroleh = $get['tot_peroleh'];
		$tampilTotalHalPerolehan = "<td class='$clGaris' align=right><b>".number_format( $tothalNilaiPeroleh , 2, ',', '.')."</b></td>
									<td class='$clGaris' colspan=8></td>";
		$tampilTotalSlrhPerolehan = "<td class='$clGaris' align=right><b>".number_format( $totseluruhPeroleh , 2, ',', '.')."</b></td>
									<td class='$clGaris' colspan=8></td>";
	}else{
		/*$get = mysql_fetch_array(mysql_query(
			"select sum(aa.jml_harga+ifnull(aa.biaya_pemeliharaan,0)+ifnull(aa.biaya_pengamanan,0)) as tot, sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));*/
		$get = mysql_fetch_array(mysql_query(
			"select sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));
		$totseluruhPeroleh = $get['tot_peroleh'];
		$tampilTotalHalPerolehan = "<td class='$clGaris' align=right><b>".number_format( $tothalNilaiPeroleh , 2, ',', '.')."</b></td>
									<td class='$clGaris' colspan=8></td>";
		$tampilTotalSlrhPerolehan = "<td class='$clGaris' align=right><b>".number_format( $totseluruhPeroleh , 2, ',', '.')."</b></td>
									<td class='$clGaris' colspan=8></td>";
	}
	
}else{
	/*$get = mysql_fetch_array(mysql_query(
			"select sum(aa.jml_harga+ifnull(aa.biaya_pemeliharaan,0)+ifnull(aa.biaya_pengamanan,0)) as tot, sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));*/
		$get = mysql_fetch_array(mysql_query(
			"select sum(nilai_buku) as tot_peroleh from v_penghapusan_bi aa 
			left join ref_barang using(f,g,h,i,j) where $Kondisi "
		));
	$totseluruhPeroleh = $get['tot_peroleh'];
	$cols = "12";
	$tampilTotalHalPerolehan = "<td class='$clGaris' align=right><b>".number_format( $tothalNilaiPeroleh , 2, ',', '.')."</b></td>
								<td class='$clGaris' colspan=3></td>";
	$tampilTotalSlrhPerolehan = "<td class='$clGaris' align=right><b>".number_format( $totseluruhPeroleh , 2, ',', '.')."</b></td>
								<td class='$clGaris' colspan=3></td>";
}
$totseluruh = $get['tot'];
$ListBarangHPS .= "<tr>					
					<td class='$clGaris' colspan=$cols><b>Jumlah Harga per Halaman </td>
					<!--<td class='$clGaris' align='right'><b>".number_format( $tothal , 2, ',', '.')."</td>-->					
					$tampilTotalHalPerolehan
				</tr>
				<tr>					
					<td class='$clGaris' colspan=$cols><b>Total Harga Seluruhnya</td>
					<!--<td class='$clGaris' align='right'><b>".number_format( $totseluruh , 2, ',', '.')."</td>-->					
					$tampilTotalSlrhPerolehan
				</tr>" ;

//header -------------------------------------
$tampilNoHeader = $cetak?
	"<th class=\"th01\" width='30' colspan=2>No.</th>"
	: "<th class=\"th01\" width='30'>No.</th>
		<th class=\"th01\" width='10'><input type=\"checkbox\" name=\"toggle\" value=\"\" onClick=\"checkAll($jmlTampilHPS,'cbHPS');\"</th>";
if($SSPg=='04' || $SSPg=='06' || $SSPg=='07' || $SSPg=='09' ){
	$tampilMerk = "<th class='th01' style='width:200'>Alamat</th>";
}else{
	$tampilMerk = "<th class='th01' width='70'>Merk / Tipe</th>";	
}
if($SSPg=='04' ){
	$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat / Tanggal / Hak</th>";
}else if( $SSPg=='06' || $SSPg=='07' || $SSPg=='09' ){
	$tampilSert = "<th class=\"th01\" width='70'>No. Dokumen / Tanggal </th>";
}else{
	$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin / No. Polisi</th>";	
}
if($mutasi<>0 && $mutasi<>""){
	$tampilKriteria = "<th class=\"th01\" rowspan='2' width='50'>Kriteria</th>";
	if($mutasi=="1"){
		$tampilMutasiReklas = "<th class=\"th02\" colspan='6'>Mutasi Tujuan</th>";
		//$tampilRinciHarga = "<th class=\"th01\" >Pemeliharaan/<br>Pengamanan</th>"; 
	}elseif($mutasi=="2"){
		$tampilMutasiReklas = "<th class=\"th02\" colspan='6'>Reklas Tujuan</th>";
		//$tampilRinciHarga = "<th class=\"th01\" >Pemeliharaan/<br>Pengamanan</th>"; 
	}else{
		$tampilMutasiReklas = "<th class=\"th02\" colspan='6'>Mutasi/Reklas Tujuan</th>";
		//$tampilRinciHarga = "<th class=\"th01\" >Perolehan/<br>Pemeliharaan/<br>Pengamanan</th>";
	}
	$tampilRinciMutasi ="<th class=\"th01\" >ID Barang </th>
						<th class=\"th01\" >SKPD </th>
						<th class=\"th01\" >Nama SKPD </th>
						<th class=\"th01\" >Kode Barang </th>
						<th class=\"th01\" >Tahun </th>
						<th class=\"th01\" >Noreg </th>";
	$tampilSK = "<th class=\"th02\" colspan='2'>Dasar Penghapusan</th>";
	
}else{
	$tampilKriteria = "";
	$tampilMutasiReklas = "";
	$tampilRinciMutasi ="";
	$tampilSK = "<th class=\"th02\" colspan='2'>SK Kep. Daerah</th>";
	//$tampilRinciHarga = "<th class=\"th01\" >Perolehan/<br>Pemeliharaan/<br>Pengamanan</th>";
}
	$ListBarangHPS_header = 
	"<tr>
	<th class=\"th02\" colspan='5'>Nomor</th>
	<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>	
	<th class=\"th01\" rowspan='2' width='50' >Tahun Peroleh an</th>	
	<th class=\"th01\" rowspan='2' width='50'>Kondisi Barang (B,KB,RB)</th>
	$tampilKriteria
	<!--<th class=\"th02\" colspan='1'>Harga</th>-->
	$tampilSK
	<th class=\"th01\" rowspan='2' width='50'>Nilai Perolehan</th>
	<th class=\"th01\" rowspan='2' width='50'>Akumulasi Penyusutan</th>
	$tampilMutasiReklas
	<th class=\"th01\" rowspan='2'>Keterangan/<br>Tgl. Penghapusan</th>	
	</tr>
	<tr>
	$tampilNoHeader
	<th class=\"th01\" width='100'>Kode <br>Barang</th>
	<th class=\"th01\" width='100'>ID Barang/ ID Awal</th>
	<th class=\"th01\" width='30'>Reg.</th>
	<th class=\"th01\" width=\"\">Nama / Jenis Barang</th>
	$tampilMerk
	$tampilSert
	$tampilRinciHarga
	<!--<th class=\"th01\" >Appraisal </th>-->
	<th class=\"th01\" >No. </th>
	<th class=\"th01\" >Tgl. </th>
	$tampilRinciMutasi
	</tr>";

	
if($cetak == false){

if($mutasi==""){
	$mut = "";
}else{
	$mut = "&mutasi=$mutasi";
}
	
$ListBarangHPS_Hlm ="<tr>
	<td colspan=24 align=center>
	".Halaman($jmlDataHPS,$Main->PagePerHal,"HalHPS","?Pg=$Pg&SPg=$SPg&SSPg=$SSPg$mut")."
	</td>
	</tr>";
	
}	

	return "$ListBarangHPS_header
	$ListBarangHPS
	$ListBarangHPS_Hlm
	";
}

function Penghapusan_daftar_mutasi(){
	//daftar utk mutasi
	global $Main, $cek, $Pg, $SPg, $cidBI, $fmIDLama;//$idBI ;
	global $HalHPS,$fmKEPEMILIKAN;
	global $fmPenghapusanSKPD, $fmPenghapusanUNIT, $fmPenghapusanSUBUNIT,$fmPenghapusanSEKSI;
	global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
	global $fmKIB, $fmFiltThnHapus, $AcsDsc1, $odr1;
	
	$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
		
	//get limit -------------
	$LimitHalHPS = " limit ".(($HalHPS*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
	//kondisi ---------------
	$KondisiC = $fmPenghapusanSKPD == "" ? "":" and c='$fmPenghapusanSKPD' ";
	$KondisiD = $fmPenghapusanUNIT == "" ? "":" and d='$fmPenghapusanUNIT' ";
	$KondisiE = $fmPenghapusanSUBUNIT == "" ? "":" and e='$fmPenghapusanSUBUNIT' ";
	$KondisiE1 = $fmPenghapusanSEKSI == "" ? "":" and e1='$fmPenghapusanSEKSI' ";
	$Kondisi = ' and mutasi=1 and sudahmutasi=0 ';
	if (!empty($fmKIB)){ $Kondisi .= " and f = '$fmKIB'";	}
	if (!empty($fmFiltThnHapus)){ $Kondisi .= " and year(tgl_penghapusan) = $fmFiltThnHapus";	}
	
	$Kondisi = " a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ".$Kondisi;
	//urut,aqry -------------
	$Asc1 = !empty($AcsDsc1)? " desc ":"";
	if(!empty($odr1)){$Urut .= " $odr1 $Asc1, ";}
	$Urut .= ' a1,a,b,c,d,e,e1,f,g,h,i,j,tahun, noreg ';	
	//$aqry="select * from penghapusan where $Kondisi order by $Urut "; echo "<br>qry = $aqry";
	//$aqry="select * from v_penghapusan_bi where $Kondisi order by $Urut "; echo "<br>qry = $aqry";
	$aqry = "select v_penghapusan_bi.*, ref_barang.nm_barang as nm_barang 
		from v_penghapusan_bi  left join ref_barang using(f,g,h,i,j)	
		where $Kondisi order by $Urut ";// echo "<br>qry = $aqry";
	//get jml data
	//echo $aqry;
	$qry= mysql_query($aqry);
	$jmlDataHPS = mysql_num_rows($qry);
	
	//set list
	$ListBarangHPS='';
	$aqry .= ' '.$LimitHalHPS; //$cek .="<br>qry = $aqry";
	$qry= mysql_query($aqry);	
	$cb=0;
	$no=$Main->PagePerHal * (($HalHPS*1) - 1);	
	while ($isi = mysql_fetch_array($qry)){
		$jmlTampilHPS++;
		$no++;
		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$clRow = $no % 2 == 0 ?"row1":"row0";
		
		//echo " idBi = ".$cidBI[0];
		if ($isi['id_bukuinduk'] == $cidBI[0]){
			$fmIDLama = $cidBI[0];//$idBI = $cidBI[0];//echo " idBi = ".$idBI;
			$Checked= 'checked=""';//	echo 'checked';
		}else {
			$Checked = '';
		}
		
		
		$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and  e1 = '{$isi['e1']}' and 
				f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
				tahun = '{$isi['tahun']}' and noreg = '{$isi['noreg']}'  ";		
		Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );		
		$kethps = $isi['ket_hapus'] =='' ? '' : $isi['ket_hapus'].' /<br>';
		$ketbi = $ISI15 == '-' || $ISI15 == '' ? '' : $ISI15.' /<br>';
		$ISI15 	= $kethps.$ketbi.TglInd($isi['tgl_penghapusan']);	
		$ISI15 .= tampilNmSubUnit($isi);
		
		//$ISI15 .= 'tes';
			
		//echo "i=".$isi['sudahmutasi'];
		$skdbrg = "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}";
		$kdbrg = "<td class=\"GarisDaftar\" align=center>
				<a href='index.php?Pg=PR&SPg=brg_cetak&byget=1&cid={$isi['id_bukuinduk']}' target='_blank'  >$skdbrg</a>
			</td>";
		$KriteriaHapus = $isi['sudahmutasi']== 0 ? $Main->ArKriteriaHapus[$isi['mutasi']][1]: " Sudah Mutasi ";
		$ListBarangHPS .= "	
			<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width='50'>$no</td>					
			<td class=\"GarisDaftar\" align=center width='20'>			
				<input type=\"checkbox\" id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id_bukuinduk']}\"  $Checked
				title='Mutasi Kode Barang $kdBarang.{$isi['thn_perolehan']}.{$isi['noreg']}'
				onClick=\"CheckAll3($jmlDataHPS,'cb',$cb); isChecked(this.checked);
				adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" />
			</td>
			$kdbrg
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			
			
			<td class=\"GarisDaftar\">{$isi['nm_barang']}</td>			
			<td class=\"GarisDaftar\">$ISI5</td>
			<td class=\"GarisDaftar\">$ISI6</td>
						
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>			
			<td class=\"GarisDaftar\">".$KriteriaHapus."</td>
			
			<td class=\"GarisDaftar\" align=right>".number_format( $isi['jml_harga'] , 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format( $isi['apraisal'] , 2, ',', '.')."</td>
			
			<td class=\"GarisDaftar\">".$isi['nosk']."</td>
			<td class=\"GarisDaftar\" width='70'>".ifemptyTgl(TglInd($isi['tglsk']),'-')."</td>
			
			<td class=\"GarisDaftar\">".$ISI15."</td>
			</tr>
			";	
		
		$cb++;	
		
	}
	
	//set header
	$header=
	"<tr>
	<th class=\"th02\" colspan='4' width='50'>Nomor</th>
	<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>	
	<th class=\"th01\" rowspan='2' width='50' >Tahun Peroleh an</th>	
	<th class=\"th01\" rowspan='2' width='50'>Kondisi Barang (B,KB,RB)</th>
	<th class=\"th01\" rowspan='2' width='50'>Kriteria</th>
	<th class=\"th02\" colspan='2'>Harga</th>
	<th class=\"th02\" colspan='2'>SK Bupati</th>
	<th class=\"th01\" rowspan='2'>Keterangan/<br>Tgl. Penghapusan</th>	
	</tr>
	<tr>
	<th class=\"th01\" width='30' colspan=2>No.</th>
	<!--<th class=\"th01\" width='10'><input type=\"checkbox\" name=\"toggle\" value=\"\" onClick=\"checkAll($jmlData);\"</th>-->
	<th class=\"th01\" width='100'>Kode <br>Barang</th>
	<th class=\"th01\" width='30'>Reg.</th>
	<th class=\"th01\" width=\"\">Nama / Jenis Barang</th>
	<th class=\"th01\" width=\"70\">Merk / Tipe&nbsp</th>
	<th class=\"th01\" width='70'>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
	<th class=\"th01\" >Perolehan</th>
	<th class=\"th01\" >Appraisal </th>
	<th class=\"th01\" >No. </th>
	<th class=\"th01\" >Tgl. </th>
	</tr>";
	/*"<TR>
		<TH class=\"th01\" colspan=2 width='70'>No</TD>		
		<TH class=\"th01\" style='width:80'>Kode Barang</TH>
		<TH class=\"th01\" style='width:60'>Nomor<br>Register</TH>
		<TH class=\"th01\">Nama Barang</TH>
		<TH class=\"th01\" style='width:60'>Tahun<br>Perolehan</TH>
		<TH class=\"th01\" style='width:80'>Tanggal<br>Penghapusan</TH>
		<TH class=\"th01\" style='width:180'>Uraian</TH>
		<TH class=\"th01\" style='width:180'>Keterangan</TH>
	</TR>";
	*/
	
	//set halaman
	$hal = 
	"<tr>
		<td class=\"GarisDaftar\" colspan=15 align=center>
		".Halaman($jmlDataHPS,$Main->PagePerHal,"HalHPS")."
		</td>
	</tr>";
	
	//set kondisi
	
	//tampil
	$cek = '';
	return
	"<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	$header
	$ListBarangHPS
	$hal
	</table>".$cek;

	
}

function Penghapusan_CekdataCutoff($mode='insert',$id='',$tgl='',$idbi=''){
global $Main;

$usrlevel=$Main->UserLevel;
$errmsg='';
if ($usrlevel!='1'){
	

$tglcutoff=$Main->TAHUN_CUTOFF."-12-31";

switch ($mode){
	

case 'insert':{
	if ($tgl<$tglcutoff) $errmsg="Tgl. penghapusan(".$tgl.") lebih kecil dari  tgl. cut off (".$tglcutoff.")";

		 	
	break;
}
case 'edit':{
//	if ($tgl<$tglcutoff) $errmsg="Data dengan tgl. penghapusan lebih kecil dari tgl. ".$tglcutoff; 
			//cek tanggal buku
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from penghapusan where id='$id'"));
			$tgl=$datax['tgl_penghapusan'];		
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. penghapusan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat diedit";
	}
	
	break;
}
case 'hapus':{
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from penghapusan where id='$id'"));
			$tgl=$datax['tgl_penghapusan'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. penghapusan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat dihapus";
	}
	
	break;
}	
}

}
return $errmsg;

}

?>