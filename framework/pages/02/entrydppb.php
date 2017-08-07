<?php
$HalDKPB = cekPOST("HalDKPB",1);
$HalDKPPB = cekPOST("HalDKPPB",1);
$LimitHalDKPB = " limit ".(($HalDKPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDKPPB = " limit ".(($HalDKPPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL", $Main->DEF_WILAYAH);
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmIDBARANGCARI = cekPOST("fmIDBARANGCARI");
$fmNMBARANGCARI = cekPOST("fmNMBARANGCARI");
$fmMEREK = cekPOST("fmMEREK");
$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmNMREKENING = cekPOST("fmNMREKENING");
$fmKET = cekPOST("fmKET");
$fmIDBARANGCARI=cekPOST("fmIDBARANGCARI");
$fmBARANGCARI=cekPOST("fmBARANGCARI");
$fmBARANGCARIDKPPB=cekPOST("fmBARANGCARIDKPPB");
$fmNOREG = cekPOST("fmNOREG");

$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmURAIAN = cekPOST("fmURAIAN");
$fmTANGGALSPK = cekPOST("fmTANGGALSPK");
$fmNOMORSPK = cekPOST("fmNOMORSPK");
$fmPTSPK = cekPOST("fmPTSPK");
$fmTANGGALDPA = cekPOST("fmTANGGALDPA");
$fmNOMORDPA = cekPOST("fmNOMORDPA");
$fmKET = cekPOST("fmKET");
$fmWILSKPD = cekPOST("fmWILSKPD");




$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

$cidDKPPB = cekPOST("cidDKPPB");
$cidDKPB = cekPOST("cidDKPB");


if(($Act=="Edit") && !isset($cidDKPPB[0])){$Act="";}
if(($Act=="TambahEdit") && !isset($cidDKPB[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidDKPPB) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidDKPPB) == 1 ? " disabled ":"";



//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNOREG,fmNMBARANG,fmTAHUNANGGARAN,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmURAIAN,fmTANGGALSPK,fmNOMORSPK,fmPTSPK,fmTANGGALDPA,fmNOMORDPA,fmKET";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArWILSKPD = explode(".",$fmWILSKPD);
		$ArRekening = explode(".",$fmIDREKENING);
		$jmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$KondisiCek = $Main->Provinsi[0].$fmWIL.$fmSKPD.$fmUNIT.$fmSUBUNIT.$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmNOREG.$fmNOMORSPK.$fmNOMORDPA.$fmTAHUNANGGARAN;
		$CekID = false;
		$Qry = mysql_query("select * from pengadaan_pemeliharaan where concat(a,b,c,d,e,f,g,h,i,j,noreg,spk_no,dpa_no,tahun)='$KondisiCek'");
		$CekID = mysql_num_rows($Qry);
		$Simpan = false;
		if($Baru=="1")
		{
			$Qry = "insert into pengadaan_pemeliharaan (a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,tahun,noreg,spk_tgl,spk_no,dpa_tgl,dpa_no,pt,merk_barang,jml_barang,satuan,harga,jml_harga,uraian,ket)
			values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmTAHUNANGGARAN','$fmNOREG','". TglSQL($fmTANGGALSPK)."','$fmNOMORSPK','".TglSQL($fmTANGGALDPA)."','$fmNOMORDPA','$fmPTSPK','$fmMEREK','$fmJUMLAH','$fmSATUAN','$fmHARGASATUAN','$jmlHARGA','$fmURAIAN','$fmKET')";
			$Simpan = mysql_query($Qry);
			$Q = mysql_fetch_array(mysql_query("select id from pengadaan_pemeliharaan where concat(a,b,c,d,e,f,g,h,i,j,noreg,spk_no,dpa_no,tahun)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update pengadaan_pemeliharaan set spk_tgl='".TglSQL($fmTANGGALSPK)."',
			spk_no='$fmNOMORSPK',
			dpa_tgl='".TglSQL($fmTANGGALDPA)."',
			dpa_no='$fmNOMORDPA',
			pt='$fmPTSPK',
			merk_barang='$fmMEREK',
			jml_barang='$fmJUMLAH',
			satuan='$fmSATUAN',
			harga='$fmHARGASATUAN',
			jml_harga='$jmlHARGA',
			uraian='$fmURAIAN',
			ket='$fmKET'
			where $Kriteria ";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			//KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmTANGGALSPK,fmNOMORSPK,fmPTSPK,fmTANGGALDPA,fmNOMORDPA,fmKET");
			$Info = "<script>alert('Data telah di ubah dan simpan')</script>";
			$Baru="0";
		}
		else
		{
			$Info .= "<script>alert('Data TIDAK dapat di ubah atau di simpan')</script>";
		}
	}
	else
	{
		$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan')</script>";
	}
	
}


//Proses EDIT

$cidDKPB = CekPOST("cidDKPB");
$cidNya = $cidDKPB;
if($Act == "Edit")
{
	$cidDKPPB = CekPOST("cidDKPPB");
	$cidNya = $cidDKPPB;
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
			$Qry = mysql_query("select * from pengadaan_pemeliharaan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from dkpb where id='{$cidNya[0]}'");
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmMEREK = "{$isi['merk_barang']}";
		$fmJUMLAH = "{$isi['jml_barang']}";
		$fmSATUAN = "{$isi['satuan']}";
		$fmNOREG = "{$isi['noreg']}";
		$fmHARGASATUAN = "{$isi['harga']}";
		$fmURAIAN = "{$isi['uraian']}";
		$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
		$fmNMREKENING = "{$nmRekening['nm_rekening']}";
		$fmKET = "{$isi['ket']}";
		$fmID = "{$isi['id']}";
		$fmWILSKPD = $isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
		$fmSKPD = "{$isi['c']}";
		$fmUNIT = "{$isi['d']}";
		$fmSUBUNIT = "{$isi['e']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
			//$AmbilMerekKIB = mysql_fetch_array(mysql_query("select bb.spesifikasi from dkpb as aa inner join view_bi_spesifikasi as bb on concat(aa.a,aa.b,aa.c,aa.d,aa.e,aa.f,aa.g,aa.h,aa.i,aa.j,aa.noreg,aa.tahun)=concat(bb.a,bb.b,bb.c,bb.d,bb.e,bb.f,bb.g,bb.h,bb.i,bb.j,bb.noreg,bb.tahun) where aa.id='{$cidNya[0]}' "));
			$AmbilMerekKIB = mysql_fetch_array(mysql_query("select bb.spesifikasi from dkpb as aa inner join view_bi_spesifikasi as bb using(a,b,c,d,e,f,g,h,i,j,noreg,tahun) where aa.id='{$cidNya[0]}' "));
			$fmMEREK = $AmbilMerekKIB['spesifikasi'];
			//echo "select bb.spesifikasi from dkpb as aa inner join view_bi_spesifikasi as bb on concat(aa.a,aa.b,aa.c,aa.d,aa.e,aa.f,aa.g,aa.h,aa.i,aa.j,aa.noreg,aa.tahun)=concat(bb.a,bb.b,bb.c,bb.d,bb.e,bb.f,bb.g,bb.h,bb.i,bb.j,bb.noreg,bb.tahun) where aa.id='{$cidNya[0]}' ";
		}
		else
		{
			$fmTANGGALSPK=TglInd($isi['spk_tgl']);
			$fmNOMORSPK="{$isi['spk_no']}";
			$fmPTSPK="{$isi['pt']}";
			$fmTANGGALDPA=TglInd($isi['dpa_tgl']);
			$fmNOMORDPA="{$isi['dpa_no']}";
			$fmWILSKPD = $isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidDBP = CekPOST("cidDKPPB");
if($Act=="Hapus" && count($cidDBP) > 0)
{
	for($i = 0; $i<count($cidDBP); $i++)
	{
		$Del = mysql_query("delete from pengadaan_pemeliharaan where id='{$cidDBP[$i]}' limit 1");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}



if($Act=="Add")
{
		$fmIDBARANG = "";
		$fmNMBARANG = "";
		$fmMEREK = "";
		$fmJUMLAH = "";
		$fmSATUAN = "";
		$fmHARGASATUAN = "";
		$fmIDREKENING = "";
		$fmNMREKENING = "";
		$fmKET = "";
		$fmID = "";
		$Baru=1;
}



//LIST DKPB ----------------------------------------------------------------------------------------------------------
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' $KondisiC $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIDKPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDKPB%' ";
}
/*
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and dkpb.thn_perolehan = '$fmTahunPerolehan' ";
}
*/


// copy untuk kondisi jumlah total DKPB
$KondisiTotalDKPB = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHargaDKPB = mysql_query("select sum(jml_biaya) as total  from dkpb where $KondisiTotalDKPB ");

if($jmlTotalHargaDKPB = mysql_fetch_array($jmlTotalHargaDKPB))
{
	$jmlTotalHargaDKPB = $jmlTotalHargaDKPB[0];
}
else
{$jmlTotalHargaDKPB=0;}
// copy untuk kondisi jumlah total sampai sini

$cek .= "select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang using(f,g,h,i,j)  where $Kondisi order by a,b,c,d,e,f,g,h,i,j ";
$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang using(f,g,h,i,j)  where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataDKPB = mysql_num_rows($Qry);
$Qry = mysql_query("select dkpb.*,ref_barang.nm_barang from dkpb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKPB");
$no=$Main->PagePerHal * (($HalDKPB*1) - 1);
$cb=0;
$jmlTampilDKPB = 0;
$jmlTotalHargaListDKPB = 0;
$ListBarangDKPB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDKPB++;
	$jmlTotalHargaListDKPB += $isi['jml_biaya'];
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdBarang1 = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$TotalBiayaPemeliharaan = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as biaya from pemeliharaan where concat(f,g,h,i,j,noreg)='$kdBarang1' "));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDKPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" id=\"cb$cb\" name=\"cidDKPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilDKPB, 'cb', this); adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp;{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_biaya'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($TotalBiayaPemeliharaan[0], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['uraian']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKPB .= "
		<tr class='row0'>
			<td colspan=8 class=\"GarisDaftar\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalHargaListDKPB, 2, ',', '.')."</td>
			<td colspan=4  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td colspan=8 class=\"GarisDaftar\">Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDKPB, 2, ',', '.')."</td>
			<td colspan=4 class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		";

//$ListBarangDKPB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListDKPB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST DKPB

//LIST DKPPB
$KondisiD = $fmUNIT == "00" ? "":" and pengadaan_pemeliharaan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengadaan_pemeliharaan.e='$fmSUBUNIT' ";
$Kondisi = "pengadaan_pemeliharaan.a='{$Main->Provinsi[0]}' and pengadaan_pemeliharaan.b='$fmWIL' and pengadaan_pemeliharaan.c='$fmSKPD' $KondisiD $KondisiE and pengadaan_pemeliharaan.tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARIDKPPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDKPPB%' ";
}

// copy untuk kondisi jumlah total DKPPB
$KondisiTotalDKPPB = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHargaDKPPB = mysql_query("select sum(jml_harga) as total  from pengadaan_pemeliharaan where $KondisiTotalDKPPB ");

if($jmlTotalHargaDKPPB = mysql_fetch_array($jmlTotalHargaDKPPB))
{
	$jmlTotalHargaDKPPB = $jmlTotalHargaDKPPB[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengadaan_pemeliharaan where $Kondisi");
$jmlTotalHarga = mysql_query("select sum(pengadaan_pemeliharaan.jml_harga) as total  from pengadaan_pemeliharaan inner join dkpb using(f,g,h,i,j) inner join ref_barang using(f,g,h,i,j) where $Kondisi ");
//echo "select sum(pengadaan_pemeliharaan.jml_harga) as total  from pengadaan_pemeliharaan inner join dkpb on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j) = concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j) inner join ref_barang on concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ";

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDKPPB = mysql_num_rows($Qry);

$Qry = mysql_query("select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKPPB");
//echo "select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKPPB";

$ListBarangDKPPB = "";
$no=$Main->PagePerHal * (($HalDKPPB*1) - 1);
$jmlTampilDKPPB = 0;
$jmlTotalHargaListDKPPB = 0;
$cb=0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilDKPPB++;
	$jmlTotalHargaListDKPPB += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDKPPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" id=\"cbDKPPB$cb\" name=\"cidDKPPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisDaftar\">".$isi['spk_no']."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKPPB .= "
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaListDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>

";
//ENDLIST DKPPB

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Pengadaan Pemeliharaan Barang Milik Daerah </th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
	".WilSKPD()."

<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR KEBUTUHAN PEMELIHARAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>
<!-DKPPB-->

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIDKPB' value='$fmBARANGCARIDKPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" width=\"20\">No</TH>
		<TH class=\"th01\">&nbsp;</TH>
		<TH class=\"th01\" width=\"75\">Kode<br> Barang</TH>
		<TH class=\"th01\" width=\"35\">Nomor<br>Reg.</TH>
		<TH class=\"th01\">Nama Barang</TH>
		<TH class=\"th01\" width=\"55\">Tahun<br>Perolehan</TH>
		<TH class=\"th01\" width=\"40\">Jumlah</TH>
		<TH class=\"th01\" width=\"80\">Harga<br> Satuan<br>(Rp)</TH>
		<TH class=\"th01\" width=\"80\">Jumlah<br> Harga<br>(Rp)</TH>
		<TH class=\"th01\" width=\"90\">Total Biaya <br> Pemeliharaan<br>(Rp)</TH>
		<TH class=\"th01\" width=\"55\">Kode<br> Rekening</TH>
		<TH class=\"th01\">Uraian<br> Pemeliharaan</TH>
		<TH class=\"th01\">Keterangan</TH>
	</TR>
	$ListBarangDKPB
	<tr>
	<td colspan=17 align=center>
	".Halaman($jmlDataDKPB,$Main->PagePerHal,"HalDKPB")."
	</td>
	</tr>
	</table>
<br>

<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENGADAAN PEMELIHARAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">     
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input type=text name='fmBARANGCARIDKPPB' value='$fmBARANGCARIDKPPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2 width=\"20\">No</TH>
		<TH class=\"th01\" rowspan=2><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDKPPB,'cbDKPPB','toggle2');\" /></TH>
		<TH class=\"th01\" rowspan=2>Nama Barang</TH>
		<TH class=\"th01\" rowspan=2>Merk / Type / Ukuran / <br>Spesifikasi</TH>
		<TH class=\"th01\" rowspan=2 >Jumlah</TH>
		<TH class=\"th01\" rowspan=2 width=\"90\">Harga Satuan<br> (Rp)</TH>
		<TH class=\"th01\" rowspan=2 width=\"90\">Jumlah Harga<br>(Rp)</TH>
		<TH class=\"th02\" colspan=2>SPK / Perjanjian /<br> Kontrak</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\" width=\"60\">Tanggal</TH>
		<TH class=\"th01\">Nomor</TH>
	</TR>
	$ListBarangDKPPB
		<tr>
	<td class=\"GarisDaftar\" colspan=10 align=center>
	".Halaman($jmlDataDKPPB,$Main->PagePerHal,"HalDKPPB")."
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
<!-END DKPPB-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

<br>
<br>
<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr valign=\"top\">   
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG")."
	</td>
	</tr>

	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Merk / Type / Ukuran / Spesifikasi</td>
	<td width=\"33\">:</td>
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\" readonly>$fmMEREK</textarea></td>
	</tr>
	

	<tr valign=\"top\">
		<td>Jumlah Barang </td>
		<td>:</td>
		<td><input name=\"fmJUMLAH\" type=\"text\" value=\"$fmJUMLAH\" />
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Satuan&nbsp;&nbsp;
		  <input name=\"fmSATUAN\" type=\"text\" size=\"10\" value=\"$fmSATUAN\" />
		  </td>
	</tr>

	<tr valign=\"top\">
	  <td>Harga Satuan </td>
	  <td>:</td>
	  <td>Rp. 
		".inputFormatRibuan("fmHARGASATUAN")."</td>
	</tr>

	<tr valign=\"top\">
	  <td>Uraian Pemeliharaan</td>
	  <td>:</td>
	  <td><textarea name=\"fmURAIAN\" cols=\"60\" >$fmURAIAN</textarea></td>
	</tr>

	<tr valign=\"top\">
	  <td>SPK/Perjanjian/Kontrak</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALSPK")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORSPK',$fmNOMORSPK,'100','20','text','')."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;PT/CV</td>
	<td>:</td>
	<td>
		".txtField('fmPTSPK',$fmPTSPK,'100','20','text','')."
	</td>
	</tr>

	<tr valign=\"top\">
	  <td>DPA/SPM/Kwitansi</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALDPA")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORDPA',$fmNOMORDPA,'100','20','text','')."
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

$cek= '';
$Main->Isi .= $cek."

<input type=hidden name='fmIDREKENING' value='$fmIDREKENING'>
<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
<input type=hidden name='fmNOREG' value='$fmNOREG'>
<input type=hidden name='Act'>
<input type=hidden name='Baru' value='$Baru'>

<script language='javascript'>
function prosesBaru()
{
	//fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN
	adminForm.Baru.value = '1';
	adminForm.fmIDBARANG.value = '';
	adminForm.fmNMBARANG.value = '';
	adminForm.fmMEREK.value = '';
	adminForm.fmJUMLAH.value = '';
	adminForm.fmSATUAN.value = '';
	adminForm.fmHARGASATUAN.value = '';
	adminForm.fmIDREKENING.value = '';
	adminForm.fmNMREKENING.value = '';
	adminForm.fmKET.value = '';
	//adminForm.Submit()
}
</script>
</td></tr></table>
		<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
</form>



";
?>