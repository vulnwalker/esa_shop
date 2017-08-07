<?php


function tampilkoord($koord,$koordbid){
	//$koord = $det['koordinat_gps'];
	//$koordbid= $det['koord_bidang'];
	//echo preg_replace( '/\s*/m', '', $koordbid ); 
	if($koord !=''){		
		$fnGetMapClick = 
			"getMap('". preg_replace( '/\s*/m', '', $koord ) ."','". preg_replace( '/\s*/m', '', $koordbid ) ."')";
	}else{
		$koord='-';
		$fnGetMapClick = '';
	}
	return '
			<tr><td width="" valign="">Koordinat Lokasi Tanah</td>	<td valign="">:</td>				
					<td> '.
					'<table><tr><td>'.
					$koord.' 
					</td><td style="padding:0 0 0 4">
					<!--<input type="button" value="Google Map" onclick="'.$fnGetMapClick.'" style="width:30">	-->
					<img src="images/tumbs/gmaps_icon_24.jpg" onclick="'.$fnGetMapClick.'" title="Google Map" style="cursor:pointer">				
					</td></tr>
					</table>
					</td>
				</tr>';
}

error_reporting(0);
include ('../config.php');
if(CekLogin()){
//-------- GET Param ----------
$cek =''; $err = ''; $content ='';
$fid= $_GET['fid']; $cek.='<br> fid='.$fid;
$id = $_GET['id'];
$cbxDlmRibu = $_GET['cbxDlmRibu'];
$tipe = $_GET['tipe'];
//$id = '11101705000002080101010001';//tes

//------- get kondisi ---------
$Kondisi = $Main->SUB_UNIT ?
	' concat(a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg) = "'.$id.'"':
	' concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$id.'"';

//------- get data ----------
$sqry = 'select * from view_buku_induk2 where '.$Kondisi ; //$cek .= '<br> qry='. $sqry;
$isi= mysql_fetch_array(mysql_query($sqry)); 

$cidBI = $isi['id'];
$noReg = $isi['noreg'];
$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];// echo 'kd='.$kdBarang;
$kdBarang2 = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];// echo 'kd='.$kdBarang;
$kodelokasi = $Main->SUB_UNIT ?
	$isi['a1'].'.'.$isi['a'].'.'.$isi['b'].'.'.$isi['c'].'.'.$isi['d'].'.'.$isi['thn_perolehan'][2].$isi['thn_perolehan'][3].'.'.$isi['e'].'.'.$isi['e1']:
	$isi['a1'].'.'.$isi['a'].'.'.$isi['b'].'.'.$isi['c'].'.'.$isi['d'].'.'.$isi['thn_perolehan'][2].$isi['thn_perolehan'][3].'.'.$isi['e'];
$kdKelBarang = $isi['f'].$isi['g']."00";
$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
//$gambar= mysql_fetch_array(mysql_query("select gambar from buku_induk where '.$Kondisi'"));
//$sImg = $isi['gambar'] == ''? '': '<img src ="gambar/'.$isi['gambar'].'" width="200" height="150" >';
//$sImg = $isi['gambar'] == ''? '': '<img src ="view_img.php?fname='.$isi['gambar'].'&sw=200&sh=150" width="200" height="150" >';
$idbigbr = $isi['idawal'] == '' || $isi['idawal'] ==0 ?$isi['id']:$isi['idawal'];//echo "idbigbr=$idbigbr<br>";
$fgbr = table_get_value("select nmfile from gambar where stat=1 and idbi=$idbigbr",'nmfile'); //echo "fgbr=$fgbr<br>";
if($tipe == 'jso'){
	$width = 200;
	$height = 150;
	$jmlgbr = '1/1';
	$prefixgbr = 'Gbr';
	$Menu = '';
		/*"<div id='".$prefixgbr."_menuv' class='menuv' 
					style='position:absolute;top:5;right:5;left:auto;'
			>				
				<ul>
				<li style='width: 16px;height: 18px;padding:1;margin:1;background-color:black;'>
					<a class='gbr_btdel' style='' title='Show/Hide' onclick='".$prefixgbr."_ShowHide()'></a>
				</li>		
				</ul>
			</div>";
	*/
	//onmousemove=\"".$prefixgbr.".menuShow()\"
	//onblur=\"".$prefixgbr.".menuHide()\"
	$sImg = $fgbr == '' ? '': 
		"<div id='$prefixgbr' style='position:relative;top:0;left:0;'  >
		
		<div id='".$prefixgbr."_menu' style='z-index: 4; position: absolute; top: 0px; 
			background-color: rgba(0, 0, 0, 0.0980392); 
			width: $width; height: $height; display: none;
			visibility:visible;'
			onmouseout=\"".$prefixgbr.".menuHide()\"
		>
			<a style='cursor: pointer;color:yellow;position:absolute;top:10;left:5;
				background-image: url(images/tumbs/star.png);
				background-position: 0 0;
    			background-repeat: no-repeat;			
				width: 32px;height: 32px;
				' title='Default' onclick=''>
			</a>			
			
			<a style='cursor:default;color:white;background-color:black;position:absolute;top:135;left:10;' title=''>
				$jmlgbr
			</a>
			<a class='".$prefixgbr."_btprev' style='' title='Sebelumnya' onclick='".$prefixgbr.".Prev()'></a>
			<a class='".$prefixgbr."_btnext' style='' title='Selanjutnya' onclick='".$prefixgbr.".Next()'></a>	
				
			$Menu
		</div>
		".
		
		"<div style='width:".($width-4).";height:$height;margin:auto;padding:2' onmousemove=\"".$prefixgbr.".menuShow()\" >".
		//"tes".
		"<table width='100%' height='100%'><tr ><td align='center'>
		<img id='".$prefixgbr."_img' 
			src='view_img.php?fname=20121102_617187_881305435.jpg&amp;sw=$width&amp;sh=$height' 
			alt='' style='visibility: visible;' 
			onload='this.style.visibility=&quot;visible&quot;
			document.getElementById(&quot;".$prefixgbr."_ContainImg&quot;).style.backgroundImage=&quot;&quot;'
		>
		</td></tr></table>".
		"</div>".
	"<div>";
	
		//'<img src ="view_img.php?fname='.$fgbr.'&sw=200&sh=150"  >'
  		//$sImg = $fgbr == '' ? '': createImgEntry('FmEntryGbr3',200,100,0,2,0);
	$sImg = '';
		
}else{
		
	$sImg = $fgbr == '' ? '': 
		'<img src ="view_img.php?fname='.$fgbr.'&sw=200&sh=150"  >';
  	//createImgEntry('FmEntryGbr3',200,100,32,2,2)
}


