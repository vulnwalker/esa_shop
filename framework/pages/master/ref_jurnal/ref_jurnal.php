<?php

class RefAkunObj  extends DaftarObj2{	
	var $Prefix = 'RefAkun';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_jurnal'; //daftar
	var $TblName_Hapus = 'ref_jurnal';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('ka','kb','kc','kd','ke','kf');
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
	var $Cetak_Judul = 'JURNAL';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'RefAkunForm'; 	
			
	function setTitle(){
		return 'Daftar Akun';
	}
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	function setMenuView(){
		return "";
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
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	
	
	
	
	$ka= $_REQUEST['fmKA'];
	$kb= $_REQUEST['fmKB'];
	$kc= $_REQUEST['fmKC'];
	$kd= $_REQUEST['fmKD'];
	$ke= $_REQUEST['ke'];
	$nm_acc= $_REQUEST['nama'];
//	$nm_acc= $_REQUEST['nm_account'];
	
	
	 
	 //---------- Validasi data tidak boleh kosong---------------------------	 	 	 	 
	 if( $err=='' && $ka =='' ) $err= 'Kode Akun Belum Di Isi !!';
	 if( $err=='' && $kb =='' ) $err= 'Kode Kelompok Belum Di Isi !!';
	 if( $err=='' && $kc =='' ) $err= 'Kode Jenis Belum Di Isi !!';
	 if( $err=='' && $kd =='' ) $err= 'Kode Objek Belum Di Isi !!';
	 if( $err=='' && $ke =='' ) $err= 'Kode Rincian Objek Belum Di Isi !!';
	 if( $err=='' && $nm_acc =='' ) $err= 'Nama Akun Belum Di Isi !!';
	 
	
	
	//----------- end data jurnal ----------------------
	$ke= $_REQUEST['ke'];
	$ke = substr($ke,1,1);
	if($fmST == 0){
		if($err==''){
			$aqry = "INSERT into ref_jurnal (ka,kb,kc,kd,ke,kf,nm_account,thn_akun) values('$ka','$kb','$kc','$kd','$ke','0','$nm_acc','2015')";			$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry == FALSE)
					{
						$err='Maaf Data Belum Terisi !';
					}
				}
			}else{	
								
	if($err==''){						
	 /*if($ka=='0') {$err= 'Kode akun tidak boleh 0';}
	 if($ck1['cnt']==0 && $err=='' && $kb<>'0') {$err= "Kode akun level ($ka,$kb,0,0,0) sebelumnya";}
	 if($ck2['cnt']==0 && $err=='' && $kc<>'0') {$err= "Kode akun level ($ka,$kb,$kc,0,0) Tidak ada sebelumnya";}
	 if($ck3['cnt']==0 && $err=='' && $kd<>'0') {$err= "Kode akun level ($ka,$kb,$kc,$kd,0)Tidak ada sebelumnya";}
	 if($ck4['cnt']==0 && $err=='' && $ke<>'0') {$err= "Kode akun level ($ka,$kb,$kc,$kd,$ke)Tidak ada sebelumnya";}*/
	
	$aqry = "UPDATE ref_jurnal set thn_akun='2015' , ka='$ka',kb='$kb',kc='$kc',kd='$kd',ke='$ke',kf='0',nm_account='$nm_acc' where concat (ka,' ',kb,' ',kc,' ',kd,' ',ke,' ',kf)='".$idplh."'";$cek .= $aqry;
						$qry = mysql_query($aqry);
				}
			} //end else'".$idplh."'
					
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
	
	$eka= $_REQUEST['ka'];
	$ekb= $_REQUEST['kb'];
	$ekc= $_REQUEST['kc'];
	$ekd= $_REQUEST['kd'];
	$eke= $_REQUEST['ke'];
	$nama= $_REQUEST['nm_account'];
	

