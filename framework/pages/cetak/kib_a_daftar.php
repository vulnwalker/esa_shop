<?php
$HalDefault = cekPOST("HalDefault",1);
$ctk = cekGET("ctk");
$Main->PagePerHal = !empty($ctk)?0:$Main->PagePerHal;
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHal = !empty($ctk)?"":$LimitHal;
/*

$HalBI = cekPOST("HalBI",1);
$HalKIB_A = cekPOST("HalKIB_A",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIB_A = " limit ".(($HalKIB_A*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
*/
$cidBI = cekPOST("cidBI");
$cidKIB_A = cekPOST("cidKIB_A");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//LIST KIB_A
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";

//$fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN
$KODELOKASI = $fmKEPEMILIKAN.".".$fmWIL . "." .$fmSKPD . "." .$fmUNIT . "." . substr($fmTAHUNANGGARAN,2,2) . "." .$fmSUBUNIT;

$KondisiTotal = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from view_kib_a where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select * from view_kib_a where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataKIB_A = mysql_num_rows($Qry);
$Qry = mysql_query("select * from view_kib_a where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg  $LimitHalKIB_A");

$no=$Main->PagePerHal * (($HalKIB_A*1) - 1);
$cb=0;
$jmlTampilKIB_A = 0;
$JmlTotalHargaListKIB_A = 0;
$ListBarangKIB_A = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilKIB_A++;
	$JmlTotalHargaListKIB_A += $isi['jml_harga'];
	$no++;
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangKIB_A .= "	
		<tr class='$clRow' >
			<td class='bl'  align=right>$no.&nbsp;</td>
			
			<td class='bl' style='width:60' align=center>{$isi['id_barang']}</td>
			<td class='bl' style='width:50' align=center>{$isi['noreg']}</td>
			<td class='bl' style='width:300' align=left>{$isi['nm_barang']}</td>
			<td class='bl' style='width:60' align=right>{$isi['luas']}</td>
			<td class='bl' style='width:50' align=center>{$isi['thn_perolehan']}</td>
			<td class='bl' >{$isi['alamat']}</td>
			<td class='bl' >".$Main->StatusHakPakai[$isi['status_hak']-1][1]."</td>
			<td class='bl' style='width:60'>".TglInd($isi['sertifikat_tgl'])."</td>
			<td class='bl' >{$isi['sertifikat_no']}</td>
			<td class='bl' >{$isi['penggunaan']}</td>
			<td class='bl' >".$Main->AsalUsul[$isi['asal_usul']-1][1]."</td>
			<td class='bl' align=right>".number_format(($isi['jml_harga']/1000), 2, ',', '.')."</td>
			<td class='blr' >{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangKIB_A .= "
	<tr><td class='bl' colspan=12 >Jumlah Harga (Rp)</td><td class='bl' align=right><b>".number_format(($JmlTotalHargaListKIB_A/1000), 2, ',', '.')."</td><td class='br' colspan=2 align=right>&nbsp;</td></tr>
	<tr><td colspan=12 class='bl'>Total Harga (Rp)</td><td class='bl' align=right><b>".number_format(($jmlTotalHarga/1000), 2, ',', '.')."</td><td class='br' colspan=2 align=right>&nbsp;</td></tr>
	";
//ENDLIST KIB_A


$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

$Main->Isi = "
<head>
<title>$Main->Judul</title>
<style>

/***Untuk Pencetakan By Juy & Kos *****/
thead    { display: table-header-group }
thead    { display: table-header-group }
/**************************************/
table,tr,th,td,input{font-family:'Arial Narrow';font-size:10pt}
@page{margin:1cm}

/**UNtuk Border Coy ***/
.tbl{
		border-top:1 black solid;
		border-bottom:1 black solid;
		border-left:1 black solid;
  }
.tbr{
		border-top:1 black solid;
		border-bottom:1 black solid;
		border-right:1 black solid;
  }
.tlr{
		border-top:1 black solid;
		border-right:1 black solid;
		border-left:1 black solid;
  }
.tblr{
		border-top:1 black solid;
		border-bottom:1 black solid;
		border-right:1 black solid;
		border-left:1 black solid;
  }
.bl{
		border-bottom:1 black solid;
		border-left:1 black solid;
  }
.br{
		border-bottom:1 black solid;
		border-right:1 black solid;
  }
.blr{
		border-bottom:1 black solid;
		border-left:1 black solid;
		border-right:1 black solid;
  }
</style>
</head>
<body leftmargin=0 rightmargin=0 topmargin=0 bottommargin=0>
<table style='width:30cm' cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=\"100%\" valign=\"top\">
<!-KIB_A-->
		<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">
		<thead>

		<tr>
		<td colspan=17>
		<center>
		<FONT style='font-size:12pt;font-weight:bold'>
		KARTU INVENTARIS BARANG (KIB) A<br>
		T A N A H<br><br>
		</FONT>
		</center>
		<font style='font-weight:bold'>".PrintSKPD()."</font>
		<font style='font-weight:bold;width:100%;text-align:right'>
		NO. KODE LOKASI : $KODELOKASI
		</font>
		<br>
		<br>
		</td>
		</tr>

			<tr>
				<TH class='tbl' rowspan=\"3\"><b>No.</b></TH>
				<TH class='tbl' colspan=\"2\"><b>N o m o r</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Nama Barang</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Luas (M2)</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Tahun<br>Pengadaan</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Letak / Alamat</b></TH>
				<TH class='tbl' colspan=\"3\"><b>Status Tanah</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Penggunaan</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Asal-Usul</b></TH>
				<TH class='tbl' rowspan=\"3\"><b>Harga<br>(Ribuan Rp)</b></TH>
				<TH class='tblr' rowspan=\"3\"><b>Keterangan</b></TH>
			</tr>
			<tr class=\"koptabel\">
				<TH class='bl' rowspan=\"2\"><b>Kode<br>Barang</b></TH>
				<TH class='bl' rowspan=\"2\"><b>Register</b></TH>
				<TH class='bl' rowspan=\"2\"><b>Hak</b></TH>
				<TH class='bl' colspan=\"2\"><b>Sertifikat</b></TH>
			</tr>
			<tr class=\"koptabel\">
				<TH class='bl' ><b>Tanggal</b></TH>
				<TH class='bl' ><b>Nomor</b></TH>
			</tr>
		</THEAD>
			$ListBarangKIB_A
        </table>









		<br>
		<table width=100% border=0>
		<tr>
			<td width=20>&nbsp;</td>
			<td align=left width=400><B>
			MENGETAHUI<BR>
			KEPALA SKPD<BR><BR><BR><BR><BR><BR>
			<INPUT TYPE=TEXT VALUE='(NAMA KEPALA SKPD)' STYLE='background:none;border:none;text-align:left' size=30 ><br>
			NIP. <INPUT TYPE=TEXT VALUE='NIP KEPALA SKPD' STYLE='background:none;border:none;text-align:left' size=30>
			</td>
			<td width='*'>&nbsp;</td>
			<td align=left width=300><B>
			<INPUT TYPE=TEXT VALUE='Bandung,......' STYLE='background:none;border:none;text-align:left' size=30><BR>
			<B>PENGURUS BARANG<BR><BR><BR><BR><BR><BR>
			<INPUT TYPE=TEXT VALUE='(Nama Pengurus)' STYLE='background:none;border:none;text-align:left' size=30 ><br>
			NIP. <INPUT TYPE=TEXT VALUE='NIP PENGURUS' STYLE='background:none;border:none;text-align:left' size=30>

			</td>
			<td width=20>&nbsp;</td>
		</tr>
		</table>
	</table>
	
";
?>