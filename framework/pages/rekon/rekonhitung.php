<?php

class RekonhitungObj  extends DaftarObj2{	
	var $Prefix = 'Rekonhitung';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'Rekonhitung'; //daftar
	var $TblName_Hapus = 'Rekonhitung';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Cetak Surat';
	var $PageIcon = '';
	var $pagePerHal ='';
	var $fileNameExcel='Rekonhitung.xls';
	var $Cetak_Judul = 'Cetak Surat';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'RekonhitungForm';	
	var $WITH_HAL=FALSE;
	var $TampilFilterColapse = 0; //0
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			"<script type='text/javascript' src='js/rekon/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
		
	function setTitle(){
		return '';
	}
	
	function setMenuEdit(){		
		return
		"";
	}
	
	function setMenuView(){
		return 
		"";
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		return array('TampilOpt'=>$TampilOpt);
	}		
	
	
	function setPage_HeaderOther(){
		return 
			"";
	}	
		
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		switch($tipe){	
			case 'cetakhitung':{		
				$json= FALSE;
				$this->cetakhitung();							
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
	
	function cetakhitung(){
		$width='21cm';
		$height='33cm';
		$font_size='11';
		
		$c = $_REQUEST['RekonSkpdfmSKPD'];
		$d = $_REQUEST['RekonSkpdfmUNIT'];
		$thn_anggaran = $_REQUEST['tahun'];
		$arrKond = array();
		//$arrKond[] = " kint='01'";
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		//if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		//if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond); 
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '. $KondisiSKPD;
		
		
		$isi_head ="<title>$Main->Judul</title>
				<!--<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />-->
				<style>
table.rangkacetak {
	background-color: #FFFFFF;
	margin: 0cm;
	padding: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	font-family : Arial, Helvetica, sans-serif;
	
}
table.rangkacetak td {
	font-size:6pt;
}
table.cetak {
	background-color: #FFFFFF;
	font-family : Arial, Helvetica, sans-serif;
	margin: 0px;
	padding: 0px;
	border: 1px solid;
	width: 30cm;
	border-collapse: collapse;
	color: #000000;
	font-size : 6pt;
}
table.cetak th.th01 {
	margin: 0px;
	padding: 0px;	
	border: 1px solid #000000;
	font-size : 6pt;
	color: #000000;
	text-align: center;
	background-color: #FFFFFF;
}
table.cetak th.th02 {
	margin: 0px;
	padding: 2px;	
	border: 1px solid #000000;
	font-size : 6pt;
	color: #000000;
	text-align: center;
	background-color: #FFFFFF;
}
table.cetak tr.row0 {
	background-color: #EBEBEB;
	text-align: left;
	border: 1px solid #000000;
}
table.cetak td {
	font-size:6pt;
	border: 1px solid #000000;
}
table.cetak input {
	font-family: Arial Narrow;
	font-size: 10pt;
}
				</style>";
		$border_bottom="align='right' style='border-bottom:1px solid;'";
		
		//$qry = "SELECT * FROM ref_barang WHERE f<>00 and g='00' and f<>07";
		$qry = 
			"select aa.kint,aa.ka,aa.kb,aa.f,aa.nm_barang,

			debet_saldoawal_brg, kredit_saldoawal_brg, debet_saldoawal, 
			kredit_saldoawal, (debet_saldoawal -kredit_saldoawal) as saldoawal, 
			debet, kredit, kredit_lain,	
			(debet_saldoawal -kredit_saldoawal + debet - kredit) as saldoakhir, 
			debet1, kredit1,	
			debet2, kredit2, 
			kredit_lain1, kredit_lain2, 
			kredit_ekstra1, kredit_ekstra2, 
			ifnull(debet_koreksi,0) as debet_koreksi, ifnull(kredit_koreksi,0) as kredit_koreksi, 
			ifnull(dkoreksi_wajar,0) as dkoreksi_wajar, ifnull(kkoreksi_wajar,0) as kkoreksi_wajar, 
			dkoreksi_lain, kkoreksi_lain, 
			(debet_saldoawal_lain - kredit_saldoawal_lain) as saldoawal_lain, 
			ifnull(dkoreksi_wajar_awal,0) as dkoreksi_wajar_awal, ifnull(kkoreksi_wajar_awal,0) as kkoreksi_wajar_awal,
			(debet_saldoawal -kredit_saldoawal + ifnull(dkoreksi_wajar_awal,0) - ifnull(kkoreksi_wajar_awal,0) ) as saldoawal_wajar
						
			
			
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
				sum(if( tgl_buku>='$thn_anggaran-07-01' && tgl_buku<='$thn_anggaran-12-31' && jns_trans<>10 && jns_trans=3 ,kredit,0)) as kredit_ekstra2,
				
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),debet,0)) as debet_saldoawal_lain, 
				sum(if(year(tgl_buku)<'$thn_anggaran' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),kredit,0)) as kredit_saldoawal_lain
				
				from t_jurnal_aset
				where  kint='01' and ka='01' $KondisiSKPD
				group by f 
			)bb	on aa.f =bb.f
			
			left join  (
				SELECT IFNULL(f,'00') as f, 
			  	sum(if( tahun=$thn_anggaran && jns_nilai=0 , debet,0 )) as debet_koreksi ,  
			  	sum(if( tahun=$thn_anggaran && jns_nilai=0 , kredit,0 )) as kredit_koreksi, 
				sum(if( tahun=$thn_anggaran && jns_nilai=1 , debet,0 )) as dkoreksi_wajar ,  
			  	sum(if( tahun=$thn_anggaran && jns_nilai=1 , kredit,0 )) as kkoreksi_wajar, 
				sum(if( tahun=$thn_anggaran && jns_nilai=2 , debet,0 )) as dkoreksi_lain ,  
			  	sum(if( tahun=$thn_anggaran && jns_nilai=2 , kredit,0 )) as kkoreksi_lain,
				
				sum(if( tahun<$thn_anggaran && jns_nilai=1 , ifnull(debet,0),0 )) as dkoreksi_wajar_awal ,  
			  	sum(if( tahun<$thn_anggaran && jns_nilai=1 , ifnull(kredit,0),0 )) as kkoreksi_wajar_awal
				
			  	from t_rekon_koreksi where c= '08' and d= '01'  group by f
			)cc on aa.f=cc.f  

			
			where aa.kint='01' and aa.ka='01' and aa.g='00' and aa.f<>'00'"; //echo $qry;
			
		$get_qry = mysql_query($qry);
		//$no=1;
		$saldowal = 0;$saldoawal_lain = 0 ;
		$debet1 = 0; $kredit1 = 0;
		$totalSem1 = 0;
		
		while($row = mysql_fetch_array($get_qry)){
			$no++;
			$kol1 = $row['saldoawal'];
			$kol2 = $row['saldoawal_lain'];
			$kol3 = $row['debet1'];
			$kol4 = $row['kredit1'];
			$kol5 = $kol1 + $kol2 + $kol3 - $kol4;
			$kol6 = $row['saldoawal']  + ($row['debet_koreksi'] - $row['kredit_koreksi']);
			$kol7 = $row['saldoawal_wajar'] + ( $row['dkoreksi_wajar']- $row['kkoreksi_wajar'] );
			$kol8 = $row['saldoawal_lain'] + ( $row['dkoreksi_lain']- $row['kkoreksi_lain'] ) ;
			$kol9 = $kol6 + $kol8;
			$kol10 = $kol9  - $kol5;
			$kol11 = $row['debet2'];
			$kol12 = $row['kredit2'] ;
			$kol13 = $row['kredit_lain2']+$row['kredit_ekstra2'];
			$kol14 = $kol7 + $kol11 -($kol12+$kol13);
			
			$golongan.="
			<tr>
				<td>".$row['nm_barang']."</td>
				<td width='75' align='right'>".number_format($kol1,2,',','.')."</td>
				<td width='75' align='right'>".number_format($kol2,2,',','.')."</td>
				<td width='75' align='right'>".number_format($kol3,2,',','.')."</td>				
				<td width='75' align='right'>".number_format($kol4,2,',','.')."</td>
				<td width='75' align='right'>".number_format($kol5,2,',','.')."</td>
				
				<td width='75' align='right'>".number_format($kol6,2,',','.')."</td>
				<td width='75' align='right'>".number_format( $kol7  ,2,',','.')."</td>
				<td width='75' align='right'>".number_format( $kol8 ,2,',','.')."</td>				
				<td width='75' align='right'>".number_format( $kol9,2,',','.')."</td>
				<td width='75' align='right'>".number_format( $kol10 ,2,',','.')."</td>
				
				<td width='75' align='right'>".number_format($kol11,2,',','.')."</td>
				<td width='75' align='right'>".number_format($kol12,2,',','.')."</td>
				<td width='75' align='right'>".number_format($kol13 ,2,',','.')."</td>
				<td width='75' align='right'>".number_format( $kol14 ,2,',','.')."</td>
			</tr>";
			
			$tot_kol1 +=$kol1;
			$tot_kol2 +=$kol2;
			$tot_kol3 += $kol3;
			$tot_kol4 += $kol4;
			
			$tot_kol5 +=$kol5;
			$tot_kol6 +=$kol6;
			$tot_kol7 +=$kol7;
			$tot_kol8 +=$kol8;
			$tot_kol9 +=$kol9;
			$tot_kol10 +=$kol10;
			$tot_kol11 +=$kol11;
			$tot_kol12 +=$kol12;
			$tot_kol13 +=$kol13;
			$tot_kol14 +=$kol14;
			
			$rowtotal=
				"<tr >
					<td width='200' align='right'>TOTAL</td>
					<td width='75' align='right'>".number_format($tot_kol1,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol2,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol3,2,',','.')."</td>				
					<td width='75' align='right'>".number_format($tot_kol4,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol5,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol6,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol7,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol8,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol9,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol10,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol11,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol12,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol13,2,',','.')."</td>
					<td width='75' align='right'>".number_format($tot_kol14,2,',','.')."</td>					
				</tr>";
			
				if($no==1){
					$golongan2.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td align='right'>".number_format($kol1,2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td >Jumlah aset tetap 31 DESEMBER $thn_anggaran</td>
						 <td $border_bottom><!--jumlah_aset_tetap--></td>
						 <td ></td>
					</tr>";
				}elseif($no==6){
					$golongan2.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td $border_bottom>".number_format($kol1,2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
					</tr>";
				}else{
					$golongan2.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td align='right'>".number_format($kol1,2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td align='right'></td>
						 <td ></td>
					</tr>";
				}
				if($no==6){
					$golongan3.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td $border_bottom>".number_format($kol2,2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
					</tr>";
				}else{
					$golongan3.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td align='right'>".number_format($kol2,2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td align='right'></td>
						 <td ></td>
					</tr>";
				}
				
				$golongan4.="
					<tr>
						 <td >".$row['nm_barang']."</td>
						 <td align='right'>".number_format( ($kol3-$kol4) + ($kol11-$kol12-$kol13),2,',','.')."</td>
						 <td ></td>
						 <td ></td>
						 <td ></td>
						 <td align='right'></td>
						 <td ></td>
					</tr>";
			/*$golongan2.="
			<tr>
					 <td >".$row['nm_barang']."</td>
					 <td $border_bottom>-</td>
					 <td ></td>
					 <td ></td>
					 <td >Jumlah aset tetap 31 DESEMBER $thn_anggaran</td>
					 <td $border_bottom>-</td>
					 <td ></td>
				</tr>";*/
		}
		
		$golongan2 = str_replace( '<!--jumlah_aset_tetap-->', number_format( ($tot_kol7)+($tot_kol3-$tot_kol4) +($tot_kol11-($tot_kol12+$tot_kol13)),2,',','.'), $golongan2);
		$isi_body="
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td align='center'><b>PEMERINTAH KABUPATEN GARUT</b></td>
				</tr>
				<tr>
					 <td align='center'><b>REKONSILIASI ASET TETAP</b></td>
				</tr>
				<tr>
					 <td align='center'><b>.............................................................................................</b></td>
				</tr> 				
			</table>
			<br>						
			<table border='0' class='cetak' style='width:100%;' >	
				<tr >
					<th class='th01' rowspan='8' width='200'>Nama Aset Tetap</th>
					<th class='th02' colspan='2' width='150'>SALDO AWAL 1 JANUARI $thn_anggaran</th>
					<th class='th01' colspan='2' width='150'>MUTASI MTS I</th>
					<th class='th01' rowspan='2' width='75'>PERHITUNGAN MENURUT SKPD</th>									
					<th class='th02' colspan='3' width='225'>NILAI ASET PER 30 JUNI $thn_anggaran (HASIL REKONSILIASI)</th>
					<th class='th01' rowspan='2' width='75'>PERHITUNGAN MENURUT REKON</th>									
					<th class='th01' rowspan='2' width='75'>KOREKSI PENAMBAHAN (PENGURANGAN)</th>									
					<th class='th02' colspan='3' width='225'>MUTASI MTS II</th>
					<th class='th01' rowspan='2' width='100'>Saldo 31/12/$thn_anggaran</th>									
				</tr>
				<tr>
					<th class='th02' width='75'>BARANG PRODUKTIF</th>
					<th class='th02' width='75'>BARANG TIDAK PRODUKTIF/IDLE</th>
					<th class='th01' width='75'>Penambahan</th>
					<th class='th01' width='75'>Pengurangan</th>
					<th class='th01' width='75'colspan='2'>PRODUKTIF</th>
					<th class='th01' width='75'>TIDAK PRODUKTIF/IDLE</th>
					<th class='th01' width='75'>Penambahan</th>
					<th class='th01' width='150' colspan='2'>Pengurangan</th>
				<tr>
				<tr>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'></th>
					<th class='th01' width='75'>Penghapusan</th>
					<th class='th01' width='75'>Reklas</th>
					<th class='th01' width='75'></th>
				<tr>
				<tr>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NW</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NW</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NP</th>
					<th class='th01' width='75'>NW</th>
					<th class='th01' width='75'>NW</th>
					<th class='th01' width='75'>NW</th>
					<th class='th01' width='75'>NW</th>
				<tr>
				<tr>
					<th class='th01' width='75'>(1)</th>
					<th class='th01' width='75'>(2)</th>
					<th class='th01' width='75'>(3)</th>
					<th class='th01' width='75'>(4)</th>
					<th class='th01' width='75'>(5)<br>=(1)+(2)+(3)+(4)</th>
					<th class='th01' width='75'>(6)</th>
					<th class='th01' width='75'>(7)</th>
					<th class='th01' width='75'>(8)</th>
					<th class='th01' width='75'>(9)<br>=(6)+(8)</th>
					<th class='th01' width='75'>(10)<br>=(9)-(5)</th>
					<th class='th01' width='75'>(11)</th>
					<th class='th01' width='75'>(12)</th>
					<th class='th01' width='75'>(13)</th>
					<th class='th01' width='75'>(14)<br>=(7)+(11)-(12)-(13)</th>
				<tr>
				".$golongan."
				$rowtotal
			</table>
			<br>
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td >Penjelasan:</td>
				</tr>
				<tr>
					 <td width='260'><i>Cara 1:***</i></td>
					 <td width='85' ></td>
					 <td width='80' ></td>
					 <td width='100'></td>
					 <td width='250'><i>Cara 2:</i></td>
					 <td width='100' ></td>
					 <td width='400'></td>
				</tr>				
				<tr>
					 <td ><b>Saldo awal 1 Januari $thn_anggaran</b></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td >Nilai wajar aset tetap per 30 Juni $thn_anggaran</td>
					 <td align='right'>".number_format($tot_kol7,2,',','.')."</td>
					 <td ></td>
				</tr>
				<tr>
					 <td ><b>Aset Produktif Per 1 Januari $thn_anggaran</b></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td >Mutasi 1 Juli sampan dengan 30 DESEMBER $thn_anggaran</td>
					 <td $border_bottom>".number_format( ($tot_kol3-$tot_kol4) +($tot_kol11-($tot_kol12+$tot_kol13)),2,',','.')."</td>
					 <td ></td>
				</tr>
				".$golongan2."
				<tr>
					 <td ><b>Jumlah Aset Produktif</b></td>
					 <td ></td>
					 <td align='right'>".number_format($tot_kol1,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td ><b>Aset Tidak Produktif Per 1 Januari $thn_anggaran</b></td>
					 <td align='right'></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				".$golongan3."				
				<tr>
					 <td ><b>Jumlah Aset tidak Produktif</b></td>
					 <td ></td>
					 <td align='right'>".number_format($tot_kol2,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td ><i>Koreksi penambahan (pengurangan)</i></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr><tr>
					 <td  colspan='2'>Penyesuaian nilai perolehan menurut SKPD dengan hasil rekonlisilliasi *</td>
					 <td align='right'>".number_format($tot_kol10,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >Kenaikan (penurunan) aset tetap akibat penilaian**</td>
					 <td ></td>
					 <td align='right'>".number_format($tot_kol7-$tot_kol6,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >Aset tidak produktif per 30 Juni $thn_anggaran</td>
					 <td ></td>
					 <td $border_bottom>".number_format($tot_kol8,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td  colspan='2'><b>Saldo awal Aset Tetap Per 1 Januari $thn_anggaran setelah koreksi</b></td>
					 <td align='right'>".number_format($tot_kol1 + $tot_kol2 + $tot_kol10+ ($tot_kol7-$tot_kol6)  ,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >Mutasi 1 Januari-31 Oktober $thn_anggaran :</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				".$golongan4."				
				<tr>
					 <td ><b>Jumlah Mutasi</b></td>
					 <td ></td>
					 <td $border_bottom>".number_format( ($tot_kol3-$tot_kol4) + ($tot_kol11-$tot_kol12-$tot_kol13),2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td ><b>Saldo Akhir Aset Tetap Per 31 Desember $thn_anggaran</b></td>
					 <td ></td>
					 <td $border_bottom>".number_format(
					 	$tot_kol1 + $tot_kol2 + $tot_kol10+ ($tot_kol7-$tot_kol6) +  
						($tot_kol3-$tot_kol4) + ($tot_kol11-$tot_kol12-$tot_kol13) ,2,',','.')."</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >]</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >keterangan :</td>
					 <td ></td>
					 <td ></td>
					 <td >Mengetahui,</td>
					 <td ></td>
					 <td ></td>
					 <td ></td>
				</tr>
				<tr>
					 <td >)* lihat kolom 10</td>
					 <td ></td>
					 <td ></td>
					 <td colspan='2'>Kasubag Keuangan/Pengurus Barang</td>
					 <td colspan='2'>Kepala Bidang Pengelolaan Aset Selaku Pembantu Pengelola Barang</td>
				</tr>
				<tr>
					 <td >)** kolom 7 minus kolom 6</td>
					 <td ></td>
					 <td ></td>
					 <td colspan='2'></td>
					 <td colspan='2'></td>
				</tr>
				<tr>
					 <td >)*** rincian dapat dilihat pada lampiran 1</td>
					 <td ></td>
					 <td ></td>
					 <td colspan='2'></td>
					 <td colspan='2'></td>
				</tr>
				<tr>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td colspan='2'>.............</td>
					 <td colspan='2'>H. AYI ROSYAD, S.IP M.Si.</td>
				</tr>
				<tr>
					 <td ></td>
					 <td ></td>
					 <td ></td>
					 <td colspan='2'>NIP</td>
					 <td colspan='2'>NIP. 197207071999011001</td>
				</tr>
			</table>	
				";
		$isi="<html>
				<head>
					$isi_head
				</head>
				<body>
					$isi_body
				</body>
			  </html>
				";			
		
		echo "<div>$isi</div>";
		//echo "<div style='width:$width;height:$height;'>$isi</div>";
	}
		
	/*function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		return
			$Main->kopLaporan= 
					"<table border='0' class='rangkacetak' style='width:100%;'>
						<tr>
						 <td>
						 	<span style='font-size:12;'><b>KEMENTRIAN ENERGI DAN SUMBERDAYA MINERAL</b></span><br>
						 	<span style='font-size:14;'><b>BADAN GEOLOGI</b></span><br>
						 	<span style='font-size:14;'><b>SEKRETARIAT BADAN GEOLOGI</b></span><br>
							<span style='font-size:14;'>Jl. Diponegoro No.57 Bandung
						 </td>
						 <td width=43%>	
						 	<span style='font-size:8;'>LAMPIRAN I</span><br>
						 	<div style='text-align:justify;text-justify:auto;'>
						    	<span style='font-size:8;'>PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA NOMOR 113/PMK.05/2012 TENTANG PERJALAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT NEGARA ,PEGAWAI NEGERI DAN PEGAWAI TIDAK TETAP</span>
						 	</div>
						 </td>
						</tr>
					</table>";
	}  */	
			
}
$Rekonhitung = new RekonhitungObj();
?>