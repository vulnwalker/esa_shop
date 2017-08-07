<?php
$HalKIR = cekPOST("HalKIR",1);
$LimitHalKIR = " limit ".(($HalKIR*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidKIR = cekPOST("cidKIR");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALKIR = cekPOST("fmTANGGALKIR");
$fmKET = cekPOST("fmKET");
$fmGEDUNG = cekPOST("fmGEDUNG");
$fmRUANG = cekPOST("fmRUANG");
$fmIDBUKUINDUK = cekPOST("fmIDBUKUINDUK");
$fmTAHUNPEROLEHAN = cekPOST("fmTAHUNPEROLEHAN");

$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,3,2) . "." .$fmSUBUNIT. "." .$fmSEKSI;


$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");


$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//LIST KIR
$KondisiD = $fmUNIT == "00" ? "":" and buku_induk.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and buku_induk.e='$fmSUBUNIT' ";
$KondisiE1 = $fmSEKSI == "00" || $fmSEKSI == "000" ? "":" and buku_induk.e1='$fmSEKSI' ";
$Kondisi = "buku_induk.a1='$fmKEPEMILIKAN' and buku_induk.a='{$Main->Provinsi[0]}' and buku_induk.b='$fmWIL' and buku_induk.c='$fmSKPD' $KondisiD $KondisiE $KondisiE1 and buku_induk.status_barang = '1'  and (buku_induk.f='02' or buku_induk.f='05' )";
$KondisiTotal = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}
/*
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}
*/
$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j)  inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.e1,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.e1,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun)  where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.e1,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.e1,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,e1,f,g,h,i,j,noreg");
//echo "select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";

$jmlDataKIR = mysql_num_rows($Qry);
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.e1,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.e1,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,e1,f,g,h,i,j,noreg $LimitHalKIR");


$ListBarangKIR = "";
$JmlTotalHargaListKIR = 0;
$no=$Main->PagePerHal * (($HalKIR*1) - 1);
$cb=0;
$jmlTampilKIR = 0;
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilKIR++;
	$JmlTotalHargaListKIR += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangKIR .= "
	
		<tr>
			<td class=\"GarisCetak\" align=center width=\"20\">$no</td>
			<td class=\"GarisCetak\" align=center width=\"85\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisCetak\" align=center width=\"40\">{$isi['noreg']}</td>
			<td class=\"GarisCetak\" width=\"260\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisCetak\" align=center width=\"60\">{$isi['thn_perolehan']}</td>
			<td class=\"GarisCetak\" width=\"80\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisCetak\" width=\"100\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<!-- <td class=\"GarisCetak\" width=\"100\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td> -->
			<td class=\"GarisCetak\">".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>
			<td class=\"GarisCetak\" width=\"90\">".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>
			<td class=\"GarisCetak\">".$Main->StatusBarang[$isi['status_barang']-1][1]."</td>
		</tr>

		";
	$cb++;
}
$ListBarangKIR .= "
<!-- <tr>
	<td class=\"GarisCetak\" colspan=6>Jumlah Harga per Halaman (Rp)</td>
	<td class=\"GarisCetak\" align=right ><b>".number_format($JmlTotalHargaListKIR, 2, ',', '.')." </td>
	<td class=\"GarisCetak\" colspan=3 align=center>&nbsp;</td>
</tr> -->
<tr class=\"row0\">
	<td class=\"GarisCetak\" colspan=6>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisCetak\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
	<td class=\"GarisCetak\" colspan=3 align=center>&nbsp;</td>
</tr>

";
//ENDLIST KIR

$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('merk','Merek/Type'),
array('ket','Keterangan')
);


$Main->Isi = "
<head>
	<title>$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\"/>
</head>

<body>
<table class=\"rangkacetak\">
<tr>
<td valign=\"top\">

	<table style='width:30cm' border=\"0\">
		<tr>
			<td class=\"judulcetak\"><DIV ALIGN=CENTER>KARTU INVENTARIS RUANGAN (KIR)</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\">".PrintSKPD()."</td>
		</tr>
	</table>

	<table width=\"100%\" border=\"0\">
		<tr>
			<td class=\"subjudulcetak\" align=right>NO. KODE LOKASI : $KODELOKASI</td>
		</tr>
	</table>

	<table class=\"cetak\">
		<thead>
		<tr>
			<TH class=\"th01\" width=\"20\">No</TD>
			<TH class=\"th01\" width=\"85\">Kode Barang</TH>
			<TH class=\"th01\" width=\"40\">Nomor<br>Reg</TH>
			<TH class=\"th01\" width=\"260\">Nama Barang</TH>
			<TH class=\"th01\" width=\"60\">Tahun<br>Buat/Beli</TH>
			<TH class=\"th01\" width=\"80\">Jumlah<br>Barang</TH>
			<TH class=\"th01\" width=\"100\">Harga<br> Perolehan<br> (Rp)</TH>
			<TH class=\"th01\">Asal Usul</TH>
			<TH class=\"th01\"  width=\"100\">Keadaan Barang <br> (B,KB,RB)</TH>
			<TH class=\"th01\">Status<br>Barang</TH>
		</tr>
		</thead>
		$ListBarangKIR
<br>
<br>
".PrintTTD()."

</td>
</tr>
</table>
		

</body>
";
?>