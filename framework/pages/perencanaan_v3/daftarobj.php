<?php

include_once('fnMenu.php');


function Fmt($val,$FormatType=0){ //format entry
	$hsl ='';
	switch($FormatType){
		case 1: $hsl = number_format($val,2,',','.'); break;
		case 2: $hsl = number_format($val,0,',','.'); break;
		default: $hsl = $val; break; 	
	}
	return $hsl;
}


function createHeaderPage($headerIco, $headerTitle,  $otherMenu='', $headerFixed= FALSE, 
	$headerClass='pageheader', 
	$ico_width=20, $ico_height=30 )
{
	global $Main;
	//$headerIco = 'images/icon/daftar32.png'; $headerTitle = 'Pendaftaran & Pendataan';
	$headerMenu = $Main->MenuHeader;
	$TampilPosFix = $headerFixed==TRUE? "position:fixed;top:0;":'';	
	/*return 
		"<table id='head' cellspacing='0' cellpadding='0' border='0' class='$headerClass' style='$TampilPosFix'>
			<tr class=''>
			<td width='36'><img src='$headerIco' ></td>
			<td>$headerTitle</td>
			<td>$otherMenu $headerMenu</td>			
		</tr>	
	</table>
	";
	*/
	
	return 
	"<table width='100%' class='menubar' cellpadding='0' cellspacing='0' border='0'>
		<tbody><tr>
		<td background='images/bg.gif'>
		
			<div id='pagetitle'>					
					<table width='100%'> <tbody><tr>
					<td width='30'>						
						<img src='$headerIco' height='$ico_height' width='$ico_width'>
					</td>
					<td>$headerTitle</td>
					<td align='right'>
						<!--menubar_kanatas-->
						<table><tbody><tr><td>
						
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/home_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='index.php?Pg=perencanaan_v3' title='Main Menu'> 											
						</a>
						</div>
												
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/logout_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='index.php?Pg=LogOut' title='Logout'> 											
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/search_24.png);	
									width:24;height:24;display: inline-block;position:absolute' target='_blank' href='viewer.php' title='Pencarian Data'> 				
							
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a style='background: no-repeat url(images/administrator/images/help_f2_24.png);	
									width:24;height:24;display: inline-block;position:absolute' href='pages.php?Pg=userprofil' title='User Profile'> 											
						</a>
						</div>
						
						<div style='margin:0 4 0 4;width:24;height:24;float:right;position:relative'>
						<a id='chat_alert' style='background-image: url(images/administrator/images/message_24_off.png); background-attachment: scroll; background-color: transparent; width: 24px; height: 24px; 
							display: inline-block; position: absolute; background-position: 0px 0px; background-repeat: no-repeat no-repeat; ' 
							target='_blank' href='index.php?Pg=Menu&amp;SPg=01' title='Chat'></a>
	</div>
						
						</td></tr></tbody></table>
						
					</td>
					</tr>
					</tbody></table>
										
					
					
			</div>
					
					
					
		</td></tr>
		</tbody></table>";
}

function genSubtitle($SubTitle='Daftar Pengguna',$Menu='', $Icon='images/icon/daftar48.png', $IcoWidth=50){
	return 
		/*"<table class='TitlePage' width='100%'><tr>"
		"<th height='47' align='left' 
					style=\"background: url('$Icon') no-repeat scroll left center transparent; padding:0 0 0 $IcoWidth;\">
					$SubTitle</th>
				<th>$Menu</th>
		</tr></table>";*/
		"<table class='adminheading' width='100%'><tr>".
		"<th height='47' align='left' class='user'
			
		>
			$SubTitle
		</th>
		<th>$Menu</th>
		</tr></table>";
  				
}	


function dialog_createCaption($caption='',$other_content = ''){
	return "<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
			<div class='menuBar2' style='height:20' >
			<ul>
			<!--<li><a href='javascript:PengamanForm.Close()' title='Batal' class='btdel'></a></li>
			<li><a href='javascript:PengamanSimpan.Simpan()' title='Simpan' class='btcheck'></a></li>-->
			</ul>	
			<span style='cursor:default;position:relative;left:6;top:2;color:White;font-size:12;font-weight:bold' 
				>$caption</span>
		.	$other_content
			</div>
			</td></tr></table>";
}


function createEntryTgl3($Tgl, $elName, $disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', $fmName = 'adminForm',
	$tglShow=TRUE, $withBtClear = TRUE){
	//global $$elName, 
	//global $Ref;//= 'entryTgl';
	
	$NamaBulan  = array(
	array("01","Januari"), 
	array("02","Pebruari"),
	array("03","Maret"),
	array("04","April"),
	array("05","Mei"),
	array("06","Juni"),
	array("07","Juli"),
	array("08","Agustus"),
	array("09","September"),
	array("10","Oktober"),
	array("11","Nopember"),
	array("12","Desember")
	);
	
	$deftgl = date( 'Y-m-d' ) ;//'2010-05-05';
		
	$tgltmp= explode(' ',$Tgl);//explode(' ',$$elName); //hilangkan jam jika ada
	$stgl = $tgltmp[0]; 
	$tgl = explode('-',$stgl);
	if ($tgl[2]=='00'){ $tgl[2]='';	}
	if ($tgl[1]=='00'){ $tgl[1]='';	}
	if ($tgl[0]=='0000'){ $tgl[0]='';	}
		
	
	$dis='';
	if($disableEntry == '1'){
		$dis = 'disabled';
	}
	
	/*$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">'.$title.'
			<input '.$dis.' type="text" name="'.$elName.'_tgl" id="'.$elName.'_tgl" value="'.$tgl[2].'" size="2" maxlength="2" 
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\''.$elName.'\')"
				style="width:25">
		</div>' : '';*/
	$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">' . 
			$title .'&nbsp;'. 			
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'', 
				" $dis ".'  onchange="TglEntry_createtgl(\'' . $elName . '\')"').
		'</div>'
		: '';
	$btClear =  $withBtClear?
		'<div style="float:left;padding: 0 4 0 0">
				<input '.$dis.'  name="'.$elName.'_btClear" id="'.$elName.'_btClear" type="button" value="Clear" 
					onclick="TglEntry_cleartgl(\''.$elName.'\')">
					&nbsp;&nbsp<span style="color:red;">'.$ket.'</span>
		</div>' : '';
		
	if ($tgl[0]==''){
		$thn =(int)date('Y') ;
	}else{
		$thn = $tgl[0];//(int)date('Y') ;
	}
	$thnaw = $thn-10;
	$thnak = $thn+11;
	$opsi = "<option value=''>Tahun</option>";
	for ($i=$thnaw; $i<$thnak; $i++){
		$sel = $i == $tgl[0]? "selected='true'" :'';
		$opsi .= "<option $sel value='$i'>$i</option>";	
	}
	$entry_thn = 
		'<select id="'. $elName  .'_thn" 
			name="' . $elName . '"_thn"	'.
			$dis. 
			' onchange="TglEntry_createtgl(\'' . $elName . '\')"
		>'.
			$opsi.
		'</select>';
	
	$hsl = 
		'<div id="'.$elName.'_content" style="float:left;">'.
			$entrytgl.
			'<div style="float:left;padding: 0 4 0 0">
				'.cmb2D_v3($elName.'_bln', $tgl[1], $NamaBulan, $dis,'Pilih Bulan',
				'onchange="TglEntry_createtgl(\''.$elName.'\')"'  ) .'
			</div>
			<div style="float:left;padding: 0 4 0 0">
				<!--<input '.$dis.' type="text" name="'.$elName.'_thn" id="'.$elName.'_thn" value="'.$tgl[0].'" size="4" maxlength="4" 
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\''.$elName.'\')"
					style="width:35"	
				>-->'.
				$entry_thn.
			'</div>'.
			
			$btClear.		
			'<input $dis type="hidden" id='.$elName.' name='.$elName.' value="'.$Tgl.'" >
		</div>';
	return $hsl;	
}

function Entry($val,$name='entry1',$param='', $EntryType=0){
	$hsl ='';
	switch($EntryType){			
		//case 0: case '': $hsl=''; break;
		case 1: case 'hidden': $hsl = "<input type='hidden' id='$name' name='$name' value='$val' $param>" ;break;
		case 2: case 'text': $hsl = "<input type='text' id='$name' name='$name' value='$val' $param>" ;break;
		case 3: case 'date': $hsl = createEntryTgl3($val, $name, false) ;break;		
		case 4: case 'number': 
			$hsl = 
				"<input type='text' 
					onkeypress='return isNumberKey(event)' 				
					value='$val' 
					name='$name' 
					id='$name' 
					$param
				>";
			break;					
		case 5: case 'memo': 
			$hsl = "<textarea $param id='$name' name='$name' >".$val."</textarea>";
			break;
		default: 
			//$hsl='';
			$hsl = "<input type='text' id='$name' name='$name' value='$val' $param>" ;
			break;
	}
	return $hsl;
}

function centerPage($content){
	return '<table width=\'100%\' height=\'100%\'><tr><td align=\'center\'> '.$content.'</td></tr></table>';	
			
}

function createDialog($fmID='divdialog1',
	$Content='', 
	$ContentWidth=623, 
	$ContentHeight=358, 
	$caption='Dialog', $dlgCaptionContent='', 
	$menuContent='', $menuHeight=22, $FormName='', $params = NULL ){
	
	
	$paddingMenuRight = 8;
		$paddingMenuLeft = 8;
		$paddingMenuBottom = 9;
	$marginTop= 9;
		$marginBottom= 8;
		$marginLeft = 8;
		$marginRight = 8;
	$menudlg = "
			<div style='padding: 0 $paddingMenuRight $paddingMenuBottom $paddingMenuLeft;height:$menuHeight; '>
			<div style='float:right;'>
				$menuContent
			</div>
			</div>
			";	 	
	$captionHeight = 30;
		$dlgHeight = $captionHeight+$marginTop+$ContentHeight+$marginBottom+$menuHeight+$paddingMenuBottom;
		//$dlgWidth = 642;
		$dlgWidth = $ContentWidth+$marginLeft+$marginRight+2;
	
	if($params == NULL){
	
		//add menu
		
		$dlg = 	
			dialog_createCaption($caption, $dlgCaptionContent).
			"<div id='$fmID' style='margin:$marginTop $marginLeft $marginBottom $marginRight;
				overflow:auto;width:$ContentWidth;height:$ContentHeight; border:1px solid #E5E5E5;'
			>".				
			$Content.
			'</div>'.
			$menudlg;
		//add border style and dimensi
		$dlg = "<div id='div_border' style='width:$dlgWidth;height:$dlgHeight;
				background-color:white;
				border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; 			
				box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>
					$dlg
				</div>";
		//add form
		if($FormName !=''){
			//$dlg = form_it($FormName,$dlg);
		}
	
	}else{
		$menudlg = "
			<div style='padding: 8;height:$menuHeight; border-top: 2px solid #ddd;'>
			<div style='float:right;'>
				$menuContent
			</div>
			</div>
			";	
		$dlg = 	
			
			"<table style='width:100%;height:100%' >".
			"<tr height='10'><td>".dialog_createCaption($caption, $dlgCaptionContent)."</td></tr>".
			"<tr  height='*' valign='top'><td>
			<div style='overflow:auto;height:100%'>$Content</div></td></tr>".
			"<tr height='30'><td >$menudlg</td></tr>".
			"</table>";
			
			
		
			
		$dlg = "<div id='div_border' style='width:100%;height:100%;
				background-color:white;
				border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; 			
				box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>
					$dlg
				</div>";
				
		//if($FormName !=''){
		//	$dlg = form_it($FormName,$dlg);
	//	}
	}
	
	
	return $dlg;
}


function setHeaderXls($nmfile='Daftar Realisasi Penerimaan.xls'){
	header("Content-Type: application/force-download");
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' ); 
	header("Content-Transfer-Encoding: Binary");
	header('Content-disposition: attachment; filename="'.$nmfile.'"');	
}

function genRadioGrp($nmElem='cbx1', $valu='', 
	$data='', 
	$Params= "style='width:90;display:block;float:left;'",
	$paramradio='' ){
	//data -> hash array	
	$isi = $valu; //if($isi=='')unset($isi);
	$Input = '';
	//if ($Params=='')$Params= "style='width:90;display:block;float:left;'";
	foreach($data as $key=>$value){
	
		$Sel = isset($isi) && $isi==$key? " checked ": ""; 
		$Input .= "<div $Params ><INPUT ".$Sel." TYPE='RADIO' id='$nmElem' NAME='$nmElem' VALUE='$key' $paramradio> $value</div>";		
	
	}
	return $Input.$cek;
	 
}

function genTableRow($Koloms, $RowAtr='', $KolomClassStyle=''){
	$baris = '';
	if($Koloms[0][0] == "Y"){
		$baris.=$Koloms[0][1];
	}else{
		foreach ($Koloms as &$value) { 
		//if($value[1] !='')
			
		$baris .= "<td class='$KolomClassStyle'  {$value[0]}>$value[1]</td>"; 
		}
	}
		
	if (count($Koloms)>0){$baris ="<tr $RowAtr > $baris </tr>"; }
	return $baris;
}


function genFilterBar($Filters, $onClick, $withButton=TRUE, $TombolCaption='Tampilkan', $Style='FilterBar'){
	$Content=''; $i=0;
	while( $i < count($Filters) ){
		$border	= $i== count($Filters)-1 ? '' : "border-right:1px solid #E5E5E5;";		
		$Content.= "<td  align='left' style='padding:1 8 0 8; $border'>".
						$Filters[$i].
					"</td>";
		$i++;
	}
	//tombol
	if($withButton){
		$Content.= "<td  align='left' style='padding:1 8 0 8;'>
					<input type=button id='btTampil' value='$TombolCaption' 
						onclick=\"$onClick\">
				</td>";		
	}
		
	/*return  "
		<table class='$Style' width='100%' style='margin: 4 0 0 0' cellspacing='0' cellpadding='0' border='0'>
		<tr><td>
			<table cellspacing='0' cellpadding='0' border='0'>
			<tr valign='middle'>   						
				$Content				
			</tr>
			</table>
		</td><td width='*'>&nbsp</td></tr>		
		</table>";	*/
	return  "
		<!--<table class='$Style' width='100%' style='margin: 4 0 0 0' cellspacing='0' cellpadding='0' border='0'>
		<tr><td> -->
		<div class='$Style' >
			<table style='width:100%'><tr><td align=left>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tr valign='middle'>   						
				$Content				
			</tr>
			</table>
			</td></tr></table>
		</div>
		<!--</td><td width='*'>&nbsp</td>
		</tr>		
		</table>-->
		
		";	
}
function cmbArray($name='txtField',$value='',$arrList = '',$default='Pilih', $param='') { 
 	$isi = $value; 
	$Input = "<option value=''>$default</option>"; 
	for($i=0;$i<count($arrList);$i++) { 
		$Sel = $isi==$arrList[$i][0]?" selected ":""; 
		$Input .= "<option $Sel value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>"; 
	} 
	$Input  = "<select $param name='$name'  id='$name' >$Input</select>"; 
	return $Input; 
} 

//OTHER ************************
function genPanelIcon($Link="",$Image="save2.png",$Isi="Isinya",$hint='',$id="",$ReadOnly="",$Disabled=FALSE,$Rid="",$aparams='') { 
	global $Pg; $RidONLY = "";
	global $PATH_IMG; 
	//if(!Empty($ReadOnly)){$Link="#FORMENTRY";} 
	if ($Disabled) {
		$Link ='';
		$DisAbled = "disabled='true'";
	}
	$Ret = " <table cellpadding='0' cellspacing='0' border='0' id='toolbar'> 
			<tr valign='middle' align='center'> 
			<td class='border:none'> 
				<a$ReadOnly class='toolbar' id='$id' href='$Link' $DisAbled title='$hint' $aparams> 					
					<img src='".$PATH_IMG."images/administrator/images/$Image'  alt='button' name='save' 
					width='32' height='32' border='0' align='middle'  /> 
					<br>$Isi
				</a> 
			</td> 
			</tr> 
			</table> "; 
	return $Ret; 
}
function tambah_hari($fmTglAwal='', $hari){
	return date('Y-m-d', strtotime( date("Y-m-d", strtotime($fmTglAwal)) . " +$hari day" ));
}
function selisih_tgl($tgl1='', $tgl2=''){//yyyy-mm-dd
	$tgl = explode('-',$tgl1);
	$jd1 = GregorianToJD($tgl[1], $tgl[2], $tgl[0]);//m/d/y
	$tgl = explode('-',$tgl2);
	$jd2 = GregorianToJD($tgl[1], $tgl[2], $tgl[0]);
	
	return $jd1-$jd2;	
}
function genHidden($dat){
	$hidden = '';
	foreach($dat as $key => $value){
		$hidden .= "<input type='hidden' name='$key' id='$key' value='$value'>";
	}
	return $hidden;
}