$bidang = $isi['nmbidang']; 
$opd = $isi['nmopd']; 
$unit = $isi['nmunit']; 

//--------- tampil -----------
//$cek .='<br> f='.$isi['f'];
//$detBarang = '<table>';
switch ($isi['f']){	
	case '01':{//kibA
		
		$det= mysql_fetch_array( mysql_query('select * from kib_a where '.$Kondisi));				
		$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='0' and kd_kota='".$det['alamat_b']."' "));
		$nmkec = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='".$det['alamat_c']."' and kd_kota='".$det['alamat_b']."'  and kd_kec<>0 "));

		$alamat_kota= $nmkota['nm_wilayah']<>''?$nmkota['nm_wilayah']:$det['kota'];
		$alamat_kec= $nmkec['nm_wilayah']<>''?$nmkec['nm_wilayah']:$det['alamat_kec'];
		
		$bersertifikat = $det['bersertifikat'] == '1'? 'Ya': 'Tidak';
		$sert1 = ''; $sert2 = ''; $sert3 = 'checked';
		switch ($det['bersertifikat']) {
			case '1': $sert1 = 'checked'; $sert2 = ''; $sert3 = '';
			break;
			case '2': $sert1 = ''; $sert2 = 'checked'; $sert3 = '';
			break;
			
		}
		$detBarang .=			
			'				
			<table width="100%" ><tr valign="top"><td width="300">			
				<table >				
				<tr><td width="120">Luas  (m<sup>2</sup>)</td><td>:</td>
					<td> '.number_format($det['luas'],2,',','.').' </td>
				</tr>
								
				<!--<tr><td width="" colspan=3>Letak</td> </tr> -->
				<tr><td width="">Alamat</td><td>:</td>	<td> '.$det['alamat'].' </td></tr> 
				<tr><td width="">Kelurahan/Desa</td><td>:</td><td> '.$det['alamat_kel'].' </td></tr>
				<tr><td width="">Kecamatan</td><td>:</td>	<td> '.$alamat_kec.'</td></tr>
				<tr><td width="">Kota</td>	<td>:</td>	<td> '.$alamat_kota.' </td></tr>
				<!--<tr><td width="" valign="top">Koordinat Lokasi Tanah</td>	<td valign="top">:</td>				
					<td> '.$koord.' &nbsp;&nbsp;&nbsp;
					<input type="button" value="Google Map" onclick="'.$fnGetMapClick.'">					
					</td>
				</tr>-->
				'.tampilkoord($det['koordinat_gps'], $det['koord_bidang']).'
					
				<tr><td width="">Status Hak Tanah</td> <td>:</td>
					<td> '.$Main->StatusHakPakai [$det['status_hak']-1][1].' </td></tr>
				<tr><td width="">Penggunaan Tanah</td> <td>:</td>
					<td> '.$det['penggunaan'].' </td></tr>				
				</table>
				
			</td><td>
			<!--</div>-->
			<div style="float:left;margin:  0 0 0 8; padding: 0 0 0 8; 
						border-left-color: #EBEBEB;	border-left-style: solid; border-left-width: 1px; ">
				<table>
				<tr valign="top"><td width="120">Sertifikat Tanah</td> <td>:</td>
				<td> 
					<input type="checkbox" '.$sert1.' disabled >  Bersertifikat <br>
					<input type="checkbox" '.$sert2.' disabled >  Proses Bersertifikat <br>
					<input type="checkbox" '.$sert3.' disabled >  Belum Bersertifikat
				</td>
				</tr>				
				<tr><td width="">&nbsp;&nbsp;&nbsp;No. Sertifikat</td> <td>:</td>
					<td> '.$det['sertifikat_no'].' </td></tr>
				<tr><td width="">&nbsp;&nbsp;&nbsp;Tgl. Sertifikat</td> <td>:</td>
					<td> '.TglInd($det['sertifikat_tgl']).' </td></tr>
				<!--<tr valign="top">
				<td width="">&nbsp;&nbsp;
						<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>
						
					</td> 
					<td></td>
					<td> 					
					</td></tr>-->
				<!--<tr><td width="">Nilai Appraisal Barang (Rp)</td> <td>:</td>
					<td> - &nbsp;&nbsp;&nbsp;<input type="button" value="Detail" > </td></tr>-->
				<tr valign="top"><td width="">Keterangan</td><td>:</td>
					<td> '.$det['ket'].' </td></tr>			
				</table>
			</td></tr></table>			
			<!--</div>-->
			';
		
		break; 
			
		}
	case '02':{//kibB
		$det= mysql_fetch_array( mysql_query('select * from kib_b where '.$Kondisi));
		$detBarang .=
			'	<table>
				<tr><td width="100">Merk/Type</td><td>:</td><td> '.$det['merk'].' </td></tr>
				<tr><td width="100">Nomor</td><td></td><td> </td></tr> 
				<tr><td width="100">&nbsp;&nbsp;Pabrik</td><td>:</td><td> '.$det['no_pabrik'].' </td></tr>
				<tr><td width="100">&nbsp;&nbsp;Rangka</td><td>:</td><td> '.$det['no_rangka'].' </td></tr>
				<tr><td width="100">&nbsp;&nbsp;Mesin</td><td>:</td><td> '.$det['no_mesin'].' </td></tr>
				<tr><td width="100">&nbsp;&nbsp;Polisi</td><td>:</td><td> '.$det['no_polisi'].' </td></tr>
				<tr><td width="100">&nbsp;&nbsp;BPKB</td><td>:</td><td> '.$det['no_bpkb'].' </td></tr>						
				<tr valign="top"><td width="100">Keterangan</td><td>:</td><td> '.$det['ket'].' </td></tr>
				<!--<tr valign="top"><td width="">
					<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>
					
				</td> <td></td>
				<td>
				</td></tr>-->
				</table>
			';
		//$cek .= '<br> det = '.$detBarang;	
		break; }
	case '03':{//kibC		
		$det= mysql_fetch_array( mysql_query('select * from kib_c where '.$Kondisi));
		$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='0' and kd_kota='".$det['alamat_b']."' "));
		$nmkec = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='".$det['alamat_c']."' and kd_kota='".$det['alamat_b']."'  and kd_kec<>0 "));

		$alamat_kota= $nmkota['nm_wilayah']<>''?$nmkota['nm_wilayah']:$det['kota'];
		$alamat_kec= $nmkec['nm_wilayah']<>''?$nmkec['nm_wilayah']:$det['alamat_kec'];
		
		//*
		$detBarang .=			
			'	
			<table width="100%" ><tr valign="top"><td width="300">	
				<table>
				<!--<tr><td width="100">Kondisi Bangunan</td><td>:</td><td>'.$Main->kondisi_bangunan[$det['kondisi_bangunan']-1][1].' </td></tr>-->
				<tr><td width="100">Konstruksi Bangunan</td><td>:</td><td>'.$Main->Bangunan[$det['kondisi_bangunan']-1][1].' </td></tr> 
				<tr><td width="100">&nbsp;&nbsp;Bertingkat/Tidak</td><td>:</td><td>'.$Main->Tingkat[$det['konstruksi_tingkat']-1][1].' </td></tr>
				<tr><td width="100">&nbsp;&nbsp;Beton/Tidak</td><td>:</td><td>'.$Main->Beton [$det['konstruksi_beton']-1][1].' </td></tr>
				<tr><td width="100">Luas Lantai (m<sup>2</sup>)</td><td>:</td><td>'.number_format($det['luas_lantai'],2,',','.') .' </td></tr>
				
				<!--<tr><td width="150">Letak </td><td></td> <td> </td></tr> -->
				<tr><td width="150">Alamat</td><td>:</td>	<td> '.$det['alamat'].' </td></tr> 
				<tr><td width="150">Kelurahan/Desa</td><td>:</td>	<td> '.$det['alamat_kel'].' </td></tr>
				<tr><td width="150">Kecamatan</td><td>:</td>	<td>'.$alamat_kec.' </td></tr>
				<tr><td width="150">Kota</td>	<td>:</td>	<td> '.$alamat_kota.' </td></tr>
				
				<!--<tr><td width="" valign="top">Koordinat Lokasi Tanah</td>	<td valign="top">:</td>				
					<td> '.$koord.' &nbsp;&nbsp;&nbsp;<input type="button" value="Google Map" onclick="'.$fnGetMapClick.'"></td></tr>
				-->
				'.tampilkoord($det['koordinat_gps'], $det['koord_bidang']).'
				</table>
				</td><td>
				<div style="float:left;margin:  0 0 0 8; padding: 0 0 0 8; 
						border-left-color: #EBEBEB;	border-left-style: solid; border-left-width: 1px; ">
				<table>
				<tr><td width="100">Dokumen Gedung</td><td></td><td> </td></tr> 		
				<tr><td width="100">&nbsp;&nbsp;Tanggal</td><td>:</td><td>'.TglInd($det['dokumen_tgl']) .' </td></tr> 
				<tr><td width="100">&nbsp;&nbsp;Nomor</td><td>:</td><td> '.$det['dokumen_no'].'</td></tr> 
				<tr><td width="100">Luas (m<sup>2</sup>)</d><td>:</td><td>'. number_format($det['luas'],2,',','.')  .' </td></tr>
				<tr><td width="100">Status Tanah</td><td>:</td><td> '.$Main->StatusTanah [$det['status_tanah']-1][1].'</td></tr>
				<tr><td width="100">No. Kode Tanah</td><td>:</td><td>'.$det['kode_tanah'].' </td></tr>
				<tr valign="top"><td width="100">Keterangan</td><td>:</td><td> '.$det['ket'].'</td></tr>			
				<!--<tr valign="top"><td width="">
					<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>	
					
				</td> <td></td>
				<td>
				</td></tr>-->			
				</table>
				</div>
				</td>
				</tr></table>
			';//*/
		break; }
	case '04':{//kibD
		$det= mysql_fetch_array( mysql_query('select * from kib_d where '.$Kondisi));
		$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='0' and kd_kota='".$det['alamat_b']."' "));
		$nmkec = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='".$det['alamat_c']."' and kd_kota='".$det['alamat_b']."'  and kd_kec<>0 "));

		$alamat_kota= $nmkota['nm_wilayah']<>''?$nmkota['nm_wilayah']:$det['kota'];
		$alamat_kec= $nmkec['nm_wilayah']<>''?$nmkec['nm_wilayah']:$det['alamat_kec'];

		/*$koord = $det['koordinat_gps'];
		if($koord !=''){
			$fnGetMapClick = "getMap('".$koord."')";
		}else{
			$koord='-';
			$fnGetMapClick = '';
		}*/
		$detBarang .=
			'	
			<table width="100%" ><tr valign="top"><td width="300">
				<table>
				<tr><td width="120">Konstruksi</td><td>:</td><td> '.$det['konstruksi'].' </td></tr>
				<tr><td width="">Panjang (Km)</td><td>:</td><td> '.$det['panjang'].'</td></tr> 
				<tr><td width="">Lebar (m)</td><td>:</td><td> '.$det['lebar'].'</td></tr>
				<tr><td width="">Luas (m<sup>2</sup>)</td><td>:</td><td> '.  number_format($det['luas'],2,',','.') .'</td></tr>
				
				<!--<tr><td width="">Letak </td><td></td> <td> </td></tr> -->
				<tr><td width="">Alamat</td><td>:</td>	<td> '.$det['alamat'].' </td></tr> 
				<tr><td width="">Kelurahan/Desa</td><td>:</td>	<td> '.$det['alamat_kel'].' </td></tr>
				<tr><td width="">Kecamatan</td><td>:</td>	<td> '.$alamat_kec.'</td></tr>
				<tr><td width="">Kota</td>	<td>:</td>	<td> '.$alamat_kota.' </td></tr>
				
				<!--<tr valign="top"><td width="" >Koordinat Lokasi Tanah</td>	<td >:</td>				
					<td> '.$koord.' &nbsp;&nbsp;&nbsp;<input type="button" value="Google Map" onclick="'.$fnGetMapClick.'"></td></tr>
				-->
				'.tampilkoord($det['koordinat_gps'], $det['koord_bidang']).'
				</table>
				</td><td>
				</div>
				<div style="float:left;margin:  0 0 0 8; padding: 0 0 0 8; 
						border-left-color: #EBEBEB;	border-left-style: solid; border-left-width: 1px; ">
				<table>
				<tr><td width="100">Dokumen</td><td></td><td> </td></tr> 		
				<tr><td width="">&nbsp;&nbsp;Tanggal</td><td>:</td><td>'.TglInd($det['dokumen_tgl']) .' </td></tr> 
				<tr><td width="">&nbsp;&nbsp;Nomor</td><td>:</td><td> '.$det['dokumen_no'].'</td></tr> 
				<tr><td width="">Luas (m<sup>2</sup>)</d><td>:</td><td>'.  number_format($det['luas'],2,',','.') .' </td></tr>
				<tr><td width="">Status Tanah</td><td>:</td><td> '.$Main->StatusTanah [$det['status_tanah']-1][1].'</td></tr>
				<tr><td width="">No. Kode Tanah</td><td>:</td><td>'.$det['kode_tanah'].' </td></tr>
				<tr valign="top"><td width="100">Keterangan</td><td>:</td><td>'.$det['ket'].' </td></tr>
				<!--<tr valign="top"><td width="">
					<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>
					
				</td> <td></td>
				<td>
				</td></tr>-->
				</table>
				</div>
				</td>
			</tr></table>
			';
		break; }
	case '05':{ //E
		$det= mysql_fetch_array( mysql_query('select * from kib_e where '.$Kondisi));
		$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where a ='".$det['alamat_a']."' and b='".$det['alamat_b']."' "));
		
		
		$detBarang .=
			'
			
			<table>
				<tr><td width="100" height="24">Buku Perpustakaan</td><td width="10"></td><td></td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Judul/Pencipta</td><td width="10">:</td><td>'.$det['buku_judul'].' </td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Spesifikasi</td><td width="10">:</td><td>'.$det['buku_spesifikasi'].' </td></tr>	
			
				<tr><td width="700" height="24" colspan="3">Barang bercorak Kesenian/Kebudayaan</td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Asal Daerah</td><td width="10">:</td><td>'.$det['seni_asal_daerah'].' </td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Pencipta</td><td width="10">:</td><td>'.$det['seni_pencipta'].' </td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Bahan</td><td width="10">:</td><td>'.$det['seni_bahan'].' </td></tr>	
			
				<tr><td width="100" height="24">Hewan Ternak</td><td width="10"></td><td></td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Jenis</td><td width="10">:</td><td>'.$det['hewan_jenis'].' </td></tr>	
				<tr><td width="100">&nbsp;&nbsp;&nbsp;Ukuran</td><td width="10">:</td><td>'.$det['hewan_ukuran'].' </td></tr>	
				<tr valign="top"><td width="100">Keterangan</td><td>:</td><td>'.$det['ket'].' </td></tr>
				<!--<tr valign="top"><td width="">
					<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>
					
				</td> <td></td>
				<td>
				</td></tr>-->
			</table>
			
			
			';
	
		break;}
	case '06':{ //F
		$det= mysql_fetch_array( mysql_query('select * from kib_f where '.$Kondisi));
		$nmkota = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='0' and kd_kota='".$det['alamat_b']."' "));
		$nmkec = mysql_fetch_array(mysql_query("select nm_wilayah from ref_kotakec where kd_kec ='".$det['alamat_c']."' and kd_kota='".$det['alamat_b']."'  and kd_kec<>0 "));

		$alamat_kota= $nmkota['nm_wilayah']<>''?$nmkota['nm_wilayah']:$det['kota'];
		$alamat_kec= $nmkec['nm_wilayah']<>''?$nmkec['nm_wilayah']:$det['alamat_kec'];
		
		/*$koord = $det['koordinat_gps'];
		if($koord !=''){
			$fnGetMapClick = "getMap('".$koord."')";
		}else{
			$koord='-';
			$fnGetMapClick = '';
		}*/
		$detBarang ='
			<table width="100%" ><tr valign="top"><td width="300">
				<table>
				<tr><td width="120">Bangunan</td><td width="10">:</td>
					<td>'.$Main->Bangunan[$det['bangunan']-1]['1'].'</td>
				</tr>
				<tr><td width="" height="24" colspan="3">Konstruksi Bangunan</td></tr>	
				<tr><td width="">&nbsp;&nbsp;&nbsp;Bertingkat/Tidak</td><td width="10">:</td>
					<td>'.$Main->Tingkat[$det['konstruksi_tingkat']-1]['1'].'</td>
				</tr>
				<tr><td width="">&nbsp;&nbsp;&nbsp;Beton/Tidak</td><td width="10">:</td>
					<td>'.$Main->Beton[$det['konstuksi_beton']-1]['1'].'</td>
				</tr>
				
				<!-- <tr><td width="">Letak </td><td></td> <td> </td></tr> -->
				<tr><td width="">Alamat</td><td>:</td>	<td> '.$det['alamat'].' </td></tr> 
				<tr><td width="">Kelurahan/Desa</td><td>:</td>	<td>'.$det['alamat_kel'].'  </td></tr>
				<tr><td width="">Kecamatan</td><td>:</td>	<td> '.$alamat_kec.'</td></tr>
				<tr><td width="">Kota</td>	<td>:</td>	<td> '.$alamat_kota.' </td></tr>
				
				</table>
			</td><td style="float:left;margin:0; padding: 0 0 0 8; 
						border-left-color: #EBEBEB;	border-left-style: solid; border-left-width: 1px; ">
				<table>
				<!--<tr  valign="top"><td width="">Koordinat Lokasi Tanah</td>	<td >:</td>				
					<td> '.$koord.' &nbsp;&nbsp;&nbsp;<input type="button" value="Google Map" onclick="'.$fnGetMapClick.'"></td></tr>
				-->
				'.tampilkoord($det['koordinat_gps'], $det['koord_bidang']).'
				<tr><td width="100">No. Dokumen</td><td width="10">:</td><td>'.$det['dokumen_no'].' </td></tr>
				<tr><td width="100">Status Tanah</td><td width="10">:</td>
					<td>'.$Main->StatusTanah[$det['status_tanah']-1]['1'].'</td>
				</tr>
				<tr><td width="100">No. Kode Tanah</td><td width="10">:</td><td>'.$det['kode_tanah'].' </td></tr>
				<tr valign="top"><td width="100">Keterangan</td><td>:</td><td>'.$det['ket'].' </td></tr>		
				<!--<tr valign="top"><td width="">
					<a href="pages.php?Pg=brg&SPg=01&byId='.$cidBI.'" target="_blank"
							title="Open Dokumen" style="color:#0000FF; text-decoration: underline;">Download Dokumen</a>
					
				</td> <td></td>
				<td>
				</td></tr>-->
				</table>
			
			</td>
			</tr></table>
			';
			
	
		break;}

}


