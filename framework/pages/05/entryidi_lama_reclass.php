<?php

//get OPT -------------------------------------------------------------------------

if(empty($ridModul05) && empty($disModul05) ){ //cek user read only?

set_time_limit(0);



//get param ------------------------------------------------------------
{
//get POST data BI 
$UID = $HTTP_COOKIE_VARS['coID'];
$fmSKPD_old = $_POST['fmSKPD_old'];
$fmUNIT_old = $_POST['fmUNIT_old'];
$fmSUBUNIT_old = $_POST['fmSUBUNIT_old'];
$fmSKPD_new = $_POST['fmSKPD_new'];
$fmUNIT_new = $_POST['fmUNIT_new'];
$fmSUBUNIT_new = $_POST['fmSUBUNIT_new'];
if ($fmSKPD_new == ''){	$fmSKPD_new = $fmSKPD;} //$cek.= "<br> skpd new =".$fmSKPD_new;
if ($fmUNIT_new == ''){	$fmUNIT_new = $fmUNIT;} //$cek.= "<br> unit new =".$fmUNIT_new;
if ($fmSUBUNIT_new == ''){	$fmSUBUNIT_new = $fmSUBUNIT;} //$cek.= "<br> sub unit new =".$fmSUBUNIT_new;

$fmIDBARANG = cekPOST("fmIDBARANG"); //$cek .= '<br> fmIDBARANG= '.$fmIDBARANG;
$fmIDBARANG_old = cekPOST("fmIDBARANG_old");  //$cek .= '<br> fmIDBARANGold= '.$fmIDBARANG_old;
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmREGISTER = cekPOST("fmREGISTER");
$fmREGISTER_old = cekPOST("fmREGISTER_old");
$fmTAHUNPEROLEHAN = cekPOST("fmTAHUNPEROLEHAN");
$fmTAHUNPEROLEHAN_old = cekPOST("fmTAHUNPEROLEHAN_old");
$fmTAHUNANGGARAN = $fmTAHUNPEROLEHAN;
$fmTAHUNANGGARAN_old = $fmTAHUNPEROLEHAN_old;
$fmJUMLAHHARGA = cekPOST("fmJUMLAHHARGA");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGABARANG = cekPOST("fmHARGABARANG");
$fmJUMLAHBARANG=cekPOST("fmJUMLAHBARANG");
$fmASALUSUL = cekPOST("fmASALUSUL");
$fmKONDISIBARANG = cekPOST("fmKONDISIBARANG");
$fmTGLUPDATE = date("d-m-Y H:i:s");//cekPOST("fmTGLUPDATE",date("d-m-Y H:i:s")); //echo TglJamSQL($fmTGLUPDATE)."<br>";
$nilai_appraisal = $_POST["nilai_appraisal"]; 
if ($nilai_appraisal==''){$nilai_appraisal=0;} $cek .= '<br> nil appraisal= '.$nilai_appraisal;
$gambar = $_POST['gambar'];
$gambar_old = $_POST['gambar_old'];
$dokumen_ket = $_POST['dokumen_ket'];
$dokumen = $_POST['dokumen'];
$dokumen_file = $_POST['dokumen_file'];
$dokumen_file_old = $_POST['dokumen_file_old'];
$tgl_buku = $_POST['tgl_buku']; $cek.='<br> tgl buku='.$tgl_buku;

$ref_idpenanggung = $_POST['ref_idpenanggung'];
$nama2 = $_POST['nama2'];
$nip2 = $_POST['nip2'];
$jbt2 = $_POST['jbt2'];

$ref_idpemegang = $_POST['ref_idpemegang'];
$nama1 = $_POST['nama1'];
$nip1 = $_POST['nip1'];
$jbt1 = $_POST['jbt1'];

$ref_idpemegang2 = $_POST['ref_idpemegang2'];
$nama3 = $_POST['nama3'];
$nip3 = $_POST['nip3'];
$jbt3 = $_POST['jbt3'];

$nm_gedung=$_POST['nm_gedung']; 
$nm_ruang=$_POST['nm_ruang'];
$ref_idruang=$_POST['ref_idruang'];

//opt --------- 
$cbxDlmRibu = $_POST['cbxDlmRibu'];
$byId = $_GET['byId'];

$status_penguasaan_ = $_REQUEST['status_penguasaan'];

//get param detail KIB --------------------------------------------------------------
$alamat_a 	= $Main->DEF_PROPINSI; 
$alamat_b	= $_POST['alamat_b'];
$alamat_kel = $_POST['alamat_kel'];
$alamat_kec = $_POST['alamat_kec'];
$koordinat_gps = $_POST['koordinat_gps'];
$koord_bidang = $_POST['koord_bidang'];
//kib a
$fmLUAS_KIB_A = cekPOST("fmLUAS_KIB_A");
	$fmLETAK_KIB_A = cekPOST("fmLETAK_KIB_A");	
	$fmHAKPAKAI_KIB_A = cekPOST("fmHAKPAKAI_KIB_A");
	$bersertifikat = $_POST['bersertifikat']; 
	$fmTGLSERTIFIKAT_KIB_A = cekPOST("fmTGLSERTIFIKAT_KIB_A");
	$fmNOSERTIFIKAT_KIB_A = cekPOST("fmNOSERTIFIKAT_KIB_A");
	$fmPENGGUNAAN_KIB_A = cekPOST("fmPENGGUNAAN_KIB_A");
	$fmKET_KIB_A = cekPOST("fmKET_KIB_A");
//kib B
$fmMERK_KIB_B 	= cekPOST("fmMERK_KIB_B");
	$fmUKURAN_KIB_B = cekPOST("fmUKURAN_KIB_B");
	$fmBAHAN_KIB_B 	= cekPOST("fmBAHAN_KIB_B");
	$fmPABRIK_KIB_B = cekPOST("fmPABRIK_KIB_B");
	$fmRANGKA_KIB_B = cekPOST("fmRANGKA_KIB_B");
	$fmMESIN_KIB_B 	= cekPOST("fmMESIN_KIB_B");
	$fmPOLISI_KIB_B = cekPOST("fmPOLISI_KIB_B");
	$fmBPKB_KIB_B 	= cekPOST("fmBPKB_KIB_B");
	$fmKET_KIB_B 	= cekPOST("fmKET_KIB_B");
//kib c
$fmKONDISI_KIB_C=cekPOST("fmKONDISI_KIB_C");
	$fmTINGKAT_KIB_C=cekPOST("fmTINGKAT_KIB_C");
	$fmBETON_KIB_C=cekPOST("fmBETON_KIB_C");
	$fmLUASLANTAI_KIB_C=cekPOST("fmLUASLANTAI_KIB_C");
	$fmLETAK_KIB_C=cekPOST("fmLETAK_KIB_C");	
	$fmTGLGUDANG_KIB_C=cekPOST("fmTGLGUDANG_KIB_C");
	$fmNOGUDANG_KIB_C=cekPOST("fmNOGUDANG_KIB_C");
	$fmLUAS_KIB_C=cekPOST("fmLUAS_KIB_C");
	$fmSTATUSTANAH_KIB_C=cekPOST("fmSTATUSTANAH_KIB_C");
	$fmNOKODETANAH_KIB_C=cekPOST("fmNOKODETANAH_KIB_C");
	//$fmNOKODELOKTANAH_KIB_C=cekPOST("fmNOKODELOKTANAH_KIB_C");
	$fmKET_KIB_C=cekPOST("fmKET_KIB_C");
//kib d
$fmKONSTRUKSI_KIB_D=cekPOST("fmKONSTRUKSI_KIB_D");
	$fmPANJANG_KIB_D=cekPOST("fmPANJANG_KIB_D");
	$fmLEBAR_KIB_D=cekPOST("fmLEBAR_KIB_D");
	$fmLUAS_KIB_D=cekPOST("fmLUAS_KIB_D");
	$fmALAMAT_KIB_D=cekPOST("fmALAMAT_KIB_D");	
	$fmTGLDOKUMEN_KIB_D=cekPOST("fmTGLDOKUMEN_KIB_D");
	$fmNODOKUMEN_KIB_D=cekPOST("fmNODOKUMEN_KIB_D");
	$fmSTATUSTANAH_KIB_D=cekPOST("fmSTATUSTANAH_KIB_D");
	$fmNOKODETANAH_KIB_D=cekPOST("fmNOKODETANAH_KIB_D");
	$fmKONDISI_KIB_D=cekPOST("fmKONDISI_KIB_D");
	$fmKET_KIB_D=cekPOST("fmKET_KIB_D");
//kib E
$fmJUDULBUKU_KIB_E=cekPOST("fmJUDULBUKU_KIB_E");
	$fmSPEKBUKU_KIB_E=cekPOST("fmSPEKBUKU_KIB_E");
	$fmSENIBUDAYA_KIB_E=cekPOST("fmSENIBUDAYA_KIB_E");
	$fmSENIPENCIPTA_KIB_E=cekPOST("fmSENIPENCIPTA_KIB_E");
	$fmSENIBAHAN_KIB_E=cekPOST("fmSENIBAHAN_KIB_E");
	$fmJENISHEWAN_KIB_E=cekPOST("fmJENISHEWAN_KIB_E");
	$fmUKURANHEWAN_KIB_E=cekPOST("fmUKURANHEWAN_KIB_E");
	$fmKET_KIB_E=cekPOST("fmKET_KIB_E");
//kib f
$fmBANGUNAN_KIB_F=cekPOST("fmBANGUNAN_KIB_F");
	$fmTINGKAT_KIB_F=cekPOST("fmTINGKAT_KIB_F");
	$fmBETON_KIB_F=cekPOST("fmBETON_KIB_F");
	$fmLUAS_KIB_F=cekPOST("fmLUAS_KIB_F");
	$fmLETAK_KIB_F=cekPOST("fmLETAK_KIB_F");	
	$fmTGLDOKUMEN_KIB_F=cekPOST("fmTGLDOKUMEN_KIB_F");
	$fmNODOKUMEN_KIB_F=cekPOST("fmNODOKUMEN_KIB_F");
	$fmTGLMULAI_KIB_F=cekPOST("fmTGLMULAI_KIB_F");
	$fmSTATUSTANAH_KIB_F=cekPOST("fmSTATUSTANAH_KIB_F");
	$fmNOKODETANAH_KIB_F=cekPOST("fmNOKODETANAH_KIB_F");
	$fmKET_KIB_F=cekPOST("fmKET_KIB_F");
	
//$fmNOREG = cekPOST('fmNOREG');
$fmNewNoBrg = cekPOST('fmNewNoBrg');
$fmUID = cekPOST('fmUID');
$fmTglUpdate = cekPOST('fmTglUpdate');
$tgl_sensus = cekPOST('tgl_sensus');
$tahun_sensus = cekPOST('tahun_sensus');
$cidBI = cekPOST('cidBI');// echo"<br>cnt cidbi=".(count($cidBI));
$KIB = cekGET('KIB');
$fmIdAwal = $_POST['fmIdAwal'];
}


//echo"<br>cidBI[0]=".$cidBI[0]; //echo"<br>act=$Act";
//Proses EDIT ----------------------------------------------------------------------------
$Act="Edit";
if($Act=="Edit"){			
	$sqry = "select * from buku_induk where id='$fmIDLama'"; $cek .= '<br> qry edit= '.$sqry;
	$Qry = mysql_query($sqry);
	$isi = mysql_fetch_array($Qry);	
	if ($isi['status_barang'] != 3){			
		Penatausahaan_GetData( $fmIDLama  ,TRUE); //echo "<br>fmUID=$fmUID";		
		$status_penguasaan_ = $isi['status_penguasaan'];		
	}else{
		$errmsg = 'Data Sudah Dihapus Tidak Bisa Di Edit!';
	}
		if ($errmsg ==''){
			$Baru = 0;
		}else{
			$Act = '';
			$Info = "<script>alert('$errmsg');window.close()</script>";
		}	
}


$InfoIDBARANG = explode(".",$fmIDBARANG); $cek .= '<br> InfoIDBARANG[0]= '.$InfoIDBARANG[0];
$MyFieldKIB="";
$InfoKIB = "";

//Proses Baru -------------------------------------------------------------------------------
$MyField ="tgl_buku,fmKEPEMILIKAN,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmREGISTER,fmTAHUNPEROLEHAN,fmJUMLAHBARANG,fmSATUAN,fmHARGABARANG,fmASALUSUL,fmTGLUPDATE,fmTAHUNANGGARAN";
if($Baru=="1" && $Act=="Baru"){	
	KosongkanField("nilai_appraisal,dokumen_ket,dokumen,dokumen_file,dokumen_file_old,gambar,gambar_old,fmIDBARANG,fmNMBARANG,fmREGISTER,fmTAHUNPEROLEHAN,fmJUMLAH,fmSATUAN,fmHARGABARANG,fmASALUSUL,fmJUMLAHBARANG,fmKONDISIBARANG");
	KosongkanField($MyFieldKIB);
	$InfoIDBARANG[0] = "";
	$fmASALUSUL = '';//'1';	
	$fmKONDISIBARANG='';//'1';
	$fmJUMLAHBARANG = 1;
}

//proses smpan --------------------------------------------------------------------------------
if($Act=="Simpan"){
	$barutmp = $Baru;
	Penatausahaan_Proses();
	if ($Sukses ){
		//echo "<br>baru=$Baru";
		$fmNewNoBrg = "$fmKEPEMILIKAN.{$Main->Provinsi[0]}.$fmSKPD.$fmUNIT.$fmSUBUNIT.$InfoIDBARANG[0].$InfoIDBARANG[1].$InfoIDBARANG[2].$InfoIDBARANG[3].$InfoIDBARANG[4].$fmTAHUNPEROLEHAN.$fmREGISTER";
		$Info = "<script>
			alert('Data telah di simpan');
			window.close ();
			</script>";
	}
}


//Proses HAPUS -----------------------------------------------------------------------------
if($Act=="Hapus" && count($cidBI) > 0){
	Penatausahaan_Proses();
}




$Main->Entry = "
	<A Name=\"ISIAN\"></A>		
		$entry->optionlist
		$entry->listdata
		<br>
	";


if ($Act=='Edit'){
	$btnTampilKib ='';
	$jmlBrgReadonly='readonly=""';	
}else{
	//$btnTampilKib ="<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.submit();\" value='Tampilkan KIB'>";
	$btnTampilKib ="<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.submit();\" value='Tampilkan KIB'>";
	$jmlBrgReadonly='';
}

//echo "<br>baru=$Baru";
if ($Baru=='1'){
	
	$titleAct = 'Baru';
}else{
	$titleAct = 'Edit';
}
/*$titleAct = "<tr><td colspan='3' height='40'>
	  	<span style='font-size: 18px;font-weight: bold;color: #C64934;'>".$titleAct."</span>
		</td></tr>";
*/

if($Baru=="0" || $Baru=="1" || $Act=="Baru" || $Act=="Tambah" || $Act=="TambahBaru"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{

$sQrLastNoReg = 'select max(noreg) as lastNo from view_buku_induk2 where concat(a1,a,c,d,e,f,g,h,i,j,tahun) ="'.
			$fmKEPEMILIKAN.$Main->Provinsi[0].$fmSKPD.$fmUNIT.$fmSUBUNIT.$InfoIDBARANG[0].$InfoIDBARANG[1].$InfoIDBARANG[2].$InfoIDBARANG[3].$InfoIDBARANG[4].$fmTAHUNPEROLEHAN.'" ';
//echo '<br> qrlastnoreg= '.$sQrLastNoReg;
$lastNoReg = table_get_value($sQrLastNoReg,'lastNo');
if ($lastNoReg==''){
	$lastNoReg='0';
}


$Main->ListData->labelbarang=
	"<table width=214 align=right style='border:1px solid #ddd;margin:0 8 0 0; ' cellpadding=0 cellspacing=0>
				<tr><td>
				<INPUT TYPE=TEXT value='$fmKEPEMILIKAN' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934' readonly>.
				<INPUT TYPE=TEXT value='{$Main->Provinsi[0]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				
				<INPUT TYPE=TEXT value='00' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmSKPD' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmUNIT' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT id='infofmTAHUNPEROLEHAN' TYPE=TEXT value='".substr($fmTAHUNPEROLEHAN,2,2)."' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmSUBUNIT' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>
				</td></tr>
			
				<tr><td>
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[0]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934' readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[1]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[2]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[3]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[4]}' style='width:22px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT id='infofmREGISTER' TYPE=TEXT value='$fmREGISTER' style='width:59px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000CC'  readonly>
				</td></tr>
			</table>";

$Entry->BI ="<A NAME='FORMENTRY'></A>".Penatausahaan_FormEntry_reclass($fmIDLama);
//echo "<br>Entry->BI =$Entry->BI";

$Entry->toolbar="
	<script>
		function prosesSimpan(){
			document.body.style.overflow='hidden';
			addCoverPage('coverpage',100);
			document.getElementById('btsave').setAttribute('href', ' ');
			adminForm.Act.value='Simpan';
			adminForm.submit();
		}
	</script>
	<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"50\"><tr>
			<!--<td>
			".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."
			</td>-->
			<td>
			<!--".PanelIcon1("javascript:document.getElementById('btsave').setAttribute('href', ' ');adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan", "btsave")."-->
			".PanelIcon1("javascript:prosesSimpan()","save_f2.png","Simpan", "btsave")."
			</td>
			<td>			
			".PanelIcon1("javascript:window.close();","cancel_f2.png","Batal")."
			</td>
			</tr>
		</table>
	</td></tr>
	</table>
	";
	

$Main->Entry .= "
	<!--entry_-->
	$Entry->BI
	$DetilKIB
	<br>
	$Entry->toolbar
	<!--opt-->
	<!--	<input type=text name='cbxDlmRibu'  value='$cbxDlmRibu'>-->

	";
}//END IF

	
}else{
	if($Act != '' ){$Info = "<script>alert('User tidak diijinkan merubah data!')</script>";}
	//$Main->Entry .= "$Info";
	$Act = '';
	$Baru = 0;
}




$Entry->hidden = 
	"<input type=hidden name='fmIDBARANG_old' value='$fmIDBARANG_old'>
	<input type=hidden name='fmNewNoBrg' id='fmNewNoBrg' value='$fmNewNoBrg'>	
	<input type=hidden name='fmWIL' value='$fmWIL'>	
	<input type=hidden name='fmSKPD_old' value='$fmSKPD_old'>
	<input type=hidden name='fmUNIT_old' value='$fmUNIT_old'>
	<input type=hidden name='fmSUBUNIT_old' value='$fmSUBUNIT_old'>
	<input type=hidden name='fmREGISTER_old' value='$fmREGISTER_old'>	
	<input type=hidden name='fmTAHUNPEROLEHAN_old' value='$fmTAHUNPEROLEHAN_old'>";


$cek = '';
$Main->Entry .= "
	".$Entry->hidden."
	".$Entry->scripts."

	
	$Info
	".$cek;



?>