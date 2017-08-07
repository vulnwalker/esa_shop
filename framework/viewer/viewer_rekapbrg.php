<?php
/*
2010.11.11
- dua angka dibelakang koma

*/

include("../common/vars.php"); 
include("../config.php"); 

set_time_limit(0);

$Main->PagePerHal = 100;
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");//not use?
$fid= $_GET['fid'];
$id= $_GET['id']; //echo 'id ='.$id.'<br>';
$params = explode(".", $id);
$cbxDlmRibu = $_GET["cbxDlmRibu"];
//$fmTahun = $_GET['fmTahun'];

$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
//$fmWIL 		= '17';//cekPOST("fmWIL");
/*$fmSKPD 	= '04';//cekPOST("fmSKPD");
$fmUNIT 	= '00';//cekPOST("fmUNIT");
$fmSUBUNIT 	= '00';//cekPOST("fmSUBUNIT");
*/
$fmSKPD 	= $params[0]; //echo 'fmskpd ='.$fmSKPD.'<br>';
$fmUNIT 	= $params[1]; //echo 'fmunit ='.$fmUNIT.'<br>';
$fmSUBUNIT 	= $params[2]; //echo 'fmsubunit ='.$fmSUBUNIT.'<br>';
$fmSEKSI 	= $params[3];


// get bidang --------------------------------------------------------
$ListSKPD = mysql_fetch_array(
	mysql_query('select nm_skpd as nmbidang from ref_skpd where c="'.$fmSKPD.'" and d ="00" and e ="00" ')); 
$ListUNIT = mysql_fetch_array(
	mysql_query('select nm_skpd as nmopd from ref_skpd  where c="'.$fmSKPD.'" and d ="'.$fmUNIT.'" and e="00"  ' )); 
$ListSUBUNIT = mysql_fetch_array(
	mysql_query('select nmunit from v_unit where c="'.$fmSKPD.'" and d ="'.$fmUNIT.'" and e="'.$fmSUBUNIT.'"  ')); 
$ListSEKSI = mysql_fetch_array(
	mysql_query('select nm_skpd from ref_skpd where c="'.$fmSKPD.'" and d ="'.$fmUNIT.'" and e="'.$fmSUBUNIT.'" and e1="'.$fmSEKSI.'" and e1<>"000" and e1<>"00" ')); 



/*
list($ListData, $jmlData) = getList_RekapByBrg($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
		$Main->PagePerHal * (($HalDefault*1) - 1),0,
		array(42,42,52,'',52,102), !empty($cbxDlmRibu)
		);	
*/
$fmTahun = cekGET('fmTahun',date('Y'));
$tglAwal = $fmTahun.'-1-1';
$tglAkhir = $fmTahun.'-12-31';
list($ListData, $jmlData) = 
	Mutasi_RekapByBrg_GetList2($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(42,42,52,'',52,102), !empty($cbxDlmRibu), FALSE, 3, $fmSEKSI
			);


// aray combo pencarian barang 
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

$tampilHeaderHarga =  !empty($cbxDlmRibu) ? "Jumlah Harga <br>dalam Ribuan <br>(Rp)" : " Jumlah Harga <br>(Rp) ";
$Main->Isi = "

<!--
<div align=\"left\" class=\"centermain\">
<div class=\"main\">
-->
<div style='padding:0 11 0 11;width:620'>
	<!--<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">-->
		
	<!--title-->
	<table width=\"615\" border=\"0\" style='margin:8'>
	<tr>
		<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR REKAPITULASI INVENTARIS BARANG MILIK DAERAH $fmTahun</div></td>
	</tr>
	</table>
	
	<!--bidang-->
	<table style = 'width:615;margin:0;padding:8;background-color: #F9F9F9;	border: 1px solid #E5E5E5;' >
	<tr valign=\"top\"> <td width='100' height='22'><b>BIDANG</td> <td width='10'><b>:</td> <td>".$ListSKPD['nmbidang']."</td> </tr> 				
	<tr valign=\"top\"> <td height='22'><b>SKPD</td> <td><b>:</td> <td>".$ListUNIT['nmopd']."</td> </tr> 
	<tr valign=\"top\"> <td height='22'><b>UNIT</td> <td><b>:</td> <td>".$ListSUBUNIT['nmunit']."</td> </tr> 
	<tr valign=\"top\"> <td height='22'><b>SUB UNIT</td> <td><b>:</td> <td>".$ListSEKSI['nm_skpd']."</td> </tr> 
	</table>
	
	<!--header-->
	<table border=\"1\" class=\"koptable\" style='width:590'>
	<tr>
		<th class=\"th01\" width=\"40\" >No.</th>
		<th class=\"th01\" width=\"40\" style='padding:4'>Gol.</th>
		<th class=\"th01\" width=\"50\" style='padding:4'>Kode<br>Bidang<br>Barang</th>
		<th class=\"th01\" style='padding:4'>Nama Bidang Barang</th>
		<th class=\"th01\" width=\"50\" style='padding:4' >Jumlah <br>Barang</th>
		<th class=\"th01\" width=\"100\" style='padding:4'>$tampilHeaderHarga</th>		
	</tr>
	</table>
	
	<!--list-->
	<div id='divscroll' style='margin-width:4;overflow-y:auto;height:300; 
			'border-color: rgb(221, 221, 221); border-style: solid; border-width: 1 1 1 1px;'>
	<table border = '1' class='koptable' style='width:590'>		

		$ListData		
	
	</table>
	</div>
	<table style = 'width:615;'>
		<tr height='40'><td align='right' style='padding:4 8 4 4'> 
		<form action='?Pg=cetakbrg' method='post' name='adminForm2' id='adminForm2'> 
		<input class='button_std' type='button' value='Cetak' onclick='".$fid.".print()'> 
		<input class='button_std' type='button' value='Simpan ke' onclick='".$fid.".print_xls()'> 
		
		<input class='button_std' type='button' value='Close' onclick='".$fid.".close()'> 
		<input type='hidden' name='fmTahun' id='fmTahun' value='".$fmTahun."'> 
		<input type='hidden' name='fmSKPD' id='fmSKPD' value='".$fmSKPD."'> 
		<input type='hidden' name='fmUNIT' id='fmUNIT' value='".$fmUNIT."'> 
		<input type='hidden' name='fmSUBUNIT' id='fmSUBUNIT' value='".$fmSUBUNIT."'> 
		<input type='hidden' name='fmSEKSI' id='fmSEKSI' value='".$fmSEKSI."'> 
		</form>
	
		</td></tr>
	</table>
	
</div>
	
";

echo $Main->Isi;

?>