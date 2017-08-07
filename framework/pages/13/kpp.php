<?
$HalBI = cekPOST("HalBI",1);
$HalPTNG = cekPOST("HalPTNG",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalPTNG = " limit ".(($HalPTNG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidPTNG = cekPOST("cidPTNG");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmBARANGCARI = cekPOST("fmBARANGCARI");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Info = "";



//LIST BI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$jmlDataBI = mysql_num_rows($Qry);
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBI");


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
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg#KARTU';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" align=center width=\"65\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center width=\"40\">{$isi['noreg']}</td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center width=\"55\">{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" width=\"60\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" width=\"90\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" width=\"90\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
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
	<td class=\"GarisDaftar\" colspan=3 align=center><b>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlag Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
";
//ENDLIST BI




$cidBI = cekPOST("cidBI");

$Kondisi = "where id = '{$cidBI[0]}'";

$Qry = mysql_query("select * from buku_induk $Kondisi");
$isi = mysql_fetch_array($Qry);

$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));


$KodeBarang = "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}";
$NoRegister = "{$isi['noreg']}";
$NamaBarang = $nmBarang['nm_barang'];
$KodeLokasi = "{$isi['c']}.{$isi['d']}.{$isi['e']}";
$Tahun = $isi['tahun'];
$CekBarang = 
$KonKib = " where concat(f,g,h,i,j,noreg,tahun) ='".$kdBarang . $isi['noreg'] . $isi['tahun']."' ";
if($isi['f']=="01"){$Qry=mysql_query("select concat('Alamat : ',alamat,'\nNomor Sertifikat : ',sertifikat_no,' Tanggal :',mid(sertifikat_tgl,8,2),'-',mid(sertifikat_tgl,6,2),'-',mid(sertifikat_tgl,1,4),'\nPenggunaan : ',penggunaan,'\n',ket) as spesifikasi from kib_a $KonKib ");}
if($isi['f']=="03"){$Qry=mysql_query("select concat('Alamat : ',alamat,'\nNomor Dokumen : ',dokumen_no,' Tanggal :',mid(dokumen_tgl,8,2),'-',mid(dokumen_tgl,6,2),'-',mid(dokumen_tgl,1,4),'\n',ket) as spesifikasi from kib_c $KonKib ");}
if($isi['f']=="02"){$Qry=mysql_query("select concat('Merk : ',merk,', Ukuran : ',ukuran,', Bahan : ',bahan,'\n',ket) as spesifikasi from kib_b $KonKib ");}
if($isi['f']=="04"){$Qry=mysql_query("select concat('Alamat : ',alamat,', Kontruksi : ',konstruksi,', Panjang  : ',panjang,', Lebar  : ',lebar,'\n',ket) as spesifikasi from kib_d $KonKib ");}
if($isi['f']=="05"){$Qry=mysql_query("select concat(buku_judul,'\n',buku_spesifikasi) as spesifikasi from kib_e $KonKib ");}
if($isi['f']=="06"){$Qry=mysql_query("select concat('Merk : ',merk,', Ukuran : ',ukuran,', Bahan : ',bahan,'\n',ket) as spesifikasi from kib_f $KonKib ");}


//echo "select concat(alamat,'\n',sertifikat_no,sertifikat_tgl,'\n',penggunaan,ket) as spesifikasi from kib_a $KonKib ";
$isi = mysql_fetch_array($Qry);
$Spesifikasi = $isi['spesifikasi'];



$ListUraian = "";
//$Qry = mysql_query("select tgl_pemanfaatan as tgl,'Pemanfaatan' as kejadian,concat(kepada_nama,kepada_instansi,kepada_alamat,ket) as uraian from pemanfaatan $KonKib order by tgl_pemanfaatan desc");
$Qry = mysql_query("select * from history_barang where id_bukuinduk='{$cidBI[0]}' order by tgl_update");
$no=0;
while($isi=mysql_fetch_array($Qry))
{
	$no++;
	$StatusBarang = !empty($isi['status_barang'])?$Main->StatusBarang[$isi['status_barang']-1][1]:$Main->StatusBarang[0][1];
	$KondisiBarang = !empty($isi['kondisi'])?$Main->KondisiBarang[$isi['kondisi']-1][1] : $Main->KondisiBarang[0][1];

	$IsiEvent = "&nbsp;";
	switch($isi['kejadian'])
	{
	case "Entry Pemeliharaan":case "Update Pemeliharaan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from pemeliharaan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_pemeliharaan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Pemanfaatan":case "Update Pemanfaatan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from Pemanfaatan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_pemanfaatan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Pengamanan":case "Update Pengamanan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from pengamanan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_pengamanan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Penilaian":case "Update Penilaian":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from penilaian where id_bukuinduk={$isi['id_bukuinduk']} and tgl_penilaian='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Penghapusan":case "Update Penghapusan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from penghapusan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_penghapusan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Pemindahtanganan":case "Update Pemindahtanganan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from pemindahtanganan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_pemindahtanganan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Pembiayaan":case "Update Pembiayaan":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from pembiayaan where id_bukuinduk={$isi['id_bukuinduk']} and tgl_pembiayaan='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;
	case "Entry Ganti Rugi":case "Update Ganti Rugi":
		$QryEvent = mysql_fetch_array(mysql_query("select ket from gantirugi where id_bukuinduk={$isi['id_bukuinduk']} and tgl_gantirugi='{$isi['tgl_update']}'"));
		$IsiEvent = $QryEvent['ket'];
	break;

	}
	$ListUraian .="
	<tr>
		<td class=\"GarisDaftar\" align=center>$no.</td>
		<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_update'])."</td>
		<td class=\"GarisDaftar\">{$isi['kejadian']}</td>
		<td class=\"GarisDaftar\">$IsiEvent</td>
		<td class=\"GarisDaftar\">$StatusBarang</td>
		<td class=\"GarisDaftar\">$KondisiBarang</td>
	</tr>
	
	";
}

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">Kartu Pengawasan dan Pengendalian Barang Milik Daerah</th>
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
	</tr>
	</table>

<!-PTNG-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH>No</TD>
		<TH><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilPTNG,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH>Kode Barang</TH>
		<TH>No.<br>Register</TH>
		<TH>Nama Barang</TH>
		<TH>Tahun<br>Perolehan</TH>
		<TH>Jumlah</TH>
		<TH>Harga Satuan (Rp)</TH>
		<TH>Jumlah Harga</TH>
		<TH>Asal Usul</TH>
		<TH>Kondisi</TH>
		<TH>Status<br>Barang</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=12 align=center>
	".Halaman($jmlDataBI,$Main->PagePerHal,"HalBI")."
	</td>
	</tr>
	</TABLE>
<!-END BI--><br>
<A NAME=KARTU></A>
<br>
<table width=\"100%\" class=\"adminform\">
	<tr>
		<td width=150>Kode Barang</td>
		<td>:</td>
		<td><b>$KodeBarang&nbsp;</td>
	</tr>
	<tr>
		<td>Nomor Register</td>
		<td>:</td>
		<td><b>$NoRegister&nbsp;</td>
	</tr>
	<tr>
		<td>Nama Barang</td>
		<td>:</td>
		<td><b>$NamaBarang&nbsp;</td>
	</tr>
	<tr>
		<td>Spesifikasi / Merk</td>
		<td>:</td>
		<td><TEXTAREA NAME=\"\" ROWS=\"6\" COLS=\"80\">$Spesifikasi</TEXTAREA></td>
	</tr>
</table>
<br>
<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
		<td class=\"contentheading\">
		<DIV ALIGN=CENTER>KARTU PENGAWASAN DAN PENGENDALIAN BARANG MILIK DAERAH</DIV>
		</td>
	</tr>
</table>
<table width=\"100%\" class=\"contentheading\">
	<tr>
		<td align=right>Kode Lokasi : <b>$KodeLokasi</td>
	</tr>
</table>
<br>
<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" class=\"koptable\">
	<tr>
		<th class=\"th01\" width=\"30\">No.</th>
		<th class=\"th01\" width=\"90\">Tanggal</th>
		<th class=\"th01\">Kejadian</th>
		<th class=\"th01\">Uraian</th>
		<th class=\"th01\" width=\"120\">Status Barang</th>
		<th class=\"th01\" width=\"120\">Kondisi</th>
	</tr>
	$ListUraian
</table>
";
?>