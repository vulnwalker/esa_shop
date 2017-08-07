<?php

class gantirugiObj  extends DaftarObj2{	
	var $Prefix = 'gantirugi';
	//var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	//var $TblName = 'v_gantirugi'; //daftar
	var $TblName = 'gantirugi'; //daftar
	var $TblName_Hapus = 'gantirugi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('jml_harga','harga');//array('jml_harga');
	var $SumValue = array();
	var $totalCol = 21; //total kolom daftar
	var $FieldSum_Cp1 = array();//berdasar mode
	var $FieldSum_Cp2 = array();
	var $fieldSum_lokasi = array(11,18);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Tuntutan Ganti Rugi';
	var $PageIcon = 'images/gantirugi_ico.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Tuntutan ganti rugi.xls';
	var $Cetak_Judul = 'Tuntutan Ganti Rugi';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'gantirugiForm';
	var $noModul=12;  	
	 
	function setTitle(){
		return 'Tuntutan Ganti Rugi';
	}
	
	function setMenuEdit(){
		return
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'UsulanBaru')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'EditUsulan')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Batal Usulan", 'HapusUsulan','','','','','style=width:80px')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Ketetapan()","new_f2.png","Ketetapan", 'Ketetapan')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".KetetapanTanpaUsulan()","new_f2.png","Tanpa Usulan", 'Ketetapan Tanpa Usulan','','','','','style=width:80px')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".BatalKetetapan()","delete_f2.png","Batal Ketetapan", 'Batal Ketetapan','','','','','style=width:90px')."</td>".
		"</td>";
	}
	
	function simpan(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$tabel='gantirugi';
		
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$kode_barang=$_REQUEST['fmIDBARANG'];
		$expkdbarang=explode('.',$kode_barang);
		$id_bukuinduk=$_REQUEST['idbi'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1= $_REQUEST['e1'];
		//$e1= $_REQUEST['e1']==''?  '001': $_REQUEST['e1'];
		
		$no_usul	= $_REQUEST['no_usul'];
		$tgl_usul	= $_REQUEST['tgl_usul'];
		$f=$expkdbarang['0']; $g=$expkdbarang['1']; $h=$expkdbarang['2']; $i=$expkdbarang['3']; $j=$expkdbarang['4'];
		$tahun=$_REQUEST['tahun'];
		$harga_perolehan=str_replace('.','',$_REQUEST['harga_perolehan']);
		$harga=$_REQUEST['harga'];
		$noreg=$_REQUEST['noreg'];
		$kepada_nama=$_REQUEST['kepada_nama'];
		$kepada_alamat=$_REQUEST['kepada_alamat'];
		$uraian=$_REQUEST['uraian'];
		$ket=$_REQUEST['ket'];
		$tgl_gantirugi=$_REQUEST['tgl_gantirugi'];
		$no_sk=$_REQUEST['no_sk'];
		$tgl_sk=$_REQUEST['tgl_sk'];
		$tgl_update=date('Y/m/d h:i:s');
		//$staset='9';	
		//$stusul='4';
		if ($kepada_nama==''){
			$err="Yang Bertanggung Jawab Tidak Boleh Kosong";
		}
		if ($f=='' && $g=='' && $h=='' && $i=='' && $j==''){
			$err="Kode Barang Tidak Boleh Kosong";
		}
		if ($tgl_usul==''){
			$err="Tanggal Usul Tidak Boleh Kosong ";
		}
		if ($no_usul==''){
			$err="No Usul Tidak Boleh Kosong ";
		}
		if(mysql_fetch_array(mysql_query("select id_bukuinduk from gantirugi where id_bukuinduk='$id_bukuinduk'"))){
		//$err="Data Sudah Diusulkan 1 ";			
		}
		$bi = mysql_fetch_array(mysql_query("select idawal from buku_induk where id='$id_bukuinduk'"));
		$idbi_awal = $bi['idawal'];
		//------------------------------------------------------
		$nilai_buku = getNilaiBuku($id_bukuinduk,$tgl_usul,0);
		$nilai_susut = getAkumPenyusutan($id_bukuinduk,$tgl_usul);
		if($Main->VERSI_NAME != 'JABAR'){
			$field='nilai_susut,nilai_buku';
			$vfield="'$nilai_susut','$nilai_buku'";
		}else{
			$field='harga_perolehan';
			$vfield="'$harga_perolehan'";
		}
		if ($err==''){
			if($fmST==0){
				$aqry="insert into $tabel (id_bukuinduk,idbi_awal,a1,a,b,c,d,e,e1,f,g,h,i,j,no_usul,tgl_usul,tahun,thn_perolehan,noreg,kepada_nama,
					kepada_alamat,uraian,ket,tgl_gantirugi,stat,uid,tgl_update,jml_harga,$field) values
					('$id_bukuinduk','$idbi_awal','".$Main->DEF_KEPEMILIKAN."','".$Main->DEF_PROPINSI."','".$Main->DEF_WILAYAH."',
					'$c','$d','$e','$e1','$f','$g','$h','$i','$j','$no_usul','$tgl_usul','$tahun','$tahun','$noreg','$kepada_nama',
					'$kepada_alamat','$uraian','$ket','$tgl_gantirugi','$stat','$uid','$tgl_update','$harga_perolehan',$vfield)";$cek.=$aqry;
				$qry=mysql_query($aqry);				
				if($qry) {
					$aqry1="Update buku_induk set stusul='4' where id='$id_bukuinduk' "; $cek.=$aqry1;
					$qry1=mysql_query($aqry1);	
					if($qry1==FALSE) $err="Gagal Simpan data ".mysql_error();
				}else{
					$err="Gagal Simpan data ".mysql_error();
				}
				
				
				
			}elseif($fmST==1){
				$aqry="UPDATE $tabel set
				f='$f',g='$g',h='$h',i='$i',j='$j',id_bukuinduk='$id_bukuinduk',
				no_usul='$no_usul',
				tgl_usul='$tgl_usul',
				tahun='$tahun',
				noreg='$noreg',
				kepada_nama='$kepada_nama',
				kepada_alamat='$kepada_alamat',
				uraian='$uraian',
				ket='$ket',
				uid='$uid',
				tgl_update='$tgl_update',
				tgl_gantirugi='$tgl_gantirugi',
				jml_harga='$harga_perolehan'
				where id='$idplh'";$cek.=$aqry;
				$qry=mysql_query($aqry);
				if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();				
			}
		}
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);		
	}
	
	
	function simpanPilih(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//$coDaftar = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];$cek .=$coDaftar;
		
		//$ids= explode(',',$coDaftar); //$_POST['cidBI'];	//id bi barang
		$ids = $_REQUEST['cidBI'];
		$tgl = $_REQUEST['tgl'];
		
		if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
		
		if($err==''){
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
			$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j)='". $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j']."'")) ;
			$nilai_buku = getNilaiBuku($bi['id'],$tgl,0);
		
			$content->fmIDBARANG = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] ;// '1';
			$content->fmNMBARANG = $brg['nm_barang'];
			$content->noreg = $bi['noreg'];
			$content->tahun = $bi['thn_perolehan'];
			//$content->harga_perolehan = $bi['harga'];
			$content->harga_perolehan =number_format($nilai_buku, 2, ',' , '.');
			//$content->tgl_gantirugi = $bi['tgl_buku'];
			$content->idbi = $ids[0];
			$content->c = $bi['c'];
			$content->d = $bi['d'];
			$content->e = $bi['e'];
			$content->e1 = $bi['e1'];			
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other2($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function simpanSK(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		global $HTTP_COOKIE_VARS;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$tabel='gantirugi';
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$id_bukuinduk=$_REQUEST['idbi'];
		$harga=$_REQUEST['harga'];
		$no_sk=$_REQUEST['no_sk'];
		$tgl_sk=$_REQUEST['tgl_sk'];
		
		$tgl_update=date('Y/m/d h:i:s');
		
		
		//=======================================================================================
		$dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='$idplh'"));
		//$tgl_gantirugi=explode('-',$dtTGR['tgl_gantirugi']);
		$tgl_gantirugi = $_REQUEST['tgl_gantirugi'];
		$tgl_gantirugi_arr=explode('-', $tgl_gantirugi);
		$tgl_gantirugi_thn=$tgl_gantirugi_arr['0'];
		$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$id_bukuinduk' and tahun>='$tgl_gantirugi_thn'";
		$get_susut = mysql_fetch_array(mysql_query($query_susut));
		$dtbi = mysql_fetch_array(mysql_query("select c,d,e,e1,staset from buku_induk where id='$id_bukuinduk'"));
		$nilai_buku = getNilaiBuku($id_bukuinduk,$tgl_sk,0);
		$nilai_susut = getAkumPenyusutan($id_bukuinduk,$tgl_sk);
		//if ($tgl_gantirugi_thn<=$Main->TAHUN_CLOSING){
			if($Main->VERSI_NAME != 'JABAR'){
				if( sudahClosing($tgl_gantirugi,$dtbi['c'],$dtbi['d'],$dtbi['e'],$dtbi['e1']) ){
					$err="Gagal Simpan SK! Tahun Ganti Rugi dibawah Tahun Closing! ";
				}
			}else{
				if( sudahClosing($tgl_gantirugi,$dtbi['c'],$dtbi['d']) ){
					$err="Gagal Simpan SK! Tahun Ganti Rugi dibawah Tahun Closing! ";
				}
			}
			
			
			/*if($get_susut['jml_susut']>0){
				$err='Sudah ada penyusutan, data tidak bisa di simpan !';
				}*/
			//========================================================================================
			if ($harga==''){
				$err="Harga Ketetapan Tidak Boleh Kosong";
			}
			if ($tgl_sk==''){
				$err="Tanggal Ketetapan Tidak Boleh Kosong";
			}
			if ($no_sk==''){
				$err="Nomor Ketetapan Tidak Boleh Kosong ";
			}
			if ($harga=='' || $harga=='0'){
				$err="Harga Ketetapan Tidak Boleh Nol ";
			}
			
			$qry_transaksi="select tgl_buku from t_transaksi where idawal='$id_bukuinduk' and jns_trans2='9' and tgl_buku>'$tgl_sk' order by tgl_buku desc limit 1";
			$cnt = mysql_num_rows(mysql_query($qry_transaksi));
			if($cnt>0)$err = 'Gagal Ketetapan, karena ada transaksi pemeliharaan setelahnya !';
			$qry_jurnal="select tgl_buku from t_jurnal_aset where idbi='$id_bukuinduk' and tgl_buku>'$tgl_sk' order by tgl_buku desc limit 1";
			$cnt2 = mysql_num_rows(mysql_query($qry_jurnal));
			if($cnt2>0)$err = 'Gagal Ketetapan, karena ada transaksi lain setelahnya !';
			if($err==''){
				$stat='1';
				if($Main->VERSI_NAME != 'JABAR'){
					$jml_harga="nilai_susut='$nilai_susut',nilai_buku='$nilai_buku',";
				}else{
					$jml_harga="";
				}
				$aqry="UPDATE $tabel set ".
					"no_sk= '$no_sk',".
					"tgl_sk='$tgl_sk',".
					"tgl_gantirugi = '$tgl_gantirugi',".
					"harga='$harga',".
					"uid='$uid',".
					"tgl_update='$tgl_update',".
					"stat='$stat',".
					$jml_harga.
					"staset='".$dtbi['staset']."'".
					"where id='$idplh'";$cek.=$aqry;
								
				$qry=mysql_query($aqry);
				$cek.=$aqry;
				if($qry==FALSE) $err="Gagal Batal Ketetapan ".mysql_error();
				$staset='6';
				$status_barang='5';
				$stusul='0';
				$aqry1="Update buku_induk set
				status_barang='$status_barang',
				staset='$staset',
				stusul='$stusul'
				where id='$id_bukuinduk';					
				";$cek.=$aqry1;
				$qry1=mysql_query($aqry1);
				if($qry1==FALSE) $err="Gagal Simpan data ".mysql_error();
				
				}	
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		}
		
		function simpanSKTU(){
			global $HTTP_COOKIE_VARS;
			global $Main;
			$uid = $HTTP_COOKIE_VARS['coID'];
			$cek = ''; $err=''; $content=''; $json=TRUE;
			$tabel='gantirugi';
			//get data -----------------
			$fmST = $_REQUEST[$this->Prefix.'_fmST'];
			$idplh = $_REQUEST[$this->Prefix.'_idplh'];
			$kode_barang=$_REQUEST['fmIDBARANG'];
			$expkdbarang=explode('.',$kode_barang);
			$id_bukuinduk=$_REQUEST['idbi'];
			$c=$_REQUEST['c'];
			$d=$_REQUEST['d'];
			$e=$_REQUEST['e'];
					
			
			$no_usul=$_REQUEST['no_usul'];
			$tgl_usul=$_REQUEST['tgl_usul'];
			$f=$expkdbarang['0']; $g=$expkdbarang['1']; $h=$expkdbarang['2']; $i=$expkdbarang['3']; $j=$expkdbarang['4'];
			$tahun=$_REQUEST['tahun'];
			$harga=$_REQUEST['harga'];
			$harga_perolehan=str_replace('.','',$_REQUEST['harga_perolehan']);
			$noreg=$_REQUEST['noreg'];
			$kepada_nama=$_REQUEST['kepada_nama'];
			$kepada_alamat=$_REQUEST['kepada_alamat'];
			$uraian=$_REQUEST['uraian'];
			$ket=$_REQUEST['ket'];
			$tgl_gantirugi=$_REQUEST['tgl_gantirugi'];
			$no_sk=$_REQUEST['no_sk'];
			$tgl_sk=$_REQUEST['tgl_sk'];
			//$tgl_update=date('Y/m/d h:i:s');
			$stat='1';
			//$staset='9';	
			$stusul='4';
			$nilai_buku = getNilaiBuku($id_bukuinduk,$tgl_gantirugi,0);
			$nilai_susut = getAkumPenyusutan($id_bukuinduk,$tgl_gantirugi);
			if ($tgl_sk==''){
				$err="Tanggal Ketetapan Tidak Boleh Kosong";
			}
			if ($no_sk==''){
				$err="Nomor Ketetapan Tidak Boleh Kosong ";
			}
			if ($kepada_nama==''){
				$err="Yang Bertanggung Jawab Tidak Boleh Kosong";
			}
			if ($harga==''){
				$err="Harga Ketetapan Tidak Boleh Kosong";
			}
			if ($f=='' && $g=='' && $h=='' && $i=='' && $j==''){
				$err="Kode Barang Tidak Boleh Kosong";
			}
			
			$fmTANGGALgantirugi  = $_POST['tgl_gantirugi'];
			$thn_gantirugi = substr($fmTANGGALgantirugi,0,4);
			$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$id_bukuinduk' and tahun>='$thn_gantirugi'";
			$get_susut = mysql_fetch_array(mysql_query($query_susut));
			$get_cd = mysql_fetch_array(mysql_query("select c,d,e,e1,staset,idawal from buku_induk where id='$id_bukuinduk'"));
			$staset=$get_cd['staset'];
			$idbi_awal=$get_cd['idawal'];
			//cek sudah ada penyusutan / tdk untuk data baru			
			/*if($get_susut['jml_susut']>0){
				$ErrMsg='Sudah ada penyusutan, data tidak bisa di masukan !';
				}*/
			//cek sudah ada Closing untuk data baru
			if($Main->VERSI_NAME != 'JABAR'){
				$e1=$_REQUEST['e1'];
				$field='nilai_susut,nilai_buku,staset,idbi_awal';
				$vfield="'$nilai_susut','$nilai_buku','$staset','$idbi_awal'";
				$param_closing=$fmTANGGALgantirugi.",".$get_cd['c'].",".$get_cd['d'].",".$get_cd['e'].",".$get_cd['e1'];
			}else{
				$e1='001';
				$field='harga_perolehan';
				$vfield="'$harga_perolehan'";
				$param_closing=$fmTANGGALgantirugi.",".$get_cd['c'].",".$get_cd['d'];
			}
			if(sudahClosing($param_closing)){
				$err = 'Tanggal sudah Closing !';
			}			
			if($err==''){
				$aqry="insert into $tabel (id_bukuinduk,a1,a,b,c,d,e,e1,f,g,h,i,j,no_usul,tgl_usul,tahun,thn_perolehan,noreg,kepada_nama,
					kepada_alamat,uraian,ket,tgl_gantirugi,stat,uid,tgl_update,no_sk,tgl_sk,jml_harga,harga,$field) values
					('$id_bukuinduk','".$Main->DEF_KEPEMILIKAN."','".$Main->DEF_PROPINSI."','".$Main->DEF_WILAYAH."','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$no_usul','$tgl_usul','$tahun','$tahun','$noreg','$kepada_nama',
					'$kepada_alamat','$uraian','$ket','$tgl_gantirugi','$stat','$uid','$tgl_update','$no_sk','$tgl_sk','$harga_perolehan','$harga',$vfield)";$cek.=$aqry;
				$qry=mysql_query($aqry);
				if($qry==FALSE) $err="Gagal Simpan data ".mysql_error();
				}	
			$cek_dt = mysql_fetch_array(mysql_query("select count(*) as cnt from v1_ref_tambah_manfaat  where f='$f' and g='$g' and 	h='$h' and i='$i' and j='$j'"));	
	if($cek_dt['cnt']>0){
		$err= 'Barang sudah ada !';
	}
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);		
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
				
				case 'hapus':{				
					$fm = $this->hapus();				
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
				case 'BatalKetetapan':{
					$get= $this->BatalKetetapan();
					$err= $get['err']; 
					$cek = $get['cek'];
					$content = $get['content'];
					$json=TRUE;	
					break;
				}
				case 'formCari':{				
					$fm = $this->formCari();				
					$cek = $fm['cek'];
					$err = $fm['err'];
					$content = $fm['content'];												
					break;
				}
				
				case 'formKetetapan':{				
					$fm = $this->setFormKetetapan();				
					$cek = $fm['cek'];
					$err = $fm['err'];
					$content = $fm['content'];												
					break;
				}
				
				case 'formKetetapanTanpaUsulan':{				
					$fm = $this->setFormKetetapanTanpaUsulan();				
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
				
				case 'simpanSK':{
					$get= $this->simpanSK();
					$cek = $get['cek'];
					$err = $get['err'];
					$content = $get['content'];
					break;
				}
				
				case 'simpanSKTU':{
					$get= $this->simpanSKTU();
					$cek = $get['cek'];
					$err = $get['err'];
					$content = $get['content'];
					break;
				}		   
				
				case 'simpanPilih':{				
					$get= $this->simpanPilih();
					$cek = $get['cek'];
					$err = $get['err'];
					$content = $get['content'];
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
			"<script type='text/javascript' src='js/gantirugi/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			
			$scriptload;
		}
		function Hapus_Data($ids){//id -> multi id with space delimiter
			$err = ''; $cek='';
			$KeyValue = explode(' ',$ids);
			$arrKondisi = array();
			for($i=0;$i<sizeof($this->KeyFields);$i++){
				$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
			}
			$Kondisi = join(' and ',$arrKondisi);
			if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
			//$Kondisi = 	"Id='".$id."'";
			
			if($err==''){
				$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
				$qry = mysql_query($aqry);
				if ($qry==FALSE){
				$err = 'Gagal Hapus Data';}
			}
			
			
			return array('err'=>$err,'cek'=>$cek);
		}
		function Hapus_Data_After($ids){
			$err = ''; $content=''; $cek='';
			
			return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
		}
		function Hapus($ids){
			$err=''; $cek='';
			//$cid= $POST['cid'];
			//$err = ''.$ids;
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$cek =$cbid[0];			
			$this->form_idplh = $cbid[0];
			for($i = 0; $i<count($ids); $i++)	{
				$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
				$cekdata2=mysql_fetch_array(mysql_query("select count(*)as cnt_bayar from gantirugi_bayar where ref_idgantirugi='".$this->form_idplh."'"));
				if($cekdata['no_sk']!=''){
					$err="Tidak bisa Dihapus, Sudah Ada Ketetapan!";
				}
				if($cekdata2['cnt_bayar']>0){
					$err="Tidak bisa Dihapus, Sudah Ada Pembayaran!";
				}
				if($err ==''){
					$get = $this->Hapus_Data($ids[$i]);
					$err = $get['err'];
					$cek.= $get['cek'];
					if ($errmsg=='') {
						$after = $this->Hapus_Data_After($ids[$i]);
						$err=$after['err'];
						$cek=$after['cek'];
					}
					if ($err != '') break;
					
					}else{
					break;
					}			
			}
			return array('err'=>$err,'cek'=>$cek);
			} 
		function BatalKetetapan(){//id -> multi id with space delimiter
			global $Main,$HTTP_COOKIE_VARS;
			
			$err = ''; $cek=''; $content='';
			$uid = $HTTP_COOKIE_VARS['coID'];
			$tgl=date('Y/m/d h:i:s');
			$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$cek =$cbid[0];			
			$this->form_idplh = $cbid[0];
			
			$KeyValue = explode(' ',$cbid);
			$cekdata=mysql_fetch_array(mysql_query("select count(*)as jml_bayar from v_gantirugi_bayar where ref_idgantirugi='".$this->form_idplh."'"));
			$dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
			$status_barang='5';
			$stat='0';
			$tgl_gantirugi = $dtTGR['tgl_gantirugi'];
			$tgl_gantirugi_arr=explode('-',$tgl_gantirugi);
			$thn_gantirugi_lama=$tgl_gantirugi_arr['0'];
			//$err=$thn_gantirugi_lama.' ini satu '.$thn_Closing.' @ '.$Main->TAHUN_CLOSING ;
			//if($thn_gantirugi_lama<=$Main->TAHUN_CLOSING){
				/*if( sudahClosing( $tgl_gantirugi )){
					$err="Tidak bisa Dibatalkan, Ganti Rugi sudah Closing!";
					}*/
				$kueri="select * from $this->TblName_Hapus 
				where id = '".$this->form_idplh."' "; //echo "$kueri";
				$data=mysql_fetch_array(mysql_query($kueri));
				if($Main->VERSI_NAME != 'JABAR'){
					$param_closing=$tgl_gantirugi.','.$dtTGR['c'].','.$dtTGR['d'].','.$dtTGR['e'].','.$dtTGR['e1'];
				}else{
					$param_closing=$tgl_gantirugi.','.$dtTGR['c'].','.$dtTGR['d'];
				}
					if($err=='' && sudahClosing($param_closing))$err = "Tidak bisa Dibatalkan, Ganti Rugi sudah Closing!";
				//cek sudah ada penyusutan / tdk untuk data baru			
				$oldthn_gantirugi = substr($data['tgl_gantirugi'],0,4);
				$query_susut = "select count(*)as jml_susut from penyusutan where idbi='".$data['id_bukuinduk']."' and tahun>='$oldthn_gantirugi'";
				$get_susut = mysql_fetch_array(mysql_query($query_susut));
				/*if($get_susut['jml_susut']>0){
					$err="Tidak bisa Dibatalkan, Sudah ada penyusutan !";
					}*/
				if($cekdata['jml_bayar']>0){
					$err="Tidak bisa Dibatalkan, Sudah Ada Pembayaran!";
				}
				if($err==''){
					/*$aqry1= "Update buku_induk set 
					staset='".$dtTGR['staset']."',". 
					//status_barang='$status_barang'
					" status_barang=1, stusul=4 ".
					" where id='".$dtTGR['id_bukuinduk']."'"; $cek.=$aqry1;
					$qry1 = mysql_query($aqry1);
					if ($qry1==FALSE){
					$err = 'Gagal Update Data BI '.mysql_error();}*/
					if($err==''){
						$aqry= "Update gantirugi set 
						no_sk='', 
						tgl_sk=null, 
						harga=null, 
						uid='$uid',
						tgl_gantirugi = null,
						tgl_update='$tgl',
						stat='$stat'
						
						where id='".$this->form_idplh."'"; $cek.=$aqry;
						$qry = mysql_query($aqry);
						if ($qry==FALSE){
						$err = 'Gagal Hapus Data'.mysql_error();}}
					}
					
					
					return array('err'=>$err,'cek'=>$cek, 'content'=>$content);
				}
				
				//form ==================================
				function formCari(){
				global $Main;
					$cek = ''; $err=''; $content=''; $json=TRUE;
					$cek = 'tes';
					
					$dt=array();
					$this->form_fmST = 0;
					//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
					//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
					//$fm = $this->setFormCr($dt);
					
					$sw=$_REQUEST['sw'];
					$sh=$_REQUEST['sh'];
					$tgl=$_REQUEST['tgl'];
					if($Main->VERSI_NAME != 'JABAR'){
						$wil_skpd=WilSKPD_ajx3('gantirugiCariSkpd','100%',100,FALSE,'','','',TRUE,'',1);
					}else{
						$wil_skpd=WilSKPD_ajx('gantirugiCariSkpd','100%',100,FALSE,'','','',TRUE,'',1);
					}
					$form_name = 'adminForm';	//nama Form			
					$this->form_width = $sw-50;
					$this->form_height = $sh-100;
					$this->form_caption = 'Cari Barang'; //judul form
					$this->form_fields = array(	
					'skpd' => array( 
					'label'=>'',
					'value'=>
					"<table width=\"100%\" class=\"adminForm\">	<tr>		
					<td width=\"100%\" valign=\"top\">" . 					
					$wil_skpd. 
					//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr></table>", 
					'type'=>'merge'
					),		
					'detailcari' => array( 
					'label'=>'',
					'value'=>"<div id='div_detailcari' style='height:5px'></div>", 
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >
					<input type='hidden' id='tgl' name='tgl' value='$tgl' >"
					,
					$this->form_menu_bawah_height,
					'',1
					).
					"</form>";
					
					$content = $form;
					
					
					return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
				}
				
				
				function setFormKetetapan(){
				global $HTTP_COOKIE_VARS;
				$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
				$tgl_buku =	$thn_login.'-00-00';
					$cbid = $_REQUEST[$this->Prefix.'_cb'];
					$cek =$cbid[0];			
					$this->form_idplh = $cbid[0];
					$this->form_fmST = 2;
					$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
				if($cekdata['no_sk']!=''){	$fm['err']="Sudah Ditetapan!";	}
					
					if($fm['err']==''){
						$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
						
						$qry=mysql_query($aqry);
						
						$dt=mysql_fetch_array($qry);
						$dt['tgl_gantirugi'] = $tgl_buku;
						$dt['tgl_sk'] = Date('Y-m-d');
						$fm = $this->setForm($dt);
					}
					
					return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
				}
				
				function setFormKetetapanTanpaUsulan(){
					global $Main;
					global $HTTP_COOKIE_VARS;
					$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
					$tgl_buku =	$thn_login.'-00-00';
					$err='';
					$dt=array();
					$this->form_fmST = 3;
					$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
					$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
					$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];					
					if($Main->VERSI_NAME != 'JABAR'){
						$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
					}else{
						$dt['e1'] = '001';
					}
					$dt['tgl_gantirugi'] = $tgl_buku;
					$dt['tgl_sk'] = Date('Y-m-d');
					
					if ($err=='') $fm = $this->setForm($dt);	
					
					return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
					}	
				
				function setFormBaru(){
					global $Main;
					$err='';
					$dt=array();
					$this->form_fmST = 0;
					$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
					$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
					$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
					if($Main->VERSI_NAME != 'JABAR'){
						$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
					}else{
						$dt['e1'] = '001';
					}
					$dt['tgl_usul'] 	= Date('Y-m-d');
					$dt['tgl_sk'] 		= Date('Y-m-d');
					//$dt['tgl_gantirugi']= Date('Y-m-d');
					
					if ($err=='') $fm = $this->setForm($dt);	
					
					return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
				}
				
				function setFormEdit(){
					$cbid = $_REQUEST[$this->Prefix.'_cb'];
					$cek =$cbid[0];
					$this->form_idplh = $cbid[0];
					$this->form_fmST = 1;
					
					$cekdata=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$this->form_idplh."'"));
				if($cekdata['no_sk']!=''){ $fm['err']="Tidak bisa Diedit, Sudah Ada Ketetapan!"; }
					if($fm['err']==''){
						$aqry = "select * from ".$this->TblName." where id='".$this->form_idplh."'"; $cek.=$aqry;
						$qry=mysql_query($aqry);
						$dt=mysql_fetch_array($qry);
						$fm = $this->setForm($dt);
					}
					
					return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
				}
				
				function setForm($dt){	
					global $SensusTmp;
					$cek = ''; $err=''; $content=''; 
					$json = TRUE;	//$ErrMsg = 'tes';
					
					$form_name = $this->Prefix.'_form';				
					
					if ($this->form_fmST==0) {
						$this->form_caption = 'USULAN BARU';
						}elseif($this->form_fmST==1){
						$this->form_caption = 'EDIT USULAN';
						}elseif($this->form_fmST==3){
						$this->form_caption = 'Form Ketetapan Tanpa Usulan';
						}else{
						$this->form_caption = 'Form Ketetapan ';
					}
					
					/*$dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$dt['id']."'"));
					$brg = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='".$dtTGR['f'].$dtTGR['g'].$dtTGR['h'].$dtTGR['i'].$dtTGR['j']."'")) ;
					if ($dtTGR['tgl_usul']='0000-00-00'){
						$dtTGR['tgl_usul']=Date('Y-m-d');
					}
					if ($dtTGR['tgl_sk']='0000-00-00'){
						$dtTGR['tgl_sk']=Date('Y-m-d');
						}	   
					if ($dtTGR['tgl_gantirugi']='0000-00-00'){
						$dtTGR['tgl_gantirugi']=Date('Y-m-d');
					}
					$kode_skpd = $dtTGR['f'].'.'.$dtTGR['g'].'.'.$dtTGR['h'].'.'.$dtTGR['i'].'.'.$dtTGR['j'] ;	   
					*/
					$kdbarang = $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
					$qrbrg = "select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j) = '".$kdbarang."'";
					$brg = mysql_fetch_array(mysql_query($qrbrg)) ;			
					//items ----------------------
					if ($this->form_fmST==0 || $this->form_fmST==1){ //baru /edit usulan
						$this->form_width = 720;
						$this->form_height = 390;
						
						$this->form_fields = array(	
						'no_usul' => array( 
						'label'=>'No Usulan',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='no_usul' id='no_usul' value='".$dt['no_usul']."'>"			 
						),
						'tgl_usul' => array( 
						'label'=>'Tanggal Usulan',
						'labelWidth'=>100,
						'value'=>createEntryTgl( 'tgl_usul', $dt['tgl_usul'], false,'','',$this->FormName),			 
						),
						'nama_barang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>100, 
						'value'=>
						"<input type='text' size=15 name='fmIDBARANG' id='fmIDBARANG' value='$kdbarang' readonly=''>".
						"&nbsp;<input type='text' size=60 name='fmNMBARANG' id='fmNMBARANG' value='".$brg['nm_barang']."' readonly=''>".
						"&nbsp;<input type='button' value='cari' onclick=\"".$this->Prefix.".caribarang1(1)\" >"			 
						),
						'tahun' => array( 
						'label'=>'Tahun Perolehan',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='tahun' id='tahun' value='".$dt['tahun']."' size='4' readonly>"			 
						),
						'noreg' => array( 
						'label'=>'No Register',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='noreg' id='noreg' value='".$dt['noreg']."' size='4' readonly>"			 
						),
						'harga_perolehan' => array( 
						'label'=>'Harga Perolehan',
						'labelWidth'=>100, 
						'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".number_format($dt['jml_harga'], 2, ',' , '.')."' readonly=''>"			 
						),
						'kpd' => array( 
						'labelWidth'=>100, 
						'value'=>"Yang Bertanggung Jawab",
						'type'=>'merge'			 
						),
						'kepada_nama' => array( 
						'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama',
						'labelWidth'=>100, 
						'value'=>"<input type='text' size='30' name='kepada_nama' id='kepada_nama' value='".$dt['kepada_nama']."' >"			 
						),	
						'kepada_alamat' => array( 
						'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',
						'labelWidth'=>100, 
						'value'=>"<textarea name='kepada_alamat' id='kepada_alamat' cols='83'>{$dt['kepada_alamat']}</textarea>"
						),
						'uraian' => array( 
						'label'=>'Uraian',
						'labelWidth'=>100, 
						'value'=>"<textarea name='uraian' id='uraian' cols='83'>{$dt['uraian']}</textarea>"			 
						),
						'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>100, 
						'value'=>"<textarea name='ket' id='ket' cols='83'>{$dt['ket']}</textarea>"			 
						),				 
						);
						$simpan1="<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan1()' >";
					}
					
					elseif($this->form_fmST==3){ //ketetapan tanpa usulan
						$this->form_width = 750;
						$this->form_height = 460;
						
						$this->form_fields = array(	
						'tgl_gantirugi' => array( 
						'label'=>'Tanggal Pembukuan',
						'labelWidth'=>150,
						'value'=>createEntryTgl('tgl_gantirugi', $dt['tgl_gantirugi'], false,'','',$this->FormName,'2'),			 
						),			
						'tgl_sk' => array( 
						'label'=>'Tanggal Ketetapan',
						'labelWidth'=>150,
						'value'=>createEntryTgl( 'tgl_sk', $dt['tgl_sk'], false,'','',$this->FormName),			 
						),
						'no_sk' => array( 
						'label'=>'No Ketetapan',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='no_sk' id='no_sk' value='".$dt['no_sk']."' size='24'>"			 
						),
						'harga' => array( 
						'label'=>'Harga Ketetapan ',
						'labelWidth'=>150, 
						'value'=>"Rp &nbsp;&nbsp;".inputFormatRibuan("harga", '',$dt['harga']),		 
						),
						
						'nama_barang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>150, 
						'value'=>"<input type='text' size='15' name='fmIDBARANG' id='fmIDBARANG' value='$kode_skpd' readonly=''>".
						"&nbsp;<input type='text' size='60' name='fmNMBARANG' id='fmNMBARANG' value='".$brg['nm_barang']."' readonly=''>".
						"&nbsp;<input type='button' value='cari' onclick=\"".$this->Prefix.".caribarang1(2)\" >"			 
						),
						'tahun' => array( 
						'label'=>'Tahun Perolehan',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='tahun' id='tahun' value='".$dt['tahun']."' size='4' readonly>"			 
						),
						'noreg' => array( 
						'label'=>'No Register',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='noreg' id='noreg' value='".$dt['noreg']."' size='4' readonly>"			 
						),
						'harga_perolehan' => array( 
						'label'=>'Harga Perolehan',
						'labelWidth'=>150, 
						'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".number_format($dt['jml_harga'], 2, ',' , '.')."' readonly=''>"			 
						),
						'kpd' => array( 
						'labelWidth'=>150, 
						'value'=>"Yang Bertanggung Jawab",
						'type'=>'merge'			 
						),
						'kepada_nama' => array( 
						'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama',
						'labelWidth'=>150, 
						'value'=>"<input type='text' size='30' name='kepada_nama' id='kepada_nama' value='".$dt['kepada_nama']."'>"			 
						),	
						'kepada_alamat' => array( 
						'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat',
						'labelWidth'=>150, 
						'value'=>"<textarea name='kepada_alamat' id='kepada_alamat' cols='83'>{$dt['kepada_alamat']}</textarea>"		 
						),
						'uraian' => array( 
						'label'=>'Uraian',
						'labelWidth'=>150, 
						'value'=>"<textarea name='uraian' id='uraian' cols='83'>{$dt['uraian']}</textarea>"			 
						),
						'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>150, 
						'value'=>"<textarea name='ket' id='ket' cols='83'>{$dt['ket']}</textarea>"			 
						),				 
						);
						$simpan1="<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan3()' >";
						
					}
					else{ //ketetapan 
						$this->form_width = 710;
						$this->form_height = 290;
						$this->form_fields = array(	
						'no_usul' => array( 
						'label'=>'No Usulan',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='no_usul' id='no_usul' value='".$dt['no_usul']."' readonly>"			 
						),
						'tgl_usul' => array( 
						'label'=>'Tanggal Usulan',
						'labelWidth'=>150,
						'value'=> TglInd( $dt['tgl_usul']) //createEntryTgl( 'tgl_usul', $dt['tgl_usul'], false,'','',$this->FormName),			 
						),
						'nama_barang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>150, 
						'value'=>
						"<input type='text' size=15 name='fmIDBARANG' id='fmIDBARANG' value='$kdbarang' readonly=''>".
						"&nbsp;<input type='text' size=60 name='fmNMBARANG' id='fmNMBARANG' value='".$brg['nm_barang']."' readonly=''>"
						//"&nbsp;<input type='button' value='cari' onclick=\"".$this->Prefix.".caribarang1()\" >"			 
						),
						'tahun' => array( 
						'label'=>'Tahun Perolehan',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='tahun' id='tahun' value='".$dt['tahun']."' size='4' readonly>"			 
						),
						'noreg' => array( 
						'label'=>'No Register',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='noreg' id='noreg' value='".$dt['noreg']."' size='4' readonly>"			 
						),
						'harga_perolehan' => array( 
						'label'=>'Harga Perolehan',
						'labelWidth'=>100, 
						'value'=>"Rp &nbsp; <input type='text' name='harga_perolehan' id='harga_perolehan' value='".number_format($dt['jml_harga'], 2, ',' , '.')."' readonly=''>"			 
						),								 
						'tgl_gantirugi' => array( 
						'label'=>'Tanggal Pembukuan',
						'labelWidth'=>150,
						'value'=>createEntryTgl( 'tgl_gantirugi', $dt['tgl_gantirugi'], false,'','',$this->FormName,'2'),			 
						),
						'tgl_sk' => array( 
						'label'=>'Tanggal Ketetapan',
						'labelWidth'=>150,
						'value'=>createEntryTgl( 'tgl_sk', $dt['tgl_sk'], false,'','',$this->FormName),			 
						),
						'no_sk' => array( 
						'label'=>'No Ketetapan',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='no_sk' id='no_sk' value='".$dt['no_sk']."' size='24'>"			 
						),
						'harga' => array( 
						'label'=>'Harga Ketetapan',
						'labelWidth'=>150, 
						'value'=>"Rp &nbsp;&nbsp;".inputFormatRibuan("harga", '',$dt['harga']),		 
						),
						
						);
						$simpan1="<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpan2()' >";
					}
					
					//tombol
					$this->form_menubawah =	
					"<input type='hidden' value='".$dt['id_bukuinduk']."' name='idbi' id='idbi'>".
					"<input type='hidden' value='".$dt['c']."' name='c' id='c'>".
					"<input type='hidden' value='".$dt['d']."' name='d' id='d'>".
					"<input type='hidden' value='".$dt['e']."' name='e' id='e'>".
					"<input type='hidden' value='".$dt['e1']."' name='e1' id='e1'>".
					//"<input type='hidden' value='".$dtTGR['tgl_gantirugi']."' name='tgl_gantirugi' id='tgl_gantirugi'>".
					$simpan1.
					"&nbsp;<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()'title='Batal kunjungan' >";
					
					$form = $this->genForm();		
					$content = $form;//$content = 'content';
					return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
				}
				
				
				//daftar =================================	
				function setKolomHeader($Mode=1, $Checkbox=''){
					$NomorColSpan = $Mode==1? 2: 1;
					
					$headerTable =
					"<tr>
					<th class='th01' width='20' rowspan='2'>No.</th>
					$Checkbox 		
					<th class='th02' colspan='2'>Usulan</th>
					<th class='th01' rowspan='2'>Kode Barang / <br>ID Barang</th>
					<th class='th01' rowspan='2'>Tahun Perolehan/ Noreg</th>
					<th class='th02' colspan='3'>Spesifikasi Barang</th>
					<th class='th01' rowspan='2'>Kondisi</th>
					<th class='th01' rowspan='2'>Harga Perolehan</th>
					<!--<th class='th01' rowspan='2'>Nilai Buku</th>-->
					<th class='th01' rowspan='2'>Harga Akumulasi Penyusutan </th>
					<th class='th02' colspan='2'>Kepada</th>
					<th class='th01' rowspan='2'>Uraian</th>
					<th class='th02' colspan='3'>SK TGR</th>
					<th class='th01' rowspan='2'>Tanggal Buku <br>Ganti Rugi</th>
					
					<th class='th01' rowspan='2' width='200'>Keterangan</th>
					</tr>
					
					<tr>
					<th class='th01' >No</th>
					<th class='th01' >Tanggal</th>
					<th class='th01' >Nama Barang/<br>Jenis</th>
					<th class='th01' >Merk/Tipe/Alamat</th>
					<th class='th01' >No. Sertifikat/ No. Pabrik/<br> No. Chasis/<br> No. Mesin/<br> No.Polisi</th>
					<th class='th01' >Nama</th>
					<th class='th01' >Alamat</th>
					<th class='th01' >No</th>
					<th class='th01' >Tanggal</th>
					<th class='th01' >Harga</th>
					
					</tr>
					";
					
					return $headerTable;
					}	
				
				function setKolomData($no, $isi, $Mode, $CheckBox){
					global $Ref;
					global $Main;
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
							$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket,no_polisi  from kib_b  $KondisiKIB limit 0,1";
							//echo"<br>qrkbb=".$aqry;
							
							$QryKIB_B = mysql_query($aqry);
							
							//echo "<br>qrkibb=".$aqry;
							while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
								$isiKIB_B = array_map('utf8_encode', $isiKIB_B);
								$ISI5 = "{$isiKIB_B['merk']}";
								$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']} / {$isiKIB_B['no_polisi']}";
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
					$dt1=mysql_fetch_array(mysql_query("select * from kib_b where idbi='".$isi['id_bukuinduk']."'"));
					$dtbi=mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'"));
					$dtTGR=mysql_fetch_array(mysql_query("select * from gantirugi where id='".$isi['id']."'"));
					$dt2=mysql_fetch_array(mysql_query("select * from ref_barang where f='".$dtTGR['f']."' 
					and g='".$dtTGR['g']."' and h='".$dtTGR['h']."' and i='".$dtTGR['i']."' and j='".$dtTGR['j']."'"));
					$nilai_buku = getNilaiBuku($isi['id_bukuinduk'],$dtTGR['tgl_gantirugi'],0);					
					$nilai_susut = getAkumPenyusutan($isi['id_bukuinduk'],$dtTGR['tgl_gantirugi']);
					$harga_ketetapan = number_format($isi['harga'], 2, ',' , '.');
					if ( $Main->VERSI_NAME == 'JABAR') {
						$harga_perolehan = number_format($isi['harga_perolehan'], 2, ',' , '.');	
					}else{
						$harga_perolehan = number_format($nilai_buku, 2, ',' , '.');	  	
					}
										
					$kode_barang=$dtTGR['f'].'.'.$dtTGR['g'].'.'.$dtTGR['h'].'.'.$dtTGR['i'].'.'.$dtTGR['j'] ;
					$Koloms = array();
					$Koloms[] = array('align="center" width="20"', $no.'.' );
					if ($Mode == 1) $Koloms[] = array(" align='center'  ", $CheckBox);
					$Koloms[] = array('align="left" ',$dtTGR['no_usul']);
					$Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_usul']));
					$Koloms[] = array('align="left" ',$kode_barang." / ".$isi['id_bukuinduk']);
					$Koloms[] = array('align="left" ',$dtTGR['thn_perolehan']." /<br/> ". $dtTGR['noreg']);
					$Koloms[] = array('align="left" "',$dt2['nm_barang']);
					$Koloms[] = array('align="left" "',$ISI5);
					$Koloms[] = array('align="left" "',$ISI6); 
					$Koloms[] = array('align="left" ',$Main->KondisiBarang[$dtbi['kondisi']-1][1]);
					$Koloms[] = array('align="right" ',$harga_perolehan);
					//$Koloms[] = array('align="right" ',number_format($nilai_buku, 2, ',' , '.'));
					$Koloms[] = array('align="right" ',number_format($nilai_susut, 2, ',' , '.'));
					$Koloms[] = array('align="left" ',$dtTGR['kepada_nama']);
					$Koloms[] = array('align="left" ',$dtTGR['kepada_alamat']);
					$Koloms[] = array('align="left" ',$dtTGR['uraian']);
					$Koloms[] = array('align="left" ',$dtTGR['no_sk']);
					$Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_sk']));					
					$Koloms[] = array('align="right" ',$harga_ketetapan);
					$Koloms[] = array('align="left" ',TglInd($dtTGR['tgl_gantirugi']));
					$Koloms[] = array('align="left" ',$dtTGR['ket']);	 	 	 	 
					return $Koloms;
					
				}		
				
				function setNavAtas(){
				$Pg = $_REQUEST['Pg'];
					
					$gantirugi = '';
					$pembayaran = '';
					$progres='';
					switch ($Pg){
						case 'gantirugi': $gantirugi ="style='color:blue;'"; break;
						case 'gantirugibayar': $pembayaran ="style='color:blue;'"; break;
						case 'gantirugiprogres': $progres ="style='color:blue;'"; break;
					}
					return 
					"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
					<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
					<A href=\"pages.php?Pg=gantirugi\" title='TGR' $gantirugi>Tuntutan Ganti Rugi</a> |
					<A href=\"pages.php?Pg=gantirugibayar\" title='Pembayaran'  $pembayaran>Pembayaran</a> |
					<A href=\"pages.php?Pg=gantirugiprogres\" title='Pelaporan'  $progres>Pelaporan</a>    												
					&nbsp&nbsp&nbsp	
					</td></tr></table>";
				}
				
				
				function genDaftarOpsi(){
					global $Ref, $Main;
					//get pilih bidang
					
					//Get data
					$fmid_barang=$_REQUEST['fmid_barang'];
					$fmkode_barang=$_REQUEST['fmkode_barang'];
					$fmtahun=$_REQUEST['fmtahun'];
					$fmnoreg=$_REQUEST['fmnoreg'];
					$fmnoketetapan=$_REQUEST['fmnoketetapan'];
					$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
					if($Main->VERSI_NAME != 'JABAR'){
						$wil_skpd=WilSKPD_ajx3($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
					}else{
						$wil_skpd=WilSKPD_ajx($this->Prefix.'Skpd','100%',100,FALSE,'','','',TRUE,'',1);
					}	
					
					$TampilOpt = 
					"<table width=\"100%\" class=\"adminform\">	<tr>		
					<td width=\"100%\" valign=\"top\">			
					".$wil_skpd."
					</td>
					<td style='padding:6'>
					</td>
					</tr></table>".
					
					genFilterBar(
					array(	
					"ID Barang &nbsp;"
					."<input type='text'  size='12' value='$fmid_barang' id='fmid_barang' name='fmid_barang'>
					Kode Barang &nbsp;"
					."<input type='text'  size='12' value='$fmkode_barang' id='fmkode_barang' name='fmkode_barang'>
					&nbsp; Tahun Perolehan &nbsp;" 
					."<input type='text'  size='12' value='$fmtahun' id='fmtahun' name='fmtahun'>
					&nbsp; No. Reg &nbsp;" 
					."<input type='text'  size='12' value='$fmnoreg' id='fmnoreg' name='fmnoreg'>
					&nbsp; No. SK &nbsp;" 
					."<input type='text'  size='12' value='$fmnoketetapan' id='fmnoketetapan' name='fmnoketetapan'> &nbsp; ".
					"Tahun Anggaran &nbsp;".
					"<input type='text'  size='12' value='$fmtahunanggaran' id='fmtahunanggaran' name='fmtahunanggaran'> &nbsp;".
					"<input type='hidden'  size='12' value='$fmstat' id='fmstat' name='fmstat'>"
					),$this->Prefix.".refreshList(true)",TRUE
					)
						;			
					return array('TampilOpt'=>$TampilOpt);
					}	
				
				function getDaftarOpsi($Mode=1){
					global $Main, $HTTP_COOKIE_VARS;
					$UID = $_COOKIE['coID']; 
					//kondisi -----------------------------------
					$arrKondisi = array();		
					//get pilih bidang
					$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
					$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
					$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
					$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');
					
					//Get data
					$fmid_barang=$_REQUEST['fmid_barang'];
					$fmkode_barang=$_REQUEST['fmkode_barang'];
					$fmtahun=$_REQUEST['fmtahun'];
					$fmnoreg=$_REQUEST['fmnoreg'];
					$fmnoketetapan=$_REQUEST['fmnoketetapan'];
					$fmstat=$_REQUEST['fmstat'];
					$fmtahunanggaran = $_REQUEST['fmtahunanggaran'];
					
					
					/**
					if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "(select c from gantirugi where id= v_gantirugi.id)  like '%$fmSKPD%'";
					if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "(select d from gantirugi where id= v_gantirugi.id)  like '%$fmUNIT%'";
					if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "(select e from gantirugi where id= v_gantirugi.id)  like '%$fmSUBUNIT%'";
					if(!empty($fmstat)) $arrKondisi[]= " (select stat from gantirugi where id= v_gantirugi.id)  like '%$fmstat%'";
					if(!empty($fmkode_barang)) $arrKondisi[]= " (select concat(f,'.',g,'.',h,'.',i,'.',j) from gantirugi where id= v_gantirugi.id)  like '$fmkode_barang%'";
					if(!empty($fmtahun)) $arrKondisi[]= " (select tahun from gantirugi where id= v_gantirugi.id) like '%$fmtahun%'";
					if(!empty($fmnoreg)) $arrKondisi[]= " (select noreg from gantirugi where id= v_gantirugi.id) like '%$fmnoreg%'";
					if(!empty($fmnoketetapan)) $arrKondisi[]= " (select no_sk from gantirugi where id= v_gantirugi.id) like '%$fmnoketetapan%' ";
					**/
					
					if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = " c = '$fmSKPD'";
					if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = " d= '$fmUNIT'";
					if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = " e = '$fmSUBUNIT'";
					if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = " e1 = '$fmSEKSI'";
					
					if(!empty($fmstat)) $arrKondisi[]= " stat like '$fmstat%'";
					if(!empty($fmid_barang)) $arrKondisi[]= "  id_bukuinduk like '%$fmid_barang%'";
					if(!empty($fmkode_barang)) $arrKondisi[]= "  concat(f,'.',g,'.',h,'.',i,'.',j) like '$fmkode_barang%'";
					if(!empty($fmtahun)) $arrKondisi[]= " thn_perolehan = '$fmtahun'";
					if(!empty($fmtahunanggaran)) $arrKondisi[]= " year(tgl_gantirugi) <= '$fmtahunanggaran'";
					if(!empty($fmnoreg)) $arrKondisi[]= " noreg = '$fmnoreg'";
					if(!empty($fmnoketetapan)) $arrKondisi[]= " no_sk like '%$fmnoketetapan%' ";
					
					
					$Kondisi= join(' and ',$arrKondisi);		
					$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
					/*
					//Order -------------------------------------
					$fmORDER1 = cekPOST('fmORDER1');
					$fmDESC1 = cekPOST('fmDESC1');			
					$Asc1 = $fmDESC1 ==''? '': 'desc';		
					$arrOrders = array();
					switch($fmORDER1){
						case '': $arrOrders[] = " concat(f,g) ASC " ;break;
						case '1': $arrOrders[] = " concat(f,g) $Asc1 " ;break;
						case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
						
						}*/
					
					$Order= join(',',$arrOrders);	
					$OrderDefault =' ';// Order By no_terima desc ';
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
		$gantirugi = new gantirugiObj();
		
?>