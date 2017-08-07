<?php
$HalRUANG = cekPOST("HalRUANG",1);
$LimitHalRUANG = " limit ".(($HalRUANG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmGEDUNG = cekPOST("fmGEDUNG","");
$fmRUANG = cekPOST("fmRUANG","");

$fmKODERUANG = cekPOST("fmKODERUANG","");
$fmNAMARUANG = cekPOST("fmNAMARUANG","");

$Act = cekPOST("Act","");
$cidRUANG = cekPOST("cidRUANG","");
$BARU = cekPOST("BARU","1");
$Info = "";
$disSIMPAN = " disabled ";
$TombolSimpan = "";
if($Act == "Simpan")
{
	$Simpan = false;
	if($BARU == "1" || $BARU=="0")
	{
		//Proses Insert
		$ArIDRUANG = explode(".",$fmKODERUANG);
		if(count($ArIDRUANG) != 2)
		{
			$Info = "<script>alert('Format Kode RUANG Salah');</script>";
		}
		elseif(strlen($ArIDRUANG[0]) != 3 ||strlen($ArIDRUANG[1]) != 3 )
		{
			$Info = "<script>alert('Format Kode RUANG Salah');</script>";
		}
		elseif(empty($fmNAMARUANG))
		{
			$Info = "<script>alert('Nama RUANG Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $ArIDRUANG[0].$ArIDRUANG[1];
			$Cek = mysql_num_rows(mysql_query("select * from ref_ruang where concat(p,q)='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODERUANG sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$Simpan = mysql_query("insert into ref_ruang (p,q,nm_ruang)values('{$ArIDRUANG[0]}','{$ArIDRUANG[1]}','$fmNAMARUANG')");
				}
				if($BARU=="0")
				{
					$Simpan = mysql_query("update ref_ruang set nm_ruang='$fmNAMARUANG' where concat(p,q)='$CekKon' limit 1");
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
		}//end EMPTY RUANG
	}//end BARU=1 || BARU=0
}

if($Act == "Hapus")
{
	$fmID = $cidRUANG[0];
	$Kondisi = "concat(p,'.',q)='$fmID'";
	$Hapus = mysql_query("delete from ref_ruang where $Kondisi");
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
	if(count($cidRUANG)==1)
	{
		$fmID = $cidRUANG[0];
		$Kondisi = "concat(p,'.',q)='$fmID'";
		$Qry = mysql_query("select * from ref_ruang where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODERUANG = $isi['p'].".".$isi['q'];
			$fmNAMARUANG = $isi['nm_ruang'];
		}
		$BARU = "0";
	}
	else
	{
		$fmKODERUANG = "";
		$fmNAMARUANG = "";
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	$fmKODERUANG = $fmGEDUNG.".";
	$fmNAMARUANG = "";
	$BARU = "1";
}

$Kondisi = "p = '$fmGEDUNG' and q != '000'";
$NmHEAD = "NAMA RUANG";
if(!empty($fmGEDUNG))
{
	$Kondisi = "p = '$fmGEDUNG' and q != '000'";
	$NmHEAD = "NAMA RUANG";
}
if(empty($fmGEDUNG))
{
	$NmHEAD = "NAMA GEDUNG";
	$Kondisi = "p != '000' and q = '000'";
}

//RUANG
$ListGEDUNG = cmbQuery1("fmGEDUNG",$fmGEDUNG,"select p,nm_ruang from ref_ruang where p!='000' and q ='000' order by p,q","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListRUANG = cmbQuery1("fmRUANG",$fmRUANG,"select q,nm_ruang from ref_ruang where p='$fmGEDUNG' and p !='000' and q != '000'  order by p,q ","onChange=\"adminForm.submit()\" ",'Pilih','');

$Qry = mysql_query("select * from ref_ruang where $Kondisi order by p,q");
$jmlDataRUANG = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_ruang where $Kondisi order by p,q $LimitHalRUANG ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalRUANG*1) - 1);
$cb=0;
$jmlTampilRUANG = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilRUANG++;
	$KODERUANG = "{$isi['p']}.{$isi['q']}";
	$NAMARUANG = $isi["nm_ruang"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbRUANG$cb\" name=\"cidRUANG[]\" value=\"{$isi['p']}.{$isi['q']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODERUANG</div></td>
				<td><div align='left'>$NAMARUANG</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar RUANG </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>GEDUNG</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListGEDUNG</td>
		</tr>
		<!--
		<tr>
		<td WIDTH='10%'>UNIT</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListRUANG</td>
		</tr>
		-->
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilRUANG,'cbRUANG','toggle2');\" /></TD>
				<th width='10%' class=\"title\"><div align=left>Kode RUANG</div></th>
				<th width='86%' class=\"title\"><div align=left>$NmHEAD</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=4 align=center>
	".Halaman($jmlDataRUANG,$Main->PagePerHal,"HalRUANG")."
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
			<td colspan=3><b>URAIAN</td>
		</tr>

		<tr>
			<td WIDTH='200'>KODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODERUANG' VALUE='$fmKODERUANG'></td>
		</tr>
		<tr>
			<td WIDTH='200'>URAIAN</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMARUANG' VALUE='$fmNAMARUANG' SIZE=100></td>
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
			<td>".PanelIcon1("javascript:adminForm.Act.value='Cetak';adminForm.submit()","print_f2.png","Cetak")."</td>

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