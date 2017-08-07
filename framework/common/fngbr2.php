<?php

class GambarObj extends DaftarObj2{
	var $SHOW_CEK = FALSE;
	function set_selector_other($tipe){		
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//$tgl_sensus = $_REQUEST['tgl_sensus'];
		switch($tipe){
			case 'getdata':{
				$idbi = $_REQUEST['idbi'];
				
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where idall='$idbi' limit 0,1" ));
				$id = $bi['idawal'];
				$aqry = "select * from gambar where idbi ='".$id."'";
				$qry = mysql_query($aqry);
				$rows=array();
				while ($isi = mysql_fetch_assoc($qry)){
					$rows[] = $isi;
				}
				$cek .=  "select * from buku_induk where idall='$idbi' limit 0,1";
				$cek .= "select * from gambar where idbi ='".$bi['id']."'";
				
				//$content->rows[] = array('name'=> '20121102_617187_881305435.jpg' );
				//$content->rows[] = array('name'=> '20121120_10254_1621872527.jpg' );
				$content->rows = $rows;
				break;
			}
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
};
$Gbr = new GambarObj();

?>