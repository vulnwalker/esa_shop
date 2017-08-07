<?php
/*
2010.09.18:
	- menu penghapusan hanya menampilkan daftar yg telah dihapus ->
		- link ditambah SPg=01,langsung ke halaman input data
		
*/

//include('/pages/base_main_menubarbawah.php');

$MyModulKU = explode(".",$HTTP_COOKIE_VARS["coModul"]);
$disModul01 = $MyModulKU[0] == "0" ?"disabled":"";
$disModul02 = $MyModulKU[1] == "0" ?"disabled":"";
$disModul03 = $MyModulKU[2] == "0" ?"disabled":"";
$disModul04 = $MyModulKU[3] == "0" ?"disabled":"";
$disModul05 = $MyModulKU[4] == "0" ?"disabled":"";
$disModul06 = $MyModulKU[5] == "0" ?"disabled":"";
$disModul07 = $MyModulKU[6] == "0" ?"disabled":"";
$disModul08 = $MyModulKU[7] == "0" ?"disabled":"";
$disModul09 = $MyModulKU[8] == "0" ?"disabled":"";
$disModul10 = $MyModulKU[9] == "0" ?"disabled":"";
$disModul11 = $MyModulKU[10] == "0" ?"disabled":"";
$disModul12 = $MyModulKU[11] == "0" ?"disabled":"";
$disModul13 = $MyModulKU[12] == "0" ?"disabled":"";
$disModulref = $MyModulKU[13] == "0" ?"disabled":"";
$disModuladm = $MyModulKU[14] == "0" ?"disabled":"";

$ridModul01 = $MyModulKU[0] == "2" ?"ReadONLY":"";
$ridModul02 = $MyModulKU[1] == "2" ?"ReadONLY":"";
$ridModul03 = $MyModulKU[2] == "2" ?"ReadONLY":"";
$ridModul04 = $MyModulKU[3] == "2" ?"ReadONLY":"";
$ridModul05 = $MyModulKU[4] == "2" ?"ReadONLY":"";
$ridModul06 = $MyModulKU[5] == "2" ?"ReadONLY":"";
$ridModul07 = $MyModulKU[6] == "2" ?"ReadONLY":"";
$ridModul08 = $MyModulKU[7] == "2" ?"ReadONLY":"";
$ridModul09 = $MyModulKU[8] == "2" ?"ReadONLY":"";
$ridModul10 = $MyModulKU[9] == "2" ?"ReadONLY":"";
$ridModul11 = $MyModulKU[10] == "2" ?"ReadONLY":"";
$ridModul12 = $MyModulKU[11] == "2" ?"ReadONLY":"";
$ridModul13 = $MyModulKU[12] == "2" ?"ReadONLY":"";
$ridModulref = $MyModulKU[13] == "2" ?"ReadONLY":"";
$ridModuladm = $MyModulKU[14] == "2" ?"ReadONLY":"";
//echo "=$disModul06 =$ridModul06";
$chatPage='?Pg=Menu&SPg=01';//?Pg=Admin&SPg=04';
//$chatPage = '';
$chat_menu = 
		"<a id='chat_alert' 
			style='background: no-repeat url(images/administrator/images/message_24_off.png);	
								width:24;height:24; display: inline-block;'
									
								 target='_blank' href='$chatPage' title='Chat' > 											
		</a>
		<a href='".$chatPage."' target='_blank' style='color: white;' title='Daftar Pengguna Online'>".JmlOnLine()." user online</a>  
		";	

/*
$chat_menu = 
		"		
		<a href='' target='_blank' style='color: white;' title='Daftar Pengguna Online'>".JmlOnLine()." user online</a>  
		";	
*/
$mnpeta = $Main->MODUL_PETA ? "<a target='_blank' href='pages.php?Pg=map&SPg=03' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/tumbs/gmaps_icon_32.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Peta Sebaran'><b>PETA SEBARAN</a> |":"";
$mnchart= $Main->MODUL_CHART ? "<a target='_blank' href='chart.php' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/chart4_32.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Chart'><b>Chart</a> |":"";

if($Main->PP27_GANTIRUGI){
	$vgantirugi = "<a$disModul12 href=\"pages.php?Pg=gantirugi\">";
}else{
	$vgantirugi = "<a$disModul12 href=\"?Pg=12\">";
}

if($Main->PP27_PENILAIAN){
	$btpenilaian = 
		"<a$disModul08 href=\"pages.php?Pg=Penilaian_koreksi\">
		<img src=\"images/index_122.gif\" alt=\"\" name=\"bt_penilaian\" width=\"97\" height=\"90\" border=\"0\" 
		id=\"bt_penilaian\" onMouseOver=\"MM_swapImage('bt_penilaian','','images/index2_122.gif',1)\" 
		onMouseOut=\"MM_swapImgRestore()\"></a>";
}else{
	$btpenilaian = 
		"<a$disModul08 href=\"pages.php?Pg=Penilaian\">
		<img src=\"images/index_122.gif\" alt=\"\" name=\"bt_penilaian\" width=\"97\" height=\"90\" border=\"0\" 
		id=\"bt_penilaian\" onMouseOver=\"MM_swapImage('bt_penilaian','','images/index2_122.gif',1)\" 
		onMouseOut=\"MM_swapImgRestore()\"></a>";
}		

if($Main->PP27_PEMINDAHTANGAN){
	$btpindahtangan = 
		"<a$disModul10 href=\"pages.php?Pg=pemindahtangan\">
		<img src=\"images/index_67.gif\" alt=\"\" name=\"bt_pemindahtanganan\" width=\"133\" height=\"77\" border=\"0\" 
		id=\"bt_pemindahtanganan\" onMouseOver=\"MM_swapImage('bt_pemindahtanganan','','images/index2_67.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a>"	;
}else{
	$btpindahtangan = 
		"<a$disModul10 href=\"?Pg=10&bentuk=&SSPg=\">
		<img src=\"images/index_67.gif\" alt=\"\" name=\"bt_pemindahtanganan\" width=\"133\" height=\"77\" border=\"0\" 
		id=\"bt_pemindahtanganan\" onMouseOver=\"MM_swapImage('bt_pemindahtanganan','','images/index2_67.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a>"	;
}

