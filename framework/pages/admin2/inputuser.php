<?php
$HalPENGGUNA = cekPOST("HalPENGGUNA",1);
$LimitHalPENGGUNA = " limit ".(($HalPENGGUNA*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmSKPD = cekPOST("fmSKPD","");
$fmUNIT = cekPOST("fmUNIT","");
$fmSUBUNIT = cekPOST("fmSUBUNIT","");
$fmPENGGUNA = cekPOST("fmPENGGUNA","");

$fmKODEPENGGUNA = cekPOST("fmKODEPENGGUNA","");
$fmNAMAPENGGUNA = cekPOST("fmNAMAPENGGUNA","");
$fmSANDIPENGGUNA = cekPOST("fmSANDIPENGGUNA","");
$SANDI2 = cekPOST("SANDI2","");
$fmLEVELPENGGUNA = cekPOST("fmLEVELPENGGUNA","");
$fmGROUPPENGGUNA = cekPOST("fmGROUPPENGGUNA","");
$fmMODUL01 = cekPOST("fmMODUL01","");
$fmMODUL02 = cekPOST("fmMODUL02","");
$fmMODUL03 = cekPOST("fmMODUL03","");
$fmMODUL04 = cekPOST("fmMODUL04","");
$fmMODUL05 = cekPOST("fmMODUL05","");
$fmMODUL06 = cekPOST("fmMODUL06","");
$fmMODUL07 = cekPOST("fmMODUL07","");
$fmMODUL08 = cekPOST("fmMODUL08","");
$fmMODUL09 = cekPOST("fmMODUL09","");
$fmMODUL10 = cekPOST("fmMODUL10","");
$fmMODUL11 = cekPOST("fmMODUL11","");
$fmMODUL12 = cekPOST("fmMODUL12","");
$fmMODUL13 = cekPOST("fmMODUL13","");
$fmMODULref = cekPOST("fmMODULref","");
$fmMODULadm = cekPOST("fmMODULadm","");
$fmStatus = cekPOST("fmStatus","1");


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
		if(empty($fmNAMAPENGGUNA) && empty($fmKODEPENGGUNA) && empty($fmSANDIPENGGUNA))
		{
			$Info = "<script>alert('ID/Nama/Sandi Pengguna Tidak Boleh Kosong');</script>";
		}
		else
		{
			$CekKon = $fmKODEPENGGUNA;
			$Cek = mysql_num_rows(mysql_query("select * from admin where uid='$CekKon' "));
			if($Cek && $BARU=="1")
			{
				$Info = "<script>alert('Kode $fmKODEPENGGUNA sudah ada, data tidak disimpan');</script>";
			}
			else
			{
				//SIMPAN DATA
				if($BARU=="1")
				{
					$PWD = md5($fmSANDIPENGGUNA);
					$Simpan = mysql_query("insert into admin (
				uid,password,nama,level,`group`,modul01,modul02,modul03,modul04,modul05,modul06,modul07,modul08,modul09,modul10,modul11,modul12,modul13,modulref,moduladm,status)values(
				'$fmKODEPENGGUNA','$PWD','$fmNAMAPENGGUNA','$fmLEVELPENGGUNA','$fmGROUPPENGGUNA',
				'$fmMODUL01','$fmMODUL02','$fmMODUL03','$fmMODUL04','$fmMODUL05','$fmMODUL06',
				'$fmMODUL07','$fmMODUL08','$fmMODUL09','$fmMODUL10','$fmMODUL11','$fmMODUL12','$fmMODUL13','$fmMODULref','$fmMODULadm','$fmStatus'
					)");
				}
				if($BARU=="0")
				{
					$PWD = $fmSANDIPENGGUNA == $SANDI2 ? $fmSANDIPENGGUNA:md5($fmSANDIPENGGUNA);
					$Simpan = mysql_query("update admin set 
					uid='$fmKODEPENGGUNA',
					password='$PWD',
						nama='$fmNAMAPENGGUNA',
						level='$fmLEVELPENGGUNA',
						`group`='$fmGROUPPENGGUNA',
						modul01='$fmMODUL01',
						modul02='$fmMODUL02',
						modul03='$fmMODUL03',
						modul04='$fmMODUL04',
						modul05='$fmMODUL05',
						modul06='$fmMODUL06',
						modul07='$fmMODUL07',
						modul08='$fmMODUL08',
						modul09='$fmMODUL09',
						modul10='$fmMODUL10',
						modul11='$fmMODUL11',
						modul12='$fmMODUL12',
						modul13='$fmMODUL13',
						modulref='$fmMODULref',
						moduladm='$fmMODULadm',
						status='$fmStatus'
						where uid='$CekKon' limit 1");
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
	$fmID = $cidSKPD;
	for ($i=0;$i<count($fmID);$i++)
	{
		$Kondisi = "uid='{$fmID[$i]}'";
		$Hapus = mysql_query("delete from admin where $Kondisi limit 1");
	}
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
		$Kondisi = "uid = '$fmID'";
		$Qry = mysql_query("select * from admin where $Kondisi limit 1");
		while($isi=mysql_fetch_array($Qry))
		{
			$fmKODEPENGGUNA = $isi['uid'];
			$fmNAMAPENGGUNA = $isi['nama'];
			$fmSANDIPENGGUNA = $isi['password'];
			$SANDI2 = $isi['password'];
			$fmLEVELPENGGUNA = $isi['level'];
			$fmGROUPPENGGUNA = $isi['group'];
			$fmMODUL01 = $isi['modul01'];
			$fmMODUL02 = $isi['modul02'];
			$fmMODUL03 = $isi['modul03'];
			$fmMODUL04 = $isi['modul04'];
			$fmMODUL05 = $isi['modul05'];
			$fmMODUL06 = $isi['modul06'];
			$fmMODUL07 = $isi['modul07'];
			$fmMODUL08 = $isi['modul08'];
			$fmMODUL09 = $isi['modul09'];
			$fmMODUL10 = $isi['modul10'];
			$fmMODUL11 = $isi['modul11'];
			$fmMODUL12 = $isi['modul12'];
			$fmMODUL13 = $isi['modul13'];
			$fmMODULref = $isi['modulref'];
			$fmMODULadm = $isi['moduladm'];
			$fmStatus = $isi['status'];
			$chkLevel1 = $fmLEVELPENGGUNA == "1" ? "checked":"";$chkLevel2 = $fmLEVELPENGGUNA == "2" ? "checked":"";
			$chkStatus_0 = $fmStatus == "0" ? "checked":"";$chkStatus_1 = $fmStatus == "1" ? "checked":"";
			$chkModul01_0 = $fmMODUL01 == "0" ? "checked":"";$chkModul01_1 = $fmMODUL01 == "1" ? "checked":"";$chkModul01_2 = $fmMODUL01 == "2" ? "checked":"";
			$chkModul02_0 = $fmMODUL02 == "0" ? "checked":"";$chkModul02_1 = $fmMODUL02 == "1" ? "checked":"";$chkModul02_2 = $fmMODUL02 == "2" ? "checked":"";
			$chkModul03_0 = $fmMODUL03 == "0" ? "checked":"";$chkModul03_1 = $fmMODUL03 == "1" ? "checked":"";$chkModul03_2 = $fmMODUL03 == "2" ? "checked":"";
			$chkModul04_0 = $fmMODUL04 == "0" ? "checked":"";$chkModul04_1 = $fmMODUL04 == "1" ? "checked":"";$chkModul04_2 = $fmMODUL04 == "2" ? "checked":"";
			$chkModul05_0 = $fmMODUL05 == "0" ? "checked":"";$chkModul05_1 = $fmMODUL05 == "1" ? "checked":"";$chkModul05_2 = $fmMODUL05 == "2" ? "checked":"";
			$chkModul06_0 = $fmMODUL06 == "0" ? "checked":"";$chkModul06_1 = $fmMODUL06 == "1" ? "checked":"";$chkModul06_2 = $fmMODUL06 == "2" ? "checked":"";
			$chkModul07_0 = $fmMODUL07 == "0" ? "checked":"";$chkModul07_1 = $fmMODUL07 == "1" ? "checked":"";$chkModul07_2 = $fmMODUL07 == "2" ? "checked":"";
			$chkModul08_0 = $fmMODUL08 == "0" ? "checked":"";$chkModul08_1 = $fmMODUL08 == "1" ? "checked":"";$chkModul08_2 = $fmMODUL08 == "2" ? "checked":"";
			$chkModul09_0 = $fmMODUL09 == "0" ? "checked":"";$chkModul09_1 = $fmMODUL09 == "1" ? "checked":"";$chkModul09_2 = $fmMODUL09 == "2" ? "checked":"";
			$chkModul10_0 = $fmMODUL10 == "0" ? "checked":"";$chkModul10_1 = $fmMODUL10 == "1" ? "checked":"";$chkModul10_2 = $fmMODUL10 == "2" ? "checked":"";
			$chkModul11_0 = $fmMODUL11 == "0" ? "checked":"";$chkModul11_1 = $fmMODUL11 == "1" ? "checked":"";$chkModul11_2 = $fmMODUL11 == "2" ? "checked":"";
			$chkModul12_0 = $fmMODUL12 == "0" ? "checked":"";$chkModul12_1 = $fmMODUL12 == "1" ? "checked":"";$chkModul12_2 = $fmMODUL12 == "2" ? "checked":"";
			$chkModul13_0 = $fmMODUL13 == "0" ? "checked":"";$chkModul13_1 = $fmMODUL13 == "1" ? "checked":"";$chkModul13_2 = $fmMODUL13 == "2" ? "checked":"";
			$chkModulref_0 = $fmMODULref == "0" ? "checked":"";$chkModulref_1 = $fmMODULref == "1" ? "checked":"";$chkModulref_2 = $fmMODULref == "2" ? "checked":"";
			$chkModuladm_0 = $fmMODULadm == "0" ? "checked":"";$chkModuladm_1 = $fmMODULadm == "1" ? "checked":"";$chkModuladm_2 = $fmMODULadm == "2" ? "checked":"";
		}
		$BARU = "0";
	}
	else
	{
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add")
{
	$fmKODEPENGGUNA = "";
	$fmNAMAPENGGUNA = "";
	$fmSANDIPENGGUNA = "";
	$fmLEVELPENGGUNA = "";
	$fmGROUPPENGGUNA = $fmSKPD.".".$fmUNIT.".".$fmSUBUNIT;
	$fmMODUL01 = "0";
	$fmMODUL02 = "0";
	$fmMODUL03 = "0";
	$fmMODUL04 = "0";
	$fmMODUL05 = "0";
	$fmMODUL06 = "0";
	$fmMODUL07 = "0";
	$fmMODUL08 = "0";
	$fmMODUL09 = "0";
	$fmMODUL10 = "0";
	$fmMODUL11 = "0";
	$fmMODUL12 = "0";
	$fmMODUL13 = "0";
	$fmMODULref = "0";
	$fmMODULadm = "0";
	$BARU = "1";
}

$Kondisi = "`group`='$fmSKPD.$fmUNIT.$fmSUBUNIT'";

//SKPD
$ListSKPD = cmbQuery1("fmSKPD",$fmSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' ","onChange=\"adminForm.submit()\" ",'Pilih Semua','00');
$ListUNIT = cmbQuery1("fmUNIT",$fmUNIT,"select d,nm_skpd from ref_skpd where c='$fmSKPD' and d !='00' and e = '00'  ","onChange=\"adminForm.submit()\" ",'Pilih Semua','00');
$ListSUBUNIT = cmbQuery1("fmSUBUNIT",$fmSUBUNIT,"select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' ","onChange=\"adminForm.submit()\" ",'Pilih Semua','00');


$NmHEAD = "NAMA PENGGUNA";
$Qry = mysql_query("select * from admin where $Kondisi order by `group`");
//echo "select * from admin where $Kondisi order by `group`";
$jmlDataSKPD = mysql_num_rows($Qry);
$Qry = mysql_query("select * from admin where $Kondisi order by `group` $LimitHalPENGGUNA ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalPENGGUNA*1) - 1);
$cb=0;
$jmlTampilSKPD = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilSKPD++;
	$KODEPENGGUNA = "{$isi['uid']}";
	$NAMAPENGGUNA = $isi["nama"];
	$LEVEL = $Main->UserLevel[$isi["level"]];
	$GROUP = $isi["group"];
	$MODUL01 = $Main->UserModul[$isi["modul01"]];
	$MODUL02 = $Main->UserModul[$isi["modul02"]];
	$MODUL03 = $Main->UserModul[$isi["modul03"]];
	$MODUL04 = $Main->UserModul[$isi["modul04"]];
	$MODUL05 = $Main->UserModul[$isi["modul05"]];
	$MODUL06 = $Main->UserModul[$isi["modul06"]];
	$MODUL07 = $Main->UserModul[$isi["modul07"]];
	$MODUL08 = $Main->UserModul[$isi["modul08"]];
	$MODUL09 = $Main->UserModul[$isi["modul09"]];
	$MODUL10 = $Main->UserModul[$isi["modul10"]];
	$MODUL11 = $Main->UserModul[$isi["modul11"]];
	$MODUL12 = $Main->UserModul[$isi["modul12"]];
	$MODUL13 = $Main->UserModul[$isi["modul13"]];
	$MODULREF = $Main->UserModul[$isi["modulref"]];
	$MODULADMIN = $Main->UserModul[$isi["moduladm"]];
	$STATUS = $isi["status"]=="1"?"Aktif":"Disabled";
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbSKPD$cb\" name=\"cidSKPD[]\" value=\"{$isi['uid']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODEPENGGUNA</div></td>
				<td><div align='left'>$NAMAPENGGUNA</div></td>
				<td><div align='left'>$LEVEL</div></td>
				<td><div align='center'>$GROUP</div></td>
				<td><div align='center'>$MODUL01</div></td>
				<td><div align='center'>$MODUL02</div></td>
				<td><div align='center'>$MODUL03</div></td>
				<td><div align='center'>$MODUL04</div></td>
				<td><div align='center'>$MODUL05</div></td>
				<td><div align='center'>$MODUL06</div></td>
				<td><div align='center'>$MODUL07</div></td>
				<td><div align='center'>$MODUL08</div></td>
				<td><div align='center'>$MODUL09</div></td>
				<td><div align='center'>$MODUL10</div></td>
				<td><div align='center'>$MODUL11</div></td>
				<td><div align='center'>$MODUL12</div></td>
				<td><div align='center'>$MODUL13</div></td>
				<td><div align='center'>$MODULREF</div></td>
				<td><div align='center'>$MODULADMIN</div></td>
				<td><div align='center'>$STATUS</div></td>
				</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar Pengguna </th>
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
				<th width='5%' class=\"title\"><div align=center>No.</div></th>
				<TH width='1%'><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilSKPD,'cbSKPD','toggle2');\" /></TD>
				<th width='5%' class=\"title\"><div align=center>ID<br>Pengguna</div></th>
				<th width='20%' class=\"title\"><div align=center>Nama Lengkap</div></th>
				<th width='5%' class=\"title\"><div align=center>Level</div></th>
				<th width='5%' class=\"title\"><div align=center>Group</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>01</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>02</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>03</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>04</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>05</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>06</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>07</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>08</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>09</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>10</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>11</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>12</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>13</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>Referensi</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>Administrasi</div></th>
				<th width='5%' class=\"title\"><div align=center>Modul<br>Status</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=20 align=center>
	".Halaman($jmlDataSKPD,$Main->PagePerHal,"HalPENGGUNA")."
	</td>
	</tr>

		</table>
	</td>
</tr>
</table>
";

if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
			$chkLevel1 = $fmLEVELPENGGUNA == "1" ? "checked":"";$chkLevel2 = $fmLEVELPENGGUNA == "2" ? "checked":"";
			$chkStatus_0 = $fmStatus == "0" ? "checked":"";$chkStatus_1 = $fmStatus == "1" ? "checked":"";
			$chkModul01_0 = $fmMODUL01 == "0" ? "checked":"";$chkModul01_1 = $fmMODUL01 == "1" ? "checked":"";$chkModul01_2 = $fmMODUL01 == "2" ? "checked":"";
			$chkModul02_0 = $fmMODUL02 == "0" ? "checked":"";$chkModul02_1 = $fmMODUL02 == "1" ? "checked":"";$chkModul02_2 = $fmMODUL02 == "2" ? "checked":"";
			$chkModul03_0 = $fmMODUL03 == "0" ? "checked":"";$chkModul03_1 = $fmMODUL03 == "1" ? "checked":"";$chkModul03_2 = $fmMODUL03 == "2" ? "checked":"";
			$chkModul04_0 = $fmMODUL04 == "0" ? "checked":"";$chkModul04_1 = $fmMODUL04 == "1" ? "checked":"";$chkModul04_2 = $fmMODUL04 == "2" ? "checked":"";
			$chkModul05_0 = $fmMODUL05 == "0" ? "checked":"";$chkModul05_1 = $fmMODUL05 == "1" ? "checked":"";$chkModul05_2 = $fmMODUL05 == "2" ? "checked":"";
			$chkModul06_0 = $fmMODUL06 == "0" ? "checked":"";$chkModul06_1 = $fmMODUL06 == "1" ? "checked":"";$chkModul06_2 = $fmMODUL06 == "2" ? "checked":"";
			$chkModul07_0 = $fmMODUL07 == "0" ? "checked":"";$chkModul07_1 = $fmMODUL07 == "1" ? "checked":"";$chkModul07_2 = $fmMODUL07 == "2" ? "checked":"";
			$chkModul08_0 = $fmMODUL08 == "0" ? "checked":"";$chkModul08_1 = $fmMODUL08 == "1" ? "checked":"";$chkModul08_2 = $fmMODUL08 == "2" ? "checked":"";
			$chkModul09_0 = $fmMODUL09 == "0" ? "checked":"";$chkModul09_1 = $fmMODUL09 == "1" ? "checked":"";$chkModul09_2 = $fmMODUL09 == "2" ? "checked":"";
			$chkModul10_0 = $fmMODUL10 == "0" ? "checked":"";$chkModul10_1 = $fmMODUL10 == "1" ? "checked":"";$chkModul10_2 = $fmMODUL10 == "2" ? "checked":"";
			$chkModul11_0 = $fmMODUL11 == "0" ? "checked":"";$chkModul11_1 = $fmMODUL11 == "1" ? "checked":"";$chkModul11_2 = $fmMODUL11 == "2" ? "checked":"";
			$chkModul12_0 = $fmMODUL12 == "0" ? "checked":"";$chkModul12_1 = $fmMODUL12 == "1" ? "checked":"";$chkModul12_2 = $fmMODUL12 == "2" ? "checked":"";
			$chkModul13_0 = $fmMODUL13 == "0" ? "checked":"";$chkModul13_1 = $fmMODUL13 == "1" ? "checked":"";$chkModul13_2 = $fmMODUL13 == "2" ? "checked":"";
			$chkModulref_0 = $fmMODULref == "0" ? "checked":"";$chkModulref_1 = $fmMODULref == "1" ? "checked":"";$chkModulref_2 = $fmMODULref == "2" ? "checked":"";
			$chkModuladm_0 = $fmMODULadm == "0" ? "checked":"";$chkModuladm_1 = $fmMODULadm == "1" ? "checked":"";$chkModuladm_2 = $fmMODULadm == "2" ? "checked":"";

	$Main->Isi .= "
	<br>
	<A NAME='FORMENTRY'></A>
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
			<td colspan=3><b>PENGGUNA</td>
		</tr>

		<tr>
			<td WIDTH='200'>ID Pengguna</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODEPENGGUNA' VALUE='$fmKODEPENGGUNA'></td>
		</tr>
		<tr>
			<td WIDTH='200'>Nama Lengkap</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMAPENGGUNA' VALUE='$fmNAMAPENGGUNA' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>Sandi</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmSANDIPENGGUNA' VALUE='$fmSANDIPENGGUNA' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>Level</td>
			<td WIDTH='1%'>:</td>
			<td>
				<INPUT $chkLevel1 TYPE='RADIO' NAME='fmLEVELPENGGUNA' VALUE='1'>&nbsp;Administrator &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkLevel2 TYPE='RADIO' NAME='fmLEVELPENGGUNA' VALUE='2'>&nbsp;Operator &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Group </td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='text' NAME='fmGROUPPENGGUNA' value='$fmGROUPPENGGUNA'></td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 01. Perencanaan dan Penganggaran</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul01_0 TYPE='RADIO' NAME='fmMODUL01' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul01_1 TYPE='RADIO' NAME='fmMODUL01' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul01_2 TYPE='RADIO' NAME='fmMODUL01' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 02. Pengadaan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul02_0 TYPE='RADIO' NAME='fmMODUL02' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul02_1 TYPE='RADIO' NAME='fmMODUL02' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul02_2 TYPE='RADIO' NAME='fmMODUL02' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 03. Penerimaan dan Pengeluaran</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul03_0 TYPE='RADIO' NAME='fmMODUL03' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul03_1 TYPE='RADIO' NAME='fmMODUL03' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul03_2 TYPE='RADIO' NAME='fmMODUL03' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 04. Penggunaan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul04_0 TYPE='RADIO' NAME='fmMODUL04' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul04_1 TYPE='RADIO' NAME='fmMODUL04' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul04_2 TYPE='RADIO' NAME='fmMODUL04' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 05. Penatausahaan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul05_0 TYPE='RADIO' NAME='fmMODUL05' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul05_1 TYPE='RADIO' NAME='fmMODUL05' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul05_2 TYPE='RADIO' NAME='fmMODUL05' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 06. Pemanfaatan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul06_0 TYPE='RADIO' NAME='fmMODUL06' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul06_1 TYPE='RADIO' NAME='fmMODUL06' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul06_2 TYPE='RADIO' NAME='fmMODUL06' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 07. Pengamanan dan Pemeliharaan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul07_0 TYPE='RADIO' NAME='fmMODUL07' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul07_1 TYPE='RADIO' NAME='fmMODUL07' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul07_2 TYPE='RADIO' NAME='fmMODUL07' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 08. Penilaian</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul08_0 TYPE='RADIO' NAME='fmMODUL08' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul08_1 TYPE='RADIO' NAME='fmMODUL08' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul08_2 TYPE='RADIO' NAME='fmMODUL08' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 09. Penghapusan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul09_0 TYPE='RADIO' NAME='fmMODUL09' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul09_1 TYPE='RADIO' NAME='fmMODUL09' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul09_2 TYPE='RADIO' NAME='fmMODUL09' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 10. Pemindahtanganan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul10_0 TYPE='RADIO' NAME='fmMODUL10' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul10_1 TYPE='RADIO' NAME='fmMODUL10' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul10_2 TYPE='RADIO' NAME='fmMODUL10' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 11.Pembiayaan</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul11_0 TYPE='RADIO' NAME='fmMODUL11' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul11_1 TYPE='RADIO' NAME='fmMODUL11' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul11_2 TYPE='RADIO' NAME='fmMODUL11' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 12. Tuntutan Ganti Rugi</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul12_0 TYPE='RADIO' NAME='fmMODUL12' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul12_1 TYPE='RADIO' NAME='fmMODUL12' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul12_2 TYPE='RADIO' NAME='fmMODUL12' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul 13. Pengawasan dan Pengendalian</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModul13_0 TYPE='RADIO' NAME='fmMODUL13' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul13_1 TYPE='RADIO' NAME='fmMODUL13' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModul13_2 TYPE='RADIO' NAME='fmMODUL13' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul Referensi</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModulref_0 TYPE='RADIO' NAME='fmMODULref' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModulref_1 TYPE='RADIO' NAME='fmMODULref' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModulref_2 TYPE='RADIO' NAME='fmMODULref' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Modul Administrasi</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkModuladm_0 TYPE='RADIO' NAME='fmMODULadm' VALUE='0'>&nbsp;Disabled &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModuladm_1 TYPE='RADIO' NAME='fmMODULadm' VALUE='1'>&nbsp;Write &nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkModuladm_2 TYPE='RADIO' NAME='fmMODULadm' VALUE='2'>&nbsp;Read &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td WIDTH='200'>Status USER</td>
			<td WIDTH='1%'>:</td>
			<td >
				<INPUT $chkStatus_0 TYPE='RADIO' NAME='fmStatus' VALUE='0'>&nbsp;Disabled / Blocked&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT $chkStatus_1 TYPE='RADIO' NAME='fmStatus' VALUE='1'>&nbsp;Aktif &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
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
		<input type=\"hidden\" name=\"SANDI2\" value=\"$fmSANDIPENGGUNA\" />
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