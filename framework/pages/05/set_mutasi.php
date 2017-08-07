<?php



//Penghapusan_daftar_mutasi();
$HalHPS = cekPOST("HalHPS",1);
$fmPenghapusanSKPD = cekPOST('fmPenghapusanSKPD');//cekPOST("fmPenghapusanSKPD");
$fmPenghapusanUNIT = cekPOST("fmPenghapusanUNIT");
$fmPenghapusanSUBUNIT = cekPOST("fmPenghapusanSUBUNIT");
$fmPenghapusanSEKSI = cekPOST("fmPenghapusanSEKSI");

//act
$cidBI 	= cekPOST("cidBI");
$fmIDLama = cekPOST("fmIDLama"); 
//cekPOST('idBI'); 
$Act = cekPOST("Act");
$Baru = cekPOST("Baru");

//BI
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmSEKSI = cekPOST("fmSEKSI");
//$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);

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

$fmHARGABARANGBELI = cekPOST("fmHARGABARANGBELI");
$fmHARGABARANGATRIBUSI = cekPOST("fmHARGABARANGATRIBUSI");
$fmHARGABARANG = cekPOST("fmHARGABARANG");

$fmJUMLAHBARANG=cekPOST("fmJUMLAHBARANG");
$fmASALUSUL = cekPOST("fmASALUSUL");
//$fmSTATUSBARANG = cekPOST("fmSTATUSBARANG");
$fmKONDISIBARANG = cekPOST("fmKONDISIBARANG");
$fmTGLUPDATE = cekPOST("fmTGLUPDATE",date("d-m-Y H:i:s")); //echo TglJamSQL($fmTGLUPDATE)."<br>";
$nilai_appraisal = $_POST["nilai_appraisal"]; 
	if ($nilai_appraisal==''){$nilai_appraisal=0;} //$cek .= '<br> nil appraisal= '.$nilai_appraisal;
$gambar = $_POST['gambar']; //echo "gambar = $gambar <br>";
$gambar_old = $_POST['gambar_old'];
$dokumen_ket = $_POST['dokumen_ket'];
$dokumen = $_POST['dokumen'];
$dokumen_file = $_POST['dokumen_file'];
$dokumen_file_old = $_POST['dokumen_file_old'];
$tgl_buku = $_POST['tgl_buku'];// $cek.='<br> tgl buku='.$tgl_buku;
$tgl_sensus = $_POST['tgl_sensus'];
$UID = $HTTP_COOKIE_VARS['coID'];
$fmIdAwal = $_POST['fmIdAwal'];

$no_ba = $_POST['no_ba'];
$tgl_ba = $_POST['tgl_ba'];
$no_spk = $_POST['no_spk'];
$tgl_spk = $_POST['tgl_spk'];
$penggunabi = $_POST['penggunabi'];

setWilSKPD();


//get param detail KIB --------------------------------------------------------------
$alamat_a 	= $Main->DEF_PROPINSI; 
$alamat_b	= $_POST['alamat_b'];
$alamat_c	= $_POST['alamat_c'];
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

$fmUID = cekPOST('fmUID');
$fmTglUpdate = cekPOST('fmTglUpdate');
$fmSTATUSASET = cekPOST('fmSTATUSASET');
	



