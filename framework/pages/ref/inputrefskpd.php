<?php
$HalSKPD = cekPOST("HalSKPD",1);
$LimitHalSKPD = " limit ".(($HalSKPD*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;


$fmSKPD = cekPOST("fmSKPD","");
$fmUNIT = cekPOST("fmUNIT","");
$fmSUBUNIT = cekPOST("fmSUBUNIT","");
$fmSEKSI = cekPOST("fmSEKSI","");

$fmKODESKPD = cekPOST("fmKODESKPD","");
$fmNAMASKPD = cekPOST("fmNAMASKPD","");

$fmKODESKPDf = cekPOST("fmKODESKPDf","");
$fmNAMASKPDf = cekPOST("fmNAMASKPDf","");

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
		$ArIDSKPD = explode(".",$fmKODESKPD);
		if(count($ArIDSKPD) != 4){
			$Info = "<script>alert('Format Kode SKPD Salah');</script>";
		}
		elseif(strlen($ArIDSKPD[0]) != 2 ||strlen($ArIDSKPD[1]) != 2 ||strlen($ArIDSKPD[2]) != 2 ||strlen($ArIDSKPD[3]) != $Main->SUBUNIT_DIGIT ){
			$Info = "<script>alert('Format Kode SKPD Salah');</script>";
		}
		elseif(empty($fmNAMASKPD)){
			$Info = "<script>alert('Nama SKPD Tidak Boleh Kosong');</script>";
		}
		else{
			
			
			$CekKon = $ArIDSKPD[0].$ArIDSKPD[1].$ArIDSKPD[2].$ArIDSKPD[3];//.$ArIDSKPD[4];
			$Cek = mysql_num_rows(mysql_query("select * from ref_skpd where concat(c,d,e,e1)='$CekKon' "));
			if($Cek && $BARU=="1"){
				$Info = "<script>alert('Kode $fmKODESKPD sudah ada, data tidak disimpan');</script>";
			}
			else{
				//SIMPAN DATA
				$err='';
				if($BARU=="1"){
					$Simpan = mysql_query(
						"insert into ref_skpd (c,d,e,e1,nm_skpd,nm_barcode)values".
						"('{$ArIDSKPD[0]}','{$ArIDSKPD[1]}','{$ArIDSKPD[2]}','{$ArIDSKPD[3]}','$fmNAMASKPD','".cekPOST('nm_barcode',"")."')"
					);
					
				}
				if($BARU=="0"){
					
					/*$old = mysql_fetch_array(mysql_query(
						"select * from ref_skpd where $Kondisi limit 1"
					));
					
					$err=$old['c'].'.'.$old['d'].'.'.$old['e'].'.'.$old['e1'].'!= '.$fmID  ;
					if($err=='' && $old['c'].'.'.$old['d'].'.'.$old['e'].'.'.$old['e1']!= $fmID  ){
						$err= 'Kode Sudah Ada!'; 
					}*/
					
					if($err==''){
						
					
						$aqry = "update ref_skpd set nm_skpd='$fmNAMASKPD', ".
							" nm_barcode ='".cekPOST('nm_barcode',"")."' ".
							" where concat(c,d,e,e1)='$CekKon' limit 1";
						//echo $aqry;
						$Simpan = mysql_query(
							$aqry
						);
					}
				}
				//if($err==''){
					if($Simpan)	{
						$Info = "<script>alert('Data sudah di simpan');</script>";
						//$err = 'Data sudah di simpan';
						$Act = "";
					} else {
						$Info = "<script>alert('Data GAGAL di simpan');</script>";
						//$err= 'Data GAGAL di simpan';
						$Act="Add";
					}	
				//}
				
				/*if($err!=''){
					$Info = "<script>alert('$err');</script>";
					$Act="Add";
				}else{
					$Info = "<script>alert('Data sudah di simpan');</script>";					
					$Act = "";
				}*/
			}//end CEK
		}//end EMPTY SKPD
	}//end BARU=1 || BARU=0
}

