<?php
$HalPEJABAT = cekPOST("HalPEJABAT",1);
$LimitHalPEJABAT = " limit ".(($HalPEJABAT*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmSKPD = cekPOST("fmSKPD","");
$fmUNIT = cekPOST("fmUNIT","");
$fmSUBUNIT = cekPOST("fmSUBUNIT","");
$fmPEJABAT = cekPOST("fmPEJABAT","");

$fmKODEPEJABAT = cekPOST("fmKODEPEJABAT","");
$fmNAMAPEJABAT = cekPOST("fmNAMAPEJABAT","");
$fmNIKPEJABAT = cekPOST("fmNIKPEJABAT","");
$fmJABATANPEJABAT = cekPOST("fmJABATANPEJABAT","");
$fmTTD1 = cekPOST("fmTTD1","");
$fmTTD2 = cekPOST("fmTTD2","");

$isiTTD1 = $fmTTD1=='on' ? "1":"0";
$isiTTD2 = $fmTTD2=='on' ? "1":"0";

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
		$ArIDPEJABAT = explode(".",$fmKODEPEJABAT);
		if(count($ArIDPEJABAT) != 4)
		{
			$Info = "<script>alert('Format Kode Pejabat Salah');</script>";
		}
		elseif(strlen($ArIDPEJABAT[0]) != 2 ||strlen($ArIDPEJABAT[1]) != 2 ||strlen($ArIDPEJABAT[2]) != 2 || strlen($ArIDPEJABAT[3]) != 2 )
		{
			$Info = "<script>alert('Format Kode Pejabat Salah');</script>";
		}
		elseif(empty($fmNAMAPEJABAT))
		{
			$Info = "<script>alert('Nama Pejabat Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $ArIDPEJABAT[0].$ArIDPEJABAT[1].$ArIDPEJABAT[2].$ArIDPEJABAT[3];
			$Cek = mysql_num_rows(mysql_query("select * from ref_pejabat where concat(c,d,e,id_pejabat)='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODEPEJABAT sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$Simpan = mysql_query("insert into ref_pejabat (c,d,e,id_pejabat,nm_pejabat,nik,jabatan,ttd1,ttd2)values('{$ArIDPEJABAT[0]}','{$ArIDPEJABAT[1]}','{$ArIDPEJABAT[2]}','{$ArIDPEJABAT[3]}','$fmNAMAPEJABAT','$fmNIKPEJABAT','$fmJABATANPEJABAT','$isiTTD1','$isiTTD2')");
				}
				if($BARU=="0")
				{
					$Simpan = mysql_query("update ref_pejabat set nm_pejabat='$fmNAMAPEJABAT',nik='$fmNIKPEJABAT',jabatan='$fmJABATANPEJABAT',ttd1='$isiTTD1',ttd2='$isiTTD2' where concat(c,d,e,id_pejabat)='$CekKon' limit 1");
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
	$Kondisi = "concat(c,'.',d,'.',e,'.',id_pejabat)='$fmID'";
	$Hapus = mysql_query("delete from ref_pejabat where $Kondisi");
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
		$Kondisi = "concat(c,'.',d,'.',e,'.',id_pejabat)='$fmID'";
		$Qry = mysql_query("select * from ref_pejabat where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODEPEJABAT = $isi['c'].".".$isi['d'].".".$isi['e'].".".$isi['id_pejabat'];
			$fmNAMAPEJABAT = $isi['nm_pejabat'];
			$fmNIKPEJABAT = $isi['nik'];
			$fmJABATANPEJABAT = $isi['jabatan'];
			$isiTTD1 = $isi['ttd1'];
			$isiTTD2 = $isi['ttd2'];
		}
		$BARU = "0";
	}
	else
	{
		$fmKODEPEJABAT = "";
		$fmNAMAPEJABAT = "";
		$isiTTD1 = "0";
		$isiTTD2 = "0";
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	$fmKODEPEJABAT = $fmSKPD.".".$fmUNIT.".".$fmSUBUNIT;
	//$fmKODEPEJABAT = "";
	$fmNAMAPEJABAT = "";
	$fmNIKPEJABAT = "";
	$fmJABATANPEJABAT = "";
	$isiTTD1 = "0";
	$isiTTD2 = "0";

	$BARU = "1";
}

$Kondisi = "c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and id_pejabat !='00'";
$NmHEAD = "NAMA SKPD";
if(!empty($fmSKPD) and !empty($fmUNIT) and !empty($fmSUBUNIT))
{
	$Kondisi = "c = '$fmSKPD' and d ='$fmUNIT' and e = '$fmSUBUNIT' and id_pejabat !='00'";
	$NmHEAD = "NAMA PEJABAT";
}
if(!empty($fmSKPD) and !empty($fmUNIT) and empty($fmSUBUNIT))
{
	$NmHEAD = "NAMA SUB UNIT";
	$Kondisi = "c = '$fmSKPD' and d ='$fmUNIT' and e != '00' and id_pejabat ='00'";
}
if(!empty($fmSKPD) and empty($fmUNIT)  and empty($fmSUBUNIT) )
{
	$NmHEAD = "NAMA UNIT";
	$Kondisi = "c = '$fmSKPD' and d !='00' and e = '00' and id_pejabat ='00'";
}
if(empty($fmSKPD) and empty($fmUNIT)  and empty($fmSUBUNIT) )
{
	$NmHEAD = "NAMA SKPD";
	$Kondisi = "c != '00' and d = '00' and e = '00' and id_pejabat ='00'";
}


//$Kondisi = "id_pejabat != ''";

//SKPD
$ListSKPD = cmbQuery1("fmSKPD",$fmSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' ","onChange=\"adminForm.submit()\" ",'Pilih','00');
$ListUNIT = cmbQuery1("fmUNIT",$fmUNIT,"select d,nm_skpd from ref_skpd where c='$fmSKPD' and d !='00' and e = '00'  ","onChange=\"adminForm.submit()\" ",'Pilih','00');
$ListSUBUNIT = cmbQuery1("fmSUBUNIT",$fmSUBUNIT,"select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' ","onChange=\"adminForm.submit()\" ",'Pilih','00');


$NmHEAD = "NAMA PEJABAT";
$Qry = mysql_query("select * from ref_pejabat where $Kondisi order by c,d,e,id_pejabat");
$jmlDataSKPD = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_pejabat where $Kondisi order by c,d,e,id_pejabat $LimitHalPEJABAT ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalPEJABAT*1) - 1);
$cb=0;
$jmlTampilSKPD = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilSKPD++;
	$KODEPEJABAT = "{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['id_pejabat']}";
	$NAMAPEJABAT = $isi["nm_pejabat"];
	$NIKPEJABAT = $isi["nik"];
	$JABATANPEJABAT = $isi["jabatan"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbSKPD$cb\" name=\"cidSKPD[]\" value=\"{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['id_pejabat']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODEPEJABAT</div></td>
				<td><div align='left'>$NIKPEJABAT</div></td>
				<td><div align='left'>$NAMAPEJABAT</div></td>
				<td><div align='left'>$JABATANPEJABAT</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Pejabat </th>
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
				<th width='5%' class=\"title\"><div align=left>No.</div></th>
				<TH width='5%'><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilSKPD,'cbSKPD','toggle2');\" /></TD>
				<th width='5%' class=\"title\"><div align=left>ID Pejabat</div></th>
				<th width='2%' class=\"title\"><div align=left>NIP/NIK</div></th>
				<th width='20%' class=\"title\"><div align=left>$NmHEAD</div></th>
				<th width='60%' class=\"title\"><div align=left>Jabatan</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=6 align=center>
	".Halaman($jmlDataSKPD,$Main->PagePerHal,"HalPEJABAT")."
	</td>
	</tr>

		</table>
	</td>
</tr>
</table>
";

if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$cekTTD1 = $isiTTD1=='1' ? " checked ":"";
	$cekTTD2 = $isiTTD2=='1' ? " checked ":"";

	$Main->Isi .= "
	<br>
	<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
			<td colspan=3><b>PEJABAT</td>
		</tr>

		<tr>
			<td WIDTH='200'>ID</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODEPEJABAT' VALUE='$fmKODEPEJABAT'></td>
		</tr>
		<tr>
			<td WIDTH='200'>NIP/NIK</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNIKPEJABAT' VALUE='$fmNIKPEJABAT' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>NAMA</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMAPEJABAT' VALUE='$fmNAMAPEJABAT' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>JABATAN</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmJABATANPEJABAT' VALUE='$fmJABATANPEJABAT' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>Kepala Penanda Tangan </td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='checkbox' NAME='fmTTD1' $cekTTD1></td>
		</tr>
		<tr>
			<td WIDTH='200'>Urusan Barang Penanda Tangan</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='checkbox' NAME='fmTTD2' $cekTTD2></td>
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