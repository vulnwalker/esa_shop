<?php

class Rekonlampiran1Obj  extends DaftarObj2{	
	var $Prefix = 'Rekonlampiran1';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'Rekonlampiran1'; //daftar
	var $TblName_Hapus = 'Rekonlampiran1';
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
	var $fileNameExcel='Rekonlampiran1.xls';
	var $Cetak_Judul = 'Cetak Surat';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Rekonlampiran1Form';	
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
			case 'cetaklampiran':{		
				$json= FALSE;
				$this->cetaklampiran();							
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
	
	function cetaklampiran(){
		$c = $_REQUEST['RekonSkpdfmSKPD'];
		$d = $_REQUEST['RekonSkpdfmUNIT'];
		
		$tahun = $_REQUEST['tahun'];
		$arrKond = array();
		//$arrKond[] = " kint='01'";
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		//if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		//if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond); 
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '. $KondisiSKPD;
		
		
		
		$width='21cm';
		$height='33cm';
		$font_size='11';
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
	font-size:7pt;
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
	font-size : 7pt;
}
table.cetak th.th01 {
	margin: 0px;
	padding: 10px;
	height: 25px;
	border: 1px solid #000000;
	font-size : 7pt;
	text-align: center;
	background-color: #FFFFFF;
}
table.cetak tr.row0 {
	background-color: #EBEBEB;
	text-align: left;
	border: 1px solid #000000;
}
table.cetak td {
	font-size:7pt;
	border: 1px solid #000000;
	padding:2px 2px 2px 3px;
}
table.cetak input {
	font-family: Arial Narrow;
	font-size: 10pt;
}
				</style>";
				
		
		
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
			(debet_saldoawal -kredit_saldoawal + ifnull(dkoreksi_wajar_awal,0) - ifnull(kkoreksi_wajar_awal,0) ) as saldoawal_wajar,
			
			bm
						
			
			
			  from v_ref_kib_keu aa
			
			left join(			
				select IFNULL(f,'00') as f,
				sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10,jml_barang_d,0)) as debet_saldoawal_brg, 
				sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10,jml_barang_k,0)) as kredit_saldoawal_brg,
			    sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10,debet,0)) as debet_saldoawal, 
				sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10,kredit,0)) as kredit_saldoawal,
			    sum(if(year(tgl_buku)='$tahun' && jns_trans<>10,debet,0)) as debet, 
				sum(if(year(tgl_buku)='$tahun' && jns_trans<>10 ,kredit  ,0)) as kredit,
				#sum(if(year(tgl_buku)='$tahun' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9,kredit  ,0)) as kredit,
				sum(if(year(tgl_buku)='$tahun' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),kredit  ,0)) as kredit_lain,
				
				sum(if( tgl_buku>='$tahun-01-01' && tgl_buku<'$tahun-07-01' && jns_trans<>10 ,debet,0)) as debet1, 
				sum(if( tgl_buku>='$tahun-01-01' && tgl_buku<'$tahun-07-01' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9  ,kredit,0)) as kredit1,				
				sum(if( tgl_buku>='$tahun-01-01' && tgl_buku<'$tahun-07-01' && jns_trans<>10 && jns_trans=9  ,kredit,0)) as kredit_lain1,
				sum(if( tgl_buku>='$tahun-01-01' && tgl_buku<'$tahun-07-01' && jns_trans<>10 && jns_trans=3  ,kredit,0)) as kredit_ekstra1,
			
			    sum(if( tgl_buku>='$tahun-07-01' && tgl_buku<='$tahun-12-31' && jns_trans<>10,debet,0)) as debet2, 
				sum(if( tgl_buku>='$tahun-07-01' && tgl_buku<='$tahun-12-31' && jns_trans<>10 && jns_trans<>3 && jns_trans<>9 ,kredit,0)) as kredit2,
				sum(if( tgl_buku>='$tahun-07-01' && tgl_buku<='$tahun-12-31' && jns_trans<>10 && jns_trans=9 ,kredit,0)) as kredit_lain2,
				sum(if( tgl_buku>='$tahun-07-01' && tgl_buku<='$tahun-12-31' && jns_trans<>10 && jns_trans=3 ,kredit,0)) as kredit_ekstra2,
				
				sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),debet,0)) as debet_saldoawal_lain, 
				sum(if(year(tgl_buku)<'$tahun' && jns_trans<>10 && (jns_trans=3 or jns_trans=9),kredit,0)) as kredit_saldoawal_lain,
				
				sum(if( year(tgl_buku)<'$tahun' && jns_trans=1 ,debet,0)) as bm ,
				sum(if( year(tgl_buku)<'$tahun' && jns_trans=3 ,debet,0)) as pelihara ,
				sum(if( year(tgl_buku)<'$tahun' && jns_trans=4 or jns_trans=5 or jns_trans=6 or jns_trans=8 ,debet,0)) as hibah 
				
				from t_jurnal_aset
				where  kint='01' and ka='01' $KondisiSKPD
				group by f 
			)bb	on aa.f =bb.f
			
			left join  (
				SELECT IFNULL(f,'00') as f, 
			  	sum(if( tahun=$tahun && jns_nilai=0 , debet,0 )) as debet_koreksi ,  
			  	sum(if( tahun=$tahun && jns_nilai=0 , kredit,0 )) as kredit_koreksi, 
				sum(if( tahun=$tahun && jns_nilai=1 , debet,0 )) as dkoreksi_wajar ,  
			  	sum(if( tahun=$tahun && jns_nilai=1 , kredit,0 )) as kkoreksi_wajar, 
				sum(if( tahun=$tahun && jns_nilai=2 , debet,0 )) as dkoreksi_lain ,  
			  	sum(if( tahun=$tahun && jns_nilai=2 , kredit,0 )) as kkoreksi_lain,
				
				sum(if( tahun<$tahun && jns_nilai=1 , ifnull(debet,0),0 )) as dkoreksi_wajar_awal ,  
			  	sum(if( tahun<$tahun && jns_nilai=1 , ifnull(kredit,0),0 )) as kkoreksi_wajar_awal
				
			  	from t_rekon_koreksi where c= '08' and d= '01'  group by f
			)cc on aa.f=cc.f  

			
			where aa.kint='01' and aa.ka='01' and aa.g='00' and aa.f<>'00'"; //echo $qry;
		$get_qry = mysql_query($qry);
		$jml_saldoawal=0; $jml_debet = 0; $jml_kredit=0; $jml_saldoakhir=0;
		$jml_saldoawal2=0; $jml_debet2 = 0; $jml_kredit2=0; $jml_saldoakhir2=0;
		$jml_saldoawal3=0; $jml_debet3 = 0; $jml_kredit3=0; $jml_saldoakhir3=0;
		while($row = mysql_fetch_array($get_qry)){
			$rowheader .= "<th class='th01' align='center' width='170'>".$row['nm_barang']."</th>";
			$saldoawal .= "<td align='right'>".number_format($row['saldoawal'],2,',','.')."</td>";
			$totSaldoAwal_ +=$row['saldoawal'];
			
			
			$koreksi .= "<td align='right'>".number_format($row['debet_koreksi'] - $row['kredit_koreksi'],2,',','.')."</td>";
			$totKoreksi_ += $row['debet_koreksi'] - $row['kredit_koreksi'];
			$tdkproduktif .= "<td align='right'>".number_format($row['dkoreksi_lain'] - $row['kkoreksi_lain'],2,',','.')."</td>";
			$totTdkProduktif_ += $row['dkoreksi_lain'] ;
			$ekstra .= "<td align='right'>".number_format(0,2,',','.')."</td>";
			$totEkstra_ += 0;
			$saldoAwalKoreksi_ = $row['saldoawal'] + ($row['debet_koreksi'] - $row['kredit_koreksi']) +
				($row['dkoreksi_lain'] - $row['kkoreksi_lain'])		;
			
			$saldoAwalKoreksi .= "<td align='right'><b>".number_format( $saldoAwalKoreksi_,2,',','.')."</b></td>";
			
			$totSaldoAwalKoreksi_ += $saldoAwalKoreksi_;
			$bmAPBD .= "<td align='right'>".number_format( $row['bm'],2,',','.')."</td>";
			$totBmAPBD_ += $row['bm'];
			
			$pelihara .= "<td align='right'>".number_format( $row['pelihara'],2,',','.')."</td>";
			$totPelihara_ += $row['pelihara'];
			
			$hibah .= "<td align='right'>".number_format( $row['hibah'],2,',','.')."</td>";
			$totHibah_ +=$row['hibah'];
			$jmlMutasiTambah_ = $row['bm'] + $row['pelihara'] + $row['hibah'];
			$jmlMutasiTambah .= "<td align='right'>".number_format( $jmlMutasiTambah_,2,',','.')."</td>";			
			$totJmlMutasiTambah_ += $jmlMutasiTambah_;
			
			$kreditLain .= "<td align='right'>".number_format( $row['kredit_lain1'] + $row['kredit_lain2'],2,',','.')."</td>";
			$totKreditLain_ += $row['kredit_lain1'] + $row['kredit_lain2'];
			
			$kreditEkstra .= "<td align='right'>".number_format( $row['kredit_ekstra1'] + $row['kredit_ekstra2'],2,',','.')."</td>";
			$totKreditEkstra_ += $row['kredit_ekstra1'] + $row['kredit_ekstra2'];
			
			$mutasiKurang_ = $row['kredit_lain1'] + $row['kredit_lain2']+$row['kredit_ekstra1'] + $row['kredit_ekstra2'];
			$mutasiKurang .= "<td align='right'>".number_format( $mutasiKurang_,2,',','.')."</td>";
			$totMutasiKurang_ += $mutasiKurang_;
			
			
			$tot_ = $saldoAwalKoreksi_ + $jmlMutasiTambah_ - $mutasiKurang_;
			$tot .= "<td align='right'>".number_format( $tot_,2,',','.')."</td>";
			$jmlTot_ +=  $tot_;
			
			
			/*<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>*/
					
		}
		
		$totSaldoAwal = number_format($totSaldoAwal_,2,',','.');
		$totKoreksi  = number_format($totKoreksi_,2,',','.');
		$totBmAPBD = number_format($totBmAPBD_,2,',','.');
		$totEkstra  = number_format($totEkstra_,2,',','.');
		$totTdkProduktif  = number_format($totTdkProduktif_,2,',','.');
		$totSaldoAwalKoreksi  = number_format($totSaldoAwalKoreksi_,2,',','.');
		$totPelihara = number_format($totPelihara_,2,',','.');
		$totHibah = number_format($totHibah_,2,',','.');
		$totJmlMutasiTambah = number_format($totJmlMutasiTambah_,2,',','.');
		$totKreditLain = number_format($totKreditLain_,2,',','.');
		$totKreditEkstra = number_format($totKreditEkstra_,2,',','.');
		$totMutasiKurang = number_format($totMutasiKurang_,2,',','.');
		$jmlTot = number_format($jmlTot_,2,',','.');
				
		$isi_body="
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td ><b><i>Lampiran 1</i></b></td>
				</tr>				
			</table>			
			<table border='1' class='cetak' style='width:100%;' >	
				<tr >
					<th class='th01' align='center' width='200'>Uraian</th>".
					/*<th class='th01' align='center' width='200'>Tanah</th>
					<th class='th01' align='center' width='200'>Peralatan dan mesin</th>
					<th class='th01' align='center' width='200'>Gedung dan bangunan</th>
					<th class='th01' align='center' width='200'>Jalan/jembatan, jaringan dan instalasi</th>
					<th class='th01' align='center' width='200'>Aset Tetap Lainnya</th>
					<th class='th01' align='center' width='200'>Konstruksi dalam pengerjaan</th>
					*/
					$rowheader.
					"<th class='th01' align='center' width='200'>Jumlah</th>					
				</tr>
				<tr >
					<td ><b>Saldo awal sebelum koreksi Per 1 Januari $tahun</b></td>
					$saldoawal					
					<td align='right'>$totSaldoAwal</td>
				</tr>
				<tr >
					<td >Koreksi :</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'>-</td>					
				</tr>
				<tr >
					<td >Kenaikan(penurunan)koreksi Audit</td>
					$koreksi	
					<td align='right'>$totKoreksi</td>				
				</tr>
				<tr >
					<td >aset tidak produktiv</td>
					$tdkproduktif
					<td align='right'>$totTdkProduktif</td>					
				</tr>
				<tr >
					<td >aset tahun lalu yang kurang(lebih)dicatat</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>					
				</tr>
				<tr >
					<td >Direklas ke Aset Ekstra Kompatibel / Nilai dibawah Rp. 250 ribu</td>
					$ekstra
					<td align='right'>$totEkstra</td>					
				</tr>
				<tr >
					<td ><b>Saldo awal setelah koreksi Per 1 Januari $tahun</b></td>
					$saldoAwalKoreksi
					<td align='right'><b>$totSaldoAwalKoreksi</b></td>					
				</tr>
				<tr >
					<td ><b>Mutasi 1 Januari-31 Desember $tahun</b></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr >
					<td ><b>Penambahan :</b></td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>
					<td align='right'>-</td>					
				</tr>
				<tr class='row0'>
					<td >Belanja Modal :</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'>-</td>					
				</tr>
				<tr class='row0'>
					<td >a. APBN :</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr class='row0'>
					<td >b. APBD :</td>
					$bmAPBD
					<td align='right'>$totBmAPBD</td>					
				</tr>
				<tr class='row0'>
					<td >c. Bantuan BOS :</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<!--<tr class='row0'>
					<td >Dari Belanja Pemeliharaan</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>-->
				<tr class='row0'>
					<td >Dari Belanja Pemeliharaan</td>
					$pelihara
					<td align='right'>$totPelihara</td>					
				</tr>
				<tr class='row0'>
					<td >Dari Belanja Barang dan Jasa</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr class='row0'>
					<td >Dari Biaya Umum</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr class='row0'>
					<td >Pendapatan hibah / BOS Langsung / Blokgrand</td>
					$hibah
					<td align='right'>$totHibah</td>					
				</tr>
				<tr >
					<td >Jumlah</td>
					$jmlMutasiTambah
					<td align='right'><b>$totJmlMutasiTambah</b></td>					
				</tr>
				<tr >
					<td ><b>Pengurangan</b></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'>-</td>					
				</tr>
				<tr class='row0'>
					<td >Belanja Hibah</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'>-</td>					
				</tr>
				<tr class='row0'>
					<td >Reklas ke persediaan</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr class='row0'>
					<td >Penghapusan (sudah ada SK)</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'>-</td>					
				</tr>
				<tr class='row0'>
					<td ><b>Reklas ke aset lain-lain</b></td>
					$kreditLain
					<td align='right'>$totKreditLain</td>					
				</tr>
				<tr class='row0'>
					<td >&nbsp<b>Reklas ke aset tidak produtiv</b></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr class='row0'>
					<td >Reklas ke Aset Ekstra Komptabel</td>
					$kreditEkstra
					<td align='right'>$totKreditEkstra</td>					
				</tr>
				<tr class='row0'>
					<td >Reklas ke Aset yang hilang / tidak diketahui keberadaannya</td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>					
				</tr>
				<tr >
					<td >Jumlah</td>
					$mutasiKurang
					<td align='right'><b>$totMutasiKurang</b></td>					
				</tr>
				<tr >
					<td height='20' valign='bottom'><b>Saldo akhir per 31 Desember $tahun</b></td>
					$tot
					<td align='right' valign='bottom'><b>$jmlTot</b></td>					
				</tr>
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
$Rekonlampiran1 = new Rekonlampiran1Obj();
?>