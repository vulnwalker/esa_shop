<?php

class ReclassObj extends DaftarObj2{
	var $Prefix = 'Reclass'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'barang_tidak_tercatat'; //daftar
	var $TblName_Hapus = 'barang_tidak_tercatat';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('jml_barang', 'jml_harga');
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
	var $PageTitle = 'Penatausahaan';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';
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
		return 'Barang Tidak Tercatat';
	}
	function setCetakTitle(){
		return	"<DIV ALIGN=CENTER>$this->Cetak_Judul ";
	}
	
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".reClass()","new_f2.png","Baru",'Baru')."</td>".
			/*"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>"*/
			'';
			
	}
	
	function setMenuView(){	
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Cetak_kerja2(\"$Op\")","print_f2.png",'K Kerja',"Cetak Kertas Kerja ")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua")."</td>"
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'K. Kerja',"Cetak Kertas Kerja per Semua")."</td>"
			;
	}
	
	function setPage_HeaderOther(){	
		//style = terpilih
		/*$Pg= $_REQUEST['Pg'];
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
			
			<!--
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu12>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu13>KIP</a>  |  
			-->
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |
			<A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' >PETA</a> |
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' $styleMenu>SENSUS</a> |
			<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu14>PEMBUKUAN</a>
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
			<A href=\"pages.php?Pg=SensusHasil\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>   | 			
			<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   |
			
			<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
			<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
			
			
			  &nbsp&nbsp&nbsp
			</td></tr></table>"
			;
			*/
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
				" . WilSKPD_ajx3('ReclassSkpd') . "
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
		$Limit = '';
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
		$tampilCheckbox =  $cetak ? "" : "	<th class='th01' ><input type='checkbox' name='".$this->Prefix."_toggle' id='".$this->Prefix."_toggle' value='' 
				onClick=\"checkAll4(".$Main->PagePerHal.",'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');".
				"Penatausaha.checkAll($Main->PagePerHal,'cb','toggle','boxchecked')".
				"\" ></th>";			
					
		
		$kolomMerk = 'Merk / Tipe / Alamat';
		$headerTable =
				"<tr>
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
				";
		return $headerTable;
	}
	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$cek = '';
		$Koloms = array();
		$cetak = $Mode==2 || $Mode==3 ;
				
		$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
		
		$brg = mysql_fetch_array(mysql_query(
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
		$gdg = mysql_fetch_array(mysql_query("select * from ref_ruang where concat(c,d,e,e1,p,q)='".$rng['c'].$rng['d'].$rng['e'].$rng['e1'].$rng['p']."0000'"));
		$vGedung = $gdg['nm_ruang'];
		
		$Koloms[] = array('align=right', $no.'.' );
		//if ($cetak == FALSE ) $Koloms[] = array('align=center', "<input type='checkbox' $Checked  id='cb$cb' name='cidBI[]' value='{$isi['id']}' onClick='isChecked(this.checked);' />"); 
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=center', $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']);			
		$Koloms[] = array('align=center', $isi['noreg']);			
		$Koloms[] = array('align=left', $vnmbarang);
		$Koloms[] = array('align=left', $vmerk);
		$Koloms[] = array('align=left', $vnosertifikat);
		$Koloms[] = array('align=center', $vthnperolehan.'<br>'.$vkondisi);
		$Koloms[] = array('align=left', $vGedung.'<br>'.$vRuang);		
		$Koloms[] = array('align=left', $vPenanggungJawab);		
		
		$Koloms[] = array('align=right', $vjml_barang);		
		
		$Koloms[] = array('align=right', $vharga );
		$Koloms[] = array('align=right', $vjml_harga );
		$Koloms[] = array('align=left', $vcatatan );
		$Koloms[] = array('align=left', $vthnsensus.'<br>'.$vtglsensus.'<br>'.$vpetugas );

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
		//$fmST = $_REQUEST['SensusTidakTercatat_fmST'];
		//$id = $_REQUEST['SensusTidakTercatat_idplh'];
		$uid = $HTTP_COOKIE_VARS['coID'];
		
		$idasal = $_REQUEST[$this->Prefix.'_idplh'];
		
		$old = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id='$idasal' and status_barang=1 "
		));
		
		$cekreklas=mysql_fetch_array(mysql_query("select count(*) as cnt from buku_induk where id_lama = '$idasal'"));
		if($err=='' && $cekreklas['cnt']>0) $err = "Barang sudah di reklas/mutasi !";
		
		$c = $old['c'];
		$d = $old['d'];
		$e = $old['e'];
		$e1 = $old['e1'];
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$tes = explode('.',$fmIDBARANG);
		$f = $tes[0];
		$g = $tes[1];
		$h = $tes[2];
		$i = $tes[3];
		$j = $tes[4];
		
		//tgl_update,tahun,gambar,dokumen,dokumen_ket,dokumen_file,nilai_appraisal,tgl_buku,uid,id_lama,tgl_sensus,jml_barang_tmp,idawal,jml_gambar,idall2,ref_idpemegang,ref_idpenanggung,ref_idruang,tahun_sensus,ref_idpemegang2,status_penguasaan		
								
		$noreg 			= $_REQUEST['noreg'];
		$thn_perolehan 	= $_REQUEST['thn_perolehan'];//$old['thn_perolehan'];		
		$tgl_buku = $_REQUEST['tgl_buku'];
		
		$jml_barang 	= $old['jml_barang'];
		$satuan 		= $old['satuan'];
		$harga			= $old['harga'];
		$jml_harga 		= $old['jml_harga'];
		$asal_usul 		= $old['asal_usul'];
		$kondisi 		= $old['kondisi'];
		$status_barang 	= '1';
		
		
		$status_penguasaan = $_REQUEST['status_penguasaan'];
		$tgl_sensus 	= $_REQUEST['tgl_sensus'];
		$petugas 		= $_REQUEST['petugas'];
		$tahun_sensus 	= $old['tahun_sensus'];
		
		$ref_idpemegang2 = $_REQUEST['ref_idpengadaan1'];
		$ref_idruang 	= $_REQUEST['ref_idruang'];
		
		$arrtglbuku= explode('-',$tgl_buku);
		
		//cek status masih inventaris -------------------------
		if($err=='' && $old['status_barang'] != 1) $err = 'Gagal reclass, barang sudah dihapus dari inventaris!';
		
		//cek kode barang
		if($err==''){
			if( ($f=='07' && $g=='21') || ($f=='07' && $g=='22') || ($f=='07' && $g=='23') || ($f=='07' && $g=='25') ) $err = "Barang tidak bisa di reclass ke kode barang $f.$g !";
		}
		if($err==''){
			if( ($old['f']=='07' && $old['g']=='21') || ($old['f']=='07' && $old['g']=='22') || ($old['f']=='07' && $old['g']=='23') || ($old['f']=='07' && $old['g']=='25') ) $err = "Kode Barang $f.$g tidak bisa di reclass !";
		}
		
		//cek status barang --------------
		if($err=='' && $old['status_barang'] != 1 ) $err= "Hanya Barang Inventaris yang bisa di reclass!";
		
		
		//cek thn perolehan -----------------------------------
		//thn perolehan baru  > thn lama
		if($err=='' && $thn_perolehan<$old['thn_perolehan']) $err = 'Tahun Perolehan harus lebih besar atau sama dengan '.$old['thn_perolehan'].'!';
		if($err=='' &&  $arrtglbuku[0]< $thn_perolehan) $err = 'Tahun Tanggal Buku harus lebih besar atau sama dengan Tahun Perolehan!';
		if ($err=='' && $fmTAHUNPEROLEHAN >  $getdate['thn'] ) { $err = 'Tahun Perolehan tidak lebih besar dari tahun sekarang!'; }
						
		//cek tgl buku ----------------------------------------
		if ($err=='' && !cektanggal($tgl_buku)){ $err = 'Tanggal Buku salah!'; }
		if ($err=='' && compareTanggal($tgl_buku, date('Y-m-d'))==2  ) $err = 'Tanggal Buku tidak lebih besar dari Hari ini!';
		if ($err=='' && $arrtglbuku[0] < 2009 ){ $err = 'Tahun Tanggal Buku tidak lebih kecil dari 2009!'; }	
		if ($err=='' && compareTanggal($old['tgl_buku'], $tgl_buku )==2){ $err = 'Tanggal Buku Baru tidak lebih kecil dari Tanggal Buku Lama!'; }
		
		//cek tgl rehab	--------------------------------------------	
		$plh = mysql_fetch_array(mysql_query(  "select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk ='$idasal'" ));
		$aman = mysql_fetch_array(mysql_query(  "select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk ='$idasal'" ));
		$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idasal'" ));
		//$manfaat = mysql_fetch_array(mysql_query(  "select max(tgl_pemanfaatan) as maxtgl from pemanfaatan where id_bukuinduk ='$idasal'" ));
		
		if ($err=='' && compareTanggal($plh['maxtgl'] , $tgl_buku )==2  ) $err = 'Tanggal Buku tidak lebih kecil dari Tanggal Pemeliharaan!';
		if ($err=='' && compareTanggal($aman['maxtgl'] , $tgl_buku )==2  ) $err = 'Tanggal Buku tidak lebih kecil dari Tanggal Pengamanan!';
		if ($err=='' && compareTanggal($hps['maxtgl'] , $tgl_buku )==2  ) $err = 'Tanggal Buku tidak lebih kecil dari Tanggal Penghapusan Sebagian!';
		
		if ($err ==''){
			$pelihara = mysql_fetch_array( mysql_query (
				"select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk = '$idasal'"
			));
			if ($err =='' && (compareTanggal($tgl_buku, $pelihara['maxtgl'])==0 || compareTanggal($tgl_buku, $pelihara['maxtgl'])==1)  ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pemeliharaan!';
			$pengaman = mysql_fetch_array( mysql_query (
				"select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk = '$idasal'"
			));
			if ($err =='' && (compareTanggal($tgl_buku, $pengaman['maxtgl'])==0 || compareTanggal($tgl_buku, $pengaman['maxtgl'])==1 ) ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pengamanan!';
			$pemanfaat = mysql_fetch_array( mysql_query (
				"select max(tgl_pemanfaatan) as maxtgl from pemanfaatan where id_bukuinduk = '$idasal'"
			));				
			if ($err =='' && (compareTanggal($tgl_buku, $pemanfaat['maxtgl'])==0 || compareTanggal($tgl_buku, $pemanfaat['maxtgl'])==1 )  ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Pemanfaatan!';						
			
			$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idasal'" ));
			if ($err=='' && (compareTanggal( $tgl_buku, $hps['maxtgl']   )==0 || compareTanggal($tgl_buku, $hps['maxtgl'])==1) ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Penghapusan Sebagian!';
			
			$hps = mysql_fetch_array(mysql_query(  "select max(tgl) as maxtgl from t_koreksi where idbi = '$idasal'" ));
			if ($err=='' && (compareTanggal( $tgl_buku , $hps['maxtgl']  )==0 || compareTanggal($tgl_buku, $hps['maxtgl'])==1) ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Koreksi!';
			
			$hps = mysql_fetch_array(mysql_query(  "select max(tgl_penilaian) as maxtgl from penilaian where id_bukuinduk = '$idasal'" ));
			if ($err=='' && (compareTanggal( $tgl_buku , $hps['maxtgl']  )==0 || compareTanggal($tgl_buku, $hps['maxtgl'])==1) ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Penilaian!';
			
			$hps = mysql_fetch_array(mysql_query(  "select max(tgl_buku) as maxtgl from t_jurnal_aset where idbi = '$idasal'" ));
			if ($err=='' && (compareTanggal( $tgl_buku , $hps['maxtgl']  )==0 
				//|| compareTanggal($tgl_buku, $hps['maxtgl'])==1
			) ) 
				$err = 'Tanggal Penghapusan harus lebih besar dari Tanggal Jurnal!';
				
		}
		
		//cek closing --------------------------------------------
		$arrTgl = explode('-',$tgl_buku);
		$thn  = $arrTgl[0];//$err=$thn;
		if ($err=='' && $thn <= $Main->TAHUN_CLOSING ) $err = "Tidak bisa diproses, Barang sudah closing ($Main->TAHUN_CLOSING)!"; 
				
		if($fmIDBARANG=='') $err= 'Kode Barang belum diisi!';
		
		//cek sedang usulan		
		if($old['stusul']>0) { 
			switch ($old['stusul']){
				case '1' : $err = 'Barang sedang diusulkan penghapusan!'; break;
				case '2' : $err = 'Barang sedang diusulkan mutasi!'; break;
				default : $err = 'Barang sedang diusulkan !';
			}
		}
		
		//cek hanya intra dan ATB
		if($err=='' && ($old['staset']!=3 && $old['staset']!=8) ) $err= 'Hanya aset Intra atau Aset Tidak Berwujud yang bisa reklass kode barang!';
		
		
		if($err==''){			
			//insert buku_induk ------------------------------
			$newstaset = $old['staset'];
			if($old['staset']==3 || $old['staset']==8) {
				$newstaset = $f.$g=='0724' ? 8: 3;
			}
			$aqry = 
				"insert into buku_induk ".
				" (a1,a,b,c,d,e,e1,".
				" f,g,h,i,j,noreg,thn_perolehan,jml_barang,".				
				" satuan,harga,jml_harga,asal_usul, ".
				" masa_manfaat,nilai_sisa,harga_beli,harga_atribusi,".
				" ref_idatribusi,thn_susut_aw,thn_susut_ak, ".
				" kondisi,status_barang,tahun,gambar,dokumen,".
				" dokumen_ket,dokumen_file,nilai_appraisal, ".
				" tgl_buku,id_lama,	jml_barang_tmp,idawal,".
				" jml_gambar,ref_idpemegang,ref_idpenanggung,".
				" ref_idruang,tahun_sensus,				
				ref_idpemegang2,status_penguasaan,uid,tgl_update, staset)".
				" values ('".
				$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
				$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','1','".				
				$old['satuan']."','".$old['harga']."','".$old['jml_harga']."','5','".
				$old['masa_manfaat']."','".$old['nilai_sisa']."','".$old['harga_beli']."','".$old['harga_atribusi']."','".
				$old['ref_idatribusi']."','".$old['thn_susut_aw']."','".$old['thn_susut_ak']."','".
				$old['kondisi']."','1','".$thn_perolehan."','".$old['gambar']."','".$old['dokumen']."','".
				$old['dokumen_ket']."','".$old['dokumen_file']."','".$old['nilai_appraisal']."','".
				$tgl_buku."','".$idasal.
				//"','".$old['tgl_sensus'].
				"',1,'".$old['idawal']."','".
				$old['jml_gambar']."','".$old['ref_idpemegang']."','".$old['ref_idpenanggung']."','".
				$old['ref_idruang'].
				"','".$old['tahun_sensus'].
				"','".$old['ref_idpemegang2']."','".$status_penguasaan."','".
				$uid."',now()".
				" ,'".$newstaset."' )"; $cek .='bi='. $aqry;
			$qry = mysql_query(
				$aqry
			);
			
			if($qry){				
				$newid = mysql_insert_id();
								
				//insert kib ----------------------------
				$koordinat_gps 	= $_REQUEST['koordinat_gps'];
				$koord_bidang 	= $_REQUEST['koord_bidang'];		
				$luas 		= $_REQUEST['luas'];
				$alamat 	= $_REQUEST['alamat'];
				$alamat_kel = $_REQUEST['alamat_kel'];
				$alamat_kec = $_REQUEST['alamat_kec'];
				$alamat_a	= $_REQUEST['alamat_a'];
				$alamat_b	= $_REQUEST['alamat_b'];
				$ket 		= $_REQUEST['ket'];
				$bersertifikat = $_REQUEST['bersertifikat'];
				$sertifikat_tgl= $_REQUEST['sertifikat_tgl'];
				$sertifikat_no = $_REQUEST['sertifikat_no'];
				$merk 			= $_REQUEST['merk'];
				$ukuran 		= $_REQUEST['ukuran'];
				$bahan 			= $_REQUEST['bahan'];
				$no_pabrik 		= $_REQUEST['no_pabrik'];
				$no_rangka 		= $_REQUEST['no_rangka'];
				$no_mesin 		= $_REQUEST['no_mesin'];
				$no_polisi 		= $_REQUEST['no_polisi'];
				$no_bpkb 		= $_REQUEST['no_bpkb'];
				
				$buku_judul 	= $_REQUEST['buku_judul'];
				$buku_spesifikasi = $_REQUEST['buku_spesifikasi'];
				
				$seni_asal_daerah = $_REQUEST['seni_asal_daerah'];
				$seni_pencipta 	= $_REQUEST['seni_pencipta'];
				$seni_bahan 	= $_REQUEST['seni_bahan'];
				$hewan_jenis 	= $_REQUEST['hewan_jenis'];
				$hewan_ukuran 	= $_REQUEST['hewan_ukuran'];
				
				$penggunaan 	= $_REQUEST['penggunaan'];
				
				$kondisi_bangunan=$_REQUEST['kondisi_bangunan'];
				$konstruksi_tingkat	= $_REQUEST['konstruksi_tingkat'];
				$konstruksi_beton=$_REQUEST['konstruksi_beton'];
				$luas_lantai 	= $_REQUEST['luas_lantai'];
				$dokumen_tgl	=$_REQUEST['dokumen_tgl'];
				$dokumen_no		=$_REQUEST['dokumen_no'];
				$kode_tanah		=$_REQUEST['kode_tanah'];
				
				$panjang	= $_REQUEST['panjang'];
				$lebar		= $_REQUEST['lebar'];
				$status_tanah = $_REQUEST['status_tanah'];
				$tmt = $_REQUEST['tmt'];
								
				$uraian			= $_REQUEST['uraian'];
				$software_nama	= $_REQUEST['software_nama'];
				$kajian_nama	= $_REQUEST['kajian_nama'];
				$kerjasama_nama	= $_REQUEST['kerjasama_nama'];
				$pencipta		= $_REQUEST['pencipta'];
				$jenis			= $_REQUEST['jenis'];
				
				
				
				switch($f){
					case '01':{
						$qrykib =
							" insert into kib_a ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							" luas,alamat,alamat_a,alamat_b,alamat_kel,alamat_kec,".
							" koordinat_gps,koord_bidang,status_hak,".
							" bersertifikat,sertifikat_tgl,sertifikat_no,".
							" penggunaan,ket,idbi)".
							" values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$luas."','".$alamat."','".$alamat_a."','".$alamat_b."','".$alamat_kel."','".$alamat_kec."','".
							$koordinat_gps."','".$koord_bidang."','".$status_hak."','".
							$bersertifikat."','".$sertifikat_tgl."','".$sertifikat_no."','".
							$penggunaan."','".$ket."',".$newid.
							" )"
						;
						break;
					}
					case '02':{
						$qrykib = 
							" insert into kib_b ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							
							"merk,ukuran,bahan,".
							"no_pabrik,no_rangka,no_mesin,".
							"no_polisi,no_bpkb,ket,idbi".
							
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$merk."','".$ukuran."','".$bahan."','".
							$no_pabrik."','".$no_rangka."','".$no_mesin."','".
							$no_polisi."','".$no_bpkb."','".
							$ket."',".$newid.
							" )"
						;
						break;
					}
					case '03': {
						$qrykib = 
							" insert into kib_c ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							" kondisi_bangunan, konstruksi_tingkat, ".
							" konstruksi_beton,luas_lantai,alamat,alamat_a,alamat_b,".
							" alamat_kel,alamat_kec,koordinat_gps,koord_bidang,".
							" dokumen_tgl,dokumen_no, luas, status_tanah, ".
							" kode_tanah,".
							" ket,idbi".
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$kondisi_bangunan."','".$konstruksi_tingkat."','".
							$konstruksi_beton."','".$luas_lantai."','".$alamat."','".$alamat_a."','".$alamat_b."','".
							$alamat_kel."','".$alamat_kec."','".$koordinat_gps."','".$koord_bidang."','".
							$dokumen_tgl."','".$dokumen_no."','".$luas."','".$status_tanah."','".
							$kode_tanah."','".
							$ket."',".$newid.
							" )"
						;
						break;
					}
					case '04': {
						$qrykib = 
							" insert into kib_d ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							" konstruksi,panjang,lebar,luas,".
							" alamat,alamat_a,alamat_b,alamat_kel,alamat_kec,".
							" koordinat_gps,koord_bidang,dokumen_tgl,dokumen_no,".
							" status_tanah,kode_tanah, ".
							" ket,idbi".
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$konstruksi."','".$panjang."','".$lebar."','".$luas."','".
							$alamat."','".$alamat_a."','".$alamat_b."','".$alamat_kel."','".$alamat_kec."','".
							$koordinat_gps."','".$koord_bidang."','".$dokumen_tgl."','".$dokumen_no."','".
							$status_tanah."','".$kode_tanah."','".
							$ket."',".$newid.
							" )"
						;
						break;
					}
					case '05': {
						$qrykib = 
							" insert into kib_e ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".						
							" buku_judul,buku_spesifikasi,seni_asal_daerah, ".
							" seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran, ".						
							" ket,idbi".
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$buku_judul."','".$buku_spesifikasi."','".$seni_asal_daerah."','".
							$seni_pencipta."','".$seni_bahan."','".$hewan_jenis."','".$hewan_ukuran."','".
							$ket."',".$newid.
							" )"
						;
						break;
					}
					case '06': {
						$qrykib = 
							" insert into kib_f ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							"bangunan,konstruksi_tingkat,konstruksi_beton,luas,".
							
							"alamat,alamat_a,alamat_b,alamat_kel,alamat_kec,".
							"koordinat_gps,koord_bidang,dokumen_tgl,dokumen_no,".
							"tmt,".
							"status_tanah,kode_tanah,".
							
							" ket,idbi".
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$bangunan."','".$konstruksi_tingkat."','".$konstruksi_beton."','".$luas."','".
							
							$alamat."','".$alamat_a."','".$alamat_b."','".$alamat_kel."','".$alamat_kec."','".
							$koordinat_gps."','".$koord_bidang."','".$dokumen_tgl."','".$dokumen_no."','".
							$tmt."','".
							$status_tanah."','".$kode_tanah."','".
							$ket."',".$newid.
							" )"
						;
						break;
					}
					case '07': {
						//a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,
						//
						$qrykib = 
							" insert into kib_g ".
							" (a1,a,b,c,d,e,e1,".
							" f,g,h,i,j,noreg,tahun,".
							"uraian,software_nama,kajian_nama,kerjasama_nama,ket,idbi,pencipta,jenis".
							
							") values('".
							$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
							$f."','".$g."','".$h."','".$i."','".$j."','".$noreg."','".$thn_perolehan."','".	
							$uraian."','".$software_nama."','".$kajian_nama."','".$kerjasama_nama."','".$ket."','".$newid."','".$pencipta."','".$jenis.
							"' )"
						;
						break;
					}
					
				}
				$cek .= ' kib='.$qrykib;
				$kib = mysql_query($qrykib);
				
				//*
				//insert penyusutan barang reklas
				 /**include_once('common/fnpenyusutan.php');
				 $thnsusut = substr($tgl_buku,0,4);
				 $blnsusut = substr($tgl_buku,5,2);
				 $sem = $blnsusut <= 6?1 : 2;
				 $susutkan = $Penyusutan->susutSatu($newid,$thnsusut,$blnsusut,$tgl_buku,$sem);
				**/
				//insert penghapusan asal ----------------------
				$aqry = "insert into penghapusan ".
					"(id_bukuinduk,a1,a,b,c,d,e,e1,".
					"f,g,h,i,j,noreg,thn_perolehan,".
					"tgl_penghapusan,ket,tahun,".
					"mutasi,sudahmutasi,".
					"uid,tgl_update".
					",staset, idbi_awal )values('".
					$idasal."','".$old['a1']."','".$old['a']."','".$old['b']."','".$old['c']."','".$old['d']."','".$old['e']."','".$old['e1']."','".
					$old['f']."','".$old['g']."','".$old['h']."','".$old['i']."','".$old['j']."','".$old['noreg']."','".$old['thn_perolehan']."',".
					"'$tgl_buku','reclass','".$old['thn_perolehan']."',".
					"2,1,'".$uid."','now()'".
					",'".$old['staset']."','".$old['idawal']."')";
				$cek .= 'hapus='.$aqry;
				$qry = mysql_query($aqry);
				//update buku_induk asal ----------------------
				$aqry = "update buku_induk set status_barang=3 where id = $idasal"; 
				$cek .= 'updt bi asal ='.$aqry;
				$qry = mysql_query($aqry);
				
				//8->3 atau 3->8 reklas ATB
				if( ($old['f']=='07' && $f<>'07')||($old['f']<>'07' && $f=='07')  ){
					$staset = $old['staset'];
					$staset_baru = $f=='07' ? 8: 3;
					$div_staset = $staset_baru-$staset;
					mysql_query(
						"insert into t_history_aset ".
						" (tgl,idbi,uid,tgl_update,staset,staset_baru,div_staset,ket,idbi_awal,jns,refid)  ".
						" values ('$tgl_buku',$newid,'$uid',now(),$staset,$staset_baru,$div_staset,'',".$old['idawal'].",4,$newid)"
					);
				}
				
				
				
			}else{
				//$err = 'err no: '.mysql_errno();
				$err = " Kode Barang untuk tahun dan noreg ini sudah ada! ";
			}
			
		}
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function simpan2_(){
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
		$alamat_c	= $_REQUEST['alamat_c'];
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
					'a1'=>"'11'",'a'=>"'10'",'b'=>"'00'",
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
					'alamat_c'=>"'$alamat_c'",
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
	
	function getNoRegAkhir(){		
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$cek = ''; $err=''; $content=''; 
		//$cek = 'tes';
		
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$thn_perolehan = $_REQUEST['thn_perolehan'];
		$idasal = $_REQUEST[$this->Prefix.'_idplh'];
		$uid = $HTTP_COOKIE_VARS['coID'];
		
		$old = mysql_fetch_array(mysql_query(
			"select * from buku_induk where id = '$idasal'"	
		));
		$kond = $old['c'].$old['d'].$old['e'].$old['e1'].$fmIDBARANG.$thn_perolehan;
		$aqry = " select (ifnull(max(noreg),0)+1) as maxno from buku_induk where concat(c,d,e,e1,f,'.',g,'.',h,'.',i,'.',j,thn_perolehan) = '$kond' " ; $cek .= $aqry;
		$get = mysql_fetch_array(mysql_query(
			$aqry
		));
		$fmN = ($get['maxno']+10000)."";
		$content->noreg = substr($fmN,1,4);	
		
		//$content->noreg = '313';
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		switch($tipe){
			case 'getNoRegAkhir':{				
				$fm = $this->getNoRegAkhir();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];
				$json=TRUE;	
				break;
			}
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				//if($err==''){
					$content = $fm['content'];		
				//}else{
					//$content='error';
				//}
				
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
			case 'genCetak_kerja2':{
				$json=FALSE;
				$this->genCetak_kerja2();
				break;
			}
			case 'simpan2' : {
				//($fmST, $tblsimpan='', $fieldKey='', $fieldKeyVal='', $fields = '', $fieldsval = '' )
				$get = $this->simpan2(); 
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				$json=TRUE;
				break;
			}
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormBaru(){
		$dt=array();
		$err='';
		/*$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		*/
		$idasal = $_REQUEST['idasal'];
		$oldqry = "select * from buku_induk where id = '$idasal'";
		$dt = mysql_fetch_array(mysql_query(
			$oldqry
		));
		
		//if($err=='' && ($dt['staset']!=3 && $dt['staset']!=8) ) $err= 'Hanya aset Intra atau Aset Tidak Berwujud yang bisa reklass kode barang!';
		//$err='tes';
		
		if($err==''){
			$this->form_idplh =$idasal;
			$this->form_fmST = 0;
			$fm = $this->setForm($dt);	
		}else{
			//$fm['content'] =  $err;
		}
		
		return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
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
	 		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

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
		
		//get tabel kib
		$kondisikib = " concat(c,d,e,e1,f,g,h,i,j,tahun,noreg) = '".$dt['c'].$dt['d'].$dt['e'].$dt['e1'].$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j'].$dt['tahun'].$dt['noreg']."' ";
		switch ($dt['f']){
			case '01' : $tblname ='kib_a'; break;
			case '02' : $tblname ='kib_b'; break;
			case '03' : $tblname ='kib_c'; break;
			case '04' : $tblname ='kib_d'; break;
			case '05' : $tblname ='kib_e'; break;
			case '06' : $tblname ='kib_f'; break;
			case '07' : $tblname ='kib_g'; break;
		}
		
		$brg = mysql_fetch_array(mysql_query(" select * from ref_barang where concat(f,g,h,i,j)='".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."' "));
		
		$kib = mysql_fetch_array(mysql_query(
			" select * from $tblname where $kondisikib "
		));
		
		$get = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0' "));
		$subunit = $get['nm_skpd'];				
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];				
		$fmIDBARANG = $dt['f']==''? '':  $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'] ;//'01.01.01.02.01';
		$bersertifikat = $kib['bersertifikat'];
		$sertifikat_tgl = $kib['sertifikat_tgl'];
		$sertifikat_no = $kib['sertifikat_no'];
		
		//-- set visible entry u kib -----------------------------
		$fmkibavisible = "style='display:none'"; //untuk alamat
		$fmkibbvisible = "style='display:none'"; 
		$fmkibcvisible = "style='display:none'"; //untuk sertifikat
		$fmkibdvisible = "style='display:none'";
		$fmkibevisible = "style='display:none'";
		$fmkibfvisible = "style='display:none'";
		$fmkibgvisible= "style='display:none'";
		$fmalamatvisible = "style='display:none'";
		$fmdocvisible = "style='display:none'";
		$fmluasvisible = "style='display:none'";
		$fmstatustanahvisible = "style='display:none'";
		$fmkodetanahvisible = "style='display:none'";
		$fmstatuspenguasaanvisible = "style='display:none'";
		$fmkonstruksivisible = "style='display:none'";
		
		 
		switch ( $dt['f']){
			case '01': $fmkibavisible = "style='display:block'"; 
				$fmalamatvisible = "style='display:block'"; 
				$fmluasvisible = "style='display:block'"; 
				$fmstatuspenguasaanvisible = "style='display:block'";
			break;			
			case '02': $fmkibbvisible = "style='display:block'"; break;
			case '03': $fmkibcvisible = "style='display:block'"; 
				$fmalamatvisible = "style='display:block'"; 
				$fmdocvisible = "style='display:block'"; 				
				$fmluasvisible = "style='display:block'"; 	
				$fmstatustanahvisible = "style='display:block'";							
				$fmkodetanahvisible = "style='display:block'";	
				$fmstatuspenguasaanvisible = "style='display:block'";						
				$fmkonstruksivisible = "style='display:block'";						
			break;
			case '04': $fmkibdvisible = "style='display:block'"; 
				$fmalamatvisible = "style='display:block'"; 
				$fmdocvisible = "style='display:block'"; 
				$fmluasvisible = "style='display:block'"; 
				$fmstatustanahvisible = "style='display:block'";
				$fmkodetanahvisible = "style='display:block'";
				$fmstatuspenguasaanvisible = "style='display:block'";
			break;
			case '05': $fmkibevisible = "style='display:block'"; break;
			case '06': $fmkibfvisible = "style='display:block'"; 
				$fmalamatvisible = "style='display:block'"; 
				$fmdocvisible = "style='display:block'"; 
				$fmluasvisible = "style='display:block'"; 
				$fmstatustanahvisible = "style='display:block'";
				$fmkodetanahvisible = "style='display:block'";
				//$fmstatuspenguasaanvisible = "style='display:block'";
				$fmkonstruksivisible = "style='display:block'";
			break;
			case '07': $fmkibgvisible = "style='display:block'"; break;
		}
		//ambil pegawai Pengurus Barang
		$read = mysql_fetch_array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$dt['ref_idpemegang2']."'"));
		$select = mysql_fetch_array(mysql_query("SELECT* FROM ref_ruang WHERE id = '".$dt['ref_idruang']."'"));
		$gdg = mysql_fetch_array(mysql_query(
			"SELECT* FROM ref_ruang 
			WHERE c = '".$select['c']."' 
			And d = '".$select['d']."'
			And e ='".$select['e']."'
			And e1 ='".$select['e1']."'
			And p ='".$select['p']."' and q='0000'"
		));
						
		$this->form_fields = array(	
			'alamat' => array( 'label'=>'Letak/Alamat', 'labelWidth'=>120,'value'=> "<textarea style='width:438;' id='alamat' name='alamat'>". $kib['alamat']."</textarea>", 'row_params'=>"valign='top'", 'labelWidth'=>116, 'type'=>'' ),
			'alamat_kel' => array( 'label'=>'Kelurahan/Desa', 'value'=>$kib['alamat_kel'] , 'type'=>'text' ),
			'alamat_kec' => array( 'label'=>'Kecamatan', 'value'=>$kib['alamat_kec'] , 'type'=>'text' ), 			
			'kota' => array( 'label'=>'Kota/Kab', 	
				'value'=>"<input type='hidden' id='alamat_a' name='alamat_a' value='".$kib['alamat_a']."' >".selKabKota2('alamat_b',$kib['alamat_b'],$Main->DEF_PROPINSI) , 'type'=>'' ),
			'koord' => array('label'=> '',	'value'=> "<table cellspacing='0' cellpadding='0' border='0' width=''>"."<tr><td width=120></td><td width=15></td><td></td></tr>". formEntryKoordinatGPS('koord',$kib['koordinat_gps'], $kib['koord_bidang'])."</table>", 'type'=>'merge' ),
		);
		$formmAlamat = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(
			'dokgedung' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Dokumen Gedung', 'type'=>'merge' ),			
			'dokumen_tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'labelWidth'=>120,'value'=> $kib['dokumen_tgl'], 'type'=>'date' ),			
			'dokumen_no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;No', 'labelWidth'=>120,'value'=> $kib['dokumen_no'], 'type'=>'text' ),			
		);
		$formmDoc = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(
			'luas' => array( 'label'=>'Luas (m2)','labelWidth'=>120, 'value'=>'<INPUT type=text name="luas" value="'.$kib['luas'].'"  onkeypress="return isNumberKey(event)">', 'type'=>'' ),
		);
		$formmLuas = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		$this->form_fields = array(
			'status_tanah' => array( 'label'=>'Status Tanah ','labelWidth'=>120, 'value'=> cmb2D('status_tanah',$kib['status_tanah'],$Main->StatusTanah,''),	'type'=>'' ),
		);
		$formmstatustanah = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(
			'kode_tanah' => array( 'label'=>'No. Kode Tanah','labelWidth'=>120, 'value'=>$kib['kode_tanah'], 'type'=>'text' ),			
		);
		$formmkodetanah = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(
			'status_penguasaan' => array( 'label'=>'Status Penguasaan','labelWidth'=>120, 'value'=> cmb2D('status_penguasaan',$dt['status_penguasaan'],$Main->arStatusPenguasaan,''),'type'=>''	),
		);
		$formmstatuspenguasaan = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		$this->form_fields = array(	
			'konstruksi_tingkat' =>array( 'label'=>'Bertingkat/Tidak', 'labelWidth'=>120,	'value'=> cmb2D('konstruksi_tingkat',$kib['konstruksi_tingkat'],$Main->Tingkat,''), 'type'=>'' ),
			'konstruksi_beton' =>array( 'label'=>'Beton/Tidak', 	'value'=> cmb2D('konstruksi_beton',$kib['konstruksi_beton'],$Main->Beton,''), 'type'=>'' ),
		);
		$formmkonstruksi = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
			
					
			
		//--- form KIB A --------------------------------------------------------------
		$this->form_fields = array(	
			'status_hak' => array( 'label'=>'Status Tanah Hak', 'labelWidth'=>120,'value'=> cmb2D('status_hak',$kib['status_hak'],$Main->StatusHakPakai,''),	'type'=>'' ),
			'sertifikat' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Sertifikat', 'type'=>'merge' ),			
			'statussertifikat' => array( 'label' => '',	'value' => createEntryBersertifikat('bersertifikat', 'sertifikat_tgl', 'sertifikat_no'),'type' => 'merge'),
			'tglsertifikat' => array( 'label' => '&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat', 'labelWidth'=>120, 'value' =>	createEntryTgl("sertifikat_tgl",$sertifikat_tgl, $bersertifikat==1?"":"1", 'tanggal bulan tahun (mis: 1 Januari 1998)','','adminForm',1	),'type' => ''	),
			'no_sertifikat' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat', 'labelWidth'=>120, 'value'=>txtField('sertifikat_no',$sertifikat_no,'100','20','text', $bersertifikat==1?"":"disabled") , 'type'=>'' ),			
			'penggunaan' => array( 'label'=>'Penggunaan', 'value'=> $kib['penggunaan'],'type'=>'text'	),
			//'status_penguasaan' => array( 'label'=>'Status Penguasaan', 'value'=> cmb2D('status_penguasaan',$dt['status_penguasaan'],$Main->arStatusPenguasaan,''),'type'=>''	),
		);		
		$formmKIBA = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB B ---------------------------------------------------------------		
		//no_pabrik,no_rangka,no_mesin,no_polisi,no_bpkb
		$this->form_fields = array(					
			'merk' => array( 'label'=>'Merk', 'labelWidth'=>120, 'value'=> "<textarea style='width:438;' id='merk' name='merk'>".$kib['merk']."</textarea>", 'row_params'=>"valign='top'",	'type'=>'' , 'labelWidth'=>120	),
			'ukuran' => array( 'label'=>'Ukuran/CC', 'labelWidth'=>120, 'value'=> $kib['ukuran'] , 'type'=>'text' ),
			'bahan' => array( 'label'=>'Bahan', 'value'=> $kib['bahan'] , 'type'=>'text' ),	
			'nomor' => array( 'label'=>'', 'value'=> 'Nomor', 'type'=>'merge' ),
			'no_pabrik' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Pabrik', 'value'=> $kib['no_pabrik'] , 'type'=>'text' ),
			'no_rangka' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Rangka', 'value'=> $kib['no_rangka'] , 'type'=>'text' ),
			'no_mesin' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Mesin', 'value'=> $kib['no_mesin'] , 'type'=>'text' ),
			'no_polisi' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Polisi', 'value'=> $kib['no_polisi'] , 'type'=>'text' ),
			'no_bpkb' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;BPKB', 'value'=> $kib['no_bpkb'] , 'type'=>'text' ),
		);
		$formmKIBB = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB C -----------------------------------------------
		$this->form_fields = array(				
			'kondisi_bangunan' =>array( 'label'=>'Konstruksi Bangunan', 'labelWidth'=>120,	'value'=> cmb2D('kondisi_bangunan',$kib['kondisi_bangunan'],$Main->Bangunan,''), 'type'=>'' ),
			//'kondisi_bangunan' =>array( 'label'=>'Konstruksi Bangunan', 'labelWidth'=>120,	'value'=> cmb2D('kondisi_bangunan',$kondisi_bangunan,$Main->Bangunan,''), 'type'=>'' ),
			//'konstruksi_tingkat' =>array( 'label'=>'Bertingkat/Tidak', 	'value'=> cmb2D('konstruksi_tingkat',$konstruksi_tingkat,$Main->Tingkat,''), 'type'=>'' ),
			//'konstruksi_beton' =>array( 'label'=>'Beton/Tidak', 	'value'=> cmb2D('konstruksi_beton',$konstruksi_beton,$Main->Beton,''), 'type'=>'' ),
			'luas_lantai' => array( 'label'=>'Luas Total Lantai (m2)', 'value'=>inputFormatRibuan("luas_lantai"), 'type'=>'' ),
			/*'dokgedung' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Dokumen Gedung', 'type'=>'merge' ),			
			'dokumen_tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'labelWidth'=>120,'value'=> $kib['dokumen_tgl'], 'type'=>'date' ),			
			'dokumen_no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;No', 'labelWidth'=>120,'value'=> $kib['dokumen_no'], 'type'=>'text' ),			
			*/
			//'luas' => array( 'label'=>'Luas Total Tanah (m2)', 'value'=>inputFormatRibuan("luas"), 'type'=>'' ),
			//'kode_tanah' => array( 'label'=>'No. Kode Tanah', 'value'=>$kib['kode_tanah'], 'type'=>'text' ),			
			//'status_penguasaan' => array( 'label'=>'Status Penguasaan', 'value'=> cmb2D('status_penguasaan',$penggunaan,$Main->arStatusPenguasaan,''),'type'=>''	),			
		);		
		$formmKIBC = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB D ------------------------------------------------
		//konstruksi,panjang,lebar,luas,alamat,alamat_a,alamat_b,alamat_kel,alamat_kec,koordinat_gps,koord_bidang,
		//dokumen_tgl,dokumen_no,status_tanah,kode_tanah,
		$this->form_fields = array(				
			'konstruksi' =>array( 'label'=>'Konstruksi', 'labelWidth'=>120,	'value'=> $kib['konstruksi'], 'type'=>'text' ),
			'panjang' =>array( 'label'=>'Panjang', 	'value'=> inputFormatRibuan("panjang",'',$kib['panjang']), 'type'=>'' ),
			'lebar' =>array( 'label'=>'Lebar', 	'value'=> inputFormatRibuan("lebar",'',$kib['lebar']), 'type'=>'' ),
			//'luas' => array( 'label'=>'Luas Total Tanah (m2)', 'value'=>inputFormatRibuan("luas"), 'type'=>'' ),
			/*			
			'dokgedung' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Dokumen Gedung', 'type'=>'merge' ),			
			'dokumen_tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'labelWidth'=>120,'value'=> $kib['dokumen_tgl'], 'type'=>'date' ),			
			'dokumen_no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;No', 'labelWidth'=>120,'value'=> $kib['dokumen_no'], 'type'=>'text' ),			
			*/
			//'status_tanah' => array( 'label'=>'Status Tanah ', 'value'=> cmb2D('status_tanah',$kib['status_tanah'],$Main->StatusTanah,''),	'type'=>'' ),
			//'kode_tanah' => array( 'label'=>'No. Kode Tanah', 'value'=>$kib['kode_tanah'], 'type'=>'text' ),
			//'status_penguasaan' => array( 'label'=>'Status Penguasaan', 'value'=> cmb2D('status_penguasaan',$penggunaan,$Main->arStatusPenguasaan,''),'type'=>''	),			
		);	
		$formmKIBD = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB E -------------------------------
		//buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran
		$this->form_fields = array(		
			'buku' => array( 'label'=>'','labelWidth'=>120, 'value'=> 'Buku Perpustakaan', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'buku_judul' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta', 'value'=> $kib['buku_judul'] , 'type'=>'text'  , 'labelWidth'=>120),
			'buku_spesifikasi' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi', 'value'=> $kib['buku_spesifikasi'] , 'type'=>'text' ),
			'seni' => array( 'label'=>'', 'value'=> 'Barang bercorak Kesenian/Kebudayaan', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'seni_asal_daerah' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah', 'value'=> $kib['seni_asal_daerah'] , 'type'=>'text' ),
			'seni_pencipta' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Pencipta', 'value'=> $kib['seni_pencipta'] , 'type'=>'text' ),
			'seni_bahan' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Bahan', 'value'=> $kib['seni_bahan'] , 'type'=>'text' ),
			'hewan' => array( 'label'=>'', 'value'=> 'Hewan Ternak', 'row_params'=>" height='24' ", 'type'=>'merge' ),
			'hewan_jenis' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Jenis', 'value'=> $kib['hewan_jenis'] , 'type'=>'text' ),
			'hewan_ukuran' => array( 'label'=>'&nbsp;&nbsp;&nbsp;&nbsp;Ukuran', 'value'=> $kib['hewan_ukuran'] , 'type'=>'text' ),
		);
		$formmKIBE = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form Kib F ------------------------------
		$this->form_fields = array(	
			'bangunan' =>array( 'label'=>'Konstruksi Bangunan', 'labelWidth'=>120,	'value'=> cmb2D('bangunan',$kib['bangunan'],$Main->Bangunan,''), 'type'=>'' ),
			'tmt' => array( 'label'=>'Tanggal mulai', 'labelWidth'=>120,
				'value'=> $kib['tmt'], 'type'=>'date' ),			
						
			//'kondisi_bangunan' =>array( 'label'=>'Konstruksi Bangunan', 'labelWidth'=>120,	'value'=> cmb2D('kondisi_bangunan',$kondisi_bangunan,$Main->Bangunan,''), 'type'=>'' ),
			//'konstruksi_tingkat' =>array( 'label'=>'Bertingkat/Tidak', 	'value'=> cmb2D('konstruksi_tingkat',$konstruksi_tingkat,$Main->Tingkat,''), 'type'=>'' ),
			//'konstruksi_beton' =>array( 'label'=>'Beton/Tidak', 	'value'=> cmb2D('konstruksi_beton',$konstruksi_beton,$Main->Beton,''), 'type'=>'' ),
			//'luas_lantai' => array( 'label'=>'Luas Total Lantai (m2)', 'value'=>inputFormatRibuan("luas_lantai"), 'type'=>'' ),
			//'luas' => array( 'label'=>'Luas Total Tanah (m2)', 'value'=>inputFormatRibuan("luas"), 'type'=>'' ),
			/*
			'dokgedung' => array( 'label'=>'', 'labelWidth'=>120,'value'=> 'Dokumen Gedung', 'type'=>'merge' ),			
			'dokumen_tgl' => array( 'label'=>'&nbsp;&nbsp;&nbsp;Tanggal', 'labelWidth'=>120,'value'=> $kib['dokumen_tgl'], 'type'=>'date' ),			
			'dokumen_no' => array( 'label'=>'&nbsp;&nbsp;&nbsp;No', 'labelWidth'=>120,'value'=> $kib['dokumen_no'], 'type'=>'text' ),			
			*/
			//'status_tanah' => array( 'label'=>'Status Tanah ', 'value'=> cmb2D('status_tanah',$kib['status_tanah'],$Main->StatusTanah,''),	'type'=>'' ),
			//'kode_tanah' => array( 'label'=>'No. Kode Tanah', 'value'=>$kib['kode_tanah'], 'type'=>'text' ),			
			//'status_penguasaan' => array( 'label'=>'Status Penguasaan', 'value'=> cmb2D('status_penguasaan',$penggunaan,$Main->arStatusPenguasaan,''),'type'=>''	),			
		);		
		$formmKIBF = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
		
		//--- form KIB G -------------------------------
		//uraian,software_nama,kajian_nama,kerjasama_nama,ket,pencipta,jenis
		$this->form_fields = array(		
			'uraian' => array( 'label'=>'Uraian', 'value'=> "<input type='text' name='uraian' id='uraian' size='59' value='".$kib['uraian']."'>"  , 'type'=>''  , 'labelWidth'=>120),
			'pencipta' => array( 'label'=>'Pencipta', 'value'=>"<input type='text' name='pencipta' id='pencipta' size='59' value='".$kib['pencipta']."'>"  , 'type'=>'' ),
			'jenis' => array( 'label'=>'Jenis', 'value'=> "<input type='text' name='jenis' id='jenis' size='59' value='".$kib['jenis']."'>"  , 'type'=>'' ),
			
		);
		$formmKIBG = "<table style='width:100%' >".$this->setForm_content_fields()."</table>";
				
		
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
							href='javascript:".$this->Prefix.".Simpan2()'> 
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
		$fmIDBARANG = '';
		
		$this->form_fields = array(		
			'judul' => array( 'label'=>'', 'value'=>"<span style='font-size: 18px;font-weight: bold;color: #C64934;'>Reclass</span>", 'type'=>'merge' ),		
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'row_params'=>" height='24' ", 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'row_params'=>" height='24' ", 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'row_params'=>" height='24' ", 'type'=>'' ),
			'seksi' => array(  'label'=>'SUBUNIT', 'value'=> $seksi, 'row_params'=>" height='24' ", 'type'=>'' ),
			'nama_barang_old'=> array(  'label'=>'Reclass Dari', 'value'=> $brg['f'].'.'.$brg['g'].'.'.$brg['h'].'.'.$brg['i'].'.'.$brg['j'].' / '.$brg['nm_barang'] , 'row_params'=>" height='24' ", 'type'=>'' ),
			'staset' => array(  'label'=>'Status Aset', 'value'=> $Main->StatusAsetView[$dt['staset']-1][1], 'row_params'=>" height='24' ", 'type'=>'' ),
			'nama_barang' => array(	'label'=>'Ke Nama Barang',  'value' =>cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2d.php",	"fmIDBARANG", "fmNMBARANG",	"$ReadOnly","$DisAbled"), 'type' => ''	),
			'thn_perolehan' => array( 'label'=> 'Tahun Perolehan', 'value'=> 				
				"<input type='text' name='thn_perolehan' id='thn_perolehan' value='{$dt['thn_perolehan']}' size='4' maxlength='4' onkeypress='return isNumberKey(event)'>", 
				'type'=>'' ),
			'noreg' => array( 'label'=> 'Nomor Register', 'value'=>'<INPUT type=text id="noreg" name="noreg" value="'.$dt['noreg'].'" size="4" maxlength="4" onkeypress="return isNumberKey(event)"	> <span style="color:red;">4 digit (mis: 0002)</span>'.
					"<input type='button' value='Cari No. Akhir' onclick='".$this->Prefix.".getNoRegAkhir()' title='Cari No. Register Terakhir'>".'','type'=>''	),
			'tgl_buku' => array( 'label' => 'Tgl. Buku', 'value' =>	createEntryTgl("tgl_buku",$dt['tgl_buku']),	'type' => '' ),
			
			/*'harga' => array( 'label'=> 'Harga Satuan Barang', 'value'=> 'Rp.'.inputFormatRibuan("jml_harga", ($entryMutasi==FALSE? '':' readonly="" '),$dt['harga'] ), 'type'=>''),
			'jml_barang' => array( 'label'=> 'Jumlah Barang', 'value'=> "<input type=\"text\" size='4' maxlength='2' name=\"jml_barang\" value=\"$dt[jml_barang]\" ".$jmlBrgReadonly." onkeypress='return isNumberKey(event)' />&nbsp;&nbsp; <!--<span style='color: red;'> (max 99)</span> &nbsp&nbsp -->
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
			),*/
			'fmkiba' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkiba' name='tidaktercatat_formkiba' $fmkibavisible>".$formmKIBA."</div>", 'type'=>'merge'),
			'fmkibb' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibb' name='tidaktercatat_formkibb' $fmkibbvisible>".$formmKIBB."</div>", 'type'=>'merge' ),
			'fmkibc' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibc' name='tidaktercatat_formkibc' $fmkibcvisible>".$formmKIBC."</div>",'type'=>'merge'	),
			'fmkibd' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibd' name='tidaktercatat_formkibd' $fmkibdvisible>".$formmKIBD."</div>", 'type'=>'merge' ),
			'fmkibe' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibe' name='tidaktercatat_formkibe' $fmkibevisible>".$formmKIBE."</div>", 'type'=>'merge' ),	
			'fmkibf' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibf' name='tidaktercatat_formkibf' $fmkibfvisible>".$formmKIBF."</div>", 'type'=>'merge' ),	
			'fmkibg' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkibg' name='tidaktercatat_formkibg' $fmkibgvisible>".$formmKIBG."</div>", 'type'=>'merge' ),	
			
			
			'fmalamat' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formalamat' name='tidaktercatat_formkibf' $fmalamatvisible>".$formmAlamat."</div>", 'type'=>'merge' ),	
			'fmdoc' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formdoc' name='tidaktercatat_formdoc' $fmdocvisible>".$formmDoc."</div>", 'type'=>'merge' ),	
			'fmluas' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formluas' name='tidaktercatat_formluas' $fmluasvisible>".$formmLuas."</div>", 'type'=>'merge' ),	
			'fmstatustanah' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formstatustanah' name='tidaktercatat_formstatustanah' $fmstatustanahvisible>".$formmstatustanah."</div>", 'type'=>'merge' ),	
			'fmkodetanah' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkodetanah' name='tidaktercatat_formkodetanah' $fmkodetanahvisible>".$formmkodetanah."</div>", 'type'=>'merge' ),	
			'fmstatuspenguasaan' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formstatuspenguasaan' name='tidaktercatat_formstatuspenguasaan' $fmstatuspenguasaanvisible>".$formmstatuspenguasaan."</div>", 'type'=>'merge' ),	
			'fmkonstruksi' => array(	'label'=> '', 'value'=> "<div id='tidaktercatat_formkonstruksi' name='tidaktercatat_formkonstruksi' $fmkonstruksivisible>".$formmkonstruksi."</div>", 'type'=>'merge' ),	
			 
			/*'ket' => array( 'label'=>'Keterangan', 
				'value'=> "<textarea style='width:438;' id='ket' name='ket'>".$kib['ket']."</textarea>",
				'row_params'=>"valign='top'",
				'type'=>'' 
			),*/
			'tgl_sensus' => array( 'label' => 'Tanggal Sensus',	'value' =>	TglInd($dt['tgl_sensus']), 'type' => ''	),		
			'tahun_sensus' => array( 'label'=> 'Tahun Sensus',	'value'=> $dt['tahun_sensus'], 'type'=>'' ),				
			'ket' => array( 'label'=>'Keterangan', 'value'=> "<textarea cols=60 rows=2 id='ket' name='ket'>{$kib['ket']}</textarea>",'type'=>''),				
			/*'petugas' => array( 
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
			*/
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
	
	function genCetak_kerja2($xls= FALSE, $Mode=''){
		global $Main;		
		global $Ref;
				
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];		
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];		
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		//$this->form_idplh = $cbid[0];
		
				
		/*================================================================
		  Untuk Data Bidang, OPD,Biro,No usulan,Tgl Usulan
		*/
		//$get =mysql_fetch_array(mysql_query("SELECT* FROM barang_tidak_tercatat WHERE Id ='".$this->form_idplh ."' "));
		//$nmopdarr=array();
		//============================= ambil Bidang ============================================			
		$bidang = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$c."' "));	
		//	if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
		//=======================================================================================
		
		//============================== ambil OPD =================================================================
		$opd = mysql_fetch_array(mysql_query("select * from v_opd where c='".$c."' and d='".$d."' "));	
		//	if($read['nmbidang']<>'') $nmopdarr[] = $opd['nmopd'];
		//==========================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$unit = mysql_fetch_array(mysql_query("select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."' "));		
		//	if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
		//	   $nmopd = join(' <br/> ', $nmopdarr );
		//====================================================================================================================================

		//================== ambil Biro /UPTD / B ============================================================================================
		$subunit = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."'  "));		
		//	if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
		//	   $nmopd = join(' <br/> ', $nmopdarr );
		//====================================================================================================================================


			
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
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
			<div style='width:100%'>
			<table class=\"rangkacetak\" style='width:30cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//=========== CONTENT =============================================================================================
		//echo'<br><br><br><br><br><br><br><br>';
		echo  //$c.' '.$d.' '.$e.' '.
			'<div style="width:30cm">
			<table class="rangkacetak" style="width:30cm">
			<tbody><tr><td valign="top"><table style="width:100%" border="0">
			<tbody><tr>
				<td class="judulcetak"><div align="CENTER">Kertas Kerja Barang Tidak Tercatat </div></td>
			</tr>
			</tbody></table>	
			<table width="100%" border="0">
				<tbody><tr>
					<td class="subjudulcetak"><table cellpadding="0" cellspacing="0" border="0" width="100%">
			
			<tbody><tr valign="top"> <td style="font-weight:bold;font-size:10pt" width="150">PROVINSI</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt"> JAWA BARAT</td> </tr> 
			<tr valign="top"> <td style="font-weight:bold;font-size:10pt">KABUPATEN/KOTA</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt"> -</td> </tr> 
			
			<tr valign="top"> <td style="font-weight:bold;font-size:10pt">BIDANG</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt">'.$bidang['nmbidang'].' </td> </tr> 
			<tr valign="top"> <td style="font-weight:bold;font-size:10pt">SKPD</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt">'.$opd['nmopd'].' </td> </tr> 
			<tr valign="top"> <td style="font-weight:bold;font-size:10pt">UNIT</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt">'.$unit['nmunit'].' </td> </tr> 
			<tr valign="top"> <td style="font-weight:bold;font-size:10pt">UNIT</td><td style="width:10;font-weight:bold;font-size:10pt">:</td><td style="font-weight:bold;font-size:10pt">'.$seksi['nm_skpd'].' </td> </tr> 
			</tbody></table></td>
				</tr>
			</tbody></table><br><div id="cntTerimaKondisi"></div>
				<div id="cntTerimaDaftar"><table class="cetak" border="1" style="margin:4 0 0 0;width:100%"><tbody><tr>
				<th class="th02" colspan="3">Nomor</th>
				<th class="th02" colspan="3">Spesifikasi Barang</th>
				
				
				<th class="th01" rowspan="2" width="50">Tahun Perolehan/<br>Keadaan Barang (B,KB,RB)</th>
				<th class="th01" rowspan="2" width="100">Gedung/Ruang</th>
				<th class="th01" rowspan="2" width="100">Penanggung Jawab Barang</th>
				<th class="th02" colspan="3" width="100">Jumlah</th>
				<!--<th class="th01" rowspan="2">Catatan</th>-->
				<th class="th01" rowspan="2" style="min-width:120;">Catatan/<br>Tahun/<br>Tanggal/<br>Petugas Sensus</th>
				
				
				
				</tr>
				<tr>
				<th class="th01">No.</th>				
				
				<th class="th01" width="100">Kode <br>Barang</th>
				<th class="th01" width="50">Reg.</th>
				<th class="th01" width="100">Nama / Jenis Barang</th>
				<th class="th01" width="120">Merk / Tipe / Alamat</th>
				<th class="th01" width="100">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin / Bahan</th>
				<th class="th01" width="50">Barang</th>
				<th class="th01" width="70">Harga Satuan</th>
				<th class="th01" width="100"> Harga Perolehan </th>
				
				</tr>

			';
		echo'<tr height="490px">
		      <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			 </tr>';
		echo'</tbody></table>';
		//echo'<div style="border:1px solid;height:300px"></div>';
		
		//echo'<br><br><br><br>';
	    echo"</div>	".			
				//$this->PrintTTD($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo"
			</td>	
			</tr>
			</tbody>
		  	</div>
			</form>		
			</body>	
			</html>";
	}


function PrintTTD($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmSEKSI, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;


    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    $TITIMANGSA = $Main->CETAK_LOKASI.", " . JuyTgl1(date("Y-m-d"));
    if (c == '04') {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT'  and e1='$fmSEKSI' and ttd1 = '1' ");
    } else {
        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '00' and ttd1 = '1' ");
    }
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD1 = $isi['nik'];
        $NAMASKPD1 = $isi['nm_pejabat'];
        $JABATANSKPD1 = $isi['jabatan'];
    }
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
        $JABATANSKPD2 = $isi['jabatan'];
    }
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='MENGETAHUI' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='KEPALA OPD'	STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='PENGURUS BARANG' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA OPD</span>";
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:100%' border=0>
				<tr> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR> 
					$vJABATAN1
					<BR><BR><BR><BR><BR><BR>
					$vNAMA1
					<br>
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp4'>
					$vTITIKMANGSA<BR> 
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}

	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
				"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/ruang.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".	
				"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
}
$Reclass = new ReclassObj();

?>