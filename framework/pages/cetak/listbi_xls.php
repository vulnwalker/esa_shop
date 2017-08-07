<?PHP

function list_header($XXBIDANG='BIDANG',$XXASISTEN='ASISTEN',
$XXBIRO='BIRO',$XXSEKSI='BIRO',$XXKOTA='KOTA',$XXPROP='PROPINSI',$XXKDLOK='KDLOKASI') {
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

	color: #000000;
	text-align: center;
	background-color: #DBDBDB;
}
table.cetak th.th02 {

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
	font-weight: bold;
}
.subjudulcetak {
	font-size: 12px;
	font-weight: bold;
}
.GCTK {
	background-color: white;
	vertical-align: middle;
}
.nfmt1 {
	mso-number-format:"\#\,\#\#0_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
	
}
.nfmt2 {
	mso-number-format:"0\.00_";
	
}
.nfmt3 {
	mso-number-format:"0000";
	
}
.nfmt4 {
	mso-number-format:"\#\,\#\#0.00_\)\;\[Red\]\\\(\#\,\#\#0\\\)";
}
table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}	
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
			<td   width="10%" colspan="2" style="font-weight:bold;font-size:9pt" >BIDANG</td>
			<td align=center width="4%" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXBIDANG.'</td> 
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >SKPD</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXASISTEN.'</td>
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >UNIT</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXBIRO.'</td> </tr> 
			<tr>
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >SUB UNIT</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" >'.$XSEKSI.'</td> </tr> 
			<tr>

			<td  colspan="2" style="font-weight:bold;font-size:9pt" >KABUPATEN/KOTA</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" >'.$XXKOTA.'</td> </tr> 
			<tr> 
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >PROVINSI</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="12" style="font-weight:bold;font-size:9pt" > JAWA BARAT</td> </tr> 
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

function list_tableheader($dlmribuan='')
{
$sharga = !empty($dlmribuan)?'HARGA Ribuan)':'HARGA';
$isix='<thead>
		<tr>
			<th class="th02" colspan="3">Nomor</th>
			<th class="th02" colspan="3">Spesifikasi Barang</th>
			<th class="th01" rowspan="2">Bahan</th>
			<th class="th01" rowspan="2" style="width:100">Asal Usul / Cara Perolehan </th>

			<th class="th01" rowspan="2" style="width:50">Tahun Perolehan</th>
			<th class="th01" rowspan="2" style="width:100">Ukuran Barang / Konstruksi (P,SP,D)</th>
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

function list_table($XXNO='',$XXKDB='',$XXREG='',$XXNJB='',$XXMERK='',$XXNOSP='',$XXBHN='',
$XXASAL='',$XXTHN='',$XXUB=0,$XXSAT='',$XXKB='',$XXJB='',$XXJH=0,$XXKET='')
{
$isix='		<tr >
			<td class="GCTK" valign=middle align=right>'.$XXNO.'&nbsp;</td>

			<td class="GCTK" align=center>'.$XXKDB.'</td>
			<td class="GCTK" align=center><div class="nfmt3">'.$XXREG.'</div></td>
			<td class="GCTK" >'.$XXNJB.'</td>
			<td class="GCTK" >'.$XXMERK.'</td>
			<td class="GCTK" >'.$XXNOSP.'</td>
			<td class="GCTK" >'.$XXBHN.'</td>

			<td class="GCTK" >'.$XXASAL.'</td>
			<td class="GCTK" align=center>'.$XXTHN.'</td>
			<td class="GCTK" align=center>'.$XXUB.'</td>
			<td class="GCTK" >'.$XXSAT.'</td>
			<td class="GCTK" >'.$XXKB.'</td>

			<td align=center class="GCTK">'.$XXJB.'</td>
			<td align=right class="GCTK"><div class="nfmt4">'.$XXJH.'</div></td>
			<td class="GCTK" >'.$XXKET.'</td>
        </tr>
	

';
return $isix;			
}
function list_tablefooter($XXJML=0,$dlmribuan='')
{
$sharga = !empty($dlmribuan)?'TOTAL (Ribuan)':'TOTAL';
$isix='<tr><td class="GCTK" colspan=13 ><b>'.$sharga.'</b></td><td class="GCTK" align=right><b><div class="nfmt4">'.$XXJML.'</div></b></td><td class="GCTK" colspan=1 >&nbsp;</td></tr> 
	</table>
</td>
</tr>	
';
return $isix;			
}

function list_footer($XXTMPTGL='',$XXJABATAN1='',$XXNAMA1='',$XXNIP1='',
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