<?PHP

function listbi_header($XXBIDANG='BIDANG',$XXASISTEN='ASISTEN',
$XXBIRO='BIRO',$XXKOTA='KOTA',$XXPROP='PROPINSI',$XXKDLOK='KDLOKASI') {
$isix='<html><head>
	<title>::ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah)</title>
	<style>
table.rangkacetak {
	background-color: #FFFFFF;
	margin: 0cm;
	padding: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	font-family : Arial,  sans-serif;
}
table.cetak {
	background-color: #FFFFFF;
	font-family : Arial,  sans-serif;
	margin: 0px;
	border: 0px;
	width: 30cm;
	border-collapse: collapse;
	color: #000000;
	font-size : 9pt;
}
table.cetak th.th01 {
	margin: 0px;
	height: 25px;
	border: 1px solid #000000;
	border-bottom: 2px solid #000000;
	font-size: 11px;
	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak th.th02 {
	margin: 0px;
	height: 25px;
	border: 1px solid #000000;
	font-size: 11px;
	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak tr.row0 {
	background-color: #DBDBDB;
	text-align: left;
}
table.cetak tr.row1 {
	background-color: #FFF;
	text-align: left;
}
table.cetak input {
	font-family: Arial Narrow;
	font-size: 9pt;
}
/* untuk repeat header */
thead { 
	display: table-header-group; 
}
/* untuk repeat footer */
tfoot { 
	display: table-footer-group; 
}
.judulcetak {
	width: 30cm;
	font-size: 16px;
	font-family: Arial Narrow;
	font-weight: bold;
}
.subjudulcetak {
	font-size: 12px;
	font-family: Arial Narrow;
	font-weight: bold;
}
.GarisCetak {
	border: 1px solid #000001;
	background-color: white;
}
.numberfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.numberfmt2 {
	mso-number-format:"0\.00_ ";
	
}
.numberfmt3 {
	mso-number-format:"0000";
	
}	
</style>
</head>

<body>
<table class="rangkacetak">
<tr>
<td valign="top">

	<table style="width:30cm" border="0">

		<tr>
			<td class="judulcetak" colspan="15" ><DIV ALIGN=CENTER>BUKU INVENTARIS BARANG</DIV></td>
		</tr>
	</table>

  </td>
  </tr>
  </tr>
  <tr>
<td valign="top">
<table cellpadding=0 cellspacing=0 border=0 width="100%"> 
			<tr>
			<td align=left  width="10%" colspan="2" style="font-weight:bold;font-size:9pt" >BIDANG</td>
			<td align=center width="4%" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXBIDANG.'</td> 
			</tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >ASISTEN / OPD</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXASISTEN.'</td>
			</tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >BIRO / UPTD/B</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXBIRO.'</td> </tr> 
			<tr>
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >KABUPATEN/KOTA</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXKOTA.'</td> </tr> 
			<tr> 
			<td align=left colspan="2" style="font-weight:bold;font-size:9pt" >PROVINSI</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td align=left colspan="12" style="font-weight:bold;font-size:9pt" > JAWA BARAT</td> </tr> 
			</table>  
  </td>
  </tr>
  <tr>
  <td valign="top">
 	
	<table width="100%" border="0">
		<tr>
			<td colspan="12">&nbsp;</td>
			<td align=right style="font-weight:bold;font-size:9pt" colspan="3">NO. KODE LOKASI : '.$XXKDLOK.'</td>
		</tr>
	</table>

  </td>
  </tr>
  <tr>
  <td valign="top">
  	<table class="cetak" border="1">
	
';
return $isix;	
}

function listbi_tableheader($dlmribuan='')
{
$sharga = !empty($dlmribuan)?'HARGA Ribuan)':'HARGA';
$isix='<thead>
		<tr>
			<th class="th02" colspan="3">Nomor</th>
			<th class="th02" colspan="3">Spesifikasi Barang</th>
			<th class="th01" rowspan="2">Bahan</th>
			<th class="th01" rowspan="2" style="width:100">Asal Usul / Cara Perolehan </th>

			<th class="th01" rowspan="2" style="width:50">Tahun Perolehan</th>
			<th class="th01" rowspan="2" style="width:100">Ukuran Barang / Konstruksi P,SP,D)</th>
			<th class="th01" rowspan="2" style="width:50">Satuan</th>
			<th class="th01" rowspan="2" style="width:60">Keadaan Barang (B,KB,RB)</th>
			<th class="th02" colspan="2">Jumlah</th>
			<th class="th01" rowspan="2">Keterangan</th>

		</tr>
		<tr>
			<th class="th01" style="width:20">No.</th>
			<th class="th01" style="width:80">Kode Barang</th>
			<th class="th01" style="width:25">Reg</th>
			<th class="th01">Nama / Jenis Barang</th>
			<th class="th01" style="width:100">Merk / Tipe</th>

			<th class="th01">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
			<th class="th01" style="width:40">Barang</th>
			<th class="th01">'.$sharga.'</th>
		</tr>
		</thead>
			
';	
return $isix;		
}

function listbi_table($XXNO='',$XXKDB='',$XXREG='',$XXNJB='',$XXMERK='',$XXNOSP='',$XXBHN='',
$XXASAL='',$XXTHN='',$XXUB=0,$XXSAT='',$XXKB='',$XXJB='',$XXJH=0,$XXKET='')
{
$isix='		<tr >
			<td class="GarisCetak" valign=middle align=right>'.$XXNO.'&nbsp;</td>

			<td class="GarisCetak" align=center>'.$XXKDB.'</td>
			<td class="GarisCetak" align=center><div class="numberfmt3">'.$XXREG.'</div></td>
			<td class="GarisCetak" align=left>'.$XXNJB.'</td>
			<td class="GarisCetak" align=left>'.$XXMERK.'</td>
			<td class="GarisCetak" align=left>'.$XXNOSP.'</td>
			<td class="GarisCetak" align=left>'.$XXBHN.'</td>

			<td class="GarisCetak" align=left>'.$XXASAL.'</td>
			<td class="GarisCetak" align=center>'.$XXTHN.'</td>
			<td class="GarisCetak" align=left>'.$XXUB.'</td>
			<td class="GarisCetak" align=left>'.$XXSAT.'</td>
			<td class="GarisCetak" align=left>'.$XXKB.'</td>

			<td align=center class="GarisCetak">'.$XXJB.'</td>
			<td align=right class="GarisCetak"><div class="numberfmt1">'.$XXJH.'</div></td>
			<td class="GarisCetak" align=left>'.$XXKET.'</td>
        </tr>
	

';
return $isix;			
}
function listbi_tablefooter($XXJML=0,$dlmribuan='')
{
$sharga = !empty($dlmribuan)?'TOTAL (Ribuan)':'TOTAL';
$isix='<tr><td class="GarisCetak" colspan=13 ><b>'.$sharga.'</b></td><td class="GarisCetak" align=right><b><div class="numberfmt1">'.$XXJML.'</div></b></td><td class="GarisCetak" colspan=1 >&nbsp;</td></tr> 
	</table>
</td>
</tr>	
';
return $isix;			
}

function listbi_footer($XXTMPTGL='',$XXJABATAN1='',$XXNAMA1='',$XXNIP1='',
$XXJABATAN2='',$XXNAMA2='',$XXNIP2='')
{
$isix='<tr>
  <td valign="top">
<table style="width:30cm" border=0> 
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>

				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td colspan=8 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP2.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				</table>

</td>
</tr>
</table>
</body>
</html>
';
return $isix;			
}


?>