function tbl_update($Tblname,$Fields, $Kondisi, $msg=''){//tes
	//Fields -> hash array (fieldname=>fieldvalue)
	$errmsg='';
	$klm = array(); 
	foreach($Fields as $key => $value){
		$klm[] = " $key = '$value' ";
	}
	$klmstr=join(',',$klm);
	if($klmstr !=''){
		$aqry = " update $Tblname set $klmstr $Kondisi ";
		$Simpan = mysql_query($aqry);
		if($Simpan==FALSE)$errmsg ='Gagal Update Data!';
	}else{
		$errmsg = 'Tidak Ada Data!';
	}	
	return $errmsg;//$aqry;
}

function tbl_insert($Tblname,$Fields, $Kondisi='', $msg=''){//tes
	//Fields -> hash array (fieldname=>fieldvalue)
	$errmsg='';
	$keys = array(); 
	$vals = array();
	foreach($Fields as $key => $value){
		$keys[]= $key;
		$vals[]= "'$value'";
	}
	//$fieldstr = print_r($Fields);
	$keystr=join(',',$keys);
	$valstr=join(',',$vals);
	if($keystr !=''){
		$aqry = " insert into $Tblname (".$keystr.") values(".$valstr.") $Kondisi ";
		$Simpan = mysql_query($aqry);
		if($Simpan==FALSE)$errmsg ='Gagal Insert Data!';//.mysql_error();//.$aqry.$keystr.$valstr;
	}else{
		$errmsg = 'Tidak Ada Data!';
	}	
	return $errmsg;//.$aqry;
}

function get_admin_akses($uid, $kodei){
	global $usr;
	
	$fvalue=array();	
	
	$aqry = "select * from admin_akses where uid = '$uid' and i='$kodei'";
	$qry = mysql_query($aqry);	
	if($isi = mysql_fetch_array($qry)){		
		//get modul akses ----------------------		
		for($i=0;$i< sizeof($usr->DaftarModulsLabel) ;$i++){
			$fname = 'modul'.genNumber(($i+1),2);//'modul01';
			$fvalue[] = $isi[$fname];
		}
	}
	//$fvalue[]=$uid;	$fvalue[]=$aqry;
	return $fvalue;
}

function cekNoTable($tblname, $fieldNo, $fieldTgl, $noValue, $tglValue){
	$cnt = 0;
	$aqry = "select count(*) as cnt from $tblname where $fieldNo='$noValue' and year($fieldTgl)='$tglValue'";
	$qry = mysql_query($aqry);
	if($isi = mysql_fetch_array($qry)){
		$cnt= $isi['cnt'];
	}
	return $cnt==0;
}

function genNoTable($tblname, $fieldNo, $fieldTgl){
	//$aqry = "select (max(coalesce($fieldNo))+1) as maxno from $tblname where year(fieldTgl) ";
	$aqry = "select max(coalesce($fieldNo,0)) as maxno from $tblname where year($fieldTgl) ";
	$qry = mysql_query($aqry);
	if($isi = mysql_fetch_array($qry)){
		$maxno=$isi['maxno'];
		if ($maxno=='') {
			$maxno=1;
		}else{
			$maxno++;
		}
		return genNumber( $maxno,5);
	}
}

function setcookie_pejabat(
	 $fmnm_pejabat, $fmjbt_pejabat, $fmnip_pejabat,
		$fmnm_petugas, $fmjbt_petugas, $fmnip_petugas)
{

	setcookie('fmnm_pejabat', $fmnm_pejabat);
	setcookie('fmjbt_pejabat', $fmjbt_pejabat);
	setcookie('fmnip_pejabat', $fmnip_pejabat);
	setcookie('fmnm_petugas', $fmnm_petugas);
	setcookie('fmjbt_petugas', $fmjbt_petugas);
	setcookie('fmnip_petugas', $fmnip_petugas);	
}
function getcookie_pejabat(){	
	global $HTTP_COOKIE_VARS;
		
	$fmnm_pejabat  =  $_COOKIE['fmnm_pejabat'];
	$fmjbt_pejabat =  $_COOKIE['fmjbt_pejabat'];
	$fmnip_pejabat =  $_COOKIE['fmnip_pejabat'];
	$fmnm_petugas  = $_COOKIE['fmnm_petugas'] ;
	$fmjbt_petugas =  $_COOKIE['fmjbt_petugas'];
	$fmnip_petugas =  $_COOKIE['fmnip_petugas'] ;		
	
	return array (
		'fmnm_pejabat'=>$fmnm_pejabat, 'fmjbt_pejabat'=>$fmjbt_pejabat, 'fmnip_pejabat'=>$fmnip_pejabat,
		'fmnm_petugas'=>$fmnm_petugas, 'fmjbt_petugas'=>$fmjbt_petugas, 'fmnip_petugas'=>$fmnip_petugas);
}

//app obj ==========================================================================
class AppCls{
	//global $Main;
	
	public $appTitle, $appCopyRight;
	public $HTMLStyle, $HTMLScript, $HTMLHead, $HTMLFoot;

	
	//constructor
	function AppCls($appTitle_='', $appCopyRight_=''){
		$this->appTitle = $appTitle_;
		$this->appCopyRight = $appCopyRight_;
	}	
	
	
	function genHTMLHead($OtherCSS='', $OtherScript='', $pathjs=''){
		global $Main;
		/*return
		"<head>".
		$Main->HTML_Title.
		$Main->HTML_Meta.
		$Main->HTML_Link.	
		$OtherCSS.
		//$Main->HTML_Script.
		"<script type='text/javascript' src='".$pathjs."js/base.js' language='JavaScript'></script>
		<script type='text/javascript' src='".$pathjs."js/jquery.js' language='JavaScript'></script>
		<script type='text/javascript' src='".$pathjs."js/ajaxc2.js' language='JavaScript' ></script>				
		<script type='text/javascript' src='".$pathjs."js/dialog.js' language='JavaScript' ></script>	
		<script type='text/javascript' src='".$pathjs."js/usr.js' language='JavaScript' ></script>".
		$OtherScript.
		"</head>";
		*/
		return
			"<head>
	<title>::ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah) dev</title>
	<meta name='format-detection' content='telephone=no'>
	<meta name='ROBOTS' content='NOINDEX, NOFOLLOW'>
	<link rel='shortcut icon' href='images/".$Main->HeadStyleico."' />
	<!--  
	<link rel='stylesheet' href='css/template_css.css' type='text/css' />
	<link rel='stylesheet' href='css/theme.css' type='text/css' />
	<link rel='stylesheet' href='dialog/dialog.css' type='text/css' />
	<link rel='stylesheet' href='lib/chatx/chatx.css' type='text/css' />
	<link rel='stylesheet' href='css/menu.css' type='text/css' />
	
	<script language='JavaScript' src='lib/js/JSCookMenu_mini.js' type='text/javascript'></script>
	<script language='JavaScript' src='lib/js/ThemeOffice/theme.js' type='text/javascript'></script>
	<script language='JavaScript' src='lib/js/joomla.javascript.js' type='text/javascript'></script>
	<script language='JavaScript' src='js/ajaxc2.js' type='text/javascript'></script>
	<script language='JavaScript' src='dialog/dialog.js' type='text/javascript'></script>
	<script language='JavaScript' src='js/base.js' type='text/javascript'></script>
	<script language='JavaScript' src='lib/chatx/chatx.js' type='text/javascript'></script>
	-->
	
		
	<link rel='stylesheet' href='css/menu.css' type='text/css'>
	<link rel='stylesheet' href='css/template_css.css' type='text/css'>
	<link rel='stylesheet' href='css/theme.css' type='text/css'>
	<link rel='stylesheet' href='dialog/dialog.css' type='text/css'>
	<link rel='stylesheet' href='lib/chatx/chatx.css' type='text/css'>
	
	<link rel='stylesheet' href='css/base.css' type='text/css'>
	<!--<link rel='stylesheet' href='css/sislog.css' type='text/css' />-->
	<!--<link rel='stylesheet' type='text/css' media='all' href='js/jscalendar-1.0/calendar-win2k-cold-1.css' title='win2k-cold-1' />-->
	$OtherCSS
	 
	
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
	<!--<script src='js/pindahtangan.js' type='text/javascript'></script>-->
	$OtherScript
	
	
	

	  <!-- calendar stylesheet -->
	  <link rel='stylesheet' type='text/css' media='all' href='js/jscalendar-1.0/calendar-win2k-cold-1.css' title='win2k-cold-1'>

	  <!-- main calendar program -->
	  <script type='text/javascript' src='js/jscalendar-1.0/calendar.js'></script>

	  <!-- language for the calendar -->
	  <script type='text/javascript' src='js/jscalendar-1.0/lang/calendar-id.js'></script>

	  <!-- the following script defines the Calendar.setup helper function, which makes
		   adding a calendar a matter of 1 or 2 lines of code. -->
	  <script type='text/javascript' src='js/jscalendar-1.0/calendar-setup.js'></script>
	  
	  <script type='text/javascript'>
	  	
	  
	  </script>

	
	<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
	<meta name='Generator' content='Joomla! Content Management System'>
	$Main->HeadStyleico

	</head>";
		
		
	}	
	function genPageFoot($WithMarquee = TRUE){
		global $PATH_IMG;
		$copyright = "Copyright &copy; 2011. Dinas Pendapatan Pemerintah Kota Cimahi. Jl. Rd. Demang Hardjakusumah - Kota Cimahi. All right reserved.";
		$marq = $WithMarquee? "<marquee scrollamount='3' >
						$copyright
					</marquee>": $copyright;		
		$align = $WithMarquee? '' : "align='center'";
		return 
			"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='text_title' height='29' align='center' background='".$PATH_IMG."images/index_03.jpg' class='text_title'>
			$marq</td></tr></table>";
	}
		
	function genAppLogo($caption = 'SISTEM INFORMASI MANAJEMEN <BR>RUMAH SAKIT JIWA', $LOGOAPP=''){
		global $PATH_IMG;
		/*return
		"<table width='970' cellspacing='0' cellpadding='0' border='0'>
          <tbody><tr>
            <td width='31'>&nbsp;</td>
            <td width='179'><img width='179' height='83' src='images/simpada.gif'></td>
            <td width='25'>&nbsp;</td>
            <td width='455'><img width='455' height='83' src='images/siatem-informasi-manajemen-pajak-daerah.jpg'></td>
            <td width='248'><img width='248' height='83' src='images/dinas-pendapatan.gif'></td>
            <td width='32'>&nbsp;</td>
          </tr>
        </tbody></table>";*/
		
		return"
		<table width='970' border='0' cellpadding='0' cellspacing='0'>
          <tr>
            <td width='31'>&nbsp;</td>
            <td width='179'><img src='".$PATH_IMG."images/simpada.gif' width='179' height='83'></td>
            <td width='25'>&nbsp;</td>
            <td width='455'><img src='".$PATH_IMG."images/siatem-informasi-manajemen-pajak-daerah.jpg' width='455' height='83'></td>
            <td width='248'><img src='".$PATH_IMG."images/dinas-pendapatan.gif' width='248' height='83'></td>
            <td width='32'>&nbsp;</td>
          </tr>
        </table>";
	}
	
}
$APP_TITLE = '.:SIMPADA - Payment Point:.';
$app = new AppCls( $APP_TITLE, $APP_COPYRIGHT);


// form obj =======================================================================
class FormObj{
	var $form_width = '600';
	var $form_height = '439';
	var $form_caption = "User";
	var $form_menu_bawah_height = 22;	
	var $form_fields = 
			array(
				'field1' => array( 'label'=>'label1', 'value'=>'value1', 'type'=>'text' ),
				'field2' => array( 'label'=>'label1', 'value'=>'value2', 'type'=>'text' )
			);
	var $form_fmST ;
	var $form_idplh ;
	var $form_menubawah ;
	var $row_params;
	function setForm_content_fields($kolom1_width= 100){
		$content = '';
		
		foreach ($this->form_fields as $key=>$field){
			if ($field['type'] == ''){
				$val = $field['value'];
			}else{
				$val = Entry($field['value'],$key,'',$field['type']);	
			}
			
			$content .= 
				"<tr $this->row_params>
					<td style='width:$kolom1_width'>".$field['label']."</td>
					<td style='width:10'>:</td>
					<td>". $val."</td>
				</tr>";
		}
		//$content = 
		//	"<tr><td style='width:100'>field</td><td style='width:10'>:</td><td>value</td></tr>";
		return $content;	
	}	
	function setForm_content(){
		$content = '';	
		$content = $this->setForm_content_fields();
			
		$content = 
			"<table style='width:100%' style=''><tr><td style='padding:4'>
				<table style='width:100%' >
				$content
				</table>
			</td></tr></table>";
		return $content;
	}	
}

// daftar obj =======================================================================
class DaftarObj{
	var $Prefix = 'Penetapan';
	var $SHOW_CEK = FALSE;
	var $elCurrPage="HalDefault";
	var $TblName = 'v3_penetapan'; //daftar
	var $TblName_Hapus = '';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('jml_ketetapan');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 10, 9,9);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	//var $KeyFields_Hapus = array('Id');
	//cetak ---------
	var $cetak_xls=FALSE ;
	var $Cetak_Judul;
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '20cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page
	var $ToolbarAtas_edit ;
	//form
	//var $form_name = 'Usr_form';
	var $form_width = '600';
	var $form_height = '439';
	var $form_caption = "User";
	var $form_menu_bawah_height = 22;	
	var $form_fields = 
			array(
				'field1' => array( 'label'=>'label1', 'value'=>'value1', 'type'=>'text', 'param'=>'' ),
				'field2' => array( 'label'=>'label1', 'value'=>'value2', 'type'=>'text' )
			);
	var $form_fmST ;
	var $form_idplh ;
	var $form_menubawah ;
	var $pemisahID = ' '; //pemisah utk multi id di checkbox
			/*//ex
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >
			<input type='hidden' id='idplh' value='' >
			<input type='hidden' id='fmST' value='0' >";	*/
	
//inisial =======================================
	function DaftarObj(){
		$this->form_menubawah =
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			//<input type='hidden' id='idplh' value='' >
			//<input type='hidden' id='fmST' value='0' >";	
	
		$this->ToolbarAtas_edit = 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Ubah", '')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", '')."</td>";
	
		/*$this->Cetak_Judul =		
			"<table width='100%' border=\"0\">
				<tr>
					<td align='right' colspan='4'>
						<span class='title2'>
							LAPORAN DAFTAR PENDATAAN
						</span>
					</td>
				</tr>
				<tr>
					<td align='right' colspan='4'>
						<span class='title1'>
							4.1.1.04.00 - PAJAK REKLAME
						</span>
					</td>
					<td colspan='4'><span class='title1'></span></td>
				</tr>
				<tr>
					<td><span class='title1'></span></td>
					<td colspan='4'><span class='title1'></span></td>
				</tr>
			</table>";*/
	
		/*$this->CetakHeader= 
			$header = 
			"<table style='width:100%' border=\"0\">
					<tr><td class=\"judulcetak\" align='center'>$JUDUL</td>	</tr>
				</table>
				<br>";*/
		
		
	
	}

