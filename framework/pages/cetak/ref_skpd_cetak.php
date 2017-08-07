<?php
set_time_limit(0);

$SDest = isset($HTTP_GET_VARS["SDest"])?$HTTP_GET_VARS["SDest"]:"";

if ($SDest=='XLS')
{
header("Content-Type: application/force-download");
header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
header("Content-Transfer-Encoding: Binary");
header('Content-disposition: attachment; filename="DAFTAR SKPD.xls"');
ob_flush();
flush();
$head='';	
} else {
	$head="<head>
	<title>$Main->Judul</title>
	<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />
</head>
";

}


$Qry = mysql_query("select * from ref_skpd  order by c,d,e,e1 ");
$ListData = "";
$no=0;
$cb=0;
$jmlTampilBRG = 0;

while ($isi=mysql_fetch_array($Qry))
{
	$no++;
	$jmlTampilBRG++;
	$KODESKPD = "{$isi['c']}.{$isi['d']}.{$isi['e']}.{$isi['e1']}";
	$NAMASKPD = $isi["nm_skpd"];
	$ListData .= 			
		"<tr>
				<td><div align='center'>$no.</div></td>
				<td><div align='center'>$KODESKPD</div></td>
				<td><div align='left'>$NAMASKPD</div></td>
		</tr>";
	$cb++;

}


$Main->Isi = $cek."
$head
<body>
<table class=\"rangkacetak\" width='11cm'>
<tr>
<td valign=\"top\">

	<table  border=\"0\" width='100%'>
		<tr>
			<td class=\"judulcetak\" ALIGN='center' colspan=3>
				DAFTAR SKPD <BR><BR>
			</td>
		</tr>
	</table>


<table  border=\"1\" class=\"cetak\" width='100%'>
	<tr>
	<thead>
		<th class=\"th01\"  width=\"5%\">Nomor</th>
		<th class=\"th01\"   width=\"10%\">Kode SKPD</th>
		<th class=\"th01\" width=\"80%\"> Uraian</th>
	</tr>
	</thead>
		$ListData
</table>
</td>
</tr>
</table>
		

</body>

";



?>