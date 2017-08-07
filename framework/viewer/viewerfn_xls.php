<?php
function number_format_ribuan_xls($val=0, $dlmRibuan=FALSE){
	return $dlmRibuan == TRUE ? number_format(($val / 1000), 2, '.', '') : number_format($val, 2, '.', '');
        
}

function RekapByOPD_TampilJmldanHarga_xls($isi, $isRibuan, $cltd) {
	//ff.jmlHrgHPS_PLH, gg.jmlHrgHPS_AMAN, hh.jmlHrgPD_PLH, ii.jmlHrgPD_AMAN, kk.jmlHrgGR_AMAN, 
	//ll.jmlBrgPD, ll.jmlHrgPD 			
	
    $jmlBrg = $isi['jmlBrgBI'] - $isi['jmlBrgHPS']- $isi['jmlBrgPD']; 
    $jmlHrg = ($isi['jmlHrgPLH'] + $isi['jmlHrgAman'] + $isi['jmlHrgBI']) 
		- $isi['jmlHrgHPS'] - $isi['jmlHrgPD']  
		- $isi['jmlHrgPD_PLH'] - $isi['jmlHrgPD_AMAN'] - $isi['jmlHrgGR_AMAN']
		;
    $tampilJmlHarga = RekapByOPD_TampilJmldanHarga_xls_($isi['e1'], $isRibuan, $cltd, $jmlBrg, $jmlHrg);
    /* $sjmlBrg = number_format($jmlBrg, 0, '.', '') ;
      $sjmlBrg = $isi['e']=='00' ? "<b>$sjmlBrg</b>" : $sjmlBrg;
      $sjmlHrg = $isRibuan?
      number_format($jmlHrg/1000, 2, '.', '') :
      number_format($jmlHrg, 2, '.', '') ;
      $sjmlHrg = $isi['e']=='00' ? "<b>$sjmlHrg</b>" : $sjmlHrg;
      $tampilJmlHarga = "
      <td class='$cltd' align='right' >$sjmlBrg</td>
      <td class='$cltd' align='right' >$sjmlHrg</td>"; */
    return array($jmlBrg, $jmlHrg, $tampilJmlHarga);
}
function RekapByOPD_TampilJmldanHarga_xls_($e1, $isRibuan, $cltd, $jmlBrg, $jmlHrg) {
global $Main;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    $sjmlBrg = number_format($jmlBrg, 0, '.', '');
    $sjmlBrg = $e1 == $kdSubUnit0 ? "<b>$sjmlBrg</b>" : $sjmlBrg;
    $sjmlHrg = $isRibuan ?
            number_format($jmlHrg / 1000, 2, '.', '') :
            number_format($jmlHrg, 2, '.', '');
    $sjmlHrg = $e1 == $kdSubUnit0 ? "<b>$sjmlHrg</b>" : $sjmlHrg;
    return "
		<td class='$cltd' align='right' ><div class='nfmt1'> $sjmlBrg</div></td>			
		<td class='$cltd' align='right' ><div class='nfmt4'>$sjmlHrg</div></td>";
}

function getList_RekapByOPD2_xls2($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=TRUE, $tgl='2012-1-1' ) {
    global $Main, $cek;
	$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
    //global $tglAwal, $tglAkhir;
    //$tgl ='2012-1-1';
    //kondisi kib -----------------------------------
    //$tblName = 'v_rekap_bi2'; //default
    switch ($SPg) {
        case '03': $KondisiKIB = '';
            break;
        case '04': $KondisiKIB = ' and  f="01" ';
            break;
        case '05': $KondisiKIB = ' and f="02" ';
            break;
        case '06': $KondisiKIB = ' and f="03" ';
            break;
        case '07': $KondisiKIB = ' and f="04" ';
            break;
        case '08': $KondisiKIB = ' and f="05" ';
            break;
        case '09': $KondisiKIB = ' and f="06" ';
            break;
        case '10': $KondisiKIB = '';
            break;
    }

    //list ------------------------------------------------------------------------
    //get resource table
    if ($SPg != '10') {
        $sqry = RekapByOPD_GetQuery($tgl, $KondisiKIB);
        $qry = mysql_query($sqry . ' ' . $LimitHal);
    } else {
        $sqry = RekapByOPD_GetQuery($tgl, ' and f="01"'); //kib a
        $qry = mysql_query($sqry . ' ' . $LimitHal);
        $sqry2 = RekapByOPD_GetQuery($tgl, ' and f="02"'); //kib b
        $qry2 = mysql_query($sqry2 . ' ' . $LimitHal);
        $sqry3 = RekapByOPD_GetQuery($tgl, ' and f="03"'); //kib c
        $qry3 = mysql_query($sqry3 . ' ' . $LimitHal);
        $sqry4 = RekapByOPD_GetQuery($tgl, ' and f="04"'); //kib d
        $qry4 = mysql_query($sqry4 . ' ' . $LimitHal);
        $sqry5 = RekapByOPD_GetQuery($tgl, ' and f="05"'); //kib e
        $qry5 = mysql_query($sqry5 . ' ' . $LimitHal);
        $sqry6 = RekapByOPD_GetQuery($tgl, ' and f="06"'); //kib f
        $qry6 = mysql_query($sqry6 . ' ' . $LimitHal);
    }
    $ListData = '';
    $no = $noawal + 1;
    if ($isCetak) {
        $cltd = 'GCTK';
    } else {
        $cltd = 'GarisDaftar';
    }
	//echo $sqry;
    while ($isi = mysql_fetch_array($qry)) {
		
		
		
		
        $clRow = $no % 2 == 0 ? "row1" : "row0"; //get css row
        if ($isCetak) {
            $clRow = '';
        }
        $no++;
        $sID = $isi['c'] . '.' . $isi['d'] . '.' . $isi['e']. '.' . $isi['e1'];
        //uraian -----------------
        if ($isi['c'] != '00' and $isi['d'] == '00') {//bid
            $uraian = $isi['c'] . '. ' . strtoupper($isi['nm_skpd']);
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else if ($isi['d'] != '00' and $isi['e'] == '00') {//opd
            $uraian = $isi['d'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:22;' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:22;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else  if ($isi['e'] != '00' and $isi['e1'] == $kdSubUnit0) {//opd
            $uraian = $isi['e'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:44;' id='$sID'><b>$uraian</b></td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:44;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	<b>$uraian</b></td>";
        } else {
            $uraian = $isi['e1'] . '. ' . $isi['nm_skpd'];
            $uraian = $isCetak ?
                    "<td class='$cltd' colspan=3 style='padding-left:66;' id='$sID'>$uraian</td>" :
                    "<td class='$cltd' colspan=3 style='padding-left:66;cursor:pointer' id='$sID'	onclick='getRkpBrg(event)' title='Klik untuk lihat rekap barang'>
			 	$uraian</td>";
        }

        //tampil kolom jml dan harga -----------
        list($jmlBrg, $jmlHrg, $tampil_jmlharga) = RekapByOPD_TampilJmldanHarga_xls($isi, $isRibuan, $cltd);
        if ($SPg == '10') {
            $i = $no - 2;
            if (!mysql_data_seek($qry2, $i)) {
                echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry3, $i)) {
                echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry4, $i)) {
                echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry5, $i)) {
                echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            if (!mysql_data_seek($qry6, $i)) {
                echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
            };
            $isi2 = mysql_fetch_assoc($qry2);
            $isi3 = mysql_fetch_assoc($qry3);
            $isi4 = mysql_fetch_assoc($qry4);
            $isi5 = mysql_fetch_assoc($qry5);
            $isi6 = mysql_fetch_assoc($qry6);
            list($jmlBrg2, $jmlHrg2, $tampil_jmlharga2) = RekapByOPD_TampilJmldanHarga_xls($isi2, $isRibuan, $cltd);
            list($jmlBrg3, $jmlHrg3, $tampil_jmlharga3) = RekapByOPD_TampilJmldanHarga_xls($isi3, $isRibuan, $cltd);
            list($jmlBrg4, $jmlHrg4, $tampil_jmlharga4) = RekapByOPD_TampilJmldanHarga_xls($isi4, $isRibuan, $cltd);
            list($jmlBrg5, $jmlHrg5, $tampil_jmlharga5) = RekapByOPD_TampilJmldanHarga_xls($isi5, $isRibuan, $cltd);
            list($jmlBrg6, $jmlHrg6, $tampil_jmlharga6) = RekapByOPD_TampilJmldanHarga_xls($isi6, $isRibuan, $cltd);
            $jmlBrgBI = $jmlBrg + $jmlBrg2 + $jmlBrg3 + $jmlBrg4 + $jmlBrg5 + $jmlBrg6;
            $jmlHrgBI = $jmlHrg + $jmlHrg2 + $jmlHrg3 + $jmlHrg4 + $jmlHrg5 + $jmlHrg6;
            $tampil_jmlhargaBI = RekapByOPD_TampilJmldanHarga_xls_($isi['e1'], $isRibuan, $cltd, $jmlBrgBI, $jmlHrgBI);
        }
        //tampil baris ----------------
        $ListData .=
                "<tr class='$clRow'>
			<td class='$cltd' align='center' >$no.</td>" .
                $uraian .
                $tampil_jmlhargaBI .
                $tampil_jmlharga .
                $tampil_jmlharga2 .
                $tampil_jmlharga3 .
                $tampil_jmlharga4 .
                $tampil_jmlharga5 .
                $tampil_jmlharga6 .
                "</tr>";
    }
    
	//cari total & jml data----------------------------------------------------
    $qry = mysql_query($sqry);
    if ($SPg == '10') {
        $qry2 = mysql_query($sqry2);
        $qry3 = mysql_query($sqry3);
        $qry4 = mysql_query($sqry4);
        $qry5 = mysql_query($sqry5);
        $qry6 = mysql_query($sqry6);
    }
    $jmlData = mysql_num_rows($qry);
    if ($noawal == 0) {
        $i = 0;
        while ($isi = mysql_fetch_array($qry)) {
            if ($isi['c'] != '00' and $isi['d'] == '00') {
                $totBrgHPS += $isi['jmlBrgHPS'] + $isi['jmlBrgPD']; 
                $totHrgHPS += $isi['jmlHrgHPS'] + $isi['jmlHrgPD'];
                $totHrgPLH += $isi['jmlHrgPLH'] + $isi['jmlHrgPD_PLH'];
                $totHrgAman += $isi['jmlHrgAman'] + $isi['jmlHrgPD_AMAN'] + $isi['jmlHrgGR_AMAN'];
                $totBrgBI += $isi['jmlBrgBI'];
                $totHrgBI += $isi['jmlHrgBI'];				
                if ($SPg == '10') {
                    if (!mysql_data_seek($qry2, $i)) {
                        echo "2 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry3, $i)) {
                        echo "3 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry4, $i)) {
                        echo "4 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry5, $i)) {
                        echo "5 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    if (!mysql_data_seek($qry6, $i)) {
                        echo "6 Cannot seek to row $i: " . mysql_error() . "\n";
                    };
                    $isi2 = mysql_fetch_assoc($qry2);
                    $isi3 = mysql_fetch_assoc($qry3);
                    $isi4 = mysql_fetch_assoc($qry4);
                    $isi5 = mysql_fetch_assoc($qry5);
                    $isi6 = mysql_fetch_assoc($qry6);
                    //B
                    $totBrgHPS2 += $isi2['jmlBrgHPS'] + $isi2['jmlBrgPD']; 
                	$totHrgHPS2 += $isi2['jmlHrgHPS'] + $isi2['jmlHrgPD'];
                	$totHrgPLH2 += $isi2['jmlHrgPLH'] + $isi2['jmlHrgPD_PLH'];
                	$totHrgAman2 += $isi2['jmlHrgAman'] + $isi2['jmlHrgPD_AMAN'] + $isi2['jmlHrgGR_AMAN'];
                    $totBrgBI2 += $isi2['jmlBrgBI'];
                    $totHrgBI2 += $isi2['jmlHrgBI'];
                    //C
                    $totBrgHPS3 += $isi3['jmlBrgHPS'] + $isi3['jmlBrgPD']; 
                	$totHrgHPS3 += $isi3['jmlHrgHPS'] + $isi3['jmlHrgPD'];
                	$totHrgPLH3 += $isi3['jmlHrgPLH'] + $isi3['jmlHrgPD_PLH'];
                	$totHrgAman3 += $isi3['jmlHrgAman'] + $isi3['jmlHrgPD_AMAN'] + $isi3['jmlHrgGR_AMAN'];
                    $totBrgBI3 += $isi3['jmlBrgBI'];
                    $totHrgBI3 += $isi3['jmlHrgBI'];
                    //D
                    $totBrgHPS4 += $isi4['jmlBrgHPS'] + $isi4['jmlBrgPD']; 
                	$totHrgHPS4 += $isi4['jmlHrgHPS'] + $isi4['jmlHrgPD'];
                	$totHrgPLH4 += $isi4['jmlHrgPLH'] + $isi4['jmlHrgPD_PLH'];
                	$totHrgAman4 += $isi4['jmlHrgAman'] + $isi4['jmlHrgPD_AMAN'] + $isi4['jmlHrgGR_AMAN'];
                    $totBrgBI4 += $isi4['jmlBrgBI'];
                    $totHrgBI4 += $isi4['jmlHrgBI'];
                    //D
					$totBrgHPS5 += $isi5['jmlBrgHPS'] + $isi5['jmlBrgPD']; 
                	$totHrgHPS5 += $isi5['jmlHrgHPS'] + $isi5['jmlHrgPD'];
                	$totHrgPLH5 += $isi5['jmlHrgPLH'] + $isi5['jmlHrgPD_PLH'];
                	$totHrgAman5 += $isi5['jmlHrgAman'] + $isi5['jmlHrgPD_AMAN'] + $isi5['jmlHrgGR_AMAN'];                    
                    $totBrgBI5 += $isi5['jmlBrgBI'];
                    $totHrgBI5 += $isi5['jmlHrgBI'];
                    //E
                    $totBrgHPS6 += $isi6['jmlBrgHPS'] + $isi6['jmlBrgPD']; 
                	$totHrgHPS6 += $isi6['jmlHrgHPS'] + $isi6['jmlHrgPD'];
                	$totHrgPLH6 += $isi6['jmlHrgPLH'] + $isi6['jmlHrgPD_PLH'];
                	$totHrgAman6 += $isi6['jmlHrgAman'] + $isi6['jmlHrgPD_AMAN'] + $isi6['jmlHrgGR_AMAN'];
                    $totBrgBI6 += $isi6['jmlBrgBI'];
                    $totHrgBI6 += $isi6['jmlHrgBI'];
                }
            }
            $i++;
        }
        $totBrg = $totBrgBI - $totBrgHPS;
        $totHrg = ($totHrgPLH + $totHrgAman + $totHrgBI) - $totHrgHPS;
        if ($SPg == '10') {
            $totBrg2 = $totBrgBI2 - $totBrgHPS2;
            $totHrg2 = ($totHrgPLH2 + $totHrgAman2 + $totHrgBI2) - $totHrgHPS2;
            $totBrg3 = $totBrgBI3 - $totBrgHPS3;
            $totHrg3 = ($totHrgPLH3 + $totHrgAman3 + $totHrgBI3) - $totHrgHPS3;
            $totBrg4 = $totBrgBI4 - $totBrgHPS4;
            $totHrg4 = ($totHrgPLH4 + $totHrgAman4 + $totHrgBI4) - $totHrgHPS4;
            $totBrg5 = $totBrgBI5 - $totBrgHPS5;
            $totHrg5 = ($totHrgPLH5 + $totHrgAman5 + $totHrgBI5) - $totHrgHPS5;
            $totBrg6 = $totBrgBI6 - $totBrgHPS6;
            $totHrg6 = ($totHrgPLH6 + $totHrgAman6 + $totHrgBI6) - $totHrgHPS6;
            $totBrgBI = $totBrg + $totBrg2 + $totBrg3 + $totBrg4 + $totBrg5 + $totBrg6; //$totBrgBI - $totBrgHPSBI;
            $totHrgBI = $totHrg + $totHrg2 + $totHrg3 + $totHrg4 + $totHrg5 + $totHrg6; //($totHrgPLHBI + $totHrgAmanBI + $totHrgBI) - $totHrgHPSBI;
        }
        //tampil tot
        $tampilTot_jmlharga = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg, $totHrg);
        if ($SPg == '10') {
            $tampilTot_jmlharga2 = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg2, $totHrg2);
            $tampilTot_jmlharga3 = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg3, $totHrg3);
            $tampilTot_jmlharga4 = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg4, $totHrg4);
            $tampilTot_jmlharga5 = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg5, $totHrg5);
            $tampilTot_jmlharga6 = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrg6, $totHrg6);
            $tampilTot_jmlhargaBI = RekapByOPD_TampilJmldanHarga_xls_('00', $isRibuan, $cltd, $totBrgBI, $totHrgBI);
        }
        //$no=;
        $sID = $row['c'] . '.' . $row['d'] . '.' . $row['e']. '.' . $row['e1'];
        $uraian = "<td class='$cltd' colspan=3><b>".$Main->NM_WILAYAH2."<b></td>";
        $ListData =
                "<tr class='$clRow'>
		<td class='$cltd' align='center' >1.</td>		
		$uraian
		$tampilTot_jmlhargaBI
		$tampilTot_jmlharga
		$tampilTot_jmlharga2
		$tampilTot_jmlharga3
		$tampilTot_jmlharga4
		$tampilTot_jmlharga5
		$tampilTot_jmlharga6
		<!--
		<td class='$cltd' align='right' ><b>$stotBrg</b></td>			
		<td class='$cltd' align='right' ><b>$stotHrg</b></td>-->			
		</tr>" . $ListData;
    }


    return array($ListData, $jmlData); 

}


