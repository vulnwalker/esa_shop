<?php
$Main->PagePerHal = 100;
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");

$cbxDlmRibu = cekPOST("cbxDlmRibu");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID 	= cekPOST("fmID",0);
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
//$fmWIL 	= cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmBIDANG 	= cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");

$Info = "";
/*
list($ListData, $jmlData) = 
	getList_RekapByBrg($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu)
			);
*/
$fmTahun = cekPOST('fmTahun',date('Y'));
$tglAwal = $fmTahun.'-1-1';
$tglAkhir = $fmTahun.'-12-31';
$fmKONDBRG = $_REQUEST['fmKONDBRG'];

list($ListData, $jmlData,$jmlTotBarangA,$jmlTotHargaA) = 
	Mutasi_RekapByBrg_GetList2_keu($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu), FALSE, 3, $fmSEKSI, $fmKONDBRG,0,0,0,0
			
			);

list($ListDataB, $jmlData,$jmlTotBarangB,$jmlTotHargaB) = 
	Mutasi_RekapByBrg_GetList2_keu($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, 
			$Main->PagePerHal * (($HalDefault*1) - 1),0,
			array(50,50,50,'',100,100), !empty($cbxDlmRibu), FALSE, 3, $fmSEKSI, $fmKONDBRG,1,$jmlTotBarangA,$jmlTotHargaA,1
			
			);

$ListData .= $ListDataB;			
$tampilHeaderHarga =  !empty($cbxDlmRibu) ? "Jumlah Harga <br>dalam Ribuan <br>(Rp)" : " Jumlah Harga <br>(Rp) ";
$ListHeader = 
	"<tr>
		<th class=\"th01\"  width=\"50\">Nomor</th>
		<th class=\"th01\"  width=\"50\">Golongan</th>
		<th class=\"th01\"  width=\"50\">Kode<br>Bidang<br>Barang</th>
		<th class=\"th01\" >Nama Bidang Barang</th>
		<th class=\"th01\" width=\"120\">Jumlah Barang</th>
		<th class=\"th01\" width=\"120\">$tampilHeaderHarga</th>
		<!--<th class=\"th01\" >Keterangan</th>-->
	</tr>";

$Tahun= "<div style='float:left;padding:2 8 2 8;border-left:1px solid #E5E5E5;'> Tahun <input type=text name='fmTahun' size=4 value='$fmTahun'></div>";
$dalamRibuan = "<div style='float:left;padding:2 8 0 0; '> 
		<table ><tr>
		<td style='padding:0;'> Tampilkan : </td>
		<td width='10' style='padding:0;'> <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> </td>
		<td style='padding:0;'>Dalam Ribuan </td>
		</tr></table>
	</div>";
/*
if ($Main->KOND_ADA_TIDAK){
	$arrKondBarang = 
		array(
			array("5","Ada"),
			array("1","Ada Baik"),
			array("2","Ada Kurang Baik"),
			array("3","Ada Rusak Berat"),
			array("4","Tidak Ada"),
		);
	$kondBarang = 
		"<div style='float:left;padding:3 8 3 0; '> ".
			cmb2D_v2('fmKONDBRG',$fmKONDBRG, $arrKondBarang,'','Kondisi Barang','').
		"</div>";
}
*/
//$dalamRibuan = " <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> Dalam Ribuan ";
$tombolTampil= 
	"<div style='float:left;padding:2 8 0 0; '> ".
	"<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>".
	"</div>";
$optTampil = "
	<table width=\"100%\" class=\"adminform\" style='margin:4 0 4 0;'>	<tr>		
	<td width=\"100%\" valign=\"top\">
				$dalamRibuan
				$Tahun
				$kondBarang
				$tombolTampil			
			
		</td></tr>
	</table>";

$Main->ListData->ToolbarBawah =
	"<!--<table width=\"100%\" class=\"menudottedline\">
	<tr><td>-->
		<table width=\"50\"><tr>
		<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>-->
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak_keu&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Cetak")."</td>		
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=rekap_bi_cetak_keu&SDest=XLS&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>
		</tr></table>
	<!--</td></tr>
	</table>-->";

//echo "<br>dlm ribu=".$cbxDlmRibu;
$cek='';


$Main->Isi = "


		<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
		
<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">Rekapitulasi Buku Inventaris Barang II </th>
	  <th>".$Main->ListData->ToolbarBawah."</th>
	</tr>
</table>


			".WilSKPD2b()."
		



$optTampil

<table border=\"1\" class=\"koptable\">
	$ListHeader
		$ListData
	<tr>
			<td colspan=12 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalDefault")."</td>
	</tr>
</table>
<br>

".$cek;



?>