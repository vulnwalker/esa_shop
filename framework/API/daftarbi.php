<?php
$arrKondisi = array();

$gambar=$_GET['gambar'];
$page=$_GET['hal'];
$kib=$_GET['kib'];
$fnbidang=$_GET['bidang'];
$fnskpd=$_GET['skpd'];
$fnunit=$_GET['unit'];
$fnsubunit=$_GET['subunit'];

$awal= $page==0 ? '0' :($page-1)*10;
$limit=10;
		
		//if(!($gambar=="off")) $arrKondisi[]= " jml_gambar >= '1'";
		switch ($gambar){
			case 'on': $arrKondisi[] =" jml_gambar >= '1' "; break;
			case 'off': $arrKondisi[] =" (jml_gambar < '1'  or jml_gambar is null) "; break;
			case 'all':  break;
		}
		//$arrKondisi[] = $gambar=="off" ? " (jml_gambar < '1'  or jml_gambar is null) " : " jml_gambar >= '1' ";
		if(!($kib=="00" || $kib=="" )) $arrKondisi[]= " f = '$kib' ";
		if(!($fnbidang=="00" || $fnbidang=="" )) $arrKondisi[]= " c = '$fnbidang' ";
		if(!($fnskpd=="00" || $fnskpd=="" )) $arrKondisi[]= " d = '$fnskpd' ";
		if(!($fnunit=="00" || $fnunit=="" )) $arrKondisi[]= " e = '$fnunit' ";
		if(!($fnsubunit=="00" || $fnsubunit=="" )) $arrKondisi[]= " e1 = '$fnsubunit' ";

		$arrKondisi[]= " status_barang <> 3 and  status_barang <> 4 and status_barang <> 5  ";// and a='10' and b='00'
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;

	$qry_jml = "select count(*) as cnt from buku_induk ".$Kondisi; 
	$jml_data = mysql_fetch_array(mysql_query($qry_jml));
		
//$order=" order by c,d,e,e1,f,g,h,i,j,tahun,noreg";
$order=" order by a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg ";
$query = "select * from buku_induk ".$Kondisi." ".$order." limit ".$awal.",".$limit; 
$hasil = mysql_query($query);