function getList_RekapByBrg_xls($fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $noawal, $limitHal, $kolomwidth, $dlmRibuan=FALSE){
	global $Main;

	//get kondisi ----------------------------------------------------------------------------------
	$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
	$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
	$Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' 
					and c='$fmSKPD' $KondisiD $KondisiE ";
	if($fmSKPD == "00"){
		$Kondisi = " and a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' 
		$KondisiD $KondisiE ";
	}
	//$Kondisi .= " and status_barang=1";
	$Kondisi .=  ' and status_barang <> 3 ';

	//list --------------------------------------------------------------
	$jmlTotalHargaDisplay = 0;
	$ListData = "";
	//$no=0;
	$cb=0;
	$QryRefBarang = mysql_query("select ref.f,ref.g,ref.nm_barang from ref_barang as ref 
						where h='00' order by ref.f,ref.g");
	$jmlData = mysql_num_rows($QryRefBarang);
	$TotalHarga = 0;
	$totalBrg =0;
	$no=$noawal;
	while($isi=mysql_fetch_array($QryRefBarang)){
	
		$Kondisi1 = "concat(f, g)= '{$isi['f']}{$isi['g']}'";	
		$sqry = "select sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from buku_induk  
				where $Kondisi1 $Kondisi group by f,g order by f,g";
		$cek .= '<br> qry FG ='.$sqry;
		$QryBarang = mysql_query($sqry);
		$isi1 = mysql_fetch_array($QryBarang);
		$no++;
		$clRow = $no % 2 == 0 ?"row1":"row0";
		$kdBidang = $isi['g'] == "00"?"":$isi['g'];
		$nmBarang = $isi['g'] == "00"?"<b>{$isi['nm_barang']}</b>":"&nbsp;&nbsp;&nbsp;{$isi['nm_barang']}";
	
		$sqry2="select sum(jml_barang) as jml_barang, sum(jml_harga) as jml_harga from buku_induk  
				where f='{$isi['f']}' $Kondisi group by f order by f";
		$QryBarangAtas = mysql_fetch_array(	mysql_query( $sqry2	));
		$cek .= '<br> qry F ='.$sqry2;
		$jmlBarangAtas = $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:$isi1['jml_barang'];		
		$jmlBarangAtas = empty($jmlBarangAtas) ? "0" : "".$jmlBarangAtas."";
		
		$jmlBarangAtas = $isi['g'] == "00" ? "<b>".number_format(($jmlBarangAtas),0,'.', '')."" : "".number_format(($jmlBarangAtas),0,'.', '')."";
		
		if ($dlmRibuan){			
			$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']/1000),2,'.', '')."": "".number_format(($isi1['jml_harga']/1000),2,'.', '')."";
		}else{			
			$jmlHargaAtas = $isi['g'] == "00" ? "<b>".number_format(($QryBarangAtas['jml_harga']),2,'.', '')."": "".number_format(($isi1['jml_harga']),2,'.', '')."";			
		}
			
					
		$ListData .= "
			<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[0]\">$no.</td>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[1]\">{$isi['f']}</td>
			<td class=\"GarisDaftar\" align=center width=\"$kolomwidth[2]\">$kdBidang</td>
			<td class=\"GarisDaftar\" width=\"$kolomwidth[3]\">$nmBarang</div></td>
			<td class=\"GarisDaftar\" align=right width=\"$kolomwidth[4]\">$jmlBarangAtas</td>
			<td class=\"GarisDaftar\" align=right width=\"$kolomwidth[5]\">$jmlHargaAtas</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
        </tr>
		";
		
		$TotalHarga +=  $isi['g'] == "00" ? $QryBarangAtas['jml_harga'] :0;
		$totalBrg += $isi['g'] == "00" ? $QryBarangAtas['jml_barang']:0;//$isi1['jml_barang'];		
		$cb++;
	}
	
	$tampilTotHarga = $dlmRibuan ? number_format(($TotalHarga/1000), 2, '.', '') : number_format(($TotalHarga), 2, '.', ''); 
	$ListData .= "
			<tr class='row0'>
			<td colspan=4 class=\"GarisDaftar\">TOTAL</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format(($totalBrg), 0, '.', '')."</td>
			<td align=right class=\"GarisDaftar\"><b>".$tampilTotHarga."</td>
			<!--<td class=\"GarisDaftar\">&nbsp;</td>-->
			</tr>
			";

	//return $ListData;
	//return compact($ListData, $jmlData);
	return array($ListData, $jmlData);
	
}


function getList_RekapByOPD_xls($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=FALSE){
	global $Main,$cek;
	
	//pilih buku_nduk atau kib -----------------------------------
	$tblName = 'v_rekap_bi2'; //default
	switch ($SPg){
		case '03': $tblName = 'v_rekap_bi2'; 
		break;
		case '04': $tblName = 'v_rekap_kiba2'; break;
		case '05': $tblName = 'v_rekap_kibb2'; break;
		case '06': $tblName = 'v_rekap_kibc2'; break;
		case '07': $tblName = 'v_rekap_kibd2'; break;
		case '08': $tblName = 'v_rekap_kibe2'; break;
		case '09': $tblName = 'v_rekap_kibf2'; break;
		case '10': $tblName = 'v_rekap_all2'; break;
	}

	//$cek = ' <br> SPg = '.$SPg;
	$sqry = ' select * from '.$tblName; //$cek .= ' sqry= '.$sqry.' '.$LimitHal.'<br>';
//	$qry = mysql_query( $sqry );
	$qry = mysql_query('select * from ref_skpd');

	$jmlData= mysql_num_rows( $qry );

	$qry = mysql_query($sqry.' '.$LimitHal);
	$ListData ='';
	$no=$noawal;//$Main->PagePerHal * (($HalDefault*1) - 1);
	//$rekap->totPerHal= array('bi'=>0, 'kiba'=>0, 'kibb'=>0, 'kibc'=>0, 'kibd'=>0, 'kibe'=>0, 'kibf'=>0);

	//cari total ------------------------------------
	if ($isCetak){ 
			//$clRow='';
			$cltd = 'GCTK';
		}else{
			$cltd = 'GarisDaftar';
		}
	if ($no==0){	
		
		$no=1;//$clRow = $no % 2 == 0 ?"row1":"row0";//get css row tot col 8
		$uraian = '<td class="'.$cltd.'" colspan=6><b>'.$Main->NM_WILAYAH2.'<b></td>';
		$totbi	= table_get_value('select sum(jml) as tot from '.$tblName,'tot');		
		$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>';
		$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName,'totbrg');		
		$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>';
					
		if ($SPg==10){
			$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName,'tot');		
			$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName,'tot');		
			$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName,'tot');		
			$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName,'tot');		
			$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName,'tot');		
			$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName,'tot');		
			$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			
		}
		
		if($SPg != 10){
			$ListData =
			'<tr class="'.$clRow.'">
			<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
			'.$uraian.'		
			<td class="'.$cltd.'" align="right" ><div class="nfmt1">'.$stotbibrg.'</div></td>		
			<td class="'.$cltd.'" align="right" ><div class="nfmt4">'.$stotbi.'</div></td>		
			</tr>';
		
		}else{
			$ListData =
			'<tr class="'.$clRow.'">
			<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
			'.$uraian.'		
			<td class="'.$cltd.'" align="right" >'.$stotbi.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkiba.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibb.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibc.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibd.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibe.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibf.'</td>		
			</tr>';
		
		}
		
	}else{
		$no++;
	}

		$kondisi = " and a1='$Main->DEF_KEPEMILIKAN' and a='$Main->DEF_PROPINSI' and b='$Main->DEF_WILAYAH' ";

$xqry="SELECT
  `aa`.`c` AS `c`, `aa`.`d` AS `d`, `aa`.`e` AS `e`,`aa`.`e1` AS `e1`,
  `aa`.`nm_skpd` AS `nm_skpd`,
coalesce(be1.jmlbrg,0)+coalesce(be2.jmlbrg,0)+coalesce(be3.jmlbrg,0)+coalesce(be4.jmlbrg,0) as jmlbrg,
coalesce(be1.jml,0)+coalesce(be2.jml,0)+coalesce(be3.jml,0)+coalesce(be4.jml,0) as jml,
coalesce(be1.jmlbrgk1,0)+coalesce(be2.jmlbrgk1,0)+coalesce(be3.jmlbrgk1,0)+coalesce(be4.jmlbrgk1,0) as jmlbrgk1,
coalesce(be1.jmlk1,0)+coalesce(be2.jmlk1,0)+coalesce(be3.jmlk1,0)+coalesce(be4.jmlk1,0) as jmlk1,
coalesce(be1.jmlbrgk2,0)+coalesce(be2.jmlbrgk2,0)+coalesce(be3.jmlbrgk2,0)+coalesce(be4.jmlbrgk2,0) as jmlbrgk2,
coalesce(be1.jmlk2,0)+coalesce(be2.jmlk2,0)+coalesce(be3.jmlk2,0)+coalesce(be4.jmlk2,0) as jmlk2,
coalesce(be1.jmlbrgk3,0)+coalesce(be2.jmlbrgk3,0)+coalesce(be3.jmlbrgk3,0)+coalesce(be4.jmlbrgk3,0) as jmlbrgk3,
coalesce(be1.jmlk3,0)+coalesce(be2.jmlk3,0)+coalesce(be3.jmlk3,0)+coalesce(be4.jmlk3,0) as jmlk3,
coalesce(be1.jmlbrgk4,0)+coalesce(be2.jmlbrgk4,0)+coalesce(be3.jmlbrgk4,0)+coalesce(be4.jmlbrgk4,0) as jmlbrgk4,
coalesce(be1.jmlk4,0)+coalesce(be2.jmlk4,0)+coalesce(be3.jmlk4,0)+coalesce(be4.jmlk4,0) as jmlk4,
coalesce(be1.jmlbrgk5,0)+coalesce(be2.jmlbrgk5,0)+coalesce(be3.jmlbrgk5,0)+coalesce(be4.jmlbrgk5,0) as jmlbrgk5,
coalesce(be1.jmlk5,0)+coalesce(be2.jmlk5,0)+coalesce(be3.jmlk5,0)+coalesce(be4.jmlk5,0) as jmlk5,
coalesce(be1.jmlbrgk6,0)+coalesce(be2.jmlbrgk6,0)+coalesce(be3.jmlbrgk6,0)+coalesce(be4.jmlbrgk6,0) as jmlbrgk6,
coalesce(be1.jmlk6,0)+coalesce(be2.jmlk6,0)+coalesce(be3.jmlk6,0)+coalesce(be4.jmlk6,0) as jmlk6
  
FROM
  `ref_skpd` `aa` LEFT JOIN
 ( select c,d,e,e1,sum(jml_barang) as jmlbrg,sum(harga) as jml,
  sum(if(f='01',jml_barang,0)) as jmlbrgk1,sum(if(f='01',harga,0)) as jmlk1, 
  sum(if(f='02',jml_barang,0)) as jmlbrgk2,sum(if(f='02',harga,0)) as jmlk2, 
  sum(if(f='03',jml_barang,0)) as jmlbrgk3,sum(if(f='03',harga,0)) as jmlk3, 
  sum(if(f='04',jml_barang,0)) as jmlbrgk4,sum(if(f='04',harga,0)) as jmlk4, 
  sum(if(f='05',jml_barang,0)) as jmlbrgk5,sum(if(f='05',harga,0)) as jmlk5, 
  sum(if(f='06',jml_barang,0)) as jmlbrgk6,sum(if(f='06',harga,0)) as jmlk6 
  
  from buku_induk
  where status_barang<>3 $kondisi
  group by b,c,d,e,e1  ) as `be4`  
  ON `aa`.`c` = `be4`.`c` AND `aa`.`d` = `be4`.`d` AND `aa`.`e` = `be4`.`e` AND `aa`.`e1` = `be4`.`e1`  
LEFT JOIN
( select c,d,e,sum(jml_barang) as jmlbrg,sum(harga) as jml,
  sum(if(f='01',jml_barang,0)) as jmlbrgk1,sum(if(f='01',harga,0)) as jmlk1, 
  sum(if(f='02',jml_barang,0)) as jmlbrgk2,sum(if(f='02',harga,0)) as jmlk2, 
  sum(if(f='03',jml_barang,0)) as jmlbrgk3,sum(if(f='03',harga,0)) as jmlk3, 
  sum(if(f='04',jml_barang,0)) as jmlbrgk4,sum(if(f='04',harga,0)) as jmlk4, 
  sum(if(f='05',jml_barang,0)) as jmlbrgk5,sum(if(f='05',harga,0)) as jmlk5, 
  sum(if(f='06',jml_barang,0)) as jmlbrgk6,sum(if(f='06',harga,0)) as jmlk6 


 from buku_induk
  where status_barang<>3 $kondisi
  group by c,d,e  ) as `be3`  
  ON `aa`.`c` = `be3`.`c` AND `aa`.`d` = `be3`.`d` AND `aa`.`e` = `be3`.`e`  AND `aa`.`e1` = '000'   
