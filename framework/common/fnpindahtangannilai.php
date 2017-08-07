<?php


//include('daftarobj.php');

class PindahTanganNilaiObj extends DaftarObj2{
	var $Prefix = 'PindahTanganNilai';
	var $SHOW_CEK = TRUE;
	var $elCurrPage="HalDefault";
	var $TblName = 'v2_penghapusan_bmd';//'view_buku_induk2'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id', 'id_bukuinduk'); //daftar/hapus
	var $FieldSum = array('jml_harga');//array('( jml_harga + tot_pelihara + tot_pengaman)');
	var $SumValue = array('jml_harga');// + tot_pelihara + tot_pengaman)');
	var $FieldSum_Cp1 = array( 11, 10, 10);//berdasar mode
	var $FieldSum_Cp2 = array( 3, 3, 3);	
	var $checkbox_rowspan = 1;
	
	//page 
	var $PageTitle = 'Pemindahtanganan';
	var $PageIcon = 'images/pemindahtanganan_ico.gif';
	var $FormName = 'PindahTanganNilaiPageForm';
	//form
	var $form_width = 650;
	var $form_height = 300;		
	//cetak 
	var $Cetak_Judul = 'DAFTAR PENILAIAN BARANG MILIK DAERAH';
	var $Cetak_WIDTH = '30cm';
	