//PROSES ========================================
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		/*if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from tagihan where ref_idpenetapan='".$id."'")
				) >0 )
			{ $errmsg = 'Gagal Hapus! SKPD Sudah ada di Tagihan!';}*/
		return $errmsg;
	}
	function Hapus_Data($id){//id -> multi id with space delimiter
		$KeyValue = explode($this->pemisahID,$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		//$Kondisi = 	"Id='".$id."'";
		
		$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi;
		$qry = mysql_query($aqry);
		if ($qry==FALSE){
			$errmsg = 'Gagal Hapus Data'. $aqry;
		}
		
		return $errmsg;
	}
	function Hapus_Data_After($id){//id -> multi id with space delimiter
		$errmsg = ''; $content=''; $cek='';
		//kondisi key -----------------
		$KeyValue = explode($this->pemisahID,$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		
		//action --------------------
		
		return $errmsg;
		//return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}
	function Hapus($ids){//array of id
		//$cid= $POST['cid'];
		$errmsg = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$errmsg = $this->Hapus_Validasi($ids[$i]);
			
			if($errmsg ==''){
				$errmsg = $this->Hapus_Data($ids[$i]);
				if ($errmsg=='') $errmsg = $this->Hapus_Data_After($ids[$i]);
				if ($errmsg != '') break;
				 				
			}else{
				break;
			}			
		}
		return $errmsg;
	} 
	function SimpanValidasi($fmST){
		$err = '';
		switch ($fmST){
			case 0 : { //baru				
				break;
			}
			case 1: {//edit
				break;
			}
		}
		return $err;
	}
	function simpan($fmST, $tblsimpan='', $fieldKeyStr = '', $fieldKeyValStr='', $fields = '', $fieldsval = '' ){
		$Simpan = FALSE; $errmsg ='';$content=''; $cek = '';
		switch ($fmST){
			case 0 : { //baru		
				$errmsg = $this->SimpanValidasi($fmST);
				if($errmsg == ''){	
					//clean fields insert -------------------					
					$arrfieldsval = explode(',',$fieldsval);
					for($i=0;$i<sizeof($arrfieldsval);$i++) $arrfieldupd[] = "'".$arrfieldsval[$i]."'";
					$fieldupd = join(',',$arrfieldupd);
					//insert tabel -----------------------------
					$aqry = "insert into $tblsimpan ($fields) values ($fieldupd) "; $cek .= $aqry;
					$Simpan = mysql_query($aqry);
					if ($Simpan == FALSE) $errmsg = 'Gagal Update Data !';//.$aqry;
				}	
				break;
			}
			case 1: { //edit
				//validasi edit()
				$errmsg = $this->SimpanValidasi($fmST);
				if($errmsg == ''){	
					//create kondisi key ----------------------
					$fid = array();
					if ($fieldKeyStr == '' ){
						$fid[] = 'Id'; //default
						$valId[] = $fieldKeyValStr;
					}else{
						$fid = explode(',',$fieldKeyStr);
						$valId = explode(',',$fieldKeyValStr);
					}
					$kondisi = '';
					for($i=0;$i<sizeof($fid);$i++) $kondisi .= $fid[$i]." = '".$valId[$i]."'";
					if ($kondisi != '') $kondisi = " where $kondisi";
					//generate fields update -------------------
					$arrfieldupd = array();
					$arrfields = explode(',',$fields);
					$arrfieldsval = explode(',',$fieldsval);
					for($i=0;$i<sizeof($arrfields);$i++) $arrfieldupd[] = $arrfields[$i]." = '".$arrfieldsval[$i]."'";
					$fieldupd = join(',',$arrfieldupd);
					if ($fieldupd != '') $fieldupd = " set $fieldupd";
					//update tabel -----------------------------
					$aqry = "update $tblsimpan $fieldupd $kondisi"; $cek .= $aqry;
					$Simpan = mysql_query($aqry);
					if ($Simpan == FALSE) $errmsg = 'Gagal Update Data !';//.$aqry;
				}
				break;
			}
			
		}
		return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}
	
//FORM ==========================================
	function setForm_content_fields(){
		$content = '';
		
		foreach ($this->form_fields as $key=>$field){
			if ($field['type'] == ''){
				$val = $field['value'];
			}else{
				$val = Entry($field['value'],$key,$field['param'],$field['type']);	
			}
			
			if($field['ttkDua']==''){ $ttkDua=':' ;}else { $ttkDua=$field['ttkDua']; }			
			if($field['valign'] ==''){	$valign = 'top';}else{	$valign = $field['valign'];	}
			
			$content .= 
				"<tr valign='$valign'>
					<td style='width:".$field['labelWidth']."'>".$field['label']."</td>
					<td style='width:10'>$ttkDua</td>
					<td>". $val."</td>
				</tr>";
		}
		//$content = 
		//	"<tr><td style='width:100'>field</td><td style='width:10'>:</td><td>value</td></tr>";
		return $content;	
	}	
	function setForm_content(){
		$content = '';	
		$content = $this->setForm_content_fields();
			
		$content = 
			"<table style='width:100%' style=''><tr><td style='padding:4'>
				<table style='width:100%' >
				$content
				</table>
			</td></tr></table>";
		return $content;
	}	
	/*function setForm_menubawah_content(){
		return
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >
			<input type='hidden' id='idplh' value='' >
			<input type='hidden' id='fmST' value='0' >";
	}*/
	function genForm_menubawah_add($content, $insert=FALSE){
		if($insert){
			$this->form_menubawah = $content.$this->form_menubawah;
		}else{
			$this->form_menubawah .= $content;	
		}		
	}
	function genForm(){	
		$form_name = $this->Prefix.'_form';	
		$form = 
			centerPage(
				"<form name='$form_name' id='$form_name' method='post' action='' >".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height).
				"</form>"
			);
		return $form;
	}	
//DAFTAR ========================================
	function getDaftar_limit($Mode=1){
		global $Main;
		$Limit=''; $NoAwal = 0;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->elCurrPage,1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		return array('Limit'=>$Limit, 'NoAwal' => $NoAwal);
	}
	function getDaftarOpsi($Mode=1){
		global $Main;
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0; $cek ='';
		$arrKondisi= array();
		
		//Kondisi		
		$fmKODEI = $_POST['fmOp'];
		if (!empty($fmKODEI)) $arrKondisi[] = " i= '".$fmKODEI."'";
		$fmStatusHit = $_POST['fmStatusHit'];		
		switch($fmStatusHit){
			case '1': $arrKondisi[] =  " ref_idhitung !='' "; break;
			case '2': $arrKondisi[] =  " (ref_idhitung ='' or  ref_idhitung is null)"; break;
		}					
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){
			case '1': $arrKondisi[] = " no_sptpd like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " nama_wp like '%$fmPILCARIVALUE%'"; break;
			case '3': $arrKondisi[] = " npwpd like '%$fmPILCARIVALUE%'"; break;
			case '4': $arrKondisi[] = " nama_op like '%$fmPILCARIVALUE%'"; break;
			case '5': $arrKondisi[] = " no_op like '%$fmPILCARIVALUE%'"; break;
			case '6': $arrKondisi[] = " lpad(ref_idhitung,5,'0') like '%$fmPILCARIVALUE%'"; break;
		}
		switch($fmSTATUS){
			case '1': $arrKondisi[] = " status_batal <> 3 "; break;
			case '2': $arrKondisi[] = " status_batal = 3 "; break;			
		}	
		
		
		
		//order -------------------------
		$fmDESC1 = $_POST['fmDESC1'];
		$AscDsc1 = $fmDESC1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['fmORDER1'];
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " no_sptpd $AscDsc1 "; break;
			case '2': $OrderArr[] =  " nama_wp $AscDsc1 "; break;
			case '3': $OrderArr[] =  " npwpd $AscDsc1 "; break;
			case '4': $OrderArr[] =  " nama_op $AscDsc1 "; break;
			case '5': $OrderArr[] =  " no_op $AscDsc1 "; break;
			case '6': $OrderArr[] =  " tgl_sptpd $AscDsc1 "; break;
			case '7': $OrderArr[] =  " ref_idhitung $AscDsc1 "; break;
		}
			
		/*
		//limit --------------------------------------
		$HalDefault=cekPOST($this->elCurrPage,1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		*/
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		$Kondisi = join(' and ',$arrKondisi); 
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal,'cek'=>$cek);
	
	}
	function genDaftarOpsi(){
		global $Main;
		$arrCari = array(
			array('1','No. SPTPD'),
			array('2','Nama Wajib Pajak'),
			array('3','NPWPD'),
			array('4','Nama Obyek Pajak'),
			array('5','NOPD'),
			array('6','No. Nota Hitung')
		);
		$arrOrder = array(
			array('1','No. SPTPD'),
			array('2','Nama Wajib Pajak'),
			array('3','NPWPD'),
			array('4','Nama Obyek Pajak'),
			array('5','NOPD'),
			array('6','Tgl. SPTPD'),
			array('7','No. Nota Hitung')
		);
		$TampilOpt =	
			genFilterBar(
				array(
					cmbArray('fmPILCARI',$fmPILCARI,$arrCari,'Cari Data','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				, $this->Prefix.".refreshList(true)",TRUE, 'Cari').
			genFilterBar(
				array(			
					//'Tampilkan : '.
					//'Nota Hitung &nbsp;&nbsp;'.genComboBox2('fmStatusHit',$fmStatusHit,$Main->ArAda,'','Pilih Semua', ''),
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--','').
					"<input type='checkbox' id='fmDESC1' name='fmDESC1' value='1'>Menurun"
				)
				, $this->Prefix.".refreshList(true)");
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	function setKolomHeader($Mode=1, $Checkbox=''){
		$headerTable =
			"<thead>
				<tr>
					$Checkbox
					<th class='th01' width='30' rowspan=2>No.</th>									
					<th class='th01' width='' rowspan=2>No. SKPD/<br>No. SPTPD/<br>No. Hitung</th>
					<th class='th01' width='' rowspan=2>Tgl. Terbit</th>				
					<th class='th02' width='' colspan=2>Objek Pajak</th>
					<th class='th02' width='' colspan=2>Wajib Pajak</th>
					<th class='th01' rowspan=2>Jenis Pajak</th>
					<th class='th01' rowspan=2>Masa Pajak</th>
					<th class='th01' rowspan=2>Jumlah Ketetapan</th>
					
				</tr>	
				<tr>
				
				<th class='th01' width='100'>NOPD/Nama OP</th>				
				<th class='th01' width=''>Alamat OP</th>
				<th class='th01' width='100'>NPWPD/Nama WP</th>				
				<th class='th01' width=''>Alamat WP</th>							
				
				
				
				</tr>				
			</thead>";
		return $headerTable;
	}
	function genDaftarHeader($Mode=1){
		//mode :1.;ist, 2.cetak hal, 3. cetak semua
		global $Main;
		$rowspan_cbx = $this->checkbox_rowspan >1 ? "rowspan='$this->checkbox_rowspan'":'';
		$Checkbox = $Mode==1? 
			"<th class='th01' width='10' $rowspan_cbx>
					<input type='checkbox' name='".$this->Prefix."_toggle' id='".$this->Prefix."_toggle' value='' 
						onClick=\"checkAll3($Main->PagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');\" />
			</th>" : '';		
		$headerTable = $this->setKolomHeader($Mode, $Checkbox);
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		$Koloms = array();
		
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array("align='center'  ", $TampilCheckBox);
		return $Koloms;
	}
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){
		$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//return mysql_query($aqry);
		return $aqry;
	}
	function setDaftar_after_getrow($list_row, $isi){
		return $list_row;
	}
	function setDaftar_before_getrow($no, $isi, $Mode, $TampilCheckBox,
			$RowAtr, $KolomClassStyle)
	{
		$ListData ='';
		/*$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);			
		$list_row = genTableRow($Koloms, 
						$RowAtr." valign='top' id='$cb' value='".$isi['Id']."'",$ColStyle);					
		$ListData = $this->setDaftar_after_getrow($list_row, $isi);*/			
		return array ('ListData'=>$ListData, 'no'=>$no);
	}
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		/*require $this-> :
			MaxFlush, TblStyle,  ColStyle, KeyFields, Prefix, FieldSum, SumValue,
			genDaftarHeader(), setDaftar_query(), setDaftar_before_getrow(), 
			setKolomData(), setDaftar_after_getrow(), genSumHal(), genRowSum(),
		*/
		$cek = '';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class=$TblStyle border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry;
		$qry = mysql_query($aqry);
		while ( $isi=mysql_fetch_array($qry)){
			$isi = array_map('utf8_encode', $isi);
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join($this->pemisahID,$KeyValue);
			$TampilCheckBox = //$Cetak? '' : 
				"<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
			
			
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
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);			
			$list_row = genTableRow(
				$Koloms, 
				$rowatr_,
				$ColStyle
			);		
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi);
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
		}
		
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		
		$ContentSum = $this->genRowSum($ColStyle, $Mode, $SumHal['sum']);
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
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>";
					
		//return $ListData;
		return array('list'=>$ListData, 'cek'=>$cek);
	}
	
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		if (sizeof($this->FieldSum)>0){
			$TampilTotalHalRp = number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total per Halaman</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total</td>": '';
			$Kanan2 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;
			$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total."</div></td>
					$Kanan2
				</tr>" ;
				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		}
		return $ContentTotalHal.$ContentTotal;
	}
	
	
	function setDaftar_hal($jmlData){
		global $Main;
		return 
			"<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
			<tr><td align=center style='padding:4'>".
				Halaman2b(
					$jmlData,
					$Main->PagePerHal,
					$this->elCurrPage,
					cekPOST($this->elCurrPage),
					5, 
					$this->Prefix.'.gotoHalaman').
			"</td></tr></table>";
	}
	function genSumHal($Kondisi){
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		
		$fsum_ = array();
		$fsum_[] = "count(*) as cnt";
		//$i=0;
		foreach($this->FieldSum as &$value){
			$fsum_[] = "sum($value) as sum_$value";
			//$i++; 
		}
		$fsum = join(',',$fsum_);
		
		
		//$aqry = "select count(*) as cnt,  sum(jml_terima) as totterima  from $this->TblName $Kondisi "; //echo $aqry;
		$aqry = "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		$qry = mysql_query($aqry);
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['cnt'];			
			/*$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,$this->elCurrPage,cekPOST('HalDefault'),5, $this->Prefix.'.gotoHalaman').
				"</td></tr></table>";
			*/
			$Hal = $this->setDaftar_hal($jmlData);
			
				
			//$jmlTotal= $isi['totterima'];
			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = number_format($SumArr[0], 2, ',' ,'.');
			
			
		}
				
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum);
	}
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		return
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='$this->elCurrPage' name='$this->elCurrPage' value='1'>".
			"</div>";
	}
	
	/*
	function genSumHal2($Kondisi){
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$jmlData = 0;
		$jmlTotal = 0;
		
		$aqry = "select count(*) as sum1, sum(jml_ketetapan) as sum2 from $this->TblName $Kondisi "; //echo $aqry;
		$qry = mysql_query($aqry);
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['sum1'];
			$jmlTotal= $isi['sum2'];
			$Sum = number_format($jmlTotal, 2, ',' ,'.');
			$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,"HalDefault",cekPOST('HalDefault'),5,'Penetapan.gotoHalaman').
				"</td></tr></table>";
		}
				
		return array('sum'=>$Sum, 'hal'=>$Hal);
	}*/
