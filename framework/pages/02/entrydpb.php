<?php
$HalDKB = cekPOST("HalDKB",1);
$HalDPB = cekPOST("HalDPB",1);
$LimitHalDKB = " limit ".(($HalDKB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDPB = " limit ".(($HalDPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL", $Main->DEF_WILAYAH);
$fmSKPD = cekPOST("fmSKPD"); $cek .= '$fmSKPD='.$fmSKPD; 
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();
$cek .= '$fmSKPD2='.$fmSKPD;

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
$fmIDBARANGCARI= cekPOST("fmIDBARANGCARI");
$fmBARANGCARI= cekPOST("fmBARANGCARI");
$fmBARANGCARIDPB= cekPOST("fmBARANGCARIDPB");


$fmUNITGUNAKAN=cekPOST("fmUNITGUNAKAN");
$fmSUBUNITGUNAKAN=cekPOST("fmSUBUNITGUNAKAN");
$fmTANGGALSPK=cekPOST("fmTANGGALSPK");
$fmNOMORSPK=cekPOST("fmNOMORSPK");
$fmPTSPK=cekPOST("fmPTSPK");
$fmTANGGALDPA=cekPOST("fmTANGGALDPA");
$fmNOMORDPA=cekPOST("fmNOMORDPA");


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


$cidDPB=cekPOST("cidDPB");
$cidDKB=cekPOST("cidDKB");

if(($Act=="Edit") && !isset($cidDPB[0])){$Act="";}
if(($Act=="TambahEdit") && !isset($cidDKB[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidDPB) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidDPB) == 1 ? " disabled ":"";



//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmTANGGALSPK,fmNOMORSPK,fmPTSPK,fmTANGGALDPA,fmNOMORDPA,fmKET,fmTAHUNANGGARAN";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$jmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$KondisiCek = $Main->Provinsi[0].$fmWIL.$fmSKPD.$fmUNIT.$fmSUBUNIT.$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmTAHUNANGGARAN;
		$CekID = false;
		$Qry = mysql_query("select * from pengadaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'");
		$CekID = mysql_num_rows($Qry);
		//echo $CekID;
		//echo "select * from pengadaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'";
		/*
		if($CekID > 0 && $Baru=="1")
			{
				//$CekID = false;
				$Info = "<script>alert('Data Barang sudah ada, data tidak dapat disimpan')</script>";
			}
		*/
		$Simpan = false;
		
		//if($Baru=="1" && $CekID < 1)
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into pengadaan (a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,merk_barang,pt,spk_tgl,spk_no,dpa_tgl,dpa_no,jml_barang,harga,satuan,jml_harga,ket,tahun)
			values ('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmMEREK','$fmPTSPK','". TglSQL($fmTANGGALSPK)."','$fmNOMORSPK','".TglSQL($fmTANGGALDPA)."','$fmNOMORDPA','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$jmlHARGA','$fmKET','$fmTAHUNANGGARAN')";
			$Simpan = mysql_query($Qry);
			$Q = mysql_fetch_array(mysql_query("select id from pengadaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update pengadaan set 
				merk_barang='$fmMEREK',jml_barang='$fmJUMLAH',harga='$fmHARGASATUAN',satuan='$fmSATUAN',jml_harga='$jmlHARGA',ket='$fmKET',pt='$fmPTSPK',spk_tgl='". TglSQL($fmTANGGALSPK)."',spk_no='$fmNOMORSPK',dpa_tgl='".TglSQL($fmTANGGALDPA)."',dpa_no='$fmNOMORDPA'
			where $Kriteria ";
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmTANGGALSPK,fmNOMORSPK,fmPTSPK,fmTANGGALDPA,fmNOMORDPA,fmKET");
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

$cidDKB = CekPOST("cidDKB");
$cidNya = $cidDKB;
if($Act == "Edit")
{
	$cidDPB = CekPOST("cidDPB");
	$cidNya = $cidDPB;
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
			$Qry = mysql_query("select * from pengadaan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from dkb where id='{$cidNya[0]}'");
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
		$fmHARGASATUAN = "{$isi['harga']}";
		$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
		$fmNMREKENING = "{$nmRekening['nm_rekening']}";
		$fmKET = "{$isi['ket']}";
		$fmID = "{$isi['id']}";

		$fmSKPD = "{$isi['c']}";
		$fmUNIT = "{$isi['d']}";
		$fmSUBUNIT = "{$isi['e']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
		}
		else
		{
			$fmTANGGALSPK=TglInd($isi['spk_tgl']);
			$fmNOMORSPK="{$isi['spk_no']}";
			$fmPTSPK="{$isi['pt']}";
			$fmTANGGALDPA="{$isi['dpa_tgl']}";
			$fmNOMORDPA="{$isi['dpa_no']}";

			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidDBP = CekPOST("cidDPB");
if($Act=="Hapus" && count($cidDBP) > 0)
{
	for($i = 0; $i<count($cidDBP); $i++)
	{
		$Del = mysql_query("delete from pengadaan where id='{$cidDBP[$i]}' limit 1");
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


//LIST DKB -------------------------------------------------------------------------------------------------
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' $KondisiC $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' "; 
}



//LIST DKB
//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from dkb where $Kondisi");
//$jmlTotalHargaDKB = mysql_query("select sum(dkb.jml_harga) as total  from dkb inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ");
$jmlTotalHargaDKB = mysql_query("select sum(dkb.jml_harga) as total  from dkb inner join ref_barang using(f,g,h,i,j) where $Kondisi ");

if($jmlTotalHargaDKB = mysql_fetch_array($jmlTotalHargaDKB))
{
	$jmlTotalHargaDKB = $jmlTotalHargaDKB[0];
}
else
{$jmlTotalHargaDKB=0;}

//echo "select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
//$Qry = mysql_query("select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$cek .= "select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDKB = mysql_num_rows($Qry);

//$Qry = mysql_query("select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKB");
$Qry = mysql_query("select dkb.*,ref_barang.nm_barang from dkb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDKB");

$ListBarangDKB = "";
$no=$Main->PagePerHal * (($HalDKB*1) - 1);
$jmlTampilDKB = 0;
$jmlTotalHargaListDKB = 0;
$cb=0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilDKB++;
	$jmlTotalHargaListDKB += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDKB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\"  align=center width=\"15\"><input type=\"checkbox\" id=\"cb$cb\" name=\"cidDKB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilDKB, 'cb', this); adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"60\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"100\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKB .= "
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaListDKB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDKB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td>
</tr>

";
//ENDLIST DKB

//LIST DPB
$KondisiD = $fmUNIT == "00" ? "":" and pengadaan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengadaan.e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and pengadaan.c='$fmSKPD' ";
$Kondisi = "pengadaan.a='{$Main->Provinsi[0]}' and pengadaan.b='$fmWIL' $KondisiC $KondisiD $KondisiE and pengadaan.tahun='$fmTAHUNANGGARAN'";
$KondisiDPB = $Kondisi;
if(!empty($fmBARANGCARIDPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPB%' ";
}

$jmlTotalHargaDPB = mysql_query("select sum(jml_harga) as total from pengadaan where $KondisiDPB");
//$jmlTotalHarga = mysql_query("select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j) = concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j) inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $KondisiDPB ");

//echo "select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j) = concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j) inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ";

if($jmlTotalHargaDPB = mysql_fetch_array($jmlTotalHargaDPB))
{
	$jmlTotalHargaDPB = $jmlTotalHargaDPB[0];
}
else
{$jmlTotalHargaDPB=0;}

//echo "select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
//$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");

$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPB = mysql_num_rows($Qry);

//$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPB");
$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPB");
$ListBarangDPB = "";
$no=$Main->PagePerHal * (($HalDPB*1) - 1);
$jmlTampilDPB = 0;
$jmlTotalHargaListDPB = 0;
$cb=0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilDKB++;
	$jmlTotalHargaListDPB += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cbDPB$cb\" name=\"cidDPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"60\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"100\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center style=\"width:60;\">".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisDaftar\" align=left>".$isi['spk_no']."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPB .= "
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaListDPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>
";

//ENDLIST DPB

$Main->Isi = //$cek. 
"<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Pengadaan Barang Milik Daerah</th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">
	".
	WilSKPD()."

<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR KEBUTUHAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">    
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input type=text name='fmBARANGCARI' value='$fmBARANGCARI'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
	</td>
	<td align=right width=30%>

			<!-- <table border=0 cellpadding=0 cellspacing=0>
			<tr >
				<td><input type=button onclick=\"javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit();\" value=\"Tambah\"></td>
			</tr>
			</table> -->
	</td>

	</tr>
	</table>

<!-DKB-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlData,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"160\">Merk / Type / Ukuran / <br>Spesifikasi</TH>
		<TH width=\"60\">Jumlah</TH>
		<TH width=\"90\">Harga Satuan<br>(Rp)</TH>
		<TH width=\"100\">Jumlah Harga<br>(Rp)</TH>
		<TH>Kode Rekening</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarangDKB
	<tr>
	<td class=\"GarisDaftar\" colspan=9 align=center>
	".Halaman($jmlDataDKB,$Main->PagePerHal,"HalDKB")."
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
<!-END DKB--><br>


<!-DPB-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENGADAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">     
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input type=text name='fmBARANGCARIDPB' value='$fmBARANGCARIDPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2 width=\"15\">No</TD>
		<TH class=\"th01\" rowspan=2 width=\"15\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPB,'cbDPB','toggle2');\" /></TD>
		<TH class=\"th01\" rowspan=2 width=\"180\">Nama Barang</TH>
		<TH class=\"th01\" rowspan=2 width=\"160\">Merk / Type / Ukuran / <br>Spesifikasi</TH>
		<TH class=\"th01\" rowspan=2 width=\"60\">Jumlah</TH>
		<TH class=\"th01\" rowspan=2 width=\"90\">Harga Satuan<br> (Rp)</TH>
		<TH class=\"th01\" rowspan=2 width=\"100\">Jumlah Harga<br>(Rp)</TH>
		<TH class=\"th02\" colspan=2>SPK / Perjanjian /<br> Kontrak</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\" style=\"width:60;\">Tanggal</TH>
		<TH class=\"th01\">Nomor</TH>
	</TR>
	$ListBarangDPB
		<tr>
	<td class=\"GarisDaftar\" colspan=10 align=center>
	".Halaman($jmlDataDPB,$Main->PagePerHal,"HalDPB")."
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
<!-END DPB-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

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
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
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
	<!--

	<tr valign=\"top\">
	  <td>Dipergunakan pada : </td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr valign=\"top\">
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;Unit</td>
	  <td>:</td>
		<td>
		".txtField('fmUNITGUNAKAN',$fmUNITGUNAKAN,'100','20','text','')."
		</td>
	</tr>
	<tr valign=\"top\">
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;Sub Unit</td>
	  <td>:</td>
	  <td>
		".txtField('fmSUBUNITGUNAKAN',$fmSUBUNITGUNAKAN,'100','20','text','')."
		</td>
	</tr>
	-->
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

$Main->Isi .= "

<input type=hidden name='fmIDREKENING' value='$fmIDREKENING'>
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