<?php

class backupObj  extends DaftarObj2{	
	var $Prefix = 'backup';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_backup'; //daftar
	var $TblName_Hapus = 't_backup';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Administrasi';
	var $PageIcon = 'images/administrasi_01.GIF';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BACKUP';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'backupForm'; 	
			
	function setTitle(){
		return 'Backup Data';
	}
	function setMenuEdit(){
		$jns = $_REQUEST['jns'];
		$vbackup = $jns==1? "<td>".genPanelIcon("javascript:".$this->Prefix.".backupdata()","backup.png","Backup",'Backup')."</td>": '';
		return

		$vbackup.
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Restore()","restore_f2.png","Restore", 'Restore')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
		"</td>";
	}
	
	function isidata(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$jns=$_REQUEST['jns'];
		//$list1 = glob('../../backup_sistem/atisisbada_bogor_kab/mysql/mysql/khusus/*.data.sql.gz');
		//$list2 = glob('../../backup_sistem/atisisbada_bogor_kab/mysql/mysql/daily/*.data.sql.gz');
		//$list3 = glob('../../backup_sistem/atisisbada_bogor_kab/mysql/mysql/weekly/*.data.sql.gz');
		//$dir = 
		$filterfile =  '*.data.sql.gz'; $cek.=' dir: '.getcwd();
		$list1 = glob($Main->BACKUP_DIR.'khusus/'.$filterfile);
		$list2 = glob($Main->BACKUP_DIR.'daily/'.$filterfile);
		$list3 = glob($Main->BACKUP_DIR.'weekly/'.$filterfile);
		$ceklist1=$Main->BACKUP_DIR."khusus/";
		$ceklist2=$Main->BACKUP_DIR."daily/";
		$ceklist3=$Main->BACKUP_DIR."weekly/";
		$x1=count($list1);
		$x2=count($list2);
		$x3=count($list3);
		//get data -----------------
		//$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		// $idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$tanggalhariini=date('Y/m/d');
		$sql=mysql_query("select * from setting WHERE Id='last_ins_backup'");
		$nilaidata=mysql_fetch_array($sql); //$nilaidata=mysql_fetch_row($sql); 				
		$nilaitanggal=$nilaidata['nilai']; $cek .= ' tgl last_ins_backup='.$nilaitanggal;
		$tanggalsekarang=strtotime(date('Y/m/d'));
		$tanggalbackup=strtotime($nilaitanggal);
		if ($tanggalbackup<$tanggalsekarang){
			$sql1="Update setting set nilai='$tanggalhariini' WHERE Id='last_ins_backup'"; $cek.=$sql1;
			mysql_query($sql1);
			
			//cek apkaah file tidak ada di direktori ------------------------------------------------
			$query1="select nmfile, jns from $this->TblName WHERE stat='0'";
		 	$hasil1=mysql_query($query1);
		 	while ($row=mysql_fetch_array($hasil1)){
		 		$cekrow=$row['jns'];
				if ($cekrow==1){								
				  	if( file_exists("$ceklist1".$row['nmfile']) ){
					
				  	}else{
						$aqry1="UPDATE $this->TblName set stat='1' where nmfile='".$row['nmfile']."'";
						$qry=mysql_query($aqry1);
				  	}
				}elseif($cekrow==2){								
				  	if(file_exists("$ceklist2".$row['nmfile']) ){
					
				  	}else{
						$aqry1="UPDATE $this->TblName set stat='1' where nmfile='".$row['nmfile']."'";
						$qry=mysql_query($aqry1);
				  	}
				}elseif($cekrow==3){								
				  	if(file_exists("$ceklist3".$row['nmfile']) ){
					
				  	}else{
						$aqry1="UPDATE $this->TblName set stat='1' where nmfile='".$row['nmfile']."'";
						$qry=mysql_query($aqry1);
				  	}
				}else{								
				}
		 	}
							
			//insert file jika belum ada di tabel -----------------------------------------------------
			for($i=0;$i<$x1;$i++){//khusus
				 $basename[$i]=basename( $list1[$i] );
				 $filesize[$i]=filesize( $list1[$i] );
				 $filemtime[$i]=date('Y/m/d h:i:s', filemtime($list1[$i]));
				 //cek file tidak ada
				 $query1 = "SELECT Id AS Id FROM $this->TblName WHERE nmfile='$basename[$i]' and jns='1'"; $cek .= $query1;
			 	$hasil1 = mysql_query($query1);
			 	$data1  = mysql_fetch_array($hasil1);
				if ($data1 ==''){ //file tidak ada
				 	$aqry1 = "INSERT into $this->TblName (nmfile,size,tgl,tgl_update,jns) values('$basename[$i]','$filesize[$i]','$filemtime[$i]',now(),'1')"; $cek .= $aqry1;	
				 	$qry = mysql_query($aqry1);
				 	if($qry==FALSE) $err="Gagal simpan data".mysql_error();
				}
			}									 									 				
			for($i=0;$i<$x2;$i++){ //daily
				$basename[$i]=basename( $list2[$i] );
				$filesize[$i]=filesize( $list2[$i] );
				$filemtime[$i]=date('Y/m/d h:i:s', filemtime($list2[$i]));
				 //cek file tidak ada
				$query1 = "SELECT Id AS Id FROM $this->TblName WHERE nmfile='$basename[$i]' and jns='2'"; $cek .= $query1;
				$hasil1 = mysql_query($query1);
				$data1  = mysql_fetch_array($hasil1);
				if ($data1 ==''){
					$aqry1 = "INSERT into $this->TblName (nmfile,size,tgl,tgl_update,jns) values('$basename[$i]','$filesize[$i]','$filemtime[$i]',now(),'2')"; $cek .= $aqry1;	
					$qry = mysql_query($aqry1);
					if($qry==FALSE) $err="Gagal simpan data".mysql_error();
				}	
			}								 
			for($i=0;$i<$x3;$i++){//weekly
				$basename[$i]=basename( $list3[$i] );
				$filesize[$i]=filesize( $list3[$i] );
				$filemtime[$i]=date('Y/m/d h:i:s', filemtime($list3[$i]));
				//cek file tidak ada
				$query1 = "SELECT Id AS Id FROM $this->TblName WHERE nmfile='$basename[$i]' and jns='3'"; $cek .= $query1;
				$hasil1 = mysql_query($query1);
				$data1  = mysql_fetch_array($hasil1);
				if ($data1 ==''){
					$aqry1 = "INSERT into $this->TblName (nmfile,size,tgl,tgl_update,jns) values('$basename[$i]','$filesize[$i]','$filemtime[$i]',now(),'3')"; $cek .= $aqry1;	
					$qry = mysql_query($aqry1);
					if($qry==FALSE) $err="Gagal simpan data".mysql_error();	
				}
			}
						
		}else{			
		}//penutup else tanggal
						
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
//Hapus data =========================================================
	function Hapus_Validasi($ids){//id -> multi field with space delimiter
		$errmsg =''; $cek='';
		$KeyValue = explode(' ',$ids);
		
		/*for($i=0;$i<sizeof($this->KeyFields);$i++){
		    $aqry1= "select * from $this->TblName_Hapus where ".$this->KeyFields[$i]."=".$KeyValue[$i]; $cek.=$aqry1;
			$qry1 = mysql_query($aqry1);
			$hasil=mysql_fetch_row($qry1);
			$jns=$hasil[4];
			$tanggalbackup=$hasil[3];
			$tambah = mktime(0,0,0,date("m")-2,date("d"),date("Y"));
			$tglkurang2bulan = strtotime(date('Y/m/d', $tambah));
			$tglvalidasi = strtotime($tanggalbackup);
			
			if ($errmsg=='' && $jns!=2 ) $errmsg = 'Gagal Hapus! Data yang dapat dihapus adalah data backup daily!';
			
			if ($errmsg=='' && $tglvalidasi<$tglkurang2bulan) $errmsg = 'Gagal Hapus! Data yang dapat dihapus adalah data yang lebih dari dua bulan!';
		
		}*/
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !=''){
			$Kondisi = ' Where '.$Kondisi;
			$aqry1= "select * from $this->TblName_Hapus $Kondisi "; //$errmsg .= ' qry='. $aqry1;
			$qry1 = mysql_query($aqry1);
			$hasil=mysql_fetch_array($qry1);
			$jns=$hasil['jns'];
			$tanggalbackup=$hasil['tgl']; //$errmsg .=' tgl'.$tanggalbackup;
			$tambah = mktime(0,0,0,date("m")-2,date("d"),date("Y"));
			$tglkurang2bulan = strtotime(date('Y/m/d', $tambah)); //$errmsg .=' tglkrg2bln='.date('Y/m/d', $tambah);
			$tglvalidasi = strtotime($tanggalbackup);		
			if ($errmsg=='' && $hasil['stat']==1) 	$errmsg = 'Gagal Hapus! Data sudah dihapus!';
			if ($errmsg=='' && $jns!=2 ) $errmsg = 'Gagal Hapus! Data yang dapat dihapus adalah data backup daily!';			
			if ($errmsg=='' && $tglvalidasi>$tglkurang2bulan) $errmsg = 'Gagal Hapus! Data yang dapat dihapus adalah data yang lebih dari dua bulan!';		
		}
		return $errmsg;
	}
	function Hapus_Data($ids){//id -> multi field with space delimiter
		$err = ''; $cek='';
		$KeyValue = explode(' ',$ids);
		$arrKondisi = array();
		global $HTTP_COOKIE_VARS;
	 	global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$tanggalsekarang=date('Y/m/d h:i:s');
		$ceklist2="../atisisbada/mysql/daily/";
		/*
	//Unlink data
		for($i=0;$i<sizeof($this->KeyFields);$i++){
	    $aqry1= "select * from $this->TblName_Hapus where ".$this->KeyFields[$i]."=".$KeyValue[$i]; $cek.=$aqry1;
		$qry1 = mysql_query($aqry1);
		$hasil=mysql_fetch_row($qry1);
		$nmfile=$hasil[1];
			unlink('$ceklist2'.$nmfile);
		}	
		*/
		
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !=''){
			$Kondisi = ' Where '.$Kondisi;
			
			//hapus file 
			$old = mysql_fetch_array(mysql_query("select * from $this->TblName_Hapus $Kondisi"));
			$path = $Main->BACKUP_DIR.'daily/';
			$unlink =  unlink($path.$old['nmfile']);
			if ($unlink==FALSE) $err= 'Gagal Hapus File!';			
			if($err==''){
				//set stat=1
				$aqry= "UPDATE $this->TblName_Hapus set stat = '1', uid='$uid', tgl_update='$tanggalsekarang' $Kondisi"; $cek.=$aqry;
				$qry = mysql_query($aqry);
			}
		}
						
		
		
