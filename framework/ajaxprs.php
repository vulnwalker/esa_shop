<?php
// include("common/vars.php"); 
include("config.php");

function dialog_createCaption($caption='',$other_content = ''){
	return "<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
			<div class='menuBar2' style='height:20' >
			<ul>
			<!--<li><a href='javascript:PengamanForm.Close()' title='Batal' class='btdel'></a></li>
			<li><a href='javascript:PengamanSimpan.Simpan()' title='Simpan' class='btcheck'></a></li>-->
			</ul>	
			<span style='cursor:default;position:relative;left:6;top:2;color:White;font-size:12;font-weight:bold' 
				>$caption</span>
			$other_content
			</div>
			</td></tr></table>";
}

function createDialog($fmID='divdialog1',
	$Content='', $ContentWidth=623, $ContentHeight=358, 
	$caption='Dialog', $dlgCaptionContent='', $menuContent='', $menuHeight=22 ){
	
	$paddingMenuRight = 8;
	$paddingMenuLeft = 8;
	$paddingMenuBottom = 9;
	
	$marginTop= 9;
	$marginBottom= 8;
	$marginLeft = 8;
	$marginRight = 8;
	
	$captionHeight = 30;
	$dlgHeight = $captionHeight+$marginTop+$ContentHeight+$marginBottom+$menuHeight+$paddingMenuBottom;
	$dlgWidth = 642;
	$dlgWidth = $ContentWidth+$marginLeft+$marginRight+2;
	$menudlg = "
		<div style='padding: 0 $paddingMenuRight $paddingMenuBottom $paddingMenuLeft;height:$menuHeight; '>
		<div style='float:right;'>
			$menuContent
		</div>
		</div>
		"; 
	
	$dlg = 	
		dialog_createCaption($caption, $dlgCaptionContent).
		"<div id='$fmID' style='margin:$marginTop $marginLeft $marginBottom $marginRight;
			overflow:auto;width:$ContentWidth;height:$ContentHeight; border:1px solid #E5E5E5;'
		>".				
		$Content.
		'</div>'.
		$menudlg;
		
	return "<div style='width:$dlgWidth;height:$dlgHeight;
			background-color:white;
			border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; 			
			box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>$dlg</div>";
}


