<?php

class Rekonlampiran2Obj  extends DaftarObj2{	
	var $Prefix = 'Rekonlampiran2';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'Rekonlampiran2'; //daftar
	var $TblName_Hapus = 'Rekonlampiran2';
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
	var $fileNameExcel='Rekonlampiran2.xls';
	var $Cetak_Judul = 'Cetak Surat';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Rekonlampiran2Form';	
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
	font-size:9pt;
}
table.cetak {
	background-color: #FFFFFF;
	font-family : Arial, Helvetica, sans-serif;
	margin: 0px;
	padding: 2px;
	border: 1px solid;
	width: 30cm;
	border-collapse: collapse;
	color: #000000;
	font-size : 9pt;
}
table.cetak th.th01 {
	margin: 0px;
	padding: 5px;
	height: 10px;
	border: 1px solid #000000;
	font-size : 9pt;
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
	font-size:9pt;
	border: 1px solid #000000;
	padding:2px 2px 2px 4px;
}
table.cetak input {
	font-family: Arial Narrow;
	font-size: 10pt;
}
				</style>";
				
		//body =============================================================================================		
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
		$jml_saldoawal=0; $jml_debet = 0; $jml_kredit=0; $jml_saldoakhir=0;
		$jml_saldoawal2=0; $jml_debet2 = 0; $jml_kredit2=0; $jml_saldoakhir2=0;
		$jml_saldoawal3=0; $jml_debet3 = 0; $jml_kredit3=0; $jml_saldoakhir3=0;
		while($row = mysql_fetch_array($get_qry)){
			$saldoawal = number_format( $row['saldoawal'] ,2,',','.');
			$debet = number_format( $row['debet_koreksi'] ,2,',','.'); 
			$kredit = number_format( $row['kredit_koreksi'] ,2,',','.'); 
			$saldoakhir = number_format( $row['saldoawal']  + ($row['debet_koreksi'] - $row['kredit_koreksi']) ,2,',','.'); 
			$jml_saldoawal_+= $row['saldoawal']; 
			$jml_debet_ += $row['debet_koreksi'] ; 
			$jml_kredit_ += $row['debet_koreksi'] ; 
			$jml_saldoakhir_ += $row['saldoawal']  + ($row['debet_koreksi'] - $row['kredit_koreksi']);
			$jml_saldoawal  = number_format( $jml_saldoawal_ ,2,',','.');
			$jml_debet  = number_format( $jml_debet_ ,2,',','.');
			$jml_kredit  = number_format( $jml_kredit_ ,2,',','.');
			$jml_saldoakhir = number_format( $jml_saldoakhir_ ,2,',','.');			
			$golongan.=
				"<tr>
					<td>".$row['nm_barang']."</td>
					<td align='right'>$saldoawal</td>
					<td align='right'>$debet</td>
					<td align='right'>$kredit</td>
					<td align='right'>$saldoakhir</td>	
				</tr>";
				
			$jml_saldoawal2_+= $row['saldoawal_wajar']; 
			$jml_debet2_ += $row['dkoreksi_wajar'] ; 
			$jml_kredit2_ += $row['kkoreksi_wajar'] ; 
			$jml_saldoakhir2_ += $row['saldoawal']  + ($row['dkoreksi_wajar'] - $row['kkoreksi_wajar']);
			$jml_saldoawal2  = number_format( $jml_saldoawal2_ ,2,',','.');
			$jml_debet2  = number_format( $jml_debet2_ ,2,',','.');
			$jml_kredit2  = number_format( $jml_kredit2_ ,2,',','.');
			$jml_saldoakhir2 = number_format( $jml_saldoakhir2_ ,2,',','.');			
			$golongan2.=
				"<tr>
					<td>".$row['nm_barang']."</td>
					<td align='right'>".number_format( $row['saldoawal_wajar']  ,2,',','.')."</td>
					<td align='right'>".number_format( $row['dkoreksi_wajar']   ,2,',','.')."</td>
					<td align='right'>".number_format( $row['kkoreksi_wajar']   ,2,',','.')."</td>
					<td align='right'>".number_format( $row['saldoawal_wajar'] + ( $row['dkoreksi_wajar']- $row['kkoreksi_wajar'] )  ,2,',','.')."</td>	
				</tr>";
				
			$jml_saldoawal3_+= $row['saldoawal_lain']; 
			$jml_debet3_ += $row['dkoreksi_lain'] ; 
			$jml_kredit3_ += $row['kkoreksi_lain'] ; 
			$jml_saldoakhir3_ += $row['saldoawal_lain']  + ($row['dkoreksi_lain'] - $row['kkoreksi_lain']);
			$jml_saldoawal3  = number_format( $jml_saldoawal3_ ,2,',','.');
			$jml_debet3  = number_format( $jml_debet3_ ,2,',','.');
			$jml_kredit3  = number_format( $jml_kredit3_ ,2,',','.');
			$jml_saldoakhir3 = number_format( $jml_saldoakhir3_ ,2,',','.');			
			$golongan3.=
				"<tr>
					<td>".$row['nm_barang']."</td>
					<td align='right'>".number_format( $row['saldoawal_lain']  ,2,',','.')."</td>
					<td align='right'>".number_format( $row['dkoreksi_lain']   ,2,',','.')."</td>
					<td align='right'>".number_format( $row['kkoreksi_lain']   ,2,',','.')."</td>
					<td align='right'>".number_format( $row['saldoawal_lain'] + ( $row['dkoreksi_lain']- $row['kkoreksi_lain'] )  ,2,',','.')."</td>	
				</tr>";
			
			
		}
		
		$isi_body="
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td ><b><i>Lampiran 2</i></b></td>
				</tr>				
			</table>
			<br>
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td ><b>Koreksi Nilai Perolehan hasil Audit BPKRI (aset produktif)</b></td>
				</tr>				
			</table>			
			<table border='1' class='cetak' style='width:100%;' >	
				<tr >
					<th class='th01' align='center' rowspan='2' width='300'>Hasil Penilaian Menurut KJPP</th>
					<th class='th01' align='center' rowspan='2' width='100'>Nilai Perolehan</th>
					<th class='th01' align='center' colspan='2' width='300'>Koreksi hasil rekonsiliasi</th>
					<th class='th01' align='center' rowspan='2' width='100'>Hasil Koreksi</th>									
				</tr>
				<tr>
					<th class='th01' align='center'>Penambahan</th>
					<th class='th01' align='center'>Pengurangan</th>
				</tr>
				".$golongan."				
				<tr >
					<td align='right'>Jumlah</td>
					<td align='right'>$jml_saldoawal</td>
					<td align='right'>$jml_debet</td>
					<td align='right'>$jml_kredit</td>
					<td align='right'>$jml_saldoakhir</td>					
				</tr>
			</table>
			<br>
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td ><b>Koreksi Nilai Wajar hasil Audit BPKRI (aset produktif)</b></td>
				</tr>				
			</table>		
			<table border='1' class='cetak' style='width:100%;' >	
				<tr >
					<th class='th01' align='center' rowspan='2' width='300'>Hasil Penilaian Menurut KJPP</th>
					<th class='th01' align='center' rowspan='2' width='100'>Nilai Wajar</th>
					<th class='th01' align='center' colspan='2' width='300'>Koreksi hasil rekonsiliasi</th>
					<th class='th01' align='center' rowspan='2' width='100'>Hasil Koreksi</th>									
				</tr>
				<tr>
					<th class='th01' align='center'>Penambahan</th>
					<th class='th01' align='center'>Pengurangan</th>
				<tr>
				".$golongan2."
				<tr >
					<td align='right'>Jumlah</td>
					<td align='right'>$jml_saldoawal2</td>
					<td align='right'>$jml_debet2</td>
					<td align='right'>$jml_kredit2</td>
					<td align='right'>$jml_saldoakhir2</td>					
				</tr>
			</table>
			<br>
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td ><b>Koreksi Nilai Perolehan hasil investigasi(aset tidak produktif)</b></td>
				</tr>				
			</table>		
			<table border='1' class='cetak' style='width:100%;' >	
				<tr >
					<th class='th01' align='center' rowspan='2' width='300'>Hasil Penilaian Menurut KJPP</th>
					<th class='th01' align='center' rowspan='2' width='100'>Nilai Perolehan</th>
					<th class='th01' align='center' colspan='2' width='300'>Koreksi hasil rekonsiliasi</th>
					<th class='th01' align='center' rowspan='2' width='100'>Hasil Koreksi</th>									
				</tr>
				<tr>
					<th class='th01' align='center'>Penambahan</th>
					<th class='th01' align='center'>Pengurangan</th>
				<tr>
				".$golongan3."
				<tr >
					<td align='right'>Jumlah</td>
					<td align='right'>$jml_saldoawal3</td>
					<td align='right'>$jml_debet3</td>
					<td align='right'>$jml_kredit3</td>
					<td align='right'>$jml_saldoakhir3</td>					
				</tr>
			</table>
			<br>
			<table border='0' class='rangkacetak' style='width:100%;' >
				<tr>
					 <td valign='top'>catatan: </td><td>- Aset tetap yang diperoleh pada tahun $tahun dicatat sebesar nilai perolehan,<br>
					 - Aset tetap yang diperoleh pada tahun $tahun dicatat sebesar nilai wajar,jika tidak memungkinkan maka nilai aset tetap didasarkan pada nilai perolehan</td>
				</td>				
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
		
		echo "<div style='width:$width;height:$height;'>$isi</div>";
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
$Rekonlampiran2 = new Rekonlampiran2Obj();
?>