if($Main->VERSI_NAME=='SERANG'){
	
	$btpenerimaan =		//penggunaaan
		//"<a$disModul03 href=\"?Pg=04\">
		"<a$disModul03 href=\"pages.php?Pg=Penggunaan\">
			<img src=\"images/index_37_srg.png\" alt=\"\" name=\"bt_penerimaan\" width=\"155\" height=\"102\" border=\"0\" id=\"bt_penerimaan\" onMouseOver=\"MM_swapImage('bt_penerimaan','','images/index2_37_srg.png',1)\" onMouseOut=\"MM_swapImgRestore()\">
		</a>";
	$btpenggunaan = //pemusnahan
		"<a$disModul04 href=\"pages.php?Pg=pemusnahan\">
			<img src=\"images/index_71_srg.png\" alt=\"\" name=\"bt_penggunaan\" width=\"100\" height=\"77\" border=\"0\" id=\"bt_penggunaan\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images/index2_71_srg.png',1)\" onMouseOut=\"MM_swapImgRestore()\">
		</a>";
		
	/*$btpenerimaan =		//penggunaaan
		//"<a$disModul03 href=\"?Pg=04\">
		"<a$disModul03 href=\"pages.php?Pg=Penggunaan\">
			<img src=\"images/index_37_srg.png\" alt=\"\" name=\"bt_penerimaan\" width=\"155\" height=\"102\" border=\"0\" id=\"bt_penerimaan\" >
		</a>";
	$btpenggunaan = //pemusnahan
		"<a$disModul04 href=\"pages.php?Pg=pemusnahan\">
			<img src=\"images/index_71_srg.png\" alt=\"\" name=\"bt_penggunaan\" width=\"100\" height=\"77\" border=\"0\" id=\"bt_penggunaan\" \">
		</a>";
*/
	
	
}else{
	$btpenerimaan = 
		"<a$disModul03 href=\"pages.php?Pg=penerimaan\">
			<img src=\"images/index_37.gif\" alt=\"\" name=\"bt_penerimaan\" width=\"155\" height=\"102\" border=\"0\" id=\"bt_penerimaan\" onMouseOver=\"MM_swapImage('bt_penerimaan','','images/index2_37.gif',1)\" onMouseOut=\"MM_swapImgRestore()\">
		</a>";
	$btpenggunaan = 
		"<a$disModul04 href=\"?Pg=04\">
			<img src=\"images/index_71.gif\" alt=\"\" name=\"bt_penggunaan\" width=\"100\" height=\"77\" border=\"0\" id=\"bt_penggunaan\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images/index2_71.gif',1)\" onMouseOut=\"MM_swapImgRestore()\">
		</a>";

	
}


if($Main->MENU_VERSI == 2){//mantap

  $Main->Base = "
  
  <html>
<head>
<!--<title>:: MANTAP</title>-->
<title><!--JUDUL--> {$HTTP_COOKIE_VARS['coNama']}</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<style type=\"text/css\">
body {
	background-color: #496887;
	background-image: url(images2/index_01.jpg);
	background-repeat: no-repeat;
	background-position:center;
	background-position:top;
}
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;
	color: #FFFFFF;
}
</style>
<script type=\"text/javascript\">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf(\"#\")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
</head>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"MM_preloadImages('images2/index2_07.png','images2/index2_09.png','images2/index2_11.png','images2/index2_13.png','images2/index2_15.png','images2/index_17.png','images2/index2_19.png','images2/index2_29.png','images2/index2_31.png','images2/index2_33.png','images2/index2_35.png','images2/index2_37.png','images2/index2_39.png','images2/index2_17.png','images2/bt_adm2.png','images2/bt_peta sebaran2.png','images2/bt_chart2.png','images2/bt_master data2.png')\">
<!-- Save for Web Slices (menu mantap_slice latar.psd) -->
<table width=\"1300\" height=\"739\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"Table_01\">
	<tr>
		<td align=\"center\" valign=\"top\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		  <tr>
		    <td align=\"center\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		      <tr>
		        <td width=\"158\">&nbsp;</td>
		        <td width=\"313\"><img src=\"images2/index_02.png\" width=\"313\" height=\"171\"></td>
		        <td width=\"363\">&nbsp;</td>
		        <td width=\"339\" align=\"right\"><img src=\"images2/index_04.png\" width=\"399\" height=\"171\"></td>
		        <td width=\"67\">&nbsp;</td>
	          </tr>
	        </table></td>
	      </tr>
		  <tr>
		    <td align=\"center\">
				<p>				
				<a$disModul01 href=\"index.php?Pg=perencanaan_v2\" 
					onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_perencanaan_v2','','images2/index2_07.png',1)\">
					<img src=\"images2/index_07.png\" width=\"145\" height=\"179\" id=\"bt_perencanaan_v2\">
				</a>
				
				<!--				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>								
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a$disModul02 href=\"pages.php?Pg=pemasukan\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
					<img src=\"images2/index_09.png\" width=\"145\" height=\"179\" id=\"bt_pengadaan\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>
				&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a$disModul04 href=\"?Pg=04\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
					<img src=\"images2/index_11.png\" width=\"145\" height=\"179\" id=\"bt_penggunaan\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>				
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a$disModul06 href=\"?Pg=06\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pemanfaatan','','images2/index2_13.png',1)\">
					<img src=\"images2/index_13.png\" width=\"145\" height=\"179\" id=\"bt_pemanfaatan\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;				
				<a$disModul11 href=\"?Pg=07\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengamanan','','images2/index2_15.png',1)\">
					<img src=\"images2/index_15.png\" width=\"145\" height=\"179\" id=\"bt_pengamanan\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;	
				<a$disModul08 href=\"pages.php?Pg=Penilaian\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penilaian','','images2/index2_17.png',1)\">
					<img src=\"images2/index_17.png\" width=\"145\" height=\"179\" id=\"bt_penilaian\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
				</a>&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
				</a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;	
				<a$disModul10 href=\"?Pg=10&bentuk=&SSPg=\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pemindahtanganan','','images2/index2_19.png',1)\">
					<img src=\"images2/index_19.png\" width=\"145\" height=\"179\" id=\"bt_pemindahtanganan\">
				</a>
			</p>
	        <p>
			
			<a href=\"pages.php?Pg=pemusnahanba\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pemusnahan','','images2/index2_29.png',1)\">
				<img src=\"images2/index_29.png\" width=\"145\" height=\"179\" id=\"bt_pemusnahan\">
			</a>
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
			</a>&nbsp;&nbsp;&nbsp;&nbsp;			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>
			-->
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=\"index.php?Pg=09&SPg=01&SSPg=03&mutasi=1\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_mutasi','','images2/index2_31.png',1)\">
				<img src=\"images2/index_31.png\" width=\"145\" height=\"179\" id=\"bt_mutasi\">
			</a>
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
			</a>&nbsp;&nbsp;&nbsp;&nbsp;			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>
			-->
			
			&nbsp;&nbsp;&nbsp;&nbsp;	
			<a$disModul12 href=\"pages.php?Pg=gantirugi\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images2/index2_33.png',1)\">
				<img src=\"images2/index_33.png\" width=\"145\" height=\"179\" id=\"bt_tuntutan\">
			</a>
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
			</a>&nbsp;&nbsp;&nbsp;&nbsp;			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>
			-->
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a$disModul09 href=\"?Pg=09&SPg=01\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penghapusan','','images2/index2_35.png',1)\">
				<img src=\"images2/index_35.png\" width=\"145\" height=\"179\" id=\"bt_penghapusan\">
			</a>
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
			</a>&nbsp;&nbsp;&nbsp;&nbsp;			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>
			-->
			
			&nbsp;&nbsp;&nbsp;&nbsp;	
			<a$disModul05 href=\"?Pg=05\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penatausahaan','','images2/index2_37.png',1)\">
				<img src=\"images2/index_37.png\" width=\"145\" height=\"179\" id=\"bt_penatausahaan\">
			</a>
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\">
			</a>&nbsp;&nbsp;&nbsp;&nbsp;			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\">
			</a>
			-->
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a$disModul13 href=\"?Pg=13\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pembinaan','','images2/index2_39.png',1)\">
				<img src=\"images2/index_39.png\" width=\"145\" height=\"179\" id=\"bt_pembinaan\">
			</a>
			</p>
		
		</td>
	      </tr>
		  <tr>
		    <td align=\"center\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		      <tr>
		        <td width=\"42\">&nbsp;</td>
		        <td width=\"200\">
				<a$disModuladm href=\"?Pg=Admin\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_adm','','images2/bt_adm2.png',1)\">
					<img src=\"images2/bt_adm1.png\" width=\"80\" height=\"80\" id=\"bt_adm\">
				</a>
				
				<!--
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_mutasi','','images2/index2_31.png',1)\"></a>
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>
				<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\"></a>
				-->
				
				&nbsp;&nbsp;&nbsp;&nbsp;				
				<a href=\"pages.php?Pg=map&SPg=03\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_peta','','images2/bt_peta sebaran2.png',1)\">
					<img src=\"images2/bt_peta sebaran1.png\" width=\"80\" height=\"80\" id=\"bt_peta\">
				</a>
				
				</td>
		        <td width=\"944\">&nbsp;</td>
		        <td width=\"200\" align=\"right\">
					<a href=\"chart.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_chart','','images2/bt_chart2.png',1)\">
						<img src=\"images2/bt_chart1.png\" width=\"80\" height=\"80\" id=\"bt_chart\">
					</a>
					<!--
					<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_adm','','images2/bt_adm2.png',1)\"></a>
					<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_mutasi','','images2/index2_31.png',1)\"></a>
					<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>
					<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images2/index2_09.png',1)\"></a>
					
					
					-->
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a$disModulref href=\"?Pg=ref\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_master data','','images2/bt_master data2.png',1)\">
						<img src=\"images2/bt_master data1.png\" width=\"80\" height=\"80\" id=\"bt_master data\">
					</a>
				</td>
		        <td width=\"42\">&nbsp;</td>
	          </tr>
	        </table></td>
	      </tr>
	    </table></td>
	</tr>
	<tr>
		<td height=\"35\" align=\"center\" valign=\"middle\" bgcolor=\"#201736\">
			
			<a href='viewer.php?Pg=banding' target='_blank' style='color: white; text-decoration: none;'>
				<img src=\"images2/migrasi.png\" width=\"22\" height=\"22\"> MIGRASI
			</a>
			&nbsp;&nbsp;&nbsp;
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images2/index2_33.png',1)\"></a>
			-->
			
			<a href='viewer.php?Pg=cari' target='_blank' style='color: white; text-decoration: none;' >
				<img src=\"images2/history.png\" width=\"22\" height=\"22\"> HISTORY
			</a>
			&nbsp;&nbsp;&nbsp;
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>			
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images2/index2_33.png',1)\"></a>
			-->
			
			<a href=\"index.php?Pg=Menu&SPg=01\" target='_blank' style='color: white; text-decoration: none;'>
				<img src=\"images2/user online.png\" width=\"22\" height=\"22\"> ".JmlOnLine()." USER ONLINE
			</a>
			&nbsp;&nbsp;&nbsp;
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images2/index2_33.png',1)\"></a>
			-->
			
			<a href='pages.php?Pg=userprofil' target='_blank' style='color: white; text-decoration: none;'>
				<img src=\"images2/profil.png\" width=\"22\" height=\"22\"> PROFIL
			</a>
			&nbsp;&nbsp;&nbsp;
			
			<!--
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images2/index2_11.png',1)\"></a>
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images2/index2_33.png',1)\"></a>
			-->
			
			<a href='index.php?Pg=LogOut' target='_blank' style='color: white; text-decoration: none;'>
				<img src=\"images2/logout.png\" width=\"22\" height=\"22\"> LOGOUT
			</a>	
		</td>
	</tr>
	<tr>
		<td height=\"24\" bgcolor=\"#302948\"><marquee scrollamount=3>MANTAP (Mobile Asset Management Aplication) Kota Bandung | Dinas Pengelolaan Keuangan dan Aset Daerah Kota Bandung</marquee></td>
	</tr>