//ajx proses ===========================================================	
	function set_ajxproses_cetak_hal(){
		$this->Cetak_Mode=2;												
		$this->genCetak();
	}
	function set_ajxproses_cetak_all(){
		$this->Cetak_Mode=3;	
		$this->genCetak();	
	}
	function set_ajxproses_other($idprs, $Opt){
		$ErrNo=0; $ErrMsg = ''; $content='tes'; $json=FALSE;
		//---------
		switch ($idprs){
			case 'formbaru':{
				$json = TRUE;	//$ErrMsg = 'tes';
				$content = $this->genForm();//$content = 'content';
				break;
			}
		}
		return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json );
	}
	function set_ajxproses($idprs, $Opt){
		global $Main;
		$ErrNo=0; $ErrMsg = ''; $content=''; $json=FALSE;	
		
			switch ($idprs){
				case 'list':{
					$Opsi = $this->getDaftarOpsi();	//$content = 'tes'.
					$list = $this->genDaftar( 
						$Opsi['Kondisi'], $Opsi['Order'], 
						$Opsi['Limit'], $Opsi['NoAwal'], 1 
					);
					$content=	
						array('list'=>$list['list']
							
						);
					$cek .= $Opsi['Kondisi'];
					$cek .= $list['cek'];
					$json= true;
					break;
				}
				case 'sumhal':{
					$Opsi = $this->getDaftarOpsi();
					$content = $this->genSumHal($Opsi['Kondisi']);
					$json= true;
					break;
				}
				case 'cetak_hal': {					
					$this->set_ajxproses_cetak_hal();
					break;
				}
				case 'cetak_all':{
					$this->set_ajxproses_cetak_all();										
					break;
				}
				case 'hapus':{
					$cbid= $_POST[$this->Prefix.'_cb'];				
					$ErrMsg=//'hapus '.$cbstr;
						$this->Hapus($cbid);
					$json=TRUE;	
					break;
				}
				
				default :{
					//$TampilOpt = $Perhitungan->genDaftarOpsi();				
					$other = $this->set_ajxproses_other($idprs,$Opt);		
					$ErrNo = $other['ErrNo'];
					$ErrMsg = $other['ErrMsg'];
					$cek = $other['cek'];
					$content = $other['content'];
					$json = $other['json'];					
					break;	
				}
			}
		
		
		
		if($Main->SHOW_CEK == FALSE) $cek ='';
		return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json, 'cek'=>$cek );
	}
	function ajxproses(){
		$ErrMsg = '';
		if ($_GET['idprs'] != ''){
			$idprs = $_GET['idprs']; //echo ',idprs='.$idprs;
		}else{ //json
			$optstring = stripslashes($_GET['opt']);
			$Opt = json_decode($optstring); //$page = json_encode(",cek="+$Opt->idprs);
			$idprs = $Opt->daftarProses[$Opt->idprs];
		}
		
		if ($idprs =='') {
			
			echo $this->setPage();
			//$this->setPage();
		}else{
			//echo 'tes';
			$get = $this->set_ajxproses($idprs, $Opt);
			
			$ErrNo = $get['ErrNo'];
			$ErrMsg = $get['ErrMsg'];
			$content = $get['content'];
			$cek = $get['cek'];
			$json = $get['json'];
			if($json){ //json---	
				$pageArr = array(
							'idprs'=>$Opt->idprs,						 
							'daftarProses'=>$Opt->daftarProses , 
							'cek'=>$cek, 
							'ErrNo'=>$ErrNo, 
							'ErrMsg'=>$ErrMsg, 
							'content'=> $content
							
						);
				$page = json_encode($pageArr);	
				$page;
			}
			echo $page;
		}
		
	}
	
//PAGE ===========================================	
	//function setPage_Header_Icon(){	return $IconPage;}
	//function setPage_Header_Title(){return $TitlePage; }
	function setPage_Header($IconPage='', $TitlePage=''){
		//global $app, $Main;
		//if ($IconPage =='') $IconPage = $this->setPage_Header_Icon();
		//if ($TitlePage =='') $TitlePage = $this->setPage_Header_Title();
		return createHeaderPage($IconPage, $TitlePage);
	}
	function setPage_OtherStyle(){
		/*return "<link type='text/css' href='css/pay.css' rel='stylesheet'>					
						<link type='text/css' href='css/menu_pay.css' rel='stylesheet'>";
		*/
		return '';
	} 
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
		return "<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
						<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".
						$scriptload;
	}
	function setPage_IconPage(){
		return 'images/icon/daftar32.png';
	}
	function setPage_TitlePage(){
		$title='Title';
		return  $title;
	}
	function setPage_OtherHeaderPage(){
		return '';
	}
	function setPage_FormName(){
		return 'adminForm';
	}
	function setPage_hidden(){
		return genHidden(array('fmOp'=> genNumber($_GET['Op'],2) ));
	}
	function setPage_TitleDaftar(){
		return 'Daftar Pendataan';
	}	
	function ToolbarAtasEdit_Add($label='',$ico='',$link='',$hint='',$insert=FALSE){
		if($insert){
			$this->ToolbarAtas_edit = 
				"<td>".genPanelIcon($link,$ico,$label,$hint)."</td>".$this->ToolbarAtas_edit;
		}else{
			$this->ToolbarAtas_edit .= 
				"<td>".genPanelIcon($link,$ico,$label,$hint)."</td>";	
		}
		
		return $ToolbarAtas_edit;
	}
	function setPage_ToolbarAtasView(){
		return //"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHit()","print_f2.png","Cetak", 'Cetak Nota Hitung')."</td>
					"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
					<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>";
					
	}
	function setPage_SubTitle_menu(){
		
		$SubTitle_menu = 
					"<div style='float:right;'>					
					<div><div></div><div>$scriptMenu</div></div>
					<table ><tr>".
					$this->ToolbarAtas_edit.//$this->SetPage_ToolbarAtasEdit().
					$this->SetPage_ToolbarAtasView().
					"</tr>
					</table>			
					</div>";
		return $SubTitle_menu;
	}
	function setPage_OtherInForm(){}
	function setPage_OtherContentPage(){}
	function setPage_OtherFooterPage(){}
	function setPage(){		
		return $this->genPage( 
					$this->setPage_OtherStyle(), 
					$this->setPage_OtherScript(), 
					$this->setPage_IconPage(), 
					$this->setPage_TitlePage(),	
					$this->setPage_OtherHeaderPage(), 
					$this->setPage_FormName(), 
					$this->setPage_hidden(), 
					$this->setPage_TitleDaftar(), 
					$this->setPage_SubTitle_menu(), 
					$this->setPage_OtherInForm(),
					$this->setPage_OtherContentPage(), 
					$this->setPage_OtherFooterPage() 
				);
	}	
	function genPage( $OtherStyle , $OtherScript, 
		$IconPage, $TitlePage,	$OtherHeaderPage, 
		$FormName, $hidden, $TitleDaftar, $SubTitle_menu, $OtherInForm,
		$OtherContentPage, $OtherFooterPage )
	{
		global $app, $Main; 
		$formbarcode =  "<span style='color:red'>BARCODE</span>".
							"<input type='TEXT' value='' 
								id='barcode_input' name='barcode_input'
								style='font-size:24;width: 369px;' 
								size='28' maxlength='28'
								
							><span id='barcode_msg' name='barcode_msg' ></span> ";
		return
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".
		"<html>".
			$app->genHTMLHead($OtherStyle, 	$OtherScript).	
			"<body >".				
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
				//header page -------------------		
				"<tr height='30'><td valign='top'>".						
					$this->setPage_Header($IconPage, $TitlePage).
					"<div id='header'></div>".
				"</td></tr>".
				$OtherHeaderPage.
				//Content ------------------------			
				"<tr height='*' valign='top'> <td style='padding:0 8 0 8'>".
					
					"<form name='$FormName' id='$FormName' method='post' action=''>".
						//Form ------------------
						$hidden.					
						genSubTitle($TitleDaftar,$SubTitle_menu).
						
						"<!--wil skpd-->
						<table width=\"100%\" class=\"adminform\">	<tr>		
						<td width=\"100%\" valign=\"top\">".			
							WilSKPD_ajx3($this->Prefix).
						"</td>
						<td >" . 
						$formbarcode. //onkeyup='inputBarcode(this)'
								
								//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>".
							
						"</td>
						</tr></table>".
						
						$this->genDaftarInitial().
						$OtherInForm.
					"</form>".
				"</td></tr>".
				$OtherContentPage.				
				//Footer ------------------------
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>				
			</body>
		</html>"; 
	}
	
//CETAK DAFTAR ===========================================
	/*function setCetak_Mode($Mode){ return $Mode;}	
	function setCetak_OtherHTMLHead($other=''){return $other;}
	function setCetak_WIDTH($width=30){return $width;}
	function setCetak_JUDUL($judul='Daftar'){return $judul;}
	function setCetak($Mode){
		return $this->genCetak(
					$this->setCetak_Mode($Mode), 
					$this->setCetak_OtherHTMLHead(), 
					$this->setCetak_WIDTH(), 
					$this->setCetak_JUDUL()
				);
	}*/
	function setCetak_Header(){
		global $Main;
		/*return  
			"<table style='width:100%' border=\"0\"><tr>
			<td >".				
				$Main->KopLogo.
			"</td>		
			<td >".
				$this->Cetak_Judul.
			"</td>
			</tr></table>";
		*/	
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD()."</td>
				</tr>
			</table><br>";
	}
	//function genCetak($Mode=2, $OtherHTMLHead='', $WIDTH=30, $JUDUL=''){
	function setCetak_footer(){
		return "<br>".	
				PrintTTD($this->Cetak_WIDTH);
	}
	
	function genCetak(){
		global $Main;
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
		$css = $this->cetak_xls	? 
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
			"<body>
			<form name='adminForm' id='adminForm' method='post' action=''>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".		
				$this->setCetak_Header().//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
		$daftar = $this->genDaftar(
			$Opsi['Kondisi'], 
			$Opsi['Order'], 
			$Opsi['Limit'], 
			$Opsi['NoAwal'], 
			$this->Cetak_Mode
		);								
		echo $daftar['list'];
		
		echo	"</div>	".			
				$this->setCetak_footer().
			"</td></tr>
			</table>
			</form>		
			</body>	
			</html>";
	}
}




class DaftarObj2{
	var $Prefix = 'pbb_penetapan_daftar'; //jsname
	var $SHOW_CEK = FALSE;
	var $withform = TRUE;
	//daftar -------------------
	//var $elCurrPage="HalDefault";
	var $TblName = 'pbb_entryawal'; //daftar
	var $TblName_Hapus = 'pbb_entryawal';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id'); //daftar/hapus
	var $FieldSum = array('luas_tanah');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 10, 9,9);//berdasar mode
	var $FieldSum_Cp2 = array( 0, 0,0);	
	var $checkbox_rowspan = 1;
	var $totalCol = 11; //total kolom daftar
	var $fieldSum_lokasi = array( 10);  //lokasi sumary di kolom ke	
	var $withSumAll = TRUE;
	var $withSumHal = TRUE;
	var $WITH_HAL = TRUE;
	var $totalhalstr = '<b>Total per Halaman';
	var $totalAllStr = '<b>Total';
	//var $KeyFields_Hapus = array('Id');
	//cetak --------------------
	var $cetak_xls=FALSE ;
	var $fileNameExcel='tes.xls';
	var $Cetak_Judul;
	//var $Cetak_Header;
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;//="<link type='text/css' href='css/template_css.css' rel='stylesheet'>";
	//page ----------------------
	//var $Prefix='page'; //js object pake ini
	var $ToolbarAtas_edit ='';
	var $PageTitle = 'PBB - Penetapan';
	var $PageIcon = 'images/icon/daftar32.png';
	var $pagePerHal= '';
	var $FormName = 'adminForm';	
	var $ico_width = 20;
	var $ico_height = 30;
	//form ---------------------
	//var $form_name = 'Usr_form';
	var $tblFormStyle = 'tblform';
	var $formLabelWidth = 100;
	var $form_width = '600';
	var $form_height = '439';
	var $form_caption = "User";
	var $form_menu_bawah_height = 22;	
	var $form_fields = 
			array(
				'field1' => array( 'label'=>'label1', 'value'=>'value1', 'type'=>'text', 'param'=>'' ),
				'field2' => array( 'label'=>'label1', 'value'=>'value2', 'type'=>'text' )
			);
	var $form_fmST ;
	var $form_idplh ;
	var $form_menubawah ;
		/*//sample
		"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >
		"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >
		<input type='hidden' id='idplh' value='' >
		<input type='hidden' id='fmST' value='0' >";	*/
	var $multiselect = FALSE;
	var $pemisahID = ' '; //pemisah utk multi id di checkbox
	//var $ms_contain = '';
	
	//user akses
	var $noModul = 1;
	
	
	//multi select ==========================
	function getNmMsCbxPilihFilter(){
		return $this->Prefix.'_cbxpilihfilter';
	}
	function getNmMsJmlPilih(){
		return $this->Prefix.'_ms_jmlpilih';
	}
	function get_multiselect_contain(){
		global $HTTP_COOKIE_VARS;
		
		$cbx = $_REQUEST[$this->getNmMsCbxPilihFilter()];
		$checked = $cbx==1 ? "checked='true'" : '';
		$id_el =  $this->Prefix. '_pilihan_msg';
		$jmlpilih =0;// $_REQUEST[$this->getNmMsJmlPilih()];
		
		$idpilihan = $HTTP_COOKIE_VARS[$this->Prefix.'_DaftarPilih'];
		if($idpilihan != ''){
			$arrid = array();
			$arrid = explode(',',$idpilihan);
			$jmlpilih = count($arrid);
		}
		
		//if ($jmlpilih == '' ) $jmlpilih = 0;
		return "<div id='$id_el' name='$id_el' style='float:right;padding: 4 4 4 8;'>".
				"<div style='float:left;padding:2'>Terpilih: </div>".
				"<div id='".$this->getNmMsJmlPilih()."' name='".$this->getNmMsJmlPilih()."' style='float:left;padding:2'>$jmlpilih</div>".
				"<div style='float:left'>".
					"<input type='checkbox' id='".$this->getNmMsCbxPilihFilter()."' name='".$this->getNmMsCbxPilihFilter()."' value='1' ".
						" onclick='".$this->Prefix.".cbxFilterKlik(this)' $checked>".
				"</div>".
			"</div>";
	}
	
//inisial =======================================
	function DaftarObj2(){
		$this->form_menubawah =
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			//<input type='hidden' id='idplh' value='' >
			//<input type='hidden' id='fmST' value='0' >";	
	/*
		$this->ToolbarAtas_edit = 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Ubah", '')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", '')."</td>";
	
		/*$this->Cetak_Judul =		
			"<table width='100%' border=\"0\">
				<tr>
					<td align='right' colspan='4'>
						<span class='title2'>
							LAPORAN DAFTAR PENDATAAN
						</span>
					</td>
				</tr>
				<tr>
					<td align='right' colspan='4'>
						<span class='title1'>
							4.1.1.04.00 - PAJAK REKLAME
						</span>
					</td>
					<td colspan='4'><span class='title1'></span></td>
				</tr>
				<tr>
					<td><span class='title1'></span></td>
					<td colspan='4'><span class='title1'></span></td>
				</tr>
			</table>";*/
	
		/*$this->CetakHeader= 
			$header = 
			"<table style='width:100%' border=\"0\">
					<tr><td class=\"judulcetak\" align='center'>$JUDUL</td>	</tr>
				</table>
				<br>";*/
		
		
	
	}

