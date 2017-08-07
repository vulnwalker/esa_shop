<?PHP
// colom 16
function list_header($XXBIDANG='BIDANG',$XXASISTEN='ASISTEN',
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
	mso-number-format:"0\.00_ ";
	
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
br
     {mso-data-placement:same-cell;}			
	</style>
</head>
<body>
<table class="rangkacetak">
<tr>
<td valign="top">

	<table style="width:30cm" border="0">
		<tr>

			<td class="judulcetak" colspan="17"><DIV ALIGN=CENTER>KARTU INVENTARIS BARANG (KIB) D</DIV></td>
		</tr>
		<tr>
			<td class="judulcetak" colspan="17"><DIV ALIGN=CENTER>JALAN, IRIGASI, DAN JARINGAN</DIV></td>
		</tr>
	</table>

<table cellpadding=0 cellspacing=0 border=0 width="100%"> 
			<tr>
			<td   width="10%" colspan="2" style="font-weight:bold;font-size:9pt" >BIDANG</td>
			<td align=center width="4%" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="14" style="font-weight:bold;font-size:9pt" >'.$XXBIDANG.'</td> 
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >ASISTEN / OPD</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="14" style="font-weight:bold;font-size:9pt" >'.$XXASISTEN.'</td>
			</tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >BIRO / UPTD/B</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="14" style="font-weight:bold;font-size:9pt" >'.$XXBIRO.'</td> </tr> 
			<tr>
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >KABUPATEN/KOTA</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="14" style="font-weight:bold;font-size:9pt" >'.$XXKOTA.'</td> </tr> 
			<tr> 
			<td  colspan="2" style="font-weight:bold;font-size:9pt" >PROVINSI</td>
			<td align=center colspan="1" style="font-weight:bold;font-size:9pt" >:</td>
			<td  colspan="14" style="font-weight:bold;font-size:9pt" > JAWA BARAT</td> </tr> 
			</table>

	<table width="100%" border="0">
		<tr>
			<td align=right style="font-weight:bold;font-size:9pt" colspan="17">NO. KODE LOKASI : '.$XXKDLOK.'</td>
		</tr>
	</table>
	<table class="cetak" border="1">
		
';
return $isix;	
}

function list_tableheader($dlmribuan='')
{
$sharga = !empty($dlmribuan)?'HARGA (Ribuan)':'HARGA';
$isix='	<thead>
		<tr>
			<th class="th01" rowspan="2" style="width:20">No.</th>
			<th class="th01" rowspan="2" style="width:75">Kode Barang</th>
			<th class="th01" rowspan="2" style="width:35">Nomor Reg</th>
			<th class="th01" rowspan="2">Nama Barang</th>

			<th class="th01" rowspan="2">Konstruksi</th>
			<th class="th01" rowspan="2" style="width:40">Panjang (km)</th>
			<th class="th01" rowspan="2" style="width:40">Lebar (M)</th>
			<th class="th01" rowspan="2" style="width:40">Luas< (M2)</th>
			<th class="th01" rowspan="2">Letak / Alamat</th>

			<th class="th02" colspan="2">Dokumen</th>
			<th class="th01" rowspan="2">Status Tanah</th>
			<th class="th01" rowspan="2">Nomor Kode Tanah</th>
			<th class="th01" rowspan="2">Asal Usul</th>

			<th class="th01" rowspan="2" style="width:75">'.$sharga.'</th>
			<th class="th01" rowspan="2" style="width:50">Kondisi (B,KB,RB)</th>
			<th class="th01" rowspan="2">Ket</th>		

		</tr>
		<tr>
			<th class="th01" style="width:60">Tanggal</th>

			<th class="th01">Nomor</th>
		</tr>
		</thead>
';	
return $isix;		
}

function list_table($XXNO='',$XXKDB='',$XXREG='',$XXNB='',$XXKON='',$XXPJ=0,$XXLB=0,
$XXLUAS=0,$XXLOK='',$XXDTGL='',$XXDNO='',$XXST='',$XXNKT='',$XXASAL='',$XXHARGA=0,$XXKB='',$XXKET='')
{
$isix='<tr>
			<td class="GCTK" align=right>'.$XXNO.'&nbsp;</td>
			<td class="GCTK" align=center>'.$XXKDB.'</td>
			<td class="GCTK" align=center><div class="nfmt3">'.$XXREG.'</div></td>

			<td class="GCTK">'.$XXNB.'</td>
			<td class="GCTK">'.$XXKON.'</td>
			<td class="GCTK" align=right><div class="nfmt4">'.$XXPJ.'</div></td>
			<td class="GCTK" align=right><div class="nfmt4">'.$XXLB.'</div></td>
			<td class="GCTK" align=right><div class="nfmt4">'.$XXLUAS.'</div></td>
			<td class="GCTK">'.$XXLOK.'</td>

			<td class="GCTK" align=center>'.$XXDTGL.'</td>
			<td class="GCTK">'.$XXDNO.'</td>
			<td class="GCTK" align=center>'.$XXST.'</td>
			<td class="GCTK" align=center>'.$XXNKT.'</td>
			<td class="GCTK">'.$XXASAL.'</td>
			<td class="GCTK" align=right><div class="nfmt4">'.$XXHARGA.'</div></td>

			<td class="GCTK">'.$XXKB.'</td>
			<td class="GCTK">'.$XXKET.'</td>
		</tr>
';
return $isix;			
}

function list_tablefooter($XXJML=0,$dlmribuan='')
{

$sharga = !empty($dlmribuan)?'TOTAL (Ribuan)':'TOTAL';
$isix='<tr><td class="GCTK" colspan=14 ><b>'.$sharga.'</b></td><td class="GCTK" align=right><b><div class="nfmt4">'.$XXJML.'</div></b></td>
<td class="GCTK" colspan=1 >&nbsp;</td>
<td class="GCTK" colspan=1 >&nbsp;</td>
</tr> 
	</table>
';
return $isix;			
}

function list_footer($XXTMPTGL='',$XXJBT1='',$XXNM1='',$XXNIP1='',
$XXJBT2='',$XXNM2='',$XXNIP2='')
{
$isix='<table style="width:30cm" border=0> 
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>

				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>MENGETAHUI</B> </td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXTMPTGL.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN1.'</B> </td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>'.$XXJABATAN2.'</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 >&nbsp;</td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 >&nbsp;</td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA1.' )</B> </td>
				<td colspan=10 >&nbsp;</td> 
				<td align=center colspan=3 style="font-weight:bold;font-size:9pt"><B>( '.$XXNAMA2.' )</B> </td>
				<td >&nbsp;</td> 
				</tr>
				<tr> 
				<td >&nbsp;</td> 
				<td align=center colspan=2 style="font-weight:bold;font-size:9pt"><B>NIP. '.$XXNIP1.'</B> </td>
				<td colspan=10 >&nbsp;</td> 
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