<?php
$HalREK = cekPOST("HalREK",1);
$LimitHalREK = " limit ".(($HalREK*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmAKUN = cekPOST("fmAKUN","");
$fmBAGIAN = cekPOST("fmBAGIAN","");
$fmKELOMPOK = cekPOST("fmKELOMPOK","");
$fmOBJEK = cekPOST("fmOBJEK","");


$fmKODEREKENING = cekPOST("fmKODEREKENING","");
$fmNAMAREKENING = cekPOST("fmNAMAREKENING","");

$Act = cekPOST("Act","");
$cidREK = cekPOST("cidREK","");
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
		$ArIDREKENING = explode(".",$fmKODEREKENING);
		if(count($ArIDREKENING) != 5)
		{
			$Info = "<script>alert('Format Kode Rekening Salah');</script>";
		}
		elseif(strlen($ArIDREKENING[0]) != 1 ||strlen($ArIDREKENING[1]) != 1 ||strlen($ArIDREKENING[2]) != 1 ||strlen($ArIDREKENING[3]) != 2 ||strlen($ArIDREKENING[4]) != 2)
		{
			$Info = "<script>alert('Format Kode Rekening Salah');</script>";
		}
		elseif(empty($fmNAMAREKENING))
		{
			$Info = "<script>alert('Nama Rekening Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $ArIDREKENING[0].$ArIDREKENING[1].$ArIDREKENING[2].$ArIDREKENING[3].$ArIDREKENING[4];
			$Cek = mysql_num_rows(mysql_query("select * from ref_rekening where concat(f,g,h,i,j)='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODEREKENING sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$Simpan = mysql_query("insert into ref_rekening (k,l,m,n,o,nm_rekening)values('{$ArIDREKENING[0]}','{$ArIDREKENING[1]}','{$ArIDREKENING[2]}','{$ArIDREKENING[3]}','{$ArIDREKENING[4]}','$fmNAMAREKENING')");
				}
				if($BARU=="0")
				{
					$Simpan = mysql_query("update ref_rekening set nm_rekening='$fmNAMAREKENING' where concat(k,l,m,n,o)='$CekKon' limit 1");
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
		}//end EMPTY REKENING
	}//end BARU=1 || BARU=0
}

if($Act == "Hapus")
{
	$fmID = $cidREK[0];
	$Kondisi = "concat(k,'.',l,'.',m,'.',n,'.',o)='$fmID'";
	$Hapus = mysql_query("delete from ref_rekening where $Kondisi");
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
	if(count($cidREK)==1)
	{
		$fmID = $cidREK[0];
		$Kondisi = "concat(k,'.',l,'.',m,'.',n,'.',o)='$fmID'";
		$Qry = mysql_query("select * from ref_rekening where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODEREKENING = $isi['k'].".".$isi['l'].".".$isi['m'].".".$isi['n'].".".$isi['o'];
			$fmNAMAREKENING = $isi['nm_rekening'];
		}
		$BARU = "0";
	}
	else
	{
		$fmKODEREKENING = "";
		$fmNAMAREKENING = "";
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	$fmKODEREKENING = "";
	$fmNAMAREKENING = "";
	$BARU = "1";
}

$Kondisi = "k = '$fmAKUN' and l = '$fmBAGIAN' and m ='$fmKELOMPOK' and n ='$fmOBJEK' and o != '00'";
$NmHEAD = "NAMA REKENING";
if(!empty($fmAKUN) and !empty($fmBAGIAN) and !empty($fmKELOMPOK) and empty($fmOBJEK))
{
	$Kondisi = "k = '$fmAKUN' and l = '$fmBAGIAN' and m ='$fmKELOMPOK' and n !='00' and o = '00'";
	$NmHEAD = "NAMA OBJEK";
}
if(!empty($fmAKUN) and !empty($fmBAGIAN) and empty($fmKELOMPOK) and empty($fmOBJEK))
{
	$NmHEAD = "NAMA KELOMPOK";
	$Kondisi = "k = '$fmAKUN' and l = '$fmBAGIAN' and m !='0' and n ='00' and o = '00'";
}

if(!empty($fmAKUN) and empty($fmBAGIAN) and empty($fmKELOMPOK) and empty($fmOBJEK))
{
	$Kondisi = "k = '$fmAKUN' and l != '0' and m ='0' and n ='00' and o = '00'";
	$NmHEAD = "NAMA BAGIAN";
}

if(empty($fmAKUN) and empty($fmBAGIAN) and empty($fmKELOMPOK) and empty($fmOBJEK))
{
	$Kondisi = "k != '0' and l = '0' and m ='0' and n ='00' and o = '00'";
	$NmHEAD = "NAMA AKUN";
}

//AKUN
$ListBidang = cmbQuery1("fmAKUN",$fmAKUN,"select k,nm_rekening from ref_rekening where k!='0' and l ='0' and m = '0' and n='00' and o='00'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListKelompok = cmbQuery1("fmBAGIAN",$fmBAGIAN,"select l,nm_rekening from ref_rekening where k='$fmAKUN' and l !='0' and m = '0' and n='00' and o='00'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubKelompok = cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select m,nm_rekening from ref_rekening where k='$fmAKUN' and l ='$fmBAGIAN' and m != '0' and n='00' and o='00'","onChange=\"adminForm.submit()\"",'Pilih','');
$ListSubSubKelompok = cmbQuery1("fmOBJEK",$fmOBJEK,"select n,nm_rekening from ref_rekening where k='$fmAKUN' and l ='$fmBAGIAN' and m = '$fmKELOMPOK' and n!='00' and o='00'","onChange=\"adminForm.submit()\"",'Pilih','');

$Qry = mysql_query("select * from ref_rekening where $Kondisi order by k,l,m,n,o");
$jmlDataREK = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_rekening where $Kondisi order by k,l,m,n,o $LimitHalREK ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalREK*1) - 1);
$cb=0;
$jmlTampilREK = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilREK++;
	$KODEREKENING = "{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}";
	$NAMAREKENING = $isi["nm_rekening"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbREK$cb\" name=\"cidREK[]\" value=\"{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODEREKENING</div></td>
				<td><div align='left'>$NAMAREKENING</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Rekening </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>AKUN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListBidang</td>
		</tr>
		<tr>
		<td WIDTH='10%'>BAGIAN</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListKelompok</td>
		</tr>
		<td WIDTH='10%'>KELOMPOK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubKelompok</td>
		</tr>
		<td WIDTH='10%'>OBJEK</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSubSubKelompok</td>
		</tr>
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilREK,'cbREK','toggle2');\" /></TD>
				<th width='10%' class=\"title\"><div align=left>Kode Rekening</div></th>
				<th width='86%' class=\"title\"><div align=left>$NmHEAD</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=4 align=center>
	".Halaman($jmlDataREK,$Main->PagePerHal,"HalREK")."
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
			<td colspan=3><b>AKUN/BAGIAN/KELOMPOK/SUB KELOMPOK/REKENING</td>
		</tr>

		<tr>
			<td WIDTH='200'>KODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODEREKENING' VALUE='$fmKODEREKENING'></td>
		</tr>
		<tr>
			<td WIDTH='200'>NAMA</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMAREKENING' VALUE='$fmNAMAREKENING' SIZE=100></td>
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