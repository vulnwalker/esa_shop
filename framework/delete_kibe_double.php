<?PHP

include 'config.php';
$delete = isset($HTTP_GET_VARS["delete"]) ? $HTTP_GET_VARS["delete"] : "";
echo "/*<br>";
echo "CEK KIB E<br>";
$sqry = "select * from v_cek_kibe_dbl where jml>=2 ";
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
	" and noreg='".$isi['noreg']."' ";

	$xqry = "select id,a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg,idbi from kib_e where a1<>'' $kondisi ";
 
 	$kodebrg=$cnt.".  ".$isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['e1'].".".
	$isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'].".".$isi['tahun'].".".$isi['noreg']." -jml-> ".$isi['jml'];
	echo "/*<br>";
	echo $kodebrg."<br>";
	echo "-------------------------------------------<br> $xqry <br>";
	echo "*/<br>";
	
//	echo "$xqry <br>";
	$delqry=mysql_query($xqry);
	$old_bi=0;
	while ($delisi = mysql_fetch_array($delqry)){
	$cntx++;
 	$kodebrgdel=$cntx.".  ".$delisi['id']."-". $delisi['a1'].".".$delisi['a'].".".$delisi['b'].".".$delisi['c'].".".
	$delisi['d'].".".$delisi['e'].".".	$delisi['e1'].".".	$delisi['f'].".".$delisi['g'].".".$delisi['h'].".".$delisi['i'].
	".".$delisi['j'].".".$delisi['tahun'].".".$delisi['noreg']." -idbi--> ".$delisi['idbi'];

	echo "/* ";
	echo $kodebrgdel."  ";
	echo "*/<br>";

//	if ($cntx==1) $old_bi=$delisi['idbi'];
	if ($cntx==2 && $old_bi!=$delisi['idbi'] )
	{
		$qrydelkib="delete from kib_e where id='".$delisi['id']."'";
		$qrydelbi="delete from buku_induk where id='".$delisi['idbi']."'";
		echo $qrydelkib.";<br>";
		echo $qrydelbi.";<br>";
		if ($delete=='ok')
		{
				$delqry1=mysql_query($qrydelkib);
				$delqry2=mysql_query($qrydelbi);

		}		

	  // del bi dan kib	
	} 
	
	
	
	
	$old_bi=$delisi['idbi'];
	
	}



}

	echo "/* selesai */";

?>