if(CekLogin()){
$ajaxResult = '';
$prs= $_GET['prs'];
global $HTTP_COOKIE_VARS;
//$UID = $HTTP_COOKIE_VARS['coID'];
$thn_login = $HTTP_COOKIE_VARS['coThnAnggaran'];
$tgl_buku =	$thn_login.'-00-00';
switch ($prs){
	//pemeliharaan --------------------------------------------------------------
	case 'PeliharaForm':{//plihara edit/baru	
		global $fmNOREG, $fmTANGGALPEMELIHARAAN, $fmJENISPEMELIHARAAN,
			$fmPEMELIHARAINSTANSI, $fmPEMELIHARAALAMAT, $fmSURATNOMOR, 
			$fmSURATTANGGAL, $fmBIAYA, $fmBUKTIPEMELIHARAAN, $fmKET, $fmTAMBAHASET,$fmTANGGALPerolehan;	
		global $idbi, $idbi_awal, $fmst, $idplh, $ActEntry, $AmbilData;
		
		$idplh=$_GET['idplh'];
		$idbi=$_GET['idbi'];
		$idbi_awal=$_GET['idbi_awal'];
				
		if (!empty($_GET['fmst'])){
			$title = 'Pemeliharaan - Baru';
			$fmNOREG = '';	$fmTANGGALPEMELIHARAAN=$tgl_buku; $fmTANGGALPerolehan=date('Y-m-d'); $fmJENISPEMELIHARAAN='';
			$fmPEMELIHARAINSTANSI = ''; $fmPEMELIHARAALAMAT=''; $fmSURATNOMOR=''; 
			$fmSURATTANGGAL=date('Y-m-d'); $fmBIAYA=''; $fmBUKTIPEMELIHARAAN=''; $fmKET='';
		}else{
			$title = 'Pemeliharaan - Edit';
			Pelihara_GetData($idplh);
		}
		$ajaxResult = createDialog('divFmPelihara', Pelihara_FormEntry(),620,450,$title,'',
			"<input type='button' value='Simpan' onclick ='PeliharaSimpan.Simpan()' >
			<input type='button' value='Batal' onclick ='if(confirm(\"Batalkan?\")){PeliharaForm.Close()}' >",
			22);
			
		break;
	}
	case 'PeliharaSimpan':{//pelihara simpan		
		$ajaxResult = Pelihara_ProsesSimpan();
		break;
	}
	case 'PeliharaList':{//Plihara list
		//$ajaxResult = Pelihara_List("select * from pemeliharaan where idbi_awal='".$_GET['idbi_awal']."'" );
		$Kondisi = " where idbi_awal='".$_GET['idbi_awal']."'";		
		$ajaxResult = Pelihara_List('v_pemelihara', '*',$Kondisi,'','',1,'koptable2');
		break;
	}
	case 'PeliharaHapus':{//pelihara hapus		
		$ajaxResult = Pelihara_Hapus(); //$ajaxResult = 'hapus '.$str;
		break;
	}
	
	//pengamanan ----------------------------------------------------------------
	case 'PengamanForm':{//pengamanan edit/baru			
		global $fmTANGGALPENGAMANAN, $fmJENISPENGAMANAN, $fmURAIANKEGIATAN, 
			$fmPENGAMANINSTANSI, $fmPENGAMANALAMAT, 
			$fmSURATNOMOR, $fmSURATTANGGAL, $fmBIAYA, $fmBUKTIPENGAMANAN, $fmKET, $fmTAMBAHASET;	
		global $idbi, $idbi_awal, $fmst, $idplh;
		
		$idplh=$_GET['idplh'];
		$idbi=$_GET['idbi'];
		$idbi_awal=$_GET['idbi_awal'];
		
				
		if (!empty($_GET['fmst'])){
			$fmTANGGALPENGAMANAN = $tgl_buku;
			$title = 'Pengamanan - Baru';			
		}else{
			$title = 'Pengamanan - Edit';
			Pengaman_GetData($idplh);
		}
				
		//echo "fmJENISPENGAMANAN=$fmJENISPENGAMANAN<br>";	echo "fmURAIANKEGIATAN=$fmURAIANKEGIATAN<br>";	
		$ajaxResult = createDialog('divFmPengaman', Pengaman_FormEntry(),640,430,$title,'',
			"<input type='button' value='Simpan' onclick ='PengamanSimpan.Simpan()' >
			<input type='button' value='Batal' onclick ='if(confirm(\"Batalkan?\")){PengamanForm.Close()}' >",
			22);
					
		break;
	}
	case 'PengamanSimpan':{//Pengaman simpan
		$ajaxResult = Pengaman_ProsesSimpan();
		break;
	}
	case 'PengamanList':{//Plihara list
		//$ajaxResult = Pengaman_List("select * from pengamanan where idbi_awal='".$_GET['idbi_awal']."'" );
		$Kondisi = " where idbi_awal='".$_GET['idbi_awal']."'";
		$ajaxResult = Pengaman_List('v_pengaman', '*', $Kondisi, '', '', 1, 'koptable2', FALSE, 0);
		break;
	}
	case 'PengamanHapus':{//Pengaman hapus
		
		$ajaxResult = Pengaman_Hapus(); //$ajaxResult = 'hapus '.$str;
		break;
	}
	
	//pemanfaat ----------------------------------------------------------------
	case 'PemanfaatForm':{//pengamanan edit/baru
		$idplh=$_GET['idplh'];
		$idbi=$_GET['idbi'];
		$idbi_awal=$_GET['idbi_awal'];						
		if (!empty($_GET['fmst'])){
			$title = 'Pemanfaatan - Baru';	
		}else{
			$title = 'Pemanfaatan - Edit';
			$Pemanfaat->GetData($idplh);
		}
		//echo "fmJENISPENGAMANAN=$fmJENISPENGAMANAN<br>";	echo "fmURAIANKEGIATAN=$fmURAIANKEGIATAN<br>";	
		$ajaxResult = createDialog('divFmPemanfaatan', $Pemanfaat->FormEntry(),623,490,$title,'',
			"<input type='button' value='Simpan' onclick ='PemanfaatSimpan.Simpan()' >
			<input type='button' value='Batal' onclick ='if(confirm(\"Batalkan?\")){PemanfaatForm.Close()}' >",
			22);
					
		break;
	}
	case 'PemanfaatSimpan':{
		$ajaxResult = $Pemanfaat->ProsesSimpan( );			
		break;
	}
	case 'PemanfaatList':{
		
		$Kondisi = " where idbi_awal='".$_GET['idbi_awal']."'";
		$fmKIB = $_GET['fmKIB'];
		$ajaxResult = $Pemanfaat->GetList( $Kondisi, '', '', 1, 'koptable2', FALSE, 0, FALSE);
		break;
	}	
	case 'PemanfaatList2':{/*refresh list di listdpb.php : belum dipakai */
		//sleep(3);
		//kondisi -------------------------------------------
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
		$fmKIB = $_GET['fmKIB'];
		$fmPilihThn = $_GET['fmPilihThn'];
		$fmbentuk_manfaat = $_GET['fmbentuk_manfaat'];
		$fmBARANGCARIDPB = $_GET['fmBARANGCARIDPB'];
		$Kondisi = $Pemanfaat->genKondisi(
			$fmKEPEMILIKAN, $Main->Provinsi[0], $Main->DEF_WILAYAH, 
			$_GET['fmSKPD'], $_GET['fmUNIT'], $_GET['fmSUBUNIT'],$_GET['fmSEKSI'], 
			$fmKIB, $fmPilihThn, $fmBARANGCARIDPB,$fmbentuk_manfaat);
		//limit ------------------------------------------
		$HalPMF = cekGET("HalPMF",1);
		$Limit = " limit ".(($HalPMF*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
		$noawal = $Main->PagePerHal * (($HalPMF*1) - 1);
		//order ------------------------------------------
		$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
		
		$ajaxResult = //$Pemanfaat->GetList(' Where '.$Kondisi, $Limit, $OrderBy, 2, 'koptable', FALSE, 0);
			$Pemanfaat->GetList(' Where '.$Kondisi, $LimitHalPMF, $OrderBy, 2, 'koptable', FALSE, $noawal, FALSE,$fmKIB);
		break;
	}
	case 'PemanfaatHapus':{//Pengaman hapus
		
		$ajaxResult = $Pemanfaat->Hapus(); //$ajaxResult = 'hapus '.$str;
		break;
	}
	//---- penghapusan sebagian
	case 'HapusSebagianForm':{//plihara edit/baru	
		global $fmNOREG, $fmTANGGALPENGHAPUSAN,
			$fmSURATNOMOR, 
			$fmSURATTANGGAL,$fmHARGA_AWAL,$fmHARGA_HAPUS, $fmKET;	
		global $idbi, $idbi_awal, $fmst, $idplh, $ActEntry, $AmbilData;
		
		$idplh=$_GET['idplh'];
		$idbi=$_GET['idbi'];
		$idbi_awal=$_GET['idbi_awal'];
				
		if (!empty($_GET['fmst'])){
			$title = 'Penghapusan Sebagian - Baru';
			$fmNOREG = '';	$fmTANGGALPENGHAPUSAN=$tgl_buku;
			$fmSURATNOMOR=''; 
			$fmSURATTANGGAL='';$fmHARGA_AWAL=''; $fmHARGA_HAPUS='';  $fmKET='';
			$fmHARGA_AWAL=HapusSebagian_GetHargaPerolehanBI($idbi);
		}else{
			$title = 'Penghapusan Sebagian - Edit';
			HapusSebagian_GetData($idplh);
		}
		$ajaxResult = createDialog('divFmHapusSebagian', HapusSebagian_FormEntry(),630,390,$title,'',
			"<input type='button' value='Simpan' onclick ='HapusSebagianSimpan.Simpan()' >
			<input type='button' value='Batal' onclick ='if(confirm(\"Batalkan?\")){HapusSebagianForm.Close()}' >",
			22);
			
		break;
	}
	case 'HapusSebagianSimpan':{//HapusSebagian simpan		
		$ajaxResult = HapusSebagian_ProsesSimpan();
		break;
	}
	case 'HapusSebagianList':{//Plihara list
		//$ajaxResult = HapusSebagian_List("select * from pemeliharaan where idbi_awal='".$_GET['idbi_awal']."'" );
		$Kondisi = " where idbi_awal='".$_GET['idbi_awal']."'";		
		$ajaxResult = HapusSebagian_List('v_hapus_sebagian', '*',$Kondisi,'','',1,'koptable2');
		break;
	}
	case 'HapusSebagianHapus':{//HapusSebagian hapus		
		$ajaxResult = HapusSebagian_Hapus(); //$ajaxResult = 'hapus '.$str;
		break;
	}	
	//Koreksi di bi edit----------------------------------------------------------------
	case 'Koreksi2Form':{//pengamanan edit/baru
		$idplh=$_GET['idplh'];
		$idbi=$_GET['idbi'];
		$idbi_awal=$_GET['idbi_awal'];						
		if (!empty($_GET['fmst'])){
			$title = 'Koreksi - Baru';	
			$isi['tgl'] = Date('Y-m-d');		
		}else{
			$title = 'Koreksi - Edit';
			$isi = $Koreksi2->GetData($idplh);
		}
		//echo "fmJENISPENGAMANAN=$fmJENISPENGAMANAN<br>";	echo "fmURAIANKEGIATAN=$fmURAIANKEGIATAN<br>";	
		$ajaxResult = createDialog('divFmKoreksi2', $Koreksi2->FormEntry($isi),623,150,$title,'',
			"<input type='button' value='Simpan' onclick ='Koreksi2Simpan.Simpan()' >
			<input type='button' value='Batal' onclick ='if(confirm(\"Batalkan?\")){Koreksi2Form.Close()}' >",
			22);
		break;
	}
	case 'Koreksi2Simpan':{
		$ajaxResult = $Koreksi2->ProsesSimpan( );			
		break;
	}
	case 'Koreksi2List':{
		
		$Kondisi = " where idbi_awal='".$_GET['idbi_awal']."'";
		$Order = ' order by tgl ';
		$fmKIB = $_GET['fmKIB'];
		$ajaxResult = $Koreksi2->GetList( $Kondisi, '', $Order, 1, 'koptable2', FALSE, 0, FALSE);
		break;
	}	
	case 'Koreksi2List2':{/*refresh list di listdpb.php : belum dipakai */
		//sleep(3);
		//kondisi -------------------------------------------
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
		$fmKIB = $_GET['fmKIB'];
		$fmPilihThn = $_GET['fmPilihThn'];
		$fmBARANGCARIDPB = $_GET['fmBARANGCARIDPB'];
		$Kondisi = $Koreksi2->genKondisi(
			$fmKEPEMILIKAN, $Main->Provinsi[0], $Main->DEF_WILAYAH, 
			$_GET['fmSKPD'], $_GET['fmUNIT'], $_GET['fmSUBUNIT'],$_GET['fmSEKSI'], 
			$fmKIB, $fmPilihThn, $fmBARANGCARIDPB);
		//limit ------------------------------------------
		$HalPMF = cekGET("HalPMF",1);
		$Limit = " limit ".(($HalPMF*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
		$noawal = $Main->PagePerHal * (($HalPMF*1) - 1);
		//order ------------------------------------------
		$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg ";
		
		$ajaxResult = //$Pemanfaat->GetList(' Where '.$Kondisi, $Limit, $OrderBy, 2, 'koptable', FALSE, 0);
			$Koreksi2->GetList(' Where '.$Kondisi, $LimitHalPMF, $OrderBy, 2, 'koptable', FALSE, $noawal, FALSE,$fmKIB);
		break;
	}
	case 'Koreksi2Hapus':{//Pengaman hapus
		
		$ajaxResult = $Koreksi2->Hapus(); //$ajaxResult = 'hapus '.$str;
		break;
	}
		
}

echo $ajaxResult;
}else{
	//echo "<div style='background:white;' > Anda Belum Login!'  </div>";
}
?>