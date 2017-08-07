<?php

class alokasiDpaObj  extends DaftarObj2{	
	var $Prefix = 'alokasiDpa';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'tabel_spd'; //bonus
	var $TblName_Hapus = 'tabel_spd';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'ALOKASI';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='alokasiDpa.xls';
	var $namaModulCetak='RINCIAN TEMPLATE';
	var $Cetak_Judul = 'RINCIAN TEMPLATE';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'alokasiDpaForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0

	var $modul = "DPA";
	var $jenisForm = "";
	var $tahun = "";
	var $nomorUrut = "";
	var $jenisAnggaran = "";
	var $idTahap = "";
	var $currentTahap = "";
	var $namaTahapTerakhir = "";
	var $masaTerakhir = "";
	//buatview
	var $urutTerakhir = "";
	var $urutSebelumnya = "";
	var $jenisFormTerakhir = "";
	
	var $username = "";
	var $provinsi = "";
	var $kota = "";
	var $pengelolaBarang = "";
	var $pejabatPengelolaBarang = "";
	var $pengurusPengelolaBarang = "";
	var $nipPengelola = "";
	var $nipPejabat = "";
	var $nipPengurus ="";
	
	var $publicVar = "";
	var $publicKondisi = "";
	var $publicExcept = array();
	var $publicGrupId = array();
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function setTitle(){
		return 'ALOKASI '.$this->jenisAnggaran.' TAHUN '.$this->tahun;
	}
	function setMenuEdit(){
	 	
		return $listMenu ;
	}
	function setMenuView(){
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Laporan()","print_f2.png",'Laporan',"Laporan")."</td>";				
			
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){	
			
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		case 'Report':{	
			foreach ($_REQUEST as $key => $value) { 
			 	 $$key = $value; 
			}
			
			setcookie('triwulan',$cmbTriwulan);
													
		break;
		}
		case 'Laporan':{	
			$json = FALSE;
			$this->Laporan($dt);										
		break;
		}
		

		


		 default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
		 break;
		 }
		 
	 }//end switch
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
   
 function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>";
		return 	
			"<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/perencanaan_v2/dpa/alokasi/alokasiDpa.js' language='JavaScript'></script>
			
			".
			
			$scriptload;
	}
	
	
	
	//form ==================================
	
	
	function setPage_HeaderOther(){

	}
		