		return array('err'=>$err,'cek'=>$cek);
	}
	/*function Hapus_Data_After($id){//id -> multi id with space delimiter
		$errmsg = '';
		
		return $errmsg;
	}*/
	function Hapus($ids){
		$err=''; $cek='';
		//$cid= $POST['cid'];
		//$err = ''.$ids;
		for($i = 0; $i<count($ids); $i++)	{
			$err = $this->Hapus_Validasi($ids[$i]);
			
			if($err ==''){
				$get = $this->Hapus_Data($ids[$i]);
				$err = $get['err'];
				$cek.= $get['cek'];
				//if ($errmsg=='') $errmsg = $this->Hapus_Data_After($ids[$i]);
				if ($err != '') break;
				 				
			}else{
				break;
			}			
		}
		return array('err'=>$err,'cek'=>$cek);
	}
	
//Progress backupObj==============================
 
//Selector========================================	
	function backupdata(){
		global $Main;
		global $MySQL;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//mysqldump -u root -p --no-data --skip-events --skip-routines --skip-triggers $db  > db_atsbd.struk.sql
		//$err= 'tes';
		$PWD = 'callysta';
		$path = //'';// 
			$Main->BACKUP_DIR."khusus/";
		$cmd = "mysqldump -u root -p$PWD --no-data --skip-events --skip-routines --skip-triggers $MySQL->DB  > $path/$MySQL->DB.struk.sql"; $cek.=$cmd;
		$content=exec($cmd); 
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	    case 'backupdata':{
			$fm = $this->backupdata();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];	
			break;
		}
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
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
					
		case 'isidata':{
			$get= $this->isidata();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'hapus':{
			$get= $this->Hapus();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   
	   case 'backupdata':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
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
			 "<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 
			
			$scriptload;
	}
	
//form ==================================
	
//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	 "<thead>
	 <tr>
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox		
   	   <th class='th01' width='500' >Nama File</th>
	   <th class='th01' width='70'>Size</th>	
	   <th class='th01' >Tanggal</th>
	   <th class='th01' >Jenis</th> 
	   <th class='th01' >User Id</th>
	   <th class='th01' >Tanggal Update</th>
	   <th class='th01' >Status</th>   	   	   
	   </tr>
	   </thead>";
	
	return $headerTable;
	}	
	
	function setPage_HeaderOther(){
		$Pg = $_REQUEST['Pg'];
		
		/*$barang = '';
		$harga = '';
		switch ($Pg){
			case 'masterbarang': $barang ="style='color:blue;'"; break;
			case 'masterharga': $harga ="style='color:blue;'"; break;
		}
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=masterbarang\" title='Barang' $barang>Barang Persediaan</a> |
			<A href=\"pages.php?Pg=masterharga\" title='Harga'  $harga>Harga Barang Persediaan</a>    												
			&nbsp&nbsp&nbsp	
			</td></tr></table>"*/;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
     
	  $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('',$isi['nmfile']);
	 $Koloms[] = array('',$isi['size']);
	 $Koloms[] = array('',$isi['tgl']);
	 $jenis_backup = array('','Khusus','Daily','Weekly');
	 $Koloms[] = array('', $jenis_backup[$isi['jns']]);
	 $Koloms[] = array('',$isi['uid']);	
	 $Koloms[] = array('',$isi['tgl_update']);
	 $status_backup = array('','Dihapus','Sinkron');
	 $Koloms[] = array('',$status_backup[$isi['stat']]);		 	 	 	 
	 return $Koloms;
	 	 
				 }
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$jns=$_REQUEST['jns'];
	$jnsselect0 ='';
	$jnsselect1 ='';
	$jnsselect2 ='';
	$jnsselect3 ='';
	
	switch($jns){
		case'0':$jnsselect0 ="selected='selected'";break;
	    case'1':$jnsselect1 ="selected='selected'";break;
		case'2':$jnsselect2 ="selected='selected'";break;
		case'3':$jnsselect3 ="selected='selected'";break;
	}
	
	//data order ------------------------------
	  $arrOrder = array(
	  	         array('1','Tanggal'),
			     
	 );	
				
	$TampilOpt = 
			"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'> 
			     					
				<td align='left' style='padding:1 8 0 8; '> Jenis  <select onchange='".$this->Prefix.".refreshList(true)' id='jns' name='jns'>
				<option Value='' $jnsselect0>---Pilih Semua---</option>
				<option Value='1' $jnsselect1>Khusus</option>
				<option Value='2' $jnsselect2>Daily</option>
				<option Value='3' $jnsselect3>Weekly</option></select>
				&nbsp".
			  "</div>".
			"<div style='float:left;padding: 2 8 0 8;height:20;'>Tanggal </div>".
			createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)."
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Urutkan : </div>".
			cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."&nbsp".					
			"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>menurun ".
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'><br>".			
					"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
					"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$jns=$_REQUEST['jns'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
		
		switch($jns){
		    case '':  $arrKondisi[] = " jns like '%$jns%'"; break;
			case '1': $arrKondisi[] = " jns like '%$jns%'"; break;		 	
			case '2': $arrKondisi[] = " jns like '%".$jns."%'"; break;
			case '3': $arrKondisi[] = " jns like '%".$jns."%'"; break;
	
		}
		
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl<='$fmFiltTglBtw_tgl2'";	
			
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
	
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " nmfile ASC " ;break;
			case '1': $arrOrders[] = " tgl $Asc1 " ;break;
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
$backup = new backupObj();

?>