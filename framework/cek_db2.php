<?php
//cari data BI yang tidak punya kib
//error_reporting(1);
set_time_limit(0);
include("common/vars.php"); 
include("config.php");
//include("common/fnfile.php");

/*
$f='02';
$tblkib='kib_b';

$sql= 'select * from buku_induk where f="'.$f.'"';
$qry=mysql_query($sql);

$i=1;
while($row = mysql_fetch_array($qry) ){
	$skib= 'select * from '.$tblkib.' where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.
				$row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].
				$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'].$row['noreg'].'"';
	//echo $skib.'<br>';
	$kib=mysql_query($skib);
	if(!$det=mysql_fetch_array($kib)){
		echo $i.' '.$row['id'].' '.$row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].
				$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'].$row['noreg'].'<br>';
		$i++;
		
	}
}
*/

function getMaxNoReg($tblname, $kondisi){
	$sql = "select max(noreg) as maxno from $tblname where
       concat(a1,a,b,c,d,e,f,g,h,i,j,tahun) = '".$kondisi."'"; 
	 //echo $sql.'<br>';
	   
	$qry = mysql_query($sql); 
	
	$maxno=0;
	while ($row= mysql_fetch_array($qry)){
		$maxno = $row['maxno'];
	};
	
	return $maxno;
	
}

function updatenoregbi( $newnoreg, $idbi ){
	$sql = "update buku_induk
				set noreg=$newnoreg
			where  id='$idbi'";//echo $sql.'<br>';
	 mysql_query($sql);
}

function updatenoregkib( $newnoreg, $idbi, $f ){
	$tblname='';
	switch($f){
		case '01': $tblname='kib_a'; break;
		case '02': $tblname='kib_b'; break;
		case '03': $tblname='kib_c'; break;
		case '04': $tblname='kib_d'; break;
		case '05': $tblname='kib_e'; break;
		case '06': $tblname='kib_f'; break;
	}
	if ($tblname != ''){
		$sql = "update $tblname
				set noreg=$newnoreg
			where  idbi='$idbi'"; //echo $sql.'<br>';
	 	mysql_query($sql);	
	}
	
}

function dataygdouble($tblname, $fix=0, $batas=20, $viewdet=1){ /*cek double*/
	//$batas = 50;
	//get count  ---------------
	/*$sql = ' select a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg,count(id) as cnt from '.$tblname.' 
			group by a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg 
			order by a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg';*/
	$sql = "select * from v_cek_bicnt where cnt>1";
	$jmlerr = mysql_num_rows(mysql_query($sql));
	echo "jmlerr=$jmlerr batas=$batas fix=$fix<br>";
	$cnt=0; $str='';
	$qry = mysql_query($sql);
	while($row= mysql_fetch_array($qry)){
		if($row['cnt'] > 1 && $cnt <=$batas  ){
			//ambil data count > 0 -------------------
			$cnt +=1;					
			
			
			
			//cari maxno utk id tersebut -------------------
			$kondisi = $row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'];			
			$maxno = getMaxNoReg( 'buku_induk', $kondisi );//maxno jadikan integer
			
			//cek apakah max kurang dari 4 digit, jika ya maka noreg tidak bisa diganti 
			//if ((strlen($maxno) == 4) && ( ($maxno)+$row['cnt']<9999 )){
			if ((strlen($maxno) == 4) && ( ($maxno+0)+$row['cnt']<9999 )){
				$FAIL = 0;
			}else {
				$FAIL =  1;	
			}
			
			//tampil info yg double -------------------
			echo  $cnt.' '.$row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].
					$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'].$row['noreg'].' cnt='.$row['cnt'].
					" maxno=$maxno fail=$FAIL <br>";
			
			//cari kode kib
			$f=$row['f'];
			
			
			//tampil detail yg double -----------
			if ($viewdet==1){		
				//ambil detal noreg yg double, diurut berdasar tgl update menurun		
				$sql2 = 'select * from buku_induk where
       					concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) =       
       						"'.$row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].
							$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'].$row['noreg'].'"
							order by tgl_update desc ';
				$qry2 = mysql_query($sql2);
				$j=0;
				while($row2= mysql_fetch_array($qry2)){
					$str = '&nbsp;&nbsp;&nbsp;- '.$row2['id'].' '.$row2['tgl_update'].' '.$row2['uid'];
					
					
					/* -------------------
					//fix : geser noreg
					$sno='';
					if($j>0 && $fix==1 && $tblname=='buku_induk' && $FAIL==0){ 
						//fix -------------------------------------------
						//$row['a1'].$row['a'].$row['b'].$row['c'].$row['d'].$row['e'].$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].$row['tahun'].$row['noreg'] ;
						$idbi=$row2['id'];
						$tmp = ($maxno+10000+$j)."";
						$newno = substr($tmp,1,4)."";
						$sno='  -> noreg = '.$newno;
						updatenoregbi(  $newno, $idbi );
						updatenoregkib(  $newno, $idbi, $f );
						
						$str .= ' fix';
					}
					*/
					
					//fix : simpan noreg dgn tglupdate terbaru dan hapus noreg lainnya berikut kibnya
					if($j>0 && $fix==1 && $tblname=='buku_induk' && $FAIL==0){ 						
						mysql_query("delete from buku_induk where  id=".$row2['id']);
						switch($row2['f']){
							case '01': mysql_query("delete from kib_a where  idbi=".$row2['id']);break;
							case '02': mysql_query("delete from kib_b where  idbi=".$row2['id']);break;
							case '03': mysql_query("delete from kib_c where  idbi=".$row2['id']);break;
							case '04': mysql_query("delete from kib_d where  idbi=".$row2['id']);break;
							case '05': mysql_query("delete from kib_e where  idbi=".$row2['id']);break;
							case '06': mysql_query("delete from kib_f where  idbi=".$row2['id']);break;
						}
						$str .= ' deleted';
					}
					
					
					echo $str.'<br>';
				
					
					$j++;
				}
			}
		}
		
		
	}
	//return $str;
}

