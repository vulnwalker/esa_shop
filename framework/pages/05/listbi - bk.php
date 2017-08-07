<?php
$cek = '';
$tim = time(); //$stim = time().' <br>';
//1.5detik
$SPg = isset($HTTP_GET_VARS["SPg"]) ? $HTTP_GET_VARS["SPg"] : "";
if ($SPg == '') {
	$SPg = '03';
}

//ajx - json ---------
$ErrMsg = '';
if ($_GET['idpilih']==''){
	$optstring = stripslashes($_GET['opt']);
	$Opt = json_decode($optstring); //$page = json_encode(",cek="+$Opt->idprs);
	$idpilih = $Opt->daftarProses[$Opt->idprs];		
}else{
	$idpilih = $_GET['idpilih'];
}

switch ($idpilih){
	case 'list':{
		//sleep(4);
		$title =$Penatausaha->genTitle($_GET['SPg'], $Penatausaha->genToolbarAtas());
		$Opsi = $Penatausaha->getDaftarOpsi(); //$content='tes';
		$daftar = $Penatausaha->genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit']);
		$content = array(
			'title'=>$title,
			'list'=>
			//'list, kondisi='.$Opsi['Kondisi'].', order='.$Opsi['Order'].', limit='.$Opsi['Limit'].
			"<table border='1' class='koptable' width='100%' >" .
        		$Penatausaha->genHeader($_GET['SPg']) .
        		//$Penatausaha->genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit']).
				$daftar['listdata'].
				//p_genList($Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit']).
        	"</table>" );
		$cek .= $Opsi['Kondisi'].' '.$Opsi['Order'].' '.$daftar['cek'] ;
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
		setWilSKPD(); //echo "<br> fmSKPD ".$fmSKPD;
		$barcode_input = $Main->BARCODE_ENABLE ?
			"<span style='color:red'>BARCODE</span>".
				"<input type='TEXT' value='' 
					id='barcode_input' name='barcode_input'
					style='font-size:24;width: 369px;' 
					size='28' maxlength='28'
				><span id='barcode_msg' name='barcode_msg' ></span> ":'';
			
		$Main->ListData->OptWil =
        	"<!--wil skpd-->
			<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD1() . "
			</td>
			<td >" . 
				
				
				$barcode_input. //onkeyup='inputBarcode(this)'
					
					//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>".
				
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

		
		$Opsi = $Penatausaha->getDaftarOpsi();
		
		$script_barcode = 
			//$Main->BARCODE_ENABLE?
			"<script src='js/barcode.js' type='text/javascript'></script>" ;
			//: '';
		$script_barcode_load =
			$Main->BARCODE_ENABLE?
			"barcode.loading();" : '';
		$Main->Isi = "
			<script src='js/penatausaha.js' type='text/javascript'></script>
			<script src='js/gantirugi.js' type='text/javascript'></script>
			$script_barcode
			<script>
				//var onscan = 0; 
				window.onload=function(){
					cek_notify('" . $_COOKIE['coID'] . "');
					//alert('tes');					
					Penatausaha.loading();
					$script_barcode_load
								
					
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
        			//$OptCari .//$OptFilter .
					$Opsi['TampilOpt'].
        			"<div id=container style='position: relative; width: 100%;'>
					<div id='cover' style='z-index: 99; margin: auto; position:absolute;
						top:8;left:5;display: block; color:white;'>
						Loading...<img src='images/wait.gif'  style='height:16;position:relative;'>
					</div>
					<div id='$Penatausaha->elContentDaftar'>  ".
						"<table border='1' class='koptable' width='100%' >" .
        					$Penatausaha->genHeader($GET['SPg']) .        					
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
	
		/*
		echo "<html>
				<head>
				</head>
				<body>
					$Main->Isi
				</body>
				</html>";
		*/
			
		break;
	}//end default
}//end case

//ajax -json -------------------------------
//if ($Trm->ErrMsg != ''){ $ErrMsg= $Trm->ErrMsg; }
if ($optstring !='' && !($idpilih =='cetak_hal' || $idpilih =='cetak_all' )){	
	$pageArr = array('idprs'=>$Opt->idprs,'idprs'=>$Opt->idprs, 'daftarProses'=>$Opt->daftarProses ,
				'cek'=>$cek, 
				 'ErrMsg'=>$ErrMsg, 'content'=> $content, '');
	$page = json_encode($pageArr);	
	echo $page;
}


?>