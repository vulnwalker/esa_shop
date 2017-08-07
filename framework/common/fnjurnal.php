<?php

class JurnalObj  extends DaftarObj2{	
	var $Prefix = 'Jurnal';
	var $elCurrPage="HalDefault";
	var $TblName = 't_jurnal_aset'; //daftar
	var $TblName_Hapus = 'v_jurnal';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('jns_trans2', 'refid');//('p','q'); //daftar/hapus
	var $FieldSum = array('debet','kredit','jml_barang_d','jml_barang_k');//array('jml_harga');
	var $fieldSum_lokasi = array( 8,9,10,11);
	var $totalCol = 19;
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Jurnal';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='jurnal.xls';
	var $Cetak_Judul = 'DAFTAR JURNAL';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	var $jns_trans2 = 0;
	var $refid = 0;
	var $kdbrg = '';
	var $kdskpd = '';
	var $kdakun = '';
	//var $refid2 = 0;
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Jurnal';
	}
	function setMenuEdit(){
		/*$buttonEdits = array(
			array('label'=>'SPPT Baru', 'icon'=>'new_f2.png','fn'=>"javascript:".$this->Prefix.".Baru()" )
		);*/
		return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>"
			'';
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".			*/
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){	
		global $Main;
			
		if($Main->JURNAL_FISIK){
			$this->TblName = 't_jurnal_aset';//				
		}else{
			$this->TblName = 'v_jurnal';//				
		}
		$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	
		return $aqry;		
	}
	function setSumHal_query($Kondisi, $fsum){
		global $Main;
			
		if($Main->JURNAL_FISIK){
			$this->TblName = 't_jurnal_aset';//				
		}else{
			$this->TblName = 'v_jurnal';//				
		}
		return "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		
		//return " select "
	}
	function genDaftarInitial(){
		global $Main;
				
		//idawal&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=7&debet=2
		$idbi = $_REQUEST['idbi'];
		$idawal = $_REQUEST['idawal'];
		$kint 	= $_REQUEST['kint'];
		$ka 	= $_REQUEST['ka'];
		$kb 	= $_REQUEST['kb'];
		$tgl1 	= $_REQUEST['tgl1'];		
		$tgl2 	= $_REQUEST['tgl2'];
		$jns_trans = $_REQUEST['jns_trans'];
		$debet 	= $_REQUEST['debet'];
		$c 		= $_REQUEST['c'];
		$d 		= $_REQUEST['d'];
		$e 		= $_REQUEST['e'];	
		setcookie('cofmSKPD', $c );	
		setcookie('cofmUNIT', $d );
		setcookie('cofmSUBUNIT', $e );
		$tanpasusut = $_REQUEST['tanpasusut'];
		
		
		$kdakun = $kint;
		$kdakun .= $ka!='' && $ka!='00' && $ka != NULL ?  '.'.$ka :'';
		$kdakun .= $kb!='' && $kb!='00' && $kb != NULL ?  '.'.$kb :'';
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h'];
		$i = $_REQUEST['i'];
		 
		setcookie('cofmSEKSI', genNumber(0, $Main->SUBUNIT_DIGIT ) );
		$hidden = 
			"<input type='hidden' id='idawal' name='idawal' value='$idawal'  >".
			"<input type='hidden' id='idbi' name='idbi' value='$idbi'  >".
			//"<input type='hidden' id='kint' name='kint' value='$kint'  >".
			//"<input type='hidden' id='ka' name='ka' value='$ka'  >".
			//"<input type='hidden' id='kb' name='kb' value='$kb'  >".			
			"<input type='hidden' id='kd_akun' name='kd_akun' value='$kdakun'  >".
			"<input type='hidden' id='tgl1' name='tgl1' value='$tgl1'  >".
			"<input type='hidden' id='tgl2' name='tgl2' value='$tgl2'  >".
			"<input type='hidden' id='jns_trans' name='jns_trans' value='$jns_trans'  >".
			"<input type='hidden' id='debet' name='debet' value='$debet'  >".	
			"<input type='hidden' id='JurnalSkpdfmSKPD' name='JurnalSkpdfmSKPD' value='$c'  >".	
			"<input type='hidden' id='JurnalSkpdfmUNIT' name='JurnalSkpdfmUNIT' value='$d'  >".	
			"<input type='hidden' id='JurnalSkpdfmSUBUNIT' name='JurnalSkpdfmSUBUNIT' value='$e'  >".	
			"<input type='hidden' id='g' name='g' value='$g'  >".		
			"<input type='hidden' id='h' name='h' value='$h'  >".		
			"<input type='hidden' id='i' name='i' value='$i'  >".		
			"<input type='hidden' id='tanpasusut' name='tanpasusut' value='$tanpasusut'  >".		
			"";
		
		$vOpsi = $this->genDaftarOpsi();
		
		$divHal = "<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
							"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
						"</div>";
		switch($this->daftarMode){						
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr valign=top>
					<td style='width:$this->containWidth;'>".
						"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
						$divHal.
					"</td>".
					"<td>".
						"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >"."</div>".
					$divHal;					
				break;
			}
		}
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". $hidden.
				//$vOpsi['TampilOpt'].
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	
	
	function Simpan(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$idbi = $_REQUEST['idbi'];
		$tgl = $_REQUEST['tgl'];
		$uid = $HTTP_COOKIE_VARS['coID'];
		$staset = $_REQUEST['staset'];
		$staset_baru = $_REQUEST['staset_baru'];
		$ket = $_REQUEST['ket'];
		$kondisi = $_REQUEST['kondisi'];
		$kondisi_baru = $_REQUEST['kondisi_baru'];
		
		//if($err=='' && $staset_baru == '') $err = "Status Aset belum dipilih!";
		//if($err=='' && $staset_baru == $staset) $err = "Status Aset harus beda!";
		if($err=='' && $tgl=='') $err = 'Tanggal belum diisi!';
		
		switch ($fmST){
			case 0 : { //baru	
				if($err==''){
					$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='$idbi'"));
					
					$staset = $bi['staset'];
					if($staset == 9){
						$staset_baru =  getStatusAset('', $kondisi_baru, $bi['harga'], $bi['f'], $bi['g'], $bi['h'], $bi['i'], $bi['j'] ) 	;
					}else{
						$staset_baru = 9;
					}
					
					$aqry = "insert into t_asetlainlain (tgl,idbi,uid,tgl_update,staset,staset_baru,ket, kondisi,kondisi_baru) ".
						" values('$tgl','$idbi','$uid',now(),'$staset','$staset_baru','$ket', '$kondisi','$kondisi_baru') "; $cek .= $aqry;
					$qry = mysql_query($aqry);		
					$newid= mysql_insert_id();
					if($qry){
						mysql_query("update buku_induk set staset = '$staset_baru' where  id='$idbi' ");	
						//jurnal
						$jur = jurnalAsetLainLain($bi, $idbi,$uid,$tgl,1, FALSE, $newid);
						$cek .= $jur['cek']; $err .=$jur['err'];
					}
				}				
				break;
			}
			case 1 : { //edit
				break;
			}
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function total(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//&c=00&d=00&e=00&e1=00&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=1
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
		$idel= $_REQUEST['idel'];
		$bold = $_REQUEST['bold'];
		$tanpasusut = $_REQUEST['tanpasusut'];
		$isjmlbrg = $_REQUEST['isjmlbrg'];
				
		//$tgl1 = $_REQUEST['tgl1'];
		//$tgl2 = $_REQUEST['tgl2'];		
		//$jns_trans = $_REQUEST['jns_trans'];
				
		//kondisi
		$arrKondisi = array();	//$arrKondisi[] = " idawal = '740330' ";				
		
		if(!($c=='' || $c=='00') ) $arrKondisi[] = "c='$c'";
		if(!($d=='' || $d=='00') ) $arrKondisi[] = "d='$d'";
		if(!($e=='' || $e=='00') ) $arrKondisi[] = "e='$e'";
		if(!($e1=='' || $e1=='00') ) $arrKondisi[] = "e1='$e1'";
				
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		
		//$kd_akun = $_REQUEST['kd_akun'];
		$kint = $_REQUEST['kint'];
		$ka = $_REQUEST['ka'];
		$kb = $_REQUEST['kb'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h'];
		$i = $_REQUEST['i'];
		$debet = $_REQUEST['debet'];
		
		$cek .= "kint=$kint ka=$ka kb=$kb";
		$kdakun = $kint; //$cek .= " kdakun1=$kdakun ";
		$kdakun .= $ka!='' && $ka!='00' && $ka != NULL ?  '.'.$ka :''; //$cek .= " kdakun2=$kdakun ";
		$kdakun .= $kb!='' && $kb!='00' && $kb != NULL ?  '.'.$kb :''; //$cek .= " kdakun3=$kdakun ";	
		if($kdakun!='') $arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
		
		
		$tgl1 = $_REQUEST['tgl1'];
		if($tgl1!='') $arrKondisi[] = " tgl_buku >='$tgl1' ";
		$tgl2 = $_REQUEST['tgl2'];
		if($tgl2!='') $arrKondisi[] = " tgl_buku <='$tgl2' ";
		$jns_trans = $_REQUEST['jns_trans'];
		if($jns_trans!='') $arrKondisi[] = " jns_trans ='$jns_trans' ";
		$jns_trans2 = $_REQUEST['jns_trans2'];
		if($jns_trans2!='') $arrKondisi[] = " jns_trans2 ='$jns_trans2' ";
		$debet = $_REQUEST['debet'];
		
		if($tanpasusut==1) $arrKondisi[] = " jns_trans <> 10 ";
		
		/*if($debet!='') {
			switch ($debet){
				case '1': $arrKondisi[] = " debet >0 "; break;
				case '2': $arrKondisi[] = " kredit >0 "; break;				
			}			
		}else{
			
		}*/
		
		
		if($g!='' && $g != '00') $arrKondisi[] = " g='$g' ";
		if($h!='' && $h != '00') $arrKondisi[] = " h='$h' ";
		if($i!='' && $i != '00') $arrKondisi[] = " i='$i' ";
		
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
			
		
		$tbl = //'v_jurnal';//
			' t_jurnal_aset ';
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
		
		$aqry = " select  sum($jml) as total from $tbl $Kondisi "; $cek.=$aqry;
		
		//select  sum( jml_barang_d-jml_barang_k ) as total from  t_jurnal_aset   Where  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.01.01%' and  tgl_buku <='2008-12-31'  and  jns_trans <> 10
		//rekap mutasi
		//$aqry = "select sum( if( , jml_barang_d - jml_barang_k,0  ) ".
		//	"from $tbl ".
		//	$Kondisi;
		
		$hsl = mysql_fetch_array( mysql_query($aqry) );
		
		$des = $isjmlbrg ==1? 0: 2;
		
		$content->idel = $idel;
		$content->total = $hsl['total'];
		$content->vtotal =  $bold ? '<b>'. number_format($hsl['total'],$des,',' , '.' ) .'</b>':  number_format($hsl['total'],$des,',' , '.' );
	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function rekapMutasi(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//&c=00&d=00&e=00&e1=00&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=1
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
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
				
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		
		//$kd_akun = $_REQUEST['kd_akun'];
		$kint = $_REQUEST['kint'];
		$ka = $_REQUEST['ka'];
		$kb = $_REQUEST['kb'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h']==''?'00':$_REQUEST['h'];
		$i = $_REQUEST['i']==''?'00':$_REQUEST['i'];
		$debet = $_REQUEST['debet'];
		
		$cek .= "kint=$kint ka=$ka kb=$kb";
		$kdakun = $kint; //$cek .= " kdakun1=$kdakun ";
		$kdakun .= $ka!='' && $ka!='00' && $ka != NULL ?  '.'.$ka :''; //$cek .= " kdakun2=$kdakun ";
		$kdakun .= $kb!='' && $kb!='00' && $kb != NULL ?  '.'.$kb :''; //$cek .= " kdakun3=$kdakun ";	
		//if($kdakun!='') $arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
		if($kdakun!='') {
			//$arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
			
			//if($kint = '01' && $ka = '01') {
			//	$arrKondisi[] = " f='$ka' and g='$kb'";	
			//}else{
				$arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kdakun%'";
			//}
		}
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
		
		
		if($g!='' && $g != '00') $arrKondisi[] = " g='$g' ";
		if($h!='' && $h != '00') $arrKondisi[] = " h='$h' ";
		if($i!='' && $i != '00') $arrKondisi[] = " i='$i' ";
		
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
			
		
		
		if($Main->JURNAL_FISIK){
			$tbl = 't_jurnal_aset';//				
		}else{
			$tbl = 'v_jurnal';//				
		}
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
		
		
		$aqry = " select  ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total1, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, debet-kredit ,0) ) as total2, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans=10, kredit-debet ,0) ) as total3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_k,0) ) as total4, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, kredit ,0) ) as total5, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total6, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d,0) ) as total7, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, debet ,0) ) as total8, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit ,0) ) as total9, ".
			
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total10, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, debet-kredit ,0) ) as total11, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans=10, kredit-debet ,0) ) as total12 ".
			
			/*" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, debet,0) ) as total1_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, jml_barang_d,0) ) as total1_3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, harga_atribusi ,0) ) as total2_1, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, debet  ,0) ) as total3_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, kredit  ,0) ) as total3_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_d  ,0) ) as total3_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_k  ,0) ) as total3_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, debet  ,0) ) as total4_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, kredit  ,0) ) as total4_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_d  ,0) ) as total4_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_k  ,0) ) as total4_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, debet  ,0) ) as total5_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, kredit  ,0) ) as total5_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_d  ,0) ) as total5_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_k  ,0) ) as total5_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, debet  ,0) ) as total6_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, kredit  ,0) ) as total6_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_d  ,0) ) as total6_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_k  ,0) ) as total6_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, debet  ,0) ) as total7_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, kredit  ,0) ) as total7_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_d  ,0) ) as total7_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_k  ,0) ) as total7_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, debet  ,0) ) as total8_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, kredit  ,0) ) as total8_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_d  ,0) ) as total8_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_k  ,0) ) as total8_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, debet  ,0) ) as total9_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, kredit  ,0) ) as total9_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_d  ,0) ) as total9_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_k  ,0) ) as total9_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total10_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit  ,0) ) as total10_2 ".*/
			
						
			" from $tbl ".
			" $Kondisi "; $cek.=$aqry;
		
		//select  sum( jml_barang_d-jml_barang_k ) as total from  t_jurnal_aset   Where  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.01.01%' and  tgl_buku <='2008-12-31'  and  jns_trans <> 10
		//rekap mutasi
		//$aqry = "select sum( if( , jml_barang_d - jml_barang_k,0  ) ".
		//	"from $tbl ".
		//	$Kondisi;
		
		$hsl = mysql_fetch_array( mysql_query($aqry) );
		
		$des = $isjmlbrg ==1? 0: 2;
		
		$content->idel = $idel;
		$content->total = $hsl['total'];
		$content->total1 = $hsl['total1'];//saldo aw
		$content->total2 = $hsl['total2'];//saldo aw
		$content->total3 = $hsl['total3'];//saldo aw
		$content->total4 = $hsl['total4'];
		$content->total5 = $hsl['total5'];
		$content->total6 = $hsl['total6'];
		$content->total7 = $hsl['total7'];
		$content->total8 = $hsl['total8'];
		$content->total9 = $hsl['total9'];
		$content->total10 = $hsl['total10'];//saldo ak
		$content->total11 = $hsl['total11'];//saldo ak
		$content->total12 = $hsl['total12'];//saldo ak
		
		
		
		
		$content->vtotal =  $bold ? '<b>'. number_format($hsl['total'],$des,',' , '.' ) .'</b>':  number_format($hsl['total'],$des,',' , '.' );
		$content->vtotal1 =  $bold ? '<b>'. number_format($hsl['total1'],0,',' , '.' ) .'</b>':  number_format($hsl['total1'],0,',' , '.' );
		$content->vtotal2 =  $bold ? '<b>'. number_format($hsl['total2'],2,',' , '.' ) .'</b>':  number_format($hsl['total2'],2,',' , '.' );
		$content->vtotal3 =  $bold ? '<b>'. number_format($hsl['total3'],2,',' , '.' ) .'</b>':  number_format($hsl['total3'],2,',' , '.' );
		$content->vtotal4 =  $bold ? '<b>'. number_format($hsl['total4'],0,',' , '.' ) .'</b>':  number_format($hsl['total4'],0,',' , '.' );
		$content->vtotal5 =  $bold ? '<b>'. number_format($hsl['total5'],2,',' , '.' ) .'</b>':  number_format($hsl['total5'],2,',' , '.' );
		$content->vtotal6 =  $bold ? '<b>'. number_format($hsl['total6'],2,',' , '.' ) .'</b>':  number_format($hsl['total6'],2,',' , '.' );
		$content->vtotal7 =  $bold ? '<b>'. number_format($hsl['total7'],0,',' , '.' ) .'</b>':  number_format($hsl['total7'],0,',' , '.' );
		$content->vtotal8 =  $bold ? '<b>'. number_format($hsl['total8'],2,',' , '.' ) .'</b>':  number_format($hsl['total8'],2,',' , '.' );
		$content->vtotal9 =  $bold ? '<b>'. number_format($hsl['total9'],2,',' , '.' ) .'</b>':  number_format($hsl['total9'],2,',' , '.' );
		$content->vtotal10 =  $bold ? '<b>'. number_format($hsl['total10'],0,',' , '.' ) .'</b>':  number_format($hsl['total10'],0,',' , '.' );
		$content->vtotal11 =  $bold ? '<b>'. number_format($hsl['total11'],2,',' , '.' ) .'</b>':  number_format($hsl['total11'],2,',' , '.' );
		$content->vtotal12 =  $bold ? '<b>'. number_format($hsl['total12'],2,',' , '.' ) .'</b>':  number_format($hsl['total12'],2,',' , '.' );
	
		/*$arrTotN = array();
		$arrVTotN = array();
		for ($i=0;$i<10;$i++){
			for ($j=0;$j<4;$j++){
				$arrTotN[$i][$j] = $hsl['total'.($i+1).'_'.($j+1)];
				$arrVTotN[$i][$j] =  $bold ? '<b>'. number_format($arrTotN[$i][$j],$des,',' , '.' ) .'</b>':  number_format($arrTotN[$i][$j],$des,',' , '.' );
			}
		}
		$content->totalN = $arrTotN;
		$content->vtotalN = $arrVTotN;
		*/
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function rekapNeraca(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		//&c=00&d=00&e=00&e1=00&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=1
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
		$e = $_REQUEST['e'];
		$e1 = $_REQUEST['e1'];
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
				
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		
		//$kd_akun = $_REQUEST['kd_akun'];
		$kint = $_REQUEST['kint'];
		$ka = $_REQUEST['ka'];
		$kb = $_REQUEST['kb'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h']==''?'00':$_REQUEST['h'];
		$i = $_REQUEST['i']==''?'00':$_REQUEST['i'];
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
		
		
		if($g!='' && $g != '00') $arrKondisi[] = " g='$g' ";
		if($h!='' && $h != '00') $arrKondisi[] = " h='$h' ";
		if($i!='' && $i != '00') $arrKondisi[] = " i='$i' ";
		
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
			
		
		if($Main->JURNAL_FISIK){
			$tbl = 't_jurnal_aset';//				
		}else{
			$tbl = 'v_jurnal';//				
		}
		
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
		
		
		$aqry = " select  ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total1, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans<>10, debet-kredit ,0) ) as total2, ".
			" sum( if( tgl_buku<'$tgl1' and jns_trans=10, kredit-debet ,0) ) as total3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_k,0) ) as total4, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, kredit ,0) ) as total5, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total6, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d,0) ) as total7, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans<>10, debet ,0) ) as total8, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit ,0) ) as total9, ".
			
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, jml_barang_d-jml_barang_k ,0) ) as total10, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans<>10, debet-kredit ,0) ) as total11, ".
			" sum( if( tgl_buku<='$tgl2' and jns_trans=10, kredit-debet ,0) ) as total12, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, debet,0) ) as total1_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, jml_barang_d,0) ) as total1_3, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=1, harga_atribusi ,0) ) as total2_1, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, debet  ,0) ) as total3_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, kredit  ,0) ) as total3_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_d  ,0) ) as total3_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=3, jml_barang_k  ,0) ) as total3_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, debet  ,0) ) as total4_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, kredit  ,0) ) as total4_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_d  ,0) ) as total4_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=4, jml_barang_k  ,0) ) as total4_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, debet  ,0) ) as total5_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, kredit  ,0) ) as total5_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_d  ,0) ) as total5_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=5, jml_barang_k  ,0) ) as total5_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, debet  ,0) ) as total6_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, kredit  ,0) ) as total6_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_d  ,0) ) as total6_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=6, jml_barang_k  ,0) ) as total6_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, debet  ,0) ) as total7_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, kredit  ,0) ) as total7_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_d  ,0) ) as total7_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=7, jml_barang_k  ,0) ) as total7_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, debet  ,0) ) as total8_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, kredit  ,0) ) as total8_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_d  ,0) ) as total8_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=8, jml_barang_k  ,0) ) as total8_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, debet  ,0) ) as total9_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, kredit  ,0) ) as total9_2, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_d  ,0) ) as total9_3, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=9, jml_barang_k  ,0) ) as total9_4, ".
			
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, debet  ,0) ) as total10_1, ".
			" sum( if( tgl_buku>='$tgl1' and tgl_buku<='$tgl2' and jns_trans=10, kredit  ,0) ) as total10_2 ".
			
						
			" from $tbl ".
			" $Kondisi "; $cek.=$aqry;
		
		//select  sum( jml_barang_d-jml_barang_k ) as total from  t_jurnal_aset   Where  CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '01.01.01%' and  tgl_buku <='2008-12-31'  and  jns_trans <> 10
		//rekap mutasi
		//$aqry = "select sum( if( , jml_barang_d - jml_barang_k,0  ) ".
		//	"from $tbl ".
		//	$Kondisi;
		
		$hsl = mysql_fetch_array( mysql_query($aqry) );
		
		$des = 2;//$isjmlbrg ==1? 0: 2;
		
		$content->idel = $idel;
		$content->total =  is_null($hsl['total'])? 0 : $hsl['total'] ;
		$content->total1 = is_null($hsl['total1'])? 0 :$hsl['total1'];//saldo aw
		$content->total2 = is_null($hsl['total2'])? 0 :$hsl['total2'];//saldo aw
		$content->total3 = is_null($hsl['total3'])? 0 :$hsl['total3'];//saldo aw
		$content->total4 = is_null($hsl['total4'])? 0 :$hsl['total4'];
		$content->total5 = is_null($hsl['total5'])? 0 :$hsl['total5'];
		$content->total6 = is_null($hsl['total6'])? 0 :$hsl['total6'];
		$content->total7 = is_null($hsl['total7'])? 0 :$hsl['total7'];
		$content->total8 = is_null($hsl['total8'])? 0 :$hsl['total8'];
		$content->total9 = is_null($hsl['total9'])? 0 :$hsl['total9'];
		$content->total10 = is_null($hsl['total10'])? 0 :$hsl['total10'];//saldo ak brg
		$content->total11 = is_null($hsl['total11'])? 0 :$hsl['total11'];//saldo ak hrg 
		$content->total12 = is_null($hsl['total12'])? 0 :$hsl['total12'];//saldo ak susut
		
		
		
		
		$content->vtotal =  $bold ? '<b>'. number_format($content->total,$des,',' , '.' ) .'</b>':  number_format($content->total,$des,',' , '.' );
		$content->vtotal1 =  $bold ? '<b>'. number_format($content->total1,$des,',' , '.' ) .'</b>':  number_format($content->total1,$des,',' , '.' );
		$content->vtotal2 =  $bold ? '<b>'. number_format($content->total2,$des,',' , '.' ) .'</b>':  number_format($content->total2,$des,',' , '.' );
		$content->vtotal3 =  $bold ? '<b>'. number_format($content->total3,$des,',' , '.' ) .'</b>':  number_format($content->total3,$des,',' , '.' );
		$content->vtotal4 =  $bold ? '<b>'. number_format($content->total4,$des,',' , '.' ) .'</b>':  number_format($content->total4,$des,',' , '.' );
		$content->vtotal5 =  $bold ? '<b>'. number_format($content->total5,$des,',' , '.' ) .'</b>':  number_format($content->total5,$des,',' , '.' );
		$content->vtotal6 =  $bold ? '<b>'. number_format($content->total6,$des,',' , '.' ) .'</b>':  number_format($content->total6,$des,',' , '.' );
		$content->vtotal7 =  $bold ? '<b>'. number_format($content->total7,$des,',' , '.' ) .'</b>':  number_format($content->total7,$des,',' , '.' );
		$content->vtotal8 =  $bold ? '<b>'. number_format($content->total8,$des,',' , '.' ) .'</b>':  number_format($content->total8,$des,',' , '.' );
		$content->vtotal9 =  $bold ? '<b>'. number_format($content->total9,$des,',' , '.' ) .'</b>':  number_format($content->total9,$des,',' , '.' );
		$content->vtotal10 =  $bold ? '<b>'. number_format($content->total10,$des,',' , '.' ) .'</b>':  number_format($content->total10,$des,',' , '.' );
		$content->vtotal11 =  $bold ? '<b>'. number_format($content->total11,$des,',' , '.' ) .'</b>':  number_format($content->total11,$des,',' , '.' );
		$content->vtotal12 =  $bold ? '<b>'. number_format($content->total12,$des,',' , '.' ) .'</b>':  number_format($content->total12,$des,',' , '.' );
	
		$arrTotN = array();
		$arrVTotN = array();
		for ($i=0;$i<10;$i++){
			for ($j=0;$j<4;$j++){
				$des= $j<2 ? 2 : 0;
				$arrTotN[$i][$j] = is_null($hsl['total'.($i+1).'_'.($j+1)])? 0 :$hsl['total'.($i+1).'_'.($j+1)];
				$arrVTotN[$i][$j] =  $bold ? '<b>'. number_format($arrTotN[$i][$j],$des,',' , '.' ) .'</b>':  number_format($arrTotN[$i][$j],$des,',' , '.' );
			}
		}
		$content->totalN = $arrTotN;
		$content->vtotalN = $arrVTotN;
		
		
		$content->saldoAk = is_null($hsl['total11'])? 0 : $hsl['total11'];
		$content->saldoAkBrg = is_null($hsl['total10'])? 0 : $hsl['total10'];
		$content->susutAk = is_null($hsl['total12'])? 0 :$hsl['total12'];
		$content->nilaibukuAk = $content->saldoAk  - $content->susutAk ;
		
		$content->vSaldoAk =$bold ? '<b>'. number_format($content->saldoAk ,2,',' , '.' ) .'</b>':  number_format($content->saldoAk ,2,',' , '.' );
		$content->vSaldoAkBrg =$bold ? '<b>'. number_format($content->saldoAkBrg ,0,',' , '.' ) .'</b>':  number_format($content->saldoAkBrg ,0,',' , '.' );
		$content->vSusutAk =$bold ? '<b>'. number_format($content->susutAk,2,',' , '.' ) .'</b>':  number_format($content->susutAk,2,',' , '.' );
		$content->vNilaibukuAk =$bold ? '<b>'. number_format($content->nilaibukuAk,2,',' , '.' ) .'</b>':  number_format($content->nilaibukuAk,2,',' , '.' );
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'total':{
				$fm = $this->total();// $this->total();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			case 'rekapMutasi':{
				$fm = $this->rekapMutasi();// $this->total();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			case 'rekapNeraca':{
				$fm = $this->rekapNeraca();// $this->total();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			case 'formBaruBI':{				
				$fm = $this->setFormBaruBI();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				//$content = 'tesssss';
				break;
			} 	
			case 'formEdit':{				
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpan':{
				$get = $this->Simpan();					
				//$get = array('cek'=>'', 'err'=>'ggal','content'=>'', 'json'=>TRUE);
				$cek = $get['cek'];
				$err = $get['err'];
				$content=$get['content'];
				$json=$get['json'];
				break;
			}
			/*case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			*/
			/*case 'formKondisiSimpan':{				
				$get= $this->formKondisiSimpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}*/
			
			default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
				break;
			}			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//form ==================================
	function setFormBaruBI(){
		global $Main;
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
		
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		
		$cidBI = $_REQUEST['cidBI'];
		$idbi = $cidBI[0];// 735615;
		$aqry = "select * from buku_induk where id ='$idbi'"; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		
		$dt['idbi']= $idbi;
		$dt['staset'] =  $bi['staset'];
		$dt['tgl'] =  Date('Y-m-d');
		$dt['kondisi'] = $bi['kondisi'];
		$dt['kondisi_baru'] = $bi['kondisi'];
			
		if($err=='' && ($bi['staset']==5 || $bi['staset']==6 || $bi['staset']==7)) $err = 'Barang '.$Main->StatusAsetView[$bi['staset']-1][1].' ini tidak bisa di reclass ke Aset Lain-lain!';
		if($err==''){
			
		
			$fm = $this->setForm($dt);
		}
		return	array ('cek'=>$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		//$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		//$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		//$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from t_asetlainlain where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		
		
		if($err==''){
			$form_name = $this->Prefix.'_form';				
			$this->form_width = 400;
			$this->form_height = $Main->STASET_OTOMATIS? 180: 150;
			
			$idbi = $dt['idbi'];
			$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id = '$idbi' "));
			
			$stasetAwal = $Main->StatusAsetView[$dt['staset']-1][1] ."<input type='hidden' id='staset' name='staset' value='".$dt['staset']."'>" ;
			$tgl = $dt['tgl'];
			$vtgl = createEntryTgl(	'tgl', $tgl, 	false, 	'', 	'','adminForm');
			//$selKondisi = cmb2D_v2( 'staset_baru',$dt['staset_baru'],$Main->StatusAset );
			
			
					
			$vkondisi = $Main->KondisiBarang[$dt['kondisi']-1][1];	
			$stkondisi = "<input type='hidden' id='kondisi' name='kondisi' value ='".$bi['kondisi']."' >";
			$stkondisi_baru = "<input type='hidden' id='kondisi_baru' name='kondisi_baru' value ='".$bi['kondisi']."' >";
			
			if($dt['staset'] == 9){
				$caption = 'Reclass dari Aset Lain-lain' ;
				if($Main->STASET_OTOMATIS){
					$dt['kondisi_baru'] = 1;
					$selSTASET =  $Main->StatusAsetView[ getStatusAset('', $dt['kondisi_baru'], $bi['harga'], $bi['f'], $bi['g'], 
						$bi['h'], $bi['i'], $bi['j'] ) -1][1];// $Main->StatusAsetView[3-1][1];						
					
					$arrKondisiBarang = array(
						array("1","Baik"),
						array("2","Kurang Baik"),					
					);
					$vkondisi_baru= cmb2D_v2( 'kondisi_baru',$dt['kondisi_baru'], $arrKondisiBarang);	
					$stkondisi = "<input type='hidden' id='kondisi' name='kondisi' value ='".$bi['kondisi']."' >";
					
					$this->form_fields = array(							
						'tgl' => array(  'label'=>'Tanggal', 'value'=> $vtgl,  'type'=>'' ),
						'staset' => array(  'label'=>'Dari', 'value'=> $stasetAwal, 'labelWidth'=>90, 'type'=>'' ),
						'staset_baru' => array(  'label'=>'Ke', 'value'=> $selSTASET, 'type'=>'' ),
						'kondisi' => array(  'label'=>'Kondisi Awal', 'value'=> $vkondisi.$stkondisi, 'labelWidth'=>90, 'type'=>'' ),
						'kondisi_baru' => array(  'label'=>'Kondisi Akhir', 'value'=> $vkondisi_baru, 'type'=>'' ),
						'ket'=> array(  'label'=>'Keterangan', 'value'=> "<textarea id='ket' name='ket' style='width: 234px;height: 35px;'>".$dt['ket']."</textarea>", 'type'=>'' )
					);
				}else{
					$dt['kondisi_baru'] = $bi['kondisi'];
					$selSTASET =  $Main->StatusAsetView[ getStatusAset('', $dt['kondisi_baru'], $bi['harga'], $bi['f'], $bi['g'], 
						$bi['h'], $bi['i'], $bi['j'] ) -1][1];// $Main->StatusAsetView[3-1][1];	
				
					$vkondisi_baru = $Main->KondisiBarang[$dt['kondisi_baru']-1][1];
					
					$this->form_fields = array(							
						'tgl' => array(  'label'=>'Tanggal', 'value'=> $vtgl,  'type'=>'' ),
						'staset' => array(  'label'=>'Dari', 'value'=> $stasetAwal, 'labelWidth'=>90, 'type'=>'' ),
						'staset_baru' => array(  'label'=>'Ke', 'value'=> $selSTASET.$stkondisi.$stkondisi_baru, 'type'=>'' ),
						'ket'=> array(  'label'=>'Keterangan', 'value'=> "<textarea id='ket' name='ket' style='width: 234px;height: 35px;'>".$dt['ket']."</textarea>", 'type'=>'' )
					);
				}
				
				
				
			}else{
				$caption = 'Reclass ke Aset Lain-lain' ;
				$selSTASET = $Main->StatusAsetView[9-1][1]	;
				
				$dt['kondisi_baru'] = $bi['kondisi'];
				if($Main->STASET_OTOMATIS){				
					$vkondisi_baru= cmb2D_v2( 'kondisi_baru',$dt['kondisi_baru'],$Main->KondisiBarang );
					$this->form_fields = array(							
						'tgl' => array(  'label'=>'Tanggal', 'value'=> $vtgl,  'type'=>'' ),
						'staset' => array(  'label'=>'Dari', 'value'=> $stasetAwal, 'labelWidth'=>90, 'type'=>'' ),
						'staset_baru' => array(  'label'=>'Ke', 'value'=> $selSTASET, 'type'=>'' ),
						'kondisi' => array(  'label'=>'Kondisi Awal', 'value'=> $vkondisi.$stkondisi, 'labelWidth'=>90, 'type'=>'' ),
						'kondisi_baru' => array(  'label'=>'Kondisi Akhir', 'value'=> $vkondisi_baru, 'type'=>'' ),
						'ket'=> array(  'label'=>'Keterangan', 'value'=> "<textarea id='ket' name='ket' style='width: 234px;height: 35px;'>".$dt['ket']."</textarea>", 'type'=>'' )
					);
				}else{
					//$vkondisi_baru = $Main->KondisiBarang[$dt['kondisi_baru']-1][1];
					
					$this->form_fields = array(							
						'tgl' => array(  'label'=>'Tanggal', 'value'=> $vtgl,  'type'=>'' ),
						'staset' => array(  'label'=>'Dari', 'value'=> $stasetAwal, 'labelWidth'=>90, 'type'=>'' ),
						'staset_baru' => array(  'label'=>'Ke', 'value'=> $selSTASET.$stkondisi.$stkondisi_baru, 'type'=>'' ),
						'ket'=> array(  'label'=>'Keterangan', 'value'=> "<textarea id='ket' name='ket' style='width: 234px;height: 35px;'>".$dt['ket']."</textarea>", 'type'=>'' )
					);
				}
			}
			
			if ($this->form_fmST==0) {		
				$this->form_caption = $caption;
			}else{
				$this->form_caption = $caption.' - Edit';
			}	
			
							
			//tombol
			$this->form_menubawah =			
				"<input type='hidden' name='idbi' id='idbi' value='$idbi'>".
				"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
				"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
					
			$form = $this->genForm();		
					
			$content = $form;//$content = 'content';
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		//kint,ka,kb,jns_trans,jns_trans2,refid,jns_trans3,refid2,debet,kredit,jml_barang_d,jml_barang_k
		$headerTable =
			"<thead>
			<tr>
				
				<th class='th01' width='40' rowspan=2>No.</th>
				$Checkbox		
				
				<th class='th01' width='80' rowspan=2>Tanggal</th>
				<th class='th01' width='30' rowspan=2>Uraian </th>
				<th class='th01' width='' rowspan=2>KD Akun </th>
				<th class='th01' width='' rowspan=2>Nama Akun </th>
				<th class='th01' width='30' rowspan=2>Rincian Harga </th>
				
				
				<th class='th02' colspan=2> Harga</th>
				<th class='th02' colspan=2> Jumlah</th>
				
				<th class='th01' width='30' rowspan=2>Group </th>	
				<th class='th02' width='' colspan=6>Barang </th>					
				<th class='th01' rowspan=2>Ket </th>
				
			</tr>
			<tr>
				<th class='th01' > Debet  </th>				
				<th class='th01' > Kredit </th>				
				<th class='th01' > Debet  </th>				
				<th class='th01' > Kredit </th>		
				
				
				<th class='th01' width='80' rowspan=1>ID Barang</th>
				<th class='th01' width='80' rowspan=1>ID Awal</th>
				<th class='th01' rowspan=1>KD SKPD</th>
				<th class='th01' rowspan=1>KD Barang </th>
				<th class='th01' width='80' rowspan=1>Tahun Perolehan </th>
				<th class='th01' width='40' rowspan=1>No Reg. </th>		
			</tr>				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){		
		global $Ref, $Main;
		
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id ='".$isi['idbi']."' "));
		$akun = mysql_fetch_array(mysql_query("select * from v_ref_kib_keu where kint='{$isi['kint']}' and ka='{$isi['ka']}' and kb='{$isi['kb']}' "));
		
		
		$vkdskpd = $isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$isi['e1'];
		$vkdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
		$vkdakun = $isi['kint'].'.'.$isi['ka'].'.'.$isi['kb'];
		$vnmakun =  $akun['nm_barang']; 
		//$kdakun_awal = $this->kdakun;
		
		$vjnstrans = $Main->jnsTrans[$isi['jns_trans']-1][1];
		if($isi['jns_trans2']==$this->jns_trans2 && $isi['refid']==$this->refid){
			$vtgl = '';
			$vjnstrans2 =  '';
			
			if($this->kdskpd == $vkdskpd){
				$vkdskpd= '';
			}else{
				$this->kdskpd =$vkdskpd	;
			}
			if($this->kdbrg == $vkdbrg){
				$vkdbrg = '';	
			}else{
				$this->kdbrg =$vkdbrg ;
			}
			if($this->kdakun == $vkdakun){
				$vkdakun = '';
				$vnmakun = '';
			}else{
				$this->kdakun = $vkdakun;
				
			}
			
			$vtahun = '';
			$vnoreg = '';
			$vidbi = '';
		}else{
			$vtgl 		= TglInd($isi['tgl_buku']);
			$vjnstrans2 = $Main->jnsTrans2[$isi['jns_trans2']-1][1] ." #".$isi['refid'];	
			$this->kdskpd = $vkdskpd ;
			$this->kdbrg= $vkdbrg ;
			$this->kdakun = $vkdakun;
			$vtahun 	= $bi['tahun'];
			$vnoreg 	= $bi['noreg'];
			$vidbi 		= $isi['idbi'];
		}
		
		$vjnstrans3 =  $Main->jnsTrans3[$isi['jns_trans3']-1][1] ." #".$isi['refid2'];
		$this->jns_trans2 = $isi['jns_trans2'];
		$this->refid = $isi['refid'];
				
		//if( $isi['jns_trans'] ==1 ) $isi['debet'] = $isi['debet'] - $isi['harga_atribusi'];		
		$vdebet = $isi['debet']==0? '':number_format( $isi['debet'],2,',','.');
		
		//if( $isi['jns_trans'] ==1 ) $vdebet =$isi['harga_atribusi'];
				
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		
		$Koloms[] = array('', $vtgl );
		
		$Koloms[] = array('', $vjnstrans2 ); //trans
		
		
		/*$Koloms[] = array('', $isi['kint'] );
		$Koloms[] = array('', $isi['ka'] );
		$Koloms[] = array('', $isi['kb'] );
		*/
		$Koloms[] = array('', $vkdakun    );
		$Koloms[] = array('',  $vnmakun  );
		
		$Koloms[] = array('', $vjnstrans3 ); //trans rincian
		
		$Koloms[] = array(" align='right' ", $vdebet );
		
		$Koloms[] = array(" align='right' ", $isi['kredit']==0? '':number_format( $isi['kredit'],2,',','.') );
		
		$Koloms[] = array(" align='right' ", $isi['jml_barang_d']==0? '': number_format($isi['jml_barang_d'],0,',','.') );
		$Koloms[] = array(" align='right' ", $isi['jml_barang_k']==0? '': number_format($isi['jml_barang_k'],0,',','.') );
		
		
		
		$Koloms[] = array('', $vjnstrans ); //group
		$Koloms[] = array('', $isi['idbi'] );
		$Koloms[] = array('', $isi['idawal'] );
		
		$Koloms[] = array('', $vkdskpd );
		$Koloms[] = array('', $vkdbrg );
		$Koloms[] = array('', $vtahun);
		$Koloms[] = array('', $vnoreg);
				
		
		//$Koloms[] = array('', $Main->StatusAsetView[$isi['staset']-1][1] );
		//$Koloms[] = array('', $Main->StatusAsetView[$isi['staset_baru']-1][1] );//$isi['staset_baru']);				
		
		//kint,ka,kb,jns_trans,jns_trans2,refid,jns_trans3,refid2,debet,kredit,jml_barang_d,jml_barang_k
		
		
		
		$Koloms[] = array('', $isi['ket']);				
		return $Koloms;
	}
	
	function setPage_HeaderOther(){
		global $Main;	
		
		$Pg= $_REQUEST['Pg'];
		$menu = $_REQUEST['menu'];		
		$styleMenu = " style='color:blue;' ";	
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		//$txtRakapbi = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "REKAP NERACA" : "REKAP BI";
		//$txtRekapneraca = $Main->VERSI_NAME == 'KOTA_BANDUNG' ? "KERTAS KERJA" : "REKAP NERACA";
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca'  >KERTAS KERJA</a>": '';
		
		$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
							"<A href=\"index.php?Pg=05&SPg=kibg&jns=atb\"  title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
						
	
		if($Main->VERSI_NAME == 'JABAR'){
			
		
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
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP ASET</a>
				<!--|<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI 2'  >REKAP BI</a>   -->
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI II</a>
				| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu >JURNAL</a>
				  &nbsp&nbsp&nbsp
				</td></tr>":'';	
				
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
				<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP NERACA</a> <!--|
				<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI' >REKAP BI 2</a>  -->  
				$menu_rekapneraca_2
				| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
				| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu>JURNAL</a> 
				  &nbsp&nbsp&nbsp
				</td></tr>	":'';
		}
		
		$menubar3 = "<tr>
			<td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			
			<A href=\"pages.php?Pg=Jurnal\" title='Jurnal'  $styleMenu>JURNAL</a> |
			<A href=\"pages.php?Pg=AsetLainLain\" title='Reklas Aset Lain-lain'  >ASET LAIN-LAIN</a> |
			<A href=\"pages.php?Pg=Kapitalisasi\" title='Kapitalisasi'  >KAPITALISASI</a> |
				<A href=\"pages.php?Pg=Kondisi\" title='Kondisi'  >KONDISI</a>
			
			&nbsp&nbsp&nbsp
			</td>
			</tr>";
		
		$menu_penyusutan = $Main->PENYUSUTAN ? 
			" <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenu title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>": '';
		
		
			
			$menubar1 = 			//"<tr height='22' valign='top'><td >".
				"<A href=\"index.php?Pg=05&SPg=03\" title='Buku Inventaris'>BI</a> |
				<A href=\"index.php?Pg=05&SPg=04\" title='Tanah'>KIB A</a>  |  
				<A href=\"index.php?Pg=05&SPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
				<A href=\"index.php?Pg=05&SPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
				<A href=\"index.php?Pg=05&SPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
				<A href=\"index.php?Pg=05&SPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
				<A href=\"index.php?Pg=05&SPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |  
							
				<A href=\"index.php?Pg=05&SPg=11\" title='Rekap BI'>REKAP BI</a> |";
			
			$menubar1.= $Main->VERSI_NAME == 'JABAR' ? " <A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' $styleMenu8>PETA</a> |":'';
			
			
			if($Main->MODUL_MUTASI) 
				$menubar1.="
					<A href=\"index.php?Pg=05&SPg=12\" title='Daftar Mutasi'>MUTASI</a>  |
					<A href=\"index.php?Pg=05&SPg=13\" title='Rekap Mutasi'>REKAP MUTASI</a> | ";
					
			//$menubar=$menubar."<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> |";
			$menubar1 .=$Main->VERSI_NAME == 'JABAR' ? '': "<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruangan'>KIR</a> | ";
			if($Main->MODUL_SENSUS){
				$menubar1.= $Main->VERSI_NAME == 'JABAR' ? "<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >INVENTARISASI</a> |":
					"<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' >SENSUS</a> | ";
			} 
				
			
			if($Main->MODUL_PEMBUKUAN) 
				$menubar1.="<A href=\"index.php?Pg=05&SPg=03&jns=intra\" title='Akuntansi' $styleMenu>AKUNTANSI</a>";
				
			$menubar1 .= $Main->VERSI_NAME == 'JABAR' ? "| <A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> ":'';
			
		//$menubar .= ;	
		
		$menubar=
			"<!--menubar_page-->
		
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
			$menubar1.
			"&nbsp&nbsp&nbsp
			</td></tr>".
			$menu_pembukuan1.
			$menubar3.
			"</table>";
		
		
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
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>": '';
		
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
	<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' >REKAP BI</a>  
	$menu_rekapneraca_2
	| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi'  >REKAP MUTASI</a>
	| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu>JURNAL</a>

	  &nbsp&nbsp&nbsp
	</td></tr>
	<tr>
	<td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	
	<A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu >JURNAL</a> |
	<A href=\"pages.php?Pg=AsetLainLain\" title='Aset Lain-lain'>ASET LAIN-LAIN</a> |
	<A href=\"pages.php?Pg=Kapitalisasi\" title='Kapitalisasi'>KAPITALISASI</a>
	
	&nbsp&nbsp&nbsp
	</td>
	</tr>":'';	
		
		
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
		global $Ref, $Main;
		
		$arrDebet = array(
	  	          	array('1','Debet'),
			     	array('2','Kredit'),
					);
		
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		$idawal = $_REQUEST['idawal'];
		$idbi= $_REQUEST['idbi'];
		$kint 	= $_REQUEST['kint'];
		$ka 	= $_REQUEST['ka'];
		$kb 	= $_REQUEST['kb'];
		$KdAkun = $_REQUEST['kd_akun'];
		$tgl1 	= $_REQUEST['tgl1'];
		$tgl2 	= $_REQUEST['tgl2'];
		$jns_trans = $_REQUEST['jns_trans'];
		$jns_trans2 = $_REQUEST['jns_trans2'];
		$debet 	= $_REQUEST['debet'];
		$kd_barang = $_REQUEST['kd_barang'];
		$thn_perolehan = $_REQUEST['thn_perolehan'];
		$noreg = $_REQUEST['noreg'];
		$tanpasusut = $_REQUEST['tanpasusut'];
		$g = $_REQUEST['g'];
		$h = $_REQUEST['h'];
		$i = $_REQUEST['i'];
		
		/*$hidden	= 
			"<input type='hidden' id='idawal' name='idawal' value='$idawal'  >".
			"<input type='hidden' id='kint' name='kint' value='$kint'  >".
			"<input type='hidden' id='ka' name='ka' value='$ka'  >".
			"<input type='hidden' id='kb' name='kb' value='$kb'  >".
			"<input type='hidden' id='tgl1' name='tgl1' value='$tgl1'  >".
			"<input type='hidden' id='tgl2' name='tgl2' value='$tgl2'  >".
			"<input type='hidden' id='jns_trans' name='jns_trans' value='$jns_trans'  >".
			"<input type='hidden' id='debet' name='debet' value='$debet'  >".			
			"";	*/
		
		/*$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];	
		$fmIdbiAwal = cekPOST('idbi_awal');
		$fmIdbi = cekPOST('idbi');
		$fmKdAkun = cekPOST('kd_akun');
		$fmJnsTrans = cekPOST('fmJnsTrans');
		$fmJnsTrans2 = cekPOST('fmJnsTrans2');*/
		$vtanpasusut = $tanpasusut ?  " checked='true' " : '';
		$vtanpasusut = " <input type='checkbox' name='tanpasusut' id='tanpasusut' value='1' $vtanpasusut  >Tanpa Penyusutan";
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				$hidden							
			</td></tr>			
			</table>".
			"<div class='FilterBar'>
				<table style='width:100%'>
					<tbody>
						<tr>
							<td align='left'>
								<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
									<tr valign='middle'>
										<td align='left' style='padding:1 8 0 8;height:30; '>".
											//$vtgl =
											"<div style='float:left;padding: 0 4 0 0'> Tanggal :</div>".
											"<div style='float:left;padding: 0 4 0 0'>".createEntryTgl3($tgl1, 'tgl1', false, '')."</div>".
											"<div style='float:left;padding: 0 4 0 0'>s/d &nbsp;</div>".
											"<div style='float:left;padding: 0 4 0 0'>".createEntryTgl3($tgl2, 'tgl2', false, '')."</div>".
											$vtanpasusut.
										"</td>
									</tr>
									<tr>
										<td align='left' style='padding:1 8 0 8;height:30; '>
											ID Awal : <input type='text' name='idawal' id='idawal' value='".$idawal."'> &nbsp;&nbsp;&nbsp;
											ID Barang : <input type='text' name='idbi' id='idbi' value='".$Idbi."'> &nbsp;&nbsp;&nbsp;
											KD Akun : <input type='text' name='kd_akun' id='kd_akun' value='".$KdAkun."'> &nbsp;&nbsp;&nbsp;".
											cmbArray('jns_trans',$jns_trans,$Main->jnsTrans,'--Group--','')."&nbsp&nbsp;&nbsp;".
											cmbArray('jns_trans2',$jns_trans2,$Main->jnsTrans2,'--Transaksi--','')."&nbsp&nbsp;&nbsp;".
											cmbArray('debet',$debet,$arrDebet,'--Semua--','')."&nbsp&nbsp;&nbsp;".
											"<input type='hidden' id='g' name='g' value='$g'  >".	
											"<input type='hidden' id='h' name='h' value='$h'  >".	
											"<input type='hidden' id='i' name='i' value='$i'  >".	
											"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>".	
										"</td>
									</tr>".
									/*"<tr>
										<td align='left' style='padding:1 8 0 8;height:30; '>
											KD Barang : <input type='text' name='kd_barang' id='kd_barang' value='".$kd_barang."'> &nbsp;&nbsp;&nbsp;
											Tahun : <input type='text' name='thn_perolehan' id='thn_perolehan' value='".$thn_perolehan."'> &nbsp;&nbsp;&nbsp;
											No. Reg : <input type='text' name='noreg' id='noreg' value='".$noreg."'> &nbsp;&nbsp;&nbsp;
											
										</td>
									</tr>".
									*/
								"</table>
							</td>
						</tr>
					</tbody>
				</table>
		    </div>";
			
			/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}

	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();	//$arrKondisi[] = " idawal = '740330' ";	
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		/*$arrKondisi[] = getKondisiSKPD2(
			12, 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);*/
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		 	
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		//&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=7&debet=2
		/*$kint = $_REQUEST['kint'];
		if($kint!='') $arrKondisi[] = " kint ='$kint' ";
		$ka = $_REQUEST['ka'];
		if($ka!='') $arrKondisi[] = " ka ='$ka' ";
		$kb = $_REQUEST['kb'];
		if($kb!='') $arrKondisi[] = " kb ='$kb' ";*/
		$g = $_REQUEST['g'];
		if($g != '' && $g!= '00') $arrKondisi[] =  " g='$g' ";
		$h = $_REQUEST['h'];
		if($h != '' && $h!= '00') $arrKondisi[] =  " h='$h' ";
		$i = $_REQUEST['i'];
		if($i != '' && $i!= '00') $arrKondisi[] =  " i='$i' ";
		$kd_akun = $_REQUEST['kd_akun'];
		if($kd_akun!='') $arrKondisi[] = " CONCAT(CAST(kint AS CHAR CHARACTER SET utf8),'.',CAST(ka AS CHAR CHARACTER SET utf8),'.', CAST(kb AS CHAR CHARACTER SET utf8)) like '$kd_akun%'";
		$tgl1 = $_REQUEST['tgl1'];
		if($tgl1!='') $arrKondisi[] = " tgl_buku >='$tgl1' ";
		$tgl2 = $_REQUEST['tgl2'];
		if($tgl2!='') $arrKondisi[] = " tgl_buku <='$tgl2' ";
		$jns_trans = $_REQUEST['jns_trans'];
		if($jns_trans!='') $arrKondisi[] = " jns_trans ='$jns_trans' ";
		$jns_trans2 = $_REQUEST['jns_trans2'];
		if($jns_trans2!='') $arrKondisi[] = " jns_trans2 ='$jns_trans2' ";
		$debet = $_REQUEST['debet'];
		if($debet!='') {
			switch ($debet){
				case '1': $arrKondisi[] = " debet >0 "; break;
				case '2': $arrKondisi[] = " kredit >0 "; break;				
			}
			
		}
		
		$tanpasusut = $_REQUEST['tanpasusut'];
		if($tanpasusut==1) $arrKondisi[] = " jns_trans <> 10 ";
		
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = ' Order by tgl_buku, jns_trans2, refid, q, kint,ka,kb, jns_trans3  ';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);	$Main->PagePerHal = 50;				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$Jurnal = new JurnalObj();

?>