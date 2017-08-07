<?php


include 'config.php';


function insert_bi_kib($isi,$newisi,$wnoreg=FALSE,$xlock_f){
global $wexec;

if ($xlock_f=='01')
{
$ins_kib="insert into kib_a (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
luas,alamat,status_hak,sertifikat_tgl,sertifikat_no,ket,tahun )
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['luas'].
"','".$newisi['alamat']."','".$newisi['status_hak']."','".$newisi['sertifikat_tgl']."','".$newisi['sertifikat_no'].
"','".$newisi['ket']."','".$newisi['tahun']."')";
	
} else if ($xlock_f=='02')
{
$ins_kib="insert into kib_b (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,merk,
ukuran,bahan,no_pabrik,no_rangka,
no_mesin,no_polisi,no_bpkb,ket,tahun)
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['merk'].
"','".$newisi['ukuran']."','".$newisi['bahan']."','".$newisi['no_pabrik']."','".$newisi['no_rangka'].
"','".$newisi['no_mesin']."','".$newisi['no_polisi']."','".$newisi['no_bpkb']."','".$newisi['ket']."','".$newisi['tahun']."')";
	
} else if ($xlock_f=='03')
{
$ins_kib="insert into kib_c (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
kondisi_bangunan,konstruksi_tingkat,konstruksi_beton,luas_lantai,alamat,luas,status_tanah,ket,tahun )
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['kondisi_bangunan'].
"','".$newisi['konstruksi_tingkat']."','".$newisi['konstruksi_beton']."','".$newisi['luas_lantai']."','".$newisi['alamat'].
"','".$newisi['luas']."','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['tahun']."')";	
} else if ($xlock_f=='04')
{
$ins_kib="insert into kib_d (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
konstruksi,panjang,lebar,luas,alamat,status_tanah,ket,tahun)
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['konstruksi'].
"','".$newisi['panjang']."','".$newisi['lebar']."','".$newisi['luas']."','".$newisi['alamat'].
"','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['tahun']."')";	
} else if ($xlock_f=='05')
{
$ins_kib="insert into kib_e (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,ket,tahun)
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['buku_judul'].
"','".$newisi['buku_spesifikasi']."','".$newisi['seni_asal_daerah']."','".$newisi['seni_pencipta']."','".$newisi['seni_bahan'].
"','".$newisi['hewan_jenis']."','".$newisi['hewan_ukuran']."','".$newisi['ket']."','".$newisi['tahun']."')";
	
} else if ($xlock_f=='06')
{
$ins_kib="insert into kib_f (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
bangunan,konstruksi_tingkat,konstruksi_beton,luas,alamat,status_tanah,ket,tahun)
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['bangunan'].
"','".$newisi['konstruksi_tingkat']."','".$newisi['konstruksi_beton']."','".$newisi['luas']."','".$newisi['alamat'].
"','".$newisi['status_tanah']."','".$newisi['ket']."','".$newisi['tahun']."')";
	
} else if ($xlock_f=='07')
{
$ins_kib="insert into kib_g (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,
buku_judul,buku_spesifikasi,seni_asal_daerah,seni_pencipta,seni_bahan,hewan_jenis,hewan_ukuran,ket,uraian,pencipta,tahun)
values ('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['buku_judul'].
"','".$newisi['buku_spesifikasi']."','".$newisi['seni_asal_daerah']."','".$newisi['seni_pencipta']."','".$newisi['seni_bahan'].
"','".$newisi['hewan_jenis']."','".$newisi['hewan_ukuran']."','".$newisi['ket']."','".$newisi['uraian']."','".$newisi['pencipta']."','".$newisi['tahun']."')";
	
}

