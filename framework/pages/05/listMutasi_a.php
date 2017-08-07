<?php
set_time_limit(0);
$tim = time();

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD"); 
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");

//$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);

//hal
$HalDefault = cekPOST("HalDefault",1);
$jmPerHal = cekPOST("jmPerHal");

//opt
$fmKIB = cekPOST('fmKIB'); //echo "<br> KIB".$fmKIB;
$fmSemester = cekPOST('fmSemester','0');
$fmTahun = cekPOST('fmTahun',date('Y'));

//act
$Act= cekPOST('Act'); //echo "<br>act=".$Act;

setWilSKPD();

//$Act = 'Cetak';
if ($Act=='Tampil' ){
	Mutasi_GetList(); //<---- global $ListData 
	
	
	$ListHalaman =
	"<tr>
		<td colspan=22 align=center>".Halaman2($jmlData,$Main->PagePerHal,"HalDefault")."</td>
	</tr>";
}


$OptWil =
	"<!--wil skpd-->
	<table width=\"100%\" class=\"adminform\">	<tr>		
		<td width=\"100%\" valign=\"top\">			
			".WilSKPD1()."
		</td>
		<td >
			<!--labelbarang-->	
		</td>
	</tr></table>";

$ToolbarAtas= $Act == 'Tampil'?
	"<!-- toolbar atas -->
			<div style='float:right;'>			
				<table width='125'><tr>
					<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=listmutasi_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
					<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=listmutasi_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
					<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SDest=XLS&SPg=listmutasi_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>					
					
				</tr></table>			
			</div>
	" : "";

$Title = 
	"<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">Daftar Mutasi Barang</th>
	  <th>
	  	".$ToolbarAtas."
	  </th>
	</tr>
	</table>
	";

$PilihKIB = "<div style='float:left;padding:0 8 0 8;'> KIB  ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua')."</div>";
$Semester = "<div style='float:left;padding:0 8 0 8; border-left:1px solid #E5E5E5;'> Semester ".cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester,'','Semester I')."</div>";
$Tahun= "<div style='float:left;padding:0 8 0 0;'> Tahun <input type=text name='fmTahun' size=4 value='$fmTahun'></div>";
$BarisPerHalaman = "<div style='float:left;padding:0 8 0 8;border-left:1px solid #E5E5E5; '> Baris per halaman <input type=text name='jmPerHal' size=4 value='$Main->PagePerHal'></div>";
$tombolTampil = "<input type=button 
		onClick=\"adminForm.Act.value='Tampil'; adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>";
$Opt="
	<table width=\"100%\" class=\"adminform\" style='margin:4 0 0 0'>	<tr>		
	<td width=\"100%\" valign=\"top\">
	$PilihKIB $Semester $Tahun $BarisPerHalaman $tombolTampil
	</td></tr>
	</table>
	";
		
	
//echo '<br>time='.(time()-$tim);
$Main->Isi = 
		"<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
		$Title
		$OptWil
		$Opt
		<table border=\"1\" class=\"koptable\" width='100%'>	
		$ListHeader
		$ListData
		$ListFooterHal
		$ListFooterAll
		$ListHalaman
	
		<input type=hidden name='Act' value=''>
		</table>";
	


?>