if($Act=='Simpan' && $fmIDLama != ''){
	$Info ='';
	
	$old = mysql_fetch_array(mysql_query("select * from penghapusan where id_bukuinduk =  $fmIDLama  "));
	
	if($old['sudahmutasi']==1) $Info = "Barang sudah mutasi !";
	
	if($Info == ''){
		Penatausahaan_Proses(TRUE);
		if ($Sukses ){
			//salin dokumen -------------
			//Dok_CopyByIdBI($fmIDLama)
			//salin data Penyusutan
			/***
			$binew = mysql_fetch_array(mysql_query("select * from buku_induk where id_lama='$fmIDLama'"));
			
			$aqry = " insert into penyusutan ".
					" (tgl,tahun,sem,bulan,idbi,idbi_awal,harga,uid,tgl_update,thn_perolehan,hrg_perolehan,hrg_rehab,masa_manfaat,residu,akum_nilai_buku, ".
					" nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke,sisa_masa_manfaat,masa_manfaat_rehab,sisa_masa_manfaat_thn,stat,bulan_awl,staset, ".
					" c,d,e,e1,f,g,h,i,j,ket,jns_trans2,id_koreksi,tgl_create,uid_create,id_koreksi_tambah) ".
					" select ".
					" tgl,tahun,sem,bulan,".$binew['id'].",idbi_awal,harga,uid,tgl_update,thn_perolehan,hrg_perolehan,hrg_rehab,masa_manfaat,residu,akum_nilai_buku, ".
					" nilai_buku_stlh_susut,akum_susut,akum_masa_manfaat,thn_ke,sisa_masa_manfaat,masa_manfaat_rehab,sisa_masa_manfaat_thn,stat,bulan_awl, staset, ".
					" c,d,e,e1,f,g,h,i,j,ket,15 ,id_koreksi,tgl_create,uid_create,id_koreksi_tambah ".
					" from penyusutan where idbi = $fmIDLama ";
			$ins = mysql_query($aqry);
			//$err = mysql_error();
			
			//salin data penyusutan di buku_induk id lama
			
			if($ins){
				$bi_old = mysql_fetch_array(mysql_query("select masa_manfaat,nilai_sisa,thn_susut_aw,thn_susut_ak,
									bln_susut_aw,bln_susut_ak,masa_manfaat_tot,stop_susut
									from buku_induk where id='$fmIDLama'"));
				//salin data penyusutan buku_induk lama ke baru
				$qqry="update buku_induk set masa_manfaat='".$bi_old['masa_manfaat']."',
						nilai_sisa='".$bi_old['nilai_sisa']."',
						thn_susut_aw='".$bi_old['thn_susut_aw']."',
						thn_susut_ak='{$bi_old['thn_susut_ak']}',
						masa_manfaat_tot='".$bi_old['masa_manfaat_tot']."',
						stop_susut='".$bi_old['stop_susut']."'
						where id='".$binew['id']."'
						";
						
				$qry2=mysql_query($qqry);
				
				if($qry2){
			
					//update sudah mutasi -------
					$sqry = "update penghapusan set sudahmutasi=1 where id_bukuinduk =  $fmIDLama ";
					//echo '<br>qry update penghapusan= '.$sqry;
					$qry = mysql_query($sqry);
					if ($qry==FALSE) $Info = "<script>alert('Gagal update sudah mutasi ')</script>";
				}else{
					
					$Info = "<script>alert('Gagal Update data penyusutan ke barang baru ' )</script>";//".mysql_errno($qry2) . " ".mysql_error($qry2)."
				}
			}else{
				//echo $fmIDLama.'<br>';
				//echo $aqry;
				$Info = "<script>alert('Gagal Salin Penyusutan ". $err ."!'  )</script>";
			}
			***/
			
			
		}
	}else{
		$Info = "<script>alert('$Info')</script>";
	}
}


$fmIDLama='';//$idBI = '';
//echo "idBi = ".$idBI;

//order by ------------------------------
$AcsDsc1 = cekPOST("AcsDsc1");
$odr1 = cekPOST("odr1");
$selThnHapus = $odr1 == "year(tgl_penghapusan)" ? " selected " :  ""; //selected
$opt1 = "<option value=''>--</option><option $selThnHapus value='year(tgl_penghapusan)'>Thn. Hapus</option>";
$ListOrderBy = " 
	<div style='float:left;padding:4 4 0 8; border-left:1px solid #E5E5E5;'>
	Urutkan berdasar : 
		<select name=odr1>$opt1</select>
	</div>
	<div style='float:left;padding:4 4 0 0;'>
		<input $AcsDsc1 type=checkbox name=AcsDsc1 value='checked'>Desc.
	</div>
	";
//filter -------------------------------
$fmFiltThnHapus = cekPOST('fmFiltThnHapus');
$fmKIB = cekPOST('fmKIB');
$PilihKIB = "<div style='float:left;padding:4 8 0 8;'> KIB  ".cmb2D_v2('fmKIB',$fmKIB,$Main->ArKIB,'','Semua')."</div>";
$PilihThnHapus = "<div style='float:left;padding:4 8 0 8; border-left:1px solid #E5E5E5;'>".
	comboBySQL('fmFiltThnHapus','select year(tgl_penghapusan)as thnhapus from penghapusan where mutasi=1 group by thnhapus desc',
			'thnhapus', 'thnhapus', 'Semua Thn. Hapus').
	"</div>";
$ListFilter = "
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr valign=\"top\">   		
		<td> 
		&nbsp;&nbsp 
		$PilihKIB		
		$PilihThnHapus
		$ListOrderBy
		<div style='float:left;padding:4 8 0 8; border-left:1px solid #E5E5E5;'>
			<input type=button onClick=\"adminForm.action='';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>
		</div>
		</td>
	</tr>
	</table>";
$tampilDaftarMutasi = 
	"<table class=\"adminheading\">
		<tr>
	  		<th height=\"47\" class=\"user\">Daftar Mutasi Barang</th>	  		
		</tr>
		</table>
		
	<table class='adminform'><tr>
	<td height='40' colspan=''>
	  		<span style='font-size: 14px; font-weight: bold; color: rgb(198, 73, 52);'>Mutasi Barang Dari</span>
			</td></tr>
	<tr><td>".
	Penghapusan_daftar_mutasi_OPD().
	"</td></tr></table><br>".
	$ListFilter.
	Penghapusan_daftar_mutasi();
	//"";