//----------------- TAB DETAIL ---------------------------
//> pemanfaatan --------------------------------------------------
$sdetqry = "select * from pemanfaatan where id_bukuinduk='$cidBI'" ;// $cek .= '<br> qry manfaat='. $sdetqry;
$detqry = mysql_query($sdetqry);
$detail_pemanfaatan_datalist = '
		<tr valign="top" >			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>			
		</tr>
		';
$jmldatadet = mysql_num_rows($detqry);
if ($jmldatadet > 0 ){
	$detail_pemanfaatan_datalist = '';	

	$no= 1;
	while ($dat = mysql_fetch_array($detqry) ){
		$detail_pemanfaatan_datalist .= '
			<tr valign="top" >			
			<td class="GarisDaftar" >'.$no.'</td>
			<td class="GarisDaftar" >'.TglInd($dat['tgl_pemanfaatan']).'</td>
			<td class="GarisDaftar" >'.$Main->BentukPemanfaatan[$dat['bentuk_pemanfaatan']-1]['1'].'</td>
			<td class="GarisDaftar" >'.$dat['kepada_instansi'].'<br>'.$dat['kepada_alamat'].'<br>'.$dat['kepada_nama'].'</td>
			
			<td class="GarisDaftar" >'.$dat['jangkawaktu'].'</td>
			<td class="GarisDaftar" >'.number_format($dat['biaya_pemanfaatan'], 2, ',', '.').'</td>
			<td class="GarisDaftar" >'.$dat['ket'].'</td>			
			</tr>';	
		$no ++;
	}
}
$detail_pemanfaatan = "
	<div id='tb1' style='position:absolute;visibility:hidden;height:1;' >
	<table width=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th02\" >No</TH>
		<TH class=\"th02\" style='width:60'>Tanggal</TH>
		<TH class=\"th02\" style=''>Bentuk<br>Pemanfaat an</TH>
		<TH class=\"th02\" style=''>Kepada <br> Instansi <br> Alamat<br> Nama</TH>		
		<TH class=\"th02\" style='width:45'>Jangka Waktu (Thn)</TH>
		<TH class=\"th02\" style='width:65'>Biaya (Ribu)</TH>
		<TH class=\"th02\" style=''>Ket.</TH>
	</TR>	
	<!--list-->
	".$detail_pemanfaatan_datalist."
	</table>
	</div>
	";	


