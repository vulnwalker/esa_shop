<?PHP
// colom 16
function number_format_ribuan_xls($val=0, $dlmRibuan=FALSE){
	return $dlmRibuan == TRUE ? number_format(($val / 1000), 2, '.', '') : number_format($val, 2, '.', '');
        
}
function Mutasi_RekapByBrg_GetList2_xls($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT,  
	$noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $cetak=FALSE, 
	$Style=1, $fmSEKSI='00', $fmKONDBRG='7') {
    // ------------------------------
    // $Style=1 = total penambahan, 2= pemelihara, pemgaman, peroleh, 3 = saldo akhir sampai dgn tglakhir
    // ------------------------------
    global $Main;
    global $tglAwal, $tglAkhir; //$fmSemester, $fmTahun;

	$cek='';

    $clGaris = $cetak == TRUE ? "GCTK" : "GarisDaftar";
    //get kondisi (skpd) ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI =='' || $fmSEKSI == "000" || $fmSEKSI == "00"  ? "" : " and e1='$fmSEKSI' ";
    $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					and c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 ";
    if ($fmSKPD == "00") {
        $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE $KondisiE1";
    }
	
	//kondisi barang
	switch($fmKONDBRG){
		case '1' :case '2': case '3': case '4' :{
			$Kondisi .= $Kondisi==''? " kondisi = '$fmKONDBRG' ": " and kondisi = '$fmKONDBRG' ";
			break;
		}
		/*default : {
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}*/
		case '5' :{
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}
	}

    //list --------------------------------------------------------------
    $ListData = "";
    $cb = 0;
    $no = $noawal;
	
	/*
	#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f and aa.g=jj.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f and aa.g=kk.g 			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f and aa.g=ll.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f and aa.g=mm.g 
			
	*/
    $sqry = "
		(select aa.f as f, aa.g as g, aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir ".			
		
		"from ref_barang aa 
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f) bb on aa.f=bb.f						
			left join (select f , g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f) cc on aa.f=cc.f
			left join (select f , g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f) dd on aa.f=dd.f
			left join (select f , g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where  $Kondisi and  tgl_buku < '$tglAwal'  group by f) ee on aa.f=ee.f
			
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f) ff on aa.f=ff.f
			left join (select f , g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f) gg on aa.f=gg.f
			left join (select f , g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f) hh on aa.f=hh.f
			left join (select f , g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and tgl_buku <= '$tglAkhir'  group by f) ii on aa.f=ii.f
			
			
			
			
			left join (select f, g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f) jj on aa.f=jj.f  
			left join (select f, g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f) kk on aa.f=kk.f 
			
			left join (select f, g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f) ll on aa.f=ll.f  
			left join (select f, g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f) mm on aa.f=mm.f 
			
			
			
			
		".			
		" where aa.g='00'  
		)union(
		select aa.f, aa.g,  aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir ".		
		"from ref_barang aa 
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select f , g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select f , g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select f , g, sum(jml_barang) as jmlBrgBI, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select f , g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select f , g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select f , g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select f , g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and tgl_buku <= '$tglAkhir'  group by f,g) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select f, g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select f, g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select f, g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select f, g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g) mm on aa.f=mm.f  and aa.g=mm.g 
			
			
			".			
		" where aa.g<>'00' and aa.h='00'  
		)
		order by f, g		
	"; // echo "$sqry";
	// $cek.=$sqry;
    $QryRefBarang = mysql_query($sqry);    
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;    
    while ($isi = mysql_fetch_array($QryRefBarang)) {
        //get kondisi1 (barang) ----------------------------------
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
        $KondisiBi = " status_barang<>3 ";
		$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
		$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";

        //data --------------------------------------------------
		//penghapusan
        $jmlBrgHPS_awal = $isi['jmlBrgHPS_awal'];
		$jmlHrgHPS_awal = $isi['jmlHrgHPS_awal'];		
		$jmlBrgHPS_akhir = $isi['jmlBrgHPS_akhir'];
		$jmlHrgHPS_akhir = $isi['jmlHrgHPS_akhir'];		
		$jmlBrgHPS_curr = $jmlBrgHPS_akhir - $jmlBrgHPS_awal;
		$jmlHrgHPS_curr = $jmlHrgHPS_akhir - $jmlHrgHPS_awal;
		//buku_induk
        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
		$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
		$jmlBrgBI_curr = $jmlBrgBI_akhir - $jmlBrgBI_awal;
		$jmlHrgBI_awal = $isi['jmlHrgBI_awal'];		       
		$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
		$jmlHrgBI_curr = $jmlHrgBI_akhir - $jmlHrgBI_awal;		
		//pemelihara
        $jmlHrgPLH_awal = $isi['jmlHrgPLH_awal'];		
        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
		$jmlHrgPLH_curr = $jmlHrgPLH_akhir - $jmlHrgPLH_awal;		
		//pengaman
        $jmlHrgAman_awal = $isi['jmlHrgAman_awal'];
		$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
        $jmlHrgAman_curr = $jmlHrgAman_akhir - $jmlHrgAman_awal;
		//hapus pelihara
		$jmlHrgHPS_PLH_awal = $isi['jmlHrgHPS_PLH_awal'];   
		$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
		$jmlHrgHPS_PLH_curr = $jmlHrgHPS_PLH_akhir - $jmlHrgHPS_PLH_awal;
		//hapus pengaman
		$jmlHrgHPS_Aman_awal = $isi['jmlHrgHPS_Aman_awal']; 		   		
		$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
		$jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal;
		//mutasi pelihara
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_PLH_akhir = $get['sumbiaya'];
		$jmlHrgMut_PLH_curr = $jmlHrgMut_PLH_akhir - $jmlHrgMut_PLH_awal;
		//mutasi pengaman
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_Aman_awal = $get['sumbiaya'];	
		$get= mysql_fetch_array( mysql_query(			
			"select f, sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		 
		$jmlHrgMut_Aman_akhir = $get['sumbiaya']; 	  
		$jmlHrgMut_Aman_curr = $jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal;	
			
		//pindahtangan ----------------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_awal = $get['sumbrg'];
		$jmlHrgPindah_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_akhir = $get['sumbrg'];
		$jmlHrgPindah_akhir = $get['sumbiaya'];		
		$jmlHrgPindah_curr = $jmlHrgPindah_akhir - $jmlHrgPindah_awal;
		//pindahtangan pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_PLH_curr = $jmlHrgPindah_PLH_akhir - $jmlHrgPindah_PLH_awal;	
		//pindahtangan pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_akhir = $get['sumbiaya'];
		$jmlHrgPindah_Aman_curr = $jmlHrgPindah_Aman_akhir - $jmlHrgPindah_Aman_awal;	
		
		//gantirugi --------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi
			where $Kondisi and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_awal = $get['sumbrg'];
		$jmlHrgGantirugi_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi 
			where $Kondisi and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_akhir = $get['sumbrg'];
		$jmlHrgGantirugi_akhir = $get['sumbiaya'];		
		$jmlHrgGantirugi_curr = $jmlHrgGantirugi_akhir - $jmlHrgGantirugi_awal;
		//Gantirugi pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_PLH_curr = $jmlHrgGantirugi_PLH_akhir - $jmlHrgGantirugi_PLH_awal;	
		//Gantirugi pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_akhir = $get['sumbiaya'];
		$jmlHrgGantirugi_Aman_curr = $jmlHrgGantirugi_Aman_akhir - $jmlHrgGantirugi_Aman_awal;	
		
        //hitung row --------------------------------------------------------------------------        
        //saldo awal
		$jmlBrg_awal = $jmlBrgBI_awal - ($jmlBrgHPS_awal + $jmlBrgPindah_awal + $jmlBrgGantirugi_awal);        
		$jmlHrg_awal = 
			($jmlHrgBI_awal + $jmlHrgPLH_awal + $jmlHrgAman_awal +  $jmlHrgMut_PLH_awal+ $jmlHrgMut_Aman_awal) - 
			($jmlHrgHPS_awal + $jmlHrgHPS_PLH_awal + $jmlHrgHPS_Aman_awal + 
			$jmlHrgPindah_awal + $jmlHrgPindah_PLH_awal + $jmlHrgPindah_Aman_awal +
			$jmlHrgGantirugi_awal + $jmlHrgGantirugi_PLH_awal + $jmlHrgGantirugi_Aman_awal 
			); 
        //bertambah
		$jmlBrgTambah_curr = $jmlBrgBI_curr;						
		$jmlHrgTambahBi_curr = $jmlHrgBI_curr;
		$jmlHrgTambahPLH_curr = $jmlHrgPLH_curr + $jmlHrgMut_PLH_curr;
		$jmlHrgTambahAman_curr = $jmlHrgAman_curr + $jmlHrgMut_Aman_curr;
		$jmlHrgTambah_curr = $jmlHrgTambahPLH_curr + $jmlHrgTambahAman_curr + $jmlHrgTambahBi_curr;
		//berkurang
		$jmlBrgKurang_curr = $jmlBrgHPS_curr + $jmlBrgPindah_curr + $jmlBrgGantirugi_curr;
		$jmlHrgKurangPLH_curr = $jmlHrgHPS_PLH_curr + $jmlHrgPindah_PLH_curr + $jmlHrgGantirugi_PLH_curr;
		$jmlHrgKurangAman_curr = $jmlHrgHPS_Aman_curr + $jmlHrgPindah_Aman_curr + $jmlHrgGantirugi_Aman_curr;
		$jmlHrgKurangBi_curr = $jmlHrgHPS_curr + $jmlHrgPindah_curr + $jmlHrgGantirugi_curr;
		$jmlHrgKurang_curr =  $jmlHrgKurangPLH_curr + $jmlHrgKurangAman_curr +  $jmlHrgKurangBi_curr; //echo "<br> $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr ";
		
		/*echo " $jmlHrgKurang_curr = 
			$jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr +
			$jmlHrgPindah_curr + $jmlHrgPindah_PLH_curr + $jmlHrgPindah_Aman_curr; <br> ";	
        */
        //akhir
		//$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir;
		$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir - $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
        $jmlHrg_akhir = 
			($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir + $jmlHrgMut_PLH_akhir+ $jmlHrgMut_Aman_akhir) - 
			($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir +
			$jmlHrgPindah_akhir + $jmlHrgPindah_PLH_akhir + $jmlHrgPindah_Aman_akhir +
			$jmlHrgGantirugi_akhir + $jmlHrgGantirugi_PLH_akhir + $jmlHrgGantirugi_Aman_akhir);
        
		//hit total --------------------------------------------------------------------------------
        //awal ----------------------------------------
		$totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
		
		//kurang barang --------------------------------
        $totBrgHPS_curr += $isi['g'] == "00" ? $jmlBrgKurang_curr : 0;
		//kurang harga
		$totHrgKurang_curr += $isi['g'] == "00" ? $jmlHrgKurang_curr : 0;		
		//kurang pelihara
		$totHrgHPS_PLH_curr += $isi['g'] == "00" ? $jmlHrgKurangPLH_curr : 0;
		//kurang aman
		$totHrgHPS_Aman_curr += $isi['g'] == "00" ? $jmlHrgKurangAman_curr : 0;		
		//kurang perolehan
		$totHrgHPS_curr += $isi['g'] == "00" ? $jmlHrgKurangBi_curr : 0;//?
		
        //tambah barang --------------------------------
        $totBrgTambah_curr += $isi['g'] == "00" ? $jmlBrgTambah_curr : 0;
		//tambah harga
		$totHrgTambah_curr += $isi['g'] == "00" ? $jmlHrgTambah_curr : 0;		
		//tambah pelihara		
		$totHrgPLH_curr += $isi['g'] == "00" ? $jmlHrgTambahPLH_curr : 0;
		//$totHrgMut_PLH_curr += $isi['g'] == "00" ? $jmlHrgMut_PLH_curr : 0;
		//tambah aman
		$totHrgAman_curr += $isi['g'] == "00" ? $jmlHrgTambahAman_curr : 0;
		//$totHrgMut_Aman_curr += $isi['g'] == "00" ? $jmlHrgMut_Aman_curr : 0;		
		//tambah perolehan
        $totHrgBI_curr += $isi['g'] == "00" ? $jmlHrgTambahBi_curr : 0;		
		
		//akhir ----------------------------------------
        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
		
		
		
		
        //tampil row --------------------------------------------------
        //dlm ribuan
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, '.', ''): number_format($jmlHrg_awal, 2, '.', '');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, '.', ''): number_format($jmlHrgTambah_curr, 2, '.', '');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahPLH_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahPLH_curr, 2, '.', '');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahAman_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahAman_curr, 2, '.', '');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahBi_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahBi_curr, 2, '.', '');
        
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, '.', ''): number_format($jmlHrgKurang_curr, 2, '.', '');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangBi_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangBi_curr, 2, '.', '');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangPLH_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangPLH_curr, 2, '.', '');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangAman_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangAman_curr, 2, '.', '');
        
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, '.', ''): number_format($jmlHrg_akhir, 2, '.', '');
		
		//$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, '.', ''): number_format($jmlHrgMut_PLH_curr, 2, '.', '');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, '.', ''): number_format($jmlHrgMut_Aman_curr, 2, '.', '');
        
		//bold
        $tampil_jmlBrg_awal = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_awal, 0, '.', '') . "" : "" . number_format($jmlBrg_awal, 0, '.', '') . "";
        $tampil_jmlBrgHPS_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgHPS_curr, 0, '.', '') . "" : "" . number_format($jmlBrgHPS_curr, 0, '.', '') . "";
        $tampil_jmlBrgTambah_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgTambah_curr, 0, '.', '') . "" : "" . number_format($jmlBrgTambah_curr, 0, '.', '') . "";
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, '.', '') . "" : "" . number_format($jmlBrg_akhir, 0, '.', '') . "";
        $tampil_jmlHrg_awal = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_awal . "" : $tampil_jmlHrg_awal;
        
		
        $tampil_jmlHrgTambah_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgTambah_curr . "" : $tampil_jmlHrgTambah_curr;
        $tampil_jmlHrgPLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgPLH_curr . "" : $tampil_jmlHrgPLH_curr;
        $tampil_jmlHrgAman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgAman_curr . "" : $tampil_jmlHrgAman_curr;
        
		$tampil_jmlHrgBI_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgBI_curr . "" : $tampil_jmlHrgBI_curr;
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		
		$tampil_jmlHrgKurang_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgKurang_curr . "" : $tampil_jmlHrgKurang_curr;
		$tampil_jmlHrgHPS_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_curr . "" : $tampil_jmlHrgHPS_curr;
		$tampil_jmlHrgHPS_PLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_PLH_curr . "" : $tampil_jmlHrgHPS_PLH_curr;
		$tampil_jmlHrgHPS_Aman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_Aman_curr . "" : $tampil_jmlHrgHPS_Aman_curr;
		$tampil_jmlHrgMut_PLH_curr = addbold( number_format_ribuan_xls($jmlHrgMut_PLH_curr, $dlmRibuan), $isi['g'] == "00" );
		$tampil_jmlHrgMut_Aman_curr = addbold( number_format_ribuan_xls($jmlHrgMut_Aman_curr, $dlmRibuan), $isi['g'] == "00" );
        //with td
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgTambah_curr</td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgPLH_curr</td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgAman_curr</td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgBI_curr</td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgKurang_curr</td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_curr</td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_PLH_curr</td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_Aman_curr</td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_PLH_curr</td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_Aman_curr</td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					
				";
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                    break;
                }
            case 3: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_akhir</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_akhir</div></td>
					$tampilKet
				";
                    break;
                }
        }
        //$tampil_jmlHrgTambah_curr='';
        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><div class=\"nfmt5\">{$isi['f']}</div></td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><div class=\"nfmt5\">$kdBidang</div></td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			$TampilStyle
        </tr>
		";
    }
    //tampil total -------------------------------------
	//if($noawal == 0  ){
		
	
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, '.', ''): number_format($totHrg_awal, 2, '.', '');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, '.', ''): number_format($totHrgHPS_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', ''): number_format($totHrg_akhir, 2, '.', '');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, '.', ''): number_format($totHrgTambah_curr, 2, '.', '');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, '.', ''): number_format($totHrgPLH_curr, 2, '.', '');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, '.', ''): number_format($totHrgAman_curr, 2, '.', '');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, '.', ''): number_format($totHrgBI_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', ''): number_format($totHrg_akhir, 2, '.', '');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, '.', ''): number_format($totHrgKurang_curr, 2, '.', '');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, '.', ''): number_format($totHrgHPS_PLH_curr, 2, '.', '');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, '.', ''): number_format($totHrgHPS_Aman_curr, 2, '.', '');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgTambah_curr . "</td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgPLH_curr . "</td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgAman_curr . "</td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgBI_curr . "</td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgKurang_curr . "</td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_curr . "</td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_PLH_curr . "</td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_Aman_curr . "</td>";
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 3: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_akhir . "</div></td>
				$tampilKet
				";
                break;
            }
    }
	//}
    $ListData .= "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>TOTAL</td>
			$TampilStyleTot
			</tr>
			";//.'no awal='.$noawal



    //return $ListData;
    //return compact($ListData, $jmlData);
	//$ListData = '';
    return array($ListData, $jmlData);
}

