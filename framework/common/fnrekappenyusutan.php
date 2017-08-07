<?php

class RekapPenyusutanObj extends DaftarObj2{
	var $Prefix = 'RekapPenyusutan'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'ref_barang'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 5,5,5);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 18; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = FALSE;
	var $totalhalstr = '';
	var $totalAllStr = '';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='RekapNeraca.xls';
	var $Cetak_Judul = 'Rekap Neraca';
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead ='<style>
	.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}</style>';//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'Penatausahaan';
	var $PageIcon = 'images/penatausahaan_ico.gif';
	var $pagePerHal= '9999';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	
	var $jml_data=0;
	var $totBrgAset = 0;
	var $totHrgAset = 0;
	
	/*
	$TampilJmlTotSaldoAwSKPD=number_format($JmlSaldoAwSKPD, 2, ',', '.');
		$TampilJmlTotBelanjaSKPD=number_format($JmlBelanjaSKPD, 2, ',', '.');
		$TampilJmlTotAtribusiSKPD=number_format($JmlAtribusiSKPD, 2, ',', '.');
		$TampilJmlTotKapitalisasiDSKPD=number_format($JmlKapitalisasiDSKPD, 2, ',', '.');
		$TampilJmlTotKapitalisasiKSKPD=number_format($JmlKapitalisasiKSKPD, 2, ',', '.');
		$TampilJmlTotHibahDSKPD=number_format($JmlHibahDSKPD, 2, ',', '.');
		$TampilJmlTotHibahKSKPD=number_format($JmlHibahKSKPD, 2, ',', '.');
		$TampilJmlTotMutasiDSKPD=number_format($JmlMutasiDSKPD, 2, ',', '.');
		$TampilJmlTotMutasiKSKPD=number_format($JmlMutasiKSKPD, 2, ',', '.');
		$TampilJmlTotPenilaianDSKPD=number_format($JmlPenilaianDSKPD, 2, ',', '.');
		$TampilJmlTotPenghapusanKSKPD=number_format($JmlPenghapusanKSKPD, 2, ',', '.');
		$TampilJmlTotPembukuanDSKPD=number_format($JmlPembukuanDSKPD, 2, ',', '.');
		$TampilJmlTotPembukuanKSKPD=number_format($JmlPembukuanKSKPD, 2, ',', '.');
		$TampilJmlTotReklassDSKPD=number_format($JmlReklassDSKPD, 2, ',', '.');
		$TampilJmlTotReklassKSKPD=number_format($JmlReklassKSKPD, 2, ',', '.');
		$TampilJmlTotSaldoAkSKPD=number_format($JmlSaldoAkSKPD, 2, ',', '.');	
	*/
	
	var $JmlSaldoAwSKPD = 0;
	var $JmlBelanjaSKPD = 0;
	var $JmlAtribusiSKPD = 0;
	var $JmlKapitalisasiDSKPD = 0;
	var $JmlKapitalisasiKSKPD = 0;
	var $JmlHibahDSKPD = 0;
	var $JmlHibahKSKPD = 0;
	var $JmlMutasiDSKPD = 0;
	var $JmlMutasiKSKPD = 0;
	var $JmlPenilaianDSKPD = 0;
	var $JmlPenghapusanKSKPD = 0;
	var $JmlPembukuanDSKPD = 0;
	var $JmlPembukuanKSKPD = 0;
	var $JmlReklassDSKPD = 0;
	var $JmlReklassKSKPD = 0;
	var $JmlSaldoAkSKPD  = 0;
	
	
	//**
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'rekapNeraca':{
				$fm = $this->rekapNeraca();// $this->total();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				
				
				$content = $fm['content'];	
				
				//$content = 'tes';
				//echo 'tess';
				break;
			}			
			default:{
				$err = 'content tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	//**/
	
	
	function rekapNeraca(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//&c=00&d=00&e=00&e1=00&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=1
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$f = $_REQUEST['f'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h'];
		$i = $_REQUEST['i'];
		$idel= $_REQUEST['idel'];
		$bold = $_REQUEST['bold'];
		$tanpasusut = $_REQUEST['tanpasusut'];
		$isjmlbrg = $_REQUEST['isjmlbrg'];
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmSemester = $_REQUEST['fmSemester'];
				
		//$tgl1 = $_REQUEST['tgl1'];
		//$tgl2 = $_REQUEST['tgl2'];		
		//$jns_trans = $_REQUEST['jns_trans'];
				
		//kondisi
		$arrKondisi = array();	//$arrKondisi[] = " idawal = '740330' ";				
		
		if(!($c=='' || $c=='00') ) $arrKondisi[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisi[] = "d='$d'";
		if(!($e=='' || $e=='00') ) $arrKondisi[] = "e='$e'";
		if(!($e1=='' || $e1=='00') ) $arrKondisi[] = "e1='$e1'";
		
		$kondSKPD = join(' and ',$arrKondisi);
		
		//get kondisi f,g,h,i,j
		if(!($f == '' || $f=='00') ) $arrKondisi[] = "f=".$f."";
		if(!($g == '' || $g=='00') ) $arrKondisi[] = "g=".$g."";
		if(!($h == '' || $h=='00') ) $arrKondisi[] = "h=".$h."";
		if(!($i == '' || $i=='00') ) $arrKondisi[] = "i=".$i."";
				
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		
		//$kd_akun = $_REQUEST['kd_akun'];
		$kint = $_REQUEST['kint'];
		$ka = $_REQUEST['ka'];
		$kb = $_REQUEST['kb'];
		$debet = $_REQUEST['debet'];
		
		$cek .= "kint=$kint ka=$ka kb=$kb";
		$kdakun = $kint; //$cek .= " kdakun1=$kdakun ";
		$kdakun .= $ka!='' && $ka!='00' && $ka != NULL ?  '.'.$ka :''; //$cek .= " kdakun2=$kdakun ";
		$kdakun .= $kb!='' && $kb!='00' && $kb != NULL ?  '.'.$kb :''; //$cek .= " kdakun3=$kdakun ";	
		if($kdakun!='') {
			//
			
			//if($kint = '01' && $ka = '01') {
			//	$arrKondisi[] = " f='$ka' and g='$kb'";	
			//}else{
				$arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
			//}
		}
		$arrKondisi[] = " jns_trans = 10 ";
		
		
		/*		
		$tgl1 = $_REQUEST['tgl1'];
		if($tgl1!='') $arrKondisi[] = " tgl_buku >='$tgl1' ";
		$tgl2 = $_REQUEST['tgl2'];
		if($tgl2!='') $arrKondisi[] = " tgl_buku <='$tgl2' ";
		$jns_trans = $_REQUEST['jns_trans'];
		if($jns_trans!='') $arrKondisi[] = " jns_trans ='$jns_trans' ";
		$jns_trans2 = $_REQUEST['jns_trans2'];
		if($jns_trans2!='') $arrKondisi[] = " jns_trans2 ='$jns_trans2' ";
		$debet = $_REQUEST['debet'];
		*/
		
		//if($tanpasusut==1) $arrKondisi[] = " jns_trans <> 10 ";
		
		/*if($debet!='') {
			switch ($debet){
				case '1': $arrKondisi[] = " debet >0 "; break;
				case '2': $arrKondisi[] = " kredit >0 "; break;				
			}			
		}else{
			
		}*/
		
		
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//$jml = $debet ==2 ? "kredit" : "debet";
		
		
		
		if($isjmlbrg==1){
			if($jns_trans==10){
				$jml = " jml_barang_k-jml_barang_d "; //penyusutan
			}else{
				$jml = " jml_barang_d-jml_barang_k ";	
			}
			switch($debet){
				case '1' : $jml = "jml_barang_d"; break;
				case '2' : $jml = "jml_barang_k"; break;
				//else $jml = " debet-kredit "; break;
			}
		}else{
			if($jns_trans==10){
				$jml = " kredit-debet "; //penyusutan
			}else{
				$jml = " debet-kredit ";	
			}
			switch($debet){
				case '1' : $jml = "debet"; break;
				case '2' : $jml = "kredit"; break;
				//else $jml = " debet-kredit "; break;
			}
		}
			
		
		//if($Main->JURNAL_FISIK){
			$tbl = 't_jurnal_aset';//				
		//}else{
			//$tbl = 'v_jurnal_penyusutan';//				
		//}
		
		/*switch ($kint){
			case '01' : 
				switch ($ka){
					case '01': $tbl = ' t_jurnal_aset_tetap ';	break;
					case '02': $tbl = ' t_jurnal_aset_lainnya ';	break;
				}				
			break;
			case '02' :
				$tbl = ' t_jurnal_aset_ekstra ';
			break;			
		}
		*/
		
		switch($fmSemester){
			case 1 : $tgl1 = $fmFiltThnBuku.'-07-01'; $tgl2 = $fmFiltThnBuku.'-12-31'; break;
			case 2 : $tgl1 = $fmFiltThnBuku.'-01-01'; $tgl2 = $fmFiltThnBuku.'-12-31'; break; 
			default : $tgl1 = $fmFiltThnBuku.'-01-01'; $tgl2 = $fmFiltThnBuku.'-06-31'; break;
		}
		
		//$tgl1 = ($fmFiltThnBuku-1).'-01-01'; $tgl2 = ($fmFiltThnBuku-1).'-12-31'; 
		//$tgl1 = ($fmFiltThnBuku-1).'-01-01'; $tgl2 = ($fmFiltThnBuku-1).'-12-31'; 
		
		
		$aqry = " select  ".
			" sum(if(tgl_buku<'$tgl1',kredit-debet,0)) as total1, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,debet,0)) as total2, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,debet,0)) as total3, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=14,debet,0)) as total4, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,debet,0)) as total5, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,debet,0)) as total6, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,debet,0)) as total7, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=30,kredit,0)) as total8, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,kredit,0)) as total9, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,kredit,0)) as total10, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,kredit,0)) as total11, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,kredit,0)) as total12, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,kredit,0)) as total13, ".
			" sum(if(tgl_buku<='$tgl2' ,kredit-debet,0)) as total14, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',debet,0)) as total15, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',kredit,0)) as total16 ".
			" from $tbl ".
			" $Kondisi ; "; $cek.=$aqry;
		
		
		
		$hsl = mysql_fetch_array( mysql_query($aqry) );
		
		$des = 2;//$isjmlbrg ==1? 0: 2;
		$content->kol1 =  is_null($hsl['cnt1'])? 0 : $hsl['cnt1'] ; //jml awal
		$content->kol2 =  is_null($hsl['cnt2'])? 0 : $hsl['cnt2'] ; //ada pindah SKPD (berkurang)
		$content->kol3 =  is_null($hsl['cnt3'])? 0 : $hsl['cnt3'] ; //ada Reklas (berkurang)
		$content->kol4 =  is_null($hsl['cnt4'])? 0 : $hsl['cnt4'] ; //ada Penghapusan (berkurang)
		$content->kol5 =  is_null($hsl['cnt5'])? 0 : $hsl['cnt5'] ; //ada Kapitalitasi (berkurang)
		$content->kol6 =  is_null($hsl['cnt6'])? 0 : $hsl['cnt6'] ; //ada Aset Lain-lain (berkurang)
		$content->kol7 =  is_null($hsl['cnt7'])? 0 : $hsl['cnt7'] ; //ada koreksi penyusutan (berkurang)
		$content->kol8 =  is_null($hsl['cnt8'])? 0 : $hsl['cnt8'] ; //ada Penyusutan (bertambah)
		$content->kol9 =  is_null($hsl['cnt9'])? 0 : $hsl['cnt9'] ; //ada koreksi penyusutan (bertambah)
		$content->kol10 =  is_null($hsl['cnt10'])? 0 : $hsl['cnt10'] ; //ada pindah SKPD (bertambah)
		$content->kol11 =  is_null($hsl['cnt11'])? 0 : $hsl['cnt11'] ; //ada Reklas (bertambah)
		$content->kol12 =  is_null($hsl['cnt12'])? 0 : $hsl['cnt12'] ; //ada Kapitalitasi (bertambah)
		$content->kol13 =  is_null($hsl['cnt13'])? 0 : $hsl['cnt13'] ; //Aset Lain-lain  (bertambah)
		$content->kol14 =  is_null($hsl['cnt14'])? 0 : $hsl['cnt14'] ; //jml akhir		
				
		$content->idel = $idel;
		$content->total1 = is_null($hsl['total1'])? 0 : $hsl['total1'] ; //jml awal
		$content->total2 = is_null($hsl['total2'])? 0 : $hsl['total2']; //ada pindah SKPD (berkurang)
		$content->total3 = is_null($hsl['total3'])? 0 : $hsl['total3']; //ada Reklas (berkurang)
		$content->total4 = is_null($hsl['total4'])? 0 : $hsl['total4']; //ada Penghapusan (berkurang)
		$content->total5 = is_null($hsl['total5'])? 0 : $hsl['total5']; //ada Kapitalitasi (berkurang)
		$content->total6 = is_null($hsl['total6'])? 0 : $hsl['total6']; //ada Aset lain-lain (berkurang)
		$content->total7 = is_null($hsl['total7'])? 0 : $hsl['total7']; //ada Koreksi Penyusutan (berkurang)
		$content->total8 = is_null($hsl['total8'])? 0 : $hsl['total8']; //ada penyusutan (bertambah)
		$content->total9 = is_null($hsl['total9'])? 0 : $hsl['total9']; //ada koreksi penyusutan (bertambah)
		$content->total10 = is_null($hsl['total10'])? 0 :$hsl['total10']; //ada pindah SKPD (bertambah)
		$content->total11 = is_null($hsl['total11'])? 0 :$hsl['total11']; //ada Reklass (bertambah)
		$content->total12 = is_null($hsl['total12'])? 0 :$hsl['total12']; //ada kapitalisasi 
		$content->total13 = is_null($hsl['total13'])? 0 :$hsl['total13']; //Aset lain-lain
		$content->total14 = is_null($hsl['total14'])? 0 :$hsl['total14']; //jml Akhir
		$content->total15 = is_null($hsl['total15'])? 0 :$hsl['total15']; //jml debet
		$content->total16 = is_null($hsl['total16'])? 0 :$hsl['total16']; //jml kredit
						
		$content->vtotal1 =  $bold ? '<b>'. number_format($content->total1,$des,',' , '.' ) .'</b>':  number_format($content->total1,$des,',' , '.' );
		$content->vtotal2 =  $bold ? ''. number_format($content->total2,$des,',' , '.' ) .'</b>':  number_format($content->total2,$des,',' , '.' );
		$content->vtotal3 =  $bold ? '<b>'. number_format($content->total3,$des,',' , '.' ) .'</b>':  number_format($content->total3,$des,',' , '.' );
		$content->vtotal4 =  $bold ? '<b>'. number_format($content->total4,$des,',' , '.' ) .'</b>':  number_format($content->total4,$des,',' , '.' );
		$content->vtotal5 =  $bold ? '<b>'. number_format($content->total5,$des,',' , '.' ) .'</b>':  number_format($content->total5,$des,',' , '.' );
		$content->vtotal6 =  $bold ? '<b>'. number_format($content->total6,$des,',' , '.' ) .'</b>':  number_format($content->total6,$des,',' , '.' );
		$content->vtotal7 =  $bold ? '<b>'. number_format($content->total7,$des,',' , '.' ) .'</b>':  number_format($content->total7,$des,',' , '.' );
		$content->vtotal8 =  $bold ? '<b>'. number_format($content->total8,$des,',' , '.' ) .'</b>':  number_format($content->total8,$des,',' , '.' );
		$content->vtotal9 =  $bold ? '<b>'. number_format($content->total9,$des,',' , '.' ) .'</b>':  number_format($content->total9,$des,',' , '.' );
		$content->vtotal10 =  $bold ? '<b>'. number_format($content->total10,$des,',' , '.' ) .'</b>':  number_format($content->total10,$des,',' , '.' );
		$content->vtotal11 =  $bold ? '<b>'. number_format($content->total11,$des,',' , '.' ) .'</b>':  number_format($content->total11,$des,',' , '.' );
		$content->vtotal12 =  $bold ? '<b>'. number_format($content->total12,$des,',' , '.' ) .'</b>':  number_format($content->total11,$des,',' , '.' );
		$content->vtotal13 =  $bold ? '<b>'. number_format($content->total13,$des,',' , '.' ) .'</b>':  number_format($content->total13,$des,',' , '.' );
		$content->vtotal14 =  $bold ? '<b>'. number_format($content->total14,$des,',' , '.' ) .'</b>':  number_format($content->total14,$des,',' , '.' );
		$content->vtotal15 =  $bold ? '<b>'. number_format($content->total15,$des,',' , '.' ) .'</b>':  number_format($content->total15,$des,',' , '.' );
		$content->vtotal16 =  $bold ? '<b>'. number_format($content->total16,$des,',' , '.' ) .'</b>':  number_format($content->total16,$des,',' , '.' );		

		/*$arrTotN = array();
		$arrVTotN = array();
		for ($i=0;$i<10;$i++){
			for ($j=0;$j<4;$j++){
				$des= $j<2 ? 2 : 0;
				$arrTotN[$i][$j] = is_null($hsl['total'.($i+1).'_'.($j+1)])? 0 :$hsl['total'.($i+1).'_'.($j+1)];
				$arrVTotN[$i][$j] =  $bold ? '<b>'. number_format($arrTotN[$i][$j],$des,',' , '.' ) .'</b>':  number_format($arrTotN[$i][$j],$des,',' , '.' );
			}
		}
		$content->totalN = $arrTotN;
		$content->vtotalN = $arrVTotN;*/
		
		
		$content->saldoAk = is_null($hsl['total11'])? 0 : $hsl['total11'];
		$content->saldoAkBrg = is_null($hsl['total10'])? 0 : $hsl['total10'];
		$content->susutAk = is_null($hsl['total12'])? 0 :$hsl['total12'];
		$content->nilaibukuAk = $content->saldoAk  - $content->susutAk ;
		
		$content->vSaldoAk =$bold ? '<b>'. number_format($content->saldoAk ,2,',' , '.' ) .'</b>':  number_format($content->saldoAk ,2,',' , '.' );
		$content->vSaldoAkBrg =$bold ? '<b>'. number_format($content->saldoAkBrg ,0,',' , '.' ) .'</b>':  number_format($content->saldoAkBrg ,0,',' , '.' );
		$content->vSusutAk =$bold ? '<b>'. number_format($content->susutAk,2,',' , '.' ) .'</b>':  number_format($content->susutAk,2,',' , '.' );
		$content->vNilaibukuAk =$bold ? '<b>'. number_format($content->nilaibukuAk,2,',' , '.' ) .'</b>':  number_format($content->nilaibukuAk,2,',' , '.' );
		
		
		$kondSKPD = $kondSKPD != ''? ' and '.$kondSKPD: $kondSKPD; 	
/*		//sensus ----------------------------------------------------- 
		if($ka <> ''){
			$aqry = "select ".//cc.idbi, cc.ada 
				" sum(if(cc.ada=1 and cc.kondisi=1,1,0  )) as cnt1 , ".
				" sum(if(cc.ada=1 and cc.kondisi=2,1,0 )) as cnt2 , ".
				" sum(if(cc.ada=1 and cc.kondisi=3,1,0 )) as cnt3 , ".
				" sum(if(cc.ada=1,1,0 )) as cnt4 , ".
				" sum(if(cc.ada=2,1,0 )) as cnt5, ".
				" count(*) as cnt6  ".				
				"from sensus cc	join ".
				"(select idbi from ".
				"(select idbi, sum( jml_barang_d) as jml_barang_d, sum(jml_barang_k) as jml_barang_k ".
				"from $tbl ".
				$Kondisi. 
				" and tgl_buku <='$tgl2'  and jns_trans<>10 ".				
				"group by idbi ".
				") aa ".
				"where  aa.jml_barang_d - aa.jml_barang_k =1 ".
				")bb ".
				"on cc.idbi = bb.idbi ".
				"where cc.tahun_sensus=$fmFiltThnBuku and (cc.sesi='' or cc.sesi is null) and (cc.error ='' or cc.error is null) ; ";
			$cek .= $aqry ;	
			$hsl = mysql_fetch_array( mysql_query($aqry) );
		}
		else{
			//$kondSKPD = join(' and ',$arrKondisi[]);
			//aset tetap
			
			$aqry = "select ".//cc.idbi, cc.ada 
				" sum(if(cc.ada=1 and cc.kondisi=1,1,0  )) as cnt1 , ".
				" sum(if(cc.ada=1 and cc.kondisi=2,1,0 )) as cnt2 , ".
				" sum(if(cc.ada=1 and cc.kondisi=3,1,0 )) as cnt3 , ".
				" sum(if(cc.ada=1,1,0 )) as cnt4 , ".
				" sum(if(cc.ada=2,1,0 )) as cnt5, ".
				" count(*) as cnt6  ".				
				"from sensus cc	join ".
				"(select idbi from ".
				"(select idbi, sum( jml_barang_d) as jml_barang_d, sum(jml_barang_k) as jml_barang_k ".
				"from $tbl ".
				" where 1=1  ".
				$kondSKPD. 
				" and  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.01%' ".
				" and tgl_buku <='$tgl2'  and jns_trans<>10 ".				
				"group by idbi ".
				") aa ".
				"where  aa.jml_barang_d - aa.jml_barang_k =1 ".
				")bb ".
				"on cc.idbi = bb.idbi ".
				"where cc.tahun_sensus=$fmFiltThnBuku and (cc.sesi='' or cc.sesi is null) and (cc.error ='' or cc.error is null) ; ";
			$cek .= $aqry ;
			$hsl = mysql_fetch_array( mysql_query($aqry) );
			
			//aset lainnya
			$aqry = "select ".//cc.idbi, cc.ada 
				" sum(if(cc.ada=1 and cc.kondisi=1,1,0  )) as cnt1 , ".
				" sum(if(cc.ada=1 and cc.kondisi=2,1,0 )) as cnt2 , ".
				" sum(if(cc.ada=1 and cc.kondisi=3,1,0 )) as cnt3 , ".
				" sum(if(cc.ada=1,1,0 )) as cnt4 , ".
				" sum(if(cc.ada=2,1,0 )) as cnt5, ".
				" count(*) as cnt6  ".				
				"from sensus cc	join ".
				"(select idbi from ".
				"(select idbi,  sum( jml_barang_d) as jml_barang_d, sum(jml_barang_k) as jml_barang_k ".
				"from $tbl ".
				" where 1=1 ".
				$kondSKPD. 
				" and  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.02%' ".
				" and tgl_buku <='$tgl2'  and jns_trans<>10 ".				
				"group by idbi ".
				") aa ".
				"where  aa.jml_barang_d - aa.jml_barang_k =1 ".
				")bb ".
				"on cc.idbi = bb.idbi ".
				"where cc.tahun_sensus=$fmFiltThnBuku and (cc.sesi='' or cc.sesi is null) and (cc.error ='' or cc.error is null) ; ";
			$cek .= $aqry ;
			$hsl2 = mysql_fetch_array( mysql_query($aqry) );
			//aset tetap + lainnya
			$hsl['cnt1'] = $hsl['cnt1'] + $hsl2['cnt1'];
			$hsl['cnt2'] = $hsl['cnt2'] + $hsl2['cnt2'];
			$hsl['cnt3'] = $hsl['cnt3'] + $hsl2['cnt3'];
			$hsl['cnt4'] = $hsl['cnt4'] + $hsl2['cnt4'];
			$hsl['cnt5'] = $hsl['cnt5'] + $hsl2['cnt5'];
			$hsl['cnt6'] = $hsl['cnt6'] + $hsl2['cnt6'];
			$hsl['cnt7'] = $hsl['cnt7'] + $hsl2['cnt7'];
			$hsl['cnt8'] = $hsl['cnt8'] + $hsl2['cnt8'];
			$hsl['cnt9'] = $hsl['cnt9'] + $hsl2['cnt9'];
			
			if($kint == ''){ //ekstra
				$aqry = "select ".//cc.idbi, cc.ada 
					" sum(if(cc.ada=1 and cc.kondisi=1,1,0  )) as cnt1 , ".
					" sum(if(cc.ada=1 and cc.kondisi=2,1,0 )) as cnt2 , ".
					" sum(if(cc.ada=1 and cc.kondisi=3,1,0 )) as cnt3 , ".
					" sum(if(cc.ada=1,1,0 )) as cnt4 , ".
					" sum(if(cc.ada=2,1,0 )) as cnt5, ".
					" count(*) as cnt6  ".
					" ".
					"from sensus cc	join ".
					"(select idbi from ".
					"(select idbi,  sum( jml_barang_d) as jml_barang_d, sum(jml_barang_k) as jml_barang_k ".
					"from $tbl ".
					" where 1=1 ".
					$kondSKPD. 
					" and  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '02%' ".
					" and tgl_buku <='$tgl2'  and jns_trans<>10 ".				
					"group by idbi ".
					") aa ".
					"where  aa.jml_barang_d - aa.jml_barang_k =1 ".
					")bb ".
					"on cc.idbi = bb.idbi ".
					"where cc.tahun_sensus=$fmFiltThnBuku and (cc.sesi='' or cc.sesi is null) and (cc.error ='' or cc.error is null) ; ";
				$cek .= $aqry ;
				$hsl2 = mysql_fetch_array( mysql_query($aqry) );
				
				//aset tetap + lainnya + ekstra
				$hsl['cnt1'] = $hsl['cnt1'] + $hsl2['cnt1'];
				$hsl['cnt2'] = $hsl['cnt2'] + $hsl2['cnt2'];
				$hsl['cnt3'] = $hsl['cnt3'] + $hsl2['cnt3'];
				$hsl['cnt4'] = $hsl['cnt4'] + $hsl2['cnt4'];
				$hsl['cnt5'] = $hsl['cnt5'] + $hsl2['cnt5'];
				$hsl['cnt6'] = $hsl['cnt6'] + $hsl2['cnt6'];
				$hsl['cnt7'] = $hsl['cnt7'] + $hsl2['cnt7'];
				$hsl['cnt8'] = $hsl['cnt8'] + $hsl2['cnt8'];
				$hsl['cnt9'] = $hsl['cnt9'] + $hsl2['cnt9'];
			}
			
			
			
			
			
			
		}
		
				
		
		//**
		//persen telah cek (ada + tidak ada)
		$hsl['cnt7'] = $hsl['cnt6']/$content->saldoAkBrg * 100;
		//jml belum cek (bi - jml telah cek)
		$hsl['cnt8'] = $content->saldoAkBrg - $hsl['cnt6']; 
		$hsl['cnt9'] = $hsl['cnt8']/$content->saldoAkBrg *100;		
		//jml belum catat
		if ($ka =='01' || $kint =='' || $ka =='' ){
			if($kb!='' && $kb != '00') $kondf =  "and f='{$kb}'";
			if($g!='' && $g != '00') $kondg =  "and g='{$g}'";
			
			$aqry = "select count(*) as cnt from barang_tidak_tercatat where tahun_sensus='$fmFiltThnBuku' $kondSKPD $kondf $kondg ;"; $cek .= $aqry;
			$blmcatat = mysql_fetch_array(mysql_query($aqry	));
			$hsl['cnt10'] = $blmcatat['cnt'];	
		}
		
		//hasil sensus (jml ada + belum tercatat)
		$hsl['cnt11'] = $hsl['cnt4']+$hsl['cnt10'];
				
		$content->kol1 =  is_null($hsl['cnt1'])? 0 : $hsl['cnt1'] ; //jml awal
		$content->kol2 =  is_null($hsl['cnt2'])? 0 : $hsl['cnt2'] ; //ada pindah SKPD (berkurang)
		$content->kol3 =  is_null($hsl['cnt3'])? 0 : $hsl['cnt3'] ; //ada Reklas (berkurang)
		$content->kol4 =  is_null($hsl['cnt4'])? 0 : $hsl['cnt4'] ; //ada Penghapusan (berkurang)
		$content->kol5 =  is_null($hsl['cnt5'])? 0 : $hsl['cnt5'] ; //ada Kapitalitasi (berkurang)
		$content->kol6 =  is_null($hsl['cnt6'])? 0 : $hsl['cnt6'] ; //ada Aset Lain-lain (berkurang)
		$content->kol7 =  is_null($hsl['cnt7'])? 0 : $hsl['cnt7'] ; //ada koreksi penyusutan (berkurang)
		$content->kol8 =  is_null($hsl['cnt8'])? 0 : $hsl['cnt8'] ; //ada Penyusutan (bertambah)
		$content->kol9 =  is_null($hsl['cnt9'])? 0 : $hsl['cnt9'] ; //ada koreksi penyusutan (bertambah)
		$content->kol10 =  is_null($hsl['cnt10'])? 0 : $hsl['cnt10'] ; //ada pindah SKPD (bertambah)
		$content->kol11 =  is_null($hsl['cnt11'])? 0 : $hsl['cnt11'] ; //ada Reklas (bertambah)
		$content->kol12 =  is_null($hsl['cnt12'])? 0 : $hsl['cnt12'] ; //ada Kapitalitasi (bertambah)
		$content->kol13 =  is_null($hsl['cnt13'])? 0 : $hsl['cnt13'] ; //Aset Lain-lain  (bertambah)
		$content->kol14 =  is_null($hsl['cnt14'])? 0 : $hsl['cnt14'] ; //jml akhir				
*/
		
		//$content->vkol1 =$bold ? '<b>'. number_format($content->kol1 ,0,',' , '.' ) .'</b>':  number_format($content->kol1 ,0,',' , '.' );
		$content->vkol2 =$bold ? '<b>'. number_format($content->kol2 ,0,',' , '.' ) .'</b>':  number_format($content->kol2 ,0,',' , '.' );
		$content->vkol3 =$bold ? '<b>'. number_format($content->kol3 ,0,',' , '.' ) .'</b>':  number_format($content->kol3 ,0,',' , '.' );
		$content->vkol4 =$bold ? '<b>'. number_format($content->kol4 ,0,',' , '.' ) .'</b>':  number_format($content->kol4 ,0,',' , '.' );
		$content->vkol5 =$bold ? '<b>'. number_format($content->kol5 ,0,',' , '.' ) .'</b>':  number_format($content->kol5 ,0,',' , '.' );
		$content->vkol6 =$bold ? '<b>'. number_format($content->kol6 ,0,',' , '.' ) .'</b>':  number_format($content->kol6 ,0,',' , '.' );
		$content->vkol7 =$bold ? '<b>'. number_format($content->kol7 ,2,',' , '.' ) .' %</b>':  number_format($content->kol7 ,2,',' , '.' ).' %';
		$content->vkol8 =$bold ? '<b>'. number_format($content->kol8 ,0,',' , '.' ) .'</b>':  number_format($content->kol8 ,0,',' , '.' );
		$content->vkol9 =$bold ? '<b>'. number_format($content->kol9 ,2,',' , '.' ) .' %</b>':  number_format($content->kol9 ,2,',' , '.' ).' %';
		$content->vkol10 =$bold ? '<b>'. number_format($content->kol10 ,0,',' , '.' ) .'</b>':  number_format($content->kol10 ,0,',' , '.' );
		$content->vkol11 =$bold ? '<b>'. number_format($content->kol11 ,0,',' , '.' ) .'</b>':  number_format($content->kol11 ,0,',' , '.' );
		$content->vkol12 =$bold ? '<b>'. number_format($content->kol12 ,0,',' , '.' ) .'</b>':  number_format($content->kol12 ,0,',' , '.' );
		$content->vkol13 =$bold ? '<b>'. number_format($content->kol13 ,0,',' , '.' ) .'</b>':  number_format($content->kol13 ,0,',' , '.' );
		$content->vkol14 =$bold ? '<b>'. number_format($content->kol14 ,0,',' , '.' ) .'</b>':  number_format($content->kol14 ,0,',' , '.' );
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	

	function genRowSum(){
		//hal

		$ContentTotalHal=''; $ContentTotal='';
		return $ContentTotalHal.$ContentTotal;
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class='$TblStyle' border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		/*
		$qry = mysql_query($aqry);
		$numrows = mysql_num_rows($qry); $cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
					
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				
			
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
				
			
			
			//sum halaman
			for ($i=0; $i< sizeof($this->FieldSum) ; $i++) {
				$this->SumValue[$i] += $isi[$this->FieldSum[$i]];
			}
			
			//---------------------------
			$rowatr_ = $RowAtr." valign='top' id='$cb' value='".$isi['Id']."'";
			$bef= $this->setDaftar_before_getrow(
					$no,$isi,$Mode, $TampilCheckBox,  
					$rowatr_,
					$ColStyle
					);
			$ListData .= $bef['ListData'];
			$no = $bef['no'];
			//get row
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
			}
		}
		
		}
		*/
		$ListData .= $this->setDaftar_After($no, $ColStyle);
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		//$SumHal = $this->genSumHal($Kondisi);
		$ContentSum = $this->genRowSum($ColStyle,  $Mode, 
			$SumHal['sums']
		);
		/*$TampilTotalHalRp = number_format($TotalHalRp,2, ',', '.');		
		$TotalColSpan = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
		$ContentTotalHal =
			"<tr>
				<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>
				<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
				<td class='$ColStyle' colspan='4'></td>
			</tr>" ;
			
		$ContentTotal = 
				"<tr>
					<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total</td>
					<td class='$ColStyle' align='right'><b><div  id='cntDaftarTotal'>".$SumHal['sum']."</div></td>
					<td class='$ColStyle' colspan='4'></td>
				</tr>" ;
		
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			$ContentTotalHal='';			
		}
		$ContentSum=$ContentTotalHal.$ContentTotal;
		*/
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>"
			.$vKondisi_old
			;
		if ($Mode==3) {	//flush
			echo $ListData;	
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}

	function genSumHal(){

		return array('sum'=>0, 'hal'=>0, 'sums'=>0, 'jmldata'=>0, 'cek'=>'' );
	}

	function setTitle(){
		return 'Rekap Penyusutan' ;
		
	}
	function setCetakTitle(){
		//return	"<DIV ALIGN=CENTER>$this->Cetak_Judul Tahun ". getTahunSensus();
		$judul=" <DIV ALIGN=CENTER>Rekap Neraca";
		if ($this->cetak_xls==TRUE)
		{
			$judul="<table width='100%'><tr><td colspan=6>Rekap Neraca x</td></tr></table>";
		}
		return $judul;
	}
	
	function setMenuEdit(){		
		return '';
	}
	
	
	function setMenuView(){		
		return 			
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>".						
			"";
		
	}
	
	function setPage_HeaderOther(){
		global $Main;	
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];

		$menu = $_REQUEST['menu'];
		
		$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		$menu_penyusutan = $Main->PENYUSUTAN ? 
			" <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenu title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>": '';
		
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
			"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
		$menubar3 = 
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
				"<A href=\"index.php?Pg=05&jns=penyusutan\"  title='Intrakomptabel' >PENYUSUTAN</a> ".
				"| <A href=\"pages.php?Pg=RekapPenyusutan\"  title='Intrakomptabel' $styleMenu >REKAP PENYUSUTAN</a>   ".
				"&nbsp&nbsp&nbsp".
				"</td></tr>";
		
		if($Main->VERSI_NAME=='JABAR'){
			
			$menubar = 			//"<tr height='22' valign='top'><td >".
				"<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
				<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
				<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
				<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
				<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
				<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
				<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
							
				<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			
			$menubar.= " <A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' $styleMenu8>PETA</a> |";
			if($Main->MODUL_MUTASI) 
				$menubar=$menubar."
					<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
					<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
					
			//$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
			
			if($Main->MODUL_SENSUS) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >INVENTARISASI</a> |";
			
			if($Main->MODUL_PEMBUKUAN) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				
			$menubar .= "| <A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> ";
			
			
			$menu_pembukuan1 = ($Main->MODUL_AKUNTANSI )?
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Aset Tetap Tanah'>Tanah</a>  |  
				<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
				<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
				<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
				<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Aset Tetap Lainnya'>ATL</a>  |  
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
				$menu_kibg1
				<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |
				$menu_penyusutan  
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP ASET</a>
				<!--|<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI 2'  >REKAP BI</a>   -->
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI II</a>
				| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' >JURNAL</a>
				  &nbsp&nbsp&nbsp
				</td></tr>":'';	
				
			
			
			$menubar=
				"<!--menubar_page-->		
				<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
					$menubar.
				"&nbsp&nbsp&nbsp".
				"</td></tr>".
				$menu_pembukuan1.
				$menubar3.
				"</table>".
				"";
		}else{
		
			$menu_pembukuan1 = ($Main->MODUL_AKUNTANSI )?
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
				<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Aset Tetap Tanah'>Tanah</a>  |  
				<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
				<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
				<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
				<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Lainnya'>ATL</a>  |  
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
				<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
				$menu_kibg1
				<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |
				$menu_penyusutan  
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a>
				|<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI 2'  >REKAP BI 2</a>   
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
				| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' >JURNAL</a>
				  &nbsp&nbsp&nbsp
				</td></tr>":'';	
				
			$menubar = 			//"<tr height='22' valign='top'><td >".
				"<!--menubar_page-->		
				<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>			
				<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
				<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
				<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
				<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
				<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
				<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
				<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |
				<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
				
			if($Main->MODUL_MUTASI) 
				$menubar=$menubar."
					<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
					<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
					
			$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
			
			if($Main->MODUL_SENSUS) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
			
			if($Main->MODUL_PEMBUKUAN) 
				$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			
			$menubar=$menubar."&nbsp&nbsp&nbsp
				</td></tr>".
				$menu_pembukuan1.
				$menubar3.			
				"</table>".
				"";
		}
		return $menubar;
			
	}
	
	
	
	function setPage_HeaderOther_(){
	global $Main;	
		
		//style = terpilih
		$Pg= $_REQUEST['Pg'];

		//if($Pg == 'sensus'){
		//	$styleMenu = " style='color:blue;' ";	
		//}
		$menu = $_REQUEST['menu'];
		/*switch ($menu){
			case 'belumcek' : $styleMenu2_1 = " style='color:blue;' "; break;
			case 'diusulkan': $styleMenu2_3 = " style='color:blue;' "; break;
			case 'laporan' 	: $styleMenu2_4 = " style='color:blue;' "; break;
			case 'kertaskerja' 	: $styleMenu2_5 = " style='color:blue;' "; break;
			case 'ada' :$styleMenu2_2 = " style='color:blue;' "; break;	
			case 'tidakada' :$styleMenu2_5 = " style='color:blue;' "; break;	
			
			//default: $styleMenu2_2 = " style='color:blue;' "; break;	
		}*/
		//if($tipe='tipe')$styleMenu2_4 = " style='color:blue;' ";
		$styleMenu = " style='color:blue;' ";	
		$styleMenu2_4 = " style='color:blue;' ";
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu >REKAP NERACA</a>": '';
		
			$menu_pembukuan1 =
		($Main->MODUL_AKUNTANSI )?
		"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>



	<A href=\"index.php?Pg=05&SPg=03&jns=intra\"  title='Intrakomptabel'>INTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=03&jns=ekstra\"  title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
	<A href=\"index.php?Pg=05&SPg=04&jns=tetap\"  title='Tanah'>KIB A</a>  |  
	<A href=\"index.php?Pg=05&SPg=05&jns=tetap\"  title='Peralatan & Mesin'>KIB B</a>  |  
	<A href=\"index.php?Pg=05&SPg=06&jns=tetap\"  title='Gedung & Bangunan'>KIB C</a>  |  
	<A href=\"index.php?Pg=05&SPg=07&jns=tetap\"  title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
	<A href=\"index.php?Pg=05&SPg=08&jns=tetap\"  title='Aset Tetap Lainnya'>KIB E</a>  |  
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Konstruksi Dalam Pengerjaan'>KIB F</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Tuntutan Ganti Rugi'>TGR</a> |    
	<A href=\"index.php?Pg=05&SPg=09&jns=tetap\"  title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
	$menu_kibg1
	<A href=\"index.php?Pg=05&SPg=03&jns=lain\"  title='Aset Lain-lain'>ASET LAIN LAIN</a> |  
	$menu_penyusutan
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a> 
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
	  &nbsp&nbsp&nbsp
	</td></tr>":'';	
		
		
		$menubar = 			//"<tr height='22' valign='top'><td >".
			"<!--menubar_page-->
		
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
			<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
			<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
			<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
			<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
			<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
			<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
			<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
						
			<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			if($Main->MODUL_MUTASI) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
			<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> |";
		   $menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";

			if($Main->MODUL_SENSUS) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> |";
			if($Main->MODUL_PEMBUKUAN) $menubar=$menubar."
			<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
			$menubar=$menubar."&nbsp&nbsp&nbsp
			</td></tr>$menu_pembukuan1			
			</table>".
			
			
			""
			;
		
		return $menubar;
			
	}
	
	function genDaftarOpsi(){
		global $Main,$fmFiltThnBuku;
		
		
		$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		$fmFiltThnSensus = $_REQUEST['fmFiltThnSensus'];
		$fmFiltThnPerolehan = $_REQUEST['fmFiltThnPerolehan'];
		$fmKONDBRG = $_REQUEST['fmKONDBRG'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmSemester = $_REQUEST['fmSemester'];
		$fmTplKosong = cekPOST('fmTplKosong');
		$fmTplDetail = cekPOST('fmTplDetail');
		
		$Semester = "Semester ".cmb2D_v2('fmSemester',$fmSemester,$Main->ArSemester1,'','Semester I');
		
		
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">			
				" . WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td style='padding:6'>
			</td>
			</tr></table>".
			
			genFilterBar(
				array(	
					'Tampilkan : '.$Semester.
					' Tahun Buku '.
					"<input type='text' id='fmFiltThnBuku' name='fmFiltThnBuku' value='$fmFiltThnBuku' 
						size='4' maxlength='4' onkeypress='return isNumberKey(event)' >".
					"<input $fmTplKosong type='checkbox' id='fmTplKosong' name='fmTplKosong' value='checked'>Tampil Kosong &nbsp;&nbsp;&nbsp;".
					"<input $fmTplDetail type='checkbox' id='fmTplDetail' name='fmTplDetail' value='checked'>Detail".
					"<input type='hidden' id='daftarcetak' name='daftarcetak' value='' >"
					/*genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
						"select year(tgl_buku)as thnbuku from buku_induk group by thnbuku desc",
						'thnbuku', 'thnbuku','Tahun Buku')*/
				),$this->Prefix.".refreshList(true)",TRUE
			)
			;
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
		
		$daftarCetak=$_REQUEST['daftarcetak'];
		
		$this->cetak_xls = $xls;
		/*
		<style>
		.nfmt1 {mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt2 {mso-number-format:'0\.00_';}
		.nfmt3 {mso-number-format:'00000';}
		.nfmt4 {mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt5 {mso-number-format:'\@';} 
		table {mso-displayed-decimal-separator:'\.';
			mso-displayed-thousand-separator:'\,';}	
		br {mso-data-placement:same-cell;}	
		</style>*/ 	
		//if($this->cetak_xls){
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}						
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH'>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
			//echo 'vkondisi='.$$Opsi[vKondisi;
		/*if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}*/
		echo $daftarCetak;
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}



	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		
		
		//$fmFiltThnBuku = $_REQUEST['fmFiltThnBuku'];
		
		//Kondisi				
		$arrKondisi= array();
		$arrKondisi[] = " h='00'";		
		//$arrKondisi[] = " year(tgl_buku)<='$fmFiltThnBuku' ";
		
		
		$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$fmDESC2 = $_POST['fmDESC2'];
		$AscDsc2 = $fmDESC2 == 1? 'desc' : '';
		$fmORDER2 = $_POST['fmORDER2'];
		$fmDESC3 = $_POST['fmDESC3'];
		$AscDsc3 = $fmDESC3 == 1? 'desc' : '';
		$fmORDER3 = $_POST['fmORDER3'];
		
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc1 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc1 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc1 "; break;			
		}
		switch($fmORDER2){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc2 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc2 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc2 "; break;			
		}
		switch($fmORDER3){
			case '1': $OrderArr[] =  " thn_perolehan $AscDsc3 "; break;
			case '2': $OrderArr[] =  " kondisi $AscDsc3 "; break;
			case '3': $OrderArr[] =  " year(tgl_buku) $AscDsc3 "; break;			
		}
			
		
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal;
		$Limit = $Mode == 3 ? '': $Limit;
		$Limit = '';
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script src='js/ruang.js' type='text/javascript'></script>
				<script src='js/pegawai.js' type='text/javascript'></script>
				
				<script src='js/usulanhapus.js' type='text/javascript'></script>
				<script src='js/usulanhapusdetail.js' type='text/javascript'></script>
				<script src='js/penatausaha.js' type='text/javascript'></script>
				
				
				<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/penyusutan/JurnalPenyusutan.js' language='JavaScript' ></script>
				<!--<script type='text/javascript' src='js/unload.js' language='JavaScript' ></script>-->
						<!--<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>
						
						-->"
						.'<style>
						.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";
	}
						</style>'.
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		global $Ref;
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$fmTplDetail = $_POST['fmTplDetail'];
		$fmFiltThnBuku = $_POST['fmFiltThnBuku'];
		$smter= $_POST['fmSemester'];
		
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		
		$thnsbl =$fmFiltThnBuku -1;
		if ($smter=='1') 
		{
			$tglAwal="$fmFiltThnBuku-07-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
		} else if ($smter=='2') 
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-12-31";
		} else
		{
			$tglAwal="$fmFiltThnBuku-01-01";
			$tglAkhir="$fmFiltThnBuku-06-30";
		}
		
		if($fmTplDetail){
			$rows1 = "rowspan='4'";
			$rows2 = "rowspan='3'";
			$cols1 = "colspan='14'";
			$cols2 = "colspan='12'";
			$cols3 = "colspan='6'";
			$cols4 = "colspan='6'";
			$Detail = "<tr>
				<th class=\"th02\" >Pindah Antar SKPD</th>
				<th class=\"th02\" >Reklas</th>
				<th class=\"th02\" >Penghapusan</th>
				<th class=\"th02\" >Kapitalisasi</th>
				<th class=\"th02\" >Aset Lain-lain</th>
				<th class=\"th02\" >Koreksi Penyusutan</th>
				<th class=\"th02\" >Penyusutan</th>
				<th class=\"th02\" >Koreksi Penyusutan</th>
				<th class=\"th02\" >Pindah Antar SKPD</th>				
				<th class=\"th02\" >Reklas</th>				
				<th class=\"th02\" >Kapitalisasi</th>				
				<th class=\"th02\" >Aset Lain-lain</th>				
			</tr>";
		}else{
			$rows1 = "rowspan='3'";
			$rows2 = "rowspan='2'";
			$cols1 = "colspan='4'";
			$cols2 = "colspan='2'";
			$cols3 = "";
			$cols4 = "";
			$Detail = "";
		}
		

		$headerTglAwal = (substr( $tglAwal, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAwal,5,2)-1)]." ".substr($tglAwal, 0,4);  	
		$headerTglAkhir =  (substr( $tglAkhir, 8, 2 ))." ".$Ref->NamaBulan[(substr($tglAkhir,5,2)-1)]." ".substr($tglAkhir, 0,4);  

		$headerTable =
			"<tr>
				<th class=\"th02\" width='30' $rows1 >No. </th>
				<th class=\"th02\" colspan='4' >Kode<br>Barang</th>
				<th class=\"th02\" $rows1 >Nama Barang</th>
				<th class=\"th02\" $cols1 > Akumulasi Penyusutan </th>
			</tr>".
			"<tr>
				<th class=\"th02\" $rows2 >F</th>
				<th class=\"th02\" $rows2 >G</th>
				<th class=\"th02\" $rows2 >H</th>
				<th class=\"th02\" $rows2 >I</th>
				<th class=\"th02\" $rows2 >Keadaan per<br>$headerTglAwal</th>
				<th class=\"th02\" $cols2 >Mutasi Perubahan Selama<br>$headerTglAwal s/d $headerTglAkhir</th>
				<th class=\"th02\" $rows2 >Keadaan per<br>$headerTglAkhir</th>
			</tr>".
			"<tr>
				<th class=\"th02\" $cols3>Berkurang</th>
				<th class=\"th02\" $cols4>Bertambah</th>
			</tr>".
			$Detail.
			"<tr>
				<th class=\"th03\" >1</th>
				<th class=\"th03\" >2</th>
				<th class=\"th03\" >3</th>
				<th class=\"th03\" >4</th>
				<th class=\"th03\" >5</th>
				<th class=\"th03\" >6</th>
				<th class=\"th03\" >7</th>				
				<th class=\"th03\" $cols3>8</th>				
				<th class=\"th03\" $cols4>9</th>				
				<th class=\"th03\" >10</th>				
			</tr>";
		/*$headerTable =
			"<tr>
				<th class=\"th02\" width='30' rowspan='3' >No. </th>
				<th class=\"th02\" colspan='2' >Kode<br>Barang</th>
				<th class=\"th02\"  rowspan='3' >Nama Bidang Barang</th>
				<th class=\"th02\" colspan='2' >Keadaan per<br>$headerTglAwal</th>
				<th class=\"th02\" colspan='4' >Mutasi Perubahan Selama<br>$headerTglAwal s/d $headerTglAkhir</th>
				<th class=\"th02\" colspan='2' >Keadaan per<br>$headerTglAkhir</th>
			</tr>
			<tr>
				<th class=\"th02\" rowspan='2' >Gol.</th>
				<th class=\"th02\" rowspan='2' >Bid.</th>
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" colspan='2' >Berkurang</th>
				<th class=\"th02\" colspan='2' >Bertambah</th>
				<th class=\"th02\" rowspan='2' >Jumlah<br>Barang</th>
				<th class=\"th02\" rowspan='2' >Jumlah Harga<br>(Rp.)</th>

			</tr>
			<tr>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
				<th class=\"th02\" >Jumlah<br>Barang</th>
				<th class=\"th02\" >Jumlah Harga<br>(Rp.)</th>
			
			</tr>
			<tr>
				<th class=\"th03\" >1</th>
				<th class=\"th03\" >2</th>
				<th class=\"th03\" >3</th>
				<th class=\"th03\" >4</th>
				<th class=\"th03\" >5</th>
				<th class=\"th03\" >6</th>
				<th class=\"th03\" >7</th>
				<th class=\"th03\" >8</th>
				<th class=\"th03\" >9</th>
				<th class=\"th03\" >10</th>
				<th class=\"th03\" >11</th>
				<th class=\"th03\" >12</th>
				
			</tr>				
			$tambahgaris";*/
			$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	
	function setKolomHeader_($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$fmFiltThnBuku = $_POST['fmFiltThnBuku'];
		$fmSemester = $_POST['fmSemester'];
		$jnsrekap = $_REQUEST['jnsrekap'];
		$rp = $jnsrekap==1? '<br>(Rp)':'';
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
		if ($fmFiltThnBuku=='') $fmFiltThnBuku = date('Y');
		
		$thnsbl =$fmFiltThnBuku -1;
		
		//default semester 1
		$thnsbl = '1 Jan '.$fmFiltThnBuku;
		$thnakhir= '30 Jun '.$fmFiltThnBuku;
		switch($fmSemester){
			case '1' :  $thnsbl = '1 Jul '.$fmFiltThnBuku; $thnakhir= '31 Des '. $fmFiltThnBuku; break; //semester 2
			case '2' :  $thnsbl = '1 Jan '.$fmFiltThnBuku ; $thnakhir= '31 Des '.$fmFiltThnBuku; break; //tahun			
		}
			
		$headerTable =
			"<tr>
				<th class=\"th01\" width='30' rowspan='2' >No. </th>
				<th class=\"th01\" width='300'  rowspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SKPD&nbsp;/&nbsp;JENIS&nbsp;ASET&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th class=\"th02\" colspan=2 >SALDO AWAL $thnsbl</th>				
				<th class=\"th02\" >BELANJA MODAL</th>
				<th class=\"th02\" >ATRIBUSI</th>
				<th class=\"th02\" colspan=2 >KAPITALISASI</th>
				<th class=\"th02\" colspan=2 >HIBAH</th>
				<th class=\"th02\" colspan=2 >PINDAH ANTAR SKPD</th>
				<th class=\"th02\" >PENILAIAN</th>
				<th class=\"th02\" >PENGHAPUSAN</th>
				<th class=\"th02\" colspan=2 >KOREKSI PEMBUKUAN </th>
				<th class=\"th02\" colspan=2 >REKLASS </th>
				
				<th class=\"th02\" colspan=2 >AKUM. PENYUSUTAN </th>
				<th class=\"th02\" colspan='3' >SALDO AKHIR $thnakhir</th>
			</tr>
			<tr>
				<th class=\"th03\" width='100' >PEROLEHAN</th>
				<th class=\"th03\" width='100' >AKUM. PENYUSUTAN</th>
				<th class=\"th03\" >DEBET</th>				
				<th class=\"th03\" >DEBET</th>
				<th class=\"th03\" >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				
				<th class=\"th03\" width='100' >DEBET</th>
				<th class=\"th03\" width='100' >KREDIT</th>
				
				<th class=\"th03\" width='100' >PEROLEHAN</th>
				<th class=\"th03\" width='100' >AKUM. PENYUSUTAN</th>
				<th class=\"th03\" width='100' >NILAI BUKU</th>
			</tr>				
			$tambahgaris";
			$headerTable=$headerTable.$this->gen_table_data($Mode);
		return $headerTable;
	}
	
	function setDaftar_After($no=0, $ColStyle=''){
	
	/*	
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];			
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];			
		$jnsrekap = $_REQUEST['jnsrekap'];
		$des = $jnsrekap==1? 2:0;
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		
		
		
		$vtotjmlbarang 	= number_format($this->totBrgAset, $des,',','.');
		$vtotjmlharga 		= number_format($this->totHrgAset, $des,',','.');

				
		$ListData = 
			"<tr class='row1'>
			<td class='$ColStyle' colspan=4 align=center><b>TOTAL</td> 
			<td class='$ColStyle' align='right'><b>$vtotjmlbarang</td>
				
			<td class='$ColStyle' align='right'><b>$vtotjmlharga</td>
			";
		*/
		$ListData="";
		return $ListData;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
		$Koloms = array();


		
		
		
		

		return $Koloms;
	}
	
	function setKolomData_($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
	
		
		

		return $Koloms;
	}


	function gen_table_data($Mode=1){
		global $Main,$HTTP_COOKIE_VARS;

		
		$cek = '';
		$cetak = $Mode==2 || $Mode==3 ;
				
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$cbxTotal = 1;//$_REQUEST['cbxTotal'];
		
		$tblSusut = 't_jurnal_aset_rekap';//'t_jurnal_aset';
			
		$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga Perolehan (Ribuan)': 'Harga Perolehan';	
		$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
		$clGaris = $cetak? 'GarisCetak':'GarisDaftar';
				
		$c = $HTTP_COOKIE_VARS['cofmSKPD'];
		$d = $HTTP_COOKIE_VARS['cofmUNIT'];
		$e = $HTTP_COOKIE_VARS['cofmSUBUNIT'];	
		$e1 = $HTTP_COOKIE_VARS['cofmSEKSI'];
		
		$jnsrekap = $_REQUEST['jnsrekap'];
		$fmFiltThnBuku = empty($_REQUEST['fmFiltThnBuku'])? date('Y') : $_REQUEST['fmFiltThnBuku']; 
		$des = $jnsrekap==1? 2:0;
		$fmTplDetail = $_REQUEST['fmTplDetail'];
		$tampilKosong = $_REQUEST['fmTplKosong'] ==''? 0 : 1; //0/kalau checked = 1
		$smter=empty($_REQUEST['fmSemester'])? '' : $_REQUEST['fmSemester']; 
		
		
		if ($smter=='1'){ // semester 2
			$tgl1="$fmFiltThnBuku-07-01";
			$tgl2="$fmFiltThnBuku-12-31";
		} else if ($smter=='2'){ // pertahun
			$tgl1="$fmFiltThnBuku-01-01";
			$tgl2="$fmFiltThnBuku-12-31";
		} else { //semester 1
			$tgl1="$fmFiltThnBuku-01-01";
			$tgl2="$fmFiltThnBuku-06-30";
		}	

				
		$arrKond = array();
		if(!($c == '' || $c=='00') ) $arrKond[] = " c= '$c'";
		if(!($d == '' || $d=='00') ) $arrKond[] = " d= '$d'";
		if(!($e == '' || $e=='00') ) $arrKond[] = " e= '$e'";
		if(!($e1 == '' || $e1=='00' || $e1=='000') ) $arrKond[] = " e1= '$e1'";
		$KondisiSKPD = join(' and ', $arrKond);
		$KondisiSKPD = $KondisiSKPD==''? '' : ' and '.$KondisiSKPD;

		$Kondisi_ATetap=" and staset<=3 ";
		$KondisiAsal = join(' and ', $arrKond);
		if ($KondisiAsal==''){
			$KondisiAsal=" c <> '' ";
		}			

		$Kondisi = $KondisiAsal." and staset<=4 and f<='06' ";
		
		if ( $cbxTotal ){
			$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,
				'T O T A L' as nm_barcode  ";
		}else{
			if ($e1=='00' || $e1=='000' ){
				$sqry=" select * from v_opd  where c<>'' $KondisiSKPD ";
			} else {
				$sqry=" select *,nm_skpd as nmopd from ref_skpd  where c<>'' $KondisiSKPD ";
			}	
			$sqry2= $sqry;
			
			//if($c<> '00' ) 
		}
		//$sqry=" select '00' as c, '00' as d, '00' as e, '00' as e1, 'T O T A L' as nmopd,				'T O T A L' as nm_barcode  ";
		//$sqry=" select *,nm_skpd as nmopd from ref_skpd where  c='04' and d<>'00' and e='00' ";
		$cek .=$sqry;
		$cskpd=0;
		$Qry = mysql_query($sqry);
		while($isi=mysql_fetch_array($Qry)){
			$cskpd++;
			if ( $cbxTotal ==TRUE){
				$KondisiSKPDx = "1=1";
				if($c!='00' && $c !='') $KondisiSKPDx .= " and c= '$c' ";
				if($d!='00' && $d !='') $KondisiSKPDx .=  $KondisiSKPDx ==''? " d= '$d' " : " and d= '$d' ";
				$paramSKPD = "&c=$c&d=$d&e=$e&e1=$e1";		$cek.= ' no=1 ';		
			}else{
				if ($e1=='00' || $e1=='000' ){
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' ";							
				} else {
					$KondisiSKPDx=" c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."' ";					
				}
				$paramSKPD = "&c=".$isi['c']."&d=".$isi['d']."&e=".$isi['e']."&e1=".$isi['e1'];
				$cek.= ' no=2 ';
			}

		if($fmTplDetail){
			$saldo_awal="bb.total1 AS awal ";
			$saldo_debet="(bb.total2+bb.total3+bb.total4+bb.total5+bb.total6+bb.total7) AS debet ";
			$saldo_kredit="(bb.total8+bb.total9+bb.total10+bb.total11+bb.total12+bb.total3) AS kredit ";
			$saldo_akhir="bb.total14 AS akhir ";
		}else{
			$saldo_awal="bb.total1 AS awal ";
			$saldo_debet="bb.total5 AS debet ";
			$saldo_kredit="bb.total6 AS kredit ";
			$saldo_akhir="bb.total14 AS akhir ";
		}
			
			//tampil intrakomptabel	 -------------------------------------------------------------------
			$rowno ++;
			//$bold = $isix['g']=='00' ? 1 : 0;
			$paramKdAkun = "&kint=01&ka=00&kb=00&bold=1";			
			$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
			$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
			$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";			
			if($fmTplDetail){
				$TampiljmlIntrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
				</tr>"	;
			}else{
				$TampiljmlIntrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
				<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
				<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
				</tr>"	;				
			}				

			$vnmbarang = $isix['g']=='00' ?  "<b>&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$isix['nm_barang']}";
			$ListData .="<tr class='row1'>
			<td class='GarisDaftar' align=center>$rowno.</td>
			<td class='GarisDaftar' align=left colspan=5 ><b>I. &nbsp;&nbsp; Intrakomptabel</td>".
			$TampiljmlIntrakomptabel;		

							
			//qry aset tetap --------------------------------------------------------------------------------------	
			$kondisi = " && jns_trans=10  ";
			/**$bqry =
				"select aa.kint,aa.ka,aa.kb,aa.kb,aa.f,aa.g, aa.h, aa.i, aa.nm_barang, $saldo_awal, $saldo_debet, $saldo_kredit, $saldo_akhir ".
				"from v_ref_kib_keu_penyusutan aa ". 
				"left join ".
				"(select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ". 
				" sum(if(tgl_buku<'$tgl1',kredit-debet,0)) as total1, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,debet,0)) as total2, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,debet,0)) as total3, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=14,debet,0)) as total4, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,debet,0)) as total5, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,debet,0)) as total6, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,debet,0)) as total7, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=30,kredit,0)) as total8, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,kredit,0)) as total9, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,kredit,0)) as total10, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,kredit,0)) as total11, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,kredit,0)) as total12, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,kredit,0)) as total13, ".
				" sum(if(tgl_buku<='$tgl2' ,kredit-debet,0)) as total14, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',debet,0)) as total15, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',kredit,0)) as total16 ".
				"from $tblSusut where c<>'' ".$KondisiSKPD." and kint='01' and ka='01' and jns_trans = 10 ".
				"group by f,g,h,i with rollup) bb ".
				"on aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i where  aa.ka='01'";
			**/
			$bqry =
				"select aa.kint,aa.ka,aa.kb,aa.kb,aa.f,aa.g, aa.h, aa.i, aa.nm_barang, bb.cnt ".//$saldo_awal, $saldo_debet, $saldo_kredit, $saldo_akhir ".
				"from v_ref_kib_keu_penyusutan aa ". 
				"left join ".
				"(select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ". 
				" sum(if(tgl_buku<='$tgl2',1,0 )) as cnt ".
				//" count(*) as cnt ".
				" from $tblSusut where c<>'' ".$KondisiSKPD." and kint='01' and ka='01' and jns_trans = 10  ".
				"group by f,g,h,i with rollup) bb ".
				"on aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i where  aa.ka='01'";
			$aQry = mysql_query($bqry);		
			while($isix=mysql_fetch_array($aQry)){
				$idbi = $isix['idbi'];
				$idawal = $isix['idawal'];
		  			
				//tampil aset tetap -------------------------------------------------------------------
				if ( $isix['f']=='00' ){//total aset tetap
					$rowno++;
					$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=1";
					$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
					$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
					$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";		
					
					if($fmTplDetail){
						$TampiljmlAsetTetap="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
						</tr>"	;
					}else{
						$TampiljmlAsetTetap="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
						<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
						<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
						</tr>"	;				
					}	
					//$bold = $isix['g']=='00' ? 1 : 0;
					$cek = $cetak ? '' :"<div id='cek' style='display:none'>$bqry</div>";
					$cek = '';
					$ListData .="<tr class='row0'>
					<td class='GarisDaftar' align=center>$rowno.</td>
					<td class='GarisDaftar' align=right></td>
					<td class='GarisDaftar' align=left colspan=4 ><b>A. &nbsp;&nbsp; Aset Tetap $cek</td>".
					$TampiljmlAsetTetap;
					
				}
				else{ //rincian aset tetap
				  	//if($isix['awal'] + $isix['debet'] +  $isix['kredit'] + $isix['akhir'] <>0 || $tampilKosong==1){
				  	if($isix['cnt'] <>0 || $tampilKosong==1){
						
						$rowno++;

						//get kondisi f,g,h,i,j
						$arrParamBarang = array();
						if(($isix['f'] != '' || $isix['f']!='00') ) $arrParamBarang[] = "f=".$isix['f']."";
						if(($isix['g'] != '' || $isix['g']!='00') ) $arrParamBarang[] = "g=".$isix['g']."";
						if(($isix['h'] != '' || $isix['h']!='00') ) $arrParamBarang[] = "h=".$isix['h']."";
						if(($isix['i'] != '' || $isix['i']!='00') ) $arrParamBarang[] = "i=".$isix['i']."";
						$ParamBarang = join('&', $arrParamBarang);
						$ParamBarang = $ParamBarang==''? '' : '&'.$ParamBarang;
						
						$bold = $isix['i']=='00' ? 1 : 0;
						$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=$bold";	
						$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl2=$tgl1";
						$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl2=$tgl2";
						$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl1=$tgl1&tgl2=$tgl2";							
						if($fmTplDetail){
							$TampiljmlRincianAsetTetap="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
							</tr>"	;
						}else{
							$TampiljmlRincianAsetTetap="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
							<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
							<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
							</tr>"	;				
						}	
						$space = '';
						$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
						$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
						$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
						
							

						$ListData .="<tr class='row0'>
						<td align=center class='$clGaris'>$rowno.</td>
						<td align=center class='$clGaris'>".		( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}".
						"</td>
						<td align=center class='$clGaris'>".
							( $isix['i'] != '00' ? '':'<b>').	( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). 
						"</td>
						<td align=center class='$clGaris'>".
							( $isix['i'] != '00' ? '':'<b>').	( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). 
						"</td>
						<td align=center class='$clGaris'>".
							( $isix['i'] != '00' ? '':'<b>').	( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). 
						"</td>
						<td class='$clGaris' >".
						( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang'].
						"</td>".
						$TampiljmlRincianAsetTetap;
						}
					}
 
		  	}																																																					
			
			//query aset lainnya ----------------------------------------------------------------------------------------------- 
			/*$bqry =
				"select aa.kint,aa.ka,aa.kb,aa.kb,aa.f,aa.g, aa.h, aa.i, aa.nm_barang, $saldo_awal, $saldo_debet, $saldo_kredit, $saldo_akhir ".
				"from v_ref_kib_keu_penyusutan aa ". 
				"left join ".
				"(select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ". 
				" sum(if(tgl_buku<'$tgl1',kredit-debet,0)) as total1, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,debet,0)) as total2, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,debet,0)) as total3, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=14,debet,0)) as total4, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,debet,0)) as total5, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,debet,0)) as total6, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,debet,0)) as total7, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=30,kredit,0)) as total8, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,kredit,0)) as total9, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,kredit,0)) as total10, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,kredit,0)) as total11, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,kredit,0)) as total12, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,kredit,0)) as total13, ".
				" sum(if(tgl_buku<='$tgl2' ,kredit-debet,0)) as total14, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',debet,0)) as total15, ".
				" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',kredit,0)) as total16 ".
				"from $tblSusut where c<>'' ".$KondisiSKPD." and kint='01' and ka='02' and jns_trans = 10 ".
				"group by f,g,h,i with rollup) bb ".
				"on aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i where kint='01' and ka='02' and (kb='04' or kb='05' or kb='00')";*/
			$bqry =
				"select kint,ka,kb,kb,f,g, h, i, nm_barang ".
				"from v_ref_kib_keu_penyusutan ". 
				"where kint='01' and ka='02' and h='00'";				
			$aQry = mysql_query($bqry);
			while($isix=mysql_fetch_array($aQry)){
			
			//get kondisi f,g,h,i,j
			$arrParamBarang = array();
			if(($isix['f'] != '' || $isix['f']!='00') ) $arrParamBarang[] = "f=".$isix['f']."";
			if(($isix['g'] != '' || $isix['g']!='00') ) $arrParamBarang[] = "g=".$isix['g']."";
			if(($isix['h'] != '' || $isix['h']!='00') ) $arrParamBarang[] = "h=".$isix['h']."";
			if(($isix['i'] != '' || $isix['i']!='00') ) $arrParamBarang[] = "i=".$isix['i']."";
			$ParamBarang = join('&', $arrParamBarang);
			$ParamBarang = $ParamBarang==''? '' : '&'.$ParamBarang;
			
		if ($isix['g']=='00'){//total aset lainnya
			$rowno++;
			$bold = $isix['g']=='00' ? 1 : 0;
			$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=1";
			$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
			$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
			$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";	
				if($fmTplDetail){
					$TampiljmlAsetLainnya="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>			
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;
				}else{
					$TampiljmlAsetLainnya="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;				
				}				

			$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$rowno.</td>
			<td align=center class='$clGaris'>&nbsp;</td>".
			"<td align=left class='$clGaris' colspan=4 ><b>B. &nbsp;&nbsp;   Aset Lainnya</td>".
			$TampiljmlAsetLainnya;
			
		}
		else{ //rincian aset Lainnya
			$QryAL="select bb.cnt ".//$saldo_awal, $saldo_debet, $saldo_kredit, $saldo_akhir  ".
				" from (select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ". 
					/*" sum(if(tgl_buku<'$tgl1',kredit-debet,0)) as total1, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,debet,0)) as total2, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,debet,0)) as total3, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=14,debet,0)) as total4, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,debet,0)) as total5, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,debet,0)) as total6, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,debet,0)) as total7, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=30,kredit,0)) as total8, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,kredit,0)) as total9, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,kredit,0)) as total10, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,kredit,0)) as total11, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,kredit,0)) as total12, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,kredit,0)) as total13, ".
					" sum(if(tgl_buku<='$tgl2' ,kredit-debet,0)) as total14, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',debet,0)) as total15, ".
					" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',kredit-debet,0)) as total16 ".*/
					" sum(if(tgl_buku<='$tgl2',1,0)) as cnt  ".
					//" count(*) as cnt ".
					" from $tblSusut where c<>'' ".$KondisiSKPD." and kint='01' and ka='02' and kb='".$isix['kb'].
					"' and jns_trans = 10 group by kint,ka) as bb";
			$isix2=mysql_fetch_array(mysql_query($QryAL));
			//if($isix2['awal'] + $isix2['debet'] +  $isix2['kredit'] + $isix2['akhir'] <>0 || $tampilKosong==1){
			if($isix2['cnt'] <>0 || $tampilKosong==1){
			$rowno++;
			$bold = $isix['i']=='00' ? 1 : 0;
			$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=$bold";
			$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
			$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
			$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";	
				if($fmTplDetail){
					$TampiljmlRincianAsetLainnya="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;
				}else{
					$TampiljmlRincianAsetLainnya="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;				
				}
			$space = '';
			$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			
			$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$rowno.</td>
			<td align=center class='$clGaris'>".		( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}".
			"</td>
			<td align=center class='$clGaris'>".
				( $isix['i'] != '00' ? '':'<b>').	( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). 
			"</td>
			<td align=center class='$clGaris'>".
				( $isix['i'] != '00' ? '':'<b>').	( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). 
			"</td>
			<td align=center class='$clGaris'>".
				( $isix['i'] != '00' ? '':'<b>').	( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). 
			"</td>
			<td class='$clGaris' >".
			( $isix['i'] != '00' ? '':'<b>').$space.$isix['nm_barang'].
			"</td>".
			$TampiljmlRincianAsetLainnya;			
				}
			}	
		}						
		
			
	//ekstra -----------------------------------------------------------------------
	$bqry3=	"select aa.kint,aa.ka,aa.kb,aa.kb,aa.f,aa.g, aa.h, aa.i, aa.nm_barang, bb.cnt ".//$saldo_awal, $saldo_debet, $saldo_kredit, $saldo_akhir ".
			"from v_ref_kib_keu_penyusutan aa ". 
			"left join ".
			"(select IFNULL(f,'00') as f,IFNULL(g,'00') as g, IFNULL(h,'00') as h, IFNULL(i,'00') as i, ". 
			" sum(if(tgl_buku<='$tgl2',1,0)) as cnt ".
			//" count(*) as cnt ".
			/**" sum(if(tgl_buku<'$tgl1',kredit-debet,0)) as total1, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,debet,0)) as total2, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,debet,0)) as total3, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=14,debet,0)) as total4, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,debet,0)) as total5, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,debet,0)) as total6, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,debet,0)) as total7, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=30,kredit,0)) as total8, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=31,kredit,0)) as total9, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=15,kredit,0)) as total10, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=16,kredit,0)) as total11, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=22,kredit,0)) as total12, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans2=21,kredit,0)) as total13, ".
			" sum(if(tgl_buku<='$tgl2' ,kredit-debet,0)) as total14, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',debet,0)) as total15, ".
			" sum(if(tgl_buku>='$tgl1' and tgl_buku<='$tgl2',kredit,0)) as total16 ".**/
			"from $tblSusut where c<>'' ".$KondisiSKPD." and kint='02' and jns_trans = 10  ".
			"group by f,g,h,i with rollup) bb ".
			"on aa.f=bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i where kint='02'";
		
	  $aQry = mysql_query($bqry3);
	  while($isix=mysql_fetch_array($aQry)){

  			//get kondisi f,g,h,i,j
			$arrParamBarang = array();
			if(($isix['f'] != '' || $isix['f']!='00') ) $arrParamBarang[] = "f=".$isix['f']."";
			if(($isix['g'] != '' || $isix['g']!='00') ) $arrParamBarang[] = "g=".$isix['g']."";
			if(($isix['h'] != '' || $isix['h']!='00') ) $arrParamBarang[] = "h=".$isix['h']."";
			if(($isix['i'] != '' || $isix['i']!='00') ) $arrParamBarang[] = "i=".$isix['i']."";
			$ParamBarang = join('&', $arrParamBarang);
			$ParamBarang = $ParamBarang==''? '' : '&'.$ParamBarang;

		if ($isix['g']=='00'){//total ekstra
			$rowno++;
			$bold = $isix['i']=='00' ? 1 : 0;
			$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=1";
			$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
			$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
			$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";	
				if($fmTplDetail){
					$TampiljmlEkstrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;
				}else{
					$TampiljmlEkstrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;				
				}

			$ListData .="<tr class='row0'>
			<td align=center class='$clGaris'>$rowno.</td>
			<td align=left class='$clGaris' colspan=5 ><b>II. &nbsp;&nbsp;   Ekstrakomptabel</td>".
			$TampiljmlEkstrakomptabel;			
			//}
			
		}
		else{ //rincian ekstra
			$rowno++;
			$paramKdAkun = "&kint={$isix['kint']}&ka={$isix['ka']}&kb={$isix['kb']}&bold=1";
			$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl2=$tgl1";
			$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl2=$tgl2";
			$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun$ParamBarang&tgl1=$tgl1&tgl2=$tgl2";	
				if($fmTplDetail){
					$TampiljmlRincianEkstrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$hrefAk&jns_trans2=30' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;
				}else{
					$TampiljmlRincianEkstrakomptabel="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
					<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
					<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
					</tr>"	;				
				}

			$space = '';
			$space = $isix['g'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['h'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			$space = $isix['i'] != '00'? '&nbsp;&nbsp;&nbsp;&nbsp;'.$space: $space;
			
			$ListData .=
			"<tr class='row0'>
			<td align=center class='$clGaris'>$rowno.</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00'? '': '<b>').	"<div class='nfmt5'>{$isix['f']}". "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['g'] != '00'? "<div class='nfmt5'>{$isix['g']}</div>" : '&nbsp;'). "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['h'] != '00'? "<div class='nfmt5'>{$isix['h']}</div>" : '&nbsp;'). "</td>
			<td align=center class='$clGaris'>". ( $isix['i'] != '00' ? '':'<b>').	( $isix['i'] != '00'? "<div class='nfmt5'>{$isix['i']}</div>" : '&nbsp;'). "</td>".
			$TampiljmlRincianEkstrakomptabel;
		}


 	}
	
	//Total
	$rowno++;
	$paramKdAkun = "&bold=1";
	$hrefAw = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl1";
	$hrefAk = "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl2=$tgl2";
	$href 	= "pages.php?Pg=JurnalPenyusutan$paramSKPD$paramKdAkun&tgl1=$tgl1&tgl2=$tgl2";	
	if($fmTplDetail){
		$TampiljmlTotal="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_2' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_3' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=14&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_4' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_5' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_6' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_7' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=30&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_8' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=31&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_9' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>			
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=15&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_10' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=16&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_11' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=22&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_12' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&jns_trans2=21&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_13' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
		</tr>"	;
	}else{
		$TampiljmlTotal="<td class='GarisDaftar' align=right><a href='$hrefAw' target='blank_'  style='color:black;' id='vrekap_$rowno"."_1' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&debet=1' target='blank_'  style='color:black;' id='vrekap_$rowno"."_15' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>				
		<td class='GarisDaftar' align=right><a href='$href&debet=2' target='blank_'  style='color:black;' id='vrekap_$rowno"."_16' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>						
		<td class='GarisDaftar' align=right><a href='$hrefAk' target='blank_'  style='color:black;' id='vrekap_$rowno"."_14' name='vrekap'>$TampilJmlSaldoAkSKPD</a></td>	
		</tr>"	;				
	}	
	//total ekstra + intra -----------------------------------------------------------
	$ListData .="<tr class='row0'>
				<td class='$clGaris' align=center colspan=6 ><b>Total</b></td>".
				$TampiljmlTotal;							

			$this->JmlSaldoAwTot += $JmlSaldoAwTot;	
			$this->JmlBelanjaTot += $JmlBelanjaTot;
			$this->JmlAtribusiTot += $JmlAtribusiTot ;
			$this->JmlKapitalisasiDTot += $JmlKapitalisasiDTot;	
			$this->JmlKapitalisasiKTot += $JmlKapitalisasiKTot;
			$this->JmlHibahDTot += $JmlHibahDTot;
			$this->JmlHibahKTot += $JmlHibahKTot;
			$this->JmlMutasiDTot += $JmlMutasiDTot;
			$this->JmlMutasiKTot += $JmlMutasiKTot;
			$this->JmlPenilaianDTot += $JmlPenilaianDTot;
			$this->JmlPenghapusanKTot += $JmlPenghapusanKTot;
			$this->JmlPembukuanDTot += $JmlPembukuanDTot;
			$this->JmlPembukuanKTot += $JmlPembukuanKTot;
			$this->JmlReklassDTot += $JmlReklassDTot;
			$this->JmlReklassKTot += $JmlReklassKTot;
			$this->JmlSaldoAkTot += $JmlSaldoAkTot;
			
			$this->JmlSusutAkTot += $JmlSusutAkTot;
			$this->JmlSusutAwTot += $JmlSusutAkTot;
			$this->Jmldebet_susutTot += $Jmldebet_susutTot;
			$this->debet_susutTot += $jmlkredit_susutTot;
			$this->NilaiBukuTot += $jmlNilaiBukuTot;
	
		}

		return $ListData;
	}



	
	
	

	
	

	
	/*function genSum_setTampilValue($i, $value){
		if( $i = 8  || $i =10) {
			return number_format($value, 2, ',' ,'.');
		}else{
			return number_format($value, 0, ',' ,'.');	
		}
		
	}*/


	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');

		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\" colspan=6>REKAP NERACA</td>
			</tr>
			</table>"	
			.PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI,$this->cetak_xls)."<br>";
	}
	
	
}


$RekapPenyusutan= new RekapPenyusutanObj();



?>