//> pemeliharaan ------------------------------------------------------
$sdetqry = "select * from v_pemelihara where id_bukuinduk='$cidBI'" ; //$cek .= '<br> qry pemeliharaan='. $sdetqry;
//$sdetqry = 'select * from pemeliharaan where ';
$detqry = mysql_query($sdetqry);
$detail_pemeliharaan_datalist = '
		<tr valign="top" >			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>			
			<td>&nbsp</td>					
		</tr>
		';
$jmldatadet = mysql_num_rows($detqry);
if ($jmldatadet > 0 ){
	$detail_pemeliharaan_datalist = '';	
	$no= 1;
	while ($dat = mysql_fetch_array($detqry) ){
		$detail_pemeliharaan_datalist .= '
		<tr valign="top" >			
			<td class="GarisDaftar">'.$no.'</td>
			<td class="GarisDaftar">'.TglInd($dat['tgl_pemeliharaan']).'</td>
			<td class="GarisDaftar">'.$dat['jenis_pemeliharaan'].'</td>			
			<td class="GarisDaftar" align=right>'.number_format($dat['biaya_pemeliharaan'], 2, ',', '.').'</td>			
			<td class="GarisDaftar">'.$dat['ket'].'</td>			
		</tr>';	
		$no ++;
		$cb++;
	}
}
//$detail->pemeliharaan =
$detail_pemeliharaan =
	"<div id='tb2' style='position:absolute;visibility:hidden;height:1;' >	
	<table width=\"100%\" class=\"koptable\" BORDER=1 id='tblpemelihara'>
	<TR>
		<TH class=\"th02\" >No</TH>				
		<TH class=\"th02\" >Tanggal</TH>
		<TH class=\"th02\" >Uraian Pemeliharaan</TH>		
		<TH class=\"th02\" >Biaya</TH>		
		<TH class=\"th02\" >Ket.</TH>
	</TR>
	<!--list-->
	".$detail_pemeliharaan_datalist."	
	</table>
	</div>
	";	

