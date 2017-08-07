<?php

class ref_skpdObj  extends DaftarObj2{	
	var $Prefix = 'ref_skpd';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_skpd'; //daftar 
	var $TblName_Hapus = 'ref_skpd';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('c1','c','d','e','e1');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'SKPD';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ref_skpdForm'; 
	var $kdbrg = '';	
			
	function setTitle(){
		return 'SKPD';
	}
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		//$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		//$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		//$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";*/
	}	
	
	function simpanUrusan(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$dtc1= $_REQUEST['c1'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Bidang Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_skpd (c1,c,d,e,e1,nm_skpd,nm_barcode) values('$dtc1','00','00','00','000','$nama','-')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$dtc1;	
				}
			}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanBidang(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$dtc1= $_REQUEST['c1'];
		$dtc= $_REQUEST['c'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Bidang Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_skpd (c1,c,d,e,e1,nm_skpd,nm_barcode) values('$dtc1','$dtc','00','00','000','$nama','-')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$dtc;	
				}
			}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanSKPD(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$dtc1= $_REQUEST['c1'];
		$dtc= $_REQUEST['c'];
		$dtd= $_REQUEST['d'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Bidang Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_skpd (c1,c,d,e,e1,nm_skpd,nm_barcode) values('$dtc1','$dtc','$dtd','00','000','$nama','-')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$dtd;	
				}
			}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanUnit(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$dtc1= $_REQUEST['c1'];
		$dtc= $_REQUEST['c'];
		$dtd= $_REQUEST['d'];
		$dte= $_REQUEST['e'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Unit Belum Di Isi !!';
		
			if($err==''){
				$aqrykd = "INSERT into ref_skpd (c1,c,d,e,e1,nm_skpd,nm_barcode) values('$dtc1','$dtc','$dtd','$dte','000','$nama','-')";	
				$cek .= $aqrykd;	
				$qry = mysql_query($aqrykd);
				$content=$dte;	
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
	
	$dk= $_REQUEST['c1'];
	$dl= $_REQUEST['c'];
	$dm= $_REQUEST['d'];
	$dn= $_REQUEST['e'];
	$do= $_REQUEST['e1'];
	$nama= $_REQUEST['nm_skpd'];
	$barcode= $_REQUEST['nm_barcode'];
	

	//$ke = substr($ke,1,1);
	
								
	if($err==''){						
		
	$aqry = "UPDATE ref_skpd set c1='$dk',c='$dl',d='$dm',e='$dn',e1='$do',nm_skpd='$nama',nm_barcode='$barcode' where concat (c1,' ',c,' ',d,' ',e,' ',e1)='".$idplh."'";$cek .= $aqry;
						$qry = mysql_query($aqry);
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
	 $kode0 = $_REQUEST['fmc1'];
     $kode1= $_REQUEST['fmc'];
	 $kode2= $_REQUEST['fmd'];
	 $kode3= $_REQUEST['fme'];
	 $kode4= $_REQUEST['e1'];
	 $nama_skpd = $_REQUEST['nama'];
	 $nama_barcode = $_REQUEST['barcode'];
	 
		
	 
	
	 if( $err=='' && $kode0 =='' ) $err= 'Kode Urusan Belum Di Isi !!';
	 if( $err=='' && $kode1 =='' ) $err= 'Kode Bidang Belum Di Isi !!';
	 if( $err=='' && $kode2 =='' ) $err= 'Kode SKPD Belum Di Isi !!';
	 if( $err=='' && $kode3 =='' ) $err= 'Kode UNIT Belum Di Isi !!';
	 if( $err=='' && $kode4 =='' ) $err= 'Kode SUB UNIT Belum Di Isi !!';
	 if( $err=='' && $nama_skpd =='' ) $err= 'nama skpd Belum Di Isi !!';
	 if( $err=='' && $nama_barcode =='') $err= 'nama barcode Belum Di Isi !!';
	 	
	
	 
			if($fmST == 0){
			$ck1=mysql_fetch_array(mysql_query("Select * from ref_skpd where c1= '$kode0' and c='$kode1' and d ='$kode2' and e ='$kode3' and e1='$kode4'"));
			if ($ck1>=1)$err= 'Gagal Simpan'.mysql_error();
				if($err==''){
					$aqry = "INSERT into ref_skpd (c1,c,d,e,e1,nm_skpd,nm_barcode) values('$kode0','$kode1','$kode2','$kode3','$kode4','$nama_skpd','$nama_barcode')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_skpd SET nm_skpd='$nama_skpd', nm_barcode='$nama_barcode' WHERE c1='$kode0' and c='$kode1' and d='$kode2' and e='$kode3' and e1='$kode4'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
			
					}
			
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function refreshUrusan(){
	global $Main;
	 
		$kc102 = $_REQUEST['fmc1'];	 
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kc1new= $_REQUEST['id_UrusanBaru'];
	 
		$queryKc1="SELECT c1, concat(c1, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1<>'00' and c = '00' and d='00' and e='00' and e1='000'" ;
	
	//	$cek.="SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$kc102' and c <> '00' and d='00' and e='00' and e1='000'";
		$content->unit=cmbQuery('fmc1',$kc1new,$queryKc1,'style="width:270;"onchange="'.$this->Prefix.'.pilihUrusan()"','&nbsp&nbsp-------- Pilih Urusan -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruUrusan()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshBidang(){
	global $Main;
	 
		$kc102 = $_REQUEST['fmc1'];	 
		$kc02 = $_REQUEST['fmc'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kcnew= $_REQUEST['id_BidangBaru'];
	 
		$queryKc="SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$kc102' and c <> '00' and d='00' and e='00' and e1='000'" ;
	
		$cek.="SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$kc102' and c <> '00' and d='00' and e='00' and e1='000'";
		$content->unit=cmbQuery('fmc',$kcnew,$queryKc,'style="width:270;"onchange="'.$this->Prefix.'.pilihBidang()"','&nbsp&nbsp-------- Pilih Bidang -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruBidang()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshSKPD(){
	global $Main;
	 
		$kc102 = $_REQUEST['fmc1'];	 
		$kc02 = $_REQUEST['fmc'];
		$kd02 = $_REQUEST['fmd'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kdnew= $_REQUEST['id_SKPDBaru'];
	 
		$queryKd="SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$kc102' and c='$kc02' and d<>'00' and e='00' and e1='000'" ;
	
		$cek.="SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$kc102' and c='$kc02' and d<>'00' and e='00' and e1='000'";
		$content->unit=cmbQuery('fmd',$kdnew,$queryKd,'style="width:270;"onchange="'.$this->Prefix.'.pilihSKPD()"','&nbsp&nbsp-------- Pilih SKPD -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSKPD()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshUnit(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmc1'];	 
		$kb02 = $_REQUEST['fmc'];
		$kc02 = $_REQUEST['fmd'];
		$kd02 = $_REQUEST['fme'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kdnew= $_REQUEST['id_UnitBaru'];
	 
	 $queryKD="SELECT e, concat(e,' . ', nm_skpd) as vnama  FROM ref_skpd WHERE c1='$ka02' and c='$kb02' and d='$kc02' and e<>'00' and e1='000'" ;
	 $cek.="SELECT e, concat(e,' . ', nm_skpd) as vnama  FROM ref_skpd WHERE c1='$ka02' and c='$kb02' and d='$kc02' and e<>'00' and e1='000'";
	 
		$koden=$queryKD['e'];
		$new = sprintf("%02s", $koden);
		$kode_n=$new.".".$queryKD['nm_skpd'];
	
		$content->unit=cmbQuery('fme',$kdnew,$queryKD,'style="width:270;"onchange="'.$this->Prefix.'.pilihUnit()"','&nbsp&nbsp-------- Pilih UNIT -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruUnit()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
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
		
		case 'pilihUrusan':{				
				$fm = $this->pilihUrusan();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
			
		case 'pilihBidang':{				
				$fm = $this->pilihBidang();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
		case 'pilihSKPD':{				
				$fm = $this->pilihSKPD();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}
			
		case 'pilihUnit':{				
				$fm = $this->pilihUnit();				
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
		
		case 'BaruUrusan':{				
				$fm = $this->setFormBaruUrusan();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}	
		
		case 'BaruBidang':{				
				$fm = $this->setFormBaruBidang();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}	
		case 'BaruSKPD':{				
				$fm = $this->setFormBaruSKPD();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
			break;
			}	
		
		case 'BaruUnit':{				
				$fm = $this->setFormBaruUnit();				
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
		
		case 'getKode_e1':{
			$get= $this->getKode_e1();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
			
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
	   
	   case 'simpanUrusan':{
				$get= $this->simpanUrusan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    }
	   
	   case 'simpanBidang':{
				$get= $this->simpanBidang();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    }
		
		case 'simpanSKPD':{
				$get= $this->simpanSKPD();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    }
	   
	   case 'simpanUnit':{
				$get= $this->simpanUnit();
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
	   
	   case 'refreshUrusan':{
				$get= $this->refreshUrusan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
	   
	   case 'refreshBidang':{
				$get= $this->refreshBidang();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
			
		 case 'refreshSKPD':{
				$get= $this->refreshSKPD();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}
			
		case 'refreshUnit':{
				$get= $this->refreshUnit();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}			
	   
	   case 'hapus':{	
				$fm= $this->Hapus($pil);
				$err= $fm['err']; 
				$cek = $fm['cek'];
				$content = $fm['content'];
			//}
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
   
   function Hapus($ids){ //validasi hapus tbl_sppd
		 $err=''; $cek='';
		 $cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		
		if ($err ==''){
			
		for($i = 0; $i<count($ids); $i++){
		$idplh1 = explode(" ",$ids[$i]);
		$data_c1= $idplh1[0];
	 	$data_c= $idplh1[1];
		$data_d= $idplh1[2];
		$data_e= $idplh1[3];
		$data_e1= $idplh1[4];
		
		
		if ($data_c1 != '0'){
			$sk1="select c1,c,d,e,e1 from ref_skpd where c1='$data_c1' and c!='00'";
		}
		
		if ($data_c != '00'){
			$sk1="select c1,c,d,e,e1 from ref_skpd where c1='$data_c1' and c='$data_c' and d!='00'";
		}
		
		if ($data_d != '00'){
			$sk1="select c1,c,d,e,e1 from ref_skpd where c1='$data_c1'  and c='$data_c' and d='$data_d' and e!='00'";
		}
		if ($data_e != '00'){
			$sk1="select c1,c,d,e,e1 from ref_skpd where c1='$data_c1'  and c='$data_c' and d='$data_d' and e='$data_e' and e1!='000'";
		}
	//	$err='tes';
		if ($data_e1=='000'){
			$qrycek=mysql_query($sk1);$cek.=$sk1;
			if(mysql_num_rows($qrycek)>0)$err='data tidak bisa di hapus';
		}
		
		
		if($err=='' ){
					$qy = "DELETE FROM ref_skpd WHERE c1='$data_c1' and c='$data_c' and d='$data_d'  and  e='$data_e' and e1='$data_e1' and  concat (c1,' ',c,' ',d,' ',e,' ',e1) ='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					
			}else{
				break;
			}			
		}
		}
		return array('err'=>$err,'cek'=>$cek);
	}	  
   
	function getKode_e1(){
	 global $Main;
	 
	 	$ka02 = $_REQUEST['fmc1'];	 
		$kb02 = $_REQUEST['fmc'];
		$kc02 = $_REQUEST['fmd'];
		$kd02 = $_REQUEST['fme'];
		$ke02 = $_REQUEST['fme1'];
	//	$ke02 = $_REQUEST['ke'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kenew= $_REQUEST['id_UnitBaru'];
	 
	 	$aqry5="SELECT MAX(e1) AS maxno FROM ref_skpd WHERE c1='$ka02' and c='$kb02' and d='$kc02' and e='$kd02'";
	 //	$cek.="SELECT MAX(o) AS maxno FROM ref_rekening WHERE k='$ka02' and l='$kb02' and m='$kc02' and n='$kd02'";
		$get=mysql_fetch_array(mysql_query($aqry5));
		$newke=$get['maxno'] + 1;
		$newke1 = sprintf("%03s", $newke);
		$content->e1=$newke1;	
	
	/* $get1=mysql_fetch_array(mysql_query($aqry5));
		$lastkode1=$get1['maxno'];
		$kode1 = (int) substr($lastkode1, 1, 3);
		$kode1++;
		$$newke1 = sprintf("%03s", $kode1);
	 $content->e1=$newke1;*/
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setFormBaruUrusan(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruUrusan($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruBidang(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruBidang($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruSKPD(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruSKPD($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruUnit(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruUnit($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
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
			 <script type='text/javascript' src='js/master/ref_skpd/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
			 ".
			// "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_skpd = explode(' ',$id);
		$c=$kode_skpd[0];	
		$d=$kode_skpd[1];
		$e=$kode_skpd[2];	
		$e1=$kode_skpd[3];
		if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where c='$c' and d='$d' and e='$e' and e1='$e1' ")
				) >0 )
			{ $errmsg = 'Gagal Hapus! SKPD Sudah ada di Buku Induk!';}
		return $errmsg;
	}
	//form ==================================
	
	function pilihUrusan(){
	global $Main;	 
	
		$c1 = $_REQUEST['fmc1'];
		
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$queryc="SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$c1' and c<>'00' and d = '00' and e='00' and e1='000'" ;$cek.=$queryc;
		$content->unit=cmbQuery('fmc',$fmc,$queryc,'style="width:270;"onchange="'.$this->Prefix.'.pilihBidang()"','-------- Pilih Kode BIDANG ------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruBidang()' title='Baru' >";
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihBidang(){
	global $Main;
		$c1 = $_REQUEST['fmc1'];
		$c = $_REQUEST['fmc'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryd="SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd WHERE c1='$c1' and c='$c' and d <> '00' and e='00' and e1='000'" ;$cek.=$queryd;
		$content->unit=cmbQuery('fmd',$fmd,$queryd,'style="width:270;"onchange="'.$this->Prefix.'.pilihSKPD()"','-------- Pilih Kode SKPD --------------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSKPD()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihSKPD(){
	global $Main;
		$c1 = $_REQUEST['fmc1'];
		$c = $_REQUEST['fmc'];
		$d = $_REQUEST['fmd'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
	
		 $querye="SELECT e, concat(e,' . ', nm_skpd) as vnama  FROM ref_skpd WHERE c1='$c1' and c='$c' and d='$d' and e<>'00' and e1='000'" ;$cek.=$querye;
		
		$content->unit=cmbQuery('fme',$fme,$querye,'style="width:270;"onchange="'.$this->Prefix.'.pilihUnit()"','-------- Pilih Kode UNIT -----------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruUnit()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihUnit(){
	global $Main;
	
		$c1 = $_REQUEST['fmc1'];
		$c = $_REQUEST['fmc'];
		$d = $_REQUEST['fmd'];
		$e = $_REQUEST['fme'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKE="SELECT max(e1) as e1, nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d = '$d' and e='$e'" ;$cek.=$queryKE;
		$get=mysql_fetch_array(mysql_query($queryKE));
		$lastkode=$get['e1'] + 1;	
		$kode_e1 = sprintf("%03s", $lastkode);
		$content->e1=$kode_e1;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function BaruUrusan($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKA';				
	 $this->form_width = 500;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode URUSAN';
		$nip	 = '';
		$C1 = $_REQUEST ['fmc1'];
			
		$aqry2="SELECT MAX(c1) AS maxno FROM ref_skpd where c='00' and d='00' and e='00' and e1='000'";
		$cek.="SELECT MAX(c1) AS maxno FROM ref_skpd where c='00' and d='00' and e='00' and e1='000'";
		$get=mysql_fetch_array(mysql_query($aqry2));
		$newc=$get['maxno'] + 1;	
		$queryc1=mysql_fetch_array(mysql_query("SELECT c1, nm_skpd FROM ref_skpd where c=00 and d=00 and e=00 and e1=000")); 
		$datak1=$queryc1['c1'].".".$queryc1['nm_skpd'];
		
	  }
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
									 			
			'kode_urusan' => array( 
						'label'=>'Kode URUSAN',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='c1' id='c1' value='".$newc."' style='width:50px;' readonly>
					
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Urusan' style='width:200px;'>
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanUrusan()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close1()' >";
							
		$form = $this->genFormUrusan();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormUrusan($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_KAform';	
		
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
	
	function BaruBidang($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKB';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode BIDANG';
		$nip	 = '';
		$C1 = $_REQUEST ['fmc1'];
			
		$aqry2="SELECT MAX(c) AS maxno FROM ref_skpd WHERE c1='$C1' and d='00' and e='00' and e1='000'";
	//	$cek.="SELECT MAX(c) AS maxno FROM ref_skpd WHERE c1='$C1' and d='00' and e='00' and e1='000'";
		$get=mysql_fetch_array(mysql_query($aqry2));
		$newc=$get['maxno'] + 1;
		
		$newdtc1 = sprintf("%02s", $newc);
		$queryc1=mysql_fetch_array(mysql_query("SELECT c1, nm_skpd FROM ref_skpd where c1='$C1' and c=00 and d=00 and e=00 and e1=000")); 
		$datak1=$queryc1['c1'].".".$queryc1['nm_skpd'];
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'urusan' => array( 
						'label'=>'Kode URUSAN',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='urusan' id='urusan' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='c1' id='c1' value='".$queryc1['c1']."'>
						</div>", 
						 ),	
									 			
			'bidang' => array( 
						'label'=>'Kode Bidang',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='c' id='c' value='".$newdtc1."' style='width:50px;' readonly>
					
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Bidang' style='width:200px;'>
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanBidang()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close2()' >";
							
		$form = $this->genFormBidang();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormBidang($withForm=TRUE, $params=NULL, $center=TRUE){	
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
	
	function BaruSKPD($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKC';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode SKPD';
		$nip	 = '';
		$C1 = $_REQUEST ['fmc1'];
		$C = $_REQUEST ['fmc'];
			
		$aqry2="SELECT MAX(d) AS maxno FROM ref_skpd WHERE c1='$C1' and c='$C'";
		$cek.="SELECT MAX(c) AS maxno FROM ref_skpd WHERE c1='$C1'";
		$get=mysql_fetch_array(mysql_query($aqry2));
		$newd=$get['maxno'] + 1;
		
		$newdtd = sprintf("%02s", $newd);
		$queryc1=mysql_fetch_array(mysql_query("SELECT c1, nm_skpd FROM ref_skpd where c1='$C1' and c=00 and d=00 and e=00 and e1=000")); 
		$queryc=mysql_fetch_array(mysql_query("SELECT c, nm_skpd FROM ref_skpd where c1='$C1' and c='$C' and d=00 and e=00 and e1=000")); 
		$datak1=$queryc1['c1'].".".$queryc1['nm_skpd'];
		$datac=$queryc['c'].".".$queryc['nm_skpd'];
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'urusan' => array( 
						'label'=>'Kode URUSAN',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='urusan' id='urusan' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='c1' id='c1' value='".$queryc1['c1']."'>
						</div>", 
						 ),	
						 
			'bidang' => array( 
						'label'=>'Kode BIDANG',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='bidang' id='bidang' value='".$datac."' style='width:255px;' readonly>
						
						<input type ='hidden' name='c' id='c' value='".$queryc['c']."'>
						</div>", 
						 ),				 
									 			
			'skpd' => array( 
						'label'=>'Kode SKPD',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='d' id='d' value='".$newdtd."' style='width:50px;' readonly>
					
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode SKPD' style='width:200px;'>
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanSKPD()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close3()' >";
							
		$form = $this->genFormSKPD();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormSKPD($withForm=TRUE, $params=NULL, $center=TRUE){	
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
	
	function BaruUnit($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKD';				
	 $this->form_width = 500;
	 $this->form_height = 120;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode Unit';
		$nip	 = '';
		$KA1 = $_REQUEST['fmc1'];
		$KB1 = $_REQUEST['fmc'];
		$KC1 = $_REQUEST['fmd'];
		$KD1 = $_REQUEST['fme'];
		
	
		$aqry4="SELECT MAX(e) AS maxno FROM ref_skpd WHERE c1='$KA1' and c='$KB1' and d='$KC1'";
	//	$cek.="SELECT MAX(n) AS maxno FROM ref_rekening WHERE k='$KA1' and l='$KB1' and m='$KC1'";
		$get=mysql_fetch_array(mysql_query($aqry4));

		$newkm=$get['maxno'] + 1;
		$newkm1 = sprintf("%02s", $newkm);
		$queryKA1=mysql_fetch_array(mysql_query("SELECT c1, nm_skpd FROM ref_skpd where c1='$KA1' and c=00 and d=00 and e=00 and e1=000"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT c, nm_skpd FROM ref_skpd where c1='$KA1' and c='$KB1' and d=00 and e=00 and e1=000"));  
		$queryKC1=mysql_fetch_array(mysql_query("SELECT d, nm_skpd FROM ref_skpd where c1='$KA1' and c='$KB1' and d='$KC1' and e=00 and e1=000"));  
	//	$cek.="SELECT m, nm_rekening FROM ref_rekening where k='$KA1' and l='$KB1' and m='$KC1' and n=00 and o=00";
//		
		$datak1=$queryKA1['c1'].".".$queryKA1['nm_skpd'];
		$datak2=$queryKB1['c'].".".$queryKB1['nm_skpd'];
		$datak3=$queryKC1['d'].".".$queryKC1['nm_skpd'];
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'urusan' => array( 
						'label'=>'Kode URUSAN',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='urusan' id='urusan' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='c1' id='c1' value='".$queryKA1['c1']."'>
						</div>", 
						 ),	
			
			'bidang' => array( 
						'label'=>'Kode BIDANG',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						
						<input type ='hidden' name='c' id='c' value='".$queryKB1['c']."'>
						</div>", 
						 ),	
						 
			'SKPD' => array( 
						'label'=>'Kode SKPD',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='jenis' id='jenis' value='".$datak3."' style='width:255px;' readonly>
						<input type ='hidden' name='d' id='d' value='".$queryKC1['d']."'>
						</div>", 
						 ),				 
									 		
			'UNIT' => array( 
						'label'=>'Kode UNIT',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='e' id='e' value='".$newkm1."' style='width:50px;' readonly>
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanUnit()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close4()' >";
							
		$form = $this->genFormUnit();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormUnit($withForm=TRUE, $params=NULL, $center=TRUE){	
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
	
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c1 = $_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$cek = $cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
			
	$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$dt['c1'] = $c1; 
		$dt['c'] = $c; 
		$dt['d'] = $d; 
		$dt['e'] = $e; 
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
  	function setFormEdit(){
		
	$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$c1=$kode[0];
		$c=$kode[1];
		$d=$kode[2];
		$e=$kode[3];
		$e1=$kode[4];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_skpd WHERE c1= '$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setFormEditdata($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEditdata($dt){	
	 global $SensusTmp ,$Main;
	 
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 520;
	 $this->form_height = 180;
	  if ($this->form_fmST==1) {
		$this->form_caption = 'FORM EDIT KODE SKPD';
	  }
	 
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;	
		$c1=$kode[0];
		$c=$kode[1];
		$d=$kode[2];
		$e=$kode[3];
		$e1=$kode[4];
		
		
		
		$queryKAedit=mysql_fetch_array(mysql_query("SELECT c1, nm_skpd FROM ref_skpd WHERE c1='$c1' and c = '00' and d='00' and e='00' and e1='000'")) ;
		$queryKBedit=mysql_fetch_array(mysql_query("SELECT c, nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d= '00' and e='00' and e1='000'")) ;
		$queryKCedit=mysql_fetch_array(mysql_query("SELECT d, nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d='$d' and e='00' and e1='000'")) ;
		$queryKDedit=mysql_fetch_array(mysql_query("SELECT e, nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d='$d' and e='$e' and e1='000'")) ;
		$queryKEedit=mysql_fetch_array(mysql_query("SELECT e1, nm_skpd FROM ref_skpd WHERE c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1'")) ;
		
		$datka=$queryKAedit['c1'].".  ".$queryKAedit['nm_skpd'];
		$datkb=$queryKBedit['c'].". ".$queryKBedit['nm_skpd'];
		$datkc=$queryKCedit['d']." .  ".$queryKCedit['nm_skpd'];
		$datkd=$queryKDedit['e'].". ".$queryKDedit['nm_skpd'];
		$datke=$queryKEedit['e1'];
		
       //items ----------------------
		  $this->form_fields = array(
		  
		  'URUSAN' => array( 
						'label'=>'URUSAN',
						'labelWidth'=>120, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ek' id='ek' value='".$datka."' style='width:350px;' readonly>
						<input type ='hidden' name='c1' id='c1' value='".$queryKAedit['c1']."'>
						</div>", 
						 ),
			'BIDANG' => array( 
						'label'=>'BIDANG',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='el' id='el' value='".$datkb."' style='width:350px;' readonly>
						<input type ='hidden' name='c' id='c' value='".$queryKBedit['c']."'>
						</div>", 
						 ),
			'SKPD' => array( 
						'label'=>'SKPD',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='em' id='em' value='".$datkc."' style='width:350px;' readonly>
						<input type ='hidden' name='d' id='d' value='".$queryKCedit['d']."'>
						</div>", 
						 ),
			'UNIT' => array( 
						'label'=>'UNIT',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='en' id='en' value='".$datkd."' style='width:350px;' readonly>
						<input type ='hidden' name='e' id='e' value='".$queryKDedit['e']."'>
						</div>", 
						 ),
			
			'SUB_UNIT' => array( 
						'label'=>'SUB UNIT',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='eo' id='eo' value='".$datke."' style='width:30px;' readonly>
						<input type ='hidden' name='e1' id='e1' value='".$queryKEedit['e1']."'>
						<input type='text' name='nm_skpd' id='nm_skpd' value='".$dt['nm_skpd']."' size='48px'>
						</div>", 
						 ),			 			 			 
			
			'BARCODE' => array( 
						'label'=>'LABEL BARCODE',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						
						<input type='text' name='nm_barcode' id='nm_barcode' value='".$dt['nm_barcode']."' size='54px'>
						</div>", 
						 ),						 			 	 
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanEdit()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
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
	 $this->form_height = 170;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'FORM BARU KODE SKPD';
	  }
	  	
		
		$fmc1 = $_REQUEST['fmc1'];
		$fmc = $_REQUEST['fmc'];
		$fmd = $_REQUEST['fmd'];
		$fme = $_REQUEST['fme'];
		$fme1 = $_REQUEST['fme1'];
		
					
		$queryc1="SELECT c1, concat(c1, '. ', nm_skpd) as vnama FROM ref_skpd where c=00 and d=00 and e=00 and e1=000";
		$queryc="SELECT c,nm_skpd FROM ref_skpd where c1='$fmc1' and d=00 and e=00 and e1=000";
		$queryd="SELECT d,nm_skpd FROM ref_skpd where c1='$fmc1' and c='$fmc' and e=00 and e1=000";
		$querye="SELECT e,nm_skpd FROM ref_skpd where c1='$fmc1' and c='$fmc' and d='$fmd' and e1=000";
		$querye1="SELECT e1,nm_skpd FROM ref_skpd where c1='$fmc1' and c='$fmc' and d='$fmd' and e='$fme'";
		
		
		/*$queryc1="SELECT * FROM ref_skpd where c=00 and d=00 and e=00 and e1=000";
		$lvl1_1=mysql_query("SELECT count(*) as cnt, c1 , c , d , e, e1 FROM ref_skpd WHERE c1='$data_c1' and c='$data_c' and d='$data_d' and e='$data_e' and e1='$data_e1'");*/
		
       //items ----------------------
		  $this->form_fields = array(
		  
		  	'urusan' => array( 
						'label'=>'URUSAN',
						'labelWidth'=>100, 
						'value'=>
					//	"<div id='cont_c1'>".cmbQuery('fmc1',$c1,$queryc1,'style="width:210;"onchange="'.$this->Prefix.'.pilihUrusan()"','-------- Pilih Kode URUSAN ------------------')."</div>",
					"<div id='cont_c1'>".cmbQuery('fmc1',$c,$queryc1,'style="width:270;"onchange="'.$this->Prefix.'.pilihUrusan()"','-------- Pilih Kode Urusan ------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruUrusan()' title='Kode Urusan' ></div>",
						 ),		
						 	
			'bidang' => array( 
						'label'=>'BIDANG',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_c'>".cmbQuery('fmc',$c,$queryc,'style="width:270;"onchange="'.$this->Prefix.'.pilihBidang()"','-------- Pilih Kode BIDANG -----------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruBidang()' title='Kode Bidang' ></div>",
						 ),
						 		 
			'skpd' => array( 
						'label'=>'SKPD',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_d'>".cmbQuery('fmd',$d,$queryd,'style="width:270;"onchange="'.$this->Prefix.'.pilihSKPD()"','-------- Pilih Kode SKPD ---------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSKPD()' title='kode SKPD' ></div>",
						 ),	
						 
			'unit' => array( 
						'label'=>'UNIT',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_e'>".cmbQuery('fme',$e,$querye,'style="width:270;"onchange="'.$this->Prefix.'.pilihUnit()"','-------- Pilih Kode UNIT -----------------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruUnit()' title='Kode UNIT' ></div>",
						 ),		
				
			'sub_unit' => array( 
						'label'=>'SUB UNIT',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='e1' id='e1' value='".$newke."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Sub Unit' style='width:220px;'>
						</div>", 
						 ),		
			
			'barcode' => array( 
						'label'=>'Label Barcode',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='barcode' id='barcode' value='".$nama."' placeholder='Label Barcode' style='width:275px;'>
						</div>", 
						 ),								 
			
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >"."&nbsp&nbsp".
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
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='200' colspan='5'>Kode</th>
	   <th class='th01' width='450' align='center'>Nama SKPD</th>
	   <th class='th01' width='450' align='center'>Nama Barcode</th>
	  
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="center"',genNumber($isi['c1'],1));
	 $Koloms[] = array('align="center"',genNumber($isi['c'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['d'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e1'],3));
	 $Koloms[] = array('align="left"',$isi['nm_skpd']);
	 $Koloms[] = array('align="left"',$isi['nm_barcode']);
	 
	 return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arrOrder = array(
	  	          array('1','Kode SKPD'),
			     	array('2','Nama SKPD'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectKode','Kode SKPD'),	
			array('selectNama','Nama SKPD'),		
			);
	$TampilOpt =
			//<table width=\"100%\" class=\"adminform\">
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajxVW($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<!--<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			-->
			</table>".
			"<tr><td>".
			"<div id='skpd_pegawai' ></div>".
			$vOrder=			
			genFilterBar(
				array(							
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). //generate checkbox					
					"&nbsp&nbsp<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>&nbsp&nbsp"
					//<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>"
					
					.cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$ref_skpdSkpdfmUrusan = $_REQUEST['ref_skpdSkpdfmUrusan'];
		$ref_skpdSkpdfmSKPD = $_REQUEST['ref_skpdSkpdfmSKPD'];//ref_skpdSkpdfmSKPD
		$ref_skpdSkpdfmUNIT = $_REQUEST['ref_skpdSkpdfmUNIT'];
		$ref_skpdSkpdfmSUBUNIT = $_REQUEST['ref_skpdSkpdfmSUBUNIT'];
		$ref_skpdSkpdfmSEKSI = $_REQUEST['ref_skpdSkpdfmSEKSI'];
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			//case 'selectKode': $arrKondisi[] = " c='".$isivalue[0]."' and d='".$isivalue[1]."' and e='".$isivalue[2]."' and e1='".$isivalue[3]."'"; break;
			case 'selectKode': $arrKondisi[] = " concat(c1,'.',c,'.',d,'.',e,'.',e1) like '$fmPILCARIvalue%'"; break;
			case 'selectNama': $arrKondisi[] = " nm_skpd like '%$fmPILCARIvalue%'"; break;	
								 	
		}	
		if($ref_skpdSkpdfmUrusan!='00' and $ref_skpdSkpdfmUrusan !='' and $ref_skpdSkpdfmUrusan!='0'){
			$arrKondisi[]= "c1='$ref_skpdSkpdfmUrusan'";
			if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !='')$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";
			if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='')$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
			if($ref_skpdSkpdfmSUBUNIT!='00' and $ref_skpdSkpdfmSUBUNIT !='')$arrKondisi[]= "e='$ref_skpdSkpdfmSUBUNIT'";
			if($ref_skpdSkpdfmSEKSI!='00' and $ref_skpdSkpdfmSEKSI !='')$arrKondisi[]= "e1='$ref_skpdSkpdfmSEKSI'";
		}
		
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
			case '1': $arrOrders[] = " c1,c,d,e,e1 $Asc1 " ;break;
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
$ref_skpd = new ref_skpdObj();
?>