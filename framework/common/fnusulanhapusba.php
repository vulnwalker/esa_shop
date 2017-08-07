<?php
/***
	salinan dari fnuseraktivitas.php
	requirement:
	 - daftarobj2 di DaftarObj2.php
	 - global variable di vars.php
	 - library fungsi di fnfile.php
	 - connect db  di config.php
***/

class UsulanHapusbaObj  extends DaftarObj2{	
	var $Prefix = 'UsulanHapusba';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'penghapusan_usul'; //daftar
	var $TblName_Hapus = 'penghapusan_usul';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penghapusan';
	var $PageIcon = 'images/penghapusan_ico.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulanhapusba.xls';
	var $Cetak_Judul = 'Berita Acara Usulan Penghapusan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	//var $pagePerHal=2;
	var $arrtindak_lanjut = array(
			array('1','DITOLAK'), //$arrtindak_lanjut[$dt['tindak_lanjut']-1]['1'] $dt['tindak_lanjut']=1; $x=0=>x=1-1=>x=$dt['tindak_lanjut']-1
			array('2','DIMUSNAHKAN'), //$arrtindak_lanjut['1']['1']	$dt['tindak_lanjut']=2;$x=2-1=>x=$dt['tindak_lanjut']-1
			array('3','DIPINDAHTANGANKAN'),	 //$arrtindak_lanjut['2']['1'] $dt['tindak_lanjut']=3
				//ditabel 1 =1,2=1,tabel 3=1
		);//$arrtindak_lanjut[1][1][2][1]
	var $status = array(
			array('1','Ada'), 
			array('2','Tidak'),
		);
		
	var $arrkonBrg = array(
			array('1','Baik'), 
			array('2','Kurang Baik'),
			array('3','Rusak Berat'),
		);
	
	var $arrjabatan = array(
			array('1','Ketua'), //$arrjabatan[0][1] =$dt['jabatan']=1-1
			array('2','Wk.Ketua'), 
			array('3','Sekretaris'), 
			array('4','Anggota'), 
		);
		
	function setPage_HeaderOther(){
	global $Main;
	$menuhapussebagian = $Main->MODUL_PENGAPUSAN_SEBAGIAN ?"<A href=\"index.php?Pg=09&SPg=06\" title='Daftar Penghapusan Sebagian'>PENGHAPUSAN SEBAGIAN</a>":"";	
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=usulanhapus\" title='Usulan Penghapusan'>USULAN </a> |
			<A href=\"pages.php?Pg=usulanhapusba\" title='Berita Acara Penghapusan' style='color:blue'>PENGECEKAN</a>  |  
			<A href=\"pages.php?Pg=usulanhapussk\" title='Usulan SK Bupati'>USULAN SK</a>  |
			<A href=\"index.php?Pg=09&SPg=01\" title='Daftar Penghapusan'>PENGHAPUSAN </a> |
			$menuhapussebagian
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}
	
	
	function setTitle(){
		return 'Pengecekan';
	}
	
	function setMenuEdit(){
		
		return 
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakKKerja()","print_f2.png","K. Kerja", 'Cetak Kertas Kerja')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".BaruBA()","new_f2.png","Cek", 'Cek Barang')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Batal()","delete_f2.png","Batal BA", 'Batal Berita Acara')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakBA()","print_f2.png","Cetak BA", 'Cetak Berita Acara')."</td>";
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
				
				$no_usulan= $_REQUEST['no_usulan'];
				$tgl_usul= $_REQUEST['tgl_usul'];
				$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
				