//> pengamanan ------------------------------------------------
$sdetqry = "select * from pengamanan where id_bukuinduk='$cidBI'" ;// $cek .= '<br> qry manfaat='. $sdetqry;
$detqry = mysql_query($sdetqry);

$detail_pengamanan_datalist = '
		<tr valign="top" >			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>			
			<td>&nbsp</td>					
		</tr>
		';
$jmldatadet = mysql_num_rows($detqry);
if ($jmldatadet > 0 ){
$detail_pengamanan_datalist = '';
$no= 1;
while ($dat = mysql_fetch_array($detqry) ){
	$detail_pengamanan_datalist .= '
		<tr valign="top" >			
			<td class="GarisDaftar" >'.$no.'</td>
			<td class="GarisDaftar" >'.TglInd($dat['tgl_pengamanan']).'</td>
			<td class="GarisDaftar" >'.$dat['uraian_kegiatan'].'</td>			
			<td class="GarisDaftar" align="right">'.number_format($dat['biaya_pengamanan'], 2, ',', '.').'</td>			
			<td class="GarisDaftar">'.$dat['ket'].'</td>			
		</tr>
		';	
	$no ++;
}
}
$detail_pengamanan ="
	<div id='tb3' style='position:absolute;visibility:hidden;height:1;' >
	<table width=\"100%\" class=\"koptable\" BORDER=1>
		<TR>
			<TH class=\"th02\" >No</TH>			
			<TH class=\"th02\" style='width:60'>Tanggal</TH>
			<TH class=\"th02\" >Uraian Kegiatan</TH>						
			<TH class=\"th02\" style='width:60'>Biaya</TH>			
			<TH class=\"th02\" >Ket.</TH>
		</TR>
		<!--list-->
		".$detail_pengamanan_datalist."	
	</table>
	</div>
	";	