	$ke = substr($ke,1,1);
	
								
	if($err==''){						
		
	$aqry = "UPDATE ref_jurnal set thn_akun='2015' , ka='$eka',kb='$ekb',kc='$ekc',kd='$ekd',ke='$eke',kf='0',nm_account='$nama' where concat (ka,' ',kb,' ',kc,' ',kd,' ',ke,' ',kf)='".$idplh."'";$cek .= $aqry;
						$qry = mysql_query($aqry);
				}
								
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanKB(){
	global $HTTP_COOKIE_VARS;
	global $Main;
	 
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$ka01= $_REQUEST['ka'];
		$kb= $_REQUEST['kb'];
		/*$h= $_REQUEST['h'];
		$i= $_REQUEST['i'];
		$j= $_REQUEST['j'];*/
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Kelompok Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_jurnal (ka,kb,nm_account,thn_akun) values('$ka01','$kb','$nama','2015')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$kb;	
				}
			}
				
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
		$ka01= $_REQUEST['ka'];
		$kb= $_REQUEST['kb'];
		$kc= $_REQUEST['kc'];
		
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Jenis Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqrykc = "INSERT into ref_jurnal (ka,kb,kc,nm_account,thn_akun) values('$ka01','$kb','$kc','$nama','2015')";	
				$cek .= $aqrykc;	
				$qry = mysql_query($aqrykc);
				$content=$kc;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_jurnal set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
				
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
		$ka01= $_REQUEST['ka'];
		$kb= $_REQUEST['kb'];
		$kc= $_REQUEST['kc'];
		$kd= $_REQUEST['kd'];
		$nama= $_REQUEST['nama'];
		
	//	$kd = substr($kd,1,1);
	if( $err=='' && $nama =='' ) $err= 'Nama Kode Objek Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqrykd = "INSERT into ref_jurnal (ka,kb,kc,kd,nm_account,thn_akun) values('$ka01','$kb','$kc','$kd','$nama','2015')";	
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
	
	
		
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	
	case 'hapus':{	
				$fm= $this->Hapus($pil);
				$err= $fm['err']; 
				$cek = $fm['cek'];
				$content = $fm['content'];
		break;
		}
	  
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
			
	case 'pilihKE':{				
				$fm = $this->pilihKE();				
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
			
	case 'formBaruKE':{				
				$fm = $this->setFormBaruKE();				
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
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			break;
		}

	   /*	case 'getdata':{

		$ref_pilihjurnal = $_REQUEST['id'];
		$kode_jurnal = explode(' ',$ref_pilihjurnal);
		$ka=$kode_jurnal[0];	
		$kb=$kode_jurnal[1];
		$kc=$kode_jurnal[2];	
		$kd=$kode_jurnal[3];
		$ke=$kode_jurnal[4];
		$kf=$kode_jurnal[5];
		//query ambil data ref_jurnal
		$get = mysql_fetch_array( mysql_query("select * from ref_jurnal where ka=$ka and kb=$kb and kc=$kc and kd=$kd and ke=$ke and kf=$kf"));
		$kode_account=$get['ka'].'.'.$get['kb'].'.'.$get['kc'].'.'.$get['kd'].'.'.$get['ke'].'.'.$get['kf'];
		
		$content = array('kode_jurnal'=>$kode_account, 'nm_account'=>$get['nm_account']);//, 'tahun_account'=>$get['thn_akun']);	
		break;
	   }*/
	   
	   case 'getdata':{
	   
	  // =1 1 1 2 0 0
				$Id = $_REQUEST['id'];
				$ka = substr($Id, 0,1);
				$kb = substr($Id, 2,1);
				$kc = substr($Id, 4,1);
				$kd = substr($Id, 6,1);
				$ke = substr($Id, 8,2);
				$get = mysql_fetch_array( mysql_query("select *, concat(ka,'.',kb,'.',kc,'.',kd,'.',ke) as kodejurnal  from ref_jurnal where ka='$ka' AND kb='$kb' AND kc='$kc' AND kd='$kd' AND ke='$ke'"));
			
				
				$content = array('kode_jurnal' => $get['kodejurnal'], 'nm_account' => $get['nm_account']);
					
				
		break;
	    }

					
		case 'simpan':{
			$get= $this->simpan();
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
		
		case 'simpanKE':{
				$get= $this->simpanKE();
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
			
		case 'refreshKE':{
				$get= $this->refreshKE();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
			break;
	    	}			
		case 'getKodeE':{
			$get= $this->getKodeE();
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
		$data_f= $idplh1[5];
		
		
		if ($data_c1 != '0'){
			$sk1="select ka,kb,kc,kd,ke,kf from ref_jurnal where ka='$data_c1' and kb!='0'";
		}
		
		if ($data_c != '0'){
			$sk1="select ka,kb,kc,kd,ke,kf from ref_jurnal where ka='$data_c1' and kb='$data_c' and kc!='0'";
		}
		
		if ($data_d != '0'){
			$sk1="select ka,kb,kc,kd,ke,kf from ref_jurnal where ka='$data_c1'  and kb='$data_c' and kc='$data_d' and kd!='00'";
		}
		if ($data_e != '00'){
			$sk1="select ka,kb,kc,kd,ke,kf from ref_jurnal where ka='$data_c1'  and kb='$data_c' and kc='$data_d' and kd='$data_e' and ke!='00'";
		}
		
		if ($data_e1 != '00'){
			$sk1="select ka,kb,kc,kd,ke,kf from ref_jurnal where ka='$data_c1'  and kb='$data_c' and kc='$data_d' and kd='$data_e' and ke='$data_e' and kf!='0000'";
		}
	//	$err='tes';
		if ($data_f=='0000'){
			$qrycek=mysql_query($sk1);$cek.=$sk1;
			if(mysql_num_rows($qrycek)>0)$err='data tidak bisa di hapus';
		}
		
		
		if($err=='' ){
					$qy = "DELETE FROM ref_jurnal WHERE ka='$data_c1' and kb='$data_c' and kc='$data_d'  and  kd='$data_e' and ke='$data_e1' and kf='$data_f' and  concat (ka,' ',kb,' ',kc,' ',kd,' ',ke,' ',kf) ='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					
			}else{
				break;
			}			
		}
		}
		return array('err'=>$err,'cek'=>$cek);
	}	  
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script type='text/javascript' src='js/master/ref_jurnal/ref_jurnal.js' language='JavaScript' ></script>".		
			$scriptload;
	}
	
	function pilihKA(){
	global $Main;	 
	
		$ka = $_REQUEST['fmKA'];
		$kb = $_REQUEST['fmKB'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$queryKB="SELECT kb, concat(kb, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka' and kb<>'0' and kc = '0' and kd='0' and ke='0' and kf='0'" ;$cek.=$queryKB;
		$content->unit=cmbQuery('fmKB',$fmkb,$queryKB,'style="width:210;"onchange="'.$this->Prefix.'.pilihKB()"','&nbsp&nbsp----- Pilih Kode Kelompok -----')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKB()' title='Baru' >";
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKB(){
	global $Main;
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKC="SELECT kc, concat(kc, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka' and kb='$kb' and kc <> '0' and kd='0' and ke='0' and kf='0'" ;$cek.=$queryKC;
		$content->unit=cmbQuery('fmKC',$fmkc,$queryKC,'style="width:210;"onchange="'.$this->Prefix.'.pilihKC()"','&nbsp&nbsp-------- Pilih Jenis --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihKC(){
	global $Main;
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
	//	$queryKD="SELECT kd, concat(kd, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka' and kb='$kb' and kc = '$kc' and kd <> '0' and ke='0' and kf='0'" ;//$cek.=$queryKD;
		 $queryKD="SELECT kd, concat(if(length(kd)=1,concat('0',kd),kd),' . ', nm_account) as vnama  FROM ref_jurnal WHERE ka='$ka' and kb='$kb' and kc='$kc' and kd<>'0' and ke='0' and kf='0'" ;$cek.=$queryKD;
		/*$koded=$queryKD['kd'];
		$new = sprintf("%02s", $koded);
		$kode_e=$new.".".$queryKD['nm_account'];
		*/
		$content->unit=cmbQuery('fmKD',$fmkd,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Sub Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";$cek.=$queryJenis;
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKD(){
	global $Main;
	
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$kd = $_REQUEST['fmKD'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKE="SELECT max(ke) as ke, nm_account FROM ref_jurnal WHERE ka='$ka' and kb='$kb' and kc = '$kc' and kd='$kd' and ke <> '0' and kf='0'" ;$cek.=$queryKE;
		$content->unit=cmbQuery('fmKE',$fmke,$queryKE,'style="width:210;"onchange="'.$this->Prefix.'.pilihKE()"','&nbsp&nbsp-------- Pilih Sub Rincian Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";$cek.=$queryJenis;
	 
		
		$get=mysql_fetch_array(mysql_query($queryKE));
		$lastkode=$get['ke'] + 1;
		
		$kode_ke = sprintf("%02s", $lastkode);
		$content->ke=$kode_ke;
	 
	 
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKE(){
	global $Main;
	
		$ka = $_REQUEST['fmKA']; 
		$kb = $_REQUEST['fmKB'];
		$kc = $_REQUEST['fmKC'];
		$kd = $_REQUEST['fmKD'];
		$ke = $_REQUEST['fmKE'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryKF="SELECT kf, concat(kf, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka' and kb='$kb' and kc = '$kc' and kd='$kd' and ke='$ke' and kf <> '0'" ;
		$content->unit=cmbQuery('fmKE',$fmke,$queryKF,'style="width:210;"onchange="'.$this->Prefix.'.pilihKE()"','&nbsp&nbsp-------- Pilih Sub Rincian Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";$cek.=$queryJenis;
	 
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
	
	function setFormBaruKE(){
		$dt=array();
		$this->form_fmST = 0;
		
		$fm = $this->BaruKE($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function BaruKE($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formKE';				
	 $this->form_width = 500;
	 $this->form_height = 140;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Kode Rincian Objek';
		$nip	 = '';
		$KA1 = $_REQUEST['fmKA'];
		$KB1 = $_REQUEST['fmKB'];
		$KC1 = $_REQUEST['fmKC'];
		$KD1 = $_REQUEST['fmKD'];
		$KE1 = $_REQUEST['fmKE'];
		
	
		$aqry5="SELECT MAX(ke) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1' and kc='$KC1' and kd='$KD1'";
		$cek.="SELECT MAX(ke) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1' and kc='$KC1' and kd='$KD1'";
		$get=mysql_fetch_array(mysql_query($aqry5));

		$newke=$get['maxno'] + 1;
		$queryKA1=mysql_fetch_array(mysql_query("SELECT ka, nm_account FROM ref_jurnal where kb=0 and kc=0 and kd=0 and ke=0 and kf=0"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT kb, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc=0 and kd=0 and ke=0 and kf=0"));  
		$queryKC1=mysql_fetch_array(mysql_query("SELECT kc, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc='$KC1' and kd=0 and ke=0 and kf=0"));  
		$queryKD1=mysql_fetch_array(mysql_query("SELECT kd, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc='$KC1' and kd='$KD1' and ke=0 and kf=0"));  
		$cek.="SELECT kc, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc='$KC1' and kd=0 and ke=0 and kf=0";
//		
		$datak1=$queryKA1['ka'].".".$queryKA1['nm_account'];
		$datak2=$queryKB1['kb'].".".$queryKB1['nm_account'];
		$datak3=$queryKC1['kc'].".".$queryKC1['nm_account'];
		$datak4=$queryKD1['kd'].".".$queryKD1['nm_account'];
		
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
						
						<input type ='hidden' name='ka' id='ka' value='".$queryKA1['ka']."'>
						</div>", 
						 ),	
			
			'kode_Kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						
						<input type ='hidden' name='kb' id='kb' value='".$queryKB1['kb']."'>
						</div>", 
						 ),	
						 
			'kode_jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='jenis' id='jenis' value='".$datak3."' style='width:255px;' readonly>
						<input type ='hidden' name='kc' id='kc' value='".$queryKC1['kc']."'>
						</div>", 
						 ),				 
									 		
			'kode_objek' => array( 
						'label'=>'Kode Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='objek' id='objek' value='".$datak4."' style='width:255px;' readonly>
						<input type ='hidden' name='kd' id='kd' value='".$queryKD1['kd']."'>
						</div>", 
						 ),			
						 
			
			'Kode_Rincian_Objek' => array( 
						'label'=>'Kode Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ke' id='ke' value='".$newke."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Kode Rincian Objek' style='width:200px;'>
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
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanKE()' title='Simpan' >"."&nbsp&nbsp".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close5()' >";
							
		$form = $this->genFormKE();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormKE($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_KEform';	
		
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
		
	
		$aqry4="SELECT MAX(kd) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1' and kc='$KC1'";
		$cek.="SELECT MAX(kd) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1' and kc='$KC1'";
		$get=mysql_fetch_array(mysql_query($aqry4));

		$newkd=$get['maxno'] + 1;
		$newke1 = sprintf("%02s", $newkd);
		$queryKA1=mysql_fetch_array(mysql_query("SELECT ka, nm_account FROM ref_jurnal where kb=0 and kc=0 and kd=0 and ke=0 and kf=0"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT kb, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc=0 and kd=0 and ke=0 and kf=0"));  
		$queryKC1=mysql_fetch_array(mysql_query("SELECT kc, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc='$KC1' and kd=0 and ke=0 and kf=0"));  
		$cek.="SELECT kc, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc='$KC1' and kd=0 and ke=0 and kf=0";
//		
		$datak1=$queryKA1['ka'].".".$queryKA1['nm_account'];
		$datak2=$queryKB1['kb'].".".$queryKB1['nm_account'];
		$datak3=$queryKC1['kc'].".".$queryKC1['nm_account'];
		
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
						
						<input type ='hidden' name='ka' id='ka' value='".$queryKA1['ka']."'>
						</div>", 
						 ),	
			
			'kode_Kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						
						<input type ='hidden' name='kb' id='kb' value='".$queryKB1['kb']."'>
						</div>", 
						 ),	
						 
			'kode_jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='jenis' id='jenis' value='".$datak3."' style='width:255px;' readonly>
						<input type ='hidden' name='kc' id='kc' value='".$queryKC1['kc']."'>
						</div>", 
						 ),				 
									 		
			'kode_objek' => array( 
						'label'=>'Kode Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kd' id='kd' value='".$newke1."' style='width:50px;' readonly>
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
		
	
		$aqry3="SELECT MAX(kc) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1'";
		$cek.="SELECT MAX(kc) AS maxno FROM ref_jurnal WHERE ka='$KA1' and kb='$KB1'";
		$get=mysql_fetch_array(mysql_query($aqry3));

		$newkc=$get['maxno'] + 1;
		$queryKA1=mysql_fetch_array(mysql_query("SELECT ka, nm_account FROM ref_jurnal where kb=0 and kc=0 and kd=0 and ke=0 and kf=0"));  
		$queryKB1=mysql_fetch_array(mysql_query("SELECT kb, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc=0 and kd=0 and ke=0 and kf=0"));  
		$cek.="SELECT kb, nm_account FROM ref_jurnal where ka='$KA1' and kb='$KB1' and kc=0 and kd=0 and ke=0 and kf=0";
//		
		$datak1=$queryKA1['ka'].".".$queryKA1['nm_account'];
		$datak2=$queryKB1['kb'].".".$queryKB1['nm_account'];
		$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
		$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
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
						
						<input type ='hidden' name='ka' id='ka' value='".$queryKA1['ka']."'>
						</div>", 
						 ),	
			
			'kode_Kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak2."' style='width:255px;' readonly>
						
						<input type ='hidden' name='kb' id='kb' value='".$queryKB1['kb']."'>
						</div>", 
						 ),	
									 			
			
			
			'kode_jenis' => array( 
						'label'=>'Kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kc' id='kc' value='".$newkc."' style='width:50px;' readonly>
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
		$fmKelompok2 = $_REQUEST['fmKelompok2'];
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis2'];
		$queryBidang = $_REQUEST['fmBidang2'];
	
		$aqry2="SELECT MAX(kb) AS maxno FROM ref_jurnal WHERE ka='$KA1'";
		$cek.="SELECT MAX(kb) AS maxno FROM ref_jurnal WHERE ka='$KA1'";
		$get=mysql_fetch_array(mysql_query($aqry2));
//		$lastkode=$get['maxno'] + 1;
		$newkb=$get['maxno'] + 1;
		/*$kode = (int) substr($lastkode, 1, 3);
		$kode++;
		$no_ba = sprintf("%1s", $kode);*/
		$queryKA1=mysql_fetch_array(mysql_query("SELECT ka, nm_account FROM ref_jurnal where kb=0 and kc=0 and kd=0 and ke=0 and kf=0"));  

		$datak1=$queryKA1['ka'].".".$queryKA1['nm_account'];
		$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
		$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
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
						<input type='text' name='Kelompok' id='Kelompok' value='".$datak1."' style='width:255px;' readonly>
						
						<input type ='hidden' name='ka' id='ka' value='".$queryKA1['ka']."'>
						</div>", 
						 ),	
									 			
			'kode_kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='kb' id='kb' value='".$newkb."' style='width:50px;' readonly>
					
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
	
	
	function refreshKB(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kbnew= $_REQUEST['id_KBBaru'];
	 
		$queryKB="SELECT kb, concat(kb, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'" ;
		//$cek.="SELECT kb,nm_account FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'";
		$cek.="SELECT kb, concat(kb, '. ', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb <> '0' and kc='0' and kd='0' and ke='0' and kf='0'";
		$content->unit=cmbQuery('fmKB',$kbnew,$queryKB,'style="width:210;"onchange="'.$this->Prefix.'.pilihKB()"','&nbsp&nbsp-------- Pilih Kelompok -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKB()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshKC(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['kc'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kcnew= $_REQUEST['id_KCBaru'];
	 
		$queryKC="SELECT kc, concat(kc,'.', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc<>'0' and kd='0' and ke='0' and kf='0'" ;
		$cek.="SELECT kc, concat(kc,'.', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc<>'0' and kd='0' and ke='0' and kf='0'";
		$content->unit=cmbQuery('fmKC',$kcnew,$queryKC,'style="width:210;"onchange="'.$this->Prefix.'.pilihKC()"','&nbsp&nbsp-------- Pilih Jenis -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshKD(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
		$kd02 = $_REQUEST['kd'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kdnew= $_REQUEST['id_KDBaru'];
	 
	 $queryKD="SELECT kd, concat(if(length(kd)=1,concat('0',kd),kd),' . ', nm_account) as vnama  FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc='$kc02' and kd<>'0' and ke='0' and kf='0'" ;
	 
		$koded=$queryKD['kd'];
		$new = sprintf("%02s", $koded);
		$kode_e=$new.".".$queryKD['nm_account'];
	
		$content->unit=cmbQuery('fmKD',$kdnew,$queryKD,'style="width:210;"onchange="'.$this->Prefix.'.pilihKD()"','&nbsp&nbsp-------- Pilih Objek -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKD()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function refreshKE(){
	global $Main;
	 
		$ka02 = $_REQUEST['fmKA'];	 
		$kb02 = $_REQUEST['fmKB'];
		$kc02 = $_REQUEST['fmKC'];
		$kd02 = $_REQUEST['fmKD'];
		$ke02 = $_REQUEST['ke'];
	//	$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$kenew= $_REQUEST['id_KEBaru'];
	 
		$queryKE="SELECT ke, concat(ke,'.', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc='$kc02' and kd='$kd02' and ke<>'0' and kf='0'" ;
		$cek.="SELECT ke, concat(ke,'.', nm_account) as vnama FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc='$kc02' and kd='$kd02' and ke<>'0' and kf='0'";
		$content->unit=cmbQuery('fmKE',$kenew,$queryKE,'style="width:210;"onchange="'.$this->Prefix.'.pilihKE()"','&nbsp&nbsp-------- Pilih Rincian Objek -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruKC()' title='Baru' >";
	 
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}	
	
	function getKodeE(){
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
	 
	 	$aqry5="SELECT MAX(ke) AS maxno FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc='$kc02' and kd='$kd02'";
	 	$cek.="SELECT MAX(ke) AS maxno FROM ref_jurnal WHERE ka='$ka02' and kb='$kb02' and kc='$kc02' and kd='$kd02'";
		$get=mysql_fetch_array(mysql_query($aqry5));
		$newke=$get['maxno'] + 1;
		$newke1 = sprintf("%02s", $newke);
		$content->ke=$newke1;	
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function Hapus_Validasi($id){//id -> multi id with space delimiter
		$errmsg ='';
		$kode_jurnal = explode(' ',$id);
		$ka=$kode_jurnal[0];	
		$kb=$kode_jurnal[1];
		$kc=$kode_jurnal[2];	
		$kd=$kode_jurnal[3];
		$ke=$kode_jurnal[4];
		$kf=$kode_jurnal[5];
		
		$quricoy="select count(*) as cnt from ref_barang where ka='$ka' and kb='$kb' and kc='$kc' and kd='$kd' and ke='$ke' and kf='$kf'";
		$dt3 = mysql_fetch_array(mysql_query($quricoy));
		$korong = $dt3 ['cnt'];
		
		if($korong>0){
		
		$korong;
		$errmsg = "ada kode barang tidak bisa di edit/hapus, karena masih ada rinciannya !";
		}
		
		if($errmsg=='' && 
				mysql_num_rows(mysql_query(
					"select Id from buku_induk where ka='$ka' and kb='$kb' and kc='$kc' and kd='$kd' and ke='$ke' and kf='$kf' ")
				) >0 )
			{ $errmsg = 'Gagal Hapus! KODE AKUN Sudah ada di Buku Induk!';}
		return $errmsg;
	}
	//form ==================================
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
		$this->form_fmST = 1;	
		$data_ka= $kode[0];
		$data_kb= $kode[1];
		$data_kc= $kode[2];
		$data_kd= $kode[3];
		$data_ke= $kode[4];
		$data_kf= $kode[5];
		
		//$cek.="idplh".$this->form_idplh;
		$queryAkun="SELECT * FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='$data_ke' and kf='$data_kf'";
	  //	$cek.="SELECT * FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='$data_ke' and kf='$data_kf'";
		$dt = mysql_fetch_array(mysql_query($queryAkun));
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
		$this->form_caption = 'FORM EDIT KODE AKUN';
	  }
	 
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;	
		$data_ka= $kode[0];
		$data_kb= $kode[1];
		$data_kc= $kode[2];
		$data_kd= $kode[3];
		$data_ke= $kode[4];
		$data_kf= $kode[5];
		
		
		
		$queryKAedit=mysql_fetch_array(mysql_query("SELECT ka, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb = '0' and kc='0' and kd='0' and ke='0' and kf='0'")) ;
		$queryKBedit=mysql_fetch_array(mysql_query("SELECT kb, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc= '0' and kd='0' and ke='0' and kf='0'")) ;
		$queryKCedit=mysql_fetch_array(mysql_query("SELECT kc, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='0' and ke='0' and kf='0'")) ;
		$queryKDedit=mysql_fetch_array(mysql_query("SELECT kd, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='0' and kf='0'")) ;
		$queryKEedit=mysql_fetch_array(mysql_query("SELECT ke, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='$data_ke' and kf='0'")) ;
		$cek.="SELECT ke, nm_account FROM ref_jurnal WHERE ka='$data_ka' and kb='$data_kb' and kc='$data_kc' and kd='$data_kd' and ke='$data_ke' and kf='0'";
					
	
		$datka=$queryKAedit['ka'].".  ".$queryKAedit['nm_account'];
		$datkb=$queryKBedit['kb'].". ".$queryKBedit['nm_account'];
		$datkc=$queryKCedit['kc']." .  ".$queryKCedit['nm_account'];
		$datkd=sprintf("%02s",$queryKDedit['kd']).". ".$queryKDedit['nm_account'];
		$datke=sprintf("%02s",$queryKEedit['ke']);
	//	$datke=sprintf("%02s",$queryKEedit['ke'])." .  ".$queryKEedit['nm_account'];
		
       //items ----------------------
		  $this->form_fields = array(
		  
		  'kode_Akun' => array( 
						'label'=>'kode Akun',
						'labelWidth'=>120, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='eka' id='eka' value='".$datka."' style='width:270px;' readonly>
						<input type ='hidden' name='ka' id='ka' value='".$queryKAedit['ka']."'>
						</div>", 
						 ),
			'kode_kelompok' => array( 
						'label'=>'Kode Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ekb' id='ekb' value='".$datkb."' style='width:270px;' readonly>
						<input type ='hidden' name='kb' id='kb' value='".$queryKBedit['kb']."'>
						</div>", 
						 ),
			'kode_Jenis' => array( 
						'label'=>'kode Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ekc' id='ekc' value='".$datkc."' style='width:270px;' readonly>
						<input type ='hidden' name='kc' id='kc' value='".$queryKCedit['kc']."'>
						</div>", 
						 ),
			'kode_Objek' => array( 
						'label'=>'kode Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='ekd' id='ekd' value='".$datkd."' style='width:270px;' readonly>
						<input type ='hidden' name='kd' id='kd' value='".$queryKDedit['kd']."'>
						</div>", 
						 ),
			'Kode_Rincian_Objek' => array( 
						'label'=>'Kode Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='eke' id='eke' value='".$datke."' style='width:20px;' readonly>
						<input type ='hidden' name='ke' id='ke' value='".$queryKEedit['ke']."'>
						<input type='text' name='nm_account' id='nm_account' value='".$dt['nm_account']."' size='36px'>
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
		$this->form_caption = 'FORM BARU KODE AKUN';
	  }else{
		$this->form_caption = 'EDIT';
	 
	  }
	  	
		$kode = explode(' ',$this->form_idplh);
	  	$data_a= $kode[0];
	 	$data_b= $kode[1];
		$data_c= $kode[2];
		$data_d= $kode[3];
		$data_e= $kode[4];
		
		//$ka = $_REQUEST['ka'];
		$fmKA = $_REQUEST['fmka'];
		$fmKB = $_REQUEST['fmkb'];
		$fmKC = $_REQUEST['fmkc'];
		$fmKD = $_REQUEST['fmkd'];
		$fmKE = $_REQUEST['fmke'];
		$fmKF = $_REQUEST['fmkf'];
					
		$queryKA="SELECT ka, concat(ka, '. ', nm_account) as vnama FROM ref_jurnal where kb=0 and kc=0 and kd=0 and ke=0 and kf=0";  
		$queryKB="SELECT kb,nm_account FROM ref_jurnal where ka='$fmKA' and kc=0 and kd=0 and ke=0 and kf=0";
		$cek.="SELECT kb,nm_account FROM ref_jurnal where ka='$fmKA' and kc=0 and kd=0 and ke=0 and kf=0";
		$queryKC="SELECT kc,nm_account FROM ref_jurnal where ka='$fmKA' and kb='$fmKB' and kd=0 and ke=0 and kf=0";
		$queryKD="SELECT kd,nm_account FROM ref_jurnal where ka='$fmKA' and kb='$fmKB' and kc='$fmKC' and ke=0 and kf=0";
		$queryKE="SELECT ke,nm_account FROM ref_jurnal where ka='$fmKA' and kb='$fmKB' and kc='$fmKC' and kd='$fmKD' and kf=0";
		
       //items ----------------------
		  $this->form_fields = array(
		  
		  	'kode_Akun' => array( 
						'label'=>'Kode Akun',
						'labelWidth'=>150, 
						'value'=>
						"<div id='cont_ka'>".cmbQuery('fmKA',$ka,$queryKA,'style="width:210;"onchange="'.$this->Prefix.'.pilihKA()"','-------- Pilih Kode Akun ------------------')."</div>",
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
									 
			
			
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >"."&nbsp&nbsp".
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

	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		//$fmSKPD = $_REQUEST['fmSKPD'];
		//$fmUNIT = $_REQUEST['fmUNIT'];
		//$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		//$tahun_anggaran = $_REQUEST['tahun_anggaran'];		
		
		//if($err=='' && ($fmSKPD=='00' || $fmSKPD=='') ) $err = 'Bidang belum diisi!';
		//if($err=='' && ($fmUNIT=='00' || $fmUNIT=='' )) $err = 'Asisten/OPD belum diisi!';
		//if($err=='' && ($fmSUBUNIT=='00' || $fmSUBUNIT=='')) $err='BIRO / UPTD/B belum diisi!';	
		//if($err==''){
			$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						800,
						500,
						'Pilih Akun',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function genDaftarInitial($nm_account='', $height=''){
		$filterAkun = $_REQUEST['filterAkun'];
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSKPD' name='".$this->Prefix."SkpdfmSKPD' value='$fmSKPD'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmUNIT' name='".$this->Prefix."SkpdfmUNIT' value='$fmUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				"<input type='hidden' id='".$this->Prefix."nm_account' name='".$this->Prefix."nm_account' value='$nm_account'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
				"<input type='hidden' id='filterAkun' name='filterAkun' value='".$filterAkun."'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $fmBIDANG = $_REQUEST['fmBIDANG'];
	 $fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
	 $fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
	 $fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];				 
	 $headerTable =
	 "<thead>
	 <tr>
  	   <th class='th01' width='50' >No.</th>
  	   $Checkbox		
   	   <th class='th01' align='center' width='100'>Kode Akun</th>
	   <th class='th01' align='center' width='800'>Nama Akun</th>	   	   	   
	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
		$isi = array_map('utf8_encode', $isi);
//$newke1 = sprintf("%02s", $newke);
	$newkdd = sprintf("%02s",$isi['kd']);
	$newkee = sprintf("%02s",$isi['ke']);
//	 $kode_jurnal=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$newkdd.'.'.$isi['ke'];
	 $kode_jurnal=$isi['ka'].'.'.$isi['kb'].'.'.$isi['kc'].'.'.$newkdd.'.'.$newkee;
	 $Koloms = array();
	 $Koloms[] = array('align="center" width=""', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="center" width="" ',$kode_jurnal);
 	 $Koloms[] = array('align="left" width=""',$isi['nm_account']);	 	 	 	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main,  $HTTP_COOKIE_VARS;
	 
	 $cek = '';

	$fmBIDANG = cekPOST('fmBIDANG');
	$fmKELOMPOK = cekPOST('fmKELOMPOK');
	$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK');
	$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK');
	$fmKODE = cekPOST('fmKODE');
	$fmAKUN = cekPOST('fmAKUN');
	//$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];	
	$filterAkun = cekPOST('filterAkun');		
		
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
	 
	 /*if($Main->WITH_THN_ANGGARAN){
		$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
				thn_akun<=$fmThnAnggaran"; $cek.=$aqry1;
				$qry1=mysql_query($aqry1);			
				$qry_jurnal=mysql_fetch_array($qry1);
				$thn_akun=$qry_jurnal['thnMax'];
				//$arrKondisi[] = " thn_akun = '$thn_akun'";														
		$vthnakun = " and thn_akun=$thn_akun ";
			
	}	*/
	
	
	if(!empty($_POST['filterAkun'])) {
		$arrakun = explode('.',$filterAkun);
		if($arrakun['0'] <> '') {
			$fmBIDANG = $arrakun['0'];
			$get = mysql_fetch_array(mysql_query(
				"select ka,kb,kc,kd, nm_account from ref_jurnal where ka='$fmBIDANG' and kb='0'"));// $vthnakun "
			
			$listBidang = $get['ka'].'. '. $get['nm_account']."<input type='hidden' id= 'fmBIDANG' name='fmBIDANG' value='$fmBIDANG' >";
		}
		if($arrakun['1'] <> '') {
			$fmKELOMPOK = $arrakun['1'];
			$get = mysql_fetch_array(mysql_query(
				"select ka,kb,kc,kd, nm_account from ref_jurnal where ka='$fmBIDANG' and kb='$fmKELOMPOK' and kc='0'")); //$vthnakun
			
			$listKelompok = $get['ka'].'.'.$get['kb'].'. '. $get['nm_account']."<input type='hidden' id= 'fmKELOMPOK' name='fmKELOMPOK' value='$fmKELOMPOK' >";
		}
		if($arrakun['2'] <> ''){
			$fmSUBKELOMPOK = $arrakun['2'];
			$get = mysql_fetch_array(mysql_query(
				"select ka,kb,kc,kd, nm_account from ref_jurnal where ka='$fmBIDANG' and kb='$fmKELOMPOK' and kc='$fmSUBKELOMPOK' and kd='0'"));// $vthnakun
			
			$listSubKelompok = $get['ka'].'.'.$get['kb'].'.'.$get['kc'].'. '.$get['nm_account']."<input type='hidden' id= 'fmSUBKELOMPOK' name='fmSUBKELOMPOK' value='$fmSUBKELOMPOK' >";
		}
		if($arrakun['3'] <> '') {
			$fmSUBSUBKELOMPOK = $arrakun['3'];
			$get = mysql_fetch_array(mysql_query(
				"select ka,kb,kc,kd, nm_account from ref_jurnal where ka='$fmBIDANG' and kb='$fmKELOMPOK' and kc='$fmSUBKELOMPOK' and kd='$fmSUBSUBKELOMPOK' and ke='0'"));//$vthnakun 
				
			$listSubSubKelompok = $get['ka'].'. '.$get['kb'].'.'.$get['kc'].'.'.$get['kd'].'. '.$get['nm_account']."<input type='hidden' id= 'fmSUBSUBKELOMPOK' name='fmSUBSUBKELOMPOK' value='$fmSUBSUBKELOMPOK' >";
		}
		//if($arr['4'] <> '') $fmBIDANG = $arr['4'];
		//if($arr['5'] <> '') $fmBIDANG = $arr['5'];
		//$konFiltAkun = " and concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) like '".$_POST['filterAkun']."%'";
	}
	$cek .= " select ka,nm_account from ref_jurnal where ka<>'0' and kb='0'";// $vthnakun;  "; 
	if($arrakun['0'] == '') $listBidang = cmbQuery1("fmBIDANG",$fmBIDANG,"select ka,nm_account from ref_jurnal where ka<>'0' and kb='0' ","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih	','');
	
	if($arrakun['1'] == '') $listKelompok = cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select kb,nm_account from ref_jurnal where ka='$fmBIDANG' and kb<>'0' and kc='0' ","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','');
	
	if($arrakun['2'] == '') $listSubKelompok = cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select kc,nm_account from ref_jurnal where ka='$fmBIDANG' and kb ='$fmKELOMPOK' and kc<>'0' and kd='0' ","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','');
	
	if($arrakun['3'] == '') $listSubSubKelompok = cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select kd,nm_account from ref_jurnal where ka='$fmBIDANG' and kb ='$fmKELOMPOK' and kc = '$fmSUBKELOMPOK' and kd<>'0' and ke='0' ","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','');
	
	
	if($Main->SHOW_CEK== FALSE) $cek = '';
				
	$TampilOpt = 
			//"<tr><td>".	
			"<div style='display:none;'> $cek </div>".
			"<div class='FilterBar'>".
			//<table style='width:100%'><tbody><tr><td align='left'>
			//<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			//<tbody><tr valign='middle'>   						
			//	<td align='left' style='padding:1 8 0 8; '>".
			//"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			
			"<table style='width:100%'>
			<tr>
			<td style='width:150px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".//$fmThnAnggaran - $aqry1 - ".
			//"select ka,nm_account from ref_jurnal where ka<>'0' and kb='0' $vthnakun ".
			$listBidang.
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			$listKelompok.
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			$listSubKelompok.
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			$listSubSubKelompok.
			"</td>
			</tr>
			
			</table>".
			"</div>".
			"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Akun : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='' size=10px>&nbsp
				Nama Akun : <input type='text' id='fmAKUN' name='fmAKUN' value='".$fmAKUN."' size=30px>&nbsp".
				"<input type='hidden' id='filterAkun' name='filterAkun' value='".$filterAkun."'>".
				"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>"
			;		
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//$thn_akun = $HTTP_COOKIE_VARS['coThnAnggaran'];
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		$fmBIDANG = $_REQUEST['fmBIDANG'];
		$fmKELOMPOK = $_REQUEST['fmKELOMPOK'];
		$fmSUBKELOMPOK = $_REQUEST['fmSUBKELOMPOK'];
		$fmSUBSUBKELOMPOK = $_REQUEST['fmSUBSUBKELOMPOK'];	
		//$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];	
		$filterAkun = cekPOST('filterAkun');	
		
		/*if($Main->WITH_THN_ANGGARAN){
			$aqry1 = "select Max(thn_akun) as thnMax from ref_jurnal where 
					thn_akun<='$fmThnAnggaran'";
					$qry1=mysql_query($aqry1);			
					$qry_jurnal=mysql_fetch_array($qry1);
					$thn_akun=$qry_jurnal['thnMax'];
					$arrKondisi[] = " thn_akun = '$thn_akun'";														
				
		}*/	
		//$fmKODE = $_REQUEST['fmKODE'];
		//$fmAKUN = $_REQUEST['fmAKUN'];		
		
		/*switch($fmPILCARI){
			case 'selectfg': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectbarang': $arrKondisi[] = " nama_barang like '%".$fmPILCARIvalue."%'"; break;					 	
		}*/
		
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
			//$arrKondisi[]= "ka !=00 and kb=00 and kc=00 and kd=00 and ke=00";
		}
		elseif(!empty($fmBIDANG) && empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "ka =$fmBIDANG";//$arrKondisi[]= "ka =$fmBIDANG and kb!=00 and kc=00 and kd=00 and ke=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "ka =$fmBIDANG and kb=$fmKELOMPOK";//$arrKondisi[]= "ka =$fmBIDANG and kb=$fmKELOMPOK and kc!=00 and kd=00 and ke=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "ka =$fmBIDANG and kb=$fmKELOMPOK and kc=$fmSUBKELOMPOK";//$arrKondisi[]= "ka =$fmBIDANG and kb=$fmKELOMPOK and kc=$fmSUBKELOMPOK and kd!=00 and ke=00";			
		}
		elseif(!empty($fmBIDANG) && !empty($fmKELOMPOK) && !empty($fmSUBKELOMPOK) && !empty($fmSUBSUBKELOMPOK))
		{
			$arrKondisi[]= "ka =$fmBIDANG and kb=$fmKELOMPOK and kc=$fmSUBKELOMPOK and kd=$fmSUBSUBKELOMPOK";			
		}
//		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) like '".$_POST['fmKODE']."%'";	
		if(!empty($_POST['fmKODE'])){
			$pecahkodebarang = explode(".",$_POST['fmKODE']);
			if($pecahkodebarang[0] != '')$arrKondisi[]= " ka LIKE '%".$pecahkodebarang[0]."%'";
			if($pecahkodebarang[1] != '')$arrKondisi[]= " kb LIKE '%".$pecahkodebarang[1]."%'";
			if($pecahkodebarang[2] != '')$arrKondisi[]= " kc LIKE '%".$pecahkodebarang[2]."%'";
			if($pecahkodebarang[3] != ''){
				if (substr($pecahkodebarang[3],0,1)=='0')$pecahkodebarang[3]= substr($pecahkodebarang[3],1);
				$arrKondisi[]= " kd LIKE '%".$pecahkodebarang[3]."%'";	
				}
			if($pecahkodebarang[4] != ''){	
				if (substr($pecahkodebarang[4],0,1)=='0')$pecahkodebarang[4]= substr($pecahkodebarang[4],1);
				$arrKondisi[]= " ke LIKE '%".$pecahkodebarang[4]."%'";	
				}	
		}			
		if(!empty($_POST['fmAKUN'])) $arrKondisi[] = " nm_account like '%".$_POST['fmAKUN']."%'";
		
	//	if(!empty($_POST['filterAkun'])) $arrKondisi[] = " concat(ka,'.',kb,'.',kc,'.',kd,'.',ke,'.',kf) like '".$_POST['filterAkun']."%'";					
		

 		
	
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
		//	case '': $arrOrders[] = " concat(ka,kb,kc,kd,ke,kf) $ASC " ;break;
		//	case '1': $arrOrders[] = " concat(ka,kb,kc,kd,ke, kf) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}

			$Order= join(',',$arrOrders);	
			/*$OrderDefault = ' order by ka,kb,kc,kd,ke,kf ';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;*/
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
$RefAkun = new RefAkunObj();

?>