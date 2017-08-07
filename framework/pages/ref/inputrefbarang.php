<?php
$HalBRG = cekPOST("HalBRG",1);
$LimitHalBRG = " limit ".(($HalBRG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmBIDANG = cekPOST("fmBIDANG","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmSUBKELOMPOK = cekPOST("fmSUBKELOMPOK","");
$fmSUBSUBKELOMPOK = cekPOST("fmSUBSUBKELOMPOK","");


$fmKODEBARANG = cekPOST("fmKODEBARANG","");
$fmNAMABARANG = cekPOST("fmNAMABARANG","");

$fmKODEBRGf = cekPOST("fmKODEBRGf","");
$fmNAMABRGf = cekPOST("fmNAMABRGf","");


$Act = cekPOST("Act","");
$cidBRG = cekPOST("cidBRG","");
$BARU = cekPOST("BARU","1");
$Info = "";
$disSIMPAN = " disabled ";
$TombolSimpan = "";
$kdSubsubkel0 = genNumber(0, $Main->SUBSUBKEL_DIGIT );
if($Act == "Simpan")
{
	$Simpan = false;
	if($BARU == "1" || $BARU=="0")
	{
		//Proses Insert
		$ArIDBARANG = explode(".",$fmKODEBARANG);
		if(count($ArIDBARANG) != 5)
		{
			$Info = "<script>alert('Format Kode Barang Salah');</script>";
		}
		elseif(strlen($ArIDBARANG[0]) != 2 ||strlen($ArIDBARANG[1]) != 2 ||strlen($ArIDBARANG[2]) != 2 ||strlen($ArIDBARANG[3]) != 2 ||strlen($ArIDBARANG[4]) != $Main->SUBSUBKEL_DIGIT)
		{
			$Info = "<script>alert('Format Kode Barang Salah');</script>";
		}
		elseif(empty($fmNAMABARANG))
		{
			$Info = "<script>alert('Nama Barang Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $ArIDBARANG[0].$ArIDBARANG[1].$ArIDBARANG[2].$ArIDBARANG[3].$ArIDBARANG[4];
			$Cek = mysql_num_rows(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODEBARANG sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$Simpan = mysql_query("insert into ref_barang (f,g,h,i,j,nm_barang)values('{$ArIDBARANG[0]}','{$ArIDBARANG[1]}','{$ArIDBARANG[2]}','{$ArIDBARANG[3]}','{$ArIDBARANG[4]}','$fmNAMABARANG')");
				}
				if($BARU=="0")
				{
					$Simpan = mysql_query("update ref_barang set nm_barang='$fmNAMABARANG' where concat(f,g,h,i,j)='$CekKon' limit 1");
				}
				if($Simpan)
				{
					$Info = "<script>alert('Data sudah di simpan');</script>";
					$Act = "";
				}
				else
				{
					$Info = "<script>alert('Data GAGAL di simpan');</script>";
					$Act="Add";
				}
			}//end CEK
		}//end EMPTY BARANG
	}//end BARU=1 || BARU=0
}

if($Act == "Hapus")
{
	$fmID = $cidBRG[0];
	$Kondisi = "concat(f,'.',g,'.',h,'.',i,'.',j)='$fmID'";
	$Hapus = mysql_query("delete from ref_barang where $Kondisi");
	if($Hapus)
	{
		$Info = "<script>alert('Data sudah dihapus');</script>";
		$Act = "";
	}
	else
	{
		$Info = "<script>alert('Data GAGAL di hapus');</script>";
		$Act = "";
	}
}

if($Act == "Edit")
{
	if(count($cidBRG)==1)
	{
		$fmID = $cidBRG[0];
		$Kondisi = "concat(f,'.',g,'.',h,'.',i,'.',j)='$fmID'";
		$Qry = mysql_query("select * from ref_barang where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODEBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
			$fmNAMABARANG = $isi['nm_barang'];
		}
		$BARU = "0";
	}
	else
	{
		$fmKODEBARANG = "";
		$fmNAMABARANG = "";
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	$fmKODEBARANG = "";
	$fmNAMABARANG = "";
	$BARU = "1";
}

$Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i ='$fmSUBSUBKELOMPOK' and j != '$kdSubsubkel0'";
$NmHEAD = "NAMA BARANG";
if(!empty($fmBIDANG) and !empty($fmKELOMPOK) and !empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK))
{
	$Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i !='00' and j = '$kdSubsubkel0'";
	$NmHEAD = "NAMA SUB SUB KELOMPOK";
}
if(!empty($fmBIDANG) and !empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK))
{
	$NmHEAD = "NAMA SUB KELOMPOK";
	$Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h !='00' and i ='00' and j = '$kdSubsubkel0'";
}

if(!empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK))
{
	$Kondisi = "f = '$fmBIDANG' and g != '00' and h ='00' and i ='00' and j = '$kdSubsubkel0'";
	$NmHEAD = "NAMA KELOMPOK";
}

if(empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK))
{
	$Kondisi = "f != '00' and g = '00' and h ='00' and i ='00' and j = '$kdSubsubkel0'";
	$NmHEAD = "NAMA BIDANG";
}

