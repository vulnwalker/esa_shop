<?php
$HalRKB = isset($HTTP_POST_VARS["HalRKB"]) ? $HTTP_POST_VARS["HalRKB"] : 1;

$LimitHal = " limit ".(($HalRKB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$fmID = cekPOST("fmID",0);
$cid = CekPOST("cid");
$fmWIL = cekPOSTCOOKIE("fmWIL","$cofmWIL",$Main->DEF_WILAYAH); 
if ($fmWIL == '' ) $fmWIL = $Main->DEF_WILAYAH; //$cek.= 'fmwil='.$fmWIL.' cofmwil='.$cofmWIL;
$fmSKPD = cekPOSTCOOKIE("fmSKPD","cofmSKPD"); //cekPOST('fmSKPD');//cekPOSTCOOKIE("fmSKPD","cofmSKPD");
	//echo 'fmskpd='.$fmSKPD;
$fmUNIT = cekPOSTCOOKIE("fmUNIT","cofmUNIT");
$fmSUBUNIT = cekPOSTCOOKIE("fmSUBUNIT","cofmSUBUNIT");
$fmTAHUNANGGARAN =cekPOSTCOOKIE("fmTAHUNANGGARAN","cofmTAHUNANGGARAN",date("Y"));
setWilSKPD();
//echo '<br>fmskpd='.$fmSKPD;

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

$Cari = cekPOST("Cari");

$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";


if($Act=="Edit" && !isset($cid[0])){$Act="";}
$ReadOnly = $Act=="Edit" &&  count($cid) == 1 ? " readonly ":"";
$DisAbled = $Act=="Edit" && count($cid) == 1 ? " disabled ":"";



//echo $HTTP_COOKIE_VARS['cofmWIL'];

//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField)){
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$JmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$KondisiCek = $Main->Provinsi[0].$fmWIL.$fmSKPD.$fmUNIT.$fmSUBUNIT.$ArBarang[0].$ArBarang[1].$ArBarang[2].$ArBarang[3].$ArBarang[4].$fmTAHUNANGGARAN;
		//$CekID = false;
		//$Qry = mysql_query("select * from rkb where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'");
		//$CekID = mysql_num_rows($Qry);
		//echo $CekID;
		//echo "select * from rkb where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek'";
		//if($Baru=="1")
		//	{
		//		//$CekID = false;
		//		$Info = "<script>alert('Data Barang sudah ada, data tidak dapat disimpan')</script>";
		//	}
		$Simpan = false;
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into rkb (a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,merk_barang,jml_barang,harga,satuan,jml_harga,ket,tahun)
			values ('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmMEREK','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$JmlHARGA','$fmKET','$fmTAHUNANGGARAN')";
			$Simpan = mysql_query($Qry);
			$Q = mysql_fetch_array(mysql_query("select * from rkb where concat(a,b,c,d,e,f,g,h,i,j,tahun)='$KondisiCek' order by id desc"));
			$fmID = $Q['id'];
		}
		if($Baru=="0")
		{
			$Kriteria = "id='$fmID'";
			$Qry = "
			update rkb set 
				k = '{$ArRekening[0]}',l = '{$ArRekening[1]}',m = '{$ArRekening[2]}',n = '{$ArRekening[3]}',o = '{$ArRekening[4]}',	merk_barang='$fmMEREK',jml_barang='$fmJUMLAH',harga='$fmHARGASATUAN',satuan='$fmSATUAN',jml_harga='$JmlHARGA',ket='$fmKET'
			where $Kriteria ";
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
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
		$data='';//"$fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmIDBARANG - $fmNMBARANG,$fmMEREK,$fmJUMLAH,$fmSATUAN,$fmHARGASATUAN - $fmIDREKENING,$fmKET,$fmTAHUNANGGARAN";
		$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan \\n$data')</script>";
	}
	
}


//Proses EDIT -------------------------------------------------------
$cid = CekPOST("cid");
if($Act=="Edit")
{
	if(count($cid) != 1)
	{
		$Info = "<script>alert('Pilih hanya satu ID yang dapat di Ubah')</script>";
	}
	else
	{
		$Qry = mysql_query("select * from rkb where id='{$cid[0]}'");
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmMEREK = "{$isi['merk_barang']}";
		$fmJUMLAH = "{$isi['jml_barang']}";
		$fmSATUAN = "{$isi['satuan']}";
		$fmHARGASATUAN = "{$isi['harga']}";
		$fmIDREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
		$fmNMREKENING = "{$nmRekening['nm_rekening']}";
		$fmKET = "{$isi['ket']}";
		$fmID = "{$isi['id']}";
		$Baru=0;
	}
}


//Proses HAPUS ------------------------------------------------------------------
if($Act=="Hapus" && count($cid) > 0)
{
	for($i = 0; $i<count($cid); $i++)
	{
		$Del = mysql_query("delete from rkb where id='{$cid[$i]}' limit 1");
		$Info = "<script>alert('Data telah di hapus')</script>";
	}
}

if($Act=="Add")
{
		$fmIDBARANG = "";
		$fmNMBARANG = "";
		$fmMEREK = "";
		$fmJUMLAH = "";
		$fmSATUAN = "";
		$fmHARGASATUAN = "";
		$fmIDREKENING = "";
		$fmNMREKENING = "";
		$fmKET = "";
		$fmID = "";
		$Baru=1;
}