//PROSES ========================================
	function Hapus_Validasi($id){
		$errmsg ='';		
		/*if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from tagihan where ref_idpenetapan='".$id."'")
				) >0 )
			{ $errmsg = 'Gagal Hapus! SKPD Sudah ada di Tagihan!';}*/
		return $errmsg;
	}
	function Hapus_Data($id){//id -> multi id with space delimiter
		$err = ''; $cek='';
		//$KeyValue = explode(' ',$id);
		$KeyValue = explode($this->pemisahID,$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		//$Kondisi = 	"Id='".$id."'";
		
		$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
		$qry = mysql_query($aqry);
		if ($qry==FALSE){
			$err = 'Gagal Hapus Data';
		}
		
		return array('err'=>$err,'cek'=>$cek);
	}
	function Hapus_Data_After($id){
		$err = ''; $content=''; $cek='';
		
		return array('err'=>$err, 'content'=>$content, 'cek'=>$cek);
	}
	function Hapus($ids){
		$err=''; $cek=''; $content = '';
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$err .= $this->Hapus_Validasi($ids[$i]);
			
			if($err ==''){
				$get = $this->Hapus_Data($ids[$i]);
				$err .= $get['err'];
				$cek .= $get['cek'];
				$content .= $get['content'];
				if ($errmsg=='') {
					$after = $this->Hapus_Data_After($ids[$i]);
					$err .=$after['err'];
					$cek .=$after['cek'];
					$content .= $after['content'];
				}
				if ($err != '') break;
				 				
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek, 'content' => $content);
	} 
	function SimpanValidasi($fmST){
		$err = '';
		switch ($fmST){
			case 0 : { //baru				
				break;
			}
			case 1: {//edit
				break;
			}
		}
		return $err;
	}
	function simpan($fmST, $tblsimpan='', $fieldKey='', $fieldKeyVal='', $fields = '', $fieldsval = '' ){	
		/*$get = $this->simpan($fmST, 'barang_tidak_tercatat', 'Id', $id,					
					"a1,a,b",
					array('a1'=>'11','a'=>'10','b'=>'00')
				);
		*/
		$Simpan = FALSE; $errmsg ='';$content=''; $cek='';
		
		$cek .= "fmst=$fmST ";
		switch ($fmST){
			case 0 : { //baru	
				//generate fields update -------------------
				$aqry  = "insert into $tblsimpan (".implode(',',array_keys($fieldsval)).
					") values (".implode(',',array_values($fieldsval)).") ;";
				$cek .= $aqry;
				$Simpan = mysql_query($aqry);
				if ($Simpan == FALSE) $errmsg = 'Gagal Simpan Data !';//.$aqry;		
				break;
			}
			/*case 1: { //edit
				//validasi edit()
				$errmsg = $this->SimpanValidasi($fmST);
				if($errmsg == ''){	
					//create kondisi key ----------------------
					$fid = array();
					if ($fieldKey == '' ){
						$fid[] = 'Id'; //default
						$valId[] = $fieldKeyVal;
					}else{
						$fid = $fieldKey;//explode(',',$fieldKeyStr);
						$valId = $fieldKeyVal;//explode(',',$fieldKeyValStr);
					}
					$kondisi = '';
					for($i=0;$i<sizeof($fid);$i++) $kondisi .= $fid[$i]." = '".$valId[$i]."'";
					if ($kondisi != '') $kondisi = " where $kondisi";
					//generate fields update -------------------
					$arrfieldupd = array();
					$arrfields = $fields;//explode(',',$fields);
					$arrfieldsval = $fieldsval;// explode(',',$fieldsval);
					for($i=0;$i<sizeof($arrfields);$i++) $arrfieldupd[] = $arrfields[$i]." = '".$arrfieldsval[$i]."'";					
					$fieldupd = join(',',$arrfieldupd);
					if ($fieldupd != '') $fieldupd = " set $fieldupd";
					//update tabel -----------------------------
					$aqry  = "update $tblsimpan $fieldupd $kondisi"; $cek.=$aqry;
					$Simpan = mysql_query($aqry);
					if ($Simpan == FALSE) $errmsg = 'Gagal Update Data !';//.$aqry;
				}
				break;
			}*/
			case 1: { //edit2
				//validasi edit()
				$errmsg = $this->SimpanValidasi($fmST);
				if($errmsg == ''){	
					//create kondisi key ----------------------
					$fid = array();
					if ($fieldKey == '' ){
						$fid[] = 'Id'; //default
						$valId[] = $fieldKeyVal;
					}else{
						$fid = $fieldKey;//explode(',',$fieldKeyStr);
						$valId = $fieldKeyVal;//explode(',',$fieldKeyValStr);
					}
					$kondisi = '';
					for($i=0;$i<sizeof($fid);$i++) $kondisi .= $fid[$i]." = '".$valId[$i]."'";
					if ($kondisi != '') $kondisi = " where $kondisi";
					//generate fields update -------------------
					$arrfieldupd = array();
					$arrfields = $fields;//explode(',',$fields);
					$arrfieldsval = $fieldsval;// explode(',',$fieldsval);
					//for($i=0;$i<sizeof($arrfields);$i++) $arrfieldupd[] = $arrfields[$i]." = '".$arrfieldsval[$i]."'";
					foreach( $fieldsval as $key=>$val) {
						$arrfieldupd[] = $key." = ".$val."";
					}
					$fieldupd = join(',',$arrfieldupd);
					if ($fieldupd != '') $fieldupd = " set $fieldupd";
					//update tabel -----------------------------
					$aqry  = "update $tblsimpan $fieldupd $kondisi"; $cek.=$aqry;
					$Simpan = mysql_query($aqry);
					if ($Simpan == FALSE) $errmsg = 'Gagal Update Data !';//.$aqry;
				}
				break;
			}
			
		}
		return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}
	
	function simpanData($fmST, $tblsimpan='',  $fieldKey='',  $fieldval = '' ){	
		/* --------	
			fieldkey -> array ('id'=>10')
			fieldval -> array ('tgl'=>'2012-01-01')
		*/
		$Simpan = FALSE; $errmsg ='';$content=''; $cek='';
		switch ($fmST){
			case 0 : { //baru	
				//generate fields insert -------------------
				$aqry  = "insert into $tblsimpan (".implode(',',array_keys($fieldval)).
					") values (".implode(',',array_values($fieldval)).") ;";
				$cek .= $aqry;
				$Simpan = mysql_query($aqry);
				if ($Simpan == FALSE) $errmsg = 'Gagal Simpan Data !';//.$aqry;		
				break;
			}			
			case 1: { //edit2
				//validasi edit()
				$errmsg = $this->SimpanValidasi($fmST);
				if($errmsg == ''){	
					//create kondisi key ----------------------					
					$kondisi = '';
					$arrkond = array();					
					foreach( $fieldKey as $key=>$val) {
						$arrkond[] = $key." = ".$val."";
					}
					$kondisi = join(',',$arrkond);
					if ($kondisi != '') $kondisi = " where $kondisi";
					//generate fields update -------------------
					$arrfieldupd = array();
					foreach( $fieldval as $key=>$val) {
						$arrfieldupd[] = $key." = ".$val."";
					}
					$fieldupd = join(',',$arrfieldupd);
					if ($fieldupd != '') $fieldupd = " set $fieldupd";
					//update tabel -----------------------------
					$aqry  = "update $tblsimpan $fieldupd $kondisi"; $cek.=$aqry;
					$Simpan = mysql_query($aqry);
					if ($Simpan == FALSE) $errmsg = 'Gagal Update Data !';//.$aqry;
				}
				break;
			}
			
		}
		return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}
	
//FORM ==========================================
	function setForm_content_fields(){
		$content = '';
		
		
		
		foreach ($this->form_fields as $key=>$field){
		
			$labelWidth = $field['labelWidth']==''? $this->formLabelWidth: $field['labelWidth'];
			$pemisah = $field['pemisah']==NULL? ':': $field['pemisah'];			
			$row_params = $field['row_params']==NULL? $this->row_params : $field['row_params'];
			if ($field['type'] == ''){
				$val = $field['value'];
				$content .= 
					"<tr $row_params>
						<td style='width:$labelWidth'>".$field['label']."</td>
						<td style='width:10'>$pemisah</td>
						<td>". $val."</td>
					</tr>";
			}else if ($field['type'] == 'merge' ){
				$val = $field['value'];
				$content .= 
					"<tr $row_params>
						<td colspan=3 >".$val."</td>
					</tr>";
			}else{
				$val = Entry($field['value'],$key,$field['param'],$field['type']);	
				$content .= 
					"<tr $row_params>
						<td style='width:$labelWidth'>".$field['label']."</td>
						<td style='width:10'>$pemisah</td>
						<td>". $val."</td>
					</tr>";
			}			
			
		}
		//$content = 
		//	"<tr><td style='width:100'>field</td><td style='width:10'>:</td><td>value</td></tr>";
		return $content;	
	}	
	function setForm_content(){
		$content = '';	
		$content = $this->setForm_content_fields();
			
		$content = 
			"<table style='width:100%' class='$this->tblFormStyle'><tr><td style='padding:4'>
				<table style='width:100%' >
				$content
				</table>
			</td></tr></table>";
		return $content;
	}		
	function setFormBaru(){
		$dt=array();
		
		$dt['tahun'] = 2012;
		$dt['nop_prop'] = '32';
		$dt['nop_kota'] = '17';
		//$dt['jnsjln_op'] = 0;
		//$dt['jln_no_op'] = '1';		
		//$dt['jnsjln_wp'] = 0;
		//$dt['jln_no_wp'] = '1';		
		
		$this->form_idplh ='';
		$this->form_fmST = 0;
		$this->setForm($dt);
	}
	function setFormEdit(){
		$cek ='';
		
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from pbb_entryawal where id =$this->form_idplh "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$this->setForm($dt);
		return array('cek'=>$cek);
	}	
	function setForm($dt){			
		$this->form_fields = array(						
			//'no_sppt' => array( 'label'=>'No. SPPT', 'value'=> genNumber($get['no_terima'],5), 'type'=>'number', 'param'=>' maxlength=5 size=5 ' ),
			'no_sppt' => array( 'label'=>'No. SPPT', 'value'=> $dt['no_sppt'], 'type'=>'text', 'param'=>" title='No. SPPT' style='width: 318px;text-transform: uppercase;'"  ),
			'tahun' => array( 'label'=>'Tahun', 'value'=>$dt['tahun'], 'type'=>'number', 'param'=>' maxlength=4 size=4 ' )	,					
			'labelobj' => array( 'label'=>'OBJEK PAJAK', 'value'=>'<b>OBJEK PAJAK', 'type'=>'merge' )	,
			'no_op' => array( 
				'label'=>'NOP', 
				'value'=> 
				"<input type='text' readonly='' style='width:32;text-transform: uppercase;' maxlength='2' value='".$dt['nop_prop']."' name='nop_prop' id='nop_prop' >
				<input type='text' readonly='' style='width:32;text-transform: uppercase;' maxlength='2' value='".$dt['nop_kota']."' name='nop_kota' id='nop_kota'>
				<input type='text' style='width:48;text-transform: uppercase;' maxlength='3' value='".$dt['nop_kec']."' name='nop_kec' id='nop_kec' title='Kecamatan' onblur='fmKec.setKec()'>
				<input type='text' style='width:48;text-transform: uppercase;' maxlength='3' value='".$dt['nop_kel']."' name='nop_kel' id='nop_kel' title='Kelurahan' onblur='fmKel.setKel()'>
				<input type='text' style='width:48;text-transform: uppercase;' maxlength='3' value='".$dt['nop_blok']."' name='nop_blok' id='nop_blok' title='Blok'>
				<input type='text' style='width:72;text-transform: uppercase;' maxlength='4' value='".$dt['nop_urut']."' name='nop_urut' id='nop_urut' title='No Urut'>
				<input type='text' style='width:21;text-transform: uppercase;' maxlength='1' value='".$dt['nop_kode']."' name='nop_kode' id='nop_kode' title='Kode'>
				&nbsp <input type='button' title='Cek NOP' value='Cek NOP' onclick='".$this->Prefix.".cekNOP()'>" 
				,
				'type'=>'' 
			),
			'alm_op' => array( 
				'label'=>'Alamat', 
				'value'=>
					/*"<select title='Jenis Jalan' id='jnsjln_op' name='jnsjln_op' style='margin: 0 4 0 0'>
						<option value=''></option>
						<option value='0'>Jl</option>
						<option value='1'>Gg</option>
					</select>".*/
					cmb2D_v3('jnsjln_op',$dt['jnsjln_op'], array(array('0','Jl.'),array('1','Gg.') ),'','').
					'&nbsp'.
					Entry($dt['jln_op'],'jln_op'," title='Nama Jalan' style='width: 170px;text-transform: uppercase;'",'text').
					'&nbsp No '.Entry($dt['jln_no_op'],'jln_no_op'," title='No Rumah/Lokasi' style='width: 79px;text-transform: uppercase;'",'text')  
				,
				'type'=>'' 
			),
			//'kec_op' => array( 'label'=>'Kecamatan', 'value'=>'', 'type'=>'text' )	,
			//'kel_op' => array( 'label'=>'Kelurahan', 'value'=>'', 'type'=>'text' )	,			
			'rw_rt_op' => array( 
				'label'=>'RT / RW', 
				'value'=>
					"<table width='320'><tr><td>".
						Entry($dt['rt_op'],'rt_op'," title='RT' style='width:50px;text-transform: uppercase;'",'text').' / '. 
						Entry($dt['rw_op'],'rw_op'," title='RW' style='width:50px;text-transform: uppercase;'",'text').
						
					"</td><td align='right'>
					</td></tr></table>", 
				'type'=>'' ),
			'kec_kel_op' => array( 
				'label'=>'Kel. / Kec.', 
				'value'=>
					Entry($dt['kel_op'],'kel_op'," readonly='' title='Kelurahan' style='width:154px;text-transform: uppercase;'",'text').' / '.
					Entry($dt['kec_op'],'kec_op'," readonly='' title='Kecamatan' style='width:154px;text-transform: uppercase;'",'text')
					 , 
				'type'=>'' ),
			'vluas' => array( 'label'=>'Luas (m2)', 
				'value'=>
					'Tanah &nbsp;'.
					Entry($dt['luas_tanah'],'luas_tanah'," title='Tanah' style='width:100;text-transform: uppercase;' ",'number').
					'&nbsp;&nbsp; Bangunan &nbsp;'.
					Entry($dt['luas_bang'],'luas_bang'," title='Luas Bangunan' style='width:100;text-transform: uppercase;'",'number')					
				, 'type'=>'' )	,
			'vnilaijual' => array( 'label'=>'Nilai Jual /m2 (Rp)', 
				'value'=>
					'Tanah &nbsp;'.
					Entry($dt['nilai_tanah'],'nilai_tanah'," title='Tanah' style='width:100;text-transform: uppercase;' ",'number').
					'&nbsp;&nbsp; Bangunan &nbsp;'.
					Entry($dt['nilai_bang'],'nilai_bang'," title='Luas Bangunan' style='width:100;text-transform: uppercase;'",'number')					
				, 'type'=>'' )	,
			'vklas' => array( 'label'=>'Klas', 
				'value'=>
					'Tanah &nbsp;'.
					Entry($dt['klas_tanah'],'klas_tanah'," title='Tanah' style='width:100;text-transform: uppercase;' ",'text').
					'&nbsp;&nbsp; Bangunan &nbsp;'.
					Entry($dt['klas_bang'],'klas_bang'," title='Luas Bangunan' style='width:100;text-transform: uppercase;'",'text')					
				, 'type'=>'' )	,
			'labelwp' => array( 'label'=>'SUBJEK PAJAK', 'value'=>'<b>SUBJEK PAJAK', 'type'=>'merge' )	,
			'npwp' => array( 
				'label'=>'NPWP', 
				'value'=> Entry($dt['npwp'],'npwp',"style='text-transform: uppercase;'",'text'), 
				'type'=>'' 
			),
			'nama_wp' => array( 'label'=>'Nama', 'value'=>$dt['nama_wp'], 'param'=>"style='width: 318px;text-transform: uppercase;'", 'type'=>'text' )	,
			
			'alm_wp' => array( 
				'label'=>'Alamat', 
				'value'=>
					cmb2D_v3('jnsjln_wp',$dt['jnsjln_wp'], array(array('0','Jl.'),array('1','Gg.') ),'','').
					'&nbsp'.
					Entry($dt['jln_wp'],'jln_wp'," title='Nama Jalan' style='width: 170px;text-transform: uppercase;'",'text').
					'&nbsp No '.Entry($dt['jln_no_wp'],'jln_no_wp'," title='No Rumah\Lokasi' style='width: 79px;text-transform: uppercase;'",'text').
					"&nbsp <input type='button' title='Salin Alamat OP ke WP' value='Salin Alamat OP' onclick='".$this->Prefix.".salinAlmOp()'>" 
				,
				'type'=>'' 
			),
			//'kec_wp' => array( 'label'=>'Kecamatan', 'value'=>'', 'type'=>'text' )	,	'kel_wp' => array( 'label'=>'Kelurahan', 'value'=>'', 'type'=>'text' )	,
			'rw_rt_wp' => array( 
				'label'=>'RT / RW', 
				'value'=>
					Entry($dt['rt_wp'],'rt_wp'," title='RT' style='width:50px;text-transform: uppercase;'",'text').' / '.
					Entry($dt['rw_wp'],'rw_wp'," title='RW' style='width:50px;text-transform: uppercase;'",'text'),
					
					//'&nbsp;&nbsp Kota '.Entry($dt['kota_wp'],'kota_wp'," title='Kota' style='width:175px;text-transform: uppercase;'",'text') , 
				'type'=>'' ),
			'kec_kel_wp' => array( 
				'label'=>'Kel. / Kec.', 
				'value'=>
					Entry($dt['kel_wp'],'kel_wp'," title='Kelurahan' style='width:154px;text-transform: uppercase;'",'text').' / '. 
					Entry($dt['kec_wp'],'kec_wp'," title='Kecamatan' style='width:154px;text-transform: uppercase;'",'text')
					, 
				'type'=>'' ),
			'kota_wp' => array( 
				'label'=> 'Kota',
				'value'=> Entry($dt['kota_wp'],'kota_wp'," title='Kota' style='width:175px;text-transform: uppercase;'",'text') ,
				'type'=>''				
			)
			
			//'rw_wp' => array( 'label'=>'RW', 'value'=>'', 'type'=>'text' )	,	'rt_wp' => array( 'label'=>'RT', 'value'=>'', 'type'=>'text' )	,
			//'kota_wp' => array( 'label'=>'Kota', 'value'=>'', 'type'=>'text' )	,
			/*'vluas_tanah' => array( 'label'=>'Luas Tanah (m2)', 
				'value'=>
					Entry($dt['luas_tanah'],'luas_tanah'," title='Luas Tanah' ",'number').'&nbsp;&nbsp; Klas Tanah &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;'.
					Entry($dt['klas_tanah'],'klas_tanah'," title='Klas Tanah' maxlength=3 size=3 ",'text')					
				, 'type'=>'' )	,
			'vluas_bang' => array( 'label'=>'Luas Bangunan (m2)', 
				'value'=>Entry($dt['luas_bang'],'luas_bang'," title='Luas Bangunan' ",'number').'&nbsp;&nbsp; Klas Bangunan '.
					Entry($dt['klas_bang'],'klas_bang'," title='Klas Bangunan' maxlength=3 size=3 ",'text')
				, 'type'=>'' )	,*/
			
			
			//'petugas' => array( 'label'=>'Petugas Penerima', 'value'=> $petugasvalue, 'type'=>'', ),
			
		);
		$this->form_width = 700;
		$this->form_height = 400;
		
		
		
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';				
		}else{
			$this->form_caption = 'Edit';			
		}
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan2()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		//return $content;
		
	}
	function genForm_menubawah_add($content, $insert=FALSE){
		if($insert){
			$this->form_menubawah = $content.$this->form_menubawah;
		}else{
			$this->form_menubawah .= $content;	
		}		
	}
	
	function genForm_nodialog(){
		global $app, $Main;
		//$this->setFormBaru();
		$paddingMenuRight = 8;
		$paddingMenuLeft = 8;
		$paddingMenuBottom = 9;
		$menuHeight=22;
		echo 
				//"<html xmlns='http://www.w3.org/1999/xhtml'>".								
				"<html>".
					/*"<head>".
						$Main->HTML_Title.
						$Main->HTML_Meta.
						$Main->HTML_Link.	
						$this->setPage_OtherStyle().
						
						//$Main->HTML_Script.				
						"<script type='text/javascript' src='".$pathjs."js/base.js' language='JavaScript'></script>
						<script type='text/javascript' src='".$pathjs."js/jquery.js' language='JavaScript'></script>
						<script type='text/javascript' src='".$pathjs."js/ajaxc2.js' language='JavaScript' ></script>				
						<script type='text/javascript' src='".$pathjs."js/dialog.js' language='JavaScript' ></script>	
						<script type='text/javascript' src='".$pathjs."js/usr.js' language='JavaScript' ></script>".
						//$this->setPage_OtherScript().
						"<script type='text/javascript' src='pages/pendataan/modul_entry.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
						<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".
						"<script type='text/javascript' src='js/pbbobj.js' language='JavaScript' ></script>".
						"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
						//$scriptload;
						"<script>
							
						</script>".
					"</head>".*/
					//$this->genHTMLHead().
					$app->genHTMLHead(
						$this->setPage_OtherStyle(), 
						$this->setPage_OtherScript_nodialog()
					).
					"<body onload='".$this->Prefix.".formNoDialog_show()'>".	
					"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
						//header page -------------------		
						"<tr height='34'><td>".													
							//$this->setPage_Header().
							//createHeaderPage($this->PageIcon, $this->PageTitle).
							createHeaderPage($this->PageIcon, $this->PageTitle,  
								'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
							).
							"<div id='header'></div>".
						"</td></tr>".
						//$OtherHeaderPage.
						//Content ------------------------			
						"<tr height='*' valign='top'> <td style='padding:0 8 0 8'>".
							
							"<form name='$this->FormName' id='$this->FormName' method='post' action=''>".
								//Form ------------------
													
								//$this->setPage_Content().
								$this->setForm_content().
								"<div style='padding: 0 $paddingMenuRight $paddingMenuBottom $paddingMenuLeft;height:$menuHeight; '>
								<div style='float:right;'>".
									$this->form_menubawah.
									"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
									<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
					
								"</div>
								</div>".
							"</form>".
							
						"</td></tr>".
						//$OtherContentPage.				
						//Footer ------------------------
						"<tr><td height='29' >".	
							//$app->genPageFoot(FALSE).
							$Main->CopyRight.							
						"</td></tr>".
						$OtherFooterPage.
					"</table>".
					"</body>
				</html>"; 
	}
	
	function genForm($withForm=TRUE, $params=NULL, $center=TRUE, $fullScreen=FALSE){	
		$form_name = $this->Prefix.'_form';	
		
		if ( $fullScreen ) $params->tipe=1;
		$form= createDialog(
			$form_name.'_div', 
			$this->setForm_content(),
			$this->form_width,
			$this->form_height,
			$this->form_caption,			
			'',
			$this->form_menubawah.
			"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
			<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
			,//$this->setForm_menubawah_content(),
			$this->form_menu_bawah_height,'',$params
		);						
		if($withForm) $form= "<form name='$form_name' id='$form_name' method='post' action=''>".$form."</form>";		
		if($center) $form = centerPage( $form );
		
		/*if($withForm){
			$form= "<form name='$form_name' id='$form_name' method='post' action='' >".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,'',$params
				);
			
			
		}*/
		
		//if($center){			$form = centerPage( $form );			}
		
		return $form;
	}	
