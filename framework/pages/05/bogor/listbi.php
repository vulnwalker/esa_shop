<?php
$cek = '';
$tim = time(); //$stim = time().' <br>';
//$tipebi = $_REQUEST['tipebi'];
//1.5detik
$SPg = isset($HTTP_GET_VARS["SPg"]) ? $HTTP_GET_VARS["SPg"] : "";
if ($SPg == '') {
	$SPg = '03';
}

//echo "idpilih = ".$_GET['idpilih'];
//ajx - json ---------
$ErrMsg = '';
if ($_GET['idpilih']==''){
	$optstring = stripslashes($_GET['opt']);
	$Opt = json_decode($optstring); //$page = json_encode(",cek="+$Opt->idprs);
	$idpilih = $Opt->daftarProses[$Opt->idprs];		
}else{
	$idpilih = $_GET['idpilih'];
}
//$content = $idpilih;

switch ($idpilih){
	case 'formCari':{
		//get param ------------------------------------------------------------------------		
		$fmID = cekPOST("fmID", 0);
		$fmWIL = cekPOST("fmWIL");
		$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN", $_POST['fmTahunPerolehan']);
		$Cari = cekPOST("Cari");
		$CariBarang = cekGET("CariBarang");
		$CariRekening = cekGET("CariRekening");
		$selStatusBrg = $_POST["selStatusBrg"];				
		$Penghapusan_Baru = cekPOST("Penghapusan_Baru", "1");
		$cidBI = cekPOST("cidBI");
		$Act = cekPOST("Act"); //echo"<br> Act=".$Act;
		$Baru = cekPOST("Baru", "");
		$Info = "";

		// tampil bidang ----------------------------------
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;		
		$fmSKPD = cekPOST('fmSKPD'); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
		$fmUNIT = cekPOST('fmUNIT'); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
		$fmSUBUNIT = cekPOST('fmSUBUNIT');
		$fmSEKSI = cekPOST('fmSEKSI');
		setWilSKPD(); //echo "<br> fmSKPD ".$fmSKPD;
		$barcode = $Main->BARCODE_ENABLE ?
			"<span style='color:red'>BARCODE</span><br>".
				"<input type='TEXT' value='' 
					id='barcode_input' name='barcode_input'
					style='font-size:24;width: 369px;' 
					size='28' maxlength='28'
					
				><span id='barcode_msg' name='barcode_msg' ></span> ":"";
					
		//if($tipebi=='pilih')	$barcode ='';	
		 
		$Main->ListData->OptWil =
        	"<!--wil skpd-->
			<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD1() . "
			</td>
			<td style='padding:0 8 0 0' >
				" . 
				//$Main->ListData->labelbarang .
				
				$barcode.
			"</td>
			</tr></table>";
		$opt_wil = $Main->ListData->OptWil;

		//********************************************************************************* {
		$Act2 = cekPOST('Act2', '');
		$ViewList = cekPOST('ViewList', 1);
		$ViewEntry = cekPOST('ViewEntry', 0);
		if ($Act == 'Hapus' || $Act == 'barcode') {	Penatausahaan_Proses();	}
		//if ($Act == 'Hapus' ) {	Penatausahaan_Proses();	}
		
		$Hidden = $Penatausaha->genHidden();
		$Entry_Script = $Penatausaha->genEntryScriptJS();
		$toolbar_bawah = $Penatausaha->genToolbarBawah();// $Main->ListData->ToolbarBawah;
		//********************************************************************************
		
		$Opsi = $Penatausaha->getDaftarOpsi();	//echo 'HalDefault='.$_POST['HalDefault'];	//echo '<br> time= '.(time()-$tim);
		

		
		echo 
		"<head>
	<title>::ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah)</title>
	<meta name='format-detection' content='telephone=no'>
	<meta name='ROBOTS' content='NOINDEX, NOFOLLOW'>
		
		
	<link rel='stylesheet' href='css/menu.css' type='text/css'>
	<link rel='stylesheet' href='css/template_css.css' type='text/css'>
	<link rel='stylesheet' href='css/theme.css' type='text/css'>
	<link rel='stylesheet' href='dialog/dialog.css' type='text/css'>
	<link rel='stylesheet' href='lib/chatx/chatx.css' type='text/css'>	
	<link rel='stylesheet' href='css/base.css' type='text/css'>
	
	 	
	<script language='JavaScript' src='lib/js/JSCookMenu_mini.js' type='text/javascript'></script>
	<script language='JavaScript' src='lib/js/ThemeOffice/theme.js' type='text/javascript'></script>
	<script language='JavaScript' src='lib/js/joomla.javascript.js' type='text/javascript'></script>
	<script src='js/jquery.js' type='text/javascript'></script>	
	<script language='JavaScript' src='js/ajaxc2.js' type='text/javascript'></script>
	<script language='JavaScript' src='dialog/dialog.js' type='text/javascript'></script>
	<script language='JavaScript' src='js/global.js' type='text/javascript'></script>
	<script language='JavaScript' src='js/base.js' type='text/javascript'></script>
	<script language='JavaScript' src='js/encoder.js' type='text/javascript'></script>	
	<script language='JavaScript' src='lib/chatx/chatx.js' type='text/javascript'></script>
	<script src='js/daftarobj.js' type='text/javascript'></script>
	<script src='js/pageobj.js' type='text/javascript'></script>	
	<script src='js/pindahtangan.js' type='text/javascript'></script>
	<script src='js/pegawai.js' type='text/javascript'></script>
	<script src='js/ruang.js' type='text/javascript'></script>
	<script src='js/sensus.js' type='text/javascript'></script>
	

	</head>".
	//	"<script src='js/keranjang.js' type='text/javascript'></script>
	"				<script src='js/skpd.js' type='text/javascript'></script>
					<script src='js/usulanhapus.js' type='text/javascript'></script>
					<script src='js/penatausaha.js' type='text/javascript'></script>
					<script src='js/gantirugi.js' type='text/javascript'></script>
					
					
					<script>
						//var onscan = 0; 
						window.onload=function(){
							cek_notify('" . $_COOKIE['coID'] . "');
							//alert('tes');	
							
							/*				
							//seting keranjang
							Keranjang = new KeranjangObj({
								name : 'Keranjang',
								nmContainPilih 		: 'cbk',
								nmContainPilihAll 	: 'cbkall',
								nmContainBtnDaftar 	: 'cbkdaftar',
								jmlContainPilih 	: 5, //default
								imgsrc 		: 'images/administrator/images/downloads_f2.png',
								imgsrc_off 	: 'images/administrator/images/downloads.png',
								
							});*/
							Penatausaha.loading();
							//barcode.loading();
							
										
							
						}
					</script>".
		"<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
						<input type='hidden' id='Penghapusan_Baru' name='Penghapusan_Baru' value='$Penghapusan_Baru'>" . 
							$divBlock . "
						<table width='100%'><tr><td>" .
		        			"<div id = 'cntTitle' >".
								//$Penatausaha->genTitle($_GET['SPg'], $Penatausaha->genToolbarAtas()).
								//"daftar pilih".
							"</div>".
		       				$opt_wil .		        				
							$Opsi['TampilOpt'].
		        			"<div id=container style='position: relative; width: 100%;'>
							<div id='cover' style='z-index: 99; margin: auto; position:absolute;
								top:8;left:5;display: block; color:white;'>
								Loading...<img src='images/wait.gif'  style='height:16;position:relative;'>
							</div>
							<div id='$Penatausaha->elContentDaftar'>  ".
								"<table border='1' class='koptable' width='100%' >" .
		        					$Penatausaha->genHeader($GET['SPg']) .
		        					//$ListData .
		        				"</table>".
							"</div>
							" .
							"<div id='$Penatausaha->elContentHal'>						
								Loading...<img src='images/wait.gif' height='16' style='margin:4'>".
		        				"<input type='hidden' id='HalDefault' name='HalDefault' value='1'>".
							"</div>".
		        			//$toolbar_bawah .
		        		"</td></tr></table>" .
		        		$Main->Entry .
		        		$Entry_Script .
		        		$Hidden .
		        	"</form>
					$Info"; ;
			
	}
	
	case 'getDaftarOpsi':{
		$Opsi = $Penatausaha->getDaftarOpsi();
		$content =  $Opsi['TampilOpt'];
		$pageArr = array('cek'=>$cek, 'ErrMsg'=>$ErrMsg, 'content'=> $content, '');
		$page = json_encode($pageArr);	
		echo $page;
		break;
	}
	case 'list':{
		//sleep(4);
		$title =$Penatausaha->genTitle($_GET['SPg'], $Penatausaha->genToolbarAtas());
		$Opsi = $Penatausaha->getDaftarOpsi(); //$content='tes';
		
		$content = array(
			'title'=>$title,
			'list'=> //'list, kondisi='.$Opsi['Kondisi'].', order='.$Opsi['Order'].', limit='.$Opsi['Limit'].
			"<table border='1' class='koptable' width='100%' >" .
        		$Penatausaha->genHeader($_GET['SPg']) .
        		$Penatausaha->genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit']).
				//p_genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit']).
        	"</table>" );
		$cek .= $Opsi['cek'].' '.$Opsi['Kondisi'].' '.$Opsi['Order'] ;
		break;	
	} 		
	case 'sumhal': {		
		$Opsi = $Penatausaha->getDaftarOpsi();
		$content = $Penatausaha->genSumHal($SPg, $Opsi['Kondisi']);
		//$content = p_genSumHal($SPg, $Opsi['Kondisi']);
		$cek .= $Opsi['Kondisi'];
		break;
	}
	
	default:{ //kerangka hal		

		//get param ------------------------------------------------------------------------		
		$fmID = cekPOST("fmID", 0);
		$fmWIL = cekPOST("fmWIL");
		$fmTAHUNANGGARAN = cekPOST("fmTAHUNANGGARAN", $_POST['fmTahunPerolehan']);
		$Cari = cekPOST("Cari");
		$CariBarang = cekGET("CariBarang");
		$CariRekening = cekGET("CariRekening");
		$selStatusBrg = $_POST["selStatusBrg"];				
		$Penghapusan_Baru = cekPOST("Penghapusan_Baru", "1");
		$cidBI = cekPOST("cidBI");
		$Act = cekPOST("Act"); //echo"<br> Act=".$Act;
		$Baru = cekPOST("Baru", "");
		$Info = "";

		// tampil bidang ----------------------------------
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;		
		$fmSKPD = cekPOST('fmSKPD'); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
		$fmUNIT = cekPOST('fmUNIT'); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
		$fmSUBUNIT = cekPOST('fmSUBUNIT');
		$fmSEKSI = cekPOST('fmSEKSI');
		$skpdro = $_GET['skpdro'];
		if($skpdro==1){
			$c= $_REQUEST['c'];
			$d= $_REQUEST['d'];
			$e= $_REQUEST['e'];
			$e1= $_REQUEST['e1'];
			
			$filterSKPD =  
				//" $fmSKPD, $fmUNIT, $fmSUBUNIT".
				WilSKPD_ajx3('', '100%', 100, TRUE, $c, $d, $e) ;

		}else{
			setWilSKPD(); //echo "<br> fmSKPD ".$fmSKPD;	
			$filterSKPD =  WilSKPD1();
		}
		
		$barcode = $Main->BARCODE_ENABLE ?
			"<span style='color:red'>BARCODE</span><br>".
				"<input type='TEXT' value='' 
					id='barcode_input' name='barcode_input'
					style='font-size:24;width: 369px;' 
					size='28' maxlength='28'
					
				><span id='barcode_msg' name='barcode_msg' ></span> ":"";
					
		
		//if($tipebi=='pilih')	$barcode ='';	
		/*
		$fmSKPD_ = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$fmUNIT_ = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$fmSUBUNIT_ = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		*/
		
		
		$Main->ListData->OptWil =
        	"<!--wil skpd-->
			<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" .$filterSKPD . "
			</td>
			<td style='padding:0 8 0 0' >
				" . 
				//$Main->ListData->labelbarang .
				
				$barcode.
			"</td>
			</tr></table>";
		$opt_wil = $Main->ListData->OptWil;

		//********************************************************************************* {
		$Act2 = cekPOST('Act2', '');
		$ViewList = cekPOST('ViewList', 1);
		$ViewEntry = cekPOST('ViewEntry', 0);
		if ($Act == 'Hapus' || $Act == 'barcode') {	Penatausahaan_Proses();	}
		//if ($Act == 'Hapus' ) {	Penatausahaan_Proses();	}
		
		$Hidden = $Penatausaha->genHidden();
		$Entry_Script = $Penatausaha->genEntryScriptJS();
		$toolbar_bawah = $Penatausaha->genToolbarBawah();// $Main->ListData->ToolbarBawah;
		//********************************************************************************
		
		$Opsi = $Penatausaha->getDaftarOpsi();	//echo 'HalDefault='.$_POST['HalDefault'];	//echo '<br> time= '.(time()-$tim);
		
		
	//	switch($tipebi){
			/*case 'pilih':{
				//$Penatausaha->tampilCbxKeranjang = FALSE;
				$Main->Isi = "
					<script src='js/keranjang.js' type='text/javascript'></script>
					<script src='js/skpd.js' type='text/javascript'></script>
					<script src='js/usulanhapus.js' type='text/javascript'></script>
					<script src='js/penatausaha.js' type='text/javascript'></script>
					<script src='js/gantirugi.js' type='text/javascript'></script>
					
					
					<script>
						//var onscan = 0; 
						window.onload=function(){
							cek_notify('" . $_COOKIE['coID'] . "');
							//alert('tes');	
											
							//seting keranjang
							Keranjang = new KeranjangObj({
								name : 'Keranjang',
								nmContainPilih 		: 'cbk',
								nmContainPilihAll 	: 'cbkall',
								nmContainBtnDaftar 	: 'cbkdaftar',
								jmlContainPilih 	: 5, //default
								imgsrc 		: 'images/administrator/images/downloads_f2.png',
								imgsrc_off 	: 'images/administrator/images/downloads.png',
								
							});
							Penatausaha.loading();
							//barcode.loading();
							
										
							
						}
					</script>
					<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
						<input type='hidden' id='Penghapusan_Baru' name='Penghapusan_Baru' value='$Penghapusan_Baru'>" . 
							$divBlock . "
						<table width='100%'><tr><td>" .
		        			"<div id = 'cntTitle' >".
								//$Penatausaha->genTitle($_GET['SPg'], $Penatausaha->genToolbarAtas()).
								//"daftar pilih".
							"</div>".
		       				$opt_wil .		        				
							$Opsi['TampilOpt'].
		        			"<div id=container style='position: relative; width: 100%;'>
							<div id='cover' style='z-index: 99; margin: auto; position:absolute;
								top:8;left:5;display: block; color:white;'>
								Loading...<img src='images/wait.gif'  style='height:16;position:relative;'>
							</div>
							<div id='$Penatausaha->elContentDaftar'>  ".
								"<table border='1' class='koptable' width='100%' >" .
		        					$Penatausaha->genHeader($GET['SPg']) .
		        					//$ListData .
		        				"</table>".
							"</div>
							" .
							"<div id='$Penatausaha->elContentHal'>						
								Loading...<img src='images/wait.gif' height='16' style='margin:4'>".
		        				"<input type='hidden' id='HalDefault' name='HalDefault' value='1'>".
							"</div>".
		        			$toolbar_bawah .
		        		"</td></tr></table>" .
		        		$Main->Entry .
		        		$Entry_Script .
		        		$Hidden .
		        	"</form>
					$Info";
				break; 	
			}
			default:{
				*/
				if ($Main->BARCODE_ENABLE){
					$barcodeload="barcode.loading();";
					$barcodejs="<script src='js/barcode.js' type='text/javascript'></script>";
				}
			
				$Main->Isi = 
					//<script src='js/keranjang.js' type='text/javascript'></script>
					
					"<script src='js/penatausaha.js' type='text/javascript'></script>
					<script src='js/gantirugi.js' type='text/javascript'></script>
					$barcodejs
					
					<script>
						//var onscan = 0; 
						window.onload=function(){
							cek_notify('" . $_COOKIE['coID'] . "');
							//alert('tes');					
							/*
							Keranjang = new KeranjangObj({
								name : 'Keranjang',
								nmContainPilih 		: 'cbk',
								nmContainPilihAll 	: 'cbkall',
								nmContainBtnDaftar 	: 'cbkdaftar',
								jmlContainPilih 	: 5, //default
								imgsrc 		: 'images/administrator/images/downloads_f2.png',
								imgsrc_off 	: 'images/administrator/images/downloads.png',
								
								setLinkDaftar : function(){
									return \" href='?Pg=05&tipebi=pilih' target='_blank' \";
								}
								
							});*/
							Penatausaha.loading();
							$barcodeload
							
										
							
						}
					</script>
					<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
						<input type='hidden' id='Penghapusan_Baru' name='Penghapusan_Baru' value='$Penghapusan_Baru'>" . 
							$divBlock . "
						<table width='100%'><tr><td>" .
		        			"<div id = 'cntTitle' >".
								$Penatausaha->genTitle($_GET['SPg'], $Penatausaha->genToolbarAtas()).
							"</div>".
		       				$opt_wil .
		        			$stim .	
							$Opsi['TampilOpt'].
		        			"<div id=container style='position: relative; width: 100%;'>
							<div id='cover' style='z-index: 99; margin: auto; position:absolute;
								top:8;left:5;display: block; color:white;'>
								Loading...<img src='images/wait.gif'  style='height:16;position:relative;'>
							</div>
							<div id='$Penatausaha->elContentDaftar'>  ".
								"<table border='1' class='koptable' width='100%' >" .
		        					$Penatausaha->genHeader($GET['SPg']) .
		        					//$ListData .
		        				"</table>".
							"</div>
							" .
							"<div id='$Penatausaha->elContentHal'>						
								Loading...<img src='images/wait.gif' height='16' style='margin:4'>".
		        				"<input type='hidden' id='HalDefault' name='HalDefault' value='1'>".
							"</div>".
		        			$toolbar_bawah .
		        		"</td></tr></table>" .
		        		$Main->Entry .
		        		$Entry_Script .
		        		$Hidden .
		        	"</form>
					$Info";
				break;
			}
		}
		
		
			
		//break;
	//}//end default
//}//end case

//ajax -json -------------------------------
//if ($Trm->ErrMsg != ''){ $ErrMsg= $Trm->ErrMsg; }
//$cek = '';
if ($optstring !='' && !($idpilih =='cetak_hal' || $idpilih =='cetak_all' )){	
	$pageArr = array('idprs'=>$Opt->idprs,'idprs'=>$Opt->idprs, 'daftarProses'=>$Opt->daftarProses ,
				'cek'=>$cek, 
				 'ErrMsg'=>$ErrMsg, 'content'=> $content, '');
	$page = json_encode($pageArr);	
	echo $page;
}


?>