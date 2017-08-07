<?php
/***
	salinan dari fnuseraktivitas.php
	requirement:
	 - daftarobj2 di DaftarObj2.php
	 - global variable di vars.php
	 - library fungsi di fnfile.php
	 - connect db  di config.php
***/

class UsulanHapusbadetailObj  extends DaftarObj2{	
	var $Prefix = 'UsulanHapusbadetail';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName =  'v1_penghapusan_usul_det_bi';
		//'penghapusan_usul_det'; //daftar
	var $TblName_Hapus = 'penghapusan_usul_det';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id','sesi','id_bukuinduk');//('p','q'); //daftar/hapus
	var $FieldSum = array('jml_harga');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 8, 7, 7);//berdasar mode
	var $FieldSum_Cp2 = array( 5, 5, 5);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Penghapusan';
	var $PageIcon = 'images/penghapusan_ico.gif';
	var $FormName =  'UsulanHapusba_form';// 'UsulanHapus_form';
	var $pagePerHal = 10;
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulanhapusba.xls';
	var $Cetak_Judul = 'Berita Acara Usulan Penghapusan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $arrtindak_lanjut = array(
			array('1','DITOLAK'), //$arrtindak_lanjut[$dt['tindak_lanjut']-1]['1'] $dt['tindak_lanjut']=1; $x=0=>x=1-1=>x=$dt['tindak_lanjut']-1
			array('2','DIMUSNAHKAN'), //$arrtindak_lanjut['1']['1']	$dt['tindak_lanjut']=2;$x=2-1=>x=$dt['tindak_lanjut']-1
			array('3','DIPINDAHTANGANKAN'),	 //$arrtindak_lanjut['2']['1'] $dt['tindak_lanjut']=3
				//ditabel 1 =1,2=1,tabel 3=1
		);//$arrtindak_lanjut[1][1][2][1]
	var $status = array(
			array('1','ADA'), 
			array('2','TIDAK'),
		);
		
	var $arrkonBrg = array(
			array('1','Baik'), 
			array('2','Kurang Baik'),
			array('3','Rusak Berat'),
		);
	
				
	function setPage_HeaderOther(){
		return "";
		
	}
	
	function setTitle(){
		return '';
	}

	function setMenuEdit(){
		
		return'';
		//"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
		//"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";			
	}
	
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return
		    
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
			
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
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				
				$keterangan = $_REQUEST['Keterangan'];
				$tgl_ket_usul = $_REQUEST['tgl_ket_usul'];
				
				$cek .=$idplh;				
				
				
				
				if($fmST == 0){
					
					if($err==''){
						$aqry = "insert into penghapusan_usul_det (id_bukuinduk,tgl_update,uid,ket_usul,tgl_ket_usul)"."values('$id_bukuinduk',now(),'$uid','$keterangan','$tgl_ket_usul')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from penghapusan_usul where Id='$idplh'"));
					if( $err=='' ){
						if($no_usulan!=$old['no_usulan'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from penghapusan_usul where no_usulan='$no_usulan' "
							));
							if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
						}
					}
					
					if($err==''){
											
						//ambil array daftarpilih edit
						$daftarpilih = $_REQUEST[$this->Prefix.'_daftarpilih'];//Id,id_bukuinduk cat:awalnya string
						$arrDF = explode(',',$daftarpilih);//dirubah jadi array['Id','id_bukuinduk']
						for($i = 0; $i<count($arrDF); $i++){
							$iddbi = $arrDF[$i];
							
							//*********************************************************
							$arr = explode(' ',$iddbi);
							$Id = $arr[0];
							$sesi = $arr[1];
							$id_bukuinduk = $arr[2];
																											
							//update tabel penghapusan_usul_det
							$sql = "update penghapusan_usul_det 
							         set "." tgl_update =now(),
											 uid ='$uid',
											tgl_ket_usul='$tgl_ket_usul',
											 ket_usul='$keterangan'".
									// "where Id='".$idplh."' and id_bukuinduk";	$cek .= $sql;
									 "where Id='".$Id."' and sesi='".$sesi."' and id_bukuinduk='".$id_bukuinduk."' ";	$cek .= $sql; 
							$query = mysql_query($sql);
							
							//*****************************************
							
						}
						
					}
					
				}
				
				//
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}
	
	function simpanPr(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = $Main->DEF_WILAYAH;
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $e1 = $_REQUEST['e1'];
							
	 $ket_ba = $_REQUEST['ket_ba'];
	 $tgl_ket_ba = $_REQUEST['tgl_ket_ba'];
	 $tindak_lanjut = $_REQUEST['tindak_lanjut'];
	 $status = $_REQUEST['status'];
	 $konBrg = $_REQUEST['konBrg'];
	 $cek .=$idplh;			
	 
	 if( $err=='' && $tgl_ket_ba =='' ) $err= ' Tanggal Pengecekan harus diisi !!';
	 if( $err=='' && $status =='' ) $err= ' Ada/Tidak harus diisi !!';
	// if( $err=='' && $tgl_ket_ba =='0000-00-00' ) $err= ' Tanggal harus  diisi !!';
	 if($status =='1'){ //jika status barang pengecekan Ada
	 	if( $err=='' && $konBrg =='' ) $err= ' Kondisi harus  diisi !!';	
	 	if( $err=='' && $tindak_lanjut =='' ) $err= 'Tindak Lanjut harus  diisi !!';	
	 }
	 									
  	 if($fmST == 0){ //gak kepake?
		if($err==''){
	  	   $aqry = "INSERT into penghapusan_usul_det(ket_ba,tgl_ket_ba,tindak_lanjut,status,kondisi)"."
		  		    values('$ket_ba','$tgl_ket_ba','$tindak_lanjut','$status','$konBrg')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
		 }
	 }else{
		$old = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id='$idplh'"));
		if( $err=='' ){
			if($no_usulan!=$old['no_usulan'] ){
				$get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM penghapusan_usul WHERE no_usulan='$no_usulan' "));
					if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
			}
		}
		//cek tgl ba > tglusul
	 	//$aqry = "select * from penghapusan_usul where id=''";
	 	//$old = mysql_fetch_array(mysql_query($aqry));
		//if($err=='' && $old['tgl_usul'] > $tgl_ket_ba ) $err = "Tanggal pengecekan tidak lebih kecil dari tanggal usulan! ";
	
		if($err==''){
		 //ambil array daftarpilih edit
		 $daftarpilih = $_REQUEST[$this->Prefix.'_daftarpilih'];//Id,id_bukuinduk cat:awalnya string
		 $arrDF = explode(',',$daftarpilih);//dirubah jadi array['Id','id_bukuinduk']
			for($i = 0; $i<count($arrDF); $i++){
			  	$iddbi = $arrDF[$i];
				//*********************************************************
				$arr = explode(' ',$iddbi);
				$Id = $arr[0];
				$sesi = $arr[1];
				$id_bukuinduk = $arr[2];
				//get data usulan
				$usul = mysql_fetch_array(mysql_query("select * from penghapusan_usul where id='$Id'"));
				//cek tgl pengecekan > tglusul ------------------------------------------
				if($err=='' && $usul['tgl_usul'] > $tgl_ket_ba ) $err = "Tanggal pengecekan tidak lebih kecil dari tanggal usulan!";
				//cek barang rusak berat tidak boleh dipindahtangankan ------------------
				if($err=='' && $konBrg=='3' && $tindak_lanjut=='3' ) $err = "Barang rusak berat tidak boleh dipindahtangankan!";
				if($err==''){
					//update tabel penghapusan_usul_det
					$sql = "UPDATE penghapusan_usul_det 
					        SET "." tgl_update =now(),
								  	       uid ='$uid',
										ket_ba = '$ket_ba',
									tgl_ket_ba = '$tgl_ket_ba',
								  tindak_lanjut = '$tindak_lanjut',
								    	status = '$status',
									   kondisi = '$konBrg' ". //kondisi di tabel penghapusan_usul_det
											// "where Id='".$idplh."' and id_bukuinduk";	$cek .= $sql;
							"where Id='".$Id."' and sesi='".$sesi."' and id_bukuinduk='".$id_bukuinduk."' ";	$cek .= $sql; 
					$query = mysql_query($sql);	
				}else{
					break;
				}
				
						//*****************************************
			} //end for
						
		} //end if
	
	   } //end else
				
	     return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}
			
	function simpanPilih(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$idusul = $_POST['idusul']; if(empty($idusul)) $idusul= 0;
		$sesi = $_POST['sesicari']; if($idusul!=0) $sesi='';
		
		$coDaftar = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];$cek .=$coDaftar;

		
		$ids= explode(',',$coDaftar); //$_POST['cidBI'];	//id bi barang
		
		
		
		$valuearr = array();
		for($i = 0; $i<count($ids); $i++)	{
			
			$valuearr[]= "('$idusul','".$ids[$i]."','$sesi', '$uid', now())";
			//cek id buku induk sudah ada!
			$aqry = "select count(*) as cnt from penghapusan_usul_det where Id='$idusul' and sesi='$sesi' and id_bukuinduk='".$ids[$i]."' "; $cek.= $aqry;
			$get = mysql_fetch_array(mysql_query(
				$aqry
			));
			if($get['cnt']>0){
				$bi = mysql_fetch_array(mysql_query(
					"select concat(a1,'.',a,'.',b,'.',c,'.',d,'.',substring(thn_perolehan,3,2),'.', e,'.', e1,'.',f,'.',g,'.',h,'.',i,'.',j,'.',noreg) as barcode from buku_induk where Id='".$ids[$i]."' "
				));				
				$err = 'Barang dengan kode '.$bi['barcode'].' sudah ada!';
				break;
			}
		}
		$valuestr = join(',',$valuearr);
		
		
		if($err==''){
			$aqry= "replace into penghapusan_usul_det (Id,id_bukuinduk,sesi, uid, tgl_update) values ".$valuestr; $cek .= $aqry;
			//$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
			$qry = mysql_query($aqry);
			if ($qry==FALSE){
				$err = 'Gagal Simpan Data';
			}
			
			//delete waktu dan sesi lebih dari 3 hari
			$aqry = "delete  from penghapusan_usul_det where Id=0 and (sesi IS not null and sesi <>'') and tgl_update  < DATE_SUB(CURDATE(), INTERVAL 2 DAY) ;"; $cek .= $aqry;
			$del = mysql_query($aqry);										
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}

	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'simpanPilih':{
				$get= $this->simpanPilih();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				//$cek = 'trs';
				break;
			}
			case 'cbxgedung':{
				$c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
				$d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				if($c=='' || $c =='00') {
					$kondC='';
				}else{
					$kondC = "and c = '$c'";
				}
				if($d=='' || $d =='00') {
					$kondD='';
				}else{
					$kondD = "and d = '$d'";
				}
				if($e=='' || $e =='00') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				if($e1=='' || $e1 =='00'  || $e1 =='000') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				
				$aqry = "select * from ref_ruang where q='0000' $kondC $kondD $kondE $kondE1";
				$cek .= $aqry;
				$content = genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						$aqry,
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" );
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
			case 'formPeriksa':{				
				$fm = $this->setFormPeriksa();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpanPr':{
				
				$get= $this->simpanPr();
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
			case 'batal':{
				
				$get= $this->batal();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'getdata':{
				$id = $_REQUEST['id'];
				$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
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
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
		}
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
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>**/
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/usulanhapussk.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormPeriksa(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$Id = $kode[0];
		$sesi = $kode[1];
		$id_bukuinduk = $kode[2];
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from penghapusan_usul_det where Id ='".$Id."' and sesi = '".$sesi."' and id_bukuinduk='".$id_bukuinduk."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		if($dt['tgl_ket_ba']=='') $dt['tgl_ket_ba'] =  date('Y-m-d');
		$fm = $this->setFormPR($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$Id = $kode[0];
		$sesi = $kode[1];
		$id_bukuinduk = $kode[2];
		
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from penghapusan_usul_det where Id ='".$Id."' and sesi = '".$sesi."' and id_bukuinduk='".$id_bukuinduk."' "; $cek.=$aqry;
		
		$dt = mysql_fetch_array(mysql_query($aqry));
			
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 640;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];		
						
		//ambil data a1,a,b,c,d,e,f,g,h,i,j,thn_perolehan,no_reg di tabel buku induk
		$get = mysql_fetch_array(mysql_query(
								"SELECT a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan from buku_induk 
								 WHERE Id ='".$dt['id_bukuinduk']."'"));
								 
		$kodea1 = $get['a1']; $kodea = $get['a']; $kodeb = $get['b']; $kodec = $get['c'];$koded = $get['d']; 
		$kodee = $get['e'];$kodee1 = $get['e1']; $kodef = $get['f']; $kodeg = $get['g']; $kodeh = $get['h']; $kodei = $get['i']; $kodej = $get['j'];	
		$noreg = $get['noreg']; $thn_perolehan = $get['thn_perolehan'];
		
		//ambil data thn perolehan 2 angka di belakang contoh:2008 jadi 08
		$thnPer = substr($thn_perolehan,2,2);
		
		//ambil data di ref barang
		$get = mysql_fetch_array(mysql_query(
						   "SELECT*from ref_barang WHERE f='".$kodef."' 
						   							 AND g='".$kodeg."'
													 AND h='".$kodeh."'
													 AND i='".$kodei."'
													 AND j='".$kodej."'
													 "));
		$f = $get['f'];
		$g = $get['g'];
		$h = $get['h'];
		$i = $get['i'];
		$j = $get['j'];
		$namaBarang = $get['nm_barang'];
		
		$this->form_fields = array(				
			//'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			//'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			//'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			
			'kode_barang' => array(  
				'label'=>'Kode Barang', 
				'value'=> $kodea1.'.'.$kodea.'.'.$kodeb.'.'.$kodec.'.'.$koded.'.'.$thnPer.'.'.$kodee.'.'.$kodee1.'.'.$kodef.'.'.$kodeg
						  .'.'.$kodeh.'.'.$kodei.'.'.$kodej.'.'.$noreg,
				'type'=>'' 
			),
			'nama_barang' =>array(
				'label'=>'Nama Barang',
				//'value'=>$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j.' '.$namaBarang, //validasi apakah kode tersebut benar
				'value'=>$namaBarang, 
				'type'=>''
			),
			
			'tgl_ket_usul' => array( 
				'label'=>'Tgl. Keterangan',
				'value'=>$dt['tgl_ket_usul'], 
				'type'=>'date'
			 ),
			'Keterangan' =>array(
				'label'=>'Keterangan',
				'value'=>
					'<textarea id="Keterangan"  name="Keterangan" style="margin: 2px; width: 453px; height: 40px;">'.$dt['ket_usul'].'</textarea>',
				'labelWidth'=>120, 
				'type'=>'' , 
				'row_params'=> " valign='top'"
			)			
			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close(1)' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		$hsl = '';
		if($KeyValueStr!=''){
			
		
			$hsl = "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');".$this->Prefix.".cbxPilih(this);\" />";					
		}
		return $hsl;
	}
	
	function setFormPR($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$tindak_lanjut = $_REQUEST['tindak_lanjut'];
		$status = $_REQUEST['status'];
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 640;
		$this->form_height = 180;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Pengecekan';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='$kdSubUnit0' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."' "));
		$subunit = $get['nm_skpd'];		
						
		//ambil data a1,a,b,c,d,e,f,g,h,i,j,thn_perolehan,no_reg di tabel buku induk
		$get = mysql_fetch_array(mysql_query(
								"SELECT a1,a,b,c,d,e,e1,f,g,h,i,j,noreg,thn_perolehan from buku_induk 
								 WHERE Id ='".$dt['id_bukuinduk']."'"));
								 
		$kodea1 = $get['a1']; $kodea = $get['a']; $kodeb = $get['b']; $kodec = $get['c'];$koded = $get['d']; 
		$kodee = $get['e'];$kodee1 = $get['e1']; $kodef = $get['f']; $kodeg = $get['g']; $kodeh = $get['h']; $kodei = $get['i']; $kodej = $get['j'];	
		$noreg = $get['noreg']; $thn_perolehan = $get['thn_perolehan'];
		
		//ambil data thn perolehan 2 angka di belakang contoh:2008 jadi 08
		$thnPer = substr($thn_perolehan,2,2);
		
		//ambil data di ref barang
		$get = mysql_fetch_array(mysql_query(
						   "SELECT*from ref_barang WHERE f='".$kodef."' 
						   							 AND g='".$kodeg."'
													 AND h='".$kodeh."'
													 AND i='".$kodei."'
													 AND j='".$kodej."'
													 "));
		$f = $get['f'];
		$g = $get['g'];
		$h = $get['h'];
		$i = $get['i'];
		$j = $get['j'];
		$namaBarang = $get['nm_barang'];
		
		$this->form_fields = array(				
			//'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			//'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			//'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			/**'kode_barang' => array(  
				'label'=>'Kode Barang', 
				'value'=> $kodea1.'.'.$kodea.'.'.$kodeb.'.'.$kodec.'.'.$koded.'.'.$thnPer.'.'.$kodee.'.'.$kodef.'.'.$kodeg
						  .'.'.$kodeh.'.'.$kodei.'.'.$kodej.'.'.$noreg,
				'type'=>'' 
			),
			'nama_barang' =>array(
				'label'=>'Nama Barang',
				//'value'=>$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j.' '.$namaBarang, //validasi apakah kode tersebut benar
				'value'=>$namaBarang, 
				'type'=>''
			),
			**/
			'tgl_ket_ba' => array( 
				'label'=>'Tgl. Pengecekan',
				'value'=>$dt['tgl_ket_ba'], 
				'type'=>'date'
			 ),
			 'status'=>array(
					'label' =>'Ada / Tidak',
					'value' =>
						cmbArray('status',$dt['status'],$this->status,'-- PILIH --','')//generate checkbox
			),
			
			'konBrg'=>array(
					'label' =>'Kondisi Barang',
					'value' =>
						cmbArray('konBrg',$dt['kondisi'],$this->arrkonBrg,'-- PILIH --','')//generate checkbox
			),
			'tindak_lanjut'=>array(
					'label' =>'Tindak Lanjuti',
					'value' =>
						cmbArray('tindak_lanjut',$dt['tindak_lanjut'],$this->arrtindak_lanjut,'-- PILIH --','')//generate checkbox
			),
			'ket_ba' =>array(
				'label'=>'Keterangan',
				'value'=>
					'<textarea id="ket_ba"  name="ket_ba" style="margin: 2px; width: 453px; height: 40px;">'.$dt['ket_ba'].'</textarea>',
				'labelWidth'=>120, 
				'type'=>'' , 
				'row_params'=> " valign='top'"
			),
			//'tindaklanjut'			
			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanPr()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close(1)' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$daftar_mode = $_REQUEST['daftar_mode'];
		switch($daftar_mode){
			case 1:{
				$headerTable =
					"<hr><h3>DAFTAR BARANG <div style='float:right'><input type='button' value='Refresh' onclick='UsulanHapusbadetail.refreshList(true)' ></div></h3>".
					"<thead>".
											
						/*"<tr><td colspan ='15'>
						
						<h3>DAFTAR BARANG</h3>
						</td></tr>".*/
						"<tr>
						<th class='th02' colspan='3'>Nomor</th>
						<th class='th02' colspan='3'>Spesifikasi Barang</th>
						<th class='th01' width='20' rowspan='2'>Tahun Perolehan</th>
						<th class='th01' width='20' rowspan='2'>Kondisi<br> Barang</th>
						<th class='th01' rowspan='2'>Harga<br> Perolehan</th>
						<th class='th02' colspan='5'>Pengecekan</th>
						
						
						</tr>	
						<tr>
							<th class='th01' width='20' >No.</th>
							$Checkbox		
							<th class='th01' >Kode <br>Barang</th>
							<th class='th01' >Nama /Jenis <br> Barang</th>
							<th class='th01' >Merk/Tipe</th>
							<th class='th01' width='30'>No. Sertifikat / No. Pabrik / No.Mesin</th>
							<th class='th01' >Tanggal</th>
							<th class='th01' >Ada / Tidak</th>
							<th class='th01' >Tindak <br>Lanjut</th>
							<th class='th01' >Kondisi<br>Barang</th>
							<th class='th01' >Keterangan</th>
							
						</tr>							
						
										
						
					</thead>";
				break;
			}
			default:{
				$headerTable =
					"<thead>
						<tr>
						<hr><h3>DAFTAR BARANG</h3>
						<th class='th02' colspan='4'>Nomor</th>
						<th class='th02' colspan='3'>Spesifikasi Barang</th>
						<th class='th01' width='10' rowspan='2'>Tahun Perolehan</th>
						<th class='th01'  width='10' rowspan='2'>Keadaan Barang</th>
						<th class='th01' width='10' rowspan='2'>Harga Perolehan</th>
						<th class='th02' colspan='2'>Keterangan</th>
						</tr>	
						<tr>
							<th class='th01' width='20' >No.</th>
							$Checkbox		
							<th class='th01'    width='30' >Kode Barang</th>
							<th class='th01'  >No. Reg</th>
							<th class='th01' width='30'>Nama Jenis Barang</th>
							<th class='th01' width='20'>Merk/Tipe</th>
							<th class='th01' width='20'>No. Sertifikat / No. Pabrik / No.Mesin</th>
							<th class='th01' >Tgl</th>
							<th class='th01'>Keterangan</th>
						</tr>			
						
					</thead>";
				break;
			}
		}
		
		
		return $headerTable;
	}
	
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		global $Main;
		$daftar_mode = $_REQUEST['daftar_mode'];
		
		$tindak_lanjut = $_REQUEST['tindak_lanjut'];
		$status = $_REQUEST['status'];
		$konBrg = $_REQUEST['konBrg'];
		
				
		//--- get dinas		
		$dinas = '';		
		$c=''.$isi['c'];
		$d=''.$isi['d'];
		$e=''.$isi['e'];		
		$e1=''.$isi['e1'];		
		$nmopdarr=array();				
		$get = mysql_fetch_array(mysql_query(
			"select * from v_bidang where c='".$c."' "
		));		
		if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
		$get = mysql_fetch_array(mysql_query(
			"select * from v_opd where c='".$c."' and d='".$d."' "
		));		
		if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
		$get = mysql_fetch_array(mysql_query(
			"select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"
		));		
		if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
		$nmopd = join(' - ', $nmopdarr );
			
		//ambil id buku induk di tabel penghapusan_usul_det
		 $id = ''.$isi['id_bukuinduk'];		
		 
		//ambil data no reg,thn_perolehan,jml_harga,kondisi dari tabel buku induk berdasarkan Id dari Id tabel penghapusan_usul_det
		$bi = mysql_fetch_array
				(mysql_query("SELECT * from buku_induk WHERE Id ='".$id."'"));
		
		//thn perolehan ini khusus untuk di pakai di kode barang
		  $thnPER =substr($bi['thn_perolehan'],2,2) ;
		   					
		$noreg = $bi['noreg'];
		$thn_perolehan = $bi['thn_perolehan'];
		//$jml_harga = $bi['jml_harga'];
		$jml_harga=$bi['jml_harga']==0?'':number_format($bi['jml_harga'],2,',','.');
		$kondisi = $bi['kondisi'];
		
		/*ambil data nama barang di tabel ref_barang berdasarkan f,g,h,i,j = f,g,h,i,j di tabel buku induk
		  dan Id di tabel buku induk= Id buku induk di penghapusan_usul_det	
		*/
		$brng =mysql_fetch_array(mysql_query("SELECT* FROM ref_barang
											    WHERE   f = '".$bi['f']."'
												    AND g = '".$bi['g']."'
											      	AND h = '".$bi['h']."'
											      	AND i = '".$bi['i']."'
											     	AND j = '".$bi['j']."'
												  	
											 "));
		#ambil data f di tabel buku induk berdasarkan Id di tabel buku induk= id buku induk di tabel penghapusan usul_det
		$f = $bi['f'];
		
		switch ($f)
		{
		case '01':{
		 #ambil kib a berdasarkan f di buku induk jika f=01
		 $getkiba = mysql_fetch_array(mysql_query("SELECT sertifikat_no from kib_a
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
		                                            AND e = '".$bi['e']."' 
		                                            AND e1 = '".$bi['e1']."' 
												 	AND f ='".$bi['f']."' 
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."' 
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."'
												  "));
		  $merk='-';
		  $spesifikasi = $getkiba['sertifikat_no'] !=''?$getkiba['sertifikat_no'].'/ /':'';
		  break;
		  }
		case '02':{
		 #ambil kib b berdasarkan f di buku induk jika f=02
		$getkibb = mysql_fetch_array(mysql_query("SELECT merk,no_pabrik,bahan,no_mesin from kib_b
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
		                                            AND e = '".$bi['e']."' 
		                                            AND e1 = '".$bi['e1']."' 
												  	AND f ='".$bi['f']."' 
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."' 
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."'
												  ")); 
		
	       $merk =$getkibb['merk']; 
		   $spesifikasi = $getkibb['no_mesin'] !=''? '/'.$getkibb['no_mesin'].'/':'/ ';
		   $spesifikasi .= $getkibb['no_pabrik'] !=''? $getkibb['no_pabrik'].'/':'';//$getkibb['no_pabrik'];
		  // $spesifikasi .= $getkibb['bahan'] !=''?$getkibb['bahan']:'';
		   
		   
		  		 
		  break;
		}
		case '03':{
		 #ambil kib c berdasarkan f di buku induk jika f=03
		$getkibc = mysql_fetch_array(mysql_query("SELECT dokumen_no from kib_c
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
		                                            AND e = '".$bi['e']."' 
		                                            AND e1 = '".$bi['e1']."' 
												  	AND f ='".$bi['f']."' 
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."'
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."' 
												  "));
		$merk='-';
		$spesifikasi = $getkibc['dokumen_no'] !=''?$getkibc['dokumen_no'].'/ /':'-';  
		  break;
		}
		case '04':{
		 #ambil kib d berdasarkan f di buku induk jika f=04
		$getkibd = mysql_fetch_array(mysql_query("SELECT dokumen_no from kib_d
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
		                                            AND e = '".$bi['e']."'  
		                                            AND e1 = '".$bi['e1']."' 
												  	AND f ='".$bi['f']."' 
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."' 
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."' 
												  "));
		$merk='-';
		$spesifikasi = $getkibd['dokumen_no'] !=''?$getkibd['dokumen_no'].'/ /':'';  
		  break;
		}
		case '05':{
		 #ambil kib e berdasarkan f di buku induk jika f=05
		$getkibe = mysql_fetch_array(mysql_query("SELECT * from kib_e
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
		                                            AND e = '".$bi['e']."'  
		                                            AND e1 = '".$bi['e1']."' 
												  	AND f ='".$bi['f']."' 
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."' 
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."' 
												  "));  
		$merk='-';
		$spesifikasi = $getkibe['dokumen_no'] !=''?$getkibe['dokumen_no']:'-';  
		  break;
		}
		 
		default:{
			$getkibf = mysql_fetch_array(mysql_query("SELECT dokumen_no from kib_f
		                                          WHERE a1 = '".$bi['a1']."' 
		                                            AND a = '".$bi['a']."' 
		                                            AND b = '".$bi['b']."' 
		                                            AND c = '".$bi['c']."' 
		                                            AND d = '".$bi['d']."' 
												    AND f ='".$bi['f']."' 
		                                            AND e = '".$bi['e']."' 
													AND e1 = '".$bi['e1']."'  
		                                            AND g ='".$bi['g']."' 
		                                            AND h ='".$bi['h']."' 
		                                            AND i ='".$bi['i']."' 
		                                            AND j ='".$bi['j']."' 
													AND noreg = '".$bi['noreg']."'
													AND tahun = '".$bi['thn_perolehan']."' 
										  ")); 
		$merk='-';	
		$spesifikasi = $getkibf['dokumen_no'] !=''?$getkibf['dokumen_no']:'/';
		}
	}
		
		$Koloms = array();
		switch($daftar_mode){
			case 1:{
				$Koloms[] = array('align=right', $no.'.' );
				if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
				$Koloms[] = array('',$bi['a1'].'.'.$bi['a'].'.'.$bi['b'].'.'.$bi['c'].'.'.$bi['d'].'.'.$thnPER.'.'.$bi['e'].'.'.$bi['e1']
									 .'<br/>'.$bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] .'.'.$noreg);	
				//$Koloms[] = array('', $noreg);		
				$Koloms[] = array('', $brng['nm_barang']);		
				$Koloms[] = array('',$merk);		
				$Koloms[] = array('', $spesifikasi);				
				$Koloms[] = array('align=center', $thn_perolehan);				
				$Koloms[] = array('', $Main->KondisiBarang[$kondisi-1][1]);				
				$Koloms[] = array('align=right', $jml_harga);				
				
				$Koloms[] = array('align=center', TglInd($isi['tgl_ket_ba']));
				$Koloms[] = array('align=center', $this->status[ $isi['status']-1][1]);//$dt['status']
				$Koloms[] = array('', $this->arrtindak_lanjut[ $isi['tindak_lanjut']-1][1]);//$dt['tindak_lanjut']
				//$Koloms[] = array('align=center', $this->arrkonBrg[ $isi['kondisi']-1][1]);
				$Koloms[] = array('', $Main->KondisiBarang[ $isi['kondisi_usul'] -1][1]);		
				$Koloms[] = array('', $isi['ket_ba']);
				//$Koloms[] = array('', $isi['ket_usul']);								
				break;
			}		
			default:{
				$Koloms[] = array('align=right', $no.'.' );
				if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
				$Koloms[] = array('',$bi['a1'].'.'.$bi['a'].'.'.$bi['b'].'.'.$bi['c'].'.'.$bi['d'].'.'.$thnPER.'.'.$bi['e'].'.'.$bi['e1']
									 .'<br/>'.$bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'] .'.'.$noreg);	
				$Koloms[] = array('', $noreg);		
				$Koloms[] = array('', $brng['nm_barang']);		
				$Koloms[] = array('',$merk);		
				$Koloms[] = array('', $spesifikasi);				
				$Koloms[] = array('align=center', $thn_perolehan);				
				$Koloms[] = array('', $Main->KondisiBarang[$kondisi-1][1]);				
			
				//$Koloms[] = array('', $Main->KondisiBarang[ $isi['kondisi_usul'] -1][1]);				
				$Koloms[] = array('align=right', $jml_harga);				
				$Koloms[] = array('', TglInd($isi['tgl_ket_usul']));				
				$Koloms[] = array('', $isi['ket_usul']);
				break;
			}	
		}
		
		return $Koloms;
		
	}
	
	
	
	function setTopBar(){
	   // return genSubTitle($this->setTitle(),$this->genMenu());
		return "";
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			
			</table>";
			//genFilterBar(
				//''
				//,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			//);
		//return array('TampilOpt'=>$TampilOpt);
	}	
					   		
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		$id = $_REQUEST['UsulanHapusba_idplh']; //ambil data kondisi
		$sesi = $_REQUEST['sesi'];
		$genStr ='Id'.'='.$id; //ambil nama field untuk data kondisi
		if ($id !=''){$arrKondisi[]=$genStr;}  else{
			if($sesi !='')$arrKondisi[]='sesi'.'='."'$sesi'";
		}   		
		
		//kondisi barcode ---------------------
		$barcodeUsulanHapusBA_input = $_REQUEST['barcodeUsulanHapusBA_input'];
		if(!empty($barcodeUsulanHapusBA_input) ) $arrKondisi[] = " idall2 ='$barcodeUsulanHapusBA_input' ";
		 			 
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		$Order ="";
		//limit --------------------------------------
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
$UsulanHapusbadetail = new UsulanHapusbadetailObj();


?>