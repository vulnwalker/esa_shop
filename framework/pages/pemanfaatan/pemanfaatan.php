<?php
class pemanfaatanObj extends DaftarObj2{
	var $Prefix = 'pemanfaatan';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'pemanfaatan';//'v1_rkb'
	var $TblName_Hapus = 'pemanfaatan';
	var $TblName_Edit = 'pemanfaatan';
	var $KeyFields = array('id');
	var $FieldSum = array('luas_manfaat','biaya_pemanfaatan');
	var $fieldSum_lokasi = array( 9,12);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 12, 11, 11);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	var $totalCol = 15; //total kolom daftar
	var $FormName = 'pemanfaatanForm';
	//var $pagePerHal = 7;
	var $PageTitle = 'Pemanfaatan Barang Milik Daerah';
	var $PageIcon = 'images/pemanfaatan_ico.gif';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='DAFTAR_HASIL_PEMANFAATAN.xls';
	var $Cetak_Judul = 'DAFTAR HASIL PEMANFAATAN BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setTitle(){
		return 'Daftar Hasil Pemanfaatan Barang';
	}
	
	function setMenuEdit(){		
		return
			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'Pemanfaatan Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit Pemanfaatan')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus Pemanfaatan')."</td>";
	}
	
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=pemanfaat_rencana" title="Rencana Pemanfaatan Barang" >Rencana</a> |			
				<a style="color:blue;" href="pages.php?Pg=pemanfaatan" title="Pemanfaatan Barang">Pemanfaatan</a>  |  
				<a href="pages.php?Pg=rekappemanfaatan" title="Rekap Pemanfaatan Barang">Rekap</a>  |
				<a href="pages.php?Pg=rekappemanfaatan_skpd" title="Rekap Pemanfaatan Barang SKPD">Rekap SKPD</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	
	function simpan(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		
		$a1 = $Main->DEF_KEPEMILIKAN;
		$a = $Main->DEF_PROPINSI;
		$b = $Main->DEF_WILAYAH;
		
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['fmSKPDUnit_form'];
		$e1 = $_REQUEST['fmSKPDSubUnit_form'];
		$tahun = $_REQUEST['fmtahun'];
		
		
		$fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
		$f = $fmIDBARANG[0];
		$g = $fmIDBARANG[1];
		$h = $fmIDBARANG[2];
		$i = $fmIDBARANG[3];
		$j = $fmIDBARANG[4];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		
		$noreg = $_REQUEST['fmnoreg'];		
		$thn_perolehan = $_REQUEST['fmtahunperolehan'];		
		$luas = $_REQUEST['fmluas'];
		$tgl_pemanfaatan = $_REQUEST['tgl_pemanfaatan'];
		$bentuk_pemanfaatan = $_REQUEST['fmbentuk_pemanfaatan'];		
		$kategori = $_REQUEST['fmkategori'];		
		$luas_manfaat = $_REQUEST['fmluas_manfaat'];
		$harga_sebagian = $_REQUEST['fmharga_sebagian'];
		
		$surat_no 	= $_REQUEST['surat_no'];
		$surat_tgl = $_REQUEST['surat_tgl'];
		$kepada_instansi = $_REQUEST['fmkepada_instansi'];
		$kepada_alamat = $_REQUEST['fmkepada_alamat'];
		$kepada_nama = $_REQUEST['fmkepada_nama'];
		$kepada_jabatan = $_REQUEST['fmkepada_jabatan'];
		
		$tgl_pemanfaatan_akhir = $_REQUEST['tgl_pemanfaatan_akhir'];
		$biaya_pemanfaatan = $_REQUEST['fmbiaya_pemanfaatan'];
		$ket = $_REQUEST['fmKET'];
		
		
		$fmIDREKENING = explode('.',$_REQUEST['kode_account']);
		$ka = $fmIDREKENING[0];
		$kb = $fmIDREKENING[1];
		$kc = $fmIDREKENING[2];
		$kd = $fmIDREKENING[3];
		$ke = $fmIDREKENING[4];
		$kf = $fmIDREKENING[5];
		$nama_account = $_REQUEST['nm_account'];
		$thn_akun = $_REQUEST['thn_akun'];
		
		$id_bukuinduk = $_REQUEST['ref_idbi'];
		$idbi_awal = $_REQUEST['ref_idbi_awal'];
		$hrg_perolehan = str_replace('.','',$_REQUEST['fmharga_perolehan']);
		$ref_idrencana = $_REQUEST['idpemanfaat'];
		
		$jangkawaktu=$_REQUEST['fmjangkawaktu'];
		$jangkawaktu_bln=$_REQUEST['fmjangkawaktu_bln'];
		$staset=$_REQUEST['staset'];
		$tgl_sekarang=date("Y-m-d");
		
		$fmIDAKUNPEMANFAATAN = explode('.',$this->getAkunPemanfaatan($bentuk_pemanfaatan));
		$m1 = $fmIDAKUNPEMANFAATAN[0];
		$m2 = $fmIDAKUNPEMANFAATAN[1];
		$m3 = $fmIDAKUNPEMANFAATAN[2];
		$m4 = $fmIDAKUNPEMANFAATAN[3];
		$m5 = $fmIDAKUNPEMANFAATAN[4];
		$m6 = $fmIDAKUNPEMANFAATAN[5];
		$StSusut=0;				
	 	$harga_buku = getNilaiBuku($id_bukuinduk,$tgl_pemanfaatan,$StSusut);		
	 	$harga_susut = getAkumPenyusutan($id_bukuinduk,$tgl_pemanfaatan);
		
		
		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName_Edit where id='$id' "		
		));
		
		
		//-- validasi
		if($err=='' && $fmIDBARANG == '')$err = "Barang belum dipilih!";
		//if($err=='' && $f == '07')$err = "Barang yang dipilih harus dari KIB G!";
		if($err=='' && $fmIDREKENING == '')$err = "Kode akun belum dipilih!";
		if($err=='' && $thn_akun == '')$err = "Tahun akun belum diisi!";
		if($err=='' && ($noreg == '' || $noreg==0))$err = "No registrasi belum diisi!";
		if($err=='' && $thn_perolehan == '')$err = "Tahun perolehan belum diisi!";
		if($err=='' && $tgl_pemanfaatan == '')$err = "Tanggal pemanfaatan belum diisi!";
		if($err=='' && ($_REQUEST['tgl_pemanfaatan_tgl'] == '' || $_REQUEST['tgl_pemanfaatan_bln'] == '' || $_REQUEST['tgl_pemanfaatan_thn'] == ''))$err = "Tanggal pemanfaatan belum Lengkap!";
		if($err=='' && $tgl_pemanfaatan > $tgl_sekarang)$err = "Tanggal Pemanfaatan tidak boleh melebihi tanggal hari ini!";
		if($err=='' && $tgl_pemanfaatan > $tgl_pemanfaatan_akhir)$err = "Tanggal Pemanfaatan akhir tidak boleh kurang dari tanggal pemanfaatan!";
		if($err=='' && $surat_no == '')$err = "Nomor SPK belum diisi!";
		if($err=='' && $surat_tgl == '')$err = "Tanggal SPK belum diisi!";
		if($err=='' && ($_REQUEST['surat_tgl_tgl'] == '' || $_REQUEST['surat_tgl_bln'] == '' || $_REQUEST['surat_tgl_thn'] == ''))$err = "Tanggal SPK belum Lengkap!";
		if($err=='' && $idbi_awal == '')$err = "Id Buku Induk awal belum diisi!";
		if($err=='' && $staset == '')$err = "Status aset belum diisi!";
		if($err=='' && $tgl_pemanfaatan_akhir == '')$err = "Tanggal Batas Akhir belum diisi!";
		if($err=='' && ($_REQUEST['tgl_pemanfaatan_akhir_tgl'] == '' || $_REQUEST['tgl_pemanfaatan_akhir_bln'] == '' || $_REQUEST['tgl_pemanfaatan_akhir_thn'] == ''))$err = "Tanggal Batas Akhir belum Lengkap!";
		if($err=='' && ($bentuk_pemanfaatan == '' || $bentuk_pemanfaatan == '0' ) )$err = "Bentuk pemanfaatan belum dipilih!";
		if($err=='' && ($kategori == '') )$err = "Kategori belum dipilih!";
		if($err=='' && ($f == '02' || $f == '05' ) && ($kategori == '1') )$err = "Untuk KIB B dan  KIB E, Jenis Kategori Harus Semua!";
		if($err=='' && ($f == '01' || $f == '03' || $f == '04') && ($luas == '' || $luas == '0' ) )$err = "Luas keseluruhan belum diisi!";
		if($err=='' && ($f == '01' || $f == '03' || $f == '04') && ($luas_manfaat == '' || $luas_manfaat == '0' ) )$err = "Luas pemanfaatan belum diisi!";
		if($err=='' && ($e == '' || $e == '00' ) )$err = "Dipergunakan Unit belum dipilih!";
		if($err=='' && ($e1 == '' || $e1 == '000' ) )$err = "Dipergunakan Sub Unit belum dipilih!";
	
		if($err=='' && sudahClosing($tgl_pemanfaatan,$c,$d))$err = "Tanggal Sudah Closing!";
		
		if($err==''){
			if($fmST == 0){//baru
				
				$aqry = 
					"insert into $this->TblName_Edit ".
					"( a1,a,b,c,d,e,e1,".
					" f,g,h,i,j,".
					" ka,kb,kc,kd,ke,kf,".
					" nm_account,thn_akun,tahun,".
					" noreg,thn_perolehan,luas,luas_manfaat,".
					" tgl_pemanfaatan,tgl_pemanfaatan_akhir,".
					" bentuk_pemanfaatan,kategori,harga_sebagian,biaya_pemanfaatan,".
					" surat_no,surat_tgl,staset,jangkawaktu,jangkawaktu_bln,".
					" ref_idrencana,hrg_perolehan,".
					" kepada_instansi,kepada_alamat,kepada_nama,kepada_jabatan,ket,".
					" m1,m2,m3,m4,m5,m6,".
					" harga_buku,harga_susut,".
					" id_bukuinduk,idbi_awal,uid,tgl_update) ".
					"values ".
					"( '$a1','$a','$b','$c','$d','$e','$e1',".
					" '$f','$g','$h','$i','$j',".
					" '$ka','$kb','$kc','$kd','$ke','$kf',".
					" '$nama_account','$thn_akun','$tahun',".
					" '$noreg','$thn_perolehan','$luas','$luas_manfaat',".
					" '$tgl_pemanfaatan','$tgl_pemanfaatan_akhir',".
					" '$bentuk_pemanfaatan','$kategori','$harga_sebagian','$biaya_pemanfaatan',".
					" '$surat_no','$surat_tgl','$staset','$jangkawaktu','$jangkawaktu_bln',".
					" '$ref_idrencana','$hrg_perolehan',".
					" '$kepada_instansi','$kepada_alamat','$kepada_nama','$kepada_jabatan','$ket',".
					" '$m1','$m2','$m3','$m4','$m5','$m6',".
					" '$harga_buku','$harga_susut',".
					" '$id_bukuinduk','$idbi_awal','$UID',now())";
				
			}else{				
				if($err=='' && sudahClosing($old['tgl_pemanfaatan'],$c,$d))$err = "Tanggal Sudah Closing!";
					$aqry = 
					"update $this->TblName_Edit  ".
					" set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',e='$e',e1='$e1',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',".
					" nm_account='$nama_account',thn_akun='$thn_akun',tahun='$tahun',".
					" noreg='$noreg',thn_perolehan='$thn_perolehan',luas='$luas',luas_manfaat='$luas_manfaat',".
					" tgl_pemanfaatan='$tgl_pemanfaatan',tgl_pemanfaatan_akhir='$tgl_pemanfaatan_akhir',".
					" bentuk_pemanfaatan='$bentuk_pemanfaatan',kategori='$kategori',harga_sebagian='$harga_sebagian',biaya_pemanfaatan='$biaya_pemanfaatan',".
					" surat_no='$surat_no',surat_tgl='$surat_tgl',staset='$staset',jangkawaktu='$jangkawaktu',jangkawaktu_bln='$jangkawaktu_bln',".
					" ref_idrencana='$ref_idrencana',hrg_perolehan='$hrg_perolehan',".
					" kepada_instansi='$kepada_instansi',kepada_alamat='$kepada_alamat',kepada_nama='$kepada_nama',kepada_jabatan='$kepada_jabatan',ket='$ket',".
					" id_bukuinduk='$id_bukuinduk',idbi_awal='$idbi_awal', ".
					" m1='$m1',m2='$m2',m3='$m3',m4='$m4',m5='$m5',m6='$m6',".
					" harga_buku='$harga_buku',harga_susut='$harga_susut',".
					" uid='$UID', tgl_update= now() ".
					"where id='".$old['id']."' ";
			}
			if($err==''){
				$cek .= $aqry;
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal SQL'.mysql_error();
			}
			
		}		
		//$err=$cek;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
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
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/pemanfaatan/pemanfaat_rencana.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pemanfaatan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
		 	
			$scriptload;
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru2':{				
				//echo 'tes';
				$get=$this->setFormBaru();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];			
				break;
			}
			case 'formEdit2':{								
				$get=$this->setFormEdit();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];				
				break;
			}
			case 'simpan' : {
				$get = $this->simpan();
				$cek = $get['cek']; 
				$err = $get['err']; 
				$content=$get['content']; 
				break;
			}
			
			case 'formCariBI':{				
				$fm = $this->SetFormCariBI();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'pilihcaribi':{				
				$fm = $this->SetPilihCariBI();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'Filldetail':{	
				$idrencana = $_REQUEST['idrencana'];
							
				$get = $this->SetPilihRencana($idrencana);				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];												
				break;
			}
			case 'cekJangkaWaktu':{	
				$tgl_spk = $_REQUEST['surat_tgl'];
				$tgl_pemanfaatan_akhir = $_REQUEST['tgl_pemanfaatan_akhir'];			
				$get = $this->cekJangkaWaktu($tgl_spk,$tgl_pemanfaatan_akhir);				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];												
				break;
			}
			
			case 'BidangAfter':{
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.refreshList(true)','--- Pilih SKPD ---','00');
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			break;
		    }
			case 'SKPDAfter':{
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
				setcookie('cofmSKPD',$fmSKPDBidang);
				setcookie('cofmUNIT',$fmSKPDskpd);
				setcookie('cofmSUBUNIT','00');
				setcookie('cofmSUBUNIT','000');
			break;
		    }
			case 'UnitRefresh':{
				$fmc = cekPOST('c');
				$fmd = cekPOST('d');
				$fme = cekPOST('fmSKPDUnit_form');
				$ref_iddkb = cekPOST('ref_iddkb');
				
				$editunit= $fmc != '' ? $fmc.".".$fmd:'';
				if($ref_iddkb != ''){
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);	
				}else{
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
				}
				
			break;
		    }
			case 'UnitAfter':{
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			if($ref_iddkb != ''){
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',TRUE);	
				}else{
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',FALSE);
				}
			
			break;
		    }	
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function Hapus_Validasi($ids){
		global $Main, $HTTP_COOKIE_VARS;
		$errmsg ='';		
			$kueri="select * from $this->TblName_Hapus 
					where id = '".$ids."' "; //echo "$kueri";
			$data=mysql_fetch_array(mysql_query($kueri));
		if($errmsg=='' && sudahClosing($data['tgl_pemanfaatan'],$data['c'],$data['d']))$err = "Tanggal Sudah Closing!";
	
		return $errmsg;
	}
	
	function SetPilihCariBI(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$ids = $_REQUEST['cidBI'];
		
		if($err=='' && $ids[0] == '') $err = 'Baran
		g belum dipilih!';
			
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'")) ;
			
			$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
			$tmax = mysql_fetch_array(mysql_query($kueri1));
			$kueri="select * from ref_jurnal 
					where thn_akun = '".$tmax['thn_akun']."' 
					and ka='".$brg['ka']."' and kb='".$brg['kb']."' 
					and kc='".$brg['kc']."' and kd='".$brg['kd']."'
					and ke='".$brg['ke']."' and kf='".$brg['kf']."'"; //echo "$kueri";
			$akn=mysql_fetch_array(mysql_query($kueri));
						
			$content->plhkode_account =$akn['ka'].".".$akn['kb'].".".$akn['kc'].".".$akn['kd'].".".$akn['ke'].".".$akn['kf'];
			$content->plhnama_account = $akn['nm_account'];
			$content->plhtahun_account = $akn['thn_akun'];
			$content->plhid_buku_induk = $bi['id'];
			$content->plhidbi_awal = $bi['idawal'];
			$content->plhIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->plhNMBARANG = $brg['nm_barang'];
			$content->plhnoreg = $bi['noreg'];
			$content->plhharga_perolehan = number_format($bi['jml_harga'],2,',','.' );
			$content->plhthn_perolehan = $bi['thn_perolehan'];
			$content->plhstaset = $bi['staset'];
			$content->plhbentuk = "";
			$content->plhidpemanfaat = "";			
			$content->plhjangkawaktu = "0";
			$content->plhjangkawaktu_bln = "0";
			
			$content->plhunit = $bi['e'];
			$content->plhsubunit =  $bi['e1'];
			$content->plhspesifikasi = $this->getSpesifikasi($bi['id']);
			
			if ($bi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);

				$content->plhluas = $arrdet['luas'];
				}
			
			if ($bi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);													
				
				$content->plhluas = $arrdet['luas_lantai'];
				}
			
			if ($bi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhluas = $arrdet['luas'];
				}
			if ($bi['f']=="02" || $bi['f']=="05" || $bi['f']=="06" || $bi['f']=="07" ){				
				$content->plhluas = "";	
			}				
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function SetPilihRencana($idrencana){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$rencana = mysql_fetch_array(mysql_query("select * from t_rencana_pemanfaatan where id='".$idrencana."'")) ;
			
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$rencana['id_bukuinduk']."'")) ;
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'")) ;
			
			$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			
			$content->plhid_buku_induk = $rencana['id_bukuinduk'];
			$content->plhidbi_awal = $rencana['idbi_awal'];
			$content->plhnoreg = $bi['noreg'];
			$content->plhthn_perolehan = $bi['tahun'];
			$content->plhbentuk = $rencana['bentuk_pemanfaatan'];
			$content->plhIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->plhNMBARANG = $brg['nm_barang'];
			$content->plhharga_perolehan = number_format($bi['jml_harga'],2,',','.' );
			$content->plhstaset = $bi['staset'];
			//Perhitungan Jangka Waktu
			$rencana_waktu=$rencana['jangkawaktu'];
			$content->plhjangkawaktu = $rencana_waktu;
			$content->plhjangkawaktu_bln = '0';
			$tgl_pemanfaatan_akhir = $_REQUEST['tgl_pemanfaatan_akhir'];
			 $tgl_sekarang = date("Y-m-d");
			 $newdate = strtotime ( '+'.$rencana_waktu.' year' , strtotime ( $tgl_sekarang ) ) ;
			 $explode_newdate = explode('-', date ("Y-m-d", $newdate)); //untuk menyimpan ke dalam 
				
			$content->plhtgl_pemanfaatan_akhir_tgl = $explode_newdate[2]; //untuk menyimpan ke dalam 
			$content->plhtgl_pemanfaatan_akhir_bln = $explode_newdate[1]; //untuk menyimpan ke dalam 
			$content->plhtgl_pemanfaatan_akhir_thn = $explode_newdate[0]; //untuk menyimpan ke dalam 
			
			$content->plhunit = $bi['e'];
			$content->plhsubunit =  $bi['e1'];
			$content->plhspesifikasi = $this->getSpesifikasi($bi['id']);			
			
			if ($bi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);

				$content->plhluas = $arrdet['luas'];
			}
			
			if ($bi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);													
				
				$content->plhluas = $arrdet['luas_lantai'];						
			}
			if ($bi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
					
				$content->plhluas = $arrdet['luas'];
				
			}
			if ($bi['f']=="02" || $bi['f']=="05" || $bi['f']=="06" || $bi['f']=="07" ){				
				$content->plhluas = "";	
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function cekJangkaWaktu($tglAwal='',$tglAkhir='',$mode=''){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 	
		$date1 = date_create($tglAwal);
        $date2 = date_create($tglAkhir);        
		//$diff = date_diff($date1,$date2);
		//$Selisih=$diff->format("%y Tahun %m Bulan")." Tahun Bulan";
		if ($mode==''){
			$diff = date_diff($date1,$date2);
			if($date1<=$date2){
				$content->Jangkawaktu = $diff->format("%y");
				$content->Jangkawaktu_bln =$diff->format("%m");
			}else{
				$err="Tanggal manfaat melebihi tanggal batas akhir!";
			}
			
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		}else{
			$diff = abs(strtotime($tglAkhir) - strtotime($tglAwal));
			$valTahun = floor($diff / (365*60*60*24));
			$valBulan = floor(($diff - $valTahun * 365*60*60*24) / (30*60*60*24));
			$Selisih="$valTahun Tahun, $valBulan Bulan";
			return $Selisih;
		}
        	
    }
	
	function getAkunPemanfaatan($idPemanfaatan=''){
		switch ($idPemanfaatan){
			case '1' : $AknPemanfaatan="1.5.2.05.01"; break;//Pinjam Pakai 
			case '2' : $AknPemanfaatan="1.5.2.01.01"; break;//Sewa
			case '3' : $AknPemanfaatan="1.5.2.02.01"; break;//Kerjasama Pemanfaatan
			case '4' : $AknPemanfaatan="1.5.2.03.01"; break;//Bangun Guna Serah
			case '5' : $AknPemanfaatan="1.5.2.04.01"; break;//Bangun Serah Guna
			case '6' : $AknPemanfaatan="1.5.2.05.01"; break;//kerjasama Insfrastruktur
			default : $AknPemanfaatan="1.5.2.05.01"; break;
		}
	 return $AknPemanfaatan;
    }
	
	function createEntryTgl(	 
	$elName, 
	$Tgl,
	$disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', 
	$fmName = 'adminForm',
	$Mode=0, $withBtnClear = TRUE,
	$tglkosong='0', $param=""
	) 
{
    //requirement : javascript TglEntry_cleartgl(), TglEntry_createtgl(), $ref->namabulan
    global $Ref; //= 'entryTgl';
    $deftgl = date('Y-m-d'); //'2010-05-05';

    $tgltmp = explode(' ', $Tgl); //explode(' ',$$elName); //hilangkan jam jika ada
    $stgl = $tgltmp[0];
    $tgl = explode('-', $stgl);
    if ($tgl[2] == '00') {
        $tgl[2] = '';
    }
    if ($tgl[1] == '00') {
        $tgl[1] = '';
    }
    if ($tgl[0] == '0000') {
        $tgl[0] = '';
    }


    $dis = '';
    if ($disableEntry == '1') {
        $dis = 'disabled';
    }
	
	//$Mode = 1;
	switch ($Mode){
		case 1 :{//tahun tanpa combo			
			$entry_thn =
				'<input ' . $dis . ' type="text" 
					
					name="' . $elName . '_thn" id="' . $elName . '_thn" 
					value="' . $tgl[0] . '" size="1" maxlength="4"
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "
				>';
			break;
		}
		default :{ //tahun combo
			if ($tgl[0]==''){
				$thn =(int)date('Y') ;
			}else{
				$thn = $tgl[0];//(int)date('Y') ;
			}
			$thnaw = 1945;
			//$thnak = $thn;
			$thnak = (int)date('Y') ;
			$opsi = "<option value=''>Tahun</option>";
			for ($i=$thnaw; $i<=$thnak; $i++){
				$sel = $i == $tgl[0]? "selected='true'" :'';
				$opsi .= "<option $sel value='$i'>$i</option>";	
			}
			$entry_thn = 
				'<select 					
					id="'. $elName  .'_thn" 
					name="' . $elName . '_thn"'.
					$dis. 
					' onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "
				>'.
					$opsi.
				'</select>';
			break;
		}
		
	}
	
	$ket = $ket == ''? '':
		'<div style="float:left;padding: 0 4 0 0">
			&nbsp;&nbsp<span style="color:red;">' . $ket . '</span>
		</div>';
	$btnclear = $withBtnClear == TRUE ?
		'<div style="float:left;padding: 0 4 0 0">
			<input ' . $dis . '  type="button" value="Clear"
				name="' . $elName . '_btClear" 
				id="' . $elName . '_btClear" 		
				onclick="TglEntry_cleartgl(\'' . $elName . '\')">				
		</div>': '';

    $hsl = '
		<div id="' . $elName . '_content">
		<div  style="float:left;padding: 0 4 0 0">' . 
			$title . 
			/*'<input ' . $dis . ' type="text" name="' . $elName . '_tgl" 
				id="' . $elName . '_tgl" value="' . $tgl[2] . '" size="2" maxlength="2"
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\'' . $elName . '\')">'.*/
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'Tgl', 
				" $dis  style= 'height:20'".'  onchange="TglEntry_createtgl(\'' . $elName . '\'); '. $param .' "').
		'</div>		
		<div style="float:left;padding: 0 4 0 0">
			' . cmb2D_v2($elName . '_bln', 
				$tgl[1], 
				$Ref->NamaBulan2, 
				$dis.' style= "height:20" ', 'Pilih Bulan',
                'onchange="TglEntry_createtgl(\'' . $elName . '\') ; '. $param .' "') . '
		</div>
		<div style="float:left;padding: 0 4 0 0">'.			
			$entry_thn.
		'</div>'.				
		$btnclear.
		$ket.		
		'	<input $dis type="hidden" id=' . $elName . ' name=' . $elName . ' value="' . $Tgl . '" >
			<input type="hidden" id="' . $elName . '_kosong" name="' . $elName . '_kosong" value="'.$tglkosong.'" >
		</div>	';
    return $hsl;
	}
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$this->form_fmST = 0;
		
		
		$dt['surat_tgl']=date("Y-m-d");
		$dt['tgl_pemanfaatan']=date("Y-m-d");
		$dt['tgl_pemanfaatan_akhir']=date("Y-m-d");
		
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	
	function setFormEdit(){		
		global $Main;
		$cek = ''; $err=''; $content='';// $json=FALSE;
		$form = '';
		
		//$err = $_REQUEST['rkbSkpdfmSKPD'];
		$cbid = $_POST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
			
		$dt['harga_perolehan']=$dt['hrg_perolehan'];
		$dt['spesifikasi']=$this->getSpesifikasi($dt['id_bukuinduk']);	
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']);
	}
	
	
	
	function setForm($dt){	
		global $fmIDBARANG,$fmIDREKENING,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';
	 $sw=$_REQUEST['sw'];
	 $sh=$_REQUEST['sh'];				
	 $this->form_width = $sw-50;
	 $this->form_height = $sh-100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
		
	  }else{
		$this->form_caption = 'EDIT';
		
	  }
	 	$arrkategori = array(
			array('1','Sebagian'),
			array('0','Semua'),
		);
		//items ----------------------
		$editunit= $dt['e'] != '' ? $dt['c'].".".$dt['d']:'';
		$editsubunit=$dt['e1'] != '' ? $dt['c'].".".$dt['d'].".".$dt['e']:'';
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		
	   	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'")) ;
	   	$kode_brg = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;			
		$kode_account = $dt['ka']==''? '' : $dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'].'.'.$dt['kf'];
		
		$vjmlharga = //$this->form_fmST==1?
			//number_format($dt['jml_harga'],0,',','.') :
			"<div>
			<input type='text' name='cnt_jmlharga' id='cnt_jmlharga' value='".number_format($dt['jml_harga'],0,',','.')."' readonly=''>
			<input type='button' value='Hitung' onclick=\"
				document.getElementById('cnt_jmlharga').innerHTML = 
					Kali('jml_barang', 'harga', 'cnt_jmlharga')\">&nbsp&nbsp
			</div>";
		
		$pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange='.$this->Prefix.'.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
		$pilihSubUnit=$this->cmbQuerySubUnit('fmSKPDSubUnit_form',$dt['e1'],'',' '.$disabled1,'--- Pilih Sub Unit ---','000',FALSE,$editsubunit) ;
		
		/*****************************************************************
		 *****************************************************************
		 ****			                                              ****
		 **                        FORM UTAMA                           **
	 	 ****                                                         ****
		 *****************************************************************
		 *****************************************************************/
		 
		 $this->form_fields = array(									 
			'bidang' => array( 'label'=>'BIDANG', 
								'labelWidth'=>150,
								'value'=>$bidang, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'skpd' => array( 'label'=>'SKPD', 
								'value'=>$unit, 
								'type'=>'', 'row_params'=>"height='21'"
							),			
            'unit' => array( 
					'label'=>'Dipergunakan Unit', 
					'value'=> '<div id=Unit_formdiv>'.$pilihUnit.'</div>' ,
					'type'=>'' 
			),
			'subunit' => array( 
					'label'=>'Dipergunakan Sub Unit', 
					'value'=> '<div id=SubUnit_formdiv>'.$pilihSubUnit.'</div>',
					'type'=>'' 
			),
			'thn_anggaran' => array( 
								'label'=>'Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmtahun' id='fmtahun' size='4' value='".$dt['tahun']."' readonly=''>"
									 ),
			'nm_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_brg' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$brg['nm_barang']."' readonly=''>".
										 "&nbsp;<input type='button' value='Cari 1' onclick=\"".$this->Prefix.".cariBI()\" title='Cari Kode Barang' >".
										 "&nbsp;<input type='button' value='Cari 2' onclick ='".$this->Prefix.".CariRencana()' title='Cari Perencanaan DKBMD' >"			 
									 ),
			'noreg' => array( 
								'label'=>'Noreg/Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmnoreg' id='fmnoreg' size='4' value='".$dt['noreg']."' readonly=''> / 
								<input type='text' name='fmtahunperolehan' id='fmtahunperolehan' size='4' value='".$dt['thn_perolehan']."' readonly=''>"
									 ),
									 
			'merk' => array( 
				'label'=>'Spesifikasi/Alamat',
				 'labelWidth'=>150,
				'value'=> "<textarea style='width:438;' id='fmspesifikasi' name='fmspesifikasi' readonly=''>".$dt['spesifikasi']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),	
			'harga_perolehan' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' id='fmharga_perolehan' name='fmharga_perolehan' value='".number_format($dt['harga_perolehan'],2,',','.' )."' readonly>"
									 ),
			'luas' => array(
				'label'=>'Luas (m2)',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluas" id="fmluas" value="'.$dt['luas'].'" size="4" 				
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'tgl_pemanfaatan' => array(
							'label'=>'Tanggal Pemanfaatan', 
							'value'=> createEntryTgl('tgl_pemanfaatan',$dt['tgl_pemanfaatan']), 
							'type'=>''
			),
			'btk_pemanfaatan' => array(
				'label'=> 'Bentuk Pemanfaatan',
				'labelWidth'=>150,
				'value'=> cmb2D('fmbentuk_pemanfaatan',$dt['bentuk_pemanfaatan'],$Main->BentukPemanfaatan,''), 
				'type'=>''
				),
			'kategori' => array(
				'label'=> 'Kategori',
				'labelWidth'=>150,
				'value'=> cmb2D('fmkategori',$dt['kategori'],$arrkategori,''), 
				'type'=>''
				),
			'luas_manfaat' => array(
				'label'=>'Luas Pemanfaatan',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluas_manfaat" id="fmluas_manfaat" value="'.$dt['luas_manfaat'].'" size="4" 				
					onkeypress="return isNumberKey(event)" >',
				'type'=>''
				),
			'harga_sebagian' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>150, 
								'value'=>inputFormatRibuan("fmharga_sebagian", '',$dt['harga_sebagian']) ,
				),
			'spk' => array(
							'label'=>'', 
							'value'=>'SPK/Kontrak', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'surat_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['surat_no'], 
							'type'=>'text'
			),
			'surat_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> $this->createEntryTgl('surat_tgl',$dt['surat_tgl'],'','Tanggal Pemanfaatan','','','',TRUE,'0',"".$this->Prefix.".cekJangkaWaktu();"   ), 
							'type'=>''
			),
			'kepada_instansi' => array(
				'label'=>'Instansi',
				'labelWidth'=>150, 
				'value'=>"<INPUT type=text name='fmkepada_instansi' id='fmkepada_instansi' value='".$dt['kepada_instansi']."' >",
				'type'=>''
				),
			'kepada_alamat' => array( 
				'label'=>'Letak/Alamat', 
				'labelWidth'=>150,
				'value'=> "<textarea style='width:438;' id='fmkepada_alamat' name='fmkepada_alamat' >".$dt['kepada_alamat']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),
			'kepada_nama' => array(
				'label'=>'Nama',
				'labelWidth'=>150, 
				'value'=>"<INPUT type=text name='fmkepada_nama' id='fmkepada_nama' value='".$dt['kepada_nama']."' >",
				'type'=>''
				),
			'kepada_jabatan' => array(
				'label'=>'Jabatan',
				'labelWidth'=>150, 
				'value'=>"<INPUT type=text name='fmkepada_jabatan' id='fmkepada_jabatan' value='".$dt['kepada_jabatan']."' >",
				'type'=>''
				),	
			'tgl_pemanfaatan_akhir' => array(
							'label'=>'Batas Akhir', 
							'value'=> $this->createEntryTgl('tgl_pemanfaatan_akhir',$dt['tgl_pemanfaatan_akhir'],'','Tanggal Batas Akhir Pemanfaatan','','','1',TRUE,'0',"".$this->Prefix.".cekJangkaWaktu();" ),
							//."<input type='button' value='Hitung' onclick=\"".$this->Prefix.".cekJangkaWaktu()\" title='Hitung jangka waktu'>", 
							'type'=>''
			),			
			'jangka_waktu' => array(
				'label'=>'Jangka Waktu',
				'labelWidth'=>150, 
				'value'=>"<INPUT type=text name='fmjangkawaktu' id='fmjangkawaktu' value='".$dt['jangkawaktu']."' size='4' readonly=''> tahun <INPUT type=text name='fmjangkawaktu_bln' id='fmjangkawaktu_bln' value='".$dt['jangkawaktu_bln']."' size='4' readonly=''> bulan",
				'type'=>''
				),
			'biaya_pemanfaatan' => array( 
					'label'=>'Harga', 
					'value'=>inputFormatRibuan("fmbiaya_pemanfaatan", '',$dt['biaya_pemanfaatan']) ,
				'type'=>'' 
			),
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 438px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),
		);
		
				
		//tombol
		$this->form_menubawah = 
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='ref_idbi' name='ref_idbi' value='".$dt['id_bukuinduk']."'> ".
			"<input type=hidden id='ref_idbi_awal' name='ref_idbi_awal' value='".$dt['idbi_awal']."'> ".
			"<input type=hidden id='staset' name='staset' value='".$dt['staset']."'> ".
			"<input type=hidden id='kode_account' name='kode_account' value='".$kode_account."'> ".
			"<input type=hidden id='nm_account' name='nm_account' value='".$dt['nm_account']."'> ".			
			"<input type=hidden id='thn_akun' name='thn_akun' value='".$dt['thn_akun']."'> ".			
			"<input type=hidden id='idpemanfaat' name='idpemanfaat' value='".$dt['idpemanfaat']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >"
			;
		
		//$this->genForm_nodialog();
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function SetFormCariBI(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		//$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		//$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		/*$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd']; 	*/
		
		$this->form_fields = array(	
		
			'detailcari' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribi' style='height:5px'></div>", 
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
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
					<th class='th01' width='40' rowspan=2 width='40'>No.</th>
					$Checkbox		
					<th class='th01' width='' rowspan=2 width=''>Unit Pengguna</th>		
					<th class='th01' width='' rowspan=2 width=''>Kode Barang/ Nama Barang</th>
					<th class='th01' width='' rowspan=2 width=''>Noreg</th>			
					<th class='th01' rowspan=2>Tahun</th>
					<th class='th01' rowspan=2 width=''>Spesifikasi/ Alamat</th>
					<th class='th01' rowspan=2 width=''>Bentuk/ Kategori</th>
					<th class='th01' rowspan=2 width=''>Luas Pemanfaatan</th>
					<th class='th02' colspan=2>SPK/ Kontrak </th>
					<th class='th01' rowspan=2 width=''>Harga (Rp)</th>
					<th class='th01' rowspan=2 width=''>Batas Akhir</th>
					<th class='th01' rowspan=2 width=''>Sisa</th>
					<th class='th01' rowspan=2 width=''>Keterangan </th>
				</tr>
				<tr>
					<th class='th01' width='60'>Nomor </th>				
					<th class='th01' width='80'>Tanggal </th>	
				</tr>
				
			</thead>";
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref,$HTTP_COOKIE_VARS,$Main;
		
		$Koloms = array();
		$isi = array_map('utf8_encode', $isi);
		$brg = mysql_fetch_array(mysql_query(
				"select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));	
		
		$nmopdarr=array();		
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmunit from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='000'"
			));		
			if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
			));		
			if($get['nmseksi']<>'') $nmopdarr[] = $get['nmseksi'];
			
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
		
		$arrkategori = array(
			array('0','Semua'),
			array('1','Sebagian'),
		);
		
		//--------------------Get Spesifikasi barang
		$merk=$this->getSpesifikasi($isi['id_bukuinduk']);	
		
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array("align=''",  $nmopd);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."/<br/>".
			$brg['nm_barang']
		);
		$Koloms[] = array("align=''",  $isi['noreg'] );
		$Koloms[] = array("align=''", $isi['thn_perolehan']	);
		$Koloms[] = array("align=''", $merk.'<br>'.$no_det) ;			
		$Koloms[] = array("align=''",  $Main->BentukPemanfaatan[$isi['bentuk_pemanfaatan']-1][1]."/<br/>".
		 $arrkategori[$isi['kategori']][1] );
		$Koloms[] = array("align='right'", $isi['luas_manfaat'] );
		$Koloms[] = array("align='right'", $isi['surat_no'] );
		$Koloms[] = array("align='center'",  TglInd($isi['surat_tgl'] ) );		
		$Koloms[] = array("align='right'", number_format($isi['biaya_pemanfaatan'] ,2,',','.' ));			
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_pemanfaatan_akhir'] ) );		
		$Koloms[] = array("align='center'", $this->cekJangkaWaktu(date('Y-m-d'),$isi['tgl_pemanfaatan_akhir'],"VIEW")
		 );
		
		$Koloms[] = array("align=''", $isi['ket']);
		return $Koloms;
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		return "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' stat='".$isi['stat']."'  onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
	}
	
	function getSpesifikasi($idbi=""){
		$arrbi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$idbi."'")) ;
		
	 		if ($arrbi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);	

				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];
			}
			
			if ($arrbi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];								
			}
			
			if ($arrbi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);	
					
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];
				
			}
			if ($arrbi['f']=="02"){
				$aqry = "select * from kib_b where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$arrdet['merk']." / ".$arrdet['no_polisi']." / ".$arrdet['no_bpkb']." / ".$arrdet['no_mesin'];
				
			}
			if ($arrbi['f']=="05"){
				$aqry = "select * from kib_e where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$arrdet['buku_judul'];
				
			}
			if ($arrbi['f']=="06"){
				$aqry = "select * from kib_f where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				
				$spesifikasi=$arrdet['alamat'].
							" ".$arrdet['alamat_kel'].
							" ".$arrdet['alamat_kec'].
							" ".$kota['nm_wilayah'];								
			}
			if ($arrbi['f']=="07"){
				$aqry = "select * from kib_g where 
				idbi='".$idbi."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$spesifikasi=$bi['jenis'];
			}
		return $spesifikasi;
	}
	
	
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main,$HTTP_COOKIE_VARS;
			
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 
		$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQueryUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('fmSKPDskpd')!=$vAtas? cekPOST('fmSKPDskpd'): $HTTP_COOKIE_VARS['cofmUNIT'];
	 }else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
	 }
		
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e!='00' and e1='000' GROUP by e";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySubUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('c')!=$vAtas? cekPOST('c'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('d')!=$vAtas? cekPOST('d'): $HTTP_COOKIE_VARS['cofmUNIT'];
		$fmSKPDUnit = cekPOST('fmSKPDUnit_form')!=$vAtas? cekPOST('fmSKPDUnit_form'): $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	}else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
		$fmSKPDUnit=$xplode[2];
	 }
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e='$fmSKPDUnit' and e1!='000' GROUP by e1";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e1'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e1'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e1]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		$divHal = "<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
							"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
						"</div>";
		switch($this->daftarMode){						
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr valign=top>
					<td style='width:$this->containWidth;'>".
						"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
						$divHal.
					"</td>".
					"<td>".
						"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >"."</div>".
					$divHal;					
				break;
			}
		}
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				"<input type='hidden' value='$fmSKPDBidang' id='fmSKPDBidang' name='fmSKPDBidang' >".
				"<input type='hidden' value='$fmSKPDskpd' id='fmSKPDskpd' name='fmSKPDskpd' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		
		//$fmSKPDBidang=cekPOST('fmSKPDBidang');
		// $fmSKPDskpd=cekPOST('fmSKPDskpd');
		 $fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		 $fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranDari');
		 $fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranSampai');
		
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		//data cari ----------------------------
		
		$arrCari = array(
			array('1','1'),
			array('2','2'), 
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILCARI = cekPOST('fmPILCARI');
		 
		
		$TampilOpt =
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			"<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
				$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange='.$this->Prefix.'.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
			"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
				$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
			"</td></tr>".
			"<tr><td width='100'>Tahun</td><td width='10'>:</td><td>
				<input type='text'  size='4' value='$fmThnAnggaranDari' id='fmThnAnggaranDari' name='fmThnAnggaranDari' > s/d <input type='text'  size='4' value='$fmThnAnggaranSampai' id='fmThnAnggaranSampai' name='fmThnAnggaranSampai' >
			</td></tr>			
			</table>".
			"</td>
			</tr></table>".
				genFilterBar(
				array(	
					"<table><tr><td width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Semua','').
					"</td></tr>			
					</table>"					
				),$this->Prefix.".refreshList(true)",TRUE
			);
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari');
		$fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai');
		$fmPILCARI = cekPOST('fmPILCARI');
		 
		$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);
		
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";
		
		if ($fmThnAnggaranDari == $fmThnAnggaranSampai){
		
			if(!($fmThnAnggaranDari=='')  && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_pemanfaatan)>='$fmThnAnggaranDari' and YEAR(tgl_pemanfaatan)<='$fmThnAnggaranSampai' ";
			switch($fmPILCARI){			
			case '1': $arrKondisi[] = " tgl_pemanfaatan>='".$fmThnAnggaranDari."-01-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaranSampai."-06-30' "; break;
			case '2': $arrKondisi[] = " tgl_pemanfaatan>='".$fmThnAnggaranDari."-07-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaranSampai."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThnAnggaranDari=='') && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_pemanfaatan)>='$fmThnAnggaranDari' and YEAR(tgl_pemanfaatan)<='$fmThnAnggaranSampai' ";
		}
				
		
		$kode_rekening  = cekPOST('kode_rekening');
		if(!empty($kode_rekening) ) $arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) like '%$kode_rekening%'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$fmORDER2 = cekPOST('fmORDER2');
		$fmDESC2 = cekPOST('fmDESC2');				
		$Asc2 = $fmDESC2 ==''? '': 'desc';		
		$fmORDER3 = cekPOST('fmORDER3');
		$fmDESC3 = cekPOST('fmDESC3');				
		$Asc3 = $fmDESC3 ==''? '': 'desc';		
		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}
		switch($fmORDER2){
			case '1': $arrOrders[] = " tahun $Asc2 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc2 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc2 " ;break;
		}
		switch($fmORDER3){
			case '1': $arrOrders[] = " tahun $Asc3 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc3 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc3 " ;break;
		}
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = ' Order By tgl_pemanfaatan';// Order By no_terima desc ';
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
$pemanfaatan = new pemanfaatanObj();

?>