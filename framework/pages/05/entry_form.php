<?php
//--------- Param ---------




$Main->entryFrom = "
<A NAME='FORMENTRY'></A>

<!--FORM-->
<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<TR>
	<td>Nama Barang</td>
	<td>:</td>
	<td>
	".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2.php","fmIDBARANG","fmNMBARANG","$ReadOnly","$DisAbled"," onselect=\"adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.submit();\" ")."
	
	</td>
	</tr>
	<tr>
	<td>Nomor Register</td>
		<td>:</td>
		<td><b><INPUT type=text name='fmREGISTER' value='$fmREGISTER' size='4' maxlength='4' onKeyup='infofmREGISTER.value=this.value'></b></td>
	</tr>

	<tr>
	<td>Tahun Perolehan</td>
		<td>:</td>
		<td>
		<input type=\"text\" name=\"fmTAHUNPEROLEHAN\" value=\"$fmTAHUNPEROLEHAN\" size='4' maxlength=4 onchange=\"s=this.value+'';infofmTAHUNPEROLEHAN.value=s.substr(2,2);\"/></td>
	</tr>

	<tr valign=\"top\">
	  <td width='245'>Harga Barang (Perolehan)</td>
	  <td width='25'>:</td>
	  <td>Rp. 
		".inputFormatRibuan("fmHARGABARANG")."</td>
	</tr>

	<tr valign=\"top\">
	  <td width='245'>Jumlah Barang</td>
	  <td width='25'>:</td>
	  <td>
		<input type=\"text\" size='4' name=\"fmJUMLAHBARANG\" value=\"$fmJUMLAHBARANG\" />&nbsp;&nbsp;
	  Satuan : <input type=\"text\" size='6' name=\"fmSATUAN\" value=\"$fmSATUAN\" />
	  </td>
	</tr>
	
	<tr valign=\"top\">
	  <td>Asal Usul/Cara Perolehan</td>
	  <td>:</td>
	  <td>
		".cmb2D('fmASALUSUL',$fmASALUSUL,$Main->AsalUsul,'')."
	</td>
	</tr>
	<tr valign=\"top\">
	  <td>Kondisi Barang</td>
	  <td>:</td>
	  <td>
		".cmb2D('fmKONDISIBARANG',$fmKONDISIBARANG,$Main->KondisiBarang,'')."
	</td>
	</tr>
	<tr valign=\"top\">
		<td >
		Status barang 
		</TD>
		<TD>:</TD>
		<TD>".cmb2D('fmSTATUSBARANG',$fmSTATUSBARANG,$Main->StatusBarang,'')." 
		</td>
	</tr>
	<tr valign=\"top\">
		<td >
		Tanggal update
		</TD>
		<TD>:</TD>
		<TD> ".InputKalender("fmTGLUPDATE")." 
		<input type=button onClick=\"adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.submit();\" value='Tampilkan KIB'>
		</td>
	</tr>

</table>
<table width=\"100%\" height=\"100%\" >
	<tr>
		<td align=right width=90%>
			<input type=hidden name='klikDetil' onClick='adminForm.submit()' value='Detil'>
		</td>
	</tr>

</table>
<table width=\"100%\" height=\"100%\" >
	<tr>
		<td align=center width=90%>
			<b>Input Data Inventaris ($InfoKIB)
		</td>
	</tr>
</table>

$DetilKIB

<br>
<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"50\"><tr>
			<td>
			".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."
			</td>
			<td>
			".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
			</td>
			<td>
			".PanelIcon1("?Pg=$Pg&SPg=$SPg","cancel_f2.png","Batal")."
			</td>
			</tr>
		</table>
	</td></tr>
</table>


";

?>