$response = array();
 $response["cek"] = $query;
 $response["jml_data"] = $jml_data['cnt'];
	if (mysql_num_rows($hasil) > 0) {
		
		$response["daftarbi"] = array();
		while ($data = mysql_fetch_assoc($hasil)){
			$h['idBI']	=$data['id'];
			$h['a1'] = $data['a1'];
			$h['a'] = $data['a'];
			$h['b'] = $data['b'];
			$h['c'] = $data['c'];
			$h['d'] = $data['d'];
			$h['e'] = $data['e'];
			$h['e1'] = $data['e1'];		
			$h['f'] = $data['f'];
			$h['g'] = $data['g'];
			$h['h'] = $data['h'];
			$h['i'] = $data['i'];
			$h['j'] = $data['j']; 
			$h['noreg'] = $data['noreg'];
			$h['tahun'] = $data['tahun'];
			$h['tgl_buku'] = TglInd($data['tgl_buku']); 
			$h['jml_harga'] =number_format($data['jml_harga'], 2, ',' , '.');
			$h['kondisi']=$Main->KondisiBarang[$data['kondisi']-1][1]; 
			$h['jml_gambar'] = $data['jml_gambar'];
		 //ambil nama barang di ref_barang
			$aqry = "select * from ref_barang where 
				f='".$data['f']."' and 
				g='".$data['g']."' and 
				h='".$data['h']."' and 
				i='".$data['i']."' and 
				j='".$data['j']."' ";
			$qry=mysql_query($aqry);
			$dt_barang=mysql_fetch_array($qry);
		
		//inisiasi nama barang				
			$h['nm_barang'] = $dt_barang['nm_barang']; 
					
		//ambil gambar di gambar()
		 	$aqry_gbr = "select * from gambar where 
				idbi='".$data['id']."' order by stat Desc";
			$qry_gbr=mysql_query($aqry_gbr);
			$h['gambar']=array();
			while ($arrgambar=mysql_fetch_assoc($qry_gbr)){
				$g['id_gambar']=$arrgambar['Id'];
				$g['nmfile']=$arrgambar['nmfile'];//"407282_1359513228.jpg";//
				$g['ket']=$arrgambar['ket'];
				$g['idbi']=$arrgambar['idbi'];
				array_push($h['gambar'], $g);
			}
			
		//ambil skpd
			$aqry1 = "select nm_skpd from ref_skpd where 
				c='".$data['c']."' and d='00' ";
			$qry1=mysql_query($aqry1);			
			$arribdang=mysql_fetch_array($qry1);
			$h['bidang']=$arribdang['nm_skpd'];
			
			$aqry1 = "select nm_skpd from ref_skpd where 
				c='".$data['c']."' and d='".$data['d']."' and e='00'";
			$qry1=mysql_query($aqry1);			
			$arriskpd=mysql_fetch_array($qry1);
			$h['skpd']=$arriskpd['nm_skpd'];
			
			$aqry1 = "select nm_skpd from ref_skpd where 
				c='".$data['c']."' and d='".$data['d']."' and e='".$data['e']."' and e1='000'";
			$qry1=mysql_query($aqry1);			
			$arriunit=mysql_fetch_array($qry1);
			$h['unit']=$arriunit['nm_skpd'];
			
			$aqry1 = "select nm_skpd from ref_skpd where 
				c='".$data['c']."' and d='".$data['d']."' and e='".$data['e']."' and e1='".$data['e1']."'";
			$qry1=mysql_query($aqry1);			
			$arrisubunit=mysql_fetch_array($qry1);
			$h['subunit']=$arrisubunit['nm_skpd'];
			
			//detail
			
				
				
			if($data['f']=='01'){
				$aqry = "select * from kib_a where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
							
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$arrdet['luas'];
				$h['det6']=$arrdet['alamat'];
				$h['det7']=$arrdet['alamat_kel'];
				$h['det8']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);
														
				$h['det9']=$kota['nm_wilayah'];
				$h['det10']=$Main->StatusHakPakai[$arrdet['status_hak']-1][1];
				$h['det11']=$arrdet['sertifikat_no']." / ".TglInd($arrdet['sertifikat_tgl']);
				$h['det12']=$arrdet['penggunaan'];
				$h['det13']=$arrdet['ket'];				
			}
			
			if($data['f']=='02'){
				$aqry = "select * from kib_b where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);			
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$arrdet['merk'];
				$h['det6']=$arrdet['ukuran'];
				$h['det7']=$arrdet['bahan'];
				$h['det8']=$arrdet['no_pabrik'];
				$h['det9']=$arrdet['no_rangka'];
				$h['det10']=$arrdet['no_mesin'];
				$h['det11']=$arrdet['no_polisi'];
				$h['det12']=$arrdet['no_bpkb'];
				$h['det13']=$arrdet['ket'];
				
			}
			
			if($data['f']=='03'){
				$aqry = "select * from kib_c where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
				$h['det6']=$arrdet['luas_lantai'];
				$h['det7']=$arrdet['alamat'];
				$h['det8']=$arrdet['alamat_kel'];
				$h['det9']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);
				$h['det10']=$kota['nm_wilayah'];
				$h['det11']=$Main->StatusTanah[$arrdet['status_tanah']-1][1];
				$h['det12']=$arrdet['dokumen_no']." / ".TglInd($arrdet['dokumen_tgl']);
				$h['det13']=$arrdet['ket'];						
				
			}
			
			if($data['f']=='04'){
				$aqry = "select * from kib_d where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$arrdet['konstruksi'];	
				$h['det6']=$arrdet['panjang']." / ".$arrdet['lebar'];
				$h['det7']=$arrdet['luas_lantai'];
				$h['det8']=$arrdet['alamat'];
				$h['det9']=$arrdet['alamat_kel'];
				$h['det10']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);
				$h['det11']=$kota['nm_wilayah'];
				$h['det12']=$arrdet['dokumen_no']." / ".TglInd($arrdet['dokumen_tgl']);
				$h['det13']=$arrdet['ket'];
					
							
				}
				
				if($data['f']=='05'){
				$aqry = "select * from kib_e where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$Main->StatusBarang[$data['status_barang']-1][1];
				$h['det6']=$arrdet['buku_judul'];
				$h['det7']=$arrdet['buku_spesifikasi'];
				$h['det8']=$arrdet['seni_pencipta'];
				$h['det9']=$arrdet['seni_bahan'];
				$h['det10']=$arrdet['seni_asal_daerah'];
				$h['det11']=$arrdet['hewan_jenis'];
				$h['det12']=$arrdet['hewan_ukuran'];
				$h['det13']=$arrdet['ket'];					
							
			}
			
			if($data['f']=='06'){
				$aqry = "select * from kib_f where 
				idbi='".$data['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);	
				$h['det4']=$Main->AsalUsul[$data['asal_usul']-1][1];
				$h['det5']=$Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
				$h['det6']=$arrdet['luas_lantai'];
				$h['det7']=$arrdet['alamat'];
				$h['det8']=$arrdet['alamat_kel'];
				$h['det9']=$arrdet['alamat_kec'];
				//mengambil nama kota
					$aqry1 = "select nm_wilayah from ref_wilayah where 
					a='".$arrdet['alamat_a']."' and b='".$arrdet['alamat_b']."' ";
					$qry1=mysql_query($aqry1);			
					$kota=mysql_fetch_array($qry1);
				$h['det10']=$kota['nm_wilayah'];
				$h['det11']=$Main->StatusTanah[$arrdet['status_tanah']-1][1];
				$h['det12']=$arrdet['dokumen_no']." / ".TglInd($arrdet['dokumen_tgl']);
				$h['det13']=$arrdet['ket'];
				
				
				
			}
			
		 array_push($response["daftarbi"], $h);
		}
		$response["success"] = "1";
	
	} else {
	    $response["success"] = "0";
	    $response["message"] = "Tidak ada data bro";
	    //echo json_encode($response);
	}
	if($Main->SHOW_CEK==FALSE) $response["cek"] =  '';
	echo json_encode($response);


?>