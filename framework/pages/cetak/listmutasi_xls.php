<?PHP

function Mutasi_GetListxls($cetak=0){
	global $Main, $ctk;
	global $jmPerHal, $HalDefault, $jmlData;
	global $fmSemester, $fmTahun, $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmKIB,$fmSEKSI;
	global $ListHeader, $ListData, $ListFooterHal, $ListFooterAll;
	global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
	
	$clGaris = $cetak==1 ? "GCTK" : "GarisDaftar";
	//hal	
	$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;	
	$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
	$LimitHal = !empty($ctk)? " limit 0, $ctk" : $LimitHal ;
	
	//tglAwal TglAkhir ------------------------------------------
	//$tglAwal= "2010-01-01";$tglAkhir="2010-06-31";	//echo "<br> semester".$fmSemester;	
	/*switch ($fmSemester){
		default : {//SEMESTER I
			$tglAwal = $fmTahun."-01-01";
			$tglAkhir = date('Y-m-j',  strtotime (  $fmTahun."-07-01 -1 day" )); 
			break;
		}
		case 1: {
			$tglAwal = $fmTahun."-07-01";
			$tglAkhir = date('Y-m-j', strtotime ( ($fmTahun+1)."-01-01 -1 day"  )); 
			break;
		}
	}*/
	list($tglAwal, $tglAkhir)= SemesterToTgl($fmSemester, $fmTahun);
	//Kondisi ----------------------------------
	$KondisiC = $fmSKPD		== "00" ? "":" and c='$fmSKPD' ";
	$KondisiD = $fmUNIT 	== "00" ? "":" and d='$fmUNIT' ";
	$KondisiE = $fmSUBUNIT 	== "00" ? "":" and e='$fmSUBUNIT' ";
    $KondisiE1 = $fmSEKSI == "00" || $fmSEKSI == "000" ? "" : " and e1='$fmSEKSI' ";
		
	$Kondisi =  " and a1='".$fmKEPEMILIKAN."' and a='{$Main->Provinsi[0]}' $KondisiC $KondisiD $KondisiE $KondisiE1 ";
	if ( $fmKIB != ''){	$Kondisi .= " and f = '$fmKIB' "; }
		
	//order -------------------------------------
	$OrderBy = " order by a1,a,b,c,d,e,e1,f,g,h,i,j, thn_perolehan, noreg ";
	//$LimitHal = "limit 0, 10";

	//saldo awal ----------------------------------------------

	{
        $SaldoAwal_BrgKurang = 0;
        $SaldoAwal_HrgKurang = 0;
        $SaldoAwal_BrgTambah = 0;
        $SaldoAwal_HrgTambah = 0;
        //saldo awal kurang penghapusan ----------------------------------------------
        $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v_penghapusan_bi
				where tgl_penghapusan < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;
        $qry = mysql_query($aqry);
        $isi = mysql_fetch_array($qry);
        $SaldoAwal_BrgKurang = $isi['totbarang'];
        $SaldoAwal_HrgKurang = $isi['totharga']; //echo"<br>brg=$SaldoAwal_BrgKurang hrg=$SaldoAwal_HrgKurang";
		//saldo awal kurang pelihara,
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_penghapusan_pelihara
				where tgl_penghapusan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang pengaman,
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_penghapusan_pengaman
				where tgl_penghapusan < '$tglAwal' $Kondisi";   //echo "<br> qry awal kurang = ".$aqry;
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		
		//saldo awal kurang pemindahtangan ---------------------------------------
		$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_pindahtangan
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;        
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_BrgKurangPindah = $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
		$SaldoAwal_BrgKurang += $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
        $SaldoAwal_HrgKurangPindah = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang pindah pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_pindahtangan_pelihara
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPindahPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang pindah pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_pindahtangan_pengaman
				where tgl_pemindahtanganan < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangPindahPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		
		//saldo awal kurang gantirugi --------------------------------------------
		$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_gantirugi
				where tgl_gantirugi < '$tglAwal' $Kondisi"; //echo "<br> qry awal kurang = ".$aqry;        
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_BrgKurangGantirugi = $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
		$SaldoAwal_BrgKurang += $isi['totbarang']==NULL ? 0 : $isi['totbarang'];
        $SaldoAwal_HrgKurangGantirugi = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang gantirugi pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_gantirugi_pelihara
				where tgl_gantirugi < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangGantirugiPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
		//saldo awal kurang gantirugi pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_gantirugi_pengaman
				where tgl_gantirugi < '$tglAwal' $Kondisi";  //echo "<br> qry awal kurang = ".$aqry; 
    	$isi = mysql_fetch_array( mysql_query($aqry));
		$SaldoAwal_HrgKurangGantirugiPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
		$SaldoAwal_HrgKurang +=  $isi['totharga']==NULL ? 0 : $isi['totharga'];
			
		
        //saldo awal tambah perolehan ------------------------------------------------
        $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from buku_induk
		where tgl_buku < '$tglAwal'   $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;
        $qry = mysql_query($aqry);
        $isi = mysql_fetch_array($qry);
        $SaldoAwal_BrgTambah = $isi['totbarang'];
        $SaldoAwal_HrgTambah = $isi['totharga']; //echo"<br>brg=$SaldoAwal_BrgTambah hrg=$SaldoAwal_HrgTambah";
        //saldo awal tambah pelihara
        $aqry = " select count(*) as totbarang, sum(biaya_pemeliharaan)as totharga from v_pemelihara
		where tgl_pemeliharaan < '$tglAwal' and tambah_aset=1  $Kondisi ";  //echo "<br> qry awal tambah = ".$aqry;
        $isi = mysql_fetch_array(mysql_query($aqry));
        $SaldoAwal_HrgTambah += $isi['totharga'];
        //saldo awal tambah pengaman
        $aqry = " select count(*) as totbarang, sum(biaya_pengamanan)as totharga from v_pengaman
		where tgl_pengamanan < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;		
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];
		//saldo awal tambah mut pelihara
		$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_mutasi_pelihara
		where tgl_buku < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;	
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];
		//saldo awal tambah mut pengaman
		$aqry = " select sum(biaya_pengamanan)as totharga from v2_mutasi_pengaman
		where tgl_buku < '$tglAwal'  and tambah_aset=1 $Kondisi";  //echo "<br> qry awal tambah = ".$aqry;		
        $isi = mysql_fetch_array(mysql_query($aqry));		
        $SaldoAwal_HrgTambah += $isi['totharga'];
		
		
        $SaldoAwal_Brg = $SaldoAwal_BrgTambah - $SaldoAwal_BrgKurang;// + $SaldoAwal_BrgKurangPindah);
        $SaldoAwal_Hrg = $SaldoAwal_HrgTambah - $SaldoAwal_HrgKurang;// + $SaldoAwal_HrgKurangPindah);//+$SaldoAwal_HrgKurangPelihara+$SaldoAwal_HrgKurangPengaman);
    }
    //perubahan berkrang ------------------------------------------------
    $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v_penghapusan_bi
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang = $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang = $isi['totharga'] == NULL ? 0 : $isi['totharga']; //echo"<br>brg=$BrgKurang hrg=$HrgKurang";
	//perubahan hapus pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_penghapusan_pelihara
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan hapus pengamanan
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_penghapusan_pengaman
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan pindah tangan --------------------------------------
	$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_pindahtangan
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang += $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_pindahtangan_pelihara
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPindahPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_pindahtangan_pengaman
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangPindahPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	
	//perubahan kurang gantirugi --------------------------------------
	$aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from v1_gantirugi
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgKurang += $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pemeliharaan)as totharga from v2_gantirugi_pelihara
		where tgl_gantirugi>= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangGantirugiPelihara = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//perubahan kurang pindah pelihara
	$aqry = " select sum(biaya_pengamanan)as totharga from v2_gantirugi_pengaman
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi<='$tglAkhir'  $Kondisi";  //echo "<br> qry kurang = ".$aqry;    
    $isi = mysql_fetch_array( mysql_query($aqry));
	$HrgKurangGantirugiPengaman = $isi['totharga']==NULL ? 0 : $isi['totharga'];    
	$HrgKurang += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	
    
    //perubahan bertambah ------------------------------------------------
    $aqry = " select sum(jml_barang) as totbarang, sum(jml_harga)as totharga from buku_induk
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir'  $Kondisi";  //echo"<br>qry tambah=".$aqry;
    $qry = mysql_query($aqry);
    $isi = mysql_fetch_array($qry);
    $BrgTambah = $isi['totbarang'] == NULL ? 0 : $isi['totbarang'];
    $HrgTambah = $isi['totharga'] == NULL ? 0 : $isi['totharga'];
    //pelihara
    $aqry = " select count(*) as totbarang, sum(biaya_pemeliharaan)as totharga from v_pemelihara
		where tgl_pemeliharaan >= '$tglAwal' and tgl_pemeliharaan <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry)); //echo ($aqry);
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
    //pengaman
    $aqry = " select count(*) as totbarang, sum(biaya_pengamanan)as totharga from v_pengaman
		where tgl_pengamanan >= '$tglAwal' and tgl_pengamanan <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
	//mutasi pelihara
    $aqry = " select sum(biaya_pemeliharaan)as totharga from v2_mutasi_pelihara
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];    
	//mutasi pengaman
    $aqry = " select sum(biaya_pengamanan)as totharga from v2_mutasi_pengaman
		where tgl_buku >= '$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 $Kondisi"; //echo"<br>qry tambah=".$aqry;
    $isi = mysql_fetch_array(mysql_query($aqry));
    $HrgTambah += $isi['totharga'] == NULL ? 0 : $isi['totharga'];
    //saldo akhir ----------------------------------------------
    $SaldoAkhir_Brg = $SaldoAwal_Brg + $BrgTambah - $BrgKurang ;//+ $BrgKurangPindah);
    $SaldoAkhir_Hrg = $SaldoAwal_Hrg + $HrgTambah - $HrgKurang ;//+ $HrgKurangPindah);//+$HrgKurangPelihara+$HrgKurangPengaman);

    // List -----------------------------------------------------------------
    //aqry 
	{
        $aqry = 
		"(select 
		'1' as grp, '0' as a1, null as a, null as b, null as c, null as d, null as e, null as e1, null as f, null as g, null as h, null as i, null as j, null as thn_perolehan, null as noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan, null as tgl_buku, null as ket_hapus, null as tgl_penghapusan,		
		$SaldoAwal_Brg as awal_barang, $SaldoAwal_Hrg as awal_harga, 
		null as brgkurang, null as hrgkurang, null as brgtambah, null as hrgtambah, null as akhir_barang, null as akhir_harga
		,null as nmbarang		
		)union(".
		
		"select 
		'3' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi, status_barang, asal_usul, satuan , tgl_buku, null as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, jml_barang as brgtambah, jml_harga as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from buku_induk
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".	
		"select 
		'3a' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pemeliharaan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,jenis_pemeliharaan as nmbarang
		from v2_mutasi_pelihara
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		"select 
		'3b' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pengamanan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_mutasi_pengaman
		where tgl_buku >='$tglAwal' and tgl_buku <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".	
		"select 
		'5' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pemeliharaan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,jenis_pemeliharaan as nmbarang
		from v_pemelihara
		where tgl_pemeliharaan >='$tglAwal' and tgl_pemeliharaan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		"select 
		'6' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, null as hrgkurang, 
		null as brgtambah, biaya_pengamanan as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v_pengaman
		where tgl_pengamanan >='$tglAwal' and tgl_pengamanan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		
		
		"select 
		'2' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , tgl_buku, ket_hapus, tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from v_penghapusan_bi 		
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".		
		"select 
		'2a' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, 
		null as hrgtambah,
		null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_penghapusan_pelihara
		where tgl_penghapusan >= '$tglAwal' and tgl_penghapusan <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		"select 
		'2b' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, ket as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga,   
		null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 
		null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_penghapusan_pengaman
		where tgl_penghapusan >='$tglAwal' and tgl_penghapusan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan ------------------------------------------------------
		"select 
		'2c' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , 
		tgl_pemindahtanganan  as tgl_buku, 
		ket_hapus as ket_hapus, null as tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, 
		null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from v1_pindahtangan 		
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan pelihara
		"select 
		'2d' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga, null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, null as hrgtambah,null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_pindahtangan_pelihara
		where tgl_pemindahtanganan >= '$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		//pindah tangan pengaman
		"select 
		'2e' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pengamanan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga,  null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 	null as hrgtambah,	null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_pindahtangan_pengaman
		where tgl_pemindahtanganan >='$tglAwal' and tgl_pemindahtanganan <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".		
		//gantirugi -------------------------------------------------------
		"select 
		'2f' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		kondisi_akhir as kondisi, status_barang, asal_usul, satuan , 
		tgl_gantirugi  as tgl_buku, 
		ket_hapus as ket_hapus, null as tgl_penghapusan,  
		null as awal_barang, null as awal_harga, 
		jml_barang as brgkurang, jml_harga as hrgkurang, 
		null as brgtambah, null as hrgtambah,
		null as akhir_barang, null as akhir_harga		
		,null as nmbarang
		from v1_gantirugi 		
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi <='$tglAkhir' 
		$Kondisi $OrderBy 
		)union(".
		//gantirugi pelihara
		"select 
		'2g' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_pemeliharaan as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga, null as brgkurang, 
		biaya_pemeliharaan as hrgkurang, 
		null as brgtambah, null as hrgtambah,null as akhir_barang, null as akhir_harga,		
		jenis_pemeliharaan as nmbarang
		from v2_gantirugi_pelihara
		where tgl_gantirugi >= '$tglAwal' and tgl_gantirugi <='$tglAkhir' and tambah_aset=1
		$Kondisi $OrderBy 
		)union(".
		//gantirugi pengaman
		"select 
		'2h' as grp, a1,a,b,c,d,e,e1,f,g,h,i,j,thn_perolehan,noreg, 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan , 
		tgl_gantirugi as tgl_buku, 
		ket as ket_hapus, 
		null as tgl_penghapusan, null as awal_barang, null as awal_harga,  null as brgkurang, 
		biaya_pengamanan as hrgkurang, 
		null as brgtambah, 	null as hrgtambah,	null as akhir_barang, null as akhir_harga		
		,uraian_kegiatan as nmbarang
		from v2_gantirugi_pengaman
		where tgl_gantirugi >='$tglAwal' and tgl_gantirugi <='$tglAkhir' and tambah_aset=1 
		$Kondisi $OrderBy 
		)union(".
		
		"select
		'4' as grp, '9999' as a1, null as a, null as b, null as c, null as d, null as e,null as e1, null as f, null as g, null as h, null as i, null as j, null as thn_perolehan, null as noreg,		 
		null as kondisi, null as status_barang, null as asal_usul, null as satuan, null as tgl_buku, null as ket_hapus, null as tgl_penghapusan,
		null as awal_barang, null as awal_harga, null as brgkurang, 
		null as hrgkurang, null as brgtambah, null as hrgtambah,
		$SaldoAkhir_Brg as akhir_barang, $SaldoAkhir_Hrg as akhir_harga 
		,null as nmbarang		
		
		)
		
		"; //echo $aqry;
    }

    //str_replace('<LimitHal>', $LimitHal, $aqry )
    //jml data
    $qry = mysql_query($aqry);
    $jmlData = mysql_num_rows($qry);

    $aqry .=" $OrderBy $LimitHal "; //echo"<br>qryunion=".$aqry;
    $qry = mysql_query($aqry);
    $totBrgKurangHal = 0;
    $totHrgKurangHal = 0;
    $totBrgTambahHal = 0;
    $totHrgTambahHal = 0;
    $no = !empty($ctk) ? 0 : $Main->PagePerHal * (($HalDefault * 1) - 1);
    
	while ($isi = mysql_fetch_array($qry)) {

        if ($cetak == 0) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $no++;
        //$jmlTotalHargaDisplay += $isi['jml_harga'];
        //get detail kib
        $kdBarang = "";
        $nmBarang = "";
        $ISI5 = ""; //b=merk;
        $ISI6 = ""; //a=sertifikatno;b=nopabrik,rangka,mesin;c=dokumenno;
        $ISI7 = ""; //b=bahan;
        $ISI10 = ""; //c=kondisibangunan;
        $ISI12 = $Main->KondisiBarang[$isi['kondisi'] - 1][1];
        $ISI15 = ""; //ket;
        //if ($isi['f'] != NULL){
        $kdBarang = $isi['f'] . "." . $isi['g'] . "." . $isi['h'] . "." . $isi['i'] . "." . $isi['j'];
        if ($isi['grp'] == '2' || $isi['grp'] == '3') {

            $nmBarang = table_get_value("select nm_barang from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j)= '$kdBarang'", 'nm_barang');
            $KondisiKIB = " Where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and e1 = '{$isi['e1']}' and
						f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
						tahun = '{$isi['thn_perolehan']}' and noreg = '{$isi['noreg']}'  ";
            Penatausahaan_BIGetKib($isi['f'], $KondisiKIB);
            
       	//} elseif ($isi['grp'] == '5'  ) {
		}else{
			
		
            $nmBarang = $isi['nmbarang'];
        }
		
		//keterangan ---------
        //$ISI15 = $isi['grp'] == '2' ? $isi['ket_hapus'] : $ISI15;
		$ISI15 = $isi['grp'] == '3' ? $ISI15: $isi['ket_hapus'];		
        $ISI15 = !empty($ISI15) ? $ISI15 : "-";
        $ISI15 .= $isi['grp'] == '2' ? ' /<br>' . TglInd($isi['tgl_penghapusan']) : ' /<br>' . TglInd($isi['tgl_buku']);
        $ISI15 .= tampilNmSubUnit($isi);

        $tampilSaldoAwalBrg = $isi['grp'] == '1' ? number_format($SaldoAwal_Brg, 0, '.', '') : '';
        $tampilSaldoAwalHrg = $isi['grp'] == '1' ? number_format($SaldoAwal_Hrg, 2, '.', '') : '';
        $tampilSaldoAkhirBrg = $isi['grp'] == '4' ? number_format($SaldoAkhir_Brg, 0, '.', '') : '';
        $tampilSaldoAkhirHrg = $isi['grp'] == '4' ? number_format($SaldoAkhir_Hrg, 2, '.', '') : '';

        $totBrgKurangHal += $isi['brgkurang'];
        $totHrgKurangHal += $isi['hrgkurang'];
        $totBrgTambahHal += $isi['brgtambah'];
        $totHrgTambahHal += $isi['hrgtambah'];

        $tampilBrgKurang = $isi['brgkurang'] != NULL ? number_format($isi['brgkurang'], 0, '.', '') : "";
        $tampilHrgKurang = $isi['hrgkurang'] != NULL ? number_format($isi['hrgkurang'], 2, '.', '') : "";
        $tampilBrgTambah = $isi['brgtambah'] != NULL ? number_format($isi['brgtambah'], 0, '.', '') : "";
        $tampilHrgTambah = $isi['hrgtambah'] != NULL ? number_format($isi['hrgtambah'], 2, '.', '') : "";


        //list ------------
        if ($isi['grp'] == '1') {
            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=2>$no.</td>
			<td class=\"$clGaris\" align=left colspan=11><b>Jumlah (Awal)</td>					
			
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilSaldoAwalBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilSaldoAwalHrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilBrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilHrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilBrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilHrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilSaldoAkhirBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilSaldoAkhirHrg . "</div></td>
			
			<td class=\"$clGaris\"></td>			
        	</tr>
			";
        } else if ($isi['grp'] == '4') {
            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=2>$no.</td>
			<td class=\"$clGaris\" align=left colspan=11><b>Jumlah (Akhir)</td>					
			
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilSaldoAwalBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilSaldoAwalHrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilBrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilHrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilBrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilHrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . $tampilSaldoAkhirBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . $tampilSaldoAkhirHrg . "</div></td>
			
			<td class=\"$clGaris\"></td>			
        	</tr>
			";
        } else {


            $ListData .= "
			<tr class=\"$clRow\" valign='top'>
			<td class=\"$clGaris\" align=center colspan=2>$no.</td>
			<!--<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>-->
			<td class=\"$clGaris\" align=center>$kdBarang</td>
			<td class=\"$clGaris\" align=center>{$isi['noreg']}</td>
			
			<td class=\"$clGaris\">$nmBarang</td>			
			<td class=\"$clGaris\">$ISI5</td>
			<td class=\"$clGaris\">$ISI6</td>
			<td class=\"$clGaris\">$ISI7</td>		
				
			<!--<td class=\"$clGaris\">" . $Main->AsalUsul[$isi['asal_usul'] - 1][1] . "/<br>" . $Main->StatusBarang[$isi['status_barang'] - 1][1] . "</td>	-->
			<td class=\"$clGaris\">" . $Main->AsalUsul[$isi['asal_usul'] - 1][1] . "</td>
			<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"$clGaris\">$ISI10</td>			
			<td class=\"$clGaris\" align=right>{$isi['satuan']}</td>
			<td class=\"$clGaris\">$ISI12</td>
			
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\">" . $tampilSaldoAwalBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\">" . $tampilSaldoAwalHrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\">" . $tampilBrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\">" . $tampilHrgKurang . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\">" . $tampilBrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\">" . $tampilHrgTambah . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\">" . $tampilSaldoAkhirBrg . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\">" . $tampilSaldoAkhirHrg . "</div></td>
			
			<td class=\"$clGaris\">$ISI15</td>
			<!--<td class=\"$clGaris\">$dok</td>-->
        	</tr>
		";
        }
    }
	


    // tampil ----------------------------------------------------------
    // list header 
	{
        $ListHeader =
                "<tr >
		<th class=\"th02\" colspan='4'>Nomor</th>
		<th class=\"th02\" colspan='4'>Spesifikasi Barang</th>	
		<th class=\"th02\" rowspan=3 width=50>Asal / Cara Perolehan Barang</th>
		<th class=\"th02\" rowspan=3>Tahun Peroleh an</th>
		<th class=\"th02\" rowspan=3>Ukuran Barang / Konstruksi (P,SP,D)</th>	
		<th class=\"th02\" rowspan=3 width=30>Satu an</th>
		<th class=\"th02\" rowspan=3>Kondisi (B,RR,RB)</th>
		<th class=\"th02\" colspan=2>Jumlah (Awal)</th>	
		<th class=\"th02\" colspan=4>Mutasi/Perubahan</th>	
		<th class=\"th02\" colspan=2>Jumlah (Akhir)</th>		
		<th class=\"th02\" rowspan=3>Keterangan/<br>Tgl. Buku/<br>Tgl. Hapus</th>	
	</tr>
	<tr>
		<th class=\"th02\" rowspan=2 colspan=2>No. Urut</th>		
		<th class=\"th02\" rowspan=2 >Kode <br>Barang</th>
		<th class=\"th02\" rowspan=2 >Reg.</th>
		<th class=\"th02\" rowspan=2 width=\"100\">Nama / Jenis Barang</th>
		<th class=\"th02\" rowspan=2 width=\"70\">Merk / Tipe </th>
		<th class=\"th02\" rowspan=2 width=\"70\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
		<th class=\"th02\" rowspan=2 >Bahan</th>
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
		<th class=\"th02\" colspan=2 > Berkurang </th>
		<th class=\"th02\" colspan=2 > Bertambah </th>	
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
	</tr>
	<tr>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
	</tr>
	<tr>
		<th class='th03' colspan=2>1</th>
		<th class='th03' >2</th><th class='th03' >3</th>
		<th class='th03' >4</th><th class='th03' >5</th><th class='th03' >6</th>
		<th class='th03' >7</th><th class='th03' >8</th><th class='th03' >9</th>
		<th class='th03' >10</th><th class='th03' >11</th><th class='th03' >12</th>
		<th class='th03' >13</th><th class='th03' >14</th><th class='th03' >15</th>
		<th class='th03' >16</th><th class='th03' >17</th><th class='th03' >18</th>
		<th class='th03' >19</th><th class='th03' >20</th><th class='th03' >21</th>	
	</tr>
	";
    }



    //list footer 
	{
        $ListFooterHal = !empty($ctk) ?
                "" :
                "<tr>
			<td class=\"$clGaris\" align=left colspan=13><b>Total per Halaman</td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($totBrgKurangHal, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($totHrgKurangHal, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($totBrgTambahHal, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($totHrgTambahHal, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" align=right></td>
			<td class=\"$clGaris\" ></td>
		</tr>";
        $ListFooterAll = 
                "<tr>
			<td class=\"$clGaris\" align=left colspan=13><b>Total Seluruhnya</td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($SaldoAwal_Brg, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($SaldoAwal_Hrg, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($BrgKurang, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($HrgKurang, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($BrgTambah, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($HrgTambah, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt1\"><b>" . number_format($SaldoAkhir_Brg, 0, '.', '') . "</div></td>
			<td class=\"$clGaris\" align=right><div class=\"nfmt4\"><b>" . number_format($SaldoAkhir_Hrg, 2, '.', '') . "</div></td>
			<td class=\"$clGaris\" ></td>
		</tr>" ;
    }