	/*
	function setSumHal_query($Kondisi, $fsum){
		return "select count(*) as cnt, sum(ifnull(jml_harga,0) + ifnull(tot_pelihara,0) + ifnull(tot_pengaman,0)) as sum_jml_harga from $this->TblName $Kondisi "; //echo $aqry;
	}
	*/	
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$menu = $_REQUEST['menu'];
		$tl = $_REQUEST['tl'];
		//$ada = $_REQUEST['ada'];
		$fmSKPD = $_COOKIE["cofmSKPD"];
		$fmUNIT = $_COOKIE["cofmUNIT"];
		$fmSUBUNIT = $_COOKIE["cofmSUBUNIT"];
		return		
			//PindahTanganNilaiSkpdfmSKPD
			"<input type='hidden' id='menu' name='menu' value='$menu'>".
			"<input type='hidden' id='tl' name='tl' value='$tl'>".			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".						
			"</div>".								
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			//"<div style='float:left;'></div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function setPage_Header(){		
		//return createHeaderPage($this->PageIcon, $this->PageTitle);
		/*$PT = $_REQUEST['PT'];
		if ($PT=='1') {
			$this->PageTitle= 'Pemindahtanganan';		
			$this->PageIcon = 'images/pemindahtanganan_ico.gif';
		}else{
			$this->PageTitle= 'Penghapusan';			
			$this->PageIcon = 'images/penghapusan_ico.gif';
		}*/
		return createHeaderPage($this->PageIcon, $this->PageTitle,  
			'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
		);
	}
	
	function setPage_TitleDaftar(){
			return 'Penghapusan';
	}	
	function setPage_TitlePage(){
			return 'Penghapusan';
	}
	function setTitle(){
		$title = 'Penilaian Pemindahtanganan';		
		return $title;
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		
		
		$judul = $this->Cetak_Judul;	
		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$judul</td>
			</tr>
			</table>".	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table>".*/
			"<br>";
	}
	function setPage_IconPage(){
		return 'images/penatausahaan_ico.gif';
	}	
	
	function setPage_HeaderOther(){	
		//$Pg = $_REQUEST['Pg'];
		$tl = $_REQUEST['tl'];
		
		
		//1=penjualan, 2=tukar menukar, 3= hibah, 4=penyertaan modal
		$style2 = "style='color:blue;'"; 
		$menubawah = "<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Usulan' >Usulan</a> |
			<A href=\"pages.php?Pg=PindahTanganNilai\" title='Penilaian Pemindahtanganan' $style2 >Penilaian</a>  |
			<A href=\"pages.php?Pg=PindahTanganSK\" title='Surat Keputusan Pemindahtanganan' >Surat Keputusan</a>  |
			<A href=\"index.php?Pg=10&bentuk=1\" title='Penjualan' >Penjualan</a>  |  
			<A href=\"index.php?Pg=10&bentuk=2\" title='Tukar Menukar' >Tukar Menukar</a>  |  
			<A href=\"index.php?Pg=10&bentuk=3\" title='Hibah' >Hibah</a>  |  
			<A href=\"index.php?Pg=10&bentuk=4\" title='Penyertaan Modal' >Penyertaan Modal</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>";
				
			
		
		//if ($PT == '1'){
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>".
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!--<A href=\"pages.php?Pg=PenghapusanPengguna&PT=1\" title='Penghapusan' $style0>PENGHAPUSAN</a> | -->
			<A href=\"#\" title='Mutasi' >MUTASI</a>  |  
			<!--<A href=\"pages.php?Pg=PenghapusanBmd&tl=1\" title='Pemusnahan' $style1>PEMUSNAHAN</a>  | -->
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Pemindah Tanganan' $style2>PEMINDAH TANGANAN</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>".
			$menubawah.
			"</table>";
		
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
			<!--<th class=\"th01\" rowspan='2'>$tampilHeaderHarga </th>-->
			<!--<th class=\"th01\" rowspan='2'>Ukuran Barang / Konstruksi (P,SP,D)</th>-->
			<th class=\"th01\" rowspan='2'>Keadaan Barang (B,KB,RB)</th>
			
			<th class=\"th02\" colspan='2'>Jumlah</th>					
			<!--<th class=\"th01\" rowspan='2'>Keterangan/ Tgl. Buku/Tahun Sensus</th>-->
			<th class=\"th01\" rowspan='2'>Nama Pengguna</th>
			<th class=\"th01\" rowspan='2'>Penilaian</th>
			<th class=\"th01\" rowspan='2'>No. Usulan Pemindahtanganan</th>
			
			<!--<th class=\"th01\" rowspan='2'>Bentuk Pemindahtanganan</th>-->
			</tr>
			<tr>
			<th class=\"th01\">No.</th>				
			$Checkbox
			<th class=\"th01\">Kode <br>Barang</th>
			<th class=\"th01\">Reg.</th>
			<th class=\"th01\"  width=\"100\">Nama / Jenis Barang</th>
			<th class=\"th01\"  width=\"100\">Merk / Tipe / Alamat</th>
			<th class=\"th01\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
			
			<th class=\"th01\">Barang</th>
			<th class=\"th01\">$tampilHeaderHarga </th>
			
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
		
		$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$isi['clama']}' and d='00'"));
		$pengguna = $get['nm_skpd'];
		$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$isi['clama']}' and d='{$isi['dlama']}' and e='00'"));
		$pengguna .= ' - '. $get['nm_skpd'];
		$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$isi['clama']}' and d='{$isi['dlama']}' and e='{$isi['dlama']}'"));
		$pengguna .= ' - '. $get['nm_skpd'];
		
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
				$ISI10 = number_format($isiKIB_A['luas'],2,',','.') .' m2';
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
			
			$get = mysql_fetch_array(mysql_query(
				"select * from v2_pemindahtanganan_sk where id_bukuinduk='{$isi['id_bukuinduk']}' and (sesi is null or sesi='' )"
			));
			$vnousulpt = $get['no_usulan'].'<br>'.TglInd($get['tgl_usul']);
				
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
			$Koloms[] =  array('align=center',$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']);			
			$Koloms[] = array('align=center', $isi['noreg']);			
			$Koloms[] = array('align=left', $isi['nm_barang']);
			$Koloms[] = array('align=left', $ISI5);
			$Koloms[] = array('align=left', $ISI6);
			$Koloms[] = array('align=left', $ISI7.'<br>'.$ISI10);
			
			$Koloms[] = array('align=center', $isi['thn_perolehan'].'<br>'.$Main->AsalUsul[$AsalUsul-1][1]);
			
			//$Koloms[] = array('align=right', $ukuran);
			
			//$Koloms[] = array('align=center', $Main->KondisiBarang[ $isi['kondisi_awal'] -1][1] );
			$Koloms[] = array('align=center', $Main->KondisiBarang[ $isi['kondisi'] -1][1] );
			
			$Koloms[] = array('align=right', '1 '.$isi['satuan']);
			$Koloms[] = array('align=right', $tampilHarga);
			//$Koloms[] = array('align=right', $ket);
			$Koloms[] = array('align=left', $pengguna);
			$Koloms[] = array('align=right', number_format( $isi['penilaian'],2,',','.' ) );
			$Koloms[] = array('align=left', $vnousulpt);
			//$Koloms[] = array('align=left', $Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1]);
			
			
		}
		
		return $Koloms;
	}
	
	
	function setDaftar_after_getrow($list_row, $isi, $no, $Mode, $TampilCheckBox,$RowAtr, $KolomClassStyle){
		$cetak = $Mode==2  || $Mode== 3;
		$cbxDlmRibu = FALSE;
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";	
		if($Mode == 1) $clRow = $no % 2 == 0 ?"row1":"row0"; 
		$det = Penatausahaan_GetListDet($isi['idawal'], $isi['f'], $isi['jml_harga'],$cetak, $cbxDlmRibu,$clRow, $clGaris, $TampilCheckBox);
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
					//array('3','Keterangan'),			
				);
				break;
			}
		}
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$kode_barang = $_REQUEST['kode_barang'];
		
		/*$fmSKPD = $Main->Pembantu_Pengelola[0];
		$fmUNIT= $Main->Pembantu_Pengelola[1];  
		$fmSUBUNIT= $Main->Pembantu_Pengelola[2];  
		*/		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Perolehan'),
			array('2','Keadaan Barang'),
			array('3','Tahun Buku')
		);
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');	
		$fmORDER2 = cekPOST('fmORDER2');
		$fmDESC2 = cekPOST('fmDESC2');	
		$fmORDER3 = cekPOST('fmORDER3');
		$fmDESC3 = cekPOST('fmDESC3');	
				
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
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx('SensusSkpd','100%',100,TRUE, $fmSKPD, $fmUNIT, $fmSUBUNIT) . 
				WilSKPD_ajx('PindahTanganNilaiSkpd') . 
			"</td>
			<td style='padding:6'>
				" . 
				//$Main->ListData->labelbarang .
				//"<script>barcode.loading();</script>".
				"<span style='color:red'>BARCODE</span><br>".				
				"<input type='TEXT' value='' 
					id='barcodePenghapusanPengguna_input' name='barcodePenghapusanPengguna_input'
					style='font-size:24;width: 379px;' 
					size='28' maxlength='28'
					
				><span id='barcodePenghapusanPengguna_msg' name='barcodePenghapusanPengguna_msg' ></span> ". //onkeyup='inputBarcode(this)'
					
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
						'fmFiltThnBuku',
						$fmFiltThnBuku,						
						" select year(tgl_buku)as thnbuku from $this->TblName  ".							
						" group by thnbuku ".					
						" order by thnbuku desc ",
						'thnbuku', 'thnbuku',
						'Semua Thn. Buku'
					).
					genComboBoxQry('fmFiltThnPerolehan',$fmFiltThnPerolehan,						
						" select thn_perolehan from $this->TblName ".												
						" group by thn_perolehan ".
						" order by thn_perolehan desc "
						,'thn_perolehan', 'thn_perolehan','Semua Thn. Perolehan'
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
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>Desc.".
					cmbArray('fmORDER2',$fmORDER2,$arrOrder,'--','').
					"<input $fmDESC2 type='checkbox' id='fmDESC2' name='fmDESC2' value='checked'>Desc.".
					cmbArray('fmORDER3',$fmORDER3,$arrOrder,'--','').
					"<input $fmDESC3 type='checkbox' id='fmDESC3' name='fmDESC3' value='checked'>Desc."
				),				
				$this->Prefix.".refreshList(true)");//.
			//"<input type='text' id='ada' name='ada' value='$ada'>";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		$arrKondisi[] = " (no_sk is not null and no_sk <> '') ";
		$arrKondisi[] = " tindak_lanjut='2' ";
		$arrKondisi[]= " penilaian>0 ";
		
		$barcodePenghapusanPengguna_input = $_REQUEST['barcodePenghapusanPengguna_input'];
		if (!empty($barcodePenghapusanPengguna_input)) $arrKondisi[] = " idall2='$barcodePenghapusanPengguna_input' ";
		
		$fmUNIT = $_POST[$this->Prefix.'SkpdfmUNIT']; 
		$fmSKPD = $_POST[$this->Prefix.'SkpdfmSKPD']; //$cek .= ' $fmSKPD='.$fmSKPD;
		$fmSUBUNIT = $_POST[$this->Prefix.'SkpdfmSUBUNIT'];
		if (!empty($fmSKPD) && $fmSKPD!='00' ) $arrKondisi[] = " clama = '".$fmSKPD."'";
		if (!empty($fmUNIT) && $fmUNIT!='00' ) $arrKondisi[] = " dlama = '".$fmUNIT."'";
		if (!empty($fmSUBUNIT) && $fmSUBUNIT!='00' ) $arrKondisi[] = " elama = '".$fmSUBUNIT."'";
		
		
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
		//$fmFiltThnSensus = cekPOST('fmFiltThnSensus');
		//if (!empty($fmFiltThnSensus))	$arrKondisi[] = " Year(tgl)  ='$fmFiltThnSensus' ";
		$menu = $_REQUEST['menu'];
		if($menu == 'diusulkan') $arrKondisi[] = " kondisi=3 ";
		
				
		
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		if($fmKONDBRG !=''){	$arrKondisi[] = "kondisi = $fmKONDBRG ";	}
		$kode_barang = $_REQUEST['kode_barang'];
		if($kode_barang != '') $arrKondisi[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		$tl = $_REQUEST['tl']; //tindak lanjut
		if($tl != '') $arrKondisi[] = " tindak_lanjut='$tl' ";
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 ? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$fmDESC2 = $_POST['fmDESC2'];
		$AscDsc2 = $fmDESC2 ? 'desc' : '';
		$fmORDER2 = $_POST['fmORDER2'];
		$fmDESC3 = $_POST['fmDESC3'];
		$AscDsc3 = $fmDESC3 ? 'desc' : '';
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
				
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		$Kondisi = join(' and ',$arrKondisi); 
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi; $cek .=$Kondisi;
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
		
	/*
	function setFormBaru(){
		global $SensusTmp, $Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		$json = TRUE;	//$ErrMsg = 'tes';
		$form_name = $this->Prefix.'_form';		
		
		$fmSKPD = $_REQUEST[$this->Prefix.'SkpdfmSKPD' ];
		$fmUNIT = $_REQUEST[$this->Prefix.'SkpdfmUNIT' ];
		$fmSUBUNIT = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT' ];
		//utk baru dari daftar belum cek
		if ($fmSKPD=='') $fmSKPD = $_REQUEST['fmSKPD'];		
		if ($fmUNIT=='') $fmUNIT = $_REQUEST['fmUNIT'];		
		if ($fmSUBUNIT=='') $fmSUBUNIT = $_REQUEST['fmSUBUNIT'];		
				
		if ($err=='' && ($fmSKPD == '' || $fmSKPD == '00' ) ) $err = "Bidang Belum Diisi!";
		if ($err=='' && ($fmUNIT == '' || $fmUNIT == '00' ) ) $err = "OPD Belum Diisi!";
		if ($err=='' && ($fmSUBUNIT == '' || $fmSUBUNIT == '00' ) ) $err = "Biro Belum Diisi!";
		
		
		
		$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT)."</div>";
					
		$sesi = gen_table_session('sensus','uid');
		
		$this->form_menubawah =			
			
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
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		$json = TRUE;	//$ErrMsg = 'tes';
		$form_name = $this->Prefix.'_form';		
		
		
				
		
		$SensusScan_cb  = $_REQUEST['SensusScan_cb'];
		
		$this->form_idplh = $SensusScan_cb[0];
		
		//ambil data sensus scan
		$aqry = "select * from sensus_scan where id ='$this->form_idplh' ";
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT)."</div>";
		$FormContent = "<div style='height:5px'>".$SensusTmp->genDaftarInitial($dt['c'], $dt['d'], $dt['e'], $dt['tahun_sensus'], $dt['tgl'])."</div>";
					
		$sesi = gen_table_session('sensus','uid');
		
		$this->form_menubawah =	
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
	*/
	
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
			"select * from v2_penghapusan_bmd  where concat(id,' ',id_bukuinduk)='".$cbid[0]."' "
		)); 
		
		$pindahtangan = mysql_fetch_array(mysql_query(
			"select * from v2_pemindahtanganan_sk where id_bukuinduk ='{$old['id_bukuinduk']}' and (sesi is null or sesi = '')"
		));
		if($err=='' && $pindahtangan['no_usulan']!='') $err = 'Data sudah ada di usulan '.$pindahtangan['no_usulan'].'!';
		
		if ($err==''){
			
			$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$old['clama']}' and d='00' and e='00' "));
			$bidang = $get['nm_skpd'];		
			$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$old['clama']}' and d='{$old['dlama']}' and e='00' "));		
			$unit = $get['nm_skpd'];
			$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='{$old['clama']}' and d='{$old['dlama']}' and e='{$old['elama']}' "));		
			$subunit = $get['nm_skpd'];		
			
					
			$barcode = 
				$old['a1'].'.'.$old['a'].'.'.$old['b'].'.'.
				$old['c'].'.'.$old['d'].'.'.substr($old['thn_perolehan'],2,2).'.'.$old['e'].'.'.
				$old['f'].'.'.$old['g'].'.'.$old['h'].'.'.$old['i'].'.'.$old['j'].'.'.$old['noreg'];
				
				
			
			$this->form_fields = array(				
				'bidang' => array(  'label'=>'BIDANG', 
					'value'=> $bidang."<input type='hidden' value='".$old['c']."' id='SensusEditSkpdfmSKPD' name='SensusEditSkpdfmSKPD' >" 
					, 'labelWidth'=>150, 'type'=>'' ),
				'unit' => array(  'label'=>'ASISTEN / OPD', 
					'value'=> $unit."<input type='hidden' value='".$old['d']."' id='SensusEditSkpdfmUNIT' name='SensusEditSkpdfmUNIT' >" 
					,  'type'=>'' ),			
				'subunit' => array(  'label'=>'BIRO / UPTD/B', 
					'value'=> $subunit."<input type='hidden' value='".$old['e']."' id='SensusEditSkpdfmSUBUNIT' name='SensusEditSkpdfmSUBUNIT' >" 
					,  'type'=>'' ),
				'barcode' => array(  'label'=>'Kode Barang', 'value'=> $barcode,  'type'=>'' ),			
				'nmbrg' => array(  'label'=>'Nama Barang', 'value'=> $old['nm_barang'],  'type'=>'' ),			
				'Tahun' => array(  'label'=>'Tahun Perolehan', 'value'=> $old['thn_perolehan'],  'type'=>'' ),						
				'tgl'=> array(  'label'=>'Tanggal Buku', 
					'value'=> TglInd($old['tgl_buku'])//createEntryTgl3( $old['tgl'], 'tgl', TRUE)
					. "<input type='hidden' id='tgl_sensus' name='tgl_sensus' value='".$old['tgl_buku']."' >"
					, 'type'=>'' 
				),				
				'kondisi' => array(  'label'=>'Kondisi', 
					'value'=> $Main->KondisiBarang[$old['kondisi']-1][1],//cmb2D_v2('kondisi', $old['kondisi'], $Main->KondisiBarang), 
					'type'=>'' 
				),			
				'harga_perolehan' => array(  'label'=>'Harga Perolehan Rp.', 
					'value'=> //$old['nm_gedung'].' / '.$old['nm_ruang'].					
						number_format($old['harga'],2,',','.')
					,  'type'=>'' 
				),			
				'penilaian' => array(  'label'=>'Penilaian Rp.', 
					'value'=> $old['penilaian']
					,  'type'=>'number' 
				),	
				/*'bentuk_pemindahtanganan' => array(  'label'=>'Bentuk Pemindahtanganan', 
					'value'=> cmb2D_v2('bentuk_pemindahtanganan', $old['bentuk_pemindahtanganan'], $Main->BentukPemindahtanganan), 
					'type'=>'' 
				),*/	
				
				
				
			);	
			$FormContent = $this->setForm_content();
			$this->form_menubawah =			
				"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanPenilaian()' >".
				"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			$form = centerPage(
				"<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$FormContent,
					450,
					250,
					'Penilaian Pemindahtanganan',
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
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);		
	}
	
	
	function simpanPenilaian(){
		global $Main, $HTTP_COOKIE_VARS;		
		$cek = ''; $err=''; $content=''; 
		
		$UID = $_COOKIE['coID'];
		$id = $_REQUEST[$this->Prefix."_idplh"];
		
		$old= mysql_fetch_array(mysql_query(
			" select * from  penghapusan_usul_bmd_det where  concat(id,' ',id_bukuinduk)='$id'  "
		));
		
		$penilaian = $_REQUEST['penilaian'];
		//$bentuk = $_REQUEST['bentuk_pemindahtanganan'];
		
		if($err=='' && ($penilaian ==0 || $penilaian=='')) $err = 'Penilaian belum diisi!';
		//if($err=='' && ($bentuk ==0 || $bentuk=='' )) $err = 'Bentuk Pemindahtanganan belum dipilih!';
		
		if($err=='' ){
			$aqry = "update penghapusan_usul_bmd_det".
				" set penilaian = '$penilaian'".//, bentuk_pemindahtanganan = '$bentuk'".
				" where concat(id,' ',id_bukuinduk)='$id'  "; $cek .= $aqry;
			$qry = mysql_query($aqry);	
		}
		
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);			
	}
	function setMenuEdit(){		
		$tl = $_REQUEST['tl'];
		
		/*if($tl==1){
			return 
				"<td>".genPanelIcon("javascript:".$this->Prefix.".BA()","new_f2.png",'BA',"Berita Acara Pemusnahan")."</td>".
				'';
		}else {*/
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","new_f2.png",'Penilaian',"Penilaian Pemindahtanganan")."</td>".
			'';
		//}
		 
	}
	
	function getJmlCetakKKerja(){
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
		
		$aqry = //"select count(*) from ";
			//" select count(*) as cnt from $tblkib where a1='11' and a='10' and b='00' $kondSKPD and status_barang <> 3 and status_barang <> 4 and status_barang <> 5 and tgl_buku<='2012-12-31' and (tahun_sensus <> 2013 or tahun_sensus='' or tahun_sensus is null) order by a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg limit 0, 10";
			" select count(*) as cnt from $tblkib where a1='11' and a='10' and b='00' $kondSKPD and status_barang <> 3 and status_barang <> 4 and status_barang <> 5 
				and tgl_buku<='2012-12-31' 
				order by a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg limit 0, 10";
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
			
			case 'formEdit':{
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];																
				break;
			}
			
			case 'simpanPenilaian':{
				$fm = $this->simpanPenilaian();				
				$cek = $fm['cek'];
				$err = $fm['err'];				
				$content = $fm['content'];																
				break;
			}
							
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
			
		}		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$PindahTanganNilai = new PindahTanganNilaiObj();

?>