//> penilaian
$sdetqry = 'select * from penilaian where '.$Kondisi ; $cek .= '<br> qry nilai='. $sdetqry;
$detqry = mysql_query($sdetqry);
$detail_penilaian_datalist = '
		<tr valign="top" >			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>							
		</tr>
		';
$jmldatadet = mysql_num_rows($detqry);
if ($jmldatadet > 0 ){
$detail_penilaian_datalist = '';
$no= 1;
while ($dat = mysql_fetch_array($detqry) ){
	$detail_penilaian_datalist .= '
		<tr valign="top" >			
			<td>'.$no.'</td>
			<td>'.TglInd($dat['tgl_penilaian']).'</td>				
			<td>'.number_format($dat['nilai_barang']/1000, 2, ',', '.').'</td>			
			<td>'.$dat['ket'].'</td>			
		</tr>
		';	
	$no ++;
}
}
$detail_penilaian = "
	<div id='tb4' style='position:absolute;visibility:hidden;height:1;' >
	<table width=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th02\" >No</TH>				
		<TH class=\"th02\" >Tanggal</TH>
		<TH class=\"th02\" >Nilai Barang (Ribu)</TH>
		<TH class=\"th02\" >Ket</TH>
	</TR>
	<!--list-->
	".$detail_penilaian_datalist."		
	</table>
	</div>
	";
//>penghapusan
$sdetqry = 'select * from penghapusan where '.$Kondisi ;// $cek .= '<br> qry manfaat='. $sdetqry;
$detqry = mysql_query($sdetqry);
$dat = mysql_fetch_array($detqry);
$simgrusak = $dat['gambar'] == ''? '': '<img src ="gambar/'.$dat['gambar'].'" width="150" height="150" >';

$detail_penghapusan = "
	<div id='tb5' style='position:absolute;visibility:hidden;height:1;' >
	
	
	<table>
		<tr valign='top'>
			<td width='150'>
				<table width='150' >
				<tr>
					<td align='center' >".
					"<div 
						style='height:150;width:150;
						background-color:#EFEFF1;												
						border: 2px solid #EFEFF1'
					>".
						//background-image: url(http:images/administrator/images/emptyimg2.jpg);
						//background-repeat-x: no-repeat;	background-repeat-y: no-repeat;
						$simgrusak.
					"</div>".
					"</td>					
				</tr>
				<tr><td align='center'>Gambar Kondisi Penghapusan</td></tr>
				</table>
			</td>
			<td>
				<table style='margin: 0 0 0 4 '>
					<tr><td width='150'>No & Tgl Permohonan </td><td width='10'>:</td><td> </td></tr>
					<tr><td width='150'>Nilai Barang </td><td width='10'>:</td><td> </td></tr>
					<tr><td width='150'>Uraian </td><td width='10'>:</td><td>".$dat['uraian']." </td></tr>
					<tr><td width='150'>Keterangan </td><td width='10'>:</td><td> ".$dat['keterangan']." </td></tr>
					<tr><td width='150'>No & Tgl Persetujuan </td><td width='10'>:</td><td>".TglInd($dat['tgl_penghapusan'])." </td></tr>
					<tr><td width='150'>No & Tgl Berita Acara </td><td width='10'>:</td><td> </td></tr>
				</table>
			</td>
			
		</tr>
	</table>	
	</div>	
	";	
