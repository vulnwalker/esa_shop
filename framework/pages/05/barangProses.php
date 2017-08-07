<?php

$fmID 		= cekPOST("fmID",0);
$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN; 
$fmWIL 		= cekPOST("fmWIL"); 
$fmSKPD 	= cekPOST("fmSKPD"); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
$fmUNIT 	= cekPOST("fmUNIT"); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
$fmSUBUNIT 	= cekPOST("fmSUBUNIT");  //echo  '<br> fmSUBUNIT  = '.$fmSUBUNIT;//?
$fmSEKSI 	= cekPOST("fmSEKSI"); //echo  '<br> $fmSEKSI  = '.$fmSEKSI;
//$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
//$cidBI = cekPOST("cidBI");
$Baru 		= cekPOST("Baru","");  //echo"<br>Baru=$Baru"; 
$fmIDLama 	= $_POST['fmIDLama'];
$tgl_buku 	= $_POST['tgl_buku'];


$byId = $_GET['byId'];
if (!empty($byId)){
	//$cidBI[0]=$byId;
	$fmIDLama = $byId;
}
//echo "<br>byId=$byId";
$Info = "";
//$fmIDLama = $cidBI[0]; echo"<br>fmIDLama=$fmIDLama"; 
//$fmIDLama = '407263';
$PrevPageParam = cekPOST("PrevPageParam"); //parameter page pmanggil. untuk kembali ke page pemanggil
$Act=cekPOST("Act"); //echo"<br>act=$Act"; //view, baru, edit, simpan, batal, kosong
//$Act = "Edit";

include("entryidi_lama.php");
//echo '<br> $fmSEKSI after entryidi_lama = '.$fmSEKSI;
$labelbarang = "";

//if (!(($Act =='Simpan')||($Baru==''))){

if( !(($Act =='')&&($Baru=='')) ) {

$old = mysql_fetch_array(mysql_query("select * from buku_induk where id='$fmID'"));
if((sudahClosing($tgl_buku,$fmSKPD,$fmUNIT)) || ($old['id']<>$old['idawal']) || ($old['status_aset']=='2' || $old['staset']=='7')){
	$readonly="readonly";
}	
//get jml transaksi
		$trans = mysql_fetch_array(mysql_query(
			"select count(*) as cnt from t_transaksi where idbi='$fmIDLama' "
		));
		$jmltrans = $trans['cnt'];
		if($jmltrans>1){
			$readonly="readonly";
		}


$optWIL = "<!--wil skpd-->
		<br><table width=\"100%\" class=\"adminform\">	
			
			<!--<tr><td colspan='3' height='40'>
	  		<span style='font-size: 18px;font-weight: bold;color: #C64934;'>Edit </span>
			</td></tr>-->
			<tr>		
			<td width=\"100%\" valign=\"top\">
			".WilSKPD1(140,'1',$readonly )."
			</td>
			<td >
			<!--labelbarang-->				
			".$Main->ListData->labelbarang."	
			</td>
		</tr></table>";
}

if ($Baru=='1'){
$optWIL = "<!--wil skpd-->
		<br><table width=\"100%\" class=\"adminform\">	
			
			<!--<tr><td colspan='3' height='40'>
	  		<span style='font-size: 18px;font-weight: bold;color: #C64934;'>Edit </span>
			</td></tr>-->
			<tr>		
			<td width=\"100%\" valign=\"top\">			
			".WilSKPD1(140,'')."
			</td>
			<td >
			<!--labelbarang-->				
			".$Main->ListData->labelbarang."	
			</td>
		</tr></table>";	
}

$Hidden = "	
	<input type=hidden name='PrevPageParam' value='index.php?Pg=$Pg&SPg=$SPg'>
	<input type=hidden name='Act' value=''>
	<input type=hidden name='Baru' value='$Baru'>
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type='hidden' value='$fmIDLama' id='fmIDLama' name='fmIDLama'>
	
	";



$Main->Isi = 
	"<form action=\"#\" method=\"post\" name=\"adminForm\" id=\"adminForm\" accept-charset=\"ISO-8859-1\">
		$optWIL
		$Main->Entry
		$Hidden
	</form>";


?>