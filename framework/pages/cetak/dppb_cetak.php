<?php
$HalDKPPB = cekPOST("HalDKPPB",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHalDKPPB = " limit ".(($HalDKPPB	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalDKPPB = !empty($ctk)?"":$LimitHalDKPPB;

/*
$HalDKPPB = cekPOST("HalDKPPB",1);
$LimitHalDKPPB = " limit ".(($HalDKPPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/

$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$fmTANGGALPERAWAL=cekPOST("fmTANGGALPERAWAL",date("01-01-Y"));
$fmTANGGALPERAKHIR=cekPOST("fmTANGGALPERAKHIR",date("d-m-Y"));

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
$jmlTotalHarga = mysql_query("select sum(pengadaan_pemeliharaan.jml_harga) as total  from pengadaan_pemeliharaan inner join dkpb on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j) = concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j) inner join ref_barang on concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ");
//echo "select sum(pengadaan_pemeliharaan.jml_harga) as total  from pengadaan_pemeliharaan inner join dkpb on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j) = concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j) inner join ref_barang on concat(dkpb.f,dkpb.g,dkpb.h,dkpb.i,dkpb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ";

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$jmlDataDKPPB = mysql_num_rows($Qry);

$Qry = mysql_query("select pengadaan_pemeliharaan.*,ref_barang.nm_barang from pengadaan_pemeliharaan inner join ref_barang on concat(pengadaan_pemeliharaan.f,pengadaan_pemeliharaan.g,pengadaan_pemeliharaan.h,pengadaan_pemeliharaan.i,pengadaan_pemeliharaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalDKPPB");
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
	
		<tr>
			<td class=\"GarisCetak\" align=center>$no</td>
			<td class=\"GarisCetak\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\">{$isi['merk_barang']}</td>
			<td class=\"GarisCetak\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisCetak\" align=center>".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisCetak\">".$isi['spk_no']."</td>
			<td class=\"GarisCetak\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDKPPB .= "
<!-- <tr class='$row0'>
	<td class=\"GarisCetak\" colspan=5>Total Harga per Halaman (Rp)</td>
	<td class=\"GarisCetak\" align=right><b>".number_format($jmlTotalHargaListDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisCetak\" colspan=3 align=right>&nbsp;</td>
</tr> -->
<tr class='$row0'>
	<td class=\"GarisCetak\" colspan=5>Total Harga Seluruhnya (Rp)</td>
	<td class=\"GarisCetak\" align=right><b>".number_format($jmlTotalHargaDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisCetak\" colspan=3 align=right>&nbsp;</td>
</tr>

";
//ENDLIST DKPPB


$Main->Isi = "
<head>
	<title>$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
</head>

<body>
<table class=\"rangkacetak\">
<tr>
<td valign=\"top\">

	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DAFTAR PENGADAAN PEMELIHARAAN BARANG MILIK DAERAH</td>
		</tr>
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>DARI TANGGAL $fmTANGGALPERAWAL SAMPAI DENGAN $fmTANGGALPERAKHIR</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".PrintSKPD()."</td>
		</tr>
	</table>
	
<br>


<table class=\"cetak\">
	<thead>
	<TR>
		<TH class=\"th01\" rowspan=2 width=\"20\">No</TH>
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
	</thead>
	$ListBarangDKPPB
</table>
<br>
".PrintTTD()."

</td>
</tr>
</table>
</body>
";
?>