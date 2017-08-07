<?php
class rkbmd_ins_v3Obj  extends DaftarObj2{	
	var $Prefix = 'rkbmd_ins_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'temp_rkbmd_pengadaan_v3'; //bonus
	var $TblName_Hapus = 't_penerimaan_barang';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('jumlah');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 3, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'RKBMD';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $ico_width = '28.8';
	var $ico_height = '28.8';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='rkbmd.xls';
	var $namaModulCetak='PERENCANAAN';
	var $Cetak_Judul = 'RKBMD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'rkbmd_ins_v3Form';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $modul = "RKBMD";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	
	function setTitle(){
	    $id = $_REQUEST['ID_RENJA'];
	    $getTahun = mysql_fetch_array(mysql_query("select * from tabel_anggaran_v3 where id_anggaran = '$id'"));
		return 'RKBMD PENGADAAN TAHUN '.$getTahun['tahun'] ;
	}
	
	function setMenuEdit(){
		return "";

	}
	
	function setMenuView(){
		return "";
	}
	
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		if (sizeof($this->FieldSum)>0){
			$TampilTotalHalRp = number_format($this->SumValue[0],0, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>JUMLAH</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					<td class='$ColStyle' align='right'><b></td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;
			
				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		}
		return $ContentTotalHal.$ContentTotal;
	}
	
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main,$HTTP_COOKIE_VARS;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 	  
	  switch($tipe){	
			
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		
		case 'subDelete':{				
			$id = $_REQUEST['id'];
			$username = $_COOKIE['coID'];
			mysql_query("delete from temp_rkbmd_pengadaan_v3 where id='$id'");	
			$getRow = mysql_num_rows(mysql_query("select * from temp_rkbmd_pengadaan_v3 where user = '$username'"));	
			if($getRow > 0){
				$content = "ada";
			}else{
				$content = "";
			}			
		break;
		}
		case 'subEdit':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			if($jumlah > $jumlahKebutuhanRiil && $jumlahKebutuhanRiil != '0' && !empty($jumlahKebutuhanOptimal) && !empty($jumlahKebutuhanMaksimal)){
				$err = "jumlah tidak dapat melebihi kebutuhan ril";
			}else{
				$data = array('jumlah' => $jumlah ,
							  'satuan' => $satuan ,
						  	  'catatan' => $keterangan
						);
				mysql_query(VulnWalkerUpdate('temp_rkbmd_pengadaan_v3',$data,"id = '$id'"));
			}
			
			$content = VulnWalkerUpdate('temp_rkbmd_pengadaan_v3',$data,"id = '$id'");
			
		break;
		}
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'clear':{	
			 $username = $_COOKIE['coID'];
		  	 mysql_query("delete from temp_rkbmd_pengadaan_v3 where user='$username'");
		break;
		}
		case 'finish':{				
			$username = $_COOKIE['coID'];
			$execute = mysql_query("select * from temp_rkbmd_pengadaan_v3 where user='$username'");	
			$adaData = mysql_num_rows($execute);
			if($this->jenisForm != 'PENYUSUNAN'){
				$err = "TAHAP PENYUSUNAN TELAH HABIS";
			}elseif($adaData >= 1 ){
				$get = mysql_fetch_array($execute);
			foreach ($get as $key => $value) { 
				  $$key = $value; 
			}
			
			$getIDRenjaForUpdate = mysql_fetch_array(mysql_query("select * from view_renja_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and jenis_anggaran = '$this->jenisAnggaran' "));
			$arrayUpdateRenja = array('status_validasi' => '1');
			mysql_query(VulnWalkerUpdate('tabel_anggaran_v3',$arrayUpdateRenja,"id_anggaran='".$getIDRenjaForUpdate['id_anggaran']."'"));
			
			
			mysql_query("delete from tabel_anggaran_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and tahun='$this->tahun' and id_tahap ='$this->idTahap' and ((id_jenis_pemeliharaan = '0' and f !='00') or uraian_pemeliharaan = 'RKBMD PENGADAAN') and status_validasi !='1' ");
			$cekSKPD = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '0' and ck = '0' and p = '0' and q= '0' and id_tahap='$this->idTahap'"));
			if($cekSKPD < 1){
				$data = array('jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun,
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => $e,
							  'e1' => $e1,
							  'bk' => '0',
							  'ck' => '0',
							  'dk' => '0',
							  'p' => '0',
							  'q' => '0',
							  'f' => '00',
							  'g' => '00',
							  'h' => '00',
							'i' => '00',
										    'j' => '000',
							  'id_tahap' => $this->idTahap,
							  'nama_modul' => "RKBMD",
							  'tanggal_update' => date('Y-m-d'),
							  'user_update' => $_COOKIE['coID']
								);
					mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
			}
			$cekProgram = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '0' and id_tahap='$this->idTahap' "));												
			if($cekProgram < 1){
				$data = array('jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun,
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => $e,
							  'e1' => $e1,
							  'bk' => $bk,
							  'ck' => $ck,
							  'dk' => '0',
							  'p' => $p,
							  'q' => '0',
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
							  'id_tahap' => $this->idTahap,
							  'nama_modul' => "RKBMD",
							  'tanggal_update' => date('Y-m-d'),
							  'user_update' => $_COOKIE['coID']
								);
					mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
			}
			
			$cekKegiatan = mysql_num_rows(mysql_query("select * from view_rkbmd_v3 where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' and bk = '$bk' and ck = '$ck' and p = '$p' and q= '$q' and  f ='00' and id_tahap='$this->idTahap' and uraian_pemeliharaan = 'RKBMD PENGADAAN'"));												
			if($cekKegiatan < 1){
				$data = array('jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun,
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => $e,
							  'e1' => $e1,
							  'bk' => $bk,
							  'ck' => $ck,
							  'dk' => '0',
							  'p' => $p,
							  'q' => $q,
							  				'f' => '00',
							 			    'g' => '00',
							  			    'h' => '00',
										    'i' => '00',
										    'j' => '000',
							  'id_tahap' => $this->idTahap,
							  'nama_modul' => "RKBMD",
							  'tanggal_update' => date('Y-m-d'),
							  'user_update' => $_COOKIE['coID'] ,
							  'uraian_pemeliharaan' => 'RKBMD PENGADAAN'
								);
					mysql_query(VulnWalkerInsert('tabel_anggaran_v3',$data));
			}	
			$execute2 = mysql_query("select * from temp_rkbmd_pengadaan_v3 where user='$username'");
			
														
			while($rows = mysql_fetch_array($execute2)){
				foreach ($rows as $key => $value) { 
				  $$key = $value; 
				}
				$data = array('jenis_anggaran' => $this->jenisAnggaran,
							  'tahun' => $this->tahun,
							  'c1' => $c1,
							  'c' => $c,
							  'd' => $d,
							  'e' => $e,
							  'e1' => $e1,
							  'bk' => $bk,
							  'ck' => $ck,
							  'p' => $p,
							  'q' => $q,
							  'f' => $f,
							  'g' => $g,
							  'h' => $h,
							  'i' => $i,
							  'j' => $j,
							  'satuan_barang' => $satuan,
							  'volume_barang'=> $jumlah,
							  'catatan' => $catatan,
							  'id_tahap' => $this->idTahap,
							  'nama_modul' => "RKBMD",
							  'tanggal_update' => date('Y-m-d'),
							  'user_update' => $_COOKIE['coID'],
							  
							  );
					mysql_query(VulnWalkerInsert("tabel_anggaran_v3",$data));
					$content = VulnWalkerInsert("tabel_anggaran_v3",$data);
					mysql_query("delete from temp_rkbmd_pengadaan_v3 where id = '$id'");
			}
			}else{
				$err  = "Data kosong !";
			}
			
		break;
		}
		
