<?php

class BaRekonObj extends DaftarObj2{
	var $Prefix = 'BaRekon'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'barekon'; //daftar
	var $TblName_Hapus = 'barekon';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 7, 6,6);//berdasar mode
	var $FieldSum_Cp2 = array( 5, 5,5);	
	var $checkbox_rowspan = 2;
	var $totalCol = 11; //total kolom daftar
	//var $fieldSum_lokasi = array( 7);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='BaRekon.xls';
	var $Cetak_Judul = 'BA Rekonsiliasi';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '14cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'BA Rekonsiliasi';
	var $PageIcon = 'images/penilaian_ico.gif';
	var $pagePerHal= '25';
	var $FormName = 'BaRekonForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	function setTitle(){
		global $Main;
		return 'BA Rekonsiliasi';	

	}
	function setCetakTitle(){
		return " <DIV ALIGN=CENTER>BA Rekonsiliasi";
	}
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batal()","delete_f2.png","Hapus", 'Hapus').
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Surat()","print_f2.png",'Surat',"Surat")."</td>".	
			"</td>";
	}
	
	function setMenuView(){		
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakSK(\"$Op\")","print_f2.png",'Cetak SK',"Cetak SK")."</td>".
			//"<td>".genPanelIcon("javascript:Penggunaan_Det.cetakAll(\"$Op\")","print_f2.png",'Lampiran',"Cetak Lampiran")."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			
		
	}
	
	/*function setPage_HeaderOther(){	
		global $Main;
		global $HTTP_COOKIE_VARS;
		$Pg = $_REQUEST['Pg'];
		
		$koreksi = '';
		$pemindahtanganan = '';
		$pemanfaatan = '';
		switch ($Pg){
			case 'Koreksi': $Koreksi ="style='color:blue;'"; break;
			case 'Pemindahtanganan': $Pemindahtanganan ="style='color:blue;'"; break;
			case 'Pemanfaatan': $Pemanfaatan ="style='color:blue;'"; break;
		}
		
			//index.php?Pg=09
			return "";
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=Koreksi\" title='Koreksi' $Koreksi>Koreksi </a> |			
			<A href=\"pages.php?Pg=Pemindahtanganan\" title='Pemindahtanganan' $Pemindahtanganan>Pemindahtanganan</a> | 
			<A href=\"pages.php?Pg=Pemanfaatan\" title='Pemanfaatan' $Pemanfaatan>Pemanfaatan</a>  
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}*/
	
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
		
		//$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku']; //tgl buku
		//$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		//$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmNip = $_REQUEST['fmNip'];
		$fmNama = $_REQUEST['fmNama'];
		$fmKdBarang = $_REQUEST['fmKdBarang'];
		$fmThnPerolehan = $_REQUEST['fmThnPerolehan'];
		$fmNoreg = $_REQUEST['fmNoreg'];		
		$fmIDBrg = $_REQUEST['fmIDBrg'];		
		$fmIDAwal = $_REQUEST['fmIDAwal'];
				
		//data order ------------------------------
		/*$arrOrder = array(
			array('1','Tanggal Usulan'),
			array('2','Tahun Anggaran'),
		);*/
		
		
		//tampil -------------------------------
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td style='padding:6'>
			</td>".
			"<td width='375'>".
				
				$barcodeCari.
				
					
				//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>-->
			"</td>
			</tr></table>
				<table width=\"100%\" class=\"adminform\" style=\"margin: 4 0 0 0;\">
					<tbody>
					<tr valign=\"top\">
						<td> 
							<div style=\"float:left\">". 
								"NIP &nbsp;<input type=\"text\" name=\"fmNip\" id=\"fmNip\" size=\"20\" value=\"$fmNip\">&nbsp;&nbsp;
								Nama &nbsp;<input type=\"text\" name=\"fmNama\" id=\"fmNama\" size=\"30\" value=\"$fmNama\">&nbsp;&nbsp;
								Kode Barang &nbsp;<input type=\"text\" name=\"fmKdBarang\" id=\"fmKdBarang\" size=\"20\" value=\"$fmKdBarang\">&nbsp;&nbsp;
								Tahun Perolehan&nbsp;<input type=\"text\" name=\"fmThnPerolehan\" id=\"fmThnPerolehan\" size=\"4\" value=\"$fmThnPerolehan\">&nbsp;&nbsp;
								No Reg &nbsp;<input type=\"text\" name=\"fmNoreg\" id=\"fmNoreg\" size=\"4\" value=\"$fmNoreg\">&nbsp;&nbsp;
								ID Barang &nbsp;<input type=\"text\" name=\"fmIDBrg\" id=\"fmIDBrg\" size=\"4\" value=\"$fmIDBrg\">&nbsp;&nbsp;
								ID Awal &nbsp;<input type=\"text\" name=\"fmIDAwal\" id=\"fmIDAwal\" size=\"4\" value=\"$fmIDAwal\">&nbsp;&nbsp;".
								"<input type=\"button\" onclick=\"".$this->Prefix.".refreshList(true)\" value=\"Tampilkan\">&nbsp;&nbsp;
								
							</div>
						</td>
					</tr>
					</tbody>
				</table>".
		
			"";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');				
		$fmNip = $_REQUEST['fmNip'];
		$fmNama = $_REQUEST['fmNama'];
		$fmKdBarang = $_REQUEST['fmKdBarang'];
		$kdbrg=str_replace('.','',$fmKdBarang);
		$fmThnPerolehan = $_REQUEST['fmThnPerolehan'];
		$fmNoreg = $_REQUEST['fmNoreg'];		
		$fmIDBrg = $_REQUEST['fmIDBrg'];		
		$fmIDAwal = $_REQUEST['fmIDAwal'];
		
		//Kondisi -------------------------		
		$arrKondisi= array();
		/*$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);
		/*if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='000') ) $arrKondisi[] = "e1='$fmSEKSI'";*/
		if(!empty($fmNip)) $arrKondisi[] = "nip ='$fmNip'";
		if(!empty($fmNama)) $arrKondisi[] = "nama like '%$fmNama%'";
		if(!empty($fmKdBarang)) $arrKondisi[] = "concat(f,g,h,i,j)='$kdbrg'";
		if(!empty($fmThnPerolehan)) $arrKondisi[] = "thn_perolehan='$fmThnPerolehan'";	
		if(!empty($fmNoreg)) $arrKondisi[] = "noreg='$fmNoreg'";	
		if(!empty($fmIDBrg)) $arrKondisi[] = "idbi='$fmIDBrg'";	
		if(!empty($fmIDAwal)) $arrKondisi[] = "idbi_awal='$fmIDAwal
		'";	
		
		/*$arrKondisi[] = getKondisiSKPD2(
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);*/

		//status kondisi
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
				
		//order ---------------------------
		/*$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc1 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc1 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc1 "; break;			
		}
		*/
			
		
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal;
		$Limit = $Mode == 3 ? '': $Limit;
		//$Limit = '';
		//$Limit = ' limit 0,1 '; //tes akuntansi
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
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				<script type='text/javascript' src='js/rekon/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20'>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01'>Tanggal</th>
				<th class='th01'>NIP</th>
				<th class='th01'>Nama Penanggung Jawab</th>
				<th class='th01'>Kode /<br>Nama SKPD</th>
				<th class='th01'>Kode Barang/<br>Nama Barang</th>
				<th class='th01'>Kode Akun/<br>Nama Akun</th>
				<th class='th01'>Tahun/<br>No. Reg</th>
				<th class='th01'>Harga Perolehan</th>
				<th class='th01'>ID Barang/<br>ID Awal</th>
				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		

		//kdBarang & $nmBarang
		$kdBarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
		$brg=mysql_fetch_array(mysql_query("SELECT
			  `dd`.`nm_barang`, `ref_jurnal`.`nm_account`, `dd`.`ka`, `dd`.`kb`, `dd`.`kc`,
			  `dd`.`kd`, `dd`.`ke`
			FROM
			  `ref_barang` `dd` LEFT JOIN
			  `ref_jurnal` ON `dd`.`ka` = `ref_jurnal`.`ka` AND
			    `dd`.`kb` = `ref_jurnal`.`kb` AND `dd`.`kc` = `ref_jurnal`.`kc` AND
			    `dd`.`kd` = `ref_jurnal`.`kd` AND `dd`.`ke` = `ref_jurnal`.`ke`
			WHERE concat(f,g,h,i,j) = '".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."'"));
		$nmBarang = $brg['nm_barang'];
		
		//$kdAkun & $nmAkun
		$kdAkun = $brg['ka'].'.'.$brg['kb'].'.'.$brg['kc'].'.'.$brg['kd'].'.'.$brg['ke'];
		$nmAkun = $brg['nm_account'];
		
		//$kdSKPD & $nmSKPD
		$kdSKPD = $isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$isi['e1'];
		$bidang = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='00' and e='00' and e1='000'"));
		$skpd = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='00' and e1='000'"));
		$unit = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='000'"));
		$subunit = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));
		$nmSKPD = $bidang['nm_skpd'].'<br>'.$skpd['nm_skpd'].'<br>'.$unit['nm_skpd'].'<br>'.$subunit['nm_skpd'];
		
		//harga perolehan
		$bi=mysql_fetch_array(mysql_query("select jml_harga from buku_induk where Id='".$isi['idbi']."' "));
		$jml_harga = $bi['jml_harga'];
				
			$Koloms[] = array('align="center" width="20"', $no.'.' );
 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="center"',TglInd($isi['tgl']));
			$Koloms[] = array('align="center"',$isi['nip']);
			$Koloms[] = array('align="left"',$isi['nama']);
			$Koloms[] = array('align="left"',$kdSKPD.'/<br>'.$nmSKPD); 
			$Koloms[] = array('align="left"',$kdBarang.'/<br>'.$nmBarang);
			$Koloms[] = array('align="left"',$kdAkun.'/<br>'.$nmAkun);
	 		$Koloms[] = array('align="left"',$isi['thn_perolehan'].'/<br>'.$isi['noreg']); 
	 		$Koloms[] = array('align="right"',number_format($jml_harga, 2, ',', '.'));
	 		$Koloms[] = array('align="center"',$isi['idbi'].'/<br>'.$isi['idbi_awal']);

		return $Koloms;
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			
			case 'cetakba':{	
				$json= FALSE;
	   			$this->cetakba();							
			break;
			}
			
			case 'setSurat':{				
			$fm = $this->setSurat();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
			/*default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
		 	break;
		 	}*/
			
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function cetakba(){
		$width='21cm';
		$height='33cm';
		$f_size1='11pt';
		$f_size2='12pt';
		
		$c = $_REQUEST['c'];	
		$d = $_REQUEST['d'];	
		$e = $_REQUEST['e'];	
		$e1 =$_REQUEST['e1'];	
		$thn_anggaran = $_REQUEST['thn_anggaran'];	
		$thn_sblm = $thn_anggaran-1;	
		$tgl1 = $_REQUEST['tgl_ba'];
		$tgl_ba = explode('-',$tgl1);
		$terbilang_tglba = bilang($tgl_ba[2]);
		$terbilang_thnba = bilang($tgl_ba[0]);
		$bln1 = $tgl_ba[1]; 
		switch($bln1 ){
			case '01':$bulanba = "Januari";break;
			case '02':$bulanba = "Februari";break;
			case '03':$bulanba = "Maret";break;
			case '04':$bulanba = "April";break;
			case '05':$bulanba = "Mei";break;
			case '06':$bulanba = "Juni";break;
			case '07':$bulanba = "Juli";break;
			case '08':$bulanba = "Agustus";break;
			case '09':$bulanba = "September";break;
			case '10':$bulanba = "Oktober";break;
			case '11':$bulanba = "November";break;
			case '12':$bulanba = "Desember";break;
		}
		
		$today = getdate(mktime(0,0,0,$tgl_ba[1],$tgl_ba[2],$tgl_ba[0]));//mktime(hour, minute, second, month, day, year)
		switch($today['weekday'] ){
			case 'Monday':$hari = "Senin";break;
			case 'Tuesday':$hari = "Selasa";break;
			case 'Wednesday':$hari = "Rabu";break;
			case 'Thursday':$hari = "Kamis";break;
			case 'Friday':$hari = "Jumat";break;
			case 'Saturday':$hari = "Sabtu";break;
			case 'Sunday':$hari = "Minggu";break;			
		}
		$tgl_sk = $_REQUEST['tgl_sk'];	
		$no_sk = $_REQUEST['no_sk'];	
		
		//Pengelola Barang   
		$nama_pengelola =$_REQUEST['nm_pb'];
		$pangkat_pengelola =$_REQUEST['pangkat_pb'];
		$nip_pengelola =$_REQUEST['nip_pb'];
		$jabatan_pengelola =$_REQUEST['jabatan_pb'];
		//---------------------------------
		//Pengguna   
		$nama_pengguna =$_REQUEST['nm_pga'];
		$pangkat_pengguna =$_REQUEST['pangkat_pga'];
		$nip_pengguna =$_REQUEST['nip_pga'];
		$jabatan_pengguna =$_REQUEST['jabatan_pga'];
		//---------------------------------
		//Pengurus  
		$nama_pengurus =$_REQUEST['nm_pgs'];
		$pangkat_pengurus =$_REQUEST['pangkat_pgs'];
		$nip_pengurus =$_REQUEST['nip_pgs'];
		$jabatan_pengurus =$_REQUEST['jabatan_pgs'];
		$alamat_pengurus =$_REQUEST['alamat_pgs'];
		//---------------------------------
		//Kasubag  
		$nama_kasubag =$_REQUEST['nm_ksb'];
		$pangkat_kasubag =$_REQUEST['pangkat_ksb'];
		$nip_kasubag =$_REQUEST['nip_ksb'];
		$jabatan_kasubag =$_REQUEST['jabatan_ksb'];
		$alamat_kasubag =$_REQUEST['alamat_ksb'];

		//---------------------------------
		if(($nama_kasubag!='' && $nama_pengurus!='') || ($nama_kasubag!='' && $nama_pengurus=='')){
			$nama_ttd = $nama_kasubag;
			$nip_ttd = $nip_kasubag;
		}else{
			$nama_ttd = $nama_pengurus;
			$nip_ttd = $nip_pengurus;
		}
		
		//GET SKPD /UNIT
		$qry = "SELECT nm_skpd FROM ref_skpd WHERE c='$c' and d='$d' ";
		$qry2 = "SELECT nm_skpd FROM ref_skpd WHERE c='$c' and d='$d' and e='$e'";
		$get = mysql_fetch_array(mysql_query($qry));
		$get2 = mysql_fetch_array(mysql_query($qry2));
		
		$skpd_unit = $get['nm_skpd'].'&nbsp/&nbsp'.$get2['nm_skpd'];
		$skpd = $get['nm_skpd'];
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
			
		
		
		// REKONSILIASI ASET TETAP ===============================================================================
		//echo"test";
		//$qry = "SELECT * FROM ref_barang WHERE f<>00 and g='00' and f<>07";
		//$kondskpd = 'c'
		$arrKond = array();
		//$arrKond[] = " kint='01'";
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		//if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		//if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond); 
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '. $KondisiSKPD;
		
		$qry = 
			"select aa.kint,aa.ka,aa.kb,aa.f,aa.nm_barang,

			debet_saldoawal_brg, kredit_saldoawal_brg,
			debet_saldoawal, kredit_saldoawal,
			(debet_saldoawal -kredit_saldoawal) as saldoawal,
			debet, kredit, kredit_lain,			
			(debet_saldoawal -kredit_saldoawal + debet - kredit) as saldoakhir,
			debet1, kredit1,	
			debet2, kredit2,
			kredit_lain1, kredit_lain2,
			kredit_ekstra1, kredit_ekstra2,
			debet_koreksi, kredit_koreksi
			
			  from v_ref_kib_keu aa
			
			left join(			
				select IFNULL(f,'00') as f,
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,jml_barang_d,0)) as debet_saldoawal_brg, 
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,jml_barang_k,0)) as kredit_saldoawal_brg,
			    sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,debet,0)) as debet_saldoawal, 
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,kredit,0)) as kredit_saldoawal,
			    sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10,debet,0)) as debet, 
				sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10 ,kredit  ,0)) as kredit,
				#sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9,kredit  ,0)) as kredit,
				sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),kredit  ,0)) as kredit_lain,
				
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10 ,debet,0)) as debet1, 
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9  ,kredit,0)) as kredit1,				
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10 && jns_trans=9  ,kredit,0)) as kredit_lain1,
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10 && jns_trans=3  ,kredit,0)) as kredit_ekstra1,
			
			    sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10,debet,0)) as debet2, 
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9 ,kredit,0)) as kredit2,
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10 && jns_trans=9 ,kredit,0)) as kredit_lain2,
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10 && jns_trans=3 ,kredit,0)) as kredit_ekstra2
			
				from t_jurnal_aset
				where  kint='01' and ka='01' $KondisiSKPD
				group by f 
			)bb	on aa.f =bb.f
			
			left join  (
				SELECT IFNULL(f,'00') as f, 
			  	sum(if( tahun=$thn_anggaran , debet ,0 )) as debet_koreksi ,  
			  	sum(if( tahun=$thn_anggaran , kredit,0 )) as kredit_koreksi 
			  	from t_rekon_koreksi where c= '08' and d= '01'  group by f
			)cc on aa.f=cc.f  

			
			where aa.kint='01' and aa.ka='01' and aa.g='00' and aa.f<>'00'"; //echo $qry;
		$get_qry = mysql_query($qry);
		$no=0; $saldoawal= 0; 
		$debet=0; $kredit=0; 
		$debet1=0; $kredit1=0; //kredit aset bukan lain2 dan ekstra
		$debet2=0; $kredit2=0; 
		$kredit_lain1 = 0; $kredit_lain2 = 0; //kredit aset lain lain  
		$kredit_ekstra1 = 0; $kredit_ekstra2 = 0; //kredit aset  extra
		$debet_koreksi=0; $kredit_koreksi=0;
		$saldoakhir=0;
		while($row = mysql_fetch_array($get_qry)){
			//saldo sebelum koreksi
			
			
			
			$no++;
			$golongan.="
			<tr>
				<td style='border: 1px solid black;'>$no</td>
				<td style='border: 1px solid black;'>".$row['nm_barang']."</td>
				<td style='border: 1px solid black;text-align:right;'>".number_format($row['saldoawal'],2,',','.')."</td>
				<td style='border: 1px solid black;text-align:right;'>".number_format($row['saldoawal'] + $row['debet_koreksi'] - $row['kredit_koreksi'],2,',','.')."</td>
				<td style='border: 1px solid black;text-align:right;'>".number_format($row['debet'],2,',','.')."</td>
				<td style='border: 1px solid black;text-align:right;'>".number_format($row['kredit'],2,',','.')."</td>
				<td style='border: 1px solid black;text-align:right;'>".number_format($row['saldoakhir'],2,',','.')."</td>	
			</tr>";
			
			$saldoawal += $row['saldoawal'];
			$debet +=$row['debet'];
			$kredit += $row['kredit'];
			$saldoakhir += $row['saldoakhir'];
			$debet1 +=$row['debet1'];
			$kredit1 += $row['kredit1'];
			$debet2 +=$row['debet2'];
			$kredit2 += $row['kredit2'];
			$kredit_lain1 +=$row['kredit_lain1'];  $kredit_lain2 += $row['kredit_lain2'];
			$kredit_ekstra1 +=$row['kredit_ekstra1'];  $kredit_ekstra2 += $row['kredit_ekstra2'];
			$debet_koreksi += $row['debet_koreksi']; $kredit_koreksi +=$row['kredit_koreksi'];
		}
		
		$jml_sebelum = number_format($saldoawal,2,',','.');
		$jml_sesudah = number_format( ($debet_koreksi-$kredit_koreksi) ,2,',','.');
		$jml_tambah = number_format($debet,2,',','.');
		$jml_kurang = number_format($kredit,2,',','.');
		$jml_saldo_akhir = number_format($saldoawal+ ($debet_koreksi-$kredit_koreksi) +  ($debet-$kredit),2,',','.');
		echo"<div style='width:$width;font-family:Arial;margin-top:50px;page-break-after:always;'>
			<html>
				<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
					td {
					font-size: 10pt;
					}
					
					td.satu {
						font-size: 7pt;
					}
					
					</style>
					
				</head>
			<body>
			<form name='adminForm' id='adminForm' method='post' action=''>
				<table style='width:100%' border='0'>
					<tr>
						 <td>
						 	<div align='center' style='font-size:10pt;font-family:Arial;'>
								BERITA ACARA<br>
						 		REKONSILIASI ASET TETAP
							</div>
						</td>
					</tr>
				</table>
				<br>
				<table border='0' class='rangkacetak' style='width:100%;'>
					<tr>
						<td colspan=4>
							Pada hari ini $hari tanggal $terbilang_tglba bulan $bulanba tahun $terbilang_thnba, kami yang bertanda tangan di bawah ini :
							<br><br>
						</td>
					</tr>
					<tr>
						<td width=2%>1. </td>
						<td width=20%>Nama Pengguna Barang </td>
						<td width=2% align=center>:</td>
						<td width=70%>$nama_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Pangkat / Golongan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$pangkat_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>NIP</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nip_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Jabatan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$jabatan_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>SKPD / Unit Kerja</td>
						<td width=2% align=center>:</td>
						<td width=70%>$skpd</td>
					</tr>
					<tr>
						<td colspan=4>
							<br>
						</td>
					</tr>
					<tr>
						<td width=2%>2. </td>
						<td width=20%>Nama</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nama_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Pangkat / Golongan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$pangkat_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>NIP</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nip_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Jabatan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$jabatan_pengelola</td>
					</tr>
					<tr>
						<td colspan=4>
						<br>
							Telah melaksanakan rekonsiliasi data aset tetap untuk Neraca Per Tanggal 30 Desember $thn_anggaran, dengan hasil
							sebagai berikut:
							<br><br>
						</td>
					</tr>
					<tr>
						<td colspan=4>
							
							<table class='rangkacetak' style='width:100%;border: 1px solid black;'>
								<tr>
									<td style='border: 1px solid black;'>No</td>
									<td style='border: 1px solid black;text-align:center;'>Nama Aset Tetap</td>
									<td style='border: 1px solid black;text-align:center;' colspan=2>Saldo Awal 1 Januari $thn_anggaran</td>
									<td style='border: 1px solid black;text-align:center;' colspan=2>Mutasi 01 Januari s/d 30 Desember $thn_anggaran</td>
									<td style='border: 1px solid black;text-align:center;' rowspan=2>Saldo Akhir Per 31 Desember $thn_anggaran</td>
								</tr>
								<tr>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;text-align:center;'>Sebelum Koreksi</td>
									<td style='border: 1px solid black;text-align:center;'>Sesudah Koreksi</td>
									<td style='border: 1px solid black;text-align:center;'>Penambahan</td>
									<td style='border: 1px solid black;text-align:center;'>Pengurangan</td>
								</tr>
								<tr>
									<td style='border: 1px solid black;'><br></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
								</tr>
								<tr>
									<td style='border: 1px solid black;'><br></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
								</tr>
								".$golongan."
								<tr>
									<td style='border: 1px solid black;'><br></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'></td>
								</tr>
								<tr>
									<td style='border: 1px solid black;'></td>
									<td style='border: 1px solid black;'>Jumlah</td>
									<td style='border: 1px solid black;text-align:right;'>$jml_sebelum</td>
									<td style='border: 1px solid black;text-align:right;'>$jml_sesudah</td>
									<td style='border: 1px solid black;text-align:right;'>$jml_tambah</td>
									<td style='border: 1px solid black;text-align:right;'>$jml_kurang</td>
									<td style='border: 1px solid black;text-align:right;'>$jml_saldo_akhir</td>
								</tr>
							</table>
							
						</td>
					</tr>
					<tr>
						<td colspan=4>
							<table class='rangkacetak' style='width:100%;'>
								<tr>
									<td class='satu'>catatan:</td>
									<td class='satu'>- Aset tetap yang diperoleh pada tahun $thn_sblm dicatat sebesar nilai perolehan,</td>
								</tr>
								<tr>
									<td></td>
									<td class='satu'>- Aset tetap yang diperoleh sebelum tahun $thn_sblm dicatat sebesar nilai wajar,jika tidak memungkinkan maka nilai
									aset tetap didasarkan pada nilai perolehan</td>
								</tr>
							</table><br><br>
						</td>
					</tr>
					<tr>
						<td colspan=4>
							Demikian Berita Acara Rekonsiliasi Data Aset Tetap, Belanja Barang Milik Daerah ini dibuat untuk dipergunakan
							sebagaimana mestinya.
						</td>
					</tr>
				</table>
				<br>
				<br>
				
				<!--footer-->
				<table border='0' class='rangkacetak' style='width:100%;'>
				<tr valign='top'>
					<td width=40%>Kasubag Keuangan/Pengurus Barang</td>
					<td width=20%></td>
					<td width=40% align=center>Kepala Bidang Pengelolaan Aset selaku Pembantu Pengelola Barang</td>
					<!--<td width=15%></td>--><!---->
				</tr>

				<tr>
					<td><br><br><br><br></td>
					<td><br><br><br><br></td>
					<td><br><br><br><br></td>
					<!--<td><br><br><br><br></td>-->
				</tr>
				<tr>
					<td><b>$nama_ttd<b></td>
					<td></td>
					<td align=center><b>$nama_pengelola</b></td>
					<!--<td></td>-->
				</tr>
				<tr>
					<td>NIP. $nip_ttd</td>
					<td></td>
					<td align=center>NIP. $nip_pengelola</td>
					<!--<td></td>-->
				</tr>
			</table>
			</body>
		</html>
		</div></div>";
		
		//pengalihan =================================================================================================
		/*$get = mysql_fetch_array(mysql_query(
			"select  IFNULL(kint,'00') as kint, IFNULL(ka,'00') as ka, IFNULL(f,'00') as f,
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,jml_barang_d,0)) as debet_saldoawal_brg, 
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,jml_barang_k,0)) as kredit_saldoawal_brg,
			    sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,debet,0)) as debet_saldoawal, 
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10,kredit,0)) as kredit_saldoawal,
			    sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10,debet,0)) as debet, 
				sum(if(year(tgl_buku)='$thn_anggaran' && jns_trans<>10,kredit,0)) as kredit,
				
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10,debet,0)) as debet1, 
				sum(if( tgl_buku>='$thn_anggaran-01-01' && tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10,kredit,0)) as kredit1,
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10,debet,0)) as debet2, 
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10,kredit,0)) as kredit2
				
				#sum(if( tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10,debet,0)) as debet1, 
				#sum(if( tgl_buku<'$thn_anggaran-07-01' && jns_trans<>10,kredit,0)) as kredit1,
				#sum(if( tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10,debet,0)) as debet2, 
				#sum(if( tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10,kredit,0)) as kredit2
			    
			
			from t_jurnal_aset
			where kint='01' and ka='02' $KondisiSKPD "
		));*/
		
		$sebelum = number_format( $saldoawal, 2,',','.'); //sebelum koreksi
		$sesudah = number_format( $saldoawal + ($debet_koreksi- $kredit_koreksi), 2,',','.');//number_format( $saldoawal, 2,',','.'); //setelah koreksi
		$mutasi = number_format( $debet1-$kredit1, 2,',','.'); //mutasi s/d 30 juni		
		//$dialihkan1 = number_format( $get['debet1'], 2,',','.'); // dialihkan ke aset lainnya 1
		$dialihkan1 = number_format( ($kredit_lain1 + $kredit_ekstra1), 2,',','.'); // dialihkan ke aset lainnya 1
		$tot_aset1 = number_format( 
			$saldoawal + ($debet_koreksi- $kredit_koreksi)+ 
			($debet1-$kredit1) - ($kredit_lain1+$kredit_ekstra1) ,2,',','.'); ; //per s/d 30 juni
		$mutasi2 = number_format( $debet2-$kredit2, 2,',','.'); //mutasi s/d 31 okt
		$tot_aset2 = number_format( 
			$saldoawal + ($debet_koreksi- $kredit_koreksi) + 
			($debet1- $kredit1) - ($kredit_lain1+$kredit_ekstra1) + 
			($debet2-$kredit2)  ,  2,',','.'); //per 30 Oktober
		//$dialihkan2 =  number_format( $get['debet2'], 2,',','.');// dialihkan ke aset lainnya
		$dialihkan2 =  number_format( ($kredit_lain2 + $kredit_ekstra2), 2,',','.');// dialihkan ke aset lainnya
		
		$tot_aset3 = number_format( 
			$saldoawal + ($debet_koreksi- $kredit_koreksi) + 
			($debet1- $kredit1) - ($kredit_lain1+$kredit_ekstra1) + 
			($debet2-$kredit2) - ($kredit_lain2+$kredit_ekstra2),  2,',','.');
		
		$rusak = number_format( $kredit_lain1+$kredit_lain2 ,2,',','.');
		$hilang = 0;
		$pindah  = number_format( $kredit_ekstra1+$kredit_ekstra2 ,2,',','.');
		
		$tdk_berwujud=number_format(0,2,',','.');
		$hibah = number_format(0,2,',','.');
		$dimutasi = number_format(0,2,',','.');
		$tot_aset_lainnya = number_format($kredit_lain1+$kredit_lain2 +  $kredit_ekstra1+$kredit_ekstra2, 2,',','.');
		
		echo"<div style='width:$width;font-family:Arial;margin-top:50px;page-break-after:always;'>
			<html>
				<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
					td {
					font-size: 11pt;
					}
					</style>
				</head>
			<body>
			<form name='adminForm' id='adminForm' method='post' action=''>
				<table style='width:100%' border='0'>
					<tr>
						 <td>
						 	<div align='center' style='font-size:10pt;font-family:Arial;'>
								BERITA ACARA<br>
						 		PENGALIHAN ASET TETAP PADA ASET LAINNYA<BR>
								PADA SKPD
							</div>
						</td>
					</tr>
				</table>
				<br>
				<table border='0' class='rangkacetak' style='width:100%;'>
					<tr>
						<td>
							Pada hari ini $hari tanggal $terbilang_tglba bulan $bulanba tahun $terbilang_thnba, kami yang bertanda tangan di bawah ini :
							<br>
						</td>
					</tr>
					<tr>
						<td>
							<table border='0' class='rangkacetak' style='width:100%;'>
								<tr>
									<td width=2%>1. </td>
									<td width=20%>Nama</td>
									<td width=2% align=center>:</td>
									<td width=70%>$nama_kasubag</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>Jabatan</td>
									<td width=2% align=center>:</td>
									<td width=70%>$jabatan_kasubag</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>SKPD</td>
									<td width=2% align=center>:</td>
									<td width=70%>$skpd</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>Alamat</td>
									<td width=2% align=center>:</td>
									<td width=70%>$alamat_kasubag</td>
								</tr>
								
								<tr>
									<td colspan=4>
										<br>
									</td>
								</tr>
								<tr>
									<td width=2%>2. </td>
									<td width=20%>Nama</td>
									<td width=2% align=center>:</td>
									<td width=70%>$nama_pengurus</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>Jabatan</td>
									<td width=2% align=center>:</td>
									<td width=70%>$jabatan_pengurus</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>SKPD</td>
									<td width=2% align=center>:</td>
									<td width=70%>$skpd</td>
								</tr>
								<tr>
									<td width=2%></td>
									<td width=20%>Alamat</td>
									<td width=2% align=center>:</td>
									<td width=70%>$alamat_pengurus</td>
								</tr>
								<tr>
									<td></td>
									<td colspan=3><br>Selaku pejabat pengurus barang sesuai Keputusan Sekretaris Daerah Kabupaten Garut Nomor 
									$no_sk/$thn_anggaran tanggal ".JuyTgl1($tgl_sk)." telah melakukan pendataan barang berupa 
									Aset Tetap yang berada dalam penguasaan kami, dengan rekapitulasi sebagai berikut :
									</td>
								</tr>
								<tr>
									<td></td>
									<td colspan=3>
										<table border='0' class='rangkacetak' style='width:100%;'>
											<tr>
												<td width=3%>a </td>
												<td>Jumlah Aset Tetap 01 Januari $thn_anggaran Sebelum Koreksi</td>
												<td width=3%>Rp</td>
												<td width=20% align=right>$sebelum</td>
											</tr>
											<tr>
												<td>b </td>
												<td>Jumlah Aset Tetap 01 Januari $thn_anggaran Setelah Koreksi</td>
												<td>Rp</td>
												<td align=right>$sesudah</td>
											</tr>
											<tr>
												<td>c </td>
												<td>Mutasi (Penambahan/Pengurangan) BM/Hibah s/d 30 Juni $thn_anggaran</td>
												<td>Rp</td>
												<td align=right>$mutasi</td>
											</tr>
											<tr>
												<td>d </td>
												<td>Aset Tetap yang dialihkan ke Aset Lainnya per 30 Juni $thn_anggaran</td>
												<td>Rp</td>
												<td align=right>$dialihkan1</td>
											</tr>
											<tr>
												<td></td>
												<td align=center><b>Total Aset per 30 Juni $thn_anggaran</b></td>
												<td><b>Rp</b></td>
												<td align=right><b>$tot_aset1</b></td>
											</tr>
											<tr>
												<td>e </td>
												<td>Mutasi (Penambahan/Pengurangan) BM/Hibah 1 Juli s/d 31 Oktober $thn_anggaran</td>
												<td>Rp</td>
												<td align=right>$mutasi2</td>
											</tr>
											<tr>
												<td></td>
												<td align=center><b>Total Aset per 31 Oktober $thn_anggaran</b></td>
												<td><b>Rp</b></td>
												<td align=right><b>$tot_aset2</b></td>
											</tr>
											<tr>
												<td>f </td>
												<td>Aset Tetap yang dialihkan ke Aset Lainnya s/d 31 Desember $thn_anggaran</td>
												<td style='border-bottom-style: solid;'>Rp</td>
												<td style='border-bottom-style: solid;background-color:#FF4D4D;' align=right>$dialihkan2</td>
											</tr>
											<tr>
												<td></td>
												<td align=center><b>Total Aset Tetap per 31 Oktober $thn_anggaran</b></td>
												<td><b>Rp</b></td>
												<td align=right><b>$tot_aset3</b></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan=4>Selanjutnya kami menjelaskan bahwa pemindahan aset tetap ke aset lainnya disebabkan oleh hal-hal
									sebagai berikut :
									</td>
								</tr>
								<tr>
									
									<td colspan=4>
										<table border='0' class='rangkacetak' style='width:100%;'>
											<tr>
												<td width=3%>1 </td>
												<td>Aset yang sudah tidak produktif/rusak berat</td>
												<td width=3%>Rp</td>
												<td width=20% align=right>$rusak</td>
											</tr>
											<tr>
												<td>2 </td>
												<td>Aset yang hilang/tidak diketahui keberadaannya</td>
												<td>Rp</td>
												<td align=right>$hilang</td>
											</tr>
											<tr>
												<td>3 </td>
												<td>Aset tetap yang dipindahkan ke barang pakai habis (alat dapur, gorden dll)</td>
												<td>Rp</td>
												<td align=right>$pindah</td>
											</tr>
											<tr>
												<td>4 </td>
												<td>Aset tidak berwujud (Aplikasi, Software, dll)</td>
												<td>Rp</td>
												<td align=right>$tdk_berwujud</td>
											</tr>
											<tr valign=top>
												<td>5 </td>
												<td>Aset yang telah dihibahkan ke masyarakat belum diproses hibah dan penghapusan 
													dari daftar inventaris SKPD senilai
												</td>
												<td>Rp</td>
												<td align=right>$hibah</td> 
											</tr>
											<tr>
												<td>6 </td>
												<td>Aset telah dimutasi belum diproses penghapusan dari Buku Inventaris SKPD senilai</td>
												<td>Rp</td>
												<td align=right>$dimutasi</td>
											</tr>
											<tr>
												<td></td>
												<td align=center><b>Jumlah Aset Lainnya</b></td>
												<td style='border-top-style: solid;'><b>Rp</b></td>
												<td align=right style='border-top-style: solid;'><b>$tot_aset_lainnya</b></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							Adapun daftar Aset Tetap dan aset yang tidak produktif (Aset Lainnya) tersebut di atas, sebagaimana
							terlampir dalam berita acara ini.<br>
							Demikian Berita Acara ini kami buat dengan sebenar-benarnya, untuk dijadikan bahan sebagaimana mestinya.
						</td>
					</tr>					
				</table>
				<br><br>
				<table border='0' class='rangkacetak' style='width:100%;'>
					<tr valign='top'>
						<td width=40% align=center>Pengurus Barang,</td>
						<td width=20%></td>
						<td width=40% align=center>Kasubag Keuangan SKPD</td>
					</tr>
	
					<tr>
						<td><br><br><br><br><br></td>
						<td><br><br><br><br><br></td>
						<td><br><br><br><br><br></td>
					</tr>
					<tr>
						<td align=center><b>$nama_pengurus<b></td>
						<td></td>
						<td align=center><b>$nama_kasubag</b></td>
					</tr>
					<tr>
						<td align=center>NIP. $nip_pengurus</td>
						<td></td>
						<td align=center>NIP. $nip_kasubag</td>
					</tr>
				</table>
			</body>
		</html>
		</div></div>";
		echo"<div style='width:$width;font-family:Arial;margin-top:50px;page-break-after:always;'>
			<html>
				<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
					td {
					font-size: 10pt;
					}
					</style>
				</head>
			<body>
			<form name='adminForm' id='adminForm' method='post' action=''>
				<table style='width:100%' border='0'>
					<tr>
						 <td>
						 	<div align='center' style='font-size:10pt;font-family:Arial;'>
								BERITA ACARA<br>
						 		REKONSILIASI DATA ASET TETAP HASIL AUDIT BPKRI PER 1 JANUARI $thn_anggaran
							</div>
						</td>
					</tr>
				</table>
				<br>
				<table border='0' class='rangkacetak' style='width:100%;'>
					<tr>
						<td colspan=4>
							Pada hari ini Rabu tanggal Tiga puluh satu bulan Desember tahun Dua ribu empat belas, kami yang bertanda tangan di bawah ini :
							<br><br>
						</td>
					</tr>
					<tr>
						<td width=2%>1. </td>
						<td width=20%>Nama Pengguna Barang</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nama_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Pangkat/ Golongan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$pangkat_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>NIP</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nip_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Jabatan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$jabatan_pengguna</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>SKPD/ Unit Kerja</td>
						<td width=2% align=center>:</td>
						<td width=70%>$skpd</td>
					</tr>
					<tr>
						<td colspan=4>
							<br>
						</td>
					</tr>
					<tr>
						<td width=2%>2. </td>
						<td width=20%>Nama</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nama_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Pangkat/ Golongan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$pangkat_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>NIP</td>
						<td width=2% align=center>:</td>
						<td width=70%>$nip_pengelola</td>
					</tr>
					<tr>
						<td width=2%></td>
						<td width=20%>Jabatan</td>
						<td width=2% align=center>:</td>
						<td width=70%>$jabatan_pengelola</td>
					</tr>
					<tr>
						<td colspan=4>
						<br>
							Telah mengoreksi hasil audit BPKRI Tahun $thn_anggaran sebagaimana tertera dalam lampiran 2. Demikian Berita Acara Rekonsiliasi Data Aset
							Tetap Hasil Penilaian/Inventarisasi, Belanja Barang Milik Daerah ini dibuat untuk dipergunakan sebagaimana mestinya.
							<br><br>
						</td>
					</tr>
				</table>
				<br>
				<table border='0' class='rangkacetak' style='width:100%;'>
				<tr valign='top'>
					<td width=40%>Kasubag Keuangan/Pengurus Barang</td>
					<td width=20%></td>
					<td width=40% align=center>Kepala Bidang Pengelolaan Aset selaku Pembantu Pengelola Barang</td>
					<!--<td width=15%></td>--><!---->
				</tr>

				<tr>
					<td><br><br><br><br></td>
					<td><br><br><br><br></td>
					<td><br><br><br><br></td>
					<!--<td><br><br><br><br></td>-->
				</tr>
				<tr>
					<td><b>$nama_ttd<b></td>
					<td></td>
					<td align=center><b>$nama_pengelola</b></td>
					<!--<td></td>-->
				</tr>
				<tr>
					<td>NIP. $nip_ttd</td>
					<td></td>
					<td align=center>NIP. $nip_pengelola</td>
					<!--<td></td>-->
				</tr>
			</table>
			</body>
		</html>
		</div></div>";
		//echo "$isiCetakSPD";
		//echo "<div style='width:$width;height:$height;margin:0px 0px px 0px;'>$isiCetakSPD2</div>";
	}
	
	
	function setFormBaru(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];	
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];

		$dt=array();
		$dt['c'] = $_REQUEST['RekonSkpdfmSKPD'];
		$dt['d'] = $_REQUEST['RekonSkpdfmUNIT'];
		$dt['e'] = $_REQUEST['RekonSkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST['RekonSkpdfmSEKSI'];
		$dt['tgl'] = date("Y-m-d");
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'].$cek, 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh =$cbid[0];
		/*$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];*/
		$aqry = "select * from t_kip where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$aqry2 = "select f,g,h,i,j from buku_induk where id ='".$dt['idbi']."'";
		$row = mysql_fetch_array(mysql_query($aqry2));
		$dt['kd_barang'] = $row['f'].'.'.$row['g'].'.'.$row['h'].'.'.$row['i'].'.'.$row['j'];
		$kd = str_replace('.','',$dt['kd_barang']);
		
		$aqry3 = "select nm_barang from ref_barang where concat(f,g,h,i,j) = '$kd'";
		$rows = mysql_fetch_array(mysql_query($aqry3));
		$dt['nm_barang'] = $rows['nm_barang'];
		
		$aqry4 = "select * from ref_pegawai where Id='".$dt['ref_idpegawai']."'";
		$peg = mysql_fetch_array(mysql_query($aqry4));
		$dt['nip']=$peg['nip'];
		$dt['nm_pegawai']=$peg['nama'];
		
		$this->form_fmST = 1;
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	/*function genForm2($withForm=TRUE){	
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
		
		
		
		return $form;
	}*/	

	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 600;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Penanggung Jawab - Baru';
			$nm_barang = "<input type=text name='kd_barang' id='kd_barang' value='".$dt['kd_barang']."' size='11' readonly>
						  <input type=text name='nm_barang' id='nm_barang' value='".$dt['nm_barang']."' size='30' readonly>
						  <input type='button' value='Pilih' onclick ='".$this->Prefix.".TambahBarang()' >";
			$thn_perolehan = "<input type=text name='thn_perolehan' id='thn_perolehan' value='".$dt['thn_perolehan']."' style='width:50' readonly>";
			$noreg = "<input type=text name='noreg' id='noreg' value='".$dt['noreg']."' style='width:50' readonly>";
		}else{
			$this->form_caption = 'Penanggung Jawab - Edit';			
			$nm_barang = $dt['kd_barang'].'&nbsp;&nbsp;&nbsp;'.$dt['nm_barang'];
			$thn_perolehan = $dt['thn_perolehan'];			
			$noreg = $dt['noreg'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		
		$this->form_fields = array(	
			
			'tgl' => array('label'=>'Tanggal',
							   'value'=> createEntryTgl3($dt['tgl'], 'tgl', false,''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),
											 
			'nm_barang' => array('label'=>'Nama Barang', 
								'labelWidth'=>100, 
								 'value'=> $nm_barang,  
								 'type'=>'' , 
								 'row_params'=>""),
			'thn_perolehan' => array(  'label'=>'Tahun Perolehan',
							   'value'=> $thn_perolehan,  
							   'type'=>'' ,
							   'param'=> "",
							 ),
							 
			'noreg' => array(  'label'=>'Nomor Register',
							   'value'=> $noreg,  
							   'type'=>'' ,
							   'param'=> "",
							 ),
			'penanggungjwb' => array('label'=>'Penanggung Jawab', 
								'labelWidth'=>100, 
								 'value'=> "<input type=text name='nip' id='nip' value='".$dt['nip']."' size='11' readonly>
								 			<input type=text name='nm_pegawai' id='nm_pegawai' value='".$dt['nm_pegawai']."' size='30' readonly>
											<input type='button' value='Pilih' onclick ='".$this->Prefix.".pilihPenanggungJAwab()' >",  
								 'type'=>'' , 
								 'params'=>""),

		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden name='idbi' id='idbi' value='".$dt['idbi']."'>".
			"<input type=hidden name='idbi_awal' id='idbi_awal' value='".$dt['idbi_awal']."'>".
			"<input type=hidden id='a1' name='a1' value='".$dt['a1']."'> ".
			"<input type=hidden id='a' name='a' value='".$dt['a']."'> ".
			"<input type=hidden id='b' name='b' value='".$dt['b']."'> ".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type=hidden name='ref_idpegawai' id='ref_idpegawai' value='".$dt['ref_idpegawai']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
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
	
	
	function simpanPilihBarang(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$ids = $_REQUEST['cidBI'];//735477
		
		//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
		$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
		$kd_barang = str_replace('.','',$kdbrg);
		$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
		$content = array('id_bukuinduk'=>$bi['id'],
						 'kd_barang'=>$kdbrg,
						 'nm_barang'=>$br['nm_barang'],
						 'thn_perolehan'=>$bi['thn_perolehan'],
						 'noreg'=>$bi['noreg'],
						 'jml_harga'=>$bi['jml_harga']);	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
		
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $_REQUEST['a1'];
	 $a = $_REQUEST['a'];
	 $b	= $_REQUEST['b'];
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
	 $kd_barang = $_REQUEST['kd_barang'];
	 $kdbrg = explode(".", $kd_barang);
	 $f = $kdbrg[0];
	 $g = $kdbrg[1];
	 $h = $kdbrg[2];
	 $i = $kdbrg[3];
	 $j = $kdbrg[4];
	 $idbi = $_REQUEST['idbi'];
	 $idbi_awal = $_REQUEST['idbi_awal'];
	 $nip = $_REQUEST['nip'];
	 $nm_pegawai = $_REQUEST['nm_pegawai'];
	 $nm_barang = $_REQUEST['nm_barang'];
	 $ref_idpegawai = $_REQUEST['ref_idpegawai'];
	 $tgl = $_REQUEST['tgl'];
	 $thn_perolehan = $_REQUEST['thn_perolehan'];
	 $noreg = $_REQUEST['noreg'];
	 
	 //cek validasi
	 if( $err=='' && $tgl =='' ) $err= 'Tanggal belum diisi !!';
	 if( $err=='' && $kd_barang =='' ) $err= 'Kode Barang belum diisi !!';
	 if( $err=='' && $nm_barang =='' ) $err= 'Nama Barang belum diisi !!';
	 if( $err=='' && $thn_perolehan =='' ) $err= 'Tahun Perolehan belum diisi !!';
	 if( $err=='' && $noreg =='' ) $err= 'Nomor Register belum diisi !!';	 
	 if( $err=='' && $nip =='' ) $err= 'NIP Penanggung Jawab belum diisi !!';	 
	 if( $err=='' && $nm_pegawai =='' ) $err= 'Nama Penanggung Jawab belum diisi !!';	
	 	 	 	 
	 
			if($fmST == 0){ //input penggunaan
				if($err==''){ 
					$aqry = "INSERT INTO t_kip (idbi,idbi_awal,ref_idpegawai,tgl,a1,a,b,c,d,e,e1,f,g,h,i,j,
										thn_perolehan,noreg,uid,tgl_update)
							VALUES ('$idbi','$idbi_awal','$ref_idpegawai','$tgl','$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j',
									'$thn_perolehan','$noreg','$uid',now())";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
					
				}	
			}elseif($fmST == 1){
			
			$old = mysql_fetch_array(mysql_query("select * from t_kip where Id='".$idplh."' "));
			$old_idbi=$old['idbi'];
									
				if($err==''){
					$aqry2 = "UPDATE t_kip
			        		 set "."
							 idbi='$old_idbi',
							 ref_idpegawai = '$ref_idpegawai',
							 tgl='$tgl',
							 uid = '$uid',
							 tgl_update = now()".
					 		 "WHERE Id='".$idplh."'";	$cek .= $aqry2;	
					$qry2 = mysql_query($aqry2);
					
				}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }
	
	function batal(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb'];
	 $idplh = $cbid[0];
	 
	 $kueri = "select * from t_kip where id='$idplh'"; $cek .= $kueri;
	 $idcek = mysql_fetch_array(mysql_query($kueri));
	 
	 $query = "select * from t_kip where idbi='".$idcek['idbi']."' order by tgl desc, id desc limit 0,1"; $cek .= $query;
	 $ck=mysql_fetch_array(mysql_query($query));
	 if($ck['Id']!=$idplh) $err="Hanya data terakhir yang bisa dihapus!";

		if($err==''){ 
			$aqry = "DELETE FROM t_kip WHERE Id='".$idplh."'";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
			//if($qry==FALSE) $err="Gagal Batal Penggunaan";
		}
			
					
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = 'PenggunaanForm';
		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$ipk = 2;$_REQUEST['ipk'];		
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		if($err==''){
			$FormContent = $this->genDaftarInitial2();
			$params->tipe=1;
			
			
			$form = //centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1000,
						500,
						'Pilih Usulan Penggunaan',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Close' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='ipk' name='ipk' value='$ipk' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height,
						'',$params
					).
					"</form>";
			//);
			$content = $form;//$content = 'content';	
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setSurat(){
		$dt=array();
		$dt['c'] = $_REQUEST['RekonSkpdfmSKPD'];
		$dt['d'] = $_REQUEST['RekonSkpdfmUNIT'];
		$dt['e'] = $_REQUEST['RekonSkpdfmSUBUNIT'];
		
				
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setFormSurat($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormSurat($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content='';		
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 600;
		$this->form_height = 550;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Form Surat';
			$dt['tgl_ba'] =date('Y-m-d');
			$dt['tgl_sk'] =date('Y-m-d');
			$dt['thn_anggaran'] = date("Y");
			$dt['nm_pb'] = 'H. AYI ROSYAD, S.IP.M.SI.';
			$dt['pangkat_pb'] = 'Penata Tk.I';
			$dt['nip_pb'] = '19720707 199901 1 001';
			$dt['jabatan_pb'] = 'Kepala Bidang Pengelolaan Aset pada DPPKA Kab. Garut selaku Pembantu Pengelola Barang Daerah';
			
		}else{
			$this->form_caption = 'Form Surat';			
		}
		
		//items ----------------------		
		$this->form_fields = array(			
			'thn_anggaran' => array(	
								'label'=>'Tahun Anggaran',
							   	'value'=>$dt['thn_anggaran'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'tgl_ba' => array(	
								'label'=>'Tanggal BA',
							   	'value'=>createEntryTgl3($dt['tgl_ba'],'tgl_ba','',''),  
							    'type'=>'' ,
							    'param'=>"",
							 ),
			'tgl_sk' => array(	
								'label'=>'Tanggal SK',
							   	'value'=>createEntryTgl3($dt['tgl_sk'],'tgl_sk','',''),  
							    'type'=>'' ,
							    'param'=>"",
							 ),
			'no_sk' => array(	
								'label'=>'No. SK',
							   	'value'=>$dt['no_sk'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'title' => array( 	
								'label'=>'',
								'value'=>'<div style="font-size: 12px;font-weight: bold;color: #C64934;margin:5 0 5 0">Pengelola Barang :</div>', 
								'type'=>'merge' 
							),
			
			'nm_pb' => array(  
								'label'=>'Nama',
							    'value'=>$dt['nm_pb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
							 
			'pangkat_pb' => array(  
								'label'=>'Pangkat / Golongan',
							    'value'=>$dt['pangkat_pb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'nip_pb' => array(  
								'label'=>'NIP',
							    'value'=>$dt['nip_pb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'jabatan_pb' => array( 'label'=>'Jabatan', 
								'value'=>"<textarea name=\"jabatan_pb\" cols=\"50\" style='margin: 0px;width: 407px; height: 30px;' >".
								$dt['jabatan_pb']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			
			'title2' => array( 	
								'label'=>'',
								'value'=>'<div style="font-size: 12px;font-weight: bold;color: #C64934;margin:5 0 5 0">Pengguna :</div>', 
								'type'=>'merge' 
							),
			
			'nm_pga' => array(  
								'label'=>'Nama',
							    'value'=>$dt['nm_pga'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
							 
			'pangkat_pga' => array(  
								'label'=>'Pangkat / Golongan',
							    'value'=>$dt['pangkat_pga'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'nip_pga' => array(  
								'label'=>'NIP',
							    'value'=>$dt['nip_pga'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'jabatan_pga' => array( 'label'=>'Jabatan', 
								'value'=>"<textarea name=\"jabatan_pga\" cols=\"50\" style='margin: 0px;width: 407px; height: 30px;' >".$dt['jabatan_pga']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			
			'title3' => array( 	
								'label'=>'',
								'value'=>'<div style="font-size: 12px;font-weight: bold;color: #C64934;margin:5 0 5 0">Pengurus :</div>', 
								'type'=>'merge' 
							),
			
			'nm_pgs' => array(  
								'label'=>'Nama',
							    'value'=>$dt['nm_pgs'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
							 
			'pangkat_pgs' => array(  
								'label'=>'Pangkat / Golongan',
							    'value'=>$dt['pangkat_pgs'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'nip_pgs' => array(  
								'label'=>'NIP',
							    'value'=>$dt['nip_pgs'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'jabatan_pgs' => array( 'label'=>'Jabatan', 
								'value'=>"<textarea name=\"jabatan_pgs\" cols=\"50\" style='margin: 0px;width: 407px; height: 30px;' >".$dt['jabatan_pgs']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			'alamat_pgs' => array( 'label'=>'Alamat', 
								'value'=>"<textarea name=\"alamat_pgs\" cols=\"50\" style='margin: 0px;width: 207px; height: 30px;' >".$dt['alamat_pgs']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			'title4' => array( 	
								'label'=>'',
								'value'=>'<div style="font-size: 12px;font-weight: bold;color: #C64934;margin:5 0 5 0">Kasubag :</div>', 
								'type'=>'merge' 
							),
			
			'nm_ksb' => array(  
								'label'=>'Nama',
							    'value'=>$dt['nm_ksb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
							 
			'pangkat_ksb' => array(  
								'label'=>'Pangkat / Golongan',
							    'value'=>$dt['pangkat_ksb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'nip_ksb' => array(  
								'label'=>'NIP',
							    'value'=>$dt['nip_ksb'],  
							    'type'=>'text' ,
							    'param'=>"size='30'",
							 ),
			'jabatan_ksb' => array( 'label'=>'Jabatan', 
								'value'=>"<textarea name=\"jabatan_ksb\" cols=\"50\" style='margin: 0px;width: 407px; height: 30px;' >".$dt['jabatan_ksb']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			'alamat_ksb' => array( 'label'=>'Alamat', 
								'value'=>"<textarea name=\"alamat_ksb\" cols=\"50\" style='margin: 0px;width: 207px; height: 30px;' >".$dt['alamat_ksb']."</textarea>", 
								'type'=>'',
								'row_params'=>"valign='top'"
			),
			

		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".			
			"<input type='button' value='Cetak' onclick ='".$this->Prefix.".cetakba()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	
	}	
	
	/*function genDaftarInitial2(){
		$vOpsi = $this->genDaftarOpsi();
		return			
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='ipk' name='ipk' value='".$ipk."'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:100%;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}*/		
	
}
$BaRekon = new BaRekonObj();

?>