$Kondisi='';

if(!empty($fmBIDANG)) $arrkond[] = " f='$fmBIDANG' ";
if(!empty($fmKELOMPOK)) $arrkond[] = " g='$fmKELOMPOK' ";
if(!empty($fmSUBKELOMPOK)) $arrkond[] = " h='$fmSUBKELOMPOK' ";
if(!empty($fmSUBSUBKELOMPOK)) $arrkond[] = " i='$fmSUBSUBKELOMPOK' ";

if(!empty($fmNAMABRGf)) $arrkond[] = " nm_barang like '%$fmNAMABRGf%' ";

$ArIDBRG = explode(".",$fmKODEBRGf);
if(!empty($ArIDBRG[0])) $arrkond[] = " f='$ArIDBRG[0]' ";
if(!empty($ArIDBRG[1])) $arrkond[] = " g='$ArIDBRG[1]' ";
if(!empty($ArIDBRG[2])) $arrkond[] = " h='$ArIDBRG[2]' ";
if(!empty($ArIDBRG[3])) $arrkond[] = " i='$ArIDBRG[3]' ";

$Kondisi = join(' and ',$arrkond);



if (!empty($Kondisi))
{
	$Kondisi=" j<>'' and ".$Kondisi;
} else {
	$Kondisi=" j<>'' ";
}


//AKUN
$ListBidang = cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListKelompok = cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubKelompok = cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubSubKelompok = cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$kdSubsubkel0'","onChange=\"adminForm.submit()\"",'Pilih','');

$Qry = mysql_query("select * from ref_barang where $Kondisi order by f,g,h,i,j");
$jmlDataBRG = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_barang where $Kondisi order by f,g,h,i,j $LimitHalBRG ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalBRG*1) - 1);
$cb=0;
$jmlTampilBRG = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilBRG++;
	$KODEBARANG = "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}";
	$NAMABARANG = $isi["nm_barang"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbBRG$cb\" name=\"cidBRG[]\" value=\"{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODEBARANG</div></td>
				<td><div align='left'>$NAMABARANG</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Barang </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>BIDANG</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListBidang</td>
		</tr>
		<tr>
		<td WIDTH='10%'>KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListKelompok</td>
		</tr>
		<tr>
		<td WIDTH='10%'>SUB KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubKelompok</td>
		</tr>
		<tr>
		<td WIDTH='10%'>SUB SUB KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubSubKelompok</td>
		</tr>
		<tr>
		<td WIDTH='10%'>Cari</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH=''>KODE&nbsp;<INPUT TYPE='TEXT' NAME='fmKODEBRGf' VALUE='$fmKODEBRGf'>&nbsp;&nbsp;NAMA BARANG&nbsp;
			<INPUT TYPE='TEXT' NAME='fmNAMABRGf' VALUE='$fmNAMABRGf' SIZE=50>
			<INPUT TYPE='BUTTON' NAME='fmTampilkan' value='Tampilkan' onclick='adminForm.submit()'>
			</td>
		</tr>		
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilBRG,'cbBRG','toggle2');\" /></TD>
				<th width='10%' class=\"title\"><div align=left>Kode Barang</div></th>
				<th width='86%' class=\"title\"><div align=left>$NmHEAD</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=4 align=center>
	".Halaman($jmlDataBRG,$Main->PagePerHal,"HalBRG")."
	</td>
	</tr>

		</table>
	</td>
</tr>
</table>
";

if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{	
	$Main->Isi .= "
	<br>
	<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
			<td colspan=3><b>BIDANG/KELOMPOK/SUB KELOMPOK/SUB SUB KELOMPOK/BARANG</td>
		</tr>

		<tr>
			<td WIDTH='200'>KODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODEBARANG' VALUE='$fmKODEBARANG'></td>
		</tr>
		<tr>
			<td WIDTH='200'>NAMA</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMABARANG' VALUE='$fmNAMABARANG' SIZE=100></td>
		</tr>
	</table>
	";
	$TombolSimpan = "<td>".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."</td>";
}//END IF



$Main->Isi .= "

	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Edit';adminForm.submit()","edit_f2.png","Ubah")."</td>
			<td>".PanelIcon1("javascript:if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){adminForm.Act.value='Hapus';adminForm.submit();}","delete_f2.png","Hapus")."</td>
			$TombolSimpan
			<td>".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."</td>
			<td>".PanelIcon1("javascript:adminForm.target='blank_';adminForm.action='?Pg=PR&SPg=ref_barang_cetak';adminForm.submit();adminForm.target='';adminForm.action=''","print_f2.png","Cetak")."</td>
			<td>".PanelIcon1("javascript:adminForm.target='';adminForm.action='?Pg=PR&SPg=ref_barang_cetak&SDest=XLS';adminForm.submit();adminForm.action=''","export_xls.png","Excel")."</td>

			</tr></table>
		</td></tr>
	</table>
		<input type=\"hidden\" name=\"Act\" value=\"$Act\" />
		<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
		<input type=\"hidden\" name=\"BARU\" value=\"$BARU\" />
		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />

</td></tr>
</form>



";
?>