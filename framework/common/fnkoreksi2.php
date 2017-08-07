<?php


class KoreksiCls {
	var $fmTANGGALPEMANFAATAN, $fmTANGGALPEMANFAATAN_akhir, $fmBENTUKPEMANFAATAN, $fmKEPADAINSTANSI,	
		$fmKEPADAALAMAT, $fmKEPADANAMA, $fmKEPADAJABATAN, $fmSURATNOMOR,
		$fmSURATTANGGAL, $fmJANGKAWAKTU, $fmBIAYA, $fmKET, 
		$fmURAIAN;
	var $idbi, $UID;
	var $MainVar;
	var $jmlTampilKoreksi2;
	var $prefix ='Koreksi2';
	
	
	
	function KoreksiCls($Main_){
		$this->MainVar = $Main_;		
	}
	

	function FormEntry($isi){
		$aqry = "select * from t_koreksi where id = '".$_GET['idplh']."'";
		//echo $aqry;	
		$qry = mysql_query($aqry);
		$isi = mysql_fetch_array($qry);
		//echo "fmJENISPENGAMANAN=$fmJENISPENGAMANAN";
		$space = formEntryBase2('','','',""," style='width:5'",'','valign="middle" height="0"');
		return "
		
			<div><div>
				$FormEntry_Script
				$FormEntry_Hidden
				<input type='hidden' name='idplh' id='idplh' value='".$_GET['idplh']."' >
				<input type='hidden' name='fmst' id='fmst' value='".$_GET['fmst']."' >
			</div></div>
	
			<table width=\"100%\"  height='100%' class=\"adminform\" style='border-width:0'><tr><td valign='top' style='padding:8;'>
			<table width='100%'>
			$space
	
			".
			formEntryBase2('Tanggal Buku Koreksi',':',
				createEntryTgl('tgl', $isi['tgl'], false, '','','FormKoreksi2').
				'&nbsp&nbsp<span style="color: red;"></span>'
				,"style=''","style=''",'','valign="middle" height="21"').
			formEntryBase2('Tanggal Perolehan',':',
				createEntryTgl('tgl_perolehan', $isi['tgl_perolehan'], false, '','','FormKoreksi2').
				'&nbsp&nbsp<span style="color: red;"></span>'
				,"style=''","style=''",'','valign="middle" height="21"').
			"<tr valign=\"top\">
	  			<td>Harga Perolehan Lama Rp. </td> <td>:</td>  <td>".number_format( $isi['harga'], 2, ',','.')."</td>
			</tr>".
			"<tr valign=\"top\">
	  			<td>Harga Perolehan Baru Rp. </td> <td>:</td>  <td>".inputFormatRibuan2("harga_baru", $isi['harga_baru'])."</td>
			</tr>".
			"<tr valign=\"top\">
	  			<td>Keterangan</td> <td>:</td> <td><textarea name=\"fmKET\" id='fmKET' cols=\"60\" >".$isi['ket']."</textarea></td>
			</tr>".
			/*"			
			
			<tr valign=\"middle\">
	  			<td>Bentuk Pemanfaatan</td>
	  			<td>:</td>
	  			<td>
				".cmb2D('fmBENTUKPEMANFAATAN',$this->fmBENTUKPEMANFAATAN,$this->MainVar->BentukPemanfaatan,'')."
				</td>
			</tr>
			
			<tr valign=\"top\">
	  			<td>Uraian</td> <td>:</td> <td><textarea name=\"fmURAIAN\" id='fmURAIAN' cols=\"60\" >$this->fmURAIAN</textarea></td>
			</tr>
	
			<tr valign=\"top\">
	  			<td>Kepada</td> <td>:</td><td>&nbsp;</td>
			</tr>		
			<tr valign=\"top\">
	  			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Instansi/CV/PT</td><td>:</td>
	  			<td>".txtField('fmKEPADAINSTANSI',$this->fmKEPADAINSTANSI,'100','50','text')."</td>
			</tr>
			<tr valign=\"top\">
	  			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td><td>:</td>
	  			<td>".txtField('fmKEPADAALAMAT',$this->fmKEPADAALAMAT,'100','50','text')."</td>
			</tr>
			<tr valign=\"top\">
	  			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama</td><td>:</td>
	  			<td>".txtField('fmKEPADANAMA',$this->fmKEPADANAMA,'100','50','text')." </td>
			</tr>
			<tr valign=\"top\">
	  			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan</td><td>:</td>
	  			<td>".txtField('fmKEPADAJABATAN',$this->fmKEPADAJABATAN,'100','50','text')." </td>
			</tr>
			<tr valign=\"top\">
	  			<td>Surat Perjanjian / Kontrak</td> <td>:</td> <td>&nbsp;	</td>
			</tr>		
			<tr valign=\"top\">
	  			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td> <td>:</td>
	  			<td>".txtField('fmSURATNOMOR',$this->fmSURATNOMOR,'100','50','text')."</td>
			</tr>
			<tr valign=\"top\">
	  			<!--<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td> <td>:</td>
	  			<td>".InputKalender("fmSURATTANGGAL")."</td>-->
	  			".formEntryBase2('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal',':',
					createEntryTgl( 'fmSURATTANGGAL', $this->fmSURATTANGGAL, false,'','','FormPemanfaat')			
					,'','','','valign="top" height="24"')."
			</tr>
			<tr valign=\"top\">
	  			<td>Jangka Waktu</td>  <td>:</td>
	  			<td>".txtField('fmJANGKAWAKTU',$this->fmJANGKAWAKTU,'6','4','text')." tahun</td>
			</tr>
			<tr valign=\"top\">	  	  
	  			".formEntryBase2('Tanggal Akhir Pemanfaatan',':',
					createEntryTgl( 'fmTANGGALPEMANFAATAN_akhir', $this->fmTANGGALPEMANFAATAN_akhir, false,'','','FormPemanfaat')			
					,'','','','valign="top" height="24"')."
			</tr>
			<tr valign=\"top\">
	  			<td>Nilai (Rp.)</td> <td>:</td>  <td>".inputFormatRibuan2("fmBIAYA", $this->fmBIAYA)."</td>
			</tr>

			*/
	
			"</table></td></tr>
		</table>	
		";
	}

