<?php
class Penilaian_koreksiObj extends DaftarObj2{
	var $Prefix = 'Penilaian_koreksi';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'penilaian';//'v1_rkb'
	var $TblName_Hapus = 'penilaian';
	var $TblName_Edit = 'penilaian';
	var $KeyFields = array('id');
	var $FieldSum = array();//'harga'
	var $fieldSum_lokasi = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 9, 8, 8);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	var $totalCol = 15; //total kolom daftar
	var $FormName = 'Penilaian_koreksiForm';
	//var $pagePerHal = 7;
	var $PageTitle = 'Penilaian';
	var $PageIcon = 'images/penilaian_ico.gif';
	var $ico_width = 20;
	var $ico_height = 30;	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='HASIL_KOREKSI_HARGA.xls';
	var $Cetak_Judul = 'HASIL KOREKSI HARGA BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setTitle(){
		return 'Hasil Koreksi Harga';
	}
	
	function setMenuEdit(){		
		return
			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Penilaian Koreksi Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit Penilaian Koreksi')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus Penilaian Koreksi')."</td>";
	}
	
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=Penilaian_manfaat" title="Penilaian pemanfaatan barang" >Pemanfaatan</a> |			
				<a href="pages.php?Pg=Penilaian_pindahtangan" title="Penilaian pemindahtanganan barang">Pemindahtanganan</a>  |  
				<a style="color:blue;" href="pages.php?Pg=Penilaian_koreksi" title="Koreksi penilaian">Koreksi</a> 
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
		$staset = $_REQUEST['staset'];
		$idbi_awal = $_REQUEST['ref_idbi_awal'];
		$id_bukuinduk = $_REQUEST['ref_idbi'];
		
		
		$fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
		$f = $fmIDBARANG[0];
		$g = $fmIDBARANG[1];
		$h = $fmIDBARANG[2];
		$i = $fmIDBARANG[3];
		$j = $fmIDBARANG[4];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		
		$fmIDREKENING = explode('.',$_REQUEST['kode_account']);
		$ka = $fmIDREKENING[0];
		$kb = $fmIDREKENING[1];
		$kc = $fmIDREKENING[2];
		$kd = $fmIDREKENING[3];
		$ke = $fmIDREKENING[4];
		$kf = $fmIDREKENING[5];
		$nama_account = $_REQUEST['nm_account'];
		$thn_akun = $_REQUEST['tahun_account'];
		
		$noreg=$_REQUEST['fmnoreg'];
		$thn_perolehan=$_REQUEST['fmtahunperolehan'];
		$tgl_penilaian=$_REQUEST['tgl_buku'];
		$nilai_barang_asal=str_replace(".","",$_REQUEST['fmharga_perolehan']);
		$harga_buku=str_replace(".","",$_REQUEST['fmharga_buku']);
		$hrg_koreksi=str_replace(".","",$_REQUEST['fmharga_koreksi']);
		$nilai_barang=$_REQUEST['fmharga_Perolehankoreksi'];
		$surat_no=$_REQUEST['surat_no'];
		$surat_tgl=$_REQUEST['surat_tgl'];
		$ket=$_REQUEST['fmKET'];
		
		$old = mysql_fetch_array( mysql_query(
			"select * from $this->TblName_Edit where id='$id' "		
		));
		
		
		//-- validasi
		if($err=='' && sudahClosing($tgl_penilaian,$c,$d))$err = "Tanggal Sudah Closing!";
		if($err=='' && ($_REQUEST['tgl_buku_tgl'] == '' || $_REQUEST['tgl_buku_bln'] == '' || $_REQUEST['tgl_buku_thn'] == ''))$err = "Tanggal buku belum Lengkap!";
		if($err=='' && $surat_no == '')$err = "Nomor berita acara belum diisi!";
		if($err=='' && ($_REQUEST['surat_tgl_tgl'] == '' || $_REQUEST['surat_tgl_bln'] == '' || $_REQUEST['surat_tgl_thn'] == ''))$err = "Tanggal berita acara belum Lengkap!";
		if($err=='' && $nilai_barang == '')$err = "Harga perolehan koreksi belum diisi!";
		if($err=='' && $fmIDBARANG == '')$err = "Barang belum dipilih!";
		if($err=='' && $fmIDREKENING == '')$err = "Kode akun belum dipilih!";
		
		
		if($err==''){
			if($fmST == 0){//baru
				//$ck_penilaian = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM $this->TblName_Edit WHERE id_bukuinduk='$id_bukuinduk'"));
				//if($ck_penilaian['cnt']>0 && $err=="") $err="Barang Sudah Masuk Ke Penilaian!";
				$aqry = 
					"insert into $this->TblName_Edit ".
					"( a1,a,b,c,d,e,e1,".
					" f,g,h,i,j,".
					" noreg,thn_perolehan,tgl_penilaian,".
					" nilai_barang_asal,nilai_barang,".
					" surat_no,surat_tgl,ket,tahun,".
					" ka,kb,kc,kd,ke,kf,".
					" thn_akun,".					
					" staset,id_bukuinduk,idbi_awal,".					
					" hrg_koreksi,harga_buku,".
					" uid,tgl_update) ".
					"values ".
					"( '$a1','$a','$b','$c','$d','$e','$e1',".
					" '$f','$g','$h','$i','$j',".
					" '$noreg','$thn_perolehan','$tgl_penilaian',".
					" '$nilai_barang_asal','$nilai_barang',".
					" '$surat_no','$surat_tgl','$ket','$tahun',".
					" '$ka','$kb','$kc','$kd','$ke','$kf',".
					" '$thn_akun',".					
					" '$staset','$id_bukuinduk','$idbi_awal',".				
					" '$hrg_koreksi','$harga_buku',".
					" '$UID',now())";
				
			}else{				
				if($err=='' && sudahClosing($old['tgl_penilaian'],$c,$d))$err = "Tanggal Sudah Closing!";
				$aqry = 
					"update $this->TblName_Edit  ".
					"set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',e='$e',e1='$e1',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',".
					" noreg='$noreg',thn_perolehan='$thn_perolehan',tgl_penilaian='$tgl_penilaian',".
					" nilai_barang_asal='$nilai_barang_asal',nilai_barang='$nilai_barang',".
					" surat_no='$surat_no',surat_tgl='$surat_tgl',ket='$ket',tahun='$tahun',".
					" ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',kf='$kf',".
					" thn_akun='$thn_akun',".					
					" staset='$staset',id_bukuinduk='$id_bukuinduk',idbi_awal='$idbi_awal',".					
					" hrg_koreksi='$hrg_koreksi',harga_buku='$harga_buku',".
					" uid='$UID', tgl_update= now() ".
					"where id='".$old['id']."' ";
				
			}
			$cek .= $aqry;
			$qry = mysql_query($aqry);
			if($qry == FALSE) $err='Gagal SQL'.mysql_error();
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
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/penilaian/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
		 	
			$scriptload;
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru':{				
				//echo 'tes';
				$get=$this->setFormBaru();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];			
				break;
			}
			case 'formEdit':{								
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
			case 'BidangAfter':{
				$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter()','--- Pilih SKPD ---','00');
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
			case 'cekkoreksi':{
				$get= $this->cekkoreksi();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
				
				$spesifikasi=$arrdet['merk']." / ".$arrdet['no_polisi']." / ".$arrdet['no_bpkb'];
				
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
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$dt['surat_tgl'] = date("Y-m-d");//tanggal surat
		$dt['tgl_penilaian'] = date("Y-m-d");//tanggal Buku
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	
	function setFormEdit(){		
		global $Main;
		$cek = ''; $err=''; $content='';// $json=FALSE;
		$form = '';
		
		$cbid = $_POST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		$kueri="select * from ref_jurnal 
					where thn_akun = '".$dt['thn_akun']."' 
					and ka='".$dt['ka']."' and kb='".$dt['kb']."' 
					and kc='".$dt['kc']."' and kd='".$dt['kd']."'
					and ke='".$dt['ke']."' and kf='".$dt['kf']."'"; //echo "$kueri";
		$akn=mysql_fetch_array(mysql_query($kueri));	
		$dt['nm_account']=$akn['nm_account'];
		//$dt['harga_buku']=getNilaiBuku($dt['id_bukuinduk'],$dt['tgl_pemindahtanganan'],0);
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
            'thn_anggaran' => array( 
					'label'=>'Tahun',
					'labelWidth'=>150, 
					'value'=>"<input type='text' name='fmtahun' id='fmtahun' size='4' value='".$dt['tahun']."' readonly=''>"
									 ),
			'dipergunakan' => array(
					'label'=>'', 
					'value'=>'Unit Pengguna', 
					'type'=>'merge',
					'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'unit' => array( 
					'label'=>'Unit', 
					'value'=> '<div id=Unit_formdiv>'.$pilihUnit.'</div>' ,
					'type'=>'' 
			),
			'subunit' => array( 
					'label'=>'Sub Unit', 
					'value'=> '<div id=SubUnit_formdiv>'.$pilihSubUnit.'</div>',
					'type'=>'' 
			),
			'nm_barang' => array( 
							'label'=>'Kode Barang',
							'labelWidth'=>150, 
							'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_brg' readonly=''>".
									 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$brg['nm_barang']."' readonly=''>".
									 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".cariBI()\" title='Cari Kode Barang' >"		 
									 ),
			'kode_account' => array( 
							'label'=>'Kode Akun',
							'labelWidth'=>100, 
							'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
										  <input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>
										  <input type=hidden id='tahun_account' name='tahun_account' value='".$dt['thn_akun']."'>" 
									 ),
			'noreg' => array( 
							'label'=>'Noreg/Tahun',
							'labelWidth'=>150, 
							'value'=>"<input type='text' name='fmnoreg' id='fmnoreg' size='4' value='".$dt['noreg']."' readonly=''> / 
							<input type='text' name='fmtahunperolehan' id='fmtahunperolehan' size='4' value='".$dt['thn_perolehan']."' readonly=''>"
									 ),									 
			'spesifikasi' => array( 
							'label'=>'Spesifikasi/Alamat',
							'labelWidth'=>150,
							'value'=> "<textarea style='width:438;' id='fmspesifikasi' name='fmspesifikasi' readonly=''>".$dt['spesifikasi']."</textarea> ", 
							'row_params'=>"valign='top'",
							'type'=>'' 
								),	
			'harga_perolehan' => array( 
							'label'=>'Harga Perolehan/Harga Buku',
							'labelWidth'=>150, 
							'value'=>"<input type='text' id='fmharga_perolehan' name='fmharga_perolehan' value='".number_format($dt['nilai_barang_asal'],2,',','.' )."' readonly> / ".
										"<input type='text' id='fmharga_buku' name='fmharga_buku' value='".number_format($dt['harga_buku'],2,',','.' )."' readonly>"	
									 ),
			'berita_acara' => array(
							'label'=>'', 
							'value'=>'Berita Acara', 
							'type'=>'merge',
							'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
							),
			'surat_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['surat_no'], 
							'type'=>'text'
			),
			'surat_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl('surat_tgl',$dt['surat_tgl'] ), 
							'type'=>''
			),
			'harga_koreksi' => array( 
							'label'=>'Harga Koreksi',
							'labelWidth'=>150, 
							'value'=>"<input type='text' id='fmharga_koreksi' name='fmharga_koreksi' value='".number_format($dt['hrg_koreksi'],2,',','.' )."' readonly>"
									 ),
			'harga_Perolehankoreksi' => array( 
							'label'=>'Harga Perolehan',
							'labelWidth'=>150, 
							'value'=>inputFormatRibuan("fmharga_Perolehankoreksi", "onblur=\"".$this->Prefix.".cekkoreksi()\"",$dt['nilai_barang']) ,
				),
			'tgl_buku' => array(
							'label'=>'Tanggal Buku', 
							'value'=> createEntryTgl('tgl_buku',$dt['tgl_penilaian'] ), 
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >"
			;
		
		//$this->genForm_nodialog();
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function cekkoreksi(){
	 global $Main;
	 $err="";
	 	$harga_perolehan_asal=str_replace(".","",$_REQUEST['fmharga_perolehan']);
		$harga_Perolehankoreksi=$_REQUEST['fmharga_Perolehankoreksi'];
		$harga_koreksi=$harga_Perolehankoreksi-$harga_perolehan_asal;
		$content->plhharga_koreksi=number_format($harga_koreksi,2,',','.' );
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
	
	function SetPilihCariBI(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$tgl_penilaian = $_REQUEST['tgl_buku'];
		$ids = $_REQUEST['cidBI'];
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
			
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
						
			$content->plhid_buku_induk = $bi['id'];
			$content->plhidbi_awal = $bi['idawal'];
			$content->plhIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;
			$content->plhNMBARANG = $brg['nm_barang'];
			$content->plhkode_akun = $akn['ka'].'.'.$akn['kb'].'.'.$akn['kc'].'.'.$akn['kd'].'.'.$akn['ke'].'.'.$akn['kf'] ;
			$content->plhnama_akun = $akn['nm_account'];
			$content->plhthn_akun = $akn['thn_akun'];			
			$content->plhstaset = $bi['staset'];
			$content->plhnoreg = $bi['noreg'];
			$content->plhtahun =  $bi['tahun'];
			$content->plhspesifikasi=$this->getSpesifikasi($bi['id']);
			$content->plhharga_perolehan=number_format($bi['jml_harga'],2,',','.' );
			$harga_buku=getNilaiBuku($bi['id'],$tgl_penilaian,0);	
			$content->plhharga_buku=number_format($harga_buku,2,',','.' );			
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
					<th class='th01' width='40' rowspan=2 width='40'>No.</th>
					$Checkbox		
					<th class='th01' width='' rowspan=2 width=''>SKPD/Unit Pengguna</th>		
					<th class='th01' width='' rowspan=2 width=''>Kode Barang/ Kode Akun</th>		
					<th class='th01' width='' rowspan=2 width=''>Nama Barang/ Nama Akun</th>
					<th class='th01' width='' rowspan=2 width=''>Noreg/ Tahun</th>
					<th class='th01' rowspan=2 width=''>Spesifikasi/ Alamat</th>
					<th class='th01' rowspan=2 width=''>Harga Perolehan/ Harga Buku</th>
					<th class='th01' rowspan=2 width=''>Harga Koreksi/ Harga Buku Koreksi</th>
					<th class='th02' colspan=2 width=''>Berita Acara</th>
					<th class='th01' rowspan=2 width=''>Tanggal Buku/ Keterangan </th>
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
		
		$brg = mysql_fetch_array(mysql_query(
				"select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));	
		$akn = mysql_fetch_array(mysql_query(
				"select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' and kf='".$isi['kf']."' and thn_akun='".$isi['thn_akun']."'"));	
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'")) ;
		$harga_buku=getNilaiBuku($isi['id_bukuinduk'],$isi['tgl_pemindahtanganan'],0);
		$nmopdarr=array();		
		if($isi['c'] != '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_bidang where c='".$isi['c']."' "
			));		
			if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
		}
		if($isi['d'] != '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
			$get = mysql_fetch_array(mysql_query(
				"select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' "
			));		
			if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
		}
		if($isi['e'] != '00'){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_unit where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."'"
			));		
			if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
		}
		if($isi['e1'] != '00' || $fmSEKSI == '000'){
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
			));		
			if($get['nmseksi']<>'') $nmopdarr[] = $get['nmseksi'];
		}
		$get = mysql_fetch_array(mysql_query(
				"select ref_jurnal as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));	
		
		$nmopd = join(' - ', $nmopdarr );
		$spesifikasi=$this->getSpesifikasi($isi['id_bukuinduk']);	
		$harga_buku_koreksi=$isi['nilai_barang']-$isi['harga_buku'];	
				
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array("align=''",  $nmopd);
		$Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."/<br/>".
			$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'].'.'.$isi['kf']
		);
		$Koloms[] = array("align=''",  $brg['nm_barang']."/<br/>".$akn['nm_account'] );
		$Koloms[] = array("align=''",  $isi['noreg']."/<br/>".$isi['thn_perolehan'] );
		$Koloms[] = array("align=''", $spesifikasi	);				
		$Koloms[] = array("align='right'", number_format($isi['nilai_barang_asal'] ,2,',','.' )."&nbsp;/</br>"
							.number_format($isi['harga_buku'] ,2,',','.' ));
		$Koloms[] = array("align='right'", number_format($isi['hrg_koreksi'] ,2,',','.' )."/<br/>"
							.number_format($harga_buku_koreksi,2,',','.' ));
		$Koloms[] = array("align='right'", $isi['surat_no'] );
		$Koloms[] = array("align='center'",  TglInd($isi['surat_tgl'] ) );		
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_penilaian'] )."/<br/>".$isi['ket'] );
		return $Koloms;
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		return "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' stat='".$isi['stat']."'  onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
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
		
		$arrSemester = array(
			array('1','1'),
			array('2','2'), 
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		 
		
		$TampilOpt =
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			"<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
				$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange='.$this->Prefix.'.BidangAfter() ','--- Pilih BIDANG ---','00')."</td></tr>".
			"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
				$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter() ','--- Pilih SKPD ---','00').
			"</td></tr>".
			"<tr><td width='100'>Tahun</td><td width='10'>:</td><td>
					<input type='text'  size='4' value='$fmThnAnggaranDari' id='fmThnAnggaranDari' name='fmThnAnggaranDari' > s/d <input type='text'  size='4' value='$fmThnAnggaranSampai' id='fmThnAnggaranSampai' name='fmThnAnggaranSampai' >
			</td></tr>".
			"</table>".
			"</td>
			</tr></table>".
				genFilterBar(
				array(	
					"<table>
					<td  width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILSEMESTER',$fmPILSEMESTER,$arrSemester,'Semua','').
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
		$fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		 
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
		
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "YEAR(tgl_penilaian)='$fmThnAnggaran'";
		
		if ($fmThnAnggaranDari == $fmThnAnggaranSampai){
		
			if(!($fmThnAnggaranDari=='')  && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_penilaian)>='$fmThnAnggaranDari' and YEAR(tgl_penilaian)<='$fmThnAnggaranSampai' ";
			switch($fmPILSEMESTER){			
			case '1': $arrKondisi[] = " tgl_penilaian>='".$fmThnAnggaranDari."-01-01' and  cast(tgl_penilaian as DATE)<='".$fmThnAnggaranSampai."-06-30' "; break;
			case '2': $arrKondisi[] = " tgl_penilaian>='".$fmThnAnggaranDari."-07-01' and  cast(tgl_penilaian as DATE)<='".$fmThnAnggaranSampai."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThnAnggaranDari=='') && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_penilaian)>='$fmThnAnggaranDari' and YEAR(tgl_penilaian)<='$fmThnAnggaranSampai' ";
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
		$OrderDefault = ' ';// Order By no_terima desc ';
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
$Penilaian_koreksi = new Penilaian_koreksiObj();

?>