<?php
function cekektra($f='00',$g='00',$h='00',$i='00',$j='000',$fmharga)
{
	global $Main;
	$jml_intra=0;
	$stextra=false;
	
	for ($x = 0; $x < count($Main->KondisiEkstra); $x++) {
		if ($Main->KondisiEkstra[$x][0]==$f)
		{
			$jml_intra=$Main->KondisiEkstra[$x][5];
			if ($Main->KondisiEkstra[$x][1]==$g)
			{
				$jml_intra=$Main->KondisiEkstra[$x][5];
				if ($Main->KondisiEkstra[$x][2]==$h)
				{
					$jml_intra=$Main->KondisiEkstra[$x][5];
					if ($Main->KondisiEkstra[$x][3]==$i)
					{
						$jml_intra=$Main->KondisiEkstra[$x][5];
						if ($Main->KondisiEkstra[$x][4]==$j)
						{
							$jml_intra=$Main->KondisiEkstra[$x][5];
						}
						
					}
					
				}
				
			}


		}
	
	}

	$errmsg = "Ektrakomptable untuk kode barang $f.$g.$h.$i.$j harga perolehan harus lebih kecil dari ".number_format_ribuan($jml_intra,false);
	
	if ($jml_intra>0){
		
		if ($fmharga<$jml_intra) {
		$stextra=true;
		$errmsg='';

		}
		
	} 

/*	
	if ($f=='02' or $f=='05')
	{
		if ($fmharga<$jml_intra) {
		$stextra=true;
		$errmsg='';

		}
	}
*/	
	
	return $errmsg;
}

function make_to_ektra($f='00',$g='00',$h='00',$i='00',$j='00',$fmharga,$staset){
	global $Main;
	$jml_intra=0;
	$stasetx=$staset;
	
	
	
	for ($x = 0; $x < count($Main->KondisiEkstra); $x++) {
		if ($Main->KondisiEkstra[$x][0]==$f)
		{
			$jml_intra=$Main->KondisiEkstra[$x][5];
			if ($Main->KondisiEkstra[$x][1]==$g)
			{
				$jml_intra=$Main->KondisiEkstra[$x][5];
				if ($Main->KondisiEkstra[$x][2]==$h)
				{
					$jml_intra=$Main->KondisiEkstra[$x][5];
					if ($Main->KondisiEkstra[$x][3]==$i)
					{
						$jml_intra=$Main->KondisiEkstra[$x][5];
						if ($Main->KondisiEkstra[$x][4]==$j)
						{
							$jml_intra=$Main->KondisiEkstra[$x][5];
						}
						
					}
					
				}
				
			}


		}
	
	}
	
	if ($jml_intra>0){
	
		if ($fmharga<$jml_intra) {
		$stasetx=10;


		}	
	}
	
	/*
	if ($f=='02' or $f=='05')
	{
	  		if ($fmharga<$jml_intra) {
			$stasetx=10;

		}
	
	}
	*/
 return $stasetx;	
	
}

?>