//> penilaian
$sdetqry = 'select * from pemindahtanganan where '.$Kondisi ;// $cek .= '<br> qry manfaat='. $sdetqry;
$detqry = mysql_query($sdetqry);
$detail_mutasi_datalist = '
		<tr valign="top" >			
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>
			<td>&nbsp</td>		
			<td>&nbsp</td>							
		</tr>
		';
$jmldatadet = mysql_num_rows($detqry);
if ($jmldatadet > 0 ){
$detail_mutasi_datalist = '';
$no= 1;
while ($dat = mysql_fetch_array($detqry) ){
	$detail->mutasi_datalist .= '
		<tr valign="top" >			
			<td>'.$no.'</td>
			<td>'.TglInd($dat['tgl_pemindahtanganan']).'</td>	
			<td>'.$Main->BentukPemindahtanganan[$dat['bentuk_pemindahtanganan']-1]['1'].'</td>		
			<td>'.$dat['uraian'].'</td>			
			<td>'.$dat['ket'].'</td>			
		</tr>
		';	
	$no ++;
}
}
$detail_mutasi = "
	<div id='tb6' style='position:absolute;visibility:hidden;height:1;' >
	<table width=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th02\" >No</TH>
		<TH class=\"th02\" >Tanggal</TH>
		<TH class=\"th02\" >Bentuk</TH>		
		<TH class=\"th02\" >Uraian</TH>
		<TH class=\"th02\" >Keterangan</TH>
	</TR>
	<!--list-->
	".$detail_mutasi_datalist."
	</table>	
	</div>
	";



$tampilHarga = !empty($cbxDlmRibu) ? number_format($isi['jml_harga']/1000, 2, ',', '.'): number_format($isi['jml_harga'], 2, ',', '.');
$tampilHeaderHarga =  !empty($cbxDlmRibu) ? "Harga Perolehan (ribu)" : "Harga Perolehan";
$cek = '';


//if ($isi['gambar'] != ''){
if ( $fgbr  != ''){
	//$tampilMenuPerbesar = '<a style="float:right;margin:0 4 0 0;color:#0000FF;text-align:left" href="gambar/'.$isi['gambar'].'" target="blank">Perbesar</a>';	
	$tampilMenuPerbesar = '<a style="float:right;margin:0 4 0 0;color:#0000FF;text-align:left" 
		href="pages.php?Pg=brg&SPg=02&byId='.$cidBI.'" target="blank">Perbesar</a>';	
}else{
	$tampilMenuPerbesar ='';
}


$header = $tipe=='jso'?'':
	'<tr><td valign="top" height="30"> <!--header-->
		<div id="pagetitle" >
		<table width="100%" class="menubar" cellspacing="0" cellpadding="0"><tr><td background="images/bg.gif" class="stDialogTitle">
			'.$nmBarang['nm_barang'].'
		</td></tr></table>
		</div>
	</td></tr>';
$detail = $tipe=='jso'?
	'<table width="100%">		
		<tr><td> <!--isi detail-->						
			<div style="margin-width:4;overflow-y:auto;height:200;
					border-color: rgb(221, 221, 221); border-style: solid; border-width: 1 1 1 1px;
						">
			<div id="pagecontain" >'.$detBarang.'</div>						
			</div>
		</td></tr>					
	</table>':
	'<table width="100%">
					<tr><td> <!--tabdetail-->
						<table id="tabdetail" width="100%">	<tr>
							<td id="t0" class="stTabDetail" width="50" onclick="tabdetail(1)">Detail 		</td> 
							<td id="t1" class="stTabDetail" width="70" onclick="tabdetail(2)">Pemanfaatan  	</td>
							<td id="t2" class="stTabDetail" width="70" onclick="tabdetail(3)">Pemeliharaan 	</td>
							<td id="t3" class="stTabDetail" width="70" onclick="tabdetail(4)">Pengamanan 	</td>
							<td id="t4" class="stTabDetail" width="70" onclick="tabdetail(5)">Penilaian 	</td>
							<td id="t5" class="stTabDetail" width="70" onclick="tabdetail(6)">Penghapusan 	</td>
							<td id="t6" class="stTabDetail" width="70" onclick="tabdetail(7)">Pemindah tanganan </td>							
						</tr></table>
					</td></tr>
					<tr><td> <!--isi detail-->						
						<div style="margin-width:4;overflow-y:auto;height:200;
								border-color: rgb(221, 221, 221); border-style: solid; border-width: 1 1 1 1px;
									">
						<div id="pagecontain" ></div>						
						</div>
					</td></tr>					
				</table>';
