<?php

class renjaObj  extends DaftarObj2{	
	var $Prefix = 'renja';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = "samsak_renja"; 
	var $TblName_Hapus = 'ref_renja';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'RENCANA KERJA';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='renja.xls';
	var $namaModulCetak='RENCANA KERJA';
	var $Cetak_Judul = 'RENCANA KERJA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'renjaForm';
	var $noModul=14; 
	var $TampilFilterColapse = 0; //0
	var $lastUrusan = "";
	var $lastBidang = "";
	var $lastSKPD = "";
	function setTitle(){
		return 'RENCANA KERJA';
	}
	
	function setMenuEdit(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];

/*	 foreach ($_REQUEST as $key => $value) { 
		  $$key = $value; 
	 } */
	 
	 $cmbBidangForm = $_REQUEST['cmbBidangForm'];
	 $cmbSKPDForm = $_REQUEST['cmbSKPDForm'];
	 $r = $_REQUEST['r'];
	 $Pnya = $_REQUEST['Pnya'];
	 $cmbKegiatan = $_REQUEST['cmbKegiatan'];
	 $tahunAnggaran = $_REQUEST['$tahunAnggaran'];
	 $nama_urusan = $_REQUEST['nama_urusan'];
	 $nama_program = $_REQUEST['nama_program'];
	 $nama_kegiatan = $_REQUEST['nama_kegiatan'];
	 $belanjaPegawai = $_REQUEST['belanjaPegawai'];
	 $belanjaModal = $_REQUEST['belanjaModal'];
	 $belanjaBarangJasa = $_REQUEST['belanjaBarangJasa'];


	  
	 if( $err=='' && $belanjaPegawai == '' ) $err= 'Lengkapi !!';
	 
