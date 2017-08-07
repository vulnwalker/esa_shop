<?php
$HalBI = cekPOST("HalBI",1);
$HalBYY = cekPOST("HalBYY",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalBYY = " limit ".(($HalBYY*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
$cidBYY = cekPOST("cidBYY");

$fmTahunPerolehan = cekPOST("fmTahunPerolehan","");
$fmID = cekPOST("fmID",0);
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
setWilSKPD();
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWILSKPD = cekPOST("fmWILSKPD");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmNOREG = cekPOST("fmNOREG");
$fmTANGGALPEMBIAYAAN = cekPOST("fmTANGGALPEMBIAYAAN");
$fmBIAYABARANG = cekPOST("fmBIAYABARANG");
$fmBUKTIBIAYA = cekPOST("fmBUKTIBIAYA");
$fmIDREKENING = cekPOST("fmIDREKENING");
//echo $fmIDREKENING;
$fmKET = cekPOST("fmKET");

$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


if(($Act=="TambahEdit") && !isset($cidBI[0])){$Act="";}
$ReadOnly = ($Act=="Edit" || $Act=="TambahEdit")  &&  count($cidBYY) == 1 ? " readonly ":"";
$DisAbled = ($Act=="Edit" || $Act=="TambahEdit")  && count($cidBYY) == 1 ? " disabled ":"";

//ProsesCekField
$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALPEMBIAYAAN,fmBIAYABARANG,fmBUKTIBIAYA,fmIDREKENING,fmKET,fmTAHUNANGGARAN";

if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$ArWILSKPD = explode(".",$fmWILSKPD);
		$Simpan = false;
		
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into pembiayaan (a1,a,b,c,d,e,f,g,h,i,j,id_bukuinduk,noreg,thn_perolehan,tgl_pembiayaan,biaya_barang,bukti_pembiayaan,k,l,m,n,o,ket,tahun)
			values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmNOREG','$fmTAHUNPEROLEHAN','".TglSQL($fmTANGGALPEMBIAYAAN)."','$fmBIAYABARANG','$fmBUKTIBIAYA','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmKET','$fmTAHUNANGGARAN')";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
			$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPEMBIAYAAN)."','Entry Pembiayaan','$fmKONDISIBARANG','')");
			//$UpdateBI = mysql_query("update buku_induk set status_barang='3' where id='$fmIDBUKUINDUK'");
			/*
			$KondisiCek = $ArWILSKPD[0].$ArWILSKPD[1].$ArWILSKPD[2].$ArWILSKPD[3].$ArWILSKPD[4].$ArWILSKPD[5].$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmIDBUKUINDUK.$fmNOREG.$fmTahunPerolehan;
			$Q = mysql_fetch_array(mysql_query("select id from pembiayaan where concat(a1,a,b,c,d,e,f,g,h,i,j,id_bukuinduk,noreg,thn_perolehan)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
			echo $fmID;
			*/
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update pembiayaan set 
				tgl_pembiayaan = '".TglSQL($fmTANGGALPEMBIAYAAN)."', 
				biaya_barang = '$fmBIAYABARANG',
				bukti_pembiayaan = '$fmBUKTIBIAYA',
				k = '{$ArRekening[0]}',
				l = '{$ArRekening[1]}',
				m = '{$ArRekening[2]}',
				n = '{$ArRekening[3]}',
				o = '{$ArRekening[4]}',
				ket = '$fmKET'
			where $Kriteria ";
			//echo $Qry;
			$Simpan = mysql_query($Qry);
			$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,id_bukuinduk,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmTAHUNANGGARAN','$fmNOREG','".TglSQL($fmTANGGALPEMBIAYAAN)."','Update Pembiayaan','$fmKONDISIBARANG','')");
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
	$cidBYY = CekPOST("cidBYY");
	$cidNya = $cidBYY;
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
			$Qry = mysql_query("select * from pembiayaan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select * from buku_induk where buku_induk.id='{$cidNya[0]}'");
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));


		$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
		$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
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
			$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
			$fmNMREKENING = "{$nmRekening['nm_rekening']}";
			$fmTANGGALPEMBIAYAAN = TglInd($isi['tgl_pembiayaan']);
			$fmBIAYABARANG = $isi['biaya_barang'];
			$fmBUKTIBIAYA = $isi['bukti_pembiayaan'];
			$fmKET = $isi['ket'];

			$Baru=0;
		}
	}
}


//Proses HAPUS
$cidBYY = cekPOST("cidBYY");
if($Act=="Hapus" && count($cidBYY) > 0)
{
	for($i = 0; $i<count($cidBYY); $i++)
	{
		$cekIdBI = mysql_fetch_array(mysql_query("select id_bukuinduk from pembiayaan where id='{$cidBYY[$i]}'"));$cekIdBI = $cekIdBI[0];
		$Del = mysql_query("delete from pembiayaan where id='{$cidBYY[$i]}' limit 1");
		$UpdateBI = mysql_query("update buku_induk set status_barang='1' where id='$cekIdBI'");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}





//LIST BI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1' ";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang using(f,g,h,i,j) where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$jmlDataBI = mysql_num_rows($Qry);
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBI");


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
			<td class=\"GarisDaftar\" width=\"75\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" width=\"45\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" width=\"200\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"55\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']} {$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
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
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')." </td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
";
//ENDLIST BI

//LIST BYY
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
//$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and c='$fmSKPD' $KondisiD $KondisiE ";
if(!empty($fmBARANGCARIBYY))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIBYY%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and pembiayaan.thn_perolehan = '$fmTahunPerolehan' ";
}

