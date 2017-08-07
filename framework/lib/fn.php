<?php 

echo "tes";

Function UserLogin() { 
	global $HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_GET_VARS; 
	$User = isset($HTTP_POST_VARS['user'])?$HTTP_POST_VARS['user']:""; 
	$Pwd = isset($HTTP_POST_VARS['password'])?$HTTP_POST_VARS['password']:""; 
	$Cek1 = mysql_query("select * from admin where uid='$User' and password = md5('$Pwd')"); 
	if(mysql_num_rows($Cek1)) { 
		$isi = mysql_fetch_array($Cek1); 
		$Nama = $isi['nama']; 
		$UserId = $isi['uid']; 
		$Sebagai = $isi['level']=="1"?"Administrator":"Operator"; 
		$Level = $isi['level']; 
		$Grup = explode(".",$isi['group']); 
		$Modul = array( "01"=>"{$isi['modul01']}","02"=>"{$isi['modul02']}", "03"=>"{$isi['modul03']}",
					"04"=>"{$isi['modul04']}", "05"=>"{$isi['modul05']}","06"=>"{$isi['modul06']}", 
					"07"=>"{$isi['modul07']}","08"=>"{$isi['modul08']}", "09"=>"{$isi['modul09']}",
					"10"=>"{$isi['modul10']}", "11"=>"{$isi['modul11']}","12"=>"{$isi['modul12']}", 
					"13"=>"{$isi['modul13']}","ref"=>"{$isi['modulref']}", "adm"=>"{$isi['moduladm']}" ); 
		$MyModul = "{$isi['modul01']}.{$isi['modul02']}.{$isi['modul03']}.{$isi['modul04']}.{$isi['modul05']}.
					{$isi['modul06']}.{$isi['modul07']}.{$isi['modul0 8']}.{$isi['modul09']}.{$isi['modul10']}.
					{$isi['modul11']}.{$isi['modul12']}.{$isi['modul13']}.{$isi['modulref']}.{$isi['moduladm']}";
		$Status = $isi['status']=="1" ? true:false; 
	} else { 
		$Status = false; 
	} 
	if($Status) { 
		setcookie("coID",$User); setcookie("coNama",$Nama); 
		setcookie("coSebagai",$Sebagai); setcookie("coStatus",$Status); 
		setcookie("coLevel",$Level); 
		setcookie("coSKPD",$Grup[0]); 
		setcookie("coUNIT",$Grup[1]); 
		setcookie("coSUBUNIT",$Grup[2]); 
		setcookie("cofmSKPD",$Grup[0]); 
		setcookie("cofmUNIT",$Grup[1]); 
		setcookie("cofmSUBUNIT",$Grup[2]); 
		setcookie("coModul",$MyModul); 
		$OnLine = mysql_query("update admin set online='1' where uid='$User'"); 
		return true; 
	} else { 
		return false; 
	} 
} 
		
Function UserLogout() { 
	global $HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_COOKIE_VARS; 
	$OnLine = mysql_query("update admin set online='0' where uid='{$HTTP_COOKIE_VARS['coID']}'"); 
	setcookie("coID"); 
	setcookie("coNama"); 
	setcookie("coSebagai"); 
	setcookie("coStatus"); 
	setcookie("coSKPD"); setcookie("coUNIT"); 
	setcookie("coSUBUNIT"); setcookie("coModul"); 
	setcookie("coLevel"); 
	return true; 
} 

function JmlOnLine() { 
	$n = 0; 
	$n = mysql_num_rows(mysql_query("select * from admin where online='1'")); 
	return $n; 
} 

Function CekLogin() { 
	global $HTTP_COOKIE_VARS; 
	if (isset($HTTP_COOKIE_VARS['coStatus'])) { 
		if($HTTP_COOKIE_VARS['coStatus']) { return true; } else { return false; } 
	} else { return false; } 
} 

