<?php

/*
2010.09.17:
 	- menu nav atas hanya untuk penatausahaan (pg=05)

*/
$Main->NavAtas = '';
$jns = $_REQUEST['jns'];

if ($Main->VERSI_NAME=='JABAR'){ 
	
	
	
	if ($Pg=='05'){
		$notnavatas = $_REQUEST['notnavatas'];
		$tipebi = $_REQUEST['tipebi'];
		if (empty($notnavatas)){
			
			
			//$SPg = $_REQUEST['$SPg']; ECHO 	$SPg;
			
			
			
			switch($SPg){
				case '03' : $styleMenu0 = " style='color:blue;' "; break;
				case '04' : $styleMenu1 = " style='color:blue;' "; break;
				case '05' : $styleMenu2 = " style='color:blue;' "; break;
				case '06' : $styleMenu3 = " style='color:blue;' "; break;
				case '07' : $styleMenu4 = " style='color:blue;' "; break;
				case '08' : $styleMenu5 = " style='color:blue;' "; break;
				case '09' : $styleMenu6 = " style='color:blue;' "; break;
				case '11' : $styleMenu7 = " style='color:blue;' "; break;
				case '12' : $styleMenu9 = " style='color:blue;' "; break;
				case '13' : $styleMenu10 = " style='color:blue;' "; break;
				case 'KIR' : $styleMenu12 = " style='color:blue;' "; break;
				case 'KIP' : $styleMenu13 = " style='color:blue;' "; break;
			}
			
			
			if($Pg=='05' && ($SPg=='belumsensus'  || $SPg=='KIR' || $SPg=='KIP' )) $styleMenu11 =" style='color:blue;' ";
			if($Pg=='Pembukuan'  ) $styleMenu14 =" style='color:blue;' ";
					
					
					
			if($Main->MODUL_AKUNTANSI){					
			
			switch($jns){
				case 'intra' : $styleMenuA0 = " style='color:blue;' "; break;
				case 'extra' : $styleMenuA1 = " style='color:blue;' "; break;
				
				case 'tetap' : 							
					$f= $_REQUEST['f'];
					switch($SPg){
						case '04' : $styleMenuA3 = " style='color:blue;' "; break;
						case '05' : $styleMenuA4 = " style='color:blue;' "; break;
						case '06' : $styleMenuA5 = " style='color:blue;' "; break;
						case '07' : $styleMenuA6 = " style='color:blue;' "; break;
						case '08' : $styleMenuA7 = " style='color:blue;' "; break;
						case '09' : $styleMenuA8 = " style='color:blue;' "; break;
						default:{
							$styleMenuA2 = " style='color:blue;' "; 
							break;
						}
					}
				break;
				case 'pindah' : $styleMenu3_13 = " style='color:blue;' "; break;
				case 'tgr' : $styleMenu3_14 = " style='color:blue;' "; break;
				case 'mitra' : $styleMenu3_15 = " style='color:blue;' "; break;
				
				case 'lain' : $styleMenuA9 = " style='color:blue;' "; break;
				case 'rekapaset' : $styleMenuA10 = " style='color:blue;' "; break;
				case 'penyusutan' : $styleMenuA11 = " style='color:blue;' "; break;
				//case 'tetap' : $styleMenuA12 = " style='color:blue;' "; break;
				
			}
			if($jns <> ''){
				$styleMenu0='';$styleMenu1='';$styleMenu2='';$styleMenu3='';$styleMenu4=''; $styleMenu5='';
				$styleMenu6='';$styleMenu7='';$styleMenu8='';$styleMenu9='';$styleMenu10=''; 
				$styleMenu11='';$styleMenu12='';$styleMenu13='';$styleMenu14='';$styleMenu15=''; 
				$styleMenu16='';
				$styleMenu14=" style='color:blue;' ";
				
				
				
				
				$menu_kibg1 = $Main->MODUL_ASET_LAINNYA?
					"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
				$menu_rekapneraca_2 = //$Main->REKAP_NERACA_2 ?
					" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >REKAP NERACA</a>";//: '';
				$menu_akuntansi =
					"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
					<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>						
						<a href=\"index.php?Pg=05&jns=intra\" title=\"BI Intracomptable\" $styleMenuA0>INTRAKOMPTABEL</a> |
						<a href=\"index.php?Pg=05&jns=extra\" title=\"BI Extracomptable\" $styleMenuA1>EKSTRAKOMPTABEL</a>  |  
						<a href=\"index.php?Pg=05&SPg=04&jns=tetap\" title=\"Aset Tetap Tanah\" $styleMenuA3>KIB A</a>  |  
						<a href=\"index.php?Pg=05&SPg=05&jns=tetap\" title=\"Aset Tetap Peralatan &amp; Mesin\" $styleMenuA4>KIB B</a>  |  
						<a href=\"index.php?Pg=05&SPg=06&jns=tetap\" title=\"Aset Tetap Gedung &amp; Bangunan\" $styleMenuA5>KIB C</a>  |  
						<a href=\"index.php?Pg=05&SPg=07&jns=tetap\" title=\"Aset Tetap Jalan, Irigasi &amp; Jaringan\" $styleMenuA6>KIB D</a>  |  
						<a href=\"index.php?Pg=05&SPg=08&jns=tetap\" title=\"Aset Tetap Lainnya\" $styleMenuA7>KIB E</a>  |  
						<a href=\"index.php?Pg=05&SPg=09&jns=tetap\" title=\"Aset Tetap Konstruksi Dalam Pengerjaan\" $styleMenuA8>KIB F</a>  |   
						<A href=\"?Pg=$Pg&SPg=03&jns=pindah\" $styleMenu3_13 title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
						<A href=\"?Pg=$Pg&SPg=03&jns=tgr\" $styleMenu3_14 title='Tuntutan Ganti Rugi'>TGR</a> |    
						<A href=\"?Pg=$Pg&SPg=03&jns=mitra\" $styleMenu3_15 title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
						$menu_kibg1
						<A href=\"?Pg=$Pg&SPg=03&jns=lain\" $styleMenuA9 title='Aset Lain-lain'>ASET LAIN LAIN</a> |  

						<a href=\"index.php?Pg=05&jns=penyusutan\" title=\"Penyusutan\" $styleMenuA11>PENYUSUTAN</a>  ".
						//"| <A href=\"pages.php?Pg=Pembukuan\" title='Rekap Neraca' >REKAP NERACA</a> ".
						//"| <A href=\"index.php?Pg=05&jns=tetap\" title='Rincian Neraca' $styleMenuA2>RINCIAN NERACA</a> ".
						"| <A href=\"pages.php?Pg=Rekap1\" title='Rekap Aset' >REKAP ASET</a>  
						$menu_rekapneraca_2
						| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi' >REKAP MUTASI II</a>
						| <A href=\"pages.php?Pg=Jurnal\" title='Rekap Mutasi' >JURNAL</a>	".
						//<a href=\"pages.php?Pg=Pembukuan\" title=\"Rekap Neraca\"  >REKAP NERACA</a>  |  ".
						//<a href=\"index.php?Pg=05&jns=tetap\" title=\"Rincian Neraca\" $styleMenuA2>RINCIAN NERACA</a>  |  ".
							
					//	<a href=\"index.php?Pg=05&SPg=03&jns=lain\" title=\"Aset Lainnya\" $styleMenuA9>ASET LAINNYA</a> | 
					//	<a href=\"pages.php?Pg=RekapAset\" title=\"Rekap Aset Tetap\" $styleMenuA10>REKAP ASET</a>
							
					"	&nbsp;&nbsp;&nbsp;
					</td></tr></table>";
			}
			
			$Pembukuan = //"<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu14>AKUNTANSI</a> |";					
				"<A href=\"index.php?Pg=05&jns=intra\" title='Pembukuan' $styleMenu14>AKUNTANSI</a> |";
		}																																																																																	else{
				
			
				//$Pembukuan = "<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu14>PEMBUKUAN</a> |";
				$Pembukuan = //"<A href=\"pages.php?Pg=Pembukuan\" title='Pembukuan' $styleMenu14>AKUNTANSI</a> |";					
					"<A href=\"index.php?Pg=05&jns=intra\" title='Pembukuan' $styleMenu14>AKUNTANSI</a> |";
			}
			
			if($jns=='penyusutan'){
				$styleMenu = " style='color:blue;' ";
				$menubar3 = 
					"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">".
					"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
					"<A href=\"index.php?Pg=05&jns=penyusutan\"  title='Intrakomptabel' $styleMenu >PENYUSUTAN</a> ".
					"| <A href=\"pages.php?Pg=RekapPenyusutan\"  title='Intrakomptabel'  >REKAP PENYUSUTAN</a>   ".
					"&nbsp&nbsp&nbsp".
					"</td></tr></table>";
			}
			 
			
			$Main->NavAtas = 
				"<!--menubar_page-->
				<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			
				<A href=\"index.php?Pg=$Pg&SPg=03\" title='Buku Inventaris' $styleMenu0>BI</a> |
				<A href=\"index.php?Pg=$Pg&SPg=04\" title='Tanah' $styleMenu1>KIB A</a>  |  
				<A href=\"index.php?Pg=$Pg&SPg=05\" title='Peralatan & Mesin' $styleMenu2>KIB B</a>  |  
				<A href=\"index.php?Pg=$Pg&SPg=06\" title='Gedung & Bangunan' $styleMenu3>KIB C</a>  |  
				<A href=\"index.php?Pg=$Pg&SPg=07\" title='Jalan, Irigasi & Jaringan' $styleMenu4>KIB D</a>  |  
				<A href=\"index.php?Pg=$Pg&SPg=08\" title='Aset Tetap Lainnya' $styleMenu5>KIB E</a>  |  
				<A href=\"index.php?Pg=$Pg&SPg=09\" title='Konstruksi Dalam Pengerjaan' $styleMenu6>KIB F</a>  |  
				<!--
				<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu12>KIR</a>  |  
				<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu13>KIP</a>  |  
				-->
				
				<!--<A href=\"?Pg=$Pg&SPg=03&fmKONDBRG=3\" title='Aset Lainnya'>ASET LAINNYA</a>  |  -->
				<!--<A href=\"javascript:showAsetLain()\" title='Aset Lainnya'>ASET LAINNYA</a>  |-->
				<A href=\"index.php?Pg=$Pg&SPg=11\" title='Rekap BI' $styleMenu7>REKAP BI</a> |
				<A target='blank' href=\"pages.php?Pg=map&SPg=03\" title='Peta Sebaran' $styleMenu8>PETA</a> |
				<A href=\"index.php?Pg=$Pg&SPg=12\" title='Daftar Rekap Mutasi' $styleMenu9>MUTASI</a>  |
				<A href=\"index.php?Pg=$Pg&SPg=13\" title='Rekap Mutasi' $styleMenu10>REKAP MUTASI</a> |
				<!--<A href=\"pages.php?Pg=sensus\" title='Sensus' $styleMenu11>SENSUS</a>-->
				<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Sensus' $styleMenu11>INVENTARISASI</a> |
				$Pembukuan
				<A href=\"pages.php?Pg=penatausahakol\" title='Gambar' >GAMBAR</a> 	
				  &nbsp&nbsp&nbsp
				</td></tr></table>".
				$menu_akuntansi.
				$menubar3.
				"";
				//	break;
				//}
			//}
			//*
			if ($SPg == 'belumsensus' || $SPg=='KIR' || $SPg=='KIP' ) {
				switch ($SPg){
					case 'belumsensus': $styleMenu2_1 = " style='color:blue;'"; break;
					case 'KIR': $styleMenu2_6 = " style='color:blue;'"; break;
					case 'KIP': $styleMenu2_7 = " style='color:blue;'"; break;
					case 'SensusScan': $styleMenu2_9 = " style='color:blue;'"; break;
					//case 'SensusTidakTercatat' : $styleMenu2_8 = " style='color:blue;'";
				}
				
				if($Pg=='SensusTidakTercatat') $styleMenu2_8 = " style='color:blue;'";
				
				$Main->NavAtas = 
				$Main->NavAtas.
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin: 1 0 0 0'>
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				
				
				<!--<A href=\"pages.php?Pg=sensus&menu=kertaskerja\" title='Kertas Kerja' $styleMenu2_5>Kertas Kerja</a> |  -->
				<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Belum Cek' $styleMenu2_1>Belum Cek</a> |
				<A href=\"pages.php?Pg=SensusScan\" title='Hasil Scan' $styleMenu2_9>Hasil Scan</a> |
				<A href=\"pages.php?Pg=SensusTidakTercatat\" title='Barang Tidak Tercatat' $styleMenu2_8>Tidak Tercatat</a> |
				
				
				
				
				<!--<A href=\"pages.php?Pg=sensus\" title='Sudah Cek' $styleMenu2_2>Sudah Cek</a>  |  -->
				<A href=\"pages.php?Pg=sensus&menu=ada\" title='Ada Barang' $styleMenu2_2>Ada</a>  |  
				<A href=\"pages.php?Pg=sensus&menu=tidakada\" title='Tidak Ada Barang' $styleMenu2_5>Tidak Ada</a>  |  
				
				<!--<A href=\"pages.php?Pg=sensus&menu=diusulkan\" title='Diusulkan Penghapusan' $styleMenu2_3>Diusulkan</a>  |  -->
				<A href=\"pages.php?Pg=SensusHasil\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>  |
				<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   |
				
				<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
				<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
						
				
				  &nbsp&nbsp&nbsp
				</td></tr></table>";
			}//*/
			
		}
	}else if ($Pg=='06'){
	/*$Main->NavAtas = "
	<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>

	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=03\" title='Buku Inventaris'>BI</a> |
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=04\" title='Tanah'>KIB A</a>  |  
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
	<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  &nbsp&nbsp&nbsp

	</td></tr></table>
	";*/
}															else if (  $Pg=='12' ){
		
		$SSPg = $_REQUEST['SSPg'];
		
		$menustyle03 =''; 
		$menustyle04 =''; 
		$menustyle05 =''; 
		$menustyle06 =''; 
		$menustyle07 =''; 
		$menustyle08 =''; 
		$menustyle09 =''; 
		
		switch($SSPg){
			case '04' : $menustyle04 = "style='color:blue'"; break;
			case '05' : $menustyle05 = "style='color:blue'"; break;
			case '06' : $menustyle06 = "style='color:blue'"; break;
			case '07' : $menustyle07 = "style='color:blue'"; break;
			case '08' : $menustyle08 = "style='color:blue'"; break;
			case '09' : $menustyle09 = "style='color:blue'"; break;		
			default: $menustyle03 ="style='color:blue'"; break;
		}
		
		$menupenghapusan = $Pg !='09'?  '' :
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=usulanhapus\" title='Penghapusan Barang Pengguna'>PENGHAPUSAN BARANG PENGGUNA</a> |
			<A href=\"pages.php?Pg=usulanhapusba\" title='Penghapusan Barang Milik Daerah'>PENGHAPUSAN BMD</a>
			&nbsp&nbsp&nbsp	
			</td></tr>".
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan'>USULAN </a> |
			<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan'>PENGECEKAN</a>  |  
			<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK Gubernur'>USULAN SK</a>  |
			<A href=\"index.php?Pg=09&SPg=01\" title='Daftar Penghapusan' style='color:blue'>PENGHAPUSAN </a>  
			
			&nbsp&nbsp&nbsp	
			</td></tr>";
			
		
				
			
			
			
		$Main->NavAtas = "
		
			<!--menubar_page-->
			<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			$menupenghapusan
			
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		
		
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=03\" title='Buku Inventaris' $menustyle03>BI</a> |
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=04\" title='Tanah' $menustyle04>KIB A</a>  |  
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=05\" title='Peralatan & Mesin' $menustyle05>KIB B</a>  |  
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=06\" title='Gedung & Bangunan' $menustyle06>KIB C</a>  |  
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=07\" title='Jalan, Irigasi & Jaringan' $menustyle07>KIB D</a>  |  
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=08\" title='Aset Tetap Lainnya' $menustyle08>KIB E</a>  |  
			<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=09\" title='Konstruksi Dalam Pengerjaan' $menustyle09>KIB F</a>  &nbsp&nbsp&nbsp
			<!--<A href=\"?Pg=$Pg&SPg=$SPg\" title='Rekap BI'>REKAP BI</a> &nbsp&nbsp&nbsp-->
			</td></tr></table>
			";
	}else if ($Pg=='09' && $SPg != '03'){
		$stylePemusnahan = '';
		$styleHapusSebagian = '';
		$menubar2 = 
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=1\" title='Usulan Pemusnahan' >Usulan</a> |
			<A href=\"pages.php?Pg=PemusnahanBa\" title='Berita Acara Pemusnahan' >Berita Acara</a>  |  
			<A href=\"index.php?Pg=09\" title='Pemusnahan'  style='color:blue;' >Pemusnahan</a>  		
			&nbsp&nbsp&nbsp	
			</td></tr>";
		switch($SPg){
			case '06':  $styleHapusSebagian = "style='color:blue;'"; $menubar2=''; break;
			default: $stylePemusnahan = "style='color:blue;'";
		}
	
		$menuhapussebagian = $Main->MODUL_PENGAPUSAN_SEBAGIAN ? "  | <A href=\"index.php?Pg=09&SPg=06\" title='Daftar Penghapusan Sebagian' $styleHapusSebagian >PENGHAPUSAN SEBAGIAN</a>":"";	
		
		$coGroup = $HTTP_COOKIE_VARS['coGroup'];
		$coLevel = $HTTP_COOKIE_VARS['coLevel'];
		if(  $coGroup==$Main->Pembantu_Pengelola[0].'.'.$Main->Pembantu_Pengelola[2].'.'.$Main->Pembantu_Pengelola[2] || 
				$coLevel==1)
		{
			$menupemusnahan = " | <A href=\"pages.php?Pg=PenghapusanBmd&tl=1\" title='Pemusnahan' $stylePemusnahan >PEMUSNAHAN</a>  ";	
			$Main->NavAtas =  
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>".
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
					<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan' >USULAN </a> |
					<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan' >PENGECEKAN</a>  |  
					<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK Sekretariat Daerah' >USULAN SK SETDA</a>  | 
					<A href=\"pages.php?Pg=PenghapusanPengguna2\" title='Daftar Penghapusan Pengguna' >PENGHAPUSAN PENGGUNA</a>  
					| <A href=\"pages.php?Pg=PenghapusanPengguna\" title='Daftar Penghapusan' >PENGHAPUSAN </a>   
					$menupemusnahan
					$menuhapussebagian
					&nbsp&nbsp&nbsp	
					</td></tr>".
				 $menubar2.
				"</table>";
		}else{
			//
			$menupemusnahan = "|  <A href=\"index.php?Pg=09\" title='Pemusnahan' $stylePemusnahan >PEMUSNAHAN</a>  ";	
			$Main->NavAtas =  
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>".
				"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
					<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan' >USULAN </a> | ".
					//"<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan' >PENGECEKAN</a>  |  ".
					//"<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK Sekretariat Daerah' >USULAN SK SETDA</a>  |".
					" <A href=\"pages.php?Pg=PenghapusanPengguna2\" title='Daftar Penghapusan Pengguna' >PENGHAPUSAN PENGGUNA</a>   ".
					//"<A href=\"pages.php?Pg=PenghapusanPengguna\" title='Daftar Penghapusan' >PENGHAPUSAN </a>  ".
					$menupemusnahan.
					$menuhapussebagian.
					"&nbsp&nbsp&nbsp	
					</td></tr>".
				 //$menubar2.
				"</table>";
		}
		
	
	} else if ($Pg=='10') {
		$style1='';$style2='';$style3='';$style4='';
		$bentuk= $_REQUEST['bentuk'];
		switch($bentuk){
			case '1' : $style1 = "style='color:blue;'"; break; 
			case '2' : $style2 = "style='color:blue;'"; break; 
			case '3' : $style3 = "style='color:blue;'"; break; 
			case '4' : $style4 = "style='color:blue;'"; break; 
		}
		
		$menubawah = "<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Usulan' >Usulan</a> |
			<A href=\"pages.php?Pg=PindahTanganNilai\" title='Penilaian Pemindahtanganan'  >Penilaian</a>  |
			<A href=\"pages.php?Pg=PindahTanganSK\" title='Surat Keputusan Pemindahtanganan'  >Surat Keputusan</a>  |
			<A href=\"index.php?Pg=10&bentuk=1\" title='Penjualan' $style1>Penjualan</a>  |  
			<A href=\"index.php?Pg=10&bentuk=2\" title='Tukar Menukar' $style2>Tukar Menukar</a>  |  
			<A href=\"index.php?Pg=10&bentuk=3\" title='Hibah' $style3>Hibah</a>  |  
			<A href=\"index.php?Pg=10&bentuk=4\" title='Penyertaan Modal' $style4>Penyertaan Modal</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>";
						
		$Main->NavAtas =  
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>".
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<!--<A href=\"pages.php?Pg=PenghapusanPengguna&PT=1\" title='Penghapusan' >PENGHAPUSAN</a> |-->
			<A href=\"pages.php?Pg=MutasiUsulan\" title='Mutasi Pindah Antar SKPD'  >MUTASI</a>  |  
			<!--<A href=\"pages.php?Pg=PenghapusanBmd&tl=1\" title='Pemusnahan' >PEMUSNAHAN</a>  |-->
			<A href=\"pages.php?Pg=PenghapusanBmd&tl=2\" title='Pemindah Tanganan' style='color:blue;'>PEMINDAH TANGANAN</a>  
			&nbsp&nbsp&nbsp	
			</td></tr>".
			$menubawah.
			"</table>";
		
	
	}else {
		$Main->NavAtas = '';
	}
}else{ //selain jabar --------------------------------------------
	
		
	if ($Pg=='05'){
	/*
		$menu_peta = 
			$Main->MODUL_PETA ?
			 "|<A href=\"pages.php?Pg=map&SPg=03\" title='Peta' target='_blank'>PETA</a>" : '';
	*/		 
	
		
		//menu bar level 3
		if($jns=='penyusutan'){
				//$styleMenu = "  ";
				$menubar3 = 
					"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">".
					"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>".
					"<A href=\"index.php?Pg=05&jns=penyusutan\"  title='Intrakomptabel' style='color:blue;' >PENYUSUTAN</a> ".
					"| <A href=\"pages.php?Pg=RekapPenyusutan\"  title='Intrakomptabel'  >REKAP PENYUSUTAN</a>   ".
					"&nbsp&nbsp&nbsp".
					"</td></tr></table>";
		}
	
		if 	($Main->MODUL_AKUNTANSI && $jns<>'') {
	
		switch($SPg.$jns){
			case	'03intra': $styleMenu3_1 = " style='color:blue;'"; break;
			case	'03ekstra': $styleMenu3_2 = " style='color:blue;'"; break;
			case	'03lain': $styleMenu3_10 = " style='color:blue;'"; break;
			case	'03pindah': $styleMenu3_13 = " style='color:blue;'"; break;
			case	'03tgr': $styleMenu3_14 = " style='color:blue;'"; break;
			case	'03mitra': $styleMenu3_15 = " style='color:blue;'"; break;
		}
		switch($SPg){
		case		'03': $styleMenu3_1a = " style='color:blue;'"; break;
		case		'04': $styleMenu3_3 = " style='color:blue;'"; break;
		case		'05': $styleMenu3_4 = " style='color:blue;'"; break;
		case		'06': $styleMenu3_5 = " style='color:blue;'"; break;
		case		'07': $styleMenu3_6 = " style='color:blue;'"; break;
		case		'08': $styleMenu3_7 = " style='color:blue;'"; break;
		case		'09': $styleMenu3_8 = " style='color:blue;'"; break;
		case		'kibg': $styleMenu3_9 = " style='color:blue;'"; break;
		case		'11a': $styleMenu3_11 = " style='color:blue;'"; $styleMenu3_1a = " style='color:blue;'"; break;
		case		'11b': $styleMenu3_11a = " style='color:blue;'"; $styleMenu3_1a = " style='color:blue;'"; break;
		}
		
		if ($jns=='penyusutan') $styleMenuPenyusutan = " style='color:blue;'";
		$menu_penyusutan = $Main->PENYUSUTAN ? " <A href=\"index.php?Pg=05&jns=penyusutan\" $styleMenuPenyusutan title='Penyusutan'>PENYUSUTAN</a> |   ":'';
		
		$menu_kibg1 =
			$Main->MODUL_ASET_LAINNYA?
			"<A href=\"?Pg=$Pg&SPg=kibg&jns=atb\" $styleMenu3_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> |":'';
		
		$menu_rekapneraca_2 = $Main->REKAP_NERACA_2 ?
			" | <A href=\"pages.php?Pg=Rekap2\" title='Rekap Neraca' $styleMenu3_11c >KERTAS KERJA</a>": '';
		
		$menu_pembukuan1 =
			($Main->MODUL_AKUNTANSI && $jns<>'')?
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		<A href=\"?Pg=$Pg&SPg=03&jns=intra\" $styleMenu3_1 title='Intrakomptabel'>INTRAKOMPTABEL</a> |
		<A href=\"?Pg=$Pg&SPg=03&jns=ekstra\" $styleMenu3_2 title='Ekstrakomptabel'>EKSTRAKOMPTABEL</a> |
		<A href=\"?Pg=$Pg&SPg=04&jns=tetap\" $styleMenu3_3 title='Aset Tetap Tanah'>Tanah</a>  |  
		<A href=\"?Pg=$Pg&SPg=05&jns=tetap\" $styleMenu3_4 title='Aset Tetap Peralatan & Mesin'>P & M</a>  |  
		<A href=\"?Pg=$Pg&SPg=06&jns=tetap\" $styleMenu3_5 title='Aset Tetap Gedung & Bangunan'>G & B</a>  |  
		<A href=\"?Pg=$Pg&SPg=07&jns=tetap\" $styleMenu3_6 title='Aset Tetap Jalan, Irigasi & Jaringan'>JIJ</a>  |  
		<A href=\"?Pg=$Pg&SPg=08&jns=tetap\" $styleMenu3_7 title='Aset Tetap Lainnya'>ATL</a>  |  
		<A href=\"?Pg=$Pg&SPg=09&jns=tetap\" $styleMenu3_8 title='Aset Tetap Konstruksi Dalam Pengerjaan'>KDP</a> |    
		<A href=\"?Pg=$Pg&SPg=03&jns=pindah\" $styleMenu3_13 title='Pemindahtanganan'>PEMINDAHTANGANAN</a> |    
		<A href=\"?Pg=$Pg&SPg=03&jns=tgr\" $styleMenu3_14 title='Tuntutan Ganti Rugi'>TGR</a> |    
		<A href=\"?Pg=$Pg&SPg=03&jns=mitra\" $styleMenu3_15 title='Kemitraan Dengan Pihak Ke Tiga'>KEMITRAAN</a> |    
		$menu_kibg1
		<A href=\"?Pg=$Pg&SPg=03&jns=lain\" $styleMenu3_10 title='Aset Lain-lain'>ASET LAIN LAIN</a> |  
		$menu_penyusutan
		<A href=\"pages.php?Pg=Rekap1\" title='Rekap BI' $styleMenu3_11b >REKAP NERACA</a>   
		<!--<A href=\"pages.php?Pg=Rekap5\" title='Rekap BI' $styleMenu3_11c >REKAP BI 2</a>   -->
		$menu_rekapneraca_2
		| <A href=\"pages.php?Pg=Rekap3\" title='Rekap Mutasi' >REKAP MUTASI</a>
		| <A href=\"pages.php?Pg=Jurnal\" title='Jurnal' $styleMenu>JURNAL</a> 
		  &nbsp&nbsp&nbsp
		</td></tr>":'';	
		
		
		
		} else {
		
			switch($SPg){
				case		'03': $styleMenu1_1 = " style='color:blue;'"; break;
				case		'04': $styleMenu1_3 = " style='color:blue;'"; break;
				case		'05': $styleMenu1_4 = " style='color:blue;'"; break;
				case		'06': $styleMenu1_5 = " style='color:blue;'"; break;
				case		'07': $styleMenu1_6 = " style='color:blue;'"; break;
				case		'08': $styleMenu1_7 = " style='color:blue;'"; break;
				case		'09': $styleMenu1_8 = " style='color:blue;'"; break;
				case		'kibg': $styleMenu1_9 = " style='color:blue;'"; break;
				case		'11': $styleMenu1_11 = " style='color:blue;'"; break;
				case		'12': $styleMenu1_12 = " style='color:blue;'"; break;
				case		'13': $styleMenu1_13 = " style='color:blue;'"; break;
				case		'KIR': $styleMenu1_14 = " style='color:blue;'"; break;
				case		'sensus': $styleMenu1_15 = " style='color:blue;'"; break;
				case		'belumsensus': $styleMenu1_15 = " style='color:blue;'"; break;
			}
			
	
	
		}
		
	
	    
		$menu_sensus = 
			$Main->MODUL_SENSUS ?
			 "| <A href=\"?Pg=$Pg&SPg=belumsensus\" $styleMenu1_15 title='SENSUS'>SENSUS</a> " : '';
		$menu_mutasi =
			$Main->MODUL_MUTASI?
			"| <A href=\"?Pg=$Pg&SPg=12\" $styleMenu1_12 title='Daftar Mutasi'>MUTASI</a> ": '';
		$menu_rekap_mutasi = 
			$Main->MODUL_MUTASI?
			"| <A href=\"?Pg=$Pg&SPg=13\" $styleMenu1_13 title='Rekap Mutasi'>REKAP MUTASI</a> ": ''; 	
		$menu_kibg = '';
			//$Main->MODUL_ASET_LAINNYA?
			//"|<A href=\"?Pg=$Pg&SPg=kibg\" $styleMenu1_9 title='Aset Tak Berwujud'>ASET TAK BERWUJUD</a> ":'';
		$menu_pembukuan =
			$Main->MODUL_AKUNTANSI?
			"| <A href=\"?Pg=05&SPg=03&jns=intra\" $styleMenu3_1a title='AKUNTANSI'>AKUNTANSI</a>":'';
		
		
		
		$Main->NavAtas = "
	
		<!--menubar_page-->
	
		<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	
	
		<A href=\"?Pg=$Pg&SPg=03\" $styleMenu1_1 title='Buku Inventaris'>BI</a> |
		<A href=\"?Pg=$Pg&SPg=04\" $styleMenu1_3 title='Tanah'>KIB A</a>  |  
		<A href=\"?Pg=$Pg&SPg=05\" $styleMenu1_4 title='Peralatan & Mesin'>KIB B</a>  |  
		<A href=\"?Pg=$Pg&SPg=06\" $styleMenu1_5 title='Gedung & Bangunan'>KIB C</a>  |  
		<A href=\"?Pg=$Pg&SPg=07\" $styleMenu1_6 title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
		<A href=\"?Pg=$Pg&SPg=08\" $styleMenu1_7 title='Aset Tetap Lainnya'>KIB E</a>  |  
		<A href=\"?Pg=$Pg&SPg=09\" $styleMenu1_8 title='Konstruksi Dalam Pengerjaan'>KIB F</a>   
		$menu_kibg		
		| <A href=\"?Pg=$Pg&SPg=11\" $styleMenu1_11 title='Rekap BI'>REKAP BI</a>
	
		$menu_mutasi
		$menu_rekap_mutasi
		| <A href=\"?Pg=$Pg&SPg=KIR\" $styleMenu1_14 title='Kartu Inventaris Ruangan'>KIR</a> 
	
		$menu_sensus
		$menu_pembukuan
		$menu_peta
		  &nbsp&nbsp&nbsp
		</td></tr>$menu_pembukuan1</table>$menubar3
		";
		
	if ($SPg == 'belumsensus' || $SPg=='KIR' || $SPg=='KIP' ) {
				switch ($SPg){
					case 'belumsensus': $styleMenu2_1 = " style='color:blue;'"; break;
					case 'KIR': $styleMenu2_6 = " style='color:blue;'"; break;
					case 'KIP': $styleMenu2_7 = " style='color:blue;'"; break;
					case 'SensusScan': $styleMenu2_9 = " style='color:blue;'"; break;
					//case 'SensusTidakTercatat' : $styleMenu2_8 = " style='color:blue;'";
				}
				
				if($Pg=='SensusTidakTercatat') $styleMenu2_8 = " style='color:blue;'";
				
				$Main->NavAtas = 
				$Main->NavAtas.
				"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin: 1 0 0 0'>
				<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
				
				
				<!--<A href=\"pages.php?Pg=sensus&menu=kertaskerja\" title='Kertas Kerja' $styleMenu2_5>Kertas Kerja</a> |  -->
				<A href=\"index.php?Pg=05&SPg=belumsensus\" title='Belum Cek' $styleMenu2_1>Belum Cek</a> | 
				<A href=\"pages.php?Pg=SensusScan\" title='Hasil Scan' $styleMenu2_9>Hasil Scan</a> | 
				<A href=\"pages.php?Pg=SensusTidakTercatat\" title='Barang Tidak Tercatat' $styleMenu2_8>Tidak Tercatat</a> | 
				
				
				
				
				<!--<A href=\"pages.php?Pg=sensus\" title='Sudah Cek' $styleMenu2_2>Sudah Cek</a>  |  -->
				<A href=\"pages.php?Pg=sensus&menu=ada\" title='Ada Barang' $styleMenu2_2>Ada</a>  |  
				<A href=\"pages.php?Pg=sensus&menu=tidakada\" title='Tidak Ada Barang' $styleMenu2_5>Tidak Ada</a>  |  
				
				<!--<A href=\"pages.php?Pg=sensus&menu=diusulkan\" title='Diusulkan Penghapusan' $styleMenu2_3>Diusulkan</a>  |  -->
				<A href=\"pages.php?Pg=SensusHasil2\" title='Rekapitulasi Hasil Sensus' $styleMenu2_4>Hasil Sensus</a>  | 
				<A href=\"pages.php?Pg=SensusProgres\" title='Sensus Progress' $styleMenu2_4_>Sensus Progress</a>   | 
				
				<A href=\"index.php?Pg=05&SPg=KIR\" title='Kartu Inventaris Ruang' $styleMenu2_6>KIR</a>  |  
				<A href=\"index.php?Pg=05&SPg=KIP\" title='Kartu Inventaris Pegawai' $styleMenu2_7>KIP</a>  
						
				
				  &nbsp&nbsp&nbsp
				</td></tr>	</table>";
			}	
		
		
	}else if ($Pg=='06'){
		/*$Main->NavAtas = "
		<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=03\" title='Buku Inventaris'>BI</a> |
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=04\" title='Tanah'>KIB A</a>  |  
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=05\" title='Peralatan & Mesin'>KIB B</a>  |  
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=06\" title='Gedung & Bangunan'>KIB C</a>  |  
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=07\" title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=08\" title='Aset Tetap Lainnya'>KIB E</a>  |  
		<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=09\" title='Konstruksi Dalam Pengerjaan'>KIB F</a>  &nbsp&nbsp&nbsp
	
		</td></tr></table>
		";*/
	}else if ($Pg=='10'){
		$SSPg = $_REQUEST['SSPg'];
		$bentuk = $_REQUEST['bentuk'];
		switch($bentuk){
				case		'1': $stylebentuk1 = " style='color:blue;'"; break;
				case		'2': $stylebentuk2 = " style='color:blue;'"; break;
				case		'3': $stylebentuk3 = " style='color:blue;'"; break;
				case		'4': $stylebentuk4 = " style='color:blue;'"; break;
				default		: $styleMenu2 = " style='color:blue;'"; break;
				
			}
		switch($SSPg){
				case		'04': $styleMenu1_3 = " style='color:blue;'"; break;
				case		'05': $styleMenu1_4 = " style='color:blue;'"; break;
				case		'06': $styleMenu1_5 = " style='color:blue;'"; break;
				case		'07': $styleMenu1_6 = " style='color:blue;'"; break;
				case		'08': $styleMenu1_7 = " style='color:blue;'"; break;
				case		'09': $styleMenu1_8 = " style='color:blue;'"; break;
				case		'10': $styleMenu1_9 = " style='color:blue;'"; break;
				default		: $styleMenu1 = " style='color:blue;'"; break;
				
			}
		$Main->MODUL_PEMUSNAHAN==0?$spasi="&nbsp&nbsp&nbsp":"";
		if($Main->MODUL_PEMUSNAHAN==1){			
			$pemisah=" |";
			$menu_hps="<A href=\"pages.php?Pg=pemusnahanba\" title='PEMUSNAHAN'>PEMUSNAHAN</a>&nbsp&nbsp&nbsp";		
		}
		$Main->NavAtas = "
		<table width=\"100%\" n class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		<A style='color:blue;' href=\"?Pg=$Pg&bentuk=1&SSPg=\" title='PEMINDAHTANGANAN'>PEMINDAHTANGANAN</a>$spasi$pemisah
		$menu_hps				
		</td></tr>
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		<A href=\"?Pg=$Pg&bentuk=&SSPg=$SSPg\" $styleMenu2 title='Semua  '>Semua</a> |
		<A href=\"?Pg=$Pg&bentuk=1&SSPg=$SSPg\" $stylebentuk1 title='Penjualan  '>Penjualan</a> |
		<A href=\"?Pg=$Pg&bentuk=2&SSPg=$SSPg\" $stylebentuk2 title='Tukar Menukar '>Tukar Menukar</a> |
		<A href=\"?Pg=$Pg&bentuk=3&SSPg=$SSPg\" $stylebentuk3 title='Hibah'>Hibah</a>  | 		
		<A href=\"?Pg=$Pg&bentuk=4&SSPg=$SSPg\" $stylebentuk4 title='Penyertaan Modal'>Penyertaan Modal</a>&nbsp&nbsp&nbsp		
		</td></tr>
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=\" $styleMenu1 title='Buku Inventaris'>BI</a> |
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=04\" $styleMenu1_3 title='Tanah'>KIB A</a>  |  
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=05\" $styleMenu1_4 title='Peralatan & Mesin'>KIB B</a>  |  
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=06\" $styleMenu1_5 title='Gedung & Bangunan'>KIB C</a>  |  
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=07\" $styleMenu1_6 title='Jalan, Irigasi & Jaringan'>KIB D</a>  
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=08\" $styleMenu1_7 title='Aset Tetap Lainnya'>KIB E</a>  |  
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=09\" $styleMenu1_8 title='Konstruksi Dalam Pengerjaan'>KIB F</a>  |
		<A href=\"?Pg=$Pg&bentuk=$bentuk&SSPg=10\" $styleMenu1_9 title='ATB'>ATB</a>&nbsp&nbsp&nbsp
	
		</td></tr></table>
		";
	}else if (($Pg=='09' && $SPg != '03') || $Pg=='12' ){
		$SSPg = $_REQUEST['SSPg'];
		$mutasi = $_REQUEST['mutasi'];
		
		switch($SSPg){
			//case '03' : $menustyle04 = "style='color:blue'"; break;
			case '04' : $menustyle1 = "style='color:blue'"; break;
			case '05' : $menustyle2 = "style='color:blue'"; break;
			case '06' : $menustyle3 = "style='color:blue'"; break;
			case '07' : $menustyle4 = "style='color:blue'"; break;
			case '08' : $menustyle5 = "style='color:blue'"; break;		
			case '09' : $menustyle6 = "style='color:blue'"; break;		
			case '10' : $menustyle7 = "style='color:blue'"; break;		
			//default: $menustyle1a = $SPg == '01' ?  "style='color:blue'" : ''; break;
			default: $menustyle1a = "style='color:blue'" ; break;
		}
		
			if($mutasi=='1'){
				$mut = "&mutasi=1";
			}elseif($mutasi=='2'){
				$mut = "&mutasi=2";
			}elseif($mutasi=='3'){
				$mut = "&mutasi=3";
			}elseif($mutasi=='4'){
				$mut = "&mutasi=4";
			}elseif($mutasi=='5'){
				$mut = "&mutasi=5";
			}else{
				$mut = "";
			}
				$submenubarx="
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=03$mut\" $menustyle1a title='Buku Inventaris'>BI</a> |
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=04$mut\" $menustyle1 title='Tanah'>KIB A</a>  |  
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=05$mut\" $menustyle2 title='Peralatan & Mesin'>KIB B</a>  |  
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=06$mut\" $menustyle3 title='Gedung & Bangunan'>KIB C</a>  |  
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=07$mut\" $menustyle4 title='Jalan, Irigasi & Jaringan'>KIB D</a>  |  
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=08$mut\" $menustyle5 title='Aset Tetap Lainnya'>KIB E</a>  |  
				<A href=\"?Pg=$Pg&SPg=$SPg&SSPg=09$mut\" $menustyle6 title='Konstruksi Dalam Pengerjaan'>KIB F</a>
				 | <A href=\"?Pg=$Pg&SPg=$SPg&SSPg=10$mut\" $menustyle7 title='Aset Tak Berwujud'>ATB</a>
				  &nbsp&nbsp&nbsp
					
					";
			if ($Main->MODUL_PENGAPUSAN && $Pg =='09' ){
			
			switch($mutasi){
				//case '03' : $menustyle04 = "style='color:blue'"; break;
				case '1' : $mutstyle1 = "style='color:blue'"; break;
				case '2' : $mutstyle2 = "style='color:blue'"; break;
				case '3' : $mutstyle3 = "style='color:blue'"; break;
				case '4' : $mutstyle4 = "style='color:blue'"; break;
				case '5' : $mutstyle5 = "style='color:blue'"; break;
				
				default: $mutstyle1a = $SPg == '01' ? "style='color:blue'":''; break;
			}
			
			$mhapussebagian_style = $SPg == '06' ? "style='color:blue'" : '';
			
			$menupenghapusann = 
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"?Pg=$Pg&SPg=01&SSPg=03\" $mutstyle1a title='Daftar Penghapusan'>PENGHAPUSAN</a> |
			<A href=\"?Pg=$Pg&SPg=06&SSPg=03\" $mhapussebagian_style title='Daftar Mutasi'>PENGHAPUSAN SEBAGIAN</a>  |
			<A href=\"?Pg=$Pg&SPg=01&SSPg=03&mutasi=1\" $mutstyle1 title='Daftar Mutasi'>MUTASI</a>  |
			  
			<A href=\"?Pg=$Pg&SPg=01&SSPg=03&mutasi=2\" $mutstyle2 title='Daftar Reklas'>REKLAS</a> 
			 | <A href=\"?Pg=$Pg&SPg=01&SSPg=03&mutasi=4\" $mutstyle4 title='Daftar Penghapusan Karena Penggabungan'>PENGGABUNGAN</a> 
			 | <A href=\"?Pg=$Pg&SPg=01&SSPg=03&mutasi=5\" $mutstyle5 title='Daftar Penghapusan Karena Koreksi (double catat)'>KOREKSI</a> 
			
			&nbsp&nbsp&nbsp	
			</td></tr>";
			
			$menupenghapusan = 
			"<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>";
				
				
			if ($Main->MODUL_PENGAPUSAN_SK) {
				
			$menupenghapusan =$menupenghapusan.
			"<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan'>USULAN </a> |
			<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan'>PENGECEKAN</a>  |  
			<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK BUPATI'>USULAN SK</a>  |";
			}
	
			$menupenghapusan = $menupenghapusan.
			"<A href=\"index.php?Pg=09&SPg=01\" title='Daftar Penghapusan' >PENGHAPUSAN </a>  |
			";
	
			if ($Main->MODUL_PENGAPUSAN_SEBAGIAN) {
				
			$menupenghapusan = $menupenghapusan.
			"<A href=\"index.php?Pg=09&SPg=06\" title='Penghapusan Sebagian' >PENGHAPUSAN SEBAGIAN</a> 
			&nbsp&nbsp&nbsp	
			</td></tr>";
			$submenubarx;
			}		
			
			} else {
			$menupenghapusan = "";
			$menupenghapusann = "";
				
			}
	
	
		$Main->NavAtas = "
	
		<!--menubar_page-->
	
		<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"> <!--$menupenghapusan--> $menupenghapusann
		<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
		$submenubarx
	
		<!--<A href=\"?Pg=$Pg&SPg=$SPg\" title='Rekap BI'>REKAP BI</a> &nbsp&nbsp&nbsp-->
		</td></tr>
	
		</table>
		";
	}else {
		$Main->NavAtas = '';
	}
	
}

?>