<?php

class ref_tandatanganObj  extends DaftarObj2{	
	var $Prefix = 'ref_tandatangan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_tandatangan'; //daftar
	var $TblName_Hapus = 'ref_tandatangan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Tanda Tangan';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='ref_tandatangan.xls';
	var $Cetak_Judul = 'TANDA TANGAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'ref_tandatanganForm'; 
	var $kdbrg = '';	
	var $arrEselon = array( 
		array('1','ESELON I'),
		array('2','ESELON II'),
		array('3','ESELON III'),
		array('4','ESELON IV'),
		array('5','ESELON V')
		);
				
	function setTitle(){
		return 'TANDA TANGAN';
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
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
     $kode1= $_REQUEST['c1'];
	 $kode2= $_REQUEST['c'];
	 $kode3= $_REQUEST['d'];
	 $kode4= $_REQUEST['e'];
	 $kode5= $_REQUEST['e1'];
	 $namapegawai= $_REQUEST['namapegawai'];
	 $nippegawai= $_REQUEST['nippegawai'];
	// $pangkat= $_REQUEST['pangkat'];
	 $p1= $_REQUEST['pangkatakhir'];
	 /*$p2= $_REQUEST['p2'];
	 $p3= $_REQUEST['p3'];*/
	 $golang_akhir= $_REQUEST['golang_akhir'];
	 $golongan = explode("/", $golang_akhir);
	 $jabatan= $_REQUEST['jabatan'];
	 $eselon= $_REQUEST['eselon_akhir'];
	/* $nama = $_REQUEST['nama'];
	 $nip = $_REQUEST['nip'];
	 $jabatan = $_REQUEST['jabatan'];*/
	//pangkatakhir
	 
	 if( $err=='' && $namapegawai =='' ) $err= 'NAMA PEGAWAI Belum Di Isi !!';
	 if( $err=='' && $nippegawai =='' ) $err= 'NIP Belum Di Isi !!';
	 if( $err=='' && $p1 =='' ) $err= 'PANGKAT/ GOL/ RUANG Belum Di Pilih !!';
	 if( $err=='' && $jabatan =='' ) $err= 'JABATAN Belum Di Isi !!';
	 if( $err=='' && $eselon =='' ) $err= 'ESELON Belum Di Pilih !!';
	// if( $err=='' && $p1 =='' ) $err= 'PANGKAT/ GOL/ RUANG Belum Di Pilih !!';
	 
	/* if( $err=='' && $kode1 =='' ) $err= 'Kode 1 Belum Di Isi !!';
	 if( $err=='' && $kode2 =='' ) $err= 'Kode 2 Belum Di Isi !!';
	 if( $err=='' && $kode3 =='' ) $err= 'Kode 3 Belum Di Isi !!';
	 if( $err=='' && $kode4 =='' ) $err= 'Kode 4 Belum Di Isi !!';
	 if( $err=='' && $nama =='' ) $err= 'nama Belum Di Isi !!';
	 if( $err=='' && $nip =='') $err= 'nip Belum Di Isi !!';
	 if( $err=='' && $jabatan =='') $err= 'jabatan Belum Di Isi !!';*/
	 	
	
	// if($err=='' && $kode_skpd =='' ) $err= 'Kode Skpd belum diisi';	 
	// if(strlen($kode1)!=2 || strlen($kode2)!=2 || strlen($kode3)!=2 || strlen($kode4)!=3) $err= 'Format Kode SKPD salah bro';	
	/*		for($j=0;$j<4;$j++){
	//urutan kode skpd 	
		if($j==0){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_tandatangan where c ='00' and d ='00' and e ='00' and e1 ='000' Order By c DESC limit 1"));
			if($kode1=='00') {$err= 'Format Kode Bidang salah';}
			elseif($kode1>sprintf("%02s",$ck['c']+1)){ $err= 'Format Kode Bidang Harus berurutan';}
				
		}elseif($j==1){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_tandatangan where c='".$kode1."' and d !='00' and e ='00' and e1 ='000' Order By d DESC limit 1"));	
			if ($kode2>sprintf("%02s",$ck['d']+1)) {$err= 'Format Kode SKPD Harus berurutan';}		
			
		}elseif($j==2){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_tandatangan where c='".$kode1."' and d ='".$kode2."' and e !='00' and e1 ='000' Order By e DESC limit 1"));			
			if ($kode3>sprintf("%02s",$ck['e']+1)) {$err= 'Format Kode Unit SKPD Harus berurutan';}		
				
		}elseif($j==3){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_tandatangan where c='".$kode1."' and d ='".$kode2."' and e ='".$kode3."' and e1 !='000' Order By e1 DESC limit 1"));	
			if ($kode4>sprintf("%02s",$ck['e1']+1)) {$err= 'Format Kode Sub Unit SKPD Harus berurutan';}
		
					
		}
	 }*/
	 
			if($fmST == 0){
			$ck1=mysql_fetch_array(mysql_query("Select * from ref_tandatangan where c='$kode1' and d ='$kode2' and e ='$kode3' and e1='$kode4'"));
			if ($ck1>=1)$err= 'Gagal Simpan'.mysql_error();
				if($err==''){
					$aqry = "INSERT into ref_tandatangan (c1,c,d,e,e1,nama,nip,jabatan,pangkat,gol,ruang,eselon) values('$kode1','$kode2','$kode3','$kode4','$kode5','$namapegawai','$nippegawai','$jabatan','$p1','$golongan[0]','$golongan[1]','$eselon')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_tandatangan SET nama='$namapegawai', nip='$nippegawai', jabatan='$jabatan' ,pangkat='$p1', gol='$golongan[0]' ,ruang='$golongan[1]',eselon='$eselon' WHERE Id='".$idplh."'";	$cek .= $aqry;
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
		
		case 'pilihPangkat':{				
			global $Main;
			$cek = ''; $err=''; $content=''; $json=TRUE;
			
			$idpangkat = $_REQUEST['pangkatakhir'];
			
			$query = "select concat(gol,'/',ruang)as nama FROM ref_pangkat WHERE nama='$idpangkat'" ;
			$get=mysql_fetch_array(mysql_query($query));$cek.=$query;
			$content=$get['nama'];											
			break;
		}
			
		case 'simpan':{
			$get= $this->simpan();
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
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script src='js/skpd.js' type='text/javascript'></script>
			 <script type='text/javascript' src='js/master/ref_tandatangan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
			 ".
			// "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$dt['c1']=$_REQUEST[$this->Prefix.'SkpdfmUrusan'];
		$dt['c']=$_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d']=$_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e']=$_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1']=$_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$dt['dk']='0';//$fmDINAS;
		
		if($dt['c1'] == '' || $dt['c1'] == '0' && $err=='')$err='URUSAN Belum di Pilih !';
		if($dt['c'] == '' || $dt['c'] == '00' && $err=='')$err='BIDANG Belum di Pilih !';
		if($dt['d'] == '' || $dt['d'] == '00' && $err=='')$err='SKPD Belum di Pilih !';
		if($dt['e'] == '' || $dt['e'] == '00' && $err=='')$err='UNIT Belum di Pilih !';
		if($dt['e1'] == '' || $dt['e1'] == '000' && $err=='')$err='SUB UNIT Belum di Pilih !';
		if($err == '')$fm = $this->setForm($dt);		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
  	function setFormEdit(){
		
	$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = $cbid[0];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_tandatangan WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	
	function setForm($dt){	
		global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 500;
	 $this->form_height = 260;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU TANDA TANGAN';
		$nip	 = '';
	  }else{
		$this->form_caption = 'EDIT TANDA TANGAN';			
		$readonly='readonly';
					
	  }
	   $arrOrder = array(
	  	           array('1','Kepala Dinas'),
			       array('2','Pengurus Barang'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectKepala Dinas','Kepala Dinas'),	
			array('selectPengurus Barang','Pengurus Barang'),		
			);
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		$kode1=genNumber($dt['c'],2);
		$kode2=genNumber($dt['d'],2);
		$kode3=genNumber($dt['e'],2);
		$kode4=genNumber($dt['e1'],3);
		$nama=$dt['nama'];
		$nip=$dt['nip'];
		$jabatan=$dt['jabatan'];
		$Arrjbt = array(
						array('1.',"Kepala Dinas"),
						array('2.','Pengurus Barang'),


);		
		$c1 = $dt['c1'];
		$c = $dt['c'];
		$d = $dt['d'];
		$e = $dt['e'];
		$e1 = $dt['e1'];
		
		$qry4 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='00' AND d='00' AND e='00' AND e1='000'";//$cek.=$qry;
		$aqry4 = mysql_query($qry4);
		$queryc1 = mysql_fetch_array($aqry4);
		
		$qry = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='$c' AND d='00' AND e='00' AND e1='000'";//$cek.=$qry;
		$aqry = mysql_query($qry);
		$queryc = mysql_fetch_array($aqry);
	//	$cek.=$data;
		
		$qry1 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='$c' AND d='$d' AND e='00' AND e1='000'";//$cek.=$qry1;
		$aqry1 = mysql_query($qry1);
		$queryd = mysql_fetch_array($aqry1);
		
		$qry2 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='000'";//$cek.=$qry2;
		$aqry2 = mysql_query($qry2);
		$querye = mysql_fetch_array($aqry2);
		
		$qry3 = "SELECT * FROM ref_skpd WHERE c1='$c1' AND c='$c' AND d='$d' AND e='$e' AND e1='$e1'";$cek.=$qry3;
		$aqry3 = mysql_query($qry3);
		$querye1 = mysql_fetch_array($aqry3);
		
		
		
	
		$queryPangkat="select nama,concat(nama,' (',gol,'/',ruang,')')as nama from ref_pangkat order by gol,ruang";
		$queryPangkat2="select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from ref_tandatangan where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'";
	//	$queryPangkat2=mysql_fetch_array(mysql_query("select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from ref_tandatangan where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'"));
		$cek.="select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from ref_tandatangan where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'";
       //items ----------------------
		 
		//$qry_jabatan = "SELECT Id, nama FROM ref_jabatan WHERE c1='$c1' AND c='$c' AND d='$d' ";
		$querygedung="";
		$datc1=$queryc1['c1'].".".$queryc1['nm_skpd'];
		$datc=$queryc['c'].".".$queryc['nm_skpd'];
		$datd=$queryd['d'].".".$queryd['nm_skpd'];
		$date=$querye['e'].".".$querye['nm_skpd'];
		$date1=$querye1['e1'].".".$querye1['nm_skpd'];
		
		 $this->form_fields = array(
			'URUSAN' => array( 
						'label'=>'URUSAN',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='dc1' id='dc1' value='".$datc1."' style='width:300px;' readonly>
						<input type ='hidden' name='c1' id='c1' value='".$queryc1['c1']."'>
						</div>", 
						 ),
			
			'bidang' => array( 
						'label'=>'BIDANG',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='dc' id='dc' value='".$datc."' style='width:300px;' readonly>
						<input type ='hidden' name='c' id='c' value='".$queryc['c']."'>
						</div>", 
						 ),
			
			'skpd' => array( 
						'label'=>'SKPD',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='dd' id='dd' value='".$datd."' style='width:300px;' readonly>
						<input type ='hidden' name='d' id='d' value='".$queryd['d']."'>
						</div>", 
						 ),			
								
			'unit' => array( 
						'label'=>'UNIT',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='de' id='de' value='".$date."' style='width:300px;' readonly>
						<input type ='hidden' name='e' id='e' value='".$querye['e']."'>
						</div>", 
						 ),					
			
			'subunit' => array( 
						'label'=>'SUB UNIT',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='de1' id='de1' value='".$date1."' style='width:300px;' readonly>
						<input type ='hidden' name='e1' id='e1' value='".$querye1['e1']."'>
						</div>", 
						 ),	
			
			'namapegawai' => array( 
						'label'=>'NAMA PEGAWAI',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='namapegawai' id='namapegawai' value='".$dt['nama']."' style='width:300px;'>
						
						</div>", 
						 ),	
			'nippegawai' => array( 
						'label'=>'NIP',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='nippegawai' id='nippegawai' value='".$dt['nip']."' style='width:300px;'>
						
						</div>", 
						 ),	
						 
			
		
			'pangkat' => array( 
						'label'=>'PANGKAT/ GOL/ RUANG.',
						'labelWidth'=>150, 
						'value'=>
						"<div id='cont_gd'>".cmbQuery('pangkatakhir','',$queryPangkat,'style="width:250px;"onchange="'.$this->Prefix.'.pilihPangkat()"','--PILIH--')."&nbsp&nbsp"."<input type='text' name='golang_akhir' style='width:40px;' id='golang_akhir' size=1 value='' readonly>
					
						</div>",
						 ),					
						
			
			'jabatan' => array( 
						'label'=>'JABATAN',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='jabatan' id='jabatan' value='".$dt['jabatan']."' style='width:300px;'>
						
						</div>", 
						 ),				
						
						
			'eselon' => array( 
						'label'=>'ESELON',
						'labelWidth'=>150, 
						'value'=>cmbArray('eselon_akhir',$dt['eselon'],$this->arrEselon,'--PILIH--','style=width:130px;'),
					//	'value'=>cmbArray('status',$dt['status'],$this->stStatus,'--PILIH Status--',''), 
						),
														 
			);
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' name='Id_skpd' id='Id_skpd'  value='".$Id."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	function setMenuView(){
		
			}
		
	//daftar =================================	
	function setKolomHeader($Mode=1, $Checkbox=''){
	$NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox
	   <th class='th01' width='450' align='center'>NAMA SKPD</th>		
	
	   <th class='th01' width='350' align='center'>NAMA PEGAWAI</th>
	   <th class='th01' width='350' align='center'>NIP</th>
	   <th class='th01' width='350' align='center'>PANGKAT/GOL/RUANG</th>
	   <th class='th01' width='350' align='center'>JABATAN</th>
	   <th class='th01' width='350' align='center'>ESELON</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	// $jabatan = $isi['jabatan'] == 1?'Kepala Dinas': 'Pengurus Barang'; 
	
	$c1=$isi['c1'];
	$c=$isi['c'];
	$d=$isi['d'];
	$e=$isi['e'];
	$e1=$isi['e1'];
	
	
	 $skpd=mysql_fetch_array(mysql_query(" select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d='$d' and e='$e' and e1='$e1' "));
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	/* $Koloms[] = array('align="center"',genNumber($isi['c'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['d'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e1'],3));*/
	 $Koloms[] = array('align="left"',$skpd['nm_skpd']);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 $Koloms[] = array('align="left"',$isi['nip']);
	 $Koloms[] = array('align="left"',$isi['pangkat'].' / '.$isi['gol'].' / '.$isi['ruang']);
	 $Koloms[] = array('align="left"',$isi['jabatan']);
	 $Koloms[] = array('align="left"',$isi['eselon']);
	
	 
	 return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	/*$fmBIDANG = cekPOST('fmBIDANG');
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
			<td style='width:120px'>BIDANG</td><td style='width:10px'>:</td>
			<td>".
			cmbQuery1("fmBIDANG",$fmBIDANG,"select f,nm_barang from ref_barang where f!='00' and g ='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmKELOMPOK",$fmKELOMPOK,"select g,nm_barang from ref_barang where f='$fmBIDANG' and g !='00' and h = '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBKELOMPOK",$fmSUBKELOMPOK,"select h,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h != '00' and i='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td>SUB SUB KELOMPOK</td><td>:</td>
			<td>".
			cmbQuery1("fmSUBSUBKELOMPOK",$fmSUBSUBKELOMPOK,"select i,nm_barang from ref_barang where f='$fmBIDANG' and g ='$fmKELOMPOK' and h = '$fmSUBKELOMPOK' and i!='00' and j='$Main->KODEBARANGJ'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>";
			/*"<div class='FilterBar'>".
			"<table style='width:100%'>
			<tr><td>
				Kode Barang : <input type='text' id='fmKODE' name='fmKODE' value='".$fmKODE."' maxlength='14' size=20px>&nbsp
				Nama Barang : <input type='text' id='fmBARANG' name='fmBARANG' value='".$fmBARANG."' size=30px>&nbsp
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";	*/		
	
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
	$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
	$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
	$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arrOrder = array(
	  	          array('1','NIP'),
			     	array('2','Nama'),
					);
	$arr = array(
			//array('selectAll','Semua'),	
			array('selectNIP','NIP'),	
			array('selectNama','Nama'),		
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
		$ref_tandatanganSkpdfmUrusan = $_REQUEST['ref_tandatanganSkpdfmUrusan'];
		$ref_tandatanganSkpdfmSKPD = $_REQUEST['ref_tandatanganSkpdfmSKPD'];
		$ref_tandatanganSkpdfmUNIT = $_REQUEST['ref_tandatanganSkpdfmUNIT'];
		$ref_tandatanganSkpdfmSUBUNIT = $_REQUEST['ref_tandatanganSkpdfmSUBUNIT'];
		$ref_tandatanganSkpdfmSEKSI = $_REQUEST['ref_tandatanganSkpdfmSEKSI'];
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			case 'selectNIP': $arrKondisi[] = " nip like '%$fmPILCARIvalue%'"; break;	
			case 'selectNama': $arrKondisi[] = " nama like '%$fmPILCARIvalue%'"; break;	
								 	
		}
		
		if($ref_tandatanganSkpdfmUrusan!='00' and $ref_tandatanganSkpdfmUrusan !='' and $ref_tandatanganSkpdfmUrusan!='0'){
			$arrKondisi[]= "c1='$ref_tandatanganSkpdfmUrusan'";
			if($ref_tandatanganSkpdfmSKPD!='00' and $ref_tandatanganSkpdfmSKPD !='')$arrKondisi[]= "c='$ref_tandatanganSkpdfmSKPD'";
			if($ref_tandatanganSkpdfmUNIT!='00' and $ref_tandatanganSkpdfmUNIT !='')$arrKondisi[]= "d='$ref_tandatanganSkpdfmUNIT'";
			if($ref_tandatanganSkpdfmSUBUNIT!='00' and $ref_tandatanganSkpdfmSUBUNIT !='')$arrKondisi[]= "e='$ref_tandatanganSkpdfmSUBUNIT'";
			if($ref_tandatanganSkpdfmSEKSI!='00' and $$ref_tandatanganSkpdfmSEKSI !='')$arrKondisi[]= "e1='$$ref_tandatanganSkpdfmSEKSI'";
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
			case '1': $arrOrders[] = " nip $Asc1 " ;break;
			case '2': $arrOrders[] = " nama $Asc1 " ;break;
			
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
$ref_tandatangan = new ref_tandatanganObj();
?>