<?php    
 
 
//function genrec( bidang, opd, biro, kode, tahun, tgl)
	

	
//get data ---------------------------
/*$recs = array(
	array('bidang'=>'bidang1', 'opd'=>'opd1','biro'=>'biro1','kode'=>'kode1','tahun'=>'tahun1'),
	array('bidang'=>'bidang2', 'opd'=>'opd2','biro'=>'biro2','kode'=>'kode2','tahun'=>'tahun2')
);*/

$UID = $_COOKIE['coID'];

$cek = ''; $err='';
$recs = array();

$cidBI = $_REQUEST["cidBI"];
if( count($cidBI) > 0){
	for($i = 0; $i<count($cidBI); $i++)	{
		$aqry = "select * from view_buku_induk2 where id='{$cidBI[$i]}'"; // $cek .= $aqry;
		//echo $aqry;
		$qry = mysql_query($aqry);
		while ($row = mysql_fetch_array($qry)){		
			$recs[] = array(
				'bidang'=>$row['nmbidang'], 
				'opd'=>$row['nmopd'],
				'biro'=>$row['nmunit'],
				'kode'=>$row['idall2'],
				'barang'=>$row['nm_barang'],
				'tahun'=>$row['thn_perolehan']
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
	$fname = $dir.'bar_'.$UID.'.xml'; //$cek .= $fame;
	$domtree->save($fname);


$pageArr = array(
	'cek'=>$cek, 'err'=>$err 
);
$page = json_encode($pageArr);	
echo $page;

/*
<?xml version="1.0" encoding="UTF-8"?>
<xml> 
<rec>
     <bidang>SEKRETARIAT DAERAH</bidang>
     <opd>Sekretariat Daerah</opd>
     <biro>Tata Usaha Asisten 1</biro>
    <barang>Meja Kayu/Rotan</barang>
    <kode>11.10.00.04.01.08.01.02.06.02.01.04.0001</kode>
    <tahun>2008</tahun>
 </rec>
<rec>
     <bidang>SEKRETARIAT DAERAH</bidang>
     <opd>Sekretariat Daerah</opd>
     <biro>Tata Usaha Asisten 1</biro>
    <barang>Meja Kayu/Rotan</barang>
   
 <kode>11.10.00.04.01.08.01.02.06.02.01.04.0002</kode>
    <tahun>2008</tahun>
 </rec>
<xml>

*/

?>