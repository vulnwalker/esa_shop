<?php

class UserAktivitasDetObj  extends DaftarObj2{	
	var $Prefix = 'UserAktivitasDet';
	var $SHOW_CEK = TRUE;
	var $elCurrPage="HalDefault";
	var $TblName = 'v1_admin_aktivitas';// 'v1_admin_aktivitas'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('uid');//('p','q'); //daftar/hapus
	var $FieldSum = array('lama');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 4, 3, 3);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Administrasi';
	var $PageIcon = 'images/administrasi_ico.png';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='detuseronline.xls';
	var $Cetak_Judul = 'DETAIL USER ONLINE';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $pagePerHal = 7;
	var $ico_width = '24';
	var $ico_height = '24';
	
	
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$cont = $this->genDaftarOpsi(3);
		return "<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>".$cont['TampilOpt'];
		/*$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>".
			"<div id='div_user_info'>".				
				"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr valign='top'><td width='50%'>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>User ID</b></td><td width='10'>:</td><td style='color:grey'><b>$this->uid</td></tr>
					<tr height=18><td><b>Nama Lengkap</td><td>:</td><td style='color:grey'>$nama</td></tr>
					<tr height=18><td><b>Level</td><td>:</td><td style='color:grey'>".$Main->UserLevel[$level ]."</td></tr>
					<tr height=18><td><b>Group</td><td>:</td><td style='color:grey'>$group</td></tr>
					</table>
				</td><td>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>Bidang</td><td width='10'>:</td><td style='color:grey'>$fmSKPD</td></tr>
					<tr height=18><td><b>Asisten/OPD</td><td>:</td><td style='color:grey'>$fmUNIT</td></tr>
					<tr height=18><td><b>Biro/UPTD/B</td><td>:</td><td style='color:grey'>$fmSUBUNIT</td></tr>
					</table>
				</td></tr>
				</table>
			</div>".
			"<div id='div_filter'>
				<table cellspacing='0' cellpadding='0' border='0'>
				<tr height=18><td width='100'><b>Periode</td><td width='10'>:</td><td style='color:grey'><b>$periode</td></tr>
				</table>
			</div>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					
				</tr>
			</table>";*/
	}
	
	function genRowSum_setTampilValue($i, $value){
		return jamToPetik($value);
		//return number_format($value,4, ',', '.');	
	}
	function genSum_setTampilValue($i, $value){
		return jamToPetik($value);
		//return number_format($value, 4, ',' ,'.');
	}
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=150>Login</th>
				<th class='th01' >Last Aktif </th>
				<th class='th01' >Lama </th>
				</tr>
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref, $Main, $HTTP_COOKIE_VARS;
		$Koloms = array();
		//$vlevel = $isi['level'] 
		//if ($TampilCheckBox != ''){
				
	
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		
		
		$Koloms[] = array('', TglJamInd( $isi['login'] ) );
		$Koloms[] = array('', TglJamInd( $isi['lastaktif'] ) );
		//$Koloms[] = array('align=right', $isi['lama'].' '.jamToPetik($isi['lama']));				
		$Koloms[] = array('align=right', jamToPetik($isi['lama']));				
		//}
		return $Koloms;
	}
	
	function setMenuEdit(){
		/*$buttonEdits = array(
			array('label'=>'SPPT Baru', 'icon'=>'new_f2.png','fn'=>"javascript:".$this->Prefix.".Baru()" )
		);*/
		return '';
			
	}
	function setMenuView(){
		return '';
	}
	function setTopBar(){
		return '';//genSubTitle($this->setTitle(),$this->genMenu());
	}
	function genDaftarOpsi($mode=1){
		global $Ref, $Main;
		$uid=cekPOST('uid',$this->uid);
		$tgl1 = cekPOST('tgl1', $this->tgllogin_tgl1_old);
		$tgl2 = cekPOST('tgl2', $this->tgllogin_tgl2_old);	
		
		//--- get data user
		$aqry = "select * from admin where uid='$uid'";
		$rowuser = mysql_fetch_array(mysql_query($aqry));
		$nama= $rowuser['nama'];
		$level= $rowuser['level'];
		$group= $rowuser['group'];
		
		//-- get skpd
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		if ($group!='00.00.00.'.$kdSubUnit0){
			$arrgrp = explode('.',$group);
			$c= $arrgrp[0];
			$d= $arrgrp[1];
			$e= $arrgrp[2];			
			$e1= $arrgrp[3];			
			
			$get = mysql_fetch_array(mysql_query( "select * from v_bidang where c='".$c."' " ));		
			if($get['nmbidang']<>'') $fmSKPD = $get['nmbidang'];
			$get = mysql_fetch_array(mysql_query( "select * from v_opd where c='".$c."' and d='".$d."' " ));		
			if($get['nmopd']<>'') $fmUNIT = $get['nmopd'];
			$get = mysql_fetch_array(mysql_query( "select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"	));		
			if($get['nmunit']<>'') $fmSUBUNIT = $get['nmunit'];
			$get = mysql_fetch_array(mysql_query( "select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."' " 	));		
			if($get['nm_skpd']<>'') $fmSEKSI = $get['nm_skpd'];
			
		}
		
		//-- periode
		if($tgl1!='') {
			$tgl1 = JuyTgl1($tgl1);
		}else{
			$tgl1 = ' - ';
		}
		if($tgl2!='') {
			$tgl2 = JuyTgl1($tgl2);
		}else{
			$tgl2 = JuyTgl1(Date('Y-m-d'));
		}
		$periode= $tgl1.' s/d '.$tgl2 ;
		
		
		if($mode==1){
			
		
		
		$TampilOpt = 			
				"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr valign='top'><td width='50%'>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>User ID</b></td><td width='10'>:</td><td style='color:grey'><b>$uid</td></tr>
					<tr height=18><td><b>Nama Lengkap</td><td>:</td><td style='color:grey'>$nama</td></tr>
					<tr height=18><td><b>Level</td><td>:</td><td style='color:grey'>".$Main->UserLevel[$level ]."</td></tr>
					<tr height=18><td><b>Group</td><td>:</td><td style='color:grey'>$group</td></tr>
					</table>
				</td><td>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>BIDANG</td><td width='10'>:</td><td style='color:grey'>$fmSKPD</td></tr>
					<tr height=18><td><b>SKPD</td><td>:</td><td style='color:grey'>$fmUNIT</td></tr>
					<tr height=18><td><b>UNIT</td><td>:</td><td style='color:grey'>$fmSUBUNIT</td></tr>
					<tr height=18><td><b>SUB UNIT</td><td>:</td><td style='color:grey'>$fmSEKSI</td></tr>
					</table>
				</td></tr>
				</table>
			</div>".
			"<div id='div_filter'>
				<table cellspacing='0' cellpadding='0' border='0'>
				<tr height=18><td width='100'><b>Periode</td><td width='10'>:</td><td style='color:grey'><b>$periode</td></tr>
				</table>
			</div>";
		}else{
			
		
		$TampilOpt = 			
				"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr valign='top'><td width='50%'>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>User ID</b></td><td width='10'>:</td><td >$uid</td></tr>
					<tr height=18><td><b>Nama Lengkap</td><td>:</td><td >$nama</td></tr>
					<tr height=18><td><b>Level</td><td>:</td><td >".$Main->UserLevel[$level ]."</td></tr>
					<tr height=18><td><b>Group</td><td>:</td><td >$group</td></tr>
					</table>
				</td><td>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>BIDANG</td><td width='10'>:</td><td >$fmSKPD</td></tr>
					<tr height=18><td><b>SKPD</td><td>:</td><td >$fmUNIT</td></tr>
					<tr height=18><td><b>UBIT</td><td>:</td><td >$fmSUBUNIT</td></tr>
					<tr height=18><td><b>SUB UNIT</td><td>:</td><td >$fmSEKSI</td></tr>
					</table>
				</td></tr>
				</table>
			</div>".
			"<div id='div_filter'>
				<table cellspacing='0' cellpadding='0' border='0'>
				<tr height=18><td width='100'><b>Periode</td><td width='10'>:</td><td >$periode</td></tr>
				</table>
			</div>";
		}
		return array('TampilOpt'=>$TampilOpt);
	}		
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		$Kondisi=''; $Order=''; $Limit=''; $NoAwal=0; $vKondisi2_old='';
		
		
		
		//-- get data
		//$UserAktivitas_cb  = cekPOST( 'UserAktivitas_cb', $this);
		$uid=cekPOST('uid',$this->uid);
		$tgl1 = cekPOST('tgl1', $this->tgllogin_tgl1_old);
		$tgl2 = cekPOST('tgl2', $this->tgllogin_tgl2_old);		
				
		//-- kondisi
		$arrKondisi = array();
		if ($uid!='') $arrKondisi[] = " uid='$uid' ";
		if($tgl1!='' ) $arrKondisi[] = " (login>='$tgl1') ";
		if($tgl2!='' ) $arrKondisi[] = " lastaktif<=DATE_ADD('$tgl2',INTERVAL 1 DAY) ";
		$Kondisi = join('and',$arrKondisi);
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		/*$vKondisi_old = genHidden( array(
			'uid' => $uid,
			'tgl1'=> $tgl1,
			'tgl2'=> $tgl2
			
		))*/;
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'vKondisi_old'=>$vKondisi_old);
		
	}
	
	
	
	
	function genDetail(){
		global $UserAktivitasDet, $Main;
		$cek = ''; $err=''; $content=''; 
		//$content = 'tessssss';
		$UserAktivitas_cb  = $_REQUEST['UserAktivitas_cb'];
		$this->tgllogin_tgl1_old = $_REQUEST['tgllogin_tgl1_old'];
		$this->tgllogin_tgl2_old = $_REQUEST['tgllogin_tgl2_old'];
		
		//-- get data user
		$this->uid=$UserAktivitas_cb[0];
		$aqry = "select * from admin where uid='$this->uid'";
		$rowuser = mysql_fetch_array(mysql_query($aqry));
		$nama= $rowuser['nama'];
		$level= $rowuser['level'];
		$group= $rowuser['group'];
		
		//-- get skpd
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		if ($group!='00.00.00.'.$kdSubUnit0){
			$arrgrp = explode('.',$group);
			$c= $arrgrp[0];
			$d= $arrgrp[1];
			$e= $arrgrp[2];			
			$e1= $arrgrp[2];			
			
			$get = mysql_fetch_array(mysql_query( "select * from v_bidang where c='".$c."' " ));		
			if($get['nmbidang']<>'') $fmSKPD = $get['nmbidang'];
			$get = mysql_fetch_array(mysql_query( "select * from v_opd where c='".$c."' and d='".$d."' " ));		
			if($get['nmopd']<>'') $fmUNIT = $get['nmopd'];
			$get = mysql_fetch_array(mysql_query( "select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"	));		
			if($get['nmunit']<>'') $fmSUBUNIT = $get['nmunit'];
			$get = mysql_fetch_array(mysql_query( "select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."' " 	));		
			if($get['nm_skpd']<>'') $fmSEKSI = $get['nm_skpd'];			
			
		}
		
		//-- periode
		if($this->tgllogin_tgl1_old!='') {
			$tgl1 = JuyTgl1($this->tgllogin_tgl1_old);
		}else{
			$tgl1 = ' - ';
		}
		if($this->tgllogin_tgl2_old!='') {
			$tgl2 = JuyTgl1($this->tgllogin_tgl2_old);
		}else{
			$tgl2 = JuyTgl1(Date('Y-m-d'));
		}
		$periode= $tgl1.' s/d '.$tgl2 ;
		
		//-- daftar		
		$daftar = $this->genDaftarInitial();
		$cont = 
			"<div id='div_user_info'>".
				genHidden( array(
					'uid' => $this->uid,
					'tgl1'=> $this->tgllogin_tgl1_old,
					'tgl2'=> $this->tgllogin_tgl2_old
					
				)).
				/*"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr valign='top'><td width='50%'>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>User ID</b></td><td width='10'>:</td><td style='color:grey'><b>$this->uid</td></tr>
					<tr height=18><td><b>Nama Lengkap</td><td>:</td><td style='color:grey'>$nama</td></tr>
					<tr height=18><td><b>Level</td><td>:</td><td style='color:grey'>".$Main->UserLevel[$level ]."</td></tr>
					<tr height=18><td><b>Group</td><td>:</td><td style='color:grey'>$group</td></tr>
					</table>
				</td><td>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr height=18><td width='100'><b>Bidang</td><td width='10'>:</td><td style='color:grey'>$fmSKPD</td></tr>
					<tr height=18><td><b>Asisten/OPD</td><td>:</td><td style='color:grey'>$fmUNIT</td></tr>
					<tr height=18><td><b>Biro/UPTD/B</td><td>:</td><td style='color:grey'>$fmSUBUNIT</td></tr>
					</table>
				</td></tr>
				</table>
			</div>".
			"<div id='div_filter'>
				<table cellspacing='0' cellpadding='0' border='0'>
				<tr height=18><td width='100'><b>Periode</td><td width='10'>:</td><td style='color:grey'><b>$periode</td></tr>
				</table>
			</div>".*/
			"<div id='div_daftar_aktivitas'>$daftar</div>";
		
		$this->form_fields = array(									
			'daftar_detail' => array( 'label'=>'', 
				'value'=> $cont, 
				'type'=> 'merge', 
			 ),
		);
		
		$this->form_width = 750;
		$this->form_height = 400;
		$this->form_caption = 'Detail User Online';	
		$this->form_menubawah =
			"<input type='button' value='Cetak' onclick ='".$this->Prefix.".cetakAll()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".CloseDet()' >";
			
		$content = $this->genForm();
		/*$content = $get['content'];
		$cek = $get['cek'];
		$err = $get['err'];*/
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genDaftarInitial(){
		//$vOpsi = $this->genDaftarOpsi();
		$Opsi = $this->getDaftarOpsi();
		$daftar = $this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']);
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative; height:250' >".	
				
				$daftar['content'].							
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//echo $tipe;
		switch($tipe){				
			case 'genDetail':{
				$fm = $this->genDetail();
				$cek =  $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
}
$UserAktivitasDet = new UserAktivitasDetObj();

class UserAktivitasObj  extends DaftarObj2{	
	var $Prefix = 'UserAktivitas';
	var $SHOW_CEK = TRUE;
	var $elCurrPage="HalDefault";
	var $TblName = 'v1_admin_aktivitas';// 'v1_admin_aktivitas'; //daftar
	var $TblName_Hapus = 'ref_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('uid');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Administrasi';
	var $PageIcon = 'images/administrasi_ico.png';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='useronline.xls';
	var $Cetak_Judul = 'USER ONLINE';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $ico_width = '24';
	var $ico_height = '24';
	
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		
		//$aqry = "select * from admin $Kondisi $Order $Limit ";	
		$aqry = 
			" select uid, nama, level, `group`, sum(lama)as lama from ".
			" v1_admin_aktivitas ".
			" $Kondisi  group by uid $Order $Limit ";	
		return $aqry;
		//return mysql_query($aqry);
	}
	function setSumHal_query($Kondisi, $fsum){
		return "select $fsum from (".
			" select uid, nama, level, `group`, sum(lama)as lama from ".
			" v1_admin_aktivitas ".
			" $Kondisi group by uid  ".
		") aa"; //echo $aqry;
	}
	function setTitle(){
		return 'User Online';
	}
	function setMenuEdit(){		
		return '';
	}
	function setMenuView(){
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".detail(\"$Op\")","new_f2.png",'Detail',"Detail")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$idplh = $_REQUEST[$this->Prefix.'_idplh'];
				$a1 = $Main->DEF_KEPEMILIKAN;
				$a = $Main->Provinsi[0];
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$nip= $_REQUEST['nip'];
				$nama= $_REQUEST['nama'];
				$jabatan= $_REQUEST['jabatan'];
				
				
				if( $err=='' && $nip =='' ) $err= 'NIP belum diisi!';
				if( $err=='' && $nama =='' ) $err= 'Nama belum diisi!';
				if( $err=='' && $jabatan =='' ) $err= 'Jabatan belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from ref_pegawai where nip='$nip' "
						));
						if($get['cnt']>0 ) $err='NIP Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into ref_pegawai (a,b,c,d,e,e1,nip,nama,jabatan)"."values('$a','$b','$c','$d','$e','$e1','$nip','$nama','$jabatan')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from ref_pegawai where Id='$idplh'"));
					if( $err=='' ){
						if($nip!=$old['nip'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from ref_pegawai where nip='$nip' "
							));
							if($get['cnt']>0 ) $err='NIP Sudah Ada!';
						}
					}
					if($err==''){
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						$aqry = "update ref_pegawai set ".
							" a='$a', b='$b', c='$c',d='$d',e='$e',e1='$e1',
							nip='$nip',nama='$nama', jabatan='$jabatan'".
							"where id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}	
	
	function genDetail(){
		global $UserAktivitasDet;
		$cek = ''; $err=''; $content=''; 
		//$content = 'tessssss';
		$UserAktivitas_cb  = $_REQUEST['UserAktivitas_cb'];
		$tgllogin_tgl1_old = $_REQUEST['tgllogin_tgl1_old'];
		$tgllogin_tgl2_old = $_REQUEST['tgllogin_tgl2_old'];
		
		//-- get data user
		$uid=$UserAktivitas_cb[0];
		$aqry = "select * from admin where uid='$uid'";
		$rowuser = mysql_fetch_array(mysql_query($aqry));
		$nama= $rowuser['nama'];
		$level= $rowuser['level'];
		$group= $rowuser['group'];
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		//-- get skpd
		if ($group!='00.00.00.'.$kdSubUnit0){
			$c= $_REQUEST['fmSKPD_old'];
			$d= $_REQUEST['fmUNIT_old'];
			$e= $_REQUEST['fmSUBUNIT_old'];			
			$e1= $_REQUEST['fmSEKSI_old'];			
			
			$get = mysql_fetch_array(mysql_query( "select * from v_bidang where c='".$c."' " ));		
			if($get['nmbidang']<>'') $fmSKPD = $get['nmbidang'];
			$get = mysql_fetch_array(mysql_query( "select * from v_opd where c='".$c."' and d='".$d."' " ));		
			if($get['nmopd']<>'') $fmUNIT = $get['nmopd'];
			$get = mysql_fetch_array(mysql_query( "select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"	));		
			if($get['nmunit']<>'') $fmSUBUNIT = $get['nmunit'];
			$get = mysql_fetch_array(mysql_query( "select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."' " 	));		
			if($get['nm_skpd']<>'') $fmSEKSI = $get['nm_skpd'];				
			
		}
		
		//-- periode
		if($tgllogin_tgl1_old!='') {
			$tgl1 = JuyTgl1($tgllogin_tgl1_old);
		}else{
			$tgl1 = ' - ';
		}
		if($tgllogin_tgl2_old!='') {
			$tgl2 = JuyTgl1($tgllogin_tgl2_old);
		}else{
			$tgl2 = JuyTgl1(Date('Y-m-d'));
		}
		$periode= $tgl1.' s/d '.$tgl2 ;
		
		//-- daftar
		/*$arrKondisi = array();
		if ($uid!='') $arrKondisi[] = " uid='$uid' ";
		if($tgllogin_tgl1_old!='' ) $arrKondisi[] = " (login>='$tgllogin_tgl1_old') ";
		if($tgllogin_tgl2_old!='' ) $arrKondisi[] = " lastaktif<=DATE_ADD('$tgllogin_tgl2_old',INTERVAL 1 DAY) ";
		$kondisi = join('and',$arrKondisi);
		if($kondisi != '')$kondisi = " where $kondisi ";
		$aqry = "select * from admin_aktivitas $kondisi ";
		$qry = mysql_query($aqry);
		$daftar = '';
		while ($isi = mysql_fetch_array($qry)){
			$daftar .= "<tr><td></td><td></td>";
		}
		*/
		$daftar = $UserAktivitasDet->genDaftarInitial();
		$cont = 
			"<div id='div_user_info'>
				User Profile
				<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr valig='top'><td width='50%'>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr><td width='100'>User ID</td><td width='10'>:</td><td>$uid</td></tr>
					<tr><td>Nama Lengkap</td><td>:</td><td>$nama</td></tr>
					<tr><td>Level</td><td>:</td><td>$level</td></tr>
					<tr><td>Group</td><td>:</td><td>$group</td></tr>
					</table>
				</td><td>
					<table cellspacing='0' cellpadding='0' border='0'>
					<tr><td width='100'>BIDANG</td><td width='10'>:</td><td>$fmSKPD</td></tr>
					<tr><td>SKPD</td><td>:</td><td>$fmUNIT</td></tr>
					<tr><td>UNIT</td><td>:</td><td>$fmSUBUNIT</td></tr>
					<tr><td>SUB UNIT</td><td>:</td><td>$fmSEKSI</td></tr>
					</table>
				</td></tr>
				</table>
			</div>".
			"<div id='div_filter'>
				<table cellspacing='0' cellpadding='0' border='0'>
				<tr><td width='100'>Periode</td><td width='10'>:</td><td>$periode</td></tr>
				</table>
			</div>".
			"<div id='div_daftar_aktivitas'>$daftar</div>";
		
		$this->form_fields = array(									
			'daftar_detail' => array( 'label'=>'', 
				'value'=> $cont, 
				'type'=> 'merge', 
			 ),
		);
		
		$this->form_width = 700;
		$this->form_height = 400;
		$this->form_caption = 'Detail User Online';	
		$this->form_menubawah =
			"<input type='button' value='Cetak' onclick ='".$this->Prefix.".cetakDetail()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			
		$content = $this->genForm();
		/*$content = $get['content'];
		$cek = $get['cek'];
		$err = $get['err'];*/
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other2($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'tes':{
				//echo 'tes';$json = FALSE;
				echo $this->pageShow(); 
				break;
			}
			case 'detail':{
				//$this->setFormBaru();
				$get= $this->genDetail();
				$err = $get['err']; $cek = $get['cek']; $content= $get['content'];
				$json = TRUE;
				break;
			}
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
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek =''; 
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from ref_pegawai where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 500;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];		
		
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=150>ID Pengguna</th>
				<th class='th01' >Nama Lengkap </th>
				<th class='th01' >Level </th>
				<th class='th01' >Group </th>
				<th class='th01' >Dinas </th>
				<th class='th01' >Lama </th>								
				
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref, $Main, $HTTP_COOKIE_VARS;
		$Koloms = array();
		//$vlevel = $isi['level'] 
		//if ($TampilCheckBox != ''){
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
			
		//get dinas		
		$dinas = '';
		if($isi['group']!= '00.00.00.'.$kdSubUnit0){
			$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
			$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
			$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
			$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
			
			$grp = $isi['group'];
			$c=''.substr($grp,0,2);
			$d=''.substr($grp,3,2);
			$e=''.substr($grp,6,2);
			$e1=''.substr($grp,9,$Main->SUBUNIT_DIGIT);
			///*
			$nmopdarr=array();	
			if($fmSKPD == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_bidang where c='".$c."' "
				));		
				if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
			}
			if($fmUNIT == '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
				$get = mysql_fetch_array(mysql_query(
					"select * from v_opd where c='".$c."' and d='".$d."' "
				));		
				if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
			}
			if($fmSUBUNIT == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"
				));		
				if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
			}
//			$nmopd = join(' - ', $nmopdarr );
			if($fmSEKSI == $kdSubUnit0){
				$get = mysql_fetch_array(mysql_query(
					"select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."'"
				));		
				if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];
			}
			$nmopd = join(' - ', $nmopdarr );
			//*/
			//$nmopd = $grp.' '.$c.$d.$e;
		}
	
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['uid']);		
		$Koloms[] = array('', $isi['nama'] );
		$Koloms[] = array('', $Main->UserLevel[$isi['level'] ]  );
		$Koloms[] = array('', $isi['group'] );
		$Koloms[] = array('', $nmopd );
		//$Koloms[] = array('align=right', $isi['lama'].' '.jamToPetik($isi['lama']));
		$Koloms[] = array('align=right', jamToPetik($isi['lama']));				
		//}
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		$level = $_REQUEST['level'];
		$tgllogin_tgl1 = $_REQUEST['tgllogin_tgl1'];
		$tgllogin_tgl2 = $_REQUEST['tgllogin_tgl2'];
		$tgllogin_tgl1_kosong = $_REQUEST['tgllogin_tgl1_kosong'];
		$tgllogin_tgl2_kosong = $_REQUEST['tgllogin_tgl2_kosong'];	
		$idpengguna = $_REQUEST['idpengguna'];
		
		
		//-- def tgl	
		if ($tgllogin_tgl1 =='' && $tgllogin_tgl1_kosong!=1) $tgllogin_tgl1 = date("Y-m-d"); 
		if ($tgllogin_tgl2 =='' && $tgllogin_tgl2_kosong!=1) $tgllogin_tgl2 = date("Y-m-d"); 
		$vlevel = 			
			"<div style='float:left;padding: 0 4 0 4;height:22;'>".
				cmb2D_v4('level',$level, $Main->UserLevel,'','Level Pengguna','','').
			"</div>";
		$vidpengguna = 			
			"<div style='float:left;padding: 0 4 0 4;height:22;'>".
				"ID Pengguna <input type='text' id='idpengguna' name='idpengguna' value='$idpengguna'>".
			"</div>";
		$vtgllogin = 	
			"<div style='float:left;padding: 0 4 0 4;height:22;'>".
				//createEntryTgl(	 'tgllogin', $tgllogin,	'', '', '', 'adminForm',0, $withBtnClear = TRUE).
				createEntryTglBeetwen('tgllogin',$tgllogin_tgl1, $tgllogin_tgl2,'','','adminForm',1, $tgllogin_tgl1_kosong, $tgllogin_tgl2_kosong);
			"</div>";	
		$TampilOpt =
			//"level= ".$level.
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>					
			</table>".
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
			<tr valign=\"top\">   		
			<td> ".
				"<table width=100%><tr><td>".
					"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan : </div>".
					$vidpengguna.$Main->batas.
					$vlevel. $Main->batas.
					$vtgllogin. $Main->batas.	
					"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>".
				"</td></tr></table>".
			"</td></tr></table>".$hiddenOld;
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
				
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		$arrKondisi = array();		
		
		
		/*$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.'00';
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		*/
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
				
		if($fmSKPD != '' && $fmSKPD != '00' ) $arrKondisi[] = " substring(`group`,1,2)='$fmSKPD' ";
    	if($fmUNIT != '' && $fmUNIT != "00" ) $arrKondisi[] =  " substring(`group`,4,2)='$fmUNIT' ";
    	if($fmSUBUNIT != '' && $fmSUBUNIT != "00" ) $arrKondisi[] = " substring(`group`,7,2)='$fmSUBUNIT' ";
    	if($fmSEKSI != '' && $fmSEKSI != $kdSubUnit0 ) $arrKondisi[] = " substring(`group`,10,".$Main->SUBUNIT_DIGIT.")='$fmSEKSI' ";
		
		//-- id pengguna
		$idpengguna = $_REQUEST['idpengguna'];
    	if($idpengguna != '' ) $arrKondisi[] = "  uid like '%$idpengguna%' ";
		//-- level	
		$level = $_REQUEST['level'];
		if($level != '' ) $arrKondisi[] = " level='$level'";	
		//-- tgl login
		$tgllogin_tgl1 = $_REQUEST['tgllogin_tgl1'];
		$tgllogin_tgl2 = $_REQUEST['tgllogin_tgl2'];
		$tgllogin_tgl1_kosong = $_REQUEST['tgllogin_tgl1_kosong']; //$cek.= ' tglkosong1='.$tgllogin_tgl1_kosong;
		$tgllogin_tgl2_kosong = $_REQUEST['tgllogin_tgl2_kosong']; //$cek.= ' tglkosong2='.$tgllogin_tgl2_kosong;
		//-- def tgl	
		if ($tgllogin_tgl1 =='' && $tgllogin_tgl1_kosong!='1') $tgllogin_tgl1 = date("Y-m-d"); 
		if ($tgllogin_tgl2 =='' && $tgllogin_tgl2_kosong!='1') $tgllogin_tgl2 = date("Y-m-d"); 
		//-- set tgl
		if($tgllogin_tgl1!='' ) $arrKondisi[] = " (login>='$tgllogin_tgl1')";
		if($tgllogin_tgl2!='' ) $arrKondisi[] = " lastaktif<=DATE_ADD('$tgllogin_tgl2',INTERVAL 1 DAY) ";
		
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
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
		$OrderDefault = ' Order By lama desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		//-- gen informasi kondisi, limit, order yg dipilih
		$vKondisi_old = 
		//'tesss'
			genHidden(array(
				'fmSKPD_old'=>$fmSKPD,
				'fmUNIT_old'=>$fmUNIT,
				'fmSUBUNIT_old'=>$fmSUBUNIT,
				'fmSEKSI_old'=>$fmSEKSI,
				'idpengguna_old'=>$idpengguna,
				'level_old'=>$level,
				'tgllogin_tgl1_old'=>$tgllogin_tgl1,
				'tgllogin_tgl2_old'=>$tgllogin_tgl2,
				'tgllogin_tgl1_kosong_old'=>$tgllogin_tgl1_kosong,
				'tgllogin_tgl2_kosong_old'=>$tgllogin_tgl2_kosong,
				'fmORDER1_old'=>$fmORDER1,
				'fmDESC1_old'=>$fmDESC1,
				'HalDefault_old'=>$HalDefault
			))
			;
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'vKondisi_old'=>$vKondisi_old);
		
	}
	
	
	

}

$UserAktivitas = new UserAktivitasObj();
?>