//list -------------------------------------------------------------
$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$KondisiC = "and c = '$fmSKPD'";//$fmSKPD =='' || $fmSKPD =='00'? '' : "and c = '$fmSKPD'";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL'  $KondisiC $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";
//echo 'Kondisi='.$Kondisi;
if(!empty($fmBARANGCARI))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARI%' ";
}

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from rkb where $Kondisi");
$jmlTotalHarga = mysql_query("select sum(rkb.jml_harga) as total  from rkb inner join ref_barang on concat(rkb.f,rkb.g,rkb.h,rkb.i,rkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select rkb.*,ref_barang.nm_barang from rkb inner join ref_barang on concat(rkb.f,rkb.g,rkb.h,rkb.i,rkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select rkb.*,ref_barang.nm_barang from rkb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlData = mysql_num_rows($Qry);

$Qry = mysql_query("select rkb.*,ref_barang.nm_barang from rkb inner join ref_barang using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal ");
//echo "select rkb.*,ref_barang.nm_barang from rkb inner join ref_barang on concat(rkb.f,rkb.g,rkb.h,rkb.i,rkb.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi $LimitHal order by a,b,c,d,e,f,g,h,i,j";
$ListBarang = "";
$no=$Main->PagePerHal * (($HalRKB*1) - 1);
$JmlTotalHargaListRKB = 0;
$JmlDataTampil=0;
$cb=0;
while ($isi = mysql_fetch_array($Qry)){
	$no++;
	$JmlDataTampil++;
	$JmlTotalHargaListRKB += $isi['jml_harga'];
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$Checked = $fmID == $isi['id'] ? "checked":"";
	$ListBarang .= "
	
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=center>$no</td>
			<td class=\"GarisDaftar\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cid[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" /></td>
			<td class=\"GarisDaftar\">{$nmBarang['nm_barang']}</td>
			<td class=\"GarisDaftar\">{$isi['merk_barang']}</td>
			<td class=\"GarisDaftar\" align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td class=\"GarisDaftar\" align=center>{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}</td>
			<td class=\"GarisDaftar\">{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarang .= "
	<tr>
		<td class=\"GarisDaftar\" colspan=6>Total Harga per Halaman(Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($JmlTotalHargaListRKB, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td>
	</tr>
	<tr>
		<td class=\"GarisDaftar\" colspan=6>Total Harga Seluruhnya (Rp)</td>
		<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
		<td class=\"GarisDaftar\" colspan=2 align=right>&nbsp;</td>
	</tr>

";


$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Rencana Kebutuhan Barang Milik Daerah </th>
</tr>
</table>
<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">".
	//WilSKPD1().
	WilSKPD().
"<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER><b>DAFTAR RENCANA KEBUTUHAN BARANG MILIK DAERAH</b></DIV>
	</td>
	</tr>
	</table>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
	<td width=10% >Nama Barang</td>
	<td width=1% >:</td>
	<td>
	<input $ReadOnly type=text name='fmBARANGCARI' value='$fmBARANGCARI'>&nbsp<input $DisAbled type=button value='Cari' onclick=\"adminForm.submit()\">
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH>No</TD>
		<TH><input type=\"checkbox\" name=\"toggle\" value=\"\" onClick=\"checkAll($JmlDataTampil);\" /></TD>
		<TH>Nama Barang</TH>
		<TH>Merk / Type / Ukuran /<br> Spesifikasi</TH>
		<TH>Jumlah</TH>
		<TH>Harga Satuan<br> (Rp)</TH>
		<TH>Jumlah Harga <br>(Rp)</TH>
		<TH>Kode<br> Rekening</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarang
	<tr>
	<td colspan=9 align=center>
	".Halaman($jmlData,$Main->PagePerHal,"HalRKB")."
	</td>
	</tr>
	</TABLE>
<br>
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>	<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah","$ReadOnly","$DisAbled")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Edit';adminForm.submit()","edit_f2.png","Ubah","$ReadOnly","$DisAbled")."</td>
			<td>".PanelIcon1("javascript:if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){adminForm.Act.value='Hapus';adminForm.submit();}","delete_f2.png","Hapus","$ReadOnly","$DisAbled")."</td>
			<!-- <td>".PanelIcon1("javascript:adminForm.Act.value='Cetak';adminForm.submit()","print_f2.png","Cetak","$ReadOnly","$DisAbled")."</td> -->
			</tr></table>
		</td></tr>
	</table>

<br>
";

if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahBaru"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "
<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr valign=\"top\">   
	<td>Nama Barang</td>
	<td>:</td>
	<td>".
	//cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG","$ReadOnly","$DisAbled").
	cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG","$ReadOnly","$DisAbled").
	"</td>
	</tr>

	<tr valign=\"top\">
	<td width=\"184\" height=\"29\">Merk / Type / Ukuran / Spesifikasi</td>
	<td width=\"33\">:</td>
	<td width=\"804\"><textarea name=\"fmMEREK\" cols=\"60\">$fmMEREK</textarea></td>
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
	<!--
    <input type=\"text\" name=\"fmHARGASATUAN\" value=\"$fmHARGASATUAN\" />
	-->
	".inputFormatRibuan("fmHARGASATUAN")."
	</td>

</tr>

	<tr valign=\"top\">   
	<td>Kode Rekening</td>
	<td>:</td>
	<td>
	".cariInfo("adminForm","pages/01/carirekening1.php","pages/01/carirekening2.php","fmIDREKENING","fmNMREKENING")."
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
			".PanelIcon1("javascript:adminForm.Act.value='';adminForm.submit()","cancel_f2.png","Batal")."
			</td>
		</tr></table>
	</td></tr>
	</table>
";
}//END IF


$Main->Isi .= 
$cek.
"<script language='javascript'>
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
		<input type=\"hidden\" name=\"boxchecked\" value=\"$boxchecked\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />

		<input type=hidden name='Act'>
		<input type=hidden name='Baru' value='$Baru'>

</form>

";
?>