				$get = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$pejabat_pengadaan."'"));
						$nama = $get['nama'];
						$nip = $get['nip'];
						$jabatan = $get['jabatan'];
				
				if( $err=='' && $no_usulan =='' ) $err= 'No Usulan belum diisi!';
				if( $err=='' && $tgl_usul =='' ) $err= 'Tgl Usul belum diisi!';
				//if( $err=='' && $pegawai =='' ) $err= 'Pegawai belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from penghapusan_usul where no_usulan='$no_usulan' "
						));
						if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into penghapusan_usul (a,b,c,d,e,e1,no_usulan,tgl_usul,tgl_update,uid,ref_idpegawai_usul)"."values('$a','$b','$c','$d','$e','$e1','$no_usulan','$tgl_usul',now(),'$uid','$pejabat_pengadaan')";	$cek .= $aqry;	
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
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						
						$aqry = "update penghapusan_usul 
						         set "." a='$a', 
								         b='$b', 
										 c='$c',
										 d='$d',
										 e='$e',
										 e1='$e1',
							 			 no_usulan='$no_usulan',
										 tgl_update =now(),
										 uid ='$uid',
										 tgl_usul='$tgl_usul',
										 ref_idpegawai_usul = '$pejabat_pengadaan'".
								 "where Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}
	
	function batal(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$cbid = $_REQUEST[$this->Prefix.'_cb']; //ambil data checkbox
				$idplh = $cbid[0]; //ambil data checkbox
				$a1 = $Main->DEF_KEPEMILIKAN;
				$a = $Main->Provinsi[0];
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$no_ba= $_REQUEST['no_ba'];
				$tgl_ba= $_REQUEST['tgl_ba'];
				$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
						
				//pengecekan No ba,Apakah no ba sudah di usulkan sk
				$getSK = mysql_fetch_array(mysql_query("select count(*) As cnt from penghapusan_usulsk_det where ref_idusulan = '".$idplh."'"));
				if($getSK['cnt'] > 0) {
				   $err = "No Ba tidak bisa dibatalkan,no ba ini sudah di usulkan SK";
				}
					
				if($fmST == 0) {					
					if($err==''){						
						//else{
						$aqry = "update penghapusan_usul 
						         set	no_ba  = NULL,
						         		tgl_ba  = NULL,
								 	    uid ='$uid',
								     	ref_idpegawai_ba =NULL".
								 " where Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
						//}
						
						$qyy = "update penghapusan_usul_det 
								set ket_ba = NULL,
								tgl_ket_ba = NULL,
								tindak_lanjut = NULL,
								status = NULL,
								kondisi = NULL 
								WHERE Id = '".$idplh."' ";$cek.=$qyy;
						
						$rts = mysql_query($qyy);
						
					}
				}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
		
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'gencetakKKerja':{
				$json=FALSE;
				$this->gencetakKKerja();
				break;
			}	
			case 'gencetakBA':{
				$json=FALSE;
				$this->gencetakBA();
				break;
			}	
			case 'formEditBA':{				
				$fm = $this->setFormEditBA();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpanBA':{
				
				$get= $this->simpanBA();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'formPilihPemeriksa':{				
				$fm = $this->setformPilihPemeriksa();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'hitungPanitia':{
				
				$get= $this->hitungPanitia();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'cbxgedung':{
				$c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
				$d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
				$e1= $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
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
				if($e1=='' || $e1 =='00' || $e1 =='000') {
					$kondE1='';
				}else{
					$kondE1 = "and e1 = '$e1'";
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
	
	function setFormEditBA(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from penghapusan_usul where Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['tgl_ba'] = date("Y-m-d"); //set waktu sekarang
		
		//$select =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk_det WHERE ref_idusulan = '".$dt['Id']."' "));
		
		//$read = mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usulsk WHERE Id ='".$select['Id']."' "));
		
		$fm = $this->setFormBA($dt);
		/**
		if($read['no_usulan_sk'] !=""){
			$fm['err'] = "No BA sudah di usulkan SK!";
		}else{
			$fm = $this->setFormBA($dt);	
		}
		**/		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setFormBA($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 800;
		$this->form_height = 400;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Pemeriksaan Usulan Penghapusan Barang';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];		
		
		//ambil pegawai
		$got = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$dt['ref_idpegawai_usul']."'"));
			$nama = $got['nama'];
			$nip = $got['nip'];
			$jabatan = $got['jabatan'];
			
		//Hitung Panitia
		$get = $this->hitungPanitia_ ($dt['Id']);
				$jmlPanitia= $get['content'].' Orang';
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'no_usulan' => array( 
						'label'=>'No. Usulan', 'value'=>$dt['no_usulan'], 
						'labelWidth'=>120, 
						'type'=>'' 
			    ),
			'tgl_usul' => array( 
						'label'=>'Tgl. Usulan',
						 'value'=>TglInd($dt['tgl_usul']), 
						 'type'=>''
						 
						 ),
			'no_ba' => array( 
						'label'=>'No. BA Pengecekan',
						//'value'=>$dt['no_ba'], 
						'value'=>"<input type='text' name='no_ba' value='".$dt['no_ba']."' size='43px'>", 
						'labelWidth'=>120, 
						'type'=>'' 
			    ),
			'tgl_ba' => array( 
						'label'=>'Tgl. BA Pengecekan',
						 'value'=>$dt['tgl_ba'], 
						 'type'=>'date'
						 ),
			
			'panitiapemeriksa' => array( 
						'label'=>'Panitia Pemeriksa',
						'value'=>'<a id="hitung2" href="javascript:UsulanHapusba.PilihPemeriksa()">'.$jmlPanitia.'</a>', 
						'type'=>''  
						   
						 ),
		);
		$formbarcode = $Main->BARCODE_ENABLE ? "<span style='color:red'>BARCODE</span><br>
				<input type='TEXT' value='' id='barcodeUsulanHapusBA_input' name='barcodeUsulanHapusBA_input' 
				style='font-size:24;width: 369px;' size='28' maxlength='28' ".				
				">".
				"<span id='barcodeUsulanHapusBA_msg' name='barcodeUsulanHapusBA_msg'>
					<a style='color:red;' href='javascript:barcodeUsulanHapusBA.setInputReady()'>Not Ready! (click for ready)</a>".
				"</span>":"" ;
		$fm1 =  "<table width='100%'><tr valign='top'><td>".
			$this->setForm_content().
			"</td><td width='375'>".
			$formbarcode.
			"</td></table>";
		$this->form_fields = array(				
			/*'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'no_usulan' => array( 
						'label'=>'No. Usulan', 'value'=>$dt['no_usulan'], 
						'labelWidth'=>120, 
						'type'=>'' 
			    ),
			'tgl_usul' => array( 
						'label'=>'Tgl. Usulan',
						 'value'=>TglInd($dt['tgl_usul']), 
						 'type'=>''
						 
						 ),*/
			'fm1' => array( 
						'label'=>'',						
						'value'=>$fm1, 
						'labelWidth'=>120, 
						'type'=>'merge' 
			    ),
			
			
			'daftardetail' => array( 
						'label'=>'',
						 'value'=>"<div id='div_detail' style='height:5px'></div>", 
						 'type'=>'merge'
						 )
			
		);
		/**13 Juni 2013**/
		//jika No ba sudah diusulkan SK maka Pengecekan,Simpan dihilangkan dan Batal dirubah namanya jadi close
		$qu = "SELECT Id FROM penghapusan_usulsk_det WHERE ref_idusulan = '".$dt['Id']."' ";$cek .=$qu;
		$read = mysql_fetch_array(mysql_query($qu));
		
		$select = mysql_fetch_array(mysql_query("SELECT no_usulan_sk FROM penghapusan_usulsk WHERE Id = '".$read['Id']."' "));
		 if($select['no_usulan_sk'] !=''){
		 	$Cek = "<input type='button' value='Pengecekan' onclick ='UsulanHapusbadetail.periksaEntry()' title='Entry Hasil Pengecekan' style = 'visibility:hidden'>";
		 	$Simpan = "<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBA()' style = 'visibility:hidden'>";
			$Batal = "<input type='button' value='Close' onclick ='".$this->Prefix.".Close()' style = 'visibility:visible'>";
		 }else{
		 	$Cek = "<input type='button' value='Pengecekan' onclick ='UsulanHapusbadetail.periksaEntry()' title='Entry Hasil Pengecekan' style = 'visibility:visible'>";
		 	$Simpan = "<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBA()' style = 'visibility:visible'>";
			$Batal = "<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' style = 'visibility:visible'>";
		 }				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			//"<input type='button' value='Pemeriksaan' onclick ='UsulanHapusdetail.Edit()' title='Edit Barang'>".
			//"<input type='button' value='Cetak BA' onclick ='".$this->Prefix.".cetakBA()' title='Cetak BA'>".
			$Cek.
			//"<input type='button' value='Pengecekan' onclick ='UsulanHapusbadetail.periksaEntry()' title='Entry Hasil Pengecekan'>".
			//"<input type='button' value='Simpan' onclick ='".$this->Prefix.".simpanBA()' >".
			$Simpan.
			//"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
			"$Batal";
		
		
		$form = $this->genForm2();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
		
	function genForm2($withForm=TRUE){	
		$form_name = $this->Prefix.'_form';	
		
		
		
		
		if($withForm){
			$params->tipe=1;
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,
					'',$params
					).
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height
				);
			
			
		}
		
		
		/*$form = 
			centerPage(
				$form
			);*/
		return $form;
	}	
	function simpanBA(){
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
		
		$no_ba= $_REQUEST['no_ba'];
		$tgl_ba= $_REQUEST['tgl_ba'];
		$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
		
		$get = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$pejabat_pengadaan."'"));
		$nama = $get['nama'];
		$nip = $get['nip'];
		$jabatan = $get['jabatan'];
		
		if( $err=='' && $no_ba =='' ) $err= 'No BA belum diisi!';
		if( $err=='' && $tgl_ba =='' ) $err= 'Tgl BA belum diisi!';
				
		if($fmST == 0){ //gak dipake
			//cek no ba
			if( $err=='' ){
				$get = mysql_fetch_array(mysql_query(
					"select count(*) as cnt from penghapusan_usul where no_ba='$no_ba' "
				));
				if($get['cnt']>0 ) $err='No BA Sudah Ada!';
			}
			if($err==''){
				$aqry = "insert into penghapusan_usul (no_ba,tgl_ba,tgl_update,uid,ref_idpegawai_ba)"."values('$no_ba','$tgl_ba',now(),'$uid','$pejabat_pengadaan')";	$cek .= $aqry;	
				$qry = mysql_query($aqry);
			}
			
		}else{
			$old = mysql_fetch_array(mysql_query("select * from penghapusan_usul where Id='$idplh'"));
			//cek no ba
			if( $err=='' ){
				if($no_ba!=$old['no_ba'] ){
					$get = mysql_fetch_array(mysql_query(
						"select count(*) as cnt from penghapusan_usul where no_ba='$no_ba' "
					));
					if($get['cnt']>0 ) $err='No BA Sudah Ada!';
				}
			}
			//cek tgl ba > tglusul
			if($err=='' && $old['tgl_usul'] > $tgl_ba ) $err = "Tanggal pengecekan tidak lebih kecil dari tanggal usulan! ";
			
			if($err==''){
				$aqry = "update penghapusan_usul 
				         	set no_ba  = '$no_ba',
			         		tgl_ba  = '$tgl_ba',
					 	    uid ='$uid',
					     	ref_idpegawai_ba = '$pejabat_pengadaan'".
						 "where Id='".$idplh."'";	$cek .= $aqry;
				$qry = mysql_query($aqry);
			}
		}
			
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}	
	function setformPilihPemeriksa(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		$fm = $this->setFormPLPMRS($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
   function setFormPLPMRS($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		$form_name = $this->Prefix.'_PanitiaPemeriksafm';	//nama Form			
		$this->form_width = 800;
		$this->form_height = 400;
		$this->form_caption = 'Panitia Pemeriksa'; //judul form
		$this->form_fields = array(				
			 'detailpanitia' => array( 
						'label'=>'',
						 'value'=>"<div id='div_detailpanitia'></div>", 
						 'type'=>'merge'
						 )
		);
		
		//tombol
		$this->form_menubawah =
			
			"<input type=hidden  value='' id='".$this->Prefix."_daftarid' name='".$this->Prefix."_daftarid' > ".
			"<input type=hidden  value='' id='".$this->Prefix."_daftarsesi' name='".$this->Prefix."_daftarsesi' > ".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closepanitiafm()' >";
		
		//$form = //$this->genForm();		
		$form= centerPage(
		//"<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height)
				//"</form>"
			);
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	
	
	function hitungPanitia_($idplh){
	$cek = ''; $err=''; $content='';
		$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from panitia_pemeriksa where ref_idusulan='".$idplh."' "
								
							));
				$cek.="select count(*) as cnt from panitia_pemeriksa where ref_idusulan='".$idplh."' ";
				$content= $get['cnt'];
				
							
				return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	

	}
	function hitungPanitia() {
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];/* */
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$idplh = $_REQUEST[$this->Prefix.'_idUsul'];
				//$idplhPanitia = $_REQUEST['PanitiaPemeriksa_idplh'];
				$a1 = $Main->DEF_KEPEMILIKAN;
				
				$hitung = $_REQUEST['hitung2']; //string
				//$hitungArray = "count($hitung)";
				$get = $this->hitungPanitia_ ($idplh);
				$content= $get['content'].' Orang';
				 $cek.=$get['cek'];			
				 $err.=$get['err'];			
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
}
	/*
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}*/
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
			"<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/usulanhapusbadetail.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/panitiapemeriksa.js' language='JavaScript' ></script>".		
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
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 0;
		//tabel penghapusan_usulsk field no_usulan_sk
		//$query = "SELECT* FROM penghapusan_usulsk_det WHERE ref_idusulan ='".$this->form_idplh."' ";
		//$select=mysql_fetch_array($query);
		
		//$query1 = "SELECT* FROM penghapusan_usulsk WHERE Id ='".$select['Id']."' ";
		//$read=mysql_fetch_array($query1);
		
		//if($read['no_usulan_sk'] !=''){
		//$fm['err'] ="BA ini sudah di usulkan";
		//}else{
		
			$fm = $this->setForm($dt);
		//}
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from penghapusan_usul where Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 800;
		$this->form_height = 250;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'   and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];	
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."'"));
		$seksi = $get['nm_skpd'];		
			
		
		//ambil data no usulan dan tgl Usulan
		$get = mysql_fetch_array(
			   mysql_query("select Id,
			   					   no_usulan,
			   					   tgl_usul
							from penghapusan_usul
							where no_ba ='' OR no_ba IS NULL
							")
				);
	     $Id = $get['Id'];
	     $no_usulan = $get['no_usulan'];
	     $tgl_usul = TglInd($get['tgl_usul']);
			
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'no_ba' =>array(
							'label'=>'No.BA','value'=>$dt['no_ba'],
							'labelWidth'=>120, 
							'type'=>'text' 
							),
			'tgl_ba' => array( 
						'label'=>'Tgl BA',
						 'value'=>$dt['tgl_ba'], 
						 'type'=>'date'
						 ),	
			'pejabat_pengadaan' => array(  
				'label'=>'Pilih No Usulan', 
				'value'=> 
					"<input type='text' id='Id' name='Id' value='".$Id."'> ".
					"<input type='text' id='no_usul' name='no_usul' readonly=true value='".$no_usulan."' style='width:250'> &nbsp; ".
					"NO.Usulan  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$tgl_usul."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihUsulan()\">"
				,
				'type'=>'' 
			), 	
			'jbt1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'no_usulan' => array( 
						'label'=>'No.Usulan', 'value'=>$no_usul, 
						'labelWidth'=>120, 
						'type'=>'text' 
			    ),
			'tgl_usul' => array( 
						'label'=>'Tgl Usul',
						 'value'=>$dt['tgl_usul'], 
						 'type'=>'date'
						 )	
			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
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
				
				<th class='th01' width='40' rowspan=3>No.</th>
				$Checkbox
				<th class='th02' width=150 colspan=2>Usulan</th>
				<th class='th01' rowspan=2>Bidang/SKPD/Unit/Sub Unit</th>	
				<th class='th02' width=150 colspan=2>Tanda Terima</th>
				<th class='th02' width=150 colspan=2>Berita Acara</th>
				<th class='th02' width=150 colspan=2>Usulan SK</th>
				<th class='th01' width=250 rowspan=2>Pemeriksa Barang</th>								
				</tr>
				<tr>
				<th class='th01' width=75>No.</th>
				<th class='th01' width=75>Tanggal</th>
				<th class='th01'width=100>No.</th>								
				<th class='th01'width=100>Tanggal</th>								
				<th class='th01'width=100>No.</th>								
				<th class='th01'width=100>Tanggal</th>								
				<th class='th01'width=100>No.</th>								
				<th class='th01'width=100>Tanggal</th>								
				</tr>
				
			</thead>";
		return $headerTable;
	}
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		
		//get dinas		
		$dinas = '';
		//if($isi['group']!= '00.00.00'){
		//	$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		//	$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		//	$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
			
			//$grp = $isi['group'];
			$c=''.$isi['c'];
			$d=''.$isi['d'];
			$e=''.$isi['e'];
			$e1=''.$isi['e1'];
			$ref_idpegawai_ba=''.$isi['ref_idpegawai_ba'];
			///*
			$nmopdarr=array();	
			//if($fmSKPD == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_bidang where c='".$c."' "
				));		
				if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
			//}
			//if($fmUNIT == '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
				$get = mysql_fetch_array(mysql_query(
					"select * from v_opd where c='".$c."' and d='".$d."' "
				));		
				if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
			//}
			//if($fmSUBUNIT == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"
				));		
				if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
			//}
			//if($fmSUBUNIT == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."'"
				));		
				if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];
			//}
			$nmopd = join(' - ', $nmopdarr );
			//*/
			//$nmopd = $grp.' '.$c.$d.$e;
		//}
		//get Pegawai
			     $get = mysql_fetch_array(mysql_query(
					"select * from ref_pegawai where id='".$ref_idpegawai_ba."'"
					//"select * from ref_pegawai"
				));		
				//if($get['ref_idpegawai_usul']<>'') $nmopdarr[] = $get['ref_idpegawai_usul'];
		/*13 Juni 2013*/
		//get no usulan sk,tgl_usul_sk
		$query ="SELECT Id FROM penghapusan_usulsk_det WHERE ref_idusulan = '".$isi['Id']."' ";$cek .=$query;
		$res = mysql_fetch_array(mysql_query($query));
		
		$que = "SELECT no_usulan_sk,tgl_usul_sk FROM penghapusan_usulsk WHERE Id = '".$res['Id']."' ";$cek .=$que;		 
		$rs = mysql_fetch_array(mysql_query($que));
		
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['no_usulan']);		
		$Koloms[] = array('align="center"', TglInd($isi['tgl_usul']));
		$Koloms[] = array('', $nmopd);		
		$Koloms[] = array('', $isi['no_tterima']);		
		$Koloms[] = array('align="center"', TglInd($isi['tgl_tterima']));
		$Koloms[] = array('', $isi['no_ba']);		
		$Koloms[] = array('align="center"', TglInd($isi['tgl_ba']));		
		$Koloms[] = array('', $rs['no_usulan_sk']);		
		$Koloms[] = array('align="center"', TglInd($rs['tgl_usul_sk']));		
		//$Koloms[] = array('', $nmopd);
		$Koloms[] = array('', 'NIP. '.$get['nip'].'<br/>'.$get['nama']);			
		return $Koloms;
	}

	function cariJml($Id,$kib) {
		//-- jml buku induk
		$query ="select count(*) AS jml , sum(ifnull(jml_harga,0)+ ifnull(tot_pelihara,0)+ ifnull(tot_pengaman,0) ) AS harga 								 
				 from v1_penghapusan_usul_det_bi
				 where Id='".$Id."' and f='".$kib."'";
		$rs = mysql_fetch_array(mysql_query($query));
		$hsl->jml = $rs['jml'];
		$hsl->harga = $rs['harga'];			
		return $hsl;
	}
	
	
	function gencetakBA($xls= FALSE, $Mode=''){
		global $Main;		
		global $Ref;
				
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		
		//================================================================
		//  Untuk Tabel ambil TANAH,PERALATAN DAN MESIN.DLL
		//=================================================================
		$get =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id ='".$this->form_idplh ."' "));
		 			
		
		$nmopdarr=array(); //inisialisasi
		//============================= ambil Bidang ============================================			
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang = $read['nmbidang'];
		//============================== ambil OPD =================================================================
		$opd = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($opd['nmopd']<>'') $nmopdarr[] = $opd['nmopd'];
			$opdd = $opd['nmopd'];	$cek.=$opdd;
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			$nm_unit=$getAll['nmunit'];
		$getseksi = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."'  and e1='".$get['e1']."' "));		
			if($getseksi['nm_skpd']<>'') $nmopdarr[] = $getseksi['nm_skpd'];		
			   $nmopd = join(' <br/> ', $nmopdarr );


			  $nm_subunit = $getseksi['nm_skpd'];		
		//====================================================================================================================================
		
		//==============CONVERT HARI =================================================================
		$tgl_ba =explode('-',$get["tgl_ba"]);
		for($i=0;$i<count($tgl_ba);$i++){
			$tgl_ba[0];	$tgl_ba[1];	$tgl_ba[2];
		}
		$y = $tgl_ba[0]; $m = $tgl_ba[1]; $d = $tgl_ba[2]; 
					
		$mk=mktime(0, 0, 0, $m, $d, $y);
		$dob_disp1=strftime('%Y-%m-%d-%w',$mk);
		$hari =strftime('%w',$mk);
		$month = intval($m) - 1;
		$bln =$Ref->NamaBulan[$month];
		$month =strftime('%m',$mk);
		$year =strftime('%Y',$mk);
		$tgl = $d;		
		//convert Hari english to Indonesia
		switch($hari){
			case '1':{	$hari = 'Senin';	break;	}
			case '2':{	$hari = 'Selasa';	break;	}
			case '3':{	$hari = 'Rabu'; 	break;	}
			case '4':{	$hari = 'Kamis';	break;	}
			case '5':{	$hari = 'Jum\'at';	break;	}
			case '6':{	$hari = 'Sabtu';	break;	}
			case '0':{	$hari = 'Minggu';	break;	}
		}
		//================END =====================
		
		//jml
		//$usulanjmlA $usulanhargaA $tidakadajmlA $tidakadahargaA $adajmlA $adahargaA
		$usulanjml = array();
		$usulanharga = array();
		$tidakadajml = array();
		$tidakadaharga = array();
		$adajml = array();
		$adaharga = array();
		
		for($i=0; $i<=6; $i++ ){
			$kib = "";
			if($i>0) $kib = " and f='0".$i."'";
			$aqry = "select count(*) as cnt, sum(harga) as tot  from v1_penghapusan_usul_det_bi ".
				" where  Id ='".$this->form_idplh ."' and (sesi='' or sesi is null) $kib ";//"'  and f='01' "; 
			$cek .= $aqry;
			$hit = mysql_fetch_array( mysql_query($aqry) );
			$usulanjml[$i] 	= number_format($hit['cnt'] ,0, ',','.');
			$usulanharga[$i]= number_format($hit['tot'] ,2, ',','.');
			
			$aqry = "select count(*) as cnt, sum(harga) as tot  from v1_penghapusan_usul_det_bi ".
				" where  Id ='".$this->form_idplh ."' and (sesi='' or sesi is null) and (status=2 or tindak_lanjut=1) $kib ";
			$cek .= $aqry;
			$hit = mysql_fetch_array( mysql_query($aqry) );
			$tidakadajml[$i] 	= number_format($hit['cnt'] ,0, ',','.');
			$tidakadaharga[$i]	= number_format($hit['tot'] ,2, ',','.');
			
			$aqry = "select count(*) as cnt, sum(harga) as tot  from v1_penghapusan_usul_det_bi ".
				" where  Id ='".$this->form_idplh ."' and (sesi='' or sesi is null) and (status<>2 and tindak_lanjut<>1) $kib ";
			$cek .= $aqry;
			$hit = mysql_fetch_array( mysql_query($aqry) );
			$adajml[$i] 	= number_format($hit['cnt'] ,0, ',','.');
			$adaharga[$i]	= number_format($hit['tot'] ,2, ',','.');
		}
		
		
		 
		//============= DATA PENGECEKAN ====================//
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		//$css = $this->cetak_xls	? 
		
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:21cm'>
			<table class=\"rangkacetak\" style='width:19cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//=========== CONTENT =============================================================================================
		//echo'<br><br><br><br><br><br>';
		echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:Arial,Helvetica,sans-serif;margin-left:0.2cm">
		 	 <tr>
			  <td><img src="images/administrator/images/kopbaru.jpg" width="100%"></td>
			 </tr>
			 </table>
			';
		echo'
		<table class="rangkacetak" style="width:19cm"> <tbody><tr> <td valign="top">
		<table width="100%" border="0">
		<tbody>
		<tr>
			<td colspan="">
			<div style="text-align:center;font-size:12pt;font-family:Arial;">
			<b>BERITA ACARA<br>
			HASIL PENGECEKAN USULAN PENGHAPUSAN BARANG</b><br>
			Nomor :'.' '.$get['no_ba'].'
			</div></td>
		</tr>
	    </tbody>
		</table> ';
	echo'<br><br>';
	echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" 
			style="margin-left:7px;">
		<tbody>
		<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;font-height:17pt;font-family:Arial;text-align:justify" >
				Pada hari ini, '.'<b>'.$hari.'</b>'.' tanggal'.'<b>'.bilang($tgl+0).'</b> bulan '.'<b>'.$bln.'</b> tahun'.'<b> '.bilang($year).'</b> kami yang bertanda tangan di bawah ini
				selaku Panitia Penghapusan barang-barang inventaris '.ucwords(strtolower($Main->NM_WILAYAH2)).' telah melakukan pengecekan/penelitian atas barang-barang milik '.ucwords(strtolower($Main->NM_WILAYAH2)).' yang digunakan pada :
			</td>
		</tr> 
		</tbody></table>';	
	echo '<br>';
	echo 
		'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px">
	  	<tbody>
	   	<tr valign="top">
		    <td style="font-weight:normal;font-size:11pt;height:0.7cm;width:150px;font-family:arial">SKPD</td>
			<td style="width:10;font-weight:normal;font-size:11pt;font-family:arial">:</td>
			<td style="font-weight:normal;font-size:11pt;font-family:arial">'.$opdd.'</td> 
	   	</tr>
		<tr valign="top">
		    <td style="font-weight:normal;font-size:11pt;height:0.7cm;width:150px;font-family:arial">UNIT</td>
			<td style="width:10;font-weight:normal;font-size:11pt;font-family:arial">:</td>
			<td style="font-weight:normal;font-size:11pt;font-family:arial">'.$nm_unit.'</td> 
	   	</tr>
		<tr valign="top">
		    <td style="font-weight:normal;font-size:11pt;height:0.7cm;width:150px;font-family:arial">SUB UNIT</td>
			<td style="width:10;font-weight:normal;font-size:11pt;font-family:arial">:</td>
			<td style="font-weight:normal;font-size:11pt;font-family:arial">'.$nm_subunit.'</td> 
	   	</tr>

	    
	   	</tbody>
	   	</table>'; 	
	echo '<br>';
	echo 
		'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		<tbody>
		<tr valign="top">
		<td colspan="2" style="font-weight:none;font-size:11pt;height:17pt;font-family:Arial;
			text-align:justify" >
			Hasil dari pengecekan/penelitian adalah sebagai berikut:
		</td>
		</tr> 
		</tbody></table>';
	echo'<br>';
	echo
		'<table cellpadding="2" cellspacing="0" border="1px solid" width="100%" style="margin-left:7px;">
		<tbody>
		<tr valign="top">
			    <td rowspan="2" width="5" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">No.</td>
			    <td rowspan="2" width="40" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jenis Barang</td>
			    <td colspan= "2" width="20" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Usulan Penghapusan</td>
			    <td colspan= "2" width="30" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Tidak Ada</td>
			    <td colspan= "2" width="30" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Ada</td>
			   
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">1.</td>  
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Tanah</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[1].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[1].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[1].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[1].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[1].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[1].'</td>
			    
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">2.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" width="250px">Peralatan dan Mesin</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[2].'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[2].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[2].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[2].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[2].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[2].'</td>
			   	
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">3.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Bangunan dan Gedung</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[3].'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[3].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[3].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[3].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[3].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[3].'</td>
			    
				</tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">4.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Jalan,Irigasi,dan Jaringan</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[4].'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[4].'</td>
			  	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[4].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[4].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[4].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[4].'</td>
			 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">5.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Aset tetap Lainnya</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[5].'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[5].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[5].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[5].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[5].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[5].'</td>
			   
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">6.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Konstruksi dalam pengerjaan</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[6].'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[6].'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[6].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[6].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[6].'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[6].'</td>			   
			  </tr> 
			  <tr>
			    <td colspan="2"  style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle">TOTAL</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$usulanjml[0].'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$usulanharga[0].'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$tidakadajml[0].'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$tidakadaharga[0].'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$adajml[0].'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$adaharga[0].'</td>
			    
			 </tr> 
			   ';	
		echo'</tbody></table>';
		echo'<br>';
	   echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		  <tbody>
			<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >
				Adapun hasil pengecekan/penelitian atas barang-barang tersebut ternyata semua barang-barang dimaksud adalah milik '.ucwords(strtolower($Main->NM_WILAYAH2)).' dan semua/sebagahagiannya dalam keadaan rusak berat dan sudah tidak dapat di pergunakan untuk kepentingan dinas, sedangkan manfaat penggunaanya untuk kepentingan dinas tidak seimbang dengan biaya perbaikan yang akan dikeluarkan.
			</td>
			</tr> 
			</tbody></table>';
	   echo'<br>';
	   echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;font-family:Arial">
		  <tbody>
			<tr valign="top">
			 <td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >	
			 Berhubungan dengan kondisi barang-barang tersebut, diusulkan kepada pejabat yang berwenang agar barang-barang dimaksud dapat dipertimbangkan untuk dihapus dari daftar inventaris kekayaan milik '.ucwords(strtolower($Main->NM_WILAYAH2)).' dan selanjutnya dilelang secara umum/dilelang terbatas/dihibahkan/ dan atau dimusnahkan.
				
			</td>
		    </tr></tbody></table>';
	   echo'<br>';
	  echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		  <tbody>
			<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >
			Demikian Berita Acara ini kami buat dengan sebenarnya dan disampaikan Kepala '.ucwords(strtolower($Main->NM_WILAYAH2)).' untuk dipergunakan sebagaimana mestinya.
			
			</td>
		    </tr></tbody></table>';
	 echo'<br>';
	echo' <table cellpadding="0" cellspacing="0" border="0" width="89%" style="margin-left:7px;font-family:Times New Roman,Arial,Helvetica,sans-serif;font-weight:bold">
			  <tbody>
			   <tr>
			    <td align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">Panitia Penghapusan Barang-Barang </td>
			   </tr>
			  <tr>
			    <td  align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">Inventaris Dan Barang Lainnya Milik Pemerintah</td>
			  </tr>
			 <tr>
			    <td  align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">'.ucwords(strtolower($Main->NM_WILAYAH2)).'</td>
			 </tr></tbody></table>';
	echo'<br>';
			 //get Panitia Pemeriksa
		$querypan = "SELECT* FROM panitia_pemeriksa WHERE ref_idusulan = '".$get['Id']."' ORDER BY jabatan ASC ";//echo $querypan;
		$rspan = mysql_query($querypan);
		$no = 1;
		$nottd = 1;
		
		echo' <table cellpadding="0" cellspacing="0" border="0" width="450" style="margin-left:7px;
				font-family:Arial;font-weight:normal">
			  <tbody>
			   <tr height="30">
			    <td style="width:30px;font-size:10pt;font-weight:bold;font-family:Arial;">No. </td>
			    <td style="width:100px;font-size:10pt;font-weight:bold;font-family:Arial;">Nama</td>
			    <td style="width:100px;font-size:10pt;font-weight:bold;font-family:Arial;">Jabatan</td>
			    <td style="width:200px;font-size:10pt;font-weight:bold;font-family:Arial;text-align:center">Tanda Tangan</td>
			 </tr>';
		while($row = mysql_fetch_array($rspan)){
			if(($nottd%2)==1 ){
				$ttd =' <td style="text-indent:3px;font-size:10pt;text-align:left;vertical-align:middle;font-family:Arial;">'.$nottd.'....................</td> ';
			}else{
				$ttd =' <td style="font-size:10pt;text-align:right;vertical-align:middle;font-family:Arial;">'.$nottd.'.......................</td> ';
			}
			
			echo'<tr height="30">
			    	<td width="20px" align="left" style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$no.'.</td>
					<td width="20px" style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$row['nama'].'</td>
					<td style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$this->arrjabatan[$row['jabatan']-1][1].'</td>'.
					$ttd.
			    '</tr>';
			$no++;
			$nottd++;
		}
		
		echo'</tbody></table>';
		echo '<br><br><br><br>';
		
		echo'</tbody></table>';
		echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
	    echo"</div>	".			
				//$this->PrintTTD2($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo"
			</td>	
			</tr>
			</tbody>
		  	</div>
			</form>		
			</body>	
			</html>";
	}

	
	function gencetakBA_($xls= FALSE, $Mode=''){
		global $Main;
		
		global $Ref;
				
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		
		//$nowdate = JuyTgl1(date("Y-m-d")); //ambil tanggal,bulan dan tahun sekarang 
		
		/*================================================================
		  Untuk Tabel ambil TANAH,PERALATAN DAN MESIN.DLL
		=================================================================*/
		$get =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id ='".$this->form_idplh ."' "));
		//echo 'ID usul ini ='.$get['Id'];
		
		//$select =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul_det WHERE Id ='".$get['Id'] ."' "));
		//echo '<br/>ID usul detail ini ='.$get['Id'];
		
		//$read =mysql_fetch_array(mysql_query("SELECT* FROM buku_induk WHERE id ='".$select['id_bukuinduk'] ."' "));
		//echo '<br/>ID buku INDUK ini = '.$read['id'];
		
		//$getA =mysql_fetch_array(mysql_query("SELECT* FROM kib_a WHERE f ='".$read['f'] ."' "));
		//echo "<br>SELECT* FROM kib_a WHERE f ='".$read['f'] ."' "; 			
		//echo '<br/>f ini = '.$getA['f']=$read['f'] ; 			
		
		$nmopdarr=array(); //inisialisasi
		//============================= ambil Bidang ============================================			
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang = $read['nmbidang'];
		//=======================================================================================
		
		//============================== ambil OPD =================================================================
		$opd = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $opd['nmopd'];
			$opdd = $opd['nmopd'];
			$cek.=$opdd;
		//==========================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
			  $biro = $getAll['nmunit'];		
		//====================================================================================================================================
		
		//==============CONVERT HARI =================================================================
		$tgl_ba =explode('-',$get["tgl_ba"]);
		for($i=0;$i<count($tgl_ba);$i++){
			$tgl_ba[0];
			$tgl_ba[1];
			$tgl_ba[2];
		}
			$y = $tgl_ba[0];
			$m = $tgl_ba[1]; 
			$d = $tgl_ba[2]; 
					
			$mk=mktime(0, 0, 0, $m, $d, $y);
			$dob_disp1=strftime('%Y-%m-%d-%w',$mk);
			$hari =strftime('%w',$mk);
			$month = intval($m) - 1;
			$bln =$Ref->NamaBulan[$month];
			$month =strftime('%m',$mk);
			$year =strftime('%Y',$mk);
			$tgl = $d;
		
		//convert Hari english to Indonesia
		switch($hari){
			case '1':{
				$hari = 'Senin';
				break;
			}
			case '2':{
				$hari = 'Selasa';
				break;
			}
			case '3':{
				$hari = 'Rabu';
				break;
			}
			case '4':{
				$hari = 'Kamis';
				break;
			}
			case '5':{
				$hari = 'Jum\'at';
				break;
			}
			case '6':{
				$hari = 'Sabtu';
				break;
			}
			case '0':{
				$hari = 'Minggu';
				break;
			}
		}
		//================END =====================
		
		//==============PENNGECEKAN STATUS : Ada / Tidak Ada ===============================
		// ****** kib A *********//
		$queryA ="SELECT count(*) AS jumA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND (tindak_lanjut=='1' and tindak_lanjut!=0 AND status='1')";//echo $queryA.'<br>';
		$resultA = mysql_fetch_array(mysql_query($queryA));
		$statAdaA = $resultA['jumA'];
				
		$querytdA ="SELECT count(*) AS jumtdA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdA.'<br>';
		$resulttdA = mysql_fetch_array(mysql_query($querytdA));
		$stattdA = $resulttdA['jumtdA'];
			
		//kib B
		$queryB ="SELECT count(*) AS jumB FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='1'";//echo $queryB.'<br>';
		$resultB = mysql_fetch_array(mysql_query($queryB));
		$statAdaB = $resultB['jumB'];
		
		$querytdB ="SELECT count(*) AS jumtdB FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdB.'<br>';
		$resulttdB = mysql_fetch_array(mysql_query($querytdB));
		$stattdB = $resulttdB['jumtdB'];
				
		//kib C
		$queryC ="SELECT count(*) AS jumC FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='1'";//echo $queryC.'<br>';
		$resultC = mysql_fetch_array(mysql_query($queryC));
		$statAdaC = $resultC['jumC'];
				
		$querytdC ="SELECT count(*) AS jumtdC FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdC.'<br>';
		$resulttdC = mysql_fetch_array(mysql_query($querytdC));
		$stattdC = $resulttdC['jumtdC'];
		
		//kib D 
		$queryD ="SELECT count(*) AS jumD FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='1'";//echo $queryD.'<br>';
		$resultD = mysql_fetch_array(mysql_query($queryD));
		$statAdaD = $resultD['jumD'];
		
		$querytdD ="SELECT count(*) AS jumtdD FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdD.'<br>';
		$resulttdD = mysql_fetch_array(mysql_query($querytdD));
		$stattdD = $resulttdD['jumtdD'];
				
		//kib E
		$queryE ="SELECT count(*) AS jumE FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='1'";//echo $queryE.'<br>';
		$resultE = mysql_fetch_array(mysql_query($queryE));
		$statAdaE = $resultE['jumE'];
				
		$querytdE ="SELECT count(*) AS jumtdE FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdE.'<br>';
		$resulttdE = mysql_fetch_array(mysql_query($querytdE));
		$stattdE = $resulttdE['jumtdE'];
			
		//kib F
		$queryF ="SELECT count(*) AS jumF FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='1'";//echo $queryF.'<br>';
		$resultF = mysql_fetch_array(mysql_query($queryF));
		$statAdaF = $resultF['jumF'];
		
		$querytdF ="SELECT count(*) AS jumtdF FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut!='1' and tindak_lanjut!=0 AND status='2'";//echo $querytdF.'<br>';
		$resulttdF = mysql_fetch_array(mysql_query($querytdF));
		$stattdF = $resulttdF['jumtdF'];
		//=============end STATUS =======================================================================================
		
		//==============PENGECEKAN Ditolak,dimusnahkan,dipindahtangankan==================================================
		//****** kib A ********/
		$sqltkA ="SELECT count(*) AS hittkA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND tindak_lanjut='1'";//echo $sqltkA.'<br>';
		$rstkA = mysql_fetch_array(mysql_query($sqltkA));
		$tolakA = $rstkA['hittkA'];
				
		$sqldmA ="SELECT count(*) AS hitdmA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND tindak_lanjut='2'";//echo $sqldmA.'<br>';
		$rsdmA = mysql_fetch_array(mysql_query($sqldmA));
		$dmusnA = $rsdmA['hitdmA'];
		
		$sqldpA = "SELECT count(*) AS hitdpA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND tindak_lanjut='3'";//echo $sqldpA.'<br>';
		$rsdpA = mysql_fetch_array(mysql_query($sqldpA));
		$dipinA = $rsdpA['hitdpA'];
				
		//jumlah total barang yang belum dicek ----------------------
			$jumAbc =  "SELECT count(*) AS belumcekA FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$kirimA = mysql_query($jumAbc);
			while($row =mysql_fetch_array($kirimA)) {
				$jumbelumcekkiba = $row['belumcekA'];
			}
				if($jumbelumcekkiba==NULL || $jumbelumcekkiba=='' ){
					$jumbelumcekkiba='0';
				}
				
		//jumlah harga barang belum dicek
			$jumAharga =  "SELECT SUM(harga) AS hargabelumcekkiba FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND (tindak_lanjut IS NULL OR tindak_lanjut='')";
			$harA = mysql_query($jumAharga);
			while($row =mysql_fetch_array($harA)) {
				$jumhargabelumcekA = $row['hargabelumcekkiba'];
				$jumhargabelumcekA1 = $row['hargabelumcekkiba'];
			}
		
		    $jumhargabelumcekA =$jumhargabelumcekA==0?'0': number_format($jumhargabelumcekA,2,',','.');
			$jumhargabelumcekA1 =$jumhargabelumcekA1==0?'0':$jumhargabelumcekA1;
		
		
		//jumlah total Barang yang sudah dicek
			$jumtotA = $tolakA + $dmusnA + $dipinA;
		
		//Jumlah harga barang yang sudah dicek 
			$jumAhargacek =  "SELECT SUM(harga) AS hargasudahcekkiba FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='01' AND tindak_lanjut!='' ";
			$harAcek = mysql_query($jumAhargacek);
			while($row =mysql_fetch_array($harAcek)) {
				$jumhargasudahcekA = $row['hargasudahcekkiba'];
				$jumhargasudahcekA1 = $row['hargasudahcekkiba'];
			}
			$jumhargasudahcekA =$jumhargasudahcekA==0?'0': number_format($jumhargasudahcekA,2,',','.');
			$jumhargasudahcekA1 =$jumhargasudahcekA1==0?'0': $jumhargasudahcekA1;
			
		//****** kib B ********/
		$sqltkB ="SELECT count(*) AS hittkB FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut='1'";//echo $sqltkB.'<br>';
		$rstkB = mysql_fetch_array(mysql_query($sqltkB));
		$tolakB = $rstkB['hittkB'];
				
		$sqldmB ="SELECT count(*) AS hitdmB FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut='2'";//echo $sqldmB.'<br>';
		$rsdmB = mysql_fetch_array(mysql_query($sqldmB));
		$dmusnB = $rsdmB['hitdmB'];
		
		$sqldpB = "SELECT count(*) AS hitdpB FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut='3'";//echo $sqldpB.'<br>';
		$rsdpB = mysql_fetch_array(mysql_query($sqldpB));
		$dipinB = $rsdpB['hitdpB'];
				
		//jumlahtotal barang belum cek KIB B
			$jumBbc =  "SELECT count(*) AS belumcekkibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$ree = mysql_query($jumBbc);
			while($row =mysql_fetch_array($ree)) {
				$jumbelumcekkibb = $row['belumcekkibb'];
			}
			
			if($jumbelumcekkibb==NULL || $jumbelumcekkibb=='' ){
				$jumbelumcekkibb='0';
			}
			
		//jumlah harga belum cek
			$jumBharga =  "SELECT SUM(harga) AS hargabelumcekkibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			//echo $jumBharga;
			$harB = mysql_query($jumBharga);
			while($row =mysql_fetch_array($harB)) {
				$jumhargabelumcekB = $row['hargabelumcekkibb'];
				$jumhargabelumcekB1 = $row['hargabelumcekkibb'];
			}		
			$jumhargabelumcekB =$jumhargabelumcekB==0?'0':number_format($jumhargabelumcekB,2,',','.');
			$jumhargabelumcekB1 =$jumhargabelumcekB1==0?'0':$jumhargabelumcekB1;
			//:number_format($a[0]->jml,0,',','.');
		
		//jumlah total barang yang sudah dicek KIB B
			$jumtotB = $tolakB + $dmusnB + $dipinB;
		
		//Jumlah harga sudah di cek 
			$jumBhargacek =  "SELECT SUM(harga) AS hargasudahcekkibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='02' AND tindak_lanjut!='' ";
			$harBcek = mysql_query($jumBhargacek);
			while($row =mysql_fetch_array($harBcek)) {
				$jumhargasudahcekB = $row['hargasudahcekkibb'];
				$jumhargasudahcekB1 = $row['hargasudahcekkibb'];
			}
			$jumhargasudahcekB =$jumhargasudahcekB==0?'0':number_format($jumhargasudahcekB,2,',','.');
			$jumhargasudahcekB1 =$jumhargasudahcekB1==0?'0':$jumhargasudahcekB1;
			
			
							
		//***************** kib C *****************/
		$sqltkC ="SELECT count(*) AS hittkC FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut='1'";//echo $sqltkC.'<br>';
		$rstkC = mysql_fetch_array(mysql_query($sqltkC));
		$tolakC = $rstkC['hittkC'];
				
		$sqldmC ="SELECT count(*) AS hitdmC FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut='2'";//echo $sqldmC.'<br>';
		$rsdmC = mysql_fetch_array(mysql_query($sqldmC));
		$dmusnC = $rsdmC['hitdmC'];
		
		$sqldpC = "SELECT count(*) AS hitdpC FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut='3'";//echo $sqldpC.'<br>';
		$rsdpC = mysql_fetch_array(mysql_query($sqldpC));
		$dipinC = $rsdpC['hitdpC'];
		
				
		//jumlah barang belum cek KIB C
			$jumCbc =  "SELECT count(*) AS belumcekkibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$mayadesiC = mysql_query($jumCbc);
			while($row =mysql_fetch_array($mayadesiC)) {
				$jumbelumcekkibc = $row['belumcekkibc'];
			}
			
			if($jumbelumcekkibc==NULL || $jumbelumcekkibc=='' ){
					$jumbelumcekkibc='0';
			}
		
			
		//jumlah harga barang belum cek
			$jumCharga =  "SELECT SUM(harga) AS hargabelumcekkibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$harC = mysql_query($jumCharga);
			while($row =mysql_fetch_array($harC)) {
				$jumhargabelumcekC = $row['hargabelumcekkibc'];
				$jumhargabelumcekC1 = $row['hargabelumcekkibc'];
			}
			$jumhargabelumcekC =$jumhargabelumcekC==0?'0': number_format($jumhargabelumcekC,2,',','.');
			$jumhargabelumcekC1 =$jumhargabelumcekC1==0?'0': $jumhargabelumcekC1;
		
		//jumlah total sudah dicek
		$jumtotC = $tolakC + $dmusnC + $dipinC;
		
		//Jumlah harga sudah di cek 
			$jumChargacek =  "SELECT SUM(harga) AS hargasudahcekkibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='03' AND tindak_lanjut!='' ";
			$harCcek = mysql_query($jumChargacek);
			while($row =mysql_fetch_array($harCcek)) {
				$jumhargasudahcekC = $row['hargasudahcekkibc'];
				$jumhargasudahcekC1 = $row['hargasudahcekkibc'];
			}
			$jumhargasudahcekC =$jumhargasudahcekC==0?'0': number_format($jumhargasudahcekC,2,',','.');
			$jumhargasudahcekC1 =$jumhargasudahcekC1==0?'0': $jumhargasudahcekC1;
			
		//************ kib D ******/ 
		$sqltkD ="SELECT count(*) AS hittkD FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut='1'";//echo $sqltkC.'<br>';
		$rstkD = mysql_fetch_array(mysql_query($sqltkD));
		$tolakD = $rstkD['hittkD'];
				
		$sqldmD ="SELECT count(*) AS hitdmD FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut='2'";//echo $sqldmD.'<br>';
		$rsdmD = mysql_fetch_array(mysql_query($sqldmD));
		$dmusnD = $rsdmD['hitdmD'];
		
		$sqldpD = "SELECT count(*) AS hitdpD FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut='3'";//echo $sqldpC.'<br>';
		$rsdpD = mysql_fetch_array(mysql_query($sqldpD));
		$dipinD = $rsdpD['hitdpD'];
						
		//jumlah belum cek KIB D
		$jumDbc =  "SELECT count(*) AS belumcekkibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
		$mayadesiD = mysql_query($jumDbc);
		while($row =mysql_fetch_array($mayadesiD)) {
			$jumbelumcekkibd = $row['belumcekkibd'];
		}
		
		if($jumbelumcekkibd==NULL || $jumbelumcekkibd=='' ){
				$jumbelumcekkibd='0';
		}
		
				
		//jumlah harga belum cek
		$jumDharga =  "SELECT SUM(harga) AS hargabelumcekkibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND (tindak_lanjut IS NULL OR tindak_lanjut='')";
		$harD = mysql_query($jumDharga);
		while($row =mysql_fetch_array($harD)) {
			$jumhargabelumcekD = $row['hargabelumcekkibd'];
			$jumhargabelumcekD1 = $row['hargabelumcekkibd'];
		}
		$jumhargabelumcekD =$jumhargabelumcekD==0?'0': number_format($jumhargabelumcekD,2,',','.');
		$jumhargabelumcekD1 =$jumhargabelumcekD1==0?'0': $jumhargabelumcekD1;
		
		//jumlah total barang sudah dicek
		$jumtotD = $tolakD + $dmusnD + $dipinD;
		
		//Jumlah harga sudah di cek 
		$jumDhargacek =  "SELECT SUM(harga) AS hargasudahcekkibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='04' AND tindak_lanjut!='' ";
		$harDcek = mysql_query($jumDhargacek);
		while($row =mysql_fetch_array($harDcek)) {
			$jumhargasudahcekD = $row['hargasudahcekkibd'];
			$jumhargasudahcekD1 = $row['hargasudahcekkibd'];
		}
		$jumhargasudahcekD =$jumhargasudahcekD==0?'0': number_format($jumhargasudahcekD,2,',','.');
		$jumhargasudahcekD1 =$jumhargasudahcekD1==0?'0': $jumhargasudahcekD1;
				
		//*************** kib E *************/
		$sqltkE ="SELECT count(*) AS hittkE FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut='1'";//echo $sqltkE.'<br>';
		$rstkE = mysql_fetch_array(mysql_query($sqltkE));
		$tolakE = $rstkE['hittkE'];
				
		$sqldmE ="SELECT count(*) AS hitdmE FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut='2'";//echo $sqldmD.'<br>';
		$rsdmE = mysql_fetch_array(mysql_query($sqldmE));
		$dmusnE = $rsdmE['hitdmE'];
		
		$sqldpE = "SELECT count(*) AS hitdpE FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut='3'";//echo $sqldpC.'<br>';
		$rsdpE = mysql_fetch_array(mysql_query($sqldpE));
		$dipinE = $rsdpE['hitdpE'];
					
		//jumlah  barang belum  cek KIB E
			$jumEbc =  "SELECT count(*) AS belumcekkibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$mayadesiE = mysql_query($jumEbc);
			while($row =mysql_fetch_array($mayadesiE)) {
				$jumbelumcekkibe = $row['belumcekkibe'];
			}
			
			if($jumbelumcekkibe==NULL || $jumbelumcekkibe=='' ){
					$jumbelumcekkibe='0';
			}
		
		//jumlah harga belum cek
			$jumEharga =  "SELECT SUM(harga) AS hargabelumcekkibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
			$harE = mysql_query($jumEharga);
			while($row =mysql_fetch_array($harE)) {
				$jumhargabelumcekE = $row['hargabelumcekkibe'];
				$jumhargabelumcekE1 = $row['hargabelumcekkibe'];
			}
			$jumhargabelumcekE =$jumhargabelumcekE==0?'0': number_format($jumhargabelumcekE,2,',','.');
			$jumhargabelumcekE1 =$jumhargabelumcekE1==0?'0':$jumhargabelumcekE1;
		
		
		//jumlah total sudah dicek
			$jumtotE = $tolakE + $dmusnE + $dipinE;
		
		//Jumlah harga sudah di cek 
		$jumEhargacek =  "SELECT SUM(harga) AS hargasudahcekkibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='05' AND tindak_lanjut!='' ";
		$harEcek = mysql_query($jumEhargacek);
		while($row =mysql_fetch_array($harEcek)) {
			$jumhargasudahcekE = $row['hargasudahcekkibe'];
			$jumhargasudahcekE1 = $row['hargasudahcekkibe'];
		}
		$jumhargasudahcekE =$jumhargasudahcekE==0?'0':number_format($jumhargasudahcekE,2,',','.');
		$jumhargasudahcekE1 =$jumhargasudahcekE1==0?'0':$jumhargasudahcekE1;
		
		
			
		//kib F
		$sqltkF ="SELECT count(*) AS hittkF FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut='1'";//echo $sqltkC.'<br>';
		$rstkF = mysql_fetch_array(mysql_query($sqltkF));
		$tolakF = $rstkF['hittkF'];
				
		$sqldmF ="SELECT count(*) AS hitdmF FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut='2'";//echo $sqldmD.'<br>';
		$rsdmF = mysql_fetch_array(mysql_query($sqldmF));
		$dmusnF = $rsdmF['hitdmF'];
		
		$sqldpF = "SELECT count(*) AS hitdpF FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut='3'";//echo $sqldpC.'<br>';
		$rsdpF = mysql_fetch_array(mysql_query($sqldpF));
		$dipinF = $rsdpF['hitdpF'];
				
		//jumlah barang belum cek KIB F
		$jumFbc =  "SELECT count(*) AS belumcekkibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
		$mayadesiF = mysql_query($jumFbc);
		while($row =mysql_fetch_array($mayadesiF)) {
			$jumbelumcekkibf = $row['belumcekkibf'];
		}
		
		if($jumbelumcekkibf==NULL || $jumbelumcekkibf=='' ){
				$jumbelumcekkibf='0';
		}
		
		//jumlah harga belum cek
		$jumFharga =  "SELECT SUM(harga) AS hargabelumcekkibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND (tindak_lanjut IS NULL OR tindak_lanjut='') ";
		$harF = mysql_query($jumFharga);
		while($row =mysql_fetch_array($harF)) {
			$jumhargabelumcekF = $row['hargabelumcekkibf'];
			$jumhargabelumcekF1 = $row['hargabelumcekkibf'];
		}
		$jumhargabelumcekF =$jumhargabelumcekF==0?'0': number_format($jumhargabelumcekF,2,',','.');
		$jumhargabelumcekF1 =$jumhargabelumcekF1==0?'0': $jumhargabelumcekF1;
		
		
		//jumlah total sudah dicek
		$jumtotF = $tolakF + $dmusnF + $dipinF;
				
		//Jumlah harga sudah di cek 
		$jumFhargacek =  "SELECT SUM(harga) AS hargasudahcekkibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$get['Id'] ."' AND f='06' AND tindak_lanjut!='' ";
		$harfcek = mysql_query($jumFhargacek);
		while($row =mysql_fetch_array($harfcek)) {
			$jumhargasudahcekF = $row['hargasudahcekkibf'];
			$jumhargasudahcekF1 = $row['hargasudahcekkibf'];
		}
		$jumhargasudahcekF =$jumhargasudahcekF==0?'0': number_format($jumhargasudahcekF,2,',','.');
		$jumhargasudahcekF1 =$jumhargasudahcekF1==0?'0': $jumhargasudahcekF1;
		
		
		//==============END==================================================
		
		//===============TOTAL STATUS Dan PENGECEKAN kib A -F =========================
		//ada
		$totAda = $statAdaA + $statAdaB + $statAdaC + $statAdaD + $statAdaE + $statAdaF;//echo $totAda.'<br>';
		//Tidak Ada
		$tottdAda = $stattdA + $stattdB + $stattdC + $stattdD + $stattdE + $stattdF;//echo $tottdAda;
		//ditolak
		$totditolak = $tolakA + $tolakB + $tolakC + $tolakD + $tolakE + $tolakF;//echo $totditolak;
		//dimusnahkan
		$totdimusnahkan = $dmusnA + $dmusnB + $dmusnC + $dmusnD + $dmusnE + $dmusnF;//echo $totdimusnahkan;
		//dipindahtangankan
		$totdipindahtangankan = $dipinA + $dipinB + $dipinC + $dipinD + $dipinE + $dipinF;//echo $totdipindahtangankan;
		//===============END TOTAL ====================================================
		
		//======= cari jml barang & harga KIB ================
		$a=array();
		for($i=1;$i<=6;$i++){
			$a[] = $this->cariJml($get['Id'],'0'.$i);
		}
		//===================================================
		
	    //============== cari Total  Jumlah KIB A -Kib F =====
		$totjml=0;
		for($i=0;$i<=6;$i++){
			$totjml=$totjml+$a[$i]->jml;
		}		
		$vtotjml = $totjml==0?'':$totjml;
		//echo'<br> TOTAL ini = '.$vtotjml;
		//=======================================================
		
		//=================== total Harga KIB A - KIB F =========
		$tothrg =0;
		for($i=0;$i<6;$i++)
		{
			$tothrg=$tothrg+$a[$i]->harga;
		}
		$vtothrg=$tothrg==0?'':number_format($tothrg,2,',','.');
		//echo $vtothrg; 
		//=======================================================
		
		//jika jumlah 0 ditampilkan kosong
		$vjmlA = $a[0]->jml==0?'0':number_format($a[0]->jml,0,',','.');
		$vjmlB = $a[1]->jml==0?'0':number_format($a[1]->jml,0,',','.');
		$vjmlC = $a[2]->jml==0?'0':number_format($a[2]->jml,0,',','.');
		$vjmlD = $a[3]->jml==0?'0':number_format($a[3]->jml,0,',','.');
		$vjmlE = $a[4]->jml==0?'0':number_format($a[4]->jml,0,',','.');
		$vjmlF = $a[5]->jml==0?'0':number_format($a[5]->jml,0,',','.');
		
		//jika Harga 0 ditampilkan kosong		
		$vhargaA =$a[0]->harga==0?'0': number_format($a[0]->harga,2,',','.');
		$vhargaB =$a[1]->harga==0?'0': number_format($a[1]->harga,2,',','.');
		$vhargaC =$a[2]->harga==0?'0': number_format($a[2]->harga,2,',','.');
		$vhargaD =$a[3]->harga==0?'0': number_format($a[3]->harga,2,',','.');
		$vhargaE =$a[4]->harga==0?'0': number_format($a[4]->harga,2,',','.');
		$vhargaF =$a[5]->harga==0?'0': number_format($a[5]->harga,2,',','.');
		
		//total barang yang belum dicek 
		$totalbarangyangbelumdicek = 
		$jumbelumcekkiba + $jumbelumcekkibb + $jumbelumcekkibc + $jumbelumcekkibd + $jumbelumcekkibe + $jumbelumcekkibf;
		
		//total barang yang sudah dicek 
		$totalbarangyangsudahdicek = 
		$jumtotA + $jumtotB + $jumtotC + $jumtotD + $jumtotE + $jumtotF;
		
		//total harga barang yang sudah dicek
		$totalhargabarangyangsudahdicek = 
			$jumhargasudahcekA1 + $jumhargasudahcekB1 + $jumhargasudahcekC1 + $jumhargasudahcekD1 + $jumhargasudahcekE1 + $jumhargasudahcekF1;
		
		$totalhargabarangyangsudahdicek=$totalhargabarangyangsudahdicek==''?'0':number_format($totalhargabarangyangsudahdicek,2,',','.');
		
		//total harga barang yang belum di cek
		$totalhargabarangyangbelumdicek	=
		 $jumhargabelumcekA1	+ $jumhargabelumcekB1	+ $jumhargabelumcekC1	+ $jumhargabelumcekD1	+ $jumhargabelumcekE1	+ $jumhargabelumcekF1;
		
		$totalhargabarangyangbelumdicek = $totalhargabarangyangbelumdicek==''?'0':number_format($totalhargabarangyangbelumdicek,2,',','.');
		//============= DATA PENGECEKAN ====================//
		 
		//============= DATA PENGECEKAN ====================//
		
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:21cm'>
			<table class=\"rangkacetak\" style='width:19cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//=========== CONTENT =============================================================================================
		//echo'<br><br><br><br><br><br>';
		echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:Arial,Helvetica,sans-serif;margin-left:0.2cm">
		 	 <tr>
			  <td><img src="images/administrator/images/kopbaru.jpg" width="100%"></td>
			 </tr>
			 </table>
			';
		echo'
		<table class="rangkacetak" style="width:19cm"> <tbody><tr> <td valign="top">
		<table width="100%" border="0">
		<tbody>
		<tr>
			<td colspan="">
			<div style="text-align:center;font-size:12pt;font-family:Arial;">
			<b>BERITA ACARA<br>
			HASIL PENGECEKAN USULAN PENGHAPUSAN BARANG</b><br>
			Nomor :'.' '.$get['no_ba'].'
			</div></td>
		</tr>
	    </tbody>
		</table>
		';
	echo'<br><br>';
	echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" 
			style="margin-left:7px;">
		<tbody>
		<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;font-height:17pt;font-family:Arial;text-align:justify" >
				Pada hari ini, '.'<b>'.$hari.'</b>'.' tanggal'.'<b>'.bilang($tgl+0).'</b> bulan '.'<b>'.$bln.'</b> tahun'.'<b> '.bilang($year).'</b> kami yang bertanda tangan di bawah ini
				selaku Panitia Penghapusan barang-barang inventaris Pemerintah Provinsi Jawa Barat telah melakukan pengecekan/penelitian atas barang-barang milik Provinsi Jawa Barat yang digunakan pada :
			</td>
		</tr> 
		</tbody></table>';	
	echo '<br>';
	echo 
		'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px">
	  	<tbody>
	   	<tr valign="top">
		    <td style="font-weight:normal;font-size:11pt;height:0.7cm;width:150px;font-family:arial">Asisten/OPD</td>
			<td style="width:10;font-weight:normal;font-size:11pt;font-family:arial">:</td>
			<td style="font-weight:normal;font-size:11pt;font-family:arial">'.$opdd.'</td> 
	   	</tr>
		<tr valign="top">
		    <td style="font-weight:normal;font-size:11pt;height:0.7cm;width:150px;font-family:arial">Biro/UPTD/Balai</td>
			<td style="width:10;font-weight:normal;font-size:11pt;font-family:arial">:</td>
			<td style="font-weight:normal;font-size:11pt;font-family:arial">'.$biro.'</td> 
	   	</tr>
	    
	   	</tbody>
	   	</table>'; 	
	echo '<br>';
	echo 
		'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		<tbody>
		<tr valign="top">
		<td colspan="2" style="font-weight:none;font-size:11pt;height:17pt;font-family:Arial;
			text-align:justify" >
			Hasil dari pengecekan/penelitian adalah sebagai berikut:
		</td>
		</tr> 
		</tbody></table>';
	echo'<br>';
	echo
		'<table cellpadding="2" cellspacing="0" border="1px solid" width="100%" style="margin-left:7px;">
		<tbody>
		<tr valign="top">
			    <td rowspan="2" width="5" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">No.</td>
			    <td rowspan="2" width="40" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jenis Barang</td>
			    <td colspan= "2" width="20" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Usulan Penghapusan</td>
			    <td colspan= "2" width="30" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Tidak Ada</td>
			    <td colspan= "2" width="30" style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Ada</td>
			   
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Jml</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">Harga (Rp)</td>
			    
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">1.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Tanah</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlA.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaA.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkiba.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekA.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotA.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekA.'</td>
			    
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">2.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" width="250px">Peralatan dan Mesin</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlB.'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaB.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkibb.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekB.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotB.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekB.'</td>
			   	
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">3.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Bangunan dan Gedung</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlC.'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaC.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkibc.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekC.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotC.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekC.'</td>
			    
				</tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">4.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Jalan,Irigasi,dan Jaringan</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlD.'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaD.'</td>
			  	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkibd.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekD.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotD.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekD.'</td>
			 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">5.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Aset tetap Lainnya</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlE.'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaE.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkibe.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekE.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotE.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekE.'</td>
			   
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">6.</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle">Konstruksi dalam pengerjaan</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vjmlF.'</td>
				<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vhargaF.'</td>
			   	<td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumbelumcekkibf.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargabelumcekF.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$jumtotF.'</td>
			    <td style="font-weight:normal;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$jumhargasudahcekF.'</td>			   
			  </tr> 
			  <tr>
			    <td colspan="2"  style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle">TOTAL</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$vtotjml.'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$vtothrg.'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$totalbarangyangbelumdicek.'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$totalhargabarangyangbelumdicek.'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="center">'.$totalbarangyangsudahdicek.'</td>
			    <td style="font-weight:bold;font-size:10pt;font-family:Arial;vertical-align:middle" align="right">'.$totalhargabarangyangsudahdicek.'</td>
			    
			 </tr> 
			   ';	
		echo'</tbody></table>
			';
		echo'<br>';
	   echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		  <tbody>
			<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >
				Adapun hasil pengecekan/penelitian atas barang-barang tersebut ternyata semua barang-barang dimaksud adalah milik Pemerintah Provinsi Jawa Barat dan semua/sebagahagiannya dalam keadaan rusak berat dan sudah tidak dapat di pergunakan untuk kepentingan dinas, sedangkan manfaat penggunaanya untuk kepentingan dinas tidak seimbang dengan biaya perbaikan yang akan dikeluarkan.
			</td>
			</tr> 
			</tbody></table>';
	   echo'<br>';
	   echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;font-family:Arial">
		  <tbody>
			<tr valign="top">
			 <td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >	
			 Berhubungan dengan kondisi barang-barang tersebut, diusulkan kepada pejabat yang berwenang agar barang-barang dimaksud dapat dipertimbangkan untuk dihapus dari daftar inventaris kekayaan milik Pemerintah Provinsi Jawa Barat Dan selanjutnya dilelang secara umum/dilelang terbatas/dihibahkan/ dan atau dimusnahkan.
				
			</td>
		    </tr></tbody></table>';
	   echo'<br>';
	  echo'<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px;">
		  <tbody>
			<tr valign="top">
			<td colspan="2" style="font-weight:normal;font-size:11pt;height:17pt;font-family:Arial;text-align:justify" >
			Demikian Berita Acara ini kami buat dengan sebenarnya dan disampaikan kepada Gubernur Jawa Barat untuk dipergunakan sebagaimana mestinya.
			
			</td>
		    </tr></tbody></table>';
	 echo'<br>';
	echo' <table cellpadding="0" cellspacing="0" border="0" width="89%" style="margin-left:7px;font-family:Times New Roman,Arial,Helvetica,sans-serif;font-weight:bold">
			  <tbody>
			   <tr>
			    <td align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">Panitia Penghapusan Barang-Barang </td>
			   </tr>
			  <tr>
			    <td  align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">Inventaris Dan Barang Lainnya Milik Pemerintah</td>
			  </tr>
			 <tr>
			    <td  align="left" colspan="3" style="font-size:11pt;font-weight:bold;font-family:Arial">Provinsi Jawa Barat</td>
			 </tr></tbody></table>';
	echo'<br>';
			 //get Panitia Pemeriksa
		$querypan = "SELECT* FROM panitia_pemeriksa WHERE ref_idusulan = '".$get['Id']."' ORDER BY jabatan ASC ";//echo $querypan;
		$rspan = mysql_query($querypan);
		$no = 1;
		$nottd = 1;
		
		echo' <table cellpadding="0" cellspacing="0" border="0" width="450" style="margin-left:7px;
				font-family:Arial;font-weight:normal">
			  <tbody>
			   <tr height="30">
			    <td style="width:30px;font-size:10pt;font-weight:bold;font-family:Arial;">No. </td>
			    <td style="width:100px;font-size:10pt;font-weight:bold;font-family:Arial;">Nama</td>
			    <td style="width:100px;font-size:10pt;font-weight:bold;font-family:Arial;">Jabatan</td>
			    <td style="width:200px;font-size:10pt;font-weight:bold;font-family:Arial;text-align:center">Tanda Tangan</td>
			 </tr>';
		while($row = mysql_fetch_array($rspan)){
			if(($nottd%2)==1 ){
				$ttd =' <td style="text-indent:3px;font-size:10pt;text-align:left;vertical-align:middle;font-family:Arial;">'.$nottd.'....................</td> ';
			}else{
				$ttd =' <td style="font-size:10pt;text-align:right;vertical-align:middle;font-family:Arial;">'.$nottd.'.......................</td> ';
			}
			
			echo'<tr height="30">
			    	<td width="20px" align="left" style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$no.'.</td>
					<td width="20px" style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$row['nama'].'</td>
					<td style="text-indent:3px;font-size:10pt;font-family:Arial;">'.$this->arrjabatan[$row['jabatan']-1][1].'</td>'.
					$ttd.
			    '</tr>';
			$no++;
			$nottd++;
		}
		
		echo'</tbody></table>';
		echo '<br><br><br><br>';
		
		echo'</tbody></table>';
		echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
	    echo"</div>	".			
				//$this->PrintTTD2($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo"
			</td>	
			</tr>
			</tbody>
		  	</div>
			</form>		
			</body>	
			</html>";
	}


	function gencetakKKerja($xls= FALSE, $Mode=''){
		global $Main;
		
		global $Ref;
				
		$cek ='';
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		
		/*================================================================
		  Untuk Data Bidang, OPD,Biro,No usulan,Tgl Usulan
		*/
		$get =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id ='".$this->form_idplh ."' "));
		
		$select =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul_det WHERE Id ='".$get['Id'] ."' "));
						
		$read =mysql_fetch_array(mysql_query("SELECT* FROM buku_induk WHERE id ='".$select['id_bukuinduk'] ."' "));
		
		$getA =mysql_fetch_array(mysql_query("SELECT* FROM kib_a WHERE f ='".$read['f'] ."' "));
				
		$nmopdarr=array();
		//============================= ambil Bidang ============================================			
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
		//=======================================================================================
		
		//============================== ambil OPD =================================================================
		$opd = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $opd['nmopd'];
		//==========================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
		//====================================================================================================================================
		//================== ambil Biro /UPTD / B ============================================================================================
		$gseksi = mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "." and e1='".$get['e1']."' "));		
			if($gseksi['nm_skpd']<>'') $nmopdarr[] = $gseksi['nm_skpd'];		
		//====================================================================================================================================


			   $nmopd = join(' <br/> ', $nmopdarr );

		
		//==================ambil Panitia Pemeriksa=======================================
		$panitia = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cnt 
												  FROM panitia_pemeriksa 
												  WHERE ref_idusulan ='".$get['Id']."' "));
		//================================================================================
		
		$nousulan = $get["no_usulan"];
		if($nousulan==''){
			$nous = '................';
		}else{
			$nous=$nousulan;
		}
		
		$tgg = TglInd($get["tgl_usul"]);
		if($tgg==''){
			$tanggl = '................';
		}else{
			$tanggl=$tgg;
		}
			
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:21cm'>
			<table class=\"rangkacetak\" style='width:32cm'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				//$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		//=========== CONTENT =============================================================================================
		//echo'<br><br><br><br><br><br><br><br><br><br><br>';
		echo'
		<table class="rangkacetak" style="width:32cm"> <tbody><tr> <td valign="top">
		<table width="100%" border="0">
		<tbody>
		<tr>
			<td class="judulcetak" colspan=""><div align="CENTER">KERTAS KERJA PENGECEKAN USULAN PENGHAPUSAN BARANG</div></td>
		</tr>
	    </tbody>
		</table>
		';
		echo'<table style="width:100%" border="0">
			<tbody>
			<tr>
				<td align="center" style="font-size:12pt;font-weight:none;font-family:Arial,Helvetica,sans-serif">No.Usulan : <b>'.$nous.'</b> Tanggal : <b> '.$tanggl.'</b></td>
			</tr>
			</tbody></table>
			';
		echo'<br>';
		echo'
			 <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-left:7px">
			  <tbody>
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm;width:150px">BIDANG</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.$read['nmbidang'].'</td> 
			   </tr> 
			    <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm">SKPD</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.$opd['nmopd'].' </td>
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm">UNIT</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.$getAll['nmunit'].' </td> 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm">SUB UNIT</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.$gseksi['nm_skpd'].' </td> 
			   </tr> 
			   <!--<tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm" width="150" >No. Usulan</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.$get["no_usulan"].'</td> 
			   </tr> 
			   <tr valign="top">
			    <td style="font-weight:bold;font-size:10pt;height:0.7cm">Tgl. Usulan</td>
				<td style="width:10;font-weight:bold;font-size:10pt">:</td>
				<td style="font-weight:bold;font-size:10pt">'.TglInd($get["tgl_usul"]).'</td>
			   </tr>--> 
			   </tbody></table>					
			';
		echo '<br>';
		echo'
			<table class="cetak" style="width:100%" border="1">
		    <thead>
			 <tr>
					<th class="th02" colspan="2">Nomor</th>
					<th class="th02" colspan="3">Spesifikasi Barang</th>
					<th class="th01" rowspan="2">Tahun <br/>Perolehan</th>
					<th class="th01" rowspan="2">Kondisi<br/> Barang</th>
					<th class="th01" rowspan="2">Harga<br/> Perolehan</th>
					<th class="th02" colspan="4">Pengecekan</th>
					<th class="th01" rowspan="2">Keterangan</th>
			</tr>
			<tr>
					<th class="th01">No.</th>				
					<th class="th01">Kode Barang</th>
					<th class="th01" width="100">Nama / Jenis Barang</th>
					<th class="th01" width="100">Merk / Tipe&nbsp;</th>
					<th class="th01">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
					<th class="th01">Tanggal</th>				
					<th class="th01">Ada / Tidak</th>
					<th class="th01">Tindak Lanjut</th>
					<th class="th01">Kondisi Barang</th>
			</tr>
			';
					/*================================================================
					  Untuk Tabel ambil TANAH,PERALATAN DAN MESIN.DLL
					=================================================================*/
					$get =mysql_fetch_array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id ='".$this->form_idplh ."' "));
					
					$query ="SELECT* FROM penghapusan_usul_det WHERE Id ='".$get['Id'] ."' "; //table penghapusan_usul_det
					$result =mysql_query($query);	
					$no =1;
					//============================== untuk Detail Barang =========================	
					while($row = mysql_fetch_array($result)){ //start penghapusan_usul_det
					$ss = $row['id_bukuinduk'];
					   $query1 = "SELECT* FROM buku_induk WHERE id ='".$ss ."' "; //table buku_induk
					   $result1 = mysql_query($query1);
					   while($bi = mysql_fetch_array($result1)){ //start buku_induk
					   $kondisi = $bi['kondisi']; //untuk keperluan $main->kondisi
					   	//table ref_Barang
					   	 $query2 = "SELECT* FROM ref_barang 
					   	    		WHERE f = '".$bi['f']."'
								  	AND   g = '".$bi['g']."'
									AND   h = '".$bi['h']."'
									AND   i = '".$bi['i']."'
									AND   j = '".$bi['j']."'
								    ";
						 $result2 = mysql_fetch_array(mysql_query($query2)); 
						 	//ambil f buku induk untuk keperluan merk,sertifikat dll
							$f = $bi['f'];
							switch ($f){ //start switch
								case '01':{
									#ambil kib a berdasarkan f di buku induk jika f=01
									$getkiba = mysql_fetch_array(
											   mysql_query("SELECT sertifikat_no 
												   			FROM kib_a
													        WHERE a1 = '".$bi['a1']."' 
													        AND a = '".$bi['a']."' 
													        AND b = '".$bi['b']."' 
													        AND c = '".$bi['c']."' 
													        AND d = '".$bi['d']."' 
													        AND e = '".$bi['e']."'
															AND e1 = '".$bi['e1']."'  
															AND f = '".$bi['f']."'
													  		AND g = '".$bi['g']."'
															AND h = '".$bi['h']."'
															AND i = '".$bi['i']."'
															AND j = '".$bi['j']."' 
												  "));
									 $merk='-';
		 							 $spesifikasi = $getkiba['sertifikat_no'] !=''?$getkiba['sertifikat_no'].'/ /':'';
									 break;
									}
								case '02':{
									 #ambil kib b berdasarkan f di buku induk jika f=02
									$getkibb = mysql_fetch_array(
											   mysql_query("SELECT merk,no_pabrik,bahan,no_mesin 
											   				FROM kib_b
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
									   break;
									}
								case '03':{
									 #ambil kib c berdasarkan f di buku induk jika f=03
									$getkibc = mysql_fetch_array(
											   mysql_query("SELECT dokumen_no 
											   				FROM kib_c
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
									$getkibd = mysql_fetch_array(
											   mysql_query("SELECT dokumen_no 
															FROM kib_d
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
									$getkibe = mysql_fetch_array(
											   mysql_query("SELECT * FROM kib_e
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
									 #ambil kib e berdasarkan f di buku induk jika f=06
									$getkibf = mysql_fetch_array(
											   mysql_query("SELECT dokumen_no 
											   				FROM kib_f
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
								}  //end switch
								
							//thn perolehan ini khusus untuk di pakai di kode barang													 
		  					$thnPER =substr($bi['thn_perolehan'],2,2);
							
					    	echo '<tr>
								  <td class="GarisCetak" align="center">'.$no.'</td>
						          <td class="GarisCetak" align="center">'.$bi['a1'].'.'.$bi['a'].'.'.$bi['b'].'.'.$bi['c'].'.'.$bi['d'].'.'.$thnPER.'.'.$bi['e'].'<br>'.
								  		$bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'].'.'.$bi['noreg'].'</td>
							      <td class="GarisCetak">'.$result2['nm_barang'].'</td>
							      <td class="GarisCetak">'.$merk.'</td>
							      <td class="GarisCetak">'.$spesifikasi.'</td>
							      <td class="GarisCetak" align="center">'.$bi['thn_perolehan'].'</td>
							      <td class="GarisCetak" align="center">'.$Main->KondisiBarang[$kondisi-1][1].'</td>
							      <td class="GarisCetak" align="right" >'.$bi['jml_harga'].'</td>
							      <td width="60px" class="GarisCetak">&nbsp</td>
							      <td class="GarisCetak" align="center">Ada / Tidak</td>
							      <td class="GarisCetak" align="center">T/M/P</td>
							      <td class="GarisCetak" align="center">KB/RB</td>
							      <td class="GarisCetak" align="center">&nbsp</td>
								  </tr>';
								
					         } //end buku_induk
							$no++;
					     } //End penghapusan_usul_det
		echo'</tbody></table>';
		echo'<table style="width:100%" border="0">
			<tbody>
			<tr>
				<td><div align="left" style="font-size:8pt;font-family:Arial,Helvetica,sans-serif;">*T/M/P : Tolak/Musnah/Pemindahtanganan</div></td>
		    </tr>
			<tr>
				<td><div align="left" style="font-size:8pt;font-family:Arial,Helvetica,sans-serif;">KB/RB : Kurang Baik/Rusak Berat</div></td>
		    </tr>
			</tbody></table>
			';	
		echo'<br><br>';
	    echo"</div>	".			
				$this->PrintTTDkertaskerja($pagewidth = '21cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='').
			"</td></tr>
			</table>";
		echo"
			</td>	
			</tr>
			</tbody>
		  	</div>
			</form>		
			</body>	
			</html>";
	}


function PrintTTDkertaskerja($pagewidth = '30cm', $xls=FALSE, $cp1='', $cp2='', $cp3='', $cp4='', $cp5='' ) {
    global $fmWIL, $fmSKPD, $fmUNIT, $fmSUBUNIT, $fmTAHUNANGGARAN, $fmKEPEMILIKAN, $Main, $HTTP_COOKIE_VARS, $NAMASKPD, $JABATANSKPD, $NIPSKPD, $NAMASKPD1, $JABATANSKPD1, $NIPSKPD1, $TITIMANGSA;
	
	$get = mysql_fetch_array(mysql_query("SELECT no_tterima,tgl_tterima 
											  FROM penghapusan_usul  
											  WHERE Id ='".$this->form_idplh."' "));
	
    $NIPSKPD = "";
    $NAMASKPD = "";
    $JABATANSKPD = "";
    //$TITIMANGSA = "Bandung, " . JuyTgl1(date("Y-m-d"));
    $TITIMANGSA = $Main->CETAK_LOKASI.", " . JuyTgl1($get['tgl_tterima']);
	$cek ='';
	
	$cbid = $_REQUEST[$this->Prefix.'_cb'];
	
	$this->form_idplh = $cbid[0];
	
	//Ambil data di tabel Penghapusan_usul
	$get= mysql_fetch_Array(mysql_query("SELECT* FROM penghapusan_usul WHERE Id = '".$this->form_idplh ."' "));
	
	//ambil data di tabel ref_pegawai berdasarkan ref_idtterima di tabel penghapusan_usul
	/*$read = mysql_fetch_Array(mysql_query("SELECT* FROM ref_pegawai WHERE Id = '".$get['ref_idtterima']."' "));
    $NIPSKPD1 = $read['nip'];
    $NAMASKPD1 = $read['nama'];
    $JABATANSKPD1 = $read['jabatan'];
    
    $Qry = mysql_query("select * from ref_pejabat where c = '$fmSKPD' and d = '$fmUNIT' and e = '$fmSUBUNIT' and ttd2 = '1' ");
    while ($isi = mysql_fetch_array($Qry)) {
        $NIPSKPD2 = $isi['nik'];
        $NAMASKPD2 = $isi['nm_pejabat'];
       $JABATANSKPD2 = $isi['jabatan'];
    }
	*/
	
	$nmopdarr=array();	
		//================== ambil Bidang ========================================================
		$read = mysql_fetch_array(mysql_query("SELECT * from v_bidang where c='".$get['c']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $read['nmbidang'];
			$bidang =$read['nmbidang'];
		//========================================================================================
		
		//================== ambil OPD =================================================================================
		$select = mysql_fetch_array(mysql_query("select * from v_opd where c='".$get['c']."' and d='".$get['d']."' "));	
			if($read['nmbidang']<>'') $nmopdarr[] = $select['nmopd'];
			$opd = $select['nmopd'];
		//==============================================================================================================
		
		//================== ambil Biro /UPTD / B ============================================================================================
		$getAll = mysql_fetch_array(mysql_query("select * from v_unit where c='".$get['c']."' and d='".$get['d']."' and e='".$get['e']."' "));		
			if($getAll['nmunit']<>'') $nmopdarr[] = $getAll['nmunit'];		
			   $nmopd = join(' <br/> ', $nmopdarr );
			  $biro = $getAll['nmunit'];		
		//====================================================================================================================================
		
	
	$NAMASKPD1 = $NAMASKPD1==''?'.................................................': $NAMASKPD1;
	$NAMASKPD2 = $NAMASKPD2==''?'.................................................': $NAMASKPD2;
	$NIPSKPD1 = $NIPSKPD1==''? 	'                                          ': $NIPSKPD1;
	$NIPSKPD2 = $NIPSKPD2==''? 	'                                          ': $NIPSKPD2;
	
	if($xls == FALSE){
		$vNAMA1	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD1)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vNAMA2	= "<INPUT TYPE=TEXT VALUE='($NAMASKPD2)' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vNIP1	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD1' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:12pt;margin-left:191px' size=50>";
		$vNIP2	= "<INPUT TYPE=TEXT VALUE='NIP. $NIPSKPD2' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:12pt;margin-left:191px' size=50>";
		//$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$TITIMANGSA' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:14pt' size=50>";
		$vTITIKMANGSA 	= "<B><INPUT TYPE=TEXT VALUE='$Main->CETAK_LOKASI,........................' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt' size=50>";
		$vMENGETAHUI 	= "<B><INPUT TYPE=TEXT VALUE='Mengetahui,' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt;font-family:Arial,Helvetica,sans-serif' size=50 >";
		$vJABATAN1		= "<INPUT TYPE=TEXT VALUE='Pengurus Barang'	STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIDANG		= "<INPUT TYPE=TEXT VALUE='$bidang' STYLE='background:none;border:none;text-align:left;font-weight:none;font-size:11pt;font-family:Arial,Helvetica,sans-serif;text-align:left' size=50 >";
		$vASISTEN		= "<INPUT TYPE=TEXT VALUE='$opd' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vBIRO		= "<INPUT TYPE=TEXT VALUE='$biro' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:18pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";
		$vJABATAN2 		= "<B><INPUT TYPE=TEXT VALUE='Petugas Pengecekan' STYLE='background:none;border:none;text-align:center;font-weight:none;font-size:12pt;font-family:Arial,Helvetica,sans-serif;text-align:center' size=50 >";	    	
	}else{
		$vNAMA1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD1)</span>";
		$vNAMA2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >($NAMASKPD2)</span>";
		$vNIP1	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold;font-size:11pt' size=50 >NIP. $NIPSKPD1</span>";
		$vNIP2	= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >NIP. $NIPSKPD2</span>";
		$vTITIKMANGSA 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >$TITIMANGSA</span>";
		$vMENGETAHUI 	= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >MENGETAHUI</span>";
		$vJABATAN1		= "<span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >KEPALA OPD</span>";
		$vJABATAN2 		= "<B><span STYLE='background:none;border:none;text-align:center;font-weight:bold' size=50 >PENGURUS BARANG</span>";
    	
	}
	$Hsl = " <table style='width:$pagewidth' border=0>
				<tr> 
				<td width=100 colspan='$cp1'>&nbsp;</td> 
				<td align=center width=300 colspan='$cp2'>
					$vMENGETAHUI<BR><BR>
					$vJABATAN1
					<BR><BR><BR><BR><BR><BR><BR><BR><BR>
					$vNAMA1
					<br>
					$vNIP1 
				</td> 
					
				<td width=400 colspan='$cp3'>&nbsp;</td>
				<td align=center width=300 colspan='$cp4'>
					$vTITIKMANGSA<BR> 
					<br>
					<br>
					$vJABATAN2
					<BR><BR><BR><BR><BR><BR><BR><BR>
					$vNAMA2
					<br> 					
					$vNIP2
				</td> 
				<td width='*' colspan='$cp5'>&nbsp;</td> 
				</tr> 
			</table> ";
    return $Hsl;
}


	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
			
		$arr = array(
			//array('selectAll','Semua'),
			array('selectUsul','Surat Usulan'),	
			array('selectBA','Surat BA'),		
			);
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				
			</td></tr>			
			</table>".
			
			genFilterBar(
				array(							
					'Cari : '.
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'--Semua--','').
					"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>"	
					),			
				$this->Prefix.".refreshList(true)"
				);
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			/*
			."<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>"
			
			."<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tgl. Surat </div>".
			createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)."
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".			
			genFilterBar(
				array(							
					'Urutkan : '.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Pilih--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>Desc."
					),			
				$this->Prefix.".refreshList(true)");
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>";
			*/
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
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];		
		switch($fmPILCARI){
			case 'selectBA': $arrKondisi[] = " no_ba like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectUsul': $arrKondisi[] = "(no_ba IS NULL OR no_ba ='') AND no_usulan like '%$fmPILCARIvalue%'"; break;			
		}		
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_usul>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_usul<='$fmFiltTglBtw_tgl2'";	
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[]= " tgl_ba>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[]= " tgl_ba<='$fmFiltTglBtw_tgl2'";
		
		//tampilkan yang sudah ada no tandaterima
		$arrKondisi[]="(no_tterima !='' OR no_tterima IS NOT NULL)";
			
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,nip ";			
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
$UsulanHapusba = new UsulanHapusbaObj();


?>