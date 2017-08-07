<?php




$pageArr = array(
	'idprs'=>$Opt->idprs,						 
	'daftarProses'=>$Opt->daftarProses , 
	'ErrNo'=>$ErrNo, 
	'ErrMsg'=>$ErrMsg, 
	'content'=> $content,
	'cek'=>$cek 
);
$page = json_encode($pageArr);	
echo $page;
	
?>