function list_header($XXBIDANG='BIDANG',$XXASISTEN='ASISTEN',
$XXBIRO='BIRO',$XXKOTA='KOTA',$XXPROP='PROPINSI',$XXKDLOK='KDLOKASI',$XXSEKSI) {
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
	mso-number-format:"0\.00_ ";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}		
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
.nfmt5 {
	mso-number-format:"00";
	
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";
	}
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

			<td class="judulcetak" colspan="7"><DIV ALIGN=CENTER>REKAPITULASI BUKU INVENTARIS BARANG</DIV></td>
		</tr>
	</table>

<table cellpadding=0 cellspacing=0 border=0 width="100%"> 
			<tr>
			<td align=left  colspan="2" style="font-weight:bold;font-size:9pt" >BIDANG</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXBIDANG.'</td> 
			</tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >SKPD</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXASISTEN.'</td>
			</tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >UNIT</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXBIRO.'</td> </tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >SUB UNIT</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXSEKSI.'</td> </tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >KABUPATEN/KOTA</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXKOTA.'</td> </tr> 
			<tr> 
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >PROVINSI</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" > JAWA BARAT</td> </tr> 
			</table>

	<table width="100%" border="0">
		<tr>
			<td align=right style="font-weight:bold;font-size:9pt" colspan="8">&nbsp;</td>
		</tr>
	</table>
	<table class="cetak" border="1">
		
