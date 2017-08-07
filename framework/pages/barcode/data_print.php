<?php
$uid = $_GET['id'];
$cek = ''; $err='';
$recs = array();
$cidBI = $_REQUEST["cidBI"];
$aqry1 = "select * from barcode_data_print where uid='$uid'";	
$qry1 = mysql_query($aqry1);
while ($isi = mysql_fetch_array($qry1)){
	$aqry = "select * from view_buku_induk2 where id='{$isi['idbi']}'"; // $cek .= $aqry;
	//echo $aqry;
	$qry = mysql_query($aqry);
	while ($row = mysql_fetch_array($qry)){		
		
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
		$kd = $row['a1'].'.'.$row['a'].'.'.$row['b'].'.'.$row['c'].'.'.$row['d'].'.'.substr($row['thn_perolehan'],2,2).'.'.$row['e'].'.'.
			$row['f'].'.'.$row['g'].'.'.$row['h'].'.'.$row['i'].'.'.$row['j'];
		$recs[] = array(
				'bidang'=>$nmbidang_barcode, 
				'opd'=>$nmopd_barcode,
				'biro'=>$nmunit_barcode,
				'kode'=>$kd,
				'barang'=>$row['nm_barang']
				//'tahun'=>$row['thn_perolehan']
		);
	}
	
				
}						
//write data --------------------------
if(count($recs)>0){
	//$domtree = new DOMDocument('1.0');
	/*$domtree = new DOMDocument('1.0', 'UTF-8');
	$domtree->formatOutput = true;
	$dom->preserveWhiteSpace = false;
	$xmlRoot = $domtree->createElement("xml");
	$xmlRoot = $domtree->appendChild($xmlRoot);
	*/
	/*header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");*/
	header('Content-type: text/xml; charset=utf-8;' );
	
	echo "<xml>";
	foreach($recs as $rec){
	  //create a rec element
	  //$recTag = $xmlRoot->appendChild( $domtree->createElement("rec"));
	  	echo "<rec>";         
	  	echo "<bidang>". $rec['opd']."</bidang>";
		echo "<opd>". $rec['bidang']."</opd>";
		echo "<biro>". $rec['biro']."</biro>";
		echo "<barang>". $rec['barang']."</barang>";
		echo "<kode>". $rec['kode']."</kode>";
		
	  
	  /*$recTag->appendChild(  $domtree->createElement("bidang", $rec['bidang']));
	  $recTag->appendChild(  $domtree->createElement("opd", $rec['opd']));
	  $recTag->appendChild(  $domtree->createElement('biro', $rec['biro']));
	  $recTag->appendChild(  $domtree->createElement('barang', $rec['barang']));
	  $recTag->appendChild(  $domtree->createElement('kode', $rec['kode']));
	  */
	  
	  
	  echo "</rec>";         
	  
	}
	echo "</xml>";

//echo $domtree->saveXML();
}


    /* get the xml printed */
    //

?>