LEFT JOIN
( select c,d,sum(jml_barang) as jmlbrg,sum(harga) as jml,
  sum(if(f='01',jml_barang,0)) as jmlbrgk1,sum(if(f='01',harga,0)) as jmlk1, 
  sum(if(f='02',jml_barang,0)) as jmlbrgk2,sum(if(f='02',harga,0)) as jmlk2, 
  sum(if(f='03',jml_barang,0)) as jmlbrgk3,sum(if(f='03',harga,0)) as jmlk3, 
  sum(if(f='04',jml_barang,0)) as jmlbrgk4,sum(if(f='04',harga,0)) as jmlk4, 
  sum(if(f='05',jml_barang,0)) as jmlbrgk5,sum(if(f='05',harga,0)) as jmlk5, 
  sum(if(f='06',jml_barang,0)) as jmlbrgk6,sum(if(f='06',harga,0)) as jmlk6 

 from buku_induk
  where status_barang<>3 $kondisi
  group by c,d  ) as `be2`  
  ON `aa`.`c` = `be2`.`c` AND `aa`.`d` = `be2`.`d` AND `aa`.`e` = '00'  AND `aa`.`e1` = '000'   
LEFT JOIN
( select c,sum(jml_barang) as jmlbrg,sum(harga) as jml,
  sum(if(f='01',jml_barang,0)) as jmlbrgk1,sum(if(f='01',harga,0)) as jmlk1, 
  sum(if(f='02',jml_barang,0)) as jmlbrgk2,sum(if(f='02',harga,0)) as jmlk2, 
  sum(if(f='03',jml_barang,0)) as jmlbrgk3,sum(if(f='03',harga,0)) as jmlk3, 
  sum(if(f='04',jml_barang,0)) as jmlbrgk4,sum(if(f='04',harga,0)) as jmlk4, 
  sum(if(f='05',jml_barang,0)) as jmlbrgk5,sum(if(f='05',harga,0)) as jmlk5, 
  sum(if(f='06',jml_barang,0)) as jmlbrgk6,sum(if(f='06',harga,0)) as jmlk6 

 from buku_induk
  where status_barang<>3 $kondisi
  group by c  ) as `be1`  
  ON `aa`.`c` = `be1`.`c` AND `aa`.`d` = '00' AND `aa`.`e` = '00'  AND `aa`.`e1` = '000'    
";

//create list -------------------------------------
	while ($row=mysql_fetch_array($qry)){
		$clRow = $no % 2 == 0 ?"row1":"row0";//get css row
		if ($isCetak){ 	$clRow=''; }
			
		$no++;	
		$sID = $row['c'].'.'.$row['d'].'.'.$row['e'];
	
		if($row['c']=='' ){//total
			$uraian	= '<td class="'.$cltd.'" colspan=3><b>TOTAL<b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName,'tot');
			$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>' ;
			$totbibrg	= table_get_value('select sum(jmlbrg) as tot from '.$tblName,'totbrg');
			$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>' ;

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName,'tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName,'tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName,'tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName,'tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName,'tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName,'tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
			
		}elseif($row['c']!='00' and $row['d']=='00'  ){//tot bidang			
			$uraian = '<td class="GCTK1"> 
					<b>&nbsp;'.$row['c'].'.</b></td><td class="GCTK2" colspan=5><b>'.strtoupper($row['nm_skpd']).'</b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');
			$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>';
			$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName.' where c="'.$row['c'].'"','totbrg');
			$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>';

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
		}elseif($row['d']!='00' and $row['e']=='00' ){//tot OPD			
			$uraian ='<td class="GCTK1" >&nbsp;</td>'. 
					'<td class="GCTK3"> 
					<b>&nbsp;'.$row['d'].'.</b></td><td class="GCTK2" colspan=4><b>'.($row['nm_skpd']).'</b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');
			$stotbi = '<b>'.number_format($totbi, 2, '.', '').'</b>';
			$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');
			$stotbibrg = '<b>'.number_format($totbibrg, 0, '.', '').'</b>';

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"'.$row['c'].'"','tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
		}elseif($row['e']!='00' ){//tot unit			
			$uraian = '<td class="GCTK1">&nbsp;</td><td class="GCTK3">&nbsp;</td> 
					<td class="GCTK3">&nbsp;'.$row['e'].'.</td><td class="GCTK2" colspan=3 >'.$row['nm_skpd'].'</td>';		
			$totbi	= $row['jml'];
			$stotbi = number_format($totbi, 2, '.', '');		
			$totbibrg	= $row['jmlbrg'];
			$stotbibrg = number_format($totbibrg, 0, '.', '');		

			if ($SPg==10){
				$tot = $row['jmlkiba'];
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibb'];
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibc'];
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibd'];
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibe'];
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibf'];
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
		}else{
		
		}
	
		if ($SPg!=10){
			$ListData .=
				'<tr class="'.$clRow.'">
				<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
				'.$uraian.'		
				<td class="'.$cltd.'" align="right" ><div class="nfmt1">'.$stotbibrg.'</div></td>						<td class="'.$cltd.'" align="right" ><div class="nfmt4">'.$stotbi.'</div></td>			
				</tr>';	
		}else{
			$ListData .=
				'<tr class="'.$clRow.'">
				<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
				'.$uraian.'		
				<td class="'.$cltd.'" align="right" >'.$stotbi.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkiba.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibb.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibc.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibd.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibe.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibf.'</td>			
				
			</tr>';
		}
		
	
	
	}


	//------ create html total harga -----------
	/*
	$ListData .= '

		<!--footer table -->
		<tr><td colspan=11 >&nbsp</td></tr>
		<tr>
		<td colspan=15 align=center height="50">'.$BarisPerHalaman.'&nbsp;&nbsp'.Halaman($jmlData,$Main->PagePerHal,'HalDefault').'</td>
		</tr>
		'.$cek;	*/
		
	return array($ListData, $jmlData);
}

function getList_RekapByOPDv2_xls($SPg, $noawal, $LimitHal, $kolomwidth, $isCetak, $isRibuan=FALSE){
	global $Main,$cek;
	
	//pilih buku_nduk atau kib -----------------------------------
	$tblName = 'v_rekap_bi2'; //default
	switch ($SPg){
		case '03': $tblName = 'v_rekap_bi2'; 
		$kondisi='';
		break;
		case '04': $tblName = 'v_rekap_kiba2';
		$kondisi=" and f='01' ";
		break;
		case '05': $tblName = 'v_rekap_kibb2';
		$kondisi=" and f='02' ";
		break;
		case '06': $tblName = 'v_rekap_kibc2';
		$kondisi=" and f='03' ";
		break;
		case '07': $tblName = 'v_rekap_kibd2'; 
		$kondisi=" and f='04' ";
		break;
		case '08': $tblName = 'v_rekap_kibe2';
		$kondisi=" and f='05' ";
		break;
		case '09': $tblName = 'v_rekap_kibf2';
		$kondisi=" and f='07' ";
		break;
		case '10': $tblName = 'v_rekap_all2'; break;
	}

	$xqry="SELECT
  `aa`.`c` AS `c`, `aa`.`d` AS `d`, `aa`.`e` AS `e`,`aa`.`e1` AS `e1`,
  `aa`.`nm_skpd` AS `nm_skpd`,
 `be1`.`jml` AS `jml1`, `be1`.`jmlbrg` AS `jmlbrg1`,
 `be2`.`jml` AS `jml2`, `be2`.`jmlbrg` AS `jmlbrg2`,
 `be3`.`jml` AS `jml3`, `be3`.`jmlbrg` AS `jmlbrg3`,
    `be4`.`jml` AS `jml4`, `be4`.`jmlbrg` AS `jmlbrg4`,
  
FROM
  `ref_skpd` `aa` LEFT JOIN
 ( select c,d,e,e1,sum(jml_barang) as jmlbrg,sum(harga) as jml from buku_induk
  where status_barang<>3 and a1=12 and a=10 $kondisi
  group by b,c,d,e,e1  ) as `be4`  
  ON `aa`.`c` = `be4`.`c` AND `aa`.`d` = `be4`.`d` AND `aa`.`e` = `be4`.`e` AND `aa`.`e1` = `be4`.`e1`  
LEFT JOIN
( select c,d,e,sum(jml_barang) as jmlbrg,sum(harga) as jml from buku_induk
  where status_barang<>3 and a1=12 and a=10 $kondisi
  group by c,d,e  ) as `be3`  
  ON `aa`.`c` = `be3`.`c` AND `aa`.`d` = `be3`.`d` AND `aa`.`e` = `be3`.`e`  AND `aa`.`e1` = '00'   
LEFT JOIN
( select c,d,sum(jml_barang) as jmlbrg,sum(harga) as jml from buku_induk
  where status_barang<>3 and a1=12 and a=10 $kondisi
  group by c,d  ) as `be2`  
  ON `aa`.`c` = `be2`.`c` AND `aa`.`d` = `be2`.`d` AND `aa`.`e` = '00'  AND `aa`.`e1` = '00'   
LEFT JOIN
( select c,sum(jml_barang) as jmlbrg,sum(harga) as jml from buku_induk
  where status_barang<>3 and a1=12 and a=10 $kondisi
  group by c  ) as `be1`  
  ON `aa`.`c` = `be1`.`c` AND `aa`.`d` = '00' AND `aa`.`e` = '00'  AND `aa`.`e1` = '00'   ";
	//$cek = ' <br> SPg = '.$SPg;
	$sqry = ' select * from '.$tblName; //$cek .= ' sqry= '.$sqry.' '.$LimitHal.'<br>';
	$qry = mysql_query( $sqry );
	$jmlData= mysql_num_rows( $qry );

	$qry = mysql_query($sqry.' '.$LimitHal);
	$ListData ='';
	$no=$noawal;//$Main->PagePerHal * (($HalDefault*1) - 1);
	//$rekap->totPerHal= array('bi'=>0, 'kiba'=>0, 'kibb'=>0, 'kibc'=>0, 'kibd'=>0, 'kibe'=>0, 'kibf'=>0);

	//cari total ------------------------------------
	if ($isCetak){ 
			//$clRow='';
			$cltd = 'GCTK';
		}else{
			$cltd = 'GarisDaftar';
		}
	if ($no==0){	
		
		$no=1;//$clRow = $no % 2 == 0 ?"row1":"row0";//get css row tot col 8
		$uraian = '<td class="'.$cltd.'" colspan=6><b>'.$Main->NM_WILAYAH2.'<b></td>';
		$totbi	= table_get_value('select sum(jml) as tot from '.$tblName,'tot');		
		$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>';
		$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName,'totbrg');		
		$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>';
					
		if ($SPg==10){
			$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName,'tot');		
			$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName,'tot');		
			$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName,'tot');		
			$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName,'tot');		
			$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName,'tot');		
			$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
			$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName,'tot');		
			$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			
		}
		
		if($SPg != 10){
			$ListData =
			'<tr class="'.$clRow.'">
			<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
			'.$uraian.'		
			<td class="'.$cltd.'" align="right" ><div class="nfmt1">'.$stotbibrg.'</div></td>		
			<td class="'.$cltd.'" align="right" ><div class="nfmt4">'.$stotbi.'</div></td>		
			</tr>';
		
		}else{
			$ListData =
			'<tr class="'.$clRow.'">
			<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
			'.$uraian.'		
			<td class="'.$cltd.'" align="right" >'.$stotbi.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkiba.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibb.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibc.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibd.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibe.'</td>		
			<td class="'.$cltd.'" align="right" >'.$stotkibf.'</td>		
			</tr>';
		
		}
		
	}else{
		$no++;
	}

//create list -------------------------------------
	while ($row=mysql_fetch_array($qry)){
		$clRow = $no % 2 == 0 ?"row1":"row0";//get css row
		if ($isCetak){ 	$clRow=''; }
			
		$no++;	
		$sID = $row['c'].'.'.$row['d'].'.'.$row['e'];
	
		if($row['c']=='' ){//total
			$uraian	= '<td class="'.$cltd.'" colspan=3><b>TOTAL<b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName,'tot');
			$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>' ;
			$totbibrg	= table_get_value('select sum(jmlbrg) as tot from '.$tblName,'totbrg');
			$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>' ;

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName,'tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName,'tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName,'tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName,'tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName,'tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName,'tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
			
		}elseif($row['c']!='00' and $row['d']=='00'  ){//tot bidang			
			$uraian = '<td class="GCTK1"> 
					<b>&nbsp;'.$row['c'].'.</b></td><td class="GCTK2" colspan=5><b>'.strtoupper($row['nm_skpd']).'</b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');
			$stotbi ='<b>'.number_format($totbi, 2, '.', '').'</b>';
			$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName.' where c="'.$row['c'].'"','totbrg');
			$stotbibrg ='<b>'.number_format($totbibrg, 0, '.', '').'</b>';

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName.' where c="'.$row['c'].'"','tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
		}elseif($row['d']!='00' and $row['e']=='00' ){//tot OPD			
			$uraian ='<td class="GCTK1" >&nbsp;</td>'. 
					'<td class="GCTK3"> 
					<b>&nbsp;'.$row['d'].'.</b></td><td class="GCTK2" colspan=4><b>'.($row['nm_skpd']).'</b></td>';
			$totbi	= table_get_value('select sum(jml) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');
			$stotbi = '<b>'.number_format($totbi, 2, '.', '').'</b>';
			$totbibrg	= table_get_value('select sum(jmlbrg) as totbrg from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');
			$stotbibrg = '<b>'.number_format($totbibrg, 0, '.', '').'</b>';

			if ($SPg==10){
				$tot = table_get_value('select sum(jmlkiba) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibb) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibc) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibd) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"'.$row['c'].'"','tot');		
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibe) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = table_get_value('select sum(jmlkibf) as tot from '.$tblName.' where c="'.$row['c'].'" and d="'.$row['d'].'"','tot');		
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
		}elseif($row['e']!='00' ){//tot unit			
			$uraian = '<td class="GCTK1">&nbsp;</td><td class="GCTK3">&nbsp;</td> 
					<td class="GCTK3">&nbsp;'.$row['e'].'.</td><td class="GCTK2" colspan=3 >'.$row['nm_skpd'].'</td>';		
			$totbi	= $row['jml'];
			$stotbi = number_format($totbi, 2, '.', '');		
			$totbibrg	= $row['jmlbrg'];
			$stotbibrg = number_format($totbibrg, 0, '.', '');		

			if ($SPg==10){
				$tot = $row['jmlkiba'];
				$stotkiba ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibb'];
				$stotkibb ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibc'];
				$stotkibc ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibd'];
				$stotkibd ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibe'];
				$stotkibe ='<b>'.number_format($tot, 2, '.', '').'</b>';
				$tot = $row['jmlkibf'];
				$stotkibf ='<b>'.number_format($tot, 2, '.', '').'</b>';
			}
			
		}else{
		
		}
	
		if ($SPg!=10){
			$ListData .=
				'<tr class="'.$clRow.'">
				<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
				'.$uraian.'		
				<td class="'.$cltd.'" align="right" ><div class="nfmt1">'.$stotbibrg.'</div></td>						<td class="'.$cltd.'" align="right" ><div class="nfmt4">'.$stotbi.'</div></td>			
				</tr>';	
		}else{
			$ListData .=
				'<tr class="'.$clRow.'">
				<td class="'.$cltd.'" align="center" >'.$no.'.</td>		
				'.$uraian.'		
				<td class="'.$cltd.'" align="right" >'.$stotbi.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkiba.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibb.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibc.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibd.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibe.'</td>			
				<td class="'.$cltd.'" align="right" >'.$stotkibf.'</td>			
				
			</tr>';
		}
		
	
	
	}


	//------ create html total harga -----------
	/*
	$ListData .= '

		<!--footer table -->
		<tr><td colspan=11 >&nbsp</td></tr>
		<tr>
		<td colspan=15 align=center height="50">'.$BarisPerHalaman.'&nbsp;&nbsp'.Halaman($jmlData,$Main->PagePerHal,'HalDefault').'</td>
		</tr>
		'.$cek;	*/
		
	return array($ListData, $jmlData);
}


