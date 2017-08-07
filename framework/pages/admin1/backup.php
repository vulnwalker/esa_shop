<?php
//SKPD
$coSKPD=$_COOKIE['coSKPD'];
if ($coSKPD=='00')
{
	$ListSKPD = cmbQuery1("fmSKPD",$coSKPD,"select c,nm_skpd from ref_skpd where c!='00' and d ='00' and e = '00' "," ",'Pilih Semua','00');
}
else
{
	$ListSKPD = cmbQuery1("fmSKPD",$coSKPD,"select c,nm_skpd from ref_skpd where c='$coSKPD' and d ='00' and e = '00' "," ",'','');
}
$Main->Isi = "
<center><br><br><br>
<form method=\"post\" action=\"db_dump.php\">Backup Data ATISISLOG Webbased (MySQL Server)<br>
<table align=center width=200>
	<tr>
			<td WIDTH='10%'>SKPD</td>
			<td WIDTH='1%'>:</td>
			<td WIDTH='50%'>$ListSKPD </td>
			<td WIDTH='10%'> <input type=\"submit\" name=\"Submit\" value=\"Export\"></td>
		</tr>
</table>
<input type=\"hidden\"  name=\"asfile\" value=\"sendit\">
<input type=\"hidden\" name=\"what\" value=\"data\">
<input type=\"hidden\" name=\"server\" value=\"1\">
<input type=\"hidden\" name=\"db\" value=\"$MySQL->DB\">
</form>";
?>