<?php

header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

set_time_limit(0);
error_reporting(0);
ob_start("ob_gzhandler");
// include("common/vars.php"); 
include("config.php");

include('headerhtml.php');


if(CekLogin()){


//Param
//global $HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_GET_VARS; 
//$Act = cekPOST($Act); 
$Act 	= $_POST['Act']; 
$passold = $_POST['passold'];
$nama 	= $_POST['nama'];
$uid 	= $_POST['uid'];
$uid2	= $_POST['uid2'];
$pass	= $_POST['pass'];
$pass2	= $_POST['pass2'];


//$cek .= '<br> act='.$Act; //setcookie("coID",'tes');
$User = login_getUser();//$cek = '<br> user old ='.$User;//isset($HTTP_POST_VARS['user'])?$HTTP_POST_VARS['user']:"";
$namel = login_getName();
if (empty($uid)){$uid= $User;}
if (empty($uid2)){$uid2= $User;}
//$cek .= '<br> user='.$User;


//simpan ------------------------------------------------------------------------------
if($Act == "Simpan"){
	
	$errmsg = '';	
	
	if (($errmsg =='') && empty($passold)){	$errmsg = 'Password Lama Belum diisi!';}
	if (($errmsg =='') && strpos($passold, ";")) { $errmsg = 'Password Lama tidak boleh ada ;!';}
	if (($errmsg =='') && empty($nama)){	$errmsg = 'Nama Lengkap Belum diisi!';}
	if (($errmsg =='') && strpos($nama, ";")) { $errmsg = 'Nama Lengkap tidak boleh ada ;!';}
	if (($errmsg =='') && empty($uid)){	$errmsg = 'User Name Belum diisi!';}	
	if (($errmsg =='') && ($uid != $uid2)){	$errmsg = 'User name dan Ulangi tidak sama!';}	
	if (($errmsg =='') && empty($pass)){	$errmsg = 'Password Baru Belum diisi!';}	
	if (($errmsg =='') && ($pass != $pass2)){	$errmsg = 'Password Baru dan ulangi tidak sama!';}
	if (($errmsg =='') && strpos($uid, " ")) { $errmsg = 'User Name tidak boleh ada spasi!';}
	if (($errmsg =='') && strpos($uid, ";")) { $errmsg = 'User Name tidak boleh ada ;!';}
	if (($errmsg =='') && strpos($pass, " ")) { $errmsg = 'Password tidak boleh ada spasi!';}
	if (($errmsg =='') && strpos($pass, ";")) { $errmsg = 'Password tidak boleh ada ;!';}
	/*if ($errmsg =='') {
		if(($pass != '') && ($pass != $pass2)){	$errmsg = 'Password Baru dan ulangi tidak sama!';}	
	}*/
	
	
	
	
	if ($errmsg =='') { 
		//$sqry = 'select * from admin where uid="'.$User.'"'; $cek .= '<br> sqry='.$sqry;
		//$row = mysql_fetch_array(mysql_query($sqry));	 $cek .= '<br> md5pass='.md5($passold).' pass= '.$row['password'];
		//if ($row['password'] != md5($passold) ){			
		
		if (login_cekPasword($User, $passold )==FALSE){
			$errmsg = 'Password lama salah!';
		}
	}
	
	if ($errmsg =='') { 
		if ((login_getUser()!=$uid )&&(login_cekUserBaru($uid)==FALSE)){
			$errmsg = 'User Name sudah ada!';
		}
	}
	
	
	
	if ($errmsg ==''){
		login_simpan($User,$uid, $pass, $nama );
		/*$sqry = 'update admin 
			set uid="'.$uid.'",
			  password="'.md5($pass).'",
			  nama ="'.$namel.'"
			where uid="'.$olduid.'" limit 1';$cek .='<br> sqrysimpan='.$sqry;
		$row = mysql_query($sqry);*/
		//setcookie("coID");
		//setcookie("coID", "", time()-3600);
		//setcookie("coID",$uid);//login_setUserCo($uid;
		//header("index.php?Pg=LogOut");
		//$cek .= '<br> new user ='.get
		//UserLogout();
		//header("Location: index.php?Pg=");
		
		//$Info = "<script>alert('Data Berhasil Disimpan');</script>";
		echo "
			".heading('Edit User ID',47)."
			<div class='centermain' align='center'>
			<div class='main'>			
			<table style='font-size: 12px;padding:4' class='menudottedline' >
			<tr height='21'><td width='90' align='left'><b>User Name Baru </td><td width='10'><b>:</td><td width='100' align='left'> ".$uid."  </td></tr> 
			<tr height='21'><td align='left'><b>Password Baru </td><td><b>:</td><td align='left'> ".$pass." </td></tr>			
			<tr height='21'><td colspan='3' align='center'> <b>Silahkan &nbsp
				<a href='index.php?Pg=LogOut' style='font-size: 14px;'> Logout</a>
			</td></tr>			
			<table>
			
			
			</div></div>";
		//header("Location:index.php?");
	}else{
		$Info = "<script>alert('".$errmsg."');</script>";
		$Act = '';
		//$Info = $errmsg;
		//echo $info;
	}
	
	$cek .= '<br>ermsg='.$errmsg;
}

if ($Act=='') {
	


//Form --------------------------------------------------------------------------------
$sForm= "

<div class='centermain' align='center'>
<div class='main'>

".heading('Edit User ID',47)."

<!--<form name='adminForm' id='adminForm' method='post' action='gantiPass_proses.php'>-->
<form name='adminForm' id='adminForm' method='post' action=''>



<table class ='adminform'>"
.formEntryPass('passold','Password Lama',':','','width=150','width=10','')
.formEntryText('nama','Nama Lengkap Baru',':',$namel,'','','')
.formEntryText('uid','User Name Baru',':',$uid,'','','')
.formEntryText('uid2','Ulangi User Name Baru',':',$uid2,'','','')
.formEntryPass('pass','Password Baru',':','','','','')
.formEntryPass('pass2','Ulangi Password Baru',':','','','','')."
</table>
<br>
<table width=\"100%\" class=\"menudottedline\">
<tr><td>
	<table width=\"50\"><tr>		
		<td>".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."</td>
		<td>".PanelIcon1("index.php","cancel_f2.png","Batal")."</td>	
		</tr>
	</table>
</td></tr>
</table>
<input type='hidden' name='Act' value='".$Act."' />
</form>

</div>
<div>




";
echo $sForm;
}

echo $Info;

}
//echo $cek;
/*

.formEntryText('passold','Password Lama',':','','','','')
.formEntryText('uid','User Name Baru',':','','','','')
.formEntryText('nama','Nama Lengkap Baru',':','','','','')
.formEntryText('pass','Password Baru',':','','','','')
.formEntryText('pass2','Ulangi Password Baru',':','','','','')."
*/
?>