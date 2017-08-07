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
	var $KeyFields = array('b1,c,d,e,e1');
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
	/*function setMenuView(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";
	}*/
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
     $kode1= $_REQUEST['b1'];	
	 $kode2= $_REQUEST['c'];
	 $kode3= $_REQUEST['d'];
	 $kode4= $_REQUEST['e'];
	 $kode5= $_REQUEST['e1'];
	 $nama_skpd = $_REQUEST['nama_skpd'];
	
	 
	 if( $err=='' && $kode1 =='' ) $err= 'Kode 1 Belum Di Isi !!';
	 if( $err=='' && $kode2 =='' ) $err= 'Kode 2 Belum Di Isi !!';
	 if( $err=='' && $kode3 =='' ) $err= 'Kode 3 Belum Di Isi !!';
	 if( $err=='' && $kode4 =='' ) $err= 'Kode 4 Belum Di Isi !!';
	 if( $err=='' && $kode5 =='' ) $err= 'Kode 5 Belum Di Isi !!';
	 if( $err=='' && $nama_skpd =='' ) $err= 'nama skpd Belum Di Isi !!';
	 	
	
	// if($err=='' && $kode_skpd =='' ) $err= 'Kode Skpd belum diisi';	 
	 if(strlen($kode1)!=2 || strlen($kode2)!=2 || strlen($kode3)!=2 || strlen($kode4)!=2 ||strlen($kode5)!=3) $err= 'Format Kode SKPD salah bro';	
			for($j=0;$j<5;$j++){
	//urutan kode skpd 	
		if($j==0){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1!='00' and c ='00' and d ='00' and e ='00' and e1 ='000' Order By b1 DESC limit 1"));
			if($kode1=='00') {$err= 'Format Kode urusan salah';}
			elseif($kode1>sprintf("%02s",$ck['b1']+1)){ $err= 'Format Kode urusan Harus berurutan';}
				
		}elseif($j==1){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1='".$kode1."' and c !='00' and d ='00' and e ='00' and e1 ='000' Order By c DESC limit 1"));	
			if ($kode2>sprintf("%02s",$ck['c']+1)) {$err= 'Format Kode bidang Harus berurutan';}		
			
		}elseif($j==2){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1='".$kode1."' and c ='".$kode2."' and d !='00' and e ='00' and e1 ='000' Order By d DESC limit 1"));			
			if ($kode3>sprintf("%02s",$ck['d']+1)) {$err= 'Format Kode SKPD Harus berurutan';}		
				
		}elseif($j==3){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1='".$kode1."' and c ='".$kode2."' and d ='".$kode3."' and e!='00' and e1='000' Order By e DESC limit 1"));	
			if ($kode4>sprintf("%02s",$ck['e']+1)) {$err= 'Format Kode unit Harus berurutan';}
		
		}elseif($j==4){
			$ck=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1='".$kode1."' and c ='".$kode2."' and d ='".$kode3."' and e ='".$kode4."' and e1!='000' Order By e1 DESC limit 1"));	
			if ($kode5>sprintf("%02s",$ck['e1']+1)) {$err= 'Format Kode SubUnit Harus berurutan';}
				
				
		}
	 }
	 
			
			
			if($fmST == 0){
			$ck1=mysql_fetch_array(mysql_query("Select * from ref_skpd where b1='$kode1' and c ='$kode2' and d ='$kode3' and e='$kode4' and e1='$kode5'"));
			if ($ck1>=1)$err= 'Gagal Simpan'.mysql_error();
				if($err==''){
					$aqry = "INSERT into ref_skpd (b1,c,d,e,e1,nm_skpd) values('$kode1','$kode2','$kode3','$kode4','$kode5','$nama_skpd')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_skpd SET nm_skpd='$nama_skpd' WHERE b1='$kode1' and c='$kode2' and d='$kode3' and e='$kode4' and e1='$kode5'";	$cek .= $aqry;
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
			"<script type='text/javascript' src='js/ref_skpd/ref_skpd.js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
  	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$b1=$kode[0];
		$c=$kode[1];
		$d=$kode[2];
		$e=$kode[3];
		$e1=$kode[4];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_skpd WHERE b1='$b1' and c='$c' and d='$d' and e='$e' and e1='$e1' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 450;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  }else{
		$this->form_caption = 'Edit';			
		$readonly='readonly';
					
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		$kode1=genNumber($dt['b1'],2);
		$kode2=genNumber($dt['c'],2);
		$kode3=genNumber($dt['d'],2);
		$kode4=genNumber($dt['e'],2);
		$kode5=genNumber($dt['e1'],3);
		$nama_skpd=$dt['nm_skpd'];
		
	 //items ----------------------
	  $this->form_fields = array(
			'kode' => array( 
						'label'=>'Kode SKPD',
						'labelWidth'=>150, 
						//'value'=>$dt['kode'],
						//'type'=>'text',
						'value'=>
						"<input type='text' name='b1' id='b1' size='5' maxlength='2' value='".$kode1."' $readonly>&nbsp
						<input type='text' name='c' id='c' size='5' maxlength='2' value='".$kode2."' $readonly>&nbsp
						<input type='text' name='d' id='d' size='5' maxlength='2' value='".$kode3."' $readonly>&nbsp
						<input type='text' name='e' id='e' size='5' maxlength='2' value='".$kode4."' $readonly>&nbsp
						<input type='text' name='e1' id='e1' size='5' maxlength='3' value='".$kode5."' $readonly>"
						 ),
			
			'nama' => array( 
						'label'=>'Nama SKPD',
						'labelWidth'=>200, 
						
						
						'value'=>"<input type='text' name='nama_skpd' id='nama_skpd' size='50' maxlength='100' value='".$nama_skpd."'>",
						'row_params'=>"valign='top'",
						'type'=>'' 
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
	
	function setPage_HeaderOther(){
	return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
	<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
	<A href=\"pages.php?Pg=ref_skpd\" title='Skpd' style='color:blue'  >Skpd</a> |	
	<A href=\"pages.php?Pg=ref_skpd\" title='Daftar'  >Daftar</a> |
		
	</td></tr></table>";
	"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>";
	
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='400' colspan='5'>Kode SKPD</th>
	   <th class='th01' width='900' align='cente'>Urusan SKPD</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="center"',genNumber($isi['b1'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['c'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['d'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['e1'],3));
	 $Koloms[] = array('align="left"',$isi['nm_skpd']);
	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	 
		
	 //data order ------------------------------
	
	 
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	//tgl bulan dan tahun
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
		//Cari 
		$isivalue=explode('.',$fmPILCARIvalue);
		switch($fmPILCARI){			
			case 'selectKode': $arrKondisi[] = " b1='".$isivalue[0]."' and c='".$isivalue[1]."' and d='".$isivalue[2]."' and e='".$isivalue[3]."' and e1='".$isivalue[4]."'"; break;
			case 'selectNama': $arrKondisi[] = " nm_skpd like '%$fmPILCARIvalue%'"; break;						 	
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
			case '1': $arrOrders[] = " b1,c,d,e,e1 $Asc1 " ;break;
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