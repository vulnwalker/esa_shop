<?php

class PenilaianObj extends DaftarObj2{
	var $Prefix = 'Penilaian'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'penilaian'; //daftar
	var $TblName_Hapus = 'penilaian';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id'); //daftar/hapus
	var $FieldSum = array('nilai_barang_asal','nilai_barang');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 9, 8,8);//berdasar mode
	var $FieldSum_Cp2 = array( 5, 5,5);	
	var $checkbox_rowspan = 2;
	var $totalCol = 17; //total kolom daftar
	var $fieldSum_lokasi = array( 9,10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='Penilaian.xls';
	var $Cetak_Judul = 'Penilaian';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '14cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penilaian';
	var $PageIcon = 'images/penilaian_ico.gif';
	var $pagePerHal= '25';
	var $FormName = 'PenilaianForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	function setTitle(){
		global $Main;
		return 'Daftar Penilaian Barang Daerah';	

	}
	function setCetakTitle(){
		return " <DIV ALIGN=CENTER>Penilaian ";
	}
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batal()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function setMenuView(){		
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakSK(\"$Op\")","print_f2.png",'Cetak SK',"Cetak SK")."</td>".
			//"<td>".genPanelIcon("javascript:Penggunaan_Det.cetakAll(\"$Op\")","print_f2.png",'Lampiran',"Cetak Lampiran")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){	
		global $Main;
		global $HTTP_COOKIE_VARS;
		$Pg = $_REQUEST['Pg'];
		
		$Penilaian = '';
		$Koreksi = '';
		$Pemindahtanganan = '';
		$Pemanfaatan = '';
		switch ($Pg){
			case 'Penilaian': $Penilaian ="style='color:blue;'"; break;
			case 'Koreksi': $Koreksi ="style='color:blue;'"; break;
			case 'Pemanfaatan': $Pemanfaatan ="style='color:blue;'"; break;
			case 'Pemindahtanganan': $Pemindahtanganan ="style='color:blue;'"; break;
		}
		
			//index.php?Pg=09
			return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=Penilaian\" title='Penilaian' $Penilaian>Penilaian </a> |			
			<A href=\"pages.php?Pg=Koreksi\" title='Koreksi' $Koreksi>Koreksi </a> |			
			<A href=\"pages.php?Pg=Pemanfaatan\" title='Pemanfaatan' $Pemanfaatan>Pemanfaatan</a> |
			<A href=\"pages.php?Pg=Pemindahtanganan\" title='Pemindahtanganan' $Pemindahtanganan>Pemindahtanganan</a> 
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
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
		$fmid_barang = $_REQUEST['fmid_barang'];
		$fmidawal = $_REQUEST['fmidawal'];
		$kd_barang = $_REQUEST['fmkd_barang'];
		$nm_barang = $_REQUEST['fmnm_barang'];
		$thn = $_REQUEST['fmthn'];
		$no_reg = $_REQUEST['fmno_reg'];		
		$no_surat = $_REQUEST['fmno_surat'];
				
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tanggal Usulan'),
			array('2','Tahun Anggaran'),
		);
		
		
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
								"ID Barang &nbsp;<input type=\"text\" name=\"fmid_barang\" id=\"fmid_barang\" size=\"20\" value=\"$fmid_barang\">&nbsp;&nbsp;
								ID Awal &nbsp;<input type=\"text\" name=\"fmidawal\" id=\"fmidawal\" size=\"20\" value=\"$fmidawal\">&nbsp;&nbsp;
								Kode Barang &nbsp;<input type=\"text\" name=\"fmkd_barang\" id=\"fmkd_barang\" size=\"20\" value=\"$kd_barang\">&nbsp;&nbsp;
								<!--Nama Barang &nbsp;<input type=\"text\" name=\"fmnm_barang\" id=\"fmnm_barang\" size=\"30\" value=\"$nm_barang\">&nbsp;&nbsp;-->
								Tahun &nbsp;<input type=\"text\" name=\"fmthn\" id=\"fmthn\" size=\"4\" value=\"$thn\">&nbsp;&nbsp;
								No Reg &nbsp;<input type=\"text\" name=\"fmno_reg\" id=\"fmno_reg\" size=\"15\" value=\"$no_reg\">&nbsp;&nbsp;
								No Surat &nbsp;<input type=\"text\" name=\"fmno_surat\" id=\"fmno_surat\" size=\"15\" value=\"$no_surat\">&nbsp;&nbsp;".
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
		$fmid_barang = $_REQUEST['fmid_barang'];
		$fmidawal = $_REQUEST['fmidawal'];
		$kd_barang = $_REQUEST['fmkd_barang'];
		$kdbrg=str_replace('.','',$kd_barang);
		$nm_barang = $_REQUEST['fmnm_barang'];
		$thn = $_REQUEST['fmthn'];
		$noreg = $_REQUEST['fmno_reg'];
		$no_surat = $_REQUEST['fmno_surat'];
		
		//Kondisi -------------------------		
		$arrKondisi= array();		
		$arrKondisi[] = getKondisiSKPD2(
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
		if(!empty($fmid_barang)) $arrKondisi[] = "id_bukuinduk='$fmid_barang'";
		if(!empty($fmidawal)) $arrKondisi[] = "idbi_awal='$fmidawal'";
		if(!empty($kd_barang)) $arrKondisi[] = "concat(f,g,h,i,j)='$kdbrg'";
		if(!empty($nm_barang)) $arrKondisi[] = "nm_barang like '%$nm_barang%'";	
		if(!empty($thn)) $arrKondisi[] = "thn_perolehan='$thn'";	
		if(!empty($noreg)) $arrKondisi[] = "noreg='$noreg'";	
		if(!empty($no_surat)) $arrKondisi[] = "surat_no='$no_surat'";	
		
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
		$OrderArr= array();	
		/*$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		
			
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
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/penilaian/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/cetakpenggunaan.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penggunaan_det.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penggunaanketetapan.js' language='JavaScript' ></script>	
				".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan='2'>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01' rowspan='2'>Kode Barang/<br>Id Barang/<br>Id Awal</th>
   	   			<th class='th01' rowspan='2'>No.Reg/<br>Thn</th>
   	   			<th class='th02' colspan='3'>Spesifikasi Barang</th>
				<th class='th01' rowspan='2'>Tanggal Penilaian/<br>Tanggal Perolehan</th>
				<th class='th01' rowspan='2'>Harga Perolehan Asal</th>
				<th class='th01' rowspan='2'>Harga Penilaian</th>
				<th class='th02' colspan='2'>Pihak Penilaian</th>
				<th class='th02' colspan='2'>Surat Perjanjian/Kontrak</th>
				<th class='th01' rowspan='2'>Keterangan</th>
				</tr>				
				<tr>				
				<th class='th01' >Nama Barang/<br>Penggunaan</th>
				<th class='th01' >Merk/Tipe/Alamat</th>
				<th class='th01' >No. Sertifikat/<br> No. Pabrik/<br> No. Chasis/<br> No. Mesin/<br> No.Polisi</th>
				<th class='th01' >Instansi</th>
				<th class='th01' >Alamat</th>
				<th class='th01' >Nomor</th>
				<th class='th01' >Tanggal</th>
								
				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;		
		
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
				$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket  from kib_b  $KondisiKIB limit 0,1";
				//echo"<br>qrkbb=".$aqry;
				
				$QryKIB_B = mysql_query($aqry);
				
				//echo "<br>qrkibb=".$aqry;
				while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
					$isiKIB_B = array_map('utf8_encode', $isiKIB_B);
					$ISI5 = "{$isiKIB_B['merk']}";
					$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
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
		
		$kdBarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
		$qry_brg=mysql_query("SELECT
								  `bb`.`nm_barang`
								FROM
								  `penilaian` `aa` LEFT JOIN
								  `ref_barang` `bb` ON `aa`.`f` = `bb`.`f` AND `aa`.`g` = `bb`.`g` AND
								    `aa`.`h` = `bb`.`h` AND `aa`.`i` = `bb`.`i` AND `aa`.`j` = `bb`.`j`    
								WHERE aa.id='".$isi['id']."';");
		$res = mysql_fetch_array($qry_brg);
		$bi = mysql_fetch_array(mysql_query("SELECT * FROM view_buku_induk2 WHERE id='".$isi['id_bukuinduk']."'"));
		$penggunaan = $bi['penggunaan'];				
				
			$Koloms[] = array('align="center" width="20"', $no.'.' );
 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="center" "',$kdBarang.'/<BR>'.$isi['id_bukuinduk'].'/<BR>'.$isi['idbi_awal']);
	 		$Koloms[] = array('align="center" "',$isi['noreg'].'/<BR>'.$isi['thn_perolehan']);
			$Koloms[] = array('align="left" "',$res['nm_barang'].'/<br>'.$penggunaan);
			$Koloms[] = array('align="left" "',$ISI5);
			$Koloms[] = array('align="left" "',$ISI6); 
			$Koloms[] = array('align="center" "',TglInd($isi['tgl_penilaian']).'/<br>'.TglInd($isi['tgl_perolehan']));
			$Koloms[] = array('align="right" "',number_format($isi['nilai_barang_asal'], 2, ',', '.'));
			$Koloms[] = array('align="right" "',number_format($isi['nilai_barang'], 2, ',', '.'));
	 		$Koloms[] = array('align="left" "',$isi['penilai_instansi']); 
	 		$Koloms[] = array('align="left" "',$isi['penilai_alamat']);
	 		$Koloms[] = array('align="center" ',$isi['surat_no']);
	 		$Koloms[] = array('align="center" "',TglInd($isi['surat_tgl'])); 
	 		$Koloms[] = array('align="left" "',$isi['ket']);					

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

			case 'formUsulan':{				
				$fm = $this->setformUsulan();				
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
			
			case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			
		   	case 'windowsave':{
	
				$ref_pilihbarang = $_REQUEST['id'];
				$ipk = $_REQUEST['ipk'];

				//update penggunaan
				$aqry2 = "UPDATE penggunaan
		        		 set "." sttemp_ketetapan = '1',
						 ref_idketetapan='$ipk'".
				 		 "WHERE Id='".$ref_pilihbarang."'";	$cek .= $aqry2;
				$qry = mysql_query($aqry2);	
				
				/*if($qry==TRUE){
					$aqry3 = "delete from penggunaan_ketetapan where sttemp=1";	$cek .= $aqry3;
					$qry2 = mysql_query($aqry3);	
				}*/
	
			break;
		   }
		   
		   case 'getdata':{
				$ids = $_REQUEST['cidBI'];//735477
				$tgl = $_REQUEST['tgl_penilaian'];
				$tgl_perolehan = $_REQUEST['tgl_perolehan'];
				$id_penilaian = $_REQUEST['id_penilaian'];
				
				
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
				$kib_c = mysql_fetch_array(mysql_query("select kota, alamat_kec, alamat_kel, alamat,alamat_b,alamat_c from kib_c where idbi = '".$ids[0]."'")) ;
				$alamat = $kib_c['alamat'];					
				$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
				$kd_barang = str_replace('.','',$kdbrg);
				$harga= 0;
				$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$bi['idawal']."' "));
				$harga += $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
				$harga += $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
				$harga += $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
				$harga += $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
				$harga -= $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan' and id!='$id_penilaian'"));
				$harga += $get['tot'];
				$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl_perolehan<='$tgl_perolehan'"));
				$harga += $get['tot'];
				$jml_harga = number_format($harga, 0, ',', '.');
				$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
				$content = array('id_bukuinduk'=>$bi['id'],
								 'c'=>$bi['c'],
								 'd'=>$bi['d'],
								 'e'=>$bi['e'],
								 'e1'=>$bi['e1'],
								 'kd_barang'=>$kdbrg,
								 'nm_barang'=>$br['nm_barang'],
								 'thn_perolehan'=>$bi['thn_perolehan'],
								 'noreg'=>$bi['noreg'],
								 'jml_harga'=>$jml_harga,
								 'staset'=>$bi['staset'],
								 'tahun'=>$bi['tahun'],
								 'alamat'=>$alamat,
								 'idbi_awal'=>$bi['idawal']);	
		break;
	    }
		   
			case 'batal':{
				$fm = $this->batal();
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
		   
	   		case 'simpanPilihBarang':{				
				$get= $this->simpanPilihBarang();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}		   			
			
			case 'cetakSK':{				
				$fm = $this->cetakSK();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				$json=FALSE;											
				break;
			}
			
			case 'GetHrg_Asal':{				
				$fm = $this->GetHrg_Asal();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				$json=TRUE;														
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function createEntryTgl3($Tgl, $elName, $disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', $fmName = 'adminForm',
	$tglShow=TRUE, $withBtClear = TRUE){
	//global $$elName, 
	//global $Ref;//= 'entryTgl';
	
	$NamaBulan  = array(
	array("01","Januari"), 
	array("02","Pebruari"),
	array("03","Maret"),
	array("04","April"),
	array("05","Mei"),
	array("06","Juni"),
	array("07","Juli"),
	array("08","Agustus"),
	array("09","September"),
	array("10","Oktober"),
	array("11","Nopember"),
	array("12","Desember")
	);
	
	$deftgl = date( 'Y-m-d' ) ;//'2010-05-05';
		
	$tgltmp= explode(' ',$Tgl);//explode(' ',$$elName); //hilangkan jam jika ada
	$stgl = $tgltmp[0]; 
	$tgl = explode('-',$stgl);
	if ($tgl[2]=='00'){ $tgl[2]='';	}
	if ($tgl[1]=='00'){ $tgl[1]='';	}
	if ($tgl[0]=='0000'){ $tgl[0]='';	}
		
	
	$dis='';
	if($disableEntry == '1'){
		$dis = 'disabled';
	}
	
	/*$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">'.$title.'
			<input '.$dis.' type="text" name="'.$elName.'_tgl" id="'.$elName.'_tgl" value="'.$tgl[2].'" size="2" maxlength="2" 
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\''.$elName.'\')"
				style="width:25">
		</div>' : '';*/
	$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">' . 
			$title .'&nbsp;'. 			
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'', 
				" $dis ".'   onchange="'.$this->Prefix.'.TglEntry_createtgl(\'' . $elName . '\')"').
		'</div>'
		: '';
	$btClear =  $withBtClear?
		'<div style="float:left;padding: 0 4 0 0">
				<input '.$dis.'  name="'.$elName.'_btClear" id="'.$elName.'_btClear" type="button" value="Clear" 
					onclick="TglEntry_cleartgl(\''.$elName.'\')">
					&nbsp;&nbsp<span style="color:red;">'.$ket.'</span>
		</div>' : '';
		
	if ($tgl[0]==''){
		$thn =(int)date('Y') ;
	}else{
		$thn = $tgl[0];//(int)date('Y') ;
	}
	$thnaw = $thn-10;
	$thnak = $thn+11;
	$opsi = "<option value=''>Tahun</option>";
	for ($i=$thnaw; $i<$thnak; $i++){
		$sel = $i == $tgl[0]? "selected='true'" :'';
		$opsi .= "<option $sel value='$i'>$i</option>";	
	}
	$entry_thn = 
		'<select id="'. $elName  .'_thn" 
			name="' . $elName . '"_thn"	'.
			$dis. 
			' onchange="'.$this->Prefix.'.TglEntry_createtgl(\'' . $elName . '\')"
		>'.
			$opsi.
		'</select>';
	
	$hsl = 
		'<div id="'.$elName.'_content" style="float:left;">'.
			$entrytgl.
			'<div style="float:left;padding: 0 4 0 0">
				'.cmb2D_v2($elName.'_bln', $tgl[1], $NamaBulan, $dis,'Pilih Bulan',
				'onchange="'.$this->Prefix.'.TglEntry_createtgl(\''.$elName.'\')"'  ) .'
			</div>
			<div style="float:left;padding: 0 4 0 0">
				<!--<input '.$dis.' type="text" name="'.$elName.'_thn" id="'.$elName.'_thn" value="'.$tgl[0].'" size="4" maxlength="4" 
					onkeypress="return isNumberKey(event)"
					onchange="'.$this->Prefix.'.TglEntry_createtgl(\''.$elName.'\')"
					style="width:35"	
				>-->'.
				$entry_thn.
			'</div>'.
			
			$btClear.		
			'<input $dis type="hidden" id='.$elName.' name='.$elName.' value="'.$Tgl.'" >
		</div>';
	return $hsl;	
	}
	
	function GetHrg_Asal(){
		$cek = ''; $err=''; $content=''; 
	 	$json = TRUE;	//$ErrMsg = 'tes';
		$tgl= $_REQUEST['tgl'];		
		$tgl_perolehan= $_REQUEST['tgl_perolehan'];		
		//$id_plh= $_REQUEST['id_plh'];		
		//$get_bi = mysql_fetch_array(mysql_query("select id_bukuinduk from penilaian where id = '$id_plh' "));
		//$idbukuinduk = $get_bi['id_bukuinduk'];	
		//$idbukuinduk = $_REQUEST['idbi_awal'];	
		//$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id = '$idbukuinduk' "));
		$idbi_awal = $_REQUEST['idbi_awal'];	
			$harga= 0;
			$get = mysql_fetch_array(mysql_query("select harga as tot from buku_induk where id = '".$idbi_awal."' "));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal = '".$idbi_awal."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$idbi_awal."' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$idbi_awal."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$idbi_awal."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga -= $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$idbi_awal."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			$get = mysql_fetch_array(mysql_query("select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$idbi_awal."' and tgl_perolehan<='$tgl_perolehan'"));
			$harga += $get['tot'];
			/*$cek.="select harga as tot from buku_induk where id = '".$bi['idawal']."' ";
			$cek.="select sum(nilai_barang) as tot from pemeliharaan where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal = '".$bi['idawal']."' and tgl_pengamanan<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(biaya_pemanfaatan) as tot from pemanfaatan where idbi_awal = '".$bi['idawal']."' and tgl_pemanfaatan<='$tgl' and tambah_aset = 1  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(harga_hapus) as tot from penghapusan_sebagian where idbi_awal = '".$bi['idawal']."' and tgl_penghapusan<='$tgl'  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(nilai_barang - nilai_barang_asal) as tot from penilaian where idbi_awal = '".$bi['idawal']."' and tgl_penilaian<='$tgl'  and tgl_perolehan<='$tgl_perolehan'";
			$cek.="select sum(harga_baru - harga) as tot from t_koreksi where idbi_awal = '".$bi['idawal']."' and tgl<='$tgl' and tgl_perolehan<='$tgl_perolehan'";
		*/$content=number_format($harga,2,',','.');
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	 	
	}
	/*function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}*/
	
	function setFormBaru(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];	
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
		$tgl_buku =	$thn_login.'-00-00';
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$dt['tgl_penilaian'] = $tgl_buku;
		$dt['tgl_perolehan'] = date("Y-m-d");
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
		$aqry = "select * from penilaian where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$aqry2 = "select f,g,h,i,j from buku_induk where id ='".$dt['id_bukuinduk']."'";
		$row = mysql_fetch_array(mysql_query($aqry2));
		$dt['kd_barang'] = $row['f'].'.'.$row['g'].'.'.$row['h'].'.'.$row['i'].'.'.$row['j'];
		$kd = str_replace('.','',$dt['kd_barang']);
		
		$aqry3 = "select nm_barang from ref_barang where concat(f,g,h,i,j) = '$kd'";
		$rows = mysql_fetch_array(mysql_query($aqry3));
		$dt['nm_barang'] = $rows['nm_barang'];
		
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
		$this->form_width = 730;
		$this->form_height = 430;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Penilaian Barang';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit Penilaian Barang';			
			$id_penilaian = $dt['id'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		
		$this->form_fields = array(	
			'nm_barang' => array('label'=>'Nama Barang', 
								'labelWidth'=>200, 
								 'value'=> "<input type=text name='kd_barang' id='kd_barang' value='".$dt['kd_barang']."' size='18' readonly >
								 			<input type=text name='nm_barang' id='nm_barang' value='".$dt['nm_barang']."' size='45' readonly>
											<input type='button' value='Cari' onclick ='".$this->Prefix.".TambahBarang()' >",  
								 'type'=>'' , 
								 'params'=>""),
								 
			'thn_perolehan' => array('label'=>'Tahun Perolehan / Nomor Register',
							   'value'=> "<input type=text name='thn_perolehan' id='thn_perolehan' value='".$dt['thn_perolehan']."' style='width:50' readonly>&nbsp;/&nbsp;<input type=text name='noreg' id='noreg' value='".$dt['noreg']."' style='width:50' readonly>
											",  
							   'type'=>'' ,
							   'param'=>"",
							 ),
			'alamat' => array('label'=>'&nbspAlamat', 
										 'value'=>"<input type=text name='alamat' id='alamat' value='".$dt['alamat']."' size='66' readonly>",  
										 'type'=>'' , 
										 'row_params'=>"valign='top'"),		
			'jml_harga' => array('label'=>'Harga Perolehan Asal',
							     //'value'=> 'Rp. '.number_format($dt['nilai_barang_asal'],2,'.',',' ),
							     'value'=> "Rp. <input type=text name='jml_harga' id='jml_harga' value='".number_format($dt['nilai_barang_asal'],2,',','.')."' style='width:135px;text-align:right;' readonly >",
								   
							     'type'=>'' ,
							     'param'=> '',
							    ),					 
			'tgl_penilaian' => array('label'=>'Tanggal Buku Penilaian',
							   'value'=> createEntryTgl('tgl_penilaian', $dt['tgl_penilaian'], false, '', '','adminForm','2'),  
							   'type'=>'' ,
							   'param'=> "",
							 ),
							 
			'tgl_perolehan' => array('label'=>'Tanggal Perolehan',
							   'value'=> $this->createEntryTgl3($dt['tgl_perolehan'], 'tgl_perolehan', false,''),  
							   'type'=>'' ,
							   'param'=> "",
							 ),								 			
			
			'nilai_brg' => array('label'=>'Harga Penilaian', 
								 'value'=> "Rp. <input type=text name='nilai_brg' id='nilai_brg' value='".number_format($dt['nilai_barang'],0,',','.')."' style='width:135px;text-align:right;' onkeypress='return isNumberKey(event)' onkeyup=".$this->Prefix.".formatRupiah(this,'.')>",  
								 'type'=>'' , 
								 'params'=>""),
							 
	  	 	'yg_menilai' => array('label'=>'',
								'labelWidth'=>150, 
								'value'=>'Yang Menilai', 
								'type'=>'merge',
								'param'=>""
								 ),	
			
			'penilai_instansi' => array('label'=>'&nbsp;&nbsp;&nbsp; Instansi/CV/PT ', 
										 'value'=> "<input type=text name='penilai_instansi' id='penilai_instansi' value='".$dt['penilai_instansi']."' size='65'>",  
										 'type'=>'' , 
										 'params'=>""),					 					
			
			'penilai_alamat' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbspAlamat', 
										 'value'=>"<textarea id='penilai_alamat' name='penilai_alamat' rows='2' cols='67'>".$dt['penilai_alamat']."</textarea>",  
										 'type'=>'' , 
										 'row_params'=>"valign='top'"),
										 
			'surat_perjanjian' => array('label'=>'',
								'labelWidth'=>150, 
								'value'=>'Surat Perjanjian/Kontrak', 
								'type'=>'merge',
								'param'=>""
								 ),	
			
			'surat_no' => array('label'=>'&nbsp;&nbsp;&nbsp; Nomor ', 
										 'value'=>"<input type=text name='surat_no' id='surat_no' value='".$dt['surat_no']."' size='65'>",  
										 'type'=>'', 
										 'params'=>""),					 					
			
			'surat_tgl' => array('label'=>'&nbsp;&nbsp;&nbsp; Tanggal ', 
										 'value'=>createEntryTgl3($dt['surat_tgl'], 'surat_tgl', false,''),  
										 'type'=>'' , 
										 'params'=>""),
			
			'ket' => array(  'label'=>'Keterangan',
							 'value'=>"<textarea id='ket' name='ket' rows='2' cols='67'>".$dt['ket']."</textarea>",  
							 'type'=>'' , 
							 'row_params'=>"style='width:150' valign='top'"),
							   
			/*'daftarpenggunaandetail' => array( 
						 'label'=>'',
						 'value'=>"<div id='daftarpenggunaandetail' style='height:5px'></div>", 
						 'type'=>'merge'
			 )					   		*/					   
							   						   
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden name='id_penilaian' id='id_penilaian' value='".$id_penilaian."'>".
			"<input type=hidden name='id_bukuinduk' id='id_bukuinduk' value='".$dt['id_bukuinduk']."'>".
			"<input type=hidden name='idbi_awal' id='idbi_awal' value='".$dt['idbi_awal']."'>".
			"<input type=hidden name='staset' id='staset' value='".$dt['staset']."'>".
			"<input type=hidden name='tahun' id='tahun' value='".$dt['tahun']."'>".
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >&nbsp;".
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
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b	= $Main->DEF_WILAYAH;
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
	 $id_bukuinduk = $_REQUEST['id_bukuinduk'];
	 $nm_barang = $_REQUEST['nm_barang'];
	 $thn_perolehan = $_REQUEST['thn_perolehan'];
	 //$tgl_closing = $Main->TGL_CLOSING;
	 $tgl_penilaian = $_REQUEST['tgl_penilaian'];
	 $noreg = $_REQUEST['noreg'];
	 //$jml_harga = $_REQUEST['jml_harga'];
	 $jml_harga = str_replace(".","",$_REQUEST['jml_harga']);
	 //$nilai_brg = $_REQUEST['nilai_brg'];
	 $nilai_brg = str_replace(".","",$_REQUEST['nilai_brg']);
	 $penilai_instansi = $_REQUEST['penilai_instansi'];
	 $penilai_alamat = $_REQUEST['penilai_alamat'];
	 $surat_no = $_REQUEST['surat_no'];
	 $surat_tgl = $_REQUEST['surat_tgl'];
	 $ket = $_REQUEST['ket'];
	 $staset = $_REQUEST['staset'];
	 $tahun = $_REQUEST['tahun'];
	 $idbi_awal = $_REQUEST['idbi_awal'];
	 $tgl_perolehan = $_REQUEST['tgl_perolehan'];
	 $alamat = $_REQUEST['alamat'];
	 $get_cd = mysql_fetch_array(mysql_query("select c,d,e,e1,tgl_buku,thn_perolehan from buku_induk where id='$id_bukuinduk'"));
	 $tgl_closing=getTglClosing($get_cd['c'],$get_cd['d'],$get_cd['e'],$get_cd['e1']); 
	 $thn_p =explode('-',$tgl_perolehan);
	 $thn_perolehan_penilaian = $thn_p[0];
	 //cek validasi
	 if( $err=='' && $tgl_penilaian =='' ) $err= 'Tanggal Penilaian belum diisi !!';
	 if( $err=='' && $tgl_perolehan =='' ) $err= 'Tanggal Perolehan belum diisi !!';
	 if($tgl_penilaian == '0000-00-00' || $tgl_penilaian=='' ) $err = 'Tanggal Penilaian belum diisi!';	
	 if(!cektanggal($tgl_penilaian)) $err = 'Tanggal Penilaian Salah!';
	 if(!cektanggal($tgl_perolehan)) $err = 'Tanggal Perolehan Salah!';
	 if($err =='' && compareTanggal($tgl_perolehan,$tgl_penilaian)==2 ) $err = 'Tanggal Perolehan tidak besar dari Tanggal Penilaian !';				
	 if( $err=='' && $kd_barang =='' ) $err= 'Kode Barang belum diisi !!';
	 if( $err=='' && $nm_barang =='' ) $err= 'Nama Barang belum diisi !!';
	 if( $err=='' && $thn_perolehan =='' ) $err= 'Tahun Perolehan belum diisi !!';
	 if( $err=='' && $jml_harga =='' ) $err= 'Harga Perolehan belum diisi !!';	 
	 //$err='cmpr_tgl='.compareTanggal($tgl_perolehan,$tgl_penilaian);
	 //$err='thn_perolehan='.$thn_perolehan_penilaian;
	 /*if( $err=='' && $nilai_brg =='' ) $err= 'Nilai Barang belum diisi !!';	 
	 if( $err=='' && $penilai_instansi =='' ) $err= 'Nama Instansi belum diisi !!';	 
	 if( $err=='' && $penilai_alamat =='' ) $err= 'Alamat Instansi belum diisi !!';	 
	 if( $err=='' && $surat_no =='' ) $err= 'Nomor Surat Perjanjian belum diisi !!';	 
	 if( $err=='' && $surat_tgl =='' ) $err= 'Tanggal Surat Perjanjian belum diisi !!';*/	 
		//get tglakhir susut,koreksi,penilaian,penghapusan_sebagian dgn idbi_awal yg sama
		$tgl_susutAkhir = mysql_fetch_array(mysql_query("select tgl from penyusutan where idbi_awal='$idbi_awal' order by tgl desc limit 1"));
		$tgl_korAkhir = mysql_fetch_array(mysql_query("select tgl,tgl_create from t_koreksi where idbi_awal='$idbi_awal' order by tgl desc limit 1"));
		$tgl_nilaiAkhir = mysql_fetch_array(mysql_query("select tgl_penilaian,tgl_create from penilaian where idbi_awal='$idbi_awal' order by tgl_penilaian desc limit 1"));
		$tgl_hpsAkhir = mysql_fetch_array(mysql_query("select tgl_penghapusan,tgl_create from penghapusan_sebagian where idbi_awal='$idbi_awal' order by tgl_penghapusan desc limit 1"));
		//-------------------------------------
	 if ($err=='' && $fmST==0){
		if($err =='' && compareTanggal($get_cd['tgl_buku'],$tgl_penilaian)==2) $err = 'Tanggal Penilaian tidak kecil dari Tanggal Buku Barang !';				
		if($err =='' && $thn_perolehan_penilaian<$get_cd['thn_perolehan']) $err = 'Tahun Perolehan tidak kecil dari Tahun Perolehan Buku Barang !';				
		if($err=='' && $tgl_penilaian<=$tgl_closing)$err ='Tanggal sudah Closing !';
		if($err=='' && $tgl_penilaian<=$tgl_susutAkhir['tgl'])$err ='Sudah ada penyusutan !';
		if($err=='' && $tgl_penilaian<$tgl_korAkhir['tgl'] )$err ='Sudah ada koreksi harga !';
		if($err=='' && $tgl_penilaian<$tgl_nilaiAkhir['tgl_penilaian'] )$err ='Sudah ada penilaian !';
		if($err=='' && $tgl_penilaian<$tgl_hpsAkhir['tgl_penghapusan'] )$err ='Sudah ada penghapusan sebagian !';
		//$err='tgl='.$tgl_closing;			
	 }else{
		$old_penilaian = mysql_fetch_array(mysql_query("select * from penilaian where id='$idplh'"));
			//$err='tgl_='.$old_penilaian['tgl_penilaian'];
			//cek tgl buku lama <= tgl closing
			if($err=='' && $old_penilaian['tgl_penilaian']<=$tgl_closing){
				if($err=='' && $old_penilaian['tgl_penilaian']!=$tgl_penilaian)$err = 'Tanggal Penilaian tidak bisa di edit,karena sudah closing !';
				if($err=='' && $old_penilaian['tgl_perolehan']!=$tgl_perolehan)$err = 'Tanggal Perolehan tidak bisa di edit,karena sudah closing !';
				if($err=='' && $old_penilaian['nilai_barang']!=$nilai_brg)$err = 'Nilai Barang tidak bisa di edit,karena sudah closing !';
				}			
			//cek tgl perolehan lama < tgl susut akhir 
			if($err=='' && ($old_penilaian['tgl_perolehan']<=$tgl_susutAkhir['tgl'] && $old_penilaian['tgl_penilaian']<=$tgl_susutAkhir['tgl'])){
				if($err=='' && $old_penilaian['tgl_penilaian']!=$tgl_penilaian)$err = 'Tanggal Penilaian tidak bisa di edit,sudah ada penyusutan !';
				if($err=='' && $old_penilaian['tgl_perolehan']!=$tgl_perolehan)$err = 'Tanggal Perolehan tidak bisa di edit,sudah ada penyusutan !';
				if($err=='' && $old_penilaian['nilai_barang']!=$nilai_brg)$err = 'Nilai Barang tidak bisa di edit,sudah ada penyusutan !';
			}
			//cek (tgl buku lama < tgl buku koreksi terakhir) atau (tgl buku lama = tgl buku koreksi terakhir  dan waktu create < waktu create koreksi terakhir)
			if(($err=='' && $old_penilaian['tgl_penilaian']<$tgl_korAkhir['tgl']) || ($old_penilaian['tgl_penilaian']==$tgl_korAkhir['tgl'] && $old_penilaian['tgl_create']<$tgl_korAkhir['tgl_create'])){
				if($err=='' && $old_penilaian['tgl_penilaian']!=$tgl_penilaian)$err = 'Tanggal Penilaian tidak bisa di edit,sudah ada koreksi harga !';
				if($err=='' && $old_penilaian['tgl_perolehan']!=$tgl_perolehan)$err = 'Tanggal Perolehan tidak bisa di edit,sudah ada koreksi harga !';
				if($err=='' && $old_penilaian['nilai_barang']!=$nilai_brg)$err = 'Nilai Barang tidak bisa di edit,sudah ada koreksi harga !';
			}
			//cek (tgl buku lama < tgl buku penilaian terakhir) atau (tgl buku lama = tgl buku penilaian terakhir  dan waktu create < waktu create penilaian terakhir)
			if(($err=='' && $old_penilaian['tgl_penilaian']<$tgl_nilaiAkhir['tgl_penilaian']) || ($old_penilaian['tgl_penilaian']==$tgl_nilaiAkhir['tgl_penilaian'] && $old_penilaian['tgl_create']<$tgl_nilaiAkhir['tgl_create'])){
				if($err=='' && $old_penilaian['tgl_penilaian']!=$tgl_penilaian)$err = 'Tanggal Penilaian tidak bisa di edit,sudah ada penilaian !';
				if($err=='' && $old_penilaian['tgl_perolehan']!=$tgl_perolehan)$err = 'Tanggal Perolehan tidak bisa di edit,sudah ada penilaian !';
				if($err=='' && $old_penilaian['nilai_barang']!=$nilai_brg)$err = 'Nilai Barang tidak bisa di edit,sudah ada penilaian !';
			}
			//cek (tgl buku lama < tgl buku hapus sebagian terakhir) atau (tgl buku lama = tgl buku hapus sebagian terakhir  dan waktu create < waktu create hapus sebagian terakhir)
			if(($err=='' && $old_penilaian['tgl_penilaian']<$tgl_hpsAkhir['tgl_penghapusan']) || ($old_penilaian['tgl_penilaian']==$tgl_hpsAkhir['tgl_penghapusan'] && $old_penilaian['tgl_create']<$tgl_hpsAkhir['tgl_create'])){
				if($err=='' && $old_penilaian['tgl_penilaian']!=$tgl_penilaian)$err = 'Tanggal Penilaian tidak bisa di edit,sudah ada penghapusan sebagian !';
				if($err=='' && $old_penilaian['tgl_perolehan']!=$tgl_perolehan)$err = 'Tanggal Perolehan tidak bisa di edit,sudah ada penghapusan sebagian !';
				if($err=='' && $old_penilaian['nilai_barang']!=$nilai_brg)$err = 'Nilai Barang tidak bisa di edit,sudah ada penghapusan sebagian !';
			}		 
		}	 	 	 
	 		if($err==''){ 
				if($fmST == 0){ //input penggunaan				
					$aqry = "INSERT INTO penilaian (id_bukuinduk,a1,a,b,c,d,e,e1,f,g,h,i,j,
										noreg,thn_perolehan,tgl_penilaian,nilai_barang_asal,
										nilai_barang,penilai_instansi,penilai_alamat,surat_no,
										surat_tgl,ket,tahun,staset,idbi_awal,tgl_update,uid,tgl_perolehan,alamat)
							VALUES ('$id_bukuinduk','$a1','$a','$b','$c','$d','$e','$e1','$f','$g','$h','$i','$j',
									'$noreg','$thn_perolehan','$tgl_penilaian','$jml_harga',
									'$nilai_brg','$penilai_instansi','$penilai_alamat','$surat_no',
									'$surat_tgl','$ket','$tahun','$staset','$idbi_awal',now(),'$uid','$tgl_perolehan','$alamat')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}else{	
					$aqry2 = "UPDATE penilaian
			        		 set "." id_bukuinduk = '$id_bukuinduk',
							 a1 = '$a1', a = '$a', b = '$b',
							 c = '$c', d = '$d', e = '$e',
							 e1 = '$e1', f = '$f', g = '$g', h = '$h',
							 i = '$i', j = '$j',
							 noreg = '$noreg',
							 thn_perolehan = '$thn_perolehan',
							 tgl_penilaian = '$tgl_penilaian',
							 nilai_barang_asal = '$jml_harga',
							 nilai_barang = '$nilai_brg',
							 penilai_instansi = '$penilai_instansi',
							 penilai_alamat = '$penilai_alamat',
							 surat_no = '$surat_no',
							 surat_tgl = '$surat_tgl',
							 ket = '$ket',
							 tahun = '$tahun',
							 staset = '$staset',
							 idbi_awal = '$idbi_awal',
							 tgl_perolehan = '$tgl_perolehan',
							 tgl_update = now(),
							 alamat = '$alamat',
							 uid = '$uid' ".
					 		 "WHERE Id='".$idplh."'";	$cek .= $aqry2;	
					$qry2 = mysql_query($aqry2);
				} //end else
			}
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }
	
	function batal(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 $tgl_closing = $Main->TGL_CLOSING;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb'];
	 $idplh = $cbid[0];
	 $ck=mysql_fetch_array(mysql_query("select * from penilaian where id='$idplh'"));
	 $idbi_awal = $ck['idbi_awal'];
	 $tgl_penilaian = $ck['tgl_penilaian'];
	 $c = $ck['c'];
	 $d = $ck['d'];
	 $e = $ck['e'];
	 $e1 = $ck['e1'];
	 //if(compareTanggal($ck['tgl_penilaian'], $tgl_closing)==0 || compareTanggal($ck['tgl_penilaian'], $tgl_closing)==1 ) $err = 'Tahun Sudah Closing!';
	 if($err=='' && sudahClosing($tgl_penilaian,$c,$d,$e,$e1))$err = 'Tanggal sudah Closing !';	 
		/*if($err==''){ 
			$aqry = "UPDATE penggunaan
	        		 set "." stbatal = '1'".
			 		 "WHERE Id='".$idplh."'";	$cek .= $aqry;	
			$qry = mysql_query($aqry);
			if($qry==FALSE) $err="Gagal Batal Penggunaan";	
		}*/
		//cek ada tgl_koreksi dan tgl_koreksi > tgl_penilaian
		$get_pemeliharaan = mysql_fetch_array(mysql_query("select count(*) as cnt from pemeliharaan where idbi_awal='$idbi_awal' and tgl_perolehan<'$tgl_penilaian'"));
		$get_pengamanan = mysql_fetch_array(mysql_query("select count(*) as cnt from pengamanan where idbi_awal='$idbi_awal' and tgl_perolehan<'$tgl_penilaian'"));
		$get_penghapusan_sebagian = mysql_fetch_array(mysql_query("select count(*) as cnt from penghapusan_sebagian where idbi_awal='$idbi_awal' and tgl_perolehan<'$tgl_penilaian'"));
		$get_koreksi = mysql_fetch_array(mysql_query("select count(*) as cnt from t_koreksi where idbi_awal='$idbi_awal' and tgl_perolehan<'$tgl_penilaian'"));
		//$err='old='.$old_penilaian['nilai_barang'];
		//if($get_koreksi['cnt']>0 && $old_penilaian['nilai_barang']<>$nilai_brg)$err = 'Nilai barang tidak bisa di edit,Tanggal koreksi melebihi tanggal penilaian !';
		/*if($get_pemeliharaan['cnt']>0 )$err = 'Data tidak bisa di edit,sudah ada pemeliharaan !';
		if($get_pengamanan['cnt']>0 )$err = 'Data tidak bisa di edit,sudah ada pengamanan !';
		if($get_penghapusan_sebagian['cnt']>0 )$err = 'Data tidak bisa di edit,sudah ada penghapusan sebagian !';
		if($get_koreksi['cnt']>0 )$err = 'Data tidak bisa di edit,sudah ada koreksi harga !';//--------------------------------------
		*/if($err==''){
			$aqry = "DELETE FROM penilaian WHERE Id='".$idplh."'";	$cek .= $aqry;	
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
$Penilaian = new PenilaianObj();

?>