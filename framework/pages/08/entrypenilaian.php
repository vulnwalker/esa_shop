<?php
$HalBI = cekPOST("HalBI",1);
$HalPNI = cekPOST("HalPNI",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPNI = " limit ".(($HalPNI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidPNI = cekPOST("cidPNI");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPENILAIAN = cekPOST("fmTANGGALPENILAIAN");
$fmNILAIBARANG = cekPOST("fmNILAIBARANG");
$fmPENILAIINSTANSI = cekPOST("fmPENILAIINSTANSI");
$fmPENILAIALAMAT = cekPOST("fmPENILAIALAMAT");
$fmSURATNOMOR = cekPOST("fmSURATNOMOR");
$fmSURATTANGGAL = cekPOST("fmSURATTANGGAL");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


if(($Act=="TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidPNI) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidPNI) == 1 ? " disabled ":"";

//ProsesCekField
$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPENILAIAN,fmNILAIBARANG,fmPENILAIINSTANSI,fmPENILAIALAMAT,fmSURATNOMOR,fmSURATTANGGAL,fmKET,fmTAHUNANGGARAN";

if($Act=="Simpan"){
	$err = '';
	//if(ProsesCekField($MyField)){
	
		$ArBarang = explode(".",$fmIDBARANG);
		$ArWILSKPD = explode(".",$fmWILSKPD);
		$Simpan = false;
		
		if($Baru=="1")
		{//Simpan Baru
			$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPENILAIAN,fmNILAIBARANG,fmPENILAIINSTANSI,fmPENILAIALAMAT,fmSURATNOMOR,fmSURATTANGGAL,fmKET,fmTAHUNANGGARAN";

			//if($err=='' && ProsesCekField($MyField) == FALSE) $err = "Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan";
			//$err .='tes ';
			if($err==''){
				$nilai_barang_asal=0;
				//$err .= 'tes2 ';
				$bi= mysql_fetch_array(mysql_query("select * from buku_induk where id='$fmIDBUKUINDUK'"));			
				$nilai_barang_asal += $bi['harga'];
				$get= mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as tot from pemeliharaan where idbi_awal ='".$bi['idawal']."' and tambah_aset=1 and tgl_pemeliharaan<='".TglSQL($fmTANGGALPENILAIAN)."' "));
				$nilai_barang_asal += $get['tot'];
				$get= mysql_fetch_array(mysql_query("select sum(biaya_pengamanan) as tot from pengamanan where idbi_awal ='".$bi['idawal']."' and tambah_aset=1 and tgl_pengamanan<='".TglSQL($fmTANGGALPENILAIAN)."' "));
				$nilai_barang_asal += $get['tot'];
				$get= mysql_fetch_array(mysql_query("select sum(-harga_hapus) as tot from penghapusan_sebagian where idbi_awal ='".$bi['idawal']."' and tgl_penghapusan<='".TglSQL($fmTANGGALPENILAIAN)."' "));
				$nilai_barang_asal += $get['tot'];
				$get= mysql_fetch_array(mysql_query("select sum(harga_baru-harga) as tot from t_koreksi where idbi_awal ='".$bi['idawal']."' and tgl<='".TglSQL($fmTANGGALPENILAIAN)."' "));
				$nilai_barang_asal += $get['tot'];
				$get= mysql_fetch_array(mysql_query("select sum(nilai_barang-nilai_barang_asal) as tot from penilaian where idbi_awal ='".$bi['idawal']."' and tgl_penilaian<='".TglSQL($fmTANGGALPENILAIAN)."' "));
				$nilai_barang_asal += $get['tot'];
				
				$aqry = "insert into penilaian (a1,a,b,c,d,e,e1, ".
					"f,g,h,i,j, ".
					"id_bukuinduk,noreg,	thn_perolehan,tgl_penilaian,nilai_barang, ".
					"penilai_instansi,penilai_alamat,surat_no,surat_tgl,ket,tahun, ".
					"staset,idbi_awal,nilai_barang_asal) ".
					"values ('".$bi['a1']."','".$bi['a']."','".$bi['b']."','".$bi['c']."','".$bi['d']."','".$bi['e']."','".$bi['e1']."', ".
					"'".$bi['f']."','".$bi['g']."','".$bi['h']."','".$bi['i']."','".$bi['j']."', ".
					"'$fmIDBUKUINDUK','$fmNOREG','$fmTAHUNPEROLEHAN','".TglSQL($fmTANGGALPENILAIAN)."','$fmNILAIBARANG', ".
					"'$fmPENILAIINSTANSI','$fmPENILAIALAMAT','$fmSURATNOMOR','".TglSQL($fmSURATTANGGAL)."','$fmKET','$fmTAHUNANGGARAN', ".
					"'".$bi['staset']."','".$bi['idawal']."','$nilai_barang_asal')";
				
				$cek .= $aqry;
				$Simpan = mysql_query($aqry);
				$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPENILAIAN)."','Entry Penilaian','$fmKONDISIBARANG','$fmSTATUSBARANG')");
			}
		}
		if($Baru=="0"){
			if($err=='' && ProsesCekField($MyField) == FALSE) $err = "Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan";
			
			if($err==''){
				$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPENILAIAN,fmNILAIBARANG,fmPENILAIINSTANSI,fmPENILAIALAMAT,fmSURATNOMOR,fmSURATTANGGAL,fmKET,fmTAHUNANGGARAN";
				$Kriteria = "id='$fmID'";
				$Qry = "
				update penilaian set ".
					//" tgl_penilaian = '".TglSQL($fmTANGGALPENILAIAN)."',".
					//" nilai_barang = '$fmNILAIBARANG', ".
					" penilai_instansi = '$fmPENILAIINSTANSI', penilai_alamat = '$fmPENILAIALAMAT', surat_no = '$fmSURATNOMOR', surat_tgl = '".TglSQL($fmSURATTANGGAL)."', ket = '$fmKET'
				where $Kriteria ";
				//echo $Qry;
				$Simpan = mysql_query($Qry);
				$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPENILAIAN)."','Update Penilaian','$fmKONDISIBARANG','$fmSTATUSBARANG')");
			}
		}
		
		/*if($Simpan)
		{
			//KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
			$Info = "<script>alert('Data telah di ubah dan simpan')</script>";
			$Baru="0";
		}
		else
		{
			$Info = "<script>alert('Data TIDAK dapat di ubah atau di simpan $err')</script>";
		}
		*/
	//}
	//else
	//{
	//	$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan')</script>";
	//}
	
	
	if($err =='' ){
		$Info = "<script>alert('Data telah di ubah dan simpan')</script>";
		$Baru="0";
	} else {
		$Info = "<script>alert('$err')</script>";	
	}
	 
	
}


//Proses EDIT

$cidBI = CekPOST("cidBI");
$cidNya = $cidBI;
if($Act == "Edit")
{
	$cidPNI = CekPOST("cidPNI");
	$cidNya = $cidPNI;
}

if($Act=="Edit"|| $Act == "TambahEdit")
{
	
	if(count($cidNya) != 1)
	{
		$Info = "<script>alert('Pilih hanya satu ID yang dapat di Ubah')</script>";
	}
	else
	{
		if($Act=="Edit")
		{
			$Qry = mysql_query("select * from penilaian where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from buku_induk where buku_induk.id='{$cidNya[0]}'");
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
		$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmTAHUNANGGARAN = "{$isi['tahun']}";
		$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
		$fmNOREG = $isi['noreg'];
		if($Act=="Edit")
		{
			$fmIDBUKUINDUK=$isi['id_bukuinduk'];
		}
		else
		{
			$fmIDBUKUINDUK=$isi['id'];
		}
		$fmID = "{$isi['id']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
		}
		else
		{
			
			$fmTANGGALPENILAIAN = TglInd($isi['tgl_penilaian']);
			$fmNILAIBARANG = $isi['nilai_barang'];
			$fmPENILAIINSTANSI = $isi['penilai_instansi'];
			$fmPENILAIALAMAT = $isi['penilai_alamat'];
			$fmSURATNOMOR = $isi['surat_no'];
			$fmSURATTANGGAL = TglInd($isi['surat_tgl']);
			$fmBIAYA = $isi['biaya_penilaian'];
			$fmKET = $isi['ket'];

			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidPNI = cekPOST("cidPNI");
if($Act=="Hapus" && count($cidPNI) > 0)
{
	for($i = 0; $i<count($cidPNI); $i++)
	{
		//$cekIdBI = mysql_fetch_array(mysql_query("select id_bukuinduk from penilaian where id='{$cidPNI[$i]}'"));$cekIdBI = $cekIdBI[0];
		$Del = mysql_query("delete from penilaian where id='{$cidPNI[$i]}' limit 1");
		//$UpdateBI = mysql_query("update buku_induk set status_barang='1' where id='$cekIdBI'");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}





//LIST BI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1' ";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang using(f,g,h,i,j) where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$jmlDataBI = mysql_num_rows($Qry);
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBI");


$ListBarang = "";
$JmlTotalHargaListBI = 0;
$no=$Main->PagePerHal * (($HalBI*1) - 1);
$cb=0;
$jmlTampilBI = 0;
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilBI++;
	$JmlTotalHargaListBI += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarang .= "
	<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilBI, 'cb', this);
			adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']} {$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>
			<td class=\"GarisDaftar\">".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>
			<td class=\"GarisDaftar\">".$Main->StatusBarang[$isi['status_barang']-1][1]."</td>
		</tr>

		";
	$cb++;
}
$ListBarang .= "
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($JmlTotalHargaListBI, 2, ',', '.')." </td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')." </td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
";
		
//ENDLIST BI

//LIST PNI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIPNI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIPNI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and penilaian.thn_perolehan = '$fmTahunPerolehan' ";
}

$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataPNI = mysql_num_rows($Qry);
$Qry = mysql_query("select penilaian.*,ref_barang.nm_barang from penilaian inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalPNI");

// copy untuk kondisi jumlah total
$KondisiTotalPNI = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHargaPNI = mysql_query("select sum(nilai_barang) as total  from penilaian where $KondisiTotalPNI ");

if($jmlTotalHargaPNI = mysql_fetch_array($jmlTotalHargaPNI))
{
	$jmlTotalHargaPNI = $jmlTotalHargaPNI[0];
}
else
{$jmlTotalHargaPNI=0;}
// copy untuk kondisi jumlah total sampai sini

$no=$Main->PagePerHal * (($HalPNI*1) - 1);
$cb=0;
$jmlTampilPNI = 0;
$jmlHargaPerHalPNI = 0;
$ListBarangPNI = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilPNI++;
	$no++;
	$jmlHargaPerHalPNI += $isi['nilai_barang'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangPNI .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" id=\"cbPNI$cb\" name=\"cidPNI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_penilaian'])."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['nilai_barang'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">{$isi['penilai_instansi']}</td>
			<td class=\"GarisDaftar\">{$isi['penilai_alamat']}</td>
			<td class=\"GarisDaftar\">{$isi['surat_no']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['surat_tgl'])."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangPNI .= "
		<tr class='row0'>
			<td colspan=7 class=\"GarisDaftar\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlHargaPerHalPNI, 2, ',', '.')."</td>
			<td colspan=5  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=7 >Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaPNI, 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" colspan=5 >&nbsp;</td>
		</tr>
		";
//$ListBarangPNI .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListPNI, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST PNI


$Main->Isi = "
<A Name=\"ISIAN\"></A>

$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Penilaian Barang Milik Daerah </th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
	".WilSKPD1()."

<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR INVENTARIS BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input type=text name='fmBARANGCARI' value='$fmBARANGCARI'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\"> &nbsp;".TahunPerolehan()."
	</td>
	<!-- <td align=right width=30%>

			<table border=0 cellpadding=0 cellspacing=0>
			<tr >
				<td><input type=button onclick=\"javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit();\" value=\"Tambah\"></td>
			</tr>
			</table>
	</td> -->

	</tr>
	</table>

<!-PNI-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH class=\"GarisDaftar\">No</TD>
		<TH class=\"GarisDaftar\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilPLH,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH class=\"GarisDaftar\">Kode Barang</TH>
		<TH class=\"GarisDaftar\">No.<br>Register</TH>
		<TH class=\"GarisDaftar\">Nama Barang</TH>
		<TH class=\"GarisDaftar\">Tahun<br>Perolehan</TH>
		<TH class=\"GarisDaftar\">Jumlah</TH>
		<TH class=\"GarisDaftar\">Harga Satuan <br>(Rp)</TH>
		<TH class=\"GarisDaftar\">Jumlah Harga <br>(Rp)</TH>
		<TH class=\"GarisDaftar\">Asal Usul</TH>
		<TH class=\"GarisDaftar\">Kondisi</TH>
		<TH class=\"GarisDaftar\">Status<br>Barang</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=12 align=center>
	".Halaman($jmlDataBI,$Main->PagePerHal,"HalBI")."
	</td>
	</tr>
<!-- 	
	<tr>
	<td colspan=9>
	<table width=100% class=\"menubar\">
		<tr>
			<td class=\"menudottedline\">
			<table border=0 cellpadding=0 cellspacing=0>
			<tr class=\"menudottedline\">
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()","edit_f2.png","Tambah")."</td>
			</tr>
			</table>
			</td>
		</tr>
	</table> 
	</td>
	</tr>
-->
	</TABLE>
<!-END BI--><br>


<!-PNI-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENILAIAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIPNI' value='$fmBARANGCARIPNI'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH>No</TD>
		<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataPNI,'cbPNI','toggle2');\" /></TD>
		<TH>Kode Barang</TH>
		<TH>No.<br>Register</TH>
		<TH>Nama Barang</TH>
		<TH>Tahun<br>Perolehan</TH>
		<TH>Tanggal<br>Penilaian</TH>
		<TH>Nilai<br>Barang</TH>
		<TH>Instansi</TH>
		<TH>Alamat</TH>
		<TH>Nomor</TH>
		<TH>Tanggal</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarangPNI
	<tr>
	<td colspan=16 align=center>
	".Halaman($jmlDataPNI,$Main->PagePerHal,"HalPNI")."
	</td>
	</tr>
	</table>
<br>
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>-->
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Edit';adminForm.submit()","edit_f2.png","Ubah")."</td>
			<td>".PanelIcon1("javascript:if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){adminForm.Act.value='Hapus';adminForm.submit();}","delete_f2.png","Hapus")."</td>
			<!-- <td>".PanelIcon1("javascript:adminForm.Act.value='Cetak';adminForm.submit()","print_f2.png","Cetak")."</td> -->
			</tr></table>
		</td></tr>
	</table>
<!-END PNI-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{

	$vtgl = $Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit" || $Act=="Add" ? 
		"<tr valign=\"top\">   
			<td>Tanggal Penilaian</td>
			<td>:</td>
			<td>".InputKalender("fmTANGGALPENILAIAN")."
			</td>
		</tr>" : 
		"<tr valign=\"top\">   
			<td>Tanggal Penilaian</td>
			<td>:</td>
			<td>".($fmTANGGALPENILAIAN)."			
			</td>
		</tr>";
	
	$vnilai = $Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit" || $Act=="Add" ? 
		"<tr valign=\"top\">
			<td>Nilai Barang</td>
			<td>:</td>
			<td>".txtField('fmNILAIBARANG',$fmNILAIBARANG,'50','10','text',' ')."
			</td>
		</tr>" : 
		"<tr valign=\"top\">
			<td>Nilai Barang</td>
			<td>:</td>
			<td>".number_format($fmNILAIBARANG,2,',','.')."
			</td>
		</tr>";
	
	$Main->Isi .= 
		"<br>
		<A NAME='FORMENTRY'></A>
			<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<TR>
			<td>Nama Barang</td>
			<td>:</td>
			<td>
				".txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ')."
				".txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly ')."
			</td>
			</tr>
			<TR>
			<td>Nomor Register</td>
			<td>:</td>
			<td>
				".txtField('fmNOREG',$fmNOREG,'6','4','text',' readonly ')."
			</td>
			</tr>
				
			$vtgl
			
			$vnilai
		
			<tr valign=\"top\">
			  <td>Yang Menilai</td>
			  <td>:</td>
			  <td>
				&nbsp;
			</td>
			</tr>
				
			<tr valign=\"top\">
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Instansi/CV/PT</td>
			  <td>:</td>
			  <td>
				".txtField('fmPENILAIINSTANSI',$fmPENILAIINSTANSI,'100','50','text')."
			</td>
			</tr>
			<tr valign=\"top\">
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			  <td>:</td>
			  <td>
				".txtField('fmPENILAIALAMAT',$fmPENILAIALAMAT,'100','50','text')."
			</td>
			</tr>
				
			<tr valign=\"top\">
			  <td>Surat Perjanjian / Kontrak</td>
			  <td>:</td>
			  <td>
				&nbsp;
			</td>
			</tr>
				
			<tr valign=\"top\">
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
			  <td>:</td>
			  <td>
				".txtField('fmSURATNOMOR',$fmSURATNOMOR,'100','50','text')."
			</td>
			</tr>
			<tr valign=\"top\">
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
			  <td>:</td>
			  <td>
				".InputKalender("fmSURATTANGGAL")."
			</td>
			</tr>
			<tr valign=\"top\">
			  <td>Keterangan</td>
			  <td>:</td>
			  <td><textarea name=\"fmKET\" cols=\"60\" >$fmKET</textarea></td>
			</tr>
			</table>
		
		<br>
			<table width=\"100%\" class=\"menudottedline\">
			<tr><td>
				<table width=\"50\"><tr>
					<td>
					".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
					</td>
					<td>
					".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."
					</td>
				</tr></table>
			</td></tr>
			</table>
		";
}//END IF

$Main->Isi .= " $cek
<input type=hidden name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
<input type=hidden name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
<input type=hidden name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
<input type=hidden name='Act'>
<input type=hidden name='Baru' value='$Baru'>
</td></tr></table>
		<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
</form>
";
?>