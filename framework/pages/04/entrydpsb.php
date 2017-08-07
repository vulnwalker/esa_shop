<?php
$HalDPBL = cekPOST("HalDPBL",1);
$HalDPSB = cekPOST("HalDPSB",1);
$LimitHalDPBL = " limit ".(($HalDPBL*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDPSB = " limit ".(($HalDPSB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidDPBL = cekPOST("cidDPBL");
$cidDPSB = cekPOST("cidDPSB");


$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
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
$fmBARANGCARIDPSB=cekPOST("fmBARANGCARIDPSB");

$fmNOSKGUBERNUR=cekPOST("fmNOSKGUBERNUR");
$fmTANGGALSKGUBERNUR=cekPOST("fmTANGGALSKGUBERNUR");
$fmKONDISIBAIK=cekPOST("fmKONDISIBAIK");
$fmKONDISIKURANGBAIK=cekPOST("fmKONDISIKURANGBAIK");
$fmTANGGALBELI=cekPOST("fmTANGGALBELI");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

if(($Act=="Edit") && !isset($cidDPSB[0])){$Act="";}
if(($Act=="TambahEdit") && !isset($cidDPBL[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidDPSB) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidDPSB) == 1 ? " disabled ":"";

//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmKET,fmTAHUNANGGARAN,fmNOSKGUBERNUR,fmTANGGALSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK";

if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$JmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$KondisiCek = $Main->Provinsi[0].$fmWIL.$fmSKPD.$fmUNIT.$fmSUBUNIT.$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmTAHUNANGGARAN;
		$CekID = false;
		$Qry = mysql_query("select * from penetapan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'");
		$CekID = mysql_num_rows($Qry);
		//echo $CekID;
		//echo "select * from penetapan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'";
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
			$Qry = "insert into penetapan (a,b,c,d,e,f,g,h,i,j,id_pengeluaran,merk_barang,jml_barang,harga,satuan,jml_harga,ket,tahun,skgub_tgl,skgub_no,jml_baik,jml_kbaik)
			values ('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDPENGELUARAN','$fmMEREK','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$JmlHARGA','$fmKET','$fmTAHUNANGGARAN','".TglSQL($fmTANGGALSKGUBERNUR)."','$fmNOSKGUBERNUR','$fmKONDISIBAIK','$fmKONDISIKURANGBAIK')";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
			$Q = mysql_fetch_array(mysql_query("select id from penetapan where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update penetapan set 
				merk_barang='$fmMEREK',jml_barang='$fmJUMLAH',harga='$fmHARGASATUAN',satuan='$fmSATUAN',jml_harga='$JmlHARGA',ket='$fmKET',skgub_tgl='".TglSQL($fmTANGGALSKGUBERNUR)."',skgub_no='$fmNOSKGUBERNUR',jml_baik='$fmKONDISIBAIK',jml_kbaik='$fmKONDISIKURANGBAIK' 
			where $Kriteria ";
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
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

$cidDPBL = CekPOST("cidDPBL");
$cidNya = $cidDPBL;
if($Act == "Edit")
{
	$cidDPSB = CekPOST("cidDPSB");
	$cidNya = $cidDPSB;
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
			$Qry = mysql_query("select * from penetapan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select pengeluaran.*,penerimaan.faktur_tgl from pengeluaran inner join penerimaan  using(id) where pengeluaran.id='{$cidNya[0]}'");
			//echo "select pengeluaran.*,penerimaan.faktur_tgl from pengeluaran inner join penerimaan on pengeluaran.id_penerimaan=penerimaan.id where pengeluaran.id='{$cidNya[0]}'";
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
//		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
//		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmMEREK = "{$isi['merk_barang']}";
		$fmJUMLAH = "{$isi['jml_barang']}";
		$fmSATUAN = "{$isi['satuan']}";
		$fmHARGASATUAN = "{$isi['harga']}";
		$fmKET = "{$isi['ket']}";
		$fmTANGGALBELI= TglInd("{$isi['faktur_tgl']}");

		if($Act=="Edit")
		{
			$fmIDPENGELUARAN=$isi['id_pengeluaran'];
		}
		else
		{
			$fmIDPENGELUARAN=$isi['id'];
			$fmSKPD = "{$isi['c']}";
			$fmUNIT = "{$isi['d']}";
			$fmSUBUNIT = "{$isi['e']}";

		}
		$fmID = "{$isi['id']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
		}
		else
		{
			$fmNOSKGUBERNUR=$isi['skgub_no'];
			$fmTANGGALSKGUBERNUR=TglInd($isi['skgub_tgl']);
			$fmKONDISIBAIK=$isi['jml_baik'];
			$fmKONDISIKURANGBAIK=$isi['jml_kbaik'];
			$fmTANGGALBELI=TglInd($isi['tgl_beli']);
			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidDBP = CekPOST("cidDPSB");
if($Act=="Hapus" && count($cidDBP) > 0)
{
	for($i = 0; $i<count($cidDBP); $i++)
	{
		$Del = mysql_query("delete from penetapan where id='{$cidDBP[$i]}' limit 1");
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


//LIST DPBL
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}



//LIST DPBL
//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengeluaran where $Kondisi");
$jmlTotalHargaDPBL = mysql_query("select sum(pengeluaran.jml_harga) as total  from pengeluaran inner join ref_barang  using(f,g,h,i,j) where $Kondisi ");

if($jmlTotalHargaDPBL = mysql_fetch_array($jmlTotalHargaDPBL))
{
	$jmlTotalHargaDPBL = $jmlTotalHargaDPBL[0];
}
else
{$jmlTotalHargaDPBL=0;}

//echo "select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang on concat(pengeluaran.f,pengeluaran.g,pengeluaran.h,pengeluaran.i,pengeluaran.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPBL = mysql_num_rows($Qry);
$Qry = mysql_query("select pengeluaran.*,ref_barang.nm_barang from pengeluaran inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPBL");


$ListBarang = "";
$JmlTotalHargaListDPBL = 0;
$no=$Main->PagePerHal * (($HalDPBL*1) - 1);
$cb=0;
$jmlTampilDPBL = 0;
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDPBL++;
	$JmlTotalHargaListDPBL += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarang .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cb$cb\" name=\"cidDPBL[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilDPBL, 'cb', this);
			adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" width=\"160\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"50\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"80\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"80\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=left>{$isi['sk_no']} / ".TglInd($isi['sk_tgl'])."</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarang .= "
<tr><td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($JmlTotalHargaListDPBL, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td></tr>
<tr><td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDPBL, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td></tr>
";
//ENDLIST DPBL

//LIST DPSB
$KondisiD = $fmUNIT == "00" ? "":" and penetapan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and penetapan.e='$fmSUBUNIT' ";
$Kondisi = "penetapan.a='{$Main->Provinsi[0]}' and penetapan.b='$fmWIL' and penetapan.c='$fmSKPD' $KondisiD $KondisiE and penetapan.tahun='$fmTAHUNANGGARAN'";
if(!empty($fmBARANGCARIDPSB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPSB%' ";
}

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from penetapan where $Kondisi");
$jmlTotalHargaDPSB = mysql_query("select sum(penetapan.jml_harga) as total from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi ");


if($jmlTotalHargaDPSB = mysql_fetch_array($jmlTotalHargaDPSB))
{
	$jmlTotalHargaDPSB = $jmlTotalHargaDPSB[0];
}
else
{$jmlTotalHargaDPSB=0;}

//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataDPSB = mysql_num_rows($Qry);
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPSB");
//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";

$JmlTotalHargaListDPSB = 0;
$no=$Main->PagePerHal * (($HalDPSB*1) - 1);
$cb=0;
$jmlTampilDPSB = 0;

$ListBarangDPSB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDPSB++;
	$JmlTotalHargaListDPSB += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPSB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" width=\"15\"><input type=\"checkbox\" id=\"cbDPSB$cb\" name=\"cidDPSB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" width=\"160\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"160\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right width=\"50\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"80\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"80\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=left>{$isi['skgub_no']}</td>
			<td class=\"GarisDaftar\" align=left>".TglInd($isi['skgub_tgl'])."</td>
			<!--<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_beli'])."</td>-->
			<td class=\"GarisDaftar\" align=right>{$isi['jml_baik']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_kbaik']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPSB .= "
<tr><td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($JmlTotalHargaListDPSB, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=5 align=right>&nbsp;</td></tr>
<tr><td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td><td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDPSB, 2, ',', '.')."</td><td class=\"GarisDaftar\" colspan=5 align=right>&nbsp;</td></tr>
";

//ENDLIST DPSB

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Penetapan Barang Milik Daerah</th>
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
	<DIV ALIGN=CENTER>DAFTAR PENGELUARAN BARANG MILIK DAERAH</DIV>
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
	<!-- <td align=right width=30%>

			<table border=0 cellpadding=0 cellspacing=0>
			<tr >
				<td><input type=button onclick=\"javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit();\" value=\"Tambah\"></td>
			</tr>
			</table>
	</td> -->

	</tr>
	</table>

<!-DPSB-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilDPSB,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH width=\"160\">Nama Barang</TH>
		<TH width=\"160\">Merk / Type / Ukuran / <BR>Spesifikasi</TH>
		<TH width=\"50\">Jumlah</TH>
		<TH width=\"80\">Harga Satuan<BR> (Rp)</TH>
		<TH width=\"80\">Jumlah Harga<BR>(Rp)</TH>
		<TH>Surat Perintah<br>Nomor/Tanggal</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=9 align=center>
	".Halaman($jmlDataDPBL,$Main->PagePerHal,"HalDPBL")."
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
<!-END DPBL--><br>


<!-DPSB-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENETAPAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIDPSB' value='$fmBARANGCARIDPSB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" rowspan=2 width=\"15\">No</TD>
		<TH class=\"th01\" rowspan=2 width=\"15\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPSB,'cbDPSB','toggle2');\" /></TD>
		<TH class=\"th01\" rowspan=2 width=\"160\">Nama Barang</TH>
		<TH class=\"th01\" rowspan=2 width=\"160\">Merk / Type / Ukuran / <BR>Spesifikasi</TH>
		<TH class=\"th01\" rowspan=2 width=\"50\">Jumlah</TH>
		<TH class=\"th01\" rowspan=2 width=\"80\">Harga Satuan<BR> (Rp)</TH>
		<TH class=\"th01\" rowspan=2 width=\"80\">Jumlah Harga<BR>(Rp)</TH>
		<TH class=\"th02\" colspan=2>SK Gubernur</TH>
<!--		<TH class=\"th01\" rowspan=2>Tanggal<br> Pembelian</TH> -->
		<TH class=\"th02\" colspan=2>J u m l a h</TH>
		<TH class=\"th01\" rowspan=2>Keterangan</TH>
	</TR>
	<TR>
		<TH class=\"th01\" width=\"90\">No.</TH>
		<TH class=\"th01\" width=\"90\">Tanggal</TH>
		<TH class=\"th01\" width=\"40\">Baik</TH>
		<TH class=\"th01\" width=\"40\">Kurang<br> Baik</TH>
	</TR>
	$ListBarangDPSB
	<tr>
	<td colspan=12 align=center>
	".Halaman($jmlDataDPSB,$Main->PagePerHal,"HalDPSB")."
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
<!-END DPSB-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

<br>
<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<TR>
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG","$ReadOnly","$DisAbled")."
	</td>
	</tr>
	
	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Merk / Type / Ukuran / Spesifikasi</td>
	<td width=\"33\">:</td>
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
	</tr>

	<tr valign=\"top\">
	  <td>Harga Satuan </td>
	  <td>:</td>
	  <td>Rp. 
		".inputFormatRibuan("fmHARGASATUAN")."</td>
	</tr>
<!--
	<tr valign=\"top\">   
	<td>Tanggal Beli</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALBELI")."
	</td>
	</tr>
-->
	<tr valign=\"top\">
	  <td>Jumlah Barang</td>
	  <td>:</td>
	  <td>
		".txtField('fmJUMLAH',$fmJUMLAH,'100','20','text','')."&nbsp; Satuan".txtField('fmSATUAN',$fmSATUAN,'100','20','text','')."
		</td>
	</tr>


	<tr valign=\"top\">
	  <td>Jumlah Kondisi Baik</td>
	  <td>:</td>
	  <td>
		".txtField('fmKONDISIBAIK',$fmKONDISIBAIK,'10','4','text','')."
		</td>
	</tr>

	<tr valign=\"top\">
	  <td>Jumlah Kondisi Kurang Baik</td>
	  <td>:</td>
	  <td>
		".txtField('fmKONDISIKURANGBAIK',$fmKONDISIKURANGBAIK,'10','4','text','')."
		</td>
	</tr>

	<tr valign=\"top\">
	  <td>No. SK Gubernur</td>
	  <td>:</td>
	  <td>&nbsp;</td>
	</tr>

	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALSKGUBERNUR")."
	</td>
	</tr>


	<tr valign=\"top\">   
	<td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
	<td>:</td>
	<td>
		".txtField('fmNOSKGUBERNUR',$fmNOSKGUBERNUR,'100','20','text','')."
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
<input type=hidden name='fmIDPENGELUARAN' value='$fmIDPENGELUARAN'>
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