<?php

/*
2010.09.18:
	- halaman penghapusan hanya menampilkan data yg telah dihapus + menu edit, hapus, cetak ->
		- list BI dihilangkan
	

*/

$HalBI = cekPOST("HalBI",1);
$HalHPS = cekPOST("HalHPS",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalHPS = " limit ".(($HalHPS*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidHPS = cekPOST("cidHPS");

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
$fmKET = cekPOST("fmKET");
$fmIsMutasi = cekPOST("fmIsMutasi");//echo '<br>fmismutasi = '.$fmIsMutasi;
$fmKondisi = cekPOST("fmKondisi"); //echo "<br>fmKondisi=".$fmKondisi;
$fmNoSK = cekPOST("fmNoSK"); //echo "<br>fmNoSK=".$fmNoSK;
$fmTglSK= cekPOST("fmTglSK"); //echo "<br>fmTglSK=".$fmTglSK;
$fmGambar = cekPOST("fmGambar"); //echo "<br>fmGambar=".$fmGambar;
$fmGambar_old = cekPOST("fmGambar_old"); //echo "<br>fmGambar_old=".$fmGambar_old;
$fmApraisal = cekPOST("fmApraisal");
$fmUID = $HTTP_COOKIE_VARS['coID'];

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$fmBARANGCARI = $_POST['fmBARANGCARI'];
$fmBARANGCARIHPS = $_POST['fmBARANGCARIHPS'];


$SSPg = $_GET['SSPg'];
$mutasi = $_GET['mutasi'];
$Act = cekPOST("Act");
$Penghapusan_Baru = cekPOST("Penghapusan_Baru","1");
$Info = "";

//$PageMode = cekPOST('PageMode',1);
$ViewList = cekPOST('ViewList',1);
$ViewEntry = cekPOST('ViewEntry',0);
$fmFiltThnHapus = cekPOST('fmFiltThnHapus');
$kode_barang = cekPOST('kode_barang');



if(($Act=="Penghapusan_TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Penghapusan_Edit" || $Act=="Penghapusan_TambahEdit")  &&  count($cidHPS) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Penghapusan_Edit" || $Act=="Penghapusan_TambahEdit")  && count($cidHPS) == 1 ? " disabled ":"";


//PENGHAPUSAN PROSES -------------------------------------------
Penghapusan_Proses(); //hanya utk penghapusan hapus

		

//order by -------------------------------------------------------
$AcsDsc1 = cekPOST("AcsDsc1");
$odr1 = cekPOST("odr1");
$selThnHapus = $odr1 == "year(tgl_penghapusan)" ? " selected " :  ""; //selected
$opt1 = "<option value=''>--</option><option $selThnHapus value='year(tgl_penghapusan)'>Thn. Hapus</option>";
$ListOrderBy = " 
	Urutkan berdasar : 
		<select name=odr1>$opt1</select><input $AcsDsc1 type=checkbox name=AcsDsc1 value='checked'>Desc. 		
	";
//cari ---------------------------------------------------------
$ListBarangHPS_Cari ="<table class='adminform' width=\"100%\" height=\"100%\" style='margin:4 0 0 0'>
			<tr valign=\"middle\">  
			<td> 
			&nbsp;&nbsp Nama Barang :			
			<input type=text name='fmBARANGCARIHPS' value='$fmBARANGCARIHPS'>&nbsp".
			"&nbsp;&nbsp;Kode Barang <input type=text name='kode_barang' id='kode_barang' value='$kode_barang'>&nbsp; (ex: 01.01)&nbsp;&nbsp;&nbsp;".
			"<input type=button value='Cari' onclick=\"adminForm.target='';adminForm.action='?Pg=$Pg&SPg=$SPg&SSPg=$SSPg';adminForm.submit()\">
			</td>
			</tr>
			</table>";
//filter ---------------------------------------------------------
switch($_GET['SSPg']){
	case '04': $KondisiKib =" where f='01'"; break;
	case '05': $KondisiKib =" where f='02'"; break;
	case '06': $KondisiKib =" where f='03'"; break;
	case '07': $KondisiKib =" where f='04'"; break;
	case '08': $KondisiKib =" where f='05'"; break;
	case '09': $KondisiKib =" where f='06'"; break;	
	case '10': $KondisiKib =" where f='07'"; break;	
	default : $KondisiKib =''; break; 
}
$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
$fmFiltThnHapus = cekPOST('fmFiltThnHapus');
$filThnBuku = comboBySQL('fmFiltThnBuku',"select year(tgl_buku)as thnbuku from buku_induk $KondisiKib  group by thnbuku desc",
			'thnbuku', 'thnbuku', 'Semua Thn. Buku');
			
$kriteria = $_POST['kriteria'];
$arrKriteria = array(
	//array("1","Penghapusan"),
	//array("2","Mutasi"),
	array("3","Belum Mutasi"),
	array("4","Sudah Mutasi"),
	//array("5","Reclass")
);

if($mutasi=='1'){
	$cbkriteria = cmb2D_v2('kriteria', $kriteria, $arrKriteria, '', 'Pilih Kriteria', '').
	"<input type=hidden name=mutasi id=mutasi value='1'>";
}elseif($mutasi=='2'){
	$cbkriteria = "<input type=hidden name=mutasi id=mutasi value='2'>";
}else{
	$cbkriteria = "<input type=hidden name=mutasi id=mutasi value='$mutasi'>";
}
$id_tujuan = cekPOST('id_tujuan');
$id_barang = cekPOST('id_barang');
//echo "select year(tgl_buku)as thnbuku from buku_induk $KondisiKib  group by thnbuku desc,".$_GET[SSPg];
$ListFilter = "
	<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin:4 0 0 0'>
	<tr valign=\"top\">   		
		<td> 
		&nbsp;&nbsp Tampilkan : 
		ID Tujuan <input type=text name='id_tujuan' id='id_tujuan' value='$id_tujuan'>&nbsp;&nbsp;
		ID Barang <input type=text name='id_barang' id='id_barang' value='$id_barang'>&nbsp;&nbsp;
		".comboBySQL('fmFiltThnHapus','select year(tgl_penghapusan)as thnhapus from penghapusan group by thnhapus desc',
			'thnhapus', 'thnhapus', 'Semua Thn. Hapus')."&nbsp;&nbsp".
			//"tes ".
		$filThnBuku."&nbsp;&nbsp".
		$cbkriteria."&nbsp;&nbsp".
		$ListOrderBy.		
		"<input type=button onClick=\"adminForm.action='';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>
		</td>
	</tr>
	</table>";
//list -------------------------------------------------------------
$ListData = Penghapusan_daftar();
//toolbar atas ------------------------------------------------------	
if($mutasi <> ''){
	$krit = "&kriteria=$mutasi";
}else{
	$krit = "";
}
$script ="
	<script>
		function Penghapusan_Edit(){
			var errmsg = '';			
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';	}
			if((errmsg=='') && (adminForm.boxchecked.value ==0 )){	errmsg= 'Data belum di pilih!';	}				
			if (errmsg ==''){					
				adminForm.action='?Pg=09&SPg=03$krit';					
				adminForm.Act.value='Penghapusan_Edit';
				adminForm.target='_blank';
				adminForm.submit();
				adminForm.target='';
			}else{
				alert(errmsg);
			}
		}
		function Penghapusan_Hapus(){
			var errmsg = '';			
			if((errmsg=='') && (adminForm.boxchecked.value ==0 )){	errmsg= 'Data belum di pilih!';	}
			if (errmsg ==''){
				if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){
					adminForm.Act.value='Hapus';
					adminForm.action='';
					adminForm.target='';
					adminForm.submit();
				}
			}else{
				alert(errmsg);
			}
		}
		function Penghapusan_CetakAll(){
			adminForm.action='?Pg=PR&SPg=penghapusan_cetak&SSPg=$SSPg&ctk=$jmlDataHPS';
			adminForm.target='_blank';
			adminForm.submit();
			adminForm.target='';		
		}
		function Penghapusan_exportXls(){
			adminForm.action='?Pg=PR&SPg=penghapusan_cetak&SSPg=$SSPg&ctk=1&xls=1';
			adminForm.target='';
			adminForm.submit();
			adminForm.target='';
		}
	</script>
	";
	if (empty($disModul09) && empty($ridModul09)){
			$toolbar_atas_edit = "<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>-->
				<td>".PanelIcon1("javascript:Penghapusan_Edit()","edit_f2.png","Ubah")."</td>
				<td>".PanelIcon1("javascript:Penghapusan_Hapus()","delete_f2.png","Hapus")."</td>".
				"";				
	}	
	$toolbar_atas = "<div style='float:right;'>					
			<table ><tr>
			$toolbar_atas_edit
			<td>".
			PanelIcon1("javascript:Penghapusan_CetakAll()","print_f2.png","Cetak").
			"</td>".
			"<td>".
			PanelIcon1("javascript:Penghapusan_exportXls()","export_xls.png","Excel").
			"</td>										
			</tr></table>			
			</div>";

//title -----------------------------------------------------------------------------
switch($mutasi){
	case '1' : $titlepage ='Daftar Penghapusan Karena Mutasi'; break;
	case '2' : $titlepage ='Daftar Reklas Barang'; break;
	case '4' : $titlepage ='Daftar Penghapusan Karena Penggabungan'; break;
	case '5' : $titlepage ='Daftar Penghapusan Karena Koreksi (Double Catat) '; break;
	default : $titlepage ='Penghapusan Barang Milik Daerah'; break;
}
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
$subpagetitle = '<br>'.$subpagetitle;
$Page_Title = "<table class=\"adminheading\">
			<tr>
  			<th height=\"47\" class=\"user\" ><div style='padding:0 0 0 8;'>$titlepage $subpagetitle</div></th>
  			<th>$toolbar_atas</th>
			</tr>
			</table>";

//page --------------------------------------------------------------------------------
$Page_Hidden = "
	<input type='hidden' name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
	<input type='hidden' name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
	<input type='hidden' name='fmWILSKPD' value='$fmWILSKPD'>
	<input type='hidden' name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
	<input type='hidden' name='Act'>
	<input type='hidden' name='Penghapusan_Baru' value='$Penghapusan_Baru'>	
	<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
	<input type=\"hidden\" name=\"option\" value=\"com_users\" />
	<input type=\"hidden\" name=\"task\" value=\"\" />
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
	<!--<input type=text name='PageMode' value='$PageMode' >-->
	<input type=hidden name='ViewList' value='$ViewList' >
	<input type=hidden name='ViewEntry' value='$ViewEntry' >
	";
$cek ='';
$Main->Isi .= "	
	<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg\">		
		$script 
		<table width=\"100%\" id='tbljustify_content' >	<tr><td width=\"60%\" valign=\"top\">
			$Page_Title			
			<table class='adminform'><tr><td> ".WilSKPD1()."	</td></tr></table>					
			$ListBarangHPS_Cari
			$ListFilter
			<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1 >	
				$ListData
			</table>		
		</td></tr></table>
		$Page_Hidden	
	</form>".
	$Info.
	$cek;

?>