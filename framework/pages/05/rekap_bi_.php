<?php
$Main->PagePerHal = 100;
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID 	= cekPOST("fmID",0);
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
//$fmWIL 	= cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmBIDANG 	= cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");

$Info = "";

//get kondisi ----------------------------------------------------------------------------------
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = " and bi.a1='$fmKEPEMILIKAN' and bi.a='{$Main->Provinsi[0]}' and bi.b='$fmWIL' 
//			and bi.c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' 
			and c='$fmSKPD' $KondisiD $KondisiE ";
if($fmSKPD == "00"){
	$Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' 
		$KondisiD $KondisiE ";
}

//list --------------------------------------------------------------
$jmlTotalHargaDisplay = 0;
$ListData = "";
$no=0;
$cb=0;
$QryRefBarang = mysql_query("select ref.f,ref.g,ref.nm_barang from ref_barang as ref 
		where h='00' order by ref.f,ref.g");
$jmlData = mysql_num_rows($QryRefBarang);
$TotalHarga = 0;
$totalBrg =0;
$no=$Main->PagePerHal * (($HalDefault*1) - 1);
while($isi=mysql_fetch_array($QryRefBarang)){
	//$Kondisi1 = "concat(bi.f,bi.g)= '{$isi['f']}{$isi['g']}'";
	//$QryBarang = mysql_query("select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi where $Kondisi1 $Kondisi group by bi.f,bi.g order by bi.f,bi.g");
	//$sqry = "select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi 
	//			where $Kondisi1 $Kondisi group by bi.f,bi.g ";
	$Kondisi1 = "concat(f, g)= '{$isi['f']}{$isi['g']}'";
	//$sqry = "select sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from v_jmlbrgharga_fg 
	//			where $Kondisi1 $Kondisi order by f,g";
	$sqry = "select sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk  
				where $Kondisi1 $Kondisi group by f,g order by f,g";
	$cek .= '<br> qry FG ='.$sqry;
	$QryBarang = mysql_query($sqry);
	$isi1 = mysql_fetch_array($QryBarang);
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$kdBidang = $isi['g'] == "00"?"":$isi['g'];
	$nmBarang = $isi['g'] == "00"?"<b>{$isi['nm_barang']}</b>":"&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
		
	//$sqry2="select sum(bi.jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk as bi 
	//			where bi.f='{$isi['f']}' $Kondisi group by f order by f";
	//$Kondisi2=" f = '{$isi['f']}'";
	//$sqry2 = "select sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from v_jmlbrgharga_f 
	//				where $Kondisi2 $Kondisi order by f";
	$sqry2="select sum(jml_barang) as jml_barang, sum(jml_harga) as jml_harga from buku_induk  
				where f='{$isi['f']}' $Kondisi group by f order by f";
	$QryBarangAtas = mysql_fetch_array(	mysql_query( $sqry2	));
	$cek .= '<br> qry F ='.$sqry2;
	$jmlBarangAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:$isi1['jml_barang'];		
	$jmlBarangAtas = empty($jmlBarangAtas) ? "0" : "".$jmlBarangAtas."";
	
	//$jmlBarangAtas = $isi['g'] == "00" ? "<b>".$jmlBarangAtas."" : "".$jmlBarangAtas."";
	$jmlBarangAtas = $isi['g'] == "00" ? "<b>".number_format(($jmlBarangAtas),0,',', '.')."" : "".number_format(($jmlBarangAtas),0,',', '.')."";
	$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']/1000),0,',', '.')."":"".number_format(($isi1['jml_harga']/1000),0,',', '.')."";
	//$TotalHarga +=  $isi1['jml_harga'];	
	//$totalBrg += $isi1['jml_barang'];		
	
	
	$TotalHarga +=  $isi['g'] == "00" ? $QryBarangAtas['jml_harga'] :0;
	$totalBrg += $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:0;//$isi1['jml_barang'];
	
	
	$ListData .= "
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"50\">$no.</td>
			<td class=\"GarisDaftar\" align=center width=\"50\">{$isi['f']}</td>
			<td class=\"GarisDaftar\" align=center width=\"50\">$kdBidang</td>
			<td class=\"GarisDaftar\">$nmBarang</div></td>
			<td class=\"GarisDaftar\" align=right width=\"120\">$jmlBarangAtas</td>
			<td class=\"GarisDaftar\" align=right width=\"120\">$jmlHargaAtas</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
        </tr>
	";
	$cb++;
}

$ListData .= "
		<tr class='row0'>
			<td colspan=4 class=\"GarisDaftar\">TOTAL</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($totalBrg), 0, ',', '.')."</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($TotalHarga/1000), 0, ',', '.')."</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
		</tr>
		";


// aray combo pencarian barang 
/*
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);
*/


$Main->ListData->ToolbarBawah =
	"<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"50\"><tr>
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
		</tr></table>
	</td></tr>
	</table>";


$cek='';
$Main->Isi = "

<div align=\"center\" class=\"centermain\">
	<div class=\"main\">
		<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
		
<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">Rekapitulasi Buku Inventaris Barang </th>
	</tr>
</table>

<table width=\"100%\">
	<tr>
		<td width=\"60%\" valign=\"top\">
			".WilSKPD2b()."
		</td>
	</tr>
</table>
<!--
<table width=\"100%\" border=\"0\">
	<tr>
		<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR REKAPITULASI INVENTARIS BARANG </td>
	</tr>
</table>-->

<table border=\"1\" class=\"koptable\">
	<tr>
		<th class=\"th01\"  width=\"50\">Nomor</th>
		<th class=\"th01\"  width=\"50\">Golongan</th>
		<th class=\"th01\"  width=\"50\">Kode<br>Bidang<br>Barang</th>
		<th class=\"th01\" >Nama Bidang Barang</th>
		<th class=\"th01\" width=\"120\">Jumlah Barang</th>
		<th class=\"th01\" width=\"120\">Jumlah Harga <br>dalam Ribuan <br>(Rp)</th>
		<!--<th class=\"th01\" >Keterangan</th>-->
	</tr>
		$ListData
	<tr>
			<td colspan=12 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalDefault")."</td>
	</tr>
</table>
<br>
".$Main->ListData->ToolbarBawah."
".$cek;



?>