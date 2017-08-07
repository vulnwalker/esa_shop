<?php


include 'config.php';
echo 'Ganti  Kode BI e1=01 jadi e1=001 <br>';
$sqry = "select * from buku_induk 
 where length(e1)<3  
 order by a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg";
$qry=mysql_query($sqry);
$no = 0;
echo $sqry."<br>";
while ($isi = mysql_fetch_array($qry)){
$no++;
echo "<b>$no</b><br>";
$cekdata=cek_kib($isi);
if ($cekdata==1)	{

 $newisi=$isi;
 $invID = str_pad($isi['e1'], 3, '0', STR_PAD_LEFT);	
 $newisi['e1']=$invID;
 if (cek_bi_kib_new($newisi)==0){
 echo 'update 1<br>';
 	update_bi_kib($isi,$newisi,FALSE);
 	
  } else {
 echo 'update 2<br>';
  	update_bi_kib_newnoreg($isi,$newisi);
  }
 

}
}

function update_bi_kib($isi,$newisi,$wnoreg=FALSE){

	$kondisikib=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ".
	" and f='".$isi['f']."' ".
	" and g='".$isi['g']."' ".
	" and h='".$isi['h']."' ".
	" and i='".$isi['i']."' ".
	" and j='".$isi['j']."' ".
	" and tahun='".$isi['thn_perolehan']."' ".
	" and noreg='".$isi['noreg']."' ";

	$kondisibi=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ".
	" and f='".$isi['f']."' ".
	" and g='".$isi['g']."' ".
	" and h='".$isi['h']."' ".
	" and i='".$isi['i']."' ".
	" and j='".$isi['j']."' ".
	" and thn_perolehan='".$isi['thn_perolehan']."' ".
	" and noreg='".$isi['noreg']."' ";
	
	if ($wnoreg==TRUE){
		$xnoreg=",noreg='".$newisi['noreg']."' ";
	} else {
		$xnoreg="";
	}
	
$updbi="update buku_induk set ".
		"e1='".$newisi['e1']."' ".$xnoreg.
		" where ".$kondisibi;	

	if ($isi['f']=='01'){
		$tablename =" kib_a ";
		
	} else if ($isi['f']=='02') {
		$tablename =" kib_b ";
	} else if ($isi['f']=='03') {
		$tablename =" kib_c ";
	} else if ($isi['f']=='04') {
		$tablename =" kib_d ";
	} else if ($isi['f']=='05') {
		$tablename =" kib_e ";
	} else if ($isi['f']=='06') {
		$tablename =" kib_f ";
	}


	
$updkib="update $tablename set ".
		"e1='".$newisi['e1']."' ".$xnoreg.
		" where ".$kondisikib;
			
$qry1=mysql_query($updbi);
$qry2=mysql_query($updkib);

echo $updbi."<br>";
echo $updkib."<br>";
	
}

function cek_bi_kib_new ($isi){
	
	$kondisi=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ".
	" and f='".$isi['f']."' ".
	" and g='".$isi['g']."' ".
	" and h='".$isi['h']."' ".
	" and i='".$isi['i']."' ".
	" and j='".$isi['j']."' ".
	" and thn_perolehan='".$isi['thn_perolehan']."' ".
	" and noreg='".$isi['noreg']."' ";

	$kondisix=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ".
	" and f='".$isi['f']."' ".
	" and g='".$isi['g']."' ".
	" and h='".$isi['h']."' ".
	" and i='".$isi['i']."' ".
	" and j='".$isi['j']."' ".
	" and thn_perolehan='".$isi['thn_perolehan']."' ";

	$sql="select * from buku_induk where $kondisi ";
	
	$jmlData= mysql_num_rows(mysql_query($sql));
/*
	if ($jmlData>0) 
	{
		$sql1="select max(noreg) as maxnoreg from buku_induk where $kondisix ";
		$qry = mysql_fetch_array(mysql_query($sql1));
		$x=$qry['maxnoreg']+1;
		echo $qry['maxnoreg']." - $x -- ".$sql1."<br>";
	}
	echo "Jml data : $jmlData  --  $sql";
*/	
	return $jmlData;		
}
function   	update_bi_kib_newnoreg($isi,$newisi){

	$kondisix=" a1='".$newisi['a1']."' ".
	" and a='".$newisi['a']."' ".
	" and b='".$newisi['b']."' ".
	" and c='".$newisi['c']."' ".
	" and d='".$newisi['d']."' ".
	" and e='".$newisi['e']."' ".
	" and e1='".$newisi['e1']."' ".
	" and f='".$newisi['f']."' ".
	" and g='".$newisi['g']."' ".
	" and h='".$newisi['h']."' ".
	" and i='".$newisi['i']."' ".
	" and j='".$newisi['j']."' ".
	" and thn_perolehan='".$newisi['thn_perolehan']."' ";


		$sql1="select max(noreg) as maxnoreg from buku_induk where $kondisix ";
		$qry = mysql_fetch_array(mysql_query($sql1));
		$x=$qry['maxnoreg']+1;
		$xnew=$x+10000;
		$xxnew=substr($xnew, -4);
		$newisi['noreg']=$xxnew;
		echo "max noreg=".$qry['maxnoreg'].":".$isi['noreg']." ---> $xxnew -- <br>";
		update_bi_kib($isi,$newisi,TRUE);
	}


	

function cek_kib($isi){
	$xqry =" ";

	$kondisi=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ".
	" and f='".$isi['f']."' ".
	" and g='".$isi['g']."' ".
	" and h='".$isi['h']."' ".
	" and i='".$isi['i']."' ".
	" and j='".$isi['j']."' ".
	" and tahun='".$isi['thn_perolehan']."' ".
	" and noreg='".$isi['noreg']."' ";
	if ($isi['f']=='01'){
		$xqry =" select * from kib_a where $kondisi ";
		
	} else if ($isi['f']=='02') {
		$xqry =" select * from kib_b where $kondisi ";
	} else if ($isi['f']=='03') {
		$xqry =" select * from kib_c where $kondisi ";
	} else if ($isi['f']=='04') {
		$xqry =" select * from kib_d where $kondisi ";
	} else if ($isi['f']=='05') {
		$xqry =" select * from kib_e where $kondisi ";
	} else if ($isi['f']=='06') {
		$xqry =" select * from kib_f where $kondisi ";
	}



		$jml=0;
		$idbi=0;

	$qry = mysql_fetch_array(mysql_query($xqry));
	if ($qry['id']!=''){
		if ($isi['id']==$qry['idbi']){
		$jml=1;
		$idbi=$qry['idbi'];
		}
	} 
	echo $jml." -- ".$xqry."<br>";
return $jml;
}

?>