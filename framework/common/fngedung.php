<?php

class GedungObj  extends DaftarObj2{	
	function set_selector_other($tipe){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formPilih':{
				$fm = $this->setFormPilih();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$json = $fm['json'];
				$content = $fm['content'];												
				break;
				
			}
			case 'getdata':{				
				//*
				$kondisi =" where q='0000' "; 
				$limit = '';
				$aqry = "select * from ref_ruang $kondisi $limit ";
				$qry = mysql_query($aqry);
				while ($isi = mysql_fetch_array($qry) ){
					$content .= "<option value='".$isi['p']."'>".$isi['nm_ruang']."</option>"	;
				}
				//*/
				//$content ="<option value='1'>Gedung1</option>"."<option value='2'>Gedung2</option>";
				//$err='cek';
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
$gedung = new GedungObj();

?>