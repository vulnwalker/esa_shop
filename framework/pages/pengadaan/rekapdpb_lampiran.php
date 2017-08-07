<?php

class Rekapdpb_lampiranObj  extends DaftarObj2{	
	var $Prefix = 'Rekapdpb_lampiran';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'dkb'; //daftar
	var $TblName_Hapus = 'dkb';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c','d');
	var $FieldSum = array('rencana','realisasi','selisih');//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array( 3,4,5); 
	var $FieldSum_Cp1 = array( 2, 2, 2);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0, 0);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Lampiran Rekap DPBMD';
	var $PageIcon = 'images/pengadaan_ico.png';
	var $ico_width = '';
	var $ico_height = '';	
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='rekapdpb_lampiran.xls';
	var $Cetak_Judul = 'Rekap DPB Lampiran';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Rekapdpb_lampiran_form'; 	
	var $totalCol = 5;
	
	
	function setTitle(){
		return 'Rekap DPB Lampiran ';
	}
	
	function setNavAtas(){
		return
		'';
	}	
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		$c = cekPOST('c');
		$d = cekPOST('d');
		$f = cekPOST('f');
		$g = cekPOST('g');
		$h = cekPOST('h');
		$i = cekPOST('i');
		$j = cekPOST('j');
		$k = cekPOST('k');
		$l = cekPOST('l');
		$m = cekPOST('m');
		$n = cekPOST('n');
		$o = cekPOST('o');
		$tahun = cekPOST('tahun');
		$berdasarkan = cekPOST('berdasarkan');	
		$sem = cekPOST('sem');		
		
		//KONDISI KODE BARANG
		$arrKondisiBarang = array();
		if(!($f=='' || $f=='00') )$arrKondisiBarang[] = "f='$f'";
		if(!($g=='' || $g=='00') )$arrKondisiBarang[] = "g='$g'";		
		if(!($h=='' || $h=='00') )$arrKondisiBarang[] = "h='$h'";		
		if(!($i=='' || $i=='00') )$arrKondisiBarang[] = "i='$i'";		
		if(!($j=='' || $j=='00') )$arrKondisiBarang[] = "j='$j'";
		$kondBarang= join(' and ',$arrKondisiBarang);		
		$kondBarang = $kondBarang =='' ? '':' where '.$kondBarang;
		
		//KONDISI KODE AKUN
		$arrKondisiAkun = array();
		if(!($k=='' || $k=='00') )$arrKondisiAkun[] = "k='$k'";	
		if(!($l=='' || $l=='00') )$arrKondisiAkun[] = "l='$l'";	
		if(!($m=='' || $m=='00') )$arrKondisiAkun[] = "m='$m'";	
		if(!($n=='' || $n=='00') )$arrKondisiAkun[] = "n='$n'";	
		if(!($o=='' || $o=='00') )$arrKondisiAkun[] = "o='$o'";
		$kondAkun= join(' and ',$arrKondisiAkun);
		$kondAkun = $kondAkun =='' ? '':' where '.$kondAkun;
		
		//KONDISI SKPD
		$arrKondisiSKPD = array();
		if(!($c=='' || $c=='00') ) $arrKondisiSKPD[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisiSKPD[] = "d='$d'";
		$kondSKPD= join(' and ',$arrKondisiSKPD);		
		$kondSKPD = $kondSKPD =='' ? '':' and '.$kondSKPD;	
		
		//KONDISI DKB
		if(!($tahun=='') ) $kondRencana = "and tahun='$tahun'";
		
		//KONDISI PENGADAAN		
		if($sem==1){//semester 1
			$kondRealisasi="and spk_tgl>='$tahun-01-01' and spk_tgl<='$tahun-06-31'";
		}elseif($sem==2){//semester 2
			$kondRealisasi="and spk_tgl>'$tahun-06-31' and spk_tgl<='$tahun-12-31'";
		}else{//semua semester 
			$kondRealisasi="and spk_tgl>='$tahun-01-01' and spk_tgl<='$tahun-12-31'";			
		}
		
		if($berdasarkan==2){//akun
			$kondisi_rencana =$kondAkun.' '.$kondSKPD.' '.$kondRencana;
			$kondisi_realisasi =$kondAkun.' '.$kondSKPD.' '.$kondRealisasi;	
		}else{//barang
			$kondisi_rencana =$kondBarang.' '.$kondSKPD.' '.$kondRencana;	
			$kondisi_realisasi =$kondBarang.' '.$kondSKPD.' '.$kondRealisasi;
		}	
		
		$aqry = "select c,d,e,e1,sum(rencana) as rencana,sum(realisasi) as realisasi,sum(rencana)-sum(realisasi) as selisih from ".
				"( ".
				"(select c,d,e,e1,jml_harga as rencana,0 as realisasi ".
				"from dkb ".
				"$kondisi_rencana ) ".
				"union ".
				"(select c,d,e,e1,0 as rencana,jml_harga as realisasi from pengadaan ".
				"$kondisi_realisasi ) ".
				")cc group by c,d,e,e1 $Order $Limit ";
//$Kondisi $Order $Limit ";	
		return $aqry;		
	}
	
	function setSumHal_query($Kondisi, $fsum){
		$c = cekPOST('c');
		$d = cekPOST('d');
		$f = cekPOST('f');
		$g = cekPOST('g');
		$h = cekPOST('h');
		$i = cekPOST('i');
		$j = cekPOST('j');
		$k = cekPOST('k');
		$l = cekPOST('l');
		$m = cekPOST('m');
		$n = cekPOST('n');
		$o = cekPOST('o');
		$tahun = cekPOST('tahun');
		$berdasarkan = cekPOST('berdasarkan');	
		$sem = cekPOST('sem');		
		
		//KONDISI KODE BARANG
		$arrKondisiBarang = array();
		if(!($f=='' || $f=='00') )$arrKondisiBarang[] = "f='$f'";
		if(!($g=='' || $g=='00') )$arrKondisiBarang[] = "g='$g'";		
		if(!($h=='' || $h=='00') )$arrKondisiBarang[] = "h='$h'";		
		if(!($i=='' || $i=='00') )$arrKondisiBarang[] = "i='$i'";		
		if(!($j=='' || $j=='00') )$arrKondisiBarang[] = "j='$j'";
		$kondBarang= join(' and ',$arrKondisiBarang);		
		$kondBarang = $kondBarang =='' ? '':' where '.$kondBarang;
		
		//KONDISI KODE AKUN
		$arrKondisiAkun = array();
		if(!($k=='' || $k=='00') )$arrKondisiAkun[] = "k='$k'";	
		if(!($l=='' || $l=='00') )$arrKondisiAkun[] = "l='$l'";	
		if(!($m=='' || $m=='00') )$arrKondisiAkun[] = "m='$m'";	
		if(!($n=='' || $n=='00') )$arrKondisiAkun[] = "n='$n'";	
		if(!($o=='' || $o=='00') )$arrKondisiAkun[] = "o='$o'";
		$kondAkun= join(' and ',$arrKondisiAkun);
		$kondAkun = $kondAkun =='' ? '':' where '.$kondAkun;
		
		//KONDISI SKPD
		$arrKondisiSKPD = array();
		if(!($c=='' || $c=='00') ) $arrKondisiSKPD[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisiSKPD[] = "d='$d'";
		$kondSKPD= join(' and ',$arrKondisiSKPD);		
		$kondSKPD = $kondSKPD =='' ? '':' and '.$kondSKPD;	
		
		//KONDISI DKB
		if(!($tahun=='') ) $kondRencana = "and tahun='$tahun'";
		
		//KONDISI PENGADAAN
		if($sem==1){//semester 1
			$kondRealisasi="and spk_tgl>='$tahun-01-01' and spk_tgl<='$tahun-06-31'";
		}elseif($sem==2){//semester 2
			$kondRealisasi="and spk_tgl>'$tahun-06-31' and spk_tgl<='$tahun-12-31'";
		}else{//semua semester 
			$kondRealisasi="and spk_tgl>='$tahun-01-01' and spk_tgl<='$tahun-12-31'";			
		}	
		
		if($berdasarkan==2){//akun
			$kondisi_rencana =$kondAkun.' '.$kondSKPD.' '.$kondRencana;
			$kondisi_realisasi =$kondAkun.' '.$kondSKPD.' '.$kondRealisasi;	
		}else{//barang
			$kondisi_rencana =$kondBarang.' '.$kondSKPD.' '.$kondRencana;	
			$kondisi_realisasi =$kondBarang.' '.$kondSKPD.' '.$kondRealisasi;
		}	
		//return "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		return "select $fsum from (".
		"select c,d,e,e1,sum(rencana) as rencana,sum(realisasi) as realisasi,sum(rencana)-sum(realisasi) as selisih from ".
				"( ".
				"(select c,d,e,e1,jml_harga as rencana,0 as realisasi ".
				"from dkb ".
				"$kondisi_rencana ) ".
				"union ".
				"(select c,d,e,e1,0 as rencana,jml_harga as realisasi from pengadaan ".
				"$kondisi_realisasi ) ".
				")cc group by c,d,e,e1)bb"; //echo $aqry;
		
		//return " select "
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
				   	   <th class='th01' width='500' >Nama SKPD/Sub Unit</th>					  
					   <th class='th01' width='100' >Rencana</th>					  
					   <th class='th01' width='200' >Realisasi</th>					   					   
					   <th class='th01' width='200' >Selisih</th>					   					   
					</tr>
					</thead>";
	
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 $c = cekPOST('c');	
	 $d = cekPOST('d');
   	 $f = cekPOST('f');
	 $g = cekPOST('g');
	 $h = cekPOST('h');
	 $i = cekPOST('i');
	 $j = cekPOST('j');
	 $tahun = cekPOST('tahun');
	 $query_c = "select * from ref_skpd where c='".$isi['c']."' and d='00' and e='00' and e1='000'";
	 $get_c=mysql_fetch_array(mysql_query($query_c));	 
	 $query_d = "select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='00' and e1='000'"; 
	 $get_d=mysql_fetch_array(mysql_query($query_d));
	 
	 $jml_barang = $dt['jml_barang'];
	 $jml_harga = $dt['jml_harga'];
	 $skpd = $get_c['nm_skpd'];	
	 $subunit = $get_d['nm_skpd'];
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 //if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
 	 $Koloms[] = array('align="left"',$skpd.' &nbsp/&nbsp '.$subunit);
 	 $Koloms[] = array('align="right" ',number_format($isi['rencana'],2,',','.')); 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="right" ',number_format($isi['realisasi'],2,',','.'));	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 $Koloms[] = array('align="right" ',number_format($isi['selisih'],2,',','.'));	 		  	 	  	 	 	 	 	 	 	 	 	 	 
 	 //$Koloms[] = array('align="right" ',$query_rkb);	 		  	 	  	 	 	 	 	 	 	 	 	 	 
	 return $Koloms;
	}
	
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		
		//if (sizeof($this->FieldSum)>0){
		if (sizeof($this->FieldSum)==1){
			
			$TampilTotalHalRp = $this->genRowSum_setTampilValue(0, $this->SumValue[0]);//number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total per Halaman</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total</td>": '';
			$Kanan2 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
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
						"<td class='$ColStyle' colspan='2' align='center'>$this->totalhalstr</td>": '';
					$ContentTotalHal .=	$Kiri1;
					$ContentTotal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='2' align='center'>$this->totalAllStr</td>":'';
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
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
			$ContentTotal .= $TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
			
			
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
		
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$c = $_GET['c'];
		$d = $_GET['d'];
		$f = $_GET['f'];
		$g = $_GET['g'];
		$h = $_GET['h'];
		$i = $_GET['i'];
		$j = $_GET['j'];
		$k = $_GET['k'];
		$l = $_GET['l'];
		$m = $_GET['m'];
		$n = $_GET['n'];
		$o = $_GET['o'];
		$tahun = $_GET['tahun'];
		$berdasarkan = $_GET['berdasarkan'];
		$sem = $_GET['sem'];
		return
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' id='c' name='c' value='$c'>".
				"<input type='hidden' id='d' name='d' value='$d'>".
				"<input type='hidden' id='f' name='f' value='$f'>".
				"<input type='hidden' id='g' name='g' value='$g'>".
				"<input type='hidden' id='h' name='h' value='$h'>".
				"<input type='hidden' id='i' name='i' value='$i'>".
				"<input type='hidden' id='j' name='j' value='$j'>".
				"<input type='hidden' id='k' name='k' value='$k'>".
				"<input type='hidden' id='l' name='l' value='$l'>".
				"<input type='hidden' id='m' name='m' value='$m'>".
				"<input type='hidden' id='n' name='n' value='$n'>".
				"<input type='hidden' id='o' name='o' value='$o'>".
				"<input type='hidden' id='tahun' name='tahun' value='$tahun'>".
				"<input type='hidden' id='berdasarkan' name='berdasarkan' value='$berdasarkan'>".
				"<input type='hidden' id='sem' name='sem' value='$sem'>".
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
		 
		$c = cekPOST('c');
		$d = cekPOST('d');
		$f = cekPOST('f');
		$g = cekPOST('g');
		$h = cekPOST('h');
		$i = cekPOST('i');
		$j = cekPOST('j');
		$k = cekPOST('k');
		$l = cekPOST('l');
		$m = cekPOST('m');
		$n = cekPOST('n');
		$o = cekPOST('o');
		$tahun = cekPOST('tahun');
		$berdasarkan = cekPOST('berdasarkan');
		$sem = cekPOST('sem');
		
		//get barang		
		$kd_barang = $f.'.'.$g.'.'.$h.'.'.$i.'.'.$j;		
		$qry_dt = "SELECT nm_barang FROM ref_barang WHERE f='$f' and g='$g' and h='$h' and i='$i' and j='$j'";
		$dt = mysql_fetch_array(mysql_query($qry_dt));
		$nm_barang = $dt['nm_barang'];		
		$arr_barang = array(							
					"<table>						
						<tr>
							<td width='100'>Kode Barang</td>
							<td> : </td>
							<td><input type='text' size='15' value='$kd_barang' id='kd_barang' name='kd_barang' readonly>
						</tr>
						<tr>
							<td width='100'>Nama Barang</td>
							<td> : </td>
							<td><input type='text' size='60' value='$nm_barang' id='nm_barang' name='nm_barang' readonly>
						</tr>
						<tr>
							<td width='100'>Tahun</td>
							<td> : </td>
							<td><input type='text' size='15' value='$tahun' id='fmTahun' name='fmTahun' readonly>
						</tr>
						<tr>
							<td width='100'>Semester</td>
							<td> : </td>
							<td><input type='text' size='15' value='$sem' id='fmsem' name='fmsem' readonly>
						</tr>
					</table>"
					);
		//--------------------------------------------
		
		//get akun
		$kd_akun = $k.'.'.$l.'.'.$m.'.'.$n.'.'.$o;
		$qry_dt2 = "SELECT nm_account FROM ref_jurnal WHERE ka='$k' and kb='$l' and kc='$m' and kd='$n' and ke='$o'";
		$dt2 = mysql_fetch_array(mysql_query($qry_dt2));
		$nm_akun = $dt2['nm_account'];
		$arr_akun = array(							
					"<table>						
						<tr>
							<td width='100'>Kode Akun</td>
							<td> : </td>
							<td><input type='text' size='15' value='$kd_akun' id='kd_akun' name='kd_akun' readonly>
						</tr>
						<tr>
							<td width='100'>Nama Akun</td>
							<td> : </td>
							<td><input type='text' size='60' value='$nm_akun' id='nm_akun' name='nm_akun' readonly>
						</tr>
						<tr>
							<td width='100'>Tahun</td>
							<td> : </td>
							<td><input type='text' size='15' value='$tahun' id='fmTahun' name='fmTahun' readonly>
								</tr>
						<tr>
							<td width='100'>Semester</td>
							<td> : </td>
							<td><input type='text' size='15' value='$sem' id='fmsem' name='fmsem' readonly>
								</tr>
					</table>"
					);
		//--------------------------------------------
		if($berdasarkan==2){
			$arr_ = $arr_akun;
		}else{
			$arr_ = $arr_barang;
		}
		
		$TampilOpt ="<input type='hidden' name='c' id='c' value='$c'>".
					"<input type='hidden' name='d' id='d' value='$d'>".			
					"<input type='hidden' id='f' name='f' value='$f'>".
					"<input type='hidden' id='g' name='g' value='$g'>".
					"<input type='hidden' id='h' name='h' value='$h'>".
					"<input type='hidden' id='i' name='i' value='$i'>".
					"<input type='hidden' id='j' name='j' value='$j'>".
					"<input type='hidden' id='k' name='k' value='$k'>".
					"<input type='hidden' id='l' name='l' value='$l'>".
					"<input type='hidden' id='m' name='m' value='$m'>".
					"<input type='hidden' id='n' name='n' value='$n'>".
					"<input type='hidden' id='o' name='o' value='$o'>".
					"<input type='hidden' id='tahun' name='tahun' value='$tahun'>".
					"<input type='hidden' id='berdasarkan' name='berdasarkan' value='$berdasarkan'>".			
					"<input type='hidden' id='sem' name='sem' value='$sem'>".			
			genFilterBar(
				$arr_,				
				$this->Prefix.".refreshList(true)",FALSE).
			genFilterBar(
				'',				
				$this->Prefix.".refreshList(true)",TRUE)
				;
		
		return array('TampilOpt'=>$TampilOpt);
	}		
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID'];		
		$c = cekPOST('c');
		$d = cekPOST('d');
		$f = cekPOST('f');
		$g = cekPOST('g');
		$h = cekPOST('h');
		$i = cekPOST('i');
		$j = cekPOST('j');
		$k = cekPOST('k');
		$l = cekPOST('l');
		$m = cekPOST('m');
		$n = cekPOST('n');
		$o = cekPOST('o');
		$tahun = cekPOST('tahun');
		$berdasarkan = cekPOST('berdasarkan');
		$sem = cekPOST('sem');
		if(!($c=='' || $c=='00') ) $arrKondisi[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisi[] = "d='$d'";		
		if(!($f=='' || $f=='00') ) $arrKondisi[] = "f='$f'";		
		if(!($g=='' || $g=='00') ) $arrKondisi[] = "g='$g'";		
		if(!($h=='' || $h=='00') ) $arrKondisi[] = "h='$h'";		
		if(!($i=='' || $i=='00') ) $arrKondisi[] = "i='$i'";		
		if(!($j=='' || $j=='00') ) $arrKondisi[] = "j='$j'";	
		if(!($k=='' || $k=='00') ) $arrKondisi[] = "k='$k'";	
		if(!($l=='' || $l=='00') ) $arrKondisi[] = "l='$l'";	
		if(!($m=='' || $m=='00') ) $arrKondisi[] = "m='$m'";	
		if(!($n=='' || $n=='00') ) $arrKondisi[] = "n='$n'";	
		if(!($o=='' || $o=='00') ) $arrKondisi[] = "o='$o'";	
		if(!($tahun=='' || $tahun=='00') ) $arrKondisi[] = "tahun='$tahun' GROUP BY c,d,e";		
		
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
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script src='js/jquery-ui.custom.js'></script>".		    
			"<script type='text/javascript' src='js/pengadaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			$scriptload;
	}	
		
	
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$c = cekPOST('c');
		$d = cekPOST('d');
		$f = cekPOST('f');
		$g = cekPOST('g');
		$h = cekPOST('h');
		$i = cekPOST('i');
		$j = cekPOST('j');
		$k = cekPOST('k');
		$l = cekPOST('l');
		$m = cekPOST('m');
		$n = cekPOST('n');
		$o = cekPOST('o');
		$tahun = cekPOST('tahun');
		$berdasarkan = cekPOST('berdasarkan');
		$sem = cekPOST('sem');
		
		//get barang		
		$kd_barang = $f.'.'.$g.'.'.$h.'.'.$i.'.'.$j;		
		$qry_dt = "SELECT nm_barang FROM ref_barang WHERE f='$f' and g='$g' and h='$h' and i='$i' and j='$j'";
		$dt = mysql_fetch_array(mysql_query($qry_dt));
		$nm_barang = $dt['nm_barang'];		
		$arr_barang ="<table>						
						<tr>
							<td width='100'>Kode Barang</td>
							<td> : </td>
							<td>$kd_barang
						</tr>
						<tr>
							<td width='100'>Nama Barang</td>
							<td> : </td>
							<td>$nm_barang
						</tr>
						<tr>
							<td width='100'>Tahun</td>
							<td> : </td>
							<td>$tahun
						</tr>
						<tr>
							<td width='100'>Semester</td>
							<td> : </td>
							<td>$sem
						</tr>
					</table>"
					;
		//--------------------------------------------
		
		//get akun
		$kd_akun = $k.'.'.$l.'.'.$m.'.'.$n.'.'.$o;
		$qry_dt2 = "SELECT nm_account FROM ref_jurnal WHERE ka='$k' and kb='$l' and kc='$m' and kd='$n' and ke='$o'";
		$dt2 = mysql_fetch_array(mysql_query($qry_dt2));
		$nm_akun = $dt2['nm_account'];
		$arr_akun ="<table>						
						<tr>
							<td width='100'>Kode Akun</td>
							<td> : </td>
							<td>$kd_akun
						</tr>
						<tr>
							<td width='100'>Nama Akun</td>
							<td> : </td>
							<td>$nm_akun
						</tr>
						<tr>
							<td width='100'>Tahun</td>
							<td> : </td>
							<td>$tahun
								</tr>
					</table>"
					;
		//--------------------------------------------
		if($berdasarkan==2){
			$arr_ = $arr_akun;
		}else{
			$arr_ = $arr_barang;
		}
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>	
			".$arr_."<br>";
	}
	
	function setCetak_footer($xls=FALSE){
		$NIPSKPD = "";
	    $NAMASKPD = "";
	    $JABATANSKPD = "";
	    $TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
	    if (c == '04') {
	        $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd1 = '1' ");
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
			$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='KEPALA $this->namaModulCetak'	STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";
			$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='PETUGAS $this->namaModulCetak' STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >";	    	
		}else{
			$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
			$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
			$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD1</span>";
			$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
			$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
			$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
			$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA $this->namaModulCetak</span>";
			$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PETUGAS $this->namaModulCetak</span>";
	    	
		}
		$Hsl = " <br><br><table style='width:$pagewidth' border=0>
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
	
}
$Rekapdpb_lampiran = new Rekapdpb_lampiranObj();

?>