$ins_bi="insert into buku_induk (a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan,
jml_barang,satuan,harga,jml_harga,asal_usul,kondisi,status_barang,tahun,tgl_buku,staset,verifikasi,harga_beli,no_ba,tgl_ba,no_spk,tgl_spk)
values('".$newisi['a1']."','".$newisi['a']."','".$newisi['b']."','".$newisi['c'].
"','".$newisi['d']."','".$newisi['e']."','".$newisi['e1']."','".$newisi['f']."','".$newisi['g'].
"','".$newisi['h']."','".$newisi['i']."','".$newisi['j']."','".$newisi['noreg']."','".$newisi['thn_perolehan'].
"','".$newisi['jml_barang']."','".$newisi['satuan']."','".$newisi['harga']."','".$newisi['jml_harga'].
"','".$newisi['asal_usul']."','".$newisi['kondisi']."','".$newisi['status_barang'].
"','".$newisi['tahun']."','".$newisi['tgl_buku']."','".$newisi['staset']."','".$newisi['verifikasi']."','".$newisi['jml_harga'].
"','".$newisi['no_ba']."','".$newisi['tgl_ba']."','".$newisi['no_spk']."','".$newisi['tgl_spk']."')";




echo $ins_bi." -- $xlock_f<br>";
echo $ins_kib." -- $xlock_f<br>";
if ($wexec=='1')
{

$qry1=mysql_query($ins_bi);
$qry2=mysql_query($ins_kib);

}
}


function update_idbi_idlama($xlock_f)
{
$upd_bi="update buku_induk set idawal=id where idawal is null";
$qry1=mysql_query($upd_bi);
if ($xlock_f=='01')
{
$upd_kib="UPDATE
  `kib_a` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
} else if ($xlock_f=='02')
{
$upd_kib="UPDATE
  `kib_b` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
} else if ($xlock_f=='03')
{
$upd_kib="UPDATE
  `kib_c` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
} else if ($xlock_f=='04')
{
$upd_kib="UPDATE
  `kib_d` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
} else if ($xlock_f=='05')
{
$upd_kib="UPDATE
  `kib_e` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
} else if ($xlock_f=='06')
{
$upd_kib="UPDATE
  `kib_f` `a` INNER JOIN
  `buku_induk` `b` ON `a`.`a1` = `b`.`a1` AND `a`.`a` = `b`.`a` AND
    `a`.`b` = `b`.`b` AND `a`.`c` = `b`.`c` AND `a`.`d` = `b`.`d` AND
    `a`.`e` = `b`.`e` AND `a`.`e1` = `b`.`e1` AND `a`.`f` = `b`.`f` AND
    `a`.`g` = `b`.`g` AND `a`.`h` = `b`.`h` AND `a`.`i` = `b`.`i` AND
    `a`.`j` = `b`.`j` AND `a`.`tahun` = `b`.`thn_perolehan` AND
    `a`.`noreg` = `b`.`noreg`
SET `a`.`idbi` = `b`.`id`
where `a`.`f`='$xlock_f' and `a`.`idbi` is null ";
}
$qry1=mysql_query($upd_kib);	
}

function insert_bi_kib_newnoreg($isi,$newisi,$wnoreg=FALSE,$xlock_f){

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
		echo "max noreg=".$qry['maxnoreg'].":".$isi['noreg']." --- $xnew -- $xxnew -- <br>";
		insert_bi_kib($isi,$newisi,TRUE,$xlock_f);


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
		"thn_perolehan='".$newisi['thn_perolehan']."' ".$xnoreg.
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
	} else if ($isi['f']=='07') {
		$tablename =" kib_g ";
	}


	
$updkib="update $tablename set ".
		"tahun='".$newisi['thn_perolehan']."' ".$xnoreg.
		" where ".$kondisikib;
			