function cek_noreg($fix=0, $batas=1){
	//cek no reg yg tidak 4 Digit
	
	//$batas= 1;
	$sqry = 'select * from v_cek_noreg ';
	$jmlerr = mysql_num_rows(mysql_query($sqry));
	echo "jmlerr=$jmlerr batas=$batas fix=$fix <br>";
	$qry = mysql_query($sqry." limit 0,$batas");
	$no=0;
	while ($isi=mysql_fetch_array($qry) ){
		 
		$no++;
		//tampil
		
		echo "$no. {$isi['idbrg']} KIB={$isi['f']}";
		
		//generate noreg yg disarankan
		$noreg= $isi['noreg'];
		$newnoreg = substr(($noreg+10000)."",1,4);
		echo " noreg=$noreg newnoreg=$newnoreg ";
		
		//cek newnoreg sudah ada di buku_induk
		$selainnoreg = $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].
				$isi['thn_perolehan'];
		$newidbrg= $selainnoreg.$newnoreg; 
		switch ($isi['f']) {
			case '01':$tblkib='kib_a';break;
			case '02':$tblkib='kib_b';break;
			case '03':$tblkib='kib_c';break;
			case '04':$tblkib='kib_d';break;
			case '05':$tblkib='kib_e';break;
			case '06':$tblkib='kib_f';break;
		}
		if (  ( table_get_value("select count(id) as cnt from buku_induk 
				where concat(a1,a,b,c,d,e,f,g,h,i,j,thn_perolehan,noreg)='$newidbrg'",
			 	"cnt") == 0 ) 
			 &&			
			  ( table_get_value("select count(id) as cnt from $tblkib 
				where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg)='$newidbrg'",
			 	"cnt") == 0 )
		){		 
			$canfix = 1;		 		
		}else{
			$canfix = 0;
		}
		if ($fix==1 && $canfix==1){
			mysql_query("update buku_induk set noreg ='$newnoreg' 
				where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg)='$selainnoreg$noreg'");
			$str = "update $tblkib set noreg ='$newnoreg' 
				where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg)='$selainnoreg$noreg'";
			//echo "<br>$str<br>";
			mysql_query( $str);
			
			echo " fixed ";
		}else{
			echo " fail ";
		}
		
		echo "<br>";
		 
	}
	
	
}

