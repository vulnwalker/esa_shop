<?php
$HalBI = cekPOST("HalBI",1);
$HalRKPB = cekPOST("HalRKPB",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalRKPB = " limit ".(($HalRKPB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidRKPB = cekPOST("cidRKPB");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTAHUNPEROLEHAN = cekPOST("fmTAHUNPEROLEHAN");
$fmSATUAN = cekPOST("fmSATUAN");


$fmJUMLAH = cekPOST("fmJUMLAH");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmURAIAN = cekPOST("fmURAIAN");
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


if(($Act=="TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidRKPB) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidRKPB) == 1 ? " disabled ":"";

//ProsesCekField
$MyField ="fmWILSKPD,fmIDBARANG,fmNOREG,fmTAHUNPEROLEHAN,fmJUMLAH,fmHARGASATUAN,fmSATUAN,fmIDREKENING,fmURAIAN,fmKET,fmTAHUNANGGARAN";

if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArWILSKPD = explode(".",$fmWILSKPD);
		$ArRekening = explode(".",$fmIDREKENING);
		$Simpan = false;
		$JmlBIAYA = $fmHARGASATUAN*$fmJUMLAH;
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into rkpb (a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,noreg,thn_perolehan,jml_barang,harga,satuan,jml_biaya,uraian,ket,tahun)
			values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmNOREG','$fmTAHUNPEROLEHAN','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$JmlBIAYA','$fmURAIAN','$fmKET','$fmTAHUNANGGARAN')";
			$Simpan = mysql_query($Qry);
		}
		if($Baru=="0")
		{
			/*
				fmJUMLAH
				fmHARGASATUAN
				fmSATUAN
				fmIDREKENING
				fmURAIAN
				fmKET
				*/
			$Kriteria = "id='$fmID'";
			$Qry = "
			update rkpb set 
				jml_barang = '$fmJUMLAH', harga = '$fmHARGASATUAN', satuan = '$fmSATUAN', uraian = '$fmURAIAN', ket = '$fmKET', jml_biaya = '$JmlBIAYA',k='{$ArRekening[0]}',l='{$ArRekening[1]}',m='{$ArRekening[2]}',n='{$ArRekening[3]}',o='{$ArRekening[4]}'
			where $Kriteria ";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			//KosongkanField("fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmJENISBARANG,fmKET,fmTANGGALSKGUBERNUR,fmNOSKGUBERNUR,fmKONDISIBAIK,fmKONDISIKURANGBAIK,fmTANGGALBELI");
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

$cidBI = CekPOST("cidBI");
$cidNya = $cidBI;
if($Act == "Edit")
{
	$cidRKPB = CekPOST("cidRKPB");
	$cidNya = $cidRKPB;
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
			$Qry = mysql_query("select * from rkpb where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from view_bi_pemeliharaan where view_bi_pemeliharaan.id='{$cidNya[0]}'");
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$fmWILSKPD = $isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
		$fmWILSKPD = $fmWILSKPD == "...." ? "" :$fmWILSKPD;
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmTAHUNANGGARAN = "{$isi['tahun']}";
		$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
		$fmNOREG = $isi['noreg'];
		if($Act=="Edit")
		{
			$fmIDBUKUINDUK=$isi['id_bukuinduk'];
		}
		else
		{
			$fmIDBUKUINDUK=$isi['id'];
		}
		$fmID = "{$isi['id']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
		}
		else
		{
			$fmJUMLAH="{$isi['jml_barang']}";
			$fmHARGASATUAN="{$isi['harga']}";
			$fmSATUAN="{$isi['satuan']}";
			$fmURAIAN="{$isi['uraian']}";
			$fmKET			="{$isi['ket']}";

			$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
			$fmIDREKENING = $fmIDREKENING == "...." ? "":$fmIDREKENING;

			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidRKPB = cekPOST("cidRKPB");
if($Act=="Hapus" && count($cidRKPB) > 0)
{
	for($i = 0; $i<count($cidRKPB); $i++)
	{
		$Del = mysql_query("delete from rkpb where id='{$cidRKPB[$i]}' limit 1");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}





//LIST BI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and view_bi_pemeliharaan.status_barang = '1' ";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and view_bi_pemeliharaan.thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(view_bi_pemeliharaan.jml_harga) as total  from view_bi_pemeliharaan inner join ref_barang using(f,g,h,i,j) where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select view_bi_pemeliharaan.*,ref_barang.nm_barang from view_bi_pemeliharaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlDataBI = mysql_num_rows($Qry);
$Qry = mysql_query("select view_bi_pemeliharaan.*,ref_barang.nm_barang from view_bi_pemeliharaan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalBI");


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
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilBI, 'cb', this);
			adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td class=\"GarisDaftar\" align=center width=\"75\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center width=\"40\">{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center width=\"55\">{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"55\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['biaya_pemeliharaan'], 2, ',', '.')."</td>
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
	<td class=\"GarisDaftar\" colspan=4 align=center>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=4><b></td>
</tr>

";
//ENDLIST BI

//LIST RKPB
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIRKPB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIRKPB%' ";
}
/*
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and rkpb.thn_perolehan = '$fmTahunPerolehan' ";
}
*/

// copy untuk kondisi jumlah total
$KondisiTotalRKPB = $Kondisi;
/*
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
*/
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHargaRKPB = mysql_query("select sum(jml_biaya) as total  from rkpb where $KondisiTotalRKPB ");

if($jmlTotalHargaRKPB = mysql_fetch_array($jmlTotalHargaRKPB))
{
	$jmlTotalHargaRKPB = $jmlTotalHargaRKPB[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini


$Qry = mysql_query("select rkpb.*,ref_barang.nm_barang from rkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataRKPB = mysql_num_rows($Qry);
$Qry = mysql_query("select rkpb.*,ref_barang.nm_barang from rkpb inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalRKPB");
$no=$Main->PagePerHal * (($HalRKPB*1) - 1);
$cb=0;
$jmlTampilRKPB = 0;
$jmlTotalHargaListRKPB = 0;
$ListBarangRKPB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilRKPB++;
	$jmlTotalHargaListRKPB += $isi['jml_biaya'];
	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdBarang1 = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['noreg'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$TotalBiayaPemeliharaan = mysql_fetch_array(mysql_query("select sum(biaya_pemeliharaan) as biaya from pemeliharaan where concat(f,g,h,i,j,noreg)='$kdBarang1' "));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangRKPB .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cbRKPB$cb\" name=\"cidRKPB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" align=center width=\"75\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center width=\"40\">{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center width=\"55\">{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"55\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_biaya'], 2, ',', '.')."</td>
			<!-- <td class=\"GarisDaftar\" align=right>".number_format($TotalBiayaPemeliharaan[0], 2, ',', '.')."</td> -->
			<td class=\"GarisDaftar\">{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['uraian']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangRKPB .= "
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHargaListRKPB, 2, ',', '.')." </td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHargaRKPB, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3><b></td>
</tr>

";

//$ListBarangRKPB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListRKPB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST RKPB

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Rencana Kebutuhan Pemeliharaan Barang Milik Daerah </th>
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

<!-RKPB-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilRKPB,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH width=\"75\">Kode <br>Barang</TH>
		<TH width=\"40\">Nomor<br>Reg.</TH>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"55\">Tahun<br>Perolehan</TH>
		<TH width=\"55\">Jumlah</TH>
		<TH>Harga Satuan<br> (Rp)</TH>
		<TH>Jumlah Harga<br>(Rp)</TH>
		<TH>Total Biaya <br>Pemeliharaan<br>(Rp)</TH>
		<TH>Asal Usul</TH>
		<TH>Kondisi</TH>
		<TH>Status<br>Barang</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=13 align=center>
	".Halaman($jmlDataBI,$Main->PagePerHal,"HalBI")."
	</td>
	</tr>
	</TABLE>
<!-END BI--><br>


<!-RKPB-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR RENCANA KEBUTUHAN PEMELIHARAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIRKPB' value='$fmBARANGCARIRKPB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataRKPB,'cbRKPB','toggle2');\" /></TD>
		<TH width=\"75\">Kode<br> Barang</TH>
		<TH width=\"40\">Nomor<br>Reg.</TH>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"55\">Tahun<br>Perolehan</TH>
		<TH width=\"55\">Jumlah</TH>
		<TH>Harga Satuan<br>(Rp)</TH>
		<TH>Jumlah Harga<br>(Rp)</TH>
		<!-- <TH>Total Biaya<br> Pemeliharaan<br>(Rp)</TH> -->
		<TH>Kode <br>Rekening</TH>
		<TH>Uraian<br> Pemeliharaan</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarangRKPB
	<tr>
	<td colspan=15 align=center>
	".Halaman($jmlDataRKPB,$Main->PagePerHal,"HalRKPB")."
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
<!-END RKPB-->";
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
		".txtField('fmIDBARANG',$fmIDBARANG,'30','20','text',' readonly ')."
		".txtField('fmNMBARANG',$fmNMBARANG,'100','100','text',' readonly ')."
	</td>
	</tr>
	<TR>
	<td>Nomor Register</td>
	<td>:</td>
	<td>
		".txtField('fmNOREG',$fmNOREG,'6','4','text',' readonly ')."
	</td>
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
		<td>Kode Rekening</td>
		<td>:</td>
		<td>
		".cariInfo("adminForm","pages/01/carirekening1.php","pages/01/carirekening2.php","fmIDREKENING","fmNMREKENING")."
		</td>
		</tr>

	<tr valign=\"top\">
	  <td>Uraian Pemeliharaan</td>
	  <td>:</td>
	  <td><textarea name=\"fmURAIAN\" cols=\"60\" >$fmURAIAN</textarea></td>
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
<input type=hidden name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
<input type=hidden name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
<input type=hidden name='Act'>
<input type=hidden name='Baru' value='$Baru'>
</td></tr></table>
		<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
</form>
";
?>