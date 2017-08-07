<?php


$cidBI = cekPOST("cidBI");
$cidPLH = cekPOST("cidPLH");

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
$fmTANGGALPEMELIHARAAN = cekPOST("fmTANGGALPEMELIHARAAN");
$fmJENISPEMELIHARAAN = cekPOST("fmJENISPEMELIHARAAN");
$fmPEMELIHARAINSTANSI = cekPOST("fmPEMELIHARAINSTANSI");
$fmPEMELIHARAALAMAT = cekPOST("fmPEMELIHARAALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmBUKTIPEMELIHARAAN = cekPOST("fmBUKTIPEMELIHARAAN");
$fmBIAYA = cekPOST("fmBIAYA");
$fmKET = cekPOST("fmKET");
$fmBARANGCARIPLH = cekPOST("fmBARANGCARIPLH");
$fmBARANGCARI_IdBrg = cekPOST("fmBARANGCARI_IdBrg");
$fmBARANGCARI_IdBiAwal = cekPOST("fmBARANGCARI_IdBiAwal");
$fmcr_data = cekPOST("fmcr_data");
$fmPILCARIvalue = cekPOST("fmPILCARIvalue");
$fmAset = cekPOST("fmAset");
$fmManfaat = cekPOST("fmManfaat");
$fmORDER1 = cekPOST("fmORDER1");
$fmDESC1 = cekPOST("fmDESC1");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$jmPerHal = cekPOST("jmPerHal")==''?25:cekPOST("jmPerHal");

$ArrCaridata = array(
//array("0","Semua"), default
array("selectPengunaan","Pengunaan"),
array("selectAlamat","Alamat"),
array("selectNopol","No Polisi"),
array("selectNoKontrak","No Kontrak"),
array("selectUraian","Uraian"),
	);

$ArrYT = array(
//array("0","Semua"), default
array("1","Ya"),
array("0","Tidak")
	);
$ArrOrder= array(
array("1","Tahun Pemeliharaan"),
array("2","Tahun Perolehan")
	);
	
/*
//LIST PLH
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
*/


//kondisi ---------------------------------------
$Kondisi = getKondisiSKPD($fmKEPEMILIKAN,$Main->DEF_PROPINSI,$Main->DEF_WILAYAH,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmSEKSI);
$fmKIB = $_POST['fmKIB'];
$fmPilihThn = $_POST['fmPilihThn'];
$fmPilihThnPerolehan = $_POST['fmPilihThnPerolehan'];
$fmPilihThnPerolehanBI = $_POST['fmPilihThnPerolehanBI'];
if(!empty($fmBARANGCARIPLH)){ $Kondisi .= " and nm_barang like '%$fmBARANGCARIPLH%' "; }
if(!empty($fmBARANGCARI_IdBrg)){ $Kondisi .= " and id_bukuinduk like '%$fmBARANGCARI_IdBrg%' "; }
if(!empty($fmBARANGCARI_IdBiAwal)){ $Kondisi .= " and idbi_awal like '%$fmBARANGCARI_IdBiAwal%' "; }
if(!empty($fmTahunPerolehan)){ $Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' "; }
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_pemeliharaan)='$fmPilihThn' ";
if (!empty($fmPilihThnPerolehan)) $Kondisi .= " and year(tgl_perolehan)='$fmPilihThnPerolehan' ";
if (!empty($fmPilihThnPerolehanBI)) $Kondisi .= " and thn_perolehan='$fmPilihThnPerolehanBI' ";
if(!empty($fmcr_data)){
	switch($fmcr_data){			
		case 'selectPengunaan': $Kondisi .= " and penggunaan like '%$fmPILCARIvalue%'"; break;
		case 'selectAlamat': $Kondisi .= " and alamat like '%$fmPILCARIvalue%'"; break;						 	
		case 'selectNopol': $Kondisi .= " and no_polisi like '%$fmPILCARIvalue%'"; break;						 	
		case 'selectNoKontrak': $Kondisi .= " and surat_no like '%$fmPILCARIvalue%'"; break;						 	
		case 'selectUraian': $Kondisi .= " and jenis_pemeliharaan like '%$fmPILCARIvalue%'"; break;						 	
	}
	
}
switch($fmAset){			
	case '1': $Kondisi .= " and tambah_aset='$fmAset'"; break;
	case '0': $Kondisi .= " and tambah_aset='$fmAset'"; break;							 	
}

switch($fmManfaat){			
	case '1': $Kondisi .= " and tambah_masamanfaat='$fmManfaat'"; break;
	case '0': $Kondisi .= " and tambah_masamanfaat='$fmManfaat'"; break;							 	
}

//hal limit ------------------------------------
$HalPLH = cekPOST("HalPLH",1);
$LimitHalPLH = " limit ".(($HalPLH*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $OrderBy.= " order by year(tgl_pemeliharaan) $Asc1 " ;break;
			case '2': $OrderBy.= " order by year(tgl_perolehan) $Asc1 " ;break;
			default : $OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
		}	

$Tbl = 'v_pemelihara';//" pemeliharaan left join ref_barang using(f,g,h,i,j) ";
$Kondisi = ' where ' .$Kondisi; 
$PeliharaList = Pelihara_List($Tbl, '*',$Kondisi, $LimitHalPLH, $OrderBy, 2,'koptable',FALSE,$Main->PagePerHal * (($HalPLH*1) - 1), FALSE, $fmKIB);

$Title = "<table width=\"100%\" height=\"100%\">
<tr valign=\"top\">
<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR PEMELIHARAAN BARANG MILIK DAERAH </DIV></td>
</tr>
</table>";
$Caridata = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
".cmb2D_v2('fmcr_data',$fmcr_data,$ArrCaridata,'','--- Cari Data ---').
" <input type=text name='fmPILCARIvalue' value='$fmPILCARIvalue' size='30'> <input type=button id='btTampil' name='btTampil' value='Cari' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\">
</div>";
$PilihKIB = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
KIB : ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua').
"</div>";
$PilihAset = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Menambah Nilai Aset : ".cmb2D_v2('fmAset',$fmAset,$ArrYT,'','--- PILIH ---').
"</div>";
$PilihManfaat = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Menambah Masa Manfaat : ".cmb2D_v2('fmManfaat',$fmManfaat,$ArrYT,'','--- PILIH ---').
"</div>";	
$CariNmBrg = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Nama Barang : <input type=text name='fmBARANGCARIPLH' value='$fmBARANGCARIPLH' size='30'>
</div>";	
$VOrder = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'>
Urutkan Berdasarkan : ".cmb2D_v2('fmORDER1',$fmORDER1,$ArrOrder,'','--- PILIH ---').
"<input $fmDESC1 type='checkbox' name='fmDESC1' id='fmDESC1'  value='checked'> Desc</div>";	
$jmPerHal = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Baris per halaman <input name='jmPerHal' id='jmPerHal' size='4' value='$jmPerHal' type='text'>
</div>";
$CariIdBrg = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Id Barang : <input type=text name='fmBARANGCARI_IdBrg' value='$fmBARANGCARI_IdBrg' size='7'>
</div>";
$CariIdBiAwal = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
Id Awal : <input type=text name='fmBARANGCARI_IdBiAwal' value='$fmBARANGCARI_IdBiAwal' size='7'>
</div>";
$PilihThn = " <div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'>".
comboBySQL('fmPilihThn','select year(tgl_pemeliharaan)as thn from v_pemelihara group by thn desc',
'thn', 'thn', 'Tahun Pemeliharaan').
"</div>";
$PilihThnPerolehan = " <div style='float:left;padding:1 8 0 8;'>".
comboBySQL('fmPilihThnPerolehan','select year(tgl_perolehan)as thn from v_pemelihara where tgl_perolehan is not null group by thn desc',
'thn', 'thn', 'Tahun Perolehan').
"</div>";
$PilihThnPerolehanBI = " <div style='float:left;padding:1 8 0 8;'>".
comboBySQL('fmPilihThnPerolehanBI','select thn_perolehan as thn from v_pemelihara where thn_perolehan is not null group by thn desc',
'thn', 'thn', 'Tahun Perolehan BI').
"</div>";
$tombolTampil = " <div style='float:left;padding:0 8 0 8;'>
<input type=button id='btTampil' name='btTampil' value='Tampilkan' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\">
</div>";
$MenuCari = "
<table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
<tr valign=\"middle\">   
<td width=10% >
<div style='padding:1 0 1 0;'>
$Caridata
</div>
</td>
</tr>
</table><table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
<tr valign=\"middle\">   
<td width=10% >
<div style='padding:1 0 1 0;'>
$CariNmBrg
$CariIdBrg
$CariIdBiAwal
$PilihKIB
$PilihThn
$PilihThnPerolehan			
$PilihThnPerolehanBI			
</div>
</td>
</tr>		
</table><table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
<tr valign=\"middle\">   
<td width=10% >
<div style='padding:1 0 1 0;'>			
$PilihAset			
$PilihManfaat	
</div>
</td>
</tr>
</table><table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
<tr valign=\"middle\">   
<td width=10% >
<div style='padding:1 0 1 0;'>						
$VOrder
$tombolTampil
</div>
</td>
</tr>		
</table>
";

/*$PeliharaMenu = "
<table width=\"100%\" class=\"menudottedline\" style='margin: 8 0 0 0 '>
<tr><td>
<table width=\"50\"><tr>
<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pelihara_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pelihara_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>

</tr></table>
</td></tr>
</table>";
*/
//toolbar atas --------------------------------
if (empty($disModul07) && empty($ridModul07)){
	$ToolbarAtas_edit = "
	<td>".PanelIcon1("javascript:PeliharaForm.Edit()","edit_f2.png","Ubah")."</td>
	<td>".PanelIcon1("javascript:PeliharaHapus.Hapus()","delete_f2.png","Hapus")."</td>";				
}
$ToolbarAtas = 
Pelihara_createScriptJs(2).
"<div style='float:right;'>					
<table ><tr>
$ToolbarAtas_edit
<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pelihara_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pelihara_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=pelihara_cetak&ctk=1&SDest=XLS';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>
</tr>
</table>			
</div>";
$Title ="
<table class=\"adminheading\" style='margin:4 0 0 0'><tr>
<th height=\"47\" class=\"user\">Daftar Pemeliharaan Barang Milik Daerah</th>
<th>$ToolbarAtas</th>
</tr></table>";
$Menu_NavAtas = "
<table width='100%' class='' cellpadding='0' cellspacing='0' border='0' width='100%'><tr>
<td class='' width='' height='20' style='text-align:right' width='40%'>
<B>
<A href='?Pg=07&SPg=04' title='Pemeliharaan' style='color:blue;'>Pemeliharaan</a> |
<A href='?Pg=07&SPg=03' title='Pengamanan'>Pengamanan</a>  	 
</td></tr></table>
";
$Main->Isi = "
$Menu_NavAtas
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
$Title
<table width=\"100%\">
<tr><td width=\"60%\" valign=\"top\">		
<table class='adminform' width='100%'><tr><td>".WilSKPD1()."</td></tr></table>						
$MenuCari
$PeliharaList		
$PeliharaMenu
</td></tr>
</table>
</form>
";
?>