//echo "fmIdLama = ".$fmIDLama;
	
//if ( $idBI != '' ){
//if ( ($fmIDLama != '') && ($Act != '')  ){
if ( $fmIDLama != ''  ){
	
	$Entry_scripts = "
		<script language='javascript'>
		function setMutasi(){			
			if(confirm('Mutasi Barang ini?')){								
				document.getElementById('btsave').setAttribute('href', ' ');
				document.body.style.overflow='hidden';
				addCoverPage('coverpage',100);
				adminForm.Act.value='Simpan';
				adminForm.submit();
			}			
		}
		</script>
	";
	//$titleAct = "Mutasi";
	$optWIL = "<!--wil skpd-->
		<br><table width=\"100%\" class=\"adminform\">	
			<tr><td colspan='3' height='40'>
	  		<span style='font-size: 14px;font-weight: bold;color: #C64934;'>Mutasi Barang Ke </span>
			</td></tr>
			<tr>		
			<td width=\"100%\" valign=\"top\">			
			".WilSKPD1()."
			</td>
			<td >
			<!--labelbarang-->				
			".$Main->ListData->labelbarang."	
			</td>
		</tr></table>";
	$Entry_toolbar="
		<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>			
			<td>
			".PanelIcon1("javascript:setMutasi()","save_f2.png","Simpan", "btsave")."
			</td>
			<td>
			".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."
			</td>
			</tr>
			</table>
		</td></tr>
		</table>
		";
	//if ( $Baru == 1){
	if($Act == 'TambahEdit'){
		//echo "<br>fmIDLama=$fmIDLama ";
		//global $fmIdAwal;
		Penatausahaan_GetData( $fmIDLama, FALSE);
		$rec = table_get_rec('select * from penghapusan where id_bukuinduk = '.$fmIDLama); //echo "<br>id bi =".$fmIDLama;
		
		$tgl_buku = $rec['tgl_penghapusan'];//= tgl hapus
		$fmREGISTER = '';
		$fmASALUSUL = 4;//mutasi
		
		//$gambar = $rec['gambar'];// dari kondisi gambar akhir di penghapusan
		//echo "<br>gambar=".$gambar;
		$gambar_old = $gambar;
		$fmKONDISIBARANG = $rec['kondisi_akhir'];
		//echo "<br>tgl_sensus=$tgl_sensus";
		/*if ($fmIdAwal == 0 || $fmIdAwal == '' || empty($fmIdAwal)){
			$fmIdAwal = $fmIDlama;
		}*/
		//echo "<br>fmIDLama=$fmIDLama fmIdAwal=$fmIdAwal";
		//$Baru=0; //$Act = '';		
	}
	
	$InfoIDBARANG = explode(".",$fmIDBARANG); //$cek .= '<br> InfoIDBARANG[0]= '.$InfoIDBARANG[0];
	$sQrLastNoReg = 'select max(noreg) as lastNo from view_buku_induk2 where concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun) ="'.
			$fmKEPEMILIKAN.$Main->Provinsi[0].$Main->DEF_WILAYAH.$fmSKPD.$fmUNIT.$fmSUBUNIT.$fmSEKSI.			
			$InfoIDBARANG[0].$InfoIDBARANG[1].$InfoIDBARANG[2].$InfoIDBARANG[3].$InfoIDBARANG[4].$fmTAHUNANGGARAN.'" ';
	//echo '<br> qrlastnoreg= '.$sQrLastNoReg;
	$lastNoReg = table_get_value($sQrLastNoReg,'lastNo');
	if ($lastNoReg==''){
		$lastNoReg='0';
	}
	$tampilEntryBI =
		$Entry_scripts.
		"<A NAME='FORMENTRY'></A>".
		$optWIL."<br>".
		Penatausahaan_FormEntry($fmIDLama, FALSE, TRUE)."<br>".		
		$Entry_toolbar;			
}


//$cek='';
$Main->Isi = 
	"<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">".
	$tampilDaftarMutasi.	
	$tampilEntryBI.
	
	"
	<!--<input type=hidden name='idBi' value='$idBI'>-->
	<!--<input type=text name='fmIdAwal' value='$fmIdAwal'>-->
	<input type=hidden name='fmIDLama' value='$fmIDLama'>
	<input type=hidden name=\"boxchecked\" value=\"0\" />
	<input type=hidden name='Act'>
	<input type=hidden name='Baru' value='$Baru'>".
	"</form>".
	$Info
	;

?>