function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	  foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' rowspan = '2'>No.</th>
	   <th class='th01' width='100' rowspan = '2'>NAMA REKENING</th>		
	   <th class='th01' width='100' rowspan = '2'>JUMLAH (RP)</th>		
	   <th class='th02' width='100' colspan ='3'>TRIWULAN I</th>		
	   <th class='th02' width='100' colspan ='3'>TRIWULAN II</th>		
	   <th class='th02' width='100' colspan ='3'>TRIWULAN III</th>		
	   <th class='th02' width='100' colspan ='3'>TRIWULAN IV</th>		
	   </tr>
	   <tr>
	   	<th class='th01' width='50' >JAN</th>	
	   	<th class='th01' width='50' >FEB</th>	
	   	<th class='th01' width='50' >MAR</th>	
	   	<th class='th01' width='50' >APR</th>	
	   	<th class='th01' width='50' >MEI</th>	
	   	<th class='th01' width='50' >JUN</th>	
	   	<th class='th01' width='50' >JUL</th>	
	   	<th class='th01' width='50' >AGU</th>	
	   	<th class='th01' width='50' >SEP</th>	
	   	<th class='th01' width='50' >OKT</th>	
	   	<th class='th01' width='50' >NOP</th>	
	   	<th class='th01' width='50' >DES</th>
	   </tr>
	   </thead>";
	   
	   if($cmbTriwulan =='1'){
	   	$headerTable = " <tr>
	  	   <th class='th01' width='5' rowspan = '2'>No.</th>
	  	   <th class='th01' width='40' rowspan = '2'>KODE REKENING</th>	
		   <th class='th01' width='100' rowspan = '2'>NAMA REKENING</th>		
		   <th class='th01' width='100' rowspan = '2'>JUMLAH (RP)</th>		
		   <th class='th02' width='100' colspan ='4'>TRIWULAN I</th>	
		   </tr>
		   <tr>
		   	<th class='th01' width='50' >JAN</th>	
		   	<th class='th01' width='50' >FEB</th>	
		   	<th class='th01' width='50' >MAR</th>	
		   	<th class='th01' width='50' >JML</th>
			<tr>	";
	   }elseif($cmbTriwulan =='2'){
	   	$headerTable = " <tr>
	  	   <th class='th01' width='5' rowspan = '2'>No.</th>
	  	   <th class='th01' width='40' rowspan = '2'>KODE REKENING</th>	
		   <th class='th01' width='100' rowspan = '2'>NAMA REKENING</th>		
		   <th class='th01' width='100' rowspan = '2'>JUMLAH (RP)</th>		
		   <th class='th02' width='100' colspan ='4'>TRIWULAN II</th>	
		   </tr>
		   <tr>
		   	<th class='th01' width='50' >APR</th>	
		   	<th class='th01' width='50' >MEI</th>	
		   	<th class='th01' width='50' >JUN</th>	
		   	<th class='th01' width='50' >JML</th>
			<tr>	";
	   }elseif($cmbTriwulan =='3'){
	   	$headerTable = " <tr>
	  	   <th class='th01' width='5' rowspan = '2'>No.</th>
	  	   <th class='th01' width='40' rowspan = '2'>KODE REKENING</th>	
		   <th class='th01' width='100' rowspan = '2'>NAMA REKENING</th>		
		   <th class='th01' width='100' rowspan = '2'>JUMLAH (RP)</th>		
		   <th class='th02' width='100' colspan ='4'>TRIWULAN III</th>	
		   </tr>
		   <tr>
		   	<th class='th01' width='50' >JUL</th>	
		   	<th class='th01' width='50' >AGU</th>	
		   	<th class='th01' width='50' >SEP</th>	
		   	<th class='th01' width='50' >JML</th>
			<tr>	";
	   }elseif($cmbTriwulan =='4'){
	   	$headerTable = " <tr>
	  	   <th class='th01' width='5' rowspan = '2'>No.</th>
	  	   <th class='th01' width='40' rowspan = '2'>KODE REKENING</th>	
		   <th class='th01' width='100' rowspan = '2'>NAMA REKENING</th>		
		   <th class='th01' width='100' rowspan = '2'>JUMLAH (RP)</th>		
		   <th class='th02' width='100' colspan ='4'>TRIWULAN IV</th>	
		   </tr>
		   <tr>
		   	<th class='th01' width='50' >OKT</th>	
		   	<th class='th01' width='50' >NOP</th>	
		   	<th class='th01' width='50' >DES</th>	
		   	<th class='th01' width='50' >JML</th>
			<tr>	";
	   }
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 $idnya = $isi['id'];
	 foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
	 if($cmbSubUnit != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit'";
			

			}elseif($cmbUnit != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' ";
			}elseif($cmbSKPD != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
				if(!empty($hiddenP)){
					$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
					if(!empty($cmbUnit)){
						$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit'  and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
						if(!empty($cmbSubUnit)){
							$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
						
						
						}
					}
		if(!empty($q)){
					$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";	
					if(!empty($cmbUnit)){
						$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit'  and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";
						if(!empty($cmbSubUnit)){
							$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";
						}
					}
					$arrKondisi[] = "q = '$q' ";
				}
		}
			}elseif($cmbBidang != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
			}elseif($cmbUrusan != ''){
				$kondisiSKPD = "and c1='$cmbUrusan'";
			}
	 foreach ($isi as $key => $value) { 
				  $$key = $value; 
			}
	 $Koloms = array();
	
	 $Koloms[] = array('align="center"',$no); 
	// $Koloms[] = array('align="center"',$k.".".$l.".".$m.".".$n.".".$o); 
	 $namaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
	 $Koloms[] = array('align="left"',$namaRekening['nm_rekening']); 
	 $total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($total[0] ,0,',','.')); 
	 $jan = mysql_fetch_array(mysql_query("select sum(alokasi_jan) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($jan[0] ,0,',','.'));
	 $feb = mysql_fetch_array(mysql_query("select sum(alokasi_feb) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($feb[0] ,0,',','.')); 
	 $mar = mysql_fetch_array(mysql_query("select sum(alokasi_mar) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($mar[0] ,0,',','.')); 
	 $triwulan1 = $jan[0] + $feb[0] + $mar[0];
	// $Koloms[] = array('align="right"',number_format($triwulan1 ,2,',','.'));
	 
	 $apr = mysql_fetch_array(mysql_query("select sum(alokasi_apr) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($apr[0] ,0,',','.'));
	 $mei = mysql_fetch_array(mysql_query("select sum(alokasi_mei) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($mei[0] ,0,',','.')); 
	 $jun = mysql_fetch_array(mysql_query("select sum(alokasi_jun) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($jun[0] ,0,',','.')); 
	 $triwulan2 = $apr[0] + $mei[0] + $jun[0];
	// $Koloms[] = array('align="right"',number_format($triwulan2 ,2,',','.'));
	 
	 $jul = mysql_fetch_array(mysql_query("select sum(alokasi_jul) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($jul[0] ,0,',','.'));
	 $agu = mysql_fetch_array(mysql_query("select sum(alokasi_agu) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($agu[0] ,0,',','.')); 
	 $sep = mysql_fetch_array(mysql_query("select sum(alokasi_sep) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($sep[0] ,0,',','.')); 
	 $triwulan3 = $jul[0] + $agu[0] + $sep[0];
	// $Koloms[] = array('align="right"',number_format($triwulan3 ,2,',','.'));
	 
	 $okt = mysql_fetch_array(mysql_query("select sum(alokasi_okt) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($okt[0] ,0,',','.'));
	 $nop = mysql_fetch_array(mysql_query("select sum(alokasi_nop) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($nop[0] ,0,',','.')); 
	 $des = mysql_fetch_array(mysql_query("select sum(alokasi_des) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
	 $Koloms[] = array('align="right"',number_format($des[0] ,0,',','.')); 
	 $triwulan4 = $okt[0] + $nop[0] + $des[0];
	// $Koloms[] = array('align="right"',number_format($triwulan4 ,2,',','.'));
	 
	 
	 if($cmbTriwulan =='1'){
	 	$Koloms = array();
	
		 $Koloms[] = array('align="center"',$no); 
		 $Koloms[] = array('align="center"',$k.".".$l.".".$m.".".$n.".".$o); 
		 $namaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
		 $Koloms[] = array('align="left"',$namaRekening['nm_rekening']); 
		 $total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($total[0] ,0,',','.')); 
		 $jan = mysql_fetch_array(mysql_query("select sum(alokasi_jan) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($jan[0] ,0,',','.'));
		 $feb = mysql_fetch_array(mysql_query("select sum(alokasi_feb) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($feb[0] ,0,',','.')); 
		 $mar = mysql_fetch_array(mysql_query("select sum(alokasi_mar) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($mar[0] ,0,',','.')); 
		 $triwulan1 = $jan[0] + $feb[0] + $mar[0];
		 $Koloms[] = array('align="right"',number_format($triwulan1 ,0,',','.'));
	 }elseif($cmbTriwulan =='2'){
	 	$Koloms = array();
	
		 $Koloms[] = array('align="center"',$no); 
		 $Koloms[] = array('align="center"',$k.".".$l.".".$m.".".$n.".".$o); 
		 $namaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
		 $Koloms[] = array('align="left"',$namaRekening['nm_rekening']); 
		 $total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($total[0] ,0,',','.')); 
		 $apr = mysql_fetch_array(mysql_query("select sum(alokasi_apr) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($apr[0] ,0,',','.'));
		 $mei = mysql_fetch_array(mysql_query("select sum(alokasi_mei) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($mei[0] ,0,',','.')); 
		 $jun = mysql_fetch_array(mysql_query("select sum(alokasi_jun) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($jun[0] ,0,',','.')); 
		 $triwulan2 = $apr[0] + $mei[0] + $jun[0];
		 $Koloms[] = array('align="right"',number_format($triwulan2 ,0,',','.'));
	 }elseif($cmbTriwulan =='3'){
	 	$Koloms = array();
	
		 $Koloms[] = array('align="center"',$no); 
		 $Koloms[] = array('align="center"',$k.".".$l.".".$m.".".$n.".".$o); 
		 $namaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
		 $Koloms[] = array('align="left"',$namaRekening['nm_rekening']); 
		 $total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($total[0] ,0,',','.')); 
		 $jul = mysql_fetch_array(mysql_query("select sum(alokasi_jul) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($jul[0] ,0,',','.'));
		 $agu = mysql_fetch_array(mysql_query("select sum(alokasi_agu) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($agu[0] ,0,',','.')); 
		 $sep = mysql_fetch_array(mysql_query("select sum(alokasi_sep) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($sep[0] ,0,',','.')); 
		 $triwulan3 = $jul[0] + $agu[0] + $sep[0];
		 $Koloms[] = array('align="right"',number_format($triwulan3 ,0,',','.'));
	 }elseif($cmbTriwulan =='4'){
	 	$Koloms = array();
	
		 $Koloms[] = array('align="center"',$no); 
		 $Koloms[] = array('align="center"',$k.".".$l.".".$m.".".$n.".".$o); 
		 $namaRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
		 $Koloms[] = array('align="left"',$namaRekening['nm_rekening']); 
		 $total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($total[0] ,0,',','.')); 
		 $okt = mysql_fetch_array(mysql_query("select sum(alokasi_okt) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($okt[0] ,0,',','.'));
		 $nop = mysql_fetch_array(mysql_query("select sum(alokasi_nop) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($nop[0] ,0,',','.')); 
		 $des = mysql_fetch_array(mysql_query("select sum(alokasi_des) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o' $kondisiSKPD and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		 $Koloms[] = array('align="right"',number_format($des[0] ,0,',','.')); 
		 $triwulan4 = $okt[0] + $nop[0] + $des[0];
		 $Koloms[] = array('align="right"',number_format($triwulan4 ,0,',','.'));
	 }
	 

	 return $Koloms;
	}
	


	function genDaftarOpsi(){

	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');
$selectedC1 = $_REQUEST['cmbUrusan'];
	$selectedC  = $_REQUEST['cmbBidang'];
	$selectedD = $_REQUEST['cmbSKPD'];
	$selectedE = $_REQUEST['cmbUnit'];
	$selectedE1 = $_REQUEST['cmbSubUnit'];
	
	
	
	
	if(!isset($selectedC1) ){
	   		$arrayData = mysql_fetch_array(mysql_query("select * from current_filter where username ='".$_COOKIE['coID']."'"));
			foreach ($arrayData as $key => $value) { 
			  $$key = $value; 
			 }
			 if($CurrentQ !='' ){
			 	$_REQUEST['p'] = $CurrentBK.".".$CurrentCK.".".$CurrentP;
				$_REQUEST['q'] =  $CurrentQ;
			 	$selectedE1 = $CurrentSubUnit;
			 	$selectedE = $CurrentUnit;
				$selectedD = $CurrentSKPD;
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
				
			}elseif($CurrentP !='' ){
			 	$_REQUEST['p'] = $CurrentBK.".".$CurrentCK.".".$CurrentP;
			 	$selectedE1 = $CurrentSubUnit;
			 	$selectedE = $CurrentUnit;
				$selectedD = $CurrentSKPD;
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
				
			}elseif($CurrentSubUnit !='000' ){
			 	$selectedE1 = $CurrentSubUnit;
			 	$selectedE = $CurrentUnit;
				$selectedD = $CurrentSKPD;
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
				
			}elseif($CurrentUnit !='00' ){
			 	$selectedE = $CurrentUnit;
				$selectedD = $CurrentSKPD;
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
				
			}elseif($CurrentSKPD !='00' ){
				$selectedD = $CurrentSKPD;
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
				
			}elseif($CurrentBidang !='00'){
				$selectedC = $CurrentBidang;
				$selectedC1 = $CurrentUrusan;
	
			}elseif($CurrentUrusan !='0'){
				$selectedC1 = $CurrentUrusan;
			}
	   }
	
	$arrayProgram = explode(".",$_REQUEST['p']);
	$selectedBK = $arrayProgram[0];
	$selectedCK = $arrayProgram[1];
	$selectedP = $arrayProgram[2];
	$selectedQ = $_REQUEST['q'];
	
	foreach ($_COOKIE as $key => $value) { 
				  $$key = $value; 
			}
	
	
		if($VulnWalkerSubUnit != '000'){
			$selectedE1 = $VulnWalkerSubUnit;
			$selectedE = $VulnWalkerUnit;
			$selectedD = $VulnWalkerSKPD;
			$selectedC = $VulnWalkerBidang;
			$selectedC1 = $VulnWalkerUrusan;
		}elseif($VulnWalkerUnit != '00'){
			$selectedE = $VulnWalkerUnit;
			$selectedD = $VulnWalkerSKPD;
			$selectedC = $VulnWalkerBidang;
			$selectedC1 = $VulnWalkerUrusan;
		}elseif($VulnWalkerSKPD != '00'){
			$selectedD = $VulnWalkerSKPD;
			$selectedC = $VulnWalkerBidang;
			$selectedC1 = $VulnWalkerUrusan;
		}elseif($VulnWalkerBidang != '00'){
			$selectedC = $VulnWalkerBidang;
			$selectedC1 = $VulnWalkerUrusan;
		}elseif($VulnWalkerUrusan != '0'){
			$selectedC1 = $VulnWalkerUrusan;
		}
		
		

		$codeAndNameUrusan = "select c1, concat(c1, '. ', nm_skpd) from ref_skpd where c='00' and d='00' and e='00' and e1='000' ";
		$urusan = cmbQuery('cmbUrusan',$selectedC1,$codeAndNameUrusan,'onchange=alokasiDpa.refreshList(true);','-- URUSAN --');
		
		$codeAndNameBidang = "select c, concat(c, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c !='00' and d='00' and e='00' and e1='000' ";
		$bidang = cmbQuery('cmbBidang',$selectedC,$codeAndNameBidang,'onchange=alokasiDpa.refreshList(true);','-- BIDANG --');
		
		$codeAndNameSKPD = "select d, concat(d, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d!='00' and e='00' and e1='000' ";
		$skpd= cmbQuery('cmbSKPD',$selectedD,$codeAndNameSKPD,'onchange=alokasiDpa.refreshList(true);','-- SKPD --');
		
		$codeAndNameUnit = "select e, concat(e, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e!='00' and e1='000' ";
		$unit = cmbQuery('cmbUnit',$selectedE,$codeAndNameUnit,'onchange=alokasiDpa.refreshList(true);','-- UNIT --');
		
		
		$codeAndNameSubUnit = "select e1, concat(e1, '. ', nm_skpd) from ref_skpd where c1='$selectedC1' and c='$selectedC' and d='$selectedD' and e='$selectedE' and e1!='000' ";
		$subunit = cmbQuery('cmbSubUnit',$selectedE1,$codeAndNameSubUnit,'onchange=alokasiDpa.refreshList(true);','-- SUB UNIT --');

	
	
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran) as max from view_rkbmd "));
	$maxID = $get1['max'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_rkbmd where id_anggaran = '$maxID' "));
	$nomorUrutSebelumnya =  $get2['no_urut'];

	
	
	
	
	
	
	
	
	
	
	
	
	
	/*$codeAndNameProgram = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p as pFromProgram, tabel_anggaran.q  , ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD' and tabel_anggaran.e = '$selectedE' and tabel_anggaran.e1 = '$selectedE1' and tabel_anggaran.q='0'  ");
	if(!empty($selectedD) && empty($selectedE) ){*/
		$codeAndNameProgram = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p as pFromProgram, tabel_anggaran.q  , ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD'  and tabel_anggaran.q='0'  ");
	//}
	$pSama = "";
	$arrayP = array() ;
	while($rows = mysql_fetch_array($codeAndNameProgram)){
		foreach ($rows as $key => $value) { 
				  $$key = $value; 
		}
		
			$concat = $bk.".".$ck.".".$pFromProgram ;
			if($concat != ".."){
				if($concat == $pSama){		
				}else{
					array_push($arrayP,
				 	  array($concat,$nama  )
					);
				}
			}
		
		
		
		
		
		
		$pSama = $concat;		
	}
	
	$program = "<input type='hidden' id='bk' name='bk' value='$selectedBK'> <input type='hidden' id='ck' name='ck' value='$selectedCK'> <input type='hidden' id='hiddenP' name='hiddenP' value='$selectedP'>".cmbArray('p',$_REQUEST['p'],$arrayP,'-- PROGRAM --','onchange=alokasiDpa.programChanged();');
	
	/*$codeAndNameKegiatan = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p, tabel_anggaran.q, ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD' and tabel_anggaran.e = '$selectedE' and tabel_anggaran.e1 = '$selectedE1' and tabel_anggaran.q !='0' and tabel_anggaran.bk='$selectedBK' and tabel_anggaran.ck='$selectedCK' and tabel_anggaran.p='$selectedP'  ");
	if(!empty($selectedD) && empty($selectedE) ){*/
		$codeAndNameKegiatan = mysql_query("select tabel_anggaran.bk, tabel_anggaran.ck, tabel_anggaran.p, tabel_anggaran.q, ref_program.nama from tabel_anggaran  inner join ref_program on tabel_anggaran.bk = ref_program.bk and tabel_anggaran.ck = ref_program.ck and tabel_anggaran.p = ref_program.p and tabel_anggaran.q = ref_program.q  inner join ref_tahap_anggaran on tabel_anggaran.id_tahap = ref_tahap_anggaran.id_tahap where tabel_anggaran.dk='0' and ref_tahap_anggaran.no_urut = '$nomorUrutSebelumnya' and tabel_anggaran.tahun ='$this->tahun' and tabel_anggaran.jenis_anggaran = '$this->jenisAnggaran' and tabel_anggaran.c1 = '$selectedC1' and tabel_anggaran.c = '$selectedC' and tabel_anggaran.d = '$selectedD' and tabel_anggaran.q !='0' and tabel_anggaran.bk='$selectedBK' and tabel_anggaran.ck='$selectedCK' and tabel_anggaran.p='$selectedP'  ");
	
	//}
	$qSama = "";
	$arrayQ = array() ;
	while($rows = mysql_fetch_array($codeAndNameKegiatan)){
		foreach ($rows as $key => $value) { 
				  $$key = $value; 
		}
		if($q != 0) {
			if($q == $qSama){		
			}else{
				array_push($arrayQ,
				   array($q,$nama)
				);
			}
		}
		
		
		
		
		$qSama = $q;		
	}
	
	$kegiatan = cmbArray('q',$_REQUEST['q'],$arrayQ,'-- KEGIATAN --','onchange=alokasiDpa.refreshList(true);');
	
	
	$arrayTriwulan = array(
							 array('1',"I"),
							 array('2',"II"),
							 array('3',"III"),
							 array('4',"IV"),
							);
	/*if(empty($_REQUEST['cmbTriwulan'])){
		$_REQUEST['cmbTriwulan'] == '1';
	}*/
	$cmbTriwulan = cmbArray('cmbTriwulan',$_REQUEST['cmbTriwulan'],$arrayTriwulan,'-- TRIWULAN --','onchange=alokasiDpa.refreshList(true);');
	$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			
			<tr>
			<td>URUSAN </td>
			<td>:</td>
			<td style='width:86%;'> 
			".$urusan."
			</td>
			</tr>
			<tr>
			<td>BIDANG</td>
			<td>:</td>
			<td style='width:86%;'>
			".$bidang."
			</td>
			</tr>
			<tr>
			<td>SKPD</td>
			<td>:</td>
			<td style='width:86%;'>
			".$skpd."
			</td>
			</tr>
			
			
			
			
			
			</table>".
			"</div>"."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>PROGRAM</td>
			<td>:</td>
			<td style='width:86%;'>
			<input type='hidden' name='tahun' id='tahun' value='$this->tahun' style='width:40px;' > <input type='hidden' name ='cmbJenisRKA' id='cmbJenisRKA' value='2.2.1'>
			".$program."
			</td>
			</tr>
			<tr>
			<td>KEGIATAN</td>
			<td>:</td>
			<td style='width:86%;'>
			".$kegiatan."
			</td>
			</tr>
			</table>".
			"</div>"
			."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>TRIWULAN</td>
			<td>:</td>
			<td style='width:86%;'>
			".$cmbTriwulan."
			</td>
			</tr>
			
			</table>".
			"</div>"
			;
	

		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 

		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		

		foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 }
			
		
		if(isset($q)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  "CurrentSKPD" => $cmbSKPD,
					   "CurrentUnit" => $cmbUnit,
					    "CurrentSubUnit" => $cmbSubUnit,
						"CurrentBK" => $bk,
						"CurrentCK" => $ck,
						"CurrentP" => $hiddenP,
						"CurrentQ" => $q,
					  
					  );
		}elseif(isset($hiddenP)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  "CurrentSKPD" => $cmbSKPD,
					   "CurrentUnit" => $cmbUnit,
					    "CurrentSubUnit" => $cmbSubUnit,
						"CurrentBK" => $bk,
						"CurrentCK" => $ck,
						"CurrentP" => $hiddenP,
					  
					  );
		}elseif(isset($cmbSubUnit)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  "CurrentSKPD" => $cmbSKPD,
					   "CurrentUnit" => $cmbUnit,
					    "CurrentSubUnit" => $cmbSubUnit,
					  
					  );
		}elseif(isset($cmbUnit)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  "CurrentSKPD" => $cmbSKPD,
					   "CurrentUnit" => $cmbUnit,
					  
					  );
		}elseif(isset($cmbSKPD)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  "CurrentSKPD" => $cmbSKPD,
					  
					  );
		}elseif(isset($cmbBidang)){
			$data = array("CurrentUrusan" => $cmbUrusan,
					  "CurrentBidang" => $cmbBidang,
					  
					  );
		}elseif(isset($cmbUrusan)){
			$data = array("CurrentUrusan" => $cmbUrusan
			
			 );
		}
		
		mysql_query(VulnWalkerUpdate("current_filter",$data,"username='$this->username'"));
		
		if(!isset($cmbUrusan) ){
	   		$arrayData = mysql_fetch_array(mysql_query("select * from current_filter where username ='".$_COOKIE['coID']."'"));
			foreach ($arrayData as $key => $value) { 
			  $$key = $value; 
			 }
			 if($CurrentQ !='' ){
			 	$_REQUEST['q'] = $CurrentQ;
			 	$_REQUEST['hiddenP'] = $CurrentP;
				$_REQUEST['bk'] = $CurrentBK;
				$_REQUEST['ck'] = $CurrentCK;
				$selectedQ =  $CurrentQ;
			 	$cmbSubUnit = $CurrentSubUnit;
			 	$cmbUnit = $CurrentUnit;
				$cmbSKPD = $CurrentSKPD;
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
				
			}elseif($CurrentP !='' ){
			 	$_REQUEST['hiddenP'] = $CurrentP;
				$_REQUEST['bk'] = $CurrentBK;
				$_REQUEST['ck'] = $CurrentCK;
			 	$cmbSubUnit = $CurrentSubUnit;
			 	$cmbUnit = $CurrentUnit;
				$cmbSKPD = $CurrentSKPD;
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
				
			}elseif($CurrentSubUnit !='000' ){
			 	$cmbSubUnit = $CurrentSubUnit;
			 	$cmbUnit = $CurrentUnit;
				$cmbSKPD = $CurrentSKPD;
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
				
			}elseif($CurrentUnit !='00' ){
			 	$cmbUnit = $CurrentUnit;
				$cmbSKPD = $CurrentSKPD;
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
				
			}elseif($CurrentSKPD !='00' ){
				$cmbSKPD = $CurrentSKPD;
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
				
			}elseif($CurrentBidang !='00'){
				$cmbBidang = $CurrentBidang;
				$cmbUrusan = $CurrentUrusan;
	
			}elseif($CurrentUrusan !='0'){
				$cmbUrusan = $CurrentUrusan;
			}
	   }
	   
	   if(isset($cmbUrusan) && $cmbUrusan== ''){
	   		$cmbBidang = "";
			$cmbSKPD = "";
			$hiddenP = "";
			$cmbUnit = "";
			$cmbSubUnit = "";
	   }elseif(isset($cmbBidang) && $cmbBidang== ''){
			$cmbBidang = "";
			$cmbSKPD = "";
			$hiddenP = "";
			$cmbUnit = "";
			$cmbSubUnit = "";
	   }elseif(isset($cmbSKPD) && $cmbSKPD== ''){
			$hiddenP = "";
			$cmbSKPD = "";
			$cmbUnit = "";
			$cmbSubUnit = "";
			$q = "";
			 $_REQUEST['bk'] = "";
			 $_REQUEST['ck'] = "";
			 $ck = "";
			 $bk = "";
			 
			 
			/*if(isset($hiddenP) && $hiddenP == ''){
			   		
			 }*/
	   }elseif(isset($cmbUnit) && $cmbUnit== ''){
			$cmbSubUnit = "";
	   }
	   
	   
		
		
		 if($cmbSubUnit != ''){
			$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit'";
			

			}elseif($cmbUnit != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' ";
			}elseif($cmbSKPD != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  ";
				if(!empty($hiddenP)){
					$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
					if(!empty($cmbUnit)){
						$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit'  and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
						if(!empty($cmbSubUnit)){
							$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' $kondisiRekening";
						
						
						}
					}
		if(!empty($q)){
					$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD'  and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";	
					if(!empty($cmbUnit)){
						$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit'  and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";
						if(!empty($cmbSubUnit)){
							$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' and bk='$bk' and ck='$ck' and p='$hiddenP' and q='$q' $kondisiRekening";
						}
					}
				}
		}
			}elseif($cmbBidang != ''){
				$kondisiSKPD = "and c1='$cmbUrusan' and c='$cmbBidang'  ";
			}elseif($cmbUrusan != ''){
				$kondisiSKPD = "and c1='$cmbUrusan'";
			}
		
		
		
		$hublaBK = $_REQUEST['bk'];
		$hublaCK = $_REQUEST['ck'];
		$hublaP = $_REQUEST['hiddenP'];
		$hublaQ = $_REQUEST['q'];

		$this->pagePerHal=$fmLimit;
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "anggaran = '$this->jenisAnggaran'";
		
		
		$grabAllSPD = mysql_query("select * from tabel_spd where tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran' group by k,l,m,n,o");
		while($rows = mysql_fetch_array($grabAllSPD)){
			foreach ($rows as $key => $value) { 
				  $$key = $value; 
			 }
			 $concat = $k.".".$l.".".$m.".".$n.".".$o;
			 if(mysql_num_rows(mysql_query("select * from tabel_spd where tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran' and concat(k,'.',l,'.',m,'.',n,'.',o) ='$concat' $kondisiSKPD")) == 0){
				
				$arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) !='$concat'";			 	
			 }
		}

		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		$Kondisi = $Kondisi." group by k,l,m,n,o";
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " id $Asc1 " ;
			
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
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
		$cmbUnit = $_REQUEST['cmbUnit'];
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order , 'NoAwal'=>$NoAwal);
		
	}


function Laporan($xls =FALSE){
		global $Main;
		
	
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		
		
		

		
		
	
		$arrKondisi[] = "tahun = '$this->tahun'";
		$arrKondisi[] = "anggaran = '$this->jenisAnggaran'";
		
		$Kondisi= join(' and ',$arrKondisi);
		/*if(sizeof($arrKondisi) == 0){
			$Kondisi= '';
		}else{
			$Kondisi = " and ".$Kondisi;
		}*/
		$qry ="select * from tabel_spd where $Kondisi group by k,l,m,n,o";
		$aqry = mysql_query($qry);
		$getKuasapenggunaBarang = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1'"));
		$kuasaPenggunaBarang = $getKuasapenggunaBarang['nm_skpd'];		
		
		
		//Get Id Awal Renja
		
		$getIdRenja = mysql_fetch_array(mysql_query("select min(id_anggaran) from view_renja where c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and bk='$hublaBK' and ck='$hublaCK' and p='$hiddenP' and q='$hublaQ' and tahun='$this->tahun' and jenis_anggaran ='$this->jenisAnggaran' "));
		$idRenja = $getIdRenja['min(id_anggaran)'];
		$getDetailRenja = mysql_fetch_array(mysql_query("select * from detail_renja where id_anggaran = '$idRenja'"));
		$getUrusan = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$cmbUrusan' and c='00'"));
		$urusan = $getUrusan['nm_skpd'];
		$getBidang = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$cmbUrusan' and c='$cmbBidang' and d='00'"));
		$bidang = $getBidang['nm_skpd'];
		$getSKPD = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='00'"));
		$skpd = $getSKPD['nm_skpd'];
		$getSubUnit = mysql_fetch_array(mysql_query("select * from ref_skpd where c1='$cmbUrusan' and c='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit' "));
		$subUnit = $getSubUnit['nm_skpd'];
		$getProgram = mysql_fetch_array(mysql_query("select * from ref_program where bk='$hublaBK' and ck='$hublaCK' and dk='0' and p='$hublaP' and q='0'"));
		$program = $getProgram['nama'];
		$getKegiatan = mysql_fetch_array(mysql_query("select * from ref_program where bk='$hublaBK' and ck='$hublaCK' and dk='0' and p='$hublaP' and q='$hublaQ'"));
		$kegiatan = $getKegiatan['nama'];
				
		
		//
				
		//MULAI Halaman Laporan ------------------------------------------------------------------------------------------ 
		$css = $xls	? "<style>.nfmt5 {mso-number-format:'\@';}</style>":"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
					<style>
						.ukurantulisan{
							font-size:17px;
						}
						.ukurantulisan1{
							font-size:20px;
						}
						.ukurantulisanIdPenerimaan{
							font-size:16px;
						}
					</style>
				</head>".
			"<body >
				<div style='width:$this->Cetak_WIDTH_Landscape;'>
					<table class=\"rangkacetak\" style='width:33cm;font-family:Times New Roman;margin-left:2cm;margin-top:2cm;'>
						<tr>
							<td valign=\"top\"> <div style='text-align:center;'>
				<span style='font-size:18px;font-weight:bold;text-decoration: '>
					ALOKASI DPA
				</span><br>
				<span style='font-size:14px;font-weight:text-decoration: '>
					PROVINSI/Kabupaten/Kota $this->kota<br>
					Tahun Anggaran $this->tahun 
					<br>
				</span><br>
			
				
				
				<br>
				";
				
				$kolomHeader ="
								<tr>
										<th class='th01' rowspan='2' >No.</th>
										<th class='th01' rowspan='2' >NAMA REKENING</th>
										<th class='th01' rowspan='2' >JUMLAH (Rp)</th>
										<th class='th02' rowspan='1' colspan='3' >TRIWULAN I</th>
										<th class='th02' rowspan='1' colspan='3' >TRIWULAN II</th>
										<th class='th02' rowspan='1' colspan='3' >TRIWULAN III</th>
										<th class='th02' rowspan='1' colspan='3' >TRIWULAN IV</th>
										
								</tr>
								<tr>
										<th class='th01' rowspan='1' >JAN</th>
										<th class='th01' rowspan='1'>FEB</th>
										<th class='th01' rowspan='1' >MAR</th>
										
										<th class='th01' rowspan='1' >APR</th>
										<th class='th01' rowspan='1'>MEI</th>
										<th class='th01' rowspan='1' >JUN</th>
										
										<th class='th01' rowspan='1' >JUL</th>
										<th class='th01' rowspan='1'>AGU</th>
										<th class='th01' rowspan='1' >SEP</th>
										
										<th class='th01' rowspan='1' >OKT</th>
										<th class='th01' rowspan='1'>NOP</th>
										<th class='th01' rowspan='1' >DES</th>
								</tr>
								
						";
				
		if($_COOKIE['triwulan'] == '1'){
			$kolomHeader = "	<tr>	<th class='th01' rowspan='2' >No.</th>
										<th class='th01' rowspan='2' colspan='5' width='50' >KODE REKENING</th>
										<th class='th01' rowspan='2' >NAMA REKENING</th>
										<th class='th01' rowspan='2' >JUMLAH (Rp)</th>
										<th class='th02' rowspan='1' colspan='4' >TRIWULAN I</th>
										
								</tr>
								<tr>
										<th class='th01' rowspan='1' >JAN</th>
										<th class='th01' rowspan='1'>FEB</th>
										<th class='th01' rowspan='1' >MAR</th>
										<th class='th01' rowspan='1' >JML</th>
								</tr>
								";
		}elseif($_COOKIE['triwulan'] == '2'){
			$kolomHeader = "	<tr>
										<th class='th01' rowspan='2' >No.</th>
										<th class='th01' rowspan='2' colspan='5' width='50' >KODE REKENING</th>
										<th class='th01' rowspan='2' >NAMA REKENING</th>
										<th class='th01' rowspan='2' >JUMLAH (Rp)</th>
										<th class='th02' rowspan='1' colspan='4' >TRIWULAN II</th>
										
								</tr>
								<tr>
										
										<th class='th01' rowspan='1' >APR</th>
										<th class='th01' rowspan='1'>MEI</th>
										<th class='th01' rowspan='1' >JUN</th>
										<th class='th01' rowspan='1' >JML</th>
								</tr>
								";
		}elseif($_COOKIE['triwulan'] == '3'){
			$kolomHeader = "	<tr>
										<th class='th01' rowspan='2' >No.</th>
										<th class='th01' rowspan='2' colspan='5' width='50' >KODE REKENING</th>
										<th class='th01' rowspan='2' >NAMA REKENING</th>
										<th class='th01' rowspan='2' >JUMLAH (Rp)</th>
										<th class='th02' rowspan='1' colspan='4' >TRIWULAN III</th>
										
								</tr>
								<tr>
										<th class='th01' rowspan='1' >JUL</th>
										<th class='th01' rowspan='1'>AGU</th>
										<th class='th01' rowspan='1' >SEP</th>
										<th class='th01' rowspan='1' >JML</th>
								</tr>
								";
		}elseif($_COOKIE['triwulan'] == '4'){
			$kolomHeader = "	<tr>
										<th class='th01' rowspan='2' >No.</th>
										<th class='th01' rowspan='2' colspan='5' width='50' >KODE REKENING</th>
										<th class='th01' rowspan='2' >NAMA REKENING</th>
										<th class='th01' rowspan='2' >JUMLAH (Rp)</th>
										<th class='th02' rowspan='1' colspan='4' >TRIWULAN IV</th>
										
								</tr>
								<tr>
										<th class='th01' rowspan='1' >OKT</th>
										<th class='th01' rowspan='1'>NOP</th>
										<th class='th01' rowspan='1' >DES</th>
										<th class='th01' rowspan='1' >JML</th>
								</tr>
								";
		}
		
				
		echo "
				
								<table table width='100%' class='cetak' border='1' style='margin:4 0 0 0;width:100%;'>
									$kolomHeader
									
								
									
		";
		/*$getTotal = mysql_fetch_array(mysql_query("select sum(jumlah_harga) from view_dpa_2_2_1 where $Kondisi  "));
		$total = number_format($getTotal['sum(jumlah_harga)'],2,',','.');*/
		$no = 1;
		while($daqry = mysql_fetch_array($aqry)){
			foreach ($daqry as $key => $value) { 
				  $$key = $value; 
			} 
			
			$nm_rekening = mysql_fetch_array(mysql_query("select * from ref_rekening where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'"));
			$nm_rekening = $nm_rekening['nm_rekening'];
			$total = mysql_fetch_array(mysql_query("select sum(total) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $jan = mysql_fetch_array(mysql_query("select sum(alokasi_jan) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $feb = mysql_fetch_array(mysql_query("select sum(alokasi_feb) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $mar = mysql_fetch_array(mysql_query("select sum(alokasi_mar) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $triwulan1 = $jan[0] + $feb[0] + $mar[0];
		   
		   $apr = mysql_fetch_array(mysql_query("select sum(alokasi_apr) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $mei = mysql_fetch_array(mysql_query("select sum(alokasi_mei) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $jun = mysql_fetch_array(mysql_query("select sum(alokasi_jun) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $triwulan2 = $apr[0] + $mei[0] + $jun[0];
		   
		   $jul = mysql_fetch_array(mysql_query("select sum(alokasi_jul) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $agu = mysql_fetch_array(mysql_query("select sum(alokasi_agu) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $sep = mysql_fetch_array(mysql_query("select sum(alokasi_sep) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $triwulan3 = $jul[0] + $agu[0] + $sep[0];
		   
		   $okt = mysql_fetch_array(mysql_query("select sum(alokasi_okt) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $nop = mysql_fetch_array(mysql_query("select sum(alokasi_nop) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'"));
		   $des = mysql_fetch_array(mysql_query("select sum(alokasi_des) from $this->TblName where k='$k' and l='$l' and m='$m' and n='$n' and o ='$o'  and tahun ='$this->tahun' and anggaran ='$this->jenisAnggaran'")); 
		   $triwulan4 = $okt[0] + $nop[0] + $des[0];
			
			if($_COOKIE['triwulan'] == '1'){
				echo "
									<tr valign='top'>
										<td align='center' class='GarisCetak' >".$no."</td>
										<td align='center' class='GarisCetak' >".$k."</td>
										<td align='center' class='GarisCetak' >".$l."</td>
										<td align='center' class='GarisCetak' >".$m."</td>
										<td align='center' class='GarisCetak' >".$n."</td>
										<td align='center' class='GarisCetak' >".$o."</td>
										<td align='left' class='GarisCetak' >".$nm_rekening."</td>
										<td align='right' class='GarisCetak' >".number_format($total[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jan[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($feb[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($mar[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($triwulan1 ,0,',','.')."</td>
									</tr>
					";
			
			}elseif($_COOKIE['triwulan'] == '2'){
				echo "
									<tr valign='top'>
										<td align='center' class='GarisCetak' >".$no."</td>
										<td align='center' class='GarisCetak' >".$k."</td>
										<td align='center' class='GarisCetak' >".$l."</td>
										<td align='center' class='GarisCetak' >".$m."</td>
										<td align='center' class='GarisCetak' >".$n."</td>
										<td align='center' class='GarisCetak' >".$o."</td>
										<td align='left' class='GarisCetak' >".$nm_rekening."</td>
										<td align='right' class='GarisCetak' >".number_format($total[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($apr[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($mei[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jun[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($triwulan2 ,0,',','.')."</td>
									</tr>
					";
			
			}elseif($_COOKIE['triwulan'] == '3'){
				echo "
									<tr valign='top'>
										<td align='center' class='GarisCetak' >".$no."</td>
										<td align='center' class='GarisCetak' >".$k."</td>
										<td align='center' class='GarisCetak' >".$l."</td>
										<td align='center' class='GarisCetak' >".$m."</td>
										<td align='center' class='GarisCetak' >".$n."</td>
										<td align='center' class='GarisCetak' >".$o."</td>
										<td align='left' class='GarisCetak' >".$nm_rekening."</td>
										<td align='right' class='GarisCetak' >".number_format($total[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jul[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($agu[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($sep[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($triwulan3 ,0,',','.')."</td>
									</tr>
					";
			
			}elseif($_COOKIE['triwulan'] == '4'){
				echo "
									<tr valign='top'>
										<td align='center' class='GarisCetak' >".$no."</td>
										<td align='center' class='GarisCetak' >".$k."</td>
										<td align='center' class='GarisCetak' >".$l."</td>
										<td align='center' class='GarisCetak' >".$m."</td>
										<td align='center' class='GarisCetak' >".$n."</td>
										<td align='center' class='GarisCetak' >".$o."</td>
										<td align='left' class='GarisCetak' >".$nm_rekening."</td>
										<td align='right' class='GarisCetak' >".number_format($total[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($okt[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($nop[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($des[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($triwulan4 ,0,',','.')."</td>
									</tr>
					";
			
			}else{
				echo "
						<tr valign='top'>
										<td align='center' class='GarisCetak' >".$no."</td>
										<td align='left' class='GarisCetak' >".$nm_rekening."</td>
										<td align='right' class='GarisCetak' >".number_format($total[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jan[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($feb[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($mar[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($apr[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($mei[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jun[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($jul[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($agu[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($sep[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($okt[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($nop[0] ,0,',','.')."</td>
										<td align='right' class='GarisCetak' >".number_format($des[0] ,0,',','.')."</td>
									</tr>
				
				";
			}
					
	
			$no += 1;
			
			
			
		}
		/*echo 				"<tr valign='top'>
									<td align='right' colspan='9' class='GarisCetak'>Jumlah</td>
									<td align='right' class='GarisCetak' ><b>".$total."</b></td>
									
								</tr>
							 </table>";		*/
		
		
	}
	

}
$alokasiDpa = new alokasiDpaObj();
$arrayResult = VulnWalkerTahap_v2($alokasiDpa->modul);
$jenisForm = $arrayResult['jenisForm'];
$nomorUrut = $arrayResult['nomorUrut'];
$tahun = $arrayResult['tahun'];
$jenisAnggaran = $arrayResult['jenisAnggaran'];
$idTahap = $arrayResult['idTahap'];

$alokasiDpa->jenisForm = $jenisForm;
$alokasiDpa->nomorUrut = $nomorUrut;
$alokasiDpa->urutTerakhir = $nomorUrut;
$alokasiDpa->tahun = $tahun;
$alokasiDpa->jenisAnggaran = $jenisAnggaran;
$alokasiDpa->idTahap = $idTahap;

$alokasiDpa->username = $_COOKIE['coID'];


if(empty($alokasiDpa->tahun)){
    
	$get1 = mysql_fetch_array(mysql_query("select max(id_anggaran)  from view_dpa_2_2_1 "));
	$maxAnggaran = $get1['max(id_anggaran)'];
	$get2 = mysql_fetch_array(mysql_query("select * from view_dpa_2_2_1 where id_anggaran = '$maxAnggaran'"));
	/*$alokasiDpa->tahun = "select max(id_anggaran) as max from view_dpa_2_2_1 where nama_modul = 'alokasiDpa'";*/
	$alokasiDpa->tahun  = $get2['tahun'];
	$alokasiDpa->jenisAnggaran = $get2['jenis_anggaran'];
	$alokasiDpa->urutTerakhir = $get2['no_urut'];
	$alokasiDpa->jenisFormTerakhir = $get2['jenis_form_modul'];
	$alokasiDpa->urutSebelumnya = $alokasiDpa->urutTerakhir - 1;
	
	
	$idtahapTerakhir = $get2['id_tahap'];
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$idtahapTerakhir'"));
	$alokasiDpa->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$alokasiDpa->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
	
	$arrayHasil =  VulnWalkerLASTTahap_v2();
	$alokasiDpa->currentTahap = $arrayHasil['currentTahap'];
}else{
	$getCurrenttahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$alokasiDpa->idTahap'"));
	$alokasiDpa->currentTahap = $getCurrenttahap['nama_tahap'];
	
	$namaTahap = mysql_fetch_array(mysql_query("select * from ref_tahap_anggaran where id_tahap = '$alokasiDpa->idTahap'"));
	$alokasiDpa->jenisFormTerakhir =  $namaTahap['jenis_form_modul'];
	$alokasiDpa->namaTahapTerakhir = $namaTahap['nama_tahap'];
	$arrayMasa = explode("-",$namaTahap['tanggal_mulai']);
	$lastTanggalMulai = $arrayMasa[2]."-".$arrayMasa[1]."-".$arrayMasa[0];
	$arrayMasa2 = explode("-",$namaTahap['tanggal_selesai']);
	$lastTanggalSelesai = $arrayMasa2[2]."-".$arrayMasa2[1]."-".$arrayMasa2[0];
	$alokasiDpa->masaTerakhir = $lastTanggalMulai." JAM : ".$namaTahap['jam_mulai']."  s/d  ".$lastTanggalSelesai." JAM : ".$namaTahap['jam_selesai'];
}

$setting = settinganPerencanaan_v2();
$alokasiDpa->provinsi = $setting['provinsi'];
$alokasiDpa->kota = $setting['kota'];
$alokasiDpa->pengelolaBarang = $setting['pengelolaBarang'];
$alokasiDpa->pejabatPengelolaBarang = $setting['pejabat'];
$alokasiDpa->pengurusPengelolaBarang = $setting['pengurus'];
$alokasiDpa->nipPengelola = $setting['nipPengelola'];
$alokasiDpa->nipPengurus = $setting['nipPengurus'];
$alokasiDpa->nipPejabat = $setting['nipPejabat'];

?>