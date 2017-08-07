<?php
$HalDPB = cekPOST("HalDPB",1);
$HalDPBT = cekPOST("HalDPBT",1);
$LimitHalDPB = " limit ".(($HalDPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDPBT = " limit ".(($HalDPBT*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


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
$fmPENERIMA = cekPOST("fmPENERIMA");
$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmNMREKENING = cekPOST("fmNMREKENING");
$fmKET = cekPOST("fmKET");
$fmIDBARANGCARI=cekPOST("fmIDBARANGCARI");
$fmBARANGCARI=cekPOST("fmBARANGCARI");
$fmBARANGCARIDPBT=cekPOST("fmBARANGCARIDPBT");


$fmUNITGUNAKAN=cekPOST("fmUNITGUNAKAN");
$fmSUBUNITGUNAKAN=cekPOST("fmSUBUNITGUNAKAN");
$fmTANGGALSPK=cekPOST("fmTANGGALSPK");
$fmNOMORSPK=cekPOST("fmNOMORSPK");
$fmPTSPK=cekPOST("fmPTSPK");
$fmTANGGALDPA=cekPOST("fmTANGGALDPA");
$fmNOMORDPA=cekPOST("fmNOMORDPA");
$fmIDPENGADAAN=cekPOST("fmIDPENGADAAN");

$fmJENISBARANG=cekPOST("fmJENISBARANG","2");
$fmTANGGALDITERIMA=cekPOST("fmTANGGALDITERIMA");
$fmPTCV=cekPOST("fmPTCV");
$fmTANGGALFAKTUR=cekPOST("fmTANGGALFAKTUR");
$fmNOMORFAKTUR=cekPOST("fmNOMORFAKTUR");
$fmTANGGALPEMERIKSAAN=cekPOST("fmTANGGALPEMERIKSAAN");
$fmNOMORPEMERIKSAAN=cekPOST("fmNOMORPEMERIKSAAN");
$fmIDGUDANG=cekPOST("fmIDGUDANG");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

$cidDPBT=cekPOST("cidDPBT");
$cidDPB=cekPOST("cidDPB");

if(($Act=="Edit") && !isset($cidDPBT[0])){$Act="";}
if(($Act=="TambahEdit") && !isset($cidDPB[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidDPBT) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidDPBT) == 1 ? " disabled ":"";

//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmPENERIMA,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmTANGGALDITERIMA,fmPTCV,fmTANGGALFAKTUR,fmNOMORFAKTUR,fmTANGGALPEMERIKSAAN,fmNOMORPEMERIKSAAN,fmIDGUDANG,fmKET,fmTAHUNANGGARAN";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$JmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$KondisiCek = $Main->Provinsi[0].$fmWIL.$fmSKPD.$fmUNIT.$fmSUBUNIT.$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmTAHUNANGGARAN;
		$CekID = false;
		$Qry = mysql_query("select * from penerimaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'");
		$CekID = mysql_num_rows($Qry);
		//echo $CekID;
		//echo "select * from penerimaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'";
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
			$Qry = "insert into penerimaan (a,b,c,d,e,f,g,h,i,j,id_pengadaan,id_gudang,tgl_penerimaan,supplier,faktur_tgl,faktur_no,merk_barang,ba_no,ba_tgl,jenis_barang,jml_barang,harga,satuan,jml_harga,ket,tahun,penerima)
			values ('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDPENGADAAN','$fmIDGUDANG','". TglSQL($fmTANGGALDITERIMA)."','$fmPTCV','". TglSQL($fmTANGGALFAKTUR)."','$fmNOMORFAKTUR','$fmMEREK','$fmNOMORPEMERIKSAAN','". TglSQL($fmTANGGALPEMERIKSAAN)."','$fmJENISBARANG','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$JmlHARGA','$fmKET','$fmTAHUNANGGARAN','$fmPENERIMA')";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
			$Q = mysql_fetch_array(mysql_query("select id from penerimaan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update penerimaan set 
				merk_barang='$fmMEREK',jml_barang='$fmJUMLAH',harga='$fmHARGASATUAN',satuan='$fmSATUAN',jml_harga='$JmlHARGA',ket='$fmKET',tgl_penerimaan='". TglSQL($fmTANGGALDITERIMA)."',supplier='$fmPTCV',faktur_tgl='".TglSQL($fmTANGGALFAKTUR)."',faktur_no='$fmNOMORFAKTUR',ba_no='$fmNOMORPEMERIKSAAN',ba_tgl='".TglSQL($fmTANGGALPEMERIKSAAN)."',jenis_barang='$fmJENISBARANG',id_gudang='$fmIDGUDANG',penerima='$fmPENERIMA'
			where $Kriteria ";
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmTANGGALDITERIMA,fmPTCV,fmTANGGALFAKTUR,fmNOMORFAKTUR,fmTANGGALPEMERIKSAAN,fmNOMORPEMERIKSAAN,fmIDGUDANG,fmKET,fmPENERIMA");
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

$cidDPB = CekPOST("cidDPB");
$cidNya = $cidDPB;
if($Act == "Edit")
{
	$cidDPBT = CekPOST("cidDPBT");
	$cidNya = $cidDPBT;
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
			$Qry = mysql_query("select * from penerimaan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from pengadaan where id='{$cidNya[0]}'");
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
//		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
//		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmMEREK = "{$isi['merk_barang']}";
		$fmJUMLAH = "{$isi['jml_barang']}";
		$fmSATUAN = "{$isi['satuan']}";
		$fmHARGASATUAN = "{$isi['harga']}";
//		$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
//		$fmNMREKENING = "{$nmRekening['nm_rekening']}";
		$fmKET = "{$isi['ket']}";
//		$fmTANGGALSPK=TglInd($isi['spk_tgl']);
///		$fmNOMORSPK="{$isi['spk_no']}";
		$fmPTSPK = $Act == "TambahEdit"?$isi['pt']:$isi['supplier'];
//		$fmTANGGALDPA="{$isi['dpa_tgl']}";
//		$fmNOMORDPA="{$isi['dpa_no']}";
		if($Act=="Edit")
		{
			$fmIDPENGADAAN=$isi['id_pengadaan'];;
		}
		else
		{
			$fmIDPENGADAAN=$isi['id'];;
			$fmSKPD = "{$isi['c']}";
			$fmUNIT = "{$isi['d']}";
			$fmSUBUNIT = "{$isi['e']}";
		}
		$fmID = "{$isi['id']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
			$fmPTCV=$isi['pt'];
		}
		else
		{
			$fmJENISBARANG=$isi['jenis_barang'];
			$fmTANGGALDITERIMA=TglInd($isi['tgl_penerimaan']);
			$fmPTCV=$isi['supplier'];
			$fmTANGGALFAKTUR=TglInd($isi['faktur_tgl']);
			$fmNOMORFAKTUR=$isi['faktur_no'];
			$fmTANGGALPEMERIKSAAN=TglInd($isi['ba_tgl']);
			$fmNOMORPEMERIKSAAN=$isi['ba_no'];
			$fmIDGUDANG=$isi['id_gudang'];
			$fmPENERIMA=$isi['penerima'];
			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidDBP = CekPOST("cidDPBT");
if($Act=="Hapus" && count($cidDBP) > 0)
{
	for($i = 0; $i<count($cidDBP); $i++)
	{
		$Del = mysql_query("delete from penerimaan where id='{$cidDBP[$i]}' limit 1");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}



if($Act=="Add")
{
		$fmJENISBARANG="";
		$fmTANGGALDITERIMA="";
		$fmPTCV="";
		$fmTANGGALFAKTUR="";
		$fmNOMORFAKTUR="";
		$fmTANGGALPEMERIKSAAN="";
		$fmNOMORPEMERIKSAAN="";
		$fmIDGUDANG="";

		$fmKET = "";
		$fmID = "";
		$Baru=1;
}


//LIST DPB
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and c='$fmSKPD' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' $KondisiC $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}



//LIST DPB
//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengadaan where $Kondisi");
$jmlTotalHargaDPB = mysql_query("select sum(pengadaan.jml_harga) as total  from pengadaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi ");

if($jmlTotalHargaDPB = mysql_fetch_array($jmlTotalHargaDPB))
{
	$jmlTotalHargaDPB = $jmlTotalHargaDPB[0];
}
else
{$jmlTotalHargaDPB=0;}

//echo "select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$cek .= "select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPB = mysql_num_rows($Qry);
$ListBarang = "";
//$no=0;
$jmlPerHalDPB = 0;
$no=$Main->PagePerHal * (($HalDPB*1) - 1);
$cb=0;
$jmlTampilDPB = 0;
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDPB++;
	$jmlPerHalDPB += $isi['jml_harga'];
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarang .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cb$cb\" name=\"cidDPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlDataDPB, 'cb', this);  
	        adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"60\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"100\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">".$isi['spk_no']."</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarang .= "
<tr class='row0'><td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlPerHalDPB, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td></tr>
<tr class='row0'><td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDPB, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td></tr>

";
//ENDLIST DPB

//LIST DPBT
$KondisiD = $fmUNIT == "00" ? "":" and penerimaan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and penerimaan.e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and penerimaan.c='$fmSKPD' ";
$Kondisi = "penerimaan.a='{$Main->Provinsi[0]}' and penerimaan.b='$fmWIL' $KondisiC $KondisiD $KondisiE and penerimaan.tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARIDPBT))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPBT%' ";
}

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from penerimaan where $Kondisi");
$jmlTotalHargaDPBT = mysql_query("select sum(penerimaan.jml_harga) as total from penerimaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi");


if($jmlTotalHargaDPBT = mysql_fetch_array($jmlTotalHargaDPBT))
{
	$jmlTotalHargaDPBT = $jmlTotalHargaDPBT[0];
}
else
{$jmlTotalHargaDPBT=0;}

//echo "select penerimaan.*,ref_barang.nm_barang from penerimaan inner join ref_barang on concat(penerimaan.f,penerimaan.g,penerimaan.h,penerimaan.i,penerimaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select penerimaan.*,ref_barang.nm_barang from penerimaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPBT = mysql_num_rows($Qry);
$ListBarangDPBT = "";
//$no=0;
$no=$Main->PagePerHal * (($HalDPBT*1) - 1);
$jmlPerHalDPBT = 0;
$cb=0;
$jmlTampilDPBT = 0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilDPBT++;
	$jmlPerHalDPBT += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPBT .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cbDPBT$cb\" name=\"cidDPBT[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"60\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"100\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_penerimaan'])."</td>
			<td class=\"GarisDaftar\" align=left>{$isi['penerima']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPBT .= "
<tr class='row0'><td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlPerHalDPBT, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td></tr>
<tr class='row0'><td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDPBT, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td></tr>
";
//ENDLIST DPBT

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Penerimaan Barang Milik Daerah </th>
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
	<DIV ALIGN=CENTER>DAFTAR PENGADAAN BARANG MILIK DAERAH</DIV>
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
<!-- Tombol Tambah 
	<td align=right width=30%>

			<table border=0 cellpadding=0 cellspacing=0>
			<tr >
				<td><input type=button onclick=\"javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit();\" value=\"Tambah\"></td>
			</tr>
			</table> 
	</td>
-->
	</tr>
	</table>

<!-DPB-->
	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2 width=\"15\">No</TD>
		<TH class=\"th01\" rowspan=2 width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlData,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH class=\"th01\" rowspan=2 width=\"180\">Nama Barang</TH>
		<TH class=\"th01\" rowspan=2 width=\"160\">Merk / Type / Ukuran / <BR>Spesifikasi</TH>
		<TH class=\"th01\" rowspan=2 width=\"60\">Jumlah</TH>
		<TH class=\"th01\" rowspan=2 width=\"90\">Harga Satuan <BR>(Rp)</TH>
		<TH class=\"th01\" rowspan=2 width=\"100\">Jumlah Harga <BR>(Rp)</TH>
		<TH class=\"th02\" colspan=2>SPK / Perjanjian /<br> Kontrak</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\">Nomor</TH>
		<TH class=\"th01\">Tanggal</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=10 align=center>
	".Halaman($jmlDataDPB,$Main->PagePerHal,"HalADA")."
	</td>
	</tr>
<!-- 
	<tr>
	<td colspan=9>
		<table width=100% class=\"menubar\">
		<tr>
			<td class=\"menudottedline\">
				<table border=0 cellpadding=0 cellspacing=0>
					tr class=\"menudottedline\">
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
<!-END DPB--><br>


<!-DPBT-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENERIMAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">    
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input type=text name='fmBARANGCARIDPBT' value='$fmBARANGCARIDPBT'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
	</td>

	</tr>
	</table>
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPBT,'cbDPBT','toggle2');\" /></TD>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"160\">Merk / Type / Ukuran /<br> Spesifikasi</TH>
		<TH width=\"60\">Jumlah</TH>
		<TH width=\"90\">Harga Satuan<br> (Rp)</TH>
		<TH width=\"100\">Jumlah Harga<br> (Rp)</TH>
		<TH>Tanggal<br> Penerimaan</TH>
		<TH>Penerima</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarangDPBT
	<tr>
	<td colspan=10 align=center>
	".Halaman($jmlDataDPBT,$Main->PagePerHal,"HalDPBT")."
	</td>
	</tr></table>
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
<!-END DPBT-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

<br>
<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Nama Gudang</td>
	<td width=\"33\">:</td>
	<td width=\"804\">".cmbQuery('fmIDGUDANG',$fmIDGUDANG,"select id_gudang,nm_gudang from ref_gudang where concat(c,d,e)='$fmSKPD$fmUNIT$fmSUBUNIT'",'')."</td>
	</tr>
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
	  <td>Jenis Barang </td>
	  <td>:</td>
	  <td>".cmb2D('fmJENISBARANG',$fmJENISBARANG,$Main->JenisBarang,'')."</td>
	</tr>

	<tr valign=\"top\">   
	<td>Tanggal diterima</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALDITERIMA")."
	</td>
	</tr>
	<tr valign=\"top\">
	  <td>Penerima</td>
	  <td>:</td>
	  <td>
		".txtField('fmPENERIMA',$fmPENERIMA,'100','20','text','')."
		</td>
	</tr>


	<tr valign=\"top\">
	  <td>Dokumen Faktur</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">
	  <td>&nbsp;&nbsp;&nbsp;&nbsp;PT/CV</td>
	  <td>:</td>
	  <td>
		".txtField('fmPTCV',$fmPTCV,'100','20','text','')."
		</td>
	</tr>
	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALFAKTUR")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORFAKTUR',$fmNOMORFAKTUR,'100','20','text','')."
	</td>
	</tr>

	<tr valign=\"top\">
	  <td>Berita Acara Pemeriksaan</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALPEMERIKSAAN")."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOMORPEMERIKSAAN',$fmNOMORPEMERIKSAAN,'100','20','text','')."
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
<input type=hidden name='fmIDPENGADAAN' value='$fmIDPENGADAAN'>
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