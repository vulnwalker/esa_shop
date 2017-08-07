<?php
$cbxDlmRibu = cekPOST("cbxDlmRibu");
$fmTahun = cekPOST('fmTahun',date('Y'));

$view->isi = file_get_contents('viewer/viewer.html');
//menu tab ----------------------------------------------------
/*$menuTab = '
	<div id="menu">
	<ul>
	<li>
		<div id="<!--menu2-->">	
		<a href="viewer.php?Pg=cari'.$addPageParam.'" target="_self" title="Cari Data"><span>Cari Data</span></a>
		</div>	
	</li>
	<li>
		<div id="<!--menu1-->">		
	   	<a  href="viewer.php?Pg=rekap'.$addPageParam.'" target="_self" title="Rekapitulasi Aset">								
			<span> Rekapitulasi Aset</span>
		</a>
		</div >
	</li>
   	</ul>
	</div>
	';*/
$menuTab = '
	<div id="menu">
	<ul>
	<li>
		<div id="<!--menu1-->">		
	   	<a  href="viewer.php?Pg=rekap'.$addPageParam.'" target="_self" title="Rekapitulasi Aset">								
			<span> Rekapitulasi Aset</span>
		</a>
		</div >
	</li>
	<li>
		<div id="<!--menu2-->">		
	   	<a  href="viewer.php?Pg=banding'.$addPageParam.'" target="_self" title="Perbandingan">								
			<span> Perbandingan</span>
		</a>
		</div >
	</li>
   	</ul>
	</div>
	';

// menu bar atas ---------------------------------------------
$menu_kibg = 
	$Main->MODUL_ASET_LAINNYA?
	'<A href="?Pg=rekap&SPg=kibg'.$addPageParam.'" title="Aset Lainnya" class="stLinkWithIcon" style="">KIB G</a>  
			&nbsp;&nbsp;&nbsp;'
	: '';
$menuBar = 
	'<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
			<tr>
			<td class="menudottedline" height="30" style="text-align:right;vertical-align:middle;padding:4 0 0 0"><B>
			<A href="?Pg=rekap&SPg=03'.$addPageParam.'" title="Buku Inventaris" class="stLinkWithIcon" style="">	BI	</a>
 			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=04'.$addPageParam.'" title="Tanah" class="stLinkWithIcon" style="">KIB A</a>  
			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=05'.$addPageParam.'" title="Peralatan & Mesin" class="stLinkWithIcon" style="">KIB B</a>  
			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=06'.$addPageParam.'" title="Gedung & Bangunan" class="stLinkWithIcon" style="">KIB C</a>  
			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=07'.$addPageParam.'" title="Jalan, Irigasi & Jaringan" class="stLinkWithIcon" style="">KIB D</a>  
			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=08'.$addPageParam.'" title="Aset Tetap Lainnya" class="stLinkWithIcon" style="">KIB E</a>  
			&nbsp;&nbsp;&nbsp;
			<A href="?Pg=rekap&SPg=09'.$addPageParam.'" title="Konstruksi Dalam Pengerjaan" class="stLinkWithIcon" style="">KIB F</a>  
			&nbsp;&nbsp;&nbsp;'.
			$menu_kibg.
			'<A href="?Pg=rekap&SPg=10'.$addPageParam.'" title="Semua" class="stLinkWithIcon" style="">Semua</a>  
			</b>
			&nbsp;&nbsp;
	</td></tr></table><br>';
$view->isi = str_replace('<!--menuTab-->',$menuTab,$view->isi);

//halaman --------------------------------------------------------
$jmPerHal = cekPOST("jmPerHal");
$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal; //$cek = '<br> jmlbrs ='.$Main->PagePerHal.' - '.$jmlPerHal;
$view->BarisPerHalaman = " Baris per halaman <input type=text name='jmPerHal' size=4 value='".$Main->PagePerHal."'>
						<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg';
							adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>";
							
//option cari/filter --------------------------------------------------
$dalamRibuan = " <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu'> Dalam Ribuan ";
$tampilTahun = "<div style='float:left;padding:0 8 0 0;'> Tahun &nbsp <input type=text name='fmTahun' id='fmTahun' size=4 value='$fmTahun'></div>";
$optTampil = '<div style="float:right;padding:0 4 0 8;border-left-color: #E5E5E5;border-left-style: solid;border-left-width: 1px;"> '. 
				$view->BarisPerHalaman.
			'</div>'.
			'<div style="float:right;padding:0 4 0 8;border-left:1px solid #E5E5E5;"> '. 
				$dalamRibuan.'&nbsp'.
			'</div>'.
			'<div style="float:right;padding:0 4 0 8;border-left:1px solid #E5E5E5;"> '. 
			$tampilTahun.
			'</div>';
			
