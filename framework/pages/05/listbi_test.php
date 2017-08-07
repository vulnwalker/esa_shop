<?php

//set_time_limit(0);



$tim = time(); //$stim = time().' <br>';

if(1==2){
	

//get param ------------------------------------------------------------------------
$jmPerHal = cekPOST("jmPerHal");
$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
$sort1 = $_GET['sort1'];
$filterHrg = $_GET['filterHrg'];
$filterBrg = $_GET['filterBrg'];
$fmTglBuku = cekPOST('fmTglBuku'); //echo $fmTglBuku;
$fmFiltThnBuku = cekPOST('fmFiltThnBuku');

// urutan --------------------------------------------------------------------------
$AcsDsc1 = cekPOST("AcsDsc1"); //echo $AcsDsc1.'<br>';
$AcsDsc2 = cekPOST("AcsDsc2");
$AcsDsc3 = cekPOST("AcsDsc3");
$Asc1 = !empty($AcsDsc1) ? " desc " : "";
$Asc2 = !empty($AcsDsc2) ? " desc " : "";
$Asc3 = !empty($AcsDsc2) ? " desc " : "";
$odr1 = cekPOST("odr1"); //echo "odr1=$odr1";
$odr2 = cekPOST("odr2");
$odr3 = cekPOST("odr3");
$Urutkan = "";
if (!empty($odr1)) {
    $Urutkan .= " $odr1 $Asc1, ";
}
if (!empty($odr2)) {
    $Urutkan .= " $odr2 $Asc2, ";
}
if (!empty($odr3)) {
    $Urutkan .= " $odr3 $Asc3, ";
}

if ($sort1 == 1) {
    $Urutkan = ' id desc, ' . $Urutkan; //' tgl_update desc, '.$Urutkan;
} else if ($sort1 == 2) {
    $Urutkan = ' tgl_update desc, ' . $Urutkan;
}
$selTahun1 = $odr1 == "tahun" ? " selected " : "";
$selTahun2 = $odr2 == "tahun" ? " selected " : "";
$selTahun2 = $odr3 == "tahun" ? " selected " : "";
$selKondisi1 = $odr1 == "kondisi" ? " selected " : "";
$selKondisi2 = $odr2 == "kondisi" ? " selected " : "";
$selKondisi3 = $odr3 == "kondisi" ? " selected " : "";
$selThnBuku1 = $odr1 == "year(tgl_buku)" ? " selected " : "";
$selThnBuku2 = $odr2 == "year(tgl_buku)" ? " selected " : "";
$selThnBuku3 = $odr3 == "year(tgl_buku)" ? " selected " : "";
$Odr1 = "<option value=''>--</option><option $selTahun1 value='tahun'>Tahun</option><option $selKondisi1 value='kondisi'>Keadaan Barang</option><option $selThnBuku1 value='year(tgl_buku)'>Tahun Buku</option>";
$Odr2 = "<option value=''>--</option><option $selTahun2 value='tahun'>Tahun</option><option $selKondisi2 value='kondisi'>Keadaan Barang</option><option $selThnBuku2 value='year(tgl_buku)'>Tahun Buku</option>";
$Odr3 = "<option value=''>--</option><option $selTahun3 value='tahun'>Tahun</option><option $selKondisi3 value='kondisi'>Keadaan Barang</option><option $selThnBuku3 value='year(tgl_buku)'>Tahun Buku</option>";



$fmTahunPerolehan = cekPOST("fmTahunPerolehan", "");
$fmID = cekPOST("fmID", 0);
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD"); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
$fmUNIT = cekPOST("fmUNIT"); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
$fmSUBUNIT = cekPOST("fmSUBUNIT");  //echo  '<br> fmSUBUNIT  = '.$fmSUBUNIT;//?
$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN", $fmTahunPerolehan);

$cbxDlmRibu = $_POST["cbxDlmRibu"]; //echo $cbxDlmRibu;
$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");
$selStatusBrg = $_POST["selStatusBrg"];
$SPg = isset($HTTP_GET_VARS["SPg"]) ? $HTTP_GET_VARS["SPg"] : "";
if ($SPg == '') {
    $SPg = '03';
}


//get -------------------------------------------------------------
$Penghapusan_Baru = cekPOST("Penghapusan_Baru", "1");
$cidBI = cekPOST("cidBI");
$Act = cekPOST("Act"); //echo"<br> Act=".$Act;
$Baru = cekPOST("Baru", "");
$Info = "";


setWilSKPD(); //echo "<br> fmSUBUNIT ".$fmSUBUNIT;
//hal limit ---------------------------
$HalDefault = cekPOST("HalDefault", 1);
$LimitHal = " limit " . (($HalDefault * 1) - 1) * $Main->PagePerHal . "," . $Main->PagePerHal;
//********************************************************************************* {
{//include ('pages/'.$Pg.'/global_templ.php'); //get mian->entry,title, toolbar, optwlayah, hidden, script
$Act2 = cekPOST('Act2', '');
$ViewList = cekPOST('ViewList', 1);
$ViewEntry = cekPOST('ViewEntry', 0);
if ($Act == 'Hapus') {
    Penatausahaan_Proses();
}

switch ($SPg) {
    case "03":$spg = 'listbi_cetak';
        $titleCaption = 'Buku Inventaris Barang';
        break;
    case "04":$spg = 'kib_a_cetak';
        $titleCaption = 'KIB A Tanah';
        break;
    case "05":$spg = 'kib_b_cetak';
        $titleCaption = 'KIB B Peralatan dan Mesin';
        break;
    case "06":$spg = 'kib_c_cetak';
        $titleCaption = 'KIB C Gedung dan Bangunan';
        break;
    case "07":$spg = 'kib_d_cetak';
        $titleCaption = 'KIB D JALAN, IRIGASI, DAN JARINGAN';
        break;
    case "08":$spg = 'kib_e_cetak';
        $titleCaption = 'KIB E ASET TETAP LAINNYA';
        break;
    case "09":$spg = 'kib_f_cetak';
        $titleCaption = 'KIB F KONSTRUKSI DALAM PENGERJAAN';
        break;
    //case "10":$spg ='kib_f_cetak';break;
    case "11":$spg = 'rekap_bi_cetak';
        break;
}
if (empty($ridModul05)) {
    $ToolbarAtas_edit =
            "<td>" . PanelIcon1("?Pg=$Pg&SPg=setmutasi", "mutasi.png", "Mutasi") . "</td>
			<td>" . PanelIcon1("javascript:prosesBaru()", "new_f2.png", "Baru") . "</td>
			<td>" . PanelIcon1("javascript:prosesEdit()", "edit_f2.png", "Ubah") . "</td>
			<td>" . PanelIcon1("javascript:prosesHapus()", "delete_f2.png", "Delete") . "</td>";
}
$Main->ListData->ToolbarATas =
        "<!-- toolbar atas -->
			<div style='float:right;'>
				<script>
					function Penatausahaan_CetakHal(){
						adminForm.action='?Pg=PR&SPg=$spg';
						adminForm.target='_blank';
						adminForm.submit();		
						adminForm.target='';
					}
					function Penatausahaan_CetakAll(){
						adminForm.action='?Pg=PR&SPg=$spg&ctk=1';
						adminForm.target='_blank';
						adminForm.submit();
						adminForm.target='';
					}
				</script>			
				<table width='125'><tr>
					$ToolbarAtas_edit
					<td>" . PanelIcon1("javascript:cetakBrg()", "print_f2.png", "Barang") . "</td>
					<td>" . PanelIcon1("javascript:Penatausahaan_CetakHal()", "print_f2.png", "Halaman") . "</td>
					<td>" . PanelIcon1("javascript:Penatausahaan_CetakAll()", "print_f2.png", "Semua") . "</td>
					<td>" . PanelIcon1("javascript:adminForm.action='?Pg=PR&SDest=XLS&SPg=$spg&ctk=<!--jmlData-->';adminForm.target='_blank';adminForm.submit();", "export_xls.png", "Excel") . "</td>

				</tr></table>			
			</div>
	";

$Main->ListData->Title =
        "<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">" . $titleCaption . "</th>
	  <th>
	  	" . $Main->ListData->ToolbarATas . "
	  </th>
	</tr>
	</table>
	";

//<td>".( empty($ridModul09) && empty($disModul09) ? PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN") : PanelIcon3("javascript:alert('User tidak diijinkan melakukan Penghapusan Barang')","PENGHAPUSAN") )."</td>
//<td>".( empty($ridModul09) && empty($disModul09) ? PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN") : "" )."</td>
//<td>".( PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN")  )."</td>
//<td>".( PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN",  '',  $disModul09 )  )."</td>
//if(($Act =='')&&($Baru=='') ) {
$Main->ListData->ToolbarBawah =
        "<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"70\"><tr>		
		
		<td>" . ( PanelIcon3("javascript:setPenghapusan()", "PENGHAPUSAN", $ridModul09, $disModul09) ) . "</td>
		<!-- <td>" . ( PanelIcon3("javascript:setPemanfaatan()", "PEMANFAATAN", $ridModul06, $disModul06) ) . "</td> -->
		
		</tr></table>
	</td></tr>
	</table> 
	<script language='javascript'>
		function setPenghapusan(){
			errmsg = '';
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
			if(errmsg ==''){
				//adminForm.action='?Pg=$Pg&SPg=$SPg';
				//adminForm.Act2.value='Penghapusan';
				//adminForm.Baru.value=1;
				
				
				//adminForm.ViewList.value=0;
				//adminForm.ViewEntry.value = 1;
				//adminForm.action='?Pg=09&SPg=01';
				adminForm.action='?Pg=09&SPg=03';
				adminForm.target='_blank';
				
				
				adminForm.Act.value='Penghapusan_TambahEdit';				
				adminForm.Penghapusan_Baru.value='1';
				
				adminForm.submit();
				adminForm.target='';
			}else{
				alert(errmsg);
			}
			
		}
		function setPemanfaatan(){
			errmsg = '';
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
			if(errmsg ==''){
				adminForm.action='?Pg=$Pg&SPg=$SPg';
				adminForm.target='';
				adminForm.Act.value='Pemanfaatan_TambahEdit';				
				adminForm.Baru.value='1';
				adminForm.submit();
			}else{
				alert(errmsg);
			}
			
		}
	</script>
	";

$Main->ListData->OptWil =
        "<!--wil skpd-->
	<table width=\"100%\" class=\"adminform\">	<tr>		
		<td width=\"100%\" valign=\"top\">			
			" . WilSKPD1() . "
		</td>
		<td >
			" . $Main->ListData->labelbarang . "
		</td>
	</tr></table>";


$Hidden = "
	<input type=hidden name='PrevPageParam' value='index.php?Pg=$Pg&SPg=$SPg'>
	<input type=hidden name='Act' value=''>
	<input type=hidden name='Act2' value=$Act2>
	<input type=hidden name='Baru' value='$Baru'>
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type=hidden name='fmIDLama' id='fmIDLama' value='$fmIDLama'>
	<input type=hidden name='ViewList' value='$ViewList' >
	<input type=hidden name='ViewEntry' value='$ViewEntry' >
	";


$Entry_Script = $Penatausahaan->genEntryScriptJS();

$toolbar_bawah = $Main->ListData->ToolbarBawah;

$opt_wil = $Main->ListData->OptWil;
$toolbar_atas = $Main->ListData->Title;
}
//********************************************************************************
//create kondisi ---------------------------------------------------------------
$Kondisi = getKondisiSKPD();
if (!empty($fmCariComboIsi) && !empty($fmCariComboField)) {
    if ($fmCariComboField != 'ket' && $fmCariComboField != 'Cari Data') {
        $Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
    }
}
if (!empty($fmTahunPerolehan)) {
    $Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
$Kondisi .= empty($fmTglBuku) ? "" : " and tgl_buku ='$fmTglBuku' "; //echo $Kondisi;
$Kondisi .= empty($fmFiltThnBuku) ? "" : " and Year(tgl_buku) ='$fmFiltThnBuku' ";
$Kondisi .= ' and status_barang <> 3 ';
if (isset($filterBrg)) {
    $Kondisi .= " and concat(f,'.',g,'.',h,'.',i,'.',j,'.',tahun,'.',noreg) like '$filterBrg%' ";
}
if (isset($filterHrg)) {
    $Kondisi .= " and jml_harga = '$filterHrg%' ";
}
$KondisiTotal = $Kondisi; //echo "<br>Kondisi =".$Kondisi;

$FilterStatus = ''; //cmb2D_v3('selStatusBrg', $selStatusBrg, $Main->StatusBarang, $disStatusBrg,'Semua Status ');
//create listdata ----------------------------------------------------------------------
$Penatausahaan->genList($Kondisi, $Urutkan, $LimitHal);
//$Main->ListData->Title = str_replace('<!--jmlData-->', $jmlData, $Main->ListData->Title);
//tampil cari ----------------------------------------------------------------	
$ArFieldCari = array(
    array('nm_barang', 'Nama Barang'),
    array('thn_perolehan', 'Tahun Pengadaan'),
    array('alamat', 'Letak/Alamat'),
    array('ket', 'Keterangan')
);
$OptCari =
        "<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
	<tr > 
		<td align='Left'> &nbsp;&nbsp;
		" . CariCombo2($ArFieldCari) . "
		</td>		
	</tr>
</table>";
//tampil urut ----------------------------------------
$OrderBy = 
	"Urutkan berdasar : 
		<select name=odr1>$Odr1</select><input $AcsDsc1 type=checkbox name=AcsDsc1 value='checked'>Desc. 
		<select name=odr2>$Odr2</select><input $AcsDsc2 type=checkbox name=AcsDsc2 value='checked'>Desc.
		<select name=odr3>$Odr3</select><input $AcsDsc3 type=checkbox name=AcsDsc3 value='checked'>Desc. 
	";
$BarisPerHalaman = " Baris per halaman <input type=text name='jmPerHal' size=4 value='$Main->PagePerHal'><input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>";
$dalamRibuan = " <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu' > Dalam Ribuan ";
//filter ---------------------------------------------
$OptFilter =
        "<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
	<tr valign=\"top\">   		
		<td> 
		&nbsp;&nbsp Tampilkan : 
		" . comboTglBuku() . "
		" . comboBySQL('fmFiltThnBuku', 'select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc',
                'thnbuku', 'thnbuku', 'Semua Thn. Buku') . "
		" . TahunPerolehan2() . "&nbsp;&nbsp;" .
        $FilterStatus . "<br>&nbsp;&nbsp" .
        $OrderBy . "&nbsp;&nbsp;" .
        $dalamRibuan . "&nbsp;&nbsp;" .
        $BarisPerHalaman . "&nbsp;&nbsp;" .
        "</td>
	</tr>
	</table>";



//create page ---------------------------------------------------------------------
$Sum = $Penatausahaan->genSumHal($SPg, 'view_buku_induk2', $Kondisi);

//echo '<br> time= '.(time()-$tim);
$Main->Isi = "
	<script>
		window.onload=function(){
			cek_notify('" . $_COOKIE['coID'] . "');
			//alert('tes');
		}
	</script>
	<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
		<input type='hidden' name='Penghapusan_Baru' value='$Penghapusan_Baru'>			
		" . $divBlock . "
		<table width='100%'><tr><td>" .
        $toolbar_atas .
        $opt_wil .
        $stim .
        $OptCari .
        $OptFilter .
        "<table border='1' class='koptable' width='100%' >" .
        $Penatausahaan->genHeader($SPg) .
        $ListData .
        "</table>" .
        $Sum['listHalaman'] .
        $toolbar_bawah .
        "</td></tr></table>" .
        $Main->Entry .
        $Entry_Script .
        $Hidden .
        "</form>
	$Info";
	
}else{
	echo 'tes';
}
?>