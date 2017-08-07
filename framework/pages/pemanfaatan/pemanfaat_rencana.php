<?php

class Pemanfaat_RencanaObj  extends DaftarObj2{	
	var $Prefix = 'Pemanfaat_Rencana';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_rencana_pemanfaatan'; //daftar
	var $TblName_Hapus = 't_rencana_pemanfaatan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array('harga');//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array( 9);
	var $totalCol = 14; //total kolom daftar
	var $FieldSum_Cp1 = array( 7, 6,6);//berdasar mode
	var $FieldSum_Cp2 = array( 3, 3, 3);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Rencana Pemanfaatan Barang Daerah';
	var $PageIcon = 'images/pemanfaatan_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Pemanfaatan Rencana.xls';
	var $Cetak_Judul = 'RENCANA PEMANFAATAN BARANG DAERAH';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $FormName = 'Pemanfaat_RencanaForm'; 	
			
	function setTitle(){
		return 'Rencana Pemanfaatan Barang Daerah';
	}
	function setMenuEdit(){
		return "";
			/*"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit1()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";*/
	}
	
	function setMenuView(){
		return  
		"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
		<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>				
		<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>
		";
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
	 $id_bukuinduk=$_REQUEST['fmid_buku_induk'];
	 $fmIDBARANG=$_REQUEST['fmIDBARANG'];
	 $expfmIDBARANG=explode('.',$fmIDBARANG);
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
	 $f=$expfmIDBARANG['0'];
	 $g=$expfmIDBARANG['1'];
	 $h=$expfmIDBARANG['2'];
	 $i=$expfmIDBARANG['3'];
	 $j=$expfmIDBARANG['4'];
	 $nm_barang=$_REQUEST['fmNMBARANG'];
	 $fmIDREKENING=$_REQUEST['kode_account'];
	 $expfmIDREKENING=explode('.',$fmIDREKENING);
	 $ka=$expfmIDREKENING['0'];
	 $kb=$expfmIDREKENING['1'];
	 $kc=$expfmIDREKENING['2'];
	 $kd=$expfmIDREKENING['3'];
	 $ke=$expfmIDREKENING['4'];
	 $kf=$expfmIDREKENING['5'];
	 $thn_akun=$_REQUEST['tahun_account'];
	 $nm_akun=$_REQUEST['nama_account'];
	 $noreg=$_REQUEST['fmnoreg'];
	 $thn_perolehan=$_REQUEST['fmthn_perolehan'];
	 $harga_perolehan=$_REQUEST['fmharga_perolehan'];
	 $bentuk_pemanfaatan=$_REQUEST['fmbentuk_pemanfaatan'];
	 $jangkawaktu=$_REQUEST['fmjangkawaktu'];
	 $ket=$_REQUEST['fmket'];
	 $thn_anggaran=$_REQUEST['fmthn_anggaran'];
	 $idbi_awal=$_REQUEST['fmidbi_awal'];
	 	if($fmIDREKENING=="" && $err=="")$err="Pilih Kode Akun!";
		if($fmIDBARANG=="" && $err=="")$err="Kode Barang Tidak Boleh Kosong!";
		if($bentuk_pemanfaatan=="" && $err=="")$err="Bentuk Pemanfaatan Harus Dipilih!";
		if($jangkawaktu=="" && $err=="")$err="Jangka Waktu Tidak Boleh Kosong!";
		if($err==''){ 
			if($fmST == 0){ 
			//Simpan DATA
			//==================================
				
				if($err==''){ 
					$aqry= "insert into t_rencana_pemanfaatan (".
					" id_bukuinduk,idbi_awal,".
					" a1,a,b,".
					" c,d,e,e1,f,g,h,i,j,nm_barang,".
					" ka,kb,kc,kd,ke,kf,nm_account,thn_akun,".
					" noreg,harga,thn_perolehan,thn_anggaran,".
					" bentuk_pemanfaatan,jangkawaktu,ket,".
					" uid,tgl_update".
					" )values(".
					" '$id_bukuinduk','$idbi_awal',".
					" '$a1','$a','$b',".
					" '$c','$d','$e','$e1','$f','$g','$h','$i','$j','$nm_barang',".
					" '$ka','$kb','$kc','$kd','$ke','$kf','$nm_akun','$thn_akun',".
					" '$noreg','$harga_perolehan','$thn_perolehan','$thn_anggaran',".
					" '$bentuk_pemanfaatan','$jangkawaktu','$ket',".
					" '$uid',now()".
					")";
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal Simpan Data ".mysql_error();
					
				}else{
					$err="Gagal menyimpan barang";
				}
			}elseif($fmST == 1){ 
			//EDIT DATA
			//==================================					
				if($err==''){
					$aqry= "update t_rencana_pemanfaatan set ".
					"  id_bukuinduk='$id_bukuinduk',idbi_awal='$idbi_awal',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',nm_barang='$nm_barang',".
					" ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',kf='$kf',nm_account='$nm_akun', thn_akun='$thn_akun',".
					" noreg='$noreg', harga='$harga_perolehan',thn_perolehan='$thn_perolehan', thn_anggaran='$thn_anggaran',".
					" bentuk_pemanfaatan='$bentuk_pemanfaatan', jangkawaktu='$jangkawaktu', ket='$ket',".
					" uid='$uid',tgl_update=now()".
					" where Id='$idplh'"; 
					$qry = mysql_query($aqry);
					if($qry==FALSE) $err="Gagal Edit Data ".mysql_error();						
				}else{
					$err="Gagal menyimpan barang";					
				}
			}else{
			if($err==''){ 
				$err="Tidak Dapat Menerima ID Pilih";
			}
			} //end else
		}//end if error		
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
		
	function SetPilihCariBI(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
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
			$row=mysql_fetch_array(mysql_query($kueri));
						
			$content->plhkode_account =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
			$content->plhnama_account = $row['nm_account'];
			$content->plhtahun_account = $row['thn_akun'];
			$content->plhid_buku_induk = $bi['id'];
			$content->plhidbi_awal = $bi['idawal'];
			$content->plhIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->plhNMBARANG = $brg['nm_barang'];
			$content->plhnoreg = $bi['noreg'];
			$content->plhthn_perolehan = $bi['thn_perolehan'];
			$content->plhharga_perolehan = $bi['harga'];
			$content->plhvalharga_perolehan = number_format($bi['harga'],0,',','.');
			$content->plhasalusul = $Main->AsalUsul[$bi['asal_usul']-1][1];
			$content->plhkondisi = $Main->KondisiBarang[$bi['kondisi']-1][1];
			if ($bi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhluas = $arrdet['luas'];
				$content->plhalamat=$arrdet['alamat'];
				$content->plhkel=$arrdet['alamat_kel'];
				$content->plhkec=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$content->plhkota=$kota['nm_wilayah'];
			}
			if ($bi['f']=="02"){
				$aqry = "select * from kib_b where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhmerk = $arrdet['merk'];
				$content->plhukuran=$arrdet['ukuran'];
				$content->plhbahan=$arrdet['bahan'];
				
			}
			if ($bi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhkonstruksi = $Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
				$content->plhtingkat = $Main->Tingkat[$arrdet['konstruksi_tingkat']-1][1];
				$content->plhbeton = $Main->Beton[$arrdet['konstruksi_beton']-1][1];
				
				$content->plhluas = $arrdet['luas_lantai'];
				$content->plhalamat=$arrdet['alamat'];
				$content->plhkel=$arrdet['alamat_kel'];
				$content->plhkec=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$content->plhkota=$kota['nm_wilayah'];
			}
			if ($bi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhkonstruksi = $Main->Bangunan[$arrdet['konstruksi']-1][1];
				$content->plhpanjang = $Main->Tingkat[$arrdet['panjang']-1][1];
				$content->plhlebar = $Main->Beton[$arrdet['lebar']-1][1];
				
				$content->plhluas = $arrdet['luas_lantai'];
				$content->plhalamat=$arrdet['alamat'];
				$content->plhkel=$arrdet['alamat_kel'];
				$content->plhkec=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$content->plhkota=$kota['nm_wilayah'];
			}
			if ($bi['f']=="05"){
				$aqry = "select * from kib_e where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhjudul = $arrdet['buku_judul'];
				$content->plhspesifikasi=$arrdet['buku_spesifikasi'];
				$content->plhasal=$arrdet['seni_asal_daerah'];
				$content->plhpencipta=$arrdet['seni_pencipta'];
				$content->plhjenis=$arrdet['hewan_jenis'];
				$content->plhukuran=$arrdet['hewan_ukuran'];
				$content->plhbahan=$arrdet['seni_bahan'];
			}
			if ($bi['f']=="06"){
				$aqry = "select * from kib_f where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$content->plhkonstruksi = $Main->Bangunan[$arrdet['bangunan']-1][1];
				$content->plhtingkat = $Main->Tingkat[$arrdet['konstruksi_tingkat']-1][1];
				$content->plhbeton = $Main->Beton[$arrdet['konstruksi_beton']-1][1];
				
				$content->plhluas = $arrdet['luas'];
				$content->plhalamat=$arrdet['alamat'];
				$content->plhkel=$arrdet['alamat_kel'];
				$content->plhkec=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$content->plhkota=$kota['nm_wilayah'];
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
	   
	   case 'subtitle':{		
					$content = $this->setTopBar();
					$json=TRUE;
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
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			break;
		}	
		
		case 'getdata':{
	
				$ref_pilihdkb = $_REQUEST['id'];
				$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
				//query ambil data ref_jurnal
				$get = mysql_fetch_array( mysql_query("select * from t_rencana_pemanfaatan where id='$ref_pilihdkb'"));
				$cek .= "select * from t_rencana_pemanfaatan where id='$ref_pilihdkb'";
				
				//query ambil data barang
				$brg = mysql_fetch_array(mysql_query("select * from ref_barang where 
										f='".$get['f']."' and g='".$get['g']."' and 
										h='".$get['h']."' and i='".$get['i']."' and 
										j='".$get['j']."'"));
				$kd_barang = $brg['f'].".".$brg['g'].".".$brg['h'].".".$brg['i'].".".$brg['j'];
				$nm_barang=$brg['nm_barang'];
				
				//query ambil data akun
				$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
				$tmax = mysql_fetch_array(mysql_query($kueri1));
				$akn = mysql_fetch_array(mysql_query("select * from ref_jurnal where thn_akun = '".$tmax['thn_akun']."' 
										and	ka='".$get['ka']."' and kb='".$get['kb']."' 
										and kc='".$get['kc']."' and kd='".$get['kd']."' 
										and ke='".$get['ke']."' and kf='".$get['kf']."'"));
				$akn = array_map('utf8_encode', $akn);
				$kd_account = $akn['ka'].".".$akn['kb'].".".$akn['kc'].".".$akn['kd'].".".$akn['ke'].".".$akn['kf'];
				$nm_account = $akn['nm_account'];
				$thn_akun = $akn['thn_akun'];
				/*
				if ($brg['f']=="07"){
					$err = "KIB G Tidak Bisa Dipilih!";
				}*/	
				$content = array('idpemanfaat'=>$ref_pilihdkb,
								 'kd_barang'=>$kd_barang,
								 'nm_barang'=>$nm_barang,
								 'kd_account'=>$kd_account,
								 'nm_account'=>$nm_account,
								 'thn_akun'=>$thn_akun,
								 'e'=>$get['e'],
								 'e1'=>$get['e1'],
								 'err'=>$err
								 );	
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
		 "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/pemanfaatan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$cek =$cbid[0];			
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$dt['thn_anggaran'] = $_COOKIE['coThnAnggaran'];
		
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
	global $Main;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		$aqry = "select * from t_rencana_pemanfaatan where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$dt['id_bukuinduk']."'")) ;
						
			$dt['harga_perolehan'] = $bi['harga'];
			$dt['valharga_perolehan'] = number_format($bi['harga'],0,',','.');
			$dt['asal_usul'] = $Main->AsalUsul[$bi['asal_usul']-1][1];
			
			$dt['kondisi'] = $Main->KondisiBarang[$bi['kondisi']-1][1];
			
			if ($bi['f']=="01"){
				$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['luas'] = $arrdet['luas'];
				$dt['alamat']=$arrdet['alamat'];
				$dt['kel']=$arrdet['alamat_kel'];
				$dt['kec']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$dt['kota']=$kota['nm_wilayah'];
			}
			if ($bi['f']=="02"){
				$aqry = "select * from kib_b where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['merk'] = $arrdet['merk'];
				$dt['ukuran']=$arrdet['ukuran'];
				$dt['bahan']=$arrdet['bahan'];
				
			}
			if ($bi['f']=="03"){
				$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['konstruksi'] = $Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
				$dt['tingkat'] = $Main->Tingkat[$arrdet['konstruksi_tingkat']-1][1];
				$dt['beton'] = $Main->Beton[$arrdet['konstruksi_beton']-1][1];
				
				$dt['luas'] = $arrdet['luas'];
				$dt['alamat']=$arrdet['alamat'];
				$dt['kel']=$arrdet['alamat_kel'];
				$dt['kec']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$dt['kota']=$kota['nm_wilayah'];
			}
			if ($bi['f']=="04"){
				$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['konstruksi'] = $Main->Bangunan[$arrdet['konstruksi']-1][1];
				$dt['panjang'] = $Main->Tingkat[$arrdet['panjang']-1][1];
				$dt['lebar'] = $Main->Beton[$arrdet['lebar']-1][1];
				
				$dt['luas'] = $arrdet['luas_lantai'];
				$dt['alamat']=$arrdet['alamat'];
				$dt['kel']=$arrdet['alamat_kel'];
				$dt['kec']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$dt['kota']=$kota['nm_wilayah'];
			}
			if ($bi['f']=="05"){
				$aqry = "select * from kib_e where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['judul'] = $arrdet['buku_judul'];
				$dt['spesifikasi']=$arrdet['buku_spesifikasi'];
				$dt['asal']=$arrdet['seni_asal_daerah'];
				$dt['pencipta']=$arrdet['seni_pencipta'];
				$dt['jenis']=$arrdet['hewan_jenis'];
				$dt['ukuran']=$arrdet['hewan_ukuran'];
				$dt['bahan']=$arrdet['seni_bahan'];
			}
			if ($bi['f']=="06"){
				$aqry = "select * from kib_f where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$dt['konstruksi'] = $Main->Bangunan[$arrdet['bangunan']-1][1];
				$dt['tingkat'] = $Main->Tingkat[$arrdet['konstruksi_tingkat']-1][1];
				$dt['beton'] = $Main->Beton[$arrdet['konstruksi_beton']-1][1];
				
				$dt['luas'] = $arrdet['luas'];
				$dt['alamat']=$arrdet['alamat'];
				$dt['kel']=$arrdet['alamat_kel'];
				$dt['kec']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);														
				$dt['kota']=$kota['nm_wilayah'];
				
				
			}				
		}
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
	 	
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd']; 				
 	    
		
	   	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'")) ;
	   	$kode_skpd = $dt['f']==''? '' : $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;			
		$kode_account = $dt['ka']==''? '' : $dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'].'.'.$dt['kf'];
		
		$title="KIB";
		
		//-- set visible entry u kib -----------------------------
		$fmkibavisible = "style='display:none'"; //untuk alamat
		$fmkibbvisible = "style='display:none'"; 
		$fmkibcvisible = "style='display:none'";
		$fmkibdvisible = "style='display:none'"; //untuk sertifikat
		$fmkibevisible = "style='display:none'";
		$fmkibfvisible = "style='display:none'"; 
		switch ( $dt['f']){
			case '01': $fmkibavisible = "style='display:block'"; break;			
			case '02': $fmkibbvisible = "style='display:block'"; break;
			case '03': $fmkibcvisible= "style='display:block'";break;
			case '04': $fmkibdvisible= "style='display:block'";break;
			case '05': $fmkibevisible = "style='display:block'"; break;
			case '06': $fmkibfvisible= "style='display:block'";break;
		}
		
		//FORM DETAIL PEMANFAATAN
		//========================
		$this->form_fields = array(	
			'btk_pemanfaatan' => array(
				'label'=> 'Bentuk Pemanfaatan',
				'labelWidth'=>150,
				'value'=> cmb2D('fmbentuk_pemanfaatan',$dt['bentuk_pemanfaatan'],$Main->BentukPemanfaatan,''), 
				'type'=>''
				),
			'jangka_waktu' => array(
				'label'=>'Jangka Waktu', 
				'value'=>'<INPUT type=text name="fmjangkawaktu" id="fmjangkawaktu" value="'.$dt['jangkawaktu'].'" size="4" onkeypress="return isNumberKey(event)"> Tahun',
				'type'=>''
				),
			'ket' => array( 
				'label'=>'Keterangan',
				'labelWidth'=>100, 
				'value'=>"<textarea name='fmket' id='fmket' cols='83'>{$dt['ket']}</textarea>"			 
				 ),	
			
		);
		$rencana_pemanfaatan_formdet = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB A
		//========================
		$this->form_fields = array(	
			'KIBA' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB A</div>', 
					'type'=>'merge' 
					),
			'luasA' => array(
				'label'=>'Luas (m2)',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluasa" id="fmluasa" value="'.$dt['luas'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'alamatA' => array( 
				'label'=>'Letak/Alamat', 
				'labelWidth'=>150,
				'value'=> "<textarea style='width:438;' id='fmalamata' name='fmalamata' readonly=''>".$dt['alamat']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),
			'alamat_kelA' => array( 
				'label'=>'Kelurahan/kec/kota',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kela" id="fmalamat_kela" value="'.$dt['kel'].'" readonly="">&nbsp
						  <INPUT type=text name="fmalamat_keca" id="fmalamat_keca" value="'.$dt['kec'].'" readonly="">&nbsp
						  <INPUT type=text name="fmalamat_kotaa" id="fmalamat_kotaa" value="'.$dt['kota'].'" readonly="">' , 
				'type'=>'' 
				),
			
			
		);
		$formKIBA = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB B
		//========================
		$this->form_fields = array(	
			'KIBB' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB B</div>', 
					'type'=>'merge' 
					),
			'merkB' => array( 
				'label'=>'Merk/Type/Spesifikasi',
				 'labelWidth'=>150,
				'value'=> "<textarea style='width:438;' id='fmmerk' name='fmmerk' readonly=''>".$dt['merk']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),		
			'ukuranB' => array(
				'label'=>'Ukuran',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmukuranb" id="fmukuranb" value="'.$dt['ukuran'].'" readonly="">',
				'type'=>''
				),
			'bahanB' => array(
				'label'=>'Bahan',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmbahanb" id="fmbahanb" value="'.$dt['bahan'].'" readonly="">',
				'type'=>''
				),
			
			
		);
		$formKIBB = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB C
		//========================
		$this->form_fields = array(	
			'KIBC' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB C</div>', 
					'type'=>'merge' 
					),
			'konstruksiC' => array(
				'label'=>'Konstruksi Bangunan', 
				'labelWidth'=>150,
				'value'=>'<INPUT type=text name="fmkonstruksic" id="fmkonstruksic" value="'.$dt['konstruksi'].'" readonly="">',
				'type'=>''
				),
			'tingkatC' => array(
				'label'=>'Bertingkat/Tidak',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmtingkatc" id="fmtingkatc" value="'.$dt['tingkat'].'" readonly="">',
				'type'=>''
				),
			'betonC' => array(
				'label'=>'Beton/Tidak', 
				'labelWidth'=>150,
				'value'=>'<INPUT type=text name="fmbetonc" id="fmbetonc" value="'.$dt['beton'].'" readonly="">',
				'type'=>''
				),
			'luasC' => array(
				'label'=>'Luas Total Lantai (m2)',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluasc" id="fmluasc" value="'.$dt['luas'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'alamatC' => array( 
				'label'=>'Letak/Alamat',
				'labelWidth'=>150, 
				'value'=> "<textarea style='width:438;' id='fmalamatc' name='fmalamatc' readonly=''>".$dt['alamat']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),
			'alamat_kelC' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kelc" id="fmalamat_kelc" value="'.$dt['kel'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kecC' => array( 
				'label'=>'Kecamatan',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kecc" id="fmalamat_kecc" value="'.$dt['kec'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kotaC' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kotac" id="fmalamat_kotac" value="'.$dt['kota'].'" readonly="">' , 
				'type'=>'' 
				),
		);
		$formKIBC = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB D
		//========================
		$this->form_fields = array(	
			'KIBD' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB D</div>', 
					'type'=>'merge' 
					),
			'konstruksiD' => array(
				'label'=>'Konstruksi', 
				'labelWidth'=>150,
				'value'=>'<INPUT type=text name="fmkonstruksid" id="fmkonstruksid" value="'.$dt['konstruksi'].'" readonly="">',
				'type'=>''
				),
			'panjangD' => array(
				'label'=>'Panjang (Km)', 
				'labelWidth'=>150,
				'value'=>'<INPUT type=text name="fmpanjang" id="fmpanjang" value="'.$dt['panjang'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'lebarD' => array(
				'label'=>'Lebar (m2)', 
				'labelWidth'=>150,
				'value'=>'<INPUT type=text name="fmlebar" id="fmlebar" value="'.$dt['lebar'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'luasD' => array(
				'label'=>'Luas (m2)',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluasd" id="fmluasd" value="'.$dt['luas'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'alamatD' => array( 
				'label'=>'Letak/Alamat',
				'labelWidth'=>150,
				'value'=> "<textarea style='width:438;' id='fmalamatd' name='fmalamatd' readonly=''>".$dt['alamat']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),			
			'alamat_kelD' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_keld" id="fmalamat_keld" value="'.$dt['kel'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kecd' => array( 
				'label'=>'Kecamatan',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kecd" id="fmalamat_kecd" value="'.$dt['kec'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kotad' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kel', 
				'value'=>'<INPUT type=text name="fmalamat_kotad" id="fmalamat_kotad" value="'.$dt['kota'].'" readonly="">' , 
				'type'=>'' 
				),
		);
		$formKIBD = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB E
		//========================
		$this->form_fields = array(	
			'KIBE' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB E</div>', 
					'type'=>'merge' 
					),
			'detE1' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'Buku Perpustakaan', 
					'type'=>'merge' 
					),
			'judulE' => array( 
				'label'=>'Judul/Pencipta',
				'labelWidth'=>150,
				'name'=>'judulE', 
				'value'=>'<INPUT type=text name="fmjudul" id="fmjudul" value="'.$dt['judul'].'" readonly="">' , 
				'type'=>'' 
				),
			'spesifikasiE' => array( 
				'label'=>'Spesifikasi',
				'labelWidth'=>150,
				'name'=>'spesifikasiE', 
				'value'=>'<INPUT type=text name="fmspesifikasi" id="fmspesifikasi" value="'.$dt['spesifikasi'].'" readonly="">' , 
				'type'=>'' 
				),
			'detE2' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'Barang Bercorak Kesenian/Kebudayaan', 
					'type'=>'merge' 
					),
			'asalE' => array( 
				'label'=>'Asal Daerah',
				'labelWidth'=>150,
				'name'=>'asalE', 
				'value'=>'<INPUT type=text name="fmasal" id="fmasal" value="'.$dt['asal'].'" readonly="">' , 
				'type'=>'' 
				),
			'penciptaE' => array( 
				'label'=>'Pencipta',
				'labelWidth'=>150,
				'name'=>'penciptaE', 
				'value'=>'<INPUT type=text name="fmpencipta" id="fmpencipta" value="'.$dt['pencipta'].'" readonly="">' , 
				'type'=>'' 
				),
			'bahanE' => array( 
				'label'=>'Bahan',
				'labelWidth'=>150,
				'name'=>'bahanE', 
				'value'=>'<INPUT type=text name="fmbahane" id="fmbahane" value="'.$dt['bahan'].'" readonly="">' , 
				'type'=>'' 
				),
			'detE3' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'Hewan Ternak', 
					'type'=>'merge' 
					),
			'jenisE' => array( 
				'label'=>'Jenis',
				'labelWidth'=>150,
				'name'=>'jenisE', 
				'value'=>'<INPUT type=text name="fmjenis" id="fmjenis" value="'.$dt['jenis'].'" readonly="">' , 
				'type'=>'' 
				),
			'ukuranE' => array( 
				'label'=>'Ukuran',
				'labelWidth'=>150,
				'name'=>'ukuranE', 
				'value'=>'<INPUT type=text name="fmukurane" id="fmukurane" value="'.$dt['ukuran'].'" readonly="">' , 
				'type'=>'' 
				),
			
		);
		$formKIBE = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//FORM KIB F
		//========================
		$this->form_fields = array(	
			'KIBF' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'<div style="font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0">KIB F</div>', 
					'type'=>'merge' 
					),
			'konstruksiF' => array(
				'label'=>'Bangunan',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmkonstruksif" id="fmkonstruksif" value="'.$dt['konstruksi'].'" readonly="">',
				'type'=>''
				),
			'detF1' => array(
					'label'=>'',
					'labelWidth'=>150,
					'value'=>'Konstruksi Bangunan', 
					'type'=>'merge' 
					),			
			'tingkatF' => array(
				'label'=>'&nbps&nbps&nbpsBertingkat/Tidak',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmtingkatf" id="fmtingkatf" value="'.$dt['tingkat'].'" readonly="">',
				'type'=>''
				),
			'betonF' => array(
				'label'=>'&nbps&nbps&nbpsBeton/Tidak',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmbetonf" id="fmbetonf" value="'.$dt['beton'].'" readonly="">',
				'type'=>''
				),
			'luasF' => array(
				'label'=>'Luas (m2)',
				'labelWidth'=>150, 
				'value'=>'<INPUT type=text name="fmluasf" id="fmluasf" value="'.$dt['luas'].'" size="4" 					
					onkeypress="return isNumberKey(event)" readonly="">',
				'type'=>''
				),
			'alamatF' => array( 
				'label'=>'Letak/Alamat',
				'labelWidth'=>150, 
				'value'=> "<textarea style='width:438;' id='fmalamatf' name='fmalamatf' readonly=''>".$dt['alamat']."</textarea> ", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' 
				),
			'alamat_kelF' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kelF', 
				'value'=>'<INPUT type=text name="fmalamat_kelf" id="fmalamat_kelf" value="'.$dt['kel'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kecF' => array( 
				'label'=>'Kecamatan',
				'labelWidth'=>150,
				'name'=>'alamat_kecF', 
				'value'=>'<INPUT type=text name="fmalamat_kecf" id="fmalamat_kecf" value="'.$dt['kec'].'" readonly="">' , 
				'type'=>'' 
				),
			'alamat_kotaF' => array( 
				'label'=>'Kelurahan/Desa',
				'labelWidth'=>150,
				'name'=>'alamat_kotaF', 
				'value'=>'<INPUT type=text name="fmalamat_kotaf" id="fmalamat_kotaf" value="'.$dt['kota'].'" readonly="">' , 
				'type'=>'' 
				),
			
		);
		$formKIBF = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		/*****************************************************************
		 *				FORM UTAMA                                       *
		 *                                                               *
		 *****************************************************************/
		  $this->form_fields = array(									 
			'bidang' => array( 'label'=>'BIDANG', 
								'labelWidth'=>150,
								'value'=>$bidang, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'unit' => array( 'label'=>'SKPD', 
								'value'=>$unit, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'subunit' => array( 'label'=>'UNIT', 
								'value'=>$subunit, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'seksi' => array( 'label'=>'SUB UNIT', 
								'value'=>$seksi, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			
            'thn_anggaran' => array( 
								'label'=>'Tahun Anggaran',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmthn_anggaran' id='fmthn_anggaran' size='4' value='".$dt['thn_anggaran']."' readonly=''>"
									 ),
			'nm_barang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_skpd' readonly=''>".
										 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='60' value='".$brg['nm_barang']."' readonly=''>".
										 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
									 ),
			/*'nm_akun' => array( 'label'=>'Nama Akun', 
								'value'=>cariInfo("adminform","pages/01/cariakun1.php","pages/01/cariakun2_pemanfaatan.php","fmIDREKENING","fmNMREKENING", "ReadOnly='TRUE'"),
								'type'=>''  
			),*/
			'kode_account' => array( 
								'label'=>'Nama Akun',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='kode_account' value='$kode_account' size='15px' id='kode_account' readonly>
										  <input type='text' name='nama_account' value='".$dt['nm_account']."' size='60px' id='nama_account' readonly>
										  <input type=hidden id='tahun_account' name='tahun_account' value='".$dt['thn_akun']."'>
										  <input type='button' value='Cari' onclick ='".$this->Prefix.".CariJurnal()' title='Cari Jurnal' >" 
									 ),
			'noreg' => array( 
								'label'=>'No Register',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmnoreg' id='fmnoreg' value='".$dt['noreg']."' readonly=''>"
									 ),
            'thn_perolehan' => array( 
								'label'=>'Tahun Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='fmthn_perolehan' id='fmthn_perolehan' value='".$dt['thn_perolehan']."' readonly=''>"
									 ),
			'harga_perolehan' => array( 
								'label'=>'Harga Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='valharga_perolehan' id='valharga_perolehan' value='".$dt['valharga_perolehan']."' readonly=''>
										<input type=hidden id='fmharga_perolehan' name='fmharga_perolehan' value='".$dt['harga_perolehan']."'>"
									 ),
			'asal_usul' => array( 
								'label'=>'Asal/Cara Perolehan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='valasalusul' id='valasalusul' value='".$dt['asal_usul']."' readonly=''>"
									 ),
			'kondisi' => array( 
								'label'=>'Kondisi Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='valkondisi' id='valkondisi' value='".$dt['kondisi']."' readonly=''>"
									 ),
			
			'fmkiba' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkiba' name='rencana_pemanfaatan_formkiba' $fmkibavisible>".$formKIBA."</div>",
				'type'=>'merge'
				),
			'fmkibb' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkibb' name='rencana_pemanfaatan_formkibb' $fmkibbvisible>".$formKIBB."</div>",
				'type'=>'merge'
				),
			'fmkibc' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkibc' name='rencana_pemanfaatan_formkibc' $fmkibcvisible>".$formKIBC."</div>",
				'type'=>'merge'
				),
			'fmkibd' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkibd' name='rencana_pemanfaatan_formkibd' $fmkibdvisible>".$formKIBD."</div>",
				'type'=>'merge'
				),
			'fmkibe' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkibe' name='rencana_pemanfaatan_formkibe' $fmkibevisible>".$formKIBE."</div>",
				'type'=>'merge'
				),
			'fmkibf' => array(
				'label'=> '',
				'value'=> "<div id='rencana_pemanfaatan_formkibf' name='rencana_pemanfaatan_formkibf' $fmkibfvisible>".$formKIBF."</div>",
				'type'=>'merge'
				),
				'det_rencana_pemanfaatan' => array(
				'label'=> '',
				'labelWidth'=>150,
				'value'=> "<div id='det_rencana_pemanfaatan' name='det_rencana_pemanfaatan' >".$rencana_pemanfaatan_formdet."</div>",
				'type'=>'merge'
			),
			);
		//tombol
		$this->form_menubawah =	
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='hidden' id='fmid_buku_induk' name='fmid_buku_induk' value='".$dt['id_bukuinduk']."' >".
			"<input type='hidden' id='fmidbi_awal' name='fmidbi_awal' value='".$dt['idbi_awal']."' >".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()'  >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' title='Batal Rencana Pemanfaatan' >";
							
		$form = $this->genForm();
			
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function SetFormCari(){
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
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd']; 	
		
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
	
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox
		   <th class='th01' rowspan='2'>SKPD/UNIT<br>Pengguna Barang</th>
		   <th class='th01' rowspan='2'>Kode / Nama Barang<br>Kode / Nama Akun</th>
		   <th class='th01' rowspan='2'>Noreg</th>
		   <th class='th01' rowspan='2'='2'>Tahun Perolehan</th>
		   <th class='th01' rowspan='2'>Spesifikasi / Alamat</th>
		   <th class='th01' rowspan='2'>Harga Perolehan</th>
		   <th class='th01' rowspan='2'>Bentuk Pemanfaatan</th>
		   <th class='th01' rowspan='2'>Kategori</th>
		   <th class='th01' rowspan='2'>Keterangan</th>
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
	
		return 
			"";
	}
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=pemanfaat_rencana" title="Rencana Pemanfaatan Barang" style="color:blue;">Rencana</a> |				
				<a href="pages.php?Pg=pemanfaatan" title="Pemanfaatan Barang">Pemanfaatan</a>  |  
				<a href="pages.php?Pg=rekappemanfaatan" title="Rekap Pemanfaatan Barang">Rekap</a>  |
				<a href="pages.php?Pg=rekappemanfaatan_skpd" title="Rekap Pemanfaatan Barang SKPD">Rekap SKPD</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
		$nmopdarr=array();		
		
		$get = mysql_fetch_array(mysql_query(
			"select * from v_bidang where c='".$isi['c']."' "
		));		
		if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];

		$get = mysql_fetch_array(mysql_query(
			"select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' "
		));		
		if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
		
		$nmopd = join(' - ', $nmopdarr );
		
		$kd_barang=$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$kd_account=$isi['ka'].".".$isi['kb'].".".$isi['kc'].".".$isi['kd'].".".$isi['ke'].".".$isi['kf'];
	 	$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."'")) ;
		$akn = mysql_fetch_array(mysql_query("select * from ref_jurnal where concat(ka,kb,kc,kd,ke,kf)='".$isi['ka'].$isi['kb'].$isi['kc'].$isi['kd'].$isi['ke'].$isi['kf']."' "));
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'")) ;
		if($bi['f']=='01'){
			$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['sertifikat_no'];
			$ukuran=$arrdet['luas']." m2";
		}
		if($bi['f']=='02'){
			$aqry = "select * from kib_b where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['merk'];
			$no_det=$arrdet['no_pabrik']."/".$arrdet['no_rangka']."/".$arrdet['no_mesin'];
			$ukuran=$arrdet['ukuran']."";
		}
		if($bi['f']=='03'){
			$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
		}
		if($bi['f']=='04'){
			$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['konstruksi']-1][1];
		}
		if($bi['f']=='05'){
			$aqry = "select * from kib_e where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
			$no_det="";
			$ukuran=$arrdet['hewan_ukuran'];
		}
		if($bi['f']=='06'){
			$aqry = "select * from kib_f where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['bangunan']-1][1];
		}
		
		$harga=number_format($isi['harga'],2,',','.');
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $nmopd);			
		$Koloms[] = array('', 
							$kd_barang.'/'.$brg['nm_barang'].'<br>'.
							$kd_account.'/'.$akn['nm_account']
					);			
		$Koloms[] = array('align=center', $isi['noreg']);
		$Koloms[] = array('align=center', $isi['thn_perolehan']);
		$Koloms[] = array('', $merk.'<br>'.$no_det) ;
		$Koloms[] = array('align=right', $harga);
		$Koloms[] = array('', $Main->BentukPemanfaatan[$isi['bentuk_pemanfaatan']-1][1]);
		//$Koloms[] = array('', $isi['jangkawaktu']." Tahun");
		$Koloms[] = array('', '');
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
	
	function genDaftarInitial($tipe='',$fmSKPD='',$fmUNIT='',$fmSUBUNIT='',$fmSEKSI='',$fmkategori='',$height=''){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmFiltThnAnggaran = $_COOKIE['coThnAnggaran'];
		
		if($tipe=='windowshow'){
			$jns = "<input type='hidden' value='windowshow' id='jns' name='jns' >";
			$title = "";
		}else{
			$jns = "<input type='hidden' value='' id='jns' name='jns' >";
			$title = "<div id='{$this->Prefix}_cont_title' style='position:relative'></div>";
		}
		
		return			
			$title.
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' value='$fmFiltThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				$jns.		
				"<input type='hidden' id='fmSKPDBidang' name='fmSKPDBidang' value='$fmSKPD'>".
				"<input type='hidden' id='fmSKPDskpd' name='fmSKPDskpd' value='$fmUNIT'>".		
				"<input type='hidden' id='fmSKPDUnit' name='fmSKPDUnit' value='$fmSUBUNIT'>".
				"<input type='hidden' id='fmSKPDSubUnit' name='fmSKPDSubUnit' value='$fmSEKSI'>".
				"<input type='hidden' id='fmkategori' name='fmkategori' value='$fmkategori'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang=cekPOST('fmSKPDBidang');
	 $fmSKPDskpd=cekPOST('fmSKPDskpd');
	 $fmThnAnggaran=  $_REQUEST['fmThnAnggaran'];
	 $jns=$_REQUEST['jns'];
	 
	 if($jns=='windowshow'){
		$bdg=mysql_fetch_array(mysql_query("SELECT * FROM ref_skpd where c='".$_REQUEST['fmSKPDBidang']."' and d='00'"));
		$bidang = $bdg['c'].'. '.$bdg['nm_skpd'];
		$unt=mysql_fetch_array(mysql_query("SELECT * FROM ref_skpd where c='".$_REQUEST['fmSKPDBidang']."' and d='".$_REQUEST['fmSKPDskpd']."' and e='00'"));
		$unit = $unt['d'].'. '.$unt['nm_skpd'];
		
			$skpd="<table>
			<tr>
				<td width='50'>BIDANG</td>
				<td width='10'>:</td>
				<td>
					<input type='text' name='nm_dkbSkpdfmSKPD' id='nm_dkbSkpdfmSKPD' value='$bidang' size='50' readonly>
					<input type='hidden' name='fmSKPDBidang' id='fmSKPDBidang' value=".$_REQUEST['fmSKPDBidang']." readonly>
				</td>
			</tr>
			<tr>
				<td width='50'>SKPD</td>
				<td width='10'>:</td>
				<td>
					<input type='text' name='nm_dkbSkpdfmUNIT' id='nm_dkbSkpdfmUNIT' value='$unit' size='50' readonly>
					<input type='hidden' name='fmSKPDskpd' id='fmSKPDskpd' value='".$_REQUEST['fmSKPDskpd']."' readonly>
				</td>
			</tr>
			<tr>
				<td width='50'>TAHUN</td>
				<td width='10'>:</td>
				<td>
					<input type='text' value='$fmThnAnggaran' id='fmFiltThnAnggaran' name='fmFiltThnAnggaran' size='5' readonly=''>
					<input type='hidden' value='$jns' id='jns' name='jns'>
				</td>
			</tr>
			</table>";
			$cariTahun='';

	}else{
	 $fmSKPDBidang=cekPOST('fmSKPDBidang');
	 $fmSKPDskpd=cekPOST('fmSKPDskpd'); 
	 
		$skpd = "<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
					$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange=Pemanfaat_Rencana.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
				"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
					$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=Pemanfaat_Rencana.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
				"</td></tr></table>";
				
		$cariTahun=genFilterBar(
						array(	
							"Tahun Anggaran &nbsp;"
							."<input type='text'  size='4' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' readonly=''>"
						),$this->Prefix.".refreshList(true)",TRUE
					)
					;
	}
	
	$TampilOpt = 
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			$skpd.
			"</td>
			</tr></table>".
			
			$cariTahun;
			
		return array('TampilOpt'=>$TampilOpt);
	}
		
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		global $fmPILCARI;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');
		$fmSKPDUnit = cekPOST('fmSKPDUnit');
		$fmSKPDSubUnit = cekPOST('fmSKPDSubUnit');
		$fmkategori = cekPOST('fmkategori');	
							
		$fmThnAnggaran=$_REQUEST['fmThnAnggaran'];
		
		
		if(!($fmSKPDBidang=='' || $fmSKPDBidang=='00') ) $arrKondisi[] = "c='$fmSKPDBidang'";
		if(!($fmSKPDskpd=='' || $fmSKPDskpd=='00') ) $arrKondisi[] = "d='$fmSKPDskpd'";
		if(!($fmSKPDUnit=='' || $fmSKPDUnit=='00') ) $arrKondisi[] = "e='$fmSKPDUnit'";
		if(!($fmSKPDSubUnit=='' || $fmSKPDSubUnit=='00') ) $arrKondisi[] = "e1='$fmSKPDSubUnit'";
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "thn_anggaran='$fmThnAnggaran'";
		switch($fmkategori){
			case '0': break;//semua
			case '1': $arrKondisi[] = " f<>'02' and f<>'05' and f<>'06' and f<>'07' " ;break;//sebagian
			default : break;//semua
		}
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
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		$fmkategori = $_REQUEST['fmkategori'];
		$tahun_anggaran = $_REQUEST['tahun_anggaran'];	
		$tipe='windowshow';	
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		//if($err==''){
			$FormContent = $this->genDaftarInitial($tipe,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI,$fmkategori);
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
	
}
$Pemanfaat_Rencana = new Pemanfaat_RencanaObj();

?>