//header table --------------------------------------------------
if ($SPg != 10){
	$tampilHeaderHarga= !empty($cbxDlmRibu)? 'Total Harga (Ribuan)': 'Total Harga';
	$tabelHeader = '
		<tr>	
		<th class="th01" width="30" >No.</th>	
		<th class="th01"  colspan=3 >Uraian</th>
		<th class="th01"  width="100" >Total Barang</th>
		<th class="th01" width="150">'.$tampilHeaderHarga.'</th>	
		</tr>
		<tr>
		</tr>
	';
}else{
	$tampilHeaderHarga= !empty($cbxDlmRibu)? 'BI<br>(Ribuan)': 'BI';
	$tampilHeaderHargaKiba= !empty($cbxDlmRibu)? 'KIB A<br>(Ribuan)': 'KIB A';
	$tampilHeaderHargaKibb= !empty($cbxDlmRibu)? 'KIB B<br>(Ribuan)': 'KIB B';
	$tampilHeaderHargaKibc= !empty($cbxDlmRibu)? 'KIB C<br>(Ribuan)': 'KIB C';
	$tampilHeaderHargaKibd= !empty($cbxDlmRibu)? 'KIB D<br>(Ribuan)': 'KIB D';
	$tampilHeaderHargaKibe= !empty($cbxDlmRibu)? 'KIB E<br>(Ribuan)': 'KIB E';
	$tampilHeaderHargaKibf= !empty($cbxDlmRibu)? 'KIB F<br>(Ribuan)': 'KIB F';
	if($Main->MODUL_ASET_LAINNYA){
		$tampilHeaderHargaKibg= !empty($cbxDlmRibu)? 'KIB G<br>(Ribuan)': 'KIB G';
		$header_kibg = '<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibg.'</th>';
		$header_kibg_2 = 
			'<th class="th01"  width="100" >Barang</th>
			<th class="th01" width="150">Harga</th>';	
	}
	
	
	$tabelHeader = '
		<tr>	
		<th class="th01" width="30" rowspan=2>No.</th>	
		<th class="th01" style="min-width:300" colspan=3 rowspan=2>Uraian</th>
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHarga.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKiba.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibb.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibc.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibd.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibe.'</th>	
		<th class="th02" width="100" colspan=2>'.$tampilHeaderHargaKibf.'</th>'.	
		$header_kibg.
		'</tr>
		<tr>
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>	
		<th class="th01"  width="100" >Barang</th>
		<th class="th01" width="150">Harga</th>'.	
		$header_kibg_2.
		'</tr>
	';
	
}							
$view->isi = str_replace('<!--menu1-->','menuaktif',$view->isi);
//get title
$view->title = getTitleKib($SPg);

	
	
// tampil page -----------------------------------------------------------
$view->isitab = file_get_contents('viewer/view_rekap.html'); //ambil template tampilan
//tampil table
include('viewer/rekap_list.php'); 	//--> $rekap->listtable	
$view->isitab = str_replace('<!--listtable-->', $rekap->listtable, $view->isitab); //
$view->isitab = str_replace('<!--title_rekap-->', $view->title, $view->isitab);	
//$view->isitab = str_replace('<!--baris_perhalaman-->', $view->BarisPerHalaman, $view->isitab);	
$view->isitab = str_replace('<!--header-->', $tabelHeader, $view->isitab);	
$view->isitab = str_replace('<!--baris_perhalaman-->', $optTampil, $view->isitab);	

$view->isitab = str_replace('<!--menu_bar-->',$menuBar,$view->isitab);
$view->isitab .=
		"<br>
		<table width=\"100%\" class=\"menudottedline\" >
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=cetak&SPg=$SPg';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=cetak&SPg=$SPg&all=1';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=cetak&SDest=XLS&SPg=$SPg&all=1';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>
			
		</tr></table>
		</td></tr>
		</table>";	
		
	
$view->isi = str_replace('<!--isitab-->',$view->isitab,$view->isi);	

//$view->isi.=



?>