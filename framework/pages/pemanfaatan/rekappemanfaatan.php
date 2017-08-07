<?php

class rekappemanfaatanObj  extends DaftarObj2{	
	var $Prefix = 'rekappemanfaatan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pemanfaatan'; //daftar
	var $TblName_Hapus = 'pemanfaatan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	//var $KeyFields = array('ka','kb','kc','kd','ke');
	var $FieldSum = array('rencana','realisasi','selisih');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 9, 8, 8);//berdasar mode
	var $FieldSum_Cp2 = array(8, 7, 7);	
	var $checkbox_rowspan = 2;
	var $totalCol = 0; //total kolom daftar	
	var $fieldSum_lokasi = array( 9);  //lokasi sumary di kolom ke		
	var $PageTitle = 'Pemanfaatan Barang Milik Daerah';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';	
	//var $pagePerHal ='100';
	var $cetak_xls=TRUE;
	var $fileNameExcel='rekappemanfaatan.xls';
	var $Cetak_Judul = 'Rekap Pemanfaatan Barang Milik Daerah';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'rekappemanfaatanForm'; 	
			
	function setTitle(){
			return 'Rekap Pemanfaatan Barang Milik Daerah';			
	}
	function setMenuEdit(){		
		return

			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Lampiran()","delete_f2.png","Lampiran", 'Lampiran').
			"</td>";
	}
	
	function setMenuView(){
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Lampiran()","print_f2.png","Lampiran",'Lampiran')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png","Excel",'Export ke Excell')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","edit_f2.png","Default",'Setting Default')."</td>";		
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
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".					
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script src='js/jquery-ui.custom.js'></script>".		    
			 "<script type='text/javascript' src='js/pemanfaatan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".			
			$scriptload;
	}	
		
	//daftar =================================	
	function setNavAtas(){
		global $Main;
		if ($Main->VERSI_NAME=='JABAR') $persediaan = "| <a href='pages.php?Pg=perencanaanbarang_persediaan' title='Perencanaan Persediaan'>Persediaan</a> ";
	
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=pemanfaat_rencana" title="Daftar Pemanfaatan milik Daerah">Rencana</a> |				
				<a href="pages.php?Pg=pemanfaatan" title="Daftar Pemanfaatan milik Daerah">Pemanfaatan</a>  |
				<a style="color:blue;" href="pages.php?Pg=rekappemandaatan" title="Rekap Pemanfaatan milik Daerah">Rekap</a>  |  
				<a href="pages.php?Pg=rekappemanfaatan_skpd" title="Rekap Pemanfaatan milik Daerah">Rekap SKPD</a> '.
					$persediaan.
				  '&nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';
	}

	function genDaftarInitial($height=''){
		global $HTTP_COOKIE_VARS, $Main;
		$vOpsi = $this->genDaftarOpsi();
		$jns=$_REQUEST['jns'];
		$fmThnAnggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
		$fmLimit = 50;
		$fmBerdasarkan = 1;
		$fmLevel = 2;
		$kdakun = $Main->KODE_ASET_TETAP;
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='jns' name='jns' value='$jns'>".
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				"<input type='hidden' value='$fmLimit' id='fmLimit' name='fmLimit' >".
				"<input type='hidden' value='$fmLevel' id='fmLevel' name='fmLevel' >".
				"<input type='hidden' value='$fmBerdasarkan' id='fmBerdasarkan' name='fmBerdasarkan' >".
				"<input type='hidden' value='$kdakun' id='kdakun' name='kdakun' >".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}

	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$berdasarkan=$_REQUEST['fmBerdasarkan'];
		if($berdasarkan==2){
			$headerTable =
				"<thead>
					<tr>				
					<th class='th01' width='40' rowspan='2'>No.</th>
					$Checkbox		
					<th class='th02' colspan='5'>Kode Akun</th>
					<th class='th01' rowspan='2'>Nama Akun</th>
					<th class='th01' rowspan='2'>Rencana</th>
					<th class='th01' rowspan='2'>Realisasi</th>										
					<th class='th01' rowspan='2'>Selisih</th>											
				</tr>
				<tr>
				<th class='th01' >ka </th>	
				<th class='th01' >kb </th>	
				<th class='th01' >kc </th>	
				<th class='th01' >kd </th>	
				<th class='th01' >ke </th>	
				</tr>
				</thead>";				
		}else{
			$headerTable =
				"<thead>
					<tr>				
					<th class='th01' width='40' rowspan='2'>No.</th>
					$Checkbox		
					<th class='th02' colspan='5'>Kode Barang</th>
					<th class='th01' rowspan='2'>Nama Barang</th>
					<th class='th01' rowspan='2'>Rencana</th>
					<th class='th01' rowspan='2'>Realisasi</th>										
					<th class='th01' rowspan='2'>Selisih</th>											
				</tr>
				<tr>
				<th class='th01' >f </th>	
				<th class='th01' >g </th>	
				<th class='th01' >h </th>	
				<th class='th01' >i </th>	
				<th class='th01' >j </th>	
				</tr>
				</thead>";						
		}
		return $headerTable;
	}
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$berdasarkan=$_REQUEST['fmBerdasarkan'];
		$fmThnAnggaran = $_REQUEST['fmThnAnggaran']; 		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmLevel=$_REQUEST['fmLevel'];
		if( $berdasarkan == 2 && $fmLevel<3 ) $fmLevel = 3 ;
		$semester=$_REQUEST['fmSemester'];
		if($semester==1){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='1' and MONTH(tgl_pemanfaatan)<'7'";
		}elseif($semester==2){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='7' and MONTH(tgl_pemanfaatan)<='12'";
		}
		if(!empty($fmThnAnggaran)){
			if($Kondisi==''){
				 $KondTahunPemanfaatan = "WHERE YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "WHERE thn_anggaran='$fmThnAnggaran'";	
			}else{
				 $KondTahunPemanfaatan = "AND YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "AND thn_anggaran='$fmThnAnggaran'";				}			
		}		


		$kdakun = $_REQUEST['kdakun'];
//		if( $fmBerdasarkan == 2 ) $arrKondisi[] = " concat(ka, '.', kb, '.', kc, '.', kd, '.', ke) like '$kdakun%' ";	
		//==================================Kondisi Level untuk barang===================================================//
		switch($fmLevel){
			case '1' : {
				$kondLevelBarang='and aa.g=00'; 
				$kondLevelAkun='and aa.kb=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f';
				$KondJoinBarangRencana='aa.f=cc.f';				 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka';
				$KondJoinAkunRencana='aa.ka=cc.ka';
				$GroupbyBarang='f'; 
				$GroupbyAkun='ka'; 
				break;
			} 
			case '2' : {
				$kondLevelBarang='and aa.h=00'; 
				$kondLevelAkun='and aa.kc=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g';
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g';						 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb';
				$GroupbyBarang='f,g'; 
				$GroupbyAkun='ka,kb'; 
				break;
			}
			case '3' : {
				$kondLevelBarang='and aa.i=00'; 
				$kondLevelAkun='and aa.kd=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h'; 
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h'; 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc';
				$GroupbyBarang='f,g,h'; 
				$GroupbyAkun='ka,kb,kc'; 
				break;
			} 
			case '4' : {
				$kondLevelBarang='and aa.j=00'; 
				$kondLevelAkun='and aa.ke=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i'; 
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h and aa.i=cc.i';
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc and aa.kd=bb.kd';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc and aa.kd=cc.kd';				
				$GroupbyBarang='f,g,h,i'; 
				$GroupbyAkun='ka,kb,kc,kd'; 
				break;
			} 											 			
			default: case '5' : {
				$kondLevelBarang=''; 
				$kondLevelAkun='and aa.kf=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i and aa.j=bb.j';
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h and aa.i=cc.i and aa.j=cc.j';				 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc and aa.kd=bb.kd and aa.ke=bb.ke';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc and aa.kd=cc.kd and aa.ke=cc.ke';				
				$GroupbyBarang='f,g,h,i,j'; 
				$GroupbyAkun='ka,kb,kc,kd,ke'; 
				break;
			} 											 			
		}
		//==================================================================================================//
		if(empty($fmSKPD)) $fmSKPD='00';
		if(empty($fmUNIT)) $fmUNIT='00';
		if($berdasarkan==2){
			$aqry = "select '$fmSKPD' as c,'$fmUNIT' as d, aa.ka, aa.kb, aa.kc, aa.kd, aa.ke, aa.kf, aa.nm_account,".
					"ifNULL(bb.realisasi,0) as realisasi,ifNULL(cc.rencana,0) as rencana,(ifNULL(cc.rencana,0)-ifNULL(bb.realisasi,0)) as selisih,bb.ket, $fmThnAnggaran as tahun ".
//ifNULL(bb.tahun,'0000') as tahun
					"from (".
						"((select ka,kb,kc,kd,ke,kf, nm_account from ref_jurnal where concat(ka, '.', kb, '.', kc, '.', kd, '.', ke) like '$kdakun%' $KondThnAkun ) ".
						"UNION ".
						"(select 'ftot' as ka, '0' as kb, '0' as kc, '0' as kd, '0' as ke, '0' as kf, '' as nm_account)) as aa ".
					"LEFT JOIN ".
						"(select c,d,ifNULL(ka,'ftot') as ka, ifNULL(kb,'0') as kb, ifNULL(kc,'0') as kc, ifNULL(kd,'0') as kd, ifNULL(ke,'0') as ke, ".
						"sum(hrg_perolehan) as realisasi, ket, YEAR(tgl_pemanfaatan) as tahun ".
						"from pemanfaatan ". 
						"$Kondisi $KondTahunPemanfaatan $KondSemester ".
						"group by $GroupbyAkun ) as bb ".
					"on $KondJoinAkunPemanfaatan ".
					"LEFT JOIN ".
						"(select c,d,ifNULL(ka,'ftot') as ka, ifNULL(kb,'0') as kb, ifNULL(kc,'0') as kc, ifNULL(kd,'0') as kd, ifNULL(ke,'0') as ke, ".
						"sum(harga) as rencana, ket, thn_anggaran as tahun ".
						"from t_rencana_pemanfaatan ". 
						"$Kondisi $KondTahunRencana ".
						"group by $GroupbyAkun ) as cc ".
					"on $KondJoinAkunRencana) where aa.ka not in ('ftot') $kondLevelAkun $Limit";
		}else{
			$aqry = "select '$fmSKPD' as c, '$fmUNIT' as d,aa.f,aa.g,aa.h,aa.i,aa.j,aa.nm_barang,ifNULL(bb.realisasi,0) as realisasi,".
					"ifNULL(cc.rencana,0) as rencana,(ifNULL(cc.rencana,0)-ifNULL(bb.realisasi,0)) as selisih,bb.ket,$fmThnAnggaran as tahun ".
//ifNULL(bb.tahun,'0000') as tahun
				"from (".
					"((select f,g,h,i,j,nm_barang from ref_barang) ".
					"union ".
					"(select 'ftot' AS f,'00' AS g,'00' AS h,'00' AS i,'000' AS j, '' AS nm_barang)) AS aa ".
				"Left Join ".
					"(select c,d,ifNULL(f,'ftot') AS f, ifNULL(g,'00') AS g,ifNULL(h,'00') AS h, ". 
					"ifNULL(i,'00') AS i,ifNULL(j,'000') AS j, sum(hrg_perolehan) as realisasi , ket, YEAR(tgl_pemanfaatan) as tahun ".
					"from pemanfaatan ".
					"$Kondisi $KondTahunPemanfaatan $KondSemester  ".
					"group by $GroupbyBarang with ROLLUP) as bb ".
				"On $KondJoinBarangPemanfaatan ".
				"Left Join ".
					"(select c,d,ifNULL(f,'ftot') AS f, ifNULL(g,'00') AS g,ifNULL(h,'00') AS h, ". 
					"ifNULL(i,'00') AS i,ifNULL(j,'000') AS j, sum(harga) as rencana , ket, thn_anggaran as tahun ".
					"from t_rencana_pemanfaatan ". 
					"$Kondisi $KondTahunRencana ".
					"group by $GroupbyBarang with ROLLUP) as cc ".
				"On $KondJoinBarangRencana)	 where aa.f not in ('ftot') $kondLevelBarang $Limit ";		

		}//return mysql_query($aqry);
		return $aqry;
	}

	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
		$berdasarkan=$_REQUEST['fmBerdasarkan'];
		if($berdasarkan==2){
			$this->KeyFields=array('c','d','ka','kb','kc','kd','ke','tahun');				
			$KondIf=$isi['ka']!='0' && $isi['kb']=='0' && $isi['kc']=='0' && $isi['kd']=='0' && $isi['ke']=='0';
		}else{
			$this->KeyFields=array('c','d','f','g','h','i','j','tahun');
			$KondIf=$isi['f']!='00' && $isi['g']=='00' && $isi['h']=='00' && $isi['i']=='00' && $isi['j']=='000';
		}
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
		$qry = mysql_query($aqry);
		$numrows = mysql_num_rows($qry); $cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
					
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				
			
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
				
			//sum halaman
			for ($i=0; $i< sizeof($this->FieldSum) ; $i++) {
				if($berdasarkan==2){
					//if($isi[$this->KeyFields[2]]!=='00' && $isi[$this->KeyFields[3]]!='00' && $isi[$this->KeyFields[4]]=='00'){
						$this->SumValue[$i] += $isi[$this->FieldSum[$i]];				
					//}	
				}else{
					if($isi[$this->KeyFields[2]]!=='00' && $isi[$this->KeyFields[3]]=='00'){
						$this->SumValue[$i] += $isi[$this->FieldSum[$i]];				
					}	
				}
				
			}
			
			//---------------------------
			$rowatr_ = $RowAtr." valign='top' id='$cb' value='".$isi['Id']."'";
			$bef= $this->setDaftar_before_getrow(
					$no,$isi,$Mode, $TampilCheckBox,  
					$rowatr_,
					$ColStyle
					);
			$ListData .= $bef['ListData'];
			$no = $bef['no'];
			//get row
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
			}
		}
		
		}
		
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
		/*$TampilTotalHalRp = number_format($TotalHalRp,2, ',', '.');		
		$TotalColSpan = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
		$ContentTotalHal =
			"<tr>
				<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>
				<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
				<td class='$ColStyle' colspan='4'></td>
			</tr>" ;
			
		$ContentTotal = 
				"<tr>
					<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total</td>
					<td class='$ColStyle' align='right'><b><div  id='cntDaftarTotal'>".$SumHal['sum']."</div></td>
					<td class='$ColStyle' colspan='4'></td>
				</tr>" ;
		
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			$ContentTotalHal='';			
		}
		$ContentSum=$ContentTotalHal.$ContentTotal;
		*/
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				
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
	
	function genSum_setTampilValue($fieldName, $value){
		switch($fieldName){
			case 'jml_barang' : return number_format($value, 0, ',' ,'.'); break;
			default : return number_format($value, 2, ',' ,'.'); break;
		}
	}	
	
	function genRowSum_setTampilValue($i, $value){
		switch($i){
			case '0' : return number_format($value,2, ',', '.'); break;
			default : return number_format($value,2, ',', '.');	break;		
		}		
	}
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
	$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
	$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
	$berdasarkan=$_REQUEST['fmBerdasarkan'];
	$fmThnAnggaran=$_REQUEST['fmThnAnggaran'];	
	$fmLevel=$_REQUEST['fmLevel'];
	$semester=$_REQUEST['fmSemester'];
	if($semester==1){
		$KondSemester="and MONTH(tgl_pemanfaatan)>='1' and MONTH(tgl_pemanfaatan)<'7'";
	}elseif($semester==2){
		$KondSemester="and MONTH(tgl_pemanfaatan)>='7' and MONTH(tgl_pemanfaatan)<='12'";
	}	
	//===================================Kondisi SKPD===================================================//
	$arrKondSKPD=array();
	if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondSKPD[] = "c='$fmSKPD'";
	if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondSKPD[] = "d='$fmUNIT'";
	if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondSKPD[] = "e='$fmSUBUNIT'";
	if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondSKPD[] = "e1='$fmSEKSI'";

	$KondSKPD= join(' and ',$arrKondSKPD);
	$KondSKPD = $KondSKPD =='' ? '':' and '.$KondSKPD;	
	//==================================================================================================//
	 if($Main->WITH_THN_ANGGARAN){
		$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
				thn_akun<=$fmThnAnggaran";
				$qry1=mysql_query($aqry1);			
				$qry_jurnal=mysql_fetch_array($qry1);
				$thn_akun=$qry_jurnal['thnMax'];
				//$arrKondisi[] = " thn_akun = '$thn_akun'";														
		$KondThnAkun = $berdasarkan==2?" and thn_akun=$thn_akun ":"";
			
	}	
	$isi = array_map('utf8_encode', $isi);
	if($berdasarkan==2){
		$kode1=$isi['ka']==0?'':$isi['ka'];
		$kode2=$isi['kb']==0?'':$isi['kb'];
		$kode3=$isi['kc']==0?'':$isi['kc'];
		$kode4=$isi['kd']==0?'':$isi['kd'];
		$kode5=$isi['ke']==0?'':$isi['ke'];
		$nama=$isi['nm_account'];
		if($kode1!=0  && $kode2==0){		
			$tagspace='';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3==0){
			$bold='<b>';
			$bold_ttp='</b>';	
			$tagspace='&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4==0){
			$bold='<b>';
			$bold_ttp='</b>';
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4!=0 && $kode5==0){
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4!=0 && $kode5!=0){
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}
		//================================Untuk Mendapatkan jumlah barang dan harga barang================================//
		switch($fmLevel){
			case '1':case '2':case '3':default:{
				if($kode1!=0  && $kode2!=0 && $kode3==0){
					//query get jml_harga level 3 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];	
					$selisih=$qh['selisih'];							
				}else{
					$realisasi=$isi['realisasi'];
					$rencana=$isi['rencana'];						
					$selisih=$isi['selisih'];				
				}				
			} break;
			case '4':{
				if($kode1!=0  && $kode2!=0 && $kode3==0){
					//query get jml_harga level 3 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];			
					$selisih=$qh['selisih'];	
				}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4==0){
					//query get jml_harga level 4 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];		
					$selisih=$qh['selisih'];	
				}else{
					$realisasi=$isi['realisasi'];	
					$rencana=$isi['rencana'];
					$selisih=$isi['selisih'];								
				}							
			} break;
			case '5':{
				if($kode1!=0  && $kode2!=0 && $kode3==0){
					//query get jml_harga level 3 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];			
					$selisih=$qh['selisih'];	
				}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4==0){
					//query get jml_harga level 3 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];		
					$selisih=$qh['selisih'];	
				}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4!=0 && $kode5==0){
					//query get jml_harga level 3 selanjutnya
					$qh=mysql_fetch_array(mysql_query("select sum(rencana) as rencana, sum(realisasi) as realisasi, sum(selisih) as selisih  
														from
															(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and kd='$kode4' and YEAR(tgl_pemanfaatan)='$fmThnAnggaran' $KondThnAkun $KondSKPD $KondSemester
															UNION
															select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan WHERE ka='$kode1' and kb='$kode2' and kc='$kode3' and kd='$kode4' and thn_anggaran='$fmThnAnggaran' $KondThnAkun $KondSKPD) 
														as aa"));
					$realisasi=$qh['realisasi'];	
					$rencana=$qh['rencana'];			
					$selisih=$qh['selisih'];	
				}else{
					$realisasi=$isi['realisasi'];	
					$rencana=$isi['rencana'];
					$selisih=$isi['selisih'];					
				}						
			} break;
		}
		//================================================================================================================//
		/*if($fmLevel==1 || $fmLevel==2 || $fmLevel==3){
		}elseif($fmLevel==4){
		}else{
		}*/						
	}else{
		$kode1=$isi['f']==0?'':$isi['f'];
		$kode2=$isi['g']==0?'':$isi['g'];
		$kode3=$isi['h']==0?'':$isi['h'];
		$kode4=$isi['i']==0?'':$isi['i'];
		$kode5=$isi['j']==0?'':$isi['j'];
		$nama=$isi['nm_barang'];
		if($kode1!=0  && $kode2==0){		
			$bold='<b>';
			$bold_ttp='</b>';
			$tagspace='';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3==0){
			$tagspace='&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4==0){
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4!=0 && $kode5==0){
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}elseif($kode1!=0  && $kode2!=0 && $kode3!=0 && $kode4!=0 && $kode5!=0){
			$tagspace='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			
		}
		$realisasi=$isi['realisasi'];	
		$rencana=$isi['rencana'];
		$selisih=$isi['selisih'];					
	}

	//kondisi jika realisasi kosong
	if($realisasi==''){
		 $realisasi="0";	 	
	}else{
		 $realisasi=$realisasi;	 			
	}
	//kondisi jika rencana kosong
	if($rencana==''){
		 $rencana="0";	 	
	}else{
		 $rencana=$rencana;	 			
	}
	//kondisi jika rencana kosong
	if($selisih==''){
		 $selisih="0";	 	
	}else{
		 $selisih=$selisih;	 			
	}
				 
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	$Koloms[] = array('align="center"',$kode1);
	$Koloms[] = array('align="center"',$kode2);	 	 	 	 
	$Koloms[] = array('align="center"',$kode3);
	$Koloms[] = array('align="center"',$kode4);	 		
	$Koloms[] = array('align="center"',$kode5);	 		
	$Koloms[] = array('align="left"',$bold.$tagspace.$nama.$bold_ttp);	 	 	 	 
	$Koloms[] = array('align="right"',$bold.number_format($rencana,2,',','.').$bold_ttp);	 	 	 	 
	$Koloms[] = array('align="right"',$bold.number_format($realisasi,2,',','.').$bold_ttp);
	$Koloms[] = array('align="right"',$bold.number_format($selisih,2,',','.').$bold_ttp);			 	 	 	 
	return $Koloms; 	
	}
	
	function setSumHal_query($Kondisi, $fsum){
		global $Main;
		$berdasarkan=$_REQUEST['fmBerdasarkan'];
		$fmThnAnggaran = $_REQUEST['fmThnAnggaran']; 		
		$fmLevel=$_REQUEST['fmLevel'];
		if ($berdasarkan == 2 && $fmLevel <3) $fmLevel = 3;
		$kdakun = $_REQUEST['kdakun'];
		$semester=$_REQUEST['fmSemester'];
		if($semester==1){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='1' and MONTH(tgl_pemanfaatan)<'7'";
		}elseif($semester==2){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='7' and MONTH(tgl_pemanfaatan)<='12'";
		}
		if(!empty($fmThnAnggaran)){
			if($Kondisi==''){
				 $KondTahunPemanfaatan = "WHERE YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "WHERE thn_anggaran='$fmThnAnggaran'";	
			}else{
				 $KondTahunPemanfaatan = "AND YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "AND thn_anggaran='$fmThnAnggaran'";				}			
		}	
			
		//==================================Kondisi Level untuk barang===================================================//
		switch($fmLevel){
			case '1' : {
				$kondLevelBarang='and aa.g=00'; 
				$kondLevelAkun='and aa.kb=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f';
				$KondJoinBarangRencana='aa.f=cc.f';				 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka';
				$KondJoinAkunRencana='aa.ka=cc.ka';
				$GroupbyBarang='f'; 
				$GroupbyAkun='ka'; 
				break;
			} 
			case '2' : {
				$kondLevelBarang='and aa.h=00'; 
				$kondLevelAkun='and aa.kc=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g';
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g';						 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb';
				$GroupbyBarang='f,g'; 
				$GroupbyAkun='ka,kb'; 
				break;
			}
			case '3' : {
				$kondLevelBarang='and aa.i=00'; 
				$kondLevelAkun='and aa.kd=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h'; 
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h'; 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc';
				$GroupbyBarang='f,g,h'; 
				$GroupbyAkun='ka,kb,kc'; 
				break;
			} 
			case '4' : {
				$kondLevelBarang='and aa.j=00'; 
				$kondLevelAkun='and aa.ke=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i'; 
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h and aa.i=cc.i';
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc and aa.kd=bb.kd';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc and aa.kd=cc.kd';				
				$GroupbyBarang='f,g,h,i'; 
				$GroupbyAkun='ka,kb,kc,kd'; 
				break;
			} 											 			
			default: case '5' : {
				$kondLevelBarang=''; 
				$kondLevelAkun='and aa.kf=0'; 
				$KondJoinBarangPemanfaatan='aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i and aa.j=bb.j';
				$KondJoinBarangRencana='aa.f=cc.f and aa.g=cc.g and aa.h=cc.h and aa.i=cc.i and aa.j=cc.j';				 
				$KondJoinAkunPemanfaatan='aa.ka=bb.ka and aa.kb=bb.kb and aa.kc=bb.kc and aa.kd=bb.kd and aa.ke=bb.ke';
				$KondJoinAkunRencana='aa.ka=cc.ka and aa.kb=cc.kb and aa.kc=cc.kc and aa.kd=cc.kd and aa.ke=cc.ke';				
				$GroupbyBarang='f,g,h,i,j'; 
				$GroupbyAkun='ka,kb,kc,kd,ke'; 
				break;
			} 											 			
		}
		//==================================================================================================//
		
		if($berdasarkan==2){
			return "select $fsum
					from (
					       ((select ka,kb,kc,kd,ke,kf, nm_account from ref_jurnal where concat(ka, '.', kb, '.', kc, '.', kd, '.', ke) like '$kdakun%' $KondThnAkun )
					       UNION
					       (select 'ftot' as ka, '0' as kb, '0' as kc, '0' as kd, '0' as ke, '0' as kf, '' as nm_account)) as aa
					LEFT JOIN
					     (select c,d,ifNULL(ka,'ftot') as ka, ifNULL(kb,'0') as kb, ifNULL(kc,'0') as kc, ifNULL(kd,'0') as kd, ifNULL(ke,'0') as ke, 
						 sum(hrg_perolehan) as realisasi, ket, YEAR(tgl_pemanfaatan) 
					     from pemanfaatan 
						 $Kondisi $KondTahunPemanfaatan $KondSemester   
						 group by $GroupbyAkun) as bb
					on $KondJoinAkunPemanfaatan
					LEFT JOIN
					     (select c,d,ifNULL(ka,'ftot') as ka, ifNULL(kb,'0') as kb, ifNULL(kc,'0') as kc, ifNULL(kd,'0') as kd, ifNULL(ke,'0') as ke,
						 sum(harga) as rencana, ket, thn_anggaran 
					     from t_rencana_pemanfaatan 
						 $Kondisi $KondTahunRencana  
						 group by $GroupbyAkun) as cc
					on $KondJoinAkunRencana) where aa.ka not in ('ftot') $kondLevelAkun";
		}else{
			return "select $fsum
				from (
					((select f,g,h,i,j,nm_barang from ref_barang)
					union 
					(select 'ftot' AS f,'00' AS g,'00' AS h,'00' AS i,'000' AS j, '' AS nm_barang)) AS aa
				Left Join
					(select c,d,ifNULL(f,'ftot') AS f, ifNULL(g,'00') AS g,ifNULL(h,'00') AS h, ifNULL(i,'00') AS i,ifNULL(j,'000') AS j, 
					sum(hrg_perolehan) as realisasi, ket, YEAR(tgl_pemanfaatan)
					from pemanfaatan 
					$Kondisi $KondTahunPemanfaatan $KondSemester   
					group by $GroupbyBarang with ROLLUP) as bb
				On $KondJoinBarangPemanfaatan
				Left Join
					(select c,d,ifNULL(f,'ftot') AS f, ifNULL(g,'00') AS g,ifNULL(h,'00') AS h, ifNULL(i,'00') AS i,ifNULL(j,'000') AS j, 
					sum(harga) as rencana , ket, thn_anggaran
					from t_rencana_pemanfaatan 
					$Kondisi $KondTahunRencana 
					group by $GroupbyBarang with ROLLUP) as cc
				On $KondJoinBarangRencana) where aa.f not in ('ftot') $kondLevelBarang";		
		}
	}
	
	function setSumHal2_query($Kondisi, $fsum){
		$kdakun = $_REQUEST['kdakun'];
		$berdasarkan=$_REQUEST['fmBerdasarkan'];		
		$fmThnAnggaran = $_REQUEST['fmThnAnggaran']; 		
		$semester=$_REQUEST['fmSemester'];
		if($semester==1){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='1' and MONTH(tgl_pemanfaatan)<'7'";
		}elseif($semester==2){
			$KondSemester="and MONTH(tgl_pemanfaatan)>='7' and MONTH(tgl_pemanfaatan)<='12'";
		}
		if(!empty($fmThnAnggaran)){
			if($Kondisi==''){
				 $KondTahunPemanfaatan = "WHERE YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "WHERE thn_anggaran='$fmThnAnggaran'";	
			}else{
				 $KondTahunPemanfaatan = "AND YEAR(tgl_pemanfaatan)='$fmThnAnggaran'";								
				 $KondTahunRencana = "AND thn_anggaran='$fmThnAnggaran'";				}			
		}	
						
		if($berdasarkan==2){
			return "select $fsum 
					from
						(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan $Kondisi $KondTahunPemanfaatan $KondSemester and concat(ka, '.', kb, '.', kc, '.', kd, '.', ke, '.', kf) like '$kdakun%'
						UNION
						select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan $Kondisi $KondTahunRencana and concat(ka, '.', kb, '.', kc, '.', kd, '.', ke, '.', kf) like '$kdakun%') 
					as aa";	
		}else{
			return "select $fsum 
					from
						(SELECT id, 0 as rencana, hrg_perolehan as realisasi,(0-hrg_perolehan) as selisih from pemanfaatan$Kondisi $KondTahunPemanfaatan $KondSemester  
						UNION
						select id, harga as rencana, 0 as realisasi,(harga-0) as selisih from t_rencana_pemanfaatan $Kondisi $KondTahunRencana) 
					as aa";
		}
		
 //echo $aqry;
	}	
	
	function genSumHal($Kondisi){
		
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		
		//$fsum_ = array();
		$fsum_data = "count(*) as cnt";
		//$i=0;
		$fsum_tot_ = array();
		foreach($this->FieldSum as &$value){
			$fsum_tot_[] = "sum($value) as sum_$value";
			//$i++; 
		}
		$fsum_tot = join(',',$fsum_tot_);
				
		//$aqry = "select count(*) as cnt,  sum(jml_terima) as totterima  from $this->TblName $Kondisi "; //echo $aqry;
		//$aqry = "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		//query for jml_data
		$aqry = $this->setSumHal_query($Kondisi, $fsum_data); $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['cnt'];			
			/*$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,$this->elCurrPage,cekPOST('HalDefault'),5, $this->Prefix.'.gotoHalaman').
				"</td></tr></table>";
			*/
			//$Hal = $this->setDaftar_hal($jmlData);
							
			//$jmlTotal= $isi['totterima'];
			
			/*foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue(0, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');*/			
			
		}
		
		//query for jml_total
		$aqry2 = $this->setSumHal2_query($Kondisi, $fsum_tot); $cek .= $aqry2;
		$qry2 = mysql_query($aqry2); 
		if ($isi2= mysql_fetch_array($qry2)){			
			//$jmlData = $isi['cnt'];			
			/*$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,$this->elCurrPage,cekPOST('HalDefault'),5, $this->Prefix.'.gotoHalaman').
				"</td></tr></table>";
			*/
			//$Hal = $this->setDaftar_hal($jmlData);
							
			//$jmlTotal= $isi['totterima'];
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi2["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue($value, $isi2["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}			
		
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = ''; 			
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}	
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		global $HTTP_COOKIE_VARS;

		$arrLevel = array(
			array('1','1'),
			array('2','2'),
			array('3','3'),
			array('4','4'),
			array('5','5')		
		);
			
		$fmSemester = cekPOST('fmSemester');	
		$fmBerdasarkan = cekPOST('fmBerdasarkan');	
		$kdakun = cekPOST('kdakun');
		$fmLevel = cekPOST('fmLevel');
		if( $fmBerdasarkan == 2 && $fmLevel<3 ) $fmLevel = 3 ;
		$fmLimit = cekPOST('fmLimit');						
		$fmThnAnggaran = $HTTP_COOKIE_VARS['coThnAnggaran'];
		//get selectbox for cari data:kode barang,nama barang
		$fmPILCARI= cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		
		//get selectbox for Tahun Rekening:tahun anggaran 2013,2012
		$fmFiltThnAnggaran = $_REQUEST['fmFiltThnAnggaran'];
		$kode_rekening = $_REQUEST['kode_rekening'];
		
		//$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];	$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		
		//data order ------------------------------
		$arrOrder = array(
			array('1','Tahun Anggaran'),
			array('2','Kode Barang'),	
			array('3','Kode Rekening'),		
		);
		
		$arrSemester = array(
			array('0','Semua'),
			array('1','1'),		
			array('2','2'),		
		);

		$arrBerdasarkan = array(
			array('1','Nama Barang'),
			array('2','Nama Akun'),		
		);		
		
		//Order untuk Order 1
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');


			
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') .
				"</td>
			<td >" .
			"</td></tr>
			<tr><td>
				&nbsp;&nbsp;&nbsp;Tahun Anggaran &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
				&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' size='5' readonly>	
			</td></tr>			
			</table>".
			genFilterBar(
				array(							
					'Semester : '.
					cmbArray('fmSemester',$fmSemester,$arrSemester,'--Semester--','').
					'&nbsp;&nbsp;Berdasarkan : '.
					cmbArray('fmBerdasarkan',$fmBerdasarkan,$arrBerdasarkan,'--Berdasarkan--','').
					'&nbsp;&nbsp; Level : '.
					cmbArray('fmLevel',$fmLevel,$arrLevel,'--Level--','').
					"&nbsp;&nbsp; Limit : <input text value='$fmLimit' id='fmLimit' name='fmLimit' size='4'>".
					"<input type='hidden' value='$kdakun' id='kdakun' name='kdakun' >"	
				),				
				$this->Prefix.".refreshList(true)");
		
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID'];
		
		 $fmBidang = $_REQUEST['fmBidang']; 
		 $fmSKPD = $_REQUEST['fmSKPD']; 
		 $fmProgram = $_REQUEST['fmProgram']; 
		 $fmKegiatan = $_REQUEST['fmKegiatan'];
 		 $fmKdAkun = str_replace('.','',$_REQUEST['fmKdAkun']);
		 $fmThnAnggaran = $_REQUEST['fmThnAnggaran']; 
		 $fmSemester = $_REQUEST['fmSemester'];		
		 $fmBerdasarkan = $_REQUEST['fmBerdasarkan'];
		 $fmLimit = $_REQUEST['fmLimit'];
		if( $fmBerdasarkan == 2 && $fmLevel<3 ) $fmLevel = 3 ; 
		 $this->pagePerHal=$fmLimit;
		 $jns = $_REQUEST['jns'];
		$kdakun = $_REQUEST['kdakun'];
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		

		//kondisi -----------------------------------				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];				

		//Kondisi Tahun
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		if($fmBerdasarkan==2){
			if($Main->WITH_THN_ANGGARAN){
				$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
						thn_akun<=$fmThnAnggaran";
						$qry1=mysql_query($aqry1);			
						$qry_jurnal=mysql_fetch_array($qry1);
						$thn_akun=$qry_jurnal['thnMax'];
						$arrKondisi[] = " thn_akun = '$thn_akun'";
				//$KondThnAkun = " and thn_akun=$thn_akun ";
					
			}		
		}
		//if( $fmBerdasarkan == 2 ) $arrKondisi[] = " concat(ka, '.', kb, '.', kc, '.', kd, '.', ke) like '$kdakun%' ";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' WHERE '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			//case '': $arrOrders[] = " tgl DESC " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
			case '2': $arrOrders[] = " tahun_anggaran $Asc1 " ;break;			
		
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
	
}
$rekappemanfaatan = new rekappemanfaatanObj();

?>