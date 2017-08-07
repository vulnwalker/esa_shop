<?php
/* gak dipakai lagi */
//$HalBI = cekPOST("HalBI",1);
//$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$HalDPB = cekPOST("HalDPB",1);


$cidBI = cekPOST("cidBI");
$cidDPB = cekPOST("cidDPB");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
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
$fmIDBUKUINDUK = cekPOST("fmIDBUKUINDUK");
$fmTAHUNPEROLEHAN = cekPOST("fmTAHUNPEROLEHAN");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmBARANGCARIDPB = cekPOST("fmBARANGCARIDPB"); //echo "<br>fmBARANGCARIDPB=".$fmBARANGCARIDPB;
$jmlDataDPB = cekPOST("jmlDataDPB");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";
$cidBI = CekPOST("cidBI");
$cidDPB = CekPOST("cidDPB");
$SSPg = cekGET("SSPg");
//$PgPrev = cekPOST("PgPrev");
//$SPgPrev = cekPOST("SPgPrev");
//echo "<br>pgprev=".$PgPrev." SPgPrev=".$SPgPrev;

if(($Act=="TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidDPB) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidDPB) == 1 ? " disabled ":"";


//Pemanfaatan_Proses();
//$ListData = Pemanfaatan_GetList();


if (empty($disModul06) && empty($ridModul06)){
	$Toolbar_edit = 
		"<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Edit';adminForm.submit()","edit_f2.png","Ubah")."</td>
		<td>".PanelIcon1("javascript:if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){adminForm.Act.value='Hapus';adminForm.submit();}","delete_f2.png","Hapus")."</td>"
		;
}
$Toolbar = "
			<table width=\"50\"><tr>			
			$Toolbar_edit	
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=manfaat_cetak&SSPg=$SSPg&ctk=$jmlDataDPB';adminForm.target='_blank';adminForm.submit();","print_f2.png","Cetak")."</td>
			</tr></table>
		";
$subpagetitle = 'Buku Inventaris';
switch($SSPg){
	case '03': break;
	case '04': $subpagetitle ='KIB A'; break;
	case '05': $subpagetitle ='KIB B'; break;
	case '06': $subpagetitle ='KIB C'; break;
	case '07': $subpagetitle ='KIB D'; break;
	case '08': $subpagetitle ='KIB E'; break;
	case '09': $subpagetitle ='KIB F'; break;
	
}
$Title = "
	<table class=\"adminheading\">
	<tr>
  	<th height=\"47\" class=\"user\">
	<div style='padding: 0pt 0pt 0pt 8px;'>
		Daftar Pemanfaatan Barang Milik Daerah<br> $subpagetitle
	</div>
	</th>
	<th>$Toolbar</th>
	</tr>
	</table>";
$OptCari = "
	<table width=\"100%\" height=\"100%\" class='adminform'>
	<tr valign=\"center\">   
		<td width=70 >Nama Barang</td>
		<td width=10 >:</td>
		<td>
		<input type=text name='fmBARANGCARIDPB' value='$fmBARANGCARIDPB'>&nbsp
		<input type=button value='Cari' onclick=\"adminForm.target='';adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit()\">
		</td>
	</tr>
	</table>";
$Hidden = "
	<input type=hidden name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
	<input type=hidden name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
	<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
	<input type=hidden name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
	
	<input type=hidden name='Act'>
	<input type=hidden name='Baru' value='$Baru'>
	<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	
	<!--<input type=hidden name='Act'>
	<input type=hidden name='Baru' value='$Baru'>
	<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
	<input type=\"hidden\" name=\"option\" value=\"com_users\" />
	<input type=\"hidden\" name=\"task\" value=\"\" />
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />-->

";

//ENTRY --------------------------------------------------------------------------------------
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	//$FormEntry = Pemanfaatan_FormEntry();
}


$Main->Isi = "
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg\">
	$Title
	<table class='adminform'><tr><td>
	".WilSKPD1()."
	</td></tr></table>		
	$OptCari 
	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
		$ListData 
	</table>		
	$FormEntry	
	$Hidden
	$Info
</form>
";



?>