	function GetData($id){
		$aqry = "select * from t_koreksi where id = '$id'";
		//jns_trans,,idbi_awal,staset,staset_baru,tgl_update,harga,harga_baru,asal_usul,asal_usul_baru,
		$qry = mysql_query($aqry);
		//if (
		$isi = mysql_fetch_array($qry);
		
		return $isi;
	}
		
	function ProsesSimpan(){	
		global $HTTP_COOKIE_VARS, $Main; 	
		$MyModulKU = explode(".",$HTTP_COOKIE_VARS["coModul"]);
					 
		//if ($MyModulKU[5]=='1'){		
		/*$fmTANGGALPEMANFAATAN = $_GET['fmTANGGALPEMANFAATAN']; 
		$fmTANGGALPEMANFAATAN_akhir = $_GET['fmTANGGALPEMANFAATAN_akhir']; 
		$fmBENTUKPEMANFAATAN = $_GET['fmBENTUKPEMANFAATAN']; 
		$fmURAIAN = $_GET['fmURAIAN']; 
		$fmKEPADAINSTANSI = $_GET['fmKEPADAINSTANSI']; 
		$fmKEPADAALAMAT = $_GET['fmKEPADAALAMAT']; 
		$fmKEPADANAMA = $_GET['fmKEPADANAMA']; 
		$fmKEPADAJABATAN = $_GET['fmKEPADAJABATAN']; 
		$fmSURATNOMOR = $_GET['fmSURATNOMOR']; 
		$fmSURATTANGGAL = $_GET['fmSURATTANGGAL']; 
		$fmJANGKAWAKTU = $_GET['fmJANGKAWAKTU']; 
		$fmBIAYA = $_GET['fmBIAYA']; 
		*/
		$tgl = $_GET['tgl'];
		$tgl_perolehan = $_GET['tgl_perolehan'];
		//$harga = $_POST['harga'];
		$harga_baru = $_GET['harga_baru'];		
		$fmKET = $_GET['fmKET']; 
		
		$this->UID = $HTTP_COOKIE_VARS['coID'];
		
		
		
		
		 
		$idplh = $_GET['idplh'];
		$idbi_awal = $_GET['idbi_awal']; 
		$fmst = $_GET['fmst'];
		
		if ($fmst==1){
			$idbi = $_GET['idbi']; //hanya fmst =baru	
		}else{
			$old = mysql_fetch_array(mysql_query("select * from t_koreksi where id='$idplh'"));
			$idbi = $old['idbi'];
		}
		
		//if ($fmBIAYA == ''){$fmBIAYA = 0;}	
		$errmsg ='';	
		//validasi -----------------------
		if($tgl == '0000-00-00' || $tgl=='' ){ $errmsg = 'Tanggal Koreksi belum diisi!';	}
		if($errmsg=='' && !cektanggal($tgl)){ 	$errmsg = "Tanggal Koreksi $tgl Salah!";	}
		if ($errmsg =='' && compareTanggal($tgl, date('Y-m-d'))==2  ) $errmsg = 'Tanggal Koreksi tidak lebih besar dari Hari ini!';				
			
		if ($errmsg ==''){
			$bi =  mysql_fetch_array( mysql_query (				
				" select staset,tgl_buku from buku_induk where id ='$idbi'"
			));
			if ($errmsg =='' && compareTanggal($tgl, $bi['tgl_buku'])==0  ) $errmsg = 'Tanggal Koreksi tidak lebih kecil dari Tanggal Buku!';			
			$staset=$bi['staset'];
		}
		
		
		//cek status		
		$bi = mysql_fetch_array( mysql_query (	" select * from buku_induk where id='$idbi' " ));
		if($errmsg=='' &&$fmst==1 && $bi['status_barang'] != 1 ) $errmsg= "Hanya Barang Inventaris yang bisa Koreksi!";
		
		//cek tanggal -------------		
		/*$plh = mysql_fetch_array(mysql_query(  "select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk ='$idbi'" ));
		$aman = mysql_fetch_array(mysql_query(  "select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk ='$idbi'" ));
		$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idbi'" ));
		if ($errmsg=='' && compareTanggal($plh['maxtgl'] , $fmTANGGALPEMANFAATAN )==2  ) $errmsg = 'Tanggal Pemanfaatan tidak lebih kecil dari Tanggal Pemeliharaan!';
		if ($errmsg=='' && compareTanggal($aman['maxtgl'] , $fmTANGGALPEMANFAATAN )==2  ) $errmsg = 'Tanggal Pemanfaatan tidak lebih kecil dari Tanggal Pengamanan!';
		if ($errmsg=='' && compareTanggal($hps['maxtgl'] , $fmTANGGALPEMANFAATAN )==2  ) $errmsg = 'Tanggal Pemanfaatan tidak lebih kecil dari Tanggal Penghapusan Sebagian!';
		*/
			
		if($errmsg == ''){	
			//cari harga asal -------------------------------------------
			$harga= 0;
			//$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '$idbi_awal' "));
			//$harga += $get['tot'];
			//$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '$idbi_awal' and tgl<='$tgl' and id<>'$idplh' "));
			//$harga += $get['tot'];
			/*$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '$idbi_awal' and tambah_aset=1 and tgl_pemeliharaan<='$tgl' "));			
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '$idbi_awal' and tambah_aset=1 and tgl_pengamanan<='$tgl' "));			
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '$idbi_awal'  and tgl_penghapusan<='$tgl' "));			
			$harga -= $get['tot'];
			*/
			$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tgl_pemeliharaan<='$tgl' and tambah_aset = 1  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_pemanfaatan<='$tgl' and tambah_aset = 1  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_penghapusan<='$tgl'  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl'  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' and id<>'$idplh' "));
			$harga += $get['tot'];
			
			//simpan ----------------------------------------------------
			if ($fmst==1){//simpan baru
				
				$isibi= table_get_rec("select * from view_buku_induk2 where id = '$idbi'" );
				$idbi_awal = $isibi['idawal'] ==''? $isibi['id']: $isibi['idawal'];
				//tgl,jns_trans,idbi,idbi_awal,staset,staset_baru,uid,tgl_update,harga,harga_baru,asal_usul,asal_usul_baru,ket
				$aqry = "insert into t_koreksi ( 
					tgl,jns_trans,idbi,idbi_awal,staset,
					uid,tgl_update,harga,harga_baru,ket,tgl_perolehan)
					values ('$tgl',19,'$idbi','$idbi_awal','".$isibi['staset']."',
					'$uid', now(), '$harga', '$harga_baru','$fmKET','$tgl_perolehan')";
				//echo "aqry=$aqry<r>";			
								
				$sukses = mysql_query($aqry);	
				if($Main->MODUL_JURNAL){
					$newid = mysql_insert_id();
					//jurnalPemanfaatan($newid,$UID,1);				
				}
				
			}else{//smpan edit				
				if($errmsg ==''){
					//ambil id buku induk
					$old = mysql_fetch_array(mysql_query(
						"select staset, idbi_awal, idbi from t_koreksi where id='$idplh'"
					));		
					//cek status barangnya
					$penatausaha = mysql_fetch_array(mysql_query(
						"select idawal,staset,status_barang from buku_induk where id='{$old['idbi']}'"
					));
					if ($errmsg =='' && $penatausaha['status_barang']==3 ) $errmsg = "Gagal Edit. Barang sudah dihapuskan!";
					if ($errmsg =='' && $penatausaha['status_barang']==4 ) $errmsg = 'Gagal Edit. Barang sudah dipindah tangankan!';
					if ($errmsg =='' && $penatausaha['status_barang']==5 ) $errmsg = 'Gagal Edit. Barang sudah diganti rugi!';
					//if ($errmsg =='' && $penatausaha['staset']!=$old['staset'] ) $errmsg = 'Gagal Edit. Status Aset Barang sudah berubah!';
					
					$oldmax = mysql_fetch_array(mysql_query("select max(tgl) as maxtgl, max(id) as maxid, count(*) as cnt from t_koreksi where idbi_awal='".$penatausaha['idawal']."' and id<> $idplh"));
					if($errmsg=='' && $oldmax['cnt']>0 && $idplh<$oldmax['maxid']  ) $errmsg = 'Hanya data koreksi terakhir yang bisa di edit!';			
					$oldmax = mysql_fetch_array(mysql_query("select max(tgl) as maxtgl, max(id) as maxid, count(*) as cnt from t_koreksi where idbi_awal='".$penatausaha['idawal']."' and id< $idplh"));
					if($errmsg=='' && $oldmax['cnt']>0 && compareTanggal($tgl, $oldmax['maxtgl'])==0   ) $errmsg = 'Tanggal koreksi tidak lebih kecil dari koreksi sebelumnya!';			
					//compareTanggal($tgl_buku, date('Y-m-d'))==2 
					
					
				}
				
				if($errmsg == '') {
					$get = mysql_fetch_array(mysql_query("select harga as tot from t_koreksi where id='$idplh' "));
					$harga = $get['tot'];
					
					$aqry = "
						update t_koreksi set 
						tgl = '$tgl', 
						harga = '$harga',
						harga_baru = '$harga_baru',
						ket = '$fmKET',						
						tgl_update = now(),
						tgl_perolehan = '$tgl_perolehan',
						uid = '$UID'			
						where id = '$idplh'";	//echo "aqry=$aqry<r>";
					$sukses = mysql_query($aqry);
					//if($sukses && $Main->MODUL_JURNAL)	jurnalPemanfaatan($idplh,$UID,2);				
					
				}				
			}			
			
			if($sukses){
				
				
			}else{
				if($errmsg=='') $errmsg = 'Gagal!. Data Tidak dapat di ubah atau di simpan ';//.$aqry;
			}
		}	
		//}
	//else{
	//		$errmsg = 'Gagal Simpan Data. Anda Tidak Mempunyai Hak Akses!';
	//	}
		return $errmsg;
	}
		
	function Hapus(){
		global $HTTP_COOKIE_VARS, $Main;
		$errmsg=''; $Del = FALSE;
		$cidKRS= $_GET['cidKRS'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		for($i = 0; $i<count($cidKRS); $i++)	{
			//$id= $cidPLH[$i]; //$str.=$id.'-';
			if($errmsg ==''){
				//ambil id buku induk
				$old = mysql_fetch_array(mysql_query(
					"select tgl, staset, idbi_awal, idbi from t_koreksi where id='{$cidKRS[$i]}'"
				));		
				//cek status barangnya
				$penatausaha = mysql_fetch_array(mysql_query(
					"select idawal,staset, status_barang from buku_induk where id='{$old['idbi']}'"
				));
				if ($errmsg =='' && $penatausaha['status_barang']==3 ) $errmsg = "Gagal Hapus. Barang sudah dihapuskan!";
				if ($errmsg =='' && $penatausaha['status_barang']==4 ) $errmsg = 'Gagal Hapus. Barang sudah dipindah tangankan!';
				if ($errmsg =='' && $penatausaha['status_barang']==5 ) $errmsg = 'Gagal Hapus. Barang sudah ganti rugi!';
				if ($errmsg =='' && $penatausaha['staset']!=$old['staset'] ) $errmsg = 'Gagal Hapus. Status Aset Barang sudah berubah!';
				
				/*$oldmax = mysql_fetch_array(mysql_query(
					"select max(tgl)as maxtgl from t_koreksi where idbi_awal='{$old['idbi_awal']}'"
				));	
				if($errmsg=='' && compareTanggal($old['tgl'], $oldmax['maxtgl'])==0  ) $errmsg = 'Gagal Hapus. Hanya koreksi terakhir yang bisa dihapus!';			
				*/
				$oldmax = mysql_fetch_array(mysql_query("select max(id) as maxid, count(*) as cnt from t_koreksi where idbi_awal='".$old['idbi_awal']."' "));
				if($errmsg=='' && $oldmax['cnt']>1 && $cidKRS[$i]<$oldmax['maxid']  ) $errmsg = 'Hanya data koreksi terakhir yang bisa di hapus!';
				//$oldmax = mysql_fetch_array(mysql_query("select max(tgl) as maxtgl, max(id) as maxid, count(*) as cnt from t_koreksi where idbi_awal='".$penatausaha['idawal']."' and id<> $idplh"));
				//if($errmsg=='' && $oldmax['cnt']>0 && $idplh<$oldmax['maxid']  ) $errmsg = 'Hanya data koreksi terakhir yang bisa di edit!';			
						
				//if($errmsg=='' ) $errmsg = 'sukses';
			}
			
			if($errmsg == '') {
				//$old = mysql_fetch_array(mysql_query("select * from  pemanfaatan where id='{$cidKRS[$i]}' "));
				$aqry = "delete from t_koreksi where id='{$cidKRS[$i]}' limit 1"; //$errmsg = $aqry;			
				$Del = mysql_query($aqry);
				if ($Del){
					//if ($Main->MODUL_JURNAL) jurnalPemanfaatan($cidKRS[$i],$UID,3); 
				}else{
					$errmsg = "Gagal Hapus ID {$cidKRS[$i]} ";	
				}
			}
			if($errmsg != '') break;
		}
		return $errmsg ;
	}
	
	function createScriptJs($Style=1){	 
		switch( $Style){
			case 1:{
				return "
					<div><div>
					<script>".
					$this->prefix."Refresh= new AjxRefreshObj('".$this->prefix."List','".$this->prefix."_cover', 'div".$this->prefix."List', new Array('idbi_awal') );".
					$this->prefix."Simpan= new AjxSimpanObj('".$this->prefix."Simpan','".$this->prefix."Simpan_cover',
						new Array('tgl','tgl_perolehan', 'harga_baru', 'fmKET', 'idbi', 'idbi_awal', 'idplh', 'fmst'),
						'".$this->prefix."Form.Close();".$this->prefix."Refresh.Refresh();' );
					".$this->prefix."Form= new AjxFormObj('".$this->prefix."Form','".$this->prefix."_cover','".$this->prefix."_checkbox','jmlTampil".$this->prefix."', 
						'cbKRS', new Array('idbi','idbi_awal'), 'document.getElementById(\'tgl\').focus()');
					".$this->prefix."Hapus= new AjxHapusObj('".$this->prefix."Hapus',  '".$this->prefix."_cover', '".$this->prefix."_checkbox', 'jmlTampil".$this->prefix."', 'cbKRS', 'cidKRS','".$this->prefix."Refresh.Refresh();');		
					</script>
					</div></div>
					";		
			}
			case 2:{
				//kondisi -------------------------------------------
				//HalKRS
				$refresh = $this->prefix.'Refresh.Refresh();';//"document.getElementById(\'btTampil\').click()";
				return "
					<div><div>
					<script>
					
					".$this->prefix."Refresh= new AjxRefreshObj('".$this->prefix."List2','".$this->prefix."_cover', 'div".$this->prefix."List', 
						new Array( 'fmSKPD','fmUNIT','fmSUBUNIT', 
								'fmKIB', 'fmPilihThn', 'fmBARANGCARIDPB','HalKRS') );
					".$this->prefix."Simpan= new AjxSimpanObj('".$this->prefix."Simpan','".$this->prefix."Simpan_cover',
						new Array('tgl','tgl_perolehan', 'harga_baru', 'fmKET', 'idbi', 'idbi_awal', 'idplh', 'fmst'),
						'".$this->prefix."Form.Close();$refresh' );
					".$this->prefix."Form= new  ('".$this->prefix."Form','".$this->prefix."_cover','".$this->prefix."_checkbox','jmlTampil".$this->prefix."', 
						'cbKRS', new Array(), 'document.getElementById(\'tgl\').focus()');
					".$this->prefix."Hapus= new AjxHapusObj('".$this->prefix."Hapus',  '".$this->prefix."_cover', '".$this->prefix."_checkbox', 'jmlTampil".$this->prefix."', 
						'cbKRS', 'cidKRS','$refresh');		
					</script>
					</div></div>
					";		
			}
		}		
	}


	function genKondisi($fmKEPEMILIKAN, $a, $b, $fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,
		$fmKIB='', $fmPilihThn='', $fmBARANGCARIDPB){
		//$Main->Provinsi[0], '00'
		//$fmKIB = $_POST['fmKIB'];
		//$fmPilihThn = $_POST['fmPilihThn'];
		//$fmBARANGCARIDPB = cekPOST("fmBARANGCARIDPB");
		$Kondisi = getKondisiSKPD2($fmKEPEMILIKAN, $a,$b, $fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI);
		if(!empty($fmBARANGCARIDPB)){$Kondisi .= " and nm_barang like '%$fmBARANGCARIDPB%' ";}		
		if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
		if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl)='$fmPilihThn' ";
		return $Kondisi;
	}
		

