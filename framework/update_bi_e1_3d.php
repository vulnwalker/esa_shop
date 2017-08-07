<?PHP

include 'config.php';
echo "/*<br>";
echo "CEK KIB A<br>";
$sqry = "select * from v_cek_kiba_dbl where jml>1 ";
$qry = mysql_query($sqry);
echo "*/<br>";
$cnt =0;
while ($isi = mysql_fetch_array($qry)){
$cnt++;
$cntx=0;
	$kondisi=" and a1='".$isi['a1']."' ".
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
 	" and tahun='".$isi['tahun']."' ".
	" and noreg='".$isi['noreg']."' ".
 
 	$kodebrg=$cnt.".  ".$isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'].".".
	$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j']." ---- ".$isi['jml'];
	echo "/*<br>";
	echo $kodebrg."<br>";
	echo "-------------------------------------------<br>";
	echo "*/<br>";
	
	$xqry = "select * from kib_a where a1<>'' $kondisi ";
	$delqry=mysql_query($xqry);
	$old_bi=0;
	while ($delisi = mysql_fetch_array($delqry)){
	$cntx++;
 	$kodebrgdel=$cntx.".  ".$delisi['id']."-". $delisi['a1'].".".$delisi['a'].".".$delisi['b'].".".$delisi['c'].".".
	$delisi['d'].".".$delisi['e'].".".	$delisi['e1'].".".	$delisi['f'].".".$delisi['g'].".".$delisi['h'].".".$delisi['i'].
	".".$delisi['j']." ---- ".$delisi['idbi'];

	echo "/*<br>";
	echo $kodebrgdel."<br>";
	echo "*/<br>";

	if ($cntx==1) $old_bi=$delisi['idbi'];
	if ($cntx==2 && $old_bi!=$delisi['idbi'] )
	{
		$qrydelkib="delete kib_a where id='".$delisi['id']."'";
		$qrydelbi="delete buku_induk where id='".$delisi['idbi']."'";
		echo $qrydelkib.";<br>";
		echo $qrydelbi.";<br>";

	  // del bi dan kib	
	} 
	
	
	
	
	
	
	}



}


?>