// $ListData .= $ListFooter;		
	
}


// kolom 21



function list_header($XXBIDANG='BIDANG',$XXASISTEN='ASISTEN',
$XXBIRO='BIRO',$XXSEKSI='',$XXKOTA='KOTA',$XXPROP='PROPINSI',$XXKDLOK='KDLOKASI',$XXSEMESTER='',$XXTAHUN='') {
$isix='<html><head>
	<title>::ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah)</title>
	<style>
table.rangkacetak {
	background-color: #FFFFFF;
	margin: 0cm;
	padding: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	font-family : Arial,  sans-serif;
}
table.cetak {
	background-color: #FFFFFF;
	font-family : Arial,  sans-serif;
	margin: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	color: #000000;
	font-size : 9pt;
}
table.cetak th.th01 {

	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak th.th02 {

	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak tr.row0 {
	background-color: #DBDBDB;
	text-align: left;
}
table.cetak tr.row1 {
	background-color: #FFF;
	text-align: left;
}
table.cetak input {

	font-size: 9pt;
}
/* untuk repeat header */
thead { 
	display: table-header-group; 
}
/* untuk repeat footer */
tfoot { 
	display: table-footer-group; 
}
.judulcetak {
	width: 30cm;
	font-size: 16px;
	font-weight: bold;
}
.subjudulcetak {
	font-size: 12px;
	font-weight: bold;
}
.GCTK {
	background-color: white;
	vertical-align: middle;
}
.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}
tr
{mso-height-source:auto;}
br
{mso-data-placement:same-cell;}			
</style>
</head>

<body>
<table class="rangkacetak">
<tr>
<td valign="top">

	<table style="width:30cm" border="0">

		<tr>
			<td class="judulcetak" colspan="21"><DIV ALIGN=CENTER>LAPORAN MUTASI BARANG '.$XXSEMESTER.'</DIV></td>
		</tr>

		<tr>
			<td class="judulcetak" colspan="21"><DIV ALIGN=CENTER></DIV></td>
		</tr>
		<tr>
			<td class="judulcetak" colspan="21"><DIV ALIGN=CENTER>TAHUN ANGGARAN '.$XXTAHUN.'</DIV></td>
		</tr>

	</table>

  </td>
  </tr>
<tr>
<td valign="top">
<table cellpadding=0 cellspacing=0 border=0 width="100%"> 
			<tr>
			<td   width="10%" colspan="2" style="font-weight:bold;font-size:9pt" >BIDANG</td>
			<td align=center width="4%" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXBIDANG.'</td> 
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >SKPD</td>
			<td align=center style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXASISTEN.'</td>
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >UNIT</td>
			<td align=center style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXBIRO.'</td> </tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >SUB UNIT</td>
			<td align=center style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXSEKSI.'</td> </tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >KABUPATEN/KOTA</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXKOTA.'</td> </tr> 
			<tr> 
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >PROVINSI</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="18" style="font-weight:bold;font-size:9pt" >'.$XXPROP.'</td> </tr> 
			</table>  
</td>
</tr>
<tr>
  <td valign="top">
	<table width="100%" border="0">
		<tr>
			<td colspan="18">&nbsp;</td>
			<td align=right style="font-weight:bold;font-size:9pt" colspan="3">NO. KODE LOKASI : '.$XXKDLOK.'</td>
		</tr>
	</table>

  </td>
  </tr>
  <tr>
  <td valign="top">
  	<table class="cetak" border="1">
	
';
return $isix;	
}