';
return $isix;	
}

function list_tableheader($dlmribuan='')
{
$isix='	<thead>
		<tr>
		<th class="th01"  width="50">Nomor</th>
		<th class="th01"  width="50">Golongan</th>
		<th class="th01"   width="50">Kode Bidang Barang</th>

		<th class="th01" >Nama Bidang Barang</th>
		<th class="th01" width="120">Jumlah Barang</th>
		<th class="th01" width="120">Jumlah Harga</th>
		</tr>
		</thead>
';	
return $isix;		
}

function list_table($XXNO='',$XXGOL='',$XXKDB='',$XXNBB='',$XXJB=0,$XXHARGA=0,$XXKET='')
{
if (empty($XXKDB)) {
	$sharga ='<b>'.$XXHARGA.'</b>';
} else {
	$sharga =$XXHARGA;
}
$isix='<tr>
			<td class="GCTK" align=right>'.$XXNO.'&nbsp;</td>
			<td class="GCTK" align=center><div class="nfmt5">'.$XXGOL.'</div></td>
			<td class="GCTK" align=center><div class="nfmt5">'.$XXKDB.'</div></td>
			<td class="GCTK">'.$XXNBB.'</td>
			<td class="GCTK" align=center><div class="nfmt1">'.$XXJB.'</div></td>
			<td class="GCTK" align=right><div class="nfmt4">'.$sharga.'</div></td>
			<td class="GCTK">'.$XXKET.'</td>
		</tr>
';
return $isix;			
}