function rekapbrg_xls_header($XXBIDANG='',$XXASISTEN='',
$XXBIRO='',$XXSEKSI='',$XXKOTA='',$XXPROP='',$XXKDLOK='') {
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
			<td align=left colspan="4" style="font-weight:bold;font-size:9pt" >'.$XXPROP.'</td> </tr> 
			</table>

	<table width="100%" border="0">
		<tr>
			<td align=right style="font-weight:bold;font-size:9pt" colspan="7">&nbsp;</td>
		</tr>
	</table>
	
';
return $isix;	
}
function rekapbrg_xls_header_table(){
$isix='<table border="1" class="cetak">
	<tr>
	<thead>
		<th class="th01"  width="50">Nomor</th>
		<th class="th01"  width="50">Golongan</th>
		<th class="th01"   width="50">Kode<br>Bidang<br>Barang</th>
		<th class="th01" >Nama Bidang Barang</th>
		<th class="th01" width="120">Jumlah Barang</th>
		<th class="th01" width="120">Jumlah Harga</th>
		<th class="th01" width="220">Keterangan</th>
	</tr>
	</thead>';	
return $isix;	
	
}
function rekapbrg_xls_footer_table($jmlb=0,$jmlh=0){
$isix='	<tr class="row0">
			<td colspan=4 class="GCTK">TOTAL</td>
			<td align=right class="GCTK"><b><div class="nfmt1">'.$jmlb.'</div></td>
			<td align=right class="GCTK"><b><div class="nfmt4">'.$jmlh.'</div></td>
			<td class="GCTK">&nbsp;</td>
		</tr>
		</table>
		';	
return $isix;	

}

function rekapbrg_xls_footer($XXTMPTGL='',$XXJABATAN1='',$XXNAMA1='',$XXNIP1='',
$XXJABATAN2='',$XXNAMA2='',$XXNIP2='')
{
$isix='
<table style="width:30cm" border=0> 
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>

				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td>&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=4 >&nbsp;</td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				</tr>
				<tr> 
				<td align=center colspan=4 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP2.'</B> </td>
				</tr>
				</table>

</body>
</html>
';
return $isix;	
}


