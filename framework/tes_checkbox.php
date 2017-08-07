<?php
/***
//tes checkbox
echo '<form action="" name="frm" method="post">
<input type="checkbox" name="hobby[]" value="coding">  coding &nbsp
<input type="checkbox" name="hobby[]" value="database">  database &nbsp
<input type="checkbox" name="hobby[]" value="software engineer">  soft Engineering <br>
<input type="submit" name="submit" value="submit"> 
</form>';

if(isset($_POST['submit'])){
  $hobby = $_POST['hobby'];	
   foreach ($hobby as $hobys=>$value) {
             echo "Hobby : ".$value."<br />";
        }
}
   
**/

//cerk closing
include('config.php');
//include('common/vars.php');
//include('common/fnfile.php');

function getTglClosing_($c='00', $d='00', $e='00', $e1='000'){
	global $Main, $HTTP_COOKIE_VARS;
	$cek ='';
	$coGroup = $HTTP_COOKIE_VARS['coGroup'];
	if($Main->VERSI_NAME=='KOTA_BANDUNG' && $coGroup =='00.00.00.000' ){
		$get['tgl'] =0;
	}else{
		//if($Main->CLOSING_SKPD){
			$aqry = "select * from t_closing where c='$c' and d='$d' and e='$e' and e1='$e1' order by Id desc limit 0,1 "; $cek .= $aqry;
			$get = mysql_fetch_array(mysql_query($aqry ));
					
			if($get['tgl']== NULL){
				$aqry = "select * from t_closing where c='$c' and d='$d' and e='$e' order by Id desc limit 0,1 "; $cek .= $aqry;
				$get = mysql_fetch_array(mysql_query( $aqry ));		
			}
			if($get['tgl']== NULL){
				$aqry = "select * from t_closing where c='$c' and d='$d' order by Id desc limit 0,1 " ; $cek .= $aqry;
				$get = mysql_fetch_array(mysql_query($aqry));		
			}
			if($get['tgl']== NULL){
				$aqry = "select * from t_closing where c='$c' order by Id desc limit 0,1 "; $cek .= $aqry;
				$get = mysql_fetch_array(mysql_query( $aqry ));		
			}
			if($get['tgl']==NULL){
				$aqry = "select * from t_closing where c='00' order by Id desc limit 0,1 " ; $cek .= $aqry;
				$get = mysql_fetch_array(mysql_query( $aqry));		
			}
			
		//}else{
		//	$get = mysql_fetch_array(mysql_query("select * from t_closing where c='00' and d='00' order by Id desc limit 0,1 " ));
		//}
		
	    if ($get['tgl'] == NULL) $get['tgl'] =0;
	}
	//echo $cek;
	
	return $get['tgl'];
}

$c = $_GET['c']; 
$tgl = $_GET['tgl'];
echo $c .' '.$tgl.'<br>';
$tglclosing =getTglClosing($c);
echo $tglclosing.'<br>';
$cek = sudahClosing($tgl,$c)? 'true':'false';
echo $cek.'<br>';
$hsl =  compareTanggal($tgl, $tglclosing)==0 || compareTanggal($tgl,$tglclosing)==1?'true':'false' ;
echo $hsl.'<br>';

   


?>