// $qry1=mysql_query($updbi);
// $qry2=mysql_query($updkib);

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
		echo "max noreg=".$qry['maxnoreg'].":".$isi['noreg']." ---> $xnew --> $xxnew -- <br>";
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
	} else if ($isi['f']=='07') {
		$xqry =" select * from kib_g where $kondisi ";
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

function bi_kib_b_to_a88($isi)
{
global $wexec;
	$kondisi=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ";


	$sql1=" delete from buku_induk where $kondisi and f='$lock_f' and c='07' ";
	$sql2=" delete from kib_b  where $kondisi and f='$lock_f' and c='07' ";
if ($wexec=='1')
{
echo "$sql1... <br>";
echo "$sql2... <br>";

// $qry1=mysql_query($sql1);
// $qry2=mysql_query($sql2);
 echo "Delete BI & KIB  selesai... <br>";

}
	
}

function cekdata($isi,$xlock_f,$xlock_c='',$xlock_d='',$xlock_e='',$xlock_e1='')
{
 global $tot_err,$cnt_err;	
$sql1="select 
'".$isi[0]."' as dt00, '".$isi[1]."' as dt01, '".$isi[2]."' as dt02, '".$isi[3]."' as dt03, '".$isi[4]."' as dt04, '".$isi[5]."' as dt06, '".$isi[7]."' as dt07,
'".$isi[8]."' as dt08, '".$isi[9]."' as dt09, '".$isi[10]."' as dt10, '".$isi[11]."' as dt11, '".$isi[12]."' as dt12, '".$isi[13]."' as dt13,'".$isi[14]."' as dt14,
'".$isi[15]."' as dt15, '".$isi[16]."' as dt16, '".$isi[17]."' as dt17, '".$isi[18]."' as dt18, '".$isi[19]."' as dt19, '".$isi[20]."' as dt20,'".$isi[21]."' as dt21,
'".$isi[22]."' as dt22, '".$isi[23]."' as dt23, '".$isi[24]."' as dt24, '".$isi[25]."' as dt25, '".$isi[26]."' as dt26, '".$isi[27]."' as dt27,'".$isi[28]."' as dt28
	 ";

$qry=mysql_query($sql1);	
$err_='';
if ($qry) {
	
	if ($isi['f']<>$xlock_f)
	{
	  $err_.='- kode barang tidak sesuai';
	}
	
	if ($xlock_c<>'' && $xlock_c<>$isi['c'] )
	{
	  $err_.='- kode c skpd  tidak sesuai';
		
	}
	if ($xlock_d<>'' && $xlock_d<>$isi['d'] )
	{
	  $err_.='- kode d skpd  tidak sesuai';
		
	}
	if ($xlock_e<>'' && $xlock_e<>$isi['e'] )
	{
	  $err_.='- kode e skpd  tidak sesuai';
		
	}
	if ($xlock_e1<>'' && $xlock_e1<>$isi['e1'] )
	{
	  $err_.='- kode e1 skpd  tidak sesuai';
		
	}
	
	
	
	
	
} else {
	$err_.='id : '.$isi['id'].' - sql data error';
}

$sql2='';

if ($err_=='')
	{
		if ($xlock_f=='01')
		{
 
		$sql2=" update bi_kib_a_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='02')
		{
 
		$sql2=" update bi_kib_b_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='03')
		{
 
		$sql2=" update bi_kib_c_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='04')
		{
 
		$sql2=" update bi_kib_d_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='05')
		{
 
		$sql2=" update bi_kib_e_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='06')
		{
 
		$sql2=" update bi_kib_f_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='07')
		{
 
		$sql2=" update bi_kib_g_tmp set stcheck=1 where id='".$isi['id']."'"; 
		}


	} else {
		
	$tot_err=$tot_err."<b> id=".$isi['id'].", no = ".$isi['no']."</b> --- ".$err_."<br>";

		if ($xlock_f=='01')
		{
 
		$sql2=" update bi_kib_a_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='02')
		{
 
		$sql2=" update bi_kib_b_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='03')
		{
 
		$sql2=" update bi_kib_c_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='04')
		{
 
		$sql2=" update bi_kib_d_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='05')
		{
 
		$sql2=" update bi_kib_e_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='06')
		{
 
		$sql2=" update bi_kib_f_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
		if ($xlock_f=='07')
		{
 
		$sql2=" update bi_kib_g_tmp set stcheck=2,stket='".$err_."'  where id='".$isi['id']."'"; 
		}
	}	
		if ($sql2<>'')
		{
			$qry3=mysql_query($sql2);
		}
		
	

if ($err_<>'') 
{
  $cnt_err++;
  echo $sql1;	
}

return  $qry;
	
}


function delete_bi_kib($isi,$xlock_f,$xlock_c)
{
global $wexec;
	$tablename='';
	if ($xlock_f=='01'){
		$tablename =" kib_a ";
		
	} else if ($xlock_f=='02') {
		$tablename =" kib_b ";
	} else if ($xlock_f=='03') {
		$tablename =" kib_c ";
	} else if ($xlock_f=='04') {
		$tablename =" kib_d ";
	} else if ($xlock_f=='05') {
		$tablename =" kib_e ";
	} else if ($xlock_f=='06') {
		$tablename =" kib_f ";
	} else if ($xlock_f=='07') {
		$tablename =" kib_g ";
	}

	$kondisi=" a1='".$isi['a1']."' ".
	" and a='".$isi['a']."' ".
	" and b='".$isi['b']."' ".
	" and c='".$isi['c']."' ".
	" and d='".$isi['d']."' ".
	" and e='".$isi['e']."' ".
	" and e1='".$isi['e1']."' ";

	if ($tablename!='')
	{
		
	
	$sql1=" delete from buku_induk where $kondisi and f='$xlock_f' and c='$xlock_c' ";
	$sql2=" delete from $tablename  where $kondisi and f='$xlock_f' and c='$xlock_c' ";
	}
if ($wexec=='1')
{
echo "$sql1... <br>";
echo "$sql2... <br>";
	
// $qry1=mysql_query($sql1);
//  $qry2=mysql_query($sql2);
 echo "Delete BI & KIB  selesai... <br>";

}
	
}

$idprs= $_GET['idprs']!=''?$_GET['idprs']:"";
$kdc= $_GET['kdc']!=''?$_GET['kdc']:"";
$kdd= $_GET['kdd']!=''?$_GET['kdd']:"";
$kde= $_GET['kde']!=''?$_GET['kde']:"";
$kde1= $_GET['kde1']!=''?$_GET['kde1']:"";
$wexec= $_GET['wexec']!=''?$_GET['wexec']:"";


echo "Import KIB & BI dari table <br>";
echo "Param proses: $idprs<br>,kode c,kode d,kode e,kode e1";

if ($kdc=='') $kdc=''; 
// if ($kdd=='') $kdd='00'; 



$lock_f="";
$tablename="";
$kondisiz="";

if ($kdc!=''){
	$kondisiz=" and c='$kdc' ";
	$lock_c=$kdc;
}
if ($kdc!='' && $kdd!=''){
	$kondisiz.=" and d='$kdd' ";
	$lock_d=$kdd;
}
if ($kdc!='' && $kdd!='' && $kde!=''){
	$kondisiz.=" and e='$kde' ";
	$lock_e=$kde;
}

if ($kdc!='' && $kdd!='' && $kde!='' && $kde1!=''){
	$kondisiz.=" and e1='$kde1' ";
	$lock_e1=$kde1;
}
if ($idprs=='insertkiball')
{
	$x1=1;
	$x2=7;
} else 
{
	$x1=0;
	$x2=0;
	
}
 $tot_err='';
 $cnt_err=0;
$hasiltxt='';
$jmltotbrgX=0;
$jmltothrgX=0;
for ($x=$x1;$x<=$x2;$x++)
{


 if ($x==0) 
 {
 $idprs= $_GET['idprs']!=''?$_GET['idprs']:"";	
 	
 } else if ($x==1)
 {
 	$idprs='insertkiba';
 } else if ($x==2)
 {
 	$idprs='insertkibb';
 } else if ($x==3)
 {
 	$idprs='insertkibc';
 } else if ($x==4)
 {
 	$idprs='insertkibd';
 } else if ($x==5)
 {
 	$idprs='insertkibe';
 } else if ($x==6)
 {
 	$idprs='insertkibf';
 } else if ($x==7)
 {
 	$idprs='insertkibg';
 }



if ($idprs=='delsensus')
{
	delete_sensus($lock_c,$lock_d,$lock_f);
} else if ($idprs=='insertkiba')
{
	$tablename='v_bi_kib_a_tmp';
	$lock_f='01';
} else if ($idprs=='insertkibb')
{
	$tablename='v_bi_kib_b_tmp';
	$lock_f='02';
} else if ($idprs=='insertkibc')
{
	$tablename='v_bi_kib_c_tmp';
	$lock_f='03';
} else if ($idprs=='insertkibd')
{
	$tablename='v_bi_kib_d_tmp';
	$lock_f='04';
} else if ($idprs=='insertkibe')
{
	$tablename='v_bi_kib_e_tmp';
	$lock_f='05';
} else if ($idprs=='insertkibf')
{
	$tablename='v_bi_kib_f_tmp';
	$lock_f='06';
} else if ($idprs=='insertkibg')
{
	$tablename='v_bi_kib_g_tmp';
	$lock_f='07';
}


echo "Insert KIB & BI dari table : $tablename<br>";
echo "execute : $wexec<br>";
echo "lock_f : $lock_f<br>";
echo "lock_c : $lock_c<br>";
echo "lock_d : $lock_d<br>";
echo "lock_e : $lock_e<br>";
echo "lock_e1 : $lock_e1<br>";




if ($tablename!='' && $lock_c!='' && $lock_f!='')
{


echo "1. Move KIB & BI dari table $tablename<br>";
if($wdel=='1')
{
echo "1a. Move KIB & BI dari table $tablename<br>";
$sqry = "select a1,a,b,c,d,e,e1 from $tablename
 where f='$lock_f'  $kondisiz
 group by a1,a,b,c,d,e,e1 
 order by a1,a,b,c,d,e,e1";
$qry=mysql_query($sqry);

while ($isi = mysql_fetch_array($qry)){
$no++;
echo $no." ".$isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e']."<br>";
delete_bi_kib($isi,$lock_f,$lock_c);
}
}


	
$sqry = "select * from $tablename  
 where thn_perolehan<>0 and f='$lock_f' $kondisiz 
 order by id ";

$qry=mysql_query($sqry);
$no = 0;
$jmltotbrg=0;
$jmltothrg=0;

echo $sqry."<br>";
while ($isi = mysql_fetch_array($qry)){
$no++;
$jmlbrg=$isi['jml_barang'];
$jmltotbrg=$jmltotbrg+$jmlbrg;

$jmlharga=$isi['jml_harga'];
$jmltothrg=$jmltothrg+$jmlharga;
$isi['jml_harga'];
$cnt=1;
/*
if ($no<>$isi['id']) echo "<b>".$isi['id']." - ".$no."</b><br>";
*/
echo "<br><b>".$isi['id']." - ".$no."</b>";
if ($wexec<>'1')
{
	
	if (cekdata($isi,$lock_f,$lock_c,$lock_d,$lock_e,$lock_e1)==TRUE) {
		echo " error : no <br>";
	} else {
		echo " error : yes <br>";
		
	}
}
if($jmlbrg>0) 
{
// $harga=floor($jmlharga/$jmlbrg);	
$harga=$jmlharga/$jmlbrg;	

}
 else 
{
$harga=$jmlharga;
	
}

// $sisa=$jmlharga% ($harga*$jmlbrg);
$sisa=0;
if ($isi['c']!='' and $isi['e1']!='' and $isi['f']!='' and $isi['j']!='' and $jmlbrg>=1 ){
	

while ($cnt<=$jmlbrg)
{
 if ($cnt==$jmlbrg) $harga=$harga+$sisa; 
 echo "&nbsp;&nbsp;&nbsp;&nbsp; - $cnt - $harga - $sisa <br>";
 $newisi=$isi;
 $newisi['jml_barang']='1';
 $newisi['verifikasi']=99;
 $newisi['jml_harga']=$harga;
 $newisi['harga']=$harga;

 if (cek_bi_kib_new($newisi)==0){
 	insert_bi_kib($isi,$newisi,FALSE,$lock_f);
 	
  } else {
  echo "<br><b>No register ganti ------------------------------------</b>";
  	insert_bi_kib_newnoreg($isi,$newisi,true,$lock_f);
  }
 

$cnt++;
if ($cnt / 100 ==1)
{
	ob_flush();
flush(); 
}
}
}
}

if ($no>0){
update_idbi_idlama($lock_f);	
}
echo 'Insert KIB & BI dari table bi_kib_b_tmp Selesai ....<br>';

}

$hasiltxt=$hasiltxt.$idprs." - jmldata = ".$no." - jml barang : ".$jmltotbrg." - jml harga : ".$jmltothrg."<br>";
$jmltotbrgX=$jmltotbrgX+$jmltotbrg;
$jmltothrgX=$jmltothrgX+$jmltothrg;

if ($tot_err<>''){
	// echo 
	$hasiltxt=$hasiltxt."<br><b>Total error : ".$cnt_err." </b> - ".$tot_err;
}

}
echo  "<b><br>"."-------------------------------<br>"."STATUS :"."<br>"."-------------------------------<br></b>".$hasiltxt;
echo "<br><b> Jml Total Barang : ".$jmltotbrgX."   Jml Total Harga : ".$jmltothrgX." <b>";

?>