function Viewer_Cari_GetList_XLS($cetak = FALSE){
	global $cari, $Main, $Pg, $SPg, $addPageParam, $all;
	global $cbxDlmRibu;
	global $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTahunPerolehan, $kode_barang, $nm_barang, $selStatusBrg;
	global $bersertifikat, $Kondisi;
	global $asc, $cbAscDsc, $selUrut, $Urutkan;
	global $OrderByKota, $ViewStatus, $FilterKotaKosong;
	global $jmlData, $jmlDataKIB_A, $jmlDataKIB_B, $jmlDataKIB_C, $jmlDataKIB_D, $jmlDataKIB_E, $jmlDataKIB_F;
	global $jmPerHal, $HalDefault, $HalKIB_A, $HalKIB_B, $HalKIB_C, $HalKIB_D, $HalKIB_E, $HalKIB_F;
	global $LimitHal, $LimitHalKIB_A, $LimitHalKIB_B, $LimitHalKIB_C, $LimitHalKIB_D, $LimitHalKIB_E, $LimitHalKIB_F;
	global $selHakPakai, $alamat, $selKabKota, $noSert, $keterangan;
	global $merk, $bahan, $noPabrik, $noRangka, $noMesin, $noPolisi, $noBPKB;
	global $konsTingkat, $konsBeton, $dokumen_no, $status_tanah, $kode_tanah;
	global $konstruksi;
	global $buku_judul, $buku_spesifikasi, $seni_asal_daerah, $seni_pencipta, $seni_bahan, $hewan_jenis, $hewan_ukuran;
	global $bangunan;
	
	//"?Pg=$Pg&SPg=$SPg$addPageParam"
	
	$clGaris = $cetak == TRUE? "GCTK" : "GarisDaftar";
	
	//Limit -----------------------------------------------------------
	if (empty($all)){	
	switch ($SPg){
		case '03':{
			$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
			break;
		}
		case '04':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_A = " limit ".(($HalKIB_A*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '05':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_B = " limit ".(($HalKIB_B*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '06':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_C = " limit ".(($HalKIB_C*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '07':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_D = " limit ".(($HalKIB_D*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '08':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_E = " limit ".(($HalKIB_E*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '09':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_F = " limit ".(($HalKIB_F*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
	}
	}else{
		$LimitHal = ""; $LimitHalKIB_A=""; $LimitHalKIB_C =""; $LimitHalKIB_D=""; $LimitHalKIB_E=""; $LimitHalKIB_F="";
	}
	
			
	//Kondisi -------------------------------------------------------
	//$Kondisi = 'a1="'.$fmKEPEMILIKAN.'" and a="'.$Main->Provinsi[0].'" and b="'.$fmWIL.'" ';
	$Kondisi = 'a1="'.$fmKEPEMILIKAN.'" ';
	$Kondisi .=  $fmSKPD ==''?'': ' and  c="'.$fmSKPD.'"';
	$Kondisi .=  $fmUNIT =='00'?'': ' and d="'.$fmUNIT.'"';
	$Kondisi .=  $fmSUBUNIT =='00'?'': ' and e="'.$fmSUBUNIT.'"';
	$Kondisi .=  $fmTahunPerolehan ==''?'': ' and thn_perolehan="'.$fmTahunPerolehan.'"';
	$Kondisi .=  $selKondisiBrg ==''?'': ' and kondisi="'.$selKondisiBrg.'"';
	$Kondisi .=  $kode_barang ==''?'': ' and concat(f,".",g,".",h,".",i,".",j) like "'.$kode_barang.'%"';
	$Kondisi .=  $nm_barang ==''?'': ' and nm_barang like "%'.$nm_barang.'%"';
	switch($selStatusBrg){
		case '' : $Kondisi .= ' and status_barang=1'; break;
		case '1': $Kondisi .= ''; break;
		default :{
			$Kondisi .= ' and status_barang = "'.$selStatusBrg.'"';	
		}
	}		
	switch ($SPg){
		case '03':{
			break;
		}
		case '04':{			
			$Kondisi .=  $selHakPakai ==''?'': ' and status_hak="'.$selHakPakai.'"';
			$Kondisi .=  $alamat == ''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota == ''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $noSert ==''?'': ' and sertifikat_no like "%'.$noSert.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';	
			//$Kondisi .=  $bersertifikat ==''?'': ' and bersertifikat ="'.$bersertifikat.'"';
			if ($bersertifikat !=''){
				switch ($bersertifikat){		
					case '1': $Kondisi .= ' and bersertifikat = "1" '; break;
					case '2': $Kondisi .= ' and bersertifikat = "2" '; break;
					case '3': $Kondisi .= ' and (bersertifikat = "" or bersertifikat is null) '; break;
				}	
			}		
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		}
		case '05':{
			$Kondisi .=  $merk ==''?'': ' and merk like "%'.$merk.'%"';
			$Kondisi .=  $bahan ==''?'': ' and bahan like "%'.$bahan.'%"';
			$Kondisi .=  $noPabrik ==''?'': ' and no_pabrik like "%'.$noPabrik.'%"';
			$Kondisi .=  $noRangka ==''?'': ' and no_rangka like "%'.$noRangka.'%"';
			$Kondisi .=  $noMesin ==''?'': ' and no_mesin like "%'.$noMesin.'%"';
			$Kondisi .=  $noPolisi ==''?'': ' and no_polisi like "%'.$noPolisi.'%"';
			$Kondisi .=  $noBPKB ==''?'': ' and no_bpkb like "%'.$noBPKB.'%"'; 
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			break; 
		}
		case '06':{						
			$Kondisi .=  $konsTingkat ==''?'': ' and konstruksi_tingkat="'.$konsTingkat.'"';
			$Kondisi .=  $konsBeton ==''?'': ' and konstruksi_beton="'.$konsBeton.'"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';			
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		} 
		case '07':{
			$Kondisi .=  $konstruksi ==''?'': ' and konstruksi like "%'.$konstruksi.'%"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break; 
		}
		case '08':{
			$Kondisi .=  $buku_judul ==''?'': ' and buku_judul like "%'.$buku_judul.'%"';
			$Kondisi .=  $buku_spesifikasi ==''?'': ' and buku_spesifikasi like "%'.$buku_spesifikasi.'%"';
			$Kondisi .=  $seni_asal_daerah ==''?'': ' and seni_asal_daerah like "%'.$seni_asal_daerah.'%"';
			$Kondisi .=  $seni_pencipta ==''?'': ' and seni_pencipta like "%'.$seni_pencipta.'%"';
			$Kondisi .=  $seni_bahan ==''?'': ' and seni_bahan like "%'.$seni_bahan.'%"';
			$Kondisi .=  $hewan_jenis ==''?'': ' and hewan_jenis like "%'.$hewan_jenis.'%"';
			$Kondisi .=  $hewan_ukuran ==''?'': ' and hewan_ukuran like "%'.$hewan_ukuran.'%"'; 
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			break;   
		}
		case '09':{			
			$Kondisi .=  $bangunan ==''?'': ' and bangunan = "'.$bangunan.'"';
			$Kondisi .=  $konsTingkat ==''?'': ' and konstruksi_tingkat="'.$konsTingkat.'"';
			$Kondisi .=  $konsBeton ==''?'': ' and konstruksi_beton="'.$konsBeton.'"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		}  
	}
	//$cari->cek .= ' kon='.$Kondisi;
		
	//sorting --------------------------------------------------------
	$asc = !empty($cbAscDsc)? " desc ":"";
	if($selUrut !=''){	$Urutkan = $selUrut.' '.$asc.', ';}
	switch ($SPg){
		case '03':{
			break;
		}
		case '04':{			
			if($OrderByKota == TRUE){
				$Urutkan = empty($Urutkan)? "": "$Urutkan,";
				$Urutkan .= "alamat_kota, alamat_kec, alamat_kel,";
			}
			break;
		}
		case '05':{
			break;
		}
		case '06':{			
			if($OrderByKota == TRUE){
				$Urutkan = empty($Urutkan)? "": "$Urutkan,";
				$Urutkan .= "alamat_kota, alamat_kec, alamat_kel,";
			}
			break;
		}
	}
		
	//ListData --------------------------------------------------------	
	switch ($SPg){
		case '03':{
			$sqry = "select * from view_buku_induk2 where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,noreg";			
			//echo "qry=$sqry $LimitHal<br> ";
			$qry = mysql_query($sqry);
			$jmlData= mysql_num_rows($qry);
			$qry = mysql_query($sqry.' '.$LimitHal);
			$no= empty($all)? $Main->PagePerHal * (($HalDefault*1) - 1) : 0; //$cari->cek .=' no='.$no; 
			$cb=0;
			$cari->listdata = '';
			while($isi=mysql_fetch_array($qry)){
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				//$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$no++;
	
				$jmlTotalHargaDisplay += $isi['jml_harga'];
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$AsalUsul = $isi['asal_usul'];
				$ISI5 = "";
				$ISI6 = "";
				$ISI7 = "";
				$ISI10 = "";
				$ISI12 = $Main->KondisiBarang[$isi['kondisi']-1][1];
				$ISI15 = "";	
	
				if($isi['f']=="01" || $isi['f']=="02" || $isi['f']=="03" || $isi['f']=="04" || $isi['f']=="05"  ) {
					$KondisiKIB = "			
						where 
						a1= '{$isi['a1']}' and 
						a = '{$isi['a']}' and 
						b = '{$isi['b']}' and 
						c = '{$isi['c']}' and 
						d = '{$isi['d']}' and 
						e = '{$isi['e']}' and 
						f = '{$isi['f']}' and 
						g = '{$isi['g']}' and 
						h = '{$isi['h']}' and 
						i = '{$isi['i']}' and 
						j = '{$isi['j']}' and 
						noreg = '{$isi['noreg']}' and 
						tahun = '{$isi['tahun']}' ";
				}	
				if($isi['f']=="01"){//KIB A			
					//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'
					$QryKIB_A = mysql_query("select * from kib_a  $KondisiKIB limit 0,1");
					while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
						$ISI6 = "{$isiKIB_A['sertifikat_no']}";		//$ISI10 = "{$isiKIB_A['luas']}";
						$ISI15 = "{$isiKIB_B['ket']}";
					}
				}
				if($isi['f']=="02"){//KIB B;			
					//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
					$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
					while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
						$ISI5 = "{$isiKIB_B['merk']}";
						$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
						$ISI7 = "{$isiKIB_B['bahan']}";
						//$ISI10 = "{$isiKIB_B['ukuran']}";
						$ISI15 = "{$isiKIB_B['ket']}";
					}
				}
				if($isi['f']=="03"){//KIB C;
					$QryKIB_C = mysql_query("select * from kib_c  $KondisiKIB limit 0,1");
					while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
						$ISI6 = "{$isiKIB_C['dokumen_no']}";
						$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
						$ISI15 = "{$isiKIB_C['ket']}";
					}
				}
				if($isi['f']=="04"){//KIB D;
					$QryKIB_D = mysql_query("select * from kib_d  $KondisiKIB limit 0,1");
					while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
						$ISI6 = "{$isiKIB_D['dokumen_no']}";
						$ISI15 = "{$isiKIB_D['ket']}";
					}
				}
				if($isi['f']=="05"){//KIB E;		
					$QryKIB_E = mysql_query("select * from kib_e  $KondisiKIB limit 0,1");
					while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
						$ISI7 = "{$isiKIB_E['seni_bahan']}";
						$ISI15 = "{$isiKIB_E['ket']}";
					}
				}
				if($isi['f']=="06"){//KIB F;		
					$QryKIB_F = mysql_query("select * from kib_f  $KondisiKIB limit 0,1");
					while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
						$ISI6 = "{$isiKIB_F['dokumen_no']}";
						$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
						$ISI15 = "{$isiKIB_F['ket']}";
					}
				}
				$ISI5 = !empty($ISI5)?$ISI5:"-";
				$ISI6 = !empty($ISI6)?$ISI6:"-";
				$ISI7 = !empty($ISI7)?$ISI7:"-";
				$ISI10 = !empty($ISI10)?$ISI10:"-";
				$ISI12 = !empty($ISI12)?$ISI12:"-";
				$ISI15 = !empty($ISI15)?$ISI15:"-";
	
				//{$nmBarang['nm_barang']}		
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
	
				$cari->listdata .= "
					<tr class=\"$clRow\" id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."'  
					>
					<td class=\"$clGaris\" align=center>

						$no.						
					</td>

					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}							
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>
					<!--det-->
					<td class=\"$clGaris\">$ISI5</td>
					<td class=\"$clGaris\">$ISI6</td>
					<td class=\"$clGaris\">$ISI7</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[0][$AsalUsul]."</td>
					<td class=\"$clGaris\">$ISI10</td>
					<td class=\"$clGaris\">$ISI12</td>
					<td class=\"$clGaris\">$ISI15</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
        			</tr>
				";
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from buku_induk where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($jmlTotalHargaDisplay/1000), 2, '.', ''):number_format(($jmlTotalHargaDisplay), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
					<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
					<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
					<td colspan='".( $ViewStatus == TRUE? "11":"7" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":
				"<tr>
					<td colspan='".( $ViewStatus == TRUE? "14":"11" )."' align='center' height='50'>".Halaman($jmlData,$Main->PagePerHal,"HalDefault", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
				";
			break;
		}
		case '04':{
			$sqry = "select * from view_kib_a2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			//echo "qry=$sqry $LimitHalKIB_A<br> ";
			$Qry = mysql_query($sqry);
			$jmlDataKIB_A = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_A;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_A);
			$no= empty($all)? $Main->PagePerHal * (($HalKIB_A*1) - 1): 0;
			$cb=0;
			$jmlTampilKIB_A = 0;
			$JmlTotalHargaListKIB_A = 0;
			$ListBarangKIB_A = "";
			$cari->listdata = '';
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_A++;
				$JmlTotalHargaListKIB_A += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilKec =  empty($isi['alamat_kec'])? "": "<br>Kec. {$isi['alamat_kec']}";
				$tampilKel =  empty($isi['alamat_kel'])? "": "<br>Kel. {$isi['alamat_kel']}";	
				$tampilAlamat = 
					"<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}$tampilKel$tampilKec<br>{$isi['alamat_kota']}</td>";
	
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				//$tampilStatus = $ViewStatus != TRUE? "": "<td class=\"$clGaris\">tesstatus</td>";
				//$tampilStatus = Viewer_Cari_TampilStatus($isi, $cetak);
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>
					
					<td class=\"$clGaris\" align=center>
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
					$tampilHarga."
					</td>
			
					<td class=\"$clGaris\" align=right>{$isi['luas']}</td>			
					<!--<td class=\"$clGaris\">{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>-->
					$tampilAlamat
					<td class=\"$clGaris\">".$Main->StatusHakPakai[$isi['status_hak']-1][1]."</td>
					<td class=\"$clGaris\">".( $isi['sertifikat_tgl'] ==''||$isi['sertifikat_tgl']=='0000-00-00'? "":  TglInd($isi['sertifikat_tgl']) ) ."</td>
					<td class=\"$clGaris\">{$isi['sertifikat_no']}</td>
					<td class=\"$clGaris\">{$isi['penggunaan']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
			}


			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_a2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}

			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_A/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_A), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
					<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
					<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
					<td colspan='".( $ViewStatus == TRUE? "11":"8" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
				";
	
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":
				"<tr>
				<td colspan='".( $ViewStatus == TRUE? "17":"14" )."' align='center' height='50'>".Halaman($jmlDataKIB_A,$Main->PagePerHal,"HalKIB_A", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
				";
			break;
		}
		case '05':{
			$sqry = "select * from view_kib_b2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_B;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_B = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_B;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_B);
			$no=$Main->PagePerHal * (($HalKIB_B*1) - 1);
			$cb=0;
			$jmlTampilKIB_B = 0;
			$JmlTotalHargaListKIB_B = 0;
			$ListBarangKIB_B = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_B++;
				$JmlTotalHargaListKIB_B += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>			
					<td class=\"$clGaris\" align=center >						
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>						
					<td class=\"$clGaris\" >{$isi['merk']}</td>
					<td class=\"$clGaris\">{$isi['ukuran']}</td>
					<td class=\"$clGaris\" >{$isi['bahan']}</td>
					<td class=\"$clGaris\">{$isi['no_pabrik']}</td>
					<td class=\"$clGaris\">{$isi['no_rangka']}</td>
					<td class=\"$clGaris\">{$isi['no_mesin']}</td>
					<td class=\"$clGaris\">{$isi['no_polisi']}</td>
					<td class=\"$clGaris\">{$isi['no_bpkb']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_b2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_B/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_B), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "13":"10" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "18":"15" )."' align=center>".Halaman($jmlDataKIB_B,$Main->PagePerHal,"HalKIB_B","?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '06':{
			$sqry = "select * from view_kib_c2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";//echo "sqry=$sqry<br>";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_C;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_C = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_C;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_C);
			$no=$Main->PagePerHal * (($HalKIB_C*1) - 1);
			$cb=0;
			$jmlTampilKIB_C = 0;
			$JmlTotalHargaListKIB_C = 0;
			$ListBarangKIB_C = "";
			while ($isi = mysql_fetch_array($Qry)) {
				$jmlTampilKIB_C++;
				$JmlTotalHargaListKIB_C += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilKec =  empty($isi['alamat_kec'])? "": "<br>Kec. {$isi['alamat_kec']}";
				$tampilKel =  empty($isi['alamat_kel'])? "": "<br>Kel. {$isi['alamat_kel']}";	
				$tampilAlamat = 
					"<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}$tampilKel$tampilKec<br>{$isi['alamat_kota']}</td>";
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				//$tampilStatus = "";
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no.</td>
					
					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\">".$Main->kondisi_bangunan[$isi['kondisi_bangunan']-1][1]."</td>
					<td class=\"$clGaris\">".$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]."</td>
					<td class=\"$clGaris\">".$Main->Beton [$isi['konstruksi_beton']-1][1]."</td>
					<td class=\"$clGaris\" align=center>{$isi['luas_lantai']}</td>
					<!--<td class=\"$clGaris\" >{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>-->
					$tampilAlamat
					<td class=\"$clGaris\" align=center  style='width:75'>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\">{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\">".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" align=center >{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_c2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_C/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_C), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align='center' height='50'>".Halaman($jmlDataKIB_C,$Main->PagePerHal,"HalKIB_C", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '07':{
			$sqry = "select * from view_kib_d2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_D;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_D = mysql_num_rows($Qry);
			$jmlData =  $jmlDataKIB_D;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_D);
			$no=$Main->PagePerHal * (($HalKIB_D*1) - 1);
			$cb=0;
			$jmlTampilKIB_D = 0;
			$JmlTotalHargaListKIB_D = 0;
			$ListBarangKIB_D = "";
			while ($isi = mysql_fetch_array($Qry)) {
				$jmlTampilKIB_D++;
				$JmlTotalHargaListKIB_D += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				//$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no.</td>
					
					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']} /<br>".
					$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\" style='width:50'>{$isi['konstruksi']}</td>	
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['panjang']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['lebar']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>
					<td class=\"$clGaris\" align=center style='width:70'>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\">{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50'>".$Main->KondisiBarang[$isi['kondisi_bi']-1][1]."</td>
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_d2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_D/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_D), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align='center' height='50'>".Halaman($jmlDataKIB_D,$Main->PagePerHal,"HalKIB_D", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '08':{
			$sqry = "select * from view_kib_e2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			//echo "qry= $sqry $LimitHalKIB_E<br>";
			$Qry = mysql_query($sqry);
			$jmlDataKIB_E = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_E;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_E);
			$no=$Main->PagePerHal * (($HalKIB_E*1) - 1);
			$cb=0;
			$jmlTampilKIB_E = 0;
			$JmlTotalHargaListKIB_B = 0;
			$ListBarangKIB_E = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_E++;
				$JmlTotalHargaListKIB_E += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>
					<td class=\"$clGaris\" align=center >						
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}	
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
					$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\" style='width:50'>{$isi['buku_judul']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['buku_spesifikasi']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_asal_daerah']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_pencipta']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_bahan']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['hewan_jenis']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['hewan_ukuran']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['jml_barang']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_e2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_E/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_E), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "13":"10" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "17":"15" )."' align=center>".Halaman($jmlDataKIB_E,$Main->PagePerHal,"HalKIB_E", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '09':{
			$sqry = "select * from view_kib_f2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_F;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_F = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_F;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_F);
			$no=$Main->PagePerHal * (($HalKIB_F*1) - 1);
			$cb=0;
			$jmlTampilKIB_F = 0;
			$JmlTotalHargaListKIB_F = 0;
			$ListBarangKIB_F = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_F++;
				$JmlTotalHargaListKIB_F += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>			
					<td class=\"$clGaris\" align=center >						
							{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
							{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>						
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Bangunan[$isi['bangunan']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Beton[$isi['konstruksi_beton']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}</td>
					<td class=\"$clGaris\" style='width:80' align=center>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" style='width:80' align=center>".( $isi['tmt'] ==''||$isi['tmt']=='0000-00-00'? "":  TglInd($isi['tmt']))."</td>
					<td class=\"$clGaris\" style='width:50' align=center>".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>						
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_f2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_F/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_F), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":"
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align=center>tes".Halaman($jmlDataKIB_F,$Main->PagePerHal,"HalKIB_F", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		
	}
	
	
	
	//Header ----------------------------------------------------------
	$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga<br> Perolehan <br> (ribuan)': 'Harga<br> Perolehan';	
	switch ($SPg){
		case '03':{						
			//$tampilHeaderStatus = $ViewStatus != TRUE? "": ' <th class="th01"  style="width:60" >Stat</th>';
			$tampilHeaderStatus = $ViewStatus != TRUE? "": ' 
				<th class="th01"  style="width:10" >G</th>
				<th class="th01"  style="width:10" >S</th>
				<th class="th01"  style="width:10" >K</th>
				<th class="th01"  style="width:60" >Tgl. Sensus</th>
				<th class="th01"  style="width:60"  >Tgl. Update</th>'
				;

			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" width="40">No.</th>	
				<th class="th01" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" width="250">Bidang / OPD / Unit</th>
				<th class="th01" width="100">Tahun / '.$tampilHeaderHarga.' </th>
				<!--BI-->
				<th class="th01" width="100">Merk / Tipe</th>
				<th class="th01" width="100">No. Sertifikat /<br> No. Pabrik /<br> No. Chasis /<br> No. Mesin</th>
				<th class="th01" width="100">Bahan</th>
				<th class="th01" width="100">Asal Usul /<br> Cara Perolehan Barang</th>
				<th class="th01" width="100">Ukuran Barang /<br> Konstruksi<br> (P,SP,D)</th>
				<th class="th01" width="100">Keadaan <br>Barang<br> (B,KB,RB)</th> 
				<th class="th01" width="100">Keterangan</th> 
				'.$tampilHeaderStatus.'
				</tr>';
			break;
		}
		case '04':{		
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				' <th class="th01" rowspan="3"  style="width:10">G</th>
				<th class="th01"  rowspan="3" style="width:10" >S</th>
				<th class="th01" rowspan="3" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="3" >Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="3" >Tanggal Update</th>
				';	
			$cari->header = '	<!--header -->
				<tr>
				<th class="th01" rowspan="3" width="40">No.</th>	
				<th class="th01" rowspan="3" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="3" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="3" width="150">Tahun / '.$tampilHeaderHarga.'</th>			
				<th class="th01" rowspan="3" >Luas (M2)</th>
				<th class="th01" rowspan="3" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="3" >Status Tanah</th>
				<th class="th01" rowspan="3" >Penggunaan</th>
				<th class="th01" rowspan="3" >Asal-Usul</th> 
				<th class="th01" rowspan="3" width="100" >Keterangan</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr class="koptable">				
				<th class="th01" rowspan="2">Hak</th>
				<th class="th02" colspan="2">Sertifikat</th>
				</tr>
				<tr>
				<th class="th01" style="width:55">Tanggal</th>
				<th class="th01" style="width:75">Nomor</th>
				</tr>';		
			break;
		}
		case '05':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				' <th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';		
			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th01" rowspan="2" style="width:200">Merk/Type</th>
				<th class="th01" rowspan="2">Ukuran/CC</th>
				<th class="th01" rowspan="2">Bahan</th>				
				<th class="th02" colspan="5">N o m o r</th>
				<th class="th01" rowspan="2">Asal-Usul</th>				
				<th class="th01" rowspan="2">Keterangan</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr>
				<th class="th01">Pabrik</th>
				<th class="th01">Rangka</th>
				<th class="th01">Mesin</th>
				<th class="th01">Polisi</th>
				<th class="th01">BPKB</th>
				</tr>
				';
			break;
		}
		case '06':{
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';
			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--BI-->				
				<th class="th01" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
				<th class="th02" colspan="2">Konstruksi Bangunan</th>
				<th class="th01" rowspan="2">Luas Lantai (M2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen Gedung</th>
				<th class="th01" rowspan="2">Luas Tanah (M2)</th>
				<th class="th01" rowspan="2">Status Tanah</th>
				<th class="th01" rowspan="2" style="width:70">Nomor Kode Tanah</th>
				<th class="th01" rowspan="2">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr>
				<th class="th01">Bertingkat/ Tidak</th>
				<th class="th01">Beton/ Tidak</th>
				<th class="th01" >Tanggal</th>
				<th class="th01">Nomor</th>				
				</tr>					
				';
			break;
		}
		case '07':{		
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';	
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="250">Kode / No Reg <br> Nama Barang<br>Jenis</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--BI-->				
				<th class="th01" rowspan="2" style="width:50">Konstruksi</th>
				<th class="th01" rowspan="2" style="width:50">Panjang (km)</th>
				<th class="th01" rowspan="2" style="width:50">Lebar (M)</th>
				<th class="th01" rowspan="2" style="width:50">Luas  (M2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen</th>
				<th class="th01" rowspan="2">Status Tanah</th>
				<th class="th01" rowspan="2">Nomor Kode Tanah</th>
				<th class="th01" rowspan="2">Asal Usul</th>
				<th class="th01" rowspan="2" style="width:50">Kondisi (B,KB,RB)</th>
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:70">Tanggal</th>
				<th class="th01">Nomor</th>
			</tr>		
			';			
			break;
		}
		case '08':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';		
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th02" colspan="2">Buku Perpustakaan</th>
				<th class="th02" colspan="3">Barang Bercorak Kesenian / Kebudayaan</th>
				<th class="th02" colspan="2">Hewan Ternak</th>
				<th class="th01" rowspan="2" style="width:50">Jumlah</th>				
				<th class="th01" rowspan="2">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket.</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:100">Judul / Pencipta</th>
				<th class="th01" style="width:50">Spesifikasi</th>
				<th class="th01" style="width:50">Asal Daerah</th>
				<th class="th01" style="width:50">Pencipta</th>
				<th class="th01" style="width:50">Bahan</th>
				<th class="th01" style="width:50">Jenis</th>
				<th class="th01" style="width:50">Ukuran</th>
			</tr>
			';
			break;
		}
		case '09':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th01" rowspan="2" style="width:50">Bangunan(P,SP,D)</th>
				<th class="th02" colspan="2">Konstruksi Bangunan</th>
				<th class="th01" rowspan="2" style="width:50">Luas (m2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen</th>
				<th class="th01" rowspan="2" style="width:80">Tanggal Mulai</th>
				<th class="th01" rowspan="2" style="width:50">Status Tanah</th>
				<th class="th01" rowspan="2" style="width:50">Kode Tanah</th>
				<th class="th01" rowspan="2" style="width:50">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:50">Bertingkat/ Tidak</th>
				<th class="th01" style="width:50">Beton/ Tidak</th>
				<th class="th01" style="width:80">Tanggal</th>
				<th class="th01" style="width:50">Nomor</th>
			</tr>
			';				
			break;
		}
	}
	
	
}


function Viewer_Cari_GetList_XLS2($cetak = FALSE){
	global $cari, $Main, $Pg, $SPg, $addPageParam, $all;
	global $cbxDlmRibu;
	global $fmKEPEMILIKAN, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTahunPerolehan, $kode_barang, $nm_barang, $selStatusBrg;
	global $bersertifikat, $Kondisi;
	global $asc, $cbAscDsc, $selUrut, $Urutkan;
	global $OrderByKota, $ViewStatus, $FilterKotaKosong;
	global $jmlData, $jmlDataKIB_A, $jmlDataKIB_B, $jmlDataKIB_C, $jmlDataKIB_D, $jmlDataKIB_E, $jmlDataKIB_F;
	global $jmPerHal, $HalDefault, $HalKIB_A, $HalKIB_B, $HalKIB_C, $HalKIB_D, $HalKIB_E, $HalKIB_F;
	global $LimitHal, $LimitHalKIB_A, $LimitHalKIB_B, $LimitHalKIB_C, $LimitHalKIB_D, $LimitHalKIB_E, $LimitHalKIB_F;
	global $selHakPakai, $alamat, $selKabKota, $noSert, $keterangan;
	global $merk, $bahan, $noPabrik, $noRangka, $noMesin, $noPolisi, $noBPKB;
	global $konsTingkat, $konsBeton, $dokumen_no, $status_tanah, $kode_tanah;
	global $konstruksi;
	global $buku_judul, $buku_spesifikasi, $seni_asal_daerah, $seni_pencipta, $seni_bahan, $hewan_jenis, $hewan_ukuran;
	global $bangunan;
	
	//"?Pg=$Pg&SPg=$SPg$addPageParam"
	$maxdata=50;
	
	$clGaris = $cetak == TRUE? "GCTK" : "GarisDaftar";
	
	//Limit -----------------------------------------------------------
	if (empty($all)){	
	switch ($SPg){
		case '03':{
			$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
			break;
		}
		case '04':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_A = " limit ".(($HalKIB_A*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '05':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_B = " limit ".(($HalKIB_B*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '06':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_C = " limit ".(($HalKIB_C*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '07':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_D = " limit ".(($HalKIB_D*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '08':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_E = " limit ".(($HalKIB_E*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
		case '09':{
			$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			$LimitHalKIB_F = " limit ".(($HalKIB_F*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
			break;
		}
	}
	}else{
		$LimitHal = ""; $LimitHalKIB_A=""; $LimitHalKIB_C =""; $LimitHalKIB_D=""; $LimitHalKIB_E=""; $LimitHalKIB_F="";
	}
	
			
	//Kondisi -------------------------------------------------------
	//$Kondisi = 'a1="'.$fmKEPEMILIKAN.'" and a="'.$Main->Provinsi[0].'" and b="'.$fmWIL.'" ';
	$Kondisi = 'a1="'.$fmKEPEMILIKAN.'" ';
	$Kondisi .=  $fmSKPD ==''?'': ' and  c="'.$fmSKPD.'"';
	$Kondisi .=  $fmUNIT =='00'?'': ' and d="'.$fmUNIT.'"';
	$Kondisi .=  $fmSUBUNIT =='00'?'': ' and e="'.$fmSUBUNIT.'"';
	$Kondisi .=  $fmTahunPerolehan ==''?'': ' and thn_perolehan="'.$fmTahunPerolehan.'"';
	$Kondisi .=  $selKondisiBrg ==''?'': ' and kondisi="'.$selKondisiBrg.'"';
	$Kondisi .=  $kode_barang ==''?'': ' and concat(f,".",g,".",h,".",i,".",j) like "'.$kode_barang.'%"';
	$Kondisi .=  $nm_barang ==''?'': ' and nm_barang like "%'.$nm_barang.'%"';
	switch($selStatusBrg){
		case '' : $Kondisi .= ' and status_barang=1'; break;
		case '1': $Kondisi .= ''; break;
		default :{
			$Kondisi .= ' and status_barang = "'.$selStatusBrg.'"';	
		}
	}		
	switch ($SPg){
		case '03':{
			break;
		}
		case '04':{			
			$Kondisi .=  $selHakPakai ==''?'': ' and status_hak="'.$selHakPakai.'"';
			$Kondisi .=  $alamat == ''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota == ''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $noSert ==''?'': ' and sertifikat_no like "%'.$noSert.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';	
			//$Kondisi .=  $bersertifikat ==''?'': ' and bersertifikat ="'.$bersertifikat.'"';
			if ($bersertifikat !=''){
				switch ($bersertifikat){		
					case '1': $Kondisi .= ' and bersertifikat = "1" '; break;
					case '2': $Kondisi .= ' and bersertifikat = "2" '; break;
					case '3': $Kondisi .= ' and (bersertifikat = "" or bersertifikat is null) '; break;
				}	
			}		
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		}
		case '05':{
			$Kondisi .=  $merk ==''?'': ' and merk like "%'.$merk.'%"';
			$Kondisi .=  $bahan ==''?'': ' and bahan like "%'.$bahan.'%"';
			$Kondisi .=  $noPabrik ==''?'': ' and no_pabrik like "%'.$noPabrik.'%"';
			$Kondisi .=  $noRangka ==''?'': ' and no_rangka like "%'.$noRangka.'%"';
			$Kondisi .=  $noMesin ==''?'': ' and no_mesin like "%'.$noMesin.'%"';
			$Kondisi .=  $noPolisi ==''?'': ' and no_polisi like "%'.$noPolisi.'%"';
			$Kondisi .=  $noBPKB ==''?'': ' and no_bpkb like "%'.$noBPKB.'%"'; 
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			break; 
		}
		case '06':{						
			$Kondisi .=  $konsTingkat ==''?'': ' and konstruksi_tingkat="'.$konsTingkat.'"';
			$Kondisi .=  $konsBeton ==''?'': ' and konstruksi_beton="'.$konsBeton.'"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';			
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		} 
		case '07':{
			$Kondisi .=  $konstruksi ==''?'': ' and konstruksi like "%'.$konstruksi.'%"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break; 
		}
		case '08':{
			$Kondisi .=  $buku_judul ==''?'': ' and buku_judul like "%'.$buku_judul.'%"';
			$Kondisi .=  $buku_spesifikasi ==''?'': ' and buku_spesifikasi like "%'.$buku_spesifikasi.'%"';
			$Kondisi .=  $seni_asal_daerah ==''?'': ' and seni_asal_daerah like "%'.$seni_asal_daerah.'%"';
			$Kondisi .=  $seni_pencipta ==''?'': ' and seni_pencipta like "%'.$seni_pencipta.'%"';
			$Kondisi .=  $seni_bahan ==''?'': ' and seni_bahan like "%'.$seni_bahan.'%"';
			$Kondisi .=  $hewan_jenis ==''?'': ' and hewan_jenis like "%'.$hewan_jenis.'%"';
			$Kondisi .=  $hewan_ukuran ==''?'': ' and hewan_ukuran like "%'.$hewan_ukuran.'%"'; 
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			break;   
		}
		case '09':{			
			$Kondisi .=  $bangunan ==''?'': ' and bangunan = "'.$bangunan.'"';
			$Kondisi .=  $konsTingkat ==''?'': ' and konstruksi_tingkat="'.$konsTingkat.'"';
			$Kondisi .=  $konsBeton ==''?'': ' and konstruksi_beton="'.$konsBeton.'"';
			$Kondisi .=  $alamat ==''?'': ' and alamat like "%'.$alamat.'%"';
			$Kondisi .=  $selKabKota ==''?'': ' and alamat_a = "'.$Main->Provinsi[0].'" and alamat_b= "'.$selKabKota.'" ';
			$Kondisi .=  $dokumen_no ==''?'': ' and dokumen_no="'.$dokumen_no.'"';
			$Kondisi .=  $status_tanah ==''?'': ' and status_tanah="'.$status_tanah.'"';
			$Kondisi .=  $kode_tanah ==''?'': ' and kode_tanah like "%'.$kode_tanah.'%"';
			$Kondisi .=  $keterangan ==''?'': ' and ket like "%'.$keterangan.'%"';
			$Kondisi .= $FilterKotaKosong != TRUE? "": " and (alamat_a = '' || alamat_b='') ";
			break;
		}  
	}
	//$cari->cek .= ' kon='.$Kondisi;

	
	//Header ----------------------------------------------------------
	$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga<br> Perolehan <br> (ribuan)': 'Harga<br> Perolehan';	
	switch ($SPg){
		case '03':{						
			//$tampilHeaderStatus = $ViewStatus != TRUE? "": ' <th class="th01"  style="width:60" >Stat</th>';
			$tampilHeaderStatus = $ViewStatus != TRUE? "": ' 
				<th class="th01"  style="width:10" >G</th>
				<th class="th01"  style="width:10" >S</th>
				<th class="th01"  style="width:10" >K</th>
				<th class="th01"  style="width:60" >Tgl. Sensus</th>
				<th class="th01"  style="width:60"  >Tgl. Update</th>'
				;

			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" width="40">No.</th>	
				<th class="th01" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" width="250">Bidang / OPD / Unit</th>
				<th class="th01" width="100">Tahun / '.$tampilHeaderHarga.' </th>
				<!--BI-->
				<th class="th01" width="100">Merk / Tipe</th>
				<th class="th01" width="100">No. Sertifikat /<br> No. Pabrik /<br> No. Chasis /<br> No. Mesin</th>
				<th class="th01" width="100">Bahan</th>
				<th class="th01" width="100">Asal Usul /<br> Cara Perolehan Barang</th>
				<th class="th01" width="100">Ukuran Barang /<br> Konstruksi<br> (P,SP,D)</th>
				<th class="th01" width="100">Keadaan <br>Barang<br> (B,KB,RB)</th> 
				<th class="th01" width="100">Keterangan</th> 
				'.$tampilHeaderStatus.'
				</tr>';
			break;
		}
		case '04':{		
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				' <th class="th01" rowspan="3"  style="width:10">G</th>
				<th class="th01"  rowspan="3" style="width:10" >S</th>
				<th class="th01" rowspan="3" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="3" >Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="3" >Tanggal Update</th>
				';	
			$cari->header = '	<!--header -->
				<tr>
				<th class="th01" rowspan="3" width="40">No.</th>	
				<th class="th01" rowspan="3" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="3" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="3" width="150">Tahun / '.$tampilHeaderHarga.'</th>			
				<th class="th01" rowspan="3" >Luas (M2)</th>
				<th class="th01" rowspan="3" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="3" >Status Tanah</th>
				<th class="th01" rowspan="3" >Penggunaan</th>
				<th class="th01" rowspan="3" >Asal-Usul</th> 
				<th class="th01" rowspan="3" width="100" >Keterangan</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr class="koptable">				
				<th class="th01" rowspan="2">Hak</th>
				<th class="th02" colspan="2">Sertifikat</th>
				</tr>
				<tr>
				<th class="th01" style="width:55">Tanggal</th>
				<th class="th01" style="width:75">Nomor</th>
				</tr>';		
			break;
		}
		case '05':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				' <th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';		
			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th01" rowspan="2" style="width:200">Merk/Type</th>
				<th class="th01" rowspan="2">Ukuran/CC</th>
				<th class="th01" rowspan="2">Bahan</th>				
				<th class="th02" colspan="5">N o m o r</th>
				<th class="th01" rowspan="2">Asal-Usul</th>				
				<th class="th01" rowspan="2">Keterangan</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr>
				<th class="th01">Pabrik</th>
				<th class="th01">Rangka</th>
				<th class="th01">Mesin</th>
				<th class="th01">Polisi</th>
				<th class="th01">BPKB</th>
				</tr>
				';
			break;
		}
		case '06':{
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';
			$cari->header = '	
				<!--header -->
				<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--BI-->				
				<th class="th01" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
				<th class="th02" colspan="2">Konstruksi Bangunan</th>
				<th class="th01" rowspan="2">Luas Lantai (M2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen Gedung</th>
				<th class="th01" rowspan="2">Luas Tanah (M2)</th>
				<th class="th01" rowspan="2">Status Tanah</th>
				<th class="th01" rowspan="2" style="width:70">Nomor Kode Tanah</th>
				<th class="th01" rowspan="2">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
				</tr>
				<tr>
				<th class="th01">Bertingkat/ Tidak</th>
				<th class="th01">Beton/ Tidak</th>
				<th class="th01" >Tanggal</th>
				<th class="th01">Nomor</th>				
				</tr>					
				';
			break;
		}
		case '07':{		
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';	
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="250">Kode / No Reg <br> Nama Barang<br>Jenis</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--BI-->				
				<th class="th01" rowspan="2" style="width:50">Konstruksi</th>
				<th class="th01" rowspan="2" style="width:50">Panjang (km)</th>
				<th class="th01" rowspan="2" style="width:50">Lebar (M)</th>
				<th class="th01" rowspan="2" style="width:50">Luas  (M2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen</th>
				<th class="th01" rowspan="2">Status Tanah</th>
				<th class="th01" rowspan="2">Nomor Kode Tanah</th>
				<th class="th01" rowspan="2">Asal Usul</th>
				<th class="th01" rowspan="2" style="width:50">Kondisi (B,KB,RB)</th>
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:70">Tanggal</th>
				<th class="th01">Nomor</th>
			</tr>		
			';			
			break;
		}
		case '08':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';		
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th02" colspan="2">Buku Perpustakaan</th>
				<th class="th02" colspan="3">Barang Bercorak Kesenian / Kebudayaan</th>
				<th class="th02" colspan="2">Hewan Ternak</th>
				<th class="th01" rowspan="2" style="width:50">Jumlah</th>				
				<th class="th01" rowspan="2">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket.</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:100">Judul / Pencipta</th>
				<th class="th01" style="width:50">Spesifikasi</th>
				<th class="th01" style="width:50">Asal Daerah</th>
				<th class="th01" style="width:50">Pencipta</th>
				<th class="th01" style="width:50">Bahan</th>
				<th class="th01" style="width:50">Jenis</th>
				<th class="th01" style="width:50">Ukuran</th>
			</tr>
			';
			break;
		}
		case '09':{	
			$tampilHeaderStatus = $ViewStatus != TRUE? "": 
				'<th class="th01" rowspan="2" style="width:10">G</th>
				<th class="th01"  rowspan="2" style="width:10" >S</th>
				<th class="th01" rowspan="2" style="width:10" >K</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Sensus</th>
				<th class="th01"  style="width:60" rowspan="2">Tanggal Update</th>
				';
			$cari->header = '	
			<!--header -->
			<tr>
				<th class="th01" rowspan="2" width="40">No.</th>	
				<th class="th01" rowspan="2" width="100">Kode / No Reg <br> Nama Barang</th>
				<th class="th01" rowspan="2" width="250">Bidang / OPD / Unit</th>
				<th class="th01" rowspan="2" width="150">Tahun / '.$tampilHeaderHarga.'</th>
				<!--det-->	
				<th class="th01" rowspan="2" style="width:50">Bangunan(P,SP,D)</th>
				<th class="th02" colspan="2">Konstruksi Bangunan</th>
				<th class="th01" rowspan="2" style="width:50">Luas (m2)</th>
				<th class="th01" rowspan="2" style="width:150">Letak / Alamat</th>
				<th class="th02" colspan="2">Dokumen</th>
				<th class="th01" rowspan="2" style="width:80">Tanggal Mulai</th>
				<th class="th01" rowspan="2" style="width:50">Status Tanah</th>
				<th class="th01" rowspan="2" style="width:50">Kode Tanah</th>
				<th class="th01" rowspan="2" style="width:50">Asal Usul</th>				
				<th class="th01" rowspan="2">Ket</th>
				'.$tampilHeaderStatus.'
			</tr>
			<tr>
				<th class="th01" style="width:50">Bertingkat/ Tidak</th>
				<th class="th01" style="width:50">Beton/ Tidak</th>
				<th class="th01" style="width:80">Tanggal</th>
				<th class="th01" style="width:50">Nomor</th>
			</tr>
			';				
			break;
		}
	}

		
	//sorting --------------------------------------------------------
	$asc = !empty($cbAscDsc)? " desc ":"";
	if($selUrut !=''){	$Urutkan = $selUrut.' '.$asc.', ';}
	switch ($SPg){
		case '03':{
			break;
		}
		case '04':{			
			if($OrderByKota == TRUE){
				$Urutkan = empty($Urutkan)? "": "$Urutkan,";
				$Urutkan .= "alamat_kota, alamat_kec, alamat_kel,";
			}
			break;
		}
		case '05':{
			break;
		}
		case '06':{			
			if($OrderByKota == TRUE){
				$Urutkan = empty($Urutkan)? "": "$Urutkan,";
				$Urutkan .= "alamat_kota, alamat_kec, alamat_kel,";
			}
			break;
		}
	}
		
	//ListData --------------------------------------------------------	
	$cari->listdata = $cari->header."<tbody>";
	
	switch ($SPg){
		case '03':{
			$sqry = "select * from view_buku_induk2 where $Kondisi order by $Urutkan a,b,c,d,e,f,g,h,i,j,noreg";			
			//echo "qry=$sqry $LimitHal<br> ";
			$qry = mysql_query($sqry);
			$jmlData= mysql_num_rows($qry);
			$qry = mysql_query($sqry.' '.$LimitHal);
			$no= empty($all)? $Main->PagePerHal * (($HalDefault*1) - 1) : 0; //$cari->cek .=' no='.$no; 
			$cb=0;
			
			while($isi=mysql_fetch_array($qry)){
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				//$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$no++;
	
				$jmlTotalHargaDisplay += $isi['jml_harga'];
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$AsalUsul = $isi['asal_usul'];
				$ISI5 = "";
				$ISI6 = "";
				$ISI7 = "";
				$ISI10 = "";
				$ISI12 = $Main->KondisiBarang[$isi['kondisi']-1][1];
				$ISI15 = "";	
	
				if($isi['f']=="01" || $isi['f']=="02" || $isi['f']=="03" || $isi['f']=="04" || $isi['f']=="05"  ) {
					$KondisiKIB = "			
						where 
						a1= '{$isi['a1']}' and 
						a = '{$isi['a']}' and 
						b = '{$isi['b']}' and 
						c = '{$isi['c']}' and 
						d = '{$isi['d']}' and 
						e = '{$isi['e']}' and 
						f = '{$isi['f']}' and 
						g = '{$isi['g']}' and 
						h = '{$isi['h']}' and 
						i = '{$isi['i']}' and 
						j = '{$isi['j']}' and 
						noreg = '{$isi['noreg']}' and 
						tahun = '{$isi['tahun']}' ";
				}	
				if($isi['f']=="01"){//KIB A			
					//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'
					$QryKIB_A = mysql_query("select * from kib_a  $KondisiKIB limit 0,1");
					while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
						$ISI6 = "{$isiKIB_A['sertifikat_no']}";		//$ISI10 = "{$isiKIB_A['luas']}";
						$ISI15 = "{$isiKIB_B['ket']}";
					}
				}
				if($isi['f']=="02"){//KIB B;			
					//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
					$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
					while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
						$ISI5 = "{$isiKIB_B['merk']}";
						$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
						$ISI7 = "{$isiKIB_B['bahan']}";
						//$ISI10 = "{$isiKIB_B['ukuran']}";
						$ISI15 = "{$isiKIB_B['ket']}";
					}
				}
				if($isi['f']=="03"){//KIB C;
					$QryKIB_C = mysql_query("select * from kib_c  $KondisiKIB limit 0,1");
					while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
						$ISI6 = "{$isiKIB_C['dokumen_no']}";
						$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
						$ISI15 = "{$isiKIB_C['ket']}";
					}
				}
				if($isi['f']=="04"){//KIB D;
					$QryKIB_D = mysql_query("select * from kib_d  $KondisiKIB limit 0,1");
					while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
						$ISI6 = "{$isiKIB_D['dokumen_no']}";
						$ISI15 = "{$isiKIB_D['ket']}";
					}
				}
				if($isi['f']=="05"){//KIB E;		
					$QryKIB_E = mysql_query("select * from kib_e  $KondisiKIB limit 0,1");
					while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
						$ISI7 = "{$isiKIB_E['seni_bahan']}";
						$ISI15 = "{$isiKIB_E['ket']}";
					}
				}
				if($isi['f']=="06"){//KIB F;		
					$QryKIB_F = mysql_query("select * from kib_f  $KondisiKIB limit 0,1");
					while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
						$ISI6 = "{$isiKIB_F['dokumen_no']}";
						$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
						$ISI15 = "{$isiKIB_F['ket']}";
					}
				}
				$ISI5 = !empty($ISI5)?$ISI5:"-";
				$ISI6 = !empty($ISI6)?$ISI6:"-";
				$ISI7 = !empty($ISI7)?$ISI7:"-";
				$ISI10 = !empty($ISI10)?$ISI10:"-";
				$ISI12 = !empty($ISI12)?$ISI12:"-";
				$ISI15 = !empty($ISI15)?$ISI15:"-";
	
				//{$nmBarang['nm_barang']}		
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
	
				$cari->listdata .= "
					<tr class=\"$clRow\" id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."'  
					>
					<td class=\"$clGaris\" align=center>

						$no.						
					</td>

					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}							
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>
					<!--det-->
					<td class=\"$clGaris\">$ISI5</td>
					<td class=\"$clGaris\">$ISI6</td>
					<td class=\"$clGaris\">$ISI7</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[0][$AsalUsul]."</td>
					<td class=\"$clGaris\">$ISI10</td>
					<td class=\"$clGaris\">$ISI12</td>
					<td class=\"$clGaris\">$ISI15</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
        			</tr>
				";
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from buku_induk where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($jmlTotalHargaDisplay/1000), 2, '.', ''):number_format(($jmlTotalHargaDisplay), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
					<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
					<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
					<td colspan='".( $ViewStatus == TRUE? "11":"7" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":
				"<tr>
					<td colspan='".( $ViewStatus == TRUE? "14":"11" )."' align='center' height='50'>".Halaman($jmlData,$Main->PagePerHal,"HalDefault", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
				";
			break;
		}
		case '04':{
			$sqry = "select * from view_kib_a2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			//echo "qry=$sqry $LimitHalKIB_A<br> ";
			$Qry = mysql_query($sqry);
			$jmlDataKIB_A = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_A;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_A);
			$no= empty($all)? $Main->PagePerHal * (($HalKIB_A*1) - 1): 0;
			$cb=0;
			$jmlTampilKIB_A = 0;
			$JmlTotalHargaListKIB_A = 0;
			$ListBarangKIB_A = "";

			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_A++;
				$JmlTotalHargaListKIB_A += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilKec =  empty($isi['alamat_kec'])? "": "<br>Kec. {$isi['alamat_kec']}";
				$tampilKel =  empty($isi['alamat_kel'])? "": "<br>Kel. {$isi['alamat_kel']}";	
				$tampilAlamat = 
					"<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}$tampilKel$tampilKec<br>{$isi['alamat_kota']}</td>";
	
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				//$tampilStatus = $ViewStatus != TRUE? "": "<td class=\"$clGaris\">tesstatus</td>";
				//$tampilStatus = Viewer_Cari_TampilStatus($isi, $cetak);
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>
					
					<td class=\"$clGaris\" align=center>
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
					$tampilHarga."
					</td>
			
					<td class=\"$clGaris\" align=right>{$isi['luas']}</td>			
					<!--<td class=\"$clGaris\">{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>-->
					$tampilAlamat
					<td class=\"$clGaris\">".$Main->StatusHakPakai[$isi['status_hak']-1][1]."</td>
					<td class=\"$clGaris\">".( $isi['sertifikat_tgl'] ==''||$isi['sertifikat_tgl']=='0000-00-00'? "":  TglInd($isi['sertifikat_tgl']) ) ."</td>
					<td class=\"$clGaris\">{$isi['sertifikat_no']}</td>
					<td class=\"$clGaris\">{$isi['penggunaan']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
				
			}


			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_a2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}

			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_A/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_A), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
					<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
					<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
					<td colspan='".( $ViewStatus == TRUE? "11":"8" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
				";
	
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":
				"<tr>
				<td colspan='".( $ViewStatus == TRUE? "17":"14" )."' align='center' height='50'>".Halaman($jmlDataKIB_A,$Main->PagePerHal,"HalKIB_A", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
				";
			break;
		}
		case '05':{
			$sqry = "select * from view_kib_b2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_B;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_B = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_B;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_B);
			$no=$Main->PagePerHal * (($HalKIB_B*1) - 1);
			$cb=0;
			$jmlTampilKIB_B = 0;
			$JmlTotalHargaListKIB_B = 0;
			$ListBarangKIB_B = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_B++;
				$JmlTotalHargaListKIB_B += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>			
					<td class=\"$clGaris\" align=center >						
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>						
					<td class=\"$clGaris\" >{$isi['merk']}</td>
					<td class=\"$clGaris\">{$isi['ukuran']}</td>
					<td class=\"$clGaris\" >{$isi['bahan']}</td>
					<td class=\"$clGaris\">{$isi['no_pabrik']}</td>
					<td class=\"$clGaris\">{$isi['no_rangka']}</td>
					<td class=\"$clGaris\">{$isi['no_mesin']}</td>
					<td class=\"$clGaris\">{$isi['no_polisi']}</td>
					<td class=\"$clGaris\">{$isi['no_bpkb']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
				
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_b2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_B/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_B), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "13":"10" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "18":"15" )."' align=center>".Halaman($jmlDataKIB_B,$Main->PagePerHal,"HalKIB_B","?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '06':{
			$sqry = "select * from view_kib_c2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";//echo "sqry=$sqry<br>";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_C;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_C = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_C;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_C);
			$no=$Main->PagePerHal * (($HalKIB_C*1) - 1);
			$cb=0;
			$jmlTampilKIB_C = 0;
			$JmlTotalHargaListKIB_C = 0;
			$ListBarangKIB_C = "";
			while ($isi = mysql_fetch_array($Qry)) {
				$jmlTampilKIB_C++;
				$JmlTotalHargaListKIB_C += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilKec =  empty($isi['alamat_kec'])? "": "<br>Kec. {$isi['alamat_kec']}";
				$tampilKel =  empty($isi['alamat_kel'])? "": "<br>Kel. {$isi['alamat_kel']}";	
				$tampilAlamat = 
					"<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}$tampilKel$tampilKec<br>{$isi['alamat_kota']}</td>";
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				//$tampilStatus = "";
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no.</td>
					
					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\">".$Main->kondisi_bangunan[$isi['kondisi_bangunan']-1][1]."</td>
					<td class=\"$clGaris\">".$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]."</td>
					<td class=\"$clGaris\">".$Main->Beton [$isi['konstruksi_beton']-1][1]."</td>
					<td class=\"$clGaris\" align=center>{$isi['luas_lantai']}</td>
					<!--<td class=\"$clGaris\" >{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>-->
					$tampilAlamat
					<td class=\"$clGaris\" align=center  style='width:75'>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\">{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\">".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" align=center >{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
				
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_c2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_C/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_C), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align='center' height='50'>".Halaman($jmlDataKIB_C,$Main->PagePerHal,"HalKIB_C", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '07':{
			$sqry = "select * from view_kib_d2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_D;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_D = mysql_num_rows($Qry);
			$jmlData =  $jmlDataKIB_D;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_D);
			$no=$Main->PagePerHal * (($HalKIB_D*1) - 1);
			$cb=0;
			$jmlTampilKIB_D = 0;
			$JmlTotalHargaListKIB_D = 0;
			$ListBarangKIB_D = "";
			while ($isi = mysql_fetch_array($Qry)) {
				$jmlTampilKIB_D++;
				$JmlTotalHargaListKIB_D += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				$kdKelBarang = $isi['f'].$isi['g']."00";
				//$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no.</td>
					
					<td class=\"$clGaris\" align=center >
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']} /<br>".
					$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\" style='width:50'>{$isi['konstruksi']}</td>	
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['panjang']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['lebar']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}<br>{$nmkota['nm_wilayah']}</td>
					<td class=\"$clGaris\" align=center style='width:70'>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\">{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50'>".$Main->KondisiBarang[$isi['kondisi_bi']-1][1]."</td>
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_d2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_D/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_D), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align='center' height='50'>".Halaman($jmlDataKIB_D,$Main->PagePerHal,"HalKIB_D", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '08':{
			$sqry = "select * from view_kib_e2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			//echo "qry= $sqry $LimitHalKIB_E<br>";
			$Qry = mysql_query($sqry);
			$jmlDataKIB_E = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_E;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_E);
			$no=$Main->PagePerHal * (($HalKIB_E*1) - 1);
			$cb=0;
			$jmlTampilKIB_E = 0;
			$JmlTotalHargaListKIB_B = 0;
			$ListBarangKIB_E = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_E++;
				$JmlTotalHargaListKIB_E += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";	
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>
					<td class=\"$clGaris\" align=center >						
						{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
						{$isi['nm_barang']}	
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
					$tampilHarga."
					</td>
					<!--det-->			
					<td class=\"$clGaris\" style='width:50'>{$isi['buku_judul']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['buku_spesifikasi']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_asal_daerah']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_pencipta']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['seni_bahan']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['hewan_jenis']}</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['hewan_ukuran']}</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['jml_barang']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>			
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
				
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_e2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_E/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_E), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "13":"10" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "": "
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "17":"15" )."' align=center>".Halaman($jmlDataKIB_E,$Main->PagePerHal,"HalKIB_E", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		case '09':{
			$sqry = "select * from view_kib_f2 where $Kondisi order by $Urutkan a1,a,b,c,d,e,f,g,h,i,j,noreg ";
			$cari->cek .= '<br> qry='.$sqry.' '.$LimitHalKIB_F;
			$Qry = mysql_query($sqry);
			$jmlDataKIB_F = mysql_num_rows($Qry);
			$jmlData = $jmlDataKIB_F;
			$Qry = mysql_query($sqry."  ".$LimitHalKIB_F);
			$no=$Main->PagePerHal * (($HalKIB_F*1) - 1);
			$cb=0;
			$jmlTampilKIB_F = 0;
			$JmlTotalHargaListKIB_F = 0;
			$ListBarangKIB_F = "";
			while ($isi = mysql_fetch_array($Qry)){
				$jmlTampilKIB_F++;
				$JmlTotalHargaListKIB_F += $isi['jml_harga'];
				$no++;
				$clRow = $no % 2 == 0 ?"row1":"row0";
				$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
				//$kdKelBarang = $isi['f'].$isi['g']."00";
				$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
				//$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
				$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$isi['alamat_a']."' and b='".$isi['alamat_b']."' "));
				//<A target=_self class='abarang' HREF=\"javascript:getdat2('".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg']."')\" title='Klik untuk lihat detail {$nmBarang['nm_barang']}'>
				$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');		
				$cari->listdata .= "	
					<tr class='$clRow' id='".$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'] .$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']."' >
					<td class=\"$clGaris\" align=center>$no</td>			
					<td class=\"$clGaris\" align=center >						
							{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>
							{$isi['nm_barang']}				
					</td>						
					<td class=\"$clGaris\">{$isi['nmbidang']}<br> / {$isi['nmopd']}<br> / {$isi['nmunit']}</td>
					<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}<br> / ".
						$tampilHarga."
					</td>						
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Bangunan[$isi['bangunan']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Tingkat[$isi['konstruksi_tingkat']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=left>".$Main->Beton[$isi['konstruksi_beton']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['luas']}</td>
					<td class=\"$clGaris\" style='width:100'>{$isi['alamat']}</td>
					<td class=\"$clGaris\" style='width:80' align=center>".( $isi['dokumen_tgl'] ==''||$isi['dokumen_tgl']=='0000-00-00'? "": TglInd($isi['dokumen_tgl']) )."</td>
					<td class=\"$clGaris\" style='width:50'>{$isi['dokumen_no']}</td>
					<td class=\"$clGaris\" style='width:80' align=center>".( $isi['tmt'] ==''||$isi['tmt']=='0000-00-00'? "":  TglInd($isi['tmt']))."</td>
					<td class=\"$clGaris\" style='width:50' align=center>".$Main->StatusTanah[$isi['status_tanah']-1][1]."</td>
					<td class=\"$clGaris\" style='width:50' align=center>{$isi['kode_tanah']}</td>
					<td class=\"$clGaris\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>						
					<td class=\"$clGaris\">{$isi['ket']}</td>
					".Viewer_Cari_TampilStatus_XLS($isi, $cetak)."
					</tr>
				";				
				$cb++;
				if (($cb % $maxrow ==1) & ($cb != 1) )
				{
				echo $cari->listdata;
				ob_flush();
				flush();
				$cari->listdata='';	
				}
				
			}
			//-------- cari total harga ---------/
			$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_f2 where $Kondisi ");
			if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga)){
				$jmlTotalHarga = $jmlTotalHarga[0];
			}else{
				$jmlTotalHarga=0;
			}
			$tampilTotalHal =  !empty($cbxDlmRibu)? number_format(($JmlTotalHargaListKIB_F/1000), 2, '.', ''):number_format(($JmlTotalHargaListKIB_F), 2, '.', '');
			$tampilTotal = !empty($cbxDlmRibu)? number_format(($jmlTotalHarga/1000), 2, '.', ''): number_format(($jmlTotalHarga), 2, '.', '');
			$cari->totalharga = "
				<tr class='row0'>
				<td class=\"$clGaris\" colspan=3 >Total Harga Seluruhnya ".(!empty($cbxDlmRibu)? " (Ribuan)":"" )."</td>
				<td class=\"$clGaris\" align=right><b>".$tampilTotal."</td>
				<td colspan='".( $ViewStatus == TRUE? "15":"12" )."' class=\"$clGaris\">&nbsp;</td>
				</tr>
			";
			//------- tampil halaman ---------
			$cari->footer = $cetak == TRUE? "":"
				<tr>
				<td colspan='".( $ViewStatus == TRUE? "19":"16" )."' align=center>tes".Halaman($jmlDataKIB_F,$Main->PagePerHal,"HalKIB_F", "?Pg=$Pg&SPg=$SPg$addPageParam")."</td>
				</tr>
			";
			break;
		}
		
	}
	
	
	
	
}