//DAFTAR ========================================
	function setTitle(){
		return 'Daftar Penetapan SPPT';
	}	
	function setMenuEdit(){
		/*$buttonEdits = array(
			array('label'=>'SPPT Baru', 'icon'=>'new_f2.png','fn'=>"javascript:".$this->Prefix.".Baru()" )
		);*/
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Recycle Bin", 'Batalkan SPPT')."</td>";
	}
	function setMenuView(){
		return 
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","SPPT",'Cetak SPPT')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Excel",'Export ke Excell')."</td>";
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","edit_f2.png","Default",'Setting Default')."</td>";		
	}
	function genMenu(){
		global $HTTP_COOKIE_VARS;
		$MyModulKU = explode(".", $HTTP_COOKIE_VARS["coModul"]);
		
		
		//$this->noModul = 1;
		$menuedit = '';
//		if( $MyModulKU[$this->noModul-1 ] == 1 ) {
//			$menuedit = $this->setMenuEdit();
//		}
			$menuedit = $this->setMenuEdit();

		$SubTitle_menu = 
			"<div style='float:right;'>					
			<div><div></div><div>$scriptMenu</div></div>
			<table ><tr>".
			$menuedit.//$this->SetPage_ToolbarAtasEdit().
			$this->setMenuView().
			"</tr>
			</table>		
			</div>";
		return $SubTitle_menu;
	}
	function setTopBar(){
		return genSubTitle($this->setTitle(),$this->genMenu());
	}
	function getDaftar_limit($Mode=1){
		global $Main;
		$Limit=''; $NoAwal = 0;
		$pagePerHal = $this->pagePerHal==''? $Main->PagePerHal : $this->pagePerHal;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);//cekPOST('HalDefault',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;
		
		return array('Limit'=>$Limit, 'NoAwal' => $NoAwal);
	}
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$fmSTATUS = cekPOST('fmSTATUS');
		//$tgl = explode('-',$fmTGLTERIMA);		
		$arrKondisi = array();	
		//default ----------------
		//$arrKondisi[] = " uid='$UID'";
			 
		$fmPILCARI = cekPOST('fmPILCARI');
		$fmPILCARIVALUE = cekPOST('fmPILCARIVALUE');
		switch($fmPILCARI){
			case '1': $arrKondisi[] = " concat(nop_prop,'.',nop_kota,'.',nop_kec,'.',nop_kel,'.',nop_blok,'.',nop_urut,'.',nop_kode) like '%$fmPILCARIVALUE%'"; break;
			case '2': $arrKondisi[] = " lpad(no_sppt,5,'0') like '%$fmPILCARIVALUE%'"; break;			
			case '3': $arrKondisi[] = " nama_wp like '%$fmPILCARIVALUE%'"; break;
			
		}
		switch($fmSTATUS){
			case '1': $arrKondisi[] = " status_batal <> 3 and status_batal <> 4 "; break;
			case '2': $arrKondisi[] = " status_batal = 3 "; break;			
			case '3': $arrKondisi[] = " status_batal = 4 "; break;			
		}		
		
		$fmPILKAS = cekPOST('fmPILKAS');
		if( !empty($fmPILKAS) ) $arrKondisi[] = " ref_admin_group='$fmPILKAS'";
		$Kondisi= join(' and ',$arrKondisi);		
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
		
		$vKondisi2_old = '';
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'vKondisi2_old'=>$vKondisi2_old);
		
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$arr = array(
			array('1','No. NOP'),
			array('2','No. SPPT'),
			array('3','Nama Subjek Pajak'),			
			);
		$arr1 = array(
			array('1','No. NOP'),
			array('2','No. SPPT'),
			array('3','Nama Subjek Pajak'),			
		);				
		$arrStat = array(
			array('1','Tidak Batal'),
			array('2','Batal'),
			array('3','Dihapus'),
		);
		$arrKec = array();
		$arrKel = array();
		$fmTGLTERIMA = date('Y-m-d');//default
		$fmTGLTERIMA2 = date('Y-m-d');
		$TampilOpt =
			genFilterBar(
				array(
					'Cari Data ',
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Berdasarkan --','').
					"&nbsp;<input type='text' value='$fmPILCARIVALUE' id='fmPILCARIVALUE' name='fmPILCARIVALUE'>" 
				)	
				,$this->Prefix.".refreshList(true)",TRUE, 'Cari').
			genFilterBar(
				array( 
					'Tampilkan',
					cmbArray('fmPilKec',$fmPilKec,$arrKec,'Semua Kecamatan',''),
					cmbArray('fmPilKel',$fmPilKel,$arrKel,'Semua Kelurahan',''),
					cmbArray('fmPilThn',$fmPilThn,$arrThn,'Semua Tahun',''),
					"Data per Halaman &nbsp;&nbsp; <input type='textbox' id='jmlPerHal' value='$Main->PagePerHal' style='width:50'>"
					
				)				
				,'',FALSE,'').
			/*genFilterBar(
				array( 
					'Lokasi &nbsp; '.	
					genComboBoxQry( 'fmPILKAS', $fmPILKAS, 
						"select Id, uraian from admin_group ",
						'Id', 'uraian', '-- Semua Lokasi Kas --',"style='width:140'" ),
					//"Penerima &nbsp; <input id='fmNmPenerima' name='fmNmPenerima' value='$fmNmPenerima'>"
					"Status &nbsp;".cmbArray('fmSTATUS',$fmSTATUS,$arrStat,'-- Semua Status --','')			
						
				)				
				,'',FALSE).*/
			genFilterBar(
				array( 
					'Urutkan &nbsp;',
					cmbArray('fmORDER1',$fmORDER1,$arr1,'-- Berdasarkan --','')."<input type='checkbox' id='fmDESC1' name='fmDESC1' value='1'>Menurun"
				)
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan');
				
			
		return array('TampilOpt'=>$TampilOpt);
	}		
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				<th class='th02' colspan=$NomorColSpan>Nomor</th>				
				<th class='th01' rowspan=2>NOP dan LETAK <br>OBJEK PAJAK</th>
				<th class='th01' rowspan=2>NPWP, NAMA dan ALAMAT <br> SUBJEK PAJAK</th>								
				<th class='th01' rowspan=2width='60'>TAHUN</th>
				<th class='th02' colspan=4 width='200'>OBJEK PAJAK BUMI</th>
				<th class='th02' colspan=4 width='200'>OBJEK PAJAK BANGUNAN</th>
				<th class='th01' rowspan=2>TOTAL NJOP <br>BUMI dan BANGUNAN<br>(Rp)</th>
				<th class='th01' rowspan=2>Total NJOPPTKP<br>(Rp)</th>
				<th class='th01' rowspan=2>Total NJOP DIKURANGI NJOPPTKP<br>(Rp)</th>
				<th class='th01' rowspan=2>PBB TERHUTANG<br>(Rp)</th>
				<th class='th01' rowspan=2>TGL. SPPT/<br>TGL. J. TEMPO/<br>STATUS</th>	
				<th class='th01' rowspan=2>UPDATE <br>TERAKHIR</th>
				</tr>
				<tr>
				$Checkbox
				<th class='th01' width='40'>No.</th>												
				<th class='th01' width='70'>Luas <br>(m2)</th>
				<th class='th01' width='70'>Kelas</th>
				<th class='th01' width='70'>per m2<br>(Rp)</th>
				<th class='th01' width='70'>Total NJOP<br>(Rp)</th>
							
				
				<th class='th01' width='70'>Luas <br>(m2)</th>
				<th class='th01' width='70'>Kelas</th>
				<th class='th01' width='70'>per m2<br>(Rp)</th>
				<th class='th01' width='70'>Total NJOP<br>(Rp)</th>
				
				</tr>			
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$arrStatus = array ('','','', 'Batal','Dihapus');
		$nop = $isi['nop_prop'].'.'.
			$isi['nop_kota'].'.'.
			$isi['nop_kec'].'.'.
			$isi['nop_kel'].'.'.
			$isi['nop_blok'].'.'.
			$isi['nop_urut'].'.'.
			$isi['nop_kode'];			
			 //genNumber($isi['no_terima'],5);
		
		$almop .= $isi['jnsjln_op']==1?"Gg. ":"Jl. ";
		$almop .= !empty($isi['jln_op'])?"".$isi['jln_op']:"";
		$almop .= !empty($isi['jln_no_op'])?"&nbsp;No. ".$isi['jln_no_op']:"";
		$almop .= !( $isi['rt_op'].$isi['rw_op'] =='' ) ? "&nbsp;RT/RW: ".$isi['rt_op']."/".$isi['rw_op'] : "";
		$almop .= !empty($isi['kel_op'])?"<br>KEL: ".$isi['kel_op']:"";
		$almop .= !empty($isi['kec_op'])?"<br>KEC: ".$isi['kec_op']:"";
		
		//$almop .= !empty($isi['kota_op'])?"<br>".$isi['kota_op']:"";
		
		$almwp .= $isi['jnsjln_wp']==1?"Gg. ":"Jl. ";
		$almwp .= !empty($isi['jln_wp'])?"".$isi['jln_wp']:"";
		$almwp .= !empty($isi['jln_no_wp'])?"&nbsp;No. ".$isi['jln_no_wp']:"";
		$almwp .= !( $isi['rt_wp'].$isi['rw_wp'] =='' ) ? "&nbsp;RT/RW: ".$isi['rt_wp']."/".$isi['rw_wp'] : "";
		$almwp .= !empty($isi['kel_wp'])?"<br>KEL: ".$isi['kel_wp']:"";
		$almwp .= !empty($isi['kec_wp'])?"<br>KEC: ".$isi['kec_wp']:"";		
		$almwp .= !empty($isi['kota_wp'])?"<br>".$isi['kota_wp']:"";
		
		$wp = !empty($isi['npwp'])? $isi['npwp'].'<br>' : '-<br>';
		$wp .= $isi['nama_wp'];
		
		$Koloms = array();
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('align=right', $no.'.' );
		$Koloms[] = array('', $nop.'<br>'.$almop);		
		$Koloms[] = array('', $wp.'<br>'.$almwp );		
		$Koloms[] = array('align=right', $isi['tahun'] );
		
		$Koloms[] = array('align=right', $isi['luas_tanah'] );
		$Koloms[] = array('align=right', $isi['klas_tanah'] );
		$Koloms[] = array('align=right', $isi['perm2_tanah'] );
		$Koloms[] = array('align=right', $isi['total_tanah'] );
		
		$Koloms[] = array('align=right', $isi['luas_bang'] );
		$Koloms[] = array('align=right', $isi['klas_bang'] );
		$Koloms[] = array('align=right', $isi['perm2_bang'] );
		$Koloms[] = array('align=right', $isi['total_bang'] );
		
		$Koloms[] = array('align=right', $isi['total_njop'] );
		
		$Koloms[] = array('align=right', $isi['k13'] );
		$Koloms[] = array('align=right', $isi['k14'] );
		$Koloms[] = array('align=right', $isi['k15'] );
		$Koloms[] = array('align=right', $isi['k16'] );
		
		$Koloms[] = array('align=right', $isi['update'] );
		
		
		
						
		return $Koloms;
	}	
	
	function genDaftarHeader($Mode=1){
		//mode :1.;ist, 2.cetak hal, 3. cetak semua
		global $Main;
		$rowspan_cbx = $this->checkbox_rowspan >1 ? "rowspan='$this->checkbox_rowspan'":'';
		$Checkbox = $Mode==1? 
			"<th class='th01' width='10' $rowspan_cbx>
					<input type='checkbox' name='".$this->Prefix."_toggle' id='".$this->Prefix."_toggle' value='' ".
						//" onClick=\"checkAll4($Main->PagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');\" /> ".
						" onClick=\"checkAll4($Main->PagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');".
							"$this->Prefix.checkAll($Main->PagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek')\" /> ".
						
			" </th>" : '';		
		$headerTable = $this->setKolomHeader($Mode, $Checkbox);
		return $headerTable;
	}	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	
		return $aqry;		
	}
	function setDaftar_after_getrow($list_row, $isi, $no, $Mode, $TampilCheckBox,
			$RowAtr, $KolomClassStyle){
		return $list_row;
	}
	function setDaftar_before_getrow($no, $isi, $Mode, $TampilCheckBox,
			$RowAtr, $KolomClassStyle)
	{
		$ListData ='';
		/*$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);			
		$list_row = genTableRow($Koloms, 
						$RowAtr." valign='top' id='$cb' value='".$isi['Id']."'",$ColStyle);					
		$ListData = $this->setDaftar_after_getrow($list_row, $isi);*/			
		return array ('ListData'=>$ListData, 'no'=>$no);
	}
	function setCekBox($cb, $KeyValueStr, $isi){
		$hsl = '';
		if($KeyValueStr!=''){
			$hsl = "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');".
					"$this->Prefix.cbxPilih(this) \" />";					
		}
		return $hsl;
	}
	
	function setDaftar_After($no=0, $ColStyle=''){
		$ListData = '';
		/*$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);	
		*/
		return $ListData;
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
		$qry = mysql_query($aqry);
		$numrows = mysql_num_rows($qry); $cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
					
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				$isi = array_map('utf8_encode', $isi);	
				
				$no++;
				$jmlDataPage++;
				if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
				
				$KeyValue = array();
				for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
					$KeyValue[$i] = $isi[$this->KeyFields[$i]];
				}
				$KeyValueStr = join($this->pemisahID,$KeyValue);
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
				
				if($Koloms != NULL){
					
				
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
		
		}
		
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
	function genRowSum_setTampilValue($i, $value){
		
		return number_format($value,2, ',', '.');	
	}
	function genRowSum_setColspanTotal($Mode, $colspanarr ){
		$TotalColSpan1 = $Mode==1? $colspanarr[0]-1 : $colspanarr[0]-2;
		return $TotalColSpan1;
	}
	function genRowSum($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		
		//if (sizeof($this->FieldSum)>0){
		if (sizeof($this->FieldSum)==1){
			
			$TampilTotalHalRp = $this->genRowSum_setTampilValue(0, $this->SumValue[0]);//number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total per Halaman</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total</td>": '';
			$Kanan2 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;			
			
			$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[0]."</div></td>
					$Kanan2
				</tr>" ;
				
			
			
		}
		else if (sizeof($this->FieldSum)>1){
			
			$colspanarr=$this->fieldSum_lokasi;
			$ContentTotalHal =	"<tr>";
			$ContentTotal =	"<tr>";
			
			
			for ($i=0; $i<sizeof($this->FieldSum);$i++){
				
				if($i==0){
					$TotalColSpan1 =  //$Mode==1? $colspanarr[0]-1 : $colspanarr[0]-2  ;			
						$this->genRowSum_setColspanTotal($Mode, $colspanarr );
					$Kiri1 = $TotalColSpan1 > 0 ? 
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalhalstr</td>": '';
					$ContentTotalHal .=	$Kiri1;
					$ContentTotal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalAllStr</td>":'';
				}else{
					$TotalColSpan1 = $colspanarr[$i] - $colspanarr[$i-1]-1; 
					//if($TotalColSpan1>0){
					$ContentTotalHal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
					$ContentTotal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
					
					//}
				}
				//$totalstr = $i==0? "<b>Total per Halaman": '';
				////$TotalColSpan1 = $colspanarr[$i]- $colspanarr[$i-1]-1;			
				//$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$totalstr</td>": '';
				
				$TampilTotalHalRp = //number_format($this->SumValue[$i],2, ',', '.');
					$this->genRowSum_setTampilValue($i, $this->SumValue[$i]);
				$vTotal = number_format($Total[$i],2, ',', '.');
				$ContentTotalHal .= //$i==0?
					//"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	:
					"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	;
				$ContentTotal .= $i==0?
					"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[$i]."</div></td>":
					"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum$i'>".$Total[$i]."</div></td>";
			}
			
			//$totrow = $Mode == 1? $this->totalRow : $this->totalRow;
			$TotalColSpan1 = $this->totalCol - $colspanarr[sizeof($this->FieldSum)-1];					
			$ContentTotalHal .=	$TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
			$ContentTotal .= $TotalColSpan1 > 0 ?
						"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
			
			
		}
		$ContentTotal = $this->withSumAll? $ContentTotal: '';
		$ContentTotalHal = $this->withSumHal? $ContentTotalHal: '';
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			//$ContentTotalHal='';			
			if ($this->withSumAll) {
				$ContentTotalHal = '';
			}
		}
		return $ContentTotalHal.$ContentTotal;
	}
	
	function genRowSum_($ColStyle, $Mode, $Total){
		//hal
		$ContentTotalHal=''; $ContentTotal='';
		
		if (sizeof($this->FieldSum)>0){
			
			$TampilTotalHalRp = number_format($this->SumValue[0],2, ',', '.');
			$TotalColSpan1 = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
			$TotalColSpan2 = $this->FieldSum_Cp2[$Mode-1];//$Mode ==1 ? 5 : 4;	
			$Kiri1 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total per Halaman</td>": '';
			$Kanan1 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$Kiri2 = $TotalColSpan1 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan1' align='center'><b>Total</td>": '';
			$Kanan2 = $TotalColSpan2 > 0 ? "<td class='$ColStyle' colspan='$TotalColSpan2' align='center'></td>": ''; 
			$ContentTotalHal =
				"<tr>
					$Kiri1
					<!--<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>-->
					<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
					$Kanan1<!--<td class='$ColStyle' colspan='4'></td>-->
				</tr>" ;			
			
			$ContentTotal = 
				"<tr>
					$Kiri2
					<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total."</div></td>
					$Kanan2
				</tr>" ;
				
			if($Mode == 2){			
				$ContentTotal = '';
			}else if($Mode == 3){
				$ContentTotalHal='';			
			}
			
		}
		return $ContentTotalHal.$ContentTotal;
	}
	function setDaftar_hal($jmlData){
		global $Main;
		$elhal = $this->Prefix.'_hal';
		$hal = cekPOST($this->Prefix.'_hal');
		$pagePerHal = $this->pagePerHal ==''? $Main->PagePerHal : $this->pagePerHal;
		return 
			"<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
			<tr><td align=center style='padding:4'>".
				Halaman2b(	$jmlData, $pagePerHal, $elhal, $hal, 5,
				 					
					$this->Prefix.'.gotoHalaman').
					
					
			"</td></tr></table>";
			
	}
	function setSumHal_query($Kondisi, $fsum){
		return "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		
		//return " select "
	}
	function genSumHal($Kondisi){
		
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		
		$fsum_ = array();
		$fsum_[] = "count(*) as cnt";
		//$i=0;
		foreach($this->FieldSum as &$value){
			$fsum_[] = "sum($value) as sum_$value";
			//$i++; 
		}
		$fsum = join(',',$fsum_);
				
		//$aqry = "select count(*) as cnt,  sum(jml_terima) as totterima  from $this->TblName $Kondisi "; //echo $aqry;
		//$aqry = "select $fsum from $this->TblName $Kondisi "; //echo $aqry;
		$aqry = $this->setSumHal_query($Kondisi, $fsum); $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['cnt'];			
			/*$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,$this->elCurrPage,cekPOST('HalDefault'),5, $this->Prefix.'.gotoHalaman').
				"</td></tr></table>";
			*/
			//$Hal = $this->setDaftar_hal($jmlData);
							
			//$jmlTotal= $isi['totterima'];
			$j=0;
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				//$vSum[] = $this->genSum_setTampilValue($value, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
				$vSum[] = $this->genSum_setTampilValue($j, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
				$j++;
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}	
		
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = ''; 			
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>htmlspecialchars($cek) );
	}
	function genSum_setTampilValue($i, $value){
		return number_format($value, 2, ',' ,'.');
	}
	function genTableAttribute ($class_){
		$tableAttribute = " border='1'   style='margin:4 0 0 0;width:'$this->tableWidth' ";
		return "<table class='$class_' $tableAttribute >";
	}
	function genTableRow($Koloms, $RowAtr='', $KolomClassStyle=''){
	$baris = '';
	if($Koloms[0][0] == "Y"){
		$baris.=$Koloms[0][1];
	}else{
		foreach ($Koloms as &$value) { 
		//if($value[1] !='')
			
		$baris .= "<td class='$KolomClassStyle'  {$value[0]}>$value[1]</td>"; 
		}
	}
		
	if (count($Koloms)>0){$baris ="<tr $RowAtr > $baris </tr>"; }
	return $baris;
}
	function genTableDetail(){
		//return "tes";
		return '';
	}			

	function genDaftarInitial(){
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
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
/*
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
*/	
	/*
	function genSumHal2($Kondisi){
		global $Main;
		//$Sum = 'sum'; $Hal = 'hal';
		$jmlData = 0;
		$jmlTotal = 0;
		
		$aqry = "select count(*) as sum1, sum(jml_ketetapan) as sum2 from $this->TblName $Kondisi "; //echo $aqry;
		$qry = mysql_query($aqry);
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['sum1'];
			$jmlTotal= $isi['sum2'];
			$Sum = number_format($jmlTotal, 2, ',' ,'.');
			$Hal= "<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
				<tr><td align=center style='padding:4'>".
					Halaman2b($jmlData,$Main->PagePerHal,"HalDefault",cekPOST('HalDefault'),5,'Penetapan.gotoHalaman').
				"</td></tr></table>";
		}
				
		return array('sum'=>$Sum, 'hal'=>$Hal);
	}*/
// menu ================================================================

//ajx proses ===========================================================	
	function set_ajxproses_other_($idprs, $Opt){
		$ErrNo=0; $ErrMsg = ''; $content='tes'; $json=FALSE; $cek='';
		//---------
		return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json ,'cek'=>$cek);
	}
	function set_ajxproses_($idprs, $Opt){
		$ErrNo=0; $ErrMsg = ''; $content=''; $json=FALSE; $cek ='';
		
		if ($idprs =='') {
			echo $this->setPage();
		}else{
			switch ($idprs){
				case 'list':{
					$Opsi = $this->getDaftarOpsi();	//$content = 'tes'.
					$content=	
						array('list'=>
							$this->genDaftar( 
								$Opsi['Kondisi'], $Opsi['Order'], 
								$Opsi['Limit'], $Opsi['NoAwal'], 1 
							)
						);
					$json= true;							
					break;
				}
				case 'sumhal':{
					$Opsi = $this->getDaftarOpsi();
					$content = $this->genSumHal($Opsi['Kondisi']);
					$json= true;
					break;
				}
				case 'cetak_hal': {
					//echo $this->setCetak(2);
					//$this->genCetak(2,$Other,'30cm','DAFTAR NOTA HITUNG');
					$this->cetak_hal();
					break;
				}
				case 'cetak_all':{
					$this->cetak_all();					
					//echo $this->setCetak(3);
					//$this->genCetak(3,$Other,'30cm','DAFTAR NOTA HITUNG');
					break;
				}
				case 'hapus':{
					$cbid= $_POST[$this->Prefix.'_cb'];				
					$ErrMsg=//'hapus '.$cbstr;
						$this->Hapus($cbid);
					$json=TRUE;	
					break;
				}
				/*case 'create_tahun':{
				$ErrMsg ='tes';//$this->create_tahun();		
				$content = $_GET['thn'];
				$json = TRUE;		 
				break;
				}*/	
				default :{
					//$TampilOpt = $Perhitungan->genDaftarOpsi();				
					$other = $this->set_ajxproses_other($idprs,$Opt);		
					$ErrNo = $other['ErrNo'];
					$ErrMsg = $other['ErrMsg'];
					$content = $other['content'];
					$json = $other['json'];	
					$cek = $other['cek'];									
					break;	
				}
			}
		}
		
		
		
		return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json, 'cek'=>$cek );
	}
	function ajxproses_(){
		$ErrMsg = ''; $cek='';
		if ($_GET['idprs'] != ''){
			$idprs = $_GET['idprs']; //echo ',idprs='.$idprs;
		}else{ //json
			$optstring = stripslashes($_GET['opt']);
			$Opt = json_decode($optstring); //$page = json_encode(",cek="+$Opt->idprs);
			$idprs = $Opt->daftarProses[$Opt->idprs];
		}
		
		$get = $this->set_ajxproses($idprs, $Opt);
		$ErrNo = $get['ErrNo'];
		$ErrMsg = $get['ErrMsg'];
		$content = $get['content'];
		$cek = $get['cek'];
		$json = $get['json'];
		if($json){ //json---	
			$pageArr = array(
						'idprs'=>$Opt->idprs,						 
						'daftarProses'=>$Opt->daftarProses , 
						'ErrNo'=>$ErrNo, 
						'ErrMsg'=>$ErrMsg, 
						'content'=> $content ,
						'cek'=>$cek
					);
			$page = json_encode($pageArr);	
			$page;
		}
		
		return $page;
	}
	function set_selector_other($tipe){
		$cek = ''; $err=''; $content=''; $json=FALSE;
		switch($tipe){	
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function setProperty(){
		//fungsi ini untuk menset ulang property class, misalnya $this->FieldSum_Cp1
	}
	function selector(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		$tipe = $_REQUEST['tipe'];
		$this->setProperty();
		//echo $tipe;
		if ($tipe==''){
			//$ModulAkses = isPageEnable('00','sum');
			//if ( $ModulAkses>0){
			//echo 'tes2';
			echo $this->pageShow();
					//echo $pbbPenetapan->ajxproses();
			//	}
		}else{
			switch($tipe){			
				case 'subtitle':{		
					$content = $this->setTopBar();
					$json=TRUE;
					break;
				}
				case 'filter': {
					$opsi = $this->genDaftarOpsi();
					$content=$opsi['TampilOpt'];
					$json=TRUE;		
					break;
				}
				case 'daftar': {
					$Opsi = $this->getDaftarOpsi(); 		
					$daftar = $this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'],  $Opsi['NoAwal'], 1, $Opsi['vKondisi_old']);					
					$content=$daftar['content']	;
					$err = $daftar['err'];
					$cek .= $daftar['cek'];
					$cek .= 'kondisi='.$Opsi['Kondisi'].'order='.$Opsi['Order'].
						' limit='.$Opsi['Limit'].
						' noawal='.$Opsi['NoAwal'] .
						' vkondisi='.$Opsi['vKondisi_old'];
					//$cek .= $daftar['cek'];		
					$json = TRUE;
					break;
				}
				case 'sumhal':{
					$Opsi = $this->getDaftarOpsi();
					$content = $this->genSumHal($Opsi['Kondisi']);
					$cek .= 'kondisi='.$Opsi['Kondisi'].'order='.$Opsi['Order'].'limit='.$Opsi['Limit'].'noawal='.$Opsi['NoAwal'];
					
					$json= true;
					break;
				}
				case 'cetak_hal': {		
					$this->cetak_hal();
					break;
				}
				case 'cetak_all':{
					$this->cetak_all();							
					break;
				}
				case 'exportXls':{
					$mode = $_REQUEST['mode'];
					$this->exportExcel($mode);
					break;	
				}
								
				case 'hapus':{
					$cbid= $_POST[$this->Prefix.'_cb'];				
					$get= $this->Hapus($cbid);
					$err= $get['err']; 
					$cek = $get['cek'];
					$content = $get['content'];
					$json=TRUE;	
					break;
				}
				/*case 'simpan':{
					//$get = $this->Simpan();					
					$get = array('cek'=>'', 'err'=>'ggal','content'=>'', 'json'=>TRUE);
					$cek = $get['cek'];
					$err = $get['err'];
					$content=$get['content'];
					$json=$get['json'];
					break;
				}*/
				default: {//other type
					//include('penetapan_list.php'); 
					$other = $this->set_selector_other($tipe);
					$cek = $other['cek'];
					$err = $other['err'];
					$content=$other['content'];
					$json=$other['json'];
					
					break;
				}
			}
			if($Main->SHOW_CEK==FALSE) $cek = '';
			if($json){
				$pageArr = array(
					//'cek'=>utf8_encode($cek), 
					//'cek'=>substr($cek,1,$Main->SHOW_CEK_LIMIT), 
					'cek'=>htmlspecialchars($cek), 
					'err'=>$err, 'content'=>$content		
				);
				$page = json_encode($pageArr);	
				//header("Content-Type: text/javascript; charset=utf-8");
				echo $page;		
			}
			
		}
		
		
	}
	
//PAGE ===========================================	
	function setPage_Header_($IconPage='', $TitlePage=''){		
		//return createHeaderPage($IconPage, $TitlePage);
		return createHeaderPage($IconPage, $TitlePage,  
			'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
		);
	}
	function setPage_Header(){		
		//return createHeaderPage($this->PageIcon, $this->PageTitle);
		return createHeaderPage($this->PageIcon, $this->PageTitle,  
			'', FALSE, 'pageheader', $this->ico_width, $this->ico_height
		);
	}
	function setPage_OtherStyle(){
		/*return "<link type='text/css' href='css/pay.css' rel='stylesheet'>					
						<link type='text/css' href='css/menu_pay.css' rel='stylesheet'>";
		*/
		return '';
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
	function setPage_OtherScript_nodialog(){
		return "<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
					"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
						"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>";
	}
	function setPage_IconPage(){
		return 'images/icon/daftar32.png';
	}
	function setPage_TitlePage(){
		$Op = $_GET['Op'];
				$NamaJnsPajak = get_kodrek_pad(genNumber($_GET['Op'],2 ) ,'00');	
		return  $NamaJnsPajak.' - Nota Hitung';
	}
	function setPage_OtherHeaderPage(){
		return '';
	}
	function setPage_FormName(){
		return 'adminForm';
	}
	function setPage_hidden(){
		return genHidden(array('fmOp'=> genNumber($_GET['Op'],2) ));
	}	
	function ToolbarAtasEdit_Add($label='',$ico='',$link='',$hint='',$insert=FALSE){
		if($insert){
			$this->ToolbarAtas_edit = 
				"<td>".genPanelIcon($link,$ico,$label,$hint)."</td>".$this->ToolbarAtas_edit;
		}else{
			$this->ToolbarAtas_edit .= 
				"<td>".genPanelIcon($link,$ico,$label,$hint)."</td>";	
		}
		
		return $ToolbarAtas_edit;
	}
	function setPage_ToolbarAtasView(){
		return //"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHit()","print_f2.png","Cetak", 'Cetak Nota Hitung')."</td>
					"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","Halaman")."</td>			
					<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png","Semua")."</td>";
					
	}	
	function setPage_OtherInForm(){}
	function setPage_OtherContentPage(){
		
	}
	function setPage_OtherFooterPage(){}	
	function genHTMLHead(){		
		global $Main, $app;	
		/*return
			"<head>".
				$Main->HTML_Title.
				$Main->HTML_Meta.
				$Main->HTML_Link.	
				$this->setPage_OtherStyle().
				
				//$Main->HTML_Script.				
				"<script type='text/javascript' src='".$pathjs."js/base.js' language='JavaScript'></script>
				<script type='text/javascript' src='".$pathjs."js/jquery.js' language='JavaScript'></script>
				<script type='text/javascript' src='".$pathjs."js/ajaxc2.js' language='JavaScript' ></script>				
				<script type='text/javascript' src='".$pathjs."js/dialog.js' language='JavaScript' ></script>	
				<script type='text/javascript' src='".$pathjs."js/usr.js' language='JavaScript' ></script>".
				$this->setPage_OtherScript().
			"</head>";
		*/
		return $app->genHTMLHead($this->setPage_OtherStyle(), 	$this->setPage_OtherScript());	
	}
	function setPage_HeaderOther(){
		return '';
	}
	function setNavAtas(){
		global $Menu;
		if($Menu) {
			//return $Menu->genMenu($this->kodeMenu);
			return $Menu->genMenu();	
		}else{
			return '';
		}
		
		//return '';//
			/*'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
					<td class="menudottedline" width="40%" height="20" style="text-align:right"><b>


	<a href="?Pg=05&amp;SPg=03" title="Buku Inventaris">BI</a> |
	<a href="?Pg=05&amp;SPg=04" title="Tanah">KIB A</a>  |  
	<a href="?Pg=05&amp;SPg=05" title="Peralatan &amp; Mesin">KIB B</a>  |  
	<a href="?Pg=05&amp;SPg=06" title="Gedung &amp; Bangunan">KIB C</a>  |  
	<a href="?Pg=05&amp;SPg=07" title="Jalan, Irigasi &amp; Jaringan">KIB D</a>  |  
	<a href="?Pg=05&amp;SPg=08" title="Aset Tetap Lainnya">KIB E</a>  |  
	<a href="?Pg=05&amp;SPg=09" title="Konstruksi Dalam Pengerjaan">KIB F</a>  |  
	<a href="?Pg=05&amp;SPg=11" title="Rekap BI">REKAP BI</a> |
	<a href="?Pg=05&amp;SPg=12" title="Daftar Mutasi">MUTASI</a>  |
	<a href="?Pg=05&amp;SPg=13" title="Rekap Mutasi">REKAP MUTASI</a> |
	<a href="pages.php?Pg=sensus" title="Sensus">SENSUS</a>
	  &nbsp;&nbsp;&nbsp;
	</b></td>
	</tr></table>';*/
	}
	function pageShow(){
		global $app, $Main; 
		
		$navatas_ = $this->setNavAtas();
		$navatas = $navatas_==''? // '0': '20';
			'':
			"<tr><td height='20'>".
					$navatas_.
			"</td></tr>";
		$form1 = $this->withform? "<form name='$this->FormName' id='$this->FormName' method='post' action=''>" : '';
		$form2 = $this->withform? "</form >": '';
		
		
		return
		
		//"<html xmlns='http://www.w3.org/1999/xhtml'>".			
		"<html>".
			$this->genHTMLHead().
			"<body >".
			/*"<div id='pageheader'>".$this->setPage_Header()."</div>".
			"<div id='pagecontent'>".$this->setPage_Content()."</div>".
			$Main->CopyRight.*/
							
			"<table id='KerangkaHal' class='menubar' cellspacing='0' cellpadding='0' border='0' width='100%' height='100%' >".
				//header page -------------------		
				"<tr height='34'><td>".						
					//$this->setPage_Header($IconPage, $TitlePage).
					$this->setPage_Header().
					"<div id='header' ></div>".
				"</td></tr>".	
				$navatas.			
				//$this->setPage_HeaderOther().
				//Content ------------------------			
				//style='padding:0 8 0 8'
				"<tr height='*' valign='top'> <td >".
					
					$this->setPage_HeaderOther().
					"<div align='center' class='centermain' >".
					"<div class='main' >".
					$form1.
					
						//Form ------------------
						//$hidden.					
						//genSubTitle($TitleDaftar,$SubTitle_menu).						
						$this->setPage_Content().
						//$OtherInForm.
						
					$form2.//"</form>".
					"</div></div>".
				"</td></tr>".
				//$OtherContentPage.				
				//Footer ------------------------
				"<tr><td height='29' >".	
					//$app->genPageFoot(FALSE).
					$Main->CopyRight.							
				"</td></tr>".
				$OtherFooterPage.
			"</table>".
			"</body>
		</html>"; 
	}	
	function setPage_Content(){
		
		//return "<div id='".$this->Prefix."_pagecontent'></div>";
		return $this->genDaftarInitial();
		
	}
//CETAK DAFTAR ===========================================
	function cetak_hal(){
		$this->Cetak_Mode=2;												
		$this->genCetak();
	}
	function cetak_all(){
		$this->Cetak_Mode=3;	
		$this->genCetak();	
	}
	function exportExcel($mode=3){ //mode 3 = cetak semua, 2 = cetak halaman
		$this->Cetak_Mode=$mode;
		$this->genCetak(TRUE);	
	}
	/**
	function exportExcel(){
		$this->Cetak_Mode=3;
		$this->genCetak(TRUE);	
	}	**/
	
	/*function setCetak_Mode($Mode){ return $Mode;}	
	function setCetak_OtherHTMLHead($other=''){return $other;}
	function setCetak_WIDTH($width=30){return $width;}
	function setCetak_JUDUL($judul='Daftar'){return $judul;}
	function setCetak($Mode){
		return $this->genCetak(
					$this->setCetak_Mode($Mode), 
					$this->setCetak_OtherHTMLHead(), 
					$this->setCetak_WIDTH(), 
					$this->setCetak_JUDUL()
				);
	}*/
	function setCetak_Header_(){
		global $Main;
		return  
			"<table style='width:100%' border=\"0\"><tr>
			<td >".
				/*"<span class='title2'>PEMERINTAH KOTA CIMAHI</span><br>
				<span class='title3'>DINAS PENDAPATAN</span><br>
				<span class='title1'>Komplek Perkantoran Pemerintah Kota Cimahi</span><br>
				<span class='title1'>Jl. Rd. Demang Hardjakusuma Lt. 2 Telp/Fax (022) 6652559</span>"
				*/
				$Main->KopLogo.
			"</td>		
			<td >".
				$this->Cetak_Judul.
			"</td>
			</tr></table>";
	}
	
	function setCetakTitle(){
		return	"<DIV ALIGN=CENTER>$this->Cetak_Judul";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');

		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	//function genCetak($Mode=2, $OtherHTMLHead='', $WIDTH=30, $JUDUL=''){
	function setCetak_footer($xls=FALSE){
		return "<br>".	
				PrintTTD($this->Cetak_WIDTH, $xls);
	}
	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
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
		$this->cetak_xls=$xls;
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
		if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}								
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}
}



