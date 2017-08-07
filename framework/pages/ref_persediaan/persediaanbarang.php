<?php

class PersediaanBarangObj  extends DaftarObj2{	
	var $Prefix = 'PersediaanBarang';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_hargabarang_persediaan'; //daftar
	var $TblName_Hapus = 'ref_hargabarang_persediaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j','satuan');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'MASTER DATA';
	var $PageIcon = 'images/administrator/images/payment.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'PersediaanBarang_Form'; 
	
	var $jk = array(
			array('L','L'), 
			array('P','P'),
		);

	var $tarif = array(
			array('1','Baru'), 
			array('2','Lama'),
		);
		
	var $status = array(
			array('0','Tidak Tamat'), 
			array('1','Tamat'),
		);	
	
	var $GolDarah = array(
			array('A','A'), 
			array('B','B'),
			array('AB','AB'), 
			array('O','O'),
		);		
		
	var $Kunjungan = array(
			array ('1', 'Rawat Jalan'),
			array ('2', 'Rawat Inap'),
			array ('3', 'IGD'),
		);
		
	var $WaktuKlinik = array(
			array ('0', 'Pagi'),
			array ('1', 'Sore'),
		);	
		
	var $StKawin = array(
			array ('1', 'Kawin'),
			array ('2', 'Belum Kawin'),
			array ('3', 'Janda'),
			array ('4', 'Duda'),
		);
		
	var $Agama = array(
			array ('1', 'Islam'),
			array ('2', 'Kristen'),
			array ('3', 'Hindu'),
			array ('4', 'Budha'),
		);										
			
	function setTitle(){
		return 'DAFTAR BARANG PERSEDIAAN';
	}
	function setMenuEdit(){
		
		return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru KJ",'Kunjungan baru')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru(1,\"\",\"\")","new_f2.png","Baru RJ",'Kunjungan RJ')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru(2,\"\",\"\")","new_f2.png","Baru RI",'Kunjungan RI')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".HargaBarang()","edit_f2.png","HB", 'HB')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".Pindah()","print_f2.png", 'Pindah',"Pindah kunjungan")."</td>".	
			"</td>";
	}
	
	function createEntryTgllahir($Tgl, $elName, $disableEntry='', 
	$ket='tanggal bulan tahun (mis: 1 Januari 1998)', 
	$title='', $fmName = 'adminForm',
	$tglShow=TRUE, $withBtClear = TRUE){
	//global $$elName, 
	//global $Ref;//= 'entryTgl';
	
	$NamaBulan  = array(
	array("01","Januari"), 
	array("02","Pebruari"),
	array("03","Maret"),
	array("04","April"),
	array("05","Mei"),
	array("06","Juni"),
	array("07","Juli"),
	array("08","Agustus"),
	array("09","September"),
	array("10","Oktober"),
	array("11","Nopember"),
	array("12","Desember")
	);
	
	$deftgl = date( 'Y-m-d' ) ;//'2010-05-05';
		
	$tgltmp= explode(' ',$Tgl);//explode(' ',$$elName); //hilangkan jam jika ada
	$stgl = $tgltmp[0]; 
	$tgl = explode('-',$stgl);
	if ($tgl[2]=='00'){ $tgl[2]='';	}
	if ($tgl[1]=='00'){ $tgl[1]='';	}
	if ($tgl[0]=='0000'){ $tgl[0]='';	}
		
	
	$dis='';
	if($disableEntry == '1'){
		$dis = 'disabled';
	}
	
	/*$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">'.$title.'
			<input '.$dis.' type="text" name="'.$elName.'_tgl" id="'.$elName.'_tgl" value="'.$tgl[2].'" size="2" maxlength="2" 
				onkeypress="return isNumberKey(event)"
				onchange="TglEntry_createtgl(\''.$elName.'\')"
				style="width:25">
		</div>' : '';*/
	$entrytgl = $tglShow?
		'<div  style="float:left;padding: 0 4 0 0">' . 
			$title .'&nbsp;'. 			
			//$tgl[2].
			genCombo_tgl(
				$elName.'_tgl',
				$tgl[2],
				'', 
				" $dis ".'  onchange="TglEntry_createtgl(\'' . $elName . '\')"').
		'</div>'
		: '';
	$btClear =  $withBtClear?
		'<div style="float:left;padding: 0 4 0 0">
				<input '.$dis.'  name="'.$elName.'_btClear" id="'.$elName.'_btClear" type="button" value="Clear" 
					onclick="TglEntry_cleartgl(\''.$elName.'\')">
					&nbsp;&nbsp<span style="color:red;">'.$ket.'</span>
		</div>' : '';
		
	if ($tgl[0]==''){
		$thn =(int)date('Y') ;
	}else{
		$thn = $tgl[0];//(int)date('Y') ;
	}
	$thnaw = $thn-10;
	$thnak = $thn+11;
	$opsi = "<option value=''>Tahun</option>";
	for ($i=$thnaw; $i<$thnak; $i++){
		$sel = $i == $tgl[0]? "selected='true'" :'';
		$opsi .= "<option $sel value='$i'>$i</option>";	
	}
	$entry_thn = 
		'<select id="'. $elName  .'_thn" 
			name="' . $elName . '"_thn"	'.
			$dis. 
			' onchange="TglEntry_createtgl(\'' . $elName . '\')"
		>'.
			$opsi.
		'</select>';
	
	$hsl = 
		'<div id="'.$elName.'_content" style="float:left;">'.
			$entrytgl.
			'<div style="float:left;padding: 0 4 0 0">
				'.cmb2D_v2($elName.'_bln', $tgl[1], $NamaBulan, $dis,'Pilih Bulan',
				'onchange="TglEntry_createtgl(\''.$elName.'\')"'  ) .'
			</div>
			<div style="float:left;padding: 0 4 0 0">
				<!--<input '.$dis.' type="text" name="'.$elName.'_thn" id="'.$elName.'_thn" value="'.$tgl[0].'" size="4" maxlength="4" 
					onkeypress="return isNumberKey(event)"
					onchange="TglEntry_createtgl(\''.$elName.'\')"
					style="width:35"	
				>-->'.
				$entry_thn.
			'</div>'.
			
			$btClear.		
			'<input $dis type="hidden" id='.$elName.' name='.$elName.' value="'.$Tgl.'" >
		</div>';
	return $hsl;	
}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 

	 $nama_barang = strtoupper($_REQUEST['nama_barang']);
	 $satuan = $_REQUEST['satuan'];
	 $tahun_anggaran = $_REQUEST['tahun_anggaran'];
	 $harga = $_REQUEST['harga'];	 

	 
			if($fmST == 0){ //input ref_obat
				if($err==''){ 
						 $kode_barang = explode(' ',$_REQUEST['kode_barang']);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];	 	  

							$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga,satuan)
							"."values('$f','$g','$h','$i','$j','$tahun_anggaran','$harga','$satuan')";	$cek .= $aqry1;	
							$qry = mysql_query($aqry1);
				}
			}elseif($fmST == 1){						
				if($err==''){
						 $kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
						 $sat=$kode_barang[5];
						 
							$aqry2 = "UPDATE ref_hargabarang_persediaan
				        	 set "." tahun_anggaran = '$tahun_anggaran',
				        	 harga = '$harga',
							 satuan = '$satuan'". 
						 	"WHERE concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."' and satuan='$sat'";	$cek .= $aqry2;
							$qry = mysql_query($aqry2);

					}
			}else{
			if($err==''){ 
						$kode_barang = explode(' ',$idplh);
						 $f=$kode_barang[0];	
						 $g=$kode_barang[1];
						 $h=$kode_barang[2];	
						 $i=$kode_barang[3];
						 $j=$kode_barang[4];
						$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga)
						"."values('$f','$g','$h','$i','$j','$tahun_anggaran','$harga')";	$cek .= $aqry1;	
						$qry = mysql_query($aqry1);
						 
				}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function batal(){
		global $HTTP_COOKIE_VARS;
	 	global $Main;
	 	if($_COOKIE['coLevel']==1){
			$uid = $HTTP_COOKIE_VARS['coID'];
		}else{
			$uid = $_REQUEST['username'];
		}
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek .=$idplh;
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		$ket_batal = $_REQUEST['ket_batal'];
	
						
	if($err==''){
		//batal kunjungan			
		$bk=mysql_fetch_array(mysql_query("select * from kunjungan where Id = '".$idplh."'"));				
		//if($bk['stbayar']==2){
		//	$err='Maaf data ini sudah dibayar!';	
		//}else{
			if($bk['jns_kunjungan']==2)
				{
				$query = "UPDATE kunjungan
						  SET stbatal = 1,
						  ketbatal = '$ket_batal',
					  	  stkeluar = NULL,
						  tgl_update=now(),
						  uid='$uid'
						  WHERE Id = '".$idplh."' ";
				$cek .=$query;
				$query2 = "UPDATE ref_ttidur
						  SET status = 0
						  WHERE Id = '".$bk['ref_idttidur']."' and c='".$bk['c']."' and d='".$bk['d']."' ";
				$cek .=$query2;
				$query3 = "Delete from kunjungan_ruang where ref_idkunjungan='".$idplh."' and c='".$bk['c']."' and d='".$bk['d']."'";
				$cek .=$query3;
				$idk=mysql_fetch_array(mysql_query("select max(kunjungan.Id) as id_kunjakhir, kunjungan.* from kunjungan where no_rm='".$bk['no_rm']."' and id < '".$idplh."' and stbatal='0'")); $cek .= $aqry;
				if(!empty($idk))
				{
					$query4 = "UPDATE pasien
							  SET Ref_idkunjakhir = NULL
						 	  WHERE no_rm ='".$bk['no_rm']."'";
					$cek .=$query4;
				}
				else	
				{
					$query4 = "UPDATE pasien
						 	 SET Ref_idkunjakhir = '".$idk['id_kunjakhir']."'
						 	 WHERE no_rm ='".$idk['no_rm']."'";
					$cek .=$query4;
				}
				$result =mysql_query($query);	
				$result =mysql_query($query2);	
				$result =mysql_query($query3);	
				$result =mysql_query($query4);	
				}	
			else{
				$query = "UPDATE kunjungan
						  SET stbatal = 1,
						  ketbatal = '$ket_batal',
					  	  stkeluar = NULL,
						  tgl_update=now(),
						  uid='$uid'
						  WHERE Id = '".$idplh."' ";
				$cek .=$query;
				$idk=mysql_fetch_array(mysql_query("select max(kunjungan.Id) as id_kunjakhir, kunjungan.* from kunjungan where no_rm='".$bk['no_rm']."' and id < '".$idplh."' and stbatal='0'")); $cek .= $aqry;
				if(!empty($idk))
				{
					$query2 = "UPDATE pasien
							  SET Ref_idkunjakhir = NULL
						 	 WHERE no_rm ='".$bk['no_rm']."'";
					$cek .=$query2;
				}
				else	
				{
					$query4 = "UPDATE pasien
						 	 SET Ref_idkunjakhir = '".$idk['id_kunjakhir']."'
						 	 WHERE no_rm ='".$idk['no_rm']."'";
					$cek .=$query4;
				}
				$result =mysql_query($query);
				$result =mysql_query($query2);
				}
			//}
		}//end if
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}
	
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaru2':{				
			$fm = $this->setFormBaru2();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formCariBarang':{				
			$fm = $this->setFormCariBarang();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}		
		
		case 'formBatal':{				
			$fm = $this->setFormBatal();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formAdmin':{				
			$fm = $this->setFormAdmin();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}		
			
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
		case 'CekBatal':{
			$cbid = $_REQUEST['Id'];
			/*$uid = $_COOKIE['coID'];*/
			$aqry = "select * from v1_kunjungan where Id = '$cbid'"; $cek .= $aqry;
			$get = mysql_fetch_array( mysql_query($aqry));
			
			if($get['stbatal']==0){
				if($_COOKIE['coLevel']==1){
				
				}elseif($_COOKIE['coLevel']!=1){
					$err="HARAP LOGIN SEBAGAI ADMIN UNTUK BATAL";
				}
			}else{
				$err="DATA SUDAH BATAL!";
			}
			
			$content=$get['stbatal'];
			
		break;
		}
		
		case 'CekAdmin':{
			/*$uid = $_COOKIE['coID'];
			$aqry = "select * from admin where uid = '$uid'"; $cek .= $aqry;
			$get = mysql_fetch_array( mysql_query($aqry));*/
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			$pswd = md5($password);
			
			$aqry = "select * from admin where uid = '$username' and password = '$pswd'"; $cek .= $aqry;
			$get = mysql_fetch_array( mysql_query($aqry));
			
			if($get['level']==1){
			}elseif($get['level']!=1){
				$err="HARAP LOGIN SEBAGAI ADMIN UNTUK BATAL";
			}
			$content=$get['uid'];
		break;
		}			
	   
	   	case 'batal':{
			$get= $this->batal();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
	   break;
		}
	   	   
	   	case 'pilihbarang':{
		//simpan data pemeriksaan detail

		$ref_pilihbarang = $_REQUEST['PersediaanBarang_daftarpilih'];
		 $kode_barang = explode(' ',$ref_pilihbarang);
		 $f=$kode_barang[0];	
		 $g=$kode_barang[1];
		 $h=$kode_barang[2];	
		 $i=$kode_barang[3];
		 $j=$kode_barang[4];

		$get = mysql_fetch_array( mysql_query("select * from ref_barang_persediaan where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'"));

		$content = array('kode_barang'=>$ref_pilihbarang, 'nama_barang'=>$get['nama_barang']);	
		break;
	   }
	   
	   case 'kodebarang':{
		$jenis = $_REQUEST['jenis'];
		if($jenis==1){
			$kode_jenis="ATK";			
		}else{
			$kode_jenis="OBT";
		}
		 $query = "SELECT max(kode_barang) AS last_kb FROM ref_barang_persediaan WHERE kode_barang LIKE '$kode_jenis%'"; $cek .= $query;
		 $hasil = mysql_query($query);
		 $data  = mysql_fetch_array($hasil);
		 $lastKodeBarang = $data['last_kb'];
		 $lastNoUrut = substr($lastKodeBarang, 4, 7); 
		 $nextNoUrut = $lastNoUrut + 1;
		 $kode_barang = $kode_jenis.sprintf('%07s', $nextNoUrut);
		//$kodebarang = substr($kode_barang,-6);
		 $content = array('kode_barang'=>$kode_barang);
				
		break;
	   }	   
	   
	   default:{
			$other = $this->set_selector_other2($tipe);
			$cek = $other['cek'];
			$err = $other['err'];
			$content=$other['content'];
			$json=$other['json'];
	 break;
	 }
	 }//end switch
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script type='text/javascript' src='js/ref_persediaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/ref_persediaan/caribarang.js' language='JavaScript' ></script>".
			  "<script type='text/javascript' src='js/kasir/tagihan.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	function autocomplete_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		$waktu_klinik = $_REQUEST['waktu_klinik'];
		if($waktu_klinik==0){
			$sql = "select Id,nama  from ref_klinik where pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}
		elseif($waktu_klinik==1){
			$sql = "select Id,nama  from ref_klinik where sore = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}
		else{
			$sql = "select Id,nama  from ref_klinik where sore = 1 and pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
	}
	
	function autocomplete_getdataruang(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		//$waktu_klinik = $_REQUEST['waktu_klinik'];
		//if($waktu_klinik==0){
			//$sql = "select Id,nama  from ref_klinik where pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		//}
		//else{
			//$sql = "select concat(c,d) as cd, CONCAT(ruang,' / ',uraian) as nama from v1_ruang where CONCAT(ruang,' / ',uraian) like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
			$sql = "SELECT
					  concat(v1_ruang.c,v1_ruang.d) as cd, concat(v1_ruang.ruang,' ',
					  v1_ruang.uraian) as nama, v1_ruang.menuRuang, v1_ruang.menuKelas, v1_ruang.ruang,
					  v1_ruang.uraian, v1_ruang.d, v1_ruang.c, ref_ttidur.status,
					  ref_ttidur.Id
					FROM
					  v1_ruang LEFT JOIN
					  ref_ttidur ON ref_ttidur.c = v1_ruang.c AND ref_ttidur.d =
					    v1_ruang.d
					WHERE
					  ref_ttidur.status = 0 AND concat(v1_ruang.ruang,
					  v1_ruang.uraian) like '%".$name_startsWith."%'
					GROUP BY
					  v1_ruang.d, v1_ruang.c limit 0,$maxRows ";$cek.=$sql;
		//}
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;
			//if($waktu_klinik==0){ 
				$a_json_row["id"] = $row['cd'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);
			//}else{
				/*$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);*/	
			//} 
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
	}

	function autocomplete_getdatapenjamin(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		//$waktu_klinik = $_REQUEST['waktu_klinik'];
		//if($waktu_klinik==0){
			//$sql = "select Id,nama  from ref_klinik where pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		//}
		//else{
			$sql = "select * from ref_penjamin where nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		//}
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;
			//if($waktu_klinik==0){ 
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);
			//}else{
				/*$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);*/	
			//} 
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
	}

	function autocomplete_getdatadokter(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		$kunjungan = $_REQUEST['kunjungan'];
		$klinik = $_REQUEST['klinik'];
		/*if(empty($klinik))
					{	
					if($kunjungan==3)
						{
							$sql = "select * from ref_dokter where ref_idklinik = '1' and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
						}
					else
						{
							$sql = "select * from ref_dokter where jns_kunjungan = '".$kunjungan."' and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;	
						}
					}*/
		if($kunjungan==1)
					{	
						$sql = "select * from ref_dokter where ref_idklinik = '".$klinik."' and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
					}
		elseif($kunjungan==2)
					{
						$sql = "select * from ref_dokter where jns_dokter = '2' and jns_tetap not in (5) and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;	
					}					
		elseif($kunjungan==3)
					{
						$sql = "select * from ref_dokter where jns_dokter = '1' and jns_tetap not in (5) and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
					}
		elseif(empty($kunjungan) && empty($klinik))
					{
						$sql = "select * from ref_dokter where nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
					
					}
					
		/*if($waktu_klinik==0){
			$sql = "select Id,nama  from ref_klinik where pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}
		elseif($waktu_klinik==1){
			$sql = "select Id,nama  from ref_klinik where sore = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}
		else{
			$sql = "select Id,nama  from ref_klinik where sore = 1 and pagi = 1 and nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		}*/
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'];
				array_push($a_json, $a_json_row);
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
	}
	

	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		//get data 
		$bulan=date('Y-m-')."1";
		$aqry = "select v1_kunjungan.*, concat(c,d) as cd from v1_kunjungan where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['readonly']='';
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4]; 
		$satuan=$kode[5];
		$bulan=date('Y-m-')."1";
		$aqry = "select * from ref_hargabarang_persediaan where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."' and satuan='$satuan'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j; 
		$dt['disabled']='disabled';
		$dt['readonly']='readonly';
		$nb=mysql_fetch_array(mysql_query("select * from ref_barang_persediaan where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'"));
		$dt['nama_barang']=$nb['nama_barang'];
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
 	function setFormHargaBarang(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 2;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4]; 
		$bulan=date('Y-m-')."1";
		$aqry = "select * from ref_barang_persediaan where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j; 
		$dt['readonly']='readonly';
		$fm = $this->setForm2($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormCariBarang(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 2;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4]; 
		$bulan=date('Y-m-')."1";
		$aqry = "select * from ref_barang_persediaan where concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j; 
		$dt['readonly']='readonly';
		$fm = $this->setForm2($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}			
	
	function setFormBaru2(){
		
		$cbid = $_REQUEST['Pasien'.'_cb'];
		$cek =$dt['tgl_kunjungan'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 3;
		//get data 
		//$bulan=date('Y-m-')."1";
		$aqry = "select * from pasien where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		if($dt['status_pasien']==1){
				$fm['err']="Pasien Tidak Aktif !";
		}else{
		$umur = explode('-',$dt['tgl_lahir']);
			//hitung selisih tgl_lahir
	 		$bedaThn = date('Y')-$umur[0];
	 		$bedaBln = date('m')-$umur[1];
	 		$bedaHr  = date('d')-$umur[2];
     		//pengecekan hari dan bulan jika hasilnya min
			if($bedaBln < 0 && $bedaHr <0)
	 		 	{
		 		$dt['umur_thn']=$bedaThn-1;
		 		$dt['umur_bln']=$bedaBln+12;
		 		$dt['umur_hari']=$bedaHr+30;
		 		}
	 		elseif($bedaBln < 0)
		 		{
		 		$dt['umur_thn']=$bedaThn-1;
		 		$dt['umur_bln']=$bedaBln+12;
		 		$dt['umur_hari']=$bedaHr;	
		 		}	
	 		elseif($bedaHr < 0)
		 		{
		 		$dt['umur_thn']=$bedaThn;
		 		$dt['umur_bln']=$bedaBln-1;
		 		$dt['umur_hari']=$bedaHr+30;	
		 		}
	 		else
		 		{
		 		$dt['umur_thn']=$bedaThn;
		 		$dt['umur_bln']=$bedaBln;
		 		$dt['umur_hari']=$bedaHr;
				}		
		$dt['tgl_kunjungan'] = date("Y-m-d"); //set waktu sekarang
		if(date('H')>=12){
			$dt['stshift']=1;	
		}else{
			$dt['stshift']=0;
		}
		$dt['Baru/Lama']=1;
		$dt['ref_idcarabayar']=1;
		$dt['ref_idrujukan'];
		$fm = $this->setForm($dt);}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
 	function setFormBatal(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data 
		$bulan=date('Y-m-')."1";
		$aqry = "select v1_kunjungan.*, concat(c,d) as cd from v1_kunjungan where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$cb = mysql_fetch_array(mysql_query("select * from kunjungan where ref_idkunjungan='".$dt['Id']."'"));
		if($dt['stbayar']==2){
			$fm['err']='Maaf data ini sudah dibayar!';	
		}elseif($cb==TRUE){
			$fm['err']='Maaf data ini tidak bisa dibatalkan!';
		}else{
			$fm = $this->setForm2($dt);
		}
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormAdmin(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		$dt['sesi'] = gen_table_session('bayar','Id');
		$fm = $this->setForm4($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}			

	/*function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
	 $with_bayar=$_REQUEST['with_bayar'];	
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 800;
	 $this->form_height = 550;
	  if ($this->form_fmST==0) {
	  	if($dt['jns_kunjungan']==3){
			$this->form_caption = 'PENDAFTARAN KUNJUNGAN IGD';
		}else{
			$this->form_caption = 'PENDAFTARAN KUNJUNGAN RI';
		}
		
		$no_rm="<input type='text' name='no_rm' id='no_rm' style='width:200ppx' size=15px onChange=\"".$this->Prefix.".getdata()\">";		
		
		$nip	 = '';
	  }elseif($this->form_fmST==1){
		$this->form_caption = 'Edit';			
		$no_rm="<input type='text' name='no_rm' value='".$dt['no_rm']."' id='no_rm' style='width:200ppx' size=15px onChange=\"".$this->Prefix.".getdata()\" readonly>";			
	  }else{
	  	$this->form_caption = 'PENDAFTARAN KUNJUNGAN';
		$no_rm="<input type='text' name='no_rm' value='".sprintf("%06s", $dt['no_rm'])."' id='no_rm' style='width:200ppx' size=15px onChange=\"".$this->Prefix.".getdata()\" readonly>";			
	   	
	   }
    $queryKota = "select Id, nama from ref_kota";
	$querykecamatan = "select Id, nama from ref_kecamatan";
    $queryPendidikan = "Select Id, nama from ref_pendidikan";
    $queryPekerjaan = "Select Id, nama from ref_pekerjaan";
    $queryKlinik = "select * from ref_klinik";
    $queryRuang = "select concat(c,d), CONCAT(ruang,' / ',uraian) from v1_ruang";
    $queryDokter = "Select Id, nama from ref_dokter";
    $queryCaraBayar = "Select * from ref_cara_bayar";
    $queryPenjamin = "Select Id, nama from ref_penjamin";
    $queryRujukan = "Select Id, nama from ref_rujukan";
	$queryTidur = "SELECT concat(c,d), status from ref_ttidur";
	$queryPelayanan = "select Id,uraian from ref_tarifigd where a=1 ";
	$opsi = "<option value=''>--Tempat tidur--</option>";
	$dokter = "<option value=''>--Dokter--</option>";
	$klinik = "<option value=''>--Klinik--</option>";
	$now=date("Y-m-d H:i:s");
	$jam=substr($now,-8,2);
	$menit=substr($now,-5,2);
	if($dt['jns_kunjungan']==1){
		$jns_kunjungan=cmbArray('kunjungan',$dt['jns_kunjungan'],$this->Kunjungan,'--Kunjungan--','id=kunjungan onChange=\''.$this->Prefix.'.kunjungan()\'');	
	}else{
		$jns_kunjungan=cmbArray('kunjungan_cmb',$dt['jns_kunjungan'],$this->Kunjungan,'--Kunjungan--','id=kunjungan_cmb onChange=\''.$this->Prefix.'.kunjungan()\'').
						 "<input type='hidden' name='kunjungan' id='kunjungan' value='".$dt['jns_kunjungan']."' size=1px>";	
	}


	   //items ----------------------
	  $this->form_fields = array(	
	  	 	'tgl' => array( 
						 'label'=>'Tanggal Masuk',
						 'labelWidth'=>200, 
						 'value'=>createEntryTgl3($dt['tgl_kunjungan'], 'tgl_kunjungan', false,'').
						 "<input type='text' name='jam' id='jam' value='".$jam."' size=1px readonly>&nbsp:&nbsp".
						 "<input type='text' name='menit' id='menit' value='".$menit."' size=1px readonly>"
						 //"<input type='text' name='detik' id='detik' value='".$detik."' size=1px>"
						 ),
			
			'no_rm' => array( 
								'label'=>'No RM',
								'labelWidth'=>200, 
								'value'=>$no_rm
									 ),
									 
			'nama_pasien' => array( 
								'label'=>'Nama Pasien',
								'labelWidth'=>200, 
								'value'=>$dt['nama_pasien'], 
								'type'=>'text',
								'id'=>'nama_pasien',
								'param'=>"style='width:250ppx' size=34px readonly"
									 ),									 
									 
									 			 
			'jk/gd/umur' => array( 
								'label'=>'Jenis Kelamin / Gol. Darah / Umur',
								'labelWidth'=>200, 
								'value'=>cmbArray('jk',$dt['jk'],$this->jk,'--PILIH--','id=jk ').'&nbsp'.'/'.'&nbsp'.
										 cmbArray('darah',$dt['gol_darah'],$this->GolDarah,'--PILIH--','id=darah').'&nbsp'.'/'.'&nbsp'.
										"<input type='text' name='umur_thn' id='umur_thn' value='".$dt['umur_thn']."' size=1px readonly>&nbspTahun&nbsp".
										"<input type='text' name='umur_bln' id='umur_bln' value='".$dt['umur_bln']."' size=1px readonly>&nbspBulan&nbsp".															
										"<input type='text' name='umur_hari' id='umur_hari' value='".$dt['umur_hari']."' size=1px readonly>&nbspHari&nbsp"
									
									 
									 ),
									 
			'ttl' => array( 
								'label'=>'Tempat/Tgl Lahir',
								'labelWidth'=>200, 
								'value'=>"<div id='tgl_lahir_content' style='float:left;'><input type='text' name='tempat_lahir' value='".$dt['tempat_lahir']."'  size='24' id='tempat_lahir' readonly>&nbsp/&nbsp</div>".createEntryTgllahir($dt['tgl_lahir'], 'tgl_lahir', true,' '),
								'type'=>'',
									 ),	
									 
			'alamat' => array( 
								'label'=>'Alamat',
								'labelWidth'=>200, 
								'value'=>$dt['alamat'],
								'type'=>'text',
								'id'=>'alamat',
								'param'=> "style='width:400px' readonly"
									 ),						 					 		 
									 						 		
			'rt/rw/pos' => array( 
								'label'=>'RT/RW/Kode Pos',
								'labelWidth'=>200, 
								'value'=>"<input type='text' name='rt' value='".$dt['rt']."'  size='5' id='rt' readonly>&nbsp/&nbsp".
								"<input type='text' name='rw' value='".$dt['rw']."'  size='5' id='rw' readonly>&nbsp/&nbsp".
								"<input type='text' name='pos' value='".$dt['kode_pos']."'  size='5' id='pos' readonly>" 
								),
									 
			'kota/kabupaten' => array( 
								'label'=>'Kota/Kabupaten',
								'labelWidth'=>200, 
								'value'=>cmbQuery('kota',$dt['ref_kota'],$queryKota,'style="width:160px" id=KunjunganKota onChange=\''.$this->Prefix.'.KunjunganKota()\' ','--Kota/Kabupaten--')
									 ),	
			
			'kecamatan' => array( 
								'label'=>'Kecamatan',
								'labelWidth'=>200, 
								'value'=>//cmbQuery('kecamatan',$dt['ref_idkecamatan'],$querykecamatan,'style="width:160px" id=kecamatan ','--Kecamatan--')
										"<div id='div_kecamatan' style='float:left'>".
										cmbQuery('kecamatan',$dt['ref_idkecamatan'],"select Id, nama from ref_kecamatan where ref_idkota='".$dt['ref_kota']."'",'disabled','--Kecamatan--').
										/*<select name='kecamatan' id='kecamatan'>".
										$kecamatan. 
										 "</select>"</div>"
									 ),	
									 
			'kelurahan' => array( 
								'label'=>'Kelurahan',
								'labelWidth'=>200, 
								'value'=>"<input type='text' name='kelurahan' value='".$dt['kelurahan']."'  size='33' id='kelurahan' readonly>"
									 ),	
									 								 								 										 						 
			'agama/pekerjaan' => array( 
								'label'=>'Agama/Pekerjaan',
								'labelWidth'=>200, 
								'value'=>cmbArray('agama',$dt['kd_agama'],$this->Agama,'--Agama--','id=agama ').'&nbsp'.'/'.'&nbsp'.
										 cmbQuery('pekerjaan',$dt['ref_idpekerjaan'],$queryPekerjaan,'id=pekerjaan ','--Pekerjaan--')
									 
									 ),
									 						 
			'pendidikan/status' => array( 
								'label'=>'Pendidikan/Status',
								'labelWidth'=>200, 
								'value'=>cmbQuery('pendidikan',$dt['ref_pendidikan'],$queryPendidikan,'style="width:160px" id=pendidikan ','-- Pendidikan --').'&nbsp'.'/'.'&nbsp'.
										 cmbArray('status',$dt['status_pendidikan'],$this->status,'--Status--','id=status ')
									 ),
									 						 
			'statuskawin/sukubangsa' => array( 
								'label'=>'Status Kawin/Suku Bangsa',
								'labelWidth'=>200, 
								'value'=>cmbArray('stkawin',$dt['status_kawin'],$this->StKawin,'--PILIH--','id=stkawin')."&nbsp/&nbsp".
								"<input type='text' name='suku_bangsa' value='".$dt['suku_bangsa']."'  size='33' id='bangsa' readonly>"

								
									 ),	
									 									 
			'nama_ayah' => array( 
								'label'=>'Nama Ayah Kandung',
								'labelWidth'=>200, 
								'value'=>$dt['nama_ayah'], 
								'type'=>'text',
								'id'=>'nama_ayah',
								'param'=>"style='width:200ppx' size=34px readonly"
									 ),
									 
			'No KTP /no telp/hp' => array( 
								'label'=>'No Telp/HP',
								'labelWidth'=>200, 
								'value'=>"<input type='text' name='no_ktp' value='".$dt['no_ktp']."'  size='33' id='no_ktp' readonly>&nbsp/&nbsp".
								"<input type='text' name='hp' value='".$dt['no_hp']."'  size='30' id='hp' readonly>" 
									 ),
						
			'kunjungan' => array( 
								'label'=>'Kunjungan',
								'labelWidth'=>200, 
								'value'=>$jns_kunjungan//cmbArray('kunjungan',$dt['jns_kunjungan'],$this->Kunjungan,'--Kunjungan--','id=kunjungan onChange=\''.$this->Prefix.'.kunjungan()\'')	
									 ),	
									 
			'tarif' => array( 
								'label'=>'Tarif Baru/Lama',
								'labelWidth'=>200, 
								'value'=>cmbArray('tarif',$dt['Baru/Lama'],$this->tarif,'--Tarif--','id=tarif onChange=\''.$this->Prefix.'.tarif()\'')
										 ),
										 
			'pelayanan' => array( 
								'label'=>'Pelayanan',
								'value'=>cmbQuery('pelayanan',$dt['pelayanan'],$queryPelayanan,'id="pelayanan"','--PILIH--')."<input type='hidden' name='ref_iddokter' value='".$dt['ref_iddokter']."'>"
									 ),								 						 
									 						 
			'Waktuklinik' => array( 
								'label'=>'Waktu Klinik',
								'labelWidth'=>200, 
								'value'=>cmbArray('waktu_klinik',$dt['stshift'],$this->WaktuKlinik,'--Pagi/Sore--','id=waktu_klinik onChange=\''.$this->Prefix.'.tarif()\'')	
									 ),	
									 
			'autocomplete' => array( 
								'label'=>'Klinik',
								'labelWidth'=>150, 
								'value'=>"
								<div>
								<input type='text' name='autocomplete' id='autocomplete'  size='40'>
								<input type='hidden' id='klinik' name='klinik' value='".$dt['tambah']."' onChange=\"".$this->Prefix.".tarif()\">
								&nbsp
								<input type='button' id='resetklinik' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete\").value=\"\"'>
								</div>
								",
								'type'=>'',
								'row_params'=>"valign='top'",
								'param'=> ""
									 ),						 			 
									 
			/*'klinik' => array( 
								'label'=>'Klinik',
								'labelWidth'=>200, 
								'value'=>"<div id='t_klinik' style='float:left'>".cmbQuery('klinik',$dt['ref_idklinik'],$queryKlinik,'id="klinik" onChange=\''.$this->Prefix.'.kunjungan()\'','--Klinik--').
										"<div>"								
									 ),
									 						 
			'ruang/kelas/tempattidur' => array( 
								'label'=>'Ruang/Kelas/Tempat Tidur',
								'labelWidth'=>200, 
								'value'=>"<div id='t_ruang' style='float:left'>
											<div>
											<input type='text' name='autocomplete1' id='autocomplete1'  size='40' >
											<input type='hidden' id='ruang' name='ruang' value='".$dt['tambah']."' onChange=\"".$this->Prefix.".ruang()\">
											&nbsp
											<input type='button' id='resetruang' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete1\").value=\"\"'>&nbsp/&nbsp</div>
											</div>"./*cmbQuery('ruang',$dt['cd'],$queryRuang,'id="ruang" onChange=\''.$this->Prefix.'.ruang()\'','--Ruang / Kelas--').
										"<div id='t_tidur' style='float:left'><select name='tempat_tidur' id='ttidur'>".
										$opsi. 
										 "</select></div>"//$ruang
									 ),
									 
			'dokter' => array( 
								'label'=>'Dokter',
								'labelWidth'=>200, 
								'value'=>"<div>
											<input type='text' name='autocomplete3' id='autocomplete3'  size='40' >
											<input type='hidden' id='dokter' name='dokter' value='".$dt['tambah']."'>
											&nbsp
											<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete3\").value=\"\"'></div>
											</div>"/*"<div id='div_dokter' style='float:left'><select name='dokter' id='dokter'>".
										$dokter. 
										 "</select></div>"//cmbQuery('dokter',$dt['ref_iddokter'],$queryDokter,'id=dokter','--Dokter--')
									 ),	
									 
			'carabayar/penjamin' => array( 
								'label'=>'Cara Bayar/Penjamin',
								'labelWidth'=>200, 
								'value'=>"<div style='float:left'>".cmbQuery('cara_bayar',$dt['ref_idcarabayar'],$queryCaraBayar,'id=cara_bayar onChange=\''.$this->Prefix.'.cara_bayar()\'','--Cara Bayar--')	."&nbsp/&nbsp".
								   		"</div><div>
											<input type='text' name='autocomplete2' id='autocomplete2'  size='40' >
											<input type='hidden' id='penjamin' name='penjamin' value='".$dt['tambah']."'>
											&nbsp
											<input type='button' id='resetpenjamin' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete2\").value=\"\"'></div>"// cmbQuery('penjamin',$dt['ref_idpenjamin'],$queryPenjamin,'id=penjamin','--Penjamin--')	

								
									 ),
									 
			'rujukan/nama' => array( 
								'label'=>'Rujukan/Nama Rujukan',
								'labelWidth'=>200, 
								'value'=>cmbQuery('rujukan',$dt['ref_idrujukan'],$queryRujukan,'id=rujukan','--Rujukan--')	."&nbsp/&nbsp".
										"<input type='text' name='nama_rujukan' value='".$dt['nama_rujukan']."'  size='33' style='text-transform:uppercase'>"

								
									 )
						 					 
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan Diteruskan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >".
			"<input type='hidden' name='with_bayar'  id='with_bayar' value='".$with_bayar."' >";
			
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}*/

	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 150;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'DATA BARU';
	  }else{
	  	$this->form_caption = 'EDIT';
	  }
	  
	  	$jenis = array(
			array('1','ATK'), 
			array('2','OBAT'),
		);
		
	  	$satuan = array(
			array('1','Rim'), 
			array('2','Pack'),
			array('3','Botol'), 
			array('4','Tablet'),			
		);
				
 	    $username=$_REQUEST['username'];
		//query ref_batal
		$querySatuan = "SELECT Id,nama_satuan FROM ref_satuan_persediaan";
       //items ----------------------
		  $this->form_fields = array(
			'nama_barang' => array( 
								'label'=>'Nama barang',
								'labelWidth'=>100, 
								'value'=>"<input type='text' name='nama_barang' value='".$dt['nama_barang']."' size='50px' id='nama_barang' ".$dt['readonly'].">&nbsp&nbsp<input type='button' value='Cari' onclick ='".$this->Prefix.".CariBarang()' title='Cari Barang'  ".$dt['disabled'].">"
									 ),	
									 
			'tahun_anggaran' => array( 
								'label'=>'Tahun Anggaran',
								'labelWidth'=>100, 
								'value'=>$dt['tahun_anggaran'], 
								'type'=>'text',
								'id'=>'nama_barang',
								'param'=>"size=8px"
									 ),	

			'satuan' => array( 
								'label'=>'Satuan',
								'labelWidth'=>100, 
								'value'=>cmbQuery('satuan',$dt['satuan'],$querySatuan,'id=satuan','--Satuan--')		 
									 ),	
									 
			'harga' => array( 
								'label'=>'Harga',
								'labelWidth'=>100, 
								'value'=>$dt['harga'], 
								'type'=>'text',
								'id'=>'harga',
								'param'=>"size=20px"
									 ),										 							 
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>".
			"<input type='hidden' value='' id='kode_barang' name='kode_barang'>".														
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function setForm2($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 800;
	 $this->form_height = 500;
	  if ($this->form_fmST==2) {
		$this->form_caption = 'CARI BARANG';
	  }else{
	  	
	  }
	  
	  	$jenis = array(
			array('1','ATK'), 
			array('2','OBAT'),
		);
		
	  	$satuan = array(
			array('1','Rim'), 
			array('2','Pack'),
			array('3','Botol'), 
			array('4','Tablet'),			
		);
				
 	    $username=$_REQUEST['username'];
		//query ref_batal
		$query = "SELECT Id,uraian FROM ref_batal";
       //items ----------------------
		  $this->form_fields = array(	
	  	 						 					 
			'daftarpersediaanbarang' => array( 

						'label'=>'',

						 'value'=>"<div id='daftarpersediaanbarang' style='height:5px'></div>",

						 'type'=>'merge'

					 )										 
						 					 
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".PilihBarang()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Closecaribarang()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	/*function setForm2($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 550;
	 $this->form_height = 100;
	  if ($this->form_fmST==1) {
		$this->form_caption = 'BATAL KUNJUNGAN';
	  }else{
	  	
	  }
 	    $username=$_REQUEST['username'];
		//query ref_batal
		$query = "SELECT Id,uraian FROM ref_batal";
       //items ----------------------
		  $this->form_fields = array(
		  		'jns_batal' => array( 
						'label'=>'Jenis Batal',
						'labelWidth'=>120, 
						'value'=>cmbQuery('jns_batal',$dt['ref_idbatal'],$query,"style='width:200px'",'--Jenis Batal---') 
						),
				
				'ket_batal' => array( 
									'label'=>'Keterangan Batal',
									'labelWidth'=>100, 
									'value'=>
									"<textarea style='width:400; height:50px;'  id='ket_batal' name='ket_batal' >".$dt['ketbatal']."</textarea>",
									'row_params'=>"valign='top'",
										 )								 
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='username' id='username' value='$username'>".	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Batal()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}*/
	
	function setForm4($dt){	
		 global $SensusTmp;
		 $cek = ''; $err=''; $content=''; 
			
		 $json = TRUE;	//$ErrMsg = 'tes';
		 	
		 $form_name = $this->Prefix.'_form';				
		 $this->form_width = 250;
		 $this->form_height = 100;
		  if ($this->form_fmST==1) {
			$this->form_caption = 'LOGIN ADMIN';
		  }else{
		  	
		  }
		  
			//query ref_batal
			$query = "SELECT Id,uraian FROM ref_batal";
			
		  
	       //items ----------------------
		  $this->form_fields = array(
		  		'username' => array( 
						'label'=>'Username',
						'labelWidth'=>50, 
						'value'=>"<input type='text' id='username' name='username' style='width:150px;text-transform: uppercase;'>", 
						'type'=>'',
						'param'=>""
						 ),
			
				'password' => array( 
							'label'=>'Password',
							'labelWidth'=>50, 
							'value'=>"<input type='password' id='password' name='password' style='width:150px'>", 
							'type'=>'',
							'param'=>""
							 ),
				);
			//tombol
			$this->form_menubawah =
				"<input type='button' value='Simpan' onclick ='".$this->Prefix.".CekAdmin()' title='Batalkan' >".
				"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
								
			$form = $this->genForm();		
			$content = $form;//$content = 'content';
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		}			

		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	 "<thead>
	 <tr>
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox		
   	   <th class='th01' width='100'>Kode Barang</th>
	   <th class='th01' width='200'>Nama Barang</th>
	   <th class='th01' width='200'>Tahun Anggaran</th>
	   <th class='th01' width='200'>Satuan</th>
	   <th class='th01' width='200'>Harga</th>
	   </tr>
	   </thead>";
	
		return $headerTable;
	}
	
	function setPage_HeaderOther(){
		$Pg = $_REQUEST['Pg'];
		
		$barang = '';
		$persediaan = '';
		switch ($Pg){
			case 'masterbarang': $barang ="style='color:blue;'"; break;
			case 'persediaanbarang': $persediaan ="style='color:blue;'"; break;
		}
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=masterbarang\" title='Barang' $barang>Barang </a> |
			<A href=\"pages.php?Pg=persediaanbarang\" title='Persediaan'  $persediaan>Persediaan</a>    	
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 if($isi['g']==0  && $isi['h']==0 && $isi['i']==0 && $isi['j']==0){
	 	$isif=$isi['f'];
		$isig='';
		$isih='';
		$isii='';
		$isij='';		
	 }elseif($isi['g']!=0  && $isi['h']==0 && $isi['i']==0 && $isi['j']==0){
	 	$isif=$isi['f'];
		$isig=$isi['g'];
		$isih='';
		$isii='';
		$isij='';	 	
	 }elseif($isi['g']!=0  && $isi['h']!=0 && $isi['i']==0 && $isi['j']==0){
	 	$isif=$isi['f'];
		$isig=$isi['g'];
		$isih=$isi['h'];
		$isii='';
		$isij='';	 	
	 }elseif($isi['g']!=0  && $isi['h']!=0 && $isi['i']!=0 && $isi['j']==0){
	 	$isif=$isi['f'];
		$isig=$isi['g'];
		$isih=$isi['h'];
		$isii=$isi['i'];
		$isij='';	 	
	 }elseif($isi['g']!=0  && $isi['h']!=0 && $isi['i']!=0 && $isi['j']!=0){
	 	$isif=$isi['f'];
		$isig=$isi['g'];
		$isih=$isi['h'];
		$isii=$isi['i'];
		$isij=$isi['j'];	 		 	
	 }	 	 

	 $f=mysql_fetch_array(mysql_query("select f, nama_barang AS golongan from ref_barang_persediaan where f =".$isif." and g=00 and h=00 and i=00 and j=00"));
	 $g=mysql_fetch_array(mysql_query("select g, nama_barang  from ref_barang_persediaan where f =".$isif." and g=".$isig." and h=00 and i=00 and j=00"));
	 $h=mysql_fetch_array(mysql_query("select h, nama_barang AS merk from ref_barang_persediaan where f =".$isif." and g=".$isig." and h=".$isih." and i=00 and j=00"));	 
	 $i=mysql_fetch_array(mysql_query("select i, nama_barang AS type from ref_barang_persediaan where f =".$isif." and g=".$isig." and h=".$isih." and i=".$isii." and j=00"));
	 $j=mysql_fetch_array(mysql_query("select j, nama_barang AS spesifikasi from ref_barang_persediaan where f =".$isif." and g=".$isig." and h=".$isih." and i=".$isii." and j=".$isij.""));	 	 	 
	 $satuan=mysql_fetch_array(mysql_query("select * from ref_satuan_persediaan where Id='".$isi['satuan']."'"));	 	 	 
	 $kode_barang=$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $nama_barang=$f['golongan'].' / '.$g['nama_barang'].' / '.$h['merk'].' / '.$i['type'].' / '.$j['spesifikasi'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="center" width="300"',$kode_barang);
 	 $Koloms[] = array('align="left" width="700"',$nama_barang);
 	 $Koloms[] = array('align="left" width="100"',$isi['tahun_anggaran']);
 	 $Koloms[] = array('align="left" width="100"',$satuan['nama_satuan']);
 	 $Koloms[] = array('align="right" width="100"',number_format($isi['harga'],2,',','.'));
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 $arr = array(
			//array('selectAll','Semua'),
			array('selecttgl','Tgl Masuk'),
			array('selectnorm','No. RM'),
			array('selectpasien','Nama Pasien'),		
			);
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Tanggal'),
			     array('2','No. RM'),	
				 array('3','Nama Pasien')
	 );
	 
	 $arrKunjungan = array(
	  	         array('1','Rawat jalan'),
			     array('2','Rawat inap'),	
				 array('3','Instalasi gawat darurat')
	 );
	 
	 $arrSB = array(
	  	         array('1','Belum Lunas'),	
				 array('2','Lunas')
	 );
	 
	 $arrJK = array(
	  	         array('L','Laki-laki'),	
				 array('P','Perempuan')
	 );
	 
	 $arrStbaru = array(
	  	         array('1','Baru'),	
				 array('2','Lama')
	 );
	 
	 $arrSP = array(
	  	         array('1','Pasien Masuk'),	
				 array('2','Pasien Keluar'),
     );
	 
	 $arrDarah = array(
					array('A','A'), 
					array('B','B'),
					array('AB','AB'), 
					array('O','O')
	 );
	 
	 $arrSK = array(
					array('1','Kawin'), 
					array('2','Belum kawin'),
					array('3','Janda'), 
					array('4','Duda')
	 );
	
	 $arrWaktu = array(
					array('1','Pagi'), 
					array('2','Sore'),
	 );
	 
	 
	 
	 
	$queryPasien = "select Id, nama_pasien from pasien";
    $queryKota = "select Id, nama from ref_kota";
    $queryPendidikan = "Select Id, nama from ref_pendidikan";
    $queryPekerjaan = "Select Id, nama from ref_pekerjaan";
    $queryKlinik = "select * from ref_klinik";
    $queryRuang = "select concat(c,d), CONCAT(ruang,' / ',uraian) from v1_ruang";
    $queryDokter = "Select Id, nama from ref_dokter";
    $queryCaraBayar = "Select * from ref_cara_bayar";
    $queryPenjamin = "Select * from ref_penjamin";
    $queryRujukan = "Select Id, nama from ref_rujukan";
	$queryTidur = "SELECT concat(c,d), status from ref_ttidur";		
	$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	$fmORDER2 = cekPOST('fmORDER2');
	$fmORDER3 = cekPOST('fmORDER3');
	$fmORDER4 = cekPOST('fmORDER4');
	$fmORDER5 = cekPOST('fmORDER5');
	$fmORDER6 = cekPOST('fmORDER6');
	$fmORDER7 = cekPOST('fmORDER7');
	$fmORDER8 = cekPOST('fmORDER8');
	$fmORDER9 = cekPOST('fmORDER9');
	$fmORDER10 = cekPOST('fmORDER10');
	$fmORDER11 = cekPOST('fmORDER11');
	$fmORDER12 = cekPOST('fmORDER12');
	$fmORDER13 = cekPOST('fmORDER13');
	$fmORDER14 = cekPOST('fmORDER14');
	$fmORDER15 = cekPOST('fmORDER15');
	$fmORDER16 = cekPOST('fmORDER16');
	$fmORDER17 = cekPOST('fmORDER17');
	$fmPILCARIvalue2 = $_REQUEST['fmPILCARIvalue2'];

				
	$TampilOpt = 
			"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Tampil : </div>".
			//createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1).
			//cmbArray('fmORDER2',$fmORDER2,$arrStbaru,'--Pasien--','')."&nbsp".
			//cmbArray('fmORDER3',$fmORDER3,$arrKunjungan,'--Kunjungan--','')."&nbsp".
			//cmbArray('fmORDER17',$fmORDER17,$arrWaktu,'--Waktu Klinik--','')."&nbsp".
			//cmbQuery('fmORDER4',$fmORDER4,$queryKlinik,'','--Klinik--','')."&nbsp".
			//cmbQuery('fmORDER5',$fmORDER5,$queryRuang,'','--Ruang/Kelas--','')."&nbsp".
			//"<tr valign='middle'>   						
			//<td align='left' style='padding:1 8 0 8; '>".
			//cmbQuery('fmORDER6',$fmORDER6,$queryCaraBayar,'','--Cara Bayar--','')."&nbsp".
			//cmbArray('fmORDER7',$fmORDER7,$arrSB,'--Status Bayar--','')."&nbsp".
			//cmbQuery('fmORDER8',$fmORDER8,$queryDokter,'','--Dokter--','')."&nbsp".
			//cmbQuery('fmORDER9',$fmORDER9,$queryRujukan,'','--Rujukan','')."&nbsp".
			//cmbArray('fmORDER10',$fmORDER10,$arrSP,'--Status Pasien','')."&nbsp".
			//"</td>				
			//</tr>
			//"<tr valign='middle'>   						
			//<td align='left' style='padding:1 8 0 8; '>".
			//genFilterBar(
				//array(		
					//cmbArray('fmORDER11',$fmORDER11,$arrJK,'--L/P--','')."&nbsp".					
					//cmbArray('fmORDER12',$fmORDER12,$arrDarah,'--GolDarah--','')."&nbsp".
					//cmbQuery('fmORDER13',$fmORDER13,$queryPendidikan,'','--Pendidikan--','')."&nbsp".
					//cmbQuery('fmORDER14',$fmORDER14,$queryPekerjaan,'','--Pekerjaan--','')."&nbsp".
					//cmbArray('fmORDER15',$fmORDER15,$arrSK,'--Status Kawin--','')."&nbsp".
					//cmbQuery('fmORDER16',$fmORDER16,$queryKota,'','--Kota/Kab--','')."&nbsp".
					//cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."&nbsp".
					//"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>menurun".
					//"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'><br>".
					//),			
				//$this->Prefix.".refreshList(true)").
			"<table><tr>
				<td>GOLONGAN</td>
				<td>:</td>
				<td>".cmbQuery('fmGOLONGAN',$fmGOLONGAN,'select f, nama_barang from ref_barang_persediaan where f !=00 and g=00 and h=00 and i=00 and j=00','','--pilih--','')."</td>
				</tr>
				<tr>
				<td>SUB GOLONGAN</td>
				<td>:</td>
				<td>".cmbQuery('fmSUBGOLONGAN',$fmSUBGOLONGAN,'select g, nama_barang from ref_barang_persediaan where f !=00 and g!=00 and h=00 and i=00 and j=00','','--pilih--','')."</td>
				</tr>
				<tr>
				<td>MERK</td>
				<td>:</td>
				<td>".cmbQuery('fmMERK',$fmMERK,'select h, nama_barang from ref_barang_persediaan where f !=00 and g!=00 and h!=00 and i=00 and j=00','','--pilih--','')."</td>
				</tr>
				<tr>
				<td>TYPE</td>
				<td>:</td>
				<td>".cmbQuery('fmTYPE',$fmTYPE,'select h, nama_barang from ref_barang_persediaan where f !=00 and g!=00 and h!=00 and i!=00 and j=00','','--pilih--','')."</td>".
			"</tr></table></td></tr>	
			</tbody></table>
			</td></tr></tbody></table>
		    </div>";
			//$vOrder=
			

			/*"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
			<td align='left' style='padding:1 8 0 8; '>".
			$cari2 =
			//cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','').'&nbsp'. //generate checkbox
			//"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>
			//<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>";*/
			
			//;
				/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		//Cari 
		switch($fmPILCARI){
			case 'selecttgl': $arrKondisi[] = " tgl_kunjungan like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectnorm': $arrKondisi[] = " no_rm like '%".(int)$fmPILCARIvalue."%'"; break;					 	
			case 'selectpasien': $arrKondisi[] = " nama_pasien like '%$fmPILCARIvalue%'"; break;		
		}
		
		//$arrKondisi[] = " stbatal != '1'";
		//$arrKondisi[] = " sttemp != '1'";
		//$arrKondisi[] = " jns_kunjungan IN(1,2,3)";
		//$arrKondisi[] = " order by tgl_kunjungan desc";
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_kunjungan>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_kunjungan<='$fmFiltTglBtw_tgl2'";
		if($_POST['fmORDER2']==1){if(!empty($_POST['fmORDER2'])) $arrKondisi[] = " stbaru = '1'";}
		else{if(!empty($_POST['fmORDER2'])) $arrKondisi[] = " stbaru = '0'";}
		if(!empty($_POST['fmORDER3'])) $arrKondisi[] = " jns_kunjungan like '%".$_POST['fmORDER3']."%'";
		if(!empty($_POST['fmORDER4'])) $arrKondisi[] = " ref_idklinik like '%".$_POST['fmORDER4']."%'";
		if(!empty($_POST['fmORDER5'])) $arrKondisi[] = " concat(c,d) like '%".$_POST['fmORDER5']."%'";
		if(!empty($_POST['fmORDER6'])) $arrKondisi[] = " ref_idcarabayar like '%".$_POST['fmORDER6']."%'";
		if($_POST['fmORDER7']==1){if(!empty($_POST['fmORDER7'])) $arrKondisi[] = " stbayar = '0'";}
		else{if(!empty($_POST['fmORDER7'])) $arrKondisi[] = " stbayar = '2'";}
		if(!empty($_POST['fmORDER8'])) $arrKondisi[] = " ref_iddokter = '".$_POST['fmORDER8']."'";
		if(!empty($_POST['fmORDER9'])) $arrKondisi[] = " ref_idrujukan like '%".$_POST['fmORDER9']."%'";
		if($_POST['fmORDER10']==1){if(!empty($_POST['fmORDER10'])) $arrKondisi[] = " stkeluar like '0'";}
		elseif($_POST['fmORDER10']==2){if(!empty($_POST['fmORDER10'])) $arrKondisi[] = " stkeluar = '1'";}
		if(!empty($_POST['fmORDER11'])) $arrKondisi[] = " jk like '%".$_POST['fmORDER11']."%'";
		if(!empty($_POST['fmORDER12'])) $arrKondisi[] = " gol_darah = '".$_POST['fmORDER12']."'";
		if(!empty($_POST['fmORDER13'])) $arrKondisi[] = " ref_pendidikan like '%".$_POST['fmORDER13']."%'";
		if(!empty($_POST['fmORDER14'])) $arrKondisi[] = " ref_idpekerjaan like '%".$_POST['fmORDER14']."%'";
		if(!empty($_POST['fmORDER15'])) $arrKondisi[] = " status_kawin like '%".$_POST['fmORDER15']."%'";
		if(!empty($_POST['fmORDER16'])) $arrKondisi[] = " ref_kota like '%".$_POST['fmORDER16']."%'";
		if($_POST['fmORDER17']==1){if(!empty($_POST['fmORDER17'])) $arrKondisi[] = " stshift = '0'";}
		else{if(!empty($_POST['fmORDER17'])) $arrKondisi[] = " stshift = '1'";}
		
		$no_rm = (int)$_POST['cari2'];	
		if(!empty($_POST['cari2'])) $arrKondisi[] = " no_rm = '".$no_rm."'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			//case '': $arrOrders[] = " tgl_kunjungan DESC " ;break;
			case '1': $arrOrders[] = " tgl_kunjungan $Asc1 " ;break;
			case '2': $arrOrders[] = " no_rm $Asc1 " ;break;
			case '3': $arrOrders[] = " nama_pasien $Asc1 " ;break;
		
		}

			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//}
		//$Order ="";
		//limit --------------------------------------
		/**$HalDefault=cekPOST($this->Prefix.'_hal',1);	//Cat:Settingan Lama				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		**/
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$PersediaanBarang = new PersediaanBarangObj();

?>