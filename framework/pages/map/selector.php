<?php

$cek = ''; $content=''; $err=''; $json = FALSE;
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";

switch($SPg){
	case "01": {
		include("view_mapjs.php");
		break;
	}
	case '02': {
		include('gen_map.php');
		break;
	}
	case '03': {
//		if($Main->MODUL_PETA){
			include('view_map3.php');
			//echo 'ter';
//		}
		break;
		
	}
	case 'getinfo':{
		$get = $Map->getinfo();
		$cek=$get['cek'];
		$err=$get['err'];
		$content=$get['content'];	
		$json=TRUE;			
		break;
	}
	case 'getdata': {		
		$get = $Map->getdata();
		$cek=$get['cek'];
		$err=$get['err'];
		$content=$get['content'];
		$json=TRUE;
		break;
	}
	case 'getmenukib':{
		$json = TRUE;
		$get = $Map->getmenukib();
		$cek=$get['cek'];
		$err=$get['err'];
		$content=$get['content'];
		break;		
	}
	
	


}

if($json){
//	$cek='';
	$pageArr = array(
		'cek'=>$cek, 'err'=>$err, 'content'=>$content
	);
	$page = json_encode($pageArr);	
	echo $page;
		
}

?>