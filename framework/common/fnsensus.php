<?php


//include('daftarobj.php');

class SensusObj extends DaftarObj2{
	var $Prefix = 'Sensus';
	var $SHOW_CEK = TRUE;
	var $elCurrPage="HalDefault";
	var $TblName = 'view2_sensus'; //daftar
	var $TblName_Hapus = 'sensus';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('jml_harga');//array('( jml_harga + tot_pelihara + tot_pengaman)');
	var $SumValue = array('jml_harga');// + tot_pelihara + tot_pengaman)');
	var $FieldSum_Cp1 = array( 9, 8, 8);//berdasar mode
	var $FieldSum_Cp2 = array( 6, 6, 6);	
	var $checkbox_rowspan = 1;
		
	//page 
	var $PageTitle = 'Sensus Barang';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $FormName = 'SensusPageForm';
	//form
	var $form_width = 650;
	var $form_height = 300;		
	//cetak 
	var $Cetak_Judul = 'DAFTAR SENSUS';
	var $Cetak_WIDTH = '30cm';
	//var $fileNameExcel='sensus_barang.xls';
	function setSumHal_query($Kondisi, $fsum){
	
		
		//return "select count(*) as cnt, sum(ifnull(jml_harga,0) + ifnull(tot_pelihara,0) + ifnull(tot_pengaman,0)) as sum_jml_harga from $this->TblName $Kondisi "; //echo $aqry;
		return "select count(*) as cnt, sum(ifnull(jml_harga,0) ) as sum_jml_harga from $this->TblName $Kondisi "; //echo $aqry;
		
	}
	
