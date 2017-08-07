<?php

class refstandarhargaObj  extends DaftarObj2{	
	var $Prefix = 'refstandarharga';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_std_harga'; //daftar
	var $TblName_Hapus = 'ref_std_harga';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Standar Satuan Harga Barang';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='refstandarharga.xls';
	var $Cetak_Judul = 'refstandarharga';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'refstandarhargaForm'; 
	var $kdbrg = '';	
			
	function setTitle(){
		return 'Standar Satuan Harga Barang';
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
		
	 foreach ($_REQUEST as $key => $value) { 
		  			$$key = $value; 
	 } 
	 $arrayKode = explode(".",$kodeBarang);
	 $f=$arrayKode[0];
	 $g=$arrayKode[1];
	 $h=$arrayKode[2];
	 $i=$arrayKode[3];
	 $j=$arrayKode[4];
	 $tgl_update = explode("-",$tanggalUpdate);
	 if(empty($spesifikasi) || empty($standarSatuanHarga) || empty($tahunMulaiBerlaku)) $err="Lengkapi";	
		if($err==''){
		
			if($fmST == 0){
				$data = array('f'=>$f,
							  'g'=>$g,
							  'h'=>$h,
							  'i'=>$i,
							  'j'=>$j,
							  'spesifikasi'=>$spesifikasi,
							  'standar_satuan_harga'=>$standarSatuanHarga,
							  'tahun_mulai_berlaku'=>$tahunMulaiBerlaku,
							  'tgl_update'=>$tgl_update[2]."-".$tgl_update[1]."-".$tgl_update[0],
							  'user'=>$user
							  );
				 mysql_query(VulnWalkerInsert("ref_std_harga",$data));
				 $cek .=VulnWalkerInsert("ref_std_harga",$data);
			
			}else{						
				$data = array(	  'spesifikasi'=>$spesifikasi,
								  'standar_satuan_harga'=>$standarSatuanHarga,
								  'tahun_mulai_berlaku'=>$tahunMulaiBerlaku,
								  'tgl_update'=>$tgl_update[2]."-".$tgl_update[1]."-".$tgl_update[0],
								  'user'=>$user
								  );
					 mysql_query(VulnWalkerUpdate("ref_std_harga",$data,"concat(f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang'"));
					 $cek .=VulnWalkerInsert("ref_std_harga",$data);
			
			  }
			
			} 
					
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
			 "<script src='js/skpd.js' type='text/javascript'></script>
			 <script type='text/javascript' src='js/master/refstandarharga/refstandarharga.js' language='JavaScript' ></script>
			 <script type='text/javascript' src='js/master/refstandarharga/refbarang.js' language='JavaScript' ></script>
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
		
		$fm = $this->setForm($dt);		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
  	function setFormEdit(){
	$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$f=$kode[0];
		$g=$kode[1];
		$h=$kode[2];
		$i=$kode[3];
		$j=$kode[4];
		$this->form_fmST = 1;				
		//get data 
		$aqry = "SELECT * FROM  ref_std_harga WHERE  f='$f' and g='$g' and h='$h' and i='$i' and j='$j' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
		
	function setForm($dt){	
	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 900;
	 $this->form_height = 250;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$chmod644 = '';
	  }else{
		$this->form_caption = 'Edit';	
		$kodeBarang =$dt['f'].".".$dt['g'].".".$dt['h'].".".$dt['i'].".".$dt['j'];
		$getNamaBarang =  mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,'.',g,'.',h,'.',i,'.',j) = '$kodeBarang' "));
		$namaBarang = $getNamaBarang['nm_barang'];
		$satuan = $getNamaBarang['satuan'];	
		$spesifikasi = $dt['spesifikasi'];
		$standarSatuanHarga = $dt['standar_satuan_harga'];
		$tahunMulaiBerlaku = $dt['tahun_mulai_berlaku'];
		$chmod644 = 'disabled';			
	  }
	    //ambil data trefditeruskan
		$now = date("d-m-Y");
		$user = $_COOKIE['coID'];
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
       //items ----------------------
		 $this->form_fields = array(
			'kodeBarang' => array( 
								'label'=>'KODE DAN NAMA BARANG',
								'labelWidth'=>200, 
								'value'=>"<input type='text' name='kodeBarang' value='".$kodeBarang."' size='15px' id='kodeBarang' readonly>&nbsp
										  <input type='text' name='namaBarang' value='".$namaBarang."' size='70px' id='namaBarang' readonly>&nbsp
										  <input type='button' value='Cari' $chmod644 onclick ='".$this->Prefix.".Cari()' title='Cari Barang' >" 
									 ),
						 
			'satuan' => array(
						'label'=>'SATUAN',
						'labelWidth'=>200, 
						'value'=>$satuan,
						'param'=>"style= 'width:200px;' readonly",
						'type'=>'text'
									 ),
			'spesifikasi' => array(
						'label'=>'SPESIFIKASI',
						'labelWidth'=>60, 
						'value'=>"<textarea name='spesifikasi' id='spesifikasi' style='width:400px; height:40px;'>$spesifikasi</textarea>"
									 ),
			
			'standarSatuanHarga' => array( 
						'label'=>'STANDAR SATUAN HARGA',
						'labelWidth'=>120, 
						'value'=> "<input type='text' name='standarSatuanHarga' id='standarSatuanHarga' value='$standarSatuanHarga' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='document.getElementById(`bantu`).innerHTML = popupBarang.formatCurrency(this.value);' style='text-align:right' placeholder='Rp.'> <font color='red' id='bantu' name='bantu'></font>"  
							  ),
			 'tahunMulaiBerlaku' => array(
						'label'=>'TAHUN MULAI BERLAKU',
						'labelWidth'=>100,
						'value' => $tahunMulaiBerlaku,
						'param'=>"style= 'width:50px;' maxlength='4' onkeypress='return event.charCode  >= 48 && event.charCode <= 57' ",
						'type'=>'text'
						),
			 'tanggalUpdate' => array(
						'label'=>'TANGGAL UPDATE',
						'labelWidth'=>100, 
						'value'=>$now,
						'param'=>"style= 'width:100px;' readonly ",
						'type'=>'text'
						),
			 'user' => array(
						'label'=>'USER',
						'labelWidth'=>100, 
						'value'=>$user,
						'param'=>"style= 'width:100px;' readonly ",
						'type'=>'text'
						)
			  
			);
		//tombol
		$this->form_menubawah =	
			"<input type='hidden' name='Id_skpd' id='Id_skpd'  value='".$Id."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' > &nbsp &nbsp ".
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
	   <th class='th01' width='150' colspan='5'>KODE BARANG</th>
	   <th class='th01' width='450' align='center'>NAMA BARANG</th>
	   <th class='th01' width='400' align='center'>SATUAN</th>
	   <th class='th01' width='200' align='center'>SPESIFIKASI</th>
	   <th class='th01' width='200' align='center'>STANDAR SATUAN HARGA</th>
	   <th class='th01' width='200' align='center'>TAHUN MULAI BERLAKU</th>
	   <th class='th01' width='200' align='center'>TANGGAL UPDATE</th>
	   <th class='th01' width='200' align='center'>USER</th>
	   
	
	  
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center' ", $TampilCheckBox);
	 $Koloms[] = array('align="center"',genNumber($isi['f'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['g'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['h'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['i'],2));
	 $Koloms[] = array('align="center"',genNumber($isi['j'],3));
	 $f=$isi['f'];
	 $g=$isi['g'];
	 $h=$isi['h'];
	 $i=$isi['i'];
	 $j=$isi['j'];
	 $kodeBarang = $f."-".$g."-".$h."-".$i."-".$j;
	 $arrayGetNamabarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,'-',g,'-',h,'-',i,'-',j) = '$kodeBarang' "));
	 $Koloms[] = array('align="left"',$arrayGetNamabarang['nm_barang']);
	 $Koloms[] = array('align="left"',$arrayGetNamabarang['satuan']);
	 $Koloms[] = array('align="left"',$isi['spesifikasi']);
	 $Koloms[] = array('align="right"',number_format($isi['standar_satuan_harga'],2,',','.'));
	 $Koloms[] = array('align="center"',$isi['tahun_mulai_berlaku']);
	 $tanggalUpdate = explode("-",$isi['tgl_update']);
	 $Koloms[] = array('align="center"',$tanggalUpdate[2]."-".$tanggalUpdate[1]."-".$tanggalUpdate[0]);
	 $Koloms[] = array('align="left"',$isi['user']);
	 return $Koloms;
	}
	
	
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 

	$cmbAkun = '0';
	$cmbKelompok = '0';
	$cmbJenis = $_REQUEST['cmbJenis'];
	$cmbObyek = $_REQUEST['cmbObyek'];
	$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
	$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
	$cmbSubSubRincianObyek = $_REQUEST['cmbSubSubRincianObyek'];	
	$fmKODE = $_REQUEST['fmKODE'];	
	$fmBARANG = $_REQUEST['fmBARANG'];	

	
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
			"<div class='FilterBar'>".

			
			"<table style='width:100%'>
			<tr>
			<td style='width:170px;' >JENIS</td><td>:</td>
			<td>".
			cmbQuery1("cmbJenis",$cmbJenis,"select f as valueCmbJenis, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f != '00'  and g ='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td style='width:170px;'>OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbObyek",$cmbObyek,"select g as valueCmbObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g !='00' and h ='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
			</tr><tr>
			<td style='width:170px;'>RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbRincianObyek",$cmbRincianObyek,"select h as valueCmbRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h !='00' and i='00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			<tr>
			<td style='width:170px;'>SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubRincianObyek",$cmbSubRincianObyek,"select i as valueCmbSubRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i != '00' and j = '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			<tr>
			<td style='width:170px;'>SUB-SUB RINCIAN OBYEK</td><td>:</td>
			<td>".
			cmbQuery1("cmbSubSubRincianObyek",$cmbSubSubRincianObyek,"select j as valueCmbSubSubRincianObyek, nm_barang from ref_barang where f1 = '$cmbAkun' and f2 = '$cmbKelompok' and f = '$cmbJenis'  and g ='$cmbObyek' and h ='$cmbRincianObyek' and i='$cmbSubRincianObyek' and j != '000'","onChange=\"$this->Prefix.refreshList(true)\"",'Pilih','').
			"</td>
				</tr>
			
			</table>".
			"</div>".
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
		function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		$cmbAkun = $_REQUEST['cmbAkun'];
		$cmbKelompok = $_REQUEST['cmbKelompok'];
		$cmbJenis = $_REQUEST['cmbJenis'];
		$cmbObyek = $_REQUEST['cmbObyek'];
		$cmbRincianObyek = $_REQUEST['cmbRincianObyek'];
		$cmbSubRincianObyek = $_REQUEST['cmbSubRincianObyek'];	
		$cmbSubSubRincianObyek = $_REQUEST['cmbSubSubRincianObyek'];	
		$fmMERK = $_REQUEST['fmMERK'];
		$fmTYPE = $_REQUEST['fmTYPE'];		
		
		switch($fmPILCARI){
			case 'selectfg': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectbarang': $arrKondisi[] = " nama_barang like '%".$fmPILCARIvalue."%'"; break;					 	
		}
		
		
		
		if(empty($cmbJenis)) {
			$cmbObyek='';
			$cmbRincianObyek='';
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "f =$cmbJenis";	
		}
		if(empty($cmbObyek)) {
			$cmbRincianObyek='';
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "g =$cmbObyek";	
		}
		if(empty($cmbRincianObyek)) {
			$cmbSubRincianObyek = '';
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "h =$cmbRincianObyek";	
		}
		if(empty($cmbSubRincianObyek)) {
			$cmbSubSubRincianObyek = '';
		}else{
			$arrKondisi[]= "i =$cmbSubRincianObyek";	
		}
		if(empty($cmbSubSubRincianObyek)) {
		}else{
			$arrKondisi[]= "j =$cmbSubSubRincianObyek";	
		}
		
			
		
		if(!empty($_POST['fmKODE'])) $arrKondisi[] = " concat(f,g,h,i,j) like '".str_replace('.','',$_POST['fmKODE'])."%'";					
		if(!empty($_POST['fmBARANG'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANG']."%'";	

		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		/*switch($fmORDER1){
			case '': $arrOrders[] = " concat(f,g,h,i,j) ASC " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
		
		}*/
			$arrOrders[] = " concat(f,g,h,i,j) ASC " ;
			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					

		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; 
		$Limit = $Mode == 3 ? '': $Limit;
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$refstandarharga = new refstandarhargaObj();

?>