<?php
//$HalDPB = cekPOST("HalDPB",1);
//$LimitHalDPB = " limit ".(($HalDPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//$cidBI = cekPOST("cidBI");
//$cidDPB = cekPOST("cidDPB");xx

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPEMANFAATAN = cekPOST("fmTANGGALPEMANFAATAN");
$fmBENTUKPEMANFAATAN = cekPOST("fmBENTUKPEMANFAATAN");
$fmKEPADAINSTANSI = cekPOST("fmKEPADAINSTANSI");
$fmKEPADAALAMAT = cekPOST("fmKEPADAALAMAT");
$fmKEPADANAMA = cekPOST("fmKEPADANAMA");
$fmKEPADAJABATAN = cekPOST("fmKEPADAJABATAN");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmJANGKAWAKTU = cekPOST("fmJANGKAWAKTU");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


//Kondisi ---------------------------------------------------
/*$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmBARANGCARIDPB = cekPOST("fmBARANGCARIDPB");
$Kondisi = getKondisiSKPD();
if(!empty($fmBARANGCARIDPB)){$Kondisi .= " and nm_barang like '%$fmBARANGCARIDPB%' ";}

if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pemanfaatan)='$fmPilihThn' ";*/
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmbentuk_manfaat = $_POST['fmbentuk_manfaat'];
$fmBARANGCARIDPB = cekPOST("fmBARANGCARIDPB");
$Kondisi = $Pemanfaat->genKondisi($fmKEPEMILIKAN, $Main->Provinsi[0], $Main->DEF_WILAYAH, $fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI, $fmKIB, $fmPilihThn, $fmBARANGCARIDPB,$fmbentuk_manfaat);
//limit -------------------
/*$jmPerHal = $_REQUEST['jmPerHal'];//cekPOST("jmPerHal",$Main->PagePerHal);
if(!empty($jmPerHal)) {
	$PagePerHal = $jmPerHal;
}else{
	$PagePerHal = $Main->PagePerHal;
}*/
//echo 'jmPerHal='.$jmPerHal.'-'.$PagePerHal;

$HalPMF = cekPOST("HalPMF",1);
$LimitHalPMF = " limit ".(($HalPMF*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$noawal = $Main->PagePerHal * (($HalPMF*1) - 1);
//$LimitHalPMF = " limit ".(($HalPMF*1) - 1) * $PagePerHal.",".$PagePerHal;
//$noawal = $PagePerHal * (($HalPMF*1) - 1);
//order ------------------------------------------
$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";


//list -----------------
$ListBarangDPB = $Pemanfaat->GetList(' Where '.$Kondisi, $LimitHalPMF, $OrderBy, 2, 'koptable', FALSE, $noawal, FALSE,$fmKIB);

//menu cari ------------------------------------
$PilihKIB = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				KIB : ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua').
			"</div>";
$CariNmBrg = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				Nama Barang : <input type=text name='fmBARANGCARIDPB' id='fmBARANGCARIDPB' value='$fmBARANGCARIDPB'>
			</div>";
$PilihThn = " <div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'>".
			comboBySQL('fmPilihThn','select year(tgl_pemanfaatan)as thn_manfaat from v_pemanfaat group by thn_manfaat desc',
				'thn_manfaat', 'thn_manfaat', 'Semua Tahun').
			"</div>";
$jmlperhal = 
		" <div style='float:left;padding:1 8 0 8;'>".
			"Data per Halaman <input type='text' name='jmPerHal' id='jmPerHal' size='4' value='25'>".
		"</div>";
$bentuk_manfaat = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				Bentuk Pemanfaatan : ".cmb2D_v2('fmbentuk_manfaat',$fmbentuk_manfaat,$Main->ArBentukManfaat,'','Semua').
			"</div>";
//$tombolTampil = " <div style='float:left;padding:0 8 0 8;'><input type=button id='btTampil' value='Tampilkan' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\"></div>";
$tombolTampil = " <div style='float:left;padding:0 8 0 8;'>
					<input type=button id='btTampil' value='Tampilkan' 
					onclick=\"javascript:PemanfaatRefresh.Refresh();\">
					</div>";
					
		
$MenuCari = "
	<table class='adminform' width=\"100%\" style='margin: 4 0 0 0' >
		<tr valign=\"middle\">   
			<td width=10% >
			<div style='padding:1 0 1 0;'>
			$CariNmBrg
			$PilihKIB
			$PilihThn
			$bentuk_manfaat
			$jmlperhal
			$tombolTampil			
			</div>
			</td>
		</tr>		
	</table>";
/*
//menu bar ----------------------------------------
$MenuBar = " 
	<table width=\"100%\" class=\"menudottedline\" style='margin: 8 0 0 0 ;'>
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=manfaat_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=manfaat_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			</tr></table>
		</td></tr>
	</table>";
*/	
//toolbar atas --------------------------------
if (empty($disModul06) && empty($ridModul06)){
	$ToolbarAtas_edit = "
		<td>".PanelIcon1("javascript:PemanfaatForm.Edit()","edit_f2.png","Ubah")."</td>
		<td>".PanelIcon1("javascript:PemanfaatHapus.Hapus()","delete_f2.png","Hapus")."</td>";				
}	
$ToolbarAtas = 
	$Pemanfaat->createScriptJs(2).
	"<div style='float:right;'>					
	<table ><tr>
	$ToolbarAtas_edit
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=manfaat_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>			
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=manfaat_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
	</tr></table>			
	</div>";
//title --------------------------------------
$Title = "
	<table class=\"adminheading\" style='margin: 8 0 0 0 ;'>	<tr>
		<th height=\"47\" class=\"user\">Daftar Pemanfaatan Barang Milik Daerah</th>
		<th>$ToolbarAtas</th>
	</tr></table>";
//tampil page ----------------------------------
$cek='';
$Main->Isi = "
<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin:0 0 4 0\">
			<tbody><tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style=\"text-align:right\"><b>
			<a href=\"pages.php?Pg=Penilaian\" title=\"Penilaian\">Penilaian </a> |			
			<a href=\"pages.php?Pg=Koreksi\" title=\"Koreksi\" >Koreksi </a> |			
			<a href=\"index.php?Pg=06\" title=\"Pemanfaatan\" style=\"color:blue;\">Pemanfaatan</a> |
			<a href=\"index.php?Pg=10\" title=\"Pemindahtanganan\">Pemindahtanganan</a> 
			&nbsp;&nbsp;&nbsp;	
			</b></td></tr></tbody></table>
	<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg\">	
	<table width=\"100%\" id='tbljustify_content' >	<tr><td width=\"60%\" valign=\"top\">
		$Title	
		<table class='adminform' width='100%'><tr><td>".WilSKPD1()."</td></tr></table>
		$MenuCari	
		<div id='divPemanfaatList'>	
		$ListBarangDPB		
		<div>
	</td></tr></table>
	</form>	
	$Info
	".$cek;
?>