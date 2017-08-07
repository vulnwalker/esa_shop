<?php

class PengadaanCariObj  extends DaftarObj2{	
	var $Prefix = 'PengadaanCari';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pengadaan_sk'; //daftar
	var $TblName_Hapus = 'pengadaan_sk';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array( 9);
	var $totalCol = 13; //total kolom daftar
	var $FieldSum_Cp1 = array( 5, 4,4);//berdasar mode
	var $FieldSum_Cp2 = array( 7, 7, 7);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penerimaan, Penyimpanan dan Penyaluran';
	var $PageIcon = 'images/penerimaan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='penerimaan.xls';
	var $Cetak_Judul = 'DAFTAR SPK PENGADAAN BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $FormName = 'PenerimaanForm'; 	
			
	function setTitle(){
		return 'Daftar SPK Pengadaan Barang';
	}
	function setMenuEdit(){
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 //Inisiasi DATA
	 //==================================
	 $id_bukuinduk=$_REQUEST['id_bukuinduk'];
	 $fmIDBARANG=$_REQUEST['fmIDBARANG'];
	 $expfmIDBARANG=explode('.',$fmIDBARANG);
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['fmSKPDUnit_form'];
	 $e1 = $_REQUEST['fmSKPDSubUnit_form'];
	 $f=$expfmIDBARANG['0'];
	 $g=$expfmIDBARANG['1'];
	 $h=$expfmIDBARANG['2'];
	 $i=$expfmIDBARANG['3'];
	 $j=$expfmIDBARANG['4'];
	 $noreg=$_REQUEST['fmnoreg'];
	 $thn_perolehan=$_REQUEST['fmthn_perolehan'];
	 $tgl_pemindahtangan=$_REQUEST['tgl_pemindahtanganan'];
	 $bentuk_pemindahtanganan=$_REQUEST['fmbentuk_pemindahtanganan'];
	 $kpd_nama=$_REQUEST['kepada_nama'];
	 $kpd_alamat=$_REQUEST['kepada_alamat'];
	 $ket=$_REQUEST['fmket'];
	 $thn_anggaran=$_REQUEST['fmthn_anggaran'];
	 $kondisi_akhir=$_REQUEST['kondisi_akhir'];
	 $nosk=$_REQUEST['nosk'];
	 $tglsk=$_REQUEST['tglsk'];
	 $staset=$_REQUEST['staset'];
	 $idbi_awal = $_REQUEST['idbi_awal'];
	 $fmIDREKENING=$_REQUEST['kode_account'];
	 $expfmIDREKENING=explode('.',$fmIDREKENING);
	 $ka=$expfmIDREKENING['0'];
	 $kb=$expfmIDREKENING['1'];
	 $kc=$expfmIDREKENING['2'];
	 $kd=$expfmIDREKENING['3'];
	 $ke=$expfmIDREKENING['4'];
	 $kf=$expfmIDREKENING['5'];
	 $thn_akun=$_REQUEST['tahun_account'];
	 $spek=$_REQUEST['fmMEREK'];
	 $jml_harga=$_REQUEST['jml_harga'];
	 $harga_buku=$_REQUEST['harga_buku'];
	 $ref_idrencana=$_REQUEST['id_rencana'];
	
		if($fmIDBARANG=="" && $err=="")$err="Kode Barang Tidak Boleh Kosong!";
	 	if($fmIDREKENING=="" && $err=="")$err="Pilih Kode Akun!";
		if($bentuk_pemindahtanganan=="" && $err=="")$err="Bentuk Pemindahtanganan Harus Dipilih!";
		if($tgl_pemindahtangan=="" && $err=="")$err="Tanggal Pemindahtanganan Harus Dipilih!";
		if(sudahClosing($tgl_pemindahtangan,$c,$d) && $err=="")$err="Tanggal Pemindahtanganan Sudah Closing!"; 
		if($nosk=="" && $err=="")$err="Nomor SK/Berita Acara Tidak Boleh Kosong!";
		if($tglsk=="" && $err=="")$err="Tanggal SK/Berita Acara Harus Dipilih!";
		if($kpd_nama=="" && $err=="")$err="Nama/Instansi Tidak Boleh Kosong!";
		if($kpd_alamat=="" && $err=="")$err="Alamat Instansi Tidak Boleh Kosong!";
		
		//Cek pemindahtanganan
		//$kondisi1=" id_bukuinduk='$id_bukuinduk' and thn_anggaran='$thn_anggaran'";$cek.=$kondisi1;
		
		if($err==''){ 
			if($fmST == 0){ 
			//Simpan DATA
			//==================================
			//	$ck_pemindahtanganan = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM pemindahtanganan WHERE $kondisi1 "));
				//if($ck_pemindahtanganan['cnt']>0 && $err=="") $err="Barang Sudah Masuk Ke Pemindahtanganan!";
		
				if($err==''){ 
					$aqry= "insert into pemindahtanganan (".
					" id_bukuinduk,".
					" a1,a,b,".
					" c,d,e,e1,f,g,h,i,j,".
					" noreg,thn_perolehan,tgl_pemindahtanganan,".
					" bentuk_pemindahtanganan,kepada_nama,kepada_alamat,".
					" uraian,ket,tahun,kondisi_akhir,".
					" nosk,tglsk,staset,idbi_awal,".
					" ka,kb,kc,kd,ke,kf,thn_akun,".
					" spek,jml_harga,harga_buku,ref_idrencana,".
					" uid,tgl_update".
					" )values(".
					" '$id_bukuinduk',".
					" '$a1','$a','$b',".
					" '$c','$d','$e','$e1','$f','$g','$h','$i','$j',".
					" '$noreg','$thn_perolehan','$tgl_pemindahtangan',".
					" '$bentuk_pemindahtanganan','$kpd_nama','$kpd_alamat',".
					" '','$ket','$thn_anggaran','$kondisi_akhir',".
					" '$nosk','$tglsk','$staset','$idbi_awal',".
					" '$ka','$kb','$kc','$kd','$ke','$kf','$thn_akun',".
					" '$spek','$jml_harga','$harga_buku','$ref_idrencana',".
					" '$uid',now()".
					")";$cek.=$aqry;
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal Simpan Data ".mysql_error();
					
				}
			}elseif($fmST == 1){ 
				$old = mysql_fetch_array(mysql_query("SELECT * FROM pemindahtanganan WHERE Id='$idplh' "));
				if(sudahClosing($old['tgl_pemindahtanganan'],$c,$d) && $err=="")$err="Tanggal Pemindahtanganan Sudah Closing!";
				
			//EDIT DATA
			//==================================					
				if($err==''){
					$aqry= "update pemindahtanganan set ".
					"  id_bukuinduk='$id_bukuinduk',idbi_awal='$idbi_awal',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',".
					" noreg='$noreg', thn_perolehan='$thn_perolehan',tgl_pemindahtanganan='$tgl_pemindahtangan',".
				    " bentuk_pemindahtanganan='$bentuk_pemindahtanganan', kepada_nama='$kpd_nama', kepada_alamat='$kpd_alamat',".
				    " ket='$ket', tahun='$thn_anggaran', kondisi_akhir='$kondisi_akhir',".
				    " nosk='$nosk', tglsk='$tglsk',staset='$staset',".
					" ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',kf='$kf', thn_akun='$thn_akun',".
					" spek='$spek', jml_harga='$jml_harga', harga_buku='$harga_buku', ref_idrencana='$ref_idrencana',".
					" uid='$uid',tgl_update=now()".
					" where Id='$idplh'";$cek.=$aqry; 
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal Edit Data ".mysql_error();						
				}
			}else{
				if($err==''){ 
					$err="Tidak Dapat Menerima ID Pilih";
				}
			} //end else
		}//end if error		
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
		
	
	
	function Hapus(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb'];
	 $idplh = $cbid[0];
	
	 $query = "select * from pemindahtanganan where Id='$idplh'"; $cek .= $query;
	 $ck=mysql_fetch_array(mysql_query($query));
	 if(sudahClosing($ck['tgl_pemindahtanganan'],$ck['c'],$ck['d']))$err="Tanggal Pemindahtanganan Sudah Closing!";

		if($err==''){ 
			$aqry = "DELETE FROM pemindahtanganan WHERE Id='".$idplh."'";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
		}
			
					
		return array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
		
		case 'hapus':{
				$fm = $this->Hapus();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			
		case 'formCari':{				
				$fm = $this->SetFormCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
		case 'pilihcaribi':{				
				$get= $this->SetPilihCariBI();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}			
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'BidangAfter':{
				$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=dpb_rencana.refreshList(true)','--- Pilih SKPD ---','00');
		break;
		}
			
		case 'SKPDAfter':{
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			$fmSKPDskpd = cekPOST('fmSKPDskpd');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT',$fmSKPDskpd);
		break;
	    }	
		
		case 'UnitRefresh':{
				$fmc = cekPOST('c');
				$fmd = cekPOST('d');
				$fme = cekPOST('fmSKPDUnit_form');
				
				
				$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange=.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);	
				
				
			break;
		}
			
		case 'UnitAfter':{
			//$fmSKPDUnit = cekPOST('fmSKPDUnit_form');
			//setcookie('cofmSUBUNIT',$fmSKPDUnit);
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			
			$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',FALSE,'');	
				
			break;
		    }	
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		
		case 'getdata':{
				global $HTTP_COOKIE_VARS;
				$uid = $HTTP_COOKIE_VARS['coID'];
				$cek='';
				$ids = $_REQUEST['id'];//id_pengadaan
				$idpb = $_REQUEST['idpb'];//id_penerimaan_ba
		
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$dt = mysql_fetch_array(mysql_query("select * from pengadaan_sk where id='".$ids."'")) ;
				$spk = explode('-',$dt['spk_tgl']);
				$spk_tgl = (int) $spk[02];
				
				$salin = "select * from pengadaan where ref_idsk='".$dt['id']."'";
				$qry = mysql_query($salin);
				while($ck=mysql_fetch_array($qry)){
					$a1=$ck['a1']; $a=$ck['a']; $b=$ck['b'];
					$c=$ck['c']; $d=$ck['d']; $e=$ck['e'];
					$e1=$ck['e1']; $f=$ck['f']; $g=$ck['g'];
					$h=$ck['h']; $i=$ck['i']; $j=$ck['j'];
					$nm_barang = $ck['nm_barang'];
					$jml_barang = $ck['jml_barang'];
					$harga = $ck['harga'];
					$jml_harga = $ck['jml_harga'];
					$merk = $ck['merk_barang'];
					$ket = $ck['ket'];
					$aqry = "insert into penerimaan (a1,a,b,c,d,f,g,h,i,j,nm_barang,jml_barang,harga,jml_harga,merk_barang,ket,ref_idba,uid,tgl_update) 
							values ('$a1','$a','$b','$c','$d','$f','$g','$h','$i','$j',
							'$nm_barang','$jml_barang','$harga','$jml_harga','$merk','$ket','$idpb',
							'$uid',now())"; $cek.=$aqry;
					$qry2 = mysql_query($aqry);
				}
				
								
				
				$content = array('id_pengadaan'=>$dt['id'],
								 'spk_no'=>$dt['spk_no'],
								 'spk_tgl'=>$dt['spk_tgl'],
								 'spk_tgl_thn'=>$spk[0],	
								 'spk_tgl_bln'=>$spk[1],
								 'spk_tgl_tgl'=>$spk_tgl);	
		break;
	    }
		
	   case 'subtitle':{		
					$content = $this->setTopBar();
					$json=TRUE;
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
		 "<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
		 "<script type='text/javascript' src='js/dpb.js' language='JavaScript' ></script>".	
		 "<script type='text/javascript' src='js/penerimaan/distribusidetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/penerimaandetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$dt['tglsk'] = date('Y-m-d');
		$dt['tgl_pemindahtanganan'] = date('Y-m-d');
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
	global $Main;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		$aqry = "select * from pemindahtanganan where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
			
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
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
	  
	  $arrCaraPerolehan = array(
					array('1','Pembelian'),
					array('2','Hibah'),
					array('3','Lainnya'),
				);
	 	
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];

		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		  $this->form_fields = array(									 
			'bidang' => array( 'label'=>'BIDANG', 
								'labelWidth'=>100,
								'value'=>"<input type='text' name='nm_bidang' id='nm_bidang' size='40px' value='$bidang' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'skpd' => array( 'label'=>'SKPD', 
								'value'=>"<input type='text' name='nm_unit' id='nm_unit' size='40px' value='$unit' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),				

            'thn_anggaran' => array( 
								'label'=>'Tahun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='fmthn_anggaran' id='fmthn_anggaran' size='4' value='".$dt['tahun']."' readonly=''>"
									 ),
			
			'spk' => array(
							'label'=>'', 
							'value'=>'SPK/Kontrak', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'faktur_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['faktur_no'], 
							'type'=>'text'
			),
			'faktur_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['faktur_tgl'], 'faktur_tgl', false,''), 
							'type'=>''
			),	
			
			'kwitansi' => array(
							'label'=>'', 
							'value'=>'SP2D/Kwitansi', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'kwitansi_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['kwitansi_no'], 
							'type'=>'text'
			),
			'kwitansi_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['kwitansi_tgl'], 'kwitansi_tgl', false,''), 
							'type'=>''
			),	
			
			'program' => array(  
							   'label'=>'Program',
							   'value'=> cmbArray('program',$dt['program'],$arrProg,'-- Pilih --',''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),	
			
			'kegiatan' => array(  
							   'label'=>'Kegiatan',
							   'value'=> cmbArray('kegiatan',$dt['kegiatan'],$arrKegiatan,'-- Pilih --',''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),	
			
			'pekerjaan' => array( 
						'label'=>'Pekerjaan',
						'labelWidth'=>100, 
						'value'=>"<textarea name='pekerjaan' id='pekerjaan' style='width:250px'>{$dt['pekerjaan']}</textarea>"			 
						 ),	
			
			'nm_instansi' => array(
							'label'=>'&nbsp;&nbsp;Nama Perusahaan', 
							'value'=>$dt['instansi'], 
							'type'=>'text'
							
			),
			'alamat' => array(
							'label'=>'&nbsp;&nbsp;Alamat', 
							'value'=>$dt['alamat'], 
							'type'=>'text',
							'param'=>"style='width:250px;'"
			),
			'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>100, 
						'value'=>"<textarea name='fmket' id='fmket' style='width:250px'>{$dt['ket']}</textarea>"			 
						 ),	
			
			'rincian_barang' => array( 
						'label'=>'',
						'value'=>"<b>Rincian Barang :<b>",
						'type'=>'merge'
						),
						 
			'pengadaan' => array( 
						'label'=>'',
						'value'=>"
						<div id='pengadaan' ></div>".
						"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>",
						'type'=>'merge'
					 	),
			
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()'  >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal Rencana Pemindahtanganan' >";
							
		$form = $this->genForm();
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		//$id_penggunaan=$_REQUEST['id_penggunaan'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(	
			/*'skpd' => array( 
				'label'=>'',
				'value'=>
					"<table width=\"200\" class=\"adminform\">	<tr>		
					<td width=\"200\" valign=\"top\">" . 					
						WilSKPD_ajx3($this->Prefix.'CariSkpd') . 
						//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr></table>", 
				'type'=>'merge'
			),	*/		
			'div_detailcaribarang' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribarang' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".PilihBarang()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".CloseCariBarang()' >";
		
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
	
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox
		   <th class='th02' colspan='2'>SPK / Kontrak</th>
		   <th class='th01' rowspan='2'>Program/ Kegiatan/ Pekerjaan</th>
		   <th class='th01' rowspan='2'>Jumlah Barang</th>
		   <th class='th01' rowspan='2'>Jumlah Harga</th>
		   <th class='th01' rowspan='2'>Nama Perusahaan</th>
		   <th class='th01' rowspan='2'>Alamat Perusahaan</th>
		   <th class='th01' rowspan='2'>Keterangan</th>
	   </tr>
	   <tr>
	       <th class='th01'>Tanggal</th>
		   <th class='th01'>Nomor</th>
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
	
		return 
			"";
	}
	function setNavAtas(){
		global $Main;
	
		return
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=pengadaancari" title="SPK">SPK</a> |				
				<a href="pages.php?Pg=dpb_rencana" title="Rencana">Rencana</a> |				
				<a href="pages.php?Pg=rkbmd" title="Pengadaan">Pengadaan</a>  |  
				<a href="pages.php?Pg=rekapdkb" title="Rekap">Rekap</a>  |
				<a href="pages.php?Pg=rekapdkb_skpd" title="Rekap SKPD">Rekap SKPD</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 	
	 	//kode/nama barang
		$kd_barang=$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
	 	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."'")) ;
		
		//kode/nama akun
		$kd_account=$isi['ka'].".".$isi['kb'].".".$isi['kc'].".".$isi['kd'].".".$isi['ke'].".".$isi['kf'];		
		$akn = mysql_fetch_array(mysql_query("select * from ref_jurnal where concat(ka,kb,kc,kd,ke,kf)='".$isi['ka'].$isi['kb'].$isi['kc'].$isi['kd'].$isi['ke'].$isi['kf']."' "));

		//spesifikasi/alamat
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'")) ;
		if($bi['f']=='01'){
			$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
		}
		if($bi['f']=='02'){
			$aqry = "select * from kib_b where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['merk'];
		}
		if($bi['f']=='03'){
			$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
		}
		if($bi['f']=='04'){
			$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
		}
		if($bi['f']=='05'){
			$aqry = "select * from kib_e where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
		}
		if($bi['f']=='06'){
			$aqry = "select * from kib_f where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
		}
		
		//harga perolehan
		$harga=number_format($isi['jml_harga'],2,',','.');
		$harga_buku=number_format($isi['harga_buku'],2,',','.');
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $kd_barang.'/'.$brg['nm_barang'].'<br>'.
							  $kd_account.'/'.$akn['nm_account']
		);			
		$Koloms[] = array('align=center', $isi['noreg'].'/<br>'.$isi['thn_perolehan']);
		$Koloms[] = array('', $isi['spek']);
		$Koloms[] = array('align=right', $harga.'/<br>'.$harga_buku) ;
		$Koloms[] = array('align=center', $Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1]);
		$Koloms[] = array('align=center', TglInd($isi['tgl_pemindahtanganan']));
		$Koloms[] = array('align=center', $isi['nosk']);
		$Koloms[] = array('', $isi['ket']);
		return $Koloms;
	}
	
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main;
	 Global $fmSKPDBidang;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
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
	 global $Ref,$Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');
		setcookie('cofmSKPD',$fmSKPDBidang);
		setcookie('cofmUNIT',$fmSKPDskpd);
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
	
	function genDaftarInitial($tipe='',$fmSKPD='',$fmUNIT='',$idpb=''){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $HTTP_COOKIE_VARS['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: $fmSKPD;
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: $fmUNIT;
		$id_penerimaanba = $idpb;
		
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
				"<input type='hidden' value='$id_penerimaanba' id='id_penerimaanba' name='id_penerimaanba' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 $fmSKPDBidang = $_REQUEST['fmSKPDBidang'];
	 $fmSKPDskpd = $_REQUEST['fmSKPDskpd'];
	 $fmThn1=  $_REQUEST['fmThn1'];
	 $fmThn2=  $_REQUEST['fmThn2'];
	 $fmSemester=  $_REQUEST['fmSemester'];
	 $idpb=  $_REQUEST['idpb'];
	
	 
	  $arrOrder = array(
	  	         array('1','1'),
			     array('2','2'),
					);
	 
	$t=date('Y');
	$thnaw = $t-10;
	$thnak = $t+11;
	$opsi = "<option value=''>--Dari Tahun--</option>";
	$opsi2 = "<option value=''>--Tahun--</option>";
	for ($a=$thnaw;$a<=$thnak;$a++){
		//for ($i=$thnaw; $i<$thnak; $i++){
		$sel = $a == $fmThn1? "selected='true'" :'';
		$sel2 = $a == $fmThn2? "selected='true'" :'';
		$opsi.= "<option value='".$a."' ".$sel.">".$a."</option>";
		$opsi2.= "<option value='".$a."' ".$sel2.">".$a."</option>";
	}
	 	
	$TampilOpt = 
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			 "<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
					$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange=PengadaanCari.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
				"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
					$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=PengadaanCari.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
				"</td></tr></table>".
			"</td>
			</tr></table>".
			
			genFilterBar(
						array(	
							"Tahun : &nbsp;"
							."<select name='fmThn1' id='fmThn1'>".$opsi."</select>".
							"&nbsp; s/d &nbsp;"
							."<select name='fmThn2' id='fmThn2'>".$opsi2."</select>".
							"&nbsp;&nbsp;&nbsp; <input type=text name='id_penerimaanba' id='id_penerimaanba' value='$idpb'>  Semester :&nbsp;"
							.cmbArray('fmSemester',$fmSemester,$arrOrder,'--Semua--','')
						),$this->Prefix.".refreshList(true)",TRUE
					)
					;
			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		global $fmPILCARI;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$fmSKPD = cekPOST('fmSKPDBidang');
		$fmUNIT = cekPOST('fmSKPDskpd');
		 $fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		
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
		
		$arrKondisi[]= " tahun='$fmThnAnggaran' and sttemp=0";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[]="c,d";
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
	
	function windowShow(){	
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmTahun = $_REQUEST['fmTahun'];
		$idpb = $_REQUEST['idpb'];
		$tipe='windowshow';	
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		//if($err==''){
			$FormContent = $this->genDaftarInitial($tipe,$fmSKPD,$fmUNIT,$idpb);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						400,
						'Pilih SPK',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
}
$PengadaanCari = new PengadaanCariObj();

?>