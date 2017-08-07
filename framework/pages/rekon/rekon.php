<?php

class RekonObj extends DaftarObj2{
	var $Prefix = 'Rekon'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 't_rekon_koreksi'; //daftar
	var $TblName_Hapus = 't_rekon_koreksi';
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
	var $fileNameExcel='Rekon.xls';
	var $Cetak_Judul = 'Koreksi Hasil Rekonsiliasi';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '14cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Koreksi Hasil Rekonsiliasi';
	var $PageIcon = 'images/penilaian_ico.gif';
	var $pagePerHal= '25';
	var $FormName = 'RekonForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	var $arrJnsKoreksi = array(
					array('0','Kenaikan(penurunan)koreksi Audit'),
					array('1','aset tidak produktiv'),
					array('2','aset tahun lalu yang kurang(lebih)dicatat'),
					array('3','Direklas ke Aset Ekstra Kompatibel / Nilai dibawah Rp. 250 ribu')
				);
	
	/*
	Kenaikan(penurunan)koreksi Audit	-	-	-	-	-	-	-
aset tidak produktiv	-	-	-	-	-	-	-
aset tahun lalu yang kurang(lebih)dicatat	-	-	-	-	-	-	-
Direklas ke Aset Ekstra Kompatibel / Nilai dibawah Rp. 250 ribu
	*/
	
	function setTitle(){
		global $Main;
		return 'Koreksi Hasil Rekonsiliasi';	

	}
	function setCetakTitle(){
		return " <DIV ALIGN=CENTER>Koreksi Hasil Rekonsiliasi";
	}
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Surat()","new_f2.png","Surat",'Surat')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Lampiran1()","new_f2.png","Lampiran 1",'Lampiran 1')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Lampiran2()","new_f2.png","Lampiran 2",'Lampiran 2')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Perhitungan()","new_f2.png","Perhitungan",'Perhitungan')."</td>";
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
	
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		
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
		
		$tahun = date("Y");
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' id='tahun' name='tahun' value='$tahun'>".
				//$vOpsi['TampilOpt'].
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
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
		$tahun = $_REQUEST['tahun'];
		
				
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
								/*"NIP &nbsp;<input type=\"text\" name=\"fmNip\" id=\"fmNip\" size=\"20\" value=\"$fmNip\">&nbsp;&nbsp;
								Nama &nbsp;<input type=\"text\" name=\"fmNama\" id=\"fmNama\" size=\"30\" value=\"$fmNama\">&nbsp;&nbsp;
								Kode Barang &nbsp;<input type=\"text\" name=\"fmKdBarang\" id=\"fmKdBarang\" size=\"20\" value=\"$fmKdBarang\">&nbsp;&nbsp;
								Tahun Perolehan&nbsp;<input type=\"text\" name=\"fmThnPerolehan\" id=\"fmThnPerolehan\" size=\"4\" value=\"$fmThnPerolehan\">&nbsp;&nbsp;
								No Reg &nbsp;<input type=\"text\" name=\"fmNoreg\" id=\"fmNoreg\" size=\"4\" value=\"$fmNoreg\">&nbsp;&nbsp;
								ID Barang &nbsp;<input type=\"text\" name=\"fmIDBrg\" id=\"fmIDBrg\" size=\"4\" value=\"$fmIDBrg\">&nbsp;&nbsp;
								ID Awal &nbsp;<input type=\"text\" name=\"fmIDAwal\" id=\"fmIDAwal\" size=\"4\" value=\"$fmIDAwal\">&nbsp;&nbsp;".*/
								" Tahun Anggaran &nbsp; <input type=''text' id='tahun' name='tahun' value='$tahun' size='4'> ".
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
		$tahun = $_REQUEST['tahun'];
		
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
		if(!empty($fmIDAwal)) $arrKondisi[] = "idbi_awal='$fmIDAwal'";	
		if(!empty($tahun)) $arrKondisi[] = "tahun='$tahun'";	
		
		
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
				<script src='js/rekon/barekon.js' type='text/javascript'></script>
				<script type='text/javascript' src='js/rekon/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan=2>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01' width='20' rowspan=2>Tahun Anggaran</th>
				<th class='th01' rowspan=2>Kode Barang</th>
				<th class='th01' rowspan=2>Nama Barang</th>
				<th class='th01' width='20' rowspan=2>Tahun Perolehan</th>
				<th class='th01' rowspan=2>No. Reg</th>
				<th class='th01' rowspan=2>Uraian</th>
				<!--<th class='th01' rowspan=2>Jenis Aset</th>-->
				<th class='th01' rowspan=2>Jenis Koreksi</th>
				<th class='th01' rowspan=2>Harga Perolehan</th>
				<!--<th class='th01' rowspan=2>Harga Wajar</th>-->
				<th class='th02' colspan=2>Koreksi Hasil Rekon</th>
				</tr>
				
				<tr>
				<th class='th01'>Penambahan</th>
				<th class='th01'>Pengurangan</th>
				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		

		//kdBarang & $nmBarang
		$kdBarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
		$brg=mysql_fetch_array(mysql_query("SELECT `nm_barang` FROM `ref_barang` WHERE concat(f,g,h,i,j) = '".$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']."'"));
		$nmBarang = $brg['nm_barang'];
		
		switch($isi['jns_aset']){
			case'0': $jns_aset='Aset Produktif'; break;
			case'1': $jns_aset='Aset Tidak Produktif'; break;
			case'2': $jns_aset='Tidak Dicatat'; break;
			case'3': $jns_aset='Ekstrakomptabel'; break;
		}
		
		//$jns_nilai=$isi['jns_nilai']=='1'?'Wajar':'Perolehan';
		$arrJnsNilai = array(
					array('0','Produktif'),
					array('1','Nilai Wajar'),
					array('2','Tidal Produktif'),
				);
		
			$Koloms[] = array('align="center" width="20"', $no.'.' );
 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="center"',$isi['tahun']);
			$Koloms[] = array('align="center"',$kdBarang);
			$Koloms[] = array('align="left"',$nmBarang);
			$Koloms[] = array('align="center"',$isi['thn_perolehan']); 
			$Koloms[] = array('align="center"',$isi['noreg']);
			$Koloms[] = array('align="left"',$isi['uraian']);
	 		//$Koloms[] = array('align="center"',$jns_aset); 
	 		$Koloms[] = array('align="center"',$arrJnsNilai[$isi['jns_nilai']][1]); 
	 		$Koloms[] = array('align="right"',number_format($isi['hrg_perolehan'], 2, ',', '.'));
	 		//$Koloms[] = array('align="right"',number_format($isi['hrg_wajar'], 2, ',', '.'));
	 		$Koloms[] = array('align="right"',number_format($isi['debet'], 2, ',', '.'));
	 		$Koloms[] = array('align="right"',number_format($isi['kredit'], 2, ',', '.'));

		return $Koloms;
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
				$fm = $this->setFormCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			
			case 'getdata':{
				$ids = $_REQUEST['cidBI'];//735477
		
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
				$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
				$kd_barang = str_replace('.','',$kdbrg);
				$jml_harga = number_format($bi['jml_harga'], 2, ',', '.');
				$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
				$content = array('idbi'=>$bi['id'],
								 'idbi_awal'=>$bi['idawal'],
								 'a1'=>$bi['a1'],
								 'a'=>$bi['a'],
								 'b'=>$bi['b'],
								 'c'=>$bi['c'],
								 'd'=>$bi['d'],
								 'e'=>$bi['e'],
								 'e1'=>$bi['e1'],
								 'kd_barang'=>$kdbrg,
								 'nm_barang'=>$br['nm_barang'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'jml_harga'=>$jml_harga);	
		break;
	    }
		
		case 'simpan':{
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
		
	
	function setFormBaru(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];	
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];

		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$dt['tgl'] = date("Y-m-d");
		$dt['tahun'] = date("Y");
		$dt['jns_aset']=0;
		$dt['jns_nilai'] = 0;
		
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
		$aqry = "select * from t_rekon_koreksi where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kd_barang'] = $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
		
		$brg=mysql_fetch_array(mysql_query("SELECT `nm_barang` FROM `ref_barang` WHERE concat(f,g,h,i,j) = '".$dt['f'].$dt['g'].$dt['h'].$dt['i'].$dt['j']."'"));
		$dt['nm_barang'] = $brg['nm_barang'];
		
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
	
	function setNavAtas(){
		global $Main;	
	$Pg='05';
	$menu_sensus = 
		$Main->MODUL_SENSUS ?
		 "|<A href=\"index.php?Pg=$Pg&SPg=belumsensus\" $styleMenu1_15 title='SENSUS'>SENSUS</a> " : '';
	$menu_mutasi =
		$Main->MODUL_MUTASI?
		"|<A href=\"index.php?Pg=$Pg&SPg=12\" $styleMenu1_12 title='Daftar Mutasi'>MUTASI</a> ": '';
	$menu_rekap_mutasi = 
		$Main->MODUL_MUTASI?
		"|<A href=\"index.php?Pg=$Pg&SPg=13\" $styleMenu1_13 title='Rekap Mutasi'>REKAP MUTASI</a> ": ''; 	
	$menu_kibg = '';
		//$Main->MODUL_ASET_LAINNYA?
		//"|<A href=\"?Pg=$Pg&SPg=kibg\" $styleMenu1_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> ":'';
	$menu_pembukuan =
		$Main->MODUL_AKUNTANSI?
		"|<A href=\"index.php?Pg=05&SPg=03&jns=intra\" $styleMenu3_1a title='AKUNTANSI'>AKUNTANSI</a>":'';
	
	$menu_rekon = $Main->MODUL_REKON ? " | <A href=\"pages.php?Pg=rekon\" title='Rekonsiliasi' style='color:blue;'>REKON</a>  ":'';
	
	
	
	return "

	<!--menubar_page-->

	<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>


	<A href=\"index.php?Pg=$Pg&SPg=03\" $styleMenu1_1 title='Buku Inventaris'>BI</a> |
	<A href=\"index.php?Pg=$Pg&SPg=04\" $styleMenu1_3 title='Tanah'>KIB A</a>  |  
	<A href=\"index.php?Pg=$Pg&SPg=05\" $styleMenu1_4 title='Peralatan & Mesin'>KIB B</a>  |  
	<A href=\"index.php?Pg=$Pg&SPg=06\" $styleMenu1_5 title='Gedung & Bangunan'>KIB C</a>  |  
	<A href=\"index.php?Pg=$Pg&SPg=07\" $styleMenu1_6 title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
	<A href=\"index.php?Pg=$Pg&SPg=08\" $styleMenu1_7 title='Aset Tetap Lainnya'>KIB E</a>  |  
	<A href=\"index.php?Pg=$Pg&SPg=09\" $styleMenu1_8 title='Konstruksi Dalam Pengerjaan'>KIB F</a>   
	$menu_kibg
	<!--<A href=\"?Pg=$Pg&SPg=03&fmKONDBRG=3\" title='Aset Lainnya'>ASET LAINNYA</a>  |  -->
	<!--<A href=\"javascript:showAsetLain()\" title='Aset Lainnya'>ASET LAINNYA</a>  |-->
	|<A href=\"index.php?Pg=$Pg&SPg=11\" $styleMenu1_11 title='Rekap BI'>REKAP BI</a>

	$menu_mutasi
	$menu_rekap_mutasi
	|<A href=\"index.php?Pg=$Pg&SPg=KIR\" $styleMenu1_14 title='Kartu Inventaris Ruangan'>KIR</a> 

	$menu_sensus
	$menu_pembukuan
	$menu_peta
	$menu_rekon
	  &nbsp&nbsp&nbsp
	</td></tr>$menu_pembukuan1</table>";
	
	}
	
	function setForm($dt){	
		global $Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';

		$form_name = $this->Prefix.'_form';				
		$this->form_width = 600;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Koreksi - Baru';
			$mutasi = 1 ;
			
		}else{
			$this->form_caption = 'Koreksi - Edit';			
			if($dt['debet']!=0){
				$mutasi = 1 ;
				$jml_harga = $dt['debet'];
			}elseif($dt['debet']==0){
				$mutasi = 2 ;
				$jml_harga = $dt['kredit'];
			}
		}
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='$kdSubUnit0'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];
		
		$arrJnsAset = array(
					array('0','Produktif'),
					array('1','Tidak Produktif'),
					array('2','Tidak Dicatat'),
					array('3','Ekstrakomptabel'),
				);
				
		$arrMutasi = array(
					array('1','Bertambah'),
					array('2','Berkurang'),
				);
				
		/*$arrJnsNilai = array(
					array('0','Perolehan'),
					array('1','Wajar'),
				);
		*/
		$arrJnsNilai = array(
					array('0','Produktif'),
					array('1','Nilai Wajar'),
					array('2','Tidal Produktif'),
				);
		
		$this->form_fields = array(	
			
			'bidang' => array( 'label'=>'BIDANG','value'=>$bidang, 'type'=>'', 'row_params'=>"height='21'"	),
			
			'unit' => array('label'=>'SKPD', 'value'=>$unit, 'type'=>'', 'row_params'=>"height='21'"),
			
			'subunit' => array( 'label'=>'UNIT', 'value'=>$subunit, 'type'=>'', 'row_params'=>"height='21'"	),
			
			'seksi' => array( 'label'=>'SUB UNIT', 	'value'=>$seksi, 'type'=>'', 'row_params'=>"height='21'"),
			
			'tahun' => array('label'=>'Tahun Anggaran', 
							'value'=> "<input type='text' id='fmTAHUN' name='fmTAHUN' value='".$dt['tahun']."' size='4' maxlength=4 onkeypress='return isNumberKey(event)'>",
							'labelWidth'=>100, 
							'row_params'=>"height='21'", 
							'type'=>'' 
			),
			
			/*'jns_aset' => array(  'label'=>'Aset',
							   'value'=> cmbArray('jns_aset',$dt['jns_aset'],$arrJnsAset,'-- Pilih --',''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),
			*/								 
			'nm_barang' => array('label'=>'Kode Barang', 
								 'labelWidth'=>100, 
								 'value'=> "<input type=text name='kd_barang' id='kd_barang' value='".$dt['kd_barang']."' size='13' readonly>
										    <input type=text name='nm_barang' id='nm_barang' value='".$dt['nm_barang']."' size='30' readonly>
										    <input type='button' value='Cari' onclick ='".$this->Prefix.".TambahBarang()' >",  
								 'type'=>'' , 
								 'row_params'=>""),
								 
			'thn_perolehan' => array(  'label'=>'Tahun Perolehan',
							   'value'=> "<input type=text name='thn_perolehan' id='thn_perolehan' value='".$dt['thn_perolehan']."' style='width:50' readonly>",  
							   'type'=>'' ,
							   'param'=> "",
							 ),
							 
			'noreg' => array(  'label'=>'Nomor Register',
							   'value'=> "<input type=text name='noreg' id='noreg' value='".$dt['noreg']."' style='width:50' readonly>",  
							   'type'=>'' ,
							   'param'=> "",
							 ),
			
			'harga' => array( 'label'=>'Harga Perolehan', 
				'value'=>"Rp. <input name=\"harga\" id=\"harga\" type=\"text\" value='".$dt['hrg_perolehan']."' style='text-align:right;'/ readonly>",
				'type'=>'' ,
			),
				
			/*'hrg_wajar' => array( 'label'=>'Nilai Wajar', 
				'value'=>"Rp. <input name=\"hrg_wajar\" id=\"hrg_wajar\" type=\"text\" value='".$dt['hrg_wajar']."' onkeypress='return isNumberKey(event)' style='text-align:right;'/ >",
				'type'=>'' ,
			
			),*/
			/*'jns_koreksi' => array(  'label'=>'Jenis Koreksi',
							   'value'=> cmbArray('jns_nilai',$dt['jns_nilai'],$arrJnsNilai,'-- Pilih --',"style='width:100px'"),  
							   'type'=>'' ,
							   'param'=> "",
							 ),*/
			'jns_nilai' => array(  'label'=>'Jenis Koreksi',
							   'value'=> cmbArray('jns_nilai',$dt['jns_nilai'],$arrJnsNilai,'-- Pilih --',"style='width:100px'"),  
							   'type'=>'' ,
							   'param'=> "",
							 ),
			
			'mutasi' => array(  'label'=>'Jenis Mutasi',
							   'value'=> cmbArray('mutasi',$mutasi,$arrMutasi,'-- Pilih --',"style='width:100px'"),  
							   'type'=>'' ,
							   'param'=> "",
							 ),
							 
			'jml_harga' => array( 'label'=>'Jumlah Koreksi', 
				'value'=>"Rp. <input name=\"jml_harga\" id=\"jml_harga\" type=\"text\" value='".$jml_harga."' onkeypress='return isNumberKey(event)' style='text-align:right;'/ >", //onkeyup=".$this->Prefix.".formatRupiah(this,'.') 
				'type'=>'' ,
			
			),
			
			'uraian' => array(  'label'=>'Uraian', 
				'value'=> "<textarea name=\"uraian\" cols=\"60\" style='margin: 2px;width: 312px; height: 50px;'>".$dt['uraian']."</textarea>", 
				//'params'=>"valign='top'",
				'type'=>'' , 'row_params'=>"valign='top'"
			),
			
			
			
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
	 $nm_barang = $_REQUEST['nm_barang'];
	 $thn_anggaran = $_REQUEST['fmTAHUN'];
	 $thn_perolehan = $_REQUEST['thn_perolehan'];
	 $noreg = $_REQUEST['noreg'];
	 $harga = str_replace(".","",$_REQUEST['harga']);
	 $hrg_wajar = $_REQUEST['hrg_wajar'];
	 $jns_aset = $_REQUEST['jns_aset'];
	 if($_REQUEST['mutasi']=='1'){
	 	$debet=$_REQUEST['jml_harga'];
		$kredit=0;
	 }elseif($_REQUEST['mutasi']=='2'){
	 	$debet=0;
		$kredit=$_REQUEST['jml_harga'];
	 }
	 $uraian = $_REQUEST['uraian'];
	 $jns_nilai = $_REQUEST['jns_nilai'];
	 
	 //cek validasi
	 if( $err=='' && $thn_anggaran =='' ) $err= 'Tahun Anggaran belum diisi !!';
	 //if( $err=='' && $jns_aset =='' ) $err= 'Aset belum dipilih !!';
	 if( $err=='' && $kd_barang =='' ) $err= 'Kode Barang belum diisi !!';
	 if( $err=='' && $harga =='' ) $err= 'Harga Perolehan belum diisi !!';
	 //if( $err=='' && $hrg_wajar =='' ) $err= 'Nilai wajar belum diisi !!';	 
	 if( $err=='' && $_REQUEST['mutasi'] =='' ) $err= 'Mutasi belum dipilih !!';	 
	 if( $err=='' && $_REQUEST['jml_harga'] =='' ) $err= 'Jumlah belum diisi !!';	
	 if( $err=='' && $uraian =='' ) $err= 'Uraian belum diisi !!';	
	 if( $err=='' && $jns_nilai =='' ) $err= 'Nilai  belum dipilih !!';	
	 	 	 	 
	 
			if($fmST == 0){ //input penggunaan
				if($err==''){ 
					$aqry = "INSERT INTO t_rekon_koreksi (tahun,uraian,idbi,a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan,hrg_perolehan,hrg_wajar,tgl_update,uid,idawal,debet,kredit,jns_nilai,jns_aset,jns_koreksi)
							VALUES ('$thn_anggaran','$uraian','$idbi','$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j','$noreg','$thn_perolehan','$harga','$hrg_wajar',now(),'$uid','$idbi_awal','$debet','$kredit','$jns_nilai','$jns_aset','')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
					
				}	
			}elseif($fmST == 1){
			
			/*$old = mysql_fetch_array(mysql_query("select * from t_kip where Id='".$idplh."' "));
			$old_idbi=$old['idbi'];*/
									
				if($err==''){
					$aqry2 = "UPDATE t_rekon_koreksi
			        		 set "."
							 tahun='$thn_anggaran',
							 uraian = '$uraian',
							 idbi='$idbi',
							 a1='$a1',
							 a='$a',
							 b='$b',
							 c='$c',
							 d='$d',
							 e='$e',
							 f='$f',
							 g='$g',
							 h='$h',
							 i='$i',
							 j='$j',
							 noreg='$noreg',
							 thn_perolehan='$thn_perolehan',".
							 //hrg_perolehan='$harga',
							 "hrg_wajar='$hrg_wajar',
							 idawal='$idbi_awal',
							 debet='$debet',
							 kredit='$kredit',
							 jns_nilai='$jns_nilai',
							 jns_aset='$jns_aset',
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
$Rekon = new RekonObj();

?>