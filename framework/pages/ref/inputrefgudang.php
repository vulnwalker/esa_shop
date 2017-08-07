<?php
$HalGUDANG = cekPOST("HalGUDANG",1);
$LimitHalGUDANG = " limit ".(($HalGUDANG*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmSKPD = cekPOST("fmSKPD","");
$fmUNIT = cekPOST("fmUNIT","");
$fmSUBUNIT = cekPOST("fmSUBUNIT","");
$fmGUDANG = cekPOST("fmGUDANG","");

$fmKODEGUDANG = cekPOST("fmKODEGUDANG","");
$fmNAMAGUDANG = cekPOST("fmNAMAGUDANG","");

$Act = cekPOST("Act","");
$cidSKPD = cekPOST("cidSKPD","");
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
		$ArIDGUDANG = explode(".",$fmKODEGUDANG);
		if(count($ArIDGUDANG) != 4)
		{
			$Info = "<script>alert('Format Kode Gudang Salah');</script>";
		}
		elseif(strlen($ArIDGUDANG[0]) != 2 ||strlen($ArIDGUDANG[1]) != 2 ||strlen($ArIDGUDANG[2]) != 2 || strlen($ArIDGUDANG[3]) != 4 )
		{
			$Info = "<script>alert('Format Kode Gudang Salah');</script>";
		}
		elseif(empty($fmNAMAGUDANG))
		{
			$Info = "<script>alert('Nama Gudang Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $ArIDGUDANG[0].$ArIDGUDANG[1].$ArIDGUDANG[2].$ArIDGUDANG[3];
			$Cek = mysql_num_rows(mysql_query("select * from ref_gudang where concat(c,d,e,id_gudang)='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODEGUDANG sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$Simpan = mysql_query("insert into ref_gudang (c,d,e,id_gudang,nm_gudang)values('{$ArIDGUDANG[0]}','{$ArIDGUDANG[1]}','{$ArIDGUDANG[2]}','{$ArIDGUDANG[3]}','$fmNAMAGUDANG')");
				}
				if($BARU=="0")
				{
					$Simpan = mysql_query("update ref_gudang set nm_gudang='$fmNAMAGUDANG' where concat(c,d,e,id_gudang)='$CekKon' limit 1");
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
		}//end EMPTY SKPD
	}//end BARU=1 || BARU=0
}

if($Act == "Hapus")
{
	$fmID = $cidSKPD[0];
	$Kondisi = "concat(c,'.',d,'.',e,'.',id_gudang)='$fmID'";
	$Hapus = mysql_query("delete from ref_gudang where $Kondisi");
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
	if(count($cidSKPD)==1)
	{
		$fmID = $cidSKPD[0];
		$Kondisi = "concat(c,'.',d,'.',e,'.',id_gudang)='$fmID'";
		$Qry = mysql_query("select * from ref_gudang where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODEGUDANG = $isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['id_gudang'];
			$fmNAMAGUDANG = $isi['nm_gudang'];
		}
		$BARU = "0";
	}
	else
	{
		//$fmKODEGUDANG = "";
		$fmNAMAGUDANG = "";
		$fmKODEGUDANG = $fmSKPD.".".$fmUNIT.".".$fmSUBUNIT;
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	//$fmKODEGUDANG = "";
	$fmNAMAGUDANG = "";
	$fmKODEGUDANG = $fmSKPD.".".$fmUNIT.".".$fmSUBUNIT;
	$BARU = "1";
}

$Kondisi = "c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and id_gudang !='0000'";
$NmHEAD = "NAMA SKPD";
if(!empty($fmSKPD) and !empty($fmUNIT) and !empty($fmSUBUNIT))
{
	$Kondisi = "c = '$fmSKPD' and d ='$fmUNIT' and e = '$fmSUBUNIT' and id_gudang !='0000'";
	$NmHEAD = "NAMA GUDANG";
}
if(!empty($fmSKPD) and !empty($fmUNIT) and empty($fmSUBUNIT))
{
	$NmHEAD = "NAMA SUB UNIT";
	$Kondisi = "c = '$fmSKPD' and d ='$fmUNIT' and e != '00' and id_gudang ='0000'";
}
if(!empty($fmSKPD) and empty($fmUNIT)  and empty($fmSUBUNIT) )
{
	$NmHEAD = "NAMA UNIT";
	$Kondisi = "c = '$fmSKPD' and d !='00' and e = '00' and id_gudang ='0000'";
}
if(empty($fmSKPD) and empty($fmUNIT)  and empty($fmSUBUNIT) )
{
	$NmHEAD = "NAMA SKPD";
	$Kondisi = "c != '00' and d = '00' and e = '00' and id_gudang ='0000'";
}

//SKPD
/*
$ListSKPD = cmbQuery1("fmSKPD",$fmSKPD,"select c,nm_gudang from ref_gudang where c!='00' and d ='00' and e = '00' and id_gudang = '0000' ","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListUNIT = cmbQuery1("fmUNIT",$fmUNIT,"select d,nm_gudang from ref_gudang where c='$fmSKPD' and d !='00' and e = '00'  and id_gudang = '0000' ","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListSUBUNIT = cmbQuery1("fmSUBUNIT",$fmSUBUNIT,"select d,nm_gudang from ref_gudang where c='$fmSKPD' and d ='$fmUNIT' and e != '00'  and id_gudang = '0000'","onChange=\"adminForm.submit()\" ",'Pilih','');
*/
//SKPD
$ListSKPD = cmbQuery1("fmSKPD",$fmSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' ","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListUNIT = cmbQuery1("fmUNIT",$fmUNIT,"select d,nm_skpd from ref_skpd where c='$fmSKPD' and d !='00' and e = '00'  ","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListSUBUNIT = cmbQuery1("fmSUBUNIT",$fmSUBUNIT,"select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' ","onChange=\"adminForm.submit()\" ",'Pilih','');



$Qry = mysql_query("select * from ref_gudang where $Kondisi order by c,d,e,id_gudang");
$jmlDataSKPD = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_gudang where $Kondisi order by c,d,e,id_gudang $LimitHalGUDANG ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalGUDANG*1) - 1);
$cb=0;
$jmlTampilSKPD = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilSKPD++;
	$KODEGUDANG = "{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['id_gudang']}";
	$NAMAGUDANG = $isi["nm_gudang"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbSKPD$cb\" name=\"cidSKPD[]\" value=\"{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['id_gudang']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODEGUDANG</div></td>
				<td><div align='left'>$NAMAGUDANG</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Gudang </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>SKPD</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSKPD</td>
		</tr>
		<tr>
		<td WIDTH='10%'>UNIT</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListUNIT</td>
		</tr>
		<td WIDTH='10%'>SUB UNIT</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSUBUNIT</td>
		</tr>
		</table>
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilSKPD,'cbSKPD','toggle2');\" /></TD>
				<th width='10%' class=\"title\"><div align=left>Kode SKPD</div></th>
				<th width='86%' class=\"title\"><div align=left>$NmHEAD</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=4 align=center>
	".Halaman($jmlDataSKPD,$Main->PagePerHal,"HalGUDANG")."
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
			<td colspan=3><b>SKPD/UNIT/SUB UNIT/GUDANG</td>
		</tr>

		<tr>
			<td WIDTH='200'>KODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODEGUDANG' VALUE='$fmKODEGUDANG'></td>
		</tr>
		<tr>
			<td WIDTH='200'>NAMA</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMAGUDANG' VALUE='$fmNAMAGUDANG' SIZE=100></td>
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