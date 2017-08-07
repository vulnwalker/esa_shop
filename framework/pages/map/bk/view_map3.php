<?php



//$Page_Content = "tes";

$KeyAPI = 'ABQIAAAA0v9TR7DzktUixI7HudY_RRRDHi7C3D_AuatlzcvXqKhcM7CorhQ2C9Sr3ywKCMrx4DzEKQP-ogrbjA';

//$Style = 'background-color:black;opacity:0.7';
$Style_backgnd = '';//background-color:#F9F9F9;';
$Style_title = 
	"style='
    height: 22px;
    padding: 4 0 0 4;
    color: blue;
    font-size: 14;
    font-weight: bold;
'";

echo"
<html>
<head>
<title>$Main->Judul</title>
$Main->HeadScript
<script language='JavaScript' src='js/skpd.js' type='text/javascript'></script>
<script type='text/javascript' src='http://www.google.com/jsapi?key=$KeyAPI'></script>
<script language='JavaScript' src='js/gbr.js' type='text/javascript'></script>	
<script language='JavaScript' src='js/map.js' type='text/javascript'></script>
<script language='JavaScript' type='text/javascript'>
	google.load('maps', '2'); 
	function body_onload(){
	}
	$(document).ready(function(){ 			
		Map.showMap();
		Map.pilihKib();		
	})
	
</script>
$Main->HeadStyle
</head>".
//border: solid 1px #E5E5E5;box-shadow: rgba(0, 0, 0, 0.298039) 0px 0px 7px 5px;padding:4 0 4 8;
"<body style='' onload='body_onload()' >".
//"<body style='' onload='body_onload()' onclick='body_onclick()' onresize='body_onresize()'>".
"<div style='position:absolute; left:0;top:0;height:100%;width:100%;' id='map_content'></div>".
"<div id='menu_overlay' style='position:fixed;left:0;width:372;height:100%;

$Style_backgnd
	'>  ".
	"<form name='adminForm' id='adminForm' method='post' action=''>".
	"<div id='menu_content' style='float:left;display:block;width:335;height:100%;
		background-color:#F9F9F9;
		box-shadow: rgba(0, 0, 0, 0.298039) 0px 0px 5px 5px;'> ".
	"<div $Style_title> PILIH DATA</div>".
	//"<table class=\"adminform\">".	
	"<table >".	
	"<tr>		
			<td width=\"100%\" valign=\"top\">" . 				 
				WilSKPD_ajx2('MapSkpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td style=''>".
				//PILIH KIB
				cmb2D_v2('kib', '01',//$kib,  
					array(	//array('','SEMUA '),
						array('01','KIB A TANAH'),
						array('03','KIB C GEDUNG DAN BANGUNAN'),
						array('04','KIB D JALAN, IRIGASI, DAN JARINGAN'),
						array('06','KIB F KONSTRUKSI DALAM PENGERJAAN'),						
					), 
					'style="width:318;"','-- PILIH KIB --',
					"onchange='Map.pilihKib()'").
			"</td></tr>".
			"<tr><td><div id='detail_cont' name='detail_cont'>".
			"</div>".
			"</td></tr>".
			"<tr><td>
			<table width='100%'>
			<tr><td style='width:20'>
				<input type='checkbox'' id='tampilbidang' name='tampilbidang' value=''>
			</td>
			<td>
				Bidang
			</td>
			<td align='right'>
				<input type='button' id='btTampil' value='Tampilkan' onclick='Map.refreshList(true)'>".				
				//"<input type='button' id='bttes' value='clear' onclick='Map.clearMap()'>".
			"</td></tr></table>".
			"</td></tr>".
			"<tr><td>".
				"<div id='div_msg' style='padding:0 0 0 14;' ></div>".	
			"</td></tr>".
	"</table>".
	"</div>".
	"<div style='float:left;display:block;padding: 4 0 0 0;height: 60;background-color:#EB7B01;box-shadow: rgba(0, 0, 0, 0.298039) 5px 7px 5px;'>
	<div class=menuv>
	<ul style='padding-left: 0px;width:26'>
	<li style='margin: 0 0 10 4;'>
		<a href='javascript:Map.menuShowHide()' style='color:yellow;'> <img id='btcolapse' src='images/tumbs/left.png' /></a>
	</li>
	<li>
		<a href='index.php' style='color:yellow;'> <img id='btcolapse' src='images/administrator/images/home_24.png' /></a>
	</li>
	</ul>
	</div>
	</div>".
	"</form>".
	
"</div>".

"</body>
</html>";

?>