//	public function GetList($Tbl, $Fields='*', $Kondisi='', 
//		$Limit='', $Order='', $Style=1, $TblStyleClass='koptable', $Cetak=FALSE,$NoAwal=0, $ReadOnly=FALSE){
	public function GetList($Kondisi='', $Limit='', $Order='', 
		$Style=1, $TblStyleClass='koptable', $Cetak=FALSE,$NoAwal=0, $ReadOnly=FALSE, $fmKIB=''){
		
		//global $jmlTampilKRS, $Main;
	
		$TdStyle= $Cetak ? 'GarisCetak':'GarisDaftar';
				
		//list -------------------------------------------------
		
		$no=$NoAwal;
		$this->jmlTampilKoreksi2= 0;
		$cb = 0;
		$aqry="select * from t_koreksi $Kondisi $Order $Limit "; //echo " $aqry<br>";		
		$Qry = mysql_query($aqry);		
		while ($isi = mysql_fetch_array($Qry)){		
			
			$this->jmlTampilKoreksi2++;
			$no++;
			$jmlTotalHargaDisplay += $isi['biaya_pemanfaatan'];
			$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
			$kdKelBarang = $isi['f'].$isi['g']."00";
			$nmBarang = $isi['nm_barang'];
			//mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
			//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
			$clRow = $no % 2 == 0 ?"row1":"row0";
			
			//ambil kolom alamat
			//if($fmKIB=='' ||  $fmKIB=='01' || $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){
			
				
			global $ISI5, $ISI6;										
			$ISI5 = ''; $ISI6 = '';			
			$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and b = '{$isi['b']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and 
					f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
					 e1 = '{$isi['e1']}' and  tahun = '{$isi['thn_perolehan']}' and noreg = '{$isi['noreg']}'  ";
				//echo "KondisiKIB=$KondisiKIB";
			if($fmKIB==''){
				Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );
			}else{
				Penatausahaan_BIGetKib_hapus($isi['f'], $KondisiKIB );	
			}
			$tampilAlamat = $Style ==1? '':
				"<td class='$TdStyle' align=left>$ISI5</td>	<td class='$TdStyle' align=left>$ISI6</td>	";
			
			//}			
			//global $tesc;	$tesc->fntes(); 
			
			$TampilCheckBox = $Cetak? '' : 
				"<td class=\"$TdStyle\" align='center'><input type=\"checkbox\" id=\"cbKRS$cb\" name=\"cidKRS[]\" value=\"{$isi['Id']}\" onClick=\"isChecked2(this.checked,'".$this->prefix."_checkbox');\" />&nbsp;</td>";			
			$TampilCheckBoxKol1 = $Style==1? '' : $TampilCheckBox;	
			$TampilCheckBoxKol2 = $Style==1? $TampilCheckBox	: '';
			
			/*$List_ = $Style==2?
				"<td class=\"$TdStyle\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
				<td class=\"$TdStyle\" align=center>{$isi['noreg']}</td>
				<td class=\"$TdStyle\">$nmBarang</td>
				$tampilAlamat 
				<td class=\"$TdStyle\" align=center>{$isi['thn_perolehan']}</td>"
				:"";					
			$ListData .= "	
				<tr class='$clRow' height='21'>
				<td class=\"$TdStyle\" align=center>$no</td>
				$TampilCheckBoxKol1					
				$List_
				<td class=\"$TdStyle\" align=center>".TglInd($isi['tgl_pemanfaatan'])."</td>	
				
				<td class=\"$TdStyle\">".$this->MainVar->BentukPemanfaatan[$isi['bentuk_pemanfaatan']-1][1]."</td>			
				<td class=\"$TdStyle\">{$isi['uraian']}</td>
				<td class=\"$TdStyle\">{$isi['kepada_instansi']} /<br> 
				{$isi['kepada_alamat']}</td>
				<td class=\"$TdStyle\">{$isi['kepada_nama']} /<br>
				{$isi['kepada_jabatan']}</td>
				<td class=\"$TdStyle\">{$isi['surat_no']}</td>
				<td class=\"$TdStyle\" align=center>".TglInd($isi['surat_tgl'])."</td>
				<td class=\"$TdStyle\" align=right>{$isi['jangkawaktu']}</td>
				<td class=\"$TdStyle\" align=center>".TglInd($isi['tgl_pemanfaatan_akhir'])."</td>
				<td class=\"$TdStyle\" align=right>".number_format(($isi['biaya_pemanfaatan']), 2, ',', '.')."</td>			
				<td class=\"$TdStyle\">{$isi['ket']}</td>
				$TampilCheckBoxKol2
				</tr>
			";*/
			
			$ListData .= "	
				<tr class='$clRow' height='21'>
				<td class=\"$TdStyle\" align=center>$no</td>
				$TampilCheckBoxKol1	
				<td class=\"$TdStyle\">".TglInd($isi['tgl'])."</td>
				<td class=\"$TdStyle\">".TglInd($isi['tgl_perolehan'])."</td>
				<td class=\"$TdStyle\" align=right>".number_format(($isi['harga']), 2, ',', '.')."</td>
				<td class=\"$TdStyle\" align=right>".number_format(($isi['harga_baru']), 2, ',', '.')."</td>
				<td class=\"$TdStyle\" align=right>".number_format(($isi['harga_baru']-$isi['harga']), 2, ',', '.')."</td>
				<td class=\"$TdStyle\">".$isi['ket']."</td>
				$TampilCheckBoxKol2
				</tr>";	
			
			$cb++;
		}	
		//$cek.=",tes";
		
		//total & Hal ----------------------------------------------------------
		$jmlkol1 = 17;
		$jmlkol2 = 21;
		//if($fmKIB==''||$fmKIB=='01' || $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){	$jmlkol1++; $jmlkol2++;	}		
		if ($Style==2 && $Cetak==FALSE ){	
			//total hal ----------------------------
			
			$totalHal = "
				<tr class='row0'>
				<td colspan=$jmlkol1 class=\"$TdStyle\">Total Harga per Halaman </td>
				<td align=right class=\"$TdStyle\"><b>".number_format(($jmlTotalHargaDisplay), 2, ',', '.')."</td>
				<td   class=\"$TdStyle\">&nbsp;</td>
				</tr>";			
			//total semua ---------------------------
			$aqry = "select count(*) as cnt, sum(biaya_pemanfaatan) as total  from v_pemanfaat $Kondisi";  //echo " $aqry<br>";
			$IsiTot = mysql_fetch_array (mysql_query($aqry));
			$jmlTotalHarga =  $IsiTot['total'];
			$totalAll = "	
				<tr class='row0'>
				<td class=\"$TdStyle\" colspan=$jmlkol1 >Total Harga Seluruhnya </td>
				<td class=\"$TdStyle\" align=right><b>".number_format(($jmlTotalHarga), 2, ',', '.')."</td>
				<td class=\"$TdStyle\" >&nbsp;</td>
				</tr>";
			//Hal -----------------------------------	
			$jmlDataKRS =  $IsiTot['cnt'];
			$Hal = "<tr>
					<td colspan=$jmlkol2 align=center>".Halaman($jmlDataKRS,$this->MainVar->PagePerHal,"HalKRS")."</td>
				</tr>";
			
			//$cek.=",tes";
		}
		if($Cetak ){	
			$jmlkol1 --;		
			$totalHal = "
				<tr class='row0'>
				<td colspan=$jmlkol1 class=\"$TdStyle\">Total </td>
				<td align=right class=\"$TdStyle\"><b>".number_format(($jmlTotalHargaDisplay), 2, ',', '.')."</td>
				<td   class=\"$TdStyle\">&nbsp;</td>
				</tr>";
		}
		
		//header---------------
		if($fmKIB=='01' || $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){
			$tampilMerk = "<th class='th01' style='width:200'>Alamat</th>";
		}else{
			$tampilMerk = "<th class='th01' width='70'>Merk/ Tipe</th>";	
		}
		if($fmKIB=='01' ){
			$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat/ Tanggal/ Hak</th>";
		}else if( $fmKIB=='03' || $fmKIB=='04' || $fmKIB=='06' ){
			$tampilSert = "<th class=\"th01\" width='70'>No. Dokumen/ Tanggal </th>";
		}else{
			$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat/ No. Pabrik/ No. Chasis/ No. Mesin</th>";	
		}
		$tampilSertMerk	= $tampilMerk .$tampilSert;		
		$TampilCheckBox = $Cetak? '' : "<TH class=\"th01\" style='width:30'><input type=\"checkbox\" name=\"".$this->prefix."_toggle\" value=\"\" onClick=\"checkAll1b(".$this->jmlTampilKoreksi2.",'cbKRS','".$this->prefix."_toggle','".$this->prefix."_checkbox');\" /></TH>";
		$TampilCheckBoxKol1 = $Style==1? '' : $TampilCheckBox;	
		$TampilCheckBoxKol2 = $Style==1? $TampilCheckBox	: '';		
		
			
		/*$Head1 = $Style==2?
			"<TH class=\"th01\" rowspan=2 style='width:70'>Kode Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:40'>Reg.</TH>
			<TH class='th02' style='width:200' colspan=3>Spesifikasi Barang</TH>
			<TH class=\"th01\" rowspan=2 style='width:40'>Tahun<br>Perolehan</TH>"
			:'';
		$Head2 =  $Style==2?
			"<TH class=\"th01\" rowspan=1 style='width:200'>Nama Barang</TH>
			$tampilSertMerk	"
			: '';				
		$Pemanfaat_header = 
			"<TR>
				<TH class=\"th01\" rowspan=2 style='width:40'>No</TD>
				$TampilCheckBoxKol1			
				$Head1
				<TH class=\"th01\" rowspan=2 style='width:70'>Tgl. Buku<br>Pemanfaatan</TH>					
				<TH class=\"th01\" rowspan=2 style='width:70'>Bentuk<br>Pemanfaatan</TH>
				<TH class=\"th01\" rowspan=2 style='width:250'>Uraian</TH>
				<TH class=\"th02\" colspan=2>K e p a d a</TH>
				<TH class=\"th02\" colspan=2>Surat Perjanjian / Kontrak</TH>
				<TH class=\"th01\" rowspan=2 style='width:45'>Jangka Waktu (Tahun)</TH>
				<TH class=\"th01\" rowspan=2 style='width:70'>Tgl. Akhir <br>Pemanfaatan</TH>
				<TH class=\"th01\" rowspan=2  style='width:65'>Nilai (Rp.) </TH>
				<TH class=\"th01\" rowspan=2 style='width:100'>Ket</TH>
				$TampilCheckBoxKol2
			</TR>
			<TR>
				$Head2
				<TH class=\"th01\">Instansi/<br>Alamat</TH>
				<TH class=\"th01\">Nama/<br>Jabatan</TH>
				<!--<TH class=\"th01\">Instansi</TH>
				<TH class=\"th01\">Alamat</TH>
				<TH class=\"th01\">Nama</TH>
				<TH class=\"th01\">Jabatan</TH>-->
				<TH class=\"th01\" style='width:70'>Nomor</TH>
				<TH class=\"th01\" style='width:60'>Tanggal</TH>
			</TR>
			";*/
		//tgl,jns_trans,idbi,idbi_awal,staset,staset_baru,uid,tgl_update,harga,harga_baru,asal_usul,asal_usul_baru,ket
		$Pemanfaat_header = 
			"<TR>
				<TH class=\"th01\" style='width:40'>No</TD>
				$TampilCheckBoxKol1							
				<TH class=\"th01\"  style='width:150'>Tgl. Buku Koreksi</TH>					
				<TH class=\"th01\"  style='width:150'>Tgl. Perolehan</TH>					
				<TH class=\"th01\"  style='width:250' >Harga Perolehan Lama (Rp)</TH>
				<TH class=\"th01\"  style='width:250' >Harga Perolehan Baru (Rp)</TH>				
				<TH class=\"th01\"  style='width:250' >Koreksi (Rp)</TH>				
				<TH class=\"th01\"  style=''>Ket</TH>
				$TampilCheckBoxKol2
			</TR>			
			";
					
		//menu ---------------------------------
		if ($Style==1){		
			$Pemanfaat_Menu= $ReadOnly? '' :						
				"<li><a href='javascript:".$this->prefix."Hapus.Hapus()' title='Hapus Koreksi' class='btdel'></a></li>
				<li><a href='javascript:".$this->prefix."Form.Edit()' title='Edit Koreksi' class='btedit'></a>
					<ul id='bgn_ulEntry'>
						<li style='width:470;top:-4;z-index:99;'>									
						</li>
					</ul>
				</li>
				<!--<li><a  href='javascript:".$this->prefix."Form.Baru()' title='Tambah Koreksi' class='btadd'></a>
					<ul id='bgn_ulEntry'>
						<li style='width:470;top:-4;z-index:99;'>	
						</li>
					</ul>
				</li>-->	
					";
			$Pemanfaat_Menu =
				"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
				<div class='menuBar2' style='' >
				<ul>
					$Pemanfaat_Menu
					<li><a  href='javascript:".$this->prefix."Refresh.Refresh()' title='Refresh Koreksi' class='btrefresh'></a></li>		
				</ul>
				<a id='".$this->prefix."_jmldata' style='cursor:default;position:relative;left:2;top:2;color:gray;font-size:11;' title='Jumlah Koreksi'>[ ".$this->jmlTampilKoreksi2." ]</a>	
				</div>
				</td></tr></table>";
		}
		
		//echo "jmlTampilPGN = $jmlTampilPGN";
		return 
			"
			$Pemanfaat_Menu
			<table class='$TblStyleClass' border='1' width='100%'  >
			$Pemanfaat_header
			$ListData
			$totalHal
			$totalAll
			
			$Hal
			</table>
			<input type='hidden' value='' id='".$this->prefix."_checkbox' >
			<input type='hidden' value='".$this->jmlTampilKoreksi2."' id='jmlTampil".$this->prefix."' >
			
			";			
	}
	function tes1(){
		/* hasil dari fungsi ini -> testes2, bisa circular link */
		global $tesc; 
		$tesc->fntes();
	}
	function tes2(){
		echo 'tes2';
	}
	
}
$Koreksi2 = new KoreksiCls($Main);




?>