$menubawah =  $tipe=='jso'?'':
	'<tr height="40"><td align="right" style="padding:4 8 4 4"> 
		<!--<input class="button_std" type="button" value="Cetak" onclick="'.$fid.'.print()">-->
		<form action="" method="post" name="adminForm2" id="adminForm2">
		<input name="cidBI[]" id="cidBI[]" type=hidden value="'.$cidBI.'">
		<input class="button_std" type="button" value="Cetak" 
			onclick="if (document.getElementById(\'cbxDlmRibu\').checked ){
							adminForm2.action = \'index.php?Pg=PR&SPg=brg_cetak&cbxDlmRibu=1\';
						}else{
							adminForm2.action = \'index.php?Pg=PR&SPg=brg_cetak\';
						}
						adminForm2.target = \'_blank\';
						document.getElementById(\'adminForm2\').submit();
				"> 
		<input class="button_std" type="button" value="Close" onclick="'.$fid.'.close()"> 
		
		</form>
	</td></tr>';
$nmkota =  $Main->DEF_KEPEMILIKAN == 11?
	'':
	'<tr><td width="150">Kota/Kabupaten</td><td width="10">:</td><td>'.$Main->NM_WILAYAH .'</td></tr>';
$lblBidang = $Main->DEF_KEPEMILIKAN == 11? 'Bidang' : 'Bidang';
$lblUnit = $Main->DEF_KEPEMILIKAN == 11? 'Asisten / OPD' : 'SKPD';
$lblSubUnit = $Main->DEF_KEPEMILIKAN == 11? 'BIRO / UPTD/B' : 'Unit';
$vSeksi = $Main->SUB_UNIT == FALSE? '' : 
	'<tr><td width="150">Sub Unit</td><td width="10">:</td><td><div style="white-space:nowrap; overflow:hidden;width:225;font-size: 11px;"  title="'.$isi['nmseksi'].'">'.$isi['nmseksi'].'</div></td></tr>';
$content =  
'<table width="100%" height="100%" cellspacing="0" cellpadding="0">'.
	$header.
	'<tr><td valign="top" style="padding:8 8 0 8">
		<!--content--> 
			
		<div id= "contentshow">
		<table>
			<tr  valign="top">			
			<td width="400"> 
				<!--general-->				
				<table>
					<tr><td width="150">Kepemilikan</td><td width="10">:</td><td>'.$Main->NM_KEPEMILIKAN.'</td></tr>
					<tr><td width="150">Provinsi</td><td width="10">:</td><td>'.$Main->Provinsi[1] .'</td></tr>'.
					$nmkota.
					'<tr><td width="150">'.$lblBidang.'</td><td width="10">:</td><td><div style="white-space:nowrap; overflow:hidden;width:225;font-size: 11px;" title="'.$isi['nmbidang'].'">'.$isi['nmbidang'].'</div></td></tr>
					<tr><td width="150">'.$lblUnit.'</td><td width="10">:</td><td><div style="white-space:nowrap; overflow:hidden;width:225;font-size: 11px;" title="'.$isi['nmopd'].'">'.$isi['nmopd'].'</div></td></tr>
					<tr><td width="150">'.$lblSubUnit.'</td><td width="10">:</td><td><div style="white-space:nowrap; overflow:hidden;width:225;font-size: 11px;" title="'.$isi['nmunit'].'">'.$isi['nmunit'].'</div></td></tr>'.
					$vSeksi.
					'<tr><td width="150">Tahun Perolehan</td><td width="10">:</td><td>'.$isi['thn_perolehan'].'</td></tr>
					
					<tr><td width="150">Kode / No. Register</td><td width="10">:</td><td>'.$kdBarang2.'.'.$noReg.'</td></tr>
					<tr><td width="150">Nama Barang</td><td width="10">:</td><td>'.$nmBarang['nm_barang'].'</td></tr>
					<!--<tr><td width="150">Cara Perolehan</td><td width="10">:</td><td>'.$Main->AsalUsul[0][$isi['asal_usul']].'</td></tr>-->
					<tr><td width="150">Cara Perolehan</td><td width="10">:</td><td>'.$Main->AsalUsul[$isi['asal_usul']-1][1].'</td></tr>
					<tr><td width="150">'.$tampilHeaderHarga.'</td><td width="10">:</td><td>'.
						$tampilHarga.'</td></tr>
										
					<tr><td width="150">Kondisi</td><td width="10">:</td><td>'.$Main->KondisiBarang[$isi['kondisi']-1][1].'</td></tr>
					<!--<tr><td width="150">Status Terakhir</td><td width="10">:</td><td>'.$Main->StatusBarang[$isi['status_barang']-1][1].'</td></tr>-->
					
				</table>
				
				
								
			</td>
			<td>
				<!--image-->
				'.$tampilMenuPerbesar.'
				<table width="200" height="200">
				<tr >'.
					"<td align='center'>
					<div 
						id = 'img_contain'
						style='height:150;width:200;
						background-color:#EFEFF1;												
						border: 2px solid #EFEFF1'
					>".
					/*<td  style="background-color:#EFEFF1;
								background-image: url(http:images/administrator/images/emptyimg.jpg);
								background-repeat-x: no-repeat;	background-repeat-y: no-repeat;
								border: 2px solid #EFEFF1;height:150;width:200" ><!--image-->'.*/
						$sImg.
					"</div>".
					'</td>
				</tr>
				<tr><td align="center" style="border: 1px solid #EFEFF1;"><b>'.$kodelokasi.'  <br>
				   '.$kdBarang2.'.'.$noReg.'  </b></td></tr>
				</table>
			</td>
			</tr>
			<tr><td colspan=2 valign="top">	
				<!--detail-->'.
				$detail.
				
			'</td></tr>
		</table>
		</div>
	</td></tr>'.
	$menubawah.
	'<tr><td><!--footer--> </td></tr>
	
</table>

'.$cek;




if($tipe=='jso'){
	$pageArr = array(
		'cek'=>$cek, 'err'=>$err, 'content'=>
			$content.
			'<div id = "contenthide" style= "position:absolute;visibility:hidden;display:none"></div>'
	);
	$page = json_encode($pageArr);	
	echo $page;
}else{
	echo $content
	.'<div id="tb0" style="position:absolute;visibility:hidden" >	'.$detBarang.' </div>'
.$detail_pemanfaatan

.$detail_pemeliharaan
//.$detail->pemeliharaan
//.$detail->pengamanan
.$detail_pengamanan

.$detail_penilaian
.$detail_penghapusan
.$detail_mutasi
.'<div id = "contenthide" style= "position:absolute;visibility:hidden;height:1;"></div>';	
}

//.map_showjs();
}else {
	echo "Anda belum Login!! Silahkan login terlebih dahulu <br> <a href='index.php'>Login</a>";
}
?>