function tampilbarangyggaada(){
	$sql = 'select aa.f, aa.g, aa.h, aa.i, aa.j, bb.nm_barang
				from buku_induk aa left join ref_barang bb 
			on
  				aa.f = bb.f and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i and aa.j =bb.j
			where  bb.nm_barang is null';
	$qry = mysql_query($sql);
	$hsl='';
	while($row= mysql_fetch_array($qry)){
	
		$hsl .= ' '.$row['f'].' '.$row['g'].' '.$row['h'].' '.$row['i'].' '.$row['j'].'<br>';
		
	}
	return $hsl;
}

function tampilOPDyggaada(){
	$sql = 'select aa.c, aa.d, aa.e, bb.nm_skpd
				from buku_induk aa left join ref_skpd bb 
			on
  				aa.c = bb.c and aa.d=bb.d and aa.e=bb.e 
			where  bb.nm_skpd is null';
	$qry = mysql_query($sql);
	$hsl='';
	$no=0;
	while($row= mysql_fetch_array($qry)){
		$no++;
		$hsl .= $no.'. '.$row['c'].' '.$row['d'].' '.$row['e'].'<br>';
		
	}
	return $hsl;
}

//function fixdetailyggaada($idF, $tbldet){
//}

function tampildetailyggaada($idF,$tbldet, $fix=0){
	$sql= 'select aa.id as id,  aa.a1 as a1, aa.a as  a, aa.b as b, aa.c as c, aa.d as d, aa.e as e, aa.f as f, aa.g as g, aa.h as h, aa.i as i, aa.j as j,  
			aa.tahun as tahun, aa.noreg as noreg, aa.jml_harga as jml_harga, bb.id as iddet
				from buku_induk aa left join '.$tbldet.' bb 
			on
   				aa.a1=bb.a1 and aa.a=bb.a and aa.b=bb.b and aa.c=bb.c and aa.d=bb.d  
   				and aa.e=bb.e and aa.f=bb.f  and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i  and aa.j=bb.j
   				and aa.tahun=bb.tahun and aa.noreg=bb.noreg    

			where aa.f='.$idF.' and bb.id is null
 			order by aa.id, aa.a1, aa.a, aa.b, aa.c, aa.d, aa.e, aa.f, aa.g, aa.h, aa.i, aa.j';
	$qry = mysql_query($sql);
	$cnt=0;
	$hsl='';
	while($row= mysql_fetch_array($qry)){
		$cnt +=1;
		
		$sfix='';
		
		if ($fix==1){	
			//fix			
			$sql2="insert into $tbldet (a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg,idbi) values 
				('".$row['a1']."',
				'".$row['a']."',
				'".$row['b']."',	
				'".$row['c']."',
				'".$row['d']."',
				'".$row['e']."',
				'".$row['f']."',
				'".$row['g']."',
				'".$row['h']."',
				'".$row['i']."',
				'".$row['j']."',
				'".$row['tahun']."',
				'".$row['noreg']."',
				".$row['id']."
				  ) ";
			//echo $sql2.'<br>';
			mysql_query($sql2);
			$sfix = ' fix ';
		}
		
		$hsl .= $cnt.'. '.$row['id'].' '.$row['a1'].$row['a'].$row['b'].' '.
					$row['c'].$row['d'].$row['e'].' '.
					$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].' '.
					$row['tahun'].' '.$row['noreg'].' '.$sfix.
				'<br>';	
		
		
		
	}
	return $hsl;
}

