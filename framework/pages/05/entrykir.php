<?php
$HalBI = cekPOST("HalBI",1);
$HalKIR = cekPOST("HalKIR",1);
$LimitHalBI = " limit ".(($HalBI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalKIR = " limit ".(($HalKIR*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cidBI = cekPOST("cidBI");
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



$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//ProsesCekField
$MyField ="fmWILSKPD,fmIDBARANG,fmIDBUKUINDUK,fmNOREG,fmTAHUNPEROLEHAN,fmTANGGALKIR,fmGEDUNG,fmRUANG,fmKET,fmTAHUNANGGARAN";

if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArWILSKPD = explode(".",$fmWILSKPD);
		$Simpan = false;
		
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into kir (a1,a,b,c,d,e,f,g,h,i,j,id_bukuinduk,noreg,thn_perolehan,tgl_update,p,q,ket,tahun)
			values ('{$ArWILSKPD[0]}','{$ArWILSKPD[1]}','{$ArWILSKPD[2]}','{$ArWILSKPD[3]}','{$ArWILSKPD[4]}','{$ArWILSKPD[5]}','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmIDBUKUINDUK','$fmNOREG','$fmTAHUNPEROLEHAN','".TglSQL($fmTANGGALKIR)."','$fmGEDUNG','$fmRUANG','$fmKET','$fmTAHUNANGGARAN')";
			$Simpan = mysql_query($Qry);
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update kir set 
				tgl_update = '".TglSQL($fmTANGGALKIR)."', 
				p = '$fmGEDUNG',q='$fmRUANG',
				ket = '$fmKET'
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
$cidBI = cekPOST("cidBI");
$cidNya = $cidBI;
if($Act == "Edit")
{
	$cidKIR = cekPOST("cidKIR");
	$cidNya = $cidKIR;
}

if($Act=="Edit"|| $Act == "TambahEdit")
{
	
	if(count($cidNya) != 1)
	{
		$Info = "<script>alert('Pilih hanya satu ID yang dapat di Ubah')</script>";
	}
	else
	{
		if($Act=="Edit" && !empty($cidKIR))
		{
			
			$Qry = mysql_query("select * from kir where id='{$cidNya[0]}'");
			$isi = mysql_fetch_array($Qry);
			$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
			$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
			$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
			$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
			$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
			$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
			$fmNMBARANG = "{$nmBarang['nm_barang']}";
			$fmTAHUNANGGARAN = "{$isi['tahun']}";
			$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
			$fmGEDUNG = $isi['p'];
			$fmRUANG = $isi['q'];
			$fmTANGGALKIR = TglIND($isi['tgl_update']);
			$fmNOREG = $isi['noreg'];
			$fmKET = $isi['ket'];
			//echo "select * from kir where id='{$cidNya[0]}";
			$Baru = "0";
			$fmID = $isi['id'];	
			$fmIDBUKUINDUK = $isi['id'];
		}
		if($Act=="TambahEdit" && !empty($cidNya[0]))
		{
			$Qry = mysql_query("select * from buku_induk where buku_induk.id='{$cidNya[0]}'");
			$isi = mysql_fetch_array($Qry);
			$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
			$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
			$fmWILSKPD = $isi['a1'].".".$isi['a'].".".$isi['b'].".".$isi['c'].".".$isi['d'].".".$isi['e'];
			$fmWILSKPD = $fmWILSKPD == "....." ? "" :$fmWILSKPD;
			$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
			$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
			$fmNMBARANG = "{$nmBarang['nm_barang']}";
			$fmTAHUNANGGARAN = "{$isi['tahun']}";
			$fmTAHUNPEROLEHAN = "{$isi['thn_perolehan']}";
			$fmNOREG = $isi['noreg'];
			$fmIDBUKUINDUK=$isi['id'];
			$Baru=1;
		}
	}
}
//echo $fmIDBUKUINDUK;

//Proses HAPUS
$cidKIR = cekPOST("cidKIR");
if($Act=="Hapus" && count($cidKIR) > 0)
{
	for($i = 0; $i<count($cidKIR); $i++)
	{
		$cekIdBI = mysql_fetch_array(mysql_query("select id_bukuinduk from kir where id='{$cidKIR[$i]}'"));$cekIdBI = $cekIdBI[0];
		$Del = mysql_query("delete from kir where id='{$cidKIR[$i]}' limit 1");
		$UpdateBI = mysql_query("update buku_induk set status_barang='1' where id='$cekIdBI'");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}





//LIST BI
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a1='$fmKEPEMILIKAN' and a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1'  and (buku_induk.f='02' or buku_induk.f='05' )";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}

//$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $KondisiTotal ");
$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang  using(f,g,h,i,j) where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


//$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$jmlDataBI = mysql_num_rows($Qry);
//$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalBI");
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
			<td class=\"GarisDaftar\" align=center width=\"75\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td class=\"GarisDaftar\" align=center width=\"40\">{$isi['noreg']}</td>
			<td class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\" align=center width=\"55\">{$isi['thn_perolehan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"60\">{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right width=\"90\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
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
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>

";
//ENDLIST BI



//LIST KIR
$KondisiD = $fmUNIT == "00" ? "":" and buku_induk.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and buku_induk.e='$fmSUBUNIT' ";
$Kondisi = "buku_induk.a1='$fmKEPEMILIKAN' and buku_induk.a='{$Main->Provinsi[0]}' and buku_induk.b='$fmWIL' and buku_induk.c='$fmSKPD' $KondisiD $KondisiE and buku_induk.status_barang = '1'  and (buku_induk.f='02' or buku_induk.f='05' )";
$KondisiTotal = $Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and buku_induk.thn_perolehan = '$fmTahunPerolehan' ";
}

//$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j)  inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun)  where $KondisiTotal ");
$jmlTotalHarga = mysql_query("select sum(buku_induk.jml_harga) as total  from buku_induk inner join ref_barang using(f,g,h,i,j)  inner join kir using(a,b,c,d,e,f,g,h,i,j,noreg,tahun)  where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}


//$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang using(f,g,h,i,j) inner join kir using(a,b,c,d,e,f,g,h,i,j,noreg,tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg");
//echo "select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";

$jmlDataKIR = mysql_num_rows($Qry);
//$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang on concat(buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) inner join kir on concat(buku_induk.a,buku_induk.b,buku_induk.c,buku_induk.d,buku_induk.e,buku_induk.f,buku_induk.g,buku_induk.h,buku_induk.i,buku_induk.j,buku_induk.noreg,buku_induk.tahun)=concat(kir.a,kir.b,kir.c,kir.d,kir.e,kir.f,kir.g,kir.h,kir.i,kir.j,kir.noreg,kir.tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalKIR");
$Qry = mysql_query("select buku_induk.*,ref_barang.nm_barang,kir.p,kir.q,kir.ket as ketkir,kir.tgl_update as kir_tgl_update,kir.id as id_kir from buku_induk inner join ref_barang using(f,g,h,i,j) inner join kir using(a,b,c,d,e,f,g,h,i,j,noreg,tahun) where $Kondisi order by a,b,c,d,e,f,g,h,i,j,noreg $LimitHalKIR");


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
	
		<tr class='$clRow'>
			<td  class=\"GarisDaftar\" align=center width=\"15\">$no</td>
			<td  class=\"GarisDaftar\" align=center width=\"15\"><input type=\"checkbox\" id=\"cbKIR$cb\" name=\"cidKIR[]\" value=\"{$isi['id_kir']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td></td>
			<td  class=\"GarisDaftar\" align=center width=\"75\">{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
			<td  class=\"GarisDaftar\" align=center width=\"40\">{$isi['noreg']}</td>
			<td  class=\"GarisDaftar\" width=\"180\">{$nmBarang['nm_barang']}</td>
			<td  class=\"GarisDaftar\" align=center width=\"55\">{$isi['thn_perolehan']}</td>
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
$ListBarangKIR .= "
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga per Halaman (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($JmlTotalHargaListKIR, 2, ',', '.')." </td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
<tr>
	<td class=\"GarisDaftar\" colspan=8>Jumlah Harga Seluruhnya (Rp)</td>
	<td class=\"GarisDaftar\" align=right ><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
	<td class=\"GarisDaftar\" colspan=3 align=center>&nbsp;</td>
</tr>
";
//ENDLIST KIR

$Main->Isi = "

$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#FORMENTRY\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Kartu Inventaris Ruangan</th>
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
	<DIV ALIGN=CENTER>DAFTAR INVENTARIS BARANG</DIV>
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

<!-BI-->
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilKIR,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH width=\"75\">Kode Barang</TH>
		<TH width=\"40\">No.<br>Register</TH>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"55\">Tahun<br>Perolehan</TH>
		<TH width=\"60\">Jumlah</TH>
		<TH width=\"90\">Harga Satuan <br>(Rp)</TH>
		<TH width=\"90\">Jumlah Harga <br>(Rp)</TH>
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


<!-KIR-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR KIR BARANG</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIKIR' value='$fmBARANGCARIKIR'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH width=\"15\">No</TD>
		<TH width=\"15\"><!-- <input type=\"checkbox\" name=\"toggle1\" value=\"\" onClick=\"checkAll1($jmlTampilKIR,'cb','toggle1');\" /> -->&nbsp;</TD>
		<TH width=\"75\">Kode Barang</TH>
		<TH width=\"40\">No.<br>Register</TH>
		<TH width=\"180\">Nama Barang</TH>
		<TH width=\"55\">Tahun<br>Perolehan</TH>
		<TH width=\"60\">Jumlah</TH>
		<TH width=\"90\">Harga Satuan<br> (Rp)</TH>
		<TH width=\"90\">Jumlah Harga <br> (Rp)</TH>
		<TH>Asal Usul</TH>
		<TH>Kondisi</TH>
		<TH>Status<br>Barang</TH>
	</TR>
	$ListBarangKIR
	<tr>
	<td class=\"GarisDaftar\" colspan=16 align=center>
	".Halaman($jmlDataKIR,$Main->PagePerHal,"HalKIR")."
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
<!-END KIR-->";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
$ListGEDUNG = cmbQuery1("fmGEDUNG",$fmGEDUNG,"select p,nm_ruang from ref_ruang where p!='000' and q ='000' order by p,q","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListRUANG = cmbQuery1("fmRUANG",$fmRUANG,"select q,nm_ruang from ref_ruang where p='$fmGEDUNG' and p !='000' and q != '000'  order by p,q ","",'Pilih','');

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
		<tr>
		<td WIDTH='10%'>GEDUNG</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListGEDUNG</td>
		</tr>

		<tr>
		<td WIDTH='10%'>RUANGAN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListRUANG</td>
		</tr>

		
	<tr valign=\"top\">   
	<td>Tanggal Update</td>
	<td>:</td>
	<td>
		".InputKalender("fmTANGGALKIR")."
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
<input type=hidden name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
<input type=hidden name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
<input type=hidden name='fmWILSKPD' value='$fmWILSKPD'>
<input type=hidden name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
<input type=hidden name='Act' value='$Act'>
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