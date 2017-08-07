<?php
class MenuObj{
   
	function genMenu(){
	
		//get kode dari href param
		$url = $_SERVER['REQUEST_URI'];
		$arrulr = explode('/',$url);
		$so = sizeof($arrulr);
		$href_ = $arrulr[$so-1];
		$get = mysql_fetch_array(mysql_query( "select * from ref_menu where href='$href_' and aktif=1 order by level desc " ));
		$kode = $get['kode'];
				
		//create menu
		$menu = '';//"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"> ";					
		$arrKode = explode('.',$kode);		
		$level = 1; $parent='';
		foreach($arrKode as &$value ){
			if($level>1 && $value <> ''){
				//ambil menu sesuai level
				$aqry = "select * from ref_menu where kode like '$parent%' and level = $level and aktif = 1 ";
				$qry = mysql_query(	$aqry	);
				$arrMenu = array();
				while($isi= mysql_fetch_array($qry)){
					$href = $isi['href'];
					$hint = $isi['hint'];
					$title = $isi['title'];
					$styleMenu =  $isi['kode'] == $parent.$value.'.' ?   "style='color:blue;'" : "style='color:red;'";					
					$arrMenu[] = " <a href='$href' title='$hint' $styleMenu>$title</a> ";			
				}				
				$menu .= "<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>". 
					join(" | ", $arrMenu) .
					"</td></tr>";
				
			}
			//set next
			$parent .= $value.'.';
			$level ++;				
		}
		if ($menu <> '' ){
			$menu = 
				 "<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"> ".
				 $menu.
				 "</table><tes_$kode></tes_$kode>";	
		}
		
		
		
   		return $menu;
	}
	
	function tes($s){
		return $s;
	}
     
}
$Menu = new MenuObj();

?>