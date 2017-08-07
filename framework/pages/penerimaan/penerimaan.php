<?php

class PenerimaanObj  extends DaftarObj2{	
	var $Prefix = 'Penerimaan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v_penerimaan2'; //daftar
	var $TblName_Hapus = 'v_penerimaan2';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array('jml_barang','harga','jml_harga',
						  'jml_distribusi','sisa');
	var $SumValue = array();
	var $fieldSum_lokasi = array(8);
	var $totalCol = 13; //total kolom daftar
	var $FieldSum_Cp1 = array( 9, 4,4);//berdasar mode
	var $FieldSum_Cp2 = array( 9, 7, 7);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penerimaan, Penyimpanan dan Penyaluran';
	var $PageIcon = 'images/penerimaan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='penerimaan.xls';
	var $Cetak_Judul = 'DAFTAR PENERIMAAN BARANG MILIK DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $FormName = 'PenerimaanForm'; 	
			
	function setTitle(){
		return 'Daftar Penerimaan Barang Milik Daerah';
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
	 $idp = $_REQUEST['idp'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $thn_anggaran=$_REQUEST['fmthn_anggaran'];
	 $bano=$_REQUEST['ba_no'];
	 $batgl=$_REQUEST['ba_tgl'];
	 $spkno=$_REQUEST['spk_no'];
	 $spktgl=$_REQUEST['spk_tgl'];
	 $cara_perolehan=$_REQUEST['cara_perolehan'];
	 $instansi_nama=$_REQUEST['nm_instansi'];
	 $instansi_alamat=$_REQUEST['alamat'];
	 $ket=$_REQUEST['fmket'];
	 $id_pengadaan=$_REQUEST['id_pengadaan'];
	 
	 
	
		/*
		if($bano=="" && $err=="")$err="Nomor BAST Penerimaan tidak boleh kosong!";
	 	if($batgl=="" && $err=="")$err="Tanggal BAST Penerimaan Harus dipilih!";
		if($spkno=="" && $err=="")$err="Nomor SPK tidak boleh kosong!";
		if($cara_perolehan=="" && $err=="")$err="Cara Perolehan Harus Dipilih!";
		if($instansi_nama=="" && $err=="")$err="Nama Instansi/Perusahaan Tidak Boleh Kosong!";
		if($instansi_alamat=="" && $err=="")$err="Alamat Instansi/Perusahaan Tidak Boleh Kosong!";
		*/ 
		
		
		if($err==''){ 
			if($fmST == 0){ 

				if($err==''){ 
					
					$aqry= "update penerimaan_ba set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',tahun='$thn_anggaran',".
					" ba_no='$bano',ba_tgl='$batgl',".
					" spk_no='$spkno',spk_tgl='$spktgl',id_pengadaan='$id_pengadaan',".
					" cara_perolehan='$cara_perolehan',".
					" instansi_nm='$instansi_nama',instansi_alamat='$instansi_alamat',ket='$ket',".
					" uid='$uid',tgl_update=now(),sttemp=0".
					" where Id='$idp'";$cek.=$aqry; 
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal Simpan Data ".mysql_error();
				}
				
			}elseif($fmST == 1){ 
						
				if($err==''){
					
					$aqry= "update penerimaan_ba set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',tahun='$thn_anggaran',".
					" ba_no='$bano',ba_tgl='$batgl',".
					" spk_no='$spkno',spk_tgl='$spktgl',id_pengadaan='$id_pengadaan',".
					" cara_perolehan='$cara_perolehan',".
					" instansi_nm='$instansi_nama',instansi_alamat='$instansi_alamat',ket='$ket',".
					" uid='$uid',tgl_update=now(),sttemp=0".
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
				$ids = $_REQUEST['cidBI'];//735477
		
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
				$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
								
				//Kode barang
				$kd_barang = str_replace('.','',$kdbrg);
				$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
				
				//Kode Akun
				$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
				$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
				$tmax = mysql_fetch_array(mysql_query($kueri1));
				$kueri="select * from ref_jurnal 
						where thn_akun = '".$tmax['thn_akun']."' 
						and ka='".$br['ka']."' and kb='".$br['kb']."' 
						and kc='".$br['kc']."' and kd='".$br['kd']."'
						and ke='".$br['ke']."' and kf='".$br['kf']."'"; //echo "$kueri";
				$row=mysql_fetch_array(mysql_query($kueri));
				$kdAkun =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
				
				//spesifikasi&alamat
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
				
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('".$bi['id']."',now(),'0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				
				$content = array('id_bukuinduk'=>$bi['id'],
								 'idbi_awal'=>$bi['idawal'],
								 'kd_barang'=>$kdbrg,
								 'nm_barang'=>$br['nm_barang'],
								 'kd_akun'=>$kdAkun,
								 'nm_akun'=>$row['nm_account'],
								 'thn_akun'=>$row['thn_akun'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'merk'=>$merk,
								 'kondisi'=>$bi['kondisi'],
								 'staset'=>$bi['staset'],
								 'jml_harga'=>$bi['jml_harga'],
								 'harga_buku'=>$hb);	
		break;
	    }
		
		case 'getbi':{
				$id_bukuinduk = $_REQUEST['id_bukuinduk'];//735477
		
				//cari BI
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='$id_bukuinduk'")) ;

				//spesifikasi&alamat
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
				
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('".$bi['id']."',now(),'0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				
				$content = array('idbi_awal'=>$bi['idawal'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'merk'=>$merk,
								 'kondisi'=>$bi['kondisi'],
								 'staset'=>$bi['staset'],
								 'jml_harga'=>$bi['jml_harga'],
								 'harga_buku'=>$hb);	
		break;
	    }
		
		case 'getHargaBuku':{
				$idbi = $_REQUEST['idbi'];
				$tgl = $_REQUEST['tgl'];
		
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('$idbi','$tgl','0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				$content = $hb;	
		break;
	    }
		
		case 'Clear':{
				$cek=''; $err=''; $content='';
				$idp = $_REQUEST['idp'];
		
				$aqry = "delete from penerimaan where ref_idba = '$idp' ";
				$qry = mysql_query($aqry); $cek.=$aqry;
				
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
		 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/distribusidetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/penerimaandetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/pengadaancari.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
	global $Main;
		$dt=array();
		$this->form_fmST = 0;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$query="INSERT into penerimaan_ba(uid,tgl_update,sttemp)"." values('$uid',NOW(),'1')"; $cek.=$query;
		$result=mysql_query($query);
		$dt['id']=mysql_insert_id();
		$this->form_idplh =$dt['id'];
		
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
		
		$aqry = "select * from penerimaan_ba where Id='".$this->form_idplh."'"; $cek.=$aqry;
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
	  	if($dt['id_pengadaan'] != ''){
			$dis = TRUE;
			$read = "readonly";
		}else{
			$dis = FALSE;
			$read = "";
		}
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
								'value'=>"<input type='text' name='nm_bidang' id='nm_bidang' size='50px' value='$bidang' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'skpd' => array( 'label'=>'SKPD', 
								'value'=>"<input type='text' name='nm_unit' id='nm_unit' size='50px' value='$unit' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),				

            'thn_anggaran' => array( 
								'label'=>'Tahun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='fmthn_anggaran' id='fmthn_anggaran' size='4' value='".$dt['tahun']."' readonly=''>"
									 ),
			
			'bast' => array(
							'label'=>'', 
							'value'=>'BAST Penerimaan', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'ba_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['ba_no'], 
							'type'=>'text'
			),
			'ba_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['ba_tgl'], 'ba_tgl', false,''), 
							'type'=>''
			),	
			
			'kontrak' => array(
							'label'=>'', 
							'value'=>'SPK / Kontrak', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			
			'spk_no' => array( 
								'label'=>'&nbsp;&nbsp;Nomor',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='spk_no' id='spk_no' size='20' value='".$dt['spk_no']."' $read>".
										 "&nbsp;<input type='button' value='Cari' onclick ='".$this->Prefix.".CariSPK()' title='Cari SPK' >".			 
										 "&nbsp;<input type='button' value='Clear' onclick ='".$this->Prefix.".Clear()' title='Cari SPK' >"			 
			),

			'spk_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['spk_tgl'], 'spk_tgl', $dis,''), 
							'type'=>''
			),	
			
			'cara_perolehan' => array(  
							   'label'=>'Cara Perolehan',
							   'value'=> cmbArray('cara_perolehan',$dt['cara_perolehan'],$arrCaraPerolehan,'-- Pilih --',''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),	
			
			'instansi' => array(
							'label'=>'', 
							'value'=>'Nama Perusahaan/ Instansi', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'nm_instansi' => array(
							'label'=>'&nbsp;&nbsp;Instansi', 
							'value'=>$dt['instansi_nm'], 
							'type'=>'text'
							
			),
			'alamat' => array(
							'label'=>'&nbsp;&nbsp;Alamat', 
							'value'=>$dt['instansi_alamat'], 
							'type'=>'text',
							'param'=>"style='width:250px;'"
			),
			'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>100, 
						'value'=>"<textarea name='fmket' id='fmket' style='width:250px'>{$dt['ket']}</textarea>"			 
						 ),	
						 
			'penerimaandetail' => array( 
						'label'=>'',
						'value'=>"
						<div id='penerimaandetail' ></div>".
						"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>",
						'type'=>'merge'
					 	),
			
			'bast_distribusi' => array( 
						'label'=>'',
						'value'=>"<b>BAST DISTRIBUSI :<b>",
						'type'=>'merge'
						),
			
			'distribusidetail' => array( 
						'label'=>'',
						'value'=>"<div id='distribusidetail' ></div>".
						"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>", 
						'type'=>'merge'
					 	)		
			
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' name='idp' id='idp' value='".$this->form_idplh."'>".
			"<input type='hidden' name='id_pengadaan' id='id_pengadaan' value='".$dt['id_pengadaan']."'>".
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
		   <th class='th02' colspan='2'>BAST</th>
		   <th class='th01' rowspan='2'>Cara Perolehan</th>
		   <th class='th01' rowspan='2'>Kode Barang/<br>Nama Barang</th>
		   <th class='th01' rowspan='2'>Merk/ Type/ Spesifikasi/ Alamat</th>
		   <th class='th01' rowspan='2'>Jumlah Barang<br>Penerimaan</th>
		   <th class='th01' rowspan='2'>Harga Satuan</th>
		   <th class='th01' rowspan='2'>Jumlah Harga</th>
		   <th class='th01' rowspan='2'>Jumlah Distribusi</th>
		   <th class='th01' rowspan='2'>Sisa Jumlah</th>
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
				<a style="color:blue;" href="pages.php?Pg=penerimaan" title="Daftar Penerimaan Barang Milik Daerah">Penerimaan</a>  |  
				<a href="pages.php?Pg=distribusi" title="Daftar Distribusi Barang Milik Daerah">Distribusi</a> '.
				'&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	
	/*function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	
		$aqry = "select * from(SELECT ".
				  "`penerimaan_ba`.*, `penerimaan`.`id` as `id_penerimaan`, `penerimaan`.`f`, `penerimaan`.`g`, `penerimaan`.`h`, ".
				  "`penerimaan`.`i`, `penerimaan`.`j`, `penerimaan`.`nm_barang`, ".
				  "`penerimaan`.`harga`, `penerimaan`.`jml_barang`, `penerimaan`.`jml_harga`, ".
				  "`penerimaan`.`merk_barang` ".
				"FROM ".
				  "`penerimaan_ba` RIGHT JOIN ".
				  "`penerimaan` ON `penerimaan_ba`.`Id` = `penerimaan`.`ref_idba` ) `cc` ".
				"$Kondisi $Order $Limit ";
		return $aqry;		
	}*/
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 	
		$kdbarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
		$nmbarang = $isi['nm_barang'];
		
		if($isi['cara_perolehan']=='1'){
			$perolehan='Pembelian';
		}elseif($isi['cara_perolehan']=='2'){
			$perolehan='Hibah';
		}elseif($isi['cara_perolehan']=='3'){
			$perolehan='Lainnya';
		}
		
		//jml_distribusi
		//$row = mysql_fetch_array(mysql_query("select * from pengeluaran where ref_idterima='".$isi['id_penerimaan']."'"));
		$jml_distribusi = $isi['jml_distribusi'];
		$sisa_jumlah = $isi['sisa'];
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=center', $isi['ba_tgl']);			
		$Koloms[] = array('align=center', $isi['ba_no']);
		$Koloms[] = array('',$perolehan);
		$Koloms[] = array('',$kdbarang.'/<br>'.$nmbarang) ;
		$Koloms[] = array('',$isi['merk_barang']);
		$Koloms[] = array('align=right', number_format($isi['jml_barang'],0, ',', '.'));
		$Koloms[] = array('align=right', number_format( $isi['harga'] ,2,',','.'));
		$Koloms[] = array('align=right', number_format( $isi['jml_harga'] ,2,',','.'));
		$Koloms[] = array('align=right', number_format($jml_distribusi,0, ',', '.'));
		$Koloms[] = array('align=right', number_format($sisa_jumlah,0, ',', '.'));
		$Koloms[] = array('', $isi['ket']);
		return $Koloms;
	}
	
	/*function setSumHal_query($Kondisi, $fsum){
	
		return "select $fsum from (select * from(SELECT
				  `penerimaan_ba`.*, `penerimaan`.`f`, `penerimaan`.`g`, `penerimaan`.`h`,
				  `penerimaan`.`i`, `penerimaan`.`j`, `penerimaan`.`nm_barang`,
				  `penerimaan`.`harga`, `penerimaan`.`jml_barang`, `penerimaan`.`jml_harga`,
				  `penerimaan`.`merk_barang`
				FROM
				  `penerimaan_ba` RIGHT JOIN
				  `penerimaan` ON `penerimaan_ba`.`Id` = `penerimaan`.`ref_idba` ) `cc` 
				$Kondisi) `dd` "; //echo $aqry;
		
	}*/
	
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
	 global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	 $fmThn1=  $_REQUEST['fmThn1'];
	 $fmThn2=  $_REQUEST['fmThn2'];
	 $fmSemester=  $_REQUEST['fmSemester'];
	 
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
					$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange=Penerimaan.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
				"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
					$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=Penerimaan.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
				"</td></tr></table>".
			"</td>
			</tr></table>".
			
			genFilterBar(
						array(	
							"Tahun : &nbsp;"
							."<select name='fmThn1' id='fmThn1'>".$opsi."</select>".
							"&nbsp; s/d &nbsp;"
							."<select name='fmThn2' id='fmThn2'>".$opsi2."</select>".
							"&nbsp;&nbsp;&nbsp; Semester :&nbsp;"
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
		//$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		//$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThn1=  cekPOST('fmThn1');
		$fmThn2=  cekPOST('fmThn2');
		$fmSemester = cekPOST('fmSemester');
		
		/*$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);*/
		$arrKondisi[] = "sttemp=0";
		$fmSKPD = cekPOST('fmSKPDBidang');
		$fmUNIT = cekPOST('fmSKPDskpd');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		
		if ($fmThn1 == $fmThn2){
		
			if(!($fmThn1=='')  && !($fmThn2=='')) $arrKondisi[] = " YEAR(ba_tgl) >='$fmThn1' and YEAR(ba_tgl) <='$fmThn2' ";
			switch($fmSemester){			
			case '1': $arrKondisi[] = " ba_tgl>='".$fmThn1."-01-01' and  cast(ba_tgl as DATE)<='".$fmThn2."-06-30' "; break;
			case '2': $arrKondisi[] = " ba_tgl>='".$fmThn1."-07-01' and  cast(ba_tgl as DATE)<='".$fmThn2."-12-31' "; break;
			default :""; break;
			}
		}else{
			if(!($fmThn1=='') && !($fmThn2=='')) $arrKondisi[] = "YEAR(ba_tgl) >='$fmThn1' and YEAR(ba_tgl) <='$fmThn2' ";
		}
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[]="c,d";
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
		//$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$tahun_anggaran = $_REQUEST['tahun_anggaran'];	
		$tipe='windowshow';	
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		//if($err==''){
			$FormContent = $this->genDaftarInitial($tipe,$fmSKPD,$fmUNIT);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						400,
						'Pilih Rencana Pemanfaatan',
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
			$jmlData = $isi['cnt'];			
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue($value, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}	
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = '';
		//if( sizeof($vSum)==0) $vsum
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}
	
	function genSum_setTampilValue($fieldName, $value){
		switch($fieldName){
			case 'jml_barang': case 'jml_distribusi':  case 'sisa' : return number_format($value, 0, ',' ,'.'); break;
			default : return number_format($value, 2, ',' ,'.'); break;
		}
	}	
	
	function genRowSum_setTampilValue($i, $value){
		switch($i){
			case '0' : case '3' : case '4' : return number_format($value,0, ',', '.'); break;
			default : return number_format($value,2, ',', '.');	break;		
		}		
	}
	
}
$Penerimaan = new PenerimaanObj();

?>