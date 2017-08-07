<?php
$thn_ = getdate();
$thn = $thn_['year'];


if($Main->DOWNLOAD_MOBILE ){
	$smartMobileLink = 
		"<tr><td align='center' style = 'height:24px'>
			<a target='' href='download.php?file=ATISISBADA_KabSrg.apk&dr=downloads&nm=ATISISBADA.apk' 	
				style='padding:8 8 8 8; color: white; 											
				font-family: tahoma, verdana, arial, sans-serif;
				font-size: 11px;' title='Download ATISISBADA Smart Mobile'><b>Download ATISISBADA Smart Mobile</a>			
		</td></tr>";
	$smartMobileLink2 = 
		
			"<a target='' href='download.php?file=".$Main->APK_FILE."&dr=downloads&nm=ATISISBADA.apk' 	
				style='padding:8 8 8 8; color: white; 											
				font-family: tahoma, verdana, arial, sans-serif;
				font-size: 11px;' title='Download ATISISBADA Smart Mobile'>
					<b>DOWNLOAD MANTAP SMART MOBILE
			</a>			
		";
}

if($Main->MENU_VERSI == 2){
$Main->Base = "	
	<html>
<head>
<title>:: MANTAP</title>
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
text {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;
	color: #666
}
</style>
<script type=\"text/javascript\">
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
</script>
</head>
<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"MM_preloadImages('images2/index_17.png','images2/bt_login2.png')\">
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
			
			
			
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		      <tr>
		        <td width=\"158\">&nbsp;</td>
		        <td width=\"313\">&nbsp;</td>
		        <td width=\"363\">&nbsp;</td>
		        <td width=\"339\" align=\"right\">
				
				<form name=\"login_form\" id=\"login_form\" method=\"post\" action=\"index.php?Pg=LogIn\" >
				<table width=\"361\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		          <tr>
		            <td><img src=\"images2/login_03.png\" width=\"361\" height=\"54\"></td>
		            </tr>
		          <tr>
		            <td height=\"177\" background=\"images2/login_05.png\">
					
					
					
					<table width=\"80%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"6\">
		              <tr>
		                <td width=\"15%\" valign=\"middle\"><p><img src=\"images2/administrator.png\" width=\"22\" height=\"22\"></p></td>
		                <td width=\"85%\" valign=\"middle\"><p>
		                  <input name=\"user\" type=\"text\" id=\"user\" value=\"\" size=\"35\"  border=\"\" placeholder='Username'>
		                </p></td>
		                </tr>
		              <tr>
		                <td valign=\"middle\">&nbsp;</td>
		                <td valign=\"middle\">&nbsp;</td>
		                </tr>
		              <tr>
		                <td valign=\"middle\"><img src=\"images2/lock.png\" width=\"22\" height=\"22\"></td>
		                <td valign=\"middle\"><input name=\"password\" type=\"password\" id=\"password\" 
							value=\"\" size=\"35\"  border=\"\" placeholder='password'></td>
		                </tr>
		              <tr>
		                <td valign=\"middle\">&nbsp;</td>
		                <td valign=\"middle\">&nbsp;</td>
		                </tr>
		              <tr>
		                <td valign=\"middle\"><img src=\"images2/tear_off_calendar.png\" width=\"22\" height=\"22\"> |</td>
		                <td valign=\"middle\"><input name=\"thn\" type=\"text\" id=\"thn\" value=\"$thn\" 
							size=\"35\"  border=\"\"></td>
		                </tr>
		              </table>
					  
					  
					  </td>
		            </tr>
		          <!--
				  <tr>
		            <td width=\"361\">
					
						<a href=\"index.html\" onMouseOut=\"MM_swapImgRestore()\" 
							onMouseOver=\"MM_swapImage('bt_login','','images2/bt_login2.png',1)\">
								<img src=\"images2/bt_login1.png\" width=\"361\" height=\"47\" id=\"bt_login\">
						</a>
					</td>
										
		            </tr>
					-->
					
					
					<tr>
					<td>
						<input type=\"image\" src=\"images2/bt_login1.png\" 
						name=\"bt_ok\" width=\"361\" height=\"47\"  border=\"0\" id=\"bt_ok\" 
						onmouseover=\"MM_swapImage('bt_ok','','images2/bt_login2.png',1)\" onmouseout=\"MM_swapImgRestore()\">
					
					</td>
					</tr>
	            </table>
				</form>
				
				
				</td>
		        <td width=\"67\">&nbsp;</td>
	          </tr>
	        </table>		      <p>&nbsp;</p></td>
	      </tr>
		  <tr>
		    <td align=\"center\">&nbsp;</td>
	      </tr>
	    </table></td>
	</tr>
	<tr>
		<td height=\"35\" align=\"center\" valign=\"middle\" bgcolor=\"#201736\">
		
		<!--DOWNLOAD BUKU PETUNJUK MANTAP | DOWNLOAD MANTAP SMART MOBILE -->
		
		<a target='' href='download.php?file=".$Main->ManualBook."&dr=downloads&nm=".$Main->ManualBook."' 	
						style='padding:8 8 8 8; color: white; 											
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Download Buku Petunjuk'><b>DOWNLOAD BUKU PETUNJUK MANTAP 
			</a>
		| 
		$smartMobileLink2 
		</td>
	</tr>
	<tr>
		<td height=\"24\" bgcolor=\"#302948\">
		<!--
		<marquee scrollamount=3>MANTAP (Mobile Asset Management Aplication) Kota Bandung | 
		Dinas Pengelolaan Keuangan dan Aset Daerah Kota Bandung
		</marquee>
		-->
		
		<marquee scrollamount=\"3\" >".
		  $Main->CopyRight_isi.
		"</marquee>
		
		</td>
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
	<title><!--JUDUL--></title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<meta name='format-detection' content='telephone=no' />
	<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW' />
	$Main->HeadStyleico
	<script type=\"text/javascript\" language=\"JavaScript\" src=\"css/ajax.js\"></script>
	<style type=\"text/css\">
	
	<!--
	body {
		background-image: url(images/motif.gif);
	}
	.text {
		font-family: verdana;
		font-size: 11px;
		color: #FFFFFF;
		text-decoration: none;
	}
	.text1 {font-family: Verdana;
		font-size: 11px;
		color: #FFFFFF;
		text-decoration: none;
	}
	.jdl {font-family: Verdana;
		font-size: 13px;
		color: #666666;
		text-decoration: none;
	}
	-->
	</style>
	<script type='text/javascript'>
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

	function cekEnableJs() {
					if(window.opener) window.opener.close();
					document.getElementById('login').style.display = '';
				}
	//-->
	</script>
	</head>
	<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"MM_preloadImages('images/bt_cancel02.gif')\">
	<!-- ImageReady Slices (Open) -->

	<table width=\"1023\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#FFFFFF\" style='position:relative;top:-30;'>
	  <tr>
		<td width=\"1019\"><table width=\"1024\" height=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"Table_01\">
		  <tr>
			<td><img src=\"images/login_01.gif\" width=\"40\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_02.gif\" width=\"283\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_03.gif\" width=\"108\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_04.gif\" width=\"162\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_05.gif\" width=\"95\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_06.gif\" width=\"290\" height=\"31\" alt=\"\"></td>
			<td><img src=\"images/login_07.gif\" width=\"46\" height=\"31\" alt=\"\"></td>
		  </tr>
		  <tr>
			<td rowspan=\"2\"><img src=\"images/login_08.gif\" width=\"40\" height=\"131\" alt=\"\"></td>
			<td rowspan=\"2\"><img src=\"images/index_09.gif\" width=\"283\" height=\"131\" alt=\"\"></td>
			<td><img src=\"images/login_10.gif\" width=\"108\" height=\"81\" alt=\"\"></td>
			<td><img src=\"images/login_11.gif\" width=\"162\" height=\"81\" alt=\"\"></td>
			<td><img src=\"images/login_12.gif\" width=\"95\" height=\"81\" alt=\"\"></td>
			<td><img src=\"images/".$Main->Image13."\" width=\"290\" height=\"81\" alt=\"\"></td>
			<td><img src=\"images/".$Main->Image14."\" width=\"46\" height=\"81\" alt=\"\"></td>
		  </tr>
		  <tr>
			<td><img src=\"images/login_15.gif\" width=\"108\" height=\"50\" alt=\"\"></td>
			<td><img src=\"images/login_16.gif\" width=\"162\" height=\"50\" alt=\"\"></td>
			<td><img src=\"images/login_17.gif\" width=\"95\" height=\"50\" alt=\"\"></td>
			<td><img src=\"images/login_18.gif\" width=\"290\" height=\"50\" alt=\"\"></td>
			<td><img src=\"images/login_19.gif\" width=\"46\" height=\"50\" alt=\"\"></td>
		  </tr>
		  <tr>
			<td height=\"511\" colspan=\"7\" align=\"center\" valign=\"top\" background=\"images/login_20.gif\"><table width=\"1024\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td><center><font color='#FFFFFF'><!--ISI--></font></center>
				<div id=\"MyResult\" style=\"display:none;\">Please Wait...</div>
				<form name=\"login_form\" id=\"login_form\" method=\"post\" action=\"index.php?Pg=LogIn\" >
				  <table width=\"300\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#FFFFFF\">
					<tr>
					  <td height=\"30\" bgcolor=\"#FFFFFF\" class=\"jdl\">&nbsp;&nbsp;<img src=\"images/bulet.gif\" alt=\"bullet\" width=\"19\" height=\"19\"><B>LOGIN</B></td>
					</tr>
					<tr>
					  <td>
					  <table width=\"340\" border=\"0\" align=\"center\" bordercolor=\"0\">
						  <tr>
						  	<td rowspan=\"5\" width=\"5\"> </td>
							<td height=\"10\" class=\"text1\"></td>
							<td class=\"text1\"></td>
							<td></td>
						  </tr>
						  <tr>
							<td width=\"115\" class=\"text1\">USER NAME</td>
							<td width=\"13\" class=\"text1\"><div align=\"center\">:</div></td>
							<td width=\"138\"><input type=\"text\" name=\"user\" id=\"user\" value=\"\">
							</td>
						  </tr>
						  <tr>
							<td class=\"text1\">PASSWORD</td>
							<td class=\"text1\"><div align=\"center\">:</div></td>
							<td><input type=\"password\" name=\"password\" id=\"password\" value=\"\" AUTOCOMPLETE='off'>
							</td>
						  </tr>
						  
						  <tr>
							<td class=\"text1\">TAHUN ANGGARAN</td>
							<td class=\"text1\"><div align=\"center\">:</div></td>
							<td><input type=\"text\" name=\"thn\" id=\"thn\" value=\"$thn\"></td>
						  </tr>
						 
						  <tr>
							<td class=\"text1\">&nbsp;</td>
							<td class=\"text1\">&nbsp;</td>
							<td><input type=\"image\" src=\"images/bt_ok01.gif\" name=\"bt_ok\" width=\"53\" height=\"23\" border=\"0\" id=\"bt_ok\" onMouseOver=\"MM_swapImage('bt_ok','','images/bt_ok02.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" >
							  <input type=\"image\" src=\"images/bt_cancel01.gif\" alt=\"cancel\" name=\"bt_cancel\" width=\"73\" height=\"23\" border=\"0\" id=\"bt_cancel\" onMouseOver=\"MM_swapImage('bt_cancel','','images/bt_cancel02.gif',1)\" onMouseOut=\"MM_swapImgRestore()\">
							 </td>
						  </tr>
						  <tr>
							<td height=\"5\" class=\"text1\"></td>
							<td class=\"text1\"></td>
							<td></td>
						  </tr>
					  </table></td>
					</tr>
					<tr>
					  <td bgcolor=\"#FFFFFF\">&nbsp;</td>
					</tr>
				  </table>
							  </form>
				  </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>				
			  </tr>
			  <tr><td align='center'>
			  		<!--<a target='_blank' href='viewer.php' 	style='padding:8 8 8 36; color: white; 
						background: no-repeat url(images/administrator/images/search_f2.png);						
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;'><b>PENCARIAN DATA</a>-->
					<a target='' href='download.php?file=".$Main->ManualBook."&dr=downloads&nm=".$Main->ManualBook."' 	
						style='padding:8 8 8 8; color: white; 											
						font-family: tahoma, verdana, arial, sans-serif;
						font-size: 11px;' title='Download Buku Petunjuk'><b>Download Buku Petunjuk</a>
					
				</td></tr>
				$smartMobileLink 
			</table></td>
		  </tr>
		  <tr>
			<td height=\"27\" colspan=\"7\" bgcolor=\"#021C41\" class=\"text\" width=\"1010\">
			<marquee scrollamount=\"1\" scrolldelay=\"30\">
			<div align=\"center\">".
			$Main->CopyRight_isi.
			//"copyright &copy; 2008-2010. Biro Pengelolaan Barang Daerah Sekretariat Daerah Propinsi Jawa Barat. All right reserved.".
			"</div>
			</marquee>
			
			</td>
		  </tr>
		</table></td>
	  </tr>
	</table>
	</body>
	</html>
";
}

?>