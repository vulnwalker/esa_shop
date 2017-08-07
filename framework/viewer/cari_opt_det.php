<?php
//------ create option detail pencarian -----------

$view->optcaridet ='';

//global $Main, $selHakPakai, $alamat, $kota, $noSert;
//global $merk, $bahan, $noPabrik, $noRangka, $noMesin, $noPolisi, $noBPKB;
switch($SPg){
	case '03': break;
	case '04'://a
		/*$opt = '<option value="">--- Semua ---</option>';
		for($i= 1; $i<=2; $i++ ){$opt .= '<option value="'.$i.'">'.$arr[$i-1][1].'</option>';}*/
		if ($doCari!= 1){
			$shakpakai = cmb2D_v2('selHakPakai', $selHakPakai, $Main->StatusHakPakai, '','--- Semua ---');	
			$sAlamat = '<textarea rows="2" cols="60" name="alamat" value="'.$alamat.'" >'.$alamat.'</textarea> ';
			$sKota = selKabKota(selKabKota, $selKabKota);
			$sBersertifikat = cmb2D_v2('bersertifikat', $bersertifikat, $Main->bersertifikat, '','--- Semua ---');
			$sNoSert = '<input name="noSert" type="text" value="'.$noSert.'">';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else {
			$shakpakai = '<b>'.$Main->StatusHakPakai[$selHakPakai-1][1].' <input type="hidden" name="selHakPakai" value="'.$selHakPakai.'" > ';	
			$sAlamat = '<b>'.$alamat.' <input type="hidden" name="alamat" value="'.$alamat.'" > ';
			if ($selKabKota != ''){
				$sKota = table_get_value("select * from ref_wilayah where a='10' and b='".$selKabKota."' order by nm_wilayah","nm_wilayah");	
			}else{
				$sKota = '';	
			}			
			$sKota = '<b>'.$sKota.' <input type="hidden" name="selKabKota" value="'.$selKabKota.'" > ';	
			$sBersertifikat = '<b>'.$Main->bersertifikat[$bersertifikat-1][1].' <input type="hidden" name="bersertifikat" value="'.$bersertifikat.'" > ';	
			$sNoSert = '<b>'.$noSert.' <input type="hidden" name="noSert" value="'.$noSert.'" > ';
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';
		}
		
		$hsl = '
			<tr height=24><td width="100">Hak Pakai</td><td width="10">:</td>  
				<td>'.$shakpakai.'</td>
			</td></tr>
			<tr height=24 valign="top"><td width="100">Alamat</td><td width="10">:</td><td>
				'.$sAlamat.' </td></tr>
			<tr height=24><td width="100">Kota/Kabupaten</td><td width="10">:</td><td>
				 '.$sKota.'  </td></tr>
			<tr height=24><td width="100">Bersertifikat</td><td width="10">:</td>				
				<td>'.$sBersertifikat.'</td>				
			</tr>				
			<tr height=24><td width="100">No. Sertifikat</td><td width="10">:</td><td>
			'.$sNoSert.'  </td></tr>
			<tr height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
		';
		break;	
	case '05':	//b		
		if ($doCari != 1){
			$sMerk = '<input name="merk" type="text" value="'.$merk.'"> ';	
			$sBahan= '<input name="bahan" type="text" value="'.$bahan.'">';
			$sNoPabrik = '<input name="noPabrik" type="text" value="'.$noPabrik.'"> ';
			$sNoRangka = '<input name="noRangka" type="text" value="'.$noRangka.'">';
			$sNoMesin = '<input name="noMesin" type="text" value="'.$noMesin.'">';
			$sNoPol = '<input name="noPolisi" type="text" value="'.$noPolisi.'">';
			$sNoBPKB = '<input name="noBPKB" type="text" value="'.$noBPKB.'">';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else{
			$sMerk = '<b>'.$merk.' <input type="hidden" name="merk" value="'.$merk.'" > ';
			$sBahan = '<b>'.$bahan.' <input type="hidden" name="bahan" value="'.$bahan.'" > ';
			$sNoPabrik = '<b>'.$noPabrik.' <input type="hidden" name="noPabrik" value="'.$noPabrik.'" > ';
			$sNoRangka = '<b>'.$noRangka.' <input type="hidden" name="noRangka" value="'.$noRangka.'" > ';
			$sNoMesin = '<b>'.$noMesin.' <input type="hidden" name="noMesin" value="'.$noMesin.'" > ';
			$sNoPol = '<b>'.$noPolisi.' <input type="hidden" name="noPolisi" value="'.$noPolisi.'" > ';
			$sNoBPKB = '<b>'.$noBPKB.' <input type="hidden" name="noBPKB" value="'.$noBPKB.'" > ';
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';			
		}
		
		$hsl = '
			<tr height=24><td width="100">Merk</td><td width="10">:</td><td> '.$sMerk.'</td></tr>			
			<tr height=24><td width="100">Bahan</td><td width="10">:</td><td>'.$sBahan.'  </td></tr>
			<tr height=24><td width="100">No. Pabrik</td><td width="10">:</td><td> '.$sNoPabrik.' </td></tr>
			<tr height=24><td width="100">No. Rangka</td><td width="10">:</td><td>'.$sNoRangka.'  </td></tr>
			<tr height=24><td width="100">No. Mesin</td><td width="10">:</td><td> '.$sNoMesin.'  </td></tr>
			<tr height=24><td width="100">No. Polisi</td><td width="10">:</td><td> '.$sNoPol.'  </td></tr>
			<tr height=24><td width="100">No. BPKB</td><td width="10">:</td><td> '.$sNoBPKB.'  </td></tr>
			<tr height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
		';
		break;	
	case '06':	//c	
		if ($doCari != 1){
			$sTingkat = cmb2D_v2('konsTingkat', $konsTingkat, $Main->Tingkat, '','--- Semua ---');
			$sBeton = cmb2D_v2('konsBeton', $konsBeton, $Main->Beton, '','--- Semua ---');
			$sAlamat = '<textarea rows="2" cols="60" name="alamat" value="'.$alamat.'" >'.$alamat.'</textarea>';
			$sKota = selKabKota(selKabKota, $selKabKota);
			$sNoDok =  '<input name="dokumen_no" type="text" value="'.$dokumen_no.'"> ';
			$sStatTanah = cmb2D_v2('status_tanah', $status_tanah, $Main->StatusTanah, '','--- Semua ---');
			$sNoTanah = '<input name="kode_tanah" type="text" value="'.$kode_tanah.'"> ';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else{
			$sTingkat = '<b>'.$Main->Tingkat[$konsTingkat-1][1].' <input type="hidden" name="konsTingkat" value="'.$konsTingkat.'" > ';	
			$sBeton = '<b>'.$Main->Beton[$konsBeton-1][1].' <input type="hidden" name="konsBeton" value="'.$konsBeton.'" > ';	
			$sAlamat = '<b>'.$alamat.' <input type="hidden" name="alamat" value="'.$alamat.'" > ';
			if ($selKabKota != ''){
				$sKota = table_get_value("select * from ref_wilayah where a='10' and b='".$selKabKota."' order by nm_wilayah","nm_wilayah");	
			}else{
				$sKota = '';	
			}			
			$sKota = '<b>'.$sKota.' <input type="hidden" name="selKabKota" value="'.$selKabKota.'" > ';	
			$sNoDok = '<b>'.$dokumen_no.' <input type="hidden" name="dokumen_no" value="'.$dokumen_no.'" > ';			
			$sStatTanah = '<b>'.$Main->StatusTanah[$status_tanah-1][1].' <input type="hidden" name="status_tanah" value="'.$status_tanah.'" > ';	
			$sNoTanah = '<b>'.$kode_tanah.' <input type="hidden" name="kode_tanah" value="'.$kode_tanah.'" > ';			
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';			
		}	
		$hsl = '							
			<tr height=24><td width="100" height="24" colspan="3">Konstruksi Bangunan</td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Bertingkat/Tidak</td><td width="10">:</td>
				<td>'.$sTingkat.'</td>
			</tr>
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Beton/Tidak</td><td width="10">:</td>
				<td>'.$sBeton.'</td>
			</tr>
			<tr valign="top"> <td width="100">Alamat</td><td width="10">:</td><td>
				'.$sAlamat.'  </td></tr>
			<tr height=24><td width="100">Kota/Kabupaten</td><td width="10">:</td><td> '.$sKota.'  </td></tr>				
			<tr height=24><td width="100">No. Dokumen</td><td width="10">:</td><td>'.$sNoDok.' </td></tr>
			<tr height=24><td width="100">Status Tanah</td><td width="10">:</td>
				<td>'.$sStatTanah.'</td>
			</tr>
			<tr height=24><td width="100">No. Kode Tanah</td><td width="10">:</td><td>'.$sNoTanah.' </td></tr>
			<tr  height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
			
		';
		break;		
	case '07':	//d
		if ($doCari != 1){
			$sKonst = '<input name="konstruksi" type="text" value="'.$konstruksi.'"> ';
			$sAlamat = '<textarea rows="2" cols="60" name="alamat" value="'.$alamat.'" >'.$alamat.'</textarea>';
			$sKota = selKabKota(selKabKota, $selKabKota);
			$sNoDok = '<input name="dokumen_no" type="text" value="'.$dokumen_no.'"> ';
			$sStatTanah = cmb2D_v2('status_tanah', $status_tanah, $Main->StatusTanah, '','--- Semua ---');
			$sNoTanah = '<input name="kode_tanah" type="text" value="'.$kode_tanah.'">';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else{
			$sKonst = '<b>'.$konstruksi.' <input type="hidden" name="konstruksi" value="'.$konstruksi.'" > ';	
			$sAlamat = '<b>'.$alamat.' <input type="hidden" name="alamat" value="'.$alamat.'" > ';
			if ($selKabKota != ''){
				$sKota = table_get_value("select * from ref_wilayah where a='10' and b='".$selKabKota."' order by nm_wilayah","nm_wilayah");	
			}else{
				$sKota = '';	
			}			
			$sKota = '<b>'.$sKota.' <input type="hidden" name="selKabKota" value="'.$selKabKota.'" > ';	
			$sNoDok = '<b>'.$dokumen_no.' <input type="hidden" name="dokumen_no" value="'.$dokumen_no.'" > ';			
			$sStatTanah = '<b>'.$Main->StatusTanah[$status_tanah-1][1].' <input type="hidden" name="status_tanah" value="'.$status_tanah.'" > ';	
			$sNoTanah = '<b>'.$kode_tanah.' <input type="hidden" name="kode_tanah" value="'.$kode_tanah.'" > ';			
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';					
		}
		$hsl = '
			<tr height=24><td width="100">Konstruksi</td><td width="10">:</td><td>'.$sKonst.' </td></tr>
			<tr height=24 valign="top"><td width="100">Alamat</td><td width="10">:</td><td>
				'.$sAlamat.'  </td></tr>
			<tr height=24><td width="100">Kota/Kabupaten</td><td width="10">:</td><td> '.$sKota.'  </td></tr>				
			<tr height=24><td width="100">No. Dokumen</td><td width="10">:</td><td>'.$sNoDok.'</td></tr>
			<tr height=24><td width="100">Status Tanah</td><td width="10">:</td>
				<td>'.$sStatTanah.'</td>
			</tr>
			<tr height=24><td width="100">No. Kode Tanah</td><td width="10">:</td><td>'.$sNoTanah.' </td></tr>
			<tr height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
		';
		
		break;
	case '08':	//e	
		if ($doCari != 1){
			$sJudul = '<input name="buku_judul" type="text" value="'.$buku_judul.'">';
			$sBukuSpek = '<input name="buku_spesifikasi" type="text" value="'.$buku_spesifikasi.'">';
			$sAsalDaerah = '<input name="seni_asal_daerah" type="text" value="'.$seni_asal_daerah.'">';
			$sPencipta = '<input name="seni_pencipta" type="text" value="'.$seni_pencipta.'">';
			$sSeniBahan = '<input name="seni_bahan" type="text" value="'.$seni_bahan.'">';
			$sHewanJenis = '<input name="hewan_jenis" type="text" value="'.$hewan_jenis.'"> ';
			$sHewanUk = '<input name="hewan_ukuran" type="text" value="'.$hewan_ukuran.'">';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else{
			$sJudul = '<b>'.$buku_judul.' <input type="hidden" name="buku_judul" value="'.$buku_judul.'" > ';			
			$sBukuSpek = '<b>'.$buku_spesifikasi.' <input type="hidden" name="buku_spesifikasi" value="'.$buku_spesifikasi.'" > ';			
			$sAsalDaerah = '<b>'.$seni_asal_daerah.' <input type="hidden" name="seni_asal_daerah" value="'.$seni_asal_daerah.'" > ';			
			$sPencipta = '<b>'.$seni_pencipta.' <input type="hidden" name="seni_pencipta" value="'.$seni_pencipta.'" > ';			
			$sSeniBahan = '<b>'.$seni_bahan.' <input type="hidden" name="seni_bahan" value="'.$seni_bahan.'" > ';			
			$sHewanJenis = '<b>'.$hewan_jenis.' <input type="hidden" name="hewan_jenis" value="'.$hewan_jenis.'" > ';			
			$sHewanUk = '<b>'.$hewan_ukuran.' <input type="hidden" name="hewan_ukuran" value="'.$hewan_ukuran.'" > ';
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';
		}	
		$hsl = '	
			<tr height=24><td width="100" height="24">Buku Perpustakaan</td><td width="10"></td><td></td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Judul/Pencipta</td><td width="10">:</td><td>'.$sJudul.' </td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Spesifikasi</td><td width="10">:</td><td>'.$sBukuSpek.' </td></tr>	
			
			<tr height=24><td width="300" height="24" colspan="3">Barang bercorak Kesenian/Kebudayaan</td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Asal Daerah</td><td width="10">:</td><td>'.$sAsalDaerah.' </td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Pencipta</td><td width="10">:</td><td>'.$sPencipta.' </td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Bahan</td><td width="10">:</td><td>'.$sSeniBahan.' </td></tr>	
			
			<tr height=24><td width="100" height="24">Hewan Ternak</td><td width="10"></td><td></td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Jenis</td><td width="10">:</td><td>'.$sHewanJenis.'</td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Ukuran</td><td width="10">:</td><td>'.$sHewanUk.' </td></tr>	
			<tr height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
		';
		break;
	case '09':	//f
		if ($doCari != 1){
			$sBangunan = cmb2D_v2('bangunan', $bangunan, $Main->Bangunan, '','--- Semua ---');
			$sTingkat = cmb2D_v2('konsTingkat', $konsTingkat, $Main->Tingkat, '','--- Semua ---');
			$sBeton = cmb2D_v2('konsBeton', $konsBeton, $Main->Beton, '','--- Semua ---');
			$sAlamat = '<textarea rows="2" cols="60" name="alamat" value="'.$alamat.'" >'.$alamat.'</textarea>';
			$sKota = selKabKota(selKabKota, $selKabKota);
			$sNoDok = '<input name="dokumen_no" type="text" value="'.$dokumen_no.'">';
			$sStatTanah = cmb2D_v2('status_tanah', $status_tanah, $Main->StatusTanah, '','--- Semua ---');
			$sNoTanah = '<input name="kode_tanah" type="text" value="'.$kode_tanah.'">';
			$sKet = '<textarea rows="2" cols="60" name="keterangan"  >'.$keterangan.'</textarea>';
		}else{
			$sBangunan = '<b>'.$Main->Bangunan[$bangunan-1][1].' <input type="hidden" name="bangunan" value="'.$bangunan.'" > ';	
			$sTingkat = '<b>'.$Main->Tingkat[$konsTingkat-1][1].' <input type="hidden" name="konsTingkat" value="'.$konsTingkat.'" > ';	
			$sBeton = '<b>'.$Main->Beton[$konsBeton-1][1].' <input type="hidden" name="konsBeton" value="'.$konsBeton.'" > ';	
			$sAlamat = '<b>'.$alamat.' <input type="hidden" name="alamat" value="'.$alamat.'" > ';
			if ($selKabKota != ''){
				$sKota = table_get_value("select * from ref_wilayah where a='10' and b='".$selKabKota."' order by nm_wilayah","nm_wilayah");	
			}else{
				$sKota = '';	
			}			
			$sKota = '<b>'.$sKota.' <input type="hidden" name="selKabKota" value="'.$selKabKota.'" > ';	
			$sNoDok = '<b>'.$dokumen_no.' <input type="hidden" name="dokumen_no" value="'.$dokumen_no.'" > ';			
			$sStatTanah = '<b>'.$Main->StatusTanah[$status_tanah-1][1].' <input type="hidden" name="status_tanah" value="'.$status_tanah.'" > ';	
			$sNoTanah = '<b>'.$kode_tanah.' <input type="hidden" name="kode_tanah" value="'.$kode_tanah.'" > ';			
			$sKet = '<b>'.$keterangan.' <input type="hidden" name="keterangan" value="'.$keterangan.'" > ';			
		}
		$hsl = '
			<tr height=24><td width="100">Bangunan</td><td width="10">:</td>
				<td>'.$sBangunan.'</td>
			</tr>
			<tr height=24><td width="100" height="24" colspan="3">Konstruksi Bangunan</td></tr>	
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Bertingkat/Tidak</td><td width="10">:</td>
				<td>'.$sTingkat.'</td>
			</tr>
			<tr height=24><td width="100">&nbsp;&nbsp;&nbsp;Beton/Tidak</td><td width="10">:</td>
				<td>'.$sBeton.'</td>
			</tr>
			<tr height=24 valign="top"><td width="100">Alamat</td><td width="10">:</td><td>
				'.$sAlamat.'  </td></tr>
			<tr height=24><td width="100">Kota/Kabupaten</td><td width="10">:</td><td> '.$sKota.'  </td></tr>				
			<tr height=24><td width="100">No. Dokumen</td><td width="10">:</td><td>'.$sNoDok.' </td></tr>
			<tr height=24><td width="100">Status Tanah</td><td width="10">:</td>
				<td>'.$sStatTanah.'</td>
			</tr>
			<tr height=24><td width="100">No. Kode Tanah</td><td width="10">:</td><td>'.$sNoTanah.' </td></tr>
			<tr height=24 valign="top"><td width="100">Keterangan</td><td width="10">:</td><td>
				'.$sKet.'  </td></tr>
		';
		break;
		
}
	
	$view->optcaridet ='<table>'.$hsl.'</table>';


?>