function tampilBIyggaada($idF,$tbldet){
	$sql= 'select bb.id,  bb.a1, bb.a, bb.b, bb.c, bb.d, bb.e, bb.f, bb.g, bb.h, bb.i, bb.j,  
			bb.tahun, bb.noreg, aa.jml_harga, bb.id, bb.tahun, bb.noreg
				from buku_induk aa right join '.$tbldet.' bb 
			on
   				aa.a1=bb.a1 and aa.a=bb.a and aa.b=bb.b and aa.c=bb.c and aa.d=bb.d  
   				and aa.e=bb.e and aa.f=bb.f  and aa.g=bb.g and aa.h=bb.h and aa.i=bb.i  and aa.j=bb.j
   				and aa.tahun=bb.tahun and aa.noreg=bb.noreg    

			where bb.f='.$idF.' and aa.id is null
 			order by aa.id, aa.a1, aa.a, aa.b, aa.c, aa.d, aa.e, aa.f, aa.g, aa.h, aa.i, aa.j';
	$qry = mysql_query($sql);
	$cnt=0;
	$hsl='';
	while($row= mysql_fetch_array($qry)){
		$cnt +=1;
		$hsl .= $cnt.'. '.$row['id'].' '.$row['a1'].$row['a'].$row['b'].' '.
					$row['c'].$row['d'].$row['e'].' '.
					$row['f'].$row['g'].$row['h'].$row['i'].$row['j'].' '.
					$row['tahun'].' '.$row['noreg'].
				'<br>';				
	}
	return $hsl;
}

if(CekLogin()){

/************** cek double ********************/
$fixnoreg = $_GET['fixnoreg'];
$fixdbl = $_GET['fixdbl'];
$nocekdbl = $_GET['nocekdbl'];
$nocekreg= $_GET['noceknoreg'];
$batas = empty($_GET['batas'])? 10: $_GET['batas'];

/*
echo "no cek noreg: $noceknoreg <br>";
echo "no cek double: $nocekdbl <br>";
echo "fix noreg: $fixnoreg <br>";
echo "fix double: $fixdbl <br>";
echo "batas: $batas <br>";

if (empty($noceknoreg)){
	echo '>cek noreg tidak 4 digit: <br>';
	cek_noreg($fixnoreg, $batas);
	echo '<br>';
}


if (empty($nocekdbl)){
	echo '> cek double : <br>';
	echo ' - buku induk <br>';
	echo dataygdouble('buku_induk',$fixdbl, $batas);
	echo '<br>';
}



echo ' - kib a <br>'.dataygdouble('kib_a').'<br>';
echo ' - kib b <br>'.dataygdouble('kib_b').'<br>';
echo ' - kib c <br>'.dataygdouble('kib_c').'<br>';
echo ' - kib d <br>'.dataygdouble('kib_d').'<br>';
echo ' - kib e <br>'.dataygdouble('kib_e').'<br>';
echo ' - kib f <br>'.dataygdouble('kib_f').'<br>';


//jml barang > 1 atau harga <> jml_harga

//====== cari barang yg ga adaa ================
echo '<br>> Barang Yang Ga ada: <br>';
echo tampilbarangyggaada();

//====== cari OPD yg ga adaa ================
echo '<br>> OPD Yang Ga ada: <br>';
echo tampilOPDyggaada();
	
//======= cari detail yg ga ada =============
echo '<br>> Detail yg ga ada : <br>';
echo 'kib a <br>'.tampildetailyggaada('01','kib_a');
echo 'kib b <br>'.tampildetailyggaada('02','kib_b');
echo 'kib c <br>'.tampildetailyggaada('03','kib_c');
echo 'kib d <br>'.tampildetailyggaada('04','kib_d');
echo 'kib e <br>'.tampildetailyggaada('05','kib_e');
echo 'kib f <br>'.tampildetailyggaada('06','kib_f');

//======= cari BI yg ga ada =============
echo '<br>> BI yg ga ada : <br>';
echo 'kib a <br>'.tampilBIyggaada('01','kib_a');
echo 'kib b <br>'.tampilBIyggaada('02','kib_b');
echo 'kib c <br>'.tampilBIyggaada('03','kib_c');
echo 'kib d <br>'.tampilBIyggaada('04','kib_d');
echo 'kib e <br>'.tampilBIyggaada('05','kib_e');
echo 'kib f <br>'.tampilBIyggaada('06','kib_f');
*/
echo 'kib e <br>'.tampildetailyggaada('05','kib_e',1);


}else{
	echo 'Anda Belum Login!';	
}


?>