function TglInd_1($Tgl="") { 
	$Tanggal='';
	if (!empty($Tgl)){
	$Tgl_Jam=explode(' ',$Tgl);
	$exp = explode('-',$Tgl_Jam[0]);
	if(count($exp) == 3) {
		$Tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];
	}
	}
	if (strpos($Tanggal,'00-00-0000')===TRUE){
		$Tanggal='';
	}	
	return $Tanggal;
}
	 
function TglJamInd_1($Tgl="") { 
	$Tanggal='';
	if (!empty($Tgl)){
	$Tgl_Jam=explode(' ',$Tgl);
	$exp = explode('-',$Tgl_Jam[0]);
	if(count($exp) == 3) {
		$Tanggal = $exp[2].'-'.$exp[1].'-'.$exp[0];
	}
	$Tanggal .= '<br>'.$Tgl_Jam[1];
	}
	return $Tanggal;
} 

function Viewer_Cari_TampilStatus_XLS($isi, $cetak = TRUE){
	global $ViewStatus ,$SPg;
	$clGaris = $cetak == TRUE? "GCTK" : "GarisDaftar";
	
	//if ($ViewStatus == 1){
	if ($ViewStatus ){	
		//gambar
		$stGambar= $isi['gambar'] ==""? "":"1";	
		$stUpdate= TglJamInd_1($isi['tgl_update']);
		$stsensus= TglInd_1($isi['tgl_sensus']);
		
		//bersertifikat
		switch ($SPg) {
			case '04':{			
				$stSertifikat .= $isi['bersertifikat'] == 1? " 1":"";
				break;
			}
			default:{
				$stSertifikat .= "";// 0";
			}
		}	
		//koordinat
		switch ($SPg) {
			case '04'; case '06'; case '07';
			case '09':{						
				$stKoordinat .= $isi['koordinat_gps'] == ""? "":" 1";
				break;
			}
			default:{
				$stKoordinat .= "";
			}
		}
		$tampil =  
			"<td class=\"$clGaris\">$stGambar</td>
			<td class=\"$clGaris\">$stSertifikat</td>
			<td class=\"$clGaris\">$stKoordinat</td>
			<td class=\"$clGaris\">$stsensus</td>
			<td class=\"$clGaris\">$stUpdate</td>
			";
	}else{
		$tampil = "";
	}
	
	//$tampil =  $ViewStatus != 1? "": "<td class=\"$clGaris\">$status</td>";
	return $tampil;
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
			mm.jmlHrgHPS_Aman_akhir ".		
		"from ref_barang aa 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_awal, sum(jml_harga ) as jmlHrgHPS_awal from v_penghapusan_bi where $Kondisi and tgl_penghapusan < '$tglAwal' group by f,g with rollup ) bb on aa.f=bb.f and aa.g=bb.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_awal, sum(biaya_pemeliharaan ) as jmlHrgPLH_awal from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan < '$tglAwal' group by f,g with rollup ) cc on aa.f=cc.f and aa.g=cc.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_awal, sum(biaya_pengamanan ) as jmlHrgAman_awal from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan < '$tglAwal' group by f,g with rollup ) dd on aa.f=dd.f and aa.g=dd.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_awal, sum(jml_harga ) as jmlHrgBI_awal from buku_induk where $Kondisi and tgl_buku < '$tglAwal'  group by f,g with rollup ) ee on aa.f=ee.f and aa.g=ee.g 
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgHPS_akhir, sum(jml_harga ) as jmlHrgHPS_akhir from v_penghapusan_bi where $Kondisi and tgl_penghapusan <= '$tglAkhir' group by f,g with rollup ) ff on aa.f=ff.f and aa.g=ff.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlPLH_akhir, sum(biaya_pemeliharaan ) as jmlHrgPLH_akhir from v_pemelihara where $Kondisi and tambah_aset=1 and tgl_pemeliharaan <= '$tglAkhir' group by f,g with rollup ) gg on aa.f=gg.f and aa.g=gg.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, count(*) as jmlAman_akhir, sum(biaya_pengamanan ) as jmlHrgAman_akhir from v_pengaman where $Kondisi and tambah_aset=1 and tgl_pengamanan <= '$tglAkhir' group by f,g with rollup ) hh on aa.f=hh.f and aa.g=hh.g 
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(jml_barang) as jmlBrgBI_akhir, sum(jml_harga ) as jmlHrgBI_akhir from buku_induk  where $Kondisi and tgl_buku <= '$tglAkhir'  group by f,g with rollup ) ii on aa.f=ii.f and aa.g=ii.g 
			
			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_awal from v2_penghapusan_pelihara where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) jj on aa.f=jj.f  and aa.g=jj.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_awal from v2_penghapusan_pengaman where  tgl_penghapusan < '$tglAwal' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) kk on aa.f=kk.f  and aa.g=kk.g 			
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pemeliharaan ) as jmlHrgHPS_PLH_akhir from v2_penghapusan_pelihara where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) ll on aa.f=ll.f  and aa.g=ll.g  
			left join (select IFNULL(f,'00') as f , IFNULL(g,'00') as g, sum(biaya_pengamanan ) as jmlHrgHPS_Aman_akhir from v2_penghapusan_pengaman where  tgl_penghapusan <= '$tglAkhir' and tambah_aset=1 $KondisiKIB group by f,g with rollup ) mm on aa.f=mm.f  and aa.g=mm.g 
			
			
			".			
		" where aa.h='00'  
		order by aa.f, aa.g		
	"; // echo "$sqry";
	// $cek.=$sqry;
    $QryRefBarang = mysql_query($sqry);    
    $jmlData = mysql_num_rows($QryRefBarang); //$totalHarga = 0; $totalBrg =0;    
	$no=0;
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
        $tampil_jmlHrg_awal = $dlmRibuan == TRUE ? number_format(($jmlHrg_awal / 1000), 2, '.', '') : number_format($jmlHrg_awal, 2, '.', '');
        
        $tampil_jmlHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambah_curr / 1000), 2, '.', '') : number_format($jmlHrgTambah_curr, 2, '.', '');
        $tampil_jmlHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahPLH_curr / 1000), 2, '.', '') : number_format($jmlHrgTambahPLH_curr, 2, '.', '');
        $tampil_jmlHrgAman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahAman_curr / 1000), 2, '.', '') : number_format($jmlHrgTambahAman_curr, 2, '.', '');
        $tampil_jmlHrgBI_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgTambahBi_curr / 1000), 2, '.', '') : number_format($jmlHrgTambahBi_curr, 2, '.', '');
        
		$tampil_jmlHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurang_curr / 1000), 2, '.', '') : number_format($jmlHrgKurang_curr, 2, '.', '');
		$tampil_jmlHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangBi_curr / 1000), 2, '.', '') : number_format($jmlHrgKurangBi_curr, 2, '.', '');
		$tampil_jmlHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangPLH_curr / 1000), 2, '.', '') : number_format($jmlHrgKurangPLH_curr, 2, '.', '');
		$tampil_jmlHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgKurangAman_curr / 1000), 2, '.', '') : number_format($jmlHrgKurangAman_curr, 2, '.', '');
        
		$tampil_jmlHrg_akhir = $dlmRibuan == TRUE ? number_format(($jmlHrg_akhir / 1000), 2, '.', '') : number_format($jmlHrg_akhir, 2, '.', '');
		
		//$tampil_jmlHrgMut_PLH_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_PLH_curr / 1000), 2, '.', '') : number_format($jmlHrgMut_PLH_curr, 2, '.', '');
		//$tampil_jmlHrgMut_Aman_curr = $dlmRibuan == TRUE ? number_format(($jmlHrgMut_Aman_curr / 1000), 2, '.', '') : number_format($jmlHrgMut_Aman_curr, 2, '.', '');
        
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
		
        $tampil_jmlHrgTambah_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgTambah_curr</div></td>";
        $tampil_jmlHrgPLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgPLH_curr</div></td>";
        $tampil_jmlHrgAman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgAman_curr</div></td>";
        $tampil_jmlHrgBI_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgBI_curr</div></td>";
		
		$tampil_jmlHrgKurang_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgKurang_curr</div></td>";
		$tampil_jmlHrgHPS_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgHPS_curr</div></td>";
		$tampil_jmlHrgHPS_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgHPS_PLH_curr</div></td>";
        $tampil_jmlHrgHPS_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgHPS_Aman_curr</div></td>";
		$tampil_jmlHrgMut_PLH_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgMut_PLH_curr</div></td>";
		$tampil_jmlHrgMut_Aman_curr = "<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt4\">$tampil_jmlHrgMut_Aman_curr</div></td>";
        

        switch ($Style) {
            case 1: {
                    //$tampil_jmlHrgTambah_curr =" $tampil_jmlHrgTambah_curr	";
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_awal</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_awal</div></td>
								
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrgHPS_curr</div></td>
					$tampil_jmlHrgKurang_curr			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrgTambah_curr</div></td>
					$tampil_jmlHrgTambah_curr										
					
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_akhir</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_akhir</div></td>
					$tampilKet
					
				";
                    break;
                }
            case 2: {
                    $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                    $TampilStyle = "
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_awal</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_awal</div></td>			
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrgHPS_curr</div></td>										
					$tampil_jmlHrgHPS_PLH_curr
					$tampil_jmlHrgHPS_Aman_curr		
					$tampil_jmlHrgHPS_curr	
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrgTambah_curr</div></td>
					$tampil_jmlHrgPLH_curr
					$tampil_jmlHrgAman_curr
					$tampil_jmlHrgBI_curr										
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[4]\"><div class=\"nfmt1\">$tampil_jmlBrg_akhir</div></td>
					<td class=\"$clGaris\" align=right width=\"$kolomwidth[5]\"><div class=\"nfmt4\">$tampil_jmlHrg_akhir</div></td>
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
		
	
    $tampil_totHrg_awal = $dlmRibuan == TRUE ? number_format(($totHrg_awal / 1000), 2, '.', '') : number_format($totHrg_awal, 2, '.', '');
    $tampil_totHrgHPS_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_curr / 1000), 2, '.', '') : number_format($totHrgHPS_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', '') : number_format($totHrg_akhir, 2, '.', '');
    $tampil_totHrgTambah_curr = $dlmRibuan == TRUE ? number_format(($totHrgTambah_curr / 1000), 2, '.', '') : number_format($totHrgTambah_curr, 2, '.', '');
    $tampil_totHrgPLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgPLH_curr / 1000), 2, '.', '') : number_format($totHrgPLH_curr, 2, '.', '');
    $tampil_totHrgAman_curr = $dlmRibuan == TRUE ? number_format(($totHrgAman_curr / 1000), 2, '.', '') : number_format($totHrgAman_curr, 2, '.', '');
    $tampil_totHrgBI_curr = $dlmRibuan == TRUE ? number_format(($totHrgBI_curr / 1000), 2, '.', '') : number_format($totHrgBI_curr, 2, '.', '');
    $tampil_totHrg_akhir = $dlmRibuan == TRUE ? number_format(($totHrg_akhir / 1000), 2, '.', '') : number_format($totHrg_akhir, 2, '.', '');
	$tampil_totHrgKurang_curr = $dlmRibuan == TRUE ? number_format(($totHrgKurang_curr / 1000), 2, '.', '') : number_format($totHrgKurang_curr, 2, '.', '');
	$tampil_totHrgHPS_PLH_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_PLH_curr / 1000), 2, '.', '') : number_format($totHrgHPS_PLH_curr, 2, '.', '');
	$tampil_totHrgHPS_Aman_curr = $dlmRibuan == TRUE ? number_format(($totHrgHPS_Aman_curr / 1000), 2, '.', '') : number_format($totHrgHPS_Aman_curr, 2, '.', '');
    //bold
    $tampil_totHrgTambah_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgTambah_curr . "</div></td>";
    $tampil_totHrgPLH_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgPLH_curr . "</div></td>";
    $tampil_totHrgAman_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgAman_curr . "</div></td>";
    $tampil_totHrgBI_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgBI_curr . "</div></td>";
	
	$tampil_totHrgKurang_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgKurang_curr . "</div></td>";
	$tampil_totHrgHPS_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgHPS_curr . "</div></td>";
	$tampil_totHrgHPS_PLH_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgHPS_PLH_curr . "</div></td>";
	$tampil_totHrgHPS_Aman_curr = "<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrgHPS_Aman_curr . "</div></td>";
    switch ($Style) {
        case 1: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_awal . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</div></td>
				$tampil_totHrgKurang_curr
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</div></td>
				$tampil_totHrgTambah_curr		
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_akhir . "</div></td>
				$tampilKet
				<!--<td class=\"$clGaris\">&nbsp;</td>-->
				";
                break;
            }
        case 2: {
                $tampilKet = $cetak ? "<td class=\"$clGaris\">&nbsp;</td>" : '';
                $TampilStyleTot = "
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_awal), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_awal . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrgHPS_curr), 0, '.', '') . "</div></td>
				$tampil_totHrgHPS_PLH_curr
				$tampil_totHrgHPS_Aman_curr
				$tampil_totHrgHPS_curr
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrgTambah_curr), 0, '.', '') . "</div></td>
				$tampil_totHrgPLH_curr
				$tampil_totHrgAman_curr
				$tampil_totHrgBI_curr
				<td align=right class=\"$clGaris\"><div class=\"nfmt1\"><b>" . number_format(($totBrg_akhir), 0, '.', '') . "</div></td>
				<td align=right class=\"$clGaris\"><div class=\"nfmt4\"><b>" . $tampil_totHrg_akhir . "</td>
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

?>