function PanelIcon($Link="",$Image="module.png",$Isi="Isinya",$Rid="") { 
	global $Pg; 
	$RidONLY = ""; 
	$Ret = "
	$Isi 
	"; 
	return $Ret; 
} 

function PanelIcon1($Link="",$Image="save2.png",$Isi="Isinya",$ReadOnly="",$DisAbled="",$Rid=""	) { 
	global $Pg; $RidONLY = ""; 
	if(!Empty($ReadOnly)){$Link="#FORMENTRY";} 
	$Ret = "
  $Isi
"; 
	return $Ret; 
} 

function cekGET($Var="",$Isi="") { global $HTTP_GET_VARS; $Hsl = isset($HTTP_GET_VARS["$Var"]) ? $HTTP_GET_VARS["$Var"] : $Isi; return $Hsl; } function cekPOST($Var="",$Isi="") { global $HTTP_POST_VARS; $Hsl = isset($HTTP_POST_VARS["$Var"]) ? $HTTP_POST_VARS["$Var"] : $Isi; return $Hsl; } function cekPOSTCOOKIE($Var="",$Var1="",$Isi="") { global $HTTP_POST_VARS,$HTTP_COOKIE_VARS; if(isset($HTTP_POST_VARS["$Var"])) { $Hsl = $HTTP_POST_VARS["$Var"]; } elseif(isset($HTTP_COOKIE_VARS["$Var1"])) { $Hsl = $HTTP_COOKIE_VARS["$Var1"]; $Var1; } else { $Hsl = ""; } return $Hsl; } function ambilPOST() { global $HTTP_POST_VARS; foreach($HTTP_POST_VARS as $key=>$value) { global $$key; $a = $value; } } function ProsesCekField($Field) { $ArField = explode(",",$Field); $Status = true; for($i=0;$i< 10 ?"0$i":"$i"; $Sel = $isi==$arrList[$index][0]?" selected ":""; $Input .= "{$arrList[$index][1]}"; } $Input = ""; return $Input; } function cmbQuery($name='txtField',$value='',$query='',$param='',$Atas='Pilih',$vAtas='') { global $Ref; $Input = "$Atas"; $Query = mysql_query($query); while ($Hasil=mysql_fetch_row($Query)) { $Sel = $Hasil[0]==$value?"selected":""; $Input .= "{$Hasil[1]}"; } $Input = ""; return $Input; } function cmbQuery1($name='txtField',$value='',$query='',$param='',$Atas='Pilih',$vAtas='') { global $Ref; $Input = "$Atas"; $Query = mysql_query($query); while ($Hasil=mysql_fetch_array($Query)) { $Sel = $Hasil[0]==$value?"selected":""; $Input .= "{$Hasil[0]}. {$Hasil[1]}"; } $Input = ""; return $Input; } function txtField($name='txtField',$value='',$maxlength='',$size='20',$type='text',$param='') { $value = stripslashes($value); $Input = ""; return $Input; } function InputKalender($NAMA="TGL",$Param="") { global $$NAMA; $VAL = $$NAMA; $d = "
 "; return $d; } function cariInfo($formNYA,$URL,$URLC,$ID="",$NM="",$ReadOnly="",$DisAbled="",$Param="") { global $$ID,$$NM; $VALID = $$ID; $VALNM = $$NM; $NMIFRAME = "iframe$ID"; $in = "   ( atau Klik  untuk mencari data) "; return $in; } function setWilSKPD() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN,$Main,$HTTP_COOKIE_VARS,$HTTP_POST_VARS; $fmKEPEMILIKAN = isset($HTTP_POST_VARS['fmKEPEMILIKAN']) ? $HTTP_POST_VARS['fmKEPEMILIKAN'] : ""; if(empty($fmKEPEMILIKAN)) { if(isset($HTTP_COOKIE_VARS['cofmKEPEMILIKAN'])) { $fmKEPEMILIKAN = $HTTP_COOKIE_VARS['cofmKEPEMILIKAN']; } } if(empty($fmWIL)) { if(isset($HTTP_COOKIE_VARS['cofmWIL'])) { $fmWIL = $HTTP_COOKIE_VARS['cofmWIL']; } } if(empty($fmSKPD)) { if(isset($HTTP_COOKIE_VARS['cofmSKPD'])) { $fmSKPD = $HTTP_COOKIE_VARS['cofmSKPD']; } } if(empty($fmUNIT)) { if(isset($HTTP_COOKIE_VARS['cofmUNIT'])) { $fmUNIT = $HTTP_COOKIE_VARS['cofmUNIT']; } } if(empty($fmSUBUNIT)) { if(isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])) { $fmSUBUNIT = $HTTP_COOKIE_VARS['cofmSUBUNIT']; } } if(empty($fmTAHUNANGGARAN)) { if(isset($HTTP_COOKIE_VARS['cofmTAHUNANGGARAN'])) { $fmTAHUNANGGARAN = $HTTP_COOKIE_VARS['cofmTAHUNANGGARAN']; } } setcookie("cofmKEPEMILIKAN",$fmKEPEMILIKAN); setcookie("cofmWIL",$fmWIL); setcookie("cofmSKPD",$fmSKPD); setcookie("cofmUNIT",$fmUNIT); setcookie("cofmSUBUNIT",$fmSUBUNIT); setcookie("cofmTAHUNANGGARAN",$fmTAHUNANGGARAN); } function WilSKPD() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$Main,$HTTP_COOKIE_VARS,$Pg,$SPg; $disSKPD = ""; $disUNIT = ""; $disSUBUNIT = ""; $KondisiSKPD = ""; $KondisiUNIT = ""; $KondisiSUBUNIT = ""; $PilihSKPD = "--- Pilih SKPD ---"; $PilihUNIT = "--- Semua UNIT ---"; $PilihSUBUNIT = "--- Semua SUB UNIT ---"; if($HTTP_COOKIE_VARS["coSKPD"] !== "00") { $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"]; $HTTP_COOKIE_VARS["cofmSKPD"]=$fmSKPD; $KondisiSKPD = " and c='$fmSKPD'"; $PilihSKPD = ""; } if($HTTP_COOKIE_VARS["coUNIT"] !== "00") { $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"]; $HTTP_COOKIE_VARS["cofmUNIT"]=$fmUNIT; $KondisiUNIT = " and d='$fmUNIT'"; $PilihUNIT = ""; } if($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") { $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"]; $HTTP_COOKIE_VARS["cofmSUBUNIT"]=$fmSUBUNIT; $KondisiSUBUNIT = " and e='$fmSUBUNIT'"; $PilihSUBUNIT = ""; } $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmWIL == $isi['b'] ? "selected":""; $Ops .= "{$isi['nm_wilayah']}\n"; } $ListKab = ""; $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSKPD == $isi['c']? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSKPD = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmUNIT == $isi['d'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListUNIT = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSUBUNIT == $isi['e'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSUBUNIT = ""; $Hsl= "
TAHUN ANGGARAN	:	
PROVINSI	:	{$Main->Provinsi[1]}
KABUPATEN / KOTA	:	$ListKab
SKPD	:	$ListSKPD
UNIT	:	$ListUNIT
SUB UNIT	:	$ListSUBUNIT
"; return $Hsl; } 

function TglInd($Tgl="") { 
	$Tanggal = !empty($Tgl)?substr($Tgl,8,2)."-".substr($Tgl,5,2)."-".substr($Tgl,0,4):""; 
	if ($Tanggal == '00-00-0000'){
		$Tanggal = '';
	}
	return $Tanggal; 
} 

function TglSQL($Tgl="") { $Tanggal = !empty($Tgl)?substr($Tgl,6,4)."-".substr($Tgl,3,2)."-".substr($Tgl,0,2):""; return $Tanggal; } function JuyTgl1($tgl="") { global $Ref; if(!empty($tgl) and substr($tgl,0,4)!="0000") { $cHr = @$Ref->NamaHari[date("w",mktime(0,0,0,substr($tgl,5,2),substr($tgl,8,2),substr($tgl,0,4)))]; return substr($tgl,8,2)." ".@$Ref->NamaBulan[(substr($tgl,5,2)*1)-1]." ".substr($tgl,0,4); } else { return " "; } } function WilSKPD1() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN,$Main,$HTTP_COOKIE_VARS,$Pg,$SPg; $disSKPD = ""; $disUNIT = ""; $disSUBUNIT = ""; $KondisiSKPD = ""; $KondisiUNIT = ""; $KondisiSUBUNIT = ""; $PilihSKPD = "--- Pilih SKPD ---"; $PilihUNIT = "--- Semua UNIT ---"; $PilihSUBUNIT = "--- Semua SUB UNIT ---"; if($HTTP_COOKIE_VARS["coSKPD"] !== "00") { $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"]; $HTTP_COOKIE_VARS["cofmSKPD"]=$fmSKPD; $KondisiSKPD = " and c='$fmSKPD'"; $PilihSKPD = ""; } if($HTTP_COOKIE_VARS["coUNIT"] !== "00") { $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"]; $HTTP_COOKIE_VARS["cofmUNIT"]=$fmUNIT; $KondisiUNIT = " and d='$fmUNIT'"; $PilihUNIT = ""; } if($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") { $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"]; $HTTP_COOKIE_VARS["cofmSUBUNIT"]=$fmSUBUNIT; $KondisiSUBUNIT = " and e='$fmSUBUNIT'"; $PilihSUBUNIT = ""; } $Qry = mysql_query("select * from ref_pemilik order by nm_pemilik"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmKEPEMILIKAN == $isi['a1'] ? "selected":""; $Ops .= "{$isi['nm_pemilik']}\n"; } $ListKepemilikan = ""; $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmWIL == $isi['b'] ? "selected":""; $Ops .= "{$isi['nm_wilayah']}\n"; } $ListKab = ""; $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSKPD == $isi['c'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSKPD = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmUNIT == $isi['d'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListUNIT = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSUBUNIT == $isi['e'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSUBUNIT = ""; $Hsl= "
KEPEMILIKAN BARANG	:	$ListKepemilikan
PROVINSI	:	{$Main->Provinsi[1]}
KABUPATEN / KOTA	:	$ListKab
SKPD	:	$ListSKPD
UNIT	:	$ListUNIT
SUB UNIT	:	$ListSUBUNIT
"; return $Hsl; } function WilSKPD2() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN,$Main,$HTTP_COOKIE_VARS,$Pg,$SPg; $disSKPD = ""; $disUNIT = ""; $disSUBUNIT = ""; $KondisiSKPD = ""; $KondisiUNIT = ""; $KondisiSUBUNIT = ""; $PilihSKPD = "--- Semua SKPD ---"; $PilihUNIT = "--- Semua UNIT ---"; $PilihSUBUNIT = "--- Semua SUB UNIT ---"; if($HTTP_COOKIE_VARS["coSKPD"] !== "00") { $fmSKPD = $HTTP_COOKIE_VARS["coSKPD"]; $HTTP_COOKIE_VARS["cofmSKPD"]=$fmSKPD; $KondisiSKPD = " and c='$fmSKPD'"; $PilihSKPD = ""; } if($HTTP_COOKIE_VARS["coUNIT"] !== "00") { $fmUNIT = $HTTP_COOKIE_VARS["coUNIT"]; $HTTP_COOKIE_VARS["cofmUNIT"]=$fmUNIT; $KondisiUNIT = " and d='$fmUNIT'"; $PilihUNIT = ""; } if($HTTP_COOKIE_VARS["coSUBUNIT"] !== "00") { $fmSUBUNIT = $HTTP_COOKIE_VARS["coSUBUNIT"]; $HTTP_COOKIE_VARS["cofmSUBUNIT"]=$fmSUBUNIT; $KondisiSUBUNIT = " and e='$fmSUBUNIT'"; $PilihSUBUNIT = ""; } $Qry = mysql_query("select * from ref_pemilik order by nm_pemilik"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmKEPEMILIKAN == $isi['a1'] ? "selected":""; $Ops .= "{$isi['nm_pemilik']}\n"; } $ListKepemilikan = ""; $Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmWIL == $isi['b'] ? "selected":""; $Ops .= "{$isi['nm_wilayah']}\n"; } $ListKab = ""; $Qry = mysql_query("select * from ref_skpd where d='00' $KondisiSKPD order by c"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSKPD == $isi['c'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSKPD = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' $KondisiUNIT order by d"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmUNIT == $isi['d'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListUNIT = ""; $Qry = mysql_query("select * from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' $KondisiSUBUNIT order by e"); $Ops = ""; while($isi=mysql_fetch_array($Qry)) { $sel = $fmSUBUNIT == $isi['e'] ? "selected":""; $Ops .= "{$isi['nm_skpd']}\n"; } $ListSUBUNIT = ""; $Hsl= "
KEPEMILIKAN BARANG	:	$ListKepemilikan
PROVINSI	:	{$Main->Provinsi[1]}
KABUPATEN / KOTA	:	$ListKab
SKPD	:	$ListSKPD
UNIT	:	$ListUNIT
SUB UNIT	:	$ListSUBUNIT
"; return $Hsl; } function Halaman($Jumlah=0,$PerHal=0,$NameHal="Hal",$Lokasi="") { global $HTTP_POST_VARS, $Pg, $SPg; $Kos = !empty($Lokasi)?"adminForm.action='$Lokasi';adminForm.target='_self';":"adminForm.action='?Pg=$Pg&SPg=$SPg';adminForm.target='_self';"; $Hal = isset($HTTP_POST_VARS[$NameHal])?$HTTP_POST_VARS[$NameHal]:1; $JmlHal = ceil($Jumlah / $PerHal); $Awal = 1; $Akhir = $JmlHal; $Sebelum = $Hal > 1 ?$Hal - 1:1; $Sesudah = $Hal < $JmlHal ? $Hal + 1 : $JmlHal; $disSebelum = $Hal <= 1 ? " disabled ":""; $disSesudah = $Hal >= $JmlHal ? " disabled ":""; $Str = ""; $Ops = ""; for ($i = 1; $i<=$JmlHal; $i++) { $sel = $i == $Hal ? " selected ":""; $Ops .= "$i"; } $Ops = ""; $Str = "   $Ops   "; return $Str; } function TahunPerolehan() { global $HTTP_POST_VARS,$HTTP_GET_VARS,$fmTahunPerolehan,$Pg,$SPg; $str = ""; $Qry = mysql_query("select thn_perolehan from buku_induk group by thn_perolehan order by thn_perolehan desc"); $ops = "Semua Tahun"; while ($isi = mysql_fetch_array($Qry)) { $sel = $fmTahunPerolehan == $isi['thn_perolehan'] ? "selected":""; $ops .= "{$isi['thn_perolehan']}\n"; } $str = "Tahun Perolehan : "; return $str; } 

function CariCombo($ArField="") { 
	global $fmCariComboField,$fmCariComboIsi,$Pg,$SPg; 
	$str = ""; 
	for($x=0;$x"; return $str; 
} 
	
function PrintSKPD() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN,$Main,$HTTP_COOKIE_VARS; $KEPEMILIKAN = mysql_fetch_array(mysql_query("select nm_pemilik from ref_pemilik where a1='$fmKEPEMILIKAN'"));$KEPEMILIKAN = $KEPEMILIKAN[0]; $WILAYAH = mysql_fetch_array(mysql_query("select nm_wilayah from ref_wilayah where b='$fmWIL' "));$WILAYAH = $WILAYAH[0]; $SKPD = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where d='00' and c='$fmSKPD' "));$SKPD = $SKPD[0]; $UNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='$fmSKPD' and d <> '00' and e = '00' and d='$fmUNIT' "));$UNIT = $UNIT[0]; $SUBUNIT = mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='$fmSKPD' and d = '$fmUNIT' and e <> '00' and e='$fmSUBUNIT' "));$SUBUNIT = $SUBUNIT[0]; $Hsl ="
Provinsi	:	JAWA BARAT
Kabupaten/Kota	:	$WILAYAH
SKPD	:	$SKPD
UNIT	:	$UNIT
SUB UNIT	:	$SUBUNIT
"; return $Hsl; } function PrintTTD() { global $fmWIL,$fmSKPD,$fmUNIT,$fmSUBUNIT,$fmTAHUNANGGARAN,$fmKEPEMILIKAN,$Main,$HTTP_COOKIE_VARS; $NIPSKPD = ""; $NAMASKPD = ""; $JABATANSKPD = ""; $TITIMANGSA = "Bandung, ".JuyTgl1(date("Y-m-d")); if (c=='04') { $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd1 = '1' "); } else { $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '00' and ttd1 = '1' "); } while($isi=mysql_fetch_array($Qry)) { $NIPSKPD1 = $isi['nik']; $NAMASKPD1 = $isi['nm_pejabat']; $JABATANSKPD1 = $isi['jabatan']; } $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' "); while($isi=mysql_fetch_array($Qry)) { $NIPSKPD2 = $isi['nik']; $NAMASKPD2 = $isi['nm_pejabat']; $JABATANSKPD2 = $isi['jabatan']; } $Hsl = "
 	 MENGETAHUI
KEPALA SKPD,






NIP. 	 	
PENGURUS BARANG,






NIP. 	 
"; return $Hsl; } function inputFormatRibuan($obj="obj") { global $$obj; $$obj = round($$obj,0); $str = "       "; return $str; } function ComboBarang() { global $fmBIDANG,$fmKELOMPOK,$fmSUBKELOMPOK,$fmSUBSUBKELOMPOK; $str = ""; $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i ='$fmSUBSUBKELOMPOK' and j != '000'"; $NmHEAD = "NAMA BARANG"; if(!empty($fmBIDANG) and !empty($fmKELOMPOK) and !empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) { $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h ='$fmSUBKELOMPOK' and i !='00' and j = '000'"; $NmHEAD = "NAMA SUB SUB KELOMPOK"; } if(!empty($fmBIDANG) and !empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) { $NmHEAD = "NAMA SUB KELOMPOK"; $Kondisi = "f = '$fmBIDANG' and g = '$fmKELOMPOK' and h !='00' and i ='00' and j = '000'"; } if(!empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) { $Kondisi = "f = '$fmBIDANG' and g != '00' and h ='00' and i ='00' and j = '000'"; $NmHEAD = "NAMA KELOMPOK"; } if(empty($fmBIDANG) and empty($fmKELOMPOK) and empty($fmSUBKELOMPOK) and empty($fmSUBSUBKELOMPOK)) { $Kondisi = "f != '00' and g = '00' and h ='00' and i ='00' and j = '000'"; $NmHEAD = "NAMA BIDANG"; } $ListBidang = cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih',''); $ListKelompok = cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih',''); $ListSubKelompok = cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih',''); $ListSubSubKelompok = cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='000'","onChange=\"adminForm.submit()\"",'Pilih',''); $str ="
GOLONGAN	:	$ListBidang
BIDANG	:	$ListKelompok
KELOMPOK	:	$ListSubKelompok
SUB KELOMPOK	:	$ListSubSubKelompok
"; return $str; }


?>