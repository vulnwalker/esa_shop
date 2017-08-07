<?php

//class SensusTidakTercatatObj extends DaftarObj2{
class SensusScanObj extends DaftarObj2{
	var $Prefix = 'SensusScan'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'sensus_scan'; //daftar
	var $TblName_Hapus = 'sensus_scan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 11, 10,10);//berdasar mode
	var $FieldSum_Cp2 = array( 2, 2,2);	
	
	var $checkbox_rowspan = 1;
	var $totalCol = 15; //total kolom daftar
	var $fieldSum_lokasi = array( 11,13);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='barang_tidak_tercatat.xls';
	var $Cetak_Judul = 'Barang Tidak Tercatat';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Sensus Barang';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= 25;
	var $FormName = 'adminFormSensusScan';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $totbi = 0;
	var $tottada = 0;
	var $totada = 0;
	var $totbaik = 0;
	var $totkb = 0;
	var $totrb = 0;
	var $totbelumcek = 0;

	
	function setTitle(){
		return 'Hasil Scan';
	}
	function setCetakTitle(){
		return	"<DIV ALIGN=CENTER>$this->Cetak_Judul ";
	}
	
	function setMenuEdit(){		
		return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:"."Sensus.Edit2()","edit_f2.png","Edit", 'Edit')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"";
			
	}
	
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Cetak_kerja2(\"$Op\")","print_f2.png",'K Kerja',"Cetak Kertas Kerja ")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua")."</td>"
			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'K. Kerja',"Cetak Kertas Kerja per Semua")."</td>"
			;
		
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
		}
		//if($tipe='tipe')$styleMenu2_4 = " style='color:blue;' ";
		//if($Pg=='SensusTidakTercatat') $styleMenu2_8 = " style='color:blue;'";
		
		switch ($Pg){
			case 'sensus' : $styleMenu = " style='color:blue;' ";	break;
			case 'SensusTidakTercatat' : $styleMenu = " style='color:blue;' ";  $styleMenu2_8 = " style='color:blue;'";	break;
			case 'SensusScan': $styleMenu2_9 = " style='color:blue;'"; break;
		}
		
		$menu_pembukuan =
		$Main->MODUL_PEMBUKUAN?
		" <A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Pembukuan' $styleMenu14>PEMBUKUAN</a> ":'';
		$menu_peta = 
		$Main->MODUL_PETA ?
		 " <A href=\"pages.php?Pg=map&SPg=03\" title='Peta' target='_blank'>PETA</a> |" : '';				
		
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
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' style='color:blue;'>SENSUS</a> |
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
			<A href=\"pages.php?Pg=SensusHasil2\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>   | 			
			<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   |
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>"
			;
			
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
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . "
			</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					'Tampilkan : '
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi -------------------------						
		//$arrKondisi[] = " h='00'";
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
				
		//order ---------------------------
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
			
		
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//$Limit = '';
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
					</script>";
		return "
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				<script src='js/sensus.js' type='text/javascript'></script>
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						-->".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		global $Main;
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
		$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
		$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
		$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		//checkAll4(25,'Sensus_cb','Sensus_toggle','Sensus_jmlcek');
		$tampilCheckbox =  $cetak ? "" : "	<th class='th01' width='30'><input type='checkbox' name='".$this->Prefix."_toggle' id='".$this->Prefix."_toggle' value='' 
				onClick=\"checkAll4(".$Main->PagePerHal.",'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');".
				"Penatausaha.checkAll($Main->PagePerHal,'cb','toggle','boxchecked')".
				"\" ></th>";			
					
		
		$kolomMerk = 'Merk / Tipe / Alamat';
		$headerTable =
			"<tr>
				<!--<th class=\"th02\" colspan='". ($cetak? "1": "2") ."' width=''>Nomor</th>-->
				<th class=\"th01\" width='30'>No.</th>				
				$tampilCheckbox
				<th class=\"th01\" width='60'>Tanggal Sensus</th>
				<th class=\"th01\" width='60'>Tahun Sensus</th>
				<th class=\"th01\" width='200'>BIDANG</th>
				<th class=\"th01\" width='200'>SKPD</th>
				<th class=\"th01\" width='200'>UNIT</th>
				<th class=\"th01\" width='200'>SUB UNIT</th>
			</tr>
			".
			"";
				/*"<tr>
				<th class=\"th02\" colspan='". ($cetak? "3": "4") ."'>Nomor</th>
				<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>
				<!--<th class=\"th01\" rowspan='2'>Bahan</th>
				<th class=\"th01\" rowspan='2'>Cara Perolehan / Status Barang</th>-->
				<th class=\"th01\" rowspan='2'>Tahun Perolehan/<br>Keadaan Barang (B,KB,RB)</th>
				<th class=\"th01\" rowspan='2'>Gedung/Ruang</th>
				<th class=\"th01\" rowspan='2'>Penanggung Jawab Barang</th>
				<th class=\"th02\" colspan='3'>Jumlah</th>
				
				<th class=\"th01\" rowspan='2'>Catatan</th>
				<th class=\"th01\" rowspan='2' style='min-width:100;'>Tahun/<br>Tanggal/<br>Petugas Sensus</th>
				$tampilDok
				$vBidang
				$tampilCbxKeranjangHead
				</tr>
				<tr>
				<th class=\"th01\">No.</th>				
				$tampilCheckbox
				<th class=\"th01\">Kode <br>Barang</th>
				<th class=\"th01\">Reg.</th>
				<th class=\"th01\"  width=\"100\">Nama / Jenis Barang</th>
				<th class=\"th01\"  width=\"100\">$kolomMerk</th>
				<th class=\"th01\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin / Bahan</th>
				<th class=\"th01\" width='70'>Barang</th>
				<th class=\"th01\"> Harga Satuan </th>
				<th class=\"th01\"> $tampilHeaderHarga </th>
				
				
				</tr>
				";*/
		return $headerTable;
	}
	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		/*$brg = mysql_fetch_array(mysql_query(
			"select * from ref_barang where concat(f,g,h,i,j)='".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."'"
		));
		$vnmbarang = $brg['nm_barang'];
		
		switch($isi['f']){
			case '01': {
				$alm = '';
				$alm .= ifempty($isi['alamat'],'-');		
				$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
				$alm .= $isi['alamat_kec'] != ''? '<br>Kec. '.$isi['alamat_kec'] : '';
				$alm .= $isi['alamat_kota'] != ''? '<br>'.$isi['alamat_kota'] : '';
				$vmerk = $alm;
				
				$vnosertifikat = $isi['sertifikat_no'];
				//$vnosertifikat = $isi['sertifikat_tgl'];				
				break;
			}
			case '02': {
				$vmerk = $isi['merk']; 
				$vnosertifikat = $isi['no_pabrik'].' / '.$isi['no_rangka'].' / '.$isi['no_mesin'].' / '.$isi['bahan'];
				break;
			}
			case '03': {
				$alm = '';
				$alm .= ifempty($isi['alamat'],'-');		
				$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
				$alm .= $isi['alamat_kec'] != ''? '<br>Kec. '.$isi['alamat_kec'] : '';
				$alm .= $isi['alamat_kota'] != ''? '<br>'.$isi['alamat_kota'] : '';
				$vmerk = $alm;
				
				$vnosertifikat = '';
				break;
			}
			case '04': {
					$alm = '';
					$alm .= ifempty($isi['alamat'],'-');		
					$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
					$alm .= $isi['alamat_kec'] != ''? '<br>Kec. '.$isi['alamat_kec'] : '';
					$alm .= $isi['alamat_kota'] != ''? '<br>'.$isi['alamat_kota'] : '';
					$vmerk = $alm;
					
					$vnosertifikat = '';
					break;
			}
			case '05': {
					$vmerk = '';
					$vnosertifikat = '';
					break;
			}
			case '06': {
					$alm = '';
					$alm .= ifempty($isi['alamat'],'-');		
					$alm .= $isi['alamat_kel'] != ''? '<br>Kel. '.$isi['alamat_kel'] : '';
					$alm .= $isi['alamat_kec'] != ''? '<br>Kec. '.$isi['alamat_kec'] : '';
					$alm .= $isi['alamat_kota'] != ''? '<br>'.$isi['alamat_kota'] : '';
					$vmerk = $alm;
					
					$vnosertifikat = '';
					break;
			}
		}
		
		$vthnperolehan = $isi['thn_perolehan'];
		$vkondisi = $Main->KondisiBarang[$isi['kondisi']-1][1];
		$pgw = mysql_fetch_array(mysql_query("select * from ref_pegawai where id='".$isi['ref_idpemegang2']."'"));
		$vPenanggungJawab = $pgw['nip'].'<br>'.$pgw['nama'];
		$vjml_barang = number_format( $isi['jml_barang'],0, ',', '.');
		$vjml_harga = number_format( $isi['jml_harga'],2, ',', '.');
		$vharga = number_format( $isi['harga'],2, ',', '.');
		$vcatatan = $isi['ket'];		
		$vtglsensus = TglInd($isi['tgl_sensus']);
		$vthnsensus = $isi['tahun_sensus'];
	 	$vpetugas = $isi['petugas'];
		
		$rng = mysql_fetch_array(mysql_query("select * from ref_ruang where id='".$isi['ref_idruang']."'"));
		$vRuang = $rng['nm_ruang'];
		$gdg = mysql_fetch_array(mysql_query("select * from ref_ruang where concat(c,d,e,p,q)='".$rng['c'].$rng['d'].$rng['e'].$rng['p']."0000'"));
		$vGedung = $gdg['nm_ruang'];
		*/
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		
		$aqry = "select * from ref_skpd where c='".$isi['c']."' and d='00' and e='00' and e1='".$kdSubUnit0."' ";
		$get = mysql_fetch_array(mysql_query($aqry));
		$skpd = $get['nm_skpd'];
		
		$aqry = "select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='00' and e1='".$kdSubUnit0."' ";
		$get = mysql_fetch_array(mysql_query($aqry));
		$opd = $get['nm_skpd'];
		
		$aqry = "select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$kdSubUnit0."' ";
		$get = mysql_fetch_array(mysql_query($aqry));
		$biro = $get['nm_skpd'];

		$aqry = "select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."' ";
		$get = mysql_fetch_array(mysql_query($aqry));
		$seksi = $get['nm_skpd'];
		
		$Koloms[] = array('align=right', $no.'.' );
		//if ($cetak == FALSE ) $Koloms[] = array('align=center', "<input type='checkbox' $Checked  id='cb$cb' name='cidBI[]' value='{$isi['id']}' onClick='isChecked(this.checked);' />"); 
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=center', TglInd($isi['tgl']) );			
		$Koloms[] = array('align=center', $isi['tahun_sensus']);			
		$Koloms[] = array('align=left', $skpd);
		$Koloms[] = array('align=left', $opd);
		$Koloms[] = array('align=left', $biro);		
		$Koloms[] = array('align=left', $seksi);		

		return $Koloms;
	}
	
	function setDaftar_After($no=0, $ColStyle=''){
		$ListData = '';		
		return $ListData;
	}
	
	/*function simpan(){
		$Simpan = FALSE; $errmsg ='';$content=''; $cek='';
		
		
		
		return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}*/
	
	function simpan2(){
		$cek = ''; $err=''; $content=''; 
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$fmST = $_REQUEST['SensusTidakTercatat_fmST'];
		$id = $_REQUEST['SensusTidakTercatat_idplh'];
		$uid = $HTTP_COOKIE_VARS['coID'];
		
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$tes = explode('.',$fmIDBARANG);
		$f = $tes[0];
		$g = $tes[1];
		$h = $tes[2];
		$i = $tes[3];
		$j = $tes[4];
								
		$noreg 			= $_REQUEST['noreg'];
		$thn_perolehan 	= $_REQUEST['thn_perolehan'];
		$jml_barang 	= $_REQUEST['jml_barang'];
		$satuan 		= $_REQUEST['satuan'];
		$jml_harga 		= $_REQUEST['jml_harga'];
		$asal_usul 		= $_REQUEST['asal_usul'];
		$kondisi 		= $_REQUEST['kondisi'];
		$koordinat_gps 	= $_REQUEST['koordinat_gps'];
		$koord_bidang 	= $_REQUEST['koord_bidang'];
		
		$luas 		= $_REQUEST['luas'];
		$alamat 	= $_REQUEST['alamat'];
		$alamat_kel = $_REQUEST['alamat_kel'];
		$alamat_kec = $_REQUEST['alamat_kec'];
		$alamat_b	= $_REQUEST['alamat_b'];
		$ket 		= $_REQUEST['ket'];
		$bersertifikat = $_REQUEST['bersertifikat'];
		$sertifikat_tgl= $_REQUEST['sertifikat_tgl'];
		$sertifikat_no = $_REQUEST['sertifikat_no'];
		$merk = $_REQUEST['merk'];
		$tgl_sensus 	= $_REQUEST['tgl_sensus'];
		$petugas 		= $_REQUEST['petugas'];
		$tahun_sensus 	= $_REQUEST['tahun_sensus'];
		$no_pabrik 		= $_REQUEST['no_pabrik'];
		$no_rangka 		= $_REQUEST['no_rangka'];
		$no_mesin 		= $_REQUEST['no_mesin'];
		$no_polisi 		= $_REQUEST['no_polisi'];
		$no_bpkb 		= $_REQUEST['no_bpkb'];
		$ukuran 		= $_REQUEST['ukuran'];
		$bahan 			= $_REQUEST['bahan'];
		$buku_judul 	= $_REQUEST['buku_judul'];
		$buku_spesifikasi = $_REQUEST['buku_spesifikasi'];
		$tgl_buku = $_REQUEST['tgl_buku'];
		$seni_asal_daerah = $_REQUEST['seni_asal_daerah'];
		$seni_pencipta 	= $_REQUEST['seni_pencipta'];
		$seni_bahan 	= $_REQUEST['seni_bahan'];
		$hewan_jenis 	= $_REQUEST['hewan_jenis'];
		$hewan_ukuran 	= $_REQUEST['hewan_ukuran'];
		$ref_idpemegang2 = $_REQUEST['ref_idpengadaan1'];
		$ref_idruang 	= $_REQUEST['ref_idruang'];
		
		if($jml_barang < 1) $err = 'Jumlah Barang Paling Sedikit 1!';
		
		if($err==''){
			$get = $this->simpan($fmST, 'barang_tidak_tercatat', array('Id'), array($id),
				"a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan,jml_barang,satuan,harga,jml_harga,asal_usul,kondisi,alamat,alamat_kel,alamat_kec,alamat_b,
				luas,merk,ukuran,bahan,no_pabrik,no_rangka,no_mesin,no_polisi,no_bpkb,bersertifikat,sertifikat_tgl,sertifikat_no,
				buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,petugas,tgl_sensus,
				tahun_sensus,ket,uid,koordinat_gps,koord_bidang,ref_idpemegang2,ref_idruang",					
				array(
					'a1'=>"'$Main->DEF_KEPEMILIKAN'",'a'=>"'$Main->DEF_PROPINSI'",'b'=>"'$Main->DEF_WILAYAH'",
					'c'=>"'$c'",'d'=>"'$d'",'e'=>"'$e'",'e1'=>"'$e1'",
					'f'=>"'$f'",'g'=>"'$g'",'h'=>"'$h'",'i'=>"'$i'",'j'=>"'$j'",
					'noreg'=>"'$noreg'",
					'thn_perolehan'=>"'$thn_perolehan'",
					'jml_barang'=>"'$jml_barang'",
					'satuan'=>"'$satuan'",
					'tgl_update'=>"now()",
					'harga'=>"'".$jml_harga."'",
					'jml_harga'=>"'".($jml_harga * $jml_barang)."'",
					'asal_usul'=>"'$asal_usul'",
					'kondisi'=>"'$kondisi'",
					'koordinat_gps'=>"'$koordinat_gps'",
					'koord_bidang'=>"'$koord_bidang'",
					'alamat'=>"'$alamat'",
					'alamat_kel'=>"'$alamat_kel'",
					'alamat_kec'=>"'$alamat_kec'",
					'alamat_b'=>"'$alamat_b'",
					'luas'=>"'$luas'",//tambah
					'merk'=>"'$merk'",
					'no_pabrik'=>"'$no_pabrik'",
					'no_rangka'=>"'$no_rangka'",
					'no_mesin'=>"'$no_mesin'",
					'no_polisi'=>"'$no_polisi'",
					'no_bpkb'=>"'$no_bpkb'",
					'ukuran'=>"'$ukuran'",//Tambah
					'bahan'=>"'$bahan'",//Tambah
					'bersertifikat'=>"'$bersertifikat'",
					'sertifikat_tgl'=>"'$sertifikat_tgl'",
					'sertifikat_no'=>"'$sertifikat_no'",
					'buku_judul'=>"'$buku_judul'",
					'buku_spesifikasi'=>"'$buku_spesifikasi'",
					'tgl_buku'=>"'$tgl_buku'",//tambah
					'seni_asal_daerah'=>"'$seni_asal_daerah'",
					'seni_pencipta'=>"'$seni_pencipta'",
					'seni_bahan'=>"'$seni_bahan'",
					'hewan_jenis'=>"'$hewan_jenis'",
					'hewan_ukuran'=>"'$hewan_ukuran'",
					'ket'=>"'$ket'",//tambah
					'uid'=>"'$uid'",//tambah
					'petugas'=>"'$petugas'",//tambah
					'tgl_sensus'=>"'$tgl_sensus'",//tambah
					'tahun_sensus'=>"'$tahun_sensus'",//tambah
					'ref_idruang'=>"'$ref_idruang'",//tambah
					'ref_idpemegang2'=>"'$ref_idpemegang2'",//tambah
				)
			);
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other($tipe){
	global $Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		switch($tipe){
			/*case 'formBaru2':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				//$json=FALSE;											
				break;
			}
			case 'formEdit2':{				
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				$json=FALSE;
				break;
			}
			
			case 'simpan' : {
				//($fmST, $tblsimpan='', $fieldKey='', $fieldKeyVal='', $fields = '', $fieldsval = '' )
				$get = $this->simpan2(); 
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				$json=TRUE;
				break;
			}
			*/
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$dt['tgl_sensus'] = date('Y-m-d');
		$dt['jml_barang'] = 1;
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdSEKSI'];
				
		$this->form_idplh = $cbid[0];
		//$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from barang_tidak_tercatat where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){	
		global $SensusTmp, $Main;
		global $fmIDBARANG, $fmIDREKENING;
		global $HTTP_COOKIE_VARS;
		global $bersertifikat;
	 	
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		$uid = $HTTP_COOKIE_VARS['coID'];
		$form_name = $this->Prefix.'_form';			
		
		$this->form_width = 500;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			//$this->form_caption = 'Barang Tidak Tercatat - Baru1';
			$nip = '';
			$Baru = 'Baru';
		}else{
			//$this->form_caption = 'Barang Tidak Tercatat - Edit';			
			$nip = $dt['nip'];
			$Baru = 'Edit';
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];				
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];				
		$fmIDBARANG = $dt['f']==''? '':  $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;//'01.01.01.02.01';
		$bersertifikat = $dt['bersertifikat'];
		$sertifikat_tgl = $dt['sertifikat_tgl'];
		$sertifikat_no = $dt['sertifikat_no'];
		
		
		//-- set visible entry u kib -----------------------------
		$fmkibavisible = "style='display:none'"; //untuk alamat
		$fmkibbvisible = "style='display:none'"; 
		$fmkibcvisible = "style='display:none'"; //untuk sertifikat
		$fmkibevisible = "style='display:none'"; 
		switch ( $dt['f']){
			case '01': $fmkibavisible = "style='display:block'"; $fmkibcvisible= "style='display:block'"; break;			
			case '02': $fmkibbvisible = "style='display:block'"; break;
			case '03': case '04': case '06':  $fmkibavisible = "style='display:block'"; break;
			case '05': $fmkibevisible = "style='display:block'"; break;
		}
		//ambil pegawai Pengurus Barang
		$read = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$dt['ref_idpemegang2']."'"));
		$select = mysql_fetch_array(mysql_query("SELECT* FROM ref_ruang WHERE id = '".$dt['ref_idruang']."'"));
		$gdg = mysql_fetch_array(
			   mysql_query("SELECT* FROM ref_ruang 
							WHERE c = '".$select['c']."' 
							And d = '".$select['d']."'
							And e ='".$select['e']."'
							And e1 ='".$select['e1']."'
							And p ='".$select['p']."' and q='0000'
							"));
		$this->form_fields = array(	
			'sertifikat' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Sertifikat', 'type'=>'merge' ),			
			'statussertifikat' => array(
				'label' => '',
				'value' => createEntryBersertifikat(
					'bersertifikat',
					'sertifikat_tgl',
					'sertifikat_no'),
				'type' => 'merge'
			),
			'tglsertifikat' => array(
				'label' => '&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat',
				'labelWidth'=>120,
				'value' =>	createEntryTgl("sertifikat_tgl",$sertifikat_tgl, $bersertifikat==1?"":"1",
					  'tanggal bulan tahun (mis: 1 Januari 1998)','','adminForm',1
					),
				'type' => ''
			),
			'no_sertifikat' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat', 
			'labelWidth'=>120,
				'value'=>txtField('sertifikat_no',$sertifikat_no,'100','20','text', $bersertifikat==1?"":"disabled") , 
				'type'=>'' 
			)
		);		
		$formmKIBC = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB A --------------------------------------------
		$this->form_fields = array(	
			'alamat' => array( 'label'=>'Letak/Alamat', 
				'value'=> "<textarea style='width:438;' id='alamat' name='alamat'>".$dt['alamat']."</textarea>", 
				'row_params'=>"valign='top'",
				'labelWidth'=>116, 'type'=>'' ),
			'alamat_kel' => array( 'label'=>'Kelurahan/Desa','name'=>'alamat_kel', 'value'=>$dt['alamat_kel'] , 'type'=>'text' ),
			'alamat_kec' => array( 'label'=>'Kecamatan', 'value'=>$dt['alamat_kec'] , 'type'=>'text' ),
			'kota' => array( 'label'=>'Kota/Kab', 
				'value'=>selKabKota2('alamat_b',$dt['alamat_b'],$Main->DEF_PROPINSI) , 
				'type'=>'' 
			),
			'koord' => array(
				'label'=> '',
				'value'=> "<table cellspacing='0' cellpadding='0' border='0' width=''>".
					"<tr><td width=120></td><td width=15></td><td></td></tr>".
					formEntryKoordinatGPS('koord',$dt['koordinat_gps'], $dt['koord_bidang'])."</table>",
				'type'=>'merge'
			),
			'luas' => array(
				'label'=>'Luas (m2)', 
				'value'=>'<INPUT type=text name="luas" value="'.$dt['luas'].'" size="4" 					
					onkeypress="return isNumberKey(event)">',
				'type'=>''
			),
			
		);		
		$formmKIBA = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB C,D,F --------------------------------------------
		/**$this->form_fields = array(	
			'alamat' => array( 'label'=>'Letak/Alamat', 
				'value'=> "<textarea style='width:438;' id='alamat' name='alamat'>".$dt['alamat']."</textarea>", 
				'row_params'=>"valign='top'",
				'labelWidth'=>120, 'type'=>'' ),
			'alamat_kel' => array( 'label'=>'Kelurahan/Desa', 'value'=>$dt['alamat_kel'] , 'type'=>'text' ),
			'alamat_kec' => array( 'label'=>'Kecamatan', 'value'=>$dt['alamat_kec'] , 'type'=>'text' ),
			'kota' => array( 'label'=>'Kota/Kab', 
				'value'=>selKabKota2('alamat_b',$dt['alamat_b'],$Main->DEF_PROPINSI) , 
				'type'=>'' 
			),			
		);		
		$formmKIBC = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		**/
		//--- form KIB B -------------------------------		
		//no_pabrik,no_rangka,no_mesin,no_polisi,no_bpkb
		$this->form_fields = array(					
			'merk' => array( 'label'=>'Merk', 
				'value'=> "<textarea style='width:438;' id='merk' name='merk'>".$dt['merk']."</textarea>",
				'row_params'=>"valign='top'",
				'type'=>'' , 'labelWidth'=>120
			),
			'nomor' => array( 'label'=>'', 'value'=> 'Nomor', 'type'=>'merge' ),
			'no_pabrik' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Pabrik', 'value'=> $dt['no_pabrik'] , 'type'=>'text' ),
			'no_rangka' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Rangka', 'value'=> $dt['no_rangka'] , 'type'=>'text' ),
			'no_mesin' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Mesin', 'value'=> $dt['no_mesin'] , 'type'=>'text' ),
			'no_polisi' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Polisi', 'value'=> $dt['no_polisi'] , 'type'=>'text' ),
			'no_bpkb' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;BPKB', 'value'=> $dt['no_bpkb'] , 'type'=>'text' ),
			'ukuran' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Ukuran', 'value'=> $dt['ukuran'] , 'type'=>'text' ),
			'bahan' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Bahan', 'value'=> $dt['bahan'] , 'type'=>'text' ),
		);
		$formmKIBB = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB E -------------------------------
		//buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran
		$this->form_fields = array(		
			'buku' => array( 'label'=>'', 'value'=> 'Buku Perpustakaan', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'buku_judul' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta', 'value'=> $dt['buku_judul'] , 'type'=>'text'  , 'labelWidth'=>120),
			'buku_spesifikasi' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi', 'value'=> $dt['buku_spesifikasi'] , 'type'=>'text' ),
			'tgl_buku' => array(
				'label' => '&nbsp;&nbsp;&nbsp;&nbsp;Tgl. Buku',
				'value' =>	createEntryTgl("tgl_buku",$dt['tgl_buku']),
				'type' => ''
			),
			'seni' => array( 'label'=>'', 'value'=> 'Barang bercorak Kesenian/Kebudayaan', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'seni_asal_daerah' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah', 'value'=> $dt['seni_asal_daerah'] , 'type'=>'text' ),
			'seni_pencipta' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Pencipta', 'value'=> $dt['seni_pencipta'] , 'type'=>'text' ),
			'seni_bahan' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Bahan', 'value'=> $dt['seni_bahan'] , 'type'=>'text' ),
			'hewan' => array( 'label'=>'', 'value'=> 'Hewan Ternak', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'hewan_jenis' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Jenis', 'value'=> $dt['hewan_jenis'] , 'type'=>'text' ),
			'hewan_ukuran' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Ukuran', 'value'=> $dt['hewan_ukuran'] , 'type'=>'text' ),
		);
		$formmKIBE = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
					
		
		//-- MENU -------------------------
		$this->form_menubawah = '';
		$menu =
			"<table width='100%' class='menudottedline'>
			<tbody><tr><td>
				<table width='50'><tbody><tr>				
					<td>					
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='btsave' 
							href='javascript:".$this->Prefix.".Simpan()'> 
						<img src='images/administrator/images/save_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Simpan'> Simpan</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					<td>			
					 <table cellpadding='0' cellspacing='0' border='0' id='toolbar'>
					<tbody><tr valign='middle' align='center'> 
					<td class='border:none'> 
						<a class='toolbar' id='' href='javascript:window.close();'> 
						<img src='images/administrator/images/cancel_f2.png' alt='Save' name='save' width='32' height='32' border='0' align='middle' title='Batal'> Batal</a> 
					</td> 
					</tr> 
					</tbody></table> 
					</td>
					</tr>
				</tbody></table>
			</td></tr>
			</tbody></table>";
		
		//-- FORM ---------------------
		$this->form_fields = array(		
			'judul' => array( 'label'=>'', 
				'value'=>"<span style='font-size: 18px;font-weight: bold;color: #C64934;'>Barang Tidak Tercatat - $Baru</span>", 
				'type'=>'merge' 
			),		
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'row_params'=>" height='24' ", 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'row_params'=>" height='24' ", 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'row_params'=>" height='24' ", 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'row_params'=>" height='24' ", 'type'=>'' ),
			'nama_barang' => array(
				'label'=>'Nama Barang', 
				'value' =>cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2c.php",
					"fmIDBARANG",
					"fmNMBARANG",
					"$ReadOnly","$DisAbled"),					
				'type' => ''				
			),
			'thn_perolehan' => array(
				'label'=> 'Tahun Perolehan',
				'value'=>'<input type="text" name="thn_perolehan" value="'.$dt['thn_perolehan'].'" size="4" maxlength=4 				
						onkeypress="return isNumberKey(event)"'.($entryMutasi==FALSE? '':' readonly="" ').' /><span style="color:red;"> 4 digit (mis: 1998)</span>',
				'type'=>''
			),
			'noreg' => array(
				'label'=> 'Nomor Register',
				'value'=>'<INPUT type=text name="noreg" value="'.$dt['noreg'].'" size="4" maxlength="4" 					
					onkeypress="return isNumberKey(event)"
					>		<span style="color:red;">4 digit (mis: 0002)</span>',
				'type'=>''
			),
			'harga' => array(
				'label'=> 'Harga Satuan Barang',
				'value'=> 'Rp.'.inputFormatRibuan("jml_harga", ($entryMutasi==FALSE? '':' readonly="" '),$dt['harga'] ),
				'type'=>''
			),
			'jml_barang' => array(
				'label'=> 'Jumlah Barang',
				'value'=> "<input type=\"text\" size='4' maxlength='2' name=\"jml_barang\" value=\"$dt[jml_barang]\" 
						".$jmlBrgReadonly."
						onkeypress='return isNumberKey(event)'
						/>&nbsp;&nbsp;
						<!--<span style='color: red;'> (max 99)</span> &nbsp&nbsp -->
						Satuan : <input type=\"text\" size='6' name=\"satuan\" value=\"$dt[satuan]\" ".($entryMutasi==FALSE? '':' readonly="" ')." />
						&nbsp&nbsp<span style='color: red;'> untuk tanah atau bangunan diisi 1 bidang/bangunan bukan diisi luas tanah/bangunan</span>",
				'type'=>''
			),
			'asal_usul' => array(
				'label'=> 'Asal Usul',
				'value'=> cmb2D('asal_usul',$dt['asal_usul'],$Main->AsalUsul,''), 
				'type'=>''
			),
			'kondisi' => array(
				'label'=> 'Kondisi',
				'value'=>  cmb2D_v2('kondisi', $dt['kondisi'], $Main->KondisiBarang, $disKondisi, 'Semua Kondisi'),
				'type'=>''
			),
			'fmkiba' => array(
				'label'=> '',
				'value'=> "<div id='tidaktercatat_formkiba' name='tidaktercatat_formkiba' $fmkibavisible>".$formmKIBA."</div>",
				'type'=>'merge'
			),
			
			
			
			'fmkibb' => array(
				'label'=> '',
				'value'=> "<div id='tidaktercatat_formkibb' name='tidaktercatat_formkibb' $fmkibbvisible>".$formmKIBB."</div>",
				'type'=>'merge'
			),
			'fmkibc' => array(
				'label'=> '',
				'value'=> "<div id='tidaktercatat_formkibc' name='tidaktercatat_formkibc' $fmkibcvisible>".$formmKIBC."</div>",
				'type'=>'merge'
			),
			'fmkibe' => array(
				'label'=> '',
				'value'=> "<div id='tidaktercatat_formkibe' name='tidaktercatat_formkibe' $fmkibevisible>".$formmKIBE."</div>",
				'type'=>'merge'
			),	
			
			'ket' => array( 'label'=>'Keterangan', 
				'value'=> "<textarea style='width:438;' id='ket' name='ket'>".$dt['ket']."</textarea>",
				'row_params'=>"valign='top'",
				'type'=>'' 
			),
			'tgl_sensus' => array(
				'label' => 'Tgl. Sensus',
				'value' =>	createEntryTgl("tgl_sensus",$dt['tgl_sensus']),
				'type' => ''
			),		
			'tahun_sensus' => array(
				'label'=> 'Tahun Sensus',
				'value'=>'<input type="text" name="tahun_sensus" value="'.$dt['tahun_sensus'].'" size="4" maxlength=4 				
						onkeypress="return isNumberKey(event)"'.($entryMutasi==FALSE? '':' readonly="" ').' /><span style="color:red;"> 4 digit (mis: 1998)</span>',
				'type'=>''
			),
					
			'petugas' => array( 
				'label'=>'Petugas Sensus', 
				'name'=>'petugas', 
				'id'=>'petugas', 
				'value'=>$dt['petugas'],
				'type'=>'text' 
			),
			'ruang' => array(  'label'=>'Gedung / Ruang', 
				'value'=> //$old['nm_gedung'].' / '.$old['nm_ruang'].					
					" <input type='text' id='nm_gedung' value='".$gdg['nm_ruang']."' readonly='true' style='width:205'>".
					' &nbsp; / &nbsp; '.
					" <input type='text' id='nm_ruang' value='".$select['nm_ruang']."' readonly='true' style='width:205'>".
					" <input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihRuang()\" id='btRuang' name='btRuang'>".
					" <input type='hidden' id='ref_idruang' name='ref_idruang' value='".$dt['ref_idruang']."'>"
				,  'type'=>'' 
			),
			
			'pejabat_pemegang2' => array(  
				'label'=>'Penanggung jawab Barang', 
				'value'=> 
					"<input type='hidden' id='ref_idpengadaan1' name='ref_idpengadaan1' value='".$dt['ref_idpemegang2']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan1' name='nama_pejabat_pengadaan1' readonly=true value='".$read['nama']."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan1' name='nip_pejabat_pengadaan1' readonly=true value='".$read['nip']."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPemegang2()\">"
				,
				'type'=>'' 
			),
			
			//menu
			'menu'=> array( 'label'=>'', 
				'value'=>
				"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
				"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
				"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
				"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
				$menu,
				'type'=>'merge'
			)
		);
		
				
		
		
		//$form = $this->genForm(TRUE,1, FALSE);		
		$form = $this->genForm_nodialog();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	

	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/ruang.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	
	
}
$SensusScan = new SensusScanObj();

?>