//class PbbDaftarObj{



function setCetakJudul ($JUDUL = 'LAPORAN DAFTAR PENDATAAN',
	$kdpajak='4.1.1.04.00', 
	$nmpajak='PAJAK REKLAME', $other='')
{
	return
			"<table width='100%' border=\"0\">
				<tr>
					<td align='right' colspan='4'>
						<span class='title2'>
							$JUDUL
						</span>
					</td>
				</tr>
				<tr>
					<td align='right' colspan='4'>
						<span class='title1'>
							$kdpajak - $nmpajak
						</span>
					</td>
					<td colspan='4'><span class='title1'></span></td>
				</tr>
				<tr>
					<td><span class='title1'></span></td>
					<td colspan='4'><span class='title1'>$other</span></td>
				</tr>
			</table>";
	}

class CetakObj{
	var $Style = 'rangkacetak';
	var $OtherHTMLHead ='';
	var $WIDTH ='20cm';
	var $HEIGHT ='26.5cm';
	function setHTMLHead(){
		global $Main, $PATH_CSS ;
		return 
			"<head>
				<title>$Main->Judul</title>
				<link rel=\"stylesheet\" href=\"".$PATH_CSS."css/template_css.css\" type=\"text/css\" />
				$this->OtherHTMLHead
			</head>";
	}
	function setContent(){
		$content = 'content';
		return $content;	
	}
	function genCetak(){
		
		//$TampilOpt = $Penetapan->genDaftarOpsi(); 						
		/*return 
			"<html>".
				$this->setHTMLHead().
			"<body >".
				"<table class=\"$this->Style\" style='width:$this->WIDTH'>
				<tr><td valign=\"top\">".
					$this->setContent().
				"</td></tr>
				</table>".
			"</body>	
			</html>";*/
		//$this->WIDTH = '20cm';
		$height = $this->HEIGHT ==''? '' : "height:".$this->HEIGHT;
		return 
			"<html>".
				$this->setHTMLHead().
			"<body >".
				"<div style='width:".$this->WIDTH.";$height;overflow:hidden'>".
					$this->setContent().
				"</div>".
			"</body>	
			</html>";
	}
}


