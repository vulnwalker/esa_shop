<?php    
 
//print barcode ---------------------------
$recs = array();
$cek = ''; $err='';
$UID = $_COOKIE['coID'];
$cidBI = $_REQUEST["cidBI"];
//insert data
if( count($cidBI) > 0){
	//pastikan hapus dulu ygsebelumnya
	$aqry = "delete from barcode_data_print where uid='$UID'";  $cek .= $aqry;	
	$qry = mysql_query($aqry);	
	
	$cek .=' count cidbi='.count($cidBI);
	for($i = 0; $i<count($cidBI); $i++)	{		
		//insert
		$aqry = "insert into barcode_data_print (uid,idbi) values ('$UID', '{$cidBI[$i]}');";  $cek .= $aqry;	
		$qry = mysql_query($aqry);	
		if($qry == FALSE) break;
		
		
		$aqry2 = "select * from view_buku_induk2 where id='{$cidBI[$i]}'"; // $cek .= $aqry;	
		$qry2 = mysql_query($aqry2);
		while ($row = mysql_fetch_array($qry2)){		
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$row['c']."'  and d='00' and e='00'"
			));
			$nmbidang_barcode = $get['nm_barcode'];
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$row['c']."'  and d='".$row['d']."' and e='00'"
			));
			$nmopd_barcode = $get['nm_barcode'];
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$row['c']."'  and d='".$row['d']."' and e='".$row['e']."'"
			));
			$nmunit_barcode = $get['nm_barcode'];			
			$recs[] = array(
				'bidang'=>$nmbidang_barcode, 
				'opd'=>$nmopd_barcode,
				'biro'=>$nmunit_barcode,
				'kode'=>$row['idall2'],
				'barang'=>$row['nm_barang']
			);
		}
		
	}
}		


//write data --------------------------
$domtree = new DOMDocument('1.0', 'UTF-8');
    $xmlRoot = $domtree->createElement("xml");
    $xmlRoot = $domtree->appendChild($xmlRoot);
foreach($recs as $rec){
  //create a rec element
  $recTag = $xmlRoot->appendChild( $domtree->createElement("rec"));
           
  /*
  //create rec attribute
  $recTag->appendChild(  $domtree->createAttribute("tglcetak"))->appendChild(  $domtree->createTextNode($rec['tglcetak']);
*/

  
  $recTag->appendChild(  $domtree->createElement("bidang", $rec['bidang']));
  $recTag->appendChild(  $domtree->createElement("opd", $rec['opd']));
  $recTag->appendChild(  $domtree->createElement('biro', $rec['biro']));
  $recTag->appendChild(  $domtree->createElement('barang', $rec['barang']));
  $recTag->appendChild(  $domtree->createElement('kode', $rec['kode']));
  $recTag->appendChild(  $domtree->createElement('tahun', $rec['tahun']));
  
  
  
}



/* get the xml printed */
//echo $domtree->saveXML();
$dir = 'barxml2011/';
$fname = $dir.$UID.'.xml'; //$cek .= $fame;
//$domtree->save($fname);
$domtree->save($fname);


//update status			
if ($qry){ 
	$aqry = "replace into barcode_status_print (uid,status,qty) values('$UID',1,1);"; $cek .=$aqry;
	$qry = mysql_query($aqry);
}

$pageArr = array(
	'cek'=>$cek, 'err'=>$err 
);
$page = json_encode($pageArr);	
echo $page;


?>