	function genSumHal($Kondisi){
		
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		
		/**$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		$arrKondisi[] = "(error is null or error ='') and (sesi is null or sesi ='') and f is not null ";
		
		$barcodeSensus_input = $_REQUEST['barcodeSensus_input'];
		if (!empty($barcodeSensus_input)) $arrKondisi[] = " idall2='$barcodeSensus_input' ";
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('SensusSkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('SensusSkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST('SensusSkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST('SensusSkpdfmSEKSI');
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH,  
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT, 
			$fmSEKSI 
		);
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){			
			case '1': $arrKondisi[] = " nm_barang like '%$fmPILCARIVALUE%'"; break;			
			case '2': $arrKondisi[] = " thn_perolehan = '$fmPILCARIVALUE'"; break;
		}
		$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
		if (!empty($fmFiltThnBuku))	$arrKondisi[] = " Year(tgl_buku) ='$fmFiltThnBuku' ";
		$fmFiltThnPerolehan = cekPOST('fmFiltThnPerolehan');
		if (!empty($fmFiltThnPerolehan))	$arrKondisi[] = " thn_perolehan ='$fmFiltThnPerolehan' ";
		$fmFiltThnSensus = cekPOST('fmFiltThnSensus');
		//if (!empty($fmFiltThnSensus))	$arrKondisi[] = " Year(tgl)  ='$fmFiltThnSensus' ";
		if (!empty($fmFiltThnSensus))	$arrKondisi[] = " tahun_sensus  ='$fmFiltThnSensus' ";
		$menu = $_REQUEST['menu'];
		if($menu == 'diusulkan') $arrKondisi[] = " kondisi=3 ";
		
		
		$menu = $_REQUEST['menu'];
		//if(empty($tidakada)) {		
		if($menu=='ada') $arrKondisi[] = " ada=1 ";		
		if($menu=='tidakada')  $arrKondisi[] = " ada=2 ";		
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		if($fmKONDBRG !=''){	$arrKondisi[] = "kondisi = $fmKONDBRG ";	}
		$kode_barang = $_REQUEST['kode_barang'];
		if($kode_barang != '') $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		//$getKond = $this->getDaftarOpsi2(1);
		//
		**/
		//*************** 
		
		$TblName = 'view2_sensus_tot';
		
		//buku induk ------------------------------------------------
		$aqry = "select count(*) as cnt, sum(jml_harga) as harga from buku_induk where id in( select idbi from $TblName $Kondisi );";$cek .= $aqry;
		/**$aqry = " select count(*) as cnt, sum(jml_harga) as harga from buku_induk where idawal in( select idawal from sensus ".
			" where (error is null or error ='') and (sesi is null or sesi ='') and f is not null  ) and ".
			$getKond['Kondisi'].';';$cek .= $aqry;
		**/
		$qry = mysql_query($aqry);
		if ($isi= mysql_fetch_array($qry)){	
			$jmlData = $isi['cnt'];
			
			$jmlTotal += $isi['harga'];
		}		
		//pemeliharaan ----------------------------------------------
		//$k1= $kondTglAkhir==''? '': "and tgl_pemeliharaan <='$kondTglAkhir' ";
		$aqry = "select sum(coalesce(biaya_pemeliharaan,0)) as tot from pemeliharaan where tambah_aset=1 ".
			" and idbi_awal in( select idawal from $TblName $Kondisi  );"; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$jmlTotal += $get['tot'];
		//pengaman ----------------------------------------------
		//$k1= $kondTglAkhir==''? '': "and tgl_pengamanan <='$kondTglAkhir' ";
		$aqry = "select sum(coalesce(biaya_pengamanan,0)) as tot from pengamanan where tambah_aset=1  ".
			" and idbi_awal in(select idawal from $TblName $Kondisi ) ; "; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$jmlTotal += $get['tot'];
		//penghapusan_sebagian ----------------------------------------------
		//$k1= $kondTglAkhir==''? '': "and tgl_penghapusan <='$kondTglAkhir' ";
		$aqry = "select sum(coalesce(harga_hapus,0)) as tot from penghapusan_sebagian where 1=1  ".
			" and idbi_awal in(select idawal from $TblName $Kondisi  ) ; "; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$jmlTotal -= $get['tot'];
		//koreksi ----------------------------------------------
		$aqry = "select sum(harga_baru - harga) as tot from t_koreksi ".
			" where idbi_awal in(select idawal from $TblName $Kondisi );"; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$jmlTotal += $get['tot'];
		//penilaian ---------------------------------------------		
		$aqry = "select sum(nilai_barang - nilai_barang_asal) as tot from penilaian ".
			" where idbi_awal in(select idawal from $TblName $Kondisi );"; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$jmlTotal += $get['tot'];
		
		
		$Sum = $this->genSum_setTampilValue(0, $jmlTotal);
		$vSum[] = $this->genSum_setTampilValue(0, $jmlTotal);
		$Hal = $this->setDaftar_hal($jmlData);		
		if ($this->WITH_HAL==FALSE) $Hal = ''; 			
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}
	
	
		
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$menu = $_REQUEST['menu'];
		//$ada = $_REQUEST['ada'];
		return		
			//$NavAtas.	
			//"<input type='text' id='ada' name='ada' value='$ada'>".
			"<input type='hidden' id='menu' name='menu' value='$menu'>".
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			"</div>".					
			//"<div id='{$this->Prefix}_cont_daftar' style='position:relative;float:left;' >".	
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			//"<div style='float:left;'></div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function setPage_TitleDaftar(){
		return 'Sensus Barang';
		/*$tipe = $_REQUEST['tipe'];
		if($tipe=='diusulkan'){
			return 'Barang Usulan Hapus';
		}else{
			return 'Sensus Barang';	
		}*/
	}	
	function setPage_TitlePage(){
		return 'Sensus Barang';		
		
	}
	function setTitle(){
		
		//return 'Sensus Barang';
		$menu = $_REQUEST['menu'];
		if($menu=='diusulkan'){
			return 'Sensus Barang (Usulan Hapus)';
		}else if($menu=='ada'){
			return 'Sensus Barang (Ada)';		
		}else if($menu=='tidakada'){
			return 'Sensus Barang (Tidak Ada)';		
		}else {
			return 'Sensus Barang';	
		}
		
	}
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		
		$menu = $_REQUEST['menu'];
		if($menu=='diusulkan'){
			$judul =  'Sensus Barang (Usulan Hapus)';
		}else if($menu=='ada'){
			$judul ='Sensus Barang (Ada)';		
		}else if($menu=='tidakada'){
			$judul ='Sensus Barang (Tidak Ada)';	
		}else{
			$judul = $this->Cetak_Judul;	
		}
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	function setPage_IconPage(){
		return 'images/penatausahaan_ico.gif';
	}	
	
	function setPage_HeaderOther(){
	global $Main;	
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];
		if($Pg == 'sensus'){
			$styleMenu = " style='color:blue;' ";	
		}
		$menu = $_REQUEST['menu'];
		switch ($menu){
			case 'belumcek' : $styleMenu2_1 = " style='color:blue;' "; break;
			case 'diusulkan': $styleMenu2_3 = " style='color:blue;' "; break;
			case 'laporan' 	: $styleMenu2_4 = " style='color:blue;' "; break;
			case 'kertaskerja' 	: $styleMenu2_5 = " style='color:blue;' "; break;
			case 'ada' :$styleMenu2_2 = " style='color:blue;' "; break;	
			case 'tidakada' :$styleMenu2_5 = " style='color:blue;' "; break;	
			//$styleMenu2_8 = " style='color:blue;'";
			//default: $styleMenu2_2 = " style='color:blue;' "; break;	
		}
		
		if($Pg=='SensusHasil')$styleMenu2_4 = " style='color:blue;' ";
		if($Pg=='SensusScan') $styleMenu2_9 = " style='color:blue;' ";

		$menu_pembukuan =
		$Main->MODUL_PEMBUKUAN?
		" <A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='AKUNTANSI' $styleMenu14>AKUNTANSI</a> ":'';
		/**
		$menu_peta = 
		$Main->MODUL_PETA ?
		 " <A href=\"pages.php?Pg=map&SPg=03\" title='Peta' target='_blank'>PETA</a> |" : '';				
		**/
		return 
			//"<tr height='22' valign='top'><td >".
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
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |
			$menu_peta
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |
			<A href=\"index.php?Pg=05&SPg=KIR\" $styleMenu1_14 title='Kartu Inventaris Ruangan'>KIR</a> |
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' $styleMenu>SENSUS</a> |
			$menu_pembukuan
			  &nbsp&nbsp&nbsp
			</td></tr></table>".
			
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin: 1 0 0 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!-- <A href=\"pages.php?Pg=sensus&menu=kertaskerja\" title='Kertas Kerja' $styleMenu2_5>Kertas Kerja</a> |  -->
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Belum Cek' $styleMenu2_1>Belum Cek</a> |
			<A href=\"pages.php?Pg=SensusScan\" title='Hasil Scan' $styleMenu2_9>Hasil Scan</a> |
			<A href=\"pages.php?Pg=SensusTidakTercatat\" title='Barang Tidak Tercatat' $styleMenu2_8>Tidak Tercatat</a> |
			
			<A href=\"pages.php?Pg=sensus&menu=ada\" title='Ada Barang' $styleMenu2_2>Ada</a>  |  
			<A href=\"pages.php?Pg=sensus&menu=tidakada\" title='Tidak Ada Barang' $styleMenu2_5>Tidak Ada</a>  |  
			<!--<A href=\"pages.php?Pg=sensus&menu=diusulkan\" title='Diusulkan Penghapusan' $styleMenu2_3>Diusulkan</a>  |  -->
			<A href=\"pages.php?Pg=SensusHasil2\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>  |
			<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   |
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>"
			;
			
	}
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						-->".
						$scriptload;
	}
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			
		$headerTable =
			"<tr>
					<th class=\"th02\" colspan='". ($cetak? "3": "4") ."'>Nomor</th>
					<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>
					<th class=\"th01\" rowspan='2'>Bahan/ <br>Ukuran</th>
					
					<th class=\"th01\" rowspan='2'>Tahun/<br>Cara Perolehan</th>
					<th class=\"th01\" rowspan='2'>$tampilHeaderHarga </th>
					
					
					<!--<th class=\"th01\" rowspan='2'>Kondisi Awal Barang </th>-->					
					<th class=\"th01\" rowspan='2'>Kondisi Saat Sensus </th>
					<th class=\"th01\" rowspan='2'>Gedung/<br>Ruang </th>
					<th class=\"th01\" rowspan='2'>Penanggung Jawab Barang/<br>Pengguna/Kuasa Pengguna Barang/<br>
						Pengurus Barang/Pembantu/<br>Status Penguasaan</th>
					<th class=\"th01\" rowspan='2'>Catatan</th>
					
					<th class=\"th01\" rowspan='2' width='70'>Tahun/<br>Tgl. Sensus/<br>Petugas Sensus</th>
					
					
					
					</tr>
					<tr>
					<th class=\"th01\">No.</th>				
					$Checkbox
					<th class=\"th01\">Kode <br>Barang</th>
					<th class=\"th01\">Reg.</th>
					<th class=\"th01\"  width=\"100\">Nama / Jenis Barang</th>
					<th class=\"th01\"  width=\"100\">Merk / Tipe / Alamat</th>
					<th class=\"th01\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
					
					</tr>
					$tambahgaris
					";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main;
		
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];	//$kdKelBarang = $isi['f'].$isi['g']."00";
		$AsalUsul = $isi['asal_usul'];	
		$ISI5 = "";	$ISI6 = "";	$ISI7 = "";	$ISI10 = "";
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		
		//**************************************************************
		
		if ($isi['f'] == "01" || $isi['f'] == "02" || $isi['f'] == "03" || $isi['f'] == "04" || $isi['f'] == "05" || $isi['f'] == "06") {
			$KondisiKIB = 
			" where 
			a1= '{$isi['a1']}' and 
			a = '{$isi['a']}' and 
			b = '{$isi['b']}' and 
			c = '{$isi['c']}' and 
			d = '{$isi['d']}' and 
			e = '{$isi['e']}' and 
			e1 = '{$isi['e1']}' and 
			f = '{$isi['f']}' and 
			g = '{$isi['g']}' and 
			h = '{$isi['h']}' and 
			i = '{$isi['i']}' and 
			j = '{$isi['j']}' and 
			noreg = '{$isi['noreg']}' and 
			tahun = '{$isi['tahun']}' ";
		}
		if ($isi['f'] == "01") {//KIB A
			$QryKIB_A = mysql_query("select * from kib_a $KondisiKIB limit 0,1");
			while ($isiKIB_A = mysql_fetch_array($QryKIB_A)) {
				$alm = '';
				$alm .= ifempty($isiKIB_A['alamat'],'-');		
				$alm .= $isiKIB_A['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_A['alamat_kel'] : '';
				$alm .= $isiKIB_A['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_A['alamat_kec'] : '';
				$alm .= $isiKIB_A['alamat_kota'] != ''? '<br>'.$isiKIB_A['alamat_kota'] : '';
				$ISI5 = $alm;				
				$ISI6 = "{$isiKIB_A['sertifikat_no']}";  
				$ISI15 = "{$isiKIB_A['ket']}";
				$ISI10 = number_format($isiKIB_A['luas'],2,',','.');
			}
		}
		if ($isi['f'] == "02") {//KIB B;
			$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
			while ($isiKIB_B = mysql_fetch_array($QryKIB_B)) {
				$ISI5 = "{$isiKIB_B['merk']}";
				$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
				$ISI7 = "{$isiKIB_B['bahan']}";							
				$ISI15 = "{$isiKIB_B['ket']}";
			}
		}
		if ($isi['f'] == "03") {//KIB C;
			$QryKIB_C = mysql_query("select * from kib_c  $KondisiKIB limit 0,1");
			while ($isiKIB_C = mysql_fetch_array($QryKIB_C)) {
				$alm = '';
				$alm .= ifempty($isiKIB_C['alamat'],'-');		
				$alm .= $isiKIB_C['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_C['alamat_kel'] : '';
				$alm .= $isiKIB_C['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_C['alamat_kec'] : '';
				$alm .= $isiKIB_C['alamat_kota'] != ''? '<br>'.$isiKIB_C['alamat_kota'] : '';
				$ISI5 = $alm;
				
				$ISI6 = "{$isiKIB_C['dokumen_no']}";
				$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan'] - 1][1];
				$ISI15 = "{$isiKIB_C['ket']}";
			}
		}
		if ($isi['f'] == "04") {//KIB D;
			$QryKIB_D = mysql_query("select * from kib_d  $KondisiKIB limit 0,1");
			while ($isiKIB_D = mysql_fetch_array($QryKIB_D)) {
				$alm = '';
				$alm .= ifempty($isiKIB_D['alamat'],'-');		
				$alm .= $isiKIB_D['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_D['alamat_kel'] : '';
				$alm .= $isiKIB_D['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_D['alamat_kec'] : '';
				$alm .= $isiKIB_D['alamat_kota'] != ''? '<br>'.$isiKIB_D['alamat_kota'] : '';
				$ISI5 = $alm;
				$ISI6 = "{$isiKIB_D['dokumen_no']}";
				$ISI15 = "{$isiKIB_D['ket']}";
			}
		}
		if ($isi['f'] == "05") {//KIB E;
			$QryKIB_E = mysql_query("select * from kib_e  $KondisiKIB limit 0,1");
			while ($isiKIB_E = mysql_fetch_array($QryKIB_E)) {
				$ISI7 = "{$isiKIB_E['seni_bahan']}";
				$ISI15 = "{$isiKIB_E['ket']}";
			}
		}
		if ($isi['f'] == "06") {//KIB F;
			$sQryKIB_F = "select * from kib_f  $KondisiKIB limit 0,1";
			$QryKIB_F = mysql_query($sQryKIB_F);
			//echo "<br>qrykibf= $sQryKIB_F";
			while ($isiKIB_F = mysql_fetch_array($QryKIB_F)) {
				$alm = '';
				$alm .= ifempty($isiKIB_F['alamat'],'-');		
				$alm .= $isiKIB_F['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_F['alamat_kel'] : '';
				$alm .= $isiKIB_F['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_F['alamat_kec'] : '';
				$alm .= $isiKIB_F['alamat_kota'] != ''? '<br>'.$isiKIB_F['alamat_kota'] : '';
				$ISI5 = $alm;
				$ISI6 = "{$isiKIB_F['dokumen_no']}";
				$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan'] - 1][1];
				$ISI15 = "{$isiKIB_F['ket']}";
			}
		}
		
		//
		//*******************************************************
		
		$ISI5 = !empty($ISI5) ? $ISI5 : "-";
		$ISI6 = !empty($ISI6) ? $ISI6 : "-";
		$ISI7 = !empty($ISI7) ? $ISI7 : "-";
		$ISI10 = !empty($ISI10) ? $ISI10 : "-";
		$ISI12 = !empty($ISI12) ? $ISI12 : "-";
		$ISI15 = !empty($ISI15) ? $ISI15 : "-";
		if (($fmCariComboField != 'ket')||($fmCariComboField == 'ket' && stripos( $ISI15, $fmCariComboIsi) !== false  )){						
			if ($sort1 >= 1){			
				$ISI15 	= $ISI15.' /<br>'.TglInd($isi['tgl_buku']).' /<br>'.$isi['tgl_update'] ;	
			}else{
				$ISI15 	= $ISI15.' /<br>'.TglInd($isi['tgl_buku']);			
			}		
			//$ISI15 .= $isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'];	//$ISI15 .= tampilNmSubUnit($isi);//echo"<br>$ISI15";	
			//$no++;
			$jmlTotalHargaDisplay += $isi['jml_harga'];
			$clRow = $no % 2 == 0 ?"row1":"row0";
			$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');					
			
			$KondisiAwal = 'kondisi awal';
			$KondisiSensus = 'kondisi sensus';
			
			$Penanggung = $isi['penanggung'];
			$Pemegang = $isi['pemegang'];
			$Pemegang2 = $isi['pemegang2'];
			$Petugas = $isi['petugas'];//$isi['uid'];
			
			$Koloms[] = array('align=right', $no.'.' );
			if ($Mode == 1) $Koloms[] = array("align='center'  ", $TampilCheckBox);
			$Koloms[] = array('align=center',$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']."<br>".$isi['idbi']."<br>".$isi['idbi_awal'] );			
			$Koloms[] = array('align=center', $isi['noreg']);			
			$Koloms[] = array('align=left', $isi['nm_barang']);
			$Koloms[] = array('align=left', $ISI5);
			$Koloms[] = array('align=left', $ISI6);
			$Koloms[] = array('align=left', $ISI7.'<br>'.$ISI10);
			
			$Koloms[] = array('align=center', $isi['thn_perolehan'].'<br>'.$Main->AsalUsul[$AsalUsul-1][1]);
			
			$Koloms[] = array('align=right', $tampilHarga);
			//$Koloms[] = array('align=center', $Main->KondisiBarang[ $isi['kondisi_awal'] -1][1] );
			$Koloms[] = array('align=center', $Main->KondisiBarang[ $isi['kondisi'] -1][1] );
			$Koloms[] = array('align=left', $isi['nm_gedung'].'<br>'.$isi['nm_ruang']);
			$Koloms[] = array('align=left', $Pemegang2.'<br>'.$Penanggung.'<br>'.$Pemegang.
				'<br>'.$Main->arStatusPenguasaan[$isi['status_penguasaan']-1][1]
				);
			$Koloms[] = array('align=left', $isi['catatan']);
			$Koloms[] = array('align=left', $isi['tahun_sensus'].'<br>'. TglJamInd($isi['tgl']).'<br>'.$Petugas);
			
		}
		
		return $Koloms;
	}
	
	
	function setDaftar_after_getrow($list_row, $isi, $no, $Mode, $TampilCheckBox,$RowAtr, $KolomClassStyle){
		$cetak = $Mode==2  || $Mode== 3;
		$cbxDlmRibu = FALSE;
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";	
		if($Mode == 1) $clRow = $no % 2 == 0 ?"row1":"row0"; 
		//$det = Penatausahaan_GetListDet($isi['idawal'], 'sensusAda', $isi['jml_harga'],$cetak, $cbxDlmRibu,$clRow, $clGaris, $TampilCheckBox,'2012-12-31');
		$det = Penatausahaan_GetListDet2($isi['idawal'], 'sensusAda', $isi['jml_harga'],$cetak, $cbxDlmRibu,$clRow, $clGaris, $TampilCheckBox,'2012-12-31');
		$list_row .= $det['ListData'];		
		$this->SumValue[0] += $det['tot_rehab'];
		
		return $list_row;
	}
	
	function genDaftarOpsi(){
		global $Main;
		
		//data cari ----------------------------
		switch($_GET['SPg']){			
			case'04': case'06': case'07': case'09' :{
				$arrCari = array(
					array('1','Nama Barang'),
					array('2','Tahun Perolehan'),					
					array('3','Letak/Alamat'),
					array('4','Keterangan'),			
				);
				break;
			};
			default:{
				$arrCari = array(
					array('1','Nama Barang'),
					array('2','Tahun Perolehan')
				);
				break;
			}
		}
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$kode_barang = $_REQUEST['kode_barang'];
		$jmlPerHal = $_REQUEST['jmlPerHal'];
		if($jmlPerHal==''|| $jmlPerHal==0) $jmlPerHal = $Main->PagePerHal;
				
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Perolehan'),
			array('2','Keadaan Barang'),
			array('3','Tahun Buku')
		);
		
		
		//tampil -------------------------------
		$menu = $_REQUEST['menu'];
		if($menu=='ada'){
			$filtKondBrg = cmb2D_v2('fmKONDBRG',$fmKONDBRG, $Main->KondisiBarang,'','Kondisi Barang','');
		}
		$vkode_barang =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 ' > Kode Barang </div>".				
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				"<input id='kode_barang' name='kode_barang' value='$kode_barang' title='Cari Kode Barang (ex: 01.02.01.01.01)'>".
			//"";
			"</div>";
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3('SensusSkpd') . "
			</td>
			<td style='padding:6'>
				" . 
				//$Main->ListData->labelbarang .
				//"<script>barcode.loading();</script>".
				"<span style='color:red'>BARCODE</span><br>".				
				"<input type='TEXT' value='' 
					id='barcodeSensus_input' name='barcodeSensus_input'
					style='font-size:24;width: 379px;' 
					size='32' maxlength='32'
					
				><span id='barcodeSensus_msg' name='barcodeSensus_msg' ></span> ". //onkeyup='inputBarcode(this)'
					
					//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>".
				
			"</td>
			</tr></table>".	
			genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
			genFilterBar(
				array(	
				"<div style='float:left;padding: 0 4 0 0;height:22;'>".
					'Tampilkan : '.	
					genComboBoxQry(
						'fmFiltThnSensus',
						$fmFiltThnSensus,
						//"select year(tgl)as thnsensus from sensus where (sesi='' or sesi is null) group by thnsensus order by thnsensus desc ",
						"select tahun_sensus from sensus  where (sesi='' or sesi is null) and (error='' or error is null) group by tahun_sensus order by tahun_sensus desc",
						'tahun_sensus', 
						'tahun_sensus',
						'Semua Thn. Sensus'
					).
					genComboBoxQry(
						'fmFiltThnBuku',
						$fmFiltThnBuku,
						//"select year(tgl_buku)as thnbuku from view2_sensus where (sesi='' or sesi is null) group by thnbuku order by thnbuku desc",
						"select year(bb.tgl_buku)as thnbuku from sensus aa ".
						" left join buku_induk bb on aa.idbi=bb.id ".
						"where (aa.sesi='' or aa.sesi is null) group by thnbuku ".
						"order by thnbuku desc",
						'thnbuku', 
						'thnbuku',
						'Semua Thn. Buku'
					).
					genComboBoxQry('fmFiltThnPerolehan',$fmFiltThnPerolehan,
						//"select thn_perolehan from view2_sensus where (sesi='' or sesi is null) group by thn_perolehan  order by thn_perolehan desc",
						" select bb.thn_perolehan from sensus aa ".
						" left join buku_induk bb on aa.idbi=bb.id ".
						" where (aa.sesi='' or aa.sesi is null) ".
						" group by bb.thn_perolehan  order by bb.thn_perolehan desc",
						'thn_perolehan', 'thn_perolehan','Semua Thn. Perolehan'
					).
					$filtKondBrg.
					"</div>".
					$Main->batas.
					$vkode_barang
				),$this->Prefix.".refreshList(true)",FALSE
			).
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC1' name='fmDESC1' value='1'>Desc.".
					cmbArray('fmORDER2',$fmORDER2,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC2' name='fmDESC2' value='1'>Desc.".
					cmbArray('fmORDER3',$fmORDER3,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC3' name='fmDESC3' value='1'>Desc.".
					" &nbsp;&nbsp;&nbsp;Baris per halaman <input type='text' id='jmlPerHal' name='jmlPerHal' size='4' value='$jmlPerHal'>"
				),				
				$this->Prefix.".refreshList(false)");//.
			//"<input type='text' id='ada' name='ada' value='$ada'>";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi				
		/*$arrKondisi[] = ' a1= '. $Main->DEF_KEPEMILIKAN;
		$arrKondisi[] = ' a= '.  $Main->Provinsi[0];
		$arrKondisi[] = ' b= '. '00';
		$arrKondisi[] = ' c= '. cekPOST('fmSKPD'); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
		$arrKondisi[] = ' d= '. cekPOST('fmUNIT'); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
		$arrKondisi[] = ' e= '.  cekPOST('fmSUBUNIT');	
		*/
		$arrKondisi[] = "(error is null or error ='') and (sesi is null or sesi ='') and f is not null ";
		$barcodeSensus_input = $_REQUEST['barcodeSensus_input'];
		if (!empty($barcodeSensus_input)) $arrKondisi[] = " idall2='$barcodeSensus_input' ";
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('SensusSkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('SensusSkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST('SensusSkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST('SensusSkpdfmSEKSI');
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){			
			case '1': $arrKondisi[] = " nm_barang like '%$fmPILCARIVALUE%'"; break;			
			case '2': $arrKondisi[] = " thn_perolehan = '$fmPILCARIVALUE'"; break;
		}
		$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
		if (!empty($fmFiltThnBuku))	$arrKondisi[] = " Year(tgl_buku) ='$fmFiltThnBuku' ";
		$fmFiltThnPerolehan = cekPOST('fmFiltThnPerolehan');
		if (!empty($fmFiltThnPerolehan))	$arrKondisi[] = " thn_perolehan ='$fmFiltThnPerolehan' ";
		$fmFiltThnSensus = cekPOST('fmFiltThnSensus');
		//if (!empty($fmFiltThnSensus))	$arrKondisi[] = " Year(tgl)  ='$fmFiltThnSensus' ";
		if (!empty($fmFiltThnSensus))	$arrKondisi[] = " tahun_sensus  ='$fmFiltThnSensus' ";
		$menu = $_REQUEST['menu'];
		if($menu == 'diusulkan') $arrKondisi[] = " kondisi=3 ";
		
		//ada/tidak
		/*$tidakada = $_REQUEST['tidakada'];
		if(empty($tidakada)){
			$ada = 1;	
		}else{
			$ada = 0;//$_REQUEST['ada'];
		}*/
		//$tidakada = $_REQUEST['tidakada'];
		$menu = $_REQUEST['menu'];
		//if(empty($tidakada)) {		
		if($menu=='ada') $arrKondisi[] = " ada=1 ";
		
		if($menu=='tidakada')  $arrKondisi[] = " ada=2 ";
		
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		if($fmKONDBRG !=''){	$arrKondisi[] = "kondisi = $fmKONDBRG ";	}
		$kode_barang = $_REQUEST['kode_barang'];
		if($kode_barang != '') $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$fmDESC2 = $_POST['fmDESC2'];
		$AscDsc2 = $fmDESC2 == 1? 'desc' : '';
		$fmORDER2 = $_POST['fmORDER2'];
		$fmDESC3 = $_POST['fmDESC3'];
		$AscDsc3 = $fmDESC3 == 1? 'desc' : '';
		$fmORDER3 = $_POST['fmORDER3'];
		
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc1 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc1 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc1 "; break;			
		}
		switch($fmORDER2){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc2 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc2 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc2 "; break;			
		}
		switch($fmORDER3){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc3 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc3 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc3 "; break;			
		}
			
		/*
		//limit --------------------------------------
		$HalDefault=cekPOST($this->elCurrPage,1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		*/
		$this->pagePerHal =  $_REQUEST['jmlPerHal'];
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function getDaftarOpsi2($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi				
		/*$arrKondisi[] = ' a1= '. $Main->DEF_KEPEMILIKAN;
		$arrKondisi[] = ' a= '.  $Main->Provinsi[0];
		$arrKondisi[] = ' b= '. '00';
		$arrKondisi[] = ' c= '. cekPOST('fmSKPD'); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
		$arrKondisi[] = ' d= '. cekPOST('fmUNIT'); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
		$arrKondisi[] = ' e= '.  cekPOST('fmSUBUNIT');	
		*/
		//$arrKondisi[] = "(error is null or error ='') and (sesi is null or sesi ='') and f is not null ";
		$barcodeSensus_input = $_REQUEST['barcodeSensus_input'];
		if (!empty($barcodeSensus_input)) $arrKondisi[] = " idall2='$barcodeSensus_input' ";
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('SensusSkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('SensusSkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST('SensusSkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST('SensusSkpdfmSEKSI');
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){			
			case '1': $arrKondisi[] = " nm_barang like '%$fmPILCARIVALUE%'"; break;			
			case '2': $arrKondisi[] = " thn_perolehan = '$fmPILCARIVALUE'"; break;
		}
		$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
		if (!empty($fmFiltThnBuku))	$arrKondisi[] = " Year(tgl_buku) ='$fmFiltThnBuku' ";
		$fmFiltThnPerolehan = cekPOST('fmFiltThnPerolehan');
		if (!empty($fmFiltThnPerolehan))	$arrKondisi[] = " thn_perolehan ='$fmFiltThnPerolehan' ";
		$fmFiltThnSensus = cekPOST('fmFiltThnSensus');
		//if (!empty($fmFiltThnSensus))	$arrKondisi[] = " Year(tgl)  ='$fmFiltThnSensus' ";
		if (!empty($fmFiltThnSensus))	$arrKondisi[] = " tahun_sensus  ='$fmFiltThnSensus' ";
		$menu = $_REQUEST['menu'];
		if($menu == 'diusulkan') $arrKondisi[] = " kondisi=3 ";
		
		//ada/tidak
		/*$tidakada = $_REQUEST['tidakada'];
		if(empty($tidakada)){
			$ada = 1;	
		}else{
			$ada = 0;//$_REQUEST['ada'];
		}*/
		//$tidakada = $_REQUEST['tidakada'];
		$menu = $_REQUEST['menu'];
		//if(empty($tidakada)) {		
		if($menu=='ada') $arrKondisi[] = " ada=1 ";
		
		if($menu=='tidakada')  $arrKondisi[] = " ada=2 ";
		
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		if($fmKONDBRG !=''){	$arrKondisi[] = "kondisi = $fmKONDBRG ";	}
		$kode_barang = $_REQUEST['kode_barang'];
		if($kode_barang != '') $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$fmDESC2 = $_POST['fmDESC2'];
		$AscDsc2 = $fmDESC2 == 1? 'desc' : '';
		$fmORDER2 = $_POST['fmORDER2'];
		$fmDESC3 = $_POST['fmDESC3'];
		$AscDsc3 = $fmDESC3 == 1? 'desc' : '';
		$fmORDER3 = $_POST['fmORDER3'];
		
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc1 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc1 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc1 "; break;			
		}
		switch($fmORDER2){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc2 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc2 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc2 "; break;			
		}
		switch($fmORDER3){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc3 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc3 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc3 "; break;			
		}
			
		/*
		//limit --------------------------------------
		$HalDefault=cekPOST($this->elCurrPage,1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		*/
		$this->pagePerHal =  $_REQUEST['jmlPerHal'];
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		//if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
		
	
	function setFormBaru(){
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		$json = TRUE;	//$ErrMsg = 'tes';
		$form_name = $this->Prefix.'_form';		
		
		$fmSKPD = $_REQUEST[$this->Prefix.'SkpdfmSKPD' ];
		$fmUNIT = $_REQUEST[$this->Prefix.'SkpdfmUNIT' ];
		$fmSUBUNIT = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT' ];
		$fmSEKSI = $_REQUEST[$this->Prefix.'SkpdfmSEKSI' ];
		//utk baru dari daftar belum cek
		if ($fmSKPD=='') $fmSKPD = $_REQUEST['fmSKPD'];		
		if ($fmUNIT=='') $fmUNIT = $_REQUEST['fmUNIT'];		
		if ($fmSUBUNIT=='') $fmSUBUNIT = $_REQUEST['fmSUBUNIT'];		
		if ($fmSEKSI=='') $fmSEKSI = $_REQUEST['fmSEKSI'];		
				
		if ($err=='' && ($fmSKPD == '' || $fmSKPD == '00' ) ) $err = "Bidang Belum Diisi!";
		if ($err=='' && ($fmUNIT == '' || $fmUNIT == '00' ) ) $err = "SKPD Belum Diisi!";
		if ($err=='' && ($fmSUBUNIT == '' || $fmSUBUNIT == '00' ) ) $err = "UNIT Belum Diisi!";
		if ($err=='' && ($fmSEKSI == '' || $fmSEKSI == '00'  || $fmSEKSI == '000' ) ) $err = "SUB UNIT Belum Diisi!";
		
		$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,'','',$fmSEKSI)."</div>";
					
		$sesi = gen_table_session('sensus','uid');
		$sensus_bt= $Main->MODUL_SENSUS_MANUAL ? "<input type='button' value='Tambah Barang' onclick ='SensusTmp.entryBarang()' >" : "";
		$this->form_menubawah =	$sensus_bt.		
			
			"<input type='button' value='Ada/Tidak Ada' onclick ='SensusTmp.entryAda()' >".
			"<input type='button' value='Penanggung Jawab Barang' onclick ='SensusTmp.pilihPegang2()' >".
			"<input type='button' value='Pengguna/Kuasa Pengguna Barang' onclick ='SensusTmp.pilihPenanggung()' >".
			"<input type='button' value='Pengurus Barang/Pembantu' onclick ='SensusTmp.pilihPegang()' >".
			"<input type='button' value='Kondisi' onclick ='SensusTmp.pilihKondisi()' >".
			"<input type='button' value='Ruang' onclick ='SensusTmp.pilihRuang()' >".
			"<input type='button' value='Catatan' onclick ='SensusTmp.entryCatatan()' >".			
			
			"&nbsp;&nbsp;&nbsp;&nbsp;".
			"<input type='button' value='Hapus' onclick ='SensusTmp.Hapus()' >".
			"&nbsp;&nbsp;&nbsp;&nbsp;".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBaru()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Batal()' >";
		$form = //centerPage(
			"<form name='$form_name' id='$form_name' method='post' action=''>".
			createDialog(
				$form_name.'_div', 
				$FormContent,
				850,
				470,
				'Sensus Barang - Baru',
				'',
				$this->form_menubawah.
				"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
				"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
				"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
				,//$this->setForm_menubawah_content(),
				$this->form_menu_bawah_height,'',1
			).
			"</form>";
		//);		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormEdit2(){
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		$json = TRUE;	//$ErrMsg = 'tes';
		$form_name = $this->Prefix.'_form';		
		
		/*
		$fmSKPD = $_REQUEST['SensusScanSkpdfmSKPD' ];
		$fmUNIT = $_REQUEST['SensusScanSkpdfmUNIT' ];
		$fmSUBUNIT = $_REQUEST['SensusScanSkpdfmSUBUNIT' ];
		
		if ($fmSKPD=='') $fmSKPD = $_REQUEST['fmSKPD'];		
		if ($fmUNIT=='') $fmUNIT = $_REQUEST['fmUNIT'];		
		if ($fmSUBUNIT=='') $fmSUBUNIT = $_REQUEST['fmSUBUNIT'];		
		*/
				
		
		$SensusScan_cb  = $_REQUEST['SensusScan_cb'];
		
		$this->form_idplh = $SensusScan_cb[0];
		
		//ambil data sensus scan
		$aqry = "select * from sensus_scan where id ='$this->form_idplh' ";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT)."</div>";
		$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($dt['c'], $dt['d'], $dt['e'], $dt['tahun_sensus'], $dt['tgl'],$dt['e1'])."</div>";
					
		$sesi = gen_table_session('sensus','uid');
		$sensus_bt= $Main->MODUL_SENSUS_MANUAL ? "<input type='button' value='Tambah Barang' onclick ='SensusTmp.entryBarang()' >" : "";		
		$this->form_menubawah =	$sensus_bt.
			"<input type='button' value='Ada/Tidak Ada' onclick ='SensusTmp.entryAda()' >".
			"<input type='button' value='Penanggung Jawab Barang' onclick ='SensusTmp.pilihPegang2()' >".
			"<input type='button' value='Pengguna/Kuasa Pengguna Barang' onclick ='SensusTmp.pilihPenanggung()' >".
			"<input type='button' value='Pengurus Barang/Pembantu' onclick ='SensusTmp.pilihPegang()' >".
			"<input type='button' value='Kondisi' onclick ='SensusTmp.pilihKondisi()' >".
			"<input type='button' value='Ruang' onclick ='SensusTmp.pilihRuang()' >".
			"<input type='button' value='Catatan' onclick ='SensusTmp.entryCatatan()' >".			
			
			"&nbsp;&nbsp;&nbsp;&nbsp;".
			"<input type='button' value='Hapus' onclick ='SensusTmp.Hapus()' >".
			"&nbsp;&nbsp;&nbsp;&nbsp;".
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBaru()' >".
			"<input type='button' value='Tutup' onclick ='".$this->Prefix.".Batal()' >";
		$form = //centerPage(
			"<form name='$form_name' id='$form_name' method='post' action=''>".
			createDialog(
				$form_name.'_div', 
				$FormContent,
				850,
				470,
				'Sensus Barang - Edit',
				'',
				$this->form_menubawah.
				"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
				"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
				"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
				,//$this->setForm_menubawah_content(),
				$this->form_menu_bawah_height,'',1
			).
			"</form>";
		//);		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormEdit(){		
		global $Main;
		$cek = ''; $err=''; $content='';// $json=FALSE;
		$form = '';
				
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		//$this->row_params = ' valign=top';
		
		$old = mysql_fetch_array(mysql_query(
			"select * from view2_sensus where id='".$cbid[0]."' "
		)); 
		
		
		//set cookie skpd sesuai barang
		setcookie('cofmSKPD', $old['c']);
		setcookie('cofmUNIT', $old['d']);
		setcookie('cofmSUBUNIT', $old['e']);
		setcookie('cofmSEKSI', $old['e1']);
		
		$bidang = $old['nmbidang'];// $get['nm_skpd'];
		
		$unit = $old['nmopd'];//$get['nm_skpd'];
		$subunit = $old['nmunit'];//$get['nm_skpd'];		
		$seksi = $old['nmseksi'];//$get['nm_skpd'];		
		
		$pemegang2 = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='".$old['ref_idpemegang2']."'"
		));
		
		$pemegang = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='".$old['ref_idpemegang']."'"
		));
		//$cek .= "select * from ref_pegawai where Id='".$old['ref_idpemegang']."'";
		//$cek .= 'pegang='.$pemegang['nama'];
		$penanggung = mysql_fetch_array(mysql_query(
			"select * from ref_pegawai where Id='".$old['ref_idpenanggung']."'"
		));
		
		$barcode = 
			$old['a1'].'.'.$old['a'].'.'.$old['b'].'.'.
			$old['c'].'.'.$old['d'].'.'.substr($old['thn_perolehan'],2,2).'.'.$old['e'].'.'.$old['e1'].'.'.
			$old['f'].'.'.$old['g'].'.'.$old['h'].'.'.$old['i'].'.'.$old['j'].'.'.$old['noreg'];
			
			
		$eStatusPenguasaan_ = 
			$old['f']=='01' || $old['f']=='03' || $old['f']=='04' ?
			cmb2D_v2('status_penguasaan', $old['status_penguasaan'], $Main->arStatusPenguasaan, $disabled):
			'';
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 
				'value'=> $bidang."<input type='hidden' value='".$old['c']."' id='SensusEditSkpdfmSKPD' name='SensusEditSkpdfmSKPD' >" 
				, 'labelWidth'=>150, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 
				'value'=> $unit."<input type='hidden' value='".$old['d']."' id='SensusEditSkpdfmUNIT' name='SensusEditSkpdfmUNIT' >" 
				,  'type'=>'' ),			
			'subunit' => array(  'label'=>'UNIT', 
				'value'=> $subunit."<input type='hidden' value='".$old['e']."' id='SensusEditSkpdfmSUBUNIT' name='SensusEditSkpdfmSUBUNIT' >" 
				,  'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 
				'value'=> $seksi."<input type='hidden' value='".$old['e']."' id='SensusEditSkpdfmSEKSI' name='SensusEditSkpdfmSEKSI' >" 
				,  'type'=>'' ),
			'barcode' => array(  'label'=>'Kode Barang', 'value'=> $barcode,  'type'=>'' ),			
			'nmbrg' => array(  'label'=>'Nama Barang', 'value'=> $old['nm_barang'],  'type'=>'' ),			
			'thn' => array(  'label'=>'Tahun Sensus', 'value'=> $old['tahun_sensus'],  'type'=>'' ),						
			'tgl'=> array(  'label'=>'Tanggal Cek Barang', 
				'value'=> TglInd($old['tgl'])//createEntryTgl3( $old['tgl'], 'tgl', TRUE)
				. "<input type='hidden' id='tgl_sensus' name='tgl_sensus' value='".$old['tgl']."' >"
				, 'type'=>'' 
			),	
			'ada' => array(  'label'=>'Ada/Tidak Ada Barang', 
				'value'=> cmb2D_v2('ada', $old['ada'], $Main->ArrAda, " onchange='Sensus.entryAdaOnchange(this)' "), 
				'type'=>'' 
			),	
			'kondisi' => array(  'label'=>'Kondisi', 
				'value'=> cmb2D_v2('kondisi', $old['kondisi'], $Main->KondisiBarang), 
				'type'=>'' 
			),			
			'ruang' => array(  'label'=>'Gedung / Ruang', 
				'value'=> //$old['nm_gedung'].' / '.$old['nm_ruang'].					
					" <input type='text' id='nm_gedung' value='".$old['nm_gedung']."' readonly='true' style='width:205'>".
					' &nbsp; / &nbsp; '.
					" <input type='text' id='nm_ruang' value='".$old['nm_ruang']."' readonly='true' style='width:205'>".
					" <input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihRuang()\" id='btRuang' name='btRuang'>".
					" <input type='hidden' id='ref_idruang' name='ref_idruang' value='".$old['ref_idruang']."'>"
				,  'type'=>'' 
			),
			'pemegang2' => array(  
				'label'=>'Penanggung Jawab Barang', 
				'value'=> "<input type='hidden' id='ref_idpemegang2' name='ref_idpemegang2' value='".$old['ref_idpemegang2']."'> ".
					"<input type='text' id='nama3' readonly=true value='".$pemegang2['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip3' readonly=true value='".$pemegang2['nip']."' style='width:150' > ".
					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPemegang2()\" id='btPemegang2' name='btPemegang2'>"
				,
				'type'=>'' 
			), 	
			'p3' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt3' readonly=true value='".$pemegang2['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'pemegang' => array(  
				'label'=>'Pengurus Barang/Pembantu', 
				'value'=> "<input type='hidden' id='ref_idpemegang' name='ref_idpemegang' value='".$old['ref_idpemegang']."'> ".
					"<input type='text' id='nama1' readonly=true value='".$pemegang['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip1' readonly=true value='".$pemegang['nip']."' style='width:150' > ".
					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPemegang()\" id='btPemegang' name='btPemegang'>"
				,
				'type'=>'' 
			), 			
			'p1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt1' readonly=true value='".$pemegang['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'ref_idpenanggung' => array(  
				'label'=>'Pengguna/Kuasa Pengguna Barang', 
				'value'=> "<input type='hidden' id='ref_idpenanggung' name='ref_idpenanggung' value='".$old['ref_idpenanggung']."'> ".
					"<input type='text' id='nama2' readonly=true value='".$penanggung['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip2' readonly=true value='".$penanggung['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPenanggung()\" id='btPenanggung' name='btPenanggung'>"
				,
				'type'=>'' 
			), 			
			'p2' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt2' readonly=true value='".$penanggung['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'catatan' => array(  'label'=>'Catatan', 
				'value'=> "<textarea id='catatan' name='catatan' style='width:430;height:40'>".$old['catatan']."</textarea>", 
				'type'=>'' , 'row_params'=>" valign='top'"
			),
			//NULL
			'status_penguasaan' => 
				 array(  'label'=>'Status Penguasaan', 
				'value'=> $eStatusPenguasaan_, 
				'type'=>'' ,
			)
			,			
			'petugas' => array(  'label'=>'Petugas Sensus', 
				'value'=> "<input type='text' id='petugas' name='petugas' $disabled value='".$old['petugas']."' style='width:430'> ",  
				'type'=>'' , 'pemisah'=>':'
			)
		);	
		$FormContent = $this->setForm_content();
		$this->form_menubawah =			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanEdit()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		$form = centerPage(
			"<form name='$form_name' id='$form_name' method='post' action=''>".
			createDialog(
				$form_name.'_div', 
				$FormContent,
				680,
				470,
				'Sensus Barang - Edit',
				'',
				$this->form_menubawah.
				"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
				"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
				"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
				,
				$this->form_menu_bawah_height
			).
			"</form>"
		);	
		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
	}
	
	function simpanBaru(){
		global $Main, $HTTP_COOKIE_VARS;		
		$cek = ''; $err=''; $content=''; 
		
		$qry = FALSE; //$err = 'tes';
		$sesi = $_REQUEST['sesi'];
		$tgl_sensus = $_REQUEST['tgl_sensus'];
		$tahun_sensus = $_REQUEST['tahun_sensus'];
		$c= $_REQUEST['SensusTmpSkpdfmSKPD'];
		$d= $_REQUEST['SensusTmpSkpdfmUNIT'];
		$e= $_REQUEST['SensusTmpSkpdfmSUBUNIT'];
		$e1= $_REQUEST['SensusTmpSkpdfmSEKSI'];
		
		//$kondisi = $_REQUEST['kondisi'];		
		
		//cek ada/tidak sudah diisi --------------------
		$get = mysql_fetch_array(mysql_query(
			"select count(*) as cnt from sensus where sesi='$sesi' and (error='' or error is null) and (ada <>1 and ada <>2)"
		));
		if($err=='' && $get['cnt']>0)$err = 'Ada/Tidak Ada Barang Belum Diisi!';				
		
		//cek kondisi sudah diisi ----------------------
		if($err==''){
			$get = mysql_fetch_array(mysql_query(
				//"select count(*) as cnt from sensus where sesi='$sesi' and (error='' or error is null) and (kondisi is null or kondisi='')"
				"select count(*) as cnt from sensus where sesi='$sesi' and (error='' or error is null) and (kondisi is null or kondisi='') and ada=1 "
			));		
			if($err=='' && $get['cnt']>0)$err = 'Kondisi Barang Belum Diisi!';				
		}
		
		if($err==''){
			//cek lagi data scan ----------------------------------
			$old = mysql_query( 
				"select * from sensus where sesi='$sesi' and (error is null or error='')"
			);
			$cek .= "select * from sensus where sesi='$sesi' and (error is null or error='');";
			while ($row = mysql_fetch_array($old) ){
				/* //cek lg sudah discan
				mysql_fetch_array( mysql_query(
					"select sesi,count(*)as cnt from sensus where idbi = '".$row['idbi']."' 
					and year(tgl)=year('$tgl_sensus') and sesi=''"
				));*/
				//cek ruang 
				$ruang = mysql_fetch_array( mysql_query(
					"select * from ref_ruang where id='".$old['ref_idruang']."'"
				)) ;			
				$cek .= "select * from ruang where id='".$old['ref_idruang']."';";			
				if($err=='' && $ruang['q']== '0000') $err = "Gagal Simpan Barcode '".$row['kode']."', Ruangan salah!";
				//cek tahun sensus
				$cekthnsensus = mysql_fetch_array(mysql_query(					
					"select count(*) as cnt from sensus where sesi='' and idbi='".$row['idbi']."' and tahun_sensus= '$tahun_sensus' "
				));
				$cek .= "select count(*) as cnt from sensus where sesi='' and idbi='".$row['idbi']."' and tahun_sensus= '$tahun_sensus' ;";
				if($err=='' && $cekthnsensus['cnt']>0 ) $err = " Gagal Simpan Barcode '".$row['kode']."', Barang sudah disensus pada tahun yang sama!";
								
			};
			if ($err=='' && mysql_num_rows($old)==0) $err = 'Data tidak ada!';
			
			if($err==''){	
				//delete error
				$aqry = "delete from sensus where sesi ='$sesi' and error<>'' "; $cek .= $aqry;
				$qry = mysql_query($aqry) ;
				if ($qry){	
					$aqry3 = "insert into sensus_scan (tgl,tahun_sensus,a1,a,b,c,d,e,e1)values ".
						"('$tgl_sensus','$tahun_sensus','$Main->DEF_KEPEMILIKAN','$Main->DEF_PROPINSI','$Main->DEF_WILAYAH','$c','$d','$e','$e1') "; $cek .=$aqry3;
					$qry3 = mysql_query($aqry3);
					$ref_idsensusscan = mysql_insert_id();
					
					
					//update sensus ------------------------------------------------------------------------
					$aqry = "select * from sensus where sesi='$sesi' and (error is null or error='')"; $cek .= $aqry;
					$old = mysql_query( $aqry );					
					while ($row = mysql_fetch_array($old) ){
						//ambil data bi --------------------------------
						$bi = mysql_fetch_array( mysql_query(
							"select * from buku_induk where id='".$row['idbi']."' "
						));	
						$cek .= "select * from buku_induk where id='".$row['idbi']."' ;";
						if( !($bi['tgl_sensus'] =='' || $bi['tgl_sensus'] =='0000-00-00 00:00:00' || $bi['tgl_sensus'] =='0000-00-00')){
							$tgl_lama = '';
						}else{
							$tgl_lama =$bi['tgl_sensus'] ; 
						}
						//update sensus ---------------------------------													
						$aqry2 = "update sensus set sesi ='',
							ref_idsensusscan = '$ref_idsensusscan',  	
							tahun_sensus ='$tahun_sensus',						
							tgl_lama='".$tgl_lama."',  
							kondisi_awal='".$bi['kondisi']."',
							tahun_sensus_lama='".$bi['tahun_sensus']."',
							ref_idpemegang_lama='".$bi['ref_idpemegang']."',
							ref_idpenanggung_lama='".$bi['ref_idpenanggung']."',
							ref_idpemegang2_lama='".$bi['ref_idpemegang2']."',
							ref_idruang_lama='".$bi['ref_idruang']."'".
							" where id='".$row['Id']."'"; $cek .= $aqry2;							
						$qry2 = mysql_query( $aqry2);		
						if($qry2 == FALSE) {						
							$err = 'Gagal simpan data!';							
						}else{												
							//update buku_induk	-----------------------------															
							if($row['ref_idpemegang'] !='') $upd_pemegang = "ref_idpemegang='".$row['ref_idpemegang']."', ";
							if($row['ref_idpemegang2'] !='') $upd_pemegang2 = "ref_idpemegang2='".$row['ref_idpemegang2']."', ";
							if($row['ref_idpenanggung'] !='') $upd_penanggung = "ref_idpenanggung='".$row['ref_idpenanggung']."', ";
							if($row['ref_idruang'] !='') $upd_idruang = "ref_idruang='".$row['ref_idruang']."', ";
							if($row['status_penguasaan']!='') $upd_stpenguasaan = "status_penguasaan='".$row['status_penguasaan']."', ";
							$aqry2 = "update buku_induk set tgl_sensus='".$row['tgl']."', ".
								"tahun_sensus='".$tahun_sensus."', ".
								$upd_pemegang.
								$upd_pemegang2.
								$upd_penanggung.
								$upd_idruang.
								$upd_stpenguasaan.
								" kondisi='".$row['kondisi']."' ".
								" where id='".$row['idbi']."'"; $cek .= $aqry2;
							$qry2 = mysql_query( $aqry2);	
							if($qry2 == FALSE) {						
								$err = 'Gagal simpan data!';								
							}
						}
						if($err!='') break;
						
					}
					
					
					
				}
			}
			$cek .= ' >';
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);				
	}
	
	function simpanEdit(){
		global $Main, $HTTP_COOKIE_VARS;		
		$cek = ''; $err=''; $content=''; 
		
		$UID = $_COOKIE['coID'];
		$id = $_REQUEST[$this->Prefix."_idplh"];
		
		$old= mysql_fetch_array(mysql_query(
			" select * from  sensus where  id='$id'  "
		));
		
		$tgl = $_REQUEST['tgl_sensus'];
		$kondisi = $_REQUEST['kondisi'];
		$ref_idruang = $_REQUEST['ref_idruang'];
		$ref_idpemegang = $_REQUEST['ref_idpemegang'];
		$ref_idpemegang2 = $_REQUEST['ref_idpemegang2'];
		$ref_idpenanggung = $_REQUEST['ref_idpenanggung'];	
		
		$catatan = $_REQUEST['catatan'];	
		$petugas = $_REQUEST['petugas'];
		$ada = $_REQUEST['ada'];	
		$status_penguasaan = $_REQUEST['status_penguasaan'];
		if( !empty($status_penguasaan) ){
			$status_penguasaan_ = "'".$_REQUEST['status_penguasaan']."'";
		}else{
			$status_penguasaan_ = 'null';
		}
				
		$aqry = "update sensus set ada='$ada', kondisi='".$kondisi."', uid='$UID', tgl_update=now(), ". 
			" catatan='".$catatan."', ref_idruang='".$ref_idruang."', ref_idpemegang2='".$ref_idpemegang2.
			"', ref_idpemegang='".$ref_idpemegang."', ".			
			" ref_idpenanggung='".$ref_idpenanggung."', ".  
			" status_penguasaan=$status_penguasaan_, ".
			" petugas='$petugas'".
			" where id='$id' "; $cek .= $aqry;
		$qry = mysql_query($aqry);
		if($qry==FALSE) {
			$err= 'Gagal simpan!';
		} else {
			//update buku_induk			
			$aqry = "update buku_induk set kondisi='".$kondisi."',  ". 
				" ref_idruang='".$ref_idruang."', ref_idpemegang2='".$ref_idpemegang2."', ref_idpemegang='".$ref_idpemegang."', 
				ref_idpenanggung='".$ref_idpenanggung."',".
				" status_penguasaan=$status_penguasaan_ ".  
				" where id='".$old['idbi']."' "; $cek .= $aqry;
			$qry = mysql_query($aqry);
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);			
	}
	
	function setMenuEdit(){		
		$menu = $_REQUEST['menu'];
		/*if($menu == 'diusulkan'){
			$menuUsulHapus = "<td>".genPanelIcon("javascript:".$this->Prefix.".usulHapus()","delete_f2.png","Usulan",'Usulan Penghapusan')."</td>";	
		}*/
		if($menu == 'ada'){
			$menuUsulHapus = "<td>".genPanelIcon("javascript:".$this->Prefix.".usulHapus()","delete_f2.png","Usulan",'Usulan Penghapusan')."</td>";	
		}
		
		return
			$menuUsulHapus.
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}
	
	function setMenuView(){	
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".FormCetakShow(2)","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".FormCetakShow(3)","print_f2.png",'Semua',"Cetak Semua")."</td>"
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel",'Export ke Excell')."</td>"		
			;
	}
	/*
	function hasilSensus(){
		global $Main;
		$Main->PagePerHal = 100;
		$HalDefault = cekPOST("HalDefault",1);
		$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
		
		$cidBI = cekPOST("cidBI");
		
		$cbxDlmRibu = cekPOST("cbxDlmRibu");
		
		$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
		$fmID 	= cekPOST("fmID",0);
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
		//$fmWIL 	= cekPOST("fmWIL");
		$fmSKPD = cekPOST("fmSKPD");
		$fmUNIT = cekPOST("fmUNIT");
		$fmSUBUNIT = cekPOST("fmSUBUNIT");
		$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
		setWilSKPD();
		
		$fmBIDANG 	= cekPOST("fmBIDANG","");
		$fmKELOMPOK = cekPOST("fmKELOMPOK","");
		$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
		$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");
		
		$Info = "";
		
		$fmTahun = cekPOST('fmTahun',date('Y'));
		$tglAwal = $fmTahun.'-1-1';
		$tglAkhir = $fmTahun.'-12-31';
		list($ListData, $jmlData) = 
			Mutasi_RekapByBrg_GetList2($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
					$Main->PagePerHal * (($HalDefault*1) - 1),0,
					array(50,50,50,'',100,100), !empty($cbxDlmRibu), FALSE, 3
					);
					
		$tampilHeaderHarga =  !empty($cbxDlmRibu) ? "Jumlah Harga <br>dalam Ribuan <br>(Rp)" : " Jumlah Harga <br>(Rp) ";
		$ListHeader = 
			"<tr>
				<th class=\"th01\"  width=\"50\">Nomor</th>
				<th class=\"th01\"  width=\"50\">Golongan</th>
				<th class=\"th01\"  width=\"50\">Kode<br>Bidang<br>Barang</th>
				<th class=\"th01\" >Nama Bidang Barang</th>
				<th class=\"th01\" width=\"120\">Jumlah Barang</th>
				<th class=\"th01\" width=\"120\">$tampilHeaderHarga</th>
				<!--<th class=\"th01\" >Keterangan</th>-->
			</tr>";
		
		$Tahun= "<div style='float:left;padding:2 8 2 8;border-left:1px solid #E5E5E5;'> Tahun <input type=text name='fmTahun' size=4 value='$fmTahun'></div>";
		$dalamRibuan = "<div style='float:left;padding:0 8 0 0; '> 
				<table ><tr>
				<td style='padding:0;'> Tampilkan : </td>
				<td width='10' style='padding:0;'> <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> </td>
				<td style='padding:0;'>Dalam Ribuan </td>
				</tr></table>
			</div>";
		//$dalamRibuan = " <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> Dalam Ribuan ";
		$tombolTampil= "<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>";
		$optTampil = "
			<table width=\"100%\" class=\"adminform\" style='margin:4 0 4 0;'>	<tr>		
			<td width=\"100%\" valign=\"top\">
						$dalamRibuan
						$Tahun
						$tombolTampil			
					
				</td></tr>
			</table>";
		
		$Main->ListData->ToolbarBawah =
			"<!--<table width=\"100%\" class=\"menudottedline\">
			<tr><td>-->
				<table width=\"50\"><tr>
				<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>-->
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Cetak")."</td>".		
				"<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak&xls=1&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>".
				//"<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak&SDest=XLS&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>".
				"</tr></table>
			<!--</td></tr>
			</table>-->";
		
		//echo "<br>dlm ribu=".$cbxDlmRibu;
		$cek='';
		
		
		$Isi = 
		
			"<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
				
			<table class=\"adminheading\">
				<tr>
				  <th height=\"47\" class=\"user\">Rekapitulasi Buku Inventaris Barang </th>
				  <th>".$Main->ListData->ToolbarBawah."</th>
				</tr>
			</table>
			
			
						".WilSKPD2b()."
					
			
			
			
			$optTampil
			
			<table border=\"1\" class=\"koptable\">
				$ListHeader
					$ListData
				<tr>
						<td colspan=12 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalDefault")."</td>
				</tr>
			</table>
			<br>
			
			".$cek;

		
		echo
		"<html>".
			$this->genHTMLHead().
			"<body >".		
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
				//header page -------------------		
				"<tr height='34'><td>".											
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.							
				"<tr height='*' valign='top'> <td >".
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					//$form1.					
					$Isi.	
						
						
					//$form2.
					"</div></div>".
				"</td></tr>".				
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>".
			"</body>
		</html>"; 

	}
	*/
	function getJmlCetakKKerja(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$kib = $_REQUEST['kib'];
		switch ($kib){
			case '1' : $tblkib = 'view_kib_a'; break;
			case '2' : $tblkib = 'view_kib_b'; break;
			case '3' : $tblkib = 'view_kib_c'; break;
			case '4' : $tblkib = 'view_kib_d'; break;
			case '5' : $tblkib = 'view_kib_e'; break;
		}
		
		if ($fmSKPD !='00' && $fmSKPD !='') $kondSKPD .= " and c='$fmSKPD' ";
		if ($fmUNIT !='00' && $fmUNIT !='') $kondSKPD .= " and d='$fmUNIT' ";
		if ($fmSUBUNIT !='00' && $fmSUBUNIT !='') $kondSKPD .= " and e='$fmSUBUNIT' ";
		if ($fmSEKSI !='00' && $fmSEKSI !='000' && $fmSEKSI !='') $kondSKPD .= " and e1='$fmSEKSI' ";
		
		$aqry = //"select count(*) from ";
			//" select count(*) as cnt from $tblkib where a1='11' and a='10' and b='00' $kondSKPD and status_barang <> 3 and status_barang <> 4 and status_barang <> 5 and tgl_buku<='2012-12-31' and (tahun_sensus <> 2013 or tahun_sensus='' or tahun_sensus is null) order by a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg limit 0, 10";
			" select count(*) as cnt from $tblkib where a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' $kondSKPD and status_barang <> 3 and status_barang <> 4 and status_barang <> 5 
				and tgl_buku<='2012-12-31' 
				order by a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg limit 0, 10";
		$get = mysql_fetch_array( mysql_query($aqry) );
		$content->jmldata = $get['cnt'];
		$content->vjmldata = number_format($get['cnt'],0,',','.');
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function set_selector_other($tipe){	
		$tipe = $_REQUEST['tipe'];	
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$tgl_sensus = $_REQUEST['tgl_sensus'];
		switch($tipe){
			/*case 'hasilSensus':{
				$this->hasilSensus();
				$json = FALSE;
				break;
			}*/
			case 'getjmlcetakkkerja':{
				$fm = $this->getJmlCetakKKerja();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];
				break;
			}
			case 'simpanBaru':{				
				$fm = $this->simpanBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];
				break;
			}	
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$json = $fm['json'];
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
			case 'formEdit2':{
				$fm = $this->setFormEdit2();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];																
				break;
			}
			case 'simpanEdit':{
				$fm = $this->simpanEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];																
				break;
			}
			case 'batal':{
				$sesi = $_REQUEST['sesi'];
				if ($sesi != ''){
					$aqry = "delete from sensus where sesi='$sesi'";
					$qry = mysql_query($aqry);
				}
				break;
			}
			case 'FormCetakShow':{				
				$fm = $this->setFormCetak();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				$json=TRUE;											
				break;
			}
			case 'cetak_all':{
				$this->cetak_all();
				break;
			}
			case 'cetak_hal':{
				$this->cetak_hal();
				break;
			}
			case 'exportXlsAll':{
				$this->exportExcelAll();
				break;	
			}
			case 'exportXlsHal':{
				$this->exportExcelHal();
				break;	
			}					
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
			
		}		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function cetak_hal(){
		$this->Cetak_Mode=2;	
		$this->genCetak();	
	}
		
	function cetak_all(){
		$this->Cetak_Mode=3;	
		$this->genCetak();	
	}
	
	function exportExcelHal(){
		$this->Cetak_Mode=2;
		$this->genCetak(TRUE);	
	}
	
	function exportExcelAll(){
		$this->Cetak_Mode=3;
		$this->genCetak(TRUE);	
	}
	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
		/*
		<style>
		.nfmt1 {mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt2 {mso-number-format:'0\.00_';}
		.nfmt3 {mso-number-format:'00000';}
		.nfmt4 {mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt5 {mso-number-format:'\@';} 
		table {mso-displayed-decimal-separator:'\.';
			mso-displayed-thousand-separator:'\,';}	
		br {mso-data-placement:same-cell;}	
		</style>*/	
		//if($this->cetak_xls){
		$this->cetak_xls=$xls;
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		$haldefault = $_REQUEST['haldefault'];
		$checked = $_REQUEST['checked'];
		$limitstart = $_REQUEST['limitstart'];
		$limitend = $_REQUEST['limitend'];
		$jmlPerHal = $_REQUEST['jmlPerHal'];	
		if($haldefault==''){
			if($limitstart==1){
				$limitstart=($limitstart)-1;
				$limitend=$limitend;
			}else{
				$limitstart=($limitstart)-1;
				$limitend=$limitend-$limitstart;
			}
		}else{
			$limitstart=(($haldefault	*1) - 1) * $jmlPerHal;
			$limitend=$jmlPerHal;
		}
		$Limit = $checked==1?'':$Limit = " limit ".$limitstart.",".$limitend; 
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}
						
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH'>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
		$Opsi['Limit']=$Limit;
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
			//echo 'vkondisi='.$$Opsi[vKondisi;
		if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}								
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}
		
	function setFormCetak($dt){	
	 global $SensusTmp,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 430;
	 $this->form_height = 70;	 
	 $jnsform = $_GET['jnsform'];
	 $HalDefault=cekPOST($this->Prefix.'_hal',1);
	 if($jnsform==2){
	 	$this->form_caption = 'Cetak Hal';
		$this->form_fields = array(
			'cetak' => array('label'=>"", 
							'value'=>"Cetak Halaman", 
							'type'=>'merge',
							'param'=>""
							  ),
				
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='haldefault' id='haldefault' value='$HalDefault'>&nbsp;".
			"<input type='button' value='Excel' onclick ='".$this->Prefix.".exportXlsHal(\"$Op\")' >&nbsp;".
			"<input type='button' value='Cetak' onclick ='".$this->Prefix.".cetakHal()' >&nbsp;".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
	 }else{
	 	$this->form_caption = 'Cetak Semual Hal';
		$this->form_fields = array(
			/*'cetak' => array('label'=>"", 
							'value'=>"Cetak Semua", 
							'type'=>'merge',
							'param'=>""
							  ),*/								
			'checkbox' => array('label'=>'', 
								'value'=>"<input type=checkbox name='limitdata' id='limitdata' value='' style='margin-left:0;' onchange='".$this->Prefix.".formCetakCek()'>&nbsp;Cetak Semua",  
								'type'=>'merge', 
								'param'=>""
								),	
			'cetaklimit' => array('label'=>"Cetak data ke	", 
							'value'=>"<input type='text' name='limitstart' id='limitstart' value='1' style='width:100;'> s/d data ke <input type='text' name='limitend' id='limitend' value='1' style='width:100;'>", 
							'type'=>'',
							'param'=>""
							  ),
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Excel' onclick ='".$this->Prefix.".exportXlsAll(\"$Op\")' >&nbsp;".
			"<input type='button' value='Cetak' onclick ='".$this->Prefix.".cetakAll()' >&nbsp;".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
	 }	 	
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function Hapus($ids){
		global $Main, $HTTP_COOKIE_VARS;
			
		$err=''; $cek='';
		// = $HTTP_COOKIE_VARS['coid'];
		$uiddelete = $_COOKIE['coID'];
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$err = $this->Hapus_Validasi($ids[$i]);
			
			if($err ==''){
				//update uid yg hapus
				$aqry = "update  sensus set uid='$uiddelete' where id='".$ids[$i]."' ";
				$qry = mysql_query($aqry);
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

}
$Sensus = new SensusObj();

class SensusTmpObj extends DaftarObj2{
	var $Prefix = 'SensusTmp';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'view2_sensus';//view2_sensus';
	var $TblName_Hapus = 'sensus';
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 6, 6,6);
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $FormName = 'Sensus_form';
	var $pagePerHal = 8;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun
		
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	
		return $aqry;		
	}	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
			
		$headerTable =
			"<tr>
					<!--<th class=\"th02\" colspan='". ($cetak? "3": "4") ."'>Nomor</th>-->
					<th class=\"th01\" colspan=''>No.</th>
					$Checkbox
					<th class=\"th01\" width='30'>Jam</th>
					<th class=\"th01\" colspan=''>Kode Lokasi/<br>Barang</th>
					<th class=\"th01\" colspan='' width=200>Nama Barang</th>
					<th class=\"th01\" colspan=''>Ada/Tidak Ada</th>
					<th class=\"th01\" colspan=''>Kondisi</th>
					
					<th class=\"th01\" colspan=''>Gedung/<br>Ruang</th>
					<th class=\"th01\" colspan=''>Penanggung Jawab Barang/<br> Pengguna/Kuasa Pengguna Barang/<br>
						Pengurus Barang/Pembantu/Status Penguasaan</th>
					<th class=\"th01\" colspan=''>Catatan</th>
					<th class=\"th01\" colspan=''>Warning</th>
					<!--<th class=\"th01\" colspan='' width='40'></th>-->
					
					
					
					</tr>"
					
					;
		return $headerTable;
	}	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main;
		
		$Koloms = array();
		
		$jmlTotalHargaDisplay += $isi['jml_harga'];
		$clRow = $no % 2 == 0 ?"row1":"row0";
		$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');					
			
			$KondisiAwal = 'kondisi awal';
			$KondisiSensus = 'kondisi sensus';
			
			//if ($isi[])
			$aqry2 = "select * from ref_ruang where id='".$isi['ref_idruang']."'" ; $cek .=$aqry2;
			$get = mysql_fetch_array(mysql_query( 	$aqry2	));
			$ruang = $get['nm_ruang']; //'Ruang';
			//$ruang = $aqry2; 
			$aqry2 = 
				"select * from ref_ruang where a='".$get['a']."' and b='".$get['b'].
				"' and c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' and e1='".$get['e1'].
				"' and p='".$get['p']."' and q='0000'"; //$cek .= $aqry2;
			
			$get = mysql_fetch_array(mysql_query( $aqry2	));
			//$Gedung = $get['nm_ruang']; // 'gedung';
			$gedung = $get['nm_ruang']; //$cek; 
			
			
			$Penanggung = $isi['penanggung'];
			$Pemegang = $isi['pemegang'];
			$Petugas = $isi['uid'];
			$menu= $isi['error']==''? "$TampilCheckBox" : '';
			//$tgl = $isi['tgl'];
			$jam = explode(' ',$isi['tgl']);
			
		$Koloms[] = array('align=right', $no.'.' );
		$Koloms[] = array('align=center', $menu);
		$Koloms[] = array('align=center', $jam[1]);
		$Koloms[] =  
			$isi['error'] == ''?
			array('align=center',
				$isi['a1'].'.'.$isi['a'].'.'.$isi['b'].'.'.
				$isi['c'].'.'.$isi['d'].'.'.substr($isi['thn_perolehan'],2,2).'.'.$isi['e'].'.'.$isi['e1'].'<br>'.
				$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'.'. $isi['noreg']
			):
			array('align=center',
				substr($isi['kode'],0,2).'.'.
				substr($isi['kode'],2,2).'.'.
				substr($isi['kode'],4,2).'.'.
				substr($isi['kode'],6,2).'.'.
				substr($isi['kode'],8,2).'.'.
				substr($isi['kode'],10,2).'.'.
				substr($isi['kode'],12,2).'.'.
				substr($isi['kode'],14,3).'<br>'.
				
				substr($isi['kode'],14,2).'.'.
				substr($isi['kode'],16,2).'.'.
				substr($isi['kode'],18,2).'.'.
				substr($isi['kode'],20,2).'.'.
				substr($isi['kode'],22,3).'.'.
				substr($isi['kode'],25,4)
				
			);			
		$Koloms[] = array('align=left', $isi['nm_barang']);
		$Koloms[] = array('align=left', $Main->ArrAda[$isi['ada']-1][1]);
		$Koloms[] = array('align=left', $Main->KondisiBarang[$isi['kondisi']-1][1] );
		$Koloms[] = array('align=left', $gedung.'<br>'. $ruang);
		$Koloms[] = array('align=left', $isi['pemegang2'].'<br>'. $isi['penanggung'].'<br>'. $isi['pemegang'].
				'<br>'.$Main->arStatusPenguasaan[$isi['status_penguasaan']-1][1]);
		$Koloms[] = array('align=left', $isi['catatan']);
		$Koloms[] = array('align=left', $isi['error']);
		
		return $Koloms;
	}
	function setTopBar(){
		return '';
	}	
	function genDaftarInitial($fmSKPD='',$fmUNIT='',$fmSUBUNIT='', $tahun_sensus='', $tgl_sensus='',$fmSEKSI=''){
		$cek = $tahun_sensus.' - '.$tgl_sensus;
		$vOpsi = $this->genDaftarOpsi();
		//$fmSKPD = $_POST['']
		return	
			//"<input type='text' value='$cek' id='cek' name='cek' >".
						
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
				"<input type='hidden' value='$tahun_sensus' id='tahun_sensus' name='tahun_sensus' >".
				"<input type='hidden' value='$tgl_sensus' id='tgl_sensus' name='tgl_sensus' >".
				"<input type='hidden' value='$fmSKPD' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' >".
				"<input type='hidden' value='$fmUNIT' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' >".
				"<input type='hidden' value='$fmSUBUNIT' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' >".
				"<input type='hidden' value='$fmSEKSI' id='".$this->Prefix."SkpdfmSEKSI' name='".$this->Prefix."SkpdfmSEKSI' >".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			//"<div id=contain style='overflow:auto;height:280;'>".
			"<div id=contain style='overflow:auto;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}	
	
	function getTahunSensus(){
		global $Main;
		$thnskr = date('Y');		
		$tahun_sensus = $Main->thnsensus_default;
		while ( ($tahun_sensus+ $Main->periode_sensus) <= $thnskr  ){
			$tahun_sensus+= $Main->periode_sensus;
		}
		return $tahun_sensus;
	}
	
	function genDaftarOpsi($tahun_sensus='', $tgl_sensus=''){
		global $Main;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		$fmStat = $_REQUEST['fmStat'];
		$Sensus_idplh  = $_REQUEST['Sensus_idplh'];
		
		$fmSKPD = $_REQUEST[$this->Prefix."SkpdfmSKPD"];//$_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_REQUEST[$this->Prefix."SkpdfmUNIT"];//$_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_REQUEST[$this->Prefix."SkpdfmSUBUNIT"];//$_COOKIE['cofmSUBUNIT'];
		$fmSEKSI = $_REQUEST[$this->Prefix."SkpdfmSEKSI"];//$_COOKIE['cofmSEKSI'];
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$fmSKPD."' and d='".$fmUNIT."' and e='".$fmSUBUNIT."' and e1='".$fmSEKSI."' "));
		$seksi = $get['nm_skpd'];		
		
		//--- tahun sensus
		//".date('Y-m-d')."
		//$tgl_sensus = $_REQUEST['tgl_sensus'];
		//if($tgl_sensus == '') {
			$tgl_sensus = $_REQUEST['tgl_sensus'];
			if($tgl_sensus == '') $tgl_sensus = date( 'Y-m-d' ) ;
		//}
		
		$vtgl_sensus = TglInd($tgl_sensus);//date('d-m-Y');
		/*$thnskr = date('Y');		
		$tahun_sensus = $this->thnsensus_default;
		while ( ($tahun_sensus+ $this->periode_sensus) <= $thnskr  ){
			$tahun_sensus+= $this->periode_sensus;
		}
		*/
		if($tahun_sensus==''){
			$tahun_sensus = $_REQUEST['tahun_sensus'];
			if($tahun_sensus == '')  $tahun_sensus = $this->getTahunSensus();
		}
		
		
		$this->form_fields = array(					
			'bidang' => array(  'label'=>'BIDANG', 
				'value'=> $bidang. "<input type='hidden' value='$fmSKPD' name='".$this->Prefix."SkpdfmSKPD' id='".$this->Prefix."SkpdfmSKPD' >"
				, 'labelWidth'=>100, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 
				'value'=> $unit. "<input type='hidden' value='$fmUNIT' name='".$this->Prefix."SkpdfmUNIT' id='".$this->Prefix."SkpdfmUNIT' >"
				,  'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 
				'value'=> $subunit. "<input type='hidden' value='$fmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' id='".$this->Prefix."SkpdfmSUBUNIT' >"
				,  'type'=>'' ),			
			'seksi' => array(  'label'=>'SUB UNIT', 
				'value'=> $seksi. "<input type='hidden' value='$fmSEKSI' name='".$this->Prefix."SkpdfmSEKSI' id='".$this->Prefix."SkpdfmSEKSI' >"
				,  'type'=>'' ),			
			'tahun' => array(  'label'=>'Tahun Sensus', 
				'value'=> 
				$tahun_sensus.
				"<input type='hidden' id='tahun_sensus' name='tahun_sensus' value='".$tahun_sensus."'>"
				/*cmb2D_v2('tahun_sensus',$tahun_sensus,
					array(
						array('2013','2013'),
						array('2018','2018'),
					)
				,'')*/
				,  'type'=>'' ),
			'field1' => array( 'label'=>'Tgl. Cek Barang', 
				'value'=> $vtgl_sensus.
				//"<input type='hidden' id='tgl_sensus' name='tgl_sensus' value='".date('Y-m-d')."'>"
				"<input type='hidden' id='tgl_sensus' name='tgl_sensus' value='$tgl_sensus'>"
				
				
				/*createEntryTgl3(
					$tgl_sensus, 'tgl_sensus', '', '', '', 
					$this->prefix.'_form',
					TRUE, FALSE
				)*/,
				'type'=>'', 'valign'=>'center' 
			),
			'field2' => array( 'label'=>'Tampilkan', 
				'value'=> 
				cmb2D_v2( 
					'fmStat',$fmStat, array(array(1,'Sukses'),array('2','Gagal')),'','Semua'
				).
				"<input type='button' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>",
				'type'=>'', 'valign'=>'center' 
			)
		);
		$barcode = $Sensus_idplh != '' ? '':
			"<span style='color:red'>BARCODE</span><br>".
			"<input type='TEXT' value='' 
				id='barcodeSensusBaru_input' name='barcodeSensusBaru_input'
				style='font-size:24;width: 369px;' 
				size='32' maxlength='32'>
			<span id='barcodeSensusBaru_msg' name='barcodeSensusBaru_msg' ></span> ";
		$FormContent =
					"<table style='width:100%' > <tr><td>".
					"<table style='width:100%' ><tr><td style='padding:4'>
						<table style='width:100%' >".
						$this->setForm_content_fields(80).
						"</table>
					</td></tr></table>".
					"</td><td width='100' valign='top' style='padding:6'>".
						$barcode. //onkeyup='inputBarcode(this)'					
					"</td></tr></table>";
					
		return array('TampilOpt'=>$FormContent);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main;
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi
		$sesi = $_REQUEST['sesi'];
		$id_pilih = $_REQUEST['Sensus_idplh'];
		if (!empty($id_pilih)) {
			 $arrKondisi[] = " ref_idsensusscan = '$id_pilih' ";	
		}else{
			if (!empty($sesi)) $arrKondisi[] = " sesi = '$sesi' ";	
		}
		
		$fmStat = $_POST['fmStat'];
		switch($fmStat){
			case '1': $arrKondisi[] = "( error is null or error='') "; break;
			case '2': $arrKondisi[] = " error<>'' "; break;
		}		
		
		/*$fmKODEI = $_POST['fmOp'];
		if (!empty($fmKODEI)) $arrKondisi[] = " i= '".$fmKODEI."'";
		$fmStatusHit = $_POST['fmStatusHit'];		
		switch($fmStatusHit){
			case '1': $arrKondisi[] =  " ref_idhitung !='' "; break;
			case '2': $arrKondisi[] =  " (ref_idhitung ='' or  ref_idhitung is null)"; break;
		}					
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){
			case '1': $arrKondisi[] = " no_sptpd like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " nama_wp like '%$fmPILCARIVALUE%'"; break;
			case '3': $arrKondisi[] = " npwpd like '%$fmPILCARIVALUE%'"; break;
			case '4': $arrKondisi[] = " nama_op like '%$fmPILCARIVALUE%'"; break;
			case '5': $arrKondisi[] = " no_op like '%$fmPILCARIVALUE%'"; break;
			case '6': $arrKondisi[] = " lpad(ref_idhitung,5,'0') like '%$fmPILCARIVALUE%'"; break;
		}
		switch($fmSTATUS){
			case '1': $arrKondisi[] = " status_batal <> 3 "; break;
			case '2': $arrKondisi[] = " status_batal = 3 "; break;			
		}*/	
		
		//$arrKondisi = '';
		
		
		
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " no_sptpd $AscDsc1 "; break;
			case '2': $OrderArr[] =  " nama_wp $AscDsc1 "; break;
			case '3': $OrderArr[] =  " npwpd $AscDsc1 "; break;
			case '4': $OrderArr[] =  " nama_op $AscDsc1 "; break;
			case '5': $OrderArr[] =  " no_op $AscDsc1 "; break;
			case '6': $OrderArr[] =  " tgl_sptpd $AscDsc1 "; break;
			case '7': $OrderArr[] =  " ref_idhitung $AscDsc1 "; break;
		}
		$OrderArr[] = 'id desc';
			
		/*
		//limit --------------------------------------
		$HalDefault=cekPOST($this->elCurrPage,1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		*/
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		$Kondisi = join(' and ',$arrKondisi); 
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}		
	function simpanRuang(){
		$err=''; $cek='';
		$ids= $_POST[$this->Prefix.'_cb'];		
		$idruang = $_GET['idruang'];		
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$aqry = "update sensus set ref_idruang='$idruang' where id='".$ids[$i]."'"; $cek .= $aqry; 
				$qry=mysql_query($aqry);
				if ($qry){
					if($Sensus_idplh != '') {	
						$old = mysql_fetch_array(mysql_query("select * from sensus where id='".$ids[$i]."'"));
						$aqry = "update buku_induk set ".							
							" ref_idruang='$idruang' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);
						
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh==''?
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh' and (error='' or error is null) ";
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set ref_idruang='$idruang', ref_idruang_lama='".$bi['ref_idruang']."'  where id='".$isi['Id']."'"; $cek .= $aqry2; 
				$qry2=mysql_query($aqry2);				
				if ($qry2){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".							
							" ref_idruang='$idruang' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry2 = mysql_query($aqry);
					}
				}
			}
			/*
			$aqry = "update sensus set ref_idruang='$idruang' where sesi='$sesi' and (error='' or error is null)";
			$qry = mysql_query($aqry);	
			if ($qry){
				if($Sensus_idplh != '') {						
					$aqry = "update buku_induk set ".							
						" ref_idruang='$idruang' ".							
						" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
					$qry = mysql_query($aqry);
					
				}
			}*/
		}
		return array('err'=>$err,'cek'=>$cek);		
	}	
	function simpanKondisi(){
		$err=''; $cek='';
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$ids= $_POST[$this->Prefix.'_cb'];		
		$kondisi = $_GET['kondisi'];		
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 			
				$aqry = "update sensus set kondisi='$kondisi', kondisi_awal='".$bi['kondisi']."'  where id='".$ids[$i]."'"; $cek .= $aqry; 
				$qry = mysql_query($aqry);
				//update buku induk jika mode edit
				if ($qry){
					if($Sensus_idplh != '') {
						
						$aqry = "update buku_induk set ".							
							" kondisi='$kondisi' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);
						
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh==''?
				 "select * from sensus where sesi='$sesi' and (error='' or error is null)":
				 "select * from sensus where ref_idsensusscan='$Sensus_idplh' and (error='' or error is null)"; $cek .= $aqry;
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set kondisi='$kondisi', kondisi_awal='".$bi['kondisi']."'  where id='".$isi['Id']."'"; $cek .= $aqry2; 
				$qry2 = mysql_query($aqry2);	
				if ($qry2){
					if($Sensus_idplh != '') {
						
						$aqry = "update buku_induk set ".							
							" kondisi='$kondisi' ".							
							" where id ='".$isi['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry2 = mysql_query($aqry);
						
					}
				}			
			}
		}
		return array('err'=>$err,'cek'=>$cek);		
	}	
	function simpanEntryCatatan(){
		$err=''; $cek='';
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$ids= $_POST[$this->Prefix.'_cb'];		
		$catatan = $_REQUEST['catatan'];		
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 			
				$aqry = "update sensus set catatan='$catatan' where id='".$ids[$i]."'"; $cek .= $aqry; 
				mysql_query($aqry);
			}
		}else{
			$aqry = $Sensus_idplh==''?
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh' ";
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set catatan='$catatan' where id='".$isi['Id']."'"; $cek .= $aqry2; 
				mysql_query($aqry2);				
			}
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
	function setFormPilihKondisi(){
		global $Main;
		$err=''; $cek=''; $content='';
		
		$this->form_width = 250;
		$this->form_height = 60;		
		$this->form_caption = 'Pilih Kondisi';
		$this->form_fields = array(				
			'kondisi' => array(  'label'=>'Pilih Kondisi', 
			'value'=> cmb2D('kondisi',$kondisi,$Main->KondisiBarang,'')	, 
			'labelWidth'=>100, 'type'=>'' ),
		);
		//$FormContent = 'Kondisi';
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".pilihKondisiSimpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".pilihKondisiClose()' >".
			"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
			"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
			"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
			;
		
		$form = $this->genForm(TRUE);
		
		/*
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						550,
						100,
						'Pilih Kondisi',
						'',
						$this->form_menubawah.
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
		);		
		*/
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		/*$pageArr = array(						
			'err'=>$err, 			
			'content'=> $content,
			'cek'=>$cek 
		);
		$page = json_encode($pageArr);	
		echo $page;*/
	}	
	function entryCatatan(){
		global $Main;
		$err=''; $cek=''; $content='';
		
		$this->form_width = 300;
		$this->form_height = 100;		
		$this->form_caption = 'Catatan';
		$this->form_fields = array(				
			'kondisi' => array(  'label'=>'Catatan', 
			'value'=> "<textarea id='catatan' name='catatan' style='width:200'></textarea>"	, 
			'labelWidth'=>80, 'type'=>'' ),
		);
		//$FormContent = 'Kondisi';
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".entryCatatanSimpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".entryCatatanClose()' >".
			"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
			"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
			"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
			;
		
		$form = $this->genForm(TRUE);		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		
	}	
	function entryAda(){
		global $Main;
		$err=''; $cek=''; $content='';
		
		$this->form_width = 710;
		$this->form_height = 370;		
		$this->form_caption = 'Ada/Tidak Ada';
		/*$this->form_fields = array(				
			'ada' => array(  'label'=>'Ada', 
			'value'=> "<textarea id='ada' name='ada' style='width:200'></textarea>"	, 
			'labelWidth'=>80, 'type'=>'' ),
		);*/
		
		//if($old['ada']==0) $old['ada']=1;
		$disabled = '';
		if($old['ada']!=1) $disabled = " disabled='1'";
		
		$this->form_fields = array(				
			'ada' => array(  'label'=>'Ada/Tidak Ada Barang', 
				'value'=> cmb2D_v2('ada', $old['ada'], $Main->ArrAda, " onchange='Sensus.entryAdaOnchange(this)'"), 
				'type'=>'', 'labelWidth'=>150
			),	
			'kondisi' => array(  'label'=>'Kondisi', 
				'value'=> cmb2D_v2('kondisi', $old['kondisi'], $Main->KondisiBarang, $disabled), 
				'type'=>'' 
			),			
			'ruang' => array(  'label'=>'Gedung / Ruang', 
				'value'=> //$old['nm_gedung'].' / '.$old['nm_ruang'].					
					" <input type='text' id='nm_gedung' value='".$old['nm_gedung']."' readonly='true' style='width:205'>".
					' &nbsp; / &nbsp; '.
					" <input type='text' id='nm_ruang' value='".$old['nm_ruang']."' readonly='true' style='width:205'>".
					" <input type='button' value='Pilih' onclick=\"Sensus.pilihRuang()\" id='btRuang' name='btRuang' $disabled>".
					"<input type='button' value='Reset' onclick=\"document.getElementById('nm_gedung').value='';document.getElementById('nm_ruang').value='';document.getElementById('ref_idruang').value='';\" $disabled id='btreset4' name='btreset4'>".
					" <input type='hidden' id='ref_idruang' name='ref_idruang' value='".$old['ref_idruang']."'>"
				,  'type'=>'' 
			),
			'pemegang2' => array(  
				'label'=>'Penanggung Jawab Barang', 
				'value'=> "<input type='hidden' id='ref_idpemegang2' name='ref_idpemegang2' value='".$old['ref_idpemegang2']."'> ".
					"<input type='text' id='nama3' readonly=true value='".$pemegang2['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip3' readonly=true value='".$pemegang2['nip']."' style='width:150'  > ".
					
					"<input type='button' value='Pilih' onclick=\"Sensus.pilihPemegang2()\" $disabled id='btPemegang2' name='btPemegang2'>".
					"<input type='button' value='Reset' onclick=\"document.getElementById('ref_idpemegang2').value='';document.getElementById('jbt3').value='';document.getElementById('nama3').value='';document.getElementById('nip3').value='';\" $disabled id='btreset1' name='btreset1'>"
				,
				'type'=>'' 
			), 	
			'p3' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt3' readonly=true value='".$pemegang2['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'pemegang' => array(  
				'label'=>'Pengurus Barang/Pembantu', 
				'value'=> "<input type='hidden' id='ref_idpemegang' name='ref_idpemegang' value='".$old['ref_idpemegang']."'> ".
					"<input type='text' id='nama1' readonly=true value='".$pemegang['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip1' readonly=true value='".$pemegang['nip']."' style='width:150' > ".
					
					"<input type='button' value='Pilih' onclick=\"Sensus.pilihPemegang()\" $disabled id='btPemegang' name='btPemegang'>".
					"<input type='button' value='Reset' onclick=\"document.getElementById('ref_idpemegang').value='';document.getElementById('jbt1').value='';document.getElementById('nama1').value='';document.getElementById('nip1').value='';\" $disabled id='btreset2' name='btreset2'>"
				,
				'type'=>'' 
			), 			
			'p1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt1' readonly=true value='".$pemegang['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'ref_idpenanggung' => array(  
				'label'=>'Pengguna/Kuasa Pengguna Barang', 
				'value'=> "<input type='hidden' id='ref_idpenanggung' name='ref_idpenanggung' value='".$old['ref_idpenanggung']."'> ".
					"<input type='text' id='nama2' readonly=true value='".$penanggung['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip2' readonly=true value='".$penanggung['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"Sensus.pilihPenanggung()\" $disabled id='btPenanggung' name='btPenanggung'>".
					"<input type='button' value='Reset' onclick=\"document.getElementById('ref_idpenanggung').value='';document.getElementById('jbt1').value='';document.getElementById('nama2').value='';document.getElementById('nip2').value='';\" $disabled id='btreset3' name='btreset3'>"
				,
				'type'=>'' 
			), 			
			'p2' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt2' readonly=true value='".$penanggung['jabatan']."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'catatan' => array(  'label'=>'Catatan', 
				'value'=> "<textarea id='catatan' name='catatan' style='width:430;height:40' $disabled>".$old['catatan']."</textarea>", 
				'type'=>'' , 'row_params'=>" valign='top'"
			),
			'petugas' => array(  'label'=>'Petugas Sensus', 
				'value'=> "<input type='text' id='petugas' name='petugas' $disabled value='".$old['petugas']."' style='width:430'> ",  
				'type'=>'' , //'pemisah'=>' '
			),
			'status_penguasaan' => array(  'label'=>'Status Penguasaan', 
				'value'=> cmb2D_v2('status_penguasaan', $old['status_penguasaan'], $Main->arStatusPenguasaan, $disabled), 
				'type'=>'' ,
			),
			
		);
		
		//$FormContent = 'Kondisi';
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".entryAdaSimpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".entryAdaClose()' >".
			"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
			"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
			
			;
		
		$form = $this->genForm(TRUE);		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		
	}	
	function simpanEntryAda(){
		$err=''; $cek='';
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$ids= $_POST[$this->Prefix.'_cb'];		
		$catatan = $_REQUEST['catatan'];		
		$kondisi = $_REQUEST['kondisi'];		
		$ada = $_REQUEST['ada'];		
		$ref_idpemegang2 = $_REQUEST['ref_idpemegang2'];		
		$ref_idpemegang = $_REQUEST['ref_idpemegang'];		
		$ref_idpenanggung = $_REQUEST['ref_idpenanggung'];	
		$ref_idruang = $_REQUEST['ref_idruang'];		
		$petugas = $_REQUEST['petugas'];
		$status_penguasaan = $_REQUEST['status_penguasaan'];
		$Sensus_idplh = $_REQUEST['Sensus_idplh']; $cek .= ' sensus idplh='.$Sensus_idplh;
		
		if($ada == '') $err = 'Ada/Tidak Ada Belum Dipilih!';
		
		//cek kondisi
		if($err=='' && $ada==1 && $kondisi=='' )$err = "Kondisi belum dipilih!" ;
		
		
		if($err==''){
			
		
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 
				if($bi['f']=='01' || $bi['f']=='03' || $bi['f']=='04'){
					$stpenguasa_ = "'$status_penguasaan'";
				}else{
					$stpenguasa_ = 'null';
				}
				$aqry = "update sensus set ". 
					"kondisi='$kondisi', ".
					"ref_idpemegang2='$ref_idpemegang2', ".
					"ref_idpemegang='$ref_idpemegang', ".
					"ref_idpenanggung='$ref_idpenanggung', ".
					"ada='$ada', ".
					"catatan='$catatan', ".
					"ref_idruang='$ref_idruang', ".					
					"ref_idpemegang_lama='".$bi['ref_idpemegang']."', ".
					"ref_idpemegang2_lama='".$bi['ref_idpemegang2']."', ".
					"ref_idpenanggung_lama='".$bi['ref_idpenanggung']."', ".
					"kondisi_awal='".$bi['kondisi']."', ".
					"ref_idruang_lama = '".$bi['ref_idruang']."', ".	
					"status_penguasaan=$stpenguasa_, ".//$stpenguasa_.
					"petugas='".$petugas."' ".									
					"where id='".$ids[$i]."'"; $cek .= ' update sensus='.$aqry; 
				//kondisi,ref_idpemegang2,ref_idpemegang ,ref_idpenanggung,catatan,ada
				$qry = mysql_query($aqry);
				
				//update buku induk jika mode edit
				if ($qry){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".
							" tgl_sensus='".$old['tgl']."', ".
							" tahun_sensus='".$old['tahun_sensus']."', ".
							" kondisi='$kondisi', ".
							" ref_idruang ='$ref_idruang', ".
							" ref_idpemegang2='$ref_idpemegang2', ".
							" ref_idpemegang='$ref_idpemegang', ".
							" status_penguasaan=$stpenguasa_, ".//" status_penguasaan='$status_penguasaan', ".
							" ref_idpenanggung='$ref_idpenanggung' ".
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);
						
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh ==''? 
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh' and (error='' or error is null) "; $cek .= $aqry; 
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 		
				if($bi['f']=='01' || $bi['f']=='03' || $bi['f']=='04'){
					$stpenguasa_ = "'$status_penguasaan'";
				}else{
					$stpenguasa_ = 'null';
				}	
				$aqry2 = //"update sensus set catatan='$catatan' where id='".$isi['Id']."'"; $cek .= $aqry2; 
					"update sensus set ". 
					"kondisi='$kondisi', ".
					"ref_idpemegang2='$ref_idpemegang2', ".
					"ref_idpemegang='$ref_idpemegang', ".
					"ref_idpenanggung='$ref_idpenanggung', ".
					"ada='$ada', ".
					"catatan='$catatan', ".
					"ref_idruang='$ref_idruang', ".					
					"ref_idpemegang_lama='".$bi['ref_idpemegang']."', ".
					"ref_idpemegang2_lama='".$bi['ref_idpemegang2']."', ".
					"ref_idpenanggung_lama='".$bi['ref_idpenanggung']."', ".
					"kondisi_awal='".$bi['kondisi']."', ".
					"ref_idruang_lama = '".$bi['ref_idruang']."', ".
					"status_penguasaan=$stpenguasa_, ".//$stpenguasa_.	//"status_penguasaan='$status_penguasaan', ".		
					"petugas='".$petugas."' ".									
					"where id='".$isi['Id']."'"; $cek .= ' update sensus='.$aqry2; 
				$qry2 = mysql_query($aqry2);
				
				if ($qry2){
					if($Sensus_idplh != '') {
						$aqry2 = 
							" update buku_induk set ".
							" tgl_sensus='".$old['tgl']."', ".
							" tahun_sensus='".$old['tahun_sensus']."', ".
							" kondisi='$kondisi', ".
							" ref_idruang ='$ref_idruang', ".
							" ref_idpemegang2='$ref_idpemegang2', ".
							" ref_idpemegang='$ref_idpemegang', ".
							" status_penguasaan=$stpenguasa_, ".//" status_penguasaan='$status_penguasaan', ".
							" ref_idpenanggung='$ref_idpenanggung' ".
							" where id ='".$old['idbi']."'"; $cek .= ' update bi='.$aqry2; 
						$qry2 = mysql_query($aqry2);
					}
				}				
			}
		}
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
	function simpanPemegang(){
		$err=''; $cek='';
		$ids= $_POST[$this->Prefix.'_cb'];		
		$idpegawai = $_GET['idpegawai'];		
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
				
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 			
				$aqry = "update sensus set ref_idpemegang='$idpegawai', ref_idpemegang_lama='".$bi['ref_idpemegang']."'  where id='".$ids[$i]."'"; $cek .= $aqry; 
				$qry = mysql_query($aqry);
				if ($qry){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".							
							" ref_idpemegang='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);						
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh==''?
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh'  and (error='' or error is null) ";
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set ref_idpemegang='$idpegawai', ref_idpemegang_lama='".$bi['ref_idpemegang']."'  where id='".$isi['Id']."'"; $cek .= $aqry2; 
				$qry2 = mysql_query($aqry2);
				if ($qry2){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".							
							" ref_idpemegang='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry2 = mysql_query($aqry);
						
					}
				}				
			}
		}
		return array('err'=>$err,'cek'=>$cek);		
	}
	function simpanPemegang2(){
		$err=''; $cek='';
		$ids= $_POST[$this->Prefix.'_cb'];		
		$idpegawai = $_GET['idpegawai'];		
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 			
				$aqry = "update sensus set ref_idpemegang2='$idpegawai', ref_idpemegang2_lama='".$bi['ref_idpemegang2']."'  where id='".$ids[$i]."'"; $cek .= $aqry; 
				$qry=mysql_query($aqry);
				if ($qry){
					if($Sensus_idplh != ''){						
						$aqry = "update buku_induk set ".							
							" ref_idpemegang2='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);						
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh==''?
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh' and (error='' or error is null)";
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set ref_idpemegang2='$idpegawai', ref_idpemegang2_lama='".$bi['ref_idpemegang2']."'  where id='".$isi['Id']."'"; $cek .= $aqry2; 
				$qry2 = mysql_query($aqry2);				
				if ($qry2){
					if($Sensus_idplh != ''){						
						$aqry = "update buku_induk set ".							
							" ref_idpemegang2='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry2 = mysql_query($aqry);
						
					}
				}
			}
		}
		return array('err'=>$err,'cek'=>$cek);		
	}
	function simpanPenanggung(){
		$err=''; $cek='';
		$ids= $_POST[$this->Prefix.'_cb'];		
		$idpegawai = $_GET['idpegawai'];
		$semua = $_REQUEST['cbxsemua'];	$cek.='semua='.$semua;	
		$sesi = $_REQUEST['sesi'];
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
		if($semua == ''){
			for($i = 0; $i<count($ids); $i++){
				$old = mysql_fetch_array(mysql_query(
					"select * from sensus where id='".$ids[$i]."'"
				));
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$old['idbi']."'"
				)); 			
				$aqry = "update sensus set ref_idpenanggung='$idpegawai', ref_idpenanggung_lama='".$bi['ref_idpenanggung']."'  where id='".$ids[$i]."'"; $cek .= $aqry; 
				$qry=mysql_query($aqry);
				if ($qry){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".							
							" ref_idpenanggung='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry = mysql_query($aqry);
					}
				}
			}
		}else{
			$aqry = $Sensus_idplh==''?
				"select * from sensus where sesi='$sesi' and (error='' or error is null)":
				"select * from sensus where ref_idsensusscan='$Sensus_idplh'  and (error='' or error is null) ";
			$qry = mysql_query($aqry);
			while($isi = mysql_fetch_array($qry)){
				$bi = mysql_fetch_array(mysql_query(
					"select * from buku_induk where id='".$isi['idbi']."'"
				)); 			
				$aqry2 = "update sensus set ref_idpenanggung='$idpegawai', ref_idpenanggung_lama='".$bi['ref_idpenanggung']."'  where id='".$isi['Id']."'"; $cek .= $aqry2; 
				$qry2=mysql_query($aqry2);				
				if ($qry2){
					if($Sensus_idplh != '') {						
						$aqry = "update buku_induk set ".							
							" ref_idpenanggung='$idpegawai' ".							
							" where id ='".$old['idbi']."'";  $cek .= ' update bi='.$aqry; 
						$qry2 = mysql_query($aqry);
					}
				}
			}
		}
		return array('err'=>$err,'cek'=>$cek);		
	}	
	function insertSensus(){
		global $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content='';
		$UID = $_COOKIE['coID'];
		$fmSKPD = $_REQUEST[$this->Prefix."SkpdfmSKPD"];// $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_REQUEST[$this->Prefix."SkpdfmUNIT"]; //$_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_REQUEST[$this->Prefix."SkpdfmSUBUNIT"];//$_COOKIE['cofmSUBUNIT'];
		$fmSEKSI = $_REQUEST[$this->Prefix."SkpdfmSEKSI"];//$_COOKIE['cofmSEKSI'];
		$kode = $_REQUEST['kode'];
		$sesi = $_REQUEST['sesi'];
		$tgl_sensus = $_REQUEST['tgl_sensus'];
		$tahun_sensus = $_REQUEST['tahun_sensus'];
				
		//get id bi 
		$aqry = "select  year(tgl_buku) as thn_buku, buku_induk.* from buku_induk where idall2 = '$kode'"; $cek.=$aqry;
		$bi = mysql_fetch_array (mysql_query( 
			$aqry
		));
		$idbi = $bi['id'];
		if ( $err=='' && ( $idbi == '' || $kode=='')) $err = "Barang Tidak Ada!";
		
		//cek dinas
		$cek .= ' sk= '. $fmSKPD; 
		if( $err=='' && $bi['c']!=$fmSKPD ) $err = " Bidang Tidak Sama!";
		if( $err=='' && $bi['d']!=$fmUNIT ) $err = " SKPD Tidak Sama!";
		if( $err=='' && $bi['e']!=$fmSUBUNIT ) $err = "UNIT Tidak Sama!";
		if( $err=='' && $bi['e1']!=$fmSEKSI ) $err = "SUB UNIT Tidak Sama!";
		
		//cek hari ini sudah disensus!
		//$aqry = "select count(*)as cnt from sensus where idbi = '$idbi' and year(tgl)=year(now()) and sesi=''"; $cek.=$aqry;
		$aqry = "select count(*)as cnt from sensus where idbi = '$idbi' 			
			and tahun_sensus='".$tahun_sensus."' and sesi=''"; $cek.=$aqry;
		$get2 = mysql_fetch_array (mysql_query( $aqry ));
		if($err=='' && $get2['cnt'] > 0) $err = "Barang sudah disensus pada tahun yang sama! ";//.$get2['cnt'];
		
		//cek sedang discan 
		$aqry = "select sesi,count(*)as cnt from sensus where idbi = '$idbi' 
			and sesi = '$sesi'"; $cek.=$aqry;
		$get2 = mysql_fetch_array (mysql_query( $aqry ));
		if($err=='' && $get2['cnt'] > 0) {
			$err = "Barang sudah di scan! ";
			/*if($get2['sesi'] == $sesi ){
				$err = "Barang sudah di scan! ";
			}else{
				//$err = "Barang sudah di scan di tempat lain! ";
			}*/
		}
		
		//cek taun perolehan : barang yg disacan hanya barang dgn tahun perolehan < tahun sensus
		//if( $err=='' && $bi['thn_perolehan'] >= $tahun_sensus){
		if( $err=='' && $bi['thn_perolehan'] >= $tahun_sensus){
			$err = "Tahun Perolehan harus lebih kecil dari tahun sensus!";
		}
		if( $err=='' && $bi['thn_buku'] >= $tahun_sensus ) $err = "Tahun Tanggal Buku harus lebih kecil dari tahun sensus!";
		
		$Sensus_idplh = $_REQUEST['Sensus_idplh'];
					
		$kondisi_awal = $bi['kondisi'];
		if ($err == '' ){
			if($Sensus_idplh==''){//baru
				$aqry = "insert into sensus (ada, kondisi, tgl, idbi, sesi, uid, kondisi_awal, kode, tgl_update) values (1, '$kondisi_awal' , now(),'$idbi','$sesi','$UID','$kondisi_awal', '$kode', now() )"; $cek.= $aqry;	
			}else{//edit
				$aqry = "insert into sensus (ref_idsensusscan, ada, kondisi, tgl, idbi,  uid, kondisi_awal, kode, tgl_update) values ('$Sensus_idplh', 1, '$kondisi_awal' , now(),'$idbi','$UID','$kondisi_awal', '$kode', now() )"; $cek.= $aqry;
			}
			
			$qry = mysql_query($aqry);
			if($qry==FALSE) $err = "Gagal simpan ke daftar Sensus!";
		}else{
			if($Sensus_idplh==''){//baru
				$aqry = "insert into sensus ( tgl, kode, error, sesi, uid, kondisi_awal, tgl_update) values (now(),'$kode','$err','$sesi','$UID','$kondisi_awal', now() )"; $cek.= $aqry;
			}else{//edit
				$aqry = "insert into sensus ( ref_idsensusscan, tgl, kode, error, uid, kondisi_awal, tgl_update) values ('$Sensus_idplh', now(),'$kode','$err','$UID','$kondisi_awal', now() )"; $cek.= $aqry;
			}
			$qry = mysql_query($aqry);
		}
		
		return array('err'=>$err,'cek'=>$cek);	
	}
	
	function set_selector_other($tipe){
		global $HTTP_COOKIE_VARS;
				
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'entryAda':{
				$get = $this->entryAda();
				$err= $get['err']; 
				$cek = $get['cek'];			
				$content = $get['content'];													
				break;
			}
			case 'simpanEntryAda':{
				//$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->simpanEntryAda();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'pilihkondisi':{
				$get = $this->setFormPilihKondisi();
				$err= $get['err']; 
				$cek = $get['cek'];			
				$content = $get['content'];													
				break;
			}			
			case 'entryCatatan':{
				$get = $this->entryCatatan();
				$err= $get['err']; 
				$cek = $get['cek'];			
				$content = $get['content'];													
				break;
			}
			case 'simpankondisi':{
				//$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->simpanKondisi();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}			
			case 'simpanEntryCatatan':{
				//$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->simpanEntryCatatan();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'simpanpenanggung':{
				//$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->simpanPenanggung();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'simpanpemegang':{				
				$get= $this->simpanPemegang();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'simpanpemegang2':{			
				$get= $this->simpanPemegang2();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'insertsensus':{
				
				$get= $this->insertSensus();
				$err= $get['err']; 
				$cek = $get['cek'];
				//$content = 'tets';
				sleep(2);
				break;
			}
			case 'simpanruang':{
				//$cbid= $_POST[$this->Prefix.'_cb'];				
				$get= $this->simpanRuang();
				$err= $get['err']; 
				$cek = $get['cek'];								
				break;
			}
			case 'insertsensusmanual':{
				
				$get= $this->insertSensusManual();
				$err= $get['err']; 
				$cek = $get['cek'];
				//$content = 'tets';
				break;
			}
			case 'entryBarang':{				
				$get= $this->setEntryBarang();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];												
				break;
			}						
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}
		//return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json );
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	function setDaftar_hal($jmlData){
		global $Main;
		$elhal = $this->Prefix.'_hal';
		$hal = cekPOST($this->Prefix.'_hal');
		$pagePerHal = $this->pagePerHal ==''? $Main->PagePerHal : $this->pagePerHal;
		return 
			"<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
			<tr><td width='90'>
			<table><tr>
			<td align=center style='width:20'><input type='checkbox' id='cbxsemua' name='cbxsemua' ></td>
			<td align=center style='padding:4'>Pilih Semua</td>
			</tr></table>
			</td>
			<td align=center style='padding:4'>".
				Halaman2b(	$jmlData, $pagePerHal, $elhal, $hal, 5, $this->Prefix.'.gotoHalaman').
					
					
			"</td></tr></table>";
			
	}
function insertSensusManual(){
		global $HTTP_COOKIE_VARS,$Main;
		$cek = ''; $err=''; $content='';
		$UID = $_COOKIE['coID'];
		$fmSKPD = $_REQUEST["fmSKPD"];// $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_REQUEST["fmUNIT"]; //$_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_REQUEST["fmSUBUNIT"];//$_COOKIE['cofmSUBUNIT'];
		$fmSEKSI = $_REQUEST["fmSEKSI"];//$_COOKIE['cofmSEKSI'];
		$kode = $_REQUEST['kode'];
		$sesi = $_REQUEST['sesi'];
		$tgl_sensus = date('Y-m-d');
		$tgl_sensus_full = date('Y-m-d H:i:s');
		$tahun_sensus = $Main->thnsensus_default;
		$cidBI=$_POST['cidBI'];
		$idc=$tgl_sensus.' - ';
		$idscan='';
		$err='';
		for($i = 0; $i<count($cidBI); $i++)	{
		
			$idc.=$cidBI[$i].' ';
			$idbi=$cidBI[$i];
			if ($i==0)
			{
			$aqry="select * from sensus_scan where tahun_sensus='$tahun_sensus' and 
			a1='".$Main->DEF_KEPEMILIKAN."' and a='".$Main->DEF_PROPINSI."' and b='".$Main->DEF_WILAYAH.
			"' and c='".$fmSKPD."' and d='".$fmUNIT."'  and e='".$fmSUBUNIT."' and e1='".$fmSEKSI."' ";
		$dtscan = mysql_fetch_array (mysql_query( 
			$aqry
		));
		$idscan = $dtscan['Id'];
		if 	($idscan==''){
		
		$iqry="insert into sensus_scan (tgl,tahun_sensus,a1,a,b,c,d,e,e1) values(
		'$tgl_sensus','$tahun_sensus','".$Main->DEF_KEPEMILIKAN."','".$Main->DEF_PROPINSI.
		"','".$Main->DEF_WILAYAH."','".$fmSKPD."','".$fmUNIT."','".$fmSUBUNIT."','".$fmSEKSI."'
		)";
		mysql_query($iqry);
			$aqry1="select * from sensus_scan where tahun_sensus='$tahun_sensus' and 
			a1='".$Main->DEF_KEPEMILIKAN."' and a='".$Main->DEF_PROPINSI."' and b='".$Main->DEF_WILAYAH.
			"' and c='".$fmSKPD."' and d='".$fmUNIT."'  and e='".$fmSUBUNIT."' and e1='".$fmSEKSI."' ";
		$dtscan = mysql_fetch_array (mysql_query($aqry1));
		$idscan = $dtscan['Id'];
		if 	($idscan==''){
			$err='Tambah barang sensus gagal'.$aqry.' '.$iqry;
		}
		}
				
		}
			
									
		
		
		
		if ($err=='' && $idscan<>'' )
		{
		
		$sqry=" select count(*) as cnt from sensus where idbi='".$cidBI[$i]."' and tahun_sensus='$tahun_sensus'";
		$dtsensus = mysql_fetch_array (mysql_query($sqry));
		$cnt=$dtsensus['cnt'];
		if ($cnt<=0)
		{
		$iqry=" select * from buku_induk where id='".$cidBI[$i]."'  ";
		$dtbi = mysql_fetch_array (mysql_query($iqry));
		$idbi_sensus=$dtbi['id'];
		$kondisi_awal=$dtbi['kondisi'];
		$ref_idpemegang_lama=$dtbi['ref_idpemegang'];
		$ref_idpenanggung_lama=$dtbi['ref_idpenanggung'];
		$ref_idruang_lama=$dtbi['ref_idruang'];
		$ref_idpemegang2_lama=$dtbi['ref_idpemegang2'];
		$tahun_sensus_lama=$dtbi['tahun_sensus'];
		$tgl_lama=$dtbi['$tgl_sensus'];
		
		$isqry="insert into sensus (tgl,idbi,uid,kondisi_awal,tgl_lama,ref_idpemegang_lama,ref_idpenanggung_lama,ref_idruang_lama,tahun_sensus_lama,ref_idpemegang2_lama,ref_idsensusscan,tahun_sensus) values ('$tgl_sensus_full','$idbi_sensus','$UID',
		'$kondisi_awal','$tgl_lama','$ref_idpemegang_lama','$ref_idpenanggung_lama','$ref_idruang_lama',
		'$tahun_sensus_lama','$ref_idpemegang2_lama','$idscan','$tahun_sensus')";	
		mysql_query($isqry);
		$uqry="update buku_induk set tahun_sensus='$tahun_sensus',tgl_sensus='$tgl_sensus_full' where id='".$cidBI[$i]."'";				mysql_query($uqry);
		}
		}
		}
	
		return array('err'=>$err,'cek'=>$cek);	
	}	
function setEntryBarang(){
		$dt=array();
		
		$this->form_fmST = 0;
		
		$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		
		$dt['sesi'] = gen_table_session('sensus_usul','uid'); //generate no sesi
		
		$fm = $this->setFormCr($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
}
	function setFormCr($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(				
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
};
$SensusTmp = new SensusTmpObj();


?>