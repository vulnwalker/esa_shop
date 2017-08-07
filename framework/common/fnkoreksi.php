<?php

class KoreksiObj  extends DaftarObj2{	
	var $Prefix = 'Koreksi';
	var $elCurrPage="HalDefault";
	var $TblName = 'v1_koreksi'; //daftar
	var $TblName_Hapus = 't_koreksi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array('harga', 'harga_baru' , 'selisih', 'debet', 'kredit');//array('jml_harga');
	var $SumValue = array('harga', 'harga_baru' , 'selisih', 'debet', 'kredit' );
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $totalCol = 11; //total kolom daftar
	var $fieldSum_lokasi = array( 9,10);
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Koreksi';
	var $PageIcon = 'images/masterdata_ico.gif';
	var $pagePerHal= 2;
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='koreksi.xls';
	var $Cetak_Judul = 'Koreksi Pembukuan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Koreksi Pembukuan';
	}
	function setMenuEdit(){
		/*$buttonEdits = array(
			array('label'=>'SPPT Baru', 'icon'=>'new_f2.png','fn'=>"javascript:".$this->Prefix.".Baru()" )
		);*/
		return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>"
			'';
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}
	
	function Simpan(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idbi = $_REQUEST['idbi'];
		$tgl = $_REQUEST['tgl'];
		$tgl_perolehan = $_REQUEST['tgl_perolehan'];
		$uid = $HTTP_COOKIE_VARS['coID'];		
		$ket = $_REQUEST['ket'];
		$hrg = $_REQUEST['hrg'];
		$hrg_baru = $_REQUEST['hrg_baru'];
		$thn_perolehan = substr($tgl_perolehan,0,4);
		
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='$idbi'"));
		
		//get tglakhir susut,koreksi,penilaian,penghapusan_sebagian dgn idbi_awal yg sama
		$tgl_susutAkhir = mysql_fetch_array(mysql_query("select tgl from penyusutan where idbi_awal='$idbi' order by tgl desc limit 1"));
		$tgl_korAkhir = mysql_fetch_array(mysql_query("select tgl,tgl_perolehan,tgl_create from t_koreksi where idbi_awal='$idbi' order by tgl desc limit 1"));
		$tgl_nilaiAkhir = mysql_fetch_array(mysql_query("select tgl_penilaian,tgl_perolehan,tgl_create from penilaian where idbi_awal='$idbi' order by tgl_penilaian desc limit 1"));
		$tgl_hpsAkhir = mysql_fetch_array(mysql_query("select tgl_penghapusan,tgl_create from penghapusan_sebagian where idbi_awal='$idbi' order by tgl_penghapusan desc limit 1"));
		//-------------------------------------
				
		if($err=='' && $tgl=='') $err = 'Tanggal belum diisi!';
		if($err=='' && ($tgl_perolehan == '0000-00-00' || $tgl_perolehan=='') ){ $err = 'Tanggal Perolehan belum diisi!';	}
		if($err=='' && ($tgl == '0000-00-00' || $tgl=='') ){ $err = 'Tanggal Koreksi belum diisi!';	}
		if($err=='' && !cektanggal($tgl)){ 	$err = "Tanggal Koreksi $tgl Salah!";	}
		if($err=='' && !cektanggal($tgl_perolehan)){ 	$err = "Tanggal Perolehan $tgl_perolehan Salah!";	}
		if($err=='' && compareTanggal($tgl, date('Y-m-d'))==2  ) $err = 'Tanggal Koreksi tidak lebih besar dari Hari ini!';				
		//if($err=='' && compareTanggal($tgl, $bi['tgl_buku'])==0  ) $err = 'Tanggal Koreksi tidak lebih kecil dari Tanggal Buku Barang !';			
		if($err =='' && compareTanggal($tgl_perolehan,$tgl )==2) $err = 'Tanggal Perolehan tidak lebih besar dari Tanggal Koreksi !';				
		
		switch ($fmST){
			case 0 : { //baru	
				if($err=='' &&$fmst==1 && $bi['status_barang'] != 1 ) $err= "Hanya Barang Inventaris yang bisa Koreksi!";
				$oldmaxtgl = mysql_fetch_array(mysql_query("select max(tgl) as maxtgl from t_koreksi where idbi_awal='".$bi['idawal']."'"));
				if($err=='' && compareTanggal($tgl, $oldmaxtgl['maxtgl'])==0  ) $err = 'Tanggal Koreksi tidak lebih kecil dari tanggal koreksi sebelumnya!';			
				//jika tambah aset =1 atau tambah manfaat = 1:
				//if($fmTAMBAHASET==1 || $fmTAMBAHMasaManfaat==1){
				if($err =='' && compareTanggal($tgl,$bi['tgl_buku'])==0) $err = 'Tanggal Koreksi tidak kecil dari Tanggal Buku Barang !';				
				if($err =='' && $thn_perolehan<$bi['thn_perolehan']) $err = 'Tahun Perolehan tidak kecil dari Tahun Perolehan Buku Barang !';				
				if($err=='' && $tgl<=$tgl_closing)$err ='Tanggal sudah Closing !';
				if($err=='' && $tgl<=$tgl_susutAkhir['tgl'])$err ='Sudah ada penyusutan !';
				//if($err=='' && $tgl<$tgl_korAkhir['tgl'] )$err ='Sudah ada koreksi harga !';
				//if($err=='' && $tgl<$tgl_nilaiAkhir['tgl_penilaian'] )$err ='Sudah ada penilaian !';
				if($err=='' && $tgl_perolehan<$tgl_korAkhir['tgl_perolehan'] )$err ='Sudah ada koreksi harga !';
				if($err=='' && $tgl_perolehan<$tgl_nilaiAkhir['tgl_perolehan'] )$err ='Sudah ada penilaian !';
				if($err=='' && $tgl<$tgl_hpsAkhir['tgl_penghapusan'] )$err ='Sudah ada penghapusan sebagian !';
				//}
				if($err==''){
					//harga asal
					$harga= 0;
					//$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' "));
					//$harga += $get['tot'];
					//$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' "));
					//$harga += $get['tot'];			
					$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' "));
					$harga += $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tgl_pemeliharaan<='$tgl' and tambah_aset = 1  "));
					$harga += $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tgl_pengamanan<='$tgl' and tambah_aset = 1  "));
					$harga += $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_pemanfaatan<='$tgl' and tambah_aset = 1  "));
					$harga += $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_penghapusan<='$tgl'  "));
					$harga -= $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl'  "));
					$harga += $get['tot'];
					$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' "));
					$harga += $get['tot'];		
					
					//simpan		
					$aqry = 
						"insert into t_koreksi ".
						"(tgl,  idbi, idbi_awal, uid, tgl_update, staset, harga, harga_baru, ket , tgl_perolehan )".					
						" values ".
						"('$tgl', '$idbi', '".$bi['idawal']."', '$uid', now(), '".$bi['staset']."' , '".$hrg."', '$hrg_baru', '$ket' , '$tgl_perolehan') "; $cek .= $aqry;
					$qry = mysql_query($aqry);							
				}				
				break;
			}
			case 1 : { //edit
				break;
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaruBI':{				
				$fm = $this->setFormBaruBI();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				//$content = 'tesssss';
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
				$get = $this->Simpan();					
				//$get = array('cek'=>'', 'err'=>'ggal','content'=>'', 'json'=>TRUE);
				$cek = $get['cek'];
				$err = $get['err'];
				$content=$get['content'];
				$json=$get['json'];
				break;
			}
			
			case 'GetHrg_Asal':{				
				$fm = $this->GetHrg_Asal();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				$json=TRUE;														
				break;
			}
			/*case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			*/
			/*case 'formKondisiSimpan':{				
				$get= $this->formKondisiSimpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}*/
			
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
	
	function createEntryTgl3($Tgl, $elName, $disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', $fmName = 'adminForm',
	$tglShow=TRUE, $withBtClear = TRUE){
	//global $$elName, 
	//global $Ref;//= 'entryTgl';
	
	$NamaBulan  = array(
	array("01","Januari"), 
	array("02","Pebruari"),
	array("03","Maret"),
	array("04","April"),
	array("05","Mei"),
	array("06","Juni"),
	array("07","Juli"),
	array("08","Agustus"),
	array("09","September"),
	array("10","Oktober"),
	array("11","Nopember"),
	array("12","Desember")
	);
	
	$deftgl = date( 'Y-m-d' ) ;//'2010-05-05';
		
	$tgltmp= explode(' ',$Tgl);//explode(' ',$$elName); //hilangkan jam jika ada
	$stgl = $tgltmp[0]; 
	$tgl = explode('-',$stgl);
	if ($tgl[2]=='00'){ $tgl[2]='';	}
	if ($tgl[1]=='00'){ $tgl[1]='';	}
	if ($tgl[0]=='0000'){ $tgl[0]='';	}
		
	
	$dis='';
	if($disableEntry == '1'){
		$dis = 'disabled';
	}
	
	/*$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">'.$title.'
			<input '.$dis.' type="text" name="'.$elName.'_tgl" id="'.$elName.'_tgl" value="'.$tgl[2].'" size="2" maxlength="2" 
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\''.$elName.'\')"
				style="width:25">
		</div>' : '';*/
	$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">' . 
			$title .'&nbsp;'. 			
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'', 
				" $dis ".'   onchange="'.$this->Prefix.'.TglEntry_createtgl(\'' . $elName . '\')"').
		'</div>'
		: '';
	$btClear =  $withBtClear?
		'<div style="float:left;padding: 0 4 0 0">
				<input '.$dis.'  name="'.$elName.'_btClear" id="'.$elName.'_btClear" type="button" value="Clear" 
					onclick="TglEntry_cleartgl(\''.$elName.'\')">
					&nbsp;&nbsp<span style="color:red;">'.$ket.'</span>
		</div>' : '';
		
	if ($tgl[0]==''){
		$thn =(int)date('Y') ;
	}else{
		$thn = $tgl[0];//(int)date('Y') ;
	}
	//$thnaw = $thn-10;
	//$thnak = $thn+11;
	$thnaw = 1900;
	$thnak = (int)date('Y') ;
	$opsi = "<option value=''>Tahun</option>";
	for ($i=$thnaw; $i<=$thnak; $i++){
		$sel = $i == $tgl[0]? "selected='true'" :'';
		$opsi .= "<option $sel value='$i'>$i</option>";	
	}
	$entry_thn = 
		'<select id="'. $elName  .'_thn" 
			name="' . $elName . '"_thn"	'.
			$dis. 
			' onchange="'.$this->Prefix.'.TglEntry_createtgl(\'' . $elName . '\')"
		>'.
			$opsi.
		'</select>';
	
	$hsl = 
		'<div id="'.$elName.'_content" style="float:left;">'.
			$entrytgl.
			'<div style="float:left;padding: 0 4 0 0">
				'.cmb2D_v2($elName.'_bln', $tgl[1], $NamaBulan, $dis,'Pilih Bulan',
				'onchange="'.$this->Prefix.'.TglEntry_createtgl(\''.$elName.'\')"'  ) .'
			</div>
			<div style="float:left;padding: 0 4 0 0">
				<!--<input '.$dis.' type="text" name="'.$elName.'_thn" id="'.$elName.'_thn" value="'.$tgl[0].'" size="4" maxlength="4" 
					onkeypress="return isNumberKey(event)"
					onchange="'.$this->Prefix.'.TglEntry_createtgl(\''.$elName.'\')"
					style="width:35"	
				>-->'.
				$entry_thn.
			'</div>'.
			
			$btClear.		
			'<input $dis type="hidden" id='.$elName.' name='.$elName.' value="'.$Tgl.'" >
		</div>';
	return $hsl;	
	}
	
	function GetHrg_Asal(){
		$cek = ''; $err=''; $content=''; 
	 	$json = TRUE;	//$ErrMsg = 'tes';
		$tgl= $_REQUEST['tgl'];		
		$tgl_perolehan= $_REQUEST['tgl_perolehan'];		
		$idbukuinduk= $_REQUEST['idbukuinduk'];		
		
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id = '$idbukuinduk' "));
			$harga= 0;
			$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' and tgl_buku>='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga -= $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			/*$cek.="select harga as tot from buku_induk where id = '".$bi['idawal']."' ";
			$cek.="select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tgl_pemeliharaan<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tgl_pengamanan<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_pemanfaatan<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_penghapusan<='$tgl'  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl'  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' and tgl_perolehan<='$tgl_perolehan'";
			*/
		$nilai_buku = getNilaiBuku($idbukuinduk,$tgl_perolehan,0);
		$content->hrg2=number_format($nilai_buku,2,',','.');		
		$content->hrg=$nilai_buku;		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	 	
	}
	
	//form ==================================
	function setFormBaruBI(){
		global $Main;
		global $HTTP_COOKIE_VARS;
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
		$tgl_buku =	$thn_login.'-00-00';		
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		
		$cidBI = $_REQUEST['cidBI'];
		$idbi = $cidBI[0];// 735615;
		$aqry = "select * from buku_induk where id ='$idbi'"; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		
		$dt['idbi']= $idbi;
		$dt['staset'] =  $bi['staset'];
		$dt['tgl'] =  $tgl_buku;
		$dt['kondisi'] = $bi['kondisi'];
		$dt['kondisi_baru'] = $bi['kondisi'];
			
		if($err=='' && ($bi['staset']==5 || $bi['staset']==6 || $bi['staset']==7)) $err = 'Barang '.$Main->StatusAsetView[$bi['staset']-1][1].' ini tidak bisa di reclass ke Aset Lain-lain!';
		if($err==''){
			
		
			$fm = $this->setForm($dt);
		}
		return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		//$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		//$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		//$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from t_koreksi where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
			
		if($err==''){
			$form_name = $this->Prefix.'_form';				
			$this->form_width = 500;
			$this->form_height = $Main->STASET_OTOMATIS? 170: 150;
			
			$idbi = $dt['idbi'];
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id = '$idbi' "));
			
			//$stasetAwal = $Main->StatusAsetView[$dt['staset']-1][1] ."<input type='hidden' id='staset' name='staset' value='".$dt['staset']."'>" ;
			$tgl = $dt['tgl'];
			$tgl_perolehan = $dt['tgl_perolehan'];
			$vtgl = createEntryTgl(	'tgl', $tgl, 	false, 	'', 	'','adminForm','2');
			$vtgl_perolehan = $this->createEntryTgl3($tgl_perolehan,'tgl_perolehan','','','','',TRUE,TRUE);
			
			$caption = 'Koreksi Pembukuan' ;
			
			$harga= 0;
			$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tgl_pemeliharaan<='$tgl' and tambah_aset = 1  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tgl_pengamanan<='$tgl' and tambah_aset = 1  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_pemanfaatan<='$tgl' and tambah_aset = 1  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_penghapusan<='$tgl'  "));
			$harga -= $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl'  "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' "));
			$harga += $get['tot'];
			
			//$vhrg = number_format($harga, 2, ',', '.' )." <input type='hidden' id='hrg' name='hrg' value='".$harga."' >";
			$vhrg =" <p id='hrg2' >".number_format($harga, 2, ',', '.' )."</p> <input type='hidden' id='hrg' name='hrg' value='".$harga."' >";
			
			$vhrg_baru = inputFormatRibuan3("hrg_baru", ($entryMutasi==FALSE? $htmlreadonly:' readonly="" '),0,'','','');
			$this->form_fields = array(							
				'tgl' => array(  'label'=>'Tanggal Koreksi','labelWidth'=>180, 'value'=> $vtgl,  'type'=>'' ),
				'tgl_perolehan' => array(  'label'=>'Tanggal Perolehan','labelWidth'=>180, 'value'=> $vtgl_perolehan,  'type'=>'' ),
				'hrg' => array(  'label'=>'Harga Perolehan Asal Rp. ','labelWidth'=>180, 'value'=> $vhrg, 'labelWidth'=>130, 'type'=>'' ),
				'hrg_baru' => array(  'label'=>'Harga Perolehan Baru Rp.','labelWidth'=>180, 'value'=> $vhrg_baru, 'type'=>'' ),
				'ket'=> array(  'label'=>'Keterangan', 'row_params'=>"valign='top'",'labelWidth'=>180,'value'=> "<textarea id='ket' name='ket' style='width: 234px;height: 35px;'>".$dt['ket']."</textarea>", 'type'=>'' )
			);
			
			if ($this->form_fmST==0) {		
				$this->form_caption = $caption;
			}else{
				$this->form_caption = $caption.' - Edit';
			}	
			
							
			//tombol
			$this->form_menubawah =			
				"<input type='hidden' name='idbi' id='idbi' value='$idbi'>".
				"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >&nbsp;".
				"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
					
			$form = $this->genForm();		
					
			$content = $form;//$content = 'content';
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40' rowspan='2'>No.</th>
				$Checkbox		
				<th class='th01' rowspan='2'>Kode Barang/<br>Id Barang/<br>Id Awal</th>
   	   			<th class='th01' rowspan='2'>No.Reg/<br>Thn</th>
   	   			<th class='th02' colspan='3'>Spesifikasi Barang</th>
				<th class='th01' rowspan='2'width='80'>Tanggal Buku/<br>Tanggal Perolehan</th>
				<th class='th01' rowspan='2'>Harga Perolehan Lama </th>
				<th class='th01' rowspan='2'>Harga Perolehan Baru </th>								
				<th class='th01' rowspan='2'>Selisih </th>								
				<th class='th01' rowspan='2'>Debet </th>	
				<th class='th01' rowspan='2'>Kredit </th>	
				<th class='th01' rowspan='2'>Ket </th>			
				</tr>
				<tr>				
				<th class='th01' >Nama Barang/<br>Penggunaan</th>
				<th class='th01' >Merk/Tipe/Alamat</th>
				<th class='th01' >No. Sertifikat/<br> No. Pabrik/<br> No. Chasis/<br> No. Mesin/<br> No.Polisi</th>
				</tr>
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref, $Main;
		
		$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and e1 = '{$isi['e1']}' and 
					f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
					tahun = '{$isi['thn_perolehan']}' and noreg = '{$isi['noreg']}'  ";
		switch($isi['f']){
			case '01':{//KIB A			
				
				$sqryKIBA = "select sertifikat_no, luas, ket from kib_a  $KondisiKIB limit 0,1";
				//$sqryKIBA = "select * from view_kib_a  $KondisiKIB limit 0,1";
				//echo '<br> qrykibA = '.$sqryKIBA;
				$QryKIB_A = mysql_query($sqryKIBA);
				while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
					$isiKIB_A = array_map('utf8_encode', $isiKIB_A);	
					//$ISI5 = $isiKIB_A['alamat'].'<br>'.$isiKIB_A['alamat_kel'].'<br>'.$isiKIB_A['alamat_kec'].'<br>'.$isiKIB_A['alamat_kota'] ;
					$ISI6 = $isiKIB_A['sertifikat_no'];
					/*$ISI6 = $isiKIB_A['sertifikat_no'].'/<br>'.
					TglInd($isiKIB_A['sertifikat_tgl']).'/<br>'.
					$Main->StatusHakPakai[ $isiKIB_A['status_hak']-1 ][1];
					*/
					$ISI10 = number_format($isiKIB_A['luas'],2,',','.');//$cek .= '<br> luas A = '.$isiKIB_A['luas'];
					$ISI15 = "{$isiKIB_A['ket']}";
				}
				break;
			}
			case '02':{//KIB B;			
				//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
				$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket  from kib_b  $KondisiKIB limit 0,1";
				//echo"<br>qrkbb=".$aqry;
				
				$QryKIB_B = mysql_query($aqry);
				
				//echo "<br>qrkibb=".$aqry;
				while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
					$isiKIB_B = array_map('utf8_encode', $isiKIB_B);
					$ISI5 = "{$isiKIB_B['merk']}";
					$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
					$ISI7 = "{$isiKIB_B['bahan']}";
					$ISI10 = "{$isiKIB_B['ukuran']}";
					$ISI15 = "{$isiKIB_B['ket']}";
				}
				break;
				}	
			case '03':{//KIB C;
				$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket,kota, alamat_kec, alamat_kel, alamat,alamat_b,alamat_c from kib_c  $KondisiKIB limit 0,1");
				//$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_c  $KondisiKIB limit 0,1");
				while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
					$isiKIB_C = array_map('utf8_encode', $isiKIB_C);
					//$ISI5 = $isiKIB_C['alamat'].'<br>'.$isiKIB_C['alamat_kel'].'<br>'.$isiKIB_C['alamat_kec'].'<br>'.$isiKIB_C['alamat_kota'] ;
					$ISI5= getalamat($isiKIB_C['alamat_b'],$isiKIB_C['alamat_c'],$isiKIB_C['alamat'],$isiKIB_C['kota'] ,$isiKIB_C['alamat_kec'],$isiKIB_C['alamat_kel']);
					$ISI6 = "{$isiKIB_C['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
					$ISI15 = "{$isiKIB_C['ket']}";
				}
				break;
			}
			case '04':{//KIB D;
				//$QryKIB_D = mysql_query("select dokumen_no, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_d  $KondisiKIB limit 0,1");
				$QryKIB_D = mysql_query("select dokumen_no, ket  from kib_d  $KondisiKIB limit 0,1");
				while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
					$isiKIB_D = array_map('utf8_encode', $isiKIB_D);
					//$ISI5 = $isiKIB_D['alamat'].'<br>'.$isiKIB_D['alamat_kel'].'<br>'.$isiKIB_D['alamat_kec'].'<br>'.$isiKIB_D['alamat_kota'] ;
					$ISI6 = "{$isiKIB_D['dokumen_no']}";
					$ISI15 = "{$isiKIB_D['ket']}";
				}
				break;
			}
			case '05':{//KIB E;		
				$QryKIB_E = mysql_query("select seni_bahan, ket from kib_e  $KondisiKIB limit 0,1");
				while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
					$isiKIB_E = array_map('utf8_encode', $isiKIB_E);
					$ISI7 = "{$isiKIB_E['seni_bahan']}";
					$ISI15 = "{$isiKIB_E['ket']}";
				}
				break;
			}
			case '06':{//KIB F;
				//$cek.='<br> F = '.$isi['f'];
				//$sqrykibF = "select dokumen_no, bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat  from view_kib_f  $KondisiKIB limit 0,1";
				$sqrykibF = "select dokumen_no, bangunan, ket from kib_f  $KondisiKIB limit 0,1";
				$QryKIB_F = mysql_query($sqrykibF);
				$cek.='<br> qrykibF = '.$sqrykibF;
				while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
					$isiKIB_F = array_map('utf8_encode', $isiKIB_F);
					//$ISI5 = $isiKIB_F['alamat'].'<br>'.$isiKIB_F['alamat_kel'].'<br>'.$isiKIB_F['alamat_kec'].'<br>'.$isiKIB_F['alamat_kota'] ;
					$ISI6 = "{$isiKIB_F['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
					$ISI15 = "{$isiKIB_F['ket']}";
				}
				break;
			}
		}
		
		$ISI5 	= !empty($ISI5)?$ISI5:"-"; 
		$ISI6 	= !empty($ISI6)?$ISI6:"-";
		
		$kdBarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
		$qry_brg=mysql_query("SELECT
								  `bb`.`nm_barang`
								FROM
								  `penilaian` `aa` LEFT JOIN
								  `ref_barang` `bb` ON `aa`.`f` = `bb`.`f` AND `aa`.`g` = `bb`.`g` AND
								    `aa`.`h` = `bb`.`h` AND `aa`.`i` = `bb`.`i` AND `aa`.`j` = `bb`.`j`    
								WHERE aa.id='".$isi['id']."';");
		$res = mysql_fetch_array($qry_brg);
		$bi = mysql_fetch_array(mysql_query("SELECT * FROM view_buku_induk2 WHERE id='".$isi['id_bukuinduk']."'"));
		$penggunaan = $bi['penggunaan'];	
		$Koloms = array();		
		//$vdebet = $isi['harga_baru'] - $isi['harga'] >=0 ? number_format( $isi['harga'] ,2, ',' , '.'  ) : '';
		//$vkredit = $isi['harga_baru'] - $isi['harga'] <0 ? number_format( $isi['harga'] ,2, ',' , '.'  ) : '';
		$vdebet = number_format( $isi['debet'] ,2, ',' , '.'  ) ;
		$vkredit = number_format( $isi['kredit'] ,2, ',' , '.'  ) ;
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align="center" "',$kdBarang.'/<BR>'.$isi['idbi'].'/<BR>'.$isi['idbi_awal']);
 		$Koloms[] = array('align="center" "',$isi['noreg'].'/<BR>'.$isi['thn_perolehan']);
		$Koloms[] = array('align="left" "',$res['nm_barang'].'/<br>'.$penggunaan);
		$Koloms[] = array('align="left" "',$ISI5);
		$Koloms[] = array('align="left" "',$ISI6); 
		$Koloms[] = array('', TglInd($isi['tgl']).'&nbsp;/<br>&nbsp;'.TglInd($isi['tgl_perolehan']));
		$Koloms[] = array(' align=right ', number_format($isi['harga'] ,2, ',' , '.'  ) );
		$Koloms[] = array(' align=right ', number_format($isi['harga_baru'] ,2, ',' , '.'  ) );//$Main->StatusAsetView[$isi['staset_baru']-1][1] );//$isi['staset_baru']);				
		$Koloms[] = array(' align=right ', number_format($isi['selisih'] ,2, ',' , '.'  ) );
		$Koloms[] = array(' align=right ', $vdebet );
		$Koloms[] = array(' align=right ', $vkredit );
		$Koloms[] = array('', $isi['ket']);				
		return $Koloms;
	}
	
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];	
		$fmIdbi = $_REQUEST['idbi'];
		$fmIdbiAwal = $_REQUEST['idbi_awal'];
		$fmKdBarang = $_REQUEST['kd_barang'];
		$fmThnPerolehan = $_REQUEST['thn_perolehan'];
		$fmNoreg = $_REQUEST['noreg'];
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	
				<tr>		
					<td width=\"100%\" valign=\"top\">" . 
						//WilSKPD_ajx($this->Prefix) . 
						WilSKPD_ajx3($this->Prefix.'Skpd') . 
					"</td>
					<td >" . 
				
					"</td></tr>
				<tr>
					<td>
						
						<!--<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>-->
					</td>
				</tr>			
			</table>".
			"<div class='FilterBar'>
				<table style='width:100%'>
					<tbody>
						<tr>
							<td align='left'>
								<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
									<tr valign='middle'>
										<td align='left' style='padding:1 8 0 8; '>".
											$vtgl =
											"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tanggal :</div>".
											createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)."
										</td>
									</tr>
									<tr>
										<td align='left' style='padding:1 8 0 8; '>
											ID Barang : <input type='text' name='idbi' id='idbi' value='".$fmIdbi."'> &nbsp;&nbsp;&nbsp;
											ID Awal : <input type='text' name='idbi_awal' id='idbi_awal' value='".$fmIdbiAwal."'> &nbsp;&nbsp;&nbsp;
											KD Barang : <input type='text' name='kd_barang' id='kd_barang' value='".$fmKdBarang."'> &nbsp;&nbsp;&nbsp;
											Tahun : <input type='text' name='thn_perolehan' id='thn_perolehan' value='".$fmThnPerolehan."'> &nbsp;&nbsp;&nbsp;
											Noreg : <input type='text' name='noreg' id='noreg' value='".$fmNoreg."'> &nbsp;&nbsp;&nbsp;
											<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
		    </div>";
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
		
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		$kd_brg = $_POST['kd_barang'];			
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		/*$arrKondisi[] = getKondisiSKPD2(
			12, 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);*/
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[] = " tgl>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[] = " tgl<='$fmFiltTglBtw_tgl2'"; 	
		if(!empty($_POST['idbi'])) $arrKondisi[] = " idbi = '".$_POST['idbi']."'";
		if(!empty($_POST['idbi_awal'])) $arrKondisi[] = " idbi_awal = '".$_POST['idbi_awal']."'";
		if(!empty($kd_brg)) $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '".$kd_brg."%'";
		if(!empty($_POST['thn_perolehan'])) $arrKondisi[] = " thn_perolehan = '".$_POST['thn_perolehan']."'";
		if(!empty($_POST['noreg'])) $arrKondisi[] = " noreg = '".$_POST['noreg']."'";
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$arrOrders[] = " c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg,tgl ";
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $this->pagePerHal.",".$this->pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $this->pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	/*function setPage_HeaderOther(){
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
		}
		//if($tipe='tipe')$styleMenu2_4 = " style='color:blue;' ";
		$styleMenu = " style='color:blue;' ";	
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca'  >REKAP NERACA</a>": '';
		
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
							"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
						
	
		
	$menu_pembukuan1 =
		($Main->MODUL_AKUNTANSI )?
		"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>



	<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Aset Tetap Tanah'>Tanah</a>  |  
	<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
	<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
	<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
	<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Lainnya'>ATL</a>  |  
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |   
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
	$menu_kibg1
	<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |  
	$menu_penyusutan
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a> |
	<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI' >REKAP BI 2</a>
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
	| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu>JURNAL</a> 
	  &nbsp&nbsp&nbsp
	</td></tr>
	<tr>
	<td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	
	<A href=\"pages.php?Pg=Jurnal\" title='Jurnal'  >JURNAL</a> |
	<A href=\"pages.php?Pg=AsetLainLain\" title='Reklas Aset Lain-lain'  >ASET LAIN-LAIN</a> |
	<A href=\"pages.php?Pg=Kapitalisasi\" title='Kapitalisasi'  >KAPITALISASI</a> |
	<A href=\"pages.php?Pg=Koreksi\" title='Koreksi Pembukuan' $styleMenu >KOREKSI</a> |
	<A href=\"pages.php?Pg=Kondisi\" title='Kondisi'  >KONDISI</a>
	
	&nbsp&nbsp&nbsp
	</td>
	</tr>":'';
		
		
		$menubar = 			//"<tr height='22' valign='top'><td >".
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
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			if($Main->MODUL_MUTASI) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
		
		  $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";

		if($Main->MODUL_SENSUS) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
		if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
		
		//$menubar .= ;	
		
		$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>
			$menu_pembukuan1			
			</table>".
			
			
			""
			;
		
		/*$menubar .= 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			
			<td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			
			<A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu >JURNAL</a> |
			<A href=\"pages.php?Pg=AsetLainLain\" title='Reklas Aset Lain-lain'  >ASET LAIN-LAIN</a> |
			<A href=\"pages.php?Pg=Kapitalisasi\" title='Kapitalisasi'  >KAPITALISASI</a> | 
			<A href=\"pages.php?Pg=Koreksi\" title='Koreksi Pembukuan'  >KOREKSI</a>
			
			&nbsp&nbsp&nbsp
			</td>
			</tr>	</table>";
		
		return $menubar;
			
	}*/
	
	function setPage_HeaderOther(){	
		global $Main;
		global $HTTP_COOKIE_VARS;
		$Pg = $_REQUEST['Pg'];
		
		$Penilaian = '';
		$Koreksi = '';
		$Pemindahtanganan = '';
		$Pemanfaatan = '';
		switch ($Pg){
			case 'Penilaian': $Penilaian ="style='color:blue;'"; break;
			case 'Koreksi': $Koreksi ="style='color:blue;'"; break;
			case 'Pemanfaatan': $Pemindahtanganan ="style='color:blue;'"; break;
			case 'Pemindahtanganan': $Pemanfaatan ="style='color:blue;'"; break;
		}
		
			//index.php?Pg=09
			return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=Penilaian\" title='Penilaian' $Penilaian>Penilaian </a> |			
			<A href=\"pages.php?Pg=Koreksi\" title='Koreksi' $Koreksi>Koreksi </a> |			
			<A href=\"pages.php?Pg=Pemanfaatan\" title='Pemanfaatan' $Pemanfaatan>Pemanfaatan</a> |
			<A href=\"pages.php?Pg=Pemindahtanganan\" title='Pemindahtanganan' $Pemindahtanganan>Pemindahtanganan</a> 
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}
	
	function genDaftarOpsi_(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			
			</table>";
			/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi_($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();				
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		/*$arrKondisi[] = getKondisiSKPD2(
			12, 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);*/
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		 	
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$arrOrders[] = " c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg,tgl ";
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$Koreksi = new KoreksiObj();





?>