</table>
<!-- End Save for Web Slices -->
</body>
</html>
  
  ";


}
else if($Main->MENU_VERSI == 1){//PP27
//if($Main->PP27_MAINMENU){
$Main->Base = 
"<html>
<head>
<title>:: ATISISBADA ::</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<style type=\"text/css\">
body {
	background-color: #021126;
}
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
}
</style>
<script type=\"text/javascript\">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf(\"#\")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
</head>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" 
	onLoad=\"MM_preloadImages('images_pp27/index2_96.gif','images_pp27/index2_108.gif','images_pp27/index2_13.gif','images_pp27/index2_54.gif','images_pp27/index2_103.gif','images_pp27/index2_99.gif','images_pp27/index2_82.gif','images_pp27/index2_67.gif','images_pp27/index2_50.gif','images_pp27/index2_32.gif','images_pp27/index2_23.gif','images_pp27/index2_19.gif','images_pp27/index2_18.gif','images_pp27/index2_24.gif','images_pp27/index2_39.gif','images_pp27/index2_45.gif','images_pp27/index2_73.gif','images_pp27/index2_86.gif','images_pp27/index2_92.gif')\">
<!-- Save for Web Slices (index.psd) -->
<table width=\"1025\" height=\"701\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"Table_01\">
	<tr>
		<td>
			<img src=\"images_pp27/index_01.jpg\" width=\"40\" height=\"31\" alt=\"\"></td>
		<td colspan=\"9\">
			<img src=\"images_pp27/index_02.jpg\" width=\"296\" height=\"31\" alt=\"\"></td>
		<td colspan=\"13\">
			<img src=\"images_pp27/index_03.jpg\" width=\"352\" height=\"31\" alt=\"\"></td>
		<td colspan=\"10\">
			<img src=\"images_pp27/index_04.jpg\" width=\"290\" height=\"31\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/index_05.jpg\" width=\"46\" height=\"31\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"31\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"5\">
			<img src=\"images_pp27/index_06.jpg\" width=\"40\" height=\"131\" alt=\"\"></td>
		<td colspan=\"9\" rowspan=\"5\">
			<img src=\"images_pp27/atisisbada_07.gif\" width=\"296\" height=\"131\" alt=\"\"></td>
		<td colspan=\"13\">
			<img src=\"images_pp27/index_08.jpg\" width=\"352\" height=\"72\" alt=\"\"></td>
		<td colspan=\"10\" rowspan=\"2\">
			<img src=\"images_pp27/index_09.jpg\" width=\"290\" height=\"81\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_10.jpg\" width=\"46\" height=\"81\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"72\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"2\" rowspan=\"4\">
			<img src=\"images_pp27/index_11.jpg\" width=\"61\" height=\"59\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_12.jpg\" width=\"77\" height=\"28\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"5\">
			<a$disModul01 href=\"index.php?Pg=perencanaan_v2\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_perencanaan_v2','','images_pp27/index2_13.gif',1)\">
				<img src=\"images_pp27/index_13.jpg\" width=\"92\" height=\"79\" id=\"bt_perencanaan_v2\">
			</a>
		</td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_14.jpg\" width=\"75\" height=\"23\" alt=\"\"></td>
		<td colspan=\"2\" rowspan=\"4\">
			<img src=\"images_pp27/index_15.jpg\" width=\"47\" height=\"59\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"9\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"10\" rowspan=\"3\">
			<img src=\"images_pp27/index_16.jpg\" width=\"290\" height=\"50\" alt=\"\"></td>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_17.jpg\" width=\"46\" height=\"50\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"14\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"4\">			
			
			<a$disModul02 href=\"pages.php?Pg=pemasukan\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images_pp27/index2_18.gif',1)\">
			<img src=\"images_pp27/index_18.jpg\" width=\"75\" height=\"62\" id=\"bt_pengadaan\"></a></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"5\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"4\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_16','','images_pp27/index2_19.gif',1)\"><img src=\"images_pp27/index_19.jpg\" width=\"77\" height=\"74\" id=\"bt_16\"></a></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"31\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_20.jpg\" width=\"40\" height=\"75\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"4\">
			<img src=\"images_pp27/index_21.jpg\" width=\"244\" height=\"75\" alt=\"\"></td>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_22.jpg\" width=\"32\" height=\"75\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"4\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_15','','images_pp27/index2_23.gif',1)\"><img src=\"images_pp27/index_23.jpg\" width=\"81\" height=\"75\" id=\"bt_15\"></a></td>
		<td colspan=\"6\" rowspan=\"4\">			
			<a$disModul04 href=\"?Pg=04\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images_pp27/index2_24.gif',1)\">
			<img src=\"images_pp27/index_24.jpg\" width=\"73\" height=\"75\" id=\"bt_penggunaan\"></a></td>
		<td colspan=\"6\" rowspan=\"5\">
			<img src=\"images_pp27/index_25.jpg\" width=\"264\" height=\"87\" alt=\"\"></td>
		<td rowspan=\"5\">
			<img src=\"images_pp27/index_26.jpg\" width=\"46\" height=\"87\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"20\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_27.jpg\" width=\"92\" height=\"55\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"6\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_28.jpg\" width=\"75\" height=\"49\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"17\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\">
			<img src=\"images_pp27/index_29.jpg\" width=\"77\" height=\"32\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"32\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_30.jpg\" width=\"40\" height=\"98\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"4\">
			<img src=\"images_pp27/index_31.jpg\" width=\"244\" height=\"98\" alt=\"\"></td>
		<td colspan=\"4\" rowspan=\"2\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_14','','images_pp27/index2_32.gif',1)\"><img src=\"images_pp27/index_32.jpg\" width=\"52\" height=\"76\" id=\"bt_14\"></a></td>
		<td colspan=\"2\" rowspan=\"4\">
			<img src=\"images_pp27/index_33.jpg\" width=\"61\" height=\"98\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"4\">
			<img src=\"images_pp27/index_34.jpg\" width=\"77\" height=\"98\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"4\">
			<img src=\"images_pp27/index_35.jpg\" width=\"92\" height=\"98\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"4\">
			<img src=\"images_pp27/index_36.jpg\" width=\"75\" height=\"98\" alt=\"\"></td>
		<td colspan=\"6\">
			<img src=\"images_pp27/index_37.jpg\" width=\"73\" height=\"12\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"12\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_38.jpg\" width=\"46\" height=\"86\" alt=\"\"></td>
		<td colspan=\"7\">
			<a$disModul06 href=\"?Pg=06\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pemanfaatan','','images_pp27/index2_39.gif',1)\">
			<img src=\"images_pp27/index_39.jpg\" width=\"80\" height=\"64\" id=\"bt_pemanfaatan\"></a></td>
		<td colspan=\"4\" rowspan=\"2\">
			<img src=\"images_pp27/index_40.jpg\" width=\"211\" height=\"83\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_41.jpg\" width=\"46\" height=\"83\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"64\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"4\" rowspan=\"2\">
			<img src=\"images_pp27/index_42.jpg\" width=\"52\" height=\"22\" alt=\"\"></td>
		<td colspan=\"7\">
			<img src=\"images_pp27/index_43.jpg\" width=\"80\" height=\"19\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"19\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_44.jpg\" width=\"18\" height=\"76\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"3\">
			<a$disModul11 href=\"?Pg=11\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_pengamanan','','images_pp27/index2_45.gif',1)\">
				<img src=\"images_pp27/index_45.jpg\" width=\"82\" height=\"76\" id=\"bt_pengamanan\">
			</a></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_46.jpg\" width=\"191\" height=\"76\" alt=\"\"></td>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_47.jpg\" width=\"46\" height=\"76\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"3\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_48.jpg\" width=\"40\" height=\"88\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_49.jpg\" width=\"227\" height=\"88\" alt=\"\"></td>
		<td colspan=\"5\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_13','','images_pp27/index2_50.gif',1)\"><img src=\"images_pp27/index_50.jpg\" width=\"57\" height=\"68\" id=\"bt_13\"></a></td>
		<td>
			<img src=\"images_pp27/index_51.jpg\" width=\"12\" height=\"68\" alt=\"\"></td>
		<td colspan=\"2\" rowspan=\"3\">
			<img src=\"images_pp27/index_52.jpg\" width=\"61\" height=\"88\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_53.jpg\" width=\"77\" height=\"88\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_17','','images_pp27/index2_54.gif',1)\"><img src=\"images_pp27/index_54.jpg\" width=\"92\" height=\"88\" id=\"bt_17\"></a></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_55.jpg\" width=\"75\" height=\"73\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_56.jpg\" width=\"46\" height=\"73\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"68\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"6\" rowspan=\"2\">
			<img src=\"images_pp27/index_57.jpg\" width=\"69\" height=\"20\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"5\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_58.jpg\" width=\"75\" height=\"29\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_59.jpg\" width=\"46\" height=\"29\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_60.jpg\" width=\"18\" height=\"29\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"2\">
			<img src=\"images_pp27/index_61.jpg\" width=\"82\" height=\"29\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_62.jpg\" width=\"191\" height=\"29\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_63.jpg\" width=\"46\" height=\"29\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"15\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_64.jpg\" width=\"40\" height=\"83\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_65.jpg\" width=\"227\" height=\"83\" alt=\"\"></td>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_66.jpg\" width=\"14\" height=\"83\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"2\"><a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_12','','images_pp27/index2_67.gif',1)\"><img src=\"images_pp27/index_67.jpg\" width=\"55\" height=\"72\" id=\"bt_12\"></a></td>
		<td colspan=\"2\" rowspan=\"3\">
			<img src=\"images_pp27/index_68.jpg\" width=\"61\" height=\"83\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_69.jpg\" width=\"77\" height=\"83\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_70.jpg\" width=\"92\" height=\"83\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"14\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_71.jpg\" width=\"75\" height=\"69\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_72.jpg\" width=\"55\" height=\"69\" alt=\"\"></td>
		<td colspan=\"4\">
			<a$disModul08 href=\"pages.php?Pg=Penilaian\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_penilaian','','images_pp27/index2_73.gif',1)\">
				<img src=\"images_pp27/index_73.jpg\" width=\"59\" height=\"58\" id=\"bt_penilaian\">
			</a>
		</td>
		<td colspan=\"2\" rowspan=\"2\">
			<img src=\"images_pp27/index_74.jpg\" width=\"32\" height=\"69\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_75.jpg\" width=\"191\" height=\"69\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_76.jpg\" width=\"46\" height=\"69\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"58\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"5\">
			<img src=\"images_pp27/index_77.jpg\" width=\"55\" height=\"11\" alt=\"\"></td>
		<td colspan=\"4\">
			<img src=\"images_pp27/index_78.jpg\" width=\"59\" height=\"11\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"11\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_79.jpg\" width=\"40\" height=\"54\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_80.jpg\" width=\"227\" height=\"54\" alt=\"\"></td>
		<td colspan=\"4\" rowspan=\"2\">
			<img src=\"images_pp27/index_81.jpg\" width=\"50\" height=\"54\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"2\"><a$disModul05 href=\"?Pg=05\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_11','','images_pp27/index2_82.gif',1)\"><img src=\"images_pp27/index_82.jpg\" width=\"87\" height=\"54\" id=\"bt_11\"></a></td>
		<td colspan=\"2\" rowspan=\"2\">
			<img src=\"images_pp27/index_83.jpg\" width=\"70\" height=\"54\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<img src=\"images_pp27/index_84.jpg\" width=\"92\" height=\"54\" alt=\"\"></td>
		<td colspan=\"3\">
			<img src=\"images_pp27/index_85.jpg\" width=\"75\" height=\"50\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"3\">
			<a$disModul10 href=\"?Pg=10\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_07','','images_pp27/index2_86.gif',1)\">
			<img src=\"images_pp27/index_86.jpg\" width=\"69\" height=\"67\" id=\"bt_07\"></a></td>
		<td colspan=\"2\" rowspan=\"3\">
			<img src=\"images_pp27/index_87.jpg\" width=\"45\" height=\"67\" alt=\"\"></td>
		<td colspan=\"2\" rowspan=\"3\">
			<img src=\"images_pp27/index_88.jpg\" width=\"32\" height=\"67\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"3\">
			<img src=\"images_pp27/index_89.jpg\" width=\"191\" height=\"67\" alt=\"\"></td>
		<td rowspan=\"3\">
			<img src=\"images_pp27/index_90.jpg\" width=\"46\" height=\"67\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"50\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_91.jpg\" width=\"10\" height=\"71\" alt=\"\"></td>
		<td rowspan=\"4\">
			<a$disModul12 href=\"?Pg=12\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_08','','images_pp27/index2_92.gif',1)\"><img src=\"images_pp27/index_92.jpg\" width=\"60\" height=\"71\" id=\"bt_08\"></a></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_93.jpg\" width=\"5\" height=\"17\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"4\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_94.jpg\" width=\"40\" height=\"59\" alt=\"\"></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_95.jpg\" width=\"57\" height=\"59\" alt=\"\"></td>
		<td rowspan=\"5\">
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_adm','','images_pp27/index2_96.gif',1)\"><img src=\"images_pp27/index_96.jpg\" width=\"72\" height=\"91\" id=\"bt_adm\"></a></td>
		<td rowspan=\"2\">
			<img src=\"images_pp27/index_97.jpg\" width=\"98\" height=\"59\" alt=\"\"></td>
		<td colspan=\"7\" rowspan=\"2\">
			<img src=\"images_pp27/index_98.jpg\" width=\"121\" height=\"59\" alt=\"\"></td>
		<td colspan=\"3\" rowspan=\"2\">
			<a$disModul09 href=\"?Pg=09&SPg=01\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_10','','images_pp27/index2_99.gif',1)\">
			<img src=\"images_pp27/index_99.jpg\" width=\"76\" height=\"59\" id=\"bt_10\"></a></td>
		<td colspan=\"2\" rowspan=\"2\">
			<img src=\"images_pp27/index_100.jpg\" width=\"18\" height=\"59\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/index_101.jpg\" width=\"75\" height=\"13\" alt=\"\"></td>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_102.jpg\" width=\"9\" height=\"75\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"13\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"3\">
			<a href=\"#\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_09','','images_pp27/index2_103.gif',1)\"><img src=\"images_pp27/index_103.jpg\" width=\"75\" height=\"62\" id=\"bt_09\"></a></td>
		<td colspan=\"6\" rowspan=\"5\">
			<img src=\"images_pp27/index_104.jpg\" width=\"74\" height=\"100\" alt=\"\"></td>
		<td colspan=\"2\" rowspan=\"5\">
			<img src=\"images_pp27/index_105.jpg\" width=\"45\" height=\"100\" alt=\"\"></td>
		<td colspan=\"2\" rowspan=\"5\">
			<img src=\"images_pp27/index_106.jpg\" width=\"32\" height=\"100\" alt=\"\"></td>
		<td rowspan=\"5\">
			<img src=\"images_pp27/index_107.jpg\" width=\"63\" height=\"100\" alt=\"\"></td>
		<td rowspan=\"4\">
			<a$disModulref href=\"?Pg=ref\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('bt_masterdata','','images_pp27/index2_108.gif',1)\"><img src=\"images_pp27/index_108.jpg\" width=\"83\" height=\"78\" id=\"bt_masterdata\"></a></td>
		<td rowspan=\"5\">
			<img src=\"images_pp27/index_109.jpg\" width=\"45\" height=\"100\" alt=\"\"></td>
		<td rowspan=\"5\">
			<img src=\"images_pp27/index_110.jpg\" width=\"46\" height=\"100\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"46\" alt=\"\"></td>
	</tr>
	<tr>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_111.jpg\" width=\"40\" height=\"54\" alt=\"\"></td>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_112.jpg\" width=\"57\" height=\"54\" alt=\"\"></td>
		<td rowspan=\"4\">
			<img src=\"images_pp27/index_113.jpg\" width=\"98\" height=\"54\" alt=\"\"></td>
		<td colspan=\"7\" rowspan=\"4\">
			<img src=\"images_pp27/index_114.jpg\" width=\"121\" height=\"54\" alt=\"\"></td>
		<td colspan=\"5\" rowspan=\"4\">
			<img src=\"images_pp27/index_115.jpg\" width=\"94\" height=\"54\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"8\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"2\" rowspan=\"3\">
			<img src=\"images_pp27/index_116.jpg\" width=\"70\" height=\"46\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"8\" alt=\"\"></td>
	</tr>
	<tr>
		<td colspan=\"2\" rowspan=\"2\">
			<img src=\"images_pp27/index_117.jpg\" width=\"84\" height=\"38\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"16\" alt=\"\"></td>
	</tr>
	<tr>
		<td>
			<img src=\"images_pp27/index_118.jpg\" width=\"72\" height=\"22\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/index_119.jpg\" width=\"83\" height=\"22\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"22\" alt=\"\"></td>
	</tr>
	<tr>
		<td height=\"27\" colspan=\"34\" bgcolor=\"#031D42\" align=\"center\"><img src=\"images_pp27/user.png\" alt=\"\" width=\"10\" height=\"10\"> User Online | <img src=\"images_pp27/bar.png\" width=\"10\" height=\"10\"> Grafik | <img src=\"images_pp27/lock.png\" alt=\"\" width=\"10\" height=\"10\"> Ganti Password | <img src=\"images_pp27/book.png\" alt=\"\" width=\"10\" height=\"10\"> Buku Petunjuk | <img src=\"images_pp27/logout.png\" alt=\"\" width=\"10\" height=\"10\"> Logout</td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"27\" alt=\"\"></td>
	</tr>
	<tr>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"40\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"57\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"72\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"98\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"14\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"3\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"32\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"7\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"12\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"52\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"9\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"7\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"60\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"10\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"8\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"75\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"9\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"10\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"60\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"5\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"46\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"1\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"8\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"9\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"5\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"4\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"41\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"12\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"20\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"63\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"83\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"45\" height=\"1\" alt=\"\"></td>
		<td>
			<img src=\"images_pp27/spacer.gif\" width=\"46\" height=\"1\" alt=\"\"></td>
		<td></td>
	</tr>