function list_tableheader($dlmribuan='')
{
$sharga = !empty($dlmribuan)?'HARGA Ribuan)':'HARGA';
$isix="<thead>
<tr>
		<th class=\"th02\" colspan='4'>Nomor</th>
		<th class=\"th02\" colspan='4'>Spesifikasi Barang</th>	
		<th class=\"th02\" rowspan=3 width=50>Asal / Cara Perolehan Barang</th>
		<th class=\"th02\" rowspan=3>Tahun Peroleh an</th>
		<th class=\"th02\" rowspan=3>Ukuran Barang / Konstruksi (P,SP,D)</th>	
		<th class=\"th02\" rowspan=3 width=30>Satu an</th>
		<th class=\"th02\" rowspan=3>Kondisi (B,RR,RB)</th>
		<th class=\"th02\" colspan=2>Jumlah (Awal)</th>	
		<th class=\"th02\" colspan=4>Mutasi/Perubahan</th>	
		<th class=\"th02\" colspan=2>Jumlah (Akhir)</th>		
		<th class=\"th02\" rowspan=3>Keterangan/<br>Tgl. Buku</th>	
	</tr>
	<tr>
		<th class=\"th02\" rowspan=2 colspan=2>No. Urut</th>		
		<th class=\"th02\" rowspan=2 >Kode <br>Barang</th>
		<th class=\"th02\" rowspan=2 >Reg.</th>
		<th class=\"th02\" rowspan=2 width=\"100\">Nama / Jenis Barang</th>
		<th class=\"th02\" rowspan=2 width=\"70\">Merk / Tipe </th>
		<th class=\"th02\" rowspan=2 width=\"70\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
		<th class=\"th02\" rowspan=2 >Bahan</th>
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
		<th class=\"th02\" colspan=2 > Berkurang </th>
		<th class=\"th02\" colspan=2 > Bertambah </th>	
		<th class=\"th02\" rowspan=2 >Barang</th>
		<th class=\"th02\" rowspan=2 > Harga </th>
	</tr>
	<tr>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
		<th class=\"th02\" >Jumlah Barang</th>
		<th class=\"th02\" >Jumlah Harga </th>
	</tr>
	<tr>
		<th class='th01' colspan=2>1</th>
		<th class='th01' >2</th><th class='th01' >3</th>
		<th class='th01' >4</th><th class='th01' >5</th><th class='th01' >6</th>
		<th class='th01' >7</th><th class='th01' >8</th><th class='th01' >9</th>
		<th class='th01' >10</th><th class='th01' >11</th><th class='th01' >12</th>
		<th class='th01' >13</th><th class='th01' >14</th><th class='th01' >15</th>
		<th class='th01' >16</th><th class='th01' >17</th><th class='th01' >18</th>
		<th class='th01' >19</th><th class='th01' >20</th><th class='th01' >21</th>	
	</tr>	
		</thead>
			
";	
return $isix;		
}

/*
	<tr>
		<th class="th01" colspan=2>1</th>
		<th class="th01" >2</th><th class="th01" >3</th>
		<th class="th01" >4</th><th class="th01" >5</th><th class="th01" >6</th>
		<th class="th01" >7</th><th class="th01" >8</th><th class="th01" >9</th>
		<th class="th01" >10</th><th class="th01" >11</th><th class="th01" >12</th>
		<th class="th01" >13</th><th class="th01" >14</th><th class="th01" >15</th>
		<th class="th01" >16</th><th class="th01" >17</th><th class="th01" >18</th>
		<th class="th01" >19</th><th class="th01" >20</th><th class="th01" >21</th>	
	</tr>
*/

function list_footer($XXTMPTGL='',$XXJABATAN1='',$XXNAMA1='',$XXNIP1='',
$XXJABATAN2='',$XXNAMA2='',$XXNIP2='')
{
$isix='</td></tr></table><tr>
  <td valign="top">
<table style="width:30cm" border=0> 
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>

				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td colspan=9 >&nbsp;</td> 
				<td align=center colspan=5 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP2.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				</table>

</td>
</tr>
</table>
</body>
</html>
';
return $isix;			
}


?>