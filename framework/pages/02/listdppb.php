<?php
$HalDKPPB = cekPOST("HalDKPPB",1);
$LimitHalDKPPB = " limit ".(($HalDKPPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL", $Main->DEF_WILAYAH);
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
$KondisiC = $fmSKPD == "00" ? "":" and pengadaan_pemeliharaan.c='$fmSKPD' ";
$Kondisi = "pengadaan_pemeliharaan.a='{$Main->Provinsi[0]}' and pengadaan_pemeliharaan.b='$fmWIL' $KondisiC $KondisiD $KondisiE and pengadaan_pemeliharaan.tahun='$fmTAHUNANGGARAN'";
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
<tr class='$row0'>
	<td class=\"GarisDaftar\" colspan=5>Total Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaListDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>
<tr class='$row0'>
	<td class=\"GarisDaftar\" colspan=5>Total Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDKPPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=right>&nbsp;</td>
</tr>

";
//ENDLIST DKPPB


$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<div align=\"center\" class=\"centermain\">
	<div class=\"main\">
		<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
	<tr>
		<th height=\"47\" class=\"user\">Daftar Pengadaan Pemeliharaan Barang Milik Daerah </th>
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
		<DIV ALIGN=CENTER>DAFTAR PENGADAAN PEMELIHARAAN BARANG MILIK DAERAH</DIV>
		</td>
	</tr>
	<tr>
				<th>
					<table>
						<tr>
							<td class=\"contentheading\"><DIV ALIGN=CENTER>
							DARI TANGGAL 
							</td>
							<td class=\"contentheading\"><DIV ALIGN=CENTER>
							".InputKalender("fmTANGGALPERAWAL")."
							</td>
							<td class=\"contentheading\"><DIV ALIGN=CENTER>SAMPAI DENGAN</td>
							<td class=\"contentheading\"><DIV ALIGN=CENTER>
							".InputKalender("fmTANGGALPERAKHIR")."
							</td>
						</tr>
					</table>
				</th>
			</tr>
</table>

<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
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
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dppb_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dppb_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
				</tr></table>
			</td></tr>
		</table>


</form>
</div>
</div>
";
?>