</table>
<!-- End Save for Web Slices -->
</body>
</html>
";

	
}

else{
	


$Main->Base = "
<html>
<head>
<title><!--JUDUL--> {$HTTP_COOKIE_VARS['coNama']}</title>

<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta name='format-detection' content='telephone=no' />
<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW' />
$Main->HeadStyleico
<!--<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />-->
	<link rel=\"stylesheet\" href=\"css/theme.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"dialog/dialog.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"lib/chatx/chatx.css\" type=\"text/css\" />
<!--	<link rel=\"stylesheet\" href='css/syslog.css' type=\"text/css\" />-->

	
	<script language=\"JavaScript\" src=\"lib/js/JSCookMenu_mini.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"lib/js/ThemeOffice/theme.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"lib/js/joomla.javascript.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"js/ajaxc2.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"dialog/dialog.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"js/base.js\" type=\"text/javascript\"></script>
	<script language=\"JavaScript\" src=\"lib/chatx/chatx.js\" type=\"text/javascript\"></script>
	

<style type=\"text/css\">
<link rel='icon' type='image/png' href='images/penatausahaan_ico.gif' />


.body {
	background-image: url(images/motif.gif);
}
.text {
	font-family: verdana;
	font-size: 11px;
	color: #FFFFFF;
	text-decoration: none;
}

</style>
<script type=\"text/javascript\">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf(\"#\")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" 
	onLoad=\"cek_notify('".$HTTP_COOKIE_VARS['coID']."');MM_preloadImages('images/index2_19.gif','images/index2_30.gif','images/index2_31.gif','images/index2_37.gif','images/index2_45.gif','images/index2_49.gif','images/index2_67.gif','images/index2_71.gif','images/index2_97.gif','images/index2_104.gif','images/index2_122.gif','images/index2_124.gif','images/index2_133.gif','images/index2_137.gif','images/index2_140.gif')\">
<!-- ImageReady Slices (menu utama.psd) -->
<table width=\"1024\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#FFFFFF\" style='position:relative;top:-30;'>
  <tr>
    <td><table width=\"1025\" height=\"701\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"Table_01\">
      <tr>
        <td><img src=\"images/index_01.gif\" width=\"40\" height=\"31\" alt=\"\"></td>
        <td colspan=\"6\"><img src=\"images/index_02.gif\" width=\"283\" height=\"31\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_03.gif\" width=\"108\" height=\"31\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_04.gif\" width=\"162\" height=\"31\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_05.gif\" width=\"95\" height=\"31\" alt=\"\"></td>
        <td colspan=\"6\"><img src=\"images/index_06.gif\" width=\"290\" height=\"31\" alt=\"\"></td>
        <td><img src=\"images/index_07.gif\" width=\"46\" height=\"31\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"31\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"3\"><img src=\"images/index_08.gif\" width=\"40\" height=\"131\" alt=\"\"></td>
        <td colspan=\"6\" rowspan=\"3\"><img src=\"images/index_09.gif\" width=\"283\" height=\"131\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"2\"><img src=\"images/index_10.gif\" width=\"108\" height=\"102\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_11.gif\" width=\"162\" height=\"102\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_12.gif\" width=\"95\" height=\"81\" alt=\"\"></td>
        <td colspan=\"6\"><img src=\"images/".$Main->Image13."\" width=\"290\" height=\"81\" alt=\"\"></td>
        <td><img src=\"images/".$Main->Image14."\" width=\"46\" height=\"81\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"81\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"4\"><img src=\"images/index_15.gif\" width=\"95\" height=\"21\" alt=\"\"></td>
        <td colspan=\"6\"><img src=\"images/index_16.gif\" width=\"290\" height=\"21\" alt=\"\"></td>
        <td><img src=\"images/index_17.gif\" width=\"46\" height=\"21\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"21\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"4\"><img src=\"images/index_18.gif\" width=\"108\" height=\"29\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\">
			<a$disModul01 href=\"index.php?Pg=perencanaan_v2\">
			<img src=\"images/index_19.gif\" alt=\"\" name=\"bt_perencanaan_v2\" width=\"162\" height=\"78\" 
			border=\"0\" id=\"bt_perencanaan_v2\" onMouseOver=\"MM_swapImage('bt_perencanaan_v2','','images/index2_19.gif',1)\" 
			onMouseOut=\"MM_swapImgRestore()\">
			</a>
		</td>
        <td colspan=\"4\"><img src=\"images/index_20.gif\" width=\"95\" height=\"29\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_21.gif\" width=\"105\" height=\"29\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_22.gif\" width=\"185\" height=\"29\" alt=\"\"></td>
        <td><img src=\"images/index_23.gif\" width=\"46\" height=\"29\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"29\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"3\"><img src=\"images/index_24.gif\" width=\"40\" height=\"87\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_25.gif\" width=\"39\" height=\"87\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_26.gif\" width=\"104\" height=\"87\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_27.gif\" width=\"40\" height=\"87\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_28.gif\" width=\"39\" height=\"87\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"3\"><img src=\"images/index_29.gif\" width=\"61\" height=\"87\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"3\">
			$vgantirugi
			<img src=\"images/index_30.gif\" alt=\"\" name=\"bt_tuntutan\" width=\"108\" height=\"87\" border=\"0\" id=\"bt_tuntutan\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images/index2_30.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a>
		</td>
        <td colspan=\"4\" rowspan=\"2\"><a$disModul02 href=\"pages.php?Pg=pemasukan\">
			<img src=\"images/index_31.gif\" alt=\"\" name=\"bt_pengadaan\" width=\"95\" height=\"76\" border=\"0\" id=\"bt_pengadaan\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images/index2_31.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_32.gif\" width=\"105\" height=\"76\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_33.gif\" width=\"185\" height=\"76\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_34.gif\" width=\"46\" height=\"76\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"49\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_35.gif\" width=\"162\" height=\"38\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"27\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/index_36.gif\" width=\"45\" height=\"11\" alt=\"\"></td>
        <td colspan=\"6\" rowspan=\"4\">
			$btpenerimaan
		</td>
        <td colspan=\"3\" rowspan=\"4\"><img src=\"images/index_38.gif\" width=\"185\" height=\"102\" alt=\"\"></td>
        <td rowspan=\"4\"><img src=\"images/index_39.gif\" width=\"46\" height=\"102\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"11\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_40.gif\" width=\"40\" height=\"78\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_41.gif\" width=\"39\" height=\"78\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_42.gif\" width=\"104\" height=\"78\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_43.gif\" width=\"40\" height=\"78\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_44.gif\" width=\"39\" height=\"78\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><a$disModul11 href=\"?Pg=11\"><img src=\"images/index_45.gif\" alt=\"\" name=\"bt_pembiayaan\" width=\"94\" height=\"78\" border=\"0\" id=\"bt_pembiayaan\" onMouseOver=\"MM_swapImage('bt_pembiayaan','','images/index2_45.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_46.gif\" width=\"75\" height=\"78\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_47.gif\" width=\"162\" height=\"70\" alt=\"\"></td>
        <td><img src=\"images/index_48.gif\" width=\"45\" height=\"70\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"70\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"3\" rowspan=\"5\"><a$disModul13 href=\"pages.php?Pg=t_pegawai\"><img src=\"images/index_49.gif\" alt=\"\" name=\"bt_pembinaan\" width=\"162\" height=\"100\" border=\"0\" id=\"bt_pembinaan\" onMouseOver=\"MM_swapImage('bt_pembinaan','','images/index2_49.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td rowspan=\"2\"><img src=\"images/index_50.gif\" width=\"45\" height=\"21\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"8\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_51.gif\" width=\"40\" height=\"27\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_52.gif\" width=\"39\" height=\"27\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_53.gif\" width=\"104\" height=\"27\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_54.gif\" width=\"40\" height=\"27\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_55.gif\" width=\"39\" height=\"27\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_56.gif\" width=\"94\" height=\"27\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_57.gif\" width=\"75\" height=\"27\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"13\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/index_58.gif\" width=\"45\" height=\"14\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_59.gif\" width=\"55\" height=\"14\" alt=\"\"></td>
        <td colspan=\"2\"><img src=\"images/index_60.gif\" width=\"100\" height=\"14\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_61.gif\" width=\"185\" height=\"14\" alt=\"\"></td>
        <td><img src=\"images/index_62.gif\" width=\"46\" height=\"14\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"14\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"3\"><img src=\"images/index_63.gif\" width=\"40\" height=\"77\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_64.gif\" width=\"39\" height=\"77\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_65.gif\" width=\"104\" height=\"77\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_66.gif\" width=\"40\" height=\"77\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"3\">
			$btpindahtangan
		</td>
        <td colspan=\"3\" rowspan=\"3\"><img src=\"images/index_68.gif\" width=\"75\" height=\"77\" alt=\"\"></td>
        <td><img src=\"images/index_69.gif\" width=\"45\" height=\"16\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_70.gif\" width=\"55\" height=\"16\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"3\">
			$btpenggunaan
		</td>
        <td colspan=\"3\" rowspan=\"3\"><img src=\"images/index_72.gif\" width=\"185\" height=\"77\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_73.gif\" width=\"46\" height=\"77\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"16\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_74.gif\" width=\"45\" height=\"61\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"2\"><img src=\"images/index_75.gif\" width=\"55\" height=\"61\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"49\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_76.gif\" width=\"27\" height=\"37\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_77.gif\" width=\"106\" height=\"37\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_78.gif\" width=\"29\" height=\"37\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"12\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/index_79.gif\" width=\"40\" height=\"25\" alt=\"\"></td>
        <td><img src=\"images/index_80.gif\" width=\"39\" height=\"25\" alt=\"\"></td>
        <td><img src=\"images/index_81.gif\" width=\"104\" height=\"25\" alt=\"\"></td>
        <td><img src=\"images/index_82.gif\" width=\"40\" height=\"25\" alt=\"\"></td>
        <td colspan=\"2\"><img src=\"images/index_83.gif\" width=\"46\" height=\"25\" alt=\"\"></td>
        <td colspan=\"2\"><img src=\"images/index_84.gif\" width=\"87\" height=\"25\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_85.gif\" width=\"75\" height=\"25\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_86.gif\" width=\"45\" height=\"33\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"2\"><img src=\"images/index_87.gif\" width=\"55\" height=\"33\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_88.gif\" width=\"69\" height=\"33\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_89.gif\" width=\"31\" height=\"33\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_90.gif\" width=\"185\" height=\"33\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_91.gif\" width=\"46\" height=\"33\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"25\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"3\"><img src=\"images/index_92.gif\" width=\"40\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_93.gif\" width=\"39\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_94.gif\" width=\"104\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_95.gif\" width=\"40\" height=\"86\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"3\"><img src=\"images/index_96.gif\" width=\"46\" height=\"86\" alt=\"\"></td>
        <td colspan=\"4\" rowspan=\"3\"><a$disModul09 href=\"?Pg=09&SPg=01\"><img src=\"images/index_97.gif\" alt=\"\" name=\"bt_penghapusan\" width=\"114\" height=\"86\" border=\"0\" id=\"bt_penghapusan\" onMouseOver=\"MM_swapImage('bt_penghapusan','','images/index2_97.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td rowspan=\"3\"><img src=\"images/index_98.gif\" width=\"48\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_99.gif\" width=\"27\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_100.gif\" width=\"106\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"4\"><img src=\"images/index_101.gif\" width=\"29\" height=\"90\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"8\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/index_102.gif\" width=\"45\" height=\"67\" alt=\"\"></td>
        <td><img src=\"images/index_103.gif\" width=\"11\" height=\"67\" alt=\"\"></td>
        <td colspan=\"4\"><a$disModul05 href=\"?Pg=05\"><img src=\"images/index_104.gif\" alt=\"\" name=\"bt_penatausahaan\" width=\"113\" height=\"67\" border=\"0\" id=\"bt_penatausahaan\" onMouseOver=\"MM_swapImage('bt_penatausahaan','','images/index2_104.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td><img src=\"images/index_105.gif\" width=\"31\" height=\"67\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_106.gif\" width=\"185\" height=\"67\" alt=\"\"></td>
        <td><img src=\"images/index_107.gif\" width=\"46\" height=\"67\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"67\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_108.gif\" width=\"45\" height=\"15\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_109.gif\" width=\"11\" height=\"15\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_110.gif\" width=\"28\" height=\"15\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_111.gif\" width=\"85\" height=\"15\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_112.gif\" width=\"31\" height=\"15\" alt=\"\"></td>
        <td colspan=\"3\" rowspan=\"2\"><img src=\"images/index_113.gif\" width=\"185\" height=\"15\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_114.gif\" width=\"46\" height=\"15\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"11\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><img src=\"images/index_115.gif\" width=\"40\" height=\"13\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_116.gif\" width=\"39\" height=\"13\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_117.gif\" width=\"104\" height=\"13\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_118.gif\" width=\"40\" height=\"13\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"2\"><img src=\"images/index_119.gif\" width=\"46\" height=\"13\" alt=\"\"></td>
        <td rowspan=\"2\"><img src=\"images/index_120.gif\" width=\"54\" height=\"13\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"2\"><img src=\"images/index_121.gif\" width=\"38\" height=\"13\" alt=\"\"></td>
        <!--<td colspan=\"3\" rowspan=\"4\"><a$disModul08 href=\"?Pg=08\">
			<img src=\"images/index_122.gif\" alt=\"\" name=\"bt_penilaian\" width=\"97\" height=\"90\" border=\"0\" 
			id=\"bt_penilaian\" onMouseOver=\"MM_swapImage('bt_penilaian','','images/index2_122.gif',1)\" 
			onMouseOut=\"MM_swapImgRestore()\"></a></td> -->
		<td colspan=\"3\" rowspan=\"4\">$btpenilaian
		</td>
        <td rowspan=\"3\"><img src=\"images/index_123.gif\" width=\"106\" height=\"19\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"4\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"4\" rowspan=\"3\"><a$disModul06 href=\"?Pg=06\"><img src=\"images/index_124.gif\" alt=\"\" name=\"bt_pemanfaatan\" width=\"113\" height=\"86\" border=\"0\" id=\"bt_pemanfaatan\" onMouseOver=\"MM_swapImage('bt_pemanfaatan','','images/index2_124.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"3\" rowspan=\"3\"><img src=\"images/index_125.gif\" width=\"85\" height=\"86\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_126.gif\" width=\"31\" height=\"86\" alt=\"\"></td>
        <td><img src=\"images/index_127.gif\" width=\"48\" height=\"9\" alt=\"\"></td>
        <td><img src=\"images/index_128.gif\" width=\"104\" height=\"9\" alt=\"\"></td>
        <td><img src=\"images/index_129.gif\" width=\"33\" height=\"9\" alt=\"\"></td>
        <td><img src=\"images/index_130.gif\" width=\"46\" height=\"9\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"9\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"3\"><img src=\"images/index_131.gif\" width=\"40\" height=\"100\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_132.gif\" width=\"39\" height=\"100\" alt=\"\"></td>
        <td rowspan=\"3\"><a$disModuladm href=\"?Pg=Admin\"><img src=\"images/index_133.gif\" alt=\"\" name=\"bt_administrasi\" width=\"104\" height=\"100\" border=\"0\" id=\"bt_administrasi\" onMouseOver=\"MM_swapImage('bt_administrasi','','images/index2_133.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"4\" rowspan=\"3\"><img src=\"images/index_134.gif\" width=\"140\" height=\"100\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"2\"><img src=\"images/index_135.gif\" width=\"38\" height=\"77\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_136.gif\" width=\"48\" height=\"100\" alt=\"\"></td>
        <td rowspan=\"3\"><a$disModulref href=\"?Pg=ref\"><img src=\"images/index_137.gif\" alt=\"\" name=\"bt_masterData\" width=\"104\" height=\"100\" border=\"0\" id=\"bt_masterData\" onMouseOver=\"MM_swapImage('bt_masterData','','images/index2_137.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td rowspan=\"3\"><img src=\"images/index_138.gif\" width=\"33\" height=\"100\" alt=\"\"></td>
        <td rowspan=\"3\"><img src=\"images/index_139.gif\" width=\"46\" height=\"100\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"6\" alt=\"\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\"><a$disModul07 href=\"?Pg=07\"><img src=\"images/index_140.gif\" alt=\"\" name=\"bt_pengamanan\" width=\"106\" height=\"94\" border=\"0\" id=\"bt_pengamanan\" onMouseOver=\"MM_swapImage('bt_pengamanan','','images/index2_140.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"71\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"2\"><img src=\"images/index_141.gif\" width=\"38\" height=\"23\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_142.gif\" width=\"97\" height=\"23\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_143.gif\" width=\"113\" height=\"23\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_144.gif\" width=\"85\" height=\"23\" alt=\"\"></td>
        <td><img src=\"images/index_145.gif\" width=\"31\" height=\"23\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"23\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/index_146.gif\" width=\"40\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_147.gif\" width=\"39\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_148.gif\" width=\"104\" height=\"18\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_149.gif\" width=\"140\" height=\"18\" alt=\"\"></td>
        <td colspan=\"2\"><img src=\"images/index_150.gif\" width=\"38\" height=\"18\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_151.gif\" width=\"97\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_152.gif\" width=\"106\" height=\"18\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_153.gif\" width=\"113\" height=\"18\" alt=\"\"></td>
        <td colspan=\"3\"><img src=\"images/index_154.gif\" width=\"85\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_155.gif\" width=\"31\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_156.gif\" width=\"48\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_157.gif\" width=\"104\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_158.gif\" width=\"33\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/index_159.gif\" width=\"46\" height=\"18\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"18\" alt=\"\"></td>
      </tr>
      <tr>
         <td colspan=\"25\" bgcolor=\"#021C41\" class=\"text\" width=\"1010\">
		<center>
		<!-- base_main_menubar_bawah-->	
		$mnpeta			
		<!--<a target='_blank' href='viewer.php' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/search_f2.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Pencarian Data'><b>PENCARIAN DATA</a> | -->
		<a target='_blank' href='viewer.php' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/search_f2.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Rekap'><b>REKAP</a> |
		<a target='_blank' href='viewer.php?Pg=cari' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/search_f2.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='History'><b>HISTORY</a> |
		$mnchart				
		
		<a href=\"?Pg=LogOut\" style='color: white;' title='Logout'><B>LOGOUT</B></A> | 
		
		$chat_menu
		
		 | 
		<a target='_blank' href='pages.php?Pg=userprofil' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/help_f2_24.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='User Profile'><b>User Profile</a> 
		</center>
		
		</td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"27\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"25\" bgcolor=\"#021C41\" class=\"text\" width=\"1010\"><marquee scrollamount=\"1\" scrolldelay=\"30\">".
          //copyright &copy; 2008-2010. Biro Pengelolaan Barang Daerah, Sekretariat Daerah Provinsi Jawa Barat. All right reserved.
		 $Main->CopyRight_isi.
        "</marquee></td>
        <td><img src=\"images/spacer.gif\" width=\"1\" height=\"27\" alt=\"\"></td>
      </tr>
      <tr>
        <td><img src=\"images/spacer.gif\" width=\"40\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"39\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"104\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"40\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"39\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"7\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"54\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"33\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"5\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"22\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"48\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"27\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"106\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"29\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"45\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"11\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"28\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"11\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"5\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"69\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"31\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"48\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"104\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"33\" height=\"1\" alt=\"\"></td>
        <td><img src=\"images/spacer.gif\" width=\"46\" height=\"1\" alt=\"\"></td>
        <td></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>";

}

?>