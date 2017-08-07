<?

$DB_TYPE	="mysql";
$DB_HOST	="localhost";
$DB_USER	="root";
$DB_PWD		="";
$DB_NAME	="db_sislog";

function showHTMLMessage($messageStr,$continueLoad=true,$messageType=MSG_EXCLAMATION){
		switch ($messageType){
			case MSG_EXCLAMATION:
				$img = "images/icons/exclamation.gif";
				break;
			case MSG_INFORMATION:
				$img = "images/icons/information.gif";
				break;
			case MSG_CRITICAL:
				$img = "images/icons/critical.gif";
				break;
		}
		
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\"> \n";
  	echo "	<tr> \n";
    echo "		<td width=\"1%\" valign=\"top\"><img src=\"".$img."\" width=25 height=25></td> \n";
	echo "    <td width=\"99%\" valign=\"bottom\" align=\"left\">";
	echo "			<p><font color='#FF0000' size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
    echo "			<b>$messageStr</b></font></p>";
	echo "		</td> \n";
  	echo "	</tr> \n";
	echo "</table> \n";
	if(!$continueLoad)
		exit;
}
define("TABLE_ROW_ODD_BGCOLOR","#E2F2FC");
define("TABLE_ROW_EVEN_BGCOLOR","#F0F0F0");
define("TABLE_BORDERCOLOR","#CCCCCC");

function getTableRowBGColor($line){
    $temp = $line % 2;    
	if($temp==1)//odd
      return TABLE_ROW_ODD_BGCOLOR;
    else
      return TABLE_ROW_EVEN_BGCOLOR;
 }

function to_number_format($string, $mode) {
    $mode = strtoupper($mode);
	if ($mode == "") $mode = "NULL";
	switch ($mode) {
		case "CUR" :
			return number_format($string, 2, '.', ',');
		break;

		case "QTY" :
			return number_format($string);
		break;

		case "NULL" :
			return number_format($string);
		break;
	}
	
}

function to_date_format($string) {
    $mode = split('/',$string);
	switch($mode[1]) {
	 case '1' : $string = $mode[0]." Januari ".$mode[2]; 
		      break;
	 case '2' : $string = $mode[0]." Februari ".$mode[2]; 
		      break;
     case '3' : $string = $mode[0]." Maret ".$mode[2]; 
		      break;
	 case '4' : $string = $mode[0]." April ".$mode[2]; 
		      break;
     case '5' : $string = $mode[0]." Mei ".$mode[2]; 
		      break;
	 case '6' : $string = $mode[0]." Juni ".$mode[2]; 
		      break;
     case '7' : $string = $mode[0]." Juli ".$mode[2]; 
		      break;
	 case '8' : $string = $mode[0]." Agustus ".$mode[2]; 
		      break;
     case '9' : $string = $mode[0]." September ".$mode[2]; 
		      break;
	 case '10' : $string = $mode[0]." Oktober ".$mode[2]; 
		      break;
     case '11' : $string = $mode[0]." November ".$mode[2]; 
		      break;
	 case '12' : $string = $mode[0]." Desember ".$mode[2]; 
		      break;
	}
	return $string;
	
}
?>
