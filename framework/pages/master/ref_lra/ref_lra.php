<?php

class Ref_LRAObj extends DaftarObj2{
	var $Prefix = 'Ref_LRA';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_lra'; //daftar
	var $TblName_Hapus = 'ref_lra';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array('triwulan1','triwulan2','triwulan3','triwulan4','total');//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array(12);
	var $FieldSum_Cp1 = array( 11, 1, 1);//berdasar mode
	var $FieldSum_Cp2 = array( 11, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='LRA.xls';
	var $Cetak_Judul = 'DAFTAR LRA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Ref_LRAForm'; 	

	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
		return
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
			"<input type='hidden' id='fmTahun' name='fmTahun' value='$fmTahun'>".
				$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='$this->elCurrPage' name='$this->elCurrPage' value='1'>".
			"</div>";
	}
	
	function setTitle(){
		global $Main;
		return 'Daftar LRA';	

	}
	
	/*function setNavAtas(){
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=perbandingan" title="Perbandingan LRA - Belanja Modal">PERBANDINGAN</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';	
	}*/
	
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		
		//if (sizeof($this->FieldSum)>0){
		if (sizeof($this->FieldSum)==1){
			
			$TampilTotalHalRp = $this->genRowSum_setTampilValue(0, $this->SumValue[0]);//number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total per Halaman</td>": '';
			//$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total</td>": '';
			//$Kanan2 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;			
			
			$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[0]."</div></td>
					$Kanan2
				</tr>" ;
				
			
			
		}else if (sizeof($this->FieldSum)>1){
			
			$colspanarr=$this->fieldSum_lokasi;
			$ContentTotalHal =	"<tr>";
			$ContentTotal =	"<tr>";
			
			
			for ($i=0; $i<sizeof($this->FieldSum);$i++){
				
				if($i==0){
					$TotalColSpan1 =  //$Mode==1? $colspanarr[0]-1 : $colspanarr[0]-2  ;			
						$this->genRowSum_setColspanTotal($Mode, $colspanarr );
					$Kiri1 = $TotalColSpan1 > 0 ? 
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalhalstr</td>": '';
					$ContentTotalHal .=	$Kiri1;
					$ContentTotal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalAllStr</td>":'';
				}else{
					$TotalColSpan1 = $colspanarr[$i] - $colspanarr[$i-1]-1; 
					//if($TotalColSpan1>0){
					$ContentTotalHal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
					$ContentTotal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
					
					//}
				}
				//$totalstr = $i==0? "<b>Total per Halaman": '';
				////$TotalColSpan1 = $colspanarr[$i]- $colspanarr[$i-1]-1;			
				//$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$totalstr</td>": '';
				
				$TampilTotalHalRp = //number_format($this->SumValue[$i],2, ',', '.');
					$this->genRowSum_setTampilValue($i, $this->SumValue[$i]);
				$vTotal = number_format($Total[$i],2, ',', '.');
				$ContentTotalHal .= //$i==0?
					//"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	:
					"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	;
				$ContentTotal .= $i==0?
					"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[$i]."</div></td>":
					"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum$i'>".$Total[$i]."</div></td>";
			}
			
			//$totrow = $Mode == 1? $this->totalRow : $this->totalRow;
			$TotalColSpan1 = $this->totalCol - $colspanarr[sizeof($this->FieldSum)-1];					
			$ContentTotalHal .=	$TotalColSpan1 > 0 ?
						"</tr>": '</tr>';					
			$ContentTotal .= $TotalColSpan1 > 0 ?
						"</tr>": '</tr>';					
			
			
		}
		$ContentTotal = $this->withSumAll? $ContentTotal: '';
		$ContentTotalHal = $this->withSumHal? $ContentTotalHal: '';
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			//$ContentTotalHal='';			
			if ($this->withSumAll) {
				$ContentTotalHal = '';
			}
		}
		return $ContentTotalHal.$ContentTotal;
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
	
	function setMenuEdit(){		
		return

			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function setMenuView(){		
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>";					

	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmBARANG = cekPOST('fmBARANG');			
	
	 $arr = array(
			//array('selectAll','Semua'),
			array('selectfg','Kode Barang'),
			array('selectbarang','Nama Barang'),	
			);
		
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Barang'),
			     array('2','Nama Barang'),	
	 );	
				
	$TampilOpt = 
			/*"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".*/
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			 "<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
					$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange=Ref_LRA.BidangAfter2() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
				"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
					$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=Ref_LRA.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
				"</td></tr></table>".
			"</td>
			</tr></table>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:150px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Tahun : <input type='text' id='fmTahun' name='fmTahun' value='".$fmTahun."' size=3>&nbsp
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='15' size=20px placeholder='ex : 01.01.01.01' >&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>";			
		return array('TampilOpt'=>$TampilOpt);
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmTahun = $_REQUEST['fmTahun'];			
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];				
		$fmKODE = $_REQUEST['fmKODE'];
		$fmBARANG = $_REQUEST['fmBARANG'];		
		
		/*$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		if(!($fmSEKSI=='' || $fmSEKSI=='00' || $fmSEKSI=='000') ) $arrKondisi[] = 'e1='.$fmSEKSI;*/
		
		$fmSKPD = cekPOST('fmSKPDBidang');
		$fmUNIT = cekPOST('fmSKPDskpd');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		
		if(empty($fmBIDANG)) {
			$fmKELOMPOK = '';
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmKELOMPOK)) {
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmSUBKELOMPOK)) {		
			$fmSUBSUBKELOMPOK='';
		}		
		
		if(empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			//$arrKondisi[]= "f !=00 and g=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG"; //$arrKondisi[]= "f =$fmBIDANG and g!=00 and h=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h!=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i!=00 and j=00";				
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i=$fmSUBSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i=$fmSUBSUBKELOMPOK and j!=00";			
		}						
		if(!empty($fmTahun)) $arrKondisi[] = " tahun = '$fmTahun'";					
		if(!empty($fmKODE)) $arrKondisi[] = " concat(f,g,h,i,j) like '".str_replace('.','',$fmKODE)."%'";					
		if(!empty($fmBARANG)) $arrKondisi[] = " nm_barang like '%".$fmBARANG."%'";	

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = "" ;break;
			//case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			//case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
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
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script type='text/javascript' src='js/master/ref_lra/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan=2>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01' rowspan=2>Tahun</th>
				<th class='th02' colspan=2>SKPD</th>
				<th class='th02' colspan=5>Kode Barang</th>
				<th class='th01' rowspan=2>Nama Barang</th>
				<th class='th02' colspan=5>Nilai Triwulan (Rp)</th>
				</tr>
				
				<tr>
				<th class='th01' width='20' rowspan=2>c</th>
				<th class='th01' width='20' rowspan=2>d</th>
				<th class='th01' width='20' rowspan=2>f</th>
				<th class='th01' width='20' rowspan=2>g</th>
				<th class='th01' width='20' rowspan=2>h</th>
				<th class='th01' width='20' rowspan=2>i</th>
				<th class='th01' width='20' rowspan=2>j</th>
				<th class='th01'>I</th>
				<th class='th01'>II</th>
				<th class='th01'>III</th>
				<th class='th01'>IV</th>
				<th class='th01'>Total</th>
				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		
		$aqry = "select * from(SELECT
				  `aa`.*, `bb`.`nm_barang`, (`aa`.`triwulan1`+`aa`.`triwulan2`+`aa`.`triwulan3`+`aa`.`triwulan4`) AS `total`
				FROM
				  `ref_lra` `aa` LEFT JOIN
				  `ref_barang` `bb` ON `aa`.`f` = `bb`.`f` AND `aa`.`g` = `bb`.`g` AND
				  `aa`.`h` = `bb`.`h` AND `aa`.`i` = `bb`.`i` AND `aa`.`j` = `bb`.`j` ) `cc` 
				$Kondisi $Order $Limit ";
		return $aqry;		
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
			
			$tri1 = number_format($isi['triwulan1'],2,',','.');
			$tri2 = number_format($isi['triwulan2'],2,',','.');
			$tri3 = number_format($isi['triwulan3'],2,',','.');
			$tri4 = number_format($isi['triwulan4'],2,',','.');
			$total = number_format($isi['total'],2,',','.');
			
			$Koloms[] = array('align="center" width="20"', $no.'.' );
 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="center"',$isi['tahun']);
			$Koloms[] = array('align="left"',$isi['c']);
			$Koloms[] = array('align="left"',$isi['d']);
			$Koloms[] = array('align="left"',$isi['f']);
			$Koloms[] = array('align="left"',$isi['g']);
			$Koloms[] = array('align="left"',$isi['h']); 
			$Koloms[] = array('align="left"',$isi['i']);
			$Koloms[] = array('align="left"',$isi['j']);
	 		$Koloms[] = array('align="left"',$isi['nm_barang']); 
	 		$Koloms[] = array('align="right"',$tri1);
	 		$Koloms[] = array('align="right"',$tri2);
	 		$Koloms[] = array('align="right"',$tri3);
	 		$Koloms[] = array('align="right"',$tri4);
	 		$Koloms[] = array('align="right"',$total);

		return $Koloms;
	}
	
	function setSumHal_query($Kondisi, $fsum){
	
		return "select $fsum from (select * from(SELECT
				  `aa`.*, `bb`.`nm_barang`, (`aa`.`triwulan1`+`aa`.`triwulan2`+`aa`.`triwulan3`+`aa`.`triwulan4`) AS `total`
				FROM
				  `ref_lra` `aa` LEFT JOIN
				  `ref_barang` `bb` ON `aa`.`f` = `bb`.`f` AND `aa`.`g` = `bb`.`g` AND
				  `aa`.`h` = `bb`.`h` AND `aa`.`i` = `bb`.`i` AND `aa`.`j` = `bb`.`j` ) `cc` 
				$Kondisi) `dd` "; //echo $aqry;
		
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

			case 'simpan':{
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
		   }
		   
			case 'BidangAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKELOMPOK = cekPOST('fmKELOMPOK2');
				$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK2');
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content->kelompok = cmbQuery1("fmKELOMPOK2",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBidang' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.KelompokAfter()\"",'Pilih','');
				$content->subkelompok = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
				$content->subsubkelompok = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
			break;
			}
			
			case 'KelompokAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKelompok = $_REQUEST['fmKelompok'];
				$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK2');
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content->subkelompok = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBidang' and g ='$fmKelompok' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.SubKelompokAfter()\"",'Pilih','');
				$content->subsubkelompok = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');

			break;
			}
			
			case 'SubKelompokAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKelompok = $_REQUEST['fmKelompok'];
				$fmSubKelompok = $_REQUEST['fmSubKelompok'];
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content= cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBidang' and g ='$fmKelompok' and h = '$fmSubKelompok' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
			break;
			}		   			
			case 'BidangAfter2':{
				$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=dpb_rencana.refreshList(true)','--- Pilih SKPD ---','00');
			break;
			}
				
			case 'SKPDAfter':{
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
				setcookie('cofmSKPD',$fmSKPDBidang);
				setcookie('cofmUNIT',$fmSKPDskpd);
			break;
		    }
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormBaru(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];	
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];

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
		
		$aqry = "select * from ref_lra where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$this->form_fmST = 1;
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){	
		global $SensusTmp, $Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 600;
		$this->form_height = 350;
		if ($this->form_fmST==0) {
			$this->form_caption = 'LRA - Baru';
			$c = $_REQUEST['fmSKPDBidang'];
			$d = $_REQUEST['fmSKPDskpd'];
			$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
			$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
			$fmBIDANG = $_REQUEST['fmBIDANG'];
			$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
			$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
			$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];
		}else{
			$this->form_caption = 'LRA - Edit';
			$c = $dt['c'];
			$d = $dt['d'];
			$e = $dt['e'];
			$e1 = $dt['e1'];
			$fmBIDANG = $dt['f'];
			$fmKELOMPOK = $dt['g'];
			$fmSUBKELOMPOK = $dt['h'];
			$fmSUBSUBKELOMPOK = $dt['i'];			
		}
		
		//items ----------------------
		$tahun=  $_COOKIE['coThnAnggaran'];
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='$c' and d='00' "));
		$skpd = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='$c' and d='$d' and e='00' "));
		$unit = $get['nm_skpd'];
		if($e != 00){
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='$c' and d='$d' and e='$e' and e1='00'"));
			$subunit = $get['nm_skpd'];	
		}else{
			$subunit = '-';
		}
		if($e1 != 000){
			$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='$c' and d='$d' and e='$e' and e1='$e1' "));
			$seksi = $get['nm_skpd'];
		}else{
			$seksi = '-';
		}
			
		
		$pilihKELOMPOK = cmbQuery1("fmKELOMPOK2",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.KelompokAfter()\"",'Pilih','');
		$pilihSUBKELOMPOK = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.SubKelompokAfter()\"",'Pilih','');
		$pilihSUBSUBKELOMPOK = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
		
		$this->form_fields = array(	
			
			'tahun' => array('label'=>'Tahun',
							   'value'=> "<input type=text name=tahun id=tahun value='$tahun' size=4>",  
							   'type'=>'' ,
							   'param'=> ""),
			
			'skpd' => array( 'label'=>'BIDANG', 
								'labelWidth'=>150,
								'value'=>"<input type='text' name='skpd' id='skpd' size='50px' value='$skpd' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'unit' => array( 'label'=>'SKPD', 
								'value'=>"<input type='text' name='unit' id='unit' size='50px' value='$unit' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),	
							
			/*
			'subunit' => array( 'label'=>'UNIT', 
								'labelWidth'=>150,
								'value'=>"<input type='text' name='subunit' id='subunit' size='50px' value='$subunit' readonly=''>", 
								'type'=>'', 
								'row_params'=>"height='21'"
							),
			'seksi' => array( 'label'=>'SUB UNIT', 
								'value'=>"<input type='text' name='seksi' id='seksi' size='50px' value='$seksi' readonly=''>", 
								'type'=>'',
								'row_params'=>"height='21'"
							),	
			*/														 
			'bidang' => array('label'=>'Bidang', 
								'labelWidth'=>100, 
								 'value'=> cmbQuery1("fmBIDANG2",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.BidangAfter()\"",'Pilih',''),  
								 'type'=>'' , 
								 'params'=>""),
								 
			'kelompok' => array('label'=>'Kelompok', 
								'labelWidth'=>100, 
								 'value'=> '<div id=kelompok_formdiv>'.$pilihKELOMPOK.'</div>',  
								 'type'=>'' , 
								 'params'=>""),
								 
			'subkelompok' => array('label'=>'Sub Kelompok', 
								'labelWidth'=>100, 
								 'value'=> '<div id=subkelompok_formdiv>'.$pilihSUBKELOMPOK.'</div>',  
								 'type'=>'' , 
								 'params'=>""),
			
			'subsubkelompok' => array('label'=>'Sub Sub Kelompok', 
								'labelWidth'=>100, 
								 'value'=> '<div id=subsubkelompok_formdiv>'.$pilihSUBSUBKELOMPOK.'</div>',  
								 'type'=>'' , 
								 'params'=>""),
			
			'nilai' => array('label'=>'', 
							'value'=>'Nilai', 
							'type'=>'merge',
							'params'=>""
							),
							
			'triwulan1' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Triwulan I (Rp)', 
							'value'=>inputFormatRibuan("triwulan1","",$dt['triwulan1'],""), 
							'type'=>'',
							'param'=>""),
							
			'triwulan2' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Triwulan II (Rp)', 
							'value'=>inputFormatRibuan("triwulan2","",$dt['triwulan2'],""), 
							'type'=>'',
							'param'=>""),
							
			'triwulan3' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Triwulan III (Rp)', 
							'value'=>inputFormatRibuan("triwulan3","",$dt['triwulan3'],""), 
							'type'=>'',
							'param'=>""),
							
			'triwulan4' => array('label'=>'&nbsp;&nbsp;&nbsp;&nbsp; Triwulan IV (Rp)', 
							'value'=>inputFormatRibuan("triwulan4","",$dt['triwulan4'],""), 
							'type'=>'',
							'param'=>""),	

		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$c."'> ".
			"<input type=hidden id='d' name='d' value='".$d."'> ".
			//"<input type=hidden id='e' name='e' value='".$e."'> ".
			//"<input type=hidden id='e1' name='e1' value='".$e1."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
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
	 $tahun = $_REQUEST['tahun'];
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 //$e = $_REQUEST['e'];
	 //$e1 = $_REQUEST['e1'];
	 $f = $_REQUEST['fmBIDANG2']==''?'00':$_REQUEST['fmBIDANG2'];
	 $g = $_REQUEST['fmKELOMPOK2']==''?'00':$_REQUEST['fmKELOMPOK2'];
	 $h = $_REQUEST['fmSUBKELOMPOK2']==''?'00':$_REQUEST['fmSUBKELOMPOK2'];
	 $i = $_REQUEST['fmSUBSUBKELOMPOK2']==''?'00':$_REQUEST['fmSUBSUBKELOMPOK2'];
	 $j = $Main->KODEBARANGJ;
	 $tri1 = $_REQUEST['triwulan1'];
	 $tri2 = $_REQUEST['triwulan2'];
	 $tri3 = $_REQUEST['triwulan3'];
	 $tri4 = $_REQUEST['triwulan4'];
	 
	 //cek validasi
	 if( $err=='' && $tahun =='' ) $err= 'Tahun belum diisi !!';
	 if( $err=='' && $c =='' ) $err= 'BIDANG SKPD belum dipilih !!';
	 if( $err=='' && $d =='' ) $err= 'SKPD belum dipilih !!';
	 //if( $err=='' && $e =='' ) $err= 'UNIT belum dipilih !!';
	 //if( $err=='' && $e1 =='' ) $err= 'SUB UNIT belum dipilih !!';
	 //if( $err=='' && $f =='' ) $err= 'Bidang belum dipilih !!';
	 //if( $err=='' && $g =='' ) $err= 'Kelompok belum dipilih !!';
	 //if( $err=='' && $h =='' ) $err= 'Sub Kelompok belum dipilih !!';
	 //if( $err=='' && $i =='' ) $err= 'Sub Sub Kelompok belum dipilih !!';	 
	 if( $err=='' && $tri1 =='' ) $err= 'Nilai Triwulan I belum diisi !!';	 
	 if( $err=='' && $tri2 =='' ) $err= 'Nilai Triwulan II belum diisi !!';	
	 if( $err=='' && $tri3 =='' ) $err= 'Nilai Triwulan III belum diisi !!';	
	 if( $err=='' && $tri4 =='' ) $err= 'Nilai Triwulan IV belum diisi !!';	
	 	 	 	 
	 
		if($fmST == 0){ //insert ref_LRA
			if($err==''){ 
				$aqry = "INSERT INTO ref_lra (c,d,f,g,h,i,j,tahun,triwulan1,triwulan2,triwulan3,triwulan4) VALUES ('$c','$d','$f','$g','$h','$i','$j','$tahun','$tri1','$tri2','$tri3','$tri4')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				
			}	
		}elseif($fmST == 1){//update ref_LRA
		
			if($err==''){
				$aqry2 = "UPDATE ref_lra
		        		 set "."
						 f = '$f',"."
						 g = '$g',"."
						 h = '$h',"."
						 i = '$i',"."
						 j = '$j',"."
						 tahun = '$tahun',"."
						 triwulan1 = '$tri1',"."
						 triwulan2 = '$tri2',"."
						 triwulan3 = '$tri3',"."
						 triwulan4 = '$tri4'".
				 		 "WHERE Id='".$idplh."'";	$cek .= $aqry2;	
				$qry2 = mysql_query($aqry2);
				
			}
		} //end else
					
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }
	
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main;
	 Global $fmSKPDBidang;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil['c']}. {$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');
		setcookie('cofmSKPD',$fmSKPDBidang);
		setcookie('cofmUNIT',$fmSKPDskpd);
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[d]}. {$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	
}
$Ref_LRA = new Ref_LRAObj();

?>