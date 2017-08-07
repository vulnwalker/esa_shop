<?php

class Pemeliharaan_Tambah_ManfaatObj  extends DaftarObj2{	
	var $Prefix = 'Pemeliharaan_Tambah_Manfaat';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pemeliharaan'; //daftar
	var $TblName_Hapus = 'pemeliharaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array('biaya_pemeliharaan');//array('jml_harga');
	var $SumValue = array();
	//var $fieldSum_lokasi = array(5); //lokasi sumary di kolom ke
	var $FieldSum_Cp1 = array( 4, 5, 5);//berdasar mode
	var $FieldSum_Cp2 = array( 2, 2, 2);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Pemeliharaan Tambah Manfaat';
	var $PageIcon = 'images/pengamanan_ico.gif';
	var $ico_width = '24';
	var $ico_height = '24';	
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='pemeliharaan_tambah_manfaat.xls';
	var $Cetak_Judul = 'Pemeliharaan Tambah Manfaat';	
	var $Cetak_Mode=1;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Pemeliharaan_Tambah_ManfaatForm'; 
	var $totalCol = 7;	
	
	function setTitle(){
		return 'Pemeliharaan Tambah Manfaat';
	}
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		$aqry = "SELECT ".
  "`aa`.`id`, `aa`.`id_bukuinduk`,`bb`.`c`, `bb`.`d`, `bb`.`e`, `bb`.`e1`,".
  "`aa`.`tgl_pemeliharaan`, `bb`.`f`, `bb`.`g`, `bb`.`h`, `bb`.`i`, `bb`.`j`, `aa`.`biaya_pemeliharaan`".
  " FROM ".
  "`pemeliharaan` `aa` JOIN ".
  "`buku_induk` `bb` ON `aa`.`id_bukuinduk` = `bb`.`id` $Kondisi GROUP BY".
  "`aa`.`id` $Order $Limit ";	
		return $aqry;		
	}
	
	function setSumHal_query($Kondisi, $fsum){
		//return "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		return "select $fsum from (SELECT ".
  "`aa`.`id`, `aa`.`id_bukuinduk`,`bb`.`c`, `bb`.`d`, `bb`.`e`, `bb`.`e1`,".
  "`aa`.`tgl_pemeliharaan`, `bb`.`f`, `bb`.`g`, `bb`.`h`, `bb`.`i`, `bb`.`j`, `aa`.`biaya_pemeliharaan`".
  " FROM ".
  "`pemeliharaan` `aa` JOIN ".
  "`buku_induk` `bb` ON `aa`.`id_bukuinduk` = `bb`.`id` $Kondisi GROUP BY".
  "`aa`.`id`) aa  "; //echo $aqry;
		
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}   
   
   function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
					 "<thead>
					 <tr>
				  	   <th class='th01' width='20' >No.</th>
				  	   <!--$Checkbox-->		
				   	   <th class='th01' width='100' >ID Barang</th>					  
					   <th class='th01' width='200' >Kode Barang</th>					  
					   <th class='th01' width='300' >Jumlah Pemeliharaan</th>					   					   
					   <th class='th01' width='300' >Total Biaya Pemeliharaan</th>					   					   
					   <th class='th01' width='300' >Nilai Perolehan</th>					   					   
					   <th class='th01' width='200' >Penambahan Masa Manfaat (Tahun)</th>					   					   
					</tr>
					</thead>";
	
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 $tahun = cekPOST('fmtahun');
	 $fmkode = cekPOST('fmkode');
	 $fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
	 $fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
	 $fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
	 $fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');
	 
	 $Arrkondisi = array();	
	 $Arrkondisi[]= "aa.id='".$isi['id']."'";
	 if(!empty($tahun))  $Arrkondisi[]= "year(tgl_pemeliharaan)='$tahun'";
	 if(!empty($fmkode)) $Arrkondisi[]= "concat(f,'.',g,'.',h,'.',i,'.',j)='$fmkode%'";
	 if(!($fmSKPD=='' || $fmSKPD=='00') ) $Arrkondisi[] = "bb.c='$fmSKPD'";
	 if(!($fmUNIT=='' || $fmUNIT=='00') ) $Arrkondisi[] = "bb.d='$fmUNIT'";
	 if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $Arrkondisi[] = "bb.e='$fmSUBUNIT'";
	 if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $Arrkondisi[] = "bb.e1='$fmSEKSI'";
	 $Arrkondisi =join(' and ',$Arrkondisi);
	 $Arrkondisi =$Arrkondisi==''?'':'WHERE '.$Arrkondisi;
	 
	 $kode = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];		
	
	 $query = "SELECT count(*)as jml,sum(biaya_pemeliharaan)as jml_biaya  FROM $this->TblName aa JOIN buku_induk bb ON aa.id_bukuinduk=bb.id $Arrkondisi GROUP BY `aa`.`id`";
	 $get_jml = mysql_fetch_array(mysql_query($query));	
	 $jml_pemeliharaan =  $get_jml['jml'];
	 $biaya_pemeliharaan =  $get_jml['jml_biaya'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 //if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
 	 $Koloms[] = array('align="left" ',$isi['id']); 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="left" ',$kode);	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="right" ',number_format($jml_pemeliharaan,0,',','.'));	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="right" ',number_format($biaya_pemeliharaan,2,',','.'));	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="right" ',);//number_format($isi['nilai_perolehan'],2,',','.'));	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="left" ',);//$isi['masa_manfaat']);	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 //$Koloms[] = array('align="right" ',$query);	 		  	 	  	 	 	 	 	 	 	 	 	 	 
	 return $Koloms;
	}		
	
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$tahun = $_GET['tahun'];
		return
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>".
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' id='tahun' name='tahun' value='$tahun'>".
				//$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='$this->elCurrPage' name='$this->elCurrPage' value='1'>".
			"</div>";
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;	
			
		$fmtahun = cekPOST('fmtahun');
		$fmkode = cekPOST('fmkode');
		$TampilOpt ="<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') .
				"</td>
			<td >" .
			"</td></tr>					
			</table>".		
			genFilterBar(
				array(												
					"&nbsp;Tampilkan : Tahun Buku  <input text value='$fmtahun' id='fmtahun' name='fmtahun' size='4'>&nbsp;&nbsp;Kode Barang : <input text value='$fmkode' id='fmkode' name='fmkode' size='13' onkeyup='".$this->Prefix.".cekcari(this)'>"	
				),				
				$this->Prefix.".refreshList(true)");
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID'];
		$fmtahun = $_REQUEST['fmtahun'];		
		$kode = $_REQUEST['fmkode'];	
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "bb.c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "bb.d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "bb.e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "bb.e1='$fmSEKSI'";
		if(!empty($fmtahun)) $arrKondisi[] = "Year(tgl_pemeliharaan)='$fmtahun'";		
		if(!empty($kode))  $arrKondisi[] = " concat(bb.f,'.',bb.g,'.',bb.h,'.',bb.i,'.',bb.j) like '$kode%' ";		
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;		
		
		}

			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
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
	
   function setMenuEdit(){		
		return
			"";
	}
   
   function setMenuView(){
		return 
		"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
		<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>
		<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel",'Export ke Excell')."</td>
		";
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
			 "<script type='text/javascript' src='js/pemeliharaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			"<script src='js/penatausaha.js' type='text/javascript'></script>".
			
			$scriptload;
	}
	
}
$Pemeliharaan_Tambah_Manfaat = new Pemeliharaan_Tambah_ManfaatObj();

?>