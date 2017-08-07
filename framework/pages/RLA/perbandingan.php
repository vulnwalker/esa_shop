<?php

class PerbandinganObj extends DaftarObj2{
	var $Prefix = 'Perbandingan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar----------------------------------------
	var $TblName = 'v_ref_kib_keu_h1'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f');
	var $FieldSum = array();
	var $SumValue = array();
	var $fieldSum_lokasi = array(8);
	var $FieldSum_Cp1 = array( 7, 1, 1);//berdasar mode
	var $FieldSum_Cp2 = array( 7, 1, 1);
	var $withSumAll = FALSE;
	var $withSumHal = FALSE;
	var $WITH_HAL = FALSE;	
	var $checkbox_rowspan = 2;
	//cetak-----------------------------------------
	var $cetak_xls=TRUE ;
	var $fileNameExcel='PerbandinganLRA_BelanjaModal.xls';
	var $Cetak_Judul = 'PERBANDINGAN LRA - BELANJA MODAL';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead ='<style>
	.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}</style>';
	var $PageTitle = 'Perbandingan';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='9999';
	var $FormName = 'PerbandinganForm'; 	
	var $totBelanjaModal = 0;
	var $totJmlRla = 0;
	var $totSelisih = 0;
	
	
	function genRowSum(){
		//hal

		$ContentTotalHal=''; $ContentTotal='';
		return $ContentTotalHal.$ContentTotal;
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class='$TblStyle' border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		
		$ListData .= $this->setDaftar_After($no, $ColStyle);
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		//$SumHal = $this->genSumHal($Kondisi);
		$ContentSum = $this->genRowSum($ColStyle,  $Mode, 
			$SumHal['sums']
		);
		
		
		$ListData .= 
				
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>"
			.$vKondisi_old
			;
		if ($Mode==3) {	//flush
			echo $ListData;	
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}
	
	
	
	function setTitle(){
		global $Main;
		return 'Perbandingan LRA - Belanja Modal';	

	}
	
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=lra" title="Daftar LRA">DAFTAR LRA</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';	
	}
	
	function setMenuEdit(){		
		return "";
	}
	
	function setMenuView(){		
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>";					

	}
	
	function genDaftarOpsi(){
		global $Main,$fmFiltThnBuku;
		
		$fmTahun = $_REQUEST['fmTahun'];
		$fmTriwulan1 = $_REQUEST['fmTriwulan1'];
		$fmTriwulan2 = $_REQUEST['fmTriwulan2'];
		
		
		$arrTriwulan = array(
	  	         array('1','I'),
			     array('2','II'),
			     array('3','III'),
			     array('4','IV'),
					);
		
		if ($fmTahun=='') $fmTahun = $_COOKIE['coThnAnggaran'];
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					'Tahun : '."<input type=text name='fmTahun' id='fmTahun' value='$fmTahun' size=4>".
					' Triwulan : '.
					cmbArray('fmTriwulan1',$fmTriwulan1,$arrTriwulan,'--Semua--','')."&nbsp; s/d &nbsp;".
					cmbArray('fmTriwulan2',$fmTriwulan2,$arrTriwulan,'--Semua--','')
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		global $fmPILCARI;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  cekPOST('fmThnAnggaran');
		$fmThn1=  cekPOST('fmThn1');
		$fmThn2=  cekPOST('fmThn2');
		$fmSemester = cekPOST('fmSemester');
		
		
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
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script type='text/javascript' src='js/RLA/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				".
				'<style>
						.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";
	}
						</style>'.
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan=2>No.</th>
  	  			<!--$Checkbox--> 		
				<th class='th02' colspan=4>Kode Barang</th>
				<th class='th01' rowspan=2>Uraian</th>
				<th class='th01' rowspan=2>Belanja Modal</th>
				<th class='th01' rowspan=2>LRA</th>
				<th class='th01' rowspan=2>Selisih</th>
				</tr>
				
				<tr>
				<th class='th01' width='25'>f</th>
				<th class='th01' width='25'>g</th>
				<th class='th01' width='25'>h</th>
				<th class='th01' width='25'>i</th>
				</tr>
				";
				//$tambahgaris";
		$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	/*function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
	 global $Main,$HTTP_COOKIE_VARS;
	 	$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		$fmTahun = cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');	
		$fmTriwulan1 = cekPOST('fmTriwulan1')==''?'1':cekPOST('fmTriwulan1');	
		$fmTriwulan2 = cekPOST('fmTriwulan2')==''?'4':cekPOST('fmTriwulan2');	
		
		if($fmSKPD==00 && $fmUNIT==00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			$kond1 = '';
			$kond2 = '';
		}elseif($fmSKPD!=00 && $fmUNIT==00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			$kond1 = "WHERE c='$fmSKPD' ";
			$kond2 = "and c='$fmSKPD'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			$kond1 = "WHERE c='$fmSKPD' and d='$fmUNIT' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT!=00 && $fmSEKSI==00){
			$kond1 = "WHERE c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT!=00 && $fmSEKSI!=00){
			$kond1 = "WHERE c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT' and e1='$fmSEKSI' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}
		
		
			if($fmTriwulan1 == $fmTriwulan2){
				switch($fmTriwulan1){
					case '1' : $tglAwal = $fmTahun.'-01-01'; $tglAkhir = $fmTahun.'-03-31'; break;
					case '2' : $tglAwal = $fmTahun.'-04-01'; $tglAkhir = $fmTahun.'-06-30'; break;
					case '3' : $tglAwal = $fmTahun.'-07-01'; $tglAkhir = $fmTahun.'-09-30'; break;
					case '4' : $tglAwal = $fmTahun.'-10-01'; $tglAkhir = $fmTahun.'-12-31'; break;
				}
				
			}elseif($fmTriwulan1 != $fmTriwulan2){
				switch($fmTriwulan1){
					case '1' : $tglAwal = $fmTahun.'-01-01'; break;
					case '2' : $tglAwal = $fmTahun.'-04-01'; break;
					case '3' : $tglAwal = $fmTahun.'-07-01'; break;
					case '4' : $tglAwal = $fmTahun.'-10-01'; break;
				}
				
				switch($fmTriwulan2){
					case '1' : $tglAkhir = $fmTahun.'-03-31'; break;
					case '2' : $tglAkhir = $fmTahun.'-06-30'; break;
					case '3' : $tglAkhir = $fmTahun.'-09-30'; break;
					case '4' : $tglAkhir = $fmTahun.'-12-31'; break;
				}
			}
		
	
		$v_jurnal = "v_jurnal_aset_tetap";
				
		$aqry = "SELECT ".
					"aa.kint,aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i,aa.nm_barang,bb.belanja_modal ".
				"FROM v_ref_kib_keu_h1 aa ".
				"LEFT JOIN ".
					"(SELECT tgl_buku,jns_trans,IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i,".
					"SUM(IF(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1,debet,0)) as belanja_modal ".
					"FROM $v_jurnal $kond1 GROUP BY f,g,h,i WITH ROLLUP) bb ".
				"ON aa.f = bb.f AND aa.g = bb.g AND aa.h = bb.h AND aa.i= bb.i ".
				"WHERE kint='01' AND ka='01' group by f,g,h,i";
				
		return $aqry;		
	}*/
	
	function setDaftar_After($no=0, $ColStyle=''){
	
	/*	
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];			
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];			
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		
		
		
		$vtotjmlbarang 	= number_format($this->totBrgAset, $des,',','.');
		$vtotjmlharga 		= number_format($this->totHrgAset, $des,',','.');

				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		*/
		$ListData="";
		return $ListData;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		
			/*$fmTahun = $_REQUEST['fmTahun']==''?$_COOKIE['coThnAnggaran']:$_REQUEST['fmTahun'];	
			$fmTriwulan1 = $_REQUEST['fmTriwulan1']==''?'1':$_REQUEST['fmTriwulan1'];	
			$fmTriwulan2 = $_REQUEST['fmTriwulan2']==''?'4':$_REQUEST['fmTriwulan2'];
			
			if($isi['f'] == 00 ){
				$kond = "";
			}elseif($isi['f'] != 00 && $isi['g'] == 00 && $isi['h'] == 00 && $isi['i'] == 00){
				$fghi = $isi['f']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] == 00 && $isi['i'] == 00){
				$fghi = $isi['f'].$isi['g']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] != 00 && $isi['i'] == 00){
				$fghi = $isi['f'].$isi['g'].$isi['h']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] != 00 && $isi['i'] != 00){
				$fghi = $isi['f'].$isi['g'].$isi['h'].$isi['i'];
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}
			
			$aqry = "SELECT SUM( 
						IF(1>=$fmTriwulan1 && 1<=$fmTriwulan2,triwulan1,0)+
						IF(2>=$fmTriwulan1 && 2<=$fmTriwulan2,triwulan2,0)+
						IF(3>=$fmTriwulan1 && 3<=$fmTriwulan2,triwulan3,0)+
						IF(4>=$fmTriwulan1 && 4<=$fmTriwulan2,triwulan4,0)
					) AS jml_rla
					FROM ref_lra WHERE tahun='$fmTahun' $kond"; 
			$isi2 = mysql_fetch_array(mysql_query($aqry));
			
			$lra = $isi2['jml_rla']==''?0:$isi2['jml_rla'];
			$bm = $isi['belanja_modal']==''?0:$isi['belanja_modal'];
			$jml_rla = number_format($lra,2,',','.');
			$belanja_modal = number_format($bm,2,',','.');
			$slsh = $bm - $lra;
			//$slsh = $isi['selisih']==''?0:$isi['selisih'];
			$selisih = number_format($slsh,2,',','.');
			
			if($isi['ka'] != 00 && $isi['kb'] == 00){
				
				$this->totBelanjaModal += $bm;
				$this->totJmlRla += $lra;
				$this->totSelisih += $slsh;
				
				$Koloms[] = array('align="center" width="20"', $no.'.' );
	 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
				$Koloms[] = array('align="left" colspan=5',"<b> A. &nbsp;&nbsp;&nbsp;".$isi['nm_barang']."</b>");
		 		$Koloms[] = array('align="right"',"<b>$belanja_modal</b>"); 
		 		$Koloms[] = array('align="right"',"<b>$jml_rla</b>");
		 		$Koloms[] = array('align="right"',"<b>$selisih</b>");
				
			}elseif($isi['ka'] != 00 && $isi['kb'] != 00){
				
				if($isi['f'] != 00 && $isi['g'] == 00 && $isi['h'] == 00 && $isi['i'] == 00){
					
					$Koloms[] = array('align="center" width="20"', $no.'.' );
		 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['f']."</b>");
					$Koloms[] = array('align="center" width="20"','');
					$Koloms[] = array('align="center" width="20"',''); 
					$Koloms[] = array('align="center" width="20"','');
					$Koloms[] = array('align="left"',"<b>".$isi['nm_barang']."</b>");
			 		$Koloms[] = array('align="right"',"<b>$belanja_modal</b>"); 
			 		$Koloms[] = array('align="right"',"<b>$jml_rla</b>");
			 		$Koloms[] = array('align="right"',"<b>$selisih</b>");
					
				}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] == 00 && $isi['i'] == 00){
					
					$Koloms[] = array('align="center" width="20"', $no.'.' );
		 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['f']."</b>");
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['g']."</b>");
					$Koloms[] = array('align="center" width="20"',''); 
					$Koloms[] = array('align="center" width="20"','');
					$Koloms[] = array('align="left"',"<b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$isi['nm_barang']."</b>");
			 		$Koloms[] = array('align="right"',"<b>$belanja_modal</b>"); 
			 		$Koloms[] = array('align="right"',"<b>$jml_rla</b>");
			 		$Koloms[] = array('align="right"',"<b>$selisih</b>");
					
				}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] != 00 && $isi['i'] == 00){
					
					$Koloms[] = array('align="center" width="20"', $no.'.' );
		 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['f']."</b>");
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['g']."</b>");
					$Koloms[] = array('align="center" width="20"',"<b>".$isi['h']."</b>"); 
					$Koloms[] = array('align="center" width="20"','');
					$Koloms[] = array('align="left"',"<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$isi['nm_barang']."</b>");
			 		$Koloms[] = array('align="right"',"<b>$belanja_modal</b>"); 
			 		$Koloms[] = array('align="right"',"<b>$jml_rla</b>");
			 		$Koloms[] = array('align="right"',"<b>$selisih</b>");
					
				}elseif($isi['f'] != 00 && $isi['g'] != 00 && $isi['h'] != 00 && $isi['i'] != 00){
					
					$Koloms[] = array('align="center" width="20"', $no.'.' );
		 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
					$Koloms[] = array('align="center"',$isi['f']);
					$Koloms[] = array('align="center"',$isi['g']);
					$Koloms[] = array('align="center"',$isi['h']); 
					$Koloms[] = array('align="center"',$isi['i']);
					$Koloms[] = array('align="left"',"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$isi['nm_barang']);
			 		$Koloms[] = array('align="right"',$belanja_modal); 
			 		$Koloms[] = array('align="right"',$jml_rla);
			 		$Koloms[] = array('align="right"',$selisih);
					
				}
			}*/

		return $Koloms;
	}
	
	function gen_table_data($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;
		
		$v_jurnal = $Main->JURNAL_FISIK? 't_jurnal_aset' : 'v_jurnal';
		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
		$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		$fmTahun = cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');	
		$fmTriwulan1 = cekPOST('fmTriwulan1')==''?'1':cekPOST('fmTriwulan1');	
		$fmTriwulan2 = cekPOST('fmTriwulan2')==''?'4':cekPOST('fmTriwulan2');	
		
		if($fmSKPD==00 && $fmUNIT==00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			//$kond1 = ' where jns_trans=1 ';
			$kond1 = '';
			$kond2 = '';
		}elseif($fmSKPD!=00 && $fmUNIT==00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			$kond1 = "and c='$fmSKPD' ";
			$kond2 = "and c='$fmSKPD'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT==00 && $fmSEKSI==00){
			$kond1 = "and c='$fmSKPD' and d='$fmUNIT' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT!=00 && $fmSEKSI==00){
			$kond1 = "and c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}elseif($fmSKPD!=00 && $fmUNIT!=00 && $fmSUBUNIT!=00 && $fmSEKSI!=00){
			$kond1 = "and c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT' and e1='$fmSEKSI' ";
			$kond2 = "and c='$fmSKPD' and d='$fmUNIT'";
		}
		
		
		if($fmTriwulan1 == $fmTriwulan2){
			switch($fmTriwulan1){
				case '1' : $tglAwal = $fmTahun.'-01-01'; $tglAkhir = $fmTahun.'-03-31'; break;
				case '2' : $tglAwal = $fmTahun.'-04-01'; $tglAkhir = $fmTahun.'-06-30'; break;
				case '3' : $tglAwal = $fmTahun.'-07-01'; $tglAkhir = $fmTahun.'-09-30'; break;
				case '4' : $tglAwal = $fmTahun.'-10-01'; $tglAkhir = $fmTahun.'-12-31'; break;
			}
			
		}elseif($fmTriwulan1 != $fmTriwulan2){
			switch($fmTriwulan1){
				case '1' : $tglAwal = $fmTahun.'-01-01'; break;
				case '2' : $tglAwal = $fmTahun.'-04-01'; break;
				case '3' : $tglAwal = $fmTahun.'-07-01'; break;
				case '4' : $tglAwal = $fmTahun.'-10-01'; break;
			}
			
			switch($fmTriwulan2){
				case '1' : $tglAkhir = $fmTahun.'-03-31'; break;
				case '2' : $tglAkhir = $fmTahun.'-06-30'; break;
				case '3' : $tglAkhir = $fmTahun.'-09-30'; break;
				case '4' : $tglAkhir = $fmTahun.'-12-31'; break;
			}
		}

		$no=0;
		// Aset Tetap ka=01 ---------------------------------------------------
		$query1 = "SELECT ".
					//"aa.kint,aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i,aa.nm_barang, ( bb.harga_perolehan - bb.harga_atribusi )as belanja_modal ". //versi lama v_jurnal
					"aa.kint,aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i,aa.nm_barang, bb.harga_perolehan as belanja_modal ". //versi baru t_jurnal
				"FROM v_ref_kib_keu_h1 aa ".
				"LEFT JOIN ".
					"(SELECT tgl_buku,jns_trans,IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i,".
					"SUM(IF(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1,debet,0)) as harga_perolehan, ".
					"SUM(IF(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1,harga_atribusi,0)) as harga_atribusi ".
					"FROM $v_jurnal where jns_trans=1 and kint='01' and ka='01' $kond1 GROUP BY f,g,h,i WITH ROLLUP) bb ".
				"ON aa.f = bb.f AND aa.g = bb.g AND aa.h = bb.h AND aa.i= bb.i ".
				"WHERE kint='01' AND ka='01' group by f,g,h,i ;"; $cek .= $query1;
		$aQry = mysql_query($query1);
		while($isix=mysql_fetch_array($aQry)){
			$no++;
			
			//kondisi query jml_rla
			if($isix['f'] == 00 ){
				$kond = "";
			}elseif($isix['f'] != 00 && $isix['g'] == 00 && $isix['h'] == 00 && $isix['i'] == 00){
				$fghi = $isix['f']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isix['f'] != 00 && $isix['g'] != 00 && $isix['h'] == 00 && $isix['i'] == 00){
				$fghi = $isix['f'].$isix['g']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isix['f'] != 00 && $isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] == 00){
				$fghi = $isix['f'].$isix['g'].$isix['h']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isix['f'] != 00 && $isix['g'] != 00 && $isix['h'] != 00 && $isix['i'] != 00){
				$fghi = $isix['f'].$isix['g'].$isix['h'].$isix['i'];
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}
			
			//kueri jml_rla
			$aqry = "SELECT SUM( 
						IF(1>=$fmTriwulan1 && 1<=$fmTriwulan2,triwulan1,0)+
						IF(2>=$fmTriwulan1 && 2<=$fmTriwulan2,triwulan2,0)+
						IF(3>=$fmTriwulan1 && 3<=$fmTriwulan2,triwulan3,0)+
						IF(4>=$fmTriwulan1 && 4<=$fmTriwulan2,triwulan4,0)
					) AS jml_rla
					FROM ref_lra WHERE tahun='$fmTahun' $kond $kond2 "; 
			$isi2 = mysql_fetch_array(mysql_query($aqry)); $cek .= $aqry;
			
			$selisih = $isix['belanja_modal']-$isi2['jml_rla'];
			
			//Daftar
			if($isix['f']==00){
				$bm_AsetTetap = $isix['belanja_modal'];
				$lra_AsetTetap = $isi2['jml_rla'];
				$selisih_AsetTetap = $isix['belanja_modal']-$isi2['jml_rla'];
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>".
				"<td align=left class='$clGaris' colspan=5 ><b>A. &nbsp;&nbsp;   ".$isix['nm_barang']."</td>".
				"<td align=right class='$clGaris'><b>".number_format($bm_AsetTetap, 2, ',', '.').
				"<td align=right class='$clGaris'><b>".number_format($lra_AsetTetap, 2, ',', '.').
				"<td align=right class='$clGaris'><b>".number_format($selisih_AsetTetap, 2, ',', '.').
				'';
			}else{
				
				$space = '';
				$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				<td align=center class='$clGaris'>".		
					( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}".
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').	
					( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). 
				"</td>
				<td class='$clGaris' >".
				( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang'].
				"</td>
				
				<td align=right class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').number_format($isix['belanja_modal'], 2, ',', '.').
				"</td>
				<td align=right class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').number_format($isi2['jml_rla'], 2, ',', '.')."</td>
				<td align=right class='$clGaris'>".
					( $isix['i'] != '00' ? '':'<b>').number_format($selisih, 2, ',', '.')."</td>			
				</tr>"	;
			}
			
		}
		
		//Aset lainnya ka=02 -------------------------------------------------------------------
		$query2 = "SELECT ".
					//"aa.kint,aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i,aa.nm_barang,  ( bb.harga_perolehan - bb.harga_atribusi ) as belanja_modal ".
					"aa.kint,aa.ka,aa.kb,aa.f,aa.g,aa.h,aa.i,aa.nm_barang,   bb.harga_perolehan  as belanja_modal ".
				"FROM v_ref_kib_keu_h1 aa ".
				"LEFT JOIN ".
					"(SELECT tgl_buku,jns_trans,IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i,".
					"SUM(IF(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1,debet,0)) as harga_perolehan , ".
					"SUM(IF(tgl_buku<='$tglAkhir' && tgl_buku>='$tglAwal' && jns_trans=1,harga_atribusi,0)) as harga_atribusi ".
					"FROM $v_jurnal where jns_trans=1 and kint='01' and ka='02' $kond1 GROUP BY f,g,h,i WITH ROLLUP) bb ".
				"ON aa.f = bb.f AND aa.g = bb.g AND aa.h = bb.h AND aa.i= bb.i ".
				"WHERE kint='01' AND ka='02' group by f,g,h,i ;";  $cek .= $query2;
		$aQry2 = mysql_query($query2);
		while($isixx=mysql_fetch_array($aQry2)){
			$no++;
			
			//kondisi query jml_rla
			if($isixx['f'] != 00 && $isixx['g'] == 00 && $isixx['h'] == 00 && $isixx['i'] == 00){
				$fghi = $isixx['f']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isixx['f'] != 00 && $isixx['g'] != 00 && $isixx['h'] == 00 && $isixx['i'] == 00){
				$fghi = $isixx['f'].$isixx['g']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isixx['f'] != 00 && $isixx['g'] != 00 && $isixx['h'] != 00 && $isixx['i'] == 00){
				$fghi = $isixx['f'].$isixx['g'].$isixx['h']."%";
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}elseif($isixx['f'] != 00 && $isixx['g'] != 00 && $isixx['h'] != 00 && $isixx['i'] != 00){
				$fghi = $isixx['f'].$isixx['g'].$isixx['h'].$isixx['i'];
				$kond = "and concat(f,g,h,i) like '$fghi'";
			}
			
			//kueri jml_rla
			$aqry2 = "SELECT SUM( 
						IF(1>=$fmTriwulan1 && 1<=$fmTriwulan2,triwulan1,0)+
						IF(2>=$fmTriwulan1 && 2<=$fmTriwulan2,triwulan2,0)+
						IF(3>=$fmTriwulan1 && 3<=$fmTriwulan2,triwulan3,0)+
						IF(4>=$fmTriwulan1 && 4<=$fmTriwulan2,triwulan4,0)
					) AS jml_rla
					FROM ref_lra WHERE tahun='$fmTahun' $kond"; 
			$isi22 = mysql_fetch_array(mysql_query($aqry2));
			
			$selisih2 = $isixx['belanja_modal']-$isi22['jml_rla'];
			
			//Daftar
			if($isixx['g']==00){
				$bm_AsetLain = $isixx['belanja_modal'];
				$lra_AsetLain = $isi22['jml_rla'];
				$selisih_AsetLain = $isixx['belanja_modal']-$isi2['jml_rla'];
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>".
				"<td align=left class='$clGaris' colspan=5 ><b>B. &nbsp;&nbsp;   ".$isixx['nm_barang']."</td>".
				"<td align=right class='$clGaris'><b>".number_format($bm_AsetLain, 2, ',', '.').
				"<td align=right class='$clGaris'><b>".number_format($lra_AsetLain, 2, ',', '.').
				"<td align=right class='$clGaris'><b>".number_format($selisih_AsetLain, 2, ',', '.').
				'';
			}else{

				$space = '';
				$space = $isixx['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isixx['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				$space = $isixx['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
				
				$ListData .="<tr class='row0'>
				<td align=center class='$clGaris'>$no.</td>
				<td align=center class='$clGaris'>".		
					( $isixx['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isixx['f']}".
				"</td>
				<td align=center class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').	
					( $isixx['g'] != '00'? "<div class='nfmt5'>{$isixx['g']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').	
					( $isixx['h'] != '00'? "<div class='nfmt5'>{$isixx['h']}</div>" : '&nbsp;'). 
				"</td>
				<td align=center class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').	
					( $isixx['i'] != '00'? "<div class='nfmt5'>{$isixx['i']}</div>" : '&nbsp;'). 
				"</td>
				<td class='$clGaris' >".
				( $isixx['i'] != '00' ? '':'<b>').$space.$isixx['nm_barang'].
				"</td>
				
				<td align=right class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').number_format($isixx['belanja_modal'], 2, ',', '.').
				"</td>
				<td align=right class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').number_format($isi22['jml_rla'], 2, ',', '.')."</td>
				<td align=right class='$clGaris'>".
					( $isixx['i'] != '00' ? '':'<b>').number_format($selisih2, 2, ',', '.')."</td>			
				</tr>"	;
			}
		}
		
		$totBmAset = $bm_AsetTetap+$bm_AsetLain;
		$totLraAset = $lra_AsetTetap+$lra_AsetLain;
		$totSelisihAset = $selisih_AsetTetap+$selisih_AsetLain;
		
		$ListData .="<tr class='row0'>
		<td class='$clGaris' align=center colspan=6 ><b>Total</b></td>
		<td align=right class='$clGaris'><b>".number_format($totBmAset, 2, ',', '.')."</b></td>
		<td align=right class='$clGaris'><b>".number_format($totLraAset, 2, ',', '.')."</b></td>
		<td align=right class='$clGaris'><b>".number_format($totSelisihAset, 2, ',', '.')."</b></td>
		</tr>"	;
		
		if($Mode==2 || $Mode==3) $cek ='';
		
		return $ListData."<div id='cek' style='display:none'>$cek</div>";
	}
	
}
$Perbandingan = new PerbandinganObj();

?>