function list_tablefooter($XXJMLB=0,$XXJMLH=0,$dlmribuan='')
{
$isix='<tr>
<td class="GCTK" colspan=4 ><b>Total</b></td>
<td class="GCTK" align=center><b><div class="nfmt1">'.$XXJMLB.'</div></b></td>
<td class="GCTK" align=right><b><div class="nfmt4">'.$XXJMLH.'</div></b></td>
<td class="GCTK" >&nbsp;</td>
</tr> 
	</table>
';
return $isix;			
}

function list_footer($XXTMPTGL='',$XXJBT1='',$XXNM1='',$XXNIP1='',
$XXJBT2='',$XXNM2='',$XXNIP2='')
{
$isix='<table style="width:30cm" border=0> 
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td align=center colspan=3 >&nbsp;</td>
				</tr>

				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td align=center colspan=3 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td align=center colspan=3 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP2.'</B> </td>
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
function Mutasi_RekapByBrg_GetList2_keu_xls($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT,  
	$noawal, $limitHal, $kolomwidth, $dlmRibuan=TRUE, $cetak=FALSE, 
	$Style=1, $fmSEKSI='00', $fmKONDBRG='7',$jns_aset=0,$jmlBarangAwal=0,$jmlHargaAwal=0,$tampiltot=0) {
    // ------------------------------
    // $Style=1 = total penambahan, 2= pemelihara, pemgaman, peroleh, 3 = saldo akhir sampai dgn tglakhir
    // ------------------------------
    global $Main;
    global $tglAwal, $tglAkhir; //$fmSemester, $fmTahun;

	$cek='';
	if ($jns_aset==1){
		$Kondisi_aset=" and aset_tetap=0 ";
	} else {
		$Kondisi_aset=" and aset_tetap=1 ";		
	}	

    $clGaris = $cetak == TRUE ? "GCTK" : "GarisDaftar";
    //get kondisi (skpd) ----------------------------------------------------------------------------------
    $KondisiD = $fmUNIT == "00" ? "" : " and d='$fmUNIT' ";
    $KondisiE = $fmSUBUNIT == "00" ? "" : " and e='$fmSUBUNIT' ";
	$KondisiE1 = $fmSEKSI =='' || $fmSEKSI == "000" || $fmSEKSI == "00"  ? "" : " and e1='$fmSEKSI' ";
    $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
					and c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 $Kondisi_aset ";
    if ($fmSKPD == "00") {
        $Kondisi = "  a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}'
		$KondisiD $KondisiE $KondisiE1 $Kondisi_aset ";
    }
/*	
	//kondisi barang
	switch($fmKONDBRG){
		case '1' :case '2': case '3': case '4' :{
			$Kondisi .= $Kondisi==''? " kondisi = '$fmKONDBRG' ": " and kondisi = '$fmKONDBRG' ";
			break;
		}

		case '5' :{
			$Kondisi .= $Kondisi==''? " kondisi in('1','2','3') ": " and kondisi   in('1','2','3') ";
			break;
		}
	}
*/
    //list --------------------------------------------------------------
    $ListData = "";
    $cb = 0;
    $no = $noawal;
	
	/*
	#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) jj on aa.f=jj.f and aa.g=jj.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan < '$tglAwal' group by f) kk on aa.f=kk.f and aa.g=kk.g 			
			#left join (select f , g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) ll on aa.f=ll.f and aa.g=ll.g 
			#left join (select f , g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where $Kondisi and tambah_aset=1 and tgl_penghapusan <= '$tglAkhir' group by f) mm on aa.f=mm.f and aa.g=mm.g 
			
	*/
    $sqry = "
		
		select aa.f, aa.g,  aa.nm_barang,
			bb.jmlBrgHPS_awal, bb.jmlHrgHPS_awal,
			cc.jmlPLH_awal, cc.jmlHrgPLH_awal,
			dd.jmlAman_awal, dd.jmlHrgAman_awal,
			ee.jmlBrgBI_awal, ee.jmlHrgBI_awal,
			ff.jmlBrgHPS_akhir, ff.jmlHrgHPS_akhir,
			gg.jmlPLH_akhir, gg.jmlHrgPLH_akhir,
			hh.jmlAman_akhir, hh.jmlHrgAman_akhir,
			ii.jmlBrgBI_akhir, ii.jmlHrgBI_akhir,
			jj.jmlHrgHPS_PLH_awal,
			kk.jmlHrgHPS_Aman_awal,
			ll.jmlHrgHPS_PLH_akhir,
			mm.jmlHrgHPS_Aman_akhir,
			nn.jmlHSBG_awal, nn.jmlHrgHSBG_awal,
			oo.jmlHSBG_akhir, oo.jmlHrgHSBG_akhir,
			pp.jmlHrgHPS_HSBG_awal,
			qq.jmlHrgHPS_HSBG_akhir
			
			 ".		
		"from ref_barang aa 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from view_buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from view_buku_induk  where $Kondisi and  tgl_buku <= '$tglAkhir'  group by f,g with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_awal, sum(harga_hapus) as jmlHrgHSBG_awal from v_hapus_sebagian where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) nn on aa.f=nn.f and aa.g=nn.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlHSBG_akhir, sum(harga_hapus ) as jmlHrgHSBG_akhir from v_hapus_sebagian where $Kondisi  and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) oo on aa.f=oo.f and aa.g=oo.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus ) as jmlHrgHPS_HSBG_awal from v2_penghapusan_hapussebagian where  tgl_penghapusan < '$tglAwal' $KondisiKIB group by f,g with rollup ) pp on aa.f=pp.f  and aa.g=pp.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(harga_hapus) as jmlHrgHPS_HSBG_akhir from v2_penghapusan_hapussebagian where  tgl_penghapusan <= '$tglAkhir'  $KondisiKIB group by f,g with rollup ) qq on aa.f=qq.f  and aa.g=qq.g  

			".			
		" where aa.h='00'  
		order by aa.f, aa.g		
	"; // echo "$sqry";
	// $cek.=$sqry;
    $QryRefBarang = mysql_query($sqry);    
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;    
    while ($isi = mysql_fetch_array($QryRefBarang)) {
        //get kondisi1 (barang) ----------------------------------
        $kdBidang = $isi['g'] == "00" ? "" : $isi['g'];
        $nmBarang = $isi['g'] == "00" ? "<b>{$isi['nm_barang']}</b>" : "&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
        $no++;
        if ($cetak == FALSE) {
            $clRow = $no % 2 == 0 ? "row1" : "row0";
        } else {
            $clRow = '';
        }
        $Kondisi1 = " concat(f, g)= '{$isi['f']}{$isi['g']}' ";
        $KondisiBi = " status_barang<>3 ";
		$KondisiFG = $isi['g'] == "00" ? "f='{$isi['f']}'" : "f='{$isi['f']}' and g='{$isi['g']}'";
		$groupFG = $isi['g'] == "00" ? "group by f" : "group by f,g";

        //data --------------------------------------------------
		//penghapusan
        $jmlBrgHPS_awal = $isi['jmlBrgHPS_awal'];
		$jmlHrgHPS_awal = $isi['jmlHrgHPS_awal'];		
		$jmlBrgHPS_akhir = $isi['jmlBrgHPS_akhir'];
		$jmlHrgHPS_akhir = $isi['jmlHrgHPS_akhir'];		
		$jmlBrgHPS_curr = $jmlBrgHPS_akhir - $jmlBrgHPS_awal;
		$jmlHrgHPS_curr = $jmlHrgHPS_akhir - $jmlHrgHPS_awal;
		//buku_induk
//        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
        $jmlBrgBI_awal = $isi['jmlBrgBI_awal'];        
		$jmlBrgBI_akhir = $isi['jmlBrgBI_akhir']; 
		$jmlBrgBI_curr = $jmlBrgBI_akhir - $jmlBrgBI_awal;
		$jmlHrgBI_awal = $isi['jmlHrgBI_awal'];		       
		$jmlHrgBI_akhir = $isi['jmlHrgBI_akhir'];		
		$jmlHrgBI_curr = $jmlHrgBI_akhir - $jmlHrgBI_awal;		
		//pemelihara
        $jmlHrgPLH_awal = $isi['jmlHrgPLH_awal'];		
        $jmlHrgPLH_akhir = $isi['jmlHrgPLH_akhir'];
		$jmlHrgPLH_curr = $jmlHrgPLH_akhir - $jmlHrgPLH_awal;		
		//pengaman
        $jmlHrgAman_awal = $isi['jmlHrgAman_awal'];
		$jmlHrgAman_akhir = $isi['jmlHrgAman_akhir'];
        $jmlHrgAman_curr = $jmlHrgAman_akhir - $jmlHrgAman_awal;
		//hapus sebagian
        $jmlHrgHSBG_awal = $isi['jmlHrgHSBG_awal'];		
        $jmlHrgHSBG_akhir = $isi['jmlHrgHSBG_akhir'];
		$jmlHrgHSBG_curr = $jmlHrgHSBG_akhir - $jmlHrgHSBG_awal;		
		
		//hapus pelihara
		$jmlHrgHPS_PLH_awal = $isi['jmlHrgHPS_PLH_awal'];   
		$jmlHrgHPS_PLH_akhir = $isi['jmlHrgHPS_PLH_akhir'];   
		$jmlHrgHPS_PLH_curr = $jmlHrgHPS_PLH_akhir - $jmlHrgHPS_PLH_awal;
		//hapus pengaman
		$jmlHrgHPS_Aman_awal = $isi['jmlHrgHPS_Aman_awal']; 		   		
		$jmlHrgHPS_Aman_akhir = $isi['jmlHrgHPS_Aman_akhir'];
		$jmlHrgHPS_Aman_curr = $jmlHrgHPS_Aman_akhir - $jmlHrgHPS_Aman_awal;
		//hapus hapus sebagian
		$jmlHrgHPS_HSBG_awal = $isi['jmlHrgHPS_HSBG_awal'];   
		$jmlHrgHPS_HSBG_akhir = $isi['jmlHrgHPS_HSBG_akhir'];   
		$jmlHrgHPS_HSBG_curr = $jmlHrgHPS_HSBG_akhir - $jmlHrgHPS_HSBG_awal;
		
		//mutasi pelihara
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_PLH_akhir = $get['sumbiaya'];
		$jmlHrgMut_PLH_curr = $jmlHrgMut_PLH_akhir - $jmlHrgMut_PLH_awal;
		//mutasi pengaman
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgMut_Aman_awal = $get['sumbiaya'];	
		$get= mysql_fetch_array( mysql_query(			
			"select f, sum(biaya_pengamanan ) as sumbiaya from v2_mutasi_pengaman
			where $Kondisi and tambah_aset=1 and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		 
		$jmlHrgMut_Aman_akhir = $get['sumbiaya']; 	  
		$jmlHrgMut_Aman_curr = $jmlHrgMut_Aman_akhir - $jmlHrgMut_Aman_awal;	

		//mutasi hapus sebagian
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian
			where $Kondisi and tgl_buku < '$tglAwal' and $KondisiFG $groupFG " 
		)); //echo "select sum(biaya_pemeliharaan ) as sumbiaya from v2_mutasi_pelihara 
			//where $Kondisi and tambah_aset=1 and tgl_buku < '$tglAwal' and $KondisiFG $groupFG ";
		$jmlHrgMut_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_mutasi_hapussebagian 
			where $Kondisi and tgl_buku <= '$tglAkhir' and $KondisiFG $groupFG " 
		));		
		$jmlHrgMut_HSBG_akhir = $get['sumbiaya'];
		$jmlHrgMut_HSBG_curr = $jmlHrgMut_HSBG_akhir - $jmlHrgMut_HSBG_awal;
			
		//pindahtangan ----------------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_awal = $get['sumbrg'];
		$jmlHrgPindah_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_pindahtangan 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgPindah_akhir = $get['sumbrg'];
		$jmlHrgPindah_akhir = $get['sumbiaya'];		
		$jmlHrgPindah_curr = $jmlHrgPindah_akhir - $jmlHrgPindah_awal;
		//pindahtangan pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_pindahtangan_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_PLH_curr = $jmlHrgPindah_PLH_akhir - $jmlHrgPindah_PLH_awal;	
		//pindahtangan pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_pindahtangan_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_Aman_akhir = $get['sumbiaya'];
		$jmlHrgPindah_Aman_curr = $jmlHrgPindah_Aman_akhir - $jmlHrgPindah_Aman_awal;	
		//pindahtangan hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and  tgl_pemindahtanganan < '$tglAwal' and $KondisiFG $groupFG " 
		));
		
		$jmlHrgPindah_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus ) as sumbiaya from v2_pindahtangan_hapussebagian 
			where $Kondisi and tgl_pemindahtanganan < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgPindah_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgPindah_HSBG_curr = $jmlHrgPindah_HSBG_akhir - $jmlHrgPindah_HSBG_awal;	
		
		//gantirugi --------------------------------------------------
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi
			where $Kondisi and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_awal = $get['sumbrg'];
		$jmlHrgGantirugi_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(jml_barang) as sumbrg, sum(jml_harga ) as sumbiaya from v1_gantirugi 
			where $Kondisi and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlBrgGantirugi_akhir = $get['sumbrg'];
		$jmlHrgGantirugi_akhir = $get['sumbiaya'];		
		$jmlHrgGantirugi_curr = $jmlHrgGantirugi_akhir - $jmlHrgGantirugi_awal;
		//Gantirugi pelihara		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pemeliharaan ) as sumbiaya from v2_gantirugi_pelihara 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_PLH_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_PLH_curr = $jmlHrgGantirugi_PLH_akhir - $jmlHrgGantirugi_PLH_awal;	
		//Gantirugi pengaman		
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(biaya_pengamanan ) as sumbiaya from v2_gantirugi_pengaman 
			where $Kondisi and tambah_aset=1 and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_Aman_akhir = $get['sumbiaya'];
		$jmlHrgGantirugi_Aman_curr = $jmlHrgGantirugi_Aman_akhir - $jmlHrgGantirugi_Aman_awal;

		//Gantirugi hapus sebagian		
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAwal' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_awal = $get['sumbiaya'];
		$get= mysql_fetch_array( mysql_query(
			"select sum(harga_hapus) as sumbiaya from v2_gantirugi_hapussebagian 
			where $Kondisi  and tgl_gantirugi < '$tglAkhir' and $KondisiFG $groupFG " 
		));
		$jmlHrgGantirugi_HSBG_akhir = $get['sumbiaya'];	
		$jmlHrgGantirugi_HSBG_curr = $jmlHrgGantirugi_HSBG_akhir - $jmlHrgGantirugi_HSBG_awal;	
			
		
        //hitung row --------------------------------------------------------------------------        
        //saldo awal
		$jmlBrg_awal = $jmlBrgBI_awal - ($jmlBrgHPS_awal + $jmlBrgPindah_awal + $jmlBrgGantirugi_awal);        
		$jmlHrg_awal = 
			($jmlHrgBI_awal + $jmlHrgPLH_awal + $jmlHrgAman_awal +  $jmlHrgMut_PLH_awal+ $jmlHrgMut_Aman_awal) - 
			($jmlHrgHPS_awal + $jmlHrgHPS_PLH_awal + $jmlHrgHPS_Aman_awal + 
			$jmlHrgPindah_awal + $jmlHrgPindah_PLH_awal + $jmlHrgPindah_Aman_awal +
			$jmlHrgGantirugi_awal + $jmlHrgGantirugi_PLH_awal + $jmlHrgGantirugi_Aman_awal 
			); 
        //bertambah
		$jmlBrgTambah_curr = $jmlBrgBI_curr;						
		$jmlHrgTambahBi_curr = $jmlHrgBI_curr;
		$jmlHrgTambahPLH_curr = $jmlHrgPLH_curr + $jmlHrgMut_PLH_curr;
		$jmlHrgTambahAman_curr = $jmlHrgAman_curr + $jmlHrgMut_Aman_curr;
		$jmlHrgTambahHSBG_curr = $jmlHrgHPS_HSBG_curr + $jmlHrgPindah_HSBG_curr + $jmlHrgGantirugi_HSBG_curr;

		$jmlHrgTambah_curr = $jmlHrgTambahPLH_curr + $jmlHrgTambahAman_curr + $jmlHrgTambahBi_curr+$jmlHrgTambahHSBG_curr;
		//berkurang
		$jmlBrgKurang_curr = $jmlBrgHPS_curr + $jmlBrgPindah_curr + $jmlBrgGantirugi_curr;
		$jmlHrgKurangPLH_curr = $jmlHrgHPS_PLH_curr + $jmlHrgPindah_PLH_curr + $jmlHrgGantirugi_PLH_curr;
		$jmlHrgKurangAman_curr = $jmlHrgHPS_Aman_curr + $jmlHrgPindah_Aman_curr + $jmlHrgGantirugi_Aman_curr;
		$jmlHrgKurangBi_curr = $jmlHrgHPS_curr + $jmlHrgPindah_curr + $jmlHrgGantirugi_curr;
		$jmlHrgKurangHSBG_curr = $jmlHrgHSBG_curr + $jmlHrgMut_HSBG_curr;


		$jmlHrgKurang_curr =  $jmlHrgKurangPLH_curr + $jmlHrgKurangAman_curr +  $jmlHrgKurangBi_curr+$jmlHrgKurangHSBG_curr;
		
		//echo "<br> $jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr ";
		
		/*echo " $jmlHrgKurang_curr = 
			$jmlHrgHPS_curr + $jmlHrgHPS_PLH_curr + $jmlHrgHPS_Aman_curr +
			$jmlHrgPindah_curr + $jmlHrgPindah_PLH_curr + $jmlHrgPindah_Aman_curr; <br> ";	
        */
        //akhir
		//$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir;
		$jmlBrg_akhir = $jmlBrgBI_akhir - $jmlBrgHPS_akhir - $jmlBrgPindah_akhir - $jmlBrgGantirugi_akhir;
        $jmlHrg_akhir = 
			($jmlHrgPLH_akhir + $jmlHrgAman_akhir + $jmlHrgBI_akhir + $jmlHrgMut_PLH_akhir+ $jmlHrgMut_Aman_akhir
			+ $jmlHrgHPS_HSBG_akhir + $jmlHrgPindah_HSBG_akhir+ $jmlHrgGantirugi_HSBG_akhir ) - 
			($jmlHrgHPS_akhir + $jmlHrgHPS_PLH_akhir + $jmlHrgHPS_Aman_akhir +
			$jmlHrgPindah_akhir + $jmlHrgPindah_PLH_akhir + $jmlHrgPindah_Aman_akhir +
			$jmlHrgGantirugi_akhir + $jmlHrgGantirugi_PLH_akhir + $jmlHrgGantirugi_Aman_akhir +
			$jmlHrgHSBG_akhir+ $jmlHrgMut_HSBG_akhir
			);
        
		//hit total --------------------------------------------------------------------------------
        //awal ----------------------------------------
		$totBrg_awal += $isi['g'] == "00" ? $jmlBrg_awal : 0;
        $totHrg_awal += $isi['g'] == "00" ? $jmlHrg_awal : 0;
		
		//kurang barang --------------------------------
        $totBrgHPS_curr += $isi['g'] == "00" ? $jmlBrgKurang_curr : 0;
		//kurang harga
		$totHrgKurang_curr += $isi['g'] == "00" ? $jmlHrgKurang_curr : 0;		
		//kurang pelihara
		$totHrgHPS_PLH_curr += $isi['g'] == "00" ? $jmlHrgKurangPLH_curr : 0;
		//kurang aman
		$totHrgHPS_Aman_curr += $isi['g'] == "00" ? $jmlHrgKurangAman_curr : 0;		
		//kurang perolehan
		$totHrgHPS_curr += $isi['g'] == "00" ? $jmlHrgKurangBi_curr : 0;//?
		
        //tambah barang --------------------------------
        $totBrgTambah_curr += $isi['g'] == "00" ? $jmlBrgTambah_curr : 0;
		//tambah harga
		$totHrgTambah_curr += $isi['g'] == "00" ? $jmlHrgTambah_curr : 0;		
		//tambah pelihara		
		$totHrgPLH_curr += $isi['g'] == "00" ? $jmlHrgTambahPLH_curr : 0;
		//$totHrgMut_PLH_curr += $isi['g'] == "00" ? $jmlHrgMut_PLH_curr : 0;
		//tambah aman
		$totHrgAman_curr += $isi['g'] == "00" ? $jmlHrgTambahAman_curr : 0;
		//$totHrgMut_Aman_curr += $isi['g'] == "00" ? $jmlHrgMut_Aman_curr : 0;		
		//tambah perolehan
        $totHrgBI_curr += $isi['g'] == "00" ? $jmlHrgTambahBi_curr : 0;		
		
		//akhir ----------------------------------------
        $totBrg_akhir += $isi['g'] == "00" ? $jmlBrg_akhir : 0;
        $totHrg_akhir += $isi['g'] == "00" ? $jmlHrg_akhir : 0;
		
		
		
        //tampil row --------------------------------------------------
        //dlm ribuan
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, '.', ''): number_format($jmlHrg_awal, 2, '.', '');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, '.', ''): number_format($jmlHrgTambah_curr, 2, '.', '');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahPLH_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahPLH_curr, 2, '.', '');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahAman_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahAman_curr, 2, '.', '');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahBi_curr / 1000), 2, '.', ''): number_format($jmlHrgTambahBi_curr, 2, '.', '');
        
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, '.', ''): number_format($jmlHrgKurang_curr, 2, '.', '');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangBi_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangBi_curr, 2, '.', '');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangPLH_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangPLH_curr, 2, '.', '');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangAman_curr / 1000), 2, '.', ''): number_format($jmlHrgKurangAman_curr, 2, '.', '');
        
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, '.', ''): number_format($jmlHrg_akhir, 2, '.', '');
		
		//$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, '.', ''): number_format($jmlHrgMut_PLH_curr, 2, '.', '');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, '.', ''): number_format($jmlHrgMut_Aman_curr, 2, '.', '');
        
		//bold
        $tampil_jmlBrg_awal = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_awal, 0, '.', '') . "" : "" . number_format($jmlBrg_awal, 0, '.', '') . "";
        $tampil_jmlBrgHPS_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgHPS_curr, 0, '.', '') . "" : "" . number_format($jmlBrgHPS_curr, 0, '.', '') . "";
        $tampil_jmlBrgTambah_curr = $isi['g'] == "00" ? "<b>" . number_format($jmlBrgTambah_curr, 0, '.', '') . "" : "" . number_format($jmlBrgTambah_curr, 0, '.', '') . "";
        $tampil_jmlBrg_akhir = $isi['g'] == "00" ? "<b>" . number_format($jmlBrg_akhir, 0, '.', '') . "" : "" . number_format($jmlBrg_akhir, 0, '.', '') . "";
        $tampil_jmlHrg_awal = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_awal . "" : $tampil_jmlHrg_awal;
        
		
        $tampil_jmlHrgTambah_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgTambah_curr . "" : $tampil_jmlHrgTambah_curr;
        $tampil_jmlHrgPLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgPLH_curr . "" : $tampil_jmlHrgPLH_curr;
        $tampil_jmlHrgAman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgAman_curr . "" : $tampil_jmlHrgAman_curr;
        
		$tampil_jmlHrgBI_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgBI_curr . "" : $tampil_jmlHrgBI_curr;
        $tampil_jmlHrg_akhir = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrg_akhir . "" : $tampil_jmlHrg_akhir;
		
		$tampil_jmlHrgKurang_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgKurang_curr . "" : $tampil_jmlHrgKurang_curr;
		$tampil_jmlHrgHPS_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_curr . "" : $tampil_jmlHrgHPS_curr;
		$tampil_jmlHrgHPS_PLH_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_PLH_curr . "" : $tampil_jmlHrgHPS_PLH_curr;
		$tampil_jmlHrgHPS_Aman_curr = $isi['g'] == "00" ? "<b>" . $tampil_jmlHrgHPS_Aman_curr . "" : $tampil_jmlHrgHPS_Aman_curr;
		$tampil_jmlHrgMut_PLH_curr = addbold( number_format_ribuan_xls($jmlHrgMut_PLH_curr, $dlmRibuan), $isi['g'] == "00" );
		$tampil_jmlHrgMut_Aman_curr = addbold( number_format_ribuan_xls($jmlHrgMut_Aman_curr, $dlmRibuan), $isi['g'] == "00" );
        //with td
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgTambah_curr</td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgPLH_curr</td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgAman_curr</td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgBI_curr</td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgKurang_curr</td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_curr</td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_PLH_curr</td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgHPS_Aman_curr</td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_PLH_curr</td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlHrgMut_Aman_curr</td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					
				";
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_awal</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_awal</td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgHPS_curr</td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrgTambah_curr</td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\">$tampil_jmlBrg_akhir</td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\">$tampil_jmlHrg_akhir</td>
					$tampilKet
					<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                    break;
                }
            case 3: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_akhir</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_akhir</div></td>
					$tampilKet
				";
                    break;
                }
        }
        //$tampil_jmlHrgTambah_curr='';
        $ListData .= "
			<tr class='$clRow'>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[1]\"><div class=\"nfmt5\">{$isi['f']}</div></td>
			<td class=\"$clGaris\" align=center width=\"$kolomwidth[2]\"><div class=\"nfmt5\">$kdBidang</div></td>
			<td class=\"$clGaris\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			$TampilStyle
        </tr>
		";
    }
    //tampil total -------------------------------------
	//if($noawal == 0  ){
		
	
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, '.', ''): number_format($totHrg_awal, 2, '.', '');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, '.', ''): number_format($totHrgHPS_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', ''): number_format($totHrg_akhir, 2, '.', '');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, '.', ''): number_format($totHrgTambah_curr, 2, '.', '');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, '.', ''): number_format($totHrgPLH_curr, 2, '.', '');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, '.', ''): number_format($totHrgAman_curr, 2, '.', '');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, '.', ''): number_format($totHrgBI_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', ''): number_format($totHrg_akhir, 2, '.', '');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, '.', ''): number_format($totHrgKurang_curr, 2, '.', '');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, '.', ''): number_format($totHrgHPS_PLH_curr, 2, '.', '');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, '.', ''): number_format($totHrgHPS_Aman_curr, 2, '.', '');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgTambah_curr . "</td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgPLH_curr . "</td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgAman_curr . "</td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgBI_curr . "</td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgKurang_curr . "</td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_curr . "</td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_PLH_curr . "</td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><b>" . $tampil_totHrgHPS_Aman_curr . "</td>";
	
	$totBrg_akhirx=$totBrg_akhir+$jmlBarangAwal;
	$totHrg_akhirx=$totHrg_akhir+$jmlHargaAwal;
	
	$tampil_totHrg_akhirx = $dlmRibuan == TRUE ? number_format(($totHrg_akhirx / 1000), 2, '.', '') : number_format($totHrg_akhirx, 2, '.', '');
		
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_awal . "</td>
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</td>
				<td align=right class=\"$clGaris\"><b>" . $tampil_totHrg_akhir . "</td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 3: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_akhir . "</div></td>
				$tampilKet
				";
                $TampilStyleTotx = "
				<td align=right class=\"$clGaris\"><div class='nfmt1'><b>" . number_format(($totBrg_akhirx), 2, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class='nfmt4'><b>" . $tampil_totHrg_akhirx . "</div></td>
				$tampilKet
				";					
                break;
            }
    }
	//}
		if ($jns_aset==1){
    $ListData = "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>B.&nbsp;&nbsp;ASET LAINNYA</td>
			$TampilStyleTot
			</tr>
			";//.'no awal='.$noawal
	} else {
    $ListData = "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>A.&nbsp;&nbsp;ASET TETAP</td>
			$TampilStyleTot
			</tr>
			".$ListData;//.'no awal='.$noawal	
	}
	if ($tampiltot==1)
	{
    $ListData .= "
			<tr class=''>
			<td colspan=4 class=\"$clGaris\"><b>TOTAL</td>
			$TampilStyleTotx
			</tr>
			";//.'no awal='.$noawal
		
	}


    //return $ListData;
    //return compact($ListData, $jmlData);
	//$ListData = '';
    return array($ListData, $jmlData,$totBrg_akhir,$totHrg_akhir);
}


?>