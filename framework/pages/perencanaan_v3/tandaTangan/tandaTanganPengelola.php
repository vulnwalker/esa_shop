<?php

class tandaTanganPengelolaBarang_v3Obj  extends DaftarObj2{	
	var $Prefix = 'tandaTanganPengelolaBarang_v3';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'tandatanganpengelolabarang_v3'; //daftar
	var $TblName_Hapus = 'tandatanganpengelolabarang_v3';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'TANDA TANGAN';
	var $PageIcon = 'images/perencanaan_ico.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='tandaTanganPengelolaBarang_v3.xls';
	var $Cetak_Judul = 'TANDA TANGAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'tandaTanganPengelolaBarang_v3Form'; 
	var $kdbrg = '';	
	var $arrEselon = array( 
		array('1','ESELON I'),
		array('2','ESELON II'),
		array('3','ESELON III'),
		array('4','ESELON IV'),
		array('5','ESELON V')
		);
				
	function setTitle(){
		return 'TANDA TANGAN  PENGELOLA BARANG';
	}
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	 function setPage_HeaderOther(){
   		
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=tandaTanganKuasaPenggunaBarang_v3\" title='TANDA TANGAN Pengelola BARANG'  > TANDA TANGAN KUASA PENGGUNA BARANG </a> |
	<A href=\"pages.php?Pg=tandaTanganPenggunaBarang_v3\" title='TANDA TANGAN Pengelola BARANG'   > TANDA TANGAN PENGGUNA BARANG </a> |
	<A href=\"pages.php?Pg=tandaTanganPengelolaBarang_v3\" title='TANDA TANGAN PENGGUNA BARANG'  style='color : blue;' > TANDA TANGAN PENGELOLA BARANG </a> |
	&nbsp&nbsp&nbsp	
	</td></tr>
	</table>";
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
	 $nama= $_REQUEST['nama'];
	  $kategori =$_REQUEST['kategori'];
	  $nip =$_REQUEST['nip'];
	 
	 
	 if( $err=='' && $nama =='' ) $err= 'NAMA Belum Di Isi !!';
	 if( $err=='' && $nip =='' ) $err= 'NIP Belum Di Isi !!';
	
	 
			if($fmST == 0){
			
				if($err==''){
					$data = array(
								  
								  'nama' => $nama,
								  'kategori' => $kategori,
								  'nip' => $nip
								 
								);
					mysql_query(VulnWalkerInsert($this->TblName,$data));
					$cek = VulnWalkerInsert($this->TblName,$data);
				}
			}else{						
				if($err==''){
					$data = array(
								  
								  'nama' => $nama,
								  'nip' => $nip
								 
								);
					mysql_query(VulnWalkerUpdate($this->TblName,$data, "id = '$idplh'"));
			
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
			 <script type='text/javascript' src='js/perencanaan_v3/tandaTangan/tandaTanganPengelola.js' language='JavaScript' ></script>
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
		foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 }
		
		$dt['c1'] = $cmbUrusan;
		$dt['c'] = $cmbBidang;
		$dt['d'] = $cmbSKPD;
		$dt['kategori'] = $kategori;
		$dt['nip'] = $nip;
		if(empty($kategori))$err = "Kategori Belum Di Pilih";
		
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
		$aqry = "SELECT * FROM  $this->TblName WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	
	function setForm($dt){	
		global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 400;
	 $this->form_height = 100;
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
		$queryPangkat2="select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from tandaTanganPengelolaBarang_v3 where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'";
	//	$queryPangkat2=mysql_fetch_array(mysql_query("select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from tandaTanganPengelolaBarang_v3 where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'"));
		$cek.="select pangkat,concat(pangkat,' (',gol,'/',ruang,')')as nama from tandaTanganPengelolaBarang_v3 where pangkat='".$dt['pangkat']."' and gol='".$dt['gol']."' and ruang='".$dt['ruang']."'";
       //items ----------------------
		 
		//$qry_jabatan = "SELECT Id, nama FROM ref_jabatan WHERE c1='$c1' AND c='$c' AND d='$d' ";
		$querygedung="";
		$datc1=$queryc1['c1'].".".$queryc1['nm_skpd'];
		$datc=$queryc['c'].".".$queryc['nm_skpd'];
		$datd=$queryd['d'].".".$queryd['nm_skpd'];
		$date=$querye['e'].".".$querye['nm_skpd'];
		$date1=$querye1['e1'].".".$querye1['nm_skpd'];
		
		 $this->form_fields = array(
			
			
			'nama' => array( 
						'label'=>'NAMA',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='nama' id='nama' value='".$dt['nama']."' style='width:300px;'>
						
						</div>", 
						 ),	
						 array( 
						'label'=>'KATEGORI',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='fd' id='fd' value='".$dt['kategori']."' style='width:300px;' readonly>
						<input type ='hidden' name='kategori' id='kategori' value='".$dt['kategori']."'>
						</div>", 
						 ),	
						 array( 
						'label'=>'NIP',
						'labelWidth'=>150, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='nip' id='nip' value='".$dt['nip']."' style='width:300px;'>
						
						</div>", 
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
	
	   <th class='th01' width='350' align='center'>NAMA</th>
	   <th class='th01' width='350' align='center'>KATEGORI</th>
	   <th class='th01' width='350' align='center'>NIP</th>
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
	
	
	 $skpd=mysql_fetch_array(mysql_query(" select nm_skpd from ref_skpd where c1='$c1' and c='$c' and d='$d'  "));
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="left"',$isi['nama']);
	 $Koloms[] = array('align="left"',$isi['kategori']);
	 $Koloms[] = array('align="left"',$isi['nip']);
	
	 
	 return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	
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
	
	$selectedC1 = $_REQUEST['cmbUrusan'];
	$selectedC  = $_REQUEST['cmbBidang'];
	$selectedD = $_REQUEST['cmbSKPD'];

	
	
	
	
	
	foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			}
	
	
		
		$arrayKategori = array(
						   array('PEJABAT','PEJABAT'),
						   array('PENGURUS','PENGURUS'),
						   array('PENGELOLA','PENGELOLA')
					);
		$cmbKategori = cmbArray('kategori',$_REQUEST['kategori'],$arrayKategori,'-- KATEGORI--',"onchange=$this->Prefix.refreshList(true);");

		$textNama = "<input type='text'  style='size:200px;'name ='filterName' id = 'filterName' value='$filterName'> &nbsp &nbsp <button type='button' onclick ='$this->Prefix.refreshList(true);'>Cari</button> ";
	
	$TampilOpt = "<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>KATEGORI</td>
			<td>:</td>
			<td style='width:86%;'>
			".$cmbKategori."
			</td>
			</tr></table></div>".
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>NAMA </td>
			<td>:</td>
			<td style='width:86%;'> 
			".$textNama."
			</td>
			</tr>
			
			
			
			
			
			
			
			</table>".
			"</div>"
			;
			
			
			/*."<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td>KATEGORI</td>
			<td>:</td>
			<td style='width:86%;'>
			".$cmbKategori."
			</td>
			</tr>"*/
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();	
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		foreach ($_REQUEST as $key => $value) { 
				  $$key = $value; 
			 }
		if(!empty($cmbSubUnit)){
			$arrKondisi[] = " c1 = '$cmbUrusan' and c ='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' and e1='$cmbSubUnit'";
		}elseif(!empty($cmbUnit)){
			$arrKondisi[] = " c1 = '$cmbUrusan' and c ='$cmbBidang' and d='$cmbSKPD' and e='$cmbUnit' ";
		}elseif(!empty($cmbSKPD)){
			$arrKondisi[] = " c1 = '$cmbUrusan' and c ='$cmbBidang' and d='$cmbSKPD'  ";
		}elseif(!empty($cmbBidang)){
			$arrKondisi[] = " c1 = '$cmbUrusan' and c ='$cmbBidang'   ";
		}elseif(!empty($cmbUrusan)){
			$arrKondisi[] = " c1 = '$cmbUrusan' ";
		}
		
		if(!empty($filterName))$arrKondisi[] = " nama like '$filterName%'";
		
		if(!empty($kategori))$arrKondisi[] = " kategori ='$kategori'";
		
		
			
		
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
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
}
$tandaTanganPengelolaBarang_v3 = new tandaTanganPengelolaBarang_v3Obj();
?>