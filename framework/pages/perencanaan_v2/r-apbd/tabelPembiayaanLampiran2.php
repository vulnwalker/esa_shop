<?php

class tabelPembiayaanLampiran2_v2Obj  extends DaftarObj2{	
	var $Prefix = 'tabelPembiayaanLampiran2_v2';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'view_r_apbd'; //daftar
	var $TblName_Hapus = 'view_r_apbd';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id_anggaran');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Sumber Dana.xls';
	var $Cetak_Judul = 'Sumber Dana';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'tabelPembiayaanLampiran2_v2Form'; 
	var $kdbrg = '';	
	var $pemisahID = ';';
	var $modul = "RKA-SKPD";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	var $currentTahap = "";
	var $namaTahapTerakhir = "";
	var $masaTerakhir = "";
	//buatview
	var $urutTerakhir = "";
	var $urutSebelumnya = "";
	var $jenisFormTerakhir = "";
	var $noUrutTerakhirapbd = "";		
	function setTitle(){
		return '';
	}
	function setMenuEdit(){
		return
			"";
	}
	function setMenuView(){
		return "";
	}
	function setTopBar(){
		return "";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		//$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		//$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		//$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";*/
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
			
		case 'windowshow':{
				$fm = $this->windowShow();
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
		
			
		case 'simpan':{
			$get= $this->simpan();
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
			 "<script src='js/skpd.js' type='text/javascript'></script>
			 <script type='text/javascript' src='js/perencanaan/r-apbd/".$this->Prefix.".js' language='JavaScript' ></script>
			 ".
			
			$scriptload;
	}
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_skpd = explode($this->pemisahID,$id);
		$c=$kode_skpd[0];	
		$d=$kode_skpd[1];
		$e=$kode_skpd[2];	
		$e1=$kode_skpd[3];
		if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where c='$c' and d='$d' and e='$e' and e1='$e1' ")
				) >0 )
			{ $errmsg = 'Gagal Hapus! SKPD Sudah ada di Buku Induk!';}
		return $errmsg;
	}
	//form ==================================
	
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		//$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		//$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		//$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$cek = $cbid[0];
				
		$this->form_idplh = $cbid[0];
		//$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		/*$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];		
		if(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.'00'.'.'.'00'.'.'.'00'.'.'.'000';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.'00'.'.'.'00'.'.'.'000';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.'.'00'.'.'.'000';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_barang']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.'.$fmSUBSUBKELOMPOK.'.'.'000';
		}	
		
		$ck=mysql_fetch_array(mysql_query("select * from ref_skpd where concat(f,'.',g,'.',h,'.',i,'.',j)='".$dt['kode_barang']."' order by persen1 desc limit 0,1"));
		if($ck['Id'] != ''){
			$dt['persen1'] = $ck['persen2'];
			$dt['readonly'] = 'readonly';
		}else{
			$dt['persen1'] = '';
			$dt['readonly'] = '';
		}
		
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}*/
	$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		//$dt['c'] = $c; 
	//	$dt['d'] = $d; 
		//$dt['e'] = $e; 
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
  	function setFormEdit(){
		/*$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//query ambil data ref_tambah_manfaat
		$aqry = "select * from ref_skpd where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
		$dt['readonly'] = '';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}*/
	$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		//$kode = explode(' ',$this->form_idplh);
		//$c=$kode[0];
		//$d=$kode[1];
		//$e=$kode[2];
		//$e1=$kode[3];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_sumber_dana WHERE nama= '".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		

			$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						800,
						500,
						'Pembiayaan',
						'',
						"<input type='button' value='Tutup' onclick ='".$this->Prefix.".windowClose()' >".
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
		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	$NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  
	   <th class='th01' width='100' rowspan='2' align='center'>KODE</th>
	   <th class='th01' width='900' rowspan='2' align='center'>URUSAN PEMERINTAH DAERAH</th>
	   <th class='th02' width='400' rowspan='1' colspan='3' align='center'>PEMBIAYAAN</th>
	   <th class='th01' width='100' rowspan='2' align='center'>SILPA TAB</th> 
	   </tr>
	  <tr>
	  <th class='th01' width='100' rowspan='1' align='center'>PENERIMAAN</th> 
	  <th class='th01' width='100' rowspan='1' align='center'>PENGELUARAN</th> 
	  <th class='th01' width='100' rowspan='1' align='center'>PEMBIAYAAN NETO</th> 
	  </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  			$$key = $value; 
	 }
	 if(!empty($this->idTahap)){
		$kondisiFilter = " and id_tahap = '$this->idTahap' ";
		if($this->jenisForm == "VALIDASI"){
			$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
		}
	}else{
		$getIdTahapTerakhir = mysql_fetch_array(mysql_query("select max(id_tahap) from tabel_anggaran where tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' and nama_modul = 'RKA-SKPD'"));
		$idTahapTerakhir = $getIdTahapTerakhir['max(id_tahap)'];
		$kondisiFilter = " and id_tahap = '$idTahapTerakhir' ";
		if($this->jenisFormTerakhir == "VALIDASI"){
			$kondisiFilter = $kondisiFilter." and status_validasi ='1' ";
		}
	}
	 $Koloms = array();
	 $Koloms[] = array('align="left"', $c1.".".$c.".".$d );
	 $getNamaSkpd = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$c1' and c='$c' and d='$d' "));
	
	 $Koloms[] = array('align="left"',$getNamaSkpd['nm_skpd']);
	 if($d =='00'){
	 	$getPenerimaan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where  rincian_perhitungan !='' and c1='$c1' and c='$c'  and k='6' and l='1' $kondisiFilter  "));
	 	$Koloms[] = array('align="right"',number_format($getPenerimaan['sum(jumlah_harga)'],2,',','.'));
		$getPengeluaran = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where  rincian_perhitungan !='' and c1='$c1' and c='$c'  and k='6' and l='2' $kondisiFilter  "));
	 	$Koloms[] = array('align="right"',number_format($getPengeluaran['sum(jumlah_harga)'],2,',','.'));
		$Koloms[] = array('align="right"',number_format( $getPenerimaan['sum(jumlah_harga)'] - $getPengeluaran['sum(jumlah_harga)'],2,',','.'));
	 }else{
	 	$getPenerimaan = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where  rincian_perhitungan !='' and c1='$c1' and c='$c' and d='$d' and k='6' and l='1' $kondisiFilter  "));
	 	$Koloms[] = array('align="right"',number_format($getPenerimaan['sum(jumlah_harga)'],2,',','.'));
		$getPengeluaran = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from tabel_anggaran where  rincian_perhitungan !='' and c1='$c1' and c='$c' and d='$d' and k='6' and l='2' $kondisiFilter  "));
	 	$Koloms[] = array('align="right"',number_format($getPengeluaran['sum(jumlah_harga)'],2,',','.'));
		$Koloms[] = array('align="right"',number_format( $getPenerimaan['sum(jumlah_harga)'] - $getPengeluaran['sum(jumlah_harga)'],2,',','.'));
	 }
	 $Koloms[] = array('align="right"','');

	 
	 return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 
		
	
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
 		$arrKondisi[] = "d !='00'";
		$getAllD = mysql_query("select *  from tabel_anggaran where  rincian_perhitungan !=''  ");
		while($rows = mysql_fetch_array($getAllD)){
			foreach ($rows as $key => $value) { 
		  			$$key = $value; 
			}
			if($k == '6'){
				$arrKondisi[] = "c1 = '$c1' and c='$c' and d='$d'";
			}
		}
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();

		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
}
$tabelPembiayaanLampiran2_v2 = new tabelPembiayaanLampiran2_v2Obj();
$arrayResult = VulnWalkerTahap_v2($tabelPembiayaanLampiran2_v2->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$tabelPembiayaanLampiran2_v2->jenisForm = $jenisForm;
$tabelPembiayaanLampiran2_v2->nomorUrut = $nomorUrut;
$tabelPembiayaanLampiran2_v2->tahun = $tahun;
$tabelPembiayaanLampiran2_v2->jenisAnggaran = $jenisAnggaran;
$tabelPembiayaanLampiran2_v2->idTahap = $idTahap;


if(empty($tabelPembiayaanLampiran2_v2->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_r_apbd "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_r_apbd where id_anggaran = '$maxAnggaran'"));
	/*$tabelPembiayaanLampiran2_v2->tahun = "select max(id_anggaran) as max from view_r_apbd where nama_modul = 'apbd'";*/
	$tabelPembiayaanLampiran2_v2->tahun  = $get2['tahun'];
	$tabelPembiayaanLampiran2_v2->jenisAnggaran = $get2['jenis_anggaran'];
	$tabelPembiayaanLampiran2_v2->urutTerakhir = $get2['no_urut'];
	$tabelPembiayaanLampiran2_v2->jenisFormTerakhir = $get2['jenis_form_modul'];
	$tabelPembiayaanLampiran2_v2->urutSebelumnya = $tabelPembiayaanLampiran2_v2->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$tabelPembiayaanLampiran2_v2->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$tabelPembiayaanLampiran2_v2->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$tabelPembiayaanLampiran2_v2->noUrutTerakhirapbd = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$tabelPembiayaanLampiran2_v2->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap_v2();
	$tabelPembiayaanLampiran2_v2->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$tabelPembiayaanLampiran2_v2->idTahap'"));
	$tabelPembiayaanLampiran2_v2->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$tabelPembiayaanLampiran2_v2->idTahap'"));
	$tabelPembiayaanLampiran2_v2->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$tabelPembiayaanLampiran2_v2->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$tabelPembiayaanLampiran2_v2->noUrutTerakhirapbd = $namaTahap['no_urut'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$tabelPembiayaanLampiran2_v2->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}
?>