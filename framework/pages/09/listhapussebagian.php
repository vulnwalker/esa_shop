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
$fmTANGGALPENGHAPUSAN = cekPOST("fmTANGGALPENGHAPUSAN");
$fmURAIAN = cekPOST("fmURAIAN");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmHARGA_HAPUS = cekPOST("fmHARGA_HAPUS");
$fmHARGA_AWAL = cekPOST("fmHARGA_AWAL");
$fmHARGA_SCRAP = cekPOST("fmHARGA_SCRAP");

$fmKET = cekPOST("fmKET");
$fmBARANGCARIPLH = cekPOST("fmBARANGCARIPLH");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$SSPg = $_GET['SSPg'];


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
$fmPilihThnBuku = $_POST['fmPilihThnBuku'];
if(!empty($fmBARANGCARIPLH)){ $Kondisi .= " and nm_barang like '%$fmBARANGCARIPLH%' "; }
if(!empty($fmTahunPerolehan)){ $Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' "; }
if (!empty($fmKIB)) $Kondisi .= " and f='$fmKIB' ";
if (!empty($fmPilihThn)) $Kondisi .= " and year(tgl_penghapusan)='$fmPilihThn' ";
if (!empty($fmPilihThnBuku)) $Kondisi .= " and year(tgl_buku)='$fmPilihThnBuku' ";

switch($_GET[SSPg]){
	case '04': $KondisiKib =" and f='01'"; break;
	case '05': $KondisiKib =" and f='02'"; break;
	case '06': $KondisiKib =" and f='03'"; break;
	case '07': $KondisiKib =" and f='04'"; break;
	case '08': $KondisiKib =" and f='05'"; break;
	case '09': $KondisiKib =" and f='06'"; break;	
	case '10': $KondisiKib =" and f='07'"; break;	
	default : $KondisiKib =''; break; 
}
if ($KondisiKib <> '') $Kondisi .= $KondisiKib;


//hal limit ------------------------------------
$HalPLH = cekPOST("HalPLH",1);
$LimitHalPLH = " limit ".(($HalPLH*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
$Tbl = 'v_hapus_sebagian';//" pemeliharaan left join ref_barang using(f,g,h,i,j) ";
$Kondisi = ' where ' .$Kondisi; 
$HapusSebagianList = HapusSebagian_List($Tbl, '*',$Kondisi, $LimitHalPLH, $OrderBy, 2,'koptable',FALSE,$Main->PagePerHal * (($HalPLH*1) - 1), FALSE, $fmKIB);

$Title = "<table width=\"100%\" height=\"100%\">
			<tr valign=\"top\">
				<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR PENGHAPUSAN SEBAGIAN BARANG MILIK DAERAH</DIV></td>
			</tr>
		</table>";

$CariNmBrg = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				Nama Barang : <input type=text name='fmBARANGCARIPLH' value='$fmBARANGCARIPLH'>
			</div>";
$PilihThn = " <div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'>".
			comboBySQL('fmPilihThn','select year(tgl_penghapusan)as thn from v_hapus_sebagian group by thn desc',
				'thn', 'thn', 'Semua Tahun Penghapusan').
			"</div>";
/*			

$PilihKIB = "<div style='float:left;padding:1 8 0 8; border-right:1px solid #E5E5E5;'> 
				KIB : ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua').
			"</div>";

$PilihThnBuku = " <div style='float:left;padding:1 8 0 8;'>".
			comboBySQL('fmPilihThnBuku','select year(tgl_buku)as thn from v_hapus_sebagian group by thn desc',
				'thn', 'thn', 'Semua Tahun Buku').
			"</div>";


*/
$tombolTampil = " <div style='float:left;padding:0 8 0 8;'>
					<input type=button id='btTampil' name='btTampil' value='Tampilkan' onclick=\"adminForm.action='';adminForm.target='';adminForm.submit()\">
					</div>";
$MenuCari = "
	<table class='adminform' width=\"100%\" style='margin: 4 0 4 0' >
		<tr valign=\"middle\">   
			<td width=10% >
			<div style='padding:1 0 1 0;'>
			$CariNmBrg
			$PilihThn
			$tombolTampil			
			</div>
			</td>
		</tr>		
	</table>";

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
if (empty($disModul09) && empty($ridModul09)){
	$ToolbarAtas_edit = "
		<td>".PanelIcon1("javascript:HapusSebagianForm.Edit()","edit_f2.png","Ubah")."</td>
		<td>".PanelIcon1("javascript:HapusSebagianHapus.Hapus()","delete_f2.png","Hapus")."</td>";				
}
$ToolbarAtas = 
	HapusSebagian_createScriptJs(2).
	"<div style='float:right;'>					
	<table ><tr>
	$ToolbarAtas_edit
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=hapussebagian_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=hapussebagian_cetak&ctk=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
	<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=hapussebagian_cetak&ctk=1&SDest=XLS';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>
	</tr>
	</table>			
	</div>";
	
$subpagetitle = 'Buku Inventaris';
switch($SSPg){
			case '03': break;
			case '04': $subpagetitle ='KIB A'; break;
			case '05': $subpagetitle ='KIB B'; break;
			case '06': $subpagetitle ='KIB C'; break;
			case '07': $subpagetitle ='KIB D'; break;
			case '08': $subpagetitle ='KIB E'; break;
			case '09': $subpagetitle ='KIB F'; break;	
			case '10': $subpagetitle ='ATB'; break;	
}
$Title ="
	<table class=\"adminheading\" style='margin:4 0 0 0'><tr>
		<th height=\"47\" class=\"user\">Daftar Penghapusan Sebagian $subpagetitle</th>
		<th>$ToolbarAtas</th>
	</tr></table>";
$Menu_NavAtas = "
	<table width='100%' class='' cellpadding='0' cellspacing='0' border='0'><tr>
	<td class='' width='' height='20' style='text-align:right'>
	<B>
	<A href='?Pg=07&SPg=04' title='Pemeliharaan'>Pemeliharaan</a> |
	<A href='?Pg=07&SPg=03' title='Pengamanan'>Pengamanan</a>  	 
	</td></tr></table>
	";
$Menu_NavAtas='';
$Main->Isi = "
$Info
$Menu_NavAtas
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">

$Title

<table width=\"100%\">
	<tr><td width=\"60%\" valign=\"top\">		
		<table class='adminform' width='100%'><tr><td>".WilSKPD1()."</td></tr></table>						
		$MenuCari
		$HapusSebagianList		
		$HapusSebagianMenu
	</td></tr>
</table>
		
</form>
";
?>