function genComboBox_detpajak($kdpajak='41101', 
	$nama_el='fmPILURAIAN_det',
	$value='', $params="style='width:150'")
{
	//$kdpajak = '41101';//concat(f,g,h,i,j)
	$content = 
		genComboBoxQry( $nama_el, $value, 
			"select j as kd, uraian 
				from v1_ref_kodrek_target 
				where j<>'00' and concat(f,'.',g,'.',h,'.',i)='$kdpajak'",
			'kd', 'uraian', 'Pilih Semua',$params, '' );
	
	return $content;
}



/*
class MenuObj{
	var $nameobj = 'menu';
	var $tampilKe = 'lap_option';
	var $width = 200;
	var $items = '[
			{"label":"a","link":"#1" }, 
			{"label":"b","link":"#2" }
		]';
	function show(){		
		$tdclass = 'GarisDaftar';
		//$aclass= 'toolbar';
		$trclass= 'row1';
		$iconwidth = '16';
		$tableatr = "cellspacing='0' cellpadding='0' border='0'";
		
		$menu_items = '';
		$items_arr =  
			//json_decode('[{"label":"2"},{"label":"3"}]');
			json_decode($this->items);
			//array('1'=>'a', '2'=>'b');
		
		if($items_arr != NULL)
		foreach ($items_arr as $key=>$value){			
			
			$item_id= $this->nameobj.'_item_'.$key;
			$item_link = $value->link !=''? 
				$value->link 
				: "javascript:".$this->nameobj."_tampil(\"$item_id\");";
			$item_label = $value->label;
			if($this->with_icon) $kolom_ico ="<td style='width:$iconwidth'>$item_ico</td>";
			$menu_items .=
				"<tr class='$trclass'><td class='$tdclass' >
					<a id='$item_id' class='$aclass' href='$item_link' style='width:$this->width'>						
						<table width='100%'><tr>
						$kolom_ico
						<td>$item_label</td>
						</tr></table>
					</a>
					<div id='".$item_id."_content' style='display:none'>
						$value->content
					</div>
				</td><tr>";
		}
			
		
		$script =
			"<script>
				function ".$this->nameobj."_tampil(id){
					//alert(id);
					document.getElementById('$this->tampilKe').innerHTML =
						document.getElementById(id+'_content').innerHTML;
				}
			</script>";
		$menukiri = 
			$script.
			"<table class='koptable3' id='toolbar' $tableatr>
			<thead>
				<th class='th01' style='height:32'>Pilih Laporan</th>
			</thead>
			<tbody>
				$menu_items				
			</tbody>
			</table>";
		
		return $menukiri;
	}
	function items_clear(){
		$this->items ='';// array();
	}
	function items_add_($item){
		$tmp=json_decode($this->items);
		//$tmp[]=json_decode('{"label":"f1","content":"","link":"#5" }');
		$tmp[]=json_decode($item);
		$this->items = json_encode($tmp);
	}
	function items_add($label='',$content='',$mylink=''){
		$json ='{"label":"'.$label.'", "content":"'.$content.'", "link":"'.$mylink.'"}';
		$this->items_add_($json);
	}
	
}

$Menu = new MenuObj();
//set menu ------------------
$menu_width = 200;
$Menu->width = $menu_width;
$Menu->tampilKe='lap_option';
//set items -----------------
$Menu->items_clear();
//$Menu->items_add("lap1",' option lap1 ','javascript:alert(\\"tes\\");' ); 
//$Menu->items_add("lap2",' option lap2 ','' ); 
*/
?>