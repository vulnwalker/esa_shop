<?php

class ref_rekeningObj  extends DaftarObj2{	
	var $Prefix = 'ref_rekening';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_rekening'; //bonus
	var $TblName_Hapus = 'ref_rekening';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('k','l','m','n','o');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'MASTER DATA';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='ref_rekening.xls';
	var $namaModulCetak='MASTER DATA';
	var $Cetak_Judul = 'REKENING';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ref_rekeningForm';
	var $noModul=9; 
	var $TampilFilterColapse = 0; //0
	
	function setTitle(){
		return 'REKENING';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";		
	}
	
	/*function setMenuView(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";
	}*/
	
	function simpanKB(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$ka01= $_REQUEST['k'];
		$kb= $_REQUEST['l'];
		
		
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Kelompok Belum Di Isi !!';
	if( $err=='' && $kb ==10 ) $err= 'Kode kelompok tidak bisa tambah baru !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_rekening (k,l,m,n,o,nm_rekening) values('$ka01','$kb','0','00','00','$nama')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$kb;	
				}
			}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanEdit(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	
	$dk= $_REQUEST['k'];
	$dl= $_REQUEST['l'];
	$dm= $_REQUEST['m'];
	$dn= $_REQUEST['n'];
	$do= $_REQUEST['o'];
	$nama= $_REQUEST['nm_rekening'];
	

	//$ke = substr($ke,1,1);
	
								
	if($err==''){						
		
	$aqry = "UPDATE ref_rekening set k='$dk',l='$dl',m='$dm',n='$dn',o='$do',nm_rekening='$nama' where concat (k,' ',l,' ',m,' ',n,' ',o)='".$idplh."'";$cek .= $aqry;
						$qry = mysql_query($aqry);
				}
								
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanKD(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$ka01= $_REQUEST['k'];
		$kb= $_REQUEST['l'];
		$kc= $_REQUEST['m'];
		$kd= $_REQUEST['n'];
		$nama= $_REQUEST['nama'];
		
	//	$kd = substr($kd,1,1);
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Objek Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqrykd = "INSERT into ref_rekening (k,l,m,n,o,nm_rekening) values('$ka01','$kb','$kc','$kd','00','$nama')";	
				$cek .= $aqrykd;	
				$qry = mysql_query($aqrykd);
				$content=$kd;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_jurnal set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanKC(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$ka01= $_REQUEST['k'];
		$kb= $_REQUEST['l'];
		$kc= $_REQUEST['m'];
		
		
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Kelompok Belum Di Isi !!';
	if( $err=='' && $kc ==10 ) $err= 'Kode Jenis tidak bisa tambah baru !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_rekening (k,l,m,n,o,nm_rekening) values('$ka01','$kb','$kc','00','00','$nama')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$kc;	
				}
			}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
    /* $kode1= $_REQUEST['k'];	
	 $kode2= $_REQUEST['l'];
	 $kode3= $_REQUEST['m'];
	 $kode4= $_REQUEST['n'];
	 $kode5= $_REQUEST['o'];*/
	// $nama_rekening = $_REQUEST['nama_rekening'];
	 $nama_rekening = $_REQUEST['nama'];
	
	$k= $_REQUEST['fmKA'];
	$l= $_REQUEST['fmKB'];
	$m= $_REQUEST['fmKC'];
	$n= $_REQUEST['fmKD'];
	$o= $_REQUEST['ke'];
	 if( $err=='' && $k =='' ) $err= 'Kode Rekening Belum Di Isi !!';
	 if( $err=='' && $l =='' ) $err= 'Kode Kelompok Belum Di Isi !!';
	 if( $err=='' && $m =='' ) $err= 'Kode Jenis Belum Di Isi !!';
	 if( $err=='' && $n =='' ) $err= 'Kode Objek Belum Di Isi !!';
	 if( $err=='' && $o =='' ) $err= 'Kode Rincian Objek Belum Di Isi !!';
	 if( $err=='' && $nama_rekening =='' ) $err= 'nama rekening Belum Di Isi !!';
	 	
	
	// if($err=='' && $kode_skpd =='' ) $err= 'Kode Skpd belum diisi';	 
	// if(strlen($kode1)!=1 || strlen($kode2)!=1 || strlen($kode3)!=1 || strlen($kode4)!=2 ||strlen($kode5)!=2) $err= 'Format KODE salah';	
			/*for($j=0;$j<5;$j++){
	//urutan kode skpd 	
		if($j==0){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_rekening where k!='0' and l ='0' and m ='0' and n ='00' and o ='00' Order By k DESC limit 1"));
			if($kode1=='0') {$err= 'Format Kode Akun salah';}
			elseif($kode1!=5){ $err= 'Format Kode Akun salah';}
				
		}elseif($j==1){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_rekening where k='".$kode1."' and l !='0' and m ='0' and n ='00' and o ='00' Order By l DESC limit 1"));	
			if ($kode2>sprintf("%02s",$ck['l']+1)) {$err= 'Format Kode Kelompok Belanja Harus berurutan';}		
			
		}elseif($j==2){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_rekening where k='".$kode1."' and l ='".$kode2."' and m !='0' and n ='00' and o ='00' Order By m DESC limit 1"));			
			if ($kode3>sprintf("%02s",$ck['m']+1)) {$err= 'Format Kode Jenis Belanja Salah';}		
				
		}elseif($j==3){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_rekening where k='".$kode1."' and l ='".$kode2."' and m ='".$kode3."' and n!='00' and o='00' Order By n DESC limit 1"));	
			if ($kode4>sprintf("%02s",$ck['n']+1)) {$err= 'Format Kode Objek Belanja Harus berurutan';}
		
		}elseif($j==4){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_rekening where k='".$kode1."' and l ='".$kode2."' and m ='".$kode3."' and n ='".$kode4."' and o!='00' Order By o DESC limit 1"));	
			if ($kode5>sprintf("%02s",$ck['o']+1)) {$err= 'Format Kode SubObjek Belanja Harus berurutan';}
				
				
		}
	 }*/
	 
			
			
			if($fmST == 0){
		//	$ck1=mysql_fetch_array(mysql_query("Select * from ref_rekening where k='$kode1' and l ='$kode2' and m ='$kode3' and n='$kode4' and o='$kode5'"));
		//	if ($ck1>=1)$err= 'Gagal Simpan'.mysql_error();
				if($err==''){
		//			$aqry = "INSERT into ref_rekening (k,l,m,n,o,nm_rekening) values('$kode1','$kode2','$kode3','$kode4','$kode5','$nama_rekening')";	$cek .= $aqry;	
					$aqry = "INSERT into ref_rekening (k,l,m,n,o,nm_rekening) values('$k','$l','$m','$n','$o','$nama_rekening')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_rekening SET nm_rekening='$nama_rekening' WHERE k='$kode1' and l='$kode2' and m='$kode3' and n='$kode4' and o='$kode5'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
						
					}
			} //end else
					
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
		case 'pilihKA':{				
				$fm = $this->pilihKA();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
		
		case 'pilihKB':{				
				$fm = $this->pilihKB();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
			
		case 'pilihKC':{				
				$fm = $this->pilihKC();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}	
			
		case 'pilihKD':{				
				$fm = $this->pilihKD();				
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
		
		case 'formBaruKB':{				
				$fm = $this->setFormBaruKB();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}	
		
		case 'formBaruKC':{				
				$fm = $this->setFormBaruKC();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
		
		case 'formBaruKD':{				
				$fm = $this->setFormBaruKD();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
		
		case 'getKode_o':{
			$get= $this->getKode_o();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		case 'formEdit':{				
			$fm = $this->setFormEdit();				
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
		
		case 'simpanKB':{
				$get= $this->simpanKB();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
		
		case 'simpanKC':{
				$get= $this->simpanKC();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
			
		case 'simpanKD':{
				$get= $this->simpanKD();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
			
		case 'simpanEdit':{
			$get= $this->simpanEdit();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }	
				
		case 'refreshKB':{
				$get= $this->refreshKB();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}	
	   
	    case 'refreshKC':{
				$get= $this->refreshKC();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
			
		case 'refreshKD':{
				$get= $this->refreshKD();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
   
   function setFormBaruKB(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruKB($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruKC(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruKC($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruKD(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruKD($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  function pilihKA(){
	global $Main;	 
	
		$ka = $_REQUEST['fmKA'];
		$kb = $_REQUEST['fmKB'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$queryl="SELECT l, concat(l, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka' and l<>'0' and m = '0' and n='00' and o='00'" ;$cek.=$queryl;
		$content->unit=cmbQuery('fmKB',$fmkb,$queryl,'style="width:210;"onchange="'.$this->Prefix.'.pilihKB()"','&nbsp&nbsp----- Pilih Kode Kelompok -----')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKB()' title='Baru' >";
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKB(){
	global $Main;
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKC="SELECT m, concat(m, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka' and l='$kb' and m <> '0' and n='00' and o='00'" ;//$cek.=$queryKC;
		$content->unit=cmbQuery('fmKC',$fmkc,$queryKC,'style="width:210;"onchange="'.$this->Prefix.'.pilihKC()"','&nbsp&nbsp-------- Pilih Jenis --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKC(){
	global $Main;
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKD="SELECT n, concat(n, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka' and l='$kb' and m = '$kc' and n <> '00' and o='00'" ;$cek.=$queryKD;
		$content->unit=cmbQuery('fmKD',$fmkd,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Sub Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	/*function pilihKD(){
	global $Main;
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$kd = $_REQUEST['fmKD'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKE="SELECT o, concat(o, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka' and l='$kb' and m = '$kc' and n='$kd' and o='00'" ;$cek.=$queryKD;
		$content->unit=cmbQuery('fmKD',$fmkd,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Sub Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}*/
	
	function pilihKD(){
	global $Main;
	
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$kd = $_REQUEST['fmKD'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
	//	$queryKE="SELECT max(o) as o, nm_rekening FROM ref_rekening WHERE k='$ka' and l='$kb' and m = '$kc' and n='$kd' and n <> '0' and o='0'" ;$cek.=$queryKE;
		$queryKE="SELECT max(o) as o, nm_rekening FROM ref_rekening WHERE k='$ka' and l='$kb' and m = '$kc' and n='$kd'" ;$cek.=$queryKE;
		/*$content->unit=cmbQuery('fmKE',$fmke,$queryKE,'style="width:210;"onchange="'.$this->Prefix.'.pilihKE()"','&nbsp&nbsp-------- Pilih Sub Rincian Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";$cek.=$queryJenis;*/
	 
		$get=mysql_fetch_array(mysql_query($queryKE));
		$lastkode=$get['o'] + 1;	
		$kode_o = sprintf("%02s", $lastkode);
		$content->ke=$kode_o;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function refreshKB(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kbnew= $_REQUEST['id_KBBaru'];
	 
		$queryKB="SELECT l, concat(l, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l <> '0' and m='0' and n='00' and o='00'" ;
		//$cek.="SELECT kb,nm_account FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'";
		$cek.="SELECT l, concat(l, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l <> '0' and m='0' and n='00' and o='00'";
		$content->unit=cmbQuery('fmKB',$kbnew,$queryKB,'style="width:210;"onchange="'.$this->Prefix.'.pilihKB()"','&nbsp&nbsp-------- Pilih Kelompok -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKB()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshKC(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kcnew= $_REQUEST['id_KCBaru'];
	 
		$queryKC="SELECT m, concat(m, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m<>'0' and n='00' and o='00'" ;
		//$cek.="SELECT kb,nm_account FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'";
		$cek.="SELECT m, concat(m, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l='$kc02' and m<>'0' and n='00' and o='00'";
		$content->unit=cmbQuery('fmKC',$kcnew,$queryKC,'style="width:210;"onchange="'.$this->Prefix.'.pilihKC()"','&nbsp&nbsp-------- Pilih Jenis -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshKD(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
		$kd02 = $_REQUEST['fmKD'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kdnew= $_REQUEST['id_KDBaru'];
	 
	 $queryKD="SELECT n, concat(n,' . ', nm_rekening) as vnama  FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m='$kc02' and n<>'00' and o='00'" ;
	 
		$koden=$queryKD['kd'];
		$new = sprintf("%02s", $koden);
		$kode_n=$new.".".$queryKD['nm_rekening'];
	
		$content->unit=cmbQuery('fmKD',$kdnew,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Objek -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function getKode_o(){
	 global $Main;
	 
	 	$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
		$kd02 = $_REQUEST['fmKD'];
		$ke02 = $_REQUEST['fmKE'];
	//	$ke02 = $_REQUEST['ke'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kenew= $_REQUEST['id_KEBaru'];
	 
	 	$aqry5="SELECT MAX(o) AS maxno FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m='$kc02' and n='$kd02'";
	 	$cek.="SELECT MAX(o) AS maxno FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m='$kc02' and n='$kd02'";
		$get=mysql_fetch_array(mysql_query($aqry5));
		$newke=$get['maxno'] + 1;
		$newke1 = sprintf("%02s", $newke);
		$content->ke=$newke1;	
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
		
	/*function refreshKD(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
		$kd02 = $_REQUEST['fmKD'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kdnew= $_REQUEST['id_KDBaru'];
	 
		$queryKD="SELECT n, concat(n, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m'$kc02' and n<>'00' and o='00'" ;
		//$cek.="SELECT kb,nm_account FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'";
		$cek.="SELECT n, concat(n, '. ', nm_rekening) as vnama FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m='$kc02' and n<>'00' and o='00'";
		$content->unit=cmbQuery('fmKD',$kdnew,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Objek -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	*/
	
	function BaruKB($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKB';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode Kelompok';
		$nip	 = '';
		$KA1 = $_REQUEST ['fmKA'];
			
		$aqry2="SELECT MAX(l) AS maxno FROM ref_rekening WHERE k='$KA1'";
		//$cek.="SELECT MAX(l) AS maxno FROM ref_rekening WHERE k='$KA1'";
		$get=mysql_fetch_array(mysql_query($aqry2));
//		$lastkode=$get['maxno'] + 1;
		$newkb=$get['maxno'] + 1;
		
	//	 if( $err=='' && $k =='' ) $err= 'Kode Rekening Belum Di Isi !!';
		/*$kode = (int) substr($lastkode, 1, 3);
		$kode++;
		$no_ba = sprintf("%1s", $kode);*/
		$queryKA1=mysql_fetch_array(mysql_query("SELECT k, nm_rekening FROM ref_rekening where l=0 and m=0 and n=00 and o=00"));  

		$datak1=$queryKA1['k'].".".$queryKA1['nm_rekening'];
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'Kelompok' => array( 
						'label'=>'Kode Akun',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='rekening' id='rekening' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='k' id='k' value='".$queryKA1['k']."'>
						</div>", 
						 ),	
									 			
			'kode_kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='l' id='l' value='".$newkb."' style='width:50px;' readonly>
					
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Kelompok' style='width:200px;'>
						</div>", 
						 ),		
			
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanKB()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close2()' >";
							
		$form = $this->genFormKB();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormKB($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_KBform';	
		
		if($withForm){
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);
		}
		
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
	function BaruKD($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKD';				
	 $this->form_width = 500;
	 $this->form_height = 120;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode Objek';
		$nip	 = '';
		$KA1 = $_REQUEST['fmKA'];
		$KB1 = $_REQUEST['fmKB'];
		$KC1 = $_REQUEST['fmKC'];
		$KD1 = $_REQUEST['fmKD'];
		
	
		$aqry4="SELECT MAX(n) AS maxno FROM ref_rekening WHERE k='$KA1' and l='$KB1' and m='$KC1'";
		$cek.="SELECT MAX(n) AS maxno FROM ref_rekening WHERE k='$KA1' and l='$KB1' and m='$KC1'";
		$get=mysql_fetch_array(mysql_query($aqry4));

		$newkm=$get['maxno'] + 1;
		$newkm1 = sprintf("%02s", $newkm);
		$queryKA1=mysql_fetch_array(mysql_query("SELECT k, nm_rekening FROM ref_rekening where l=0 and m=0 and n=00 and o=00"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT l, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m=0 and n=00 and o=00"));  
		$queryKC1=mysql_fetch_array(mysql_query("SELECT m, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m='$KC1' and n=00 and o=00"));  
		$cek.="SELECT m, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m='$KC1' and n=00 and o=00";
//		
		$datak1=$queryKA1['k'].".".$queryKA1['nm_rekening'];
		$datak2=$queryKB1['l'].".".$queryKB1['nm_rekening'];
		$datak3=$queryKC1['m'].".".$queryKC1['nm_rekening'];
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'kode_akun' => array( 
						'label'=>'Kode Akun',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Akun' id='Akun' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='k' id='k' value='".$queryKA1['k']."'>
						</div>", 
						 ),	
			
			'kode_Kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						
						<input type ='hidden' name='l' id='l' value='".$queryKB1['l']."'>
						</div>", 
						 ),	
						 
			'kode_jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='jenis' id='jenis' value='".$datak3."' style='width:255px;' readonly>
						<input type ='hidden' name='m' id='m' value='".$queryKC1['m']."'>
						</div>", 
						 ),				 
									 		
			'kode_objek' => array( 
						'label'=>'Kode Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='n' id='n' value='".$newkm1."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Objek' style='width:200px;'>
						</div>", 
						 ),		
						 
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanKD()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close4()' >";
							
		$form = $this->genFormKD();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormKD($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_KDform';	
		
		if($withForm){
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);
		}
		
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
	
	
	function BaruKC($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKC';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode Jenis';
		$nip	 = '';
		$KA1 = $_REQUEST['fmKA'];
		$KB1 = $_REQUEST['fmKB'];
		$KC1 = $_REQUEST['fmKC'];
		
	
		$aqry3="SELECT MAX(m) AS maxno FROM ref_rekening WHERE k='$KA1' and l='$KB1'";
		$cek.="SELECT MAX(m) AS maxno FROM ref_rekening WHERE k='$KA1' and l='$KB1'";
		$get=mysql_fetch_array(mysql_query($aqry3));

		$newkm=$get['maxno'] + 1;
		$queryKA1=mysql_fetch_array(mysql_query("SELECT k, nm_rekening FROM ref_rekening where l=0 and m=0 and n=00 and o=00"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT l, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m=0 and n=00 and o=00"));  
		$cek.="SELECT l, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m=0 and n=00 and o=00";
//		
		$datak1=$queryKA1['k'].".".$queryKA1['nm_rekening'];
		$datak2=$queryKB1['l'].".".$queryKB1['nm_rekening'];
	//	$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
	//	$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'kode_akun' => array( 
						'label'=>'Kode Akun',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Akun' id='Akun' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='k' id='k' value='".$queryKA1['k']."'>
						</div>", 
						 ),	
			
			'kode_Kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						<input type ='hidden' name='l' id='l' value='".$queryKB1['l']."'>
						</div>", 
						 ),	
									 			
			
			
			'kode_jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='m' id='m' value='".$newkm."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Kelompok' style='width:200px;'>
						</div>", 
						 ),		
						 
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanKC()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close3()' >";
							
		$form = $this->genFormKC();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormKC($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_KCform';	
		
		if($withForm){
			$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);
		}
		
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
					</script>";
		return 
			 "<script src='js/skpd.js' type='text/javascript'></script>
			 <script type='text/javascript' src='js/master/ref_rekening/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
			 ".
			// "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	/*function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}*/
	
	function setFormBaru(){
		//$cbid = $_REQUEST[$this->Prefix.'_cb'];
		//$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		//$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		//$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$dt['readonly']='';
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];
		if(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_jurnal']=$fmBIDANG.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_jurnal']=$fmBIDANG.'.'.$fmKELOMPOK.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_jurnal']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.';
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$dt['kode_jurnal']=$fmBIDANG.'.'.$fmKELOMPOK.'.'.$fmSUBKELOMPOK.'.'.$fmSUBSUBKELOMPOK.'.';
		}
		$fm = $this->setForm($dt);		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$k=$kode[0];
		$l=$kode[1];
		$m=$kode[2];
		$n=$kode[3];
		$o=$kode[4];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_rekening WHERE k='$k' and l='$l' and m='$m' and n='$n' and o='$o' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setFormEditdata($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormEditdata($dt){	
	 global $SensusTmp ,$Main;
	 
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 490;
	 $this->form_height = 150;
	  if ($this->form_fmST==1) {
		$this->form_caption = 'FORM EDIT KODE Rekening';
	  }
	 
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;	
		$k=$kode[0];
		$l=$kode[1];
		$m=$kode[2];
		$n=$kode[3];
		$o=$kode[4];
		
		
		
		$queryKAedit=mysql_fetch_array(mysql_query("SELECT k, nm_rekening FROM ref_rekening WHERE k='$k' and l = '0' and m='0' and n='00' and o='00'")) ;
		$cek.=$queryKAedit;
		$queryKBedit=mysql_fetch_array(mysql_query("SELECT l, nm_rekening FROM ref_rekening WHERE k='$k' and l='$l' and m= '0' and n='00' and o='00'")) ;
		$queryKCedit=mysql_fetch_array(mysql_query("SELECT m, nm_rekening FROM ref_rekening WHERE k='$k' and l='$l' and m='$m' and n='00' and o='00'")) ;
		$queryKDedit=mysql_fetch_array(mysql_query("SELECT n, nm_rekening FROM ref_rekening WHERE k='$k' and l='$l' and m='$m' and n='$n' and o='00'")) ;
		$queryKEedit=mysql_fetch_array(mysql_query("SELECT o, nm_rekening FROM ref_rekening WHERE k='$k' and l='$l' and m='$m' and n='$n' and o='$o'")) ;
	//	$cek.="SELECT ke, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='$data_ke' and kf='0'";
					
	
		$datka=$queryKAedit['k'].".  ".$queryKAedit['nm_rekening'];
		$datkb=$queryKBedit['l'].". ".$queryKBedit['nm_rekening'];
		$datkc=$queryKCedit['m']." .  ".$queryKCedit['nm_rekening'];
		$datkd=$queryKDedit['n'].". ".$queryKDedit['nm_rekening'];
		$datke=$queryKEedit['o'];
	//	$datke=sprintf("%02s",$queryKEedit['ke'])." .  ".$queryKEedit['nm_account'];
		
       //items ----------------------
		  $this->form_fields = array(
		  
		  'kode_Akun' => array( 
						'label'=>'kode Rekening',
						'labelWidth'=>120, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ek' id='ek' value='".$datka."' style='width:270px;' readonly>
						<input type ='hidden' name='k' id='k' value='".$queryKAedit['k']."'>
						</div>", 
						 ),
			'kode_kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='el' id='el' value='".$datkb."' style='width:270px;' readonly>
						<input type ='hidden' name='l' id='l' value='".$queryKBedit['l']."'>
						</div>", 
						 ),
			'kode_Jenis' => array( 
						'label'=>'kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='em' id='em' value='".$datkc."' style='width:270px;' readonly>
						<input type ='hidden' name='m' id='m' value='".$queryKCedit['m']."'>
						</div>", 
						 ),
			'kode_Objek' => array( 
						'label'=>'kode Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='en' id='en' value='".$datkd."' style='width:270px;' readonly>
						<input type ='hidden' name='n' id='n' value='".$queryKDedit['n']."'>
						</div>", 
						 ),
			'Kode_Rincian_Objek' => array( 
						'label'=>'Kode Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='eo' id='eo' value='".$datke."' style='width:20px;' readonly>
						<input type ='hidden' name='o' id='o' value='".$queryKEedit['o']."'>
						<input type='text' name='nm_rekening' id='nm_rekening' value='".$dt['nm_rekening']."' size='36px'>
						</div>", 
						 ),			 			 			 
						 			 
		 
			
			/*'Nama' => array( 
						'label'=>'Nama',
						//'id'=>'cont_object',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='nm_account' id='nm_account' value='".$dt['nm_account']."' size='40px'>
						</div>", 
						 ),		*/				 
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanEdit()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			"<input type='hidden' name='ka' id='ka' value='".$dt['ka']."'>".
			"<input type='hidden' name='kb' id='kb' value='".$dt['kb']."'>".
			"<input type='hidden' name='kc' id='kc' value='".$dt['kc']."'>".
			"<input type='hidden' name='kd' id='kd' value='".$dt['kd']."'>".
			"<input type='hidden' name='ke' id='ke' value='".$dt['ke']."'>".
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function setForm($dt){	
	 global $SensusTmp ,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	$this->form_width = 490;
	 $this->form_height = 150;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'FORM BARU KODE REKENING';
	//	$nip	 = '';
	  }/*else{
		$this->form_caption = 'Edit';			
		$readonly='readonly';
					
	  }*/
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		$fmKA = $_REQUEST['fmka'];
		$fmKB = $_REQUEST['fmkb'];
		$fmKC = $_REQUEST['fmkc'];
		$fmKD = $_REQUEST['fmkd'];
		$fmKE = $_REQUEST['fmke'];
		$fmKF = $_REQUEST['fmkf'];
		
		/*$kode1=genNumber($dt['k'],1);
		$kode2=genNumber($dt['l'],1);
		$kode3=genNumber($dt['m'],1);
		$kode4=genNumber($dt['n'],2);
		$kode5=genNumber($dt['o'],2);
		$nama_rekening=$dt['nm_rekening'];*/
		$queryKA="SELECT k, concat(k, '. ', nm_rekening) as vnama FROM ref_rekening where l=0 and m=0 and n=00 and o=00"; 
		$queryKB="SELECT l,nm_rekening FROM ref_rekening where k='$fmKA' and  m=0 and n=00 and o=00";
		$queryKC="SELECT m,nm_rekening FROM ref_rekening where k='$fmKA' and  l='$fmKB' and n=00 and o=00";
		$queryKD="SELECT n,nm_rekening FROM ref_rekening where k='$fmKA' and  l='$fmKB' and m='$fmKC' and o=00";
		$queryKE="SELECT o,nm_rekening FROM ref_rekening where k='$fmKA' and  l='$fmKB' and m='$fmKC' and o='$fmKD'";
	 //items ----------------------
	  $this->form_fields = array(
			
			'kode_Akun' => array( 
						'label'=>'Kode Rekening',
						'labelWidth'=>150, 
						'value'=>
						"<div id='cont_ka'>".cmbQuery('fmKA',$ka,$queryKA,'style="width:210;"onchange="'.$this->Prefix.'.pilihKA()"','-------- Pilih Kode Rekening ------------------')."</div>",
						 ),	
						 	
			'kode_kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_kb'>".cmbQuery('fmKB',$kb,$queryKB,'style="width:210;"onchange="'.$this->Prefix.'.pilihKB()"','-------- Pilih Kode Kelompok ------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKB()' title='Kode Kelompok' ></div>",
						 ),
			
			'kode_Jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_kc'>".cmbQuery('fmKC',$kc,$queryKC,'style="width:210;"onchange="'.$this->Prefix.'.pilihKC()"','-------- Pilih Kode Jenis --------------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='kode jenis' ></div>",
						 ),	
			
			'kode_Objek' => array( 
						'label'=>'Kode Objek',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_kd'>".cmbQuery('fmKD',$kd,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','-------- Pilih Kode Objek ---------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Kode Objek' ></div>",
						 ),		
			'Kode_Rincian_Objek' => array( 
						'label'=>'Kode Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ke' id='ke' value='".$newke."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Rincian Objek' style='width:220px;'>
						</div>", 
						 ),		
			/*'Kode_Rincian_Objek' => array( 
						'label'=>'Kode Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ke' id='ke' value='".$newke."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Rincian Objek' style='width:220px;'>
						</div>", 
						 ),	*/
						 			 
			/*'1kode' => array( 
						'label'=>'Kode Rekening',
						'labelWidth'=>100, 
						//'value'=>$dt['kode'],
						//'type'=>'text',
						'value'=>
						"<input type='text' name='k' id='k' size='5' maxlength='1' value='".$kode1."' $readonly>&nbsp
						<input type='text' name='l' id='l' size='5' maxlength='1' value='".$kode2."' $readonly>&nbsp
						<input type='text' name='m' id='m' size='5' maxlength='1' value='".$kode3."' $readonly>&nbsp
						<input type='text' name='n' id='n' size='5' maxlength='2' value='".$kode4."' $readonly>&nbsp
						<input type='text' name='o' id='o' size='5' maxlength='2' value='".$kode5."' $readonly>"
						 ),
			
			'nama' => array( 
						'label'=>'Nama Rekening',
						'labelWidth'=>100, 
						
						
						'value'=>"<input type='text' name='nama_rekening' id='nama_rekening' size='50' maxlength='100' value='".$nama_rekening."'>",
						'row_params'=>"valign='top'",
						'type'=>'' 
									 ),*/
			);
		//tombol
		$this->form_menubawah =
			
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	/*function setPage_HeaderOther(){
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=ref_skpd\" title='Skpd'  >Skpd</a> |	
	<A href=\"pages.php?Pg=ref_rekening\" title='Rekening' style='color:blue'  >Rekening</a> |
	<A href=\"pages.php?Pg=ref_satuan\" title='Satuan'  >Satuan</a> |
	<A href=\"pages.php?Pg=ref_kepala_skpd\" title='Kepala Skpd'  >Kepala Skpd</a> |
	<A href=\"pages.php?Pg=ref_pengesahan\" title='Pengesahan'   >Pengesahan</a> |
	<A href=\"pages.php?Pg=ref_tapd\" title='Tapd'   >Tapd</a> |
	<A href=\"pages.php?Pg=ref_program\" title='Program & Kegiatan'   >Program & Kegiatan</a> |
	<A href=\"pages.php?Pg=ref_sumber_dana\" title='Sumber Dana'   >Sumber Dana</a> |
	
	</td></tr></table>";
	"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>";
	
	}*/
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='400' colspan='5'>Kode</th>
	   <th class='th01' width='900' align='cente'>Nama</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="center"',genNumber($isi['k'],1));
	 $Koloms[] = array('align="center"',genNumber($isi['l'],1));
	 $Koloms[] = array('align="center"',genNumber($isi['m'],1));
	 $Koloms[] = array('align="center"',genNumber($isi['n'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['o'],2));
	 $Koloms[] = array('align="left"',$isi['nm_rekening']);
	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 
		
	$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmBARANG = cekPOST('fmBARANG');			
	//$fmPILCARI = $_REQUEST['fmPILCARI'];	
	//$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//$fmORDER1 = cekPOST('fmORDER1');
	//$fmDESC1 = cekPOST('fmDESC1');
	
	
	 $arr = array(
			//array('selectAll','Semua'),
			array('selectfg','Kode Barang'),
			array('selectbarang','Nama Barang'),	
			);
		
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Barang'),
			     array('2','Nama Barang'),	
	 );	
				
	$TampilOpt = 
			//"<tr><td>".	
			"<div class='FilterBar'>".
			//<table style='width:100%'><tbody><tr><td align='left'>
			//<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			//<tbody><tr valign='middle'>   						
			//	<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			
			"<table style='width:100%'>
			<tr>
			<td style='width:150px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select k,nm_rekening from ref_rekening where k!='0' and l ='0' and m = '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select l,nm_rekening from ref_rekening where k='$fmBIDANG' and l !='0' and m = '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select m,nm_rekening from ref_rekening where k='$fmBIDANG' and l ='$fmKELOMPOK' and m != '0' and n='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select n,nm_rekening from ref_rekening where k='$fmBIDANG' and l ='$fmKELOMPOK' and m = '$fmSUBKELOMPOK' and n!='00' and o='00'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";	
			"";
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
	 	$fmBIDANG = cekPOST('fmBIDANG');
	    $fmKELOMPOK = cekPOST('fmKELOMPOK');
		$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
		$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
		$fmKODE = cekPOST('fmKODE');
		$fmBARANG = cekPOST('fmBARANG');
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			//case 'selectKode': $arrKondisi[] = " c='".$isivalue[0]."' and d='".$isivalue[1]."' and e='".$isivalue[2]."' and e1='".$isivalue[3]."'"; break;
			case 'selectKode': $arrKondisi[] = " concat(k,'.',l,'.',m,'.',n,'.',o) like '$fmPILCARIvalue%'"; break;
			case 'selectNama': $arrKondisi[] = " nm_rekening like '%$fmPILCARIvalue%'"; break;	
				
								 	
		}	
		/*if($fmBIDANG!='00' and $fmBIDANG !='')$arrKondisi[]= "k='$fmBIDANG'";
		if($fmKELOMPOK!='00' and $fmKELOMPOK !='')$arrKondisi[]= "l='$fmKELOMPOK'";
		if($fmSUBKELOMPOK!='00' and $fmSUBKELOMPOK !='')$arrKondisi[]= "m='$fmSUBKELOMPOK'";
		if($fmSUBSUBKELOMPOK!='00' and $fmSUBSUBKELOMPOK !='')$arrKondisi[]= "n='$fmSUBSUBKELOMPOK'";*/
		
		
		if(empty($fmBIDANG)) {
			$fmKELOMPOK = '';
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmKELOMPOK)) {
			$fmSUBKELOMPOK='';
			$fmSUBSUBKELOMPOK='';
		}
		if(empty($fmSUBKELOMPOK)) {		
			$fmSUBSUBKELOMPOK='';
		}		
		
		if(empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			//$arrKondisi[]= "f !=00 and g=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "k =$fmBIDANG"; //$arrKondisi[]= "f =$fmBIDANG and g!=00 and h=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "k =$fmBIDANG and l=$fmKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h!=00 and i=00 and j=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "k =$fmBIDANG and l=$fmKELOMPOK and m=$fmSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i!=00 and j=00";				
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "k =$fmBIDANG and l=$fmKELOMPOK and m=$fmSUBKELOMPOK and n=$fmSUBSUBKELOMPOK";//$arrKondisi[]= "f =$fmBIDANG and g=$fmKELOMPOK and h=$fmSUBKELOMPOK and i=$fmSUBSUBKELOMPOK and j!=00";			
		}
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(k,'.',l,'.',m,'.',n,'.',o) like '".$_POST['fmKODE']."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_rekening like '%".$_POST['fmBARANG']."%'";
		/*$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		//Cari 
		switch($fmPILCARI){			
			case 'selectNama': $arrKondisi[] = " nama_pasien like '%$fmPILCARIvalue%'"; break;
			case 'selectAlamat': $arrKondisi[] = " alamat like '%$fmPILCARIvalue%'"; break;						 	
		}
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_daftar>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_daftar<='$fmFiltTglBtw_tgl2'";	*/
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " k,l,m,n,o $Asc1 " ;break;
			case '2': $arrOrders[] = " nm_skpd $Asc1 " ;break;
			
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
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
$ref_rekening = new ref_rekeningObj();
?>