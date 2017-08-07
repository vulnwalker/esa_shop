<?php


$cidBI = cekPOST("cidBI");
$cidPMN = cekPOST("cidPMN");

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
$fmTANGGALPENGAMANAN = cekPOST("fmTANGGALPENGAMANAN");
$fmJENISPENGAMANAN = cekPOST("fmJENISPENGAMANAN");
$fmURAIANKEGIATAN = cekPOST("fmURAIANKEGIATAN");
$fmPENGAMANINSTANSI = cekPOST("fmPENGAMANINSTANSI");
$fmPENGAMANALAMAT = cekPOST("fmPENGAMANALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmBUKTIPENGAMANAN = cekPOST("fmBUKTIPENGAMANAN");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");
$fmBARANGCARIPMN = cekPOST("fmBARANGCARIPMN");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

//kondisi --------------------------------------
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmPilihThnBuku = $_POST['fmPilihThnBuku'];
$Kondisi = getKondisiSKPD($fmKEPEMILIKAN,$Main->DEF_PROPINSI,$Main->DEF_WILAYAH,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
if(!empty($fmBARANGCARIPMN)){
	$Kondisi .= " and nm_barang like '%$fmBARANGCARIPMN%' ";
}
if(!empty($fmTahunPerolehan)){
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pengamanan)='$fmPilihThn' ";
if (!empty($fmPilihThnBuku)) $Kondisi .= " and year(tgl_buku)='$fmPilihThnBuku' ";

//limit -------------------
$HalPMN = cekPOST("HalPMN",1);
$LimitHalPMN = " limit ".(($HalPMN*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

//list -----------------
$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
$Tbl = ' v_pengaman ';//" pengamanan left join ref_barang using(f,g,h,i,j) ";
$Kondisi = ' where ' .$Kondisi; 
$Pengaman_List = Pengaman_List($Tbl, '*',$Kondisi, $LimitHalPMN, $OrderBy, 2,'koptable',FALSE,$Main->PagePerHal * (($HalPMN*1) - 1), FALSE, $fmKIB);

$PilihKIB = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				KIB : ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua').
			"</div>";
$CariNmBrg = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				Nama Barang : <input type=text name='fmBARANGCARIPMN' value='$fmBARANGCARIPMN'>
			</div>";
$PilihThn = " <div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'>".
			comboBySQL('fmPilihThn','select year(tgl_pengamanan)as thn_aman from v_pengaman group by thn_aman desc',
				'thn_aman', 'thn_aman', 'Semua Tahun Pengamanan').
			"</div>";
$PilihThnBuku = " <div style='float:left;padding:1 8 0 8;'>".
			comboBySQL('fmPilihThnBuku','select year(tgl_buku)as thn_aman from v_pengaman group by thn_aman desc',
				'thn_aman', 'thn_aman', 'Semua Tahun Buku').
			"</div>";
$tombolTampil = " <div style='float:left;padding:0 8 0 8;'>
					<input type=button id='btTampil' name='btTampil' value='Tampilkan' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\">
					</div>";
$MenuCari = "
	<table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
		<tr valign=\"middle\">   
			<td width=10% >
			<div style='padding:1 0 1 0;'>
			$CariNmBrg
			$PilihKIB
			$PilihThn
			$PilihThnBuku
			$tombolTampil						
			</div>
			</td>
		</tr>		
	</table>";
/*$MenuCari = "
	<table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
		<tr valign=\"middle\">   
			<td width=10% >&nbsp;&nbsp; Nama Barang :
			
			<input type=text name='fmBARANGCARIPMN' value='$fmBARANGCARIPMN'>&nbsp<input type=button value='Cari' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\">
			</td>
		</tr>		
	</table>";*/
/*$MenuBar = "
	<table width=\"100%\" class=\"menudottedline\" style='margin: 8 0 0 0 '>
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengamanan_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengamanan_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

			</tr></table>
		</td></tr>
	</table>";*/
//toolbar atas --------------------------------
if (empty($disModul07) && empty($ridModul07)){
	$ToolbarAtas_edit = "
		<td>".PanelIcon1("javascript:PengamanForm.Edit()","edit_f2.png","Ubah")."</td>
		<td>".PanelIcon1("javascript:PengamanHapus.Hapus()","delete_f2.png","Hapus")."</td>";				
}
$ToolbarAtas = 
	Pengaman_createScriptJs(2).
	"<div style='float:right;'>					
	<table ><tr>
	$ToolbarAtas_edit
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengamanan_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengamanan_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pengamanan_cetak&ctk=1&SDest=XLS';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>	
	</tr></table>			
	</div>";
$Title =" <table class=\"adminheading\" style='margin:4 0 0 0'><tr>
		<th height=\"47\" class=\"user\">Daftar Pengamanan Barang Milik Daerah</th>
		<th>$ToolbarAtas</th>
	</tr></table>";
$Menu_NavAtas = "
	<table width='100%' class='' cellpadding='0' cellspacing='0' border='0'><tr>
	<td class='' width='' height='20' style='text-align:right'>
	<B>
	<A href='?Pg=07&SPg=04' title='Pemeliharaan'>Pemeliharaan</a> |
	<A href='?Pg=07&SPg=03' title='Pengamanan'  style='color:blue;'>Pengamanan</a>  	 
	</td></tr></table>
	";
$Main->Isi = "
$Info
$Menu_NavAtas
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
	
	$Title
	
	<table width=\"100%\" >
	<tr><td width=\"60%\" valign=\"top\">		
		<table class='adminform' width='100%'><tr><td>".WilSKPD1()."</td></tr></table>		
		$MenuCari
		$Pengaman_List			
		$MenuBar
	</td></tr>
	</table>
";
?>