		case 'CekKosong':{				
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			$query = "select * from view_rkbmd_v3 where c1 = '$c1' and c ='$c' and d ='$d' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='$c' and d ='$d' and tahun = '$tahunAnggaran' ");
			}
			
			
			$query = "select * from view_rkbmd_v3 where c1 = '$c1' and c ='$c' and d !='00' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='$c' and d ='00' and tahun = '$tahunAnggaran'");
				
			}
			
			$query = "select * from view_rkbmd_v3 where  c1 = '$c1' and c !='00' and d !='00' and p !='00' and q != '00' and tahun = '$tahunAnggaran'";
			$ada = mysql_num_rows(mysql_query($query));
			if($ada > 0){
			}else{
				mysql_query("delete from rkbmd where c1 = '$c1' and c ='00' and d ='00' and tahun = '$tahunAnggaran' ");
				
			}
			
			
			
														
		break;
		}		
		case 'Simpan':{
		
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			} 
			
			$c1 = explode(".",$urusan);
			$c1 = $c1[0];
			$c = explode(".",$bidang);
			$c = $c[0];
			$d = explode(".",$skpd);
			$d = $d[0];
			$e = explode(".",$unit);
			$e = $e[0];
			$e1 = explode(".",$subunit);
			$e1 = $e1[0];
			
			$kodeBarang = explode(".",$kodeBarang);
			$f = $kodeBarang[0];
			$g = $kodeBarang[1];
			$h = $kodeBarang[2];
			$i = $kodeBarang[3];
			$j = $kodeBarang[4];
			
			if(empty($q)){
				$err = "Pilih Kegiatan";
			}
			if($jumlah > $jumlahKebutuhanRiil && $jumlahKebutuhanRiil != '0' && !empty($jumlahKebutuhanOptimal) && !empty($jumlahKebutuhanMaksimal) ){
				$err = "Jumlah tidak dapat meleibihi kebutuhan RILL";
			}
			if(empty($kodeBarang)){
				$err = "Pilih Barang";
			}
			if(empty($jumlah)){
				$err = "Isi jumlah";
			}
			$username = $_COOKIE['coID'];
			$cekAda = mysql_num_rows(mysql_query("select * from temp_rkbmd_pengadaan_v3 where  f='$f' and g='$g' and h='$h' and i='$i' and j='$j' and user='$username'"));
			if($cekAda == 1){
				$err = "Barang sudah ada";
			}
			if($err == ''){
				$data  = array("c1" => $c1,
						   "c" => $c,
						   "d" => $d,
						   "e" => $e,
						   "e1" => $e1,
						   "bk" => $bk,
						   "ck" => $ck,
						   "dk" => $dk,
						   "p" => $p,
						   "q" => $q,
						   "f" => $f,
						   "g" => $g,
						   "h" => $h,
						   "i" => $i,
						   "j" => $j,
						   "satuan" => $satuan,
						   "jumlah" => $jumlah,
						   "catatan" => $keterangan,
						   "user" => $_COOKIE['coID']
				);
			
				$query = VulnWalkerInsert("temp_rkbmd_pengadaan_v3",$data);
			
				mysql_query($query);
					$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$bk' and ck = '$ck'  and p = '$p' and q !='0' ";
	$cmbKegiatan = cmbQuery('q', $q, $codeAndNameKegiatan,' disabled','-- KEGIATAN --'); 
			}
			
			
		 
			
			
			$content = array("q" => $cmbKegiatan);
		
		
		break;
	    }
		case 'CekAda':{
		
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			} 
			
			$c1 = explode(".",$urusan);
			$c1 = $c1[0];
			$c = explode(".",$bidang);
			$c = $c[0];
			$d = explode(".",$skpd);
			$d = $d[0];
			$e = explode(".",$unit);
			$e = $e[0];
			$e1 = explode(".",$subunit);
			$e1 = $e1[0];
			$username = $_COOKIE['coID'];
			mysql_query("delete from temp_rkbmd_pengadaan_v3 where user='$username'");

			$getAll = mysql_query("select * from view_rkbmd_v3 where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and jenis_form_modul ='PENYUSUNAN' and id_tahap='$this->idTahap' and j !='000' and id_jenis_pemeliharaan ='0' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1 ='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q' and status_validasi !='1'");
			if(mysql_num_rows($getAll) > 0 ){
				$tergantung = 'ada';
				
				while($rows = mysql_fetch_array($getAll)){
					foreach ($rows as $key => $value) { 
			 	 		$$key = $value; 
					}
					$data  = array("c1" => $c1,
						   "c" => $c,
						   "d" => $d,
						   "e" => $e,
						   "e1" => $e1,
						   "bk" => $bk,
						   "ck" => $ck,
						   "dk" => $dk,
						   "p" => $p,
						   "q" => $q,
						   "f1" => $f1,
						   "f2" => $f2,
						   "f" => $f,
						   "g" => $g,
						   "h" => $h,
						   "i" => $i,
						   "j" => $j,
						   "satuan" => $satuan_barang,
						   "jumlah" => $volume_barang,
						   "catatan" => $catatan,
						   "user" => $_COOKIE['coID']
				);
			
				$query = VulnWalkerInsert("temp_rkbmd_pengadaan_v3",$data);
				mysql_query($query);
				}
				
			}else{
				$tergantung = 'kosong';
			}
				
			
			
			
		 
			
			
			$content = array("status" => $tergantung, "query" => "select * from view_rkbmd_v3 where tahun ='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and jenis_form_modul ='PENYUSUNAN' and id_tahap='$this->idTahap' and j !='000' and id_jenis_pemeliharaan ='0' and c1='$c1' and c='$c' and d='$d' and e='$e' and e1 ='$e1' and bk='$bk' and ck='$ck' and p='$p' and q='$q'");
		
		
		break;
	    }
		case 'rincianpenerimaanDET':{
			$get= $this->rincianpenerimaanDET();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'subShowEdit':{
			foreach ($_REQUEST as $key => $value) { 
			  $$key = $value; 
			}
			
			$get = mysql_fetch_array(mysql_query("select * from temp_rkbmd_pengadaan_v3 where id = '$id'"));
			foreach ($get as $key => $value) { 
			  $$key = $value; 
			}
		    $concat2 = $f.".".$g.".".$h.".".$i.".".$j;
			$concat = $c1.".".$c.".".$d.".".$e.".".$e1.".".$f.".".$g.".".$h.".".$i.".".$j ; 
       	    $getKebutuhanMaksimal = mysql_fetch_array(mysql_query("select * from ref_std_kebutuhan_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
		 
		 
		   $getKebutuhanOptimal = mysql_fetch_array(mysql_query("select sum(jml_barang) as kebutuhanOptimal from buku_induk_v3 where concat(c1,'.',c,'.',d,'.',e,'.',e1,'.',f,'.',g,'.',h,'.',i,'.',j) = '$concat' and status_barang = '1' and (kondisi = '1' or kondisi = '2') "));	
		   $kebutuhanOptimal = $getKebutuhanOptimal['kebutuhanOptimal'];	
		   $getBarang = mysql_fetch_array( mysql_query("select * from ref_barang_v3 where  f=$f and g=$g and h=$h and i=$i and j=$j"));
		   $content = array('kodeBarang' => $concat2,'jumlah' => $jumlah, 'keterangan' => $catatan, 'jumlahKebutuhanOptimal' => $kebutuhanOptimal, 'jumlahKebutuhanMaksimal' => $getKebutuhanMaksimal['jumlah'], 'namaBarang' => $getBarang['nm_barang'], 'satuan' => $satuan, 'jumlahKebutuhanRill' => $getKebutuhanMaksimal['jumlah'] - $kebutuhanOptimal );
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
		 
	 }
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
   
  
   
   function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						setTimeout(function myFunction() {".$this->Prefix.".rincianpenerimaan()},1000);
						
					</script>";
		return

			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			"<script type='text/javascript' src='js/perencanaan_v3/rkbmd/rkbmd_ins.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/perencanaan_v3/rkbmd/popupProgram.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan/rkbmd/rkbmd.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/perencanaan_v3/rkbmd/popupBarang.js' language='JavaScript' ></script>
			
			
			".
			
			'
			  <link rel="stylesheet" href="datepicker/jquery-ui.css">
			  <script src="datepicker/jquery-1.12.4.js"></script>
			  <script src="datepicker/jquery-ui.js"></script>
			  <script>
			  $( function() {
			    $( "#tgl_dok" ).datepicker({ dateFormat: "dd-mm-yy" });
				
				$( "#datepicker2" ).datepicker({ dateFormat: "dd-mm-yy" });
			  } );
			  </script>
			'.
			$scriptload;
	}
	
	//form ==================================
 
	function setPage_HeaderOther(){
	return 
	"";
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01' width='20' >No.</th>
						<th class='th01' width='60'>KODE BARANG</th>
						<th class='th01' width='900'>NAMA BARANG</th>	
						<th class='th01'>JUMLAH</th>
						<th class='th01'>SATUAN</th>
						<th class='th01' width='100'>AKSI</th>	
					   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	foreach ($isi as $key => $value) { 
			  $$key = $value; 
			}
	$Koloms = array();
	$concat = $f.".".$g.".".$h.".".$i.".".$j;
	$getNamaBarang = mysql_fetch_array(mysql_query("select * from ref_barang_v3 where concat(f,'.',g,'.',h,'.',i,'.',j) = '$concat'"));
	$Koloms[] = array('align="center" width="20"', $no.'.' );
    $Koloms[] = array('align="center"', $f.".".$g.".".$h.".".$i.".".$j );
	$Koloms[] = array('align="left"', $getNamaBarang['nm_barang'] );
	$Koloms[] = array('align="right"', number_format($jumlah,0,',','.') );
	$Koloms[] = array('align="left"', $satuan );
	$aksi  = "<img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_ins_v3.hapus('$id');></img>&nbsp &nbsp <img src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_ins_v3.edit('$id');></img> ";
	$Koloms[] = array('align="center"', $aksi );
	return $Koloms;
	}
	
	function pageShow(){
		global $app, $Main; 
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		$cbid = $_REQUEST['pemasukan_cb'];
		 setcookie("coUrusanProgram", "", time()-3600);
		 setcookie('coBidangProgram', "", time()-3600);
		 unset($_COOKIE['coProgram']);
   		 
		return
		

		"<html>".
			$this->genHTMLHead().
			"<body >".
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".	
				"<tr height='34'><td>".					
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
					"
					<input type='hidden' name='ID_RENJA' value='".$_REQUEST['id']."' />
					<input type='hidden' name='ID_rkbmd' value='".$_REQUEST['ID_rkbmd']."' />".
					"<input type='hidden' name='databaru' id='databaru' value='".$_REQUEST['YN']."' />".
					"<input type='hidden' name='idubah' id='idubah' value='".$cbid[0]."' />".
										
						$this->setPage_Content().

					$form2.
					"</div></div>".
				"</td></tr>".
				"<tr><td height='29' >".	
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>".
			"</body>
		</html>"; 
	}	
	
	function genDaftarOpsi(){
	 global $Ref, $Main, $HTTP_COOKIE_VARS;

	$ID_RENJA = $_REQUEST['ID_RENJA'];
	$ID_rkbmd = $_REQUEST['ID_rkbmd'];
	
	$username = $_COOKIE['coID'];
	$Syntax_ambil_yang_udah_ada_plafon = "select * from tabel_anggaran_v3 where id_anggaran='$ID_RENJA'";
	$ambilSKPD = mysql_fetch_array(mysql_query($Syntax_ambil_yang_udah_ada_plafon));
	$c1  = $ambilSKPD['c1'];
	$c   = $ambilSKPD['c'];
	$d   = $ambilSKPD['d'];
	$e   = $ambilSKPD['e'];
	$e1  = $ambilSKPD['e1'];
	$tahunAnggaran = $ambilSKPD['tahun'];
	$tanggalSekarang = str_replace("-","",date("Y-m-d"));
	$jenisTransaksi = $ambilSKPD['jenis_anggaran'];
	$plafon = $ambilSKPD['jumlah']; 
	$tujuan = "Simpan()";
	if($plafon == ''){
		$disablePlafon = "";
	}else{
		$disablePlafon = "readonly";
		
	}

	$readOnlyJenisKegiatanPlus = "readonly";
	$readOnlyJenisKegiatanMin = "readonly";
	$disabledCariProgram = "";
	$arrayNameUrusan = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='00' and d='00' and e='00' and e1='000'"));
	$namaUrusan = $arrayNameUrusan['nm_skpd'];
	
	$arrayNameBidang = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='00' and e='00' and e1='000'"));
	$namaBidang = $arrayNameBidang['nm_skpd'];
	
	$arrayNameSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='00' and e1='000'"));
	$namaSKPD = $arrayNameSKPD['nm_skpd'];
	
	$arrayNameUNIT = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='$e' and e1='000'"));
	$namaUnit = $arrayNameSKPD['nm_skpd'];
	
	$arrayNameSUBUNIT = mysql_fetch_array(mysql_query("select * from ref_skpd where c1 ='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1'"));
	$namaSubUnit = $arrayNameSKPD['nm_skpd'];
	
 

	$selectedBK = $_REQUEST['bk'];
	$selectedCK = $_REQUEST['ck'];
	$selectedP = $_REQUEST['p'];
	$selecteQ = $_REQUEST['q'];
	$program = $_REQUEST['program'];
	$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$selectedBK' and ck = '$selectedCK'  and p = '$selectedP' and q ='$selecteQ' ";
	$cmbKegiatan = cmbQuery('q', $selecteQ, $codeAndNameKegiatan,' onchange=rkbmd_ins_v3.CekAda(); ','-- KEGIATAN --');  
	
	$cekGordon = mysql_num_rows(mysql_query("select * from temp_rkbmd_pengadaan_v3 where user = '$username'"));
	if($cekGordon > 0){
		$cokot = mysql_fetch_array(mysql_query("select * from temp_rkbmd_pengadaan_v3 where user = '$username'"));
		$selectedBK = $cokot['bk'];
		$selectedCK = $cokot['ck'];
		$selectedP = $cokot['p'];
		$selecteQ = $cokot['q'];
		$getNamaProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk='$selectedBK' and ck='$selectedCK' and p = '$selectedP' and q='0'"));
		$program = $getNamaProgram['nama'];
		$codeAndNameKegiatan = "select q, concat(q,'. ', nama) from ref_program where bk = '$selectedBK' and ck = '$selectedCK'  and p = '$selectedP' and q ='$selecteQ' ";
		$cmbKegiatan = cmbQuery('q', $selecteQ, $codeAndNameKegiatan,' disabled ','-- KEGIATAN --');  
		$disabledCariProgram = "disabled";
	}
	
	$TampilOpt =
			
			
	$vOrder=
			genFilterBar(
				array(
					$this->isiform(
						array(
							array(
								'label'=>'URUSAN',
								'name'=>'urusan',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c1.". ".$namaUrusan,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'BIDANG',
								'name'=>'bidang',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$c.'. '.$namaBidang,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'SKPD',
								'name'=>'skpd',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$d.'. '.$namaSKPD,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'UNIT',
								'name'=>'unit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e.'. '.$namaUnit,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							),
							array(
								'label'=>'SUB UNIT',
								'name'=>'subunit',
								'label-width'=>'200px;',
								'type'=>'text',
								'value'=>$e1.'. '.$namaSubUnit,
								'align'=>'left',
								'parrams'=>"style='width:600px;' readonly",
							)
						)
					)
				
				),'','','').
				genFilterBar(
				array(
					$this->isiform(
						array(
							
							array( 
								'label'=>'PROGRAM',
								'label-width'=>'200px;',
								'value'=>"<input type='hidden' name 'tahunAnggaran' id='tahunAnggaran' value='$tahunAnggaran'>
										 <input type='hidden' name='bk' id='bk' value='$selectedBK'>
										  <input type='hidden' name='ck' id='ck' value='$selectedCK'>
										  <input type='hidden' name='dk' id='dk' value='0'>
										  <input type='hidden' name='p' id='p' value='$selectedP'>
								<input type='text' name='program' value='".$program."' style='width:600px;' id='program' readonly>&nbsp
								<input type='button' value='Cari' id='findProgram' onclick ='rkbmd_ins_v3.CariProgram($ID_RENJA)'  title='Cari Program dan Kegiatan' $disabledCariProgram >"				
							),
							array( 
								'label'=>'KEGIATAN',
								'label-width'=>'200px;',
								'value'=>$cmbKegiatan			
							),
						)
					)
				
				),'','','').
			genFilterBar(
				array(
					"<span id='inputpenerimaanbarang' style='color:black;font-size:14px;font-weight:bold;'/>INPUT NAMA BARANG</span>",
					
				
				),'','','').	
				genFilterBar(
				array(
					$this->isiform(
						array(
							
							array(
									'label'=>'KODE BARANG',
									'label-width'=>'200px;',
									'value'=>"<input type='text' name='kodeBarang' onkeyup='cariBarang.pilBar2(this.value)' id='kodeBarang' placeholder='KODE BARANG' style='width:150px;' value='".$dt['kodebarangnya']."' readonly /> 
										<input type='text' name='namaBarang' id='namaBarang' placeholder='NAMA BARANG' style='width:445px;' readonly value='".$dt['nm_barang']."' />
										<input type='button' id='findBarang' value='CARI' onclick='rkbmd_ins_v3.CariBarang();'/>
									",
								),
							array(
									'label'=>'JUMLAH',
									'label-width'=>'200px;',
									'value'=>"<input type='text' name='jumlah' id='jumlah' onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style='width:50px;'  /> 
										 <input type='text' name='satuanBarang' id='satuanBarang' placeholder='SATUAN' style='width:250px;'   />
										
									",
								),
							array(  'name' => "jumlahKebutuhanRiil",
									'label'=>'JUMLAH KEBUTUHAN RIIL',
									'label-width'=>'200px;',
									'type' => 'text',
								    'parrams' => "style='width:50px' readonly"
									
								),
							array(  'name' => "jumlahKebutuhanMaksimal",
									'label'=>'JUMLAH KEBUTUHAN MAKSIMAL',
									'label-width'=>'200px;',
									'type' => 'text',
								    'parrams' => "style='width:50px' readonly"
									
								),
							array(      'name' => "jumlahKebutuhanOptimal",
									'label'=>'JUMLAH KEBUTUHAN OPTIMAL',
									'label-width'=>'200px;',
									'type' => 'text',
								    'parrams' => "style='width:50px' readonly"
									
								),
						array(      'name' => "keterangan",
									'label'=>'KETERANGAN',
									'label-width'=>'200px;',
									'type' => 'text',
								    'parrams' => "style='width:600px;'"
									
								),
						)
					).
					'<span id="pilCaraPerolehan"></span>'
				
				),'','','').
				genFilterBar(
					array(
					"
						<input type='hidden' name='".$this->Prefix."_idplh' id='".$this->Prefix."_idplh' value='$idplhnya' />
					<input type='hidden' name='refid_terimanya' id='refid_terimanya' value='".$dt['Id']."' />
					<input type='hidden' name='FMST_penerimaan_det' id='FMST_penerimaan_det' value='".$dt['FMST_penerimaan_det']."' />
					<table>
						<tr>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' href='javascript:rkbmd_ins_v3.$tujuan'> 
						<img src='images/administrator/images/save_f2.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> SIMPAN</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btcancel' href='javascript:rkbmd_ins_v3.closeTab()'> 
						<img src='images/administrator/images/cancel_f2.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> BATAL</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							<td><table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='finish' href='javascript:rkbmd_ins_v3.finish()'> 
						<img src='images/administrator/images/checkin.png' alt='BATAL' name='BATAL' width='32' height='32' border='0' align='middle' title='SIMPAN'> SELESAI</a> 
					</td> 
					</tr> 
					</tbody></table></td>
							
						</tr>".
					"</table>"
				
					
					
				),'','','').
				"<div id='rinciandatabarangnya'></div>"
							
			;
			
			
		return array('TampilOpt'=>$TampilOpt);
	}
	
	
	
	function isiform($value){
		$isinya = '';
		$tbl ='<table width="100%">';
		for($i=0;$i<count($value);$i++){
			if(!isset($value[$i]['align']))$value[$i]['align'] = "left";
			if(!isset($value[$i]['valign']))$value[$i]['valign'] = "top";
			
			if(isset($value[$i]['type'])){
				switch ($value[$i]['type']){
					case "text" :
						$isinya = "<input type='text' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "hidden" :
						$isinya = "<input type='hidden' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "password" :
						$isinya = "<input type='password' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					default:
						$isinya = $value[$i]['value'];
					break;					
				}
			}else{
				$isinya = $value[$i]['value'];
			}
			
			$tbl .= "
				<tr>
					<td width='".$value[$i]['label-width']."' valign='top'>".$value[$i]['label']."</td>
					<td width='10px' valign='top'>:<br></td>
					<td align='".$value[$i]['align']."' valign='".$value[$i]['valign']."'> $isinya</td>
				</tr>
			";		
		}
		$tbl .= '</table>';
		
		return $tbl;
	}
	
	
	
	function tampilarrnya($value, $width='12'){
		$gabung = '<div class="col-lg-'.$width.' form-horizontal well bs-component"><fieldset>';
		for($i=0;$i<count($value);$i++){
			
			if(isset($value[$i]['type'])){
				switch ($value[$i]['type']){
					case "text" :
						$isinya = "<input type='text' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "hidden" :
						$isinya = "<input type='hidden' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					case "password" :
						$isinya = "<input type='password' name='".$value[$i]['name']."' id='".$value[$i]['name']."' ".$value[$i]['parrams']." value='".$value[$i]['value']."' />";
					break;
					default:
						$isinya = $value[$i]['value'];
					break;					
				}
			}else{
				$isinya = $value[$i]['value'];
			}
			
			$gabung .='<div class="form-group">
				<label for="'.$value[$i]['name'].'" class="col-lg-'.$value[$i]['label-width'].' control-label" style="text-align:left;font-size:15px;font-weight: bold;">'.$value[$i]['label'].' </label>
				<label class="col-lg-1 control-label" style="text-align:center;">:</label>
      			<div class="col-lg-'.$value[$i]['isi-width'].'">
					'.$isinya.'
				</div>
    		</div>';
		}
		$gabung .= '</fieldset></div>';
		return $gabung;
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$username = $_COOKIE['coID'];
		$arrKondisi[] = "user = '$username'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " nama $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

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
	


	
	function buttonnya($js,$img,$name,$alt,$judul){
		return "<table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:$js'> 
						<img src='images/administrator/images/$img' alt='$alt' name='$name' width='32' height='32' border='0' align='middle' title='$judul'> $judul</a> 
					</td> 
					</tr> 
					</tbody></table> ";
	}
	
	
	
	
	
}
$rkbmd_ins_v3 = new rkbmd_ins_v3Obj();

$arrayResult = tahapPerencanaanV3($rkbmd_ins_v3->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$rkbmd_ins_v3->jenisForm = $jenisForm;
$rkbmd_ins_v3->nomorUrut = $nomorUrut;
$rkbmd_ins_v3->urutTerakhir = $nomorUrut;
$rkbmd_ins_v3->tahun = $tahun;
$rkbmd_ins_v3->jenisAnggaran = $jenisAnggaran;
$rkbmd_ins_v3->idTahap = $idTahap;
$rkbmd_ins_v3->username = $_COOKIE['coID'];
$rkbmd_ins_v3->wajibValidasi = $Main->wajibValidasi;
if($rkbmd_ins_v3->wajibValidasi == TRUE){
	$rkbmd_ins_v3->sqlValidasi = " and status_validasi ='1' ";
}else{
	$rkbmd_ins_v3->sqlValidasi = " ";
}

?>