if($Act == "Hapus")
{
	$fmID = $cidSKPD[0];
	$Kondisi = "concat(c,'.',d,'.',e,'.',e1)='$fmID'";
	$Hapus = mysql_query("delete from ref_skpd where $Kondisi");
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

if($Act == "Edit") {
	if(count($cidSKPD)==1) {
		$fmID = $cidSKPD[0];
		$Kondisi = "concat(c,'.',d,'.',e,'.',e1)='$fmID'";
		
		
		//if($err==''){
			
		
			$Qry = mysql_query("select * from ref_skpd where $Kondisi limit 1");
			while($isi=mysql_fetch_array($Qry))
			{
				$fmKODESKPD = $isi['c'].".".$isi['d'].".".$isi['e'].'.'.$isi['e1'];
				$fmNAMASKPD = $isi['nm_skpd'];
				$nm_barcode = $isi['nm_barcode'];
			}
		//}else{
		//	$Info = "<script>alert('$err');</script>";
		//}
		$BARU = "0";
		
	}
	else {
		$fmKODESKPD = "";
		$fmNAMASKPD = "";
		$nm_barcode = '';
		$Act="";
		$BARU = "1";
	}
}
if($Act == "Add") {
	$fmKODESKPD = "";
	$fmNAMASKPD = "";
	$nm_barcode = '';
	$BARU = "1";
}


//komndisi skpd -----------------
$NmHEAD = "NAMA SKPD";

$arrkond = array();
if(!empty($fmSKPD)) $arrkond[] = " c='$fmSKPD' ";
if(!empty($fmUNIT)) $arrkond[] = " d='$fmUNIT' ";
if(!empty($fmSUBUNIT)) $arrkond[] = " e='$fmSUBUNIT' ";
// if(!empty($fmSEKSI)) $arrkond[] = " e='$fmSEKSI' ";
if(!empty($fmNAMASKPDf)) $arrkond[] = " nm_skpd like '%$fmNAMASKPDf%' ";

$ArIDSKPD = explode(".",$fmKODESKPDf);
if(!empty($ArIDSKPD[0])) $arrkond[] = " c='$ArIDSKPD[0]' ";
if(!empty($ArIDSKPD[1])) $arrkond[] = " d='$ArIDSKPD[1]' ";
if(!empty($ArIDSKPD[2])) $arrkond[] = " e='$ArIDSKPD[2]' ";
if(!empty($ArIDSKPD[3])) $arrkond[] = " e1='$ArIDSKPD[3]' ";



$Kondisi = join(' and ',$arrkond);



if (!empty($Kondisi))
{
	$Kondisi=" c<>'' and ".$Kondisi;
} else {
	$Kondisi=" c<>'' ";
}




switch ($Main->SUBUNIT_DIGIT ){
	case 3:	$e1_default = '000'; break;
	default: $e1_default = '00';break;
}
//echo $e1_default;
// echo "select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' and e1='$e1_default'  order by c,d,e ";
//SKPD
$ListSKPD = cmbQuery1("fmSKPD",$fmSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' order by c,d,e","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListUNIT = cmbQuery1("fmUNIT",$fmUNIT,"select d,nm_skpd from ref_skpd where c='$fmSKPD' and d !='00' and e = '00'  order by c,d,e ","onChange=\"adminForm.submit()\" ",'Pilih','');
$ListSUBUNIT = cmbQuery1("fmSUBUNIT",$fmSUBUNIT,"select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' and e1='$e1_default'  order by c,d,e ","onChange=\"adminForm.submit()\" ",'Pilih','');
//echo "select e,nm_skpd from ref_skpd where c='$fmSKPD' and d ='$fmUNIT' and e != '00' and e1='00'  order by c,d,e ";


$aqry = "select * from ref_skpd where  $Kondisi order by c,d,e,e1"; //echo $aqry;
$Qry = mysql_query($aqry);
$jmlDataSKPD = mysql_num_rows($Qry);
$Qry = mysql_query("select * from ref_skpd where  $Kondisi order by c,d,e,e1 $LimitHalSKPD ");
$ListDATA = "";
$no=$Main->PagePerHal * (($HalSKPD*1) - 1);
$cb=0;
$jmlTampilSKPD = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilSKPD++;
	$KODESKPD = "{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['e1']}";
	$NAMASKPD = $isi["nm_skpd"];
	$ListDATA .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><input type=\"checkbox\" id=\"cbSKPD$cb\" name=\"cidSKPD[]\" value=\"{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['e1']}\" onClick=\"isChecked(this.checked);\" />&nbsp;</td>
				<td><div align='left'>$KODESKPD</div></td>
				<td><div align='left'>$NAMASKPD</div></td>
				<td><div align='left'>".$isi["nm_barcode"]."</div></td>
		</tr>";
	$cb++;

}
$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Daftar SKPD </th>
</tr>
</table>
<table width=\"100%\">
<tr>
	<td width=\"60%\" valign=\"top\">
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
		<td WIDTH='10%'>BIDANG</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSKPD</td>
		</tr>
		<tr>
		<td WIDTH='10%'>SKPD</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListUNIT</td>
		</tr>
		<tr>
		<td WIDTH='10%'>UNIT</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='89%'>$ListSUBUNIT</td>
		</tr>
		<tr>
		<td WIDTH='10%'>Cari</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH=''>KODE&nbsp;<INPUT TYPE='TEXT' NAME='fmKODESKPDf' VALUE='$fmKODESKPDf'>&nbsp;&nbsp;NAMA SKPD&nbsp;
			<INPUT TYPE='TEXT' NAME='fmNAMASKPDf' VALUE='$fmNAMASKPDf' SIZE=50>
			<INPUT TYPE='BUTTON' NAME='fmTampilkan' value='Tampilkan' onclick='adminForm.submit()'>
			</td>
		</tr>
		</table>
		
		<table width=\"100%\" height=\"100%\" class=\"adminlist\">
			<tr>
				<th width='4%' class=\"title\"><div align=left>No.</div></th>
				<TH width='2%' align='left'><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlTampilSKPD,'cbSKPD','toggle2');\" /></TD>
				<th width='10%' class=\"title\"><div align=left>Kode SKPD</div></th>
				<th width='42%' class=\"title\"><div align=left>$NmHEAD</div></th>
				<th width='42%' class=\"title\" ><div align=left>NAMA BARCODE</div></th>
			</tr>
			$ListDATA
	<tr>
	<td colspan=5 align=center>
	".Halaman($jmlDataSKPD,$Main->PagePerHal,"HalSKPD")."
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
			<td colspan=3><b>SKPD/UNIT/SUB UNIT</td>
		</tr>

		<tr>
			<td WIDTH='200'>KODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmKODESKPD' VALUE='$fmKODESKPD'></td>			
		</tr>
		<tr>
			<td WIDTH='200'>NAMA UNIT</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='fmNAMASKPD' VALUE='$fmNAMASKPD' SIZE=100></td>
		</tr>
		<tr>
			<td WIDTH='200'>NAMA BARCODE</td>
			<td WIDTH='1%'>:</td>
			<td ><INPUT TYPE='TEXT' NAME='nm_barcode' id='nm_barcode' VALUE='$nm_barcode' SIZE=100></td>
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
			<td>".PanelIcon1("javascript:adminForm.target='blank_';adminForm.action='?Pg=PR&SPg=ref_skpd_cetak';adminForm.submit();adminForm.target='';adminForm.action=''","print_f2.png","Cetak")."</td>
			<td>".PanelIcon1("javascript:adminForm.target='';adminForm.action='?Pg=PR&SPg=ref_skpd_cetak&SDest=XLS';adminForm.submit();adminForm.action=''","export_xls.png","Excel")."</td>
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