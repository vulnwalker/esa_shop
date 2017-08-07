<?php
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

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
$fmBARANGCARIDPB=cekPOST("fmBARANGCARIDPB");


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

$Info = "";

$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmTAHUNANGGARAN,fmTANGGALPERAWAL,fmTANGGALPERAKHIR";

//LIST DPB
$KondisiD = $fmUNIT == "00" ? "":" and pengadaan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and pengadaan.e='$fmSUBUNIT' ";
$KondisiC = $fmSKPD == "00" ? "":" and pengadaan.c='$fmSKPD' ";
$KondisiTahun = " and spk_tgl >= '".TglSQL($fmTANGGALPERAWAL)."' and spk_tgl <= '".TglSQL($fmTANGGALPERAKHIR)."' ";
$Kondisi = "pengadaan.a='{$Main->Provinsi[0]}' and pengadaan.b='$fmWIL' $KondisiC $KondisiD $KondisiE and pengadaan.tahun='$fmTAHUNANGGARAN' $KondisiTahun";
if(!empty($fmBARANGCARIDPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPB%' ";
}

// copy untuk kondisi jumlah total
$KondisiTotal = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from pengadaan where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from pengadaan where $Kondisi");
$jmlTotalHargaDisplay = mysql_query("select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb using(f,g,h,i,j) inner join ref_barang using(f,g,h,i,j) where $Kondisi ");
//echo "select sum(pengadaan.jml_harga) as total  from pengadaan inner join dkb on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j) = concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j) inner join ref_barang on concat(dkb.f,dkb.g,dkb.h,dkb.i,dkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ";

if($jmlTotalHargaDisplay = mysql_fetch_array($jmlTotalHargaDisplay))
{
	$jmlTotalHargaDisplay = $jmlTotalHargaDisplay[0];
}
else
{$jmlTotalHargaDisplay=0;}

//echo "select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang on concat(pengadaan.f,pengadaan.g,pengadaan.h,pengadaan.i,pengadaan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataDPB = mysql_num_rows($Qry);

$Qry = mysql_query("select pengadaan.*,ref_barang.nm_barang from pengadaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

$ListBarangDPB = "";
$no=$Main->PagePerHal * (($HalDefault*1) - 1);
$cb=0;
while ($isi = mysql_fetch_array($Qry))
{
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$kodeskpd = $isi['c'].$isi['d'].$isi['e'];
	$namaskpd = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where concat(c,d,e)='$kodeskpd'")); 
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['spk_tgl'])."</td>
			<td class=\"GarisDaftar\" align=left>".$isi['spk_no']."</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['dpa_tgl'])."</td>
			<td class=\"GarisDaftar\" align=left>".$isi['dpa_no']."</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" style='width:140'>{$namaskpd['nm_skpd']}</td>
			<td class=\"GarisDaftar\" style='width:120'>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPB .= "
	<tr class='row0'>
		<td class=\"GarisDaftar\" colspan=8>Total Harga per Halaman (Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td>
	</tr>
	<tr class='row0'>
		<td class=\"GarisDaftar\" colspan=8>Total Harga Seluruhnya (Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=2>&nbsp;</td>
	</tr>
";
//ENDLIST DPB


$Main->Isi = "
<A Name=\"ISIAN\"></A>
<script language=\"javascript\" type=\"text/javascript\">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'hapus') {
				form.action=\"home.php?ar=7\";
				form.submit();
				return;
			}else
			{ if (pressbutton == 'edit') {
				form.action=\"home.php?ar=6\";
				form.submit();
				return;
			  }	 

			}		
		}

</script>

<div align=\"center\" class=\"centermain\">
	<div class=\"main\">
		<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
		<table class=\"adminheading\">
			<tr>
			  <th height=\"47\" class=\"user\">Daftar Pengadaan Barang</th>
			</tr>
		</table>
		<table width=\"100%\" class=\"adminheading\">
			<tr>
			<td colspan=4>
			<br>
".WilSKPD()."
</td>
			</tr>
		</table>
		<table width=\"100%\">
			<tr>
				<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR PENGADAAN BARANG</td>
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
			<tr>
				<th class=\"th01\" rowspan=\"2\" style='width:20'>No.</th>
				<!--<th rowspan=\"2\">
				<input type=\"checkbox\" name=\"toggle\" value=\"\" onClick=\"checkAll($jmlData);\" />
				</th>-->
				<th class=\"th01\" rowspan=\"2\">Barang yang Dibeli</th>
				<th class=\"th02\" colspan=\"2\">SPK / Perjanjian /<br> Kontrak</th>
				<th class=\"th02\" colspan=\"2\">DPA / SPM /<br> Kwitansi</th>
				<th class=\"th02\" colspan=\"3\">J u m l a h</th>
				<th class=\"th01\" rowspan=\"2\" style='width:140'>Dipergunakan<br> pada Unit</th>
				<th class=\"th01\" rowspan=\"2\" style='width:120'>Keterangan</th>
			</tr>
			<tr>
				<th class=\"th01\" style='width:55'>Tanggal</th>
				<th class=\"th01\">Nomor</th>
				<th class=\"th01\" style='width:55'>Tanggal</th>
				<th class=\"th01\">Nomor</th>
				<th class=\"th01\">Banyak<br> nya<br> Barang</th>
				<th class=\"th01\" style='width:90'>Harga Satuan<br> (Rp)</th>
				<th class=\"th01\" style='width:100'>Jumlah Harga<br> (Rp)</th>
			</tr>
			$ListBarangDPB
			<tr>
				<td colspan=12 align=center>".Halaman($jmlDataDPB,$Main->PagePerHal,"HalDefault")."</td>
			</tr>
        </table>
<br>
		<table width=\"100%\" class=\"menudottedline\">
			<tr><td>
				<table width=\"50\"><tr>
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dpb_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
				<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dpb_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
				</tr></table>
			</td></tr>
		</table>

		</form>
	</div>
</div>
";
?>