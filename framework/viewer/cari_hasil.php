<?php


$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$LimitHal = '';

//-------- parameter cari --------------
$fmWIL = cekPOST("fmWIL");
$fmSKPD = $_POST['fmSKPD'];//cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTahunPerolehan = $_POST["fmTahunPerolehan"];
$selKondisiBrg = $_POST["selKondisiBrg"];

//-------- default & tes -------------
$fmKEPEMILIKAN 	= '11'; //provinsi
$fmWIL 			= '17'; //bandung
//$fmSKPD 		= '05'; //tes
//$fmUNIT			= '02';



//---------- header ------------
switch ($SPg){
	case '03': //BI
		$cari->header = '	
			<!--header -->
			<tr>
			<th class="th01" width="40">No.</th>	
			<th class="th01" width="250">Kode / No Reg <br> Nama Barang<br>Jenis</th>
			<th class="th01" width="250">Bidang / OPD / Unit</th>
			<th class="th01" width="150">Tahun / Harga<br> Perolehan</th>
			<!--BI-->
			<th class="th01" width="150">Merk / Tipe</th>
			<th class="th01" width="200">No. Sertifikat /<br> No. Pabrik /<br> No. Chasis /<br> No. Mesin</th>
			<th class="th01" width="150">Bahan</th>
			<th class="th01" width="150">Asal Usul /<br> Cara Perolehan Barang</th>
			<th class="th01" width="100">Ukuran Barang /<br> Konstruksi<br> (P,SP,D)</th>
			<th class="th01" width="100">Keadaan <br>Barang<br> (B,KB,RB)</th> 
			<th class="th01" width="100">Keterangan</th> 
			</tr>';
		break;
	case '04': //KIBA
		$cari->header = '	
			<!--header -->
			<tr>
			<th class="th01" rowspan="3" width="40">No.</th>	
			<th class="th01" rowspan="3" width="250">Kode / No Reg <br> Nama Barang<br>Jenis</th>
			<th class="th01" rowspan="3" width="250">Bidang / OPD / Unit</th>
			<th class="th01" rowspan="3" width="150">Tahun / Harga<br> Perolehan</th>
			<!--KIBA-->
			<th class="th01" rowspan="3" >Luas (M2)</th>
			<th class="th01" rowspan="3" style="width:100">Letak / Alamat</th>
			<th class="th02" colspan="3" >Status Tanah</th>
			<th class="th01" rowspan="3" >Penggunaan</th>
			<th class="th01" rowspan="3" >Asal-Usul</th> 
			<th class="th01" rowspan="3" width="100" >Keterangan</th>
			</tr>
			<tr class="koptable">				
				<th class="th01" rowspan="2">Hak</th>
				<th class="th02" colspan="2">Sertifikat</th>
			</tr>
			<tr>
				<th class="th01" style="width:55">Tanggal</th>
				<th class="th01" style="width:75">Nomor</th>
			</tr>';
		break;
}

	
	

//-------- Kondisi ----------------

$Kondisi = 'a1="'.$fmKEPEMILIKAN.'" and a="'.$Main->Provinsi[0].'" and b="'.$fmWIL.'" ';
$Kondisi .=  $fmSKPD ==''?'': ' and  c="'.$fmSKPD.'"';
$Kondisi .=  $fmUNIT ==''?'': ' and d="'.$fmUNIT.'"';
$Kondisi .=  $fmSUBUNIT ==''?'': ' and e="'.$fmSUBUNIT.'"';
//$cari->cek = 'kon='.$Kondisi;