// copy untuk kondisi jumlah total
$KondisiTotalBYY = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHargaBYY = mysql_query("select sum(biaya_barang) as total  from pembiayaan where $KondisiTotalBYY ");

if($jmlTotalHargaBYY = mysql_fetch_array($jmlTotalHargaBYY))
{
	$jmlTotalHargaBYY = $jmlTotalHargaBYY[0];
}
else
{$jmlTotalHargaBYY=0;}
// copy untuk kondisi jumlah total sampai sini

$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg ");
$jmlDataBYY = mysql_num_rows($Qry);
$Qry = mysql_query("select pembiayaan.*,ref_barang.nm_barang from pembiayaan inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a1,a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBYY");



$no=$Main->PagePerHal * (($HalBYY*1) - 1);
$cb=0;
$jmlTampilBYY = 0;
$jmlHargaPerHalBYY = 0;

$ListBarangBYY = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilBYY++;
	$no++;
	$jmlHargaPerHalBYY += $isi['biaya_barang'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangBYY .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cbBYY$cb\" name=\"cidBYY[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
			<td class=\"GarisDaftar\" width=\"75\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" width=\"45\" align=center>{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" width=\"200\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" width=\"55\" align=center>{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=center>".TglInd($isi['tgl_pembiayaan'])."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['biaya_barang'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\">{$isi['bukti_pembiayaan']}</td>
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangBYY .= "

		<tr class='row0'>
			<td colspan=7 class=\"GarisDaftar\">Total Harga per Halaman (Rp)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlHargaPerHalBYY, 2, ',', '.')."</td>
			<td colspan=3  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td class=\"GarisDaftar\" colspan=7>Total Harga Seluruhnya (Rp)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHargaBYY, 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" colspan=3>&nbsp;</td>
		</tr>
		";
//$ListBarangBYY .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListBYY, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST BYY

$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Pembiayaan Barang Milik Daerah </th>
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
	<!-- <td align=right width=30%>

			<table border=0 cellpadding=0 cellspacing=0>
			<tr >
				<td><input type=button onclick=\"javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit();\" value=\"Tambah\"></td>
			</tr>
			</table>
	</td> -->

	</tr>
	</table>

<!-BYY-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH class=\"GarisDaftar\" width=\"15\">No</TD>
		<TH class=\"GarisDaftar\" width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilPLH,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH class=\"GarisDaftar\" width=\"75\">Kode Barang</TH>
		<TH class=\"GarisDaftar\" width=\"45\">No.<br>Register</TH>
		<TH class=\"GarisDaftar\" width=\"200\">Nama Barang</TH>
		<TH class=\"GarisDaftar\" width=\"55\">Tahun<br>Perolehan</TH>
		<TH class=\"GarisDaftar\">Jumlah</TH>
		<TH class=\"GarisDaftar\">Harga Satuan <br>(Rp)</TH>
		<TH class=\"GarisDaftar\">Jumlah Harga <br>(Rp)</TH>
		<TH class=\"GarisDaftar\">Asal Usul</TH>
		<TH class=\"GarisDaftar\">Kondisi</TH>
		<TH class=\"GarisDaftar\">Status<br>Barang</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=12 align=center>
	".Halaman($jmlDataBI,$Main->PagePerHal,"HalBI")."
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
<!-END BI--><br>


<!-BYY-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PEMBIAYAAN BARANG MILIK DAERAH</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIBYY' value='$fmBARANGCARIBYY'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<TR>
		<TH class=\"th01\" width=\"15\">No</TD>
		<TH class=\"th01\" width=\"15\"><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataBYY,'cbBYY','toggle2');\" /></TD>
		<TH class=\"th01\" width=\"75\">Kode Barang</TH>
		<TH class=\"th01\" width=\"45\">No.<br>Register</TH>
		<TH class=\"th01\" width=\"200\">Nama Barang</TH>
		<TH class=\"th01\" width=\"55\">Tahun<br>Perolehan</TH>
		<TH class=\"th01\" width=\"75\">Tanggal<br>Pembiayaan</TH>
		<TH class=\"th01\" width=\"90\">Biaya Barang <br>(Rp)</TH>
		<TH class=\"th01\">Tanda Bukti<br>Pembiayaan</TH>
		<TH class=\"th01\" width=\"60\">Kode<br> Rekening</TH>
		<TH class=\"th01\">Keterangan</TH>
	</TR>
	$ListBarangBYY
	<tr>
	<td colspan=16 align=center>
	".Halaman($jmlDataBYY,$Main->PagePerHal,"HalBYY")."
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
<!-END BYY-->";
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
	<td>Tanggal Pembiayaan</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALPEMBIAYAAN")."
	</td>
	</tr>

	<td>Pembiayaan Barang (Rp)</td>
	<td>:</td>
	<td>
		".txtField('fmBIAYABARANG',$fmBIAYABARANG,'18','18','text',' ')."
	</td>
	</tr>

	<td>Tanda Bukti Pembiayaan</td>
	<td>:</td>
	<td>
		".txtField('fmBUKTIBIAYA',$fmBUKTIBIAYA,'100','50','text',' ')."
	</td>
	</tr>

	<tr valign=\"top\">   
	<td>Kode Rekening</td>
	<td>:</td>
	<td>
	".cariInfo("adminForm","pages/01/carirekening1.php","pages/01/carirekening2.php","fmIDREKENING","fmNMREKENING")."
	</td>
	</tr>



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
<input type=hidden name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
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