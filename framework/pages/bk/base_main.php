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
						font-size: 11px;' title='Pencarian Data'><b>PETA SEBARAN</a> |":"";

$Main->Base = "
<html>
<head>
<title><!--JUDUL--> {$HTTP_COOKIE_VARS['coNama']}</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta name='format-detection' content='telephone=no' />
<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW' />
<link rel=\"shortcut icon\" href=\"images/logo_bogor_kab.ico\">
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
        <td colspan=\"6\"><img src=\"images/index_13.gif\" width=\"290\" height=\"81\" alt=\"\"></td>
        <td><img src=\"images/index_14.gif\" width=\"46\" height=\"81\" alt=\"\"></td>
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
        <td colspan=\"3\" rowspan=\"2\"><a$disModul01 href=\"?Pg=01\"><img src=\"images/index_19.gif\" alt=\"\" name=\"bt_perencanaan\" width=\"162\" height=\"78\" border=\"0\" id=\"bt_perencanaan\" onMouseOver=\"MM_swapImage('bt_perencanaan','','images/index2_19.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
        <td colspan=\"4\" rowspan=\"3\"><a$disModul12 href=\"?Pg=12\"><img src=\"images/index_30.gif\" alt=\"\" name=\"bt_tuntutan\" width=\"108\" height=\"87\" border=\"0\" id=\"bt_tuntutan\" onMouseOver=\"MM_swapImage('bt_tuntutan','','images/index2_30.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"4\" rowspan=\"2\"><a$disModul02 href=\"?Pg=02\"><img src=\"images/index_31.gif\" alt=\"\" name=\"bt_pengadaan\" width=\"95\" height=\"76\" border=\"0\" id=\"bt_pengadaan\" onMouseOver=\"MM_swapImage('bt_pengadaan','','images/index2_31.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
        <td colspan=\"6\" rowspan=\"4\"><a$disModul03 href=\"?Pg=03\"><img src=\"images/index_37.gif\" alt=\"\" name=\"bt_penerimaan\" width=\"155\" height=\"102\" border=\"0\" id=\"bt_penerimaan\" onMouseOver=\"MM_swapImage('bt_penerimaan','','images/index2_37.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
        <td colspan=\"3\" rowspan=\"5\"><a$disModul13 href=\"?Pg=13\"><img src=\"images/index_49.gif\" alt=\"\" name=\"bt_pembinaan\" width=\"162\" height=\"100\" border=\"0\" id=\"bt_pembinaan\" onMouseOver=\"MM_swapImage('bt_pembinaan','','images/index2_49.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
        <td colspan=\"4\" rowspan=\"3\"><a$disModul10 href=\"?Pg=10\"><img src=\"images/index_67.gif\" alt=\"\" name=\"bt_pemindahtanganan\" width=\"133\" height=\"77\" border=\"0\" id=\"bt_pemindahtanganan\" onMouseOver=\"MM_swapImage('bt_pemindahtanganan','','images/index2_67.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
        <td colspan=\"3\" rowspan=\"3\"><img src=\"images/index_68.gif\" width=\"75\" height=\"77\" alt=\"\"></td>
        <td><img src=\"images/index_69.gif\" width=\"45\" height=\"16\" alt=\"\"></td>
        <td colspan=\"4\"><img src=\"images/index_70.gif\" width=\"55\" height=\"16\" alt=\"\"></td>
        <td colspan=\"2\" rowspan=\"3\"><a$disModul04 href=\"?Pg=04\"><img src=\"images/index_71.gif\" alt=\"\" name=\"bt_penggunaan\" width=\"100\" height=\"77\" border=\"0\" id=\"bt_penggunaan\" onMouseOver=\"MM_swapImage('bt_penggunaan','','images/index2_71.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
        <td colspan=\"3\" rowspan=\"4\"><a$disModul08 href=\"?Pg=08\"><img src=\"images/index_122.gif\" alt=\"\" name=\"bt_penilaian\" width=\"97\" height=\"90\" border=\"0\" id=\"bt_penilaian\" onMouseOver=\"MM_swapImage('bt_penilaian','','images/index2_122.gif',1)\" onMouseOut=\"MM_swapImgRestore()\"></a></td>
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
				
		<a target='_blank' href='viewer.php' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/search_f2.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Pencarian Data'><b>PENCARIAN DATA</a> |
		
		<a href=\"?Pg=LogOut\" style='color: white;' title='Logout'><B>LOGOUT</B></A> | 
		
		$chat_menu
		
		 | 
		<a href='gantiPass.php' style='color: white;' title='Ganti Password'><B>Ganti Password</B></A>
		
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
?>