/*
$qry = mysql_query("select * from view_buku_induk2 where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,noreg");
$jmlData= mysql_num_rows($qry);
$qry = mysql_query("select * from view_buku_induk2 where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,noreg ".$LimitHal);
$no=$Main->PagePerHal * (($HalDefault*1) - 1);
$cari->listdata = '';

while($isi=mysql_fetch_array($qry)){
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_harga'];
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$AsalUsul = $isi['asal_usul'];
	$ISI5 = "";
	$ISI6 = "";
	$ISI7 = "";
	$ISI10 = "";
	$ISI12 = $Main->KondisiBarang[$isi['kondisi']-1][1];
	$ISI15 = "";	
	
	if($isi['f']=="01" || $isi['f']=="02" || $isi['f']=="03" || $isi['f']=="04" || $isi['f']=="05"  ) {
		$KondisiKIB = "			
		where 
		a1= '{$isi['a1']}' and 
		a = '{$isi['a']}' and 
		b = '{$isi['b']}' and 
		c = '{$isi['c']}' and 
		d = '{$isi['d']}' and 
		e = '{$isi['e']}' and 
		f = '{$isi['f']}' and 
		g = '{$isi['g']}' and 
		h = '{$isi['h']}' and 
		i = '{$isi['i']}' and 
		j = '{$isi['j']}' and 
		noreg = '{$isi['noreg']}' and 
		tahun = '{$isi['tahun']}' ";
	}	
	if($isi['f']=="01"){//KIB A			
		//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'
		$QryKIB_A = mysql_query("select * from kib_a  $KondisiKIB limit 0,1");
		while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
			$ISI6 = "{$isiKIB_A['sertifikat_no']}";
			//$ISI10 = "{$isiKIB_A['luas']}";
			$ISI15 = "{$isiKIB_B['ket']}";
		}
	}
	if($isi['f']=="02"){//KIB B;			
		//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
		$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
		while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
			$ISI5 = "{$isiKIB_B['merk']}";
			$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
			$ISI7 = "{$isiKIB_B['bahan']}";
			//$ISI10 = "{$isiKIB_B['ukuran']}";
			$ISI15 = "{$isiKIB_B['ket']}";
		}
	}
	if($isi['f']=="03"){//KIB C;
		$QryKIB_C = mysql_query("select * from kib_c  $KondisiKIB limit 0,1");
		while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
			$ISI6 = "{$isiKIB_C['dokumen_no']}";
			$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
			$ISI15 = "{$isiKIB_C['ket']}";
		}
	}
	if($isi['f']=="04"){//KIB D;
		$QryKIB_D = mysql_query("select * from kib_d  $KondisiKIB limit 0,1");
		while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
			$ISI6 = "{$isiKIB_D['dokumen_no']}";
			$ISI15 = "{$isiKIB_D['ket']}";
		}
	}
	if($isi['f']=="05"){//KIB E;		
		$QryKIB_E = mysql_query("select * from kib_e  $KondisiKIB limit 0,1");
		while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
			$ISI7 = "{$isiKIB_E['seni_bahan']}";
			$ISI15 = "{$isiKIB_E['ket']}";
		}
	}
	if($isi['f']=="06"){//KIB F;		
		$QryKIB_F = mysql_query("select * from kib_f  $KondisiKIB limit 0,1");
		while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
			$ISI6 = "{$isiKIB_F['dokumen_no']}";
			$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
			$ISI15 = "{$isiKIB_F['ket']}";
		}
	}
	
	
	$ISI5 = !empty($ISI5)?$ISI5:"-";
	$ISI6 = !empty($ISI6)?$ISI6:"-";
	$ISI7 = !empty($ISI7)?$ISI7:"-";
	$ISI10 = !empty($ISI10)?$ISI10:"-";
	$ISI12 = !empty($ISI12)?$ISI12:"-";
	$ISI15 = !empty($ISI15)?$ISI15:"-";
	
	$cari->listdata .= "
		<tr class=\"$clRow\" >
			<td class=\"GarisDaftar\" align=center>$no.</td>
			<!--<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>-->
			<td class=\"GarisDaftar\" align=center>
				{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
				{$nmBarang['nm_barang']}				
			</td>						
			<td class=\"GarisDaftar\">{$isi['nmbidang']} / <br>{$isi['nmopd']} / <br>{$isi['nmunit']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']} /<br>".
				(number_format($isi['jml_harga']/1000, 2, ',', '.'))."
			</td>
			<td class=\"GarisDaftar\">$ISI5</td>
			<td class=\"GarisDaftar\">$ISI6</td>
			<td class=\"GarisDaftar\">$ISI7</td>
			<td class=\"GarisDaftar\">".$Main->AsalUsul[0][$AsalUsul]."</td>
			<td class=\"GarisDaftar\">$ISI10</td>
			<td class=\"GarisDaftar\">$ISI12</td>
			<td class=\"GarisDaftar\">$ISI15</td>
			
			<!--
			<td class=\"GarisDaftar\">$ISI5</td>
			<td class=\"GarisDaftar\">$ISI6</td>
			<td class=\"GarisDaftar\">$ISI7</td>
			<td class=\"GarisDaftar\">".$Main->AsalUsul[0][$AsalUsul]."</td>
			
			<td class=\"GarisDaftar\">$ISI10</td>
			<td class=\"GarisDaftar\">$ISI12</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']} {$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga']/1000, 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">$ISI15</td>
			-->
        </tr>
	";
	$cb++;
}



//-------- cari total harga ---------/
$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from buku_induk where $Kondisi ");
if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else{
	$jmlTotalHarga=0;
}

$cari->listdata .= "
		<tr class='row0'>
			<td colspan=3 class=\"GarisDaftar\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($jmlTotalHargaDisplay/1000), 2, ',', '.')."</td>
			<td colspan=7 class=\"GarisDaftar\">&nbsp;</td>
			
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=3 >Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format(($jmlTotalHarga/1000), 2, ',', '.')."</td>
			<td colspan=7 class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		";




$cari->footer = "
	<tr>
	<td colspan=15 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalDefault")."</td>
	</tr>
	";
*/

$cari->hasil = 
		'<table border="1" class="koptable">'.
		$cari->header.$cari->listdata.$cari->footer.
		'</table>'.
		$cari->cek;

?>