			if($fmST == 0){
				if($err==''){
					if ($_COOKIE['cofmSKPD'] != '00' && $_COOKIE['cofmSKPD'] != '') {
					$kueBidang = $_COOKIE['cofmSKPD'];
					$kueSKPD = $_COOKIE['cofmUNIT'];
					
					//cari nama bidang dan SKPD
					$getBidang = mysql_fetch_array( mysql_query("SELECT nm_skpd as vnama FROM ref_skpd where c='$kueBidang' and  d = '00' and e = '00' and e1='000'"));
					$nama_bidang = $getBidang['vnama'];
					$getSKPD = mysql_fetch_array( mysql_query("SELECT nm_skpd as vnama FROM ref_skpd where c='$kueBidang' and  d = '$kueSKPD' and e = '00' and e1='000'"));
					$nama_skpd = $getBidang['vnama'];
					
					//seleksi pertama  (cek nama urusan) 
					$cekPertama = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '' and d = '' and p = '' and q = '' "));
					if(sizeof($cekPertama) == 0){
						$aqry = "insert into samsak_renja (r,nama_urusan,tahun) values ('$r','$nama_urusan','$tahunAnggaran')";
				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi kedua (cek nama_program )
					$cekKedua = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '' and d = '' and p = '$Pnya' and q = '00' "));
					if(sizeof($cekKedua) == 0){
					$aqry = "insert into samsak_renja (r,p,q,nama_program_kegiatan,tahun) values ('$r','$Pnya','00','$nama_program','$tahunAnggaran')"; 					$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi ketiga (cek  nama bidang )
					$cekKetiga = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '$kueBidang' and d = '00' and p = '$Pnya' and q = '00' "));
					if(sizeof($cekKetiga) == 0){
					$aqry = "insert into samsak_renja (r,c,d,p,q,nama_bidang,tahun) values ('$r','$kueBidang','00','$Pnya','00','$nama_bidang','$tahunAnggaran')";
					$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi keempat (cek  nama SKPD )
					$cekKeempat = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '$kueBidang' and d = '$kueSKPD' and p = '$Pnya' and q <> '00' "));
					if(sizeof($cekKeempat) == 0){
					$aqry = "insert into samsak_renja (r,c,d,p,q,nama_skpd,tahun,belanja_pegawai,belanja_barang_jasa,belanja_modal,nama_program_kegiatan) values ('$r','$kueBidang','$kueSKPD',$Pnya','$cmbKegiatan','$nama_skpd','$tahunAnggaran','$belanjaPegawai','$belanjaBarangJasa','$belanjaModal','$nama_kegiatan')"; 					
				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}else{
					$aqry = "insert into samsak_renja (r,c,d,p,q,tahun,belanja_pegawai,belanja_barang_jasa,belanja_modal,nama_program_kegiatan) values ('$r','$kueBidang','$kueSKPD',$Pnya','$cmbKegiatan','$nama_skpd','$tahunAnggaran','$belanjaPegawai','$belanjaBarangJasa','$belanjaModal','$nama_kegiatan')"; 							$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					
					
					}else{
					
					
					//cari nama bidang dan SKPD
					$getBidang = mysql_fetch_array( mysql_query("SELECT nm_skpd as vnama FROM ref_skpd where c='$cmbBidangForm' and  d = '00' and e = '00' and e1='000'"));
					$nama_bidang = $getBidang['vnama'];
					$getSKPD = mysql_fetch_array( mysql_query("SELECT nm_skpd as vnama FROM ref_skpd where c='$cmbBidangForm' and  d = '$cmbSKPDForm' and e = '00' and e1='000'"));
					$nama_skpd = $getBidang['vnama'];
					
					//seleksi pertama  (cek nama urusan) 
					$cekPertama = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '' and d = '' and p = '' and q = '' "));
					if(sizeof($cekPertama) == 0){
						$aqry = "insert into samsak_renja (r,nama_urusan,tahun) values ('$r','$nama_urusan','$tahunAnggaran')";
				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi kedua (cek nama_program )
					$cekKedua = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '' and d = '' and p = '$Pnya' and q = '00' "));
					if(sizeof($cekKedua) == 0){
					$aqry = "insert into samsak_renja (r,p,q,nama_program_kegiatan,tahun) values ('$r','$Pnya','00','$nama_program','$tahunAnggaran')"; 					$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi ketiga (cek  nama bidang )
					$cekKetiga = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '$cmbBidangForm' and d = '00' and p = '$Pnya' and q = '00' "));
					if(sizeof($cekKetiga) == 0){
					$aqry = "insert into samsak_renja (r,c,d,p,q,nama_bidang,tahun) values ('$r','$cmbBidangForm','00','$Pnya','00','$nama_bidang','$tahunAnggaran')"; 				
				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}
					//seleksi keempat (cek  nama SKPD )
					$cekKeempat = mysql_fetch_array( mysql_query("select r,c,d,p,q from samsak_renja where r = '$r' and c = '$cmbBidangForm' and d = '$cmbSKPDForm' and p = '$Pnya' and q <> '00' "));
					if(sizeof($cekKeempat) == 0){
					$aqry = "insert into samsak_renja (r,c,d,p,q,nama_skpd,tahun,belanja_pegawai,belanja_barang_jasa,belanja_modal,nama_program_kegiatan) values ('$r','$cmbBidangForm','$cmbSKPDForm',$Pnya','$cmbKegiatan','$nama_skpd','$tahunAnggaran','$belanjaPegawai','$belanjaBarangJasa','$belanjaModal','$nama_kegiatan')"; 					
					 				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}else{
					$aqry = "insert into samsak_renja (r,c,d,p,q,tahun,belanja_pegawai,belanja_barang_jasa,belanja_modal,nama_program_kegiatan) values ('$r','$cmbBidangForm','$cmbSKPDForm',$Pnya','$cmbKegiatan','$nama_skpd','$tahunAnggaran','$belanjaPegawai','$belanjaBarangJasa','$belanjaModal','$nama_kegiatan')"; 										$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());
					}

					
					}
				}
			}else{						
				if($err==''){


				$aqry = "UPDATE ref_renja set  belanja_pegawai = '$belanjaPegawai', belanja_barangjasa = '$belanjaBarangJasa', belanja_modal = '$belanjaModal', p = '$Pnya',q = '$cmbKegiatan', r = '$r'  WHERE id='".$idplh."'";	
				$cek .= $aqry; $qry = mysql_query($aqry) or die(mysql_error());		
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
		 case 'getNamaUrusan':{
				$R = $_REQUEST['r'];
				$get = mysql_fetch_array( mysql_query("select * from ref_urusan_renja where r='$R'"));
				$content = array('nama_urusan' => $get['nama_urusan'] );
		break;
		}
		case 'getNamaKegiatan':{
				$P = $_REQUEST['p'];
				$Q = $_REQUEST['q'];
				$get = mysql_fetch_array( mysql_query("select * from ref_programkegiatan where p='$P' and q='$Q' "));
				$content = array('nama_kegiatan' => $get['nama_program_kegiatan'] );
		break;
		}
	   
		case 'BidangAfter':{
				$fmBidang = $_REQUEST['fmBidang'];
				$fmKELOMPOK = cekPOST('fmKELOMPOK2');
				$fmSUBKELOMPOK = cekPOST('fmSUBKELOMPOK2');
				$fmSUBSUBKELOMPOK = cekPOST('fmSUBSUBKELOMPOK2');
				$content->kelompok = cmbQuery1("fmKELOMPOK2",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBidang' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.KelompokAfter()\"",'Pilih','');
				$content->subkelompok = cmbQuery1("fmSUBKELOMPOK2",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
				$content->subsubkelompok = cmbQuery1("fmSUBSUBKELOMPOK2",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","",'Pilih','');
			break;
		}
				   			


			case 'BidangAfterForm':{
				$content= $this->cmbQuerySKPD('cmbSKPDForm',$fmSKPDskpd,'','onchange=renja.refreshList(true)','--- SEMUA SKPD ---','00');
			break;
			}
				
			case 'SKPDAfter':{
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
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
			"<script src='js/skpd.js' type='text/javascript'></script>
			<script type='text/javascript' src='js/master/ref_renja/ref_renja.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/master/ref_renja/list_programKegiatan.js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$dt['bidang'] = $_REQUEST['fmSKPDBidang'];
		$dt['skpd'] = $_REQUEST['fmSKPDskpd'];
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;				

		if($cnt['cnt'] > 0) $err = "renja Tidak Bisa Diubah ! Sudah Digunakan Di Ref Barang.";
		if($err == ''){
			$aqry = "SELECT ref_renja.id, ref_skpd.nm_skpd, ref_programkegiatan.nama_program_kegiatan, ref_skpd.c , ref_urusan_renja.r, ref_urusan_renja.nama_urusan, ref_skpd.d, ref_programkegiatan.p, ref_programkegiatan.q, ref_renja.belanja_pegawai, ref_renja.belanja_barangjasa, ref_renja.belanja_modal FROM ref_renja inner join ref_skpd on ref_renja.c = ref_skpd.c and ref_renja.d = ref_skpd.d inner join ref_programkegiatan on ref_renja.p = ref_programkegiatan.p and ref_renja.q = ref_programkegiatan.q inner join ref_urusan_renja on ref_urusan_renja.r = ref_renja.r  WHERE ref_renja.id='".$this->form_idplh."' group by concat(ref_skpd.c,'.',ref_skpd.d,'.',ref_renja.p,'.',ref_renja.q)  "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$fm = $this->setForm($dt);
		}
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
		
function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 600;
	 $this->form_height = 300;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
	    $selectedBidang = $_REQUEST['fmSKPDBidang'];
        $selectedskpd = $_REQUEST['fmSKPDskpd'];
		
		if($_COOKIE['cofmSKPD']!='00' ){
		$cmbRo = "disabled";
		}else{
			$cmbRo = "";
		}	
	  }else{
		$this->form_caption = 'Edit';			
			$cmbRo = 'disabled';	
			$selectedBidang = $dt['c'];
	     	$selectedskpd =  $dt['d'];
			$belanjaPegawai = $dt['belanja_pegawai'];
			$belanjaBarangJasa = $dt['belanja_barangjasa'];
			$belanjaModal = $dt['belanja_modal'];
			$selectedP = $dt['p'];
			$selectedQ = $dt['q'];
			$selectedR = $dt['r'];
	  }
	    //ambil data trefditeruskan
   $codeAndNameBidang = "SELECT c, concat(c, '. ', nm_skpd) as vnama FROM ref_skpd where d = '00' and e = '00' and e1='000'";	

     $codeAndNameskpd = "SELECT d, concat(d, '. ', nm_skpd) as vnama FROM ref_skpd  where c='$selectedBidang' and d != '00' and  e = '00' and e1='000' ";
     $cek .= $codeAndNameskpd;
	 

	    $refDataNamaKegiatan= "SELECT q, nama_program_kegiatan  FROM ref_programkegiatan where p = '$selectedP' and q <> '0' ";	
		
		$refUrusanRencanaKerja = "select r, nama_urusan from ref_urusan_renja";
		
	  	$query = "select * from ref_skpd " ;$cek .=$query;
	  	$res = mysql_query($query);
		


	$comboBoxBidangForm =  cmbQuery('cmbBidangForm', $selectedBidang, $codeAndNameBidang,''.$cmbRo.'  onChange=\''.$this->Prefix.'.BidangAfterform()\'','-- Pilih Semua --');	

if ($cmbRo == 'disabled'){
	$comboLucknut= cmbQuery('cmbKegiatan', $selectedP, $refDataNamaKegiatan,'  onChange=\''.$this->Prefix.'.getNamaKegiatan()\'','-- Pilih Semua --');
}else{
	$comboLucknut= cmbQuery('cmbKegiatan', $selectedP, $refDataNamaKegiatan,'  onChange=\''.$this->Prefix.'.getNamaKegiatan()\'','-- Pilih Semua --');
}
	



	 //items ----------------------
	  $this->form_fields = array(
			'kode1' => array(
	  					'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=> $comboBoxBidangForm
						 ),
			'kode2' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=> cmbQuery('cmbSKPDForm', $selectedskpd, $codeAndNameskpd,''.$cmbRo.'','-- Pilih Semua --')
						 ),	
			'tahunAnggaran' => array( 
						'label'=>'TAHUN ANGGARAN',
						'labelWidth'=>150, 
						'value'=>$dt['tahun'], 
						'type'=>'text',
						'param'=>"style='width:40px;' readOnly"
						 ),
			 'program' => array( 
						'label'=>'PROGRAM',
						'labelWidth'=>100, 
						'value'=>"
							<input type='hidden' name='Pnya' style='width:0px;' id='Pnya' value='".$dt['p']."' readonly>
							<input type='hidden' name='nama_kegiatan' style='width:0px;' id='nama_kegiatan' value='".$dt['nama_program_kegiatan']."' readonly>
							<input type='hidden' name='nama_urusan' style='width:0px;' id='nama_urusan' value='".$dt['nama_urusan']."' readonly>
							<input type='text' name='nama_program' style='width: 300px;' id='nama_program' value='".$dt['nama_program_kegiatan']."' readonly>
							<input type='button' value='CARI' id='Cari' onclick='renja.Cari()'>",
						 ),	
			'kegiatan' => array( 
						'label'=>'KEGIATAN',
						'labelWidth'=>150, 
						'value'=> $comboLucknut 
						 ),	
			'urusan_renja' => array( 
						'label'=>'URUSAN PEMERINTAHAN',
						'labelWidth'=>150, 
						'value'=> cmbQuery('r', $selectedR, $refUrusanRencanaKerja,'  onChange=\''.$this->Prefix.'.getNamaUrusan()\'','-- Pilih Semua --') 
						 ),	
			'belanjaPegawai' => array( 
						'label'=>'BELANJA PEGAWAI',
						'labelWidth'=>100, 
						'value'=>'<input type="text" name="belanjaPegawai" placeholder="Rp." value="'.$belanjaPegawai.'" id="belanjaPegawai" onkeypress="return isNumberKey(event)" onkeyup="document.getElementById(`formatbelanjaPegawai`).innerHTML = renja.formatCurrency(this.value);" style="width:150px;text-align:right"/> <b> Rp </b>  <span  id="formatbelanjaPegawai" style="font-weight:bold;">'.number_format($belanjaPegawai,2,",",".").'</span>' 
						 ),
			'belanjaBarangJasa' => array( 
						'label'=>'BELANJA BARANG JASA',
						'labelWidth'=>100, 
						'value'=>'<input type="text" name="belanjaBarangJasa" placeholder="Rp." value="'.$belanjaBarangJasa.'" id="belanjaBarangJasa" onkeypress="return isNumberKey(event)" onkeyup="document.getElementById(`formatbelanjaBarangJasa`).innerHTML = renja.formatCurrency(this.value);" style="width:150px;text-align:right"/> <b> Rp </b>  <span  id="formatbelanjaBarangJasa" style="font-weight:bold;">'.number_format($belanjaBarangJasa,2,",",".").'</span>' 
						 ),
			'belanjaModal' => array( 
						'label'=>'BELANJA MODAL',
						'labelWidth'=>100, 
						'value'=>'<input type="text" name="belanjaModal" placeholder="Rp." value="'.$belanjaModal.'" id="belanjaModal" onkeypress="return isNumberKey(event)" onkeyup="document.getElementById(`formatBelanjaModal`).innerHTML = renja.formatCurrency(this.value);" style="width:150px;text-align:right"/> <b> Rp </b>  <span  id="formatBelanjaModal" style="font-weight:bold;">'.number_format($belanjaModal,2,",",".").'</span>' 
						 ),
			
			);
			
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setPage_HeaderOther(){
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages?Pg=renja\" title='RENCANA KERJA'>RENCANA KERJA</a> |
	
	&nbsp&nbsp&nbsp	
	</td></tr></table>";
	}
		
	//daftar =================================
	

	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan=2>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01'  rowspan=2 width='40' >KODE</th>
				<th class='th01' rowspan=2>NAMA URUSAN, BIDANG DAN SKPD</th>
				<th class='th01'  rowspan=2 width='50' colspan ='2'>KODE PROGRAM/KEGIATAN</th>
				<th class='th01' rowspan=2>NAMA PROGRAM DAN KEGIATAN</th>

				<th class='th02' colspan=3>JENIS BELANJA</th>
				</tr>
				<tr>
				<th class='th01' width='200' rowspan=2>PEGAWAI</th>
				<th class='th01' width='200' rowspan=2>BARANG DAN JASA</th>
				<th class='th01' width='200' rowspan=2>MODAL</th>

				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref, $lastUrusan, $lastBidang, $lastSKPD;
	 $bold = "";

	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		 $R= $isi['r'];
		 $C= $isi['c'];
		 $D= $isi['d'];
		 $P= $isi['p'];
		 $Q= $isi['q'];	
		if($isi['c'] == '' && $isi['p'] == '' ){
		 $margin = 'style="margin-left:0px; font-weight:bold;" ';
		 $Koloms[] = array('align="left"',$isi['r']);
		 $Koloms[] = array('align="left" style="font-weight:bold;" ',$isi['nama_urusan']);
		 $Koloms[] = array('align="center"',$isi['p']);
		 $Koloms[] = array('align="center"',$isi['q']);
		 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_program_kegiatan']."</span>");

		 
		 $sumPerUrusan= "select sum(belanja_pegawai) as totalPerUrusanPegawai, sum(belanja_barang_jasa) as totalPerUrusanBarangJasa, sum(belanja_modal) as totalPerUrusanModal  from samsak_renja where r = '$R' and c <> '00' and d <> '00' and p <> '00' and q <> '00'";
		 $get = mysql_fetch_array( mysql_query($sumPerUrusan));
		 
		 
		 $Koloms[] = array('align="right"',number_format($get['totalPerUrusanPegawai'],2,",","."));
		 $Koloms[] = array('align="right"',number_format($get['totalPerUrusanBarangJasa'],2,",","."));
		 $Koloms[] = array('align="right"',number_format($get['totalPerUrusanModal'],2,",",".")); 
		}elseif($isi['c'] == '' && $isi['p'] != ''){
		 $Koloms[] = array('align="left"',);
		 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>Total / Program </span>");
		 $Koloms[] = array('align="center"',$isi['p']);
		 $Koloms[] = array('align="center"',$isi['q']);
		 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_program_kegiatan']."</span>");
		 
		 $sumPerProgram= "select sum(belanja_pegawai) as totalPerProgramPegawai, sum(belanja_barang_jasa) as totalPerProgramBarangJasa, sum(belanja_modal) as totalPerProgramModal  from samsak_renja where r = '$R' and c <> '00' and d <> '00' and p = '$P' and q <> '00'";
		 $get = mysql_fetch_array( mysql_query($sumPerProgram));
		 
		 $Koloms[] = array('align="right"',number_format($get['totalPerProgramPegawai'],2,",","."));
		 $Koloms[] = array('align="right"',number_format($get['totalPerProgramBarangJasa'],2,",","."));
		 $Koloms[] = array('align="right"',number_format($get['totalPerProgramModal'],2,",","."));	
		}else{
			if($isi['q'] =='00'){
				 $margin = 'style="margin-left:15px; font-weight:bold;" ';
				 $Koloms[] = array('align="left"',$isi['r'].".".$isi['c'].".".$isi['d'].".".$isi['p'].".".$isi['q']);	
				 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_bidang']."</span>");	
				 $Koloms[] = array('align="center"',$isi['p']);
				 $Koloms[] = array('align="center"',$isi['q']);
				 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_program_kegiatan']."</span>");
				 $sumPerBidang= "select sum(belanja_pegawai) as totalPerBidangPegawai, sum(belanja_barang_jasa) as totalPerBidangBarangJasa, sum(belanja_modal) as totalPerBidangModal  from samsak_renja where r = '$R' and c = '$C' and d <> '00' and p = '$P' and q <> '00'";
				 $get = mysql_fetch_array( mysql_query($sumPerBidang));
				 
				 $Koloms[] = array('align="right"',number_format($get['totalPerBidangPegawai'],2,",","."));
				 $Koloms[] = array('align="right"',number_format($get['totalPerBidangBarangJasa'],2,",","."));
				 $Koloms[] = array('align="right"',number_format($get['totalPerBidangModal'],2,",","."));	
			}else{
				 $margin = 'style="margin-left:30px;" ';
				 $Koloms[] = array('align="left"',$isi['r'].".".$isi['c'].".".$isi['d'].".".$isi['p'].".".$isi['q']);	
				 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_skpd']."</span>");	
				 $Koloms[] = array('align="center"',$isi['p']);
				 $Koloms[] = array('align="center"',$isi['q']);
				 $Koloms[] = array('align="left" '.$bold.' ',"<span $margin>".$isi['nama_program_kegiatan']."</span>");
				 $Koloms[] = array('align="right"',number_format($isi['belanja_pegawai'],2,",","."));
				 $Koloms[] = array('align="right"',number_format($isi['belanja_barang_jasa'],2,",","."));
				 $Koloms[] = array('align="right"',number_format($isi['belanja_modal'],2,",","."));
			}

		}
		 




	 return $Koloms;
	 

	 
	 
	}
	
	function genDaftarOpsi(){
	global $Ref, $Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
	 $fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
	 $fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
	$fmTahun=  cekPOST('fmTahun')==''?$_COOKIE['coThnAnggaran']:cekPOST('fmTahun');
	$fmBIDANG = cekPOST('fmBIDANG');
	
	 $arr = array(
			//array('selectAll','Semua'),	
			array('kode_program_kegiatan','KODE PROGRAM KEGIATAN'),	
			array('nama_program_kegiatan','NAMA PROGRAM KEGIATAN'),
			);
		
	 //data order ------------------------------
	 $arrOrder = array(

					array('1','KODE PROGRAM KEGIATAN'),	
					array('2','NAMA PROGRAM KEGIATAN'),
					);
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
		$baris = $_REQUEST['baris'];
		$tahunAnggaran = $_COOKIE['coThnAnggaran'];
	$TampilOpt =
				"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				ComboBidangDanSKPD('renja'). 
			"</td>
			<td >" . 		
			"</td></tr>".
			"<td width=\"100%\" valign=\"top\">" . 
				
			"</td>
			<td >" . 		
			"</td></tr>".
			
			$vOrder=
			genFilterBar(
				array(							
					cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --',''). //generate checkbox					
					" <input type='text' placeholder='Kata Kunci Pencarian' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue''> "
					.
					cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','').
					"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun.".
					"  Baris / Halaman : <input type='text' name='baris' value='$baris' id='baris' style='width:30px;'> &nbsp &nbsp       <input type='text' value='$tahunAnggaran' name='thnAnggaran' id='thnAnggaran'style='width:40px;' readonly> "
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
		//cari tgl,bln,thn
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];

		$kueBidang = $_COOKIE['cofmSKPD'];
		$kueSKPD =  $_COOKIE['cofmUNIT'];
		
		$ref_skpdSkpdfmSKPD = $_REQUEST['fmSKPDBidang'];
		$ref_skpdSkpdfmUNIT = $_REQUEST['fmSKPDskpd'];
	
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_program_kegiatan': $arrKondisi[] = "nama_program_kegiatan like '%$fmPILCARIvalue%'"; break;	
			case 'kode_program_kegiatan': $arrKondisi[] = " concat(p,'.',q) like '%$fmPILCARIvalue%'"; break;						 	
		}
		if($kueBidang!='00' and $kueBidang!='')$arrKondisi[]= "c='$kueBidang'";
		if($kueSKPD!='00' and $kueSKPD!='')$arrKondisi[]= "d='$kueSKPD'";

		if($ref_skpdSkpdfmSKPD!='00' and $ref_skpdSkpdfmSKPD !=''  )$arrKondisi[]= "c='$ref_skpdSkpdfmSKPD'";

		if($ref_skpdSkpdfmSKPD!='00'){

		if($ref_skpdSkpdfmUNIT!='00' and $ref_skpdSkpdfmUNIT !='' )$arrKondisi[]= "d='$ref_skpdSkpdfmUNIT'";
		}
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		

		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " p $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_program_kegiatan $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					

		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;

		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main;
	 Global $fmSKPDBidang;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil['c']}. {$Hasil[nm_skpd]}";
    	}
		
	 	
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main;
	 Global $fmSKPDBidang,$fmSKPDskpd;
		$fmSKPDBidang = cekPOST('fmSKPDBidang');
		$fmSKPDskpd = cekPOST('fmSKPDskpd');
/*		setcookie('cofmSKPD',$fmSKPDBidang);
		setcookie('cofmUNIT',$fmSKPDskpd);*/
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[d]}. {$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}

	function Hapus($ids){ //validasi hapus ref_kota
		 $err=''; $cek='';
		for($i = 0; $i<count($ids); $i++)	{
		

					$qy = "DELETE FROM $this->TblName_Hapus WHERE id='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
		
		}
		return array('err'=>$err,'cek'=>$cek);
	}
}
$ref_renja = new renjaObj();
?>