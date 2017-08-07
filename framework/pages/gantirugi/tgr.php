<?php

class TGRObj  extends DaftarObj2{	
	var $Prefix = 'TGR';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'gantirugi'; //daftar
	var $TblName_Hapus = 'gantirugi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 11, 12, 12);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0, 0);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $ico_width = '28.8';
	var $ico_height = '28.8';	
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'TUNTUTAN GANTI RUGI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'TGRForm'; 	
			
	function setTitle(){
		return 'TUNTUTAN GANTI RUGI';
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
	 $idplh = explode(",",$_REQUEST[$this->Prefix.'_idplh']);
	 $id = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = $Main->DEF_WILAYAH;
	 
	 $id_bukuinduk = $_REQUEST['idbi'];
	 $idbi_awal = $_REQUEST['idbi_awal'];
	 $bi = mysql_fetch_array( mysql_query(
			"select * from buku_induk where id='$id_bukuinduk' "		
		));
	 
	 $c = $bi['c'];
	 $d = $bi['d'];
	 $e = $bi['e'];
	 $e1 = $bi['e1'];
	 $f = $bi['f'];
	 $g = $bi['g'];
	 $h = $bi['h'];
	 $i = $bi['i'];
	 $j = $bi['j'];
	 
	 $kode_akun = explode('.',$_REQUEST['kode_akun']);
	 $ka = $kode_akun[0];
	 $kb = $kode_akun[1];
	 $kc = $kode_akun[2];
	 $kd = $kode_akun[3];
	 $ke = $kode_akun[4];
	 $kf = $kode_akun[5];
	 
	 $nm_account = $_REQUEST['nama_akun'];
	 $thn_akun = $_REQUEST['thn_akun'];
		
	 $noreg = $_REQUEST['noreg'];		
	 $thn_perolehan = $_REQUEST['thn_perolehan'];		
	 $tgl_gantirugi = $_REQUEST['tgl_gantirugi'];		
	 $no_ba = $_REQUEST['nomor'];		
	 $tgl_ba = $_REQUEST['tgl_ba'];		
	 $uraian = $_REQUEST['uraian'];		
	 $ket = $_REQUEST['keterangan'];		
	 $kepada_nama = $_REQUEST['nama'];		
	 $kepada_alamat = $_REQUEST['alamat'];		
	 $tahun = $_REQUEST['tahun'];		
	 $kondisi_akhir = $bi['kondisi'];			
	 $staset = $bi['staset'];			
	 $harga_perolehan = $bi['jml_harga'];
	 $StSusut=0;				
	 $harga_buku = getNilaiBuku($id_bukuinduk,$tgl_gantirugi,$StSusut);		
	 $harga_susut = getAkumPenyusutan($id_bukuinduk,$tgl_gantirugi);	
	 
	 $aqryOld="select * from $this->TblName where id='$id' ";
	 $old = mysql_fetch_array( mysql_query(
			$aqryOld		
		));
		
	//validasi
		$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$id_bukuinduk' and tahun>='$thn_gantirugi'";
		$get_susut = mysql_fetch_array(mysql_query($query_susut));			
		$thn_gantirugi = substr($tgl_gantirugi,0,4);
		if ($fmST==0){
			//cek sudah ada Closing untuk data baru
			if(sudahClosing($tgl_gantirugi,$c,$d))$err = "Tanggal Sudah Closing!";
			//cek sudah ada penyusutan / tdk untuk data baru			
			//if($get_susut['jml_susut']>0)$err='Sudah ada penyusutan, data tidak bisa di masukan !';			
		}else{
			$oldthn_gantirugi = substr($old['tgl_gantirugi'],0,4);
			//cek sudah ada Closing untuk data edit
			if(sudahClosing($tgl_gantirugi,$c,$d) && $oldthn_gantirugi!=$thn_gantirugi)$err = "Tanggal Sudah Closing!";
			//cek sudah ada penyusutan / tdk untuk data edit	
			if($get_susut['jml_susut']>0 && $oldthn_gantirugi!=$thn_gantirugi)$err='Sudah ada penyusutan, data tidak bisa dirubah !';
		}	 
	if($err==''){
			if($fmST == 0){//baru
				
				$aqry = 
					"insert into $this->TblName ".
					"( a1,a,b,c,d,e,e1,".
					" f,g,h,i,j,".
					" ka,kb,kc,kd,ke,kf,".
					" noreg,thn_perolehan,tgl_gantirugi,".
					" uraian,ket,kepada_nama,kepada_alamat,".
					" tahun,kondisi_akhir,staset,idbi_awal,id_bukuinduk,".
					" harga_perolehan,harga_buku,".
					" no_ba,tgl_ba,".
					" harga_susut,nm_account,thn_akun,".
					" uid,tgl_update) ".
					"values ".
					"( '$a1','$a','$b','$c','$d','$e','$e1',".
					" '$f','$g','$h','$i','$j',".
					" '$ka','$kb','$kc','$kd','$ke','$kf',".
					" '$noreg','$thn_perolehan','$tgl_gantirugi',".
					" '$uraian','$ket','$kepada_nama','$kepada_alamat',".
					" '$tahun','$kondisi_akhir','$staset','$idbi_awal','$id_bukuinduk',".
					" '$harga_perolehan','$harga_buku',".
					" '$no_ba','$tgl_ba',".					
					" '$harga_susut','$nm_account','$thn_akun',".
					" '$uid',now())";
				
			}else{				
				if($err=='' && sudahClosing($old['tgl_gantirugi'],$c,$d))$err = "Tanggal Sudah Closing!";
					$aqry = 
					"update $this->TblName  ".
					" set ".
					" a1='$a1',a='$a',b='$b',c='$c',d='$d',e='$e',e1='$e1',".
					" f='$f',g='$g',h='$h',i='$i',j='$j',".
					" ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',kf='$kf',".
					" noreg='$noreg',thn_perolehan='$thn_perolehan',tgl_gantirugi='$tgl_gantirugi',".
					" uraian='$uraian',ket='$ket',kepada_nama='$kepada_nama',kepada_alamat='$kepada_alamat',".
					" tahun='$tahun',kondisi_akhir='$kondisi_akhir',staset='$staset',idbi_awal='$idbi_awal',id_bukuinduk='$id_bukuinduk',".
					" harga_perolehan='$harga_perolehan',harga_buku='$harga_buku',".
					" no_ba='$no_ba',tgl_ba='$tgl_ba',".
					" harga_susut='$harga_susut',nm_account='$nm_account',thn_akun='$thn_akun',".
					" uid='$uid', tgl_update= now() ".
					" where id='".$old['id']."' ";
			}
			if($err==''){
				$cek .= $aqry;
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal SQL'.mysql_error();
			}
			
		}
			
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }
	

	
	function simpanPilihBarang(){
		 global $HTTP_COOKIE_VARS;
		 global $Main;
		 $uid = $HTTP_COOKIE_VARS['coID'];
		 $cek = ''; $err=''; $content=''; $json=TRUE;
		
		$ids = $_REQUEST['cidBI'];
		$tgl_gantirugi = $_REQUEST['tgl_gantirugi'];
		$StSusut = 0;
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
								
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
			$nb = mysql_fetch_array(mysql_query("select * from ref_barang where f='".$bi['f']."' and g='".$bi['g']."' and h='".$bi['h']."' and i='".$bi['i']."' and j='".$bi['j']."'")) ;
			$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
			$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
			$tmax = mysql_fetch_array(mysql_query($kueri1));
			$na = mysql_fetch_array(mysql_query("select * from ref_jurnal where thn_akun = '".$tmax['thn_akun']."' 
					and ka='".$nb['ka']."' and kb='".$nb['kb']."' 
					and kc='".$nb['kc']."' and kd='".$nb['kd']."' 
					and ke='".$nb['ke']."' and kf='".$nb['kf']."'")) ;
			$na = array_map('utf8_encode', $na);
			$content->idbi=$bi['id'];
			$content->idbi_awal=$bi['idawal'];
			$content->kode_barang=$bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
			$content->nama_barang=$nb['nm_barang'];
			$content->kode_akun=$nb['ka'].'.'.$nb['kb'].'.'.$nb['kc'].'.'.$nb['kd'].'.'.$nb['ke'].'.'.$nb['kf'];
			$content->nama_akun=$na['nm_account'];
			$content->thn_akun=$na['thn_akun'];
			$content->noreg=$bi['noreg'];
			$content->thn_perolehan=$bi['thn_perolehan'];
			$content->harga_perolehan=number_format($bi['jml_harga'],2,',','.' );
			$harga_buku=getNilaiBuku($bi['id'],$tgl_gantirugi,$StSusut);	
			$content->harga_buku=number_format($harga_buku,2,',','.' );
			
			$content->merk_barang=$this->getSpesifikasi($bi['id']);		
			
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
		
		case 'formCari':{				
			$fm = $this->setFormCari();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
			break;
		}		
	   
		case 'BidangAfter':{
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter()','--- Pilih SKPD ---','00');
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
				
	    case 'UnitPengguna':{
			$bidang = $_REQUEST['bidang'];
			$skpd = $_REQUEST['skpd'];
			$unit = $_REQUEST['unit'];
			$subunit = $_REQUEST['subunit'];						
			$querySUBUNIT = "SELECT e1, nm_skpd FROM ref_skpd WHERE c='$bidang' and d='$skpd' and e='$unit' and e1!='000'"; $cek .= $query2;
			$hasilSUBUNIT = mysql_query($querySUBUNIT);
			$opsi_subunit = "<option value=''>-- Pilih Sub Unit --</option>";
			while ($rowSUBUNIT = mysql_fetch_array($hasilSUBUNIT))
			{
				$selectedSUBUNIT=$rowSUBUNIT['e1']==$subunit? 'selected':'';
				$opsi_subunit.="<option value='".$rowSUBUNIT['e1']."'>".$rowSUBUNIT['nm_skpd']."</option>";
			}
			$content = "<select name='e1' id='e1'>".$opsi_subunit."</select>";
			break;
		}	

	    case 'Skpd':{
			$bidang = $_REQUEST['bidang'];
			$skpd = $_REQUEST['skpd'];
			$querySKPD = "SELECT d, nm_skpd FROM ref_skpd WHERE c='$bidang' and d!='00' and e='00' and e1='000'"; $cek .= $querySKPD;
			$hasilSKPD = mysql_query($querySKPD);
			$opsi_skpd = "<option value=''>-- Pilih SKPD --</option>";				
			while ($rowSKPD = mysql_fetch_array($hasilSKPD))
			{
				$selectedSKPD=$rowSKPD['d']==$skpd? 'selected':'';
				$opsi_skpd.="<option value='".$rowSKPD['d']."' $selectedSKPD>".$rowSKPD['nm_skpd']."</option>";
			}
			$content="<select name='skpd' id='skpd'>".$opsi_skpd."</select>";
			break;
		}		

   		case 'simpanPilihBarang':{				
			$get= $this->simpanPilihBarang();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
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
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'Hapus':{
			//$json=TRUE;
	   		//$cbid= $_POST[$this->Prefix.'_cb'];			
			$get= $this->Hapus();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
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
				$ref_pilihtgr = $_REQUEST['id'];
				
				//query ambil data ref_jurnal
				$get = mysql_fetch_array( mysql_query("select * from gantirugi where id='$ref_pilihtgr'"));
				$cek .= "select * from gantirugi where id='$ref_pilihtgr'";
				$get = array_map('utf8_encode', $get);
				//query ambil data barang
				$brg = mysql_fetch_array(mysql_query("select * from ref_barang where 
										f='".$get['f']."' and g='".$get['g']."' and 
										h='".$get['h']."' and i='".$get['i']."' and 
										j='".$get['j']."'"));
				$kd_barang = $brg['f'].".".$brg['g'].".".$brg['h'].".".$brg['i'].".".$brg['j'];
				$nm_barang=$brg['nm_barang'];
				
				//query ambil data akun
				$kd_account = $get['ka'].".".$get['kb'].".".$get['kc'].".".$get['kd'].".".$get['ke'].".".$get['kf'];
				$nm_account = $get['nm_account'];
				$thn_akun = $get['thn_akun'];
				/*
				if ($brg['f']=="07"){
					$err = "KIB G Tidak Bisa Dipilih!";
				}*/	
				$content = array('idtgr'=>$ref_pilihtgr,
								 'kd_barang'=>$kd_barang,
								 'nm_barang'=>$nm_barang,
								 'kd_account'=>$kd_account,
								 'nm_account'=>$nm_account,
								 'thn_akun'=>$thn_akun,
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
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script src='js/jquery-ui.custom.js'></script>".		    
			"<script type='text/javascript' src='js/gantirugi/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".			
			$scriptload;
	}
	
	//Windows show ==================================
	function windowShow(){	
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
		$tahun_anggaran = $_REQUEST['tahun_anggaran'];	
		$tipe='windowshow';	
		
			$FormContent = $this->genDaftarInitial($tipe,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						400,
						'Pilih Barang Tuntutan Ganti Rugi',
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
	
	//Hapus ==================================
	function Hapus(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb']; $cek .= $cbid;
 	 
	 for($i = 0; $i<count($cbid); $i++)	{
			//Validasi Hapus
			$kueri="select * from $this->TblName_Hapus 
					where id = '".$cbid[$i]."' "; //echo "$kueri";
			$data=mysql_fetch_array(mysql_query($kueri));
			if($errmsg=='' && sudahClosing($data['tgl_gantirugi'],$data['c'],$data['d']))$err = "Id ".$cbid[$i].", Tanggal Sudah Closing!";
			//cek sudah ada penyusutan / tdk untuk data baru			
			$oldthn_gantirugi = substr($data['tgl_gantirugi'],0,4);
			$query_susut = "select count(*)as jml_susut from penyusutan where idbi='".$data['id_bukuinduk']."' and tahun>='$oldthn_gantirugi'";
			$get_susut = mysql_fetch_array(mysql_query($query_susut));
			if($get_susut['jml_susut']>0){
				$errmsg="Id ".$cbid[$i].", Sudah ada penyusutan !";
			}
			
			if($err ==''){
				$aqry = "DELETE FROM $this->TblName_Hapus WHERE id='".$cbid[$i]."'";	$cek .= $aqry;	
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal Hapus '.mysql_error();
				if ($err != '') break;
			}else{
				break;
			}			
		}
		
					
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
		
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$dt['tgl_gantirugi'] = date("Y-m-d");
		$dt['tgl_ba'] = date("Y-m-d");
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
		   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$aqry = "select * from $this->TblName where id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
	 	
		//query barang
		$brg=mysql_fetch_array(mysql_query("select * from ref_barang where f='".$dt['f']."' and g='".$dt['g']."' and h='".$dt['h']."' and i='".$dt['i']."' and j='".$dt['j']."'"));
		$dt['kode_barang']=$dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
		$dt['nama_barang']=$brg['nm_barang'];		
		
		$dt['kode_akun']=$dt['ka'].'.'.$dt['kb'].'.'.$dt['kc'].'.'.$dt['kd'].'.'.$dt['ke'].'.'.$dt['kf'];
		$dt['nama_akun']=$dt['nm_account'];
		$dt['merk_barang']=$this->getSpesifikasi($dt['id_bukuinduk']);
	 	
		$dt['disabled']='disabled';
		
		
			$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = '';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		$id_mutasi=$_REQUEST['id_mutasi'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Tambah Barang'; //judul form
		$this->form_fields = array(	
					
			'div_detailtambahbarang' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailtambahbarang' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='id_mutasi' name='id_mutasi' value='$id_mutasi'> ".
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".PilihBarang()' >&nbsp".
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
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 1000;
		$this->form_height = 500;
		if ($this->form_fmST==0) {
			$this->form_caption = 'TGR BARANG';
			
			$simpan="<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >&nbsp";	
		}elseif ($this->form_fmST==1){
			$this->form_caption = 'Form Edit';			
			
			$simpan="<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >&nbsp";										
		}else{
			$this->form_caption = 'Form Batal';			
			$tambahbarang="";
			$hapusbarang="";
			$simpan="<input type='button' value='Batal Mutasi' onclick ='".$this->Prefix.".SimpanBatal()' >&nbsp";	
		}
		
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$skpd = $get['nm_skpd'];
		
		$queryUnit="select e,nm_skpd from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e!='00' and e1='000'";
		$querySubUnit="select e1,nm_skpd from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1!='000'";
		$queryBidangBaru="select c,nm_skpd from ref_skpd where c!='00' and d='00' and e='00' and e1='000'";
		$querySkpdBaru="select d,nm_skpd from ref_skpd where c='".$dt['cbaru']."' and d!='00' and e='00' and e1='000'";
			
			
		$this->form_fields = array(								 								 
			'bidang' => array('label'=>'BIDANG', 
							  'value'=> $bidang, 
							  ),
								
			'skpd' => array( 'label'=>'SKPD',
							 'value'=> $skpd,
							 ),

			'tahun' => array('label'=>'Tahun', 
								 'value'=> $dt['tahun'],  
								 'type'=> 'text' , 
								 'param'=>'style=width:40px readonly',
							),

			'unit_pengguna' => array('label'=>'', 
								 'value'=>'Unit Pengguna',  
								 'type'=>'merge' , 
								 'row_params'=>"style='font-size: 200%;font-weight: bold;color: #C64934;margin:0 0 10 0';"
								 ),
								 					
			'unit' => array(  'label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUNIT', 
								 'value'=> cmbQuery('e',$dt['e'],$queryUnit,'id=e onChange=\''.$this->Prefix.'.UnitPengguna()\' ','-- Pilih Unit --'),   
								 'type'=>'' , 
								 //'row_params'=>"style='height:24'"
								 ),
								 
			'subunit' => array(  'label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSUB UNIT', 
								 'value'=> '<div id=div_e1>'.cmbQuery('e1',$dt['e1'],$querySubUnit,'id=e1 ','-- Pilih Sub Unit --').'</div>',   
								 //'type'=>'' , 
								 //'row_params'=>"style='height:24'"
								 ),										 						
				   
	  	 	'tgl_buku' => array( 
					 'label'=>'Tanggal Buku',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_gantirugi'], 'tgl_gantirugi', FALSE,'tanggal bulan tahun (mis: 1 Januari 1998)','','','disabled')
			 			),				   

			'nama_barang' => array( 
								'label'=>'Kode Barang',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='kode_barang' value='".$dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j']."' size='10px' id='kode_barang' readonly>
								<input type='text' name='nama_barang' value='".$dt['nama_barang']."' size='55px' id='nama_barang' readonly>&nbsp
								<input type='button' value='Cari'  onclick=\"javascript:".$this->Prefix.".TambahBarang()\" ".$dt['disabled']." >"
									),	
									
			'nama_akun' => array( 
								'label'=>'Kode Akun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='kode_akun' value='".$dt['kode_akun']."' size='10px' id='kode_akun' readonly>
								<input type='text' name='nama_akun' value='".$dt['nama_akun']."' size='55px' id='nama_akun' readonly>&nbsp
								<input type='hidden' name='thn_akun' value='".$dt['thn_akun']."' size='10px' id='thn_akun'>"
								
									),

			'noreg/tahun' => array( 
								'label'=>'No Reg / Tahun',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='noreg' value='".$dt['noreg']."' size='10px' id='noreg' readonly>
										<input type='text' name='thn_perolehan' value='".$dt['thn_perolehan']."' size='10px' id='thn_perolehan' readonly>&nbsp"
									),
									
			'Spesifikasi' => array(  'label'=>'Spesifikasi/Alamat',
									 'value'=>"<textarea id='merk_barang' name='merk_barang' rows='2' cols='60' readonly>".$dt['merk_barang']."</textarea>", 
									 //'param'=> "valign=top",
									 'row_params'=> "valign=top",		 
									   ),
				   
			'harga' => array( 
								'label'=>'Harga Perolehan / Harga Buku',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='harga_perolehan' value='".number_format($dt['harga_perolehan'],2,',','.' )."' size='10px' id='harga_perolehan' readonly>
										<input type='text' name='harga_buku' value='".number_format($dt['harga_buku'],2,',','.' )."' size='10px' id='harga_buku' readonly>&nbsp"
									),
				   
			'ba_tgr' => array('label'=>'', 
								 'value'=>'Berita Acara TGR',  
								 'type'=>'merge' , 
								 'row_params'=>"style='font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0';"
								 ),			

			'nomor' => array( 
								'label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNomor',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='nomor' id='nomor' value='".$dt['no_ba']."' style='width:200px' >&nbsp;",							
								'type'=>'',
								'row_params'=>"valign='top'",
								'param'=> ""
									 ),									 
	  	 	'tanggal' => array( 
					 'label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTanggal',
					 'labelWidth'=>150, 
					 'value'=>createEntryTgl3($dt['tgl_ba'], 'tgl_ba', FALSE,'tanggal bulan tahun (mis: 1 Januari 1998)','','','disabled')
			 			),
									   							   
			'penanggung_jawab' => array('label'=>'', 
								 'value'=>'Penanggung Jawab',  
								 'type'=>'merge' , 
								 'row_params'=>"style='font-size: 18px;font-weight: bold;color: #C64934;margin:0 0 10 0';"
								 ),						   							   

			'nama' => array('label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNama', 
								 'value'=> $dt['kepada_nama'],  
								 'type'=>'text' , 
								 'param'=>'style=width:200px',
							),			
											   						   
			'alamat' => array('label'=>'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspAlamat', 
								 'value'=> $dt['kepada_alamat'],  
								 'type'=>'text' , 
								 'param'=>'style=width:250px',
							),	
							
			'uraian' => array(  'label'=>'Uraian Kejadian',
				 'value'=>"<textarea id='uraian' name='uraian' rows='2' cols='60' >".$dt['uraian']."</textarea>", 
				 'param'=> "valign=top",
				 'row_params'=> "valign=top",		 
				   ),
			
			'keterangan' => array('label'=>'Keterangan', 
								 'value'=> $dt['ket'],  
								 'type'=>'text' , 
								 'param'=>'style=width:300px',
							),									
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='idbi' name='idbi' value='".$dt['id_bukuinduk']."'> ".	
			"<input type=hidden id='idbi_awal' name='idbi_awal' value='".$dt['idbi_awal']."'> ".			
			$simpan."&nbsp".			
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm2();		
				
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
		
	//daftar =================================
	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01' width='20' rowspan='2'>No.</th>
				  	   $Checkbox		
					   <th class='th01' width='250' rowspan='2'>SKPD/ <br>UNIT Pengguna</th>	   	   	   
					   <th class='th01' width='250' rowspan='2'>Kode Barang /<br> Kode ASet</th>	 
  					   <th class='th01' width='250' rowspan='2'>Nama Barang /<br> Nama Akun</th>	 
   					   <th class='th01' width='250' rowspan='2'>No Reg <br>Tahun</th>	   	   	   
					   <th class='th01' width='250' rowspan='2'>Spesifikasi / Alamat</th>	 
					   <th class='th01' width='250' rowspan='2'>Harga Perolehan /<br>Harga Buku</th>	   	   	    
				   	   <th class='th02' width='300' colspan='2'>Berita Acara</th>
					   <th class='th01' width='250' rowspan='2'>Uraian Kejadian</th>						   
					   <th class='th01' width='200' rowspan='2'>Penanggung Jawab</th>
					   <th class='th01' width='200' rowspan='2'>Tgl Buku /<br> Keterangan</th>			   
					</tr>
					<tr>
						<th class='th01'>Nomor</th>
						<th class='th01'>Tanggal</th>
					</tr>   
					</thead>";
	
		return $headerTable;
	}	
	
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=tgr" title="Tuntutan Ganti">TGR</a> |	
				<a href="pages.php?Pg=tgr_ketetapan" title="Berita Acara Serah Terima">Ketetapan</a> 
				<!--|<a href="pages.php?Pg=pembayaran" title="Berita Acara Serah Terima">Pembayaran</a>  |	-->
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';	 
	}

	function genDaftarInitial($tipe='',$fmSKPD='',$fmUNIT='',$fmSUBUNIT='',$fmSEKSI=''){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		if($tipe=='windowshow'){
			$jns = "<input type='hidden' value='windowshow' id='jns' name='jns' >";
			$title = "";
			$FilterDaftarTGR="<input type='hidden' id='fmSKPDBidang' name='fmSKPDBidang' value='$fmSKPD'>".
							"<input type='hidden' id='fmSKPDskpd' name='fmSKPDskpd' value='$fmUNIT'>".		
							"<input type='hidden' id='fmSKPDUnit' name='fmSKPDUnit' value='$fmSUBUNIT'>".
							"<input type='hidden' id='fmSKPDSubUnit' name='fmSKPDSubUnit' value='$fmSEKSI'>";
		}else{
			$jns = "<input type='hidden' value='' id='jns' name='jns' >";
			$title = "<div id='{$this->Prefix}_cont_title' style='position:relative'></div>";
			$FilterDaftarTGR= "<input type='hidden' value='$fmSKPDBidang' id='fmSKPDBidang' name='fmSKPDBidang' >".
								"<input type='hidden' value='$fmSKPDskpd' id='fmSKPDskpd' name='fmSKPDskpd' >";
		}
		
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
			$title. 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>".
			"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
			$jns. 
			$FilterDaftarTGR.
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}

	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 
	 $isi = array_map('utf8_encode', $isi);
	 $bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'")) ;
	 $brg = mysql_fetch_array(mysql_query(
				"select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));	
	 $akn = mysql_fetch_array(mysql_query(
				"select * from ref_jurnal where ka='".$isi['ka']."' and kb='".$isi['kb']."' and kc='".$isi['kc']."' and kd='".$isi['kd']."' and ke='".$isi['ke']."' and kf='".$isi['kf']."'"));	
	 $akn = array_map('utf8_encode', $akn);
	
	 $spesifikasi=$this->getSpesifikasi($isi['id_bukuinduk']);
	 			
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
		
		$nmopd = //$fmSKPD.'-'.$fmUNIT.'-'.$fmSUBUNIT.' '.
			join(' - ', $nmopdarr );
			
		$Koloms = array();		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array("align='left'",  $nmopd);
		$Koloms[] = array("align='left'", 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."/<br/>".
			$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$isi['kd'].'.'.$isi['ke'].'.'.$isi['kf']
		);
		$Koloms[] = array("align='left'",  $brg['nm_barang']."/<br/>".$akn['nm_account'] );
		$Koloms[] = array("align='center'",  $isi['noreg']."/<br/>".$isi['tahun'] );
		$Koloms[] = array("align='left'", $spesifikasi	);				
		$Koloms[] = array("align='right'", number_format($isi['harga_perolehan'] ,2,',','.' )."/<br/>"
			.number_format($isi['harga_buku'] ,2,',','.' ));
		$Koloms[] = array("align='center'",  $isi['no_ba'] );
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_ba'] ) );		
		$Koloms[] = array("align='left'",$isi['uraian']);
		$Koloms[] = array("align=''left", $isi['kepada_nama']."/<br/>"
			.$isi['kepada_alamat']	);
		$Koloms[] = array("align='center'",  TglInd($isi['tgl_gantirugi'] )."/<br/>".$isi['ket'] );		 		  	 	  	   	 	 	 	 	 	 	 
		 return $Koloms; 	
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

	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		
		 $fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		 $fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranDari');
		 $fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai')=='' ? $fmThnAnggaran : cekPOST('fmThnAnggaranSampai');
		
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmPILCARI = cekPOST('fmPILCARI'); //get name select box 
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE'); //get value textfield
		 
		//data cari ----------------------------
		$arrCari = array(
			array('selectKodeBarang','Kode Barang'),
			array('selectNamaBarang','Nama Barang'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
		
		$arrSemester = array(
			array('1','1'),
			array('2','2'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		
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
			$TampilDaftarFilter="<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			$skpd.
			"</td>
			</tr></table>";
			$TampilFmPILSEMESTER="";

	}else{
		$TampilDaftarFilter="<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			"<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
				$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange='.$this->Prefix.'.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
			"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
				$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
			"</td></tr>".
			"<tr><td width='100'>Tahun Anggaran</td><td width='10'>:</td><td>
				<input type='text'  size='4' value='$fmThnAnggaranDari' id='fmThnAnggaranDari' name='fmThnAnggaranDari' > s/d <input type='text'  size='4' value='$fmThnAnggaranSampai' id='fmThnAnggaranSampai' name='fmThnAnggaranSampai' >
			</td></tr>			
			</table>".
			"</td>
			</tr></table>";
		$TampilFmPILSEMESTER=genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
				genFilterBar(
				array(	"<table><tr><td width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILSEMESTER',$fmPILSEMESTER,$arrSemester,'Semua','').
					"</td></tr>			
					</table>"				
				),$this->Prefix.".refreshList(true)",TRUE
			);
	}
		
		$TampilOpt =$TampilDaftarFilter.$TampilFmPILSEMESTER;
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID'];
		 
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');
		$fmSKPDUnit = cekPOST('fmSKPDUnit');
		$fmSKPDSubUnit = cekPOST('fmSKPDSubUnit');
		
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThnAnggaranDari=  cekPOST('fmThnAnggaranDari');
		$fmThnAnggaranSampai=  cekPOST('fmThnAnggaranSampai');
		$fmPILSEMESTER = $_REQUEST['fmPILSEMESTER'];
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIVALUE = $_REQUEST['fmPILCARIVALUE'];					
		
		//kondisi -----------------------------------				
		$arrKondisi = array();		
		
			$arrKondisi[] = 
			getKondisiSKPD3(
				$Main->DEF_KEPEMILIKAN, 
				$Main->Provinsi[0], 
				$Main->DEF_WILAYAH, 
				$fmSKPDBidang, 
				$fmSKPDskpd,
				$fmSKPDUnit,
				$fmSKPDSubUnit
			);
			if(!($fmThnAnggaran=='') ) $arrKondisi[] = "YEAR(tgl_gantirugi)='$fmThnAnggaran'";
			if ($fmThnAnggaranDari == $fmThnAnggaranSampai){
			
				if(!($fmThnAnggaranDari=='')  && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_gantirugi)>='$fmThnAnggaranDari' and YEAR(tgl_gantirugi)<='$fmThnAnggaranSampai' ";
				switch($fmPILCARI){			
				case '1': $arrKondisi[] = " tgl_gantirugi>='".$fmThnAnggaranDari."-01-01' and  cast(tgl_gantirugi as DATE)<='".$fmThnAnggaranSampai."-06-30' "; break;
				case '2': $arrKondisi[] = " tgl_gantirugi>='".$fmThnAnggaranDari."-07-01' and  cast(tgl_gantirugi as DATE)<='".$fmThnAnggaranSampai."-12-31' "; break;
				default :""; break;
				}
			}else{
				if(!($fmThnAnggaranDari=='') && !($fmThnAnggaranSampai=='')) $arrKondisi[] = "YEAR(tgl_gantirugi)>='$fmThnAnggaranDari' and YEAR(tgl_gantirugi)<='$fmThnAnggaranSampai' ";
			}
			switch($fmPILCARI){
				case 'selectKodeBarang': $arrKondisi[] = "concat(f,g,h,i,j) like '".str_replace(".","",$fmPILCARIVALUE)."%'"; break;		 	
				case 'selectNamaBarang': $arrKondisi[] = "concat(f,g,h,i,j) in (select concat(f,g,h,i,j) from ref_barang where nm_barang like '%$fmPILCARIVALUE%') "; break;
			}
			
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			//case '': $arrOrders[] = " tgl DESC " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
			case '2': $arrOrders[] = " tahun_anggaran $Asc1 " ;break;			
		
		}

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
	
}
$TGR = new TGRObj();

?>