<?php
$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmMEREK = cekPOST("fmMEREK");
$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGASATUAN = cekPOST("fmHARGASATUAN");
$fmIDREKENING = cekPOST("fmIDREKENING");
$fmNMREKENING = cekPOST("fmNMREKENING");
$fmKET = cekPOST("fmKET");
$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//ProsesCekField
$MyField ="fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$ArRekening = explode(".",$fmIDREKENING);
		$JmlHARGA = $fmHARGASATUAN * $fmJUMLAH;
		$Simpan = false;
		if($Baru=="1")
		{
			//Simpan Baru
			$Qry = "insert into rkb (a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,merk_barang,jml_barang,harga,satuan,jml_harga,ket,tahun)
			values ('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','{$ArRekening[0]}','{$ArRekening[1]}','{$ArRekening[2]}','{$ArRekening[3]}','{$ArRekening[4]}','$fmMEREK','$fmJUMLAH','$fmHARGASATUAN','$fmSATUAN','$JmlHARGA','$fmKET','$fmTAHUNANGGARAN')";
			$Simpan = mysql_query($Qry);
		}
		if($Baru=="0")
		{
			$Kriteria = "concat(a,b,c,d,e,f,g,h,i,j,tahun)='{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNANGGARAN'";
			$Qry = "
			update rkb set 
				k = '{$ArRekening[0]}',l = '{$ArRekening[1]}',m = '{$ArRekening[2]}',n = '{$ArRekening[3]}',o = '{$ArRekening[4]}',	merk_barang='$fmMEREK',jml_barang='$fmJUMLAH',harga='$fmHARGASATUAN',satuan='$fmSATUAN',jml_harga='$JmlHARGA',ket='$fmKET'
			where $Kriteria ";
			$Simpan = mysql_query($Qry);
		}
		if($Simpan)
		{
			$Info = "<script>alert('Data telah di simpan')</script>";
			$Baru="0";
		}
		else
		{
			$Info = "<script>alert('Data TIDAK dapat disimpan')</script>";
		}
	}
	else
	{
		$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan')</script>";
	}
	
}





$Main->Isi = "

<A Name=\"ISIAN\"></A>
$Info
<div align=\"center\" class=\"centermain\">
	<div class=\"main\">
		<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">
		<table class=\"adminheading\">
		<tr>
		  <th height=\"47\" class=\"user\">Rencana Kebutuhan Barang</th>
		</tr>
		</table>

		<table width=\"100%\" class=\"adminheading\">
		<tr>
		<td colspan=4>
		<br>

		<table width=\"100%\">
		<tr>
			<td width=\"60%\" valign=\"top\">
				".WilSKPD()."
			</td>
		</tr>
		<tr><td>
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr valign=\"top\">   
			<td>Nama Barang</td>
			<td>:</td>
			<td>
			".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2a.php","fmIDBARANG","fmNMBARANG")."
			</td>
		</tr>
		</table>
	<input type=hidden name='Act'>
	<input type=hidden name='Baru' value='$Baru'>

	<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
		<tr><td class=\"menudottedline\">
				<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr>
				<td class=\"menudottedline\" height=\"52\" align=right>
				".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."
				</td>
				<td class=\"menudottedline\" height=\"52\">
				".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
				</td>
				<td align=right  class=\"menudottedline\" >
				".PanelIcon1("?Pg=$Pg","cancel_f2.png","Tutup")."
				</td>
				</tr>
				</table>
		</td></tr>
	</table>
<script language='javascript'>
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
</form>
</div></div>


";
?>