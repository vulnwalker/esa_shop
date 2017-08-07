<?php
include ('common/daftarobj.php');
include('common/fnsensus.php');
$SPg = $_GET['SPg'];

switch($SPg){	
	default: {//tampil page				
		//$ModulAkses = isPageEnable('00','sum');
		//if ( $ModulAkses>0){
			$Sensus->selector();
		//}
		break;
	}
}


/*
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
switch($SPg){
	case "01":include("listsensus.php");break;	
	case 'formbaru': 
		$cek = '';
		include('common\daftarobj.php');
		include('common\fnsensus.php');
		$form_name = $Sensus->Prefix.'_form';	
		$Sensus->form_fields = array(
			'field1' => array( 'label'=>'Gedung', 'value'=>"<div id='cbxgedung_render'></div>", 'type'=>'', 'valign'=>'center', 'labelWidth'=>80),
			'field2' => array( 'label'=>'Ruang', 'value'=>'value2', 'type'=>'text', 'valign'=>'center' ),
			'field3' => array( 'label'=>'Tgl. Sensus', 
				'value'=> createEntryTgl3(
					$tgl_sensus, 'tgl_sensus', '', '', '', 
					$Sensus->prefix.'_form',
					TRUE, FALSE
				),
				'type'=>'', 'valign'=>'center' 
			)
		);
		$FormContent = 
			"<table style='width:100%' style=''><tr><td style='padding:4'>
				<table style='width:100%' >".
				$Sensus->setForm_content_fields(80).
				"</table>
			</td></tr></table>";
		$content = centerPage(
			"<form name='$form_name' id='$form_name' method='post' action=''>".
			createDialog(
				$form_name.'_div', 
				$FormContent,
				750,
				450,
				'Sensus Barang - Baru',
				'',
				$Sensus->form_menubawah.
				"<input type='hidden' id='".$Sensus->Prefix."_idplh' name='".$Sensus->Prefix."_idplh' value='$Sensus->form_idplh' >
				<input type='hidden' id='".$Sensus->Prefix."_fmST' name='".$Sensus->Prefix."_fmST' value='$this$Sensus->form_fmST' >"
				,//$this->setForm_menubawah_content(),
				$Sensus->form_menu_bawah_height
			).
			"</form>"
		);	
		$pageArr = array(
			'content'=>$content, 
			'cek'=>$cek, 
		);
		$page = json_encode($pageArr);	
		echo $page;	
	break;
	default: include("listsensus.php");
	break;
}
*/
?>