<?php

class rekappemanfaatan_skpdObj  extends DaftarObj2{	
	var $Prefix = 'rekappemanfaatan_skpd';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pemanfaatan'; //daftar
	var $TblName_Hapus = 'pemanfaatan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d');
	var $FieldSum = array('jml_rencana_a','jml_realisasi_a','jml_selisih_a',
						  'jml_rencana_b','jml_realisasi_b','jml_selisih_b',
						  'jml_rencana_c','jml_realisasi_c','jml_selisih_c',
						  'jml_rencana_d','jml_realisasi_d','jml_selisih_d',
						  'jml_rencana_e','jml_realisasi_e','jml_selisih_e',
						  'jml_rencana_f','jml_realisasi_f','jml_selisih_f',
						  'jml_rencana_g','jml_realisasi_g','jml_selisih_g',
						  'tot_rencana','tot_realisasi','tot_selisih');//array('jml_harga');
	var $fieldSum_lokasi = array( 4);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Pemanfaatan';
	var $PageIcon = 'images/pemanfaatan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='REKAP_PEMANFAATAN_SKPD.xls';
	var $Cetak_Judul = 'REKAP PEMANFAATAN BARANG MILIK DAERAH SKPD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $FormName = 'rekappemanfaatan_skpdForm'; 	
			
	function setTitle(){
		return 'Rekap Daftar Pemanfaatan Barang Milik Daerah (SKPD)';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".rincian()","edit_f2.png","Rincian", 'Rincian')."</td>";
	}
	function setMenuView(){
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel")."</td>";
	}
	
	
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  
		case 'BidangAfter':{
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter()','--- Pilih SKPD ---','00');
			break;
		    }
		case 'SKPDAfter':{
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			$fmSKPDskpd = cekPOST('fmSKPDskpd');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT',$fmSKPDskpd);
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSUBUNIT','000');
		break;
			}		
			   
	   case 'subtitle':{		
					$content = $this->setTopBar();
					$json=TRUE;
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
		 "<script type='text/javascript' src='js/pemanfaatan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
	

	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
	  	   <th class='th01' width='5' rowspan='2'>No.</th>
	  	   $Checkbox
		   <th class='th01' rowspan='2'>SKPD</th>
		   <th class='th02' colspan='3'>KIB A</th>
		   <th class='th02' colspan='3'>KIB B</th>
		   <th class='th02' colspan='3'>KIB C</th>
		   <th class='th02' colspan='3'>KIB D</th>
		   <th class='th02' colspan='3'>KIB E</th>
		   <th class='th02' colspan='3'>KIB F</th>
		   <th class='th02' colspan='3'>KIB G</th>
		   <th class='th02' colspan='3'>Total</th>
	   </tr>
	   <tr>
	  	   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   <th class='th01' >Rencana</th>
		   <th class='th01' >Realisasi</th>
		   <th class='th01' >Selisih</th>
		   		   
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
	
		return 
			"";
	}
	function setNavAtas(){
		return
			'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=pemanfaat_rencana" title="Rencana Pemanfaatan Barang" >Rencana</a> |			
				<a href="pages.php?Pg=pemanfaatan" title="Pemanfaatan Barang">Pemanfaatan</a>  |  
				<a href="pages.php?Pg=rekappemanfaatan" title="Rekap Pemanfaatan Barang">Rekap</a>  |
				<a style="color:blue;" href="pages.php?Pg=rekappemanfaatan_skpd" title="Rekap Pemanfaatan Barang SKPD">Rekap SKPD</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=Left', $isi['nm_skpd']);			
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_a'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_a'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_a'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_b'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_b'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_b'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_c'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_c'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_c'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_d'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_d'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_d'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_e'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_e'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_e'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_f'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_f'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_f'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['jml_rencana_g'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_realisasi_g'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['jml_selisih_g'],2,',','.'));
					
		$Koloms[] = array('align=right', number_format($isi['tot_rencana'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['tot_realisasi'],2,',','.'));
		$Koloms[] = array('align=right', number_format($isi['tot_selisih'],2,',','.'));
		return $Koloms;
	}
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main,$HTTP_COOKIE_VARS;
			
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 
		$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
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
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				"<input type='hidden' value='$fmSKPDBidang' id='fmSKPDBidang' name='fmSKPDBidang' >".
				"<input type='hidden' value='$fmSKPDskpd' id='fmSKPDskpd' name='fmSKPDskpd' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		
		//$fmSKPDBidang=cekPOST('fmSKPDBidang');
		// $fmSKPDskpd=cekPOST('fmSKPDskpd');
		 $fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		//data cari ----------------------------
		
		$arrSemester = array(
			array('1','1'),
			array('2','2'), //array('3','Kode Rekening'),	//array('4','Nama Rekening')
		);
			
		 //get selectbox cari data :kode barang,nama barang
		 $fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		 
		
		$TampilOpt =
			"<table  class=\"adminform\">	<tr>		
			<td style='padding:1 8 0 8; '  valign=\"top\">".
			"<table><tr><td width='100'>Bidang</td><td width='10'>:</td><td>".
				$this->cmbQueryBidang('fmSKPDBidang',$fmSKPDBidang,'','onchange='.$this->Prefix.'.BidangAfter() '.$disabled1,'--- Pilih BIDANG ---','00')."</td></tr>".
			"<tr><td width='100'>SKPD</td><td width='10'>:</td><td>".
				$this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange='.$this->Prefix.'.SKPDAfter() '.$disabled1,'--- Pilih SKPD ---','00').
			"</td></tr>".
			"<tr><td width='100'>Tahun Anggaran</td><td width='10'>:</td><td>
				<input type='text'  size='4' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' readonly=''>
			</td></tr>			
			</table>".
			"</td>
			</tr></table>".
				genFilterBar(
				array(	
					"<table><tr><td width='105'>Semester</td><td width='10'>:</td><td>".
					cmbArray('fmPILSEMESTER',$fmPILSEMESTER,$arrSemester,'Semua','').
					"</td></tr>			
					</table>"					
				),$this->Prefix.".refreshList(true)",TRUE
			);
		
		return array('TampilOpt'=>$TampilOpt);
	}	
		
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');		
		$fmThnAnggaran=$_REQUEST['fmThnAnggaran'];
		$fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		$arrKondisiSKPD = array();
		if(!($fmThnAnggaran=='') ) $arrKondisiSKPD[] = "thn_anggaran='$fmThnAnggaran'";
		if(!($fmSKPDBidang=='' || $fmSKPDBidang=='00') ) $arrKondisiSKPD[] = "c='$fmSKPDBidang'";
		if(!($fmSKPDskpd=='' || $fmSKPDskpd=='00') ) $arrKondisiSKPD[] = "d='$fmSKPDskpd'";
		//$arrKondisiSKPD[] = " e='00' and e1='000' and d<>'00'";
		$kondSKPD= join(' and ',$arrKondisiSKPD);		
		$kondSKPD = $kondSKPD =='' ? '':' where '.$kondSKPD;
			$kondSKPD_pemanfaatan=str_replace("thn_anggaran","tahun",$kondSKPD);
		switch($fmPILSEMESTER){			
			case '1': $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and tgl_pemanfaatan>='".$fmThnAnggaran."-01-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-06-30' "; break;
			case '2': $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and tgl_pemanfaatan>='".$fmThnAnggaran."-07-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-12-31' "; break;
			default : $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and YEAR(tgl_pemanfaatan)='$fmThnAnggaran'" ; break;
		}
			
		$arrKondisiSKPD2 = array();
		if(!($fmSKPDBidang=='' || $fmSKPDBidang=='00') ) $arrKondisiSKPD2[] = "aa.c='$fmSKPDBidang'";
		if(!($fmSKPDskpd=='' || $fmSKPDskpd=='00') ) {
			$arrKondisiSKPD2[] = "aa.d='$fmSKPDskpd'";
		}else{
			$arrKondisiSKPD2[] = "  aa.d<>'00'";
		}
		
		$kondSKPD2= join(' and ',$arrKondisiSKPD2);		
		$kondSKPD2 = $kondSKPD2 =='' ? '':' where '.$kondSKPD2;
		$aqry = "select aa.c, aa.d, aa.nm_skpd,

    bb.jml_rencana_a,cc.jml_realisasi_a,(IFNULL(bb.jml_rencana_a,0)-IFNULL(cc.jml_realisasi_a,0)) as jml_selisih_a,
    bb.jml_rencana_b,cc.jml_realisasi_b,(IFNULL(bb.jml_rencana_b,0)-IFNULL(cc.jml_realisasi_b,0)) as jml_selisih_b,
    bb.jml_rencana_c,cc.jml_realisasi_c,(IFNULL(bb.jml_rencana_c,0)-IFNULL(cc.jml_realisasi_c,0)) as jml_selisih_c,
	bb.jml_rencana_d,cc.jml_realisasi_d,(IFNULL(bb.jml_rencana_d,0)-IFNULL(cc.jml_realisasi_d,0)) as jml_selisih_d,
    bb.jml_rencana_e,cc.jml_realisasi_e,(IFNULL(bb.jml_rencana_e,0)-IFNULL(cc.jml_realisasi_e,0)) as jml_selisih_e,
    bb.jml_rencana_f,cc.jml_realisasi_f,(IFNULL(bb.jml_rencana_f,0)-IFNULL(cc.jml_realisasi_f,0)) as jml_selisih_f,
    bb.jml_rencana_g,cc.jml_realisasi_g,(IFNULL(bb.jml_rencana_g,0)-IFNULL(cc.jml_realisasi_g,0)) as jml_selisih_g,   
	(bb.jml_rencana_a+bb.jml_rencana_b+bb.jml_rencana_c+bb.jml_rencana_d+bb.jml_rencana_e+bb.jml_rencana_f+bb.jml_rencana_g) as tot_rencana,    
	(cc.jml_realisasi_a+cc.jml_realisasi_b+cc.jml_realisasi_c+cc.jml_realisasi_d+cc.jml_realisasi_e+cc.jml_realisasi_f+cc.jml_realisasi_g) as tot_realisasi,
    ((IFNULL(bb.jml_rencana_a,0)-IFNULL(cc.jml_realisasi_a,0))+
    (IFNULL(bb.jml_rencana_b,0)-IFNULL(cc.jml_realisasi_b,0))+
    (IFNULL(bb.jml_rencana_c,0)-IFNULL(cc.jml_realisasi_c,0))+
    (IFNULL(bb.jml_rencana_d,0)-IFNULL(cc.jml_realisasi_d,0))+
    (IFNULL(bb.jml_rencana_e,0)-IFNULL(cc.jml_realisasi_e,0))+
    (IFNULL(bb.jml_rencana_f,0)-IFNULL(cc.jml_realisasi_f,0))+
    (IFNULL(bb.jml_rencana_g,0)-IFNULL(cc.jml_realisasi_g,0))) as tot_selisih

		from (ref_skpd aa

		left join

		(

		select c,d,  

		sum(if(f='01',harga,0)) as jml_rencana_a,

		sum(if(f='02',harga,0)) as jml_rencana_b,

		sum(if(f='03',harga,0)) as jml_rencana_c,

		sum(if(f='04',harga,0)) as jml_rencana_d,

		sum(if(f='05',harga,0)) as jml_rencana_e,

		sum(if(f='06',harga,0)) as jml_rencana_f,

		sum(if(f='07',harga,0)) as jml_rencana_g

		 from t_rencana_pemanfaatan 

		  $kondSKPD  

		 group by c,d

		) bb

		on aa.c=bb.c and aa.d=bb.d )

       left join       
              
       (

		select c,d,tgl_pemanfaatan, 

		sum(if(f='01',hrg_perolehan,0)) as jml_realisasi_a,

		sum(if(f='02',hrg_perolehan,0)) as jml_realisasi_b,

		sum(if(f='03',hrg_perolehan,0)) as jml_realisasi_c,

		sum(if(f='04',hrg_perolehan,0)) as jml_realisasi_d,

		sum(if(f='05',hrg_perolehan,0)) as jml_realisasi_e,

		sum(if(f='06',hrg_perolehan,0)) as jml_realisasi_f,

		sum(if(f='07',hrg_perolehan,0)) as jml_realisasi_g

		 from pemanfaatan 

		  $kondSKPD_pemanfaatan  

		 group by c,d

		) cc
       on aa.c=cc.c and aa.d=cc.d

		$kondSKPD2
		group by c,d
		$Order $Limit
";	
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	
		return $aqry;		
	}
	function setSumHal_query($Kondisi, $fsum){
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');		
		$fmThnAnggaran=$_REQUEST['fmThnAnggaran'];
		$fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		$arrKondisiSKPD = array();
		if(!($fmThnAnggaran=='') ) $arrKondisiSKPD[] = "thn_anggaran='$fmThnAnggaran'";
		if(!($fmSKPDBidang=='' || $fmSKPDBidang=='00') ) $arrKondisiSKPD[] = "c='$fmSKPDBidang'";
		if(!($fmSKPDskpd=='' || $fmSKPDskpd=='00') ) $arrKondisiSKPD[] = "d='$fmSKPDskpd'";
		//$arrKondisiSKPD[] = " e='00' and e1='000' and d<>'00'";
		$kondSKPD= join(' and ',$arrKondisiSKPD);		
		$kondSKPD = $kondSKPD =='' ? '':' where '.$kondSKPD;
			$kondSKPD_pemanfaatan=str_replace("thn_anggaran","tahun",$kondSKPD);		
		switch($fmPILSEMESTER){	
			case '1': $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and tgl_pemanfaatan>='".$fmThnAnggaran."-01-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-06-30' "; break;
			case '2': $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and tgl_pemanfaatan>='".$fmThnAnggaran."-07-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-12-31' "; break;
			default : $kondSKPD_pemanfaatan = $kondSKPD_pemanfaatan." and YEAR(tgl_pemanfaatan)='$fmThnAnggaran'" ; break;
		}
			
		$arrKondisiSKPD2 = array();
		if(!($fmSKPDBidang=='' || $fmSKPDBidang=='00') ) $arrKondisiSKPD2[] = "aa.c='$fmSKPDBidang'";
		if(!($fmSKPDskpd=='' || $fmSKPDskpd=='00') ) {
			$arrKondisiSKPD2[] = "aa.d='$fmSKPDskpd'";
		}else{
			$arrKondisiSKPD2[] = "  aa.d<>'00'";
		}
		$kondSKPD2= join(' and ',$arrKondisiSKPD2);		
		$kondSKPD2 = $kondSKPD2 =='' ? '':' where '.$kondSKPD2;
		return "select $fsum from (
		select aa.c, aa.d, aa.nm_skpd,

    bb.jml_rencana_a,cc.jml_realisasi_a,(IFNULL(bb.jml_rencana_a,0)-IFNULL(cc.jml_realisasi_a,0)) as jml_selisih_a,
    bb.jml_rencana_b,cc.jml_realisasi_b,(IFNULL(bb.jml_rencana_b,0)-IFNULL(cc.jml_realisasi_b,0)) as jml_selisih_b,
    bb.jml_rencana_c,cc.jml_realisasi_c,(IFNULL(bb.jml_rencana_c,0)-IFNULL(cc.jml_realisasi_c,0)) as jml_selisih_c,
	bb.jml_rencana_d,cc.jml_realisasi_d,(IFNULL(bb.jml_rencana_d,0)-IFNULL(cc.jml_realisasi_d,0)) as jml_selisih_d,
    bb.jml_rencana_e,cc.jml_realisasi_e,(IFNULL(bb.jml_rencana_e,0)-IFNULL(cc.jml_realisasi_e,0)) as jml_selisih_e,
    bb.jml_rencana_f,cc.jml_realisasi_f,(IFNULL(bb.jml_rencana_f,0)-IFNULL(cc.jml_realisasi_f,0)) as jml_selisih_f,
    bb.jml_rencana_g,cc.jml_realisasi_g,(IFNULL(bb.jml_rencana_g,0)-IFNULL(cc.jml_realisasi_g,0)) as jml_selisih_g,   
	(bb.jml_rencana_a+bb.jml_rencana_b+bb.jml_rencana_c+bb.jml_rencana_d+bb.jml_rencana_e+bb.jml_rencana_f+bb.jml_rencana_g) as tot_rencana,    
	(cc.jml_realisasi_a+cc.jml_realisasi_b+cc.jml_realisasi_c+cc.jml_realisasi_d+cc.jml_realisasi_e+cc.jml_realisasi_f+cc.jml_realisasi_g) as tot_realisasi,
    ((IFNULL(bb.jml_rencana_a,0)-IFNULL(cc.jml_realisasi_a,0))+
    (IFNULL(bb.jml_rencana_b,0)-IFNULL(cc.jml_realisasi_b,0))+
    (IFNULL(bb.jml_rencana_c,0)-IFNULL(cc.jml_realisasi_c,0))+
    (IFNULL(bb.jml_rencana_d,0)-IFNULL(cc.jml_realisasi_d,0))+
    (IFNULL(bb.jml_rencana_e,0)-IFNULL(cc.jml_realisasi_e,0))+
    (IFNULL(bb.jml_rencana_f,0)-IFNULL(cc.jml_realisasi_f,0))+
    (IFNULL(bb.jml_rencana_g,0)-IFNULL(cc.jml_realisasi_g,0))) as tot_selisih

		from (ref_skpd aa

		left join

		(

		select c,d,  

		sum(if(f='01',harga,0)) as jml_rencana_a,

		sum(if(f='02',harga,0)) as jml_rencana_b,

		sum(if(f='03',harga,0)) as jml_rencana_c,

		sum(if(f='04',harga,0)) as jml_rencana_d,

		sum(if(f='05',harga,0)) as jml_rencana_e,

		sum(if(f='06',harga,0)) as jml_rencana_f,

		sum(if(f='07',harga,0)) as jml_rencana_g

		 from t_rencana_pemanfaatan 

		  $kondSKPD  

		 group by c,d

		) bb

		on aa.c=bb.c and aa.d=bb.d )

       left join       
              
       (

		select c,d, tgl_pemanfaatan, 

		sum(if(f='01',hrg_perolehan,0)) as jml_realisasi_a,

		sum(if(f='02',hrg_perolehan,0)) as jml_realisasi_b,

		sum(if(f='03',hrg_perolehan,0)) as jml_realisasi_c,

		sum(if(f='04',hrg_perolehan,0)) as jml_realisasi_d,

		sum(if(f='05',hrg_perolehan,0)) as jml_realisasi_e,

		sum(if(f='06',hrg_perolehan,0)) as jml_realisasi_f,

		sum(if(f='07',hrg_perolehan,0)) as jml_realisasi_g

		 from pemanfaatan 

		  $kondSKPD_pemanfaatan  

		 group by c,d

		) cc
       on aa.c=cc.c and aa.d=cc.d

		$kondSKPD2
		group by c,d
		$Order $Limit
		) zz"; //echo $aqry;
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		 
		$arrKondisi[] = 
		//$tes =
		getKondisiSKPD3(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);
		if(!($fmThnAnggaran=='') ) $arrKondisi[] = "tahun='$fmThnAnggaran'";
		
		$fmPILSEMESTER = cekPOST('fmPILSEMESTER');
		switch($fmPILSEMESTER){			
			case '1': $arrKondisi[] = " tgl_pemanfaatan>='".$fmThnAnggaran."-01-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-06-30' "; break;
			case '2': $arrKondisi[] = " tgl_pemanfaatan>='".$fmThnAnggaran."-07-01' and  cast(tgl_pemanfaatan as DATE)<='".$fmThnAnggaran."-12-31' "; break;
			default :""; break;
		}
		$fmFiltThnAnggaran = cekPOST('fmFiltThnAnggaran');
		if(!empty($fmFiltThnAnggaran) ) {
			$arrKondisi[] = "tahun='$fmFiltThnAnggaran'";
		}else{
		
		}
		
		$kode_rekening  = cekPOST('kode_rekening');
		if(!empty($kode_rekening) ) $arrKondisi[] = "concat(k,'.',l,'.',m,'.',n,'.',o) like '%$kode_rekening%'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$fmORDER2 = cekPOST('fmORDER2');
		$fmDESC2 = cekPOST('fmDESC2');				
		$Asc2 = $fmDESC2 ==''? '': 'desc';		
		$fmORDER3 = cekPOST('fmORDER3');
		$fmDESC3 = cekPOST('fmDESC3');				
		$Asc3 = $fmDESC3 ==''? '': 'desc';		
		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}
		switch($fmORDER2){
			case '1': $arrOrders[] = " tahun $Asc2 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc2 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc2 " ;break;
		}
		switch($fmORDER3){
			case '1': $arrOrders[] = " tahun $Asc3 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc3 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc3 " ;break;
		}
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = ' ';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$rekappemanfaatan_skpd = new rekappemanfaatan_skpdObj();

?>